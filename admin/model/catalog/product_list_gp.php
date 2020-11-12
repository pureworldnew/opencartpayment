<?php
/*
  #file: admin/model/catalog/product_list_gp.php
  #powered by fabiom7 - www.fabiom7.com - fabiome77@hotmail.it - copyright fabiom7 2012 - 2013 - 2014
  #switched: v1.5.4.1 - v1.5.5.1 - v1.5.6
*/

class ModelCatalogProductListGp extends Model {
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
	} //E addProduct($data);
	
	public function copyProduct($product_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		if ($query->num_rows) {
			$data = array();
			
			$data = $query->row;
						
			$data['sku'] = '';
			$data['upc'] = '';
			$data['viewed'] = '0';
			$data['keyword'] = '';
			$data['status'] = '0';
						
			$data = array_merge($data, array('product_attribute' => $this->getProductAttributes($product_id)));
			$data = array_merge($data, array('product_description' => $this->getProductDescriptions($product_id)));			
			$data = array_merge($data, array('product_discount' => $this->getProductDiscounts($product_id)));
			if (VERSION > '1.5.4.1') {
				$data = array_merge($data, array('product_filter' => $this->getProductFilters($product_id)));
			}
			$data = array_merge($data, array('product_image' => $this->getProductImages($product_id)));		
			$data = array_merge($data, array('product_option' => $this->getProductOptions($product_id)));
			$data = array_merge($data, array('product_related' => $this->getProductRelated($product_id)));
			$data = array_merge($data, array('product_reward' => $this->getProductRewards($product_id)));
			$data = array_merge($data, array('product_special' => $this->getProductSpecials($product_id)));
			$data = array_merge($data, array('product_category' => $this->getProductCategories($product_id)));
			$data = array_merge($data, array('product_download' => $this->getProductDownloads($product_id)));
			$data = array_merge($data, array('product_layout' => $this->getProductLayouts($product_id)));
			$data = array_merge($data, array('product_store' => $this->getProductStores($product_id)));
			
			////
			$data = array_merge($data, array('group_list' => $this->getProductGrouped($product_id)));
			$product_grouped_discount = $this->getProductGroupedDiscount($product_id);
			$data['group_discount'] = $product_grouped_discount['discount'];
			$data['group_discount_type'] = $product_grouped_discount['type'];
			$product_grouped_type = $this->getProductGroupedType($product_id);
			$data['pg_type'] = $product_grouped_type['pg_type'];
			$data['pg_layout'] = $product_grouped_type['pg_layout'];
			if ($product_grouped_type['pg_type'] == 'configurable') {
				$data = array_merge($data, array('product_grouped_configurable' => $this->getProductGroupedConfigurable($product_id)));
			}
			
			$this->addProduct($data);
		}
	}
	
	public function deleteProductFromProductConcatTemp($id) {
		$this->db->query("DELETE FROM product_concat_temp_table WHERE id = '" . (int)$id . "'");
	}
	
	public function deleteProduct($product_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
		if (VERSION > '1.5.4.1') {
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");
		}
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id. "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE product_id = '" . (int)$product_id . "'");
		
		////
		$this->deleteProductGroupedDatabaseFields($product_id);
		$prodotti_raggruppati = $this->getProductGrouped($product_id);
		foreach ($prodotti_raggruppati as $result) {
			$grouped_is_unique = $this->db->query("SELECT product_grouped_id FROM " . DB_PREFIX . "product_grouped WHERE grouped_id = '" . (int)$result['grouped_id'] . "'");
			if (!$grouped_is_unique->num_rows) {
				$this->db->query("UPDATE " . DB_PREFIX . "product SET pgvisibility = '1', pgprice_from = '0', pgprice_to = '0' WHERE product_id = '" . (int)$result['grouped_id'] . "'");
			}
		}
		
		$this->cache->delete('product');
	} 
	
	// Getting
	public function getProducts($data = array()) {
		//print_r($data);
		$sql = "SELECT pg.*,p.*,pgt.* FROM product_concat_temp_table pg 
				left join ". DB_PREFIX ."product p on p.product_id = pg.product_id 
				left join " . DB_PREFIX . "product_grouped_type pgt on pgt.product_id = pg.product_id ";
		
		
		$sql .= " WHERE pg.name != '' ";
		
		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(pg.name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		}
		
		if (!empty($data['filter_price'])) {
			$sql .= " AND pg.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}
		
		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND pg.group_status = '" . (int)$data['filter_status'] . "'";
		}
		
		if (isset($data['filter_sku']) && !is_null($data['filter_sku'])) {
			$sql .= " AND pg.sku LIKE '%" . $data['filter_sku'] . "%'";
		}
		
		if (isset($data['filter_indicator']) && !is_null($data['filter_indicator'])) {
			$sql .= " AND pg.groupindicator = '" . $data['filter_indicator'] . "'";
		}
		
		if (isset($data['filter_indicator_id']) && !is_null($data['filter_indicator_id'])) {
			$sql .= " AND pg.groupindicator_id = '" . $data['filter_indicator_id'] . "'";
		}
		
		//$sql .= " GROUP BY pg.groupindicator_id";
	
		$sort_data = array(
			'pg.groupindicator_id',
			'name',
			'p.price',
			'p.status',
			'sort_order'
		);	
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY groupindicator_id";
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
		//echo $sql;
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getProductSpecials($product_id) {
		$query = $this->db->query("SELECT * FROM ". DB_PREFIX ."product_special WHERE product_id = '". (int)$product_id ."' ORDER BY priority, price");
		
		return $query->rows;
	}
	
	public function getTotalProducts($data = array()) {
		$sql = "SELECT COUNT(pg.id) as total FROM product_concat_temp_table pg left join ". DB_PREFIX ."product p on p.product_id = pg.product_id";
		
		$sql .= " WHERE pg.name != '' ";
		
		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(pg.name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		}
		
		if (!empty($data['filter_price'])) {
			$sql .= " AND pg.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}
		
		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND pg.group_status = '" . (int)$data['filter_status'] . "'";
		}
		
		if (isset($data['filter_sku']) && !is_null($data['filter_sku'])) {
			$sql .= " AND pg.sku LIKE '%" . $data['filter_sku'] . "%'";
		}
		
		if (isset($data['filter_indicator']) && !is_null($data['filter_indicator'])) {
			$sql .= " AND pg.groupindicator = '" . $data['filter_indicator'] . "'";
		}
		
		if (isset($data['filter_indicator_id']) && !is_null($data['filter_indicator_id'])) {
			$sql .= " AND pg.groupindicator_id = '" . $data['filter_indicator_id'] . "'";
		}
		
		$sort_data = array(
			'pg.groupindicator_id',
			'name',
			'p.price',
			'p.status',
			'sort_order'
		);	
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY groupindicator_id";
		}
		
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
	
	// S for copy
	public function getProductAttributes($product_id) {
		$product_attribute_data = array();
		
		$product_attribute_query = $this->db->query("SELECT attribute_id FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' GROUP BY attribute_id");
		
		foreach ($product_attribute_query->rows as $product_attribute) {
			$product_attribute_description_data = array();
			
			$product_attribute_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");
			
			foreach ($product_attribute_description_query->rows as $product_attribute_description) {
				$product_attribute_description_data[$product_attribute_description['language_id']] = array('text' => $product_attribute_description['text']);
			}
			
			$product_attribute_data[] = array(
				'attribute_id'                  => $product_attribute['attribute_id'],
				'product_attribute_description' => $product_attribute_description_data
			);
		}
		
		return $product_attribute_data;
	}
	
	public function getProductDescriptions($product_id) {
		$product_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($query->rows as $result) {
			$product_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'description'      => $result['description'],
				'meta_keyword'     => $result['meta_keyword'],
				'meta_description' => $result['meta_description'],
				'tag'              => $result['tag'],
				'tag_title'        => $result['tag_title']
			);
		}
		
		return $product_description_data;
	}
	
	public function getProductDiscounts($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' ORDER BY quantity, priority, price");
		
		return $query->rows;
	}
	
	public function getProductFilters($product_id) {
		$product_filter_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($query->rows as $result) {
			$product_filter_data[] = $result['filter_id'];
		}
				
		return $product_filter_data;
	}
	
	public function getProductImages($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
		
		return $query->rows;
	}
	
	public function getProductOptions($product_id) {
		$product_option_data = array();
		
		$product_option_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_option` po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN `" . DB_PREFIX . "option_description` od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		foreach ($product_option_query->rows as $product_option) {
			$product_option_value_data = array();	
				
			$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value WHERE product_option_id = '" . (int)$product_option['product_option_id'] . "'");
				
			foreach ($product_option_value_query->rows as $product_option_value) {
				$product_option_value_data[] = array(
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'option_value_id'         => $product_option_value['option_value_id'],
					'quantity'                => $product_option_value['quantity'],
					'subtract'                => $product_option_value['subtract'],
					'price'                   => $product_option_value['price'],
					'price_prefix'            => $product_option_value['price_prefix'],
					'points'                  => $product_option_value['points'],
					'points_prefix'           => $product_option_value['points_prefix'],						
					'weight'                  => $product_option_value['weight'],
					'weight_prefix'           => $product_option_value['weight_prefix']					
				);
			}
				
			$product_option_data[] = array(
				'product_option_id'    => $product_option['product_option_id'],
				'option_id'            => $product_option['option_id'],
				'name'                 => $product_option['name'],
				'type'                 => $product_option['type'],			
				'product_option_value' => $product_option_value_data,
				'option_value'         => $product_option['option_value'],
				'required'             => $product_option['required']				
			);
		}
		
		return $product_option_data;
	}
	
	public function getProductRelated($product_id) {
		$product_related_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($query->rows as $result) {
			$product_related_data[] = $result['related_id'];
		}
		
		return $product_related_data;
	}
	
	public function getProductRewards($product_id) {
		$product_reward_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($query->rows as $result) {
			$product_reward_data[$result['customer_group_id']] = array('points' => $result['points']);
		}
		
		return $product_reward_data;
	}
	
	public function getProductCategories($product_id) {
		$product_category_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($query->rows as $result) {
			$product_category_data[] = $result['category_id'];
		}

		return $product_category_data;
	}
	
	public function getProductDownloads($product_id) {
		$product_download_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($query->rows as $result) {
			$product_download_data[] = $result['download_id'];
		}
		
		return $product_download_data;
	}
	
	public function getProductLayouts($product_id) {
		$product_layout_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($query->rows as $result) {
			$product_layout_data[$result['store_id']] = $result['layout_id'];
		}
		
		return $product_layout_data;
	}
	
	public function getProductStores($product_id) {
		$product_store_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_store_data[] = $result['store_id'];
		}
		
		return $product_store_data;
	}
	// E for copy

	////
	public function getProductGrouped($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_grouped WHERE product_id = '" . (int)$product_id . "' ORDER BY grouped_sort_order");
		return $query->rows;
	}
	
	public function getProductGroupedType($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_grouped_type WHERE product_id = '" . (int)$product_id . "'");
		return $query->row;
	}
	
	public function getProductGroupedDiscount($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_grouped_discount WHERE product_id = '" . (int)$product_id . "'");
		
		if ($query->row) {
			return $query->row;
		} else {
			return false;
		}
	}
	
	public function getTotalGroupedByProductId($product_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_grouped WHERE product_id = '" . (int)$product_id . "'");
		return $query->row['total'];
	}
	
	public function getProductGroupedConfigurable($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_grouped_configurable WHERE product_id = '" . (int)$product_id . "'");
		return $query->rows;
	}
	
	public function deleteProductGroupedDatabaseFields($product_id){
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_grouped WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_grouped_type WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_grouped_discount WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_grouped_configurable WHERE product_id = '" . (int)$product_id . "'");
	}
	
	public function populateProductGroupedDatabaseFields($product_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET price = '" . $data['price'] . "', pgprice_from = '" . $data['pgprice_from'] . "', pgprice_to = '" . $data['pgprice_to'] . "' WHERE product_id = '" . (int)$product_id . "'");
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "product_grouped_type SET product_id = '" . (int)$product_id . "', pg_type = '" . $data['pg_type'] . "', pg_layout = '" . $data['pg_layout'] . "'");
		
		if (isset($data['group_list'])) {
			foreach ($data['group_list'] as $key => $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_grouped SET product_id = '" . (int)$product_id . "', grouped_id = '" . (int)$value['grouped_id'] . "', grouped_maximum = '" . (int)$value['grouped_maximum'] . "', is_starting_price = '" . (int)$value['is_starting_price'] . "', grouped_sort_order = '" . (int)$value['grouped_sort_order'] . "', grouped_stock_status_id = '" . (int)$value['grouped_stock_status_id'] . "'");
			}
		}
		
		// discount configurable - bundle
		if ((float)$data['group_discount']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_grouped_discount SET product_id = '" . (int)$product_id . "', discount = '" . $data['group_discount'] . "', type = '" . $data['group_discount_type'] . "'");
		}
		
		// Configurable
		if (isset($data['product_grouped_configurable'])) {
			foreach ($data['product_grouped_configurable'] as $pgc) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_grouped_configurable SET product_id = '" . (int)$product_id . "', option_type = '" . $pgc['option_type'] . "', option_sort_order = '" . $pgc['option_sort_order'] . "', option_required = '" . $pgc['option_required'] . "', option_min_qty = '" . $pgc['option_min_qty'] . "', option_hide_qty = '" . $pgc['option_hide_qty'] . "', language_id = '" . $pgc['language_id'] . "', option_name = '" . $this->db->escape($pgc['option_name']) . "', child_id = '" . $pgc['child_id'] . "', child_to_hide = '" . $pgc['child_to_hide'] . "'");
			}
		}
	}
}
?>