<?php

class ModelProductConcatScript extends Model {

    //max grouping options
    public $max_options = 11;

    public function checkifTableExists($table_name) {
        $result = $this->db->query("SHOW TABLES LIKE '" . $table_name . "'");
        return ($result->num_rows == 1) ? TRUE : FALSE;
    }

    public function createTable($table, $columns) {
        $query = "CREATE TABLE " . $table . " (";
        $query .= "id int NOT NULL AUTO_INCREMENT, group_status int DEFAULT 0, ";

        foreach ($columns as $column) {
            $query .= $column;
            $query .= " varchar(255),";
        }
        $query = substr($query, 0, -1);
        $query .= ",primary key (id)";
        $query .= ")";
        if ($this->db->query($query)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function updateGroupBySortOrder($id, $product_id, $groupbysortorder)
    {
        $this->db->query("UPDATE product_concat_temp_table SET groupbysortorder = '" . $groupbysortorder . "' WHERE id = '" . $id . "' AND product_id = '" . $product_id . "'");
        $this->db->query("UPDATE oc_product_grouped SET grouped_sort_order = '" . $groupbysortorder . "' WHERE grouped_id = '" . $product_id . "'");
    }

    public function saveTempData($table, $data) {

        //$groupIndicatorDatas = array();
		$error = array();
        foreach ($data as $row) {

            $id = $row['id'];
            unset($row['id']);
            $res = $this->db->query("SELECT groupindicator_id FROM product_concat_temp_table where id = '" . $id . "'");
            //check if same id already exists in concat table
            if ($res->num_rows > 0) {
                // update sort order
                $this->updateGroupBySortOrder($id, $row['product_id'], $row['groupbysortorder']);
                /** start task 2923  * */
                //check if "groupindicator_id" is empty
                if (empty($row['groupindicator_id'])) {
                    //if "groupindicator_id" is empty
                    //get,set required fields
                    $current_product_id = $row['product_id'];
                    $current_product_group_by_name = $row['groupbyname'];
                    $current_product_group_by_value = $row['groupbyvalue'];
                    $old_groupindicator_id = $res->row['groupindicator_id'];
                    $existing_grouped_product_id = $this->db->query("SELECT group_product_id FROM " . DB_PREFIX . "product_grouped_indicator where group_indicator = '" . $old_groupindicator_id . "'");
                    //get count of rows corresponding to that group indicator
                    $count_by_group_indicator = $this->db->query("SELECT count(*) as count  FROM product_concat_temp_table where groupindicator_id = '" . $old_groupindicator_id . "'");
                    if ($existing_grouped_product_id->num_rows > 0) {
                        $product_group_id = $existing_grouped_product_id->row['group_product_id'];
                        if ($count_by_group_indicator->row['count'] == 1) { //if count is one
                            //delete row from oc_product_grouped_indicator
                            $this->db->query("DELETE FROM " . DB_PREFIX . "product_grouped_indicator WHERE group_product_id='" . $product_group_id . "'");
                            //delete the group product
                            $this->load->model('catalog/product_list_gp');
                            $this->model_catalog_product_list_gp->deleteProduct($product_group_id);
                        } else {
                            //fetch all rows with same groupindicator_id,groupbyname and groupbyvalue
                            $sub_group_product_count = $this->db->query("SELECT count(*) as count  FROM product_concat_temp_table WHERE groupindicator_id = '" . $old_groupindicator_id . "' AND groupbyname='" . $this->db->escape($current_product_group_by_name) . "' AND groupbyvalue='" . $this->db->escape($current_product_group_by_value) . "'");
                            if ($sub_group_product_count->row['count'] > 0) {
                                //if only one combination row exists
                                if ($sub_group_product_count->row['count'] == 1) {
                                    //delete product from that group
                                    $this->db->query("DELETE FROM " . DB_PREFIX . "product_grouped WHERE product_id='" . $product_group_id . "' AND grouped_id='" . $current_product_id . "'");
                                } else { //combination rows > 1
                                    //check if current row's product id is directly grouped
                                    $check_directly_grouped = $this->db->query("SELECT count(*) as count FROM " . DB_PREFIX . "product_grouped WHERE product_id='" . $product_group_id . "' AND grouped_id='" . $current_product_id . "'");

                                    if ($check_directly_grouped->row['count'] == 1) { //if directly grouped
                                        //get all products under sub-group
                                        $next_product_id = $this->db->query("SELECT product_id FROM product_concat_temp_table WHERE id != '" . $id . "' AND groupindicator_id = '" . $old_groupindicator_id . "' AND groupbyname='" . $this->db->escape($current_product_group_by_name) . "' AND groupbyvalue='" . $this->db->escape($current_product_group_by_value) . "' LIMIT 0,1");
                                        $this->db->query("INSERT INTO " . DB_PREFIX . "product_grouped SET product_id = '" . $product_group_id . "',grouped_id = '" . $next_product_id->row['product_id'] . "',grouped_stock_status_id='0'");
                                        $this->db->query("UPDATE " . DB_PREFIX . "product SET pgvisibility = 2 WHERE product_id='" . $next_product_id->row['product_id'] . "'");
                                        //delete product from that group
                                        $this->db->query("DELETE FROM " . DB_PREFIX . "product_grouped WHERE product_id='" . $product_group_id . "' AND grouped_id='" . $current_product_id . "'");
                                    }
                                }

                                //reassigning minimum cost
                                $min_select_query = $this->db->query("SELECT p.product_id,MIN(p.price) FROM " . DB_PREFIX . "product_grouped pg LEFT JOIN " . DB_PREFIX . "product p ON pg.grouped_id = p.product_id WHERE pg.product_id='" . $product_group_id . "' GROUP BY p.product_id");
                                if ($min_select_query->num_rows > 0) {
                                    $this->db->query("UPDATE " . DB_PREFIX . "product_grouped SET is_starting_price = CASE WHEN grouped_id = " . $min_select_query->row['product_id'] . " THEN 1 ELSE 0 END");
                                }
                            }
                        }
                    }

                    //remove extra assigned options from that product
                    $this->load->model('catalog/product');
                    $product_options = $this->model_catalog_product->getProductOptions($current_product_id);
                    foreach ($product_options as $product_option) {
                        if ($product_option['type'] == 'select') {
                            for ($i = 0; $i <= $this->max_options; $i++) {
                                if (!empty($row['optionname' . $i]) && trim(strtolower($row['optionname' . $i])) != trim(strtolower($product_option['name']))) {
                                    $this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_option_id='" . $product_option['product_option_id'] . "'");
                                    foreach ($product_option['product_option_value'] as $product_option_value) {
                                        $option_value_name = $this->db->query("SELECT name FROM " . DB_PREFIX . "option_value_description WHERE option_value_id='" . $product_option_value['option_value_id'] . "'");
                                        $this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_option_value_id ='" . $product_option_value['product_option_value_id'] . "'");
                                    }
                                } elseif (!empty($row['optionname' . $i]) && trim(strtolower($row['optionname' . $i])) == trim(strtolower($product_option['name']))) {
                                    foreach ($product_option['product_option_value'] as $product_option_value) {
                                        $option_value_name = $this->db->query("SELECT name FROM " . DB_PREFIX . "option_value_description WHERE option_value_id='" . $product_option_value['option_value_id'] . "'");
                                        if (trim(strtolower($row['optionvalue' . $i])) != trim(strtolower($option_value_name->row['name']))) {
                                            $this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_option_value_id ='" . $product_option_value['product_option_value_id'] . "'");
                                        }
                                    }
                                }
                            }
                        }
                    }
                    //enable/show the simpler product
                    $this->db->query("UPDATE " . DB_PREFIX . "product SET pgvisibility=1 WHERE product_id='" . $current_product_id . "'");
                    //delete current row from product_concat table		
                    $this->db->query("DELETE FROM product_concat_temp_table where id = '" . $id . "'");
                } else {
                    /** end task 2923  * */
                    $query = "UPDATE " . $table . " SET ";
                    foreach ($row as $key => $value) {
                        if ($key !== '') {
                            $query .= $key . " = '" . $this->db->escape($value) . "' ,";
                        }
                    }
                    $query = substr($query, 0, -1);
                    $query.= "where id =" . $id;
                    $query .= ";";

                    $this->db->query($query);
                    $this->db->query("UPDATE product_concat_temp_table SET group_status = 0 where id = '" . $id . "'");

                    $groupIndicatorData[$res->row['groupindicator_id']][] = $row['groupindicator_id'];
                }
            } else {
                /** start task 2923  * */
                if (empty($row['groupindicator_id'])) {
                    continue;
                }
				
				if (!empty($row['sku'])) {
					$current_sku = $row['sku'];
					$current_groupindicator = $row['groupindicator'];
                    $res = $this->db->query("SELECT * FROM product_concat_temp_table where sku = '" . $current_sku . "'");
				   
				    if ($res->num_rows > 0) {
							
							if($row['groupindicator'] != $res->row['groupindicator']){
								$error['text'] = "Product Group Indicator ".$current_groupindicator." Alrady Assign to ".$res->row['groupindicator_id'];
            					$error['type'] = 'error';
							}
							
							if($row['groupindicator_id'] != $res->row['groupindicator_id']){
								$error['text'] = "Product Group Indicator ".$current_groupindicator." Alrady Assign to ".$res->row['groupindicator_id'];
            					$error['type'] = 'warning';
							}
							
							
					}
					
					if(!empty($error)){
						return $error;
						die('warning');
					}
                }
				
				
				if (!empty($row['product_id'])) {
					$current_product_id = $row['product_id'];
					$res = $this->db->query("SELECT id, groupindicator_id FROM product_concat_temp_table where product_id = '" . $current_product_id . "'");
				    if ($res->num_rows > 0) {
						/** end task 2923  * */
						$query = "UPDATE " . $table . " SET ";
						foreach ($row as $key => $value) {
							if ($key !== '') {
								$query .= $key . " = '" . $this->db->escape($value) . "' ,";
							}
						}
						$query = substr($query, 0, -1);
						$query.= "where id =" . $res->row['id'];
						$query .= ";";
	
						$this->db->query($query);
						$this->db->query("UPDATE product_concat_temp_table SET group_status = 0 where id = '" . $res->row['id'] . "'");
	
						$groupIndicatorData[$res->row['groupindicator_id']][] = $row['groupindicator_id'];
					}else{
						
						$query = "INSERT INTO " . $table . " SET ";
						foreach ($row as $key => $value) {
							if ($key !== '') {
								$query .= $key . " = '" . $this->db->escape($value) . "' ,";
							}
						}
						$query = substr($query, 0, -1);
						$query .= ";";
						$this->db->query($query);
					}
                }
                
				
				
				
                
            }
        }
        $this->load->model('catalog/product_list_gp');
        if (isset($groupIndicatorData) && !empty($groupIndicatorData)) {
            foreach ($groupIndicatorData as $key => $value) {
                $query = $this->db->query("SELECT * from product_concat_temp_table where groupindicator_id = '$key'");
                if ($query->num_rows <= 0) {
                    $data = $this->db->query("SELECT group_product_id from oc_product_grouped_indicator where group_indicator = '$key'");
                    if ($data->num_rows > 0) {
                        $this->model_catalog_product_list_gp->deleteProduct($data->row['group_product_id']);
                        $this->db->query("DELETE FROM oc_product_grouped_indicator WHERE group_product_id = '" . $data->row['group_product_id'] . "'");
                    }
                }
            }
        }
		$error['text'] = "Step 2: Complete (CSV Processed to temporary data).";
        $error['type'] = 'success';
        return $error;
    }

    /**
     * 
     * This function retuns number of products assigned to specific product indicator
     * @param type $groupindicator
     * @param type $table
     * @return count assigned products to a group indicator.
     */
    public function checkGroupIndicator($groupindicator, $table) {
        $sql = "select sku from " . $table . " where groupindicator='" . $groupindicator . "'";
        $query = $this->db->query($sql);
        return $query->num_rows;
    }

    /**
     *  This function delete all the assigned products from product_concat_temp_table table
     * @param type $groupindicator
     * @param type $table
     */
    public function deleteByGroupindicator($groupindicator, $table) {
        $this->db->query("delete from " . $table . " where groupindicator='" . $groupindicator . "'");
    }

    public function getAllSku($table) {
        $query = "SELECT * from " . $table . "";
        $result = $this->db->query($query);
    }

    public function checkGroupProductExist($indicator) {
        $sql = "select group_product_id from " . DB_PREFIX . "product_grouped_indicator where group_indicator='" . $indicator . "'";
        $query = $this->db->query($sql);
        if ($query->num_rows > 0) {
            return $query->rows;
        } else {
            return false;
        }
    }

    public function checkCreateOption($option_name, $option_type = null) {
        if (is_null($option_type)) {
            $option_type = 'select';
        }
        $check = $this->db->query("SELECT o.option_id FROM " . DB_PREFIX . "option o LEFT JOIN " . DB_PREFIX . "option_description od ON o.option_id=od.option_id WHERE od.name='" . $this->db->escape($option_name) . "' AND LOWER(o.type)='" . $option_type . "'");
        if ($check->num_rows == 0) {
            return $this->_createOption($option_name);
        } else {
            return $check->row['option_id'];
        }
    }

    private function _createOption($option_name) {
        $option_insert_query = $this->db->query("INSERT INTO " . DB_PREFIX . "option SET type='select',sort_order='0'");
        if ($option_insert_query) {
            $option_id = $this->db->getLastId();
            $option_description_insert_query = $this->db->query("INSERT INTO " . DB_PREFIX . "option_description SET option_id='" . $option_id . "',language_id='1',name='" . $option_name . "'");
            if ($option_description_insert_query) {
                return $option_id;
            }
        }
    }

    public function checkCreateOptionValue($option_id, $option_value) {
        $check = $this->db->query("SELECT option_value_id FROM " . DB_PREFIX . "option_value_description "
                . "WHERE option_id='" . $option_id . "' AND name='" . $option_value . "'");

        if (!$check->num_rows) {
            return $this->_createOptionValue($option_id, $option_value);
        } else {
            return $check->row['option_value_id'];
        }
    }

    public function _createOptionValue($option_id, $option_value) {
        $option_value_insert_query = $this->db->query("INSERT INTO " . DB_PREFIX . "option_value SET option_id='" . $option_id . "', image='', market_price='0', sort_order='0'");
        if ($option_value_insert_query) {
            $option_value_id = $this->db->getLastId();
            $option_description_insert_query = $this->db->query("INSERT INTO " . DB_PREFIX . "option_value_description "
                    . "SET option_value_id='" . $option_value_id . "', option_id='" . $option_id . "',language_id='1',name='" . $option_value . "'");
            if ($option_description_insert_query) {
                return $option_value_id;
            }
        }
    }

    public function checkproductId($product_id) {
        $sel_query = $this->db->query("SELECT product_id from " . DB_PREFIX . "product WHERE product_id ='" . $product_id . "'");
        if ($sel_query->num_rows) {
            return ($this->_checkStatus($product_id, 'product_id')) ? $sel_query->row['product_id'] : 'disabled';
        }
    }

    public function getproductIdBySku($sku) {
        $sel_query = $this->db->query("SELECT product_id from " . DB_PREFIX . "product WHERE sku='" . $sku . "'");
        if ($sel_query->num_rows) {
            return ($this->_checkStatus($sku, 'sku')) ? $sel_query->row['product_id'] : 'disabled';
        } else {
            $sel_query_upc = $this->db->query("SELECT product_id from " . DB_PREFIX . "product WHERE upc='" . $sku . "'");
            if ($sel_query_upc->num_rows) {
                return ($this->_checkStatus($sku, 'upc')) ? $sel_query_upc->row['product_id'] : 'disabled';
            } else {
                $sel_query_model = $this->db->query("SELECT product_id from " . DB_PREFIX . "product WHERE model='" . $sku . "'");
                if ($sel_query_model->num_rows) {
                    return ($this->_checkStatus($sku, 'model')) ? $sel_query_model->row['product_id'] : 'disabled';
                } else {
                    return FALSE;
                }
            }
        }
    }

    private function _checkStatus($sku, $field) {
        $query = "SELECT status FROM " . DB_PREFIX . "product WHERE `$field` = '" . $sku . "'";
        $status_check_query = $this->db->query($query);
        if ($status_check_query->num_rows) {
            if ($status_check_query->row['status'] == 1) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function checkCreateProductOption($product_id, $option_id, $option_value_id, $option_sort) {
        $opt_array = array();
        $check = $this->db->query("SELECT product_option_id from " . DB_PREFIX . "product_option WHERE product_id='" . $product_id . "' AND option_id='" . $option_id . "'");
        if (!$check->num_rows) {
            $product_option_id = $this->_createProductOption($product_id, $option_id);
            $product_option_value_id = $this->_createProductOptionValue($product_id, $option_id, $option_value_id, $product_option_id, $option_sort);
        } else {
            $product_option_id = $check->row['product_option_id'];
            $product_option_value_check = $this->db->query("SELECT product_option_value_id from " . DB_PREFIX . "product_option_value WHERE product_option_id='" . $product_option_id . "'AND product_id='" . $product_id . "' AND option_id='" . $option_id . "' AND option_value_id='" . $option_value_id . "'");
            if (!($product_option_value_check->num_rows)) {
                $product_option_value_id = $this->_createProductOptionValue($product_id, $option_id, $option_value_id, $product_option_id, $option_sort);
            }
        }
        return true;
    }

    public function _createProductOption($product_id, $option_id) {
        $product_option_insert_query = $this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id='" . $product_id . "', option_id='" . $option_id . "', required=1");
        if ($product_option_insert_query) {
            return $this->db->getLastId();
        }
    }

    private function _createProductOptionValue($product_id, $option_id, $option_value_id, $product_option_id, $sort_order) {

        $product_option_value_insert_query = $this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value "
                . "SET product_option_id='" . $product_option_id . "', "
                . "product_id='" . $product_id . "', "
                . "option_id='" . $option_id . "', "
                . "option_value_id = '" . $option_value_id . "',"
                . "sort_order = '" . $sort_order . "',"
                . "quantity='99999999',subtract='0', "
                . "price='',price_prefix='+', "
                . "points='0',points_prefix='+',weight='0', "
                . "weight_prefix='+'");
        if ($product_option_value_insert_query) {
            return $this->db->getLastId();
        } else {
            return FALSE;
        }
    }

    public function getGroups($group_indicator_id) {
        $group_select_query = $this->db->query("SELECT groupbyvalue FROM product_concat_temp_table "
                . "WHERE groupindicator_id='" . $group_indicator_id . "' GROUP BY groupbyvalue");
        if ($group_select_query) {
            return $group_select_query->rows;
        }
    }

    public function getGroupProducts($group_indicator_id, $group) {
        $group_products_select_query = $this->db->query("SELECT * FROM product_concat_temp_table "
                . "WHERE groupindicator_id ='" . $group_indicator_id . "' AND groupbyvalue='" . $group . "'");
        if ($group_products_select_query) {
            return $group_products_select_query->rows;
        }
    }

    public function getAllGroups() {
        $all_group_select = $this->db->query("SELECT groupindicator,groupindicator_id,groupbyname FROM product_concat_temp_table WHERE group_status = '0' GROUP BY groupindicator_id ORDER BY id");
        if ($all_group_select) {
            return $all_group_select->rows;
        }
    }

    public function renameProduct($product_id, $product_prefix, $grouped_product_name) {
        $update_product_query = $this->db->query("UPDATE " . DB_PREFIX . "product_description SET name='" . $product_prefix . " " . $grouped_product_name . "' WHERE product_id='" . $product_id . "'");
        if ($update_product_query) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function updateProductNames($csv_data)
    {
        if( !empty( $csv_data ) )
        {
            foreach( $csv_data as $csv_row )
            {
                $groupbyvalue       = $csv_row['groupbyvalue'];
                $optionvalue1       = $csv_row['optionvalue1'];
                $optionvalue2       = $csv_row['optionvalue2'];
                $groupedproductname = $csv_row['groupedproductname'];
                $product_id         = $csv_row['product_id'];

                if( !empty($groupbyvalue) && !empty($groupedproductname) )
                {
                    $product_name = $groupbyvalue;
                    if( !empty($optionvalue1) && strpos($groupedproductname, $optionvalue1) === false )
                    {
                        $product_name .= " " . $optionvalue1;
                    }

                    if( !empty($optionvalue2) && strpos($groupedproductname, $optionvalue2) === false )
                    {
                        $product_name .= " " . $optionvalue2;
                    }

                    $product_name .= " " . $groupedproductname;

                    $this->db->query("UPDATE " . DB_PREFIX . "product_description SET name='" . $product_name . "' WHERE product_id='" . $product_id . "'");
                }
            }
        }
    }

    /**
     * Function return grouped product name from the temprary table
     * @param type $group_indicator
     * @return type
     */
    public function getGroupedProductName($group_indicator_id) {
        $group_product_name_select = $this->db->query("SELECT groupedproductname FROM product_concat_temp_table WHERE groupindicator_id='" . $group_indicator_id . "'");
        if ($group_product_name_select->num_rows) {
            return trim($group_product_name_select->row['groupedproductname']);
        }
    }

    public function getMinCost($products, $get_product_id = FALSE) {
        $product_ids = '';
        foreach ($products as $product) {
            if (!empty($product)) {
                $product_ids .= $product . ',';
            }
        }
        //pr($products);
        $product_ids = substr($product_ids, 0, -1);
        // pr($product_ids);die;
        $min_price_get_query = $this->db->query("SELECT product_id,MIN(price) FROM " . DB_PREFIX . "product WHERE product_id IN (" . $product_ids . ")");
        if ($min_price_get_query->num_rows) {
            if ($get_product_id == TRUE) {
                return $min_price_get_query->row['product_id'];
            } else {
                return $min_price_get_query->row['MIN(price)'];
            }
        }
    }

    public function getProductImageById($product_id) {
        $image_get_query = $this->db->query("SELECT image FROM " . DB_PREFIX . "product WHERE product_id='" . $product_id . "'");
        if ($image_get_query->num_rows) {
            return $image_get_query->row['image'];
        }
    }

    public function disableProduct($product_id, $sku) {
        $get_product_id_by_sku = $this->getproductIdBySku($sku);
        if ($get_product_id_by_sku != $product_id) {
            $this->db->query("UPDATE " . DB_PREFIX . "product SET status = 0 where product_id='" . $get_product_id_by_sku . "'");
            return $get_product_id_by_sku;
        } else {
            return false;
        }
    }

    public function detachProductOptions($product_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id='" . $product_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id='" . $product_id . "'");
    }

    public function updateGroupedStatus($groupindicator_id) {
        $this->db->query("UPDATE product_concat_temp_table SET group_status = 1 WHERE groupindicator_id='" . $groupindicator_id . "'");
    }

    public function getGroupedProductIds() {
        $ids_get_query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product_grouped GROUP BY product_id");
        return $ids_get_query->rows;
    }

    public function getGroupedNameById($product_id) {
        $grouped_name = $this->db->query("SELECT name FROM " . DB_PREFIX . "product_description where product_id='" . $product_id . "'");
        return $grouped_name->num_rows ? $grouped_name->row['name'] : FALSE;
    }

    public function updateSeoUrl($product_id, $name) {
        $exist_url = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query='product_id=" . (int) $product_id . "'");
        if (!$exist_url->num_rows) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int) $product_id . "', keyword = '" . $this->db->escape($name) . "'");
        }
    }

    public function saveUnitsTax($group_id) {
        $product_id = $this->getSimpleProductInGroup($group_id);
        $units = $this->getUnitsProduct($product_id);
        $this->saveUnitTaxData($group_id, $units[0]);
    }

    public function getSimpleProductInGroup($group_id) {
        $query = $this->db->query("SELECT grouped_id FROM " . DB_PREFIX . "product_grouped WHERE product_id=" . $group_id);
        return $query->row['grouped_id'];
    }

    public function getProductOtherData($product_id) {
        $query = $this->db->query("SELECT unit_plural, unit_singular,tax_class_id FROM " . DB_PREFIX . "product WHERE product_id=" . $product_id);
        if ($query->num_rows) {
            return $query->row;
        } else {
            return false;
        }
    }

    public function saveUnitTaxData($group_id, $units) {
        $this->db->query("UPDATE " . DB_PREFIX . "product SET unit_plural='" . $units['unit_plural'] . "', unit_singular= '" . $units['unit_singular'] . "', tax_class_id= '" . $units['tax_class_id'] . "' WHERE model = 'grouped' AND product_id='" . $group_id . "'");
    }

    public function saveGroupIndicator($group_indicator, $group_product_id) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_grouped_indicator SET group_indicator='" . $group_indicator . "',group_product_id='" . $group_product_id . "'");
    }

    public function hideProduct($product_id) {
        $this->db->query("UPDATE " . DB_PREFIX . "product SET pgvisibility = 2 where product_id='" . $product_id . "'");
    }

    public function duplicateNameChange() {
        $query = $this->db->query("SELECT SUBSTRING_INDEX(query, '=', -1) as pid, ua.keyword as name FROM oc_url_alias ua INNER JOIN (SELECT keyword FROM oc_url_alias GROUP BY keyword HAVING count(keyword) > 1) dup ON ua.keyword = dup.keyword");
        if ($query->num_rows) {
            foreach ($query->rows as $data) {
                $this->updateDuplicateSeoUrl($data['pid'], $data['name']);
            }
        }
    }

    public function updateDuplicateSeoUrl($pid, $name) {
        $query = 'product_id=' . $pid;
        $keyword = $name . '-' . $pid;
        $this->db->query("UPDATE oc_url_alias SET keyword = '$keyword' where query= '$query'");
    }

    /**
     * Remove products from current grouping
     * @param type $productIds
     */
    public function detachProductFromGroup($productIds) {
        $productIds = implode(',', $productIds);
        $this->db->query('delete from ' . DB_PREFIX . 'product_grouped where grouped_id in(' . $productIds . ')');
    }

}
