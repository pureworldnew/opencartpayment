<?php
class ModelAbandonedCartsScript extends Model {
	public function getAbandonedCartsPeoples($data = array()) {
		$sql = "SELECT *, CONCAT(firstname, ' ', lastname) AS name FROM " . DB_PREFIX . "abandonedcart";	
	
		$sql .= " WHERE email !=''";
		
		if (isset($data['filter_email_notify']) && !is_null($data['filter_email_notify'])) {
			$sql .= " AND email_notify = '" . $this->db->escape($data['filter_email_notify']) . "'";
		}
		
		$sql .= " GROUP BY email, store_id";
		
		$sql .= " ORDER BY date_added DESC";

		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getStore($store_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "store WHERE store_id = '" . (int)$store_id . "'");

		return $query->row;
	}
	
	public function getAbandonedCartsProducts($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "abandonedcart";

		$sql .= " WHERE email !='' AND LCASE(email) = '" . $this->db->escape(utf8_strtolower($data['filter_email'])) . "'";
		
		if (isset($data['filter_email_notify']) && !is_null($data['filter_email_notify'])) {
			$sql .= " AND email_notify = '" . $this->db->escape($data['filter_email_notify']) . "'";
		}
		
		if (isset($data['filter_store_id']) && !is_null($data['filter_store_id'])) {
			$sql .= " AND store_id = '" . $this->db->escape($data['filter_store_id']) . "'";
		}
		
		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		if (!empty($data['filter_account'])) {
			$sql .= " AND customer_id != '0'";
		}elseif (isset($data['filter_account']) && !is_null($data['filter_account'])) {
			$sql .= " AND customer_id = '0'";
		}
		
		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getAbandonedTemplateData($abandoned_template_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "abandoned_template at LEFT JOIN " . DB_PREFIX . "abandoned_template_description atd on(at.abandoned_template_id=atd.abandoned_template_id) WHERE at.abandoned_template_id = '" . (int)$abandoned_template_id . "' AND atd.language_id = '". (int)$this->config->get('config_language_id') ."'");

		return $query->row;
	}
	
	public function UpdateCartNotify($email, $store_id) {
		$this->db->query("UPDATE ". DB_PREFIX ."abandonedcart SET email_notify = '1' WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "' and store_id = '". (int)$store_id ."'");
	}
	
	public function getCartProductOptions($product_id, $product_option_id, $value) {
		$option_data = array();
		
		$option_query = $this->db->query("SELECT po.product_option_id, po.option_id, od.name, o.type FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_option_id = '" . (int)$product_option_id . "' AND po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		if ($option_query->num_rows) {
			if ($option_query->row['type'] == 'select' || $option_query->row['type'] == 'radio' || $option_query->row['type'] == 'image') {
				$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$value . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

				if ($option_value_query->num_rows) {
					$option_data[] = array(
						'product_option_id'       => $product_option_id,
						'product_option_value_id' => $value,
						'option_id'               => $option_query->row['option_id'],
						'option_value_id'         => $option_value_query->row['option_value_id'],
						'name'                    => $option_query->row['name'],
						'value'                   => $option_value_query->row['name'],
						'type'                    => $option_query->row['type'],
					);
				}
			} elseif ($option_query->row['type'] == 'checkbox' && is_array($value)) {
				foreach ($value as $product_option_value_id) {
					$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

					if ($option_value_query->num_rows) {
						$option_data[] = array(
							'product_option_id'       => $product_option_id,
							'product_option_value_id' => $product_option_value_id,
							'option_id'               => $option_query->row['option_id'],
							'option_value_id'         => $option_value_query->row['option_value_id'],
							'name'                    => $option_query->row['name'],
							'value'                   => $option_value_query->row['name'],
							'type'                    => $option_query->row['type'],
						);
					}
				}
			} elseif ($option_query->row['type'] == 'text' || $option_query->row['type'] == 'textarea' || $option_query->row['type'] == 'file' || $option_query->row['type'] == 'date' || $option_query->row['type'] == 'datetime' || $option_query->row['type'] == 'time') {
				$option_data[] = array(
					'product_option_id'       => $product_option_id,
					'product_option_value_id' => '',
					'option_id'               => $option_query->row['option_id'],
					'option_value_id'         => '',
					'name'                    => $option_query->row['name'],
					'value'                   => $value,
					'type'                    => $option_query->row['type'],
				);
			}
		}
		
		return $option_data;
	}
}