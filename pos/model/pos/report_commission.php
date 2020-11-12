<?php
class ModelPosReportCommission extends Model {
	public function getOrderCommissions($data = array()) {
		$sql = "SELECT u.username, c.* FROM `" . DB_PREFIX . "user` u RIGHT JOIN `" . DB_PREFIX . "order_commission` c ON u.user_id = c.user_id WHERE c.order_id > 0";

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND c.order_id = '" . (int)$data['filter_order_id'] . "'";
		}
		
		if (!empty($data['filter_commission'])) {
			$sql .= " AND c.commission = '" . (float)$data['filter_commission'] . "'";
		}

		if (!empty($data['filter_commission_date'])) {
			$sql .= " AND DATE(FROM_UNIXTIME(CAST(UNIX_TIMESTAMP(c.date_modified) + (" . (int)$data['timezone_offset'] . ") AS UNSIGNED))) = DATE('" . $this->db->escape($data['filter_commission_date']) . "')";
		}
		
		if (!empty($data['filter_user_id'])) {
			$sql .= " AND c.user_id = '" . (int)$data['filter_user_id'] . "'";
		}

		$sort_data = array(
			'order_id',
			'username',
			'commission',
			'date_modified'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY c.order_id";
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
	
	public function getTotalOrderCommissions($data = array()) {
      	$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order_commission` WHERE order_id > 0";

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_commission_date'])) {
			$sql .= " AND DATE(FROM_UNIXTIME(CAST(UNIX_TIMESTAMP(date_modified) + (" . (int)$data['timezone_offset'] . ") AS UNSIGNED))) = DATE('" . $this->db->escape($data['filter_commission_date']) . "')";
		}
		
		if (!empty($data['filter_user_id'])) {
			$sql .= " AND user_id = '" . (int)$data['filter_user_id'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	
	public function getOrderCommissionSummary($data = array()) {
		$sql = "SELECT u.username, tmp.* FROM `" . DB_PREFIX . "user` u RIGHT JOIN (SELECT user_id, SUM(commission) as commission, MIN(date_modified) AS date_start, MAX(date_modified) AS date_end FROM `" . DB_PREFIX . "order_commission` WHERE order_id > 0";
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(FROM_UNIXTIME(CAST(UNIX_TIMESTAMP(date_modified) + (" . (int)$data['timezone_offset'] . ") AS UNSIGNED))) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(FROM_UNIXTIME(CAST(UNIX_TIMESTAMP(date_modified) + (" . (int)$data['timezone_offset'] . ") AS UNSIGNED))) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		if (!empty($data['filter_user_id'])) {
			$sql .= " AND user_id = '" . (int)$data['filter_user_id'] . "'";
		}
		
		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}
		
		switch($group) {
			case 'day';
				$sql_group = " GROUP BY DAY(FROM_UNIXTIME(CAST(UNIX_TIMESTAMP(date_modified) + (" . (int)$data['timezone_offset'] . ") AS UNSIGNED)))";
				break;
			default:
			case 'week':
				$sql_group = " GROUP BY WEEK(FROM_UNIXTIME(CAST(UNIX_TIMESTAMP(date_modified) + (" . (int)$data['timezone_offset'] . ") AS UNSIGNED)))";
				break;	
			case 'month':
				$sql_group = " GROUP BY MONTH(FROM_UNIXTIME(CAST(UNIX_TIMESTAMP(date_modified) + (" . (int)$data['timezone_offset'] . ") AS UNSIGNED)))";
				break;
			case 'year':
				$sql_group = " GROUP BY YEAR(FROM_UNIXTIME(CAST(UNIX_TIMESTAMP(date_modified) + (" . (int)$data['timezone_offset'] . ") AS UNSIGNED)))";
				break;									
		}
		
		$sql .= $sql_group . ", user_id) tmp ON u.user_id = tmp.user_id";
		
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
	
	public function getTotalOrderCommissionSummary($data = array()) {
		$sql = "SELECT SUM(tmp.total) AS total FROM (SELECT COUNT(DISTINCT ";
		
		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}
		
		switch($group) {
			case 'day';
				$sql .= "DAY(FROM_UNIXTIME(CAST(UNIX_TIMESTAMP(date_modified) + (" . (int)$data['timezone_offset'] . ") AS UNSIGNED)))";
				break;
			default:
			case 'week':
				$sql .= "WEEK(FROM_UNIXTIME(CAST(UNIX_TIMESTAMP(date_modified) + (" . (int)$data['timezone_offset'] . ") AS UNSIGNED)))";
				break;	
			case 'month':
				$sql .= "MONTH(FROM_UNIXTIME(CAST(UNIX_TIMESTAMP(date_modified) + (" . (int)$data['timezone_offset'] . ") AS UNSIGNED)))";
				break;
			case 'year':
				$sql .= "YEAR(FROM_UNIXTIME(CAST(UNIX_TIMESTAMP(date_modified) + (" . (int)$data['timezone_offset'] . ") AS UNSIGNED)))";
				break;									
		}

		$sql .= ") AS total FROM `" . DB_PREFIX . "order_commission` WHERE order_id > 0";

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(FROM_UNIXTIME(CAST(UNIX_TIMESTAMP(date_modified) + (" . (int)$data['timezone_offset'] . ") AS UNSIGNED))) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(FROM_UNIXTIME(CAST(UNIX_TIMESTAMP(date_modified) + (" . (int)$data['timezone_offset'] . ") AS UNSIGNED))) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (!empty($data['filter_user_id'])) {
			$sql .= " AND user_id = '" . (int)$data['filter_user_id'] . "'";
		}
		
		$sql .= " GROUP BY user_id) tmp";

		$query = $this->db->query($sql);
		
		$total_commissions = $query->row['total'];
		
		return $total_commissions;
	}
}
?>