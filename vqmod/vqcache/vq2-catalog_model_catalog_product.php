<?php

class ModelCatalogProduct extends Model {

	public $is_total_zero = TRUE;


	public function updateViewed($product_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET viewed = (viewed + 1) WHERE product_id = '" . (int)$product_id . "'");
	}

	public function getGiveawayProducts($giveaway_products_id)
	{
		$sql = "SELECT p.product_id, pd.name, p.image FROM " . DB_PREFIX . "product p INNER JOIN " . DB_PREFIX . "product_description pd ON
		p.product_id = pd.product_id WHERE pd.language_id = 1 AND p.product_id IN (". $giveaway_products_id .")";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getProductArticles($product_id) {
		$product_articles = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_article WHERE product_id = '" . (int)$product_id . "'");
		$result = $query->rows;
		
		if($result)
		{
			foreach($result as $row)
			{
				$product_articles[] = $this->getArticleDetails($row['article_id']);
			}
		}

		return $product_articles;
	}

	public function getProductToCategoryPath($product_id)
	{
		$query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int) $product_id . "' Order By category_id LIMIT 1");
		$category_id = $query->row ? $query->row['category_id'] : 0;
		if($category_id)
		{
			$query2 = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_path WHERE category_id = '" . (int) $category_id . "' Order By level");
			if ( $query2->rows )
			{
				$path_array = array();
				foreach($query2->rows as $category)
				{
					$path_array[] = $category['path_id'];
				}
				return implode("_",$path_array);
			}
		}
		return "";
	}
	
	public function getArticleDetails($id)
	{
		$wp_db = new DB('mysqli', 'localhost', 'root', '', 'gempack_blog_prod', '3306');
		$sql = "SELECT * FROM wp_posts WHERE post_status = 'publish' AND post_type = 'post' AND ID = '" . (int)$id . "'";
		$query = $wp_db->query($sql);
		if($query->row)
		{
			$query->row['image'] = $this->getArticleFeaturedImage($id);
		}
		return $query->row;
	}
	
	public function getArticleFeaturedImage($id)
	{
		$wp_db = new DB('mysqli', 'localhost', 'root', '', 'gempack_blog_prod', '3306');
		$sql = "SELECT guid FROM wp_posts WHERE post_type = 'attachment' AND post_parent = '" . (int)$id . "'";
		$query = $wp_db->query($sql);
		if($query->row)
		{
			return $query->row['guid'];
		}
		return 'http://localhost/sourcetree/newgempacked/catalog/view/theme/default/image/no_product.jpg';
	}
	
	//Get product id by product sku
    public function getProductIdBySku($product_sku) {
        $query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product pd WHERE pd.upc =" . $product_sku);
        if (!empty($query->row)) {
            return $query->row['product_id'];
        } else {
            return;
        }
    }

    // Function to query length id respective to their name
    public function getLengthClasses($unit1, $unit2) {
        $this->load->model('catalog/product');
        $query = $this->db->query("SELECT DISTINCT lcd.length_class_id, lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE lcd.unit ='" . $unit1 . "' OR lcd.unit ='" . $unit2 . "'");
        $data = array();
        $i = 0;
        foreach ($query->rows as $query_rows) {
            $data[$i]['name'] = $query_rows['unit'];
            $data[$i]['value'] = $query_rows['length_class_id'];
            $i++;
        }
        return $data;
    }

    // Function to query length id respective to their name
    public function getWeightClasses($unit1, $unit2) {
        $this->load->model('catalog/product');
        $query = $this->db->query("SELECT DISTINCT wcd.weight_class_id, wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE wcd.unit ='" . $unit1 . "' OR wcd.unit ='" . $unit2 . "'");
        $data = array();
        $i = 0;
        foreach ($query->rows as $query_rows) {
            $data[$i]['name'] = $query_rows['unit'];
            $data[$i]['value'] = $query_rows['weight_class_id'];
            $i++;
        }
        return $data;
    }
	
		public function getProductByProductId($product_id,$store_id=NULL) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	
                
                if($store_id==NULL){
                    $store_id=(int)$this->config->get('config_store_id');
                }
				
		$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND customer_group_id = '" . (int)$customer_group_id . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . $store_id . "'");
		
		if ($query->num_rows) {
			$query->row['price'] = ($query->row['discount'] ? $query->row['discount'] : $query->row['price']);
			$query->row['rating'] = (int)$query->row['rating'];
			
			return $query->row;
		} else {
			return false;
		}
	}	



					public function addSearch($search, $customer_id){
					
						// Sanitize the search query before saving it in the database:
						
						$search_analytics_lib = new Search_analytics();
						$search = $search_analytics_lib->sanitize_keyphrases($search);
						
						if (empty($search)) {
							return;
						}
						
						// Enable this if your server is behind a Proxy/Load Balancer
					
						// When OpenCart is deployed behind a load balancer, the Client IP address shows the load balancer's IP address.
						// OpenCart should get the real client IP address looking at the HTTP_X_FORWARDED_FOR instead of REMOTE_ADDR.
					
					/*
						if (!empty($_SERVER['HTTP_CLIENT_IP'])) { 
						
							$ip =  $this->db->escape($this->request->server['HTTP_CLIENT_IP']);
						}
						elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {

							$ip =  $this->db->escape($this->request->server['HTTP_X_FORWARDED_FOR']);
						}
						else {
							$ip =  $this->db->escape($this->request->server['REMOTE_ADDR']);
						}
					*/	
						// Disable this line if the above code is enabled:
						$ip = $this->db->escape($this->request->server['REMOTE_ADDR']);
						
						// Check if the table search_history exists:
						$sql = "SHOW TABLES LIKE '" . DB_PREFIX . "search_history'";
						$result = $this->db->query($sql);
						
						if ($result->num_rows > 0 ) {
						
							$sql = "INSERT INTO " . DB_PREFIX . "search_history(keyphrase, customer_id, ip) VALUES('" . $this->db->escape($search) . "','" . $customer_id . "','" . $this->db->escape($ip) . "')";
							$this->db->query($sql);
						}
					}
				
    public function getProduct($product_id,$visibility = 0) {
        if ($this->customer->isLogged()) {
            $customer_group_id = $this->customer->getCustomerGroupId();
        } else {
            $customer_group_id = $this->config->get('config_customer_group_id');
        }
		
		
        $adminorder=false;
        if(isset($this->request->get['backend'])&&$this->request->get['backend']==1 ){
        	$adminorder=true;
        	$this->session->data['allow_disabled_product'] = true;
        }else{
        	$adminorder=false;
        	$this->session->data['allow_disabled_product'] = false;
        }

        if($adminorder){
        	$allow_disabled=" AND (p.status='1' OR p.status='0')  ";
        }else{
        	$allow_disabled= " AND p.status='1'";
        }

		
		if($visibility == 1){
            $query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int) $customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int) $customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND customer_group_id = '" . (int) $customer_group_id . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int) $this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int) $this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int) $this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int) $product_id . "' AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "' ".$allow_disabled." AND p.pgvisibility != '0' AND p2s.store_id = '" . (int) $this->config->get('config_store_id') . "'");
        } else {
            $query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int) $customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int) $customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND customer_group_id = '" . (int) $customer_group_id . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int) $this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int) $this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int) $this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int) $product_id . "' AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "' ".$allow_disabled." AND p2s.store_id = '" . (int) $this->config->get('config_store_id') . "'");
        }

		if ($query->num_rows) {
			
			$query->row['price'];
			
			// Show first (lowest) option price as "Starting at" price
			// But for product page, to avoid issues with option price update, use a different method
			if ($query->row['price'] == 0) {
				$options = $this->getProductOptions($product_id);
				
				$option_prices = array();
				if ($options) {
					foreach ($options as $j => $option) {
						if (!$option['option_value']) { continue; }
						foreach ($option['option_value'] as $l => $option_value) {
							if (!(float)$option_value['price']) { continue; }
							if ($option_value['price_prefix'] == '-') {
								$option_prices[] = -$option_value['price'];
							} else {
								$option_prices[] = $option_value['price'];
							}
							$options[$j]['option_value'][$l]['price_prefix'] = '';
						}
					}
				}
				if ($option_prices) {
					rsort($option_prices);
					//if (!isset($this->request->get['product_id']) && !isset($this->request->post['product_id'])) {
					//if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH']!='XMLHttpRequest' && !isset($this->request->get['product_id']) && !isset($this->request->post['product_id'])) {
					//if (isset($this->request->get['route']) && $this->request->get['route'] != 'product/product') {
					if ((!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH']!='XMLHttpRequest') && ((isset($this->request->get['route']) && $this->request->get['route'] != 'product/product') || !isset($this->request->get['route']))) {
						$query->row['price'] = reset($option_prices);
					} else {
						$this->session->data['start_at_price'] = $this->tax->calculate($option_prices[0], $query->row['tax_class_id']);
						$this->session->data['start_at_price_ex_tax'] = $option_prices[0];
					}
				}				
			}

			$result = $this->db->query("SELECT * FROM ".DB_PREFIX."estimatedays WHERE product_id='".$query->row['product_id']."' ORDER BY estimate_days");
			$get_estimatedays = '';
			if($result->num_rows > 0){
				$get_estimatedays = $result->rows;
			}else{
				$product_id = 0;
				$result = $this->db->query("SELECT * FROM ".DB_PREFIX."estimatedays WHERE product_id='".(int)$product_id."' ORDER BY estimate_days");
				$get_estimatedays = $result->rows;
			}
			//return $get_estimatedays->rows;

				
			return array(
				'product_id'       => $query->row['product_id'],
				'pgprice_to'      => $query->row['pgprice_to'],
				'pgprice_from'    => $query->row['pgprice_from'],
				//'tag_title'       => $query->row['tag_title'],
				'stock_status_id' => $query->row['stock_status_id'],

				'name'             => $query->row['name'],
				'description'      => $query->row['description'],
				'meta_title'       => $query->row['meta_title'],
				'meta_description' => $query->row['meta_description'],
				'meta_keyword'     => $query->row['meta_keyword'], 'custom_imgtitle' => $query->row['custom_imgtitle'], 'custom_h2' => $query->row['custom_h2'], 'custom_h1' => $query->row['custom_h1'], 'custom_alt' => $query->row['custom_alt'],
				'tag'              => $query->row['tag'],
				'model'            => $query->row['model'],
				'custom_title' => $query->row['custom_title'], 
				'custom_imgtitle' => $query->row['custom_imgtitle'], 
				'custom_h2' => $query->row['custom_h2'], 
				'custom_h1' => $query->row['custom_h1'], 
				'custom_alt' => $query->row['custom_alt'],
				'sku'              => $query->row['sku'],
				'upc'              => $query->row['upc'],
				'ean'              => $query->row['ean'],
				'jan'              => $query->row['jan'],
				'isbn'             => $query->row['isbn'],
				'mpn'              => $query->row['mpn'],
				'location'         => $query->row['location'],
				'quantity'         => $query->row['quantity'],
				'stock_status'     => $query->row['stock_status'],
				'image'            => $query->row['image'],
				'video'            => $query->row['video_link'],
				'poster'            => $query->row['video_p_link'],
				'manufacturer_id'  => $query->row['manufacturer_id'],
				'manufacturer'     => $query->row['manufacturer'],
				'price'            => ($query->row['discount'] ? $query->row['discount'] : $query->row['price']),
				'discounted_price' => $query->row['discount'],
                'orignial_price' => $query->row['price'],
				'special'          => $query->row['special'],
				'reward'           => $query->row['reward'],
				'points'           => $query->row['points'],
				'tax_class_id'     => $query->row['tax_class_id'],
				'date_available'   => $query->row['date_available'],
				'frontend_date_available'   => $query->row['frontend_date_available'],
				'weight'           => $query->row['weight'],
				'weight_class_id'  => $query->row['weight_class_id'],
				'length'           => $query->row['length'],
				'width'            => $query->row['width'],
				'height'           => $query->row['height'],
				'length_class_id'  => $query->row['length_class_id'],
				'subtract'         => $query->row['subtract'],
				'rating'           => round($query->row['rating']),
				'reviews'          => $query->row['reviews'] ? $query->row['reviews'] : 0,
				'minimum'          => $query->row['minimum'],
				'minimum_amount'   => $query->row['minimum_amount'],
				'sort_order'       => $query->row['sort_order'],
				'status'           => $query->row['status'],
				'date_added'       => $query->row['date_added'],
				'date_modified'    => $query->row['date_modified'],
				'viewed'           => $query->row['viewed'],
				'unit_singular'    => $query->row['unit_singular'],
                'unit_plural'      => $query->row['unit_plural'],
				'date_sold_out'    => $query->row['date_sold_out'],
				'date_ordered'    => $query->row['date_ordered'],
				'estimate_deliver_time'    => $query->row['estimate_deliver_time'],
				'estimate_deliver_days'	=> $get_estimatedays,
				'show_product_label_1'	=> $query->row['show_product_label_1'],
				'product_label_text_1'	=> $query->row['product_label_text_1'],
				'unique_price_discount'	=> $query->row['unique_price_discount'],
				'unique_option_price'	=> $query->row['unique_option_price'],
				'labour_cost'			=> $query->row['labour_cost'],
				'default_vendor_unit'	=> $query->row['default_vendor_unit'],
				'show_product_label_2'	=> $query->row['show_product_label_2'],
				'product_label_text_2'	=> $query->row['product_label_text_2'],
			);
		} else {
			return false;
		}
	}
	
		public function getProductsByStoreId($store_id,$data = array()) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	
		
		$sql = "SELECT p.product_id, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special"; 
		
		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";			
			} else {
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			}
		
			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}
		
		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$store_id . "'";
		
		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";	
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";			
			}	
		
			if (!empty($data['filter_filter'])) {
				$implode = array();
				
				$filters = explode(',', $data['filter_filter']);
				
				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}
				
				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";				
			}
		}	

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";
			
			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}
				
				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}
			
			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}
			
			if (!empty($data['filter_tag'])) {
				$sql .= "pd.tag LIKE '%" . $this->db->escape($data['filter_tag']) . "%'";
			}
			
			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}
			
			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}	
			
			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}		

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}
			
			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}		
			
			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}
			
			$sql .= ")";
		}
					
		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}
		
		$sql .= " GROUP BY p.product_id";
		
		$sort_data = array(
			'pd.name',
			'p.model',
			'p.quantity',
			'p.price',
			'rating',
			'p.sort_order',
			'p.date_added'
		);	
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} elseif ($data['sort'] == 'p.price') {
				$sql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";	
		}
		
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
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
		
		$product_data = array();
				
		$query = $this->db->query($sql);
	
		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->getProductByProductId(
            $result['product_id'],$store_id);
		}
		return $product_data;
	}	


	
	
	public function getProducts($data = array()) {
		$sql = "SELECT p.product_id, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.model <> 'grouped' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}

			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
			}
		}

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$implode = array();

                $words = explode(' ', trim(preg_replace('/\s\s+/', ' ', $data['filter_name'])));

                foreach ($words as $word) {
                        $implode[] = "pd.tag LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
                }

                if ($implode) {
                        $sql .= " " . implode(" AND ", $implode) . "";
                }
				//$sql .= "pd.tag LIKE '%" . $this->db->escape($data['filter_tag']) . "%'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'p.product_id',
			'p.model',
			'p.quantity',
			'p.price',
			'p.sold',
			'rating',
			'p.sort_order',
			'p.date_added',
			'p.date_modified'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} elseif ($data['sort'] == 'p.price') {
				$sql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
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

		$product_data = array(); 
		
		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}

		return $product_data;
	}

	public function getProductPath($product_id)
	{
		$sql = "SELECT category_id FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "' LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row ? $query->row['category_id'] : 0;
	}

	public function getProductsForSiteMap($data = array()) {
		$sql = "SELECT p.product_id, pd.name, p.image";

		if (!empty($data['filter_category_id'])) {
				
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
				$sql .= " INNER JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}

		$sql .= " INNER JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		if (!empty($data['filter_category_id'])) {
			
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
		}

		
		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		
		
		$sql .= " ORDER BY p.sort_order ASC";

		$query = $this->db->query($sql);
		
		$product_data = $query->rows;

		return $product_data;
	}

	public function getProductSpecials($data = array()) {
		$sql = "SELECT DISTINCT ps.product_id, (SELECT AVG(rating) FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = ps.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) GROUP BY ps.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'ps.price',
			'rating',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
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

		$product_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}

		return $product_data;
	}

	public function getLatestProducts($limit) {
		$product_data = $this->cache->get('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);

		if (!$product_data) {
			$query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.date_added DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}

			$this->cache->set('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $product_data);
		}

		return $product_data;
	}

	public function getLatestSubCatProducts($subcats) {
		$product_data = array();
		$query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "category_description WHERE name = 'latest'");
		$latest_cat = !empty($query->row) ? $query->row['category_id'] : 999999;

		$subcats = implode(",",$subcats);
		$subcats = rtrim($subcats, ",");

		$query2 = $this->db->query("SELECT a.product_id FROM " . DB_PREFIX . "product_to_category a INNER JOIN " . DB_PREFIX . "product_to_category b ON 
				 a.product_id = b.product_id INNER JOIN " . DB_PREFIX . "product c ON a.product_id = c.product_id WHERE c.status = 1 AND a.category_id = '" . (int)$latest_cat . "' AND b.category_id IN (" . $subcats . ") ORDER BY rand() LIMIT 5");
		
		if ( $query2->rows )
		{
			foreach ($query2->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}
		}
		return $product_data;
	}

	public function getPopularProducts($limit) {
		$product_data = array();

		$query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.viewed DESC, p.date_added DESC LIMIT " . (int)$limit);

		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}

		return $product_data;
	}

	public function getBestSellerProducts($limit) {
		$product_data = $this->cache->get('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);

		if (!$product_data) {
			$product_data = array();

			$query = $this->db->query("SELECT op.product_id, SUM(op.quantity) AS total FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) LEFT JOIN `" . DB_PREFIX . "product` p ON (op.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE o.order_status_id > '0' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' GROUP BY op.product_id ORDER BY total DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}

			$this->cache->set('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $product_data);
		}

		return $product_data;
	}

	public function getProductAttributes($product_id) {
		$product_attribute_group_data = array();
		$product_attribute_group_query = $this->db->query("SELECT ag.attribute_group_id, agd.name FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE pa.product_id = '" . (int)$product_id . "' AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND a.show_on_front = 1 GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name");

		foreach ($product_attribute_group_query->rows as $product_attribute_group) {
			$product_attribute_data = array();
			$product_attribute_query = $this->db->query("SELECT a.attribute_id, ad.name, pa.text FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.product_id = '" . (int)$product_id . "' AND a.attribute_group_id = '" . (int)$product_attribute_group['attribute_group_id'] . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pa.language_id = '" . (int)$this->config->get('config_language_id') . "' AND a.show_on_front = 1 ORDER BY a.sort_order, ad.name");
			foreach ($product_attribute_query->rows as $product_attribute) {
				$product_attribute_data[] = array(
					'attribute_id' => $product_attribute['attribute_id'],
					'name'         => $product_attribute['name'],
					'text'         => $product_attribute['text']
				);
			}

			$product_attribute_group_data[] = array(
				'attribute_group_id' => $product_attribute_group['attribute_group_id'],
				'name'               => $product_attribute_group['name'],
				'attribute'          => $product_attribute_data
			);
		}

		return $product_attribute_group_data;
	}
	
	public function caclOptionPrice($price, $mp) {
        if ($mp > 0) {
            return $price * $mp;
        } else {
            return $price;
        }
    }

	    public function getProductOptions($product_id, $stat = 0) {
        $product_option_data = array();	
		//echo '<br>';
		//echo "SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int) $product_id . "' AND od.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY o.sort_order";
        $product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int) $product_id . "' AND od.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY o.sort_order");

        foreach ($product_option_query->rows as $product_option) {
            if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
                $product_option_value_data = array();

                if($stat == 1){
                    $product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int) $product_id . "' AND pov.product_option_id = '" . (int) $product_option['product_option_id'] . "' AND ovd.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY pov.sort_order");
                } else {
                    $product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int) $product_id . "' AND pov.product_option_id = '" . (int) $product_option['product_option_id'] . "' AND ovd.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY pov.sort_order"); 
                }

                foreach ($product_option_value_query->rows as $product_option_value) {
                    $product_option_value_data[] = array(
					
						'default' => $product_option_value['default'], //Q: Default Product Option
			
                        'product_option_value_id' => $product_option_value['product_option_value_id'],
                        'option_value_id' => $product_option_value['option_value_id'],
                        'name' => $product_option_value['name'],
                        'image' => $product_option_value['image'],
                        'quantity' => $product_option_value['quantity'],
                        'subtract' => $product_option_value['subtract'],
                        'price' => $this->caclOptionPrice($product_option_value['price'], $product_option_value['market_price']),
                        'price_prefix' => $product_option_value['price_prefix'],
                        'weight' => $product_option_value['weight'],
                        'weight_prefix' => $product_option_value['weight_prefix']
                    );
                }

                $product_option_data[] = array(
                    'product_option_id' => $product_option['product_option_id'],
                    'option_id' => $product_option['option_id'],
                    'name' => $product_option['name'],
                    'metal_type' => $product_option['metal_type'],
                    'type' => $product_option['type'],
                    'option_value' => $product_option_value_data,
                    'required' => $product_option['required']
                );
            } else {
                $product_option_data[] = array(
                    'product_option_id' => $product_option['product_option_id'],
                    'option_id' => $product_option['option_id'],
                    'name' => $product_option['name'],
                    'type' => $product_option['type'],
                    'option_value' => $product_option['option_value'],
                    'required' => $product_option['required']
                );
            }
        }
		/*echo '<pre>';
		print_r($product_option_data);
		echo '</pre>';*/
        return $product_option_data;
    }


	public function getProductDiscounts($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND quantity > 0 AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity ASC, priority ASC, price ASC");

		return $query->rows;
	}
	
	 public function getProductDiscountByQuantity($product_id=0,$discount_quantity) {
        if ($this->customer->isLogged()) {
            $customer_group_id = $this->customer->getCustomerGroupId();
        } else {
            $customer_group_id = $this->config->get('config_customer_group_id');
        }
        if($discount_quantity>0 && $discount_quantity<1) {
            $discount_quantity =1;
        }
        
		$sql = "SELECT
	" . DB_PREFIX . "product_discount.price as discount,
	" . DB_PREFIX . "product.price as base_price 

FROM
	" . DB_PREFIX . "product_discount
LEFT JOIN " . DB_PREFIX . "product ON " . DB_PREFIX . "product_discount.product_id = " . DB_PREFIX . "product.product_id
WHERE
	" . DB_PREFIX . "product_discount.product_id = ".$product_id."
AND customer_group_id = $customer_group_id
AND " . DB_PREFIX . "product_discount.quantity <= ".$discount_quantity."
AND (
	(	
		date_start = '0000-00-00'
		OR date_start < NOW()
	)
	AND (
		date_end = '0000-00-00'
		OR date_end > NOW()
	)
)
ORDER BY
	" . DB_PREFIX . "product_discount.quantity DESC,
	" . DB_PREFIX . "product_discount.priority ASC,
	" . DB_PREFIX . "product_discount.price ASC
LIMIT 1";
        $query = $this->db->query($sql);
        return $query->row;
    }


			   public function getFullPath($product_id) {
			   
				  $query = $this->db->query("SELECT COUNT(product_id) AS total, min(category_id) as catid FROM " . DB_PREFIX . "product_to_category  WHERE product_id = '" . (int)$product_id . "' group by product_id");
				  
				  if ($query->rows) { $total = $query->row['total']; }
					else { $total = 0; }
				  
				  if ($total >= 1) {
					 $path = array();
					 $path[0] = $query->row['catid'];
					 
					 $query = $this->db->query("SELECT parent_id AS pid FROM " . DB_PREFIX . "category WHERE category_id = '" . (int)$path[0] . "'");
					
					if ($query->rows) { $parent_id = $query->row['pid']; }
						else { $parent_id = 0; }
					 
					 $i = 1;
					 while($parent_id > 0) {
						$path[$i] = $parent_id;		
						
						$query = $this->db->query("SELECT parent_id AS pid FROM " . DB_PREFIX . "category WHERE category_id = '" . (int)$parent_id . "'");
						$parent_id = $query->row['pid'];
						$i++;
					 }
				  
					 $path = array_reverse($path);
					 					 	  
					 $fullpath = '';
					 
					 foreach($path as $val){
						$fullpath .= '_'.$val;
					 }
				  
					 return ltrim($fullpath, '_');
				  }	else {
					 return false;
				  }
	   }
	public function getProductImages($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}
	
	public function isValidProductOption($product_id, $product_option_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "' AND product_option_id = '" . (int)$product_option_id . "' LIMIT 1");

		return $query->row ? true : false;
	}
	
	public function getValidProductOptionFromDb($product_id, $product_option_value_id) {
		$query = $this->db->query("SELECT option_value_id FROM " . DB_PREFIX . "product_option_value WHERE product_option_value_id = '" . (int)$product_option_value_id . "'");
		
		$row = $query->row;
		
		if($row)
		{
			$option_value_id = $row['option_value_id'];
			$query2 = $this->db->query("SELECT product_option_value_id, product_option_id FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "' AND option_value_id = '" . (int)$option_value_id . "' LIMIT 1");
			return $query2->row;
			
		}
		return false;
	}

	public function getProductRelated($product_id,$mode = 2,$filter_data = array()) {
		$product_data = array();

		$start = 0;
		$limit = 99999999;
		if (!empty($filter_data)) {
			if (isset($filter_data['start'])) {
				$start = $filter_data['start'];
			}

			if (isset($filter_data['limit'])) {
				$limit = $filter_data['limit'];
			}
		}
		if ($mode == 2)
			$sql = "SELECT * FROM " . DB_PREFIX . "product_related pr LEFT JOIN " . DB_PREFIX . "product p ON (pr.related_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pr.product_id = '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT $start,$limit";
		else
			$sql = "SELECT * FROM " . DB_PREFIX . "product_related pr LEFT JOIN " . DB_PREFIX . "product p ON (pr.related_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pr.product_id = '" . (int)$product_id . "' AND pr.is_wwell = '$mode' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT $start,$limit";
		//echo $sql;
		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$product_data[$result['related_id']] = $this->getProduct($result['related_id']);
		}

		return $product_data;
	}

	public function getProductRelatedTotal($product_id,$filter_data = array()) {
		$product_data = array();

		$sql = "SELECT * FROM " . DB_PREFIX . "product_related pr LEFT JOIN " . DB_PREFIX . "product p ON (pr.related_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pr.product_id = '" . (int)$product_id . "'  AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
		//echo $sql;
		$query = $this->db->query($sql);
		return $query->num_rows;
	}

	public function getRelatedProducts($product_id,$mode) {

		$products = array();
		$results = $this->getProductRelated($product_id,$mode);
		$this->load->model('tool/image');

		foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}
				
				if ($price && $result['model'] == 'grouped' && !(float)$result['pgprice_from']) {
					$price = (!$special ? $this->language->get('text_price_start') : $this->language->get('text_price_start_special')) . ' ' . $price;
				} elseif ($price && $result['model'] == 'grouped' && (float)$result['pgprice_from']) {
					$price = $this->language->get('text_price_from') . $this->currency->format($this->tax->calculate($result['pgprice_from'], $result['tax_class_id'], $this->config->get('config_tax')));
					if ((float)$result['pgprice_to']) {
						$price .= $this->language->get('text_price_to') . $this->currency->format($this->tax->calculate($result['pgprice_to'], $result['tax_class_id'], $this->config->get('config_tax')));
					}
				}

				//$this->document->addScript('catalog/view/javascript/responsiveCarousel.js');

				$products[] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'unit'		  => $result['unit_singular'],
					'tax'         => $tax,
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $rating,
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}
			return $products;

	}
	
	public function getVideo($product_id) {
		$query = $this->db->query("SELECT video_link FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
		if ($query->num_rows) {
			return $query->row['video_link'];
		} else {
			return 0;
		}
	}

	public function getVideoPoster($product_id) {
		$query = $this->db->query("SELECT video_p_link FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
		if ($query->num_rows) {
			return $query->row['video_p_link'];
		} else {
			return 0;
		}
	}

	public function getProductLayoutId($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return 0;
		}
	}

	public function getCategories($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

		return $query->rows;
	}

	public function getTotalProducts($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}

			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
			}
		}

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$implode = array();

                $words = explode(' ', trim(preg_replace('/\s\s+/', ' ', $data['filter_name'])));

                foreach ($words as $word) {
                        $implode[] = "pd.tag LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
                }

                if ($implode) {
                        $sql .= " " . implode(" AND ", $implode) . "";
                }
				//$sql .= "pd.tag LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getProfile($product_id, $recurring_id) {
		return $this->db->query("SELECT * FROM `" . DB_PREFIX . "recurring` `p` JOIN `" . DB_PREFIX . "product_recurring` `pp` ON `pp`.`recurring_id` = `p`.`recurring_id` AND `pp`.`product_id` = " . (int)$product_id . " WHERE `pp`.`recurring_id` = " . (int)$recurring_id . " AND `status` = 1 AND `pp`.`customer_group_id` = " . (int)$this->config->get('config_customer_group_id'))->row;
	}

	public function getProfiles($product_id) {
		return array();//$this->db->query("SELECT `pd`.* FROM `" . DB_PREFIX . "product_recurring` `pp` JOIN `" . DB_PREFIX . "recurring_description` `pd` ON `pd`.`language_id` = " . (int)$this->config->get('config_language_id') . " AND `pd`.`recurring_id` = `pp`.`recurring_id` JOIN `" . DB_PREFIX . "recurring` `p` ON `p`.`recurring_id` = `pd`.`recurring_id` WHERE `product_id` = " . (int)$product_id . " AND `status` = 1 AND `customer_group_id` = " . (int)$this->config->get('config_customer_group_id') . " ORDER BY `sort_order` ASC")->rows;
	}

	public function getTotalProductSpecials() {
		$query = $this->db->query("SELECT COUNT(DISTINCT ps.product_id) AS total FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))");

		if (isset($query->row['total'])) {
			return $query->row['total'];
		} else {
			return 0;
		}
	}
	
	 public function getProductDiscountsSingle($product_id){
         if ($this->customer->isLogged()) {
            $customer_group_id = $this->customer->getCustomerGroupId();
        } else {
            $customer_group_id = $this->config->get('config_customer_group_id');
        }

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int) $product_id . "' AND customer_group_id = '" . (int) $customer_group_id . "' AND quantity = 1 AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity ASC, priority ASC, price ASC");

        return $query->row;
    }
public function getTotalQAsByProductIdXML($product_id) {
                    $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "qa qa LEFT JOIN " . DB_PREFIX . "product p ON (qa.product_id = p.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND p.date_available <= NOW() AND p.status = '1' AND qa.status = '1'";

                    if ((int)$this->config->get('qa_display_all_languages') != 1) {
                        $sql .= " AND qa.lang_code = '" . $this->db->escape($this->config->get('config_language')) . "'";
                    }

                    $query = $this->db->query($sql);

                    return $query->row['total'];
                }
    
    public function getOptionMarketValue($option_value){
        $query = "SELECT
                ". DB_PREFIX ."option_value.market_price
                FROM
                ". DB_PREFIX ."option_value ,
                ". DB_PREFIX ."product_option_value
                WHERE
                ". DB_PREFIX ."product_option_value.option_value_id = ". DB_PREFIX ."option_value.option_value_id AND
                ". DB_PREFIX ."product_option_value.product_option_value_id = $option_value ";
        $query2 = $this->db->query($query);
        return $query2->row['market_price'];
    }
    
    /**
     * Get Conversion help Return converting units
     * @param type $product_id
     */
	 public function getGroupedProductName($product_id=null) {
		if($product_id) {
			$query="SELECT name FROM " . DB_PREFIX . "product_description WHERE product_id='".$product_id."'";
			$result= $this->db->query($query);
			
			if(!empty($result)) {
				return $result->row['name'];
			}
		}
	}


	public function getGroupedtotalProducts($product_id=null) {
	if($product_id) {
	$sql = "SELECT COUNT(DISTINCT pct.product_id) AS total  FROM product_concat_temp_table AS pct LEFT JOIN " . DB_PREFIX . "product AS p ON p.product_id = pct.product_id  LEFT JOIN " . DB_PREFIX . "product_description pd ON (pct.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (pct.product_id = p2s.product_id) WHERE pct.groupindicator_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.model <> 'grouped' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

	$query = $this->db->query($sql);
	return $query->row['total'];
	} else {
	return '0';
	}
	}

	public function getGroupedtotalProductsFiltered($data = array()) {
		if($data) {
			$sql = "SELECT COUNT(DISTINCT pct.product_id) AS total  FROM product_concat_temp_table AS pct LEFT JOIN " . DB_PREFIX . "product AS p ON p.product_id = pct.product_id  LEFT JOIN " . DB_PREFIX . "product_description pd ON (pct.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (pct.product_id = p2s.product_id) WHERE pct.groupindicator_id = '" . (int)$data['filter_group_id'] . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.model <> 'grouped' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

			if(isset($data['price']) && !empty($data['price'])){
				$sql .= " AND (p.price >= " . (int)$data['price'] . " AND  p.price <= (SELECT MAX(price) FROM " . DB_PREFIX . "product))"; 
			}

			if(isset($data['price_from']) && $data['price_from'] !== "" && isset($data['price_to']) && $data['price_to'] !== ""){
				$sql .= " AND (p.price >= " . (int)$data['price_from'] . " AND  p.price <= " . (int)$data['price_to'] . ")"; 
				//$sql .= " AND (p.price BETWEEN " . (int)$data['price_from'] . " AND " . (int)$data['price_to'] . " )"; 
			}

			if(isset($data['filter_grouped']) && !empty($data['filter_grouped'])){
				foreach($data['filter_grouped'] as $key => $op) {
					$sql .= " AND (pct.groupbyname = '" . trim($key) ."' AND pct.groupbyvalue = '" . trim($op) . "')";
				}
			}

			if(!empty($data['filter_options'])) {
				foreach($data['filter_options'] as $key => $op) {
					$sql .= " AND (";
						for($i = 1; $i <= 11; $i++) {
							$sql .= "(pct.optionname" . $i ." = '" .  trim($key) . "' AND pct.optionvalue" . $i ." = '" . trim($op) ."')";
							if($i < 11){
								$sql .= " OR "; 
							}
						}
					$sql .= ")"; 
				}
			}	
			
			$query = $this->db->query($sql);
			return $query->row['total'];
		} else {
			return '0';
		}
	}

	public function getGroupedProductsMaxPrice($data = array()) {
		if($data) {
			$sql = "SELECT MAX(p.price) AS price  FROM product_concat_temp_table AS pct LEFT JOIN " . DB_PREFIX . "product AS p ON p.product_id = pct.product_id  LEFT JOIN " . DB_PREFIX . "product_description pd ON (pct.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (pct.product_id = p2s.product_id) WHERE pct.groupindicator_id = '" . (int)$data['filter_group_id'] . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.model <> 'grouped' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
			
			$query = $this->db->query($sql);

			return $query->row['price'];
		} else {
			return '0';
		}
	}

	public function getGroupedProducts($data = array()) {
		$sql = "SELECT pct.*, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = pct.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = pct.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = pct.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special";
	
		$sql .= " FROM product_concat_temp_table AS pct LEFT JOIN " . DB_PREFIX . "product AS p ON p.product_id = pct.product_id";		
	
		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (pct.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (pct.product_id = p2s.product_id) WHERE pct.groupindicator_id = '" . (int)$data['filter_group_id'] . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.model <> 'grouped' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
	
		if(isset($data['price']) && !empty($data['price'])){
			$sql .= " AND (p.price >= " . (int)$data['price'] . " AND  p.price <= (SELECT MAX(price) FROM " . DB_PREFIX . "product))"; 
		}
	
		if(isset($data['price_from']) && $data['price_from'] !== "" && isset($data['price_to']) && $data['price_to'] !== ""){
			$sql .= " AND (p.price >= " . (int)$data['price_from'] . " AND  p.price <= " . (int)$data['price_to'] . ")"; 
		}
	
		if(isset($data['filter_grouped']) && !empty($data['filter_grouped'])){
		foreach($data['filter_grouped'] as $key => $op) {
			$sql .= " AND (pct.groupbyname = '" . trim($key) ."' AND pct.groupbyvalue = '" . trim($op) . "')";
		}
		}
	
		if(!empty($data['filter_options'])) {
		foreach($data['filter_options'] as $key => $op) {
			$sql .= " AND (";
				for($i = 1; $i <= 11; $i++) {
					$sql .= "(pct.optionname" . $i ." = '" .  trim($key) . "' AND pct.optionvalue" . $i ." = '" . trim($op) ."')";
					if($i < 11){
						$sql .= " OR "; 
					}
				}
			$sql .= ")"; 
		}
		}	
	
		$sql .= " GROUP BY pct.product_id";
	
		$sort_data = array(
		'pd.name',
		'pct.product_id',
		'p.model',
		'p.quantity',
		'p.price',
		'p.sold',
		'rating',
		'p.sort_order',
		'p.date_added',
		'p.date_modified'
		);
	
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
		if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
			$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
		} elseif ($data['sort'] == 'p.price') {
			$sql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
		} else {
			$sql .= " ORDER BY " . $data['sort'];
		}
		} else {
		$sql .= " ORDER BY p.sort_order";
		}
	
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
		$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
		$sql .= " ASC, LCASE(pd.name) ASC";
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
	
		$product_data = array(); 
	
		$query = $this->db->query($sql);
	
		foreach ($query->rows as $result) {
		$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		for($i = 1; $i <= 11; $i++){
			$product_data[$result['product_id']]['optionname'.$i] = $result['optionname'.$i];
			$product_data[$result['product_id']]['optionvalue'.$i] = $result['optionvalue'.$i];
		}
		}
	
		return $product_data;
		}
				
	  
	public function getProductOptionName($product_id=null) {
		$options = array();
		if($product_id) {
			$query="SELECT optionvalue1, optionvalue2 FROM product_concat_temp_table WHERE product_id='".$product_id."'";
			$result= $this->db->query($query);
			if( !empty($result->row) )
			{
				if(!empty($result->row['optionvalue1']))
				{
					$options[] = $result->row['optionvalue1'];
				}
				if(!empty($result->row['optionvalue2']))
				{
					$options[] = $result->row['optionvalue2'];
				}
			}
		}

		return $options;
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

	 
    public function getConversionHelp($product_id){
          $query = "SELECT
                    ". DB_PREFIX ."option_value.market_price,
                    ". DB_PREFIX ."option_value_description.`name`
                    FROM
                    ". DB_PREFIX ."option_value ,
                    ". DB_PREFIX ."product_option_value
                    INNER JOIN
                    ". DB_PREFIX ."option_value_description "
                  . "ON ". DB_PREFIX ."product_option_value.option_value_id = ". DB_PREFIX ."option_value_description.option_value_id
                    WHERE
                    ". DB_PREFIX ."product_option_value.option_value_id = ". DB_PREFIX ."option_value.option_value_id AND
                    ". DB_PREFIX ."product_option_value.product_id = $product_id AND ". DB_PREFIX ."option_value_description.`name` LIKE '%\)' GROUP BY ". DB_PREFIX ."option_value_description.`name`
                    ";
            $query2 = $this->db->query($query);
            return $query2->rows;
    }
	
	
	public function getDefaultUnitDetails($product_id){
		  $query = "SELECT ucv.name,ucp.convert_price,ucp.sort_order,ucp.unit_conversion_product_id
				  FROM ". DB_PREFIX ."unit_conversion_product ucp 
				  LEFT JOIN ". DB_PREFIX ."unit_conversion_value ucv on ucp.unit_value_id=ucv.unit_value_id 
				  WHERE ucp.convert_price = 1 AND product_id = '$product_id' order by sort_order LIMIT 0,1";
		  $query2 = $this->db->query($query);
		  return $query2->row;
    }
	
	public function getDefaultUnitName($product_id){
		  $query = "SELECT p.unit_singular,p.unit_plural
				    FROM ". DB_PREFIX ."product p 
				  	WHERE p.product_id = '$product_id' LIMIT 0,1";
		  $query2 = $this->db->query($query);
		  return $query2->row;
    }
	
	public function getunitdataname($products_id){
		//echo "SELECT unit_singular,unit_plural FROM `" . DB_PREFIX . "product` WHERE products_id = '$products_id'";
		$query = $this->db->query("SELECT unit_singular,unit_plural FROM `" . DB_PREFIX . "product` WHERE product_id = '$products_id'");

        return $query->row;
	 }
	 
	 public function getProductCategory($product_id)
	 {
		 $query = $this->db->query("SELECT c.name FROM " . DB_PREFIX . "category_description c INNER JOIN " . DB_PREFIX . "product_to_category p
		 ON c.category_id = p.category_id WHERE p.product_id = '" . (int)$product_id . "' LIMIT 1");
		 
		 return $query->row ? $query->row['name'] : "";
	 }

	public function getGroupProductReviewCount($product_id)
	{
		$review = 0;
		$sql   = "SELECT groupindicator_id FROM product_concat_temp_table WHERE product_id='".$product_id."'";
		$query =  $this->db->query($sql);
		$groupindicator_id = $query->row ? $query->row['groupindicator_id'] : 0;
		$sql   = "SELECT product_id FROM product_concat_temp_table WHERE groupindicator_id='".$groupindicator_id."'";
		$query =  $this->db->query($sql);
		$pid = "";
		if($query->num_rows)
		{
			foreach( $query->rows as $row )
			{
				$pid .= $row['product_id'] . ",";
			}

			$pid = rtrim($pid, ",");

			$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review WHERE product_id IN (" . $pid . ") AND status = '1'";
			$query =  $this->db->query($sql);
			return $query->row ? $query->row['total'] : 0;
		}

		return $review;
	}

	public function getGroupProductQACount($product_id)
	{
		$qa = 0;
		$sql   = "SELECT groupindicator_id FROM product_concat_temp_table WHERE product_id='".$product_id."'";
		$query =  $this->db->query($sql);
		$groupindicator_id = $query->row ? $query->row['groupindicator_id'] : 0;
		$sql   = "SELECT product_id FROM product_concat_temp_table WHERE groupindicator_id='".$groupindicator_id."'";
		$query =  $this->db->query($sql);
		$pid = "";
		if($query->num_rows)
		{
			foreach( $query->rows as $row )
			{
				$pid .= $row['product_id'] . ",";
			}

			$pid = rtrim($pid, ",");

			$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "qa qa LEFT JOIN " . DB_PREFIX . "product p ON (qa.product_id = p.product_id) WHERE p.product_id IN (" . $pid . ") AND p.date_available <= NOW() AND p.status = '1' AND qa.status = '1'";

                    if ((int)$this->config->get('qa_display_all_languages') != 1) {
                        $sql .= " AND qa.lang_code = '" . $this->db->escape($this->config->get('config_language')) . "'";
                    }

                    $query = $this->db->query($sql);

                    return $query->row['total'];
		}

		return $qa;
	}

}
?>
