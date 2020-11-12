<?php
class ControllerModuleTmdAccount extends Controller {
	public function index($setting) {
		$this->load->language('module/tmdaccount');

		$data['heading_title'] = $this->language->get('heading_title');

		$accountlables=$this->config->get('tmdaccount_lable');
		
		if(!empty($accountlables[$this->config->get('config_language_id')]['profilelabel'])){
		$data['text_myprofile']	= $accountlables[$this->config->get('config_language_id')]['profilelabel'];
		} else {
		$data['text_myprofile'] = $this->language->get('text_myprofile');
		}
		
		if(!empty($accountlables[$this->config->get('config_language_id')]['checklabel'])){
		$data['text_headingorder']	= $accountlables[$this->config->get('config_language_id')]['checklabel'];
		} else {
		$data['text_headingorder'] = $this->language->get('text_headingorder');
		}
		
		if(!empty($accountlables[$this->config->get('config_language_id')]['accountlabel'])){
		$data['text_account']	= $accountlables[$this->config->get('config_language_id')]['accountlabel'];
		} else {
		$data['text_account'] = $this->language->get('text_account');
		}
		
		if(!empty($accountlables[$this->config->get('config_language_id')]['passlabel'])){
		$data['text_password']	= $accountlables[$this->config->get('config_language_id')]['passlabel'];
		} else {
		$data['text_password'] = $this->language->get('text_password');
		}
		
		if(!empty($accountlables[$this->config->get('config_language_id')]['editaclabel'])){
		$data['text_edit']	= $accountlables[$this->config->get('config_language_id')]['editaclabel'];
		} else {
		$data['text_edit'] = $this->language->get('text_edit');
		}
		
		
		if(!empty($accountlables[$this->config->get('config_language_id')]['booklabel'])){
		$data['text_address']	= $accountlables[$this->config->get('config_language_id')]['booklabel'];
		} else {
		$data['text_address'] = $this->language->get('text_address');
		}
		
		if(!empty($accountlables[$this->config->get('config_language_id')]['wishlabel'])){
		$data['text_wishlist']	= $accountlables[$this->config->get('config_language_id')]['wishlabel'];
		} else {
		$data['text_wishlist'] = $this->language->get('text_wishlist');
		}
		
		if(!empty($accountlables[$this->config->get('config_language_id')]['orderlabel'])){
		$data['text_order']	= $accountlables[$this->config->get('config_language_id')]['orderlabel'];
		} else {
		$data['text_order'] = $this->language->get('text_order');
		}
		
		if(!empty($accountlables[$this->config->get('config_language_id')]['downlodlabel'])){
		$data['text_download']	= $accountlables[$this->config->get('config_language_id')]['downlodlabel'];
		} else {
		$data['text_download'] = $this->language->get('text_download');
		}
		
		if(!empty($accountlables[$this->config->get('config_language_id')]['pointslabel'])){
		$data['text_reward_point']	= $accountlables[$this->config->get('config_language_id')]['pointslabel'];
		} else {
		$data['text_reward_point'] = $this->language->get('text_reward_point');
		}
		
		if(!empty($accountlables[$this->config->get('config_language_id')]['returnlabel'])){
		$data['text_return']	= $accountlables[$this->config->get('config_language_id')]['returnlabel'];
		} else {
		$data['text_return'] = $this->language->get('text_return');
		}
		
		if(!empty($accountlables[$this->config->get('config_language_id')]['transctionlabel'])){
		$data['text_transaction']	= $accountlables[$this->config->get('config_language_id')]['transctionlabel'];
		} else {
		$data['text_transaction'] = $this->language->get('text_transaction');
		}
		
		
		if(!empty($accountlables[$this->config->get('config_language_id')]['newslabel'])){
		$data['text_newsletter']	= $accountlables[$this->config->get('config_language_id')]['newslabel'];
		} else {
		$data['text_newsletter'] = $this->language->get('text_newsletter');
		}
		
		if(!empty($accountlables[$this->config->get('config_language_id')]['paylabel'])){
		$data['text_recurring']	= $accountlables[$this->config->get('config_language_id')]['paylabel'];
		} else {
		$data['text_recurring'] = $this->language->get('text_recurring');
		}
		
		if(!empty($accountlables[$this->config->get('config_language_id')]['loginlabel'])){
		$data['text_login']	= $accountlables[$this->config->get('config_language_id')]['loginlabel'];
		} else {
		$data['text_login'] = $this->language->get('text_login');
		}
		
		if(!empty($accountlables[$this->config->get('config_language_id')]['logoutlabel'])){
		$data['text_logout']	= $accountlables[$this->config->get('config_language_id')]['logoutlabel'];
		} else {
		$data['text_logout'] = $this->language->get('text_logout');
		}
		
		if(!empty($accountlables[$this->config->get('config_language_id')]['registerlabel'])){
		$data['text_register']	= $accountlables[$this->config->get('config_language_id')]['registerlabel'];
		} else {
		$data['text_register'] = $this->language->get('text_register');
		}
		
		if(!empty($accountlables[$this->config->get('config_language_id')]['forgotlabel'])){
		$data['text_forgotten']	= $accountlables[$this->config->get('config_language_id')]['forgotlabel'];
		} else {
		$data['text_forgotten'] = $this->language->get('text_forgotten');
		}

		$data['logged'] = $this->customer->isLogged();
		$data['register'] = $this->url->link('account/register', '', 'SSL');
		$data['login'] = $this->url->link('account/login', '', 'SSL');
		$data['logout'] = $this->url->link('account/logout', '', 'SSL');
		$data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');
		$data['account'] = $this->url->link('account/account', '', 'SSL');
		$data['edit'] = $this->url->link('account/edit', '', 'SSL');
		$data['password'] = $this->url->link('account/password', '', 'SSL');
		$data['address'] = $this->url->link('account/address', '', 'SSL');
		$data['wishlist'] = $this->url->link('account/wishlist');
		$data['order'] = $this->url->link('account/order', '', 'SSL');
		$data['download'] = $this->url->link('account/download', '', 'SSL');
		$data['reward'] = $this->url->link('account/reward', '', 'SSL');
		$data['return'] = $this->url->link('account/return', '', 'SSL');
		$data['transaction'] = $this->url->link('account/transaction', '', 'SSL');
		$data['newsletter'] = $this->url->link('account/newsletter', '', 'SSL');
		$data['recurring'] = $this->url->link('account/recurring', '', 'SSL');
		
		$this->load->model('tool/image');
		
		$this->load->model('account/customer');
		$this->load->model('account/tmdaccount');
		
		$prodileimages= $this->model_account_customer->getCustomer($this->customer->getId());
		
		if ($prodileimages) {
		$data['prodileimage'] = $this->model_tool_image->resize($prodileimages['image'], 90,89);
		} else {
			$data['prodileimage'] = '';
		}
		
		
		$tmdaccount_info= $this->model_account_tmdaccount->getShowAccount($this->customer->getId());
		
		if (isset($tmdaccount_info['firstname'])) {
		$data['firstname'] = $tmdaccount_info['firstname'];
		} else {
		$data['firstname'] = '';
		}
		
		if (isset($tmdaccount_info['lastname'])) {
		$data['lastname'] = $tmdaccount_info['lastname'];
		} else {
		$data['lastname'] = '';
		}
		
		if (isset($tmdaccount_info['email'])) {
		$data['email'] = $tmdaccount_info['email'];
		} else {
		$data['email'] = '';
		}
		
		$data['placeholder1'] = $this->model_tool_image->resize('placeholder.png', $this->config->get('tmdaccount_defaultwidth'),$this->config->get('tmdaccount_defaultheight'));
		
		$defaulpic = $this->config->get('tmdaccount_defaultimage');
		
		if (is_file(DIR_IMAGE . $defaulpic)) {
			$data['defaulpic'] = $this->model_tool_image->resize($defaulpic, $this->config->get('tmdaccount_defaultwidth'),$this->config->get('tmdaccount_defaultheight'));
		} else {
			$data['defaulpic'] = $this->model_tool_image->resize('placeholder.png', $this->config->get('tmdaccount_defaultwidth'),$this->config->get('tmdaccount_defaultheight'));
		}
		
		
		
		$tmdaccount_status = $this->config->get('tmdaccount_status');
		if (isset($tmdaccount_status)) {
		$data['account_status'] = $tmdaccount_status;
		} else {
		$data['account_status'] = '';
		}
		
		$tmdaccount_myaccount = $this->config->get('tmdaccount_myaccount');
		if (isset($tmdaccount_myaccount)) {
		$data['my_account'] = $tmdaccount_myaccount;
		} else {
		$data['my_account'] = '';
		}
		
		$tmdaccount_editaccount = $this->config->get('tmdaccount_editaccount');
		if (isset($tmdaccount_editaccount)) {
		$data['edit_account'] = $tmdaccount_editaccount;
		} else {
		$data['edit_account'] = '';
		}
		
		$tmdaccount_password = $this->config->get('tmdaccount_password');
		if (isset($tmdaccount_password)) {
		$data['change_password'] = $tmdaccount_password;
		} else {
		$data['change_password'] = '';
		}
		
		$tmdaccount_address_book = $this->config->get('tmdaccount_address_book');
		if (isset($tmdaccount_address_book)) {
		$data['address_book'] = $tmdaccount_address_book;
		} else {
		$data['address_book'] = '';
		}
		
		$tmdaccount_wishlist = $this->config->get('tmdaccount_wishlist');
		if (isset($tmdaccount_wishlist)) {
		$data['my_wishlist'] = $tmdaccount_wishlist;
		} else {
		$data['my_wishlist'] = '';
		}
		
		$tmdaccount_newsletter = $this->config->get('tmdaccount_newsletter');
		if (isset($tmdaccount_newsletter)) {
		$data['account_newsletter'] = $tmdaccount_newsletter;
		} else {
		$data['account_newsletter'] = '';
		}
		
		$tmdaccount_logout = $this->config->get('tmdaccount_logout');
		if (isset($tmdaccount_logout)) {
		$data['account_logout'] = $tmdaccount_logout;
		} else {
		$data['account_logout'] = '';
		}
		
		$tmdaccount_order = $this->config->get('tmdaccount_order');
		if (isset($tmdaccount_order)) {
		$data['my_order'] = $tmdaccount_order;
		} else {
		$data['my_order'] = '';
		}
		
		$tmdaccount_downloads = $this->config->get('tmdaccount_downloads');
		if (isset($tmdaccount_downloads)) {
		$data['my_downloads'] = $tmdaccount_downloads;
		} else {
		$data['my_downloads'] = '';
		}
		
		$tmdaccount_payments = $this->config->get('tmdaccount_payments');
		if (isset($tmdaccount_payments)) {
		$data['my_payments'] = $tmdaccount_payments;
		} else {
		$data['my_payments'] = '';
		}
		
		$tmdaccount_reward = $this->config->get('tmdaccount_reward');
		if (isset($tmdaccount_reward)) {
		$data['my_reward'] = $tmdaccount_reward;
		} else {
		$data['my_reward'] = '';
		}
		
		$tmdaccount_returns = $this->config->get('tmdaccount_returns');
		if (isset($tmdaccount_returns)) {
		$data['my_returns'] = $tmdaccount_returns;
		} else {
		$data['my_returns'] = '';
		}
		
		$tmdaccount_transaction = $this->config->get('tmdaccount_transaction');
		if (isset($tmdaccount_transaction)) {
		$data['my_transaction'] = $tmdaccount_transaction;
		} else {
		$data['my_transaction'] = '';
		}
		
		$tmdaccount_login = $this->config->get('tmdaccount_login');
		if (isset($tmdaccount_login)) {
		$data['account_login'] = $tmdaccount_login;
		} else {
		$data['account_login'] = '';
		}
		
		$tmdaccount_register = $this->config->get('tmdaccount_register');
		if (isset($tmdaccount_register)) {
		$data['account_register'] = $tmdaccount_register;
		} else {
		$data['account_register'] = '';
		}
		
		$tmdaccount_forgot = $this->config->get('tmdaccount_forgot');
		if (isset($tmdaccount_forgot)) {
		$data['account_forgot'] = $tmdaccount_forgot;
		} else {
		$data['account_forgot'] = '';
		}

		return $this->load->view('default/template/module/tmdaccount.tpl', $data);
	}
}
