<?php
class ModelApiOrder extends Model {
	//new modification for ajax orders in admin
	 public function getOrderByOrderProduct($order_id,$product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "' AND product_id = '" . (int)$product_id . "'");

		return $query->rows;
	}
	
	public function UpdateOrderShipping($data)
	{ 
		$order_id = $data['order_id'];
		if($data['shipping_method'] == 'free.free')
		{
			$title = $data['shipping_title'];
			$value = str_replace("$","",$data['shipping_value']);
			$this->db->query("UPDATE " . DB_PREFIX . "order_total SET title='".$title."',value='".$value."' WHERE order_id='".$order_id."' AND code='shipping'");
		} elseif ($data['shipping_method'] == 'instore.instore') {
			$this->db->query("UPDATE " . DB_PREFIX . "order_total SET title='Shipping',value=0 WHERE order_id='".$order_id."' AND code='shipping'");
			$this->db->query("UPDATE " . DB_PREFIX . "order SET shipping_code='instore.instore',shipping_method='Instore Shipping' WHERE order_id='".$order_id."'");
		} else {
			
			$shipping_method_text = $data['shipping_method_text']; //Flat Shipping Rate - $5.00
			if (strpos($shipping_method_text, ' - $') !== false) {
				$shipping_data = explode(" - $",$shipping_method_text);
				$title = $shipping_data[0];
				$value = $shipping_data[1];
				$this->db->query("UPDATE " . DB_PREFIX . "order_total SET title='".$title."',value='".$value."' WHERE order_id='".$order_id."' AND code='shipping'");
			}
			
		}
		
	}
	
	public function getOrderTotal($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order ASC");
		return $query->rows;
	}
	
	public function updateOrderProduct($order_id,$product_id,$quantity,$price,$total,$quantity_supplied)
	{
		$query = $this->db->query("UPDATE " . DB_PREFIX . "order_product SET price='".$price."',total='".$total."',quantity_supplied='".$quantity_supplied."',quantity='".$quantity."' WHERE order_id='".$order_id."' AND product_id='".$product_id."'");		
	}
	
	public function UpdateOrderProducts($order_id,$product_data)
	{  
		if(is_numeric($product_data['product_qty_supplied']))
		{
			$product_data['product_qty_supplied'] = floatval($product_data['product_qty_supplied']);
			$total = ((float)$product_data['product_actual_price'] * (float)$product_data['product_qty_supplied']);
		} else {
			$total = ((float)$product_data['product_price'] * (float)$product_data['product_qty']);
		}

		$vendor_query = "";
		if(!empty($product_data['default_vendor_unit']))
		{
			$vendor_query .= "`default_vendor_unit`='".$product_data['default_vendor_unit']."',";
		}

		if(!empty($product_data['updated_vendor_unit']))
		{
			$vendor_query .= "`updated_vendor_unit`='".$product_data['updated_vendor_unit']."',";
		}

		if(isset($product_data['remark']))
		{
			$vendor_query .= "`remark`='".$product_data['remark']."',";
		}
		if(is_numeric($product_data['product_qty_supplied']))
		{
			$qry = "UPDATE " . DB_PREFIX . "order_product SET ".
			"`name`='".$product_data['product_name']."',".
			"`model`='".$product_data['product_model']."',".
			"`quantity`='".$product_data['product_qty']."',".
			"`price`='".$product_data['product_price']."',".
			"`total`='".$total."',". $vendor_query .
			"`quantity_supplied`='".$product_data['product_qty_supplied']."'".
			" WHERE `order_id`='".$order_id."' AND `product_id` = '".$product_data['product_id']."'";
		} else {
			$qry = "UPDATE " . DB_PREFIX . "order_product SET ".
			"`name`='".$product_data['product_name']."',".
			"`model`='".$product_data['product_model']."',".
			"`quantity`='".$product_data['product_qty']."',".
			"`price`='".$product_data['product_price']."',".
			"`total`='".$total."',". $vendor_query .
			"`quantity_supplied`= NULL".
			" WHERE `order_id`='".$order_id."' AND `product_id` = '".$product_data['product_id']."'";
		}
		
		//echo $qry; exit;
		$query = $this->db->query(
			$qry
		);
	}
	
	public function getConvertedPrice($unit_conversion_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "unit_conversion_product WHERE unit_conversion_product_id = '" . (int)$unit_conversion_id . "'");

		return $query->row ? $query->row['convert_price'] : 1;
	}
	
	public function UpdateOrderTotalData($order_id)
	{
		$query = $this->db->query("SELECT `price`,`quantity`,`quantity_supplied`,unit_conversion_values FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
		$order_products = $query->rows;
		$sub_total = 0.00;
		foreach( $order_products as $order_product )
		{
			
			if(is_numeric($order_product['quantity_supplied']))
			{
				$order_product['quantity_supplied'] = floatval($order_product['quantity_supplied']);
				$convert_price = $order_product['unit_conversion_values'] > 0 ? $this->getConvertedPrice($order_product['unit_conversion_values']) : 1;
				$sub_total += ($order_product['price']/$convert_price) * $order_product['quantity_supplied'];
			} else {
				$sub_total += ($order_product['price']) * $order_product['quantity'];
			}
		}
		
		$this->db->query("UPDATE " . DB_PREFIX . "order_total SET value='".(float)$sub_total."' WHERE order_id='". (int)$order_id."' AND code='sub_total'");
		
		$query2 = $this->db->query("SELECT SUM(`value`) as grand_total FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' AND `code` <> 'total' GROUP BY `order_id`");
		
		$grand_total = $query2->row['grand_total'];
			
		$this->db->query("UPDATE " . DB_PREFIX . "order_total SET value='".(float)$grand_total."' WHERE order_id='". (int)$order_id."' AND code='total'");
		
		$this->db->query("UPDATE " . DB_PREFIX . "order SET total='".(float)$grand_total."' WHERE order_id='". (int)$order_id."'");
	}
	
	public function getOrderGrandTotal($order_id)
	{
		$query = $this->db->query("SELECT `value` FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' AND `code` = 'total' LIMIT 1");
		
		return $query->row['value']; 
		
	}


	public function getCustomerResaleNumber($customer_id)
	{
		$query = $this->db->query("SELECT `resale_number` FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");

		if ( $query->row )
		{
			return !empty($query->row['resale_number']) ? true : false;
		}
		return false;
	}

	public function getCustomerZone($shipping_address_id)
	{
		$query = $this->db->query("SELECT `zone_id` FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)$shipping_address_id . "'");

		if ( $query->row )
		{
			return !empty($query->row['zone_id']) ? $query->row['zone_id'] : 0;
		}
		return 0;
	}

	public function addSalesTax($order_id)
	{
		$query = $this->db->query("SELECT `value` FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' AND `code` = 'sub_total' LIMIT 1");
		
		$sub_total =  $query->row['value']; 

		$tax = $sub_total * 0.095;

		$query2 = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' AND `code` = 'tax' LIMIT 1");
		
		$exists =  $query2->row ? true : false; 

		if ( $exists )
		{
			$this->db->query("UPDATE " . DB_PREFIX . "order_total SET value='".(float)$tax."' WHERE order_id='". (int)$order_id."' AND code='tax'");
		} else {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET value='".(float)$tax."',order_id='". (int)$order_id."',code='tax',title='Sales-taxes US-CA 9.5%',sort_order=5");
		}	
		
	}

	public function removeSalesTax($order_id)
	{
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id='". (int)$order_id."' AND code='tax'");
	}

	
}