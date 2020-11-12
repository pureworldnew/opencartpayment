<?php
class ModelPaymentPaystand extends Model {
	public function getMethod($address, $total) {
		$this->load->language('payment/paystand');

		$status = false;

		if( $this->config->get('paystand_status') == 1 && !empty( $this->config->get('paystand_publishable_key') ) ) 
		{
			$status = true;
		}
		

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code'       => 'paystand',
				'title'      => $this->language->get('text_title'),
				'terms'      => '',
				'sort_order' => $this->config->get('paystand_sort_order')
			);
		}

		return $method_data;
	}
}