<?php
	namespace google_marketing_tools;
	class google_dynamic_remarketing extends master{

		public function get_dynamic_remarketing_params($data_gmt)
		{
			$ro = $this->route;

			$params = array();

			if(in_array($ro, array('common/home')))
			{
				$params = array(
					'ecomm_pagetype' => "home"
				);
			}
			elseif(in_array($ro, array('product/product')))
			{
				$product_info = array_key_exists('product_details', $data_gmt) ? $data_gmt['product_details'] : '';
			    if(empty($product_info)) {
                    $product_id = $this->get_current_product_id();
                    $product_info = $this->model_catalog_product->getProduct($product_id);
                }
				if(!empty($product_info))
				{
					$params = array(
						'ecomm_prodid' => $this->get_real_product_identificator($product_info),
						'dynx_itemid' => $this->get_real_product_identificator($product_info),
						'dynx_itemid2' => $product_info[$this->config->get('google_dynamic_remarketing_dynx2_'.$this->id_store)],
						'ecomm_totalvalue' => $this->get_product_price($product_info),
						'ecomm_pagetype' => "product",
                        'ecomm_category' => $this->get_product_category_name($product_info['product_id'])
					);
				}
			}
			elseif(in_array($ro, array('product/category')))
			{
				$products_listed = array_key_exists('product_listed', $data_gmt) ? $data_gmt['product_listed'] : '';

				if(!empty($products_listed)) {
                    $params = array(
                        'ecomm_pcat' => $this->get_category_names_array_category_view(),
                        'ecomm_pagetype' => "category",
                        'ecomm_prodid' => $this->get_product_ids_array($products_listed, true)
                    );
                }
			}
			elseif(in_array($ro, array('product/search')))
			{
				$params = array(
					'ecomm_pagetype' => "searchresults",
				);

				$products_listed = array_key_exists('product_listed', $data_gmt) ? $data_gmt['product_listed'] : '';

				if(!empty($products_listed))
				{
					$total = $this->get_total_price_products($products_listed);
					$array_product_ids = $this->get_product_ids_array($products_listed, true);
					$params = array(
						'ecomm_prodid' => $array_product_ids,
						'ecomm_totalvalue' => $total,
						'ecomm_pagetype' => "searchresults",
					);
				}
			}
			elseif($this->is_checkout_cart || $this->is_checkout_checkout)
			{
				$product_in_cart = $this->cart_products;

				$total = $this->get_total_price_products($product_in_cart);
				$array_product_ids = $this->get_product_ids_array($product_in_cart, true);
				$params = array(
					'ecomm_prodid' => $array_product_ids,
					'ecomm_totalvalue' => $total,
					'ecomm_pagetype' => "cart",
				);
			}
			elseif($this->is_checkout_success && array_key_exists('order_info', $data_gmt))
			{
				$order_data = $data_gmt['order_info'];

				if(!empty($order_data))
				{
					$order_products = $order_data['products'];

					$total = $order_data['total'];
					$array_product_ids = $this->get_product_ids_array($order_products, true);
					$params = array(
						'ecomm_prodid' => $array_product_ids,
						'ecomm_totalvalue' => $total,
						'ecomm_pagetype' => "purchase",
					);
				}
			}
			else
			{
				$params = array(
					'ecomm_pagetype' => "other",
				);
			}
			return json_encode($params);
		}

		public function get_gdr_params($data_gmt)
		{
		    $script = '';
		    $params = $this->get_dynamic_remarketing_params($data_gmt);

		    if(!empty($params)) {
                $script = '<script type="text/javascript">';
                $script .= "dataLayer.push({";
                $script .= "'googleDynamicRemarketing' : " . $this->get_dynamic_remarketing_params($data_gmt);
                $script .= "});";
                $script .= "</script>";
            }

			return $script;
		}
		public function get_gdr_event() {
		    $script = '<script type="text/javascript">';
                $script .= "dataLayer.push({";
                $script .= "'event': 'googleDynamicRemarketing'";
                $script .= "});";
            $script .= "</script>";

            return $script;
        }
	}
?>