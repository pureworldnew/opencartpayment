<?php
class ControllerCheckoutCart extends Controller {
	
	public function index() {
			if($this->customer->isLogged()){
              $this->db->query("DELETE FROM ".DB_PREFIX."cart WHERE customer_id='".$this->customer->getId()."' AND session_id='".$this->session->getId()."' AND is_admin='1' ");
		       }

		$this->language->load('product/product_grouped_mask');
		$this->load->language('checkout/cart');
		$this->load->model('catalog/product');
		$this->document->setTitle($this->language->get('heading_title'));
		$user_agent = isset($this->request->server['HTTP_USER_AGENT'])?$this->request->server['HTTP_USER_AGENT']:'';
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/home'),
			'text' => $this->language->get('text_home')
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('checkout/cart'),
			'text' => $this->language->get('heading_title')
		);
		if ( isset( $this->session->data['pos_cart'] ) )
		{
			unset($this->session->data['pos_cart']);
		}
		if ($this->cart->hasProducts() || !empty($this->session->data['vouchers'])) {
			
			foreach ($this->cart->getProducts() as $product) {

			$gp_config_q = $this->db->query("SELECT pg_type FROM " . DB_PREFIX . "product_grouped_type WHERE product_id = '" . (int)$product['product_id'] . "' AND pg_type = 'configurable'");
				
				if ($gp_config_q->num_rows && $product['reward'] == 1) {
					
					$product_points_sum = 0;
					$product_points_apply = true;
					
					foreach ($product['option'] as $configurable_option) {
						if ($configurable_option['points']) {
							$product_points_sum += $configurable_option['points'];
						} else {
							$product_points_apply = false;
						}
					}
					
					$points_total = $product_points_apply ? $product_points_sum : 0;
				
				}else if ($product['points']) {
                    $points_total += $product['points'];
                }
            }
			
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

			$data['action'] = $this->url->link('checkout/cart/edit', '', true);

			if ($this->config->get('config_cart_weight')) {
				$data['weight'] = $this->weight->format($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
			} else {
				$data['weight'] = '';
			}

			$this->load->model('tool/image');
			$this->load->model('tool/upload');

			$data['products'] = array();

			// Check for minimum in whole cart
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
				
				if ($product['minimum_amount'] > $product['total']) {
					$minimum_amount = "$" . number_format((float)$product['minimum_amount'], 2, '.', '');
					$data['error_warning'] = sprintf($this->language->get('error_minimum'), $product['name'], $minimum_amount);
				}
			}
			// End of Check for minimum in whole cart

			$products = $this->cart->getProducts(10); 
			$data['lowest_cart_id'] = $this->cart->getLowestCartID();

			foreach ($products as $product) {
				$product_total = 0;

				foreach ($products as $product_2) {
					if ($product_2['product_id'] == $product['product_id']) {
						$product_total += $product_2['quantity'];
					}
				}

				/* if ($product['minimum'] > $product_total) {
					$data['error_warning'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);
				}
				
				if ($product['minimum_amount'] > $product['total']) {
					$minimum_amount = "$" . number_format((float)$product['minimum_amount'], 2, '.', '');
					$data['error_warning'] = sprintf($this->language->get('error_minimum'), $product['name'], $minimum_amount);
				} */
				
				
				if ($product['image']) {
					if($this->isMobile($user_agent) || $this->isTablet($user_agent)){
						$image = $this->model_tool_image->resize($product['image'],150, 150);
					}else{
						$image = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'));
					}
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
			
				$data['products'][] = array(
					'cart_id'   => $product['cart_id'],
					'key'   => $product['cart_id'],
					'thumb'     => $image,
					'name'      => $product['name'],
					'model'     => $product['model'],
					'option'    => $option_data,
					'unit' => $product['unit'],
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

			$files = glob(DIR_APPLICATION . '/controller/total/*.php');

			if ($files) {
				foreach ($files as $file) {
					$extension = basename($file, '.php');

					$data[$extension] = $this->load->controller('total/' . $extension);
				}
			}

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
			
			
				if($this->isMobile($user_agent) || $this->isTablet($user_agent)){
					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/mobile_cart.tpl')) {
						$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/checkout/mobile_cart.tpl', $data));
					} else {
						$this->response->setOutput($this->load->view('default/template/checkout/mobile_cart.tpl', $data));
					}
				}else{
					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/cart.tpl')) {
						$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/checkout/cart.tpl', $data));
					} else {
						$this->response->setOutput($this->load->view('default/template/checkout/cart.tpl', $data));
					}
					
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

	public function loadMoreData()
	{
		$this->load->model('tool/image');
		$this->load->model('tool/upload');
		$this->load->model('catalog/product');
		$last_cart_id = $this->request->post['last_cart_id']; 
		$data['products'] = array();

			$products = $this->cart->loadMoreProducts($last_cart_id, 10);

			$data['lowest_cart_id'] = $this->cart->getLowestCartID();

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
				
				if ($product['minimum_amount'] > $product['total']) {
					$minimum_amount = "$" . number_format((float)$product['minimum_amount'], 2, '.', '');
					$data['error_warning'] = sprintf($this->language->get('error_minimum'), $product['name'], $minimum_amount);
				}
				
				
				if ($product['image']) {
					if($this->isMobile($user_agent) || $this->isTablet($user_agent)){
						$image = $this->model_tool_image->resize($product['image'],150, 150);
					}else{
						$image = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'));
					}
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
			
				$data['products'][] = array(
					'cart_id'   => $product['cart_id'],
					'key'   => $product['cart_id'],
					'thumb'     => $image,
					'name'      => $product['name'],
					'model'     => $product['model'],
					'option'    => $option_data,
					'unit' => $product['unit'],
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
		if( isset( $this->request->post['page'] ) && $this->request->post['page'] == 'checkout' )
		{
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/load_more_checkout.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/checkout/load_more_checkout.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/checkout/load_more_checkout.tpl', $data));
			}
		} elseif( isset( $this->request->post['page'] ) && $this->request->post['page'] == 'mobile_cart' )
		{
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/load_more_mobile.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/checkout/load_more_mobile.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/checkout/load_more_mobile.tpl', $data));
			}
		} else {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/load_more.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/checkout/load_more.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/checkout/load_more.tpl', $data)); 
			}
		}
		
	}

	public function add() {
		$this->load->language('checkout/cart');
		$this->load->model('catalog/product');
		$json = array();

		if (isset($this->request->post['product_id'])) {
			$product_id = (int)$this->request->post['product_id'];
		} else if (isset($this->request->post['product_sku'])) {
            $product_data = $this->model_catalog_product->getProductIdBySku('\''.$this->request->post['product_sku'].'\'');

            if ($product_data) {
                $product_id = $product_data;
                $this->request->post['product_id'] = $product_id;
            } else {
                $json['error']['wrong_id'] = "Product not found";
                return $this->response->setOutput(json_encode($json));
            }
        }else {
			$product_id = 0;
		}

		

		$product_info = $this->model_catalog_product->getProduct($product_id);
		if ($product_info['model'] == 'grouped') {
				$json['redirect'] = str_replace('&amp;', '&', $this->url->link('product/product_grouped', 'product_id=' . $this->request->post['product_id'], '', 'SSL'));
			}
		if ($product_info) {
			if (isset($this->request->post['quantity']) && ((int)$this->request->post['quantity'] >= $product_info['minimum'])) {
				$quantity = (float)$this->request->post['quantity'];
			} else {
				$quantity = $product_info['minimum'] ? $product_info['minimum'] : 1;
			}
			
			if (isset($this->request->post['unit_conversion_values'])) {
				$unit_conversion_values = $this->request->post['unit_conversion_values'];
			} else {
				$unit_conversion_values = 0;
			}
			
			$product_unit_price = (!empty($unit_conversion_values) || $unit_conversion_values != 0) ? $this->cart->getUnitPriceOfProduct($product_id, $unit_conversion_values, $product_info['price']) : $product_info['price'];
			
			$product_cart_total = $this->cart->getProductTotal($product_id);
			if ( ( $quantity * $product_unit_price ) + $product_cart_total < $product_info['minimum_amount'] )
			{
				$minimum_amount = "$" . number_format((float)$product_info['minimum_amount'], 2, '.', '');
				$json['error']['minimum'] = sprintf("The minimum order amount for this product is %s . We apologize for the inconvenience.",$minimum_amount);
			}

			if (isset($this->request->post['option'])) {
				$option = array_filter($this->request->post['option']);
				//Group Products
				foreach($option as $product_option_id => $product_option_value_id)
				{
				   $valid_product_option_id = $this->model_catalog_product->isValidProductOption($this->request->post['product_id'], $product_option_id);
				   if(!$valid_product_option_id)
				   {
						
						$valid_product_option_from_db = $this->model_catalog_product->getValidProductOptionFromDb($this->request->post['product_id'], $product_option_value_id);
						if($valid_product_option_from_db)
						{
							unset($option[$product_option_id]);
							$new_product_option_id = $valid_product_option_from_db['product_option_id'];
							$new_product_option_value_id = $valid_product_option_from_db['product_option_value_id'];
							$option[$new_product_option_id] = $new_product_option_value_id;
						}
					}
				}
				///End Group Products
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
			
			if (!$json) {
				$this->cart->add($this->request->post['product_id'], $quantity, $option, $recurring_id,$unit_conversion_values);
				$data = $this->cart->getProducts();
				$json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']), $product_info['name'], $this->url->link('checkout/cart'));

				// Unset all shipping and payment methods
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

                  if($this->config->get('wk_quick_order_status') && isset($this->request->post['quick'])){
										$this->load->model('tool/image');
										$products = $this->cart->getProducts();
										foreach ($products as $product) {
											if($product['product_id'] == $product_id){
												if ($product['image']) {
													$image = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'));
												} else {
													$image = '';
												}
												$json['cart_id'] = $product['cart_id'];
												$json['product_id'] = $product['product_id'];
												$json['image'] = $image;
												$json['href']	 = $this->url->link('product/product&product_id='.$product['product_id']);
												$json['product_name'] = $product['name'];
												$json['product_quantity'] = $product['quantity'];
												$json['product_price'] = $this->currency->format($this->tax->calculate($product['price']*$product['quantity'] , $product_info['tax_class_id'],$this->config->get('config_tax')),$this->session->data['currency']);
											}
										}
                    if($this->cart->hasShipping()){
											$this->load->model('extension/extension');
									    $data['modules'] = array();
									    $files = glob(DIR_APPLICATION . '/controller/total/shipping.php');
									    if ($files) {
									      foreach ($files as $file) {
									        $result = $this->load->controller('total/' . basename($file, '.php'));
									        if ($result) {
									          $json['modules'][] = $result;
									        }
									      }
									    }
										}
									}
		            
				$json['total_desktop'] = $this->currency->format($this->cart->getTotal());
				$json['product_id']=$this->request->post['product_id'];
				$json['model']=$product_info['model'];
				$json['quantity']=$quantity;
				$json['options']=$option;
				$json['cart_total']=$this->cart->getTotal();
				$json['unit_dates']=$this->model_catalog_product->getUnitDetails($this->request->post['product_id']);
				$this->cart->writeLogForOrder('add_to_cart',serialize($json));

			} else {
				$json['redirect'] = str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $this->request->post['product_id'], '', 'SSL'));
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
                $json['cart_id']=$key;
				
				if(isset($this->request->post['get-unit-data'][$key])){
					$this->cart->update($key, $value,$this->request->post['get-unit-data'][$key]);
					$json['values']= $value;
					$json['unit_conversion_values']=$this->request->post['get-unit-data'][$key];
				}else{
				
					$this->cart->update($key, $value,0);
					$json['values']= $value;
					$json['unit_conversion_values']=0;
				}

				$this->cart->writeLogForOrder('update_add_to_cart',serialize($json));
            }
            unset($this->session->data['shipping_method']);
            unset($this->session->data['shipping_methods']);
            unset($this->session->data['payment_method']);
            unset($this->session->data['payment_methods']);
            unset($this->session->data['reward']);

            $this->response->redirect($this->url->link('checkout/cart', '', 'SSL'));
        }

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function removeSelected() {
		$json = array();
		$this->load->language('checkout/cart');
		if (isset($this->request->post['selected'])) {

			$selected = explode(",", $this->request->post['selected']); 
			foreach ( $selected as $cart_id )
			{
				$this->cart->remove($cart_id);
			}

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

	public function remove() {
		$this->load->language('checkout/cart');

		$json = array();

		// Remove
		if (isset($this->request->post['key'])) {
			$this->cart->remove($this->request->post['key']);

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
	
	public function lengthId($val, $from, $to) {
        
        $this->load->model("catalog/product");
        $data = $this->model_catalog_product->getLengthClasses($from, $to);
        foreach ($data as $newdata) {
            if ($newdata['name'] == $from) {
                $from_id = $newdata['value'];
            }
            if ($newdata['name'] == $to) {
                $to_id = $newdata['value'];
            }
        }
        $result = $this->length->convert($val, $from_id, $to_id);
        return $result;
    }

    public function weightId($val, $from, $to) {
        $this->load->model("catalog/product");
        $data = $this->model_catalog_product->getWeightClasses($from, $to);
        foreach ($data as $newdata) {
            if ($newdata['name'] == $from) {
                $from_id = $newdata['value'];
            }
            if ($newdata['name'] == $to) {
                $to_id = $newdata['value'];
            }
        }
        $result = $this->weight->convert($val, $from_id, $to_id);
        return $result;
    }
	
			public function addBundle() {
			$this->load->model('catalog/product');
			
			if (!empty($this->request->post['quantity'])) {
				$error_cart = false;
				$quantity_check = 0;
				
				foreach ($this->request->post['quantity'] as $product_id => $quantity) {
					$quantity_check += $quantity;
					 
					$product_info = $this->model_catalog_product->getProduct($product_id);
					
					if ($product_info && $quantity) {
						if (isset($this->request->post['option'])) {
							$option = array_filter($this->request->post['option']);
						} else {
							$option = array();
						}
						
						$product_options = $this->model_catalog_product->getProductOptions($product_id);
						
						foreach ($product_options as $product_option) {
							if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
								$error_cart = true;
							}
						}
					}
				}
				
				if (!$error_cart) {
					foreach ($this->request->post['quantity'] as $product_id => $quantity) {
						$product_info = $this->model_catalog_product->getProduct($product_id);
						
						if ($product_info) {
							if (isset($this->request->post['option'])) {
								$option = array_filter($this->request->post['option']);
							} else {
								$option = array();
							}
							
							$this->cart->addBundle($product_id,$quantity,$option); //go to library
							unset($this->session->data['shipping_method']);
							unset($this->session->data['shipping_methods']);
							unset($this->session->data['payment_method']);
							unset($this->session->data['payment_methods']);
							//unset($this->session->data['reward']);
						}
					}
				}
			}
			
			if (!$error_cart && $quantity_check) {
				$this->response->redirect($this->url->link('checkout/cart'));	
			} else {
				$this->response->redirect($this->url->link('product/product_bundle','product_id='.$this->request->post['product_id'].'&error=1'));
			}
		}
		

		public function addConfig() {
			if(isset($this->request->post['product_id'])){
				$product_id = $this->request->post['product_id'];
			}else{
				$product_id = 0;
			}
			
			$this->load->model('catalog/product');
			
			$product_info = $this->model_catalog_product->getProduct($product_id);
			
			if ($product_info) {
				if(isset($this->request->post['quantity'])){
					$quantity = $this->request->post['quantity'];
				}else{
					$quantity = 1;
				}
								
				if(isset($this->request->post['option'])){
					$option = array_filter($this->request->post['option']);
				}else{
					$option = array();
				}
				
				$error_cart = false;
				
				if(!$option){
					$error_cart = '1&cqty=' . $this->request->post['quantity'];
					
				}elseif((float)$product_info['weight']){
					$peso_min = $product_info['weight'] - ($product_info['weight'] / 100 * $this->config->get('weight_allow_config_min'));
					$peso_max = $product_info['weight'] + ($product_info['weight'] / 100 * $this->config->get('weight_allow_config_max'));
					if($this->request->post['weight_sum'] < $peso_min){
						$error_cart = '4&cqty=' . $this->request->post['quantity'] . '&cset=' . base64_encode(serialize($option));
					}elseif($this->request->post['weight_sum'] > $peso_max){
						$error_cart = '5&cqty=' . $this->request->post['quantity'] . '&cset=' . base64_encode(serialize($option));
					}
					
				}elseif($option){
					foreach($option as $grouped_id => $qty){
						$config_option_qty_q = $this->db->query("SELECT option_type, option_min_qty FROM " . DB_PREFIX . "product_grouped_configurable WHERE product_id = '" . (int)$product_id . "' AND child_id LIKE '%" . $grouped_id . ",%' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");
						
						if(substr($config_option_qty_q->row['option_min_qty'],0,1) > $qty){
							$error_cart = '2&cqty=' . $this->request->post['quantity'] . '&cset=' . base64_encode(serialize($option));
						}
						
						$grouped_option[] = $config_option_qty_q->row['option_type'];
					}
					
					$config_option_all_q = $this->db->query("SELECT option_required, option_type FROM " . DB_PREFIX . "product_grouped_configurable WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");
					
					foreach($config_option_all_q->rows as $key => $value){
						if($value['option_required'] && !in_array($value['option_type'], $grouped_option)){
							$error_cart = '3&cqty=' . $this->request->post['quantity'] . '&cset=' . base64_encode(serialize($option));
						}
					}
				}
				
				if(!$error_cart){
					if(isset($this->session->data['cart']) && $this->request->post['current_set']){
						unset($this->session->data['cart'][$product_id . ':' . $this->request->post['current_set']]);
					}
					
					$this->cart->addConfig($product_id,$quantity,$option); //go to library
					unset($this->session->data['shipping_method']);
					unset($this->session->data['shipping_methods']);
					unset($this->session->data['payment_method']);
					unset($this->session->data['payment_methods']);
					//unset($this->session->data['reward']);
				}
			}
			
			if(!$error_cart){
				$this->response->redirect($this->url->link('checkout/cart'));
			}else{
				$this->response->redirect($this->url->link('product/product_configurable', 'product_id=' . $product_id . '&error=' . $error_cart));
			}
		}
		

                
                    public function addQuickMobile() {
                        $this->load->model('catalog/product');
                        $this->load->model('tool/image');
                        $product_id = $this->request->post['product_id'];
                        if(isset($this->request->post['quantity'])) {
                            $quantity = $this->request->post['quantity'];
                        } else {
                            $quantity = 1;
                        } 
                        $product = $this->model_catalog_product->getProduct($product_id);
                     
                        $options = $this->model_catalog_product->getProductOptions($product_id);
                        $data = $this->model_catalog_product->getUnitDetails($product_id);
                        $this->data['name'] = $product['name'];
                        $this->data['image'] = $this->model_tool_image->resize($product['image'],91,84);
                        $this->data['price'] =  $this->currency->format($product['price']);
                        $this->data['discounted_price'] = $this->currency->format($product['discounted_price']);
                        $this->data['options'] = $options;
                        $this->data['unit_datas']=$data;
                        $this->data['quantity'] = $quantity;
                        $this->data['unit_singular'] = $product['unit_singular'];
                        $this->data['unit_plural'] = $product['unit_plural'];
                        $this->response->setOutput(json_encode($this->data));
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
