<?php
class ControllerCatalogProductGroupedSort extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('catalog/product_grouped_sort');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');

		$this->getList();
	}

	public function update() {
		$this->language->load('catalog/product_grouped_sort');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');

		if (isset($this->request->post['grouped_sort_order']) && $this->validateUpdate()) {
			
			$this->model_catalog_product->updateGroupProductSorting($this->request->post['grouped_sort_order']);
			if( isset( $this->request->post['other_option'] ) )
			{
				$this->model_catalog_product->updateOtherOptionsSorting($this->request->post['other_option']);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			

			$this->response->redirect($this->url->link('catalog/product_grouped_sort', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['filter_model'])) {
			$filter_model = $this->request->get['filter_model'];
		} else {
			$filter_model = null;
		}

		

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/product_grouped_sort', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['update'] = $this->url->link('catalog/product_grouped_sort/update', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['products'] = array();

		$filter_data = array(
			'filter_model'	  => $filter_model
		);

		$results = $this->model_catalog_product->getGroupProductsSorting($filter_data);
		$data['main_options'] = array();
		if( isset( $results['product_grouped'] ) )
		{
			foreach ($results['product_grouped'] as $result) { 
				$data['main_options'][] = array(
					'product_grouped_id' => $result['product_grouped_id'],
					'type' => $result['type'],
					'option_name' => $result['option_name'],
					'sort_order' => $result['sort_order']
				);
			}
		}
		$data['other_options'] = array();
		if( isset( $results['options'] ) ) 
		{
			foreach ($results['options'] as $option) {
			
				foreach ( $option['product_option_value'] as $product_option_value )
				$data['other_options'][] = array(
					'name' 		 			  => $option['name'],
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'option_value_name' => $product_option_value['option_value_name'],
					'sort_order' => $product_option_value['sort_order']
				);
			}
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		

		$data['column_product_id'] = $this->language->get('column_product_id');
		$data['column_group_id'] = $this->language->get('column_group_id');
		$data['column_option'] = $this->language->get('column_option');
		$data['column_type'] = $this->language->get('column_type');
		$data['column_groupby_type'] = $this->language->get('column_groupby_type');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		

		$data['entry_groupindicator'] = $this->language->get('entry_groupindicator');
		$data['entry_model'] = $this->language->get('entry_model');
		$data['entry_groupby_option'] = $this->language->get('entry_groupby_option');
		$data['entry_other_option'] = $this->language->get('entry_other_option');
		$data['entry_nogroupby_option'] = $this->language->get('entry_nogroupby_option');
		$data['entry_noother_option'] = $this->language->get('entry_noother_option');
		
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

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

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		$data['filter_model'] = $filter_model;
		

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/product_list_sort.tpl', $data));
	}

	protected function validateUpdate() {
		if (!$this->user->hasPermission('modify', 'catalog/product_grouped_sort')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	
	
	
	
	
	
}
