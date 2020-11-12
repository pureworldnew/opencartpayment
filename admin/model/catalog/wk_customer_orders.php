<?php
/**
 * Webkul Software.
 * @category  Webkul
 * @author    Webkul
 * @copyright Copyright (c) 2010-2016 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
class ModelCatalogWkcustomerorders extends Model {

	use RmaModelTrait;

	public function validateOrder($qty,$order_p_id,$is_admin = false){

		$query = '';

		if($is_admin)
		{
			if((int)$this->config->get('wk_rma_system_time_admin'))
			$query = " AND o.date_added >= DATE_SUB(CURDATE(), INTERVAL '".(int)$this->config->get('wk_rma_system_time_admin')."' DAY)"; //

		} else {
			if((int)$this->config->get('wk_rma_system_time'))
				$query = " AND o.date_added >= DATE_SUB(CURDATE(), INTERVAL '".(int)$this->config->get('wk_rma_system_time')."' DAY)"; //
		}	
		$sql = $this->db->query("SELECT o.order_id FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (o.order_id = op.order_id) WHERE order_product_id='".(int)$order_p_id."' AND quantity >= '".(int)$qty."' $query AND o.order_status_id > 0 ")->row;

		if($sql){
			return true;
		} else {
			return false;
		}
	}

	public function getOrderCustomerID($order_id)
	{
			$query = $this->db->query("SELECT customer_id FROM " . DB_PREFIX . "order WHERE order_id ='".(int)$order_id."'");
			return $query->row ? $query->row['customer_id'] : 0; 
	}

	public function isValidReturnOrderID($order_id)
	{
		$query = '';
		if((int)$this->config->get('wk_rma_system_time_admin'))
		{
			$query .= " AND o.date_added >= DATE_SUB(CURDATE(), INTERVAL '".(int)$this->config->get('wk_rma_system_time_admin')."' DAY) ";
		}
		
		if ($this->config->get('wk_rma_system_orders')) {
			$sql = "SELECT o.order_id,o.total,SUM(op.quantity) as quantity FROM `" . DB_PREFIX . "order` o LEFT JOIN `".DB_PREFIX."order_product` op ON (o.order_id = op.order_id) WHERE o.order_id = '".$order_id."' $query AND o.order_status_id IN (".implode(',',$this->config->get('wk_rma_system_orders')).")";
		} else {
			return false;
		}

    $sql .= " GROUP BY o.order_id ORDER BY o.order_id DESC";

		$query = $this->db->query($sql);
		return $query->row ? true : false; 
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

	public function getAllCustomers(){
    return $this->db->query("SELECT CONCAT(firstname ,' ',lastname) as name , customer_id FROM " . DB_PREFIX . "customer")->rows;
  }

  public function updateRmaAdminStatus($data)
  {
	$this->db->query("UPDATE " . DB_PREFIX . "wk_rma_order SET admin_status = '".(int)$data['wk_rma_admin_adminstatus']."' WHERE `order_id` = '".(int)$data['order']."'");
  }

}
