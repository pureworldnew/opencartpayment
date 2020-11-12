<?php
class ControllerProductWkquickorder extends Controller {
	private $error = array();

	public function index() {

    $data = array();

		$this->load->model('tool/image');
		$this->load->model('tool/upload');
		$this->load->model('extension/extension');
		$this->load->model('catalog/product');
		$data = $this->load->language('product/wk_quick_order');
		$user_agent = isset($this->request->server['HTTP_USER_AGENT'])?$this->request->server['HTTP_USER_AGENT']:'';
		$this->document->setTitle($data['heading_title']);

    if(!$this->config->get('wk_quick_order_status')){
      $this->response->redirect($this->url->link('common/home'));
    }

	$this->document->addScript('catalog/view/javascript/group.product.page.v2.js');  
	$this->document->addScript('catalog/view/javascript/group.product.fix.js'); 

    $data['breadcrumbs'] 		= array();

		$data['breadcrumbs'][] 	= array(
			'text' =>	$data['text_home'],
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] 	= array(
			'text' => $data['text_quick_order'],
			'href' => $this->url->link('product/wk_quick_order')
		);
		if($this->config->get('wk_quick_order_limit')) {
			$data['row_limit'] = $this->config->get('wk_quick_order_limit');
		} else {
			$data['row_limit'] = 20;
		}

		$data['checkout_href'] = $this->url->link('checkout/checkout');

		if ($this->cart->hasProducts()) {
			$products = $this->cart->getProducts();
			foreach($products as $product) {

				if ($product['image']) {
					$image = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'));
				} else {
					$image = '';
				}

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$unit_price = $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'));
					$total = $this->currency->format($unit_price * $product['quantity'], $this->session->data['currency']);
				} else {
					$total = false;
				}

				$option_data = array();
				foreach ($product['option'] as $option) {
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
							'cart_id' 		=> $product['cart_id'],
							'key'   		=> $product['cart_id'],
							'product_id' 	=> $product['product_id'],
							'image'			=> $image,
							'href'			=> $this->url->link('product/product&product_id='.$product['product_id']),
							'unit' 			=> $product['unit'],
							'unit_dates' 	=>$this->model_catalog_product->getUnitDetails($product['product_id']),
							'unit_dates_default' =>$this->model_catalog_product->getDefaultUnitDetails($product['product_id']),
							'DefaultUnitName' =>$this->model_catalog_product->getDefaultUnitName($product['product_id']),
							'quantity' 		=> $product['quantity'],
							'name' 				=> $product['name'],
							'model' 			=> $product['model'],
							'option' 			=> $option_data,
							'price' 			=> $total
				);
			}
		}

    $data['modules'] = array();
    $files = glob(DIR_APPLICATION . '/controller/total/shipping.php');

    if ($files) {
      foreach ($files as $file) {
        $result = $this->load->controller('total/' . basename($file, '.php'));
        if ($result) {
          $data['modules'][] = $result;
        }
      }
    }

		$this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

    $data['column_left'] 		= $this->load->controller('common/column_left');
    $data['column_right']		= $this->load->controller('common/column_right');
    $data['content_top'] 		= $this->load->controller('common/content_top');
    $data['content_bottom'] = $this->load->controller('common/content_bottom');
    $data['footer'] 				= $this->load->controller('common/footer');
	$data['header'] 				= $this->load->controller('common/header');
	
	if($this->isMobile($user_agent) || $this->isTablet($user_agent)){
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/wk_quick_order_mobile.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/wk_quick_order_mobile.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/product/wk_quick_order.tpl', $data));
		}
	}else{
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/wk_quick_order.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/wk_quick_order.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/product/wk_quick_order.tpl', $data));
		}
	}

		
  }

  	public function getProduct() {

		$this->load->model('catalog/product');

      	if(isset($this->request->get['product_name'])){
        	$product_name = $this->request->get['product_name'];
      	}else{
        	$product_name = '';
      	}
		$json = array();
	  	$results = $this->model_catalog_product->getQuickProduct($product_name);
      	foreach ($results as $result) {
			$json[] = array(
				'product_id'  => $result['product_id'],
				'name'        => $result['name'],
				'model'       => $result['model'],
				'price'       => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']),
				'minimum'     => $result['minimum'] ? $result['minimum'] :1,
				'hasoption'   => $result['hasoption']
			);
      	}

      	$this->response->addHeader('Content-Type: application/json');
  		$this->response->setOutput(json_encode($json));
  	}

	public function getProductOption() {

		$data = array();
		$this->load->model('catalog/product');
		$this->load->model('catalog/wk_quick_order');
		$this->load->model('tool/image');

		$this->load->language('product/wk_quick_order');
		$data['text_option'] 		= $this->language->get('text_option');
		$data['text_select'] 		= $this->language->get('text_select');
		$data['button_upload'] 	= $this->language->get('button_upload');
		$data['text_loading'] 	= $this->language->get('text_loading');

		if (isset($this->request->post['product_id'])) {
			$product_id = (int)$this->request->post['product_id'];
		} else {
			$product_id = 0;
		}


		$product_info = $this->model_catalog_product->getProduct($product_id);
		 
		if($product_info){

			if ($product_info['image']) {
				$data['thumb'] = $this->model_tool_image->resize($product_info['image'], 250, 250);
			} else {
				$data['thumb'] = $this->model_tool_image->resize('data/product/no_product.jpg', 250, 250);
			}

			$data['base_price'] = $product_info['orignial_price'];
			$data['unit_datas'] = $this->model_catalog_product->getUnitDetails($product_id); 
			if (isset($product_info['unit_singular'])) {
                $data['unit_singular'] = $product_info['unit_singular'];
            } else {
                $data['unit_singular'] = '';
            }
            if (isset($product_info['unit_plural'])) {
                $data['unit_plural'] = $product_info['unit_plural'];
            } else {
                $data['unit_plural'] = '';
            }

			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				 $prices = $this->model_catalog_product->getProductDiscountByQuantity($product_id,1);
					if(isset($prices['discount']) && $prices['discount'] > 0) {
					$newprice = round(ceil($prices['discount']*100)/100,2);
				}elseif(isset($prices['base_price']) && $prices['base_price'] > 0) {
					$newprice = round(ceil($prices['base_price']*100)/100,2);
				}else {
					$newprice = round(ceil($result['price']*100)/100,2);;
				}
				$price = $this->currency->format($this->tax->calculate($newprice, $product_info['tax_class_id'], $this->config->get('config_tax')));

				 $price_val = $this->cart->geProductCalculatedPrice($product_id);
			} else {
				$price = false;
			}

			if ((float)$product_info['special']) {
				$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$special = false;
			}

			$data['price'] = $price;
			$data['special'] = $special;

			$data['options'] = array();
			foreach ($this->model_catalog_product->getProductOptions($product_id) as $option) {
				//echo "<pre>"; print_r($option); echo "</pre>"; exit;
				$product_option_value_data = array();

				foreach ($option['option_value'] as $option_value) {
					if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
						if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
							$price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false), $this->session->data['currency']);
						} else {
							$price = false;
						}

						$product_option_value_data[] = array(
							'product_option_value_id' => $option_value['product_option_value_id'],
							'option_value_id'         => $option_value['option_value_id'],
							'name'                    => $option_value['name'],
							'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
							'price'                   => $price,
							'price_prefix'            => $option_value['price_prefix']
						);
					}
				}

				$data['options'][] = array(
						 'product_option_id'    => $option['product_option_id'],
						 'product_option_value' => $product_option_value_data,
						 'option_id'            => $option['option_id'],
						 'name'                 => $option['name'],
						 'type'                 => $option['type'],
						 'value'                => isset($option['value']) ? $option['value'] : "",
						 'required'             => $option['required']
				);
			}
		}
		
		$group_product_data = $this->model_catalog_wk_quick_order->getProductConcatData($product_id);
		$data['concat_group_product_options'] = array();

		if($group_product_data)
		{
			$data['group_product_options'] = array();
			for ($i = 1; $i <= 11; $i++) {
				if (!empty($group_product_data['optionname' . $i])) {
					$data['concat_group_product_options'][] = array("optionname" => $group_product_data['optionname' . $i], "optionvalue" => $group_product_data['optionvalue' . $i]);
					$product_option_value_id = $this->model_catalog_wk_quick_order->_optionCheckCreate($product_id, $group_product_data['optionname' . $i], $group_product_data['optionvalue' . $i]);

					if(!empty($product_option_value_id))
					{
						$data['group_product_options'][] = $product_option_value_id;
					}
				}
			}
		} else {
			$data['group_product_options'] = array();
		} 

		//echo "<pre>"; print_r($data); echo "</pre>"; exit; 

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/wk_quick_order_option.tpl')) {
			return $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/wk_quick_order_option.tpl', $data));
		} else {
			return $this->response->setOutput($this->load->view('default/template/product/wk_quick_order_option.tpl', $data));
		}
  }

	public function validateProductOption() {
		$json 	= array();
		$option = array();
		$this->load->model('catalog/product');

		$product_id = $this->request->post['product_id'];
		$option 		= array_filter($this->request->post['option']);

		$cart_id = $this->model_catalog_product->optionValidate($product_id , $option);

		if($cart_id){
			$json['match_status'] = true;
			$json['cart_id'] 			= $cart_id;
		}else{
			$json['match_status'] = false;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

	}

	public function populateOptionData()
	{
		$json 	= array();
		$json['options'] = '';
		$this->load->model('catalog/wk_quick_order');
		$product_id = $this->request->get['product_id'];
		$group_product_data = $this->model_catalog_wk_quick_order->getProductConcatData($product_id);
		if($group_product_data)
		{
			for ($i = 1; $i <= 11; $i++) {
				if (!empty($group_product_data['optionname' . $i])) {
					$json['options'] .= "<br>- <small>" . $group_product_data['optionname' . $i] . ": " . $group_product_data['optionvalue' . $i] . "</small>";
				}
			}
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}


	public function getProductUnitValue($product_id)
	{
		$query = $this->db->query("SELECT unit_conversion_values FROM " . DB_PREFIX . "cart WHERE product_id = '" . (int)$product_id . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "' ORDER BY cart_id DESC LIMIT 1");
		return $query->row ? $query->row['unit_conversion_values'] : 0;
	}

	public function populateUnitData()
	{
		$json 	= array();
		$json['unit_data'] = '';
		$this->load->model('catalog/product');
		$product_id = $this->request->get['product_id'];
		$cart_id = $this->request->get['cart_id'];
		$product_unit_value  = $this->getProductUnitValue($product_id);  
		$unit_dates  = $this->model_catalog_product->getUnitDetails($product_id);
		$unit_dates_default = $this->model_catalog_product->getDefaultUnitDetails($product_id);
		$DefaultUnitName = $this->model_catalog_product->getDefaultUnitName($product_id);
		
		if( !empty( $unit_dates ) )
		{
			$json['unit_data'] .= '<br /><div class="ig_MetalType ig_Units units_grouped"><select class="get-unit-data id_convert_long form-control-custom-select" id="'. $cart_id .'" name="get-unit-data['. $cart_id .']">';

			foreach ($unit_dates as $unit_data) 
			{

				if( $product_unit_value == $unit_data['unit_conversion_product_id'] )
				{
					$checked = 'selected';
				} else {
					$checked = '';
				}
				
				$json['unit_data'] .= '<option  name="'. $unit_data['name']. '" data-value ="'. $unit_data['unit_conversion_product_id']. '" value="'. $unit_data['unit_conversion_product_id']. '" '. $checked .'>'. $unit_data['name']. '</option>';
			}
			
			$json['unit_data'] .= '</select></div>';
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function populateUnitDataMobile()
	{
		$json 	= array();
		$json['unit_data'] = '';
		$this->load->model('catalog/product');
		$product_id = $this->request->get['product_id'];
		$cart_id = $this->request->get['cart_id'];
		$quantity_id = $this->request->get['quantity_id'];
		$quantity = $this->request->get['quantity'];
		$product_unit_value  = $this->getProductUnitValue($product_id);  
		$unit_dates  = $this->model_catalog_product->getUnitDetails($product_id);
		$unit_dates_default = $this->model_catalog_product->getDefaultUnitDetails($product_id);
		$DefaultUnitName = $this->model_catalog_product->getDefaultUnitName($product_id);
		
		if( !empty( $unit_dates ) )
		{
			foreach ($unit_dates as $unit_data) 
			{
				if( $product_unit_value == $unit_data['unit_conversion_product_id'] &&  $unit_data['convert_price'] != 1)
				{
					$json['unit_data'] .= '<br />- <small style="color:#DD0205; font-weight:bold;">'. number_format(($unit_data["convert_price"] * $quantity),2) . '&nbsp;' . $unit_dates_default["name"]  .' = '.  $quantity .'&nbsp;'. $unit_data["name"] .'</small>';
				} 
			}

			$json['unit_data'] .= '<br /><small style="float: left; width: 50%;">Quantity: <input type="number" id="' . $quantity_id . '" class="form-control-custom product-quantity" value="'. $quantity . '"/></small><div class=""><select class="get-unit-data id_convert_long form-control-custom-select" id="'. $cart_id .'" name="get-unit-data['. $cart_id .']">';

			foreach ($unit_dates as $unit_data) 
			{
				if( $product_unit_value == $unit_data['unit_conversion_product_id'] )
				{
					$checked = 'selected';
				} else {
					$checked = '';
				}
				
				$json['unit_data'] .= '<option  name="'. $unit_data['name']. '" data-value ="'. $unit_data['unit_conversion_product_id']. '" value="'. $unit_data['unit_conversion_product_id']. '" '. $checked .'>'. $unit_data['name']. '</option>';
			}
			
			$json['unit_data'] .= '</select></div>';
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function cartEdit() { 
		$json = array();

		$this->load->model('catalog/product');
		$this->load->language('product/wk_quick_order');


		$total_quantity = $this->model_catalog_product->getProductQuantity($this->request->post['product_id']);

		if(empty($this->request->post['product_id']))
		{
			$json['error'] = $this->language->get('error_product');

		} elseif($this->request->post['quantity'] > $total_quantity){
			$json['error'] = sprintf($this->language->get('error_stock'),$total_quantity);

		}else{
			if( !empty( $this->request->post['unit_conversion_value'] ) )
			{
				$this->cart->update($this->request->post['cart_id'],$this->request->post['quantity'],$this->request->post['unit_conversion_value']);
			} else {
				$this->cart->update($this->request->post['cart_id'],$this->request->post['quantity']);
			}
			$products = $this->cart->getProducts();
			$total 		= 0;
			foreach ($products as $product) {
				if($product['cart_id'] == $this->request->post['cart_id']){

					$json['cart_id'] 					= $product['cart_id'];
					$json['product_id'] 			= $product['product_id'];
					$json['product_name'] 		= $product['name'];
					$json['product_quantity'] = $product['quantity'];
					$json['product_price'] 		= $this->currency->format($this->tax->calculate(($product['price']) , $product['tax_class_id'],$this->config->get('config_tax'))*$product['quantity'],$this->session->data['currency']);

				}
				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$total += $this->tax->calculate($product['price'] , $product['tax_class_id'],$this->config->get('config_tax')) * $product['quantity'];
				}
			}
			$json['success'] = $this->language->get('text_success');
			$json['total'] = sprintf($this->language->get('text_total'), $this->cart->countProducts(), $this->currency->format($total, $this->session->data['currency']));
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
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
