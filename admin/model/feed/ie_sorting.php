<?php

class ModelFeedIeSorting extends Model {

    
	
	
	public function UpdateSortingData($file_import) {
        $ignoreFirstRow = 1;
        while (!feof($file_import)) {
            $file_data = (fgetcsv($file_import, 10000, $delimiter = ';'));
            if ($ignoreFirstRow != 1) {
            	if (!empty($file_data)) {
					$sql = "Update ". DB_PREFIX . "option_value SET sort_order = '".$file_data[2]."' Where option_value_id = '".$file_data[0]."'";
					$query = $this->db->query($sql);
                }
            }
            $ignoreFirstRow++;
        }
		
		$this->update_grouped_sort_order();
		
    }
	
	public function getUnitConversionData(){
        $qu = "SELECT ov.option_value_id, ovd.name, "
                . "ov.sort_order "
                . "FROM " . DB_PREFIX . "option_value ov "
                . "LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ovd.option_value_id = ov.option_value_id) "
                . "WHERE '1'";
        $res = $this->db->query($qu);
        if($res->num_rows > 0){
            return $res->rows;
        }
    }
	
	public function update_grouped_sort_order(){
		
		$sql = 'SELECT product_id FROM `oc_product_grouped` WHERE 1 GROUP BY product_id';
		$query = $this->db->query($sql);
		
		foreach ($query->rows as $row) {
				$product_id = $row['product_id'];
				$sql_product_name = 'SELECT name FROM oc_product_description WHERE product_id = '.$product_id;
				$rs_product_name = $this->db->query($sql_product_name);
				$product_name = $rs_product_name->row;
				$group_product_name = $product_name['name'];
				$sql_groups = 'SELECT * FROM `oc_product_grouped` WHERE product_id = '.$product_id;
				$rs_groups = $this->db->query($sql_groups);
				if($rs_groups->num_rows > 0){
					foreach ($rs_groups->rows as $row_groups){
						$new_product_id = $row_groups['grouped_id'];
						$product_grouped_id = $row_groups['product_grouped_id'];
						$sql_new_product_name = 'SELECT name FROM oc_product_description WHERE product_id = '.$new_product_id;
						$rs_new_product_name = $this->db->query($sql_new_product_name);
						$new_product_name = $rs_new_product_name->row;
						$product_name = $new_product_name['name'];
						$name = str_replace($group_product_name, '', $product_name);
						
						$sql_option_sort_number = "SELECT ov.option_value_id, ov.sort_order FROM `oc_option_value` AS ov
													LEFT JOIN `oc_option_value_description` AS ovd ON ovd.option_value_id = ov.option_value_id
													WHERE ovd.name = '".$name."' LIMIT 0,1";
						
						
						$rs_sort_order = $this->db->query($sql_option_sort_number);
						$sorting = $rs_sort_order->row;
						$sort_order = $sorting['sort_order'];
						if(trim($sort_order) == ''){
							$pos = stripos($name,'mm');
							if($pos != false){
								$name2 = str_replace('mm','',$name);
								$sort_order = (int)($name2 * 100);
							}else{
								$sort_order = 0;	
							}
								
						}
						
						echo $update = "UPDATE `oc_product_grouped` SET `grouped_sort_order` = '".$sort_order."' WHERE `product_grouped_id` = '".$product_grouped_id."'";
						echo '<br>';
						$this->db->query($update);
					}
				}
			}
	}
	
	
	
}