<?php
class ModelGkdExportDriverManufacturer extends Model {
  
  public function getItems($data = array(), $count = false) {
    $select = ($count) ? 'COUNT(DISTINCT m.manufacturer_id) AS total' : '*';
    
    $sql = "SELECT ".$select." FROM " . DB_PREFIX . "manufacturer m";
    
    if (!empty($data['filter_store'])) {
      $sql .= " LEFT JOIN " . DB_PREFIX . "manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id)";
    }
    
    // Where
    $sql .= " WHERE 1=1";
    
    if (!empty($data['filter_store'])) {
      $sql .= " AND m2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
    }
    
    if (!empty($data['filter_status'])) {
      $sql .= " AND m.status = '" . (int)$data['filter_status'] . "'";
    }
    
		if (!empty($data['filter_name'])) {
			$sql .= " AND m.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

    // return count
    if ($count) {
      return $this->db->query($sql)->row['total'];
    }
    
    $sql .= " ORDER BY m.manufacturer_id";
    
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}
  
  public function getTotalItems($data = array()) {
    return $this->getItems($data, true);
  }
}