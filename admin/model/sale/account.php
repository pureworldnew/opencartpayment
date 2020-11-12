<?php

class ModelSaleAccount extends Model {

    /**
     * Function to get all the orders with product information
     * @param type $data
     */
    public function getAllOrders($data) {
        
    }

    /**
     * Get Total Fields 
     * @param type $data
     */
    public function getTotalFeild() {
        
    }
	
	public function trancatedb() {
			$query_sql = "TRUNCATE TABLE `oc_order_accounts_report`";
			$this->db->query($query_sql);
	}
	
	 public function checkrecords() {
       $query_sql = "SELECT  count(*) as total From oc_order_accounts_report where 1";
		
		$query = $this->db->query($query_sql);
        
		if($query->row['total'] > 0){
			return '1';	
		}else{
			return '0';	
		}
    }
	
	public function firstlastrecords() {
       $query_sql = " SELECT 
						(SELECT date FROM oc_order_accounts_report WHERE 1 ORDER BY order_id LIMIT 1) as 'first',
						(SELECT date FROM oc_order_accounts_report WHERE 1 ORDER BY order_id DESC LIMIT 1) as 'last'";
		
		$query = $this->db->query($query_sql);
        
		return $query->row;
    }
  
   
    public function exportExcel($date_from, $date_to) {
       /* $query_sql = "SELECT        oc_order.order_id as order_id,
                                    oc_order.date_added as date,
                                    CONCAT(oc_order.firstname,' ',oc_order.lastname) as customer_name,
                                    oc_order.currency_code as currency,
                                    oc_order.email as customer_email,
                                    oc_order.telephone as customer_phone,
                                    oc_order.shipping_address_1 as delivery_address,
                                    oc_order.shipping_city,
									 oc_order.shipping_zone,
                                    oc_order.shipping_country,
                                    oc_order.payment_address_1,
                                    oc_order.payment_city,
									oc_order.payment_zone,
                                    oc_order.payment_country,
                                    oc_order.resale_text as customer_resale,
									oc_customer_group_description.name as customer_group,
                                    oc_order.total
                                    FROM oc_order 
									INNER JOIN oc_customer_group_description on oc_customer_group_description.customer_group_id = oc_order.customer_group_id
                                   	WHERE oc_order.order_status_id > 0 AND oc_order.date_added BETWEEN '$date_from' AND '$date_to' 
                                    ORDER BY oc_order.order_id DESC";
        */
		
		 $query_sql = "SELECT  * From oc_order_accounts_report where 1";
		
		$query = $this->db->query($query_sql);
        $result_products =  $query->rows;
        return $result_products;
    }
	
	public function insertDB($date_from, $date_to) {
        $query_sql = "SELECT        oc_order.order_id as order_id,
                                    oc_order.date_added as date,
                                    CONCAT(oc_order.firstname,' ',oc_order.lastname) as customer_name,
                                    oc_order.currency_code as currency,
                                    oc_order.email as customer_email,
                                    oc_order.telephone as customer_phone,
                                    oc_order.shipping_address_1 as delivery_address,
                                    oc_order.shipping_city,
									 oc_order.shipping_zone,
                                    oc_order.shipping_country,
                                    oc_order.payment_address_1,
                                    oc_order.payment_city,
									oc_order.payment_zone,
                                    oc_order.payment_country,
                                    oc_order.order_type,
                                    oc_order.resale_text as customer_resale,
									oc_customer_group_description.name as customer_group,
                                    oc_order.total
                                    FROM oc_order 
									INNER JOIN oc_customer_group_description on oc_customer_group_description.customer_group_id = oc_order.customer_group_id
                                   	WHERE oc_order.order_status_id > 0 AND oc_order.date_added BETWEEN '$date_from' AND '$date_to' 
                                    ORDER BY oc_order.order_id ASC";
      
		$query = $this->db->query($query_sql);
        $result_orders =  $query->rows;
		
			foreach($result_orders as $order){ 

                $order_type=$this->db->query("SELECT is_pos,payment_method FROM `".DB_PREFIX."order` WHERE order_id='".(int)$order['order_id']."'");
                if($order_type->num_rows){
                   if($order_type->row['is_pos']==1){
                    $payment_type=$this->db->query("SELECT payment_type FROM ".DB_PREFIX."order_payment WHERE order_id='".(int)$order['order_id']."'");
                    if($payment_type->row['payment_type']){
                        $paymenttype=$payment_type->row['payment_type'];
                    }
                   }else{
                    $paymenttype=$order_type->row['payment_method'];
                   }
                }
            switch($order['order_type'])  
            {
                case '2'    :   $storename='Incoming'; break;
                case '1'    :   $storename='POS'; break;
                case '3'    :   $storename='Backorder'; break;
                default     :   $storename='Gempacked';
            }  

            $taxes_data = $this->getTotal($order['order_id'],'tax');
            $shipping_data = $this->getTotal($order['order_id'],'shipping');
            $total_paid = $this->getTotal($order['order_id'],'sub_total');
			$total_voucher_amount = $this->getTotal($order['order_id'],'voucher');
            $product_ids = $this->getTotalProducts($order['order_id']);
            $order_status = $this->getOrderStatus($order['order_id']);
            $final_price = 0;
//            $product_quantity = $this->
            foreach($product_ids as $product_id){
                 $final_price += $this->geProductCalculatedPriceWithouDiscount($product_id, $order['order_id']);
            }
            $final_data[] = $order;
			$order_id = $order['order_id'];
			$customer_name = $this->db->escape($order['customer_name']);
			$currency = $this->db->escape($order['currency']);
			$customer_email = $this->db->escape($order['customer_email']);
			$customer_phone = $this->db->escape($order['customer_phone']);
			
			$delivery_address = $this->db->escape($order['delivery_address']);
			$shipping_city = $this->db->escape($order['shipping_city']);
			$shipping_zone = $this->db->escape($order['shipping_zone']);
			$shipping_country = $this->db->escape($order['shipping_country']);
			
			$payment_address_1 = $this->db->escape($order['payment_address_1']);
			$payment_city = $this->db->escape($order['payment_city']);
			$payment_zone = $this->db->escape($order['payment_zone']);
			$customer_resale = $this->db->escape($order['customer_resale']);
			$payment_country = $this->db->escape($order['payment_country']);
			
			$customer_group = $this->db->escape($order['customer_group']);
			$total = $order['total'];
			
			
			//$total_product_cost = ($total_product_cost) ? $total_product_cost['value'] : 0; // this should be total_paid
            $total_voucher_amount = ($total_voucher_amount) ? $total_voucher_amount['value'] : 0;
			$date = date("Y-m-d", strtotime($order['date']));
            $total_tax = ($taxes_data) ? $taxes_data['value'] : 0;
            $total_shipping = ($shipping_data) ? $shipping_data['value'] : 0;
            //$total_paid = $final_price; // this should be total_product_cost
			$total_paid = ($total_paid) ? $total_paid['value'] : 0;
			$total_product_cost = $final_price;
            $order_status = $order_status;
            $item_count = $this->getItemCount($order_id);
            $refund_amount = $this->getRefundAmount($order_id);
			$sql = '';
			$sql = "INSERT INTO oc_order_accounts_report (`order_id`, `Date`, `Customer_Name`, `Currency`, `Customer_email`, `Customer_Phone`, `Delivery_Address`, `Delivery_City`, `Delivery_State`, `Delivery_Country`, `Payment_Address`, `Payment_City`, `Payment_State`, `Payment_Country`, `Customer_Resale`, `customer_group`, `Total_Paid`, `Total_Paid_Products`, `Voucher_Amount`, `Total_Tax`, `Total_Shipping`, `Total_Product_Cost`, `Order_Status`,`payment_method`,`store_name`,`item_count`,`refund_amount`) 
			VALUES ('$order_id', '$date', '$customer_name', '$currency', '$customer_email', '$customer_phone', '$delivery_address', '$shipping_city', '$shipping_zone', '$shipping_country', '$payment_address_1', '$payment_city', '$payment_zone', '$payment_country', '$customer_resale', '$customer_group', '$total', '$total_paid', '$total_voucher_amount', '$total_tax', '$total_shipping', '$total_product_cost', '$order_status','$paymenttype','$storename','$item_count','$refund_amount')";
			$this->db->query($sql); 
			
        }	
		
    }
	
    public function getItemCount($order_id)
    {
        $query = $this->db->query("SELECT COUNT(product_id) as total FROM oc_order_product WHERE order_id = $order_id");
        return $query->row ? $query->row['total'] : 1;
    }

    public function getRefundAmount($order_id)
    {
        $query = $this->db->query("SELECT SUM(refund_amount) as total FROM " . DB_PREFIX . "return_refund WHERE order_id = '" . (int)$order_id . "'");
        return $query->row ? $query->row['total'] : 0;
    }

    public function getTotal($order_id, $code){
        $query = $this->db->query("select value from oc_order_total where order_id = $order_id and code= '$code'");
        if($query->num_rows > 0)
       	 	return $query->row;
        else
        	return FALSE;
    }
    
    public function getPaidTotal($order_id){
        
    }
    public function getTotalProducts($order_id){
        $query = $this->db->query("SELECT product_id FROM oc_order_product WHERE order_id = $order_id");
        $product_ids = array();
       foreach($query->rows as $row){
           $product_ids[]= $row['product_id'];
       }
        return $product_ids;
    }
    
    public function geProductCalculatedPriceWithouDiscount($pid, $order_id){
        $opional_price = $this->getOptionPrice($pid);
        $main_price = $this->getMainPrice($pid);
        $final_price = $main_price + $opional_price;
        $store_discount = $this->db->query("select unique_price_discount from oc_product where product_id = $pid");
        if($store_discount->num_rows > 0){
            $store_discount_price = $store_discount->row['unique_price_discount'];
        }else{
            $store_discount_price = 1;
        }
        //($store_discount_price ==0 ? $store_discount_price = 1 : "");
		if( $store_discount_price == 0 )
		{
			$store_discount_price = 1;
		}
       
	    $final_price = $final_price * $store_discount_price;
        //get quanitity from db
        $quanities = $this->db->query("select quantity, "
                . "quantity_supplied from "
                . "oc_order_product "
                . "where order_id= $order_id AND product_id = $pid");
       
	    $quanities = (QUANTITY) ? $quanities->row['quantity'] : $quanities->row['quantity_supplied'] ;
	   $final_price = $final_price*$quanities;
        //return round($final_price, 2);
		return number_format((float)$final_price, 2, '.', '');
    }
      /**
     * Function getOptionPrice
     * @param type $pid
     * @return boolean
     */
    public function getOptionPrice($pid, $metal_type = 1) {
        $single = "SELECT
                " . DB_PREFIX . "product_option_value.price,
               " . DB_PREFIX . "option_value.market_price,
               (" . DB_PREFIX . "product_option_value.price*" . DB_PREFIX . "option_value.market_price ) as option_increment
               FROM
               " . DB_PREFIX . "option_description
               INNER JOIN " . DB_PREFIX . "option_value ON " . DB_PREFIX . "option_description.option_id = " . DB_PREFIX . "option_value.option_id
               INNER JOIN " . DB_PREFIX . "product_option_value ON " . DB_PREFIX . "product_option_value.option_id = " . DB_PREFIX . "option_value.option_id AND " . DB_PREFIX . "option_value.option_value_id = " . DB_PREFIX . "product_option_value.option_value_id
               WHERE
               " . DB_PREFIX . "product_option_value.product_id = $pid ";
        if ($metal_type) {
            $single.= "AND
               " . DB_PREFIX . "option_description.metal_type = 1";
        }

        $query = $this->db->query($single);
        if (!$metal_type) {
            return $query->row['price'];
        }
        if (isset($query->row['option_increment'])) {
            $calculatedPrice = $query->row['option_increment'];
        } else {
            if (isset($query->row['price'])) {
                $calculatedPrice = $query->row['price'];
            } else {
                $calculatedPrice = 0;
            }
        }
        return $calculatedPrice;
    }
    
     /**
     * getMainPrice
     * @param type $pid
     * @return type
     */
    public function getMainPrice($pid) {
        $main_price = $this->db->query("select " . DB_PREFIX . "product.price from " . DB_PREFIX . "product where " . DB_PREFIX . "product.product_id = $pid");
        if($main_price->num_rows > 0)
        return $main_price->row['price'];
        else
        return    0;
    }
    
    
    public function getOrderStatus($order_id){
        $sql = "select oc_order_status.name from oc_order_status
                LEFT JOIN oc_order on oc_order_status.order_status_id = oc_order.order_status_id
                WHERE oc_order.order_id =".$order_id;
        $order_statuss = $this->db->query($sql);
        return $order_statuss->row['name'];
    }
    

}

?>