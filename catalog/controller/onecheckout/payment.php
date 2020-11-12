<?php  

class ControllerOneCheckoutPayment extends Controller {

  	public function index() {

		$this->language->load('onecheckout/checkout');		

		$json = array();		

		$this->load->model('onecheckout/checkout');		

		if ($this->customer->isLogged()) {

			if (isset($this->session->data['payment_address_id'])) {

				$payment_address = $this->model_onecheckout_checkout->getAddress($this->session->data['payment_address_id']);

			} else {

				$payment_address = $this->model_onecheckout_checkout->getAddress($this->customer->getAddressId());				

			}					

		} elseif (isset($this->session->data['guest']['payment'])) {

			$payment_address = $this->session->data['guest']['payment'];

		} else {
			
			if (isset($this->request->get['countryid']) && isset($this->request->get['zoneid'])){
			
				$payment_address['country_id'] = $this->request->get['countryid'];			
				$payment_address['zone_id'] = $this->request->get['zoneid'];
				$payment_address['city'] = $this->request->get['city'];
				$payment_address['postcode'] = $this->request->get['postcode'];
				
				$this->session->data['guest']['payment']['country_id'] = $this->request->get['countryid'];
				$this->session->data['guest']['payment']['zone_id'] = $this->request->get['zoneid'];
				$this->session->data['guest']['payment']['city'] = $this->request->get['city'];
				$this->session->data['guest']['payment']['postcode'] = $this->request->get['postcode'];
			}
		}
		
		if (isset($this->request->get['isset']) && $this->request->get['isset']) {
			
			$payment_address['country_id'] = $this->request->get['countryid'];			
			$payment_address['zone_id'] = $this->request->get['zoneid'];
			$payment_address['city'] = $this->request->get['city'];
			$payment_address['postcode'] = $this->request->get['postcode'];
			
			$this->session->data['guest']['payment']['country_id'] = $this->request->get['countryid'];
			$this->session->data['guest']['payment']['zone_id'] = $this->request->get['zoneid'];
			$this->session->data['guest']['payment']['city'] = $this->request->get['city'];
			$this->session->data['guest']['payment']['postcode'] = $this->request->get['postcode'];
			unset($this->session->data['payment_address_id']);
			unset($payment_address['iso_code_2']);
		}
		
		if (isset($this->request->get['addressid']) && $this->request->get['addressid']) {
		
			$payment_address = $this->model_onecheckout_checkout->getAddress($this->request->get['addressid']);
			$this->session->data['payment_address_id'] = $this->request->get['addressid'];
			unset($this->session->data['guest']['payment']['country_id']);
			unset($this->session->data['guest']['payment']['zone_id']);
		
		}	
		

		if (!isset($payment_address)) {

			$json['redirect'] = $this->url->link('onecheckout/checkout', '', 'SSL');

		}else {
			// Default Payment Address
			if ($this->config->get('config_tax_customer') == 'payment') {
				$this->session->data['payment_country_id'] = $payment_address['country_id'];
				$this->session->data['payment_zone_id'] = $payment_address['zone_id'];			
			}
		}	
		
		if(!isset($payment_address['iso_code_2'])){
			$country_info = $this->model_onecheckout_checkout->getCountry($payment_address['country_id']);
		
			if ($country_info) {
				$payment_address['country'] = $country_info['name'];
				$payment_address['iso_code_2'] = $country_info['iso_code_2'];
				$payment_address['iso_code_3'] = $country_info['iso_code_3'];
				$payment_address['address_format'] = $country_info['address_format'];
			
				$this->session->data['guest']['payment']['country'] = $country_info['name'];
				$this->session->data['guest']['payment']['iso_code_2'] = $country_info['iso_code_2'];
				$this->session->data['guest']['payment']['iso_code_3'] = $country_info['iso_code_3'];
				$this->session->data['guest']['payment']['address_format'] = $country_info['address_format'];
			} else {
				$payment_address['country'] = '';	
				$payment_address['iso_code_2'] = '';
				$payment_address['iso_code_3'] = '';
				$payment_address['address_format'] = '';
			
				$this->session->data['guest']['payment']['country'] = '';	
				$this->session->data['guest']['payment']['iso_code_2'] = '';
				$this->session->data['guest']['payment']['iso_code_3'] = '';
				$this->session->data['guest']['payment']['address_format'] = '';
			}
		
			$zone_info = $this->model_onecheckout_checkout->getZone($payment_address['zone_id']);

			if ($zone_info) {
				$payment_address['zone'] = $zone_info['name'];
				$payment_address['zone_code'] = $zone_info['code'];
			
				$this->session->data['guest']['payment']['zone'] = $zone_info['name'];
				$this->session->data['guest']['payment']['zone_code'] = $zone_info['code'];
			} else {
				$payment_address['zone'] = '';
				$payment_address['zone_code'] = '';
			
				$this->session->data['guest']['payment']['zone'] = '';
				$this->session->data['guest']['payment']['zone_code'] = '';
			}
		}		

		if ((!$this->cart->hasProducts() && (!isset($this->session->data['vouchers']) || !$this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {

			$json['redirect'] = $this->url->link('checkout/cart');				

		}	
		
		if(isset($this->session->data['payment_methods'])){
			
			unset($this->session->data['payment_methods']);
			
		}
		
		if (!isset($this->session->data['payment_methods'])) {

				// Calculate Totals

				$total_data = array();					

				$total = 0;

				$taxes = $this->cart->getTaxes();

				$sort_order = array(); 
				$results = $this->model_onecheckout_checkout->getExtensions('total');				

				foreach ($results as $key => $value) {
					$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');

				}
				array_multisort($sort_order, SORT_ASC, $results);
				foreach ($results as $result) {
                                        
					if ($this->config->get($result['code'] . '_status')) {

						$this->load->model('total/' . $result['code']);
						$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
					}
				}


				// Payment Methods

				$method_data = array();	 			

				$results = $this->model_onecheckout_checkout->getExtensions('payment');
				foreach ($results as $result) {

					if ($this->config->get($result['code'] . '_status')) {

						$this->load->model('payment/' . $result['code']);
						$method = $this->{'model_payment_' . $result['code']}->getMethod($payment_address, $total); 

						if ($method) {

							$method_data[$result['code']] = $method;

						}

					}

				}
				$sort_order = array(); 

				foreach ($method_data as $key => $value) {

					$sort_order[$key] = $value['sort_order'];

				}		

				array_multisort($sort_order, SORT_ASC, $method_data);
				$this->session->data['payment_methods'] = $method_data;			

		}		
									

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {

			if (!$json) {

				if (!isset($this->request->post['payment_method'])) {

					$json['error']['warning'] = $this->language->get('error_payment');

				} elseif (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {

					$json['error']['warning'] = $this->language->get('error_payment');

				}								

				
			}

			if (!$json) {

				$this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];			  

				if(isset($this->request->post['comment'])) {
					$this->session->data['comment'] = strip_tags($this->request->post['comment']);
				}

			}

		} else {
		

			$data['text_payment_method'] = $this->language->get('text_payment_method');

			$data['text_comments'] = $this->language->get('text_comments');
			$data['text_checkout_comment'] = $this->language->get('text_checkout_comment');
	   

			if (isset($this->session->data['payment_methods']) && !$this->session->data['payment_methods']) {

				$data['error_warning'] = sprintf($this->language->get('error_no_payment'), $this->url->link('information/contact'));

			} else {

				$data['error_warning'] = '';

			}	

	

			if (isset($this->session->data['payment_methods'])) {

				$data['payment_methods'] = $this->session->data['payment_methods']; 

			} else {

				$data['payment_methods'] = array();

			}

		  

			if (isset($this->session->data['payment_method']['code'])) {

				$data['code'] = $this->session->data['payment_method']['code'];

			} else {

				$data['code'] = '';

			}

			

			if (isset($this->session->data['comment'])) {

				$data['comment'] = $this->session->data['comment'];

			} else {

				$data['comment'] = '';

			}

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/onecheckout/payment.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/onecheckout/payment.tpl';
			} else {
				$this->template = 'default/template/onecheckout/payment.tpl';
			}

					

			$json['output'] = $this->render();	

		}

		$this->response->setOutput($this->model_onecheckout_checkout->jsonencode($json));

  	}
	
	public function savecomment() {
	
		if(isset($this->request->post['comment'])) {
			$this->session->data['comment'] = strip_tags($this->request->post['comment']);
		}
	
	}

}

?>