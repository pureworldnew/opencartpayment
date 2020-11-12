<?php
class ControllerCheckoutCart extends Controller {
	public function index() {
		$this->load->language('checkout/cart');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/home'),
			'text' => $this->language->get('text_home')
		);
		
		
		

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('checkout/cart'),
			'text' => $this->language->get('heading_title')
		);

		if ($this->cart->hasProducts() || !empty($this->session->data['vouchers'])) {
			$data['heading_title'] = $this->language->get('heading_title');

			$data['text_recurring_item'] = $this->language->get('text_recurring_item');
			$data['text_next'] = $this->language->get('text_next');
			$data['text_next_choice'] = $this->language->get('text_next_choice');

			$data['column_image'] = $this->language->get('column_image');
			$data['column_name'] = $this->language->get('column_name');
			$data['column_model'] = $this->language->get('column_model');
			$data['column_quantity'] = $this->language->get('column_quantity');
			$data['column_price'] = $this->language->get('column_price');
			$data['column_total'] = $this->language->get('column_total');

			$data['button_update'] = $this->language->get('button_update');
			$data['button_remove'] = $this->language->get('button_remove');
			$data['button_shopping'] = $this->language->get('button_shopping');
			$data['button_checkout'] = $this->language->get('button_checkout');

			if (!$this->cart->hasStock() && (!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning'))) {
				$data['error_warning'] = $this->language->get('error_stock');
			} elseif (isset($this->session->data['error'])) {
				$data['error_warning'] = $this->session->data['error'];

				unset($this->session->data['error']);
			} else {
				$data['error_warning'] = '';
			}

			if ($this->config->get('config_customer_price') && !$this->customer->isLogged()) {
				$data['attention'] = sprintf($this->language->get('text_login'), $this->url->link('account/login'), $this->url->link('account/register'));
			} else {
				$data['attention'] = '';
			}

			if (isset($this->session->data['success'])) {
				$data['success'] = $this->session->data['success'];

				unset($this->session->data['success']);
			} else {
				$data['success'] = '';
			}

			$data['action'] = $this->url->link('checkout/cart/edit');

			if ($this->config->get('config_cart_weight')) {
				$data['weight'] = $this->weight->format($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
			} else {
				$data['weight'] = '';
			}

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
					$image = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'));
				} else {
					$image = '';
				}

				$option_data = array();

				foreach ($product['option'] as $option) {
					if ($option['type'] != 'file') {
						if($option['name'] == 'design-image-front'){
						}
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
						'value' => $value
					);
				}

				// Display prices
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}

				// Display prices
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$total = $this->currency->format($this->tax->calculate($product['total'], $product['tax_class_id'], $this->config->get('config_tax')));
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

				$data['products'][] = array(
					'key'       => $product['key'],
					'thumb'     => $image,
					'name'      => $product['name'],
					'model'     => $product['model'],
					'option'    => $option_data,
					'recurring' => $recurring,
					'quantity'  => $product['quantity'],
					'stock'     => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
					'reward'    => ($product['reward'] ? sprintf($this->language->get('text_points'), $product['reward']) : ''),
					'price'     => $price,
					'total'     => $total,
					'href'      => $this->url->link('product/product', 'product_id=' . $product['product_id'])
				);
			}

			// Gift Voucher
			$data['vouchers'] = array();

			if (!empty($this->session->data['vouchers'])) {
				foreach ($this->session->data['vouchers'] as $key => $voucher) {
					$data['vouchers'][] = array(
						'key'         => $key,
						'description' => $voucher['description'],
						'amount'      => $this->currency->format($voucher['amount']),
						'remove'      => $this->url->link('checkout/cart', 'remove=' . $key)
					);
				}
			}

			// Totals
			$this->load->model('extension/extension');

			$total_data = array();
			$total = 0;
			$taxes = $this->cart->getTaxes();

			// Display prices
			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
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
			}

			$data['totals'] = array();

			foreach ($total_data as $total) {
				$data['totals'][] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'])
				);
			}

			$data['continue'] = $this->url->link('common/home');

			$data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');

			$this->load->model('extension/extension');

			$data['checkout_buttons'] = array();

			$data['coupon'] = $this->load->controller('checkout/coupon');
			$data['voucher'] = $this->load->controller('checkout/voucher');
			$data['reward'] = $this->load->controller('checkout/reward');
			$data['shipping'] = $this->load->controller('checkout/shipping');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/cart.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/checkout/cart.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/checkout/cart.tpl', $data));
			}
		} else {
			$data['heading_title'] = $this->language->get('heading_title');

			$data['text_error'] = $this->language->get('text_empty');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');

			unset($this->session->data['success']);

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
			}
		}
	}
	
	
	
	public function add_screen() {
		$this->language->load('checkout/cart');
		
		$json = array();
		
		if (isset($this->request->post['product_id'])) {
			$product_id = $this->request->post['product_id'];
		} else {
			$product_id = 0;
		}
		
		$this->load->model('catalog/product');
		
		$color_rate_perside = $this->model_catalog_product->getsetupcost();
		$sidecount = 0 ;
		$screenprintingcost  = 0;
		$product_price = 0;
		
		
		$detail = array();
						
		$product_info = $this->model_catalog_product->getProduct($product_id);
		
		if ($product_info) {			
			if (isset($this->request->post['quantity'])) {
				$quantity = $this->request->post['quantity'];
			} else {
				$quantity = 1;
			}
														
			if (isset($this->request->post['option'])) {
				$option = array_filter($this->request->post['option']);
			} else {
				$option = array();	
			}
            
            if (isset($this->request->post['profile_id'])) {
                $profile_id = $this->request->post['profile_id'];
            } else {
                $profile_id = 0;
            }
			
			$product_options = $this->model_catalog_product->getProductOptions($this->request->post['product_id']);
			
			foreach ($product_options as $product_option) {
				if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
					$json['error']['option'][$product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
				}
			}

            $profiles = $this->model_catalog_product->getProfiles($product_info['product_id']);
            
            if ($profiles) {
                $profile_ids = array();
                
                foreach ($profiles as $profile) {
                    $profile_ids[] = $profile['profile_id'];
                }
                
                if (!in_array($profile_id, $profile_ids)) {
                    $json['error']['profile'] = $this->language->get('error_profile_required');
                }
            }
			$data['sizeqty'] = "";
			$qty = $this->cart->countProductsWithoutScreenprinting();
			$qty_color_size = $this->cart->ProductsDetails();
			$data['Quantity'] = $qty;
			$data['Quantity-Colors-Sizes'] = $qty_color_size;
			$data['sides'] = "";
			if($this->request->post['backcolor'] > 0){
					$sidecount = $sidecount + $this->request->post['backcolor'] ;
					$ratebackside = $this->model_catalog_product->getcolorrate($this->request->post['backcolor'],$qty);
					$rate = $ratebackside['rate'];
					$screenprintingcost = $screenprintingcost + ($rate * $qty);
					$data['sides'] .= "Back Color=".$this->request->post['backcolor'].",";
					
			}
			if($this->request->post['frontcolor'] > 0){
					$sidecount = $sidecount + $this->request->post['frontcolor'] ;
					$ratefrontcolor = $this->model_catalog_product->getcolorrate($this->request->post['frontcolor'] ,$qty);
					$rate = $ratefrontcolor['rate'];
					$screenprintingcost = $screenprintingcost + ($rate * $qty);
					$data['sides'] .= "Front Color=".$this->request->post['frontcolor'].",";
						
			}
			if($this->request->post['sidecolor'] > 0){
					$sidecount = $sidecount + $this->request->post['sidecolor'] ;
					$ratesidecolor = $this->model_catalog_product->getcolorrate($this->request->post['sidecolor'],$qty);
					$rate = $ratesidecolor['rate'];
					$screenprintingcost = $screenprintingcost + ($rate * $qty);
					$data['sides'] .= "Side Color=".$this->request->post['sidecolor'];
						
			}
			
			$data['screenprintingcost'] = $this->currency->format($screenprintingcost,'','',false);
			$setup_cost = $sidecount * $color_rate_perside;
			$screenprintingcost = $screenprintingcost + $setup_cost;
			$data['setup_cost'] = $this->currency->format($setup_cost,'','',false);
			$data['price'] 	= 	$this->currency->format($screenprintingcost,'','',false);
			$data['file-upload-name'] = $this->request->post['file-upload-name'];
			$data['image_height'] = $this->request->post['image_height'];
			$data['image_width'] = $this->request->post['image_width'];
			$this->cart->add($this->request->post['product_id'], 1, $option,$profile_id,true,$data,$qty);
			$json['redirect'] = $this->url->link('checkout/cart', '', '');	
			
		}
		
		$this->response->setOutput(json_encode($json));		
	}
	
	public function add() {
		$this->load->language('checkout/cart');
		
		
		//print_r($this->request->post);
		
		$json = array();

		if (isset($this->request->post['product_id'])) {
			$product_id = (int)$this->request->post['product_id'];
		} else {
			$product_id = 0;
		}
		
		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);
//		print_r($product_info);
		if ($product_info) {
			
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

			if (isset($this->request->post['recurring_id'])) {
				$recurring_id = $this->request->post['recurring_id'];
			} else {
				$recurring_id = 0;
			}

			$recurrings = $this->model_catalog_product->getProfiles($product_info['product_id']);

			if ($recurrings) {
				$recurring_ids = array();

				foreach ($recurrings as $recurring) {
					$recurring_ids[] = $recurring['recurring_id'];
				}

				if (!in_array($recurring_id, $recurring_ids)) {
					$json['error']['recurring'] = $this->language->get('error_recurring_required');
				}
			}
			print_r($this->request->post['designs']);
			if(isset($this->request->post['product']) && isset($this->request->post['designs'])){
				
				$product_type_option_value_id =$this->request->post['product']['product_type_option_value_id'];
				if(isset($this->request->post['product']['images']['front'])){
					$data['design_image_front'] = $this->request->post['product']['images']['front'];
				}
				if(isset($this->request->post['product']['images']['back'])){
					$data['design_image_back'] = $this->request->post['product']['images']['back'];
				}
				if(isset($this->request->post['product']['images']['side'])){
					$data['design_image_side'] = $this->request->post['product']['images']['side'];
				}
				$data['sizeqty'] = '';
				$data['Quantity'] = $this->request->post['product']['quantity'];
				//print_r($this->request->post['product']['attribute']);
				$prices = $this->request->post['designs']['item']['prices'];
				
				foreach($prices as $pricename => $amount){
					
					
				}
				foreach($this->request->post['product']['attribute'] as $attribute){
					
					foreach($attribute as $size=>$qty){
					//echo $size.'=>'.$qty;
					$data['sizeqty'] .= $size."*".$product_type_option_value_id."=".$qty.",";
						//$details=$this->model_catalog_product->seekDetail($this->request->post['product_id'],$product_detail);
					}
				}
				$data['sizeqty'] = rtrim($data['sizeqty'], ",");
				$this->cart->add($this->request->post['product_id'], 1, $option,$recurring_id,true,$data,$data['Quantity']);
				//$this->cart->add($this->request->post['product_id'], $quantity,$detail,$recurring_id);
				
			}else{
				if (isset($this->request->post['product_details'])) {
				
    	            $this->load->model('catalog/product');
                	foreach($this->request->post['product_details'] as $product_detail) {
						$quantity = $product_detail['quantity'];
						unset($product_detail['quantity']);
						$details=$this->model_catalog_product->seekDetail($this->request->post['product_id'],$product_detail);
						$detail[0]=$details['product_detail_id'];
						$this->cart->add($this->request->post['product_id'], $quantity,$detail,$recurring_id);
                	}
            	}
			
			}

			if (!$json) {
			//	$this->cart->add($this->request->post['product_id'], $this->request->post['quantity'], $option, $recurring_id);

				$json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']), $product_info['name'], $this->url->link('checkout/cart'));

				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);

				// Totals
				$this->load->model('extension/extension');

				$total_data = array();
				$total = 0;
				$taxes = $this->cart->getTaxes();

				// Display prices
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
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
				}

				$json['total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total));
			} else {
				$json['redirect'] = str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']));
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function edit() {
		$this->load->language('checkout/cart');

		$json = array();

		// Update
		if (!empty($this->request->post['quantity'])) {
			foreach ($this->request->post['quantity'] as $key => $value) {
				$this->cart->update($key, $value);
			}
			
			$this->update_screenprinting();
			
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['reward']);

			$this->response->redirect($this->url->link('checkout/cart'));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function remove() {
		$this->load->language('checkout/cart');

		$json = array();

		// Remove
		if (isset($this->request->post['key'])) {
			$this->cart->remove($this->request->post['key']);
			$this->update_screenprinting();
			unset($this->session->data['vouchers'][$this->request->post['key']]);

			$this->session->data['success'] = $this->language->get('text_remove');

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['reward']);

			// Totals
			$this->load->model('extension/extension');

			$total_data = array();
			$total = 0;
			$taxes = $this->cart->getTaxes();

			// Display prices
			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
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
			}

			$json['total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	
	private function update_screenprinting(){
		
		
		$this->language->load('checkout/cart');
		$json = array();
		$product_id = 2171;
		
		$this->load->model('catalog/product');
		
		$color_rate_perside = $this->model_catalog_product->getsetupcost();
		$sidecount = 0 ;
		$screenprintingcost  = 0;
		$product_price = 0;
		$Back_Color = 0;
		$Front_Color = 0;
		$Side_Color = 0;
		$profile_id = 0;
		$Image_Name = '';
		$Image_Height = '';
		$Image_width = '';
		$detail = array();
						
		$screenprinting_info = $this->cart->getProductInfo($product_id);
		
		if (!empty($screenprinting_info)) {			
			
			foreach($screenprinting_info['option'] as $options){
					
							if($options['name'] == 'Back Color'){
								$Back_Color = $options['value'];
							}
							if($options['name'] == 'Front Color'){
								$Front_Color = $options['value'];
							}
							if($options['name'] == 'Side Color'){
								$Side_Color = $options['value'];
							}
							
							if($options['name'] == 'Image Name'){
								$Image_Name = $options['value'];
							}
							
							if($options['name'] == 'Image width'){
								$Image_width = $options['value'];
							}
							
							if($options['name'] == 'Image Height'){
								$Image_Height = $options['value'];
							}
					}
			$detail = array();
			$option = array();											
			$data['sizeqty'] = "";
			$qty = $this->cart->countProductsWithoutScreenprinting();
			$qty_color_size = $this->cart->ProductsDetails();
			$data['Quantity'] = $qty;
			$data['Quantity-Colors-Sizes'] = $qty_color_size;
			$data['sides'] = "";
			
			if($Back_Color > 0){
					$sidecount = $sidecount + $Back_Color ;
					$ratebackside = $this->model_catalog_product->getcolorrate($Back_Color,$qty);
					$rate = $ratebackside['rate'];
					$screenprintingcost = $screenprintingcost + ($rate * $qty);
					$data['sides'] .= "Back Color=".$Back_Color.",";
					
			}
			if($Front_Color > 0){
					$sidecount = $sidecount + $Front_Color ;
					$ratefrontcolor = $this->model_catalog_product->getcolorrate($Front_Color ,$qty);
					$rate = $ratefrontcolor['rate'];
					$screenprintingcost = $screenprintingcost + ($rate * $qty);
					$data['sides'] .= "Front Color=".$Front_Color.",";
						
			}
			if($Side_Color > 0){
					$sidecount = $sidecount + $Side_Color ;
					$ratesidecolor = $this->model_catalog_product->getcolorrate($Side_Color,$qty);
					$rate = $ratesidecolor['rate'];
					$screenprintingcost = $screenprintingcost + ($rate * $qty);
					$data['sides'] .= "Side Color=".$Side_Color;
						
			}
			
			$data['screenprintingcost'] = $this->currency->format($screenprintingcost,'','',false);
			$setup_cost = $sidecount * $color_rate_perside;
			$screenprintingcost = $screenprintingcost + $setup_cost;
			$data['setup_cost'] = $this->currency->format($setup_cost,'','',false);
			$data['price'] 	= 	$this->currency->format($screenprintingcost,'','',false);
			$data['file-upload-name'] = $Image_Name;
			$data['image_height'] = $Image_Height;
			$data['image_width'] = $Image_width;
			$this->cart->remove($screenprinting_info['key']);
			if($qty > 0){
				$this->cart->add($screenprinting_info['product_id'], 1, $option,0,true,$data,$qty);
			}
			$json['redirect'] = $this->url->link('checkout/cart', '', '');	
			
		}
		
		$this->response->setOutput(json_encode($json));		
		
		
	}
	
	public function add_screen_designer(){
		$this->language->load('checkout/cart');
		
		$json = array();
		
		if (isset($this->request->post['product_id'])) {
			$product_id = $this->request->post['product_id'];
		} else {
			$product_id = 0;
		}
		
		$this->load->model('catalog/product');
		
		$color_rate_perside = $this->model_catalog_product->getsetupcost();
		$sidecount = 0 ;
		$screenprintingcost  = 0;
		$product_price = 0;
		
		
		$detail = array();
						
		$product_info = $this->model_catalog_product->getProduct($product_id);
		
		if ($product_info) {			
			if (isset($this->request->post['quantity'])) {
				$quantity = $this->request->post['quantity'];
			} else {
				$quantity = 1;
			}
														
			if (isset($this->request->post['option'])) {
				$option = array_filter($this->request->post['option']);
			} else {
				$option = array();	
			}
            
            if (isset($this->request->post['profile_id'])) {
                $profile_id = $this->request->post['profile_id'];
            } else {
                $profile_id = 0;
            }
			
			$product_options = $this->model_catalog_product->getProductOptions($this->request->post['product_id']);
			
			foreach ($product_options as $product_option) {
				if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
					$json['error']['option'][$product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
				}
			}

            $profiles = $this->model_catalog_product->getProfiles($product_info['product_id']);
            
            if ($profiles) {
                $profile_ids = array();
                
                foreach ($profiles as $profile) {
                    $profile_ids[] = $profile['profile_id'];
                }
                
                if (!in_array($profile_id, $profile_ids)) {
                    $json['error']['profile'] = $this->language->get('error_profile_required');
                }
            }
			$data['sizeqty'] = "";
			$data['Quantity'] = $this->request->post['qty'];
			$data['Quantity-Colors-Sizes'] = '';
			if (isset($this->request->post['product_details_sc'])) {
                $this->load->model('catalog/product');
                foreach($this->request->post['product_details_sc'] as $product_detail) {
						if($product_detail['quantity'] > 0){
							$price_one = $this->model_catalog_product->getDetailPriceWithpercent($this->request->post['product_id'],$product_detail['quantity'],$product_detail[3],$product_detail[4]);
							$slab = $this->model_catalog_product->getProductSlabThroughQty($product_detail['quantity']);
							$price = $price_one * (1 + ($slab['percent'] / 100));
							$price = $price * $product_detail['quantity'];
							$product_price = $product_price + $price;
							$data['sizeqty'] .= $product_detail[3]."*".$product_detail[4]."=".$product_detail['quantity'].",";
						}
						
                }
            }
			$data['sizeqty'] = rtrim($data['sizeqty'], ",");
			$data['sides'] = "";
			if($this->request->post['backcolor'] > 0){
					$sidecount = $sidecount + $this->request->post['backcolor'] ;
					$ratebackside = $this->model_catalog_product->getcolorrate($this->request->post['backcolor'],$this->request->post['qty']);
					$rate = $ratebackside['rate'];
					$screenprintingcost = $screenprintingcost + ($rate * $this->request->post['qty']);
					$data['sides'] .= "Back Color=".$this->request->post['backcolor'].",";
					
			}
			if($this->request->post['frontcolor'] > 0){
					$sidecount = $sidecount + $this->request->post['frontcolor'] ;
					$ratefrontcolor = $this->model_catalog_product->getcolorrate($this->request->post['frontcolor'] ,$this->request->post['qty']);
					$rate = $ratefrontcolor['rate'];
					$screenprintingcost = $screenprintingcost + ($rate * $this->request->post['qty']);
					$data['sides'] .= "Front Color=".$this->request->post['frontcolor'].",";
						
			}
			if($this->request->post['sidecolor'] > 0){
					$sidecount = $sidecount + $this->request->post['sidecolor'] ;
					$ratesidecolor = $this->model_catalog_product->getcolorrate($this->request->post['sidecolor'],$this->request->post['qty']);
					$rate = $ratesidecolor['rate'];
					$screenprintingcost = $screenprintingcost + ($rate * $this->request->post['qty']);
					$data['sides'] .= "Side Color=".$this->request->post['sidecolor'];
						
			}
			$countname = 0;
		$countnumber = 0;
		$countnameqty = 0;
		$countnumberqty = 0;
		$coutqty = 0;
		$namenumberstring = '';
		$namecost = 0;
		$numbercost = 0;
		$font_name_size = $this->request->post['name-size'];
		$font_number_size = $this->request->post['number-size'];
		
		if(isset($this->request->post['namenumber'])){
				$namenumber = $this->request->post['namenumber'];
				$count = count($namenumber);
				for($i=1;$i<=$count;$i++){
					if(trim($namenumber[$i]['name']) != ''){
						$countname = $countname + 1;
						$countnameqty = $countnameqty + $namenumber[$i]['qty'];
					}
					if(trim($namenumber[$i]['number']) != ''){
						$countnumber = $countnumber + 1;
						$countnumberqty = $countnumberqty + $namenumber[$i]['qty'];
					}
					if(trim($namenumber[$i]['qty']) != ''){
						$coutqty = $coutqty + $namenumber[$i]['qty'];
					}
					$namenumberstring .= $namenumber[$i]['name'].','.$namenumber[$i]['number'].','.$namenumber[$i]['size'].','.$namenumber[$i]['qty'].'-';
				}
				
				$namecost = $this->model_catalog_product->getnamecost($countnameqty,$countname,$font_name_size);
				$numbercost = $this->model_catalog_product->getnumbercost($countnumberqty,$countnumber,$font_number_size);
			}	
			
			unset($data['namenumber']);
				
			$data['screenprintingcost'] = $this->currency->format($screenprintingcost,'','',false);
			$setup_cost = $sidecount * $color_rate_perside;
			$screenprintingcost = $screenprintingcost + $setup_cost + $product_price + $namecost +$numbercost;;
			$data['setup_cost'] = $this->currency->format($setup_cost,'','',false);
			//
			$data['price'] 	= 	$this->currency->format($screenprintingcost,'','',false);
			$data['file-upload-name'] = $this->request->post['file-upload-name'];
			$data['image_height'] = $this->request->post['image_height'];
			$data['image_width'] = $this->request->post['image_width'];
			$data['namecost'] = $this->currency->format($namecost,'','',false);
			$data['numbercost'] = $this->currency->format($numbercost,'','',false);
			$data['namenumberdetails'] = $namenumberstring;
			$data['name_font'] = $this->request->post['name-font'];
			$data['number_font'] = $this->request->post['number-font'];
			$data['name_size'] = $this->request->post['name-size'];
			$data['number_size'] = $this->request->post['number-size'];
			$data['number_color'] = $this->request->post['number-color'];
			$data['name_color'] = $this->request->post['name-color'];
			$data['design_image_name'] = $this->request->post['design_image_name'];
			$data['forground_image_name'] = $this->request->post['forground_image_name'];
			$data['product_price'] = $this->currency->format($product_price,'','',false);
			
			$this->cart->add($this->request->post['product_id'], 1, $option,$profile_id,true,$data,$this->request->post['qty']);
			$json['redirect'] = $this->url->link('checkout/cart', '', 'SSL');	
			
		}
		
		$this->response->setOutput(json_encode($json));		
	}
	function clear(){
		$this->cart->clear();		
	}
}