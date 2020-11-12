<?php

class ControllerModuleMmosAjaxSearch extends Controller {

    public function index() {

        $json = array();

        if (isset($this->request->get['filter_name'])) {

            $this->load->model('catalog/product');

            $this->load->model('tool/image');

            $this->load->model('setting/setting');

            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            $mmos_ajax_search = $this->model_setting_setting->getSetting('mmos_ajax_search', $this->config->get('config_store_id'));

            $data_config = $mmos_ajax_search['mmos_ajax_search'];
            
 
            if (isset($data_config['limit'])) {
                $limit = $data_config['limit'];
            } else {
                $limit = 5;
            }

           
            $filter_data = array(
                'filter_name' => $filter_name,
                'start' => 0,
                'limit' => $limit
            );
          
            if (!empty($data_config['search'])) {
                if (in_array(2, $data_config['search'])) {
                    $filter_data['filter_tag'] = $filter_name;
                }
                if (in_array(3, $data_config['search'])) {
                    $filter_data['filter_description'] = $filter_name;
                }
            }
            
            $results = $this->model_catalog_product->getProducts($filter_data);
        
         

            foreach ($results as $result) {
                if (isset($data_config['image'])) {

                    $set_height = ($data_config['height'] / 2) - 10;

                    if ($result['image']) {
                        $image = $this->model_tool_image->resize($result['image'], $data_config['width'], $data_config['height']);
                    } else {
                        $image = $this->model_tool_image->resize('placeholder.png', $data_config['width'], $data_config['height']);
                    }
                } else {
                    $set_height = false;
                    $image = '';
                }


                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $price = false;
                }

                if ((float) $result['special']) {
                    $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $special = false;
                }

                $name = strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'));
                if (isset($data_config['maxtext']) && strlen($name) > $data_config['maxtext']) {
                    $name = utf8_substr($name, 0, $data_config['maxtext']) . '..';
                }
                
                $json[] = array(
                    'product_id' => $result['product_id'],
                    'name' => $name,
                    'model' => $result['model'],
                    'price' => $result['price'],
                    'href' => $this->url->link('product/product', 'product_id=' . $result['product_id']),
                    'image' => $image,
                    'price' => $price,
                    'special' => $special,
                    'setheight' => $set_height
                );
              
               
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
