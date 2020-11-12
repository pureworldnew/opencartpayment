<?php
class ModelCatalogProduct extends Model {
	public function addProduct($data) {
		$data['quantity'] = isset($data['total_instock_quantity']) ? $data['total_instock_quantity'] : $data['quantity'];
		if ( $data['quantity'] > 0 )
		{
			//$data['frontend_date_available'] = $data['date_sold_out'] = $data['date_ordered'] = "0000-00-00";
		}
		$this->event->trigger('pre.admin.product.add', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "product SET model = '" . $this->db->escape($data['model']) . "', sku = '" . $this->db->escape($data['sku']) . "', upc = '" . $this->db->escape($data['upc']) . "', ean = '" . $this->db->escape($data['ean']) . "', jan = '" . $this->db->escape($data['jan']) . "', isbn = '" . $this->db->escape($data['isbn']) . "', mpn = '" . $this->db->escape($data['mpn']) . "', location = '" . $this->db->escape($data['location']) . "', quantity = '" . (int)$data['quantity'] . "', front_quantity = '" . (int)$data['front_quantity'] . "', storage_quantity = '" . (int)$data['storage_quantity'] . "', minimum = '" . (int)$data['minimum'] . "',  minimum_amount = '" . (float)$data['minimum_amount'] . "', subtract = '" . (int)$data['subtract'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', frontend_date_available = '" . $this->db->escape($data['frontend_date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "', points = '" . (int)$data['points'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '" . (int)$data['status'] . "', tax_class_id = '" . (int)$data['tax_class_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_added = NOW(), estimate_deliver_time = '" . (int)$data['estimate_deliver_time'] . "', date_sold_out = '" . $data['date_sold_out'] . "', date_ordered = '" . $data['date_ordered'] . "', show_product_label_1 = '" . $data['show_product_label_1'] . "', product_label_text_1 = '" . $data['product_label_text_1'] . "', show_product_label_2 = '" . $data['show_product_label_2'] . "', product_label_text_2 = '" . $data['product_label_text_2'] . "'");

		$product_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape($data['image']) . "' WHERE product_id = '" . (int)$product_id . "'");
		}
		 if(isset($this->request->post['locations'])){
			foreach ($this->request->post['locations'] as $location) {
				$this->db->query("INSERT INTO ".DB_PREFIX."product_to_location_quantity SET product_id='".(int)$product_id."',unit_id='".$location["'location_unit'"]."',location_id='".$location["'location_name'"]."',location_quantity='".$location["'location_quantity'"]."'  ");
			}
		}

		$this->db->query("UPDATE ".DB_PREFIX."product SET unit_singular='".$this->db->escape($data['unit_singular'])."',unit_plural='".$this->db->escape($data['unit_plural'])."',unique_option_price='".$this->db->escape($data['unique_option_price'])."',unique_price_discount='".$this->db->escape($data['unique_price_discount'])."',labour_cost='".$this->db->escape($data['labour_cost'])."' WHERE product_id='".$product_id."' ");


		foreach ($data['product_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		if (isset($data['product_store'])) {
			foreach ($data['product_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['product_attribute'])) {
			foreach ($data['product_attribute'] as $product_attribute) {
				if ($product_attribute['attribute_id']) {
					foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");
					}
				}
			}
		}
		
		if (!empty($data['product_article'])) {
			foreach ($data['product_article'] as $product_article) {
				if ($product_article['article_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_article SET product_id = '" . (int)$product_id . "', article_id = '" . (int)$product_article['article_id'] . "', name = '" . $this->db->escape($product_article['name']) . "'");
				}
			}
		}
		
		

		$this->db->query("DELETE FROM " . DB_PREFIX . "estimatedays WHERE product_id = '" . (int)$product_id . "'");
		if( isset($data['estimatedays']) ){
			foreach ($data['estimatedays'] as $get_data) {
				if($get_data['days']){
					$this->db->query("INSERT INTO ". DB_PREFIX."estimatedays SET product_id='".(int)$product_id."', estimate_days='".$get_data['days']."', text='".$get_data['estimatedays_description']."'");
				}
			}
		}


		if (isset($data['product_option'])) {
			foreach ($data['product_option'] as $product_option) {
				if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
					if (isset($product_option['product_option_value'])) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");

						$product_option_id = $this->db->getLastId();

						foreach ($product_option['product_option_value'] as $product_option_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . (int)$product_option_value['option_value_id'] . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
						}
					}
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', value = '" . $this->db->escape($product_option['value']) . "', required = '" . (int)$product_option['required'] . "'");
				}
			}
		}

		if (isset($data['product_discount'])) {
			foreach ($data['product_discount'] as $product_discount) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_discount['customer_group_id'] . "', quantity = '" . (int)$product_discount['quantity'] . "',discount_percent='".(float)$product_discount['discount_percent']."', priority = '" . (int)$product_discount['priority'] . "', price = '" . (float)$product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
			}
		}

		if (isset($data['product_special'])) {
			foreach ($data['product_special'] as $product_special) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_special['customer_group_id'] . "', priority = '" . (int)$product_special['priority'] . "', price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
			}
		}

		if (isset($data['product_image'])) {
			foreach ($data['product_image'] as $product_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($product_image['image']) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
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
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "' AND is_wwell = 0");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "' AND is_wwell = 0");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
			}
		}

		if (isset($data['product_wwell'])) {
			foreach ($data['product_wwell'] as $related_id) {
				//die("HERE YOU GO");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "' AND is_wwell = 1");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "',is_wwell = 1");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "' AND is_wwell = 1");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "',is_wwell = 1");
			}
		}

		if (isset($data['product_reward'])) {
			foreach ($data['product_reward'] as $customer_group_id => $product_reward) {
				if ((int)$product_reward['points'] > 0) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$customer_group_id . "', points = '" . (int)$product_reward['points'] . "'");
				}
			}
		}

		if (isset($data['product_layout'])) {
			foreach ($data['product_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_layout SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		if (isset($data['keyword'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		if (isset($data['product_recurrings'])) {
			foreach ($data['product_recurrings'] as $recurring) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_recurring` SET `product_id` = " . (int)$product_id . ", customer_group_id = " . (int)$recurring['customer_group_id'] . ", `recurring_id` = " . (int)$recurring['recurring_id']);
			}
		}

		$this->cache->delete('product');

		$this->event->trigger('post.admin.product.add', $product_id);

		return $product_id;
	}

	public function editProduct($product_id, $data) {
		//die("DHDHD");
		$data['quantity'] = isset($data['total_instock_quantity']) ? $data['total_instock_quantity'] : $data['quantity'];
		if ( $data['quantity'] > 0 )
		{
			//$data['frontend_date_available'] = $data['date_sold_out'] = $data['date_ordered'] = "0000-00-00";
		}
		$this->event->trigger('pre.admin.product.edit', $data);

		$this->db->query("UPDATE " . DB_PREFIX . "product SET model = '" . $this->db->escape($data['model']) . "', sku = '" . $this->db->escape($data['sku']) . "', upc = '" . $this->db->escape($data['upc']) . "', ean = '" . $this->db->escape($data['ean']) . "', jan = '" . $this->db->escape($data['jan']) . "', isbn = '" . $this->db->escape($data['isbn']) . "', mpn = '" . $this->db->escape($data['mpn']) . "', location = '" . $this->db->escape($data['location']) . "', quantity = '" . (int)$data['quantity'] . "',front_quantity = '" . (int)$data['front_quantity'] . "', storage_quantity = '" . (int)$data['storage_quantity'] . "', minimum = '" . (int)$data['minimum'] . "', minimum_amount = '" . (float)$data['minimum_amount'] . "', subtract = '" . (int)$data['subtract'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', frontend_date_available = '" . $this->db->escape($data['frontend_date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "', points = '" . (int)$data['points'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '" . (int)$data['status'] . "',pos_status = '" . (int)$data['pos_status'] . "', tax_class_id = '" . (int)$data['tax_class_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_modified = NOW(), estimate_deliver_time = '" . (int)$data['estimate_deliver_time'] . "', date_sold_out = '" . $data['date_sold_out'] . "', date_ordered = '" . $data['date_ordered'] . "', show_product_label_1 = '" . $data['show_product_label_1'] . "', product_label_text_1 = '" . $data['product_label_text_1'] . "', show_product_label_2 = '" . $data['show_product_label_2'] . "', product_label_text_2 = '" . $data['product_label_text_2'] . "' WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape($data['image']) . "' WHERE product_id = '" . (int)$product_id . "'");
		}

		$this->db->query("UPDATE ".DB_PREFIX."product SET unit_singular='".$this->db->escape($data['unit_singular'])."',unit_plural='".$this->db->escape($data['unit_plural'])."',unique_option_price='".$this->db->escape($data['unique_option_price'])."',unique_price_discount='".$this->db->escape($data['unique_price_discount'])."',labour_cost='".$this->db->escape($data['labour_cost'])."' WHERE product_id='".$product_id."' ");
		
		 if(isset($this->request->post['locations'])){
			$this->db->query("DELETE FROM ".DB_PREFIX."product_to_location_quantity WHERE product_id='".(int)$product_id."' ");
			foreach ($this->request->post['locations'] as $location) {
				$this->db->query("INSERT INTO ".DB_PREFIX."product_to_location_quantity SET product_id='".(int)$product_id."',unit_id='".$location["'location_unit'"]."',location_id='".$location["'location_name'"]."',location_quantity='".$location["'location_quantity'"]."'  ");
			}
		}


		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");

		foreach ($data['product_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
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
					foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");
					}
				}
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_article WHERE product_id = '" . (int)$product_id . "'");

		if (!empty($data['product_article'])) {
			foreach ($data['product_article'] as $product_article) {
				if ($product_article['article_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_article SET product_id = '" . (int)$product_id . "', article_id = '" . (int)$product_article['article_id'] . "', name = '" . $this->db->escape($product_article['name']) . "'");
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "estimatedays WHERE product_id = '" . (int)$product_id . "'");
		if( isset($data['estimatedays']) ){
			foreach ($data['estimatedays'] as $get_data) {
				if($get_data['days']){
					$this->db->query("INSERT INTO ". DB_PREFIX."estimatedays SET product_id='".(int)$product_id."', estimate_days='".$get_data['days']."', text='".$get_data['estimatedays_description']."'");
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_option'])) {
			foreach ($data['product_option'] as $product_option) {
				if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
					if (isset($product_option['product_option_value'])) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_option_id = '" . (int)$product_option['product_option_id'] . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");

						$product_option_id = $this->db->getLastId();

						foreach ($product_option['product_option_value'] as $product_option_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_value_id = '" . (int)$product_option_value['product_option_value_id'] . "', product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . (int)$product_option_value['option_value_id'] . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
						}
					}
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_option_id = '" . (int)$product_option['product_option_id'] . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', value = '" . $this->db->escape($product_option['value']) . "', required = '" . (int)$product_option['required'] . "'");
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_discount'])) {
			foreach ($data['product_discount'] as $product_discount) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_discount['customer_group_id'] . "', quantity = '" . (int)$product_discount['quantity'] . "',discount_percent='".(float)$product_discount['discount_percent']."', priority = '" . (int)$product_discount['priority'] . "', price = '" . (float)$product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_special'])) {
			foreach ($data['product_special'] as $product_special) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_special['customer_group_id'] . "', priority = '" . (int)$product_special['priority'] . "', price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_image'])) {
			foreach ($data['product_image'] as $product_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($product_image['image']) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
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

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_filter'])) {
			foreach ($data['product_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = '" . (int)$product_id . "'");

		/*if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
			}
		}*/
		//print_r($data['product_related']);
		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "' AND is_wwell = 0");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "' AND is_wwell = 0");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
			}
		}

		//print_r($data['product_wwell']);
		//die();

		if (isset($data['product_wwell'])) {
			foreach ($data['product_wwell'] as $related_id) {
				//die("HERE YOU GO");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "' AND is_wwell = 1");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "',is_wwell = 1");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "' AND is_wwell = 1");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "',is_wwell = 1");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_reward'])) {
			foreach ($data['product_reward'] as $customer_group_id => $value) {
				if ((int)$value['points'] > 0) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$customer_group_id . "', points = '" . (int)$value['points'] . "'");
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_layout'])) {
			foreach ($data['product_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_layout SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "'");

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_recurring` WHERE product_id = " . (int)$product_id);

		if (isset($data['product_recurring'])) {
			foreach ($data['product_recurring'] as $product_recurring) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_recurring` SET `product_id` = " . (int)$product_id . ", customer_group_id = " . (int)$product_recurring['customer_group_id'] . ", `recurring_id` = " . (int)$product_recurring['recurring_id']);
			}
		}

		$this->cache->delete('product');

		$this->event->trigger('post.admin.product.edit', $product_id);
	}

	public function copyProduct($product_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		if ($query->num_rows) {
			$data = $query->row;

			$data['sku'] = '';
			$data['upc'] = '';
			$data['viewed'] = '0';
			$data['keyword'] = '';
			$data['status'] = '0';

			$data['product_attribute'] = $this->getProductAttributes($product_id);
			$data['product_description'] = $this->getProductDescriptions($product_id);
			$data['product_discount'] = $this->getProductDiscounts($product_id);
			$data['product_filter'] = $this->getProductFilters($product_id);
			$data['product_image'] = $this->getProductImages($product_id);
			$data['product_option'] = $this->getProductOptions($product_id);
			$data['product_related'] = $this->getProductRelated($product_id);
			$data['product_reward'] = $this->getProductRewards($product_id);
			$data['product_special'] = $this->getProductSpecials($product_id);
			$data['product_category'] = $this->getProductCategories($product_id);
			$data['product_download'] = $this->getProductDownloads($product_id);
			$data['product_layout'] = $this->getProductLayouts($product_id);
			$data['product_store'] = $this->getProductStores($product_id);
			$data['product_recurrings'] = $this->getRecurrings($product_id);

			$this->addProduct($data);
		}
	}

	


	public function deleteProduct($product_id) {
		$this->event->trigger('pre.admin.product.delete', $product_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_recurring WHERE product_id = " . (int)$product_id);
		$this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "'");

		$this->cache->delete('product');

		$this->event->trigger('post.admin.product.delete', $product_id);
	}

	public function getMissingImageProducts()
	{
		$query = $this->db->query("SELECT p.product_id, p.sku, p.model, p.image, pd.name FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.status = 1 AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->rows;
	}

	public function getProduct($product_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "') AS keyword FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getProductEstimateDays($product_id){
		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."estimatedays WHERE product_id='".$product_id."' ORDER BY estimate_days");
		//print_r($query);
		return $query->rows;
		//return 'No';
	}

	public function getProducts($data = array()) {
		
		$sql = "SELECT p.*, pd.*, mn.name as `manufacturer` FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";
		$sql.= " LEFT JOIN ". DB_PREFIX . "manufacturer mn ON (p.manufacturer_id = mn.manufacturer_id)";
		$filter_price_type = -1;
		$filter_quantity_type = -1; //default to like operator

		if (isset($data['filter_price_type']) && !is_null($data['filter_price_type'])){
			$filter_price_type = $this->db->escape($data['filter_price_type']);
		}

		if (isset($data['filter_quantity_type']) && !is_null($data['filter_quantity_type'])){
			$filter_quantity_type = $this->db->escape($data['filter_quantity_type']);
		}

		if (!empty($data['filter_category_id'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";			
		}
				
		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'"; 
if(!empty($data['filter_grouped'])){ $sql .= " AND p.model != 'grouped'"; }
                if(isset($data['pgvisibility']) && $data['pgvisibility']) {
                    $sql .= " AND p.pgvisibility = 1";
                }

        if (isset($data['filter_location']) && !is_null($data['filter_location'])){
			$sql .= " AND p.location LIKE '" . $this->db->escape($data['filter_location']) . "%'";
		}       
         
		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
			$sql .= " OR p.sku LIKE '" . $this->db->escape($data['filter_name']) . "%'";
			$sql .= " OR p.model LIKE '" . $this->db->escape($data['filter_name']) . "%'";
			$sql .= " OR p.mpn LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		
		if (!empty($data['filter_price'])) {
			switch ($filter_price_type) {
				case -1:
					$kw = 'LIKE';
					break;
				case 0:
					$kw = '=';
					break;
				case 1:
					$kw = '>';
					break;
				case 2:
					$kw = '<';
					break;
				
				default:
					$kw = 'LIKE'; //for error handling , never reaches here in bug free mode
					break;
			}
			//$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
			$sql .= " AND p.price ".$kw. "'" . $this->db->escape($data['filter_price']) .(($kw != 'LIKE')?"'":"%'");
			//$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}
		
		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			switch ($filter_quantity_type) {
				case -1:
					$kw = '=';
					break;
				case 0:
					$kw = '=';
					break;
				case 1:
					$kw = '>';
					break;
				case 2:
					$kw = '<';
					break;
				
				default:
					$kw = '='; //for error handling , never reaches here in bug free mode
					break;
			}
			//$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
			$sql .= " AND p.quantity ".$kw. "'" . $this->db->escape($data['filter_quantity']) ."'";
			//$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
			//$sql .= " AND p.quantity = '" . $this->db->escape($data['filter_quantity']) . "'";
		}
		
		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		if (isset($data['filter_product_type']) && !is_null($data['filter_product_type']) && $data['filter_product_type'] > 1) {
			$sql .= " AND p.quick_sale = '" . (int)$data['filter_product_type'] . "'";
		}

		if (!empty($data['filter_category_id'])) {
			$sql .= " AND p2c.category_id IN (" . $data['filter_category_id'] . ") ";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id IN (" . $data['filter_manufacturer_id'] . ") ";
		}
		
		$sql .= " GROUP BY p.product_id";
					
		$sort_data = array(
			'pd.name',
			'p.model',
			'p.price',
			'p.location',
			'p.quantity',
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
		//echo ($sql);
		
		$query = $this->db->query($sql);
	
		return $query->rows;
	}

	public function getProductsByCategoryId($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2c.category_id = '" . (int)$category_id . "' ORDER BY pd.name ASC");

		return $query->rows;
	}

	public function getProductDescriptions($product_id) {
		$product_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'description'      => $result['description'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword'],
				'tag'              => $result['tag']
			);
		}

		return $product_description_data;
	}

	public function getProductCategories($product_id) {
		$product_category_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_category_data[] = $result['category_id'];
		}

		return $product_category_data;
	}

	public function getProductFilters($product_id) {
		$product_filter_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_filter_data[] = $result['filter_id'];
		}

		return $product_filter_data;
	}

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

	public function getProductAttributesData($product_id){
	    $product_attribute_data = array();

		$product_attribute_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' GROUP BY attribute_id");

		foreach ($product_attribute_query->rows as $key => $product_attribute) {
			
			$product_attribute_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "attribute_description WHERE  attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");
			$data =$product_attribute_description_query->row;

			$product_attribute_data []= array('name'=> $data["name"],"value"=>$product_attribute["text"],"required"=>1);				
		}

		return $product_attribute_data;

	}
	
	public function getProductArticles($product_id) {
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_article WHERE product_id = '" . (int)$product_id . "'");

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
					'sku'                     => $product_option_value['sku'],	
					'cost'                    => $product_option_value['cost'],	
					'cost_amount'             => $product_option_value['cost_amount'],							
					'cost_prefix'             => $product_option_value['cost_prefix'],
					'costing_method'     	  => $product_option_value['costing_method'],
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
				'value'         => $product_option['option_value'],
				'required'             => $product_option['required']				
			);
		}

		return $product_option_data;
	}

	public function getProductOptionValue($product_id, $product_option_value_id) {
		$query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getProductImages($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getProductDiscounts($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' ORDER BY quantity, priority, price");

		return $query->rows;
	}

	public function getProductDiscountsByCustomerGroud($product_id, $customer_group_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' ORDER BY quantity, priority, price");

		return $query->rows;
	}

	public function getProductSpecials($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "' ORDER BY priority, price");

		return $query->rows;
	}

	public function getProductRewards($product_id) {
		$product_reward_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_reward_data[$result['customer_group_id']] = array('points' => $result['points']);
		}

		return $product_reward_data;
	}

	public function getProductDownloads($product_id) {
		$product_download_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_download_data[] = $result['download_id'];
		}

		return $product_download_data;
	}

	public function getProductStores($product_id) {
		$product_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_store_data[] = $result['store_id'];
		}

		return $product_store_data;
	}

	public function getProductLayouts($product_id) {
		$product_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $product_layout_data;
	}

	public function getProductRelated($product_id,$mode = 0) {
		$product_related_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND is_wwell='$mode'");

		foreach ($query->rows as $result) {
			$product_related_data[] = $result['related_id'];
		}

		return $product_related_data;
	}

	public function getRecurrings($product_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_recurring` WHERE product_id = '" . (int)$product_id . "'");

		return $query->rows;
	}

	public function getTotalProducts($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";

		if (!empty($data['filter_category_id'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";			
		}
		
		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		$filter_price_type = -1;
		$filter_quantity_type = -1; //default to like operator

		if (isset($data['filter_price_type']) && !is_null($data['filter_price_type'])){
			$filter_price_type = $this->db->escape($data['filter_price_type']);
		}

		if (isset($data['filter_quantity_type']) && !is_null($data['filter_quantity_type'])){
			$filter_quantity_type = $this->db->escape($data['filter_quantity_type']);
		}

		if (isset($data['filter_location']) && !is_null($data['filter_location'])){
			$sql .= " AND p.location LIKE '" . $this->db->escape($data['filter_location']) ."%'";
		}

		

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
			switch ($filter_price_type) {
				case -1:
					$kw = 'LIKE';
					break;
				case 0:
					$kw = '=';
					break;
				case 1:
					$kw = '>';
					break;
				case 2:
					$kw = '<';
					break;
				
				default:
					$kw = 'LIKE'; //for error handling , never reaches here in bug free mode
					break;
			}
			//$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
			$sql .= " AND p.price ".$kw. "'" . $this->db->escape($data['filter_price']) .(($kw != 'LIKE')?"'":"%'");
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			switch ($filter_quantity_type) {
				case -1:
					$kw = '=';
					break;
				case 0:
					$kw = '=';
					break;
				case 1:
					$kw = '>';
					break;
				case 2:
					$kw = '<';
					break;
				
				default:
					$kw = '='; //for error handling , never reaches here in bug free mode
					break;
			}
			//$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
			$sql .= " AND p.quantity ".$kw. "'" . $this->db->escape($data['filter_quantity']) ."'";
			//$sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		if (isset($data['filter_product_type']) && !is_null($data['filter_product_type']) && $data['filter_product_type'] > 1) {
			$sql .= " AND p.quick_sale = '" . (int)$data['filter_product_type'] . "'";
		}

		if (!empty($data['filter_category_id'])) {
			$sql .= " AND p2c.category_id IN (" . $data['filter_category_id'] . ") ";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id IN (" . $data['filter_manufacturer_id'] . ") ";
		}
		//echo ($sql);

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalProductsByTaxClassId($tax_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE tax_class_id = '" . (int)$tax_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByStockStatusId($stock_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE stock_status_id = '" . (int)$stock_status_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByWeightClassId($weight_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE weight_class_id = '" . (int)$weight_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByLengthClassId($length_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE length_class_id = '" . (int)$length_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByDownloadId($download_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_to_download WHERE download_id = '" . (int)$download_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByManufacturerId($manufacturer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByAttributeId($attribute_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_attribute WHERE attribute_id = '" . (int)$attribute_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByOptionId($option_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_option WHERE option_id = '" . (int)$option_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByProfileId($recurring_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_recurring WHERE recurring_id = '" . (int)$recurring_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}
	public function getGroupProduct($id) {
		//echo "SELECT * FROM product_concat_temp_table WHERE layout_id = '" . (int)$id . "'";
		
		$sql = "SELECT * FROM product_concat_temp_table pctt";
		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (pctt.product_id = pd.product_id)";
		$sql.= "WHERE id = '" . (int)$id . "'";
		$query = $this->db->query($sql);

		return $query->row;
	}
	
	public function getUnitDetails($product_id){
		$query = "SELECT ucv.name,ucp.convert_price,ucp.sort_order,ucp.unit_conversion_product_id
				FROM
				". DB_PREFIX ."unit_conversion_product ucp LEFT JOIN
				". DB_PREFIX ."unit_conversion_value ucv on ucp.unit_value_id=ucv.unit_value_id 
				WHERE
				product_id = '$product_id' order by sort_order";
		$query2 = $this->db->query($query);
		return $query2->rows;
	}
	
	public function getProductIdByModel($model)
	{
		$sql = "SELECT product_id FROM " . DB_PREFIX . "product WHERE model LIKE '" . $this->db->escape($model) . "'";
		$query = $this->db->query($sql);
		return $query->row ? $query->row['product_id'] : '';
	}
	
	public function getProductOptionsForSorting($product_id) {
		$product_option_data = array();

		$product_option_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_option` po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN `" . DB_PREFIX . "option_description` od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($product_option_query->rows as $product_option) {
			$product_option_value_data = array();

			$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value WHERE product_option_id = '" . (int)$product_option['product_option_id'] . "' ORDER BY sort_order");
			
			foreach ($product_option_value_query->rows as $product_option_value) {
				$product_option_value_data[] = array(
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'option_value_id'         => $product_option_value['option_value_id'],
					'option_value_name'       => $this->optionValueName($product_option_value['option_value_id']),
					'quantity'                => $product_option_value['quantity'],
					'subtract'                => $product_option_value['subtract'],
					'price'                   => $product_option_value['price'],
					'price_prefix'            => $product_option_value['price_prefix'],
					'points'                  => $product_option_value['points'],
					'points_prefix'           => $product_option_value['points_prefix'],
					'weight'                  => $product_option_value['weight'],
					'weight_prefix'           => $product_option_value['weight_prefix'],
					'sort_order'              => $product_option_value['sort_order']
				);
			}

			$product_option_data[] = array(
				'product_option_id'    => $product_option['product_option_id'],
				'option_id'            => $product_option['option_id'],
				'name'                 => $product_option['name'],
				'type'                 => $product_option['type'],			
				'product_option_value' => $product_option_value_data,
				'value'         => $product_option['option_value'],
				'required'             => $product_option['required']				
			);
		}

		return $product_option_data;
	}
	
	public function optionValueName($option_value_id)
	{
		$query = $this->db->query("SELECT name FROM " . DB_PREFIX . "option_value_description WHERE option_value_id = '" . (int)$option_value_id . "'");
		return $query->row ? $query->row['name'] : "";
	}
	
	public function getGroupedByType($product_id)
	{
		$query = $this->db->query("SELECT groupbyname FROM product_concat_temp_table WHERE product_id = '" . (int)$product_id . "'");
		return $query->row ? $query->row['groupbyname'] : "";
	}

	public function getGroupIdByProductId($product_id)
	{
		$sql = "SELECT groupindicator_id FROM product_concat_temp_table WHERE product_id = '" . (int)$product_id . "'";
		$query = $this->db->query($sql);
		$groupindicator_id = $query->row ? $query->row['groupindicator_id'] : "";
		if($groupindicator_id)
		{
			$sql2 = "SELECT group_product_id FROM " . DB_PREFIX . "product_grouped_indicator WHERE group_indicator = '" . (int)$groupindicator_id . "'";
			$query2 = $this->db->query($sql2);
			return $query2->row ? $query2->row['group_product_id'] : "";
		}
		return "";
	}
	
	public function getGroupProductsSorting($data = array()){
		$output_data = array();
		if (!empty($data['filter_model'])) {
			$product_id = $this->getProductIdByModel($data['filter_model']);
					
		}
		if(empty($product_id))
		{
			return $output_data;
		}
		
		$options = $this->getProductOptionsForSorting($product_id);
		
		$output_data['options'] = $options;
		
		
		
		$sql = "SELECT product_id FROM " . DB_PREFIX . "product_grouped WHERE grouped_id = '" . (int)$product_id . "'";
		
		$query = $this->db->query($sql);
		
		$group_id = $query->row ? $query->row['product_id'] : $this->getGroupIdByProductId($product_id);
		
		if(empty($group_id))
		{
			return $output_data;
		}
		
		$product_grouped_name = $this->getGroupedProductName($group_id);
		
		$group_by_type = $this->getGroupedByType($product_id);
		
		$group_products = $this->getGrouped($group_id);
		
		if (!empty($group_products)) 
		{ 
				foreach ($group_products as $group_product) 
				{
					$product_name = $this->getGroupedProductName($group_product['grouped_id']);
					$name = str_replace($product_grouped_name, '', $product_name);
	
					$output_data['product_grouped'][] = array(
						'product_grouped_id' => $group_product['product_grouped_id'],
						'type'        => $group_by_type,
						'option_name' => $name,
						'sort_order' => $group_product['grouped_sort_order']
					);
            }
			return $output_data;
		}
		
		return $output_data;
	}
	
	public function getProductModelById($product_id)
	{
		$query="SELECT model FROM " . DB_PREFIX . "product WHERE product_id='".$product_id."'";
		$result= $this->db->query($query);
		return $result->row ? $result->row['model'] : "";
	}
	
	public function getProductDataForCsv($product_id,$groupindicator, $groupindicator_id, $group_by)
	{
		$data = array();
		$model = $this->getProductModelById($product_id);
		$sql = "SELECT product_id FROM " . DB_PREFIX . "product_grouped WHERE grouped_id = '" . (int)$product_id . "'";
		
		$query = $this->db->query($sql);
		
		$group_id = $query->row ? $query->row['product_id'] : $this->getGroupIdByProductId($product_id);
		if($group_id)
		{
			$product_grouped_name = $this->getGroupedProductName($group_id);
		
			$group_by_type = $this->getGroupedByType($product_id);
			
			$group_products = $this->getGrouped($group_id);
			
			if (!empty($group_products) && $group_by) 
			{ 
					foreach ($group_products as $group_product) 
					{
						$product_name = $this->getGroupedProductName($group_product['grouped_id']);
						$name = str_replace($product_grouped_name, '', $product_name);
		
						$data[] = array(
							'product_id' 		=> $group_product['grouped_id'],
							'model' 			=> $this->getProductModelById($group_product['grouped_id']),
							'groupindicator' 	=> $groupindicator,
							'groupindicator_id' => $groupindicator_id,
							'type'        => $group_by_type,
							'option_name' => $name,
							'product_option_value_id' => 0,
							'sort_order' => $group_product['grouped_sort_order']
						);
				}
				
			}
			
		}
		
		$options = $this->getProductOptionsForSorting($product_id);
		if($options)
		{
			foreach($options as $option)
			{
				
				foreach($option['product_option_value'] as $product_option)
				{
					$data[] = array(
							'product_id' 		=> $product_id,
							'model' 			=> $model,
							'groupindicator' 	=> $groupindicator,
							'groupindicator_id' => $groupindicator_id,
							'type'        => $option['name'],
							'option_name' => $product_option['option_value_name'],
							'product_option_value_id' => $product_option['product_option_value_id'],
							'sort_order' => $product_option['sort_order']
						);
				}
			}
		}
		
		return $data;
	}
	
	public function getGroupedProductName($product_id=null) {
		if($product_id) {
			$query="SELECT name FROM " . DB_PREFIX . "product_description WHERE product_id='".$product_id."'";
			$result= $this->db->query($query);
			
			if(!empty($result)) {
				return $result->row['name'];
			}
		}
	}
	
	 public function getGrouped($product_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_grouped WHERE product_id = '" . $product_id . "' ORDER BY grouped_sort_order");

        return $query->rows;
    }
	
	public function updateGroupProductSorting($product_grouped)
	{ 
	  foreach($product_grouped as $k => $product_group)
	  {
		$this->db->query("UPDATE " . DB_PREFIX . "product_grouped SET grouped_sort_order = '" . $product_group . "' WHERE product_grouped_id = '" . $k . "'");
		$this->updateSortingInConcateTempTable($k, $product_group);
	  }
	}

	public function updateSortingInConcateTempTable($product_grouped_id, $grouped_sort_order)
	{
		$query = $this->db->query("SELECT grouped_id FROM " . DB_PREFIX . "product_grouped WHERE product_grouped_id = '" . $product_grouped_id . "'");
		$product_id = $query->row ? $query->row['grouped_id'] : 0;
		if($product_id)
		{
			$this->db->query("UPDATE product_concat_temp_table SET groupbysortorder = '" . $grouped_sort_order . "' WHERE product_id = '" . $product_id . "'");
		}
	}
	
	public function updateOtherOptionsSorting($other_options)
	{
	  foreach($other_options as $k => $sort_order)
	  {  
		  $this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET sort_order = '" . $sort_order . "' WHERE product_option_value_id = '" . $k . "'");
		  $this->updateOptionSortingInConcateTempTable($k);
	  }
	}

	public function updateOptionSortingInConcateTempTable($product_option_value_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value WHERE product_option_value_id = '" . $product_option_value_id . "'");

		$product_option_value = $query->row;

		if ( $product_option_value )
		{
			$product_id = $product_option_value['product_id'];
			$option_id = $product_option_value['option_id'];
			$option_value_id = $product_option_value['option_value_id'];
			$option_name = $this->getOptionName($option_id);
			$option_value = $this->getOptionValue($option_id, $option_value_id);
			$sort_order = $product_option_value['sort_order'];
			$i =  $this->getOptionSortNumberInTemp($product_id, $option_name, $option_value);
			if($i)
			{
				$this->updateOptionSortNumberInTemp($product_id, $i, $sort_order);
			}

		}
	}

	public function updateOptionSortNumberInTemp($product_id, $number, $sort_order)
	{
		$this->db->query("UPDATE product_concat_temp_table SET optionsort". $number ." = '" . $sort_order . "' WHERE product_id = '" . $product_id . "'");
	}

	public function getOptionSortNumberInTemp($product_id, $option_name, $option_value)
	{
		$query = $this->db->query("SELECT * FROM product_concat_temp_table WHERE product_id = '" . (int)$product_id . "'");
		$data = $query->row;
		if ( $data )
		{
			for ($i = 1; $i <= 11; $i++) {
				if (!empty($data['optionname' . $i]) && trim($data['optionname' . $i]) == trim($option_name) && trim($data['optionvalue' . $i]) == trim($option_value) ) {
					return $i;
				}
			}
		}

		return 0;
	}

	public function getOptionName($option_id)
	{
		$query = $this->db->query("SELECT name FROM " . DB_PREFIX . "option_description WHERE option_id = '" . $option_id . "' AND language_id = 1");
		return $query->row ? $query->row['name'] : "";
	}

	public function getOptionValue($option_id, $option_value_id)
	{
		$query = $this->db->query("SELECT name FROM " . DB_PREFIX . "option_value_description WHERE option_id = '" . $option_id . "' AND option_value_id = '" . $option_value_id . "' AND language_id = 1");
		return $query->row ? $query->row['name'] : "";
	}
	
	public function exportProductsByGroupIndicator($groupindicator)
	{
		$products = array();
		$products_data = array();
		$sql = "SELECT groupindicator_id FROM product_concat_temp_table WHERE groupindicator LIKE '" . $this->db->escape($groupindicator) . "' GROUP BY groupindicator";
		$query = $this->db->query($sql);
		if( $query->row )
		{
			$groupindicator_id = $query->row['groupindicator_id'];
			$sql2 = "SELECT group_product_id FROM " . DB_PREFIX . "product_grouped_indicator WHERE group_indicator = '" . (int)$groupindicator_id . "'";
			$query2 = $this->db->query($sql2);
			if( $query2->row )
			{
				$group_product_id = $query2->row['group_product_id'];
				$sql3 = "SELECT grouped_id FROM " . DB_PREFIX . "product_grouped WHERE product_id = '" . (int)$group_product_id . "'";
				$query3 = $this->db->query($sql3);
				if( $query3->rows )
				{
					foreach ( $query3->rows as $product )
					{
						$products[] = $product['grouped_id'];
					}
					if($products)
					{
						$group_by = true;
						foreach($products as $product_id)
						{ 
							$products_data[] = $this->getProductDataForCsv($product_id,$groupindicator, $groupindicator_id, $group_by);
							$group_by = false;
						}
					}
				}
			}
		}
		
		
		$fp = fopen(DIR_DOWNLOAD . "sorting_by_groupindicator.csv", 'w');
		$heading = array("Product ID","Model","Groupindicator","Groupindicator_ID","Group By/Option Type","Option Name","Product Option Value Id","Sort Order");
		fputcsv($fp, $heading);	
		if($products_data)
		{
			foreach($products_data as $product_data)
			{
				foreach($product_data as $option_data)
				{
					fputcsv($fp, $option_data);
				}
			}
		}
		
		fclose($fp);
		
		$file = DIR_DOWNLOAD . "sorting_by_groupindicator.csv"; //path to the file on disk
        if (file_exists($file)) {

            //set appropriate headers
            header('Content-Description: File Transfer');
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename=' . basename($file));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            // ob_clean();
            flush();

            //read the file from disk and output the content.
            readfile($file);
            exit;
        }
	}
	
	public function UpdateCSVSortingData($data)
	{
		if($data[0] !== 'Product ID')
		{
			$product_id 		= $data[0];
			$model 				= $data[1];
			$groupindicator 	= $data[2];
			$groupindicator_id 	= $data[3];
			$type        		= $data[4];
			$option_name 		= $data[5];
			$product_option_value_id = $data[6];
			$sort_order 		= $data[7];
			
			if ( $product_option_value_id > 0 )
			{
				$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET sort_order = '" . $sort_order . "' WHERE product_option_value_id = '" . $product_option_value_id . "'");
				} else {
				$this->db->query("UPDATE " . DB_PREFIX . "product_grouped SET grouped_sort_order = '" . $sort_order . "' WHERE grouped_id = '" . $product_id . "'");
			}
		}
	}
	public function getProductsForAC($data = array())
	{
		$sql = "SELECT pd.product_id,pd.name,r.model,r.image FROM ". DB_PREFIX . "product_description pd";
		$sql.= " LEFT JOIN ".DB_PREFIX."product r ON (pd.product_id = r.product_id)";
		
		$lid = $this->config->get('config_language_id');
		$sql .= " WHERE pd.language_id = '" . $lid . "'"; 
		//echo "here";
		//die($sql);


    
         
		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND r.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
			//$sql .= " OR p.sku LIKE '" . $this->db->escape($data['filter_name']) . "%'";
			//$sql .= " OR p.model LIKE '" . $this->db->escape($data['filter_name']) . "%'";
			//$sql .= " OR p.mpn LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		

		
		$sql .= " GROUP BY pd.product_id";
					
		$sort_data = array(
			'r.model',
			'pd.product_id'
			
		);	
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY r.model";	
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
		//echo ($sql);
		
		$query = $this->db->query($sql);
	
		return $query->rows;
	}

	public function getProductHistoryTotal($data = array(), $product_id) {
		if (isset($data['filter_history_date_start']) && $data['filter_history_date_start']) {
			$date_start = $data['filter_history_date_start'];
		} else {
			$date_start = '';
		}

		if (isset($data['filter_history_date_end']) && $data['filter_history_date_end']) {
			$date_end = $data['filter_history_date_end'];
		} else {
			$date_end = '';
		}

		if (isset($data['filter_history_range'])) {
			$range_history = $data['filter_history_range'];
		} else {
			$range_history = 'all_time';
		}

		switch($range_history) 
		{
			case 'custom';
				$date_start = "DATE(psh.date_added) >= '" . $this->db->escape($data['filter_history_date_start']) . "'";
				$date_end = " AND DATE(psh.date_added) <= '" . $this->db->escape($data['filter_history_date_end']) . "'";				
				break;
			case 'week';
				$date_start = "DATE(psh.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-7 day'))) . "'";
				$date_end = " AND DATE(psh.date_added) <= DATE (NOW())";	
				break;			
			case 'month';
				$date_start = "DATE(psh.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-30 day'))) . "'";

				$date_end = " AND DATE(psh.date_added) <= DATE (NOW())";					
				break;			
			case 'quarter';
				$date_start = "DATE(psh.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-91 day'))) . "'";
				$date_end = " AND DATE(psh.date_added) <= DATE (NOW())";						
				break;
			case 'year';
				$date_start = "DATE(psh.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-365 day'))) . "'";
				$date_end = " AND DATE(psh.date_added) <= DATE (NOW())";					
				break;
			case 'current_week';
				$date_start = "DATE(psh.date_added) >= CURDATE() - WEEKDAY(CURDATE())";
				$date_end = " AND DATE(psh.date_added) <= DATE (NOW())";			
				break;	
			case 'current_month';
				$date_start = "YEAR(psh.date_added) = YEAR(CURDATE())";
				$date_end = " AND MONTH(psh.date_added) = MONTH(CURDATE())";			
				break;
			case 'current_quarter';
				$date_start = "QUARTER(psh.date_added) = QUARTER(CURDATE())";
				$date_end = " AND YEAR(psh.date_added) = YEAR(CURDATE())";					
				break;					
			case 'current_year';
				$date_start = "YEAR(psh.date_added) = YEAR(CURDATE())";
				$date_end = '';			
				break;					
			case 'last_week';
				$date_start = "DATE(psh.date_added) >= CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+5 DAY";
				$date_end = " AND DATE(psh.date_added) < CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-2 DAY";				
				break;	
			case 'last_month';
				$date_start = "DATE(psh.date_added) >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH, '%Y/%m/01')";
				$date_end = " AND DATE(psh.date_added) < DATE_FORMAT(CURRENT_DATE, '%Y/%m/01')";				
				break;
			case 'last_quarter';
				$date_start = "QUARTER(psh.date_added) = QUARTER(DATE_ADD(NOW(), INTERVAL -3 MONTH))";
				$date_end = '';				
				break;					
			case 'last_year';
				$date_start = "DATE(psh.date_added) >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 YEAR, '%Y/01/01')";
				$date_end = " AND DATE(psh.date_added) < DATE_FORMAT(CURRENT_DATE, '%Y/01/01')";				
				break;					
			case 'all_time';
				$date_start = "DATE(psh.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "'";
				$date_end = " AND DATE(psh.date_added) <= DATE (NOW())";						
				break;	
		}

		$option_history = '';
		if (!empty($data['filter_history_option'])) {
			$option_history = " AND CONCAT(psh.product_option_id,psh.option_id,psh.option_value_id) = '" . $data['filter_history_option'] . "'";
		}

		$supplier_history = '';
		if (!empty($data['filter_history_supplier'])) {
			$supplier_history = " AND psh.supplier_id = '" . (int)$data['filter_history_supplier'] . "'";
		}
		
		if (isset($data['filter_history_option']) && $data['filter_history_option'] == 0) {				
			$sql = "SELECT COUNT(psh.product_id) AS total FROM " . DB_PREFIX . "product_stock_history psh WHERE psh.product_id = '" . (int)$product_id . "' AND (" . $date_start . $date_end . ")" . $supplier_history;
		} else {
			$sql = "SELECT COUNT(psh.product_id) AS total FROM " . DB_PREFIX . "product_option_stock_history psh WHERE psh.product_id = '" . (int)$product_id . "' AND (" . $date_start . $date_end . ")" . $option_history . $supplier_history;
		}
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}

	public function getProductHistory($data = array(), $product_id) {		
		$token = $this->session->data['token'];
		
		if (isset($data['filter_history_date_start']) && $data['filter_history_date_start']) {
			$date_start = $data['filter_history_date_start'];
		} else {
			$date_start = '';
		}

		if (isset($data['filter_history_date_end']) && $data['filter_history_date_end']) {
			$date_end = $data['filter_history_date_end'];
		} else {
			$date_end = '';
		}

		if (isset($data['filter_history_range'])) {
			$range_history = $data['filter_history_range'];
		} else {
			$range_history = 'all_time';
		}
		
		switch($range_history) 
		{
			case 'custom';
				$date_start = "DATE(psh.date_added) >= '" . $this->db->escape($data['filter_history_date_start']) . "'";
				$date_end = " AND DATE(psh.date_added) <= '" . $this->db->escape($data['filter_history_date_end']) . "'";				
				break;
			case 'week';
				$date_start = "DATE(psh.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-7 day'))) . "'";
				$date_end = " AND DATE(psh.date_added) <= DATE (NOW())";	
				break;			
			case 'month';
				$date_start = "DATE(psh.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-30 day'))) . "'";
				$date_end = " AND DATE(psh.date_added) <= DATE (NOW())";					
				break;			
			case 'quarter';
				$date_start = "DATE(psh.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-91 day'))) . "'";
				$date_end = " AND DATE(psh.date_added) <= DATE (NOW())";						
				break;
			case 'year';
				$date_start = "DATE(psh.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-365 day'))) . "'";
				$date_end = " AND DATE(psh.date_added) <= DATE (NOW())";					
				break;
			case 'current_week';
				$date_start = "DATE(psh.date_added) >= CURDATE() - WEEKDAY(CURDATE())";
				$date_end = " AND DATE(psh.date_added) <= DATE (NOW())";			
				break;	
			case 'current_month';
				$date_start = "YEAR(psh.date_added) = YEAR(CURDATE())";
				$date_end = " AND MONTH(psh.date_added) = MONTH(CURDATE())";			
				break;
			case 'current_quarter';
				$date_start = "QUARTER(psh.date_added) = QUARTER(CURDATE())";
				$date_end = " AND YEAR(psh.date_added) = YEAR(CURDATE())";					
				break;					
			case 'current_year';
				$date_start = "YEAR(psh.date_added) = YEAR(CURDATE())";
				$date_end = '';				
				break;					
			case 'last_week';
				$date_start = "DATE(psh.date_added) >= CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+5 DAY";
				$date_end = " AND DATE(psh.date_added) < CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-2 DAY";				
				break;	
			case 'last_month';
				$date_start = "DATE(psh.date_added) >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH, '%Y/%m/01')";
				$date_end = " AND DATE(psh.date_added) < DATE_FORMAT(CURRENT_DATE, '%Y/%m/01')";				
				break;
			case 'last_quarter';
				$date_start = "QUARTER(psh.date_added) = QUARTER(DATE_ADD(NOW(), INTERVAL -3 MONTH))";
				$date_end = '';				
				break;					
			case 'last_year';
				$date_start = "DATE(psh.date_added) >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 YEAR, '%Y/01/01')";
				$date_end = " AND DATE(psh.date_added) < DATE_FORMAT(CURRENT_DATE, '%Y/01/01')";				
				break;					
			case 'all_time';
				$date_start = "DATE(psh.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "'";
				$date_end = " AND DATE(psh.date_added) <= DATE (NOW())";						
				break;	
		}

		$option_history = '';
		if (!empty($data['filter_history_option'])) {
			$option_history = " AND CONCAT(psh.product_option_id,psh.option_id,psh.option_value_id) = '" . $data['filter_history_option'] . "'";
		}

		$supplier_history = '';
		if (!empty($data['filter_history_supplier'])) {
			$supplier_history = " AND psh.supplier_id = '" . (int)$data['filter_history_supplier'] . "'";
		}
		
		if (isset($data['filter_history_option']) && $data['filter_history_option'] == 0) {			
			$sql = "SELECT psh.*, (psh.price-psh.cost) AS profit, (((psh.price-psh.cost)/psh.price)*100) AS profit_margin, (((psh.price-psh.cost)/psh.cost)*100) AS profit_markup, (SELECT s.name FROM `" . DB_PREFIX . "supplier` s WHERE s.supplier_id = psh.supplier_id) AS supplier FROM `" . DB_PREFIX . "product_stock_history` psh WHERE psh.product_id = '" . (int)$product_id . "' AND (" . $date_start . $date_end . ")" . $supplier_history;
		} else {
			$sql = "SELECT psh.*, (psh.price-psh.cost) AS profit, (((psh.price-psh.cost)/psh.price)*100) AS profit_margin, (((psh.price-psh.cost)/psh.cost)*100) AS profit_markup, (SELECT s.name FROM `" . DB_PREFIX . "supplier` s WHERE s.supplier_id = psh.supplier_id) AS supplier FROM `" . DB_PREFIX . "product_option_stock_history` psh WHERE psh.product_id = '" . (int)$product_id . "' AND (" . $date_start . $date_end . ")" . $option_history . $supplier_history;		
		}

		$sort_data = array(
			'psh.date_added',
			'psh.comment',
			'supplier',
			'psh.costing_method',			
			'psh.restock_quantity',
			'psh.stock_quantity',
			'psh.restock_cost',
			'psh.cost',
			'psh.price',
			'profit',
			'profit_margin',
			'profit_markup'
		);		
			
		if (isset($data['sort_history']) && in_array($data['sort_history'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort_history'];	
		} else {
			$sql .= " ORDER BY psh.date_added";	
		}
			
		if (isset($data['order_history']) && ($data['order_history'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
					
		if (isset($data['start_history']) || isset($data['limit_history'])) {
			if ($data['start_history'] < 0) {
				$data['start_history'] = 0;
			}				

			if ($data['limit_history'] < 1) {
				$data['limit_history'] = 20;
			}	
		
			$sql .= " LIMIT " . (int)$data['start_history'] . "," . (int)$data['limit_history'];
		}
					
		$query = $this->db->query($sql);
		
		return $query->rows;
	}

	public function getProductOptionsHistory($data = array(), $product_id) {
		$query = $this->db->query("SELECT CONCAT(poh.product_option_id,poh.option_id,poh.option_value_id) AS options, od.name AS option_name, ovd.name AS option_value, pov.sku AS option_sku FROM `" . DB_PREFIX . "product_option_stock_history` poh, `" . DB_PREFIX . "product_option_value` pov, `" . DB_PREFIX . "option_description` od, `" . DB_PREFIX . "option_value_description` ovd WHERE poh.product_id = '" . (int)$product_id . "' AND poh.product_id = pov.product_id AND poh.option_value_id = pov.option_value_id AND poh.option_id = od.option_id AND od.language_id = '" . (int)$this->config->get('config_language_id') . "' AND poh.option_value_id = ovd.option_value_id AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY od.name, ovd.name ORDER BY od.name, ovd.name ASC");		

		return $query->rows;
	}	

	public function getProductSuppliersHistory($data = array(), $product_id) {
		$query = $this->db->query("SELECT psh.supplier_id AS supplier_id, s.name AS supplier_name FROM `" . DB_PREFIX . "product_stock_history` psh, `" . DB_PREFIX . "supplier` s WHERE psh.product_id = '" . (int)$product_id . "' AND psh.supplier_id = s.supplier_id GROUP BY psh.supplier_id ORDER BY s.name ASC");		

		return $query->rows;
	}

	public function getProductChartHistory($data = array(), $product_id) {	
		
		$sql = "SELECT * FROM " . DB_PREFIX . "product_stock_history WHERE product_id = '" . (int)$product_id . "' ORDER BY date_added ASC";
					
		$query = $this->db->query($sql);
		
		return $query->rows;
	}

	public function getOrderStatuses($data = array()) {
		$query = $this->db->query("SELECT os.name, os.order_status_id FROM `" . DB_PREFIX . "order_status` os WHERE os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY LCASE(os.name) ASC");
		
		return $query->rows;	
	}

	public function getOrderOptions($data = array(), $product_id) {
		$query = $this->db->query("SELECT DISTINCT LCASE(CONCAT(oo.name, oo.value, oo.type)) AS options, oo.name AS option_name, oo.value AS option_value FROM `" . DB_PREFIX . "order` o, `" . DB_PREFIX . "order_product` op, `" . DB_PREFIX . "order_option` oo WHERE o.order_id = op.order_id AND op.order_product_id = oo.order_product_id AND op.product_id = '" . (int)$product_id . "' AND o.order_status_id > '0' AND (oo.type = 'radio' OR oo.type = 'checkbox' OR oo.type = 'select' OR oo.type = 'image' OR oo.type = 'colour' OR oo.type = 'size' OR oo.type = 'multiple') GROUP BY oo.name, oo.value, oo.type ORDER BY oo.name, oo.value, oo.type ASC");		

		return $query->rows;
	}

	public function getProductSalesTotal($data = array(), $product_id) {
		if (isset($data['filter_sale_date_start']) && $data['filter_sale_date_start']) {
			$date_start = $data['filter_sale_date_start'];
		} else {
			$date_start = '';
		}

		if (isset($data['filter_sale_date_end']) && $data['filter_sale_date_end']) {
			$date_end = $data['filter_sale_date_end'];
		} else {
			$date_end = '';
		}

		if (isset($data['filter_sale_range'])) {
			$range_sale = $data['filter_sale_range'];
		} else {
			$range_sale = 'all_time';
		}
		
		switch($range_sale) 
		{
			case 'custom';
				$date_start = "DATE(date_added) >= '" . $this->db->escape($data['filter_sale_date_start']) . "'";
				$date_end = " AND DATE(date_added) <= '" . $this->db->escape($data['filter_sale_date_end']) . "'";				
				break;
			case 'today';
				$date_start = "DATE(date_added) = CURDATE()";
				$date_end = '';
				break;
			case 'yesterday';
				$date_start = "DATE(date_added) >= DATE_ADD(CURDATE(), INTERVAL -1 DAY)";
				$date_end = " AND DATE(date_added) < CURDATE()";
				break;					
			case 'week';
				$date_start = "DATE(date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-7 day'))) . "'";
				$date_end = " AND DATE(date_added) <= DATE (NOW())";	
				break;			
			case 'month';
				$date_start = "DATE(date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-30 day'))) . "'";
				$date_end = " AND DATE(date_added) <= DATE (NOW())";					
				break;			
			case 'quarter';
				$date_start = "DATE(date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-91 day'))) . "'";
				$date_end = " AND DATE(date_added) <= DATE (NOW())";						
				break;
			case 'year';
				$date_start = "DATE(date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-365 day'))) . "'";
				$date_end = " AND DATE(date_added) <= DATE (NOW())";					
				break;
			case 'current_week';
				$date_start = "DATE(date_added) >= CURDATE() - WEEKDAY(CURDATE())";
				$date_end = " AND DATE(date_added) <= DATE (NOW())";			
				break;	
			case 'current_month';
				$date_start = "YEAR(date_added) = YEAR(CURDATE())";
				$date_end = " AND MONTH(date_added) = MONTH(CURDATE())";			
				break;
			case 'current_quarter';
				$date_start = "QUARTER(date_added) = QUARTER(CURDATE())";
				$date_end = " AND YEAR(date_added) = YEAR(CURDATE())";					
				break;					
			case 'current_year';
				$date_start = "YEAR(date_added) = YEAR(CURDATE())";
				$date_end = '';			
				break;					
			case 'last_week';
				$date_start = "DATE(date_added) >= CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+5 DAY";
				$date_end = " AND DATE(date_added) < CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-2 DAY";				
				break;	
			case 'last_month';
				$date_start = "DATE(date_added) >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH, '%Y/%m/01')";
				$date_end = " AND DATE(date_added) < DATE_FORMAT(CURRENT_DATE, '%Y/%m/01')";				
				break;
			case 'last_quarter';
				$date_start = "QUARTER(date_added) = QUARTER(DATE_ADD(NOW(), INTERVAL -3 MONTH))";
				$date_end = '';				
				break;					
			case 'last_year';
				$date_start = "DATE(date_added) >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 YEAR, '%Y/01/01')";
				$date_end = " AND DATE(date_added) < DATE_FORMAT(CURRENT_DATE, '%Y/01/01')";				
				break;					
			case 'all_time';
				$date_start = "DATE(date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "'";
				$date_end = " AND DATE(date_added) <= DATE (NOW())";						
				break;	
		}

		$sql = "SELECT COUNT(op.order_product_id) AS total FROM `" . DB_PREFIX . "order` o INNER JOIN `" . DB_PREFIX . "order_product` op ON (o.order_id = op.order_id) WHERE op.product_id = '" . (int)$product_id . "'";

		if (!empty($data['filter_sale_order_status'])) {
			$sql .= " AND (";
			$implode = array();
			foreach ($data['filter_sale_order_status'] as $filter_sale_order_status) {
				$implode[] = "o.order_status_id = '" . (int)$filter_sale_order_status . "'";
			}

			if ($implode) {
				$sql .= implode(" OR ", $implode) . "";
			}
			$sql .= ")";
		} else {
			$sql .= " AND o.order_status_id > '0'";
		}
		
		if (!empty($data['filter_sale_option'])) {
			$sql .= " AND (";
			$implode = array();
			foreach ($data['filter_sale_option'] as $filter_sale_option) {
				$implode[] = "(SELECT DISTINCT oo.order_id FROM `" . DB_PREFIX . "order_option` oo WHERE op.order_product_id = oo.order_product_id AND HEX(CONCAT(oo.name, oo.value, oo.type)) = '" . $filter_sale_option . "')";
			}

			if ($implode) {
				$sql .= implode(" AND ", $implode) . "";
			}
			$sql .= ")";
		}
				
		$sql .= ' AND (' . $date_start . $date_end . ')';
				
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}

	public function getProductSales($data = array(), $product_id) {		
		$token = $this->session->data['token'];
		
		if (isset($data['filter_sale_date_start']) && $data['filter_sale_date_start']) {
			$date_start = $data['filter_sale_date_start'];
		} else {
			$date_start = '';
		}

		if (isset($data['filter_sale_date_end']) && $data['filter_sale_date_end']) {
			$date_end = $data['filter_sale_date_end'];
		} else {
			$date_end = '';
		}

		if (isset($data['filter_sale_range'])) {
			$range_sale = $data['filter_sale_range'];
		} else {
			$range_sale = 'all_time';
		}
		
		switch($range_sale) 
		{
			case 'custom';
				$date_start = "DATE(date_added) >= '" . $this->db->escape($data['filter_sale_date_start']) . "'";
				$date_end = " AND DATE(date_added) <= '" . $this->db->escape($data['filter_sale_date_end']) . "'";				
				break;
			case 'today';
				$date_start = "DATE(date_added) = CURDATE()";
				$date_end = '';
				break;
			case 'yesterday';
				$date_start = "DATE(date_added) >= DATE_ADD(CURDATE(), INTERVAL -1 DAY)";
				$date_end = " AND DATE(date_added) < CURDATE()";
				break;					
			case 'week';
				$date_start = "DATE(date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-7 day'))) . "'";
				$date_end = " AND DATE(date_added) <= DATE (NOW())";	
				break;			
			case 'month';
				$date_start = "DATE(date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-30 day'))) . "'";
				$date_end = " AND DATE(date_added) <= DATE (NOW())";					
				break;			
			case 'quarter';
				$date_start = "DATE(date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-91 day'))) . "'";
				$date_end = " AND DATE(date_added) <= DATE (NOW())";						
				break;
			case 'year';
				$date_start = "DATE(date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-365 day'))) . "'";
				$date_end = " AND DATE(date_added) <= DATE (NOW())";					
				break;
			case 'current_week';
				$date_start = "DATE(date_added) >= CURDATE() - WEEKDAY(CURDATE())";
				$date_end = " AND DATE(date_added) <= DATE (NOW())";			
				break;	
			case 'current_month';
				$date_start = "YEAR(date_added) = YEAR(CURDATE())";
				$date_end = " AND MONTH(date_added) = MONTH(CURDATE())";			
				break;
			case 'current_quarter';
				$date_start = "QUARTER(date_added) = QUARTER(CURDATE())";
				$date_end = " AND YEAR(date_added) = YEAR(CURDATE())";					
				break;					
			case 'current_year';
				$date_start = "YEAR(date_added) = YEAR(CURDATE())";
				$date_end = '';			
				break;					
			case 'last_week';
				$date_start = "DATE(date_added) >= CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+5 DAY";
				$date_end = " AND DATE(date_added) < CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-2 DAY";				
				break;	
			case 'last_month';
				$date_start = "DATE(date_added) >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH, '%Y/%m/01')";
				$date_end = " AND DATE(date_added) < DATE_FORMAT(CURRENT_DATE, '%Y/%m/01')";				
				break;
			case 'last_quarter';
				$date_start = "QUARTER(date_added) = QUARTER(DATE_ADD(NOW(), INTERVAL -3 MONTH))";
				$date_end = '';				
				break;					
			case 'last_year';
				$date_start = "DATE(date_added) >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 YEAR, '%Y/01/01')";
				$date_end = " AND DATE(date_added) < DATE_FORMAT(CURRENT_DATE, '%Y/01/01')";				
				break;					
			case 'all_time';
				$date_start = "DATE(date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "'";
				$date_end = " AND DATE(date_added) <= DATE (NOW())";						
				break;	
		}

		$date = ' AND (' . $date_start . $date_end . ')';

		$order_status = '';
		if (!empty($data['filter_sale_order_status'])) {
			$order_status = " AND (";
			$implode = array();
			foreach ($data['filter_sale_order_status'] as $filter_sale_order_status) {
				$implode[] = "o.order_status_id = '" . (int)$filter_sale_order_status . "'";
			}

			if ($implode) {
				$order_status .= implode(" OR ", $implode) . "";
			}
			$order_status .= ")";
		} else {
		$order_status = ' AND o.order_status_id > 0';
		}

		$sale_option = '';
		if (!empty($data['filter_sale_option'])) {
			$sale_option = " AND (";
			$implode = array();
			foreach ($data['filter_sale_option'] as $filter_sale_option) {
				$implode[] = "(SELECT DISTINCT oo.order_id FROM `" . DB_PREFIX . "order_option` oo WHERE op.order_product_id = oo.order_product_id AND LCASE(CONCAT(oo.name, oo.value, oo.type)) = '" . $filter_sale_option . "')";
			}

			if ($implode) {
				$sale_option .= implode(" AND ", $implode) . "";
			}
			$sale_option .= ")";
		}
				
		$sql = "SELECT o.*, 
		op.product_id, 
		op.order_product_id, 
		CONCAT('<a href=\"index.php?route=sale/order/info&token=$token&order_id=',op.order_id,'\">',op.order_id,'</a>') AS product_order_id, 
		o.date_added AS product_date_added, 
		op.name AS product_name, 
		(SELECT GROUP_CONCAT(CONCAT(oo.name,': ',oo.value) SEPARATOR '<br>') FROM `" . DB_PREFIX . "order_option` oo WHERE op.order_product_id = oo.order_product_id AND (oo.type != 'textarea' OR oo.type != 'file' OR oo.type != 'date' OR oo.type != 'datetime' OR oo.type != 'time') ORDER BY op.order_product_id) AS product_option,  
		SUM(op.quantity_supplied) AS product_sold, 
		SUM(op.total) AS product_total_excl_vat, 
		SUM(op.tax*op.quantity) AS product_tax, 
		SUM(op.total+(op.tax*op.quantity)) AS product_total_incl_vat, 
		SUM(op.total) AS product_revenue, 
		SUM(op.cost*op.quantity) AS product_cost, 
		SUM(op.total - (op.cost*op.quantity)) AS product_profit, 
		SUM(((op.total - (op.cost*op.quantity)) / op.total)*100) AS product_margin, 
		SUM(((op.total - (op.cost*op.quantity)) / (op.cost*op.quantity))*100) AS product_markup, 		
		(SELECT SUM(op.quantity_supplied) FROM `" . DB_PREFIX . "order` o INNER JOIN `" . DB_PREFIX . "order_product` op ON (o.order_id = op.order_id) WHERE op.product_id = '" . (int)$product_id . "'" . $date . $order_status . $sale_option . ") AS product_sold_total, 
		(SELECT SUM(op.total) FROM `" . DB_PREFIX . "order` o INNER JOIN `" . DB_PREFIX . "order_product` op ON (o.order_id = op.order_id) WHERE op.product_id = '" . (int)$product_id . "'" . $date . $order_status . $sale_option . ") AS product_total_excl_vat_total, 
		(SELECT SUM(op.tax*op.quantity) FROM `" . DB_PREFIX . "order` o INNER JOIN `" . DB_PREFIX . "order_product` op ON (o.order_id = op.order_id) WHERE op.product_id = '" . (int)$product_id . "'" . $date . $order_status . $sale_option . ") AS product_tax_total, 
		(SELECT SUM(op.total+(op.tax*op.quantity)) FROM `" . DB_PREFIX . "order` o INNER JOIN `" . DB_PREFIX . "order_product` op ON (o.order_id = op.order_id) WHERE op.product_id = '" . (int)$product_id . "'" . $date . $order_status . $sale_option . ") AS product_total_incl_vat_total, 
		(SELECT SUM(op.total) FROM `" . DB_PREFIX . "order` o INNER JOIN `" . DB_PREFIX . "order_product` op ON (o.order_id = op.order_id) WHERE op.product_id = '" . (int)$product_id . "'" . $date . $order_status . $sale_option . ") AS product_revenue_total, 
		(SELECT SUM(op.cost*op.quantity) FROM `" . DB_PREFIX . "order` o INNER JOIN `" . DB_PREFIX . "order_product` op ON (o.order_id = op.order_id) WHERE op.product_id = '" . (int)$product_id . "'" . $date . $order_status . $sale_option . ") AS product_cost_total, 
		(SELECT SUM(op.total - (op.cost*op.quantity)) FROM `" . DB_PREFIX . "order` o INNER JOIN `" . DB_PREFIX . "order_product` op ON (o.order_id = op.order_id) WHERE op.product_id = '" . (int)$product_id . "'" . $date . $order_status . $sale_option . ") AS product_profit_total 
				
		FROM `" . DB_PREFIX . "order` o INNER JOIN `" . DB_PREFIX . "order_product` op ON (o.order_id = op.order_id) WHERE op.product_id = '" . (int)$product_id . "'" . $date . $order_status . $sale_option;
			
		$sql .= " GROUP BY op.order_id, product_option";

		$sort_data = array(
			'product_order_id',
			'product_date_added',
			'product_option',
			'product_sold',
			'product_total_excl_vat',
			'product_tax',
			'product_total_incl_vat',			
			'product_revenue',
			'product_cost',												
			'product_profit',
			'product_margin',
			'product_markup'
		);	
			
		if (isset($data['sort_sale']) && in_array($data['sort_sale'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort_sale'];	
		} else {
			$sql .= " ORDER BY product_date_added";	
		}
			
		if (isset($data['order_sale']) && ($data['order_sale'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
					
		if (isset($data['start_sale']) || isset($data['limit_sale'])) {
			if ($data['start_sale'] < 0) {
				$data['start_sale'] = 0;
			}				

			if ($data['limit_sale'] < 1) {
				$data['limit_sale'] = 20;
			}	
		
			$sql .= " LIMIT " . (int)$data['start_sale'] . "," . (int)$data['limit_sale'];
		}
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}	

	public function calculate($value, $tax_class_id, $calculate = true, $fixed_taxes = true) {
		if ($tax_class_id == 0) return $value;
			if ($tax_class_id && $calculate) {
				$amount = $this->getTax($value, $tax_class_id, $fixed_taxes);
				return $amount;
			} else {
				return $value;
			}
	}

	public function getTaxRates($tax_class_id) {
		$tax_rates = array();
		
		$customer_group_id = $this->config->get('config_customer_group_id');
		
		if ($this->config->get('adv_price_tax_store_based') or !$this->config->get('adv_price_tax')) {
			$this->store_address = array(
				'country_id' => $this->config->get('config_country_id'),
				'zone_id'    => $this->config->get('config_zone_id')
			);
			$this->shipping_address = array(
				'country_id' => $this->config->get('config_country_id'),
				'zone_id'    => $this->config->get('config_zone_id')
			);
			$this->payment_address = array(
				'country_id' => $this->config->get('config_country_id'),
				'zone_id'    => $this->config->get('config_zone_id')
			);
		} else {
			$this->store_address = array(
				'country_id' => $this->config->get('config_country_id'),
				'zone_id'    => $this->config->get('config_zone_id')
			);
			$this->shipping_address = array(
				'country_id' => $this->config->get('adv_price_tax_country_id'),
				'zone_id'    => $this->config->get('adv_price_tax_zone_id')
			);
			$this->payment_address = array(
				'country_id' => $this->config->get('adv_price_tax_country_id'),
				'zone_id'    => $this->config->get('adv_price_tax_zone_id')
			);
		}
		
		if ($this->shipping_address) {
			$tax_query = $this->db->query("SELECT tr2.tax_rate_id, tr2.name, tr2.rate, tr2.type, tr1.priority FROM " . DB_PREFIX . "tax_rule tr1 LEFT JOIN " . DB_PREFIX . "tax_rate tr2 ON (tr1.tax_rate_id = tr2.tax_rate_id) INNER JOIN " . DB_PREFIX . "tax_rate_to_customer_group tr2cg ON (tr2.tax_rate_id = tr2cg.tax_rate_id) LEFT JOIN " . DB_PREFIX . "zone_to_geo_zone z2gz ON (tr2.geo_zone_id = z2gz.geo_zone_id) LEFT JOIN " . DB_PREFIX . "geo_zone gz ON (tr2.geo_zone_id = gz.geo_zone_id) WHERE tr1.tax_class_id = '" . (int)$tax_class_id . "' AND tr1.based = 'shipping' AND tr2cg.customer_group_id = '" . (int)$customer_group_id . "' AND z2gz.country_id = '" . (int)$this->shipping_address['country_id'] . "' AND (z2gz.zone_id = '0' OR z2gz.zone_id = '" . (int)$this->shipping_address['zone_id'] . "') ORDER BY tr1.priority ASC");
				
			foreach ($tax_query->rows as $result) {
				$tax_rates[$result['tax_rate_id']] = array(
					'tax_rate_id' => $result['tax_rate_id'],
					'name'        => $result['name'],
					'rate'        => $result['rate'],
					'type'        => $result['type'],
					'priority'    => $result['priority']
				);
			}
		}
		
		if ($this->payment_address) {
			$tax_query = $this->db->query("SELECT tr2.tax_rate_id, tr2.name, tr2.rate, tr2.type, tr1.priority FROM " . DB_PREFIX . "tax_rule tr1 LEFT JOIN " . DB_PREFIX . "tax_rate tr2 ON (tr1.tax_rate_id = tr2.tax_rate_id) INNER JOIN " . DB_PREFIX . "tax_rate_to_customer_group tr2cg ON (tr2.tax_rate_id = tr2cg.tax_rate_id) LEFT JOIN " . DB_PREFIX . "zone_to_geo_zone z2gz ON (tr2.geo_zone_id = z2gz.geo_zone_id) LEFT JOIN " . DB_PREFIX . "geo_zone gz ON (tr2.geo_zone_id = gz.geo_zone_id) WHERE tr1.tax_class_id = '" . (int)$tax_class_id . "' AND tr1.based = 'payment' AND tr2cg.customer_group_id = '" . (int)$customer_group_id . "' AND z2gz.country_id = '" . (int)$this->payment_address['country_id'] . "' AND (z2gz.zone_id = '0' OR z2gz.zone_id = '" . (int)$this->payment_address['zone_id'] . "') ORDER BY tr1.priority ASC");
				
			foreach ($tax_query->rows as $result) {
				$tax_rates[$result['tax_rate_id']] = array(
					'tax_rate_id' => $result['tax_rate_id'],
					'name'        => $result['name'],
					'rate'        => $result['rate'],
					'type'        => $result['type'],
					'priority'    => $result['priority']
				);
			}
		}
		
		if ($this->store_address) {
			$tax_query = $this->db->query("SELECT tr2.tax_rate_id, tr2.name, tr2.rate, tr2.type, tr1.priority FROM " . DB_PREFIX . "tax_rule tr1 LEFT JOIN " . DB_PREFIX . "tax_rate tr2 ON (tr1.tax_rate_id = tr2.tax_rate_id) INNER JOIN " . DB_PREFIX . "tax_rate_to_customer_group tr2cg ON (tr2.tax_rate_id = tr2cg.tax_rate_id) LEFT JOIN " . DB_PREFIX . "zone_to_geo_zone z2gz ON (tr2.geo_zone_id = z2gz.geo_zone_id) LEFT JOIN " . DB_PREFIX . "geo_zone gz ON (tr2.geo_zone_id = gz.geo_zone_id) WHERE tr1.tax_class_id = '" . (int)$tax_class_id . "' AND tr1.based = 'store' AND tr2cg.customer_group_id = '" . (int)$customer_group_id . "' AND z2gz.country_id = '" . (int)$this->store_address['country_id'] . "' AND (z2gz.zone_id = '0' OR z2gz.zone_id = '" . (int)$this->store_address['zone_id'] . "') ORDER BY tr1.priority ASC");
		
			foreach ($tax_query->rows as $result) {
				$tax_rates[$result['tax_rate_id']] = array(
					'tax_rate_id' => $result['tax_rate_id'],
					'name'        => $result['name'],
					'rate'        => $result['rate'],
					'type'        => $result['type'],
					'priority'    => $result['priority']
				);
			}
		}
		
		return $tax_rates;
	}
}
