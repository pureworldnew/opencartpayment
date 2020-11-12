<?php

/*include 'PHPMailer.php';

  #file: catalog/controller/product/product_grouped.php
  #powered by fabiom7 - www.fabiom7.com - fabiome77@hotmail.it - copyright fabiom7 2012 - 2013 - 2014
  #switched: v1.5.4.1 - v1.5.5.1 - v1.5.6
 */
class ControllerProductProductGrouped extends Controller {

    private $error = array();

    public function __construct($registry) {
        $this->registry = $registry;
        $this->load->language('product/product_grouped_mask');
        $this->load->language('product/product_grouped');
        $this->load->language('product/product');
        $this->load->model('catalog/product_grouped');
        $this->load->model('catalog/product');
    }


                            private function _getProductUnits($product_id){
                                $data = $this->model_catalog_product->getUnitDetails($product_id);
                                return $data = $this->model_catalog_product->getUnitDetails($product_id);
                            }                          
                            
                        
    public function index() {
        $this->load->model('setting/setting');
        $this->load->model('tool/image');
        $this->load->model('catalog/product');
        //$this->document->addStyle('catalog/view/javascript/cloudzoom/cloud-zoom.css?v='.rand());	
		//$this->document->addScript('catalog/view/javascript/cloudzoom/cloud-zoom.js?v='.rand());
		$this->document->addStyle('catalog/view/theme/gempack/javascript/jquery/owl-carousel/owl.carousel.css');
		$this->document->addScript('catalog/view/theme/gempack/javascript/jquery/owl-carousel/owl.carousel.min.js');

        $this->document->addStyle('catalog/view/javascript/cloudzoom/cloud-zoom.css');
        $this->document->addScript('catalog/view/javascript/cloudzoom/cloud-zoom.js');
	

        $product_id = $this->request->get['product_id'];
        $main_product_id = $this->request->get['product_id'];
        $data['main_product_id'] = $main_product_id;
        $user_agent = isset($this->request->server['HTTP_USER_AGENT'])?$this->request->server['HTTP_USER_AGENT']:'';

        // Google Ads Schema-Start
        if (isset($this->request->get['gp_product_id'])) {

            $google_schema_data = $this->model_catalog_product->getProduct($this->request->get['gp_product_id']);
            $google_ads_schema_discount_price = $this->getdiscountedprice(1, $this->request->get['gp_product_id']);
			$google_ads_schema_final_price = round($google_ads_schema_discount_price,2);
            $data['google_ads_schema_sku'] = $google_schema_data['sku'];
            $data['google_ads_schema_price'] = $google_ads_schema_final_price;
            $data['google_ads_schema_special'] = round($google_schema_data['special'],2);

            if ($google_schema_data['image']) {
                $data['google_ads_schema_image'] = $this->model_tool_image->resize($google_schema_data['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
            } else {
                $data['google_ads_schema_image'] = '';
            }

        } else {

            $google_schema_data = $this->model_catalog_product->getProduct($product_id);
            $data['google_ads_schema_sku'] = $google_schema_data['sku'];
            $data['google_ads_schema_price'] = round($google_schema_data['price'],2);
            $data['google_ads_schema_special'] = round($google_schema_data['special'],2);

            if ($google_schema_data['image']) {
                $data['google_ads_schema_image'] = $this->model_tool_image->resize($google_schema_data['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
            } else {
                $data['google_ads_schema_image'] = '';
            }

        }
        // Google Ads Schema-End

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
            'separator' => false
        );
        if (!$this->customer->isLogged()) {
            $data['logged'] = FALSE;
        } else {
            $data['logged'] = $this->customer->isLogged();
        }
        $this->load->model('catalog/manufacturer');


        if (isset($this->request->get['manufacturer_id'])) {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_brand'),
                'href' => $this->url->link('product/manufacturer'),
                'separator' => $this->language->get('text_separator')
            );

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id']);

            if ($manufacturer_info) {
                $data['breadcrumbs'][] = array(
                    'text' => $manufacturer_info['name'],
                    'href' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . $url),
                    'separator' => $this->language->get('text_separator')
                );
            }
        }
        
        if (!isset($this->request->get['path'])) {
            
			$product_to_category_path = $this->model_catalog_product->getProductToCategoryPath($product_id);
			if(!empty($product_to_category_path))
			{
				$this->request->get['path'] = $product_to_category_path;
			}	
        }
        
        if (isset($this->request->get['path'])) {
            $this->load->model('catalog/category');
            $path = '';

            $parts = explode('_', (string) $this->request->get['path']);

            $category_id = (int) array_pop($parts);

            foreach ($parts as $path_id) {
                if (!$path) {
                    $path = $path_id;
                } else {
                    $path .= '_' . $path_id;
                }

                $category_info = $this->model_catalog_category->getCategory($path_id);

                if ($category_info) {
                    $data['breadcrumbs'][] = array(
                        'text' => $category_info['name'],
                        'href' => $this->url->link('product/category', 'path=' . $path),
                        'separator' => $this->language->get('text_separator')
                    );
                }
            }

            // Set the last category breadcrumb
            $category_info = $this->model_catalog_category->getCategory($category_id);

            if ($category_info) {
                $url = '';

                if (isset($this->request->get['sort'])) {
                    $url .= '&sort=' . $this->request->get['sort'];
                }

                if (isset($this->request->get['order'])) {
                    $url .= '&order=' . $this->request->get['order'];
                }

                if (isset($this->request->get['page'])) {
                    $url .= '&page=' . $this->request->get['page'];
                }

                if (isset($this->request->get['limit'])) {
                    $url .= '&limit=' . $this->request->get['limit'];
                }

                $data['breadcrumbs'][] = array(
                    'text' => $category_info['name'],
                    'href' => $this->url->link('product/category', 'path=' . $this->request->get['path']),
                    'separator' => $this->language->get('text_separator')
                );
            }
        }

        ////////

        $data['option_out_of_stock'] = $this->language->get('option_out_of_stock');
        $gp_tpl_q = $this->model_catalog_product_grouped->getProductGroupedType($product_id);
        if (!$gp_tpl_q) {
            //for error_text : product not found
            $product_info = false; //ok, go to: template/error/not_found.tpl
        } elseif ($gp_tpl_q && $gp_tpl_q['pg_type'] != 'grouped') {
            $this->response->redirect($this->url->link('product/product_' . $gp_tpl_q['pg_type'], '&product_id=' . $product_id));
        }
        $data['text_groupby'] = !empty($gp_tpl_q['pg_groupby']) ? $gp_tpl_q['pg_groupby'] : $this->model_catalog_product_grouped->getProductGroupedByName($product_id);
        $product_grouped = array();
        $product_grouped_name = $this->model_catalog_product->getGroupedProductName($product_id);

		$gruppi = $this->model_catalog_product_grouped->getGrouped($product_id);
        if (!empty($gruppi)) {
            $group_indicator_id = $this->model_catalog_product_grouped->getGroupIndicator($main_product_id);
            $data['group_indicator_id'] = $group_indicator_id;
            /**
             * REQUESTED PRODUCT CODE
             */
            if (isset($this->request->get['gp_product_id'])) {
                $group_product_id = $this->request->get['gp_product_id'];
                //check if requested "gp_product_id" is of same group
                $requested_product_data = $this->model_catalog_product_grouped->getGroupedData($this->request->get['gp_product_id'], $group_indicator_id);
                //if requested "gp_product_id" is not of same group then unset get variable
                if ($requested_product_data === FALSE) {
                    unset($this->request->get['gp_product_id']);
                }
            } else {
                $group_product_id = 0; 
            }
            /**
             * REQUESTED PRODUCT CODE
             */
            $requested_product_id = $gruppi[0]['grouped_id'];
            $total_options_product = 0;
            foreach ($gruppi as $groups) { 
                $total_options_product += $this->model_catalog_product->getGroupedtotalProducts($groups['grouped_id']);
                $product_name = $this->model_catalog_product->getGroupedProductName($groups['grouped_id']);
                $p_option_names = $this->model_catalog_product->getProductOptionName($groups['grouped_id']);
                $name = str_replace($product_grouped_name, '', $product_name);
                if( !empty($p_option_names) )
                {
                    foreach($p_option_names as $p_option_name)
                    {
                        $name = str_replace(" " . $p_option_name, '', $name);
                    }
                }

                //REQUESTED PRODUCT CODE

                $requested_product = FALSE;

                if (isset($this->request->get['gp_product_id']) && trim($name) == $requested_product_data['groupbyvalue']) {
                    $requested_product = TRUE;
                    $requested_product_id = $groups['grouped_id'];
                }

                //REQUESTED PRODUCT CODE END

                $product_grouped[] = array(
                    'product_id' => $groups['grouped_id'],
                    'product_name' => $name,
                    'is_requested_product' => $requested_product //REQUESTED PRODUCT CODE
                );
            }
			$data['total_options_product'] = $total_options_product;
			/*usort($product_grouped, function($a, $b) {
				return strnatcmp($a['product_name'], $b['product_name']);
			});*/

            $product_id = $requested_product_id;
            $product_info = $this->model_catalog_product->getProduct($requested_product_id);
            $new_grouping_system = $this->model_catalog_product_grouped->getNewGroupingSystemStatus($requested_product_id);
            if ($product_info) {
                $url = '';

                if (isset($this->request->get['path'])) {
                    $url .= '&path=' . $this->request->get['path'];
                }

                if (isset($this->request->get['filter'])) {
                    $url .= '&filter=' . $this->request->get['filter'];
                }

                if (isset($this->request->get['manufacturer_id'])) {
                    $url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
                }

                if (isset($this->request->get['search'])) {
                    $url .= '&search=' . $this->request->get['search'];
                }

                if (isset($this->request->get['tag'])) {
                    $url .= '&tag=' . $this->request->get['tag'];
                }

                if (isset($this->request->get['description'])) {
                    $url .= '&description=' . $this->request->get['description'];
                }

                if (isset($this->request->get['category_id'])) {
                    $url .= '&category_id=' . $this->request->get['category_id'];
                }

                if (isset($this->request->get['sub_category'])) {
                    $url .= '&sub_category=' . $this->request->get['sub_category'];
                }

                if (isset($this->request->get['sort'])) {
                    $url .= '&sort=' . $this->request->get['sort'];
                }

                if (isset($this->request->get['order'])) {
                    $url .= '&order=' . $this->request->get['order'];
                }

                if (isset($this->request->get['page'])) {
                    $url .= '&page=' . $this->request->get['page'];
                }

                if (isset($this->request->get['limit'])) {
                    $url .= '&limit=' . $this->request->get['limit'];
                }

                if(!$this->isMobile($user_agent) && !$this->isTablet($user_agent)){
                    $data['breadcrumbs'][] = array(
                        'text' => $product_info['name'],
                        'href' => $this->url->link('product/product', $url . '&product_id=' . $this->request->get['product_id']),
                        'separator' => $this->language->get('text_separator')
                    );
                }

                $this->document->setTitle($product_info['name']);
                $this->document->setDescription($product_info['meta_description']);
                $this->document->setKeywords($product_info['meta_keyword']);
                //$this->document->addLink($this->url->link('product/product', 'product_id=' . $product_id), 'canonical');
                
                $this->document->addScript('catalog/view/javascript/jquery/tabs.js');
                $this->document->addScript('catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js');
                $this->document->addStyle('catalog/view/javascript/jquery/colorbox/colorbox.css');
                if($this->config->get('config_template') == 'gempack')
                {
                    if(isset($new_grouping_system) && $new_grouping_system  == 1) {
                        $this->document->addScript('catalog/view/javascript/group.product.page.dev.v2.js?_='.rand(100000,getrandmax())); 
                    }else{
                        $this->document->addScript('catalog/view/javascript/group.product.page.v2.js?_='.rand(100000,getrandmax())); 
                    }
                    $this->document->addScript('catalog/view/javascript/group.product.fix.js?_='.rand(100000,getrandmax()));  
                } else {
                    $this->document->addScript('catalog/view/javascript/group.product.page.js');
                }
                $this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
                $this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
                $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
                $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
                $this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

                $data['heading_title'] = $product_info['name'];
				$text_select = $this->language->get('text_select');
                $data['text_select'] = $this->language->get('text_select');
                $data['text_manufacturer'] = $this->language->get('text_manufacturer');
                $data['text_model'] = $this->language->get('text_model');
                $data['text_reward'] = $this->language->get('text_reward');
                $data['text_points'] = $this->language->get('text_points');
                $data['text_price_login'] = $this->language->get('text_price_login');
                $data['text_wholesale_login'] = $this->language->get('text_wholesale_login');
                $data['text_discount'] = $this->language->get('text_discount');
                $data['text_stock'] = $this->language->get('text_stock');
                $data['text_price'] = $this->language->get('text_price');
                $data['text_tax'] = $this->language->get('text_tax');
                $data['text_discount'] = $this->language->get('text_discount');
                $data['text_option'] = $this->language->get('text_option');
                $data['text_qty'] = $this->language->get('text_qty');
                $data['text_minimum'] = sprintf($this->language->get('text_minimum'), $product_info['minimum']);
                $data['text_or'] = $this->language->get('text_or');
                $data['text_write'] = $this->language->get('text_write');
                $data['text_note'] = $this->language->get('text_note');
                $data['text_share'] = $this->language->get('text_share');
                $data['text_wait'] = $this->language->get('text_wait');
                $data['text_tags'] = $this->language->get('text_tags');

                $data['entry_name'] = $this->language->get('entry_name');
                $data['entry_review'] = $this->language->get('entry_review');
                $data['entry_rating'] = $this->language->get('entry_rating');
                $data['entry_good'] = $this->language->get('entry_good');
                $data['entry_bad'] = $this->language->get('entry_bad');
//            $data['entry_captcha'] = $this->language->get('entry_captcha');

                $data['button_cart'] = $this->language->get('button_cart');
                $data['button_wishlist'] = $this->language->get('button_wishlist');
                $data['button_compare'] = $this->language->get('button_compare');
                $data['button_upload'] = $this->language->get('button_upload');
                $data['button_continue'] = $this->language->get('button_continue');

                $this->load->model('catalog/review');

                $data['tab_description'] = $this->language->get('tab_description');
                $data['tab_attribute'] = $this->language->get('tab_attribute');
                $review_count = $this->model_catalog_product->getGroupProductReviewCount($product_info['product_id']);
                $data['tab_review'] = sprintf($this->language->get('tab_review'), $review_count);
                $data['tab_related'] = $this->language->get('tab_related');
//                $data['tab_qa']

                $data['product_id'] = $requested_product_id;
                $data['group_products_href'] = $this->url->link('product/product_grouped/group&pid='. $main_product_id, 'group_id=' . $group_indicator_id);
				
            $data['product_articles'] = (array)$this->model_catalog_product->getProductArticles($requested_product_id);
			$data['embeded_product_article']=false;
                foreach ($data['product_articles'] as $key => $article) {
                    if (isset($article['description'])) {
                        $data['embeded_product_article'][] = array(
                            'title' => html_entity_decode($article['title'], ENT_QUOTES, 'UTF-8'),
                            'description' => html_entity_decode($article['description'], ENT_QUOTES, 'UTF-8'),
                        );
                        unset($data['product_articles'][$key]);
                    }
                }
 $data['product_articles'] = array_values(array_filter($data['product_articles']));
			
                $data['manufacturer'] = $product_info['manufacturer'];
                $data['manufacturers'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_info['manufacturer_id']);
                $data['model'] = $product_info['model'];
                $data['reward'] = $product_info['reward'];
                $data['points'] = $product_info['points'];
                $data['date_available'] = $product_info['date_available'];
				$data['frontend_date_available'] = $product_info['frontend_date_available'];
$this->load->model('catalog/qa');
                  $data['text_ask'] = $this->language->get('text_ask');
$data['entry_question'] = $this->language->get('entry_question');
                
            $data['entry_email'] = $this->language->get('entry_email');
   $data['tab_qa'] = sprintf($this->language->get('tab_qa'), $this->model_catalog_qa->getTotalQAsByProductId($this->request->get['product_id']));
 $data['qa_notify'] = $this->config->get("qa_question_reply_notification");
            $data['qa_name'] = ($this->customer->isLogged()) ? $this->customer->getFirstName() : "";
            $data['qa_email'] = ($this->customer->isLogged()) ? $this->customer->getEmail() : "";
            $data['preload'] = intval($this->config->get("qa_preload"));
            if ($data['preload'] == 1)
                $data['qas'] =  $this->question($product_id, 1, false);
            else if ($data['preload'] == 2)
                $data['qas'] =  $this->question($product_id, 1, false, 0);
            else
                $data['qas'] =  '';

                        $data['qa_status'] = 1; //$this->config->get('qa_status');
			$data['quick_config_image_thumb_width'] = $this->config->get('config_image_thumb_width');

			$data['quick_h2'] = $this->config->get('quick_h2');

			$data['quick_description_left'] = $this->config->get('quick_description_left');
			$data['quick_description_right'] = $this->config->get('quick_description_right');
			$data['quick_general_bottom_description'] = $this->config->get('quick_general_bottom_description');

			$data['quick_name_general'] = 'Product';//$this->config->get('quick_name_general');
			$data['quick_tab_description'] = $this->config->get('quick_tab_description');
			$data['quick_tab_attribute'] = $this->config->get('quick_tab_attribute');
			$data['quick_tab_review'] = $this->config->get('quick_tab_review');
			$data['quick_tab_related'] = $this->config->get('quick_tab_related');
			$data['quick_tab_review'] = $this->config->get('quick_tab_review');
			$data['quick_tab_related'] = $this->config->get('quick_tab_related');


			$data['quick_posleft1'] = 'image';//$this->config->get('quick_posleft1');
			$data['quick_posleft2'] = 'thumb';//$this->config->get('quick_posleft2');
			$data['quick_posleft3'] = $this->config->get('quick_posleft3');
			$data['quick_posleft4'] = $this->config->get('quick_posleft4');
			$data['quick_posleft5'] = $this->config->get('quick_posleft5');
			$data['quick_posleft6'] = $this->config->get('quick_posleft6');

			$data['quick_posright1'] = 'description';//$this->config->get('quick_posright1');
			$data['quick_posright2'] = 'options';//$this->config->get('quick_posright2');
			$data['quick_posright3'] = 'price';//$this->config->get('quick_posright3');
			$data['quick_posright4'] = $this->config->get('quick_posright4');
			$data['quick_posright5'] = 'cart';//$this->config->get('quick_posright5');
			$data['quick_posright6'] = $this->config->get('quick_posright6');
			
			$data['product_url'] = $this->url->link('product/product', $url . '&product_id=' . $this->request->get['product_id']);

			$data['quick_column_left_width'] = $this->config->get('quick_column_left_width');
			$data['quick_column_right_width'] = $this->config->get('quick_column_right_width');

			if ($this->config->get('quick_des')) {
				$data['quick_ck_des'] = html_entity_decode($this->config->get('quick_ck_des'), ENT_QUOTES, 'UTF-8');
			} else {
				$data['quick_ck_des'] = false;
			}

			if ($this->config->get('quick_des_bottom')) {
				$data['quick_ck_des_bottom'] = html_entity_decode($this->config->get('quick_ck_des_bottom'), ENT_QUOTES, 'UTF-8');
			} else {
				$data['quick_ck_des_bottom'] = false;
			}

                /** quick view code from plugin ** */
                $data['quick_config_image_thumb_width'] = $this->config->get('config_image_thumb_width');

                $data['quick_h2'] = $this->config->get('quick_h2');

                $data['quick_description_left'] = $this->config->get('quick_description_left');
                $data['quick_description_right'] = $this->config->get('quick_description_right');
                $data['quick_general_bottom_description'] = $this->config->get('quick_general_bottom_description');

              /*  $data['quick_name_general'] = $this->config->get('quick_name_general');
                $data['quick_tab_description'] = $this->config->get('quick_tab_description');
                $data['quick_tab_attribute'] = $this->config->get('quick_tab_attribute');
                $data['quick_tab_review'] = $this->config->get('quick_tab_review');
                $data['quick_tab_related'] = $this->config->get('quick_tab_related');
                $data['quick_tab_review'] = $this->config->get('quick_tab_review');
                $data['quick_tab_related'] = $this->config->get('quick_tab_related');


                $data['quick_posleft1'] = $this->config->get('quick_posleft1');
                $data['quick_posleft2'] = $this->config->get('quick_posleft2');
                $data['quick_posleft3'] = $this->config->get('quick_posleft3');
                $data['quick_posleft4'] = $this->config->get('quick_posleft4');
                $data['quick_posleft5'] = $this->config->get('quick_posleft5');
                $data['quick_posleft6'] = $this->config->get('quick_posleft6');*/

               /* $data['quick_posright1'] = $this->config->get('quick_posright1');
                $data['quick_posright2'] = $this->config->get('quick_posright2');
                $data['quick_posright3'] = $this->config->get('quick_posright3');
                $data['quick_posright4'] = $this->config->get('quick_posright4');
                $data['quick_posright5'] = $this->config->get('quick_posright5');
                $data['quick_posright6'] = $this->config->get('quick_posright6');*/

                $data['quick_column_left_width'] = $this->config->get('quick_column_left_width');
                $data['quick_column_right_width'] = $this->config->get('quick_column_right_width');

                if (isset($product_info['unit_singular'])) {
                    $data['unit_singular'] = $product_info['unit_singular'];
                } else {
                    $data['unit_singular'] = '';
                }
                if (isset($product_info['unit_plural'])) {
                    $data['unit_plural'] = $product_info['unit_plural'];
                } else {
                    $data['unit_plural'] = '';
                }
                if ($this->config->get('quick_des')) {
                    $data['quick_ck_des'] = html_entity_decode($this->config->get('quick_ck_des'), ENT_QUOTES, 'UTF-8');
                } else {
                    $data['quick_ck_des'] = false;
                }

                if ($this->config->get('quick_des_bottom')) {
                    $data['quick_ck_des_bottom'] = html_entity_decode($this->config->get('quick_ck_des_bottom'), ENT_QUOTES, 'UTF-8');
                } else {
                    $data['quick_ck_des_bottom'] = false;
                }
                /*                 * **quick view code plugin ends ***** */

                $data['sku'] = $product_info['sku'];

                $data['upc_ref'] = $product_info['upc'];
                $data['quantity'] = $product_info['quantity'];
                
				$data['date_available'] = $product_info['date_available']; 
				$data['frontend_date_available'] = $product_info['frontend_date_available']; 
                $data['date_sold_out'] = $product_info['date_sold_out'];
                $data['date_ordered'] = $product_info['date_ordered'];
                $data['estimate_deliver_time'] = $product_info['estimate_deliver_time'];
                $data['estimate_deliver_days'] = $product_info['estimate_deliver_days'];
                //echo '<pre>';print_r($product_info);exit;

                if ($product_info['quantity'] <= 0) {
                    $data['stock'] = $product_info['stock_status'];
                } elseif ($this->config->get('config_stock_display')) {
                    $data['stock'] = $product_info['quantity'];
                } else {
                    $data['stock'] = $this->language->get('text_instock');
                }

				if ($product_info['image']) {
					$data['quick_thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
				} else {
					$data['quick_thumb'] = '';
				}
                /*                 * **** quick view plugin code ********** */
                if ($product_info['image']) {
                    $data['quick_thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
                } else {
                    $data['quick_thumb'] = '';
                }
                /*                 * **** quick view plugin code end ********** */
                
                $data['fimage'] = false;

                if ($product_info['image']) {
					$data['fimage'] = ($this->request->server['HTTPS'])? HTTPS_SERVER . 'image/'.$product_info['image']:HTTP_SERVER . 'image/'.$product_info['image'];
                    $data['popup'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
                } else {
                    $data['popup'] = '';
                }

                if ($product_info['image']) {
                    $data['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
                } else {
                    $data['thumb'] = '';
                }

     if ($product_info['date_added']) {
                $date = $product_info['date_added'];
            } else {
                $date = "";
            }
            
            $data['show_product_label_1'] = $product_info['show_product_label_1']; 
            $data['product_label_text_1'] = $product_info['product_label_text_1'];

            $data['show_product_label_2'] = $product_info['show_product_label_2']; 
            $data['product_label_text_2'] = $product_info['product_label_text_2']; 
            
             //out of stock
            if ($product_info['quantity']) {
                $stock_quantity = $product_info['quantity'];
            } else {
                $stock_quantity = 0;
            }
            $this->load->model('localisation/products_label');
            $label_info = $this->model_localisation_products_label->getLabels();

            foreach($label_info as $labelget){
                $data['labels'][]=array(
                    'label_id'=> $labelget['label_id'],
                    'label_text'=> $labelget['label_text'],
                    'label_color'=> $labelget['label_color'],
                    'label_text_color'=> $labelget['label_text_color'],
                    'condition_type'=> $labelget['condition_type'],
                    'status'=> $labelget['status']

                );
                if($labelget['label_id']==1) {
                    $data['new_product_db_days']= $labelget['condition_type'];
                    $data['new_product_db_text']= $labelget['label_text'];
                    $data['new_product_db_text_color']= $labelget['label_text_color'];
                    $data['new_product_db_label_color']= $labelget['label_color'];
                    $data['new_product_status']= $labelget['status'];
                    $data['position_new']=$labelget['position'];

                }
                if($labelget['label_id']==2) {
                    $data['discount_product_db_percent']= $labelget['condition_type'];
                    $data['discount_product_db_text']= $labelget['label_text'];
                    $data['discount_product_db_text_color']= $labelget['label_text_color'];
                    $data['discount_product_db_label_color']= $labelget['label_color'];
                    $data['discount_product_status']= $labelget['status'];
                    $data['position_discount']= $labelget['position'];

                }
                if($labelget['label_id']==3) {
                    $data['shipping_product_db_text']= $labelget['label_text'];
                    $data['shipping_product_db_text_color']= $labelget['label_text_color'];
                    $data['shipping_product_db_label_color']= $labelget['label_color'];
                    $data['custom_product_label_2_status']= $labelget['status'];
                    $data['position_shipping']= $labelget['position'];

                }
                if($labelget['label_id']==4) {
                    $data['outofstock_product_db_text']= $labelget['label_text'];
                    $data['outofstock_product_db_text_color']= $labelget['label_text_color'];
                    $data['outofstock_product_db_label_color']= $labelget['label_color'];
                    $data['outofstock_product_status']= $labelget['status'];
                    $data['position_outofstock']= $labelget['position'];
                }
                if($labelget['label_id']==5) {
                    $data['custom_product_db_text']= $labelget['label_text'];
                    $data['custom_product_db_text_color']= $labelget['label_text_color'];
                    $data['custom_product_db_label_color']= $labelget['label_color'];
                    $data['custom_product_label_1_status']= $labelget['status'];
                    $data['position_custom']= $labelget['position'];
                }
            }
if ((float)$product_info['special']) {
                $special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
            } else {
                $special = false;
            }
//take flat shipping rate in shipping_method.php ->controller
              $data['shipping_charge']= $this->config->get('flat_cost');
              $data['install_status'] = $this->config->get('product_label_install_status');

                $data['images'] = array();
				

                $results = $this->model_catalog_product->getProductImages($requested_product_id);
                if(empty($results) && $group_product_id > 0){
                    $results = $this->model_catalog_product->getProductImages($group_product_id);
                }

                foreach ($results as $result) {
                    $data['images'][] = array(
 'percent'   => sprintf($this->language->get('text_percent'), (round((($product_info['price'] -  $product_info['special'])/$product_info['price'])*100 ,0))),
                    'percent_value' => (round((($product_info['price'] -  $product_info['special'])/$product_info['price'])*100 ,0)),
                     'date'=>$date,
                    'special'     => $special,
                    'stockquantity'=>$stock_quantity,

	      'quick_thumb' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height')),
	      
                        'popup' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
                        'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'))
                    );
                }
                $data['images'][] = array(
 'percent'   => sprintf($this->language->get('text_percent'), (round((($product_info['price'] -  $product_info['special'])/$product_info['price'])*100 ,0))),
                    'percent_value' => (round((($product_info['price'] -  $product_info['special'])/$product_info['price'])*100 ,0)),
                     'date'=>$date,
                    'special'     => $special,
                    'stockquantity'=>$stock_quantity,
                    'popup' => $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
                    'thumb' => $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'))
                );
//             pr($data['images']);
                $data['images'] = array_reverse($data['images']);
				
                //$data['videos'] = array();
                $data['videos'] = array();
                $files = array();
                //$data['video_type'] = false;
                if (!empty($product_info['video'])) {
					$files = array();
					$vd = $product_info['video'];
					$vd = explode(",",$vd);
					foreach ($vd as $vf){
						$v_file = urldecode($vf);
					
                 
                 //now check for link from file
                 if (strpos($v_file,'http') !== FALSE) {
					 $files[] = array('file_type'=>'youtube','video'=>str_replace('watch?v=','embed/',$v_file));
                     //$data['file_type'] = 'youtube';
                     //$data['video']  = str_replace('watch?v=','embed/',$v_file);
                 }else{
                     $file_type = 'local';
                     $video_file = DIR_IMAGE . $v_file;
                     if (file_exists($video_file)){
                        $formats = array('m4v'=>'mp4','m4a' => 'mp4');
                        $video  = HTTPS_SERVER."image/" . $v_file;
                        $ext = pathinfo($video_file, PATHINFO_EXTENSION);
                        $v_type = isset($formats[$ext])?$formats[$ext]:$ext;
                        $video_type = 'video/' . $v_type;
                     }else{
                        $video = false;
                        $video_type = false;
                     }
                     $files[] = array('file_type'=>$file_type,'video_type'=>$video_type,'video'=>$video);
                 }
				} //foreach ends
              }
				 $data['video'] = $files;
				 //echo '<pre>';print_r($files);
				 //die();
                /*
                 * Calculates Discount percent
                 * @param discounted price
                 * @param product original price
                 * returns $discount_percent
                 */
                if ($product_info['discounted_price']) {
                    $discount_percent = $this->cart->calcMetalTypeDiscount($product_info['discounted_price'], $product_info['orignial_price']);
                } else {
                    $discount_percent = 1;
                }

                $data['old_price'] = $product_info['orignial_price'];

                $data['options'] = array();

                $data['unit_conversion_help'] = $this->model_catalog_product->getConversionHelp($product_info['product_id']);
                $formula = FALSE;

                $data['base_price'] = $product_info['orignial_price'];

                            $data['unit_datas'] = $this->_getProductUnits($product_info['product_id']);
                            
                        

                foreach ($this->model_catalog_product->getProductOptions($requested_product_id) as $option) {
                    if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') {
                        $option_value_data = array();
                        $size = 0;
                        //REQUESTED PRODUCT CODE
                        $is_requested_option = FALSE;
                        if (isset($this->request->get['gp_product_id']) && $requested_product_data['option_count'] > 0) {
                            $requested_option_exists = array_search(trim($option['name']), array_reverse($requested_product_data)); 
                            if ($requested_option_exists !== FALSE) {
                                $is_requested_option = TRUE;
                            }
                        }
                        //REQUESTED PRODUCT CODE ENDS
                        foreach ($option['option_value'] as $option_value) {
                            //REQUESTED PRODUCT CODE
                            $is_requested_option_value = FALSE;
                            if ($is_requested_option === TRUE && $option_value['name'] == $requested_product_data['optionvalue' . filter_var($requested_option_exists, FILTER_SANITIZE_NUMBER_INT)]) {
                                $is_requested_option_value = TRUE;
                            }
                            //REQUESTED PRODUCT CODE ENDS
                            if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
                                $option_unformated_price = $this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
                                if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float) $option_value['price']) {
                                    $price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                                } else {
                                    $price = false;
                                }



                                $option_value_data[] = array(
                                    'product_option_value_id' => $option_value['product_option_value_id'],
                                    'quantity' => $option_value['quantity'],
                                    'option_value_id' => $option_value['option_value_id'],
                                    'name' => $option_value['name'],
                                    'image' => $this->model_tool_image->resize($option_value['image'], 50, 50),
                                    'price' => $price,
                                    'price2' => $option_unformated_price,
                                    'price_prefix' => $option_value['price_prefix'],
                                    'is_requested_option_value' => $is_requested_option_value//REQUESTED PRODUCT CODE
                                );
                            }
                        }

                        $data['options'][] = array(
                            'product_option_id' => $option['product_option_id'],
                            'option_id' => $option['option_id'],
                            'metal_type' => $size,
                            'name' => $option['name'],
                            'type' => $option['type'],
                            'option_value' => $option_value_data,
                            'required' => $option['required']
                        );
                    } elseif ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {

                        $data['options'][] = array(
                            'product_option_id' => $option['product_option_id'],
                            'option_id' => $option['option_id'],
                            'name' => $option['name'],
                            'type' => $option['type'],
                            'option_value' => $option['option_value'],
                            'required' => $option['required']
                        );
                    }
                }
                if (!$formula) {
                    $product_info['orignial_price'] = $product_info['price'];
                } if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $data['price'] = $this->currency->format($this->tax->calculate($this->cart->geProductCalculatedPrice($product_info['product_id']), $product_info['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $data['price'] = false;
                }
                $data['contact'] = $this->url->link('information/contact');
                $data['price_without_discount'] = $this->currency->format($product_info['price']);

                if ((float) $product_info['special']) {
                    $data['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $data['special'] = false;
                }

                if ($this->config->get('config_tax')) {
                    $data['tax'] = $this->currency->format((float) $product_info['special'] ? $product_info['special'] : $product_info['orignial_price']);
                } else {
                    $data['tax'] = false;
                }

                $data['discounts'] = $this->model_catalog_product->getProductDiscounts($requested_product_id);

                if ($product_info['minimum']) {
                    $data['minimum'] = $product_info['minimum'];
                } else {
                    $data['minimum'] = 1;
                }

                $data['review_status'] = $this->config->get('config_review_status');
                $data['reviews'] = ($review_count > 0) ? sprintf($this->language->get('text_reviews'), (int) $review_count) : "Be the first to review!";
                $data['rating'] = (int) $product_info['rating'];
                $data['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');
                $data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($requested_product_id);

                $data['products'] = array();
                
                if ($group_product_id == 0) {
                    $this->load->model('catalog/seo_url');
                    $_0xMD = $product_info['model'];
                    $_5QL = "SELECT * FROM `".DB_PREFIX."product` WHERE `model` LIKE '$_0xMD'";
                    
                    $_R3S = $this->db->query($_5QL);
                    //print_r($_R3S);
                    if ($_R3S->num_rows) {
                        $_0xMD = $_R3S->row['product_id'];
                        $group_product_id = $_0xMD;
                    }

                    //echo $group_product_id;
                    //echo $requested_product_id;
                    //$a = $this->model_catalog_seo_url->getproductIdByProductId("9407");
                    //var_dump($a);

                }
                
                //echo $gp_product_id;
                //die();
                $results = $this->model_catalog_product->getProductRelated($group_product_id,0);
                //print_r($results);
                foreach ($results as $result) {
                    if ($result['image']) {
                        $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
                    } else {
                        $image = false;
                    }


                    if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                        $price = $this->currency->format($this->tax->calculate($this->cart->geProductCalculatedPrice($result['product_id']), $result['tax_class_id'], $this->config->get('config_tax')));
                    } else {
                        $price = false;
                    }

                    if ((float) $result['special']) {
                        $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
                    } else {
                        $special = false;
                    }

                    if ($this->config->get('config_review_status')) {
                        $rating = (int) $result['rating'];
                    } else {
                        $rating = false;
                    }
                    $data['products'][] = array(
 'percent'   => sprintf($this->language->get('text_percent'), (round((($result['price'] -  $result['special'])/$result['price'])*100 ,0))),
                    'percent_value' => (round((($result['price'] -  $result['special'])/$result['price'])*100 ,0)),
                    'date'=>$date,
                    'stockquantity'=>$stock_quantity,
                        'product_id' => $result['product_id'],
                        'thumb' => $image,
                        'name' => $result['name'],
                        'price' => $price,
                        'unit'  => $result['unit_singular'],
                        'special' => $special,
                        'rating' => $rating,
                        'reviews' => sprintf($this->language->get('text_reviews'), (int) $result['reviews']),
                        'href' => $this->url->link('product/product', 'product_id=' . $result['product_id'])
                    );
                }

            //echo "<pre>";print_r($data['products']);echo "</pre>";
            $data['wproducts'] = array();

			$results= $this->model_catalog_product->getProductRelated($group_product_id,1);
            //echo "<pre>";print_r($results);echo "</pre>";

			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}
				
				if ($price && $result['model'] == 'grouped' && !(float)$result['pgprice_from']) {
					$price = (!$special ? $this->language->get('text_price_start') : $this->language->get('text_price_start_special')) . ' ' . $price;
				} elseif ($price && $result['model'] == 'grouped' && (float)$result['pgprice_from']) {
					$price = $this->language->get('text_price_from') . $this->currency->format($this->tax->calculate($result['pgprice_from'], $result['tax_class_id'], $this->config->get('config_tax')));
					if ((float)$result['pgprice_to']) {
						$price .= $this->language->get('text_price_to') . $this->currency->format($this->tax->calculate($result['pgprice_to'], $result['tax_class_id'], $this->config->get('config_tax')));
					}
				}

				//$this->document->addScript('catalog/view/javascript/responsiveCarousel.js');

				$data['wproducts'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'unit'		  => $result['unit_singular'],
					'tax'         => $tax,
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $rating,
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
            }
            
            //echo "<pre>";print_r($data['wproducts']);echo "</pre>";

      $this->load->model('localisation/products_label');
            $label_info = $this->model_localisation_products_label->getLabels();

            foreach($label_info as $labelget){
                $data['labels'][]=array(
                    'label_id'=> $labelget['label_id'],
                    'label_text'=> $labelget['label_text'],
                    'label_color'=> $labelget['label_color'],
                    'label_text_color'=> $labelget['label_text_color'],
                    'condition_type'=> $labelget['condition_type'],
                    'status'=> $labelget['status']

                );
                if($labelget['label_id']==1) {
                    $data['new_product_db_days']= $labelget['condition_type'];
                    $data['new_product_db_text']= $labelget['label_text'];
                    $data['new_product_db_text_color']= $labelget['label_text_color'];
                    $data['new_product_db_label_color']= $labelget['label_color'];
                    $data['new_product_status']= $labelget['status'];
                    $data['position_new']=$labelget['position'];

                }
                if($labelget['label_id']==2) {
                    $data['discount_product_db_percent']= $labelget['condition_type'];
                    $data['discount_product_db_text']= $labelget['label_text'];
                    $data['discount_product_db_text_color']= $labelget['label_text_color'];
                    $data['discount_product_db_label_color']= $labelget['label_color'];
                    $data['discount_product_status']= $labelget['status'];
                    $data['position_discount']= $labelget['position'];

                }
                if($labelget['label_id']==3) {
                    $data['shipping_product_db_text']= $labelget['label_text'];
                    $data['shipping_product_db_text_color']= $labelget['label_text_color'];
                    $data['shipping_product_db_label_color']= $labelget['label_color'];
                    $data['custom_product_label_2_status']= $labelget['status'];
                    $data['position_shipping']= $labelget['position'];

                }
                if($labelget['label_id']==4) {
                    $data['outofstock_product_db_text']= $labelget['label_text'];
                    $data['outofstock_product_db_text_color']= $labelget['label_text_color'];
                    $data['outofstock_product_db_label_color']= $labelget['label_color'];
                    $data['outofstock_product_status']= $labelget['status'];
                    $data['position_outofstock']= $labelget['position'];
                }
                if($labelget['label_id']==5) {
                    $data['custom_product_db_text']= $labelget['label_text'];
                    $data['custom_product_db_text_color']= $labelget['label_text_color'];
                    $data['custom_product_db_label_color']= $labelget['label_color'];
                    $data['custom_product_label_1_status']= $labelget['status'];
                    $data['position_custom']= $labelget['position'];
                }
            }

//take flat shipping rate in shipping_method.php ->controller
              $data['shipping_charge']= $this->config->get('flat_cost');

              $data['install_status'] = $this->config->get('product_label_install_status');
                $data['tags'] = array();

                if ($product_info['tag']) {
                    $tags = explode(',', $product_info['tag']);
                    foreach ($tags as $tag) {
                        if ($tag == 'weight_chains') {
                            $data['weight_chains'] = TRUE;
                        }
                        if ($tag == 'length_chains') {
                            $data['length_chains'] = TRUE;
                        }
                        $data['tags'][] = array(
                            'tag' => trim($tag),
                            'href' => $this->url->link('product/search', 'tag=' . trim($tag))
                        );
                    }
                }

                $data['text_payment_profile'] = $this->language->get('text_payment_profile');
                $data['profiles'] = $this->model_catalog_product->getProfiles($product_info['product_id']);


                        if($data['logged']){
                            if(isset($data['discounts'][0]['price'])){
                                $data['price'] = $this->currency->format($data['discounts'][0]['price']);    
                            }
                        }                         
                        
                    
                $this->model_catalog_product->updateViewed($this->request->get['product_id']);



                $data['gp_column_name'] = $this->language->get('gp_column_name');
                if (isset($gp_column_option)) {
                    $data['gp_column_option'] = $this->language->get('gp_column_option');
                } else {
                    $data['gp_column_option'] = '0';
                }
                $data['gp_column_price'] = $this->language->get('gp_column_price');
                $data['gp_column_qty'] = $this->language->get('gp_column_qty');

                $data['product_grouped'] = $product_grouped;

                $data['price'] = $this->currency->format2d($data['price']);
/*
				if(isset($_REQUEST['view'])) {
					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/qv/quickview.tpl')) {
						$this->template = $this->config->get('config_template') . '/template/qv/quickview.tpl';
					} else {
						$this->template = 'default/template/product/not_found.tpl';
					}
               }else {
					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/product_grouped_' . $gp_tpl_q['pg_layout'] . '.tpl')) {
						$this->template = $this->config->get('config_template') . '/template/product/product_grouped_' . $gp_tpl_q['pg_layout'] . '.tpl';
					} else {}
						$this->template = 'default/template/product/product_grouped_' . $gp_tpl_q['pg_layout'] . '.tpl';
					}
				}*/
				//echo $this->template;
                $total_qas = $this->model_catalog_product->getGroupProductQACount($product_id);
                $data['tab_qa'] = "Q & A(" . $total_qas . ")";
// E 5/7 //
//            


                // Captcha
                if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
                    $data['captcha'] = $this->load->controller('captcha/' . $this->config->get('config_captcha'), $this->error);
                } else {
                    $data['captcha'] = '';
                }

				$data['column_left'] = $this->load->controller('common/column_left');
				$data['column_right'] = $this->load->controller('common/column_right');
				$data['content_top'] = $this->load->controller('common/content_top');
				$data['content_bottom'] = $this->load->controller('common/content_bottom');
				$data['footer'] = $this->load->controller('common/footer');
				$data['header'] = $this->load->controller('common/header');
				if(isset($_REQUEST['view'])) {
					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/qv/quickview.tpl')) {
						$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/qv/quickview.tpl', $data));
					} else {
						$this->response->setOutput($this->load->view('default/template/product/not_found.tpl', $data));
					}
               }else {
                    if(isset($new_grouping_system) && $new_grouping_system  == 1) {
                        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/product_grouped_dev.tpl', $data));
                    } else {
                        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/product_grouped_' . $gp_tpl_q['pg_layout'] . '.tpl')) {
                            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/product_grouped_' . $gp_tpl_q['pg_layout'] . '.tpl', $data));
                        } else {
                            $this->response->setOutput($this->load->view('default/template/product/product_grouped_' . $gp_tpl_q['pg_layout'] . '.tpl', $data));
                        }
                    }
			  }
			
            } else {
                $url = '';

                if (isset($this->request->get['path'])) {
                    $url .= '&path=' . $this->request->get['path'];
                }

                if (isset($this->request->get['manufacturer_id'])) {
                    $url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
                }

                if (isset($this->request->get['filter_name'])) {
                    $url .= '&filter_name=' . $this->request->get['filter_name'];
                }

                if (isset($this->request->get['filter_tag'])) {
                    $url .= '&filter_tag=' . $this->request->get['filter_tag'];
                }

                if (isset($this->request->get['filter_description'])) {
                    $url .= '&filter_description=' . $this->request->get['filter_description'];
                }

                if (isset($this->request->get['filter_category_id'])) {
                    $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
                }

                $data['breadcrumbs'][] = array(
                    'text' => $this->language->get('text_error'),
                    'href' => $this->url->link('product/product', $url . '&product_id=' . $product_id),
                    'separator' => $this->language->get('text_separator')
                );

                $this->document->setTitle($this->language->get('text_error'));

                $data['heading_title'] = $this->language->get('text_error');

                $data['text_error'] = $this->language->get('text_error');

                $data['button_continue'] = $this->language->get('button_continue');

                $data['continue'] = $this->url->link('common/home');

                
				
				$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
			}
			
				
            }
        } else {
            $url = '';

            if (isset($this->request->get['path'])) {
                $url .= '&path=' . $this->request->get['path'];
            }

            if (isset($this->request->get['manufacturer_id'])) {
                $url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
            }

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . $this->request->get['filter_name'];
            }

            if (isset($this->request->get['filter_tag'])) {
                $url .= '&filter_tag=' . $this->request->get['filter_tag'];
            }

            if (isset($this->request->get['filter_description'])) {
                $url .= '&filter_description=' . $this->request->get['filter_description'];
            }

            if (isset($this->request->get['filter_category_id'])) {
                $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
            }

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_error'),
                'href' => $this->url->link('product/product', $url . '&product_id=' . $product_id),
                'separator' => $this->language->get('text_separator')
            );

            $this->document->setTitle($this->language->get('text_error'));

            $data['heading_title'] = $this->language->get('text_error');

            $data['text_error'] = $this->language->get('text_error');

            $data['button_continue'] = $this->language->get('button_continue');

            $data['continue'] = $this->url->link('common/home');

           $data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
			}
        }
    }

    public function group() {
        if (!isset($this->request->get['group_id']) && $this->request->get['group_id'] < 1)
		{
			$this->response->redirect($this->url->link('common/home','','SSL'));
		}

		$this->load->language('product/category');
        $this->load->model('catalog/product');
        $this->load->model('catalog/qa');
        $this->load->model('tool/image');
        
        $this->document->addStyle('catalog/view/theme/gempack/javascript/jquery/owl-carousel/owl.carousel.css');
        $this->document->addStyle('catalog/view/theme/gempack/javascript/jquery/owl-carousel/owl.transitions.css');
        $this->document->addStyle('catalog/view/css/ion.rangeSlider.min.css');
        $this->document->addScript('catalog/view/theme/gempack/javascript/jquery/owl-carousel/owl.carousel.min.js');
        $this->document->addScript('catalog/view/javascript/ion.rangeSlider.min.js');

		if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
		} else {
			$filter = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limit = $this->config->get('config_product_limit');
        }

        if (isset($this->request->get['price'])) {
			$price = (int)$this->request->get['price'];
		} else {
			$price = '';
        }

        if (isset($this->request->get['price_to'])) {
			$price_to = (int)$this->request->get['price_to'];
		} else {
			$price_to = '';
        }

        if (isset($this->request->get['price_from'])) {
			$price_from = (int)$this->request->get['price_from'];
		} else {
			$price_from = '';
        }
        
        if (isset($this->request->get['options']) && $this->request->get['options'] !== '') {
            $options = urldecode($this->request->get['options']);
            $options = rtrim($options, ',');
            $options = explode(',',$options);
            $optiondata = array();
            foreach($options as $result){
                $option = explode('~', $result);
                $optiondata[$option[0]] = $option[1];
            }
		} else {
			$optiondata = '';
        }

        if (isset($this->request->get['grouped']) && $this->request->get['grouped'] !== '') {
            $grouped = urldecode($this->request->get['grouped']);
            $grouped = rtrim($grouped, ',');
            $grouped = explode(',',$grouped);
            $groupeddata = array();
            foreach($grouped as $result){
                $group = explode('~', $result);
                $groupeddata[$group[0]] = $group[1];
            }
		} else {
			$groupeddata = '';
        }

        if (!$this->customer->isLogged()) {
            $data['logged'] = FALSE;
        } else {
            $data['logged'] = $this->customer->isLogged();
        }

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home'),
			'separator' => false
        );
        

		if (isset($this->request->get['group_id'])) {
			$group_id = $this->request->get['group_id'];
            $url = '';
            
            if(isset($this->request->get['pid'])){
                $product_id = $this->request->get['pid'];
                $url .= '&pid=' . $this->request->get['pid'];
            }else{
                $product_id = '';
            }

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
            }

            if (isset($this->request->get['price'])) {
				$url .= '&price=' . $this->request->get['price'];
            }

            if (isset($this->request->get['price_to'])) {
				$url .= '&price_to=' . $this->request->get['price_to'];
            }

            if (isset($this->request->get['price_from'])) {
				$url .= '&price_from=' . $this->request->get['price_from'];
            }

            if (isset($this->request->get['options']) && $this->request->get['options'] !== '') {
				$url .= '&options=' . $this->request->get['options'];
            }

            if (isset($this->request->get['grouped']) && $this->request->get['grouped'] !== '') {
				$url .= '&grouped=' . $this->request->get['grouped'];
            }
		} else {
			$group_id = 0;
		}
	
		$this->document->setTitle('Group Products List');
		$this->document->setDescription('');
		$this->document->setKeywords('');
        $this->document->addScript('catalog/view/javascript/jquery/jquery.total-storage.min.js?v='.rand());
        $data['category_title'] = '';
        $data['page_url'] = $this->url->link('product/product_grouped/group&pid='. $product_id, 'group_id=' . $group_id);       
        $data['heading_title'] = '';
        $data['text_refine'] = $this->language->get('text_refine');
        $data['text_empty'] = $this->language->get('text_empty');
        $data['text_quantity'] = $this->language->get('text_quantity');
        $data['text_manufacturer'] = $this->language->get('text_manufacturer');
        $data['text_model'] = $this->language->get('text_model');
        $data['text_price'] = $this->language->get('text_price');
        $data['text_tax'] = $this->language->get('text_tax');
        $data['text_points'] = $this->language->get('text_points');
        $data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
        $data['text_sort'] = $this->language->get('text_sort');
        $data['text_model'] = $this->language->get('text_model');
        $data['text_limit'] = $this->language->get('text_limit');
        $data['max_price'] = '0';

        $data['button_cart'] = $this->language->get('button_cart');
        $data['button_wishlist'] = $this->language->get('button_wishlist');
        $data['button_compare'] = $this->language->get('button_compare');
        $data['button_continue'] = $this->language->get('button_continue');
        $data['button_list'] = $this->language->get('button_list');
        $data['button_grid'] = $this->language->get('button_grid');
	
        $url = '';

        if (isset($this->request->get['filter'])) {
            $url .= '&filter=' . $this->request->get['filter'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['limit'])) {
            $url .= '&limit=' . $this->request->get['limit'];
        }

        if (isset($this->request->get['price'])) {
            $url .= '&price=' . $this->request->get['price'];
        }

        if (isset($this->request->get['price_from'])) {
            $url .= '&price_from=' . $this->request->get['price_from'];
        }

        if (isset($this->request->get['price_to'])) {
            $url .= '&price_to=' . $this->request->get['price_to'];
        }

        if (isset($this->request->get['options']) && $this->request->get['options'] !== '') {
            $url .= '&options=' . $this->request->get['options'];
        }

        if (isset($this->request->get['grouped']) && $this->request->get['grouped'] !== '') {
            $url .= '&grouped=' . $this->request->get['grouped'];
        }

        $data['text_groupby'] = '';
        $data['product_grouped'] = array();
        if(isset($product_id) && $product_id > 0){
            $gp_tpl_q = $this->model_catalog_product_grouped->getProductGroupedType($product_id);
            $data['text_groupby'] = !empty($gp_tpl_q['pg_groupby']) ? $gp_tpl_q['pg_groupby'] : $this->model_catalog_product_grouped->getProductGroupedByName($product_id);
            
            $product_grouped = array();
            $product_grouped_name = $this->model_catalog_product->getGroupedProductName($product_id);
            $this->document->setTitle($product_grouped_name);
            $gruppi = $this->model_catalog_product_grouped->getGrouped($product_id);
            
            if (!empty($gruppi)) {
            
                $total_options_product = 0;
                foreach ($gruppi as $groups) { 
                    $total_options_product += $this->model_catalog_product->getGroupedtotalProducts($groups['grouped_id']);
                    $product_name = $this->model_catalog_product->getGroupedProductName($groups['grouped_id']);
                    $p_option_names = $this->model_catalog_product->getProductOptionName($groups['grouped_id']);
                    $name = str_replace($product_grouped_name, '', $product_name);
                    if(!empty($p_option_names)) {
                        foreach($p_option_names as $p_option_name) {
                            $name = str_replace(" " . $p_option_name, '', $name);
                        }
                    }
                    //REQUESTED PRODUCT CODE
                    $requested_product = FALSE;

                    if(isset($groupeddata[$data['text_groupby']]) &&  trim(strtolower($groupeddata[$data['text_groupby']])) == trim(strtolower($name))){
                        $requested_product = TRUE;
                    }

                    $product_grouped[] = array(
                        'product_id' => $groups['grouped_id'],
                        'product_name' => $name,
                        'is_requested_product' => $requested_product //REQUESTED PRODUCT CODE
                    );
                }
                $data['product_grouped'] =  $product_grouped;
            }
        }

        $max_price_data = array(
            'filter_group_id'    => $group_id
        );

        $data['max_price'] = (int)$this->model_catalog_product->getGroupedProductsMaxPrice($max_price_data)+1;
        
        $filter_data = array(
            'filter_group_id'    => $group_id,
            'filter_filter'      => $filter,
            'filter_options'     => $optiondata,
            'filter_grouped'     => $groupeddata,
            'sort'               => $sort,
            'order'              => $order,
            'price'              => $price,
            'price_from'         => $price_from,
            'price_to'           => $price_to,
            'start'              => ($page - 1) * $limit,
            'limit'              => $limit
        );
      
		$results = $this->model_catalog_product->getGroupedtotalProductsFiltered($filter_data);
		$data['products'] = array();
		
		$product_total = 0;
		if($results > 0 ) {

			$product_total = $results;
            $results = $this->model_catalog_product->getGroupedProducts($filter_data);

			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $prices = $this->model_catalog_product->getProductDiscountByQuantity($result['product_id'],1);
					if(isset($prices['discount']) && $prices['discount'] > 0) {
						$newprice = round(ceil($prices['discount']*100)/100,2);
					}elseif(isset($prices['base_price']) && $prices['base_price'] > 0) {
						$newprice = round(ceil($prices['base_price']*100)/100,2);
					}else {
						$newprice = round(ceil($result['price']*100)/100,2);;
					}
					$price = $this->currency->format($this->tax->calculate($newprice, $result['tax_class_id'], $this->config->get('config_tax')));

				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
				} else {
					$tax = false;
				}

                if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
                }

                if ($result['quantity']) {
                    $stock_quantity = $result['quantity'];
                } else {
                    $stock_quantity = 0;
                }


                if ($result['date_added']) {
                    $date = $result['date_added'];
                } else {
                    $date = "";
                }

                if (isset($result['unit_singular'])) {
                    $data['unit_singular'] = $result['unit_singular'];
                } else {
                    $data['unit_singular'] = '';
                }
                if (isset($result['unit_plural'])) {
                    $unit_plural = $result['unit_plural'];
                } else {
                    $unit_plural = '';
                }
                
                $productAttributes= $this->model_catalog_product->getProductAttributes($result['product_id']);
                $discounts = $this->model_catalog_product->getProductDiscounts($result['product_id']);
                $price_without_discount = $this->currency->format($result['price']);

                if(!empty($discounts)){
                    if(is_array($discounts)){
                        foreach($discounts as $key => $discount){
                            $discounts[$key]['price'] = $this->currency->format($discount['price']);
                        }
                    }
                }
                
				$data['products'][] = array(
 'percent'   => sprintf($this->language->get('text_percent'), (round((($result['price'] -  $result['special'])/$result['price'])*100 ,0))),
                    'percent_value' => (round((($result['price'] -  $result['special'])/$result['price'])*100 ,0)),
                    'date'=>$date,
                    'stockquantity'=>$stock_quantity,
					'product_id'  => $result['product_id'],
					'model'       => $result['model'],
					'thumb'       => $image,
                    'name'        => $result['name'],
                    'quantity'    => $result['quantity'],
                    'stock'       => $result['stock_status'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
                    'tax'         => $tax,
                    'options'     => $this->model_catalog_qa->getProductOptions($result['product_id']),
                    'product_attributes'=>$productAttributes,
                    'discounts'   => $discounts,
                    'price_without_discount' => $price_without_discount,
                    'unit'        => $result['unit_singular'],
                    'unit_plural' => $unit_plural,
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $result['rating'],
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'] . $url)
				);
            }

            $data['options'] = array();

            foreach ($this->model_catalog_product->getProductOptions($group_id) as $option) {
                if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') {
                    $option_value_data = array();
                    $size = 0;

                    //REQUESTED PRODUCT CODE ENDS
                    foreach ($option['option_value'] as $option_value) {
                        //REQUESTED PRODUCT CODE
                        $is_requested_option_value = FALSE;
                        if(isset($optiondata[$option['name']]) &&  $optiondata[$option['name']] == $option_value['name']){
                            $is_requested_option_value = TRUE;
                        }
                        //REQUESTED PRODUCT CODE ENDS
                        if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
                            $option_value_data[] = array(
                                'product_option_value_id' => $option_value['product_option_value_id'],
                                'quantity' => $option_value['quantity'],
                                'option_value_id' => $option_value['option_value_id'],
                                'name' => $option_value['name'],
                                'image' => $this->model_tool_image->resize($option_value['image'], 50, 50),
                                'is_requested_option_value' => $is_requested_option_value//REQUESTED PRODUCT CODE
                            );
                        }
                    }

                    $data['options'][] = array(
                        'product_option_id' => $option['product_option_id'],
                        'option_id' => $option['option_id'],
                        'metal_type' => $size,
                        'name' => $option['name'],
                        'type' => $option['type'],
                        'option_value' => $option_value_data,
                        'required' => $option['required']
                    );
                } elseif ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
                    $data['options'][] = array(
                        'product_option_id' => $option['product_option_id'],
                        'option_id' => $option['option_id'],
                        'name' => $option['name'],
                        'type' => $option['type'],
                        'option_value' => $option['option_value'],
                        'required' => $option['required']
                    );
                }
            }

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
            }

            if (isset($this->request->get['price'])) {
				$url .= '&price=' . $this->request->get['price'];
            }

            if (isset($this->request->get['price_from'])) {
				$url .= '&price_from=' . $this->request->get['price_from'];
            }

            if (isset($this->request->get['price_to'])) {
				$url .= '&price_to=' . $this->request->get['price_to'];
            }

            if (isset($this->request->get['options']) && $this->request->get['options'] !== '') {
				$url .= '&options=' . $this->request->get['options'];
            }

            if (isset($this->request->get['grouped']) && $this->request->get['grouped'] !== '') {
				$url .= '&grouped=' . $this->request->get['grouped'];
            }
            
            $data['breadcrumbs'][] = array(
                'text' => $product_grouped_name,
                'href' => $this->url->link('product/product_grouped/group&pid='. $product_id, 'group_id=' . $this->request->get['group_id'])
            );

			$data['sorts'] = array();

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_default'),
				'value' => 'p.sort_order-ASC',
				'href'  => $this->url->link('product/product_grouped/group&pid='. $product_id, 'group_id=' . $this->request->get['group_id'] . '&sort=p.sort_order&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href'  => $this->url->link('product/product_grouped/group&pid='. $product_id, 'group_id=' . $this->request->get['group_id']  . '&sort=pd.name&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href'  => $this->url->link('product/product_grouped/group&pid='. $product_id, 'group_id=' . $this->request->get['group_id']  . '&sort=pd.name&order=DESC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_asc'),
				'value' => 'p.price-ASC',
				'href'  => $this->url->link('product/product_grouped/group&pid='. $product_id, 'group_id=' . $this->request->get['group_id']  . '&sort=p.price&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_desc'),
				'value' => 'p.price-DESC',
				'href'  => $this->url->link('product/product_grouped/group&pid='. $product_id, 'group_id=' . $this->request->get['group_id']  . '&sort=p.price&order=DESC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_newest'),
				'value' => 'p.date_added-DESC',
				'href'  => $this->url->link('product/product_grouped/group&pid='. $product_id, 'group_id=' . $this->request->get['group_id']  . '&sort=p.date_added&order=DESC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_oldest'),
				'value' => 'p.date_added-ASC',
				'href'  => $this->url->link('product/product_grouped/group&pid='. $product_id, 'group_id=' . $this->request->get['group_id']  . '&sort=p.date_added&order=ASC' . $url)
			);

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
            }
            
            if (isset($this->request->get['price'])) {
				$url .= '&price=' . $this->request->get['price'];
            }

            if (isset($this->request->get['price_from'])) {
				$url .= '&price_from=' . $this->request->get['price_from'];
            }

            if (isset($this->request->get['price_to'])) {
				$url .= '&price_to=' . $this->request->get['price_to'];
            }

            if (isset($this->request->get['options']) && $this->request->get['options'] !== '') {
				$url .= '&options=' . $this->request->get['options'];
            }

            if (isset($this->request->get['grouped']) && $this->request->get['grouped'] !== '') {
				$url .= '&grouped=' . $this->request->get['grouped'];
            }

			$data['limits'] = array();

			$limits = array_unique(array($this->config->get('config_product_limit'), 25, 50, 75, 100));

			sort($limits);

			foreach($limits as $value) {
				$data['limits'][] = array(
					'text'  => $value,
					'value' => $value,
					'href'  => $this->url->link('product/product_grouped/group&pid=' . $product_id, 'group_id=' . $this->request->get['group_id']  . $url . '&limit=' . $value)
				);
			}

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
            }
            
            if (isset($this->request->get['price'])) {
				$url .= '&price=' . $this->request->get['price'];
            }

            if (isset($this->request->get['price_from'])) {
				$url .= '&price_from=' . $this->request->get['price_from'];
            }

            if (isset($this->request->get['price_to'])) {
				$url .= '&price_to=' . $this->request->get['price_to'];
            }

            if (isset($this->request->get['options']) && $this->request->get['options'] !== '') {
				$url .= '&options=' . $this->request->get['options'];
            }

            if (isset($this->request->get['grouped']) && $this->request->get['grouped'] !== '') {
				$url .= '&grouped=' . $this->request->get['grouped'];
            }

			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('product/product_grouped/group&pid=' . $product_id, 'group_id=' . $this->request->get['group_id']  . $url . '&page={page}');

			$data['pagination'] = $pagination->render();

			$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

			if ($page == 1) {
			    $this->document->addLink($this->url->link('product/product_grouped/group&pid=' . $product_id, 'group_id=' . $this->request->get['group_id'] , 'SSL'), 'canonical');
			} elseif ($page == 2) {
			    $this->document->addLink($this->url->link('product/product_grouped/group&pid=' . $product_id, 'group_id=' . $this->request->get['group_id'] , 'SSL'), 'prev');
			} else {
			    $this->document->addLink($this->url->link('product/product_grouped/group&pid=' . $product_id, 'group_id=' . $this->request->get['group_id']  . '&page='. ($page - 1), 'SSL'), 'prev');
			}

			if ($limit && ceil($product_total / $limit) > $page) {
			    $this->document->addLink($this->url->link('product/product_grouped/group&pid=' . $product_id, 'group_id=' . $this->request->get['group_id']  . '&page='. ($page + 1), 'SSL'), 'next');
			}

			$data['sort'] = $sort;
			$data['order'] = $order;
			$data['limit'] = $limit;

			$data['continue'] = $this->url->link('common/home');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
			$data['latest_products'] = array();
			$data['tab_latest'] = "Latest " . $data['heading_title'];

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/group_product_list.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/group_product_list.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/product/group_product_list.tpl', $data));
			}
		} else {

            $data['options'] = array();

            foreach ($this->model_catalog_product->getProductOptions($group_id) as $option) {
                if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') {
                    $option_value_data = array();
                    $size = 0;
                   
                    foreach ($option['option_value'] as $option_value) {
                        //REQUESTED PRODUCT CODE
                        $is_requested_option_value = FALSE;
                        if(isset($optiondata[$option['name']]) &&  $optiondata[$option['name']] == $option_value['name']){
                            $is_requested_option_value = TRUE;
                        }
                        //REQUESTED PRODUCT CODE ENDS
                        if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
                            $option_value_data[] = array(
                                'product_option_value_id' => $option_value['product_option_value_id'],
                                'quantity' => $option_value['quantity'],
                                'option_value_id' => $option_value['option_value_id'],
                                'name' => $option_value['name'],
                                'image' => $this->model_tool_image->resize($option_value['image'], 50, 50),
                                'is_requested_option_value' => $is_requested_option_value//REQUESTED PRODUCT CODE
                            );
                        }
                    }

                    $data['options'][] = array(
                        'product_option_id' => $option['product_option_id'],
                        'option_id' => $option['option_id'],
                        'metal_type' => $size,
                        'name' => $option['name'],
                        'type' => $option['type'],
                        'option_value' => $option_value_data,
                        'required' => $option['required']
                    );
                } elseif ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
                    $data['options'][] = array(
                        'product_option_id' => $option['product_option_id'],
                        'option_id' => $option['option_id'],
                        'name' => $option['name'],
                        'type' => $option['type'],
                        'option_value' => $option['option_value'],
                        'required' => $option['required']
                    );
                }
            }

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
            }

            if (isset($this->request->get['price'])) {
				$url .= '&price=' . $this->request->get['price'];
            }

            if (isset($this->request->get['price_from'])) {
				$url .= '&price_from=' . $this->request->get['price_from'];
            }

            if (isset($this->request->get['price_to'])) {
				$url .= '&price_to=' . $this->request->get['price_to'];
            }

            if (isset($this->request->get['options']) && $this->request->get['options'] !== '') {
				$url .= '&options=' . $this->request->get['options'];
            }

            if (isset($this->request->get['grouped']) && $this->request->get['grouped'] !== '') {
				$url .= '&grouped=' . $this->request->get['grouped'];
            }
            
            $data['breadcrumbs'][] = array(
                'text' => $product_grouped_name,
                'href' => $this->url->link('product/product_grouped/group&pid=' . $product_id, 'group_id=' . $this->request->get['group_id'])
            );

			$data['sorts'] = array();

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_default'),
				'value' => 'p.sort_order-ASC',
				'href'  => $this->url->link('product/product_grouped/group&pid=' . $product_id, 'group_id=' . $this->request->get['group_id'] . '&sort=p.sort_order&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href'  => $this->url->link('product/product_grouped/group&pid=' . $product_id, 'group_id=' . $this->request->get['group_id']  . '&sort=pd.name&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href'  => $this->url->link('product/product_grouped/group&pid=' . $product_id, 'group_id=' . $this->request->get['group_id']  . '&sort=pd.name&order=DESC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_asc'),
				'value' => 'p.price-ASC',
				'href'  => $this->url->link('product/product_grouped/group&pid=' . $product_id, 'group_id=' . $this->request->get['group_id']  . '&sort=p.price&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_desc'),
				'value' => 'p.price-DESC',
				'href'  => $this->url->link('product/product_grouped/group&pid=' . $product_id, 'group_id=' . $this->request->get['group_id']  . '&sort=p.price&order=DESC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_newest'),
				'value' => 'p.date_added-DESC',
				'href'  => $this->url->link('product/product_grouped/group&pid=' . $product_id, 'group_id=' . $this->request->get['group_id']  . '&sort=p.date_added&order=DESC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_oldest'),
				'value' => 'p.date_added-ASC',
				'href'  => $this->url->link('product/product_grouped/group&pid=' . $product_id, 'group_id=' . $this->request->get['group_id']  . '&sort=p.date_added&order=ASC' . $url)
			);

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
            }
            
            if (isset($this->request->get['price'])) {
				$url .= '&price=' . $this->request->get['price'];
            }

            if (isset($this->request->get['price_from'])) {
				$url .= '&price_from=' . $this->request->get['price_from'];
            }

            if (isset($this->request->get['price_to'])) {
				$url .= '&price_to=' . $this->request->get['price_to'];
            }

            if (isset($this->request->get['options']) && $this->request->get['options'] !== '') {
				$url .= '&options=' . $this->request->get['options'];
            }

            if (isset($this->request->get['grouped']) && $this->request->get['grouped'] !== '') {
				$url .= '&grouped=' . $this->request->get['grouped'];
            }

			$data['limits'] = array();

			$limits = array_unique(array($this->config->get('config_product_limit'), 25, 50, 75, 100));

			sort($limits);

			foreach($limits as $value) {
				$data['limits'][] = array(
					'text'  => $value,
					'value' => $value,
					'href'  => $this->url->link('product/product_grouped/group&pid=' . $product_id, 'group_id=' . $this->request->get['group_id']  . $url . '&limit=' . $value)
				);
			}

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
            }
            
            if (isset($this->request->get['price'])) {
				$url .= '&price=' . $this->request->get['price'];
            }

            if (isset($this->request->get['price_from'])) {
				$url .= '&price_from=' . $this->request->get['price_from'];
            }

            if (isset($this->request->get['price_to'])) {
				$url .= '&price_to=' . $this->request->get['price_to'];
            }

            if (isset($this->request->get['options']) && $this->request->get['options'] !== '') {
				$url .= '&options=' . $this->request->get['options'];
            }

            if (isset($this->request->get['grouped']) && $this->request->get['grouped'] !== '') {
				$url .= '&grouped=' . $this->request->get['grouped'];
            }

			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('product/product_grouped/group&pid=' . $product_id, 'group_id=' . $this->request->get['group_id']  . $url . '&page={page}');

			$data['pagination'] = $pagination->render();

			$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

			if ($page == 1) {
			    $this->document->addLink($this->url->link('product/product_grouped/group&pid=' . $product_id, 'group_id=' . $this->request->get['group_id'] , 'SSL'), 'canonical');
			} elseif ($page == 2) {
			    $this->document->addLink($this->url->link('product/product_grouped/group&pid=' . $product_id, 'group_id=' . $this->request->get['group_id'] , 'SSL'), 'prev');
			} else {
			    $this->document->addLink($this->url->link('product/product_grouped/group&pid=' . $product_id, 'group_id=' . $this->request->get['group_id']  . '&page='. ($page - 1), 'SSL'), 'prev');
			}

			if ($limit && ceil($product_total / $limit) > $page) {
			    $this->document->addLink($this->url->link('product/product_grouped/group&pid=' . $product_id, 'group_id=' . $this->request->get['group_id']  . '&page='. ($page + 1), 'SSL'), 'next');
			}

			$data['sort'] = $sort;
			$data['order'] = $order;
			$data['limit'] = $limit;

			$data['continue'] = $this->url->link('common/home');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
			$data['latest_products'] = array();
			$data['tab_latest'] = "Latest " . $data['heading_title'];

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/group_product_list.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/group_product_list.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/product/group_product_list.tpl', $data));
			}
		}
    }

    public function isMobile($user_agent) {
		$mobile = false;
		if(stripos($user_agent,"iPod") || stripos($user_agent,"iPhone") || stripos($user_agent,"webOS") || stripos($user_agent,"BlackBerry") || stripos($user_agent,"windows phone") || stripos($user_agent,"symbian") || stripos($user_agent,"vodafone") || stripos($user_agent,"opera mini") || stripos($user_agent,"windows ce") || stripos($user_agent,"smartphone") || stripos($user_agent,"palm") || stripos($user_agent,"midp")) {
			$mobile = true;
		}
		if(stripos($user_agent,"Android") && stripos($user_agent,"mobile")){
		    $mobile = true;
		}else if(stripos($user_agent,"Android")){
	    	$mobile = false;
	   	}
		
		return $mobile;
	}
	
	public function isTablet($user_agent) {
		$tablet = false;
		if(stripos($user_agent,"Android") && stripos($user_agent,"mobile")){
		    $tablet = false;
		}else if(stripos($user_agent,"Android")){
	    	$tablet = true;
	   	}
		if(stripos($user_agent,"iPad") || stripos($user_agent,"RIM Tablet") || stripos($user_agent,"hp-tablet") || stripos($user_agent,"Kindle Fire")) {
			$tablet = true;
		}
		
		return $tablet;
	}

    public function review() {
        $this->load->language('product/product');

        $this->load->model('catalog/review');

        $data['text_on'] = $this->language->get('text_on');
        $data['text_no_reviews'] = $this->language->get('text_no_reviews');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['reviews'] = array();

        $review_total = $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id']);

        $results = $this->model_catalog_review->getReviewsByProductId($this->request->get['product_id'], ($page - 1) * 5, 5);

        foreach ($results as $result) {
            $data['reviews'][] = array(
                'author' => $result['author'],
                'text' => $result['text'],
                'rating' => (int) $result['rating'],
                'reviews' => sprintf($this->language->get('text_reviews'), (int) $review_total),
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
            );
        }

        $pagination = new Pagination();
        $pagination->total = $review_total;
        $pagination->page = $page;
        $pagination->limit = 5;
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('product/product/review', 'product_id=' . $this->request->get['product_id'] . '&page={page}');

        $data['pagination'] = $pagination->render();

        
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/review.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/review.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/product/review.tpl', $data));
		}
    }

    public function write() {
        $this->load->language('product/product');

        $this->load->model('catalog/review');

        $json = array();

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
                $json['error'] = $this->language->get('error_name');
            }

            if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
                $json['error'] = $this->language->get('error_text');
            }

            if (empty($this->request->post['rating'])) {
                $json['error'] = $this->language->get('error_rating');
            }

            if (empty($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
                $json['error'] = $this->language->get('error_captcha');
            }

            if (!isset($json['error'])) {
// S 6/7 //
                if ($this->request->get['grouped_id']) {
                    $this->model_catalog_review->addReview($this->request->get['grouped_id'], $this->request->post);
                }
// E 6/7 //
                $this->model_catalog_review->addReview($this->request->get['product_id'], $this->request->post);

                $json['success'] = $this->language->get('text_success');
            }
        }

        $this->response->setOutput(json_encode($json));
    }

    public function captcha() {
        $this->load->library('captcha');

        $captcha = new Captcha();

        $this->session->data['captcha'] = $captcha->getCode();

        $captcha->showImage();
    }

    public function upload() {
        $this->load->language('product/product');

        $json = array();

        if (!empty($this->request->files['file']['name'])) {
            $filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8')));

            if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 64)) {
                $json['error'] = $this->language->get('error_filename');
            }

            $allowed = array();

            $filetypes = explode(',', $this->config->get('config_upload_allowed'));

            foreach ($filetypes as $filetype) {
                $allowed[] = trim($filetype);
            }

            if (!in_array(substr(strrchr($filename, '.'), 1), $allowed)) {
                $json['error'] = $this->language->get('error_filetype');
            }

            if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
                $json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
            }
        } else {
            $json['error'] = $this->language->get('error_upload');
        }

        if (!$json) {
            if (is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])) {
                $file = basename($filename) . '.' . md5(mt_rand());

                // Hide the uploaded file name so people can not link to it directly.
                $json['file'] = $this->encryption->encrypt($file);

                move_uploaded_file($this->request->files['file']['tmp_name'], DIR_DOWNLOAD . $file);
            }

            $json['success'] = $this->language->get('text_upload');
        }

        $this->response->setOutput(json_encode($json));
    }

    public function compareInfo() {
        $this->load->language('product/compare');

        $data['title'] = $this->language->get('text_product');

        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['base'] = $server;
        $data['direction'] = $this->language->get('direction');
        $data['lang'] = $this->language->get('code');

        if ($this->config->get('config_icon') && file_exists(DIR_IMAGE . $this->config->get('config_icon'))) {
            $data['icon'] = $server . 'image/' . $this->config->get('config_icon');
        } else {
            $data['icon'] = '';
        }

        $this->load->model('catalog/product');
        $this->load->model('tool/image');
if ($product_info['image']) {
				$data['quick_thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
			} else {
				$data['quick_thumb'] = '';
			}

        $data['text_product'] = $this->language->get('text_product');
        $data['text_name'] = $this->language->get('text_name');
        $data['text_image'] = $this->language->get('text_image');
        $data['text_price'] = $this->language->get('text_price');
        $data['text_model'] = $this->language->get('text_model');
        $data['text_manufacturer'] = $this->language->get('text_manufacturer');
        $data['text_availability'] = $this->language->get('text_availability');
        $data['text_rating'] = $this->language->get('text_rating');
        $data['text_summary'] = $this->language->get('text_summary');
        $data['text_weight'] = $this->language->get('text_weight');
        $data['text_dimension'] = $this->language->get('text_dimension');
        $data['text_empty'] = $this->language->get('text_empty');

        $data['review_status'] = $this->config->get('config_review_status');

        $data['products'] = array();

        $data['attribute_groups'] = array();

        $compare_id = explode('-', $this->request->get['product']);

        foreach ($compare_id as $product_id) {
            $product_info = $this->model_catalog_product->getProduct($product_id);

            if ($product_info) {
                if ($product_info['image']) {
                    $image = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_compare_width'), $this->config->get('config_image_compare_height'));
                } else {
                    $image = false;
                }

                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $price = false;
                }

                if ((float) $product_info['special']) {
                    $special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $special = false;
                }

                if ($product_info['quantity'] <= 0) {
                    $availability = $product_info['stock_status'];
                } elseif ($this->config->get('config_stock_display')) {
                    $availability = $product_info['quantity'];
                } else {
                    $availability = $this->language->get('text_instock');
                }

                $attribute_data = array();

                $attribute_groups = $this->model_catalog_product->getProductAttributes($product_id);

                foreach ($attribute_groups as $attribute_group) {
                    foreach ($attribute_group['attribute'] as $attribute) {
                        $attribute_data[$attribute['attribute_id']] = $attribute['text'];
                    }
                }

                $data['products'][$product_id] = array(
                    'product_id' => $product_info['product_id'],
                    'name' => $product_info['name'],
                    'thumb' => $image,
                    'price' => $price,
                    'special' => $special,
                    'description' => strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')),
                    'model' => $product_info['model'],
                    'manufacturer' => $product_info['manufacturer'],
                    'availability' => $availability,
                    'rating' => (int) $product_info['rating'],
                    'reviews' => sprintf($this->language->get('text_reviews'), (int) $product_info['reviews']),
                    'weight' => $this->weight->format($product_info['weight'], $product_info['weight_class_id']),
                    'length' => $this->length->format($product_info['length'], $product_info['length_class_id']),
                    'width' => $this->length->format($product_info['width'], $product_info['length_class_id']),
                    'height' => $this->length->format($product_info['height'], $product_info['length_class_id']),
                    'attribute' => $attribute_data
                );

                foreach ($attribute_groups as $attribute_group) {
                    $data['attribute_groups'][$attribute_group['attribute_group_id']]['name'] = $attribute_group['name'];

                    foreach ($attribute_group['attribute'] as $attribute) {
                        $data['attribute_groups'][$attribute_group['attribute_group_id']]['attribute'][$attribute['attribute_id']]['name'] = $attribute['name'];
                    }
                }
            }
        }
		
		$data['content_top'] = $this->load->controller('common/content_bottom');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
			
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/product_grouped_details.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/product_grouped_details.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/product/product_grouped_details.tpl', $data));
		}
       
    }

    /**
    ***Get Related Products on options change
    **/
    public function getRelated() {
       $this->load->model('catalog/product');
       $product_id = $_GET['product_id'];
       $mode = $_GET['related'];

       $results = $this->model_catalog_product->getRelatedProducts($product_id,$mode);
       //print_r($results);
       $html = "";
       if ($results) {
            $a = (int)count($results);
            if ($a > 4){
                $flag = true;
            }else{
                $flag = false;
            }
            $i = 0;
            $html .= '<div class="owl-carousel product-carousel">';
            foreach ($results as $rproduct) {
                if($i >= 4)    break;
                $html.='<div class="owl-item"><div class="item text-center"><div class="product-item">
                    <div class="name">
                        <a href="'.$rproduct['href'].'" title="'.$rproduct['description'].'">'.$rproduct['name'].'</a></div>';

                    if ($rproduct['thumb']) { 
                        $html.='<div class="image"><a href="'.$rproduct['href'].'"><img src="'.$rproduct['thumb'].'" title="'.$rproduct['name'].'" alt="'.$rproduct['name'].'"></a></div>';
                    } else {
                        $html.='<div class="image"><img src="'.HTTP_SERVER.'catalog/view/theme/default/image/no_product.jpg" title="'.$rproduct['name'].'" alt="'.$rproduct['name'].'"></div>';    
                    }
                    
                    $html.= '<div class="price">';
                    if ($rproduct['special'])
                        $html.='<span>'.$rproduct['special'].'</span>';
                    else
                        $html.='<span>'.$rproduct['price'].'</span>'; //addQuickBuy('#addtocart0')
                    
                    $html.='<span class="unit-products"> per '.$rproduct['unit'].'</span></div>';
                    $html.='<div class="cart"><input id="button-cart" value="Add to Cart" class="button button-cart" data-product_id="'.$rproduct['product_id'].'" type="button" onClick="javascript:addToCart('."'".$rproduct['product_id']."'".',1)"></div>';
                    $html.='</div></div></div>';
                    $i++;
            }

            if ($flag) {
                    $html.='<div class="owl-item"><div class="item text-center"><div class="product-item">
                    <div class="name">
                        <strong>SEE ALL PRODUCTS<br/>Click To view All related Products</strong></div>';
                    $seeall = "index.php?route=product/search&getAllRelated&product_id=" . $product_id;    
                    
                    $html.='<div class="image"><a href="'.$seeall.'"><img src="'.HTTP_SERVER.'catalog/view/theme/default/image/seeall.png" style="width:150px;height:130px;"></a></div>';    
                    
                    
                    $html.= '<div class="price">';
                    $html.='<span>SEE ALL</span>';
                    
                    
                    $html.="</div></div></div>";
                }
                
            $html .= "</div>";
       }
       echo $html;
    }
    
    public function getVideo() {
		$this->load->model('catalog/product');
        $this->load->model('tool/image');
		$product_id = $_GET['product_id'];
		$video = $this->model_catalog_product->getVideo($product_id);
        $thumb = $this->model_catalog_product->getVideoPoster($product_id);
        $imgcount = count($this->model_catalog_product->getProductImages($product_id));
        $imgcount = isset($imgcount)?$imgcount:0;
		
		//$v_file = urldecode($video);
        $thumb = urldecode($thumb);
		//$data = array();
		$files = array();
		if (!empty($video)) {
			$vd = $video;
			$vd = explode(",",$vd);
			foreach ($vd as $vf){
				$v_file = urldecode($vf);
				//now check for link from file
				if (strpos($v_file,'http') !== FALSE) {
					$files['video'][] = array('file_type'=>'youtube','video'=>str_replace('watch?v=','embed/',$v_file));
					$files['poster'] = false;
                    $files['imgcount'] = $imgcount;
				}else{
					$file_type = 'local';
					$video_file = DIR_IMAGE . $v_file;
					$thumb_file = empty($thumb)?false:(DIR_IMAGE . $thumb);
					if (file_exists($video_file)){
						$formats = array('m4v'=>'mp4','m4a' => 'mp4');
						$video  = HTTPS_SERVER."image/" . $v_file;
						$ext = pathinfo($video_file, PATHINFO_EXTENSION);
						$v_type = isset($formats[$ext])?$formats[$ext]:$ext;
						$video_type = 'video/' . $v_type;
						$poster = (file_exists($thumb_file)?(HTTPS_SERVER."image/" . $thumb):false);
						$poster = ($poster !== false)?$this->model_tool_image->resize($thumb, $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height')):false;
					}else{
						$video = false;
						$video_type = false;
						$file_type = false;
						$poster = false;
					}
				$files['video'][] = array('file_type'=>$file_type,'video_type'=>$video_type,'video'=>$video);
				$files['poster'] = $poster;
                $files['imgcount'] = $imgcount;
			  }
			} //foreach ends
		}else{
			$files['video'] = false;
            $files['poster'] = false;
            $files['imgcount'] = 0;
		}
		echo json_encode($files);
	}

    /**
     * Get Product Options on the basis of selected otherwise default first 
     */
    public function getGroupOptions() {
        $this->load->model('catalog/product_grouped');
        $this->load->model('catalog/product');
        $groupIndicator = isset($this->request->post['group_indicator']) ? $this->request->post['group_indicator'] : "";
        if (isset($this->request->post['selChoice'])) {
            foreach ($this->request->post['selChoice'] as $opt) {
                $p = explode('~', $opt);
                $selOpt[$p[0]] = $p[1];
                //$selOpt[] = $p[1]; //not sure if want to open it (presented in new theme controller not in old one)
            }
        } else {
            $selOpt = '';
        }
        $product_id = isset($this->request->post['product_id']) ? $this->request->post['product_id'] : 0;
        $product_info = $this->model_catalog_product->getProduct($product_id);
        $this->load->model('tool/image');
if ($product_info['image']) {
				$data['quick_thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
			} else {
				$data['quick_thumb'] = '';
			}
        $product_info['group_indicator'] = $groupIndicator;
        $data['options'] = $this->_getproductOptionData($product_id, $product_info, $selOpt, $groupIndicator);
        echo json_encode($data);
    }

    public function getGroupOptionsDev() {
        $this->load->model('catalog/product_grouped');
        $this->load->model('catalog/product');
        $groupIndicator = isset($this->request->post['group_indicator']) ? $this->request->post['group_indicator'] : "";
        if (isset($this->request->post['selChoice'])) {
            foreach ($this->request->post['selChoice'] as $opt) {
                $p = explode('~', $opt);
                $selOpt[$p[0]] = $p[1];
                //$selOpt[] = $p[1]; //not sure if want to open it (presented in new theme controller not in old one)
            }
        } else {
            $selOpt = '';
        }
        $product_id = isset($this->request->post['product_id']) ? $this->request->post['product_id'] : 0;
        $product_info = $this->model_catalog_product->getProduct($product_id);
        $this->load->model('tool/image');
        if ($product_info['image']) {
			$data['quick_thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
		} else {
			$data['quick_thumb'] = '';
		}
        $product_info['group_indicator'] = $groupIndicator;
        $data['options'] = $this->_getproductOptionDataDev($product_id, $product_info, $selOpt, $groupIndicator);
        echo json_encode($data);
    }


    /**
     * Get combination Product data
     */
    public function getCombinationData() {
        $this->load->model('catalog/product_grouped');
        $this->load->model('catalog/product');
        $selected_options = array();
        if (!$this->customer->isLogged()) {
            $logged = FALSE;
        } else {
            $logged = $this->customer->isLogged();
        }
        if (isset($this->request->post['selChoice'])) {
            $selected_options = $this->request->post['selChoice'];
        }

        $groupbyvalue = $this->request->post['groupbyvalue'];
        $gi = isset($this->request->post['group_indicator']) ? $this->request->post['group_indicator'] : 0;
        $owhere = array(
            'groupindicator_id' => $gi,
            'groupbyvalue' => $groupbyvalue
        );
        foreach ($selected_options as $key => $op) {
            $o = explode('~', $op);
            $where = array();
            $k = $key + 1;
            $where['optionname' . $k] = $o[0];
            $where['optionvalue' . $k] = $o[1];
            $owhere = array_merge($owhere, $where);
        }

        $response = $this->model_catalog_product_grouped->getCombination($owhere);
        // pr($response); die;
        if ($response->num_rows <= 0) {
            $data['error'] = "Product not found";
            echo json_encode($data);
            die;
        }
//        $sku=$response->row['sku'];
//        $product_id=$this->model_catalog_product_grouped->getproductIdBySku($sku);
        $product_id = $response->row['product_id'];
        $data['op_ids'] = $this->_getOtionIds($product_id, $selected_options);
        $product_info = $this->model_catalog_product->getProduct($product_id);
        //echo '<pre>';print_r($product_info);exit;
        $this->load->model('tool/image');
if ($product_info['image']) {
				$data['quick_thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
			} else {
				$data['quick_thumb'] = '';
            }
        
        // Product Label
        $data['show_product_label_1'] = $product_info['show_product_label_1']; 
        $data['product_label_text_1'] = $product_info['product_label_text_1'];
        $data['show_product_label_2'] = $product_info['show_product_label_2']; 
        $data['product_label_text_2'] = $product_info['product_label_text_2']; 
        
        $this->load->model('localisation/products_label');
        $label_info = $this->model_localisation_products_label->getLabels();

        foreach($label_info as $labelget){
            $data['labels'][]=array(
                'label_id'=> $labelget['label_id'],
                'label_text'=> $labelget['label_text'],
                'label_color'=> $labelget['label_color'],
                'label_text_color'=> $labelget['label_text_color'],
                'condition_type'=> $labelget['condition_type'],
                'status'=> $labelget['status']

            );
            if($labelget['label_id']==1) {
                $data['new_product_db_days']= $labelget['condition_type'];
                $data['new_product_db_text']= $labelget['label_text'];
                $data['new_product_db_text_color']= $labelget['label_text_color'];
                $data['new_product_db_label_color']= $labelget['label_color'];
                $data['new_product_status']= $labelget['status'];
                $data['position_new']=$labelget['position'];

            }
            if($labelget['label_id']==2) {
                $data['discount_product_db_percent']= $labelget['condition_type'];
                $data['discount_product_db_text']= $labelget['label_text'];
                $data['discount_product_db_text_color']= $labelget['label_text_color'];
                $data['discount_product_db_label_color']= $labelget['label_color'];
                $data['discount_product_status']= $labelget['status'];
                $data['position_discount']= $labelget['position'];

            }
            if($labelget['label_id']==3) {
                $data['shipping_product_db_text']= $labelget['label_text'];
                $data['shipping_product_db_text_color']= $labelget['label_text_color'];
                $data['shipping_product_db_label_color']= $labelget['label_color'];
                $data['custom_product_label_2_status']= $labelget['status'];
                $data['position_shipping']= $labelget['position'];

            }
            if($labelget['label_id']==4) {
                $data['outofstock_product_db_text']= $labelget['label_text'];
                $data['outofstock_product_db_text_color']= $labelget['label_text_color'];
                $data['outofstock_product_db_label_color']= $labelget['label_color'];
                $data['outofstock_product_status']= $labelget['status'];
                $data['position_outofstock']= $labelget['position'];
            }
            if($labelget['label_id']==5) {
                $data['custom_product_db_text']= $labelget['label_text'];
                $data['custom_product_db_text_color']= $labelget['label_text_color'];
                $data['custom_product_db_label_color']= $labelget['label_color'];
                $data['custom_product_label_1_status']= $labelget['status'];
                $data['position_custom']= $labelget['position'];
            }
        }
        
        if( $data['custom_product_label_1_status'] == 1 && $data['show_product_label_1'] ) {
                if(empty($data['product_label_text_1'])) { 
                    $data['custom_product'] = '<div class="custom_product">' . $data['custom_product_db_text'] . '</div>';
                } else { 
                    $data['custom_product'] = '<div class="custom_product">' . $data['product_label_text_1'] . '</div>';
                } 
        } else {
                    $data['custom_product'] = '';
        }

        if( $data['custom_product_label_2_status'] == 1 && $data['show_product_label_2'] ) {
            if(empty($data['product_label_text_2'])) { 
                $data['shipping_product'] = '<div class="shipping_product">' . $data['shipping_product_db_text'] . '</div>';
            } else { 
                $data['shipping_product'] = '<div class="shipping_product">' . $data['product_label_text_2'] . '</div>';
            } 
    } else {
                $data['shipping_product'] = '';
    }
        // End Product Label
        
        $data['name'] = $product_info['name'];
		$data['minimum'] = !empty($product_info['minimum']) ? $product_info['minimum'] : 1;
        $data['product_id'] = $product_id;
        $data['price'] = $this->currency->format($product_info['price']);
        $data['unit'] = $product_info['unit_singular'];
        $data['date_sold_out'] = $product_info['date_sold_out'];
		$data['date_ordered'] = $product_info['date_ordered'];
		$data['estimate_deliver_time'] = $product_info['estimate_deliver_time'];
        $data['estimate_deliver_days'] = $product_info['estimate_deliver_days'];
		$data['date_available'] = $product_info['date_available'];
		$data['frontend_date_available'] = $product_info['frontend_date_available'];
        if ($product_info['quantity'] <= 0) {
			
			if( !empty($product_info['frontend_date_available']) && $product_info['frontend_date_available'] > date("Y-m-d") && $product_info['date_sold_out'] < $product_info['frontend_date_available'] )
            {
               $data['frontend_date_available'] = "<div class='date_available'>Estimated Arrival: " . $product_info['frontend_date_available'] . "</div>";
            } else {
			   $data['frontend_date_available'] = "<div class='date_available'></div>";
			}
            //$data['stock_status'] = "<span class='outofstock'></span>";
            if($product_info['stock_status'] == '2-3 Days') { $data['stock_status'] = "<span class='two_three_days'></span>"; }
			elseif($product_info['stock_status'] == 'Pre-Order') { $data['stock_status'] = "<span class='pre_order'></span>"; }
			elseif($product_info['stock_status'] == 'In Stock') { $data['stock_status'] = "<span class='inofstock'></span>"; }
			else { $data['stock_status'] = "<span class='outofstock'></span>"; }
            
            if ($product_info['quantity'] < 0) {  //$data['stock_status'] = "<span class='outofstock'></span>"; 
			
			if($product_info['stock_status'] == '2-3 Days') { $data['stock_status'] = "<span class='two_three_days'></span>"; }
			elseif($product_info['stock_status'] == 'Pre-Order') { $data['stock_status'] = "<span class='pre_order'></span>"; }
			elseif($product_info['stock_status'] == 'In Stock') { $data['stock_status'] = "<span class='inofstock'></span>"; }
			else { $data['stock_status'] = "<span class='outofstock'></span>"; }
			
			?>
            
            	<?php
            		$datetoday = date("Y-m-d");
	        		//echo $datetoday;
	        		if($datetoday > $data['date_ordered']){
						//echo "<span class='stocktext'>Will take at least (".$estimate_deliver_time.") days to come back in stock" . "</span>";
						$count = count($data['estimate_deliver_days']);
						$val = 0;
						foreach($data['estimate_deliver_days'] as $get_days){
							$val++;
							$availabledate = date("Y-m-d",strtotime($date_ordered ."+".$get_days['estimate_days']." days"));
							if($datetoday > $availabledate){
								if($count == $val){
									$data['stock_status'] .= "<span class='stocktext'> We expected this item back in stock a few weeks ago. There may be a manufacturer delay, please contact us for details </span>";
								}
								continue;
							}else{
								$data['stock_status'] .= "<span class='stocktext'>".str_replace('%s',date( "M d", strtotime($availabledate) ),$get_days['text']) ."</span>";
								break;
							}
						}
					}else{
						if($data['date_ordered'] != "0000-00-00"){
							foreach($data['estimate_deliver_days'] as $get_days){
								$availabledate = date("Y-m-d",strtotime($data['date_ordered'] . " +".$get_days['estimate_days']." days"));
								$availabledate = date( "M d", strtotime($availabledate) );
								$data['stock_status'] .= "<span class='stocktext'>".str_replace('%s',$availabledate,$get_days['text']) ."</span>";
								break;
							}		
						}
					}


            		
            	?>
                                           
        <?php } else { //$data['stock_status'] = "<span class='outofstock'></span>";
		
		    if($product_info['stock_status'] == '2-3 Days') { $data['stock_status'] = "<span style='    width: 111px;
    height: 27px;
    float: left;
    background: green;
    color: #fff; text-align:center;' class='two_three_days'>".$product_info['stock_status']."</span>"; }
			elseif($product_info['stock_status'] == 'Pre-Order') { $data['stock_status'] = "<span class='pre_order'></span>"; }
			elseif($product_info['stock_status'] == 'In Stock') { $data['stock_status'] = "<span class='inofstock'></span>"; }
			else { $data['stock_status'] = "<span class='outofstock'></span>"; }
		
		
		 ?>
            

        <?php }
            
            
//            $data['stock_status'] = $product_info['stock_status'];
        } elseif ($this->config->get('config_stock_display')) {
            $data['stock_status'] = $product_info['quantity'];
			$data['date_available'] = "<div class='date_available'></div>";
			$data['frontend_date_available'] = "<div class='date_available'></div>";
        } else {
//            $data['stock_status'] = "In Stock";
            $data['stock_status'] = "<span class='inofstock'></span>";
			$data['date_available'] = "<div class='date_available'></div>";
			$data['frontend_date_available'] = "<div class='date_available'></div>";
        }
        $data['model'] = $product_info['model'];
        $data['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');
		
		$results = $this->model_catalog_product->getProductImages($product_id);
		
		$data['additional_images'] ='';
        foreach ($results as $result) {
			$popup = $this->model_tool_image->resize($result['image'], 500, 500);
            $zoom_image = $this->model_tool_image->resize($result['image'], 1000, 1000);
			$thumb = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'));
			$data['additional_images'] .= '<li class="media_additional" style="display: inline-block"><a id="image" class="media_thumbnail" href="'.$popup.'" title="'.$product_info['name'].'"> <img src="'.$thumb.'" title="'.$product_info['name'].'" alt="'.$product_info['name'].'" /></a></li>';
            //$data['additional_images'] .= '<a href="'.$popup.'" title="'.$product_info['name'].'" class="cloudzoom-gallery_no" data-cloudzoom="useZoom: '."'.cloudzoom', image: '".$popup."', zoomImage:'".$zoom_image.'\'"><img src="'.$thumb.'" title="'.$product_info['name'].'" /></a>';
			
		}

        $data['additional_images_popup'] ='';
        foreach ($results as $result) {
            $popup = $this->model_tool_image->resize($result['image'], 1000, 1000);
            $img = $this->model_tool_image->resize($result['image'], 500, 500);
            $thumb = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'));
            $data['additional_images_popup'] .= '<a href="'.$popup.'" title="'.$product_info['name'].'" class="cloudzoom-gallery" data-cloudzoom="useZoom: '."'.cloudzoom_popup',startMagnification:1.9, image: '".$img."', zoomImage:'".$popup.'\'"><img src="'.$thumb.'" title="'.$product_info['name'].'" /></a>';
            
        }
		
        if ($product_info['image']) {
            $data['image'] = $data['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
            $data['large_image'] = $data['thumb'] = $this->model_tool_image->resize($product_info['image'], 500, 500);
        } else {
            $data['image'] = $data['thumb'] = $this->model_tool_image->resize('data/product/no_product.jpg', $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
            $data['large_image'] = $data['thumb'] = $this->model_tool_image->resize('data/product/no_product.jpg', 500, 500);
        }
		
		
		

        $data['base_price'] = $product_info['orignial_price'];
        $data['discounted_price'] = $product_info['discounted_price'];
        $data['reviews'] = $product_info['reviews'] . " Reviews";
        $data['tab_review'] = "Reviews(" . $product_info['reviews'] . ")";
        $data['rating'] = (int) $product_info['rating'];
        $data['sku'] = $product_info['sku'];
        $data['attribute_html'] = $this->_getProductAtrriData($product_id);
        $total_qas = $this->model_catalog_product->getGroupProductQACount($product_id);

        $data['text_tab_qa'] = "Q & A(" . $total_qas . ")";
        $data['add_image_data'] = $this->_getAdditionalImageData($product_id, $data['name']);
        $quantity = ($this->request->post['quantity'] != '') ? $this->request->post['quantity'] : 1;
        $unit_conversion_text = $this->request->post['unit_conversion_text'];
        $unit_data = $this->_getProductUnitVariables($product_id, $unit_conversion_text);

                $data['product_articles'] = (array) $this->model_catalog_product->getProductArticles($product_id);
        $data['embeded_product_article'] = false;
        foreach ($data['product_articles'] as $key => $article) {
                    if (isset($article['description'])) {
                        $data['embeded_product_article'][] = array(
                            'title' => html_entity_decode($article['title'], ENT_QUOTES, 'UTF-8'),
                            'description' => html_entity_decode($article['description'], ENT_QUOTES, 'UTF-8'),
                        );
                        unset($data['product_articles'][$key]);
                    }
                }
 $data['product_articles'] = array_values(array_filter($data['product_articles']));
                
        if (!empty($unit_data['unit_datas_html'])) {
            $data['product_unit_data_ajax'] = $unit_data['unit_datas_html'];
			if(isset($unit_data['selected_unit_price'])){
            	$conversion_price = $unit_data['selected_unit_price'];
			}else{
				$conversion_price = 0;
			}
            if (!empty($data['product_unit_data_ajax']) && $conversion_price != '') {
                $data['price'] = $this->_calConvertPrice($data['base_price'], $quantity, $conversion_price, $product_id);
                $data['unit'] = $unit_conversion_text;
            } else {
                $data['price'] = $this->_getQuanityPrice($data['base_price'], $quantity, $product_id);
                $data['unit'] = ($quantity > 1) ? $product_info['unit_singular'] : $product_info['unit_plural'];
            }
        } else {
            $data['price'] = $data['discounted_price'] ? $this->currency->format($data['discounted_price']) : $data['price'];
        }
        if ($logged) {
            $data['get_product_discount'] = $this->_getproductdiscount($product_id, $product_info['tax_class_id'], $product_info['unit_plural']);
        }
        
        echo json_encode($data);
    }

    /*
     * Fecting product option and 
     */

    private function _getOtionIds($product_id, $selected_options) {
        $option_ids = $this->model_catalog_product_grouped->getProductOptionIds($product_id, $selected_options);
        return $option_ids;
    }

    private function _getproductdiscount($product_id, $tax_class_id, $unit_plural) {
        $discounts = $this->model_catalog_product->getProductDiscounts($product_id);
        $price_without_discount = $this->currency->format($this->cart->geProductCalculatedPriceWithouDiscount($product_id), $tax_class_id, $this->config->get('config_tax'));

        $discount_html = "";
        if (!empty($discounts)) {
            $discount_html .="<li><span class='scale-quantity'>Non-Wholesale</span>
                                <span style='text-align: right !important;float: right;' class='scale-price'>" . $price_without_discount . "</span></li>";
            foreach ($discounts as $key => $discount) {
                $nextArray = ($key == 0) ? current($discounts) : next($discounts);
                if (!empty($nextArray)) {
                    $nextQuan = $nextArray['quantity'];
                    $nextQuan--;
                    $nextQuan = " - " . $nextQuan;
                } else {
                    $nextQuan = "+";
                }
                $discount_html .= "<li><span class='scale-quantity'>" . $discount['quantity'] . $nextQuan . " " . $unit_plural . "</span>";
                $discount_html.= "<span style='text-align: right !important;float: right;' class='scale-price'>" . $this->currency->format($discount['price']) . "</span></li>";
            }
        }
        return $discount_html;
    }

    private function _getProductAtrriData($product_id) {
        $attribute_groups = $this->model_catalog_product->getProductAttributes($product_id);

        $attribute_html = "";
        if (!empty($attribute_groups)) 
        {
            if($this->config->get('config_template') == 'gempack')
            {
                $attribute_html.= '<table class="table table-bordered attribute">';
                foreach ($attribute_groups as $attribute_group) 
                {
                    $attribute_html.= '<thead><tr><td colspan="2" class="background_td"><strong>' . $attribute_group['name'] . '</strong></td></tr></thead><tbody>';
                    foreach ($attribute_group['attribute'] as $attribute) 
                    {
                        $attribute_html.= '<tr>';
                        $attribute_html.= '<td class="background_td">' . $attribute['name'] . '</td>';
                        $attribute_html.= '<td>' . $attribute['text'] . '</td>';
                        $attribute_html.= '</tr>';
                    }
                    
                    $attribute_html.='</tbody></table>';
                }      
            } 
            else 
            {
                $attribute_html.="<table class='attribute'>";
                foreach ($attribute_groups as $attribute_group) {
                    $attribute_html.="<thead><tr><td class='background_td' colspan='2'>" . $attribute_group['name'] . "</td></tr></thead><tbody>";
                    foreach ($attribute_group['attribute'] as $attribute) {
                        $attribute_html.= "<tr>";
                        $attribute_html.= "<td class='background_td'>" . $attribute['name'] . "</td>";
                        $attribute_html.= "<td>" . $attribute['text'] . "</td>";
                        $attribute_html.= "</tr>";
                    }

                    $attribute_html.="</tbody></table>";
                }
            }
        }
        return $attribute_html;
    }

    private function _getAdditionalImageData($product_id, $product_name) {
        $is_qv_requsest = false;
        if (isset($this->request->post['qv'])) {
            $is_qv_requsest = $this->request->post['qv'];
        }
        $additional_images = $this->model_catalog_product->getProductImages($product_id);
        $add_image_data = '';
        if (!empty($additional_images)) {
            foreach ($additional_images as $result) {
                $thumb = "";
                $popup = "";
                if ($is_qv_requsest) {
                    $thumb = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'));
                    $popup = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
                } else {
                    $thumb = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'));
                    $popup = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
                }
                $add_image_data .="<a href='" . $popup . "' title='" . $product_name . "' class='changeMainGroup'>";
                $add_image_data .="<img src='" . $thumb . "' title='" . $product_name . "' alt='" . $product_name . "'></a>";
            }
        }
        return $add_image_data;
    }

    private function _getProductUnitVariables($product_id, $unit_conversion_text) {
        if (isset($this->request->post['is_quick_order'])) {
            $unit_select_box_id = 'qv_get-unit-data';
        } else {
            $unit_select_box_id = 'get-unit-data';
        }

        $unit_datas = $this->_getProductUnits($product_id);
        $unit_datas_html = "";
        if (!empty($unit_datas)) {
            $unit_datas_html .="<select id='" . $unit_select_box_id . "' class='get-unit-data id_convert_long form-control'>";
            foreach ($unit_datas as $unit_data) {
               
				if ($unit_data['name'] == $unit_conversion_text) {
                    $sel = "selected='selected'";
                    $return_unit_datas['selected_unit_price'] = $unit_data['convert_price'];
                } else {
                    $sel = '';
                }
                $unit_datas_html .="<option " . $sel . " data-value =" . $unit_data['unit_conversion_product_id'] . " name='" . $unit_data['name'] . "' value='" . $unit_data['convert_price'] . "'>";
                $unit_datas_html .=$unit_data['name'];
                $unit_datas_html .="</option>";
            }
            $unit_datas_html.="</select>";
        }
        $return_unit_datas['unit_datas_html'] = $unit_datas_html;
        return $return_unit_datas;
    }

    private function _getproductOptionData($product_id, $product_info, $selected_options, $groupIndicator) {
        // pr(array('groupindicator'=>$groupIndicator,'product_id'=>$product_info['group_indicator']));
        $opData = $this->model_catalog_product_grouped->getOptionsNames(array('groupindicator' => $groupIndicator, 'product_id' => $product_info['group_indicator']));
        $option_data = "";
        if ( isset($product_info['discounted_price']) && $product_info['discounted_price'] != "" ) {
            $discount_percent = $this->cart->calcMetalTypeDiscount($product_info['discounted_price'], $product_info['orignial_price']);
        } else {
            $discount_percent = 1;
        }
        $productOptions = $this->model_catalog_product->getProductOptions($product_id, $stat = 1);
        foreach ($productOptions as $option) {
            if ($option['type'] == 'select' || $option['type'] == 'radio') {
                $option_value_data = array();
                $size = 0;
                foreach ($option['option_value'] as $option_value) {
//                    if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {

                    if ($option['type'] == 'select' || $option['type'] == 'radio') {
                        if ($option['metal_type'] == 1) {
                            $formula = TRUE;
                            $size = sizeof($option['option_value']);
                            if ($size == 1) {
                                $add_optionsprice = $option_value['price'];
                                if ($add_optionsprice != 0) {
                                    $base_price = ($product_info['orignial_price'] + $add_optionsprice);
                                    $data['base_price'] = $base_price;
                                    $product_info['orignial_price'] = ($add_optionsprice + $product_info['orignial_price']) * $discount_percent;
                                }
                            } else {
                                !isset($add_optionsprice) ? $add_optionsprice = 0 : '';
                                $base_price = ($add_optionsprice + $product_info['orignial_price']);
                                $data['base_price'] = $base_price;
                                $product_info['orignial_price'] = $discount_percent * $base_price;
                            }
                        }
                    }
                    
                    if(!isset($product_info['tax_class_id'])) $product_info['tax_class_id'] = 0;
                    
                    $option_unformated_price = $this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
                    if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float) $option_value['price']) {
                        $price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                    } else {
                        $price = false;
                    }
                    $option_value_data[] = array(
                        'product_option_value_id' => $option_value['product_option_value_id'],
                        'option_value_id' => $option_value['option_value_id'],
                        'name' => $option_value['name'],
                        'image' => $this->model_tool_image->resize($option_value['image'], 50, 50),
                        'price' => $price,
                        'price2' => $option_unformated_price,
                        'price_prefix' => $option_value['price_prefix'],
                        'quantity' => $option_value['quantity']
                    );
//                    }
                }

                $data['options'][] = array(
                    'product_option_id' => $option['product_option_id'],
                    'option_id' => $option['option_id'],
                    'metal_type' => $size,
                    'name' => $option['name'],
                    'type' => $option['type'],
                    'option_value' => $option_value_data,
                    'required' => $option['required']
                );
            }
        }

        if (!isset($data['options'])) {
		    $data['options'] = array();
        } else {
            if(!empty($selected_options) && is_array($selected_options))
            {
                foreach ( $selected_options as $selected_option)
                {
                    $selopt[] = $selected_option;
                }

            } else {
                $selopt = '';
            }
            $data['options'] = $this->_checkCombinationExist($opData, $data['options'], $product_info['group_indicator'], $selopt);
        }
        if (isset($this->request->post['is_quick_order'])) {
            $option_class = 'option';
        } else {
            $option_class = 'option';
        }
        if (!empty($data['options'])) {
            foreach ($data['options'] as $key => $option) {
                if ($option['type'] == 'select') {
                    $UnitArry = explode(' ', $option['name']);
                    if (strtolower(end($UnitArry)) != "units") {
						$name = str_replace(' ', '', $option['name']);
						$name = str_replace('?', '', $name);
                        $option_data .="<div id='option-" . $option['product_option_id'] . "' class='" . $option_class . " ig_" . str_replace(' ', '', $name) . "' title='ig_" . str_replace(' ', '', $option['name']) . "'>";
                        if ($option['required']) {
                            $option_data .="<span class='required'>*</span>";
                        }

                        $option_data.="<b>" . $option['name'] . ":</b>";
                        $option_data.= "<select name='option[" . $option['product_option_id'] . "]'>";

                        if ($option['metal_type'] > 1) {
                            //$option_data.="<option value=''></option>";
                        }
                        $ophtml = array();
                        $selectflag = false;
                        foreach ($option['option_value'] as $option_value) {
                            $option_price = $option_value['price2'] ? $option_value['price2'] : '';
                            //$selected = in_array($option_value['name'], (array) $selected_options) ? 'selected=selected' : '';
                            $selected = ( $option_value['name'] == $selected_options[$option['name']]) ? 'selected=selected' : '';
                    
                            if ($selectflag == false && $selected != '') {
                                $selectflag = true;
                            }
                            $option_value_image = '';
                            if (!empty($option_value['image']) && !strpos($option_value['image'], 'no_image')) {
                                $option_value_image = $option_value['image'];
                            }
                            if ($option_value['quantity'] <= 0) {
                                $ophtml[] = "<option qty='" . $option_value['quantity'] . "' data-option_value_image='" . $option_value_image . "' data-price='" . $option_price . "' " . $selected . " value='" . $option_value['product_option_value_id'] . "'>"
                                        . "" . $option_value['name'] . " - " . $this->language->get('option_out_of_stock') .
                                        "</option>";
                            } else {
                                $ophtml[] = "<option qty='" . $option_value['quantity'] . "' data-option_value_image='" . $option_value_image . "' data-price='" . $option_price . "' " . $selected . " value='" . $option_value['product_option_value_id'] . "'>"
                                        . "" . $option_value['name'] .
                                        "</option>";
                            }
                        }

                        if ($selectflag == false) {
                            if(isset($ophtml[0]))
                            {
                            	$ophtml[0] = str_replace("value=", "selected='selected' value=", $ophtml[0]);
							}
                        }
                        $option_data.=implode('', $ophtml);
                        $option_data.="</select>";
                        $option_data.="</div>";
                    }
                }

                if ($option['type'] == 'radio') {
                    $UnitArry = explode(' ', $option['name']);
                    if (strtolower(end($UnitArry)) != "units") {
                        $option_data .="<div id='option-" . $option['product_option_id'] . "' class='" . $option_class . " ig_" . str_replace(' ', '', $option['name']) . "'>";
                        if ($option['required']) {
                            $option_data .="<span class='required'>*</span>";
                        }
                        $option_data.="<b>" . $option['name'] . ":</b>";

                        foreach ($option['option_value'] as $option_value) {
                            $option_price = $option_value['price2'] ? $option_value['price2'] : '';
                            $option_data.= "<input data-price='" . $option_price . "' type='radio' name='option[" . $option['product_option_id'] . "]' value='" . $option_value['product_option_value_id'] . "' id='option-value-." . $option_value['product_option_value_id'] . "' />";
                            $option_data.= "<label for='option-value-" . $option_value['product_option_value_id'] . " data-val='" . $option_value['name'] . "' >" . $option_value['name'];
                            $option_data.= "</label>";
                        }

                        $option_data.="</div>";
                    }
                }
            }
        }
        return $option_data;
    }

    private function _getproductOptionDataDev($product_id, $product_info, $selected_options, $groupIndicator) {
        $opData = $this->model_catalog_product_grouped->getOptionsNames(array('groupindicator' => $groupIndicator, 'product_id' => $product_info['group_indicator']));
        $option_data = "";
        if ( isset($product_info['discounted_price']) && $product_info['discounted_price'] != "" ) {
            $discount_percent = $this->cart->calcMetalTypeDiscount($product_info['discounted_price'], $product_info['orignial_price']);
        } else {
            $discount_percent = 1;
        }
        $productOptions = $this->model_catalog_product->getProductOptions($product_id, $stat = 1);
        foreach ($productOptions as $option) {
            if ($option['type'] == 'select' || $option['type'] == 'radio') {
                $option_value_data = array();
                $size = 0;
                foreach ($option['option_value'] as $option_value) {
                    if ($option['type'] == 'select' || $option['type'] == 'radio') {
                        if ($option['metal_type'] == 1) {
                            $formula = TRUE;
                            $size = sizeof($option['option_value']);
                            if ($size == 1) {
                                $add_optionsprice = $option_value['price'];
                                if ($add_optionsprice != 0) {
                                    $base_price = ($product_info['orignial_price'] + $add_optionsprice);
                                    $data['base_price'] = $base_price;
                                    $product_info['orignial_price'] = ($add_optionsprice + $product_info['orignial_price']) * $discount_percent;
                                }
                            } else {
                                !isset($add_optionsprice) ? $add_optionsprice = 0 : '';
                                $base_price = ($add_optionsprice + $product_info['orignial_price']);
                                $data['base_price'] = $base_price;
                                $product_info['orignial_price'] = $discount_percent * $base_price;
                            }
                        }
                    }
                    
                    if(!isset($product_info['tax_class_id'])) $product_info['tax_class_id'] = 0;
                    
                    $option_unformated_price = $this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
                    if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float) $option_value['price']) {
                        $price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                    } else {
                        $price = false;
                    }
                    $option_value_data[] = array(
                        'product_option_value_id' => $option_value['product_option_value_id'],
                        'option_value_id' => $option_value['option_value_id'],
                        'name' => $option_value['name'],
                        'image' => $this->model_tool_image->resize($option_value['image'], 50, 50),
                        'price' => $price,
                        'price2' => $option_unformated_price,
                        'price_prefix' => $option_value['price_prefix'],
                        'quantity' => $option_value['quantity']
                    );
                }

                $data['options'][] = array(
                    'product_option_id' => $option['product_option_id'],
                    'option_id' => $option['option_id'],
                    'metal_type' => $size,
                    'name' => $option['name'],
                    'type' => $option['type'],
                    'option_value' => $option_value_data,
                    'required' => $option['required']
                );
            }
        }

        if (!isset($data['options'])) {
		    $data['options'] = array();
        } else {
            if(!empty($selected_options) && is_array($selected_options)) {
                foreach ( $selected_options as $selected_option) {
                    $selopt[] = $selected_option;
                }
            } else {
                $selopt = '';
            }
            $data['options'] = $this->_checkCombinationExist($opData, $data['options'], $product_info['group_indicator'], $selopt);
        }
       
        if (isset($this->request->post['is_quick_order'])) {
            $option_class = 'option';
        } else {
            $option_class = 'option';
        }
        if (!empty($data['options'])) {
            foreach ($data['options'] as $key => $option) {
                if ($option['type'] == 'select') {
                    $UnitArry = explode(' ', $option['name']);
                    if (strtolower(end($UnitArry)) != "units") {
						$name = str_replace(' ', '', $option['name']);
                        $name = str_replace('?', '', $name);
                        $option_data .= "<div class='panel panel-default panel-default-options'>";
                        $option_data .= "<div class='panel-heading'>";
                        $option_data .= "<h4 class='panel-title'><a data-toggle='collapse' href='#option-" . $option['product_option_id'] . "'>  " . $option['name'] . "<i class='fa fa-caret-down pull-right'></i></a></h4>";
                        $option_data .= "</div>";
                        $option_data .="<div id='option-" . $option['product_option_id'] . "' class='" . $option_class . " ig_" . str_replace(' ', '', $name) . " panel-collapse collapse in' title='ig_" . str_replace(' ', '', $option['name']) . "'>";
                        $option_data .="<b style='display: none;'>" . $option['name'] . ":</b>";
                        $option_data .= "<div class='panel-body'>";
                        $option_data.= "<select name='option[" . $option['product_option_id'] . "]' class='form-control' onChange='checkit(this);'>";
                        //$selected_none = ($selected_options[$option['name']] == 'Please Select') ? 'selected=selected' : '';
                        //$option_data.= "<option value='0' " . $selected_none .">Please Select</option>";
                        if ($option['metal_type'] > 1) {
                            //$option_data.="<option value=''></option>";
                        }
                        $ophtml = array();
                        $selectflag = false;
                        foreach ($option['option_value'] as $option_value) {
                            //$option_price = $option_value['price2'] ? $option_value['price2'] : '';
                            //$selected = in_array($option_value['name'], (array) $selected_options) ? 'selected=selected' : '';
                            //if(empty($selected_none)){
                            //    $selected = ( $option_value['name'] == $selected_options[$option['name']]) ? 'selected=selected' : '';
                            //}else{
                            //   $selectflag = true;
                            //   $selected = '';
                            //}
                            //if ($selectflag == false && $selected != '') {
                            //    $selectflag = true;
                            //}

                            $option_price = $option_value['price2'] ? $option_value['price2'] : '';
                            //$selected = in_array($option_value['name'], (array) $selected_options) ? 'selected=selected' : '';
                            $selected = ( $option_value['name'] == $selected_options[$option['name']]) ? 'selected=selected' : '';
                            
                            if ($selectflag == false && $selected != '') {
                                $selectflag = true;
                            }

                            $option_value_image = '';
                            if (!empty($option_value['image']) && !strpos($option_value['image'], 'no_image')) {
                                $option_value_image = $option_value['image'];
                            }
                            if ($option_value['quantity'] <= 0) {
                                $ophtml[] = "<option qty='" . $option_value['quantity'] . "' data-option_value_image='" . $option_value_image . "' data-price='" . $option_price . "' " . $selected . " value='" . $option_value['product_option_value_id'] . "'>" . "" . $option_value['name'] . " - " . $this->language->get('option_out_of_stock') . "</option>";
                            } else {
                                $ophtml[] = "<option qty='" . $option_value['quantity'] . "' data-option_value_image='" . $option_value_image . "' data-price='" . $option_price . "' " . $selected . " value='" . $option_value['product_option_value_id'] . "' >" . "" . $option_value['name'] . "</option>";
                            }
                        }

                        if ($selectflag == false) {
                            if(isset($ophtml[0]))
                            {
                            	$ophtml[0] = str_replace("value=", "selected='selected' value=", $ophtml[0]);
							}
                        }
                        $option_data.=implode('', $ophtml);
                        $option_data.="</select>";
                        $option_data.="</div></div></div>";
                    }
                }

                if ($option['type'] == 'radio') {
                    $UnitArry = explode(' ', $option['name']);
                    if (strtolower(end($UnitArry)) != "units") {
                        $option_data .="<div id='option-" . $option['product_option_id'] . "' class='" . $option_class . " ig_" . str_replace(' ', '', $option['name']) . "'>";
                        if ($option['required']) {
                            $option_data .="<span class='required'>*</span>";
                        }
                        $option_data.="<b>" . $option['name'] . ":</b>";

                        foreach ($option['option_value'] as $option_value) {
                            $option_price = $option_value['price2'] ? $option_value['price2'] : '';
                            $option_data.= "<input data-price='" . $option_price . "' type='radio' name='option[" . $option['product_option_id'] . "]' value='" . $option_value['product_option_value_id'] . "' id='option-value-." . $option_value['product_option_value_id'] . "' />";
                            $option_data.= "<label for='option-value-" . $option_value['product_option_value_id'] . " data-val='" . $option_value['name'] . "' >" . $option_value['name'];
                            $option_data.= "</label>";
                        }

                        $option_data.="</div>";
                    }
                }
            }
        }
        return $option_data;
    }

    private function _calConvertPrice($base_price, $quantity, $conversion_price, $product_id) {
        $discount_qty = $quantity * $conversion_price;
        $discount_multiplier = $this->_getPercentDiscountMultiplier($discount_qty, $product_id);
        $base_price = $discount_qty * $base_price;
        $final_price = $discount_multiplier * $base_price;
        $returnObject['calc_price'] = $this->currency->format($final_price);
        return $returnObject['calc_price'];
    }

    private function _getQuanityPrice($base_price, $quantity, $product_id) {
        $discount_multiplier = $this->_getPercentDiscountMultiplier($quantity, $product_id);

        $final_price = $discount_multiplier * $base_price * $quantity;
        $calc_price = $this->currency->format($final_price);
        return $calc_price;
    }

    private function _getPercentDiscountMultiplier($discount_qty, $product_id) {
        $discount_qty = ($discount_qty < 1) ? 1 : $discount_qty;
        $discount_result = $this->model_catalog_product->getProductDiscountByQuantity($product_id, $discount_qty);
        if (isset($discount_result['discount'])) {
            return ($discount_result['discount'] / $discount_result['base_price']);
        } else {
            return 1;
        }
    }

    /**
     * Check Combination
     */
    private function _checkCombinationExist($group, $options, $product_id, $selop) {
        $disabledProducts = array();
        $owhere = $revisedOptions = $narray = $tmp = array();
        $gi = $product_id;
       // $disabledProducts = $this->model_catalog_product_grouped->getProductStatus($gi);
        $disabledProducts = $this->model_catalog_product_grouped->getDisabledProducts($gi);
        $options = $this->_reOrderOption($product_id, $options);

        foreach ($options as $key => $option) {
            if (!isset($selop[$key])) {
                $selop[$key] = $option['option_value'][0]['name'];
            }
            $inc = $key + 1;
            $optValues = $option['option_value'];
            unset($option['option_value']);
            foreach ($optValues as $optValue) {
                $where = array(
                    'groupindicator_id' => $gi,
                    'groupbyvalue' => $this->request->post['groupbyname'],
                    'optionname' . $inc => $option['name'],
                    'optionvalue' . $inc => $optValue['name']
                );
                $owhere = array_merge($narray, $where);
                $response = $this->model_catalog_product_grouped->getCombination($owhere);
                if ($response->num_rows > 0) {
                    if ($response->num_rows == 1 && !in_array($response->row['product_id'], $disabledProducts)) {
                        $option['option_value'][] = $optValue;
                    } else if ($response->num_rows > 1) {
                        $option['option_value'][] = $optValue;
                    }
                    if ($selop[$key] == $optValue['name']) {
                        $tmp = array_merge($narray, $where);
                    }
                }
            }
            if (!isset($option['option_value'])) {
                $option['option_value'] = array();
            }
            $revisedOptions[] = $option;
            $narray = array_merge($narray, $tmp);
            $key++;
        }
        return $revisedOptions;
    }

    /** Reorder options */
    private function _reOrderOption($product_id, $options) {
        $da = array();
        $where = array('product_id' => $product_id);
        $response = $this->model_catalog_product_grouped->getCombination($where, '*');
        foreach ($response->row as $key => $value) {
            if (preg_match('/(optionname)/', $key)) {
                if ($value !== '') {
                    $key = preg_replace('/(optionname)/', '', $key);
                    $da[$key - 1] = $value;
                }
            }
        }
        $options = $this->msort($options, $da);

        return $options;
    }

    private function msort($array, $key) {
        $sortedItems = $sorted = array();
        foreach ($key as $value) {
            $opt = $this->findInArray($array, $value);
            if ($opt !== false) {
                $sorted[] = $opt['value'];
                $sortedItems[] = $opt['key'];
            }
        }
        foreach ($array as $k => $value) {
            if (!in_array($k, $sortedItems)) {
                array_push($sorted, $value);
            }
        }
        return $sorted;
    }

    private function findInArray($array, $item) {
        $rarray = array();
        foreach ($array as $key => $ar) {
            if ($ar['name'] == $item) {
                $rarray['value'] = $ar;
                $rarray['key'] = $key;
                return $rarray;
            }
        }
        return false;
    }

    function getdiscountedprice($discount_qty, $product_id) {
        $this->load->model('catalog/product');
        ($discount_qty < 1) ? $discount_qty = 1 : $discount_qty = $discount_qty;
        $discount_result = $this->model_catalog_product->getProductDiscountByQuantity($product_id, $discount_qty);
        if(!empty($discount_result)){
            if (isset($discount_result['discount']) && $discount_result['discount'] >0) {
                return round(ceil($discount_result['discount']*100)/100,2);
            }elseif (isset($discount_result['base_price']) && $discount_result['base_price'] >0) {
                return round(ceil($discount_result['base_price']*100)/100,2);
            }else {
                $product_details = $this->model_catalog_product->getProduct($product_id);
                if(isset($product_details['price'])){
                    return round(ceil($product_details['price']*100)/100,2);
                }
            }
        }else{
            $product_details = $this->model_catalog_product->getProduct($product_id);
            if(isset($product_details['price'])){
                return round(ceil($product_details['price']*100)/100,2);
            }
        }
    }

    public function sendErrorMail() {
		die;
        /*$path = $_SERVER['HTTP_HOST'] . $_REQUEST['current_path'];
        //$to = "ricky@solutionbeyond.net, abha@yopmail.com, abha.sood@ignitras.com, ashish.gupta@ignitras.com";
        $subject = "Error in concated product";
        $txt = "Error Product link :  " . $path;
        $txt .= " Ip Address :  " . $_SERVER['REMOTE_ADDR'];
//        $headers .= 'From: <abha.sood@ignitras.com>' . "\r\n";
//        $headers .= 'Cc: abha@yopmail.com, abha.sood@ignitras.com, ashish.gupta@ignitras.com' . "\r\n";
//        mail($to,$subject,$txt,$headers);
//        $mail = new Mail(); 
//        $mail->protocol = 'SMTP';
//        $mail->parameter = '';
//        $mail->hostname = 'ssl://smtp.gmail.com';
//        $mail->username = 'sales@gempacked.com';
//        $mail->password = 'To9vTtNtQzB6';
//        $mail->port = 587;
//        $mail->timeout = 7;			
//        $mail->setTo("abha@yopmail.com");
//        $mail->setFrom('abha.sood@ignitras.com');
//        $mail->setSender('abha.sood@ignitras.com');
//        $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
//        $mail->setText(html_entity_decode($txt, ENT_QUOTES, 'UTF-8'));
        $mail = new PHPMailer(); // create a new object
        $mail->IsSMTP(); // enable SMTP
        $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
        $mail->SMTPAuth = true; // authentication enabled
        $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 465; // or 587
        $mail->IsHTML(true);
        $mail->Username = "sales@gempacked.com";
        $mail->Password = "To9vTtNtQzB6";
        $mail->setFrom('abha.sood@ignitras.com');
        $mail->Subject = $subject;
        $mail->Body = $txt;
        $mail->AddAddress('ricky@solutionbeyond.net');
        $mail->AddAddress('abha@yopmail.com');
        $mail->AddAddress('abha.sood@ignitras.com');
        $mail->AddAddress('ashish.gupta@ignitras.com');
        if (!$mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            echo "Message has been sent";
        }*/
        
    }

}
