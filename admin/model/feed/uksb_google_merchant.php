<?php
class ModelFeedUKSBGoogleMerchant extends Model {
	public function getTotalProductsByStore($store_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_to_store pts LEFT JOIN " . DB_PREFIX . "product p on (p.product_id = pts.product_id) WHERE pts.store_id = '" . (int)$store_id . "' AND p.status = 1 Order By store_id ASC");

		return $query->row['total'];
	}			
}
?>