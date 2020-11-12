<?php
class ControllerCommonHeader extends Controller {
	public function index() {
		// Analytics
		$this->load->model('extension/extension');

		// clearing customer cart if set from admin
		$this->cart->clearCustomerCart();
		if(isset($this->session->data['pos_cart'])){
			unset($this->session->data['pos_cart']);
		}
		if(isset($this->session->data['pos_products'])){
			unset($this->session->data['pos_products']);
		}
		$data['current_route'] = ( isset($this->request->get['route']) ) ? $this->request->get['route'] : "";
		$data['current_route_page'] = $data['current_route'];
		$data['search_page'] = ( isset($this->request->get['route']) && $this->request->get['route'] == 'product/search' ) ? true : false;

		if( !empty($_GET['sort']) || !empty($_GET['page']) || !empty($_GET['filtering']) || !empty($_GET['order']) || !empty($_GET['limit']) )
		{
			$data['no_index_this_page'] = true;
		} else {
			$data['no_index_this_page'] = false;
		}

		$data['analytics'] = array();

		$uri_string =str_replace("logout","account/tmdaccount", $_SERVER["REQUEST_URI"]);
		/*if($uri_string[0] == "/"){
			$uri_string = ltrim($uri_string, '/'); 
		}*/

		$data['current_route'] =$uri_string ;

		$analytics = $this->model_extension_extension->getExtensions('analytics');

		foreach ($analytics as $analytic) {
			if ($this->config->get($analytic['code'] . '_status')) {
				$data['analytics'][] = $this->load->controller('analytics/' . $analytic['code']);
			}
		}

		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->document->addLink($server . 'image/' . $this->config->get('config_icon'), 'icon');
		}
		
		$data['title'] = $this->document->getTitle();

		$data['base'] = $server;

              $this->load->model('setting/setting');
                
                $mmos_ajax_search = $this->model_setting_setting->getSetting('mmos_ajax_search', $this->config->get('config_store_id'));

                if(isset($mmos_ajax_search['mmos_ajax_search'])){
                
                    $data_ajax_search = $mmos_ajax_search['mmos_ajax_search'];

                    if ($data_ajax_search['status'] == '1') {
                        $this->document->addScript('catalog/view/javascript/mmos_ajax_search.js');
                    }
                }
            
		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		$data['links'] = $this->document->getLinks();

				
				foreach ($data['links'] as $link) { 
					if ($link['rel']=='canonical') {$hasCanonical = true;} 
					} 
				$data['canonical_link'] = '';
				$canonicals = $this->config->get('canonicals'); 
				if (!isset($hasCanonical) && isset($this->request->get['route']) && (isset($canonicals['canonicals_extended']))) {
					$data['canonical_link'] = $this->url->link($this->request->get['route']);					
					}
				
				
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts();
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');

		$data['name'] = $this->config->get('config_name');

		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] = '';
		}

		if(isset($this->session->data['loginwarning']))
        {
            $data['loginwarning'] = $this->session->data['loginwarning'];
            unset($this->session->data['loginwarning']);
        } else {
            $data['loginwarning'] = "";
        }

		$this->load->language('common/header');

		$data['text_home'] = $this->language->get('text_home');

		// Wishlist
		if ($this->customer->isLogged()) {
			$this->load->model('account/wishlist');

			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), $this->model_account_wishlist->getTotalWishlist());
		} else {
			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		}

		$data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
		//$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', 'SSL'), $this->customer->getFirstName(), $this->url->link('account/logout', '', 'SSL'));

		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->customer->getFirstName(), '<a href="'.$this->url->link('account/logout', '', 'SSL').'">Logout</a>');
        $data['username'] = $this->customer->getFirstName();

		$data['text_account'] = $this->language->get('text_account');
		$data['text_register'] = $this->language->get('text_register');
		$data['text_login'] = $this->language->get('text_login');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_transaction'] = $this->language->get('text_transaction');
		$data['text_download'] = $this->language->get('text_download');
		$data['text_logout'] = $this->language->get('text_logout');
		$data['text_checkout'] = $this->language->get('text_checkout');
		$data['text_category'] = $this->language->get('text_category');
		$data['text_all'] = $this->language->get('text_all');

		$data['home'] = $this->url->link('common/home');
		$data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
		$data['logged'] = $this->customer->isLogged();
		$data['account'] = $this->url->link('account/account', '', 'SSL');
		$data['register'] = $this->url->link('account/register', '', 'SSL');
		$data['login'] = $this->url->link('account/login', '', 'SSL');
		$data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');
		$data['order'] = $this->url->link('account/order', '', 'SSL');
		$data['transaction'] = $this->url->link('account/transaction', '', 'SSL');
		$data['download'] = $this->url->link('account/download', '', 'SSL');
		$data['logout'] = $this->url->link('account/logout', '', 'SSL');
		$data['shopping_cart'] = $this->url->link('checkout/cart');
		$data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
		$data['contact'] = $this->url->link('information/contact');
		$data['telephone'] = $this->config->get('config_telephone');

							if($this->config->get('wk_quick_order_status')){
								$data['quick_order'] = $this->url->link('product/wk_quick_order');
								$data['quick_order_text'] = $this->language->get('quick_order_text');
							}
            
		$data['help_url'] = $this->url->link('information/information', 'information_id=' . 5, 'SSL');
		$status = true;

		if (isset($this->request->server['HTTP_USER_AGENT'])) {
			$robots = explode("\n", str_replace(array("\r\n", "\r"), "\n", trim($this->config->get('config_robots'))));

			foreach ($robots as $robot) {
				if ($robot && strpos($this->request->server['HTTP_USER_AGENT'], trim($robot)) !== false) {
					$status = false;

					break;
				}
			}
		}
		
		
		
		$data['categories'] = array();

		$this->load->model('catalog/category');
		$this->load->model('catalog/product');


		$categories = $this->model_catalog_category->getCategories(0);

		foreach ($categories as $category) {
			if ($category['top']) {
				// Level 2
				$children_data = array();

				$children = $this->model_catalog_category->getCategories($category['category_id']);

				foreach ($children as $child) {
					$filter_data = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => true
					);

					
				if($this->model_catalog_product->virtualStatus() && $child['category_id'] == $this->model_catalog_product->virtualId()) {
				
				// Latest virtual category
				$getvirtualconf = $this->config->get('latest_virtualcat');
				$productlimit['limit'] = $getvirtualconf['limit'] == 0 || $getvirtualconf['limit'] > $this->model_catalog_product->getTotalProducts() ? $this->model_catalog_product->getTotalProducts() : $getvirtualconf['limit'];

				if($this->model_catalog_product->virtualSortOption()) {
					// Count Date limited products
					$datelimit = $this->model_catalog_product->getLatestProductscountbydate();
	
					// If Date limit, use that instead of Product limit
					$productlimit['limit'] = $datelimit != 0 ? $datelimit : $productlimit['limit'];
				}

				$children_data[] = array( 
					'name' => $child['name'] . ($this->config->get('config_product_count') ? ' ('.$productlimit['limit'].')' : ''), 
					'href' => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
				);
				} else {
				$children_data[] = array(
						'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
						'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
					);
				}
			
				}

				// Level 1
				$data['categories'][] = array(
					'name'     => $category['name'],
					'children' => $children_data,
					'column'   => $category['column'] ? $category['column'] : 1,
					'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
				);
			}
		}
		//////get for more categories path
		$more_cat = $this->config->get('supermenu_more') ? $this->config->get('supermenu_more'):array();
		$more = array();
		foreach ($more_cat as $mid) {
			$_more = $this->model_catalog_category->getCategory($mid);
			if ($_more){
				$more[] = array(
					'name'     => $_more['name'],
					'href'     => $this->url->link('product/category', 'path=' . $_more['category_id'])
				);
			}
		}
		$data['categories'][] = array(
					'name'     => "More",
					'children' => $more,
					'column'   => 1,
					'href'     => '#'
		);
		//////////////////////////////////////////////////////////////////////////////////////////

		$data['language'] = $this->load->controller('common/language');
		$data['currency'] = $this->load->controller('common/currency');
		//$data['search'] = $this->load->controller('common/search');
		if (isset($this->request->get['search'])) {
			$data['search'] = $this->request->get['search'];
		} else {
			$data['search'] = '';
		}
		$data['cart'] = $this->load->controller('common/cart');
		$data['cart_mobile'] = $this->load->controller('common/cartmobile');
		$data['quick_login'] = $this->load->controller('common/quick_login');
		$data['quick_login_action'] = $this->url->link('common/quick_login');
		$data['ecquickbuy'] = array();//$this->load->controller('module/ecquickbuy');
		$data['supermenu'] = $this->load->controller('module/supermenu');
		$data['supermenu_settings'] = $this->load->controller('module/supermenu_settings');
		// For page specific css
		if (isset($this->request->get['route'])) {
			if (isset($this->request->get['product_id'])) {
				$class = '-' . $this->request->get['product_id'];
			} elseif (isset($this->request->get['path'])) {
				$class = '-' . $this->request->get['path'];
			} elseif (isset($this->request->get['manufacturer_id'])) {
				$class = '-' . $this->request->get['manufacturer_id'];
			} else {
				$class = '';
			}

			$data['class'] = str_replace('/', '-', $this->request->get['route']) . $class;
			// For Contact Page
			if ( $this->request->get['route'] == 'information/contact' )
			{
				$data['include_contact_js'] = true;
			} else {
				$data['include_contact_js'] = false;
			}

		} else {
			$data['class'] = 'common-home';
			$data['include_contact_js'] = false;
		}
		
		$data['callme'] = $this->convertTo();
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/common/header.tpl', $data);
		} else {
			return $this->load->view('default/template/common/header.tpl', $data);
		}
	}
	
	 /*
     * Functio to get category details using product id
     *  @return array $categories
     */

    public function getCategoryById($product_id) {
        $this->load->model('catalog/product');
        $this->load->model('catalog/category');
        $categories = array();
        $product_info = $this->model_catalog_product->getCategories($product_id);
        foreach ($product_info as $p_info) {
            $category_info = $this->model_catalog_category->getCategoryByCid($p_info['category_id']);
            if (!empty($category_info)) {
                $categories[] = $category_info['name'];
            }
        }
        return $categories;
    }

    /*
     * TimeZone convert and send message in response
     */

    public function convertTo() {
        date_default_timezone_set('America/Los_Angeles');
//                date_default_timezone_set('Asia/Calcutta');
        $current_time = time();
        $start_time = strtotime("09:00:00");
        $end_time = strtotime("17:00:00");
        if ($current_time >= $start_time && $current_time <= $end_time) {
            return "call us";
        } else {
            return "Feel Free to Call During Business Hours, or send us an <a href='?route=information/contact'>email</a> at anytime";
        }
    }

    /*
     * Ajax AutoComplete Search 
     * @return array json
     */

    public function ajaxsearch() {
        $this->load->model('tool/image');
        $this->load->model('catalog/product');
        $filter_name = $this->request->get['q'];
        $array = array();
        $data = array(
            'filter_name' => $filter_name
        );
        $results = $this->model_catalog_product->getProducts($data);
        $category = array();
        foreach ($results as $result) {
//            $category = $this->getCategoryById($result['product_id']);
            $link = '?route=product/product&product_id=' . $result['product_id'];
            if ($result['image']) {
                $image = $this->model_tool_image->resize($result['image'], 50, 50);
            } else {
                $image = false;
            }
//            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
//                $price = $this->currency->format($this->tax->calculate($this->cart->geProductCalculatedPrice($result['product_id']),$result['tax_class_id'], $this->config->get('config_tax')));
//            } else {
//                $price = false;
//            }
//
//            if ((float) $result['special']) {
//                $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
//            } else {
//                $special = false;
//            }
            $array[] = array('product_link' => $link, 'product_id' => $result['product_id'], 'pname' => $result['name'], 'image' => $image);
//            $array[] = array('cname' => $category, 'product_link' => $link, 'product_id' => $result['product_id'], 'pname' => $result['name'], 'image' => $image, 'model' => $result['model'], 'quantity' => $result['quantity'], 'price' => $price, 'special' => $special, 'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 100) . '..');
        }
        $this->response->setOutput(json_encode($array));
    }

    /*
     * Manipulation on free shipping if order total is greater than 200$.
     * @param $qualifiedAmount
     */

    private function freeShipping() {
        $hasQualifiedText = "This Order Qualifies for Free Ground Shipping!";
        $hasNotQualifiedText = "until you qualify for Free Ground Shipping!";
        $qualifiedAmount = 200;
        $cartTotal = $this->cart->getTotal();
        if ($cartTotal >= $qualifiedAmount) {
            return $hasQualifiedText;
        } else if ($cartTotal > 0 && $cartTotal < $qualifiedAmount) {
            $qualify_amt = $qualifiedAmount - $cartTotal;
            return $this->currency->format($qualify_amt) . " " . $hasNotQualifiedText;
        } else {
            return "Free Shipping to anywhere in the USA for orders over $200!";
            //return "FREE shipping on your first order!";
        }
    }

    // Convert dotted IP address into IP number in long
    function Dot2LongIP($IPaddr) {

        if ($IPaddr == "") {
            return 0;
        } else {
            $ips = explode(".", "$IPaddr");
            return ($ips[3] + $ips[2] * 256 + $ips[1] * 256 * 256 + $ips[0] * 256 * 256 * 256);
        }
    }

    //Get Location By Ip
    public function getRegionByIp($host) {

        $this->load->model('catalog/iplocation');
        $ipAddr = $this->Dot2LongIP($host);
        $data = $this->model_catalog_iplocation->getRegion($ipAddr);
        return $data;
    }

}
