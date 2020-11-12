<?php

class ControllerCatalogMarketp extends Controller {
    const default_option_id = 2;
    const default_option_name = 'Swarovski Series';
    const swarovski_option_id = 5;
    
    public function index() {
        $this->language->load('catalog/market_p');
        $cat_by_ids = (array) $this->_getOptionValuesById();
        $cat_by_name = (array) $this->_getOptionValuesByName();
        $cat_names = array_merge($cat_by_name,$cat_by_ids);
        $unique_cats = $this->_arrayUnique($cat_names);
        $get_cats =$this->_getCatMetalPrice($unique_cats);
        //$data['success'] = ( !empty( $this->session->data['success'] ) ) ? $this->language->get('success') : '';
        $data['error_warning'] = ( !empty( $this->session->data['error_warning'] ) ) ? $this->language->get('text_error') : '';
        //$this->session->data['success']=FALSE; 
        $this->session->data['error_warning']=FALSE;
        $data['cats'] = $get_cats;
        $data['token'] = $this->session->data['token'];
        
        $data['heading_title'] = $this->language->get('heading_title');
        $this->document->setTitle($this->language->get('heading_title'));
        $data['text_value'] = $this->language->get('text_value');
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_db_cron'] = 'Update Product Rates';
		$data['button_discount_update'] = 'Update Discount Rates';
        $data['text_name'] = $this->language->get('text_name');
        $data['action'] = $this->url->link('catalog/marketp/updatePrice','token=' . $this->session->data['token'], 'SSL');
		$data['db_cron'] = HTTPS_CATALOG.'index.php?route=common/db_cron_updated';
		$data['discount_update'] = $this->url->link('catalog/marketp/updateDiscountRates','token=' . $this->session->data['token'], 'SSL');
/*        $this->template = 'catalog/market_price.tpl';
        $this->children = array(
                'common/header',
                'common/footer'
        );
	$this->response->setOutput($this->render());*/
        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }
        
		$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
	
			$this->response->setOutput($this->load->view('catalog/market_p.tpl', $data));
    }

    public function updateMetalPrice()
    {
        $json = array();
        $name = $this->request->post['name'];
        $metal_price = $this->request->post['metal_price'];
        $option_value_id = $this->request->post['option_value_id'];
        $this->_updateOptionValueName($option_value_id, $name);
        $this->_updateCategoryMetalPrice($metal_price, $name);
        $this->session->data['success'] = "Multiplier Name/Multiplier Value Updated successfully.";
		$this->response->setOutput(json_encode($json));
    }


    public function deleteMetalPrice()
    {
        $json = array();
        $option_value_id = $this->request->get['option_value_id'];
        $this->_deleteOptionValue($option_value_id);
        $this->session->data['success'] = "Multiplier Name/Multiplier Value Deleted successfully.";
		$this->response->setOutput(json_encode($json));
    }


    public function _deleteOptionValue($option_value_id)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "option_value_description WHERE option_value_id = '" . (int)$option_value_id . "' AND option_id = 2");
        $this->db->query("DELETE FROM " . DB_PREFIX . "option_value WHERE option_value_id = '" . (int)$option_value_id . "' AND option_id = 2");
    }


    public function _updateOptionValueName($option_value_id, $name)
    {
        $name = trim($name);
        if( !empty($name) )
        {
            $this->db->query("UPDATE " . DB_PREFIX . "option_value_description SET name = '" . $this->db->escape($name) . "' WHERE option_value_id = '" . (int)$option_value_id . "'");
        }
    }

    public function _updateCategoryMetalPrice($metal_price, $name)
    {
        $name = trim($name);
        if( !empty($name) )
        {
            $query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "category_description WHERE name LIKE '".$name."%'"); 
            $category_id = $query->row ? $query->row['category_id'] : "";
            $this->db->query("UPDATE " . DB_PREFIX . "category SET metal_price = '" . (float)$metal_price . "' WHERE category_id = '" . (int)$category_id . "'");  
        }
    }
    
    public function updatePrice() {
        $market_price_data = $this->request->post['market_price']; 
        if( !empty($market_price_data) ) {
            $this->load->model('catalog/market_p');
            $this->model_catalog_market_p->updatePrice($market_price_data); 
            $this->model_catalog_market_p->updateProductsMultiplierValues($market_price_data); 
            $this->session->data['success'] = "All Multiplier Name/Multiplier Value Save & Updated successfully.";
            $this->response->redirect($this->url->link('catalog/marketp', 'token=' . $this->session->data['token'], 'SSL'));
        } else {
            $this->session->data['error_warning'] = TRUE;
            $this->response->redirect($this->url->link('catalog/marketp', 'token=' . $this->session->data['token'], 'SSL'));
        }
    }
	
	public function updateDiscountRates() { 
            $this->load->model('catalog/market_p');
            $this->model_catalog_market_p->updateDiscountrates();
            $this->session->data['success'] = "Products Discount rates updated.";
			$this->response->redirect($this->url->link('catalog/marketp', 'token=' . $this->session->data['token'], 'SSL'));
    }	
	

    private function _getOptionValuesById() {
        $query = "SELECT ovd.name,ov.market_price,ov.option_value_id FROM "
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
            $market_price = isset($result->row['metal_price']) ? $result->row['metal_price'] : NULL;
            if(!$result) {
                echo mysql_error();
            }
            $data[]=array('name'=>$unique_cat['name'],'market_price'=>$market_price, 'option_value_id'=>$unique_cat['option_value_id']);
        }
        return $data;
    }
    private function _getOptionValuesByName() {
        $query = "SELECT CONCAT(od.name)as name,ov.market_price,ov.option_value_id "
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
