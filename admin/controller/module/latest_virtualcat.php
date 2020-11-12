<?php
class ControllerModuleLatestVirtualcat extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('module/latest_virtualcat');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$status = false;	
			
			foreach ($this->request->post['latest_virtualcat'] as $latest_virtualcat) {
				if (@$latest_virtualcat['status']) {
					$status = true;
					
					break;
				}
			}
			
			$this->request->post['latest_virtualcat']['name'] = trim($this->request->post['latest_virtualcat']['name']);
			$this->request->post['latest_virtualcat']['meta_title'] = trim($this->request->post['latest_virtualcat']['meta_title']);
			
			$this->request->post['latest_virtualcat']['sort'] = $this->request->post['latest_virtualcat']['sort'] ? (int)$this->request->post['latest_virtualcat']['sort'] : 0;
			$this->request->post['latest_virtualcat']['limit'] = $this->request->post['latest_virtualcat']['limit'] ? (int)$this->request->post['latest_virtualcat']['limit'] : 0;
			$this->request->post['latest_virtualcat']['datelimit'] = $this->request->post['latest_virtualcat']['datelimit'] ? (int)$this->request->post['latest_virtualcat']['datelimit'] : 0;
			
			// Create virtual category
			
			$this->load->model('catalog/category');
			$catexists = $this->model_catalog_category->getCategory($this->request->post['latest_virtualcat']['category_id']);
			
			$data['image'] = isset($catexists['image']) ? $catexists['image'] : '';
			$data['sort_order'] = $this->request->post['latest_virtualcat']['sort'];
			$data['status'] = $this->request->post['latest_virtualcat']['status'];
			$data['parent_id'] = isset($catexists['parent_id']) ? $catexists['parent_id'] : 0;
			$data['top'] = isset($catexists['top']) ? $catexists['top'] : 1;
			$data['column'] = isset($catexists['column']) ? $catexists['column'] : 1;
			$data['keyword'] = isset($catexists['keyword']) ? $catexists['keyword'] : '';
			$data['category_description'][$this->config->get('config_language_id')] = array(
				'name' => $this->request->post['latest_virtualcat']['name'],
				'description' => isset($catexists['description']) ? $catexists['description'] : '',
				'meta_title' => $this->request->post['latest_virtualcat']['meta_title'],
				'meta_description' => isset($catexists['meta_description']) ? $catexists['meta_description'] : '',
				'meta_keyword' => isset($catexists['meta_keyword']) ? $catexists['meta_keyword'] : ''
			);
			
			if($this->request->post['latest_virtualcat']['category_id']) {				
				$data['category_layout'] = $this->model_catalog_category->getCategoryLayouts($this->request->post['latest_virtualcat']['category_id']);
				$data['category_store'] = $this->model_catalog_category->getCategoryStores($this->request->post['latest_virtualcat']['category_id']);
			
				$this->model_catalog_category->editCategory($this->request->post['latest_virtualcat']['category_id'], $data);	
				
			} else {			
				$data['category_layout'] = array();
				$data['category_store'] = array(0);
				$this->request->post['latest_virtualcat']['category_id'] = $this->model_catalog_category->addCategory($data);
	
			}
				
			$this->model_setting_setting->editSetting('latest_virtualcat', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
			
			if(isset($this->request->post['latest_virtualcat']['continue'])) {
				$this->response->redirect($this->url->link('module/latest_virtualcat', 'token=' . $this->session->data['token'], 'SSL'));
			} else {
				$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_info'] = $this->language->get('text_info');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_sort_product_limit'] = $this->language->get('text_sort_product_limit');
		$data['text_sort_date_limit'] = $this->language->get('text_sort_date_limit');
		
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_meta_title'] = $this->language->get('entry_meta_title');
		$data['entry_sort'] = $this->language->get('entry_sort');
		$data['entry_limit'] = $this->language->get('entry_limit');
		$data['entry_datelimit'] = $this->language->get('entry_datelimit');
		$data['entry_sort_option'] = $this->language->get('entry_sort_option');
		$data['entry_sort_latest'] = $this->language->get('entry_sort_latest');
		$data['entry_sort_default'] = $this->language->get('entry_sort_default');
		$data['entry_req'] = $this->language->get('entry_req');
		$data['entry_status'] = $this->language->get('entry_status');
		
		$data['help_name'] = $this->language->get('help_name');
		$data['help_meta_title'] = $this->language->get('help_meta_title');
		$data['help_sort'] = $this->language->get('help_sort');
		$data['help_limit'] = $this->language->get('help_limit');
		$data['help_datelimit'] = $this->language->get('help_datelimit');
		$data['help_sort_option'] = $this->language->get('help_sort_option');
		$data['help_sort_latest'] = $this->language->get('help_sort_latest');
		$data['help_sort_default'] = $this->language->get('help_sort_default');
		$data['help_status'] = $this->language->get('help_status');

		$data['info_datelimit'] = $this->language->get('info_datelimit');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_saveandcontinue'] =  $this->language->get('button_saveandcontinue');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}
		
		if (isset($this->error['meta_title'])) {
			$data['error_meta_title'] = $this->error['meta_title'];
		} else {
			$data['error_meta_title'] = '';
		}
		
		if (isset($this->error['sort'])) {
			$data['error_sort'] = $this->error['sort'];
		} else {
			$data['error_sort'] = '';
		}
		
		if (isset($this->error['limit'])) {
			$data['error_limit'] = $this->error['limit'];
		} else {
			$data['error_limit'] = '';
		}
		
		if (isset($this->error['datelimit'])) {
			$data['error_datelimit'] = $this->error['datelimit'];
		} else {
			$data['error_datelimit'] = '';
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
			'href' => $this->url->link('module/latest_virtualcat', 'token=' . $this->session->data['token'], 'SSL')
		);			
		
		$data['action'] = $this->url->link('module/latest_virtualcat', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		$getcat = $this->config->get('latest_virtualcat');
		
		$data['category_link'] = $this->url->link('catalog/category/edit', 'token=' . $this->session->data['token'] . '&category_id=' . $getcat['category_id'], 'SSL');
		
		if (isset($this->request->post['latest_virtualcat'])) {
			$data['latest_virtualcat'] = $this->request->post['latest_virtualcat'];
		} else {
			$data['latest_virtualcat'] = $this->config->get('latest_virtualcat');
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/latest_virtualcat.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/latest_virtualcat')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if ((utf8_strlen($this->request->post['latest_virtualcat']['name']) < 2) || (utf8_strlen($this->request->post['latest_virtualcat']['name']) > 32)) {
			$this->error['name'] = $this->language->get('error_name');
		}
		
		if ((utf8_strlen($this->request->post['latest_virtualcat']['meta_title']) < 3) || (utf8_strlen($this->request->post['latest_virtualcat']['meta_title']) > 255)) {
			$this->error['meta_title'] = $this->language->get('error_meta_title');
		}
		
		if (!empty($this->request->post['latest_virtualcat']['sort']) && !is_numeric($this->request->post['latest_virtualcat']['sort'])) {
			$this->error['sort'] = $this->language->get('error_sort');
		}
		
		if (!empty($this->request->post['latest_virtualcat']['sort']) && $this->request->post['latest_virtualcat']['sort'] < 0) {
			$this->error['sort'] = $this->language->get('error_sort_neg');
		}
		
		if (!empty($this->request->post['latest_virtualcat']['limit']) && !is_numeric($this->request->post['latest_virtualcat']['limit'])) {
			$this->error['limit'] = $this->language->get('error_limit');
		}
		
		if (!empty($this->request->post['latest_virtualcat']['datelimit']) && !is_numeric($this->request->post['latest_virtualcat']['datelimit'])) {
			$this->error['datelimit'] = $this->language->get('error_datelimit');
		}
		
		if (!empty($this->request->post['latest_virtualcat']['limit']) && $this->request->post['latest_virtualcat']['limit'] < 0) {
			$this->error['limit'] = $this->language->get('error_limit_neg');
		}
		
		if (!empty($this->request->post['latest_virtualcat']['datelimit']) && $this->request->post['latest_virtualcat']['datelimit'] < 0) {
			$this->error['datelimit'] = $this->language->get('error_datelimit_neg');
		}		

		return !$this->error;
	}
	
	public function install() {	
		$this->load->model('extension/event');
		$this->model_extension_event->addEvent('latest_virtualcat', 'post.admin.category.edit', 'module/latest_virtualcat/edit_category');
		$this->model_extension_event->addEvent('latest_virtualcat', 'post.admin.category.delete', 'module/latest_virtualcat/delete_category');
	}
	
	public function uninstall() {
		$getcatid = $this->config->get('latest_virtualcat');
		if($getcatid['category_id']) {
			$this->load->model('catalog/category');
			$this->model_catalog_category->deleteCategory($getcatid['category_id']);
		}
		
		$this->load->model('extension/event');
		$this->model_extension_event->deleteEvent('latest_virtualcat');

	}
	
	public function edit_category($cat_id) {
		
		$this->load->model('catalog/category');
		$catexists = $this->model_catalog_category->getCategory($cat_id);
		
		$getconf = $this->config->get('latest_virtualcat');
		
		if(isset($catexists['category_id']) && $getconf['category_id'] == $cat_id) {
				
			$data['latest_virtualcat'] = array(
			'continue' => '',
			'name' => trim($catexists['name']),
			'category_id' => $catexists['category_id'],
			'meta_title' => trim($catexists['meta_title']),
			'sort' => (int)$catexists['sort_order'],
			'limit' => (int)$getconf['limit'],
			'datelimit' => (int)$getconf['datelimit'],
			'sort_option' => (int)$getconf['sort_option'],
			'sort_default' => (int)$getconf['sort_default'],
			'sort_latest' => $getconf['sort_latest'],
			'status' => $catexists['status']
			);
			
			$this->load->model('setting/setting');
			$this->model_setting_setting->editSetting('latest_virtualcat', $data);
		}
	}
	
	public function delete_category($cat_id) {
		
		$this->load->model('catalog/category');
		$catexists = $this->model_catalog_category->getCategory($cat_id);
		
		$getconf = $this->config->get('latest_virtualcat');
		
		if(isset($catexists['category_id']) && $getconf['category_id'] == $cat_id) {
			
			$this->model_catalog_category->deleteCategory($cat_id);
			
		}
		
		if($getconf['category_id'] == $cat_id) {
			
			$this->load->model('setting/setting');
			$this->model_setting_setting->deleteSetting('latest_virtualcat');
		}
	}
}