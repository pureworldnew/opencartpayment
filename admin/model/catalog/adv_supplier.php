<?php
class ModelCatalogAdvSupplier extends Model {
	public function addSupplier($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "supplier SET name = '" . $this->db->escape($data['name']) . "', website = '" . $this->db->escape($data['website']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', company = '" . $this->db->escape($data['company']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "', status = '" . (int)$data['status'] . "'");

		$supplier_id = $this->db->getLastId();

		if (isset($data['supplier_store'])) {
			foreach ($data['supplier_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "supplier_to_store SET supplier_id = '" . (int)$supplier_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
		
		$this->cache->delete('supplier');

		return $supplier_id;
	}

	public function editSupplier($supplier_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "supplier SET name = '" . $this->db->escape($data['name']) . "', website = '" . $this->db->escape($data['website']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', company = '" . $this->db->escape($data['company']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "', status = '" . (int)$data['status'] . "' WHERE supplier_id = '" . (int)$supplier_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "supplier_to_store WHERE supplier_id = '" . (int)$supplier_id . "'");

		if (isset($data['supplier_store'])) {
			foreach ($data['supplier_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "supplier_to_store SET supplier_id = '" . (int)$supplier_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
		
		$this->cache->delete('supplier');
	}

	public function deleteSupplier($supplier_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "supplier WHERE supplier_id = '" . (int)$supplier_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "supplier_to_store WHERE supplier_id = '" . (int)$supplier_id . "'");

		$this->cache->delete('supplier');
	}

	public function getSupplier($supplier_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "supplier WHERE supplier_id = '" . (int)$supplier_id . "'");

		return $query->row;
	}

	public function getProductSuppliers($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "supplier WHERE status = '1' ORDER BY name";

		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getSuppliers($data = array()) {
		$sql = "SELECT *, (SELECT COUNT(p.supplier_id) FROM `" . DB_PREFIX . "product` p WHERE s.supplier_id = p.supplier_id) AS products FROM " . DB_PREFIX . "supplier s";

		$sort_data = array(
			's.supplier_id',
			's.name',
			's.email',
			's.website',
			'products',
			's.status'			
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY s.name";
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

	public function getSupplierStores($supplier_id) {
		$supplier_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "supplier_to_store WHERE supplier_id = '" . (int)$supplier_id . "'");

		foreach ($query->rows as $result) {
			$supplier_store_data[] = $result['store_id'];
		}

		return $supplier_store_data;
	}
	
	public function getTotalSuppliers() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "supplier");

		return $query->row['total'];
	}
	
	public function getTotalProductsBySupplierId($supplier_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE supplier_id = '" . (int)$supplier_id . "'");

		return $query->row['total'];
	}		
}
