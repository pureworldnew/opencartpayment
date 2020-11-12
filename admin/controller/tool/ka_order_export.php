<?php
/*
	Project: CSV Product Export
	Author : karapuz <support@ka-station.com>

	Version: 4 ($Revision: 44 $)

*/

class ControllerToolKaOrderExport extends KaController { 

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

		$this->data['heading_title']    = "CSV Incoming Order Export";

		$this->data['store_images_dir'] = $this->store_images_dir;
		$this->data['store_root_dir']   = $this->store_root_dir;

		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
		);
		$this->data['breadcrumbs'][] = array(
			'text'      => "CSV Incoming Order Export",
			'href'      => $this->url->link('tool/ka_order_export', 'token=' . $this->session->data['token'], 'SSL'),
		);	
		
		$this->children = array(
			'common/header',
			'common/footer',
			'common/column_left',
		);
	}


	protected function prepareOutput() {
		$this->document->setTitle('CSV Incoming Order Export');

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
			$this->template = 'tool/ka_order_export.tpl';
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
		 
		 
		 if (isset($this->session->data['warning'])) {
			$this->data['error_warning'] = $this->session->data['warning'];
			unset($this->session->data['warning']);
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
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

		$this->data['action']    = $this->url->link('tool/ka_order_export/export', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['params']    = $this->params;

		$this->template = 'tool/ka_order_export.tpl';
		return $this->prepareOutput();
	}


	public function export()
	{
		if ($this->request->post['products_from_manufacturers'] == 'selected') {
			if (empty($this->request->post['manufacturer_ids'])) {
				$this->session->data['warning'] = "Please specify which manufacturer orders you want to export.";
        		$this->response->redirect($this->url->link('tool/ka_order_export', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}
		$this->load->model('tool/ka_order_export');
		$this->model_tool_ka_order_export->export($this->request->post);
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

		if ($stat['status'] == 'completed') {
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