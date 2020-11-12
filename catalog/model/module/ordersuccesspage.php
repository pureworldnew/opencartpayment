<?php
class ModelModuleOrderSuccessPage extends Model {
	public function getMessage($OSP, $OSP_orderdata, $current_language) {
		if ($OSP['DiscountType']=='N') {
			// do nothing here
		} else {
			$DiscountCode		= $this->generateuniquerandomcouponcode();
			$TimeEnd			= time() + $OSP['DiscountValidity'] * 24 * 60 * 60;
			$CouponData			= array(
			  'name' => 'OSP [' . $OSP_orderdata['email'].']',
			  'code'				=> $DiscountCode, 
			  'discount'			=> $OSP['Discount'],
			  'type'				=> $OSP['DiscountType'],
			  'total'		   		=> $OSP['TotalAmount'],
			  'logged'		 		=> '0',
			  'shipping'			=> '0',
			  'date_start'	  		=> date('Y-m-d', time()),
			  'date_end'			=> date('Y-m-d', $TimeEnd),
			  'uses_total'	  		=> '1',
			  'uses_customer'   	=> '1',
			  'status'		  		=> '1');
			$this->addCoupon($CouponData);
		}

		if ($OSP['DiscountType']=='N') {
			$osp_message_original = array('{first_name}', '{last_name}', '{order_id}');
			$osp_message_replace = array($OSP_orderdata['firstname'],$OSP_orderdata['lastname'],$OSP_orderdata['order_id']);
		} else {
			$osp_message_original = array('{first_name}', '{last_name}', '{order_id}', '{discount_code}', '{discount_value}', '{total_amount}', '{date_end}');
			$osp_message_replace = array($OSP_orderdata['firstname'],$OSP_orderdata['lastname'],$OSP_orderdata['order_id'],$DiscountCode,$OSP['Discount'],$OSP['TotalAmount'],date($OSP['DateFormat'], $TimeEnd));
		}
		
		if (!empty($OSP['PageText'][$current_language])) {
			$OSP['PageText'][$current_language] = str_replace($osp_message_original, $osp_message_replace, html_entity_decode($OSP['PageText'][$current_language]));
		}
		
		return $OSP;				
	}
	
	public function getPromotedProducts($OSP) {
		$this->load->model('tool/image');	
		$this->load->model('catalog/product');	
		
		$promoted_products = array();
		if (isset($OSP['PromotedProducts']) && !empty($OSP['PromotedProducts'])) {
			if (empty($OSP['PromotedPictureWidth'])) 
				$picture_width=100; else $picture_width=$OSP['PromotedPictureWidth'];
			if (empty($OSP['PromotedPictureHeight'])) 
				$picture_height=100; else $picture_height=$OSP['PromotedPictureHeight'];

			foreach ($OSP['PromotedProducts'] as $p_id) {
				$result = $this->model_catalog_product->getProduct($p_id);

				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $picture_width, $picture_height);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $picture_width, $picture_height);
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
					$rating = $result['rating'];
				} else {
					$rating = false;
				}

				$promoted_products[] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'rating'      => $rating,
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id']),
				);
			}
		}
		
		return $promoted_products;	
	}
	
	public function getOrderData($order_info, $osp_order_products = '') {
		$this->load->model('tool/image');	
		$this->load->model('catalog/product');	
		$this->load->model('account/order');	

		if ($order_info['invoice_no']) {
			$data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
		} else {
			$data['invoice_no'] = '';
		}
		
		$data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));
		
		if ($order_info['payment_address_format']) {
			$format = $order_info['payment_address_format'];
		} else {
			$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}
		
		$find = array('{firstname}', '{lastname}', '{company}', '{address_1}', '{address_2}', '{city}', '{postcode}', '{zone}', '{zone_code}', '{country}');
		$replace = array('firstname' => $order_info['payment_firstname'], 'lastname'  => $order_info['payment_lastname'], 'company'   => $order_info['payment_company'], 'address_1' => $order_info['payment_address_1'], 'address_2' => $order_info['payment_address_2'], 'city'      => $order_info['payment_city'], 'postcode'  => $order_info['payment_postcode'], 'zone'      => $order_info['payment_zone'], 'zone_code' => $order_info['payment_zone_code'], 'country'   => $order_info['payment_country']);

		$data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

		$data['payment_method'] = $order_info['payment_method'];

		if ($order_info['shipping_address_format']) {
			$format = $order_info['shipping_address_format'];
		} else {
			$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}

		$find = array('{firstname}', '{lastname}', '{company}', '{address_1}', '{address_2}', '{city}', '{postcode}', '{zone}', '{zone_code}', '{country}');
		$replace = array('firstname' => $order_info['shipping_firstname'], 'lastname'  => $order_info['shipping_lastname'], 'company'   => $order_info['shipping_company'], 'address_1' => $order_info['shipping_address_1'], 'address_2' => $order_info['shipping_address_2'], 'city'      => $order_info['shipping_city'], 'postcode'  => $order_info['shipping_postcode'], 'zone'      => $order_info['shipping_zone'], 'zone_code' => $order_info['shipping_zone_code'], 'country'   => $order_info['shipping_country']);

		$data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
		$data['shipping_method'] = $order_info['shipping_method'];
		
		$data['products'] = array();
	
		$products = !empty($osp_order_products) ? $osp_order_products : $this->model_account_order->getOrderProducts($order_info['order_id']);
		
		$OSP		= $this->config->get('ordersuccesspage');
		
		if (empty($OSP['OrderPictureWidth'])) 
			$picture_width=50; else $picture_width=$OSP['OrderPictureWidth'];
		if (empty($OSP['OrderPictureHeight'])) 
			$picture_height=50; else $picture_height=$OSP['OrderPictureHeight'];

		foreach ($products as $product) {
			$option_data = array();
			if( isset( $product['order_product_id'] ) )
			{
				$options = $this->model_account_order->getOrderOptions($order_info['order_id'], $product['order_product_id']);


				foreach ($options as $option) {
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
			}
			
			if(empty($product['unit'])){
					$product['unit']['unit_conversion_values'] = 0;
					$product['unit']['convert_price'] = 1;
				}

			$product_info = $this->model_catalog_product->getProduct($product['product_id']);
			$product_category = $this->model_catalog_product->getProductCategory($product['product_id']);
			
			
			if ($product_info['image']) { $image = $this->model_tool_image->resize($product_info['image'], $picture_width, $picture_height); }
			else { $image = $this->model_tool_image->resize('placeholder.png', $picture_width, $picture_height); }
			$product_price = $product['price']/$product['unit']['convert_price'];
			$data['products'][] = array(
				'href'    => $this->url->link('product/product', 'product_id='.$product['product_id']),
				'name'     => $product['name'],
				'image'	   => $image,
				'model'    => $product['model'],
				'manufacturer' => $product_info['manufacturer'],
				'category'    => $product_category,
				'option'   => $option_data,
				'unit' 		 => $product['unit'],
				'quantity' => ($product['quantity'] * $product['unit']['convert_price']),
				'price'    => $this->currency->format($product_price + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
				'gtm_price'    => $product_price,
				'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
				'unit_dates' =>$this->model_catalog_product->getUnitDetails($product['product_id']),
				'unit_dates_default' =>$this->model_catalog_product->getDefaultUnitDetails($product['product_id']),
				'DefaultUnitName' =>$this->model_catalog_product->getDefaultUnitName($product['product_id']),
			);
		}

		// Voucher
		$data['vouchers'] = array();

		$vouchers = $this->model_account_order->getOrderVouchers($order_info['order_id']);

		foreach ($vouchers as $voucher) {
			$data['vouchers'][] = array(
				'description' => $voucher['description'],
				'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
			);
		}

		// Totals
		$data['totals'] = array();

		$totals = $this->model_account_order->getOrderTotals($order_info['order_id']);

		foreach ($totals as $total) {
			$data['totals'][] = array(
				'title' => $total['title'],
				'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
			);
		}

		$data['comment'] = nl2br($order_info['comment']);
	
		return $data;
	}
	
	public function generateuniquerandomcouponcode() {
		  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		  $couponCode = '';
		  for ($i = 0; $i < 10; $i++) {	
			  $couponCode .= $characters[rand(0, strlen($characters) - 1)]; 
		  }
		  if($this->isUniqueCode($couponCode)) {	
			  return $couponCode;
		  } else {	
			  return $this->generateuniquerandomcouponcode();
		  }
	}
	
	  public function isUniqueCode($randomCode) {
		  $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coupon` WHERE code='".$this->db->escape($randomCode)."'");
		  if($query->num_rows == 0) {
			return true;
		  } else {
			return false;
		  }	
	  }
	
	  public function addCoupon($data) {
		  $this->db->query("INSERT INTO " . DB_PREFIX . "coupon SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', discount = '" . (float)$data['discount'] . "', type = '" . $this->db->escape($data['type']) . "', total = '" . (float)$data['total'] . "', logged = '" . (int)$data['logged'] . "', shipping = '" . (int)$data['shipping'] . "', date_start = '" . $this->db->escape($data['date_start']) . "', date_end = '" . $this->db->escape($data['date_end']) . "', uses_total = '" . (int)$data['uses_total'] . "', uses_customer = '" . (int)$data['uses_customer'] . "', status = '" . (int)$data['status'] . "', date_added = NOW()");
	
		  $coupon_id = $this->db->getLastId();	
	  }	
}