<?php
class ModelPosExtendedProduct extends Model {
	public function editProduct($product_id, $data) {
		$this->event->trigger('pre.admin.edit.product', $data);
		
		$this->db->query("UPDATE " . DB_PREFIX . "product SET abbreviation = '" . $this->db->escape($data['abbreviation']) . "', weight_price = '" . (int)$data['weight_price'] . "', weight_name = '" . $this->db->escape($data['weight_name']) . "', date_modified = NOW() WHERE product_id = '" . (int)$product_id . "'");
		if (!empty($data['commission']) && (int)$data['commission']) {
			if ((int)$data['commission_type'] == 1) {
				$this->db->query("REPLACE INTO `" . DB_PREFIX . "product_commission` SET product_id = '" . (int)$product_id . "', type = '1', value = '" . (float)$data['commission_fixed'] . "', base = '0', date_modified = NOW()");
			} else {
				$this->db->query("REPLACE INTO `" . DB_PREFIX . "product_commission` SET product_id = '" . (int)$product_id . "', type = '2', value = '" . (float)$data['commission_percentage'] . "', base = '" . (float)$data['commission_base'] . "', date_modified = NOW()");
			}
		} else {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "product_commission` WHERE product_id = '" . (int)$product_id . "'");
		}

		$this->cache->delete('product');

		$this->event->trigger('post.admin.edit.product', $product_id);
	}

	public function getProduct($product_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "') AS keyword FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}
	
	public function getProductSNInfo($product_id) {
		$sn_instore_query = $this->db->query("SELECT COUNT(product_sn_id) AS total FROM `" . DB_PREFIX . "product_sn` WHERE product_id = '" . (int)$product_id . "' AND status = '1'");
		$sn_instore = 0;
		if ($sn_instore_query->row) {
			$sn_instore = $sn_instore_query->row['total'];
		}
		$sn_sold_query = $this->db->query("SELECT COUNT(product_sn_id) AS total FROM `" . DB_PREFIX . "product_sn` WHERE product_id = '" . (int)$product_id . "' AND status = '2'");
		$sn_sold = 0;
		if ($sn_sold_query->row) {
			$sn_sold = $sn_sold_query->row['total'];
		}
		
		return array('instore'=>$sn_instore, 'sold'=>$sn_sold);
	}

	public function getProducts($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '%" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (!empty($data['filter_abbreviation'])) {
			$sql .= " AND p.abbreviation LIKE '%" . $this->db->escape($data['filter_abbreviation']) . "%'";
		}

		if (!empty($data['filter_quick_sale'])) {
			$sql .= " AND p.quick_sale = '" . $data['filter_quick_sale'] . "'";
		}

		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.abbreviation',
			'p.quick_sale'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY pd.name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getProductImages($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getTotalProducts($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";

		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '%" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (!empty($data['filter_abbreviation'])) {
			$sql .= " AND p.abbreviation LIKE '%" . $this->db->escape($data['filter_abbreviation']) . "%'";
		}

		if (!empty($data['filter_quick_sale'])) {
			$sql .= " AND p.quick_sale = '" . $data['filter_quick_sale'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	
	public function getProductSNs($data) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_sn` WHERE 1";
		if (!empty($data['product_id'])) {
			$sql .= " AND product_id = '" . (int)$data['product_id'] . "'";
		}
		if (!empty($data['sn'])) {
			$sql .= " AND sn LIKE '%" . $this->db->escape($data['sn']) . "%'";
		}
		if (!empty($data['status'])) {
			$sql .= " AND status = '" . (int)$data['status'] . "'";
		}
		$start = 0;
		$limit = $this->config->get('config_limit_admin');
		if (!empty($data['start'])) {
			$start = $data['start'];
		}
		if (!empty($data['limit'])) {
			$limit = $data['limit'];
		}
		$sql .= " ORDER BY date_modified DESC LIMIT " . (int)$start . "," . (int)$limit ;
		$result = $this->db->query($sql);
		
		return $result->rows;
	}
	
	public function getTotalProductSN($data) {
		$sql = "SELECT count(product_sn_id) AS total FROM `" . DB_PREFIX . "product_sn` WHERE 1";
		if (!empty($data['product_id'])) {
			$sql .= " AND product_id = '" . (int)$data['product_id'] . "'";
		}
		if (!empty($data['sn'])) {
			$sql .= " AND sn LIKE '%" . $this->db->escape($data['sn']) . "%'";
		}
		if (!empty($data['status'])) {
			$sql .= " AND status = '" . (int)$data['status'] . "'";
		}
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
	
	public function addProductSN($product_id, $sns) {
		$duplicate_sns = array();
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_sn` WHERE product_id = '" . (int)$product_id . "'");
		if (!empty($query->rows)) {
			foreach ($query->rows as $result) {
				if (in_array($result['sn'], $sns)) {
					$duplicate_sns[] = $result['sn'];
				}
			}
		}
		// insert the records if not duplicated
		$new_sn = 0;
		foreach ($sns as $sn) {
			if (!in_array($sn, $duplicate_sns)) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_sn` SET product_id = '" . (int)$product_id . "', sn = '" . $this->db->escape($sn) . "', date_modified = NOW()");
				$new_sn ++;
			}
		}
		// update product stock
		if ($new_sn > 0) {
			$this->db->query("UPDATE `" . DB_PREFIX . "product` SET quantity = (quantity + " . $new_sn . ") WHERE product_id = '" . (int)$product_id . "'");
		}
		
		return $duplicate_sns;
	}
	
	public function deleteProductSN($product_sn_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_sn` WHERE product_sn_id = '" . (int)$product_sn_id . "'");
		if ($query->row) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "product_sn` WHERE product_sn_id = '" . (int)$product_sn_id . "'");
			// update stock if the product is in store
			if ($query->row['status'] == 1) {
				$this->db->query("UPDATE `" . DB_PREFIX . "product` SET quantity = (quantity - 1) WHERE product_id = '" . $query->row['product_id'] . "'");
			}
		}
	}
	
	public function editProductSN($product_sn_id, $sn) {
		$this->db->query("UPDATE `" . DB_PREFIX . "product_sn` SET sn = '" . $this->db->escape($sn) . "' WHERE product_sn_id = '" . (int)$product_sn_id . "'");
	}
	
	public function getCommission($product_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_commission` WHERE product_id = '" . (int)$product_id . "'");
		return $query->row;
	}
	
	public function convertProduct($product_id) {
		$query = $this->db->query("SELECT store_id FROM `" . DB_PREFIX . "product` p LEFT JOIN " . DB_PREFIX . "product_to_store ps ON (p.product_id = ps.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND ps.store_id = '-100'");
		if ($query->num_rows) {
			$this->db->query("UPDATE `" . DB_PREFIX . "product` SET quick_sale = '3' WHERE product_id = '" . (int)$product_id . "'");
			$this->db->query("UPDATE " . DB_PREFIX . "product_to_store SET store_id = '0' WHERE product_id = '" . (int)$product_id . "'");
		}
	}
}