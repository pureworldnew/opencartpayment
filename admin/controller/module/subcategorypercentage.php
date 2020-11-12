<?php
class ControllerModuleSubcategorypercentage extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('module/subcategorypercentage');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('subcategorypercentage', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('module/subcategorypercentage', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
        $data['token']=$this->session->data['token'];
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/subcategorypercentage', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('module/subcategorypercentage', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['subcategorypercentage_status'])) {
			$data['subcategorypercentage_status'] = $this->request->post['subcategorypercentage_status'];
		} else {
			$data['subcategorypercentage_status'] = $this->config->get('subcategorypercentage_status');
		}
        if (isset($this->request->post['subcategorypercentage_category'])) {
			$data['subcategorypercentage_category'] = $this->request->post['subcategorypercentage_category'];
		} else {
			$data['subcategorypercentage_category'] = $this->config->get('subcategorypercentage_category');
		}
		
		$this->load->model('catalog/category');

		if(null!==$this->config->get('subcategorypercentage_category')){
			$categories = $this->config->get('subcategorypercentage_category');
		}else{
			 $categories=array();
		}
			
		
		
		$data['product_categories'] = array();

		foreach ($categories as $category_id) {
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info) {
				$data['product_categories'][] = array(
					'category_id' => $category_info['category_id'],
					'name' => ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name']
				);
			}
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/subcategorypercentage.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/subcategorypercentage')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}