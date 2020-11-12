<?php
class ControllerExtensionFeedTwengaBasePro extends Controller {

	public function __construct($registry) {
        parent::__construct($registry);
        $this->delimiterView = '~';
        $this->useView = false;

        $this->language_id = !empty($this->request->get['language_id']) ? $this->request->get['language_id'] : $this->config->get('config_language_id');
        $this->config->set('config_language_id', $this->language_id);
        $result = $this->db->query('SELECT `code` FROM '.DB_PREFIX.'language WHERE language_id = '.$this->language_id);
        $this->session->data['language'] = $result->row['code'];

        $currency_code = !empty($this->request->get['currency_code']) ? $this->request->get['currency_code'] : '';
        if(empty($currency_code))
        	$currency_code = version_compare(VERSION, '2.2.0.0', '>=') ? $this->session->data['currency'] : $this->currency->getCode();

        $currencies = array(
			'AED','AFN','ALL','AMD','ANG','AOA','ARS','AUD','AWG','AZN','BAM','BBD','BDT','BGN','BHD','BIF','BMD','BND','BOB','BOV','BRL','BSD','BTN','BWP','BYR','BZD','CAD','CDF','CHE','CHF','CHW','CLF','CLP','CNY','COP','COU','CRC','CUP','CVE','CYP','CZK','DJF','DKK','DOP','DZD','EEK','EGP','ERN','ETB','EUR','FJD','FKP','GBP','GEL','GHS','GIP','GMD','GNF','GTQ','GYD','HKD','HNL','HRK','HTG','HUF','IDR','ILS','INR','IQD','IRR','ISK','JMD','JOD','JPY','KES','KGS','KHR','KMF','KPW','KRW','KWD','KYD','KZT','LAK','LBP','LKR','LRD','LSL','LTL','LVL','LYD','MAD','MDL','MGA','MKD','MMK','MNT','MOP','MRO','MTL','MUR','MVR','MWK','MXN','MXV','MYR','MZN','NAD','NGN','NIO','NOK','NPR','NZD','OMR','PAB','PEN','PGK','PHP','PKR','PLN','PYG','QAR','RON','RSD','RUB','RWF','SAR','SBD','SCR','SDG','SEK','SGD','SHP','SKK','SLL','SOS','SRD','STD','SYP','SZL','THB','TJS','TMM','TND','TOP','TRY','TTD','TWD','TZS','UAH','UGX','USD','USN','USS','UYU','UZS','VEB','VND','VUV','WST','XAF','XAG','XAU','XBA','XBB','XBC','XBD','XCD','XDR','XFO','XFU','XOF','XPD','XPF','XPT','XTS','XXX','YER','ZAR','ZMK','ZWD'
		);

		if (!in_array($currency_code, $currencies)) {
			$currency_code = 'USD';
			$currency_value = $this->currency->getValue('USD');
		} else {
			$currency_value = $this->currency->getValue($currency_code);
		}

		$this->currency_code = $currency_code;
		$this->currency_value = $currency_value;

        //Devman Extensions - info@devmanextensions.com - 2017-09-18 19:42:42 - Get configuration
        	$store_id = $this->config->get('config_store_id');
        	$config_name = $this->request->get['configuration'] ? urlencode($this->request->get['configuration']) : 'No configuration name';

        	$twenga_base_pro_configurations_url = DIR_APPLICATION.'controller/extension/feed/twenga_base_pro_configurations_backup.json';
        	$twenga_base_pro_configurations_url_path = DIR_APPLICATION.'controller/feed/twenga_base_pro_configurations_backup.json';
        	$twenga_base_pro_configurations_url2 = DIR_APPLICATION.'controller/extension/feed/twenga_base_pro_configurations.json';

        	$str = file_exists($twenga_base_pro_configurations_url_path) ? file_get_contents($twenga_base_pro_configurations_url) : file_get_contents($twenga_base_pro_configurations_url2);
        	$configuration = json_decode($str, true);

        	$configuration_found = array_key_exists('store_'.$store_id, $configuration) && array_key_exists($config_name, $configuration['store_'.$store_id]) ? true : die('Configuration not found');

        	$this->configuration = $configuration['store_'.$store_id][$config_name];
        //END

        //Get all options
			require('admin/model/catalog/option.php');
			$optionModel = new ModelCatalogOption( $this->registry );
			$options = $optionModel->getOptions( true );

			$all_options = array();
			foreach ($options as $key => $option) {
				if(!isset($all_options[$option['option_id']]))
					$all_options[$option['option_id']] = array();

				$option_values = $optionModel->getOptionValues( $option['option_id'] );

				foreach ($option_values as $key2 => $optva) {
					$all_options[$option['option_id']][$optva['option_value_id']] = $optva['name'];
				}
			}

			$this->all_options = $all_options;
		//END Get all options
	}
	public function index() {
		
		ini_set('memory_limit',-1);
		ini_set('max_execution_time',500000000);

		$id = $this->config->get('config_store_id');

		$status = array_key_exists('google_twenga_base_pro_status_'.$id, $this->configuration) ? $this->configuration['google_twenga_base_pro_status_'.$id] : false;

		$more_oc_1541 = version_compare(VERSION, '1.5.4.1', '>');

		$this->load->model('extension/feed/google_base_pro');

		//Categories allowed configuration
			if(version_compare(VERSION, '1.5.3.1', '>'))
				$categories = $this->model_extension_feed_google_base_pro->getCategories();
			else
				$categories = $this->model_extension_feed_google_base_pro->getCategories_oc_old();

			$categories_allowed = array();

			foreach ($categories as $key => $cat) {
				if(!empty($this->configuration['google_twenga_base_pro_category_allowed_'.$cat['category_id'].'_'.$id]))
					$categories_allowed[] = $cat['category_id'];
			}
		//END Categories allowed configuration

		if (!empty($status)) {
			//Get configuration
				$google_twenga_base_pro_thumbs_width = array_key_exists('google_twenga_base_pro_thumbs_width_'.$id, $this->configuration) && !empty($this->configuration['google_twenga_base_pro_thumbs_width_'.$id]) ? $this->configuration['google_twenga_base_pro_thumbs_width_'.$id] : 600;
				$google_twenga_base_pro_thumbs_height = array_key_exists('google_twenga_base_pro_thumbs_height_'.$id, $this->configuration) && !empty($this->configuration['google_twenga_base_pro_thumbs_height_'.$id]) ? $this->configuration['google_twenga_base_pro_thumbs_height_'.$id] : 600;

				$google_twenga_base_pro_ignore_products = array_key_exists('google_twenga_base_pro_ignore_products_'.$id, $this->configuration) && !empty($this->configuration['google_twenga_base_pro_ignore_products_'.$id]) ? explode(',', $this->configuration['google_twenga_base_pro_ignore_products_'.$id]) : array();

				$only_these_products = !empty($this->configuration['google_twenga_base_pro_only_these_products_'.$id]) ? $this->configuration['google_twenga_base_pro_only_these_products_'.$id] : false;

				$google_twenga_base_pro_option_split = array_key_exists('google_twenga_base_pro_option_split_'.$id, $this->configuration) ? $this->configuration['google_twenga_base_pro_option_split_'.$id] : false;

				$google_twenga_base_pro_show_out_stock = array_key_exists('google_twenga_base_pro_show_out_stock_'.$id, $this->configuration) ? $this->configuration['google_twenga_base_pro_show_out_stock_'.$id] : false;

				$google_twenga_base_pro_product_shipping = array_key_exists('google_twenga_base_pro_product_shipping_'.$id, $this->configuration) && !empty($this->configuration['google_twenga_base_pro_product_shipping_'.$id]) ? $this->configuration['google_twenga_base_pro_product_shipping_'.$id] : 'NC';

				$google_twenga_base_pro_product_out_of_stock = array_key_exists('google_twenga_base_pro_product_out_of_stock_'.$id, $this->configuration) && !empty($this->configuration['google_twenga_base_pro_product_out_of_stock_'.$id]) ? 'R' : 'N';

				$google_twenga_base_pro_product_stock_detail = array_key_exists('google_twenga_base_pro_product_stock_detail_'.$id.'_'.$this->language_id, $this->configuration) && !empty($this->configuration['google_twenga_base_pro_product_stock_detail_'.$id.'_'.$this->language_id]) ? $this->configuration['google_twenga_base_pro_product_stock_detail_'.$id.'_'.$this->language_id] : '';

				$google_twenga_base_pro_product_out_of_stock = array_key_exists('google_twenga_base_pro_product_out_of_stock_'.$id, $this->configuration) && !empty($this->configuration['google_twenga_base_pro_product_out_of_stock_'.$id]) ? 'R' : 'N';

				$google_twenga_base_pro_product_stock_margin = array_key_exists('google_twenga_base_pro_product_stock_margin_'.$id, $this->configuration) && !empty($this->configuration['google_twenga_base_pro_product_stock_margin_'.$id]) ? $this->configuration['google_twenga_base_pro_product_stock_margin_'.$id] : 0;

		        //Only one model to ignore
		        if (!empty($google_twenga_base_pro_ignore_products) && !is_array($google_twenga_base_pro_ignore_products))
		          $google_twenga_base_pro_ignore_products = array($google_twenga_base_pro_ignore_products);

		        //Remove spaces in models
		        foreach ($google_twenga_base_pro_ignore_products as $key => $value) {
		         	$google_twenga_base_pro_ignore_products[$key] = trim($value);
		        }

			//End Get configuration
			$output  = '<?xml version="1.0" encoding="UTF-8" ?>'."\n";
			$output .= '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">'."\n";
			$output .= '<channel>'."\n";
				$this->load->model('catalog/category');
				$this->load->model('catalog/product');
				$this->load->model('tool/image');
				$this->request->get['store_id'] = 0;

				//Opencart Quality Extensions - info@opencartqualityextensions.com - 2016-10-09 18:14:35 - Check if view exists
				$viewName = DB_PREFIX . "view_products_".$this->config->get('config_store_id')."_".$this->language_id;

				$results_view = $this->db->query("SHOW TABLES LIKE '".$viewName."'");

				if($results_view->num_rows == 1)
				{
					$this->useView = true;
					$products = $this->model_extension_feed_google_base_pro->getProductsFromView($viewName, $only_these_products);
				}
				else
					$products = $this->model_extension_feed_google_base_pro->getProducts($categories_allowed, $only_these_products);

				foreach ($products as $key => $product) {
					if (!$google_twenga_base_pro_show_out_stock)
					{
						if ($product['quantity'] == 0) unset($products[$key]);
					}
					if ((int)$product['price'] == 0) unset($products[$key]);
				}

				if($google_twenga_base_pro_option_split)
				{
					$new_products = array();

					foreach ($products as $key => $product) {
						$add_normal_product = true;

						$options = $this->get_options($product['product_id']);
						
						if(!empty($options))
						{
							$add_normal_product = false;

							foreach ($options as $key2 => $col) {
								$temp_product = $product;
								if(array_key_exists('option_id', $col))
								{ 
									$val_name = $col['name'];
									$temp_product['quantity'] = $col['quantity'];

									$values_can_change = array('price', 'weight');
									foreach ($values_can_change as $val) {
										if($col[$val] > 0)
										{
											$operator = $col[$val.'_prefix'];
											$new_value = $col[$val];
											$current_value = $temp_product[$val];
											$temp_product[$val] = eval("return $current_value $operator $new_value;");
										}
									}

								}
								else
									$val_name = $col;

								$temp_product['item_​group_​id'] = $temp_product['product_id'];
								$temp_product['product_id_combinated'] = $temp_product['product_id'].'-'.$val_name;
								$temp_product['name'] = $temp_product['name'].' - '.$val_name;
								$temp_product['option_name'] = $val_name;
								$new_products[] = $temp_product;
							}
						}

						if($add_normal_product)
							$new_products[] = $product;
					}
				
					$products = $new_products;
				}

				foreach ($products as $product)
				{
					$show = true;
					if($this->useView)
					{
						$categories = explode($this->delimiterView, $product['product_categories']);
						foreach ($categories as $key => $cat) {
							$category_id = $this->useView ? $cat : $cat['category_id'];
							if(in_array($category_id, $categories_allowed))
							{
								$show = true;
								break;
							}
						}
					}

			        if (!in_array($product['model'], $google_twenga_base_pro_ignore_products) && $show)
			        {
			        	if(!$this->useView)
							$categories = $this->model_catalog_product->getCategories($product['product_id']);

		  				$output .= "\n".'<product>'."\n";

		  					$merchant_ref = array_key_exists('option_name', $product) ? $product['product_id'] : '';
		  					$merchant_id = array_key_exists('product_id_combinated', $product) ? $product['product_id_combinated'] : $product['product_id'];
		  					$upc_ean = array_key_exists('ean', $product) && !empty($product['ean']) ? $product['ean'] : (array_key_exists('upc', $product) && !empty($product['upc']) ? $product['upc'] : '');
		  					$manufacturer_id = html_entity_decode($product['manufacturer'], ENT_QUOTES, 'UTF-8');
		  					$product_url = $this->url->link('product/product', 'product_id=' . $product['product_id']);
		  					$image_url = $this->model_tool_image->resize( ($product['image'] ? $product['image'] : 'no_image.jpg'), $google_twenga_base_pro_thumbs_width, $google_twenga_base_pro_thumbs_height);
							$regular_price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id']), $this->currency_code, $this->currency_value, false);
							$price = $this->currency->format($this->tax->calculate(!empty($product['special']) ? $product['special'] : $product['price'], $product['tax_class_id']), $this->currency_code, $this->currency_value, false);
							$designation = $product['name'].' '.$manufacturer_id.' '.$product['model'];
							$description = strip_tags(htmlspecialchars_decode($product['description']), '<br></ br>');
							$brand = html_entity_decode($product['manufacturer'], ENT_QUOTES, 'UTF-8');
							$in_stock = $product['quantity'] == 0 ? $google_twenga_base_pro_product_out_of_stock : 'Y';
							$stock_detail = $google_twenga_base_pro_product_stock_detail;
							$merchant_margin = $google_twenga_base_pro_product_stock_margin > 0 ? $this->currency->format($price*($google_twenga_base_pro_product_stock_margin/100), $this->currency_code, $this->currency_value, false) : 0;
							$ecotax = $this->currency->format($this->tax->getTax($price, $product['tax_class_id']), $this->currency_code, $this->currency_value, false);

							$category = '';
		  					foreach ($categories as $cat) {
		  						$path = $this->getPath($cat['category_id']);
		  						if ($path) {
		  							foreach (explode('_', $path) as $path_id) {
		  								$category_info = $this->model_catalog_category->getCategory($path_id);

		  								if ($category_info) {
		  									if (!$category) {
		  										$category = $category_info['name'];
		  									} else {
		  										$category .= ' &gt; ' . $category_info['name'];
		  									}
		  								}
		  							}
		  						}
		  					}

		  					$output .= !empty($merchant_ref) ? '<merchant_ref>'.$merchant_ref.'</merchant_ref>'."\n" : '';
		  					$output .= !empty($merchant_id) ? '<merchant_id>'.$merchant_id.'</merchant_id>'."\n" : '';
							$output .= '<upc_ean>'.$upc_ean.'</upc_ean>'."\n";
							$output .= '<manufacturer_id>'.$manufacturer_id.'</manufacturer_id>'."\n";
							$output .= '<product_url>'.$product_url.'</product_url>'."\n";
							$output .= '<image_url>'.$image_url.'</image_url>'."\n";
							$output .= '<price>'.$price.'</price>'."\n";
							$output .= '<regular_price>'.$regular_price.'</regular_price>'."\n";
							$output .= '<shipping_cost>'.$google_twenga_base_pro_product_shipping.'</shipping_cost>'."\n";
							$output .= '<designation>'.$designation.'</designation>'."\n";
							$output .= '<description><![CDATA['.$description.']]></description>'."\n";
							$output .= '<category>'.$category.'</category>'."\n";
							$output .= '<brand>'.$brand.'</brand>'."\n";
							$output .= '<in_stock>'.$in_stock.'</in_stock>'."\n";
							$output .= '<stock_detail>'.$stock_detail.'</stock_detail>'."\n";
							$output .= '<availability>'.$product['quantity'].'</availability>'."\n";
							$output .= $merchant_margin > 0 ? '<merchant_margin>'.$merchant_margin.'</merchant_margin>'."\n" : '';
							$output .= '<ecotax>'.$ecotax.'</ecotax>'."\n";
							$output .= '<condition>0</condition>'."\n";
		  				$output .= '</product>';
		        	}
				}

			$output .= "\n".'</channel>'."\n"; 
			$output .= '</rss>';	

			$this->response->addHeader('Content-Type: application/xml');
			$this->response->setOutput($output);
		}
	}

	protected function getPath($parent_id, $current_path = '') {
		$category_info = $this->model_catalog_category->getCategory($parent_id);

		if ($category_info) {
			if (!$current_path) {
				$new_path = $category_info['category_id'];
			} else {
				$new_path = $category_info['category_id'] . '_' . $current_path;
			}	

			$path = $this->getPath($category_info['parent_id'], $new_path);

			if ($path) {
				return $path;
			} else {
				return $new_path;
			}
		}
	}		

	public function plainText($string) {
		$table = array(
		'“'=>'&#39;', '”'=>'&#39;', '‘'=>"&#34;", '’'=>"&#34;", '•'=>'*', '—'=>'-', '–'=>'-', '¿'=>'?', '¡'=>'!', '°'=>' deg. ',
		'÷'=>' / ', '×'=>'X', '±'=>'+/-',
		'&nbsp;'=> ' ', '"'=> '&#34;', "'"=> '&#39;', '<'=> '&lt;', '>'=> '&gt;', "\n"=> ' ', "\r"=> ' '
		);
		
		$string = strip_tags(html_entity_decode($string));
		$string = strtr($string, $table);
		$string = preg_replace('/&#?[a-z0-9]+;/i',' ',$string);	
		$string = preg_replace('/\s{2,}/i', ' ', $string );	
		if($this->config->get('uksb_google_merchant_characters')){
			
			$table2 = array(
			'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 
			'Þ'=>'B', 'þ'=>'b', 'ß'=>'Ss',
			'ç'=>'c',
			'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 
			'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i',
			'ñ'=>'n',
			'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'œ'=>'o', 'ð'=>'o',
			'š'=>'s',
			'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ü'=>'u',
			'ý'=>'y', 'ÿ'=>'y', 
			'ž'=>'z', 'ž'=>'z',
			'©'=>'(c)', '®'=>'(R)'
			);
			
			$string = strtr($string, $table2);
			$string = preg_replace('/[^(\x20-\x7F)]*/','', $string ); 
		}
		return substr($string, 0, 5000 );	
	}

	public function get_options($product_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value WHERE product_id = ".$product_id);
		$option_values = $query->rows;

		$return_option_values = array();
		foreach ($option_values as $key => $option_value) {
			$option_value_id = $option_value['option_value_id'];
			$option_id = $option_value['option_id'];
			
			$option_value = array_key_exists($option_id, $this->all_options) && array_key_exists($option_value_id, $this->all_options[$option_id]) && !empty($this->all_options[$option_id][$option_value_id]) ? $this->all_options[$option_id][$option_value_id] : '';

			if(!empty($option_value))
			{
				$option_values[$key]['name'] = $this->all_options[$option_id][$option_value_id];
				$return_option_values[] = $option_values[$key];
			}
		}

		$return = $return_option_values;

		return $return;
	}
}
?>