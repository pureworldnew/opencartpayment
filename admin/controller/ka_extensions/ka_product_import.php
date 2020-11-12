<?php
/*
  Project: CSV Product Import
  Author : karapuz <support@ka-station.com>

  Version: 4 ($Revision: 89 $)

*/

class ControllerKaExtensionsKaProductImport extends KaInstaller {

	protected $extension_version = '4.1.9';
	protected $min_store_version = '2.0.1.0';
	protected $max_store_version = '2.1.0.9';
	protected $tables;
	protected $xml_file = 'ka_product_import.xml';

	protected function onLoad() {

		$this->loadLanguage('ka_extensions/ka_product_import');
			
 		$this->tables = array(
 			'product' => array(
 				'fields' => array(
 					'skip_import' => array(
 						'type' => 'tinyint(1)',
 						'query' => "ALTER TABLE `" . DB_PREFIX . "product` ADD `skip_import` TINYINT(1) NOT NULL DEFAULT '0'"
 					),
 				),
 				'indexes' => array(
 					'model' => array()
 				)
 			),

 			'ka_product_import' => array(
 				'is_new' => true,
 				'fields' => array(
 					'product_id' => array(
 						'type' => 'int(11)',
 					),
 					'token' => array(
 						'type' => 'varchar(255)',
 					),
 					'is_new' => array(
 						'type' => 'tinyint(1)',
 						'query' => "ALTER TABLE `" . DB_PREFIX . "ka_product_import` ADD `is_new` TINYINT(1) NOT NULL DEFAULT '0'"
 					),
 					'added_at' => array(
 						'type' => 'timestamp'
 					)
 				)
 			),
 			
 			'ka_import_records' => array(
 				'is_new' => true,
 				'fields' => array(
 					'product_id' => array(
 						'type' => 'int(11)',
 					),
 					'record_type' => array(
 						'type' => 'int(6)',
 					),
 					'record_id' => array(
 						'type' => 'int(11)',
 					),
 					'token' => array(
 						'type' => 'varchar(255)',
 					),
 					'added_at' => array(
 						'type' => 'timestamp',
 						'query' => "ALTER TABLE `" . DB_PREFIX . "ka_import_records` ADD added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP",
 					)
 				),
 				'indexes' => array(
 					'product_id' => array(),
 					'trr' => array(),
 				),
 			),		
 			
 			'ka_import_profiles' => array(
 				'is_new' => true,
 				'fields' => array(
  					'import_profile_id' => array(
  						'type' => 'int(11)',
  					),
  					'name' => array(
  						'type' => 'varchar(128)',
  					),
  					'params' => array(
  						'type' => 'mediumtext',
  					),
  				),
				'indexes' => array(
					'PRIMARY' => array(
						'query' => "ALTER TABLE `" . DB_PREFIX . "ka_import_profiles` ADD PRIMARY KEY (`import_profile_id`)",
					),
					'name' => array(
						'query' => "ALTER TABLE `" . DB_PREFIX . "ka_import_profiles` ADD INDEX (`name`)",
					),
				),
			),
 			
		);

		$this->tables['product']['indexes']['model']['query'] = 
			"ALTER TABLE " . DB_PREFIX . "product ADD INDEX (`model`)";


		$this->tables['ka_product_import']['query'] = "
			CREATE TABLE `" . DB_PREFIX . "ka_product_import` (
				`product_id` int(11) NOT NULL,
				`token` varchar(255) NOT NULL,
				`is_new` tinyint(1) NOT NULL default 0,
				`added_at` timestamp NOT NULL default CURRENT_TIMESTAMP,
				PRIMARY KEY  (`product_id`,`token`)
			) DEFAULT CHARSET=utf8
		";

		$this->tables['ka_import_profiles']['query'] = "
			CREATE TABLE `" . DB_PREFIX . "ka_import_profiles` (
			  `import_profile_id` int(11) NOT NULL auto_increment,
			  `name` varchar(128) NOT NULL,
			  `params` mediumtext NOT NULL,
			  PRIMARY KEY  (`import_profile_id`),
			  KEY `name` (`name`) 
			) DEFAULT CHARSET=utf8
		";
		
		$this->tables['ka_import_records']['query'] = "
			CREATE TABLE `" . DB_PREFIX . "ka_import_records` (  
				`product_id` int(11) NOT NULL,  
				`record_type` int(6) NOT NULL,  
				`record_id` int(11) NOT NULL,  
				`token` varchar(255) NOT NULL, 
				`added_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				KEY `product_id` (`product_id`),  
				KEY `trr` (`token`,`record_type`,`record_id`)
			) DEFAULT CHARSET=utf8
		";
		
		
		return true;
	}

	
	public function getTitle() {
		$str = str_replace('{{version}}', $this->extension_version, $this->language->get('heading_title_ver'));
		return $str;
	}
	
	public function index() {

		$heading_title = $this->getTitle();
		$this->document->setTitle($heading_title);

		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			$val = max(5, $this->request->post['ka_pi_update_interval']);
			$this->request->post['ka_pi_update_interval'] = min(25, $val);
			if (!isset($this->request->post['ka_pi_create_options'])) {
				$this->request->post['ka_pi_create_options'] = '';
			}
			if (!isset($this->request->post['ka_pi_generate_seo_keyword'])) {
				$this->request->post['ka_pi_generate_seo_keyword'] = '';
			}
			if (!isset($this->request->post['ka_pi_enable_product_id'])) {
				$this->request->post['ka_pi_enable_product_id'] = '';
			}
			if (!isset($this->request->post['ka_pi_skip_img_download'])) {
				$this->request->post['ka_pi_skip_img_download'] = '';
			}
			
			if (!isset($this->request->post['ka_pi_compare_as_is'])) {
				$this->request->post['ka_pi_compare_as_is'] = '';
			}
			if (!isset($this->request->post['ka_pi_enable_macfix'])) {
				$this->request->post['ka_pi_enable_macfix'] = '';
			}

			$this->model_setting_setting->editSetting('ka_pi', $this->request->post);
			$this->addTopMessage($this->language->get('Settings have been stored sucessfully.'));
			$this->redirect($this->url->link('extension/ka_extensions', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$this->data['heading_title']         = $heading_title;
		$this->data['extension_version']     = $this->extension_version;
	
		$this->data['button_save']           = $this->language->get('button_save');
		$this->data['button_cancel']         = $this->language->get('button_cancel');

		$this->data['ka_pi_update_interval']    = $this->config->get('ka_pi_update_interval');
		$this->data['ka_pi_general_separator']         = $this->config->get('ka_pi_general_separator');
		$this->data['ka_pi_multicat_separator']         = $this->config->get('ka_pi_multicat_separator');
		$this->data['ka_pi_related_products_separator'] = $this->config->get('ka_pi_related_products_separator');
		$this->data['ka_pi_options_separator']          = $this->config->get('ka_pi_options_separator');
		$this->data['ka_pi_image_separator']            = $this->config->get('ka_pi_image_separator');
		$this->data['ka_pi_compare_as_is']              = $this->config->get('ka_pi_compare_as_is');
		$this->data['ka_pi_enable_macfix']              = $this->config->get('ka_pi_enable_macfix');
		
		$this->data['ka_pi_create_options']       = $this->config->get('ka_pi_create_options');
		$this->data['ka_pi_generate_seo_keyword'] = $this->config->get('ka_pi_generate_seo_keyword');
		$this->data['ka_pi_enable_product_id']    = $this->config->get('ka_pi_enable_product_id');
		$this->data['ka_pi_skip_img_download']    = $this->config->get('ka_pi_skip_img_download');
		
		$this->data['ka_pi_key_fields']         = $this->config->get('ka_pi_key_fields');		
		if (!is_array($this->data['ka_pi_key_fields']) || empty($this->data['ka_pi_key_fields'])) {
			$this->data['ka_pi_key_fields'] = array('model');
		}
		$this->data['key_fields'] = array(
			array(
				'field' => 'model',
				'name'  => 'model',
			),
			array(
				'field' => 'sku',
				'name'  => 'sku',
			),
			array(
				'field' => 'upc',
				'name'  => 'upc',
			),
		);
		
		$this->data['ka_pi_status_for_new_products']      = $this->config->get('ka_pi_status_for_new_products');
		$this->data['ka_pi_status_for_existing_products'] = $this->config->get('ka_pi_status_for_existing_products');

		$this->data['action'] = $this->url->link('ka_extensions/ka_product_import', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/ka_extensions', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['import_page']        = $this->url->link('tool/ka_product_import', 'token=' . $this->session->data['token'], 'SSL');

		
 		$this->data['breadcrumbs'] = array();
 		$this->data['breadcrumbs'][] = array(
	   		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
 		);

	  	$this->data['breadcrumbs'][] = array(
   			'text'      => $this->language->get('Ka Extensions'),
			'href'      => $this->url->link('extension/ka_extensions', 'token=' . $this->session->data['token'], 'SSL'),
  		);
 		$this->data['breadcrumbs'][] = array(
	   		'text'      => $heading_title,
 		);
		
		$this->template = 'ka_extensions/ka_product_import.tpl';
		$this->children = array(
			'common/header',
			'common/column_left',			
			'common/footer'
		);

		$this->setOutput();
	}
	
	
	protected function validate() {
	
		if (!$this->user->hasPermission('modify', 'ka_extensions/ka_product_import')) {
			$this->addTopMessage($this->language->get('error_permission'), 'E');
			return false;
			
		} elseif (empty($this->request->post['ka_pi_key_fields'])) {
			$this->addTopMessage($this->language->get('Key fields cannot be empty'), 'E');
			return false;
		}

		return true;
	}

	
	protected function checkDBCompatibility(&$messages) {

		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "ka_product_import`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "ka_import_profiles`");
	
		return parent::checkDBCompatibility($messages);
	}


	public function install() {

		if (!parent::install()) {
			return false;
		}
		
		$this->load->model('extension/extension');
		$this->load->model('setting/setting');
		
		// grant permissions to the import page automatically
		$this->load->model('user/user_group');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'tool/ka_product_import');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'tool/ka_product_import');

		$settings = array(
			'ka_pi_update_interval'            => 10,
			'ka_pi_related_products_separator' => ':::',
			'ka_pi_options_separator'          => ':::',
			'ka_pi_image_separator'            => ':::',
			
			'ka_pi_compare_as_is'              => 'Y',
			'ka_pi_enable_macfix'              => 'Y',
			
			'ka_pi_multicat_separator'         => ':::',
			'ka_pi_general_separator'          => ':::',
			'ka_pi_create_options'             => 'Y',
			'ka_pi_generate_seo_keyword'       => 'Y',
			'ka_pi_key_fields'                 => array('model'),
			'ka_pi_skip_img_download'          => '',
		);
		$this->model_setting_setting->editSetting('ka_pi', $settings);

		return true;
	}

	
	public function uninstall() {
		parent::uninstall();
		
		return true;
	}
}
?>