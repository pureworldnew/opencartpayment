<?php
class ControllerReportOrderPayment extends Controller { 
	public function index() {  
		$this->language->load('pos/payment_report');

		$this->document->setTitle($this->language->get('details_heading_title'));

		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}

		if (isset($this->request->get['filter_pos_return_id'])) {
			$filter_pos_return_id = $this->request->get['filter_pos_return_id'];
		} else {
			$filter_pos_return_id = null;
		}

		if (isset($this->request->get['filter_payment_type'])) {
			$filter_payment_type = $this->request->get['filter_payment_type'];
		} else {
			$filter_payment_type = null;
		}

		if (isset($this->request->get['filter_tendered_amount'])) {
			$filter_tendered_amount = $this->request->get['filter_tendered_amount'];
		} else {
			$filter_tendered_amount = null;
		}
		
		if (isset($this->request->get['filter_payment_date'])) {
			$filter_payment_date = $this->request->get['filter_payment_date'];
		} else {
			$filter_payment_date = null;
		}
		
		// add for admin payment details begin
		if (isset($this->request->get['filter_user_id'])) {
			$filter_user_id = $this->request->get['filter_user_id'];
		} else {
			$filter_user_id = null;
		}
		
		if (isset($this->request->get['filter_user_name'])) {
			$filter_user_name = $this->request->get['filter_user_name'];
		} else {
			$filter_user_name = null;
		}
	
		if (isset($this->request->get['filter_invoice_number'])) {
			$filter_invoice_number = $this->request->get['filter_invoice_number'];
		} else {
			$filter_invoice_number = null;
		}
		// add for admin payment details end
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'order_payment_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
				
		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_pos_return_id'])) {
			$url .= '&filter_pos_return_id=' . $this->request->get['filter_pos_return_id'];
		}
		
		if (isset($this->request->get['filter_payment_type'])) {
			$url .= '&filter_payment_type=' . urlencode(html_entity_decode($this->request->get['filter_payment_type'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_tendered_amount'])) {
			$url .= '&filter_tendered_amount=' . $this->request->get['filter_tendered_amount'];
		}
		
		if (isset($this->request->get['filter_payment_date'])) {
			$url .= '&filter_payment_date=' . $this->request->get['filter_payment_date'];
		}
		
		// add for admin payment details begin
		if (isset($this->request->get['filter_user_id'])) {
			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
		}
		if (isset($this->request->get['filter_user_name'])) {
			$url .= '&filter_user_name=' . $this->request->get['filter_user_name'];
		}
		
		if (isset($this->request->get['filter_invoice_number'])) {
			$url .= '&filter_invoice_number=' . $this->request->get['filter_invoice_number'];
		}
		// add for admin payment details end

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
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('details_heading_title'),
			'href'      => $this->url->link('report/order_payment', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);

		$data['order_payments'] = array();

		$timezone_offset = 0;
		if (isset($this->request->get['browser_time'])) {
			$time_query = $this->db->query("SELECT NOW() AS now");
			$time_db = strtotime($time_query->row['now']);
			$timezone_offset = intval(($this->request->get['browser_time'] / 1000 - $time_db) / 60);
		}
		
		$filter_data = array(
			'filter_order_id'        => $filter_order_id,
			'filter_pos_return_id'   => $filter_pos_return_id,
			'filter_payment_type'	 => $filter_payment_type,
			'filter_tendered_amount' => $filter_tendered_amount,
			'filter_payment_date'    => $filter_payment_date, 
			'timezone_offset'		 => $timezone_offset * 60,
			// add for admin payment details begin
			'filter_user_id'         => $filter_user_id,
			'filter_invoice_number'  => $filter_invoice_number,
			// add for admin payment details end
			'sort'                   => $sort,
			'order'                  => $order,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		$this->load->model('pos/pos');
		
		$payment_total = $this->model_pos_pos->getTotalOrderPayments($filter_data);
		$results = $this->model_pos_pos->getOrderPayments($filter_data);

		// add for admin payment details begin
		$this->load->model('user/user');
		// add for admin payment details end

    	foreach ($results as $result) {
			// add for admin payment details begin
			$user_info = $this->model_user_user->getUser($result['user_id']);
			// add for admin payment details end
			$data['order_payments'][] = array(
				'order_payment_id' => $result['order_payment_id'],
				'order_id'         => $result['order_id'],
				'pos_return_id'    => $result['pos_return_id'],
				'payment_type'     => $result['payment_type'],
				'tendered_amount'  => $this->currency->format($result['tendered_amount']),
				'payment_note'     => $result['payment_note'],
				// add for admin payment details begin
				'user_id'          => $result['user_id'],
				'user_name'        => $user_info ? $user_info['username'] : '',
				// add for admin payment details end
				'payment_time'     => date($this->language->get('date_format_short'), strtotime($result['payment_time']) + $timezone_offset * 60) . ' ' . date($this->language->get('time_format'), strtotime($result['payment_time']) + $timezone_offset * 60)
			);
		}

		$data['heading_title'] = $this->language->get('details_heading_title');

		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['column_order_payment_id'] = $this->language->get('column_order_payment_id');
    	$data['column_order_id'] = $this->language->get('column_order_id');
    	$data['column_pos_return_id'] = $this->language->get('column_pos_return_id');
		$data['column_payment_type'] = $this->language->get('column_payment_type');
		$data['column_tendered_amount'] = $this->language->get('column_tendered_amount');
		$data['column_payment_note'] = $this->language->get('column_payment_note');
		$data['column_payment_time'] = $this->language->get('column_payment_time');
		// add for admin payment details begin
		$data['column_user_name'] = $this->language->get('column_user_name');
		$data['column_invoice_number'] = $this->language->get('column_invoice_number');
		// add for admin payment details end

		$data['button_export_csv'] = $this->language->get('button_export_csv');
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

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_pos_return_id'])) {
			$url .= '&filter_pos_return_id=' . $this->request->get['filter_pos_return_id'];
		}
		
		if (isset($this->request->get['filter_payment_type'])) {
			$url .= '&filter_payment_type=' . urlencode(html_entity_decode($this->request->get['filter_payment_type'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_tendered_amount'])) {
			$url .= '&filter_tendered_amount=' . $this->request->get['filter_tendered_amount'];
		}
		
		if (isset($this->request->get['filter_payment_date'])) {
			$url .= '&filter_payment_date=' . $this->request->get['filter_payment_date'];
		}
		
		// add for admin payment details begin
		if (isset($this->request->get['filter_user_id'])) {
			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
		}
		if (isset($this->request->get['filter_invoice_number'])) {
			$url .= '&filter_invoice_number=' . $this->request->get['filter_invoice_number'];
		}
		// add for admin payment details end

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_order_payment'] = $this->url->link('report/order_payment', 'token=' . $this->session->data['token'] . '&sort=order_payment_id' . $url, 'SSL');
		$data['sort_order'] = $this->url->link('report/order_payment', 'token=' . $this->session->data['token'] . '&sort=order_id' . $url, 'SSL');
		$data['sort_pos_return'] = $this->url->link('report/order_payment', 'token=' . $this->session->data['token'] . '&sort=pos_return_id' . $url, 'SSL');
		$data['sort_payment_type'] = $this->url->link('report/order_payment', 'token=' . $this->session->data['token'] . '&sort=payment_type' . $url, 'SSL');
		$data['sort_tendered_amount'] = $this->url->link('report/order_payment', 'token=' . $this->session->data['token'] . '&sort=tendered_amount' . $url, 'SSL');
		$data['sort_payment_time'] = $this->url->link('report/order_payment', 'token=' . $this->session->data['token'] . '&sort=payment_time' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_pos_return_id'])) {
			$url .= '&filter_pos_return_id=' . $this->request->get['filter_pos_return_id'];
		}
		
		if (isset($this->request->get['filter_payment_type'])) {
			$url .= '&filter_payment_type=' . urlencode(html_entity_decode($this->request->get['filter_payment_type'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_tendered_amount'])) {
			$url .= '&filter_tendered_amount=' . $this->request->get['filter_tendered_amount'];
		}
		
		if (isset($this->request->get['filter_payment_date'])) {
			$url .= '&filter_payment_date=' . $this->request->get['filter_payment_date'];
		}
		
		// add for admin payment details begin
		if (isset($this->request->get['filter_user_id'])) {
			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
		}
		if (isset($this->request->get['filter_invoice_number'])) {
			$url .= '&filter_invoice_number=' . $this->request->get['filter_invoice_number'];
		}
		// add for admin payment details end

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $payment_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/order_payment', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($payment_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($payment_total - $this->config->get('config_limit_admin'))) ? $payment_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $payment_total, ceil($payment_total / $this->config->get('config_limit_admin')));

		$data['filter_order_id'] = $filter_order_id;
		$data['filter_pos_return_id'] = $filter_pos_return_id;
		$data['filter_payment_type'] = $filter_payment_type;
		$data['filter_tendered_amount'] = $filter_tendered_amount;
		$data['filter_payment_date'] = $filter_payment_date;
		
		// add for admin payment details begin
		$data['filter_user_id'] = $filter_user_id;
		$data['filter_user_name'] = $filter_user_name;
		$data['filter_invoice_number'] = $filter_invoice_number;
		// add for admin payment details end

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('pos/report_payment_details.tpl', $data));
	}
	
	// add for admin payment details begin
	public function autocompleteByUserName() {
		$json = array();
		
		if (isset($this->request->get['filter_name'])) {
			$sql = "SELECT * FROM `" . DB_PREFIX . "user` WHERE username LIKE '%" . $this->db->escape($this->request->get['filter_name']) . "%' LIMIT 0, 20";
			
			$query = $this->db->query($sql);
			foreach ($query->rows as $result) {
				$json[] = array(
					'user_id'       => $result['user_id'], 
					'user_name'     => $result['username']
				);					
			}

			$sort_order = array();
		  
			foreach ($json as $key => $value) {
				$sort_order[$key] = $value['user_name'];
			}

			array_multisort($sort_order, SORT_ASC, $json);
		}

		$this->response->setOutput(json_encode($json));
	}
	// add for admin payment details end
	
	public function summary() {  
		$this->language->load('pos/payment_report');
		$this->language->load('report/sale_order');

		$this->document->setTitle($this->language->get('summary_heading_title'));

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}
		
		if (isset($this->request->get['filter_group'])) {
			$filter_group = $this->request->get['filter_group'];
		} else {
			$filter_group = 'day';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		
		if (isset($this->request->get['filter_group'])) {
			$url .= '&filter_group=' . $this->request->get['filter_group'];
		}		

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

   		$data['breadcrumbs'] = array();

		$heading_title = $this->language->get('summary_heading_title');

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),       		
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
			'text'      => $heading_title,
			'href'      => $this->url->link('report/order_payment/summary', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->load->model('setting/setting');
		$pos_settings = $this->model_setting_setting->getSetting('POS');
		
		$payment_types = $pos_settings['POS_POS_payment_types'];

		$this->load->model('pos/report_payment');

		$data['orders'] = array();
		
		$timezone_offset = 0;
		if (isset($this->request->get['browser_time'])) {
			$time_query = $this->db->query("SELECT NOW() AS now");
			$time_db = strtotime($time_query->row['now']);
			$timezone_offset = intval(($this->request->get['browser_time'] / 1000 - $time_db) / 60);	// set to minute, not seconds
		}
		
		$filter_data = array(
			'filter_date_start'	     => $filter_date_start, 
			'filter_date_end'	     => $filter_date_end, 
			'timezone_offset'		 => $timezone_offset * 60,
			'filter_group'           => $filter_group,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		
		$order_total = $this->model_pos_report_payment->getTotalPayments($filter_data);
		
		$results = $this->model_pos_report_payment->getPayments($filter_data);
		
		$payment_types = array('cash_in', 'cash_out');
		foreach ($results as $result) {
			$payments = array();
			
			if ($result['username']) {
				$payments['username'] = $result['username'];
			} else {
				$payments['username'] = $this->language->get('text_front_order');
			}
			$payments['date_start'] = date('Y-m-d', (strtotime($result['date_start']) + $timezone_offset * 60));
			$payments['date_end'] = date('Y-m-d', (strtotime($result['date_end']) + $timezone_offset * 60));
			
			$pos_payment_total = 0;
			$pos_payment_subtotal = 0;
			$payments['amounts'] = array();
			foreach ($result['amounts'] as $amount) {
				$payments['amounts'][$amount['payment_type']] = $this->currency->format($amount['amount'], $this->config->get('config_currency'));
				if (!in_array($amount['payment_type'], $payment_types)) {
					array_push($payment_types, $amount['payment_type']);
				}
				
				$pos_payment_total += $amount['amount'];
				if ($amount['payment_type'] != 'cash_in' && $amount['payment_type'] != 'cash_out') {
					$pos_payment_subtotal += $amount['amount'];
				}
			}
			$payments['total'] = $this->currency->format($pos_payment_total, $this->config->get('config_currency'));
			$payments['subtotal'] = $this->currency->format($pos_payment_subtotal, $this->config->get('config_currency'));

			$data['payments'][] = $payments;
		}
		if (!empty($data['payments'])) {
			foreach ($payment_types as $payment_type) {
				foreach ($data['payments'] as $key => $payments) {
					if (!isset($payments['amounts'][$payment_type])) {
						$data['payments'][$key]['amounts'][$payment_type] = $this->currency->format(0, $this->config->get('config_currency'));
					}
				}
			}
		}
		$data['payment_types'] = $payment_types;

		$data['heading_title'] = $this->language->get('summary_heading_title');
		
		$data['text_no_results'] = $this->language->get('text_no_results');
		
		$data['column_user_name'] = $this->language->get('column_user_name');
		$data['column_date_start'] = $this->language->get('column_date_start');
		$data['column_date_end'] = $this->language->get('column_date_end');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_subtotal'] = $this->language->get('column_subtotal');
		
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_group'] = $this->language->get('entry_group');	

		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_export_csv'] = $this->language->get('button_export_csv');
		
		$data['token'] = $this->session->data['token'];

		$data['groups'] = array();

		$data['groups'][] = array(
			'text'  => $this->language->get('text_year'),
			'value' => 'year',
		);

		$data['groups'][] = array(
			'text'  => $this->language->get('text_month'),
			'value' => 'month',
		);

		$data['groups'][] = array(
			'text'  => $this->language->get('text_week'),
			'value' => 'week',
		);

		$data['groups'][] = array(
			'text'  => $this->language->get('text_day'),
			'value' => 'day',
		);

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		
		if (isset($this->request->get['filter_group'])) {
			$url .= '&filter_group=' . $this->request->get['filter_group'];
		}		

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/order_payment/summary', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;		
		$data['filter_group'] = $filter_group;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('pos/report_payment_summary.tpl', $data));
	}
	
	public function generate_report_csv() {
		$type = $this->request->get['type'];
		
		// output headers so that the file is downloaded rather than displayed
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=' . $type . '.csv');

		// create a file pointer connected to the output stream
		$output = fopen('php://output', 'w');

		$this->language->load('pos/payment_report');
		$this->language->load('module/pos');
		
		// output the data
		if ($type == 'details') {
			// set the column header
			fputcsv($output, array(
				$this->language->get('column_order_payment_id'),
				$this->language->get('column_order_id'),
				$this->language->get('column_pos_return_id'),
				$this->language->get('column_payment_type'),
				$this->language->get('column_tendered_amount'),
				$this->language->get('column_payment_note'),
				$this->language->get('column_payment_time'),
				$this->language->get('column_user_name')));
			
			if (isset($this->request->get['filter_order_id'])) {
				$filter_order_id = $this->request->get['filter_order_id'];
			} else {
				$filter_order_id = null;
			}

			if (isset($this->request->get['filter_pos_return_id'])) {
				$filter_pos_return_id = $this->request->get['filter_pos_return_id'];
			} else {
				$filter_pos_return_id = null;
			}

			if (isset($this->request->get['filter_payment_type'])) {
				$filter_payment_type = $this->request->get['filter_payment_type'];
			} else {
				$filter_payment_type = null;
			}

			if (isset($this->request->get['filter_tendered_amount'])) {
				$filter_tendered_amount = $this->request->get['filter_tendered_amount'];
			} else {
				$filter_tendered_amount = null;
			}
			
			if (isset($this->request->get['filter_payment_date'])) {
				$filter_payment_date = $this->request->get['filter_payment_date'];
			} else {
				$filter_payment_date = null;
			}
			
			// add for admin payment details begin
			if (isset($this->request->get['filter_user_id'])) {
				$filter_user_id = $this->request->get['filter_user_id'];
			} else {
				$filter_user_id = null;
			}
		
			if (isset($this->request->get['filter_invoice_number'])) {
				$filter_invoice_number = $this->request->get['filter_invoice_number'];
			} else {
				$filter_invoice_number = null;
			}
			
			$timezone_offset = 0;
			if (isset($this->request->get['browser_time'])) {
				$time_query = $this->db->query("SELECT NOW() AS now");
				$time_db = strtotime($time_query->row['now']);
				$timezone_offset = intval(($this->request->get['browser_time'] / 1000 - $time_db) / 60);
			}

			$filter_data = array(
				'filter_order_id'        => $filter_order_id,
				'filter_pos_return_id'   => $filter_pos_return_id,
				'filter_payment_type'	 => $filter_payment_type,
				'filter_tendered_amount' => $filter_tendered_amount,
				'filter_payment_date'    => $filter_payment_date, 
				'timezone_offset'		 => $timezone_offset * 60,
				'filter_user_id'         => $filter_user_id,
				'filter_invoice_number'  => $filter_invoice_number,
			);
			
			$this->load->model('pos/pos');
			$payment_total = $this->model_pos_pos->getTotalOrderPayments($filter_data);
			$results = $this->model_pos_pos->getOrderPayments($filter_data);

			// add for admin payment details begin
			$this->load->model('user/user');
			// add for admin payment details end

			foreach ($results as $result) {
				// add for admin payment details begin
				$user_info = $this->model_user_user->getUser($result['user_id']);
				// add for admin payment details end
				$row = array(
					'order_payment_id' => $result['order_payment_id'],
					'order_id'         => $result['order_id'],
					'pos_return_id'    => $result['pos_return_id'],
					'payment_type'     => $result['payment_type'],
					'tendered_amount'  => $this->currency->format($result['tendered_amount']),
					'payment_note'     => $result['payment_note'],
					'payment_time'     => date($this->language->get('date_format_short'), strtotime($result['payment_time']) + $timezone_offset * 60) . ' ' . date($this->language->get('time_format'), strtotime($result['payment_time']) + $timezone_offset * 60),
					'user_name'        => $user_info ? $user_info['username'] : ''
				);
				fputcsv($output, $row);
			}
		} elseif ($type == 'summary') {
			$this->language->load('report/sale_order');
			
			if (isset($this->request->get['filter_date_start'])) {
				$filter_date_start = $this->request->get['filter_date_start'];
			} else {
				$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
			}

			if (isset($this->request->get['filter_date_end'])) {
				$filter_date_end = $this->request->get['filter_date_end'];
			} else {
				$filter_date_end = date('Y-m-d');
			}
			
			if (isset($this->request->get['filter_group'])) {
				$filter_group = $this->request->get['filter_group'];
			} else {
				$filter_group = 'day';
			}

			$this->load->model('pos/report_payment');
			
			$timezone_offset = 0;
			if (isset($this->request->get['browser_time'])) {
				$time_query = $this->db->query("SELECT NOW() AS now");
				$time_db = strtotime($time_query->row['now']);
				$timezone_offset = intval(($this->request->get['browser_time'] / 1000 - $time_db) / 60);	// set to minute, not seconds
			}
			
			$filter_data = array(
				'filter_date_start'	     => $filter_date_start, 
				'filter_date_end'	     => $filter_date_end, 
				'timezone_offset'		 => $timezone_offset * 60,
				'filter_group'           => $filter_group
			);
			
			$order_total = $this->model_pos_report_payment->getTotalPayments($filter_data);
			
			$results = $this->model_pos_report_payment->getPayments($filter_data);
			
			$payment_types = array('cash_in', 'cash_out');
			
			$summaries = array();
			foreach ($results as $result) {
				$payments = array();
				
				if ($result['username']) {
					$payments['username'] = $result['username'];
				} else {
					$payments['username'] = $this->language->get('text_front_order');
				}
				$payments['date_start'] = date('Y-m-d', (strtotime($result['date_start']) + $timezone_offset * 60));
				$payments['date_end'] = date('Y-m-d', (strtotime($result['date_end']) + $timezone_offset * 60));
				
				$pos_payment_total = 0;
				$pos_payment_subtotal = 0;
				$payments['amounts'] = array();
				foreach ($result['amounts'] as $amount) {
					$payments['amounts'][$amount['payment_type']] = $this->currency->format($amount['amount'], $this->config->get('config_currency'));
					if (!in_array($amount['payment_type'], $payment_types)) {
						array_push($payment_types, $amount['payment_type']);
					}
					
					$pos_payment_total += $amount['amount'];
					if ($amount['payment_type'] != 'cash_in' && $amount['payment_type'] != 'cash_out') {
						$pos_payment_subtotal += $amount['amount'];
					}
				}
				$payments['total'] = $this->currency->format($pos_payment_total, $this->config->get('config_currency'));
				$payments['subtotal'] = $this->currency->format($pos_payment_subtotal, $this->config->get('config_currency'));

				$summaries[] = $payments;
			}
			if (!empty($summaries)) {
				foreach ($payment_types as $payment_type) {
					foreach ($summaries as $key => $summary) {
						if (!isset($summary['amounts'][$payment_type])) {
							$summaries[$key]['amounts'][$payment_type] = $this->currency->format(0, $this->config->get('config_currency'));
						}
					}
				}
			}
			
			// set the column header
			$header = array($this->language->get('column_user_name'), $this->language->get('column_date_start'), $this->language->get('column_date_end'));
			foreach($payment_types as $payment_type) {
				if ($payment_type != 'cash_in' && $payment_type != 'cash_out') {
					$header[] = $payment_type;
				}
			}
			$header[] = $this->language->get('column_subtotal');
			$header[] = $this->language->get('text_cash_in');
			$header[] = $this->language->get('text_cash_out');
			$header[] = $this->language->get('column_total');
			fputcsv($output, $header);
			if (!empty($summaries)) {
				foreach ($summaries as $summary) {
					$row = array($summary['username'], $summary['date_start'], $summary['date_end']);
					foreach($summary['amounts'] as $payment_type => $payment_value) {
						if ($payment_type != 'cash_in' && $payment_type != 'cash_out') {
							$row[] = $payment_value;
						}
					}
					$row[] = $summary['subtotal'];
					$row[] = $summary['amounts']['cash_in'];
					$row[] = $summary['amounts']['cash_out'];
					$row[] = $summary['total'];
					fputcsv($output, $row);
				}
			}
		}
	}
}
?>