<?php
class ModelAccountTmdaccount extends Model {
		
	public function getOrders($start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 1;
		}

		$query = $this->db->query("SELECT o.order_id, o.firstname, o.lastname, os.name as status, o.date_added, o.total, o.currency_code, o.currency_value, op.product_id, op.quantity FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) LEFT JOIN ". DB_PREFIX ."order_product op on (o.order_id = op.order_id) WHERE o.customer_id = '" . (int)$this->customer->getId() . "' AND o.order_status_id > '0' AND o.order_type NOT IN (2,3) AND o.store_id = '" . (int)$this->config->get('config_store_id') . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.order_id DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}
	
	public function getProductData($product_id = 0)
	{
		$query = $this->db->query("SELECT p.image, pd.name FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}
	
	 public function getShowAccount($customer_id){
		$sql="select * from " . DB_PREFIX . "customer where customer_id='".$this->customer->getId()."'";
		$query=$this->db->query($sql);
		return $query->row;
	}
	
	public function getShowAddress($customer_id){
		$sql="select * from " . DB_PREFIX . "address where customer_id='".$this->customer->getId()."'";
		$query=$this->db->query($sql);
		return $query->row;
	}
	
	public function getTotalOrder($customer_id){
		$sql="SELECT COUNT(*) as total FROM " . DB_PREFIX . "order WHERE customer_id ='".$this->customer->getId()."' AND order_status_id > '0' AND order_type NOT IN (2,3) AND store_id = '" . (int)$this->config->get('config_store_id') . "'";
		$query=$this->db->query($sql);
		return $query->row['total'];
	}
	
	
	public function getTotalRewardPoints($customer_id){
		$sql="select count(*) as total from " . DB_PREFIX . "customer_reward where customer_id='".$this->customer->getId()."'";
		$query=$this->db->query($sql);
		return $query->row['total'];
		
	}
	
	public function getTotalTransaction($customer_id){
		$sql="select count(*) as total from " . DB_PREFIX . "customer_transaction where customer_id='".$this->customer->getId()."'";
		$query=$this->db->query($sql);
		return $query->row['total'];
		
	}
	
	public function getTotalDownload($customer_id){
			$sql="select count(*) as total from " . DB_PREFIX . "product_to_download pd LEFT JOIN ". DB_PREFIX ."order_product op ON(pd.product_id = op.product_id) LEFT JOIN ". DB_PREFIX ."order o ON(op.order_id= o.order_id ) WHERE o.customer_id='".$this->customer->getId()."'";
		
		$query=$this->db->query($sql);
		return $query->row['total'];
		
	}
	
	public function getTotalOrders() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` o WHERE customer_id = '" . (int)$this->customer->getId() . "' AND o.order_status_id > '0' AND o.order_type NOT IN (2,3) AND o.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		return $query->row['total'];
	}
	
	public function getTotalWishlist($customer_id){
		$sql="select count(*) as total from " . DB_PREFIX . "customer_wishlist where customer_id='".$this->customer->getId()."'";
		$query=$this->db->query($sql);
		return $query->row['total'];
	}
	
	
	
}
