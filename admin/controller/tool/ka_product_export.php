<?php
/*
	Project: CSV Product Export
	Author : karapuz <support@ka-station.com>

	Version: 4 ($Revision: 44 $)

*/

class ControllerToolKaProductExport extends KaController { 

	protected $tmp_dir;
	protected $store_root_dir;
	protected $store_images_dir;
	protected $kaformat = null;

	protected function onLoad() {

		parent::onLoad();

 		$this->loadLanguage('ka_extensions/ka_product_export');
 		$this->loadLanguage('tool/ka_product_export');
				
		KaElements::init($this->registry);
		$this->kaformat = new KaFormat($this->registry);
	
		$this->tmp_dir          = DIR_CACHE;
		$this->store_root_dir   = dirname(DIR_APPLICATION);
		$this->store_images_dir = dirname(DIR_IMAGE) . DIRECTORY_SEPARATOR . basename(DIR_IMAGE);

		if (!$this->validate()) {
			return $this->redirect($this->url->link('error/permission', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$this->load->model('catalog/product');
		$this->load->model('tool/ka_product_export');

		if (!$this->db->isKaInstalled('ka_product_export')) {
			$this->addTopMessage("The extension is not installed");
			return $this->redirect($this->url->link('extension/ka_extensions', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title']    = $this->language->get('heading_title');

		$this->data['store_images_dir'] = $this->store_images_dir;
		$this->data['store_root_dir']   = $this->store_root_dir;

		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
		);
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('tool/ka_product_export', 'token=' . $this->session->data['token'], 'SSL'),
		);	
		
		$this->children = array(
			'common/header',
			'common/footer',
			'common/column_left',
		);
	}


	protected function prepareOutput() {
		$this->document->setTitle($this->data['heading_title']);

		$this->response->setOutput($this->render());
	}


	protected function validate() {
		if (!$this->user->hasPermission('modify', 'tool/ka_product_export')) {
			return FALSE;
		}

		return TRUE;
	}


	protected function getStores() {

		$this->load->model('setting/store');
		$stores = $this->model_setting_store->getStores();

		$stores[] = array(
			'store_id' => 0,
			'name'     => $this->config->get('config_name') . $this->language->get('text_default'),
			'url'      => HTTP_CATALOG,
		);

		$stores[] = array(
			'store_id' => -1,
			'name'     => '--- unassigned --',
			'url'      => HTTP_CATALOG,
		);
		
		return $stores;
	}

	
	// 
	// public actions
	//	
	public function index() { // step1
	
		// do we need to re-install the extension?
		//
		if (!$this->model_tool_ka_product_export->isDBPrepared()) {
			$this->data['is_wrong_db'] = true;
			$this->template = 'tool/ka_product_export.tpl';
			$this->prepareOutput();
			return;
		}

		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		$this->load->model('localisation/currency');
		$this->data['currencies'] = $this->model_localisation_currency->getCurrencies();

		$profiles = $this->model_tool_ka_product_export->getProfiles();
		$this->data['profiles'] = $profiles;

		if (empty($this->params) || ($this->request->server['REQUEST_METHOD'] == 'GET' && empty($this->session->data['save_params']))) {
		
			$this->params = array(
				'charset'             => 'UTF-8', // 'UTF-16LE' - MS Excel
				'file_settings'       => 'custom',
				'charset_option'      => 'predefined',
				'cat_separator'       => '///',
				'delimiter'           => 's',
				'store_id'            => 0,
				'sort_by'             => 'name',
				'image_path'          => 'path',
				'products_from_categories'    => 'all',
				'products_from_manufacturers' => 'all',
				'category_ids'        => array(),
				'manufacturer_ids'    => array(),
				'profile_name'        => '',
				'profile_id'          => '',
				'copy_path'           => '',
				'geo_zone_id'         => '',
				'customer_group_id'   => 0,
				'apply_taxes'         => false,
				'include_subcategories' => false,				
				'use_special_price'   => false,
				'exclude_inactive'    => false,
				'exclude_zero_qty' => false,
				'separate_units'   => false,
			);

			$language_code = $this->config->get('config_language');
			if (!empty($this->data['languages'][$language_code])) {
				$this->params['language_id'] = $this->data['languages'][$language_code]['language_id'];
			} else {
				$this->params['language_id'] = 0;
			}
			$currency_code = $this->config->get('config_currency');
			if (!empty($this->data['currencies'][$currency_code])) {
				$this->params['currency'] = $this->data['currencies'][$currency_code];
			} else {
				$this->params['currency']= array('code' => $currency_code);
			}
			
			$this->params['file'] = $this->model_tool_ka_product_export->tempname(DIR_CACHE, "export_" . date("Y-m-d") . '_', 'csv');
	 	}

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

			$msg = '';

			if ($this->request->post['mode'] == 'load_profile') {
			
				$this->params = $this->model_tool_ka_product_export->getProfileParams($this->request->post['profile_id']);

				$this->params['profile_id'] = $this->request->post['profile_id'];
				$this->session->data['save_params'] = true;
				$this->addTopMessage("Profile has been loaded succesfully");
				
				return $this->redirect($this->url->link('tool/ka_product_export', 'token=' . $this->session->data['token'], 'SSL'));
				
			} elseif ($this->request->post['mode'] == 'delete_profile') {
			
				$this->model_tool_ka_product_export->deleteProfile($this->request->post['profile_id']);
				$this->session->data['save_params'] = true;
				$this->addTopMessage("Profile has been deleted succesfully");
				
				return $this->redirect($this->url->link('tool/ka_product_export', 'token=' . $this->session->data['token'], 'SSL'));
			}
					
			$this->params['cat_separator']  = $this->request->post['cat_separator']; 
			$this->params['delimiter']      = $this->request->post['delimiter'];
			$this->params['language_id']    = $this->request->post['language_id'];
			$this->params['copy_path']      = $this->request->post['copy_path']; 
			
			// we store the entire currency array in the variable
			$this->params['currency']       = $this->data['currencies'][$this->request->post['currency']];
			
			$this->params['store_id']       = $this->request->post['store_id'];
			$this->params['image_path']     = $this->request->post['image_path'];
			
			$this->params['products_from_categories']    = $this->request->post['products_from_categories'];
			$this->params['include_subcategories']       = (!empty($this->request->post['include_subcategories'])) ? true:false;			
			$this->params['products_from_manufacturers'] = $this->request->post['products_from_manufacturers'];

			$this->params['apply_taxes']        = (!empty($this->request->post['apply_taxes'])) ? true:false;
			$this->params['use_special_price']  = (!empty($this->request->post['use_special_price'])) ? true:false;
			$this->params['customer_group_id']  = $this->request->post['customer_group_id'];
			$this->params['exclude_inactive']   = (!empty($this->request->post['exclude_inactive'])) ? true:false;
			$this->params['exclude_zero_qty']   = (!empty($this->request->post['exclude_zero_qty'])) ? true:false;
			$this->params['separate_units']     = (!empty($this->request->post['separate_units'])) ? true:false;
			
			$this->params['charset_option'] = $this->request->post['charset_option']; 
			if ($this->params['charset_option'] == 'predefined') {
				$this->params['charset'] = $this->request->post['charset']; 
			} else {
				$this->params['charset'] = $this->request->post['custom_charset']; 
			}

			if ($this->params['products_from_categories'] == 'selected') {
				if (empty($this->request->post['category_ids'])) {
					$msg = "Please specify what products you want to export.";
				} else {
					$this->params['category_ids'] = $this->request->post['category_ids'];
				}
			}
		
			if(!empty($this->params['category_ids']) && $this->params['include_subcategories']){
				$subcategories = array();
				foreach($this->params['category_ids'] as $category_id){
					$subcategories[] = $this->model_tool_ka_product_export->getAllSubCategories($category_id);
				}
				if(!empty($subcategories)){
					$this->params['category_ids'] = array();
					foreach($subcategories as $values){
						foreach($values as $category){
							if(!in_array($category, $this->params['category_ids'])){
								$this->params['category_ids'][] = $category;
							}
						}
					}
				}
			}
			
			if ($this->params['products_from_manufacturers'] == 'selected') {
				if (empty($this->request->post['manufacturer_ids'])) {
					$msg = "Please specify what products you want to export.";
				} else {
					$this->params['manufacturer_ids'] = $this->request->post['manufacturer_ids'];
				}
			}
			
			if (empty($this->params['file'])) {
				$msg = "Cannot create a temporary file. Check 'cache' directory permissions.";
			}

			if (!empty($msg)) {
				$this->addTopMessage($msg, 'E');
				$this->session->data['save_params'] = true;
			 	return $this->redirect($this->url->link('tool/ka_product_export', 'token=' . $this->session->data['token'], 'SSL'));
			}

			return $this->redirect($this->url->link('tool/ka_product_export/step2', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->session->data['save_params'] = false;
	 	$this->params['step']          = 1;
		$this->params['iconv_exists']  = function_exists('iconv');
		$this->params['filter_exists'] = in_array('convert.iconv.*', stream_get_filters());

		$this->load->model('setting/store');
		$this->data['stores']     = $this->getStores();
		
		$this->load->model('localisation/geo_zone');
		$this->data['geo_zones']  = $this->model_localisation_geo_zone->getGeoZones();

		if (version_compare(VERSION, '2.1.0.0', '>=')) {
			$this->load->model('customer/customer_group');
			$this->data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();
		} else {
			$this->load->model('sale/customer_group');
			$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		}
		
		$this->load->model('catalog/category');		
		$this->data['categories'] = $this->model_catalog_category->getCategoriesExport(0);
		$this->load->model('catalog/manufacturer');		
		$this->data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();
		$this->data['charsets']   = $this->model_tool_ka_product_export->getCharsets();

		$this->data['action']    = $this->url->link('tool/ka_product_export', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['params']    = $this->params;

		$this->template = 'tool/ka_product_export.tpl';
		return $this->prepareOutput();
	}


	public function step2($params_custom = '') { // step2

		if(!empty($params_custom)){
			$this->params = $params_custom;
		}
		$this->params['step'] = 2;
		//die("EJJE");
		$sets = $this->model_tool_ka_product_export->getFieldSets($this->params);
		/*echo "<pre>";
		print_r($this->params);
		echo "</pre>";
		die();*/

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->params['columns'] = $this->request->post['columns'];
			
			if (!empty($this->params['columns'])) {

				// 1) fill in the type column (it is used for 'date' only at this time)
				// 2) trim custom column names
				//
				//print_r($this->params['columns']);
				//die();
				foreach (array('general', 'attributes', 'specials', 'discounts') as $set_name) {
					if (!empty($this->params['columns'][$set_name])) {
						foreach ($this->params['columns'][$set_name] as $col_name => &$col_data) {
							if (!empty($sets[$set_name][$col_name]['type'])) {
								$col_data['type'] = $sets[$set_name][$col_name]['type'];
								print_r($col_data);
							}
							if (isset($col_data['column'])) {
								$col_data['column'] = trim($col_data['column']);
							}
						}
					}
				}
			}
			
			if ($this->request->post['mode'] == 'save_profile') {
			
				if (empty($this->request->post['profile_name'])) {
					$this->addTopMessage("Profile name is empty", "E");
					
				} else {
					if ($this->model_tool_ka_product_export->setProfileParams($this->request->post['profile_id'], $this->request->post['profile_name'], $this->params)) {
						$this->addTopMessage("Profile has been saved succesfully");
					}
				}
				
				return $this->redirect($this->url->link('tool/ka_product_export/step2', 'token=' . $this->session->data['token'], 'SSL'));
			}

			$errors_found = false;

			if ($errors_found) {
				return $this->redirect($this->url->link('tool/ka_product_export/step2', 'token=' . $this->session->data['token'], 'SSL'));
			}
	
			return $this->redirect($this->url->link('tool/ka_product_export/step3', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['general']       = $sets['general'];
		$this->data['specials']      = $sets['specials'];
		$this->data['discounts']     = $sets['discounts'];
		$this->data['reward_points'] = $sets['reward_points'];
		$this->data['attributes']    = $sets['attributes'];
		$this->data['filter_groups'] = $sets['filter_groups'];
		$this->data['options']       = $sets['options'];

		if (!empty($this->params['columns'])) {
			$this->data['columns']    = $this->params['columns'];
		}
		
		$this->data['attribute_page_url'] = $this->url->link('catalog/attribute', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['filter_page_url']    = $this->url->link('catalog/filter', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['option_page_url']    = $this->url->link('catalog/option', 'token=' . $this->session->data['token'], 'SSL');
    	$this->data['action']             = $this->url->link('tool/ka_product_export/step2', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['back_action']        = $this->url->link('tool/ka_product_export', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['params']             = $this->params;

		$this->template = 'tool/ka_product_export.tpl';

		return $this->prepareOutput();
	}

	
	public function step3() { // step3

		$this->params['step'] = 3;

		$params = $this->params;
		/*echo "<pre>";
		print_r($this->params);
		echo "</pre>";
		//die();*/
		
		$params['delimiter'] = strtr($params['delimiter'], array('c'=>',', 's'=>';', 't'=>"\t"));

		if (!$this->model_tool_ka_product_export->initExport($params)) {
			$this->addTopMessage($this->model_tool_ka_product_export->getLastError(), 'E');
			return $this->redirect($this->url->link('tool/ka_product_export/step2', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['done_action']        = $this->url->link('tool/ka_product_export', 'token=' . $this->session->data['token'], 'SSL');

		if ($this->config->get('ka_pe_direct_download') == 'Y') {
			chmod($params['file'], 0644);
			
			if (version_compare(VERSION, '2.1.0.0', '>=')) {
				$cache_dir = 'system/storage/cache/';
			} else {
				$cache_dir = 'system/cache/';
			}
			
			$this->data['download_link']  = HTTP_CATALOG . $cache_dir . basename($params['file']);
		} else {
	    	$this->data['download_link']  = $this->url->link('tool/ka_product_export/download', 'token=' . $this->session->data['token'], 'SSL');
	    }

		$this->data['params']             = $this->params;

		$sec = $this->model_tool_ka_product_export->getSecPerCycle();

		$this->data['update_interval']    = $sec.' - ' .($sec + 5);
 		$this->data['page_url'] = str_replace('&amp;', '&', $this->url->link('tool/ka_product_export/stat', 'format=raw&tmpl=component&token=' . $this->session->data['token'], 'SSL'));
		$this->template = 'tool/ka_product_export.tpl';

		return $this->prepareOutput();
	}


	/*
		The function is called by ajax script and it outputs information in json format.

		json format:
			status - in progress, completed, error;
			...    - extra export parameters.
	*/
	public function stat() {

		if ($this->params['step'] != 3) {
			$this->addTopMessage('This script can be requested at step 3 only', 'E');
			return $this->redirect($this->url->link('tool/ka_product_export/step2', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$this->model_tool_ka_product_export->process($this);

		$stat                  = $this->model_tool_ka_product_export->getExportStat();
		$stat['messages']      = $this->model_tool_ka_product_export->getExportMessages();
		$stat['time_passed']   = $this->kaformat->formatPassedTime(time() - $stat['started_at']);
		$stat['file_size']     = $this->kaformat->convertToMegabyte($stat['file_size']);
		
		if ($stat['status'] == 'in_progress') {
			$stat['completion_at'] = sprintf("%.2f%%", $stat['products_processed'] * 100 / $stat['products_total']);
		} elseif ($stat['status'] == 'completed') {
			$stat['completion_at'] = sprintf("%.2f%%", 100);
		}
	
 		$this->response->setOutput(json_encode($stat));
	}


	public function download() {

		$stat = $this->model_tool_ka_product_export->getExportStat();

		if ( isset($stat['status']) && $stat['status'] == 'completed' ) {
			$file = $this->params['file'];
			$name = "export_" . date("Y-m-d") . ".csv";

			if (!headers_sent()) {
				if (file_exists($file)) {
					header('Content-Description: File Transfer');
					header('Content-Type: application/octet-stream');
					header("Content-Disposition: attachment; filename=\"$name\"");
					header('Content-Transfer-Encoding: binary');
					header('Expires: 0');
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header('Pragma: public');
					header('Content-Length: ' . filesize($file));
					
					readfile($file, 'rb');
					
					exit;
				} else {
					exit('Error: Could not find file ' . $file . '!');
				}
			} else {
				exit('Error: Headers were already sent out!');
			}
		} else {
			$this->redirect($this->url->link('tool/ka_product_export/step2', 'token=' . $this->session->data['token'], 'SSL'));
		}
	}
}
?>