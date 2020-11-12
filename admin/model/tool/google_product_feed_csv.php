<?php
class ModelToolGoogleProductFeedCsv extends Model {


	public function getVisitorData($data) {
		$query = $this->db->query("SELECT * from  `" . DB_PREFIX . "google_shopping_tracker` WHERE visitor_date BETWEEN '".$data['date_from']."' AND '".$data['date_to']."'");

$fp = fopen('debug.log', 'w'); 
fwrite ($fp, "SELECT * from  `" . DB_PREFIX . "google_shopping_tracker` WHERE visitor_date BETWEEN '".$data['date_from']."' AND '".$data['date_to']."'");
fclose ($fp);	
	
		if ($query->num_rows) {
			return $query->rows;
		} else {
			return false;
		}
		
	}

	public function getProduct($product_id) {
		$query = $this->db->query("SELECT name from  `" . DB_PREFIX . "product_description` WHERE product_id = '" . (int)$product_id . "'");
		
		return $query->row;		
	}
	
	
}
?>