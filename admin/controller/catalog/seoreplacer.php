<?php 

/*******************************************************************************
*                                 Opencart SEO Pack                            *
*                             © Copyright Ovidiu Fechete                       *
*                              email: ovife21@gmail.com                        *
*                Below source-code or any part of the source-code              *
*                          cannot be resold or distributed.                    *
*******************************************************************************/

class ControllerCatalogSEOReplacer extends Controller {
	private $error = array(); 
	
	public function index() {
		$this->load->language('catalog/seoreplacer');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('seoreplacer', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$seoreplacer = $this->request->post['seoreplacer'];
			
			if (isset($seoreplacer['metakeywords']) && ($seoreplacer['metakeywords'])) {
				$this->db->query("UPDATE " . DB_PREFIX . "category_description SET meta_keyword = REPLACE (meta_keyword, '".$seoreplacer['replace']."', '".$seoreplacer['replacewith']."') WHERE language_id = '".$seoreplacer['language_id']."';");
				$this->db->query("UPDATE " . DB_PREFIX . "product_description SET meta_keyword = REPLACE (meta_keyword, '".$seoreplacer['replace']."', '".$seoreplacer['replacewith']."') WHERE language_id = '".$seoreplacer['language_id']."';");
				$this->db->query("UPDATE " . DB_PREFIX . "information_description SET meta_keyword = REPLACE (meta_keyword, '".$seoreplacer['replace']."', '".$seoreplacer['replacewith']."') WHERE language_id = '".$seoreplacer['language_id']."';");
				$this->db->query("UPDATE " . DB_PREFIX . "manufacturer_description SET meta_keyword = REPLACE (meta_keyword, '".$seoreplacer['replace']."', '".$seoreplacer['replacewith']."') WHERE language_id = '".$seoreplacer['language_id']."';");
				}

			if (isset($seoreplacer['metadescription']) && ($seoreplacer['metadescription'])) {
				$this->db->query("UPDATE " . DB_PREFIX . "category_description SET meta_description = REPLACE (meta_description, '".$seoreplacer['replace']."', '".$seoreplacer['replacewith']."') WHERE language_id = '".$seoreplacer['language_id']."';");
				$this->db->query("UPDATE " . DB_PREFIX . "product_description SET meta_description = REPLACE (meta_description, '".$seoreplacer['replace']."', '".$seoreplacer['replacewith']."') WHERE language_id = '".$seoreplacer['language_id']."';");
				$this->db->query("UPDATE " . DB_PREFIX . "information_description SET meta_description = REPLACE (meta_description, '".$seoreplacer['replace']."', '".$seoreplacer['replacewith']."') WHERE language_id = '".$seoreplacer['language_id']."';");
				$this->db->query("UPDATE " . DB_PREFIX . "manufacturer_description SET meta_description = REPLACE (meta_description, '".$seoreplacer['replace']."', '".$seoreplacer['replacewith']."') WHERE language_id = '".$seoreplacer['language_id']."';");
				}
				
			if (isset($seoreplacer['customtitles']) && ($seoreplacer['customtitles'])) {
				$this->db->query("UPDATE " . DB_PREFIX . "category_description SET meta_title = REPLACE (meta_title, '".$seoreplacer['replace']."', '".$seoreplacer['replacewith']."') WHERE language_id = '".$seoreplacer['language_id']."';");
				$this->db->query("UPDATE " . DB_PREFIX . "product_description SET meta_title = REPLACE (meta_title, '".$seoreplacer['replace']."', '".$seoreplacer['replacewith']."') WHERE language_id = '".$seoreplacer['language_id']."';");
				$this->db->query("UPDATE " . DB_PREFIX . "information_description SET meta_title = REPLACE (meta_title, '".$seoreplacer['replace']."', '".$seoreplacer['replacewith']."') WHERE language_id = '".$seoreplacer['language_id']."';");
				$this->db->query("UPDATE " . DB_PREFIX . "manufacturer_description SET meta_title = REPLACE (meta_title, '".$seoreplacer['replace']."', '".$seoreplacer['replacewith']."') WHERE language_id = '".$seoreplacer['language_id']."';");
				}
				
			if (isset($seoreplacer['seourls']) && ($seoreplacer['seourls'])) {			
				$this->db->query("UPDATE " . DB_PREFIX . "url_alias SET keyword = REPLACE (keyword, '".$seoreplacer['replace']."', '".$seoreplacer['replacewith']."') WHERE language_id = '".$seoreplacer['language_id']."';");			
				}
				
			if (isset($seoreplacer['customalts']) && ($seoreplacer['customalts'])) {			
				$this->db->query("UPDATE " . DB_PREFIX . "product_description SET custom_alt = REPLACE (custom_alt, '".$seoreplacer['replace']."', '".$seoreplacer['replacewith']."') WHERE language_id = '".$seoreplacer['language_id']."';");			
				}
				
			if (isset($seoreplacer['customalts']) && ($seoreplacer['customh1tags'])) {			
				$this->db->query("UPDATE " . DB_PREFIX . "product_description SET custom_h1 = REPLACE (custom_h1, '".$seoreplacer['replace']."', '".$seoreplacer['replacewith']."') WHERE language_id = '".$seoreplacer['language_id']."';");			
				}
				
			if (isset($seoreplacer['customalts']) && ($seoreplacer['customh2tags'])) {			
				$this->db->query("UPDATE " . DB_PREFIX . "product_description SET custom_h2 = REPLACE (custom_h2, '".$seoreplacer['replace']."', '".$seoreplacer['replacewith']."') WHERE language_id = '".$seoreplacer['language_id']."';");			
				}
				
			if (isset($seoreplacer['customalts']) && ($seoreplacer['customimagetitles'])) {			
				$this->db->query("UPDATE " . DB_PREFIX . "product_description SET custom_imgtitle = REPLACE (custom_imgtitle, '".$seoreplacer['replace']."', '".$seoreplacer['replacewith']."') WHERE language_id = '".$seoreplacer['language_id']."';");			
				}
				
			if (isset($seoreplacer['customalts']) && ($seoreplacer['producttags'])) {			
				$this->db->query("UPDATE " . DB_PREFIX . "product_description SET tag = REPLACE (tag, '".$seoreplacer['replace']."', '".$seoreplacer['replacewith']."') WHERE language_id = '".$seoreplacer['language_id']."';");			
				}
				
				

			$this->response->redirect($this->url->link('catalog/seoreplacer', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
		$data['cancel'] = $this->url->link('catalog/seoreplacer', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => 'Auto Links',
			'href'      => $this->url->link('catalog/seoreplacer', 'token=' . $this->session->data['token'], 'SSL'),       		
      		'separator' => ' :: '
   		);
		
   					
		$data['action'] = $this->url->link('catalog/seoreplacer', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['seoreplacer'] = array();
		
		if (isset($this->request->post['seoreplacer'])) {
			$data['seoreplacer'] = $this->request->post['seoreplacer'];
		} else {
			$data['seoreplacer'] = $this->config->get('seoreplacer');
		}
		$this->load->model('localisation/language');
		
		$data['languages'] = $this->model_localisation_language->getLanguages();
		
			
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/seoreplacer.tpl', $data));
	} 
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'catalog/seoreplacer')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}	
}
?>