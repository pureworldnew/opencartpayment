<?php
class ModelModuleProductLabels extends Model {

	public function install() {

		$this->db->query("SET CHARACTER SET utf8");
		$this->db->query("SET @@session.sql_mode = 'MYSQL40'");
		$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."pl_label_templates` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(20) NOT NULL,
		  `width` varchar(6) NOT NULL,
		  `height` varchar(6) NOT NULL,
		  `number_h` varchar(3) NOT NULL,
		  `number_v` varchar(3) NOT NULL,
		  `space_h` varchar(6) default '0',
		  `space_v` varchar(6) default '0',
		  `page_w` varchar(6) default '210',
		  `page_h` varchar(6) default '297',
		  `margin_t` varchar(6) default 'auto',
		  `margin_l` varchar(6) default 'auto',
		  `rounded` varchar(1) default '1',
		  `default` varchar(1) default '0',
		  `desc` text,
		   PRIMARY KEY (`id`)
		) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");

		$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."pl_labels` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(20) NOT NULL,
		  `data` text NOT NULL,
		  `default` varchar(1) NOT NULL,
		  `active` varchar(1) NOT NULL,
		   PRIMARY KEY (`id`)
		) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");

		//insert sample data
		$this->db->query('INSERT INTO `'.DB_PREFIX.'pl_labels` (`id`,`name`,`data`,`default`,`active`) VALUES
		(NULL,\'PREVIEW\',\'{"type":{"1":"text","2":"text","3":"text","4":"text","5":"text","6":"text","7":"barcode"},"ff":{"1":"helveticaB","2":"helveticaB","3":"helveticaB","4":"helveticaB","5":"helvetica","6":"helvetica","7":"helvetica"},"fs":{"1":"14","2":"12","3":"16","4":"16","5":"10","6":"12","7":"12"},"fr":{"1":"0","2":"0","3":"0","4":"0","5":"0","6":"0","7":"0"},"text":{"1":"{NAME}","2":"{MODEL}","3":"{O:SIZE}","4":"{O:COLOUR}","5":"ID {product_id}    SKU {sku}","6":"{Manufacturer}","7":"{ean}"},"img":{"1":"","2":"","3":"","4":"","5":"","6":"","7":""},"x":{"1":"6","2":"6","3":"6","4":"6","5":"right-3.5","6":"6","7":"width-38"},"y":{"1":"3","2":"16.5","3":"23","4":"30","5":"16","6":"10","7":"height-20"},"w":{"1":"","2":"","3":"","4":"","5":"","6":"","7":"35"},"h":{"1":"","2":"","3":"","4":"","5":"","6":"","7":"18"},"color":{"1":"000000","2":"000000","3":"000000","4":"000000","5":"000000","6":"000000","7":"000000"},"fill":{"1":"FFFFFF","2":"FFFFFF","3":"FFFFFF","4":"FFFFFF","5":"FFFFFF","6":"FFFFFF","7":"FFFFFF"},"numrows":7}\',\'0\',\'0\'),
		(NULL,\'Shoe label\',\'{"type":{"1":"img","2":"img","3":"text","4":"text","6":"text","7":"text","9":"text","8":"barcode"},"ff":{"1":"helvetica","2":"helvetica","3":"helveticaB","4":"helvetica","6":"helveticaB","7":"helvetica","9":"helveticaB","8":"helvetica"},"fs":{"1":"12","2":"12","3":"21","4":"12","6":"18","7":"10","9":"18","8":"12"},"fr":{"1":"0","2":"0","3":"0","4":"0","6":"0","7":"0","9":"0","8":"0"},"text":{"1":"","2":"","3":"{model}","4":"{name}","6":"{O:SIZE}","7":"EAN","9":"{O:COLOUR}","8":"{ean}"},"img":{"1":"{image}","2":"{manufacturer_image}","3":"","4":"","6":"","7":"","9":"","8":""},"x":{"1":"6","2":"right-3","3":"3","4":"3","6":"3","7":"width-42","9":"right-3","8":"width-40"},"y":{"1":"8","2":"3","3":"40","4":"52","6":"59","7":"height-27","9":"30","8":"height-22"},"w":{"1":"45","2":"30","3":"","4":"","6":"","7":"","9":"","8":"35"},"h":{"1":"","2":"","3":"","4":"","6":"","7":"","9":"","8":"20"},"color":{"1":"000000","2":"000000","3":"000000","4":"000000","6":"000000","7":"000000","9":"000000","8":"000000"},"fill":{"1":"FFFFFF","2":"FFFFFF","3":"FFFFFF","4":"FFFFFF","6":"FFFFFF","7":"FFFFFF","9":"FFFFFF","8":"FFFFFF"},"numrows":8}\',\'0\',\'1\'),
		(NULL,\'100 barcodes\',\'{"type":{"1":"barcode"},"ff":{"1":"helvetica"},"fs":{"1":"12"},"fr":{"1":"0"},"text":{"1":"{ean}"},"img":{"1":""},"x":{"1":"1"},"y":{"1":"1"},"w":{"1":"width-2"},"h":{"1":"height-2"},"color":{"1":"000000"},"fill":{"1":"FFFFFF"},"numrows":1}\',\'0\',\'1\'),
		(NULL,\'Dymo Label\',\'{"type":{"1":"text","2":"text","3":"text","4":"text","5":"text","6":"text","7":"barcode"},"ff":{"1":"helveticaB","2":"helveticaB","3":"helveticaB","4":"helveticaB","5":"helvetica","6":"helvetica","7":"helvetica"},"fs":{"1":"14","2":"12","3":"16","4":"16","5":"10","6":"12","7":"12"},"fr":{"1":"0","2":"0","3":"0","4":"0","5":"0","6":"0","7":"0"},"text":{"1":"{NAME}","2":"{MODEL}","3":"{O:SIZE}","4":"{O:COLOUR}","5":"ID {product_id}    SKU {sku}","6":"{Manufacturer}","7":"{ean}"},"img":{"1":"","2":"","3":"","4":"","5":"","6":"","7":""},"x":{"1":"6","2":"6","3":"6","4":"6","5":"right-3.5","6":"6","7":"width-38"},"y":{"1":"3","2":"16.5","3":"23","4":"30","5":"16","6":"10","7":"height-20"},"w":{"1":"","2":"","3":"","4":"","5":"","6":"","7":"35"},"h":{"1":"","2":"","3":"","4":"","5":"","6":"","7":"18"},"color":{"1":"000000","2":"000000","3":"000000","4":"000000","5":"000000","6":"000000","7":"000000"},"fill":{"1":"FFFFFF","2":"FFFFFF","3":"FFFFFF","4":"FFFFFF","5":"FFFFFF","6":"FFFFFF","7":"FFFFFF"},"numrows":7}\',\'0\',\'1\')');

		$this->db->query('INSERT INTO `'.DB_PREFIX.'pl_label_templates` (`id`,`name`,`width`,`height`,`number_h`,`number_v`,`space_h`,`space_v`,`page_w`,`page_h`,`margin_t`,`margin_l`,`rounded`,`default`,`desc`) VALUES
		(NULL,\'8 labels\',\'100\',\'70\',\'2\',\'4\',\'2.4\',\'0\',\'210\',\'297\',\'auto\',\'auto\',\'0\',\'0\',\'Avery\'),
		(NULL,\'Avery 63.5 x 72\',\'63.5\',\'72\',\'3\',\'4\',\'2.4\',\'0\',\'210\',\'297\',\'auto\',\'auto\',\'1\',\'1\',\'Avery\'),
		(NULL,\'Avery 63.5 x 38.1\',\'63.5\',\'38.1\',\'3\',\'7\',\'2.4\',\'0\',\'210\',\'297\',\'auto\',\'auto\',\'1\',\'0\',\'Avery\'),
		(NULL,\'100 labels\',\'21\',\'29.7\',\'10\',\'10\',\'0\',\'0\',\'210\',\'297\',\'auto\',\'auto\',\'0\',\'0\',\'Avery\'),
		(NULL,\'Dymo 11356 (89x41)\',\'89\',\'41\',\'1\',\'1\',\'0\',\'0\',\'89\',\'41\',\'0\',\'0\',\'1\',\'0\',\'Avery\')');
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."pl_label_templates`");
		$this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."pl_labels`");
	}

	public function getLabel($id=0) {
		$sql = "SELECT * FROM `".DB_PREFIX."pl_labels` WHERE `id` = ".(int)$id;
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getLabels() {
		$sql = "SELECT `id`,`name` FROM `".DB_PREFIX."pl_labels` WHERE 1";
		$query = $this->db->query($sql);
		$res=array();

		foreach ($query->rows as $result)
			if ($result['id'] > 1)
				$res[$result['id']] = $result['name'];

		return $res;
	}

	public function setLabel($serialized="") {
		$this->db->query("INSERT INTO `".DB_PREFIX."pl_labels` (`id`,`name`,`data`,`default`,`active`) VALUES (NULL,'".$this->request->get['saveas_label_name']."', '".$serialized."', '0', '1')");
		return $this->db->getLastId();
	}

	public function deleteLabel() {
		$this->db->query("DELETE FROM `".DB_PREFIX."pl_labels` WHERE `id` = ".(int) $this->request->get['id']);
		return $this->db->countAffected();
	}

	public function updateLabel($serialized="") {
		$sql = "UPDATE `".DB_PREFIX."pl_labels` SET `data`='".$serialized."' WHERE `id`='".(int) $this->request->get['id']."'";
		$this->db->query($sql);
		return $this->db->countAffected();
	}

	public function getLabelTemplate($id=0) {
		$sql = "SELECT * FROM `".DB_PREFIX."pl_label_templates` WHERE `id` = ".(int) $id;
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getLabelTemplates() {
		$sql = "SELECT `id`,`name` FROM `".DB_PREFIX."pl_label_templates` WHERE 1";
		$query = $this->db->query($sql);
		$res=array();
		foreach ($query->rows as $result) {
			$res[$result['id']] = $result['name'];
		}
		return $res;
	}

	public function setLabelTemplate() {
		$this->db->query("INSERT INTO `".DB_PREFIX."pl_label_templates` (`id`,`name`,`width`,`height`,`number_h`,`number_v`,`space_h`,`space_v`,`page_w`,`page_h`,`margin_t`,`margin_l`,`rounded`,`default`,`desc`) VALUES (NULL,'".$this->request->get['saveas_template']."','".$this->request->get['labelw']."','".$this->request->get['labelh']."','".$this->request->get['numw']."','".$this->request->get['numh']."','".$this->request->get['hspacing']."','".$this->request->get['vspacing']."','".$this->request->get['pagew']."','".$this->request->get['pageh']."','".$this->request->get['margint']."','".$this->request->get['marginl']."','".$this->request->get['rounded']."','0','Avery')");
		return $this->db->getLastId();
	}

	public function updateLabelTemplate() {
		$this->db->query("UPDATE `".DB_PREFIX."pl_label_templates` SET `width`='".$this->request->get['labelw']."', `height`='".$this->request->get['labelh']."', `number_h`='".$this->request->get['numw']."', `number_v`='".$this->request->get['numh']."', `space_h`='".$this->request->get['hspacing']."', `space_v`='".$this->request->get['vspacing']."', `page_w`='".$this->request->get['pagew']."', `page_h`='".$this->request->get['pageh']."', `margin_t`='".$this->request->get['margint']."', `margin_l`='".$this->request->get['marginl']."', `rounded`='".$this->request->get['rounded']."' WHERE `id`='".$this->request->get['id']."'");
		return $this->db->countAffected();
	}

	public function deleteTemplate() {
		$this->db->query("DELETE FROM `".DB_PREFIX."pl_label_templates` WHERE `id` = ".(int) $this->request->get['id']);
		return $this->db->countAffected();
	}
}
