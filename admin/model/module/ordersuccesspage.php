<?php 
class ModelModuleOrderSuccessPage extends Model {	

	// Install function, called from the controller
  	public function install() {	
		$this->db->query("UPDATE `" . DB_PREFIX . "modification` SET status=1 WHERE `name` LIKE'%OrderSuccessPage by iSenseLabs%'");
		$modifications = $this->load->controller('extension/modification/refresh');
  	} 

	// Uninstall function, called from the controller
  	public function uninstall() {
		$this->db->query("UPDATE `" . DB_PREFIX . "modification` SET status=0 WHERE `name` LIKE'%OrderSuccessPage by iSenseLabs%'");
		$modifications = $this->load->controller('extension/modification/refresh');
  	}
	
  }
?>