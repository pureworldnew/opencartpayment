<?php
class ControllerCatalogAdvSupplier extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/adv_supplier');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/adv_supplier');

		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/adv_supplier');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/adv_supplier');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_adv_supplier->addSupplier($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/adv_supplier', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/adv_supplier');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/adv_supplier');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_adv_supplier->editSupplier($this->request->get['supplier_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/adv_supplier', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/adv_supplier');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/adv_supplier');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $supplier_id) {
				$this->model_catalog_adv_supplier->deleteSupplier($supplier_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/adv_supplier', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/adv_supplier', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!file_exists(DIR_APPLICATION . 'model/module/adv_settings.php')) {
			$data['module_page'] = $this->response->redirect($this->url->link('module/adv_profit_module', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$data['add'] = $this->url->link('catalog/adv_supplier/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('catalog/adv_supplier/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['auth'] = FALSE;
		$data['ldata'] = FALSE;
		$data['suppliers'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$supplier_total = $this->model_catalog_adv_supplier->getTotalSuppliers();

		$results = $this->model_catalog_adv_supplier->getSuppliers($filter_data);

		foreach ($results as $result) {
			$data['suppliers'][] = array(
				'supplier_id' 	  => $result['supplier_id'],
				'name'            => $result['name'],
				'email'      	  => $result['email'],
				'telephone'       => $result['telephone'],				
				'website'         => $result['website'],
				'products'        => $result['products'],
				'link'         	  => $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&filter_supplier_id=' . $result['supplier_id'], 'SSL'),
				'status'       	  => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),				
				'edit'            => $this->url->link('catalog/adv_supplier/edit', 'token=' . $this->session->data['token'] . '&supplier_id=' . $result['supplier_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['adv_ext_name'] = $this->language->get('adv_ext_name');
		$data['adv_ext_short_name'] = 'adv_profit_module';
		$data['adv_ext_version'] = $this->language->get('adv_ext_version');	
		$data['adv_ext_url'] = 'http://www.opencart.com/index.php?route=extension/extension/info&extension_id=16601';

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_supplier_id'] = $this->language->get('column_supplier_id');	
		$data['column_name'] = $this->language->get('column_name');	
		$data['column_email'] = $this->language->get('column_email');
		$data['column_telephone'] = $this->language->get('column_telephone');
		$data['column_website'] = $this->language->get('column_website');		
		$data['column_products'] = $this->language->get('column_products');	
		$data['column_status'] = $this->language->get('column_status');	
		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');

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

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_supplier_id'] = $this->url->link('catalog/adv_supplier', 'token=' . $this->session->data['token'] . '&sort=s.supplier_id' . $url, 'SSL');
		$data['sort_name'] = $this->url->link('catalog/adv_supplier', 'token=' . $this->session->data['token'] . '&sort=s.name' . $url, 'SSL');
		$data['sort_email'] = $this->url->link('catalog/adv_supplier', 'token=' . $this->session->data['token'] . '&sort=s.email' . $url, 'SSL');
		$data['sort_telephone'] = $this->url->link('catalog/adv_supplier', 'token=' . $this->session->data['token'] . '&sort=s.telephone' . $url, 'SSL');
		$data['sort_website'] = $this->url->link('catalog/adv_supplier', 'token=' . $this->session->data['token'] . '&sort=s.website' . $url, 'SSL');
		$data['sort_products'] = $this->url->link('catalog/adv_supplier', 'token=' . $this->session->data['token'] . '&sort=products' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('catalog/adv_supplier', 'token=' . $this->session->data['token'] . '&sort=s.status' . $url, 'SSL');		

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $supplier_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/adv_supplier', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($supplier_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($supplier_total - $this->config->get('config_limit_admin'))) ? $supplier_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $supplier_total, ceil($supplier_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/adv_supplier_list.tpl', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['adv_ext_name'] = $this->language->get('adv_ext_name');
		$data['adv_ext_short_name'] = 'adv_profit_module';
		$data['adv_ext_version'] = $this->language->get('adv_ext_version');	
		$data['adv_ext_url'] = 'http://www.opencart.com/index.php?route=extension/extension/info&extension_id=16601';
		
		$data['text_form'] = !isset($this->request->get['supplier_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_supplier_detail'] = $this->language->get('text_supplier_detail');
		$data['text_supplier_address'] = $this->language->get('text_supplier_address');		

		$data['entry_supplier_id'] = $this->language->get('entry_supplier_id');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_website'] = $this->language->get('entry_website');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_telephone'] = $this->language->get('entry_telephone');
		$data['entry_fax'] = $this->language->get('entry_fax');	
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_company'] = $this->language->get('entry_company');
		$data['entry_address_1'] = $this->language->get('entry_address_1');
		$data['entry_address_2'] = $this->language->get('entry_address_2');
		$data['entry_city'] = $this->language->get('entry_city');
		$data['entry_postcode'] = $this->language->get('entry_postcode');
		$data['entry_country'] = $this->language->get('entry_country');
		$data['entry_zone'] = $this->language->get('entry_zone');		

		$data['button_save'] = $this->language->get('button_save');
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

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['auth'] = FALSE;
		$data['ldata'] = FALSE;
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/adv_supplier', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!file_exists(DIR_APPLICATION . 'model/module/adv_settings.php')) {
			$data['module_page'] = $this->response->redirect($this->url->link('module/adv_profit_module', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		if (!isset($this->request->get['supplier_id'])) {
			$data['action'] = $this->url->link('catalog/adv_supplier/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('catalog/adv_supplier/edit', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('catalog/adv_supplier', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['supplier_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$supplier_info = $this->model_catalog_adv_supplier->getSupplier($this->request->get['supplier_id']);
		}

		$data['token'] = $this->session->data['token'];

		if (!empty($supplier_info)) {
			$data['supplier_id'] = $supplier_info['supplier_id'];
		} else {
			$data['supplier_id'] = '';
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($supplier_info)) {
			$data['name'] = $supplier_info['name'];
		} else {
			$data['name'] = '';
		}

		$this->load->model('setting/store');

		$data['stores'] = $this->model_setting_store->getStores();

		if (isset($this->request->post['supplier_store'])) {
			$data['supplier_store'] = $this->request->post['supplier_store'];
		} elseif (isset($this->request->get['supplier_id'])) {
			$data['supplier_store'] = $this->model_catalog_adv_supplier->getSupplierStores($this->request->get['supplier_id']);
		} else {
			$data['supplier_store'] = array(0);
		}
		
		if (isset($this->request->post['website'])) {
			$data['website'] = $this->request->post['website'];
		} elseif (!empty($supplier_info)) {
			$data['website'] = $supplier_info['website'];
		} else {
			$data['website'] = '';
		}
		
		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} elseif (!empty($supplier_info)) {
			$data['email'] = $supplier_info['email'];
		} else {
			$data['email'] = '';
		}

		if (isset($this->request->post['telephone'])) {
			$data['telephone'] = $this->request->post['telephone'];
		} elseif (!empty($supplier_info)) {
			$data['telephone'] = $supplier_info['telephone'];
		} else {
			$data['telephone'] = '';
		}

		if (isset($this->request->post['fax'])) {
			$data['fax'] = $this->request->post['fax'];
		} elseif (!empty($supplier_info)) {
			$data['fax'] = $supplier_info['fax'];
		} else {
			$data['fax'] = '';
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($supplier_info)) {
			$data['status'] = $supplier_info['status'];
		} else {
			$data['status'] = true;
		}
		
		if (isset($this->request->post['company'])) {
			$data['company'] = $this->request->post['company'];
		} elseif (!empty($supplier_info)) {
			$data['company'] = $supplier_info['company'];
		} else {
			$data['company'] = '';
		}

		if (isset($this->request->post['address_1'])) {
			$data['address_1'] = $this->request->post['address_1'];
		} elseif (!empty($supplier_info)) {
			$data['address_1'] = $supplier_info['address_1'];
		} else {
			$data['address_1'] = '';
		}

		if (isset($this->request->post['address_2'])) {
			$data['address_2'] = $this->request->post['address_2'];
		} elseif (!empty($supplier_info)) {
			$data['address_2'] = $supplier_info['address_2'];
		} else {
			$data['address_2'] = '';
		}

		if (isset($this->request->post['city'])) {
			$data['city'] = $this->request->post['city'];
		} elseif (!empty($supplier_info)) {
			$data['city'] = $supplier_info['city'];
		} else {
			$data['city'] = '';
		}

		if (isset($this->request->post['postcode'])) {
			$data['postcode'] = $this->request->post['postcode'];
		} elseif (!empty($supplier_info)) {
			$data['postcode'] = $supplier_info['postcode'];
		} else {
			$data['postcode'] = '';
		}

		if (isset($this->request->post['country_id'])) {
			$data['country_id'] = $this->request->post['country_id'];
		} elseif (!empty($supplier_info)) {
			$data['country_id'] = $supplier_info['country_id'];
		} else {
			$data['country_id'] = '';
		}

		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();

		if (isset($this->request->post['zone_id'])) {
			$data['zone_id'] = $this->request->post['zone_id'];
		} elseif (!empty($supplier_info)) {
			$data['zone_id'] = $supplier_info['zone_id'];
		} else {
			$data['zone_id'] = '';
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/adv_supplier_form.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/adv_supplier')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 2) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/adv_supplier')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('catalog/product');

		foreach ($this->request->post['selected'] as $supplier_id) {
			$product_total = $this->model_catalog_adv_supplier->getTotalProductsBySupplierId($supplier_id);

			if ($product_total) {
				$this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);
			}
		}

		return !$this->error;
	}
}