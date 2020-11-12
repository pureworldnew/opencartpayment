<?php 

class ControllerOneCheckoutGuest extends Controller {

  	public function index() {

    	$this->language->load('onecheckout/checkout');
		$this->load->model('onecheckout/checkout');	
		$version_int = $this->model_onecheckout_checkout->versiontoint();

		$json = array();		

		if ($this->customer->isLogged()) {
			$json['redirect'] = $this->url->link('onecheckout/checkout', '', 'SSL');
		} 			
		
		if ((!$this->cart->hasProducts() && (!isset($this->session->data['vouchers']) || !$this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');	
		}					

		if (!$this->config->get('config_guest_checkout') || $this->cart->hasDownload()) {
			$json['redirect'] = $this->url->link('onecheckout/checkout', '', 'SSL');
		} 

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (!$json) {

				if ((strlen(utf8_decode($this->request->post['firstname'])) < 1) || (strlen(utf8_decode($this->request->post['firstname'])) > 32)) {

					$json['error']['firstname'] = $this->language->get('error_firstname');

				}

		

				if ((strlen(utf8_decode($this->request->post['lastname'])) < 1) || (strlen(utf8_decode($this->request->post['lastname'])) > 32)) {

					$json['error']['lastname'] = $this->language->get('error_lastname');

				}

		

				if ((strlen(utf8_decode($this->request->post['email'])) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {

					$json['error']['email'] = $this->language->get('error_email');

				}

				

				if ((strlen(utf8_decode($this->request->post['telephone'])) < 3) || (strlen(utf8_decode($this->request->post['telephone'])) > 32)) {

					$json['error']['telephone'] = $this->language->get('error_telephone');

				}
				
				//version
				if($version_int >= 1530){
					// Customer Group
					$this->load->model('account/customer_group');
			
					if (isset($this->request->post['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->post['customer_group_id'], $this->config->get('config_customer_group_display'))) {
						$customer_group_id = $this->request->post['customer_group_id'];
					} else {
						$customer_group_id = $this->config->get('config_customer_group_id');
					}
			
					$customer_group = $this->model_account_customer_group->getCustomerGroup($customer_group_id);
				
					if ($customer_group) {	
						// Company ID
						if ($customer_group['company_id_display'] && $customer_group['company_id_required'] && empty($this->request->post['company_id'])) {
							$json['error']['company_id'] = $this->language->get('error_company_id');
						}
				
						// Tax ID
						if ($customer_group['tax_id_display'] && $customer_group['tax_id_required'] && empty($this->request->post['tax_id'])) {
							$json['error']['tax_id'] = $this->language->get('error_tax_id');
						}						
					}
				}

				

				if ((strlen(utf8_decode($this->request->post['address_1'])) < 3) || (strlen(utf8_decode($this->request->post['address_1'])) > 128)) {

					$json['error']['address_1'] = $this->language->get('error_address_1');

				}		

				if ((strlen(utf8_decode($this->request->post['city'])) < 2) || (strlen(utf8_decode($this->request->post['city'])) > 128)) {

					$json['error']['city'] = $this->language->get('error_city');

				}
				$country_info = $this->model_onecheckout_checkout->getCountry($this->request->post['country_id']);
				

				if ($country_info){
				
					if($country_info['postcode_required'] && (strlen(utf8_decode($this->request->post['postcode'])) < 2) || (strlen(utf8_decode($this->request->post['postcode'])) > 10)) {

						$json['error']['postcode'] = $this->language->get('error_postcode');
					}
					
					if($version_int >= 1530){
						// VAT Validation
						$this->load->helper('vat');
				
						if ($this->config->get('config_vat') && $this->request->post['tax_id'] && (vat_validation($country_info['iso_code_2'], $this->request->post['tax_id']) == 'invalid')) {
							$json['error']['tax_id'] = $this->language->get('error_vat');
						}	
					}

				}
		

				if ($this->request->post['country_id'] == '') {
					$json['error']['country'] = $this->language->get('error_country');
				}				

				if ($this->request->post['zone_id'] == '') {
					$json['error']['zone'] = $this->language->get('error_zone');
				}	

			}			

			if (!$json) {

				$this->session->data['guest']['customer_group_id'] = isset($customer_group_id) ? $customer_group_id : $this->config->get('config_customer_group_id');
				$this->session->data['guest']['firstname'] = $this->request->post['firstname'];
				$this->session->data['guest']['lastname'] = $this->request->post['lastname'];
				$this->session->data['guest']['email'] = $this->request->post['email'];
				$this->session->data['guest']['telephone'] = $this->request->post['telephone'];
				$this->session->data['guest']['fax'] = isset($this->request->post['fax']) ? $this->request->post['fax'] : '';				
				$this->session->data['guest']['payment']['firstname'] = $this->request->post['firstname'];
				$this->session->data['guest']['payment']['lastname'] = $this->request->post['lastname'];
				$this->session->data['guest']['payment']['company'] = isset($this->request->post['company']) ? $this->request->post['company'] : '';
				$this->session->data['guest']['payment']['company_id'] = isset($this->request->post['company_id']) ? $this->request->post['company_id'] : '';
				$this->session->data['guest']['payment']['tax_id'] = isset($this->request->post['tax_id']) ? $this->request->post['tax_id'] : '';
				$this->session->data['guest']['payment']['address_1'] = $this->request->post['address_1'];
				$this->session->data['guest']['payment']['address_2'] = $this->request->post['address_2'];
				$this->session->data['guest']['payment']['postcode'] = $this->request->post['postcode'];
				$this->session->data['guest']['payment']['city'] = $this->request->post['city'];
				$this->session->data['guest']['payment']['country_id'] = $this->request->post['country_id'];
				$this->session->data['guest']['payment']['zone_id'] = $this->request->post['zone_id'];
				

				$country_info = $this->model_onecheckout_checkout->getCountry($this->request->post['country_id']);
				

				if ($country_info) {

					$this->session->data['guest']['payment']['country'] = $country_info['name'];	

					$this->session->data['guest']['payment']['iso_code_2'] = $country_info['iso_code_2'];

					$this->session->data['guest']['payment']['iso_code_3'] = $country_info['iso_code_3'];

					$this->session->data['guest']['payment']['address_format'] = $country_info['address_format'];

				} else {

					$this->session->data['guest']['payment']['country'] = '';	

					$this->session->data['guest']['payment']['iso_code_2'] = '';

					$this->session->data['guest']['payment']['iso_code_3'] = '';

					$this->session->data['guest']['payment']['address_format'] = '';

				}


				$zone_info = $this->model_onecheckout_checkout->getZone($this->request->post['zone_id']);				

				if ($zone_info) {
					$this->session->data['guest']['payment']['zone'] = $zone_info['name'];
					$this->session->data['guest']['payment']['zone_code'] = $zone_info['code'];
				} else {

					$this->session->data['guest']['payment']['zone'] = '';
					$this->session->data['guest']['payment']['zone_code'] = '';

				}
				

				if (isset($this->request->post['shipping_address']) && $this->request->post['shipping_address']) {

					$this->session->data['guest']['shipping_address'] = true;

				} else {

					$this->session->data['guest']['shipping_address'] = false;

				}

				// Default Payment Address
				if ($this->config->get('config_tax_customer') == 'payment') {
					$this->session->data['payment_country_id'] = $this->request->post['country_id'];
					$this->session->data['payment_zone_id'] = $this->request->post['zone_id'];
				}

				if ($this->session->data['guest']['shipping_address']) {

					$this->session->data['guest']['shipping']['firstname'] = $this->request->post['firstname'];

					$this->session->data['guest']['shipping']['lastname'] = $this->request->post['lastname'];

					$this->session->data['guest']['shipping']['company'] = isset($this->request->post['company']) ? $this->request->post['company'] : '';

					$this->session->data['guest']['shipping']['address_1'] = $this->request->post['address_1'];

					$this->session->data['guest']['shipping']['address_2'] = $this->request->post['address_2'];

					$this->session->data['guest']['shipping']['postcode'] = $this->request->post['postcode'];

					$this->session->data['guest']['shipping']['city'] = $this->request->post['city'];

					$this->session->data['guest']['shipping']['country_id'] = $this->request->post['country_id'];

					$this->session->data['guest']['shipping']['zone_id'] = $this->request->post['zone_id'];

					

					if ($country_info) {

						$this->session->data['guest']['shipping']['country'] = $country_info['name'];	

						$this->session->data['guest']['shipping']['iso_code_2'] = $country_info['iso_code_2'];

						$this->session->data['guest']['shipping']['iso_code_3'] = $country_info['iso_code_3'];

						$this->session->data['guest']['shipping']['address_format'] = $country_info['address_format'];

					} else {

						$this->session->data['guest']['shipping']['country'] = '';	

						$this->session->data['guest']['shipping']['iso_code_2'] = '';

						$this->session->data['guest']['shipping']['iso_code_3'] = '';

						$this->session->data['guest']['shipping']['address_format'] = '';

					}


					if ($zone_info) {

						$this->session->data['guest']['shipping']['zone'] = $zone_info['name'];

						$this->session->data['guest']['shipping']['zone_code'] = $zone_info['code'];

					} else {

						$this->session->data['guest']['shipping']['zone'] = '';

						$this->session->data['guest']['shipping']['zone_code'] = '';

					}
					$version_int = $this->model_onecheckout_checkout->versiontoint();
					//version
					if($version_int < 1513 && $version_int >= 1500){
						$this->tax->setZone($this->request->post['country_id'], $this->request->post['zone_id']);
					}
					
					// Default Shipping Address
					if ($this->config->get('config_tax_customer') == 'shipping') {
						$this->session->data['shipping_country_id'] = $this->request->post['country_id'];
						$this->session->data['shipping_zone_id'] = $this->request->post['zone_id'];
						$this->session->data['shipping_postcode'] = $this->request->post['postcode'];
					}
				}

				

				unset($this->session->data['shipping_methods']);

				unset($this->session->data['shipping_method']);

				unset($this->session->data['payment_methods']);

				unset($this->session->data['payment_method']);

			}

    	} else {

			$data['entry_shipping'] = $this->language->get('entry_shipping');
			
			$data['button_continue'] = $this->language->get('button_continue');

			$data['shipping_required'] = $this->cart->hasShipping();

			

			if (isset($this->session->data['guest']['shipping_address'])) {

				$data['shipping_address'] = $this->session->data['guest']['shipping_address'];			

			} else {

				$data['shipping_address'] = true;

			}			

			

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/onecheckout/guest.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/onecheckout/guest.tpl';
			} else {
				$this->template = 'default/template/onecheckout/guest.tpl';
			}

					

			$json['output'] = $this->render();	

		}
		$this->response->setOutput($this->model_onecheckout_checkout->jsonencode($json));			

  	}

  	

	public function shipping() {

		$this->language->load('onecheckout/checkout');
		$this->load->model('onecheckout/checkout');
		$version_int = $this->model_onecheckout_checkout->versiontoint();
		
		$json = array();		

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {

			if ((strlen(utf8_decode($this->request->post['firstname'])) < 1) || (strlen(utf8_decode($this->request->post['firstname'])) > 32)) {

				$json['error']['firstname'] = $this->language->get('error_firstname');

			}

	

			if ((strlen(utf8_decode($this->request->post['lastname'])) < 1) || (strlen(utf8_decode($this->request->post['lastname'])) > 32)) {

				$json['error']['lastname'] = $this->language->get('error_lastname');

			}
			

			if ((strlen(utf8_decode($this->request->post['address_1'])) < 3) || (strlen(utf8_decode($this->request->post['address_1'])) > 128)) {

				$json['error']['address_1'] = $this->language->get('error_address_1');

			}
	

			if ((strlen(utf8_decode($this->request->post['city'])) < 2) || (strlen(utf8_decode($this->request->post['city'])) > 128)) {

				$json['error']['city'] = $this->language->get('error_city');

			}			

			$country_info = $this->model_onecheckout_checkout->getCountry($this->request->post['country_id']);

			

			if ($country_info && $country_info['postcode_required'] && (strlen(utf8_decode($this->request->post['postcode'])) < 2) || (strlen(utf8_decode($this->request->post['postcode'])) > 10)) {

				$json['error']['postcode'] = $this->language->get('error_postcode');

			}	

			if ($this->request->post['country_id'] == '') {
				$json['error']['country'] = $this->language->get('error_country');
			}			

			if ($this->request->post['zone_id'] == '') {
				$json['error']['zone'] = $this->language->get('error_zone');
			}							

			if (!$json) {

				$this->session->data['guest']['shipping']['firstname'] = trim($this->request->post['firstname']);
				$this->session->data['guest']['shipping']['lastname'] = trim($this->request->post['lastname']);

				$this->session->data['guest']['shipping']['company'] = trim($this->request->post['company']);

				$this->session->data['guest']['shipping']['address_1'] = $this->request->post['address_1'];

				$this->session->data['guest']['shipping']['address_2'] = $this->request->post['address_2'];

				$this->session->data['guest']['shipping']['postcode'] = $this->request->post['postcode'];

				$this->session->data['guest']['shipping']['city'] = $this->request->post['city'];

				$this->session->data['guest']['shipping']['country_id'] = $this->request->post['country_id'];

				$this->session->data['guest']['shipping']['zone_id'] = $this->request->post['zone_id'];

				$country_info = $this->model_onecheckout_checkout->getCountry($this->request->post['country_id']);
				

				if ($country_info) {

					$this->session->data['guest']['shipping']['country'] = $country_info['name'];	

					$this->session->data['guest']['shipping']['iso_code_2'] = $country_info['iso_code_2'];

					$this->session->data['guest']['shipping']['iso_code_3'] = $country_info['iso_code_3'];

					$this->session->data['guest']['shipping']['address_format'] = $country_info['address_format'];

				} else {

					$this->session->data['guest']['shipping']['country'] = '';	

					$this->session->data['guest']['shipping']['iso_code_2'] = '';

					$this->session->data['guest']['shipping']['iso_code_3'] = '';

					$this->session->data['guest']['shipping']['address_format'] = '';

				}

				$zone_info = $this->model_onecheckout_checkout->getZone($this->request->post['zone_id']);			

				if ($zone_info) {

					$this->session->data['guest']['shipping']['zone'] = $zone_info['name'];

					$this->session->data['guest']['shipping']['zone_code'] = $zone_info['code'];

				} else {

					$this->session->data['guest']['shipping']['zone'] = '';

					$this->session->data['guest']['shipping']['zone_code'] = '';

				}	
				
				$version_int = $this->model_onecheckout_checkout->versiontoint();
				//version
				if($version_int < 1513 && $version_int >= 1500){
					if ($this->cart->hasShipping()) {
						$this->tax->setZone($this->request->post['country_id'], $this->request->post['zone_id']);
					}
				}elseif( $version_int >= 1530){
					// Default Shipping Address
					if ($this->config->get('config_tax_customer') == 'shipping') {
						$this->session->data['shipping_country_id'] = $this->request->post['country_id'];
						$this->session->data['shipping_zone_id'] = $this->request->post['zone_id'];
						$this->session->data['shipping_postcode'] = $this->request->post['postcode'];				
					}				
				}		

			}
			
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['shipping_method']);

		} else {

			$data['text_select'] = $this->language->get('text_select');
			$data['text_none'] = $this->language->get('text_none');

			$data['entry_firstname'] = $this->language->get('entry_firstname');

			$data['entry_lastname'] = $this->language->get('entry_lastname');

			$data['entry_company'] = $this->language->get('entry_company');

			$data['entry_address_1'] = $this->language->get('entry_address_1');

			$data['entry_address_2'] = $this->language->get('entry_address_2');

			$data['entry_postcode'] = $this->language->get('entry_postcode');

			$data['entry_city'] = $this->language->get('entry_city');

			$data['entry_country'] = $this->language->get('entry_country');

			$data['entry_zone'] = $this->language->get('entry_zone');
					

			if (isset($this->session->data['guest']['shipping']['firstname'])) {

				$data['firstname'] = $this->session->data['guest']['shipping']['firstname'];

			} else {

				$data['firstname'] = '';

			}

	

			if (isset($this->session->data['guest']['shipping']['lastname'])) {

				$data['lastname'] = $this->session->data['guest']['shipping']['lastname'];

			} else {

				$data['lastname'] = '';

			}

			

			if (isset($this->session->data['guest']['shipping']['company'])) {

				$data['company'] = $this->session->data['guest']['shipping']['company'];			

			} else {

				$data['company'] = '';

			}

			

			if (isset($this->session->data['guest']['shipping']['address_1'])) {

				$data['address_1'] = $this->session->data['guest']['shipping']['address_1'];			

			} else {

				$data['address_1'] = '';

			}

	

			if (isset($this->session->data['guest']['shipping']['address_2'])) {

				$data['address_2'] = $this->session->data['guest']['shipping']['address_2'];			

			} else {

				$data['address_2'] = '';

			}

	

			if (isset($this->session->data['guest']['shipping']['postcode'])) {

				$data['postcode'] = $this->session->data['guest']['shipping']['postcode'];					

			} elseif (isset($this->session->data['shipping_postcode'])) {
				$data['postcode'] = $this->session->data['shipping_postcode'];								
			} else {

				$data['postcode'] = '';

			}

			

			if (isset($this->session->data['guest']['shipping']['city'])) {

				$data['city'] = $this->session->data['guest']['shipping']['city'];			

			} else {

				$data['city'] = '';

			}

	

			if (isset($this->session->data['guest']['shipping']['country_id'])) {

				$data['country_id'] = $this->session->data['guest']['shipping']['country_id'];			  	

			} elseif (isset($this->session->data['shipping_country_id'])) {
				$data['country_id'] = $this->session->data['shipping_country_id'];		
			} else {

				$data['country_id'] = $this->config->get('config_country_id');

			}

	

			if (isset($this->session->data['guest']['shipping']['zone_id'])) {

				$data['zone_id'] = $this->session->data['guest']['shipping']['zone_id'];			

			} elseif (isset($this->session->data['shipping_zone_id'])) {
				$data['zone_id'] = $this->session->data['shipping_zone_id'];						
			} else {

				$data['zone_id'] = '';

			}

			$data['countries'] = $this->model_onecheckout_checkout->getCountries();
			

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/onecheckout/guest_shipping.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/onecheckout/guest_shipping.tpl';
			} else {
				$this->template = 'default/template/onecheckout/guest_shipping.tpl';
			}		

			

			$json['output'] = $this->render();	

		}

		$this->response->setOutput($this->model_onecheckout_checkout->jsonencode($json));		

	}

	

  	public function zone() {

		$output = '<option value="">' . $this->language->get('text_select') . '</option>';
		$this->load->model('onecheckout/checkout');
    	$results = $this->model_onecheckout_checkout->getZonesByCountryId($this->request->get['country_id']);
      	foreach ($results as $result) {

        	$output .= '<option value="' . $result['zone_id'] . '"';
	    	if (isset($this->request->get['zone_id']) && ($this->request->get['zone_id'] == $result['zone_id'])) {

	      		$output .= ' selected="selected"';

	    	}
	    	$output .= '>' . $result['name'] . '</option>';

    	} 

		

		if (!$results) {

		  	$output .= '<option value="0">' . $this->language->get('text_none') . '</option>';

		}
		$this->response->setOutput($output);

  	}	

}

?>