<?php
ini_set("max_execution_time", 0);
ini_set("memory_limit", "10000M");
ini_set('default_socket_timeout', 600);
class ControllerCommonDbCronUpdated extends Controller {
    const default_option_id = 2;
    const default_option_name = 'Swarovski Series';
    const swarovski_option_id = 5;
    var $current_product_id = "";
    public function index() {
        $this->_updateProductPrices();
        $this->updateProductPrice();
		$this->updateProductDiscount();
    }
    
    
    /**
     * Function for getting all option values name against option_id=2 
     * and then creating categories based on those names
     * If category exists update its market price based on option value price 
     */
    private function _createUpdateCategories() {
        $cat_by_ids = (array) $this->_getOptionValuesById();
        $cat_by_name = (array) $this->_getOptionValuesByName();
        $cat_names = array_merge($cat_by_name,$cat_by_ids);
        $filtered_cats = $this->_filterCats($cat_names);
        if(isset($filtered_cats['new'])) {
            $this->_addCategory($filtered_cats['new']);
        }
        if(isset($filtered_cats['old'])) {
            $this->_updateCategory($filtered_cats['old']);
        }
    }
    
    /**
     * Function for adding a new category
     * @param type $cat_names array
     */
    private function _addCategory($cat_names) {
        foreach ($cat_names as $cat_name) {
            $cat_desc=$this->_getCategoryDescription($cat_name['name']);
            $cat_data=array('category_description'=>$cat_desc,'image'=>'',
                'status'=>1,'column'=>1,'parent_id'=>0,'sort_order'=>'',
                'keyword'=>'','metal_price'=>$cat_name['market_price']);
            $this->_saveCategory($cat_data);
            $this->_assignCategories($cat_name['option_value_id'],$cat_name['name']);
        }
    }
    /**
     * Function for updating existing categories market price based on its name
     * @param type $cat_names array
     */
    private function _updateCategory($cat_names) {
        foreach ($cat_names as $cat_name) {
            $this->_updateMarketPrice($cat_name['name'],
                    $cat_name['market_price']);
        }
    }
    /**
     * Function for executing update market price query
     * @param $cat_name string
     * @param $metal_price int
     */
    private function _updateMarketPrice($cat_name,$market_price) {
        $query="UPDATE ".DB_PREFIX."category c LEFT JOIN ".DB_PREFIX.""
                        . "category_description cd ON c.category_id ="
                        . "cd.category_id SET metal_price="
                        . "'".$market_price."' "
                        . " WHERE cd.name='".$cat_name."'";
           
        $this->db->query($query);
    }
    /**
     * for getting all option values id,name and market price for option_id='2'
     * @return array of option values on success and false if no rows found
     */
    private function _getOptionValuesById() {
        $query = "SELECT ovd.option_value_id,ovd.name,ov.market_price FROM "
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
    /**
     * for getting all option values id,name and market price give 
     * option value name
     * @return array of option values on success and false if no rows found
     */
    private function _getOptionValuesByName() {
        $query = "SELECT ov.option_value_id,CONCAT(od.name,ovd.name) as name,ov.market_price "
                . "FROM " .DB_PREFIX."option_value ov LEFT JOIN " .DB_PREFIX. 
                "option_description od ON od.option_id = ov.option_id LEFT JOIN "
                . "".DB_PREFIX."option_value_description ovd ON ovd.option_id=ov.option_id "
                ."WHERE od.name ='".self::default_option_name."'";
        $result = $this->db->query($query);
        if($result->num_rows > 0) {
            return $result->rows;
        } else {
            return false;
        }
    }
    /**
     * function for creating category description array
     * @param $category_name string
     * @return array
     */
    private function _getCategoryDescription($category_name) {
       $category_description[1] = array('name' =>
                $category_name, 'meta_description' => '',
                'meta_keyword' => '', 'description' => '');
        
        return $category_description;
    }

    /**
     * function for getting all langauge's id from databse
     * @return type array of langauge id's
     */
    private function _getLangs() {
        $query="SELECT language_id from ". DB_PREFIX."language where language_id=1";
        $result=$this->db->query($query);
        return $result->rows;
    }
    /**
     * Check for the existing categories, 
     * separate new and existing values
     * @param type $cat_names array
     * @return type array
     */
    private function _filterCats($cat_names) {
        $filtered_cats=array();
        foreach ($cat_names as $cat_name) {
            $query="SELECT category_id FROM oc_category_description cd  WHERE "
                    . "cd.name='".$cat_name['name']."'";
            $result = $this->db->query($query);
            if($result->num_rows>0) {
                $filtered_cats['old'][] = $cat_name;
            } else {
                $filtered_cats['new'][] = $cat_name;
            }
        }
        return $filtered_cats;
    }
    /**
     * Getting all rpoduct that have given option_value_id assigned
     * and thenm assigning newly created categories to them.  
     * @param $option_value_id int
     * @param $cat_name string
     */
    private function _assignCategories($option_value_id,$cat_name) {
        $category_id = $this->_getCategoryID($cat_name);
        $products = $this->_getOptionRelatedProducts($option_value_id);
        foreach ($products as $product) {
            $check_query="SELECT * FROM ".DB_PREFIX."product_to_category p2c "
                    . "WHERE p2c.product_id='".$product['product_id']."' "
                    . "AND category_id ='".$category_id."'";
            $check_result=  $this->db->query($check_query);
            if($check_result->num_rows == 0) {
                $query="INSERT INTO ".DB_PREFIX."product_to_category SET "
                        . "product_id='".$product['product_id']."',category_id "
                        . "='".$category_id."'";
                $this->db->query($query);
            }
        }
    }
    /**
     * get category ID from category name
     * @param $cat_name string
     * @return int
     */
    private function _getCategoryID($cat_name) {
        $query = "SELECT category_id FROM ". DB_PREFIX."category_description"
                . " WHERE name='".$cat_name."' AND language_id ='1'";
        $result = $this->db->query($query);
        return $result->row['category_id'];
    }
    /**
     * Get all products that have given option value id
     * @param type $option_value_id int
     * @return type array
     */
    private function _getOptionRelatedProducts($option_value_id) {
        $query = "SELECT product_id FROM ". DB_PREFIX."product_option_value"
                . " WHERE option_value_id ='".(int)$option_value_id."'";
        $result = $this->db->query($query);
        return $result->rows;
    }
    
   /**
    *  For setting labour_cost field equal to product price and truncating 
    * product price
    */
    private function _updateProductPrices() {
        $query="UPDATE ".DB_PREFIX."product p SET p.labour_cost=p.price, "
                 . "p.price='' WHERE is_updated IS NULL AND model != 'grouped'";
       //echo "<pre>"; print_r(); echo "</pre>"; exit;
        $this->db->query($query);
    }
    
   

    private function updateProductDiscount() {
            $query="UPDATE ".DB_PREFIX."product_discount pd LEFT JOIN "
                    . "".DB_PREFIX."product p ON pd.product_id=p.product_id SET "
                    . "pd.price=p.price - (p.price * (pd.discount_percent/100)) where pd.discount_percent > 0.00";
            $this->db->query($query);
    }
    private function _saveCategory($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "category SET parent_id = "
                . "'" . (int) $data['parent_id'] . "', `top` = '" . 
                (isset($data['top']) ? (int) $data['top'] : 0) . "', `column` = "
                . "'" . (int) $data['column'] . "', sort_order = '" . (int) 
                $data['sort_order'] . "', status = '" . (int) $data['status'] . 
                "', date_modified = NOW(), date_added = NOW(), "
                . "metal_price='".$data['metal_price']."'");

        $category_id = $this->db->getLastId();

        foreach ($data['category_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "category_description"
                    . " SET category_id = '" . (int) $category_id . "', "
                    . "language_id = '" . (int) $language_id . "', name = '" . 
                    $this->db->escape($value['name']) . "', meta_keyword = '" . 
                    $this->db->escape($value['meta_keyword']) . "', "
                    . "meta_description = '" . 
                    $this->db->escape($value['meta_description']) . "', "
                    . "description = '" . 
                    $this->db->escape($value['description']) . "'");
        }
    }
    public function updateProductPrice() {
        $get_product_query = $this->db->query("SELECT product_id FROM oc_product");
        foreach ($get_product_query->rows as $value) { 
            $this->current_product_id = $value['product_id'];
            $this->_processProduct();
        }
        die('success');
    }
    private function _processProduct(){
        $getPricesQuery = $this->db->query("SELECT p.labour_cost , p.unique_option_price, p.multiplier_value, c.metal_price FROM oc_category c LEFT JOIN oc_product_to_category p2c ON c.category_id=p2c.category_id LEFT JOIN oc_product p ON p.product_id = p2c.product_id WHERE (c.metal_price > 0 OR p.multiplier_value <> '')  AND p.product_id = $this->current_product_id");
       
        if(empty($getPricesQuery->rows)) {
            $this->_productWithoutOption();
        } else {
            $labour_cost = $getPricesQuery->row['labour_cost'];
            $unique_option_price = $getPricesQuery->row['unique_option_price'];
            $metal_price = $getPricesQuery->row['multiplier_value'] != '' ? (float)$getPricesQuery->row['multiplier_value'] : $getPricesQuery->row['metal_price'];
            $this->_productWithOption($labour_cost, $unique_option_price, $metal_price);
        }
    }
    
    private function _productWithoutOption() {
        $this->db->query("UPDATE oc_product set price = labour_cost where product_id = $this->current_product_id AND model != 'grouped'");
    }
     /**
     * Calculating product price 
     * Formula : price=labour_cost+unique_option_price*metal_price
     */
    private function _productWithOption($labour_cost, $unique_option_price, $metal_price) {
        $my_final_price = $labour_cost + ($unique_option_price * $metal_price);
        if(!$this->db->query("UPDATE oc_product SET price = '" . (float)$my_final_price . "', is_updated= 1 WHERE product_id = $this->current_product_id AND model != 'grouped'")) {
            //echo mysql_error()."<br>"; 
            echo "price ".$my_final_price."<br>";
            echo "prod_id ".$this->current_product_id;
           // die('here');
        }
    }
    
}