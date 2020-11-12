<?php
class ControllerPosExtendedProduct extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/product');
		$this->load->language('pos/extended_product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('pos/extended_product');

		$this->getList();
	}

	public function edit() {
		$this->load->language('catalog/product');
		$this->load->language('pos/extended_product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('pos/extended_product');
		$this->load->model('catalog/product');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_pos_extended_product->editProduct($this->request->get['product_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_abbreviation'])) {
				$url .= '&filter_abbreviation=' . urlencode(html_entity_decode($this->request->get['filter_abbreviation'], ENT_QUOTES, 'UTF-8'));
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

			$this->response->redirect($this->url->link('pos/extended_product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
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

		if (isset($this->request->get['filter_abbreviation'])) {
			$filter_abbreviation = $this->request->get['filter_abbreviation'];
		} else {
			$filter_abbreviation = null;
		}

		if (isset($this->request->get['filter_quick_sale'])) {
			$filter_quick_sale = $this->request->get['filter_quick_sale'];
		} else {
			$filter_quick_sale = null;
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

		if (isset($this->request->get['filter_abbreviation'])) {
			$url .= '&filter_abbreviation=' . urlencode(html_entity_decode($this->request->get['filter_abbreviation'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_quick_sale'])) {
			$url .= '&filter_quick_sale=' . urlencode(html_entity_decode($this->request->get['filter_quick_sale'], ENT_QUOTES, 'UTF-8'));
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
			'href' => $this->url->link('pos/extended_product', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['convert'] = $this->url->link('pos/extended_product/convert', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['products'] = array();

		$filter_data = array(
			'filter_name'	  		=> $filter_name,
			'filter_model'	  		=> $filter_model,
			'filter_abbreviation'	=> $filter_abbreviation,
			'filter_quick_sale'		=> $filter_quick_sale,
			'sort'            		=> $sort,
			'order'           		=> $order,
			'start'           		=> ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           		=> $this->config->get('config_limit_admin')
		);

		$this->load->model('tool/image');

		$product_total = $this->model_pos_extended_product->getTotalProducts($filter_data);

		$results = $this->model_pos_extended_product->getProducts($filter_data);

		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 40, 40);
			}
			
			$sn_info = $this->model_pos_extended_product->getProductSNInfo($result['product_id']);
			$sn_display = '0';
			if ((int)$sn_info['instore'] > 0 || (int)$sn_info['sold'] > 0) {
				$sn_display = sprintf($this->language->get('text_product_sn_info'), ((int)$sn_info['instore']+(int)$sn_info['sold']), $sn_info['sold']);
			}
			
			$commission = $this->model_pos_extended_product->getCommission($result['product_id']);
			$commission_display = '0';
			if ($commission) {
				if ($commission['type'] == 1) {
					$commission_display = $commission['value'];
				} else {
					$commission_display = $commission['value'] . ' ' . $this->language->get('text_commission_percentage_base') . ' ' . $commission['base'];
				}
			}

			$data['products'][] = array(
				'product_id' => $result['product_id'],
				'image'      => $image,
				'name'       => $result['name'],
				'model'       => $result['model'],
				'abbreviation'      => $result['abbreviation'],
				'weight_price'      => $result['weight_price'],
				'weight_name'      => $result['weight_name'],
				'quick_sale'      => $result['quick_sale'],
				'sn_display' => $sn_display,
				'commission_display' => $commission_display,
				'text_quick_sale' => ($result['quick_sale'] == 1) ? $this->language->get('text_quick_sale_none') : (($result['quick_sale'] == 2) ? $this->language->get('text_quick_sale_on') : $this->language->get('text_quick_sale_converted')),
				'edit'       => $this->url->link('pos/extended_product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_image'] = $this->language->get('column_image');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_abbreviation'] = $this->language->get('column_abbreviation');
		$data['column_decimal_quantity'] = $this->language->get('column_decimal_quantity');
		$data['column_sn'] = $this->language->get('column_sn');
		$data['column_commission'] = $this->language->get('column_commission');
		$data['column_action'] = $this->language->get('column_action');
		
		$data['column_quick_sale'] = $this->language->get('column_quick_sale');
		$data['entry_quick_sale'] = $this->language->get('entry_quick_sale');
		$data['text_quick_sale_none'] = $this->language->get('text_quick_sale_none');
		$data['text_quick_sale_on'] = $this->language->get('text_quick_sale_on');
		$data['text_quick_sale_converted'] = $this->language->get('text_quick_sale_converted');
		$data['button_convert'] = $this->language->get('button_convert');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_model'] = $this->language->get('entry_model');
		$data['entry_abbreviation'] = $this->language->get('entry_abbreviation');

		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['text_no'] = $this->language->get('text_no');

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

		if (isset($this->request->get['filter_abbreviation'])) {
			$url .= '&filter_abbreviation=' . urlencode(html_entity_decode($this->request->get['filter_abbreviation'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_quick_sale'])) {
			$url .= '&filter_quick_sale=' . urlencode(html_entity_decode($this->request->get['filter_quick_sale'], ENT_QUOTES, 'UTF-8'));
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('pos/extended_product', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL');
		$data['sort_model'] = $this->url->link('pos/extended_product', 'token=' . $this->session->data['token'] . '&sort=p.model' . $url, 'SSL');
		$data['sort_abbreviation'] = $this->url->link('pos/extended_product', 'token=' . $this->session->data['token'] . '&sort=p.abbreviation' . $url, 'SSL');
		$data['sort_quick_sale'] = $this->url->link('pos/extended_product', 'token=' . $this->session->data['token'] . '&sort=p.quick_sale' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_abbreviation'])) {
			$url .= '&filter_abbreviation=' . urlencode(html_entity_decode($this->request->get['filter_abbreviation'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_quick_sale'])) {
			$url .= '&filter_quick_sale=' . urlencode(html_entity_decode($this->request->get['filter_quick_sale'], ENT_QUOTES, 'UTF-8'));
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
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('pos/extended_product', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

		$data['filter_name'] = $filter_name;
		$data['filter_model'] = $filter_model;
		$data['filter_abbreviation'] = $filter_abbreviation;
		$data['filter_quick_sale'] = $filter_quick_sale;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('pos/extended_product_list.tpl', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_form'] = $this->language->get('text_edit');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_none'] = $this->language->get('text_none');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_model'] = $this->language->get('entry_model');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_abbreviation'] = $this->language->get('entry_abbreviation');
		$data['entry_decimal_quantity'] = $this->language->get('entry_decimal_quantity');
		$data['entry_decimal_quantity_name'] = $this->language->get('entry_decimal_quantity_name');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_search'] = $this->language->get('button_search');
		$data['button_add_sn'] = $this->language->get('button_add_sn');
		$data['button_modify'] = $this->language->get('button_modify');
		$data['text_sn_sold'] = $this->language->get('text_sn_sold');
		$data['text_sn_in_store'] = $this->language->get('text_sn_in_store');
		$data['entry_sn'] = $this->language->get('entry_sn');
		$data['text_no_sn_selected'] = $this->language->get('text_no_sn_selected');
		$data['text_confirm_delete_sn'] = $this->language->get('text_confirm_delete_sn');
		$data['text_no_results'] = $this->language->get('text_no_results');
		
		$data['entry_commission'] = $this->language->get('entry_commission');
		$data['entry_commission_type'] = $this->language->get('entry_commission_type');
		$data['entry_commission_fixed'] = $this->language->get('entry_commission_fixed');
		$data['entry_commission_percentage'] = $this->language->get('entry_commission_percentage');
		$data['entry_commission_value'] = $this->language->get('entry_commission_value');
		$data['text_commission_percentage_base'] = $this->language->get('text_commission_percentage_base');

		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_product_sn'] = $this->language->get('tab_product_sn');
		$data['column_action'] = $this->language->get('column_action');
		$data['column_sn_product_sn'] = $this->language->get('column_sn_product_sn');
		$data['column_sn_product_status'] = $this->language->get('column_sn_product_status');
		
		$data['entry_option_value'] = $this->language->get('entry_option_value');
		$data['entry_quantity'] = $this->language->get('entry_quantity');
		
		$data['product_id'] = $this->request->get['product_id'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_abbreviation'])) {
			$url .= '&filter_abbreviation=' . urlencode(html_entity_decode($this->request->get['filter_abbreviation'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_quick_sale'])) {
			$url .= '&filter_quick_sale=' . urlencode(html_entity_decode($this->request->get['filter_quick_sale'], ENT_QUOTES, 'UTF-8'));
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
			'href' => $this->url->link('pos/extended_product', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['action'] = $this->url->link('pos/extended_product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $this->request->get['product_id'] . $url, 'SSL');
		$data['cancel'] = $this->url->link('pos/extended_product', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['product_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$product_info = $this->model_pos_extended_product->getProduct($this->request->get['product_id']);
		}

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($product_info)) {
			$data['name'] = $product_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['model'])) {
			$data['model'] = $this->request->post['model'];
		} elseif (!empty($product_info)) {
			$data['model'] = $product_info['model'];
		} else {
			$data['model'] = '';
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
		
		if (isset($this->request->post['abbreviation'])) {
			$data['abbreviation'] = $this->request->post['abbreviation'];
		} elseif (!empty($product_info)) {
			$data['abbreviation'] = $product_info['abbreviation'];
		} else {
			$data['abbreviation'] = '';
		}

		if (isset($this->request->post['weight_name'])) {
			$data['weight_name'] = $this->request->post['weight_name'];
		} elseif (!empty($product_info)) {
			$data['weight_name'] = $product_info['weight_name'];
		} else {
			$data['weight_name'] = '';
		}

		if (isset($this->request->post['weight_price'])) {
			$data['weight_price'] = $this->request->post['weight_price'];
		} elseif (!empty($product_info)) {
			$data['weight_price'] = $product_info['weight_price'];
		} else {
			$data['weight_price'] = 0;
		}
		
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
		
		$filter_data = array('product_id' => $this->request->get['product_id']);
		$sn_total = $this->model_pos_extended_product->getTotalProductSN($filter_data);
		$sns = $this->model_pos_extended_product->getProductSNs($filter_data);

		$data['product_sns'] = array();
    	foreach ($sns as $sn) {
			$data['product_sns'][] = array(
				'product_sn_id' => $sn['product_sn_id'],
				'name'    		=> $sn['sn'],
				'order_id'		=> $sn['order_id'],
				'status'      	=> ($sn['status'] == 1) ? $this->language->get('text_sn_in_store') : ($sn['status'] == 2 ? $this->language->get('text_sn_sold') . sprintf($this->language->get('text_sold_info'), $sn['order_id']) : '')
			);
		}
		
		$page = (!empty($data['page'])) ? $data['page'] : 1;
		$limit = (!empty($data['limit'])) ? $data['limit'] : $this->config->get('config_limit_admin');
		$data['pagination'] = $this->getPagination($sn_total, $page, $limit, 'selectProductSNPage');
		
		$commission = $this->model_pos_extended_product->getCommission($this->request->get['product_id']);
		if (!empty($commission)) {
			$data['commission'] = 1;
			$data['commission_type'] = $commission['type'];
			$data['commission_value'] = $commission['value'];
			$data['commission_base'] = $commission['base'];
		} else {
			$data['commission'] = 0;
			$data['commission_type'] = 1;
			$data['commission_value'] = 0;
			$data['commission_base'] = 100;
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('pos/extended_product_form.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'pos/extended_product')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model']) || isset($this->request->get['filter_abbreviation'])) {
			$this->load->model('pos/extended_product');

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

			if (isset($this->request->get['filter_abbreviation'])) {
				$filter_abbreviation = $this->request->get['filter_abbreviation'];
			} else {
				$filter_abbreviation = '';
			}

			if (isset($this->request->get['filter_quick_sale'])) {
				$filter_quick_sale = $this->request->get['filter_quick_sale'];
			} else {
				$filter_quick_sale = '';
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 5;
			}

			$filter_data = array(
				'filter_name'  => $filter_name,
				'filter_model'  => $filter_model,
				'filter_abbreviation' => $filter_abbreviation,
				'filter_quick_sale' => $filter_quick_sale,
				'start'        => 0,
				'limit'        => $limit
			);

			$results = $this->model_pos_extended_product->getProducts($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'product_id' => $result['product_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'model'       => strip_tags(html_entity_decode($result['model'], ENT_QUOTES, 'UTF-8')),
					'abbreviation'      => $result['abbreviation']
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function convert() {
    	$this->language->load('catalog/product');
		$this->language->load('pos/extended_product');
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('pos/extended_product');
		
		if (isset($this->request->post['selected'])) {
			foreach ($this->request->post['selected'] as $product_id) {
				$this->model_pos_extended_product->convertProduct($product_id);
	  		}
		}

    	$this->getList();
	}
	
	// add for serial no begin
	public function getProductSN() {
		$json = array();
		$this->language->load('pos/extended_product');
		$this->load->model('pos/extended_product');
			
		$filter_data = array('product_id' => $this->request->post['product_id']);
		if (!empty($this->request->post['sn'])) {
			$filter_data['sn'] = $this->request->post['sn'];
		}
		if (isset($this->request->post['status'])) {
			$filter_data['status'] = $this->request->post['status'];
		}
		
		$page = 1;
		$filter['start'] = 0;
		$limit = $this->config->get('config_limit_admin');
		$filter_data['limit'] = $limit;
		if (!empty($this->request->post['page'])) {
			$page = $this->request->post['page'];
			$filter_data['start'] = ((int)$page - 1) * $filter_data['limit'];
		}
		$sn_total = $this->model_pos_extended_product->getTotalProductSN($filter_data);
		$sns = $this->model_pos_extended_product->getProductSNs($filter_data);

		$json['product_sns'] = array();
		foreach ($sns as $sn) {
			$json['product_sns'][] = array(
				'product_sn_id' => $sn['product_sn_id'],
				'name'    		=> $sn['sn'],
				'order_id'		=> $sn['order_id'],
				'status'      	=> ($sn['status'] == 1) ? $this->language->get('text_sn_in_store') : ($sn['status'] == 2 ? $this->language->get('text_sn_sold') . sprintf($this->language->get('text_sold_info'), $sn['order_id']) : '')
			);
		}
		
		$json['pagination'] = $this->getPagination($sn_total, $page, $limit, 'selectProductSNPage');

		$this->response->setOutput(json_encode($json));
	}
	public function saveProductSN() {
		$json = array();
		$this->language->load('pos/extended_product');
		if ($this->request->post['product_id'] && !empty($this->request->post['product_sn'])) {
			$this->load->model('pos/extended_product');
			$duplicates = $this->model_pos_extended_product->addProductSN($this->request->post['product_id'], $this->request->post['product_sn']);	
			if (!empty($duplicates)) {
				$json['error'] = sprintf($this->language->get('text_duplicated_sn'), implode("\n", $duplicates));
			}
			$json['success'] = sprintf($this->language->get('text_add_sn_success'), sizeof($this->request->post['product_sn'])-sizeof($duplicates));
			
			$filter_data = array('product_id' => $this->request->post['product_id']);
			$sn_total = $this->model_pos_extended_product->getTotalProductSN($filter_data);
			$sns = $this->model_pos_extended_product->getProductSNs($filter_data);

			$json['product_sns'] = array();
			foreach ($sns as $sn) {
				$json['product_sns'][] = array(
					'product_sn_id' => $sn['product_sn_id'],
					'name'    		=> $sn['sn'],
					'order_id'		=> $sn['order_id'],
					'status'      	=> ($sn['status'] == 1) ? $this->language->get('text_sn_in_store') : ($sn['status'] == 2 ? $this->language->get('text_sn_sold') . sprintf($this->language->get('text_sold_info'), $sn['order_id']) : '')
				);
			}
			
			$page = 1;
			$limit = $this->config->get('config_limit_admin');
			$json['pagination'] = $this->getPagination($sn_total, $page, $limit, 'selectProductSNPage');
		} else {
			$json['success'] = sprintf($this->language->get('text_add_sn_success'), 0);
		}
		$this->response->setOutput(json_encode($json));
	}
	
	public function deleteProductSN() {
		$json = array();
		$this->load->model('pos/extended_product');
		$this->language->load('pos/extended_product');
		if (!empty($this->request->post['sn_selected'])) {
			foreach ($this->request->post['sn_selected'] as $product_sn_id) {
				$this->model_pos_extended_product->deleteProductSN($product_sn_id);
			}
		}
		
		$filter_data = array('product_id' => $this->request->get['product_id']);
		$sn_total = $this->model_pos_extended_product->getTotalProductSN($filter_data);
		$sns = $this->model_pos_extended_product->getProductSNs($filter_data);

		$json['product_sns'] = array();
		foreach ($sns as $sn) {
			$json['product_sns'][] = array(
				'product_sn_id' => $sn['product_sn_id'],
				'name'    		=> $sn['sn'],
				'order_id'		=> $sn['order_id'],
				'status'      	=> ($sn['status'] == 1) ? $this->language->get('text_sn_in_store') : ($sn['status'] == 2 ? $this->language->get('text_sn_sold') . sprintf($this->language->get('text_sold_info'), $sn['order_id']) : '')
			);
		}
		
		$page = 1;
		$limit = $this->config->get('config_limit_admin');
		$json['pagination'] = $this->getPagination($sn_total, $page, $limit, 'selectProductSNPage');
		
		$this->response->setOutput(json_encode($json));
	}
	
	public function editProductSN() {
		$json = array();
		$this->load->model('pos/extended_product');
		$sns = $this->model_pos_extended_product->editProductSN($this->request->post['product_sn_id'], $this->request->post['product_sn']);
		$this->response->setOutput(json_encode($json));
	}
	// add for serial no end
	private function getPagination($total, $page, $limit, $function) {
		$num_links = 10;
		$num_pages = ceil($total / $limit);
		$text = $this->language->get('text_pagination');
		$text_first = '|&lt;';
		$text_last = '&gt;|';
		$text_next = '&gt;';
		$text_prev = '&lt;';
		
		$output = '<ul class="pagination">';
		if ($page > 1) {
			$output .= '<li><a onclick="' . $function . '(1);">' . $text_first . '</a></li><li><a onclick="' . $function . '(' . ($page - 1) . ');">' . $text_prev . '</a></li>';
    	}

		if ($num_pages > 1) {
			if ($num_pages <= $num_links) {
				$start = 1;
				$end = $num_pages;
			} else {
				$start = $page - floor($num_links / 2);
				$end = $page + floor($num_links / 2);
			
				if ($start < 1) {
					$end += abs($start) + 1;
					$start = 1;
				}
						
				if ($end > $num_pages) {
					$start -= ($end - $num_pages);
					$end = $num_pages;
				}
			}

			for ($i = $start; $i <= $end; $i++) {
				if ($page == $i) {
					$output .= '<li class="active"><span>' . $i . '</span></li>';
				} else {
					$output .= '<li><a onclick="' . $function . '(' . $i . ');">' . $i . '</a></li>';
				}	
			}
		}
		
   		if ($page < $num_pages) {
			$output .= '<li><a onclick="' . $function . '(' . ($page + 1) . ');">' . $text_next . '</a></li><li><a onclick="' . $function . '(' . $num_pages . ');">' . $text_last . '</a></li>';
		}
		
		$output .= '</ul>';
		
		$text = sprintf($text, ($total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($total - $limit)) ? $total : ((($page - 1) * $limit) + $limit), $total, $num_pages);
		
		return ($output ? '<div class="col-sm-6 text-left" style="width: 60%;">' . $output . '</div>' : '') . '<div class="col-sm-6 text-right" style="width: 40%;">' . $text . '</div>';
	}
}