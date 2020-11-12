<?php
/**
 * Webkul Software.
 * @category  Webkul
 * @author    Webkul
 * @copyright Copyright (c) 2010-2016 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
class ControllerCatalogwkrmaadmin extends Controller {

	private $error = array();
	use RmaControllerTrait;

	public function index() {

		if (!$this->config->get('wk_rma_status'))
			$this->response->redirect($this->urlChange('common/dashboard', 'token=' . $this->session->data['token'], true));

		$this->language->load('catalog/wk_rma_admin');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/wk_rma_admin');
		$this->getList();
  }



	protected function getList() {

		if (!$this->config->get('wk_rma_status'))
			$this->response->redirect($this->urlChange('common/dashboard', 'token=' . $this->session->data['token'], true));

		$data = $this->language->load('catalog/wk_rma_admin');

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_order'])) {
			$filter_order = $this->request->get['filter_order'];
		} else {
			$filter_order = null;
		}

		if (isset($this->request->get['filter_reason'])) {
			$filter_reason = $this->request->get['filter_reason'];
		} else {
			$filter_reason = null;
		}

		if (isset($this->request->get['filter_rma_status'])) {
			$filter_rma_status = $this->request->get['filter_rma_status'];
		} else {
			$filter_rma_status = null;
		}

		if (isset($this->request->get['filter_rma_status_id'])) {
			$filter_rma_status_id = $this->request->get['filter_rma_status_id'];
		} else {
			$filter_rma_status_id = 1;
		}

		if (isset($this->request->get['filter_admin_status'])) {
			$filter_admin_status = $this->request->get['filter_admin_status'];
		} else {
			$filter_admin_status = null;
		}

		if (isset($this->request->get['filter_date'])) {
			$filter_date = $this->request->get['filter_date'];
		} else {
			$filter_date = null;
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

		$limit = $this->config->get('config_limit_admin') ? $this->config->get('config_limit_admin') : 20;

		$filter_data = array(
			'filter_name'              => $filter_name,
			'filter_order' 		   	     => $filter_order,
			'filter_reason'            => $filter_reason,
			'filter_rma_status'        => $filter_rma_status,
			'filter_admin_status' 	   => $filter_admin_status,
			'filter_rma_status_id'     => $filter_rma_status_id,
			'filter_date'              => $filter_date,
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $limit,
			'limit'                    => $limit
		);

		$url = '';


		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order'])) {
			$url .= '&filter_order=' . $this->request->get['filter_order'];
		}

		if (isset($this->request->get['filter_reason'])) {
			$url .= '&filter_reason=' . $this->request->get['filter_reason'];
		}

		if (isset($this->request->get['filter_rma_status_id'])) {
			$url .= '&filter_rma_status_id=' . $this->request->get['filter_rma_status_id'];
		}

		if (isset($this->request->get['filter_rma_status'])) {
			$url .= '&filter_rma_status=' . $this->request->get['filter_rma_status'];
		}

		if (isset($this->request->get['filter_admin_status'])) {
			$url .= '&filter_admin_status=' . $this->request->get['filter_admin_status'];
		}

		if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . urlencode(html_entity_decode($this->request->get['filter_date'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		$data['sort_name'] = $this->urlChange('catalog/wk_rma_admin', 'token=' . $this->session->data['token']. '&sort=c.firstname'.$url , 'SSL');
		$data['sort_product'] = $this->urlChange('catalog/wk_rma_admin', 'token=' . $this->session->data['token'] . '&sort=wro.id' . $url, 'SSL');
		$data['sort_order'] = $this->urlChange('catalog/wk_rma_admin', 'token=' . $this->session->data['token']. '&sort=wro.order_id'.$url , 'SSL');
		$data['sort_reason'] = $this->urlChange('catalog/wk_rma_admin', 'token=' . $this->session->data['token'] . '&sort=wrr.id' . $url, 'SSL');
		$data['sort_admin_status'] = $this->urlChange('catalog/wk_rma_admin', 'token=' . $this->session->data['token']. '&sort=wrs.admin_status'.$url , 'SSL');
		$data['sort_date'] = $this->urlChange('catalog/wk_rma_admin', 'token=' . $this->session->data['token'] . '&sort=wro.date' . $url, 'SSL');

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
					'href'      => $this->urlChange('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
					'href'      => $this->urlChange('catalog/wk_rma_admin', 'token=' . $this->session->data['token']. $url , 'SSL'),
      		'separator' => ' :: '
   		);

		$product_total = $this->model_catalog_wk_rma_admin->viewtotalentry($filter_data);

		$results = $this->model_catalog_wk_rma_admin->viewtotal($filter_data);

		$this->load->model('catalog/wk_customer_orders');
    $data['customers'] = $this->model_catalog_wk_customer_orders->getAllCustomers();

		$data['delete'] = $this->urlChange('catalog/wk_rma_admin/delete', 'token=' . $this->session->data['token'], 'SSL');

		$data['result_rmaadmin'] = array();

		$data['defaultRmaStatus'] = $this->model_catalog_wk_rma_admin->defaultRmaStatus();
		$data['solveRmaStatus'] = $this->model_catalog_wk_rma_admin->solveRmaStatus();
		$data['cancelRmaStatus'] = $this->model_catalog_wk_rma_admin->cancelRmaStatus();

		if($results)
			foreach ($results as $result) {
				$action = array();

				$action[] = array(
					'text' => $this->language->get('text_edit'),
					'href' => $this->urlChange('catalog/wk_rma_admin/getForm', 'token=' . $this->session->data['token'] .'&id=' . $result['id'], 'SSL')
				);

				$result_products = $this->model_catalog_wk_rma_admin->viewProducts($result['id']);

				$quantity = $product = $reason = '';
				foreach ($result_products as $products) {
					$product .= $products['name'].' <br/> ';
					$reason .= $products['reason'].' <br/> ';
					$quantity .= $products['quantity'].' <br/> ';
				}

				$rma_status = $result['rma_status'];

				$result['rma_status'] = $result['admin_status'];

				if($result['cancel_rma']){
					$result['rma_status'] = $this->language->get('text_canceled_customer');
					$result['color'] = 'red';
				}
				if($result['solve_rma']){
					$result['rma_status'] = $this->language->get('text_solved_customer');
					$result['color'] = ' green';
				}

				if($result['admin_return']){
					$result['color'] = 'green';
					$result['rma_status'] = $this->language->get('text_quantity');
				}

				if($data['cancelRmaStatus'] == $rma_status && $rma_status){
					$result['cancel_rma'] = 1;
					$result['rma_status'] = $this->language->get('text_canceled');
					$result['color'] = 'red';
				}
				if($data['solveRmaStatus'] == $rma_status && $rma_status){
					$result['solve_rma'] = 1;
					$result['rma_status'] = $this->language->get('text_solved');
					$result['color'] = 'green';
				}

				$data['result_rmaadmin'][] = array(
						'selected'=>False,
						'id' => $result['id'],
						'name' => $result['name'],
						'product' => $product,
						'type' => $result['return_pos'],
						'oid' => $result['order_id'],
						'color' => $result['color'],
						'reason' => $reason,
						'quantity' => $quantity,
						'date' => $result['date'],
						'rma_status' => $result['rma_status'],
						'action' => $action
					);

			}

		$data['reasons'] = $this->model_catalog_wk_rma_admin->getCustomerReason();
		$data['admin_status'] = $this->model_catalog_wk_rma_admin->getAdminStatus();

		$data['admin_status'][] = array(
			'name' => $this->language->get('text_canceled_customer'),
			'id'   => 'cancel'
		);
		$data['admin_status'][] = array(
			'name' => $this->language->get('text_solved_customer'),
			'id'   => 'solve'
		);
		$data['admin_status'][] = array(
			'name' => $this->language->get('text_quantity'),
			'id'   => 'admin'
		);

		$this->load->model('localisation/order_status');
		$data['rma_status'] = $this->model_localisation_order_status->getOrderStatuses();

 		$data['token'] = $this->session->data['token'];

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['warning'])) {
			$data['error_warning'] = $this->session->data['warning'];
			unset($this->session->data['warning']);
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order'])) {
			$url .= '&filter_order=' . $this->request->get['filter_order'];
		}

		if (isset($this->request->get['filter_reason'])) {
			$url .= '&filter_reason=' . $this->request->get['filter_reason'];
		}

		if (isset($this->request->get['filter_rma_status'])) {
			$url .= '&filter_rma_status=' . $this->request->get['filter_rma_status'];
		}

		if (isset($this->request->get['filter_admin_status'])) {
			$url .= '&filter_admin_status=' . $this->request->get['filter_admin_status'];
		}

		if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . urlencode(html_entity_decode($this->request->get['filter_date'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_rma_status_id'])) {
			$url .= '&filter_rma_status_id=' . $this->request->get['filter_rma_status_id'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['invoice'] = $this->urlChange('catalog/wk_rma_admin/invoice', 'token=' . $this->session->data['token'], 'SSL');

		if (version_compare(VERSION,'2.1','>=')) {
			$data['customer_link'] = 'customer/customer';
		} else {
			$data['customer_link'] = 'sale/customer';
		}

		$limit = $this->config->get('config_limit_admin') ? $this->config->get('config_limit_admin') : 20;

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->urlChange('catalog/wk_rma_admin', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['filter_name'] = $filter_name;
		$data['filter_order'] = $filter_order;
		$data['filter_reason'] = $filter_reason;
		$data['filter_rma_status'] = $filter_rma_status;
		$data['filter_admin_status'] = $filter_admin_status;
		$data['filter_rma_status_id'] = $filter_rma_status_id;
		$data['filter_date'] = $filter_date;


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		if (isset($this->request->get['out_req'])){
			$data['column_left'] = "";
			$data['footer'] = "";
		}


		$this->response->setOutput($this->load->view('catalog/wk_rma_admin.tpl', $data));
  }

  public function getForm() {

		if (!$this->config->get('wk_rma_status'))
			$this->response->redirect($this->urlChange('common/dashboard', 'token=' . $this->session->data['token'], true));

	  $rma_id = 0;
	  $notify_cust = 0;

		if(isset($this->request->get['id'])){
			$rma_id = (int)$this->request->get['id'];
		}

		if (isset($this->request->post['input-notify_message'])) {
			
			$notify_cust = $this->request->post['input-notify_message'];
			//die("HERE CODE".$notify_cust);
		}else{
			
			$notify_cust = 0;
			//echo $notify_cust;
			//die("CHECK WITH ME". $notify_cust);
		}
		
		//die($notify_cust);

		//die($this->request->post['input-notify_message']);

		$data = $this->language->load('catalog/wk_rma_admin');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/wk_rma_admin');

		$this->load->model('tool/image');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate('catalog/wk_rma_admin') && isset($this->request->post['rma_id']) && (int)$this->request->post['rma_id']) {

			$file_name = '';
			if($this->request->files['up_file']['name']){

				if ($this->request->files['up_file']['name']) {
					$files = $this->validateImage($this->request->files['up_file']);
				} else {
					$files = array();
				}

				$result = $this->model_catalog_wk_rma_admin->getRmaOrderid($this->request->post['rma_id']);
				if($result && $result['images'] && $files){
					foreach ($files as $key => $file) {
						$file_name = $file['name'];
						$target = DIR_IMAGE.''.$result['images']."/files/".$file['name'];
						@move_uploaded_file($file['tmp_name'],$target);
					}
					$this->model_catalog_wk_rma_admin->updateAdminStatus($this->request->post['wk_rma_admin_msg'],$this->request->post['wk_rma_admin_adminstatus'],$this->request->post['rma_id'],$notify_cust,$file_name);
					$this->session->data['success'] = $this->language->get('text_success');
					$this->response->redirect($this->urlChange('catalog/wk_rma_admin/getForm', '&token=' . $this->session->data['token'] . '&id=' . (int)$this->request->post['rma_id'] . '&tab=messages'));
				}
			} else {
				$this->model_catalog_wk_rma_admin->updateAdminStatus($this->request->post['wk_rma_admin_msg'],$this->request->post['wk_rma_admin_adminstatus'],$this->request->post['rma_id'],$notify_cust,$file_name);
				$this->session->data['success'] = $this->language->get('text_success');
				$this->response->redirect($this->urlChange('catalog/wk_rma_admin/getForm', '&token=' . $this->session->data['token'] . '&id=' . (int)$this->request->post['rma_id'] . '&tab=messages'));
			}
		}

  	if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_message'])) {
			$filter_message = $this->request->get['filter_message'];
		} else {
			$filter_message = null;
		}

		if (isset($this->request->get['filter_date'])) {
			$filter_date = $this->request->get['filter_date'];
		} else {
			$filter_date = null;
		}

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

		$filter_data = array(
			'filter_name'              => $filter_name,
			'filter_message'           => $filter_message,
			'filter_date'              => $filter_date,
			'filter_id'				         => $rma_id,
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                    => $this->config->get('config_limit_admin')
		);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_message'])) {
			$url .= '&filter_message=' . urlencode(html_entity_decode($this->request->get['filter_message'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . urlencode(html_entity_decode($this->request->get['filter_date'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		$url .= '&id=' . $rma_id;

		$data['sort_name'] = $this->urlChange('catalog/wk_rma_admin/getForm', 'token=' . $this->session->data['token']. '&sort=wrm.writer'.$url , 'SSL');
		$data['sort_message'] = $this->urlChange('catalog/wk_rma_admin/getForm', 'token=' . $this->session->data['token'] . '&sort=wrm.message' . $url, 'SSL');
		$data['sort_date'] = $this->urlChange('catalog/wk_rma_admin/getForm', 'token=' . $this->session->data['token'] . '&sort=wrm.date' . $url, 'SSL');

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		/**
		 * voucher code here
		 * @var [type]
		 */
		$this->load->model('sale/voucher_theme');

		$data['voucher_themes'] = $this->model_sale_voucher_theme->getVoucherThemes();

		$data['breadcrumbs'] = array();

 		$data['breadcrumbs'][] = array(
     		'text'      => $this->language->get('text_home'),
				'href'      => $this->urlChange('common/dashboard', 'token=' . $this->session->data['token'].$url, 'SSL'),
    		'separator' => false
 		);

 		$data['breadcrumbs'][] = array(
     		'text'      => $this->language->get('heading_title'),
				'href'      => $this->urlChange('catalog/wk_rma_admin', 'token=' . $this->session->data['token'].$url, 'SSL'),
    		'separator' => ' :: '
 		);

		$result = $this->model_catalog_wk_rma_admin->getRmaOrderid($rma_id);

		if(!$result) {
			$this->session->data['warning'] = $this->language->get('text_rma_not_found');
			$this->response->redirect($this->urlChange('catalog/wk_rma_admin', 'token=' . $this->session->data['token'], true));
		}

		$data['voucher_total'] = $this->model_catalog_wk_rma_admin->getVoucherTotal($rma_id);

		$data['voucher_total'] = $this->currency->format($data['voucher_total'], $this->config->get('config_currency'));

		$results_message = $this->model_catalog_wk_rma_admin->viewtotalMessageBy($filter_data);
		$results_message_total = $this->model_catalog_wk_rma_admin->viewtotalNoMessageBy($filter_data);

		$data['results_message'] = $results_message;

		$attachmentLinkDir = DIR_IMAGE.$result['images'].'/files/';

		$data['attachmentLink'] = HTTP_SERVER.'../image/'.$result['images'].'/files/';

		foreach($results_message as $key => $value){
			if(!file_exists($attachmentLinkDir.$value['attachment'])){
				$data['results_message'][$key]['attachment'] = '';
			}
		}

		if (isset($this->request->post['wk_rma_admin_msg'])) {
			$data['wk_rma_admin_msg'] = $this->request->post['wk_rma_admin_msg'];
		} else {
			$data['wk_rma_admin_msg'] = '';
		}

		if(!$rma_id) {
			$data['save'] = $this->urlChange('catalog/wk_rma_admin/getForm', 'token=' . $this->session->data['token'] . '&tab=messages', 'SSL');
		} else {
			$data['save'] = $this->urlChange('catalog/wk_rma_admin/getForm', 'token=' . $this->session->data['token'].'&id='.$rma_id . '&tab=messages', 'SSL');
		}

		$data['default_text_desc'] = sprintf($this->language->get('default_text_desc'),$rma_id);

		$data['invoice'] = $this->urlChange('catalog/wk_rma_admin/invoice&rma_id='.$rma_id, 'token=' . $this->session->data['token'], 'SSL');
		$data['back'] = $this->urlChange('catalog/wk_rma_admin', 'token=' . $this->session->data['token'], 'SSL');
		$data['savelabel'] = $this->urlChange('catalog/wk_rma_admin/saveLabel&id='.$rma_id, 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['tab'])) {
			$data['tab'] = $this->request->get['tab'];
		} else {
			$data['tab'] = '';
		}

		$data['vid'] = $rma_id;

		$data['result_rmaadmin'] = $data['result_rmaadmin_images'] = $data['result_products'] = array();

		$path = $result['images'].'/';
		$data['shipping_label'] = '';
		if ($result) {

	    $this->document->addScript('../catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
			$this->document->addStyle('../catalog/view/javascript/jquery/magnific/magnific-popup.css');

			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->urlChange('catalog/wk_rma_admin', 'token=' . $this->session->data['token'] .'&id=' . $rma_id, 'SSL')
			);

			$customerDetails = $this->model_catalog_wk_rma_admin->viewCustomerDetails($result['order_id']);

			$data['defaultRmaStatus'] = $this->model_catalog_wk_rma_admin->defaultRmaStatus();
			$data['solveRmaStatus'] = $this->model_catalog_wk_rma_admin->solveRmaStatus();
			$data['cancelRmaStatus'] = $this->model_catalog_wk_rma_admin->cancelRmaStatus();

			if($customerDetails){

				if($result['admin_return']){
					$result['color'] = 'green';
					$result['rma_status'] = $this->language->get('text_admin_return_text');
				}

				if($result['cancel_rma']){
					$result['rma_status'] = $this->language->get('text_canceled_customer');
					$result['color'] = 'red';
				}
				if($result['solve_rma']){
					$result['rma_status'] = $this->language->get('text_solved_customer');
					$result['color'] = ' green';
				}

				if($data['solveRmaStatus'] == $result['admin_st'] && $result['admin_st']){
					$result['cancel_rma'] = 1;
					$result['rma_status'] = $this->language->get('text_canceled');
					$result['color'] = 'red';
				}

				if($data['solveRmaStatus'] == $result['admin_st'] && $result['admin_st']){
					$result['solve_rma'] = 1;
					$result['rma_status'] = $this->language->get('text_solved');
					$result['color'] = ' green';
				}

				if($result['shipping_label']){
					if(!file_exists($attachmentLinkDir.$result['shipping_label'])){
						$result['shipping_label'] = '';
					}else
					$data['shipping_label'] = $result['shipping_label'] = $this->model_tool_image->resize($path.'files/'.$result['shipping_label'],300,300);
				}

				$data['result_rmaadmin'] = array(
					'selected'=>False,
					'id' => $rma_id,
					'name' => $customerDetails['firstname'].' '.$customerDetails['lastname'],
					'oid' => '# '.$result['order_id'],
					'orderurl' => $this->urlChange('sale/order/info&order_id='.$result['order_id'].'&token='. $this->session->data['token']),
					'date' => $result['date'],
					'admin_status' => $result['admin_st'],
					'astatus' => $result['admin_status'],
					'rmastatus' => $result['rma_status'],
					'add_info' => $result['add_info'],
					'auth_no' => $result['rma_auth_no'],
					'color' =>  $result['color'],
					'shipping_label' => $result['shipping_label'],
					'action'     => $action
				);
				$result_products = $this->model_catalog_wk_rma_admin->viewProducts($rma_id);
				$data['result_rmaadmin_images'] = $this->getFolderImage($result['images']);

			}

		}

		$data['admin_status'] = $this->model_catalog_wk_rma_admin->getAdminStatus();

 		$data['token'] = $this->session->data['token'];

		if (isset($this->session->data['error_warning'])) {
			$this->error['warning'] = $this->session->data['error_warning'];
			unset($this->session->data['error_warning']);
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

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_message'])) {
			$url .= '&filter_message=' . urlencode(html_entity_decode($this->request->get['filter_message'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . urlencode(html_entity_decode($this->request->get['filter_date'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$url .= '&id=' . $rma_id;

		$limit = $this->config->get('config_limit_admin') ?  $this->config->get('config_limit_admin') : 20;

		$pagination = new Pagination();
		$pagination->total = $results_message_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->urlChange('catalog/wk_rma_admin/getForm', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		if (isset($this->request->post['wk_rma_admin_adminstatus'])) {
			$data['result_rmaadmin']['admin_status'] = $this->request->post['wk_rma_admin_adminstatus'];
		}

		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($results_message_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($results_message_total - $limit)) ? $results_message_total : ((($page - 1) * $limit) + $limit), $results_message_total, ceil($results_message_total / $limit));

		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['filter_name'] = $filter_name;
		$data['filter_message'] = $filter_message;
		$data['filter_date'] = $filter_date;

		$data['checkIfCustomer'] = $this->model_catalog_wk_rma_admin->checkIfCustomer($rma_id);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		if (isset($this->request->get['out_req'])){
			$data['column_left'] = "";
			$data['footer'] = "";
		}

		$this->response->setOutput($this->load->view('catalog/wk_rma_admin_details.tpl', $data));
  }

  public function invoice() {

		if (!$this->config->get('wk_rma_status'))
			$this->response->redirect($this->urlChange('common/dashboard', 'token=' . $this->session->data['token'], true));

		$data = array_merge($data= array(), $this->language->load('sale/order'));

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$data['base'] = HTTPS_SERVER;
		} else {
			$data['base'] = HTTP_SERVER;
		}

		$data['returned_check'] = false;

		$data = array_merge($data, $this->language->load('catalog/wk_rma_admin'));

		$data['language'] = isset($this->session->data['language']) ? $this->session->data['language'] : 'english';

		$this->load->model('sale/order');
		$this->load->model('setting/setting');
		$this->load->model('catalog/wk_rma_admin');
		$this->load->model('tool/image');
		$data['orders'] = array();

		$orders = array();

		$rma_order = array();

		if (isset($this->request->post['selected'])) {
			$rma_order = $this->request->post['selected'];
		} elseif (isset($this->request->get['rma_id'])) {
			$rma_order[] = $this->request->get['rma_id'];
		}

		$data['logo'] = '';

		if($this->config->get('config_logo'))
			$data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'),200,200);

		foreach ($rma_order as $rma) {

			$rma_details = $this->model_catalog_wk_rma_admin->getRmaOrderid($rma);

			$order_id = 0;

			if($rma_details AND isset($rma_details['order_id']))
				$order_id = $rma_details['order_id'];

			$order_info = $this->model_sale_order->getOrder($order_id);

			$total_data = 0;

			if ($order_info) {

				$store_info = $this->model_setting_setting->getSetting('config', $order_info['store_id']);

				if ($store_info) {
					$store_address = $store_info['config_address'];
					$store_email = $store_info['config_email'];
					$store_telephone = $store_info['config_telephone'];
					$store_fax = $store_info['config_fax'];
				} else {
					$store_address = $this->config->get('config_address');
					$store_email = $this->config->get('config_email');
					$store_telephone = $this->config->get('config_telephone');
					$store_fax = $this->config->get('config_fax');
				}

				if ($order_info['invoice_no']) {
					$invoice_no = $order_info['invoice_prefix'] . $order_info['invoice_no'];
				} else {
					$invoice_no = '';
				}

				if ($order_info['shipping_address_format']) {
					$format = $order_info['shipping_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}

				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				);

				$replace = array(
					'firstname' => $order_info['shipping_firstname'],
					'lastname'  => $order_info['shipping_lastname'],
					'company'   => $order_info['shipping_company'],
					'address_1' => $order_info['shipping_address_1'],
					'address_2' => $order_info['shipping_address_2'],
					'city'      => $order_info['shipping_city'],
					'postcode'  => $order_info['shipping_postcode'],
					'zone'      => $order_info['shipping_zone'],
					'zone_code' => $order_info['shipping_zone_code'],
					'country'   => $order_info['shipping_country']
				);

				$shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				if ($order_info['payment_address_format']) {
					$format = $order_info['payment_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}

				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				);

				$replace = array(
					'firstname' => $order_info['payment_firstname'],
					'lastname'  => $order_info['payment_lastname'],
					'company'   => $order_info['payment_company'],
					'address_1' => $order_info['payment_address_1'],
					'address_2' => $order_info['payment_address_2'],
					'city'      => $order_info['payment_city'],
					'postcode'  => $order_info['payment_postcode'],
					'zone'      => $order_info['payment_zone'],
					'zone_code' => $order_info['payment_zone_code'],
					'country'   => $order_info['payment_country']
				);

				$payment_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				$product_data = array();

				//get RMA products instead of total Order products
				$products = $this->model_catalog_wk_rma_admin->getOrderProducts($order_id,$rma);

				foreach ($products as $product) {
					$option_data = array();

					$options = $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);

					foreach ($options as $option) {
						if ($option['type'] != 'file') {
							$value = $option['value'];
						} else {
							$value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
						}

						$option_data[] = array(
							'name'  => $option['name'],
							'value' => $value
						);
					}

					$product_data[] = array(
						'name'     => $product['name'],
						'model'    => $product['model'],
						'option'   => $option_data,
						'quantity' => $product['returned'],
						'reason'   => $product['reason'],
						'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
						'total'    => $this->currency->format(($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0)) * $product['returned'] , $order_info['currency_code'], $order_info['currency_value'])
					);

					$total_data = $total_data + ( $product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0 )) * $product['returned'];
				}

				$voucher_data = array();

				$vouchers = $this->model_sale_order->getOrderVouchers($order_id);

				foreach ($vouchers as $voucher) {
					$voucher_data[] = array(
						'description' => $voucher['description'],
						'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
					);
				}

				$total_data_main = $this->model_sale_order->getOrderTotals($order_id);

				if($total_data_main) {
					foreach ($total_data_main as $key => $value) {
						if($value['code']=='sub_total')
							$total_data_main[$key]['text'] = $this->currency->format($total_data, $order_info['currency_code'], $order_info['currency_value']);
						elseif($value['code']=='total')
							$total_data_main[$key]['text'] = $this->currency->format($total_data, $order_info['currency_code'], $order_info['currency_value']);
						else
							unset($total_data_main[$key]);
					}
				}

				$images = array();

				if($rma_details['images']){
		       		$path = 'rma/'.$rma_details['images'].'/';
	                $dir = DIR_IMAGE.'rma/'.$rma_details['images'].'/';
		       		if(file_exists($dir)){
			       		if ($dh = opendir($dir)) {
		                  while (($file = readdir($dh)) !== false) {
		                      if (!is_dir($file)) {
		                    	$images [] = $this->model_tool_image->resize($path.$file,150,150);
		                   	  }
		                  }
		                }
		             }
	       		}

	      if($rma_details['admin_return']){
					$data['returned_check'] = true;
					$rma_details['color'] = 'green';
					$rma_details['rma_status'] = $this->language->get('text_admin_return_text');
				}

				$data['orders'][] = array(
					'order_id'	         => '# '.$order_id,
					'return_qty'  		 => $this->urlChange('catalog/wk_rma_admin/returnQty&id='.$rma, 'token=' . $this->session->data['token'], 'SSL'),
					'invoice_no'         => $invoice_no,
					'date_added'   		 => $order_info['date_added'],
					'store_name'         => $order_info['store_name'],
					'store_url'          => rtrim($order_info['store_url'], '/'),
					'store_address'      => nl2br($store_address),
					'store_email'        => $store_email,
					'store_telephone'    => $store_telephone,
					'store_fax'          => $store_fax,
					'email'              => $order_info['email'],
					'telephone'          => $order_info['telephone'],
					'shipping_address'   => $shipping_address,
					'shipping_method'    => $order_info['shipping_method'],
					'payment_address'    => $payment_address,
					'payment_method'     => $order_info['payment_method'],
					'product'            => $product_data,
					'voucher'            => $voucher_data,
					'total'              => $total_data_main,
					'comment'            => nl2br($order_info['comment']),
					'add_info'			 => $rma_details['add_info'],
					'admin_status'		 => ucfirst($rma_details['admin_status']),
					'rma_status'		 => ucfirst($rma_details['rma_status']),
					'auth_no'			 => $rma_details['rma_auth_no'],
					'color'			 	 => $rma_details['color'],
					'admin_return'		 => $rma_details['admin_return'],
					'id'			     => '# '.$rma,
					'images'			 => $images,
					'tracking'			 => $rma_details['rma_auth_no'],
					'date_added_rma'     => $rma_details['date'],

				);
			}
		}

		$this->response->setOutput($this->load->view('catalog/wk_rma_invoice.tpl', $data));

	}

	/**
	 * save shipping label.
	 * @return [type] [description]
	 */

	public function saveLabel() {
		$rma_id = (int)$this->request->get['id'];

		if (!$this->config->get('wk_rma_status'))
			$this->response->redirect($this->urlChange('common/dashboard', 'token=' . $this->session->data['token'], true));

	  $this->language->load('catalog/wk_rma_admin');
		if (!$this->validate('catalog/wk_rma_admin')) {
			$this->session->data['error_warning'] = $this->language->get('error_permission');
			$this->response->redirect($this->urlChange('catalog/wk_rma_admin/getForm&id='.$rma_id, 'token=' . $this->session->data['token'] . '&tab=shipping', 'SSL'));
		}
	  $this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/wk_rma_admin');

		if ($this->request->files['shipping_label']['name']) {
			$images = $this->validateImage($this->request->files['shipping_label']);
		} else {
			$images = array();
		}
		if (isset($this->request->get['id'])) {
			$result = $this->model_catalog_wk_rma_admin->getRmaOrderid($rma_id);
		} else {
			$result = '';
		}
		$file_name = '';

		if ($this->validate('catalog/wk_rma_admin') && $result) {
			if ($images) {
				foreach ($images as $key => $image) {
					$file_name = $image['name'];
					$target = DIR_IMAGE.'rma/files/'.$image['name'];
					@mkdir(DIR_IMAGE.'rma/files/',0755,true);
					@move_uploaded_file($image['tmp_name'],$target);
				}
			} else if(isset($this->request->post['selected']) && $this->request->post['selected']) {
				$file_name = $this->request->post['selected'];
			}
			if (!is_dir(DIR_IMAGE.$result['images']."/files/")) {
				@mkdir(DIR_IMAGE.$result['images']."/files/",0755,true);
			}
			$target = DIR_IMAGE.$result['images']."/files/".$file_name;
			if(file_exists(DIR_IMAGE.'rma/files/'.$file_name)){
				@copy(DIR_IMAGE.'rma/files/'.$file_name,$target);
			} else {
				$file_name = '';
			}
		}

		if ($file_name){
			$this->model_catalog_wk_rma_admin->addLabel($this->request->get['id'],$file_name,$result['images']);
			$this->session->data['success'] = $this->language->get('text_success');
		} else {
			if (isset($this->error['warning'])) {
				$this->session->data['error_warning'] = $this->error['warning'];
			} else {
				$this->session->data['error_warning'] = $this->language->get('error_label');
			}
		}

    $this->response->redirect($this->urlChange('catalog/wk_rma_admin/getForm&id='.$rma_id, 'token=' . $this->session->data['token'] . '&tab=shipping', 'SSL'));
  }

	public function returnQty() {

		if (!$this->config->get('wk_rma_status'))
			$this->response->redirect($this->urlChange('common/dashboard', 'token=' . $this->session->data['token'], true));

	  $data = array_merge($data = array(),$this->language->load('catalog/wk_rma_admin'));
	  $this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/wk_rma_admin');

		if (isset($this->request->get['id']) && $this->validate('catalog/wk_rma_admin')) {
			$this->model_catalog_wk_rma_admin->returnQty($this->request->get['id']);
			$this->session->data['success'] = $this->language->get('text_success');
			$url='';
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->response->redirect($this->urlChange('catalog/wk_rma_admin', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		} else {
			$this->session->data['error_warning'] = $this->language->get('error_permission');
		}
    $this->getList();
  }

  public function delete() {

		if (!$this->config->get('wk_rma_status'))
			$this->response->redirect($this->urlChange('common/dashboard', 'token=' . $this->session->data['token'], true));

    $this->language->load('catalog/wk_rma_admin');
    $this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/wk_rma_admin');
		if (isset($this->request->post['selected']) && $this->validate('catalog/wk_rma_admin')) {
			foreach ($this->request->post['selected'] as $id) {
				$this->model_catalog_wk_rma_admin->deleteentry($id);
	  	}
			$this->session->data['success'] = $this->language->get('text_success');
			$url='';
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->response->redirect($this->urlChange('catalog/wk_rma_admin', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		} else {
			$this->session->data['error_warning'] = $this->language->get('error_selected');
			if (!$this->validate('catalog/wk_rma_admin')) {
				$this->session->data['error_warning'] = $this->language->get('error_permission');
			}
		}
    $this->getList();
  }

	public function addTransaction() {

		if (!$this->config->get('wk_rma_status'))
			$this->response->redirect($this->urlChange('common/dashboard', 'token=' . $this->session->data['token'], true));

		if (version_compare(VERSION,'2.1','>=')) {
			$this->load->language('customer/customer');
		} else {
			$this->load->language('sale/customer');
		}


		$json = array();

		if (!$this->validate('catalog/wk_rma_admin')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('catalog/wk_rma_admin');
			$this->model_catalog_wk_rma_admin->addTransaction($this->request->get['rma_id'],$this->request->post['description'], (float)$this->request->post['amount']);
			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function addVoucher() {

		if (!$this->config->get('wk_rma_status'))
			$this->response->redirect($this->urlChange('common/dashboard', 'token=' . $this->session->data['token'], true));

			if (version_compare(VERSION,'2.1','>=')) {
				$this->load->language('customer/customer');
			} else {
				$this->load->language('sale/customer');
			}

		$json = array();

		if (!$this->validate('catalog/wk_rma_admin')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('catalog/wk_rma_admin');
			$data['rma_id'] = (int)$this->request->get['rma_id'];
			$data['message'] = (int)$this->request->post['message'];
			$data['amount'] = (int)$this->request->post['amount'];
			$this->model_catalog_wk_rma_admin->addVoucher($data);
			$json['success'] = $this->currency->format($data['amount'],$this->config->get('config_currency'));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function transaction() {

		if (version_compare(VERSION,'2.1','>=')) {
			$this->load->language('customer/customer');
		} else {
			$this->load->language('sale/customer');
		}
		$this->load->model('catalog/wk_rma_admin');

		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_balance'] = $this->language->get('text_balance');

		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_description'] = $this->language->get('column_description');
		$data['column_amount'] = $this->language->get('column_amount');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['error_warning'] = '';
		$data['success'] = '';

		$data['transactions'] = array();

		$results = $this->model_catalog_wk_rma_admin->getTransactions($this->request->get['rma_id'], ($page - 1) * 10, 10);

		foreach ($results as $result) {
			$data['transactions'][] = array(
				'amount'      => $this->currency->format($result['amount'], $this->config->get('config_currency')),
				'description' => $result['description'],
				'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

		$data['balance'] = $this->currency->format($this->model_catalog_wk_rma_admin->getTransactionTotal($this->request->get['rma_id']), $this->config->get('config_currency'));

		$transaction_total = $this->model_catalog_wk_rma_admin->getTotalTransactions($this->request->get['rma_id']);

		$pagination = new Pagination();
		$pagination->total = $transaction_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->urlChange('catalog/wk_rma_admin/transaction', 'token=' . $this->session->data['token'] . '&rma_id=' . $this->request->get['rma_id'] . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($transaction_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($transaction_total - 10)) ? $transaction_total : ((($page - 1) * 10) + 10), $transaction_total, ceil($transaction_total / 10));

		if (version_compare(VERSION,'2.1','>=')) {
			$this->response->setOutput($this->load->view('customer/customer_transaction.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('sale/customer_transaction.tpl', $data));
		}
	}

}
