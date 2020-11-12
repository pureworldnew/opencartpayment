<?php
class ModelPosSnManager extends Model {
	public function getSns($data = array()) {
		// Get the packagings for the given filter
		$packaging_sql = "SELECT packaging_id FROM `" . DB_PREFIX . "product_packaging` WHERE 1";
		
		if (!empty($data['filter_packaging_id'])) {
			$packaging_sql .= " AND packaging_id = '" . (int)$data['filter_packaging_id'] . "'";
		}
		
		if (!empty($data['filter_packaging_slip'])) {
			$packaging_sql .= " AND packaging_slip LIKE '%" . $this->db->escape($data['filter_packaging_slip']) . "%'";
		}
		
		if (!empty($data['filter_order_number'])) {
			$packaging_sql .= " AND order_number LIKE '%" . $this->db->escape($data['filter_order_number']) . "%'";
		}
		
		if (!empty($data['filter_packaging_note'])) {
			$packaging_sql .= " AND note LIKE '%" . $this->db->escape($data['filter_packaging_note']) . "%'";
		}
		
		if (!empty($data['filter_packaging_date'])) {
			$packaging_sql .= " AND date = '" . $data['filter_packaging_date'] . "'";
		}
		
		$packaging_sql .= " ORDER BY date DESC";
		
		$sn_sql = "SELECT pd.name AS product_name, psn.*, pk.*, DATE(pk.date) AS packaging_date FROM " . DB_PREFIX . "product_sn_packaging psk LEFT JOIN " . DB_PREFIX . "product_sn psn ON (psk.product_sn_id = psn.product_sn_id) LEFT JOIN " . DB_PREFIX . "product_packaging pk ON psk.packaging_id = pk.packaging_id LEFT JOIN " . DB_PREFIX . "product_description pd ON psn.product_id = pd.product_id WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND psk.packaging_id IN (" . $packaging_sql . ")";

		if (!empty($data['filter_product_sn_id'])) {
			$sn_sql .= " AND psn.product_sn_id = '" . (int)$data['filter_product_sn_id'] . "'";
		}

		if (!empty($data['filter_product_sn'])) {
			$sn_sql .= " AND psn.sn LIKE '%" . $this->db->escape($data['filter_product_sn']) . "%'";
		}

		if (!empty($data['filter_product_sn_cost'])) {
			$sn_sql .= " AND psn.cost = '" . $data['filter_product_sn_cost'] . "'";
		}

		if (!empty($data['filter_product_id'])) {
			$sn_sql .= " AND psn.product_id = '" . (int)$data['filter_product_id'] . "'";
		}

		if (!empty($data['filter_product_name'])) {
			$sn_sql .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_product_name']) . "%'";
		}

		if (!empty($data['filter_product_sn_status'])) {
			$sn_sql .= " AND psn.status = '" . (int)$data['filter_product_sn_status'] . "'";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sn_sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		$query = $this->db->query($sn_sql);

		return $query->rows;
	}

	public function getTotalSns($data = array()) {
		$packaging_sql = "SELECT packaging_id FROM `" . DB_PREFIX . "product_packaging` WHERE 1";
		
		if (!empty($data['filter_packaging_id'])) {
			$packaging_sql .= " AND packaging_id = '" . (int)$data['filter_packaging_id'] . "'";
		}
		
		if (!empty($data['filter_packaging_slip'])) {
			$packaging_sql .= " AND packaging_slip LIKE '%" . $this->db->escape($data['filter_packaging_slip']) . "%'";
		}
		
		if (!empty($data['filter_order_number'])) {
			$packaging_sql .= " AND order_number LIKE '%" . $this->db->escape($data['filter_packaging_slip']) . "%'";
		}
		
		if (!empty($data['filter_packaging_note'])) {
			$packaging_sql .= " AND note LIKE '%" . $this->db->escape($data['filter_packaging_note']) . "%'";
		}
		
		if (!empty($data['filter_packaging_date'])) {
			$packaging_sql .= " AND date = '" . $data['filter_packaging_date'] . "'";
		}
		
		$sn_sql = "SELECT COUNT(DISTINCT psn.product_sn_id) AS total FROM " . DB_PREFIX . "product_sn_packaging psk LEFT JOIN " . DB_PREFIX . "product_sn psn ON (psk.product_sn_id = psn.product_sn_id) LEFT JOIN " . DB_PREFIX . "product_packaging pk ON psk.packaging_id = pk.packaging_id LEFT JOIN " . DB_PREFIX . "product_description pd ON psn.product_id = pd.product_id WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND psk.packaging_id IN (" . $packaging_sql . ")";

		if (!empty($data['filter_product_sn_id'])) {
			$sn_sql .= " AND psn.product_sn_id = '" . (int)$data['filter_product_sn_id'] . "'";
		}

		if (!empty($data['filter_product_sn'])) {
			$sn_sql .= " AND psn.sn LIKE '%" . $this->db->escape($data['filter_product_sn']) . "%'";
		}

		if (!empty($data['filter_product_sn_cost'])) {
			$sn_sql .= " AND psn.cost = '" . $data['filter_product_sn_cost'] . "'";
		}

		if (!empty($data['filter_product_id'])) {
			$sn_sql .= " AND psn.product_id = '" . (int)$data['filter_product_id'] . "'";
		}

		if (!empty($data['filter_product_name'])) {
			$sn_sql .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_product_name']) . "%'";
		}

		if (!empty($data['filter_product_sn_status'])) {
			$sn_sql .= " AND psn.status = '" . (int)$data['filter_product_sn_status'] . "'";
		}

		$query = $this->db->query($sn_sql);

		return $query->row['total'];
	}
	
	public function getPackagings($data = array()) {
		$packaging_sql = "SELECT * FROM " . DB_PREFIX . "product_packaging WHERE 1";
		
		if (!empty($data['filter_packaging_slip'])) {
			$packaging_sql .= " AND packaging_slip LIKE '%" . $this->db->escape($data['filter_packaging_slip']) . "%'";
		}
		if (!empty($data['filter_order_number'])) {
			$packaging_sql .= " AND order_number LIKE '%" . $this->db->escape($data['filter_order_number']) . "%'";
		}
		if (!empty($data['filter_packaging_note'])) {
			$packaging_sql .= " AND note LIKE '%" . $this->db->escape($data['filter_packaging_note']) . "%'";
		}
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$packaging_sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		$query = $this->db->query($packaging_sql);

		return $query->rows;
	}
	
	public function getProductSns($data = array()) {
		$sn_sql = "SELECT pd.name AS product_name, psn.product_sn_id, psn.sn AS product_sn FROM " . DB_PREFIX . "product_sn psn LEFT JOIN " . DB_PREFIX . "product_description pd ON psn.product_id = pd.product_id WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_product_sn'])) {
			$sn_sql .= " AND psn.sn LIKE '%" . $this->db->escape($data['filter_product_sn']) . "%'";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sn_sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		$query = $this->db->query($sn_sql);

		return $query->rows;
	}
	
	public function change_packaging_slip($data) {
		if (!empty($data['packaging_id']) && !empty($data['packaging_slip'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product_packaging SET packaging_slip = '" . $this->db->escape($data['packaging_slip']) . "' WHERE packaging_id = '" . (int)$data['packaging_id'] . "'");
		}
	}
	
	public function change_order_number($data) {
		if (!empty($data['packaging_id']) && !empty($data['order_number'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product_packaging SET order_number = '" . $this->db->escape($data['order_number']) . "' WHERE packaging_id = '" . (int)$data['packaging_id'] . "'");
		}
	}
	
	public function change_packaging_date($data) {
		if (!empty($data['packaging_id']) && !empty($data['date'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product_packaging SET date = '" . $this->db->escape($data['date']) . "' WHERE packaging_id = '" . (int)$data['packaging_id'] . "'");
		}
	}
	
	public function change_packaging_note($data) {
		if (!empty($data['packaging_id']) && !empty($data['note'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product_packaging SET note = '" . $this->db->escape($data['note']) . "' WHERE packaging_id = '" . (int)$data['packaging_id'] . "'");
		}
	}
	
	public function change_product_sn($data) {
		if (!empty($data['product_sn_id']) && !empty($data['product_sn'])) {
			$query = $this->db->query("SELECT product_sn_id FROM " . DB_PREFIX . "product_sn WHERE product_sn_id = '" . (int)$data['product_sn_id'] . "' AND status = '2'");
			if ($query->num_rows == 0) {
				// serial number not sold yet
				$this->db->query("UPDATE " . DB_PREFIX . "product_sn SET sn = '" . $this->db->escape($data['product_sn']) . "' WHERE product_sn_id = '" . (int)$data['product_sn_id'] . "'");
			} else {
				return false;
			}
		}
		return true;
	}
	
	public function change_product_sn_cost($data) {
		if (!empty($data['product_sn_id']) && !empty($data['cost'])) {
			$query = $this->db->query("SELECT product_sn_id FROM " . DB_PREFIX . "product_sn WHERE product_sn_id = '" . (int)$data['product_sn_id'] . "' AND status = '2'");
			if ($query->num_rows == 0) {
				// serial number not sold yet
				$this->db->query("UPDATE " . DB_PREFIX . "product_sn SET cost = '" . (float)$data['cost'] . "' WHERE product_sn_id = '" . (int)$data['product_sn_id'] . "'");
			} else {
				return false;
			}
		}
		return true;
	}
	
	public function save_packaging($data) {
		if (!empty($data['packaging']) && !empty($data['packaging']['packaging_slip'])) {
			// add a new record regardless the packaging_slip exists or not as the function is meant to create a new packaging
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_packaging SET packaging_slip = '" . $this->db->escape($data['packaging']['packaging_slip']) . "', order_number = '" . $this->db->escape($data['packaging']['order_number']) . "', date = '" . $data['packaging']['date'] . "', note = '" . $this->db->escape($data['packaging']['note']) . "'");
			$packaging_id = $this->db->getLastId();
			
			if (!empty($data['product_sn'])) {
				$product_sn_ids = array();
				foreach ($data['product_sn'] as $product_id => $product_sns_info) {
					// insert the records for the given product
					$new_sn = 0;
					foreach ($product_sns_info as $product_sn_info) {
						if (!empty($product_sn_info['product_sn'])) {
							$this->db->query("INSERT INTO `" . DB_PREFIX . "product_sn` SET product_id = '" . (int)$product_id . "', sn = '" . $this->db->escape($product_sn_info['product_sn']) . "', cost = '" . $product_sn_info['cost'] . "', date_modified = NOW()");
							$product_sn_ids[] = $this->db->getLastId();
							$new_sn ++;
						}
					}
					// update product stock
					if ($new_sn > 0) {
						$this->db->query("UPDATE `" . DB_PREFIX . "product` SET quantity = (quantity + " . $new_sn . ") WHERE product_id = '" . (int)$product_id . "'");
					}
				}
				// insert the new packaging and product_sn to the product_sn_packaging table
				foreach ($product_sn_ids as $product_sn_id) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_sn_packaging SET packaging_id = '" . $packaging_id . "', product_sn_id = '" . $product_sn_id . "'");
				}
			}
		}
	}
	
	public function delete_product_sn($data) {
		if (!empty($data['packaging_selected'])) {
			$packaging_ids = array();
			foreach ($data['packaging_selected'] as $product_sn_id) {
				// get the packaging id for the product_sn_id, to be used for check if all product sn in the packaging has been deleted
				$query = $this->db->query("SELECT packaging_id FROM " . DB_PREFIX . "product_sn_packaging WHERE product_sn_id = '" . $product_sn_id . "'");
				if (!empty($query->row) && !in_array($query->row['packaging_id'], $packaging_ids)) {
					$packaging_ids[] = $query->row['packaging_id'];
				}
				// delete it from product_sn and product_sn_packaging table
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_sn_packaging WHERE product_sn_id = '" . $product_sn_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_sn WHERE product_sn_id = '" . $product_sn_id . "'");
			}
			
			foreach ($packaging_ids as $packaging_id) {
				$query = $this->db->query("SELECT product_sn_id FROM ". DB_PREFIX . "product_sn_packaging WHERE packaging_id = '" . $packaging_id . "'");
				if ($query->num_rows == 0) {
					// no product sn in the packaging any more, remove it from product_packaging table
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_packaging WHERE packaging_id = '" . $packaging_id . "'");
				}
			}
		}
	}
}