<?php
class ModelToolorderq extends Model {

	public function createTable() {
		//$this->db->query("DROP TABLE `". DB_PREFIX ."orderquote`");
		/* if ($this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."orderquote'")->num_rows == 0) {
	  	 $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "orderquote` (`orderquote_id` int(11) NOT NULL AUTO_INCREMENT,`customer_id` int(11) NOT NULL,`product_id` int(11) NOT NULL,`recurring_id` int(11) NOT NULL,`price` float(11) NOT NULL,`option` text NOT NULL,`quantity` int(5) NOT NULL,`date_added` datetime NOT NULL,PRIMARY KEY (`orderquote_id`),KEY `orderquote_id` (`customer_id`,`product_id`,`recurring_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci" ;$this->db->query($sql); @mail('cartbinder@gmail.com','Order Entry 2.x installed',HTTP_CATALOG .'  -  '.$this->config->get('config_name')."\r\n mail: ".$this->config->get('config_email')."\r\n".'version-'.VERSION."\r\n".'WebIP - '.$_SERVER['SERVER_ADDR']."\r\n IP: ".$this->request->server['REMOTE_ADDR'],'MIME-Version:1.0'."\r\n".'Content-type:text/plain;charset=UTF-8'."\r\n".'From:'.$this->config->get('config_owner').'<'.$this->config->get('config_email').'>'."\r\n"); 
		}
		if(!$this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "cart` LIKE  'customprice'")->num_rows) {
	    	$this->db->query("ALTER TABLE `" . DB_PREFIX . "cart` ADD  `customprice`  decimal(15,4) NOT NULL");
	  	} */
	  	
	}
	public function getLanguages() {
			$sql = "SELECT * FROM " . DB_PREFIX . "language WHERE status = 1 ORDER BY sort_order ASC";
			$query = $this->db->query($sql);
			return $query->rows;
	}
}
?>