<?php
class ModelFeedGoogleProductFeed extends Model {


	public function enableAllProducts() {
		$query = $this->db->query("UPDATE `" . DB_PREFIX . "product` SET `gpf_status` ='1'");
		
	}

	public function copyGTIN($gtin) {
		$query = $this->db->query("SELECT product_id, " . $gtin . " as original_gtin from  `" . DB_PREFIX . "product`");
		
		foreach ($query->rows as $result) {
			$query = $this->db->query("UPDATE " . DB_PREFIX . "product SET gtin = '" . $result['original_gtin'] ."' WHERE product_id = '" . $result['product_id'] ."'");
		}
		
	}

	public function copyMPN($mpn) {
		$query = $this->db->query("SELECT product_id, " . $mpn . " as original_mpn from  `" . DB_PREFIX . "product`");
		
		foreach ($query->rows as $result) {
			$query = $this->db->query("UPDATE " . DB_PREFIX . "product SET mpn = '" . $result['original_mpn'] ."' WHERE product_id = '" . $result['product_id'] ."'");
		}
		
	}
	
	public function copyProductCategory() {
		$query = $this->db->query("SELECT value FROM `" . DB_PREFIX . "setting` WHERE `key` = 'default_google_product_category'");
		
		if($query->num_rows) {
			$default_google_product_category = $query->row['value'];
		}
		
		if(isset($default_google_product_category)) {
			$query = $this->db->query("UPDATE " . DB_PREFIX . "product SET google_product_category = '" . $default_google_product_category ."' WHERE google_product_category = ''");
		}
		
	}
	
	public function copyCondition() {
		$query = $this->db->query("SELECT value FROM `" . DB_PREFIX . "setting` WHERE `key` = 'condition'");
		
		if($query->num_rows) {
			$default_google_condition = $query->row['value'];
		}
		
		if(isset($default_google_condition)) {
			$query = $this->db->query("UPDATE " . DB_PREFIX . "product SET `condition` = '" . $default_google_condition ."'");
		}
		
	}

	public function copyOosStatus() {
		$query = $this->db->query("SELECT value FROM `" . DB_PREFIX . "setting` WHERE `key` = 'oos_status'");
		
		if($query->num_rows) {
			$default_oos_status = $query->row['value'];
		}
		
		if(isset($default_oos_status)) {
			$query = $this->db->query("UPDATE " . DB_PREFIX . "product SET oos_status = '" . $default_oos_status ."'");
		}
		
	}

	public function copyIdentifierExists() {
		$query = $this->db->query("SELECT value FROM `" . DB_PREFIX . "setting` WHERE `key` = 'identifier_exists'");
		
		if($query->num_rows) {
			$default_identifier_exists = $query->row['value'];
		}
		
		if(isset($default_identifier_exists)) {
			$query = $this->db->query("UPDATE " . DB_PREFIX . "product SET identifier_exists = '" . $default_identifier_exists ."'");
		}
		
	}
	public function copyManufacturer() {
		$manufacturers = array();
		$query = $this->db->query("SELECT manufacturer_id, name from  `" . DB_PREFIX . "manufacturer`");
		
		foreach ($query->rows as $result) {
			$manufacturers[] = array (
				'manufacturer_id' => $result['manufacturer_id'],
				'name'            => $result['name']);
		}

		foreach ($manufacturers as $manufacturer) {
			$query = $this->db->query("UPDATE " . DB_PREFIX . "product SET brand = '" . $this->db->escape($manufacturer['name']) ."' WHERE `manufacturer_id` = '" . (int)$manufacturer['manufacturer_id'] ."'");
		}
	}
	
	public function getProducts($data = array()) {
		if ($data) {
			$sql = "SELECT *, pd.name AS name, p.image, m.name AS manufacturer FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id)";
			
			if (!empty($data['filter_category_id'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";			
			}
					
			$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'"; 
			
			if (!empty($data['filter_name'])) {
				$sql .= " AND LCASE(pd.name) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
			}

			if (!empty($data['filter_model'])) {
				$sql .= " AND LCASE(p.model) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_model'])) . "%'";
			}
			
			if (!empty($data['filter_manufacturer'])) {
				$sql .= " AND LCASE(m.name) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_manufacturer'])) . "%'";
			}
			
			if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
				$sql .= " AND p.quantity = '" . $this->db->escape($data['filter_quantity']) . "'";
			}
			
			if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
				$sql .= " AND p.gpf_status = '" . (int)$data['filter_status'] . "'";
			}
					
			if (!empty($data['filter_category_id'])) {
				if (!empty($data['filter_sub_category'])) {
					$implode_data = array();
					
					$implode_data[] = "category_id = '" . (int)$data['filter_category_id'] . "'";
					
					$this->load->model('catalog/category');
					
					$categories = $this->model_catalog_category->getCategories($data['filter_category_id']);
					
					foreach ($categories as $category) {
						$implode_data[] = "p2c.category_id = '" . (int)$category['category_id'] . "'";
					}
					
					$sql .= " AND (" . implode(' OR ', $implode_data) . ")";			
				} else {
					$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
				}
			}
			
			$sql .= " GROUP BY p.product_id";
						
			$sort_data = array(
				'pd.name',
				'p.model',
				'manufacturer',
				'p.gpf_status',
				'p.sort_order'
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
		} else {
			$product_data = $this->cache->get('product.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'));
		
			if (!$product_data) {
				$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY pd.name ASC");
	
				$product_data = $query->rows;
			
				$this->cache->set('product.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'), $product_data);
			}	
	
			return $product_data;
		}
	}

	public function getProductData($product_id) {
	
			$product_data = array();
			
			$query = $this->db->query("SELECT model FROM " . DB_PREFIX . "product  WHERE product_id = '" . (int)$product_id . "'");

			$product_data['model'] = $query->row['model'];
			
			$query = $this->db->query("SELECT name FROM " . DB_PREFIX . "product_description  WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') ."'");

			$product_data['name'] = $query->row['name'];
	
			return $product_data;
		
	}

	public function bulk_update_db($data) {

		// Replace Data
		$sql = "UPDATE " . DB_PREFIX . "product SET gpf_status = '" . (int)$data['gpf_status'] . "', `condition` = '" . $this->db->escape($data['condition']) . "', oos_status = '" . $this->db->escape($data['oos_status']) . "', identifier_exists  ='" . $this->db->escape($data['identifier_exists']) . "', adwords_redirect = '" . (int)$data['adwords_redirect'] ."'";
		
		if($data['google_product_category'] != '' && $data['google_product_category'] != $data['text_clear_keyword']) {
			$sql .= ", google_product_category = '" . $this->db->escape($data['google_product_category']) . "'";
		}
		
		if($data['brand'] != '' && $data['brand'] != $data['text_clear_keyword']) {
			$sql .= ", brand = '" . $this->db->escape($data['brand']) . "'";
		}
		
		if($data['mpn'] != '' && $data['mpn'] != $data['text_clear_keyword']) {
			$sql .= ", mpn = '" . $this->db->escape($data['mpn']) . "'";
		}
		
		if($data['colour'] != '' && $data['colour'] != $data['text_clear_keyword']) {
			$sql .= ", colour = '" . $this->db->escape($data['colour']) . "'";
		}
		
		if($data['size'] != '' && $data['size'] != $data['text_clear_keyword']) {
			$sql .= ", size = '" . $this->db->escape($data['size']) . "'";
		}
		
		if($data['gender'] != 'Do Not Change' && $data['gender'] != $data['text_clear_keyword']) {
			$sql .= ", gender = '" . $this->db->escape($data['gender']) . "'";
		}
		
		if($data['agegroup'] != 'Do Not Change' && $data['agegroup'] != $data['text_clear_keyword']) {
			$sql .= ", agegroup = '" . $this->db->escape($data['agegroup']) . "'";
		}
		
		if($data['adwords_grouping'] != '' && $data['adwords_grouping'] != $data['text_clear_keyword']) {
			$data['adwords_grouping'] = str_replace(" ", "-", trim($data['adwords_grouping']));
			$sql .= ", adwords_grouping = '" . $this->db->escape($data['adwords_grouping']) . "'";
		}
		
		if($data['adwords_labels'] != '' && $data['adwords_labels'] != $data['text_clear_keyword']) {
			$sql .= ", adwords_labels = '" . $this->db->escape($data['adwords_labels']) . "'";
		}
		
		
		
		// Clear Data
		
		$sql_clear = '';
		
		if($data['brand'] == $data['text_clear_keyword']) {
			if($sql_clear) {
				$sql_clear .= ", brand = ''";
			} else {
				$sql_clear .= "brand = ''";
			}
		}
		
		if($data['mpn'] == $data['text_clear_keyword']) {
			if($sql_clear) {
				$sql_clear .= ", mpn = ''";
			} else {
				$sql_clear .= "mpn = ''";
			}
		}
		
		if($data['colour'] == $data['text_clear_keyword']) {
			if($sql_clear) {
				$sql_clear .= ", colour = ''";
			} else {
				$sql_clear .= "colour = ''";
			}
		}
		
		if($data['size'] == $data['text_clear_keyword']) {
			if($sql_clear) {
				$sql_clear .= ", size = ''";
			} else {
				$sql_clear .= "size = ''";
			}
		}
		
		if($data['adwords_grouping'] == $data['text_clear_keyword']) {
			if($sql_clear) {
				$sql_clear .= ", adwords_grouping = ''";
			} else {
				$sql_clear .= "adwords_grouping = ''";
			}
		}
		
		if($data['adwords_labels'] == $data['text_clear_keyword']) {
			if($sql_clear) {
				$sql_clear .= ", adwords_labels = ''";
			} else {
				$sql_clear .= "adwords_labels = ''";
			}
		}
		
		if($sql_clear) {
			$sql_clear = "UPDATE " . DB_PREFIX . "product SET " . $sql_clear;
		}
		
		
		// Update the database
		$products = $data['selected_products'];
		foreach($products as $product_id) {
		
			$query = $this->db->query($sql . " WHERE product_id = '" . (int)$product_id . "'");
			if($sql_clear) {
				$query = $this->db->query($sql_clear . " WHERE product_id = '" . (int)$product_id . "'");
			}
			
			if(isset($data['product_variants']) ){
				$query = $this->db->query("DELETE FROM " . DB_PREFIX . "product_variants WHERE product_id = '" . (int)$product_id . "'");
				$query = $this->db->query("INSERT INTO " . DB_PREFIX . "product_variants SET product_id = '" . (int)$product_id . "', variants = '" . $this->db->escape(serialize($data['product_variants'])) . "'");
			} elseif (!isset($data['product_variants']) && $data['clear_variants'] == '0') {
				$query = $this->db->query("DELETE FROM " . DB_PREFIX . "product_variants WHERE product_id = '" . (int)$product_id . "'");
			}

		}
		
	}
	
}
?>