<?php
class ControllerModuleAdvReportsProductsProfit extends Controller {
	private $error = array(); 

	public function index() {  		
		$this->load->language('module/adv_reports_products_profit');
		
		$this->document->setTitle($this->language->get('heading_title_main'));

		$data['heading_title_main'] = $this->language->get('heading_title_main');
		$data['text_edit'] = $this->language->get('text_edit');
			
		$data['tab_about'] = $this->language->get('tab_about');
		
		$data['text_help_request'] = $this->language->get('text_help_request');
		$data['text_asking_help'] = $this->language->get('text_asking_help');		
		$data['text_terms'] = $this->language->get('text_terms');		
		
		$data['button_documentation'] = $this->language->get('button_documentation');

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['button_cancel'] = $this->language->get('button_cancel');	
		
		$data['token'] = $this->session->data['token'];
		
  		$data['breadcrumbs'] = array();
		
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')

   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_main'),
			'href'      => $this->url->link('module/adv_reports_products_profit', 'token=' . $this->session->data['token'], 'SSL')
   		);
		
		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
				
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/adv_reports_products_profit.tpl', $data));
	}
	
	public function install(){
		$this->load->model('user/user_group');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'module/adv_reports_products_profit');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'module/adv_reports_products_profit');	

		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'report/adv_products_profit');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'report/adv_products_profit');	
	}

	public function uninstall(){
		$this->load->model('extension/extension');
		$this->model_extension_extension->uninstall('module', 'adv_reports_products_profit');
	}
}