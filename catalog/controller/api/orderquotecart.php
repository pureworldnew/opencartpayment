<?php
class ControllerApiorderquotecart extends Controller {
	public function add() {
		$this->load->language('api/cart');

		$json = array();
		
		if (isset($this->request->post['order_id'])) {
				if(isset($this->session->data['order_id'])) unset($this->session->data['order_id']);
				if(isset($this->session->data['orderquotecart_id'])) unset($this->session->data['orderquotecart_id']);
				
				$this->session->data['order_id'] = $this->request->post['order_id'];
				$this->session->data['orderquotecart_id'] = $this->request->post['order_id'];
		}
		
		if (!isset($this->session->data['api_id'])) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->post['product'])) {
				$this->cart->clear();

				foreach ($this->request->post['product'] as $product) {
					//print_r($product);
					if (isset($product['option'])) {
						$option = $product['option'];
					} else {
						$option = array();
					}
					if(isset($product['unit_conversion_values'])){
                                    $convert_id = $product['unit_conversion_values'];
                    } else {
                                    $convert_id = 0;
                    }
					
					$product_id = $product['product_id'];
					$quantity = $product['quantity'];
					$quantity_supplied = $product['quantity_supplied'];
					if(!empty($convert_id) || $convert_id != 0){
						$sql_unit_sql = "SELECT unit_id, unit_value_id, convert_price FROM " . DB_PREFIX . "unit_conversion_product WHERE unit_conversion_product_id = $convert_id AND product_id = $product_id";
						$res_unit_sql = $this->db->query($sql_unit_sql);
						if($res_unit_sql->num_rows >0){
							$unit_id = $res_unit_sql->row['unit_id'];
							$unit_value_id = $res_unit_sql->row['unit_value_id'];
							$convert_price = $res_unit_sql->row['convert_price'];
							//echo '~'.$convert_price;
							$quantity = $product['quantity'];// / $convert_price;
							//echo '~';
					    }
					}
				if($quantity_supplied==""){
					$quantity_supplied=NULL;
				}
					
					$this->cart->add($product_id, $quantity, $option,0,$convert_id,$quantity_supplied,1);
					//$this->cart->add($product['product_id'], $product['quantity'], $option);
				}

				$json['success'] = $this->language->get('text_success');

				/*unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);*/
			} 
			elseif (isset($this->request->post['product_id'])) {
				$this->load->model('catalog/product');

				$product_info = $this->model_catalog_product->getProduct($this->request->post['product_id']);

				if ($product_info) {
					if (isset($this->request->post['quantity'])) {
						$quantity = $this->request->post['quantity'];
					} else {
						$quantity = 1;
					}
					if (isset($this->request->post['quantity_supplied'])) {
						$quantity_supplied = $this->request->post['quantity_supplied'];
					} else {
						$quantity_supplied = 0;
					}
					
					if (isset($this->request->post['option'])) {
						$option = array_filter($this->request->post['option']);
					} else {
						$option = array();
					}

					$product_options = $this->model_catalog_product->getProductOptions($this->request->post['product_id']);

					foreach ($product_options as $product_option) {
						if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
							$json['error']['option'][$product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
						}
					}

					if (!isset($json['error']['option'])) {
						
						if(isset($this->request->post['unit_conversion_values'])){
                            $convert_id = $this->request->post['unit_conversion_values'];
						} else {
							$convert_id = 0;
						}
                        $this->cart->add($this->request->post['product_id'], $quantity, $option,0,$convert_id,$quantity_supplied,1);
						//$this->cart->add($this->request->post['product_id'], $quantity, $option, $recurring_id,$unit_conversion_values);
						//$this->cart->add($this->request->post['product_id'], $quantity, $option);

						$json['success'] = $this->language->get('text_success');

						unset($this->session->data['shipping_method']);
						unset($this->session->data['shipping_methods']);
						unset($this->session->data['payment_method']);
						unset($this->session->data['payment_methods']);
					}
				} else {
					$json['error']['store'] = $this->language->get('error_store');
				}
			}
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function edit() {
		$this->load->language('api/cart');

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->cart->update($this->request->post['key'], $this->request->post['quantity']);

			$json['success'] = $this->language->get('text_success');

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['reward']);
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function remove() {
		$this->load->language('api/cart');

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			// Remove
			if (isset($this->request->post['key'])) {
				$this->cart->remove($this->request->post['key']);

				unset($this->session->data['vouchers'][$this->request->post['key']]);

				$json['success'] = $this->language->get('text_success');

				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);
				unset($this->session->data['reward']);
			}
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function refreshprices()
	{
		$this->load->language('api/cart');
		$this->load->model('catalog/product');
		$this->load->model('api/order');
		$this->load->model('api/coupon');
		$this->load->model('total/coupon');
		$this->load->model('tool/image');
		
		$json = array();

		if (!isset($this->session->data['api_id']))
		{
			$json['error']['warning'] = $this->language->get('error_permission');
		}
		else
		{
			$this->session->data['refreshproducts'] = TRUE;
			$this->add();
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode("done"));
		
	}
	
	public function updateshipping()
	{
		$this->load->model('api/order');
		$this->model_api_order->UpdateOrderShipping($this->request->post);
		$json['success']  = 'Shipping Updated';
		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function checkfortax()
	{
		$this->load->model('api/order');
		$shipping_address_id = $this->request->post['shipping_address_id'];
		$customer_id = $this->request->post['customer_id'];
		$order_id = $this->request->post['order_id'];

		

		if ( $order_id && $customer_id && $shipping_address_id )
		{
			$customer_resale_number = $this->model_api_order->getCustomerResaleNumber($customer_id);
			if( $customer_resale_number )
			{
				$this->model_api_order->removeSalesTax($order_id);
				return true;
			}

			$customer_zone = $this->model_api_order->getCustomerZone($shipping_address_id);
			if ( $customer_zone == 3624 )
			{
				$this->model_api_order->addSalesTax($order_id);
				return true;
			} else {
				$this->model_api_order->removeSalesTax($order_id);
				return true;
			}
		}

	}
	
	public function updateproducts()
	{
		$this->load->model('api/order');
		//$this->add();
		$json = array();
		if (!isset($this->session->data['api_id']))
		{
			$json['error']['warning'] = $this->language->get('error_permission');
		}
		else
		{
			$order_id = isset($this->session->data['order_id']) ? $this->session->data['order_id'] : 0;
			
			if (isset($this->request->post['product']))
			{	
				$post_products 		= $this->request->post['product'];
				$post_productsname 	= $this->request->post['product_name'];
				$post_productsmodel = $this->request->post['product_model'];
				
				foreach ($this->request->post['product'] as $key => $product)
				{ 
					$product_id 			= $product['product_id'];
					$product_name 			= $post_productsname[$key];
					$product_model 			= $post_productsmodel[$key];
					$product_qty 			= $product['quantity'];
					$product_qty_supplied 	= $product['quantity_supplied'];
					$product_price 			= ( !empty($product['unit_conversion_values']) && !empty($product['convert_quantity']) )
					? round(($product['convert_quantity']/$product['quantity']) * $product['price'], 2) : $product['price']; 
					
					$product_data = array(
						"product_id"			=>	$product_id,
						"product_name"			=>	$product_name,
						"product_model"			=>	$product_model,
						"default_vendor_unit"	=>	!empty($product['default_vendor_unit']) ? $product['default_vendor_unit'] : "",
						"updated_vendor_unit"	=>	!empty($product['updated_vendor_unit']) ? $product['updated_vendor_unit']: "",
						"remark"				=>	isset($product['remark'])? $product['remark'] : '',
						"product_qty"			=>	$product_qty,
						"product_qty_supplied"	=>	$product_qty_supplied,
						"product_price"			=>	$product_price,
						"product_actual_price"  =>	$product['price']
					); 
					
					$this->model_api_order->UpdateOrderProducts($order_id,$product_data);
				}
				$this->model_api_order->UpdateOrderTotalData($order_id);
				$json['success']  = 'done';
			}
		}
		
		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function products() {
		
		$this->load->language('api/cart');
		$this->load->model('catalog/product');
		$this->load->model('api/order');
		$this->load->model('api/coupon');
		$this->load->model('total/coupon');
		$this->load->model('tool/image');
		
		$json = array();
if(!isset($this->request->get['orderid'])&&!isset($this->request->get['neworder'])){
				//$this->cart->clear();
			}
		if (!isset($this->session->data['api_id'])) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			// Stock
			if (!$this->cart->hasStock() && (!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning'))) {
				$json['error']['stock'] = $this->language->get('error_stock');
			}

			// Products
			$json['products'] = array();

			$products = $this->cart->getProducts();
			$order_id = @$this->session->data['order_id'];
			foreach ($products as $product) {
				$product_total = 0;
				//print_r($product);
				foreach ($products as $product_2) {
					if ($product_2['product_id'] == $product['product_id']) {
						$product_total += $product_2['quantity'];
					}
				}

				if ($product['minimum'] > $product_total) {
					$json['error']['minimum'][] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);
				}
				
				if ($product['image']) {
					$image = $this->model_tool_image->resize($product['image'], 100, 100);
				} else {
					$image = '';
				}

				$option_data = array();

				foreach ($product['option'] as $option) {
					$option_data[] = array(
						'product_option_id'       => $option['product_option_id'],
						'product_option_value_id' => $option['product_option_value_id'],
						'name'                    => $option['name'],
						'value'                   => $option['value'],
						'type'                    => $option['type']
					);
				}
				
				
				$order_products = $this->model_api_order->getOrderByOrderProduct($order_id,$product['product_id']);
				
				if(!empty($order_products))
				{ 
					$product['name']			  = $order_products[0]['name'];
					$product['model']			  = $order_products[0]['model'];
					$product['quantity_supplied'] = $order_products[0]['quantity_supplied'];
					$product['quantity'] 		  = $order_products[0]['quantity'];
					$product['default_vendor_unit'] = !empty($order_products[0]['default_vendor_unit']) ? $order_products[0]['default_vendor_unit'] : $product['default_vendor_unit'];
					$product['updated_vendor_unit'] = !empty($order_products[0]['updated_vendor_unit']) ? $order_products[0]['updated_vendor_unit'] : $product['default_vendor_unit'];
					$product['remark'] = $order_products[0]['remark'];
					$json['order_data'][] 		  = $order_products;
					$json['order_data_product'][] = $product;
				}
				
				$convert_price = FALSE;
				
				if(isset($this->session->data['refreshproducts']))
				{
					//$p_price = $product['price'];
					$p_price = isset($order_products[0]['price']) ? $order_products[0]['price'] : $product['price'];
					$convert_price = TRUE;
				}
				elseif(isset($this->session->data['orderquotecart_id']))
				{				
					$p_price = isset($order_products[0]['price']) ? $order_products[0]['price'] : $product['price'];
					$convert_price = TRUE;
				}
				else
				{
					$p_price = $product['price'];					
					$convert_price = TRUE;
				}
				
				if(!empty($product['unit']) && $convert_price){
						
						$price = $p_price/$product['unit']['convert_price'];
					}else{
						$price = $p_price;	
					}
				
				if(!empty($product['unit']) && $convert_price){
						if(is_numeric($product['quantity_supplied'])) {
								$total = ($p_price/$product['unit']['convert_price']) * $product['quantity_supplied'];
						}else{
							$total = ($p_price/$product['unit']['convert_price']) * ($product['quantity'] * $product['unit']['convert_price']);
						}
					}else{
						if(is_numeric($product['quantity_supplied'])) {
								$total = $p_price * $product['quantity_supplied'];
						}else{
							$total = $p_price * $product['quantity'];	
						}
						
					}
					//$product['quantity_supplied'] = floatval($product['quantity_supplied']); 
					$totals_order=$this->currency->format($this->tax->calculate($total, $product['tax_class_id'], $this->config->get('config_tax')));
					
					/*if($product['quantity_supplied']>0){
						$product['quantity_supplied']=$product['quantity_supplied'];
                       $totals_order=$this->currency->format($this->tax->calculate($total, $product['tax_class_id'], $this->config->get('config_tax')));
					}elseif($product['quantity_supplied']==""){
                       $product['quantity_supplied']="";
                       $totals_order=$this->currency->format($this->tax->calculate($product['price']*$product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')));
					}else{
						$product['quantity_supplied']=0;
						$totals_order=$this->currency->format($this->tax->calculate(0, $product['tax_class_id'], $this->config->get('config_tax')));
					}*/
					
				$json['products'][] = array(
					'cart_id'    => $product['cart_id'],
					'product_id' => $product['product_id'],
					'name'       => $product['name'],
					'model'      => $product['model'],
					'location'      =>  $product['location'],
					'location_edit' =>$this->getStorageLocation($product['product_id']),
					'mpn'      => $this->getProductMpn($product['product_id']),
					'weight'      => $product['weight'],
					'labour_cost' => isset($product['labour_cost']) ? $product['labour_cost'] : "",
					'unique_option_price' => isset($product['unique_option_price']) ? $product['unique_option_price'] : "",
					'unique_price_discount' => isset($product['unique_price_discount']) ? $product['unique_price_discount'] : "",
					'default_vendor_unit' => !empty($product['default_vendor_unit']) ? $product['default_vendor_unit'] : "",
					'updated_vendor_unit' => !empty($product['updated_vendor_unit']) ? $product['updated_vendor_unit'] : "",
					'remark' 	 => isset($product['remark'])?$product['remark']:'',
					'unit'       => $product['unit'],
					'option'     => $option_data,
					'quantity'   => $product['quantity'],
					'quantity_supplied'   => $product['quantity_supplied'],
					'stock'      => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
					'shipping'   => $product['shipping'],
					'image'      => $image,
					//'price'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))),
					//'total'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']),
					'price'      => $this->currency->format($this->tax->calculate($price, $product['tax_class_id'], $this->config->get('config_tax'))),	
					'total'      =>$totals_order,
					'reward'     => $product['reward'],
					'pricewithtax'  => $this->currency->format($this->tax->calculate($price, $product['tax_class_id'], $this->config->get('config_tax'))),
					'newprice'   => round($price,4),
					'newpricewithtax'  => $this->currency->format($this->tax->calculate($price, $product['tax_class_id'], $this->config->get('config_tax'))),
					'unit_product_price'   => $product['unit_product_price'],
					'unit_dates' =>$this->model_catalog_product->getUnitDetails($product['product_id']),
					'unit_dates_default' =>$this->model_catalog_product->getDefaultUnitDetails($product['product_id']),
					'order_item_sort_order' =>  $product['order_item_sort_order'],
					'DefaultUnitName' =>$this->model_catalog_product->getDefaultUnitName($product['product_id'])
				);
			}

			// Voucher
			$json['vouchers'] = array();

			if (!empty($this->session->data['vouchers'])) {
				foreach ($this->session->data['vouchers'] as $key => $voucher) {
					$json['vouchers'][] = array(
						'code'             => $voucher['code'],
						'description'      => $voucher['description'],
						'from_name'        => $voucher['from_name'],
						'from_email'       => $voucher['from_email'],
						'to_name'          => $voucher['to_name'],
						'to_email'         => $voucher['to_email'],
						'voucher_theme_id' => $voucher['voucher_theme_id'],
						'message'          => $voucher['message'],
						'amount'           => $this->currency->format($voucher['amount'])
					);
				}
			}
			
			//ticket#90
			//Check if coupon already exist and no new coupon added
			if(!isset($this->session->data['coupon']))
			{
				$rec_coupon = $this->model_api_coupon->getCouponHistoryByOrder($order_id);
				if(isset($rec_coupon['coupon_id']))
				{
					$get_coupon_code = $this->model_api_coupon->getCouponCodeById($rec_coupon['coupon_id']);
					if(isset($get_coupon_code['code']))
					{
						$this->session->data['coupon'] = $get_coupon_code['code'];
					}
				}
			}

			// Totals
			$this->load->model('api/order');
			$total_data = array();
			$order_totals = $this->model_api_order->getOrderTotal($order_id);
			
			foreach($order_totals as $order_total)
			{
				$total_data[] = array(
				'code'       => $order_total['code'],
				'title'      => $order_total['title'],
				'value'      => $order_total['value'],
				'sort_order' => $order_total['sort_order']
				);
			}
			
			$json['totals'] = array();
	
			foreach ($total_data as $total) {
				$json['totals'][] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'])
				);
			}
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	/* 
		public function products() {
		$this->load->language('api/orderquotecart');

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			// Stock
			if (!$this->cart->hasStock() && (!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning'))) {
				$json['error']['stock'] = $this->language->get('error_stock');
			}

			// Products
			$json['products'] = array();
			$this->load->model('tool/image');
			$products = $this->cart->getProducts();
			
			foreach ($products as $product) {
				$product_total = 0;

				if ($product['image']) {
					$image = $this->model_tool_image->resize($product['image'], 100, 100);
				} else {
					$image = '';
				}

				foreach ($products as $product_2) {
					if ($product_2['product_id'] == $product['product_id']) {
						$product_total += $product_2['quantity'];
					}
				}

				if ($product['minimum'] > $product_total) {
					$json['error']['minimum'][] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);
				}

				$option_data = array();

				foreach ($product['option'] as $option) {
					$option_data[] = array(
						'product_option_id'       => $option['product_option_id'],
						'product_option_value_id' => $option['product_option_value_id'],
						'name'                    => $option['name'],
						'value'                   => $option['value'],
						'type'                    => $option['type']
					);
				}
				if(!isset($product['unitprice'])){
					$product['unitprice'] = $product['price'];	
				}
				$json['products'][] = array(
					'cart_id'    => $product['cart_id'],
					'product_id' => $product['product_id'],
					'name'       => $product['name'],
					'model'      => $product['model'],
					'unit'       => $product['unit'],
					'option'     => $option_data,
					'image'      => $image,
					'quantity'   => $product['quantity'],
					'quantity_supplied'   => $product['quantity_supplied'],
					'price'      => $this->currency->format($product['unitprice']),
					'pricewithtax'  => $this->currency->format($this->tax->calculate($product['unitprice'], $product['tax_class_id'], $this->config->get('config_tax'))),
					'newprice'   => $product['price'],
					'newpricewithtax'  => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))),
					'stock'      => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
					'shipping'   => $product['shipping'],
					'total'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']),
					'reward'     => $product['reward']
				);
			}

			// Voucher
			$json['vouchers'] = array();

			if (!empty($this->session->data['vouchers'])) {
				foreach ($this->session->data['vouchers'] as $key => $voucher) {
					$json['vouchers'][] = array(
						'code'             => $voucher['code'],
						'description'      => $voucher['description'],
						'from_name'        => $voucher['from_name'],
						'from_email'       => $voucher['from_email'],
						'to_name'          => $voucher['to_name'],
						'to_email'         => $voucher['to_email'],
						'voucher_theme_id' => $voucher['voucher_theme_id'],
						'message'          => $voucher['message'],
						'amount'           => $this->currency->format($voucher['amount'])
					);
				}
			}
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	*/
	public function getStorageLocation($product_id){
     $query=$this->db->query("SELECT * FROM ".DB_PREFIX."product_to_location_quantity WHERE product_id='".$product_id."'");
     if($query->num_rows){
       return $query->rows;
     }else{
     	return array();
     }
	}


	public function getProductMpn($product_id){
     $query=$this->db->query("SELECT mpn FROM ".DB_PREFIX."product WHERE product_id='".$product_id."' ");
     if($query->num_rows){
     	return $query->row['mpn'];
     }else{
     	return "";
     }
	}
	public function total() {
		$this->load->language('api/orderquotecart');
		
		
		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			// Stock
			if (!$this->cart->hasStock() && (!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning'))) {
				$json['error']['stock'] = $this->language->get('error_stock');
			}

			// Totals
			$this->load->model('extension/extension');

			$total_data = array();
			$total = 0;
			$taxes = $this->cart->getTaxes();

			$sort_order = array();

			$results = $this->model_extension_extension->getExtensions('total');

			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
			}

			array_multisort($sort_order, SORT_ASC, $results);

			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('total/' . $result['code']);

					$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
				}
			}
			
			
			$sort_order = array();

			foreach ($total_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $total_data);

			$json['totals'] = array();

			foreach ($total_data as $total) {
				$json['totals'][] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'])
				);
			}
		}
		
		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function getTotal() {
		$this->load->model('api/order');
		$order_id = $this->request->get['order_id'];
		$total_data = array();
		$order_totals = $this->model_api_order->getOrderTotal($order_id);
		
		foreach($order_totals as $order_total)
		{
			$total_data[] = array(
			'code'       => $order_total['code'],
			'title'      => $order_total['title'],
			'value'      => $order_total['value'],
			'sort_order' => $order_total['sort_order']
			);
		}
		
		$json['totals'] = array();
		foreach ($total_data as $total) {
			$json['totals'][] = array(
				'code' => $total['code'],
				'title' => $total['title'],
				'text'  => $this->currency->format($total['value'])
			);
		}
	
		
		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function customer() {
		$this->load->language('api/customer');

		// Delete past customer in case there is an error
		if(isset($this->session->data['customer'])) unset($this->session->data['customer']);

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			// Add keys for missing post vars
			$customer_info = array();

			// Customer
			if (isset($this->request->post['customer_id'])) {
				if($this->request->post['customer_id'] > 0){
					$this->load->model('account/customer');
	
					$customer_info = $this->model_account_customer->getCustomer($this->request->post['customer_id']);
					
					if (!$customer_info || !$this->customer->login($customer_info['email'], '', true)) {
						$json['error']['warning'] = $this->language->get('error_customer');
					}
				}
			}

			// Customer Group
			if (isset($customer_info['customer_group_id'])) {
				$customer_group_id = $customer_info['customer_group_id'];
			}  else {
				$customer_group_id = 0;
			}
			
			if(count($customer_info) >0){
				if ((utf8_strlen(trim($customer_info['firstname'])) < 1) || (utf8_strlen(trim($customer_info['firstname'])) > 32)) {
					$json['error']['firstname'] = $this->language->get('error_firstname');
				}
	
				if ((utf8_strlen(trim($customer_info['lastname'])) < 1) || (utf8_strlen(trim($customer_info['lastname'])) > 32)) {
					$json['error']['lastname'] = $this->language->get('error_lastname');
				}
	
				if ((utf8_strlen($customer_info['email']) > 96) || (!preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $customer_info['email']))) {
					$json['error']['email'] = $this->language->get('error_email');
				}
	
				if ((utf8_strlen($customer_info['telephone']) < 3) || (utf8_strlen($customer_info['telephone']) > 32)) {
					$json['error']['telephone'] = $this->language->get('error_telephone');
				}
				
				// Custom field validation
				$this->load->model('account/custom_field');
	
				$custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);
	
				$customer_info['custom_field'] =  json_decode($customer_info['custom_field'], true);
				
				foreach ($custom_fields as $custom_field) {
					
					if (($custom_field['location'] == 'account') && $custom_field['required'] && empty($customer_info['custom_field'][$custom_field['custom_field_id']])) {
						$json['error']['custom_field' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
					}
				}
				
				if (!$json) {
					if( isset($this->session->data['customer']) ) unset($this->session->data['customer']);
					
					$this->session->data['customer'] = array(
						'customer_id'       => !empty($customer_info['customer_id']) ? $customer_info['customer_id'] : $this->request->post['customer_id'],
						'customer_group_id' => $customer_group_id,
						'firstname'         => $customer_info['firstname'],
						'lastname'          => $customer_info['lastname'],
						'email'             => $customer_info['email'],
						'telephone'         => $customer_info['telephone'],
						'fax'               => $customer_info['fax'],
						'custom_field'      => isset($customer_info['custom_field']) ? $customer_info['custom_field'] : array()
					);
				}
			}else{
				if ($this->request->get['order_id'] && $this->request->get['order_id'] > 0) {
					$this->load->model('account/order');
	
					$customer_info = $this->model_account_order->getOrderguest($this->request->get['order_id']);
					
					if (!$customer_info || !$this->customer->login($customer_info['email'], '', true)) {
						//$json['error']['warning'] = $this->language->get('error_customer');
					}
					
					if ((utf8_strlen(trim($customer_info['firstname'])) < 1) || (utf8_strlen(trim($customer_info['firstname'])) > 32)) {
						$json['error']['firstname'] = $this->language->get('error_firstname');
					}
		
					if ((utf8_strlen(trim($customer_info['lastname'])) < 1) || (utf8_strlen(trim($customer_info['lastname'])) > 32)) {
						$json['error']['lastname'] = $this->language->get('error_lastname');
					}
		
					if ((utf8_strlen($customer_info['email']) > 96) || (!preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $customer_info['email']))) {
						$json['error']['email'] = $this->language->get('error_email');
					}
		
					if ((utf8_strlen($customer_info['telephone']) < 3) || (utf8_strlen($customer_info['telephone']) > 32)) {
						$json['error']['telephone'] = $this->language->get('error_telephone');
					}
				
					// Custom field validation
					$this->load->model('account/custom_field');
		
					$custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);
					if(isset($customer_info['custom_field'])){
						$customer_info['custom_field'] =  json_decode($customer_info['custom_field'], true);
					}else{
						$customer_info['custom_field'] = '';
					}
					foreach ($custom_fields as $custom_field) {
						
						if (($custom_field['location'] == 'account') && $custom_field['required'] && empty($customer_info['custom_field'][$custom_field['custom_field_id']])) {
							$json['error']['custom_field' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
						}
					}
					
					if (!$json) {
						$this->session->data['customer'] = array(
							'customer_id'       => !empty($customer_info['customer_id']) ? $customer_info['customer_id'] : $this->request->post['customer_id'],
							'customer_group_id' => $customer_group_id,
							'firstname'         => $customer_info['firstname'],
							'lastname'          => $customer_info['lastname'],
							'email'             => $customer_info['email'],
							'telephone'         => $customer_info['telephone'],
							'fax'               => $customer_info['fax'],
							'custom_field'      => isset($customer_info['custom_field']) ? $customer_info['custom_field'] : array()
						);
					}
					
				
			}
				$json['success'] = $this->language->get('text_success');
			}
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function paymentaddress() {
		$this->load->language('api/payment');

		// Delete old payment address, payment methods and method so not to cause any issues if there is an error
		unset($this->session->data['payment_address']);
		unset($this->session->data['payment_methods']);
		unset($this->session->data['payment_method']);

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			// Add keys for missing post vars
			$details = array();
			if (isset($this->request->get['address_id']) && !empty($this->request->get['address_id'])) {
				$this->load->model('account/address');

				$details = $this->model_account_address->getAddress($this->request->get['address_id']);
			}
			
			
			if ((utf8_strlen(trim($details['firstname'])) < 1) || (utf8_strlen(trim($details['firstname'])) > 32)) {
				$json['error']['firstname'] = $this->language->get('error_firstname');
			}

			if ((utf8_strlen(trim($details['lastname'])) < 1) || (utf8_strlen(trim($details['lastname'])) > 32)) {
				$json['error']['lastname'] = $this->language->get('error_lastname');
			}

			if ((utf8_strlen(trim($details['address_1'])) < 3) || (utf8_strlen(trim($details['address_1'])) > 128)) {
				$json['error']['address_1'] = $this->language->get('error_address_1');
			}

			if ((utf8_strlen($details['city']) < 2) || (utf8_strlen($details['city']) > 32)) {
				$json['error']['city'] = $this->language->get('error_city');
			}
			
			$this->load->model('localisation/country');

			$country_info = $this->model_localisation_country->getCountry($details['country_id']);
			
			if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($details['postcode'])) < 2 || utf8_strlen(trim($details['postcode'])) > 10)) {
				$json['error']['postcode'] = $this->language->get('error_postcode');
			}

			if ($details['country_id'] == '') {
				$json['error']['country'] = $this->language->get('error_country');
			}
			
			if (!isset($details['zone_id']) || $details['zone_id'] == '') {
				$json['error']['zone'] = $this->language->get('error_zone');
			}
			
			// Custom field validation
			$this->load->model('account/custom_field');

			$custom_fields = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));
			
			foreach ($custom_fields as $custom_field) {
				if (($custom_field['location'] == 'address') && $custom_field['required'] && empty($details['custom_field'][$custom_field['custom_field_id']])) {
					$json['error']['custom_field' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
				}
			}
			
			if (!$json) {
				$this->load->model('localisation/country');

				$country_info = $this->model_localisation_country->getCountry($details['country_id']);

				if ($country_info) {
					$country = $country_info['name'];
					$iso_code_2 = $country_info['iso_code_2'];
					$iso_code_3 = $country_info['iso_code_3'];
					$address_format = $country_info['address_format'];
				} else {
					$country = '';
					$iso_code_2 = '';
					$iso_code_3 = '';
					$address_format = '';
				}

				$this->load->model('localisation/zone');

				$zone_info = $this->model_localisation_zone->getZone($details['zone_id']);

				if ($zone_info) {
					$zone = $zone_info['name'];
					$zone_code = $zone_info['code'];
				} else {
					$zone = '';
					$zone_code = '';
				}

				$this->session->data['payment_address'] = array(
					'firstname'      => $details['firstname'],
					'lastname'       => $details['lastname'],
					'company'        => $details['company'],
					'address_1'      => $details['address_1'],
					'address_2'      => $details['address_2'],
					'postcode'       => $details['postcode'],
					'city'           => $details['city'],
					'zone_id'        => $details['zone_id'],
					'zone'           => $zone,
					'zone_code'      => $zone_code,
					'country_id'     => $details['country_id'],
					'country'        => $country,
					'iso_code_2'     => $iso_code_2,
					'iso_code_3'     => $iso_code_3,
					'address_format' => $address_format,
					'custom_field'   => isset($details['custom_field']) ? $details['custom_field'] : array()
				);

				$json['success'] = $this->language->get('text_address');

				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);
			}
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function shippingaddress() {
		$this->load->language('api/shipping');

		// Delete old shipping address, shipping methods and method so not to cause any issues if there is an error
		unset($this->session->data['shipping_address']);
		unset($this->session->data['shipping_methods']);
		unset($this->session->data['shipping_method']);

		$json = array();

		if ($this->cart->hasShipping()) {
			if (!isset($this->session->data['api_id'])) {
				$json['error']['warning'] = $this->language->get('error_permission');
			} else {

				$details = array();
				if (isset($this->request->get['address_id']) && !empty($this->request->get['address_id'])) {
					$this->load->model('account/address');

					$details = $this->model_account_address->getAddress($this->request->get['address_id']);
				}

				if ((utf8_strlen(trim($details['firstname'])) < 1) || (utf8_strlen(trim($details['firstname'])) > 32)) {
					$json['error']['firstname'] = $this->language->get('error_firstname');
				}

				if ((utf8_strlen(trim($details['lastname'])) < 1) || (utf8_strlen(trim($details['lastname'])) > 32)) {
					$json['error']['lastname'] = $this->language->get('error_lastname');
				}

				if ((utf8_strlen(trim($details['address_1'])) < 3) || (utf8_strlen(trim($details['address_1'])) > 128)) {
					$json['error']['address_1'] = $this->language->get('error_address_1');
				}

				if ((utf8_strlen($details['city']) < 2) || (utf8_strlen($details['city']) > 32)) {
					$json['error']['city'] = $this->language->get('error_city');
				}

				$this->load->model('localisation/country');

				$country_info = $this->model_localisation_country->getCountry($details['country_id']);

				if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($details['postcode'])) < 2 || utf8_strlen(trim($details['postcode'])) > 10)) {
					$json['error']['postcode'] = $this->language->get('error_postcode');
				}

				if ($details['country_id'] == '') {
					$json['error']['country'] = $this->language->get('error_country');
				}

				if (!isset($details['zone_id']) || $details['zone_id'] == '') {
					$json['error']['zone'] = $this->language->get('error_zone');
				}

				// Custom field validation
				$this->load->model('account/custom_field');

				$custom_fields = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));

				foreach ($custom_fields as $custom_field) {
					if (($custom_field['location'] == 'address') && $custom_field['required'] && empty($details['custom_field'][$custom_field['custom_field_id']])) {
						$json['error']['custom_field' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
					}
				}

				if (!$json) {
					$this->load->model('localisation/country');

					$country_info = $this->model_localisation_country->getCountry($details['country_id']);

					if ($country_info) {
						$country = $country_info['name'];
						$iso_code_2 = $country_info['iso_code_2'];
						$iso_code_3 = $country_info['iso_code_3'];
						$address_format = $country_info['address_format'];
					} else {
						$country = '';
						$iso_code_2 = '';
						$iso_code_3 = '';
						$address_format = '';
					}

					$this->load->model('localisation/zone');

					$zone_info = $this->model_localisation_zone->getZone($details['zone_id']);

					if ($zone_info) {
						$zone = $zone_info['name'];
						$zone_code = $zone_info['code'];
					} else {
						$zone = '';
						$zone_code = '';
					}

					$this->session->data['shipping_address'] = array(
						'firstname'      => $details['firstname'],
						'lastname'       => $details['lastname'],
						'company'        => $details['company'],
						'address_1'      => $details['address_1'],
						'address_2'      => $details['address_2'],
						'postcode'       => $details['postcode'],
						'city'           => $details['city'],
						'zone_id'        => $details['zone_id'],
						'zone'           => $zone,
						'zone_code'      => $zone_code,
						'country_id'     => $details['country_id'],
						'country'        => $country,
						'iso_code_2'     => $iso_code_2,
						'iso_code_3'     => $iso_code_3,
						'address_format' => $address_format,
						'custom_field'   => isset($details['custom_field']) ? $details['custom_field'] : array()
					);

					$json['success'] = $this->language->get('text_address');

					unset($this->session->data['shipping_method']);
					unset($this->session->data['shipping_methods']);
				}
			}
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function shippingmethods() {
		$this->load->language('api/shipping');

		// Delete past shipping methods and method just in case there is an error
		unset($this->session->data['shipping_methods']);
		unset($this->session->data['shipping_method']);

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} elseif ($this->cart->hasShipping()) {
			if (!isset($this->session->data['shipping_address'])) {
				$json['error'] = $this->language->get('error_address');
			}

			if (!$json) {
				// Shipping Methods
				$json['shipping_methods'] = array();

				$this->load->model('extension/extension');

				$results = $this->model_extension_extension->getExtensions('shipping');

				foreach ($results as $result) {
					if ($this->config->get($result['code'] . '_status')) {
						$this->load->model('shipping/' . $result['code']);

						$quote = $this->{'model_shipping_' . $result['code']}->getQuote($this->session->data['shipping_address']);

						if ($quote) {
							$json['shipping_methods'][$result['code']] = array(
								'title'      => $quote['title'],
								'quote'      => $quote['quote'],
								'sort_order' => $quote['sort_order'],
								'error'      => $quote['error']
							);
						}
					}
				}

				$sort_order = array();

				foreach ($json['shipping_methods'] as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}

				array_multisort($sort_order, SORT_ASC, $json['shipping_methods']);

				if ($json['shipping_methods']) {
					$this->session->data['shipping_methods'] = $json['shipping_methods'];
				} else {
					$json['error'] = $this->language->get('error_no_shipping');
				}
			}
		} else {
			$json['shipping_methods'] = array();
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function shippingmethod() {
		$this->load->language('api/shipping');

		// Delete old shipping method so not to cause any issues if there is an error
		unset($this->session->data['shipping_method']);

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if ($this->cart->hasShipping()) {
				// Shipping Address
				if (!isset($this->session->data['shipping_address'])) {
					$json['error'] = $this->language->get('error_address');
				}

				// Shipping Method
				if (empty($this->session->data['shipping_methods'])) {
					$json['error'] = $this->language->get('error_no_shipping');
				} elseif (!isset($this->request->post['shipping_method'])) {
					$json['error'] = $this->language->get('error_method');
				} else {
					$shipping = explode('.', $this->request->post['shipping_method']);

					if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
						$json['error'] = $this->language->get('error_method');
					}
				}

				if (!$json) {
					$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];

					$json['success'] = $this->language->get('text_method');
				}
			} else {
				unset($this->session->data['shipping_address']);
				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
			}
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function paymentmethods() {
		$this->load->language('api/payment');

		// Delete past shipping methods and method just in case there is an error
		unset($this->session->data['payment_methods']);
		unset($this->session->data['payment_method']);

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			// Payment Address
			if (!isset($this->session->data['payment_address'])) {
				$json['error'] = $this->language->get('error_address');
			}

			if (!$json) {
				// Totals
				$total_data = array();
				$total = 0;
				$taxes = $this->cart->getTaxes();

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

						$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
					}
				}

				// Payment Methods
				$json['payment_methods'] = array();

				$this->load->model('extension/extension');

				$results = $this->model_extension_extension->getExtensions('payment');

				$recurring = $this->cart->hasRecurringProducts();

				foreach ($results as $result) {
					if ($this->config->get($result['code'] . '_status')) {
						$this->load->model('payment/' . $result['code']);

						$method = $this->{'model_payment_' . $result['code']}->getMethod($this->session->data['payment_address'], $total);

						if ($method) {
							if ($recurring) {
								if (method_exists($this->{'model_payment_' . $result['code']}, 'recurringPayments') && $this->{'model_payment_' . $result['code']}->recurringPayments()) {
									$json['payment_methods'][$result['code']] = $method;
								}
							} else {
								$json['payment_methods'][$result['code']] = $method;
							}
						}
					}
				}

				$sort_order = array();

				foreach ($json['payment_methods'] as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}

				array_multisort($sort_order, SORT_ASC, $json['payment_methods']);

				if ($json['payment_methods']) {
					$this->session->data['payment_methods'] = $json['payment_methods'];
				} else {
					$json['error'] = $this->language->get('error_no_payment');
				}
			}
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function paymentmethod() {
		$this->load->language('api/payment');

		// Delete old payment method so not to cause any issues if there is an error
		unset($this->session->data['payment_method']);

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			// Payment Address
			if (!isset($this->session->data['payment_address'])) {
				$json['error'] = $this->language->get('error_address');
			}

			// Payment Method
			if (empty($this->session->data['payment_methods'])) {
				$json['error'] = $this->language->get('error_no_payment');
			} elseif (!isset($this->request->post['payment_method'])) {
				$json['error'] = $this->language->get('error_method');
			} elseif (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
				$json['error'] = $this->language->get('error_method');
			}

			if (!$json) {
				$this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];

				$json['success'] = $this->language->get('text_method');
			}
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function coupon() {
		$this->load->language('api/coupon');

		// Delete past coupon in case there is an error
		unset($this->session->data['coupon']);

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('total/coupon');

			if (isset($this->request->post['coupon'])) {
				$coupon = $this->request->post['coupon'];
			} else {
				$coupon = '';
			}

			$coupon_info = $this->model_total_coupon->getCoupon($coupon);

			if ($coupon_info) {
				$this->session->data['coupon'] = $this->request->post['coupon'];

				$json['success'] = $this->language->get('text_success');
			} else {
				$json['error'] = $this->language->get('error_coupon');
			}
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function voucher() {
		$this->load->language('api/voucher');

		// Delete past voucher in case there is an error
		unset($this->session->data['voucher']);

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('total/voucher');

			if (isset($this->request->post['voucher'])) {
				$voucher = $this->request->post['voucher'];
			} else {
				$voucher = '';
			}

			$voucher_info = $this->model_total_voucher->getVoucher($voucher);

			if ($voucher_info) {
				$this->session->data['voucher'] = $this->request->post['voucher'];

				$json['success'] = $this->language->get('text_success');
			} else {
				$json['error'] = $this->language->get('error_voucher');
			}
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function addVoucher() {
		$this->load->language('api/voucher');

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			// Add keys for missing post vars
			$keys = array(
				'from_name',
				'from_email',
				'to_name',
				'to_email',
				'voucher_theme_id',
				'message',
				'amount'
			);

			foreach ($keys as $key) {
				if (!isset($this->request->post[$key])) {
					$this->request->post[$key] = '';
				}
			}

			if (isset($this->request->post['voucher'])) {
				$this->session->data['vouchers'] = array();

				foreach ($this->request->post['voucher'] as $voucher) {
					if (isset($voucher['code']) && isset($voucher['to_name']) && isset($voucher['to_email']) && isset($voucher['from_name']) && isset($voucher['from_email']) && isset($voucher['voucher_theme_id']) && isset($voucher['message']) && isset($voucher['amount'])) {
						$this->session->data['vouchers'][$voucher['code']] = array(
							'code'             => $voucher['code'],
							'description'      => sprintf($this->language->get('text_for'), $this->currency->format($this->currency->convert($voucher['amount'], $this->currency->getCode(), $this->config->get('config_currency'))), $voucher['to_name']),
							'to_name'          => $voucher['to_name'],
							'to_email'         => $voucher['to_email'],
							'from_name'        => $voucher['from_name'],
							'from_email'       => $voucher['from_email'],
							'voucher_theme_id' => $voucher['voucher_theme_id'],
							'message'          => $voucher['message'],
							'amount'           => $this->currency->convert($voucher['amount'], $this->currency->getCode(), $this->config->get('config_currency'))
						);
					}
				}

				$json['success'] = $this->language->get('text_cart');

				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);
			} else {
				// Add a new voucher if set
				if ((utf8_strlen($this->request->post['from_name']) < 1) || (utf8_strlen($this->request->post['from_name']) > 64)) {
					$json['error']['from_name'] = $this->language->get('error_from_name');
				}

				if ((utf8_strlen($this->request->post['from_email']) > 96) || !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['from_email'])) {
					$json['error']['from_email'] = $this->language->get('error_email');
				}

				if ((utf8_strlen($this->request->post['to_name']) < 1) || (utf8_strlen($this->request->post['to_name']) > 64)) {
					$json['error']['to_name'] = $this->language->get('error_to_name');
				}

				if ((utf8_strlen($this->request->post['to_email']) > 96) || !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['to_email'])) {
					$json['error']['to_email'] = $this->language->get('error_email');
				}

				if (($this->request->post['amount'] < $this->config->get('config_voucher_min')) || ($this->request->post['amount'] > $this->config->get('config_voucher_max'))) {
					$json['error']['amount'] = sprintf($this->language->get('error_amount'), $this->currency->format($this->config->get('config_voucher_min')), $this->currency->format($this->config->get('config_voucher_max')));
				}

				if (!$json) {
					$code = mt_rand();

					$this->session->data['vouchers'][$code] = array(
						'code'             => $code,
						'description'      => sprintf($this->language->get('text_for'), $this->currency->format($this->currency->convert($this->request->post['amount'], $this->currency->getCode(), $this->config->get('config_currency'))), $this->request->post['to_name']),
						'to_name'          => $this->request->post['to_name'],
						'to_email'         => $this->request->post['to_email'],
						'from_name'        => $this->request->post['from_name'],
						'from_email'       => $this->request->post['from_email'],
						'voucher_theme_id' => $this->request->post['voucher_theme_id'],
						'message'          => $this->request->post['message'],
						'amount'           => $this->currency->convert($this->request->post['amount'], $this->currency->getCode(), $this->config->get('config_currency'))
					);

					$json['success'] = $this->language->get('text_cart');

					unset($this->session->data['shipping_method']);
					unset($this->session->data['shipping_methods']);
					unset($this->session->data['payment_method']);
					unset($this->session->data['payment_methods']);
				}
			}
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function reward() {
		$this->load->language('api/reward');

		// Delete past reward in case there is an error
		unset($this->session->data['reward']);

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$points = $this->customer->getRewardPoints();

			$points_total = 0;

			foreach ($this->cart->getProducts() as $product) {
				if ($product['points']) {
					$points_total += $product['points'];
				}
			}

			if (empty($this->request->post['reward'])) {
				$json['error'] = $this->language->get('error_reward');
			}

			if ($this->request->post['reward'] > $points) {
				$json['error'] = sprintf($this->language->get('error_points'), $this->request->post['reward']);
			}

			if ($this->request->post['reward'] > $points_total) {
				$json['error'] = sprintf($this->language->get('error_maximum'), $points_total);
			}

			if (!$json) {
				$this->session->data['reward'] = abs($this->request->post['reward']);

				$json['success'] = $this->language->get('text_success');
			}
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

}
