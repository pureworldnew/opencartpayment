<?php
	namespace google_marketing_tools;
	class google_reviews extends master {
        function get_reviews_badge_code() {
		    $gr_status = $this->config->get('google_reviews_badge_code_' . $this->id_store);
            $gr_style = $this->config->get('google_reviews_badge_code_style_' . $this->id_store);
            $gr_id = $this->config->get('google_reviews_id_' . $this->id_store);

            $gr_lang_supported = array('af', 'ar-AE', 'cs', 'da', 'de', 'en_AU', 'en_GB', 'en_US', 'es', 'es-419', 'fil', 'fr', 'ga', 'id', 'it', 'ja', 'ms', 'nl', 'no', 'pl', 'pt_BR', 'pt_PT', 'ru', 'sv', 'tr', 'zh-CN', 'zh-TW');
            $gr_lang_default_code = $this->config->get('google_reviews_lang_default_code_' . $this->id_store);
            $lang_code = in_array($this->config->get('config_language_id'), $gr_lang_supported) ? $this->config->get('config_language_id') : (!empty($gr_lang_default_code) ? $gr_lang_default_code : 'en_US');

            if ($gr_status && !empty($gr_id)) {
                $script_google_reviews = '
                   <!-- BEGIN GCR Badge Code -->
                    <script src="https://apis.google.com/js/platform.js?onload=renderBadge" async defer></script>
                    <script>
                      window.renderBadge = function() {
                        var ratingBadgeContainer = document.createElement("div");
                          document.body.appendChild(ratingBadgeContainer);
                          window.gapi.load(\'ratingbadge\', function() {
                            window.gapi.ratingbadge.render(
                              ratingBadgeContainer, {
                                "merchant_id": '.$gr_id.',
                                "position": "'.$gr_style.'"
                              });           
                         });
                      }
                    </script>
                    <!-- END GCR Badge Code -->
                    <!-- BEGIN GCR Language Code -->
                    <script>
                      window.___gcfg = {
                        lang: \''.$lang_code.'\'
                      };
                    </script>
                    <!-- END GCR Language Code -->
                    ';
                return $script_google_reviews;
            }
            return '';
        }

        function get_google_reviews_code($order_info) {
            $gr_status = $this->config->get('google_reviews_status_' . $this->id_store);
            $gr_id = $this->config->get('google_reviews_id_' . $this->id_store);
            $gr_style = $this->config->get('google_reviews_style_' . $this->id_store);
            $gr_days = $this->config->get('google_reviews_delivery_days_' . $this->id_store);

            $gr_lang_supported = array('af', 'ar-AE', 'cs', 'da', 'de', 'en_AU', 'en_GB', 'en_US', 'es', 'es-419', 'fil', 'fr', 'ga', 'id', 'it', 'ja', 'ms', 'nl', 'no', 'pl', 'pt_BR', 'pt_PT', 'ru', 'sv', 'tr', 'zh-CN', 'zh-TW');
            $gr_lang_default_code = $this->config->get('google_reviews_lang_default_code_' . $this->id_store);
            $lang_code = in_array($this->config->get('config_language_id'), $gr_lang_supported) ? $this->config->get('config_language_id') : (!empty($gr_lang_default_code) ? $gr_lang_default_code : 'en_US');

            if ($gr_status) {
                $new_date = date('Y-m-d', strtotime($order_info['date_added'] . ' + ' . $gr_days . ' days'));
                if (!empty($order_info['payment_country_id']) && (!array_key_exists('payment_iso_code_2', $order_info) || (!array_key_exists('payment_iso_code_2', $order_info) && empty($order_info['payment_iso_code_2'])))) {
                    $country_iso_code_2 = $this->db->query('SELECT iso_code_2 FROM ' . DB_PREFIX . 'country WHERE country_id=' . $order_info['payment_country_id']);
                    $country_iso_code_2 = array_key_exists('iso_code_2', $country_iso_code_2->row) ? $country_iso_code_2->row['iso_code_2'] : '';
                } else {
                    $country_iso_code_2 = $order_info['payment_iso_code_2'];
                }

                $product_gtin = $this->config->get('google_reviews_gtin_' . $this->id_store);
                $js_gtin = '';
                if (!empty($product_gtin) && !empty($order_info['products'])) {
                    $js_gtin .= ', "products": [';
                    foreach ($order_info['products'] as $key => $prod) {
                        $info = $this->get_product_data($prod['product_id'], array($product_gtin));
                        $gtin = !empty($info) && array_key_exists($product_gtin, $info) ? $info[$product_gtin] : '';
                        $js_gtin .= '{"gtin":"' . $gtin . '"},';
                    }
                    $js_gtin = rtrim($js_gtin, ',') . ']';
                }


                $script_google_reviews = '
                    <!-- BEGIN GCR Opt-in Module Code -->
                        <script src="https://apis.google.com/js/platform.js?onload=renderOptIn" async defer></script>
                        <script>
                          window.renderOptIn = function() { 
                            window.gapi.load(\'surveyoptin\', function() {
                              window.gapi.surveyoptin.render(
                                {
                                  "merchant_id": ' . $gr_id . ',
                                  "order_id": "' . $order_info['order_id'] . '",
                                  "email": "' . $order_info['email'] . '",
                                  "delivery_country": "' . $country_iso_code_2 . '",
                                  "estimated_delivery_date": "' . $new_date . '",
                                  "opt_in_style": "' . $gr_style . '"' . $js_gtin . '
                                }); 
                             });
                          }
                        </script>
                        <script>
                            window.___gcfg = {
                                lang: \'' . $lang_code . '\'
                            };
                        </script>
                    <!-- END GCR Opt-in Module Code -->
                    ';

                return $script_google_reviews;
            }
            return '';
        }
    }
?>