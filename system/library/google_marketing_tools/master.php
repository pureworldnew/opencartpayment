<?php
	namespace google_marketing_tools;
	class Master {
	    private $scripts = array();
	    private $gmt_data = array();

		public function __construct($registry) {
			$this->log = $registry->get('log');
			$this->config = $registry->get('config');
			$this->db = $registry->get('db');
			$this->session = $registry->get('session');
			$this->load = $registry->get('load');
			$this->request = $registry->get('request');
			$this->language = $registry->get('language');
			$this->currency = $registry->get('currency');
			$this->url = $registry->get('url');
			$this->tax = $registry->get('tax');
			$this->customer = $registry->get('customer');
			$this->cart = $registry->get('cart');
            $this->id_store = $this->config->get('config_store_id');
            $this->is_oc_3x =  version_compare(VERSION, '3', '>=');
            $this->currency_code = version_compare(VERSION, '2.2.0.0', '<') ? $this->currency->getCode() : $this->session->data['currency'];
            $this->cart_products = $this->cart->getProducts();
			$this->cart_units = $this->cart->countProducts();
            $this->language_code = $this->language->get('code');
		    $this->language_id = $this->config->get('config_language_id');
		    $this->search = !empty($this->request->get['search']) ? $this->request->get['search'] : (!empty($this->request->get['filter_name']) ? $this->request->get['filter_name'] : '');
		    $this->tag = !empty($this->request->get['tag']) ? $this->request->get['tag'] : $this->search;
		    $this->description = !empty($this->request->get['description']) ? $this->request->get['description'] : '';
		    $this->load_models($registry);

		    $this->assets_path = DIR_SYSTEM.'assets/gmt_includes/';

			$checkout_checkout_compatibilities = array('simplecheckout', 'checkout', 'quickcheckout');
            if(is_file($this->assets_path.'new_checkout_views.php'))
                require($this->assets_path.'new_checkout_views.php');
			$this->_checkout_checkout_compatibilities = $checkout_checkout_compatibilities;

            $this->set_route_and_id();
		    $this->is_checkout_checkout = in_array($this->route, array("checkout/checkout", "supercheckout/supercheckout", "simplecheckout/", "simplecheckout", "simple-checkout/", "extension/quickcheckout/checkout"));

		    $checkout_success_pages = array("checkout/success", "supercheckout/success", "checkout-success/", "success");
            if(is_file($this->assets_path.'new_checkout_success_views.php'))
                require($this->assets_path.'new_checkout_success_views.php');
            $is_checkout_success = in_array($this->route, $checkout_success_pages);

		    $this->is_checkout_success = $is_checkout_success;
		    $checkout_cart_pages = array("checkout/cart");
            if(is_file($this->assets_path.'new_checkout_cart_views.php'))
                require($this->assets_path.'new_checkout_cart_views.php');
		    $this->is_checkout_cart = in_array($this->route, $checkout_cart_pages);
		    $this->is_products_list_view = in_array($this->route, array("product/search", "product/category", "product/manufacturer/info"));


		    $this->categories_id = array();
		    $this->category_id = 0;

		    if(!empty($this->request->get['path']))
		    {
			    $categories_id = !empty($this->request->get['path']) ? explode('_', $this->request->get['path']) : array();
			    $this->categories_id = $categories_id;
			    $categories_id_temp = $categories_id;
			    $current_category_id = !empty($categories_id_temp) ? array_pop($categories_id_temp) : 0;
			    $this->category_id = !empty($this->request->get['category_id']) ? $this->request->get['category_id'] : $current_category_id;
			}
			elseif(!empty($this->request->get["_route_"]))
			{
				$categories_id = $this->startPathCategoryFromSEOURL($this->request->get["_route_"]);
				$this->categories_id = $categories_id;
				$this->category_id = !empty($categories_id[0]) ? $categories_id[0] : '';
			}

			$manufacturer_id = '';
			if($this->route == 'product/manufacturer/info' && array_key_exists('manufacturer_id', $this->request->get))
			    $manufacturer_id = $this->request->get['manufacturer_id'];

			$this->manufacturer_id = $manufacturer_id;

		    $this->sub_category = !empty($this->request->get['sub_category']) ? 1 : '';

            $this->information_id = array_key_exists('information_id', $this->request->get) ? $this->request->get['information_id'] : false;

		    $this->sort = !empty($this->request->get['sort']) ? $this->request->get['sort'] : 'p.sort_order';
		    $this->order = !empty($this->request->get['order']) ? $this->request->get['order'] : 'ASC';
		    $this->page = !empty($this->request->get['page']) && is_numeric($this->request->get['page']) ? $this->request->get['page'] : 1;
		    $this->limit = !empty($this->request->get['limit']) && is_numeric($this->request->get['limit']) ? $this->request->get['limit'] : ($this->config->get('config_product_limit') ? $this->config->get('config_product_limit') : 20);

		    $this->filter_data = array(
		      'filter_name'         => $this->search,
		      'filter_tag'          => $this->tag,
		      'filter_description'  => $this->description,
		      'filter_category_id'  => $this->category_id,
		      'filter_sub_category' => $this->sub_category,
		      'sort'                => $this->sort,
		      'order'               => $this->order,
		      'start'               => ($this->page - 1) * $this->limit,
		      'limit'               => $this->limit
		    );

		    //Devman Extensions - info@devmanextensions.com - 2017-11-21 21:20:03 - Abandoned carts
		    	$this->apikey = $this->config->get('google_ac_api_key_'.$this->id_store);
				$this->listid = $this->config->get('google_ac_list_id_'.$this->id_store);
				$apikey_explode = explode('-', $this->apikey);

				if(count($apikey_explode) == 2 & !empty($this->apikey))
					$this->server = $apikey_explode[1];
				else
				    $this->server = '';
		    //END
		}

		function gmt_is_enabled() {
		    $gmt_status = $this->config->get('google_tag_manager_status_'.$this->id_store);

		    if($gmt_status) {
		        $identification = $this->config->get('google_container_id_'.$this->id_store);
				$is_gtm = strpos($identification, 'GTM') !== false;

				if(!$is_gtm)
					return false;
				return true;
            }
		}

		function get_gtm_id() {
            $gtm_id = $this->config->get('google_container_id_'.$this->id_store);
            return $gtm_id;
        }

        function get_user_id() {
		    $user_id  = $this->customer->isLogged() ? $this->customer->getId() : 0;
		    return $user_id;
        }

        function get_ee_multichannel_step($action) {
		    if(!$this->config->get('google_multichannel_funnel_status_'.$this->id_store))
		        return 0;

		    if($this->config->get('google_multichannel_step_1_'.$this->id_store) == $action)
                return 1;
            else if($this->config->get('google_multichannel_step_2_'.$this->id_store) == $action)
                return 2;
            else if($this->config->get('google_multichannel_step_3_'.$this->id_store) == $action)
                return 3;
            else if($this->config->get('google_multichannel_step_4_'.$this->id_store) == $action)
                return 4;
            else if($this->config->get('google_multichannel_step_5_'.$this->id_store) == $action)
                return 5;
            else
                return 0;
        }

        function get_current_view() {
            if($this->route == 'common/home')
				return 'homepage';
			elseif(in_array($this->route, array("product/product")))
				return 'product';
			elseif(in_array($this->route, array("product/wishlist")))
				return 'wishlist';
			elseif($this->is_checkout_checkout)
				return 'checkout';
			elseif($this->is_checkout_success)
                return 'purchase';
			elseif($this->is_checkout_cart)
				return 'cart';
			elseif(in_array($this->route, array("product/special")))
				return 'special';
			elseif(in_array($this->route, array("product/search")))
				return 'search';
			elseif(in_array($this->route, array("product/category")))
				return 'category';
			elseif(in_array($this->route, array("product/manufacturer/info")))
				return 'manufacturer';
			elseif(in_array($this->route, array("product/manufacturer")))
				return 'manufacturer_list';
			elseif(in_array($this->route, array("information/information")))
				return 'information';
			elseif(in_array($this->route, array("account/register")))
				return 'account_register';
			elseif(in_array($this->route, array("account/login")))
				return 'account_login';
			elseif(in_array($this->route, array("account/account")))
				return 'account_account';
			elseif(in_array($this->route, array("account/password")))
				return 'account_password';
			elseif(in_array($this->route, array("account/address")))
				return 'account_address';
			elseif(in_array($this->route, array("account/forgotten")))
				return 'account_forgotten';
			elseif(in_array($this->route, array("account/wishlist")))
				return 'account_wishlist';
			elseif(in_array($this->route, array("account/order")))
				return 'account_order';
			elseif(in_array($this->route, array("account/download")))
				return 'account_download';
			elseif(in_array($this->route, array("account/recurring")))
				return 'account_recurring';
			elseif(in_array($this->route, array("account/reward")))
				return 'account_reward';
			elseif(in_array($this->route, array("account/return")))
				return 'account_return';
			elseif(in_array($this->route, array("account/transaction")))
				return 'account_transaction';
			elseif(in_array($this->route, array("account/newsletter")))
				return 'account_newsletter';
			elseif(in_array($this->route, array("account/recurring")))
				return 'account_recurring';
			elseif(in_array($this->route, array("account/voucher")))
				return 'account_voucher';
			elseif(in_array($this->route, array("account/logout")))
				return 'account_logout';
			elseif(in_array($this->route, array("account/success")))
				return 'account_success';
			elseif(in_array($this->route, array("affiliate/register")))
				return 'affiliate_register';
			elseif(in_array($this->route, array("affiliate/login")))
				return 'affiliate_login';
			elseif(in_array($this->route, array("affiliate/success")))
				return 'affiliate_success';
			elseif(in_array($this->route, array("information/contact")))
				return 'contact';
			elseif(in_array($this->route, array("information/sitemap")))
				return 'sitemap';
			else
				return $this->route;
        }

        function get_current_list() {
		    $route_name = $this->get_current_view();
		    $routes_need_more_data = array('product', 'search', 'category', 'manufacturer', 'information');

		    if(in_array($route_name, $routes_need_more_data)) {
		        if($route_name == 'product') {
		            $product_details = $this->get_data('product_details');

		            if(empty($product_details)) {
		                $product_id = $this->get_current_product_id();
		                if($product_id) {
                            $model = $this->get_product_data($product_id, array('model'));
                            $product_details = array(
                                'model' => $model['model'],
                                'name' => $this->get_product_name($product_id)
                            );
                        }
                    }

		            if(!empty($product_details)) {
                        $route_name = $route_name . ': ' . $product_details['model'] . ' - ' . $product_details['name'];
                    }
                } else if($route_name == 'manufacturer' && $this->id) {
		            $route_name = $route_name.': '.$this->get_manufacturer_name($this->id);
                } else if($route_name == 'category' && $this->categories_id) {
		            $category_names = implode (' > ', $this->get_category_names_array_category_view(true));
		            $route_name = $route_name.': '.$category_names;
                } else if($route_name == 'search' && $this->search) {
		            $route_name = $route_name.': '.trim(ucfirst(strtolower($this->search)));
                } else if($route_name == 'information' && $this->id) {
		            $route_name = $route_name.': '.$this->get_information_title($this->id);
                }
            }

		    return $route_name;
        }

        function get_manufacturer_name($manufacturer_id) {
		    $manufacturer_name = $this->db->query('SELECT `name` FROM '. DB_PREFIX . 'manufacturer WHERE manufacturer_id = '.(int)$manufacturer_id);

		    if($manufacturer_name->num_rows == 1)
		        return $manufacturer_name->row['name'];
		    else return '';
        }

        function get_category_names_array_category_view($category_reverse = false)
		{
			$array_categories = array();
			$temp_categories = /*$category_reverse ? array_reverse($this->categories_id) :*/ $this->categories_id;
			foreach ($temp_categories as $key3 => $cat_id) {
				$category_info = $this->model_catalog_category->getCategory($cat_id);

				if(!empty($category_info['name']))
					$array_categories[] = $category_info['name'];
			}

			return $array_categories;
		}

		function get_information_title($information_id) {
            $information = $this->db->query('SELECT `title` FROM '. DB_PREFIX .'information_description WHERE information_id = '.$information_id.' AND language_id = '.$this->language_id);
            if($information->num_rows == 1)
		        return $information->row['title'];
		    else return '';
        }

        function load_models($registry) {
		    $this->load->model('catalog/product');
			$this->model_catalog_product = $registry->get('model_catalog_product');

			$this->load->model('tool/image');
			$this->model_tool_image = $registry->get('model_tool_image');

			$this->load->model('catalog/category');
			$this->model_catalog_category = $registry->get('model_catalog_category');

			$this->load->model('catalog/review');
			$this->model_catalog_review = $registry->get('model_catalog_review');

			$this->load->model('checkout/order');
			$this->model_checkout_order = $registry->get('model_checkout_order');
        }

        function set_route_and_id() {
		    if(empty($this->request->get["_route_"])) {
			    $this->id = '';
                $this->route = !empty($this->request->get["route"]) ? $this->request->get["route"] : 'common/home';
            }
			else {
		        $route_string = strlen($this->request->get["_route_"]) > 1 ? rtrim($this->request->get["_route_"], '/') : $this->request->get["_route_"];
                $route = $this->translateSEOURL($route_string, true);
                $id = '';
                if(is_array($route)) {
                    $this->route = $route['route'];
                    $this->id = $route['id'];
                } else {
                    $this->route = $route;
                    $this->id = '';
                }
            }
        }

        function translateSEOURL($seo_url, $get_id = false)
		{
			$parts = explode('/', $seo_url);


            if(empty($parts[0]) && count($parts) > 1) {
                unset($parts[0]);
                $parts = array_values($parts);
            }

			//Devman Extensions - info@devmanextensions.com - 2017-07-29 11:18:09 - Cart compatibility
			if((!empty($parts[0]) && $parts[0] == 'cart') || (empty($parts[0]) && $parts[1] == 'cart'))
				return 'checkout/cart';

			//Devman Extensions - info@devmanextensions.com - 2017-06-17 12:22:06 - Checkout compatibilities
				$custom_checkout = (!empty($parts[1]) && in_array($parts[1], $this->_checkout_checkout_compatibilities)) || (count($parts) == 1 && in_array($parts[0], $this->_checkout_checkout_compatibilities));
				if($custom_checkout || (count($parts) == 2 && empty($parts[0]) && $parts[1] == 'checkout'))
					return 'checkout/checkout';

			//Devman Extensions - info@devmanextensions.com - 2017-07-29 12:34:02 - Checkout success compatibility
			if(!empty($parts[0]) && $parts[0] == 'success' && !empty($parts[1]) && $parts[1] == 'checkout')
				return 'checkout/success';

            //$parts = array_reverse($parts);
			foreach ($parts as $part) {
				if(!empty($part))
				{
					//Devman Extensions - info@devmanextensions.com - 2017-06-26 19:11:53 - Compatibility with some seo external extensions
						if (!empty($part) && strpos($part, '=') === false) {
							if($part == 'search')
								return 'product/search';
						}
					//END

					if(!$this->is_oc_3x)
						$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($part) . "'");
					else
						$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE keyword = '" . $this->db->escape($part) . "' AND language_id = ".$this->config->get('config_language_id')." AND store_id = ".$this->config->get('config_store_id'));

					/*SEO BACKPACK ISENSE EXTENSION COMPATIBILITY - INSERT INTO oc_setting (`code`, `key`, `value`, `serialized`) VALUES ('google', 'google_backpack_isense_compatibility', '1', 0);*/
						if (empty($query->num_rows) && $this->config->get('google_backpack_isense_compatibility')) {
							$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url_alias WHERE keyword = '" . $this->db->escape($part) . "'");
						}
					//END

					if ($query->num_rows) {

						$url = explode('=', $query->row['query']);

						if ($url[0] == 'product_id')
							return 'product/product';
						elseif ($url[0] == 'category_id')
							return 'product/category';
						elseif ($url[0] == 'manufacturer_id') {
						    if($get_id) {
						        return array(
						            'id' => array_key_exists(1, $url) ? $url[1] : '',
                                    'route' => 'product/manufacturer/info'
                                );
                            }
                            return 'product/manufacturer/info';
                        }
						elseif ($url[0] == 'information_id') {
						    if($get_id) {
						        return array(
						            'id' => array_key_exists(1, $url) ? $url[1] : '',
                                    'route' => 'information/information'
                                );
                            }
                            return 'information/information';
                        }

					}
				}
			}
			//Devman Extensions - info@devmanextensions.com - 2017-08-01 16:58:55 - If not found any seo url, return "Other"
			return $seo_url;
		}

		function get_gmt_version() {
		    return $this->config->get('google_version');
        }

        function set_script($html_position, $script, $begin_array = false) {
		    if($begin_array) {
		        $copy_scripts = array_key_exists($html_position, $this->scripts) ? $this->scripts[$html_position] : array();
		        $new_scripts = array();
		        $new_scripts[] = $script;
		        foreach ($copy_scripts as $code) {
		            $new_scripts[] = $code;
		        }
		        $this->scripts[$html_position] = $new_scripts;
            }
		    else
                $this->scripts[$html_position][] = $script;
        }

        function get_scripts($html_position = null, $string_format = true) {
		    if(!$html_position)
		        return $this->scripts;

		    if(!array_key_exists($html_position, $this->scripts))
		        return false;

		    if(!$string_format)
		        return $this->scripts[$html_position];

		    if($string_format) {
		        $string = '';
                foreach ($this->scripts[$html_position] as $id_code => $cod) {
                    $string .= $cod;
                }
                return $string;
            }
            return false;
        }

        function set_data($id, $data) {
            $this->gmt_data[$id] = $data;
        }

        function add_data($id, $data) {
		    $current_data = $this->get_data($id);
		    
		    if(empty($current_data))
                $this->gmt_data[$id] = $data;
		    else {
		        if(is_array($data)) {
		            foreach ($data as $key => $dat) {
		                $this->gmt_data[$id][] = $dat;
		            }
                } else {
		            $this->gmt_data[$id][] = $data;
                }
            }
        }

        function get_data($id = '') {
		    if(empty($id))
		        return $this->gmt_data;

            $data = array_key_exists($id, $this->gmt_data) ? $this->gmt_data[$id] : false;
            return $data;
        }

        function get_real_product_identificator($product) {
		    $product_identificator = $this->config->get('google_product_id_like_'.$this->id_store);
		    
		    if(empty($product_identificator) || $product_identificator == 'product_id')
		        return $product['product_id'];
		    
		    $identificator = $this->get_product_data($product['product_id'], array($product_identificator));

		    return !empty($identificator[$product_identificator]) ? $identificator[$product_identificator] : $product['product_id'];
        }

        function get_current_product_id() {
		    $prod_id = isset($this->request->get) && array_key_exists('product_id', $this->request->get) ? $this->request->get['product_id'] : false;
		    return $prod_id;
        }

        function get_product_data($product_id, $data) {
		    $sql = "SELECT `".implode("` , `", $data)."` FROM `".DB_PREFIX."product` WHERE product_id = ".$product_id;
		    $result = $this->db->query($sql);
		    $result = !empty($result->row) ? $result->row : false;
		    return $result;
        }

        function get_product_name($product_id) {
		    $sql = "SELECT `name` FROM `".DB_PREFIX."product_description` WHERE product_id = ".$product_id." AND language_id = ".(int)$this->language_id;
		    $result = $this->db->query($sql);
		    return array_key_exists('name', $result->row) ? $result->row['name'] : '';
        }

        function validate_order_status($order_id) {
		    $order_status_id = $this->getOrderStatus($order_id);

		    $order_statuses_valid = $this->config->get('google_positive_conversion_status_id_'.$this->id_store);

            if (empty($order_statuses_valid))
                return true;
            else {
                if (in_array($order_status_id, $order_statuses_valid))
                    return true;
                else
                    return false;
            }

            return false;
        }

		function get_product_category_name($product_id)
		{
			$this->load->model('catalog/product');
			$product_categories = $this->model_catalog_product->getCategories($product_id);
			$this->load->model('catalog/category');

			if(!empty($product_categories[0]['category_id']))
				$category_info = $this->model_catalog_category->getCategory($product_categories[0]['category_id']);

			return !empty($category_info['name']) ? str_replace("'", "\'", $category_info['name']) : '';
		}

		function get_total_price_products($products)
		{
			$total = 0;
			foreach ($products as $key => $pro) {
			    $quantity = array_key_exists('quantity', $pro) ? $pro['quantity'] : 1;
				$total += $this->get_product_price($pro['product_id'], true)*$quantity;
			}
			return $total;
		}

		function get_product_ids_array($products, $get_real_id = false)
		{
			$product_ids = array();

			foreach ($products as $key => $prod) {
				$product_ids[] = $get_real_id ? $this->get_real_product_identificator($prod) : $prod['product_id'];
			}

			return $product_ids;
		}

		function get_product_price($product_info, $from_product_id = false)
		{
		    $final_price = 0;

		    if ($from_product_id)
                $product_info = $this->model_catalog_product->getProduct($product_info);

		    $product_from_order = array_key_exists('order_product_id', $product_info) && !empty($product_info['order_product_id']);

		    if($product_from_order) {
		        $price = $this->db->query('SELECT `total` FROM '.DB_PREFIX.'order_product WHERE order_product_id='.(int)$product_info['order_product_id']);

		        if(!empty($price->row['total']))
		            $final_price = $this->format_price($price->row['total'], true);
            }

            if (!$product_from_order && !empty($product_info) && is_array($product_info)) {
                if (!array_key_exists('tax_class_id', $product_info))
                    $product_info['tax_class_id'] = $this->get_product_tax_class_id($product_info['product_id']);

                if (!empty($product_info['special']))
                    $price = !is_numeric($product_info['special']) ? $product_info['special'] : round($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), 2);
                else
                    $price = !is_numeric($product_info['price']) ? $product_info['price'] : round($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), 2);

                $final_price = $this->format_price($price, true);
            }

            return $final_price;
		}

		function get_product_image_url($product_info)
		{
			$width = 500;
			$height = 500;

			if(version_compare(VERSION, '2.1.0.2.1', '<='))
			{
				$width = $this->config->get('config_image_popup_width');
				$height = $this->config->get('config_image_popup_height');
			}
			elseif(version_compare(VERSION, '2.1.0.2.1', '>') && version_compare(VERSION, '2.3.0.2', '<='))
			{
				$width = $this->config->get($this->config->get('config_theme') . '_image_popup_width');
				$height = $this->config->get($this->config->get('config_theme') . '_image_popup_height');
			}
			else
			{
				$width = $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_width');
				$height = $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_height');
			}

			$width = empty($width) ? 500 : $width;
			$height = empty($height) ? 500 : $height;

			return $this->model_tool_image->resize($product_info['image'], $width, $height);
		}

		function format_price($price, $currency = true, $thousands = false)
		{
			if($currency)
				$price = $this->currency->format($price, $this->currency_code, '', false);

			$decimal_separator = !$thousands ? '.' : ',';
			$thousands_separator = !$thousands ? '' : '.';
			return number_format((float)$price, 2, $decimal_separator, $thousands_separator);
		}

		function translate_controller_name($controller)
		{
			if (strpos($controller, 'ModuleFeatured') !== false)
				return 'Featured module';
			elseif (strpos($controller, 'ModuleBestSeller') !== false)
				return 'Best seller module';
			elseif (strpos($controller, 'ModuleLatest') !== false)
				return 'Latest module';
			elseif (strpos($controller, 'ModuleSpecial') !== false)
				return 'Special module';
			else
				return '';
		}

		function get_product_manufacturer($product_id)
		{
		    if(empty($product_id))
		        return '';

			$sql = "SELECT ma.name FROM " . DB_PREFIX . "product pr LEFT JOIN " . DB_PREFIX . "manufacturer ma ON (pr.manufacturer_id = ma.manufacturer_id) WHERE pr.product_id = ".$product_id;
			$results = $this->db->query($sql);

			return !empty($results->row['name']) ? $results->row['name'] : '';
		}

		function get_product_tax_class_id($product_id)
		{
			$sql = "SELECT pr.tax_class_id FROM " . DB_PREFIX . "product pr WHERE pr.product_id = ".$product_id;
			$results = $this->db->query($sql);

			return !empty($results->row['tax_class_id']) ? $results->row['tax_class_id'] : '';
		}

		function get_product_variant($product_id, $options_choosed)
		{
			$product_options = $this->model_catalog_product->getProductOptions($product_id);
			$product_options = $this->format_options($product_options);

			foreach ($options_choosed as $product_option_id => $option_value_id) {

				if(is_array($option_value_id))
					$option_values_id = $option_value_id;
				else
					$option_values_id = array($option_value_id);

				foreach ($option_values_id as $key => $opt_val_id) {

					$key_exists = array_key_exists($product_option_id, $product_options) && array_key_exists($opt_val_id, $product_options[$product_option_id]);

					if($key_exists && !empty($product_options[$product_option_id][$opt_val_id]))
						return $product_options[$product_option_id][$opt_val_id];
				}

			}

			return '';
		}

		function get_product_variant_order_success($options)
		{
			foreach ($options as $key => $opt) {
				return $opt['name'].(!empty($opt['value']) ? ': '.$opt['value'] : '');
			}
		}

		function format_options($options)
		{
			$final_options = array();

			foreach ($options as $key => $opt) {
				if(!isset($final_options[$opt['product_option_id']]))
					$final_options[$opt['product_option_id']] = array();

				$option_values = version_compare(VERSION, '2.0.0.0', '>=') ? $opt['product_option_value'] : $opt['option_value'];

				if(!empty($option_values))
				{
					foreach ($option_values as $key2 => $optval) {
						$final_options[$opt['product_option_id']][$optval['product_option_value_id']] = $opt['name'].': '.$optval['name'];
					}
				}
			}

			return $final_options;
		}

		function startPathCategoryFromSEOURL($seo_url)
		{
			$parts = explode('/', $seo_url);
			//$parts = array_reverse($parts);

			$categories_id = array();

			foreach ($parts as $part) {
				if(!$this->is_oc_3x)
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($part) . "'");
				else
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE keyword = '" . $this->db->escape($part) . "' AND language_id = ".$this->config->get('config_language_id')." AND store_id = ".$this->config->get('config_store_id'));

				if ($query->num_rows) {
					$url = explode('=', $query->row['query']);
					if ($url[0] == 'category_id')
						$categories_id[] = $url[1];
				}
			}

			return $categories_id;
		}

		function getOrder($order_id) {
		    $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = " . (int)$order_id);

			return !empty($query->rows) ? $query->rows[0] : array();
        }

        function getOrderTotals($order_id) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order");

			return $query->rows;
		}

		function getOrderProducts($order_id) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

			return $query->rows;
		}

		function getOrderStatus($order_id) {
		    $query = $this->db->query("SELECT order_status_id FROM `" . DB_PREFIX . "order` WHERE order_id = " . (int)$order_id);

			return !empty($query->row) ? $query->row['order_status_id'] : '';
        }

        function generate_uuid() {
		    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
		        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
		        mt_rand( 0, 0xffff ),
		        mt_rand( 0, 0x0fff ) | 0x4000,
		        mt_rand( 0, 0x3fff ) | 0x8000,
		        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
		    );
		}


	}
?>