<?php
/*
  #file: catalog/model/catalog/product_bundle.php
  #powered by fabiom7 - www.fabiom7.com - fabiome77@hotmail.it - copyright fabiom7 2012 - 2013 - 2014
*/

class ModelCatalogProductBundle extends Model {
	public function getProductGroupedType($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_grouped_type WHERE product_id = '" . (int)$product_id . "' LIMIT 1");
		
		if ($query->num_rows) {
			return array(
				'pg_type'   => $query->row['pg_type'],
				'pg_layout' => $query->row['pg_layout']
			);
		} else {
			return false;
		}
	}
	
	public function getProductRelatedIsGrouped($product_id) {
		$query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product_grouped WHERE product_id = '" . $product_id . "' LIMIT 1");
		
		return $query->num_rows;
	}
	
	public function getGrouped($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_grouped WHERE product_id = '" . $product_id . "' ORDER BY grouped_sort_order");
		
		return $query->rows;
	}
	
	public function getProductGroupedDiscount($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_grouped_discount WHERE product_id = '" . (int)$product_id . "'");
		
		if ($query->num_rows) {
			return $query->row;
		} else {
			return false;
		}
	}
}
?>