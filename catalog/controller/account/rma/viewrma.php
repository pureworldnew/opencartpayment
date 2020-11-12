<?php
/**
 * Webkul Software.
 * @category  Webkul
 * @author    Webkul
 * @copyright Copyright (c) 2010-2016 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
class ControllerAccountRmaviewrma extends Controller {
	use RmaControllerTrait;

	private $error = array();

	public function index() {

		if(!$this->config->get('wk_rma_status'))
			$this->response->redirect($this->urlChange('account/login', '', true));

		if (!$this->customer->isLogged() AND !isset($this->session->data['rma_login'])) {
			$this->session->data['redirect'] = $this->urlChange('account/rma/viewrma&vid='.$this->request->get['vid'], '', true);
			$this->response->redirect($this->urlChange('account/rma/rmalogin', '', true));
		}

		$this->load->model('account/rma/rma');
		$this->load->model('tool/image');

		$data = array_merge($data = array(), $this->language->load('account/rma/viewrma'));

	  $this->document->setTitle($this->language->get('heading_title'));

		if(!isset($this->request->get['vid']))
			$this->response->redirect($this->urlChange('account/rma/rma', '', true));

		$this->document->addstyle('catalog/view/theme/default/stylesheet/rma/rma.css');

		$vid = $data['vid'] = (int)$this->request->get['vid'];
		$result = $this->model_account_rma_rma->viewRmaid($vid);
		if(!$result){
			$this->session->data['rma_error'] = $this->language->get('text_error');
			$this->response->redirect($this->urlChange('account/rma/rma', '', true));
		}
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			if(isset($this->request->post['solve'])){
				if(!$result){
					$this->error['warning'] = $this->language->get('text_error');
				}else{
					$this->model_account_rma_rma->updateRmaSta($this->request->post['solve'],$this->request->post['wk_viewrma_grp_id']);
					$this->session->data['success'] = $this->language->get('text_success');
					$this->response->redirect($this->urlChange('account/rma/viewrma&vid='.$this->request->post['wk_viewrma_grp_id'] , '', '', true));
				}
			}

			if(isset($this->request->post['wk_view_message'])){
				if(!$result){
					$this->session->data['rma_error'] = $this->language->get('text_error');
					$this->response->redirect($this->urlChange('account/rma/rma', '', true));
				}
				$file_name = '';
				/**
				 * customer message code
				 * @var [type]
				 */
				if($this->request->files['up_file']['name']){
					$file = $this->request->files['up_file'];
					// This is used for file validation of uploaded file.Uploaded file must not be PHP file.
					if($this->fileValidation($file)){
						$file_name = $file['name'];
						$target = DIR_IMAGE.$result['images']."/files/".$file['name'];
						@move_uploaded_file($file['tmp_name'],$target);
						$writer = 'me';
						$this->model_account_rma_rma->insertMessageRma($this->request->post['wk_view_message'],$writer,$this->request->post['wk_viewrma_grp_id'],$file_name);
						$this->session->data['success'] = $this->language->get('text_success_msg');
					}
				} else {
					$writer = 'me';
					$this->model_account_rma_rma->insertMessageRma($this->request->post['wk_view_message'],$writer,$this->request->post['wk_viewrma_grp_id'],$file_name);
					$this->session->data['success'] = $this->language->get('text_success_msg');
				}
				/**
				 * customer message code ends here
				 */
				/**
				 * resolve code
				 * @var [type]
				 */
				if(isset($this->request->post['wk_view_reopensolved'])){
					$this->model_account_rma_rma->updateRmaSta($this->request->post['wk_view_reopensolved'],$this->request->post['wk_viewrma_grp_id'],true);
					$this->session->data['success'] = $this->language->get('text_success_reopen');
				}

				/**
				 * resolve code ends here
				 */
				if (!isset($this->error['warning']) || !$this->error['warning']) {
					$this->response->redirect($this->urlChange('account/rma/viewrma&vid='.$vid,'',''));
				}
			}
		}

		if (isset($this->request->post['wk_view_message'])) {
			$data['wk_view_message'] = $this->request->post['wk_view_message'];
		} else {
			$data['wk_view_message'] = '';
		}

		$data['text_allowed_ex'] = sprintf($this->language->get('text_allowed_ex'),$this->config->get('wk_rma_system_file'),$this->config->get('wk_rma_system_size'));

		$data['action'] = $this->urlChange('account/rma/viewrma&vid='.$vid, '', true);
		$data['print'] = $this->urlChange('account/rma/viewrma&print&vid='.$vid , '', true);
		$data['print_shipping_lable'] = $this->urlChange('account/rma/viewrma/printlable&vid='.$vid ,'', true);
		$data['back'] = $this->urlChange('account/rma/rma', '', true);

		$data['viewrma'] = array();

  	$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->urlChange('common/home', '', true),
			'separator' => false
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title_list'),
			'href'      => $this->urlChange('account/rma/rma', '', true),
			'separator' => ' :: '
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->urlChange('account/rma/viewrma&vid='.$vid, '', true),
			'separator' => ' :: '
		);

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = $this->config->get('config_admin_limit') ? $this->config->get('config_admin_limit') : 20;
		$start = ($page - 1) * $limit ;

		//get RMA messages
		$result_messages = $this->model_account_rma_rma->viewRmaMessage($vid,$start,$limit);
		$total_result_messages = $this->model_account_rma_rma->viewTotalRmaMessage($vid);

		$data['rma_messages'] = $result_messages;
		$data['attachmentLink'] = HTTP_SERVER.'image/'.$result['images'].'/files/';

		$data['defaultRmaStatus'] = $this->model_account_rma_rma->defaultRmaStatus();
		$data['solveRmaStatus'] = $this->model_account_rma_rma->solveRmaStatus();
		$data['cancelRmaStatus'] = $this->model_account_rma_rma->cancelRmaStatus();

	  if ($result) {
	    	$this->document->addScript('catalog/view/javascript/RMA/ajaxfileupload.js');
	    	$this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
				$this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');

	    	$data['prodetails'] = array();
	     	$prodetails = $this->model_account_rma_rma->prodetails($vid,$result['order_id']);
	     	if($prodetails){
	     		foreach($prodetails as $products){
	     			$data['prodetails'][]	= array(
							'price' => Membership::currencyFormat(($products['price']),$this->session->data['currency']),
	     				'total' => Membership::currencyFormat(($products['price'] + $products['tax'])*$products['returned'],$this->session->data['currency']),
	     				'name' => $products['name'],
	     				'link' => $this->urlChange('product/product&product_id='.$products['product_id'], '', true),
	     				'ordered' => $products['ordered'],
	     				'reason' => $products['reason'],
	     				'model' => $products['model'],
	     				'returned' => $products['returned'],
	     			);
	     		}
	     	}

      $images = $this->getFolderImage($result['images']);
			if($result['cancel_rma']){
				$result['rma_status'] = $this->language->get('text_canceled_customer');
				$result['color'] = 'red';
			}
			if($result['solve_rma']){
				$result['rma_status'] = $this->language->get('text_solved_customer');
				$result['color'] = ' green';
			}

			if($result['admin_return']){
				$result['cancel_rma'] = 0;
				$result['solve_rma'] = 1;
				$result['color'] = 'green';
				$result['rma_status'] = $this->language->get('text_admin_return');
			}

			if($data['cancelRmaStatus'] == $result['admin_status'] && $result['admin_status']){
				$result['cancel_rma'] = 1;
				$result['rma_status'] = $this->language->get('text_canceled');
				$result['color'] = 'red';

			}

			if ($data['solveRmaStatus'] == $result['admin_status'] && $result['admin_status']){
				$result['solve_rma'] = 1;
				$result['rma_status'] = $this->language->get('text_solved');
				$result['color'] = ' green';
				$result['cancel_rma'] = 0;
			}

			$data['viewrma'] = array(
				'id' => $vid,
				'order_id' => $result['order_id'],
				'order_url' => $this->urlChange('account/order/info&order_id='.$result['order_id'], '', true),
				'images' => $images,
				'add_info' => $result['add_info'],
				'date' => $result['date'],
				'color' => $result['color'],
				'cancel_rma' => $result['cancel_rma'],
				'solve_rma' => $result['solve_rma'],
				'admin_status' => $result['admin_status'],
				'shipping_label' => $result['shipping_label'],
				'rma_status' => $result['rma_status'],
				'rma_auth_no'     => $result['rma_auth_no'],
			);

		}

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['warning'])) {
			$data['error_warning'] = $this->session->data['warning'];
			unset($this->session->data['warning']);
		}

		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];
			unset($this->session->data['error']);
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$pagination = new Pagination();
		$pagination->total = $total_result_messages;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->urlChange('account/rma/viewrma&vid='.$vid,'&page={page}', '', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($total_result_messages) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($total_result_messages - $limit)) ? $total_result_messages : ((($page - 1) * $limit) + $limit), $total_result_messages, ceil($total_result_messages / $limit));

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$tpl = '';

		if(isset($this->request->get['print'])){
			$tpl = 'print';
			$data['direction'] = 'ltr';
			$data['lang'] = 'en';
			$data['base'] = HTTP_SERVER;
		}

		if (version_compare(VERSION, '2.2', '>=')) {
			$this->response->setOutput($this->load->view('account/rma/viewrma'.$tpl.'', $data));
		} else {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/rma/viewrma'.$tpl.'.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/rma/viewrma'.$tpl.'.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/account/rma/viewrma'.$tpl.'.tpl', $data));
			}
		}
	}

	public function printlable() {

		if(!isset($this->request->get['vid'])){
			$this->response->redirect($this->urlChange('account/account', '', true));
		}

		if (!$this->customer->isLogged() AND !isset($this->session->data['rma_login'])) {
			$this->session->data['redirect'] = $this->urlChange('account/rma/viewrma/printlable&vid='.$this->request->get['vid'], '', true);
			$this->response->redirect($this->urlChange('account/rma/rmalogin', '', true));
		}

		$this->load->model('tool/image');
		$this->load->model('account/rma/rma');

		$result = $this->model_account_rma_rma->viewRmaid($this->request->get['vid']);

		$this->language->load('account/rma/viewrma');
		$this->document->setTitle($this->language->get('heading_title_label'));

		$data['heading_title'] = $this->language->get('heading_title_label');
		$data['text_from'] = $this->language->get('text_from');
		$data['text_to'] = $this->language->get('text_to');
		$data['text_shipping_label'] = $this->language->get('text_shipping_label');
		$data['text_quantity'] = $this->language->get('wk_viewrma_qty');
		$data['text_product_name'] = $this->language->get('wk_viewrma_pname');
		$data['text_reason'] = $this->language->get('wk_viewrma_reason');
		$data['text_auth_label'] = $this->language->get('text_auth_label');
		$data['text_order_id'] = $this->language->get('wk_viewrma_orderid');
		$data['text_rma_id'] = $this->language->get('text_rma_id');

		$data['rma_id'] = $this->request->get['vid'];
		$data['direction'] = 'ltr';
		$data['lang'] = 'en';
		$data['base'] = HTTP_SERVER;
		$data['address'] = $this->config->get('wk_rma_address');
		$data['label'] = $data['order_id'] = '';
		$data['prodetails'] = array();


		if($result AND $result['images'] AND $result['shipping_label']) {
			$label = $result['images'].'/files/'.$result['shipping_label'];
			if(file_exists(DIR_IMAGE.$label)){
				$data['label'] = $this->model_tool_image->resize($label,200,200);
			}
			$data['order_id'] = $result['order_id'];
	    $data['prodetails'] = $this->model_account_rma_rma->prodetails($this->request->get['vid'],$result['order_id']);
		}

		if (version_compare(VERSION, '2.2', '>=')) {
			$this->response->setOutput($this->load->view('account/rma/printlable', $data));
		} else {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/rma/printlable.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/rma/printlable.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/account/rma/printlable.tpl', $data));
			}
		}
	}

	/**
	 * view rma image upload.
	 * @return [type] [description]
	 */
	public function imageupload()   {

		$json = array();
		if(!isset($this->request->post['id']) || !isset($this->request->files['rma_file']))
			return;
		$this->load->language('common/rma');
		$this->load->model('tool/image');
		$this->load->model('account/rma/rma');
		$result = $this->model_account_rma_rma->viewRmaid($this->request->post['id']);
		//echo '<pre>'; print_r($result);exit;
		if($result && $result['images']){
			$img_folder_rma = $result['images'];
			$img_folder = DIR_IMAGE . $result['images'];
			$images = $this->validateImage($this->request->files['rma_file']);
			if (!is_dir($img_folder)) {
				@chmod(DIR_IMAGE . 'rma/',0755);
				@mkdir($img_folder);
				@mkdir($img_folder . '/files');
			}
			if ($images) {
				//echo '<pre>'; print_r($images);exit;
				$json['success'] = array();
				foreach ($images as $key => $image) {
					$target = $img_folder . '/' . $image['name'];
					@move_uploaded_file($image['tmp_name'], $target);
					$resize = $this->model_tool_image->resize($img_folder_rma . '/' . $image['name'],125,125);
					$thumb = $this->model_tool_image->resize($img_folder_rma . '/' . $image['name'],500,500);
					$json['success'][] = $thumb;
				}
			} else {
				if (isset($this->error['warning'])) {
					$json['error'] = $this->error['warning'];
				} else {
					$json['error'] = $this->language->get('error_upload');
				}

			}
		}
		$this->response->setOutput(json_encode($json));
	}
}
