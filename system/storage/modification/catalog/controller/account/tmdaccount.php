<?php
class ControllerAccountTmdAccount extends Controller {
	public function index() {

			$data['tmdaccount_customcss'] = $this->config->get('tmdaccount_custom_css');
			$data['tmdaccount_status'] = $this->config->get('tmdaccount_status');
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/tmdaccount', '', 'SSL');
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('account/tmdaccount');
		//$this->load->model('account/customer');
		$this->load->model('account/tmdaccount');

		$this->document->setTitle($this->language->get('heading_title'));
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		} 
				if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'total';
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
		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limit = 20;
		}
		$url = '';
				
		$data['heading_title']= $this->language->get('heading_title');
		$data['text_phone']= $this->language->get('text_phone');
		$data['text_email']= $this->language->get('text_email');
		$data['text_address']= $this->language->get('text_address');
		$data['text_edit_profile']= $this->language->get('text_edit_profile');
		
		
		$data['text_no_results']= $this->language->get('text_no_results');
		
		
		$accountlables=$this->config->get('tmdaccount_lable');
			
		if(!empty($accountlables[$this->config->get('config_language_id')]['totalodrlabel'])){
		$data['text_total_order']	= $accountlables[$this->config->get('config_language_id')]['totalodrlabel'];
		} else {
		$data['text_total_order'] = $this->language->get('text_total_order');
		}
			
		if(!empty($accountlables[$this->config->get('config_language_id')]['totaldownlabel'])){
		$data['text_downloads']	= $accountlables[$this->config->get('config_language_id')]['totaldownlabel'];
		} else {
		$data['text_downloads']= $this->language->get('text_downloads');
		}
		
		if(!empty($accountlables[$this->config->get('config_language_id')]['totalwishlabel'])){
		$data['text_total_wishlist']	= $accountlables[$this->config->get('config_language_id')]['totalwishlabel'];
		} else {
		$data['text_total_wishlist']= $this->language->get('text_total_wishlist');
		}
		
		
		if(!empty($accountlables[$this->config->get('config_language_id')]['pointslabel'])){
		$data['text_reward_points']	= $accountlables[$this->config->get('config_language_id')]['pointslabel'];
		} else {
		$data['text_reward_points']= $this->language->get('text_reward_points');
		}
		
		if(!empty($accountlables[$this->config->get('config_language_id')]['pointslabel'])){
		$data['text_reward_point']	= $accountlables[$this->config->get('config_language_id')]['pointslabel'];
		} else {
		$data['text_reward_point']= $this->language->get('text_reward_point');
		}
		
		if(!empty($accountlables[$this->config->get('config_language_id')]['totaltranslabel'])){
		$data['text_transations']	= $accountlables[$this->config->get('config_language_id')]['totaltranslabel'];
		} else {
		$data['text_transations']= $this->language->get('text_transations');
		}
		
		if(!empty($accountlables[$this->config->get('config_language_id')]['transctionlabel'])){
		$data['text_transation']	= $accountlables[$this->config->get('config_language_id')]['transctionlabel'];
		} else {
		$data['text_transation']= $this->language->get('text_transation');
		}
		
		
		if(!empty($accountlables[$this->config->get('config_language_id')]['editaclabel'])){
		$data['text_edit_account']	= $accountlables[$this->config->get('config_language_id')]['editaclabel'];
		} else {
		$data['text_edit_account']= $this->language->get('text_edit_account');
		}
		if(!empty($accountlables[$this->config->get('config_language_id')]['passlabel'])){
		$data['text_change_password']	= $accountlables[$this->config->get('config_language_id')]['passlabel'];
		} else {
		$data['text_change_password']= $this->language->get('text_change_password');
		}
		
		if(!empty($accountlables[$this->config->get('config_language_id')]['booklabel'])){
		$data['text_address_book']	= $accountlables[$this->config->get('config_language_id')]['booklabel'];
		} else {
		$data['text_address_book']= $this->language->get('text_address_book');
		}
		
		if(!empty($accountlables[$this->config->get('config_language_id')]['wishlabel'])){
		$data['text_wishlist']	= $accountlables[$this->config->get('config_language_id')]['wishlabel'];
		} else {
		$data['text_wishlist']= $this->language->get('text_wishlist');
		}
		if(!empty($accountlables[$this->config->get('config_language_id')]['orderlabel'])){
		$data['text_order']	= $accountlables[$this->config->get('config_language_id')]['orderlabel'];
		} else {
		$data['text_order']= $this->language->get('text_order');
		}
		
		if(!empty($accountlables[$this->config->get('config_language_id')]['downlodlabel'])){
		$data['text_download']	= $accountlables[$this->config->get('config_language_id')]['downlodlabel'];
		} else {
		$data['text_download']= $this->language->get('text_download');
		}
		
		if(!empty($accountlables[$this->config->get('config_language_id')]['returnlabel'])){
		$data['text_returnrequest']	= $accountlables[$this->config->get('config_language_id')]['returnlabel'];
		} else {
		$data['text_returnrequest']= $this->language->get('text_returnrequest');
		}
		
		if(!empty($accountlables[$this->config->get('config_language_id')]['paylabel'])){
		$data['text_recurringpayments']	= $accountlables[$this->config->get('config_language_id')]['paylabel'];
		} else {
		$data['text_recurringpayments']= $this->language->get('text_recurringpayments');
		}
		
		if(!empty($accountlables[$this->config->get('config_language_id')]['newslabel'])){
		$data['text_newsletter']	= $accountlables[$this->config->get('config_language_id')]['newslabel'];
		} else {
		$data['text_newsletter']= $this->language->get('text_newsletter');
		}
		
		if(!empty($accountlables[$this->config->get('config_language_id')]['latestlabel'])){
		$data['text_latest']	= $accountlables[$this->config->get('config_language_id')]['latestlabel'];
		} else {
		$data['text_latest']= $this->language->get('text_latest');
		}
		
		if(!empty($accountlables[$this->config->get('config_language_id')]['orderidlabel'])){
		$data['column_order_id']	= $accountlables[$this->config->get('config_language_id')]['orderidlabel'];
		} else {
		$data['column_order_id']= $this->language->get('column_order_id');
		}
		
		if(!empty($accountlables[$this->config->get('config_language_id')]['noprolabel'])){
		$data['column_product']	= $accountlables[$this->config->get('config_language_id')]['noprolabel'];
		} else {
		$data['column_product']= $this->language->get('column_product');
		}
		
		$data['column_product_name'] = $this->language->get('column_product_name');
		$data['column_image'] = $this->language->get('column_image');
		
		if(!empty($accountlables[$this->config->get('config_language_id')]['statuslabel'])){
		$data['column_status']	= $accountlables[$this->config->get('config_language_id')]['statuslabel'];
		} else {
		$data['column_status']= $this->language->get('column_status');
		}
		
		if(!empty($accountlables[$this->config->get('config_language_id')]['viewalllabel'])){
		$data['text_viewall']	= $accountlables[$this->config->get('config_language_id')]['viewalllabel'];
		} else {
		$data['text_viewall']= $this->language->get('text_viewall');
		}
		if(!empty($accountlables[$this->config->get('config_language_id')]['totalprolabel'])){
		$data['column_total']	= $accountlables[$this->config->get('config_language_id')]['totalprolabel'];
		} else {
		$data['column_total']= $this->language->get('column_total');
		}
		
		if(!empty($accountlables[$this->config->get('config_language_id')]['datelabel'])){
		$data['column_date_added']	= $accountlables[$this->config->get('config_language_id')]['datelabel'];
		} else {
		$data['column_date_added']= $this->language->get('column_date_added');
		}
		
		if(!empty($accountlables[$this->config->get('config_language_id')]['actionlabel'])){
		$data['column_action']	= $accountlables[$this->config->get('config_language_id')]['actionlabel'];
		} else {
		$data['column_action']= $this->language->get('column_action');
		}
		
		$data['button_view']= $this->language->get('button_view');
		
		$tmdaccount_info= $this->model_account_tmdaccount->getShowAccount($this->customer->getId());
		
		$data['firstname']=$tmdaccount_info['firstname'];
		$data['lastname']=$tmdaccount_info['lastname'];
		$data['telephone']=$tmdaccount_info['telephone'];
		$data['email']=$tmdaccount_info['email'];
		$data['href']=$this->url->link('account/edit','customer_id=' . $tmdaccount_info['customer_id']);
		
		$address_info= $this->model_account_tmdaccount->getShowAddress($this->customer->getId());
		$data['address_1']=$address_info['address_1'];
		$data['city']=$address_info['city'];
		
		$data['order_total']= $this->model_account_tmdaccount->getTotalOrder($this->customer->getId());
		
		$data['wishlist_total'] = $this->model_account_tmdaccount->getTotalWishlist($this->customer->getId());
		
		$data['points']= $this->model_account_tmdaccount->getTotalRewardPoints($this->customer->getId());
		$data['totaltransaction']= $this->model_account_tmdaccount->getTotalTransaction($this->customer->getId());
		$data['totaldownload']= $this->model_account_tmdaccount->getTotalDownload($this->customer->getId());
		
		$this->load->model('tool/image');
		
		$this->load->model('account/customer');
		
		$prodileimages= $this->model_account_customer->getCustomer($this->customer->getId());
		
		if ($prodileimages) {
		$data['prodileimage'] = $this->model_tool_image->resize($prodileimages['image'], 90,89);
		} else {
			$data['prodileimage'] = '';
		}
		
		
		
		$tmdaccount_bgimage = $this->config->get('tmdaccount_bgimage');
		
		if (is_file(DIR_IMAGE . $tmdaccount_bgimage)) {
			$data['tmdaccount_bgimage'] = $this->model_tool_image->resize($tmdaccount_bgimage, $this->config->get('tmdaccount_bgwidth'),$this->config->get('tmdaccount_bgheight'));
		} else {
			$data['tmdaccount_bgimage'] = '';
		}
		
		
		$defaulpic = $this->config->get('tmdaccount_defaultimage');
		
		if (is_file(DIR_IMAGE . $defaulpic)) {
			$data['defaulpic'] = $this->model_tool_image->resize($defaulpic, $this->config->get('tmdaccount_defaultwidth'),$this->config->get('tmdaccount_defaultheight'));
		} else {
			$data['defaulpic'] = '';
		}
		
		$tmdaccount_totalorders = $this->config->get('tmdaccount_totalorders');
		if (isset($tmdaccount_totalorders)) {
		$data['total_order'] = $tmdaccount_totalorders;
		} else {
		$data['total_order'] = '';
		}
		
		$tmdaccount_totalwishlist = $this->config->get('tmdaccount_totalwishlist');
		if (isset($tmdaccount_totalwishlist)) {
		$data['total_wishlist'] = $tmdaccount_totalwishlist;
		} else {
		$data['total_wishlist'] = '';
		}
		
		$tmdaccount_totalreward = $this->config->get('tmdaccount_totalreward');
		if (isset($tmdaccount_totalreward)) {
		$data['total_reward'] = $tmdaccount_totalreward;
		} else {
		$data['total_reward'] = '';
		}
		
		$tmdaccount_totaldownload = $this->config->get('tmdaccount_totaldownload');
		if (isset($tmdaccount_totaldownload)) {
		$data['total_download'] = $tmdaccount_totaldownload;
		} else {
		$data['total_download'] = '';
		}
		
		$tmdaccount_totaltransaction = $this->config->get('tmdaccount_totaltransaction');
		if (isset($tmdaccount_totaltransaction)) {
		$data['total_transaction'] = $tmdaccount_totaltransaction;
		} else {
		$data['total_transaction'] = '';
		}
		
		$tmdaccount_latestorder = $this->config->get('tmdaccount_latestorder');
		if (isset($tmdaccount_latestorder)) {
		$data['latest_order'] = $tmdaccount_latestorder;
		} else {
		$data['latest_order'] = '';
		}
		//
		$tmdaccount_link_editaccount = $this->config->get('tmdaccount_link_editaccount');
		if (isset($tmdaccount_link_editaccount)) {
		$data['link_editaccount'] = $tmdaccount_link_editaccount;
		} else {
		$data['link_editaccount'] = '';
		}
		
		$tmdaccount_link_password = $this->config->get('tmdaccount_link_password');
		if (isset($tmdaccount_link_password)) {
		$data['link_password'] = $tmdaccount_link_password;
		} else {
		$data['link_password'] = '';
		}
		
		$tmdaccount_link_address_book = $this->config->get('tmdaccount_link_address_book');
		if (isset($tmdaccount_link_address_book)) {
		$data['link_address_book'] = $tmdaccount_link_address_book;
		} else {
		$data['link_address_book'] = '';
		}
		
		$tmdaccount_link_wishlist = $this->config->get('tmdaccount_link_wishlist');
		if (isset($tmdaccount_link_wishlist)) {
		$data['link_wishlist'] = $tmdaccount_link_wishlist;
		} else {
		$data['link_wishlist'] = '';
		}
		
		$tmdaccount_link_order = $this->config->get('tmdaccount_link_order');
		if (isset($tmdaccount_link_order)) {
		$data['link_order'] = $tmdaccount_link_order;
		} else {
		$data['link_order'] = '';
		}
		
		$tmdaccount_link_downloads = $this->config->get('tmdaccount_link_downloads');
		if (isset($tmdaccount_link_downloads)) {
		$data['link_downloads'] = $tmdaccount_link_downloads;
		} else {
		$data['link_downloads'] = '';
		}
		
		$tmdaccount_link_reward = $this->config->get('tmdaccount_link_reward');
		if (isset($tmdaccount_link_reward)) {
		$data['link_reward'] = $tmdaccount_link_reward;
		} else {
		$data['link_reward'] = '';
		}
		
		$tmdaccount_link_returns = $this->config->get('tmdaccount_link_returns');
		if (isset($tmdaccount_link_returns)) {
		$data['link_returns'] = $tmdaccount_link_returns;
		} else {
		$data['link_returns'] = '';
		}
		
		$tmdaccount_link_transaction = $this->config->get('tmdaccount_link_transaction');
		if (isset($tmdaccount_link_transaction)) {
		$data['link_transaction'] = $tmdaccount_link_transaction;
		} else {
		$data['link_transaction'] = '';
		}
		
		$tmdaccount_link_newsletter = $this->config->get('tmdaccount_link_newsletter');
		if (isset($tmdaccount_link_newsletter)) {
		$data['link_newsletter'] = $tmdaccount_link_newsletter;
		} else {
		$data['link_newsletter'] = '';
		}
		$tmdaccount_link_payments = $this->config->get('tmdaccount_link_payments');
		if (isset($tmdaccount_link_payments)) {
		$data['link_payments'] = $tmdaccount_link_payments;
		} else {
		$data['link_payments'] = '';
		}
		
		$filter_data = array(				
			'order'              => $order,
			'start'              => ($page - 1) * 10,
			'limit'              => $limit
		);
		
		/* new code */
		$data['viewalorders'] = $this->url->link('account/order'); 
		/* new code */
		//print_r($results);die();
		$url='';
		
		$data['orders']= array();
		$odr_total = $this->model_account_tmdaccount->getTotalOrders();
		
		$results=$this->model_account_tmdaccount->getOrders(($page - 1) * 10, 10);
		
		if(isset($results)){
			
			foreach($results as $result){ 
				$product_data = $this->model_account_tmdaccount->getProductData($result['product_id']);
				if (is_file(DIR_IMAGE . $product_data['image'])) {
				$image = $this->model_tool_image->resize($product_data['image'], 40, 40);
				} else {
					$image = $this->model_tool_image->resize('no_image.png', 40, 40);
				}
				
				$data['orders'][] = array( 
				'order_id'	     => $result['order_id'],
				'date_added'	 => $result['date_added'],
				'total'	         => $result['total'],
				'name'	 		 => !empty($product_data['name']) ? $product_data['name'] : "",
				'image'	 		 => $image,
				'product_url'	 => !empty($result['product_id']) ? $this->url->link('product/product', 'product_id=' . $result['product_id']) : "",
				'noof_product'	 => $result['quantity'],
				'status'	     => $result['status'],
				'href'	         => $this->url->link('account/order/info'. '&order_id=' . $result['order_id'] . $url, 'SSL')
				
				);
			}
			
		}
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		$pagination = new Pagination();
		$pagination->total = $odr_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('account/tmdaccount', $url . '&page={page}');
		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($odr_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($odr_total - $limit)) ? $odr_total : ((($page - 1) * $limit) + $limit), $odr_total, ceil($odr_total / $limit));
		
		
		$data['edit'] = $this->url->link('account/edit', '', true);
		$data['password'] = $this->url->link('account/password', '', true);
		$data['address'] = $this->url->link('account/address', '', true);
		$data['return'] = $this->url->link('account/return', '', true);
		$data['transactions'] = $this->url->link('account/transaction', '', true);
		$data['newsletter'] = $this->url->link('account/newsletter', '', true);
		$data['recurring'] = $this->url->link('account/recurring', '', true);
		$data['wishlist'] = $this->url->link('account/wishlist');
		$data['order'] = $this->url->link('account/order', '', true);
		$data['downloads'] = $this->url->link('account/download', '', true);
		$data['reward'] = $this->url->link('account/reward', '', true);
		/* if ($this->config->get('reward_status')) {
			$data['reward'] = $this->url->link('account/reward', '', true);
		} else {
			$data['reward'] = '';
		} */
		
		$data['tmdaccount_customcss'] = $this->config->get('tmdaccount_custom_css');
		
		$data['column_left']	= $this->load->controller('common/column_left');
		$data['column_right']	= $this->load->controller('common/column_right');
		$data['content_top']	= $this->load->controller('common/content_top');
		$data['content_bottom']= $this->load->controller('common/content_bottom');
		$data['footer']		= $this->load->controller('common/footer');
		$data['header']		= $this->load->controller('common/header');
		
		$this->response->setOutput($this->load->view('default/template/account/tmdaccount.tpl', $data));
	}
}
