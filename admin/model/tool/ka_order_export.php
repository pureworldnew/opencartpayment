<?php
/*
  Project: CSV Product Export
  Author : karapuz <support@ka-station.com>

  Version: 4 ($Revision: 42 $)

*/

class ModelToolKaOrderExport extends KaModel {

	public function export($data)
	{
		$manufacturers = array();
		if( $data['delimiter'] == 's' )
		{
			$delimiter = ";";
		} else {
			$delimiter = ",";	
		}
		$sql = "SELECT order_id, store_name, date_added, order_status_id FROM `" . DB_PREFIX . "order` WHERE order_type = 2";

		if ( isset($data['products_from_manufacturers']) && $data['products_from_manufacturers'] == 'selected' ) {
			foreach ($data['manufacturer_ids'] as $manufacturer_id) {
				$manufacturers[] = (int)$manufacturer_id;
			}	
		}

		if (!empty($data['filter_date_from'])) {
			$sql .= " AND DATE(date_added) >= DATE('" . $this->db->escape($data['filter_date_from']) . "')";
		}

		if (!empty($data['filter_date_to'])) {
			$sql .= " AND DATE(date_added) <= DATE('" . $this->db->escape($data['filter_date_to']) . "')";
		}

		$query = $this->db->query($sql);

		$orders = $query->rows; 
		$file = time() . '_incoming_orders.csv';
		$fp = fopen(DIR_DOWNLOAD . $file, 'w');
		$heading = array("Order Id","Date","Store","Order Status","Product Id", "Model Number", "MPN", "Product Name", "Manufacturer", "Quantity", "Ordered Vendor Unit", "Quantity Received", "Received Vendor Unit", "Price", "Location", "Total","Remark");
		fputcsv($fp, $heading, $delimiter);
		if ( !empty( $orders ) )
		{
			foreach ( $orders as $order )
			{
				$order_products = $this->getOrderProducts($order['order_id']);

				if ( $order_products )
				{
					foreach ( $order_products as $order_product ) 
					{
						if ( empty($manufacturers) )
						{
							$manufacturer 	= $this->getProductManufacturer($order_product['product_id']);
							$mpn 			= $this->getProductMpn($order_product['product_id']);
							$location 		= $this->getProductLocation($order_product['product_id']);
							$order_status 	= $this->getOrderStatus($order['order_status_id']);
							$order_date 	= date("Y-m-d", strtotime($order['date_added']));

							$order_product['quantity_supplied'] = !empty($order_product['quantity_supplied']) ? $order_product['quantity_supplied'] : 0;

							$csv_data = array($order['order_id'], $order_date, $order['store_name'], $order_status, $order_product['product_id'], $order_product['model'], $mpn, $order_product['name'], $manufacturer, $order_product['quantity'], $order_product['default_vendor_unit'], $order_product['quantity_supplied'], $order_product['updated_vendor_unit'], $order_product['price'], $location, $order_product['total'], $order_product['remark']);

							fputcsv($fp, $csv_data, $delimiter);
						} else {

							if ( in_array( $this->getProductManufacturerID($order_product['product_id']), $manufacturers ) )
							{
								$manufacturer 	= $this->getProductManufacturer($order_product['product_id']);
								$mpn 			= $this->getProductMpn($order_product['product_id']);
								$location 		= $this->getProductLocation($order_product['product_id']);
								$order_status 	= $this->getOrderStatus($order['order_status_id']);
								$order_date 	= date("Y-m-d", strtotime($order['date_added']));

								$order_product['quantity_supplied'] = !empty($order_product['quantity_supplied']) ? $order_product['quantity_supplied'] : 0;

								$csv_data = array($order['order_id'], $order_date, $order['store_name'], $order_status, $order_product['product_id'], $order_product['model'], $mpn, $order_product['name'], $manufacturer, $order_product['quantity'], $order_product['default_vendor_unit'], $order_product['quantity_supplied'], $order_product['updated_vendor_unit'], $order_product['price'], $location, $order_product['total'], $order_product['remark']);

								fputcsv($fp, $csv_data, $delimiter);
							}

						}
						
					}
				}

			}
		}

		fclose($fp);

		$csv_file = DIR_DOWNLOAD . $file;

		if (!headers_sent()) {
			if (file_exists($csv_file)) {
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header("Content-Disposition: attachment; filename=\"$file\"");
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				header('Content-Length: ' . filesize($csv_file));
				
				readfile($csv_file, 'rb');
				
				exit;
			} else {
				exit('Error: Could not find file ' . $csv_file . '!');
			}
		}
	}


	public function exportBackOrders($data)
	{
		$manufacturers = array();
		if( $data['delimiter'] == 's' )
		{
			$delimiter = ";";
		} else {
			$delimiter = ",";	
		}
		$sql = "SELECT order_id, store_name, date_added, order_status_id, customer_id, firstname, lastname, email, telephone, payment_company FROM `" . DB_PREFIX . "order` WHERE order_type = 3";

		if ( isset($data['products_from_manufacturers']) && $data['products_from_manufacturers'] == 'selected' ) {
			foreach ($data['manufacturer_ids'] as $manufacturer_id) {
				$manufacturers[] = (int)$manufacturer_id;
			}	
		}

		if (!empty($data['filter_date_from'])) {
			$sql .= " AND DATE(date_added) >= DATE('" . $this->db->escape($data['filter_date_from']) . "')";
		}

		if (!empty($data['filter_date_to'])) {
			$sql .= " AND DATE(date_added) <= DATE('" . $this->db->escape($data['filter_date_to']) . "')";
		}

		$query = $this->db->query($sql);

		$orders = $query->rows; 
		$file = time() . '_backorders.csv';
		$fp = fopen(DIR_DOWNLOAD . $file, 'w');
		$heading = array("Order Id","Date","Store","Customer ID","First Name","Last Name","Company","Email","Phone","Order Status","Product Id", "Model Number", "MPN", "Product Name", "Manufacturer", "Quantity","Price", "Location", "Total","Order Comments");
		fputcsv($fp, $heading, $delimiter);
		if ( !empty( $orders ) )
		{
			foreach ( $orders as $order )
			{
				$order_products = $this->getOrderProducts($order['order_id']);

				if ( $order_products )
				{
					foreach ( $order_products as $order_product ) 
					{
						if ( empty($manufacturers) ) 
						{
							$manufacturer 	= $this->getProductManufacturer($order_product['product_id']);
							$mpn 			= $this->getProductMpn($order_product['product_id']);
							$location 		= $this->getProductMainLocation($order_product['product_id']);
							$order_status 	= $this->getOrderStatus($order['order_status_id']);
							$order_date 	= date("Y-m-d", strtotime($order['date_added']));

							$order_product['quantity_supplied'] = !empty($order_product['quantity_supplied']) ? $order_product['quantity_supplied'] : 0;

							$csv_data = array($order['order_id'], $order_date, "Backorders",$order['customer_id'],$order['firstname'],$order['lastname'],$order['payment_company'],$order['email'],$order['telephone'],$order_status, $order_product['product_id'], $order_product['model'], $mpn, $order_product['name'], $manufacturer, $order_product['quantity'],$order_product['price'], $location, $order_product['total'], $order_product['remark']);

							fputcsv($fp, $csv_data, $delimiter);
						} else {

							if ( in_array( $this->getProductManufacturerID($order_product['product_id']), $manufacturers ) )
							{
								$manufacturer 	= $this->getProductManufacturer($order_product['product_id']);
								$mpn 			= $this->getProductMpn($order_product['product_id']);
								$location 		= $this->getProductMainLocation($order_product['product_id']);
								$order_status 	= $this->getOrderStatus($order['order_status_id']);
								$order_date 	= date("Y-m-d", strtotime($order['date_added']));

								$order_product['quantity_supplied'] = !empty($order_product['quantity_supplied']) ? $order_product['quantity_supplied'] : 0;

								$csv_data = array($order['order_id'], $order_date,"Backorders",$order['customer_id'],$order['firstname'],$order['lastname'],$order['payment_company'],$order['email'],$order['telephone'],$order_status, $order_product['product_id'], $order_product['model'], $mpn, $order_product['name'], $manufacturer, $order_product['quantity'],$order_product['price'], $location, $order_product['total'], $order_product['remark']); 

								fputcsv($fp, $csv_data, $delimiter);
							}

						}
						
					}
				}

			}
		}

		fclose($fp);

		$csv_file = DIR_DOWNLOAD . $file;

		if (!headers_sent()) {
			if (file_exists($csv_file)) {
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header("Content-Disposition: attachment; filename=\"$file\"");
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				header('Content-Length: ' . filesize($csv_file));
				
				readfile($csv_file, 'rb');
				
				exit;
			} else {
				exit('Error: Could not find file ' . $csv_file . '!');  
			}
		}
	}

	public function getOrderStatus($order_status_id) {
		$query = $this->db->query("SELECT name FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row ? $query->row['name'] : "";
	}

	public function getOrderProducts($order_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

		return $query->rows;
	}

	public function getProductManufacturer($product_id)
	{
		$query = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");

		$manufacturer_id = $query->row ? $query->row['manufacturer_id'] : 0;

		if($manufacturer_id)
		{
			$query = $this->db->query("SELECT name FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

			$name = $query->row ? $query->row['name'] : "";

			return $name;

		} else {
			return "";
		}
	}

	public function getProductManufacturerID($product_id)
	{
		$query = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");

		$manufacturer_id = $query->row ? $query->row['manufacturer_id'] : 0;
		
		return $manufacturer_id;
	}

	public function getProductMpn($product_id)
	{
		$query = $this->db->query("SELECT mpn FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");

		return $query->row ? $query->row['mpn'] : "";
	}

	public function getProductMainLocation($product_id)
	{
		$query = $this->db->query("SELECT location FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");

		return $query->row ? $query->row['location'] : "";
	}

	public function getProductLocation($product_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_location_quantity WHERE product_id = '" . $product_id . "'");
		$locations = $query->rows;
		if($locations)
		{
			$return_location = "";

			foreach ( $locations as $location )
			{
				$return_location .= $location['location_id'] . ":" . $location['location_quantity'] . "/";
			}

			$return_location = rtrim($return_location,"/");
			return $return_location;

		} else {
			return "";
		}
	}
}
?>