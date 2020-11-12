<?php 
class ControllerOneCheckoutForm extends Controller {

  	public function index() {

		$this->language->load('onecheckout/checkout');
		$this->load->model('onecheckout/checkout');		
		$version_int = $this->model_onecheckout_checkout->versiontoint();
		$data['version_int'] = $version_int;
		
		$json = array();

		if ($this->customer->isLogged()) {
			$json['redirect'] = $this->url->link('onecheckout/checkout', '', 'SSL');
		}

		if ((!$this->cart->hasProducts() && (!isset($this->session->data['vouchers']) || !$this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');	

		}

			$data['text_select'] = $this->language->get('text_select');
			$data['text_none'] = $this->language->get('text_none');
			$data['text_your_details'] = $this->language->get('text_your_details');
			$data['text_your_address'] = $this->language->get('text_your_address');
			$data['text_your_password'] = $this->language->get('text_your_password');
					
			$data['entry_firstname'] = $this->language->get('entry_firstname');
			$data['entry_lastname'] = $this->language->get('entry_lastname');
			$data['entry_email'] = $this->language->get('entry_email');
			$data['entry_telephone'] = $this->language->get('entry_telephone');
			$data['entry_fax'] = $this->language->get('entry_fax');
			$data['entry_company'] = $this->language->get('entry_company');
			$data['entry_account'] = $this->language->get('entry_account');
			$data['entry_company_id'] = $this->language->get('entry_company_id');
			$data['entry_tax_id'] = $this->language->get('entry_tax_id');
			$data['entry_address_1'] = $this->language->get('entry_address_1');
			$data['entry_address_2'] = $this->language->get('entry_address_2');
			$data['entry_postcode'] = $this->language->get('entry_postcode');
			$data['entry_city'] = $this->language->get('entry_city');
			$data['entry_country'] = $this->language->get('entry_country');
			$data['entry_zone'] = $this->language->get('entry_zone');
			$data['entry_newsletter'] = sprintf($this->language->get('entry_newsletter'), $this->config->get('config_name'));
			$data['entry_password'] = $this->language->get('entry_password');
			$data['entry_confirm'] = $this->language->get('entry_confirm');
			$data['entry_shipping'] = $this->language->get('entry_shipping');			
			$data['error_firstname'] = $this->language->get('error_firstname');			
			$data['text_reg'] = $this->language->get('text_reg');

			if (isset($this->session->data['guest']['firstname'])) {
				$data['firstname'] = $this->session->data['guest']['firstname'];
			} else {
				$data['firstname'] = '';
			}	

			if (isset($this->session->data['guest']['lastname'])) {
				$data['lastname'] = $this->session->data['guest']['lastname'];
			} else {
				$data['lastname'] = '';
			}			

			if (isset($this->session->data['guest']['email'])) {
				$data['email'] = $this->session->data['guest']['email'];
			} else {
				$data['email'] = '';
			}			

			if (isset($this->session->data['guest']['telephone'])) {
				$data['telephone'] = $this->session->data['guest']['telephone'];	
			} else {
				$data['telephone'] = '';
			}

			if (isset($this->session->data['guest']['fax'])) {
				$data['fax'] = $this->session->data['guest']['fax'];	
			} else {
				$data['fax'] = '';
			}	

			if (isset($this->session->data['guest']['payment']['company'])) {
				$data['company'] = $this->session->data['guest']['payment']['company'];	
			} else {
				$data['company'] = '';
			}			
			
			//version			
			if($version_int >= 1530){
				$this->load->model('account/customer_group');
				$data['customer_groups'] = array();
		
				if (is_array($this->config->get('config_customer_group_display'))) {
					$customer_groups = $this->model_account_customer_group->getCustomerGroups();
			
					foreach ($customer_groups as $customer_group) {
						if (in_array($customer_group['customer_group_id'], $this->config->get('config_customer_group_display'))) {
							$data['customer_groups'][] = $customer_group;
						}
					}
				}
		
				if (isset($this->session->data['guest']['customer_group_id'])) {
    				$data['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
				} else {
					$data['customer_group_id'] = $this->config->get('config_customer_group_id');
				}
		
				// Company ID
				if (isset($this->session->data['guest']['payment']['company_id'])) {
					$data['company_id'] = $this->session->data['guest']['payment']['company_id'];			
				} else {
					$data['company_id'] = '';
				}
		
				// Tax ID
				if (isset($this->session->data['guest']['payment']['tax_id'])) {
					$data['tax_id'] = $this->session->data['guest']['payment']['tax_id'];			
				} else {
					$data['tax_id'] = '';
				}
			}

			if (isset($this->session->data['guest']['payment']['address_1'])) {
				$data['address_1'] = $this->session->data['guest']['payment']['address_1'];
			} else {
				$data['address_1'] = '';
			}

			if (isset($this->session->data['guest']['payment']['address_2'])) {
				$data['address_2'] = $this->session->data['guest']['payment']['address_2'];	
			} else {
				$data['address_2'] = '';
			}	

			if (isset($this->session->data['guest']['payment']['postcode'])) {
				$data['postcode'] = $this->session->data['guest']['payment']['postcode'];	
			} elseif (isset($this->session->data['shipping_postcode'])) {
				$data['postcode'] = $this->session->data['shipping_postcode'];			
			} else {
				$data['postcode'] = '';
			}			

			if (isset($this->session->data['guest']['payment']['city'])) {
				$data['city'] = $this->session->data['guest']['payment']['city'];	
			} else {
				$data['city'] = '';
			}	

			if (isset($this->session->data['guest']['payment']['country_id'])) {
				$data['country_id'] = $this->session->data['guest']['payment']['country_id'];	
			} elseif (isset($this->session->data['shipping_country_id'])) {
				$data['country_id'] = $this->session->data['shipping_country_id'];		
			} else {
				$data['country_id'] = $this->config->get('config_country_id');
			}

	

			if (isset($this->session->data['guest']['payment']['zone_id'])) {
				$data['zone_id'] = $this->session->data['guest']['payment']['zone_id'];	
			} elseif (isset($this->session->data['shipping_zone_id'])) {
				$data['zone_id'] = $this->session->data['shipping_zone_id'];						
			} else {
				$data['zone_id'] = $this->config->get('config_zone_id');
			}

			$data['countries'] = $this->model_onecheckout_checkout->getCountries();


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
			
			$data['guest_checkout'] = ($this->config->get('config_guest_checkout') && !$this->config->get('config_customer_price') && !$this->cart->hasDownload());
			

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/onecheckout/form.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/onecheckout/form.tpl';
			} else {
				$this->template = 'default/template/onecheckout/form.tpl';
			}		

			$json['output'] = $this->render();		

		$this->response->setOutput($this->model_onecheckout_checkout->jsonencode($json));			

  	}
	
	public function validate(){
		
		$json['error']=array();
		
		$this->language->load('onecheckout/checkout');
		$this->load->model('onecheckout/checkout');	
		$version_int = $this->model_onecheckout_checkout->versiontoint();
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {

			if(isset($this->request->post['firstname'])) {
				if ((strlen(utf8_decode($this->request->post['firstname'])) < 1) || (strlen(utf8_decode($this->request->post['firstname'])) > 32)) {
					$json['error']['firstname'] = $this->language->get('error_firstname');
				} else {
					$this->session->data['shipping']['firstname'] = $this->request->post['firstname'];
				}
			}
		

			if(isset($this->request->post['lastname'])) {
				if ((strlen(utf8_decode($this->request->post['lastname'])) < 1) || (strlen(utf8_decode($this->request->post['lastname'])) > 32)) {
					$json['error']['lastname'] = $this->language->get('error_lastname');
				} else {
					$this->session->data['shipping']['lastname'] = $this->request->post['lastname'];
				}
			}

		
			if(isset($this->request->post['email'])) {
				
				if ((strlen(utf8_decode($this->request->post['email'])) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
					$json['error']['email'] = $this->language->get('error_email');
				} elseif ($this->model_onecheckout_checkout->getTotalCustomersByEmail($this->request->post['email'])) {
					$json['error']['email'] = $this->language->get('error_exists');
				}
			}

			if(isset($this->request->post['telephone'])) {
				if ((strlen(utf8_decode($this->request->post['telephone'])) < 3) || (strlen(utf8_decode($this->request->post['telephone'])) > 32)) {
					$json['error']['telephone'] = $this->language->get('error_telephone');
				}
			}
			
			//version
			if($version_int >= 1530){
				// Customer Group
				$this->load->model('account/customer_group');
			
				if (isset($this->request->post['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->post['customer_group_id'], $this->config->get('config_customer_group_display'))) {
					$customer_group_id = $this->request->post['customer_group_id'];
				} elseif ($this->customer->isLogged()) {
					$customer_group_id = $this->customer->getCustomerGroupId();
				} else {
					$customer_group_id = $this->config->get('config_customer_group_id');
				}
			
				$customer_group = $this->model_account_customer_group->getCustomerGroup($customer_group_id);
				
				if ($customer_group) {	
					// Company ID
					if ($customer_group['company_id_display'] && $customer_group['company_id_required'] && isset($this->request->post['company_id']) && empty($this->request->post['company_id'])) {
						$json['error']['company_id'] = $this->language->get('error_company_id');
					}
				
					// Tax ID
					if ($customer_group['tax_id_display'] && $customer_group['tax_id_required'] && isset($this->request->post['tax_id']) && empty($this->request->post['tax_id'])) {
						$json['error']['tax_id'] = $this->language->get('error_tax_id');
					}						
				}
			}
			
			if(isset($this->request->post['company'])) {
				$this->session->data['shipping']['company'] = $this->request->post['company'];
			}
	
			if(isset($this->request->post['address_1'])) {
				if ((strlen(utf8_decode($this->request->post['address_1'])) < 3) || (strlen(utf8_decode($this->request->post['address_1'])) > 128)) {
					$json['error']['address_1'] = $this->language->get('error_address_1');
				} else {
					$this->session->data['shipping']['address_1'] = $this->request->post['address_1'];
				}
			}

			if(isset($this->request->post['city'])) {
				if ((strlen(utf8_decode($this->request->post['city'])) < 2) || (strlen(utf8_decode($this->request->post['city'])) > 128)) {
					$json['error']['city'] = $this->language->get('error_city');
				}
			}

			if(isset($this->request->post['country_id'])) {				
				$country_info = $this->model_onecheckout_checkout->getCountry($this->request->post['country_id']);				

				if ($country_info){
					if(isset($this->request->post['postcode'])){
						if($country_info['postcode_required'] && (strlen(utf8_decode($this->request->post['postcode'])) < 2) || (strlen(utf8_decode($this->request->post['postcode'])) > 10)) {
							$json['error']['postcode'] = $this->language->get('error_postcode');
						}
					}
					
					if($version_int >= 1530 && isset($this->request->post['tax_id'])){
						// VAT Validation
						$this->load->helper('vat');
				
						if ($this->config->get('config_vat') && $this->request->post['tax_id'] && (vat_validation($country_info['iso_code_2'], $this->request->post['tax_id']) == 'invalid')) {
							$json['error']['tax_id'] = $this->language->get('error_vat');
						}
					}
				}				
			}

			if(isset($this->request->post['password'])) {
				if ((strlen(utf8_decode($this->request->post['password'])) < 4) || (strlen(utf8_decode($this->request->post['password'])) > 20)) {
					$json['error']['password'] = $this->language->get('error_password');
				}
			}		

			if(isset($this->request->post['password'])&&isset($this->request->post['confirm'])) {
				if ($this->request->post['confirm'] != $this->request->post['password']) {
					$json['error']['confirm'] = $this->language->get('error_confirm');
				}
			}
		
		}

		$this->response->setOutput($this->model_onecheckout_checkout->jsonencode($json));
	}
}
?>