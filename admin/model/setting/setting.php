<?php
class ModelSettingSetting extends Model {
	public function getSetting($code, $store_id = 0) {
		$setting_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `code` = '" . $this->db->escape($code) . "'");

		foreach ($query->rows as $result) {
			if (!$result['serialized']) {
				$setting_data[$result['key']] = $result['value'];
			} else {
				$setting_data[$result['key']] = json_decode($result['value'], true);
			}
		}

		return $setting_data;
	}

	public function editSetting($code, $data, $store_id = 0) {
		//echo '<pre>';print_r($code);exit;
		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE store_id = '" . (int)$store_id . "' AND `code` = '" . $this->db->escape($code) . "'");

		foreach ($data as $key => $value) {
			if (substr($key, 0, strlen($code)) == $code) {
				if (!is_array($value)) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$store_id . "', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$store_id . "', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(json_encode($value)) . "', serialized = '1'");
				}
			}

			$product_id = 0;
			$this->db->query("DELETE FROM " . DB_PREFIX . "estimatedays WHERE product_id = '" . (int)$product_id . "'");
			if( isset($data['estimatedays']) ){
				foreach ($data['estimatedays'] as $get_data) {
					if($get_data['days']){
						$this->db->query("INSERT INTO ". DB_PREFIX."estimatedays SET product_id='".(int)$product_id."', estimate_days='".$get_data['days']."', text='".$get_data['estimatedays_description']."'");
					}
				}
			}


		}
	}


	public function getusergroups(){
		$query = $this->db->query("SELECT user_group_id,name FROM " . DB_PREFIX . "user_group");
		return $query->rows;
	}
	
	public function getProductEstimateDays($product_id){
		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."estimatedays WHERE product_id='".$product_id."' ORDER BY estimate_days");
		//print_r($query);
		return $query->rows;
		//return 'No';
	}


	public function deleteSetting($code, $store_id = 0) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `code` = '" . $this->db->escape($code) . "'");
	}

	public function editSettingValue($code = '', $key = '', $value = '', $store_id = 0) {
		if (!is_array($value)) {
			$this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = '" . $this->db->escape($value) . "', serialized = '0'  WHERE `code` = '" . $this->db->escape($code) . "' AND `key` = '" . $this->db->escape($key) . "' AND store_id = '" . (int)$store_id . "'");
		} else {
			$this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = '" . $this->db->escape(json_encode($value)) . "', serialized = '1' WHERE `code` = '" . $this->db->escape($code) . "' AND `key` = '" . $this->db->escape($key) . "' AND store_id = '" . (int)$store_id . "'");
		}
	}

	public function getLatestCatSetting()
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `code` = 'latest_category'");
		return $query->rows;
	}

	public function updateLatestCatSetting($data)
	{
		$this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = '" . $this->db->escape((int)$data['product_limit']) . "' WHERE `code` = 'latest_category' AND `key` = 'product_limit'");

		$this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = '" . $this->db->escape((int)$data['unlink_old_products']) . "' WHERE `code` = 'latest_category' AND `key` = 'unlink_old_products'");

	}
}
