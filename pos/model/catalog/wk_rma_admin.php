<?php
/**
 * Webkul Software.
 * @category  Webkul
 * @author    Webkul
 * @copyright Copyright (c) 2010-2016 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
class ModelCatalogwkrmaadmin extends Model {

	use RmaModelTrait;

	public function approveAdminStatus($data = array()) {
		if (isset($data['id']) && isset($data['approve']) && $this->db->query("SELECT * FROM `" . DB_PREFIX . "wk_rma_status` WHERE `status_id`='" . (int)$this->db->escape($data['id']) . "'")->num_rows) {
			$this->db->query("UPDATE `" . DB_PREFIX . "wk_rma_status` SET `default` = 0 WHERE `default` NOT IN ('cancel','solve')");
			if ($data['approve'] == 'approve') {
				$this->db->query("UPDATE `" . DB_PREFIX . "wk_rma_status` SET `default` = 'admin' WHERE status_id='" . (int)$this->db->escape($data['id']) . "'");
				$this->db->query("UPDATE `" . DB_PREFIX . "wk_rma_order` SET  `admin_status` =  '" . (int)$this->db->escape($data['id']) . "' WHERE admin_status = 0 ");
			}
			return true;
		}
		return false;
	}

	public function approveSolveStatus($data = array()) {
		if (isset($data['id']) && isset($data['approve']) && $this->db->query("SELECT * FROM `" . DB_PREFIX . "wk_rma_status` WHERE `status_id`='" . (int)$this->db->escape($data['id']) . "'")->num_rows) {
			$this->db->query("UPDATE `" . DB_PREFIX . "wk_rma_status` SET `default` = 'null' WHERE `default` NOT IN ('cancel','admin')");
			if ($data['approve'] == 'approve') {
				$this->db->query("UPDATE `" . DB_PREFIX . "wk_rma_status` SET `default` = 'solve' WHERE status_id='" . (int)$this->db->escape($data['id']) . "'");
				$this->db->query("UPDATE `" . DB_PREFIX . "wk_rma_order` SET  `admin_status` =  '" . (int)$this->db->escape($data['id']) . "' WHERE solve_rma = 1 AND admin_status = 0");
			}
			return true;
		}
		return false;
	}

	public function approveCancelStatus($data = array()) {
		if (isset($data['id']) && isset($data['approve']) && $this->db->query("SELECT * FROM `" . DB_PREFIX . "wk_rma_status` WHERE `status_id`='" . (int)$this->db->escape($data['id']) . "'")->num_rows) {
			$this->db->query("UPDATE `" . DB_PREFIX . "wk_rma_status` SET `default` = 'null' WHERE `default` NOT IN ('admin','solve')");
			if ($data['approve'] == 'approve') {
				$this->db->query("UPDATE `" . DB_PREFIX . "wk_rma_status` SET `default` = 'cancel' WHERE status_id='" . (int)$this->db->escape($data['id']) . "'");
				$this->db->query("UPDATE `" . DB_PREFIX . "wk_rma_order` SET  `admin_status` =  '" . (int)$this->db->escape($data['id']) . "' WHERE cancel_rma = 1 AND admin_status = 0");
			}
			return true;
		}
		return false;
	}

	public function getOrderStatus() {
		return $this->db->query("SELECT order_status_id FROM `" . DB_PREFIX . "wk_rma_status` WHERE 1=1")->rows;
	}

	public function UpdateStatus($data){
		foreach($data['name'] as $key => $value)
			$this->db->query("UPDATE " . DB_PREFIX . "wk_rma_status SET `name` = '".$this->db->escape($value)."', `status` = '".(int)$data['status']."',`color`= '".$this->db->escape($data['color'])."' WHERE status_id = '".(int)$data['id']."' AND `language_id` ='".(int)$key."' ");
	}

	public function getTransactions($rma_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_transaction ct LEFT JOIN " . DB_PREFIX . "wk_rma_transaction wrt ON (ct.customer_transaction_id = wrt.transaction_id) WHERE rma_id = '" . (int)$rma_id . "' ORDER BY date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalTransactions($rma_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total  FROM " . DB_PREFIX . "customer_transaction  ct LEFT JOIN " . DB_PREFIX . "wk_rma_transaction wrt ON (ct.customer_transaction_id = wrt.transaction_id) WHERE  rma_id = '" . (int)$rma_id . "'");

		return $query->row['total'];
	}

	public function getTransactionTotal($rma_id) {
		$query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "customer_transaction  ct LEFT JOIN " . DB_PREFIX . "wk_rma_transaction wrt ON (ct.customer_transaction_id = wrt.transaction_id) WHERE rma_id = '" . (int)$rma_id . "'");

		return $query->row['total'];
	}

	public function getTransactionTotalRma($customer_id) {
		$query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "customer_transaction  ct LEFT JOIN " . DB_PREFIX . "wk_rma_transaction wrt ON (ct.customer_transaction_id = wrt.transaction_id) WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row['total'];
	}

	public function addTransaction($rma_id,$description = '', $amount = '') {

		$customer_id = $this->db->query("SELECT customer_id FROM `" . DB_PREFIX . "wk_rma_customer` WHERE rma_id = '" . (int)$rma_id . "'")->row;

		$customer_id = isset($customer_id['customer_id']) ? $customer_id['customer_id'] : 0;

		if (version_compare(VERSION,'2.1','>=')) {
			$this->load->model('customer/customer');
			$customer_info = $this->model_customer_customer->getCustomer($customer_id);
		} else {
			$this->load->model('sale/customer');
			$customer_info = $this->model_sale_customer->getCustomer($customer_id);
		}

		if ($customer_info) {

			$check = $this->db->query("SELECT * FROM `" . DB_PREFIX . "wk_rma_transaction` WHERE rma_id = '" . (int)$rma_id . "'")->row;
			if (!$check) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "customer_transaction SET customer_id = '" . (int)$customer_id . "', order_id = '0', description = '" . $this->db->escape($description) . "', amount = '" . (float)$amount . "', date_added = NOW()");
				$this->db->query("INSERT INTO `" . DB_PREFIX . "wk_rma_transaction` SET transaction_id = " . $this->db->getlastId() . ", rma_id = '" . (int)$rma_id . "'");
			} else {
				$this->db->query("UPDATE " . DB_PREFIX . "customer_transaction SET customer_id = '" . (int)$customer_id . "', order_id = '0', description = '" . $this->db->escape($description) . "', amount = '" . (float)$amount . "', date_added = NOW() WHERE customer_transaction_id = " . (int)$check['transaction_id'] . "");
			}

			$this->load->language('mail/customer');

			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore($customer_info['store_id']);

			if ($store_info) {
				$store_name = $store_info['name'];
			} else {
				$store_name = $this->config->get('config_name');
			}

			$message  = sprintf($this->language->get('text_transaction_received'), $this->currency->format($amount, $this->config->get('config_currency'))) . "\n\n";
			$message .= sprintf($this->language->get('text_transaction_total'), $this->currency->format($this->getTransactionTotalRma($customer_id), $this->config->get('config_currency')));

			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($customer_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($store_name, ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(sprintf($this->language->get('text_transaction_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')));
			$mail->setText($message);
			$mail->send();
		}
	}

	public function addVoucher($data = array()) {
		$data['code'] = chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90)) . intval( "0" . rand(1,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) );
		$data['from_name'] = $this->config->get('config_name');
		$data['from_email'] = $this->config->get('config_email');

		$customer_info = $this->getCustomer($data['rma_id']);

		$data['to_name'] = $customer_info['firstname'] . ' ' . $customer_info['lastname'];
		$data['to_email'] = $customer_info['email'];

		$check = $this->db->query("SELECT * FROM `" . DB_PREFIX . "wk_rma_voucher` WHERE rma_id = '" . (int)$data['rma_id'] . "'")->row;
		if (!$check) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "voucher SET code = '" . $this->db->escape($data['code']) . "', from_name = '" . $this->db->escape($data['from_name']) . "', from_email = '" . $this->db->escape($data['from_email']) . "', to_name = '" . $this->db->escape($data['to_name']) . "', to_email = '" . $this->db->escape($data['to_email']) . "', voucher_theme_id = '" . (int)$this->config->get('wk_rma_voucher_theme') . "', message = '" . $this->db->escape($data['message']) . "', amount = '" . (float)$data['amount'] . "', status = '1', date_added = NOW()");
			$voucher_id = $this->db->getlastId();
			$this->db->query("INSERT INTO `" . DB_PREFIX . "wk_rma_voucher` SET transaction_id = " . (int)$voucher_id . ", rma_id = '" . (int)$data['rma_id'] . "'");
		} else {
			$this->db->query("UPDATE " . DB_PREFIX . "voucher SET message = '" . $this->db->escape($data['message']) . "', amount = '" . (float)$data['amount'] . "', status = '1', date_added = NOW() WHERE voucher_id = '" . (int)$check['transaction_id'] . "'");
			$voucher_id = (int)$check['transaction_id'];
		}

		$this->load->model('sale/voucher');
		$this->model_sale_voucher->sendVoucher($voucher_id);

	}

	public function getVoucherTotal($rma_id) {
		$query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "voucher  ct LEFT JOIN " . DB_PREFIX . "wk_rma_voucher wrt ON (ct.voucher_id = wrt.transaction_id) WHERE rma_id = '" . (int)$rma_id . "'")->row;

		if($query)
			return $query['total'];
		else {
			return false;
		}
	}

	/*************************************************/

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

	public function deleteentry($id){

		$imagefolder = $this->db->query("SELECT images FROM " . DB_PREFIX . "wk_rma_order WHERE id = '".(int)$id."'")->row;

		$this->db->query("DELETE FROM " . DB_PREFIX . "wk_rma_order WHERE id = '".(int)$id."'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "wk_rma_order_message WHERE rma_id = '".(int)$id."'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "wk_rma_customer WHERE rma_id = '".(int)$id."'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "wk_rma_product WHERE rma_id = '".(int)$id."'");

		if($imagefolder){
			$dirPath = DIR_IMAGE.'rma/'.$imagefolder['images'];
			if(file_exists($dirPath)){
				$this->deleteDir($dirPath);
			}
		}

	}

	public function deleteDir($dirPath){
		$files = glob($dirPath . '*', GLOB_MARK);
	    foreach ($files as $file) {
	        if (is_dir($file)) {
	            self::deleteDir($file);
	        } else {
	            unlink($file);
	        }
	    }
	    if(file_exists($dirPath))
	    	rmdir($dirPath);
	}

	public function viewtotalMessageBy($data){

		$sql = "SELECT * FROM " . DB_PREFIX . "wk_rma_order_message wrm WHERE wrm.rma_id = '".(int)$data['filter_id']."'";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "LCASE(wrm.writer) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		}

		if (isset($data['filter_message']) && !empty($data['filter_message'])) {
			$implode[] = "LCASE(wrm.message) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_message'])) . "%'";
		}

		if (isset($data['filter_date']) && !empty($data['filter_date'])) {
			$implode[] = "LCASE(wrm.date) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_date'])) . "%'";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'wrm.writer',
			'wrm.message',
			'wrm.date',
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY wrm.id";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " ASC";
		} else {
			$sql .= " DESC";
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

	public function viewtotalNoMessageBy($data){

		$sql = "SELECT * FROM " . DB_PREFIX . "wk_rma_order_message wrm WHERE wrm.rma_id = '".(int)$data['filter_id']."'";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "LCASE(wrm.writer) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		}

		if (isset($data['filter_message']) && !empty($data['filter_message'])) {
			$implode[] = "LCASE(wrm.message) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_message'])) . "%'";
		}

		if (isset($data['filter_date']) && !empty($data['filter_date'])) {
			$implode[] = "LCASE(wrm.date) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_date'])) . "%'";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$result = $this->db->query($sql);

		return count($result->rows);
	}


	public function updateAdminStatus($msg,$status,$vid,$notify,$file_name){

		
		$this->db->query("UPDATE " . DB_PREFIX . "wk_rma_order SET admin_status='".(int)$status."' WHERE id='".(int)$vid."' ");

		if($msg!=''){
			$this->db->query("INSERT INTO " . DB_PREFIX . "wk_rma_order_message SET rma_id = '".$this->db->escape($vid)."', writer = 'admin', message = '".$this->db->escape(nl2br($msg))."',is_notify = '".$this->db->escape($notify)."', date = NOW(), attachment = '".$this->db->escape($file_name)."'");
		}

		$query = $this->db->query("SELECT name FROM " . DB_PREFIX . "wk_rma_status WHERE status_id = '".(int)$status."'");

		$status = $query->row ? $query->row['name'] : $status;

		$data = array(
			'status' => $status,
			'rma_id' => $vid,
			'message' => $msg,
		);

		if ($notify == 1){
			//die("MAIN IS HERE");
			$this->mail($data,'message_to_customer');
		}

	}

	public function addLabel($id,$file,$folder = ''){
		$this->db->query("UPDATE " . DB_PREFIX . "wk_rma_order SET `shipping_label` = '".$this->db->escape($file)."' WHERE id = '".(int)$id."'");

		$data = array('rma_id' => $id,
					  'link' => HTTP_CATALOG.'index.php?route=account/rma/viewrma/printlable&vid='.$id,
					  'label' => 'rma/'.$folder.'/files/'.$file
					);
		$this->mail($data,'label_to_customer');

	}

	public function viewCustomerDetails($order_id){
		$result = $this->db->query("SELECT o.firstname,o.lastname FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '$order_id' ");
		return $result->row;
	}

	public function getRmaOrderid($id){
		$result = $this->db->query("SELECT wro.*,wro.admin_status as admin_st,wrs.name as rma_status,wrs.color,wrs.name as admin_status FROM " . DB_PREFIX . "wk_rma_order wro LEFT JOIN " . DB_PREFIX . "wk_rma_status wrs ON (wro.admin_status = wrs.status_id) LEFT JOIN " . DB_PREFIX . "wk_rma_customer wrc ON (wrc.rma_id = wro.id) WHERE wro.id ='".(int)$id."'");
		if ($result) {
			return $result->row;
		}
		return false;
	}

	//for reason
	public function viewreason($data){
		$sql = "SELECT * FROM " . DB_PREFIX . "wk_rma_reason wrr WHERE language_id ='".$this->config->get('config_language_id')."' ";

		$implode = array();

		if (!empty($data['filter_reason'])) {
			$implode[] = "LCASE(wrr.reason) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_reason'])) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "wrr.status = '" . (int)$data['filter_status'] . "'";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'wrr.reason',
			'wrr.status',
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY id";
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

	public function viewreasonbyId($id){
		$sql = $this->db->query("SELECT * FROM " . DB_PREFIX . "wk_rma_reason WHERE reason_id='".(int)$id."'");
		return $sql->rows;
	}

	public function viewtotalreason($data){
		$sql = "SELECT * FROM " . DB_PREFIX . "wk_rma_reason wrr WHERE language_id ='".$this->config->get('config_language_id')."' ";

		$implode = array();

		if (!empty($data['filter_reason'])) {
			$implode[] = "LCASE(wrr.reason) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_reason'])) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "wrr.status = '" . (int)$data['filter_status'] . "'";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$result = $this->db->query($sql);
		return count($result->rows);
	}

	public function deleteReason($id){
		$this->db->query("DELETE FROM " . DB_PREFIX . "wk_rma_reason WHERE reason_id = '".(int)$id."'");
	}

	public function addReason($data){
		$reason_id = 1;
		$last_reason_id = $this->db->query("SELECT reason_id FROM " . DB_PREFIX . "wk_rma_reason ORDER BY reason_id DESC LIMIT 1")->row;
		if(isset($last_reason_id['reason_id']))
			$reason_id = $last_reason_id['reason_id']+1;
		foreach($data['reason'] as $key => $value)
			$this->db->query("INSERT INTO " . DB_PREFIX . "wk_rma_reason SET reason_id = '".(int)$reason_id."',`reason` = '".$this->db->escape($value)."',`language_id` ='".(int)$key."', `status` = '".(int)$data['status']."'");
	}

	public function UpdateReason($data){

		$reason_id = $data['id'];
		foreach($data['reason'] as $key => $value)
			$this->db->query("UPDATE " . DB_PREFIX . "wk_rma_reason SET `reason` = '".$this->db->escape($value)."',`status` = '".(int)$data['status']."' WHERE reason_id = '".(int)$reason_id."' AND `language_id` ='".(int)$key."'");
	}

	public function getCustomerReason(){
		$sql = $this->db->query("SELECT reason ,id FROM " . DB_PREFIX . "wk_rma_reason WHERE language_id ='".$this->config->get('config_language_id')."' AND status = 1 ORDER BY id ");
		return $sql->rows;
	}

	public function viewStatus($data){
		$sql = "SELECT * FROM " . DB_PREFIX . "wk_rma_status wrs WHERE wrs.language_id ='".$this->config->get('config_language_id')."'";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "LCASE(wrs.name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "wrs.status = '" . (int)$data['filter_status'] . "'";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'wrs.name',
			'wrs.status',
			'wrs.id',
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY id";
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

	public function viewStatusbyId($id){
		$sql = $this->db->query("SELECT * FROM " . DB_PREFIX . "wk_rma_status WHERE status_id='".(int)$id."'");
		return $sql->rows;
	}

	public function getOrderStatusFromStatus(){
		$sql = $this->db->query("SELECT DISTINCT order_status_id FROM " . DB_PREFIX . "wk_rma_status ");
		return $sql->rows;
	}

	public function viewtotalStatus($data){
		$sql = "SELECT * FROM " . DB_PREFIX . "wk_rma_status wrs WHERE wrs.language_id ='".$this->config->get('config_language_id')."'";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "LCASE(wrs.name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "wrs.status = '" . (int)$data['filter_status'] . "'";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$result = $this->db->query($sql);

		return count($result->rows);
	}

	public function deleteStatus($id){
		/**
		 * we are disabling order status because if we delete it then RMA having admin status with this status id will never be shown at any end.
		 */
		$this->db->query("DELETE FROM " . DB_PREFIX . "wk_rma_status WHERE status_id = '".(int)$id."'");
		// $this->db->query("UPDATE " . DB_PREFIX . "wk_rma_status SET status = 0 WHERE status_id = '".(int)$id."'");
	}

	public function addStatus($data){

		$status_id = 1;
		$last_status_id = $this->db->query("SELECT status_id FROM " . DB_PREFIX . "wk_rma_status ORDER BY status_id DESC LIMIT 1")->row;
		if(isset($last_status_id['status_id']))
			$status_id = $last_status_id['status_id']+1;

		foreach($data['name'] as $key => $value)
			$this->db->query("INSERT INTO " . DB_PREFIX . "wk_rma_status SET status_id = '".(int)$status_id."',`name` = '".$this->db->escape($value)."',`language_id` ='".(int)$key."', `status` = '".(int)$data['status']."', `color`= '".$this->db->escape($data['color'])."'");
	}

	public function getCustomerStatus(){
		$sql = $this->db->query("SELECT name ,id FROM " . DB_PREFIX . "wk_rma_status WHERE language_id ='".$this->config->get('config_language_id')."' AND status = 1 ORDER BY id ");
		return $sql->rows;
	}

	public function getAdminStatus(){
		$sql = $this->db->query("SELECT name ,id,status_id FROM " . DB_PREFIX . "wk_rma_status WHERE language_id ='".$this->config->get('config_language_id')."' AND status = 1 ORDER BY id ");
		return $sql->rows;
	}

	public function getOrderProducts($order_id,$id) {

		$sql = $this->db->query("SELECT op.product_id,op.name,op.model,op.quantity as ordered,op.tax tax,op.price,wrp.quantity as returned,op.order_product_id,wrr.reason FROM " . DB_PREFIX . "wk_rma_product wrp LEFT JOIN ".DB_PREFIX."order_product op ON (wrp.product_id = op.product_id AND wrp.order_product_id = op.order_product_id) LEFT JOIN ".DB_PREFIX."wk_rma_reason wrr ON (wrp.reason = wrr.reason_id) WHERE op.order_id = '".(int)$order_id."' AND wrp.rma_id='".(int)$id."' AND wrr.language_id = '".$this->config->get('config_language_id')."'")->rows;
		if(!$sql){
			$sql = $this->db->query("SELECT p.product_id,pd.name,p.model,p.price,wrp.quantity as returned,wrr.reason FROM " . DB_PREFIX . "wk_rma_product wrp LEFT JOIN ".DB_PREFIX."product p ON (wrp.product_id = p.product_id) LEFT JOIN ".DB_PREFIX."product_description pd ON (p.product_id = pd.product_id) LEFT JOIN ".DB_PREFIX."wk_rma_reason wrr ON (wrp.reason = wrr.reason_id) WHERE wrp.rma_id='".(int)$id."' AND pd.language_id = '".$this->config->get('config_language_id')."' AND wrr.language_id = '".$this->config->get('config_language_id')."'")->rows;

			if($sql)
				foreach($sql as $key => $value){
					$sql[$key]['ordered'] = '0';
					$sql[$key]['tax'] = '0';
					$sql[$key]['order_product_id'] = 0;
				}
		}

		return $sql;
	}

	public function returnQty($rma_id){

		$sql = $this->db->query("SELECT order_id FROM " . DB_PREFIX . "wk_rma_order WHERE id ='".(int)$rma_id."'")->row;

		$order_product_query = false;

		if($sql)
			$order_product_query = $this->getOrderProducts($sql['order_id'],$rma_id);

		if($order_product_query){

			//load opencart order model and get order info
			$this->load->model('sale/order');
			$This_order = $this->model_sale_order->getOrder($sql['order_id']);

			//make shipping ,payment, store array for get tax
			$shipping_address = array(
				'country_id' => $This_order['shipping_country_id'],
				'zone_id'    => $This_order['shipping_zone_id']
			);

			$payment_address = array(
				'country_id' => $This_order['payment_country_id'],
				'zone_id'    => $This_order['payment_zone_id']
			);

			$store_address = array(
				'country_id' => $this->config->get('config_country_id'),
				'zone_id'    => $this->config->get('config_zone_id')
			);

			$this->db->query("UPDATE " . DB_PREFIX . "wk_rma_order SET admin_return = 1 WHERE id ='".(int)$rma_id."'");

			foreach ($order_product_query as $order_product) {

				if($order_product['returned'] <= $order_product['ordered']){

					$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity + " . (int)$order_product['returned'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND subtract = '1'");

					$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$sql['order_id'] . "' AND order_product_id = '" . (int)$order_product['order_product_id'] . "'");

					foreach ($order_option_query->rows as $option) {
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity + " . (int)$order_product['returned'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
					}

					$this->db->query("UPDATE " . DB_PREFIX . "order_product SET quantity = (quantity - " . (int)$order_product['returned'] . "), `total` = (`total`-(`total`*".(int)$order_product['returned'].")/".(int)$order_product['ordered']."), reward = (`reward`-(`reward`*".(int)$order_product['returned'].")/".(int)$order_product['ordered'].") WHERE order_product_id = '" . (int)$order_product['order_product_id'] . "'");


				}else{ //remove product from order
					$this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_product_id = '" . (int)$order_product['order_product_id'] . "'");
					$this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_product_id = '" . (int)$order_product['order_product_id'] . "'");
				}

			}

			// code for update total price of order
			$getProducts = $this->db->query("SELECT * FROM ".DB_PREFIX."order_product WHERE order_id = '".$sql['order_id']."'")->rows;

			if($getProducts){
				$sub_total = $total = 0;
				foreach ($getProducts as $key => $value) {
					$sub_total = $sub_total + $value['total'];
				}
				//get tax from function
				$tax_class_id = $this->db->query("SELECT tax_class_id FROM ".DB_PREFIX."product WHERE product_id = '".$order_product['product_id']."'")->row;

				$tax_per_product = 0;
				$ItemBasePrice = $this->currency->convert($order_product['price'], $this->config->get('config_currency'), $This_order['currency_code']);
				if($tax_class_id)
					$tax_per_product = $this->getRates($ItemBasePrice, $tax_class_id['tax_class_id'], $This_order['customer_group_id'],$shipping_address,$payment_address,$store_address);

				if($tax_per_product){
					foreach ($tax_per_product as $key => $value) {
						if(!$sub_total)
							$this->db->query("UPDATE " . DB_PREFIX . "order_total SET value = (value - " . (float)($value['amount'] * $order_product['returned']) . ") WHERE title = '" . $this->db->escape($value['name']) . "' AND code = 'tax' AND order_id = '".$sql['order_id']."'");
						else
							$this->db->query("UPDATE " . DB_PREFIX . "order_total SET value = 0 WHERE title = '" . $this->db->escape($value['name']) . "' AND code = 'tax' AND order_id = '".$sql['order_id']."'");
					}
				}
				$getOrderTotal = $this->db->query("SELECT * FROM ".DB_PREFIX."order_total WHERE order_id = '".$sql['order_id']."'")->rows;

				foreach ($getOrderTotal as $key => $value) {
					if($value['code']!='sub_total' AND $value['code']!='total')
						$total = $total + $value['value'];
				}
				$total = $total + $sub_total;
				$subtotalWidCurrency = $this->currency->format($sub_total, $This_order['currency_code']);
				$totalWidCurrency = $this->currency->format($total, $This_order['currency_code']);

				$this->db->query("UPDATE " . DB_PREFIX . "order_total SET value = '".(float)$total."' WHERE order_id = '".$sql['order_id']."' AND code = 'total'");
				$this->db->query("UPDATE " . DB_PREFIX . "order_total SET value = '".(float)$sub_total."' WHERE order_id = '".$sql['order_id']."' AND code = 'sub_total'");
			}

		}

	}

	public function getRates($value, $tax_class_id, $customer_group_id,$shipping_address,$payment_address,$store_address) {
		$tax_rates = array();

		if (!$customer_group_id) {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		if ($shipping_address) {
			$tax_query = $this->db->query("SELECT tr2.tax_rate_id, tr2.name, tr2.rate, tr2.type, tr1.priority FROM " . DB_PREFIX . "tax_rule tr1 LEFT JOIN " . DB_PREFIX . "tax_rate tr2 ON (tr1.tax_rate_id = tr2.tax_rate_id) INNER JOIN " . DB_PREFIX . "tax_rate_to_customer_group tr2cg ON (tr2.tax_rate_id = tr2cg.tax_rate_id) LEFT JOIN " . DB_PREFIX . "zone_to_geo_zone z2gz ON (tr2.geo_zone_id = z2gz.geo_zone_id) LEFT JOIN " . DB_PREFIX . "geo_zone gz ON (tr2.geo_zone_id = gz.geo_zone_id) WHERE tr1.tax_class_id = '" . (int)$tax_class_id . "' AND tr1.based = 'shipping' AND tr2cg.customer_group_id = '" . (int)$customer_group_id . "' AND z2gz.country_id = '" . (int)$shipping_address['country_id'] . "' AND (z2gz.zone_id = '0' OR z2gz.zone_id = '" . (int)$shipping_address['zone_id'] . "') ORDER BY tr1.priority ASC");

			foreach ($tax_query->rows as $result) {
				$tax_rates[$result['tax_rate_id']] = array(
					'tax_rate_id' => $result['tax_rate_id'],
					'name'        => $result['name'],
					'rate'        => $result['rate'],
					'type'        => $result['type'],
					'priority'    => $result['priority']
				);
			}
		}

		if ($payment_address) {
			$tax_query = $this->db->query("SELECT tr2.tax_rate_id, tr2.name, tr2.rate, tr2.type, tr1.priority FROM " . DB_PREFIX . "tax_rule tr1 LEFT JOIN " . DB_PREFIX . "tax_rate tr2 ON (tr1.tax_rate_id = tr2.tax_rate_id) INNER JOIN " . DB_PREFIX . "tax_rate_to_customer_group tr2cg ON (tr2.tax_rate_id = tr2cg.tax_rate_id) LEFT JOIN " . DB_PREFIX . "zone_to_geo_zone z2gz ON (tr2.geo_zone_id = z2gz.geo_zone_id) LEFT JOIN " . DB_PREFIX . "geo_zone gz ON (tr2.geo_zone_id = gz.geo_zone_id) WHERE tr1.tax_class_id = '" . (int)$tax_class_id . "' AND tr1.based = 'payment' AND tr2cg.customer_group_id = '" . (int)$customer_group_id . "' AND z2gz.country_id = '" . (int)$payment_address['country_id'] . "' AND (z2gz.zone_id = '0' OR z2gz.zone_id = '" . (int)$payment_address['zone_id'] . "') ORDER BY tr1.priority ASC");

			foreach ($tax_query->rows as $result) {
				$tax_rates[$result['tax_rate_id']] = array(
					'tax_rate_id' => $result['tax_rate_id'],
					'name'        => $result['name'],
					'rate'        => $result['rate'],
					'type'        => $result['type'],
					'priority'    => $result['priority']
				);
			}
		}

		if ($store_address) {
			$tax_query = $this->db->query("SELECT tr2.tax_rate_id, tr2.name, tr2.rate, tr2.type, tr1.priority FROM " . DB_PREFIX . "tax_rule tr1 LEFT JOIN " . DB_PREFIX . "tax_rate tr2 ON (tr1.tax_rate_id = tr2.tax_rate_id) INNER JOIN " . DB_PREFIX . "tax_rate_to_customer_group tr2cg ON (tr2.tax_rate_id = tr2cg.tax_rate_id) LEFT JOIN " . DB_PREFIX . "zone_to_geo_zone z2gz ON (tr2.geo_zone_id = z2gz.geo_zone_id) LEFT JOIN " . DB_PREFIX . "geo_zone gz ON (tr2.geo_zone_id = gz.geo_zone_id) WHERE tr1.tax_class_id = '" . (int)$tax_class_id . "' AND tr1.based = 'store' AND tr2cg.customer_group_id = '" . (int)$customer_group_id . "' AND z2gz.country_id = '" . (int)$store_address['country_id'] . "' AND (z2gz.zone_id = '0' OR z2gz.zone_id = '" . (int)$store_address['zone_id'] . "') ORDER BY tr1.priority ASC");

			foreach ($tax_query->rows as $result) {
				$tax_rates[$result['tax_rate_id']] = array(
					'tax_rate_id' => $result['tax_rate_id'],
					'name'        => $result['name'],
					'rate'        => $result['rate'],
					'type'        => $result['type'],
					'priority'    => $result['priority']
				);
			}
		}

		$tax_rate_data = array();

		foreach ($tax_rates as $tax_rate) {
			if (isset($tax_rate_data[$tax_rate['tax_rate_id']])) {
				$amount = $tax_rate_data[$tax_rate['tax_rate_id']]['amount'];
			} else {
				$amount = 0;
			}

			if ($tax_rate['type'] == 'F') {
				$amount += $tax_rate['rate'];
			} elseif ($tax_rate['type'] == 'P') {
				$amount += ($value / 100 * $tax_rate['rate']);
			}

			$tax_rate_data[$tax_rate['tax_rate_id']] = array(
				'tax_rate_id' => $tax_rate['tax_rate_id'],
				'name'        => $tax_rate['name'],
				'rate'        => $tax_rate['rate'],
				'type'        => $tax_rate['type'],
				'amount'      => $amount
			);
		}

		return $tax_rate_data;
	}

	public function checkIfCustomer($rma_id) {

		return $this->db->query("SELECT * FROM `" . DB_PREFIX . "wk_rma_customer` WHERE rma_id = '" . (int)$rma_id . "'")->row['customer_id'];

	}

}
