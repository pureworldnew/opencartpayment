<?php
class ControllerProductProduct extends Controller {
	private $error = array();


			private function parseText($node, $keyword, $dom, $link, $target='', $tooltip = 0)
			{
				if (mb_strpos($node->nodeValue, $keyword) !== false)
					{
						$keywordOffset = mb_strpos($node->nodeValue, $keyword, 0, 'UTF-8');
						$newNode = $node->splitText($keywordOffset);
						$newNode->deleteData(0, mb_strlen($keyword, 'UTF-8'));
						$span = $dom->createElement('a', $keyword);
						if ($tooltip)
							{
								$span->setAttribute('href', '#');
								$span->setAttribute('style', 'text-decoration:none');
								$span->setAttribute('class', 'title');
								$span->setAttribute('title', $keyword.'|'.$link);
							}
							else
							{
								$span->setAttribute('href', $link);
								$span->setAttribute('target', $target);
								$span->setAttribute('style', 'text-decoration:none');
							}							
						
						$node->parentNode->insertBefore($span, $newNode);
						$this->parseText($newNode ,$keyword, $dom, $link, $target, $tooltip);
					}					
			}
			
			

			
	public function index() {

				
							// Search Analytics
							//
							// The search string can be returned by different sources:
							// - cookie
							// - GET variables
							// 
							// In some cases we cannot use GET variables to get the search string, because 
							// clicks don't come from a search button but from a link in the Live Search 
							// (when installed, see Advanced Smart Search, it includes Search Analytics)
				
							// "search" (OC >= 1.5.5) / "filter_name" (OC < 1.5.5)

							if (isset($this->request->cookie['adsmart_search_string'])) {	$search = base64_decode($this->request->cookie['adsmart_search_string']);	}		
							else if (isset($this->request->get['search'])) 				{	$search = $this->request->get['search']; 									}	
							else if (isset($this->request->get['filter_name']))			{	$search = $this->request->get['filter_name'];								}
							else 														{	$search = ''; 																}
				
							$customer_id	= $this->customer->getId(); // 0 if Guest
							
							$this->load->model('catalog/product'); 
							$this->model_catalog_product->addSearch($search, $customer_id);
							
							// Unset the cookie if present 
							if (isset($_COOKIE['adsmart_search_string'])) {
								unset($_COOKIE['adsmart_search_string']);
								setcookie('adsmart_search_string', null, -1, '/');	
							}
							
							// End Search Analytics
				
		$this->load->language('product/product');
		$this->load->language('product/product_grouped_mask');
		$user_agent = isset($this->request->server['HTTP_USER_AGENT'])?$this->request->server['HTTP_USER_AGENT']:'';
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home'),
            'separator' => $this->language->get('text_separator')
		);
		if(isset($this->request->get['product_id'])){
            $this->load->model('catalog/seo_url');
            $group_product_id = $this->model_catalog_seo_url->isProductGrouped($this->request->get['product_id']);
			$new_product_id=0;
			$gp_master_q = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product_grouped_type WHERE product_id = '" . (int)$group_product_id . "'");
			
			if($gp_master_q->num_rows){
				$new_product_id = $gp_master_q->row['product_id'];
			}else{
				$gp_slave_q = $this->db->query("SELECT pg.product_id FROM ".DB_PREFIX."product_grouped pg LEFT JOIN ".DB_PREFIX."product p ON (pg.grouped_id=p.product_id) WHERE p.model != 'grouped' AND p.pgvisibility!='1' AND p.status='1' AND pg.grouped_id='".(int)$group_product_id."' LIMIT 1");
				if($gp_slave_q->num_rows){
					$new_product_id = $gp_slave_q->row['product_id'];
				}
			}
			
			if($new_product_id){
                            
				$url = '';
				if(isset($this->request->get['path'])){$url .= '&path='.$this->request->get['path'];}
				if(isset($this->request->get['manufacturer_id'])){$url .= '&manufacturer_id='.$this->request->get['manufacturer_id'];}
				if(isset($this->request->get['filter_name'])){$url .= '&filter_name='.$this->request->get['filter_name'];}
				if(isset($this->request->get['filter_tag'])){$url .= '&filter_tag='.$this->request->get['filter_tag'];}
				if(isset($this->request->get['filter_description'])){$url .= '&filter_description='.$this->request->get['filter_description'];}
				if(isset($this->request->get['filter_category_id'])){$url .= '&filter_category_id='.$this->request->get['filter_category_id'];}
				$url .= '&product_id='.$new_product_id;
				if(isset($this->request->get['view'])){$url .= '&view';}
				$gp_tpl_q = $this->db->query("SELECT pg_type FROM " . DB_PREFIX . "product_grouped_type WHERE product_id = '" . (int)$new_product_id . "' LIMIT 1");
				$this->response->redirect($this->url->link('product/product_'.$gp_tpl_q->row['pg_type'],$url)); //go to: controller file
			}
		}
		
		$this->load->model('setting/setting');
		//$this->document->addStyle('catalog/view/javascript/cloudzoom/cloud-zoom.css?v='.rand());	
		//$this->document->addScript('catalog/view/javascript/cloudzoom/cloud-zoom.js?v='.rand());

		$this->document->addStyle('catalog/view/theme/gempack/javascript/jquery/owl-carousel/owl.carousel.css');
		$this->document->addScript('catalog/view/theme/gempack/javascript/jquery/owl-carousel/owl.carousel.min.js');

		$this->load->model('catalog/category');
		$this->load->model('catalog/product');

		if (isset($this->request->get['source']) && isset($this->request->get['product_id'])) {
			$result = $this->model_catalog_product->track($this->request->get['source'], $this->request->get['grouping'], $this->request->get['product_id']);
		}
		if (!$this->customer->isLogged()) {
            $data['logged'] = FALSE;
        } else {
            $data['logged'] = $this->customer->isLogged();
        }
		
		if (!isset($this->request->get['path'])) {
			$product_to_category_path = $this->model_catalog_product->getProductToCategoryPath($this->request->get['product_id']);
			if(!empty($product_to_category_path))
			{
				$this->request->get['path'] = $product_to_category_path;
			}	
		}
		
		if (isset($this->request->get['path'])) {
			$path = '';

			$parts = explode('_', (string)$this->request->get['path']);

			$category_id = (int)array_pop($parts);

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
					'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url),
                'separator' => $this->language->get('text_separator')
				);
			}
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

		if (isset($this->request->get['search']) || isset($this->request->get['tag'])) {
			$url = '';

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

			/* $data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_search'),
				'href' => $this->url->link('product/search', $url),
                'separator' => $this->language->get('text_separator')
			); */
		}

		if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
		} else {
			$product_id = 0;
		}

		$product_info = $this->model_catalog_product->getProduct($product_id);

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

$extendedseo = $this->config->get('extendedseo');
			$this->document->setTitle(((isset($category_info['name']) && isset($extendedseo['categoryintitle']) )?($category_info['name'].' : '):'').($product_info['meta_title']?$product_info['meta_title']:$product_info['name']));
			$this->document->setDescription($product_info['meta_description']);
			$this->document->setKeywords($product_info['meta_keyword']);
			$this->document->addLink($this->url->link('product/product', 'product_id=' . $this->request->get['product_id']), 'canonical');
			if($this->config->get('config_template') == 'gempack')
			{
				$this->document->addScript('catalog/view/javascript/group.product.page.v2.js');  
				$this->document->addScript('catalog/view/javascript/group.product.fix.js');   
			} else {
				$this->document->addScript('catalog/view/javascript/group.product.page.js');
			}
			$this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
			$this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
			$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
			$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
			$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');
			$this->document->addScript('catalog/view/javascript/jquery/tabs.js');
			$data['heading_title'] = ($product_info['custom_h1'] <> '')?$product_info['custom_h1']:$product_info['name'];
			$data['custom_alt'] = $product_info['custom_alt'];
			$data['custom_imgtitle'] = $product_info['custom_imgtitle'];
$data['custom_alt'] = $product_info['custom_alt'];
$data['custom_imgtitle'] = $product_info['custom_imgtitle'];
			$data['text_select'] = $this->language->get('text_select');
			$data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$data['text_model'] = $this->language->get('text_model');
			$data['text_reward'] = $this->language->get('text_reward');
			$data['text_points'] = $this->language->get('text_points');
			$data['text_stock'] = $this->language->get('text_stock');
			$data['text_discount'] = $this->language->get('text_discount');
			$data['text_tax'] = $this->language->get('text_tax');
			$data['text_option'] = $this->language->get('text_option');
			$data['text_minimum'] = sprintf($this->language->get('text_minimum'), $product_info['minimum']);
			$data['text_write'] = $this->language->get('text_write');
			$data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', 'SSL'), $this->url->link('account/register', '', 'SSL'));
			$data['text_note'] = $this->language->get('text_note');
			$data['text_tags'] = $this->language->get('text_tags');
			$data['text_related'] = $this->language->get('text_related');
			$data['text_payment_recurring'] = $this->language->get('text_payment_recurring');
			$data['text_loading'] = $this->language->get('text_loading');
			$data['option_out_of_stock'] = $this->language->get('option_out_of_stock');
			$data['entry_qty'] = $this->language->get('entry_qty');
			$data['entry_name'] = $this->language->get('entry_name');
			$data['entry_review'] = $this->language->get('entry_review');
			$data['entry_rating'] = $this->language->get('entry_rating');
			$data['entry_good'] = $this->language->get('entry_good');
			$data['entry_bad'] = $this->language->get('entry_bad');
			$data['text_qty'] = $this->language->get('text_qty');
			 $data['text_wait'] = $this->language->get('text_wait');
                $data['text_tags'] = $this->language->get('text_tags');


			$data['button_cart'] = $this->language->get('button_cart');
			$data['button_wishlist'] = $this->language->get('button_wishlist');
			$data['button_compare'] = $this->language->get('button_compare');
			$data['button_upload'] = $this->language->get('button_upload');
			$data['button_continue'] = $this->language->get('button_continue');

			$this->load->model('catalog/review');
			$this->load->model('catalog/qa');

			$data['tab_description'] = $this->language->get('tab_description');
			$data['tab_attribute'] = $this->language->get('tab_attribute');
			$data['tab_review'] = sprintf($this->language->get('tab_review'), $product_info['reviews']);
			
			/*  if ($this->config->get('qa_status')) {
                $data['tab_qa'] = sprintf($this->language->get('tab_qa'), $this->model_catalog_qa->getTotalQAsByProductId($this->request->get['product_id']));
            } */
			$data['tab_qa'] = sprintf($this->language->get('tab_qa'), $this->model_catalog_qa->getTotalQAsByProductId($this->request->get['product_id']));
			$data['product_id'] = (int)$this->request->get['product_id'];
			
            $data['product_articles'] = (array)$this->model_catalog_product->getProductArticles($product_id);
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

			
				$data['mbreadcrumbs'] = array();

				$data['mbreadcrumbs'][] = array(
					'text'      => $this->language->get('text_home'),
					'href'      => $this->url->link('common/home')
				);
				
				if ($this->model_catalog_product->getFullPath($this->request->get['product_id'])) {
					
					$path = '';
			
					$parts = explode('_', (string)$this->model_catalog_product->getFullPath($this->request->get['product_id']));
					
					$category_id = (int)array_pop($parts);
											
					foreach ($parts as $path_id) {
						if (!$path) {
							$path = $path_id;
						} else {
							$path .= '_' . $path_id;
						}
						
						$category_info = $this->model_catalog_category->getCategory($path_id);
						
						if ($category_info) {
							$data['mbreadcrumbs'][] = array(
								'text'      => $category_info['name'],
								'href'      => $this->url->link('product/category', 'path=' . $path)								
							);
						}
					}
					
					$category_info = $this->model_catalog_category->getCategory($category_id);
					
					if ($category_info) {			
						$url = '';
											
						$data['mbreadcrumbs'][] = array(
							'text'      => $category_info['name'],
							'href'      => $this->url->link('product/category', 'path=' . $this->model_catalog_product->getFullPath($this->request->get['product_id']))						
						);
					}
			
				
				} else {
				$data['mbreadcrumb'] = false;
				}
				
				$data['review_no'] = $product_info['reviews'];		
				$data['quantity'] = $product_info['quantity'];						
				$data['currency_code'] = $this->session->data['currency'];
				$data['richsnippets'] = $this->config->get('richsnippets');				
			
			
			
			$extendedseo = $this->config->get('extendedseo');
			$data['description'] = ((isset($extendedseo['productseo']))?'<h2>'.$product_info['name'].'</h2>':'').html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');
			
$data['description'] = ($product_info['custom_h2'] != '')?'<h2>'.$product_info['custom_h2'].'</h2>'.$data['description']:$data['description'];
			//$data['custom_alt'] = $product_info['custom_alt'];
			//$data['custom_imgtitle'] = $product_info['custom_imgtitle'];
			if ($product_info['quantity'] <= 0) {
				$data['stock'] = $product_info['stock_status'];
			} elseif ($this->config->get('config_stock_display')) {
				$data['stock'] = $product_info['quantity'];
			} else {
				$data['stock'] = $this->language->get('text_instock');
			}
			
			$data['date_available'] = $product_info['date_available'];
			$data['frontend_date_available'] = $product_info['frontend_date_available'];
			$data['date_sold_out'] = $product_info['date_sold_out'];
			$data['date_ordered'] = $product_info['date_ordered'];
			$data['estimate_deliver_time'] = $product_info['estimate_deliver_time'];
			$data['estimate_deliver_days'] = $product_info['estimate_deliver_days'];
			$data['product']['date'] = $product_info['date_added'];
			
			$data['qa_notify'] = $this->config->get("qa_question_reply_notification");
            $data['qa_name']   = ($this->customer->isLogged()) ? $this->customer->getFirstName() : "";
            $data['qa_email']  = ($this->customer->isLogged()) ? $this->customer->getEmail() : "";
            $data['preload']   = intval($this->config->get("qa_preload"));
            if ($data['preload'] == 1)
                $data['qas']   =  $this->question($product_id, 1, false);
            else if ($data['preload'] == 2)
                $data['qas']   =  $this->question($product_id, 1, false, 0);
            else
                $data['qas']   =  '';
            
			
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
			$data['quick_config_image_thumb_width'] = $this->config->get('quick_config_image_thumb_width');

			$data['quick_h2'] = $this->config->get('quick_h2');

			$data['quick_description_left'] = $this->config->get('quick_description_left');
			$data['quick_description_right'] = $this->config->get('quick_description_right');
			$data['quick_general_bottom_description'] = $this->config->get('quick_general_bottom_description');

			$data['quick_name_general'] = $this->config->get('quick_name_general');
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
			$data['quick_posleft6'] = $this->config->get('quick_posleft6');

			$data['quick_posright1'] = $this->config->get('quick_posright1');
			$data['quick_posright2'] = $this->config->get('quick_posright2');
			$data['quick_posright3'] = $this->config->get('quick_posright3');
			$data['quick_posright4'] = $this->config->get('quick_posright4');
			$data['quick_posright5'] = $this->config->get('quick_posright5');
			$data['quick_posright6'] = $this->config->get('quick_posright6');

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
            $data['quick_config_image_thumb_width'] = $this->config->get('quick_config_image_thumb_width');

            $data['quick_h2'] = $this->config->get('quick_h2');

            $data['quick_description_left'] = $this->config->get('quick_description_left');
            $data['quick_description_right'] = $this->config->get('quick_description_right');
            $data['quick_general_bottom_description'] = $this->config->get('quick_general_bottom_description');

            $data['quick_name_general'] = $this->config->get('quick_name_general');
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
            $data['quick_posleft6'] = $this->config->get('quick_posleft6');

            $data['quick_posright1'] = $this->config->get('quick_posright1');
            $data['quick_posright2'] = $this->config->get('quick_posright2');
            $data['quick_posright3'] = $this->config->get('quick_posright3');
            $data['quick_posright4'] = $this->config->get('quick_posright4');
            $data['quick_posright5'] = $this->config->get('quick_posright5');
            $data['quick_posright6'] = $this->config->get('quick_posright6');

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
            /*             * **quick view code plugin ends ***** */

            $data['sku'] = $product_info['sku'];

            $data['upc_ref'] = $product_info['upc'];
            $data['quantity'] = $product_info['quantity'];
			
			$data['msg']="" ;
            if($this->config->get('advanced_stock_alert_status')==1 and $this->config->get('allowaddemail')==1)
            {
					if ($product_info['quantity'] <= 0) {
						$data['stock'] = $product_info['stock_status'];
						$data['msg']="Enter Your Email to be informed when this product arrives:";
					} elseif ($this->config->get('config_stock_display')) {
						$data['stock'] = $product_info['quantity'];
					} else {
						$data['stock'] = $this->language->get('text_instock');
					}
			} else {
				if ($product_info['quantity'] <= 0) {
					$data['stock'] = $product_info['stock_status'];
				} elseif ($this->config->get('config_stock_display')) {
					$data['stock'] = $product_info['quantity'];
				} else {
					$data['stock'] = $this->language->get('text_instock');
				}
			}
			$data['action'] = $this->url->link('product/product', 'product_id=' . $product_info['product_id']);
			if (isset($this->request->post['cmailid'])) {
					$data['cmailid'] = $this->request->post['cmailid'];
			} else {
					$data['cmailid'] = '';
			}
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate())  
			{
				$this->model_catalog_product->addnotifycustomer($this->request->post);
				$data['success']='Email Added successfully';
			}
			if (isset($this->error['cmailid'])) {
				$data['error_cmailid'] = $this->error['cmailid'];
			} else {
				$data['error_cmailid'] = '';
			}


			$this->load->model('tool/image');


            	$data['zoom_image'] = '';

			

			if (isset($product_info['image']) && $product_info['image']) {

				$data['zoom_image'] = $this->model_tool_image->resize($product_info['image'], 1000, 1000);								

				$data['thumbadditional'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'));								

			} else {

				$data['zoom_image'] = '';

				$data['thumbadditional'] = '';				

			}		

            
			
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

			$results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);

			foreach ($results as $result) {
				$data['images'][] = array(
			

			'zoom_image' => $this->model_tool_image->resize(isset($result['image'])?$result['image']:'' , 1000, 1000),

			'thumbimage' => $this->model_tool_image->resize(isset($result['image'])?$result['image']:'' , $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height')),			

            
 'percent'	=> sprintf($this->language->get('text_percent'), (round((($product_info['price'] -  $product_info['special'])/$product_info['price'])*100 ,0))),
                    'percent_value'	=> (round((($product_info['price'] -  $product_info['special'])/$product_info['price'])*100 ,0)),
                     'date'=>$date,
                    'special'     => $special,
                    'stockquantity'=>$stock_quantity,
					'popup' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
					'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'))
				);
			}
			
			 $data['images'] = array_reverse($data['images']);
			 
			
			$data['videos'] = array();
			$data['poster'] = false;
			$files = array();
			if (!empty($product_info['video'])) {
				$vd = $product_info['video'];
				$vd = explode(",",$vd);
				foreach ($vd as $vf){
					$v_file = urldecode($vf);
					//now check for link from file
					if (strpos($v_file,'http') !== FALSE) {
						$files['video'][] = array('file_type'=>'youtube','video'=>str_replace('watch?v=','embed/',$v_file));
						$files['poster'] = false;
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
				  }
				} //foreach ends
				}else{
					$files['video'] = false;
					$files['poster'] = false;
				}
				 $data['videos'] = $files['video'];
				 //echo '<pre>';print_r($data['videos']);echo '</pre>';
				 
			 
			 
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
            
			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
			   $product_info['price'] = round(ceil($product_info['price']*100)/100,2);
				$data['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$data['price'] = false;
			}

			if ((float)$product_info['special']) {
				$data['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$data['special'] = false;
			}

			if ($this->config->get('config_tax')) {
				$data['tax'] = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price']);
			} else {
				$data['tax'] = false;
			}

			$discounts = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);

			$data['discounts'] = array();

			foreach ($discounts as $discount) {
				$data['discounts'][] = array(
					'quantity' => $discount['quantity'],
					'price'    => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')))
				);
			}

           
            $data['options'] = array();
//            pr($product_info);
            $data['unit_conversion_help'] = $this->model_catalog_product->getConversionHelp($product_info['product_id']);
            $formula = FALSE;
           
            $data['base_price'] = $product_info['orignial_price'];

                            $data['unit_datas'] = $this->_getProductUnits($product_info['product_id']);
                            
                        
            //$data['discounts'] = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);
                                                 
            foreach ($this->model_catalog_product->getProductOptions($this->request->get['product_id']) as $option) {
                if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') {
                    $option_value_data = array();
                    $size = 0;

                    foreach ($option['option_value'] as $option_value) {
                        if (!$option_value['subtract'] || $option_value['subtract']) {

                            $option_unformated_price = $this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
                            if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float) $option_value['price']) {
                                $price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                            } else {
                                $price = false;
                            }
                            $option_value_data[] = array(

											'default' => $option_value['default'], //Q: Default Product Option
			
                                'product_option_value_id' => $option_value['product_option_value_id'],
'quantity'         => $option_value['quantity'],
                                'option_value_id' => $option_value['option_value_id'],
                                'name' => $option_value['name'],
                                'image' => $this->model_tool_image->resize($option_value['image'], 50, 50),
                                'price' => $price,
                                'price2' => $option_unformated_price,
                                'price_prefix' => $option_value['price_prefix']
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
			$data['price_without_discount'] = $this->currency->format($product_info['orignial_price']);
            if (!$formula) {
                $product_info['orignial_price'] = $product_info['price'];
			 } if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
               // $data['price'] = $this->currency->format($this->tax->calculate($this->cart->geProductCalculatedPrice($product_info['product_id']), $product_info['tax_class_id'], $this->config->get('config_tax')));
                 $prices = $this->model_catalog_product->getProductDiscountByQuantity($product_info['product_id'],1);
					if (isset($prices['discount']) && $prices['discount'] > 0) {
						$newprice = round(ceil($prices['discount']*100)/100,2);
					} elseif(isset($prices['base_price']) && $prices['base_price'] > 0) {
						$newprice = round(ceil($prices['base_price']*100)/100,2);
					} else {
						$newprice = round(ceil($product_info['price']*100)/100,2);
					}           
			  $data['price'] = $this->currency->format($this->tax->calculate($newprice, $product_info['tax_class_id'], $this->config->get('config_tax')));    
			 } else {
                $data['price'] = false;
            }
			$data['contact'] = $this->url->link('information/contact');
           //$data['price_without_discount'] = $this->currency->format($product_info['price']);
			
			if ($product_info['minimum']) {
				$data['minimum'] = $product_info['minimum'];
			} else {
				$data['minimum'] = 1;
			}

			$data['review_status'] = $this->config->get('config_review_status');

			if ($this->config->get('config_review_guest') || $this->customer->isLogged()) {
				$data['review_guest'] = true;
			} else {
				$data['review_guest'] = false;
			}

			if ($this->customer->isLogged()) {
				$data['customer_name'] = $this->customer->getFirstName() . '&nbsp;' . $this->customer->getLastName();
			} else {
				$data['customer_name'] = '';
			}

			$data['reviews'] = sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']);
			$data['rating'] = (int)$product_info['rating'];

			// Captcha
			if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
				$data['captcha'] = $this->load->controller('captcha/' . $this->config->get('config_captcha'));
			} else {
				$data['captcha'] = '';
			}


				$autolinks = $this->config->get('autolinks'); 
				
				if (isset($autolinks) && (strpos($data['description'], 'iframe') == false) && (strpos($data['description'], 'object') == false)){
				$xdescription = mb_convert_encoding(html_entity_decode($data['description'], ENT_COMPAT, "UTF-8"), 'HTML-ENTITIES', "UTF-8"); 
				
				libxml_use_internal_errors(true);
				$dom = new DOMDocument; 			
				$dom->loadHTML('<div>'.$xdescription.'</div>');				
				libxml_use_internal_errors(false);

				
				$xpath = new DOMXPath($dom);
								
				foreach ($autolinks as $autolink)
				{	
					$keyword = $autolink['keyword'];
					$xlink = mb_convert_encoding(html_entity_decode($autolink['link'], ENT_COMPAT, "UTF-8"), 'HTML-ENTITIES', "UTF-8");
					$target = $autolink['target'];
					$tooltip = isset($autolink['tooltip']);
													
					$pTexts = $xpath->query(
						sprintf('///text()[contains(., "%s")]', $keyword)
					);
					
					foreach ($pTexts as $pText) {
						$this->parseText($pText, $keyword, $dom, $xlink, $target, $tooltip);
					}

									
				}
						
				$data['description'] = $dom->saveXML($dom->documentElement);
				
				}
				
			
			$data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);
			
						$extendedseo = $this->config->get('extendedseo');
			$data['description'] = ((isset($extendedseo['productseo']))?'<h2>'.$product_info['name'].'</h2>':'').html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');
			
			//$data['description'] = ($product_info['custom_h2'] != '')?'<h2>'.$product_info['custom_h2'].'</h2>'.$data['description']:$data['description'];

			$autolinks = $this->config->get('autolinks'); 
				
			if (isset($autolinks) && (strpos($data['description'], 'iframe') == false) && (strpos($data['description'], 'object') == false)){
				$xdescription = mb_convert_encoding(html_entity_decode($data['description'], ENT_COMPAT, "UTF-8"), 'HTML-ENTITIES', "UTF-8"); 
				
				libxml_use_internal_errors(true);
				$dom = new DOMDocument; 			
				$dom->loadHTML('<div>'.$xdescription.'</div>');				
				libxml_use_internal_errors(false);

				
				$xpath = new DOMXPath($dom);
								
				foreach ($autolinks as $autolink)
				{	
					$keyword = $autolink['keyword'];
					$xlink = mb_convert_encoding(html_entity_decode($autolink['link'], ENT_COMPAT, "UTF-8"), 'HTML-ENTITIES', "UTF-8");
					$target = $autolink['target'];
					$tooltip = isset($autolink['tooltip']);
													
					$pTexts = $xpath->query(
						sprintf('///text()[contains(., "%s")]', $keyword)
					);
					
					foreach ($pTexts as $pText) {
						$this->parseText($pText, $keyword, $dom, $xlink, $target, $tooltip);
					}

									
				}
						
				$data['description'] = $dom->saveXML($dom->documentElement);
				
				}
				
			

				$autolinks = $this->config->get('autolinks'); 
				
				if (isset($autolinks) && (strpos($data['description'], 'iframe') == false) && (strpos($data['description'], 'object') == false)){
				$xdescription = mb_convert_encoding(html_entity_decode($data['description'], ENT_COMPAT, "UTF-8"), 'HTML-ENTITIES', "UTF-8"); 
				
				libxml_use_internal_errors(true);
				$dom = new DOMDocument; 			
				$dom->loadHTML('<div>'.$xdescription.'</div>');				
				libxml_use_internal_errors(false);

				
				$xpath = new DOMXPath($dom);
								
				foreach ($autolinks as $autolink)
				{	
					$keyword = $autolink['keyword'];
					$xlink = mb_convert_encoding(html_entity_decode($autolink['link'], ENT_COMPAT, "UTF-8"), 'HTML-ENTITIES', "UTF-8");
					$target = $autolink['target'];
					$tooltip = isset($autolink['tooltip']);
													
					$pTexts = $xpath->query(
						sprintf('///text()[contains(., "%s")]', $keyword)
					);
					
					foreach ($pTexts as $pText) {
						$this->parseText($pText, $keyword, $dom, $xlink, $target, $tooltip);
					}

									
				}
						
				$data['description'] = $dom->saveXML($dom->documentElement);
				
				}
				
			
            $data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);

            $data['qa_status'] = 1; //$this->config->get('qa_status');
            


			$this->load->model('catalog/youtube_embed');
			
			$data['youtube_embed_tab_text'] = $this->config->get('youtube_embed_tab_text');
			
			$data['youtube_videos_tab'] = array();
			
			$data['youtube_embed_tab'] = false;
			
			if ($this->config->get('youtube_embed_enabled')) {
				$data['youtube_embed_tab'] = true;
			
				if ($this->config->get('youtube_embed_tab')) {
					$data['youtube_videos'] = $this->model_catalog_youtube_embed->getYoutubeVideos($data['description']);
					foreach ($data['youtube_videos'] as $url) {
						$data['description'] = str_ireplace($url, '', $data['description']);
						
						$data['youtube_videos_tab'][] = array(
							'url'	=>	$url,
							'code'	=>	$this->model_catalog_youtube_embed->getYoutubeCode($url),
						);
					}
				} else {
					$data['youtube_embed_tab'] = false;
					$data['youtube_videos'] = $this->model_catalog_youtube_embed->getYoutubeVideos($data['description']);
					foreach ($data['youtube_videos'] as $url) {
						$data['description'] = str_ireplace($url, $this->model_catalog_youtube_embed->getYoutubeCode($url), $data['description']);
					}
				}
			}
			
			$data['products'] = array();

			$results = $this->model_catalog_product->getProductRelated($this->request->get['product_id'],0);
			
			//print_r($results);

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

				$data['products'][] = array(
 'percent'	=> sprintf($this->language->get('text_percent'), (round((($result['price'] -  $result['special'])/$result['price'])*100 ,0))),
                    'percent_value'	=> (round((($result['price'] -  $result['special'])/$result['price'])*100 ,0)),
                    'date'=>$date,
                    'stockquantity'=>$stock_quantity,
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

			$data['wproducts'] = array();

			$results= $this->model_catalog_product->getProductRelated($this->request->get['product_id'],1);

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
					$data['tags'][] = array(
						'tag'  => trim($tag),
						'href' => $this->url->link('product/search', 'tag=' . trim($tag))
					);
				}
			}

			$data['profiles'] = $this->model_catalog_product->getProfiles($this->request->get['product_id']);
			
			$richsnippets = $this->config->get('richsnippets');
			$ga_tracking_type = $this->config->get('config_ga_tracking_type');
			
			$this->model_catalog_product->updateViewed($this->request->get['product_id']);

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');


			$data['price'] = $this->currency->format2d($data['price']);

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/product.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/product.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/product/product.tpl', $data));
			}
		} else {
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

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('product/product', $url . '&product_id=' . $product_id),
                'separator' => $this->language->get('text_separator')
			);

$extendedseo = $this->config->get('extendedseo');
			$this->document->setTitle(((isset($category_info['name']) && isset($extendedseo['categoryintitle']) )?($category_info['name'].' : '):'').$this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

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
		$this->load->model('tool/image');

		$data['text_no_reviews'] = $this->language->get('text_no_reviews');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['reviews'] = array();

		if( isset($this->request->get['gp_product']) )
		{
			//Group Product
			$review_total = $this->model_catalog_review->getTotalGroupProductReviewsByProductId($this->request->get['product_id']);
			$results = $this->model_catalog_review->getGroupProductReviewsByProductId($this->request->get['product_id'], ($page - 1) * 5, 5); 

		} else {
			// Simple Product
			$review_total = $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id']);
			$results = $this->model_catalog_review->getReviewsByProductId($this->request->get['product_id'], ($page - 1) * 5, 5);
		}

		foreach ($results as $result) {
			$data['reviews'][] = array(
				'author'     	=> $result['author'],
				'text'       	=> nl2br($result['text']),
				'rating'     	=> (int)$result['rating'],
				'image'		 	=> $this->model_tool_image->resize($result['image'], $this->config->get('config_image_wishlist_width'), $this->config->get('config_image_wishlist_height')),
				'name'		 	=> $result['name'],
				'sku'		 	=> $result['sku'],
				'product_options' => $this->model_catalog_review->getProductOptions($result['product_id']),
				'price'			=> $result['price'],
				'href' 			=> $this->url->link('product/product', 'product_id=' . $result['product_id']),
				'date_added' 	=> date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

		$pagination = new Pagination();
		$pagination->total = $review_total;
		$pagination->page = $page;
		$pagination->limit = 5;
		if( isset($this->request->get['gp_product']) )
		{
			$pagination->url = $this->url->link('product/product/review', 'product_id=' . $this->request->get['product_id'] . '&gp_product=1&page={page}');
		} else {
			$pagination->url = $this->url->link('product/product/review', 'product_id=' . $this->request->get['product_id'] . '&page={page}');
		}

		$data['pagination'] = $pagination->render();
			
				foreach ($pagination->prevnext() as $pagelink) {
					$this->document->addLink($pagelink['href'], $pagelink['rel']);
				}
				

		$data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * 5) + 1 : 0, ((($page - 1) * 5) > ($review_total - 5)) ? $review_total : ((($page - 1) * 5) + 5), $review_total, ceil($review_total / 5));

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/review.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/review.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/product/review.tpl', $data));
		}
		
	}
	
	public function article() {
		
		$this->load->model('catalog/product');

		$data['product_articles'] = $this->model_catalog_product->getProductArticles($this->request->get['product_id']);

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/article.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/article.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/product/article.tpl', $data));
		}
		
	}

	public function write() {
		$this->load->language('product/product');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
				$json['error'] = $this->language->get('error_name');
			}

			if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
				$json['error'] = $this->language->get('error_text');
			}

			if (empty($this->request->post['rating']) || $this->request->post['rating'] < 0 || $this->request->post['rating'] > 5) {
				$json['error'] = $this->language->get('error_rating');
			}

			// Captcha
			if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
				$captcha = $this->load->controller('captcha/' . $this->config->get('config_captcha') . '/validate');

				if ($captcha) {
					$json['error'] = $captcha;
				}
			}

			if (!isset($json['error'])) {
				$this->load->model('catalog/review');

				$this->model_catalog_review->addReview($this->request->get['product_id'], $this->request->post);

				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function getRecurringDescription() {
		$this->language->load('product/product');
		$this->load->model('catalog/product');

		if (isset($this->request->post['product_id'])) {
			$product_id = $this->request->post['product_id'];
		} else {
			$product_id = 0;
		}

		if (isset($this->request->post['recurring_id'])) {
			$recurring_id = $this->request->post['recurring_id'];
		} else {
			$recurring_id = 0;
		}

		if (isset($this->request->post['quantity'])) {
			$quantity = $this->request->post['quantity'];
		} else {
			$quantity = 1;
		}

		$product_info = $this->model_catalog_product->getProduct($product_id);
		$recurring_info = $this->model_catalog_product->getProfile($product_id, $recurring_id);

		$json = array();

		if ($product_info && $recurring_info) {
			if (!$json) {
				$frequencies = array(
					'day'        => $this->language->get('text_day'),
					'week'       => $this->language->get('text_week'),
					'semi_month' => $this->language->get('text_semi_month'),
					'month'      => $this->language->get('text_month'),
					'year'       => $this->language->get('text_year'),
				);

				if ($recurring_info['trial_status'] == 1) {
					$price = $this->currency->format($this->tax->calculate($recurring_info['trial_price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')));
					$trial_text = sprintf($this->language->get('text_trial_description'), $price, $recurring_info['trial_cycle'], $frequencies[$recurring_info['trial_frequency']], $recurring_info['trial_duration']) . ' ';
				} else {
					$trial_text = '';
				}

				$price = $this->currency->format($this->tax->calculate($recurring_info['price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')));

				if ($recurring_info['duration']) {
					$text = $trial_text . sprintf($this->language->get('text_payment_description'), $price, $recurring_info['cycle'], $frequencies[$recurring_info['frequency']], $recurring_info['duration']);
				} else {
					$text = $trial_text . sprintf($this->language->get('text_payment_cancel'), $price, $recurring_info['cycle'], $frequencies[$recurring_info['frequency']], $recurring_info['duration']);
				}

				$json['success'] = $text;
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	private function _getProductUnits($product_id){
		$data = $this->model_catalog_product->getUnitDetails($product_id);
		return $data = $this->model_catalog_product->getUnitDetails($product_id);
    }
	
	  /*
     * Function calculating discount percent
     * @param $orginal_price - Product price
     * @param $product_id - Product id
     * @param $discount_qty - No of products in cart
     * returns $discount_percent
     */

    public function calcMetalPercent($original_price, $product_id, $discount_qty) {
        $discount_query = array();
        $this->load->model('catalog/product');
        $discount_query = $this->model_catalog_product->getProductDiscountByQuantity($product_id, $discount_qty);
        if ($discount_query) {
            $price = $discount_query[0]['price'];
            $discount_percent = $this->cart->calcMetalTypeDiscount($price, $original_price);
            return $price;
        } else {
            return $price = 1;
        }
    }

    /*
     * Function Calculate product price
     */

    public function calcPrice() {
        if (isset($this->request->post)) {
            $product_id = $this->request->post['p_id'];
            $discount_qty = $this->request->post['quantity'];
            $original_price = $this->request->post['price'];
            $option_value = (isset($this->request->post['option_value'])) ? $this->request->post['option_value'] : "";
            $discountpercent = $this->calcMetalPercent($original_price, $product_id, $discount_qty);
           
            //If Product has special price
            if (isset($this->request->post['price_special'])) {
                if ($discount_qty >= 1 && $discount_qty < 10) { // If product quantity is between 1 and 10
                    $price = $this->request->post['price_special'];
                } else {
                    $discount_percent = 100 - (100 / $original_price) * $discountpercent;
                    $add_optionsprice = $option_value;
                    $price = ($original_price + $add_optionsprice) - (($discount_percent / 100) * ($original_price + $add_optionsprice));
                }
                if (isset($this->request->post['op_units_value'])) {
                    $price*= $this->request->post['op_units_value'];
                }
                $price*= $discount_qty;
            } else {
                if ($option_value != '') {
                    $price = ($original_price * $discountpercent + $option_value * $discountpercent);
                } else {
                    $price = ($original_price * $discountpercent);
                }
                if (isset($this->request->post['op_units_value'])) {
                    $price*= $this->request->post['op_units_value'];
                }
                $price*= $discount_qty;
            }

            echo $price;
        }
    }

    /*
     * Unit Conversion
     * 
     */

    public function convertLengthUnits($product_id, $units) {
        switch ($to) {
            case 'metre':
                $this->session->data[$product_id]['length'] = $units;
                break;
            case 'cm':
                break;
            case 'mm':
                break;
            case 'foot':
                break;
            case 'inch':
                break;
            case 'default':
                break;
        }
    }
	
	  protected function validate() 
	  {
		  if ((utf8_strlen($this->request->post['cmailid']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['cmailid'])) {
			  $this->error['cmailid'] = "Enter correct Email Id.";
		  }
		  $check="SELECT * FROM "."advanced_stock_alert where product_id=".(int)$this->request->post['product_id']." and customer_email='".$this->db->escape($this->request->post['cmailid'])."'";

		  $check_row=$this->db->query($check);
		  if($check_row->num_rows>0){
			  $this->error['cmailid']="Email already added";
		  }
		  if (!$this->error) {
			  return true;
		  } else {
			  return false;
		  }
	  } 
	
	  public function weightId() {
        $val = trim($this->request->post['value']);
        $from = trim($this->request->post['from']);
        $to = trim($this->request->post['to']);

        $this->load->model("catalog/product");
        $data = $this->model_catalog_product->getWeightClasses($from, $to);
        if (sizeof($data) == 1) {
            $from_id = $data[0]['value'];
            $to_id = $data[0]['value'];
        } else {
            foreach ($data as $newdata) {
                if (strcmp($newdata['name'], $from)) {
                    $from_id = $newdata['value'];
                }
                if (strcmp($newdata['name'], $to)) {
                    $to_id = $newdata['value'];
                }
            }
        }
        $result = $this->weight->convert($val, $from_id, $to_id);
        echo json_encode(round($result, 2) . $from);
    }

    public function lengthId() {
        $val = trim($this->request->post['value']);
        $from = trim($this->request->post['from']);
        $to = trim($this->request->post['to']);
//        echo $from.$to.$val; die();
        $this->load->model("catalog/product");
        $data = $this->model_catalog_product->getLengthClasses($from, $to);
//        print_r($data); die();
        if (sizeof($data) == 1) {
            $from_id = $data[0]['value'];
            $to_id = $data[0]['value'];
        } else {
            foreach ($data as $newdata) {
                if (strcmp($newdata['name'], $from)) {
                    $from_id = $newdata['value'];
                }
                if (strcmp($newdata['name'], $to)) {
                    $to_id = $newdata['value'];
                }
            }
        }
        $result = $this->length->convert($val, $from_id, $to_id);
        echo json_encode(round($result, 2) . $from);
    }

    function calcPrice2() {
//        pr($_POST);
        $returnObject = array();
        $product_id = $_POST['p_id'];
        $this->load->model('catalog/product');
        $discounts = $this->model_catalog_product->getProductDiscounts($product_id);
		$unit_name = $this->model_catalog_product->getDefaultUnitName($product_id);
		$unit_data = $this->model_catalog_product->getDefaultUnitDetails($product_id);
		
		//print_r($unit_name);
		$returnObject['unit_convertion_enable'] = '0';
		
		if(!empty($unit_data)){
			$returnObject['unit_convertion_enable'] = '1';
		}
		
		if(isset($_POST['quantity'])){
            	
		}else{
				$_POST['quantity'] = 1;
		}
        $unit_bulk_pricing = array();
        if (isset($_POST['conversion_price']) && $unit_data) { 
            $this->load->model('catalog/product');
            $discount_qty = $_POST['quantity'] * $_POST['conversion_price'];
            $returnObject['discount_quantity'] = number_format((float) $discount_qty, 2, '.', '');
            $discount_multiplier = $this->getPercentDiscountMultiplier($discount_qty, $product_id);
            $base_price = $_POST['base_price'];
           // $base_price = $discount_qty * $base_price;
            $final_price = $discount_multiplier * $base_price;
            $quantity = $_POST['quantity'] * $_POST['conversion_price'];
            $final_price = round((($discount_multiplier*$discount_qty*$base_price)/$_POST['quantity']),2);
                
            $returnObject['calc_price'] = $this->currency->format($final_price * $_POST['quantity'],'','',false);
			if($_POST['quantity'] == 1){
            	//$returnObject['unit_fullName'] = $_POST['default_conversion_value_name'];
				$returnObject['unit_fullName'] = $unit_name['unit_singular'];
			}else{
				$returnObject['unit_fullName'] = $unit_name['unit_plural'];
			}
			
			$returnObject['converstion_string'] = '';
			if(!empty($unit_data)){
				$returnObject['converstion_string'] = $_POST['quantity'].' '.$_POST['unit_fullName'].' = '.$returnObject['discount_quantity'].' '.$_POST['default_conversion_value_name'];
			}
			
              
            $NonWholeUnitprice = $_POST['base_price'] * $_POST['conversion_price'];
            $unit_bulk_pricing[] = array('quantity'=>"Non-Wholesale",'price'=> $this->currency->format($NonWholeUnitprice));

            foreach($discounts as $key=>$discount){
                $quantity = $discount['quantity'] * $_POST['conversion_price'];
                $discount_multiplier = $this->getPercentDiscountMultiplier($quantity, $product_id);
                $price = ($discount_multiplier*$quantity*$_POST['base_price'])/$discount['quantity'];
                $quantity = $discount['quantity'];
                 if ($key == 0) {
                        $nextArray = current($discounts);
                    } else {
                        $nextArray = next($discounts);
                    }
                    if (!empty($nextArray)) {
                        $nextQuan = $nextArray['quantity'];
                        $nextQuan--;
                        $nextQuan = round($quantity/$returnObject['discount_quantity'],2)." - " . round($nextQuan/$returnObject['discount_quantity'],2);
                    } else {
                       $nextQuan =round($quantity/$returnObject['discount_quantity'],2)."+";
                    }
                    $nextQuan = $nextQuan." ".substr($_POST['plural_unit'], 0, strpos($_POST['plural_unit'], ' '));
    //            $quant_text = 
                $unit_bulk_pricing[] = array('quantity'=>$nextQuan,'price'=>$this->currency->format($price));
             }
            $returnObject['unit_bulk_pricing'] = $unit_bulk_pricing; 
        } else {
            if (isset($_POST['simplePrice'])) {
                
				$discount_price = $this->getdiscountedprice($_POST['quantity'], $product_id);
			    $final_price = round($discount_price * $_POST['quantity'],2);
			    $returnObject['show_tooltip'] = 0;
                $returnObject['calc_price'] = $this->currency->format($final_price,'','',false);
				
				/*$discount_multiplier = $this->getPercentDiscountMultiplier($_POST['quantity'], $product_id);
                $final_price = $_POST['base_price'] * $_POST['quantity'];
                $final_price = $discount_multiplier * $final_price;
                $returnObject['show_tooltip'] = 0;
                $returnObject['calc_price'] = $this->currency->format($final_price);*/
				
				if($_POST['quantity'] == 1){
            		$returnObject['unit_fullName'] = $unit_name['unit_singular'];
				}else{
					$returnObject['unit_fullName'] = $unit_name['unit_plural'];
				}
                $returnObject['converstion_string'] = '';
				//$returnObject['unit_fullName'] = $_POST['plural_unit'];
                $returnObject['unit_bulk_pricing'] = 0;
            }
        }
        if ($_POST['quantity'] > 1) {
            $returnObject['quantity'] = $_POST['quantity'];
        } else {
            $returnObject['quantity'] = '';
        }

        $returnObject['calc_price'] = $this->currency->format($returnObject['calc_price']);
        echo json_encode($returnObject);
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
	
    function getUnitConversionValue($optionValueId) {
        
    }

    function getPercentDiscountMultiplier($discount_qty, $product_id) {
        $this->load->model('catalog/product');
        ($discount_qty < 1) ? $discount_qty = 1 : $discount_qty = $discount_qty;
        $discount_result = $this->model_catalog_product->getProductDiscountByQuantity($product_id, $discount_qty);
        if (isset($discount_result['discount'])) {
            return ($discount_result['discount'] / $discount_result['base_price']);
        } else {
            return 1;
        }
    }

    function getProductPrice($discount_qty, $product_id) {
        $discount_multiplier = $this->getPercentDiscountMultiplier($discount_qty, $product_id);
    }
    
    private function give_discount($price,  $percentage){
        $percentage_price = $price * ($percentage/100);
        return $price - $percentage_price;
    }
    
    public function formatProPrice() {
        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $returnObject = array();
            $product_id = $this->request->post['product_id'];
            //$quantity = $this->request->post['quantity'];
            $quantity = isset($this->request->post['quantity'])?$this->request->post['quantity']:1;
            $option_data['price'] = $this->request->post['option_price'];
            $option_data['option_id'] = $this->request->post['option_id'];
            $conversion_price = $this->request->post['conversion_price']=='null'?NULL:$this->request->post['conversion_price'];
            $price = ($this->cart->geProductCalculatedPrice($product_id, $quantity, $conversion_price, $option_data))*$quantity;
            $returnObject['price'] = $this->currency->format($price);
            $returnObject['status'] = 1;
            echo json_encode($returnObject);
        }
    }


    public function question($product_id = null, $page = 1, $ajax = true, $per_page = null) {
        $this->language->load('product/product');

		$this->load->model('catalog/qa');
		$this->load->model('tool/image');

        $data['text_question'] = $this->language->get('text_question');
        $data['text_answer'] = $this->language->get('text_answer');
        $data['text_no_questions'] = $this->language->get('text_no_questions');
        $data['text_no_answer'] = $this->language->get('text_no_answer');
        $data['qa_question_author'] = $this->config->get('qa_question_author');
        $data['qa_question_date'] = $this->config->get('qa_question_date');
        $data['qa_answer_author'] = $this->config->get('qa_answer_author');
        $data['qa_answer_date'] = $this->config->get('qa_answer_date');

        if ($ajax) {
            if (isset($this->request->get['page'])) {
                $page = $this->request->get['page'];
            } else {
                $page = 1;
            }

            $product_id = $this->request->get['product_id'];
        }

        $data['qas'] = array();
		if (is_null($per_page)) $per_page = (int)$this->config->get('qa_items_per_page');
		
		if( isset($this->request->get['gp_product']) )
		{
			$results = $this->model_catalog_qa->getGroupQAsByProductId($product_id, ($page - 1) * $per_page, $per_page);
		    $qa_total = $this->model_catalog_qa->getTotalGroupQAsByProductId($product_id);
		} else {
		   $results = $this->model_catalog_qa->getQAsByProductId($product_id, ($page - 1) * $per_page, $per_page);
		   $qa_total = $this->model_catalog_qa->getTotalQAsByProductId($product_id);
		}   
        foreach ($results as $result) {
            $data['qas'][] = array(
                'q_author'   => $result['q_author'],
                'a_author'   => $result['a_author'],
                'question'   => strip_tags($result['question']),
				'answer'     => $result['answer'],
				'image'		 	=> $this->model_tool_image->resize($result['image'], $this->config->get('config_image_wishlist_width'), $this->config->get('config_image_wishlist_height')),
				'name'		 	=> $result['name'],
				'sku'		 	=> $result['sku'],
				'product_options' => $this->model_catalog_qa->getProductOptions($result['product_id']),
				'price'			=> $result['price'],
				'href' 			=> $this->url->link('product/product', 'product_id=' . $result['product_id']),
                'date_asked' => date($this->language->get('date_format_short'), strtotime($result['date_asked'])),
                'date_answered' => date($this->language->get('date_format_short'), strtotime($result['date_answered']))
            );
          }

        $qa_total = $this->model_catalog_qa->getTotalQAsByProductId($product_id);

        $pagination = new Pagination();
        $pagination->total = $qa_total;
        $pagination->page = $page;
        $pagination->limit = ($per_page) ? $per_page : $qa_total;
		$pagination->text = $this->language->get('text_pagination');
		if( isset($this->request->get['gp_product']) )
		{
			$pagination->url = $this->url->link('product/product/question', 'product_id=' . $product_id . '&gp_product=1&page={page}');
		} else {
			$pagination->url = $this->url->link('product/product/question', 'product_id=' . $product_id . '&page={page}');
		}

        $data['pagination'] = $pagination->render();
			
				foreach ($pagination->prevnext() as $pagelink) {
					$this->document->addLink($pagelink['href'], $pagelink['rel']);
				}
				
			
				foreach ($pagination->prevnext() as $pagelink) {
					$this->document->addLink($pagelink['href'], $pagelink['rel']);
				}
				
		if($ajax){
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/qa.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/qa.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/product/qa.tpl', $data));
			}
		}else{
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/qa.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/product/qa.tpl', $data);
			} else {
				return $this->load->view('default/template/product/qa.tpl', $data);
			}
		}
    }

    public function ask() {
        $this->language->load('product/product');

        $this->load->model('catalog/qa');

        $json = array();

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateQuestion()) {
			
			$this->model_catalog_qa->addQA($this->request->get['product_id'], $this->request->post);

            $json['success'] = $this->language->get('text_success_question');
        } else {
			$json['error'] = $this->error['message'];
        }

        $this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
    }

    private function validateQuestion() { 
		$this->error['message'] = '';
        if ((strlen(utf8_decode($this->request->post['name'])) < 3) || (strlen(utf8_decode($this->request->post['name'])) > 25)) {
            $this->error['message'] .= $this->language->get('error_q_author') . "<br>";
        }

        if ( empty( $this->request->post['email'] ) || !filter_var( $this->request->post['email'], FILTER_VALIDATE_EMAIL ) ) {
            $this->error['message'] .= $this->language->get('error_q_email') . "<br>";
        }

        if ((strlen(utf8_decode($this->request->post['question'])) < 15) || (strlen(utf8_decode($this->request->post['question'])) > 1000)) {
            $this->error['message'] .= $this->language->get('error_question') . "<br>";
		}
		
		// Captcha
		if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('question', (array)$this->config->get('config_captcha_page'))) {
			$captcha = $this->load->controller('captcha/' . $this->config->get('config_captcha') . '/validate');

			if ($captcha) {
				$this->error['message'] .= $captcha;
			}
		}

        if (!$this->error['message']) {
            return true;
        } else {
            return false;
        }
    }
}
