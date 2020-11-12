<?php
class ModelCheckoutOrder extends Model {
	public function getOrderProduct($order_id) {
   				$query = $this->db->query("SELECT cd.name as category_name,op.* FROM " . DB_PREFIX . "category_description cd INNER JOIN " . DB_PREFIX . "product_to_category pc ON pc.category_id = cd.category_id INNER JOIN " . DB_PREFIX . "order_product op ON pc.product_id = op.product_id LEFT JOIN " . DB_PREFIX . "order_option oo ON (oo.order_product_id = op.order_product_id) WHERE op.order_id = '" . (int)$order_id . "' AND pc.product_id = op.product_id AND oo.order_id IS NULL GROUP BY op.order_product_id");

            	return $query->rows;
       		 }
               
            public function getOrderProductOptions($order_id) {
				$query = $this->db->query("SELECT cd.name as category_name,op.*,oo.name as option_name, oo.value,oo.order_product_id,GROUP_CONCAT(DISTINCT oo.name, ': ', oo.value SEPARATOR ' - ') as options_data FROM " . DB_PREFIX . "category_description cd INNER JOIN " . DB_PREFIX . "product_to_category pc ON pc.category_id = cd.category_id INNER JOIN " . DB_PREFIX . "order_product op ON pc.product_id = op.product_id INNER JOIN " . DB_PREFIX . "order_option oo ON op.order_product_id = oo.order_product_id WHERE op.order_id = '" . (int)$order_id . "' AND pc.product_id = op.product_id AND op.order_product_id = oo.order_product_id GROUP BY oo.order_product_id");

            	return $query->rows;
				}
				
	public function changeStore($order_id, $store_id)
	{
		if ( $store_id == 1 )
		{
			$is_pos = 1;
		} else {
			$is_pos = 0;
		}
		$this->db->query("UPDATE " . DB_PREFIX . "order SET is_pos = '" . (int)$is_pos . "', order_type = '" . (int)$store_id . "' WHERE order_id = '" . (int)$order_id . "'");
	}
			 
	public function addOrder($data) {
		$this->event->trigger('pre.order.add', $data);
		 if(isset($data['resale_text'])){
            $resale_text = $data['resale_text'];
        }else{
            $resale_text = '';
        }
		if(isset($data['additional_cost'])){
            $additional_cost = $data['additional_cost'];
        }else{
            $additional_cost = '0';
        }

          if(isset($data['payment_cost'])){
          $data['payment_cost']=$data['payment_cost'];
        }else{
        	$data['payment_cost']=0;
        }

         if(isset($data['shipping_cost'])){
          $data['shipping_cost']=$data['shipping_cost'];
        }else{
        	$data['shipping_cost']=0;
        }

         if(isset($data['extra_cost'])){
          $data['extra_cost']=$data['extra_cost'];
        }else{
        	$data['extra_cost']=0;
				}

				$order_type = 0;
				if( isset($this->request->get['order_type']) && $this->request->get['order_type'] == 3 )
				{
					$order_type = 3; 
				}
		
		$this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET weight = '" . (isset($data['weight']) ? floatval($data['weight']) : 0) . "', invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . "', store_id = '" . (int)$data['store_id'] . "', store_name = '" . $this->db->escape($data['store_name']) . "', store_url = '" . $this->db->escape($data['store_url']) . "', customer_id = '" . (int)$data['customer_id'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? json_encode($data['custom_field']) : '') . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_country = '" . $this->db->escape($data['payment_country']) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_zone = '" . $this->db->escape($data['payment_zone']) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "', payment_custom_field = '" . $this->db->escape(isset($data['payment_custom_field']) ? json_encode($data['payment_custom_field']) : '') . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', payment_code = '" . $this->db->escape($data['payment_code']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($data['shipping_country']) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "', shipping_custom_field = '" . $this->db->escape(isset($data['shipping_custom_field']) ? json_encode($data['shipping_custom_field']) : '') . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', shipping_code = '" . $this->db->escape($data['shipping_code']) . "', comment = '" . $this->db->escape($data['comment']) . "', total = '" . (float)$data['total'] . "', payment_cost = '" . (float)$data['payment_cost'] . "', shipping_cost = '" . (float)$data['shipping_cost'] . "', extra_cost = '" . (float)$data['extra_cost'] . "', affiliate_id = '" . (int)$data['affiliate_id'] . "', commission = '" . (float)$data['commission'] . "', marketing_id = '" . (int)$data['marketing_id'] . "', tracking = '" . $this->db->escape($data['tracking']) . "', language_id = '" . (int)$data['language_id'] . "', currency_id = '" . (int)$data['currency_id'] . "', currency_code = '" . $this->db->escape($data['currency_code']) . "', currency_value = '" . (float)$data['currency_value'] . "', ip = '" . $this->db->escape($data['ip']) . "', forwarded_ip = '" .  $this->db->escape($data['forwarded_ip']) . "', user_agent = '" . $this->db->escape($data['user_agent']) . "', accept_language = '" . $this->db->escape($data['accept_language']) . "', date_added = NOW(), date_modified = NOW(),resale_text='".$this->db->escape($resale_text)."',order_type= '" . (int)$order_type . "', additional_cost = '" . (float)$additional_cost . "'");

		$order_id = $this->db->getLastId();

		$this->db->query("UPDATE `".DB_PREFIX."order` SET session_id='".$this->db->escape($this->session->getId())."' WHERE order_id='".$order_id."' ");

		// Products
		
		
		if (isset($data['products'])) {
			foreach ($data['products'] as $product) {
				if(isset($product['unit']['unit_conversion_values'])){
                            $convert_id = $product['unit']['unit_conversion_values']; 
                         } else {
                            $convert_id = 0;
                         }
						  if(isset($product['base_price'])){
					          $product['base_price']=$product['base_price'];
					        }else{
					        	$product['base_price']=0;
					        }

					         if(isset($product['supplier_id'])){
					          $product['supplier_id']=$product['supplier_id'];
					        }else{
					        	$product['supplier_id']=0;
					        }

					        if(isset($product['cost'])){
					          $product['cost']=$product['cost'];
					        }else{
					        	$product['cost']=0;
					        }
						 
						 
				 if(isset($product['quantity_supplied'])&&$product['quantity_supplied']!=""){
                      $quantity_supplied = $product['quantity_supplied'];
					  $this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (float)$product['quantity'] . "',quantity_supplied='".$quantity_supplied . "', price = '" . (float)$product['price'] . "', base_price = '" . (float)$product['base_price'] . "', cost = '" . (float)$product['cost'] . "', supplier_id = '" . (int)$product['supplier_id'] . "', total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "', reward = '" . (int)$product['reward'] . "', unit_conversion_values = '" . (int)$convert_id . "'");
                 } else {
					 $quantity_supplied = NULL;
					 $this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (float)$product['quantity'] . "', price = '" . (float)$product['price'] . "', base_price = '" . (float)$product['base_price'] . "', cost = '" . (float)$product['cost'] . "', supplier_id = '" . (int)$product['supplier_id'] . "', total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "', reward = '" . (int)$product['reward'] . "', unit_conversion_values = '" . (int)$convert_id . "'");
                     
                 }
				/*$this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (float)$product['quantity'] . "',quantity_supplied='".$quantity_supplied . "', price = '" . (float)$product['price'] . "', base_price = '" . (float)$product['base_price'] . "', cost = '" . (float)$product['cost'] . "', supplier_id = '" . (int)$product['supplier_id'] . "', total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "', reward = '" . (int)$product['reward'] . "', unit_conversion_values = '" . (int)$convert_id . "'");

			
			
			'NULL'  0.0000
*/			
			
			
			
				$order_product_id = $this->db->getLastId();

				foreach ($product['option'] as $option) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$option['product_option_id'] . "', product_option_value_id = '" . (int)$option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'");
				}
			}
		}

		// Gift Voucher
		$this->load->model('total/voucher');

		// Vouchers
		if (isset($data['vouchers'])) {
			foreach ($data['vouchers'] as $voucher) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_voucher SET order_id = '" . (int)$order_id . "', description = '" . $this->db->escape($voucher['description']) . "', code = '" . $this->db->escape($voucher['code']) . "', from_name = '" . $this->db->escape($voucher['from_name']) . "', from_email = '" . $this->db->escape($voucher['from_email']) . "', to_name = '" . $this->db->escape($voucher['to_name']) . "', to_email = '" . $this->db->escape($voucher['to_email']) . "', voucher_theme_id = '" . (int)$voucher['voucher_theme_id'] . "', message = '" . $this->db->escape($voucher['message']) . "', amount = '" . (float)$voucher['amount'] . "'");

				$order_voucher_id = $this->db->getLastId();

				$voucher_id = $this->model_total_voucher->addVoucher($order_id, $voucher);

				$this->db->query("UPDATE " . DB_PREFIX . "order_voucher SET voucher_id = '" . (int)$voucher_id . "' WHERE order_voucher_id = '" . (int)$order_voucher_id . "'");
			}
		}

		// Totals
		if (isset($data['totals'])) {
			foreach ($data['totals'] as $total) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', `value` = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
			}
		}

		$this->event->trigger('post.order.add', $order_id);

		return $order_id;
	}

	public function addIncomingOrder($data) {
		$this->event->trigger('pre.order.add', $data);
		 if(isset($data['resale_text'])){
            $resale_text = $data['resale_text'];
        }else{
            $resale_text = '';
        }
		if(isset($data['additional_cost'])){
            $additional_cost = $data['additional_cost'];
        }else{
            $additional_cost = '0';
        }

          if(isset($data['payment_cost'])){
          $data['payment_cost']=$data['payment_cost'];
        }else{
        	$data['payment_cost']=0;
        }

         if(isset($data['shipping_cost'])){
          $data['shipping_cost']=$data['shipping_cost'];
        }else{
        	$data['shipping_cost']=0;
        }

         if(isset($data['extra_cost'])){
          $data['extra_cost']=$data['extra_cost'];
        }else{
        	$data['extra_cost']=0;
        }
		
		$this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET weight = '" . (isset($data['weight']) ? floatval($data['weight']) : 0) . "', invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . "', store_id = '" . (int)$data['store_id'] . "', store_name = '" . $this->db->escape($data['store_name']) . "', store_url = '" . $this->db->escape($data['store_url']) . "', customer_id = '" . (int)$data['customer_id'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? json_encode($data['custom_field']) : '') . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_country = '" . $this->db->escape($data['payment_country']) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_zone = '" . $this->db->escape($data['payment_zone']) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "', payment_custom_field = '" . $this->db->escape(isset($data['payment_custom_field']) ? json_encode($data['payment_custom_field']) : '') . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', payment_code = '" . $this->db->escape($data['payment_code']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($data['shipping_country']) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "', shipping_custom_field = '" . $this->db->escape(isset($data['shipping_custom_field']) ? json_encode($data['shipping_custom_field']) : '') . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', shipping_code = '" . $this->db->escape($data['shipping_code']) . "', comment = '" . $this->db->escape($data['comment']) . "', total = '" . (float)$data['total'] . "', payment_cost = '" . (float)$data['payment_cost'] . "', shipping_cost = '" . (float)$data['shipping_cost'] . "', extra_cost = '" . (float)$data['extra_cost'] . "', affiliate_id = '" . (int)$data['affiliate_id'] . "', commission = '" . (float)$data['commission'] . "', marketing_id = '" . (int)$data['marketing_id'] . "', tracking = '" . $this->db->escape($data['tracking']) . "', language_id = '" . (int)$data['language_id'] . "', currency_id = '" . (int)$data['currency_id'] . "', currency_code = '" . $this->db->escape($data['currency_code']) . "', currency_value = '" . (float)$data['currency_value'] . "', ip = '" . $this->db->escape($data['ip']) . "', forwarded_ip = '" .  $this->db->escape($data['forwarded_ip']) . "', user_agent = '" . $this->db->escape($data['user_agent']) . "', accept_language = '" . $this->db->escape($data['accept_language']) . "', date_added = NOW(), date_modified = NOW(),resale_text='".$this->db->escape($resale_text)."',order_type='2', order_status_id='1', additional_cost = '" . (float)$additional_cost . "'");

		$order_id = $this->db->getLastId();

		$this->db->query("UPDATE `".DB_PREFIX."order` SET session_id='".$this->db->escape($this->session->getId())."' WHERE order_id='".$order_id."' ");

		// Products
		
		
		if (isset($data['products'])) {
			foreach ($data['products'] as $product) {
				if(isset($product['unit']['unit_conversion_values'])){
                            $convert_id = $product['unit']['unit_conversion_values']; 
                         } else {
                            $convert_id = 0;
                         }
						  if(isset($product['base_price'])){
					          $product['base_price']=$product['base_price'];
					        }else{
					        	$product['base_price']=0;
					        }

					         if(isset($product['supplier_id'])){
					          $product['supplier_id']=$product['supplier_id'];
					        }else{
					        	$product['supplier_id']=0;
					        }

					        if(isset($product['cost'])){
					          $product['cost']=$product['cost'];
					        }else{
					        	$product['cost']=0;
					        }
						 
						 
				 if(isset($product['quantity_supplied'])&&$product['quantity_supplied']!=""){
                      $quantity_supplied = $product['quantity_supplied'];
					  $this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (float)$product['quantity'] . "',quantity_supplied='".$quantity_supplied . "', price = '" . (float)$product['price'] . "', base_price = '" . (float)$product['base_price'] . "', cost = '" . (float)$product['cost'] . "', supplier_id = '" . (int)$product['supplier_id'] . "', total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "', reward = '" . (int)$product['reward'] . "', unit_conversion_values = '" . (int)$convert_id . "'");
                 } else {
					 $quantity_supplied = NULL;
					 $this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (float)$product['quantity'] . "', price = '" . (float)$product['price'] . "', base_price = '" . (float)$product['base_price'] . "', cost = '" . (float)$product['cost'] . "', supplier_id = '" . (int)$product['supplier_id'] . "', total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "', reward = '" . (int)$product['reward'] . "', unit_conversion_values = '" . (int)$convert_id . "'");
                     
                 }
				/*$this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (float)$product['quantity'] . "',quantity_supplied='".$quantity_supplied . "', price = '" . (float)$product['price'] . "', base_price = '" . (float)$product['base_price'] . "', cost = '" . (float)$product['cost'] . "', supplier_id = '" . (int)$product['supplier_id'] . "', total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "', reward = '" . (int)$product['reward'] . "', unit_conversion_values = '" . (int)$convert_id . "'");

			
			
			'NULL'  0.0000
*/			
			
			
			
				$order_product_id = $this->db->getLastId();

				foreach ($product['option'] as $option) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$option['product_option_id'] . "', product_option_value_id = '" . (int)$option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'");
				}
			}
		}

		// Gift Voucher
		$this->load->model('total/voucher');

		// Vouchers
		if (isset($data['vouchers'])) {
			foreach ($data['vouchers'] as $voucher) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_voucher SET order_id = '" . (int)$order_id . "', description = '" . $this->db->escape($voucher['description']) . "', code = '" . $this->db->escape($voucher['code']) . "', from_name = '" . $this->db->escape($voucher['from_name']) . "', from_email = '" . $this->db->escape($voucher['from_email']) . "', to_name = '" . $this->db->escape($voucher['to_name']) . "', to_email = '" . $this->db->escape($voucher['to_email']) . "', voucher_theme_id = '" . (int)$voucher['voucher_theme_id'] . "', message = '" . $this->db->escape($voucher['message']) . "', amount = '" . (float)$voucher['amount'] . "'");

				$order_voucher_id = $this->db->getLastId();

				$voucher_id = $this->model_total_voucher->addVoucher($order_id, $voucher);

				$this->db->query("UPDATE " . DB_PREFIX . "order_voucher SET voucher_id = '" . (int)$voucher_id . "' WHERE order_voucher_id = '" . (int)$order_voucher_id . "'");
			}
		}

		// Totals
		if (isset($data['totals'])) {
			foreach ($data['totals'] as $total) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', `value` = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
			}
		}

		$this->event->trigger('post.order.add', $order_id);

		return $order_id;
	}

	public function editOrder($order_id, $data) {
		$this->event->trigger('pre.order.edit', $data);

		// Void the order first
		$this->addOrderHistory($order_id, 0);

		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . "', store_id = '" . (int)$data['store_id'] . "', store_name = '" . $this->db->escape($data['store_name']) . "', store_url = '" . $this->db->escape($data['store_url']) . "', customer_id = '" . (int)$data['customer_id'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(json_encode($data['custom_field'])) . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_country = '" . $this->db->escape($data['payment_country']) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_zone = '" . $this->db->escape($data['payment_zone']) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "', payment_custom_field = '" . $this->db->escape(json_encode($data['payment_custom_field'])) . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', payment_code = '" . $this->db->escape($data['payment_code']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($data['shipping_country']) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "', shipping_custom_field = '" . $this->db->escape(json_encode($data['shipping_custom_field'])) . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', shipping_code = '" . $this->db->escape($data['shipping_code']) . "', comment = '" . $this->db->escape($data['comment']) . "', total = '" . (float)$data['total'] . "', payment_cost = '" . (float)$data['payment_cost'] . "', shipping_cost = '" . (float)$data['shipping_cost'] . "', extra_cost = '" . (float)$data['extra_cost'] . "', affiliate_id = '" . (int)$data['affiliate_id'] . "', commission = '" . (float)$data['commission'] . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "'");

		// Products
		if (isset($data['products'])) {
			foreach ($data['products'] as $product) {
				if(isset($product['unit']['unit_conversion_values'])){
                            $convert_id = $product['unit']['unit_conversion_values']; 
                         } else {
                            $convert_id = 0;
                         }
				if(isset($product['quantity_supplied'])&&$product['quantity_supplied']!=""){
					$quantity_supplied = $product['quantity_supplied']; 
				} else {
					$quantity_supplied = NULL;
				}

				$vendor_query = "";
				if(!empty($product['default_vendor_unit']))
				{
					$vendor_query .= "`default_vendor_unit`='".$product['default_vendor_unit']."',";
				}
		
				if(!empty($product['updated_vendor_unit']))
				{
					$vendor_query .= "`updated_vendor_unit`='".$product['updated_vendor_unit']."',";
				}

				if(isset($product['remark']))
				{
					$vendor_query .= "`remark`='".$product['remark']."',";
				}
				
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (float)$product['quantity'] . "',quantity_supplied='".$quantity_supplied . "', price = '" . (float)$product['price'] . "', base_price = '" . (float)$product['base_price'] . "', cost = '" . (float)$product['cost'] . "', supplier_id = '" . (int)$product['supplier_id'] . "', total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "', ". $vendor_query . " reward = '" . (int)$product['reward'] . "', unit_conversion_values = '" . (int)$convert_id . "'");

				$order_product_id = $this->db->getLastId();

				foreach ($product['option'] as $option) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$option['product_option_id'] . "', product_option_value_id = '" . (int)$option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'");
				}
			}
		}

		// Gift Voucher
		$this->load->model('total/voucher');

		$this->model_total_voucher->disableVoucher($order_id);

		// Vouchers
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'");

		if (isset($data['vouchers'])) {
			foreach ($data['vouchers'] as $voucher) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_voucher SET order_id = '" . (int)$order_id . "', description = '" . $this->db->escape($voucher['description']) . "', code = '" . $this->db->escape($voucher['code']) . "', from_name = '" . $this->db->escape($voucher['from_name']) . "', from_email = '" . $this->db->escape($voucher['from_email']) . "', to_name = '" . $this->db->escape($voucher['to_name']) . "', to_email = '" . $this->db->escape($voucher['to_email']) . "', voucher_theme_id = '" . (int)$voucher['voucher_theme_id'] . "', message = '" . $this->db->escape($voucher['message']) . "', amount = '" . (float)$voucher['amount'] . "'");

				$order_voucher_id = $this->db->getLastId();

				$voucher_id = $this->model_total_voucher->addVoucher($order_id, $voucher);

				$this->db->query("UPDATE " . DB_PREFIX . "order_voucher SET voucher_id = '" . (int)$voucher_id . "' WHERE order_voucher_id = '" . (int)$order_voucher_id . "'");
			}
		}

		// Totals
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "'");

		if (isset($data['totals'])) {
			foreach ($data['totals'] as $total) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', `value` = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
			}
		}

		//WA-Sale Tax
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' AND code = 'tax'");
		$order_tax_data = $query->row;
		if( !empty($order_tax_data) && strpos($order_tax_data['title'], 'US-CA') !== false )  
		{
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' AND code = 'sub_total'");
			$sub_total = $query->row['value'];
			$tax = ($sub_total * 9.5) / 100;
			$this->db->query("UPDATE " . DB_PREFIX . "order_total SET `value` = '" . (float)$tax . "' WHERE order_id = '" . (int)$order_id . "' AND code = 'tax'");  
			$query = $this->db->query("SELECT SUM(`value`) as total FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' AND code != 'total'");
			$total = $query->row['total'];
			$this->db->query("UPDATE " . DB_PREFIX . "order_total SET `value` = '" . (float)$total . "' WHERE order_id = '" . (int)$order_id . "' AND code = 'total'");   
		}
		//WA-Sale Tax

		$this->event->trigger('post.order.edit', $order_id);
	}

	public function deleteOrder($order_id) {
		$this->event->trigger('pre.order.delete', $order_id);

		// Void the order first
		$this->addOrderHistory($order_id, 0);

		$this->db->query("DELETE FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_product` WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_option` WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_voucher` WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_history` WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE `or`, ort FROM `" . DB_PREFIX . "order_recurring` `or`, `" . DB_PREFIX . "order_recurring_transaction` `ort` WHERE order_id = '" . (int)$order_id . "' AND ort.order_recurring_id = `or`.order_recurring_id");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "affiliate_transaction` WHERE order_id = '" . (int)$order_id . "'");

		// Gift Voucher
		$this->load->model('total/voucher');

		$this->model_total_voucher->disableVoucher($order_id);

		$this->event->trigger('post.order.delete', $order_id);
	}
	
	public function assignSalesPersontoOrder($order_id, $sales_person)
	{
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET sales_person = '" . (int)$sales_person . "' WHERE order_id = '" . (int)$order_id . "'");
	}
	
	public function getLatestOrderStatusId($order_id)
	{
		$query = $this->db->query("SELECT order_status_id FROM " . DB_PREFIX . "order_history WHERE order_id = '" . (int)$order_id . "' ORDER BY order_history_id DESC LIMIT 1");

		return $query->row['order_status_id'];
	}
	
	public function getOrderStatus($order_status_id)
	{
		$query = $this->db->query("SELECT name FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "'");

		return $query->row ? $query->row['name'] : "";
	}
	
	public function getSalesPersonById($user_id) {
		$query = $this->db->query("SELECT CONCAT(firstname, ' ', lastname) as name FROM " . DB_PREFIX . "user WHERE user_id = '" . (int)$user_id . "'");

		return $query->row['name'];
	}
	
	public function getSalesPersonByName($name) {
		$query = $this->db->query("SELECT user_id FROM " . DB_PREFIX . "user WHERE CONCAT(firstname, ' ', lastname) = '" . $name . "'");

		return $query->row['user_id'];
	}

	public function getOrder($order_id) {
		$order_query = $this->db->query("SELECT *, (SELECT os.name FROM `" . DB_PREFIX . "order_status` os WHERE os.order_status_id = o.order_status_id AND os.language_id = o.language_id) AS order_status FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int)$order_id . "'");

		if ($order_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");

			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row['iso_code_2'];
				$payment_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row['code'];
			} else {
				$payment_zone_code = '';
			}

			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");

			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row['code'];
			} else {
				$shipping_zone_code = '';
			}

			$this->load->model('localisation/language');

			$language_info = $this->model_localisation_language->getLanguage($order_query->row['language_id']);

			if ($language_info) {
				$language_code = $language_info['code'];
				$language_directory = $language_info['directory'];
			} else {
				$language_code = '';
				$language_directory = '';
			}

			return array(
				'order_id'                => $order_query->row['order_id'],
				'invoice_no'              => $order_query->row['invoice_no'],
				'invoice_prefix'          => $order_query->row['invoice_prefix'],
				'store_id'                => $order_query->row['store_id'],
				'store_name'              => $order_query->row['store_name'],
				'store_url'               => $order_query->row['store_url'],
				'customer_id'             => $order_query->row['customer_id'],

				'customer_group_id'            => $order_query->row['customer_group_id'],
			
				'customer_group_id' => (isset($order_query->row['customer_group_id'])) ? $order_query->row['customer_group_id'] : '',
				'affiliate_id' => (isset($order_query->row['affiliate_id'])) ? $order_query->row['affiliate_id'] : '',
            	'weight' => (isset($order_query->row['weight'])) ? $order_query->row['weight'] : 0,
				'firstname'               => $order_query->row['firstname'],
				'lastname'                => $order_query->row['lastname'],
				'email'                   => $order_query->row['email'],
				'telephone'               => $order_query->row['telephone'],
				'fax'                     => $order_query->row['fax'],
				'custom_field'            => json_decode($order_query->row['custom_field'], true),
				'payment_firstname'       => $order_query->row['payment_firstname'],
				'payment_lastname'        => $order_query->row['payment_lastname'],
				'payment_company'         => $order_query->row['payment_company'],
				'payment_address_1'       => $order_query->row['payment_address_1'],
				'payment_address_2'       => $order_query->row['payment_address_2'],
				'payment_postcode'        => $order_query->row['payment_postcode'],
				'payment_city'            => $order_query->row['payment_city'],
				'payment_zone_id'         => $order_query->row['payment_zone_id'],
				'payment_zone'            => $order_query->row['payment_zone'],
				'payment_zone_code'       => $payment_zone_code,
				'payment_country_id'      => $order_query->row['payment_country_id'],
				'payment_country'         => $order_query->row['payment_country'],
				'payment_iso_code_2'      => $payment_iso_code_2,
				'payment_iso_code_3'      => $payment_iso_code_3,
				'payment_address_format'  => $order_query->row['payment_address_format'],
				'payment_custom_field'    => json_decode($order_query->row['payment_custom_field'], true),
				'payment_method'          => $order_query->row['payment_method'],
				'payment_code'            => $order_query->row['payment_code'],
				'shipping_firstname'      => $order_query->row['shipping_firstname'],
				'shipping_lastname'       => $order_query->row['shipping_lastname'],
				'shipping_company'        => $order_query->row['shipping_company'],
				'shipping_address_1'      => $order_query->row['shipping_address_1'],
				'shipping_address_2'      => $order_query->row['shipping_address_2'],
				'shipping_postcode'       => $order_query->row['shipping_postcode'],
				'shipping_city'           => $order_query->row['shipping_city'],
				'shipping_zone_id'        => $order_query->row['shipping_zone_id'],
				'shipping_zone'           => $order_query->row['shipping_zone'],
				'shipping_zone_code'      => $shipping_zone_code,
				'shipping_country_id'     => $order_query->row['shipping_country_id'],
				'shipping_country'        => $order_query->row['shipping_country'],
				'shipping_iso_code_2'     => $shipping_iso_code_2,
				'shipping_iso_code_3'     => $shipping_iso_code_3,
				'shipping_address_format' => $order_query->row['shipping_address_format'],
				'shipping_custom_field'   => json_decode($order_query->row['shipping_custom_field'], true),
				'shipping_method'         => $order_query->row['shipping_method'],
				'shipping_code'           => $order_query->row['shipping_code'],
				'comment'                 => $order_query->row['comment'],
				'total'                   => $order_query->row['total'],

				'payment_cost'            => $order_query->row['payment_cost'],	
				'shipping_cost'           => $order_query->row['shipping_cost'],
				'extra_cost'           	  => $order_query->row['extra_cost'],
            
				'order_status_id'         => $order_query->row['order_status_id'],
				'order_status'            => $order_query->row['order_status'],
				'affiliate_id'            => $order_query->row['affiliate_id'],
				'commission'              => $order_query->row['commission'],
				'language_id'             => $order_query->row['language_id'],
				'language_code'           => $language_code,
				'language_directory'      => $language_directory,
				'currency_id'             => $order_query->row['currency_id'],
				'currency_code'           => $order_query->row['currency_code'],
				'currency_value'          => $order_query->row['currency_value'],
				'ip'                      => $order_query->row['ip'],
				'forwarded_ip'            => $order_query->row['forwarded_ip'],
				'user_agent'              => $order_query->row['user_agent'],
				'accept_language'         => $order_query->row['accept_language'],
			'date_invoice'              => isset($order_query->row['date_invoice']) ? $order_query->row['date_invoice'] : '',
				'date_modified'           => $order_query->row['date_modified'],
				'date_added'              => $order_query->row['date_added'],
				'additional_cost'		  => $order_query->row['additional_cost']
			);
		} else {
			return false;
		}
	}
	
	public function _addOrderHistory($order_id,$order_status_id, $comment)
	{
		$date_added = date("Y-m-d h:i:s");
		$this->db->query("INSERT INTO ".DB_PREFIX."order_history SET order_id='".(int)$order_id."',order_status_id='".(int)$order_status_id."',comment='".$comment."',date_added='".$date_added."'");
		
	}

	public function addOrderHistory($order_id, $order_status_id, $comment = '', $notify = false, $override = false) {
		$event_data = array(
			'order_id'		  => $order_id,
			'order_status_id' => $order_status_id,
			'comment'		  => $comment,
			'notify'		  => $notify
		);

		$this->event->trigger('pre.order.history.add', $event_data);

		$order_info = $this->getOrder($order_id);

		if ($order_info) {
			// Fraud Detection
			$this->load->model('account/customer');

			$customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);

			if ($customer_info && $customer_info['safe']) {
				$safe = true;
			} else {
				$safe = false;
			}

			// Only do the fraud check if the customer is not on the safe list and the order status is changing into the complete or process order status
			if (!$safe && !$override && in_array($order_status_id, array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status')))) {
				// Anti-Fraud
				$this->load->model('extension/extension');

				$extensions = $this->model_extension_extension->getExtensions('fraud');

				foreach ($extensions as $extension) {
					if ($this->config->get($extension['code'] . '_status')) {
						$this->load->model('fraud/' . $extension['code']);

						$fraud_status_id = $this->{'model_fraud_' . $extension['code']}->check($order_info);

						if ($fraud_status_id) {
							$order_status_id = $fraud_status_id;
						}
					}
				}
			}
			
			// Redeem coupon, vouchers and reward points
			$order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order ASC");

			foreach ($order_total_query->rows as $order_total) {
				$this->load->model('total/' . $order_total['code']);
				
				if (method_exists($this->{'model_total_' . $order_total['code']}, 'confirm')) {
				  // Confirm coupon, vouchers and reward points
				  $fraud_status_id = $this->{'model_total_' . $order_total['code']}->confirm($order_info, $order_total);

				  // If the balance on the coupon, vouchers and reward points is not enough to cover the transaction or has already been used then the fraud order status is returned.
				  if ($fraud_status_id) {
					  //$order_status_id = $fraud_status_id;
				  }
			  }
			}

			// If current order status is not processing or complete but new status is processing or complete then commence completing the order
			if (!in_array($order_info['order_status_id'], array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status'))) && in_array($order_status_id, array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status')))) {
				//removed the code from here and moved it above the condition for ticket#90 (modified this due to the quick order edit module)

				// Add commission if sale is linked to affiliate referral.
				if ($order_info['affiliate_id'] && $this->config->get('config_affiliate_auto')) {
					$this->load->model('affiliate/affiliate');

					$this->model_affiliate_affiliate->addTransaction($order_info['affiliate_id'], $order_info['commission'], $order_id);
				}

				// Stock subtraction
				$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

				foreach ($order_product_query->rows as $order_product) {
					$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND subtract = '1'");

					$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$order_product['product_id'] . "' AND subtract = '1'");
					foreach ($product_query->rows as $product) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_stock_history SET product_id = '" . (int)$product['product_id'] . "', restock_quantity = '-" . (int)$order_product['quantity'] . "', stock_quantity = '" . (int)$product['quantity'] . "', supplier_id = '0', costing_method = '" . (int)$product['costing_method'] . "', restock_cost = (" . (float)$product['cost_amount'] . " + ((" . (float)$product['cost_percentage'] . " / 100) * " . (float)$product['price'] . ") + " . (float)$product['cost_additional'] . "), cost = '" . (float)$product['cost'] . "', price = '" . (float)$product['price'] . "', comment = 'Order ID: " . (int)$order_id . "', date_added = NOW()");
					}
            

					$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product['order_product_id'] . "'");

					foreach ($order_option_query->rows as $option) {
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");

						$product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
						foreach ($product_option_query->rows as $product_option) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_stock_history SET product_option_id = '" . (int)$product_option['product_option_id'] . "', product_id = '" . (int)$product_option['product_id'] . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . (int)$product_option['option_value_id'] . "', restock_quantity = '-" . (int)$order_product['quantity'] . "', stock_quantity = '" . (int)$product_option['quantity'] . "', supplier_id = '0', costing_method = '" . (int)$product_option['costing_method'] . "', restock_cost = '" . (float)$product_option['cost_amount'] . "', cost = '" . (float)$product_option['cost'] . "', price = '" . (float)$product_option['price'] . "', comment = 'Order ID: " . (int)$order_id . "', date_added = NOW()");
						}
            
					}
				}
			}

			// Update the DB with the new statuses
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '" . (int)$notify . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
			
				// Update Combine Shipping Option
				$selected_order_statuses = $this->config->get('selected_order_statuses');
				$this->db->query("UPDATE `" . DB_PREFIX . "order` SET combine_shipping = 0 WHERE order_id = '" . (int)$order_id . "' AND order_status_id NOT IN (" . $selected_order_statuses .")");
			
			if(isset($order_info['additional_cost'])&&$order_info['additional_cost'] >0){ 
			
					$additional_authorize_text = 'Additional Cost added to Total '.$this->currency->format($order_info['additional_cost'], $order_info['currency_code'], $order_info['currency_value']).' Payment Authorization Amount '.$this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value']);
					$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '0', comment = '" . $this->db->escape($additional_authorize_text) . "', date_added = NOW()");
					$additional_authorize_text = '<span style="color:#000000;">Additional Cost added to Total '.$this->currency->format($order_info['additional_cost'], $order_info['currency_code'], $order_info['currency_value']).' Payment Authorization Amount '.$this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value']).'</span>';
			

			}
						// If old order status is the processing or complete status but new status is not then commence restock, and remove coupon, voucher and reward history
			if (in_array($order_info['order_status_id'], array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status'))) && !in_array($order_status_id, array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status')))) {
				// Restock
				$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

				foreach($product_query->rows as $product) {
					$this->db->query("UPDATE `" . DB_PREFIX . "product` SET quantity = (quantity + " . (float)$product['quantity'] . ") WHERE product_id = '" . (int)$product['product_id'] . "' AND subtract = '1'");

					$option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");

					foreach ($option_query->rows as $option) {
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity + " . (float)$product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
					}
				}

				// Remove coupon, vouchers and reward points history
				$this->load->model('account/order');

				$order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order ASC");

				foreach ($order_total_query->rows as $order_total) {
					$this->load->model('total/' . $order_total['code']);

					if (method_exists($this->{'model_total_' . $order_total['code']}, 'unconfirm')) {
						$this->{'model_total_' . $order_total['code']}->unconfirm($order_id);
					}
				}

				// Remove commission if sale is linked to affiliate referral.
				if ($order_info['affiliate_id']) {
					$this->load->model('affiliate/affiliate');

					$this->model_affiliate_affiliate->deleteTransaction($order_id);
				}
			}

			$this->cache->delete('product');

			// If order status is 0 then becomes greater than 0 send main html email

		if(isset($this->request->post['notify-orderentry']) && !$this->request->post['notify-orderentry']) {
			goto dontsendhtmlemail;
		}
				
			if (!$order_info['order_status_id'] && $order_status_id) {
				// Check for any downloadable products
				$download_status = false;

				$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

				foreach ($order_product_query->rows as $order_product) {
					// Check if there are any linked downloads
					$product_download_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "product_to_download` WHERE product_id = '" . (int)$order_product['product_id'] . "'");

					if ($product_download_query->row['total']) {
						$download_status = true;
					}
				}

				// Load the language for any mails that might be required to be sent out
				$language = new Language($order_info['language_directory']);
				$language->load($order_info['language_directory']);
				$language->load('mail/order');

				$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_info['language_id'] . "'");

				if ($order_status_query->num_rows) {
					$order_status = $order_status_query->row['name'];
				} else {
					$order_status = '';
				}

				$subject = sprintf($language->get('text_new_subject'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'), $order_id);

				// HTML Mail
				$data = array();

				$data['title'] = sprintf($language->get('text_new_subject'), $order_info['store_name'], $order_id);

				$data['text_greeting'] = sprintf($language->get('text_new_greeting'), $order_info['store_name']);
				$data['text_link'] = $language->get('text_new_link');
				$data['text_download'] = $language->get('text_new_download');
				$data['text_order_detail'] = $language->get('text_new_order_detail');
				$data['text_instruction'] = $language->get('text_new_instruction');
				$data['text_order_id'] = $language->get('text_new_order_id');
				$data['text_date_added'] = $language->get('text_new_date_added');
				$data['text_payment_method'] = $language->get('text_new_payment_method');
				$data['text_shipping_method'] = $language->get('text_new_shipping_method');
				$data['text_email'] = $language->get('text_new_email');
				$data['text_telephone'] = $language->get('text_new_telephone');
				$data['text_ip'] = $language->get('text_new_ip');
				$data['text_order_status'] = $language->get('text_new_order_status');
				$data['text_payment_address'] = $language->get('text_new_payment_address');
				$data['text_shipping_address'] = $language->get('text_new_shipping_address');
				$data['text_product'] = $language->get('text_new_product');
				$data['text_model'] = $language->get('text_new_model');
				$data['text_quantity'] = $language->get('text_new_quantity');
				$data['text_price'] = $language->get('text_new_price');
				$data['text_total'] = $language->get('text_new_total');
				$data['text_footer'] = $language->get('text_new_footer');

				$data['logo'] = $this->config->get('config_url') . 'image/' . $this->config->get('config_logo');
				$data['store_name'] = $order_info['store_name'];
				$data['store_url'] = $order_info['store_url'];
				$data['customer_id'] = $order_info['customer_id'];
				$data['link'] = $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id;

				if ($download_status) {
					$data['download'] = $order_info['store_url'] . 'index.php?route=account/download';
				} else {
					$data['download'] = '';
				}

				$data['order_id'] = $order_id;
				$data['date_added'] = date($language->get('date_format_short'), strtotime($order_info['date_added']));
				$data['payment_method'] = $order_info['payment_method'];
				$data['shipping_method'] = $order_info['shipping_method'];

		      
				   $xshippingpro_desc_mail=$this->config->get('xshippingpro_desc_mail');
				  
			      if(strstr($order_info['shipping_code'],'xshippingpro')){
				     if ($xshippingpro_desc_mail) {
					     $xshippingpro=$this->config->get('xshippingpro');
						 if ($xshippingpro) {	
							 $xshippingpro=unserialize(base64_decode($xshippingpro)); 
						 }
						$language_id=$order_info['language_id']; 
						if(!is_array($xshippingpro))$xshippingpro=array();  
						if(!isset($xshippingpro['desc']))$xshippingpro['desc']=array();
						foreach($xshippingpro['desc'] as $no_of_tab=>$descs){
						   if($order_info['shipping_code']=='xshippingpro'.'.xshippingpro'.$no_of_tab){
						          
								$data['shipping_method'].=($descs[$language_id])?'<span style="color: #999999;font-size: 11px;display:block" class="x-shipping-desc">'.$descs[$language_id].'</span>':'';
								break;
						   }
						}
				     }
				   }
			   
			
				$data['email'] = $order_info['email'];
				$data['telephone'] = $order_info['telephone'];
				$data['ip'] = $order_info['ip'];
				$data['order_status'] = $order_status;

				if ($comment && $notify) {
					$data['comment'] = nl2br($comment);
				} else {
					$data['comment'] = '';
				}

				if ($order_info['payment_address_format']) {
					$format = $order_info['payment_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}

				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				);

				$replace = array(
					'firstname' => $order_info['payment_firstname'],
					'lastname'  => $order_info['payment_lastname'],
					'company'   => $order_info['payment_company'],
					'address_1' => $order_info['payment_address_1'],
					'address_2' => $order_info['payment_address_2'],
					'city'      => $order_info['payment_city'],
					'postcode'  => $order_info['payment_postcode'],
					'zone'      => $order_info['payment_zone'],
					'zone_code' => $order_info['payment_zone_code'],
					'country'   => $order_info['payment_country']
				);

				$data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				if ($order_info['shipping_address_format']) {
					$format = $order_info['shipping_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}

				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				);

				$replace = array(
					'firstname' => $order_info['shipping_firstname'],
					'lastname'  => $order_info['shipping_lastname'],
					'company'   => $order_info['shipping_company'],
					'address_1' => $order_info['shipping_address_1'],
					'address_2' => $order_info['shipping_address_2'],
					'city'      => $order_info['shipping_city'],
					'postcode'  => $order_info['shipping_postcode'],
					'zone'      => $order_info['shipping_zone'],
					'zone_code' => $order_info['shipping_zone_code'],
					'country'   => $order_info['shipping_country']
				);

				$data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				$this->load->model('tool/upload');

				// Products
				$data['products'] = array();

				foreach ($order_product_query->rows as $product) {
					$option_data = array();

					$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");

					foreach ($order_option_query->rows as $option) {
						if ($option['type'] != 'file') {
							$value = $option['value'];
						} else {
							$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

							if ($upload_info) {
								$value = $upload_info['name'];
							} else {
								$value = '';
							}
						}

						$option_data[] = array(
							'name'  => $option['name'],
							'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
						);
					}

					$data['products'][] = array(
						'name'     => $product['name'],
						'model'    => $product['model'],
						'option'   => $option_data,
						'quantity' => $product['quantity'],
						'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
						'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
					);
				}

				// Vouchers
				$data['vouchers'] = array();

				$order_voucher_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'");

				foreach ($order_voucher_query->rows as $voucher) {
					$data['vouchers'][] = array(
						'description' => $voucher['description'],
						'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
					);
				}

				// Order Totals
				$order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order ASC");

				foreach ($order_total_query->rows as $total) {
					$data['totals'][] = array(
						'title' => $total['title'],
						'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
					);
				}

				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/order.tpl')) {
					$html = $this->load->view($this->config->get('config_template') . '/template/mail/order.tpl', $data);
				} else {
					$html = $this->load->view('default/template/mail/order.tpl', $data);
				}

				// Text Mail
				$text  = sprintf($language->get('text_new_greeting'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8')) . "\n\n";
				$text .= $language->get('text_new_order_id') . ' ' . $order_id . "\n";
				$text .= $language->get('text_new_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n";
				$text .= $language->get('text_new_order_status') . ' ' . $order_status . "\n\n";

				if ($comment && $notify) {
					$text .= $language->get('text_new_instruction') . "\n\n";
					$text .= $comment . "\n\n";
				}

				// Products
				$text .= $language->get('text_new_products') . "\n";

				foreach ($order_product_query->rows as $product) {
					$text .= $product['quantity'] . 'x ' . $product['name'] . ' (' . $product['model'] . ') ' . html_entity_decode($this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";

					$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . $product['order_product_id'] . "'");

					foreach ($order_option_query->rows as $option) {
						if ($option['type'] != 'file') {
							$value = $option['value'];
						} else {
							$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

							if ($upload_info) {
								$value = $upload_info['name'];
							} else {
								$value = '';
							}
						}

						$text .= chr(9) . '-' . $option['name'] . ' ' . (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value) . "\n";
					}
				}

				foreach ($order_voucher_query->rows as $voucher) {
					$text .= '1x ' . $voucher['description'] . ' ' . $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']);
				}

				$text .= "\n";

				$text .= $language->get('text_new_order_total') . "\n";

				foreach ($order_total_query->rows as $total) {
					$text .= $total['title'] . ': ' . html_entity_decode($this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";
				}

				$text .= "\n";

				if ($order_info['customer_id']) {
					$text .= $language->get('text_new_link') . "\n";
					$text .= $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id . "\n\n";
				}

				if ($download_status) {
					$text .= $language->get('text_new_download') . "\n";
					$text .= $order_info['store_url'] . 'index.php?route=account/download' . "\n\n";
				}

				// Comment
				if ($order_info['comment']) {
					$text .= $language->get('text_new_comment') . "\n\n";
					$text .= $order_info['comment'] . "\n\n";
				}

				$text .= $language->get('text_new_footer') . "\n\n";

				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

				$mail->setTo($order_info['email']);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'));
				$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
				$mail->setHtml($html);
				$mail->setText($text);
				
				if($this->config->get('pdf_invoice_auto_generate')){
					$invoice_no = '';
					if ($order_info && !$order_info['invoice_no']) {
						$query = $this->db->query("SELECT MAX(invoice_no) AS invoice_no FROM `" . DB_PREFIX . "order` WHERE invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "'");

						if ($query->row['invoice_no']) {
							$order_info['invoice_no'] = $query->row['invoice_no'] + 1;
						} else {
							$order_info['invoice_no'] = 1;
						}

						$this->db->query("UPDATE `" . DB_PREFIX . "order` SET invoice_no = '" . (int)$order_info['invoice_no'] . "', invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "' WHERE order_id = '" . (int)$order_id . "'");
					}
				}
				
        $this->load->model('tool/pdf_invoice');

        if ($this->config->get('pdf_invoice_mail') || in_array($order_status_id, (array) $this->config->get('pdf_invoice_auto_notify')) || ($order_info['payment_code'] == 'invoicepay' && $this->config->get('invoicepay_forcepdf'))) {
          if (!$this->config->get('pdf_invoice_invoiced') || ($this->config->get('pdf_invoice_invoiced') && $order_info['invoice_no'])) {
            $temp_pdf = $this->model_tool_pdf_invoice->generate($order_id, 'file', 'invoice');
            $mail->addAttachment($temp_pdf);
          }
        }
        
        if ($this->config->get('pdf_invoice_backup') && $this->config->get('pdf_invoice_backup_moment') == 'order') {
          if ((!$this->config->get('pdf_invoice_adminlang') || ($this->config->get('pdf_invoice_adminlang') == $order_info['language_id'])) && isset($temp_pdf)) {
            copy($temp_pdf, $this->model_tool_pdf_invoice->getBackupPath($order_id));
          } else {
            $this->model_tool_pdf_invoice->generate($order_id, 'backup', 'invoice', $this->config->get('pdf_invoice_adminlang'));
          }
        }
				
				$mail->send();
				
				if(isset($temp_pdf) && is_file($temp_pdf)){
					unlink($temp_pdf);
				}
			

				// Admin Alert Mail
				if ($this->config->get('config_order_mail')) {
					$subject = sprintf($language->get('text_new_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'), $order_id);

					// HTML Mail
					$data['text_greeting'] = $language->get('text_new_received');

					if ($comment) {
						if ($order_info['comment']) {
							$data['comment'] = nl2br($comment) . '<br/><br/>' . $order_info['comment'];
						} else {
							$data['comment'] = nl2br($comment);
						}
					} else {
						if ($order_info['comment']) {
							$data['comment'] = $order_info['comment'];
						} else {
							$data['comment'] = '';
						}
					}

					$data['text_download'] = '';

					$data['text_footer'] = '';

					$data['text_link'] = '';
					$data['link'] = '';
					$data['download'] = '';

					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/order.tpl')) {
						$html = $this->load->view($this->config->get('config_template') . '/template/mail/order.tpl', $data);
					} else {
						$html = $this->load->view('default/template/mail/order.tpl', $data);
					}

					// Text
					$text  = $language->get('text_new_received') . "\n\n";
					$text .= $language->get('text_new_order_id') . ' ' . $order_id . "\n";
					$text .= $language->get('text_new_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n";
					$text .= $language->get('text_new_order_status') . ' ' . $order_status . "\n\n";
					$text .= $language->get('text_new_products') . "\n";

					foreach ($order_product_query->rows as $product) {
						$text .= $product['quantity'] . 'x ' . $product['name'] . ' (' . $product['model'] . ') ' . html_entity_decode($this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";

						$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . $product['order_product_id'] . "'");

						foreach ($order_option_query->rows as $option) {
							if ($option['type'] != 'file') {
								$value = $option['value'];
							} else {
								$value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
							}

							$text .= chr(9) . '-' . $option['name'] . ' ' . (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value) . "\n";
						}
					}

					foreach ($order_voucher_query->rows as $voucher) {
						$text .= '1x ' . $voucher['description'] . ' ' . $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']);
					}

					$text .= "\n";

					$text .= $language->get('text_new_order_total') . "\n";

					foreach ($order_total_query->rows as $total) {
						$text .= $total['title'] . ': ' . html_entity_decode($this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";
					}

					$text .= "\n";

					if ($order_info['comment']) {
						$text .= $language->get('text_new_comment') . "\n\n";
						$text .= $order_info['comment'] . "\n\n";
					}

					$mail = new Mail();
					$mail->protocol = $this->config->get('config_mail_protocol');
					$mail->parameter = $this->config->get('config_mail_parameter');
					$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
					$mail->smtp_username = $this->config->get('config_mail_smtp_username');
					$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
					$mail->smtp_port = $this->config->get('config_mail_smtp_port');
					$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

					$mail->setTo($this->config->get('config_email'));

        $this->load->model('tool/pdf_invoice');
      
				if ($this->config->get('pdf_invoice_admincopy')){
					$temp_pdf_admin = $this->model_tool_pdf_invoice->generate($order_id, 'file', 'invoice', $this->config->get('pdf_invoice_adminlang'));
					$mail->addAttachment($temp_pdf_admin);
				}
				
				if($this->config->get('pdf_invoice_packingslip')){
					$temp_pdf_slip = $this->model_tool_pdf_invoice->generate($order_id, 'file', 'packingslip', $this->config->get('pdf_invoice_adminlang'));
					$mail->addAttachment($temp_pdf_slip);
				}
				/* adminalertcopy disabled
				if ($this->config->get('pdf_invoice_adminalertcopy')){
          if (version_compare(VERSION, '2.2', '>=')) {
            $mail->setHtml($this->load->view('mail/order', $data));
          } else {
					  $mail->setHtml($html);
          }
				}
        */
			
					$mail->setFrom($this->config->get('config_email'));
					$mail->setSender(html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'));
					$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
					$mail->setHtml($html);
					$mail->setText($text);
					$mail->send();

					// Send to additional alert emails

					// Send to additional alert emails from custom order
					if(isset($this->request->post['additionalemail']) && !empty($this->request->post['additionalemail'])) {
					$additionalemails = explode(',', $this->request->post['additionalemail']);
	
					foreach ($additionalemails as $additionalemail) {
						if ($additionalemail && filter_var($additionalemail, FILTER_VALIDATE_EMAIL)) {
							$mail->setTo($additionalemail);
							$mail->send();
						}
					}
					}
				
					$emails = explode(',', $this->config->get('config_mail_alert'));

					foreach ($emails as $email) {
						if ($email && preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $email)) {
							$mail->setTo($email);
							
				if (in_array($order_status_id, (array) $this->config->get('pdf_invoice_auto_notify'))) {
          if (!$this->config->get('pdf_invoice_invoiced') || ($this->config->get('pdf_invoice_invoiced') && $order_info['invoice_no'])) {

				if(isset($temp_pdf_slip) && is_file($temp_pdf_slip)){
					unlink($temp_pdf_slip);
				}
				if(isset($temp_pdf_admin) && is_file($temp_pdf_admin)){
					unlink($temp_pdf_admin);
				}
			
            $this->load->model('tool/pdf_invoice');
            $temp_pdf = $this->model_tool_pdf_invoice->generate($order_id, 'file', 'invoice');
            $mail->addAttachment($temp_pdf);
          }
				}
				
				$mail->send();
				
				if (isset($temp_pdf) && is_file($temp_pdf)) {
					unlink($temp_pdf);
				}
			
						}
					}
				}
			}

			// If order status is not 0 then send update text email

		dontsendhtmlemail:
				
			if ($order_info['order_status_id'] && $order_status_id && $notify) {
				$language = new Language($order_info['language_directory']);
				$language->load($order_info['language_directory']);
				$language->load('mail/order');

				$subject = sprintf($language->get('text_update_subject'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'), $order_id);

				$message  = $language->get('text_update_order') . ' ' . $order_id . "<br>";
				$message .= $language->get('text_update_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "<br><br>";

				$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_info['language_id'] . "'");

				if ($order_status_query->num_rows) {
					$message .= $language->get('text_update_order_status') . " <b>";
					$message .= $order_status_query->row['name'] . "</b> <br><br>";
				}

				if ($comment) {
					$sub_comment = substr($comment, strpos($comment,"Order status changed from")+strlen("Order status changed from"),strlen($comment));
					$remove_this = "Order status changed from" . substr($sub_comment,0,strpos($sub_comment,"("));
					$comment = str_replace($remove_this, "", $comment);
					$comment = str_replace("(", " (Sent by ", $comment);
					$message .= $language->get('text_update_comment') . "<br><br>";
					$message .= strip_tags($comment) . "<br><br>";
				}

				if ($order_info['customer_id']) {
					$message .= $language->get('text_update_link') . "<br>";
					$message .= $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id . "<br><br>";
				}

				$message .= $language->get('text_update_footer');

				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

				$mail->setTo($order_info['email']);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'));
				$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
				//$mail->setText($message);
				$mail->setHtml($message);
				$mail->send();
			}
		}

		$this->event->trigger('post.order.history.add', $order_id);
	}
	
	public function getOrderTotal($order_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' AND code = 'total'");
		$result = $query->row;
		if($result)
		{
			$total = number_format((float)$result['value'], 2, '.', '');
			return $total;
		}
	}
	
	public function getOrderTax($order_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' AND code = 'tax'");
		$result = $query->row;
		if($result)
		{
			$tax = number_format((float)$result['value'], 2, '.', '');
			return $tax;
		}
		
		return 0.00;
	}
	
	public function getOrderShipping($order_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' AND code = 'shipping'");
		$result = $query->row;
		if($result)
		{
			$shipping = number_format((float)$result['value'], 2, '.', '');
			return $shipping;
		}
		
		return 0.00;
	}

	public function isValidGiftProduct($product_id)
	{
		$query = $this->db->query("SELECT is_gift_product FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
		$is_gift_product = $query->row ? $query->row['is_gift_product'] : 0;
		if( $is_gift_product == 1 )
		{
			return true;
		} else {
			return false;
		}
	}

	public function addGiftProductToOrder($order_id, $product_id)
	{
		$query = $this->db->query("SELECT p.product_id,pd.name,p.model FROM " . DB_PREFIX . "product p INNER JOIN " . DB_PREFIX . "product_description pd ON p.product_id = pd.product_id  WHERE pd.language_id = 1 AND p.product_id = '" . (int)$product_id . "'");

		$product_info = $query->row;
		if($product_info)
		{
			$name = $product_info['name'] . " (Gift Product)"; 
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$product_id . "', 
			model = '" . $product_info['model'] . "', name = '" . $this->db->escape($name) . "', quantity = 1, price = 0, total = 0, is_gift_product = 1");   
		}
	}
}
