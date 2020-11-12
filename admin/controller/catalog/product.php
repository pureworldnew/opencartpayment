<?php
class ControllerCatalogProduct extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');

		$this->getList();
	}

	public function add() {
		$this->language->load('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_product->addProduct($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}

			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->language->load('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			//echo '<pre>';print_r($this->request->post);exit;
			
			$this->model_catalog_product->editProduct($this->request->get['product_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}

			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->language->load('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $product_id) {
				$this->model_catalog_product->deleteProduct($product_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}

			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	public function copy() {
		$this->language->load('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');

		if (isset($this->request->post['selected']) && $this->validateCopy()) {
			foreach ($this->request->post['selected'] as $product_id) {
				$this->model_catalog_product->copyProduct($product_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}

			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_model'])) {
			$filter_model = $this->request->get['filter_model'];
		} else {
			$filter_model = null;
		}

		if (isset($this->request->get['filter_price'])) {
			$filter_price = $this->request->get['filter_price'];
		} else {
			$filter_price = null;
		}

		if (isset($this->request->get['filter_price_type'])) {
			$filter_price_type = $this->request->get['filter_price_type'];
		} else {
			$filter_price_type = 0;
		}

		if (isset($this->request->get['filter_quantity'])) {
			$filter_quantity = $this->request->get['filter_quantity'];
			
		} else {
			$filter_quantity = null;
		}

		if (isset($this->request->get['filter_quantity_type'])) {
			$filter_quantity_type = $this->request->get['filter_quantity_type'];
		} else {
			$filter_quantity_type = 0;
		}

		if (isset($this->request->get['filter_product_type'])) {
			$filter_product_type = $this->request->get['filter_product_type'];
		} else {
			$filter_product_type = 1;
		}

		if (isset($this->request->get['filter_location'])) {
			$filter_location= $this->request->get['filter_location'];
		}else{
			$filter_location = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['filter_record_limit'])) {
			$filter_record_limit = $this->request->get['filter_record_limit'];
		} else {
			$filter_record_limit = '20';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pd.name';
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

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_price_type'])) {
			$url .= '&filter_price_type=' . $this->request->get['filter_price_type'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_quantity_type'])) {
			$url .= '&filter_quantity_type=' . $this->request->get['filter_quantity_type'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_record_limit'])) {
			$url .= '&filter_record_limit=' . $this->request->get['filter_record_limit'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['manufacturer']['modify'])) {
			$data['modify'] = $this->request->get['manufacturer']['modify'];
		}else {
			$data['modify'] = array();
		}

		if (isset($this->request->get['category']['access'])) {
			$data['access'] = $this->request->get['category']['access'];
		}else {
			$data['access'] = array();
		}

		

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/product', 'token=' . $this->session->data['token'], 'SSL') //remove url to reset search filters
		);

		$data['add'] = $this->url->link('catalog/product/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['copy'] = $this->url->link('catalog/product/copy', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('catalog/product/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['action_create'] = $this->url->link('catalog/incomingorders/createIncomingOrder', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['products'] = array();

		$filter_data = array(
			'filter_name'	  => $filter_name,
			'filter_model'	  => $filter_model,
			'filter_price'	  => $filter_price,
			'filter_price_type' => $filter_price_type,
			'filter_quantity' => $filter_quantity,
			'filter_quantity_type' => $filter_quantity_type,
			'filter_product_type' => $filter_product_type,
			'filter_location'	=> $filter_location,
			'filter_status'   => $filter_status,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $filter_record_limit,
			'limit'           => $filter_record_limit
		);

		$this->load->model('tool/image');

		$product_total = $this->model_catalog_product->getTotalProducts($filter_data);

		$results = $this->model_catalog_product->getProducts($filter_data);

		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 40, 40);
			}

			$special = false;

			$product_specials = $this->model_catalog_product->getProductSpecials($result['product_id']);

			foreach ($product_specials  as $product_special) {
				if (($product_special['date_start'] == '0000-00-00' || strtotime($product_special['date_start']) < time()) && ($product_special['date_end'] == '0000-00-00' || strtotime($product_special['date_end']) > time())) {
					$special = $product_special['price'];

					break;
				}
			}
			
			$this->load->model("report/product");
			
			$model = $result['model'];
			
			$getProductGroupIndicator = $this->model_report_product->getProductGroupIndicator($result['product_id']);
			if(!empty($getProductGroupIndicator))
			{
				$indicator = $this->model_report_product->getGroupIndicatorById($getProductGroupIndicator['group_indicator']);
				if(!empty($indicator))
				{
					$model = "group^" . $indicator['groupindicator'];
				}
			}

			$data['products'][] = array(
				'product_id' => $result['product_id'],
				'image'      => $image,
				'name'       => $result['name'],
				'model'      => $model,//$result['model'],
				'location'   => $result['location'],
				'price'      => $result['price'],
				'special'    => $special,
				'quantity'   => $result['quantity'],
				'status'     => ($result['status']) ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'edit'       => $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_image'] = $this->language->get('column_image');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_model'] = $this->language->get('entry_model');
		$data['entry_price'] = $this->language->get('entry_price');
		$data['entry_quantity'] = $this->language->get('entry_quantity');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_copy'] = $this->language->get('button_copy');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
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

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_price_type'])) {
			$url .= '&filter_price_type=' . $this->request->get['filter_price_type'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_quantity_type'])) {
			$url .= '&filter_quantity_type=' . $this->request->get['filter_quantity_type'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_record_limit'])) {
			$url .= '&filter_record_limit=' . $this->request->get['filter_record_limit'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL');
		$data['sort_model'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.model' . $url, 'SSL');
		$data['sort_location'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.location' . $url, 'SSL');
		$data['sort_price'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.price' . $url, 'SSL');
		$data['sort_quantity'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.quantity' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.status' . $url, 'SSL');
		$data['sort_order'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_price_type'])) {
			$url .= '&filter_price_type=' . $this->request->get['filter_price_type'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_quantity_type'])) {
			$url .= '&filter_quantity_type=' . $this->request->get['filter_quantity_type'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_record_limit'])) {
			$url .= '&filter_record_limit=' . $this->request->get['filter_record_limit'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $filter_record_limit;
		$pagination->url = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $filter_record_limit) + 1 : 0, ((($page - 1) * $filter_record_limit) > ($product_total - $filter_record_limit)) ? $product_total : ((($page - 1) * $filter_record_limit) + $filter_record_limit), $product_total, ceil($product_total / $filter_record_limit));

		$data['filter_name'] = $filter_name;
		$data['filter_model'] = $filter_model;
		$data['filter_price'] = $filter_price;
		$data['filter_quantity'] = $filter_quantity;
		$data['filter_location'] = $filter_location;
		$data['filter_status'] = $filter_status;
		$data['filter_price_type'] = $filter_price_type;
		$data['filter_quantity_type'] = $filter_quantity_type;
		$data['filter_product_type'] = $filter_product_type;
		$data['filter_record_limit'] = $filter_record_limit; 

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/product_list.tpl', $data));
	}

	public function export() {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_model'])) {
			$filter_model = $this->request->get['filter_model'];
		} else {
			$filter_model = null;
		}

		if (isset($this->request->get['filter_price'])) {
			$filter_price = $this->request->get['filter_price'];
		} else {
			$filter_price = null;
		}

		if (isset($this->request->get['filter_price_type'])) {
			$filter_price_type = $this->request->get['filter_price_type'];
		} else {
			$filter_price_type = 0;
		}

		if (isset($this->request->get['filter_quantity'])) {
			$filter_quantity = $this->request->get['filter_quantity'];
			
		} else {
			$filter_quantity = null;
		}

		if (isset($this->request->get['filter_quantity_type'])) {
			$filter_quantity_type = $this->request->get['filter_quantity_type'];
		} else {
			$filter_quantity_type = 0;
		}

		if (isset($this->request->get['filter_product_type'])) {
			$filter_product_type = $this->request->get['filter_product_type'];
		} else {
			$filter_product_type = 1;
		}

		if (isset($this->request->get['filter_location'])) {
			$filter_location= $this->request->get['filter_location'];
		}else{
			$filter_location = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['filter_category_id'])) {
			$products_from_categories = 'selected';
			$filter_category_id = $this->request->get['filter_category_id'];
		} else {
			$products_from_categories = 'all';
			$filter_category_id = array();
		}
		
		if (isset($this->request->get['filter_manufacturer_id'])) {
			$products_from_manufacturers = 'selected';
			$filter_manufacturer_id = $this->request->get['filter_manufacturer_id'];
		} else {
			$products_from_manufacturers = 'all';
			$filter_manufacturer_id = array();
		}

		$this->load->model('tool/ka_product_export');

		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();

		$this->load->model('localisation/currency');
		$data['currencies'] = $this->model_localisation_currency->getCurrencies();

		$profiles = $this->model_tool_ka_product_export->getProfiles();
		$data['profiles'] = $profiles;
		
		if (empty($this->params) || ($this->request->server['REQUEST_METHOD'] == 'GET' && empty($this->session->data['save_params']))) {

			$language_code = $this->config->get('config_language');
			if (!empty($data['languages'][$language_code])) {
				$language_id = $data['languages'][$language_code]['language_id'];
			} else {
				$language_id = 0;
			}
	
			$currency_code = $this->config->get('config_currency');
			if (!empty($data['currencies'][$currency_code])) {
				$currency = $data['currencies'][$currency_code];
			} else {
				$currency = array('code' => $currency_code);
			}

			$file = $this->model_tool_ka_product_export->tempname(DIR_CACHE, "export_" . date("Y-m-d") . '_', 'csv');
			
			$this->params = array(
				'charset'             => 'UTF-8', // 'UTF-16LE' - MS Excel
				'file_settings'       => 'custom',
				'charset_option'      => 'predefined',
				'cat_separator'       => '///',
				'delimiter'           => 's',
				'store_id'            => 0,
				'sort_by'             => 'name',
				'image_path'          => 'path',
				'products_from_categories'    => $products_from_categories,
				'products_from_manufacturers' => $products_from_manufacturers,
				'category_ids'        => explode(',' , $filter_category_id),
				'manufacturer_ids'    => explode(',' , $filter_manufacturer_id),
				'profile_name'        => '',
				'profile_id'          => '',
				'copy_path'           => '',
				'geo_zone_id'         => '',
				'customer_group_id'   => 0,
				'apply_taxes'         => false,				
				'use_special_price'   => false,
				'exclude_inactive'    => false,
				'exclude_zero_qty' => false,
				'separate_units'   => false,
				'language_id'					=> $language_id,
				'currency'						=> $currency,
				'file'							=> $file,
				'filter_name' 					=> $filter_name,
				'filter_model' 					=> $filter_model,
				'filter_price' 					=> $filter_price,
				'filter_price_type' 			=> $filter_price_type,
				'filter_quantity' 				=> $filter_quantity,
				'filter_quantity_type' 			=> $filter_quantity_type,
				'filter_product_type' 			=> $filter_product_type,
				'filter_location' 				=> $filter_location,
				'filter_status' 				=> $filter_status,
				);
		 }
		return $this->load->controller('tool/ka_product_export/step2',$this->params);
	}

	public function updateQuantity()
	{
		$product_id = $this->request->get['product_id'];
		$quantity = $this->request->get['quantity'];
		$que = "UPDATE ". DB_PREFIX . "product SET quantity = '" . $quantity . "' where product_id = '". $product_id."'";
		$result = $this->db->query($que);
		if ($result){
			$json['message'] = 'Update is Successfull';
		}else{
			$json['message'] = "Error Updating quantity. Try again";
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function updateStatus()
	{
		$product_id = $this->request->get['product_id'];
		$status = $this->request->get['status'];
		if ($status == "Enabled")
			$st = 1;
		else
			$st = 0;
		$que = "UPDATE ". DB_PREFIX . "product SET status = '" . $st . "' where product_id = '". $product_id."'";
		$result = $this->db->query($que);
		if ($result){
			$json['message'] = 'Update is Successfull';
		}else{
			$json['message'] = "Error Updating Status. Try again";
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['product_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_plus'] = $this->language->get('text_plus');
		$data['text_minus'] = $this->language->get('text_minus');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_option'] = $this->language->get('text_option');
		$data['text_option_value'] = $this->language->get('text_option_value');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_percent'] = $this->language->get('text_percent');
		$data['text_amount'] = $this->language->get('text_amount');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_meta_title'] = $this->language->get('entry_meta_title');
		$data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$data['entry_keyword'] = $this->language->get('entry_keyword');
		$data['entry_model'] = $this->language->get('entry_model');
		$data['entry_sku'] = $this->language->get('entry_sku');
		$data['entry_upc'] = $this->language->get('entry_upc');
		$data['entry_ean'] = $this->language->get('entry_ean');
		$data['entry_jan'] = $this->language->get('entry_jan');
		$data['entry_isbn'] = $this->language->get('entry_isbn');
		$data['entry_mpn'] = $this->language->get('entry_mpn');
		$data['entry_location'] = $this->language->get('entry_location');
		$data['entry_minimum'] = $this->language->get('entry_minimum');
		$data['entry_minimum_amount'] = $this->language->get('entry_minimum_amount');
		$data['entry_shipping'] = $this->language->get('entry_shipping');
		$data['entry_date_available'] = $this->language->get('entry_date_available');
		$data['entry_frontend_date_available'] = $this->language->get('entry_frontend_date_available');
		$data['entry_quantity'] = $this->language->get('entry_quantity');
		$data['entry_stock_status'] = $this->language->get('entry_stock_status');
		$data['entry_price'] = $this->language->get('entry_price');
		$data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$data['entry_points'] = $this->language->get('entry_points');
		$data['entry_option_points'] = $this->language->get('entry_option_points');
		$data['entry_subtract'] = $this->language->get('entry_subtract');
		$data['entry_weight_class'] = $this->language->get('entry_weight_class');
		$data['entry_weight'] = $this->language->get('entry_weight');
		$data['entry_dimension'] = $this->language->get('entry_dimension');
		$data['entry_length_class'] = $this->language->get('entry_length_class');
		$data['entry_length'] = $this->language->get('entry_length');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
		$data['entry_download'] = $this->language->get('entry_download');
		$data['entry_category'] = $this->language->get('entry_category');
		$data['entry_filter'] = $this->language->get('entry_filter');
		$data['entry_related'] = $this->language->get('entry_related');
		$data['entry_attribute'] = $this->language->get('entry_attribute');
		$data['entry_text'] = $this->language->get('entry_text');
		$data['entry_option'] = $this->language->get('entry_option');
		$data['entry_option_value'] = $this->language->get('entry_option_value');
		$data['entry_required'] = $this->language->get('entry_required');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_priority'] = $this->language->get('entry_priority');
		$data['entry_tag'] = $this->language->get('entry_tag');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_reward'] = $this->language->get('entry_reward');
		$data['entry_layout'] = $this->language->get('entry_layout');
		$data['entry_recurring'] = $this->language->get('entry_recurring');
		$data['entry_date_sold_out'] = $this->language->get('entry_date_sold_out');
		$data['entry_date_ordered'] = $this->language->get('entry_date_ordered');
		$data['entry_estimate_deliver_time'] = $this->language->get('entry_estimate_deliver_time');
		$data['entry_show_product_label_1'] = $this->language->get('entry_show_product_label_1');
		$data['entry_product_label_text_1'] = $this->language->get('entry_product_label_text_1');
		$data['entry_show_product_label_2'] = $this->language->get('entry_show_product_label_2');
		$data['entry_product_label_text_2'] = $this->language->get('entry_product_label_text_2');
		$data['entry_multiplier_name'] = $this->language->get('entry_multiplier_name');
		$data['entry_multiplier_value'] = $this->language->get('entry_multiplier_value');
		$data['entry_is_gift_product'] = $this->language->get('entry_is_gift_product');

		$data['help_keyword'] = $this->language->get('help_keyword');
		$data['help_sku'] = $this->language->get('help_sku');
		$data['help_upc'] = $this->language->get('help_upc');
		$data['help_ean'] = $this->language->get('help_ean');
		$data['help_jan'] = $this->language->get('help_jan');
		$data['help_isbn'] = $this->language->get('help_isbn');
		$data['help_mpn'] = $this->language->get('help_mpn');
		$data['help_minimum'] = $this->language->get('help_minimum');
		$data['help_manufacturer'] = $this->language->get('help_manufacturer');
		$data['help_stock_status'] = $this->language->get('help_stock_status');
		$data['help_points'] = $this->language->get('help_points');
		$data['help_category'] = $this->language->get('help_category');
		$data['help_filter'] = $this->language->get('help_filter');
		$data['help_download'] = $this->language->get('help_download');
		$data['help_related'] = $this->language->get('help_related');
		$data['help_tag'] = $this->language->get('help_tag');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_attribute_add'] = $this->language->get('button_attribute_add');
		$data['button_option_add'] = $this->language->get('button_option_add');
		$data['button_option_value_add'] = $this->language->get('button_option_value_add');
		$data['button_discount_add'] = $this->language->get('button_discount_add');
		$data['button_special_add'] = $this->language->get('button_special_add');
		$data['button_image_add'] = $this->language->get('button_image_add');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['button_recurring_add'] = $this->language->get('button_recurring_add');

		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_data'] = $this->language->get('tab_data');
		$data['tab_attribute'] = $this->language->get('tab_attribute');
		$data['tab_option'] = $this->language->get('tab_option');
		$data['tab_recurring'] = $this->language->get('tab_recurring');
		$data['tab_discount'] = $this->language->get('tab_discount');
		$data['tab_special'] = $this->language->get('tab_special');
		$data['tab_image'] = $this->language->get('tab_image');
		$data['tab_links'] = $this->language->get('tab_links');
		$data['tab_reward'] = $this->language->get('tab_reward');
		$data['tab_design'] = $this->language->get('tab_design');
		$data['tab_openbay'] = $this->language->get('tab_openbay');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
		}

		if (isset($this->error['meta_title'])) {
			$data['error_meta_title'] = $this->error['meta_title'];
		} else {
			$data['error_meta_title'] = array();
		}

		if (isset($this->error['model'])) {
			$data['error_model'] = $this->error['model'];
		} else {
			$data['error_model'] = '';
		}

		if (isset($this->error['keyword'])) {
			$data['error_keyword'] = $this->error['keyword'];
		} else {
			$data['error_keyword'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

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
			'href' => $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['product_id'])) {
			$data['action'] = $this->url->link('catalog/product/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $this->request->get['product_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['product_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);
		}

		if(isset($this->request->post['front_quantity'])){
			$data['front_quantity']=$this->request->post['front_quantity'];
		}elseif(!empty($product_info)){
			$data['front_quantity']=$product_info['front_quantity'];
		}else{
			$data['front_quantity']="";
		}

		if(isset($this->request->post['storage_quantity'])){
			$data['storage_quantity']=$this->request->post['storage_quantity'];
		}elseif(!empty($product_info)){
			$data['storage_quantity']=$product_info['storage_quantity'];
		}else{
			$data['storage_quantity']="";
		}

		$data['token'] = $this->session->data['token'];
		
		 $data['locations']=array();

        $location_query=$this->db->query("SELECT * FROM ".DB_PREFIX."locations");
        if($location_query->num_rows){
        	$data['locations']=$location_query->rows;
        }

        $data['unitconversions']=array();
   if(isset($this->request->get['product_id'])){
	$unitquery=$this->db->query("SELECT * FROM ".DB_PREFIX."unit_conversion_product oucp LEFT JOIN ".DB_PREFIX."unit_conversion_value oucv ON(oucp.unit_value_id=oucv.unit_value_id) WHERE oucp.product_id='".$this->request->get['product_id']."'");
	if($unitquery->num_rows){
		$data['unitconversions']=$unitquery->rows;
	}
    }

    $data['getlistdata']=array();

    if(isset($this->request->get['product_id'])){
    	$quantitydata=$this->db->query("SELECT * FROM ".DB_PREFIX."product_to_location_quantity WHERE product_id='".$this->request->get['product_id']."'");
    	if($quantitydata->num_rows){
    		$data['getlistdata']=$quantitydata->rows;
    	}
    }
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['product_description'])) {
			$data['product_description'] = $this->request->post['product_description'];
		} elseif (isset($this->request->get['product_id'])) {
			$data['product_description'] = $this->model_catalog_product->getProductDescriptions($this->request->get['product_id']);
		} else {
			$data['product_description'] = array();
		}

		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($product_info)) {
			$data['image'] = $product_info['image'];
		} else {
			$data['image'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($product_info) && is_file(DIR_IMAGE . $product_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($product_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (isset($this->request->post['model'])) {
			$data['model'] = $this->request->post['model'];
		} elseif (!empty($product_info)) {
			$data['model'] = $product_info['model'];
		} else {
			$data['model'] = '';
		}

		if (isset($this->request->post['sku'])) {
			$data['sku'] = $this->request->post['sku'];
		} elseif (!empty($product_info)) {
			$data['sku'] = $product_info['sku'];
		} else {
			$data['sku'] = '';
		}

		if (isset($this->request->post['upc'])) {
			$data['upc'] = $this->request->post['upc'];
		} elseif (!empty($product_info)) {
			$data['upc'] = $product_info['upc'];
		} else {
			$data['upc'] = '';
		}

		if (isset($this->request->post['ean'])) {
			$data['ean'] = $this->request->post['ean'];
		} elseif (!empty($product_info)) {
			$data['ean'] = $product_info['ean'];
		} else {
			$data['ean'] = '';
		}

		if (isset($this->request->post['jan'])) {
			$data['jan'] = $this->request->post['jan'];
		} elseif (!empty($product_info)) {
			$data['jan'] = $product_info['jan'];
		} else {
			$data['jan'] = '';
		}

		if (isset($this->request->post['isbn'])) {
			$data['isbn'] = $this->request->post['isbn'];
		} elseif (!empty($product_info)) {
			$data['isbn'] = $product_info['isbn'];
		} else {
			$data['isbn'] = '';
		}

		if (isset($this->request->post['mpn'])) {
			$data['mpn'] = $this->request->post['mpn'];
		} elseif (!empty($product_info)) {
			$data['mpn'] = $product_info['mpn'];
		} else {
			$data['mpn'] = '';
		}

		if (isset($this->request->post['location'])) {
			$data['location'] = $this->request->post['location'];
		} elseif (!empty($product_info)) {
			$data['location'] = $product_info['location'];
		} else {
			$data['location'] = '';
		}

		$this->load->model('setting/store');

		$data['stores'] = $this->model_setting_store->getStores();

		if (isset($this->request->post['product_store'])) {
			$data['product_store'] = $this->request->post['product_store'];
		} elseif (isset($this->request->get['product_id'])) {
			$data['product_store'] = $this->model_catalog_product->getProductStores($this->request->get['product_id']);
		} else {
			$data['product_store'] = array(0);
		}

		if (isset($this->request->post['keyword'])) {
			$data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($product_info)) {
			$data['keyword'] = $product_info['keyword'];
		} else {
			$data['keyword'] = '';
		}

		if (isset($this->request->post['shipping'])) {
			$data['shipping'] = $this->request->post['shipping'];
		} elseif (!empty($product_info)) {
			$data['shipping'] = $product_info['shipping'];
		} else {
			$data['shipping'] = 1;
		}

		if (isset($this->request->post['price'])) {
			$data['price'] = $this->request->post['price'];
		} elseif (!empty($product_info)) {
			$data['price'] = $product_info['price'];
		} else {
			$data['price'] = '';
		}

		$this->load->model('catalog/recurring');

		$data['recurrings'] = $this->model_catalog_recurring->getRecurrings();

		if (isset($this->request->post['product_recurrings'])) {
			$data['product_recurrings'] = $this->request->post['product_recurrings'];
		} elseif (!empty($product_info)) {
			$data['product_recurrings'] = $this->model_catalog_product->getRecurrings($product_info['product_id']);
		} else {
			$data['product_recurrings'] = array();
		}

		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['tax_class_id'])) {
			$data['tax_class_id'] = $this->request->post['tax_class_id'];
		} elseif (!empty($product_info)) {
			$data['tax_class_id'] = $product_info['tax_class_id'];
		} else {
			$data['tax_class_id'] = 0;
		}

		if (isset($this->request->post['date_available'])) {
			$data['date_available'] = $this->request->post['date_available'];
		} elseif (!empty($product_info)) {
			$data['date_available'] = ($product_info['date_available'] != '0000-00-00') ? $product_info['date_available'] : '';
		} else {
			$data['date_available'] = date('Y-m-d');
		}
		
		if (isset($this->request->post['frontend_date_available'])) {
			$data['frontend_date_available'] = $this->request->post['frontend_date_available'];
		} elseif (!empty($product_info)) {
			$data['frontend_date_available'] = ($product_info['frontend_date_available'] != '0000-00-00') ? $product_info['frontend_date_available'] : '';
		} else {
			$data['frontend_date_available'] = date('Y-m-d');
		}
		
		if (isset($this->request->post['date_sold_out'])) {
			$data['date_sold_out'] = $this->request->post['date_sold_out'];
		} elseif (!empty($product_info)) {
			$data['date_sold_out'] = ($product_info['date_sold_out'] != '0000-00-00') ? $product_info['date_sold_out'] : '';
		} else {
			$data['date_sold_out'] = date('Y-m-d');
		}
		
		if (isset($this->request->post['date_ordered'])) {
			$data['date_ordered'] = $this->request->post['date_ordered'];
		} elseif (!empty($product_info)) {
			$data['date_ordered'] = ($product_info['date_ordered'] != '0000-00-00') ? $product_info['date_ordered'] : '';
		} else {
			$data['date_ordered'] = date('Y-m-d');
		}
		
		if (isset($this->request->post['estimate_deliver_time'])) {
			$data['estimate_deliver_time'] = $this->request->post['estimate_deliver_time'];
		} elseif (!empty($product_info)) {
			$data['estimate_deliver_time'] = $product_info['estimate_deliver_time'];
		} else {
			$data['estimate_deliver_time'] = 0;
		}

		if (isset($this->request->post['quantity'])) {
			$data['quantity'] = $this->request->post['quantity'];
		} elseif (!empty($product_info)) {
			$data['quantity'] = $product_info['quantity'];
		} else {
			$data['quantity'] = 1;
		}

		if (isset($this->request->post['minimum'])) {
			$data['minimum'] = $this->request->post['minimum'];
		} elseif (!empty($product_info)) {
			$data['minimum'] = $product_info['minimum'];
		} else {
			$data['minimum'] = 1;
		}
		
		if (isset($this->request->post['minimum_amount'])) {
			$data['minimum_amount'] = $this->request->post['minimum_amount'];
		} elseif (!empty($product_info)) {
			$data['minimum_amount'] = $product_info['minimum_amount'];
		} else {
			$data['minimum_amount'] = '';
		}

		if (isset($this->request->post['subtract'])) {
			$data['subtract'] = $this->request->post['subtract'];
		} elseif (!empty($product_info)) {
			$data['subtract'] = $product_info['subtract'];
		} else {
			$data['subtract'] = 1;
		}

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($product_info)) {
			$data['sort_order'] = $product_info['sort_order'];
		} else {
			$data['sort_order'] = 1;
		}

		if (isset($this->request->post['unit_singular'])) {
			$data['unit_singular'] = $this->request->post['unit_singular'];
		} elseif (!empty($product_info)) {
			$data['unit_singular'] = $product_info['unit_singular'];
		} else {
			$data['unit_singular'] = "";
		}

		if (isset($this->request->post['unit_plural'])) {
			$data['unit_plural'] = $this->request->post['unit_plural'];
		} elseif (!empty($product_info)) {
			$data['unit_plural'] = $product_info['unit_plural'];
		} else {
			$data['unit_plural'] = "";
		}

		if (isset($this->request->post['unique_option_price'])) {
			$data['unique_option_price'] = $this->request->post['unique_option_price'];
		} elseif (!empty($product_info)) {
			$data['unique_option_price'] = $product_info['unique_option_price'];
		} else {
			$data['unique_option_price'] = "";
		}

		if (isset($this->request->post['unique_price_discount'])) {
			$data['unique_price_discount'] = $this->request->post['unique_price_discount'];
		} elseif (!empty($product_info)) {
			$data['unique_price_discount'] = $product_info['unique_price_discount'];
		} else {
			$data['unique_price_discount'] = "";
		}

		if (isset($this->request->post['labour_cost'])) {
			$data['labour_cost'] = $this->request->post['labour_cost'];
		} elseif (!empty($product_info)) {
			$data['labour_cost'] = $product_info['labour_cost'];
		} else {
			$data['labour_cost'] = "";
		}

		if (isset($this->request->post['default_vendor_unit'])) {
			$data['default_vendor_unit'] = $this->request->post['default_vendor_unit'];
		} elseif (!empty($product_info)) {
			$data['default_vendor_unit'] = $product_info['default_vendor_unit'];
		} else {
			$data['default_vendor_unit'] = "";
		}

		if (isset($this->request->post['product_label_text_1'])) {
			$data['product_label_text_1'] = $this->request->post['product_label_text_1'];
		} elseif (!empty($product_info)) {
			$data['product_label_text_1'] = $product_info['product_label_text_1'];
		} else {
			$data['product_label_text_1'] = "";
		}

		if (isset($this->request->post['product_label_text_2'])) {
			$data['product_label_text_2'] = $this->request->post['product_label_text_2'];
		} elseif (!empty($product_info)) {
			$data['product_label_text_2'] = $product_info['product_label_text_2'];
		} else {
			$data['product_label_text_2'] = "";
		}

		$this->load->model('localisation/stock_status');

		$data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();

		if (isset($this->request->post['stock_status_id'])) {
			$data['stock_status_id'] = $this->request->post['stock_status_id'];
		} elseif (!empty($product_info)) {
			$data['stock_status_id'] = $product_info['stock_status_id'];
		} else {
			$data['stock_status_id'] = 0;
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($product_info)) {
			$data['status'] = $product_info['status'];
		} else {
			$data['status'] = true;
		}
		if (isset($this->request->post['pos_status'])) {
			$data['pos_status'] = $this->request->post['pos_status'];
		} elseif (!empty($product_info)) {
			$data['pos_status'] = $product_info['pos_status'];
		} else {
			$data['pos_status'] = true;
		}

		if (isset($this->request->post['show_product_label_1'])) {
			$data['show_product_label_1'] = $this->request->post['show_product_label_1'];
		} elseif (!empty($product_info)) {
			$data['show_product_label_1'] = $product_info['show_product_label_1'];
		} else {
			$data['show_product_label_1'] = false;
		}

		if (isset($this->request->post['show_product_label_2'])) {
			$data['show_product_label_2'] = $this->request->post['show_product_label_2'];
		} elseif (!empty($product_info)) {
			$data['show_product_label_2'] = $product_info['show_product_label_2'];
		} else {
			$data['show_product_label_2'] = false;
		}


		if (isset($this->request->post['is_gift_product'])) {
			$data['is_gift_product'] = $this->request->post['is_gift_product'];
		} elseif (!empty($product_info)) {
			$data['is_gift_product'] = $product_info['is_gift_product'];
		} else {
			$data['is_gift_product'] = false;
		}

		if (isset($this->request->post['weight'])) {
			$data['weight'] = $this->request->post['weight'];
		} elseif (!empty($product_info)) {
			$data['weight'] = $product_info['weight'];
		} else {
			$data['weight'] = '';
		}

		$this->load->model('localisation/weight_class');

		$data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

		if (isset($this->request->post['weight_class_id'])) {
			$data['weight_class_id'] = $this->request->post['weight_class_id'];
		} elseif (!empty($product_info)) {
			$data['weight_class_id'] = $product_info['weight_class_id'];
		} else {
			$data['weight_class_id'] = $this->config->get('config_weight_class_id');
		}

		if (isset($this->request->post['length'])) {
			$data['length'] = $this->request->post['length'];
		} elseif (!empty($product_info)) {
			$data['length'] = $product_info['length'];
		} else {
			$data['length'] = '';
		}

		if (isset($this->request->post['width'])) {
			$data['width'] = $this->request->post['width'];
		} elseif (!empty($product_info)) {
			$data['width'] = $product_info['width'];
		} else {
			$data['width'] = '';
		}

		if (isset($this->request->post['height'])) {
			$data['height'] = $this->request->post['height'];
		} elseif (!empty($product_info)) {
			$data['height'] = $product_info['height'];
		} else {
			$data['height'] = '';
		}

		$this->load->model('localisation/length_class');

		$data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();

		if (isset($this->request->post['length_class_id'])) {
			$data['length_class_id'] = $this->request->post['length_class_id'];
		} elseif (!empty($product_info)) {
			$data['length_class_id'] = $product_info['length_class_id'];
		} else {
			$data['length_class_id'] = $this->config->get('config_length_class_id');
		}

		$this->load->model('catalog/manufacturer');

		if (isset($this->request->post['manufacturer_id'])) {
			$data['manufacturer_id'] = $this->request->post['manufacturer_id'];
		} elseif (!empty($product_info)) {
			$data['manufacturer_id'] = $product_info['manufacturer_id'];
		} else {
			$data['manufacturer_id'] = 0;
		}

		if (isset($this->request->post['manufacturer'])) {
			$data['manufacturer'] = $this->request->post['manufacturer'];
		} elseif (!empty($product_info)) {
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);

			if ($manufacturer_info) {
				$data['manufacturer'] = $manufacturer_info['name'];
			} else {
				$data['manufacturer'] = '';
			}
		} else {
			$data['manufacturer'] = '';
		}

		// Categories
		$this->load->model('catalog/category');

		if (isset($this->request->post['product_category'])) {
			$categories = $this->request->post['product_category'];
		} elseif (isset($this->request->get['product_id'])) {
			$categories = $this->model_catalog_product->getProductCategories($this->request->get['product_id']);
		} else {
			$categories = array();
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

		// Filters
		$this->load->model('catalog/filter');

		if (isset($this->request->post['product_filter'])) {
			$filters = $this->request->post['product_filter'];
		} elseif (isset($this->request->get['product_id'])) {
			$filters = $this->model_catalog_product->getProductFilters($this->request->get['product_id']);
		} else {
			$filters = array();
		}

		$data['product_filters'] = array();

		foreach ($filters as $filter_id) {
			$filter_info = $this->model_catalog_filter->getFilter($filter_id);

			if ($filter_info) {
				$data['product_filters'][] = array(
					'filter_id' => $filter_info['filter_id'],
					'name'      => $filter_info['group'] . ' &gt; ' . $filter_info['name']
				);
			}
		}

		// Attributes
		$this->load->model('catalog/attribute');

		if (isset($this->request->post['product_attribute'])) {
			$product_attributes = $this->request->post['product_attribute'];
		} elseif (isset($this->request->get['product_id'])) {
			$product_attributes = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);
		} else {
			$product_attributes = array();
		}

		$data['product_attributes'] = array();

		foreach ($product_attributes as $product_attribute) {
			$attribute_info = $this->model_catalog_attribute->getAttribute($product_attribute['attribute_id']);

			if ($attribute_info) {
				$data['product_attributes'][] = array(
					'attribute_id'                  => $product_attribute['attribute_id'],
					'name'                          => $attribute_info['name'],
					'product_attribute_description' => $product_attribute['product_attribute_description']
				);
			}
		}
		
		if (isset($this->request->post['product_article'])) {
			$product_articles = $this->request->post['product_article'];
		} elseif (isset($this->request->get['product_id'])) {
			$product_articles = $this->model_catalog_product->getProductArticles($this->request->get['product_id']);
		} else {
			$product_articles = array();
		}

		$data['product_articles'] = array();

		foreach ($product_articles as $product_article) {
			

			if ($product_article) {
				$data['product_articles'][] = array(
					'article_id'                  =>   $product_article['article_id'],
					'name'                        =>   $product_article['name']
					
				);
			}
		}
		
		
		if (isset($this->request->post['estimatedays'])) {
			$data['estimatedays'] = $this->request->post['estimatedays'];
		} elseif (isset($this->request->get['product_id'])) {
			$data['estimatedays'] = $this->model_catalog_product->getProductEstimateDays($this->request->get['product_id']);
		} else {
			$data['estimatedays'] = array();
		}
		//echo '<pre>';print_r($estimatedays);echo 'yes';exit;
		

		// Options
		$this->load->model('catalog/option');

		if (isset($this->request->post['product_option'])) {
			$product_options = $this->request->post['product_option'];
		} elseif (isset($this->request->get['product_id'])) {
			$product_options = $this->model_catalog_product->getProductOptions($this->request->get['product_id']);
		} else {
			$product_options = array();
		}

		$data['product_options'] = array();

		foreach ($product_options as $product_option) {
			$product_option_value_data = array();

			if (isset($product_option['product_option_value'])) {
				foreach ($product_option['product_option_value'] as $product_option_value) {
					$product_option_value_data[] = array(
						'product_option_value_id' => $product_option_value['product_option_value_id'],
						'option_value_id'         => $product_option_value['option_value_id'],
						'quantity'                => $product_option_value['quantity'],
						'subtract'                => $product_option_value['subtract'],
						'price'                   => $product_option_value['price'],
						'price_prefix'            => $product_option_value['price_prefix'],
						'points'                  => $product_option_value['points'],
						'points_prefix'           => $product_option_value['points_prefix'],
						'weight'                  => $product_option_value['weight'],
						'weight_prefix'           => $product_option_value['weight_prefix']
					);
				}
			}

			$data['product_options'][] = array(
				'product_option_id'    => $product_option['product_option_id'],
				'product_option_value' => $product_option_value_data,
				'option_id'            => $product_option['option_id'],
				'name'                 => $product_option['name'],
				'type'                 => $product_option['type'],
				'value'                => isset($product_option['value']) ? $product_option['value'] : '',
				'required'             => $product_option['required']
			);
		}

		$data['option_values'] = array();

		foreach ($data['product_options'] as $product_option) {
			if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
				if (!isset($data['option_values'][$product_option['option_id']])) {
					$data['option_values'][$product_option['option_id']] = $this->model_catalog_option->getOptionValues($product_option['option_id']);
				}
			}
		}

		$this->load->model('customer/customer_group');

		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		if (isset($this->request->post['product_discount'])) {
			$product_discounts = $this->request->post['product_discount'];
		} elseif (isset($this->request->get['product_id'])) {
			$product_discounts = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);
		} else {
			$product_discounts = array();
		}

		$data['product_discounts'] = array();

		foreach ($product_discounts as $product_discount) {
			$data['product_discounts'][] = array(
				'customer_group_id' => $product_discount['customer_group_id'],
				'quantity'          => $product_discount['quantity'],
				'priority'          => $product_discount['priority'],
				'price'             => $product_discount['price'],
				'discount_percent'             => $product_discount['discount_percent'],
				'date_start'        => ($product_discount['date_start'] != '0000-00-00') ? $product_discount['date_start'] : '',
				'date_end'          => ($product_discount['date_end'] != '0000-00-00') ? $product_discount['date_end'] : ''
			);
		}

		if (isset($this->request->post['product_special'])) {
			$product_specials = $this->request->post['product_special'];
		} elseif (isset($this->request->get['product_id'])) {
			$product_specials = $this->model_catalog_product->getProductSpecials($this->request->get['product_id']);
		} else {
			$product_specials = array();
		}

		$data['product_specials'] = array();

		foreach ($product_specials as $product_special) {
			$data['product_specials'][] = array(
				'customer_group_id' => $product_special['customer_group_id'],
				'priority'          => $product_special['priority'],
				'price'             => $product_special['price'],
				'date_start'        => ($product_special['date_start'] != '0000-00-00') ? $product_special['date_start'] : '',
				'date_end'          => ($product_special['date_end'] != '0000-00-00') ? $product_special['date_end'] :  ''
			);
		}

		// Images
		if (isset($this->request->post['product_image'])) {
			$product_images = $this->request->post['product_image'];
		} elseif (isset($this->request->get['product_id'])) {
			$product_images = $this->model_catalog_product->getProductImages($this->request->get['product_id']);
		} else {
			$product_images = array();
		}

		$data['product_images'] = array();

		foreach ($product_images as $product_image) {
			if (is_file(DIR_IMAGE . $product_image['image'])) {
				$image = $product_image['image'];
				$thumb = $product_image['image'];
			} else {
				$image = '';
				$thumb = 'no_image.png';
			}

			$data['product_images'][] = array(
				'image'      => $image,
				'thumb'      => $this->model_tool_image->resize($thumb, 100, 100),
				'sort_order' => $product_image['sort_order']
			);
		}

		$data['product_vlink'] = array_map(function($l){return urldecode($l);},explode(',',$product_info['video_link']));
		//echo "<pre>";print_r($data['product_vlink']);echo "</pre>";
		$data['product_plink'] = urldecode($product_info['video_p_link']);
		$data['product_qrcode'] = base64_decode($product_info['qrcode']);

		$this->load->model('qrlabel/qrlabel');
		$data['product_qrcode_fields'] = array();
		$data['qrcode_fields'] = array();

		$data['qrcode_row'] = $this->model_qrlabel_qrlabel->getProductQrFieldsTotal($product_info['product_id']);
		$data['product_qrcode_fields'] = $this->model_qrlabel_qrlabel->getProductQrFields($product_info['product_id']);
		$data['qrcode_fields'] = $this->model_qrlabel_qrlabel->getQrFields();
		$data['qrcode'] = $this->model_qrlabel_qrlabel->generateQRCode($product_info['product_id']);
		$data['qrcode_cols'] = $this->model_qrlabel_qrlabel->getQrFields(1);

		// Downloads
		$this->load->model('catalog/download');

		if (isset($this->request->post['product_download'])) {
			$product_downloads = $this->request->post['product_download'];
		} elseif (isset($this->request->get['product_id'])) {
			$product_downloads = $this->model_catalog_product->getProductDownloads($this->request->get['product_id']);
		} else {
			$product_downloads = array();
		}

		$data['product_downloads'] = array();

		foreach ($product_downloads as $download_id) {
			$download_info = $this->model_catalog_download->getDownload($download_id);

			if ($download_info) {
				$data['product_downloads'][] = array(
					'download_id' => $download_info['download_id'],
					'name'        => $download_info['name']
				);
			}
		}

		if (isset($this->request->post['product_related'])) {
			$products = $this->request->post['product_related'];
		} elseif (isset($this->request->get['product_id'])) {
			$products = $this->model_catalog_product->getProductRelated($this->request->get['product_id']);
		} else {
			$products = array();
		}

		$data['product_relateds'] = array();

		foreach ($products as $product_id) {
			$related_info = $this->model_catalog_product->getProduct($product_id);

			if ($related_info) {
				$data['product_relateds'][] = array(
					'product_id' => $related_info['product_id'],
					'name'       => $related_info['name']
				);
			}
		}

		if (isset($this->request->post['product_wwell'])) {
			$wproducts = $this->request->post['product_wwell'];
		} elseif (isset($this->request->get['product_id'])) {
			$wproducts = $this->model_catalog_product->getProductRelated($this->request->get['product_id'],1);
		} else {
			$wproducts = array();
		}

		$data['product_wwell'] = array();

		foreach ($wproducts as $product_id) {
			$wwell_info = $this->model_catalog_product->getProduct($product_id);

			if ($wwell_info) {
				$data['product_wwell'][] = array(
					'product_id' => $wwell_info['product_id'],
					'name'       => $wwell_info['name']
				);
			}
		}

		if (isset($this->request->post['points'])) {
			$data['points'] = $this->request->post['points'];
		} elseif (!empty($product_info)) {
			$data['points'] = $product_info['points'];
		} else {
			$data['points'] = '';
		}

		if (isset($this->request->post['product_reward'])) {
			$data['product_reward'] = $this->request->post['product_reward'];
		} elseif (isset($this->request->get['product_id'])) {
			$data['product_reward'] = $this->model_catalog_product->getProductRewards($this->request->get['product_id']);
		} else {
			$data['product_reward'] = array();
		}

		if (isset($this->request->post['product_layout'])) {
			$data['product_layout'] = $this->request->post['product_layout'];
		} elseif (isset($this->request->get['product_id'])) {
			$data['product_layout'] = $this->model_catalog_product->getProductLayouts($this->request->get['product_id']);
		} else {
			$data['product_layout'] = array();
		}

		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		if (isset($this->request->post['multiplier_name'])) {
			$data['multiplier_name'] = $this->request->post['multiplier_name'];
		} elseif (!empty($product_info)) {
			$data['multiplier_name'] = $product_info['multiplier_name'];
		} else {
			$data['multiplier_name'] = "";
		}

		if (isset($this->request->post['multiplier_value'])) {
			$data['multiplier_value'] = $this->request->post['multiplier_value'];
		} elseif (!empty($product_info)) {
			$data['multiplier_value'] = $product_info['multiplier_value'];
		} else {
			$data['multiplier_value'] = "";
		}

		$this->response->setOutput($this->load->view('catalog/product_form.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/product')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['product_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 255)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}

			if ((utf8_strlen($value['meta_title']) < 3) || (utf8_strlen($value['meta_title']) > 255)) {
				$this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
			}
		}

		if ((utf8_strlen($this->request->post['model']) < 1) || (utf8_strlen($this->request->post['model']) > 64)) {
			$this->error['model'] = $this->language->get('error_model');
		}

		if (utf8_strlen($this->request->post['keyword']) > 0) {
			$this->load->model('catalog/url_alias');

			$url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['keyword']);

			if ($url_alias_info && isset($this->request->get['product_id']) && $url_alias_info['query'] != 'product_id=' . $this->request->get['product_id']) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}

			if ($url_alias_info && !isset($this->request->get['product_id'])) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/product')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validateCopy() {
		if (!$this->user->hasPermission('modify', 'catalog/product')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}


	public function exportmissingimage() {

		$this->load->model('catalog/product');
		$products = $this->model_catalog_product->getMissingImageProducts();
		$file = 'missing_image_products.csv';
		$fp = fopen(DIR_DOWNLOAD . $file, 'w');
		$heading = array("Product ID","SKU","Model","Product Name","Image Issue");
		fputcsv($fp, $heading);

		foreach( $products as $product )
		{
			if ( empty( $product['image'] ) )
			{
				$csv_data = array($product['product_id'],$product['sku'],$product['model'],$product['name'],"Image is not assigned to this product");
				fputcsv($fp, $csv_data);
			}
			elseif (!is_file(DIR_IMAGE . $product['image'])) {
				$csv_data = array($product['product_id'],$product['sku'],$product['model'],$product['name'],"Image file does not exists on server.");
				fputcsv($fp, $csv_data);
			} 
		}

		fclose($fp);

		$csv_file = DIR_DOWNLOAD . $file; //path to the file on disk
		$json = array();
		if( !file_exists($csv_file) ) 
		{
			$json['error'] = "File not found";
		} else {
			$json['success'] = $file;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

	}

	public function autocomplete1(){
		$json = array();
		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model'])) {
			$this->load->model('catalog/product');

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['filter_model'])) {
				$filter_model = $this->request->get['filter_model'];
			} else {
				$filter_model = '';
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 5;
			}

			if(preg_match('@sale/order/update@', $_SERVER['HTTP_REFERER'])) {
                  $pgvisibility = 1;
            } else {
                   $pgvisibility = 0;
            }	
			$filter_data = array(
				'filter_name'  => $filter_name,
				'filter_model' => $filter_model,
				'start'        => 0,
				'limit'        => $limit,
                'pgvisibility' => $pgvisibility
			);

			$results = $this->model_catalog_product->getProductsForAC($filter_data);
			//print_r($results);
			//die();

			foreach ($results as $result) {
				$json[] = array(
					'product_id' => $result['product_id'],
					'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}

		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	public function autocompletegiveaway(){
		$json = array();
		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model'])) {
			$this->load->model('catalog/product');

			if (isset($this->request->get['filter_model'])) {
				$filter_model = $this->request->get['filter_model'];
			} else {
				$filter_model = '';
			}

			$limit = 5;
	
			$filter_data = array(
				'filter_model' => $filter_model,
				'start'        => 0,
				'limit'        => $limit
			);

			$results = $this->model_catalog_product->getProductsForGiveaway($filter_data);
			foreach ($results as $result) {
				$json[] = array(
					'product_id' => $result['product_id'],
					'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}

		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	public function autocomplete() {
		//die("ooo");
		$json = array();

		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model'])) {
			$this->load->model('catalog/product');
			$this->load->model('catalog/option');

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['filter_model'])) {
				$filter_model = $this->request->get['filter_model'];
			} else {
				$filter_model = '';
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 5;
			}
			if(preg_match('@sale/order/update@', $_SERVER['HTTP_REFERER'])) {
                  $pgvisibility = 1;
            } else {
                   $pgvisibility = 0;
            }	
			$filter_data = array(
				'filter_name'  => $filter_name,
				'filter_model' => $filter_model,
				'start'        => 0,
				'limit'        => $limit,
                'pgvisibility' => $pgvisibility
			);

			$results = $this->model_catalog_product->getProducts($filter_data);
			//die("O");
			
			foreach ($results as $result) {
				$option_data = array();

				$product_options = $this->model_catalog_product->getProductOptions($result['product_id']);
				$this->load->model('catalog/unit_conversion');
               // $unit_data   = $this->model_catalog_unit_conversion->getProductUnits($result['product_id']);
				//die("KK");
				$groupdata = $this->getgroupdata($result['product_id']);

				//print_r($groupdata);
				if(isset($groupdata['unit_datas'])){
					$unit_data   = $groupdata['unit_datas'];
				}else{
					$unit_data   = '';
				}
				
				if(!isset($groupdata['option_data'])){
					foreach ($product_options as $product_option) {
					$option_info = $this->model_catalog_option->getOption($product_option['option_id']);

					if ($option_info) {
						$product_option_value_data = array();

						foreach ($product_option['product_option_value'] as $product_option_value) {
							$option_value_info = $this->model_catalog_option->getOptionValue($product_option_value['option_value_id']);

							if ($option_value_info) {
								$product_option_value_data[] = array(
									'product_option_value_id' => $product_option_value['product_option_value_id'],
									'option_value_id'         => $product_option_value['option_value_id'],
									'name'                    => $option_value_info['name'],
									'price'                   => (float)$product_option_value['price'] ? $this->currency->format($product_option_value['price'], $this->config->get('config_currency')) : false,
									'price_prefix'            => $product_option_value['price_prefix']
								);
							}
						}

						$option_data[] = array(
							'product_option_id'    => $product_option['product_option_id'],
							'product_option_value' => $product_option_value_data,
							'option_id'            => $product_option['option_id'],
							'name'                 => $option_info['name'],
							'type'                 => $option_info['type'],
							'value'                => $product_option['value'],
							'required'             => $product_option['required']
						);
					}
				}
				}
				else{
					$option_data = $groupdata['option_data'];
				}
				$json[] = array(
					'product_id' => $result['product_id'],
					//'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'name'		 => $result['name'],
					'model'      => $result['model'],
					'option'     => $option_data,
					'unit'       => (isset($unit_data) ? $unit_data : 0),
					'groupdata'  => (isset($groupdata) ? $groupdata : ''),
					'price'      => $result['price']
				);
			}
			//die("KK");
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function getgroupdata($product_id) {
		$this->request->get['product_id'] = $product_id;
		$json = array();
		$option_data = array();
		
		$this->load->model('catalog/product');
		$this->load->model('pos/product_grouped');
		$this->load->model('pos/seo_url');
		$this->load->model('pos/pos');
		$this->load->model('catalog/option');
		
		// recalculate the total
		
		//echo $product_id = $this->request->get['product_id'];
		//exit;
		$group_product_id = $this->model_pos_seo_url->isProductGrouped($product_id);
		$new_product_id=0;
		$gp_master_q = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product_grouped_type WHERE product_id = '" . (int)$group_product_id . "'");
			
			if($gp_master_q->num_rows){
				$new_product_id = $gp_master_q->row['product_id'];
			}else{
				$gp_slave_q = $this->db->query("SELECT pg.product_id FROM ".DB_PREFIX."product_grouped pg LEFT JOIN ".DB_PREFIX."product p ON (pg.grouped_id=p.product_id) WHERE p.model != 'grouped' AND p.pgvisibility!='1' AND p.status='1' AND pg.grouped_id='".(int)$group_product_id."' LIMIT 1");
				if($gp_slave_q->num_rows){
					$new_product_id = $gp_slave_q->row['product_id'];
				}
			}
        //$product 
		$gp_tpl_q = $this->model_pos_product_grouped->getProductGroupedType($product_id);
		if (!$gp_tpl_q) {
			$data['text_groupby'] = $gp_tpl_q['pg_groupby'];
        	$product_grouped = array();
        	$product_grouped_name = $this->model_pos_pos->getGroupedProductName($product_id);
			$data['groupbyname'] = $product_grouped_name;
		}else{
			$product_info = false;	
		}
		$gruppi = $this->model_pos_product_grouped->getGrouped($group_product_id);

		//$product_options = $this->model_catalog_product->getProductOptions($this->request->get['product_id']);
		  if (!empty($gruppi)) {
			   $group_indicator_id = $this->model_pos_product_grouped->getGroupIndicator($group_product_id);
            $data['group_indicator_id'] = $group_indicator_id;
            /**
             * REQUESTED PRODUCT CODE
             */
            if ($group_product_id != 0) {
                //check if requested "gp_product_id" is of same group
                $requested_product_data = $this->model_pos_product_grouped->getGroupedData($product_id, $group_indicator_id);
                $data['requested_product_data'] = $requested_product_data;
				//if requested "gp_product_id" is not of same group then unset get variable
                if ($requested_product_data === FALSE) {
                    $group_indicator_id = 0;
                }
            }
            /**
             * REQUESTED PRODUCT CODE
             */
			// print_r($gruppi);
            $requested_product_id = $gruppi[0]['grouped_id'];
			 $product_grouped = array();
            foreach ($gruppi as $groups) {
                $product_name = $this->model_pos_product_grouped->getGroupedProductName($groups['grouped_id']);
                $name = str_replace($product_grouped_name, '', $product_name);

                //REQUESTED PRODUCT CODE

                $requested_product = FALSE;

                if ($product_id !=0 && trim($name) == $requested_product_data['groupbyvalue']) {
                    $requested_product = TRUE;
                    $requested_product_id = $groups['grouped_id'];
                }

                //REQUESTED PRODUCT CODE END

                $product_grouped[] = array(
                    'product_id' => $groups['grouped_id'],
                    'product_name' => $name,
                    'is_requested_product' => $requested_product //REQUESTED PRODUCT CODE
                );
            }
			
           // $product_id = $requested_product_id;
            $product_info = $this->model_pos_product_grouped->getProduct($product_id);
			 /*if ($product_info['discounted_price']) {
                    $discount_percent = $this->cart->calcMetalTypeDiscount($product_info['discounted_price'], $product_info['orignial_price']);
                } else {*/
                    $discount_percent = 1;
              //  }

                $data['old_price'] = $product_info['orignial_price'];

                $data['options'] = array();

                //$data['unit_conversion_help'] = $this->model_pos_product_grouped->getConversionHelp($product_info['product_id']);
                $formula = FALSE;

                $data['base_price'] = $product_info['orignial_price'];
			$this->load->model('catalog/unit_conversion');
               $data['unit_datas'] = $this->model_catalog_unit_conversion->getProductUnits($product_info['product_id']);
                
               // $unit_data   = $this->model_catalog_unit_conversion->getProductUnits($result['product_id']);            
                        

                foreach ($this->model_pos_pos->getProductOptions($product_id) as $option) {
                    if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') {
                        $option_value_data = array();
                        $size = 0;
                        //REQUESTED PRODUCT CODE
                        $is_requested_option = FALSE;
                        if ($product_id !=0 && $requested_product_data['option_count'] > 0) {
                            $requested_option_exists = array_search(trim($option['name']), $requested_product_data);
                            if ($requested_option_exists !== FALSE) {
                                $is_requested_option = TRUE;
                            }
                        }
                        //REQUESTED PRODUCT CODE ENDS
                        foreach ($option['option_value'] as $option_value) {
                            //REQUESTED PRODUCT CODE
                            $is_requested_option_value = FALSE;
                            if ($is_requested_option === TRUE && $option_value['name'] == $requested_product_data['optionvalue' . filter_var($requested_option_exists, FILTER_SANITIZE_NUMBER_INT)]) {
                                $is_requested_option_value = TRUE;
                            }
                            //REQUESTED PRODUCT CODE ENDS
                            if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
                               /* $option_unformated_price = $this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
                                if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float) $option_value['price']) {
                                    $price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                                } else {
                                    $price = false;
                                }*/



                                $option_value_data[] = array(
                                    'product_option_value_id' => $option_value['product_option_value_id'],
                                    'quantity' => $option_value['quantity'],
                                    'option_value_id' => $option_value['option_value_id'],
                                    'name' => $option_value['name'],
                                    'price' => $option_value['price'],
                                    'price2' => $option_value['price'],
                                    'price_prefix' => $option_value['price_prefix'],
                                    'is_requested_option_value' => $is_requested_option_value//REQUESTED PRODUCT CODE
                                );
                            }
                        }

                        $option_data[] = array(

                            'product_option_id' => $option['product_option_id'],
                            'option_id' => $option['option_id'],
                            'metal_type' => $size,
                            'name' => $option['name'],
                            'type' => $option['type'],
                            'product_option_value' => $option_value_data,
                            'required' => $option['required']
                        );
                    } elseif ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {

                        $option_data[] = array(
                            'product_option_id' => $option['product_option_id'],
                            'option_id' => $option['option_id'],
                            'name' => $option['name'],
                            'type' => $option['type'],
                            'product_option_value' => $option['option_value'],
                            'required' => $option['required']
                        );
                    }
                }
				
		  }else{
		 	foreach ($this->model_pos_pos->getProductOptions($this->request->get['product_id']) as $option) {
                if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') {
                    $option_value_data = array();
                    $size = 0;

                    foreach ($option['option_value'] as $option_value) {
                        if (!$option_value['subtract'] || $option_value['subtract']) {

                            $option_unformated_price = $this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
                            if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float) $option_value['price']) {
                                $price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                            } else {
                                $price = false;
                            }
                            $option_value_data[] = array(

								'default' => $option_value['default'], //Q: Default Product Option
			
                                'product_option_value_id' => $option_value['product_option_value_id'],
								'quantity'         => $option_value['quantity'],
                                'option_value_id' => $option_value['option_value_id'],
                                'name' => $option_value['name'],
                                'price' => $price,
                                'price2' => $option_unformated_price,
                                'price_prefix' => $option_value['price_prefix']
                            );
                        }
                    }

                    $option_data[] = array(
                        'product_option_id' => $option['product_option_id'],
                        'option_id' => $option['option_id'],
                        'metal_type' => $size,
                        'name' => $option['name'],
                        'type' => $option['type'],
                        'product_option_value' => $option_value_data,
                        'required' => $option['required']
                    );
                } elseif ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {

                    $option_data[] = array(
                        'product_option_id' => $option['product_option_id'],
                        'option_id' => $option['option_id'],
                        'name' => $option['name'],
                        'type' => $option['type'],
                        'product_option_value' => $option['option_value'],
                        'required' => $option['required']
                    );
                }
            }
		  }
		/* foreach ($product_options as $product_option) {
			$option_info = $this->model_catalog_option->getOption($product_option['option_id']);
			
			if ($option_info) {				
				if ($option_info['type'] == 'select' || $option_info['type'] == 'radio' || $option_info['type'] == 'checkbox' || $option_info['type'] == 'image') {
					$option_value_data = array();
					
					foreach ($product_option['product_option_value'] as $product_option_value) {
						$option_value_name = '';
						if (version_compare(VERSION, '1.5.5', '<')) {
							$option_value_name = $product_option_value['name'];
						} else {
							$option_value_info = $this->model_catalog_option->getOptionValue($product_option_value['option_value_id']);
							if ($option_value_info) {
								$option_value_name = $option_value_info['name'];
							}
						}
				
						$option_value_data[] = array(
							'default' => $product_option_value['default'], //Q: Default Product Option
							'product_option_value_id' => $product_option_value['product_option_value_id'],
							'option_value_id'         => $product_option_value['option_value_id'],
							'name'                    => $option_value_name,
							'price'                   => (float)$product_option_value['price'] ? $this->currency->formatFront($product_option_value['price'], $this->config->get('config_currency')) : false,
							'price_prefix'            => $product_option_value['price_prefix']
						);
					}
				
					$option_data[] = array(
						'product_option_id' => $product_option['product_option_id'],
						'option_id'         => $product_option['option_id'],
						'name'              => $option_info['name'],
						'type'              => $option_info['type'],
						'option_value'      => $option_value_data,
						'required'          => $product_option['required']
					);	
				} elseif ($option_info['type'] != 'file') {
					$option_data[] = array(
						'product_option_id' => $product_option['product_option_id'],
						'option_id'         => $product_option['option_id'],
						'name'              => $option_info['name'],
						'type'              => $option_info['type'],
						'option_value'      => $product_option['value'],
						'required'          => $product_option['required']
					);				
				}
			}
		} */
		//$unit_data = $this->_getProductUnits($this->request->get['product_id']);
		$data['product_grouped'] = $product_grouped;
		$this->load->model('catalog/unit_conversion');
		$DefaultUnitdata   = $this->model_catalog_unit_conversion->getDefaultUnitDetails($product_id);
				
		if(isset( $data['unit_datas'])){
			$json['unit_datas'] =  $data['unit_datas'];
			$json['DefaultUnitdata'] = $DefaultUnitdata;
		}else{
			$json['unit_datas'] = '';
			$json['DefaultUnitdata'] = '';	
		}
		$json['discounts'] = $this->model_catalog_product->getProductDiscounts($product_id);
		$json['option_data'] = $option_data;
		$json['data'] = $data;
		//print_r($json);
		return $json;
	}
	
	public function getCombinationData() {
        $this->load->model('pos/product_grouped');
        $this->load->model('pos/product');
        $selected_options = array();
        
        if (isset($this->request->post['selChoice'])) {
            $selected_options = $this->request->post['selChoice'];
        }

        $groupbyvalue = $this->request->post['groupbyvalue'];
        $gi = $this->request->post['group_indicator'];
        $owhere = array(
            'groupindicator_id' => $gi,
            'groupbyvalue' => $groupbyvalue
        );
        foreach ($selected_options as $key => $op) {
            $o = explode('~', $op);
            $where = array();
            $k = $key + 1;
            $where['optionname' . $k] = $o[0];
            $where['optionvalue' . $k] = $o[1];
            $owhere = array_merge($owhere, $where);
        }

        $response = $this->model_pos_product_grouped->getCombination($owhere);
        // pr($response); die;
        if ($response->num_rows <= 0) {
            $data['error'] = "Product not found";
            echo json_encode($data);
            die;
        }
//        $sku=$response->row['sku'];
//        $product_id=$this->model_catalog_product_grouped->getproductIdBySku($sku);
        $product_id = $response->row['product_id'];
        
        $product_info = $this->model_pos_product->getProduct($product_id);
		$data['unit_dates_default'] =$this->model_pos_product->getDefaultUnitDetails($product_id);
		$data['DefaultUnitName'] =$this->model_pos_product->getDefaultUnitName($product_id);
        $data['name'] = $product_info['name'];
        $data['product_id'] = $product_id;
        $data['price'] = $this->currency->format($product_info['price']);
		if (isset($product_info['unit_singular'])) {
                $data['unit_singular'] = $product_info['unit_singular'];
            } else {
                $data['unit_singular'] = '';
            }
            if (isset($product_info['unit_plural'])) {
                $data['unit_plural'] = $product_info['unit_plural'];
            } else {
                $data['unit_plural'] = '';
            }
        /*$data['unit'] = $product_info['unit_singular'];
        if ($product_info['quantity'] <= 0) {
            $data['stock_status'] = "<span class='outofstock'></span>";
//            $data['stock_status'] = $product_info['stock_status'];
        } elseif ($this->config->get('config_stock_display')) {
            $data['stock_status'] = $product_info['quantity'];
        } else {
//            $data['stock_status'] = "In Stock";
            $data['stock_status'] = "<span class='inofstock'></span>";
        }*/
		$data['sku'] = $product_info['sku'];
        $data['model'] = $product_info['model'];
		$data['base_price'] = $product_info['orignial_price'];
        $data['unit_datas'] = $this->_getProductUnits($product_info['product_id']);
        //$data['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');
		
		/*$results = $this->model_catalog_product->getProductImages($product_id);
		
		$data['additional_images'] ='';
        foreach ($results as $result) {
			$popup = $this->model_tool_image->resize($result['image'], 500, 500);
			$thumb = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'));
			$data['additional_images'] .= '<a href="'.$popup.'" title="'.$product_info['name'].'" class="changeMainGroup"><img src="'.$thumb.'" title="'.$product_info['name'].'" /></a>';
			
		}
		
        if ($product_info['image']) {
            $data['image'] = $data['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
            $data['large_image'] = $data['thumb'] = $this->model_tool_image->resize($product_info['image'], 500, 500);
        } else {
            $data['image'] = $data['thumb'] = $this->model_tool_image->resize('data/product/no_product.jpg', $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
            $data['large_image'] = $data['thumb'] = $this->model_tool_image->resize('data/product/no_product.jpg', 500, 500);
        }
		*/
		
		

        $data['base_price'] = $product_info['orignial_price'];
        $data['discounted_price'] = $product_info['discounted_price'];
       // $data['reviews'] = $product_info['reviews'] . " Reviews";
        //$data['tab_review'] = "Reviews(" . $product_info['reviews'] . ")";
        //$data['rating'] = (int) $product_info['rating'];
        $data['sku'] = $product_info['sku'];
       // $data['attribute_html'] = $this->_getProductAtrriData($product_id);
       // $total_qas = $this->model_catalog_product->getTotalQAsByProductIdXML($product_id);

        //$data['text_tab_qa'] = "Q & A(" . $total_qas . ")";
        //$data['add_image_data'] = $this->_getAdditionalImageData($product_id, $data['name']);
        //$quantity = ($this->request->post['quantity'] != '') ? $this->request->post['quantity'] : 1;
        //$unit_conversion_text = $this->request->post['unit_conversion_text'];
        /*$unit_data = $this->_getProductUnitVariables($product_id, $unit_conversion_text);
        if (!empty($unit_data['unit_datas_html'])) {
            $data['product_unit_data_ajax'] = $unit_data['unit_datas_html'];
            $conversion_price = $unit_data['selected_unit_price'];
            if (!empty($data['product_unit_data_ajax']) && $conversion_price != '') {
                $data['price'] = $this->_calConvertPrice($data['base_price'], $quantity, $conversion_price, $product_id);
                $data['unit'] = $unit_conversion_text;
            } else {
                $data['price'] = $this->_getQuanityPrice($data['base_price'], $quantity, $product_id);
                $data['unit'] = ($quantity > 1) ? $product_info['unit_singular'] : $product_info['unit_plural'];
            }
        } else {
            $data['price'] = $data['discounted_price'] ? $this->currency->format($data['discounted_price']) : $data['price'];
        }
        if ($logged) {
            $data['get_product_discount'] = $this->_getproductdiscount($product_id, $product_info['tax_class_id'], $product_info['unit_plural']);
        }*/
		
        echo json_encode($data);
    }

}
