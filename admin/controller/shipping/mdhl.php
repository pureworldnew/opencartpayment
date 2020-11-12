<?php
/* 
Extension:	Mintegra Opencart 2.X DHL Extension
Version: 	1.0
Support:	http://www.mintegra.com
Author:		Mahesh Karekar
*/
class ControllerShippingMdhl extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('shipping/mdhl');
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('mdhl', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->response->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');		
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_test'] = $this->language->get('text_test');	
		$data['text_production'] = $this->language->get('text_production');			
		
		$data['entry_site_id'] = $this->language->get('entry_site_id');
		$data['entry_password'] = $this->language->get('entry_password');			
		
		$data['entry_status'] = $this->language->get('entry_status');	
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_mode'] = $this->language->get('entry_mode');
	
		
		$data['entry_zip'] = $this->language->get('entry_zip');
		$data['entry_country'] = $this->language->get('entry_country');		
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		
		$data['help_site_id'] = $this->language->get('help_site_id');
		$data['help_password'] = $this->language->get('help_password');		
		$data['help_zip'] = $this->language->get('help_zip');
		$data['help_country'] = $this->language->get('help_country');
		$data['help_mode'] = $this->language->get('help_mode');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['site_id'])) {
			$data['error_site_id'] = $this->error['site_id'];
		} else {
			$data['error_site_id'] = '';
		}
		
		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}

		if (isset($this->error['zip'])) {
			$data['error_zip'] = $this->error['zip'];
		} else {
			$data['error_zip'] = '';
		}	

		if (isset($this->error['country'])) {
			$data['error_country'] = $this->error['country'];
		} else {
			$data['error_country'] = '';
		}			
				
		
  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][]  = array(
			'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('text_home')
   		);

   		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('text_shipping')
   		);
		
   		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link('shipping/mdhl', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('heading_title')
   		);
		
		$data['action'] = $this->url->link('shipping/mdhl', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['mdhl_site_id'])) {
			$data['mdhl_site_id'] = $this->request->post['mdhl_site_id'];
		} else {
			$data['mdhl_site_id'] = $this->config->get('mdhl_site_id');
		}		

		if (isset($this->request->post['mdhl_password'])) {
			$data['mdhl_password'] = $this->request->post['mdhl_password'];
		} else {
			$data['mdhl_password'] = $this->config->get('mdhl_password');
		}			
	
		if (isset($this->request->post['mdhl_status'])) {
			$data['mdhl_status'] = $this->request->post['mdhl_status'];
		} else {
			$data['mdhl_status'] = $this->config->get('mdhl_status');
		}

		if (isset($this->request->post['mdhl_sort_order'])) {
			$data['mdhl_sort_order'] = $this->request->post['mdhl_sort_order'];
		} else {
			$data['mdhl_sort_order'] = $this->config->get('mdhl_sort_order');
		}
		
		if (isset($this->request->post['mdhl_zip'])) {
			$data['mdhl_zip'] = $this->request->post['mdhl_zip'];
		} else {
			$data['mdhl_zip'] = $this->config->get('mdhl_zip');
		}	
		
		if (isset($this->request->post['mdhl_country'])) {
			$data['mdhl_country'] = $this->request->post['mdhl_country'];
		} else {
			$data['mdhl_country'] = $this->config->get('mdhl_country');
		}	
		
		if (isset($this->request->post['mdhl_geo_zone_id'])) {
			$data['mdhl_geo_zone_id'] = $this->request->post['mdhl_geo_zone_id'];
		} else {
			$data['mdhl_geo_zone_id'] = $this->config->get('mdhl_geo_zone_id');
		}		

		if (isset($this->request->post['mdhl_mode'])) {
			$data['mdhl_mode'] = $this->request->post['mdhl_mode'];
		} else {
			$data['mdhl_mode'] = $this->config->get('mdhl_mode');
		}			
		
		$this->load->model('localisation/geo_zone');
		
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();		
		
		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('shipping/mdhl.tpl', $data));		
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/mdhl')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['mdhl_site_id']) {
			$this->error['site_id'] = $this->language->get('error_site_id');
		}
		
		if (!$this->request->post['mdhl_password']) {
			$this->error['password'] = $this->language->get('error_password');
		}			
		
		if (!$this->request->post['mdhl_zip']) {
			$this->error['zip'] = $this->language->get('error_zip');
		}	
				
		if (!$this->request->post['mdhl_country']) {
			$this->error['country'] = $this->language->get('error_country');
		}	
						
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>