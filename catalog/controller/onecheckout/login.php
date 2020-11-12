<?php  

class ControllerOneCheckoutLogin extends Controller { 

	public function index() {

		$this->language->load('onecheckout/checkout');		
		$this->load->model('onecheckout/checkout');
		$json = array();		

		if ((!$this->cart->hasProducts() && (!isset($this->session->data['vouchers']) || !$this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {

			$json['redirect'] = $this->url->link('checkout/cart');

		}			

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {

			if (isset($this->request->post['account'])) {

				$this->session->data['account'] = $this->request->post['account'];

			}	

			if (isset($this->request->post['email']) && isset($this->request->post['password'])) {

				if ($this->customer->login($this->request->post['email'], $this->request->post['password'])) {

					unset($this->session->data['guest']);	
					
					$this->session->data['payment_address_id'] = $this->customer->getAddressId();
					$this->session->data['shipping_address_id'] = $this->customer->getAddressId();	
					
					$json['logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', 'SSL'), $this->customer->getFirstName(), $this->url->link('account/logout', '', 'SSL'));					
					
					$json['hasshipping'] = $this->cart->hasShipping();
					
					$version_int = $this->model_onecheckout_checkout->versiontoint();
					//version
					if($version_int < 1513 && $version_int >= 1500){
						$address_info = $this->model_onecheckout_checkout->getAddress($this->customer->getAddressId());	
						if ($address_info) {
							$this->tax->setZone($address_info['country_id'], $address_info['zone_id']);
						}
					}elseif ($version_int >= 1530) {
						$address_info = $this->model_onecheckout_checkout->getAddress($this->customer->getAddressId());	
									
						if ($address_info) {
							if ($this->config->get('config_tax_customer') == 'shipping') {
								$this->session->data['shipping_country_id'] = $address_info['country_id'];
								$this->session->data['shipping_zone_id'] = $address_info['zone_id'];
								$this->session->data['shipping_postcode'] = $address_info['postcode'];	
							}
				
							if ($this->config->get('config_tax_customer') == 'payment') {
								$this->session->data['payment_country_id'] = $address_info['country_id'];
								$this->session->data['payment_zone_id'] = $address_info['zone_id'];
							}
						} else {
							unset($this->session->data['shipping_country_id']);	
							unset($this->session->data['shipping_zone_id']);	
							unset($this->session->data['shipping_postcode']);
							unset($this->session->data['payment_country_id']);	
							unset($this->session->data['payment_zone_id']);	
						}
						
					}

				} else {

					$json['error']['warning'] = $this->language->get('error_login');

				}

			}

		} else {

			$data['text_forgotten'] = $this->language->get('text_forgotten');	 

			$data['entry_email'] = $this->language->get('entry_email');

			$data['entry_password'] = $this->language->get('entry_password');

			$data['button_login'] = $this->language->get('button_login');			

			$data['guest_checkout'] = ($this->config->get('config_guest_checkout') && !$this->config->get('config_customer_price') && !$this->cart->hasDownload());
			

			if (isset($this->session->data['account'])) {

				$data['account'] = $this->session->data['account'];

			} else {

				$data['account'] = 'register';

			}			

			$data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');			

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/onecheckout/login.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/onecheckout/login.tpl';
			} else {
				$this->template = 'default/template/onecheckout/login.tpl';
			}					

			$json['output'] = $this->render();

		}

		$this->response->setOutput($this->model_onecheckout_checkout->jsonencode($json));		

	}

}

?>