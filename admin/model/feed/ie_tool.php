<?php

class ModelFeedIeTool extends Model {

    public function selectCategory() {
        $sql = "SELECT " . DB_PREFIX . "category_description.category_id ," . DB_PREFIX . "category_description.name FROM " . DB_PREFIX . "category_description WHERE " . DB_PREFIX . "category_description.language_id = 1";
        $query = $this->db->query($sql);
        return $query->rows;
    }
    
    public function getCategories() {
		$sql = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR ' &gt; ') AS name, c.parent_id, c.sort_order FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category c ON (cp.path_id = c.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (c.category_id = cd1.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id) WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		if (!empty($data['filter_name'])) {
			$sql .= " AND cd2.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sql .= " GROUP BY cp.category_id ORDER BY name";
		
//		if (isset($data['start']) || isset($data['limit'])) {
//			if ($data['start'] < 0) {
//				$data['start'] = 0;
//			}				
//
//			if ($data['limit'] < 1) {
//				$data['limit'] = 20;
//			}	
//		 
//			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
//		}
						
		$query = $this->db->query($sql);
		
		return $query->rows;
	}

    public function selectManufacture() {
        $sql = "SELECT " . DB_PREFIX . "manufacturer.manufacturer_id ," . DB_PREFIX . "manufacturer.name from " . DB_PREFIX . "manufacturer where " . DB_PREFIX . "manufacturer.manufacturer_id > 1";
        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function exportExcel($cate_id = 0, $manu_id = 0) {
        $sql = "SELECT
                    " . DB_PREFIX . "product.product_id as product_id,
                    " . DB_PREFIX . "product.upc as reference_id,
                    " . DB_PREFIX . "product.quantity as product_quantity,
                    " . DB_PREFIX . "product.price as product_price,
                    " . DB_PREFIX . "product_description.name as product_name,
                    " . DB_PREFIX . "product_description.meta_description as product_meta_desp,
                    " . DB_PREFIX . "product_description.description as product_desp,
                    " . DB_PREFIX . "product_description.meta_keyword as product_meta_keyword,
                    " . DB_PREFIX . "product_description.tag as product_tag,
                    " . DB_PREFIX . "manufacturer.`name` as manufacture_name,
                    " . DB_PREFIX . "product.sku as sku,
                    " . DB_PREFIX . "category_description.`name` as cat_name,
                    " . DB_PREFIX . "category_description.description as cat_desp,
                    " . DB_PREFIX . "category_description.meta_description as cat_meta_desp,
                    " . DB_PREFIX . "product.unit_singular as unit_singular,
                    " . DB_PREFIX . "product.unit_plural as unit_plural
                    FROM
                    " . DB_PREFIX . "product
                    INNER JOIN " . DB_PREFIX . "product_description 
                        ON " . DB_PREFIX . "product.product_id = " . DB_PREFIX . "product_description.product_id
                    INNER JOIN " . DB_PREFIX . "manufacturer 
                        ON " . DB_PREFIX . "manufacturer.manufacturer_id = " . DB_PREFIX . "product.manufacturer_id
                    INNER JOIN " . DB_PREFIX . "product_to_category 
                        ON " . DB_PREFIX . "product_to_category.product_id = " . DB_PREFIX . "product.product_id
                    INNER JOIN " . DB_PREFIX . "category_description 
                        ON
                    " . DB_PREFIX . "category_description.category_id = " . DB_PREFIX . "product_to_category.category_id 
                     ";
        if ($cate_id) { // modified code to contains the product in sub categories
            $implode_data = array();
            $sql.=" WHERE ";
            $implode_data[] = "" . DB_PREFIX . "category_description.category_id = '" . $cate_id . "'";
            $categories = $this->getCategoriesByParentId($cate_id);
            foreach ($categories as $category_id) {
                $implode_data[] = "  " . DB_PREFIX . "category_description.category_id = '" . (int) $category_id . "'";
            }
            $sql .= "  (" . implode(' OR ', $implode_data) . ")";
        }
        if ($manu_id) {
            $sql.=" AND " . DB_PREFIX . "product.manufacturer_id = $manu_id ";
        }

        $run = $this->db->query($sql);
        $results = $run->rows;
        foreach ($results as $key => $value) {
            $newCatName = $this->getParentCatNames($value['product_id']);
            $results[$key]['cat_name'] = $newCatName;
        }
        return $results;
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
                    $outputs = array();
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
        $get_unit_id_query = $this->db->query("SELECT unit_id FROM ".DB_PREFIX."unit_conversion WHERE name='units'");
        $select_temp_name_query = "SELECT name FROM temp_csv_units_data";
        $select_temp_name_result = $this->db->query($select_temp_name_query);
        $unit_conversion_unique_names = $this->_arrayUnique($select_temp_name_result->rows);
        $select_unit_name_query = "SELECT name FROM ".DB_PREFIX."unit_conversion_value "
                . "WHERE unit_id='".$get_unit_id_query->row['unit_id']."'";
        $select_unit_name_result = $this->db->query($select_unit_name_query);
        $new_unit_names = $this->_arrdiff($select_unit_name_result->rows, $unit_conversion_unique_names);
        foreach ($new_unit_names as $value) {
            $query=$this->db->query("INSERT INTO ".DB_PREFIX."unit_conversion_value SET name='".$value['name']."',"
                    . " unit_id ='".$get_unit_id_query->row['unit_id']."', language_id='1'");
        }
        $select_temp_result=$this->db->query("SELECT * FROM temp_csv_units_data");
        foreach ($select_temp_result->rows as $key => $pro_data) {
            $select_unit_value_id=$this->db->query("SELECT unit_value_id FROM "
                    . "".DB_PREFIX."unit_conversion_value WHERE unit_id='".
                        $get_unit_id_query->row['unit_id']."' "
                    . "AND name='".$pro_data['Name']."'");
            $query="SELECT convert_price FROM ".DB_PREFIX."unit_conversion_product WHERE "
                    . "unit_id='".$get_unit_id_query->row['unit_id']."' AND "
                    . "unit_value_id='".$select_unit_value_id->row['unit_value_id']."' AND "
                    . "product_id='".$pro_data['Id_Product']."'";
            $sel_data=$this->db->query($query);
            
            if($sel_data->num_rows == 0) {
                $this->db->query("INSERT INTO ".DB_PREFIX."unit_conversion_product SET "
                    . "unit_id='".$get_unit_id_query->row['unit_id']."',"
                    . "unit_value_id='".$select_unit_value_id->row['unit_value_id']."',"
                    . "convert_price='".$pro_data['Convert_units']."',"
                    . "product_id='".$pro_data['Id_Product']."'");
            } else {
                $this->db->query("UPDATE ".DB_PREFIX."unit_conversion_product SET "
                    . "convert_price='".$pro_data['Convert_units']."' WHERE "
                     . "unit_id='".$get_unit_id_query->row['unit_id']."' AND "
                    . "unit_value_id='".$select_unit_value_id->row['unit_value_id']."'AND "
                    . "product_id='".$pro_data['Id_Product']."'");
            }
            
        }
        //$this->_delRecords();
    }
    
    private function _delRecords() {
        /* $delete_tables=array('option','option_description','option_value',
        'option_value_description','product_option','product_option_value');
        $option_id=22;
        foreach ($delete_tables as $table) {
            $query="";
            $query="DELETE FROM ".DB_PREFIX.$table." WHERE "
                . "option_id>'".$option_id."'";
            $this->db->query($query);
        } */
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
    
    private function _arrayUnique($array) {
        $result = array_map("unserialize", array_unique(array_map("serialize", $array)));
        foreach ($result as $key => $value) {
            if (is_array($value)) {
                $result[$key] = $this->_arrayUnique($value);
            }
        }

        return $result;
    }
    
    private function _arrdiff($a1, $a2) {
        $res = array();
        foreach($a2 as $a) if (array_search($a, $a1) === false) $res[] = $a;
        return $res;
    }
	
	public function getGroupProductData($cate_id=0, $manu_id=0){
        $skus_data = array();
        $sd = array();
        $sku_data = '';
		
		$sql = "SELECT " . DB_PREFIX . "product.product_id, " . DB_PREFIX . "product.manufacturer_id, product_concat_temp_table.groupindicator_id
                    FROM
                    " . DB_PREFIX . "product
					INNER JOIN product_concat_temp_table
                        ON " . DB_PREFIX . "product.product_id = product_concat_temp_table.product_id
                    INNER JOIN " . DB_PREFIX . "product_to_category 
                        ON " . DB_PREFIX . "product_to_category.product_id = " . DB_PREFIX . "product.product_id";
                $sql.=" WHERE ";
				
				if ($cate_id) { 
                    $implode_data = array();
                    
                    $implode_data[] = "" . DB_PREFIX . "product_to_category.category_id = '" . $cate_id . "'";
                    $categories = $this->getCategoriesByParentId($cate_id);
                    foreach ($categories as $category_id) {
                        $implode_data[] = "  " . DB_PREFIX . "product_to_category.category_id = '" . (int) $category_id . "'";
                    }
                    $sql .= " (" . implode(' OR ', $implode_data) . ") AND ";
                }
                
                if ($manu_id) {
                    $sql.="" . DB_PREFIX . "product.manufacturer_id = $manu_id AND ";
                }
				
				 $sql .= "1";
				 
				 $run = $this->db->query($sql);
				
			    if($run->num_rows > 0){
					foreach($run->rows as $row)
					{
                    	$sd[] = "'".$row['product_id']."'";
                    	$skus_data[] = "'".$row['groupindicator_id']."'";
					}
                }
     
        $skus_data = array_unique($skus_data);
		
        if(empty($skus_data)){
            return false;
        }
        $sku_data = implode(",",$skus_data);
        $qu = "SELECT id, name, sku, product_id, groupindicator, groupindicator_id, groupbyname, groupbyvalue,groupbysortorder, "
                . "optionname1, optionvalue1, optionsort1,"
                . "optionname2, optionvalue2, optionsort2,"
                . "optionname3, optionvalue3, optionsort3,"
                . "optionname4, optionvalue4, optionsort4,"
                . "optionname5, optionvalue5, optionsort5,"
                . "optionname6, optionvalue6, optionsort6,"
                . "optionname7, optionvalue7, optionsort7,"
                . "optionname8, optionvalue8, optionsort8,"
                . "optionname9, optionvalue9, optionsort9,"
                . "optionname10, optionvalue10, optionsort10,"
                . "optionname11, optionvalue11, optionsort11,"
                . "groupedproductname"
                . " FROM product_concat_temp_table WHERE groupindicator_id IN ($sku_data)";
				
        $res = $this->db->query($qu);
        if($res->num_rows > 0){
            return $res->rows;
        }
    }
    
    /*public function getGroupProductData($cate_id=0, $manu_id=0){
        $skus_data = array();
        $sd = array();
        $sku_data = '';
        
        $query = $this->db->query("SELECT product_id from product_concat_temp_table");
        
        foreach($query->rows as $data){
            $sql = "SELECT " . DB_PREFIX . "product.product_id 
                    FROM
                    " . DB_PREFIX . "product
                    INNER JOIN " . DB_PREFIX . "manufacturer 
                        ON " . DB_PREFIX . "manufacturer.manufacturer_id = " . DB_PREFIX . "product.manufacturer_id
                    INNER JOIN " . DB_PREFIX . "product_to_category 
                        ON " . DB_PREFIX . "product_to_category.product_id = " . DB_PREFIX . "product.product_id
                    INNER JOIN " . DB_PREFIX . "category_description 
                        ON
                    " . DB_PREFIX . "category_description.category_id = " . DB_PREFIX . "product_to_category.category_id 
                     ";
                $sql.=" WHERE ";
                
                if ($cate_id) { // modified code to contains the product in sub categories
                    $implode_data = array();
                    
                    $implode_data[] = "" . DB_PREFIX . "category_description.category_id = '" . $cate_id . "'";
                    $categories = $this->getCategoriesByParentId($cate_id);
                    foreach ($categories as $category_id) {
                        $implode_data[] = "  " . DB_PREFIX . "category_description.category_id = '" . (int) $category_id . "'";
                    }
                    $sql .= " (" . implode(' OR ', $implode_data) . ") AND ";
                }
                
                if ($manu_id) {
                    $sql.="" . DB_PREFIX . "product.manufacturer_id = $manu_id AND ";
                }
                
                $sql .= "" . DB_PREFIX . "product.product_id = '" . $data['product_id'] . "' AND " . DB_PREFIX . "category_description.language_id = 1";
            
            $run = $this->db->query($sql);
            if($run->num_rows > 0){
                $result = $this->db->query("SELECT groupindicator_id FROM product_concat_temp_table WHERE product_id = '" . $run->row['product_id'] . "'");
                if($result->num_rows > 0){
                    $sd[] = "'".$run->row['product_id']."'";
                    $skus_data [] = "'".$result->row['groupindicator_id']."'";
                }
            }
        }
        $skus_data = array_unique($skus_data);
        if(empty($skus_data)){
            return false;
        }
        $sku_data = implode(",",$skus_data);
        $qu = "SELECT id, name, sku, product_id, groupindicator, groupindicator_id, groupbyname, groupbyvalue, "
                . "optionname1, optionvalue1,"
                . "optionname2, optionvalue2,"
                . "optionname3, optionvalue3,"
                . "optionname4, optionvalue4,"
                . "optionname5, optionvalue5,"
                . "optionname6, optionvalue6,"
                . "optionname7, optionvalue7,"
                . "optionname8, optionvalue8,"
                . "optionname9, optionvalue9,"
                . "optionname10, optionvalue10,"
                . "optionname11, optionvalue11,"
                . "groupedproductname"
                . " FROM product_concat_temp_table WHERE groupindicator_id IN ($sku_data)";
        $res = $this->db->query($qu);
        if($res->num_rows > 0){
            return $res->rows;
        }
    }*/
    
    public function getUnitConversionData(){
        $qu = "SELECT ucp.product_id AS Id_Product, pd.name As Product_Name, "
                . "ucv.name AS name, uc.name AS Measure, ucp.convert_price AS Convert_units, "
                . "ucp.sort_order AS Position "
                . "FROM " . DB_PREFIX . "unit_conversion_product ucp "
                . "LEFT JOIN " . DB_PREFIX . "unit_conversion uc ON (ucp.unit_id = uc.unit_id) "
                . "LEFT JOIN " . DB_PREFIX . "unit_conversion_value ucv ON (ucp.unit_value_id = ucv.unit_value_id) "
                . "LEFT JOIN " . DB_PREFIX . "product_description pd ON (ucp.product_id = pd.product_id) "
                . "WHERE pd.language_id = '1'";
        $res = $this->db->query($qu);
        if($res->num_rows > 0){
            return $res->rows;
        }
    }
}