<?php
class ModelTotalcustomtotal extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		$customtotal_fees = $this->config->get('customtotal_names');
		$customtotal_allowfront = $this->config->get('customtotal_allowfront');
		if(is_array($customtotal_fees) && !empty($customtotal_fees) && $this->cart->countProducts()) {
			if (isset($this->session->data['api_id']) && !empty($this->session->data['api_id'])) {
				foreach ($customtotal_fees as $key => $value) {
					if(isset($this->session->data['customttotalvalues']) && !empty($this->session->data['customttotalvalues'])) {
						if(isset($this->session->data['customttotalvalues'][$key])) {
							$customtotal = $this->session->data['customttotalvalues'][$key]['amount'];
							$text = $this->session->data['customttotalvalues'][$key]['name'];
							$total_data[] = array(
								'code'       => 'customtotal',
								'title'      =>  $text,
								'value'      => $customtotal,
								'sort_order' => $this->config->get('customtotal_sort_order')
							);
							
							if ($this->session->data['customttotalvalues'][$key]['tax_class_id']) {
								$tax_rates = $this->tax->getRates($customtotal, $this->session->data['customttotalvalues'][$key]['tax_class_id']);

								foreach ($tax_rates as $tax_rate) {
									if (!isset($taxes[$tax_rate['tax_rate_id']])) {
										$taxes[$tax_rate['tax_rate_id']] = $tax_rate['amount'];
									} else {
										$taxes[$tax_rate['tax_rate_id']] += $tax_rate['amount'];
									}
								}
							}

							$total +=  $customtotal;
						}	
					}
			    }
			    if(isset($this->session->data['custom_payment_method']) && !empty($this->session->data['custom_payment_method'])) {
					$customtotal = $this->session->data['custom_payment_method']['amount'];
					$text = $this->session->data['custom_payment_method']['name'];
					$total_data[] = array(
						'code'       => 'customtotal',
						'title'      =>  $text,
						'value'      => $customtotal,
						'sort_order' => $this->config->get('customtotal_sort_order')
					);
					$total +=  $customtotal;
				}	
			} else if(!isset($this->session->data['api_id']) && $customtotal_allowfront) {
				foreach ($customtotal_fees as $key => $value) {
					$customtotal = $value['amount'];
					$lid = $this->config->get('config_language_id');
					if(isset($value[$lid]) && $customtotal && $value[$lid]['name'] != "") {
						$text = $value[$lid]['name'];
						$total_data[] = array(
							'code'       => 'customtotal',
							'title'      =>  $text,
							'value'      => $customtotal,
							'sort_order' => $this->config->get('customtotal_sort_order')
						);

						$total +=  $customtotal;
					} 
			    }
			}
			
		}
	}
}
