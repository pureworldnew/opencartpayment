<?php
class ControllerReportPosCommission extends Controller { 
	public function index() {  
		$this->language->load('pos/commission');

		$this->document->setTitle($this->language->get('details_heading_title'));

		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}

		if (isset($this->request->get['filter_commission'])) {
			$filter_commission = $this->request->get['filter_commission'];
		} else {
			$filter_commission = null;
		}

		if (isset($this->request->get['filter_commission_date'])) {
			$filter_commission_date = $this->request->get['filter_commission_date'];
		} else {
			$filter_commission_date = null;
		}
		
		if (isset($this->request->get['filter_user_id'])) {
			$filter_user_id = $this->request->get['filter_user_id'];
		} else {
			$filter_user_id = 0;
		}
		
		if (isset($this->request->get['filter_user_name'])) {
			$filter_user_name = $this->request->get['filter_user_name'];
		} else {
			$filter_user_name = null;
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'order_id';
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

		if (isset($this->request->get['filter_commission_date'])) {
			$url .= '&filter_commission_date=' . $this->request->get['filter_commission_date'];
		}
		
		if (isset($this->request->get['filter_user_id'])) {
			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
		}
		if (isset($this->request->get['filter_user_name'])) {
			$url .= '&filter_user_name=' . $this->request->get['filter_user_name'];
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
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('details_heading_title'),
			'href'      => $this->url->link('report/pos_commission', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);

		$timezone_offset = 0;
		if (isset($this->request->get['browser_time'])) {
			$time_query = $this->db->query("SELECT NOW() AS now");
			$time_db = strtotime($time_query->row['now']);
			$timezone_offset = intval(($this->request->get['browser_time'] / 1000 - $time_db) / 60);
		}
		
		$filter_data = array(
			'filter_order_id'        => $filter_order_id,
			'filter_commission'      => $filter_commission,
			'filter_commission_date' => $filter_commission_date,
			'timezone_offset'		 => $timezone_offset * 60,
			'filter_user_id'         => $filter_user_id,
			'sort'                   => $sort,
			'order'                  => $order,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		$this->load->model('pos/report_commission');
		
		$commissions_total = $this->model_pos_report_commission->getTotalOrderCommissions($filter_data);
		$data['order_commissions'] = $this->model_pos_report_commission->getOrderCommissions($filter_data);
		foreach ($data['order_commissions'] as $key => $order_commission) {
			$data['order_commissions'][$key]['date_modified'] = date('Y-m-d', (strtotime($order_commission['date_modified']) + $timezone_offset * 60));
		}

		$data['heading_title'] = $this->language->get('details_heading_title');

		$data['text_no_results'] = $this->language->get('text_no_results');

    	$data['column_order_id'] = $this->language->get('column_order_id');
		$data['column_commission'] = $this->language->get('column_commission');
		$data['column_commission_date'] = $this->language->get('column_commission_date');
		$data['column_time'] = $this->language->get('column_time');
		$data['column_user_name'] = $this->language->get('column_user_name');

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
		
		if (isset($this->request->get['filter_commission_date'])) {
			$url .= '&filter_commission_date=' . $this->request->get['filter_commission_date'];
		}
		
		if (isset($this->request->get['filter_user_id'])) {
			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_order'] = $this->url->link('report/pos_commission', 'token=' . $this->session->data['token'] . '&sort=order_id' . $url, 'SSL');
		$data['sort_commission'] = $this->url->link('report/pos_commission', 'token=' . $this->session->data['token'] . '&sort=commission' . $url, 'SSL');
		$data['sort_commission_time'] = $this->url->link('report/pos_commission', 'token=' . $this->session->data['token'] . '&sort=date_modified' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		
		if (isset($this->request->get['filter_commission_date'])) {
			$url .= '&filter_commission_date=' . $this->request->get['filter_commission_date'];
		}
		
		if (isset($this->request->get['filter_user_id'])) {
			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $commissions_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/pos_commission', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($commissions_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($commissions_total - $this->config->get('config_limit_admin'))) ? $commissions_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $commissions_total, ceil($commissions_total / $this->config->get('config_limit_admin')));

		$data['filter_order_id'] = $filter_order_id;
		$data['filter_commission'] = $filter_commission;
		$data['filter_commission_date'] = $filter_commission_date;
		$data['filter_user_id'] = $filter_user_id;
		$data['filter_user_name'] = $filter_user_name;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('pos/report_commission_details.tpl', $data));
	}
	
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

	public function summary() {  
		$this->language->load('pos/commission');
		$this->language->load('report/sale_order');

		$this->document->setTitle($this->language->get('summary_heading_title'));
		
		$this->load->model('user/user');
		$data['users'] = $this->model_user_user->getUsers();
		
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

		if (isset($this->request->get['filter_user_id'])) {
			$filter_user_id = $this->request->get['filter_user_id'];
		}
		
		if (isset($this->request->get['filter_group'])) {
			$filter_group = $this->request->get['filter_group'];
		} else {
			$filter_group = 'week';
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
		
		if (isset($this->request->get['filter_user_id'])) {
			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
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
			'href'      => $this->url->link('report/pos_commission/summary', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);

		$timezone_offset = 0;
		if (isset($this->request->get['browser_time'])) {
			$time_query = $this->db->query("SELECT NOW() AS now");
			$time_db = strtotime($time_query->row['now']);
			$timezone_offset = intval(($this->request->get['browser_time'] / 1000 - $time_db) / 60);
		}
		
		$filter_data = array(
			'filter_date_start'	     => $filter_date_start, 
			'filter_date_end'	     => $filter_date_end,
			'timezone_offset'		 => $timezone_offset,
			'filter_group'           => $filter_group,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		if (isset($filter_user_id)) {
			$data['filter_user_id'] = $filter_user_id;
		}
		
		$this->load->model('pos/report_commission');
		
		$order_total = $this->model_pos_report_commission->getTotalOrderCommissionSummary($filter_data);
		
		$data['commissions'] = $this->model_pos_report_commission->getOrderCommissionSummary($filter_data);
		foreach ($data['commissions'] as $key => $commission) {
			$data['commissions'][$key]['date_start'] = date('Y-m-d', (strtotime($commission['date_start']) + $timezone_offset * 60));
			$data['commissions'][$key]['date_end'] = date('Y-m-d', (strtotime($commission['date_end']) + $timezone_offset * 60));
		}
		
		$data['heading_title'] = $this->language->get('summary_heading_title');
		
		$data['text_no_results'] = $this->language->get('text_no_results');
		
		$data['column_user_name'] = $this->language->get('column_user_name');
		$data['column_date_start'] = $this->language->get('column_date_start');
		$data['column_date_end'] = $this->language->get('column_date_end');
		$data['column_commission'] = $this->language->get('column_commission');
		
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_group'] = $this->language->get('entry_group');	
		$data['entry_user_id'] = $this->language->get('entry_user_id');

		$data['button_filter'] = $this->language->get('button_filter');
		
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
		
		if (isset($this->request->get['filter_user_id'])) {
			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
		}
		
		if (isset($this->request->get['filter_group'])) {
			$url .= '&filter_group=' . $this->request->get['filter_group'];
		}		

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/pos_commission/summary', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;		
		$data['filter_group'] = $filter_group;
		if (isset($filter_user_id)) {
			$data['filter_user_id'] = $filter_user_id;
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('pos/report_commission_summary.tpl', $data));
	}
}
?>