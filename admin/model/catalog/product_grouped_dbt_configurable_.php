<?php
/* 
  #file: admin/model/catalog/product_grouped_dbt_configurable.php
  #powered by fabiom7 - www.fabiom7.com - fabiome77@hotmail.it - copyright fabiom7 2012 - 2013 - 2014
  #switched: v1.5.4.1 - v1.5.5.1 - v1.5.6
*/

class ModelCatalogProductGroupedDbtConfigurable extends Model {
	public function checkInstall() {
		if (false === mysql_query("SELECT * FROM " . DB_PREFIX . "product_grouped_configurable LIMIT 0")) {
			$operation = $this->installProductConfigurable();
		} else {
			$operation = $this->upgradeProductConfigurable();
		}
		
		if (isset($this->request->get['_ms'])) {
			$url = $this->url->link('extension/product_grouped', 'token=' . $this->session->data['token'] . '&_dbt=_del&_ms=1', 'SSL');
		} else {
			$url = $this->url->link('extension/product_grouped', 'token=' . $this->session->data['token'] . '&_dbt=_del', 'SSL');
		}
		
		return $operation . '<p><span style="color:#ff0;">The install file must be deleted in order to use "Grouped Product".<br />Path: admin/model/catalog/ product_grouped_dbt_configurable.php</span> <span style="color:#fff;">(<a style="color:#fff;" href="' . $url . '">Click here</a> or remove it manually)</span></p>';
	}
	
	public function installProductConfigurable() {
		$operation = ''; $br = '<br />'; $success = '<span style="color:#fff;">Success: </span>'; $error = '<span style="color:red;">Error: </span>';
		
		// Table product_grouped_configurable
		$table = DB_PREFIX . 'product_grouped_configurable';
		if (false === mysql_query("SELECT * FROM " . $table . " LIMIT 0")) {
			$sql = "CREATE TABLE IF NOT EXISTS `" . $table . "` (`id` int(11) NOT NULL auto_increment, `product_id` int(11) NOT NULL, `option_type` varchar(3) NOT NULL DEFAULT '0', `option_sort_order` INT(2) NOT NULL DEFAULT '0', `option_required` tinyint(1) NOT NULL DEFAULT '0', `option_min_qty` varchar(7) NOT NULL default '1', `option_hide_qty` tinyint(1) NOT NULL DEFAULT '0', `language_id` INT(11) NOT NULL, `option_name` varchar(255) NOT NULL, `child_id` VARCHAR(255) NOT NULL, `child_to_hide` VARCHAR(255) NOT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;"; $this->db->query($sql);
			if(mysql_query("SELECT * FROM " . $table . " LIMIT 0")){ $operation .= $success . $sql . $br; }else{ $operation .= $error . $sql . $br; }
		}
		
		if ($operation) {
			return '<p style="color:#ff0;">Starting first running installation...</p><br />' . $operation;
		} else {
			return $operation;
		}
	}
	
	public function upgradeProductConfigurable() {
		$operation = ''; $br = '<br />'; $success = '<span style="color:#fff;">Success: </span>'; $error = '<span style="color:red;">Error: </span>';
		
		// Table product_grouped_configurable
		$table = DB_PREFIX . 'product_grouped_configurable';
		if (false === mysql_query("SELECT option_sort_order FROM " . $table . " LIMIT 0")) { //v3.2
			$sql = "ALTER TABLE `" . $table . "` ADD `option_sort_order` INT(2) NOT NULL DEFAULT '0'"; $this->db->query($sql);
			if(mysql_query("SELECT option_sort_order FROM " . $table . " LIMIT 0")){ $operation .= $success . $sql . $br; }else{ $operation .= $error . $sql . $br; }
		}
		if (false === mysql_query("SELECT option_hide_qty FROM " . $table . " LIMIT 0")) { //v3.2
			$sql = "ALTER TABLE `" . $table . "` ADD `option_hide_qty` TINYINT(1) NOT NULL DEFAULT '0' AFTER `option_min_qty`"; $this->db->query($sql);
			if(mysql_query("SELECT option_hide_qty FROM " . $table . " LIMIT 0")){ $operation .= $success . $sql . $br; }else{ $operation .= $error . $sql . $br; }
		}
		if (false === mysql_query("SELECT child_id FROM " . $table . " LIMIT 0")) { //v5.0
			$sql = "ALTER TABLE `" . $table . "` ADD `child_id` VARCHAR(255) NOT NULL"; $this->db->query($sql);
			if(mysql_query("SELECT child_id FROM " . $table . " LIMIT 0")){ $operation .= $success . $sql . $br; }else{ $operation .= $error . $sql . $br; }
		}
		if (false === mysql_query("SELECT child_to_hide FROM " . $table . " LIMIT 0")) { //v5.0
			$sql = "ALTER TABLE `" . $table . "` ADD `child_to_hide` VARCHAR(255) NOT NULL"; $this->db->query($sql);
			if(mysql_query("SELECT child_to_hide FROM " . $table . " LIMIT 0")){ $operation .= $success . $sql . $br; }else{ $operation .= $error . $sql . $br; }
		}
		// S Aggiornamento delle opzioni, sposto i figli dalla table product_grouped alla table product_grouped_configurable
		if (mysql_query("SELECT option_type FROM " . DB_PREFIX . "product_grouped LIMIT 0")) { //v5.0
			$query = $this->db->query("SELECT product_id, option_type FROM " . DB_PREFIX . "product_grouped_configurable WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
			if ($query->num_rows) { $i = 1;
				foreach ($query->rows as $pg) { $child_id = '';
					$child_query = $this->db->query("SELECT grouped_id FROM " . DB_PREFIX . "product_grouped WHERE option_type = '" . $pg['option_type'] . "' AND product_id = '" . $pg['product_id'] . "' ORDER BY grouped_sort_order"); foreach($child_query->rows as $child){ $child_id .= $child['grouped_id'] . ','; }
					$sql = ("UPDATE `" . DB_PREFIX . "product_grouped_configurable` SET `child_id` = '" . $child_id . "', `option_sort_order` = '" . $i++ . "' WHERE `product_id` = '" . $pg['product_id'] . "' AND `option_type` = '" . $pg['option_type'] . "'"); $this->db->query($sql);
					$operation .= '<span style="color:#ff0;">Updating product configurable: </span>' . $sql . $br;
				}
			}
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_grouped` DROP `option_type`");
			if (mysql_query("SELECT product_to_hide FROM " . DB_PREFIX . "product_grouped LIMIT 0")) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_grouped` DROP `product_to_hide`");
			}
			if (mysql_query("SELECT product_to_hide FROM " . DB_PREFIX . "product_grouped_configurable LIMIT 0")) { //from v5.0preview to v5.0
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_grouped_configurable` DROP `product_to_hide`");
			}
		}
		// E Aggiornamento delle opzioni, sposto i figli dalla table product_grouped alla table product_grouped_configurable
		
		if ($operation) {
			return '<p style="color:#ff0;">Starting upgrade process...</p><br />' . $operation;
		} else {
			return $operation;
		}
	}
}
?>