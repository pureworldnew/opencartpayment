
<?php
class ControllerProductCategory extends Controller {
	public function index() {
		if ( $this->request->get['route'] == 'product/category' && $this->request->get['path'] == 1 )
		{
			$this->response->redirect($this->url->link('common/home','','SSL'));
		}

		$this->load->language('product/category');

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$this->document->addStyle('catalog/view/theme/gempack/javascript/jquery/owl-carousel/owl.carousel.css');
		$this->document->addStyle('catalog/view/theme/gempack/javascript/jquery/owl-carousel/owl.transitions.css');
		$this->document->addScript('catalog/view/theme/gempack/javascript/jquery/owl-carousel/owl.carousel.min.js');

		if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
		} else {
			$filter = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			if($this->model_catalog_product->virtualSortdefault()) {
				$sort = 'p.date_added'; }
				else {
					$sort = 'p.sort_order';
				}
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			if($this->model_catalog_product->virtualSortdefault()) {
				$order = 'DESC'; }
				else {
					$order = 'ASC';
				}
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limit = $this->config->get('config_product_limit');
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home'),
			'separator' => false
		);

		if (isset($this->request->get['path'])) {
			$pathx = explode('_', $this->request->get['path']);
			$pathx = end($pathx); 
			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$path = '';

			$parts = explode('_', (string)$this->request->get['path']);
			
			$category_id = (int)array_pop($parts);
			
			foreach ($parts as $path_id) {
				if (!$path) {
					$path = (int)$path_id;
				} else {
					$path .= '_' . (int)$path_id;
				}

				$category_info = $this->model_catalog_category->getCategory($path_id);

				if ($category_info) {
					$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('product/category', 'path=' . $path . $url),
						'separator' => $this->language->get('text_separator')
					);
				}
			}
		} else {
			$category_id = 0;
		}

		$category_info = $this->model_catalog_category->getCategory($category_id);

		if ($category_info) {  
			if( !empty($category_info['sort_by']) && !isset($this->request->get['sort']) ) 
			{
				$sort = 'p.' . $category_info['sort_by'];  
			}
			if( !empty($category_info['sorting_order']) && !isset($this->request->get['order']) ) 
			{
				$order = $category_info['sorting_order'];  
			}
			$this->document->setTitle($category_info['meta_title']?$category_info['meta_title']:$category_info['name']);
			$this->document->setDescription($category_info['meta_description']);
			$this->document->setKeywords($category_info['meta_keyword']);
$pathx = explode('_', $this->request->get['path']);
                $pathx = end($pathx);
                $this->document->addLink($this->url->link('product/category', 'path=' . $pathx ), 'canonical');
            $this->document->addScript('catalog/view/javascript/jquery/jquery.total-storage.min.js?v='.rand());
   			$data['category_title'] = $category_info['name'];       
            $data['heading_title'] = $category_info['name'];

			$data['heading_title'] = $category_info['name'];

			$data['text_refine'] = $this->language->get('text_refine');
			$data['text_empty'] = $this->language->get('text_empty');
			$data['text_quantity'] = $this->language->get('text_quantity');
			$data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$data['text_model'] = $this->language->get('text_model');
			$data['text_price'] = $this->language->get('text_price');
			$data['text_tax'] = $this->language->get('text_tax');
			$data['text_points'] = $this->language->get('text_points');
			$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
			$data['text_sort'] = $this->language->get('text_sort');
			$data['text_model'] = $this->language->get('text_model');
			$data['text_limit'] = $this->language->get('text_limit');

			$data['button_cart'] = $this->language->get('button_cart');
			$data['button_wishlist'] = $this->language->get('button_wishlist');
			$data['button_compare'] = $this->language->get('button_compare');
			$data['button_continue'] = $this->language->get('button_continue');
			$data['button_list'] = $this->language->get('button_list');
			$data['button_grid'] = $this->language->get('button_grid');

			// Set the last category breadcrumb
			$data['breadcrumbs'][] = array(
				'text' => $category_info['name'],
				'href' => $this->url->link('product/category', 'path=' . $this->request->get['path']),
				 'separator' => $this->language->get('text_separator')
			);

			if ($category_info['image']) {
				$data['thumb'] = $this->model_tool_image->resize($category_info['image'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
			} else {
				$data['thumb'] = '';
			}

			$data['banner'] = $category_info['banner']; 
			$data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');
			$data['compare'] = $this->url->link('product/compare');

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['categories'] = array();


				if($this->model_catalog_product->virtualStatus() && $category_info['category_id'] == $this->model_catalog_product->virtualId()) {	
		
			// Latest virtual category
			$thiscatid = $this->model_catalog_product->virtualId();
	  	
	  	$getvirtualconf = $this->config->get('latest_virtualcat');
			$productlimit['limit'] = $getvirtualconf['limit'] == 0 || $getvirtualconf['limit'] > $this->model_catalog_product->getTotalProducts() ? $this->model_catalog_product->getTotalProducts() : $getvirtualconf['limit'];

			if($this->model_catalog_product->virtualSortOption()) {
				// Count Date limited products
				$datelimit = $this->model_catalog_product->getLatestProductscountbydate();

				// If Date limit, use that instead of Product limit
				$productlimit['limit'] = $datelimit != 0 ? $datelimit : $productlimit['limit'];
			}
	  
			$data['products'] = array();

			$filter_data = array(
				'filter_category_id' => $category_id,
				'filter_filter'      => $filter, 
				'sort'               => $sort,
				'order'              => $order,
				'start'              => ($page - 1) * $limit,
				'limit'              => $limit,
				'productlimit'		 	 => $productlimit['limit']
			);
	                    
    $product_total = $productlimit['limit'];
    $results = $this->model_catalog_product->getLatestProductsbycat($filter_data);
            
    $data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		
		if (isset($this->request->get['path'])) {
			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$path = '';

			$parts = explode('_', (string)$this->request->get['path']);

			$category_id = (int)array_pop($parts);

			foreach ($parts as $path_id) {
				if (!$path) {
					$path = (int)$path_id;
				} else {
					$path .= '_' . (int)$path_id;
				}

				$topcategory_info = $this->model_catalog_category->getCategory($path_id);

				if ($category_info) {
					$data['breadcrumbs'][] = array(
						'text' => $topcategory_info['name'],
						'href' => $this->url->link('product/category', 'path=' . $path . $url)
					);
				}
			}
		} else {
			$category_id = 0;
		}
		
		if ($category_info) {
			
			$data['breadcrumbs'][] = array(
				'text' => $category_info['name'],
				'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'])
			);
			
			$data['categories'] = array();

			$newresults = $this->model_catalog_category->getCategories($thiscatid);

			foreach ($newresults as $result) {
				$filter_data = array(
					'filter_category_id'  => $result['category_id'],
					'filter_sub_category' => true
				);

				$data['categories'][] = array(
                    'category_id' => $result['category_id'],
					'name'  => $result['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
					'href'  => $this->url->link('product/category', 'path=' . $result['category_id'] . $url)
				);
			}
				
		}

		} else { 
			$results = $this->model_catalog_category->getCategories($category_id);
			$data['products'] = array();
			//print_r($results);
			$product_total = 0;
			if($results){
				foreach ($results as $result) {
					if ($result['image']) {
							$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
						} else {
							$image = false;
						}
					$filter_data = array(
						'filter_category_id'  => $result['category_id'],
						'filter_sub_category' => true
					);
	
					if($this->model_catalog_product->virtualStatus() && $result['category_id'] == $this->model_catalog_product->virtualId()) {
				
			// Latest virtual category
			$getvirtualconf = $this->config->get('latest_virtualcat');
			$productlimit['limit'] = $getvirtualconf['limit'] == 0 || $getvirtualconf['limit'] > $this->model_catalog_product->getTotalProducts() ? $this->model_catalog_product->getTotalProducts() : $getvirtualconf['limit'];

			if($this->model_catalog_product->virtualSortOption()) {
				// Count Date limited products
				$datelimit = $this->model_catalog_product->getLatestProductscountbydate();

				// If Date limit, use that instead of Product limit
				$productlimit['limit'] = $datelimit != 0 ? $datelimit : $productlimit['limit'];
			}
				
			$data['categories'][] = array(
            'category_id' => $result['category_id'],
			'name'  => $result['name'] . ($this->config->get('config_product_count') ? ' ('.$productlimit['limit'].')' : ''),
            'thumb' => $image,
			'href'  => $this->url->link('product/category', 'path=' .$this->request->get['path'] . '_' . $result['category_id'] . $url)
			);
			} else {
				
			$data['categories'][] = array(
                    'category_id' => $result['category_id'],
					'name'  => $result['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
                    'thumb' => $image,
					'href'  => $this->url->link('product/category', 'path=' .$this->request->get['path'] . '_'  . $result['category_id'] . $url)
				);	
				}
					$filter_data = array(
						'filter_category_id' => $category_id,
						'filter_filter'      => $filter,
						'sort'               => $sort,
						'order'              => $order,
						'start'              => ($page - 1) * $limit,
						'limit'              => $limit
					);
		
					$product_total = $this->model_catalog_product->getTotalProducts($filter_data);
				}
			
			}else{
				$filter_data = array(
				'filter_category_id' => $category_id,
				'filter_filter'      => $filter,
				'sort'               => $sort,
				'order'              => $order,
				'start'              => ($page - 1) * $limit,
				'limit'              => $limit
			);

			$product_total = $this->model_catalog_product->getTotalProducts($filter_data);

}
			$results = $this->model_catalog_product->getProducts($filter_data);
}

			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					//$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
					//$price = $this->currency->format($this->tax->calculate($this->cart->geProductCalculatedPrice($result['product_id']), $result['tax_class_id'], $this->config->get('config_tax')));
                     $prices = $this->model_catalog_product->getProductDiscountByQuantity($result['product_id'],1);
						if(isset($prices['discount']) && $prices['discount'] > 0) {
						$newprice = round(ceil($prices['discount']*100)/100,2);
					}elseif(isset($prices['base_price']) && $prices['base_price'] > 0) {
						$newprice = round(ceil($prices['base_price']*100)/100,2);
					}else {
						$newprice = round(ceil($result['price']*100)/100,2);;
					}
					$price = $this->currency->format($this->tax->calculate($newprice, $result['tax_class_id'], $this->config->get('config_tax')));

					 $price_val = $this->cart->geProductCalculatedPrice($result['product_id']);
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
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}
				$productAttributes=array();
				$product_attributes=$this->db->query("SELECT * FROM ".DB_PREFIX."product_attribute WHERE product_id='".$result['product_id']."' ");

				if($product_attributes->num_rows){
					foreach ($product_attributes->rows as $attribut) {
						$productAttributes[]=array(
							'text'=>$this->getAttributeNameById($attribut['attribute_id']),
							'value'=>$attribut['text']
						);
					}
				}

if ($result['date_added']) {
                    $date = $result['date_added'];
                } else {
                    $date = "";
                }
                //out of stock
                if ($result['quantity']) {
                    $stock_quantity = $result['quantity'];
                } else {
                    $stock_quantity = 0;
                }
				$data['products'][] = array(
  'percent'	=> sprintf($this->language->get('text_percent'), (round((($result['price'] -  $result['special'])/$result['price'])*100 ,0))),
                    'percent_value'	=> (round((($result['price'] -  $result['special'])/$result['price'])*100 ,0)),
                    'date'=>$date,
                    'special'     => $special,
                    'stockquantity'=>$stock_quantity,
					'product_id'  => $result['product_id'],
					'model'  => $result['model'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'product_attributes'=>$productAttributes,
					 'unit' => " per " . strtolower($result['unit_singular']),
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $result['rating'],
					'href'        => $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'] . $url)
				);
			}

			/*}*/
			

			
			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			
				

            $this->load->model('localisation/products_label');
            $label_info = $this->model_localisation_products_label->getLabels();

            foreach($label_info as $labelget){
                $data['labels'][]=array(
                    'label_id'=> $labelget['label_id'],
                    'label_text'=> $labelget['label_text'],
                    'label_color'=> $labelget['label_color'],
                    'label_text_color'=> $labelget['label_text_color'],
                    'condition_type'=> $labelget['condition_type'],
                    'status'=> $labelget['status']

                );
                if($labelget['label_id']==1) {
                    $data['new_product_db_days']= $labelget['condition_type'];
                    $data['new_product_db_text']= $labelget['label_text'];
                    $data['new_product_db_text_color']= $labelget['label_text_color'];
                    $data['new_product_db_label_color']= $labelget['label_color'];
                    $data['new_product_status']= $labelget['status'];
                    $data['position_new']=$labelget['position'];

                }
                if($labelget['label_id']==2) {
                    $data['discount_product_db_percent']= $labelget['condition_type'];
                    $data['discount_product_db_text']= $labelget['label_text'];
                    $data['discount_product_db_text_color']= $labelget['label_text_color'];
                    $data['discount_product_db_label_color']= $labelget['label_color'];
                    $data['discount_product_status']= $labelget['status'];
                    $data['position_discount']= $labelget['position'];

                }
                if($labelget['label_id']==3) {
                    $data['shipping_product_db_text']= $labelget['label_text'];
                    $data['shipping_product_db_text_color']= $labelget['label_text_color'];
                    $data['shipping_product_db_label_color']= $labelget['label_color'];
                    $data['custom_product_label_2_status']= $labelget['status'];
                    $data['position_shipping']= $labelget['position'];

                }
                if($labelget['label_id']==4) {
                    $data['outofstock_product_db_text']= $labelget['label_text'];
                    $data['outofstock_product_db_text_color']= $labelget['label_text_color'];
                    $data['outofstock_product_db_label_color']= $labelget['label_color'];
                    $data['outofstock_product_status']= $labelget['status'];
                    $data['position_outofstock']= $labelget['position'];
                }
            }

//take flat shipping rate in shipping_method.php ->controller
              $data['shipping_charge']= $this->config->get('flat_cost');
              $data['install_status'] = $this->config->get('product_label_install_status');

				$data['sorts'] = array();
				
				if($this->model_catalog_product->virtualStatus() && $this->model_catalog_product->virtualSort()) {
					$this->load->language('module/latest_virtualcat');
					
					if($this->model_catalog_product->virtualSortdefault() == false) {
						
						$data['sorts'][] = array(
							'text'  => $this->language->get('text_default'),
							'value' => 'p.sort_order-ASC',
							'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.sort_order&order=ASC' . $url)
						);
					}
					
					$data['sorts'][] = array(
						'text'  => $this->language->get('text_latest_desc'),
						'value' => 'p.date_added-DESC',
						'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.date_added&order=DESC' .$url)
					);
					
					$data['sorts'][] = array(
						'text'  => $this->language->get('text_latest_asc'),
						'value' => 'p.date_added-ASC',
						'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.date_added&order=ASC' .$url)
					);
				} else {

					$data['sorts'][] = array(
						'text'  => $this->language->get('text_default'),
						'value' => 'p.sort_order-ASC',
						'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.sort_order&order=ASC' . $url)
					);
				}				
			

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=DESC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_asc'),
				'value' => 'p.price-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_desc'),
				'value' => 'p.price-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=DESC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_newest'),
				'value' => 'p.date_added-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.date_added&order=DESC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_oldest'),
				'value' => 'p.date_added-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.date_added&order=ASC' . $url)
			);

			if ($this->config->get('config_review_status')) {
				/* $data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=DESC' . $url)
				);

				$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_asc'),
					'value' => 'rating-ASC',
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=ASC' . $url)
				); */
			}

			/* $data['sorts'][] = array(
				'text'  => $this->language->get('text_model_asc'),
				'value' => 'p.model-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_desc'),
				'value' => 'p.model-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=DESC' . $url)
			); */
			
			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$data['limits'] = array();

			$limits = array_unique(array($this->config->get('config_product_limit'), 25, 50, 75, 100));

			sort($limits);

			foreach($limits as $value) {
				$data['limits'][] = array(
					'text'  => $value,
					'value' => $value,
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=' . $value)
				);
			}

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&page={page}');

			$data['pagination'] = $pagination->render();
			
				foreach ($pagination->prevnext() as $pagelink) {
					$this->document->addLink($pagelink['href'], $pagelink['rel']);
				}
				

			$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

			/* // http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html 
			if ($page == 1) {
			    $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'], 'SSL'), 'canonical');
			} elseif ($page == 2) {
			    $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'], 'SSL'), 'prev');
			} else {
			    $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'] . '&page='. ($page - 1), 'SSL'), 'prev');
			}

			if ($limit && ceil($product_total / $limit) > $page) {
			    $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'] . '&page='. ($page + 1), 'SSL'), 'next');
			}

			// */ 
			$data['sort'] = $sort;
			$data['order'] = $order;
			$data['limit'] = $limit;

			$data['continue'] = $this->url->link('common/home');


				$canonicals = $this->config->get('canonicals');
				if (isset($canonicals['canonicals_categories'])) {
					$this->document->removeLink('canonical');
					$pathx = explode('_', $this->request->get['path']);
					$pathx = end($pathx);
					$this->document->addLink($this->url->link('product/category', 'path=' . $pathx .((isset($page) && ($page > 1))?('&page='.$page):'') ), 'canonical');
					}
			
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
			$data['latest_products'] = array();
			$data['tab_latest'] = "Latest " . $data['heading_title'];
			if ( $data['categories'] )
			{ 
				$all_subcat = array();
				foreach ( $data['categories'] as $subcategories )
				{
					$all_subcat[] = $subcategories['category_id'];
				}
				$latest_results = $this->model_catalog_product->getLatestSubCatProducts($all_subcat);
				if ($latest_results) {
					foreach ($latest_results as $result) {
						if ($result['image']) {
							$image = $this->model_tool_image->resize($result['image'], 200, 200);
						} else {
							$image = $this->model_tool_image->resize('placeholder.png', 200, 200);
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
	
						$data['latest_products'][] = array(
							'product_id'  => $result['product_id'],
							'thumb'       => $image,
							'name'        => $result['name'],
							'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
							'price'       => $price,
							'special'     => $special,
							'unit' => " per " . strtolower($result['unit_singular']),
							'tax'         => $tax,
							'rating'      => $rating,
							'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id']),
						);
					}
				}
			}

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/category.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/category.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/product/category.tpl', $data));
			}
		} else {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('product/category', $url)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');


				$canonicals = $this->config->get('canonicals');
				if (isset($canonicals['canonicals_categories'])) {
					$this->document->removeLink('canonical');
					$pathx = explode('_', $this->request->get['path']);
					$pathx = end($pathx);
					$this->document->addLink($this->url->link('product/category', 'path=' . $pathx .((isset($page) && ($page > 1))?('&page='.$page):'') ), 'canonical');
					}
			
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

		public function getAttributeNameById($attribute_id){
			$attr_name=$this->db->query("SELECT name FROM ".DB_PREFIX."attribute_description WHERE attribute_id='".$attribute_id."'  ");

			return $attr_name->row['name'];

	}
}
