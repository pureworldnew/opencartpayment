<?php
/**
 * Webkul Software.
 * @category  Webkul
 * @author    Webkul
 * @copyright Copyright (c) 2010-2016 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
class ControllerAccountRmaRmalogin extends Controller {
	use RmaControllerTrait;

	private $error = array();
	private $data = array();

	public function index() {

		if(!$this->config->get('wk_rma_status'))
			$this->response->redirect($this->urlChange('account/login', '', true));

		$this->language->load('account/rma/rma');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$data = $this->request->post;
			if(isset($data['logintype'])){
				if($data['logintype']==1)
					$this->response->redirect($this->urlChange('account/login', '', true));
				else
					$this->response->redirect($this->urlChange('account/rma/rmalogin/guest', '', true));
			}else
				$this->error['warning'] = $this->language->get('error_logintype');
		}

		if ($this->customer->isLogged()){ // || isset($this->session->data['rma_login'])) {
			$this->response->redirect($this->urlChange('account/rma/rma', '', true));
		}

		$this->document->setTitle($this->language->get('heading_title_login'));
		$this->getlist();

	}

	public function getlist(){

  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->urlChange('common/home', '', true),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_login'),
			'href'      => $this->urlChange('account/rma/rmalogin', '', true),
      		'separator' => ' :: '
   		);

		$data['action'] = $this->urlChange('account/rma/rmalogin', '', true);

		$data['heading_title'] = $this->language->get('heading_title_login');
		$data['text_login_type'] = $this->language->get('text_login_type');
		$data['text_guest'] = $this->language->get('text_guest');
		$data['text_registered'] = $this->language->get('text_registered');
		$data['button_request'] = $this->language->get('button_request');
		$data['text_star'] = $this->language->get('text_star');
		$data['text_login_info'] = $this->language->get('text_login_info');

		$data['logintype'] = true;

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


		$data['column_left'] = $this->load->controller('common/column_left');

		$data['column_right'] = $this->load->controller('common/column_right');

		$data['content_top'] = $this->load->controller('common/content_top');

		$data['content_bottom'] = $this->load->controller('common/content_bottom');

		$data['footer'] = $this->load->controller('common/footer');

		$data['header'] = $this->load->controller('common/header');

		if (version_compare(VERSION, '2.2', '>=')) {
			$this->response->setOutput($this->load->view('account/rma/rmalogin', $data));
		} else {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/rma/rmalogin.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/rma/rmalogin.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/account/rma/rmalogin.tpl', $data));
			}
		}

	}

	public function guest() {

		if ($this->customer->isLogged()) {
			$this->response->redirect($this->urlChange('account/rma/rma', '', true));
		}

		$this->language->load('account/rma/rma');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$data = $this->request->post;

			if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
				$this->error['warning'] = $this->language->get('error_email');
			}

			if ((utf8_strlen($this->request->post['orderinfo']) < 1)) {
				$this->error['warning'] = $this->language->get('error_orderinfo');
			}

			if(!isset($this->error['warning'])){
				$this->load->model('account/rma/rma');
				$result = $this->model_account_rma_rma->getGuestStatus($data);
				if($result AND $result['customer_id']==0){
					$this->session->data['rma_login'] = $data['email'];
					$this->response->redirect($this->urlChange('account/rma/rma', '', true));
				}elseif($result AND $result['customer_id']!=0){
					$this->error['warning'] = $this->language->get('error_pleaselogin', '', true);
				}else{
					$this->error['warning'] = $this->language->get('error_order');
				}
			}

		}

		if(isset($this->request->post['email'])){
			$data['email'] = $this->request->post['email'];
		}else{
			$data['email'] = '';
		}

		if(isset($this->request->post['orderinfo'])){
			$data['orderinfo'] = $this->request->post['orderinfo'];
		}else{
			$data['orderinfo'] = '';
		}

		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->urlChange('common/home', '', true),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_login'),
			'href'      => $this->urlChange('account/rma/rmalogin', '', true),
      		'separator' => ' :: '
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_guest'),
			'href'      => $this->urlChange('account/rma/rmalogin/guest', '', true),
      		'separator' => ' :: '
   		);

   		$this->document->setTitle($this->language->get('heading_title_guest'));
		$data['action'] = $this->urlChange('account/rma/rmalogin/guest', '', true);

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_orderid'] = $this->language->get('text_orderid');
		$data['text_email'] = $this->language->get('text_email');
		$data['button_request'] = $this->language->get('button_request');
		$data['text_star'] = $this->language->get('text_star');
		$data['text_guest_info'] = $this->language->get('text_guest_info');
		$data['text_order_info'] = $this->language->get('text_order_info');

		$data['guestlogin'] = true;

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

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (version_compare(VERSION, '2.2', '>=')) {
			$this->response->setOutput($this->load->view('account/rma/rmalogin', $data));
		} else {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/rma/rmalogin.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/rma/rmalogin.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/account/rma/rmalogin.tpl', $data));
			}
		}
	}

}
?>
