<?php
class ModelPosReportPayment extends Model {
	public function getPayments($data = array()) {
		$payments = array();
		
		$current_user_query = $this->db->query("SELECT name FROM `" . DB_PREFIX . "user_group` WHERE user_group_id IN (SELECT user_group_id FROM `" . DB_PREFIX . "user` WHERE user_id = '" . $this->user->getId() . "')");
		if ($current_user_query->row) {
			if ($current_user_query->row['name'] == 'POS') {
				// if not enabled location, for POS users, only retrieve its own payment
				$sql_users = "SELECT user_id, username FROM `" . DB_PREFIX . "user` WHERE user_id = '" . $this->user->getId() . "'";
			} else {
				// if not enabled location, for other users, only retrieve users not locations
				$sql_users = "SELECT user_id, username FROM `" . DB_PREFIX . "user`";
			}
			$sql_date_and_limit = "SELECT user_id, username, MIN(payment_time) AS date_start, MAX(payment_time) AS date_end FROM " . DB_PREFIX . "order_payment op JOIN (" . $sql_users . ") od USING(user_id) WHERE 1";
			
			if (!empty($data['filter_group'])) {
				$group = $data['filter_group'];
			} else {
				$group = 'day';
			}
			
			switch($group) {
				case 'day';
					$sql_group = " GROUP BY DAY(FROM_UNIXTIME(CAST(UNIX_TIMESTAMP(payment_time) + (" . (int)$data['timezone_offset'] . ") AS UNSIGNED)))";
					break;
				default:
				case 'week':
					$sql_group = " GROUP BY WEEK(FROM_UNIXTIME(CAST(UNIX_TIMESTAMP(payment_time) + (" . (int)$data['timezone_offset'] . ") AS UNSIGNED)))";
					break;	
				case 'month':
					$sql_group = " GROUP BY MONTH(FROM_UNIXTIME(CAST(UNIX_TIMESTAMP(payment_time) + (" . (int)$data['timezone_offset'] . ") AS UNSIGNED)))";
					break;
				case 'year':
					$sql_group = " GROUP BY YEAR(FROM_UNIXTIME(CAST(UNIX_TIMESTAMP(payment_time) + (" . (int)$data['timezone_offset'] . ") AS UNSIGNED)))";
					break;									
			}
			
			if (!empty($data['filter_date_start'])) {
				$sql_date_and_limit .= " AND DATE(FROM_UNIXTIME(CAST(UNIX_TIMESTAMP(payment_time) + (" . (int)$data['timezone_offset'] . ") AS UNSIGNED))) >= '" . $this->db->escape($data['filter_date_start']) . "'";
			}
			if (!empty($data['filter_date_end'])) {
				$sql_date_and_limit .= " AND DATE(FROM_UNIXTIME(CAST(UNIX_TIMESTAMP(payment_time) + (" . (int)$data['timezone_offset'] . ") AS UNSIGNED))) <= '" . $this->db->escape($data['filter_date_end']) . "'";
			}
			$sql_date_and_limit .= $sql_group . ", user_id ORDER BY user_id, date_start DESC";
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}			

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
				
				$sql_date_and_limit .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			$query_date_and_limit = $this->db->query($sql_date_and_limit);
			
			if ($query_date_and_limit->rows) {
				foreach ($query_date_and_limit->rows as $date_row) {
					$amounts = array();
					// for each type of payment, in the given date for the given user, add all amounts
					$payment_sql = "SELECT payment_type, SUM(tendered_amount) AS amount FROM " . DB_PREFIX . "order_payment WHERE user_id = '" . $date_row['user_id'] . "' AND payment_time >= '" . $date_row['date_start'] . "' AND payment_time <= '" . $date_row['date_end'] . "' GROUP BY payment_type";
					$query = $this->db->query($payment_sql);
					if ($query->rows) {
						$amounts = $query->rows;
					}

					// do not use pos_change
					$pos_change = 0;
					foreach ($amounts as $key => $amount) {
						if ($amount['payment_type'] == 'pos_change') {
							$pos_change = $amount['amount'];
							unset($amounts[$key]);
						}
					}
					
					$payments[] = array('username' => $date_row['username'],
										'date_start' => $date_row['date_start'],
										'date_end' => $date_row['date_end'],
										'amounts' => $amounts);
				}
			}
		}
		
		return $payments;
	}	
	
	public function getTotalPayments($data = array()) {
		$total_payments = 0;
		
		$current_user_query = $this->db->query("SELECT name FROM `" . DB_PREFIX . "user_group` WHERE user_group_id IN (SELECT user_group_id FROM `" . DB_PREFIX . "user` WHERE user_id = '" . $this->user->getId() . "')");
		if ($current_user_query->row) {
			if ($current_user_query->row['name'] == 'POS') {
				// if not enabled location, for POS users, only retrieve its own payment
				$sql_users = "SELECT user_id, username FROM `" . DB_PREFIX . "user` WHERE user_id = '" . $this->user->getId() . "'";
			} else {
				// if not enabled location, for other users, only retrieve users not locations
				$sql_users = "SELECT user_id, username FROM `" . DB_PREFIX . "user`";
			}
			$sql_date_and_limit = "SELECT user_id AS total FROM " . DB_PREFIX . "order_payment op JOIN (" . $sql_users . ") od USING(user_id) WHERE 1";
			
			if (!empty($data['filter_group'])) {
				$group = $data['filter_group'];
			} else {
				$group = 'day';
			}
			
			switch($group) {
				case 'day';
					$sql_group = " GROUP BY DAY(FROM_UNIXTIME(CAST(UNIX_TIMESTAMP(payment_time) + (" . (int)$data['timezone_offset'] . ") AS UNSIGNED)))";
					break;
				default:
				case 'week':
					$sql_group = " GROUP BY WEEK(FROM_UNIXTIME(CAST(UNIX_TIMESTAMP(payment_time) + (" . (int)$data['timezone_offset'] . ") AS UNSIGNED)))";
					break;	
				case 'month':
					$sql_group = " GROUP BY MONTH(FROM_UNIXTIME(CAST(UNIX_TIMESTAMP(payment_time) + (" . (int)$data['timezone_offset'] . ") AS UNSIGNED)))";
					break;
				case 'year':
					$sql_group = " GROUP BY YEAR(FROM_UNIXTIME(CAST(UNIX_TIMESTAMP(payment_time) + (" . (int)$data['timezone_offset'] . ") AS UNSIGNED)))";
					break;									
			}
			
			if (!empty($data['filter_date_start'])) {
				$sql_date_and_limit .= " AND DATE(FROM_UNIXTIME(CAST(UNIX_TIMESTAMP(payment_time) + (" . (int)$data['timezone_offset'] . ") AS UNSIGNED))) >= '" . $this->db->escape($data['filter_date_start']) . "'";
			}
			if (!empty($data['filter_date_end'])) {
				$sql_date_and_limit .= " AND DATE(FROM_UNIXTIME(CAST(UNIX_TIMESTAMP(payment_time) + (" . (int)$data['timezone_offset'] . ") AS UNSIGNED))) <= '" . $this->db->escape($data['filter_date_end']) . "'";
			}
			$sql_date_and_limit .= $sql_group . ", user_id";
			
			$query = $this->db->query($sql_date_and_limit);
			if ($query->row) {
				$total_payments = $query->num_rows;
			}
		}
		
		return $total_payments;	
	}
}
?>