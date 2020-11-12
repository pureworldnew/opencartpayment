<?php

if (!defined('DIR_SYSTEM')) exit;

class EmailTemplate {

	public static $version = '2.6.17.5';

	/**
	 * Replace Language variable with translated text
	 *
	 * @var bool
	 */
	public static $replace_language_vars = false;

	public static $original_templates = array(
		// Customer
		'customer.register',
		'customer.forgotten',
		'information.contact_customer',
		'admin.customer_approve',
		'admin.customer_create',
		'admin.customer_reward',
		'admin.customer_transaction',
		'admin.newsletter',

		// Order
		'order.customer',
		'order.admin',
		'order.return',
		'order.update',
		'order.voucher',
		'admin.return_history',
		'admin.voucher',

		// Affiliate
		'admin.affiliate_approve',
		'admin.affiliate_transaction',
		'affiliate.forgotten',
		'affiliate.register',

		// Admin
		'customer.register_admin',
		'information.contact',
		'affiliate.register_admin',
		'product.review',

		// Openbay
		'order.openbay.confirm',
		'order.openbay.update',
		'openbay.admin'
	);

	public $data = array();
	public $language_data =  array();

	private $registry;
	private $request;
	private $config;
	private $load;
	private $model;
	private $oMail;

	private $debug = false;

	private $built = false;

	private $parse_shortcodes = true;
	private $insert_shortcodes = null;

	private $html = null;
	private $content = '';
	private $css = null;
	private $wrapper_tpl = '_main.tpl';

	private $emailtemplate_id;
	private $emailtemplate_config_id;
	private $emailtemplate_default_id;
	private $emailtemplate_log_id;
	private $emailtemplate_log_enc;
	private $emailtemplate_shortcodes = array();

	private $language_id;
	private $store_id;
	private $customer_id;
	private $customer_group_id;

	/**
	 * @param Request $request
	 */
	public function __construct(Request $request, Registry $registry) {
		$this->registry = $registry;
		$this->request = $request;
		$this->config = $registry->get('config');
		$this->load = $registry->get('load');

		$this->language_id = $this->config->get("config_language_id");

		$this->store_id = $this->config->get("config_store_id");

		$oCustomer = $registry->get('customer');

		if ($oCustomer && $oCustomer instanceof Customer && $oCustomer->isLogged()) {
		    $this->customer_id = $oCustomer->getId();
		    $this->customer_group_id = $oCustomer->getGroupId();
		} else {
		    $this->customer_id = 0;
		    $this->customer_group_id = $this->config->get('config_customer_group_id');
		}

		$this->load->model('module/emailtemplate');
		$this->model = new ModelModuleEmailTemplate($this->registry);

		if (isset($request->server['HTTPS']) && (($request->server['HTTPS'] == 'on') || ($request->server['HTTPS'] == '1'))) {
			$this->data['server'] = defined('HTTPS_CATALOG') ? HTTPS_CATALOG : HTTPS_SERVER;
		} else {
			$this->data['server'] = defined('HTTP_CATALOG') ? HTTP_CATALOG : HTTP_SERVER;
		}
	}

	/**
	 * Load Template + Config
	 *
	 */
	public function load($data, $withfallback = true) {
		// Reset
		$this->css = null;
		$this->emailtemplate_id = null;
		$this->emailtemplate_config_id = null;
		$this->emailtemplate_log_id = null;

	   	if (is_array($data)) {
			$filter = array();

			if (isset($data['emailtemplate_config_id']) && $data['emailtemplate_config_id']) {
				$filter['emailtemplate_config_id'] = $this->emailtemplate_config_id = $data['emailtemplate_config_id'];
			}

			if (isset($data['emailtemplate_id'])) {
				$filter['emailtemplate_id'] = $data['emailtemplate_id'];
			}

			if (isset($data['order_status_id'])) {
				$filter['order_status_id'] = $data['order_status_id'];
			}

			if (isset($data['key'])) {
				$filter['emailtemplate_key'] = $data['key'];
			}

			if (isset($data['emailtemplate_key'])) {
				if (is_numeric($data['emailtemplate_key'])) {
					$filter['emailtemplate_id'] = $data['emailtemplate_key'];
				} else {
					$filter['emailtemplate_key'] = $data['emailtemplate_key'];
				}
			}

			if (!empty($data['language_id'])) {
				$this->language_id = (int)$data['language_id'];
			}

			if (isset($data['store_id']) && $data['store_id'] >= 0) {
				$this->store_id = (int)$data['store_id'];
			}

			if (!empty($data['customer_group_id'])) {
				$this->customer_group_id = (int)$data['customer_group_id'];
			}
		} elseif (is_numeric($data) && $data) {
			$filter = array('emailtemplate_id' => $data);
		} elseif (is_string($data) && $data) {
			$filter = array('emailtemplate_key' => $data);
		} elseif ($withfallback) {
			$filter = array('emailtemplate_id' => 1);
		} else {
			return false;
		}

		if($filter){
			$templates = $this->model->getTemplates($filter);
		}

		if (empty($templates)) {
			if (!$withfallback) {
				return false;
			}

			$templates = $this->model->getTemplates(array('emailtemplate_id' => 1));
		}

		$keys = array(
			'language_id' => $this->language_id,
			'customer_group_id' => $this->customer_group_id,
			'store_id' => $this->store_id
		);

		if (isset($data['order_status_id'])) {
			$keys['order_status_id'] = $data['order_status_id'];
		}

		foreach ($templates as &$template) {
			$template['power'] = 0;

			foreach ($keys as $_key => $_value) {
				$template['power'] = $template['power'] << 1;

				if (isset($template[$_key]) && $template[$_key] == $_value) {
					$template['power'] |= 1;
				}
			}

			if (!empty($template['emailtemplate_condition'])) {
				if (!is_array($template['emailtemplate_condition'])) {
					$template['emailtemplate_condition'] = unserialize(base64_decode($template['emailtemplate_condition']));
				}
				if (is_array($template['emailtemplate_condition'])) {
					foreach($template['emailtemplate_condition'] as $condition) {
						$template['power'] = $template['power'] << 1;
						$key = trim($condition['key']);

						if (isset($this->data[$key])) {
							switch(html_entity_decode($condition['operator'], ENT_COMPAT, "UTF-8")) {
								case '==':
									if ($this->data[$key] == $condition['value'])
										$template['power'] |= 1;
									break;
								case '!=':
									if ($this->data[$key] != $condition['value'])
										$template['power'] |= 1;
									break;
								case '>':
									if ($this->data[$key] > $condition['value'])
										$template['power'] |= 1;
									break;
								case '<':
									if ($this->data[$key] < $condition['value'])
										$template['power'] |= 1;
									break;
								case '>=':
									if ($this->data[$key] >= $condition['value'])
										$template['power'] |= 1;
									break;
								case '<=':
									if ($this->data[$key] <= $condition['value'])
										$template['power'] |= 1;
									break;
								case 'IN':
									$haystack = explode(',', $condition['value']);
									if (is_array($haystack) && in_array($this->data[$key], $haystack))
										$template['power'] |= 1;
									break;
								case 'NOTIN':
									$haystack = explode(',', $condition['value']);
									if (is_array($haystack) && !in_array($this->data[$key], $haystack))
										$template['power'] |= 1;
									break;
							}
						}
					}
				}
			}
		}
		unset($template);

		// template with highest power
		$this->data['emailtemplate'] = $templates[0];
		$template_default = array();
		foreach ($templates as $template) {
			if ($template['emailtemplate_default']) {
				$this->emailtemplate_default_id = $template['emailtemplate_id'];
				$template_default = $template;
			}

			if ($this->data['emailtemplate']['power'] < $template['power']) {
				$this->data['emailtemplate'] = $template;
			}
		}

		if ($this->debug) {
			$debug = "\n\nFILTER\n" . print_r($filter, true);

			if (count($templates) > 1) {
				$debug .= "\n\nTEMPLATES\n" . print_r($templates, true);
			}

			$debug .= "\n\nKEYS\n" . print_r($keys, true);

			$debug .= "\n\nTEMPLATE\n" . print_r($this->data['emailtemplate'], true);

			file_put_contents(DIR_LOGS . '/emailtemplate-debug.log', $debug);
		}

		foreach($this->data['emailtemplate'] as $key => $val) {
			if (empty($val) && !empty($template_default[$key])) {
				$val = $template_default[$key];
			}

			if (strpos($key, 'emailtemplate_') === 0 && substr($key, -3) != '_id') {
				unset($this->data['emailtemplate'][$key]);
				$this->data['emailtemplate'][substr($key, strlen('emailtemplate_'))] = $val;
			} else {
				$this->data['emailtemplate'][$key] = $val;
			}
		}

		$this->emailtemplate_id = $this->data['emailtemplate']['emailtemplate_id'];

		$description_default = array();
		if ($this->emailtemplate_default_id != $this->emailtemplate_id) {
			$description_default = $this->model->getTemplateDescription(array(
				'emailtemplate_id' => $this->emailtemplate_default_id,
				'language_id' => $this->language_id
			), 1);
		}

		$description = $this->model->getTemplateDescription(array(
			'emailtemplate_id' => $this->emailtemplate_id,
			'language_id' => $this->language_id
		), 1);

		if (!$description) {
			$description = $this->model->getTemplateDescription(array(
				'emailtemplate_id' => $this->emailtemplate_id
			), 1);

			// Load empty description
			foreach ($description as $key => $val) {
				$description[$key] = '';
			}
		}

		if ($description) {
			foreach($description as $key => $val) {
				if (isset($this->data['emailtemplate'][$key])) continue;

				if (empty($val) && !empty($description_default[$key])) {
					$val = $description_default[$key];
				}

				if (strpos($key, 'emailtemplate_description_') === 0 && substr($key, -3) != '_id') {
					$this->data['emailtemplate'][substr($key, strlen('emailtemplate_description_'))] = $val;
				} else {
					$this->data['emailtemplate'][$key] = $val;
				}
			}
		}

		if (empty($data['emailtemplate_config_id']) && $this->data['emailtemplate']['emailtemplate_config_id']) {
			$this->emailtemplate_config_id = $this->data['emailtemplate']['emailtemplate_config_id'];
		}

		if ($this->emailtemplate_config_id) {
			$template_config = $this->model->getConfig($this->emailtemplate_config_id);
		} else {
			$config_load = array('store_id' => $this->store_id, 'language_id' => $this->language_id);

			$configs = $this->model->getConfigs($config_load);

			if (!$configs) {
				$configs = $this->model->getConfigs();
			}

			if ($configs) {
				if (count($configs) == 1) {
					$template_config = $configs[0];
				} elseif (count($configs) > 1) {
					// Both (language + store)
					foreach ($configs as $config) {
						if ($config['language_id'] == $this->language_id && $config['store_id'] == $this->store_id) {
							$template_config = $config;
							break;
						}
					}

					// Either OR (language + store)
					if (empty($template_config)) {
						foreach ($configs as $config) {
							if (($config['language_id'] == $this->language_id || $config['language_id'] == 0) && ($config['store_id'] == $this->store_id || $config['store_id'] == 0)) {
								$template_config = $config;
								break;
							}
						}
					}

					unset($config);
				}
			}
		}

		if (empty($template_config)) {
			$template_config = $this->model->getConfig(1);
		}

		$this->data['config'] = $this->model->formatConfig($template_config);

		$this->emailtemplate_config_id = $this->data['config']['emailtemplate_config_id'];

		foreach($this->data['config'] as $key => $val) {
			if (strpos($key, 'emailtemplate_config_') === 0 && substr($key, -3) != '_id') {
				unset($this->data['config'][$key]);
				$this->data['config'][substr($key, 21)] = $val; #21=strlen('emailtemplate_config_')
			} else {
				$this->data['config'][$key] = $val;
			}
		}

		if ($this->emailtemplate_log_id === null && ($this->data['emailtemplate']['log'] || $this->data['config']['log'])) {
			$this->emailtemplate_log_id = $this->model->getLastTemplateLogId() + 1;
		}

		$config_keys = array('title', 'name', 'url', 'ssl', 'owner', 'address', 'email', 'telephone', 'fax', 'country_id', 'zone_id', 'tax', 'tax_default', 'customer_price');

		if ($this->config->get('config_store_id') == $this->store_id) {
			foreach($config_keys as $_key) {
				if(isset($this->data['store_'.$_key])) continue;

				$this->data['store_'.$_key] = $this->config->get('config_'.$_key);
			}

			$this->data['store_url'] = defined('HTTP_CATALOG') ? HTTP_CATALOG : HTTP_SERVER;

			$this->data['store_ssl'] = defined('HTTPS_CATALOG') ? HTTPS_CATALOG : HTTPS_SERVER;
		} else {
			$this->load->model('setting/store');
			$this->load->model('setting/setting');

			$this->model_setting_store = new ModelSettingStore($this->registry);
			$this->model_setting_setting = new ModelSettingSetting($this->registry);

			$store_info = array_merge(
				$this->model_setting_setting->getSetting("config", $this->store_id),
				$this->model_setting_store->getStore($this->store_id)
			);

			foreach($config_keys as $_key) {
				if(isset($this->data['store_'.$_key])) continue;

				if (isset($store_info[$_key])) {
					$this->data['store_'.$_key] =  $store_info[$_key];
				} elseif (isset($store_info['config_'.$_key])) {
					$this->data['store_'.$_key] =  $store_info['config_'.$_key];
				} else {
					$this->data['store_'.$_key] =  '';
				}
			}

			if (!$this->data['store_ssl']) {
				$this->data['store_ssl'] = $this->data['store_url'];
			}
		}

		if (!$this->data['store_url']) {
			$this->data['store_url'] = $this->data['server'];
		}

		$this->load->model('tool/image');

		$this->model_tool_image = new ModelToolImage($this->registry);

		$this->model_tool_image->setUrl($this->data['store_url']);

		/* Bug in outlook https images
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->model_tool_image->setUrl($this->data['store_ssl']);
		} else {
			$this->model_tool_image->setUrl($this->data['store_url']);
		} */

		$this->data['title'] = $this->data['subject'] = $this->data['store_name'];

		if ($this->data['emailtemplate']['wrapper_tpl'] && file_exists($this->_getTemplatePath($this->data['emailtemplate']['wrapper_tpl']).$this->data['emailtemplate']['wrapper_tpl'])) {
			$this->wrapper_tpl = $this->data['emailtemplate']['wrapper_tpl'];
		} elseif ($this->data['config']['wrapper_tpl'] && file_exists($this->_getTemplatePath($this->data['config']['wrapper_tpl']).$this->data['config']['wrapper_tpl'])) {
			$this->wrapper_tpl = $this->data['config']['wrapper_tpl'];
		}

		if (is_null($this->insert_shortcodes) && $this->data['emailtemplate']['shortcodes'] == 0) {
			$this->insert_shortcodes = true;
		}

		$this->html = null;

		unset($this->data['preheader_preview']);

		return true;
	}

	/**
     * Check if template loaded
     * @return boolean
     */
    public function isLoaded() {
    	return (!empty($this->data['emailtemplate']));
    }

    /**
     * Load language file manually without building template
     */
    public function loadLanguage() {
    	$this->load->model('localisation/language');

    	$this->model_language = new ModelLocalisationLanguage($this->registry);

        $language = $this->model_language->getLanguage($this->language_id);

        $oLanguage = new Language($language['directory']);

        // In Admin Area
        if (defined('DIR_CATALOG')) {
        	if (substr($this->data['emailtemplate']['key'], 0, 6) == 'admin.') {
        		$dir =  DIR_LANGUAGE;
        	} else {
        		$dir = DIR_CATALOG . 'language/';
        	}
        } else {
        	if (substr($this->data['emailtemplate']['key'], 0, 6) == 'admin.') {
        		$dir =  defined('HTTP_ADMIN') ? HTTP_ADMIN . 'language/' : substr(DIR_SYSTEM, 0, -7) . 'admin/language/';
        	} else {
        		$dir = DIR_LANGUAGE;
        	}
        }
        $oLanguage->setPath($dir);

        // Default
        $oLanguage->load($language['directory']);

        // Template language files
        foreach (explode(',', $this->data['emailtemplate']['language_files']) as $language_file) {
        	$oLanguage->load($language_file);
        }

        $this->language_data = $oLanguage->load('mail/emailtemplate');
    }

	/**
     * Build template
     *
     * @return object
     */
    public function build() {
        if (!$this->isLoaded()) return false;

        $this->built = true;

    	if (empty($this->language_data)) {
    		$this->loadLanguage();
    	}

        // Shortcodes
    	if ($this->insert_shortcodes) {
    		$shortcodes = array();

    		foreach($this->data as $key => $value){
    			if (!isset($this->language_data[$key])) {
    				$shortcodes[$key] = $value;
    			}
    		}

    		// Hidden shortcodes
    		$hidden_shortcodes = array('emailtemplate', 'config', 'showcase_selection');
    		foreach ($hidden_shortcodes as $key) {
    			if (isset($shortcodes[$key])) {
    				unset($shortcodes[$key]);
    			}
    		}

        	$this->model->insertTemplateShortCodes($this->emailtemplate_id, $shortcodes);
        }

        $this->data['store_id'] = $this->store_id;
        $this->data['language_id'] = $this->language_id;
        $this->data['customer_id'] = $this->customer_id;
        $this->data['customer_group_id'] = $this->customer_group_id;

        // Shadow
        foreach(array('top','bottom','left','right') as $var) {
        	$cells = "";

        	if ($this->data['config']['shadow_'.$var]['start'] && $this->data['config']['shadow_'.$var]['end'] &&  $this->data['config']['shadow_'.$var]['length'] > 0) {
        		$gradient = $this->_generateGradientArray($this->data['config']['shadow_'.$var]['start'], $this->data['config']['shadow_'.$var]['end'], $this->data['config']['shadow_'.$var]['length']);

        		foreach($gradient as $hex => $width) {
        			switch($var) {
        				case 'top':
        				case 'bottom':
        					$cells .= "<tr class='emailShadow'><td bgcolor='#{$hex}' style='background:#{$hex}; height:1px; font-size:1px; line-height:0; mso-margin-top-alt:1px' height='1'> </td></tr>\n";
        					break;
        				default:
        					$cells .= "<td class='emailShadow' bgcolor='#{$hex}' style='background:#{$hex}; width:{$width}px !important; font-size:1px; line-height:0; mso-margin-top-alt:1px' width='{$width}'> </td>\n";
        					break;
        			}

        			$this->data['config']['shadow_'.$var]['bg'] = $cells;
        		}
        	}
        }

    	if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			if(defined('HTTPS_IMAGE')){
				$image_url = HTTPS_IMAGE;
			} else {
				$image_url = $this->data['store_ssl'] . 'image/';
			}
		} else {
			if(defined('HTTP_IMAGE')){
				$image_url = HTTP_IMAGE;
			} else {
				$image_url = $this->data['store_url'] . 'image/';
			}
		}

        foreach (array('top', 'bottom') as $v) {
            foreach (array('left', 'right') as $h) {
                if (!empty($this->data['config']['shadow_'.$v][$h.'_img'])) {
                    $this->data['config']['shadow_'.$v][$h.'_img'] = ($this->data['config']['shadow_'.$v][$h.'_img']) ? $image_url . $this->data['config']['shadow_'.$v][$h.'_img'] : '';
                    $this->data['config']['shadow_'.$v][$h.'_img_height'] = $this->data['config']['shadow_'.$v]['length'] + $this->data['config']['shadow_'.$v]['overlap'];
                    $this->data['config']['shadow_'.$v][$h.'_img_width'] = $this->data['config']['shadow_'.$h]['length'] + $this->data['config']['shadow_'.$h]['overlap'];
                }
            }
        }

        $this->data['config']['theme_dir'] = $this->data['config']['theme'] . '/template/mail/';

        $this->data['config']['head_text'] = html_entity_decode($this->data['config']['head_text'], ENT_QUOTES, 'UTF-8');
        $this->data['config']['page_footer_text'] = html_entity_decode($this->data['config']['page_footer_text'], ENT_QUOTES, 'UTF-8');
        $this->data['config']['footer_text'] = html_entity_decode($this->data['config']['footer_text'], ENT_QUOTES, 'UTF-8');

        if ($this->data['config']['showcase_title']) {
        	$this->data['config']['showcase_title'] = html_entity_decode($this->data['config']['showcase_title'], ENT_QUOTES, 'UTF-8');
        } elseif (isset($this->data['heading_showcase'])) {
        	$this->data['config']['showcase_title'] = html_entity_decode($this->data['heading_showcase'], ENT_QUOTES, 'UTF-8');
        } else {
        	$this->data['config']['showcase_title'] = '';
        }

        if (!empty($this->data['emailtemplate']['comment'])) {
        	$this->data['emailtemplate']['comment'] = html_entity_decode($this->data['emailtemplate']['comment'], ENT_QUOTES, 'UTF-8');
        }

        if (!empty($this->data['emailtemplate']['unsubscribe_text'])) {
        	$this->data['emailtemplate']['unsubscribe_text'] = html_entity_decode($this->data['emailtemplate']['unsubscribe_text'], ENT_QUOTES, 'UTF-8');
        }

        for($i=1; $i <= EmailTemplateDescriptionDAO::$content_count; $i++) {
        	if (!empty($this->data['emailtemplate']['content'.$i])) {
        		$this->data['emailtemplate']['content'.$i] = html_entity_decode($this->data['emailtemplate']['content'.$i], ENT_QUOTES, 'UTF-8');
        	}
        }

        $this->data['config']['body_bg_image'] = ($this->data['config']['body_bg_image']) ? $image_url .$this->data['config']['body_bg_image'] : '';

        $this->data['config']['header_bg_image'] = ($this->data['config']['header_bg_image']) ? $image_url .$this->data['config']['header_bg_image'] : '';

        $this->data['config']['email_full_width'] = $this->data['config']['email_width'] + ($this->data['config']['shadow_left']['length'] + $this->data['config']['shadow_right']['length']);

        if ($this->data['config']['logo']) {
	        if ($this->data['config']['logo_width'] && $this->data['config']['logo_height']) {
	        	$this->data['config']['logo'] = $this->model_tool_image->resize($this->data['config']['logo'], $this->data['config']['logo_width'], $this->data['config']['logo_height']);
	        } else {
	        	$this->data['config']['logo'] = $image_url .$this->data['config']['logo'];
	        }

	        $this->data['config']['logo'] = $this->data['config']['logo'];
        } else {
        	unset($this->data['config']['logo']);
        }

        $this->data['tracking'] = $this->getTracking();

        $this->data['store_url_tracking'] = $this->getTracking($this->data['store_url']);

        if ($this->emailtemplate_log_id) {
       		$this->emailtemplate_log_enc = substr(md5(uniqid(rand(), true)), 0, 20);

	        if ($this->data['config']['log_read']) {
	       		$this->data['emailtemplate']['tracking_img'] = $this->data['store_url'] . 'index.php?route=module/emailtemplate/record&id='.$this->emailtemplate_log_id . '&enc=' . $this->emailtemplate_log_enc;
	        }

	        if ($this->data['emailtemplate']['view_browser']) {
	        	$url = $this->data['store_url'] . 'index.php?route=module/emailtemplate/view&id='.$this->emailtemplate_log_id . '&enc=' . $this->emailtemplate_log_enc;

	        	$this->data['view_browser'] = str_replace('{$url}', $url, $this->data['config']['view_browser_text']);
	        }
        }

        // Showcase
        if ($this->data['config']['showcase'] && $this->data['emailtemplate']['showcase']) {
	        $this->data['text_rating'] = isset($this->language_data['text_rating']) ? $this->language_data['text_rating'] : '';

	        $this->data['showcase_selection'] = $this->model->getShowcase($this->data);

	        if (!empty($this->data['showcase_selection'])) {
	        	foreach($this->data['showcase_selection'] as &$showcase) {
	        		if ($showcase['url']) {
	        			$showcase['url_tracking'] = $this->getTracking($showcase['url']);
	        		}
	        	}
	        	unset($showcase);
	        }
        }

        if ($this->language_data) {
        	$this->data = array_merge($this->language_data, $this->data);
        }

    	if ($this->parse_shortcodes) {
	        $replace = $this->_fetchShortCodes();

	        foreach($this->data as $key => $val) {
	        	if (is_string($val) && strpos($val, '{$') !== false) {
	        		$this->data[$key] = str_replace($replace['find'], $replace['replace'], $val);
	        	}
	        }
	        foreach($this->data['emailtemplate'] as $key => $val) {
	        	if (is_string($val) && strpos($val, '{$') !== false) {
	        		$this->data['emailtemplate'][$key] = str_replace($replace['find'], $replace['replace'], $val);
	        	}
	        }
	        foreach($this->data['config'] as $key => $val) {
	        	if (is_string($val) && strpos($val, '{$') !== false) {
	        		$this->data['config'][$key] = str_replace($replace['find'], $replace['replace'], $val);
	        	}
	        }
        }

        return $this;
    }

    /**
     * Apply email template settings to Mail object
     *
     * @param object - Mail
     * @return object
     */
    public function hook(Mail $mail) {
    	if (!$this->isLoaded()) return $mail;

    	if (!$this->built) {
    		$this->build();
    	}

    	$this->oMail = $mail;

    	$mail->setEmailTemplate($this);

    	if (!empty($this->data['emailtemplate']['subject'])) {
    		$mail->setSubject($this->data['emailtemplate']['subject']);
    	}

    	if (!isset($this->data['subject'])) {
    		$this->data['subject'] = $mail->getSubject();
    	}

    	if (is_null($this->html)) {
    		$this->html = $this->fetch();
    	}
    	if ($this->html) {
    		if ($this->data['emailtemplate']['mail_html']){
    			$mail->setHtml($this->html);
    		}

	    	if ($this->data['emailtemplate']['mail_plain_text']) {
	    		$mail->setText($this->getPlainText($this->html));
	    	}
    	}

    	if ($this->data['emailtemplate']['mail_to']) {
    		$mail->setTo($this->data['emailtemplate']['mail_to']);
    	}
    	if ($this->data['emailtemplate']['mail_bcc']) {
    		$mail->setBcc($this->data['emailtemplate']['mail_bcc']);
    	}
    	if ($this->data['emailtemplate']['mail_cc']) {
    		$mail->setCc($this->data['emailtemplate']['mail_cc']);
    	}
    	if ($this->data['emailtemplate']['mail_from']) {
    		$mail->setFrom($this->data['emailtemplate']['mail_from']);
    	}
    	if ($this->data['emailtemplate']['mail_sender']) {
    		$mail->setSender($this->data['emailtemplate']['mail_sender']);
    	}
    	if ($this->data['emailtemplate']['mail_replyto'] && $this->data['emailtemplate']['mail_replyto'] != $this->data['emailtemplate']['mail_to']) {
    		$mail->setReplyTo($this->data['emailtemplate']['mail_replyto'], $this->data['emailtemplate']['mail_replyto_name']);
    	}
    	if ($this->data['emailtemplate']['mail_attachment']) {
    		$attachments = explode(',', $this->data['emailtemplate']['mail_attachment']);
    		$dir = substr(DIR_SYSTEM, 0, -7); // remove 'system/'

    		foreach($attachments as $attachment){
    			$mail->addAttachment($dir . $attachment);
    		}
    	}

    	return $mail;
    }

	/**
	 * @param string $filename - same as 1st parameter in Template::fetch()
	 * @param string $content - if $filename is null then the content will be used as the body
	 * @returns string
	 */
	public function fetch($filename = null, $content = null, $parseHtml = true) {
		if (!$this->isLoaded()){
			$this->load(1);
		}

		if (!$this->built) {
			$this->build();
		}

		$this->html = '';
		$this->content = '';

		if (!is_null($filename)) {
			$this->content = $this->fetchTemplate($filename);
		} elseif (!is_null($content)) {
			$this->content = html_entity_decode($content, ENT_QUOTES, 'UTF-8');
		} elseif (isset($this->data['emailtemplate']) && $this->data['emailtemplate']['template']) {
			$this->content = $this->fetchTemplate($this->data['emailtemplate']['template']);
		}

		if (!$this->content) {
			for($i=1; $i <= EmailTemplateDescriptionDAO::$content_count; $i++) {
	        	if (!empty($this->data['emailtemplate']['content'.$i])) {
	        		$this->content .= $this->data['emailtemplate']['content'.$i];
	        	}
	        }
		}

		if ($this->wrapper_tpl) {
			$wrapper = $this->fetchTemplate($this->wrapper_tpl);

			$this->html = str_ireplace('{CONTENT}', $this->content, $wrapper);
		} else {
			$this->html = $this->content;
		}

		if ($parseHtml) {
			$this->html = $this->_parseHtml($this->html);
		}

		return $this->html;
	}


	/**
	 * Send Email
	 */
	public function send($sendAdditional = false) {
		if(version_compare(VERSION, '2.0.0.0', '>=') && version_compare(VERSION, '2.0.2.0', '<')) {
			$mail = new Mail($this->config->get('config_mail'));
		} else {
			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			if($this->config->get('config_mail_smtp_host')){
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_host');
			} else {
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			}
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
		}

		$mail = $this->hook($mail);
		$mail->send();

		if ($sendAdditional) {
			$emails = explode(',', $this->config->get('config_alert_emails'));
			foreach ($emails as $email) {
				if (strlen($email) > 0 && preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
		}

		$this->sent();
	}

	/**
	 * Perform actions after email has been sent.
	 */
	public function sent() {
		if (isset($this->data['emailtemplate_invoice_pdf']) && file_exists($this->data['emailtemplate_invoice_pdf'])) {
			unlink($this->data['emailtemplate_invoice_pdf']);
		}

		if ($this->emailtemplate_log_id) {
			$log = array();

			if ($this->oMail) {
				$mail_vars = $this->oMail->getMailProperties();

				foreach (array('to', 'from', 'sender', 'subject', 'text') as $var) {
					if (isset($mail_vars[$var])) {
						$log['emailtemplate_log_' . $var] = $mail_vars[$var];
					}
				}
			}

			$log['emailtemplate_log_content'] = $this->_parseHtml($this->content);

			$log['emailtemplate_log_id'] = $this->emailtemplate_log_id;

			$log['emailtemplate_log_enc'] = $this->emailtemplate_log_enc;

			$log['emailtemplate_id'] = $this->data['emailtemplate']['emailtemplate_id'];

			$log['store_id'] = $this->store_id;

			$log['customer_id'] = $this->customer_id;

			return $this->model->insertLog($log);
		}
	}

	/**
	 * Property
	 */
	public function get($key) {
		if (property_exists($this, $key) && $key[0] != '_') {
			return $this->$key;
		}
	}
	public function set($key, $val) {
		if (property_exists($this, $key) && $key[0] != '_') {
			$this->$key = $val;
			return true;
		}
		return false;
	}

	/**
	 * Appends template data
	 *
	 * [code]
	 * $template->addData($my_data_array, 'prefix'); // array(prefix)
	 * $template->addData('my_value', $my_value); // string=key,value
	 *
	 * @return object
	 */
	public function addData($param1, $param2 = '') {
		if (is_array($param1)) {
			// $param2 acts as prefix
			if ($param2) {
				$param2 = rtrim($param2, "_") . "_";
				foreach ($param1 as $key => $value) {
					$param1[$param2.$key] = $value;
					unset($param1[$key]);
				}
			}
			$this->data = array_merge($this->data, $param1);
		} elseif (is_string($param1) && $param2 != "") {
			$this->data[$param1] = $param2;
		}

		return $this;
	}

	/**
	 * Appends $this->data with $data
	 *
	 * @deprecated
	 */
	public function appendData($data) {
		return $this->addData($data);
	}

	/**
	 * Get Tracking
	 */
	public function getTracking($url = null) {
		if (empty($this->data['config']['tracking'])) return $url;

		if (!isset($this->data['tracking'])) {
			$tracking = array();
			$tracking['utm_campaign'] = $this->data['config']['tracking_campaign_name'];
			$tracking['utm_medium'] = 'email';

			if ($this->data['config']['tracking_campaign_term']) {
				$tracking['utm_term'] = $this->data['config']['tracking_campaign_term'];
			}

			if ($this->data['emailtemplate']['tracking_campaign_source']) {
				$tracking['utm_source'] = $this->data['emailtemplate']['tracking_campaign_source'];
			} elseif (isset($this->request->get['route'])) {
				$tracking['utm_source'] =  $this->request->get['route'];
			}

			$this->data['tracking'] = http_build_query($tracking);
		}

        if ($url) {
        	return $url . (strpos($url, '?') === false ? '?' : '&amp;') . $this->data['tracking'];
        } else {
        	return $this->data['tracking'];
        }
	}

	/**
	 * Tries to convert the given HTML into a plain text format - best suited for
	* e-mail display, etc.
	*
	* <p>In particular, it tries to maintain the following features:
	* <ul>
	*   <li>Links are maintained, with the 'href' copied over
	*   <li>Information in the &lt;head&gt; is lost
	* </ul>
	*
	* @param html the input HTML
	* @return the HTML converted, as best as possible, to text
	*/
	public function getPlainText($html = null) {
		if (is_null($html)) {
			$html = $this->getHtml();
		}

		$dom = new DOMDocument('1.0', 'UTF-8');
		if ($dom->loadHTML($html)){
			$html = $this->_html_to_plain_text($dom->getElementById('emailPage'));
		}

		return $html;
	}

	/**
	 * Get HTML email template
	 */
	public function getHtml() {
		if ($this->html === null) {
			$this->fetch();
		}
		return $this->html;
	}

	/**
	 * Get Template Path
	 */
	protected function _getTemplatePath($file) {
		if (defined('DIR_CATALOG')) {
			if (file_exists(DIR_TEMPLATE.'mail/'.$file)) {
				$path = DIR_TEMPLATE.'mail/';
			} elseif (isset($this->data['config']['theme']) && file_exists(DIR_CATALOG.'view/theme/'.$this->data['config']['theme'].'/template/mail/'.$file)) {
				$path = DIR_CATALOG.'view/theme/'.$this->data['config']['theme'].'/template/mail/';
			} elseif ($this->config->get('config_template') && file_exists(DIR_CATALOG.'view/theme/'.$this->config->get('config_template').'/template/mail/'.$file)) {
				$path = DIR_CATALOG.'view/theme/'.$this->data['config']['theme'].'/template/mail/';
			} else {
				$path = DIR_CATALOG.'view/theme/default/template/mail/';
			}
		} else {
			if (isset($this->data['config']['theme']) && file_exists(DIR_TEMPLATE.$this->data['config']['theme'].'/template/mail/'.$file)) {
				$path = DIR_TEMPLATE.$this->data['config']['theme'].'/template/mail/';
			} elseif ($this->config->get('config_template') && file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/mail/'.$file)) {
				$path = DIR_TEMPLATE.$this->data['config']['theme'].'/template/mail/';
			} else {
				$path = DIR_TEMPLATE.'default/template/mail/';
			}
		}

		return $path;
	}

	/**
	 * Get Email Wrapper Filename
	 *
	 * @param string - filename
	 * @return object - EmailTemplate
	 */
	public function fetchTemplate($file) {
		if (!$file) return false;

		$template_file = false;

		$path = $this->_getTemplatePath($file);

		if (file_exists($path . $file)) {
			$template_file = $path . $file;
		} elseif(file_exists(DIR_TEMPLATE . $file)) {
			$template_file = DIR_TEMPLATE . $file;
		}

		if ($template_file) {
			extract($this->data);

			ob_start();

			include(modification($template_file));

			$content = ob_get_contents();

			if (ob_get_length()) ob_end_clean();

			return $content;
		} else {
			trigger_error('Error file not found:' . $file);
			return false;
		}
	}

	/**
	 * Generate array of hex values for shadow
	 * @param $from - HEX colour from
	 * @param $until - HEX colour from
	 * @param $length - distance of shadow
	 * @return Array(hex=>width)
	 */
	private function _generateGradientArray($from, $until, $length) {
	$from = ltrim($from,'#');
		$until = ltrim($until,'#');
		$from = array(hexdec(substr($from,0,2)),hexdec(substr($from,2,2)),hexdec(substr($from,4,2)));
		$until = array(hexdec(substr($until,0,2)),hexdec(substr($until,2,2)),hexdec(substr($until,4,2)));

		if ($length > 1) {
			$red=($until[0]-$from[0])/($length-1);
			$green=($until[1]-$from[1])/($length-1);
			$blue=($until[2]-$from[2])/($length-1);
			$return = array();

			for($i=0;$i<$length;$i++) {
				$newred=dechex($from[0]+round($i*$red));
				if (strlen($newred)<2) $newred="0".$newred;

				$newgreen=dechex($from[1]+round($i*$green));
				if (strlen($newgreen)<2) $newgreen="0".$newgreen;

				$newblue=dechex($from[2]+round($i*$blue));
				if (strlen($newblue)<2) $newblue="0".$newblue;

				$hex = $newred.$newgreen.$newblue;
				if (isset($return[$hex])) {
					$return[$hex] ++;
				} else {
					$return[$hex] = 1;
				}
			}
			return $return;
		} else {
			$red=($until[0]-$from[0]);
			$green=($until[1]-$from[1]);
			$blue=($until[2]-$from[2]);

			$newred=dechex($from[0]+round($red));
			if (strlen($newred)<2) $newred="0".$newred;

			$newgreen=dechex($from[1]+round($green));
			if (strlen($newgreen)<2) $newgreen="0".$newgreen;

			$newblue=dechex($from[2]+round($blue));
			if (strlen($newblue)<2) $newblue="0".$newblue;

			return array($newred.$newgreen.$newblue => $length);
		}

	}

	/**
	 * Method parses add inline CSS styles
	 *
	 * @param string $html
	 * @return string $html
	 */
	private function _parseHtml($html) {
		if ($this->isLoaded()) {
			$replace = $this->_fetchShortCodes();

			$html = str_replace($replace['find'], $replace['replace'], $html);
		}

		if (file_exists(DIR_SYSTEM. 'library/emailtemplate/email_template.php.css')) {
			extract($this->data);

			ob_start();

			include(DIR_SYSTEM. 'library/emailtemplate/email_template.php.css');

			$this->css = ob_get_contents();

			if (ob_get_length()) ob_end_clean();

			if ($this->css) {
				require_once DIR_SYSTEM . 'library/shared/CssToInlineStyles/CssToInlineStyles.php';

				$cssToInlineStyles = new CssToInlineStyles();

				$cssToInlineStyles->setCSS($this->css);

				$cssToInlineStyles->setHTML($html);

				$html = $cssToInlineStyles->convert();
			}
		}

		return wordwrap($html, 520, "\n");
	}

	/**
	 *  GET SHORTCODES
	 *
	 *  Get all empty shortcodes from database & merge with data
	 */
	private function _fetchShortCodes() {
		$find = array();
		$replace = array();

		$data = $this->data;

		if(empty($this->emailtemplate_shortcodes)){
			$this->emailtemplate_shortcodes = $this->model->getTemplateShortcodes(array(
				'emailtemplate_id' => $this->emailtemplate_id
			));
		}

		foreach($this->emailtemplate_shortcodes as $row) {
			if (!isset($data[$row['emailtemplate_shortcode_code']])) {
				$data[$row['emailtemplate_shortcode_code']] = '';
			}
		}

		foreach($data as $key => $var) {
			if (is_array($var)) {
				foreach($var as $key2 => $var2) {
					if (is_string($var2) || is_int($var2)) {
						$find[] = '{$'.$key.'.'.$key2.'}';
						$replace[] = $var2;
					}
				}
			} elseif (is_string($var) || is_int($var)) {
				$find[] = '{$'.$key.'}';
				$replace[] = $var;
			}
		}

		return array('find' => $find, 'replace' => $replace);
	}

	public static function truncate_str($str, $length = 100, $breakWords = true, $append = '...') {
		$str = strip_tags(html_entity_decode($str, ENT_QUOTES, 'UTF-8'));

		$strLength = utf8_strlen($str);
		if ($strLength <= $length) {
			return $str;
		}

		if (!$breakWords) {
			while ($length < $strLength AND preg_match('/^\pL$/', utf8_substr($str, $length, 1))) {
				$length++;
			}
		}

		$str = utf8_substr($str, 0, $length) . $append;
		$str = preg_replace('/\s{3,}/',' ', $str);
		$str = trim($str);

		return $str;
	}

	/******************************************************************************
	 * Copyright (c) 2010 Jevon Wright and others.
	* All rights reserved. This program and the accompanying materials
	* are made available under the terms of the Eclipse Public License v1.0
	* which accompanies this distribution, and is available at
	* http://www.eclipse.org/legal/epl-v10.html
	*
	* Contributors:
	*    Jevon Wright - initial API and implementation
	****************************************************************************/
	private function _html_to_plain_text($node) {
		if ($node instanceof DOMText) {
			return preg_replace("/\\s+/im", " ", $node->wholeText);
		}
		if ($node instanceof DOMDocumentType) {
			// ignore
			return "";
		}

		// Next
		$nextNode = $node->nextSibling;
		while ($nextNode != null) {
			if ($nextNode instanceof DOMElement) {
				break;
			}
			$nextNode = $nextNode->nextSibling;
		}
		$nextName = null;
		if ($nextNode instanceof DOMElement && $nextNode != null) {
			$nextName = strtolower($nextNode->nodeName);
		}

		// Previous
		$nextNode = $node->previousSibling;
		while ($nextNode != null) {
			if ($nextNode instanceof DOMElement) {
				break;
			}
			$nextNode = $nextNode->previousSibling;
		}
		$prevName = null;
		if ($nextNode instanceof DOMElement && $nextNode != null) {
			$prevName = strtolower($nextNode->nodeName);
		}

		$name = strtolower($node->nodeName);

		// start whitespace
		switch ($name) {
			case "hr":
				return "------\n";

			case "style":
			case "head":
			case "title":
			case "meta":
			case "script":
				// ignore these tags
				return "";

			case "h1":
			case "h2":
			case "h3":
			case "h4":
			case "h5":
			case "h6":
				// add two newlines
				$output = "\n";
				break;

			case "p":
			case "div":
				// add one line
				$output = "\n";
				break;

			default:
				// print out contents of unknown tags
				$output = "";
				break;
		}

		// debug $output .= "[$name,$nextName]";

		if ($node->childNodes) {
			for ($i = 0; $i < $node->childNodes->length; $i++) {
				$n = $node->childNodes->item($i);

				$text = $this->_html_to_plain_text($n);

				$output .= $text;
			}
		}

		// end whitespace
		switch ($name) {
			case "style":
			case "head":
			case "title":
			case "meta":
			case "script":
				// ignore these tags
				return "";

			case "h1":
			case "h2":
			case "h3":
			case "h4":
			case "h5":
			case "h6":
				$output .= "\n";
				break;

			case "p":
			case "br":
				// add one line
				if ($nextName != "div")
					$output .= "\n";
				break;

			case "div":
				// add one line only if the next child isn't a div
				if (($nextName != "div" && $nextName != null) || ($node->hasAttribute('class') && strstr($node->getAttribute('class'), 'emailtemplateSpacing')))
					$output .= "\n";
				break;

			case "td":
				// add one line only if the next child isn't a div
				if ($node->hasAttribute('class') && strstr($node->getAttribute('class'), 'emailtemplateSpacing'))
					$output .= "\n\n";
				break;

			case "a":
				// links are returned in [text](link) format
				$href = $node->getAttribute("href");
				if ($href == null) {
					// it doesn't link anywhere
					if ($node->getAttribute("name") != null) {
						$output = "$output";
					}
				} else {
					if ($href == $output || ($node->hasAttribute('class') && strstr($node->getAttribute('class'), 'emailtemplateNoDisplay'))) {
						// link to the same address: just use link
						$output;
					} else {
						// No display
						$output = $href . "\n" . $output;
					}
				}

				// does the next node require additional whitespace?
				switch ($nextName) {
					case "h1": case "h2": case "h3": case "h4": case "h5": case "h6":
						$output .= "\n";
						break;
				}

			default:
				// do nothing
		}

		return $output;
	}
}

?>