<?php
class Cart {
	private $data = array();

	public function __construct($registry) {
		$this->config = $registry->get('config');
		$this->customer = $registry->get('customer');
		$this->session = $registry->get('session');
		$this->db = $registry->get('db');
		$this->tax = $registry->get('tax');
		$this->weight = $registry->get('weight');

		// Remove all the expired carts with no customer ID
		$this->db->query("DELETE FROM " . DB_PREFIX . "cart WHERE customer_id = '0' AND date_added < DATE_SUB(NOW(), INTERVAL 1 HOUR)");

		if ($this->customer->getId()) {
			// We want to change the session ID on all the old items in the customers cart
			$this->db->query("UPDATE " . DB_PREFIX . "cart SET session_id = '" . $this->db->escape($this->session->getId()) . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");

			// Once the customer is logged in we want to update the customer ID on all items he has
			$cart_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE customer_id = '0' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");

			foreach ($cart_query->rows as $cart) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "cart WHERE cart_id = '" . (int)$cart['cart_id'] . "'");

				// The advantage of using $this->add is that it will check if the products already exist and increaser the quantity if necessary.
				$this->add($cart['product_id'], $cart['quantity'], json_decode($cart['option']), $cart['recurring_id'], $cart['unit_conversion_values']);			}
		}
	}
	 public function writeLogForOrder($step,$value,$order_id=0){
     	//$this->db->query("DELETE FROM ".DB_PREFIX."order_logs WHERE session_id='".$this->db->escape($this->session->getId())."' AND step ='".$step."' ");
      $this->db->query("INSERT INTO ".DB_PREFIX."order_logs SET step='".$step."',res_values='".$this->db->escape($value)."' ,date_added=NOW(),session_id='".$this->db->escape($this->session->getId())."' ");
      if($order_id>0){
      	$this->db->query("UPDATE ".DB_PREFIX."order_logs SET order_id='".(int)$order_id."' WHERE session_id='".$this->db->escape($this->session->getId())."' AND order_id='0' ");
      }
     }
	  
	  public function getProductTotal($product_id)
	  {
		  $total = 0;
		  $products = $this->getProducts();
		  if($products)
		  {
			  foreach($products as $key => $product)
			  {
				  if($product['product_id'] == $product_id)
				  {
					  $total += $product['total'];
				  }
			  }
		  }
		  
		  return $total;
	  }
	  
	  public function getUnitPriceOfProduct($product_id,$unit_conversion_values,$price)
	  {
		  if(!empty($unit_conversion_values) || $unit_conversion_values != 0){
			  $sql_unit_sql = "SELECT unit_id, unit_value_id, convert_price FROM " . DB_PREFIX . "unit_conversion_product WHERE unit_conversion_product_id = $unit_conversion_values AND product_id = $product_id";
						$res_unit_sql = $this->db->query($sql_unit_sql);
						if($res_unit_sql->num_rows >0){
							$convert_price = $res_unit_sql->row['convert_price'];
							$converted_price = $price * $convert_price;
							return number_format((float)$converted_price, 2, '.', '');
						}
		  }
		  
		  return $price;
	  }

	  public function getLowestCartID()
	  {
		$cart_query = $this->db->query("SELECT cart_id FROM " . DB_PREFIX . "cart WHERE customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "' ORDER BY cart_id ASC LIMIT 1");
		return $cart_query->row ? $cart_query->row['cart_id'] : 0;
	  }
	  
		public function getProducts($limit = 0) {
		if (!empty($this->session->data['pos_cart'])) {
				$products = array();
				$pos_products = array();
				if (!empty($this->session->data['pos_products'])) {
					$pos_products =  $this->session->data['pos_products'];
				} elseif (!empty($this->data['pos_products'])) {
					$pos_products = $this->data['pos_products'];
				}
				
				foreach ($pos_products as $product) {
					if (!empty($product['discount'])) {
						$product['price'] = $product['discount']['discounted_price'];
						$product['tax'] = $product['discount']['discounted_tax'];
						$product['total'] = $product['discount']['discounted_total'];
					}
					$products[] = $product;
				}
				return $products;
			}
			
		$product_data = array();
		$reverse = 0;
		if( $limit > 0 )
		{
			$query_limit = " LIMIT " . $limit;
		} else {
			$query_limit = "";
		}
		$cart_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "' ORDER BY cart_id DESC $query_limit");
		$order_item_sort_order_count = 1;
		foreach ($cart_query->rows as $cart) {
			 if(isset($group_product_id)) {
                    unset($group_product_id);
                }
			$stock = true;
			$product_id = $cart['product_id'];
			$quantity = $cart['quantity'];
			$is_admin = (int)$cart['is_admin'];
			$quantity_supplied = $cart['quantity_supplied'];
            $product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_store p2s LEFT JOIN " . DB_PREFIX . "product p ON (p2s.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND p2s.product_id = '" . (int)$cart['product_id'] . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.date_available <= NOW() AND (p.status = '1' OR p.status='0')");


			if ($product_query->num_rows && ($cart['quantity'] > 0)) {
				$option_price = 0;
				$option_points = 0;
				$option_weight = 0;
				
				
                $group_get_query = $this->db->query("SELECT groupindicator FROM product_concat_temp_table WHERE sku='".$product_query->row['sku']."'");
                if($group_get_query->num_rows) {
                        $group_product_get_query = $this->db->query("SELECT opgi.group_product_id FROM ".DB_PREFIX."product_grouped_indicator opgi INNER JOIN ".DB_PREFIX."product p ON (opgi.group_product_id=p.product_id) WHERE opgi.group_indicator='".$group_get_query->row['groupindicator']."'");
                      if($group_product_get_query->num_rows) {
                           $group_product_id = $group_product_get_query->row['group_product_id'];
                      }
                }                
                if(!isset($group_product_id)) {
                    $group_product_id = $product_id;
                }
				if (!empty($cart['unit_conversion_values'])) {
                   $unit_conversion_values = $cart['unit_conversion_values'];
                } else {
                   $unit_conversion_values = 0;
                }
				
				$unit_data = array();

                if(!empty($unit_conversion_values) || $unit_conversion_values != 0){
					$sql_unit_sql = "SELECT unit_id, unit_value_id, convert_price FROM " . DB_PREFIX . "unit_conversion_product WHERE unit_conversion_product_id = $unit_conversion_values AND product_id = $product_id";
					$res_unit_sql = $this->db->query($sql_unit_sql);
					if($res_unit_sql->num_rows >0){
						$unit_id = $res_unit_sql->row['unit_id'];
						$unit_value_id = $res_unit_sql->row['unit_value_id'];
						$convert_price = $res_unit_sql->row['convert_price'];

						$sql_unit_name = "SELECT name FROM " . DB_PREFIX . "unit_conversion WHERE unit_id = $unit_id";
						$res_unit_name = $this->db->query($sql_unit_name);
						$unit_name = $res_unit_name->row['name'];

						$sql_unit_value_name = "SELECT name FROM " . DB_PREFIX . "unit_conversion_value WHERE unit_value_id = $unit_value_id";
						$res_unit_value_name = $this->db->query($sql_unit_value_name);
						$unit_value_name = $res_unit_value_name->row['name'];

						$unit_data = array(
										'unit_conversion_values' => $unit_conversion_values,
										'unit_name' => $unit_name,
										'unit_value_name' => $unit_value_name,
										'convert_price' => $convert_price
								);
				   }
				}
				
				$option_details=array();
                foreach(json_decode($cart['option']) as $product_option_id => $product_option_value_id){
                    $query = "select price, option_id "
                            . "from ". DB_PREFIX ."product_option_value "
                            . " where product_id = '" . (int) $product_id . "' AND "
                            . " product_option_id = '" . (int) $product_option_id. "' AND "
                            . " product_option_value_id ='" . (int) $product_option_value_id. "'";
                    $result_price = $this->db->query($query);
                    $option_details = $result_price->row;
                }
				/*if (isset($this->session->data['order_id'])) {
                    $sql_order = "SELECT * FROM " . DB_PREFIX . "order_product where product_id = '" . (int) $product_id . "' AND order_id  = '" . (int) $this->session->data['order_id'] . "'";
                    $product_order_query = $this->db->query($sql_order);

                    if ($product_order_query->num_rows == 0) {
                        $quantity_supplied = 0;
                    } else {
                        $quantity_supplied = $product_order_query->row['quantity_supplied'];
                    }
                } else {
                    $quantity_supplied = 0;
                }*/
				$option_data = array();
				
				foreach (json_decode($cart['option']) as $product_option_id => $value) {
					$gp_config_q = $this->db->query("SELECT pg_type FROM " . DB_PREFIX . "product_grouped_type WHERE product_id = '" . (int)$product_id . "' AND pg_type = 'configurable'");
			
					if ($gp_config_q->num_rows) {
						//catalog/model/catalog/product/product.php | public function getProduct($product_id)
						if($this->customer->isLogged()){
							$customer_group_id = $this->customer->getCustomerGroupId(); }else{
							$customer_group_id = $this->config->get('config_customer_group_id');
						}
						
						$getproductby_productoptionid_query = $this->db->query("SELECT DISTINCT p.model, p.price, p.points, p.tax_class_id, p.weight, pd.name AS name, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND customer_group_id = '" . (int)$customer_group_id . "') AS reward FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.product_id = '" . (int)$product_option_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
						
						if((float)$getproductby_productoptionid_query->row['special']){
							$prezzo = $getproductby_productoptionid_query->row['special'];
						}else{
							$prezzo = $getproductby_productoptionid_query->row['price'];
						}
						
						if(!(float)$product_query->row['price']){
							$option_price += $value * $prezzo;
						}
						
						$option_points += $value * $getproductby_productoptionid_query->row['points'];
						
						if(!(float)$product_query->row['weight']){
							$option_weight += $value * $getproductby_productoptionid_query->row['weight'];
						}
						
						$option_data[] = array(
							'product_option_id'       => $product_option_id, //id prodotti acquistati
							'product_option_value_id' => $value, //quantita acquistata dei prodotti
							'option_id'               => '',
							'option_value_id'         => '',
							'name'                    => $value . 'x ' . $getproductby_productoptionid_query->row['name'],
							'value'            		  => $getproductby_productoptionid_query->row['model'],
							'type'                    => 'configurable',//order edit
							'quantity'                => '',
							'subtract'                => '',
							'price'                   => $prezzo, //'', utile per ciascuna tassa
							'tax_class_id'            => $getproductby_productoptionid_query->row['tax_class_id'], //add row per ciascuna tassa
							'price_prefix'            => '',
							'points'                  => $value * $getproductby_productoptionid_query->row['points'],
							'points_prefix'           => '+',
							'weight'                  => '',
							'weight_prefix'           => ''
						);
						
						
					}
				
					
					$option_query = $this->db->query("SELECT po.product_option_id, po.option_id, od.name, o.type FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_option_id = '" . (int)$product_option_id . "' AND po.product_id = '" . (int)$cart['product_id'] . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

					if ($option_query->num_rows) {
						if ($option_query->row['type'] == 'select' || $option_query->row['type'] == 'radio' || $option_query->row['type'] == 'image') {
							$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$value . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

							if ($option_value_query->num_rows) {
								if ($option_value_query->row['price_prefix'] == '+') {
									$option_price += $option_value_query->row['price'];
								} elseif ($option_value_query->row['price_prefix'] == '-') {
									$option_price -= $option_value_query->row['price'];
								}

								if ($option_value_query->row['points_prefix'] == '+') {
									$option_points += $option_value_query->row['points'];
								} elseif ($option_value_query->row['points_prefix'] == '-') {
									$option_points -= $option_value_query->row['points'];
								}

								if ($option_value_query->row['weight_prefix'] == '+') {
									$option_weight += $option_value_query->row['weight'];
								} elseif ($option_value_query->row['weight_prefix'] == '-') {
									$option_weight -= $option_value_query->row['weight'];
								}

								if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $cart['quantity']))) {
									$stock = false;
								}

								$option_data[] = array(
									'product_option_id'       => $product_option_id,
									'product_option_value_id' => $value,
									'option_id'               => $option_query->row['option_id'],
									'option_value_id'         => $option_value_query->row['option_value_id'],
									'name'                    => $option_query->row['name'],
									'value'                   => $option_value_query->row['name'],
									'type'                    => $option_query->row['type'],
									'quantity'                => $option_value_query->row['quantity'],
									'subtract'                => $option_value_query->row['subtract'],
									'price'                   => $option_value_query->row['price'],
									'price_prefix'            => $option_value_query->row['price_prefix'],
									'points'                  => $option_value_query->row['points'],
									'points_prefix'           => $option_value_query->row['points_prefix'],
									'weight'                  => $option_value_query->row['weight'],
									'weight_prefix'           => $option_value_query->row['weight_prefix']
								);
							}
						} elseif ($option_query->row['type'] == 'checkbox' && is_array($value)) {
							foreach ($value as $product_option_value_id) {
								$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

								if ($option_value_query->num_rows) {
									if ($option_value_query->row['price_prefix'] == '+') {
										$option_price += $option_value_query->row['price'];
									} elseif ($option_value_query->row['price_prefix'] == '-') {
										$option_price -= $option_value_query->row['price'];
									}

									if ($option_value_query->row['points_prefix'] == '+') {
										$option_points += $option_value_query->row['points'];
									} elseif ($option_value_query->row['points_prefix'] == '-') {
										$option_points -= $option_value_query->row['points'];
									}

									if ($option_value_query->row['weight_prefix'] == '+') {
										$option_weight += $option_value_query->row['weight'];
									} elseif ($option_value_query->row['weight_prefix'] == '-') {
										$option_weight -= $option_value_query->row['weight'];
									}

									if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $cart['quantity']))) {
										$stock = false;
									}

									$option_data[] = array(
										'product_option_id'       => $product_option_id,
										'product_option_value_id' => $product_option_value_id,
										'option_id'               => $option_query->row['option_id'],
										'option_value_id'         => $option_value_query->row['option_value_id'],
										'name'                    => $option_query->row['name'],
										'value'                   => $option_value_query->row['name'],
										'type'                    => $option_query->row['type'],
										'quantity'                => $option_value_query->row['quantity'],
										'subtract'                => $option_value_query->row['subtract'],
										'price'                   => $option_value_query->row['price'],
										'price_prefix'            => $option_value_query->row['price_prefix'],
										'points'                  => $option_value_query->row['points'],
										'points_prefix'           => $option_value_query->row['points_prefix'],
										'weight'                  => $option_value_query->row['weight'],
										'weight_prefix'           => $option_value_query->row['weight_prefix']
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
								'quantity'                => '',
								'subtract'                => '',
								'price'                   => '',
								'price_prefix'            => '',
								'points'                  => '',
								'points_prefix'           => '',
								'weight'                  => '',
								'weight_prefix'           => ''
							);
						}
					}
				
				}


				$price = $product_query->row['price'];
				
				// Product Discounts
				$discount_quantity = 0;

				$cart_2_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");

				foreach ($cart_2_query->rows as $cart_2) {
					if ($cart_2['product_id'] == $cart['product_id']) {
						$discount_quantity += $cart_2['quantity'];
					}
				}

				if(isset($unit_data) && !empty($unit_data)) {
					$discount_quantity = $discount_quantity * $unit_data['convert_price'];
					if( $discount_quantity < 1 )
					{
						$discount_quantity = 1;
					}
				}

				$product_discount_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$cart['product_id'] . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND quantity <= '" . (int)$discount_quantity . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");

				if ($product_discount_query->num_rows) {
					$price = $product_discount_query->row['price'];
				}

				// Product Specials
				$product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$cart['product_id'] . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");

				if ($product_special_query->num_rows) {
					$price = $product_special_query->row['price'];
				}

				// Reward Points
				$product_reward_query = $this->db->query("SELECT points FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$cart['product_id'] . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'");

				if ($product_reward_query->num_rows) {
					$reward = $product_reward_query->row['points'];
				} else {
					$reward = 0;
				}

				// Downloads
				$download_data = array();

				$download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download p2d LEFT JOIN " . DB_PREFIX . "download d ON (p2d.download_id = d.download_id) LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) WHERE p2d.product_id = '" . (int)$cart['product_id'] . "' AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

				foreach ($download_query->rows as $download) {
					$download_data[] = array(
						'download_id' => $download['download_id'],
						'name'        => $download['name'],
						'filename'    => $download['filename'],
						'mask'        => $download['mask']
					);
				}

				// Stock
				if (!$product_query->row['quantity'] || ($product_query->row['quantity'] < $cart['quantity'])) {
					$stock = false;
				}

				$recurring_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recurring r LEFT JOIN " . DB_PREFIX . "product_recurring pr ON (r.recurring_id = pr.recurring_id) LEFT JOIN " . DB_PREFIX . "recurring_description rd ON (r.recurring_id = rd.recurring_id) WHERE r.recurring_id = '" . (int)$cart['recurring_id'] . "' AND pr.product_id = '" . (int)$cart['product_id'] . "' AND rd.language_id = " . (int)$this->config->get('config_language_id') . " AND r.status = 1 AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'");

				if ($recurring_query->num_rows) {
					$recurring = array(
						'recurring_id'    => $cart['recurring_id'],
						'name'            => $recurring_query->row['name'],
						'frequency'       => $recurring_query->row['frequency'],
						'price'           => $recurring_query->row['price'],
						'cycle'           => $recurring_query->row['cycle'],
						'duration'        => $recurring_query->row['duration'],
						'trial'           => $recurring_query->row['trial_status'],
						'trial_frequency' => $recurring_query->row['trial_frequency'],
						'trial_price'     => $recurring_query->row['trial_price'],
						'trial_cycle'     => $recurring_query->row['trial_cycle'],
						'trial_duration'  => $recurring_query->row['trial_duration']
					);
				} else {
					$recurring = false;
				}
					
				$unit_product_price = $price;					 
							
				    if(isset($unit_data) && !empty($unit_data)) {
					 if($quantity_supplied >= 0 && trim($quantity_supplied) != ''){
						 if($is_admin == 1){
                      	 	 $combinePrice = $this->geProductCalculatedPrice($product_query->row['product_id'],$quantity_supplied,$unit_data['convert_price'],NULL,1);
						 }else{
							 $combinePrice = $this->geProductCalculatedPrice($product_query->row['product_id'],$quantity_supplied,$unit_data['convert_price'],NULL);
						 }
					 }else{
						 $combinePrice = $this->geProductCalculatedPrice($product_query->row['product_id'],$quantity,$unit_data['convert_price']);
					 }
                   }else {
                        if($quantity_supplied >= 0 && trim($quantity_supplied) != ''){
					    	if($is_admin == 1){
								$combinePrice = $this->geProductCalculatedPrice($product_id, $quantity_supplied,null, $option_details,1);
							}else{
								$combinePrice = $this->geProductCalculatedPrice($product_id, $quantity_supplied,null, $option_details);	
							}
					   }else{
						   $combinePrice = $this->geProductCalculatedPrice($product_id, $quantity,null, $option_details);
					   }
                    }
                
                
				if(isset($this->session->data['refreshproducts']))
				{
					$order_item_sort_order = $order_item_sort_order_count;
				}
				elseif(isset($this->session->data['orderquotecart_id'])){
					$ord_data = $this->db->query("SELECT price, order_item_sort_order FROM ".DB_PREFIX."order_product WHERE order_id = '".(int)$this->session->data['orderquotecart_id']."' AND product_id='".(int)$product_id."'");
					if($ord_data->num_rows)
					{
						$combinePrice = $ord_data->row['price'];
						if($ord_data->row['order_item_sort_order'] > 0){
							$reverse = 1;
							$order_item_sort_order = $ord_data->row['order_item_sort_order'];
						}else{
							$order_item_sort_order = $order_item_sort_order_count;
						}
					}else{
						$order_item_sort_order = $order_item_sort_order_count;
					}
				}else{
					$order_item_sort_order = $order_item_sort_order_count;
				}
                
                $combinePrice = $this->roundUp($combinePrice, 2);
			   
				$total = $combinePrice * $quantity;
				if($is_admin == 1){
					if($quantity_supplied >= 0 && trim($quantity_supplied) != ''){
						
						if(isset($unit_data) && !empty($unit_data)) {
							$total = ($combinePrice /$unit_data['convert_price']) * $quantity_supplied;
						}else{
							$total = $combinePrice  * $quantity_supplied;
						}
					}else{
						
						/*if(isset($unit_data) && !empty($unit_data)) {
							$total = ($combinePrice /$unit_data['convert_price']) * $quantity_supplied;
						}else{
							$total = $combinePrice  * $quantity_supplied;
						}	*/
						
					}
				}
				//echo "<pre>"; print_r($product_query->row); echo "</pre>"; exit;
				$product_data[] = array(
					'cart_id'         => $cart['cart_id'],
					'product_id'      => $group_product_id,
					'name'            => $product_query->row['name'],
					'model'           => $product_query->row['model'],
					'location'        => $product_query->row['location'],
					'shipping'        => $product_query->row['shipping'],
					'image'           => $product_query->row['image'],
					'option'          => $option_data,
					'unit' => (isset($unit_data) ? $unit_data : 0),
					'download'        => $download_data,
					'quantity'        => $cart['quantity'],
					'quantity_supplied' => $quantity_supplied,
					'minimum'         => $product_query->row['minimum'],
					'minimum_amount'  => $product_query->row['minimum_amount'],
					'subtract'        => $product_query->row['subtract'],
					'stock'           => $stock,
					'price'           => $combinePrice,
					'total'           => $total,
					'unit_product_price' => $unit_product_price,
					'additional'	  => $this->getWiredProducts(),
					'reward'          => $reward * $cart['quantity'],
					'points'          => ($product_query->row['points'] ? ($product_query->row['points'] + $option_points) * $cart['quantity'] : 0),
					'tax_class_id'    => $product_query->row['tax_class_id'],
					'weight'          => ($product_query->row['weight'] + $option_weight) * $cart['quantity'],
					'weight_class_id' => $product_query->row['weight_class_id'],
					'length'          => $product_query->row['length'],
					'width'           => $product_query->row['width'],
					'height'          => $product_query->row['height'],
					'length_class_id' => $product_query->row['length_class_id'],
					'unique_price_discount' => $product_query->row['unique_price_discount'],
					'labour_cost' 	  => $product_query->row['labour_cost'],
					'unique_option_price' 	=> $product_query->row['unique_option_price'],
					'default_vendor_unit' 	=> $product_query->row['default_vendor_unit'],
					'recurring'       => $recurring,
					'order_item_sort_order' => $order_item_sort_order,
					'is_admin'       => $is_admin
				);
				$order_item_sort_order_count++;
			} else {
				$this->remove($cart['cart_id']);
			}
			
		}
		if($reverse == 1){
			$product_data = array_reverse($product_data);
		}
		//echo "<pre>"; print_r($product_data); echo "</pre>";

		return $product_data;
	}

	public function loadMoreProducts($cart_id, $limit = 0) {
		if (!empty($this->session->data['pos_cart'])) {
				$products = array();
				$pos_products = array();
				if (!empty($this->session->data['pos_products'])) {
					$pos_products =  $this->session->data['pos_products'];
				} elseif (!empty($this->data['pos_products'])) {
					$pos_products = $this->data['pos_products'];
				}
				
				foreach ($pos_products as $product) {
					if (!empty($product['discount'])) {
						$product['price'] = $product['discount']['discounted_price'];
						$product['tax'] = $product['discount']['discounted_tax'];
						$product['total'] = $product['discount']['discounted_total'];
					}
					$products[] = $product;
				}
				return $products;
			}
			
		$product_data = array();
		if( $limit > 0 )
		{
			$query_limit = " LIMIT " . $limit;
		} else {
			$query_limit = "";
		}
		$cart_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "' AND cart_id < '" . (int)$cart_id . "' ORDER BY cart_id DESC $query_limit");

		foreach ($cart_query->rows as $cart) {
			 if(isset($group_product_id)) {
                    unset($group_product_id);
                }
			$stock = true;
			$product_id = $cart['product_id'];
			$quantity = $cart['quantity'];
			$is_admin = (int)$cart['is_admin'];
			$quantity_supplied = $cart['quantity_supplied'];
            $product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_store p2s LEFT JOIN " . DB_PREFIX . "product p ON (p2s.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND p2s.product_id = '" . (int)$cart['product_id'] . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.date_available <= NOW() AND (p.status = '1' OR p.status='0')");


			if ($product_query->num_rows && ($cart['quantity'] > 0)) {
				$option_price = 0;
				$option_points = 0;
				$option_weight = 0;
				
				
                $group_get_query = $this->db->query("SELECT groupindicator FROM product_concat_temp_table WHERE sku='".$product_query->row['sku']."'");
                if($group_get_query->num_rows) {
                        $group_product_get_query = $this->db->query("SELECT opgi.group_product_id FROM ".DB_PREFIX."product_grouped_indicator opgi INNER JOIN ".DB_PREFIX."product p ON (opgi.group_product_id=p.product_id) WHERE opgi.group_indicator='".$group_get_query->row['groupindicator']."'");
                      if($group_product_get_query->num_rows) {
                           $group_product_id = $group_product_get_query->row['group_product_id'];
                      }
                }                
                if(!isset($group_product_id)) {
                    $group_product_id = $product_id;
                }
				if (!empty($cart['unit_conversion_values'])) {
                   $unit_conversion_values = $cart['unit_conversion_values'];
                } else {
                   $unit_conversion_values = 0;
                }
				
				$unit_data = array();

                if(!empty($unit_conversion_values) || $unit_conversion_values != 0){
					$sql_unit_sql = "SELECT unit_id, unit_value_id, convert_price FROM " . DB_PREFIX . "unit_conversion_product WHERE unit_conversion_product_id = $unit_conversion_values AND product_id = $product_id";
					$res_unit_sql = $this->db->query($sql_unit_sql);
					if($res_unit_sql->num_rows >0){
						$unit_id = $res_unit_sql->row['unit_id'];
						$unit_value_id = $res_unit_sql->row['unit_value_id'];
						$convert_price = $res_unit_sql->row['convert_price'];

						$sql_unit_name = "SELECT name FROM " . DB_PREFIX . "unit_conversion WHERE unit_id = $unit_id";
						$res_unit_name = $this->db->query($sql_unit_name);
						$unit_name = $res_unit_name->row['name'];

						$sql_unit_value_name = "SELECT name FROM " . DB_PREFIX . "unit_conversion_value WHERE unit_value_id = $unit_value_id";
						$res_unit_value_name = $this->db->query($sql_unit_value_name);
						$unit_value_name = $res_unit_value_name->row['name'];

						$unit_data = array(
										'unit_conversion_values' => $unit_conversion_values,
										'unit_name' => $unit_name,
										'unit_value_name' => $unit_value_name,
										'convert_price' => $convert_price
								);
				   }
				}
				
				$option_details=array();
                foreach(json_decode($cart['option']) as $product_option_id => $product_option_value_id){
                    $query = "select price, option_id "
                            . "from ". DB_PREFIX ."product_option_value "
                            . " where product_id = '" . (int) $product_id . "' AND "
                            . " product_option_id = '" . (int) $product_option_id. "' AND "
                            . " product_option_value_id ='" . (int) $product_option_value_id. "'";
                    $result_price = $this->db->query($query);
                    $option_details = $result_price->row;
                }
				/*if (isset($this->session->data['order_id'])) {
                    $sql_order = "SELECT * FROM " . DB_PREFIX . "order_product where product_id = '" . (int) $product_id . "' AND order_id  = '" . (int) $this->session->data['order_id'] . "'";
                    $product_order_query = $this->db->query($sql_order);

                    if ($product_order_query->num_rows == 0) {
                        $quantity_supplied = 0;
                    } else {
                        $quantity_supplied = $product_order_query->row['quantity_supplied'];
                    }
                } else {
                    $quantity_supplied = 0;
                }*/
				$option_data = array();
				
				foreach (json_decode($cart['option']) as $product_option_id => $value) {
					$gp_config_q = $this->db->query("SELECT pg_type FROM " . DB_PREFIX . "product_grouped_type WHERE product_id = '" . (int)$product_id . "' AND pg_type = 'configurable'");
			
					if ($gp_config_q->num_rows) {
						//catalog/model/catalog/product/product.php | public function getProduct($product_id)
						if($this->customer->isLogged()){
							$customer_group_id = $this->customer->getCustomerGroupId(); }else{
							$customer_group_id = $this->config->get('config_customer_group_id');
						}
						
						$getproductby_productoptionid_query = $this->db->query("SELECT DISTINCT p.model, p.price, p.points, p.tax_class_id, p.weight, pd.name AS name, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND customer_group_id = '" . (int)$customer_group_id . "') AS reward FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.product_id = '" . (int)$product_option_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
						
						if((float)$getproductby_productoptionid_query->row['special']){
							$prezzo = $getproductby_productoptionid_query->row['special'];
						}else{
							$prezzo = $getproductby_productoptionid_query->row['price'];
						}
						
						if(!(float)$product_query->row['price']){
							$option_price += $value * $prezzo;
						}
						
						$option_points += $value * $getproductby_productoptionid_query->row['points'];
						
						if(!(float)$product_query->row['weight']){
							$option_weight += $value * $getproductby_productoptionid_query->row['weight'];
						}
						
						$option_data[] = array(
							'product_option_id'       => $product_option_id, //id prodotti acquistati
							'product_option_value_id' => $value, //quantita acquistata dei prodotti
							'option_id'               => '',
							'option_value_id'         => '',
							'name'                    => $value . 'x ' . $getproductby_productoptionid_query->row['name'],
							'value'            		  => $getproductby_productoptionid_query->row['model'],
							'type'                    => 'configurable',//order edit
							'quantity'                => '',
							'subtract'                => '',
							'price'                   => $prezzo, //'', utile per ciascuna tassa
							'tax_class_id'            => $getproductby_productoptionid_query->row['tax_class_id'], //add row per ciascuna tassa
							'price_prefix'            => '',
							'points'                  => $value * $getproductby_productoptionid_query->row['points'],
							'points_prefix'           => '+',
							'weight'                  => '',
							'weight_prefix'           => ''
						);
						
						
					}
				
					
					$option_query = $this->db->query("SELECT po.product_option_id, po.option_id, od.name, o.type FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_option_id = '" . (int)$product_option_id . "' AND po.product_id = '" . (int)$cart['product_id'] . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

					if ($option_query->num_rows) {
						if ($option_query->row['type'] == 'select' || $option_query->row['type'] == 'radio' || $option_query->row['type'] == 'image') {
							$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$value . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

							if ($option_value_query->num_rows) {
								if ($option_value_query->row['price_prefix'] == '+') {
									$option_price += $option_value_query->row['price'];
								} elseif ($option_value_query->row['price_prefix'] == '-') {
									$option_price -= $option_value_query->row['price'];
								}

								if ($option_value_query->row['points_prefix'] == '+') {
									$option_points += $option_value_query->row['points'];
								} elseif ($option_value_query->row['points_prefix'] == '-') {
									$option_points -= $option_value_query->row['points'];
								}

								if ($option_value_query->row['weight_prefix'] == '+') {
									$option_weight += $option_value_query->row['weight'];
								} elseif ($option_value_query->row['weight_prefix'] == '-') {
									$option_weight -= $option_value_query->row['weight'];
								}

								if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $cart['quantity']))) {
									$stock = false;
								}

								$option_data[] = array(
									'product_option_id'       => $product_option_id,
									'product_option_value_id' => $value,
									'option_id'               => $option_query->row['option_id'],
									'option_value_id'         => $option_value_query->row['option_value_id'],
									'name'                    => $option_query->row['name'],
									'value'                   => $option_value_query->row['name'],
									'type'                    => $option_query->row['type'],
									'quantity'                => $option_value_query->row['quantity'],
									'subtract'                => $option_value_query->row['subtract'],
									'price'                   => $option_value_query->row['price'],
									'price_prefix'            => $option_value_query->row['price_prefix'],
									'points'                  => $option_value_query->row['points'],
									'points_prefix'           => $option_value_query->row['points_prefix'],
									'weight'                  => $option_value_query->row['weight'],
									'weight_prefix'           => $option_value_query->row['weight_prefix']
								);
							}
						} elseif ($option_query->row['type'] == 'checkbox' && is_array($value)) {
							foreach ($value as $product_option_value_id) {
								$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

								if ($option_value_query->num_rows) {
									if ($option_value_query->row['price_prefix'] == '+') {
										$option_price += $option_value_query->row['price'];
									} elseif ($option_value_query->row['price_prefix'] == '-') {
										$option_price -= $option_value_query->row['price'];
									}

									if ($option_value_query->row['points_prefix'] == '+') {
										$option_points += $option_value_query->row['points'];
									} elseif ($option_value_query->row['points_prefix'] == '-') {
										$option_points -= $option_value_query->row['points'];
									}

									if ($option_value_query->row['weight_prefix'] == '+') {
										$option_weight += $option_value_query->row['weight'];
									} elseif ($option_value_query->row['weight_prefix'] == '-') {
										$option_weight -= $option_value_query->row['weight'];
									}

									if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $cart['quantity']))) {
										$stock = false;
									}

									$option_data[] = array(
										'product_option_id'       => $product_option_id,
										'product_option_value_id' => $product_option_value_id,
										'option_id'               => $option_query->row['option_id'],
										'option_value_id'         => $option_value_query->row['option_value_id'],
										'name'                    => $option_query->row['name'],
										'value'                   => $option_value_query->row['name'],
										'type'                    => $option_query->row['type'],
										'quantity'                => $option_value_query->row['quantity'],
										'subtract'                => $option_value_query->row['subtract'],
										'price'                   => $option_value_query->row['price'],
										'price_prefix'            => $option_value_query->row['price_prefix'],
										'points'                  => $option_value_query->row['points'],
										'points_prefix'           => $option_value_query->row['points_prefix'],
										'weight'                  => $option_value_query->row['weight'],
										'weight_prefix'           => $option_value_query->row['weight_prefix']
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
								'quantity'                => '',
								'subtract'                => '',
								'price'                   => '',
								'price_prefix'            => '',
								'points'                  => '',
								'points_prefix'           => '',
								'weight'                  => '',
								'weight_prefix'           => ''
							);
						}
					}
				
				}


				$price = $product_query->row['price'];
				
				// Product Discounts
				$discount_quantity = 0;

				$cart_2_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");

				foreach ($cart_2_query->rows as $cart_2) {
					if ($cart_2['product_id'] == $cart['product_id']) {
						$discount_quantity += $cart_2['quantity'];
					}
				}

				if(isset($unit_data) && !empty($unit_data)) {
					$discount_quantity = $discount_quantity * $unit_data['convert_price'];
					if( $discount_quantity < 1 )
					{
						$discount_quantity = 1;
					}
				}

				$product_discount_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$cart['product_id'] . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND quantity <= '" . (int)$discount_quantity . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");

				if ($product_discount_query->num_rows) {
					$price = $product_discount_query->row['price'];
				}

				// Product Specials
				$product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$cart['product_id'] . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");

				if ($product_special_query->num_rows) {
					$price = $product_special_query->row['price'];
				}

				// Reward Points
				$product_reward_query = $this->db->query("SELECT points FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$cart['product_id'] . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'");

				if ($product_reward_query->num_rows) {
					$reward = $product_reward_query->row['points'];
				} else {
					$reward = 0;
				}

				// Downloads
				$download_data = array();

				$download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download p2d LEFT JOIN " . DB_PREFIX . "download d ON (p2d.download_id = d.download_id) LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) WHERE p2d.product_id = '" . (int)$cart['product_id'] . "' AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

				foreach ($download_query->rows as $download) {
					$download_data[] = array(
						'download_id' => $download['download_id'],
						'name'        => $download['name'],
						'filename'    => $download['filename'],
						'mask'        => $download['mask']
					);
				}

				// Stock
				if (!$product_query->row['quantity'] || ($product_query->row['quantity'] < $cart['quantity'])) {
					$stock = false;
				}

				$recurring_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recurring r LEFT JOIN " . DB_PREFIX . "product_recurring pr ON (r.recurring_id = pr.recurring_id) LEFT JOIN " . DB_PREFIX . "recurring_description rd ON (r.recurring_id = rd.recurring_id) WHERE r.recurring_id = '" . (int)$cart['recurring_id'] . "' AND pr.product_id = '" . (int)$cart['product_id'] . "' AND rd.language_id = " . (int)$this->config->get('config_language_id') . " AND r.status = 1 AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'");

				if ($recurring_query->num_rows) {
					$recurring = array(
						'recurring_id'    => $cart['recurring_id'],
						'name'            => $recurring_query->row['name'],
						'frequency'       => $recurring_query->row['frequency'],
						'price'           => $recurring_query->row['price'],
						'cycle'           => $recurring_query->row['cycle'],
						'duration'        => $recurring_query->row['duration'],
						'trial'           => $recurring_query->row['trial_status'],
						'trial_frequency' => $recurring_query->row['trial_frequency'],
						'trial_price'     => $recurring_query->row['trial_price'],
						'trial_cycle'     => $recurring_query->row['trial_cycle'],
						'trial_duration'  => $recurring_query->row['trial_duration']
					);
				} else {
					$recurring = false;
				}
					
				$unit_product_price = $price;					 
							
				    if(isset($unit_data) && !empty($unit_data)) {
					 if($quantity_supplied >= 0 && trim($quantity_supplied) != ''){
						 if($is_admin == 1){
                      	 	 $combinePrice = $this->geProductCalculatedPrice($product_query->row['product_id'],$quantity_supplied,$unit_data['convert_price'],NULL,1);
						 }else{
							 $combinePrice = $this->geProductCalculatedPrice($product_query->row['product_id'],$quantity_supplied,$unit_data['convert_price'],NULL);
						 }
					 }else{
						 $combinePrice = $this->geProductCalculatedPrice($product_query->row['product_id'],$quantity,$unit_data['convert_price']);
					 }
                   }else {
                        if($quantity_supplied >= 0 && trim($quantity_supplied) != ''){
					    	if($is_admin == 1){
								$combinePrice = $this->geProductCalculatedPrice($product_id, $quantity_supplied,null, $option_details,1);
							}else{
								$combinePrice = $this->geProductCalculatedPrice($product_id, $quantity_supplied,null, $option_details);	
							}
					   }else{
						   $combinePrice = $this->geProductCalculatedPrice($product_id, $quantity,null, $option_details);
					   }
                    }
                
                
				if(isset($this->session->data['refreshproducts']))
				{
					
				}
				elseif(isset($this->session->data['orderquotecart_id'])){
					$ord_data = $this->db->query("SELECT price FROM ".DB_PREFIX."order_product WHERE order_id = '".(int)$this->session->data['orderquotecart_id']."' AND product_id='".(int)$product_id."'");
					if($ord_data->num_rows)
					{
						$combinePrice = $ord_data->row['price'];
					}
				}
                
                $combinePrice = $this->roundUp($combinePrice, 2);
			   
				$total = $combinePrice * $quantity;
				if($is_admin == 1){
					if($quantity_supplied >= 0 && trim($quantity_supplied) != ''){
						
						if(isset($unit_data) && !empty($unit_data)) {
							$total = ($combinePrice /$unit_data['convert_price']) * $quantity_supplied;
						}else{
							$total = $combinePrice  * $quantity_supplied;
						}
					}else{
						
						/*if(isset($unit_data) && !empty($unit_data)) {
							$total = ($combinePrice /$unit_data['convert_price']) * $quantity_supplied;
						}else{
							$total = $combinePrice  * $quantity_supplied;
						}	*/
						
					}
				}
				//echo "<pre>"; print_r($product_query->row); echo "</pre>"; exit;
				$product_data[] = array(
					'cart_id'         => $cart['cart_id'],
					'product_id'      => $group_product_id,
					'name'            => $product_query->row['name'],
					'model'           => $product_query->row['model'],
					'location'        => $product_query->row['location'],
					'shipping'        => $product_query->row['shipping'],
					'image'           => $product_query->row['image'],
					'option'          => $option_data,
					'unit' => (isset($unit_data) ? $unit_data : 0),
					'download'        => $download_data,
					'quantity'        => $cart['quantity'],
					'quantity_supplied' => $quantity_supplied,
					'minimum'         => $product_query->row['minimum'],
					'minimum_amount'  => $product_query->row['minimum_amount'],
					'subtract'        => $product_query->row['subtract'],
					'stock'           => $stock,
					'price'           => $combinePrice,
					'total'           => $total,
					'unit_product_price' => $unit_product_price,
					'additional'	  => $this->getWiredProducts(),
					'reward'          => $reward * $cart['quantity'],
					'points'          => ($product_query->row['points'] ? ($product_query->row['points'] + $option_points) * $cart['quantity'] : 0),
					'tax_class_id'    => $product_query->row['tax_class_id'],
					'weight'          => ($product_query->row['weight'] + $option_weight) * $cart['quantity'],
					'weight_class_id' => $product_query->row['weight_class_id'],
					'length'          => $product_query->row['length'],
					'width'           => $product_query->row['width'],
					'height'          => $product_query->row['height'],
					'length_class_id' => $product_query->row['length_class_id'],
					'unique_price_discount' => $product_query->row['unique_price_discount'],
					'labour_cost' 	  => $product_query->row['labour_cost'],
					'unique_option_price' 	=> $product_query->row['unique_option_price'],
					'default_vendor_unit' 	=> $product_query->row['default_vendor_unit'],
					'recurring'       => $recurring,
					'is_admin'       => $is_admin
				);
			} else {
				$this->remove($cart['cart_id']);
			}
		}
		
		//echo "<pre>"; print_r($product_data); echo "</pre>";

		return $product_data;
	}
	
		public function getWiredProducts(){
		$pricevalue=0;
		$producttotal=0;
		$cart_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");
		foreach ($cart_query->rows as $product) {
			
			
			if(null!==$this->config->get('subcategorypercentage_category')){
				$categories=implode(",",$this->config->get('subcategorypercentage_category'));
			}else{
				$categories=0;
			}

			 $getWiredProducts=$this->db->query("SELECT * FROM ".DB_PREFIX."product_to_category WHERE product_id='".$product['product_id']."' AND category_id IN(".$categories.") ");
				 
					 if($getWiredProducts->num_rows){
						$priceval=$this->db->query("SELECT price from ".DB_PREFIX."product WHERE product_id='".$product['product_id']."' ");
						if($priceval->num_rows){
							$pricevalue += $priceval->row['price']*$product['quantity'];
						}
						
					 }
		}

				if($pricevalue){
					  if(null!==$this->config->get('config_additional_percentage')){
							$additional_percentage= $this->config->get('config_additional_percentage')/100;
							$total_val=$pricevalue*$additional_percentage;
						 }
						
					 if(isset($total_val)){
						$producttotal=$total_val;
							 }
						}
						
						return $producttotal;
	}


	public function add($product_id, $quantity = 1, $option = array(), $recurring_id = 0,$unit_conversion_values = 0,$qty_shipped = NULL,$is_admin = 0) {
		//echo "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "cart WHERE customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "' AND product_id = '" . (int)$product_id . "' AND recurring_id = '" . (int)$recurring_id . "' AND `option` = '" . $this->db->escape(json_encode($option)) . "' AND unit_conversion_values = '" . (int)$unit_conversion_values . "'";
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "cart WHERE customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "' AND product_id = '" . (int)$product_id . "' AND recurring_id = '" . (int)$recurring_id . "' AND `option` = '" . $this->db->escape(json_encode($option)) . "' AND unit_conversion_values = '" . (int)$unit_conversion_values . "' AND is_admin = '" . (int)$is_admin . "'");

		if($qty_shipped != NULL){
			if (!$query->row['total']) {
				$this->db->query("INSERT " . DB_PREFIX . "cart SET customer_id = '" . (int)$this->customer->getId() . "', session_id = '" . $this->db->escape($this->session->getId()) . "', product_id = '" . (int)$product_id . "', recurring_id = '" . (int)$recurring_id . "', `option` = '" . $this->db->escape(json_encode($option)) . "', unit_conversion_values = '" .(int)$unit_conversion_values. "', quantity = '" . (float)$quantity . "',quantity_supplied = '" . (float)$qty_shipped . "',is_admin = '" . (int)$is_admin . "', date_added = NOW()");
			} else {
				$this->db->query("UPDATE " . DB_PREFIX . "cart SET quantity = (quantity + " . (float)$quantity . ") WHERE customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "' AND product_id = '" . (int)$product_id . "' AND recurring_id = '" . (int)$recurring_id . "' AND `option` = '" . $this->db->escape(json_encode($option)) . "' AND quantity_supplied = '" . (float)$qty_shipped . "' AND is_admin = '" . (int)$is_admin . "'");
			}
		}else{
			if (!$query->row['total']) {
				$this->db->query("INSERT " . DB_PREFIX . "cart SET customer_id = '" . (int)$this->customer->getId() . "', session_id = '" . $this->db->escape($this->session->getId()) . "', product_id = '" . (int)$product_id . "', recurring_id = '" . (int)$recurring_id . "', `option` = '" . $this->db->escape(json_encode($option)) . "', unit_conversion_values = '" .(int)$unit_conversion_values. "', quantity = '" . (float)$quantity . "',is_admin = '" . (int)$is_admin . "', date_added = NOW()");
			} else {
				$this->db->query("UPDATE " . DB_PREFIX . "cart SET quantity = (quantity + " . (float)$quantity . ") WHERE customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "' AND product_id = '" . (int)$product_id . "' AND recurring_id = '" . (int)$recurring_id . "' AND `option` = '" . $this->db->escape(json_encode($option)) . "' AND is_admin = '" . (int)$is_admin . "'");
			}
		}
	}

	public function clearCustomerCart(){
		if($this->customer->isLogged()){
              $this->db->query("DELETE FROM ".DB_PREFIX."cart WHERE customer_id='".$this->customer->getId()."' AND session_id='".$this->session->getId()."' AND is_admin='1' ");
		       }
	}

	public function update($cart_id, $quantity,$unit_conversion_values = 0) {
		$this->db->query("UPDATE " . DB_PREFIX . "cart SET quantity = '" . (int)$quantity . "', unit_conversion_values = '" .(int)$unit_conversion_values. "' WHERE cart_id = '" . (int)$cart_id . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");
	}

	public function remove($cart_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "cart WHERE cart_id = '" . (int)$cart_id . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");
	}

	public function clear() {
		$this->db->query("DELETE FROM " . DB_PREFIX . "cart WHERE customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");
	}

	public function getRecurringProducts() {
		$product_data = array();

		foreach ($this->getProducts() as $value) {
			if ($value['recurring']) {
				$product_data[] = $value;
			}
		}

		return $product_data;
	}

	public function getWeight() {
		$weight = 0;

		foreach ($this->getProducts() as $product) {
			if ($product['shipping']) {
				$weight += $this->weight->convert($product['weight'], $product['weight_class_id'], $this->config->get('config_weight_class_id'));
			}
		}

		return $weight;
	}

	public function getSubTotal() {
		$total = 0;

		foreach ($this->getProducts() as $product) {
			$total += $product['total'];
		}

		return $total;
	}

	public function getTaxes() {
		$tax_data = array();

		foreach ($this->getProducts() as $product) {
			
			//$gp_config_q = $this->db->query("SELECT pg_type FROM " . DB_PREFIX . "product_grouped_type WHERE product_id = '" . (int)$product['product_id'] . "' AND pg_type = 'configurable'"); if($gp_config_q->num_rows){ // && !(float)$product['price']
			
			$gp_config_q = $this->db->query("SELECT p.price FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_grouped_type pgt ON (p.product_id = pgt.product_id) WHERE p.price <= '0' AND pgt.product_id = '" . (int)$product['product_id'] . "' AND pgt.pg_type = 'configurable'");
			
			if($gp_config_q->num_rows){
				foreach ($product['option'] as $gp_option) if($gp_option['tax_class_id']){
					$tax_rates = $this->tax->getRates($gp_option['price'], $gp_option['tax_class_id']);
					
					foreach($tax_rates as $tax_rate){
						if(!isset($tax_data[$tax_rate['tax_rate_id']])){
							$tax_data[$tax_rate['tax_rate_id']] = ($tax_rate['amount'] * $gp_option['product_option_value_id'] * $product['quantity']);
						}else{
							$tax_data[$tax_rate['tax_rate_id']] += ($tax_rate['amount'] * $gp_option['product_option_value_id'] * $product['quantity']);
						}
					}
				}
			} else
			//print_r($product);
			if ($product['tax_class_id']) {
				//echo $product['price'];
				if(!empty($product['unit'])){
					if(isset($product['quantity_supplied']) && $product['quantity_supplied'] > 0){
						$tax_rates = $this->tax->getRates(($product['price']/$product['unit']['convert_price']), $product['tax_class_id']);
					}else{
						$tax_rates = $this->tax->getRates($product['price'], $product['tax_class_id']);	
					}
				}else{
					$tax_rates = $this->tax->getRates($product['price'], $product['tax_class_id']);
				}//print_r($product);
				foreach ($tax_rates as $tax_rate) {
					if (!isset($tax_data[$tax_rate['tax_rate_id']])) {
						
						if((isset($product['quantity_supplied']) && $product['quantity_supplied'] > 0) || $product['is_admin'] == 1){
							$tax_data[$tax_rate['tax_rate_id']] = ($tax_rate['amount'] * $product['quantity_supplied']);
						}else{
							$tax_data[$tax_rate['tax_rate_id']] = ($tax_rate['amount'] * $product['quantity']);
						}
					} else {
						if((isset($product['quantity_supplied']) && $product['quantity_supplied'] > 0) || $product['is_admin'] == 1){
							$tax_data[$tax_rate['tax_rate_id']] += ($tax_rate['amount'] * $product['quantity_supplied']);
						}else{
							$tax_data[$tax_rate['tax_rate_id']] += ($tax_rate['amount'] * $product['quantity']);
						}
						//$tax_data[$tax_rate['tax_rate_id']] += ($tax_rate['amount'] * $product['quantity']);
					}
				}
			}
		}

		return $tax_data;
	}

	public function getTotal() {
		$total = 0;

		foreach ($this->getProducts() as $product) {
			$total += $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'];
		}

		return $total;
	}

	public function countProducts() {
		$product_total = 0;

		$products = $this->getProducts();

		foreach ($products as $product) {
            $product_total += 1;
		}

		return $product_total;
	}

	public function hasProducts() {
		return count($this->getProducts());
	}

	public function hasRecurringProducts() {
		return count($this->getRecurringProducts());
	}

	public function hasStock() {
		$stock = true;

		foreach ($this->getProducts() as $product) {
			if (!$product['stock']) {
				$stock = false;
			}
		}

		return $stock;
	}

	public function hasShipping() {
		$shipping = false;

		foreach ($this->getProducts() as $product) {
			if ($product['shipping']) {
				$shipping = true;

				break;
			}
		}

		return $shipping;
	}

	public function hasDownload() {
		$download = false;

		foreach ($this->getProducts() as $product) {
			if ($product['download']) {
				$download = true;

				break;
			}
		}

		return $download;
	}
	
    public function getDiscountPercent($discount_quantity=null, $product_id) {
        $customer_group_id=1;
        
        if(isset($this->session->data['order_id'])){
		$checkCustomerGroup=$this->db->query("SELECT customer_id,customer_group_id FROM ".DB_PREFIX."order WHERE order_id='".$this->session->data['order_id']."' ");
		if($checkCustomerGroup->num_rows){
			if($checkCustomerGroup->row['customer_id']==0&&$checkCustomerGroup->row['customer_group_id']==0){
				$this->config->set('customer_group_id',1);
				
			}else{
				 if ($this->customer->isLogged()) {
				            $customer_group_id = $this->customer->getCustomerGroupId();
				        } else {
				            $customer_group_id = $this->config->get('config_customer_group_id');
				        }

			}
		}
	}else{
				 if ($this->customer->isLogged()) {
				            $customer_group_id = $this->customer->getCustomerGroupId();
				        } else {
				            $customer_group_id = $this->config->get('config_customer_group_id');
				        }

	}
	
        if(is_null($discount_quantity) && empty($discount_quantity)) {
            $discount_quantity=1;
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
                " . DB_PREFIX . "product_discount.product_id = " . $product_id . "
            AND customer_group_id = ".$customer_group_id."
            AND " . DB_PREFIX . "product_discount.quantity <= " . $discount_quantity . "
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
        $discount_result = $query->row;
        if (isset($discount_result['discount'])) {
            $discount_result['base_price']=($discount_result['base_price']==0)?1:$discount_result['base_price'];
            return ($discount_result['discount'] / $discount_result['base_price']);
        } else {
            return 1;
        }
    }

    /**
     * 
     * @param type $pid
     * @param type $quantity
     * @param type $unit_mult
     * @return type
     */
    public function geProductCalculatedPrice($product_id, $quantity = 1, $unit_mult = 1, $option_details=null,$is_admin=0) {
        $base_price = $this->getMainPrice($product_id);
        if(!empty($unit_mult) && !is_null($unit_mult)) {
			
            if($is_admin==0){
            	$discount_qty = $quantity * $unit_mult;
			}else{
				$discount_qty = $quantity;	
			}
			$discount_multiplier = $this->getDiscountPercent($discount_qty, $product_id);
            $final_price = $discount_multiplier * $base_price * $unit_mult;
        } else {
            $discount_multiplier = $this->getDiscountPercent($quantity, $product_id);
            $final_price = $discount_multiplier * $base_price ;
           
        };
        if($option_details !=NULL && !empty($option_details) && $option_details['option_id'] !=5) {
            $final_price = $final_price +$option_details['price'];
        }
        return $final_price;
    }
    /**
     * Function geProductCalculatedPrice
     * @param type $pid
     * @return type
     */
    public function geProductCalculatedPriceWithouDiscount($pid, $quantity = null) {
        $opional_price = $this->getOptionPrice($pid);
        $discounts = $this->getProductDiscountsSingle($pid, $quantity);
        $main_price = $this->getMainPrice($pid);
        if ($main_price == 0) {
            $opional_price = $this->getOptionPrice($pid, 0);
            $opional_price_market = $this->getOptionPrice_market($pid);
            $opional_price = $opional_price * $opional_price_market;
            $rPrice = $opional_price;
            return round($rPrice, 2);
        }
        $main_price == 0 ? $main_price = 1 : '';
        $discounts = $this->getProductDiscountsSingle($pid, $quantity);
        $discount_price = (isset($discounts['price'])) ? $discounts['price'] : $main_price;
        $final_price = $main_price + $opional_price;
        return round($final_price, 2);
    }

    private function getOptionPrice_market($pid){
        $get_option = $this->db->query("SELECT `market_price` FROM `oc_option_value` WHERE `option_id` = '5' LIMIT 1");
        return $get_option->row['market_price'];
    }
    /**
     * Function getOptionPrice
     * @param type $pid
     * @return boolean
     */
    public function getOptionPrice($pid, $metal_type = 1, $option_details=null) {
        if($option_details!=null && $option_details['option_id'] == '20'){
            return $option_details['price'];
        }else{
        $single = "SELECT
                " . DB_PREFIX . "product_option_value.price,
               " . DB_PREFIX . "option_value.market_price,
               (" . DB_PREFIX . "product_option_value.price*" . DB_PREFIX . "option_value.market_price ) as option_increment
               FROM
               " . DB_PREFIX . "option_description
               INNER JOIN " . DB_PREFIX . "option_value ON " . DB_PREFIX . "option_description.option_id = " . DB_PREFIX . "option_value.option_id
               INNER JOIN " . DB_PREFIX . "product_option_value ON " . DB_PREFIX . "product_option_value.option_id = " . DB_PREFIX . "option_value.option_id AND " . DB_PREFIX . "option_value.option_value_id = " . DB_PREFIX . "product_option_value.option_value_id
               WHERE
               " . DB_PREFIX . "product_option_value.product_id = $pid ";
        if ($metal_type) {
            $single.= "AND
               " . DB_PREFIX . "option_description.metal_type = 1";
        }
       
        $query = $this->db->query($single);
        if (!$metal_type) {
            if (!empty($query->row)) {
                return $query->row['price'];
            } else {
                return 0;
            }
        }
        if (isset($query->row['option_increment'])) {
            $calculatedPrice = $query->row['option_increment'];
        } else {
            if (isset($query->row['price'])) {
                $calculatedPrice = $query->row['price'];
            } else {
                $calculatedPrice = 0;
            }
        }
        return $calculatedPrice;
        }
    }

    /**
     * getMainPrice
     * @param type $pid
     * @return type
     */
    public function getMainPrice($pid) {
        $main_price = $this->db->query("select " . DB_PREFIX . "product.price from " . DB_PREFIX . "product where " . DB_PREFIX . "product.product_id = $pid");
        return $main_price->row['price'];
    }

    /**
     * getProductDiscountsSingle
     * @param type $product_id
     * @return type
     */
    public function getProductDiscountsSingle($product_id, $quantity = 1) {
        if ($this->customer->isLogged()) {
            $customer_group_id = $this->customer->getCustomerGroupId();
        } else {
            $customer_group_id = 1;
        }

        if ($quantity == NULL) {
            $quantity = 1;
        }
        $quer = "SELECT * FROM " . DB_PREFIX . "product_discount WHERE "
                . "product_id = '" . (int) $product_id . "' AND"
                . " customer_group_id = '" . (int) $customer_group_id . "' AND"
                . "  " . DB_PREFIX . "product_discount.quantity <= " . $quantity . " "
                . "AND ((date_start = '0000-00-00' OR date_start < NOW()) "
                . "AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER"
                . " BY quantity DESC, priority ASC, price ASC LIMIT 1";
        $query = $this->db->query($quer);
        return $query->row;
    }
	
	    /*
     * Takes two parameters 
     * @param option price like 0.345
     * @param market price as mp like 23,24,20
     * Returns option price
     */

    public function caclOptionPrice($price, $mp) {
        $result = 0;
        if ($mp > 0) {
            $result = $price * $mp;
        } else {
            $result = $price;
        }
        return $result;
    }

    /*
     * Takes two parameters 
     * @param discounted price like 10% of 126.99 = 114.2910
     * @param original price 126.99
     * Returns discount percent in decimals like 10% = 1-0.10 = 0.9
     */

    public function calcMetalTypeDiscount($price, $originalPrice) {
        $originalPrice == 0 ? $originalPrice = 1 : '';
        $discount = 1 - (($originalPrice - $price) / $originalPrice);
        return $discount;
    }


                
                    public function roundUp( $value, $precision ) { 
                        $pow = pow ( 10, $precision ); 
                        return ( ceil ( $pow * $value ) + ceil ( $pow * $value - ceil ( $pow * $value ) ) ) / $pow; 
                    } 
                

}

?>
