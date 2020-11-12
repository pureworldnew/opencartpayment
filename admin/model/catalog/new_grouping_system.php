<?php

class ModelCatalogNewGroupingSystem extends Model {

    public function getTotalGroupIndicators($data = array()) {
		$sql = "SELECT COUNT(DISTINCT groupindicator) AS total FROM product_concat_temp_table";

		$sql .= " WHERE groupindicator = sku";

		if (!empty($data['filter_groupindicator'])) {
			$sql .= " AND groupindicator LIKE '" . $this->db->escape($data['filter_groupindicator']) . "%'";
		}
        
		$query = $this->db->query($sql);

		return $query->row['total'];
    }
    
    public function getGroupIndicators($data = array()) { 
		$sql = "SELECT * FROM product_concat_temp_table WHERE groupindicator = sku";

		if (!empty($data['filter_groupindicator'])) {
			$sql .= " AND groupindicator LIKE '" . $this->db->escape($data['filter_groupindicator']) . "%'";
		}

		$sql .= " GROUP BY groupindicator";

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

	public function updateGroupProduct($groupindicator_id, $new_grouping_system)
	{
		$this->db->query("UPDATE product_concat_temp_table SET new_grouping_system = '" . $new_grouping_system . "' WHERE groupindicator_id = '" . $groupindicator_id . "'");
	}

}