<?php
/*
	Project: CSV Product Export
	Author : karapuz <support@ka-station.com>

	Version: 4 ($Revision: 43 $)

*/

class ControllerKaExtensionsKaProductExport extends KaInstaller {

	protected $extension_version = '4.1.3';
	protected $min_store_version = '2.0.1.0';
	protected $max_store_version = '2.1.0.9';
	protected $tables;

	protected function onLoad() {
	
		$this->loadLanguage('ka_extensions/ka_product_export');
			
 		$this->tables = array(
 		
 			'product' => array(
 				'fields' => array(),
 				'indexes' => array(
 					'model' => array()
 				)
 			),
 			
 			'ka_export_profiles' => array(
 				'is_new' => true,
 				'fields' => array(
  					'export_profile_id' => array(
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
						'query' => "ALTER TABLE `" . DB_PREFIX . "ka_export_profiles` ADD PRIMARY KEY (`export_profile_id`)",
					),
					'name' => array(
						'query' => "ALTER TABLE `" . DB_PREFIX . "ka_export_profiles` ADD INDEX (`name`)",
					),
				),
			),
		);

		$this->tables['product']['indexes']['model']['query'] = 
			"ALTER TABLE " . DB_PREFIX . "product ADD INDEX (`model`)";

		$this->tables['ka_export_profiles']['query'] = "
			CREATE TABLE `" . DB_PREFIX . "ka_export_profiles` (
			  `export_profile_id` int(11) NOT NULL auto_increment,
			  `name` varchar(128) NOT NULL,
			  `params` mediumtext NOT NULL,
			  PRIMARY KEY  (`export_profile_id`),
			  KEY `name` (`name`)
			) DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		";
		
		return true;
	}

	
	public function index() {

		$heading_title = $this->getTitle();
		$this->document->setTitle($heading_title);
		
		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			$val = max(5, $this->request->post['ka_pe_update_interval']);
			$this->request->post['ka_pe_update_interval'] = min(25, $val);
			
			if (empty($this->request->post['ka_pe_direct_download'])) {
				$this->request->post['ka_pe_direct_download'] = '';
			}

			if (empty($this->request->post['ka_pe_prefix_with_space'])) {
				$this->request->post['ka_pe_prefix_with_space'] = '';
			}

			if (empty($this->request->post['ka_pe_enable_product_id'])) {
				$this->request->post['ka_pe_enable_product_id'] = 'N';
			}
			
			if (empty($this->request->post['ka_pe_cats_in_one_cell'])) {
				$this->request->post['ka_pe_cats_in_one_cell'] = '';
			}
			
			if (empty($this->request->post['ka_pe_related_in_one_cell'])) {
				$this->request->post['ka_pe_related_in_one_cell'] = '';
			}

			if (empty($this->request->post['ka_pe_cats_in_one_cell'])) {
				$this->request->post['ka_pe_images_in_one_cell'] = '';
			}
			
			$this->model_setting_setting->editSetting('ka_pe', $this->request->post);
			$this->addTopMessage($this->language->get('Settings have been stored sucessfully.'));
			$this->redirect($this->url->link('extension/ka_extensions', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$this->data['heading_title']         = $heading_title;
		$this->data['extension_version']     = $this->extension_version;
	
		$this->data['button_save']           = $this->language->get('button_save');
		$this->data['button_cancel']         = $this->language->get('button_cancel');

		$this->data['ka_pe_update_interval']     = $this->config->get('ka_pe_update_interval');
		$this->data['ka_pe_direct_download']     = $this->config->get('ka_pe_direct_download');
		$this->data['ka_pe_prefix_with_space']   = $this->config->get('ka_pe_prefix_with_space');
		$this->data['ka_pe_enable_product_id']   = $this->config->get('ka_pe_enable_product_id');
		$this->data['ka_pe_cats_in_one_cell']    = $this->config->get('ka_pe_cats_in_one_cell');
		$this->data['ka_pe_related_in_one_cell'] = $this->config->get('ka_pe_related_in_one_cell');
		$this->data['ka_pe_images_in_one_cell']  = $this->config->get('ka_pe_images_in_one_cell');
		
		$this->data['ka_pe_general_sep']       = $this->config->get('ka_pe_general_sep');

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
		
		$this->data['action'] = $this->url->link('ka_extensions/ka_product_export', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/ka_extensions', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['export_page']        = $this->url->link('tool/ka_product_export', 'token=' . $this->session->data['token'], 'SSL');

		$this->template = 'ka_extensions/ka_product_export.tpl';
		$this->children = array(
			'common/header',
			'common/column_left',			
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'ka_extensions/ka_product_export')) {
			$this->addTopMessage($this->language->get('error_permission'), 'E');
			return false;
		}
		
		return true;
	}

		
	public function install() {

		if (!parent::install()) {
			return false;
		}

		$this->load->model('extension/extension');
		$this->load->model('setting/setting');
		
		// grant permissions to the extension page automatically
		$this->load->model('user/user_group');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'tool/ka_product_export');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'tool/ka_product_export');

		$settings = array(
			'ka_pe_update_interval'   => 10,
			'ka_pe_direct_download'   => 'N',
			'ka_pe_prefix_with_space' => 'N',
			'ka_pe_enable_product_id' => 'N',
			'ka_pe_general_sep'       => ':::',
			'ka_pe_cats_in_one_cell'    => 'Y',
			'ka_pe_images_in_one_cell'  => 'Y',
			'ka_pe_related_in_one_cell' => 'Y',
		);
		$this->model_setting_setting->editSetting('ka_pe', $settings);

		$this->db->query("DELETE FROM " . DB_PREFIX . "ka_export_profiles");

		return true;
	}
	
}
?>