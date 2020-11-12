<?php
class ModelToolKaOrderImport extends KaModel {

	/**
	 * Process CSV file for Import
	 */
	public function processImport($file, $delimiter)
	{
		// First truncate incoming_order_temp_data table
		$this->truncateTempTable(); 

		// Import CSV to incoming_order_temp_data table
		$this->importDataToTempTable($file, $delimiter);

		// Get Order list from CSV
		$orders = $this->getImportedOrders();
		
		foreach ( $orders as $order )
		{ 
			$order_id = $order['order_id'];

			// Get product list of order
			$products = $this->getIncomingOrderedProducts($order_id);
			// Add / Edit Manufacturer

			$this->updateManufacturer($products);

			//  If existing order
			if( $order_id > 0 )
			{
				// Update order product from CSV
				$this->importOrderProducts( $order_id, $products );

				// Update Order Total
				$this->updateOrderTotal($order_id);

			} else {
				// New Incoming Order
				$this->createIncomingOrders($products);
			}
				
		}

	}


	public function processBackOrderImport($file, $delimiter)
	{
		// First truncate backorder_temp_data table
		$this->truncateBackorderTempTable(); 

		// Import CSV to backorder_temp_data table
		$this->importBackorderDataToTempTable($file, $delimiter);

		// Get BackOrder list from CSV
		$orders = $this->getImportedBackOrders();
		 
		foreach ( $orders as $order )
		{ 
			$order_id = $order['order_id'];

			// Get product list of order
			$products = $this->getBackOrderedProducts($order_id);

			// Add / Edit Manufacturer

			$this->updateManufacturer($products);

			//  If existing order
			if( $order_id > 0 )
			{
				// Update order product from CSV
				$this->importOrderProducts( $order_id, $products );

				// Update Order Total
				$this->updateOrderTotal($order_id);

			} else {
				// New Back Order
				$this->createBackOrders($products);
			}
				
		}

	}

	
	public function updateManufacturer($products)
	{
		if ( $products )
		{
			foreach( $products as $product )
			{
				$product_id 		= $product['product_id'];
				$manufacturer_name 	= trim($product['manufacturer']);
				if ( !empty($manufacturer_name) && $this->productHasNoManufacturer($product_id) )
				{
					$this->assignManufacturerToProduct($product_id, $manufacturer_name);
				}
			}
		}
	}

	public function productHasNoManufacturer($product_id)
	{
		$query = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "product WHERE product_id ='".$product_id."'");

		$manufacturer_id = $query->row ? $query->row['manufacturer_id'] : 0;

		if ( $manufacturer_id )
		{
			return false;
		} else {
			return true;
		}

	}

	public function assignManufacturerToProduct($product_id, $manufacturer_name)
	{
		$query = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer WHERE name = '" . $this->db->escape($manufacturer_name) . "'");

		$manufacturer_id = $query->row ? $query->row['manufacturer_id'] : 0;
		if($manufacturer_id)
		{
			$this->db->query("UPDATE ".DB_PREFIX."product SET manufacturer_id = '".$manufacturer_id."' WHERE product_id = '".$product_id."'");
		} else {
			$manufacturer_id = $this->createNewManufacturer($manufacturer_name);
			$this->db->query("UPDATE ".DB_PREFIX."product SET manufacturer_id = '".$manufacturer_id."' WHERE product_id = '".$product_id."'");

		}
	}

	public function createNewManufacturer($manufacturer_name)
	{
		$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer SET name = '" . $this->db->escape($manufacturer_name) . "'");
		$manufacturer_id = $this->db->getLastId();
		$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '" . (int)$manufacturer_id . "', store_id = '0'");
		return $manufacturer_id;
	}

	/**
	 * Create new incoming orders
	 */
	public function createIncomingOrders($products)
	{
		
		if ( $products )
		{ 
			// create new incoming order
			$new_order_id =	$this->createIncomingOrderRow( array(), 0);
			$this->addProductsToIncomingOrder( $new_order_id, $products );
		}
	}

	public function createBackOrders($products)
	{
 
		if ( $products )
		{
			foreach( $products as $product) 
			{
				// create New Back Orders
				$this->createNewBackOrder($product);  
			}
			
		}
	}

	public function createIncomingProduct($data)
	{
		$product = array(); 
		$data['quantity'] = !empty($data['quantity']) ? $data['quantity'] : 1;
		$this->db->query("INSERT INTO `" . DB_PREFIX . "product` SET model = '" . $this->db->escape($data['model']) . "', sku = '', upc = '', ean = '', jan = '', isbn = '', mpn = '', location = '', quantity = '" . (int)$data['quantity'] . "', minimum = '1', subtract = '0', stock_status_id = '7', date_available = NOW() - INTERVAL 1 DAY, manufacturer_id = '0', shipping = '" . (isset($data['shipping']) ? (int)$data['shipping'] : '0') . "', price = '" . (float)$data['price'] . "', points = '0', weight = '0', weight_class_id = '0', length = '0', width = '0', height = '0', length_class_id = '0', status = '0', pos_status = '0', tax_class_id = '0', sort_order = '200', quick_sale = '3', date_added = NOW()");

		$product_id = $this->db->getLastId();

		$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$this->config->get('config_language_id') . "', name = '" . $this->db->escape($data['name']) . "', meta_keyword = '', meta_description = '', description = '', tag = ''");

		$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '0'");

		if( !empty($data['manufacturer']) )
		{
			$product[] = array('product_id' => $product_id, 'manufacturer' => $data['manufacturer']);
			$this->updateManufacturer($product);
		}
		
		return $product_id;
	}

	public function createBackOrderProduct($data)
	{
		$product = array(); 
		$data['quantity'] = !empty($data['quantity']) ? $data['quantity'] : 1;
		$this->db->query("INSERT INTO `" . DB_PREFIX . "product` SET model = '" . $this->db->escape($data['model']) . "', sku = '', upc = '', ean = '', jan = '', isbn = '', mpn = '" . $this->db->escape($data['mpn']) . "', location = '', quantity = '" . (int)$data['quantity'] . "', minimum = '1', subtract = '0', stock_status_id = '7', date_available = NOW() - INTERVAL 1 DAY, manufacturer_id = '0', shipping = '" . (isset($data['shipping']) ? (int)$data['shipping'] : '0') . "', price = '" . (float)$data['price'] . "', points = '0', weight = '0', weight_class_id = '0', length = '0', width = '0', height = '0', length_class_id = '0', status = '0', pos_status = '0', tax_class_id = '0', sort_order = '200', quick_sale = '3', date_added = NOW()");

		$product_id = $this->db->getLastId();

		$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$this->config->get('config_language_id') . "', name = '" . $this->db->escape($data['name']) . "', meta_keyword = '', meta_description = '', description = '', tag = ''");

		$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '0'");

		if( !empty($data['manufacturer']) )
		{
			$product[] = array('product_id' => $product_id, 'manufacturer' => $data['manufacturer']);
			$this->updateManufacturer($product);
		}
		
		return $product_id;
	}

	/**
	 * Fill empty incoming order with products
	 */
	public function addProductsToIncomingOrder( $order_id, $products )
	{
		if ( $products )
		{
			$totalprice = 0;
			foreach ( $products as $product )
			{
				if ( $product['quantity'] > 0 )
				{
					$product_data = $this->getProductData($product['model']);

					if ( empty($product['product_id']) && empty($product_data) && !empty($product['model']) )
					{
						$this->createIncomingProduct($product);
						$product_data = $this->getProductData($product['model']);
					}

					$product['product_id'] = !empty($product['product_id']) ? $product['product_id'] : !empty($product_data) ? $product_data['product_id'] : 0;

					$product['name'] = !empty($product['name']) ? $product['name'] : !empty($product_data) ? $product_data['name'] : "";
					
					$product['price'] = !empty($product['price']) ? $product['price'] : !empty($product_data) ? $product_data['price'] : 0;

					$product['quantity'] = !empty($product['quantity']) ? $product['quantity'] : 1;

					$product['quantity_received'] = !empty($product['quantity_received']) ? $product['quantity_received'] : 0;
					$product['default_vendor_unit'] = !empty($product['default_vendor_unit']) ? $product['default_vendor_unit'] : "";
					$product['updated_vendor_unit'] = !empty($product['updated_vendor_unit']) ? $product['updated_vendor_unit'] : "";

					$this->db->query("INSERT INTO ".DB_PREFIX."order_product SET order_id='".$order_id."',product_id='".$product['product_id']."' ,name='".$this->db->escape($product['name'])."',model='".$this->db->escape($product['model'])."',price='".$this->db->escape($product['price'])."',quantity='".$product['quantity']."', default_vendor_unit='".$this->db->escape($product['default_vendor_unit'])."', quantity_supplied='".$product['quantity_received']."', updated_vendor_unit='".$this->db->escape($product['updated_vendor_unit'])."', total='".$product['price'] * $product['quantity']."', remark = '" . $this->db->escape($product['remark']) . "'");

					$totalprice += $product['price'] * $product['quantity'];
					$date_ordered = date("Y-m-d");
					$this->db->query("UPDATE ".DB_PREFIX."product SET incoming_request='1', 
					date_ordered = '". $date_ordered ."' WHERE product_id='".$product['product_id']."'");
					$estimate_deliver_time = $this->getProductEstimateDeliveryTime($product['product_id']);
					if($estimate_deliver_time > 0)
					{
						$date_available = date('Y-m-d', strtotime($date_ordered. " + {$estimate_deliver_time} days"));
						$this->db->query("UPDATE " . DB_PREFIX . "product SET frontend_date_available = '" . $date_available . "', stock_status_id = 5 WHERE product_id = '" . (int)$product['product_id'] . "' AND quantity < 1");
					}
				}

			}

			$this->db->query("UPDATE ".DB_PREFIX."order set total='".$totalprice."', order_type='2' WHERE order_id='".$order_id."' ");

			$comment = "New incoming manufacturer order";
			$this->db->query("INSERT INTO ".DB_PREFIX."order_history SET order_id='".$order_id."',order_status_id='1',comment='".$comment."',date_added=NOW() ");

			$this->db->query("INSERT INTO ".DB_PREFIX."order_total SET order_id='".$order_id."',code='sub_total',title='Sub Total',text='$".$totalprice."',value='".$totalprice."',sort_order='1' ");

			$this->db->query("INSERT INTO ".DB_PREFIX."order_total SET order_id='".$order_id."',code='total',title='Total',text='$".$totalprice."',value='".$totalprice."',sort_order='2' ");
		}
	}

	public function getProductEstimateDeliveryTime($product_id)
	{
		$query = $this->db->query("SELECT estimate_deliver_time FROM ". DB_PREFIX ."product WHERE product_id = '". $product_id ."'");
		return $query->row ? $query->row['estimate_deliver_time'] : 0;
	}

	/**
	 * Create Empty Incoming Order
	 */
	public function createIncomingOrderRow($request,$manufacturer_id)
	{

		if($_SERVER['SERVER_NAME']=='localhost'){
			$store_url="http://localhost/gempacked/";
		}else{
			if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') { 
                $store_url="https://".$_SERVER['SERVER_NAME']."/";
			} else{
				$store_url="http://".$_SERVER['SERVER_NAME']."/";
			}
			
		}
      
      	$customer_info = $this->getCustomerByEmail('bellausa@yahoo.com');

		$this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET invoice_prefix = '', store_id = '".$customer_info['store_id']."', store_name = 'Gempacked', store_url = '".$store_url."', customer_id = '".$customer_info['customer_id']."', customer_group_id = '".$customer_info['customer_group_id']."', firstname = '".$customer_info['firstname']."', lastname = '".$customer_info['lastname']."', email = '".$customer_info['email']."', telephone = '".$customer_info['telephone']."', fax = '".$customer_info['fax']."', custom_field = '', payment_firstname = '".$customer_info['firstname']."', payment_lastname = '".$customer_info['lastname']."', payment_company = '', payment_address_1 = '".$customer_info['address_1']."', payment_address_2 = '".$customer_info['address_2']."', payment_city = '".$customer_info['city']."', payment_postcode = '".$customer_info['postcode']."', payment_country = '', payment_country_id = '".$customer_info['country_id']."', payment_zone = '', payment_zone_id = '".$customer_info['zone_id']."', payment_address_format = '', payment_custom_field = '', payment_method = 'Bill Me Later', payment_code = 'cheque', shipping_firstname = '".$customer_info['firstname']."', shipping_lastname = '".$customer_info['lastname']."', shipping_company = '".$customer_info['company']."', shipping_address_1 = '".$customer_info['address_1']."', shipping_address_2 = '".$customer_info['address_2']."', shipping_city = '".$customer_info['city']."', shipping_postcode = '".$customer_info['postcode']."', shipping_country = '', shipping_country_id = '".$customer_info['country_id']."', shipping_zone = '', shipping_zone_id = '".$customer_info['zone_id']."', shipping_address_format = '', shipping_custom_field = '', shipping_method = 'In-Store Pick up', shipping_code = 'xshippingpro.xshippingpro2', comment = '', total = '', affiliate_id = '',order_status_id='1', commission = '', marketing_id = '', tracking = '', language_id = '', currency_id = '',currency_value='1', currency_code = 'USD', manufacturer_id = '".$manufacturer_id."', date_added = NOW(), date_modified = NOW()");

		$order_id = $this->db->getLastId();

        return $order_id;

	}

	public function createNewCustomer($data)
	{
		$data['password'] = "123abc123";
		if( empty($data['email']) && !empty($data['phone']) )
		{
			$data['email'] = $data['phone'] . "@gempacked.com";
		}
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['phone']) . "', salt = '" . $this->db->escape($salt = token(9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', status = 1, approved = 1, date_added = NOW()");

		$customer_id = $this->db->getLastId();

		$this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int)$customer_id . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company = '" . $this->db->escape($data['company']) . "'");

		$address_id = $this->db->getLastId();

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");

		return $customer_id;
	}

	public function createNewBackOrder($product)
	{
			$product['customer_id'] = !empty($product['customer_id']) ? intval($product['customer_id']) : 0;
			if( $product['customer_id'] > 0 )
			{
					$customer_info = $this->getCustomerByID($product['customer_id']); // First check customer by id
			}
			if( empty($customer_info) )
			{
					$customer_info = $this->getCustomerByEmail($product['email']); // Then check by email
			}

			if( empty($customer_info) && !empty($product['phone']) )
			{
					$customer_info = $this->getCustomerByPhone($product['phone']); // atlast check by phone 
			}
		  
		  if( empty($customer_info) && ( $product['customer_id'] > 0 || !empty($product['email']) || !empty($product['phone'])) )
		  {
			  $customer_id   = $this->createNewCustomer($product);
			  $customer_info = $this->getCustomerByID($customer_id); 
		  }

		if( empty($customer_info) )
		{
			$customer_info = array(
					'store_id' 	=> 0,
					'firstname' => 'In Store',
					'lastname' 	=> 'Guest',
					'email' 		=> 'customer@instore.com',
					'telephone' => '1600',
					'customer_group_id' => 2,
					'customer_id' => 0,
					'fax' => '',
					'address_1' => '607 S. Hill Street',
					'address_2' => 'Suite AR6',
					'city' => 'Los Angeles',
					'postcode' => '90014',
					'country_id' => 223,
					'zone_id' => 3624
			);
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET invoice_prefix = '', store_id = '".$customer_info['store_id']."', store_name = 'Gempacked', customer_id = '".$customer_info['customer_id']."', customer_group_id = '".$customer_info['customer_group_id']."', firstname = '".$customer_info['firstname']."', lastname = '".$customer_info['lastname']."', email = '".$customer_info['email']."', telephone = '".$customer_info['telephone']."', fax = '".$customer_info['fax']."', custom_field = '', payment_firstname = '".$customer_info['firstname']."', payment_lastname = '".$customer_info['lastname']."', payment_company = '', payment_address_1 = '".$customer_info['address_1']."', payment_address_2 = '".$customer_info['address_2']."', payment_city = '".$customer_info['city']."', payment_postcode = '".$customer_info['postcode']."', payment_country = '', payment_country_id = '".$customer_info['country_id']."', payment_zone = '', payment_zone_id = '".$customer_info['zone_id']."', payment_address_format = '', payment_custom_field = '', payment_method = 'Bill Me Later', payment_code = 'cheque', shipping_firstname = '".$customer_info['firstname']."', shipping_lastname = '".$customer_info['lastname']."', shipping_company = '".$customer_info['company']."', shipping_address_1 = '".$customer_info['address_1']."', shipping_address_2 = '".$customer_info['address_2']."', shipping_city = '".$customer_info['city']."', shipping_postcode = '".$customer_info['postcode']."', shipping_country = '', shipping_country_id = '".$customer_info['country_id']."', shipping_zone = '', shipping_zone_id = '".$customer_info['zone_id']."', shipping_address_format = '', shipping_custom_field = '', shipping_method = 'In-Store Pick up', shipping_code = 'xshippingpro.xshippingpro2', comment = '', total = '', affiliate_id = '',order_status_id='1', commission = '', marketing_id = '', tracking = '', language_id = '', currency_id = '',currency_value='1', currency_code = 'USD', order_type = 3, manufacturer_id = '".$manufacturer_id."', date_added = NOW(), date_modified = NOW()"); 

		$order_id = $this->db->getLastId();

		$this->insertBackOrderProduct($order_id,$product); 
		
		$this->updateOrderTotal($order_id);
		

	}

	/**
	 * Get Customer By email
	 */
	public function getCustomerByEmail($email) {
		$data = array();
		if( !empty($email) )
		{
			$query = $this->db->query("SELECT DISTINCT c.*,a.* FROM " . DB_PREFIX . "customer c LEFT JOIN ".DB_PREFIX."address a ON(a.customer_id=c.customer_id) WHERE LOWER(c.email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
			$data = $query->row;
		}

		return $data;
	}

	public function getCustomerByID($customer_id) {
		$data = array();
		if( $customer_id > 0 )
		{
			$query = $this->db->query("SELECT DISTINCT c.*,a.* FROM " . DB_PREFIX . "customer c LEFT JOIN ".DB_PREFIX."address a ON(a.customer_id=c.customer_id) WHERE c.customer_id = '" . (int)$customer_id . "'");
			$data = $query->row;
		}

		return $data;
	}


	public function getCustomerByPhone($telephone) {
		$data = array();
		if( !empty($telephone) )
		{
			$query = $this->db->query("SELECT DISTINCT c.*,a.* FROM " . DB_PREFIX . "customer c LEFT JOIN ".DB_PREFIX."address a ON(a.customer_id=c.customer_id) WHERE c.telephone = '" . $this->db->escape($telephone) . "'");
			$data = $query->row;
		}

		return $data;
	}

	/**
	 * Import product from CSV into DB
	 */
	public function importOrderProducts($order_id,$products)
	{ 
		if ( $products )
		{
			foreach( $products as $product )
			{
				$product_id = $product['product_id'];

				// Backorder check for customer name update
				if( !empty($product['firstname']) && !empty($product['lastname']) )
				{
						$this->updateCustomerName($order_id, $product['firstname'], $product['lastname']);
				}
				// Check weather product already exist in order
				$product_exist = $this->checkProductInOrder($order_id,$product_id);
				if( $product_exist )
				{ 
					if ( $product['quantity'] < 0 )
					{
						// If quantity is negative then delete it
						$this->deleteThisOrderProduct($order_id,$product_id);

					} else { 
						// In case product already exist then update product data from CSV
						$this->updateOrderProduct($order_id,$product);
					}
				} else {
					if ( $product['quantity'] > 0 )
					{
						//Check whether product is from backorder or incoming order
						if( isset($product['email']) )
						{
							// Backorder product
							$this->insertBackOrderProduct($order_id,$product);
						} else {
							// Incoming product
							$this->insertOrderProduct($order_id,$product);
						}
					}
				}
			}
		}
	}

   public function deleteThisOrderProduct($order_id, $product_id)
   {
		$this->db->query("DELETE FROM ".DB_PREFIX."order_product WHERE product_id='".$product_id."' AND order_id='".$order_id."'");
	 }
	 

	 public function updateCustomerName($order_id, $firstname, $lastname)
	 {
			$this->db->query("UPDATE " . DB_PREFIX . "order SET firstname = '". $this->db->escape($firstname) ."', lastname = '". $this->db->escape($lastname) ."' WHERE order_id = '" . (int)$order_id . "'");
	 }

	
	/**
	 * Update Products from CSV
	 */
	public function updateOrderProduct($order_id,$product)
	{  
		$product_id = $product['product_id'];
		$sql = "UPDATE " . DB_PREFIX . "order_product SET";
		if ( $product['price'] > 0 )
		{
			$sql .= " price='".$product['price']."',";
		}

		if ( $product['quantity'] > 0 )
		{
			$sql .= " quantity='".$product['quantity']."',";
		}

		if ( !empty($product['quantity_received']) )
		{
			$sql .= " quantity_supplied='".$product['quantity_received']."',";
		}

		if ( !empty($product['default_vendor_unit']) )
		{
			$sql .= " default_vendor_unit='".$product['default_vendor_unit']."',";
		}

		if ( !empty($product['updated_vendor_unit']) )
		{
			$sql .= " updated_vendor_unit='".$product['updated_vendor_unit']."',";
		}

		if ( isset($product['remark']) )
		{ 
			$sql .= " remark ='". $this->db->escape($product['remark']) ."',";
		}

		if ( isset($product['order_comment']) )
		{ 
			$sql .= " remark ='". $this->db->escape($product['order_comment']) ."',";
		}

		$sql = rtrim($sql,",");

		$sql .= " WHERE order_id='".$order_id."' AND product_id='".$product_id."'";
		$this->db->query($sql);	
		$this->db->query("UPDATE " . DB_PREFIX . "order_product SET total = quantity * price WHERE order_id='".$order_id."' AND product_id='".$product_id."'");	

	}

	/**
	 * Insert Products from CSV
	 */
	public function insertOrderProduct($order_id,$product)
	{
		$product_data = $this->getProductData($product['model']);

		if ( empty($product['product_id']) && empty($product_data) && !empty($product['model']) )
		{
			$this->createIncomingProduct($product);
			$product_data = $this->getProductData($product['model']); 
		}

		$product['product_id'] = !empty($product['product_id']) ? $product['product_id'] : !empty($product_data) ? $product_data['product_id'] : 0;

		$product['name'] = !empty($product['name']) ? $product['name'] : !empty($product_data) ? $product_data['name'] : "";
		
		$product['price'] = !empty($product['price']) ? $product['price'] : !empty($product_data) ? $product_data['price'] : 0;

		$product['quantity'] = !empty($product['quantity']) ? $product['quantity'] : 1;

		$product['quantity_received'] = !empty($product['quantity_received']) ? $product['quantity_received'] : 0;
		$product['default_vendor_unit'] = !empty($product['default_vendor_unit']) ? $product['default_vendor_unit'] : "";
		$product['updated_vendor_unit'] = !empty($product['updated_vendor_unit']) ? $product['updated_vendor_unit'] : "";

		$this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (float)$product['quantity'] . "', default_vendor_unit = '" . $this->db->escape($product['default_vendor_unit']) . "', quantity_supplied='".(float)$product['quantity_received'] . "', updated_vendor_unit = '" . $this->db->escape($product['updated_vendor_unit']) . "', price = '" . (float)$product['price'] . "', total = '" . (float)$product['price'] * (float)$product['quantity'] . "', remark = '" . $this->db->escape($product['remark']) . "'");

		$date_ordered = date("Y-m-d");
		$this->db->query("UPDATE ".DB_PREFIX."product SET incoming_request='1', 
		date_ordered = '". $date_ordered ."' WHERE product_id='".$product['product_id']."'");
		$estimate_deliver_time = $this->getProductEstimateDeliveryTime($product['product_id']);
		if($estimate_deliver_time > 0)
		{
			$date_available = date('Y-m-d', strtotime($date_ordered. " + {$estimate_deliver_time} days"));
			$this->db->query("UPDATE " . DB_PREFIX . "product SET frontend_date_available = '" . $date_available . "' WHERE product_id = '" . (int)$product['product_id'] . "' AND stock_status_id = 5 AND quantity < 1");
		}
	}

	public function insertBackOrderProduct($order_id,$product)
	{
		$product_data = $this->getProductData($product['model']);

		if ( empty($product['product_id']) && empty($product_data) && !empty($product['model']) )
		{
			$this->createBackOrderProduct($product);
			$product_data = $this->getProductData($product['model']); 
		}

		$product['product_id'] = !empty($product['product_id']) ? $product['product_id'] : !empty($product_data) ? $product_data['product_id'] : 0;

		$product['name'] = !empty($product['name']) ? $product['name'] : !empty($product_data) ? $product_data['name'] : "";
		
		$product['price'] = !empty($product['price']) ? $product['price'] : !empty($product_data) ? $product_data['price'] : 0;

		$product['quantity'] = !empty($product['quantity']) ? $product['quantity'] : 1;

		$this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (float)$product['quantity'] . "', price = '" . (float)$product['price'] . "', total = '" . (float)$product['price'] * (float)$product['quantity'] . "', remark = '" . $this->db->escape($product['order_comment']) . "'");

	}

	/**
	 * Check weather product already exist in order
	 */
	public function checkProductInOrder($order_id,$product_id)
	{
		$query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "' AND product_id = '" . (int)$product_id . "'");
		return $query->row ? true : false;
	}

	/**
	 * Get CSV Products
	 */
	public function getIncomingOrderedProducts($order_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "incoming_order_temp_data WHERE order_id = '" . (int)$order_id . "' ORDER BY product_id DESC");
		return $query->rows;
	}

	public function getBackOrderedProducts($order_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "backorder_temp_data WHERE order_id = '" . (int)$order_id . "' ORDER BY product_id DESC");
		return $query->rows;
	}

	/**
	 * Truncate incoming order temp data from importing product from CSV
	 */
	public function truncateTempTable()
	{
		$this->db->query("TRUNCATE TABLE " . DB_PREFIX . "incoming_order_temp_data");
	}

	public function truncateBackorderTempTable()
	{
		$this->db->query("TRUNCATE TABLE " . DB_PREFIX . "backorder_temp_data");
	}

	/**
	 * Insert CSV data to temp table
	 */
	public function insertTempData($data)
	{ 
		$this->db->query("INSERT INTO " . DB_PREFIX . "incoming_order_temp_data SET order_id = '" . (int)$data['order_id'] . "', store_name = '" . $this->db->escape($data['store_name']) . "', product_id = '" . (int)$data['product_id'] . "', model = '" . $this->db->escape($data['model']) . "', mpn = '" . $this->db->escape($data['mpn']) . "', name = '" . $this->db->escape($data['name']) . "', manufacturer = '" . $this->db->escape($data['manufacturer']) . "', price = '" . (float)$data['price'] . "', quantity = '" . (int)$data['quantity'] . "', default_vendor_unit = '" . $this->db->escape($data['default_vendor_unit']) . "', quantity_received = '" . (int)$data['quantity_received'] . "', updated_vendor_unit = '" . $this->db->escape($data['updated_vendor_unit']) . "', total = '" . (float)$data['total'] . "', remark = '" . $this->db->escape($data['remark']) . "'");
		   
	   
	}


	public function insertBackorderTempData($data)
	{ 
		$this->db->query("INSERT INTO " . DB_PREFIX . "backorder_temp_data SET order_id = '" . (int)$data['order_id'] . "', date_added = '" . $this->db->escape($data['date_added']) . "', store = '" . $this->db->escape($data['store_name']) . "', customer_id = '" . (int)$data['customer_id'] . "',  firstname = '" . $this->db->escape($data['firstname']) . "',  lastname = '" . $this->db->escape($data['lastname']) . "',  company = '" . $this->db->escape($data['company']) . "',  email = '" . $this->db->escape($data['email']) . "',  phone = '" . $this->db->escape($data['phone']) . "', order_status = '" . $this->db->escape($data['order_status']) . "', product_id = '" . (int)$data['product_id'] . "', model = '" . $this->db->escape($data['model']) . "', mpn = '" . $this->db->escape($data['mpn']) . "', name = '" . $this->db->escape($data['name']) . "', manufacturer = '" . $this->db->escape($data['manufacturer']) . "', price = '" . (float)$data['price'] . "', quantity = '" . (int)$data['quantity'] . "', total = '" . (float)$data['total'] . "', location = '" . $this->db->escape($data['location']) . "', order_comment = '" . $this->db->escape($data['order_comment']) . "'");
	}

	/**
	 * Get All Imported Order List
	 */
	public function getImportedOrders()
	{
		$query = $this->db->query("SELECT i.order_id FROM " . DB_PREFIX . "incoming_order_temp_data i INNER JOIN " . DB_PREFIX . "order o WHERE o.order_type = 2 GROUP BY order_id ORDER BY order_id DESC");
		return $query->rows;

	}

	public function getImportedBackOrders()
	{
		$query = $this->db->query("SELECT order_id FROM " . DB_PREFIX . "backorder_temp_data GROUP BY order_id ORDER BY order_id DESC");
		return $query->rows;

	}

	/**
	 * Update order total after products imported
	 */
	public function updateOrderTotal($order_id)
	{ 
		$query = $this->db->query("SELECT sum(total) as products_total FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

		$total = $query->row ? $query->row['products_total'] : 0;
		
		if( $total )
		{
			$text = "$" . $total; 
			$this->db->query("UPDATE " . DB_PREFIX . "order_total SET value='".$total."', text='".$text."' WHERE order_id='".$order_id."' AND code='sub_total'");	
			$this->db->query("UPDATE " . DB_PREFIX . "order_total SET value='".$total."', text='".$text."' WHERE order_id='".$order_id."' AND code='total'");
			$this->db->query("UPDATE " . DB_PREFIX . "order SET total='".$total."' WHERE order_id='".$order_id."'");
		}
	}


	public function getOrderData($order_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order WHERE order_id ='".$order_id."'");

		return $query->row ? $query->row : array();
	}

	public function getProductData($model)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p INNER JOIN " . DB_PREFIX . "product_description pd ON p.product_id = pd.product_id WHERE pd.language_id = 1 AND p.model ='".$model."'");

		return $query->row ? $query->row : array();
	}

	public function getManufacturerName($manufacturer_id)
	{
		$query = $this->db->query("SELECT name FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id ='".$manufacturer_id."'");

		return $query->row ? $query->row['name'] : "";
	}


	public function validateFile($file, $delimiter)
	{
		$ext = pathinfo($file, PATHINFO_EXTENSION);
		if ( $ext != 'csv' )
		{
			return "The import works with CSV files only.";
		}
		$read_file = fopen($file,"r");
		$i = 0;
		while(! feof($read_file))
		{
			$data = array();
			$row = fgetcsv($read_file, 1000, $delimiter);
			
			if ($i == 0)
			{
				foreach( $row as $k => $col )
				{
					if ( $col == 'Model Number')
					{
						$required = $k;
					}
				}
			} else {

				if ( !empty($row) && empty($row[$required]) )
				{
					return "Please check your csv. Some product modal number is missing in csv.";
				}
			}
			
			 
			$i++;
		}

		return "ok";
	}


	public function validateBackorderFile($file, $delimiter)
	{
		$ext = pathinfo($file, PATHINFO_EXTENSION);
		if ( $ext != 'csv' )
		{
			return "The import works with CSV files only.";
		}
		$read_file = fopen($file,"r");
		$i = 0;
		while(! feof($read_file))
		{
			$data = array();
			$row = fgetcsv($read_file, 1000, $delimiter);
			
			if ($i == 0)
			{
				foreach( $row as $k => $col )
				{
					if ( $col == 'Model Number' )
					{
						$required = $k;
					}
				}
			} else {

				if ( !empty($row) && empty($row[$required]) )
				{
					return "Please check your csv. Some product modal number is missing in csv.";
				}
			}
			
			$i++;
		}

		return "ok";
	}

	public function getProductMpn($product_id)
	{
		$query = $this->db->query("SELECT mpn FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");

		return $query->row ? $query->row['mpn'] : "";
	}
	
	/**
	 * Prepare CSV data for import to table
	 */
	public function importDataToTempTable($file, $delimiter)
	{
		$read_file = fopen($file,"r");
		$i = 0;
		$cols = array(); // columns in csv
		while(! feof($read_file))
		{
			$data = array();
			$row = fgetcsv($read_file, 1000, $delimiter);
			if ( $i == 0 )
			{
				foreach( $row as $k => $col )
				{
					switch($col)
					{
						case 'Order Id' 	: $cols['order_id'] = $k; break;
						case 'Date' 		: $cols['date_added'] = $k; break;
						case 'Store' 		: $cols['store_name'] = $k; break;
						case 'Order Status' : $cols['order_status'] = $k; break;
						case 'Product Id' 	: $cols['product_id'] = $k; break;
						case 'Model Number' : $cols['model'] = $k; break;
						case 'MPN' 			: $cols['mpn'] = $k; break;
						case 'Product Name' : $cols['name'] = $k; break;
						case 'Manufacturer' : $cols['manufacturer'] = $k; break;
						case 'Quantity' 	: $cols['quantity'] = $k; break;
						case 'Ordered Vendor Unit'	: $cols['default_vendor_unit'] = $k; break;
						case 'Quantity Received' 	: $cols['quantity_received'] = $k; break;
						case 'Received Vendor Unit' : $cols['updated_vendor_unit'] = $k; break;
						case 'Price' 		: $cols['price'] = $k; break;
						case 'Location' 	: $cols['location'] = $k; break;
						case 'Total' 		: $cols['total'] = $k; break;
						case 'Remark' 		: $cols['remark'] = $k; break;
					}
				}

			} else {
				
					// Order ID
					if ( array_key_exists( "order_id", $cols ) )
					{
						$order_id = $cols['order_id'];
						$data['order_id'] = $row[$order_id];
					} else {
						$data['order_id'] = 0;
					}

					// Modal
					$model 			= $cols['model'];
					$data['model'] 	= $row[$model]; 

					// Order Data
					$order_data 	= $this->getOrderData($data['order_id']);

					// Product Data
					$product_data 	= $this->getProductData($data['model']);

					// Store Name
					$data['store_name'] 	= "Gempacked";

					// Product ID
					if ( array_key_exists( "product_id", $cols ) )
					{
						$product_id = $cols['product_id'];
						$data['product_id'] = $row[$product_id];
					} else {
						$data['product_id'] = isset($product_data['product_id']) ? $product_data['product_id'] : 0;
					}

					// Product Name
					if ( array_key_exists( "name", $cols ) )
					{
						$name = $cols['name'];
						$data['name'] = $row[$name];
					} else {
						$data['name'] = isset($product_data['name']) ? $product_data['name'] : "";
					}

					// MPN
					if ( array_key_exists( "mpn", $cols ) )
					{
						$mpn 			= $cols['mpn'];
						$data['mpn'] 	= $row[$mpn]; 
					} else {
						$data['mpn'] 	= isset($product_data['product_id']) ? $this->getProductMpn($product_data['product_id']) : "";
					}

					// Ordered Vendor Unit
					if ( array_key_exists( "default_vendor_unit", $cols ) )
					{
						$default_vendor_unit 			= $cols['default_vendor_unit'];
						$data['default_vendor_unit'] 	= $row[$default_vendor_unit]; 
					} else {
						$data['default_vendor_unit'] 	= isset($product_data['default_vendor_unit']) ? $product_data['default_vendor_unit'] : "";
					}

					// Received Vendor Unit
					if ( array_key_exists( "updated_vendor_unit", $cols ) )
					{
						$updated_vendor_unit 			= $cols['updated_vendor_unit'];
						$data['updated_vendor_unit'] 	= $row[$updated_vendor_unit]; 
					} else {
						$data['updated_vendor_unit'] 	= isset($product_data['updated_vendor_unit']) ? $product_data['updated_vendor_unit'] : "";
					}

					// Manufacturer
					if ( array_key_exists( "manufacturer", $cols ) )
					{
						$manufacturer = $cols['manufacturer'];
						$data['manufacturer'] = $row[$manufacturer];
					} else {
						$data['manufacturer'] = isset($product_data['manufacturer_id']) ? $this->getManufacturerName($product_data['manufacturer_id']) : "";
					}

					// Quantity
					if ( array_key_exists( "quantity", $cols ) )
					{
						$quantity = $cols['quantity'];
						$data['quantity'] = $row[$quantity];
					} else {
						$data['quantity'] = 0;
					}

					// Quantity Received
					if ( array_key_exists( "quantity_received", $cols ) )
					{
						$quantity_received = $cols['quantity_received'];
						$data['quantity_received'] = $row[$quantity_received];
					} else {
						$data['quantity_received'] = 0;
					}

					// Price
					if ( array_key_exists( "price", $cols ) )
					{
						$price = $cols['price'];
						$data['price'] = $row[$price];
					} else {
						$data['price'] = 0;
					}

					// Total
					if ( array_key_exists( "total", $cols ) )
					{
						$total = $cols['total'];
						$data['total'] = $row[$total];
					} else {
						$data['total'] = 0;
					}

					// Remark
					if ( array_key_exists( "remark", $cols ) )
					{
						$remark = $cols['remark'];
						$data['remark'] = $row[$remark];
					} else {
						$data['remark'] = "";
					}

					if( !empty( $data['model'] )  && $data['model'] != 'Model Number' )
					{
						$this->insertTempData($data);
					}
			 }
			 
		  $i++;
	  	}

	}

	public function importBackorderDataToTempTable($file, $delimiter)
	{
		$read_file = fopen($file,"r");
		$i = 0;
		$cols = array(); // columns in csv
		while(! feof($read_file))
		{
			$data = array();
			$row = fgetcsv($read_file, 1000, $delimiter);
			if ( $i == 0 )
			{
				foreach( $row as $k => $col )
				{
					switch($col)
					{
						case 'Order Id' 	: $cols['order_id'] = $k; break;
						case 'Date' 		: $cols['date_added'] = $k; break;
						case 'Store' 		: $cols['store'] = $k; break;
						case 'Customer ID'  : $cols['customer_id'] = $k; break;
						case 'First Name' 	: $cols['firstname'] = $k; break;
						case 'Last Name'    : $cols['lastname'] = $k; break;
						case 'Company' 		: $cols['company'] = $k; break;
						case 'Email' 		: $cols['email'] = $k; break;
						case 'Phone' 		: $cols['phone'] = $k; break;
						case 'Order Status' : $cols['order_status'] = $k; break;
						case 'Product Id'	: $cols['product_id'] = $k; break;
						case 'Model Number' 	: $cols['model'] = $k; break;
						case 'MPN' 			: $cols['mpn'] = $k; break;
						case 'Product Name' : $cols['name'] = $k; break;
						case 'Manufacturer' : $cols['manufacturer'] = $k; break;
						case 'Quantity' 	: $cols['quantity'] = $k; break;
						case 'Price' 		: $cols['price'] = $k; break;
						case 'Location' 	: $cols['location'] = $k; break;
						case 'Total' 		: $cols['total'] = $k; break;
						case 'Order Comments' : $cols['order_comment'] = $k; break; 
					}
				}

			} else {
				
					// Order ID
					if ( array_key_exists( "order_id", $cols ) )
					{
						$order_id = $cols['order_id'];
						$data['order_id'] = $row[$order_id];
					} else {
						$data['order_id'] = 0;
					}

					// Modal
					$model 			= $cols['model'];
					$data['model'] 	= $row[$model]; 

					// Order Data
					$order_data 	= $this->getOrderData($data['order_id']);

					// Product Data
					$product_data 	= $this->getProductData($data['model']);

					// Store Name
					$data['store_name'] 	= "Backorders";

					// Date Added
					if ( array_key_exists( "date_added", $cols ) )
					{
						$date_added = $cols['date_added'];
						$data['date_added'] = $row[$date_added];
					} else {
						$data['date_added'] = $order_data['date_added'];
					} 

					// Customer ID
					if ( array_key_exists( "customer_id", $cols ) )
					{
						$customer_id = $cols['customer_id'];
						$data['customer_id'] = $row[$customer_id];
					} else {
						$data['customer_id'] = $order_data['customer_id'];
					} 

					// First Name
					if ( array_key_exists( "firstname", $cols ) )
					{
						$firstname = $cols['firstname'];
						$data['firstname'] = $row[$firstname];
					} else {
						$data['firstname'] = $order_data['firstname'];
					} 

					// Last Name
					if ( array_key_exists( "lastname", $cols ) )
					{
						$lastname = $cols['lastname'];
						$data['lastname'] = $row[$lastname];
					} else {
						$data['lastname'] = $order_data['lastname'];
					} 

					// Company
					if ( array_key_exists( "company", $cols ) )
					{
						$company = $cols['company'];
						$data['company'] = $row[$company];
					} else {
						$data['company'] = $order_data['payment_company'];
					} 

					// Email
					if ( array_key_exists( "email", $cols ) )
					{
						$email = $cols['email'];
						$data['email'] = $row[$email];
					} else {
						$data['email'] = $order_data['email'];
					} 

					// Phone
					if ( array_key_exists( "phone", $cols ) )
					{
						$phone = $cols['phone'];
						$data['phone'] = $row[$phone];
					} else {
						$data['phone'] = $order_data['telephone'];
					} 

					// Product ID
					if ( array_key_exists( "product_id", $cols ) )
					{
						$product_id = $cols['product_id'];
						$data['product_id'] = $row[$product_id];
					} else {
						$data['product_id'] = isset($product_data['product_id']) ? $product_data['product_id'] : 0;
					}

					// Product Name
					if ( array_key_exists( "name", $cols ) )
					{
						$name = $cols['name'];
						$data['name'] = $row[$name];
					} else {
						$data['name'] = isset($product_data['name']) ? $product_data['name'] : "";
					}

					// MPN
					if ( array_key_exists( "mpn", $cols ) )
					{
						$mpn 			= $cols['mpn'];
						$data['mpn'] 	= $row[$mpn]; 
					} else {
						$data['mpn'] 	= isset($product_data['product_id']) ? $this->getProductMpn($product_data['product_id']) : "";
					}

					

					// Manufacturer
					if ( array_key_exists( "manufacturer", $cols ) )
					{
						$manufacturer = $cols['manufacturer'];
						$data['manufacturer'] = $row[$manufacturer];
					} else {
						$data['manufacturer'] = isset($product_data['manufacturer_id']) ? $this->getManufacturerName($product_data['manufacturer_id']) : "";
					}

					// Quantity
					if ( array_key_exists( "quantity", $cols ) )
					{
						$quantity = $cols['quantity'];
						$data['quantity'] = $row[$quantity];
					} else {
						$data['quantity'] = 0;
					}

					

					// Price
					if ( array_key_exists( "price", $cols ) )
					{
						$price = $cols['price'];
						$data['price'] = $row[$price];
					} else {
						$data['price'] = 0;
					}

					// Total
					if ( array_key_exists( "total", $cols ) )
					{
						$total = $cols['total'];
						$data['total'] = $row[$total];
					} else {
						$data['total'] = 0;
					}

					// Order Status
					if ( array_key_exists( "order_comment", $cols ) )
					{
						$order_comment = $cols['order_comment'];
						$data['order_comment'] = $row[$order_comment];
					} else {
						$data['order_comment'] = "";
					}

					// Location
					if ( array_key_exists( "location", $cols ) )
					{
						$location = $cols['location'];
						$data['location'] = $row[$location];
					} else {
						$data['location'] = "";
					}

					// Order Comments
					if ( array_key_exists( "order_status", $cols ) )
					{
						$order_status = $cols['order_status'];
						$data['order_status'] = $row[$order_status];
					} else {
						$data['order_status'] = "";
					}

					if( !empty( $data['model'] )  && $data['model'] != 'Model Number' )
					{
						$this->insertBackorderTempData($data);
					}
			 }
			 
		  $i++;
	  	}

	}
}

?>