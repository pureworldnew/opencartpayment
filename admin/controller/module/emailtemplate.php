<?php

class ControllerModuleEmailtemplate extends Controller {

	protected $data = array();

	private $error = array();

	private $_css = array('view/stylesheet/module/emailtemplate.css');
	private $_js = array('view/javascript/emailtemplate/core.js');

	public function __construct($registry) {
		parent::__construct($registry);

		$file = DIR_SYSTEM . 'library/emailtemplate/email_template.php';

		if (file_exists($file)) {
			include_once($file);
		} else {
			trigger_error('Error: Could not load library ' . $file . '!');
			exit();
		}
	}

	/**
	 * List Of Email Templates & Config
	 */
	public function index() {
		if (!$this->installed()) {
			$this->response->redirect($this->url->link('module/emailtemplate/installer', 'token='.$this->session->data['token'], 'SSL'));
		} else {
			$chk = $this->db->query("SELECT count(1) AS total FROM `". DB_PREFIX . "emailtemplate`");

			if ($chk->num_rows  && $chk->row['total'] <= 1) {
				$this->response->redirect($this->url->link('module/emailtemplate/installer', 'token='.$this->session->data['token'], 'SSL'));
			}
		}

		$this->load->language('module/emailtemplate');

		$this->load->model('module/emailtemplate');
		$this->load->model('localisation/language');

		if ($this->model_module_emailtemplate->checkVersion() !== false) {
			if ($this->model_module_emailtemplate->upgrade()) {
				$this->session->data['success'] = $this->language->get('upgrade_success');

				$this->response->redirect($this->url->link('module/emailtemplate', 'token='.$this->session->data['token'], 'SSL'));
			}
		}

		$url = '';

		if (isset($this->request->get['filter_type'])) {
			$url .= '&filter_type=' . $this->request->get['filter_type'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		} else {
			$url .= '&sort=modified';
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->load->language('module/emailtemplate');

		if (isset($this->request->get['action'])) {
			if (empty($this->request->post['selected'])) {
				$this->session->data['attention'] = $this->language->get('error_template_selection_empty');

				$this->response->redirect($this->url->link('module/emailtemplate', 'token='.$this->session->data['token'] . $url, 'SSL'));
			}

			switch($this->request->get['action']) {
				case 'delete':
					$result = $this->model_module_emailtemplate->deleteTemplate($this->request->post['selected']);

					if ($result) {
						$this->session->data['success'] = sprintf($this->language->get('success_delete_template'), $result, (($result > 1) ? "'s" : ""));

						$this->session->data['attention'] = sprintf($this->language->get('text_template_changed'), $this->url->link('module/emailtemplate/test', 'token='.$this->session->data['token'], 'SSL'));

						$this->response->redirect($this->url->link('module/emailtemplate', 'token='.$this->session->data['token'] . $url, 'SSL'));
					}
				break;

				case 'disable':
				case 'enable':
					$result = $this->model_module_emailtemplate->updateTemplatesStatus($this->request->post['selected'], ($this->request->get['action'] == 'enable'));

					if ($result) {
						$this->session->data['success'] = sprintf($this->language->get('success_status_template_update'), $result, (($result > 1) ? "'s" : ""));

						$this->session->data['attention'] = sprintf($this->language->get('text_template_changed'), $this->url->link('module/emailtemplate/test', 'token='.$this->session->data['token'], 'SSL'));

						$this->response->redirect($this->url->link('module/emailtemplate', 'token='.$this->session->data['token'] . $url, 'SSL'));
					}
				break;

				case 'delete_shortcode':
					foreach ($this->request->post['selected'] as $template_id) {
						$this->model_module_emailtemplate->deleteTemplateShortcodes($template_id);
					}

					$this->session->data['success'] = $this->language->get('success_delete_shortcode');

					$this->response->redirect($this->url->link('module/emailtemplate', 'token='.$this->session->data['token'] . $url, 'SSL'));
				break;
			}
		}

		$this->_setTitle();

		$this->_messages();

		$this->_breadcrumbs();

		$this->data['action'] = $this->url->link('module/emailtemplate', 'token='.$this->session->data['token'] . $url, 'SSL');

		$this->data['cancel'] = $this->url->link('extension/module', 'token='.$this->session->data['token'], 'SSL');

		$this->data['config_url'] = $this->url->link('module/emailtemplate/config', 'token='.$this->session->data['token'] . '&id=1', 'SSL');
		$this->data['test_url'] = $this->url->link('module/emailtemplate/test', 'token='.$this->session->data['token'], 'SSL');
		$this->data['logs_url'] = $this->url->link('module/emailtemplate/logs', 'token='.$this->session->data['token'], 'SSL');

		$this->data['support_url'] = 'http://support.opencart-templates.co.uk/open.php';

		if (defined('VERSION')) {
			$ocVer = VERSION;
		} else {
			$ocVer = '';
		}

		$i = 1;
		foreach(array('name'=>$this->config->get("config_owner").' - '.$this->config->get("config_name"), 'email'=>$this->config->get("config_email"), 'protocol'=>$this->config->get("config_mail_protocol"), 'storeUrl'=>HTTP_CATALOG, 'version'=>EmailTemplate::$version, 'opencartVersion'=>$ocVer, 'phpVersion'=>phpversion()) as $key=>$val) {
			$this->data['support_url'] .= (($i == 1) ? '?' : '&amp;') . $key . '=' . html_entity_decode($val,ENT_QUOTES,'UTF-8');
			$i++;
		}

		$this->data['action_insert_template'] = $this->url->link('module/emailtemplate/template', 'token='.$this->session->data['token'], 'SSL');

		$this->_template_list();

		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		$this->_output('module/emailtemplate/extension.tpl');
	}

	/**
	 * Config Form
	 */
	public function config() {
		$this->load->model('module/emailtemplate');
		$this->load->language('module/emailtemplate');

		if (!isset($this->request->get['id'])) {
			return false;
		}

		if (isset($this->request->get['action'])) {
			switch($this->request->get['action']) {
				case 'create':
					$newId = $this->model_module_emailtemplate->cloneConfig($this->request->get['id'], $this->request->post);
					if ($newId) {
						$this->session->data['success'] = $this->language->get('success_config');
						$this->response->redirect($this->url->link('module/emailtemplate/config', 'token='.$this->session->data['token'].'&id='.$newId, 'SSL'));
					}
				break;
				case 'delete':
					if ($this->model_module_emailtemplate->deleteConfig($this->request->get['id'])) {
						$this->session->data['success'] = $this->language->get('success_config_delete');
					}

					$this->response->redirect($this->url->link('module/emailtemplate/config', 'token='.$this->session->data['token'] . '&id=1', 'SSL'));
				break;
				case 'test_send':
					$return = array();

					$config = $this->model_module_emailtemplate->getConfig($this->request->get['id'], true);

					if ($this->_sendTestEmail($this->config->get('config_email'), $config['store_id'], $config['language_id'])) {
						$return['success'] = $this->language->get('success_send');
					}

					// Send to additional alert emails if new account email is enabled
					$emails = explode(',', $this->config->get('config_mail_alert'));

					foreach ($emails as $email) {
						if (utf8_strlen($email) > 0 && preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $email)) {
							$this->_sendTestEmail($email, $config['store_id'], $config['language_id']);
						}
					}

					$this->response->addHeader('Content-Type: application/json');
					$this->response->setOutput(json_encode($return));
					return true;
				break;
			}

		}

		// Save
	    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->_validateConfig($this->request->post)) {
			$data = $this->request->post;

			$checkboxes = array(
				'emailtemplate_config_email_responsive',
				'emailtemplate_config_table_quantity',
				'emailtemplate_config_tracking',
				'emailtemplate_config_showcase_related',
				'emailtemplate_config_view_browser_theme',
				'emailtemplate_config_log',
				'emailtemplate_config_log_read'
			);

			foreach($checkboxes as $checkbox) {
				if (!isset($data[$checkbox])) {
					$data[$checkbox] = 0;
				}
			}

	    	// check style changed
	    	$config = $this->model_module_emailtemplate->getConfig($this->request->get['id'], true);
	    	if ($config['emailtemplate_config_style'] != $data['emailtemplate_config_style']) {
	    		$data = $this->_config_style($data);
	    	}

	    	// Fix for summernote editor - empty content
			foreach(array(
				'emailtemplate_config_view_browser_text',
				'emailtemplate_config_head_text',
				'emailtemplate_config_page_footer_text',
				'emailtemplate_config_footer_text'
			) as $var){
				if(!empty($data[$var])){
					if(trim(strip_tags(html_entity_decode($data[$var], ENT_QUOTES, 'UTF-8'))) == ''){
						$data[$var] = '';
					}
				}
			}

	        if ($this->model_module_emailtemplate->updateConfig($this->request->get['id'], $data)) {
	            $this->session->data['success'] = $this->language->get('success_config');
	        }

        	$this->response->redirect($this->url->link('module/emailtemplate/config', 'token='.$this->session->data['token'].'&id='.$this->request->get['id'], 'SSL'));
	    }

	    $this->_messages();

	    $this->_breadcrumbs(array('heading_config' => array(
			'link' => 'module/emailtemplate/config',
			'params' => '&id='.$this->request->get['id']
		)));

		$this->_config_form();

		$this->_css[] = 'view/javascript/bootstrap/css/bootstrap-colorpicker.min.css';
		$this->_js[] = 'view/javascript/bootstrap/js/bootstrap-colorpicker.min.js';
		$this->_js[] = 'view/javascript/bootstrap/js/bootstrap-checkbox.min.js';
		$this->_js[] = 'view/javascript/emailtemplate/config.js';

		$this->_output('module/emailtemplate/config.tpl');
	}

	/**
	 * Template Details
	 */
	public function template() {
		$this->load->language('module/emailtemplate');
		$this->load->model('module/emailtemplate');

		if (isset($this->request->get['id'], $this->request->get['action'])) {
			switch($this->request->get['action']) {
				case 'delete':
					$result = $this->model_module_emailtemplate->deleteTemplate($this->request->get['id']);

					if ($result) {
						$this->session->data['success'] = sprintf($this->language->get('success_delete_template'), $result, (($result > 1) ? "'s" : ""));
						$this->response->redirect($this->url->link('module/emailtemplate', 'token='.$this->session->data['token'], 'SSL'));
					}
				break;

				case 'delete_shortcode':
					if (isset($this->request->post['shortcode_selection'])) {
						if ($this->model_module_emailtemplate->deleteTemplateShortcodes($this->request->get['id'], array('emailtemplate_shortcode_id' => $this->request->post['shortcode_selection']))) {
							$this->session->data['success'] = $this->language->get('success_delete_shortcode');
						}
					} else {
						if ($this->model_module_emailtemplate->deleteTemplateShortcodes($this->request->get['id'])) {
							$this->session->data['success'] = $this->language->get('success_delete_shortcode');
						}
					}
				break;

				case 'default_shortcode':
					if ($this->model_module_emailtemplate->insertTemplateShortcodes($this->request->get['id'])) {
						$this->session->data['success'] = $this->language->get('text_success');
					}
				break;
			}

			$url = '&id='.$this->request->get['id'];

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('module/emailtemplate/template', 'token='.$this->session->data['token'] . $url, 'SSL'));
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->_validateTemplate($this->request->post)) {
			$url = '';

			if (isset($this->request->get['id'])) {
				// Update
				$request = $this->request->post;

				$original = $this->model_module_emailtemplate->getTemplate($this->request->get['id']);

				$checkboxes = array(
					'emailtemplate_mail_html',
					'emailtemplate_mail_plain_text',
					'emailtemplate_view_browser',
					'emailtemplate_showcase',
					'emailtemplate_status',
					'emailtemplate_log'
				);

				foreach($checkboxes as $checkbox) {
					if (!isset($request[$checkbox])) {
						$request[$checkbox] = 0;
					}
				}

				// Fix for summernote editor - empty content
				for ($i = 1; $i <= EmailTemplateDescriptionDAO::$content_count; $i++) {
					if(!empty($request['emailtemplate_description_content' . $i])){
						foreach(array_keys($request['emailtemplate_description_content' . $i]) as $langId){
							if(strip_tags(html_entity_decode($request['emailtemplate_description_content' . $i][$langId], ENT_QUOTES, 'UTF-8')) == ''){
								$request['emailtemplate_description_content' . $i][$langId] = '';
							}
						}
					}
				}

				if ($this->model_module_emailtemplate->updateTemplate($this->request->get['id'], $request)) {
					$this->session->data['success'] = $this->language->get('text_success');

					if($original['emailtemplate_status'] != $request['emailtemplate_status']){
						$this->session->data['attention'] = sprintf($this->language->get('text_template_changed'), $this->url->link('module/emailtemplate/test', 'token='.$this->session->data['token'], 'SSL'));
					}
				}
				$url .= '&id='.$this->request->get['id'];
			} else {
				// Insert
				$request = $this->request->post;

				// Key
				if (!$request['emailtemplate_key'] && $request['emailtemplate_key_select']) {
					$defaultTemplate = $this->model_module_emailtemplate->getTemplate($request['emailtemplate_key_select']);

					$request['default_emailtemplate_id'] = $defaultTemplate['emailtemplate_id'];

					unset($defaultTemplate['emailtemplate_id']);
					unset($defaultTemplate['emailtemplate_label']);
					unset($defaultTemplate['emailtemplate_modified']);

					$request = array_merge($defaultTemplate, $request);

					$result = $this->model_module_emailtemplate->getTemplateDescription(array('emailtemplate_id' => $request['default_emailtemplate_id']));

					$defaultTemplateDescriptions = array();

					foreach($result as $row) {
						foreach($row as $col => $val) {
							if(!isset($request[$col]) || !is_array($request[$col])){
								$request[$col] = array();
							}
							$request[$col][$row['language_id']] = $val;
						}
					}

					$request['emailtemplate_key'] = $request['emailtemplate_key_select'];
					$request['emailtemplate_default'] = 0;
					$request['emailtemplate_shortcodes'] = 0;
					$request['store_id'] = 'NULL';
				}

				$id = $this->model_module_emailtemplate->insertTemplate($request);

				if ($id) {
					$url .= '&id='.$id;
					$this->session->data['success'] = $this->language->get('success_insert');
				}
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('module/emailtemplate/template', 'token='.$this->session->data['token'] . $url, 'SSL'));
		}

		$this->_js[] = 'view/javascript/bootstrap/js/bootstrap-checkbox.min.js';
		$this->_js[] = 'view/javascript/emailtemplate/template.js';

		if (isset($this->request->get['id'])) {
			$this->_template_form();
			$this->_output('module/emailtemplate/template_form.tpl');
		} else {
			$this->_template_form_create();
			$this->_output('module/emailtemplate/template_create_form.tpl');
		}
	}

	/**
	 * Logs
	 */
	public function logs() {
		$this->load->language('module/emailtemplate');
		$this->load->model('module/emailtemplate');
		$this->load->model('sale/customer');
		$this->load->model('sale/customer_group');

		$this->load->language('module/emailtemplate');

		foreach(array(
			'button_cancel',
			'button_delete',
			'button_edit_template',
			'button_html',
			'button_load',
			'button_plain_text',
			'button_reply',
			'column_from',
			'column_sent',
			'column_subject',
			'column_to',
			'entry_store',
			'heading_logs',
			'text_confirm',
			'text_customer',
			'text_from',
			'text_no_results',
			'text_read',
			'text_read_last',
			'text_search',
			'text_select',
			'text_subject',
			'text_template',
			'text_to'
		) as $var) {
			$this->data[$var] = $this->language->get($var);
		}

		if (isset($this->request->get['store_id']) && is_numeric($this->request->get['store_id'])) {
			$this->data['filter_store_id'] = $this->request->get['store_id'];
		} else {
			$this->data['filter_store_id'] = null;
		}

		if (isset($this->request->get['filter_emailtemplate_id'])) {
			$this->data['filter_emailtemplate_id'] = $this->request->get['filter_emailtemplate_id'];
		} else {
			$this->data['filter_emailtemplate_id'] = '';
		}

		if (isset($this->request->get['filter_store_id'])) {
			$this->data['filter_store_id'] = $this->request->get['filter_store_id'];
		} else {
			$this->data['filter_store_id'] = '';
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$this->data['filter_customer_id'] = $this->request->get['filter_customer_id'];
		} else {
			$this->data['filter_customer_id'] = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'sent';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit']) && $this->request->get['limit'] <= 100) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = $this->config->get('config_limit_admin');
		}

		$url = '';
		if ($order == 'ASC') {
			$url .= '&order=ASC';
		} else {
			$url .= '&order=DESC';
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		if (isset($this->request->get['filter_emailtemplate_id'])) {
			$url .= '&filter_emailtemplate_id=' . $this->request->get['filter_emailtemplate_id'];
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}

		if (isset($this->request->get['filter_store_id'])) {
			$url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
		}

		if (!empty($this->request->post['selected'])) {
			$result = $this->model_module_emailtemplate->deleteLogs($this->request->post['selected']);
			if ($result) {
				$this->session->data['success'] = sprintf($this->language->get('success_delete_log'), $result, ($result > 1 ? '\'s' : ''));
			}
			$this->response->redirect($this->url->link('module/emailtemplate/logs', 'token='.$this->session->data['token'].$url, 'SSL'));
		}

		$this->_setTitle($this->language->get('heading_logs'));

		$this->_messages();

		$this->_breadcrumbs(array('heading_logs' => array(
			'link' => 'module/emailtemplate/logs'
		)));

		$filter = array();
		$filter['start'] = ($page - 1) * $limit;
		$filter['limit'] = $limit;
		$filter['order'] = $order;
		$filter['sort'] = $sort;
		$filter['emailtemplate_id'] = $this->data['filter_emailtemplate_id'];
		$filter['store_id'] = $this->data['filter_store_id'];
		$filter['customer_id'] = $this->data['filter_customer_id'];

		$result = $this->model_module_emailtemplate->getTemplateLogs($filter, true, true);

		$total = $this->model_module_emailtemplate->getTotalTemplateLogs($filter);

		$link = $this->url->link('module/emailtemplate/logs', 'token='.$this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->_renderPagination($link, $page, $total, $limit, 'select');

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['action'] = $this->url->link('module/emailtemplate/logs', 'token='.$this->session->data['token'] . $url, 'SSL');

		$this->data['cancel'] = $this->url->link('module/emailtemplate', 'token='.$this->session->data['token'], 'SSL');

		$this->data['logs'] = array();

		foreach($result as $row) {
			$row['preview'] = EmailTemplate::truncate_str($row['subject'], 50);

			if ($row['sent'] && $row['sent'] != '0000-00-00 00:00:00') {
				$time = strtotime($row['sent']);
				if (date('Ymd') == date('Ymd', $time)) {
					$row['sent'] = date($this->language->get('time_format'), $time);
				} else {
					$row['sent'] = date($this->language->get('date_format_short'), $time);
				}
			} else {
				$row['sent'] = '';
			}

			if ($row['read'] && $row['read'] != '0000-00-00 00:00:00') {
				$time = strtotime($row['read']);
				if (date('Ymd') == date('Ymd', $time)) {
					$row['read'] = date($this->language->get('time_format'), $time);
				} else {
					$row['read'] = date($this->language->get('date_format_short'), $time);
				}
			} else {
				$row['read'] = '';
			}

			if ($row['emailtemplate_id']) {
				$row['emailtemplate'] = $this->model_module_emailtemplate->getTemplate($row['emailtemplate_id'], $this->config->get('config_language_id'), true);
				if ($row['emailtemplate']) {
					$row['emailtemplate']['url_edit'] = $this->url->link('module/emailtemplate/template', 'token='.$this->session->data['token'] . '&id=' . $row['emailtemplate_id'], 'SSL');
				}
			}

			if ($row['store_id'] >= 0) {
				$stores = $this->model_module_emailtemplate->getStores($row['store_id']);

				if (isset($stores[$row['store_id']])) {
					$row['store'] = $stores[$row['store_id']];
				} else {
					$row['store'] = current($stores);
				}
			}

			$row['action'] = array();

			if ($row['customer_id']) {
				$customer = $this->model_sale_customer->getCustomer($row['customer_id']);
				if ($customer) {
					$row['customer'] = $customer;
					$row['customer']['url_edit'] = $this->url->link('sale/customer/edit', 'token='.$this->session->data['token'] . '&customer_id=' . $row['customer_id'], 'SSL');
				}
			} else {
				$customer = $this->model_sale_customer->getCustomerByEmail($row['to']);
				if ($customer) {
					$row['customer'] = $customer;
					$row['customer_id'] = $customer['customer_id'];
					$row['customer']['url_edit'] = $this->url->link('sale/customer/edit', 'token='.$this->session->data['token'] . '&customer_id=' . $row['customer_id'], 'SSL');
				}
			}

			$row['action']['load'] = array(
				'label' => $this->data['button_load'],
				'url' => $this->url->link('module/emailtemplate/log', 'token='.$this->session->data['token'] . '&id=' . $row['emailtemplate_log_id'], 'SSL')
			);
			if ($row['emailtemplate_id']) {
				$row['action']['edit'] = array(
					'label' => $this->data['button_edit_template'],
					'url' => $this->url->link('module/emailtemplate', 'token='.$this->session->data['token'] . '&id=' . $row['emailtemplate_id'], 'SSL')
				);
			}

			$this->data['logs'][] = $row;
		}

		$this->data['emailtemplates'] = $this->model_module_emailtemplate->getTemplates(array(), true);

		$this->data['stores'] = $this->model_module_emailtemplate->getStores();

		if (isset($this->request->get['filter_customer_id'])) {
			$customer = $this->model_sale_customer->getCustomer($this->request->get['customer_id']);

			$this->data['filter_customer'] = strip_tags(html_entity_decode($customer['firstname'] . ' ' . $customer['lastname'], ENT_QUOTES, 'UTF-8'));
		} else {
			$this->data['filter_customer'] = '';
		}

		$url = '';
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		if (isset($this->request->get['filter_emailtemplate_id'])) {
			$url .= '&filter_emailtemplate_id=' . $this->request->get['filter_emailtemplate_id'];
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . $this->request->get['filter_customer'];
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_store_id'])) {
			$url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
		}

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		$this->data['total'] = $total;

		foreach(array('subject', 'to', 'from',  'sent', 'read', 'store', 'emailtemplate') as $var) {
			$this->data['sort_'.$var] = $this->url->link('module/emailtemplate/logs', 'token='.$this->session->data['token'] . '&sort=' . $var . $url, 'SSL');
		}

		$this->_js[] = 'view/javascript/emailtemplate/logs.js';

		$this->_output('module/emailtemplate/logs.tpl');
	}

	/**
	 * Get Template & Parse Tags
	 */
	public function get_template() {
		if (!isset($this->request->get['id'])) return false;
		$return = array();
		$template = new EmailTemplate($this->request, $this->registry);

		$template->set('insert_shortcodes', false);

		if (isset($this->request->get['parse']) && !$this->request->get['parse']) {
			$template->set('parse_shortcodes', false);
		}

		$template_data = array(
			'emailtemplate_id' => $this->request->get['id']
		);

		if (isset($this->request->get['store_id'])) {
			$template_data['store_id'] = $this->request->get['store_id'];
		}

		if (isset($this->request->get['language_id'])) {
			$template_data['language_id'] = $this->request->get['language_id'];

			if ($template->load($template_data)) {
				$template->build();
				$return[$template_data['language_id']] = $template->data;
			}
		} else {
			$this->load->model('localisation/language');
			$languages = $this->model_localisation_language->getLanguages();

			foreach($languages as $language) {
				$template_data['language_id'] = $language['language_id'];

				if ($template->load($template_data)) {
					$template->build();
					$return[$language['language_id']] = $template->data;
				}
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($return));
		return true;
	}

	/**
	 * Get Template Shortcodes
	 *
	 * @deprecated
	 */
	public function get_template_shortcodes() {
		$this->load->model('module/emailtemplate');

		$filter = array();
		if (!empty($this->request->get['id'])) {
			$filter['emailtemplate_id'] = $this->request->get['id'];
		} elseif (!empty($this->request->get['key'])) {
			$filter['emailtemplate_key'] = $this->request->get['key'];
		} else {
			return false;
		}

		$return = $this->model_module_emailtemplate->getTemplateShortcodes($filter, true);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($return));
		return true;
	}

	/**
	 * Edit Shortcode
	 */
	public function template_shortcode() {
		if (!isset($this->request->get['id'])) {
			$this->response->redirect($this->url->link('module/emailtemplate', 'token='.$this->session->data['token'], 'SSL'));
		}

		$this->load->language('module/emailtemplate');
		$this->load->model('module/emailtemplate');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->_validateTemplateShortcode($this->request->post)) {
			$url = '';
			$return = array();

			if (isset($this->request->get['id'])) {
				if ($this->model_module_emailtemplate->updateTemplateShortcode($this->request->get['id'], $this->request->post)) {
					$return['success'] = $this->language->get('text_success');
				}
			} else {
				$id = $this->model_module_emailtemplate->insertTemplateShortcode($this->request->post);
				if ($id) {
					$url .= '&id='.$id;
					$return['success'] = $this->language->get('success_insert');
				}
			}

			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($return));
			return true;
		}

		$this->_template_shortcode_form();

		$this->_css[] = 'view/stylesheet/emailtemplate/modal.css';

		$this->_output('module/emailtemplate/template_shortcode_form.tpl');
	}

	/**
	 * Restore Template
	 */
	public function template_restore($data = array()) {
		if (!isset($this->request->get['key'])) {
			$this->response->redirect($this->url->link('module/emailtemplate', 'token='.$this->session->data['token'], 'SSL'));
		}

		$this->load->language('module/emailtemplate');

		$this->load->model('module/emailtemplate');

		$new_id = $this->model_module_emailtemplate->installTemplate($this->request->get['key']);

		if ($new_id) {
			$this->session->data['success'] = $this->language->get('success_restore');

			$this->session->data['attention'] = sprintf($this->language->get('text_template_changed'), $this->url->link('module/emailtemplate/test', 'token='.$this->session->data['token'], 'SSL'));

			$this->response->redirect($this->url->link('module/emailtemplate/template', 'token='.$this->session->data['token'] . '&id='.$new_id, 'SSL'));
		}
	}

	/**
	 * Fetch Template & Parse Tags
	 */
	public function fetch_template($data = array()) {
		if (empty($data)) {
			$data = $this->request->get;
		}
		$template_data = array();
		$template = new EmailTemplate($this->request, $this->registry);

		$template->set('insert_shortcodes', false);

		if (isset($data['parse']) && !$data['parse']) {
			$template->set('parse_shortcodes', false);
		}

		if (isset($data['order_id'])) {
			$this->load->model('sale/order');
			$order_info = $this->model_sale_order->getOrder($data['order_id']);

			$template->addData($order_info);
			$template->data['payment_address'] = $this->_formatAddress($order_info, 'payment', $order_info['payment_address_format']);
			$template->data['shipping_address'] = $this->_formatAddress($order_info, 'shipping', $order_info['shipping_address_format']);
		}

		if (isset($data['id'])) {
			$template_data['emailtemplate_id'] = $this->request->get['id'];
		}
		if (isset($data['store_id'])) {
			$template_data['store_id'] = $this->request->get['store_id'];
		}
		if (isset($data['language_id'])) {
			$template_data['language_id'] = $this->request->get['language_id'];
		}
		if (isset($data['customer_id'])) {
			$template_data['customer_id'] = $this->request->get['customer_id'];
		}

		if (empty($template_data)) return false;

		if ($template->load($template_data)) {
			$template->build();

			if (isset($data['output']) && isset($template->data['emailtemplate'][$data['output']])) {
				$html = $template->data['emailtemplate'][$data['output']];
			} else {
				$template->set('wrapper_tpl', '');
				$html = $template->fetch();
			}

			echo $html;
			exit;
		}
	}

	/**
	 * Fetch Template Log
	 */
	public function fetch_log($data = array()) {
		$data = array_merge($data, $this->request->get);

		if (empty($data['id'])) {
			return false;
		}

		$this->load->model('module/emailtemplate');

		$log = $this->model_module_emailtemplate->getTemplateLog($data['id'], true);

		if (empty($log)) {
			return false;
		}

		$log['preview'] = EmailTemplate::truncate_str($log['subject'], 50);

		$log['text'] = nl2br($log['text']);

		if ($log['sent'] && $log['sent'] != '0000-00-00 00:00:00') {
			$time = strtotime($log['sent']);
			if (date('Ymd') == date('Ymd', $time)) {
				$log['sent'] = date($this->language->get('time_format'), $time);
			} else {
				$log['sent'] = date($this->language->get('date_format_long'), $time);
			}
		} else {
			$log['sent'] = '';
		}

		if ($log['read'] && $log['read'] != '0000-00-00 00:00:00') {
			$time = strtotime($log['read']);
			if (date('Ymd') == date('Ymd', $time)) {
				$log['read'] = date($this->language->get('time_format'), $time);
			} else {
				$log['read'] = date($this->language->get('date_format_short'), $time);
			}
		} else {
			$log['read'] = '';
		}

		if ($log['read_last'] && $log['read_last'] != '0000-00-00 00:00:00') {
			$time = strtotime($log['read_last']);
			if (date('Ymd') == date('Ymd', $time)) {
				$log['read_last'] = date($this->language->get('time_format'), $time);
			} else {
				$log['read_last'] = date($this->language->get('date_format_short'), $time);
			}
		} else {
			$log['read_last'] = '';
		}

		$template = new EmailTemplate($this->request, $this->registry);

		$template->load(1);

		$content = html_entity_decode($log['content'], ENT_QUOTES, 'UTF-8');

		$template->fetch(null, $content);

		$log['html'] = $template->getHtml();

		if (isset($data['output']) && $data['output'] == 'html') {
			echo $log['html'];
			exit;
		} else {
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($log));
			return true;
		}
	}

	/**
	 * Get preview of email using order
	 */
public function preview_email() {
		if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
			$this->response->redirect($this->url->link('module/emailtemplate', 'token='.$this->session->data['token'], 'SSL'));
		}

		$request = $this->request->get;

		if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
			$request = array_merge($this->request->post, $this->request->get);
		}

		if (isset($request['language_id'])) {
			$language_id = $request['language_id'];
		} else {
			$language_id = $this->config->get('language_id');
		}

		$overwrite = array();

		if (isset($_POST['data'])) {
			unset($request['data']);
			parse_str($_POST['data'], $overwrite);
		}

		if (!empty($request['order_id'])) {
			$this->load->model('module/emailtemplate');

			$template = $this->model_module_emailtemplate->getCompleteOrderEmail($request['order_id'], $request, $overwrite);
		} else {
			$template = new EmailTemplate($this->request, $this->registry);

			if (!empty($request['emailtemplate_id'])) {
				$data = $this->model_module_emailtemplate->getTemplateShortcodes($request['emailtemplate_id']);
				foreach($data as $shortcode) {
					$template->addData($shortcode['emailtemplate_shortcode_code'], $shortcode['emailtemplate_shortcode_example']);
				}
			}

			$template->load($request);

			$template->set('insert_shortcodes', false);

			if ($overwrite) {
				foreach($overwrite as $key => $val) {
					if (strpos($key, 'emailtemplate_config_') === 0) {
						$key = substr($key, 21); #21=strlen('emailtemplate_config_')

						if (isset($template->data['config'][$key])) {
							$template->data['config'][$key] = $val;
						}
					} elseif (strpos($key, 'emailtemplate_description_') === 0) {
						$key = substr($key, 26); #26=strlen('emailtemplate_description_')

						if (isset($template->data['emailtemplate'][$key]) && isset($val[$language_id])) {
							$template->data['emailtemplate'][$key] = $val[$language_id];
						}
					} elseif (strpos($key, 'emailtemplate_') === 0) {
						$key = substr($key, 14); #14=strlen('emailtemplate_')

						if (isset($template->data['emailtemplate'][$key])) {
							$template->data['emailtemplate'][$key] = $val;
						}
					}
				}
			}

			$template->build();
		}

		echo $template->fetch();
		exit;
	}

	/**
	 * Test module modifications
	 */
	public function test() {
		$this->load->language('module/emailtemplate');

		$this->load->model('module/emailtemplate');

		$this->model_module_emailtemplate->updateModification('core');
		$this->model_module_emailtemplate->updateModification('lang');
		$this->model_module_emailtemplate->updateModification();

		$this->session->data['success'] = $this->language->get('success_test');

		$this->session->data['attention'] = sprintf($this->language->get('text_modifications_refresh'), $this->url->link('extension/modification/refresh', 'token='.$this->session->data['token'], 'SSL'));

		if (isset($this->request->server['HTTP_REFERER'])) {
			$referer = html_entity_decode($this->request->server['HTTP_REFERER'], ENT_QUOTES, 'UTF-8');

			if ($referer) {
				$url = parse_url($referer, PHP_URL_QUERY);

				parse_str($url, $url_query);

				if (isset($url_query['route']) && isset($url_query['id'])) {
					$this->response->redirect($this->url->link($url_query['route'], 'token='.$this->session->data['token'] . '&id=' . $url_query['id'], 'SSL'));
				}
			}
		}

		$this->response->redirect($this->url->link('module/emailtemplate', 'token='.$this->session->data['token'], 'SSL'));
	}

	/**
	 * Check module installed
	 */
	public function installed() {
		// Db installed?
		$chk = $this->db->query("SHOW TABLES LIKE '". DB_PREFIX . "emailtemplate'");
		if (!$chk->num_rows) {
			$this->session->data['error'] = sprintf($this->language->get('error_missing_module'), $this->url->link('extension/module/install', 'token='.$this->session->data['token'] . '&extension=emailtemplate', 'SSL'));
			return false;
		}

		$chk = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = 'module' AND `code` = 'emailtemplate' LIMIT 1");
		if (!$chk->num_rows) {
			$this->session->data['error'] = sprintf($this->language->get('error_missing_module'), $this->url->link('extension/module/install', 'token='.$this->session->data['token'] . '&extension=emailtemplate', 'SSL'));
			return false;
		}

		// Core modification
		$this->load->model('module/emailtemplate');

		$modification_info = $this->model_module_emailtemplate->getModificationByCode("emailtemplates_core");

		if (!$modification_info) {
			$this->session->data['error'] = sprintf($this->language->get('error_missing_modifications'), $this->url->link('extension/module/install', 'token='.$this->session->data['token'] . '&extension=emailtemplate', 'SSL'));
			return false;
		}

		// Modifications refreshed?
		$this->load->model('tool/image');

		if (!method_exists('Mail', 'setEmailTemplate') || !method_exists($this->model_tool_image, 'setUrl')) {
			$this->session->data['error'] = sprintf($this->language->get('error_refresh_modifications'), $this->url->link('extension/modification/refresh', 'token='.$this->session->data['token'], 'SSL'));
			return false;
		}

		return true;
	}

	/**
	 * Opencart module install
	 */
	public function install() {
		$this->load->language('module/emailtemplate');

		$this->load->model('module/emailtemplate');

		$this->model_module_emailtemplate->uninstall();

		$this->model_module_emailtemplate->install();

		$this->model_module_emailtemplate->updateModification('core');

		$this->model_module_emailtemplate->updateModification('lang');

		$this->response->redirect($this->url->link('module/emailtemplate/installer', 'token='.$this->session->data['token'], 'SSL'));
	}

	/**
	 * Install module
	 */
	public function installer() {
		$this->load->language('module/emailtemplate');

		$this->load->model('module/emailtemplate');

		if ($this->installed()) {
			$this->data['install'] = true;

			$chk = $this->db->query("SELECT count(1) as count FROM `". DB_PREFIX . "emailtemplate`");

			if ($chk->num_rows  && $chk->row['count'] > 1) {
				$this->response->redirect($this->url->link('module/emailtemplate', 'token='.$this->session->data['token'], 'SSL'));
			}
		} else {
			$this->data['install'] = false;
		}

		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->model_module_emailtemplate->installTemplates($this->request->post)) {

			$this->session->data['success'] = $this->language->get('install_success');

			$this->response->redirect($this->url->link('module/emailtemplate', 'token='.$this->session->data['token'], 'SSL'));
		}

		foreach(array(
			'button_cancel',
			'button_install',
			'heading_install',
			'template_admin_affiliate_approve',
			'template_admin_affiliate_transaction',
			'template_admin_customer_approve',
			'template_admin_customer_create',
			'template_admin_customer_reward',
			'template_admin_customer_transaction',
			'template_admin_newsletter',
			'template_admin_return_history',
			'template_admin_voucher',
			'template_affiliate_forgotten',
			'template_affiliate_register',
			'template_affiliate_register_admin',
			'template_customer_forgotten',
			'template_customer_register',
			'template_customer_register_admin',
			'template_information_contact',
			'template_information_contact_customer',
			'template_order_admin',
			'template_order_customer',
			'template_order_return',
			'template_order_update',
			'template_order_voucher',
			'template_order_openbay_confirm',
			'template_order_openbay_update',
			'template_openbay_admin',
			'template_product_review',
			'text_admin',
			'text_affiliate',
			'text_all',
			'text_customer',
			'text_order',
			'text_openbay',
			'text_no',
			'text_yes'
		) as $var) {
			$this->data[$var] = $this->language->get($var);
		}

		$this->_setTitle($this->language->get('heading_install'));

		$this->_messages();

		$this->_breadcrumbs();

		$this->data['action'] = $this->url->link('module/emailtemplate/installer', 'token='.$this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/module', 'token='.$this->session->data['token'], 'SSL');

		$this->_js[] = 'view/javascript/bootstrap/js/bootstrap-checkbox.min.js';

		$this->_output('module/emailtemplate/install.tpl');
	}

	/**
	* Delete module settings for each store.
	*/
	public function uninstall() {
		$this->load->language('module/emailtemplate');

		if (!$this->user->hasPermission('modify', 'module/emailtemplate')) {
			$this->session->data['error'] = $this->language->get('error_permission');

			$this->response->redirect($this->url->link('extension/module', 'token='.$this->session->data['token'], 'SSL'));
		}

		$this->load->model('setting/store');
		$this->load->model('setting/setting');

		foreach ($this->model_setting_store->getStores() as $store) {
			$this->model_setting_setting->deleteSetting("emailtemplate", $store['store_id']);
		}

		$this->load->model('module/emailtemplate');

		$this->model_module_emailtemplate->uninstall();
	}

	/**
	 * Upgrade Extension
	 */
	public function upgrade() {
		$this->load->language('module/emailtemplate');
		$this->load->model('module/emailtemplate');

		if ($this->model_module_emailtemplate->upgrade()) {

			$this->session->data['success'] = $this->language->get('upgrade_success');
		}

		$this->response->redirect($this->url->link('module/emailtemplate', 'token='.$this->session->data['token'], 'SSL'));
	}


	/**
	 * Get Extension Form
	 */
	private function _config_form() {

		foreach(array(
			'button_cancel',
			'button_configs',
			'button_default',
			'button_delete',
			'button_preview',
			'button_restore',
			'button_save',
			'button_test',
			'button_update_preview',
			'button_withimages',
			'button_withoutimages',
			'entry_body',
			'entry_body_bg_color',
			'entry_body_bg_image',
			'entry_body_font_color',
			'entry_body_heading_color',
			'entry_body_link_color',
			'entry_bottom_left',
			'entry_bottom_right',
			'entry_color',
			'entry_corner_image',
			'entry_customer_group',
			'entry_end',
			'entry_email_width',
			'entry_footer',
			'entry_footer_text',
			'entry_head',
			'entry_head_text',
			'entry_header',
			'entry_header_bg_color',
			'entry_header_bg_image',
			'entry_header_border_color',
			'entry_height',
			'entry_image_repeat',
			'entry_label',
			'entry_language',
			'entry_limit',
			'entry_log',
			'entry_log_read',
			'entry_logo',
			'entry_logo_resize_options',
			'entry_overlap',
			'entry_page_align',
			'entry_page_bg_color',
			'entry_page_footer_text',
			'entry_related_products',
			'entry_responsive',
			'entry_selection',
			'entry_showcase',
			'entry_start',
			'entry_store',
			'entry_style',
			'entry_table_quantity',
			'entry_text_align',
			'entry_title',
			'entry_theme',
			'entry_top_left',
			'entry_top_right',
			'entry_tracking',
			'entry_tracking_campaign_name',
			'entry_tracking_campaign_term',
			'entry_view_browser_text',
			'entry_view_browser_theme',
			'entry_width',
			'entry_wrapper',
			'heading_config',
			'heading_footer',
			'heading_header',
			'heading_logs',
			'heading_preview',
			'heading_sections',
			'heading_settings',
			'heading_shadow',
			'heading_showcase',
			'heading_style',
			'heading_tracking',
			'help_logo',
			'text_align',
			'text_baseline',
			'text_bestsellers',
			'text_bottom',
			'text_bottom_left',
			'text_bottom_right',
			'text_center',
			'text_confirm',
			'text_create_config',
			'text_default',
			'text_desktop',
			'text_end',
			'text_font_size',
			'text_height',
			'text_latest',
			'text_left',
			'text_logo',
			'text_middle',
			'text_mobile',
			'text_no',
			'text_none',
			'text_order',
			'text_popular',
			'text_products',
			'text_right',
			'text_search',
			'text_select',
			'text_start',
			'text_specials',
			'text_tablet',
			'text_text_color',
			'text_top',
			'text_top_left',
			'text_top_right',
			'text_width',
			'text_yes'
		) as $var) {
			$this->data[$var] = $this->language->get($var);
		}

		$this->data['emailtemplate_config'] = $this->model_module_emailtemplate->getConfig($this->request->get['id']);

		if (!$this->data['emailtemplate_config']) {
			$this->response->redirect($this->url->link('module/emailtemplate', 'token='.$this->session->data['token'], 'SSL'));
		}

		$this->data['id'] = $this->request->get['id'];

		$cols = EmailTemplateConfigDAO::describe();
		foreach($cols as $col => $type) {
			if (isset($this->request->post[$col])) {
				$this->data['emailtemplate_config'][$col] = $this->request->post[$col];
			}
		}
		$this->data['emailtemplate_config'] = $this->model_module_emailtemplate->formatConfig($this->data['emailtemplate_config'], true);

		if ($this->request->get['id'] == 1) {
		    $this->_setTitle($this->language->get('heading_config') . ' - ' . $this->language->get('button_default'));
		} else {
		    $this->_setTitle($this->language->get('heading_config') . ' - ' . $this->data['emailtemplate_config']['name']);

		    $this->data['action_default'] = $this->url->link('module/emailtemplate/config', 'token='.$this->session->data['token'] . '&id=1', 'SSL');

		    $this->data['action_delete'] = $this->url->link('module/emailtemplate/config', 'token='.$this->session->data['token'] . '&id=' . $this->request->get['id'] . '&action=delete', 'SSL');

		    $this->load->model('localisation/language');
		    $this->data['languages'] = $this->model_localisation_language->getLanguages();

		    $this->data['stores'] = $this->model_module_emailtemplate->getStores();

		    $this->load->model('sale/customer_group');
		    $this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		}

		$this->data['action_insert_config'] = $this->url->link('module/emailtemplate/config', 'token='.$this->session->data['token'] . '&id=' . $this->request->get['id'] . '&action=create', 'SSL');

		$result = $this->db->query("SELECT emailtemplate_config_id, emailtemplate_config_name FROM " . DB_PREFIX . "emailtemplate_config WHERE emailtemplate_config_id != '" . (int)$this->request->get['id'] . "' AND emailtemplate_config_id != 1");
		if ($result->rows) {
			$this->data['action_configs'] = array();

			foreach($result->rows as $row) {
				$this->data['action_configs'][] = array(
					'id' => $row['emailtemplate_config_id'],
					'name' => $row['emailtemplate_config_name'],
					'url' =>$this->url->link('module/emailtemplate/config', 'token='.$this->session->data['token'] . '&id=' . $row['emailtemplate_config_id'], 'SSL')
				);
			}
		}

		$this->data['action'] = $this->url->link('module/emailtemplate/config', 'token='.$this->session->data['token'] . '&id=' . $this->request->get['id'], 'SSL');

		$this->data['cancel'] = $this->url->link('module/emailtemplate', 'token='.$this->session->data['token'], 'SSL');

		$this->load->model('tool/image');

		$this->data['no_image'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		$this->data['no_shadow_image'] = $this->model_tool_image->resize('no_image.png', 17, 17);

		# Installed Themes
		$this->data['themes'] = array();
		$directories = glob(DIR_CATALOG . 'view/theme/*', GLOB_ONLYDIR);
		foreach ($directories as $directory) {
			$this->data['themes'][] = basename($directory);
		}

		if (!empty($this->data['emailtemplate_config']['language_id'])) {
			$language_id = $this->data['emailtemplate_config']['language_id'];
		} else {
			$language_id = $this->config->get('config_language_id');
		}

		if (isset($this->data['emailtemplate_config']['store_id'])) {
			$store_id = $this->data['emailtemplate_config']['store_id'];
		} else {
			$store_id = 0;
		}

		$result = $this->db->query("SELECT GROUP_CONCAT(emailtemplate_key SEPARATOR ', ') AS `keys` FROM " . DB_PREFIX . "emailtemplate WHERE emailtemplate_default = 1 AND emailtemplate_id !=1");

		if ($result->row) {
			$diff = array_diff(EmailTemplate::$original_templates, explode(', ', $result->row['keys']));

			if ($diff) {
				$this->data['templates_restore'] = array();

				foreach($diff as $key) {
					$this->data['templates_restore'][] = array(
						'name' => $key,
						'url' => $this->url->link('module/emailtemplate/template_restore', 'token='.$this->session->data['token'] . '&key='. $key . '&id='.$this->request->get['id'], 'SSL')
					);
				}
			}
		}

		if ($this->data['emailtemplate_config']['showcase'] == 'products') {
			$showcase_template = new EmailTemplate($this->request, $this->registry);

			$showcase_template->load(array(
				'emailtemplate_config_id' => $this->data['emailtemplate_config'],
				'store_id' => $this->data['emailtemplate_config']['store_id'],
				'emailtemplate_id' => 1
			));

			$showcase_template->build();

			$this->data['showcase_selection'] = $this->model_module_emailtemplate->getShowcase($showcase_template->data);
		}

		$order_id = 0;
		$result = $this->db->query("SELECT `order_id` FROM `" . DB_PREFIX . "order` WHERE `store_id` = '" . (int)$store_id . "' AND (`language_id` = '". (int)$language_id . "' OR `language_id` > 0) ORDER BY `order_id` DESC LIMIT 1");
		if ($result->row) {
			$order_id = $result->row['order_id'];
		} else {
			$result = $this->db->query("SELECT `order_id` FROM `" . DB_PREFIX . "order` WHERE (`language_id` = '{$language_id}' OR `language_id` > 0) ORDER BY `order_id` DESC LIMIT 1");
			if ($result->row) {
				$order_id = $result->row['order_id'];
			}
		}

		if ($order_id) {
			$this->data['preview_order_id'] = $order_id;
		} else {
			$this->data['error_preview_order'] = $this->language->get('error_preview_order');
		}
	}

	/**
	 * Get Templates
	 */
	private function _config_style(array $data) {
		foreach(array('top', 'bottom', 'left', 'right') as $place) {
			foreach(array('length', 'overlap', 'start', 'end', 'left_img', 'right_img') as $var) {
				$data['emailtemplate_config_shadow_'.$place][$var] = '';
				$data['emailtemplate_config_shadow_'.$place][$var] = '';
				$data['emailtemplate_config_shadow_'.$place][$var] = '';
				$data['emailtemplate_config_shadow_'.$place][$var] = '';
			}
		}

		$data['emailtemplate_config_head_section_bg_color'] = '';
		$data['emailtemplate_config_header_section_bg_color'] = '';
		$data['emailtemplate_config_body_section_bg_color'] = '';
		$data['emailtemplate_config_footer_section_bg_color'] = '';

		switch($data['emailtemplate_config_style']) {
			case 'white':
				$data['emailtemplate_config_wrapper_tpl'] = '_main.tpl';
				$data['emailtemplate_config_body_bg_color'] = '#FFFFFF';
				$data['emailtemplate_config_page_bg_color'] = '#F9F9F9';
				$data['emailtemplate_config_body_font_color'] = '#333333';

				$data['emailtemplate_config_shadow_top'] = array(
					'length' => '',
					'overlap' => '',
					'start' => '',
					'end' => '',
					'left_img' => '',
					'right_img' => ''
				);

				$data['emailtemplate_config_shadow_bottom'] = array(
					'length' => 9,
					'overlap' => 8,
					'start' => '#d4d4d4',
					'end' => '#ffffff',
					'left_img' => 'catalog/emailtemplate/white/bottom_left.png',
					'right_img' => 'catalog/emailtemplate/white/bottom_right.png'
				);

				$data['emailtemplate_config_shadow_left'] = array(
					'length' => 9,
					'overlap' => 8,
					'start' => '#ffffff',
					'end' => '#d4d4d4'
				);

				$data['emailtemplate_config_shadow_right'] = array(
					'length' => 9,
					'overlap' => 8,
					'start' => '#d4d4d4',
					'end' => '#ffffff'
				);
			break;

			case 'page':
				$data['emailtemplate_config_wrapper_tpl'] = '_main.tpl';
				$data['emailtemplate_config_body_bg_color'] = '#F9F9F9';
				$data['emailtemplate_config_page_bg_color'] = '#FFFFFF';
				$data['emailtemplate_config_body_font_color'] = '#333333';

				$data['emailtemplate_config_shadow_top'] = array(
					'length' => '',
					'overlap' => '',
					'start' => '',
					'end' => '',
					'left_img' => '',
					'right_img' => ''
				);

				$data['emailtemplate_config_shadow_bottom'] = array(
					'length' => 9,
					'overlap' => 8,
					'start' => '#d4d4d4',
					'end' => '#f8f8f8',
					'left_img' => 'catalog/emailtemplate/gray/bottom_left.png',
					'right_img' => 'catalog/emailtemplate/gray/bottom_right.png'
				);

				$data['emailtemplate_config_shadow_left'] = array(
					'length' => 9,
					'overlap' => 8,
					'start' => '#f8f8f8',
					'end' => '#d4d4d4'
				);

				$data['emailtemplate_config_shadow_right'] = array(
					'length' => 9,
					'overlap' => 8,
					'start' => '#d4d4d4',
					'end' => '#f8f8f8'
				);
			break;

			case 'clean':
				$data['emailtemplate_config_wrapper_tpl'] = '_main.tpl';
				$data['emailtemplate_config_body_bg_color'] = '#FFFFFF';
				$data['emailtemplate_config_page_bg_color'] = '#FFFFFF';
				$data['emailtemplate_config_body_font_color'] = '#333333';
			break;

			case 'border':
				$data['emailtemplate_config_wrapper_tpl'] = '_main.tpl';
				$data['emailtemplate_config_body_font_color'] = '#333333';
				foreach(array('bottom', 'left', 'right') as $place) {
					$data['emailtemplate_config_shadow_'.$place] = array(
						'length' => 1,
						'overlap' => 0,
						'start' => '#515151',
						'end' => '#515151',
						'left_img' => '',
						'right_img' => ''
					);
				}
			break;

			case 'sections':
				$data['emailtemplate_config_wrapper_tpl'] = '_main.tpl';
				$data['emailtemplate_config_body_bg_color'] = '#FFFFFF';
				$data['emailtemplate_config_page_bg_color'] = '#FFFFFF';
				$data['emailtemplate_config_body_section_bg_color'] = '#FFFFFF';
				$data['emailtemplate_config_body_font_color'] = '#333333';
				$data['emailtemplate_config_header_section_bg_color'] = $data['emailtemplate_config_header_bg_color'];

				$rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
				$data['emailtemplate_config_head_section_bg_color'] = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
				$data['emailtemplate_config_footer_section_bg_color'] = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];

				foreach(array('top', 'bottom', 'left', 'right') as $place) {
					$data['emailtemplate_config_shadow_'.$place] = array(
						'length' => '',
						'overlap' => '',
						'start' => '',
						'end' => '',
						'left_img' => '',
						'right_img' => ''
					);
				}
			break;

			case 'inner_page':
				$data['emailtemplate_config_wrapper_tpl'] = '_main_clean.tpl';
				$data['emailtemplate_config_body_bg_color'] = '#FFFFFF';
				$data['emailtemplate_config_page_bg_color'] = '#E85140';
				$data['emailtemplate_config_body_font_color'] = '#FFFFFF';

				foreach(array('top', 'bottom', 'left', 'right') as $place) {
					$data['emailtemplate_config_shadow_'.$place] = array(
						'length' => '',
						'overlap' => '',
						'start' => '',
						'end' => '',
						'left_img' => '',
						'right_img' => ''
					);
				}
			break;
		}

		return $data;
	}

	/**
	 * Get Templates
	 */
	private function _template_list($data = null) {
		if (is_null($data)) $data = $this->request->get;

		foreach(array(
			'button_cancel',
			'button_default',
			'button_delete',
			'button_delete_shortcodes',
			'button_disable',
			'button_enable',
			'button_tools',
			'column_config',
			'column_key',
			'column_label',
			'column_modified',
			'column_status',
			'column_shortcodes',
			'heading_config',
			'heading_logs',
			'heading_modification',
			'heading_templates',
			'heading_title',
			'text_admin',
			'text_affiliate',
			'text_all',
			'text_confirm',
			'text_customer',
			'text_create_template',
			'text_order'
		) as $var) {
			$this->data[$var] = $this->language->get($var);
		}

		$this->data['version'] = EmailTemplate::$version;

		if (isset($data['store_id']) && is_numeric($data['store_id'])) {
			$this->data['templates_store_id'] = $data['store_id'];
		} else {
			$this->data['templates_store_id'] = NULL;
		}

		if (isset($data['customer_group_id'])) {
			$this->data['templates_customer_group_id'] = $data['customer_group_id'];
		} else {
			$this->data['templates_customer_group_id'] = '';
		}

		if (isset($data['key'])) {
			$this->data['templates_key'] = $data['key'];
		} else {
			$this->data['templates_key'] = '';
		}

		if (isset($data['filter_type'])) {
			$this->data['emailtemplate_type'] = $data['filter_type'];
		} else {
			$this->data['emailtemplate_type'] = 'order';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'modified';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = 15;
		$filter = array(
			'language_id' => $this->config->get('config_language_id'),
			'store_id' => $this->data['templates_store_id'],
			'customer_group_id' => $this->data['templates_customer_group_id'],
			'emailtemplate_key' => $this->data['templates_key'],
			'emailtemplate_default' => 1,
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);

		if (!empty($this->data['emailtemplate_type'])) {
			$filter['emailtemplate_type'] = $this->data['emailtemplate_type'];
		}

		if (isset($data['not_emailtemplate_id'])) {
			$filter['not_emailtemplate_id'] = $data['not_emailtemplate_id'];
		}

		if (isset($data['default'])) {
			$filter['emailtemplate_default'] = $data['default'];
		}

		$emailtemplate_configs = array();
		foreach($this->model_module_emailtemplate->getConfigs() as $emailtemplate_config){
			$emailtemplate_configs[$emailtemplate_config['emailtemplate_config_id']] = $emailtemplate_config;
		}

		$templates_total = $this->model_module_emailtemplate->getTotalTemplates($filter);
		$results = $this->model_module_emailtemplate->getTemplates($filter);

		$this->data['templates'] = array();
		foreach ($results as $item) {
			$row = array(
				'id' 		  	=> $item['emailtemplate_id'],
				'emailtemplate_config_id' => $item['emailtemplate_config_id'],
				'store_id' 		=> $item['store_id'],
				'customer_group_id' => $item['customer_group_id'],
				'key'    	  	=> $item['emailtemplate_key'],
				'name'    	  	=> $item['emailtemplate_label'] ? $item['emailtemplate_label'] : $item['emailtemplate_key'],
				'label'    	  	=> $item['emailtemplate_label'],
				'template'    	=> $item['emailtemplate_template'],
				'status'      	=> $item['emailtemplate_status'],
				'default'      	=> $item['emailtemplate_default'],
				'shortcodes'    => $item['emailtemplate_shortcodes'],
				'action'		=> $this->url->link('module/emailtemplate/template', 'token='.$this->session->data['token'] . '&id=' . $item['emailtemplate_id'], 'SSL'),
				'selected'	  	=> isset($this->request->post['selected']) && in_array($item['emailtemplate_id'], $this->request->post['selected'])
			);

			if(isset($emailtemplate_configs[$item['emailtemplate_config_id']])){
				$row['config'] = $emailtemplate_configs[$item['emailtemplate_config_id']]['emailtemplate_config_name'];
				$row['config_url'] = $this->url->link('module/emailtemplate/config', 'token='.$this->session->data['token'] . '&id=' . $item['emailtemplate_config_id'], 'SSL');
			}

			$modified = strtotime($item['emailtemplate_modified']);
			if (date('Ymd') == date('Ymd', $modified)) {
				//$row['modified'] = date($this->language->get('time_format'), $modified);
				$row['modified'] = date('H:i', $modified);
			} else {
				$row['modified'] = date($this->language->get('date_format_short'), $modified);
			}

			$row['custom_templates'] = $this->model_module_emailtemplate->getTemplates(array(
				'emailtemplate_key' => $item['emailtemplate_key'],
				'emailtemplate_default' => 0
			));

			$row['custom_count'] = count($row['custom_templates']);

			foreach ($row['custom_templates'] as $i => $custom_templates) {
				$row['custom_templates'][$i]['action'] = $this->url->link('module/emailtemplate/template', 'token='.$this->session->data['token'] . '&id=' . $custom_templates['emailtemplate_id'], 'SSL');
			}

			if ($item['store_id'] >= 0) {
				$stores = $this->model_module_emailtemplate->getStores($item['store_id']);

				if (isset($stores[$row['store_id']])) {
					$row['store'] = $stores[$row['store_id']];
				} else {
					$row['store'] = current($stores);
				}
			}

			if ($item['customer_group_id']) {
				$row['customer_group'] = $this->model_sale_customer_group->getCustomerGroup($item['customer_group_id']);
			}

			$this->data['templates'][] = $row;
		}

		$this->data['token'] = $this->session->data['token'];

		$url = '';

		if (isset($this->request->get['filter_type'])) {
			$url .= '&filter_type=' . $this->request->get['filter_type'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($data['page'])) {
			$url .= '&page=' . $data['page'];
		}

		$this->data['sort_label'] = $this->url->link('module/emailtemplate', 'token='.$this->session->data['token'] . '&sort=label' . $url, 'SSL');
		$this->data['sort_key'] = $this->url->link('module/emailtemplate', 'token='.$this->session->data['token'] . '&sort=key' . $url, 'SSL');
		$this->data['sort_template'] = $this->url->link('module/emailtemplate', 'token='.$this->session->data['token'] . '&sort=template' . $url, 'SSL');
		$this->data['sort_shortcodes'] = $this->url->link('module/emailtemplate', 'token='.$this->session->data['token'] . '&sort=shortcodes' . $url, 'SSL');
		$this->data['sort_default'] = $this->url->link('module/emailtemplate', 'token='.$this->session->data['token'] . '&sort=default' . $url, 'SSL');
		$this->data['sort_config'] = $this->url->link('module/emailtemplate', 'token='.$this->session->data['token'] . '&sort=config' . $url, 'SSL');
		$this->data['sort_content'] = $this->url->link('module/emailtemplate', 'token='.$this->session->data['token'] . '&sort=content' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('module/emailtemplate', 'token='.$this->session->data['token'] . '&sort=status' . $url, 'SSL');
		$this->data['sort_store'] = $this->url->link('module/emailtemplate', 'token='.$this->session->data['token'] . '&sort=store' . $url, 'SSL');
		$this->data['sort_modified'] = $this->url->link('module/emailtemplate', 'token='.$this->session->data['token'] . '&sort=modified' . $url, 'SSL');
		$this->data['sort_customer_group'] = $this->url->link('module/emailtemplate', 'token='.$this->session->data['token'] . '&sort=customer_group' . $url, 'SSL');

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$url = '';

		if (isset($data['sort'])) {
			$url .= '&sort=' . $data['sort'];
		}

		if (isset($data['order'])) {
			$url .= '&order=' . $data['order'];
		}

		if (isset($this->request->get['filter_type'])) {
			$url .= '&filter_type=' . $this->request->get['filter_type'];
		}

		$link = $this->url->link('module/emailtemplate', 'token='.$this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->_renderPagination($link, $page, $templates_total, $limit);
	}


	/**
	 * Get Template Shortcodes
	 */
	private function _shortcodes_list($data = null) {
		if (is_null($data)) $data = $this->request->get;

		if (!isset($data['id'])) {
			return false;
		}

		$this->data['shortcode_emailtemplate_id'] = $data['id'];

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'code';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($data['limit'])) {
			$limit = $data['limit'];
		} else {
			$limit = 15;
		}


		$filter = array(
			'emailtemplate_id'  => $this->data['shortcode_emailtemplate_id'],
			'start' => ($page - 1) * $limit,
			'limit' => $limit,
			'sort'  => $sort,
			'order' => $order
		);

		$results = $this->model_module_emailtemplate->getTemplateShortcodes($filter);

		$total = $this->model_module_emailtemplate->getTotalTemplateShortcodes($filter);

		$this->data['shortcodes'] = array();
		foreach ($results as $item) {
			$example = strip_tags(html_entity_decode($item['emailtemplate_shortcode_example'], ENT_QUOTES, 'UTF-8'));

			if (strlen($example) > 300) {
				$example = substr($example, 0, 300);
				$example = substr($example, 0, strrpos($example, ' '));
				$example .= '...';
			}

			$this->data['shortcodes'][] = array(
				'id' 	   => $item['emailtemplate_shortcode_id'],
				'code' 	   => $item['emailtemplate_shortcode_code'],
				'type' 	   => $item['emailtemplate_shortcode_type'],
				'example'  => $example,
				'url_edit'  => $this->url->link('module/emailtemplate/template_shortcode', 'token='.$this->session->data['token'].'&id='.$item['emailtemplate_shortcode_id'], 'SSL')
			);
		}

		$url = '';
		if (isset($data['sort'])) {
			$url .= '&sort=' . $data['sort'];
		}
		if (isset($data['order'])) {
			$url .= '&order=' . $data['order'];
		}
		if (isset($this->request->get['id'])) {
			$url .= '&id='.$this->request->get['id'];
		}

		$link = $this->url->link('module/emailtemplate/template', 'token='.$this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->_renderPagination($link, $page, $total, $limit, 'select');

		$url = '';
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}
		if (isset($this->request->get['id'])) {
			$url .= '&id='.$this->request->get['id'];
		}

		$this->data['sort_code'] = $this->url->link('module/emailtemplate/template', 'token='.$this->session->data['token'] . '&sort=code' . $url, 'SSL');
		$this->data['sort_example'] = $this->url->link('module/emailtemplate/template', 'token='.$this->session->data['token'] . '&sort=example' . $url, 'SSL');

		$this->data['action'] = $this->url->link('module/emailtemplate/template', 'token='.$this->session->data['token'] . '&id='.$this->request->get['id'], 'SSL');

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
	}

	/**
	 * Get template form
	 */
	private function _template_form() {
		if (empty($this->request->get['id'])) {
			$this->response->redirect($this->url->link('module/emailtemplate', 'token='.$this->session->data['token'], 'SSL'));
		}

		$this->load->model('localisation/language');
		$this->load->model('localisation/order_status');
		$this->load->model('tool/image');
		$this->load->model('sale/customer_group');

		$this->load->language('module/emailtemplate');

		foreach(array(
			'button_add',
			'button_cancel',
			'button_create',
			'button_delete',
			'button_default',
			'button_default_template',
			'button_templates',
			'button_preview',
			'button_save',
			'button_update_preview',
			'button_withimages',
			'button_withoutimages',
			'entry_attach_invoice',
			'entry_attach_invoice_info',
			'entry_comment',
			'entry_condition',
			'entry_content_count',
			'entry_customer_group',
			'entry_key',
			'entry_label',
			'entry_language',
			'entry_language_files',
			'entry_log_template',
			'entry_mail_to',
			'entry_mail_from',
			'entry_mail_html',
			'entry_mail_plain_text',
			'entry_mail_sender',
			'entry_mail_replyto',
			'entry_mail_replyto_name',
			'entry_mail_cc',
			'entry_mail_bcc',
			'entry_mail_attachment',
			'entry_order_status',
			'entry_preheader',
			'entry_showcase',
			'entry_status',
			'entry_store',
			'entry_subject',
			'entry_template_config',
			'entry_template_file',
			'entry_tracking_campaign_source',
			'entry_type',
			'entry_unsubscribe',
			'entry_view_browser',
			'entry_wrapper',
			'column_code',
			'column_example',
			'heading_mail',
			'heading_settings',
			'heading_shortcodes',
			'heading_template_create',
			'heading_preview',
			'text_add_editor',
			'text_admin',
			'text_catalog',
			'text_config',
			'text_confirm',
			'text_create_template',
			'text_default',
			'text_desktop',
			'text_disabled',
			'text_enabled',
			'text_mobile',
			'text_placeholder_custom',
			'text_select',
			'text_tablet',
			'text_no',
			'text_yes'
		) as $var) {
			$this->data[$var] = $this->language->get($var);
		}

		$this->_messages();

		$emailtemplate = $this->model_module_emailtemplate->getTemplate($this->request->get['id'], 0);

		if (!$emailtemplate) {
			$this->response->redirect($this->url->link('module/emailtemplate', 'token='.$this->session->data['token'], 'SSL'));
		}

		$result = $this->model_module_emailtemplate->getTemplateDescription(array('emailtemplate_id' => $emailtemplate['emailtemplate_id']));

		$emailtemplate['descriptions'] = array();
		foreach($result as $row) {
			$emailtemplate['descriptions'][$row['language_id']] = $row;
		}

		$config = $this->data['emailtemplate_config'] = $this->model_module_emailtemplate->getConfig(array(
			'store_id' => ($emailtemplate['store_id']) ? $emailtemplate['store_id'] : 0
		));

		// Default and similar templates
		$this->data['template_similar'] = array();

		if ($emailtemplate['emailtemplate_default']) {
			$this->data['default_emailtemplate_id'] = $emailtemplate['emailtemplate_id'];
		}

		$templates = $this->model_module_emailtemplate->getTemplates(array('emailtemplate_key' => $emailtemplate['emailtemplate_key']));

		if ($templates) {
			foreach($templates as $template) {
				if ($template['emailtemplate_id'] != $emailtemplate['emailtemplate_id']) {

					if ($template['emailtemplate_default']) {
						$this->data['default_emailtemplate_id'] = $template['emailtemplate_id'];

						$this->data['template_default_url'] = $this->url->link('module/emailtemplate/template', 'token='.$this->session->data['token'] . '&id='.$template['emailtemplate_id'], 'SSL');
					} else {
						$this->data['template_similar'][] = array(
							'name' => $template['emailtemplate_label'],
							'url' => $this->url->link('module/emailtemplate/template', 'token='.$this->session->data['token'] . '&id='.$template['emailtemplate_id'], 'SSL')
						);
					}

				}
			}
		}

		$this->_shortcodes_list(array('id' => $emailtemplate['emailtemplate_id'], 'limit' => 50));

		if (!empty($this->data['shortcodes'])) {
			$this->data['shortcodes_data'] = array();
			foreach($this->data['shortcodes'] as $row) {
				$this->data['shortcodes_data'][$row['code']] = $row['example'];
			}
		}

		$this->data['action'] = $this->url->link('module/emailtemplate/template', 'token='.$this->session->data['token'] . '&id=' . $this->request->get['id'], 'SSL');

		$this->data['action_insert_template'] = $this->url->link('module/emailtemplate/template', 'token='.$this->session->data['token'] . '&key=' . $emailtemplate['emailtemplate_key'], 'SSL');

		$this->_setTitle($emailtemplate['emailtemplate_label'] . ' &raquo; ' . $this->language->get('heading_template_edit'));

		$this->_breadcrumbs(array('heading_template_edit' => array(
			'link' => 'module/emailtemplate/template',
			'params' => '&id='.$this->request->get['id']
		)));

		$this->data['cancel'] = $this->url->link('module/emailtemplate', 'token='.$this->session->data['token'], 'SSL');

		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		$this->data['emailtemplate'] = array();
		$cols = EmailTemplateDAO::describe();
		foreach($cols as $col => $type) {
			$key = (strpos($col, 'emailtemplate_') === 0 && substr($col, -3) != '_id') ? substr($col, 14) : $col;
			if (isset($this->request->post[$col])) {
				$this->data['emailtemplate'][$key] = $this->request->post[$col];
			} elseif (isset($emailtemplate[$col])) {
				$this->data['emailtemplate'][$key] = $emailtemplate[$col];
			} else {
				$this->data['emailtemplate'][$key] = '';
			}
		}

		$descriptionCols = EmailTemplateDescriptionDAO::describe();
		$this->data['emailtemplate_description'] = array();

		foreach($this->data['languages'] as &$language) {
			$row = array();

			if ($language['language_id'] == $this->config->get('config_language_id')) {
				$language['default'] = 1;
			} else {
				$language['default'] = 0;
			}

			foreach($descriptionCols as $col => $type) {
				$key = (strpos($col, 'emailtemplate_description_') === 0) ? substr($col, 26) : $col;

				if (isset($this->request->post[$col][$language['language_id']])) {
					$value = $this->request->post[$col][$language['language_id']];
				} elseif (isset($emailtemplate['descriptions'][$language['language_id']][$col])) {
					$value = $emailtemplate['descriptions'][$language['language_id']][$col];
				} else {
					$value = '';
				}

				$row[$key] = $value;
			}

			$this->data['emailtemplate_description'][$language['language_id']] = $row;
		}
		unset($language);

		$modified = strtotime($this->data['emailtemplate']['modified']);
		if (date('Ymd') == date('Ymd', $modified)) {
			$this->data['emailtemplate']['modified'] = date($this->language->get('time_format'), $modified);
		} else {
			$this->data['emailtemplate']['modified'] = date($this->language->get('date_format_short'), $modified);
		}

		$this->data['emailtemplate_files'] = $this->model_module_emailtemplate->getTemplateFiles($config['emailtemplate_config_theme']);

		if (substr($this->data['emailtemplate']['key'], 0, 6) == 'admin.') {
			$this->data['emailtemplate_template_path'] = 'admin/view/template/mail/';
		} else {
			$this->data['emailtemplate_template_path'] = 'catalog/view/theme/' . $config['emailtemplate_config_theme'] . '/template/mail/';
		}

		if ($this->data['emailtemplate']['emailtemplate_config_id']) {
			$this->data['config_url'] = $this->url->link('module/emailtemplate/config', 'token='.$this->session->data['token'] . '&id=' . $this->data['emailtemplate']['emailtemplate_config_id'], 'SSL');
		} else {
			$this->data['config_url'] = $this->url->link('module/emailtemplate/config', 'token='.$this->session->data['token'] . '&id=1', 'SSL');
		}

		$this->data['emailtemplate_configs'] = $this->model_module_emailtemplate->getConfigs(array(), true, true);

		if (isset($emailtemplate['emailtemplate_id'])) {
			$this->data['emailtemplate_shortcodes'] = $this->model_module_emailtemplate->getTemplateShortcodes(array('emailtemplate_id' => $emailtemplate['emailtemplate_id']), true);

			// Get defualt template shortcodes
			if (empty($this->data['emailtemplate_shortcodes'])) {
				$this->data['emailtemplate_shortcodes'] = $this->model_module_emailtemplate->getTemplateShortcodes(array('emailtemplate_key' => $emailtemplate['emailtemplate_key']), true);
			}
		}

		$this->data['no_image'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		$this->data['stores'] = $this->model_module_emailtemplate->getStores();

		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

		$key = isset($this->request->get['key']) ? $this->request->get['key'] : $this->data['emailtemplate']['key'];
		switch($key) {
			case 'order.admin':
			case 'order.customer':
			case 'order.update':
				$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
			break;
		}

		if ($this->config->get('pdf_invoice')) {
			$this->data['pdf_invoice_installed'] = true;
		}
	}

	/**
	 * Get create template form
	 */
	private function _template_form_create() {
		$this->load->language('module/emailtemplate');

		foreach(array(
			'button_cancel',
			'button_create',
			'entry_key',
			'entry_label',
			'heading_template_create',
			'help_template_key',
			'text_admin',
			'text_catalog',
			'text_select',
			'text_placeholder_custom'
		) as $var) {
			$this->data[$var] = $this->language->get($var);
		}

		$this->_messages();

		$this->data['action'] = $this->url->link('module/emailtemplate/template', 'token='.$this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('module/emailtemplate', 'token='.$this->session->data['token'], 'SSL');

		$this->data['insertMode'] = true;

		$this->data['emailtemplate_keys'] = $this->model_module_emailtemplate->getTemplateKeys();

		$this->data['emailtemplate'] = array();
		$cols = EmailTemplateDAO::describe();
		foreach($cols as $col => $type) {
			$key = (strpos($col, 'emailtemplate_') === 0 && substr($col, -3) != '_id') ? substr($col, 14) : $col;
			if (isset($this->request->post[$col])) {
				$this->data['emailtemplate'][$key] = $this->request->post[$col];
			} else {
				$this->data['emailtemplate'][$key] = '';
			}
		}

		if (isset($this->request->post['emailtemplate_key_select'])) {
			$this->data['emailtemplate']['key_select'] = $this->request->post['emailtemplate_key_select'];
		} elseif (isset($this->request->get['key'])) {
			$this->data['emailtemplate']['key_select'] = $this->request->get['key'];
		} else {
			$this->data['emailtemplate']['key_select'] = '';
		}

		$this->_setTitle($this->language->get('heading_template_create'));

		$this->_breadcrumbs(array('heading_template_create' => array(
			'link' => 'module/emailtemplate/template'
		)));
	}

	/**
	 * Get template shortcode form
	 */
	private function _template_shortcode_form() {
		$this->load->language('module/emailtemplate');

		foreach(array(
			'heading_template_shortcode',
			'button_save',
			'entry_code',
			'entry_example',
			'entry_type',
			'text_auto',
			'text_language'
		) as $var) {
			$this->data[$var] = $this->language->get($var);
		}

		$this->_messages();

		$this->data['action'] = $this->url->link('module/emailtemplate/template_shortcode', 'token='.$this->session->data['token'] . '&id=' . $this->request->get['id'], 'SSL');

		$shortcodes = $this->model_module_emailtemplate->getTemplateShortcodes(array('emailtemplate_shortcode_id' => $this->request->get['id']));
		$shortcode = $shortcodes[0];

		$this->_breadcrumbs(array('heading_template_shortcode' => array(
			'link' => 'module/emailtemplate/template_shortcode',
			'params' => '&id='.$this->request->get['id']
		)));

		$this->_setTitle($this->language->get('heading_template_shortcode') . ' &raquo; ' . $shortcode['emailtemplate_shortcode_code']);

		$this->data['cancel'] = $this->url->link('module/emailtemplate/template', 'token='.$this->session->data['token'] . '&id=' . $shortcode['emailtemplate_id'], 'SSL');

		$this->data['shortcode'] = array();

		$cols = EmailTemplateShortCodesDAO::describe();

		foreach($cols as $col => $type) {
			$key = (strpos($col, 'emailtemplate_shortcode_') === 0 && substr($col, -3) != '_id') ? substr($col, 24) : $col;

			if (isset($this->request->post[$col])) {
				$this->data['shortcode'][$key] = $this->request->post[$col];
			} elseif (isset($shortcode[$col])) {
				$this->data['shortcode'][$key] = $shortcode[$col];
			} else {
				$this->data['shortcode'][$key] = '';
			}
		}
	}

	/**
	 * Send Test Email with demo template
	 */
	private function _sendTestEmail($toAddress, $store_id = 0, $language_id = 1) {
	    $result = $this->db->query("SELECT `order_id` FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0' ORDER BY `order_id` DESC LIMIT 1");

	    if ($result->row) {
	        $data = array(
	            'store_id' => $store_id,
	            'language_id' => $language_id
	        );

	        $this->load->model('module/emailtemplate');

	        $template = $this->model_module_emailtemplate->getCompleteOrderEmail($result->row['order_id'], $data);

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

	        $mail->setFrom($this->config->get('config_email'));
	        $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
	        $mail = $template->hook($mail);
	        $mail->setSubject(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
	        $mail->setText($template->getPlainText());
	        $mail->setTo($toAddress);
	        if ($mail->send()) {
	            return true;
	        }
	        return true;  # false; always returns true bug in mail.php
	    } else {
	        $this->session->data['error'] = $this->language->get('text_no_orders');
	    }

	    return false;
	}

	/**
	 * Populates $this->data with error_* keys using data from $this->error
	 */
	private function _messages() {
		# Attention
		if (isset($this->session->data['attention'])) {
			$this->data['error_attention'] = $this->session->data['attention'];
			unset($this->session->data['attention']);
		} else {
			$this->data['error_attention'] = '';
		}

		# Error
		if (isset($this->session->data['error'])) {
			$this->data['error_warning'] = $this->session->data['error'];
			unset($this->session->data['error']);
		} else {
			$this->data['error_warning'] = '';
		}
		foreach ($this->error as $key => $val) {
			$this->data["error_{$key}"] = $val;
		}

		# Success
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
	}

	/**
	 * Populates breadcrumbs array for $this->data
	 */
	private function _breadcrumbs($crumbs = array(), $home = true) {
		$bc = array();
		$bc_map = array(
			'text_home' => array('link' => 'common/home', 'params' => ''),
			'text_modules' => array('link' => 'extension/module', 'params' => '')
		);

		if ($home) {
			$bc_map = array_merge($bc_map, array('text_module' => array('link' => 'module/emailtemplate')));
		}
		$bc_map = array_merge($bc_map, $crumbs);

		foreach ($bc_map as $name => $item) {
			$bc[]= array(
				'text' => $this->language->get($name),
				'href' => $this->url->link($item['link'], 'token='.$this->session->data['token'] . (isset($item['params']) ? $item['params'] : ''), 'SSL')
			);
		}
   		$this->data['breadcrumbs'] = $bc;
	}

	/**
	 * Validate form data
	 */
	private function _validateConfig($data) {
		if (!$this->user->hasPermission('modify', 'module/emailtemplate')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!isset($data['emailtemplate_config_name']) || $data['emailtemplate_config_name'] == '') {
			$this->error['emailtemplate_config_name'] = $this->language->get('error_required');
		}

		# Check directory and images exist
		if (!empty($data['emailtemplate_config_theme'])) {
			$dir = DIR_CATALOG . 'view/theme/' . $data['emailtemplate_config_theme'] . '/template/mail/_main.tpl';
			if (!file_exists($dir)) {
				$this->error['emailtemplate_config_theme'] = sprintf($this->language->get('error_theme'), $dir);
			}
		}

		# Validate logo contains space or special character
		if ($data['emailtemplate_config_logo']) {
			$logo = $data['emailtemplate_config_logo'];
			if ($logo && preg_match('/[^\w.-]/', basename($logo))) {
				$this->error['emailtemplate_config_logo'] = sprintf($this->language->get('error_logo_filename'), $logo);
			}
		}

		if ($this->error) {
			if (!isset($this->error['warning'])) {
				$this->error['warning'] = $this->language->get('error_warning');
			}
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Validate template form data
	 */
	private function _validateTemplate($data) {
		if (!$this->user->hasPermission('modify', 'module/emailtemplate')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		// #1 key empty and select no set #2 either empty
		if (($data['emailtemplate_key'] == '' && !isset($data['emailtemplate_key_select'])) || ($data['emailtemplate_key'] == '' && empty($data['emailtemplate_key_select']))) {
			$this->error['emailtemplate_key'] = $this->language->get('error_required');
		} elseif ($data['emailtemplate_key'] != '' && !empty($data['emailtemplate_key_select'])) {
			$this->error['emailtemplate_key'] = $this->language->get('error_key_select');
		}

		if (empty($data['emailtemplate_label'])) {
			$this->error['emailtemplate_label'] = $this->language->get('error_required');
		}

		if (isset($data['emailtemplate_mail_attachment']) && $data['emailtemplate_mail_attachment']) {
			$dir = substr(DIR_SYSTEM, 0, -7); // remove 'system/'
			if (!file_exists($dir.$data['emailtemplate_mail_attachment'])) {
				$this->error['emailtemplate_mail_attachment'] = sprintf($this->language->get('error_file_not_exists'), $dir.$data['emailtemplate_mail_attachment']);
			}
		}

		if (isset($data['emailtemplate_attach_invoice']) && !$this->config->get('pdf_invoice')) {
			$this->error['emailtemplate_attach_invoice'] = $this->language->get('error_pdf_invoice');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Validate template shortcode form data
	 */
	private function _validateTemplateShortcode($data) {
		if (!$this->user->hasPermission('modify', 'module/emailtemplate')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!isset($data['emailtemplate_shortcode_code']) || $data['emailtemplate_shortcode_code'] == '') {
			$this->error['emailtemplate_shortcode_code'] = $this->language->get('error_required');
		}

		if (!isset($data['emailtemplate_shortcode_type']) || $data['emailtemplate_shortcode_type'] == '') {
			$this->error['emailtemplate_shortcode_type'] = $this->language->get('error_required');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Format Address
	 *
	 * @param Array data eg array(firstname=>'', shipping_firstname=>'', payment_firstname=>'')
	 * @param String prefix of address: '' or 'shipping' or 'payment'
	 * @param String address formatting e.g '{firstname}...'
	 */
	private function _formatAddress($address, $address_prefix = '', $format = null) {
		$find = array();
		$replace = array();
		if ($address_prefix != "") {
			$address_prefix = trim($address_prefix, '_') . '_';
		}
		if (is_null($format) || $format == '') {
			$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}
		$vars = array(
				'firstname',
				'lastname',
				'company',
				'address_1',
				'address_2',
				'city',
				'postcode',
				'zone',
				'zone_code',
				'country'
		);
		foreach($vars as $var) {
			$find[$var] = '{'.$var.'}';
			if ($address_prefix && isset($address[$address_prefix.$var])) {
				$replace[$var] =  $address[$address_prefix.$var];
			} elseif (isset($address[$var])) {
				$replace[$var] =  $address[$var];
			} else {
				$replace[$var] =  '';
			}
		}
		return str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
	}

	/**
	 * Output Page
	 *
	 * @param string $template - template file path
	 * @param array $children
	 */
	private function _setTitle($title = '') {
		if ($title == '') {
			$title = $this->language->get('heading_title');
		} else {
			$title .= ' - ' . $this->language->get('heading_title');
		}

		$this->data['title'] = $title;

		$this->document->setTitle(strip_tags($title));

		return $this;
	}

	/**
	 * Output Page
	 *
	 * @param string $template - template file path
	 */
	private function _output($tpl) {
		if ($this->_css) {
			foreach($this->_css as $file) {
				$this->document->addStyle($file);
			}
		}

		if ($this->_js) {
			foreach($this->_js as $file) {
				$this->document->addScript($file);
			}
		}

		$this->data['header'] = $this->load->controller('common/header');
		$this->data['column_left'] = $this->load->controller('common/column_left');
		$this->data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view($tpl, $this->data));
	}

	/**
	 * Pagination
	 *
	 * @param string $url
	 * @param int $page - current page number
	 * @param int $total - total rows count
	 */
	private function _renderPagination($url, $page, $total, $limit = null, $style = '') {
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->paging_style = $style;
		$pagination->page = $page;
		$pagination->limit = ($limit == null) ? $this->config->get('config_limit_admin') : $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $url;

		$this->data['pagination'] = $pagination->render();

		$this->data['pagination_results'] = sprintf($this->language->get('text_pagination'), ($pagination->total) ? (($page - 1) * $pagination->limit) + 1 : 0, ((($page - 1) * $pagination->limit) > ($pagination->total - $pagination->limit)) ? $pagination->total : ((($page - 1) * $pagination->limit) + $pagination->limit), $pagination->total, ceil($pagination->total / $pagination->limit));
	}

	private function _link($link, $isAdmin = false) {
		if ($isAdmin) {
			return $this->url->link($link, 'token='.$this->session->data['token'], 'SSL');
		} else {
			if ($this->config->get('config_secure') && defined('HTTPS_SERVER') && defined('HTTPS_CATALOG')) {
				return str_replace(HTTPS_SERVER, HTTPS_CATALOG, $this->url->link($link, '', 'SSL'));
			} else {
				return str_replace(HTTP_SERVER, HTTP_CATALOG, $this->url->link($link, '', 'SSL'));
			}
		}
	}

}
?>