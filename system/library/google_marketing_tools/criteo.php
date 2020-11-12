<?php
	namespace google_marketing_tools;
	class criteo extends master{
		public function get_params($data_gmt)
		{
			$ro = $this->route;
            $email = '';
			if(in_array($ro, array('common/home')))
			{
				$params = array(
					'PageType' => "HomePage"
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
						'PageType' => "ProductPage",
						'ProductID' => $product_info['product_id']
					);
				}
			}
			elseif(in_array($ro, array('product/category', 'product/search', 'product/manufacturer/info')))
			{
				$products_listed = array_key_exists('product_listed', $data_gmt) ? $data_gmt['product_listed'] : '';

				if(!empty($products_listed)) {
                    $params = array(
                        'PageType' => "ListingPage",
                        'ProductIDList' => $this->get_product_ids_array($products_listed)
                    );
                }
			}
			elseif($this->is_checkout_cart || $this->is_checkout_checkout)
			{
				$product_in_cart = $this->cart_products;

				$final_products = array();
				foreach ($product_in_cart as $key => $pro) {
					$temp = array(
						'id' => $pro['product_id'],
						'price' => $this->get_product_price($pro),
						'quantity' => $pro['quantity'],
					);

					$final_products[] = $temp;
				}
				$params = array(
					'PageType' => "BasketPage",
					'ProductBasketProducts' => $final_products
				);
			}
			elseif($this->is_checkout_success && array_key_exists('order_info', $data_gmt))
			{
				$order_data = $data_gmt['order_info'];

				if(!empty($order_data))
				{
					$order_products = $order_data['products'];

					$final_products = array();
					foreach ($order_products as $key => $pro) {
						$temp = array(
							'id' => $pro['product_id'],
							'price' => $this->get_product_price($pro),
							'quantity' => $pro['quantity'],
						);

						$final_products[] = $temp;
					}

					$total = $order_data['total'];
					$array_product_ids = $this->get_product_ids_array($order_products);
					$params = array(
						'PageType' => "TransactionPage",
						'ProductTransactionProducts' => $final_products,
						'TransactionID'=> $order_data['order_id']
					);

					$email = $order_data['email'];
				}
			}

			$customer_email = $this->customer->getEmail();
			$params['email'] = !empty($customer_email) ? $customer_email : $email;

			return json_encode($params, JSON_NUMERIC_CHECK);
		}

		public function get_criteo_params($data_gmt)
		{
		    $script = '';
		    $params = $this->get_params($data_gmt);
		    if(!empty($params)) {
                $script = '<script type="text/javascript">';
                $script .= "dataLayer.push({";
                $script .= "'event': 'criteoOneTag',";
                $script .= "'criteoParams' : " . $params;
                $script .= "});";
                $script .= "</script>";
            }

            return $script;
		}
	}
?>