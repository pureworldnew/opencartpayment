<?php 
class ControllerQuickCheckoutConfirm extends Controller { 
	public function index() {
		$redirect = '';
		$this->load->model('catalog/product');
		if ($this->cart->hasShipping()) {
			// Validate if shipping address has been set.
			if (!isset($this->session->data['shipping_address'])) {
				$redirect = $this->url->link('checkout/checkout', '', true);
			}

			// Validate if shipping method has been set.
			if (!isset($this->session->data['shipping_method'])) {
				$redirect = $this->url->link('checkout/checkout', '', true);
			}
		} else {
			unset($this->session->data['shipping_address']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
		}
		
		// Validate if payment address has been set.
		if (!isset($this->session->data['payment_address'])) {
			$redirect = $this->url->link('checkout/checkout', '', true);
		}

		// Validate if payment method has been set.
		if (!isset($this->session->data['payment_method'])) {
			$redirect = $this->url->link('checkout/checkout', '', true);
		}

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$redirect = $this->url->link('checkout/cart');
		}
		
		// Validate minimum quantity requirements.
		$products = $this->cart->getProducts();
		$pricevalue=0;
		$producttotal = 0;
		foreach ($products as $product) {
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$redirect = $this->url->link('checkout/cart');

				break;
			}
			if(null!==$this->config->get('subcategorypercentage_category')){
				$categories=implode(",",$this->config->get('subcategorypercentage_category'));
			}else{
				$categories=0;
			}
			 
		}
		
		if (!$redirect) {
			$order_data = array();

			$order_data['totals'] = array();
			$total = 0;
			$taxes = $this->cart->getTaxes();
			
			if (version_compare(VERSION, '2.2.0.0', '>=')) {
				$total_data = array(
					'totals' => &$totals,
					'taxes'  => &$taxes,
					'total'  => &$total
				);
			}

			$this->load->model('extension/extension');

			$sort_order = array();

			$results = $this->model_extension_extension->getExtensions('total');

			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
			}

			array_multisort($sort_order, SORT_ASC, $results);

			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('total/' . $result['code']);

					if (version_compare(VERSION, '2.2.0.0', '<')) {
						$this->{'model_total_' . $result['code']}->getTotal($order_data['totals'], $total, $taxes);
					} else {
						$this->{'model_total_' . $result['code']}->getTotal($total_data);
					}
				}
			}
			
			if (version_compare(VERSION, '2.2.0.0', '>=')) {
				$order_data['totals'] = $totals;
			}

			$sort_order = array();
			
			foreach ($order_data['totals'] as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $order_data['totals']);

			$data = $this->load->language('checkout/checkout');
			$data = array_merge($data, $this->load->language('quickcheckout/checkout'));

			$order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
			$order_data['store_id'] = $this->config->get('config_store_id');
			$order_data['store_name'] = $this->config->get('config_name');

			if ($order_data['store_id']) {
				$order_data['store_url'] = $this->config->get('config_url');
			} else {
				$order_data['store_url'] = HTTP_SERVER;
			}

			if ($this->customer->isLogged()) {
				$this->load->model('account/customer');

				$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

				$order_data['customer_id'] = $this->customer->getId();
				$order_data['customer_group_id'] = $customer_info['customer_group_id'];
				$order_data['firstname'] = $customer_info['firstname'];
				$order_data['lastname'] = $customer_info['lastname'];
				$order_data['email'] = $customer_info['email'];
				$order_data['telephone'] = $customer_info['telephone'];
				$order_data['fax'] = $customer_info['fax'];
				
				if (version_compare(VERSION, '2.1.0.0', '>=')) {
					$order_data['custom_field'] = json_decode($customer_info['custom_field']);
				} else {
					$order_data['custom_field'] = unserialize($customer_info['custom_field']);
				}
			} elseif (isset($this->session->data['guest'])) {
				$order_data['customer_id'] = 0;
				$order_data['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
				$order_data['firstname'] = $this->session->data['guest']['firstname'];
				$order_data['lastname'] = $this->session->data['guest']['lastname'];
				$order_data['email'] = $this->session->data['guest']['email'];
				$order_data['telephone'] = $this->session->data['guest']['telephone'];
				$order_data['fax'] = $this->session->data['guest']['fax'];
				$order_data['custom_field'] = $this->session->data['guest']['custom_field'];
			}

			$order_data['payment_firstname'] = $this->session->data['payment_address']['firstname'];
			$order_data['payment_lastname'] = $this->session->data['payment_address']['lastname'];
			$order_data['payment_company'] = $this->session->data['payment_address']['company'];
			$order_data['payment_address_1'] = $this->session->data['payment_address']['address_1'];
			$order_data['payment_address_2'] = $this->session->data['payment_address']['address_2'];
			$order_data['payment_city'] = $this->session->data['payment_address']['city'];
			$order_data['payment_postcode'] = $this->session->data['payment_address']['postcode'];
			$order_data['payment_zone'] = $this->session->data['payment_address']['zone'];
			$order_data['payment_zone_id'] = $this->session->data['payment_address']['zone_id'];
			$order_data['payment_country'] = $this->session->data['payment_address']['country'];
			$order_data['payment_country_id'] = $this->session->data['payment_address']['country_id'];
			$order_data['payment_address_format'] = $this->session->data['payment_address']['address_format'];
			$order_data['payment_custom_field'] = $this->session->data['payment_address']['custom_field'];

			if (isset($this->session->data['payment_method']['title'])) {
				$order_data['payment_method'] = $this->session->data['payment_method']['title'];
			} else {
				$order_data['payment_method'] = '';
			}

			if (isset($this->session->data['payment_method']['code'])) {
				$order_data['payment_code'] = $this->session->data['payment_method']['code'];
			} else {
				$order_data['payment_code'] = '';
			}

			if ($this->cart->hasShipping()) {
				$order_data['shipping_firstname'] = $this->session->data['shipping_address']['firstname'];
				$order_data['shipping_lastname'] = $this->session->data['shipping_address']['lastname'];
				$order_data['shipping_company'] = $this->session->data['shipping_address']['company'];
				$order_data['shipping_address_1'] = $this->session->data['shipping_address']['address_1'];
				$order_data['shipping_address_2'] = $this->session->data['shipping_address']['address_2'];
				$order_data['shipping_city'] = $this->session->data['shipping_address']['city'];
				$order_data['shipping_postcode'] = $this->session->data['shipping_address']['postcode'];
				$order_data['shipping_zone'] = $this->session->data['shipping_address']['zone'];
				$order_data['shipping_zone_id'] = $this->session->data['shipping_address']['zone_id'];
				$order_data['shipping_country'] = $this->session->data['shipping_address']['country'];
				$order_data['shipping_country_id'] = $this->session->data['shipping_address']['country_id'];
				$order_data['shipping_address_format'] = $this->session->data['shipping_address']['address_format'];
				$order_data['shipping_custom_field'] = $this->session->data['shipping_address']['custom_field'];

				
				if (isset($this->session->data['shipping_method']['title'])) {
					$order_data['shipping_method'] = $this->session->data['shipping_method']['title'];
				} else {
					$order_data['shipping_method'] = '';
				}
				
				if (isset($this->session->data['shipping_method']['desc'])) {
					$order_data['shipping_method_desc'] = $this->session->data['shipping_method']['desc'];
				} else {
					$order_data['shipping_method_desc'] = '';
				}

				if (isset($this->session->data['shipping_method']['code'])) {
					$order_data['shipping_code'] = $this->session->data['shipping_method']['code'];
				} else {
					$order_data['shipping_code'] = '';
				}
			} else {
				$order_data['shipping_firstname'] = '';
				$order_data['shipping_lastname'] = '';
				$order_data['shipping_company'] = '';
				$order_data['shipping_address_1'] = '';
				$order_data['shipping_address_2'] = '';
				$order_data['shipping_city'] = '';
				$order_data['shipping_postcode'] = '';
				$order_data['shipping_zone'] = '';
				$order_data['shipping_zone_id'] = '';
				$order_data['shipping_country'] = '';
				$order_data['shipping_country_id'] = '';
				$order_data['shipping_address_format'] = '';
				$order_data['shipping_custom_field'] = array();
				$order_data['shipping_method'] = '';
				$order_data['shipping_code'] = '';
			}

			$order_data['products'] = array();

			foreach ($this->cart->getProducts() as $product) {
				$pricevalue = 0;
				$option_data = array();
				$getWiredProducts=$this->db->query("SELECT * FROM ".DB_PREFIX."product_to_category WHERE product_id='".$product['product_id']."' AND category_id IN(".$categories.") ");
				 
				if($getWiredProducts->num_rows){
					$pricevalue += $product['total'];
				}
				if($pricevalue){
					  if(null!==$this->config->get('config_additional_percentage')){
							$additional_percentage= $this->config->get('config_additional_percentage')/100;
							$total_val=$pricevalue*$additional_percentage;
						 }
						
					 if(isset($total_val)){
						$producttotal+=$total_val;
							 }
						}
				foreach ($product['option'] as $option) {
					$option_data[] = array(
						'product_option_id'       => $option['product_option_id'],
						'product_option_value_id' => $option['product_option_value_id'],
						'option_id'               => $option['option_id'],
						'option_value_id'         => $option['option_value_id'],
						'name'                    => $option['name'],
						'value'                   => $option['value'],
						'type'                    => $option['type']
					);
				}
				if(empty($product['unit'])){
					$product['unit']['unit_conversion_values'] = 0;
					$product['unit']['convert_price'] = 1;
				}
				
				//$proprice = ;
				
				$order_data['products'][] = array(
					'product_id' => $product['product_id'],
					'name'       => $product['name'],
					'model'      => $product['model'],
					'option'     => $option_data,
					'download'   => $product['download'],
					'unit' 		 => $product['unit'],
					'quantity' => $product['quantity'],//($product['quantity'] * $product['unit']['convert_price']),
                    'subtract' => $product['subtract'],
                    'price' => $product['price'],// /$product['unit']['convert_price'],
                    'total' => $product['total'],
					'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
					'reward'     => $product['reward']
				);
			}
			
			
			
			// Gift Voucher
			$order_data['vouchers'] = array();

			if (!empty($this->session->data['vouchers'])) {
				foreach ($this->session->data['vouchers'] as $voucher) {
					$order_data['vouchers'][] = array(
						'description'      => $voucher['description'],
						'code'             => substr(md5(mt_rand()), 0, 10),
						'to_name'          => $voucher['to_name'],
						'to_email'         => $voucher['to_email'],
						'from_name'        => $voucher['from_name'],
						'from_email'       => $voucher['from_email'],
						'voucher_theme_id' => $voucher['voucher_theme_id'],
						'message'          => $voucher['message'],
						'amount'           => $voucher['amount']
					);
				}
			}
			
			if (!isset($this->session->data['order_comment'])) { 
				$this->session->data['order_comment'] = '';
			}
			
			if (!isset($this->session->data['survey'])) { 
				$this->session->data['survey'] = '';
			}
			
			if (!isset($this->session->data['delivery_date'])) {
				$this->session->data['delivery_date'] = '';
			}
			
			if (!isset($this->session->data['delivery_time'])) {
				$this->session->data['delivery_time'] = '';
			}
			
			$this->session->data['comment'] = '';
			
			if ($this->session->data['order_comment'] != '') {
				$this->session->data['comment'] .= $this->language->get('text_order_comments') . ' ' . $this->session->data['order_comment'];
			}
			
			if ($this->session->data['survey'] != '') {
				$this->session->data['comment'] .= "\n\n" . $this->language->get('text_survey') . ' ' . $this->session->data['survey'];
			}
			
			if ($this->session->data['delivery_date'] != '') {
				$this->session->data['comment'] .= "\n\n" . $this->language->get('text_delivery') . ' ' . $this->session->data['delivery_date'];
				
				if ($this->session->data['delivery_time'] != '') {
					$this->session->data['comment'] .= ' ' . $this->session->data['delivery_time'];
				}
			}

			$order_data['comment'] = $this->session->data['comment'];
			$order_data['total'] = $total+$producttotal;
			$order_data['additional_cost'] = $producttotal;

			if (isset($this->request->cookie['tracking'])) {
				$order_data['tracking'] = $this->request->cookie['tracking'];

				$subtotal = $this->cart->getSubTotal();

				// Affiliate
				$this->load->model('affiliate/affiliate');

				$affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);

				if ($affiliate_info) {
					$order_data['affiliate_id'] = $affiliate_info['affiliate_id'];
					$order_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
				} else {
					$order_data['affiliate_id'] = 0;
					$order_data['commission'] = 0;
				}

				// Marketing
				$this->load->model('checkout/marketing');

				$marketing_info = $this->model_checkout_marketing->getMarketingByCode($this->request->cookie['tracking']);

				if ($marketing_info) {
					$order_data['marketing_id'] = $marketing_info['marketing_id'];
				} else {
					$order_data['marketing_id'] = 0;
				}
			} else {
				$order_data['affiliate_id'] = 0;
				$order_data['commission'] = 0;
				$order_data['marketing_id'] = 0;
				$order_data['tracking'] = '';
			}

			$order_data['language_id'] = $this->config->get('config_language_id');
			
			if (version_compare(VERSION, '2.2.0.0', '<')) {
				$order_data['currency_id'] = $this->currency->getId();
				$order_data['currency_code'] = $this->currency->getCode();
				$order_data['currency_value'] = $this->currency->getValue($this->currency->getCode());
			} else {
				$order_data['currency_id'] = $this->currency->getId($this->session->data['currency']);
				$order_data['currency_code'] = $this->session->data['currency'];
				$order_data['currency_value'] = $this->currency->getValue($this->session->data['currency']);
			}
			
			$order_data['ip'] = $this->request->server['REMOTE_ADDR'];

			if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
				$order_data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
			} elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
				$order_data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
			} else {
				$order_data['forwarded_ip'] = '';
			}

			if (isset($this->request->server['HTTP_USER_AGENT'])) {
				$order_data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
			} else {
				$order_data['user_agent'] = '';
			}

			if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
				$order_data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
			} else {
				$order_data['accept_language'] = '';
			}
			
			if (isset($this->session->data['resale_id_number'])) {
                $order_data['resale_text'] = $this->session->data['resale_id_number'];
            } else {
                $order_data['resale_text'] = '';
            }

			$this->load->model('checkout/order');

			$this->session->data['order_id'] = $this->model_checkout_order->addOrder($order_data);
			$json['order_data']=$order_data;
			$json['order_id']=$this->session->data['order_id'];
            $this->cart->writeLogForOrder('confirm_order_added',serialize($json),$this->session->data['order_id']);

			$data['text_recurring_item'] = $this->language->get('text_recurring_item');
			$data['text_payment_recurring'] = $this->language->get('text_payment_recurring');

			$data['column_name'] = $this->language->get('column_name');
			$data['column_model'] = $this->language->get('column_model');
			$data['column_quantity'] = $this->language->get('column_quantity');
			$data['column_price'] = $this->language->get('column_price');
			$data['column_total'] = $this->language->get('column_total');
			$data['order_data'] = $order_data;
			$this->load->model('tool/image');
			$this->load->model('tool/upload');

				$data['products'] = array();
		
			$products = $this->cart->getProducts();
				
			foreach ($products as $product) {
					$product_total = 0;
	
					foreach ($products as $product_2) {
						if ($product_2['product_id'] == $product['product_id']) {
							$product_total += $product_2['quantity'];
						}
					}
	
					if ($product['minimum'] > $product_total) {
						$data['error_warning'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);
					}
	
					if ($product['image']) {
						$image = $this->model_tool_image->resize($product['image'], 50, 50);
					} else {
						$image = '';
					}
	
					
					
									$option_data = array();
	
	
					foreach ($product['option'] as $option) {
						if ($option['type'] != 'file') {
							$value = $option['value'];
						} else {
							$filename = $this->encryption->decrypt($option['value']);
	
							$value = utf8_substr($filename, 0, utf8_strrpos($filename, '.'));
						}
						if ($option['name'] == 'Units' ||
								$option['name'] == "Select Overall length" ||
								$option['name'] == "Select width" ||
								$option['name'] == "Weight" ||
								$option['name'] == "Select weight"
						) {
							continue;
						}
	
	
			$gp_option_out_stock = '';
			if($product['model']=='grouped'){
				$gp_option_qty_q = $this->db->query("SELECT quantity FROM ".DB_PREFIX."product WHERE product_id='".(int)$option['product_option_id']."' LIMIT 1");
				if($gp_option_qty_q->num_rows){
					if(($gp_option_qty_q->row['quantity'] < $option['product_option_value_id']) && $this->config->get('config_stock_warning')){
						$gp_option_out_stock = '<span class="stock" title="'.$this->language->get('error_stock').'" style="cursor:help;">***</span>';
					}
				}
			}
			
						$option_data[] = array(
	
				'gp_option_out_stock' => $gp_option_out_stock,
			
							'name' => $option['name'],
							'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
						);
					} 
					$to = "";
					$to_weight = "";
					//Units to be converted to
					foreach ($product['option'] as $option) {
						if ($option['name'] == 'Units') {
							$to = $option['value'];
	
			$gp_option_out_stock = '';
			if($product['model']=='grouped'){
				$gp_option_qty_q = $this->db->query("SELECT quantity FROM ".DB_PREFIX."product WHERE product_id='".(int)$option['product_option_id']."' LIMIT 1");
				if($gp_option_qty_q->num_rows){
					if(($gp_option_qty_q->row['quantity'] < $option['product_option_value_id']) && $this->config->get('config_stock_warning')){
						$gp_option_out_stock = '<span class="stock" title="'.$this->language->get('error_stock').'" style="cursor:help;">***</span>';
					}
				}
			}
			
							$option_data[] = array(
	
				'gp_option_out_stock' => $gp_option_out_stock,
			
								'name' => $option['name'],
								'value' => $option['value']
							);
							continue;
						}
						//units to be converted from
						if ($option['name'] == "Select Overall length") {
							$a = $option['value'];
							$val = floatval($a);
							$b = explode($val, $a);
							$from = $b[1];
							$value_length = $this->lengthId($val, $from, $to);
	
			$gp_option_out_stock = '';
			if($product['model']=='grouped'){
				$gp_option_qty_q = $this->db->query("SELECT quantity FROM ".DB_PREFIX."product WHERE product_id='".(int)$option['product_option_id']."' LIMIT 1");
				if($gp_option_qty_q->num_rows){
					if(($gp_option_qty_q->row['quantity'] < $option['product_option_value_id']) && $this->config->get('config_stock_warning')){
						$gp_option_out_stock = '<span class="stock" title="'.$this->language->get('error_stock').'" style="cursor:help;">***</span>';
					}
				}
			}
			
							$option_data[] = array(
	
				'gp_option_out_stock' => $gp_option_out_stock,
			
								'name' => $option['name'],
								'value' => round($value_length, 2) . $to
							);
						}
						if ($option['name'] == "Select width") {
							$a = $option['value'];
							$val = floatval($a);
							$b = explode($val, $a);
							$from = $b[1];
							$value_length = $this->lengthId($val, $from, $to);
	
			$gp_option_out_stock = '';
			if($product['model']=='grouped'){
				$gp_option_qty_q = $this->db->query("SELECT quantity FROM ".DB_PREFIX."product WHERE product_id='".(int)$option['product_option_id']."' LIMIT 1");
				if($gp_option_qty_q->num_rows){
					if(($gp_option_qty_q->row['quantity'] < $option['product_option_value_id']) && $this->config->get('config_stock_warning')){
						$gp_option_out_stock = '<span class="stock" title="'.$this->language->get('error_stock').'" style="cursor:help;">***</span>';
					}
				}
			}
			
							$option_data[] = array(
	
				'gp_option_out_stock' => $gp_option_out_stock,
			
								'name' => $option['name'],
								'value' => round($value_length, 2) . $to
							);
						}
	
						//Weight class convertor
						if ($option['name'] == 'Weight') {
							$to_weight = $option['value'];
	
			$gp_option_out_stock = '';
			if($product['model']=='grouped'){
				$gp_option_qty_q = $this->db->query("SELECT quantity FROM ".DB_PREFIX."product WHERE product_id='".(int)$option['product_option_id']."' LIMIT 1");
				if($gp_option_qty_q->num_rows){
					if(($gp_option_qty_q->row['quantity'] < $option['product_option_value_id']) && $this->config->get('config_stock_warning')){
						$gp_option_out_stock = '<span class="stock" title="'.$this->language->get('error_stock').'" style="cursor:help;">***</span>';
					}
				}
			}
			
							$option_data[] = array(
	
				'gp_option_out_stock' => $gp_option_out_stock,
			
								'name' => $option['name'],
								'value' => $option['value']
							);
							continue;
						}
						
						//units to be converted from
						if ($option['name'] == "Select weight") {
							$a = $option['value'];
							$val = (double) $a;
							$b = explode($val, $a);
							$from = $b[1];
							$value_length = $this->weightId($val, $from, $to_weight);
	
			$gp_option_out_stock = '';
			if($product['model']=='grouped'){
				$gp_option_qty_q = $this->db->query("SELECT quantity FROM ".DB_PREFIX."product WHERE product_id='".(int)$option['product_option_id']."' LIMIT 1");
				if($gp_option_qty_q->num_rows){
					if(($gp_option_qty_q->row['quantity'] < $option['product_option_value_id']) && $this->config->get('config_stock_warning')){
						$gp_option_out_stock = '<span class="stock" title="'.$this->language->get('error_stock').'" style="cursor:help;">***</span>';
					}
				}
			}
			
							$option_data[] = array(
	
				'gp_option_out_stock' => $gp_option_out_stock,
			
								'name' => $option['name'],
								'value' => round($value_length, 2) . $to_weight
							);
						}
					}
	
					// Display prices
					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						//$price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
						if(!empty($product['unit'])){
							$price = $this->currency->format($this->tax->calculate(($product['price']/$product['unit']['convert_price']), $product['tax_class_id'], $this->config->get('config_tax')));
						}else{
							$price = $this->currency->format($this->tax->calculate(($product['price']), $product['tax_class_id'], $this->config->get('config_tax')));	
						}
					} else {
						$price = false;
					}
	
					// Display prices
					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						//$total = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']);
						if(!empty($product['unit'])){
							$total = $this->currency->format($this->tax->calculate(($product['price']/$product['unit']['convert_price']), $product['tax_class_id'], $this->config->get('config_tax')) * ($product['quantity'] * $product['unit']['convert_price']));
						}else{
							$total = $this->currency->format($this->tax->calculate(($product['price']), $product['tax_class_id'], $this->config->get('config_tax')) * ($product['quantity']));
						}
					} else {
						$total = false;
					}
	
					$recurring = '';
	
					if ($product['recurring']) {
						$frequencies = array(
							'day'        => $this->language->get('text_day'),
							'week'       => $this->language->get('text_week'),
							'semi_month' => $this->language->get('text_semi_month'),
							'month'      => $this->language->get('text_month'),
							'year'       => $this->language->get('text_year'),
						);
	
						if ($product['recurring']['trial']) {
							$recurring = sprintf($this->language->get('text_trial_description'), $this->currency->format($this->tax->calculate($product['recurring']['trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['trial_cycle'], $frequencies[$product['recurring']['trial_frequency']], $product['recurring']['trial_duration']) . ' ';
						}
	
						if ($product['recurring']['duration']) {
							$recurring .= sprintf($this->language->get('text_payment_description'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
						} else {
							$recurring .= sprintf($this->language->get('text_payment_cancel'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
						}
					}
								$gp_config_q = $this->db->query("SELECT pg_type FROM " . DB_PREFIX . "product_grouped_type WHERE product_id = '" . (int)$product['product_id'] . "' AND pg_type = 'configurable'");
				
				if ($gp_config_q->num_rows) {	
					if((!(float)$product['price'] && $this->config->get('config_customer_price') && $this->customer->isLogged())
					|| (!(float)$product['price'] && !$this->config->get('config_customer_price'))) {
						$config_price = 0;
						$config_total = 0;
						
						foreach ($product['option'] as $gp_option) {
							$config_price += $this->tax->calculate($gp_option['price'], $gp_option['tax_class_id'], $this->config->get('config_tax')) * $gp_option['product_option_value_id'];
							$config_total += $this->tax->calculate($gp_option['price'], $gp_option['tax_class_id'], $this->config->get('config_tax')) * $gp_option['product_option_value_id'] * $product['quantity'];
						}
						
						$price = $this->currency->format($config_price);
						$total = $this->currency->format($config_total);
					}
					
					$cset = $this->url->link('product/product_configurable', '&product_id=' . $product['product_id'] . '&cset=' . str_replace($product['product_id'].':', '', $product['key']));
				} else {
					$cset = false;
				}
			
	
				$gp_config_q = $this->db->query("SELECT pg_type FROM " . DB_PREFIX . "product_grouped_type WHERE product_id = '" . (int)$product['product_id'] . "' AND pg_type = 'configurable'");
					
				if ($gp_config_q->num_rows && $product['reward'] == 1) {
					$product_reward_sum = 0;
					$product_reward_apply = true;
					
					foreach ($product['option'] as $configurable_option) {
						if ($configurable_option['points']) {
							$product_reward_sum += $configurable_option['points'];
						} else {
							$product_reward_apply = false;
						}
					}
					
					$product['reward'] = $product_reward_apply ? $product_reward_sum : 0;
				}
			
				if ($product['model'] == 'grouped') { $product['model'] = $this->language->get('text_mask_model'); }
				if(!empty($product['unit'])){
					$product['quantity'] = $product['quantity'] * $product['unit']['convert_price'];
					//$product['unit']['convert_price'] = 1;
				}
					$data['products'][] = array(
						'cart_id'   => $product['cart_id'],
						'key'   => $product['cart_id'],
						'thumb'     => $image,
						'name'      => $product['name'],
						'model'     => $product['model'],
						'option'    => $option_data,
						'unit' 		=> 		$product['unit'],
						'recurring' => $recurring,
						'quantity'  => $product['quantity'],
						'stock'     => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
						'reward'    => ($product['reward'] ? sprintf($this->language->get('text_points'), $product['reward']) : ''),
						'price'     => $this->currency->format2d($price),
						'total'     => $total,
						'href'      => $this->url->link('product/product', 'product_id=' . $product['product_id']),
						'unit_dates' =>$this->model_catalog_product->getUnitDetails($product['product_id']),
						'unit_dates_default' =>$this->model_catalog_product->getDefaultUnitDetails($product['product_id']),
						'DefaultUnitName' =>$this->model_catalog_product->getDefaultUnitName($product['product_id']),
					);
				}
			// Gift Voucher
			$data['vouchers'] = array();

			if (!empty($this->session->data['vouchers'])) {
				foreach ($this->session->data['vouchers'] as $voucher) {
					if (version_compare(VERSION, '2.2.0.0', '<')) {
						$amount = $this->currency->format($voucher['amount']);
					} else {
						$amount = $this->currency->format($voucher['amount'], $this->session->data['currency']);
					}
					
					$data['vouchers'][] = array(
						'description' => $voucher['description'],
						'amount'      => $amount
					);
				}
			}

			$data['totals'] = array();
			$this->session->data['additional_cost_for_wire'] = '';
			if ($producttotal){
				$this->session->data['additional_cost_for_wire'] = $producttotal;
			}
		if($producttotal && $this->config->get('subcategorypercentage_status')){
					//$this->session->data['additional_cost_for_wire'] = $producttotal;
					$data['totals'][]=array('desc'=>'additional','title'=>'Authorization for Additional cost of wire','text'=>$this->currency->format($producttotal, $this->session->data['currency']));
					
					}
			foreach ($order_data['totals'] as $total) {
				if($total['title'] == 'Total'){
					if($producttotal){
					if (version_compare(VERSION, '2.2.0.0', '<')) {
						$text = $this->currency->format($total['value']+$producttotal);
					} else {
						$text = $this->currency->format(($total['value']+$producttotal), $this->session->data['currency']);
					}
					if(isset($total['desc'])){
						$data['totals'][] = array(
							'title' => $total['title'],
							'desc' => $total['desc'],
							'text'  => $text,
						);	
					}else{
						$data['totals'][] = array(
							'title' => $total['title'],
							'text'  => $text,
						);
					}
				}
					else{
					if (version_compare(VERSION, '2.2.0.0', '<')) {
						$text = $this->currency->format($total['value']);
					} else {
						$text = $this->currency->format($total['value'], $this->session->data['currency']);
					}
					if(isset($total['desc'])){
						$data['totals'][] = array(
							'title' => $total['title'],
							'desc' => $total['desc'],
							'text'  => $text,
						);	
					}else{
						$data['totals'][] = array(
							'title' => $total['title'],
							'text'  => $text,
						);
					}
				}
				}else{
					if (version_compare(VERSION, '2.2.0.0', '<')) {
						$text = $this->currency->format($total['value']);
					} else {
						$text = $this->currency->format($total['value'], $this->session->data['currency']);
					}
					if(isset($total['desc'])){
						$data['totals'][] = array(
							'title' => $total['title'],
							'desc' => $total['desc'],
							'text'  => $text,
						);	
					}else{
						$data['totals'][] = array(
							'title' => $total['title'],
							'text'  => $text,
						);
					}
				}
			}

			$data['payment'] = $this->load->controller('payment/' . $this->session->data['payment_method']['code']);
		} else {
			$data['redirect'] = $redirect;
		}

		// All variables
		$data['confirmation_page'] = $this->config->get('quickcheckout_confirmation_page');
		$data['auto_submit'] = $this->config->get('quickcheckout_auto_submit');
		$data['button_back'] = $this->language->get('button_back');
		$data['payment_target'] = html_entity_decode($this->config->get('quickcheckout_payment_target'), ENT_QUOTES);
		$data['back'] = $this->url->link('quickcheckout/checkout', '', true);
		$data['back_to_cart'] = $this->url->link('checkout/cart', '', true);
		$user_agent = isset($this->request->server['HTTP_USER_AGENT'])?$this->request->server['HTTP_USER_AGENT']:'';
		
		
		if($this->isMobile($user_agent) || $this->isTablet($user_agent)){
			if (version_compare(VERSION, '2.2.0.0', '<')) {
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/quickcheckout/mobile_confirm.tpl')) {
					$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/quickcheckout/mobile_confirm.tpl', $data));
				} else {
					$this->response->setOutput($this->load->view('default/template/quickcheckout/mobile_confirm.tpl', $data));
				}
			} else {
				$this->response->setOutput($this->load->view('quickcheckout/confirm', $data));
			}
		}else{
			if (version_compare(VERSION, '2.2.0.0', '<')) {
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/quickcheckout/confirm.tpl')) {
					$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/quickcheckout/confirm.tpl', $data));
				} else {
					$this->response->setOutput($this->load->view('default/template/quickcheckout/confirm.tpl', $data));
				}
			} else {
				$this->response->setOutput($this->load->view('quickcheckout/confirm', $data));
			}
			
		}
  	}
	
	public function isMobile($user_agent) {
		$mobile = false;
		if(stripos($user_agent,"iPod") || stripos($user_agent,"iPhone") || stripos($user_agent,"webOS") || stripos($user_agent,"BlackBerry") || stripos($user_agent,"windows phone") || stripos($user_agent,"symbian") || stripos($user_agent,"vodafone") || stripos($user_agent,"opera mini") || stripos($user_agent,"windows ce") || stripos($user_agent,"smartphone") || stripos($user_agent,"palm") || stripos($user_agent,"midp")) {
			$mobile = true;
		}
		if(stripos($user_agent,"Android") && stripos($user_agent,"mobile")){
		    $mobile = true;
		}else if(stripos($user_agent,"Android")){
	    	$mobile = false;
	   	}
		
		return $mobile;
	}
	
	public function isTablet($user_agent) {
		$tablet = false;
		if(stripos($user_agent,"Android") && stripos($user_agent,"mobile")){
		    $tablet = false;
		}else if(stripos($user_agent,"Android")){
	    	$tablet = true;
	   	}
		if(stripos($user_agent,"iPad") || stripos($user_agent,"RIM Tablet") || stripos($user_agent,"hp-tablet") || stripos($user_agent,"Kindle Fire")) {
			$tablet = true;
		}
		
		return $tablet;
	}
}