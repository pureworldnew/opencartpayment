<?php
class ModelFeedUksbSitemaps extends Model {
	public function getInformationList() {
		$information_data = $this->cache->get('information.' . (int)$this->config->get('config_language_id'));
	
		if (!$information_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY id.title");

			$information_data = $query->rows;
		
			$this->cache->set('information.' . (int)$this->config->get('config_language_id'), $information_data);
		}	

		return $information_data;			
	}
}
?>