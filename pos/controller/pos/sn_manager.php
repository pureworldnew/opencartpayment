<?php
class ControllerPosSnManager extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/product');
		$this->load->language('pos/sn_manager');
		$this->load->language('pos/extended_product');

		$this->document->setTitle($this->language->get('heading_title_sn_manager'));

		$this->load->model('pos/sn_manager');

		if (isset($this->request->get['filter_packaging_id'])) {
			$filter_packaging_id = $this->request->get['filter_packaging_id'];
		} else {
			$filter_packaging_id = null;
		}

		if (isset($this->request->get['filter_packaging_slip'])) {
			$filter_packaging_slip = $this->request->get['filter_packaging_slip'];
		} else {
			$filter_packaging_slip = null;
		}

		if (isset($this->request->get['filter_order_number'])) {
			$filter_order_number = $this->request->get['filter_order_number'];
		} else {
			$filter_order_number = null;
		}

		if (isset($this->request->get['filter_product_sn_id'])) {
			$filter_product_sn_id = $this->request->get['filter_product_sn_id'];
		} else {
			$filter_product_sn_id = null;
		}
		
		if (isset($this->request->get['filter_product_sn'])) {
			$filter_product_sn = $this->request->get['filter_product_sn'];
		} else {
			$filter_product_sn = null;
		}
		
		if (isset($this->request->get['filter_product_sn_cost'])) {
			$filter_product_sn_cost = $this->request->get['filter_product_sn_cost'];
		} else {
			$filter_product_sn_cost = null;
		}

		if (isset($this->request->get['filter_product_id'])) {
			$filter_product_id = $this->request->get['filter_product_id'];
		} else {
			$filter_product_id = null;
		}
		
		if (isset($this->request->get['filter_product_name'])) {
			$filter_product_name = $this->request->get['filter_product_name'];
		} else {
			$filter_product_name = null;
		}
		
		if (isset($this->request->get['filter_product_sn_status'])) {
			$filter_product_sn_status = $this->request->get['filter_product_sn_status'];
		} else {
			$filter_product_sn_status = null;
		}
		
		if (isset($this->request->get['filter_packaging_date'])) {
			$filter_packaging_date = $this->request->get['filter_packaging_date'];
		} else {
			$filter_packaging_date = null;
		}
		
		if (isset($this->request->get['filter_packaging_note'])) {
			$filter_packaging_note = $this->request->get['filter_packaging_note'];
		} else {
			$filter_packaging_note = null;
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_packaging_id'])) {
			$url .= '&filter_packaging_id=' . $this->request->get['filter_packaging_id'];
		}
		
		if (isset($this->request->get['filter_packaging_slip'])) {
			$url .= '&filter_packaging_slip=' . urlencode(html_entity_decode($this->request->get['filter_packaging_slip'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_order_number'])) {
			$url .= '&filter_order_number=' . urlencode(html_entity_decode($this->request->get['filter_order_number'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_product_sn_id'])) {
			$url .= '&filter_product_sn_id=' . $this->request->get['filter_product_sn_id'];
		}
		
		if (isset($this->request->get['filter_product_sn'])) {
			$url .= '&filter_product_sn=' . urlencode(html_entity_decode($this->request->get['filter_product_sn'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_product_sn_cost'])) {
			$url .= '&filter_product_sn_cost=' . $this->request->get['filter_product_sn_cost'];
		}
		
		if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}
		
		if (isset($this->request->get['filter_product_name'])) {
			$url .= '&filter_product_name=' . urlencode(html_entity_decode($this->request->get['filter_product_name'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_product_sn_status'])) {
			$url .= '&filter_product_sn_status=' . $this->request->get['filter_product_sn_status'];
		}
		
		if (isset($this->request->get['filter_packaging_date'])) {
			$url .= '&filter_packaging_date=' . $this->request->get['filter_packaging_date'];
		}
		
		if (isset($this->request->get['filter_packaging_note'])) {
			$url .= '&filter_packaging_note=' . urlencode(html_entity_decode($this->request->get['filter_packaging_note'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_sn_manager'),
			'href'      => $this->url->link('pos/sn_manager', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);

		$data['sns'] = array();

		$filter_data = array(
			'filter_packaging_id'       => $filter_packaging_id,
			'filter_packaging_slip'     => $filter_packaging_slip,
			'filter_order_number'       => $filter_order_number,
			'filter_product_sn_id'      => $filter_product_sn_id,
			'filter_product_sn'         => $filter_product_sn,
			'filter_product_sn_cost'    => $filter_product_sn_cost,
			'filter_product_id'         => $filter_product_id,
			'filter_product_name'       => $filter_product_name,
			'filter_product_sn_status'  => $filter_product_sn_status,
			'filter_packaging_date'     => $filter_packaging_date,
			'filter_packaging_note'     => $filter_packaging_note,
			'start'                     => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                     => $this->config->get('config_limit_admin')
		);

		$sn_total = $this->model_pos_sn_manager->getTotalSns($filter_data);

		$results = $this->model_pos_sn_manager->getSns($filter_data);

    	foreach ($results as $result) {
			$data['sns'][] = array(
				'packaging_id'         => $result['packaging_id'],
				'packaging_slip'       => $result['packaging_slip'],
				'order_number'         => $result['order_number'],
				'product_name'     	   => $result['product_name'],
				'product_sn'     	   => $result['sn'],
				'product_sn_id'    	   => $result['product_sn_id'],
				'cost'         		   => $result['cost'],
				'product_sn_status'	   => $result['status'],
				'date'                 => $result['packaging_date'],
				'note'                 => (!empty($result['note'])) ? $result['note'] : ''
			);
		}

		$data['heading_title'] = $this->language->get('heading_title_sn_manager');
		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['column_packaging_slip'] = $this->language->get('column_packaging_slip');
		$data['column_order_number'] = $this->language->get('column_order_number');
		$data['column_product_name'] = $this->language->get('column_product_name');
		$data['column_product_sn'] = $this->language->get('column_product_sn');
    	$data['column_product_sn_status'] = $this->language->get('column_product_sn_status');
		$data['column_date'] = $this->language->get('column_date');
		$data['column_note'] = $this->language->get('column_note');
		$data['column_cost'] = $this->language->get('column_cost');

		$data['text_new_packaging'] = $this->language->get('text_new_packaging');
		$data['text_sn_in_store'] = $this->language->get('text_sn_in_store');
		$data['text_sn_sold'] = $this->language->get('text_sn_sold');
		$data['text_saving_packaging'] = $this->language->get('text_saving_packaging');
		$data['text_deleting'] = $this->language->get('text_deleting');
		$data['text_no_item_selected'] = $this->language->get('text_no_item_selected');
		$data['text_confirm_delete'] = $this->language->get('text_confirm_delete');

		$data['button_new_packaging'] = $this->language->get('button_new_packaging');
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

		$url = '';

		if (isset($this->request->get['filter_packaging_id'])) {
			$url .= '&filter_packaging_id=' . $this->request->get['filter_packaging_id'];
		}
		
		if (isset($this->request->get['filter_packaging_slip'])) {
			$url .= '&filter_packaging_slip=' . urlencode(html_entity_decode($this->request->get['filter_packaging_slip'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_order_number'])) {
			$url .= '&filter_order_number=' . urlencode(html_entity_decode($this->request->get['filter_order_number'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_product_sn_id'])) {
			$url .= '&filter_product_sn_id=' . $this->request->get['filter_product_sn_id'];
		}
		
		if (isset($this->request->get['filter_product_sn'])) {
			$url .= '&filter_product_sn=' . urlencode(html_entity_decode($this->request->get['filter_product_sn'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_product_sn_cost'])) {
			$url .= '&filter_product_sn_cost=' . $this->request->get['filter_product_sn_cost'];
		}
		
		if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}
		
		if (isset($this->request->get['filter_product_name'])) {
			$url .= '&filter_product_name=' . urlencode(html_entity_decode($this->request->get['filter_product_name'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_product_sn_status'])) {
			$url .= '&filter_product_sn_status=' . $this->request->get['filter_product_sn_status'];
		}
		
		if (isset($this->request->get['filter_packaging_date'])) {
			$url .= '&filter_packaging_date=' . $this->request->get['filter_packaging_date'];
		}
		
		if (isset($this->request->get['filter_packaging_note'])) {
			$url .= '&filter_packaging_note=' . urlencode(html_entity_decode($this->request->get['filter_packaging_note'], ENT_QUOTES, 'UTF-8'));
		}

		$pagination = new Pagination();
		$pagination->total = $sn_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('pos/sn_manager', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($sn_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($sn_total - $this->config->get('config_limit_admin'))) ? $sn_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $sn_total, ceil($sn_total / $this->config->get('config_limit_admin')));

		$data['filter_packaging_id'] = $filter_packaging_id;
		$data['filter_packaging_slip'] = $filter_packaging_slip;
		$data['filter_order_number'] = $filter_order_number;
		$data['filter_product_sn_id'] = $filter_product_sn_id;
		$data['filter_product_sn'] = $filter_product_sn;
		$data['filter_product_sn_cost'] = $filter_product_sn_cost;
		$data['filter_product_id'] = $filter_product_id;
		$data['filter_product_name'] = $filter_product_name;
		$data['filter_product_sn_status'] = $filter_product_sn_status;
		$data['filter_packaging_date'] = $filter_packaging_date;
		$data['filter_packaging_note'] = $filter_packaging_note;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('pos/sn_manager.tpl', $data));
	}

	public function autocomplete_packaging() {
		$json = array();

		if (isset($this->request->get['filter_packaging_slip']) || isset($this->request->get['filter_order_number']) || isset($this->request->get['filter_packaging_note'])) {
			$this->load->model('pos/sn_manager');

			if (isset($this->request->get['filter_packaging_slip'])) {
				$filter_packaging_slip = $this->request->get['filter_packaging_slip'];
			} else {
				$filter_packaging_slip = null;
			}

			if (isset($this->request->get['filter_order_number'])) {
				$filter_order_number = $this->request->get['filter_order_number'];
			} else {
				$filter_order_number = null;
			}

			if (isset($this->request->get['filter_packaging_note'])) {
				$filter_packaging_note = $this->request->get['filter_packaging_note'];
			} else {
				$filter_packaging_note = null;
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 5;
			}

			$filter_data = array(
				'filter_packaging_slip'  => $filter_packaging_slip,
				'filter_order_number'  => $filter_order_number,
				'filter_packaging_note'  => $filter_packaging_note,
				'start'        => 0,
				'limit'        => $limit
			);

			$results = $this->model_pos_sn_manager->getPackagings($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'packaging_id' => $result['packaging_id'],
					'packaging_slip' => strip_tags(html_entity_decode($result['packaging_slip'], ENT_QUOTES, 'UTF-8')),
					'order_number' => strip_tags(html_entity_decode($result['order_number'], ENT_QUOTES, 'UTF-8')),
					'date' => $result['date'],
					'note' => strip_tags(html_entity_decode($result['note'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function autocomplete_product_sn() {
		$json = array();

		if (isset($this->request->get['filter_product_sn'])) {
			$this->load->model('pos/sn_manager');

			$filter_product_sn = $this->request->get['filter_product_sn'];

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 5;
			}

			$filter_data = array(
				'filter_product_sn'  => $filter_product_sn,
				'start'        => 0,
				'limit'        => $limit
			);

			$results = $this->model_pos_sn_manager->getProductSns($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'product_sn_id' => $result['product_sn_id'],
					'product_sn' => $result['product_sn'],
					'product_name' => $result['product_name']
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function change_packaging_slip() {
		$this->load->model('pos/sn_manager');
		$this->model_pos_sn_manager->change_packaging_slip($this->request->post);
		$this->response->setOutput(json_encode(array()));
	}
	
	public function change_order_number() {
		$this->load->model('pos/sn_manager');
		$this->model_pos_sn_manager->change_order_number($this->request->post);
		$this->response->setOutput(json_encode(array()));
	}
	
	public function change_packaging_date() {
		$this->load->model('pos/sn_manager');
		$this->model_pos_sn_manager->change_packaging_date($this->request->post);
		$this->response->setOutput(json_encode(array()));
	}
	
	public function change_packaging_note() {
		$this->load->model('pos/sn_manager');
		$this->model_pos_sn_manager->change_packaging_note($this->request->post);
		$this->response->setOutput(json_encode(array()));
	}
	
	public function change_product_sn() {
		$this->load->model('pos/sn_manager');
		$success = $this->model_pos_sn_manager->change_product_sn($this->request->post);
		if (!$success) {
			$this->load->language('pos/sn_manager');
			$this->response->setOutput(json_encode(array('error' => $this->language->get('text_product_sn_already_sold'))));
		} else {
			$this->response->setOutput(json_encode(array()));
		}
	}
	
	public function change_product_sn_cost() {
		$this->load->model('pos/sn_manager');
		$success = $this->model_pos_sn_manager->change_product_sn_cost($this->request->post);
		if (!$success) {
			$this->load->language('pos/sn_manager');
			$this->response->setOutput(json_encode(array('error' => $this->language->get('text_product_sn_already_sold'))));
		} else {
			$this->response->setOutput(json_encode(array()));
		}
	}
	
	public function save_packaging() {
		$this->load->model('pos/sn_manager');
		$err = $this->model_pos_sn_manager->save_packaging($this->request->post);
		$this->response->setOutput(json_encode(array()));
	}
	
	public function delete_product_sn() {
		$this->load->model('pos/sn_manager');
		$err = $this->model_pos_sn_manager->delete_product_sn($this->request->post);
		$this->response->setOutput(json_encode(array()));
	}
}