<?php
class ControllerCommonQuickLogin extends Controller {

    private $error = array();

    public function index(){

        if ($this->customer->isLogged()){
           // $this->response->redirect($this->url->link('common/quick_login/logged/1'));
        }

        $this->load->language('module/quick_login');
        $this->document->setTitle($this->language->get('heading_title'));
		
		//print_r($this->request->post);

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['quick_login']) && $this->validate()){
		    $this->logged();
            return false;
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_register'] = $this->language->get('text_register');
        $data['text_forgotten'] = $this->language->get('text_forgotten');

        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_password'] = $this->language->get('entry_password');

        $data['button_login'] = $this->language->get('button_login');

        $data['register'] = $this->url->link('account/register', '', 'SSL');
        $data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');

        $data['error_email'] = $this->language->get('error_email');
		$data['error_password'] = $this->language->get('error_password');
		$data['error_login'] = $this->language->get('error_login');
		$data['error_approved'] = $this->language->get('error_approved');
		   
		
		
        if ($this->customer->isLogged()){
           $data['text_login'] = $this->customer->getFirstName();
        }
        else{
           $data['text_login'] = $this->language->get('text_login');
        }

       $data['text_hidden_login'] = $this->language->get('text_login');
            // welcome text for visitor
       $data['text_welcome'] = sprintf(
                                $this->language->get('text_welcome'), 
                                $this->url->link('account/register', '', 'SSL'), 
                                $this->language->get('text_visitor')
                            );
                
            
        $data['text_welcome_mobile'] = sprintf($this->language->get('text_welcome_mobile'), $this->url->link('account/login', '', 'SSL'), $this->url->link('account/register', '', 'SSL'));
        
                
                        // welcome text for customer
        $data['text_logged'] = sprintf($this->language->get('text_logged'), '<a href="'.$this->url->link('account/logout', '', 'SSL').'">Logout</a>', $this->customer->getFirstName());
                
            
        /*text_logged_mobile use wih mobile devices*/
       $data['text_logged_mobile'] = sprintf($this->language->get('text_logged_mobile'), $this->url->link('account/account', '', 'SSL'), $this->customer->getFirstName(), $this->url->link('account/logout', '', 'SSL'));
		
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            //$data['error_warning'] = isset($this->session->data['loginwarning']) ? $this->session->data['loginwarning'] : '';
            $data['error_warning'] = "";
        }

        if(isset($this->session->data['loginwarning']))
        {
            $data['loginwarning'] = $this->session->data['loginwarning'];
            unset($this->session->data['loginwarning']);
        } else {
            $data['loginwarning'] = "";
        }

        $data['action'] = $this->url->link('common/quick_login');

        if (isset($this->request->post['email'])){
            $data['email'] = $this->request->post['email'];
        }
        else{
            $data['email'] = '';
        }

        if (isset($this->request->post['password'])){
            $data['password'] = $this->request->post['password'];
        }
        else{
            $data['password'] = '';
        }
		$data['logged'] = $this->customer->isLogged();
        //echo "<pre>";print_r($data);
        //die();
       
	   if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/quicklogin/quick_login.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/common/quicklogin/quick_login.tpl', $data);
		} else {
			return $this->load->view('default/template/common/quicklogin/quick_login.tpl', $data);
		}
	    
    }
    
    public function removeError()
    {
		unset($this->session->data['loginwarning']);
		echo json_encode("done");
	}
	
	 public function checklogin(){
		
		if ($this->customer->isLogged()){
            $this->response->redirect($this->url->link('common/quick_login/logged'));
        }

        $this->load->language('module/quick_login');
        $this->document->setTitle($this->language->get('heading_title'));

        if ($this->request->server["REQUEST_METHOD"]=="POST" && $this->validate()){
            $this->logged();
            return false;
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_register'] = $this->language->get('text_register');
        $data['text_forgotten'] = $this->language->get('text_forgotten');

        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_password'] = $this->language->get('entry_password');

        $data['button_login'] = $this->language->get('button_login');

        $data['register'] = $this->url->link('account/register', '', 'SSL');
        $data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');

        $data['error_email'] = $this->language->get('error_email');
		
		
                    if ($this->customer->isLogged()){
                       $data['text_login'] = $this->customer->getFirstName();
                    }
                    else{
                       $data['text_login'] = $this->language->get('text_login');
                    }
                   $data['text_hidden_login'] = $this->language->get('text_login');
                        // welcome text for visitor
                   $data['text_welcome'] = sprintf(
                                            $this->language->get('text_welcome'), 
                                            $this->url->link('account/register', '', 'SSL'), 
                                            $this->language->get('text_visitor')
                    );
                
            
       $data['text_welcome_mobile'] = sprintf($this->language->get('text_welcome_mobile'), $this->url->link('account/login', '', 'SSL'), $this->url->link('account/register', '', 'SSL'));
        
                
                        // welcome text for customer
                   $data['text_logged'] = sprintf($this->language->get('text_logged'), '<a href="'.$this->url->link('account/logout', '', 'SSL').'">Logout</a>', $this->customer->getFirstName());
                
            
        /*text_logged_mobile use wih mobile devices*/
       $data['text_logged_mobile'] = sprintf($this->language->get('text_logged_mobile'), $this->url->link('account/account', '', 'SSL'), $this->customer->getFirstName(), $this->url->link('account/logout', '', 'SSL'));
		
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = isset($this->session->data['loginwarning']) ? $this->session->data['loginwarning'] : '';
        }

        $data['action'] = $this->url->link('common/quick_login');

        if (isset($this->request->post['email'])){
            $data['email'] = $this->request->post['email'];
        }
        else{
            $data['email'] = '';
        }

        if (isset($this->request->post['password'])){
            $data['password'] = $this->request->post['password'];
        }
        else{
            $data['password'] = '';
        }
		$data['logged'] = $this->customer->isLogged();
       
	   if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/quicklogin/quick_login_form.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/quicklogin/quick_login_form.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/common/quicklogin/quick_login_form.tpl', $data));
		}
	    
    }

    protected function validate(){
		
		unset($this->session->data['loginwarning']);
		
		if (!$this->customer->login($this->request->post['email'], $this->request->post['password'])) {
            $this->error['warning'] = $this->language->get('error_login');
            //$this->session->data['loginwarning'] = $this->language->get('error_login');
            $this->session->data['loginwarning'] = "Warning: No match for E-Mail Address and/or Password.";
        }
		
		
        $this->load->model('account/customer');
        $customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);

        if ($customer_info && !$customer_info['approved']) {
            $this->error['warning'] = $this->language->get('error_approved');
            $this->session->data['loginwarning'] = $this->language->get('error_login');
        }
		
		//print_r($this->error);
        if (!$this->error) {
			$this->load->model('account/address');

			if ($this->config->get('config_tax_customer') == 'payment') {
				$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
			}

			if ($this->config->get('config_tax_customer') == 'shipping') {
				$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
			}

			// Wishlist
			if (isset($this->session->data['wishlist']) && is_array($this->session->data['wishlist'])) {
				$this->load->model('account/wishlist');

				foreach ($this->session->data['wishlist'] as $key => $product_id) {
					$this->model_account_wishlist->addWishlist($product_id);

					unset($this->session->data['wishlist'][$key]);
				}
			}

			// Add to activity log
			$this->load->model('account/activity');

			$activity_data = array(
				'customer_id' => $this->customer->getId(),
				'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
			);

			$this->model_account_activity->addActivity('login', $activity_data);

			// Trigger customer post login event
			//$this->event->trigger('post.customer.login');
            //return true;
            $this->event->trigger('post.customer.login');
            //$this->response->redirect($this->url->link($this->request->post['current_route']));
            $this->response->redirect($this->request->post['current_route']);
        } else {
			//echo 'hereeeee5d';
            //return false;
            if ( isset($this->request->post['current_route']) && $this->request->post['current_route'] == 'account/login' )
            {
                return false;
            } else {
                //$this->response->redirect($this->url->link($this->request->post['current_route'])); 
                $this->response->redirect($this->request->post['current_route']);
                //return false;
            }
        }
    }

    public function logged() {
            // it is no more necessary to keep
        
		
		unset($this->request->post['email']);
       unset($this->request->post['password']);
        unset($this->request->post['button_login']);

        $this->language->load('module/quick_login');

        if ($this->customer->isLogged()){
            $data['text_logged'] = sprintf($this->language->get('text_logged'), $this->customer->getFirstName());
            $data['text_name'] = $this->customer->getFirstName();
        }
        else{
            $data['text_logged'] = '';
            $data['text_name'] = '';
        }
		
		$this->load->model('account/address');

			//if ($this->config->get('config_tax_customer') == 'payment') {
				$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
			//}

			//if ($this->config->get('config_tax_customer') == 'shipping') {
				$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
			//}
			
			//print_r($this->session->data['payment_address']);
		
        $data['text_my_account'] = $this->language->get('text_my_account');
        $data['text_edit_account'] = $this->language->get('text_edit_account');
        $data['text_password'] = $this->language->get('text_password');
        $data['text_address_books'] = $this->language->get('text_address_books');
        $data['text_wish_list'] = $this->language->get('text_wish_list');
        $data['text_order_history'] = $this->language->get('text_order_history');
        $data['text_downloads'] = $this->language->get('text_downloads');
        $data['text_returns'] = $this->language->get('text_returns');
        $data['text_transactions'] = $this->language->get('text_transactions');
        $data['text_newsletter'] = $this->language->get('text_newsletter');
        $data['text_logout'] = $this->language->get('text_logout');

        $data['my_account'] = $this->url->link('account/account', '', 'SSL');
        $data['edit_account'] = $this->url->link('account/edit', '', 'SSL');
        $data['password'] = $this->url->link('account/password', '', 'SSL');
        $data['address_books'] = $this->url->link('account/address', '', 'SSL');
        $data['wish_list'] = $this->url->link('account/wishlist');
        $data['order_history'] = $this->url->link('account/order', '', 'SSL');
        $data['downloads'] = $this->url->link('account/download', '', 'SSL');
        $data['returns'] = $this->url->link('account/return', '', 'SSL');
        $data['transactions'] = $this->url->link('account/transaction', '', 'SSL');
        $data['newsletter'] = $this->url->link('account/newsletter', '', 'SSL');
        $data['logout'] = $this->url->link('account/logout', '', 'SSL');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/quicklogin/quick_login_logged.tpl')) {
			echo $this->load->view($this->config->get('config_template') . '/template/common/quicklogin/quick_login_logged.tpl', $data);
			exit;
		} else {
			echo $this->load->view('default/template/common/quicklogin/quick_login_logged.tpl', $data);
			exit;
		}
		
    }

    public function logout(){
        if ($this->customer->isLogged()) {
            $this->customer->logout();
            $this->cart->clear();
            unset($this->session->data['wishlist']);
            unset($this->session->data['shipping_address_id']);
            unset($this->session->data['shipping_country_id']);
            unset($this->session->data['shipping_zone_id']);
            unset($this->session->data['shipping_postcode']);
            unset($this->session->data['shipping_method']);
            unset($this->session->data['shipping_methods']);
            unset($this->session->data['payment_address_id']);
            unset($this->session->data['payment_country_id']);
            unset($this->session->data['payment_zone_id']);
            unset($this->session->data['payment_method']);
            unset($this->session->data['payment_methods']);
            unset($this->session->data['comment']);
            unset($this->session->data['order_id']);
            unset($this->session->data['coupon']);
            unset($this->session->data['reward']);
            unset($this->session->data['voucher']);
            unset($this->session->data['vouchers']);
        }
    }

}