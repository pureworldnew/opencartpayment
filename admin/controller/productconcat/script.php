<?php

/**
 * @author : Manish Gautam
 * This script concats products to grouped products.
 * Steps:
 * 1) Importing all data from csv into a temporary table
 * 2) Getting all products in groups based on grouped by value
 * 3) Forming Sub groups based on group indicators
 * 4) Processing SubGroups 
 *      i)  Getting products based on group indicator and group by value(grouping 
 *          factor)
 *      ii) Getting product id of the first product of the group.Then
 *          a) Renaming product
 *          b) Removing all its option
 *      iii) Processing all the products and adding all options to all the products
 * 5) Then creating grouped products and adding processed products to the group.
 * */
ini_set('max_execution_time', 0);
ini_set('max_execution_time', 0);
set_time_limit(0);

class ControllerProductConcatScript extends Controller {

    private $table_name = "product_concat_temp_table";
    private $csv_file;
    private $csv_data;
    private $option_max_depth = 11;
    private $product_id;
    private $group_product_array;
    public $error = array('text' => '', 'type' => '');

    public function index() {
        if (isset($this->session->data['concatFile'])) {
            $csv = $this->session->data['concatFile'];
        } else {
            $this->error['text'] = "OOPs no CSV file found. Please upload CSV.";
            $this->error['type'] = 'warning';
            echo json_encode($this->error);
        }
        $this->csv_file = DIR_DOWNLOAD . '/' . $csv;
        $this->load->model('productconcat/script');
        $this->load->model('catalog/product_grouped');
        /** Step 1 Process CSV and Save data to Temporary table */
        
		$this->error = $this->_saveCsvToTempTable();
		echo json_encode($this->error);
		/*
		if ($this->_saveCsvToTempTable()) {
            $this->error['text'] = "Step 2: Complete (CSV Processed to temporary data).";
            $this->error['type'] = 'success';
            echo json_encode($this->error);
        }else{
			
		}*/
    }

    public function concat() {
        $this->load->model('productconcat/script');
        $this->load->model('catalog/product_grouped');
        $this->_groupProducts();
        $this->model_productconcat_script->duplicateNameChange();
        $this->model_productconcat_script->updateProductNames($this->session->data['csv_data']);
        unset($this->session->data['csv_data']);
        /** Response */
        $this->error['text'] = "Step 3: Complete (Products Concatinated to grouped Products). All Process Complete.";
        $this->error['type'] = 'success';
        echo json_encode($this->error);
    }

    /**
     * Main Group Function
     */
    private function _groupProducts() {
        $all_groups = $this->model_productconcat_script->getAllGroups();
        foreach ($all_groups as $key => $group) {
            if (!empty($group['groupindicator_id'])) {
                $product_ids = array();
                $sub_groups = $this->model_productconcat_script->getGroups($group['groupindicator_id']);
                
                foreach ($sub_groups as $sub_group) {
                    $prodId = $this->_groupProductsByGroupValue($group['groupindicator_id'], $sub_group['groupbyvalue']);
                    if ($prodId != '') {
                        $product_ids[] = $prodId;
                    }
                }

                if (!empty($product_ids)) {
                    $product_ids = array_filter($product_ids); //to remove false values from array
                    $this->model_productconcat_script->detachProductFromGroup($product_ids); //remove these products from current grouping
                    $this->_groupProduct($product_ids, $group['groupindicator_id'], $group['groupbyname'], $product_ids[0]);
                    $this->model_productconcat_script->updateGroupedStatus($group['groupindicator_id']);
                }
            }
        }
    }

    /**
     * 
     * @param type $products
     * @param type $group_indicator
     * @param type $group_by_name
     * @param type $product_id
     * @return boolean
     */
    private function _groupProduct($products, $group_indicator_id, $group_by_name, $product_id) {
        $this->group_product_array = array();
        $this->load->model('catalog/product');
        $this->group_product_array['product_description'] = $this->_getGroupedProductDescription($group_indicator_id);
        $this->group_product_array['group_list'] = $this->_getGroupedProducts($products);
        $this->group_product_array['groupby'] = $group_by_name;
        $this->group_product_array['tax_class_id'] = 5;
        $this->group_product_array['product_category'] = $this->model_catalog_product->getProductCategories($product_id);
        $this->_setOthers($products);

        /** Check for Group Exist */
        $check = $this->model_productconcat_script->checkGroupProductExist($group_indicator_id);
        if (!$check) {
            /* Insert Grouped product */
            $group_product_id = $this->model_catalog_product_grouped->addProduct($this->group_product_array);
            $name = $this->model_productconcat_script->getGroupedNameById($group_product_id);
            $this->model_productconcat_script->updateSeoUrl($group_product_id, $this->_clean($name));
            $this->model_productconcat_script->saveGroupIndicator($group_indicator_id, $group_product_id);
        } else {
            /* Update Grouped Product */
            $name = $this->group_product_array['product_description'][1]['name'];
            $group_product_id = $check[0]['group_product_id'];
            $this->model_catalog_product_grouped->editProduct($group_product_id, $this->group_product_array);
            $this->model_productconcat_script->updateSeoUrl($group_product_id, $this->_clean($name));
        }
        return true;
    }

    private function _clean($name) {
        $name = str_replace('&', 'and', $name);
        $name = str_replace(' ', '-', $name);
        return preg_replace('/[^A-Za-z0-9\-]/', '', $name);
    }

    private function _setOthers($products) {
        $this->group_product_array['date_available'] = date('Y-m-d', time() - 86400);
        $this->group_product_array['status'] = 1;
        $this->group_product_array['sort_order'] = 1;
        $this->group_product_array['manufacturer_id'] = 0;
        $this->group_product_array['product_store'] = array(0);
        $this->group_product_array['pg_layout'] = 'default';
        $this->group_product_array['is_starting_price'] = $this->model_productconcat_script->getMinCost($products, TRUE);
        $this->group_product_array['price'] = $this->model_productconcat_script->getMinCost($products);
        $this->group_product_array['quantity'] = '99';
        $this->group_product_array['model'] = 'grouped';
        $this->group_product_array['length_class_id'] = '3';
        $this->group_product_array['weight_class_id'] = '5';
        $this->group_product_array['sku'] = '';
        $this->group_product_array['image'] = $this->model_productconcat_script->getProductImageById($products[0]);
        $this->group_product_array['weight'] = '';
        $this->group_product_array['length'] = '';
        $this->group_product_array['width'] = '';
        $this->group_product_array['height'] = '';
        $other_data = $this->model_productconcat_script->getProductOtherData($products[0]);
        $this->group_product_array['unit_singular'] = !empty($other_data['unit_singular']) ? $other_data['unit_singular'] : '';
        $this->group_product_array['unit_plural'] = !empty($other_data['unit_plural']) ? $other_data['unit_plural'] : '';
        $this->group_product_array['tax_class_id'] = !empty($other_data['tax_class_id']) ? $other_data['tax_class_id'] : 0;
        $this->group_product_array['keyword'] = '';
    }

    /**
     * This function returns data to be grouped under group_list in array
     * @param type $products
     * @return type
     */
    private function _getGroupedProducts($products) {
        $group_list = array();
        foreach ($products as $key => $product) {
            $group_list[$product] = array(
                'grouped_maximum' => 0,
                'grouped_id' => $product,
                'pgvisibility' => 2,
                'product_sort_order' => $this->getGroupbySortorder($product),
                'grouped_stock_status_id' => 0
            );
        }
        return $group_list;
    }

    public function getGroupbySortorder($product_id)
    {
        $query = $this->db->query("SELECT groupbysortorder FROM product_concat_temp_table WHERE product_id = '" . $product_id . "'");
        return $query->row ? $query->row['groupbysortorder'] : 0;
    } 

    /**
     * Function returns product description from group indicator
     * @param type $group_indicator
     * @return string
     */
    private function _getGroupedProductDescription($group_indicator_id) {
        $description = array();
        $this->load->model('localisation/language');
        $grouped_product_name = $this->model_productconcat_script->getGroupedProductName($group_indicator_id);
        foreach ($this->model_localisation_language->getLanguages() as $lang) {
            $description[$lang['language_id']] = array(
                'name' => $grouped_product_name,
                'tag_title' => '',
                'meta_description' => '',
                'meta_keyword' => '',
                'description' => '',
                'tag' => ''
            );
        }
        return $description;
    }

    private function _groupProductsByGroupValue($group_indicator_id, $group_by_value) {
        $this->product_id = '';
        $group_products = $this->model_productconcat_script->getGroupProducts($group_indicator_id, $group_by_value);
        $num_product_in_group = count($group_products);

        $this->product_id = $this->model_productconcat_script->checkproductId($group_products[0]['product_id']);
//      $this->product_id = $this->model_productconcat_script->getproductIdBySku($group_products[0]['product_id']);
        if ($this->product_id == 'disabled') {
            if ($num_product_in_group > 1) {
                for ($i = 1; $i < $num_product_in_group; $i++) {
                    $this->product_id = $this->model_productconcat_script->checkproductId($group_products[$i]['product_id']);
                    //$this->product_id = $this->model_productconcat_script->getproductIdBySku($group_products[$i]['sku']);
                    if ($this->product_id && $this->product_id != 'disabled') {
                        break;
                    }
                }
            }
        }
        if (!$this->product_id || $this->product_id == 'disabled') {
            return FALSE;
        }

        foreach ($group_products as $group_product) {
            $group_product_id = $this->model_productconcat_script->checkproductId($group_product['product_id']);
            $this->model_productconcat_script->detachProductOptions($group_product_id);
            $this->model_productconcat_script->renameProduct($group_product_id, $group_products[0]['groupbyvalue'], $group_products[0]['groupedproductname']);
            if ($group_product_id != $this->product_id) {
                $this->model_productconcat_script->hideProduct($group_product_id, $group_product);
                for ($i = 0; $i < $num_product_in_group; $i++) {
                    $this->_addHiddenProductOptions($group_product_id, $group_products[$i]);
                }
            }
            $this->_addProductOptions($group_product);
        }
        return $this->product_id;
    }

    private function _addProductOptions($group_product) {
        if ($this->product_id) {
            for ($i = 1; $i <= $this->option_max_depth; $i++) {
                if (!empty($group_product['optionname' . $i])) {
                    $this->_optionCheckCreate($this->product_id, $group_product['optionname' . $i], $group_product['optionvalue' . $i], $group_product['optionsort' . $i]);
                }
            }
        }
    }

    private function _addHiddenProductOptions($product_id, $group_product) {
        for ($i = 1; $i <= $this->option_max_depth; $i++) {
            if (!empty($group_product['optionname' . $i])) {
                $this->_optionCheckCreate($product_id, $group_product['optionname' . $i], $group_product['optionvalue' . $i], $group_product['optionsort' . $i]);
            }
        }
    }

    private function _optionCheckCreate($product_id, $option_name, $option_value, $option_sort) {
        $option_id = $this->model_productconcat_script->checkCreateOption($option_name, 'select'); //option id

        if ($option_id) {
            $option_value_id = $this->model_productconcat_script->checkCreateOptionValue($option_id, $option_value); //option value _id 

            if ($option_value_id) {
                $this->model_productconcat_script->checkCreateProductOption($product_id, $option_id, $option_value_id, $option_sort); //product option id, product option value id
            }
        }
    }

    //saving csv file to temp table
    private function _saveCsvToTempTable() {
        if ($this->_createTableifNotExist()) {
            return $this->_saveData();
        } else {
            echo "There was some problem while creating table";
            die;
        }
    }

    private function _saveData() {
        $this->csv_data = $this->csv_to_array();
        $this->session->data['csv_data'] = $this->csv_data;
        return $this->model_productconcat_script->saveTempData($this->table_name, $this->csv_data);
    }

    private function _createTableifNotExist() {
        if ($this->model_productconcat_script->checkifTableExists($this->table_name)) {
            return TRUE;
        } else {
            return $this->_createTable();
        }
    }

    private function _createTable() {
        $columns = $this->_getTableColumns();
        $modified_columns = $this->_modifyColumns($columns);
        if ($this->model_productconcat_script->createTable($this->table_name, $modified_columns)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function _getTableColumns() {
        $delimiter = ';';
        if (!file_exists($this->csv_file) || !is_readable($this->csv_file)) {
            echo "File Not exists/readable";
            die;
        }
        $header = FALSE;
        if (($handle = fopen($this->csv_file, 'r')) !== FALSE) {
            while (($row = fgetcsv($handle, 2000, $delimiter)) !== FALSE) {
                return $row;
            }
        }
    }

    private function _modifyColumns($columns) {
        $modified = array();
        foreach ($columns as $column) {
            $modified[] = strtolower(preg_replace("/[^\d\w]/i", "", $column));
        }
        return $modified;
    }

    /**
     * Function to convert csv to array
     * 
     * @param string $delimiter
     * @return array in case of error it will return false
     */
    public function csv_to_array() {
        $delimiter = ';';
        if (!file_exists($this->csv_file) || !is_readable($this->csv_file)) {
            echo "File Not exists/readable";
            die;
        }
        $header = NULL;
        $data = array();
        if (($handle = fopen($this->csv_file, 'r')) !== FALSE) {
            while (($row = fgetcsv($handle, 2000, $delimiter)) !== FALSE) {
                if (!$header) {
                    $header = $this->_modifyColumns($row);
                } else {

                    //Logic to handle incomplet row
                    if (count($header) != count($row)) {
                        $h_count = count($header);
                        $r_count = count($row);
                        $dif = $h_count - $r_count;
                        $d = array_fill($r_count, $dif, '');
                        ;
                        $row = $row + $d;
                    }
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }
        return $data;
    }

}
