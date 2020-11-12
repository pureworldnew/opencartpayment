<?php
class ModelGkdExportDriverInformation extends Model {
  private $langIdToCode = array();
  
  public function getItems($data = array(), $count = false) {
    $select = ($count) ? 'COUNT(DISTINCT i.information_id) AS total' : '*';
    
    $sql = "SELECT ".$select." FROM " . DB_PREFIX . "information i";

    if (isset($data['filter_language']) && $data['filter_language'] !== '') {
      $sql .= " LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id)";
    }
    
    if (!empty($data['filter_store'])) {
      $sql .= " LEFT JOIN " . DB_PREFIX . "information_to_store i2s ON (i.information_id = i2s.information_id)";
    }
    
    // WHERE
    // languages
    if (isset($data['filter_language']) && $data['filter_language'] !== '') {
      $sql .= " WHERE id.language_id = '" . (int)$data['filter_language'] . "'";
    } else {
      $lgquery = $this->db->query("SELECT DISTINCT language_id, code FROM " . DB_PREFIX . "language WHERE status = 1")->rows;
      
      foreach ($lgquery as $lang) {
        $this->langIdToCode[$lang['language_id']] = substr($lang['code'], 0, 2);
      }
    }
    
    if (!empty($data['filter_store'])) {
      $sql .= " AND i2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
    }
    
    if (!empty($data['filter_status'])) {
      $sql .= " AND i.status = '" . (int)$data['filter_status'] . "'";
    }
    
		if (!empty($data['filter_name'])) {
			$sql .= " AND id.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

    // return count
    if ($count) {
      return $this->db->query($sql)->row['total'];
    }
    
    $sql .= " ORDER BY i.information_id";
    
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

    foreach ($query->rows as &$row) {
      if (isset($data['filter_language']) && $data['filter_language'] === '') {
        $row += $this->getInformationDescription($row['information_id']);
      }
    }
		return $query->rows;
	}
  
  public function getInformationDescription($information_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information_description WHERE information_id = '" . (int)$information_id . "' ORDER BY language_id ASC");
    
    $res = array();
    
    foreach ($query->rows as &$row) {
      foreach ($row as $key => $val) {
        if (!in_array($key, array('language_id', 'information_id'))) {
          $res[$key.'_'.$this->langIdToCode[$row['language_id']]] = $val;
        }
      }
    }
    
		return $res;
	}

  public function getTotalItems($data = array()) {
    return $this->getItems($data, true);
  }
}