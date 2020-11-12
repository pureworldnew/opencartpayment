<?php
class ModelPaymentNMCGateway extends Model {
	public function getMethod($address, $total) { 
		$this->load->language('payment/nmc_gateway');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('nmc_gateway_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if ($this->config->get('nmc_gateway_total') > 0 && $this->config->get('nmc_gateway_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('nmc_gateway_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code'       => 'nmc_gateway',
				'title'      => (int)$address['country_id'] == 223 ? $this->config->get('nmc_gateway_title_usa') : $this->config->get('nmc_gateway_title_nonusa'),
				'terms'      => '',
				'sort_order' => $this->config->get('nmc_gateway_sort_order')
			);
		}

		return $method_data;
	}
}