<?php
class ModelCatalogWkquickorder extends Model {

	public function getProductConcatData($product_id)
	{
		$query = $this->db->query("SELECT * FROM product_concat_temp_table WHERE product_id = '".(int)$product_id."'");
		return $query->row; 
	}

	public function _optionCheckCreate($product_id, $option_name, $option_value) 
	{
		$option_id = $this->checkCreateOption($option_name, 'select'); //option id
	
		if ($option_id) {
			$option_value_id = $this->checkCreateOptionValue($option_id, $option_value); //option value _id 
	
			if ($option_value_id) {
				return $this->getProductOptionValueId($product_id, $option_id, $option_value_id);	
			}
		}

		return 0;
	}

	public function checkCreateOptionValue($option_id, $option_value) {
		$check = $this->db->query("SELECT option_value_id FROM " . DB_PREFIX . "option_value_description "
				. "WHERE option_id='" . $option_id . "' AND name='" . $option_value . "'");
	
		if (!$check->num_rows) {
			
		} else {
			return $check->row['option_value_id'];
		}
	}
	
	public function checkCreateOption($option_name, $option_type = null) {
		if (is_null($option_type)) {
			$option_type = 'select';
		}
		$check = $this->db->query("SELECT o.option_id FROM " . DB_PREFIX . "option o LEFT JOIN " . DB_PREFIX . "option_description od ON o.option_id=od.option_id WHERE od.name='" . $this->db->escape($option_name) . "' AND LOWER(o.type)='" . $option_type . "'");
		if ($check->num_rows == 0) {
		
		} else {
			return $check->row['option_id'];
		}
	}

	public function getProductOptionValueId($product_id, $option_id, $option_value_id)
	{
		$query = $this->db->query("SELECT product_option_value_id FROM " . DB_PREFIX . "product_option_value WHERE option_id = '" . $option_id . "' AND option_value_id = '" . $option_value_id . "' AND product_id ='" . $product_id . "'");
		return $query->row ? $query->row['product_option_value_id'] : 0;
	}
}