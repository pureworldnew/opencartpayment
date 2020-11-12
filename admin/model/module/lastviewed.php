<?php 
class ModelModuleLastViewed extends Model {

	public function getSetting($code, $store_id = 0) {
	    $this->load->model('setting/setting');
		return $this->model_setting_setting->getSetting($code,$store_id);
	}
  
  	public function editSetting($code, $data, $store_id = 0) {
	    $this->load->model('setting/setting');
		$this->model_setting_setting->editSetting($code,$data,$store_id);
	}
	
  	public function install() {
		$this->db->query("UPDATE `" . DB_PREFIX . "modification` SET status=1 WHERE `name` LIKE'%LastViewed by iSenseLabs%'");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "last_viewed_ip` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `ip` varchar(255) NOT NULL,
						  `date` datetime NOT NULL,
						  `products` varchar(255) NOT NULL,
						  PRIMARY KEY (`id`)
						)");

		$modifications = $this->load->controller('extension/modification/refresh');
  	} 
  
  	public function uninstall() {
		$this->db->query("UPDATE `" . DB_PREFIX . "modification` SET status=0 WHERE `name` LIKE'%LastViewed by iSenseLabs%'");
		$modifications = $this->load->controller('extension/modification/refresh');
  	}
	
  }
?>