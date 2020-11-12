<?php

class ModelfeedCategoryDiscount extends Model {

    public function __construct($registry) {
        parent::__construct($registry);
        $discounts = array();
        $cust_group = '';
        $product_id = '';
        $calc_discounts = array();
        $count = 0;
    }

    public function selectCategory() {
        $sql = "SELECT " . DB_PREFIX . "category_description.category_id ," . DB_PREFIX . "category_description.name FROM " . DB_PREFIX . "category_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";
        $query = $this->db->query($sql);
        return $query->rows;
    }
    
    public function selectCustomeGroup() {
        $sql = "SELECT customer_group_id, name FROM " . DB_PREFIX . "customer_group_description WHERE language_id = 1";
        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function selectManufacture() {
        $sql = "SELECT " . DB_PREFIX . "manufacturer.manufacturer_id ," . DB_PREFIX . "manufacturer.name from " . DB_PREFIX . "manufacturer where " . DB_PREFIX . "manufacturer.manufacturer_id > 1";
        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function updateCategoriesDiscounts($data)
    { 
        $query_where = array();
        $query_join  = '';

        if ($data['products_from_categories'] == 'selected_sub') {
            if ( !empty( $data['category_ids'] ) ) 
            {
                $sub_categories = array();

                foreach ( $data['category_ids'] as $category_id )
                {
                    $categories = $this->getCategoriesByParentId($category_id);
                    if ( $categories )
                    {
                        $sub_categories = array_merge($sub_categories,$categories);
                    }
                }

                $data['category_ids'] = array_merge($data['category_ids'],$sub_categories);

                $data['category_ids'] = array_unique($data['category_ids'], SORT_REGULAR);

				$query_where[] = "ptc.category_id IN ('" . implode("','", $data['category_ids']) . "')";
				
				$query_join .= " LEFT JOIN " . DB_PREFIX . "product_to_category ptc ON p.product_id = ptc.product_id ";
			}
        }

        if ($data['products_from_categories'] == 'selected') {
			if (!empty($data['category_ids'])) {
				$query_where[] = "ptc.category_id IN ('" . implode("','", $data['category_ids']) . "')";
				
				$query_join .= " LEFT JOIN " . DB_PREFIX . "product_to_category ptc ON p.product_id = ptc.product_id ";
			}
        }
        
        if ($data['products_from_manufacturers'] == 'selected') {
			if (!empty($data['manufacturer_ids'])) {
				$query_where[] = "p.manufacturer_id IN ('" . implode("','", $data['manufacturer_ids']) . "')";
			}
        }
        
        if (!empty($query_where)) {
			$query_where = " WHERE " . implode(' AND ', $query_where);
		} else {
			$query_where = "";
        }
        
        $query_from = "SELECT p.product_id, p.price FROM " . DB_PREFIX . "product p";
        
        if (!empty($query_join)) {
			$query_from .= $query_join;
        }
        
        $query_from .= $query_where;
        
        $query = $this->db->query($query_from);  

        $products = $query->rows;

        $products = array_unique($products, SORT_REGULAR);

       // echo "<pre>"; print_r($products); echo "</pre>"; exit;

        $dicount_count = count($data['select_cust_group']);

        for ( $i = 0; $i < $dicount_count; $i++ )
        {
            foreach ( $products as $product )
            {
                $this->updateProductDiscount( $product['product_id'], $product['price'], $data['select_cust_group'][$i]['customer_group'],$data['product_discount'][$i]);
            }
        }
    }

    public function updateProductDiscount( $product_id, $price, $customer_group_id, $discounts )
    {
        if($discounts)
        {
            $this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "'");
            
            foreach ( $discounts as $discount )
            {
                $quantity = $discount['quantity'];
                $discount_percent = $discount['price'];

                $discount_price = $price - ( $price * ( $discount_percent / 100 ) );

                $this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$customer_group_id . "', quantity = '" . (int)$quantity . "',discount_percent='".(float)$discount_percent."', priority = '1', price = '" . (float)$discount_price . "'");
            }
        }
    }

    /**
     * This function set the category discounts
     * @param type $cate_id
     * @param type $discounts
     * @author Anil Gautam
     */
    public function updateCatDiscounts($cate_id = 0, $discounts, $cust_group) {
        $sql = "SELECT oc_product.product_id from "
                . "oc_product LEFT JOIN oc_product_to_category"
                . " on oc_product.product_id = oc_product_to_category.product_id";
        if ($cate_id) { // modified code to contains the product in sub categories
            $implode_data = array();
            $sql.=" WHERE ";
            $implode_data[] = "" . DB_PREFIX . "product_to_category.category_id = '" . $cate_id . "'";
            $categories = $this->getCategoriesByParentId($cate_id);
            foreach ($categories as $category_id) {
                $implode_data[] = "  " . DB_PREFIX . "product_to_category.category_id = '" . (int) $category_id . "'";
            }
            $sql .= "  (" . implode(' OR ', $implode_data) . ")";
        }
        // $sql .= " AND oc_product.status = 1";
//       echo $sql;
//       die;
        $run = $this->db->query($sql);
        $results = $run->rows;
      // echo $run->num_rows;
	  // die;
//        pr($results);die;
        $this->discounts = $discounts;
        $this->cust_group = $cust_group;
        $this->_processProducts($results);
    }

    /**
     *  Function to process products
     *  @author Anil Gautam
     */
    private function _processProducts($products) {
        
         echo "Following products are updated <br>";
        foreach ($products as $single_group) {
//           echo  $this->count++;
//           echo "<br/>";
        echo "PRODUCT ID: " . $this->product_id = $single_group['product_id'];
           echo "<br/>";
//            $this->product_name = $single_group['name'];
            
                    $product_discoutns = $this->_getProductDiscounts();
        $product_discounts_calculated = $this->getCalcDiscounts();
        $this->updateDiscounts($product_discounts_calculated);
        }die;
        echo $this->count;
    }

    /**
     * This function process single product each time
     * @param type $product_id
     * @author Anil Gautam
     */
    private function _processSingleProduct() {
        echo "------ <br />";
        echo "PRODUCT ID: " . $this->product_id." <br>";
//        echo "PRODUCT NAME: " . $this->product_name. "<br>";
        echo "------<br />";
//       
//        $product_discoutns = $this->_getProductDiscounts();
//        $product_discounts_calculated = $this->getCalcDiscounts();
//        $this->updateDiscounts($product_discounts_calculated);
    }

    /**
     *  Function to return products discounts from backend
     */
    private function _getProductDiscounts() {
        $dis_query = $this->db->query("select * from oc_product_discount where product_id=". $this->product_id);
        if($dis_query->num_rows = 0)
        {
            return false;
        }else{
            return $dis_query->rows;
        }
    }
    /**
     *  Function to return products discounts from backend
     */
    private function getCalcDiscounts() {
       $getMainPrice = $this->db->query("select price from oc_product where product_id =".$this->product_id);
       $price = $getMainPrice->row['price'];
       $calc_discounts = array();
	 //  print_r($this->discounts);
	   
       foreach($this->discounts as $single_discount){
           $calc_discounts[$single_discount['quantity']][] = $price - ($price*($single_discount['price']/100));
		   $calc_discounts[$single_discount['quantity']][] = $single_discount['price'];
		   $calc_discounts[$single_discount['quantity']][] = $single_discount['quantity'];
       }
	  // print_r($calc_discounts);
	  // die;
       return $calc_discounts;
    }
    
    /**
     *  Update discounts
     */
    private function updateDiscounts($product_discounts_calculated){
        $this->db->query('delete from oc_product_discount where product_id ='.$this->product_id.' AND customer_group_id ='.$this->cust_group);
        foreach($product_discounts_calculated as $discounts){
			$quantity = $discounts[2];
			$discount = $discounts[0];
			$discount_percent = $discounts[1];
            $this->db->query("insert "
                        . "into"
                        . " oc_product_discount "
                        . "values ('','".$this->product_id."','$this->cust_group','".$quantity."','1', '".$discount."', '".$discount_percent."','','')");
			
        }
    }

    public function getParentCatNames($product_id) {
        $query = $this->db->query("SELECT
                " . DB_PREFIX . "category_description.name," . DB_PREFIX . "category_description.category_id
                FROM
                " . DB_PREFIX . "product_to_category
                Inner Join " . DB_PREFIX . "category_description ON " . DB_PREFIX . "category_description.category_id = " . DB_PREFIX . "product_to_category.category_id
                WHERE
                " . DB_PREFIX . "product_to_category.product_id = $product_id
                AND
                " . DB_PREFIX . "product_to_category.category_id != 136
                ");
        $man_cat_name_final = $query->row['name'];
        $category_id = $query->row['category_id'];
        $man_cat_name = array();
        while ($category_id != 0) {
            if ($category_id != 0) {
                $topLevelName_array = $this->getTopCategoryName($category_id);
                if (isset($topLevelName_array['category_id'])) {
                    $category_id = $topLevelName_array['category_id'];
                    $man_cat_name[] = $topLevelName_array['name'];
                } else {
                    $category_id = 0;
                }
            }
        }
        $man_cat_name2 = array_reverse($man_cat_name);
        $name_list = implode("///", $man_cat_name2);
        $foina = $name_list . "///" . $man_cat_name_final;
        return $foina;
    }

    public function getTopCategoryName($category_id) {
        $parent_name_query = $this->db->query("SELECT
                            " . DB_PREFIX . "category_description.name,
                            " . DB_PREFIX . "category_description.category_id
                            FROM
                            " . DB_PREFIX . "category
                            Inner Join " . DB_PREFIX . "category_description ON " . DB_PREFIX . "category.parent_id = " . DB_PREFIX . "category_description.category_id
                            WHERE
                            " . DB_PREFIX . "category.category_id = $category_id
    ");
        return $parent_name_query->row;
    }

    public function getCategoriesByParentId($category_id) {
        $category_data = array();

        $category_query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "category WHERE parent_id = '" . (int) $category_id . "'");

        foreach ($category_query->rows as $category) {
            $category_data[] = $category['category_id'];

            $children = $this->getCategoriesByParentId($category['category_id']);

            if ($children) {
                $category_data = array_merge($children, $category_data);
            }
        }

        return $category_data;
    }

    public function saveData($file_import) {
        $ignoreFirstRow = 1;
        while (!feof($file_import)) {
            $file_data = (fgetcsv($file_import, 10000, $delimiter = ';'));
            if ($ignoreFirstRow == 1) {
                $output[] = 'self_id int NOT NULL AUTO_INCREMENT';
                foreach ($file_data as $field):
                    if (!empty($field)) {
                        $output[] = $field . ' varchar(255)';
                        $fields[] = $field;
                    }
                endforeach;
                $data = implode(",", $output);
                $sql = "CREATE TABLE IF NOT EXISTS `temp_csv_data` ($data , PRIMARY KEY (self_id)) ";
                $query = $this->db->query($sql);
            } else {
                if (!empty($file_data)) {
                    $outputs = '';
                    $field = implode(",", $fields);
                    foreach ($file_data as $field_data):
                        $outputs[] = "'" . $field_data . "'";
                    endforeach;
                    $data = '';
                    $data = implode(",", $outputs);
                    $sql = "INSERT INTO `temp_csv_data` ($field) VALUES ($data)";
                    unset($data);
                    $query = $this->db->query($sql);
                }
            }
            $ignoreFirstRow++;
        }
    }

    public function updatationData() {
        $select_temp_query = "SELECT * FROM temp_csv_data";
        $select_temp_result = $this->db->query($select_temp_query);
        foreach ($select_temp_result->rows as $temp_data) {

            $options = $temp_data['Options'];
            $reference_id = $temp_data['Combination_Reference'];
            $supplier_reference_id = $temp_data['Supplier_Reference'];
            if (empty($temp_data['Ean13'])) {
                $ean = 0;
            } else {
                $ean = $temp_data['Ean13'];
            }

            if (empty($temp_data['upc'])) {
                $upc = 0;
            } else {
                $upc = $temp_data['upc'];
            }

            if (empty($temp_data['wholesale_price'])) {
                $wholesale_price = 0;
            } else {
                $wholesale_price = $temp_data['wholesale_price'];
            }

            if (empty($temp_data['price'])) {
                $price = 0;
            } else {
                $price = $temp_data['price'];
            }

            if (empty($temp_data['ecotax'])) {
                $ecotax = 0;
            } else {
                $ecotax = $temp_data['ecotax'];
            }

            if (empty($temp_data['quantity'])) {
                $quantity = 0;
            } else {
                $quantity = $temp_data['quantity'];
            }

            if (empty($temp_data['weight'])) {
                $weight = 0;
            } else {
                $weight = $temp_data['weight'];
            }

            if (empty($temp_data['combination_default'])) {
                $combination_default = 0;
            } else {
                $combination_default = $temp_data['combination_default'];
            }

            $options_explode = explode(":", $options);
            $option_name = addslashes($options_explode[0]);
            $option_value = addslashes($options_explode[1]);

            $select_option_desp_query = "SELECT option_id FROM " . DB_PREFIX . "option_description where name = '$option_name'";
            $select_option_desp_result = $this->db->query($select_option_desp_query);

            if ($select_option_desp_result->num_rows != 0) {
                $option_id = $select_option_desp_result->row['option_id'];

                $select_option_value_desp_query = "SELECT option_value_id FROM " . DB_PREFIX . "option_value_description where name = '$option_value' AND option_id = $option_id";
                $select_option_value_desp_result = $this->db->query($select_option_value_desp_query);

                if ($select_option_value_desp_result->num_rows != 0) {
                    $option_value_id = $select_option_value_desp_result->row['option_value_id'];
                } else {
                    $insert_option_value_query = "INSERT into " . DB_PREFIX . "option_value (option_id) VALUES ('$option_id')";
                    $this->db->query($insert_option_value_query);
                    $last_inst_option_value_id = mysql_insert_id();

                    $query_insert_option_value_desp = "INSERT into " . DB_PREFIX . "option_value_description
                          (option_value_id,language_id,option_id,name)
                          VALUES
                          ('$last_inst_option_value_id',1,'$option_id','$option_value')";
                    $this->db->query($query_insert_option_value_desp);

                    $query_select_product = "SELECT product_id FROM " . DB_PREFIX . "product where upc = '$reference_id'";
                    $result_select_product = $this->db->query($query_select_product);
                    $product_id = $result_select_product->row['product_id'];

                    $query_select_product_option = "SELECT product_option_id  FROM " . DB_PREFIX . "product_option where product_id = '$product_id' AND option_id = '$option_id'";
                    $result_select_product_option = $this->db->query($query_select_product_option);
                    $product_option_id = $result_select_product_option->row['product_option_id'];

                    $insert_product_option_value = "INSERT into " . DB_PREFIX . "product_option_value
                                    (product_option_id, product_id, option_id, option_value_id, weight, weight_prefix, subtract, price, price_prefix,quantity )
                                    VALUES
                                    ('$product_option_id','$product_id','$option_id','$last_inst_option_value_id','$weight','+','1','$wholesale_price','+','$quantity')";
                    $this->db->query($insert_product_option_value);
                }
            } else {
                $insert_option_query = "INSERT into " . DB_PREFIX . "option (type) VALUES ('select')";
                $this->db->query($insert_option_query);
                $last_inst_option_id = mysql_insert_id();

                $query_insert_option_desp = "INSERT into " . DB_PREFIX . "option_description (option_id,language_id,name) VALUES ($last_inst_option_id,1,'$option_name')";
                $this->db->query($query_insert_option_desp);

                $insert_option_value_query = "INSERT into " . DB_PREFIX . "option_value (option_id) VALUES ('$last_inst_option_id')";
                $this->db->query($insert_option_value_query);
                $last_inst_option_value_id = mysql_insert_id();

                $query_insert_option_value_desp = "INSERT into " . DB_PREFIX . "option_value_description
                          (option_value_id,language_id,option_id,name)
                          VALUES
                          ('$last_inst_option_value_id',1,'$last_inst_option_id','$option_value')";
                $this->db->query($query_insert_option_value_desp);

                $query_select_product = "SELECT product_id FROM " . DB_PREFIX . "product where upc = '$reference_id'";
                $result_select_product = $this->db->query($query_select_product);
                $product_id = $result_select_product->row['product_id'];

                $query_select_product_option = "SELECT product_option_id  FROM " . DB_PREFIX . "product_option where product_id = '$product_id' AND option_id = '$last_inst_option_id'";
                $result_select_product_option = $this->db->query($query_select_product_option);
                $product_option_id = $result_select_product_option->row['product_option_id'];

                $insert_product_option_value = "INSERT into " . DB_PREFIX . "product_option_value
                                    (product_option_id, product_id, option_id, option_value_id, weight, weight_prefix, subtract, price, price_prefix,quantity )
                                    VALUES
                                    ('$product_option_id','$product_id','$last_inst_option_id','$last_inst_option_value_id','$weight','+','1','$wholesale_price','+','$quantity')";
                $this->db->query($insert_product_option_value);
            }
        }
    }

    public function dropTempTable() {
        $sql = "DROP TABLE temp_csv_data";
        $query = $this->db->query($sql);
    }

    public function saveUnitsData($file_import) {
        $ignoreFirstRow = 1;
        while (!feof($file_import)) {
            $file_data = (fgetcsv($file_import, 10000, $delimiter = ';'));
            if ($ignoreFirstRow == 1) {
                $output[] = 'self_id int NOT NULL AUTO_INCREMENT';
                foreach ($file_data as $field):
                    if (!empty($field)) {
                        $output[] = $field . ' varchar(255)';
                        $fields[] = $field;
                    }
                endforeach;
                $data = implode(",", $output);
                $sql = "CREATE TABLE IF NOT EXISTS `temp_csv_units_data` ($data , PRIMARY KEY (self_id)) ";
                $query = $this->db->query($sql);
            } else {
                if (!empty($file_data)) {
                    $outputs = '';
                    $field = implode(",", $fields);
                    foreach ($file_data as $field_data):
                        $outputs[] = "'" . $field_data . "'";
                    endforeach;
                    $data = '';
                    $data = implode(",", $outputs);
                    $sql = "INSERT INTO `temp_csv_units_data` ($field) VALUES ($data)";
                    unset($data);
                    $query = $this->db->query($sql);
                }
            }
            $ignoreFirstRow++;
        }
    }

    public function updatationUnitsData() {
        $select_temp_query = "SELECT * FROM temp_csv_units_data";
        $select_temp_result = $this->db->query($select_temp_query);
        $product_ids = array();
        foreach ($select_temp_result->rows as $temp_data) {
            $product_ids[$temp_data['Id_Product']][] = $temp_data;
        }
        foreach ($product_ids as $key => $pro_data) {
            $product_id = $key;
            $option_name = 'weight ' . $key . ' units';
            $query_select_option_id = "SELECT option_id FROM " . DB_PREFIX . "option_description where name = '$option_name'";
            $result_select_option_id = $this->db->query($query_select_option_id);
            $this->deleteOptions($result_select_option_id->rows);

            $query_select_check_option_id = "SELECT product_option_id FROM " . DB_PREFIX . "product_option where product_id = '$product_id' AND option_id = 23";
            $result_select_check_option_id = $this->db->query($query_select_check_option_id);
            $this->deleteCheckOptions($result_select_check_option_id->rows);

            $count = count($pro_data);
            $option_type = 'select';
            $query_insert_option = "INSERT into " . DB_PREFIX . "option (type) VALUES ('$option_type')";
            $result_insert_option = $this->db->query($query_insert_option);
            $lang = 1;
            $required = 1;
            $option_id = mysql_insert_id();

            $query_insert_option_desp = "INSERT into " . DB_PREFIX . "option_description (option_id,language_id,name,metal_type) VALUES ($option_id,$lang,'$option_name',3)";
            $result_insert_option_desp = $this->db->query($query_insert_option_desp);


            $insert_product_option = "INSERT into " . DB_PREFIX . "product_option (product_id,option_id,required)
                                                      VALUES ($product_id,$option_id,$required)";
            $result_insert_product_option = $this->db->query($insert_product_option);
            $product_option_id = mysql_insert_id();

            for ($x = 0; $x < $count; $x++) {
                $market_price = $pro_data[$x]['Convert_units'];
                $option_value_name = $pro_data[$x]['Name'];

                $query_insert_option_value = "INSERT into " . DB_PREFIX . "option_value (option_id,market_price) VALUES ('$option_id','$market_price')";
                $result_insert_option_value = $this->db->query($query_insert_option_value);
                $option_value_id = mysql_insert_id();

                $query_insert_option_value_desp = "INSERT into " . DB_PREFIX . "option_value_description
                          (option_value_id,language_id,option_id,name)
                          VALUES
                          ('$option_value_id',$lang,'$option_id','$option_value_name')";
                $result_insert_option_value_desp = $this->db->query($query_insert_option_value_desp);

                $price = 0;
                $weight = 0;
                $quantity = 100000;
                $price_prefix = '+';
                $weight_prefix = '+';

                $insert_product_option_value = "INSERT into " . DB_PREFIX . "product_option_value
                                    (product_option_id, product_id, option_id, option_value_id, weight, weight_prefix, subtract, price, price_prefix,quantity )
                                    VALUES
                                    ('$product_option_id','$product_id','$option_id','$option_value_id','$weight','$weight_prefix','1','$price','$price_prefix','$quantity')";
                $result_insert_product_option_value = mysql_query($insert_product_option_value);
            }
        }
    }

    public function deleteOptions($options) {
        foreach ($options as $option) {
            $option_id = $option['option_id'];

            $query_delete_option = "DELETE FROM " . DB_PREFIX . "option WHERE option_id = $option_id";
            $this->db->query($query_delete_option);

            $query_delete_option_description = "DELETE FROM " . DB_PREFIX . "option_description WHERE option_id = $option_id";
            $this->db->query($query_delete_option_description);

            $query_delete_option_value = "DELETE FROM " . DB_PREFIX . "option_value WHERE option_id = $option_id";
            $this->db->query($query_delete_option_value);

            $query_delete_option_value_description = "DELETE FROM " . DB_PREFIX . "option_value_description WHERE option_id = $option_id";
            $this->db->query($query_delete_option_value_description);

            $query_delete_product_option = "DELETE FROM " . DB_PREFIX . "product_option WHERE option_id = $option_id";
            $this->db->query($query_delete_product_option);

            $query_delete_product_option_value = "DELETE FROM " . DB_PREFIX . "product_option_value WHERE option_id = $option_id";
            $this->db->query($query_delete_product_option_value);
        }
    }

    public function deleteCheckOptions($options) {
        foreach ($options as $option) {
            $product_option_id = $option['product_option_id'];

            $query_delete_product_option = "DELETE FROM " . DB_PREFIX . "product_option WHERE product_option_id = $product_option_id";
            $this->db->query($query_delete_product_option);

            $query_delete_product_option_value = "DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_option_id = $product_option_id";
            $this->db->query($query_delete_product_option_value);
        }
    }

    public function dropUnitsTempTable() {
        $sql = "DROP TABLE temp_csv_units_data";
        $query = $this->db->query($sql);
    }

    public function saveAttributeData($file_import) {
        $ignoreFirstRow = 1;
        while (!feof($file_import)) {
            $file_data = (fgetcsv($file_import, 10000, $delimiter = ';'));
            if ($ignoreFirstRow == 1) {
                $output[] = 'self_id int NOT NULL AUTO_INCREMENT';
                foreach ($file_data as $field):
                    if (!empty($field)) {
                        $output[] = "`" . $field . "`" . ' varchar(255)';
                        $fields[] = "`" . $field . "`";
                    }
                endforeach;
                $data = implode(",", $output);
                $sql = "CREATE TABLE IF NOT EXISTS `temp_csv_attribute_data` ($data , PRIMARY KEY (self_id)) ";
                echo "<br>";
                $query = $this->db->query($sql);
            } else {
                if (!empty($file_data)) {
                    $outputs = '';
                    $field = implode(",", $fields);
                    foreach ($file_data as $field_data):
                        $outputs[] = "'" . $field_data . "'";
                    endforeach;
                    $data = '';
                    $data = implode(",", $outputs);
                    $sql = "INSERT INTO `temp_csv_attribute_data` ($field) VALUES ($data)";
                    unset($data);
                    $query = $this->db->query($sql);
                }
            }
            $ignoreFirstRow++;
        }
    }

    public function updatationAttributeData() {
        $select_temp_query = "SELECT * FROM temp_csv_attribute_data";
        $select_temp_result = $this->db->query($select_temp_query);
        $rsArray = $select_temp_result->rows;

        foreach ($select_temp_result->rows as $attribute_data) {
            $arry_key = array_keys($attribute_data);
            $count_array = count($arry_key);

            $product_id = $attribute_data['Product ID'];
            $product_name = $attribute_data['Product Name'];
            $product_reference_id = $attribute_data['Reference'];

            for ($x = 0; $x < $count_array; $x++) {
                $key_name = $arry_key[$x];
                $key_value = $attribute_data[$arry_key[$x]];
                $explode_key_name = explode(':', $key_name);

                if (!empty($explode_key_name[1])) {
                    $key_name_check = trim($explode_key_name[1]);

                    $query_select_attribute_id = "SELECT attribute_id,name FROM " . DB_PREFIX . "attribute_description where name = '$key_name_check'";
                    $result_select_attribute_id = $this->db->query($query_select_attribute_id);
                    if (!empty($result_select_attribute_id->num_rows)) {
                        $attribute_id = $result_select_attribute_id->row['attribute_id'];
                        if (!empty($key_value)) {
                            $query_select_product_attribute_id = "SELECT product_id FROM " . DB_PREFIX . "product_attribute where attribute_id = $attribute_id AND product_id = $product_id";
                            $result_select_product_attribute_id = $this->db->query($query_select_product_attribute_id);

                            if (!empty($result_select_product_attribute_id->num_rows)) {
                                $query_update_product_attribute_id = "UPDATE  " . DB_PREFIX . "product_attribute SET text = '$key_value' where product_id = $product_id AND attribute_id = $attribute_id AND language_id = 1";
                                $result_update_product_attribute_id = $this->db->query($query_update_product_attribute_id);
                            }
                        }
                    } else {
                        $insert_attribute_query = "Insert into oc_attribute (attribute_group_id) VALUES (1)";
                        $result_insert_attribute = $this->db->query($insert_attribute_query);
                        $attribute_inst_id = mysql_insert_id();

                        $insert_attribute_desp_query = "Insert into oc_attribute_description (attribute_id,language_id,name) VALUES ($attribute_inst_id,1,'$key_name_check')";
                        $result_insert_attribute_desp = $this->db->query($insert_attribute_desp_query);

                        $insert_attribute_product_query = "Insert into oc_product_attribute (product_id,attribute_id,language_id,text) VALUES ($product_id,$attribute_inst_id,1,'$key_value')";
                        $result_insert_attribute_product = $this->db->query($insert_attribute_product_query);
                    }
                }
            }
        }
    }

    public function dropAttributeTempTable() {
        $sql = "DROP TABLE temp_csv_attribute_data";
        $query = $this->db->query($sql);
    }

}
