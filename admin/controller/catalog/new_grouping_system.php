<?php
class ControllerCatalogNewGroupingSystem extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('catalog/new_grouping_system');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/new_grouping_system');
		
		if (isset($this->request->get['filter_groupindicator'])) {
			//$this->model_catalog_product->exportProductsByGroupIndicator($this->request->get['filter_groupindicator']);
		}

		$this->getList();
	}

	

	protected function getList() {
		if (isset($this->request->get['filter_groupindicator'])) {
			$filter_groupindicator = $this->request->get['filter_groupindicator'];
		} else {
			$filter_groupindicator = null;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_groupindicator'])) {
			$url .= '&filter_groupindicator=' . urlencode(html_entity_decode($this->request->get['filter_groupindicator'], ENT_QUOTES, 'UTF-8'));
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
			'href' => $this->url->link('catalog/new_grouping_system', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		//$data['update'] = $this->url->link('catalog/product_grouped_sort/update', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['products'] = array();

		$filter_data = array(
			'filter_groupindicator'	  => $filter_groupindicator,
			'start'           		  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           		  => $this->config->get('config_limit_admin')
		);

		$product_total = $this->model_catalog_new_grouping_system->getTotalGroupIndicators($filter_data);

		$results = $this->model_catalog_new_grouping_system->getGroupIndicators($filter_data);

		foreach ($results as $result) { 
			$data['products'][] = array(
				'product_id' 		=> $result['product_id'],
				'name'       		=> $result['name'],
				'sku'      			=> $result['sku'],
				'groupindicator'    => $result['groupindicator'],
				'groupindicator_id' => $result['groupindicator_id'],
				'new_grouping_system' => $result['new_grouping_system'],
				'update'       		=> $this->url->link('catalog/new_grouping_system/update', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		

		$data['column_product_id'] = $this->language->get('column_product_id');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_groupindicator'] = $this->language->get('column_groupindicator');
		$data['column_groupindicator_id'] = $this->language->get('column_groupindicator_id');
		$data['column_new'] = $this->language->get('column_new');
		
		

		$data['entry_groupindicator'] = $this->language->get('entry_groupindicator');
		$data['entry_groupby_option'] = $this->language->get('entry_groupby_option');
		$data['entry_other_option'] = $this->language->get('entry_other_option');
		$data['entry_nogroupby_option'] = $this->language->get('entry_nogroupby_option');
		$data['entry_noother_option'] = $this->language->get('entry_noother_option');
		
		$data['button_update'] = $this->language->get('button_update');
		$data['button_filter'] = $this->language->get('button_filter');
		
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		

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

		if (isset($this->request->get['filter_groupindicator'])) {
			$url .= '&filter_groupindicator=' . urlencode(html_entity_decode($this->request->get['filter_groupindicator'], ENT_QUOTES, 'UTF-8'));
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/new_grouping_system', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

		$data['filter_groupindicator'] = $filter_groupindicator;
		

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/new_grouping_system.tpl', $data));
	}

	public function updateGroupProduct()
	{
		$json = array();
		$groupindicator_id 		= $this->request->post['groupindicator_id'];
		$new_grouping_system 	= $this->request->post['new_grouping_system'];
		$this->load->model('catalog/new_grouping_system');
		$this->model_catalog_new_grouping_system->updateGroupProduct($groupindicator_id, $new_grouping_system);
		$json['success'] = "Updated successfully.";
		$this->response->setOutput(json_encode($json));
	}

	public function updateBulkGroupProducts()
	{
		$json = array();

		if ( !isset($this->request->post['products']) || count($this->request->post['products']) == 0 )
		{
			$json['error'] = "Please first select atleast one product";
		} else {
			$this->load->model('catalog/new_grouping_system');
			$products = $this->request->post['products'];
			$new_grouping_system = $this->request->post['selected'];

			foreach( $products as $product)
			{
				$this->model_catalog_new_grouping_system->updateGroupProduct($product, $new_grouping_system);
			}
			$json['success'] = "Group products modified successfully";
		}

		$this->response->setOutput(json_encode($json));

	}

}
