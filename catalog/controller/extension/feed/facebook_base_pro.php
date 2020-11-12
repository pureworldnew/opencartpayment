<?php
class ControllerExtensionFeedFacebookBasePro extends Controller {

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
        $this->currency_code = $currency_code;

        //Devman Extensions - info@devmanextensions.com - 2017-09-18 19:42:42 - Get configuration
        	$store_id = $this->config->get('config_store_id');
        	$config_name = $this->request->get['configuration'] ? urlencode($this->request->get['configuration']) : 'No configuration name';

        	$facebook_base_pro_configurations_url = DIR_APPLICATION.'controller/extension/feed/facebook_base_pro_configurations_backup.json';
        	$facebook_base_pro_configurations_url_path = DIR_APPLICATION.'controller/feed/facebook_base_pro_configurations_backup.json';
        	$facebook_base_pro_configurations_url2 = DIR_APPLICATION.'controller/extension/feed/facebook_base_pro_configurations.json';

        	$str = file_exists($facebook_base_pro_configurations_url_path) ? file_get_contents($facebook_base_pro_configurations_url) : file_get_contents($facebook_base_pro_configurations_url2);
        	$configuration = json_decode($str, true);

        	$configuration_found = is_array($configuration) && array_key_exists('store_'.$store_id, $configuration) && array_key_exists($config_name, $configuration['store_'.$store_id]) ? true : die('Configuration not found');

        	$this->configuration = $configuration['store_'.$store_id][$config_name];

        	$this->special_customer_group_id = array_key_exists('google_facebook_base_pro_product_sale_price_customer_group_'.$store_id, $this->configuration) ? $this->configuration['google_facebook_base_pro_product_sale_price_customer_group_'.$store_id] : 1;
        //END

	}
	public function index() {
		
		ini_set('memory_limit',-1);
		ini_set('max_execution_time',500000000);

		$id = $this->config->get('config_store_id');

		$status = array_key_exists('google_facebook_base_pro_status_'.$id, $this->configuration) ? $this->configuration['google_facebook_base_pro_status_'.$id] : false;

		$more_oc_1541 = version_compare(VERSION, '1.5.4.1', '>');

		$this->load->model('extension/feed/google_base_pro');

		//Categories allowed configuration
			if(version_compare(VERSION, '1.5.3.1', '>'))
				$categories = $this->model_extension_feed_google_base_pro->getCategories();
			else
				$categories = $this->model_extension_feed_google_base_pro->getCategories_oc_old();
			
			$categories_allowed = array();

			foreach ($categories as $key => $cat) {
				if(!empty($this->configuration['google_facebook_base_pro_category_allowed_'.$cat['category_id'].'_'.$id]))
					$categories_allowed[] = $cat['category_id'];
			}
		//END Categories allowed configuration

		if (!empty($status)) {
			//Get configuration
				$google_facebook_base_pro_product_title = array_key_exists('google_facebook_base_pro_product_title_'.$id, $this->configuration) && !empty($this->configuration['google_facebook_base_pro_product_title_'.$id]);
				$google_facebook_base_pro_product_link = array_key_exists('google_facebook_base_pro_product_link_'.$id, $this->configuration) && !empty($this->configuration['google_facebook_base_pro_product_link_'.$id]);
				$google_facebook_base_pro_product_description = array_key_exists('google_facebook_base_pro_product_description_'.$id, $this->configuration) && !empty($this->configuration['google_facebook_base_pro_product_description_'.$id]);
				$google_facebook_base_pro_product_brand = array_key_exists('google_facebook_base_pro_product_brand_'.$id, $this->configuration) && !empty($this->configuration['google_facebook_base_pro_product_brand_'.$id]);
				$google_facebook_base_pro_product_condition = array_key_exists('google_facebook_base_pro_product_condition_'.$id, $this->configuration) && !empty($this->configuration['google_facebook_base_pro_product_condition_'.$id]);
				$google_facebook_base_pro_product_id = array_key_exists('google_facebook_base_pro_product_id_'.$id, $this->configuration) && !empty($this->configuration['google_facebook_base_pro_product_id_'.$id]);
				$google_facebook_base_pro_multiples_identificators = array_key_exists('google_facebook_base_pro_multiples_identificators_'.$id, $this->configuration) && !empty($this->configuration['google_facebook_base_pro_multiples_identificators_'.$id]);
				$google_facebook_base_pro_identifier_exists = array_key_exists('google_facebook_base_pro_identifier_exists_'.$id, $this->configuration) && !empty($this->configuration['google_facebook_base_pro_identifier_exists_'.$id]);
				$google_facebook_base_pro_product_image_link = array_key_exists('google_facebook_base_pro_product_image_link_'.$id, $this->configuration) && !empty($this->configuration['google_facebook_base_pro_product_image_link_'.$id]);
				$google_facebook_base_pro_product_price = array_key_exists('google_facebook_base_pro_product_price_'.$id, $this->configuration) && !empty($this->configuration['google_facebook_base_pro_product_price_'.$id]);
				$google_facebook_base_pro_product_sale_price = array_key_exists('google_facebook_base_pro_product_sale_price_'.$id, $this->configuration) && !empty($this->configuration['google_facebook_base_pro_product_sale_price_'.$id]);
				$google_facebook_base_pro_product_include_tax = array_key_exists('google_facebook_base_pro_product_include_tax_'.$id, $this->configuration) && !empty($this->configuration['google_facebook_base_pro_product_include_tax_'.$id]);
				$google_facebook_base_pro_product_type = array_key_exists('google_facebook_base_pro_product_type_'.$id, $this->configuration) && !empty($this->configuration['google_facebook_base_pro_product_type_'.$id]);
				$google_facebook_base_pro_product_quantity = array_key_exists('google_facebook_base_pro_product_quantity_'.$id, $this->configuration) && !empty($this->configuration['google_facebook_base_pro_product_quantity_'.$id]);
				$google_facebook_base_pro_product_weight = array_key_exists('google_facebook_base_pro_product_weight_'.$id, $this->configuration) && !empty($this->configuration['google_facebook_base_pro_product_weight_'.$id]);
				$google_facebook_base_pro_product_availability = array_key_exists('google_facebook_base_pro_product_availability_'.$id, $this->configuration) && !empty($this->configuration['google_facebook_base_pro_product_availability_'.$id]);
				$google_facebook_base_pro_show_out_stock = array_key_exists('google_facebook_base_pro_show_out_stock_'.$id, $this->configuration) && !empty($this->configuration['google_facebook_base_pro_show_out_stock_'.$id]);

				$google_facebook_base_pro_thumb_width = array_key_exists('google_facebook_base_pro_thumb_width_'.$id, $this->configuration) && !empty($this->configuration['google_facebook_base_pro_thumb_width_'.$id]) ? $this->configuration['google_facebook_base_pro_thumb_width_'.$id] : 600;
				$google_facebook_base_pro_thumb_height = array_key_exists('google_facebook_base_pro_thumb_height_'.$id, $this->configuration) && !empty($this->configuration['google_facebook_base_pro_thumb_height_'.$id]) ? $this->configuration['google_facebook_base_pro_thumb_height_'.$id] : 600;

		        $google_facebook_base_pro_ignore_products = explode(',', (!empty($this->configuration['google_facebook_base_pro_ignore_products_'.$id]) ? $this->configuration['google_facebook_base_pro_ignore_products_'.$id] : ''));

		        $only_these_products = !empty($this->configuration['google_facebook_base_pro_only_these_products_'.$id]) ? $this->configuration['google_facebook_base_pro_only_these_products_'.$id] : false;

		        //Only one model to ignore
		        if (!empty($google_facebook_base_pro_ignore_products) && !is_array($google_facebook_base_pro_ignore_products))
		          $google_facebook_base_pro_ignore_products = array($google_facebook_base_pro_ignore_products);
		        
		        //Remove spaces in models
		        foreach ($google_facebook_base_pro_ignore_products as $key => $value) {
		         	$google_facebook_base_pro_ignore_products[$key] = trim($value);
		        }
			//End Get configuration
			$output  = '<?xml version="1.0" encoding="UTF-8" ?>'."\n";
			$output .= '<rss version="2.0">'."\n";
			$output .= '<channel>'."\n";
			$output .= '<title><![CDATA[' . ( array_key_exists('google_facebook_base_pro_title_'.$id, $this->configuration) ? $this->configuration['google_facebook_base_pro_title_'.$id] : '') . ']]></title>'."\n";
			$output .= '<description><![CDATA[' . ( array_key_exists('google_facebook_base_pro_description_'.$id, $this->configuration) ? $this->configuration['google_facebook_base_pro_description_'.$id] : '') . ']]></description>'."\n";
			$output .= '<link><![CDATA[' . ( array_key_exists('google_facebook_base_pro_link_'.$id, $this->configuration) ? $this->configuration['google_facebook_base_pro_link_'.$id] : '') . ']]></link>'."\n";

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
				if (!$google_facebook_base_pro_show_out_stock)
				{
					if ($product['quantity'] == 0) unset($products[$key]);
				}
				if ((int)$product['price'] == 0) unset($products[$key]);
			}

			foreach ($products as $product)
			{
				if(!$this->useView)
					$categories = $this->model_catalog_product->getCategories($product['product_id']);
				else
					$categories = explode($this->delimiterView, $product['product_categories']);

				$show = false;
				foreach ($categories as $key => $cat) {
					$category_id = $this->useView ? $cat : $cat['category_id'];
					if(in_array($category_id, $categories_allowed))
					{
						$show = true;
						break;
					}
				}

		        if (!in_array($product['model'], $google_facebook_base_pro_ignore_products) && $show)
		        {
	  				$output .= "\n".'<item>'."\n";
	  				if ($google_facebook_base_pro_product_title == 1)
	  					$output .= '<title><![CDATA[' . $product['name'] . ']]></title>'."\n";

	  				if ($google_facebook_base_pro_product_link == 1)
	  					$output .= '<link><![CDATA[' . $this->url->link('product/product', 'product_id=' . $product['product_id']) . ']]></link>'."\n";
	  				
	  				if ($google_facebook_base_pro_product_description == 1)
	  					$output .= '<description><![CDATA[' . strip_tags(htmlspecialchars_decode($product['description']), '<br></ br>') . ']]></description>'."\n";
	  				
	  				if ($google_facebook_base_pro_product_brand == 1 && !empty($product['manufacturer']))
	  					$output .= '<brand><![CDATA[' . html_entity_decode($product['manufacturer'], ENT_QUOTES, 'UTF-8') . ']]></brand>'."\n";
	  				
	  				if ($google_facebook_base_pro_product_condition == 1)
	  					$output .= '<condition>new</condition>'."\n";

	  				if ($google_facebook_base_pro_product_id == 1)
	  				{
	  					$final_product_id = $product['product_id'];
	  					$output .= '<id><![CDATA[' . $final_product_id . ']]></id>'."\n";
	  				}

	  				if ($google_facebook_base_pro_product_image_link == 1)
	  				{
	  					if ($product['image']) {
	  						$output .= '<image_link><![CDATA[' . $this->model_tool_image->resize($product['image'], $google_facebook_base_pro_thumb_width, $google_facebook_base_pro_thumb_height) . ']]></image_link>'."\n";
	  					} else {
	  						$output .= '<image_link><![CDATA[' . $this->model_tool_image->resize('no_image.jpg', $google_facebook_base_pro_thumb_width, $google_facebook_base_pro_thumb_height) . ']]></image_link>'."\n";
	  					}
	  				}

	  				if ($google_facebook_base_pro_multiples_identificators == 1)
	  				{	  					
	  					if(!empty($product['ean']) && version_compare(VERSION, '1.5.2.1', '>'))
	  						$output .= '<gtin><![CDATA[' . $product['ean'] . ']]></gtin>'."\n";
	  					elseif(!empty($product['upc']))
	  						$output .= '<gtin><![CDATA[' . $product['upc'] . ']]></gtin>'."\n";
	  				}

	  				$currencies = array(
	  					'AED','AFN','ALL','AMD','ANG','AOA','ARS','AUD','AWG','AZN','BAM','BBD','BDT','BGN','BHD','BIF','BMD','BND','BOB','BOV','BRL','BSD','BTN','BWP','BYR','BZD','CAD','CDF','CHE','CHF','CHW','CLF','CLP','CNY','COP','COU','CRC','CUP','CVE','CYP','CZK','DJF','DKK','DOP','DZD','EEK','EGP','ERN','ETB','EUR','FJD','FKP','GBP','GEL','GHS','GIP','GMD','GNF','GTQ','GYD','HKD','HNL','HRK','HTG','HUF','IDR','ILS','INR','IQD','IRR','ISK','JMD','JOD','JPY','KES','KGS','KHR','KMF','KPW','KRW','KWD','KYD','KZT','LAK','LBP','LKR','LRD','LSL','LTL','LVL','LYD','MAD','MDL','MGA','MKD','MMK','MNT','MOP','MRO','MTL','MUR','MVR','MWK','MXN','MXV','MYR','MZN','NAD','NGN','NIO','NOK','NPR','NZD','OMR','PAB','PEN','PGK','PHP','PKR','PLN','PYG','QAR','RON','RSD','RUB','RWF','SAR','SBD','SCR','SDG','SEK','SGD','SHP','SKK','SLL','SOS','SRD','STD','SYP','SZL','THB','TJS','TMM','TND','TOP','TRY','TTD','TWD','TZS','UAH','UGX','USD','USN','USS','UYU','UZS','VEB','VND','VUV','WST','XAF','XAG','XAU','XBA','XBB','XBC','XBD','XCD','XDR','XFO','XFU','XOF','XPD','XPF','XPT','XTS','XXX','YER','ZAR','ZMK','ZWD'
	  				);

	  				if (in_array($this->currency_code, $currencies)) {
	  					$currency_code = $this->currency_code;
	  					$currency_value = $this->currency->getValue($currency_code);
	  				} else {
	  					$currency_code = 'USD';
	  					$currency_value = $this->currency->getValue('USD');
	  				}

	  				if ($google_facebook_base_pro_product_price == 1)
	  				{
	  					$price = $product['price'];

  						if($google_facebook_base_pro_product_include_tax)
  							$price = $this->currency->format($this->tax->calculate($price, $product['tax_class_id']), $currency_code, $currency_value, false);
  						else
  							$price = $this->currency->format($price, $currency_code, $currency_value, false);

	  					$output .= '<price>' . $price . ' ' . $currency_code . '</price>'."\n";
	  				}

	  				if($google_facebook_base_pro_product_sale_price == 1 && $product['special'] > 0)
	  				{
	  					$price = $product['special'];

  						if($google_facebook_base_pro_product_include_tax)
  							$price = $this->currency->format($this->tax->calculate($price, $product['tax_class_id']), $currency_code, $currency_value, false);
  						else
  							$price = $this->currency->format($price, $currency_code, $currency_value, false);

	  					$output .= '<sale_price>' . $price . ' ' . $currency_code . '</sale_price>'."\n";
	  				}

	  				if ($google_facebook_base_pro_product_type == 1)
	  				{
	  					if(!$this->useView)
	  					{
		  					foreach ($categories as $category) {
		  						$path = $this->getPath($category['category_id']);

		  						if ($path) {
		  							$string = '';

		  							foreach (explode('_', $path) as $path_id) {
		  								$category_info = $this->model_catalog_category->getCategory($path_id);

		  								if ($category_info) {
		  									if (!$string) {
		  										$string = $category_info['name'];
		  									} else {
		  										$string .= ' &gt; ' . $category_info['name'];
		  									}
		  								}
		  							}

		  							$output .= '<product_type><![CDATA[' . $string . ']]></product_type>'."\n";
		  						}
		  					}

		  					$last_category = array_pop($categories);
			            	$google_category_id = !empty($this->configuration['google_facebook_base_pro_merchantcenter_category_'.$last_category['category_id'].'_'.$id]) ? $this->configuration['google_facebook_base_pro_merchantcenter_category_'.$last_category['category_id'].'_'.$id] : '';
		  				}
		  				else
		  				{
		  					$last_category = array_pop($categories);
			            	$google_category_id = !empty($this->configuration['google_facebook_base_pro_merchantcenter_category_'.$last_category.'_'.$id]) ? $this->configuration['google_facebook_base_pro_merchantcenter_category_'.$last_category.'_'.$id] : '';
		  					$output .= '<product_type><![CDATA[' . $product['category_tree'] . ']]></product_type>'."\n";
		  				}

			            if ($google_category_id != "")
			            {
			            	//Devman Extensions - info@devmanextensions.com - 2017-05-31 11:04:36 - Get id
			            	$google_category_id = explode(' - ', $google_category_id);
			            	if(!empty($google_category_id[0]) && is_numeric($google_category_id[0]))
			            		$output .= '<google_product_category><![CDATA[' . $google_category_id[0] . ']]></google_product_category>'."\n";
			            }
	  				}

	  				if ($google_facebook_base_pro_product_quantity == 1)
	  					$output .= '<quantity><![CDATA[' . $product['quantity'] . ']]></quantity>'."\n";

	  				if ($google_facebook_base_pro_product_weight == 1)
	  					$output .= '<shipping_weight><![CDATA[' . $this->weight->format($product['weight'], $product['weight_class_id']) . ']]></shipping_weight>'."\n";
	  				
	  				if ($google_facebook_base_pro_product_availability == 1)
	  					$output .= '<availability>' . ($product['quantity'] ? 'in stock' : 'out of stock') . '</availability>'."\n";
	  				$output .= '</item>'."\n";
	        	}
			}

			$output .= '</channel>'."\n"; 
			$output .= '</rss>';

			$this->response->addHeader('Content-Type: application/xml');
			$this->response->setOutput($output);
		}
	}

	protected function getPath($parent_id, $current_path = '') {
		$category_info = $this->model_catalog_category->getCategory($parent_id);

		if (array_key_exists('category_id') && !empty($category_info['category_id'])) {
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
}
?>