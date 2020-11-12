<?php

//Devman Extensions - info@devmanextensions.com - 2017-01-20 16:33:18 - Excel library
    require_once DIR_SYSTEM . 'library/Spout/Autoloader/autoload.php';
    use Box\Spout\Reader\ReaderFactory;
    use Box\Spout\Writer\WriterFactory;
    use Box\Spout\Common\Type;
    use Box\Spout\Writer\Style\StyleBuilder;
    use Box\Spout\Writer\Style\Color;
    use Box\Spout\Writer\Style\Border;
    use Box\Spout\Writer\Style\BorderBuilder;
//END

class ControllerExtensionFeedGoogleBusinessBasePro extends Controller {

	public function __construct($registry) {
        parent::__construct($registry);

        if(phpversion() < '5.4') {
            die('ERROR: YOUR PHP VERSION IS <b>'.phpversion().'</b>, TO GENERATE CSV FILE IS REQUIRED PHP <b>5.4.0 or higher</b>');
        }

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

        	$google_business_base_pro_configurations_url = DIR_APPLICATION.'controller/extension/feed/google_business_base_pro_configurations_backup.json';
        	$google_business_base_pro_configurations_url_path = DIR_APPLICATION.'controller/feed/google_business_base_pro_configurations_backup.json';
        	$google_business_base_pro_configurations_url2 = DIR_APPLICATION.'controller/extension/feed/google_business_base_pro_configurations.json';

        	$str = file_exists($google_business_base_pro_configurations_url_path) ? file_get_contents($google_business_base_pro_configurations_url) : file_get_contents($google_business_base_pro_configurations_url2);
        	$configuration = json_decode($str, true);

        	$configuration_found = array_key_exists('store_'.$store_id, $configuration) && array_key_exists($config_name, $configuration['store_'.$store_id]) ? true : die('Configuration not found');

        	$this->configuration = $configuration['store_'.$store_id][$config_name];

        	$this->special_customer_group_id = array_key_exists('google_business_base_pro_product_sale_price_customer_group_'.$store_id, $this->configuration) ? $this->configuration['google_business_base_pro_product_sale_price_customer_group_'.$store_id] : 1;
        //END
	}
	public function index() {

		ini_set('memory_limit',-1);
		ini_set('max_execution_time',500000000);

		$id = $this->config->get('config_store_id');
		$status = array_key_exists('google_business_base_pro_status_'.$id, $this->configuration) && $this->configuration['google_business_base_pro_status_'.$id];
		$more_oc_1541 = version_compare(VERSION, '1.5.4.1', '>');

		$this->load->model('extension/feed/google_base_pro');

		//Categories allowed configuration
			if(version_compare(VERSION, '1.5.3.1', '>'))
				$categories = $this->model_extension_feed_google_base_pro->getCategories();
			else
				$categories = $this->model_extension_feed_google_base_pro->getCategories_oc_old();

			$categories_allowed = array();

			foreach ($categories as $key => $cat) {
				$index = 'google_business_pro_category_allowed_'.$cat['category_id'].'_'.$id;

				if(array_key_exists($index, $this->configuration) && !empty($this->configuration[$index]))
					$categories_allowed[] = $cat['category_id'];
			}
		//END Categories allowed configuration

		if (!empty($status)) {
			//Get configuration
				$google_business_pro_product_id = array_key_exists('google_business_base_pro_product_id_'.$id, $this->configuration) && !empty($this->configuration['google_business_base_pro_product_id_'.$id]);
				$google_business_pro_product_id2 = array_key_exists('google_business_base_pro_product_id2_'.$id, $this->configuration) && !empty($this->configuration['google_business_base_pro_product_id2_'.$id]);
				$google_business_pro_product_title = array_key_exists('google_business_base_pro_product_title_'.$id, $this->configuration) && !empty($this->configuration['google_business_base_pro_product_title_'.$id]);
				$google_business_pro_product_keywords = array_key_exists('google_business_base_pro_product_keywords_'.$id, $this->configuration) && !empty($this->configuration['google_business_base_pro_product_keywords_'.$id]);
				$google_business_pro_product_category = array_key_exists('google_business_base_pro_product_category_'.$id, $this->configuration) && !empty($this->configuration['google_business_base_pro_product_category_'.$id]);
				$google_business_pro_product_description = array_key_exists('google_business_base_pro_product_description_'.$id, $this->configuration) && !empty($this->configuration['google_business_base_pro_product_description_'.$id]);
				$google_business_pro_product_link = array_key_exists('google_business_base_pro_product_link_'.$id, $this->configuration) && !empty($this->configuration['google_business_base_pro_product_link_'.$id]);
				$google_business_pro_product_image_link = array_key_exists('google_business_base_pro_product_image_link_'.$id, $this->configuration) && !empty($this->configuration['google_business_base_pro_product_image_link_'.$id]);
				$google_business_pro_product_price = array_key_exists('google_business_base_pro_product_price_'.$id, $this->configuration) && !empty($this->configuration['google_business_base_pro_product_price_'.$id]);
				$google_business_pro_product_price_formatted = array_key_exists('google_business_base_pro_product_price_formatted_'.$id, $this->configuration) && !empty($this->configuration['google_business_base_pro_product_price_formatted_'.$id]);
				$google_business_pro_product_sale_price = array_key_exists('google_business_base_pro_product_sale_price_'.$id, $this->configuration) && !empty($this->configuration['google_business_base_pro_product_sale_price_'.$id]);
				$google_business_pro_product_sale_price_formatted = array_key_exists('google_business_base_pro_product_sale_price_formatted_'.$id, $this->configuration) && !empty($this->configuration['google_business_base_pro_product_sale_price_formatted_'.$id]);
				$google_business_pro_product_include_tax = array_key_exists('google_business_base_pro_product_include_tax_'.$id, $this->configuration) && !empty($this->configuration['google_business_base_pro_product_include_tax_'.$id]);

				$google_business_pro_ignore_products = explode(',', (!empty($this->configuration['google_business_base_pro_ignore_products_'.$id]) ? $this->configuration['google_business_base_pro_ignore_products_'.$id] : ''));

				$only_these_products = !empty($this->configuration['google_business_base_pro_only_these_products_'.$id]) ? $this->configuration['google_business_base_pro_only_these_products_'.$id] : false;

		        $google_business_pro_thumb_width = array_key_exists('google_business_base_pro_thumb_width_'.$id, $this->configuration) && !empty($this->configuration['google_business_base_pro_thumb_width_'.$id]) ? $this->configuration['google_business_base_pro_thumb_width_'.$id] : 600;
				$google_business_pro_thumb_height = array_key_exists('google_business_base_pro_thumb_height_'.$id, $this->configuration) && !empty($this->configuration['google_business_base_pro_thumb_height_'.$id]) ? $this->configuration['google_business_base_pro_thumb_height_'.$id] : 600;
				$google_business_pro_show_out_stock = array_key_exists('google_business_base_pro_show_out_stock_'.$id, $this->configuration) && !empty($this->configuration['google_business_base_pro_show_out_stock_'.$id]);

		        //Only one model to ignore
		        if (!empty($google_business_pro_ignore_products) && !is_array($google_business_pro_ignore_products))
		          $google_business_pro_ignore_products = array($google_business_pro_ignore_products);

		        if(empty($google_business_pro_ignore_products))
		        	$google_business_pro_ignore_products = array();

		        //Remove spaces in models
		        foreach ($google_business_pro_ignore_products as $key => $value) {
		          $google_business_pro_ignore_products[$key] = trim($value);
		        }
			//End Get configuration


			/*$out = fopen('php://output', 'w') or die("Can't open php://output");
			header("Content-Type:application/csv");
			header("Content-Disposition:attachment;filename=".date('Ymd-His')."-google-business.csv");
		    $headers  = array('ID','ID2','Item title','Final URL','Image URL','Item subtitle','Item description','Item category','Price','Sale price','Contextual keywords','Item address','Tracking template','Custom parameter','Destination URL');

		    fputs($out, implode($headers, ',')."\n");*/
			//fputcsv($out, $headers);

            $products_to_feed = array();

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
				if (!$google_business_pro_show_out_stock)
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

		        if (!in_array($product['model'], $google_business_pro_ignore_products) && $show)
		        {
		        	$temp = array();

		        	if ($google_business_pro_product_id == 1)
	  				{
	  					$final_product_id = $product['product_id'];
	  					$temp[] = $final_product_id;
	  				}
	  				else
	  				{
	  					$temp[] = '';
	  				}

		        	if ($google_business_pro_product_id2 == 1)
	  				{
	  					$final_product_id = $product['model'];
	  					$temp[] = $final_product_id;
	  				}
	  				else
	  				{
	  					$temp[] = '';
	  				}


		        	if($google_business_pro_product_title == 1)
		        	{
		        		$temp[] = $this->formatString($product['name']);
		        	}
		        	else
		        	{
		        		$temp[] = '';
		        	}

					if($google_business_pro_product_link == 1)
					{
						$temp[] = $this->formatLink($this->url->link('product/product', 'product_id=' . $product['product_id']));
					}
					else
					{
						$temp[] = '';
					}

					if($google_business_pro_product_image_link == 1)
					{
						if ($product['image']) {
	  						$temp[] = $this->formatLink($this->model_tool_image->resize($product['image'], $google_business_pro_thumb_width, $google_business_pro_thumb_height));
	  					} else {
	  						$temp[] = $this->formatLink($this->model_tool_image->resize('no_image.jpg', $google_business_pro_thumb_width, $google_business_pro_thumb_height));
	  					}
					}
					else
					{
						$temp[] = '';
					}

					//Item subtitle
					$temp[] = '';

					if($google_business_pro_product_description == 1)
					{
						$temp[] = substr($this->formatString($product['description']), 0 , 1000);
					}
					else
					{
						$temp[] = '';
					}

					if($google_business_pro_product_category == 1)
					{
						if(!$this->useView)
	  					{
		  					$string_category = '';
		  					foreach ($categories as $category) {
		  						$path = $this->getPath($category['category_id']);

		  						if ($path) {

		  							foreach (explode('_', $path) as $path_id) {
		  								$category_info = $this->model_catalog_category->getCategory($path_id);

		  								if ($category_info) {
		  									if (!$string_category) {
		  										$string_category = $category_info['name'];
		  									} else {
		  										$string_category .= ' > ' . $category_info['name'];
		  									}
		  								}
		  							}
		  						}
		  					}

		  					$temp[] = $string_category;
		  				}
		  				else
		  					$temp[] = $product['category_tree'];
					}
					else
					{
						$temp[] = '';
					}

					//Currencies
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
		  			//END

					if($google_business_pro_product_price == 1)
					{
						$price = $product['price'];

  						if($google_business_pro_product_include_tax)
  							$price = $this->currency->format($this->tax->calculate($price, $product['tax_class_id']), $currency_code, $currency_value, false);
  						else
  							$price = $this->currency->format($price, $currency_code, $currency_value, false);

	  					$temp[] = $price . ' ' . $currency_code;

					}
					else
					{
						$temp[] = '';
					}

					if($google_business_pro_product_price_formatted == 1)
					{
						$price = $product['price'];

  						if($google_business_pro_product_include_tax)
  							$price = $this->currency->format($this->tax->calculate($price, $product['tax_class_id']), $currency_code, $currency_value, $this->currency_code);
  						else
  							$price = $this->currency->format($price, $currency_code, $currency_value, $this->currency_code);

	  					$temp[] = str_replace(',','', $price);
					}
					else
					{
						$temp[] = '';
					}

					if($google_business_pro_product_sale_price == 1 && $product['special'] > 0)
					{
						$price = $product['special'];

  						if($google_business_pro_product_include_tax)
  							$price = $this->currency->format($this->tax->calculate($price, $product['tax_class_id']), $currency_code, $currency_value, false);
  						else
  							$price = $this->currency->format($price, $currency_code, $currency_value, false);

	  					$temp[] = $price . ' ' . $currency_code;
					}
					else
					{
						$temp[] = '';
					}

					if($google_business_pro_product_sale_price_formatted == 1 && $product['special'] > 0)
					{
						$special_formatted = $product['special'];

  						if($google_business_pro_product_include_tax)
  							$special_formatted = $this->currency->format($this->tax->calculate($special_formatted, $product['tax_class_id']), $currency_code, $currency_value, $this->currency_code);
  						else
  							$special_formatted = $this->currency->format($special_formatted, $currency_code, $currency_value, $this->currency_code);

	  					$temp[] = str_replace(',','', $special_formatted);
					}
					else
					{
						$temp[] = '';
					}

					if($google_business_pro_product_keywords == 1)
					{
						$temp[] = $this->formatString($product['meta_keyword']);
					}
					else
					{
						$temp[] = '';
					}

					//Item address
					$temp[] = '';
					//Tracking template
					$temp[] = '';
					//Custom parameter
					$temp[] = '';

					$products_to_feed[] = $temp;
					//fputcsv($out, $temp);
					//fputs($out, implode($temp, ',')."\n");
	        	}
			}
			//fclose($out) or die("Can't close php://output");
		}

		$columns = array(
		    'ID',
            'ID2',
            'Item title',
            'Final URL',
            'Image URL',
            'Item subtitle',
            'Item description',
            'Item category',
            'Price',
            'Formatted price',
            'Sale price',
            'Formatted sale price',
            'Contextual keywords',
            'Item address',
            'Tracking template',
            'Custom parameter',
            'Destination URL'
        );
		$fileName = date('Ymd-His')."-google-business.csv";

		$writer = WriterFactory::create(Type::CSV);
        $writer->openToBrowser($fileName);
        $writer->addRow($columns);
        $writer->addRows($products_to_feed);
        $writer->close();
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

	protected function formatLink($link)
	{
		$link = str_replace(" ","%20", $link);
		$link = str_replace("&amp;","&", $link);
		return $link;
	}

	protected function formatString($string)
	{
		$string = $this->fixEncoding(str_replace(",", " ",trim(strip_tags(htmlspecialchars_decode($string)))));
		$string = str_replace(array('; ',' ;',',',"\n","\r","\r\n"), ";", $string);

		return $string;
	}

	protected function fixEncoding($string)
	{
		$string=str_replace("&amp;nbsp;"," ",$string);
		$string=str_replace("&amp;acute;","´",$string);
		$string=str_replace("&amp;rsquo;","’",$string);
		$string=str_replace("&amp;#39;","'",$string);
		$string=str_replace("&amp;reg;","®",$string);
		$string=str_replace("&amp;copy;","©",$string);
		$string=str_replace("&amp;mdash;","—",$string);
		$string=str_replace("&amp;auml;","ä",$string);
		$string=str_replace("&amp;ouml;","ö",$string);
		$string=str_replace("&amp;lsquo;","‘",$string);
		$string=str_replace("&amp;ldquo;","“",$string);
		$string=str_replace("&amp;sbquo;","‚",$string);
		$string=str_replace("&amp;bdquo;","„",$string);
		$string=str_replace("&amp;rdquo;","”",$string);
		$string=str_replace("&amp;ndash;","–",$string);
		$string=str_replace("&amp;permil;","‰",$string);
		$string=str_replace("&amp;euro;","€",$string);
		$string=str_replace("&amp;lsaquo;","‹",$string);
		$string=str_replace("&amp;rsaquo;","›",$string);
		$string=str_replace("&amp;lt;","&lt;",$string);
		$string=str_replace("&amp;gt;","&gt;",$string);
		$string=str_replace("&amp;quot;","&quot;",$string);
		$string=str_replace("&amp;trade;","™",$string);
		$string=str_replace("&amp;amp;","&amp;",$string);

		$string=str_replace("&nbsp;"," ",$string);
		$string=str_replace("&acute;","´",$string);
		$string=str_replace("&rsquo;","’",$string);
		$string=str_replace("&#39;","'",$string);
		$string=str_replace("&reg;","®",$string);
		$string=str_replace("&copy;","©",$string);
		$string=str_replace("&mdash;","—",$string);
		$string=str_replace("&auml;","ä",$string);
		$string=str_replace("&ouml;","ö",$string);
		$string=str_replace("&lsquo;","‘",$string);
		$string=str_replace("&ldquo;","“",$string);
		$string=str_replace("&sbquo;","‚",$string);
		$string=str_replace("&bdquo;","„",$string);
		$string=str_replace("&rdquo;","”",$string);
		$string=str_replace("&ndash;","–",$string);
		$string=str_replace("&permil;","‰",$string);
		$string=str_replace("&euro;","€",$string);
		$string=str_replace("&lsaquo;","‹",$string);
		$string=str_replace("&rsaquo;","›",$string);
		$string=str_replace("&lt;","&lt;",$string);
		$string=str_replace("&gt;","&gt;",$string);
		$string=str_replace("&quot;","&quot;",$string);
		$string=str_replace("&trade;","™",$string);

		return $string;
	}
}
?>