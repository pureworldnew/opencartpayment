<?php

/*
  #file: catalog/model/catalog/product_grouped.php
  #powered by fabiom7 - www.fabiom7.com - fabiome77@hotmail.it - copyright fabiom7 2012 - 2013 - 2014
 */

class ModelPosProductGrouped extends Model {

    //max grouping options
    public $max_options = 11;
	public function getProduct($product_id,$visibility = 0)
	{

       /* if ($this->customer->isLogged()) {
            $customer_group_id = $this->customer->getCustomerGroupId();
        } else {*/
            $customer_group_id = $this->config->get('config_customer_group_id');
        //}
		
		if($visibility == 1){
            $query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int) $customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int) $customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND customer_group_id = '" . (int) $customer_group_id . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int) $this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int) $this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int) $this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int) $product_id . "' AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND p.status = '1' AND p.pgvisibility != '0' AND p2s.store_id = '" . (int) $this->config->get('config_store_id') . "'");
        } else {
            $query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int) $customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int) $customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND customer_group_id = '" . (int) $customer_group_id . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int) $this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int) $this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int) $this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int) $product_id . "' AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND p.status = '1' AND p2s.store_id = '" . (int) $this->config->get('config_store_id') . "'");
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
			
			return array(
				'product_id'       => $query->row['product_id'],
				'pgprice_to'      	=> $query->row['pgprice_to'],
				'pgprice_from'    	=> $query->row['pgprice_from'],
				//'tag_title'       => $query->row['tag_title'],
				'stock_status_id' 	=> $query->row['stock_status_id'],

				'name'             	=> $query->row['name'],
				'description'      	=> $query->row['description'],
				'meta_title'       	=> $query->row['meta_title'],
				'meta_description' 	=> $query->row['meta_description'],
				'meta_keyword'    	=> $query->row['meta_keyword'],
				'tag'             	=> $query->row['tag'],
				'model'            	=> $query->row['model'],
				'custom_title' 		=> $query->row['custom_title'], 
				'custom_imgtitle' 	=> $query->row['custom_imgtitle'], 
				'custom_h2' 		=> $query->row['custom_h2'], 
				'custom_h1' 		=> $query->row['custom_h1'], 
				'custom_alt' 		=> $query->row['custom_alt'],
				'sku'              	=> $query->row['sku'],
				'upc'              	=> $query->row['upc'],
				'ean'              	=> $query->row['ean'],
				'jan'              	=> $query->row['jan'],
				'isbn'             	=> $query->row['isbn'],
				'mpn'              	=> $query->row['mpn'],
				'location'         	=> $query->row['location'],
				'quantity'         	=> $query->row['quantity'],
				'stock_status'     	=> $query->row['stock_status'],
				'image'            	=> $query->row['image'],
				'manufacturer_id'  	=> $query->row['manufacturer_id'],
				'manufacturer'     	=> $query->row['manufacturer'],
				'price'            	=> ($query->row['discount'] ? $query->row['discount'] : $query->row['price']),
				'discounted_price' 	=> $query->row['discount'],
                'orignial_price'	=> $query->row['price'],
				'special'          	=> $query->row['special'],
				'reward'           	=> $query->row['reward'],
				'points'           	=> $query->row['points'],
				'tax_class_id'     	=> $query->row['tax_class_id'],
				'date_available'   	=> $query->row['date_available'],
				'weight'           	=> $query->row['weight'],
				'weight_class_id'  	=> $query->row['weight_class_id'],
				'length'           	=> $query->row['length'],
				'width'            	=> $query->row['width'],
				'height'           	=> $query->row['height'],
				'length_class_id'  	=> $query->row['length_class_id'],
				'subtract'         	=> $query->row['subtract'],
				'rating'           	=> round($query->row['rating']),
				'reviews'          	=> $query->row['reviews'] ? $query->row['reviews'] : 0,
				'minimum'          	=> $query->row['minimum'],
				'sort_order'       	=> $query->row['sort_order'],
				'status'           	=> $query->row['status'],
				'date_added'       	=> $query->row['date_added'],
				'date_modified'    	=> $query->row['date_modified'],
				'viewed'           	=> $query->row['viewed'],
				'unit_singular'	 	=> $query->row['unit_singular'],
                'unit_plural' 		=> $query->row['unit_plural']
			);
		} else {
			return false;
		}
	}
	
		 public function getDiscountPercent($discount_quantity=null, $product_id) {
       /* if ($this->customer->isLogged()) {
            $customer_group_id = $this->customer->getCustomerGroupId();
        } else {*/
            $customer_group_id = $this->config->get('config_customer_group_id');
        //}
        //if(is_null($discount_quantity) && empty($discount_quantity)) {
           // $discount_quantity=1;
        /*}
        if($discount_quantity>0 && $discount_quantity<1) {*/
           // $discount_quantity =1;
        //}
       
        $m = "SELECT
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
        $query = $this->db->query($m);
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
    public function geProductCalculatedPrice($product_id, $quantity = 1, $unit_mult = 1, $option_details=null) {
        $base_price = $this->getMainPrice($product_id);
		
        if(!empty($unit_mult) && !is_null($unit_mult)) {
            $discount_qty = $quantity * $unit_mult;
            $discount_multiplier = $this->getDiscountPercent($discount_qty, $product_id);
            $final_price = $discount_multiplier * $base_price * $unit_mult;
        } else {
            $discount_multiplier = $this->getDiscountPercent($quantity, $product_id);
            $final_price = $discount_multiplier * $base_price ;
           
        }
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
        /*if ($this->customer->isLogged()) {
            $customer_group_id = $this->customer->getCustomerGroupId();
        } else {*/
            $customer_group_id = 1;
        //}

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
  
  public function getProductOptions($product_id, $stat = 0) {
        $product_option_data = array();	
		//echo '<br>';
		//echo "SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int) $product_id . "' AND od.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY o.sort_order";
        $product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int) $product_id . "' AND od.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY o.sort_order");

        foreach ($product_option_query->rows as $product_option) {
            if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
                $product_option_value_data = array();

                if($stat == 1){
                    $product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int) $product_id . "' AND pov.product_option_id = '" . (int) $product_option['product_option_id'] . "' AND ovd.language_id = '" . (int) $this->config->get('config_language_id') . "'");
                } else {
                    $product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int) $product_id . "' AND pov.product_option_id = '" . (int) $product_option['product_option_id'] . "' AND ovd.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY ov.sort_order");
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
		
        return $product_option_data;
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
    public function getProductGroupedType($product_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_grouped_type WHERE product_id = '" . (int) $product_id . "' LIMIT 1");

        if ($query->num_rows) {
            return array(
                'pg_type' => $query->row['pg_type'],
                'pg_groupby' => $query->row['groupby'],
                'pg_layout' => $query->row['pg_layout']
            );
        } else {
            return false;
        }
    }

    public function getProductRelatedIsGrouped($product_id) {
        $query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product_grouped WHERE product_id = '" . $product_id . "' LIMIT 1");

        return $query->num_rows;
    }

    public function getGrouped($product_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_grouped WHERE product_id = '" . $product_id . "' ORDER BY grouped_sort_order");

        return $query->rows;
    }

    public function getGroupIndicator($group_product_id) {
        $query = $this->db->query("SELECT group_indicator FROM " . DB_PREFIX . "product_grouped_indicator WHERE group_product_id = '" . $group_product_id . "'");
        if ($query->num_rows > 0) {
            return $query->row['group_indicator'];
        } else {
            return false;
        }
    }

    public function getSelectedOptionProductId($data) {
        $options = $data['option_text'];
        $num_options = $data['num_options'];
        $sql = "SELECT sku FROM product_concat_temp_table WHERE  groupbyvalue='" . $this->db->escape(trim($data['group_selector'])) . "' AND groupindicator='" . $this->db->escape($data['group_indicator']) . "'";
        $selected_option_values = array();

        foreach ($options as $key => $option_text) {
            $option_num = $key + 1;
            $opt_data = explode('~', $option_text, 2);
            $optname = $opt_data[0];
            $opt_val = $opt_data[1];
            $num_opt = $data['num_options'];
            for ($x = 1; $x <= $num_opt; $x++) {
                $sql_query = "SELECT optionname" . $x . " FROM product_concat_temp_table WHERE groupbyvalue='" . $this->db->escape(trim($data['group_selector'])) . "' AND groupindicator='" . $this->db->escape($data['group_indicator']) . "' AND optionname" . $x . "='" . $this->db->escape($optname) . "'";
                $query_sql = $this->db->query($sql_query);
                if ($query_sql->num_rows) {
                    $sql .= " AND optionvalue" . $x . "='" . $this->db->escape($opt_val) . "'";
                }
            }
        }
        $query = $this->db->query($sql);
        if ($query->num_rows) {
            return $this->getproductIdBySku($query->row['sku']);
        } else {

            return FALSE;
        }
    }

    public function getOptionsNames($where) {

        $sql = "SELECT * FROM product_concat_temp_table WHERE groupindicator_id='" . $where['groupindicator'] . "' and product_id='" . $where['product_id'] . "'";
        $op = $this->db->query($sql);
        $da = array(
            'all' => $op->row,
            'columns' => array()
        );
		
        foreach ($op->row as $key => $value) {
            if (preg_match('/(optionname)/', $key)) {
                if ($value !== '') {
                    $da['columns'][$key] = $value;
                }
            }
        }
		
		//print_r($da);
        return $da;
    }

    public function getCombination($where, $field = 'product_id') {
        $sql = "SELECT $field FROM product_concat_temp_table";
        if (!empty($where)) {
            $sql.=" where ";
            foreach ($where as $key => $wh) {
                $sql.=$key . "='" . $wh . "' and ";
            }
            $sql = trim($sql, "and ");
           // echo $sql;
			$query = $this->db->query($sql);
            return $query;
        } else {
            return false;
        }
    }

    public function getproductIdBySku($sku) {
        $sel_query = $this->db->query("SELECT product_id from " . DB_PREFIX . "product WHERE status = 1 AND sku='" . $sku . "'");
        if ($sel_query->num_rows) {
            return $sel_query->row['product_id'];
        } else {
            $sel_query_upc = $this->db->query("SELECT product_id from " . DB_PREFIX . "product WHERE status = 1 AND upc='" . $sku . "'");
            if ($sel_query_upc->num_rows) {
                return $sel_query_upc->row['product_id'];
            } else {
                $sel_query_model = $this->db->query("SELECT product_id from " . DB_PREFIX . "product WHERE status = 1 AND model='" . $sku . "'");
                if ($sel_query_model->num_rows) {
                    return $sel_query_model->row['product_id'];
                } else {
                    return FALSE;
                }
            }
        }
    }

    public function getProductStatus($gi) {
        $skus = array();
        $d = $this->db->query('SELECT sku FROM product_concat_temp_table where groupindicator="' . $gi . '"');
        if ($d->num_rows > 0) {
            foreach ($d->rows as $sku) {
                $skus[] = "'" . $sku['sku'] . "'";
            }
        }

        if (!empty($skus)) {
            $s = $this->db->query("SELECT sku from " . DB_PREFIX . "product WHERE sku IN (" . implode(',', $skus) . ") and status=0");
            $skus = array();
            if ($s->num_rows > 0) {
                foreach ($s->rows as $sku) {
                    $skus[] = $sku['sku'];
                }
            }
        }
        return $skus;
    }

    public function getCombinationSku($group_indicator) {
        $sql = $this->db->query("SELECT * from product_concat_temp_table WHERE groupindicator = '" . $group_indicator . "' LIMIT 1");
        if ($sql->num_rows > 0) {
            return $sql->row['sku'];
        } else {
            return false;
        }
    }

    public function getSkuOfCombination($selOpt, $group_indicator) {
        
    }

    public function getCombinationProductId($sku) {
        $sql = $this->db->query("SELECT product_id from " . DB_PREFIX . "product WHERE sku = '" . $sku . "'");
        if ($sql->num_rows > 0) {
            return $sql->row['product_id'];
        } else {
            return false;
        }
    }

    public function getProductOptionIds($product_id, $selected_options) {
        $option_values_ids = array();
        foreach ($selected_options as $selected_option) {
            $sp = explode('~', $selected_option);
            $option_name = $sp[0];
            $option_value_name = $sp[1];

            $sql = $this->db->query("SELECT option_id from " . DB_PREFIX . "option_description WHERE name = '" . trim($option_name) . "' AND language_id = 1");
            if ($sql->num_rows > 0) {
                $option_id = $sql->row['option_id'];
            } else {
                $option_id = 0;
            }
            $sql1 = $this->db->query("SELECT option_value_id from " . DB_PREFIX . "option_value_description WHERE name = '" . trim($option_value_name) . "' AND language_id = 1 AND option_id = '" . $option_id . "'");
            if ($sql1->num_rows > 0) {
                $option_value_id = $sql1->row['option_value_id'];
            } else {
                $option_value_id = 0;
            }

            $sql2 = $this->db->query("SELECT product_option_id from " . DB_PREFIX . "product_option WHERE product_id = '" . $product_id . "' AND option_id = '" . $option_id . "'");
            if ($sql2->num_rows > 0) {
                $product_option_id = $sql2->row['product_option_id'];
            } else {
                $product_option_id = 0;
            }

            $sql3 = $this->db->query("SELECT product_option_value_id from " . DB_PREFIX . "product_option_value WHERE product_id = '" . $product_id . "' AND product_option_id = '" . $product_option_id . "' AND option_id = '" . $option_id . "' AND option_value_id = '" . $option_value_id . "'");
            if ($sql3->num_rows > 0) {
                $product_option_value_id = $sql3->row['product_option_value_id'];
            } else {
                $product_option_value_id = 0;
            }
			

			$option_name = str_replace('?','',$option_name);
			$option_name = str_replace(' ','',$option_name);
            $option_values_ids[] = array(
                $option_name => array(
                    'product_option_id' => $product_option_id,
                    'option_value' => array(
                        'name' => $option_value_name,
                        'product_option_value_id' => $product_option_value_id
                    )
                )
            );
        }
        return $option_values_ids;
    }

    /**
     * This function handles :
     * 1) get product from group concat table
     * @author : Manish Gautam
     */
    public function getGroupedData($product_id, $groupindicator_id) {
		//echo "SELECT * FROM product_concat_temp_table WHERE product_id=" . $product_id . " AND groupindicator_id='" . $groupindicator_id . "'";
        $product_data_select_query = $this->db->query("SELECT * FROM product_concat_temp_table WHERE product_id=" . (int)$product_id . " AND groupindicator_id='" . (int)$groupindicator_id . "'");
        if ($product_data_select_query->num_rows > 0) {
            $product_data = $product_data_select_query->row;

            $required_data = array();

            $required_data['groupbyname'] = $product_data['groupbyname'];
            $required_data['groupbyvalue'] = $product_data['groupbyvalue'];

            $option_count = 0;

            for ($i = 0; $i <= $this->max_options; $i++) {
                if (!empty($product_data['optionname' . $i])) {
                    $required_data['optionname' . $i] = trim($product_data['optionname' . $i]);
                    $required_data['optionvalue' . $i] = trim($product_data['optionvalue' . $i]);
                    $option_count++;
                }
            }

            $required_data['option_count'] = $option_count;
			//print_r($required_data);
            return $required_data;
        } else {
            return FALSE;
        }
    }

}

?>
