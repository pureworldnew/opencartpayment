<?php

class ControllerCatalogMarketprice extends Controller {
    const default_option_id = 2;
    const default_option_name = 'Swarovski Series';
    const swarovski_option_id = 5;
    
    public function index() {
        $this->language->load('catalog/market_price');
        $cat_by_ids = (array) $this->_getOptionValuesById();
        $cat_by_name = (array) $this->_getOptionValuesByName();
        $cat_names = array_merge($cat_by_name,$cat_by_ids);
        $unique_cats = $this->_arrayUnique($cat_names);
        $get_cats =$this->_getCatMetalPrice($unique_cats);
        $data['success'] = ( !empty( $this->session->data['success'] ) ) ? $this->language->get('success') : '';
        $data['error_warning'] = ( !empty( $this->session->data['error_warning'] ) ) ? $this->language->get('text_error') : '';
        $this->session->data['success']=FALSE;
        $this->session->data['error_warning']=FALSE;
        $data['cats'] = $get_cats;
        
        $data['heading_title'] = $this->language->get('heading_title');
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_db_cron'] = 'Product rates update';
		$data['button_discount_update'] = 'Discount rates update';
        $data['text_name'] = $this->language->get('text_name');
        $data['action'] = $this->url->link('catalog/marketprice/updatePrice','token=' . $this->session->data['token'], 'SSL');
		$data['db_cron'] = HTTPS_CATALOG.'index.php?route=common/db_cron';
		$data['discount_update'] = $this->url->link('catalog/marketprice/updateDiscountRates','token=' . $this->session->data['token'], 'SSL');
/*        $this->template = 'catalog/market_price.tpl';
        $this->children = array(
                'common/header',
                'common/footer'
        );
	$this->response->setOutput($this->render());*/
	
		$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
	
			$this->response->setOutput($this->load->view('catalog/market_price.tpl', $data));
    }
    
    public function updatePrice() {
        $market_price_data = $this->request->post['market_price'];
        if(!empty($market_price_data)) {
            $this->load->model('catalog/market_price');
            $this->model_catalog_market_price->updatePrice($market_price_data);
            $this->session->data['success'] = TRUE;
            $this->index();
        } else {
            $this->session->data['error_warning'] = TRUE;
            $this->index();
        }
    }
	
	public function updateDiscountRates() {
            $this->load->model('catalog/market_price');
            $this->model_catalog_market_price->updateDiscountrates();
			die('Products Discount rates updated');
    }	
	

    private function _getOptionValuesById() {
        $query = "SELECT ovd.name,ov.market_price FROM "
                . "" .DB_PREFIX."option_value_description ovd LEFT JOIN "
                . "oc_option_value ov ON ovd.option_value_id = ov.option_value_id "
                . "WHERE ovd.option_id ='".self::default_option_id."'";
        $result = $this->db->query($query);
        if($result->num_rows > 0) {
            return $result->rows;
        } else {
            return false;
        }
    }
    private function _getCatMetalPrice($unique_cats) {
        $data=array();
        foreach($unique_cats as $unique_cat) {
            $query = "SELECT c.metal_price FROM ".DB_PREFIX."category c LEFT JOIN "
                    . "".DB_PREFIX."category_description cd ON c.category_id = "
                    . "cd.category_id WHERE cd.name LIKE '".$unique_cat['name']."%'";
            $result=$this->db->query($query);
            if(!$result) {
                echo mysql_error();
            }
            $data[]=array('name'=>$unique_cat['name'],'market_price'=>$result->row['metal_price']);
        }
        return $data;
    }
    private function _getOptionValuesByName() {
        $query = "SELECT CONCAT(od.name)as name,ov.market_price "
                . "FROM " .DB_PREFIX."option_value ov LEFT JOIN " .DB_PREFIX. 
                "option_description od ON od.option_id = ov.option_id LEFT JOIN "
                . "".DB_PREFIX."option_value_description ovd ON ovd.option_id=ov.option_id "
                ."WHERE od.name ='".self::default_option_name."' LIMIT 1";
        $result = $this->db->query($query);
        if($result->num_rows > 0) {
            return $result->rows;
        } else {
            return false;
        }
    }
    
    private function _arrayUnique($array) {
        $result = array_map("unserialize", array_unique(array_map("serialize", $array)));
        foreach ($result as $key => $value) {
            if (is_array($value)) {
                $result[$key] = $this->_arrayUnique($value);
            }
        }

        return $result;
    }
}
