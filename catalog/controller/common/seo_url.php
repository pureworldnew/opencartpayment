<?php

class ControllerCommonSeoUrl extends Controller {

    public function index() {
        // Add rewrite to url class
        if ($this->config->get('config_seo_url')) {
            $this->url->addRewrite($this);
        }

        $this->load->model('catalog/seo_url');
        $lang = array('en', 'es', 'ja');


        // Decode URL
        if (isset($this->request->get['_route_'])) {
            /*
             * Quick view 301 redirects and group products redirects.
             * ASM
             */
            if (isset($this->request->get['view']) && !isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                header("HTTP/1.1 301 Moved Permanently");
                header("Location: " . $this->request->get['_route_']);
            }

            $db_redirect = $this->model_catalog_seo_url->getDbRedirectLink($this->request->get['_route_']);
            if ($db_redirect) {
                if ($db_redirect == 'home') {
                    $db_redirect = '';
                }
                $move_url = $this->config->get('config_url') . $db_redirect;
                header('Location: ' . $move_url, true, 301);
                die;
            }

            if (isset($this->request->get['search_query'])) {
                $move_url = $this->config->get('config_url') . 'index.php?route=product/search&search';
                header('Location: ' . $move_url, true, 301);
                die;
            }

            $parts = explode('/', $this->request->get['_route_']);

            if (!isset($parts[1]) || empty($parts[1])) {
                if (in_array($parts[0], $lang)) {
                    $move_url = $this->config->get('config_url');
                    header('Location: ' . $move_url, true, 301);
                    die;
                }
            }

            foreach ($parts as $part) {
                /*
                 * Pestashop 301 redirects to opencart redirects.
                 * ASM
                 */
				if (strrpos($part, "PR-") !== false) {

					$price_parts=explode("-",$part);

					$part=$price_parts[0];

				}	
				
                $check_var = '';
                $c_or_p = '';
                $part = str_replace(".html", "", $part);
                $res_id = explode('-', $part);
                $check_var = $res_id[0];
                unset($res_id[0]);
                $link_rewrite = trim(implode("-", $res_id));

                if (isset($this->request->get['id_product']) || isset($this->request->get['id_category']) || !empty($check_var) && !empty($link_rewrite)) {
                    if (isset($this->request->get['id_product'])) {
                        $query = 'product_id=' . $this->request->get['id_product'];
                    } else if (isset($this->request->get['id_category'])) {
                        $query = 'category_id=' . $this->request->get['id_category'];
                    } else if (!empty($check_var)) {
                        $return_data = $this->model_catalog_seo_url->productOrCaregory($check_var, $link_rewrite);
                        $query = $return_data['link'];
                        $c_or_p = $return_data['c-or-p'];
                    }
                    if (!empty($query)) {
                        $part = $this->model_catalog_seo_url->seoUrlkeyword($query);
                        $move_url = $this->config->get('config_url') . $part;
                        if (isset($this->request->get['id_category']) || $c_or_p == 1) {
                            header('Location: ' . $move_url, true, 301);
                            die;
                        }
                    }
                }

                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($part) . "'");
                if ($query->num_rows) {
                    $url = explode('=', $query->row['query']);
                    if ($url[0] == 'product_id') {
                        $group_product_id = $this->model_catalog_seo_url->isProductGrouped($url[1]);
                        if ($group_product_id) {
                            $query_1 = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . $group_product_id . "'");
                            $move_url = $this->config->get('config_url') . str_replace($part, "", $this->request->get['_route_']) . $query_1->row['keyword'];
                            //appending id 
                            if (isset($this->request->get['gp_product_id'])) {
                                $move_url .= '?gp_product_id=' . $this->request->get['gp_product_id'];
                            }
                            
                            if (isset($this->request->get['view'])) {
                                $move_url = $move_url . '&view';
                            }
                            header('Location: ' . $move_url, true, 301);
                            die;
                        } else if (isset($this->request->get['id_product']) || $c_or_p == 2) {
                            $move_url = $this->config->get('config_url') . $part;
                            header('Location: ' . $move_url, true, 301);
                            die;
                        } else { 
                            $this->request->get['product_id'] = $url[1];
                            if (isset($this->request->get['gp_product_id'])) 
                            {
                                $cat_path = $this->getProductToCategoryPath($this->request->get['gp_product_id']);
                                $move_url = $this->url->link('product/product', 'path=' . $cat_path . '&product_id=' . $this->request->get['product_id']); 
                                $move_url .= '?gp_product_id=' . $this->request->get['gp_product_id'];

                                if($move_url !=  $this->config->get('config_url') . $this->request->get['_route_'] . '?gp_product_id=' . $this->request->get['gp_product_id'])
                                {
                                    header('Location: ' . $move_url, true, 301);
                                    die;
                                }
                            }
                            else {
                                $cat_path = $this->getProductToCategoryPath($this->request->get['product_id']);
                                $move_url = $this->url->link('product/product', 'path=' . $cat_path . '&product_id=' . $this->request->get['product_id']); 
                                if($move_url !=  $this->config->get('config_url') . $this->request->get['_route_'])
                                {
                                    header('Location: ' . $move_url, true, 301);
                                    die;
                                }
                            }
                        }
                    }

                    if ($url[0] == 'category_id') {
                        if (!isset($this->request->get['path'])) {
                            $this->request->get['path'] = $url[1];
                        } else {
                            $this->request->get['path'] .= '_' . $url[1];
                        }
                    }
					if ($url[0] == 'filter_width') { $this->request->get['filter_width'] = $url[1];}
					if ($url[0] == 'filter_height') {$this->request->get['filter_height'] = $url[1];}					
					if ($url[0] == 'filter_length') {$this->request->get['filter_length'] = $url[1];}					
					if ($url[0] == 'filter_model') {$this->request->get['filter_model'] = $url[1];}					
					if ($url[0] == 'filter_sku') {$this->request->get['filter_sku'] = $url[1];}					
					if ($url[0] == 'filter_upc') {$this->request->get['filter_upc'] = $url[1];}					
					if ($url[0] == 'filter_location') {$this->request->get['filter_location'] = $url[1];}					
					if ($url[0] == 'filter_weight') {$this->request->get['filter_weight'] = $url[1];}					
					if ($url[0] == 'filter_ean') {$this->request->get['filter_ean'] = $url[1];}					
					if ($url[0] == 'filter_isbn') {$this->request->get['filter_isbn'] = $url[1];}					
					if ($url[0] == 'filter_mpn') {$this->request->get['filter_mpn'] = $url[1];}					
					if ($url[0] == 'filter_jan') {$this->request->get['filter_jan'] = $url[1];}	
					if ($url[0] == 'filter_rating') {$this->request->get['filter_rating'] = $url[1];}					
					if ($url[0] == 'filter_special') {$this->request->get['filter_special'] = $url[1];}	
					if ($url[0] == 'filter_stock') {$this->request->get['filter_stock'] = $url[1];}					
					if ($url[0] == 'filter_clearance') {$this->request->get['filter_clearance'] = $url[1];}	
					if ($url[0] == 'filter_arrivals') {$this->request->get['filter_arrivals'] = $url[1];}			
					$pos = strrpos($url[0], "filter_att");
						if ($pos !== false) {
						$data = array();
		                parse_str($query->row['query'], $data);
						foreach ($data['filter_att'] as $key=>$value){
						 if ($url[0] == 'filter_att['.$key.']') {$this->request->get['filter_att'][$key]= $url[1]; }
						}
						}
					$pos = strrpos($url[0], "filter_opt");
						if ($pos !== false) {
    					$data = array();
		                parse_str($query->row['query'], $data);
						foreach ($data['filter_opt'] as $key=>$value){
						 if ($url[0] == 'filter_opt['.$key.']') {
						$this->request->get['filter_opt'][$key]= $url[1];
						 }
						}
						}	
					if ($url[0] == 'filtering') {
						$this->request->get['filtering'] = $url[1];
					}
                    if ($url[0] == 'manufacturer_id') {
                        $this->request->get['manufacturer_id'] = $url[1];
                    }

                    if ($url[0] == 'information_id') {
                        $this->request->get['information_id'] = $url[1];
                    }
                } else {
                    $this->request->get['route'] = 'error/not_found';
                }
            }


			if (strpos($this->request->get['_route_'], '/page/') !== false) {
					$this->request->get['page'] = str_replace('/page/','',substr($this->request->get['_route_'], strpos($this->request->get['_route_'], '/page/')));					
				}			
			

			if (strpos($this->request->get['_route_'], 'tags/') !== false) {
					$this->request->get['route'] = 'product/search';
					$this->request->get['tag'] = str_replace('tags/','',$this->request->get['_route_']);
				}
			
            if (isset($this->request->get['product_id'])) {
                $this->request->get['route'] = 'product/product';

			$gp_tpl_q = $this->db->query("SELECT pg_type FROM ".DB_PREFIX."product_grouped_type WHERE product_id='".(int)$this->request->get['product_id']."' LIMIT 1");
			if($gp_tpl_q->num_rows){$this->request->get['route']='product/product_'.$gp_tpl_q->row['pg_type'];}
		


			} elseif ($this->request->get['_route_'] ==  'wishlist') { $this->request->get['route'] =  'account/wishlist';
			} elseif ($this->request->get['_route_'] ==  'contact') { $this->request->get['route'] =  'information/contact';
			} elseif ($this->request->get['_route_'] ==  'account') { $this->request->get['route'] =  'account/account';
			} elseif ($this->request->get['_route_'] ==  'sitemap') { $this->request->get['route'] =  'information/sitemap';
			} elseif ($this->request->get['_route_'] ==  'manufacturer') { $this->request->get['route'] =  'product/manufacturer';
			} elseif ($this->request->get['_route_'] ==  'affiliates') { $this->request->get['route'] =  'affiliate/account';
			} elseif ($this->request->get['_route_'] ==  'special') { $this->request->get['route'] =  'product/special';
			} elseif ($this->request->get['_route_'] ==  'login') { $this->request->get['route'] =  'account/login';
			} elseif ($this->request->get['_route_'] ==  'logout') { $this->request->get['route'] =  'account/logout';
			} elseif ($this->request->get['_route_'] ==  'register') { $this->request->get['route'] =  'account/register';
			
			
            } elseif (isset($this->request->get['path'])) {

        
      
                $this->request->get['route'] = 'product/category';

        
      
            } elseif (isset($this->request->get['manufacturer_id'])) {

       
      
                $this->request->get['route'] = 'product/manufacturer/info';

      
      
            } elseif (isset($this->request->get['information_id'])) {
                $this->request->get['route'] = 'information/information';
            }


				$route = '';
				$path = '';
				$exists = false;
				
				foreach ($parts as $part) {
					
					
					$route .= $part . '/';
					
					$path .= $part;
					
					if (is_dir(DIR_APPLICATION . 'controller/' . $path)) {
						$path .= '/';
					} elseif (is_file(DIR_APPLICATION . 'controller/' . $path . '.php')) {
						$exists = true;
					}
				}
				
				if ($exists == true) {
					$this->request->get['route'] = substr($route, 0, -1);
				}
			// Category Full Path
            if ( isset($this->request->get['route']) && $this->request->get['route'] == 'product/category' && isset($this->request->get['path']) ) {

                $pathx = explode('_', $this->request->get['path']);
                $pathx = end($pathx); 
                $full_path = $this->getCategoryPath($pathx);
                if( $this->request->get['path'] != $full_path )
                {
                    $move_url = $this->url->link('product/category', 'path=' . $full_path);
                    header('Location: ' . $move_url, true, 301);
                    die;
                }
            }
						
            if (!empty($this->request->get['route'])) {
               return new Action($this->request->get['route']);
            }
        }
        else {
                if (isset($this->request->get['route']) && $this->request->get['route'] == 'information/information' && !empty($this->request->get['information_id'])) {
                    $query_2 = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'information_id=" . $this->request->get['information_id'] . "'");

                    $keyword = !empty($query_2->row) ? $query_2->row['keyword'] : "";

                    if( !empty($keyword) )
                    {
                        $move_url = $this->config->get('config_url') . $keyword;
                        header('Location: ' . $move_url, true, 301);
                        die;
                    }
                }
        }
    }

    public function rewrite($link) {
        $url_info = parse_url(str_replace('&amp;', '&', $link));

        $url = '';

        $data = array();

			$extendedseo = $this->config->get('extendedseo');
			

        parse_str($url_info['query'], $data);
        $this->load->model('catalog/seo_url');

        foreach ($data as $key => $value) {
            if(isset($data['route'])){
				
            	if(
			
			($data['route'] == 'product/product' && $key == 'product_id') || ($data['route'] == 'product/product_grouped' && $key == 'product_id') || ($data['route'] == 'product/product_configurable' && $key == 'product_id') || ($data['route'] == 'product/product_bundle' && $key == 'product_id') || 
		 (($data['route'] == 'product/manufacturer/info' || $data['route'] == 'product/product') && $key == 'manufacturer_id') || ($data['route'] == 'information/information' && $key == 'information_id') || ($data['route'] == 'product/asearch' && $key == 'manufacturer_id')) {
      				
					//echo "SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int) $value) . "'";
				
                    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int) $value) . "'");
                    if ($query->num_rows) {
                        $url .= '/' . $query->row['keyword'];
                        unset($data[$key]);
                    }
                    if ($key == 'product_id') {
						
                        $product_in_group = $this->model_catalog_seo_url->checkIfGrouped($value);
                        if ($product_in_group) {
                            $data['gp_product_id'] = $value;
                        }
                    }

			} elseif (isset($data['route']) && $data['route'] ==   'common/home') { $url .=  '/';
			} elseif (isset($data['route']) && $data['route'] ==   'account/wishlist' && $key != 'remove') { $url .=  '/wishlist';
			} elseif (isset($data['route']) && $data['route'] ==   'information/contact') { $url .=  '/contact';
			} elseif (isset($data['route']) && $data['route'] ==   'account/account') { $url .=  '/account';
			} elseif (isset($data['route']) && $data['route'] ==   'information/sitemap') { $url .=  '/sitemap';
			} elseif (isset($data['route']) && $data['route'] ==   'product/manufacturer') { $url .=  '/manufacturer';
			} elseif (isset($data['route']) && $data['route'] ==   'affiliate/account') { $url .=  '/affiliates';
			} elseif (isset($data['route']) && $data['route'] ==   'product/special' && $key != 'page' && $key != 'sort' && $key != 'limit' && $key != 'order') { $url .=  '/special';
			} elseif (isset($data['route']) && $data['route'] ==   'account/login') { $url .=  '/login';
			} elseif (isset($data['route']) && $data['route'] ==   'account/logout') { $url .=  '/logout';
			} elseif (isset($data['route']) && $data['route'] ==   'account/register') { $url .=  '/register';
			

			} elseif ($data['route'] == 'product/search' && (isset($extendedseo['seotags'])) && ($key == 'filter_tag' || $key == 'tag')) {
						$url .= '/tags/' . $value;
						unset($data[$key]);
			
                } elseif ($key == 'path') {
                    $categories = explode('_', $value);

                    foreach ($categories as $category) {
                        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'category_id=" . (int) $category . "'");

                        if ($query->num_rows) {
                            $url .= '/' . $query->row['keyword'];
                        }
                    }

                    unset($data[$key]);
                }

				elseif ($key == 'route') {
					if ($data['route'] == 'common/home') {
						$url .= '/';
					} elseif ($data['route'] != 'product/product' && $data['route'] != 'product/product_grouped' && $data['route'] != 'product/asearch' && $data['route'] != 'product/category' && $data['route'] != 'product/manufacturer/info' && $data['route'] != 'information/information') {
						$url .= '/' . $data['route'];
					}
				} 
			
            }
        }
        

			$seopagination = $this->config->get('seopagination');				
			if ($key == 'page' && $url && (isset($seopagination['pagination']))) {
						$url .= '/page/' . $value;
						unset($data[$key]);
					}
			
        if ($url) {
            unset($data['route']);

            $query = '';
            if ($data) {
                foreach ($data as $key => $value) {
					
					if ($key == 'filter_att' || $key == 'filter_opt' ){
						foreach ($value as $key2 => $value2) {
							$query .= '&' . $key.'['.(string)$key2.']=' . rawurlencode((string)$value2);
						}
					}else{
						$query .= '&' . rawurlencode((string)$key) . '=' . rawurlencode((string)$value);
					}
                }

                if ($query) {
                    $query = '?' . trim($query, '&');
                }
            }

            return $url_info['scheme'] . '://' . $url_info['host'] . (isset($url_info['port']) ? ':' . $url_info['port'] : '') . str_replace('/index.php', '', $url_info['path']) . $url . $query;
        } else {
            return $link;
        }
    }

    public function getProductToCategoryPath($product_id)
	{
		$query = $this->db->query("SELECT ptc.category_id FROM " . DB_PREFIX . "product_to_category ptc INNER JOIN " . DB_PREFIX . "category_description cd  ON ptc.category_id = cd.category_id WHERE cd.language_id = 1 AND cd.name NOT IN ('Home', 'Latest') AND ptc.product_id = '" . (int) $product_id . "' Order By ptc.category_id LIMIT 1");
		$category_id = $query->row ? $query->row['category_id'] : 0;
		if($category_id)
		{
			$query2 = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_path WHERE category_id = '" . (int) $category_id . "' Order By level");
			if ( $query2->rows )
			{
				$path_array = array();
				foreach($query2->rows as $category)
				{
					$path_array[] = $category['path_id'];
				}
				return implode("_",$path_array);
			}
		}
		return "";
    }
    
    public function getCategoryPath($category_id)
	{
		if($category_id)
		{
			$query2 = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_path WHERE category_id = '" . (int) $category_id . "' Order By level");
			if ( $query2->rows )
			{
				$path_array = array();
				foreach($query2->rows as $category)
				{
					$path_array[] = $category['path_id'];
				}
				return implode("_",$path_array);
			}
		}
		return "";
	}

}

?>