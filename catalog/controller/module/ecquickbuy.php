<?php  
class ControllermoduleEcquickbuy extends Controller {
	public function index($setting = array()) {
		
		$setting = $this->config->get('ecquickbuy_module');
		static $module = 0;
		$setting = $this->defaultConfig($setting);
		$current_store_id = $this->config->get('config_store_id');
		$stores = isset($setting['store_id'])?$setting['store_id']:array();
		
		if(!empty($stores) && !in_array($current_store_id, $stores)){
			return;
		}
		
		$this->load->language('module/ecquickbuy');
		$this->load->model('tool/image');
		$this->load->model('catalog/category');
		$this->load->model('catalog/manufacturer');

		if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/ecquickbuy.css?v='.rand())) {
			$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/ecquickbuy.css?v='.rand());
		} else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/ecquickbuy.css?v='.rand());
		}
		$this->document->addScript('catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js?v='.rand());
		$this->document->addStyle('catalog/view/javascript/jquery/colorbox/colorbox.css?v='.rand());
			
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
         	$data['base'] = $this->config->get('config_ssl');
	    } else {
	        $data['base'] = $this->config->get('config_url');
	    }

		//$this->document->addScript('catalog/view/javascript/ecquickbuy/common.js?v='.rand());
		$this->document->addScript('catalog/view/javascript/ecquickbuy/common.unmin.js?v='.rand());

		$route = isset($this->request->get['route'])?$this->request->get['route']:"";
		$data['reload'] = false;
		if($route == "checkout/cart"){
			$data['reload'] = true;
		}

		$data['view_direction'] = $setting['view_direction'];
		$data['limit'] = $setting['limit'];
		$data['show_category'] = $setting['show_category'];
		$data['show_manufacturer'] = $setting['show_manufacturer'];
		$data['all_result'] = $setting['all_result'];
		$data['tag_suggestion'] = $setting['tag_suggestion'];
		$data['show_image'] = $setting['show_image'];
		$data['show_price'] = $setting['show_price'];
		$data['show_qty'] = $setting['show_qty'];
		$data['search_sub_category'] = $setting['search_sub_category'];
		$data['search_description'] = $setting['search_description'];
		$data['show_title'] = $setting['show_title'];
		$data['input_width'] = $setting['input_width'];
		$data['hover_bgcolor'] = $setting['hover_bgcolor'];
		$data['text_color'] = $setting['text_color'];
		$data['popup_width'] = $setting['popup_width'];
		$data['popup_height'] = $setting['popup_height'];
		$data['categories'] = $data["manufacturers"] = array();
		$data['category_id'] = 0;
		
		$data['text_price'] = $this->language->get("text_price");
		$data['text_viewall'] = $this->language->get("text_viewall");
		$data['text_quickview'] = $this->language->get("text_quickview");
		$data['text_ecquickbuy_title'] = $this->language->get("text_ecquickbuy_title");
		$data['text_manufacturer'] = $this->language->get("text_manufacturer");
		$data['text_search_product'] = $this->language->get("text_search_product");
		$data['text_add_to_cart'] = $this->language->get("text_add_to_cart");
		$data['text_ecquickbuy_title'] = $this->language->get("text_ecquickbuy_title");

		/* if($data['show_manufacturer']){
			$this->data["manufacturers"] = $this->model_catalog_manufacturer->getManufacturers();
		}*/
		$data["search_link"] = $this->url->link('product/search', '');
		
		/* if($data['show_category']){
			$categories = $this->model_catalog_category->getCategories(0);
		
			foreach ($categories as $category) {
				
				$children_data = array();

				$children = $this->model_catalog_category->getCategories($category['category_id']);

				foreach ($children as $child) {
					$data = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => true
					);

					$children_data[] = array(
						'category_id' => $child['category_id'],
						'name'        => $child['name'],
						'href'        => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])	
					);		
				}

				$data['categories'][] = array(
					'category_id' => $category['category_id'],
					'name'        => $category['name'],
					'children'    => $children_data,
					'href'        => $this->url->link('product/category', 'path=' . $category['category_id'])
				);
			}

		} */
		
		$data['module'] = $module++;
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/ecquickbuy.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/module/ecquickbuy.tpl', $data);
		}else{
				return $this->load->view('default/template/module/ecquickbuy.tpl', $data);
		}
	}
	public function autocomplete() {
		$json = array();

		$this->language->load('product/category');
		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_manufacturer_id']) || isset($this->request->get['filter_category_id'])) {
			$this->load->model('tool/image');
			$this->load->model('catalog/product');
			
			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}
			
			if (isset($this->request->get['filter_manufacturer_id'])) {
				$filter_manufacturer_id = $this->request->get['filter_manufacturer_id'];
			} else {
				$filter_manufacturer_id = '';
			}

			if (isset($this->request->get['filter_category_id'])) {
				$filter_category_id = $this->request->get['filter_category_id'];
			} else {
				$filter_category_id = '';
			}
			if (isset($this->request->get['description'])) {
				$description = $this->request->get['description'];
			} else {
				$description = '';
			}
			if (isset($this->request->get['sub_category'])) {
				$sub_category = $this->request->get['sub_category'];
			} else {
				$sub_category = '';
			}

			/*if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];	
			} else {*/
				$limit = 20;	
			//}

			$data = array(
				'filter_name'  => $filter_name,
				'filter_tag'   => $filter_name,
				'filter_model' => $filter_name,
				'filter_description' => $description,
				'filter_sub_category' => $sub_category,
				'filter_manufacturer_id' => $filter_manufacturer_id,
				'filter_category_id' => $filter_category_id,
				'start'        => 0,
				'limit'        => $limit
			);

			$results = $this->model_catalog_product->getProducts($data);
			$total = $this->model_catalog_product->getTotalProducts($data);
			$i = 1;
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], 100, 100);
				} else {
					$image = false;
				}
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					//$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
                                        $price = $this->currency->format($this->tax->calculate($this->cart->geProductCalculatedPrice($result['product_id']),$result['tax_class_id'], $this->config->get('config_tax')));
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
				
				$unit = $this->model_catalog_product->getDefaultUnitName($result['product_id']);
				$text_price = "";
				$raw_price = 0;
				if ($price) {
					$text_price .= '<div class="price">';
					if (!$special) {
					 $text_price .= $price;
					 $raw_price = $price;
					} else { 
					 $text_price .= '  <span class="price-old">'.$price.'</span> <span class="price-new">'.$special.'</span>';
					 $raw_price = $special;
					}
					if ($tax) { 
				        $text_price .= '<br />';
				        $text_price .= '<span class="price-tax">'.$this->language->get("text_tax").$tax.'</span>';
			        }
					$text_price .= '</div>';
				}
				//echo 
				if(strtolower($result['model']) == strtolower($filter_name)){
					$json[0] = array(
						'total' => $total,
						'product_id' => $result['product_id'],
						'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),	
						'model'      => $result['model'],
						'image'		 => $image,
						'link'		 => $this->url->link('product/product','product_id='.$result['product_id'], '', 'SSL'),
						'price'      => $text_price,
						'rprice'     => $raw_price,
						'unit'       => $unit
					);		
				}else{
					$json[$i] = array(
						'total' => $total,
						'product_id' => $result['product_id'],
						'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),	
						'model'      => $result['model'],
						'image'		 => $image,
						'link'		 => $this->url->link('product/product','product_id='.$result['product_id'], '', 'SSL'),
						'price'      => $text_price,
						'rprice'     => $raw_price,
						'unit'       => $unit
					);
					$i = $i+1;
				}
				
			}
			
		}

		$this->response->setOutput(json_encode($json));
	}
	protected function defaultConfig($setting = array()){
		$defaults = array('limit'		=> 20,
			'store_id'					=> 0,
			'show_category'				=> 1,
			'show_manufacturer'			=> 1,
			'all_result'				=> 1,
			'tag_suggestion'			=> 1,
			'show_image'				=> 1,
			'show_price'				=> 1,
			'search_sub_category'		=> 0,
			'search_description'		=> 0,
			'show_title'				=> 1,
			'show_qty'					=> 1,
			'popup_width'				=> '800px',
			'popup_height'				=> '550px',
			'input_width'				=> 35,
			'hover_bgcolor'				=> '',
			'view_direction'			=> 'horizontal',
			'text_color'				=> '000000'
			);
		if(!empty($setting)){
			return array_merge($defaults, $setting);
		}
		else{
			return $defaults;
		}
	}
}
?>
