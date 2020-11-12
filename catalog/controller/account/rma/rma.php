<?php
/**
 * Webkul Software.
 * @category  Webkul
 * @author    Webkul
 * @copyright Copyright (c) 2010-2016 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
class ControllerAccountRmarma extends Controller {
	use RmaControllerTrait;

	private $error = array();
	private $data = array();

	public function index() {

		if(!$this->config->get('wk_rma_status'))
			$this->response->redirect($this->urlChange('account/login', '', true));

		if (!$this->customer->isLogged() AND !isset($this->session->data['rma_login'])) {
			$this->session->data['redirect'] = $this->urlChange('account/rma/rma', '', true);
			$this->response->redirect($this->urlChange('account/rma/rmalogin', '', true));
		}

		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');
		$this->document->addstyle('catalog/view/theme/default/stylesheet/rma/rma.css');
		$this->getlist();

	}

	private function getlist(){

		$data = array_merge($data =array(), $this->language->load('account/rma/rma'));
	  $this->document->setTitle($this->language->get('heading_title'));

		$url = '';

		$config_data = array(
			'filter_id',
			'filter_order',
			'filter_qty',
			'filter_rma_status',
			'filter_price',
			'page',
			'sort',
		);

		foreach ($config_data as $config) {
			if (isset($this->request->get[$config])) {
				$url .= '&' . $config . '=' . $this->request->get[$config];
				${$config}= $this->request->get[$config];
			} else {
				${$config} = null;
			}
		}

		if (isset($this->request->get['filter_date'])) {
			$filter_date = $this->request->get['filter_date'];
		} else {
			$filter_date = null;
		}
		
		if (isset($this->request->get['filter_date_from'])) {
			$filter_date_from = $this->request->get['filter_date_from'];
		} else {
			$filter_date_from = null;
		}
		
		if (isset($this->request->get['filter_date_to'])) {
			$filter_date_to = $this->request->get['filter_date_to'];
		} else {
			$filter_date_to = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'wro.date';
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

		if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . urlencode(html_entity_decode($this->request->get['filter_date'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date_from=' . urlencode(html_entity_decode($this->request->get['filter_date_from'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date_to=' . urlencode(html_entity_decode($this->request->get['filter_date_to'], ENT_QUOTES, 'UTF-8'));
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		$limit = $this->config->get('config_product_limit') ? $this->config->get('config_product_limit') : 20;

		$filter_data = array(
			'filter_id'                => $filter_id,
			'filter_qty'           	   => $filter_qty,
			'filter_order' 		   	   	 => $filter_order,
			'filter_price'             => $filter_price,
			'filter_rma_status'        => $filter_rma_status,
			'filter_date'              => $filter_date,
			'filter_date_from'         => $filter_date_from,
			'filter_date_to'           => $filter_date_to,
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $limit,
			'limit'                    => $limit,
			'email'					   				 => $this->customer->getEmail() ? $this->customer->getEmail() : $this->session->data['rma_login']
		);

		$data['sort_id'] = $this->urlChange('account/rma/rma', ''. '&sort=wro.id'.$url , '', true);
		$data['sort_qty'] = $this->urlChange('account/rma/rma', '' . '&sort=wrp.quantity' . $url, '', true);
		$data['sort_order'] = $this->urlChange('account/rma/rma', ''. '&sort=wro.order_id'.$url , '', true);
		$data['sort_price'] = $this->urlChange('account/rma/rma', '' . '&sort=ot.value' . $url, '', true);
		$data['sort_date'] = $this->urlChange('account/rma/rma', '' . '&sort=wro.date' . $url, '', true);
		$data['sort_rma_status'] = $this->urlChange('account/rma/rma', '' . '&sort=wrs.name' . $url, '', true);

		$this->load->model('account/rma/rma');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
   		'text'      => $this->language->get('text_home'),
			'href'      => $this->urlChange('common/home'.$url, '', true),
  		'separator' => false
		);

		$data['breadcrumbs'][] = array(
   		'text'      => $this->language->get('heading_title'),
			'href'      => $this->urlChange('account/rma/rma'.$url, '', true),
  		'separator' => ' :: '
		);

		$data['newrma'] = $this->urlChange('account/rma/addrma', '', true);

		$this->load->model('localisation/order_status');
		$data['rma_status'] = $this->model_localisation_order_status->getOrderStatuses();

		$data['rma_ress'] = array();


		$results = $this->model_account_rma_rma->ramDetails($filter_data);
		$resultrmatotal = $this->model_account_rma_rma->rmaTotal($filter_data);

		$data['defaultRmaStatus'] = $this->model_account_rma_rma->defaultRmaStatus();
		$data['solveRmaStatus'] = $this->model_account_rma_rma->solveRmaStatus();
		$data['cancelRmaStatus'] = $this->model_account_rma_rma->cancelRmaStatus();

	  foreach ($results as $result) {

			$action = array();
			$action[] = array(
				'text' => $this->language->get('text_viewdetails'),
				'href' => $this->urlChange('account/rma/viewrma'.'&vid=' . $result['id'], '', true)
			);

			if(!$result['quantity'])
				continue;

			$result_products = $this->model_account_rma_rma->viewProducts($result['id']);

			$quantity = $product = $reason = '';
			foreach ($result_products as $products) {
				$product .= $products['name'].' <br/> ';
				$reason .= $products['reason'].' <br/> ';
				$quantity .= $products['quantity'].' <br/> ';
			}

			if($result['cancel_rma']){
				$result['rma_status'] = $this->language->get('text_canceled_customer');
				$result['color'] = 'red';
			}
			if($result['solve_rma']){
				$result['rma_status'] = $this->language->get('text_solved_customer');
				$result['color'] = ' green';
			}
			if($result['admin_return']){
				$result['solve_rma'] = 1;
				$result['color'] = 'green';
				$result['rma_status'] = $this->language->get('text_admin_return');
			}
			if($data['cancelRmaStatus'] == $result['admin_status'] && $result['admin_status']){
				$result['cancel_rma'] = 1;
				$result['rma_status'] = $this->language->get('text_canceled');
				$result['color'] = 'red';
			}
			if($data['solveRmaStatus'] == $result['admin_status'] && $result['admin_status']){
				$result['solve_rma'] = 1;
				$result['rma_status'] = $this->language->get('text_solved');
				$result['color'] = 'green';
			}


  		$data['rma_ress'][] = array(
				'selected'=>False,
				'id' => $result['id'],
				'order_id' => $result['order_id'],
				'product' => $product,
				'reason' => $reason,
				'price' => Membership::currencyFormat($result['value'],$this->session->data['currency']),
				'date' => $result['date'],
				'color' => $result['color'],
				'cancel_rma_link' => $this->urlChange('account/rma/rma/cnclrma&rma='.$result['id'], '' .  $url, '', true),
				'cancel_rma' => $result['cancel_rma'],
				'solve_rma' => $result['solve_rma'],
				'rma_status' => $result['rma_status'],
				'quantity' => $quantity,
				'action'     => $action,
			);
		}

		if (isset($this->session->data['rma_error'])) {
			$this->error['warning'] = $this->session->data['rma_error'];
			unset($this->session->data['rma_error']);
		}

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

		$config_data = array(
			'filter_id',
			'filter_order',
			'filter_qty',
			'filter_rma_status',
			'filter_price',
			'sort',
		);

		foreach ($config_data as $config) {
			if (isset($this->request->get[$config])) {
				$url .= '&' . $config . '=' . $this->request->get[$config];
			}
		}

		if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . urlencode(html_entity_decode($this->request->get['filter_date'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_date_from'])) {
			$url .= '&filter_date_from=' . urlencode(html_entity_decode($this->request->get['filter_date_from'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_date_to'])) {
			$url .= '&filter_date_to=' . urlencode(html_entity_decode($this->request->get['filter_date_to'], ENT_QUOTES, 'UTF-8'));
		}



		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $resultrmatotal;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->urlChange('account/rma/rma'. $url,'&page={page}', '', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($resultrmatotal) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($resultrmatotal - $limit)) ? $resultrmatotal : ((($page - 1) * $limit) + $this->config->get('config_limit_admin')), $resultrmatotal, ceil($resultrmatotal / $limit));

		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['filter_id'] = $filter_id;
		$data['filter_qty'] = $filter_qty;
		$data['filter_order'] = $filter_order;
		$data['filter_price'] = $filter_price;
		$data['filter_rma_status'] = $filter_rma_status;
		$data['filter_date'] = $filter_date;
		$data['filter_date_from'] = $filter_date_from;
		$data['filter_date_to'] = $filter_date_to;

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (version_compare(VERSION, '2.2', '>=')) {
			$this->response->setOutput($this->load->view('account/rma/rma', $data));
		} else {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/rma/rma.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/rma/rma.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/account/rma/rma.tpl', $data));
			}
		}

	}

	public function cnclrma() {

		$this->load->model('account/rma/rma');
		$this->language->load('account/rma/rma');

		if(isset($this->request->get['rma']) AND $this->request->get['rma']){
			if($this->customer->getId() || isset($this->session->data['rma_login'])){
			    $results = $this->model_account_rma_rma->viewRmaid($this->request->get['rma']);
				if($results){
					$getCustomerCancel = $this->model_account_rma_rma->updateRmaSta('cancel',(int)$this->request->get['rma']);
					$this->session->data['success'] = $this->language->get('text_cancel_rma');
				}else{
					$this->session->data['rma_error'] = $this->language->get('error_rma_delete');
				}
			}else{
				$this->session->data['rma_error'] = $this->language->get('error_rma_delete');
			}
		}
		$this->response->redirect($this->urlChange('account/rma/rma', '', true));
	}

	public function addcons() {

		$this->load->model('account/rma/rma');

		$json = array();

		if(isset($this->request->post['auth_no']) AND $this->request->post['auth_no'] AND isset($this->request->post['vid']) AND $this->request->post['vid']){
			if($this->customer->getId() || isset($this->session->data['rma_login'])){
			    $results = $this->model_account_rma_rma->viewRmaid($this->request->post['vid']);
				if($results){
					$this->model_account_rma_rma->updateRmaAuth($this->request->post['auth_no'],$this->request->post['vid']);
			    	$json['success'] = 'done';
				}
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function getorder() {
		if(isset($this->request->post['order']) && $this->request->post['order']){
			$this->load->model('account/order');
			$this->load->model('tool/upload');
			$this->load->model('account/rma/rma');

			$products = $this->model_account_rma_rma->orderprodetails((int)$this->request->post['order'],(int)$this->customer->getId());
			$order_status_id = $this->model_account_rma_rma->getOrderStatus((int)$this->request->post['order']);

			foreach ($products as $key => $product) {
				$option_data = array();

				$options = $this->model_account_order->getOrderOptions((int)$this->request->post['order'], $product['order_product_id']);

				foreach ($options as $option) {
					if ($option['type'] != 'file') {
						$value = $option['value'];
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);
						if ($upload_info) {
							$value = $upload_info['name'];
						} else {
							$value = '';
						}
					}
					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);
				}
				$products[$key]['option'] = $option_data;
				$products[$key]['order_status_id'] = $order_status_id;
			}
		  $this->response->setOutput(json_encode($products));
		}
	}
}
