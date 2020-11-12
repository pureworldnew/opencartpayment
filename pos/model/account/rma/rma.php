<?php
/**
 * Webkul Software.
 * @category  Webkul
 * @author    Webkul
 * @copyright Copyright (c) 2010-2016 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
class ModelAccountRmarma extends Model {

	use RmaModelTrait;

	public function ramDetails($data){

		if(!$this->customer->getId() AND isset($this->session->data['rma_login']))
			$sqlForCustomer = " wrc.customer_id = 0 AND email = '".$this->db->escape($this->session->data['rma_login'])."'";
		else
			$sqlForCustomer = " wrc.customer_id = '".$this->customer->getId()."'";

		$sql = "SELECT wro.id,wro.admin_return,wro.order_id,wro.cancel_rma,wro.solve_rma,wro.date,wrs.color,ot.value,wrs.name as rma_status,wro.admin_status, (SELECT SUM(quantity) FROM ".DB_PREFIX."wk_rma_product wop WHERE wop.rma_id = wro.id) as quantity FROM " . DB_PREFIX . "wk_rma_order wro LEFT JOIN " . DB_PREFIX . "wk_rma_status wrs ON (wro.admin_status = wrs.status_id) LEFT JOIN " . DB_PREFIX . "wk_rma_customer wrc ON (wrc.rma_id = wro.id) LEFT JOIN ". DB_PREFIX ."order_total ot ON (wro.order_id = ot.order_id) WHERE wrs.language_id = '".$this->config->get('config_language_id')."' AND ot.code = 'total' AND $sqlForCustomer ";

		$implode = array();

		if (!empty($data['filter_id'])) {
			$implode[] = "wro.id = '" . (int)$data['filter_id'] . "'";
		}

		if (isset($data['filter_order']) && !is_null($data['filter_order'])) {
			$implode[] = "wro.order_id = '" . (int)$data['filter_order'] . "'";
		}

		if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
			$implode[] = " ot.value LIKE '%" . (float)$data['filter_price'] . "%'";
		}

		if (!empty($data['filter_date'])) {
			$implode[] = "LCASE(wro.date) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_date'])) . "%'";
		}
		
		if (!empty($data['filter_date_from'])) {
			$implode[] = "LCASE(wro.date) >= '" . $this->db->escape(utf8_strtolower($data['filter_date_from'])) . "'";
		}
		
		if (!empty($data['filter_date_to'])) {
			$implode[] = "LCASE(wro.date) <= '" . $this->db->escape(utf8_strtolower($data['filter_date_to'])) . "'";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'wro.id',
			'wro.order_id',
			'ot.value',
			'wop.quantity',
			'wro.date',
			'wrs.name',
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY wro.id";
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

		if (isset($data['filter_qty']) && !is_null($data['filter_qty']))
			foreach ($result->rows as $key => $value) {
				if ($value['quantity'] < (int)$data['filter_qty']) {
					unset($result->rows[$key]);
				}
			}

		return $result->rows;
	}

	public function rmaTotal($data){
		

		if(!$this->customer->getId() AND isset($this->session->data['rma_login']))
			$sqlForCustomer = " wrc.customer_id = 0 AND email = '".$this->db->escape($this->session->data['rma_login'])."'";
		else
			$sqlForCustomer = " wrc.customer_id = '".$this->customer->getId()."'";

		$sql = "SELECT wro.id,wro.order_id,wro.date,ot.value,wrs.name as rma_status,(SELECT SUM(quantity) FROM ".DB_PREFIX."wk_rma_product wop WHERE wop.rma_id = wro.id) as quantity FROM " . DB_PREFIX . "wk_rma_order wro LEFT JOIN " . DB_PREFIX . "wk_rma_status wrs ON (wro.admin_status = wrs.status_id) LEFT JOIN " . DB_PREFIX . "wk_rma_customer wrc ON (wrc.rma_id = wro.id) LEFT JOIN ". DB_PREFIX ."order_total ot ON (wro.order_id = ot.order_id) WHERE wrs.language_id= '".$this->config->get('config_language_id')."' AND ot.code = 'total' AND $sqlForCustomer ";

		$implode = array();

		if (!empty($data['filter_id'])) {
			$implode[] = "wro.id = '" . (int)$data['filter_id'] . "'";
		}

		if (isset($data['filter_order']) && !is_null($data['filter_order'])) {
			$implode[] = "wro.order_id = '" . (int)$data['filter_order'] . "'";
		}

		if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
			$implode[] = " ot.value LIKE '%" . (float)$data['filter_price'] . "%'";
		}

		if (!empty($data['filter_date'])) {
			$implode[] = "LCASE(wro.date) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_date'])) . "%'";
		}
		
		if (!empty($data['filter_date_from'])) {
			$implode[] = "LCASE(wro.date) >= '" . $this->db->escape(utf8_strtolower($data['filter_date_from'])) . "'";
		}
		
		if (!empty($data['filter_date_to'])) {
			$implode[] = "LCASE(wro.date) <= '" . $this->db->escape(utf8_strtolower($data['filter_date_to'])) . "'";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$result = $this->db->query($sql);

		$total = $result->num_rows;

		if (isset($data['filter_qty']) && !is_null($data['filter_qty']))
			foreach ($result->rows as $key => $value) {
				if ($value['quantity'] < (int)$data['filter_qty']) {
					$total--;
				}
			}

		return $total;
	}

	public function viewRmaid($id){

		$query = '';

		if(!$this->customer->getId())
			$query = "AND email = '".$this->db->escape($this->session->data['rma_login'])."'";

		$result = $this->db->query("SELECT wro.*,wrs.color,wrs.name as rma_status,wro.admin_status FROM " . DB_PREFIX . "wk_rma_order wro LEFT JOIN " . DB_PREFIX . "wk_rma_status wrs ON (wro.admin_status = wrs.status_id) LEFT JOIN " . DB_PREFIX . "wk_rma_customer wrc ON (wrc.rma_id = wro.id) WHERE wro.id ='".(int)$id."' AND wrc.customer_id='".(int)$this->customer->getId()."' $query AND wrs.language_id= '".$this->config->get('config_language_id')."'");

		return $result->row;
	}


	public function prodetails($id,$order_id){

		$sql = $this->db->query("SELECT op.product_id,op.name,op.model,op.quantity as ordered,op.tax tax,op.price,wrr.reason,wrp.quantity as returned FROM " . DB_PREFIX . "wk_rma_product wrp LEFT JOIN ".DB_PREFIX."order_product op ON (wrp.product_id = op.product_id AND wrp.order_product_id = op.order_product_id) LEFT JOIN ". DB_PREFIX ."wk_rma_reason wrr ON (wrp.reason = wrr.reason_id) WHERE op.order_id = '".(int)$order_id."' AND wrp.rma_id='".(int)$id."' AND wrr.language_id= '".$this->config->get('config_language_id')."'")->rows;

		if(!$sql){
			$sql = $this->db->query("SELECT p.product_id,pd.name,p.model,p.price,wrr.reason,wrp.quantity as returned FROM " . DB_PREFIX . "wk_rma_product wrp LEFT JOIN ".DB_PREFIX."product p ON (wrp.product_id = p.product_id) LEFT JOIN ".DB_PREFIX."product_description pd ON (p.product_id = pd.product_id) LEFT JOIN ". DB_PREFIX ."wk_rma_reason wrr ON (wrp.reason = wrr.reason_id) WHERE wrp.rma_id='".(int)$id."' AND pd.language_id = '".$this->config->get('config_language_id')."' AND wrr.language_id= '".$this->config->get('config_language_id')."'")->rows;

			if($sql)
				foreach($sql as $key => $value){
					$sql[$key]['ordered'] = '0';
					$sql[$key]['tax'] = '0';
				}
		}

		return $sql;
	}

	public function updateRmaSta($status,$vid,$reopen = false){
		if($reopen){
			$getDefaultStatus = $this->getDefaultStatus();
			$this->db->query("UPDATE " . DB_PREFIX . "wk_rma_order SET admin_status = '".(isset($getDefaultStatus) ? (int)$getDefaultStatus : 0) ."', solve_rma = 0 , cancel_rma = 0 WHERE id ='".(int)$vid."'");
		}else{
			if ($status == 'solve')
				$getDefaultStatus = $this->solveRmaStatus();
			else
				$getDefaultStatus = $this->cancelRmaStatus();
			$this->db->query("UPDATE " . DB_PREFIX . "wk_rma_order SET ".$status."_rma = 1 WHERE id ='".(int)$vid."'");
		}

		$data = array('status' => $status,
					  'rma_id' => $vid,
					);

		$this->mail($data,'status_to_admin');
		$this->mail($data,'status_to_customer');

	}

	public function updateRmaAuth($auth_no,$vid){
		$this->db->query("UPDATE " . DB_PREFIX . "wk_rma_order SET rma_auth_no = '".$this->db->escape($auth_no)."' WHERE id ='".(int)$vid."'");
	}

	public function insertMessageRma($msg,$writer,$vid,$file){

		$this->db->query("INSERT INTO " . DB_PREFIX . "wk_rma_order_message SET rma_id = '".$this->db->escape($vid)."', writer = '".$this->db->escape($writer)."', message = '".$this->db->escape(nl2br($msg))."', date = NOW(), attachment = '".$this->db->escape($file)."'");

		$data = array();
		$data['rma_id'] = $vid;
		$data['message'] = $msg;

		$this->mail($data,'message_to_admin');
	}

	public function viewRmaMessage($vid,$start,$limit){
		$sql = $this->db->query("SELECT * FROM " . DB_PREFIX . "wk_rma_order_message WHERE rma_id='".(int)$vid."' ORDER BY id DESC LIMIT ".$start .",".$limit);
		return $sql->rows;
	}

	public function viewTotalRmaMessage($vid){
		$sql =$this->db->query("SELECT * FROM " . DB_PREFIX . "wk_rma_order_message WHERE rma_id='".(int)$vid."' ORDER BY id");
		return count($sql->rows);
	}

	public function rmaprice($id){
		$result = $this->db->query("SELECT total FROM " . DB_PREFIX . "order WHERE order_id='$id' ");
		return $result->row;
	}

	public function validateOrder($qty,$order_p_id){

		$query = '';

		if((int)$this->config->get('wk_rma_system_time'))
			$query = " AND o.date_added >= DATE_SUB(CURDATE(), INTERVAL '".(int)$this->config->get('wk_rma_system_time')."' DAY)"; // AND o.date_added <= CURDATE()

		//AND o.order_id NOT IN (SELECT order_id FROM ".DB_PREFIX."wk_rma_order)
		$sql = $this->db->query("SELECT o.order_id FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (o.order_id = op.order_id) WHERE order_product_id='".(int)$order_p_id."' AND quantity >= '".(int)$qty."' $query AND o.order_status_id > 0 ")->row;

		if($sql){
			return true;
		}else{
			return false;
		}
	}

	//for reason
	public function getCustomerReason(){
		$sql = $this->db->query("SELECT reason ,reason_id as id FROM " . DB_PREFIX . "wk_rma_reason WHERE language_id ='".$this->config->get('config_language_id')."' AND status = 1 ORDER BY id ");
		return $sql->rows;
	}

	//for status
	public function getCustomerStatus(){
		$sql = $this->db->query("SELECT name,status_id as id FROM " . DB_PREFIX . "wk_rma_status wrs WHERE language_id ='".$this->config->get('config_language_id')."' AND status = 1 ORDER BY id ");
		return $sql->rows;
	}

	//check guest login
	public function getGuestStatus($data){
		$sql = $this->db->query("SELECT customer_id FROM `" . DB_PREFIX . "order` WHERE order_id = '".(int)$data['orderinfo']."' AND  email = '".$this->db->escape($data['email'])."'");
		return $sql->row;
	}
}
