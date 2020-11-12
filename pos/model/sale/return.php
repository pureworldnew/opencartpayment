<?php
class ModelSaleReturn extends Model {
	public function addReturn($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "return` SET order_id = '" . (int)$data['order_id'] . "', product_id = '" . (int)$data['product_id'] . "', customer_id = '" . (int)$data['customer_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', product = '" . $this->db->escape($data['product']) . "', model = '" . $this->db->escape($data['model']) . "', quantity = '" . (int)$data['quantity'] . "', opened = '" . (int)$data['opened'] . "', return_reason_id = '" . (int)$data['return_reason_id'] . "', return_action_id = '" . (int)$data['return_action_id'] . "', return_status_id = '" . (int)$data['return_status_id'] . "', comment = '" . $this->db->escape($data['comment']) . "', date_ordered = '" . $this->db->escape($data['date_ordered']) . "', date_added = NOW(), date_modified = NOW()");
	}

	public function editReturn($return_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "return` SET order_id = '" . (int)$data['order_id'] . "', product_id = '" . (int)$data['product_id'] . "', customer_id = '" . (int)$data['customer_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', product = '" . $this->db->escape($data['product']) . "', model = '" . $this->db->escape($data['model']) . "', quantity = '" . (int)$data['quantity'] . "', opened = '" . (int)$data['opened'] . "', return_reason_id = '" . (int)$data['return_reason_id'] . "', return_action_id = '" . (int)$data['return_action_id'] . "', comment = '" . $this->db->escape($data['comment']) . "', date_ordered = '" . $this->db->escape($data['date_ordered']) . "', date_modified = NOW() WHERE return_id = '" . (int)$return_id . "'");
	}

	public function deleteReturn($return_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "return` WHERE return_id = '" . (int)$return_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "return_history WHERE return_id = '" . (int)$return_id . "'");
	}

	public function getReturn($return_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = r.customer_id) AS customer FROM `" . DB_PREFIX . "return` r WHERE r.return_id = '" . (int)$return_id . "'");

		return $query->row;
	}
	
	public function getOrderedPrice($return_id) {
		$query = $this->db->query("SELECT order_product_id FROM " . DB_PREFIX . "return` WHERE return_id = '" . (int)$return_id . "'");

		$order_product_id =  $query->row['order_product_id'];
		
		$query2 = $this->db->query("SELECT price FROM " . DB_PREFIX . "order_product` WHERE order_product_id = '" . (int)$order_product_id . "'");

		return $query2->row['price'];
	}

	public function getReturns($data = array()) {
		$sql = "SELECT *, CONCAT(r.firstname, ' ', r.lastname) AS customer, (SELECT rs.name FROM " . DB_PREFIX . "return_status rs WHERE rs.return_status_id = r.return_status_id AND rs.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status FROM `" . DB_PREFIX . "return` r";

		$implode = array();

		if (!empty($data['filter_return_id'])) {
			$implode[] = "r.return_id = '" . (int)$data['filter_return_id'] . "'";
		}

		if (!empty($data['filter_order_id'])) {
			$implode[] = "r.order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$implode[] = "CONCAT(r.firstname, ' ', r.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_product'])) {
			$implode[] = "r.product = '" . $this->db->escape($data['filter_product']) . "'";
		}

		if (!empty($data['filter_model'])) {
			$implode[] = "r.model = '" . $this->db->escape($data['filter_model']) . "'";
		}

		if (!empty($data['filter_return_status_id'])) {
			$implode[] = "r.return_status_id = '" . (int)$data['filter_return_status_id'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(r.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$implode[] = "DATE(r.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'r.return_id',
			'r.order_id',
			'customer',
			'r.product',
			'r.model',
			'status',
			'r.date_added',
			'r.date_modified'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY r.return_id";
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

	public function getCustomerReason(){
		$sql = $this->db->query("SELECT reason ,id FROM " . DB_PREFIX . "wk_rma_reason WHERE language_id ='".$this->config->get('config_language_id')."' AND status = 1 ORDER BY id ");
		return $sql->rows;
	}

	public function getAdminStatus(){
		$sql = $this->db->query("SELECT name ,id,status_id FROM " . DB_PREFIX . "wk_rma_status WHERE language_id ='".$this->config->get('config_language_id')."' AND status = 1 ORDER BY id ");
		return $sql->rows;
	}

	public function defaultRmaStatus(){
		$result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "wk_rma_status` WHERE `default` = 'admin'")->row;
		if ($result) {
		  return $result['status_id'];
		}
		return false;
	}

	public function solveRmaStatus(){
	$result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "wk_rma_status` WHERE `default` = 'solve'")->row;
	if ($result) {
		return $result['status_id'];
	}
	return false;
	}

	public function cancelRmaStatus(){
	$result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "wk_rma_status` WHERE `default` = 'cancel'")->row;
	if ($result) {
		return $result['status_id'];
	}
	return false;
	}

	public function viewProducts($id){
		$sql = "SELECT pd.name,wrp.quantity,wrp.order_product_id,wrr.reason,p.model,op.product_id,op.quantity `quantity_total`,op.quantity_supplied `quantity_shipped`,op.price,op.order_id, IFNULL(rf.refund_amount,0) `refund_amount` FROM " . DB_PREFIX . "product_description pd LEFT JOIN ".DB_PREFIX."wk_rma_product wrp ON (wrp.product_id = pd.product_id) LEFT JOIN ".DB_PREFIX."wk_rma_reason wrr ON (wrp.reason = wrr.reason_id) LEFT JOIN ".DB_PREFIX."product p ON (p.product_id = pd.product_id) LEFT JOIN ".DB_PREFIX."order_product op ON (op.order_product_id = wrp.order_product_id) LEFT JOIN " .DB_PREFIX."return_refund rf ON (op.product_id = rf.product_id) WHERE wrp.rma_id = '".(int)$id."' AND pd.language_id = '".$this->config->get('config_language_id')."' AND wrr.language_id = '".$this->config->get('config_language_id')."'";

		$result = $this->db->query($sql);
		return $result->rows;
	}

	public function viewtotal($data = array()){

		$implodeInner = '';

		//$sql = "SELECT CONCAT(c.firstname,' ', c.lastname) AS name,wro.id,wro.admin_return,wro.order_id,wro.add_info,wro.rma_auth_no,wro.date,wrs.color,wrs.name as admin_status,wro.admin_status as rma_status,wro.cancel_rma,wro.solve_rma FROM " . DB_PREFIX . "wk_rma_order wro LEFT JOIN " . DB_PREFIX . "wk_rma_customer wrc ON (wro.id = wrc.rma_id) LEFT JOIN `" . DB_PREFIX . "order` c ON ((wrc.customer_id = c.customer_id || wrc.email = c.email ) AND c.order_id = wro.order_id) LEFT JOIN " . DB_PREFIX . "wk_rma_status wrs ON (wro.admin_status = wrs.status_id) WHERE 1";
		$sql = "SELECT CONCAT(c.firstname,' ', c.lastname) AS name,wro.id,wro.admin_return,wro.order_id,wro.add_info,wro.rma_auth_no,wro.return_pos,wro.date,wrs.color,wrs.name as admin_status,wro.admin_status as rma_status,wro.cancel_rma,wro.solve_rma FROM " . DB_PREFIX . "wk_rma_order wro LEFT JOIN " . DB_PREFIX . "wk_rma_customer wrc ON (wro.id = wrc.rma_id) LEFT JOIN `" . DB_PREFIX . "order` c ON ((wrc.customer_id = c.customer_id || wrc.email = c.email ) AND c.order_id = wro.order_id) LEFT JOIN " . DB_PREFIX . "wk_rma_status wrs ON (wro.admin_status = wrs.status_id) WHERE 1";
		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (isset($data['filter_order']) && !is_null($data['filter_order'])) {
			$implode[] = "wro.order_id = '" . (int)$data['filter_order'] . "'";
		}

		if (isset($data['filter_rma_status_id']) && !is_null($data['filter_rma_status_id'])) {
			//$implode[] = "wrs.status = '" . (int)$data['filter_rma_status_id'] . "'";
		} else {
			//$implode[] = "wrs.status = 1";
		}

		if (isset($data['filter_admin_status']) && !is_null($data['filter_admin_status'])) {
			if ($data['filter_admin_status'] == 'admin') {
				$implode[] = "wro.admin_return = 1";
			} else if ($data['filter_admin_status'] == 'solve') {
				$implode[] = "wro.solve_rma = 1";
			} else if ($data['filter_admin_status'] == 'cancel') {
				$implode[] = "wro.cancel_rma = 1";
			} else {
				$implode[] = " wrs.id = '" . (int)$data['filter_admin_status'] . "' AND admin_return <> 1 AND solve_rma <> 1 AND cancel_rma <> 1";
			}
		}

		if (!empty($data['filter_date'])) {
			$implode[] = "LCASE(wro.date) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_date'])) . "%'";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'c.firstname',
			'c.order_id',
			'wro.order_id',
			'wrr.id',
			'wro.date',
		);


		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY wro.order_id";
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

		$result = $this->db->query($sql);
		return $result->rows;
	}

	public function viewtotalentry($data = array()){

		$sql = "SELECT wro.id FROM " . DB_PREFIX . "wk_rma_order wro LEFT JOIN " . DB_PREFIX . "wk_rma_customer wrc ON (wro.id = wrc.rma_id) LEFT JOIN `" . DB_PREFIX . "order` c ON ((wrc.customer_id = c.customer_id || wrc.email = c.email ) AND c.order_id = wro.order_id) LEFT JOIN " . DB_PREFIX . "wk_rma_status wrs ON (wro.admin_status = wrs.status_id) WHERE 1";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (isset($data['filter_order']) && !is_null($data['filter_order'])) {
			$implode[] = "wro.order_id = '" . (int)$data['filter_order'] . "'";
		}

		if (isset($data['filter_admin_status']) && !is_null($data['filter_admin_status'])) {
			if ($data['filter_admin_status'] == 'admin') {
				$implode[] = "wro.admin_return = 1";
			} else if ($data['filter_admin_status'] == 'solve') {
				$implode[] = "wro.solve_rma = 1";
			} else if ($data['filter_admin_status'] == 'cancel') {
				$implode[] = "wro.cancel_rma = 1";
			} else {
				$implode[] = " wrs.id = '" . (int)$data['filter_admin_status'] . "' AND admin_return <> 1 AND solve_rma <> 1 AND cancel_rma <> 1";
			}
		}

		if (isset($data['filter_rma_status_id']) && !is_null($data['filter_rma_status_id'])) {
			//$implode[] = "wrs.status = '" . (int)$data['filter_rma_status_id'] . "'";
		} else {
			//$implode[] = "wrs.status = 1";
		}

		if (!empty($data['filter_date'])) {
			$implode[] = "LCASE(wro.date) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_date'])) . "%'";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'c.firstname',
			'c.order_id',
			'wrr.id',
			'wro.date',
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY c.firstname";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		$result = $this->db->query($sql);

		return count($result->rows);
	}

	public function getTotalReturns($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "return`r";

		$implode = array();

		if (!empty($data['filter_return_id'])) {
			$implode[] = "r.return_id = '" . (int)$data['filter_return_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$implode[] = "CONCAT(r.firstname, ' ', r.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_order_id'])) {
			$implode[] = "r.order_id = '" . $this->db->escape($data['filter_order_id']) . "'";
		}

		if (!empty($data['filter_product'])) {
			$implode[] = "r.product = '" . $this->db->escape($data['filter_product']) . "'";
		}

		if (!empty($data['filter_model'])) {
			$implode[] = "r.model = '" . $this->db->escape($data['filter_model']) . "'";
		}

		if (!empty($data['filter_return_status_id'])) {
			$implode[] = "r.return_status_id = '" . (int)$data['filter_return_status_id'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(r.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$implode[] = "DATE(r.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalReturnsByReturnStatusId($return_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "return` WHERE return_status_id = '" . (int)$return_status_id . "'");

		return $query->row['total'];
	}

	public function getTotalReturnsByReturnReasonId($return_reason_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "return` WHERE return_reason_id = '" . (int)$return_reason_id . "'");

		return $query->row['total'];
	}

	public function getTotalReturnsByReturnActionId($return_action_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "return` WHERE return_action_id = '" . (int)$return_action_id . "'");

		return $query->row['total'];
	}

	public function addReturnHistory($return_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "return` SET return_status_id = '" . (int)$data['return_status_id'] . "', date_modified = NOW() WHERE return_id = '" . (int)$return_id . "'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "return_history SET return_id = '" . (int)$return_id . "', return_status_id = '" . (int)$data['return_status_id'] . "', notify = '" . (isset($data['notify']) ? (int)$data['notify'] : 0) . "', comment = '" . $this->db->escape(strip_tags($data['comment'])) . "', date_added = NOW()");

		if ($data['notify']) {
			$return_query = $this->db->query("SELECT *, rs.name AS status FROM `" . DB_PREFIX . "return` r LEFT JOIN " . DB_PREFIX . "return_status rs ON (r.return_status_id = rs.return_status_id) WHERE r.return_id = '" . (int)$return_id . "' AND rs.language_id = '" . (int)$this->config->get('config_language_id') . "'");

			if ($return_query->num_rows) {
				$this->load->language('mail/return');

				$subject = sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'), $return_id);

				$message  = $this->language->get('text_return_id') . ' ' . $return_id . "\n";
				$message .= $this->language->get('text_date_added') . ' ' . date($this->language->get('date_format_short'), strtotime($return_query->row['date_added'])) . "\n\n";
				$message .= $this->language->get('text_return_status') . "\n";
				$message .= $return_query->row['status'] . "\n\n";

				if ($data['comment']) {
					$message .= $this->language->get('text_comment') . "\n\n";
					$message .= strip_tags(html_entity_decode($data['comment'], ENT_QUOTES, 'UTF-8')) . "\n\n";
				}

				$message .= $this->language->get('text_footer');

				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

				$mail->setTo($return_query->row['email']);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
				$mail->setSubject($subject);
				$mail->setText($message);
				$mail->send();
			}
		}
	}

	public function getReturnHistories($return_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT rh.date_added, rs.name AS status, rh.comment, rh.notify FROM " . DB_PREFIX . "return_history rh LEFT JOIN " . DB_PREFIX . "return_status rs ON rh.return_status_id = rs.return_status_id WHERE rh.return_id = '" . (int)$return_id . "' AND rs.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY rh.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalReturnHistories($return_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "return_history WHERE return_id = '" . (int)$return_id . "'");

		return $query->row['total'];
	}

	public function getTotalReturnHistoriesByReturnStatusId($return_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "return_history WHERE return_status_id = '" . (int)$return_status_id . "' GROUP BY return_id");

		return $query->row['total'];
	}
}