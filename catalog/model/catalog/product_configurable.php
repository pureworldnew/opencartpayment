<?php
/*
  #file: catalog/model/catalog/product_configurable.php
  #powered by fabiom7 - www.fabiom7.com - fabiome77@hotmail.it - copyright fabiom7 2012 - 2013 - 2014
*/

class ModelCatalogProductConfigurable extends Model {
	public function getProductGroupedType($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_grouped_type WHERE product_id = '" . (int)$product_id . "' LIMIT 1");
		
		if ($query->num_rows) {
			return $query->row;
		} else {
			return false;
		}
	}
	
	public function getProductRelatedIsGrouped($product_id) {
		$query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product_grouped WHERE product_id = '" . $product_id . "' LIMIT 1");
		
		return $query->num_rows;
	}
	
	public function getProductGroupedDiscount($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_grouped_discount WHERE product_id = '" . (int)$product_id . "'");
		
		if ($query->num_rows) {
			return $query->row;
		} else {
			return false;
		}
	}
	
	public function getProductConfigurable($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_grouped_configurable WHERE product_id = '" . (int)$product_id . "' AND language_id='" . (int)$this->config->get('config_language_id') . "' ORDER BY option_sort_order ASC");
		
		return $query->rows;
	}
	
	public function getChildStockStatusId($product_id, $child_id) {
		$query = $this->db->query("SELECT grouped_stock_status_id FROM " . DB_PREFIX . "product_grouped WHERE product_id = '" . $product_id . "' AND grouped_id = '" . $child_id . "' LIMIT 1");
		
		return $query->row['grouped_stock_status_id'];
	}
}
?>