<?php
class ControllerModuleALTHEMISTtabs extends Controller {
	private $error = array(); 
	 
	public function index() {   
		$this->load->language('module/ALTHEMISTtabs');
		
		$this->load->model('tool/image');

		$this->document->setTitle($this->language->get('doc_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('ALTHEMISTtabs', $this->request->post);		
			
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$this->document->addScript('view/stylesheet/althemist/js/colorpicker.js');
		$this->document->addStyle('view/stylesheet/althemist/css/colorpicker.css');
		$this->document->addStyle('view/stylesheet/althemist/css/ALTHEMISTControl.css');
		$this->document->addStyle('view/stylesheet/althemist/css/font-awesome.min.css');
				
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_content_top'] = $this->language->get('text_content_top');
		$data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$data['text_column_left'] = $this->language->get('text_column_left');
		$data['text_column_right'] = $this->language->get('text_column_right');
		$data['text_browse'] = $this->language->get('text_browse');
		$data['text_clear'] = $this->language->get('text_clear');
		$data['text_title'] = $this->language->get('text_title');
		$data['text_link'] = $this->language->get('text_link');
		$data['text_image_manager'] = $this->language->get('text_image_manager');
		
		$data['entry_dimension'] = $this->language->get('entry_dimension');
		$data['entry_image_dimension'] = $this->language->get('entry_image_dimension');
		$data['entry_title_description'] = $this->language->get('entry_title_description');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_layout'] = $this->language->get('entry_layout');
		$data['entry_position'] = $this->language->get('entry_position');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_add_module'] = $this->language->get('button_add_module');
		$data['button_add_section'] = $this->language->get('button_add_section');
		$data['button_remove'] = $this->language->get('button_remove');
		
		$data['tab_module'] = $this->language->get('tab_module');
		
		$data['token'] = $this->session->data['token'];

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		

  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/ALTHEMISTtabs', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$data['action'] = $this->url->link('module/ALTHEMISTtabs', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$modules = array();
		
		if (isset($this->request->post['ALTHEMISTtabs_module'])) {
			$modules = $this->request->post['ALTHEMISTtabs_module'];
		} elseif ($this->config->get('ALTHEMISTtabs_module')) { 
			$modules = $this->config->get('ALTHEMISTtabs_module');
		}

		if ($modules){
			foreach($modules as $key => $value){
				$module_number = $key;
				
				foreach($value['sections'] as $key => $value){
					$section_number = $key;
					$modules[$module_number]['sections'][$section_number]['icon'] = $value['icon'];
				}				
			}
		}

		$data['modules'] = $modules;	
				
		$this->load->model('design/layout');
		
		$data['layouts'] = $this->model_design_layout->getLayouts();
		
		$this->load->model('localisation/language');
		
		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/ALTHEMISTtabs.tpl', $data));
		
	/*	$this->template = 'module/ALTHEMISTtabs.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());*/
	}
	
	private function validate() {
		
		if (!$this->user->hasPermission('modify', 'module/ALTHEMISTtabs')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (isset($this->request->post['ALTHEMISTtabs_module'])) {
			foreach ($this->request->post['ALTHEMISTtabs_module'] as $key => $value) {
				$module_number = $key;
				
			}
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>