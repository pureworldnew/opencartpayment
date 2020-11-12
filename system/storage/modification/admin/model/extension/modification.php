<?php
class ModelExtensionModification extends Model {
	public function addModification($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "modification SET date_modified = NOW(), code = '" . $this->db->escape($data['code']) . "', name = '" . $this->db->escape($data['name']) . "', author = '" . $this->db->escape($data['author']) . "', version = '" . $this->db->escape($data['version']) . "', link = '" . $this->db->escape($data['link']) . "', xml = '" . $this->db->escape($data['xml']) . "', status = '" . (int)$data['status'] . "', date_added = NOW()");
	}

//karapuz (ka_extensions.ocmod.xml) 
	public function isKaExtensions($modification_id) {
		
		$mod = $this->getModification($modification_id);
		if (empty($mod)) {
			return false;
		}
		
		if ($mod['code'] == 'ka_extensions') {
			return true;
		}
	
		return false;
	}	
	
	public function isKaRequired() {
	
		$qry = $this->db->query("SELECT * FROM " . DB_PREFIX . "modification WHERE
			`code` != 'ka_extensions'
			AND `code` LIKE 'ka_%'
		");
		
		if (empty($qry->row)) {
			return false;
		}
		
		return true;
	}
///karapuz (ka_extensions.ocmod.xml) 
	public function deleteModification($modification_id) {
//karapuz (ka_extensions.ocmod.xml) 
		if ($this->isKaExtensions($modification_id) && $this->isKaRequired()) {
			return false;
		}
///karapuz (ka_extensions.ocmod.xml) 
		$this->db->query("DELETE FROM " . DB_PREFIX . "modification WHERE modification_id = '" . (int)$modification_id . "'");
	}

	public function enableModification($modification_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "modification SET status = '1', date_modified = NOW() WHERE modification_id = '" . (int)$modification_id . "'");
	}

	public function disableModification($modification_id) {
//karapuz (ka_extensions.ocmod.xml) 
		if ($this->isKaExtensions($modification_id) && $this->isKaRequired()) {
			return false;
		}
///karapuz (ka_extensions.ocmod.xml) 
		$this->db->query("UPDATE " . DB_PREFIX . "modification SET status = '0', date_modified = NOW() WHERE modification_id = '" . (int)$modification_id . "'");
	}


	public function editModification($modification_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "modification SET code = '" . $this->db->escape($data['code']) . "', name = '" . $this->db->escape($data['name']) . "', author = '" . $this->db->escape($data['author']) . "', version = '" . $this->db->escape($data['version']) . "', link = '" . $this->db->escape($data['link']) . "', xml = '" . $this->db->escape($data['xml']) . "', date_modified = NOW() WHERE modification_id = '" . (int)$modification_id . "'");
	}
      
	public function getModification($modification_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "modification WHERE modification_id = '" . (int)$modification_id . "'");

		return $query->row;
	}

	public function getModifications($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "modification";

		$cond = array();

		if (!empty($data['filter_name'])) {
			$cond[] = " `name` LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_xml'])) {
			$cond[] = " MATCH (`xml`) AGAINST ('" . $this->db->escape($data['filter_xml']) . "' IN BOOLEAN MODE)";
		}

		if (!empty($data['filter_author'])) {
			$cond[] = " `author` LIKE '" . $this->db->escape($data['filter_author']) . "%'";
		}

		if ($cond) {
			$sql .= " WHERE " . implode(' AND ', $cond);
		}

		$sort_data = array(
			'date_modified',
			'name',
			'author',
			'version',
			'status',
			'date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

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

	public function getTotalModifications($data = array()) {
		
		$cond = array();

		if (!empty($data['filter_name'])) {
			$cond[] = " `name` LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_xml'])) {
			$cond[] = " `xml` LIKE '%" . $this->db->escape($data['filter_xml']) . "%'";
		}

		if (!empty($data['filter_author'])) {
			$cond[] = " `author` LIKE '%" . $this->db->escape($data['filter_author']) . "%'";
		}

		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "modification";

		if ($cond) {
			$sql .= " WHERE " . implode(' AND ', $cond);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getModificationByCode($code) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "modification WHERE code = '" . $this->db->escape($code) . "'");

		return $query->row;
	}
}