<?php
class ControllerPosShipping extends Controller {
	public function index() {
		$json = array();

		$this->load->model('extension/extension');
		$this->load->model('localisation/country');
		$this->load->model('localisation/zone');
		
		$country_info = $this->model_localisation_country->getCountry($this->request->post['shipping_country_id']);

		if ($country_info) {
			$country = $country_info['name'];
			$iso_code_2 = $country_info['iso_code_2'];
			$iso_code_3 = $country_info['iso_code_3'];
			$address_format = $country_info['address_format'];
		} else {
			$country = '';
			$iso_code_2 = '';
			$iso_code_3 = '';	
			$address_format = '';
		}
	
		$zone_info = $this->model_localisation_zone->getZone($this->request->post['shipping_zone_id']);
		
		if ($zone_info) {
			$zone = $zone_info['name'];
			$zone_code = $zone_info['code'];
		} else {
			$zone = '';
			$zone_code = '';
		}					

		$address_data = array(
			'firstname'      => $this->request->post['shipping_firstname'],
			'lastname'       => $this->request->post['shipping_lastname'],
			'company'        => $this->request->post['shipping_company'],
			'address_1'      => $this->request->post['shipping_address_1'],
			'address_2'      => $this->request->post['shipping_address_2'],
			'postcode'       => $this->request->post['shipping_postcode'],
			'city'           => $this->request->post['shipping_city'],
			'zone_id'        => $this->request->post['shipping_zone_id'],
			'zone'           => $zone,
			'zone_code'      => $zone_code,
			'country_id'     => $this->request->post['shipping_country_id'],
			'country'        => $country,	
			'iso_code_2'     => $iso_code_2,
			'iso_code_3'     => $iso_code_3,
			'address_format' => $address_format
		);

		$results = $this->model_extension_extension->getExtensions('shipping');
		
		foreach ($results as $result) {
			if ($this->config->get($result['code'] . '_status')) {
				$this->load->model('shipping/' . $result['code']);
				
				$quote = $this->{'model_shipping_' . $result['code']}->getQuote($address_data); 
	
				if ($quote) {
					$json['shipping_method'][$result['code']] = array( 
						'title'      => $quote['title'],
						'quote'      => $quote['quote'], 
						'sort_order' => $quote['sort_order'],
						'error'      => $quote['error']
					);
					// set the session payment method if required
					if (isset($this->request->post['shipping_code'])) {
						foreach ($quote['quote'] as $quote_method) {
							if ($this->request->post['shipping_code'] == $quote_method['code']) {
								$this->session->data['shipping_method'] = $quote_method;
							}
						}
					}
				}
			}
		}

		$sort_order = array();
	  
		foreach ($json['shipping_method'] as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $json['shipping_method']);

	
		$this->response->setOutput(json_encode($json));	
	}
}
?>