<?php 
/* 
  #file: catalog/controller/product/product_bundle.php
  #powered by fabiom7 - www.fabiom7.com - fabiome77@hotmail.it - copyright fabiom7 2012 - 2013 - 2014
  #switched: v1.5.4.1 - v1.5.5.1 - v1.5.6
*/

class ControllerProductProductBundle extends Controller {
	private $error = array(); 
	
	public function index() {
		$this->language->load('product/product_grouped_mask');
		$this->language->load('product/product_bundle');
		$this->load->model('catalog/product_bundle');
		
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),	
			'separator' => false
		);
		
		$this->load->model('catalog/category');	
		
		if (isset($this->request->get['path'])) {
			$path = '';
				
			foreach (explode('_', $this->request->get['path']) as $path_id) {
				if (!$path) {
					$path = $path_id;
				} else {
					$path .= '_' . $path_id;
				}
				
				$category_info = $this->model_catalog_category->getCategory($path_id);
				
				if ($category_info) {
					$this->data['breadcrumbs'][] = array(
						'text'      => $category_info['name'],
						'href'      => $this->url->link('product/category', 'path=' . $path),
						'separator' => $this->language->get('text_separator')
					);
				}
			}
		}
		
		$this->load->model('catalog/manufacturer');	
		
		if (isset($this->request->get['manufacturer_id'])) {
			$this->data['breadcrumbs'][] = array( 
				'text'      => $this->language->get('text_brand'),
				'href'      => $this->url->link('product/manufacturer'),
				'separator' => $this->language->get('text_separator')
			);	
				
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id']);

			if ($manufacturer_info) {	
				$this->data['breadcrumbs'][] = array(
					'text'	    => $manufacturer_info['name'],
					'href'	    => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id']),					
					'separator' => $this->language->get('text_separator')
				);
			}
		}
		
		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_tag'])) {
			$url = '';
			
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
						
			if (isset($this->request->get['filter_tag'])) {
				$url .= '&filter_tag=' . $this->request->get['filter_tag'];
			}
						
			if (isset($this->request->get['filter_description'])) {
				$url .= '&filter_description=' . $this->request->get['filter_description'];
			}
			
			if (isset($this->request->get['filter_category_id'])) {
				$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
			}	
						
			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_search'),
				'href'      => $this->url->link('product/search', $url),
				'separator' => $this->language->get('text_separator')
			); 	
		}

		if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
		} else {
			$product_id = 0;
		}
		
		$this->load->model('catalog/product');
		
		$product_info = $this->model_catalog_product->getProduct($product_id);
		
// S 1/7 //
		$gp_tpl_q = $this->model_catalog_product_bundle->getProductGroupedType($product_id);
		if(!$gp_tpl_q){
			$this->language->load('product/product'); //for error_text : product not found
			$product_info = false; //ok, go to: template/error/not_found.tpl
		}elseif($gp_tpl_q && $gp_tpl_q['pg_type'] != 'bundle'){
			$this->response->redirect($this->url->link('product/product_'.$gp_tpl_q['pg_type'],'&product_id='.$product_id));
		}
// E 1/7 //
		
		if ($product_info) {
			$url = '';
			
			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}
			
			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}			

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
						
			if (isset($this->request->get['filter_tag'])) {
				$url .= '&filter_tag=' . $this->request->get['filter_tag'];
			}
			
			if (isset($this->request->get['filter_description'])) {
				$url .= '&filter_description=' . $this->request->get['filter_description'];
			}	
						
			if (isset($this->request->get['filter_category_id'])) {
				$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
			}
											
			$this->data['breadcrumbs'][] = array(
				'text'      => $product_info['name'],
				'href'      => $this->url->link('product/product', $url . '&product_id=' . $this->request->get['product_id']),
				'separator' => $this->language->get('text_separator')
			);	
			
// S 2/7 //
			if ($product_info['tag_title']) {
				$this->document->setTitle($product_info['tag_title']);
			} else {
				$this->document->setTitle($product_info['name']);
			}
			
			$this->document->setDescription($product_info['meta_description']);
			$this->document->setKeywords($product_info['meta_keyword']);
			$this->document->addLink($this->url->link('product/product', 'product_id=' . $this->request->get['product_id']), 'canonical');
			
			if(VERSION > '1.5.4.1'){
				$this->document->addScript('catalog/view/javascript/jquery/tabs.js');
				$this->document->addScript('catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js');
				$this->document->addStyle('catalog/view/javascript/jquery/colorbox/colorbox.css');
			}
// E 2/7 //
			
			$this->data['heading_title'] = $product_info['name'];
		
			$this->data['text_select'] = $this->language->get('text_select');
			$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$this->data['text_model'] = $this->language->get('text_model');
			$this->data['text_reward'] = $this->language->get('text_reward');
			$this->data['text_points'] = $this->language->get('text_points');	
			$this->data['text_discount'] = $this->language->get('text_discount');
			$this->data['text_stock'] = $this->language->get('text_stock');
			$this->data['text_price'] = $this->language->get('text_price');
			$this->data['text_tax'] = $this->language->get('text_tax');
			$this->data['text_option'] = $this->language->get('text_option');
			$this->data['text_qty'] = $this->language->get('text_qty');
			//$this->data['text_minimum'] = sprintf($this->language->get('text_minimum'), $product_info['minimum']);
			$this->data['text_or'] = $this->language->get('text_or');
			$this->data['text_write'] = $this->language->get('text_write');
			$this->data['text_note'] = $this->language->get('text_note');
			$this->data['text_share'] = $this->language->get('text_share');
			$this->data['text_wait'] = $this->language->get('text_wait');
			$this->data['text_tags'] = $this->language->get('text_tags');
			
			$this->data['entry_name'] = $this->language->get('entry_name');
			$this->data['entry_review'] = $this->language->get('entry_review');
			$this->data['entry_rating'] = $this->language->get('entry_rating');
			$this->data['entry_good'] = $this->language->get('entry_good');
			$this->data['entry_bad'] = $this->language->get('entry_bad');
			$this->data['entry_captcha'] = $this->language->get('entry_captcha');
			
			$this->data['button_cart'] = $this->language->get('button_cart');
			$this->data['button_wishlist'] = $this->language->get('button_wishlist');
			$this->data['button_compare'] = $this->language->get('button_compare');			
			$this->data['button_upload'] = $this->language->get('button_upload');
			$this->data['button_continue'] = $this->language->get('button_continue');
			
			$this->load->model('catalog/review');

			$this->data['tab_description'] = $this->language->get('tab_description');
			$this->data['tab_attribute'] = $this->language->get('tab_attribute');
			$this->data['tab_review'] = sprintf($this->language->get('tab_review'),$this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id']));
			$this->data['tab_related'] = $this->language->get('tab_related');
			
			$this->data['product_id'] = $this->request->get['product_id'];
			$this->data['manufacturer'] = $product_info['manufacturer'];
			$this->data['manufacturers'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_info['manufacturer_id']);
			
			/*
			$this->data['model'] = $product_info['model'];
			$this->data['reward'] = $product_info['reward'];
			$this->data['points'] = $product_info['points'];
			
			if ($product_info['quantity'] <= 0) {
				$this->data['stock'] = $product_info['stock_status'];
			} elseif ($this->config->get('config_stock_display')) {
				$this->data['stock'] = $product_info['quantity'];
			} else {
				$this->data['stock'] = $this->language->get('text_instock');
			}
			*/
			
			$this->load->model('tool/image');
			
// S 3/7 //
			$image_popup_w = $this->config->get('config_image_popup_width');
			$image_popup_h = $this->config->get('config_image_popup_height');
			$image_thumb_w = $this->config->get('config_image_thumb_width');
			$image_thumb_h = $this->config->get('config_image_thumb_height');
			$image_additional_w = $this->config->get('config_image_additional_width');
			$image_additional_h = $this->config->get('config_image_additional_height');
			
			if ($product_info['image']) {
				$this->data['popup'] = $this->model_tool_image->resize($product_info['image'], $image_popup_w, $image_popup_h);
				$this->data['thumb'] = $this->model_tool_image->resize($product_info['image'], $image_thumb_w, $image_thumb_h);
			} else {
				$this->data['popup'] = '';
				$this->data['thumb'] = '';
			}
			
			$this->data['images'] = array();
			$results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);
			foreach ($results as $result) {
				$this->data['images'][] = array(
					'popup' => $this->model_tool_image->resize($result['image'], $image_popup_w, $image_popup_h),
					'thumb' => $this->model_tool_image->resize($result['image'], $image_additional_w, $image_additional_h)
				);
			}
			
			/*
			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$this->data['price'] = $this->currency->format($this->tax->calculate($product_info['price'],$product_info['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$this->data['price'] = false;
			}
			
			if ((float)$product_info['special']) {
				$this->data['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$this->data['special'] = false;
			}
			
			if ($this->config->get('config_tax')) {
				$this->data['tax'] = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price']);
			} else {
				$this->data['tax'] = false;
			}
			*/
			
			$tcg_config_tax = $this->config->get('config_tax');
			if($tcg_config_tax){
				$this->data['tax'] = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price']);
			}else{
				$this->data['tax'] = false;
			}
			if(($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$tcg_config_customer_price = true;
			}else{
				$tcg_config_customer_price = false;
				$this->data['tax'] = false;
			}
// E 3/7 //
			
			$this->data['discounts'] = array(); /*
			$discounts = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);
			
			$this->data['discounts'] = array(); 
			
			foreach ($discounts as $discount) {
				$this->data['discounts'][] = array(
					'quantity' => $discount['quantity'],
					'price'    => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')))
				);
			} */
			
			$this->data['options'] = array(); /*
			foreach ($this->model_catalog_product->getProductOptions($this->request->get['product_id']) as $option) { 
				if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') { 
					$option_value_data = array();
					
					foreach ($option['option_value'] as $option_value) {
						if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
							if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
								$price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
							} else {
								$price = false;
							}
							
							$option_value_data[] = array(
								'product_option_value_id' => $option_value['product_option_value_id'],
								'option_value_id'         => $option_value['option_value_id'],
								'name'                    => $option_value['name'],
								'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
								'price'                   => $price,
								'price_prefix'            => $option_value['price_prefix']
							);
						}
					}
					
					$this->data['options'][] = array(
						'product_option_id' => $option['product_option_id'],
						'option_id'         => $option['option_id'],
						'name'              => $option['name'],
						'type'              => $option['type'],
						'option_value'      => $option_value_data,
						'required'          => $option['required']
					);					
				} elseif ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
					$this->data['options'][] = array(
						'product_option_id' => $option['product_option_id'],
						'option_id'         => $option['option_id'],
						'name'              => $option['name'],
						'type'              => $option['type'],
						'option_value'      => $option['option_value'],
						'required'          => $option['required']
					);						
				}
			}

			if ($product_info['minimum']) {
				$this->data['minimum'] = $product_info['minimum'];
			} else {
				$this->data['minimum'] = 1;
			} */
			
			$this->data['review_status'] = $this->config->get('config_review_status');
			$this->data['reviews'] = sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']);
			$this->data['rating'] = (int)$product_info['rating'];
			$this->data['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');
			$this->data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);
			
			$this->data['products'] = array();
			
			$results = $this->model_catalog_product->getProductRelated($this->request->get['product_id']);
			
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
				} else {
					$image = false;
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
				
				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}
				
// S 4/7 //
// Controllo e aggiungo i prodotti correlati
				$related_is_grouped = $this->model_catalog_product_bundle->getProductRelatedIsGrouped($result['product_id']);
				if($related_is_grouped && $price){
					if(!(float)$result['pgprice_from']){
						if(!$special){
							$price = $this->language->get('text_price_start') . ' ' . $price;
						}else{
							$price = $this->language->get('text_price_start_special') . ' ' . $price;
						}
					}elseif((float)$result['pgprice_from']){
						$price = $this->language->get('text_price_from');
						$price .= $this->currency->format($this->tax->calculate($result['pgprice_from'], $result['tax_class_id'], $tcg_config_tax));
						if((float)$result['pgprice_to']){
							$price .= $this->language->get('text_price_to');
							$price .= $this->currency->format($this->tax->calculate($result['pgprice_to'], $result['tax_class_id'], $tcg_config_tax));
						}
					}
				}
// E 4/7 //
				
				$this->data['products'][] = array(
					'product_id' => $result['product_id'],
					'thumb'   	 => $image,
					'name'    	 => $result['name'],
					'price'   	 => $price,
					'special' 	 => $special,
					'rating'     => $rating,
					'reviews'    => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
					'href'    	 => $this->url->link('product/product', 'product_id=' . $result['product_id']),
				);
			}	
			
			$this->data['tags'] = array();
					
			$tags = explode(',', $product_info['tag']);
			
			foreach ($tags as $tag) {
				$this->data['tags'][] = array(
					'tag'  => trim($tag),
					'href' => $this->url->link('product/search', 'filter_tag=' . trim($tag))
				);
			}
			
			$this->model_catalog_product->updateViewed($this->request->get['product_id']);

// S 5/7 //
			if ($this->config->get('use_image_column_bundle')) {
				$use_image_column = true;
				$this->data['gp_column_image'] = $this->language->get('gp_column_image');
				$image_column_w = $this->config->get('image_column_bundle_width');
				$image_column_h = $this->config->get('image_column_bundle_height');
				$this->data['img_col_w'] = 'width:' . $image_column_w . 'px;';
				$this->data['img_col_h'] = 'height:' . $image_column_h . 'px;';
			} else {
				$use_image_column = false;
				$this->data['gp_column_image'] = false;
			}
			
		//S switch
			if (!$this->config->get('use_image_replace_bundle')) {
				$this->data['use_image_replace'] = false;
				$use_image_replace = false;
			} else {
				$this->data['use_image_replace'] = true;
				$use_image_replace = true;
			}
			
			if ($pgd = $this->model_catalog_product_bundle->getProductGroupedDiscount($product_id)) {
				$this->data['gp_discount'] = sprintf($this->language->get('text_discount_bundle'), $this->currency->format($pgd['discount']));
			} else {
				$this->data['gp_discount'] = false;
			}
			
			if (isset($this->request->get['error'])) {
				$this->data['error_bundle'] = $this->language->get('error_bundle');
			} else {
				$this->data['error_bundle'] = false;
			}
			
			$this->data['button_cart_out'] = $this->language->get('button_cart_out');
			$this->data['text_total'] = $this->language->get('text_total');
			$this->data['action_add_bundle'] = $this->url->link('checkout/cart/addBundle');
		//E switch
			
			$product_master_name = $product_info['name'];
			$this->data['tcg_config_customer_price'] = $tcg_config_customer_price;
			$this->data['tcg_template'] = $this->config->get('config_template');
	
			$minimun_text = $this->language->get('text_minimum');
			$this->data['text_sku'] = $this->language->get('text_sku');
			$this->data['text_rrp'] = $this->language->get('text_rrp');
			$this->data['text_review_for'] = $this->language->get('text_review_for');
			$this->data['text_review_all'] = $this->language->get('text_review_all');
			
			$use_topimage_additional = $this->config->get('use_topimage_additional_bundle');
			$use_subimage_additional = $this->config->get('use_subimage_additional_bundle');
			$use_sku = $this->config->get('use_sku');
			$use_rating = $this->config->get('use_rating');
			$use_saving = $this->config->get('pguse_saving');
			$use_child_descriptions = $this->config->get('use_child_descriptions_bundle');
			$use_popup_details = $this->config->get('use_popup_details_bundle');
	
		////////
			$product_grouped = array();
			$gruppi = $this->model_catalog_product_bundle->getGrouped($product_id);

			foreach ($gruppi as $gruppo) {
				$product_info = $this->model_catalog_product->getProduct($gruppo['grouped_id']);

				if ($product_info) {			
					if ($product_info['image']) {
						if ($use_topimage_additional) {
							$this->data['images'][] = array(
								'popup' => $this->model_tool_image->resize($product_info['image'], $image_popup_w, $image_popup_h),
								'thumb' => $this->model_tool_image->resize($product_info['image'], $image_additional_w, $image_additional_h),
								'name'  => $product_info['name']
							);
						}
						if ($use_subimage_additional) {
							$results = $this->model_catalog_product->getProductImages($gruppo['grouped_id']);
							foreach ($results as $result) {
								$this->data['images'][] = array(
									'popup' => $this->model_tool_image->resize($result['image'], $image_popup_w, $image_popup_h),
									'thumb' => $this->model_tool_image->resize($result['image'], $image_additional_w, $image_additional_h),
									'name'  => $product_info['name']
								);
							}
						}
						if ($use_image_replace) {
							$image_replace = $this->model_tool_image->resize($product_info['image'], $image_thumb_w, $image_thumb_h);
						} else {
							$image_replace = false;
						}
						if ($use_image_column) {
							$image_column = $this->model_tool_image->resize($product_info['image'], $image_column_w, $image_column_h);
							$image_column_popup = $this->model_tool_image->resize($product_info['image'], $image_popup_w, $image_popup_h);
						} else {
							$image_column = false;
							$image_column_popup = false;
						}
					} else {
						if ($use_image_replace) {
							$image_replace = $this->data['thumb'];
						} else {
							$image_replace = false;
						}
						if ($use_image_column) {
							$image_column = $this->model_tool_image->resize('no_image.jpg', $image_column_w, $image_column_h);
							$image_column_popup = $this->model_tool_image->resize('no_image.jpg', $image_popup_w, $image_popup_h);
						} else {
							$image_column = false;
							$image_column_popup = false;
						}
					}

			// (!)option before product price
			$this_data_options = array();
			foreach ($this->model_catalog_product->getProductOptions($product_info['product_id']) as $option) { 
				if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') { 
					$option_value_data = array();
					
					foreach ($option['option_value'] as $option_value) {
						if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
							if ($tcg_config_customer_price && (float)$option_value['price']) {
								$price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
							} else {
								$price = false;
							}
							
							$option_value_data[] = array(
								'product_option_value_id' => $option_value['product_option_value_id'],
								'option_value_id'         => $option_value['option_value_id'],
								'name'                    => $option_value['name'],
								'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
								'price'                   => $price,
								'price_prefix'            => $option_value['price_prefix']
							);
						}
					}
					
					$this_data_options[] = array(
						'product_option_id' => $option['product_option_id'],
						'option_id'         => $option['option_id'],
						'name'              => $option['name'],
						'type'              => $option['type'],
						'option_value'      => $option_value_data,
						'required'          => $option['required']
					);					
				} elseif ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
					$this_data_options[] = array(
						'product_option_id' => $option['product_option_id'],
						'option_id'         => $option['option_id'],
						'name'              => $option['name'],
						'type'              => $option['type'],
						'option_value'      => $option['option_value'],
						'required'          => $option['required']
					);						
				}
			}

					if ($this_data_options) {
						$gp_column_option = '1';
					}

					if ($product_info['quantity'] <= 0) {
						$stock = $product_info['stock_status'];
					} elseif ($this->config->get('config_stock_display')) {
						$stock = $product_info['quantity'];
					} else {
						$stock = $this->language->get('text_instock');
					}

					// Disable add to cart
					if ($product_info['quantity'] <= 0 && $product_info['stock_status_id'] == $gruppo['grouped_stock_status_id']) {
						$out_of_stock = true;
					} else {
						$out_of_stock = false;
					}

					// Add RRP DR extension
					if (isset($product_info['rr_price']) && (float)$product_info['rr_price']) {
						$rr_price = $this->currency->format($this->tax->calculate($product_info['rr_price'], $product_info['tax_class_id'], $tcg_config_tax));
						$price_max = max($product_info['rr_price'], $product_info['price']);
					} else {
						$rr_price = false;
						$price_max = $product_info['price'];
					}

					$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $tcg_config_tax));
			
					if ((float)$product_info['special']) {
						$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $tcg_config_tax));
						$price_min = $product_info['special'];
						$price_value_ex_tax = $product_info['special'];
					} else {
						$special = false;
						$price_min = $product_info['price'];
						$price_value_ex_tax = $product_info['price'];
					}

					$price_value = $this->tax->calculate($price_value_ex_tax, $product_info['tax_class_id'], $tcg_config_tax);

					if ($tcg_config_tax) {
						$tax = $this->currency->format($price_value_ex_tax);
					} else {
						$tax = false;
					}

					$discounts = array(); 
					foreach ($this->model_catalog_product->getProductDiscounts($product_info['product_id']) as $discount) {
						$discounts[] = array(
							'quantity' => $discount['quantity'],
							'price'    => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $tcg_config_tax))
						);
					}

					$saving = false;
					if ($use_saving && (float)$price_max && (float)$price_min) {
						$calc = ($price_max - $price_min) / $price_max * 100;
						if ($calc) {
							$save_fixed = $this->currency->format($this->tax->calculate($price_max,$product_info['tax_class_id'],$tcg_config_tax) - $this->tax->calculate($price_min,$product_info['tax_class_id'],$tcg_config_tax));
							$save_percent = round($calc) . '%';
							switch ($use_saving) { 
								case 'p'  : $saving = sprintf($this->language->get('text_save'), $save_percent); break;
								case 'f'  : $saving = sprintf($this->language->get('text_save'), $save_fixed); break;
								case 'p:f': $saving = sprintf($this->language->get('text_save'), $save_percent) . ' (' . $save_fixed . ')'; break;
								case 'f:p': $saving = sprintf($this->language->get('text_save'), $save_fixed) . ' (' . $save_percent . ')'; break;
								case 'p:t': $saving = sprintf($this->language->get('text_save_t'), $save_fixed, $save_percent); break;
								case 'f:t': $saving = sprintf($this->language->get('text_save_t'), $save_percent, $save_fixed); break;
							}
						}
					}

					if (!$tcg_config_customer_price) {
						$rr_price = false;
						$price = false;
						$special = false;
						$price_value = 0;
						$price_value_ex_tax = 0;
						$tax = false;
						$discounts = array();
						$saving = false;
					}

					if ($use_popup_details && $product_info['description']) {
						$details = $this->url->link('product/product_grouped/compareInfo', 'product=' . $product_info['product_id']);
					} else {
						$details = false;
					}

					if ($use_child_descriptions && $product_info['description']) { 
						$this->data['description'] .= '<br /><div class="gp-append-child-name">' . $product_info['name'] . '</div><div class="gp-append-child-description">' . html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8') . '</div>';
					}

					$name = str_replace($product_master_name,'',$product_info['name']);

					$product_grouped[] = array(
						'product_id'         => $product_info['product_id'],
						'details'            => $details,
						'image_replace'      => $image_replace,
						'image_column'       => $image_column,
						'image_column_popup' => $image_column_popup,
						'name'               => $name,
						'manufacturer'       => $product_info['manufacturer'],
						'manufacturers'      => $this->url->link('product/manufacturer/info','manufacturer_id='.$product_info['manufacturer_id']),
						'model'              => $product_info['model'],
						'sku'                => $use_sku ? $product_info['sku'] : false,
						'reward'             => $product_info['reward'],
						'points'             => $product_info['points'],
						'quantity'           => $product_info['quantity'],
						'price'              => $price,
						'rr_price'           => $rr_price,
						'special'            => $special,
						'saving'             => $saving,
						'rating'             => $use_rating ? (int)$product_info['rating'] : false,
						'price_value'        => $price_value,
						'price_value_ex_tax' => $price_value_ex_tax,
						'tax'                => $tax,
						'discounts'          => $discounts,
						'options'            => $this_data_options,
						'minimum'            => ($product_info['minimum'] ? $product_info['minimum'] : 1),
						'minimum_text'       => sprintf($minimun_text,$product_info['minimum']),
						'stock'              => $stock,
						'out_of_stock'       => $out_of_stock,
						'weight'             => $product_info['weight'],
						'maximum'            => $gruppo['grouped_maximum']
					);
				}
			}

			$this->data['gp_column_name'] = $this->language->get('gp_column_name');
			if(isset($gp_column_option)){ $this->data['gp_column_option'] = $this->language->get('gp_column_option'); }else{ $this->data['gp_column_option'] = '0'; }
			$this->data['gp_column_price'] = $this->language->get('gp_column_price');
			$this->data['gp_column_qty'] = $this->language->get('gp_column_qty');
			
			$this->data['product_grouped'] = $product_grouped;
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/product_bundle_' . $gp_tpl_q['pg_layout'] . '.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/product/product_bundle_' . $gp_tpl_q['pg_layout'] . '.tpl';
			} else {
				$this->template = 'default/template/product/product_bundle_' . $gp_tpl_q['pg_layout'] . '.tpl';
			}
// E 5/7 //

			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);
						
			$this->response->setOutput($this->render());
		} else {
			$url = '';
			
			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}
			
			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}			

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}	
					
			if (isset($this->request->get['filter_tag'])) {
				$url .= '&filter_tag=' . $this->request->get['filter_tag'];
			}
							
			if (isset($this->request->get['filter_description'])) {
				$url .= '&filter_description=' . $this->request->get['filter_description'];
			}
					
			if (isset($this->request->get['filter_category_id'])) {
				$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
			}
								
      		$this->data['breadcrumbs'][] = array(
        		'text'      => $this->language->get('text_error'),
				'href'      => $this->url->link('product/product', $url . '&product_id=' . $product_id),
        		'separator' => $this->language->get('text_separator')
      		);			
		
      		$this->document->setTitle($this->language->get('text_error'));

      		$this->data['heading_title'] = $this->language->get('text_error');

      		$this->data['text_error'] = $this->language->get('text_error');

      		$this->data['button_continue'] = $this->language->get('button_continue');

      		$this->data['continue'] = $this->url->link('common/home');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
			} else {
				$this->template = 'default/template/error/not_found.tpl';
			}
			
			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);
						
			$this->response->setOutput($this->render());
    	}
  	}

	public function review() {
    	$this->language->load('product/product');
		
		$this->load->model('catalog/review');

		$this->data['text_on'] = $this->language->get('text_on');
		$this->data['text_no_reviews'] = $this->language->get('text_no_reviews');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}  
		
		$this->data['reviews'] = array();
		
		$review_total = $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id']);
		
		$results = $this->model_catalog_review->getReviewsByProductId($this->request->get['product_id'], ($page - 1) * 5, 5);
      	
		foreach ($results as $result) {
        	$this->data['reviews'][] = array(
        		'author'     => $result['author'],
				'text'       => $result['text'],
				'rating'     => (int)$result['rating'],
        		'reviews'    => sprintf($this->language->get('text_reviews'), (int)$review_total),
        		'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
        	);
      	}			
			
		$pagination = new Pagination();
		$pagination->total = $review_total;
		$pagination->page = $page;
		$pagination->limit = 5; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('product/product/review', 'product_id=' . $this->request->get['product_id'] . '&page={page}');
			
		$this->data['pagination'] = $pagination->render();
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/review.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/review.tpl';
		} else {
			$this->template = 'default/template/product/review.tpl';
		}
		
		$this->response->setOutput($this->render());
	}
	
	public function write() {
		$this->language->load('product/product');
		
		$this->load->model('catalog/review');
		
		$json = array();
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
				$json['error'] = $this->language->get('error_name');
			}
			
			if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
				$json['error'] = $this->language->get('error_text');
			}
	
			if (empty($this->request->post['rating'])) {
				$json['error'] = $this->language->get('error_rating');
			}
	
			if (empty($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
				$json['error'] = $this->language->get('error_captcha');
			}
				
			if (!isset($json['error'])) {
// S 6/7 //
				if ($this->request->get['grouped_id']) {
					$this->model_catalog_review->addReview($this->request->get['grouped_id'], $this->request->post);
				}
// E 6/7 //
				$this->model_catalog_review->addReview($this->request->get['product_id'], $this->request->post);
				
				$json['success'] = $this->language->get('text_success');
			}
		}
		
		$this->response->setOutput(json_encode($json));
	}
	
	public function captcha() {
		$this->load->library('captcha');
		
		$captcha = new Captcha();
		
		$this->session->data['captcha'] = $captcha->getCode();
		
		$captcha->showImage();
	}
	
	public function upload() {
		$this->language->load('product/product');
		
		$json = array();
		
		if (!empty($this->request->files['file']['name'])) {
			$filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8')));
			
			if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 64)) {
        		$json['error'] = $this->language->get('error_filename');
	  		}	  	
			
			$allowed = array();
			
			$filetypes = explode(',', $this->config->get('config_upload_allowed'));
			
			foreach ($filetypes as $filetype) {
				$allowed[] = trim($filetype);
			}
			
			if (!in_array(substr(strrchr($filename, '.'), 1), $allowed)) {
				$json['error'] = $this->language->get('error_filetype');
       		}	
						
			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
			}
		} else {
			$json['error'] = $this->language->get('error_upload');
		}
		
		if (!$json) {
			if (is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])) {
				$file = basename($filename) . '.' . md5(mt_rand());
				
				// Hide the uploaded file name so people can not link to it directly.
				$json['file'] = $this->encryption->encrypt($file);
				
				move_uploaded_file($this->request->files['file']['tmp_name'], DIR_DOWNLOAD . $file);
			}
						
			$json['success'] = $this->language->get('text_upload');
		}	
		
		$this->response->setOutput(json_encode($json));		
	}
	
	public function compareInfo() {
		$this->language->load('product/compare');
		
		$this->data['title'] = $this->language->get('text_product');
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}
		
		$this->data['base'] = $server;
		$this->data['direction'] = $this->language->get('direction');
		$this->data['lang'] = $this->language->get('code');
		
		if ($this->config->get('config_icon') && file_exists(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->data['icon'] = $server . 'image/' . $this->config->get('config_icon');
		} else {
			$this->data['icon'] = '';
		}
				
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		
		$this->data['text_product'] = $this->language->get('text_product');
		$this->data['text_name'] = $this->language->get('text_name');
		$this->data['text_image'] = $this->language->get('text_image');
		$this->data['text_price'] = $this->language->get('text_price');
		$this->data['text_model'] = $this->language->get('text_model');
		$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$this->data['text_availability'] = $this->language->get('text_availability');
		$this->data['text_rating'] = $this->language->get('text_rating');
		$this->data['text_summary'] = $this->language->get('text_summary');
		$this->data['text_weight'] = $this->language->get('text_weight');
		$this->data['text_dimension'] = $this->language->get('text_dimension');
		$this->data['text_empty'] = $this->language->get('text_empty');
		
		$this->data['review_status'] = $this->config->get('config_review_status');
		
		$this->data['products'] = array();
		
		$this->data['attribute_groups'] = array();

		$compare_id = explode('-', $this->request->get['product']);
		
		foreach ($compare_id as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);
			
			if ($product_info) {	
				if ($product_info['image']) {
					$image = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_compare_width'), $this->config->get('config_image_compare_height'));
				} else {
					$image = false;
				}
				
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}
				
				if ((float)$product_info['special']) {
					$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}
				
				if ($product_info['quantity'] <= 0) {
					$availability = $product_info['stock_status'];
				} elseif ($this->config->get('config_stock_display')) {
					$availability = $product_info['quantity'];
				} else {
					$availability = $this->language->get('text_instock');
				}
				
				$attribute_data = array();
				
				$attribute_groups = $this->model_catalog_product->getProductAttributes($product_id);
				
				foreach ($attribute_groups as $attribute_group) {
					foreach ($attribute_group['attribute'] as $attribute) {
						$attribute_data[$attribute['attribute_id']] = $attribute['text'];
					}
				}
				
				$this->data['products'][$product_id] = array(
					'product_id'   => $product_info['product_id'],
					'name'         => $product_info['name'],
					'thumb'        => $image,
					'price'        => $price,
					'special'      => $special,
					'description'  => strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')),
					'model'        => $product_info['model'],
					'manufacturer' => $product_info['manufacturer'],
					'availability' => $availability,
					'rating'       => (int)$product_info['rating'],
					'reviews'      => sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']),
					'weight'       => $this->weight->format($product_info['weight'], $product_info['weight_class_id']),
					'length'       => $this->length->format($product_info['length'], $product_info['length_class_id']),
					'width'        => $this->length->format($product_info['width'], $product_info['length_class_id']),
					'height'       => $this->length->format($product_info['height'], $product_info['length_class_id']),
					'attribute'    => $attribute_data
				);
				
				foreach ($attribute_groups as $attribute_group) {
					$this->data['attribute_groups'][$attribute_group['attribute_group_id']]['name'] = $attribute_group['name'];
					
					foreach ($attribute_group['attribute'] as $attribute) {
						$this->data['attribute_groups'][$attribute_group['attribute_group_id']]['attribute'][$attribute['attribute_id']]['name'] = $attribute['name'];
					}
				}
			}
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/product_grouped_details.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/product_grouped_details.tpl';
		} else {
			$this->template = 'default/template/product/product_grouped_details.tpl';
		}
		
		$this->children = array(
			'common/content_top',
			'common/content_bottom'
		);
		
		$this->response->setOutput($this->render());
	}
	
// S 7/7 //	
	public function updateSumPrice() {
		$json = array();
		
		$json['text_sum_price'] = $this->currency->format($this->request->post['bundle_price_sum']);
		$json['text_sum_price_ex_tax'] = $this->currency->format($this->request->post['bundle_price_sum_ex_tax']);
		
		$this->response->setOutput(json_encode($json));
	}
// E 7/7 //
}
?>