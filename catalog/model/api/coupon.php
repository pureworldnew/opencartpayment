<?php
class ModelApiCoupon extends Model {
	public function getCouponHistoryByOrder($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "coupon_history WHERE order_id = '" . (int)$order_id . "'");
		return $query->row;
	}
	
	public function getCouponCodeById($coupon_id) {
		$query = $this->db->query("SELECT `code` FROM " . DB_PREFIX . "coupon WHERE coupon_id = '" . (int)$coupon_id . "'");
		return $query->row;
	}
}