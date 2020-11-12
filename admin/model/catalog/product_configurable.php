<?php
/*
  #file: admin/model/catalog/product_configurable.php
  #powered by fabiom7 - www.fabiom7.com - fabiome77@hotmail.it - copyright fabiom7 2012 - 2013 - 2014
  #switched: v1.5.4.1 - v1.5.5.1 - v1.5.6
*/

class ModelCatalogProductConfigurable extends Model {
	public function addProduct($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "product SET model = '" . $this->db->escape($data['model']) . "', sku = '" . $this->db->escape($data['sku']) . "', quantity = '" . (int)$data['quantity'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "',  points = '" . (int)$data['points'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '" . (int)$data['status'] . "', tax_class_id = '" . $this->db->escape($data['tax_class_id']) . "', sort_order = '" . (int)$data['sort_order'] . "', date_modified = NOW(), date_added = NOW()");
		
		$product_id = $this->db->getLastId();
		
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE product_id = '" . (int)$product_id . "'");
		}
		
		foreach ($data['product_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', tag_title = '" . $this->db->escape($value['tag_title']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "'");
		}
		
		if (isset($data['product_store'])) {
			foreach ($data['product_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
		
		if (isset($data['product_attribute'])) {
			foreach ($data['product_attribute'] as $product_attribute) {
				if ($product_attribute['attribute_id']) {
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");
					
					foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {				
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");
					}
				}
			}
		}
		
		if (isset($data['product_image'])) {
			foreach ($data['product_image'] as $product_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape(html_entity_decode($product_image['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
			}
		}
		
		if (isset($data['product_download'])) {
			foreach ($data['product_download'] as $download_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
			}
		}
		
		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}
		}
		
		if (isset($data['product_filter'])) {
			foreach ($data['product_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}
		
		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
			}
		}
		
		if (isset($data['product_reward'])) {
			foreach ($data['product_reward'] as $customer_group_id => $product_reward) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$customer_group_id . "', points = '" . (int)$product_reward['points'] . "'");
			}
		}
		
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
		
		$this->populateProductGroupedDatabaseFields($product_id, $data);
		
		$this->cache->delete('product');
		
		return $product_id;
	} //E addProduct($data)
	
	public function editProduct($product_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET model = '" . $this->db->escape($data['model']) . "', sku = '" . $this->db->escape($data['sku']) . "', quantity = '" . (int)$data['quantity'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', points = '" . (int)$data['points'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '" . (int)$data['status'] . "', tax_class_id = '" . $this->db->escape($data['tax_class_id']) . "', sort_order = '" . (int)$data['sort_order'] . "', date_modified = NOW() WHERE product_id = '" . (int)$product_id . "'");
				
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE product_id = '" . (int)$product_id . "'");
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($data['product_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', tag_title = '" . $this->db->escape($value['tag_title']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "'");
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_store'])) {
			foreach ($data['product_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");

		if (!empty($data['product_attribute'])) {
			foreach ($data['product_attribute'] as $product_attribute) {
				if ($product_attribute['attribute_id']) {
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");
					
					foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {				
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");
					}
				}
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_image'])) {
			foreach ($data['product_image'] as $product_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape(html_entity_decode($product_image['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_download'])) {
			foreach ($data['product_download'] as $download_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}		
		}
		
		if (VERSION > '1.5.4.1') {
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");
		}
		
		if (isset($data['product_filter'])) {
			foreach ($data['product_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
			}		
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = '" . (int)$product_id . "'");

		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_reward'])) {
			foreach ($data['product_reward'] as $customer_group_id => $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$customer_group_id . "', points = '" . (int)$value['points'] . "'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id. "'");
		
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
		
		$this->deleteProductGroupedDatabaseFields($product_id);
		$this->populateProductGroupedDatabaseFields($product_id, $data);
		
		$this->cache->delete('product');
	} //E editProduct($product_id, $data)
	
	// Getting 
	public function getProducts($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";
		
		$sql .= " LEFT JOIN " . DB_PREFIX . "product_grouped_type pgt ON (p.product_id = pgt.product_id)";
		
		$sql .= " WHERE p.model = 'grouped' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(pd.name) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		}
		
		if (!empty($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}
		
		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}
		
		if (isset($data['filter_type']) && !is_null($data['filter_type'])) {
			$sql .= " AND pgt.pg_type = '" . (int)$data['filter_type'] . "'";
		}
		
		$sql .= " GROUP BY p.product_id";
	
		$sort_data = array(
			'pgt.pg_type',
			'pd.name',
			'p.price',
			'p.status',
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
	}
	
	public function getProductSpecials($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "' ORDER BY priority, price");
		
		return $query->rows;
	}
	
	////
	public function getProductGroupedTypeLayout($product_id) {
		$query = $this->db->query("SELECT pg_layout FROM " . DB_PREFIX . "product_grouped_type WHERE product_id = '" . (int)$product_id . "' LIMIT 1");
		return $query->row['pg_layout'];
	}
	
	public function getProductGroupedDiscount($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_grouped_discount WHERE product_id = '" . (int)$product_id . "'");
		
		if ($query->row) {
			return $query->row;
		} else {
			return false;
		}
	}
	
	public function getProductGroupedConfigurable($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_grouped_configurable WHERE product_id = '" . (int)$product_id . "' ORDER BY option_sort_order");
		return $query->rows;
	}
	
	public function getProductGroupedStockStatusId($product_id, $child_id) {
		$query = $this->db->query("SELECT grouped_stock_status_id FROM " . DB_PREFIX . "product_grouped WHERE product_id = '" . (int)$product_id . "' AND grouped_id = '" . $child_id . "' LIMIT 1");
		return $query->row['grouped_stock_status_id'];
	}
	
	public function deleteProductGroupedDatabaseFields($product_id){
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_grouped WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_grouped_type WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_grouped_discount WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_grouped_configurable WHERE product_id = '" . (int)$product_id . "'");
	}
	
	public function populateProductGroupedDatabaseFields($product_id, $data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "product_grouped_type SET product_id='" . (int)$product_id . "', pg_type = 'configurable', pg_layout = '" . $data['pg_layout'] . "'");
		
		// discount
		if ((float)$data['group_discount']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_grouped_discount SET product_id = '" . (int)$product_id . "', discount = '" . $data['group_discount'] . "', type = '" . $data['group_discount_type'] . "'");
		}
		
		// option configurable
		if (isset($data['gpo'])) {
			$price = 0; $price_from = 0; $price_to = 0; $max_price = array(); $min_price = array();
			
			$prevent_duplicate_child_id = array();
			
			foreach ($data['gpo'] as $key => $option_config) {
				$child_id = ''; $child_to_hide = '';
				if (isset($option_config['child_id'])) {
					foreach ($option_config['pgvisibility'] as $prevent_duplicate_key => $value) {
						$pgvisibility[$prevent_duplicate_key] = $value;
					}
					foreach ($option_config['grouped_stock_status_id'] as $prevent_duplicate_key => $value) {
						$grouped_stock_status_id[$prevent_duplicate_key] = $value;
					}
					
					foreach ($option_config['child_to_hide'] as $child_key => $child_value) if($child_value) {
						$child_to_hide .= $child_key . ':' . $child_value . ';';
					}
					foreach ($option_config['child_id'] as $option_config_child_id) {
						$prevent_duplicate_child_id[$option_config_child_id] = $option_config_child_id;
						
						$child_id .= $option_config_child_id . ','; //virgola neccessaria anche per un solo id per controllo "option qty min" .xml carrello
						
						// Get min/max prices (excluding special price)
						$prices_query = $this->db->query("SELECT p.price FROM " . DB_PREFIX . "product p WHERE p.product_id = '" . (int)$option_config_child_id . "'");
						if ($prices_query->num_rows) {
							if ($option_config['option_required']) {
								$min_price[$option_config['option_type']][] = $prices_query->row['price'] * substr($option_config['option_min_qty'],0,1);
							}
							$max_price[$option_config['option_type']][] = $prices_query->row['price'] * substr($option_config['option_min_qty'],0,1);
						}
					}
				}
				
				foreach ($option_config['option_name'] as $language_id => $value) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_grouped_configurable SET product_id = '" . (int)$product_id . "', option_sort_order = '" . $option_config['option_sort_order'] . "', option_type = '" . $option_config['option_type'] . "', option_required = '" . $option_config['option_required'] . "', option_min_qty = '" . $option_config['option_min_qty'] . "', option_hide_qty = '" . $option_config['option_hide_qty'] . "', language_id = '" . (int)$language_id . "', option_name = '" .  $this->db->escape($value['option_name']) . "', child_id = '" . $child_id . "', child_to_hide = '" . $child_to_hide . "'");
				}
			}
			
			foreach ($prevent_duplicate_child_id as $child_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_grouped SET product_id = '" . (int)$product_id . "', grouped_id = '" . (int)$child_id . "', grouped_stock_status_id = '" . (int)$grouped_stock_status_id[$child_id] . "'");
				
				$this->db->query("UPDATE " . DB_PREFIX . "product SET pgvisibility = '" . (int)$pgvisibility[$child_id] . "' WHERE product_id = '" . (int)$child_id . "'");
			}
			
			if ($data['price_type'] == 'price_from'){
				if ((float)$data['price_from']) {
					$price_from = $data['price_from'];
				} else {
					foreach ($min_price as $ot => $price_calc) {
						$price_from += min($price_calc);
					}
				}
			} elseif ($data['price_type'] == 'price_from_to') {
				if ((float)$data['price_from']) {
					$price_from = $data['price_from'];
				} else { 
					foreach ($min_price as $ot => $price_calc) {
						$price_from += min($price_calc);
					}
				}
				if ((float)$data['price_to']) {
					$price_to = $data['price_to'];
				} else { 
					foreach ($max_price as $ot => $price_calc) {
						$price_to += max($price_calc);
					}
				}
			} elseif ($data['price_type'] == 'price_fixed') {
				$price = $data['price_fixed'];
			}
			
			$this->db->query("UPDATE " . DB_PREFIX . "product SET price = '" . $price . "', pgprice_from = '" . $price_from . "', pgprice_to = '" . $price_to . "' WHERE product_id = '" . (int)$product_id . "'");
		}
	}
}
?>