<?php 

class ControllerOneCheckoutRegister extends Controller {

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

				if ($this->model_onecheckout_checkout->getTotalCustomersByEmail($this->request->post['email'])) {
					$json['error']['warning'] = $this->language->get('error_exists');
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
					$json['error']['city'] = $this->language->get('error_city');				}
		

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

				if ((strlen(utf8_decode($this->request->post['password'])) < 4) || (strlen(utf8_decode($this->request->post['password'])) > 20)) {

					$json['error']['password'] = $this->language->get('error_password');

				}		

				if ($this->request->post['confirm'] != $this->request->post['password']) {

					$json['error']['confirm'] = $this->language->get('error_confirm');

				}				

				if ($this->config->get('config_account_id')) {


					$information_info = $this->model_onecheckout_checkout->getInformation($this->config->get('config_account_id'));					

					if ($information_info && !isset($this->request->post['agree'])) {
						$json['error']['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);

					}

				}

			}

			

			if (!$json) {

				if($version_int >= 1530){
					$this->load->model('account/customer');
					$this->model_account_customer->addCustomer($this->request->post);
					$customer_app = $customer_group && !$customer_group['approval'];
				}else{
					$this->model_onecheckout_checkout->addCustomer($this->request->post);
					$customer_app = !$this->config->get('config_customer_approval');					
				}			

				if (!$this->config->get('config_customer_approval')) {
					$this->customer->login($this->request->post['email'], $this->request->post['password']);
					
					$this->session->data['payment_address_id'] = $this->customer->getAddressId();
					
					if ($this->config->get('config_tax_customer') == 'payment') {
						$this->session->data['payment_country_id'] = $this->request->post['country_id'];
						$this->session->data['payment_zone_id'] = $this->request->post['zone_id'];
					}
				
					if (isset($this->request->post['shipping_address']) && $this->request->post['shipping_address']) {
						$this->session->data['shipping_address_id'] = $this->customer->getAddressId();
						if ($this->config->get('config_tax_customer') == 'shipping') {
							$this->session->data['shipping_country_id'] = $this->request->post['country_id'];
							$this->session->data['shipping_zone_id'] = $this->request->post['zone_id'];
							$this->session->data['shipping_postcode'] = $this->request->post['postcode'];	
						}
					}
				} else {
					$json['redirect'] = $this->url->link('account/success');
				}		
				$version_int = $this->model_onecheckout_checkout->versiontoint();
				//version
				if($version_int < 1513 && $version_int >= 1500){
					$this->tax->setZone($this->request->post['country_id'], $this->request->post['zone_id']);	
				}			

				unset($this->session->data['guest']);
				unset($this->session->data['shipping_methods']);
				unset($this->session->data['shipping_method']);
				unset($this->session->data['payment_methods']);
				unset($this->session->data['payment_method']);						

			}

		} else {


			$data['entry_newsletter'] = sprintf($this->language->get('entry_newsletter'), $this->config->get('config_name'));
			$data['entry_password'] = $this->language->get('entry_password');
			$data['entry_confirm'] = $this->language->get('entry_confirm');
			$data['entry_shipping'] = $this->language->get('entry_shipping');					
			
			if ($this->config->get('config_account_id')) {
				$information_info = $this->model_onecheckout_checkout->getInformation($this->config->get('config_account_id'));

				if ($information_info) {

					$data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/info', 'information_id=' . $this->config->get('config_account_id'), 'SSL'), $information_info['title'], $information_info['title']);
				} else {
					$data['text_agree'] = '';
				}

			} else {

				$data['text_agree'] = '';

			}
			

			$data['shipping_required'] = $this->cart->hasShipping();			

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/onecheckout/register.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/onecheckout/register.tpl';
			} else {
				$this->template = 'default/template/onecheckout/register.tpl';
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