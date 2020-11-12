<?php 
class ControllerQuickCheckoutCart extends Controller {
	public function index() {
		$data = $this->load->language('checkout/checkout');
		
		$data = array_merge($data, $this->load->language('quickcheckout/checkout'));
		
		// Totals
		$this->load->model('extension/extension');
		$this->load->model('catalog/product');
		$total_data = array();					
		$total = 0;
		$taxes = $this->cart->getTaxes();
		
		if (version_compare(VERSION, '2.2.0.0', '>=')) {
			$total_data = array(
				'totals' => &$totals,
				'taxes'  => &$taxes,
				'total'  => &$total
			);
		}
		
		// Display prices
		$data['totals'] = array();
		
		
		
		$this->load->model('tool/image');
		$this->load->model('tool/upload');
		
		$data['products'] = array();

		$products = $this->cart->getProducts(10);
		$data['lowest_cart_id'] = $this->cart->getLowestCartID();
		$pricevalue=0;
		$producttotal = 0;
		foreach ($products as $product) {
			$product_total = 0;
			if(!isset($product['minimum'])){
				$product['minimum'] = 1;
			}
			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$redirect = $this->url->link('checkout/cart');

				break;
			}
			
		}
		if(null!==$this->config->get('subcategorypercentage_category')){
				$categories=implode(",",$this->config->get('subcategorypercentage_category'));
			}else{
				$categories=0;
			}
		foreach ($products as $product) {
				$pricevalue = 0;
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
				if(isset($product['option'])){
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
						$totalnonformated = $this->currency->format($this->tax->calculate(($product['price']/$product['unit']['convert_price']), $product['tax_class_id'], $this->config->get('config_tax')) * ($product['quantity'] * $product['unit']['convert_price']),'','',0);
						$total = $this->currency->format($this->tax->calculate(($product['price']/$product['unit']['convert_price']), $product['tax_class_id'], $this->config->get('config_tax')) * ($product['quantity'] * $product['unit']['convert_price']));
						$total = $this->currency->format($this->tax->calculate(($product['price']/$product['unit']['convert_price']), $product['tax_class_id'], $this->config->get('config_tax')) * ($product['quantity'] * $product['unit']['convert_price']));
					}else{
						$totalnonformated =$this->currency->format($this->tax->calculate(($product['price']), $product['tax_class_id'], $this->config->get('config_tax')) * ($product['quantity']),'','',0);
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
			
			 $getWiredProducts=$this->db->query("SELECT * FROM ".DB_PREFIX."product_to_category WHERE product_id='".$product['product_id']."' AND category_id IN(".$categories.") ");
			 //print_r($getWiredProducts->rows);
			if($getWiredProducts->num_rows){
				$pricevalue = $totalnonformated;
			}
			//echo $pricevalue;
			if($pricevalue > 0){
					  
					  if(null!==$this->config->get('config_additional_percentage')){
							$additional_percentage= $this->config->get('config_additional_percentage')/100;
							$total_val=$pricevalue*$additional_percentage;
						 }
						
					 if(isset($total_val)){
						$producttotal+=$total_val;
					}
			
		}
		
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
		if($producttotal && $this->config->get('subcategorypercentage_status')){
					$data['totals'][]=array('desc'=>'additional','title'=>'Authorization for Additional cost of wire','text'=>$this->currency->format($producttotal, $this->session->data['currency']));
					}
		// Gift Voucher
		$data['vouchers'] = array();

		if (!empty($this->session->data['vouchers'])) {
			foreach ($this->session->data['vouchers'] as $key => $voucher) {
				if (version_compare(VERSION, '2.2.0.0', '<')) {
					$amount = $this->currency->format($voucher['amount']);
				} else {
					$amount = $this->currency->format($voucher['amount'], $this->session->data['currency']);
				}
				
				$data['vouchers'][] = array(
					'key'         => $key,
					'description' => $voucher['description'],
					'amount'      => $amount,
					'remove'      => $this->url->link('checkout/cart', 'remove=' . $key)
				);
			}
		}
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
		
					if (version_compare(VERSION, '2.2.0.0', '<')) {
						$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
					} else {
						$this->{'model_total_' . $result['code']}->getTotal($total_data);
					}
				}
			}
			
			if (version_compare(VERSION, '2.2.0.0', '>=')) {
				$total_data = $totals;
			}
				
			$sort_order = array(); 
		  
			foreach ($total_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $total_data);

			foreach ($total_data as $total) {
				if($total['title'] == 'Total'){
					if($producttotal){
						if (version_compare(VERSION, '2.2.0.0', '<')) {
							$text = $this->currency->format($total['value']+$producttotal);
						} else {
							$text = $this->currency->format(($total['value']+$producttotal), $this->session->data['currency']);
						}
					}else{
						if (version_compare(VERSION, '2.2.0.0', '<')) {
							$text = $this->currency->format($total['value']);
						} else {
							$text = $this->currency->format($total['value'], $this->session->data['currency']);
						}
						
						
					}
				
					$data['totals'][] = array(
						'title' => $total['title'],
						'text'  => $text
					);
				}else{
					if (version_compare(VERSION, '2.2.0.0', '<')) {
						$text = $this->currency->format($total['value']);
					} else {
						$text = $this->currency->format($total['value'], $this->session->data['currency']);
					}
				
					$data['totals'][] = array(
						'title' => $total['title'],
						'text'  => $text
					);
				}
			}		
		}
		 	
		if (isset($this->session->data['shipping_address']['zone_id']) && $this->session->data['shipping_address']['zone_id'] == 3624) {
        	   $data['resale_status'] = 1;
        } else {
           	$data['resale_status'] = 0;
        }
		
		
		 if (isset($this->session->data['resale_id_number'])) {
                $data['resale_id_number'] = $this->session->data['resale_id_number'];
            } else {
                $data['resale_id_number'] = '';
            }
		// All variables
		$data['edit_cart'] = $this->config->get('quickcheckout_edit_cart');
		$data['voucher'] = $this->load->controller('quickcheckout/voucher');
		if (version_compare(VERSION, '2.2.0.0', '<')) {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/quickcheckout/cart.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/quickcheckout/cart.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/quickcheckout/cart.tpl', $data));
			}
		} else {
			$this->response->setOutput($this->load->view('quickcheckout/cart', $data));
		}
	}
	
	public function update() {
		$json = array();
		
		if (!empty($this->request->post['quantity'])) {
			foreach ($this->request->post['quantity'] as $key => $value) {
				$this->cart->update($key, $value);
			}
		}
		
		if (isset($this->request->get['remove'])) {
			$this->cart->remove($this->request->get['remove']);
			
			unset($this->session->data['vouchers'][$this->request->get['remove']]);
		}
		
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');
		}
		
		if ($this->cart->getTotal() < $this->config->get('quickcheckout_minimum_order')) {
			$json['redirect'] = $this->url->link('checkout/cart');
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));	
	}
	
	public function validateResale() {
        
        $this->language->load('quickcheckout/checkout');
        $json = array();
		$flag = 0;
        if (!$this->cart->hasProducts()) {
            $json['redirect'] = $this->url->link('checkout/cart');
        }
        if (isset($this->request->post['resale_id_number'])) {
            if ($this->request->post['resale_id_number'] == "") {
                $json['error'] = $this->language->get('error_resale_number');
				$json['error'] = $this->language->get('error_resale_number');
            } else {
				$this->request->post['resale_id_number'] = trim(str_replace('+','',$this->request->post['resale_id_number']));
				
				if ((utf8_strlen($this->request->post['resale_id_number']) > 14)) {
					$json['error'] = $this->language->get('error_resale_number');
					$json['error_html'] = str_replace("\n","<br>",$this->language->get('error_resale_number'));
				}else{
					if(preg_match('/^[A-Z]{4}-[0-9]{9}$/i', $this->request->post['resale_id_number'])){
						$flag = 1;
					}else if(preg_match('/^[A-Z]{4}[0-9]{9}$/i', $this->request->post['resale_id_number'])){
						$flag = 1;
					}else if(preg_match('/^[0-9]{9}$/', $this->request->post['resale_id_number'])){
						$flag = 1;
					}else{
						$json['error'] = $this->language->get('error_resale_number');
						$json['error_html'] = str_replace("\n","<br>",$this->language->get('error_resale_number'));
					}
						
				}
				if($flag == 1){
					$json['success'] = "Success: Your Resale id Number has been applied! ";
                	$this->session->data['resale_id_number'] = $this->request->post['resale_id_number'];
                	$this->session->data['success'] = "Success: Your Resale id Number has been applied! ";
                	if($this->customer->isLogged()){
						$this->customer->setResaleNumber($this->request->post['resale_id_number']);
					}
				}
            }
        }
       $this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
    }
}