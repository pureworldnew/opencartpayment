<?php
class ModelGkdExportDriverCategory extends Model {
  private $langIdToCode = array();

  public function getItems($data = array(), $count = false) {
    $select = ($count) ? 'COUNT(DISTINCT c.category_id) AS total' : '*';
    
    $sql = "SELECT ".$select." FROM " . DB_PREFIX . "category c";
    
    if (isset($data['filter_language']) && $data['filter_language'] !== '') {
      $sql .= " LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id)";
    }
    
    if (!empty($data['filter_store'])) {
      $sql .= " LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id)";
    }
    
    // WHERE
    // languages
    if (isset($data['filter_language']) && $data['filter_language'] !== '') {
      $sql .= " WHERE cd.language_id = '" . (int)$data['filter_language'] . "'";
    } else {
      $lgquery = $this->db->query("SELECT DISTINCT language_id, code FROM " . DB_PREFIX . "language WHERE status = 1")->rows;
      
      foreach ($lgquery as $lang) {
        $this->langIdToCode[$lang['language_id']] = substr($lang['code'], 0, 2);
      }
    }
    
    if (!empty($data['filter_parent'])) {
      $sql .= " c.parent_id = '" . (int)$data['filter_parent'] . "'";
    }
    
    if (!empty($data['filter_store'])) {
      $sql .= " AND c2s.store_id = '" . (int)$data['filter_store'] . "'";
    }
    
    if (!empty($data['filter_status'])) {
      $sql .= " AND c.status = '" . (int)$data['filter_status'] . "'";
    }
    
		if (!empty($data['filter_name'])) {
			$sql .= " AND cd2.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

    // return count
    if ($count) {
      return $this->db->query($sql)->row['total'];
    }
    
    $sql .= " ORDER BY c.category_id";
    
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
        $row += $this->getCategoryDescription($row['category_id']);
      }
    }
		return $query->rows;
	}
  
  public function getCategoryDescription($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int)$category_id . "' ORDER BY language_id ASC");
    
    $res = array();
    
    foreach ($query->rows as &$row) {
      foreach ($row as $key => $val) {
        if (!in_array($key, array('language_id', 'category_id'))) {
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