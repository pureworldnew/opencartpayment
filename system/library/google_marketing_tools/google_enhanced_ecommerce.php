<?php
	namespace google_marketing_tools;
	class google_enhanced_ecommerce extends master{
        function get_impressions_product_code($products, $add_word_to_list = '') {
            $products = array_values($products);
            $list_name = $this->get_current_list().(!empty($add_word_to_list) ? ' - '.$add_word_to_list : '');
            $products_formatted = $this->format_products_to_ee($products, $list_name);
            $code = '';
            if (!empty($products_formatted)) {
                $params = array(
                    'currencyCode' => $this->currency_code,
                    'impressions' => $products_formatted
                );
                $code = '<script>dataLayer.push({
                    "ecommerce":'.json_encode($params).',
                    "event": "enhancedEcommerceProductImpressions"
                });</script>';
                foreach ($products_formatted as $key => $product) {
                    $href = array_key_exists($key, $products) && array_key_exists('href', $products[$key]) ? $products[$key]['href'] : '';
                    if(!empty($href))
                        $code .= '<script>EeProductsClick["'.$href.'"] = '.json_encode($product).';</script>';
                }
            }

            return $code;
        }

        function get_impressions_promotion_code($promotions, $setting_name = '') {
            $params = array();
            $promotions_formatted = $this->format_promotions_to_ee($promotions, $setting_name);
            $params['promoView']['promotions'] = $promotions_formatted;

            $code = '<script>dataLayer.push({
                "ecommerce":'.json_encode($params).',
                "event": "enhancedEcommercePromotionImpressions"
            });</script>';

            foreach ($promotions_formatted as $key => $promotion) {
                $link = array_key_exists($key, $promotions) && array_key_exists('link', $promotions[$key]) ? $promotions[$key]['link'] : '';
                if(!empty($link))
                    $code .= '<script>EePromotionsClick["'.$link.'"] = '.json_encode($promotion).';</script>';
            }

            return $code;
        }

        function get_details_product_code($product) {
            $params = array(
                'detail' => array(
                    'actionField' => array('list' => $this->get_current_list()),
                    'products' => array(array($this->format_product_to_ee($product, '', true)))
                )
            );

            $code = '<script>dataLayer.push({
                        "ecommerce":'.json_encode($params).',
                        "event": "enhancedEcommerceProductDetails"
            });</script>';

            return $code;
        }

        function format_products_to_ee($products, $list_name = '') {
            if(empty($list_name))
                $list_name = $this->get_current_list();

            $final_products = array();
            foreach ($products as $key => $prod) {
                $prod_formatted = $this->format_product_to_ee($prod, $list_name);
                $prod_formatted['position'] = $key+1;
                $final_products[] = $prod_formatted;
            }

            return $final_products;
        }

        function format_product_to_ee($product, $list_name = '', $remove_view = false) {
            if(empty($list_name) && !$remove_view)
                $list_name = $this->get_current_list();

            $prod_id = $product['product_id'];
            
            $basic_data = array(
                //'id' => $this->get_real_product_identificator($product),
                'id' => $product['product_id'],
                'name' => $product['name'],
                'price' => $this->get_product_price($product),
                'brand' => $this->get_product_manufacturer($prod_id),
                'category' => $this->get_product_category_name($prod_id),
                'list' => $list_name,
            );

            if(array_key_exists('quantity', $product)) {
                $basic_data['quantity'] = $product['quantity'];
                $product_from_order = array_key_exists('order_product_id', $product) && !empty($product['order_product_id']);
                if($product_from_order && $product['quantity'] > 1) {
                    $basic_data['price'] = number_format($basic_data['price']/$product['quantity'], 2);
                }
            }

            if($remove_view)
                unset($basic_data['list']);

            return $basic_data;
        }

        function format_promotions_to_ee($promotions, $setting_name = '') {

            $final_promotions = array();
            foreach ($promotions as $key => $promot) {
                $promot_formatted = $this->format_promotion_to_ee($promot);
                $promot_formatted['position'] = (!empty($setting_name) ? $setting_name.' - ' : '').($key+1);
                $final_promotions[] = $promot_formatted;
            }

            return $final_promotions;
        }

        function format_promotion_to_ee($promotion) {
            $basic_data = array(
                'name' => $promotion['title'],
            );

            if(array_key_exists('result_copy', $promotion)) {
                if(array_key_exists('banner_id', $promotion['result_copy']))
                   $basic_data['id'] =  $promotion['result_copy']['banner_id'];
                if(array_key_exists('name', $promotion['result_copy']))
                   $basic_data['creative'] =  $promotion['result_copy']['name'];
            }

            return $basic_data;
        }

        function get_measuring_purchase_code($order_info) {
            //Format totals
                $shipping = 0;
                $tax = 0;
                $subtotal = 0;
                $coupon = '';
                foreach ($order_info['totals'] as $key => $ord) {
                    if ($ord['code'] == 'sub_total')
                        $subtotal += $ord['value'];
                    elseif ($ord['code'] == 'shipping')
                        $shipping += $ord['value'];
                    elseif ($ord['code'] == 'tax')
                        $tax += $ord['value'];
                    elseif ($ord['code'] == 'coupon')
                        $coupon = $ord['title'];
                }
                $order_info['shipping'] = $this->format_price($shipping);
                $order_info['tax'] = $this->format_price($tax);
                $order_info['subtotal'] = $this->format_price($subtotal);
                $order_info['coupon'] = $coupon;
                $order_info['total'] = $this->format_price($order_info['total']);
            //END

            //Add Enhanced Ecommerce object to datalayer
                $params = array(
                    'currencyCode' => $this->currency_code,
                    'purchase' => array(
                        'actionField' => array(
                            'id' => $order_info['order_id'],
                            'affiliation' => $order_info['store_name'],
                            'revenue' => $order_info['total'],
                            'tax' => $order_info['tax'],
                            'shipping' => $order_info['shipping'],
                            'coupon' => $order_info['coupon'],
                        ),
                        'products' => $this->format_products_to_ee($order_info['products'])
                    )
                );

                $code = '<script>dataLayer.push({
                    "ecommerce":'.json_encode($params).',
                    "event": "enhancedEcommercePurchase"
                });</script>';

                $code .= '<script> var measure_purchase = true;</script>';

                return $code;
            //END
        }
    }


?>