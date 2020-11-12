<?php 

class ControllerOneCheckoutShipping extends Controller {

  	public function index() {

		$this->language->load('onecheckout/checkout');
		$json = array();
		$this->load->model('onecheckout/checkout');

		if ($this->customer->isLogged()) {	

			if (isset($this->session->data['shipping_address_id'])) {
				$shipping_address = $this->model_onecheckout_checkout->getAddress($this->session->data['shipping_address_id']);

			} else {

				$shipping_address = $this->model_onecheckout_checkout->getAddress($this->customer->getAddressId());

			}			
					

		} elseif (isset($this->session->data['guest']['shipping'])) {

			$shipping_address = $this->session->data['guest']['shipping'];

		} else {
		
			if (isset($this->request->get['countryid']) && isset($this->request->get['zoneid'])){
			
				$shipping_address['country_id'] = $this->request->get['countryid'];			
				$shipping_address['zone_id'] = $this->request->get['zoneid'];
				$shipping_address['city'] = $this->request->get['city'];
				$shipping_address['postcode'] = $this->request->get['postcode'];
				
				$this->session->data['guest']['shipping']['country_id'] = $this->request->get['countryid'];
				$this->session->data['guest']['shipping']['zone_id'] = $this->request->get['zoneid'];
				$this->session->data['guest']['shipping']['city'] = $this->request->get['city'];
				$this->session->data['guest']['shipping']['postcode'] = $this->request->get['postcode'];	
			}
		}
		
		if (isset($this->request->get['isset']) && $this->request->get['isset']) {
			
			$shipping_address['country_id'] = $this->request->get['countryid'];			
			$shipping_address['zone_id'] = $this->request->get['zoneid'];
			$shipping_address['city'] = $this->request->get['city'];
			$shipping_address['postcode'] = $this->request->get['postcode'];
			
			$this->session->data['guest']['shipping']['country_id'] = $this->request->get['countryid'];
			$this->session->data['guest']['shipping']['zone_id'] = $this->request->get['zoneid'];
			$this->session->data['guest']['shipping']['city'] = $this->request->get['city'];
			$this->session->data['guest']['shipping']['postcode'] = $this->request->get['postcode'];
			unset($this->session->data['shipping_address_id']);
			unset($shipping_address['iso_code_2']);
		}
		
		if (isset($this->request->get['addressid']) && $this->request->get['addressid']) {
		
			$shipping_address = $this->model_onecheckout_checkout->getAddress($this->request->get['addressid']);
			$this->session->data['shipping_address_id'] = $this->request->get['addressid'];
			unset($this->session->data['guest']['shipping']['country_id']);
			unset($this->session->data['guest']['shipping']['zone_id']);
		
		}		

		if (empty($shipping_address)) {								

			$json['redirect'] = $this->url->link('onecheckout/checkout', '', 'SSL');

		}
		
		if(!isset($shipping_address['firstname'])){
			$shipping_address['firstname'] = isset($this->session->data['guest']['shipping']['firstname'])?$this->session->data['guest']['shipping']['firstname']:$this->session->data['shipping']['firstname'];
		}
		
		if(!isset($shipping_address['lastname'])){
			$shipping_address['lastname'] = isset($this->session->data['guest']['shipping']['lastname'])?$this->session->data['guest']['shipping']['lastname']:$this->session->data['shipping']['lastname'];
		}	
		
		if(!isset($shipping_address['company'])){
			$shipping_address['company'] = isset($this->session->data['guest']['shipping']['company'])?$this->session->data['guest']['shipping']['company']:$this->session->data['shipping']['company'];
		}
		
		if(!isset($shipping_address['address_1'])){
			$shipping_address['address_1'] = isset($this->session->data['guest']['shipping']['address_1'])?$this->session->data['guest']['shipping']['address_1']:$this->session->data['shipping']['address_1'];
		}
		
		if(!isset($shipping_address['address_2'])){
			$shipping_address['address_2'] = isset($this->session->data['guest']['shipping']['address_2'])?$this->session->data['guest']['shipping']['address_2']:'';
		}
		
		if(!isset($shipping_address['iso_code_2'])){
			$country_info = $this->model_onecheckout_checkout->getCountry($shipping_address['country_id']);
		
			if ($country_info) {
				$shipping_address['country'] = $country_info['name'];
				$shipping_address['iso_code_2'] = $country_info['iso_code_2'];
				$shipping_address['iso_code_3'] = $country_info['iso_code_3'];
				$shipping_address['address_format'] = $country_info['address_format'];
			
				$this->session->data['guest']['shipping']['country'] = $country_info['name'];
				$this->session->data['guest']['shipping']['iso_code_2'] = $country_info['iso_code_2'];
				$this->session->data['guest']['shipping']['iso_code_3'] = $country_info['iso_code_3'];
				$this->session->data['guest']['shipping']['address_format'] = $country_info['address_format'];
			} else {
				$shipping_address['country'] = '';	
				$shipping_address['iso_code_2'] = '';
				$shipping_address['iso_code_3'] = '';
				$shipping_address['address_format'] = '';
			
				$this->session->data['guest']['shipping']['country'] = '';	
				$this->session->data['guest']['shipping']['iso_code_2'] = '';
				$this->session->data['guest']['shipping']['iso_code_3'] = '';
				$this->session->data['guest']['shipping']['address_format'] = '';
			}
		
			$zone_info = $this->model_onecheckout_checkout->getZone($shipping_address['zone_id']);

			if ($zone_info) {
				$shipping_address['zone'] = $zone_info['name'];
				$shipping_address['zone_code'] = $zone_info['code'];
			
				$this->session->data['guest']['shipping']['zone'] = $zone_info['name'];
				$this->session->data['guest']['shipping']['zone_code'] = $zone_info['code'];
			} else {
				$shipping_address['zone'] = '';
				$shipping_address['zone_code'] = '';
			
				$this->session->data['guest']['shipping']['zone'] = '';
				$this->session->data['guest']['shipping']['zone_code'] = '';
			}
		}		
		
		if (isset($this->session->data['shipping_methods'])) {
		
			unset($this->session->data['shipping_methods']);
		
		}				

		if ((!$this->cart->hasProducts() && (!isset($this->session->data['vouchers']) || !$this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {

			$json['redirect'] = $this->url->link('checkout/cart');				

		}	
		
		if (isset($shipping_address)) {		
		
				$version_int = $this->model_onecheckout_checkout->versiontoint();
				//version
				if($version_int < 1513 && $version_int >= 1500){
					$this->tax->setZone($shipping_address['country_id'], $shipping_address['zone_id']);
				}
				// Default Shipping Address
				if ($this->config->get('config_tax_customer') == 'shipping') {
					$this->session->data['shipping_country_id'] = $shipping_address['country_id'];
					$this->session->data['shipping_zone_id'] = $shipping_address['zone_id'];
					$this->session->data['shipping_postcode'] = $shipping_address['postcode'];
				}
				
				if (!isset($this->session->data['shipping_methods'])) {

					$quote_data = array();					
			

					$results = $this->model_onecheckout_checkout->getExtensions('shipping');
					

					foreach ($results as $result) {

						if ($this->config->get($result['code'] . '_status')) {

							$this->load->model('shipping/' . $result['code']);

							

							$quote = $this->{'model_shipping_' . $result['code']}->getQuote($shipping_address); 
				

							if ($quote) {

								$quote_data[$result['code']] = array( 

									'title'      => $quote['title'],

									'quote'      => $quote['quote'], 

									'sort_order' => $quote['sort_order'],

									'error'      => $quote['error']

								);

							}

						}

					}			

					$sort_order = array();				  

					foreach ($quote_data as $key => $value) {

						$sort_order[$key] = $value['sort_order'];

					}			

					array_multisort($sort_order, SORT_ASC, $quote_data);					

					$this->session->data['shipping_methods'] = $quote_data;

				}

		}		

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {

			if (!$json) {

				if (!isset($this->request->post['shipping_method'])) {

					$json['error']['warning'] = $this->language->get('error_shipping');

				} else {

					$shipping = explode('.', $this->request->post['shipping_method']);				

					if (!isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {			

						$json['error']['warning'] = $this->language->get('error_shipping');

					}

				}			

			}			

			if (!$json) {

				$shipping = explode('.', $this->request->post['shipping_method']);				

				$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
			}			

		} else {
						

			$data['text_shipping_method'] = $this->language->get('text_shipping_method');	
			
			$data['text_not_shipping_method'] = $this->language->get('text_not_shipping_method');		

			

			if (isset($this->session->data['shipping_methods']) && !$this->session->data['shipping_methods']) {

				$data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));

			} else {

				$data['error_warning'] = '';

			}	

						

			if (isset($this->session->data['shipping_methods'])) {

				$data['shipping_methods'] = $this->session->data['shipping_methods']; 

			} else {

				$data['shipping_methods'] = array();

			}

			

			if (isset($this->session->data['shipping_method']['code'])) {

				$data['code'] = $this->session->data['shipping_method']['code'];

			} else {

				$data['code'] = '';

			}

			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/onecheckout/shipping.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/onecheckout/shipping.tpl';
			} else {
				$this->template = 'default/template/onecheckout/shipping.tpl';
			}

					

			$json['output'] = $this->render();	

		}
		$this->response->setOutput($this->model_onecheckout_checkout->jsonencode($json));		

  	}

}

?>