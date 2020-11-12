<?php
class ControllerExtensionFeedGoogleBasePro extends Controller {

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

        	$google_base_pro_configurations_url = DIR_APPLICATION.'controller/extension/feed/google_base_pro_configurations_backup.json';
        	$google_base_pro_configurations_url_path = DIR_APPLICATION.'controller/feed/google_base_pro_configurations_backup.json';
        	$google_base_pro_configurations_url2 = DIR_APPLICATION.'controller/extension/feed/google_base_pro_configurations.json';

        	$str = file_exists($google_base_pro_configurations_url_path) ? file_get_contents($google_base_pro_configurations_url) : file_get_contents($google_base_pro_configurations_url2);
        	$configuration = json_decode($str, true);

        	$configuration_found = array_key_exists('store_'.$store_id, $configuration) && array_key_exists($config_name, $configuration['store_'.$store_id]) ? true : die('Configuration not found');

        	$this->configuration = $configuration['store_'.$store_id][$config_name];

        	$this->special_customer_group_id = array_key_exists('google_base_pro_product_sale_price_customer_group_'.$store_id, $this->configuration) ? $this->configuration['google_base_pro_product_sale_price_customer_group_'.$store_id] : 1;
        //END
        $this->split_products = !empty($this->configuration['google_base_pro_option_split_'.$store_id]) && !empty($this->configuration['google_base_pro_option_split_'.$store_id]);
        $this->split_colors = $this->split_products && !empty($this->configuration['google_base_pro_product_color_'.$store_id]) && !empty($this->configuration['google_base_pro_product_color_split_'.$store_id]);
        $this->split_sizes = $this->split_products && !empty($this->configuration['google_base_pro_product_size_'.$store_id]);
	}
	public function index() {

		ini_set('memory_limit',-1);
		ini_set('max_execution_time',500000000);

		$id = $this->config->get('config_store_id');
		$status = array_key_exists('google_base_pro_status_'.$id, $this->configuration) ? $this->configuration['google_base_pro_status_'.$id] : false;

		$more_oc_1541 = version_compare(VERSION, '1.5.4.1', '>');

		$this->load->model('extension/feed/google_base_pro');

		//Categories allowed configuration
			if(version_compare(VERSION, '1.5.3.1', '>'))
				$categories = $this->model_extension_feed_google_base_pro->getCategories();
			else
				$categories = $this->model_extension_feed_google_base_pro->getCategories_oc_old();
			
			$categories_allowed = array();

			foreach ($categories as $key => $cat) {
				if(!empty($this->configuration['google_base_pro_category_allowed_'.$cat['category_id'].'_'.$id]))
					$categories_allowed[] = $cat['category_id'];
			}
		//END Categories allowed configuration

		if (!empty($status)) {
			//Get configuration
				$google_base_pro_product_title = !empty($this->configuration['google_base_pro_product_title_'.$id]);
				$google_base_pro_product_link = !empty($this->configuration['google_base_pro_product_link_'.$id]);
				$google_base_pro_product_description = !empty($this->configuration['google_base_pro_product_description_'.$id]);
				$google_base_pro_product_brand = !empty($this->configuration['google_base_pro_product_brand_'.$id]);
				$google_base_pro_product_condition = !empty($this->configuration['google_base_pro_product_condition_'.$id]);
				$google_base_pro_product_id = !empty($this->configuration['google_base_pro_product_id_'.$id]);
				$google_base_pro_product_like = array_key_exists('google_base_pro_product_id_like_'.$id, $this->configuration) ? $this->configuration['google_base_pro_product_id_like_'.$id] : 'product_id';
				$google_base_pro_multiples_identificators = !empty($this->configuration['google_base_pro_multiples_identificators_'.$id]);
				$google_base_pro_identifier_exists = !empty($this->configuration['google_base_pro_identifier_exists_'.$id]);
				$google_base_pro_product_image_link = !empty($this->configuration['google_base_pro_product_image_link_'.$id]);
				$google_base_pro_product_additional_images = !empty($this->configuration['google_base_pro_product_additional_images_'.$id]);
				$google_base_pro_product_price = !empty($this->configuration['google_base_pro_product_price_'.$id]);
				$google_base_pro_product_sale_price = !empty($this->configuration['google_base_pro_product_sale_price_'.$id]);
				$google_base_pro_product_include_tax = !empty($this->configuration['google_base_pro_product_include_tax_'.$id]);
				$google_base_pro_product_type = !empty($this->configuration['google_base_pro_product_type_'.$id]);
				$google_base_pro_product_quantity = !empty($this->configuration['google_base_pro_product_quantity_'.$id]);
				$google_base_pro_product_weight = !empty($this->configuration['google_base_pro_product_weight_'.$id]);
				$google_base_pro_product_availability = !empty($this->configuration['google_base_pro_product_availability_'.$id]);
				$google_base_pro_show_out_stock = !empty($this->configuration['google_base_pro_show_out_stock_'.$id]);

				$google_base_pro_thumb_width = !empty($this->configuration['google_base_pro_thumb_width_'.$id]) ? $this->configuration['google_base_pro_thumb_width_'.$id] : 600;
				$google_base_pro_thumb_height = !empty($this->configuration['google_base_pro_thumb_height_'.$id]) ? $this->configuration['google_base_pro_thumb_height_'.$id] : 600;

				$only_these_products = !empty($this->configuration['google_base_pro_only_these_products_'.$id]) ? $this->configuration['google_base_pro_only_these_products_'.$id] : false;

        		//Color
        			$google_base_pro_product_color = !empty($this->configuration['google_base_pro_product_color_'.$id]);
					$google_base_pro_product_color_attribute = !empty($this->configuration['google_base_pro_product_color_attribute_'.$id]) ? $this->configuration['google_base_pro_product_color_attribute_'.$id] : '';
					if($more_oc_1541)
						$google_base_pro_product_color_filter = !empty($this->configuration['google_base_pro_product_color_filter_'.$id]) ? $this->configuration['google_base_pro_product_color_filter_'.$id] : '';
					$google_base_pro_product_color_option = !empty($this->configuration['google_base_pro_product_color_option_'.$id]) ? $this->configuration['google_base_pro_product_color_option_'.$id] : '';
					
					if(!empty($google_base_pro_product_color_attribute)) $get_color_from = 'attribute_'.$google_base_pro_product_color_attribute;
					elseif($more_oc_1541 && !empty($google_base_pro_product_color_filter)) $get_color_from = 'filter_'.$google_base_pro_product_color_filter;
					elseif(!empty($google_base_pro_product_color_option)) $get_color_from = 'option_'.$google_base_pro_product_color_option;
				//END Color

				//Gender
        			$google_base_pro_product_gender = !empty($this->configuration['google_base_pro_product_gender_'.$id]);
					$google_base_pro_product_gender_attribute = !empty($this->configuration['google_base_pro_product_gender_attribute_'.$id]) ? $this->configuration['google_base_pro_product_gender_attribute_'.$id] : '';
					if($more_oc_1541)
						$google_base_pro_product_gender_filter = !empty($this->configuration['google_base_pro_product_gender_filter_'.$id]) ? $this->configuration['google_base_pro_product_gender_filter_'.$id] : '';
					$google_base_pro_product_gender_option = !empty($this->configuration['google_base_pro_product_gender_option_'.$id]) ? $this->configuration['google_base_pro_product_gender_option_'.$id] : '';
					
					if(!empty($google_base_pro_product_gender_attribute)) $get_gender_from = 'attribute_'.$google_base_pro_product_gender_attribute;
					elseif($more_oc_1541 && !empty($google_base_pro_product_gender_filter)) $get_gender_from = 'filter_'.$google_base_pro_product_gender_filter;
					elseif(!empty($google_base_pro_product_gender_option)) $get_gender_from = 'option_'.$google_base_pro_product_gender_option;
				//END Gender

				//Age Group
        			$google_base_pro_product_age_group = !empty($this->configuration['google_base_pro_product_age_group_'.$id]);
					$google_base_pro_product_age_group_attribute = !empty($this->configuration['google_base_pro_product_age_group_attribute_'.$id]) ? $this->configuration['google_base_pro_product_age_group_attribute_'.$id] : '';
					if($more_oc_1541)
						$google_base_pro_product_age_group_filter = !empty($this->configuration['google_base_pro_product_age_group_filter_'.$id]) ? $this->configuration['google_base_pro_product_age_group_filter_'.$id] : '';
					$google_base_pro_product_age_group_option = !empty($this->configuration['google_base_pro_product_age_group_option_'.$id]) ? $this->configuration['google_base_pro_product_age_group_option_'.$id] : '';
					
					if(!empty($google_base_pro_product_age_group_attribute)) $get_age_group_from = 'attribute_'.$google_base_pro_product_age_group_attribute;
					elseif($more_oc_1541 && !empty($google_base_pro_product_age_group_filter)) $get_age_group_from = 'filter_'.$google_base_pro_product_age_group_filter;
					elseif(!empty($google_base_pro_product_age_group_option)) $get_age_group_from = 'option_'.$google_base_pro_product_age_group_option;
				//END Age Group

				//Size
        			$google_base_pro_product_size = !empty($this->configuration['google_base_pro_product_size_'.$id]);
					$google_base_pro_product_size_attribute = !empty($this->configuration['google_base_pro_product_size_attribute_'.$id]) ? $this->configuration['google_base_pro_product_size_attribute_'.$id] : '';
					if($more_oc_1541)
						$google_base_pro_product_size_filter = !empty($this->configuration['google_base_pro_product_size_filter_'.$id]) ? $this->configuration['google_base_pro_product_size_filter_'.$id] : '';
					$google_base_pro_product_size_option = !empty($this->configuration['google_base_pro_product_size_option_'.$id]) ? $this->configuration['google_base_pro_product_size_option_'.$id] : '';
					
					$get_size_from = '';
					if(!empty($google_base_pro_product_size_attribute)) $get_size_from = 'attribute_'.$google_base_pro_product_size_attribute;
					elseif($more_oc_1541 && !empty($google_base_pro_product_size_filter)) $get_size_from = 'filter_'.$google_base_pro_product_size_filter;
					elseif(!empty($google_base_pro_product_size_option)) $get_size_from = 'option_'.$google_base_pro_product_size_option;
				//END Size

				//Material
        			$google_base_pro_product_material = !empty($this->configuration['google_base_pro_product_material_'.$id]);
					$google_base_pro_product_material_attribute = !empty($this->configuration['google_base_pro_product_material_attribute_'.$id]) ? $this->configuration['google_base_pro_product_material_attribute_'.$id] : '';
					if($more_oc_1541)
						$google_base_pro_product_material_filter = !empty($this->configuration['google_base_pro_product_material_filter_'.$id]) ? $this->configuration['google_base_pro_product_material_filter_'.$id] : '';
					$google_base_pro_product_material_option = !empty($this->configuration['google_base_pro_product_material_option_'.$id]) ? $this->configuration['google_base_pro_product_material_option_'.$id] : '';
					
					if(!empty($google_base_pro_product_material_attribute)) $get_material_from = 'attribute_'.$google_base_pro_product_material_attribute;
					elseif($more_oc_1541 && !empty($google_base_pro_product_material_filter)) $get_material_from = 'filter_'.$google_base_pro_product_material_filter;
					elseif(!empty($google_base_pro_product_material_option)) $get_material_from = 'option_'.$google_base_pro_product_material_option;
				//END Material

				//Custom Labels
					$custom_labels = array(0,1,2,3,4,5);

					foreach ($custom_labels as $key => $cl) {
						${'google_base_pro_product_custom_label_'.$cl} = !empty($this->configuration['google_base_pro_product_custom_label_'.$cl.'_'.$id]);

						${'google_base_pro_product_custom_label_'.$cl.'_fixed_word'} = $this->configuration['google_base_pro_product_custom_label_'.$cl.'_fixed_word_'.$id];

						${'google_base_pro_product_custom_label_'.$cl.'_attribute'} = !empty($this->configuration['google_base_pro_product_custom_label_'.$cl.'_attribute_'.$id]) ? $this->configuration['google_base_pro_product_custom_label_'.$cl.'_attribute_'.$id] : '';
						if($more_oc_1541)
							${'google_base_pro_product_custom_label_'.$cl.'_filter'} = !empty($this->configuration['google_base_pro_product_custom_label_'.$cl.'_filter_'.$id]) ? $this->configuration['google_base_pro_product_custom_label_'.$cl.'_filter_'.$id] : '';

						${'google_base_pro_product_custom_label_'.$cl.'_option'} = !empty($this->configuration['google_base_pro_product_custom_label_'.$cl.'_option_'.$id]) ? $this->configuration['google_base_pro_product_custom_label_'.$cl.'_option_'.$id] : '';

						${'get_custom_label_'.$cl.'_from'} = '';
						
						if(!empty(${'google_base_pro_product_custom_label_'.$cl.'_attribute'}))
							${'get_custom_label_'.$cl.'_from'} = 'attribute_'.${'google_base_pro_product_custom_label_'.$cl.'_attribute'};
						elseif($more_oc_1541 && !empty(${'google_base_pro_product_custom_label_'.$cl.'_filter'}))
							${'get_custom_label_'.$cl.'_from'} = 'filter_'.${'google_base_pro_product_custom_label_'.$cl.'_filter'};
						elseif(!empty(${'google_base_pro_product_custom_label_'.$cl.'_option'}))
							${'get_custom_label_'.$cl.'_from'} = 'option_'.${'google_base_pro_product_custom_label_'.$cl.'_option'};
					}
				//END Custom Labels

				//Get all filters
					$all_filters = array();

					if($more_oc_1541)
					{
						require('admin/model/catalog/filter.php');
						$filterModel = new ModelCatalogFilter( $this->registry );
						$filters = $filterModel->getFilters( true );

						foreach ($filters as $key => $filter) {
							if(!isset($all_filters[$filter['filter_group_id']]))
								$all_filters[$filter['filter_group_id']] = array();

							$all_filters[$filter['filter_group_id']][$filter['filter_id']] = $filter['name'];
						}
					}
				//END Get all filters

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
				//END Get all options

		        $google_base_pro_ignore_products = explode(',', (!empty($this->configuration['google_base_pro_ignore_products_'.$id]) ? $this->configuration['google_base_pro_ignore_products_'.$id] : ''));
		        
		        //Only one model to ignore
		        if (!empty($google_base_pro_ignore_products) && !is_array($google_base_pro_ignore_products))
		          $google_base_pro_ignore_products = array($google_base_pro_ignore_products);
		        
		        //Remove spaces in models
		        foreach ($google_base_pro_ignore_products as $key => $value) {
		          $google_base_pro_ignore_products[$key] = trim($value);
		        }
			//End Get configuration
			$output  = '<?xml version="1.0" encoding="UTF-8" ?>'."\n";
			$output .= '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">'."\n";
			$output .= '<channel>'."\n";
			$output .= '<title><![CDATA[' . (!empty($this->configuration['google_base_pro_title_'.$id]) ? $this->configuration['google_base_pro_title_'.$id] : '') . ']]></title>'."\n";
			$output .= '<description><![CDATA[' . (!empty($this->configuration['google_base_pro_description_'.$id]) ? $this->configuration['google_base_pro_description_'.$id] : '') . ']]></description>'."\n";
			$output .= '<link><![CDATA[' . (!empty($this->configuration['google_base_pro_link_'.$id]) ? $this->configuration['google_base_pro_link_'.$id] : '') . ']]></link>'."\n";

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
				if (!$google_base_pro_show_out_stock)
				{
					if ($product['quantity'] == 0) unset($products[$key]);
				}
				if ((int)$product['price'] == 0) unset($products[$key]);
			}

			//Devman Extensions - info@devmanextensions.com - 2017-11-03 10:42:24 - In case that we have to slipt products by size of material, process products array.
				$split_products = array();

				if($this->split_colors || $this->split_sizes)
				{
					$new_products = array();

					$product_can_slipt = array('colors', 'sizes');

					foreach ($products as $key => $product) {
						$add_normal_product = true;
						foreach ($product_can_slipt as $var_change) {
							if($var_change == 'colors')
							{
								$get_values_from = $get_color_from;
								$what_name = 'color';
								$can_slipt = $this->split_colors && !empty($get_values_from);
							}
							elseif($var_change == 'sizes')
							{
								$get_values_from = $get_size_from;
								$what_name = 'size';
								$can_slipt = $this->split_sizes && !empty($get_values_from);
							}
							if($can_slipt)
							{
								$values = $this->get_special_attribute($get_values_from, $product, $what_name, $all_filters, $all_options);

								if(!empty($values))
								{
									$add_normal_product = false;
									$values = is_array($values) ? $values : explode('/', $values);
									foreach ($values as $key2 => $col) {
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
										$temp_product[$what_name] = $val_name;
										$new_products[] = $temp_product;
									}
								}
							}
						}
						if($add_normal_product)
								$new_products[] = $product;
					}
				
					$products = $new_products;
				}
			//END

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

		        if (!in_array($product['model'], $google_base_pro_ignore_products) && $show)
		        {
		        	//Color
		        		$color = array_key_exists('color', $product) ? $product['color'] : '';

		        		$force_show_color = !array_key_exists('color', $product) && $google_base_pro_product_color && !empty($get_color_from) && $this->split_colors;

						if($force_show_color || (!$this->split_colors && $google_base_pro_product_color && !empty($get_color_from)))
							$color = $this->get_special_attribute($get_color_from, $product, 'color', $all_filters, $all_options, $force_show_color);
					//END Color

					//Gender
						$gender = '';
						if($google_base_pro_product_gender && !empty($get_gender_from))
							$gender = $this->get_special_attribute($get_gender_from, $product, 'gender', $all_filters, $all_options, true);
					//END Gender

					//Age Group
						$age_group = '';
						if($google_base_pro_product_age_group && !empty($get_age_group_from))
							$age_group = $this->get_special_attribute($get_age_group_from, $product, 'age_group', $all_filters, $all_options, true);
					//END Age Group

					//Size
						$size = array_key_exists('size', $product) ? $product['size'] : '';
						if(!$this->split_sizes && $google_base_pro_product_size && !empty($get_size_from))
							$size = $this->get_special_attribute($get_size_from, $product, 'size', $all_filters, $all_options, true);
					//END Size

					//Material
						$material = '';
						if($google_base_pro_product_material && !empty($get_material_from))
							$material = $this->get_special_attribute($get_material_from, $product, 'material', $all_filters, $all_options, true);
					//END Material

	  				$output .= "\n".'<item>'."\n";
	  				if ($google_base_pro_product_title == 1)
	  					$output .= '<title><![CDATA[' . htmlentities(mb_substr($product['name'], 0, 99), ENT_QUOTES, "UTF-8") . ']]></title>'."\n";

	  				if ($google_base_pro_product_link == 1)
	  					$output .= '<link><![CDATA[' . $this->url->link('product/product', 'product_id=' . $product['product_id']) . ']]></link>'."\n";
	  				
	  				if ($google_base_pro_product_description == 1)
	  				{
	  					$product_description = trim(strip_tags(htmlspecialchars_decode($product['description']), '<br></ br>'));
	  					$product_description = strlen($product_description) > 1000 ? mb_substr($product_description, 0, 997).'...' : $product_description;
	  					//Avoid chinnese error: Bytes: 0x08 0xEF 0xBC 0x8C
	  					$product_description = preg_replace('/[\x00-\x1f]/','',htmlspecialchars($product_description));
	  					$output .= '<description><![CDATA[' . $product_description . ']]></description>'."\n";
	  				}
	  				
	  				if ($google_base_pro_product_brand == 1 && !empty($product['manufacturer']))
	  					$output .= '<g:brand><![CDATA[' . html_entity_decode($product['manufacturer'], ENT_QUOTES, 'UTF-8') . ']]></g:brand>'."\n";
	  				
	  				if ($google_base_pro_product_condition == 1)
	  					$output .= '<g:condition>new</g:condition>'."\n";

	  				if ($google_base_pro_product_id == 1)
	  				{
	  					$final_product_id = array_key_exists('product_id_combinated', $product) && !empty($product['product_id_combinated']) ? $product['product_id_combinated'] : $product[$google_base_pro_product_like];
	  					$output .= '<g:id><![CDATA[' . $final_product_id . ']]></g:id>'."\n";
	  				}

	  				if(array_key_exists('item_​group_​id', $product) && !empty($product['item_​group_​id']))
	  					$output .= '<g:item_group_id>' . $product['item_​group_​id'] . '</g:item_group_id>'."\n";

	  				if ($google_base_pro_product_image_link == 1)
	  				{
	  					if ($product['image']) {
	  						$output .= '<g:image_link><![CDATA[' . $this->model_tool_image->resize($product['image'], $google_base_pro_thumb_width, $google_base_pro_thumb_height) . ']]></g:image_link>'."\n";
	  					} else {
	  						$output .= '<g:image_link><![CDATA[' . $this->model_tool_image->resize('no_image.jpg', $google_base_pro_thumb_width, $google_base_pro_thumb_height) . ']]></g:image_link>'."\n";
	  					}
	  				}

			        
			        if($google_base_pro_product_additional_images == 1)
			        {
  						$additional_images = $this->model_catalog_product->getProductImages($product['product_id']);
			        	$count = 0;
			        	foreach ($additional_images as $key => $image) {
			        		$output .= '<g:additional_image_link>'.$this->model_tool_image->resize($image['image'], $google_base_pro_thumb_width, $google_base_pro_thumb_height).'</g:additional_image_link>'."\n";

			        		$count++;
			        		if($count == 10)
			        			break;
			        	}
			        }

	  				if ($google_base_pro_multiples_identificators == 1)
	  				{
	  					if(!empty($product['mpn']) && version_compare(VERSION, '1.5.2.1', '>'))
	  					{
	  						$mpn = strlen($product['mpn']) > 70 ? mb_substr($product['mpn'], 0, 68) : $product['mpn'];
	  						$output .= '<g:mpn><![CDATA[' . $mpn . ']]></g:mpn>'."\n";
	  					}
	  					elseif(!empty($product['model']))
	  					{
	  						$mpn = strlen($product['model']) > 70 ? mb_substr($product['model'], 0, 68) : $product['model'];
	  						$output .= '<g:mpn><![CDATA[' . $mpn . ']]></g:mpn>'."\n";
	  					}
	  					
	  					if(!empty($product['ean']) && version_compare(VERSION, '1.5.2.1', '>'))
	  						$output .= '<g:gtin><![CDATA[' . $product['ean'] . ']]></g:gtin>'."\n";
	  					elseif(!empty($product['upc']))
	  						$output .= '<g:gtin><![CDATA[' . $product['upc'] . ']]></g:gtin>'."\n";
	  				}

	  				if($google_base_pro_identifier_exists)
	  					$output .= '<g:identifier_exists>FALSE</g:identifier_exists>'."\n";

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

	  				if ($google_base_pro_product_price == 1)
	  				{
	  					$price = $product['price'];

  						if($google_base_pro_product_include_tax)
  							$price = $this->currency->format($this->tax->calculate($price, $product['tax_class_id']), $currency_code, $currency_value, false);
  						else
  							$price = $this->currency->format($price, $currency_code, $currency_value, false);

	  					$output .= '<g:price>' . $price . ' ' . $currency_code . '</g:price>'."\n";
	  				}

	  				if($google_base_pro_product_sale_price == 1 && $product['special'] > 0)
	  				{
	  					$price = $product['special'];

  						if($google_base_pro_product_include_tax)
  							$price = $this->currency->format($this->tax->calculate($price, $product['tax_class_id']), $currency_code, $currency_value, false);
  						else
  							$price = $this->currency->format($price, $currency_code, $currency_value, false);

	  					$output .= '<g:sale_price>' . $price . ' ' . $currency_code . '</g:sale_price>'."\n";
	  				}

	  				if ($google_base_pro_product_type == 1)
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
		  							//Insert product_type
		  							$output .= '<g:product_type><![CDATA[' . $string . ']]></g:product_type>'."\n";
		  							//END Insert product_type
		  						}
		  					}
		  					$last_category = array_pop($categories);
			            	$google_category_id = !empty($this->configuration['google_base_pro_merchantcenter_category_'.$last_category['category_id'].'_'.$id]) ? $this->configuration['google_base_pro_merchantcenter_category_'.$last_category['category_id'].'_'.$id] : '';
		  				}
		  				else
		  				{
		  					$last_category = array_pop($categories);
			            	$google_category_id = !empty($this->configuration['google_base_pro_merchantcenter_category_'.$last_category.'_'.$id]) ? $this->configuration['google_base_pro_merchantcenter_category_'.$last_category.'_'.$id] : '';
		  					$output .= '<g:product_type><![CDATA[' . $product['category_tree'] . ']]></g:product_type>'."\n";
		  				}
			            
			            if ($google_category_id != "")
			            {
			            	//Devman Extensions - info@devmanextensions.com - 2017-05-31 11:04:36 - Get id
			            	$google_category_id = explode(' - ', $google_category_id);
			            	if(!empty($google_category_id[0]) && is_numeric($google_category_id[0]))
			            		$output .= '<g:google_product_category><![CDATA[' . $google_category_id[0] . ']]></g:google_product_category>'."\n";
			            }
	  				}

	  				if(!empty($color))
	  					$output .= '<g:color>'.preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $color).'</g:color>'."\n";

					if(!empty($gender))
						$output .= '<g:gender>'.preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $gender).'</g:gender>'."\n";

					if(!empty($age_group))
						$output .= '<g:age_group>'.preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $age_group).'</g:age_group>'."\n";

					if(!empty($size))
						$output .= '<g:size>'.$size.'</g:size>'."\n";

					if(!empty($material))
						$output .= '<g:material>'.$material.'</g:material>'."\n";

	  				if ($google_base_pro_product_quantity == 1)
	  					$output .= '<g:quantity><![CDATA[' . $product['quantity'] . ']]></g:quantity>'."\n";

	  				if ($google_base_pro_product_weight == 1)
	  					$output .= '<g:weight><![CDATA[' . $this->weight->format($product['weight'], $product['weight_class_id']) . ']]></g:weight>'."\n";
	  				
	  				if ($google_base_pro_product_availability == 1)
	  					$output .= '<g:availability>' . ($product['quantity'] ? 'in stock' : 'out of stock') . '</g:availability>'."\n";

	  				//Devman Extensions - info@devmanextensions.com - 2017-11-30 12:36:49 - Custom labels
	  					$custom_labels = array(0,1,2,3,4,5);

						foreach ($custom_labels as $key => $cl) {
							if(${'google_base_pro_product_custom_label_'.$cl})
							{
								$fixed_word = ${'google_base_pro_product_custom_label_'.$cl.'_fixed_word'};
								$get_from = ${'get_custom_label_'.$cl.'_from'};
								$custom_label_value = '';

								if(!empty($fixed_word))
									$custom_label_value = $fixed_word;
								else
									$custom_label_value = $this->get_special_attribute($get_from, $product, 'custom_label_'.$cl, $all_filters, $all_options);

								$output .= '<g:custom_label_'.$cl.'><![CDATA[' . $custom_label_value . ']]></g:custom_label_'.$cl.'>'."\n";								
							}
						}
	  				//END

	  				$output .= '</item>'."\n";
	        	}
			}

			$output .= '</channel>'."\n"; 
			$output .= '</rss>';	

			$this->response->addHeader('Content-Type: application/xml');
			$this->response->setOutput($output);
		}
	}

	protected function get_special_attribute($get_from, $product, $what, $all_filters, $all_options, $ignore_split = false)
	{
		$product_id = $product['product_id'];

		$get_from = explode('_', $get_from);
		$record_id = $get_from[1];
		$get_from = $get_from[0];
		$break_when = array('age_group', 'gender');

		$return = "";

		$split = ($what == 'size' || ($what == 'color' && $this->split_colors)) && !$ignore_split;
		
		if($get_from == 'attribute')
		{
			if(!$this->useView)
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_attribute WHERE product_id = ".$product_id." AND language_id = ".(int)$this->language_id);
			else
			{
				$attributes = explode($this->delimiterView, $product['product_attributes_id']);
				$attributes_texts = explode($this->delimiterView, $product['product_attributes_texts']);
			}
			$attributes = !$this->useView ? $query->rows : $attributes;

			foreach ($attributes as $key => $record) {
				$attribute_id = !$this->useView ? $record['attribute_id'] : $record;
				$attribute_text = !$this->useView ? $record['text'] : $attributes_texts[$key];

				if($record_id == $attribute_id)
				{
					$return .= $attribute_text.'/';
					if(in_array($what, $break_when)) break;
				}
			}
			if(!empty($return))
				$return = substr($return, 0, -1);
		}
		elseif($get_from == 'filter')
		{
			if(!$this->useView)
			{
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_filter WHERE product_id = ".$product_id);
				$filters = $query->rows;
			}
			else
				$filters = explode($this->delimiterView, $product['product_filters']);

			foreach ($filters as $key => $filter) {
				$filter_id = !$this->useView ? $filter['filter_id'] :  $filter;

				if(!empty($all_filters[$record_id][$filter_id]))
				{
					$return .= $all_filters[$record_id][$filter_id].'/';
					if(in_array($what, $break_when)) break;
				}
			}
			if(!empty($return))
				$return = substr($return, 0, -1);
		}
		elseif($get_from == 'option')
		{
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value WHERE product_id = ".$product_id);
			$option_values = $query->rows;

			if(!$split)
			{
				foreach ($option_values as $key => $option_value) {
					$option_value_id = $option_value['option_value_id'];

					if(!empty($all_options[$record_id][$option_value_id]))
					{
						$return .= $all_options[$record_id][$option_value_id].'/';
						if(in_array($what, $break_when)) break;
					}
				}
				if(!empty($return))
					$return = substr($return, 0, -1);
			}
			else
			{
				$return_option_values = array();
				foreach ($option_values as $key => $option_value) {
					$option_value_id = $option_value['option_value_id'];

					if(!empty($all_options[$record_id][$option_value_id]))
					{
						$option_values[$key]['name'] = $all_options[$record_id][$option_value_id];
						$return_option_values[] = $option_values[$key];
					}
				}

				$return = $return_option_values;
			}
		}
        $return = !is_array($return) ? preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $return) : $return;
		return $return; 
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
}
?>