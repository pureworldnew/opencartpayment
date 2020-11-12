<?php 
class ControllerModuleLastViewed extends Controller  {
    private $moduleVersion;
    private $modulePath;
    private $moduleName;
    private $moduleModel;

    public function __construct($registry) {
        parent::__construct($registry);

        $this->config->load('isenselabs/lastviewed');

        $this->moduleVersion = $this->config->get('lastviewed_version');
        $this->modulePath = $this->config->get('lastviewed_path');
        $this->moduleName = $this->config->get('lastviewed_name');
        $this->moduleModel = $this->config->get('lastviewed_model');
        $this->load->model($this->modulePath);
    }

    private function getConfigTemplate(){
        if(version_compare(VERSION, '2.2.0.0', '<')) {
            return $this->config->get('config_template');
        } else {
            return  $this->config->get($this->config->get('config_theme') . '_directory');
        }
    }

    private function getProductDescription() {
        if(version_compare(VERSION, '2.2.0.0', '<')) {
            return $this->config->get('config_product_description_length');
        } else {
            return  $this->config->get($this->config->get('config_theme') . '_product_description_length');
        }
    }
    
    public function index() {

        $data['modulePath'] = $this->modulePath;

        $this->document->addStyle('catalog/view/theme/' . $this->getConfigTemplate() . '/stylesheet/'.$this->moduleName.'.css');
    
        $languageVariables= array('heading_title', 'add_to_cart');

        foreach ($languageVariables as $variable) {
            $data[$variable] = $this->language->get($variable);
        }
        
        $data['button_cart'] = $this->language->get('button_cart');
        $data['button_wishlist'] = $this->language->get('button_wishlist');
        $data['button_compare'] = $this->language->get('button_compare');
        $this->load->language('product/product');
        $data['text_tax'] = $this->language->get('text_tax');
        
        $this->load->model('catalog/product');
        $this->load->model('tool/image');
        
        $data['products'] = array();
        
        $moduleSetting = $this->{$this->moduleModel}->getSetting($this->moduleName, $this->config->get('config_store_id'));
        $data['moduleData'] = isset($moduleSetting[$this->moduleName]) ? $moduleSetting[$this->moduleName] : array();
        
        if(!isset($data['moduleData']['PanelName'][$this->config->get('config_language')])){
            $data['PanelName'] = $data['heading_title'];
        } else {
            $data['PanelName'] = $data['moduleData']['PanelName'][$this->config->get('config_language')];
        }
        
        if($data['moduleData']['Track_method'] == 'session') {
            $reversed = $this->getProductsSavedBySession();
        } elseif ($data['moduleData']['Track_method'] == 'ip') {
            $reversed = $this->getProductsSavedByIP();
        }
        
        $i=0;
        $limit = isset($data['moduleData']['Limit']) ? $data['moduleData']['Limit'] : 4;
        $imageWidth = isset($data['moduleData']['ImageWidth']) ? $data['moduleData']['ImageWidth'] : '100';
        $imageHeight = isset($data['moduleData']['ImageHeight']) ? $data['moduleData']['ImageHeight'] : '100';
        if (isset($reversed)) {
            foreach ($reversed as $result) {
                $product_data = $this->model_catalog_product->getProduct($result);
                
                if ($product_data['image']) {
                    $image = $this->model_tool_image->resize($product_data['image'], $imageWidth, $imageWidth);
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', $imageHeight, $imageHeight);
                }

                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                   // $price = $this->currency->format($this->tax->calculate($product_data['price'], $product_data['tax_class_id'], $this->config->get('config_tax')),$this->session->data['currency']);
                    $price = $this->currency->format($this->tax->calculate($this->cart->geProductCalculatedPrice($product_data['product_id']), $product_data['tax_class_id'], $this->config->get('config_tax')));
                        $price_val = $this->cart->geProductCalculatedPrice($product_data['product_id']);
                } else {
                    $price = false;
                }

                if ((float)$product_data['special']) {
                    $special = $this->currency->format($this->tax->calculate($product_data['special'], $product_data['tax_class_id'], $this->config->get('config_tax')),$this->session->data['currency']);
                } else {
                    $special = false;
                }

                if ($this->config->get('config_tax')) {
                    $tax = $this->currency->format((float)$product_data['special'] ? $product_data['special'] : $product_data['price'],$this->session->data['currency']);
                } else {
                    $tax = false;
                }

                if ($this->config->get('config_review_status')) {
                    $rating = $product_data['rating'];
                } else {
                    $rating = false;
                }
                        
                $data['products'][] = array(
                    'product_id'  => $product_data['product_id'],
                    'thumb'       => $image,
                    'name'        => $product_data['name'],
                    'description' => utf8_substr(strip_tags(html_entity_decode($product_data['description'], ENT_QUOTES, 'UTF-8')), 0, $this->getProductDescription()) . '..',
                    'price'       => $price,
                    'special'     => $special,
                    'tax'         => $tax,
                    'unit' => ($product_data['unit_singular']? " per " . strtolower($product_data['unit_singular']):""),
                    'rating'      => $rating,
                    'href'        => $this->url->link('product/product', 'product_id=' . $product_data['product_id'])
                );
                
                $i++;
                if ($i==$limit)
                    break;
            }
        }

        $ajaxrequest = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

        if ($ajaxrequest == false) {
            if(isset($data['product_id'])) {
                $data['product_id'] = $this->request->get['product_id'];
            } else {
                $data['product_id'] = '';
            }

            if(version_compare(VERSION, '2.2.0.0', "<")) {
                if (file_exists(DIR_TEMPLATE . $this->getConfigTemplate() . '/template/'.$this->modulePath.'/'.$this->moduleName.'.tpl')) {
                    return $this->load->view($this->getConfigTemplate().'/template/'.$this->modulePath.'/'.$this->moduleName.'.tpl', $data);
                } else {
                    return $this->load->view('default/template/'.$this->modulePath.'/'.$this->moduleName.'.tpl', $data);
                }
            } else {
                   return $this->load->view($this->modulePath.'/'.$this->moduleName.'.tpl', $data);
            }

        } else {
            if(version_compare(VERSION, '2.2.0.0', "<")) {
                if (file_exists(DIR_TEMPLATE . $this->getConfigTemplate() . '/template/'.$this->modulePath.'.tpl')) {
                    return $this->load->view($this->getConfigTemplate().'/template/'.$this->modulePath.'.tpl', $data);
                } else {
                    return $this->load->view('default/template/'.$this->modulePath.'.tpl', $data);
                }
            } else {
                   return $this->load->view($this->modulePath.'.tpl', $data);
            }
        }       
    }

    public function getindex() {
        $this->response->setOutput($this->index());
    }

    private function getProductsSavedBySession() {
        $reversed = array();
        if(isset($this->session->data['lastviewed_products']))
        {
            $reversed = array_reverse($this->session->data['lastviewed_products']);
            if (isset($this->request->get['product_id'])) {
                if (($key = array_search($this->request->get['product_id'], $reversed)) !== false) unset($reversed[$key]);
            }
        }

        return $reversed;
    }

    private function getProductsSavedByIP() {
        $IP = $this->getVisitorIp();
        $products = $this->{$this->moduleModel}->getProductsSavedByIP($IP);

        if(!empty($products)) {
            $products = array_reverse($products);
        }

        return $products;
    }
    
    public function loglv() {
        $moduleSetting = $this->{$this->moduleModel}->getSetting($this->moduleName, $this->config->get('config_store_id'));
        $moduleData = isset($moduleSetting[$this->moduleName]) ? $moduleSetting[$this->moduleName] : array();

        if($moduleData['Track_method'] == 'session') {
            $this->trackBySession();
        } elseif ($moduleData['Track_method'] == 'ip') {
            $this->trackByIP();
        }       
    }

    private function trackByIP() {
        if (isset($this->request->get['product_id'])) {
            $product_id = $this->request->get['product_id'];
            $IP = $this->getVisitorIp();

            $this->{$this->moduleModel}->insertProductView($IP, $product_id);
        }
    }

    private function trackBySession() {
        // Session variable create
        if(!isset($this->session->data['lastviewed_products'])) {
            $this->session->data['lastviewed_products'] = array();
        }
        
        if (isset($this->request->get['product_id'])) {
            if (!in_array($this->request->get['product_id'], $this->session->data['lastviewed_products'])) {
                $this->session->data['lastviewed_products'][] = $this->request->get['product_id'];
            } else {
                foreach ($this->session->data['lastviewed_products'] as $k=>$v) {
                    if($v == $this->request->get['product_id']) {
                        unset($this->session->data['lastviewed_products'][$k]);
                        $this->session->data['lastviewed_products'][] = $this->request->get['product_id'];
                        $this->session->data['lastviewed_products'] = array_values($this->session->data['lastviewed_products']);
                    }
                }
            }
        }
    }

    public function getVisitorIp() {
        $ip = (!empty($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '';
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
    
        } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { 
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
    
        return $ip;
    }
    
    public function getCatalogURL($store_id){
        if(isset($store_id) && $store_id){
            $storeURL = $this->db->query('SELECT url FROM `'.DB_PREFIX.'store` WHERE store_id=' . $store_id)->row['url'];
        }elseif (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
            $storeURL = HTTPS_SERVER;
        } else {
            $storeURL = HTTP_SERVER;
        } 
        return $storeURL;
    }
}
?>
