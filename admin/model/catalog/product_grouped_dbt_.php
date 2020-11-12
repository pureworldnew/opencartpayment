<?php
/* 
  #file: admin/model/catalog/product_grouped_dbt.php
  #powered by fabiom7 - www.fabiom7.com - fabiome77@hotmail.it - copyright fabiom7 2012 - 2013 - 2014
  #switched: v1.5.4.1 - v1.5.5.1 - v1.5.6
*/

class ModelCatalogProductGroupedDbt extends Model {
	public function checkInstall() {
		if (false === mysql_query("SELECT * FROM " . DB_PREFIX . "product_grouped LIMIT 0")) {
			$operation = $this->installProductGrouped();
		} else {
			$operation = $this->upgradeProductGrouped();
		}
		
		if (isset($this->request->get['_ms'])) {
			$url = $this->url->link('extension/product_grouped', 'token=' . $this->session->data['token'] . '&_dbt=_del&_ms=1', 'SSL');
		} else {
			$url = $this->url->link('extension/product_grouped', 'token=' . $this->session->data['token'] . '&_dbt=_del', 'SSL');
		}
		
		return $operation . '<p><span style="color:#ff0;">The install file must be deleted in order to use "Grouped Product".<br />Path: admin/model/catalog/ product_grouped_dbt.php</span> <span style="color:#fff;">(<a style="color:#fff;" href="' . $url . '">Click here</a> or remove it manually)</span></p>';
	}
	
	public function installProductGrouped() {
		$operation = ''; $br = '<br />'; $success = '<span style="color:#fff;">Success: </span>'; $error = '<span style="color:red;">Error: </span>';
		
		// Table product
		$table = DB_PREFIX . 'product';
		if (false === mysql_query("SELECT pgvisibility FROM " . $table . " LIMIT 0")) {
			$sql = "ALTER TABLE `" . $table . "` ADD `pgvisibility` TINYINT(1) NOT NULL DEFAULT '1'"; $this->db->query($sql);
			if(mysql_query("SELECT pgvisibility FROM " . $table . " LIMIT 0")){ $operation .= $success . $sql . $br; }else{ $operation .= $error . $sql . $br; }
		}
		if (false === mysql_query("SELECT pgprice_from FROM " . $table . " LIMIT 0")) {
			$sql = "ALTER TABLE `" . $table . "` ADD `pgprice_from` decimal(15,4) NOT NULL DEFAULT '0.0000'"; $this->db->query($sql);
			if(mysql_query("SELECT pgprice_from FROM " . $table . " LIMIT 0")){ $operation .= $success . $sql . $br; }else{ $operation .= $error . $sql . $br; }
		}
		if (false === mysql_query("SELECT pgprice_to FROM " . $table . " LIMIT 0")) {
			$sql = "ALTER TABLE `" . $table . "` ADD `pgprice_to` decimal(15,4) NOT NULL DEFAULT '0.0000'"; $this->db->query($sql);
			if(mysql_query("SELECT pgprice_to FROM " . $table . " LIMIT 0")){ $operation .= $success . $sql . $br; }else{ $operation .= $error . $sql . $br; }
		}
		
		// Table product_description
		$table = DB_PREFIX . 'product_description';
		if (false === mysql_query("SELECT tag_title FROM " . $table . " LIMIT 0")) {
			$sql = "ALTER TABLE `" . $table . "` ADD `tag_title` VARCHAR(99) NOT NULL"; $this->db->query($sql);
			if(mysql_query("SELECT tag_title FROM " . $table . " LIMIT 0")){ $operation .= $success . $sql . $br; }else{ $operation .= $error . $sql . $br; }
		}
		
		// Table product_grouped
		$table = DB_PREFIX . 'product_grouped';
		if (false === mysql_query("SELECT * FROM " . $table . " LIMIT 0")) {
			$sql = "CREATE TABLE IF NOT EXISTS `" . $table . "` (`product_grouped_id` int(11) NOT NULL auto_increment, `product_id` int(11) NOT NULL, `grouped_id` int(11) NOT NULL, `grouped_maximum` SMALLINT(1) NOT NULL DEFAULT '0', `grouped_sort_order` int(11) NOT NULL default '0', `grouped_stock_status_id` int(11) NOT NULL, `is_starting_price` tinyint(1) NOT NULL default '0', PRIMARY KEY (`product_grouped_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;"; $this->db->query($sql);
			if(mysql_query("SELECT * FROM " . $table . " LIMIT 0")){ $operation .= $success . $sql . $br; }else{ $operation .= $error . $sql . $br; }
		}
		
		// Table product_grouped_type
		$table = DB_PREFIX . 'product_grouped_type';
		if(false === mysql_query("SELECT * FROM " . $table . " LIMIT 0")){
			$sql = "CREATE TABLE IF NOT EXISTS `" . $table . "` (`product_id` int(11) NOT NULL, `pg_type` varchar(16) character set utf8 NOT NULL, `pg_layout` varchar(16) character set utf8 NOT NULL DEFAULT 'default', PRIMARY KEY (`product_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;"; $this->db->query($sql);
			if(mysql_query("SELECT * FROM " . $table . " LIMIT 0")){ $operation .= $success . $sql . $br; }else{ $operation .= $error . $sql . $br; }
		}
		
		// Table product_grouped_discount
		$table = DB_PREFIX . 'product_grouped_discount';
		if(false === mysql_query("SELECT * FROM " . $table . " LIMIT 0")){
			$sql = "CREATE TABLE IF NOT EXISTS `" . $table . "` (`product_grouped_discount_id` int(11) NOT NULL auto_increment, `product_id` INT(11) NOT NULL, `discount` decimal(15,4) NOT NULL DEFAULT '0.0000', `type` CHAR(1) NOT NULL DEFAULT 'F', PRIMARY KEY (`product_grouped_discount_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;"; $this->db->query($sql);
			if(mysql_query("SELECT * FROM " . $table . " LIMIT 0")){ $operation .= $success . $sql . $br; }else{ $operation .= $error . $sql . $br; }
		}
		
		if ($operation) {
			return '<p style="color:#ff0;">Starting first running installation...</p><br />' . $operation;
		} else {
			return $operation;
		}
	}
	
	public function upgradeProductGrouped() {
		$operation = ''; $br = '<br />'; $success = '<span style="color:#fff;">Success: </span>'; $error = '<span style="color:red;">Error: </span>';
		
		// Table product_grouped
		$table = DB_PREFIX . 'product_grouped';
		if (false === mysql_query("SELECT is_starting_price FROM " . $table . " LIMIT 0")) {
			$sql = "ALTER TABLE `" . $table . "` ADD `is_starting_price` tinyint(1) NOT NULL default '0'"; $this->db->query($sql);
			if(mysql_query("SELECT is_starting_price FROM " . $table . " LIMIT 0")){ $operation .= $success . $sql . $br; }else{ $operation .= $error . $sql . $br; }
		}
		if (false === mysql_query("SELECT grouped_maximum FROM " . $table . " LIMIT 0")) {
			$sql = "ALTER TABLE `" . $table . "` ADD `grouped_maximum` tinyint(1) NOT NULL default '0'"; $this->db->query($sql);
			if(mysql_query("SELECT grouped_maximum FROM " . $table . " LIMIT 0")){ $operation .= $success . $sql . $br; }else{ $operation .= $error . $sql . $br; }
		}
		
		// Table product_grouped_type
		$table = DB_PREFIX . 'product_grouped_type';
		if (false === mysql_query("SELECT pg_layout FROM " . $table . " LIMIT 0")) { //v5.0
			$sql = "ALTER TABLE `" . $table . "` ADD `pg_layout` varchar(16) character set utf8 NOT NULL DEFAULT 'default'"; $this->db->query($sql);
			if(mysql_query("SELECT pg_layout FROM " . $table . " LIMIT 0")){ $operation .= $success . $sql . $br; }else{ $operation .= $error . $sql . $br; }
			
			// S Aggiornamento prodotti con peso
			$weight_query = $this->db->query("SELECT DISTINCT pg.product_id, pgt.product_type FROM " . DB_PREFIX . "product_grouped pg LEFT JOIN " . DB_PREFIX . "product p ON(pg.grouped_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_grouped_type pgt ON(pg.product_id = pgt.product_id) WHERE p.weight > '0' AND pgt.product_type = 'config'");
			if ($weight_query->num_rows) { $p=1; $tot = count($weight_query->rows);
				$operation .= 'Updating existing Grouped Products configurable by weight. (Total:'.$tot.')<br />';
				foreach($weight_query->rows as $result){
					$this->db->query("UPDATE `". DB_PREFIX ."product_grouped_type` SET `pg_layout` = 'weight2cols' WHERE `product_id` = '". $result['product_id'] ."'");
					$operation .= $p.'/'.$tot.': Update ProductID: '. $result['product_id'] . '<br />'; $p++;
				}
				if(($p-1)==$tot){ $operation .= $success . 'Products updated.<br />'; }else{ $operation .= $error . 'Check configurable product by weight, save it.<br />'; }
			}
			// E Aggiornamento prodotti con peso
		}
		if (false === mysql_query("SELECT pg_type FROM " . $table . " LIMIT 0")) { //v5.0
			$sql = "ALTER TABLE `" . $table . "` CHANGE `product_type` `pg_type` VARCHAR(16) NOT NULL"; $this->db->query($sql);
			if(mysql_query("SELECT pg_type FROM " . $table . " LIMIT 0")){ $operation .= $success . $sql . $br; }else{ $operation .= $error . $sql . $br; }
			$sql = "UPDATE `" . $table . "` SET `pg_type` = 'configurable' WHERE `pg_type` = 'config'"; $this->db->query($sql);
		}
		
		// Table product_grouped_discount
		$table = DB_PREFIX . 'product_grouped_discount';
		if (false === mysql_query("SELECT type FROM " . $table . " LIMIT 0")) { //v3.1
			$sql = "ALTER TABLE `" . $table . "` CHANGE `product_discount_bundle` `discount` DECIMAL(15,4) NOT NULL DEFAULT '0.0000'"; $this->db->query($sql);
			if(mysql_query("SELECT discount FROM " . $table . " LIMIT 0")){ $operation .= $success . $sql . $br; }else{ $operation .= $error . $sql . $br; }
			$sql = "ALTER TABLE `" . $table . "` ADD `type` CHAR(1) NOT NULL DEFAULT 'F'"; $this->db->query($sql);
			if(mysql_query("SELECT type FROM " . $table . " LIMIT 0")){ $operation .= $success . $sql . $br; }else{ $operation .= $error . $sql . $br; }
		}
		
		if ($operation) {
			return '<p style="color:#ff0;">Starting upgrade process...</p><br />' . $operation;
		} else {
			return $operation;
		}
	}
}
?>