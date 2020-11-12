<?php
class ModelPosCleanTables extends Model {
	public function getTotalTables() {
		$total = 0;
		
		$query = $this->db->query("SELECT table_name FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '" . DB_DATABASE . "'");
		if (!empty($query->rows)) {
			foreach ($query->rows as $row) {
				if (strlen($row['table_name']) > 33) {
					$table_name = substr($row['table_name'], 33);
					if ($table_name == 'address' || $table_name == 'customer' || $table_name == 'order_product') {
						$total ++;
					}
				}
			}
		}

		return $total;
	}
	
	public function getTables($start, $limit) {
		$query = $this->db->query("SELECT table_name, table_rows FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '" . DB_DATABASE . "'");
		$results = array();
		$index = 0;
		if (!empty($query->rows)) {
			foreach ($query->rows as $row) {
				if (strlen($row['table_name']) > 33) {
					$table_name = substr($row['table_name'], 33);
					if ($table_name == 'address' || $table_name == 'customer' || $table_name == 'order_product') {
						$index ++;
						if ($index > $start && $index <= $start+$limit) {
							$results[] = $row;
						}
					}
				}
			}
		}
		return $results;
	}
	
	public function deleteTables($table_names) {
		if (!empty($table_names)) {
			foreach ($table_names as $table_name) {
				$this->db->query("DROP TABLE `" . $table_name . "`");
			}
		}
	}
}