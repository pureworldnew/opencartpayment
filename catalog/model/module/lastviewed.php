<?php
class ModelModuleLastViewed extends Model {
  	private $moduleVersion;
	private $modulePath;
	private $moduleName;
	private $moduleModel;

	public function __construct($registry) {
		parent::__construct($registry);

		$this->config->load('isenselabs/lastviewed');

		$this->moduleVersion = $this->config->get('lastviewed_version');
		$this->modulePath = $this->config->get('lastviewed_path');
		$this->moduleName = $this->config->get('lastviewed_name');
		$this->moduleModel = $this->config->get('lastviewed_model');
	}

  	public function getSetting($code, $store_id) {
	    $this->load->model('setting/setting');
		return $this->model_setting_setting->getSetting($code,$store_id);
  	}

  	public function insertProductView($IP, $product_id) {
  		$moduleSetting = $this->getSetting($this->moduleName, $this->config->get('config_store_id'));
        $moduleData = isset($moduleSetting[$this->moduleName]) ? $moduleSetting[$this->moduleName] : array();
        $limit = isset($moduleData['Limit']) ? $moduleData['Limit'] : 4;

  		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "last_viewed_ip` WHERE ip = '" . $this->db->escape($IP) . "' LIMIT 1");

  		if($query->num_rows == 0) {
  			$product_data = json_encode(array($product_id), true);

  			$this->db->query("INSERT INTO `" . DB_PREFIX . "last_viewed_ip` 
  							 SET
  							 	ip 		 = '". $this->db->escape($IP)."',
  							 	date 	 = NOW(),
  							 	products = '" . $this->db->escape($product_data) . "'
  							");
  		} else {
  			$product_data = json_decode($query->row['products']);
  			if(!in_array($product_id, $product_data)) {

  				if(count($product_data) >= $limit) {
  					for ($i=1; $i <= $limit - count($product_data); $i++) { 
  						array_shift($product_data);
  					}
				}
				$product_data[] = $product_id;

				$product_data = json_encode($product_data, true);
  				$this->db->query("UPDATE `" . DB_PREFIX . "last_viewed_ip` SET products = '" . $this->db->escape($product_data) . "',date = NOW()  WHERE ip = '" . $this->db->escape($IP) . "'");
  			} else {
  				$key = array_search($product_id, $product_data);

  				$product_data = $this->array_reorder($product_data, $key, count($product_data) - 1);

  				$product_data = json_encode($product_data, true);

  				$this->db->query("UPDATE `" . DB_PREFIX . "last_viewed_ip` SET products = '" . $this->db->escape($product_data) . "',date = NOW()  WHERE ip = '" . $this->db->escape($IP) . "'");
  			}
  		}

  	}

  	private function array_reorder($array, $oldIndex, $newIndex) {
	    array_splice(
	        $array,
	        $newIndex,
	        count($array),
	        array_merge(
	            array_splice($array, $oldIndex, 1),
	            array_slice($array, $newIndex, count($array))
	        )
	    );
    	return $array;
	}

  	public function getProductsSavedByIP($IP) {
  		$products = array();

  		$query = $this->db->query("SELECT products FROM `" . DB_PREFIX . "last_viewed_ip` WHERE ip = '" . $this->db->escape($IP) . "' LIMIT 1");
  		if($query->num_rows > 0) {
  			$products = json_decode($query->row['products']);
  		}

  		return $products;
  	}

}
?>