<?php
	namespace google_marketing_tools;
	class facebook_pixel extends master{

		public function get_track_name()
		{
			if(in_array($this->route, array("product/product")))
				return 'ViewContent';
			elseif(in_array($this->route, array("product/search")))
				return 'Search';
			elseif($this->is_checkout_checkout || $this->is_checkout_cart)
				return 'InitiateCheckout';
			elseif($this->is_checkout_success)
				return 'Purchase';
			elseif(in_array($this->route, array("account/success", "affiliate/success")))
				return 'CompleteRegistration';
			else
				return 'PageView';
		}

		public function get_track_info($data_gmt)
		{
			$track_info = array();

			if(in_array($this->route, array("product/product")))
			{
			    $product_info = array_key_exists('product_details', $data_gmt) ? $data_gmt['product_details'] : '';
			    if(empty($product_info)) {
                    $product_id = $this->get_current_product_id();
                    $product_info = $this->model_catalog_product->getProduct($product_id);
                }
			    if(!empty($product_info)) {
                    $track_info = array(
                        'value' => $this->get_product_price($product_info),
                        'currency' => $this->currency_code,
                        'content_ids' => array($product_info['product_id']),
                        'content_name' => $product_info['name'],
                        'content_type' => 'product',
                    );
                }
			}
			elseif(in_array($this->route, array("product/search")))
			{
			    $product_listed = array_key_exists('product_listed', $data_gmt) ? $data_gmt['product_listed'] : '';

			    if(!empty($product_listed)) {
                    $track_info = array(
                        'value' => !empty($product_listed) ? $this->get_total_price_products($product_listed) : '0.00',
                        'currency' => $this->currency_code,
                        'search_string' => $this->search
                    );
                }
			}
			elseif(in_array($this->route, array("account/success", "affiliate/success")))
			{
				$track_info = array(
					'value' => '0.00',
					'currency' => $this->currency_code
				);
			}
			elseif( ($this->is_checkout_checkout || $this->is_checkout_cart) && !empty($this->cart_products))
			{
				$content_ids = '[';
					foreach ($this->cart_products as $key => $pro)
						$content_ids .= "'".$pro['product_id']."',";
					$content_ids = substr($content_ids, 0, -1);
				$content_ids .= ']';

				$value = $this->get_total_price_products($this->cart_products);

				$track_info = array(
					'value' => $value,
					'currency' => $this->currency_code,
					'content_ids' => $content_ids,
					'num_items' => $this->cart_units,
					'content_type' => count($this->cart_products) > 1 ? 'product_group' : 'product'
				);
			}
			elseif($this->is_checkout_success && array_key_exists('order_info', $data_gmt))
			{
                $order_data = $data_gmt['order_info'];

				if(!empty($order_data))
				{
					$quantity = 0;
					$content_ids = '[';
						foreach ($order_data['products'] as $key => $pro)
						{
							$content_ids .= "'".$pro['product_id']."',";
							$quantity += $pro['quantity'];
						}
						$content_ids = substr($content_ids, 0, -1);
					$content_ids .= ']';

					$track_info = array(
						'content_type' => count($order_data['products']) > 1 ? 'product_group' : 'product',
						'value' => $this->format_price($order_data['total']),
						'currency' => $this->currency_code,
						'content_ids' => $content_ids,
						'num_items' => $quantity
					);
				}
			}
			
			$return = json_encode($track_info);
			return str_replace('&amp;','&', $return);
		}

		public function get_data_view_content_code($data_gmt)
		{
			$track_info = $this->get_track_info($data_gmt);
            $script = '';

			if($track_info != '[]')
			{
				$script = '<script type="text/javascript">';
					$script .= 'dataLayer.push({';
						$script .= "'event': 'trackFBPixel',";
						$script .= "'fb_pixel_track_name' : '".$this->get_track_name()."',";
						$script .= "'fb_pixel_track_info' : ".$track_info;
					$script .= "});";
				$script .= "</script>";
			}

			return $script;
		}
	}
?>