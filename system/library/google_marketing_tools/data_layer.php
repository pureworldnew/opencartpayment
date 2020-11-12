<?php
	namespace google_marketing_tools;
	class data_layer extends master{
	    function get_data_layer_initialization_code() {

	        $current_view = $this->get_current_view();

	        $code = "<script> var dataLayer = [];</script>";
            $code .= "<script> var EeProductsClick = [];</script>";
            $code .= "<script> var EePromotionsClick = [];</script>";

            $code .= "<script> var eeMultiChanelVisitProductPageStep = ".$this->get_ee_multichannel_step('visit_product_page').";</script>";
            $code .= "<script> var eeMultiChanelAddToCartStep = ".$this->get_ee_multichannel_step('add_to_cart').";</script>";
            $code .= "<script> var eeMultiChanelVisitCartPageStep = ".$this->get_ee_multichannel_step('visit_cart_page').";</script>";
            $code .= "<script> var eeMultiChanelVisitCheckoutStep = ".$this->get_ee_multichannel_step('visit_checkout_page').";</script>";
            $code .= "<script> var eeMultiChanelFinishOrderStep = ".$this->get_ee_multichannel_step('finish_order').";</script>";

            $code .= "<script>dataLayer.push({'current_view':'".$current_view."'});</script>";
            $code .= "<script>dataLayer.push({'current_list':'".$this->get_current_list()."'});</script>";
            $code .= "<script>dataLayer.push({'current_currency':'".$this->currency_code."'});</script>";
            $code .= "<script>dataLayer.push({'userId':'".$this->get_user_id()."'});</script>";
            $code .= "<script>dataLayer.push({'cart_products':".json_encode($this->get_cart_products())."});</script>";
            $code .= "<script>dataLayer.push({'string_searched':'".$this->search."'});</script>";

            if($current_view == 'search')
                $code .= "<script>dataLayer.push({'event':'isSearchView'});</script>";
            if($current_view == 'category')
                $code .= "<script>dataLayer.push({'event':'isCategoryView'});</script>";

            return $code;
        }

        function get_gdpr_code($cookies_accepted) {
	        $event_name = $cookies_accepted == 'statistics' ? 'GDPRStatisticsAccepted' : 'GDPRMarketingAccepted';
	        $var_name = $cookies_accepted == 'statistics' ? 'gdpr_statistics_status' : 'gdpr_marketing_status';

	        $code = "<script>dataLayer.push({'event':'".$event_name."'});</script>";
	        $code .= "<script>dataLayer.push({".$var_name.":'accepted'});</script>";
	        return $code;
        }

		function set_data_layer_product_detail($pro)
		{
			if(empty($pro))
				return false;

			$pro['price_final'] = $this->get_product_price($pro);
			$pro['image_url'] = $this->get_product_image_url($pro);

			$reviews = array();

			//Reviews
				if($this->config->get('config_review_status'))
       				$reviews = $this->model_catalog_review->getReviewsByProductId($pro['product_id'], 0, 20);
			//END

       		//Add product details to datalayer, this is used by google rich snippets, also will can use for another tags.
			$script = '<script type="text/javascript">';
				$script .= "dataLayer.push({";
					$script .= '"event": "productDetails",';
					$script .= '"productDetails": {';
						$script .= '"product": '.json_encode($pro).",";
						$script .= '"reviews": '.json_encode($reviews);
					$script .= "}";
				$script .= "});";
				$script .= "dataLayer.push({";
					$script .= '"event": "RichSnippets"';
				$script .= "});";
		    $script .= "</script>";

		    return $script;
		}

		function set_data_layer_order_success($order_info){
	        $params = array(
	            'id' => $order_info['order_id'],
                'total' => $order_info['total']
            );
	        $script = '<script type="text/javascript">';
                $script .= "dataLayer.push({";
                    $script .= '"event": "orderSuccess",';
                    $script .= '"orderSuccess": ' . json_encode($params);
                $script .= "});";
            $script .= "</script>";

            return $script;
        }

		function get_cart_products()
		{
			$products = $this->cart->getProducts();

			if(!empty($products))
			{
				$final_products = array();

				foreach ($products as $key => $prod) {
					$manufacturer = $this->get_product_manufacturer($prod['product_id']);
					$category_name = $this->get_product_category_name($prod['product_id']);
					$variant = '';

					if(!empty($prod['option']))
  						$variant = $this->get_product_variant_order_success($prod['option']);

  					$final_products[] = array(
				        'id' => $prod['product_id'],
						'name' => $prod['name'], 
				        'price' => $prod['price'],
				        'brand' => $manufacturer,
				        'category' => $category_name,
				        'variant' => $variant,
				        'quantity' => $prod['quantity']
					);
				}

				return $final_products;
			}
		}
	}
?>