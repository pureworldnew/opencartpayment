<?php
class ModelAccountActivity extends Model {
	public function addActivity($key, $data) {
		if (isset($data['customer_id'])) {
			$customer_id = $data['customer_id'];
		} else {
			$customer_id = 0;
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_activity` SET `customer_id` = '" . (int)$customer_id . "', `key` = '" . $this->db->escape($key) . "', `data` = '" . $this->db->escape(json_encode($data)) . "', `ip` = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', `date_added` = NOW()");
	}
	
	public function addAuthorizationAmount($order_id)
	{
		$this->db->query("UPDATE `".DB_PREFIX."order` SET `authorization_amount` = `total` WHERE order_id='".$order_id."'");
	}
	
	public function getConvertedValue($unit_conversion_product_id)
	{
		$query = $this->db->query("SELECT convert_price FROM " . DB_PREFIX . "unit_conversion_product WHERE unit_conversion_product_id = '" . (int)$unit_conversion_product_id . "'");
		return $query->row ? $query->row['convert_price'] : 1;
	}
	public function updateSoldQuantity($order_id)
	{
		$query = $this->db->query("SELECT order_id, product_id, quantity, unit_conversion_values, sold_quantity FROM ".DB_PREFIX."order_product WHERE order_id ='".$order_id."'");
		foreach ($query->rows as $order_product ) {
			if ( empty($order_product['unit_conversion_values']) )
			{
				$this->db->query("UPDATE " . DB_PREFIX . "order_product SET sold_quantity = quantity WHERE product_id = '" . (int)$order_product['product_id'] . "' AND order_id = '" . (int)$order_product['order_id'] . "'");
			} else {
				$converted_price = $this->getConvertedValue($order_product['unit_conversion_values']);
				$this->db->query("UPDATE " . DB_PREFIX . "order_product SET sold_quantity = quantity * '" . (float)$converted_price . "' WHERE product_id = '" . (int)$order_product['product_id'] . "' AND order_id = '" . (int)$order_product['order_id'] . "'");
			}
			$this->updateProductSoldQuantity($order_product['product_id']);
		}
	}

	public function getCompletedOrderStatusIds() 
	{
		$key = "config_complete_status";
		$query = $this->db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE `key` LIKE '" . $key . "'");
		$result = $query->row['value'];
		$result = str_replace("[","", $result);
		$result = str_replace("]","", $result);
		return (string)$result;
	}

	public function updateProductSoldQuantity($product_id)
	{
		$query = $this->db->query("SELECT op.`product_id`, sum(op.quantity_supplied) as sold FROM ".DB_PREFIX."order_product op INNER JOIN ".DB_PREFIX."order o ON op.order_id = o.order_id WHERE op.`product_id` ='".$product_id."' AND o.order_status_id IN(" . $this->getCompletedOrderStatusIds() . ")");

		$sold = $query->row ? $query->row['sold'] : 0;
		if ( $sold )
		{
		  $this->db->query("UPDATE `".DB_PREFIX."product` SET `sold` = '".$sold."' WHERE product_id='".$product_id."'");
		}
	}

	public function addCombineShipping($customer_id, $eligible_to_combine, $time_frame, $selected_order_statuses)
	{ 
		$time_frame = "-" . $time_frame . " day";
		$time_frame_date = date("Y-m-d", strtotime($time_frame));
		if ( !empty($customer_id) && !empty($eligible_to_combine) )
		{
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET combine_shipping = 1 WHERE customer_id = '" . (int)$customer_id . "' AND order_status_id IN (" . $selected_order_statuses .") AND date_added >= DATE('" . $time_frame_date . "')");
		}
	}
	
	public function subtractStock($order_id)
	{
		$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
		foreach ( $order_product_query->rows as $order_product ) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND subtract = '1'");
			$this->db->query("UPDATE " . DB_PREFIX . "product SET stock_status_id = 5 WHERE product_id = '" . (int)$order_product['product_id'] . "' AND subtract = '1' AND stock_status_id = 7 AND quantity < 1");
			
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE product_id = '" . (int) $order_product['product_id'] . "'");
			if ($query->num_rows) {
				$product = $query->row;
				$quantity  = $product['quantity'];
				$ordered_quantity = (int)$order_product['quantity'];
				if( $quantity + $ordered_quantity > 0 && $quantity < 1 )
				{
					$this->db->query("UPDATE " . DB_PREFIX . "product SET date_sold_out = '" . date("Y-m-d") . "' WHERE product_id = '" . (int)$order_product['product_id'] . "' AND stock_status_id = 5 AND quantity < 1");
					
				}
				if( $quantity + $ordered_quantity > 0 && $quantity < 1 && $product['estimate_deliver_time'] > 0 && $product['date_ordered'] != '0000-00-00' )
				{
					$days = $product['estimate_deliver_time'];
					$frontend_date_available = date('Y-m-d', strtotime($product['date_ordered']. " + {$days} days"));
					$this->db->query("UPDATE " . DB_PREFIX . "product SET frontend_date_available = '" . $frontend_date_available . "' WHERE product_id = '" . (int)$order_product['product_id'] . "' AND stock_status_id = 5 AND quantity < 1");
				}
			}
		}
	}
}