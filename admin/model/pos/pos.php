<?php
// add for Quick sale begin
define ('QUICK_SALE_STORE_ID', '-100');
// add for Quick sale end

class ModelPosPos extends Model {
	
	// This function is how POS module creates it's tables
	public function createModuleTables() {
		// add for Sale Person Affiliate begin
		$query = $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "user_affiliate (user_id INT(11) NOT NULL, affiliate_id INT(11) NOT NULL, PRIMARY KEY (user_id))");
		// add for Sale Person Affiliate end
        // add for payment report by admin begin
        $res = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX. "order` LIKE 'user_id'");
        if($res->num_rows == 0){
            $this->db->query("ALTER TABLE `". DB_PREFIX. "order` ADD `user_id` INT( 11 )");
        }
        // add for payment report by admin end
		
		$res = $this->db->query("SHOW TABLES LIKE '". DB_PREFIX. "order_payment'");
		if ($res->num_rows == 0) {
			// table does not exists, simply recreate the table
			$query = $this->db->query("CREATE TABLE " . DB_PREFIX . "order_payment (order_payment_id INT(11) NOT NULL AUTO_INCREMENT, order_id INT(11) NOT NULL DEFAULT '0', pos_return_id INT(11) NOT NULL DEFAULT '0', user_id INT(11) NOT NULL, payment_type VARCHAR(100), tendered_amount FLOAT NOT NULL, payment_note VARCHAR(256), payment_time DATETIME, PRIMARY KEY (order_payment_id), KEY order_id(order_id)) DEFAULT CHARACTER SET utf8");
		} else {
			$res = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX. "order_payment` LIKE 'user_id'");
			if($res->num_rows == 0) {
				// old table, migrate data
				$migrate_data = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_payment");
				$this->db->query("DROP TABLE " . DB_PREFIX . "order_payment");
				$query = $this->db->query("CREATE TABLE " . DB_PREFIX . "order_payment (order_payment_id INT(11) NOT NULL AUTO_INCREMENT, order_id INT(11) NOT NULL, user_id INT(11) NOT NULL, payment_type VARCHAR(100), tendered_amount FLOAT NOT NULL, payment_note VARCHAR(256), payment_time DATETIME, PRIMARY KEY (order_payment_id), KEY order_id(order_id)) DEFAULT CHARACTER SET utf8");
				foreach ($migrate_data->rows as $key => $row) {
					$order_query = $this->db->query("SELECT user_id FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$row['order_id'] . "'");
					$order_info = $order_query->row;
					$user_id = 0;
					if ($order_info) {
						// add for admin payment details begin
						$user_id = $order_info['user_id'];
					}
					// add data back to table
					$this->db->query("INSERT INTO " . DB_PREFIX . "order_payment SET order_id = '" . $row['order_id'] . "', user_id = '" . $user_id . "', payment_type = '" . $row['payment_type'] . "', tendered_amount = '" . $row['tendered_amount'] . "', payment_note = '" . $this->db->escape($row['payment_note']) . "', payment_time = '" . $row['payment_time'] . "'");
				}
			}
			$res = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX. "order_payment` LIKE 'pos_return_id'");
			if($res->num_rows == 0) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "order_payment` ADD pos_return_id INT(11) NOT NULL DEFAULT '0'");
			}
		}
		
        // add for quotation begin
        $res = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX. "order` LIKE 'quote_status_id'");
        if($res->num_rows == 0){
            $this->db->query("ALTER TABLE `". DB_PREFIX. "order` ADD `quote_status_id` INT( 11 ) DEFAULT '0', ADD `quote_id` INT(11) DEFAULT '0'");
        }
		$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "quote_status (quote_status_id INT(11) NOT NULL AUTO_INCREMENT, language_id INT(11) NOT NULL, name VARCHAR(32), PRIMARY KEY (quote_status_id)) DEFAULT CHARACTER SET utf8");
        // add for quotation end
        // add for product abbreviation begin
        $res = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX. "product` LIKE 'abbreviation'");
        if($res->num_rows == 0){
            $this->db->query("ALTER TABLE `". DB_PREFIX. "product` ADD `abbreviation` VARCHAR( 10 ) DEFAULT ''");
        }
        // add for product abbreviation end
        // add for product quick sale begin
        $res = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX. "product` LIKE 'quick_sale'");
        if($res->num_rows == 0){
            $this->db->query("ALTER TABLE `". DB_PREFIX. "product` ADD `quick_sale` INT( 11 ) DEFAULT '1'");
        }
        // add for product quick sale end
        // add for product weight based price begin
        $res = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX. "product` LIKE 'weight_price'");
        if($res->num_rows == 0){
            $this->db->query("ALTER TABLE `". DB_PREFIX. "product` ADD `weight_price` TINYINT( 1 ) DEFAULT '0', ADD `weight_name` VARCHAR(20) DEFAULT 'Weight'");
        }
        $res = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX. "order_product` LIKE 'weight'");
        if($res->num_rows == 0){
            $this->db->query("ALTER TABLE `". DB_PREFIX. "order_product` ADD `weight` DECIMAL( 8,2 ) DEFAULT '1'");
        }
        // add for product weight based price end
        // add for table management begin
        $res = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX. "order` LIKE 'table_id'");
        if($res->num_rows == 0){
            $this->db->query("ALTER TABLE `". DB_PREFIX. "order` ADD `table_id` INT( 11 ) DEFAULT '0'");
        }
		$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "pos_table (table_id INT(11) NOT NULL AUTO_INCREMENT, location_id INT(11) NOT NULL, coors VARCHAR(32), name VARCHAR(32) NOT NULL, description VARCHAR(100), PRIMARY KEY (table_id)) DEFAULT CHARACTER SET utf8");
        // add for table management end
		// add for serial no begin
		$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "product_sn (product_sn_id INT(11) NOT NULL AUTO_INCREMENT, product_id INT(11) NOT NULL, sn VARCHAR(32) NOT NULL, status TINYINT(1) DEFAULT '1', order_product_id INT(11), order_id INT(11), date_modified DATETIME, PRIMARY KEY (product_sn_id)) DEFAULT CHARACTER SET utf8");
		// add for serial no end
		// customization serial no cost and packaging slip begin
        $res = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX. "product_sn` LIKE 'cost'");
        if($res->num_rows == 0){
            $this->db->query("ALTER TABLE `". DB_PREFIX. "product_sn` ADD `cost` DECIMAL(8,2) DEFAULT '0.00'");
        }
		$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "product_packaging (packaging_id INT(11) NOT NULL AUTO_INCREMENT, packaging_slip VARCHAR(50) NOT NULL, order_number VARCHAR(50) NOT NULL, date DATETIME, note VARCHAR(100), PRIMARY KEY (packaging_id)) DEFAULT CHARACTER SET utf8");
		$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "product_sn_packaging (sn_packaging_id INT(11) NOT NULL AUTO_INCREMENT, packaging_id INT(11) NOT NULL, product_sn_id INT(11) NOT NULL, PRIMARY KEY (sn_packaging_id))");
		// customization serial no cost and packaging slip end
		// add for commission begin
		$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "product_commission (product_id INT(11) NOT NULL, type VARCHAR(1) NOT NULL, value DECIMAL(8,2) DEFAULT '0.00', base DECIMAL(15,2) DEFAULT '0.00', date_modified DATETIME, PRIMARY KEY (product_id))");
		$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "order_commission (order_id INT(11) NOT NULL, commission DECIMAL(8,2) DEFAULT '0.00', user_id INT(11) NOT NULL, date_modified DATETIME, PRIMARY KEY (order_id))");
		// add for commission end
		// add for product based discount begin
        $res = $this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX. "order_product_discount` (order_product_id INT(11) NOT NULL, discount_type TINYINT(1), discount_value DECIMAL(15,2) DEFAULT '0.00', `include_tax` TINYINT(1), `discounted_price` DECIMAL(15,4) DEFAULT '0.00', `discounted_tax` DECIMAL(15,4) DEFAULT '0.00', `discounted_total` DECIMAL(15,4) DEFAULT '0.00', PRIMARY KEY(order_product_id))");
		// add for product based discount end
		// add for product return begin
        $res = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX. "return` LIKE 'order_product_id'");
        if($res->num_rows == 0){
            $this->db->query("ALTER TABLE `". DB_PREFIX. "return` ADD `price` DECIMAL(15,4), ADD `tax` DECIMAL(15,4), ADD `order_product_id` INT(11), ADD `pos_return_id` INT(11) DEFAULT '0'");
        } else {
			$res = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX. "return` LIKE 'return_receipt_id'");
			if($res->num_rows >0){
				$this->db->query("ALTER TABLE `". DB_PREFIX. "return` CHANGE `return_receipt_id` `pos_return_id` INT");
			}
			$res = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX. "return` LIKE 'price'");
			if($res->num_rows == 0){
				$this->db->query("ALTER TABLE `". DB_PREFIX. "return` ADD `price` DECIMAL(15,4), ADD `tax` DECIMAL(15,4)");
			}
			$res = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX. "return` LIKE 'weight'");
			if($res->num_rows == 0){
				$this->db->query("ALTER TABLE `". DB_PREFIX. "return` ADD `weight` DECIMAL(8,2) DEFAULT '1', ADD `weight_name` VARCHAR(20), ADD `sn` VARCHAR(32)");
			}
		}
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX. "pos_return` (pos_return_id INT(11) NOT NULL AUTO_INCREMENT, user_id INT(11), location_id INT(11), customer_id INT(11), firstname VARCHAR(32), lastname VARCHAR(32), email VARCHAR(96), telephone VARCHAR(32), return_status_id INT(11), tax DECIMAL(15, 4), sub_total DECIMAL(15,4), date_added DATETIME, date_modified DATETIME, PRIMARY KEY(pos_return_id)) DEFAULT CHARACTER SET utf8");
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "pos_return_option` (`return_option_id` INT(11) NOT NULL AUTO_INCREMENT, `return_id` INT(11) NOT NULL, `product_option_id` INT(11) NOT NULL, `product_option_value_id` INT(11) NOT NULL, `name` VARCHAR(255) NOT NULL, `value` TEXT NOT NULL, `type` VARCHAR(32) NOT NULL, PRIMARY KEY (`return_option_id`)) DEFAULT CHARACTER SET utf8");
		// add for product return end
		// add for opencart discount support begin
		$res = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX. "order_product` LIKE 'price_change'");
        if($res->num_rows == 0) {
            $this->db->query("ALTER TABLE `". DB_PREFIX. "order_product` ADD `price_change` TINYINT(1) DEFAULT '0'");
        }
		// add for opencart discount support end
        // add for product shelf live begin
        $res = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX. "product` LIKE 'best_before'");
        if($res->num_rows == 0){
            $this->db->query("ALTER TABLE `". DB_PREFIX. "product` ADD `best_before` DATE DEFAULT '0000-00-00'");
        }
		// add for product shelf live end
        // add for customer loyalty card begin
        $res = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX. "customer` LIKE 'card_number'");
        if($res->num_rows == 0){
            $this->db->query("ALTER TABLE `". DB_PREFIX. "customer` ADD `card_number` VARCHAR(20) DEFAULT ''");
        }
		// add for customer loyalty card end
		// add for label print begin
        $res = $this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX. "pos_label_template` (label_template_id INT(11) NOT NULL AUTO_INCREMENT, name VARCHAR(30), top_margin INT(11) DEFAULT '3', side_margin INT(1) DEFAULT '1', vertical_pitch INT(11) DEFAULT '22', horizontal_pitch INT(11) DEFAULT '32', height INT(11) DEFAULT '20', width INT(11) DEFAULT '30', number_across INT(11) DEFAULT '3', number_down INT(11) DEFAULT '5', content TEXT DEFAULT '', PRIMARY KEY(label_template_id)) DEFAULT CHARACTER SET utf8");
		// add for label print end
        // add for product low stock begin
        $res = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX. "product` LIKE 'low_stock'");
        if($res->num_rows == 0){
            $this->db->query("ALTER TABLE `". DB_PREFIX. "product` ADD `low_stock` INT(11) DEFAULT '3'");
        }
        $res = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX. "product_option_value` LIKE 'low_stock'");
        if($res->num_rows == 0){
            $this->db->query("ALTER TABLE `". DB_PREFIX. "product_option_value` ADD `low_stock` INT(11) DEFAULT '3'");
        }
		// add for product low stock end
	}

	public function deleteModuleTables() {
		// $query = $this->db->query("DROP TABLE " . DB_PREFIX . "order_payment");
		// add for Sale Person Affiliate begin
		// $query = $this->db->query("DROP TABLE " . DB_PREFIX . "user_affiliate");
		// add for Sale Person Affiliate end
	}

	public function addOrderPayment($data) {
		if (isset($data['order_id'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_payment SET order_id = '" . (int)$data['order_id'] . "', user_id = '" . (int)$data['user_id'] . "', payment_type = '" . $this->db->escape($data['payment_type']) . "', tendered_amount = '" . (float)$data['tendered_amount'] . "', payment_note = '" . $this->db->escape($data['payment_note']) . "', payment_time = NOW()");
		} elseif (!empty($data['pos_return_id'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_payment SET order_id = '-1', pos_return_id = '" . (int)$data['pos_return_id'] . "', user_id = '" . (int)$data['user_id'] . "', payment_type = '" . $this->db->escape($data['payment_type']) . "', tendered_amount = '" . (0 - (float)$data['tendered_amount']) . "', payment_note = '" . $this->db->escape($data['payment_note']) . "', payment_time = NOW()");
		}
		$order_payment_id = $this->db->getLastId();
		
		if (isset($data['change'])) {
			if (isset($data['order_id'])) {
				$change_row = $this->db->query("SELECT order_payment_id FROM " . DB_PREFIX . "order_payment WHERE order_id = '" . (int)$data['order_id'] . "' AND payment_type = 'pos_change'");
				if ($change_row->row) {
					$this->db->query("UPDATE " . DB_PREFIX . "order_payment SET tendered_amount = '" . (float)$data['change'] . "', payment_note = '" . $order_payment_id . "', payment_time = NOW() WHERE order_payment_id = '" . $change_row->row['order_payment_id'] . "'");
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "order_payment SET order_id = '" . (int)$data['order_id'] . "', user_id = '" . (int)$data['user_id'] . "', payment_type = 'pos_change', tendered_amount = '" . (float)$data['change'] . "', payment_note = '" . $order_payment_id . "', payment_time = NOW()");
				}
			} elseif (!empty($data['pos_return_id'])) {
				$change_row = $this->db->query("SELECT order_payment_id FROM " . DB_PREFIX . "order_payment WHERE pos_return_id = '" . (int)$data['pos_return_id'] . "' AND payment_type = 'pos_change'");
				if ($change_row->row) {
					$this->db->query("UPDATE " . DB_PREFIX . "order_payment SET tendered_amount = '" . (0 - (float)$data['change']) . "', payment_note = '" . $order_payment_id . "', payment_time = NOW() WHERE order_payment_id = '" . $change_row->row['order_payment_id'] . "'");
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "order_payment SET order_id = '-1', pos_return_id = '" . (int)$data['pos_return_id'] . "', user_id = '" . (int)$data['user_id'] . "', payment_type = 'pos_change', tendered_amount = '" . (0 - (float)$data['change']) . "', payment_note = '" . $order_payment_id . "', payment_time = NOW()");
				}
			}
		}
		// add for syncing data
		if (isset($data['payment_time'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "order_payment SET payment_time = '" . $data['payment_time'] . "' WHERE order_payment_id = '" . $order_payment_id . "'");
		}
		
		return $order_payment_id;
	}

	public function deleteOrderPayment($data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_payment WHERE payment_note = '" . $data['order_payment_id'] . "' AND payment_type = 'pos_change'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_payment WHERE order_payment_id = '" . $data['order_payment_id'] . "'");
	}

	public function retrieveOrderPayments($order_id) {
		// use order id to retrieve all the payment data
		$sqlQuery = "SELECT * FROM " . DB_PREFIX . "order_payment WHERE order_id = '" . $order_id . "'";
		$query = $this->db->query($sqlQuery);
		$payments = array();
		$pos_change = null;
		if (!empty($query->rows)) {
			foreach ($query->rows as $row) {
				if ($row['payment_type'] == 'pos_change') {
					$pos_change = $row;
				} else {
					$payments[] = $row;
				}
			}
		}
		if ($pos_change) {
			foreach ($payments as $key => $payment) {
				if ($payment['order_payment_id'] == $pos_change['payment_note']) {
					$payments[$key]['tendered_amount'] += $pos_change['tendered_amount'];
				}
			}
		}
		return $payments;
	}

	public function retrieveReturnPayments($pos_return_id) {
		// use pos return id to retrieve all the payment data
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_payment` WHERE pos_return_id = '" . (int)$pos_return_id . "'");
		$payments = array();
		$pos_change = null;
		if (!empty($query->rows)) {
			foreach ($query->rows as $row) {
				if ($row['payment_type'] == 'pos_change') {
					$pos_change = $row;
				} else {
					$payments[] = $row;
				}
			}
		}
		foreach ($payments as $key => $payment) {
			if ($pos_change && $payment['order_payment_id'] == $pos_change['payment_note']) {
				$payments[$key]['tendered_amount'] += $pos_change['tendered_amount'];
			}
			$payments[$key]['tendered_amount'] = 0 - (float)$pos_change['tendered_amount'];
		}
		return $payments;
	}

	public function getOrderPayment($order_payment_id) {
		$sql_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_payment WHERE order_payment_id = '" . $order_payment_id . "'") ;
		return $sql_query->row;
	}
	// add for Report begin
	public function getOrderPayments($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "order_payment WHERE 1";

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_return_id'])) {
			$sql .= " AND pos_return_id = '" . (int)$data['filter_pos_return_id'] . "'";
		}

		if (!empty($data['filter_payment_type'])) {
			$sql .= " AND payment_type LIKE '%" . $this->db->escape($data['filter_payment_type']) . "%'";
		}
		
		if (!empty($data['filter_tendered_amount'])) {
			$sql .= " AND tendered_amount = '" . (float)$data['filter_tendered_amount'] . "'";
		}

		if (!empty($data['filter_payment_date'])) {
			$sql .= " AND DATE(FROM_UNIXTIME(CAST(UNIX_TIMESTAMP(payment_time) + (" . (int)$data['timezone_offset'] . ") AS UNSIGNED))) = DATE('" . $this->db->escape($data['filter_payment_date']) . "')";
		}
		
		// add for admin payment details begin
		if (!empty($data['filter_user_id'])) {
			$sql .= " AND user_id = '" . (int)$data['filter_user_id'] . "'";
		}
		// add for admin payment details end

		$sort_data = array(
			'order_payment_id',
			'order_id',
			'pos_return_id',
			'payment_type',
			'tendered_amount',
			'payment_time'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY order_payment_id";
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
	
	public function getTotalOrderPayments($data = array()) {
      	$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order_payment` WHERE pos_return_id = '0'";

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_pos_return_id'])) {
			$sql .= " AND pos_return_id = '" . (int)$data['filter_pos_return_id'] . "'";
		}

		if (!empty($data['filter_payment_type'])) {
			$sql .= " AND payment_type LIKE '%" . $this->db->escape($data['filter_payment_type']) . "%'";
		}
		
		if (!empty($data['filter_tendered_amount'])) {
			$sql .= " AND tendered_amount = '" . (float)$data['filter_tendered_amount'] . "'";
		}

		if (!empty($data['filter_payment_date'])) {
			$sql .= " AND DATE(FROM_UNIXTIME(CAST(UNIX_TIMESTAMP(payment_time) + (" . (int)$data['timezone_offset'] . ") AS UNSIGNED))) = DATE('" . $this->db->escape($data['filter_payment_date']) . "')";
		}
		
		// add for admin payment details begin
		if (!empty($data['filter_user_id'])) {
			$sql .= " AND user_id = '" . (int)$data['filter_user_id'] . "')";
		}
		// add for admin payment details end

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	// add for Report end
		
	public function saveOrderStatus($order_id, $order_status_id) {
		$sql = "UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$order_status_id . "'";
		// add for Sale Person Affiliate begin
		if ($order_status_id == 5) {
			$sql_affiliate = "SELECT affiliate_id, total FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "'";
			$sql_result = $this->db->query($sql_affiliate);
			if ($sql_result->row && $sql_result->row['affiliate_id']) {
				$this->load->model('marketing/affiliate');
				
				$affiliate_info = $this->model_marketing_affiliate->getAffiliate($sql_result->row['affiliate_id']);
				if ($affiliate_info) {
					$commission = ($sql_result->row['total'] / 100) * $affiliate_info['commission'];
					$sql .= ", commission = '" . (float)$commission . "'";
					
					// add a transaction to affiliate transaction table or update if it's already there
					$sql_tx = "SELECT affiliate_transaction_id FROM `" . DB_PREFIX . "affiliate_transaction` WHERE order_id = '" . (int)$order_id . "' AND affiliate_id = '" . $affiliate_info['affiliate_id'] . "'";
					$sql_tx_result = $this->db->query($sql_tx);
					if ($sql_tx_result->row) {
						// update as it's already there
						$this->db->query("UPDATE `" . DB_PREFIX . "affiliate_transaction` SET amount = '" . (float)$commission . "' WHERE affiliate_transaction_id = '" . $sql_tx_result->row['affiliate_transaction_id'] . "'");
					} else {
						// insert a transaction
						$this->db->query("INSERT INTO `" . DB_PREFIX . "affiliate_transaction` SET affiliate_id = '" . $affiliate_info['affiliate_id'] . "', order_id = '" . (int)$order_id . "', description = 'Order ID: #" . (int)$order_id . "', amount = '" . (float)$commission . "', date_added = NOW()");
					}
				}
			}
		}
		// add for Sale Person Affiliate end
		$sql .= ", date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'";

		$this->db->query($sql);
	}
		
	public function saveReturnStatus($pos_return_id, $return_status_id) {
		$this->db->query("UPDATE `" . DB_PREFIX . "return` SET return_status_id = '" . (int)$return_status_id . "', date_modified = NOW() WHERE pos_return_id = '" . (int)$pos_return_id . "'");
		$this->db->query("UPDATE `" . DB_PREFIX . "pos_return` SET return_status_id = '" . (int)$return_status_id . "', date_modified = NOW() WHERE pos_return_id = '" . (int)$pos_return_id . "'");
	}
	
	public function addOrder($data) {
		$this->event->trigger('pre.order.add', $data);

		$invoice_prefix = $this->config->get('config_invoice_prefix');
		$store_id = isset($data['store_id']) ? $data['store_id'] : $this->config->get('config_store_id');
		$store_name = isset($data['store_name']) ? $data['store_name'] : $this->config->get('config_name');
		$store_url = isset($data['store_url']) ? $data['store_url'] : $this->config->get('config_url');
		
		$this->load->model('localisation/country');
		
		$this->load->model('localisation/zone');
		
		$country_info = $this->model_localisation_country->getCountry($data['shipping_country_id']);
		
		if ($country_info) {
			$shipping_country = $country_info['name'];
			$shipping_address_format = $country_info['address_format'];
		} else {
			$shipping_country = '';	
			$shipping_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}	
		
		$zone_info = $this->model_localisation_zone->getZone($data['shipping_zone_id']);
		
		if ($zone_info) {
			$shipping_zone = $zone_info['name'];
		} else {
			$shipping_zone = '';			
		}	

		$country_info = $this->model_localisation_country->getCountry($data['payment_country_id']);
		
		if ($country_info) {
			$payment_country = $country_info['name'];
			$payment_address_format = $country_info['address_format'];			
		} else {
			$payment_country = '';	
			$payment_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';					
		}
	
		$zone_info = $this->model_localisation_zone->getZone($data['payment_zone_id']);
		
		if ($zone_info) {
			$payment_zone = $zone_info['name'];
		} else {
			$payment_zone = '';			
		}	

		$this->load->model('localisation/currency');

		$currency_info = $this->model_localisation_currency->getCurrencyByCode(isset($data['currency_code']) ? $data['currency_code'] : $this->config->get('config_currency'));
		
		if ($currency_info) {
			$currency_id = $currency_info['currency_id'];
			$currency_code = $currency_info['code'];
			$currency_value = $currency_info['value'];
		} else {
			$currency_id = 0;
			$currency_code = $this->config->get('config_currency');
			$currency_value = 1.00000;			
		}

		$quote_status_id = '0';
		if (isset($data['quote'])) {
			$quote_query = $this->db->query("SELECT MIN(quote_status_id) AS min_quote_status_id FROM `" . DB_PREFIX . "quote_status`");
			if ($quote_query->row) {
				$quote_status_id = $quote_query->row['min_quote_status_id'];
			}
		}
		$order_status_id = (isset($data['order_status_id'])) ? $data['order_status_id'] : '1';
      	
		$this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET invoice_prefix = '" . $this->db->escape($invoice_prefix) . "', store_id = '" . (int)$data['store_id'] . "', store_name = '" . $this->db->escape($store_name) . "', store_url = '" . $this->db->escape($store_url) . "', customer_id = '" . (int)$data['customer_id'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_country = '" . $this->db->escape($payment_country) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_zone = '" . $this->db->escape($payment_zone) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape($payment_address_format) . "', payment_custom_field = '" . $this->db->escape(isset($data['payment_custom_field']) ? serialize($data['payment_custom_field']) : '') . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', payment_code = '" . $this->db->escape($data['payment_code']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($shipping_country) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($shipping_zone) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape($shipping_address_format) . "', shipping_custom_field = '" . $this->db->escape(isset($data['shipping_custom_field']) ? serialize($data['shipping_custom_field']) : '') . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', shipping_code = '" . $this->db->escape($data['shipping_code']) . "', comment = '" . $this->db->escape($data['comment']) . (isset($data['order_id']) ? "\n".$data['order_id'] : "") . "', total = '0', affiliate_id = '0', commission = '0', marketing_id = '" . (isset($data['marketing_id']) ? (int)$data['marketing_id'] : '0') . "', tracking = '" . $this->db->escape(isset($data['tracking']) ? $data['tracking'] : '') . "', language_id = '" . (int)$this->config->get('config_language_id') . "', currency_id = '" . (int)$currency_id . "', currency_code = '" . $this->db->escape($currency_code) . "', currency_value = '" . (float)$currency_value . "', ip = '" . $this->db->escape(isset($data['ip']) ? $data['ip'] : '') . "', forwarded_ip = '" .  $this->db->escape(isset($data['forwarded_ip']) ? $data['forwarded_ip'] : '') . "', user_agent = '" . $this->db->escape(isset($data['user_agent']) ? $data['user_agent'] : '') . "', accept_language = '', date_added = NOW(), date_modified = NOW(), order_status_id = '" . $order_status_id . "', user_id = '" . (int)$data['user_id'] . "', quote_status_id = '" . $quote_status_id . "'");

		$order_id = $this->db->getLastId();
		
		// add for sync order
		if (isset($data['date_added'])) {
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET date_added = '" . $data['date_added'] . "' WHERE order_id = '" . $order_id . "'");
		}
		if (isset($data['date_modified'])) {
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET date_modified = '" . $data['date_modified'] . "' WHERE order_id = '" . $order_id . "'");
		}
		
		// add for table management begin
		if (isset($data['table_id'])) {
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET table_id = '" . $data['table_id'] . "' WHERE order_id = '" . $order_id . "'");
		}
		// add for table management end
		
      	// for new order, do not have products, for synced order, products have been handled in the controller
		// so there is no need here to process order products in this order

		// Get the total
		$total = 0;
		
		if (isset($data['totals'])) {		
      		foreach ($data['totals'] as $order_total) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($order_total['code']) . "', title = '" . $this->db->escape($order_total['title']) . "', `value` = '" . (float)$order_total['value'] . "', sort_order = '" . (int)$order_total['sort_order'] . "'");
			}
			
			$total += $order_total['value'];
		}
		// add order payment
		if (!empty($data['order_payments'])) {
			foreach ($data['order_payments'] as $order_payment) {
				$order_payment['order_id'] = $order_id;
				$this->addOrderPayment($order_payment);
			}
		}

		// Affiliate
		$affiliate_id = 0;
		$commission = 0;

		// add for Sales Person Affiliate begin
		if (isset($data['user_id'])) {
			// check if the affiliate id is associated
			$sqlQuery = "SELECT affiliate_id FROM " . DB_PREFIX . "user_affiliate WHERE user_id = '" . (int)$data['user_id'] . "'";
			$query = $this->db->query($sqlQuery);
			if ($query->row) {
				// already associated
				$affiliate_id = $query->row['affiliate_id'];
			} else {
				// get user information
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user` WHERE user_id = '" . (int)$data['user_id'] . "'");
				if ($query->row) {
					// insert record into affiliate
					$firstname = ($query->row['firstname'] == '') ? $query->row['username'] : $query->row['firstname'];
					$lastname = $query->row['lastname'];
					$password = $query->row['password'];
					if (version_compare(VERSION, '1.5.3.1', '>')) {
						$salt = $query->row['salt'];
					}
					$email = $query->row['email'];
					if (version_compare(VERSION, '1.5.3.1', '>')) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "affiliate` SET firstname = '" . $this->db->escape($firstname) . "', lastname = '" . $this->db->escape($lastname) . "', email = '" . $this->db->escape($email) . "', telephone = '', fax = '', salt = '" . $this->db->escape($salt) . "', password = '" . $this->db->escape($password) . "', company = '', address_1 = '', address_2 = '', city = '', postcode = '', country_id = '" . (int)$data['payment_country_id'] . "', zone_id = '" . (int)$data['payment_zone_id'] . "', code = '', commission = '0', tax = '', payment = '', cheque = '', paypal = '', bank_name = '', bank_branch_number = '', bank_swift_code = '', bank_account_name = '', bank_account_number = '', status = '1', approved = '1', date_added = NOW()");
					} else {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "affiliate` SET firstname = '" . $this->db->escape($firstname) . "', lastname = '" . $this->db->escape($lastname) . "', email = '" . $this->db->escape($email) . "', telephone = '', fax = '', password = '" . $this->db->escape($password) . "', company = '', address_1 = '', address_2 = '', city = '', postcode = '', country_id = '" . (int)$data['payment_country_id'] . "', zone_id = '" . (int)$data['payment_zone_id'] . "', code = '', commission = '0', tax = '', payment = '', cheque = '', paypal = '', bank_name = '', bank_branch_number = '', bank_swift_code = '', bank_account_name = '', bank_account_number = '', status = '1', approved = '1', date_added = NOW()");
					}
					$affiliate_id = $this->db->getLastId();
					// insert relationship into user_affiliate
					$this->db->query("INSERT INTO " . DB_PREFIX . "user_affiliate SET user_id = '" . (int)$data['user_id'] . "', affiliate_id = '" . (int)$affiliate_id . "'");
				}
			}
		} else {
		// add for Sales Person Affiliate end
		if (!empty($this->request->post['affiliate_id'])) {
			$affiliate_id = (int)$this->request->post['affiliate_id'];
		}
		// add for Sales Person Affiliate begin
		}
		// add for Sales Person Affiliate end
		
		if ($affiliate_id > 0 ) {
			$this->load->model('marketing/affiliate');
			
			$affiliate_info = $this->model_marketing_affiliate->getAffiliate($affiliate_id);
			
			if ($affiliate_info) {
				$commission = ($total / 100) * $affiliate_info['commission']; 
			}
		}
		
		// Update order total			 
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET total = '" . (float)$total . "', affiliate_id = '" . (int)$affiliate_id . "', commission = '" . (float)$commission . "' WHERE order_id = '" . (int)$order_id . "'"); 	
		

		$this->event->trigger('post.order.add', $order_id);

		return $order_id;
	}
	
	public function editOrder($order_id, $data) {
		$this->event->trigger('pre.order.edit', $data);

		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET invoice_prefix = '" . $this->db->escape(!empty($data['invoice_prefix']) ? $data['invoice_prefix'] : '') . "', store_id = '" . (int)$data['store_id'] . "', store_name = '" . $this->db->escape($data['store_name']) . "', store_url = '" . $this->db->escape($data['store_url']) . "', customer_id = '" . (int)$data['customer_id'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(!empty($data['custom_field']) ? serialize($data['custom_field']) : '') . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_country = '" . $this->db->escape($data['payment_country']) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_zone = '" . $this->db->escape($data['payment_zone']) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape(!empty($data['payment_address_format']) ? $data['payment_address_format'] : '') . "', payment_custom_field = '" . $this->db->escape(!empty($data['payment_custom_field']) ? serialize($data['payment_custom_field']) : '') . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', payment_code = '" . $this->db->escape($data['payment_code']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($data['shipping_country']) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape(!empty($data['shipping_address_format']) ? $data['shipping_address_format'] : '') . "', shipping_custom_field = '" . $this->db->escape(!empty($data['shipping_custom_field']) ? serialize($data['shipping_custom_field']) : '') . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', shipping_code = '" . $this->db->escape($data['shipping_code']) . "', comment = '" . $this->db->escape($data['comment']) . "', total = '" . (float)$data['total'] . "', affiliate_id = '" . (isset($data['affiliate_id']) ? $data['affiliate_id'] : '0') . "', commission = '" . (isset($data['commission']) ? (float)$data['commission'] : '0') . "', date_modified = NOW(), user_id = '', quote_status_id = '" . $data['user_id'] . "', quote_id = '" . (isset($data['quote_id']) ? $data['quote_id'] : '0') . "', table_id = '" . (!empty($data['order_table_id']) ? $data['order_table_id'] : 0) . "' WHERE order_id = '" . (int)$order_id . "'");
		if (isset($data['date_added'])) {
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET date_added = '" . $data['date_added'] . "' WHERE order_id = '" . $order_id . "'");
		}
		if (isset($data['date_modified'])) {
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET date_modified = '" . $data['date_modified'] . "' WHERE order_id = '" . $order_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "'");

		// Products
		foreach ($data['products'] as $product) {
			if ((int)$product['order_product_id'] > 0) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (int)$product['quantity'] . "', price = '" . (float)$product['price'] . "', total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "', reward = '" . (isset($product['reward']) ? (int)$product['reward'] : 0) . "'");

				$order_product_id = $this->db->getLastId();

				if (!empty($product['option'])) {
					foreach ($product['option'] as $option) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$option['product_option_id'] . "', product_option_value_id = '" . (int)$option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'");
					}
				}
			}
		}

		// Totals
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "'");

		foreach ($data['totals'] as $total) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', `value` = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
		}
		
		// order payments
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_payment WHERE order_id = '" . (int)$order_id . "'");

		if (!empty($data['order_payments'])) {
			foreach ($data['order_payments'] as $order_payment) {
				$order_payment['order_id'] = $order_id;
				$this->addOrderPayment($order_payment);
			}
		}

		$this->event->trigger('post.order.edit', $order_id);
	}
	
	public function addPosReturn($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "pos_return` SET customer_id = '" . (int)$data['customer_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', return_status_id = '" . (int)$data['return_status_id'] . "', user_id = '" . (int)$data['user_id'] . "', tax = '0', sub_total = '0', date_added = NOW(), date_modified = NOW()");
     	$pos_return_id = $this->db->getLastId();

		if (isset($data['date_added'])) {
			$this->db->query("UPDATE `" . DB_PREFIX . "pos_return` SET date_added = '" . $data['date_added'] . "' WHERE pos_return_id = '" . $pos_return_id . "'");
		}
		if (isset($data['date_modified'])) {
			$this->db->query("UPDATE `" . DB_PREFIX . "pos_return` SET date_modified = '" . $data['date_modified'] . "' WHERE pos_return_id = '" . $pos_return_id . "'");
		}
		
      	if (!empty($data['return_products'])) {
			$tax = 0;
			$sub_total = 0;
      		foreach ($data['return_products'] as $return_product) {
				$return_product['pos_return_id'] = $pos_return_id;
				$return_product['price_change'] = (float)$return_product['quantity'] * (float)$return_product['weight'] * (float)$return_product['price'];
				$return_product['tax_change'] = (int)$return_product['quantity'] * (float)$return_product['weight'] * (float)$return_product['tax'];
      			$this->addReturn($return_product);
			}
		}
		// add return payment
		if (!empty($data['return_payments'])) {
			foreach ($data['return_payments'] as $return_payment) {
				$return_payment['pos_return_id'] = $pos_return_id;
				$this->addOrderPayment($return_payment);
			}
		}
		
		return $pos_return_id;
	}
	
	public function editPosReturn($pos_return_id, $data) {
		// for now only sync data will call this method
		$this->db->query("UPDATE `" . DB_PREFIX . "pos_return` SET customer_id = '" . (int)$data['customer_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', return_status_id = '" . (int)$data['return_status_id'] . "', user_id = '" . (int)$data['user_id'] . "', tax = '0', sub_total = '0', date_modified = NOW() WHERE pos_return_id = '" . $pos_return_id . "'");
		if (isset($data['date_added'])) {
			$this->db->query("UPDATE `" . DB_PREFIX . "pos_return` SET date_added = '" . $data['date_added'] . "' WHERE pos_return_id = '" . $pos_return_id . "'");
		}
		if (isset($data['date_modified'])) {
			$this->db->query("UPDATE `" . DB_PREFIX . "pos_return` SET date_modified = '" . $data['date_modified'] . "' WHERE pos_return_id = '" . $pos_return_id . "'");
		}

		// returns
		if (!empty($data['return_products'])) {
			$return_ids = array();
			foreach ($data['return_products'] as $return_product) {
				$return_ids[] = $return_product['return_id'];
			}
			
			$return_ids = "(" . implode(",", $return_ids) . ")";
			$this->db->query("DELETE FROM `" . DB_PREFIX . "pos_return_option` WHERE return_id IN (SELECT return_id FROM `" . DB_PREFIX . "return` WHERE pos_return_id = '" . (int)$pos_return_id . "' AND return_id NOT IN " . $return_ids . ")");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "return` WHERE pos_return_id = '" . (int)$pos_return_id . "' AND return_id NOT IN " . $return_ids);

			foreach ($data['return_products'] as $return_product) {
				$return_query = $this->db->query("SELECT return_id FROM `" . DB_PREFIX . "return` WHERE return_id = '" . $return_product['return_id'] . "'");
				if ($return_query->row) {
					$sub_total = (int)$return_product['quantity'] * (float)$return_product['price'] * (float)$return_product['weight'];
					$tax = (int)$return_product['quantity'] * (float)$return_product['tax'] * (float)$return_product['weight'];
					$this->db->query("UPDATE `" . DB_PREFIX . "pos_return` SET sub_total = sub_total + " . $sub_total . ", tax = tax + " . $tax . " WHERE pos_return_id = '" . (int)$data['pos_return_id'] . "'");
					$this->db->query("UPDATE `" . DB_PREFIX . "return` SET quantity = '" . (int)$return_product['quantity'] . "', price = '" . (float)$return_product['price'] . "', tax = '" . (float)$return_product['tax'] . "' WHERE return_id = '" . $return_product['return_id'] . "'");
				} else {
					$return_product['pos_return_id'] = $pos_return_id;
					$return_product['price_change'] = (int)$return_product['quantity'] * (float)$return_product['weight'] * (float)$return_product['price'];
					$return_product['tax_change'] = (int)$return_product['quantity'] * (float)$return_product['weight'] * (float)$return_product['tax'];
					$this->addReturn($return_product);
				}
			}
		} else {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "pos_return_option` WHERE return_id IN (SELECT return_id FROM `" . DB_PREFIX . "return` WHERE pos_return_id = '" . (int)$pos_return_id . "')");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "return` WHERE pos_return_id = '" . (int)$pos_return_id . "'");
		}
		
		// return payments
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_payment` WHERE pos_return_id = '" . (int)$pos_return_id . "'");

		if (!empty($data['return_payments'])) {
			foreach ($data['return_payments'] as $return_payment) {
				$return_payment['pos_return_id'] = $pos_return_id;
				$this->addOrderPayment($return_payment);
			}
		}
	}

	// add for Hiding Order Status begin, add for user can only manage his/her own orders begin
	public function getOrders($data = array()) {
		$sql = "SELECT o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS customer, o.user_id, o.order_status_id, o.email, t.name, ";
		// add for Quotation begin
		if (!empty($data['filter_quote'])) {
			$sql .= "(SELECT qs.name FROM " . DB_PREFIX . "quote_status qs WHERE qs.quote_status_id = o.quote_status_id AND qs.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, ";
		} else {
		// add for Quotation end
		$sql .= "(SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, ";
		// add for Quotation begin
		}
		// add for Quotation end
		$sql .= "o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "pos_table` t ON o.table_id = t.table_id";

		// add for Quotation begin
		if (!empty($data['filter_quote'])) {
			// filter by quote status id
			if (isset($data['filter_quote_status_id']) && !is_null($data['filter_quote_status_id'])) {
				$sql .= " WHERE o.quote_status_id = '" . (int)$data['filter_quote_status_id'] . "'";
			} else {
				$sql .= " WHERE o.quote_status_id > '0'";
			}
		} else {
		// add for Quotation end
		if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}
		// add for table management begin
		if (isset($data['filter_table_id']) && !is_null($data['filter_table_id']) && $data['filter_table_id']) {
			$sql .= " AND o.table_id = '" . (int)$data['filter_table_id'] . "'";
		}
		// add for table management end

		// add for Hiding Order Status begin
		if (!empty($data['order_hiding_status'])) {
			$sql .= " AND o.order_status_id NOT IN (";
			foreach ($data['order_hiding_status'] as $order_status_id) {
				$sql .= "'" . $order_status_id . "',";
			}
			$sql = substr($sql, 0, -1);
			$sql .= ")";
		}
		// add for Hiding Order Status end
		if (empty($data['display_locked_orders']) && !empty($data['order_locking_status'])) {
			$sql .= " AND o.order_status_id NOT IN (";
			foreach ($data['order_locking_status'] as $order_status_id) {
				$sql .= "'" . $order_status_id . "',";
			}
			$sql = substr($sql, 0, -1);
			$sql .= ")";
		}
		
		// add for Quotation begin
		}
		// add for Quotation end
		// add for user can only manage his/her own orders begin
		if (!empty($data['filter_user_id'])) {
			$sql .= " AND o.user_id = '" . (int)$data['filter_user_id'] . "'";
		}
		// add for user can only manage his/her own orders end

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}
		
		if (isset($data['filter_total'])) {
			$sql .= " AND ROUND(o.total, 2) = '" . round((float)$data['filter_total'], 2) . "'";
		}

		$sort_data = array(
			'o.order_id',
			'o.table_id',
			'customer',
			'status',
			'o.date_added',
			'o.date_modified',
			'o.total'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY o.order_id";
		}

		if (isset($data['order']) && ($data['order'] == 'ASC')) {
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

		$query = $this->db->query($sql);

		return $query->rows;
	}
	public function getTotalOrders($data = array()) {
      	$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order`";

		// add for Quotation begin
		if (!empty($data['filter_quote'])) {
			// filter by quote status id
			if (isset($data['filter_quote_status_id']) && !is_null($data['filter_quote_status_id'])) {
				$sql .= " WHERE quote_status_id = '" . (int)$data['filter_quote_status_id'] . "'";
			} else {
				$sql .= " WHERE quote_status_id > '0'";
			}
		} else {
		// add for Quotation end
		if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
			$sql .= " WHERE order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE order_status_id > '0'";
		}
		
		// add for table management begin
		if (isset($data['filter_table_id']) && !is_null($data['filter_table_id']) && $data['filter_table_id']) {
			$sql .= " AND table_id = '" . (int)$data['filter_table_id'] . "'";
		}
		// add for table management end
		
		// add for Hiding Order Status begin
		if (!empty($data['order_hiding_status'])) {
			$sql .= " AND order_status_id NOT IN (";
			foreach ($data['order_hiding_status'] as $order_status_id) {
				$sql .= "'" . $order_status_id . "',";
			}
			$sql = substr($sql, 0, -1);
			$sql .= ")";
		}
		// add for Hiding Order Status end
		if (empty($data['display_locked_orders']) && !empty($data['order_locking_status'])) {
			$sql .= " AND order_status_id NOT IN (";
			foreach ($data['order_locking_status'] as $order_status_id) {
				$sql .= "'" . $order_status_id . "',";
			}
			$sql = substr($sql, 0, -1);
			$sql .= ")";
		}
		// add for Quotation begin
		}
		// add for Quotation end
		// add for user can only manage his/her own orders begin
		if (!empty($data['filter_user_id'])) {
			$sql .= " AND user_id = '" . (int)$data['filter_user_id'] . "'";
		}
		// add for user can only manage his/her own orders end
		// add for Quotation begin
		if (!empty($data['filter_quote'])) {
			$sql .= " AND quote_status_id > 0";
		} else {
			$sql .= " AND quote_status_id = 0";
		}
		// add for Quotation end

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}
		
		if (isset($data['filter_total'])) {
			$sql .= " AND ROUND(total,2) = '" . round((float)$data['filter_total'], 2) . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	// add for Hiding Order Status begin, add for user can only manage his/her own orders end
	// add for User as Affiliate begin
	public function addUA($data) {
		$sqlInsert = "INSERT INTO `" . DB_PREFIX . "user_affiliate` SET user_id = '" . (int)$data['user_id'] . "', affiliate_id = '" . (int)$data['affiliate_id'] . "'";
		$this->db->query($sqlInsert);
	}
	public function deleteUA($data) {
		$sqlInsert = "DELETE FROM `" . DB_PREFIX . "user_affiliate` WHERE user_id = '" . (int)$data['user_id'] . "' AND affiliate_id = '" . (int)$data['affiliate_id'] . "'";
		$this->db->query($sqlInsert);
	}
	public function getUAs() {
		$sqlQuery = $this->db->query("SELECT ua.user_id, ua.affiliate_id, u.username, a.firstname, a.lastname FROM `" . DB_PREFIX . "user_affiliate` ua LEFT JOIN `" . DB_PREFIX . "user` u ON ua.user_id = u.user_id LEFT JOIN `" . DB_PREFIX . "affiliate` a ON ua.affiliate_id = a.affiliate_id");
		return $sqlQuery->rows;
	}
	// add for User as Affiliate end
	// add for edit order address begin
	public function editOrderAddresses($order_id, $data) {
		$this->load->model('localisation/country');
		
		$this->load->model('localisation/zone');
		
		$country_info = $this->model_localisation_country->getCountry($data['shipping_country_id']);
		
		if ($country_info) {
			$shipping_country = $country_info['name'];
			$shipping_address_format = $country_info['address_format'];
		} else {
			$shipping_country = '';	
			$shipping_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}	
		
		$zone_info = $this->model_localisation_zone->getZone($data['shipping_zone_id']);
		
		if ($zone_info) {
			$shipping_zone = $zone_info['name'];
		} else {
			$shipping_zone = '';			
		}	

		$country_info = $this->model_localisation_country->getCountry($data['payment_country_id']);
		
		if ($country_info) {
			$payment_country = $country_info['name'];
			$payment_address_format = $country_info['address_format'];			
		} else {
			$payment_country = '';	
			$payment_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';					
		}
	
		$zone_info = $this->model_localisation_zone->getZone($data['payment_zone_id']);
		
		if ($zone_info) {
			$payment_zone = $zone_info['name'];
		} else {
			$payment_zone = '';			
		}			

      	$this->db->query("UPDATE `" . DB_PREFIX . "order` SET payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_country = '" . $this->db->escape($payment_country) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_zone = '" . $this->db->escape($payment_zone) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape($payment_address_format) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "',  shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($shipping_country) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($shipping_zone) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape($shipping_address_format) . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', shipping_code = '" . $this->db->escape($data['shipping_code']) . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");
	}
	// add for edit order address end
	// add for Quotation begin
	public function getQuoteStatuses() {
		$quote_status_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "quote_status` WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		return $quote_status_query->rows;
	}
	
	public function getQuoteStatus($status_id) {
		$quote_status_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "quote_status` WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' AND quote_status_id = '" . (int)$status_id . "'");
		return $quote_status_query->row;
	}
	
	public function addQuoteStatus($status) {
		$quote_status_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "quote_status` WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' AND name = '" . $this->db->escape($status) . "'");
		$status_id = 0;
		if ($quote_status_query->num_rows == 0) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "quote_status` SET language_id = '" . (int)$this->config->get('config_language_id') . "', name = '" . $this->db->escape($status) . "'");
			$status_id = $this->db->getLastId();
		}
		
		return $status_id;
	}
	
	public function renameQuoteStatus($status_id, $status) {
		$quote_status_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "quote_status` WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' AND name = '" . $this->db->escape($status) . "'");
		$result = 0;
		if ($quote_status_query->num_rows == 0) {
			$this->db->query("UPDATE `" . DB_PREFIX . "quote_status` SET name = '" . $this->db->escape($status) . "' WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' AND quote_status_id = '" . (int)$status_id . "'");
			$result = 1;
		}
		
		return $result;
	}
	
	public function deleteQuoteStatus($status_id) {
		$order_quote_query = $this->db->query("SELECT order_id FROM `" . DB_PREFIX . "order` WHERE quote_status_id = '" . (int)$status_id . "'");
		$result = array();
		if ($order_quote_query->num_rows) {
			foreach ($order_quote_query->rows as $row) {
				array_push($result, $row['order_id']);
			}
		} else {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "quote_status` WHERE quote_status_id = '" . (int)$status_id . "'");
		}
		
		return $result;
	}
	
	public function saveQuoteStatus($order_id, $quote_status_id) {
		$sql = "UPDATE `" . DB_PREFIX . "order` SET quote_status_id = '" . (int)$quote_status_id . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'";

		$this->db->query($sql);
	}
	
	public function convertQuote2Order($order_id) {
		// copy the quote to an order
		$new_order_id = $this->copyOrder($order_id);
		// update order status and quote_id
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '1', quote_status_id = '0', quote_id = '" . (int)$order_id . "' WHERE order_id='" . $new_order_id . "'");
		return $new_order_id;
	}
	
	private function copyOrder($order_id) {
		// duplicate order item
		$new_order_id = $this->duplicateRow('order', array('order_id'=>$order_id), 'order_id', null);
		// duplicate the order products
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
		if ($query->rows) {
			foreach ($query->rows as $row) {
				$order_product_id = $this->duplicateRow('order_product', array('order_product_id'=>$row['order_product_id']), 'order_product_id', array('order_id'=>array('old'=>$order_id, 'new'=>$new_order_id)));
				// duplicate the order options
				$this->duplicateRow('order_option', array('order_id'=>$order_id), 'order_option_id', array('order_id'=>array('old'=>$order_id, 'new'=>$new_order_id), 'order_product_id'=>array('old'=>$row['order_product_id'], 'new'=>$order_product_id)));
			}
		}
		// duplicate the order total
		$this->duplicateRow('order_total', array('order_id'=>$order_id), 'order_total_id', array('order_id'=>array('old'=>$order_id, 'new'=>$new_order_id)));
		
		return $new_order_id;
	}
	
	private function duplicateRow($table_name, $where, $pk, $modify) {
		// duplicate a row in the table with auto-increment id
		$sql = "CREATE TABLE pos_tmp (SELECT * FROM `" . DB_PREFIX . $table_name . "` WHERE 1=1";
		foreach ($where as $key => $value) {
			$sql .= " AND " . $key . " = '" . $value . "'";
		}
		$sql .= ")";
		$this->db->query($sql);
		
		// modify the values before insert
		if (!empty($modify)) {
			$sql = "UPDATE pos_tmp SET " . $pk . " = '0'";
			foreach ($modify as $key => $values) {
				$sql .= ", " . $key . " = '" . $values['new'] . "'";
			}
			$sql .= " WHERE 1=1";
			foreach ($modify as $key => $values) {
				$sql .= " AND " . $key . " = '" . $values['old'] . "'";
			}
			$this->db->query($sql);
		}
		
		$this->db->query("ALTER TABLE pos_tmp DROP " . $pk);
		$this->db->query("INSERT INTO `" . DB_PREFIX . $table_name . "` SELECT 0,pos_tmp.* FROM pos_tmp");
		$last_id = $this->db->getLastId();
		$this->db->query("DROP TABLE pos_tmp");
		
		return $last_id;
	}
	// add for Quotation end
	// add for Empty order control begin
	public function addAdditionalOrderStatus($status_initial, $status_deleted) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_status` WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		$hasInitial = false;
		$hasDeleted = false;
		$inital_status_id = 0;
		if (!empty($query->rows)) {
			foreach ($query->rows as $row) {
				if ($row['name'] == $status_initial) {
					$hasInitial = true;
					$inital_status_id = $row['order_status_id'];
				} elseif ($row['name'] == $status_deleted) {
					$hasDeleted = true;
				}
			}
		}
		if (!$hasInitial) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "order_status` SET language_id = '" . (int)$this->config->get('config_language_id') . "', name = '" . $this->db->escape($status_initial) . "'");
			$inital_status_id = $this->db->getLastId();
		}
		if (!$hasDeleted) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "order_status` SET language_id = '" . (int)$this->config->get('config_language_id') . "', name = '" . $this->db->escape($status_deleted) . "'");
		}
		
		return $inital_status_id;
	}
	
	public function deleteEmptyOrders($no_product, $deleted_status) {
		if ($no_product || $deleted_status) {
			$order_ids = array();
			if ($no_product) {
				$order_query = $this->db->query("SELECT order_id FROM `" . DB_PREFIX . "order` WHERE date_added <= NOW() - INTERVAL 1 DAY ORDER BY order_id DESC LIMIT 30");
				if (!empty($order_query->rows)) {
					foreach ($order_query->rows as $row) {
						array_push($order_ids, $row['order_id']);
					}
				}
			}
			if ($deleted_status) {
				$order_statuses = "('" . $this->db->escape($deleted_status) . "')";
				
				$order_query = $this->db->query("SELECT order_id FROM `" . DB_PREFIX . "order` WHERE order_status_id IN (SELECT order_status_id FROM `" . DB_PREFIX . "order_status` WHERE name IN " . $order_statuses . ")");
				if (!empty($order_query->rows)) {
					foreach ($order_query->rows as $row) {
						array_push($order_ids, $row['order_id']);
					}
				}
			}
			
			if (!empty($order_ids)) {
				foreach ($order_ids as $order_id) {
					$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0' AND order_id = '" . (int)$order_id . "'");

					if ($order_query->num_rows) {
						$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

						foreach($product_query->rows as $product) {
							$this->db->query("UPDATE `" . DB_PREFIX . "product` SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_id = '" . (int)$product['product_id'] . "' AND subtract = '1'");

							$option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");

							foreach ($option_query->rows as $option) {
								$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
							}
						}
					}
				}

				$order_ids_string = implode(",", $order_ids);
				
				$this->db->query("DELETE FROM `" . DB_PREFIX . "order` WHERE order_id IN (" . $order_ids_string . ")");
				$this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_id IN (" . $order_ids_string . ")");
				$this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_id IN (" . $order_ids_string . ")");
				$this->db->query("DELETE FROM " . DB_PREFIX . "order_voucher WHERE order_id IN (" . $order_ids_string . ")");
				$this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id IN (" . $order_ids_string . ")");
				$this->db->query("DELETE FROM " . DB_PREFIX . "order_history WHERE order_id IN (" . $order_ids_string . ")");
				$this->db->query("DELETE FROM " . DB_PREFIX . "order_fraud WHERE order_id IN (" . $order_ids_string . ")");
				$this->db->query("DELETE FROM " . DB_PREFIX . "customer_transaction WHERE order_id IN (" . $order_ids_string . ")");
				$this->db->query("DELETE FROM " . DB_PREFIX . "customer_reward WHERE order_id IN (" . $order_ids_string . ")");
				$this->db->query("DELETE FROM " . DB_PREFIX . "affiliate_transaction WHERE order_id IN (" . $order_ids_string . ")");
				$this->db->query("DELETE FROM " . DB_PREFIX . "order_payment WHERE order_id IN (" . $order_ids_string . ")");
			}
		}
	}
	// add for Empty order control end
	
	public function getProductsForBrowse($data) {
		if(isset($this->request->get['backend'])&&$this->request->get['backend']==1){
			$allow_disabled=" AND (p.status='1' OR p.status='0')  ";
		}else{
			$allow_disabled=" AND p.status='1'";
		}
		$sql = "SELECT p.product_id, p.price, p.model, p.shipping, p.tax_class_id, p.quantity, p.subtract, p.image, p.weight_price, p.weight_name, p.points, p.upc, p.sku, p.ean, p.mpn, p.quick_sale, pd.name, pd.description, pm.name AS m_name FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN `" . DB_PREFIX . "manufacturer` pm ON p.manufacturer_id = pm.manufacturer_id WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'".$allow_disabled;

		if (!empty($data['filter_name']) && !empty($data['filter_scopes'])) {
			$sql .= " AND (pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
			foreach ($data['filter_scopes'] as $filter_scope) {
				if ($filter_scope == 'model') {
					$sql .= " OR LOWER(p.model) LIKE LOWER('%" . $this->db->escape($data['filter_name']) . "%')";
				} elseif ($filter_scope == 'manufacturer') {
					$sql .= " OR LOWER(pm.name) LIKE LOWER('%" . $this->db->escape($data['filter_name']) . "%')";
				} elseif ($filter_scope == 'upc') {
					$sql .= " OR LOWER(p.upc) LIKE LOWER('%" . $this->db->escape($data['filter_name']) . "%')";
				} elseif ($filter_scope == 'sku') {
					$sql .= " OR LOWER(p.sku) LIKE LOWER('%" . $this->db->escape($data['filter_name']) . "%')";
				} elseif ($filter_scope == 'ean') {
					$sql .= " OR LOWER(p.ean) LIKE LOWER('%" . $this->db->escape($data['filter_name']) . "%')";
				} elseif ($filter_scope == 'mpn') {
					$sql .= " OR LOWER(p.mpn) LIKE LOWER('%" . $this->db->escape($data['filter_name']) . "%')";
				}
			}
			$sql .=")";
		}
		
		if (!empty($data['quick_sale'])) {
			$sql .= " AND p.quick_sale = '2'";
		}

		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.sort_order'
		);
		$sql .= " ASC";


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
		//print_r($query->rows);
		
		return $query->rows;
	}
	
	public function getBrowseProductsForReturn($order_id) {
		$order_products = array();
		
		$order_products = $this->getOrderProducts($order_id);
		if (!empty($order_products)) {
			$order_date_query = $this->db->query("SELECT DATE(date_modified) AS date_ordered FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "'");
			$this->load->model('sale/order');
			foreach ($order_products as $key => $order_product) {
				// add for product based discount begin
				if (!empty($order_product['discount'])) {
					$order_products[$key]['price'] = $order_product['discount']['discounted_price'];
					$order_products[$key]['tax'] = $order_product['discount']['discounted_tax'];
				}
				// add for product based discount end
				
				$option_data = $this->model_sale_order->getOrderOptions($order_id, $order_product['order_product_id']);
				$order_products[$key]['option'] = $option_data ? $option_data : array();
				
				$sn_query = $this->db->query("SELECT sn FROM `" . DB_PREFIX . "product_sn` WHERE order_product_id = '" . $order_product['order_product_id'] . "'");
				$order_products[$key]['sn'] = $sn_query->row ? $sn_query->row['sn'] : '';
				
				$order_products[$key]['date_ordered'] = $order_date_query->row['date_ordered'];
			}
		}

		return $order_products;
	}
	
	// add for Browse begin
	public function getCategories() {
		// get all categories
		$query = $this->db->query("SELECT c.category_id, c.parent_id, cd.name FROM `" . DB_PREFIX . "category` c LEFT JOIN `" . DB_PREFIX . "category_description` cd ON c.category_id = cd.category_id WHERE cd.language_id = '". (int)$this->config->get('config_language_id') . "'");
		return $query->rows;
	}
	public function getSubCategories($category_id) {
		// get all sub categories under the given category
		$query = $this->db->query("SELECT c.category_id, c.image, cd.name FROM `" . DB_PREFIX . "category` c LEFT JOIN `" . DB_PREFIX . "category_description` cd ON c.category_id = cd.category_id WHERE c.status = '1' AND cd.language_id = '". (int)$this->config->get('config_language_id') . "' AND c.parent_id = '" . $category_id . "'");
		return $query->rows;
	}
	public function getProducts($category_id) {
		// get all products in the given category
		// add for (update) Weight based price begin
		// $query = $this->db->query("SELECT p.product_id, p.price, p.quantity, p.image, pd.name, GROUP_CONCAT(po.option_id) as options FROM `" . DB_PREFIX . "product_to_category` pc LEFT JOIN `" . DB_PREFIX . "product` p ON pc.product_id = p.product_id LEFT JOIN `" . DB_PREFIX . "product_description` pd ON p.product_id = pd.product_id LEFT JOIN `" . DB_PREFIX . "product_option` po ON p.product_id = po.product_id WHERE pd.language_id = '". (int)$this->config->get('config_language_id') . "' AND pc.category_id = '" . $category_id . "' GROUP BY p.product_id");
		$query = $this->db->query("SELECT p.product_id, p.model, p.price, p.subtract, p.shipping, p.tax_class_id, p.quantity, p.image, p.weight_price, p.weight_name, p.points, p.quick_sale, pd.name, pd.description, p.upc, p.sku, p.ean, p.mpn, pm.name AS m_name, GROUP_CONCAT(po.option_id) as options FROM `" . DB_PREFIX . "product_to_category` pc LEFT JOIN `" . DB_PREFIX . "product` p ON pc.product_id = p.product_id LEFT JOIN `" . DB_PREFIX . "product_description` pd ON p.product_id = pd.product_id LEFT JOIN `" . DB_PREFIX . "product_option` po ON p.product_id = po.product_id LEFT JOIN `" . DB_PREFIX . "manufacturer` pm ON p.manufacturer_id = pm.manufacturer_id WHERE p.status = '1' AND pd.language_id = '". (int)$this->config->get('config_language_id') . "' AND pc.category_id = '" . $category_id . "' GROUP BY p.product_id");
		// add for (update) Weight based price end
		return $query->rows;
	}
	public function getCategoryFullPath($category_id) {
		// get the category paths in category names
		$query = $this->db->query("SELECT name FROM (SELECT cp.category_id, GROUP_CONCAT(CONCAT(cd.category_id, '|||', cd.name, '|||', c.image) ORDER BY cp.level SEPARATOR '!|||!') AS name FROM " . DB_PREFIX . "category_path cp LEFT JOIN `" . DB_PREFIX . "category` c ON (cp.path_id = c.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.category_id ORDER BY name) tmp WHERE category_id = '" . (int)$category_id . "'");
		return $query->row;
	}
	public function getCategoryFullPathOld($category_id) {
		// get the category paths in category names
		if ((int)$category_id == 0) {
			return null;
		}
		
		$query = $this->db->query("SELECT name, c.category_id, parent_id, c.image FROM `" . DB_PREFIX . "category` c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, cd.name ASC");
		
		if ($query->row['parent_id']) {
			return $this->getCategoryFullPathOld($query->row['parent_id']) . '!|||!' . $query->row['category_id'] . '|||' . $query->row['name'] . '|||' . $query->row['image'];
		} else {
			return $query->row['category_id'] . '|||' . $query->row['name'] . '|||' . $query->row['image'];
		}
	}
	public function getTotalSubItems($category_id) {
		$total_items = 0;
		
		$sub_category_total_query = $this->db->query("SELECT count(category_id) AS total FROM `" . DB_PREFIX . "category` WHERE parent_id = '" . $category_id . "'");
		if ($sub_category_total_query->row) {
			$total_items += $sub_category_total_query->row['total'];
		}
		
		$product_total_query = $this->db->query("SELECT count(p.product_id) AS total FROM `" . DB_PREFIX . "product_to_category` pc LEFT JOIN `" . DB_PREFIX . "product` p ON p.product_id = pc.product_id WHERE pc.category_id = '" . $category_id . "' AND p.status = '1'");
		if ($product_total_query->row) {
			$total_items += $product_total_query->row['total'];
		}
		
		return $total_items;
	}
	// add for Browse end
	// add for Quick sale begin
	public function updateQSProduct($data) {
		if (isset($data['product_id']) && (int)$data['product_id'] > 0) {
			$this->db->query("UPDATE `" . DB_PREFIX . "product` SET shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "', tax_class_id = '" . $this->db->escape($data['tax_class_id']) . "' WHERE product_id = '" . (int)$data['product_id'] . "'");
			$product_id = $data['product_id'];
		} else {
			// add a temporary product to the database, only product name, model, price, tax_class_id is required
			$this->db->query("INSERT INTO `" . DB_PREFIX . "product` SET model = '" . $this->db->escape($data['model']) . "', sku = '', upc = '', ean = '', jan = '', isbn = '', mpn = '', location = '', quantity = '1', minimum = '1', subtract = '0', stock_status_id = '7', date_available = NOW() - INTERVAL 1 DAY, manufacturer_id = '0', shipping = '" . (isset($data['shipping']) ? (int)$data['shipping'] : '0') . "', price = '" . (float)$data['price'] . "', points = '0', weight = '0', weight_class_id = '0', length = '0', width = '0', height = '0', length_class_id = '0', status = '1', tax_class_id = '" . $this->db->escape($data['tax_class_id']) . "', sort_order = '100', quick_sale = '2', date_added = NOW()");
			$product_id = $this->db->getLastId();
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$this->config->get('config_language_id') . "', name = '" . $this->db->escape($data['name']) . "', meta_keyword = '', meta_description = '', description = '', tag = ''");
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . QUICK_SALE_STORE_ID . "'");
		}
		return $product_id;
	}
	// add for Quick sale end
	// add for table management begin
	public function getTables($withOrder) {
		if ($withOrder) {
			$table_query = $this->db->query("SELECT t.*, o.order_status_id, o.total, o.order_id FROM " . DB_PREFIX . "pos_table t LEFT JOIN `" . DB_PREFIX . "order` o ON t.table_id = o.table_id WHERE o.order_status_id <> '5'");
		} else {
			$table_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "pos_table");
		}
		return $table_query->rows;
	}
	public function addTable($data) {
		if ($data['index'] == -1) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "pos_table SET name = '" . $this->db->escape($data['name']) . "', description = '" . $this->db->escape($data['desc']) . "', location_id = '1', coors = '" . $data['coors'] . "'");
			return $this->db->getLastId();
		} else {
			$this->db->query("UPDATE " . DB_PREFIX . "pos_table SET name = '" . $this->db->escape($data['name']) . "', description = '" . $this->db->escape($data['desc']) . "', location_id = '1', coors = '" . $data['coors'] . "' WHERE table_id = '" . $data['index'] . "'");
			return $data['index'];
		}
	}
	public function deleteTable($data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "pos_table WHERE table_id = '" . $data['index'] . "'");
	}
	public function addTableBatch($data) {
		$start = (int)$data['startNum'];
		$total = (int)$data['total'];
		$table_ids = array();
		for ($index = $start; $index < $start+$total; $index++) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "pos_table SET name = '" . $index . "', description = '', location_id = '1', coors = ''");
			$table_ids[] = array('table_id' => $this->db->getLastId(), 'name' => $index);
		}
		
		return $table_ids;
	}
	public function deleteTableBatch($data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "pos_table WHERE table_id in (" . $data['table_ids'] . ")");
	}
	public function saveOrderTableId($order_id, $table_id) {
		$sql = "UPDATE `" . DB_PREFIX . "order` SET table_id = '" . (int)$table_id . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'";

		$this->db->query($sql);
	}
	public function getOrderTableId($order_id) {
		$query = $this->db->query("SELECT table_id FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "'");
		if ($query->row) {
			return $query->row['table_id'];
		}
		
		return 0;
	}
	// add for table management end
	// add for serial no begin
	public function sellProductSN($product_sn_id, $product_sn, $order_product_id, $product_id, $order_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_sn` WHERE product_sn_id = '" . (int)$product_sn_id . "'");
		if ($query->row) {
			$this->db->query("UPDATE `" . DB_PREFIX . "product_sn` SET status = '2', order_product_id = '" . (int)$order_product_id . "', order_id = '" . (int)$order_id . "', date_modified = NOW() WHERE product_sn_id = '" . (int)$product_sn_id . "'");
			// return the list of the sns
			return $this->getSoldProductSN($order_product_id);
		} else {
			// if the serial no does not match any records
			if ($this->config->get('POS_enable_non_predefined_sn') && (int)($this->config->get('POS_enable_non_predefined_sn'))) {
				// allow non-predefined serial no
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_sn` WHERE product_id = '" . (int)$product_id . "' AND sn = '" . $this->db->escape($product_sn) . "'");
				if ($query->row) {
					$this->db->query("UPDATE `" . DB_PREFIX . "product_sn` SET status = '2', order_product_id = '" . (int)$order_product_id . "', order_id = '" . (int)$order_id . "', date_modified = NOW() WHERE product_sn_id = '" . $query->row['product_sn_id'] . "'");
				} else {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "product_sn` SET product_id = '" . (int)$product_id . "', sn = '" . $this->db->escape($product_sn) . "', status = '2', order_product_id = '" . (int)$order_product_id . "', order_id = '" . (int)$order_id . "', date_modified = NOW()");
				}
			}
		}
		return null;
	}

	public function getSoldProductSN($order_product_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_sn` WHERE order_product_id = '" . (int)$order_product_id . "'");
		return $query->rows;
	}
	public function restoreSoldProductSN($order_product_id) {
		$this->db->query("UPDATE `" . DB_PREFIX . "product_sn` SET status = '1', order_product_id = '', date_modified = NOW() WHERE order_product_id = '" . (int)$order_product_id . "'");
	}
	// add for serial no end
	// add for commission begin
	public function getCommissions($data) {
		$sql = "SELECT p.product_id, pd.name, pc.type, pc.value, pc.base FROM `" . DB_PREFIX . "product` p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_commission pc ON (p.product_id = pc.product_id)";
		
		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'"; 
		
		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}
		
		if (!empty($data['filter_product_id'])) {
			$sql .= " AND p.product_id = '" . (int)$data['filter_product_id'] . "'";
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
	public function addProductCommission($product_id, $type, $value, $base) {
		$sql = "REPLACE INTO " . DB_PREFIX . "product_commission SET product_id = '" . (int)$product_id . "', type = '" . $type . "', value = '" . (float)$value . "', base = '" . (float)$base . "', date_modified = NOW()";
		$query = $this->db->query($sql);
	}
	public function addOrderCommission($order_id, $user_id) {
		$query = $this->db->query("SELECT op.quantity, op.price, pc.* FROM " . DB_PREFIX . "order_product op LEFT JOIN " . DB_PREFIX . "product_commission pc ON (op.product_id = pc.product_id) WHERE op.order_id = '" . (int)$order_id . "'");
		$commission = 0;
		if (!empty($query->rows)) {
			foreach ($query->rows as $row) {
				if ($row['type'] == '1') {
					$commission += $row['value'];
				} elseif ($row['type'] == '2' && $row['price'] > $row['base']) {
					$commission += $row['quantity'] * ($row['price'] - $row['base']) * $row['value'] / 100;
				}
			}
		}
		$this->db->query("REPLACE INTO " . DB_PREFIX . "order_commission SET order_id = '" . (int)$order_id . "', commission = '" . $commission . "', user_id = '" . (int)$user_id . "', date_modified = NOW()");
	}
	public function deleteProductCommission($product_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_commission WHERE product_id = '" . (int)$product_id . "'");
	}
	
	public function getOrderStatusByID($order_status_id)
	{
		$query = $this->db->query("SELECT name FROM " . DB_PREFIX . "order_status WHERE order_status_id  = '" . (int)$order_status_id . "'");
		return $query->row ? $query->row['name'] : "";
	}
	
	// add for commission end
	public function getOrderStatus($order_id) {
		$status = '';
		$query = $this->db->query("SELECT name FROM `" . DB_PREFIX . "order_status` WHERE order_status_id in (SELECT order_status_id FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "') AND language_id = '" . (int)$this->config->get('config_language_id') . "'");
		if ($query->row) {
			$status = $query->row['name'];
		}
		return $status;
	}
	// add for product return begin
	public function addReturn($data) {
		// return the stock in case the product can be resold
		// $this->db->query("UPDATE `" . DB_PREFIX . "product` SET quantity = quantity + " . (int)$data['quantity'] . " WHERE product_id = '" . (int)$data['product_id'] . "'");
		// update pos_return table with tax and total
		$this->db->query("UPDATE `" . DB_PREFIX . "pos_return` SET sub_total = sub_total + " . (float)$data['price_change'] . ", tax = tax + " . (float)$data['tax_change'] . ", date_modified = NOW() WHERE pos_return_id = '" . (int)$data['pos_return_id'] . "'");
		// insert into the return table
		$weight = 1;
		$weight_name = '';
		if (!empty($data['weight_name'])) {
			$weight = $data['weight'];
			$weight_name = $data['weight_name'];
		}
		$sn = '';
		if (!empty($data['sn'])) {
			$sn = $data['sn'];
		}
		$this->db->query("INSERT INTO `" . DB_PREFIX . "return` SET order_id = '" . (int)$data['order_id'] . "', product_id = '" . (int)$data['product_id'] . "', customer_id = '" . (int)$data['customer_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', product = '" . $this->db->escape($data['product']) . "', model = '" . $this->db->escape($data['model']) . "', quantity = '" . (int)$data['quantity'] . "', opened = '" . (int)$data['opened'] . "', return_reason_id = '" . (int)$data['return_reason_id'] . "', return_action_id = '" . (int)$data['return_action_id'] . "', return_status_id = '" . (int)$data['return_status_id'] . "', comment = '" . $this->db->escape($data['comment']) . "', date_ordered = '" . $this->db->escape($data['date_ordered']) . "', date_added = NOW(), date_modified = NOW(), order_product_id = '" . (int)$data['order_product_id'] . "', pos_return_id = '" . (int)$data['pos_return_id'] . "', price = '" . (float)$data['price'] . "', tax = '" . (float)$data['tax'] . "', weight = '" . (float)$weight . "', weight_name = '" . $this->db->escape($weight_name) . "', sn = '" . $this->db->escape($sn) . "'");
		$return_id = $this->db->getLastId();
		// insert into the return option table if option is used
		if (!empty($data['order_product_id'])) {
			if ((int)$data['order_product_id'] > 0) {
				// only when order_product_id is great than 0 (not from the local sync request)
				// for the synced return option, will be taken care at the last step of sync
				$order_product_options_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_option` WHERE order_product_id = '" . (int)$data['order_product_id'] . "'");
				if ($order_product_options_query->rows) {
					foreach ($order_product_options_query->rows as $row) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "pos_return_option` SET return_id = '" . $return_id . "', product_option_id = '" . $row['product_option_id'] . "', product_option_value_id = '" . $row['product_option_value_id'] . "', name = '" . $this->db->escape($row['name']) . "', value = '" . $this->db->escape($row['value']) . "', type = '" . $this->db->escape($row['type']) . "'");
					}
				}
			}
		} elseif (!empty($data['option'])) {
			foreach ($data['option'] as $option) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "pos_return_option` SET return_id = '" . $return_id . "', product_option_id = '" . $option['product_option_id'] . "', product_option_value_id = '" . $option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', value = '" . $this->db->escape($option['value']) . "', type = '" . $this->db->escape($option['type']) . "'");
			}
		}
		
		return $return_id;
	}
	public function editReturn($data) {
		$items_in_cart = 0;
		
		if (isset($data['quantity'])) {
			$this->db->query("UPDATE `" . DB_PREFIX . "return` SET quantity = '" . (int)$data['quantity'] . "', date_modified = NOW() WHERE order_product_id = '" . (int)$data['order_product_id'] . "' AND pos_return_id = '" . (int)$data['pos_return_id'] . "'");
		}
		if (isset($data['price']) && isset($data['tax'])) {
			$this->db->query("UPDATE `" . DB_PREFIX . "return` SET price = '" . (float)$data['price'] . "', tax = '" . (float)$data['tax'] . "', date_modified = NOW() WHERE order_product_id = '" . (int)$data['order_product_id'] . "' AND pos_return_id = '" . (int)$data['pos_return_id'] . "'");
		}
		if (isset($data['action']) && $data['action'] == 'delete') {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "return` WHERE return_id = '" . (int)$data['return_id'] . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "pos_return_option` WHERE return_id = '" . (int)$data['return_id'] . "'");
		}
		
		$item_query = $this->db->query("SELECT quantity FROM `" . DB_PREFIX . "return` WHERE pos_return_id = '" . (int)$data['pos_return_id'] . "'");
		if ($item_query->rows) {
			foreach ($item_query->rows as $row) {
				$items_in_cart += $row['quantity'];
			}
		}
		
		$this->db->query("UPDATE `" . DB_PREFIX . "pos_return` SET sub_total = sub_total + (" . (float)$data['price_change'] . "), tax = tax + (" . (float)$data['tax_change'] . "), date_modified = NOW() WHERE pos_return_id = '" . (int)$data['pos_return_id'] . "'");
		
		return $items_in_cart;
	}
	public function deleteReturn($pos_return_id) {
		$return_query = $this->db->query("SELECT return_id FROM `" . DB_PREFIX . "return` WHERE pos_return_id = '" . (int)$pos_return_id . "'");
		if ($return_query->rows) {
			foreach ($return_query->rows as $row) {
				$this->db->query("DELETE FROM `" . DB_PREFIX . "return` WHERE return_id = '" . (int)$row['return_id'] . "'");
				$this->db->query("DELETE FROM `" . DB_PREFIX . "pos_return_option` WHERE return_id = '" . (int)$row['return_id'] . "'");
			}
		}
		$this->db->query("DELETE FROM `" . DB_PREFIX . "pos_return` WHERE pos_return_id = '" . (int)$pos_return_id . "'");
	}
	public function saveReturnAction($data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "return` SET return_action_id = '" . (int)$data['return_action_id'] . "', date_modified = NOW() WHERE pos_return_id = '" . (int)$data['pos_return_id'] . "'");
		return $this->db->countAffected();
	}
	public function getReturnDetails($return_id) {
		$return_details = array();
		$return_details_query = $this->db->query("SELECT r.*, rs.name AS reason FROM `" . DB_PREFIX . "return` r LEFT JOIN `" . DB_PREFIX . "return_reason` rs ON r.return_reason_id = rs.return_reason_id WHERE return_id = '" . $return_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");
		if ($return_details_query->row) {
			$return_details = $return_details_query->row;
		}
		
		$option_query = $this->db->query("SELECT name, value FROM `" . DB_PREFIX . "pos_return_option` WHERE return_id = '" . $return_id . "'");
		if ($option_query->rows) {
			$return_details['option'] = $option_query->rows;
		} else {
			$return_details['option'] = array();
		}
		
		return $return_details;
	}
	public function getReturnOrderId($pos_return_id) {
		$return_order_id = 0;
		
		$order_query = $this->db->query("SELECT order_id FROM `" . DB_PREFIX . "return` WHERE pos_return_id = '" . (int)$pos_return_id . "' LIMIT 1");
		if ($order_query->row) {
			$return_order_id = $order_query->row['order_id'];
		}
		
		return $return_order_id;
	}
	public function checkReturnedQuantity($order_product_id, $return_id) {
		$quantity = 0;
		$quantity_query = $this->db->query("SELECT SUM(quantity) AS total FROM `" . DB_PREFIX . "return` WHERE order_product_id = '" . (int)$order_product_id . "' AND return_id <> '" . (int)$return_id . "'");
		if ($quantity_query->row && $quantity_query->row['total']) {
			$quantity = (int)$quantity_query->row['total'];
		}
		return $quantity;
	}
	public function getExistingReturns($order_id) {
		$order_return_query = $this->db->query("SELECT product, CONCAT(firstname, ' ', lastname) AS customer, email, quantity, comment, date_modified FROM `" . DB_PREFIX . "return` WHERE order_id = '" . (int)$order_id . "'");
		return $order_return_query->rows;
	}
	// add for product return end
	// add for gift receipt begin
	public function addGiftReceiptOrderStatus($status_gift_receipt, $status_gift_collected) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_status` WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		$has_gift_receipt = false;
		$has_gif_collected = false;
		$gift_receipt_status_id = 0;
		$gift_collected_status_id = 0;
		if (!empty($query->rows)) {
			foreach ($query->rows as $row) {
				if ($row['name'] == $status_gift_receipt) {
					$has_gift_receipt = true;
					$gift_receipt_status_id = $row['order_status_id'];
				} elseif ($row['name'] == $status_gift_collected) {
					$has_gif_collected = true;
					$gift_collected_status_id = $row['order_status_id'];
				}
			}
		}
		if (!$has_gift_receipt) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "order_status` SET language_id = '" . (int)$this->config->get('config_language_id') . "', name = '" . $this->db->escape($status_gift_receipt) . "'");
			$gift_receipt_status_id = $this->db->getLastId();
		}
		if (!$has_gif_collected) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "order_status` SET language_id = '" . (int)$this->config->get('config_language_id') . "', name = '" . $this->db->escape($status_gift_collected) . "'");
			$gift_collected_status_id = $this->db->getLastId();
		}
		
		return array('gift_receipt_status_id'=>$gift_receipt_status_id, 'gift_collected_status_id'=>$gift_collected_status_id);
	}
	// add for gift receipt end
	// add for product based discount begin
	public function getOrderProducts($order_id) {
		$order_products = $this->db->query("SELECT op.*, p.subtract, p.tax_class_id, p.shipping, p.weight_price, p.weight_name,p.length,p.width,p.height,p.weight_class_id, p.ean, p.abbreviation, p.image, p.weight_price, p.weight_name FROM `" . DB_PREFIX . "order_product` op LEFT JOIN `" . DB_PREFIX . "product` p ON op.product_id = p.product_id WHERE order_id = '" . (int)$order_id . "' ORDER BY order_product_id")->rows;
		if (!empty($order_products)) {
			foreach ($order_products as $key => $order_product) {
				$order_product_discount_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_product_discount` WHERE order_product_id = '" . $order_product['order_product_id'] . "'");
				if ($order_product_discount_query->row) {
					$order_products[$key]['discount'] = $order_product_discount_query->row;
				}
				if (!isset($order_product['shipping'])) {
					$order_products[$key]['shipping'] = 0;
				}
			}
		}
		return $order_products;
	}
	public function deleteOrder($order_id, $is_quote=false) {
		$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0' AND order_id = '" . (int)$order_id . "'");

		if ($order_query->num_rows) {
			$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

			if (!$is_quote) {
				foreach($product_query->rows as $product) {
					$this->db->query("UPDATE `" . DB_PREFIX . "product` SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_id = '" . (int)$product['product_id'] . "' AND subtract = '1'");

					$option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");

					foreach ($option_query->rows as $option) {
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
					}
				}
			}
		}
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_product_discount` WHERE order_product_id IN (SELECT order_product_id FROM `" . DB_PREFIX . "order_product` WHERE order_id = '" . (int)$order_id . "')");

		$this->db->query("DELETE FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
      	$this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'");
      	$this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_history WHERE order_id = '" . (int)$order_id . "'");
        $res = $this->db->query("SHOW TABLES LIKE '". DB_PREFIX. "order_fraud'");
        if($res->num_rows > 0){
			$this->db->query("DELETE FROM " . DB_PREFIX . "order_fraud WHERE order_id = '" . (int)$order_id . "'");
        }
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_transaction WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_reward WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "affiliate_transaction WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_payment WHERE order_id = '" . (int)$order_id . "'");
	}
	// add for product based discount end
	public function getZonesByCountryId($country_id) {
		$zone_data = $this->cache->get('zone.' . (int)$country_id);
	
		if (!$zone_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE country_id = '" . (int)$country_id . "' AND status = '1' ORDER BY name");
	
			$zone_data = $query->rows;
			
			$this->cache->set('zone.' . (int)$country_id, $zone_data);
		}
		
		$zone_ids = '';
		$size = count($zone_data);
		if ($size > 0) {
			$count = 0;
			foreach ($zone_data as $zone) {
				$zone_ids .= $zone['zone_id'];
				if ($count < $size-1) {
					$zone_ids .= ',';
				}
				$count++;
			}
			$zone_areas = $this->db->query("SELECT * FROM `" . DB_PREFIX . "pos_area` WHERE zone_id in (" . $zone_ids . ") ORDER BY area_id DESC")->rows;
			foreach ($zone_data as $key => $zone) {
				$zone_data[$key]['areas'] = array();
				foreach ($zone_areas as $zone_area) {
					if ($zone['zone_id'] == $zone_area['zone_id']) {
						$zone_data[$key]['areas'][] = $zone_area;
					}
				}
			}
		}
	
		return $zone_data;
	}
	public function save_area($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "pos_area` SET zone_id = '" . (int)$data['zone_id'] . "', name = '" . $this->db->escape($data['area_name']) . "'");
		return $this->db->getLastId();
	}
	public function update_area($data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "pos_area` SET name = '" . $this->db->escape($data['area_name']) . "' WHERE area_id = '" . (int)$data['area_id'] . "'");
	}
	public function delete_area($data) {
		$area_ids = implode(',', $data['area_ids']);
		$this->db->query("DELETE FROM `" . DB_PREFIX . "pos_area` WHERE area_id IN (" . $area_ids . ")");
	}
	public function get_areas($zone_id) {
		$area_query = $this->db->query("SELECT area_id, name FROM `" . DB_PREFIX . "pos_area` WHERE zone_id = '" . (int)$zone_id . "'");
		return $area_query->rows;
	}
	public function get_new_order_number() {
		$query = $this->db->query("SELECT COUNT(order_id) AS order_num FROM `" . DB_PREFIX . "order` WHERE (user_id IS NULL OR user_id = '-1')");
		$new_order_num = $query->row['order_num'];
		return $new_order_num;
	}
	
	public function getOrder($order_id) {
		$order_query = $this->db->query("SELECT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = o.customer_id) AS customer FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int)$order_id . "'");

		if ($order_query->num_rows) {
			$reward = 0;
			
			$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
		
			foreach ($order_product_query->rows as $product) {
				$reward += $product['reward'];
			}			
			
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");

			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row['iso_code_2'];
				$payment_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row['code'];
			} else {
				$payment_zone_code = '';
			}
			
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");

			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row['code'];
			} else {
				$shipping_zone_code = '';
			}
		
			if ($order_query->row['affiliate_id']) {
				$affiliate_id = $order_query->row['affiliate_id'];
			} else {
				$affiliate_id = 0;
			}				
				
			$this->load->model('marketing/affiliate');
				
			$affiliate_info = $this->model_marketing_affiliate->getAffiliate($affiliate_id);
				
			if ($affiliate_info) {
				$affiliate_firstname = $affiliate_info['firstname'];
				$affiliate_lastname = $affiliate_info['lastname'];
			} else {
				$affiliate_firstname = '';
				$affiliate_lastname = '';				
			}

			$this->load->model('localisation/language');
			
			$language_info = $this->model_localisation_language->getLanguage($order_query->row['language_id']);
			
			if ($language_info) {
				$language_code = $language_info['code'];
				$language_directory = $language_info['directory'];
			} else {
				$language_code = '';
				$language_directory = '';
			}
			
			return array(
				'order_id'                => $order_query->row['order_id'],
				'invoice_no'              => $order_query->row['invoice_no'],
				'invoice_prefix'          => $order_query->row['invoice_prefix'],
				'store_id'                => $order_query->row['store_id'],
				'store_name'              => $order_query->row['store_name'],
				'store_url'               => $order_query->row['store_url'],
				'customer_id'             => $order_query->row['customer_id'],
				'customer'                => $order_query->row['customer'],
				'customer_group_id'       => $order_query->row['customer_group_id'],
				'firstname'               => $order_query->row['firstname'],
				'lastname'                => $order_query->row['lastname'],
				'telephone'               => $order_query->row['telephone'],
				'fax'                     => $order_query->row['fax'],
				'email'                   => $order_query->row['email'],
				'payment_firstname'       => $order_query->row['payment_firstname'],
				'payment_lastname'        => $order_query->row['payment_lastname'],
				'payment_company'         => $order_query->row['payment_company'],
				'payment_address_1'       => $order_query->row['payment_address_1'],
				'payment_address_2'       => $order_query->row['payment_address_2'],
				'payment_postcode'        => $order_query->row['payment_postcode'],
				'payment_city'            => $order_query->row['payment_city'],
				'payment_zone_id'         => $order_query->row['payment_zone_id'],
				'payment_zone'            => $order_query->row['payment_zone'],
				'payment_zone_code'       => $payment_zone_code,
				'payment_country_id'      => $order_query->row['payment_country_id'],
				'payment_country'         => $order_query->row['payment_country'],
				'payment_iso_code_2'      => $payment_iso_code_2,
				'payment_iso_code_3'      => $payment_iso_code_3,
				'payment_address_format'  => $order_query->row['payment_address_format'],
				'payment_method'          => $order_query->row['payment_method'],
				'payment_code'            => $order_query->row['payment_code'],				
				'shipping_firstname'      => $order_query->row['shipping_firstname'],
				'shipping_lastname'       => $order_query->row['shipping_lastname'],
				'shipping_company'        => $order_query->row['shipping_company'],
				'shipping_address_1'      => $order_query->row['shipping_address_1'],
				'shipping_address_2'      => $order_query->row['shipping_address_2'],
				'shipping_postcode'       => $order_query->row['shipping_postcode'],
				'shipping_city'           => $order_query->row['shipping_city'],
				'shipping_zone_id'        => $order_query->row['shipping_zone_id'],
				'shipping_zone'           => $order_query->row['shipping_zone'],
				'shipping_zone_code'      => $shipping_zone_code,
				'shipping_country_id'     => $order_query->row['shipping_country_id'],
				'shipping_country'        => $order_query->row['shipping_country'],
				'shipping_iso_code_2'     => $shipping_iso_code_2,
				'shipping_iso_code_3'     => $shipping_iso_code_3,
				'shipping_address_format' => $order_query->row['shipping_address_format'],
				'shipping_method'         => $order_query->row['shipping_method'],
				'shipping_code'           => $order_query->row['shipping_code'],
				'comment'                 => $order_query->row['comment'],
				'total'                   => $order_query->row['total'],
				'reward'                  => $reward,
				'order_status_id'         => $order_query->row['order_status_id'],
				'affiliate_id'            => $order_query->row['affiliate_id'],
				'affiliate_firstname'     => $affiliate_firstname,
				'affiliate_lastname'      => $affiliate_lastname,
				'commission'              => $order_query->row['commission'],
				'language_id'             => $order_query->row['language_id'],
				'language_code'           => $language_code,
				'language_directory'      => $language_directory,				
				'currency_id'             => $order_query->row['currency_id'],
				'currency_code'           => $order_query->row['currency_code'],
				'currency_value'          => $order_query->row['currency_value'],
				'ip'                      => $order_query->row['ip'],
				'forwarded_ip'            => $order_query->row['forwarded_ip'], 
				'user_agent'              => $order_query->row['user_agent'],	
				'accept_language'         => $order_query->row['accept_language'],					
				'date_added'              => $order_query->row['date_added'],
				'date_modified'           => $order_query->row['date_modified'],
				'user_id'				  => $order_query->row['user_id']
			);
		} else {
			return false;
		}
	}

	public function getReturn($pos_return_id) {
		$return_query = $this->db->query("SELECT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = pr.customer_id) AS customer FROM `" . DB_PREFIX . "pos_return` pr WHERE pr.pos_return_id = '" . (int)$pos_return_id . "'");

		if ($return_query->num_rows) {
			return array(
				'pos_return_id'           => $pos_return_id,
				'customer_id'             => $return_query->row['customer_id'],
				'customer'                => $return_query->row['customer'],
				'firstname'               => $return_query->row['firstname'],
				'lastname'                => $return_query->row['lastname'],
				'telephone'               => $return_query->row['telephone'],
				'email'                   => $return_query->row['email'],
				'sub_total'               => $return_query->row['sub_total'],
				'tax'                     => $return_query->row['tax'],
				'return_status_id'        => $return_query->row['return_status_id'],
				'date_added'              => $return_query->row['date_added'],
				'date_modified'           => $return_query->row['date_modified'],
				'user_id'				  => $return_query->row['user_id']
			);
		} else {
			return false;
		}
	}
	
	public function getReturnProducts($pos_return_id) {
		$return_products = $this->db->query("SELECT r.*, p.abbreviation, p.image FROM `" . DB_PREFIX . "return` r LEFT JOIN `" . DB_PREFIX . "product` p ON r.product_id = p.product_id WHERE pos_return_id = '" . (int)$pos_return_id . "'")->rows;
		if (!empty($return_products)) {
			foreach ($return_products as $key => $return_product) {
				$option_query = $this->db->query("SELECT name, value FROM `" . DB_PREFIX . "pos_return_option` WHERE return_id = '" . $return_product['return_id'] . "'");
				if ($option_query->rows) {
					$return_products[$key]['option'] = $option_query->rows;
				} else {
					$return_products[$key]['option'] = array();
				}
			}
		}
		return $return_products;
	}
	
	public function get_full_username($user_id, $short=false) {
		$user_query = $this->db->query("SELECT firstname, lastname FROM `" . DB_PREFIX . "user` WHERE user_id = '" . (int)$user_id . "'");
		if ($user_query->row) {
			if ($short) {
				return $user_query->row['firstname'] . '.' . strtoupper(substr($user_query->row['lastname'], 0, 1));
			} else {
				return $user_query->row['firstname'] . ' ' . $user_query->row['lastname'];
			}
		}
		return '';
	}
	
	public function getCustomers($data = array()) {
		$sql = "SELECT *, CONCAT(firstname, ' ', lastname) AS name FROM " . DB_PREFIX . "customer";

		$implode = array();

		if (!empty($data['filter_customer_id'])) {
			$implode[] = "company_id LIKE '%" . (int)($data['filter_customer_id']) . "%'";
		}

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "email LIKE '%" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (!empty($data['filter_telephone'])) {
			$implode[] = "telephone LIKE '%" . $this->db->escape($data['filter_telephone']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'name',
			'email',
			'date_added'
		);

		$sql .= " ORDER BY customer_id";

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
	
	public function getTotalCustomers($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer";

		$implode = array();

		if (!empty($data['filter_customer_id'])) {
			$implode[] = "company_id LIKE '%" . (int)($data['filter_customer_id']) . "%'";
		}
		
		

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "email LIKE '%" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (!empty($data['filter_telephone'])) {
			$implode[] = "telephone LIKE '%" . $this->db->escape($data['filter_telephone']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	
	public function editCustomer($customer_id, $data) {
		if (!isset($data['custom_field'])) {
			$data['custom_field'] = array();
		}

		// update for customer loyalty card
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET card_number = '" . $this->db->escape($data['card_number']) . "',resale_number = '" . $this->db->escape($data['resale_number']) . "',notes = '" . $this->db->escape($data['notes']) . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . (isset($data['telephone']) ? $this->db->escape($data['telephone']) : '') . "', fax = '" . (isset($data['fax']) ? $this->db->escape($data['fax']) : '') . "', custom_field = '" . (isset($data['custom_field']) ? $this->db->escape(serialize($data['custom_field'])) : '') . "', newsletter = '" . (isset($data['newsletter']) ? (int)$data['newsletter'] : 0) . "', status = '" . (isset($data['status']) ? (int)$data['status'] : 0) . "', safe = '" . (isset($data['safe']) ? (int)$data['safe'] : 0) . "' WHERE customer_id = '" . (int)$customer_id . "'");

		if (!empty($data['password'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' WHERE customer_id = '" . (int)$customer_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "'");

		if (isset($data['addresses'])) {
			foreach ($data['addresses'] as $address) {
				if (!isset($address['custom_field'])) {
					$address['custom_field'] = array();
				}

				if (!empty($address['address_id']) && (int)$address['address_id'] > 0) {
					// reserve the previous address id
					$address_id = (int)$address['address_id'];
					$this->db->query("INSERT INTO " . DB_PREFIX . "address SET address_id = '" . (int)$address['address_id'] . "', customer_id = '" . (int)$customer_id . "', firstname = '" . $this->db->escape($address['firstname']) . "', lastname = '" . $this->db->escape($address['lastname']) . "', company = '" . $this->db->escape($address['company']) . "', address_1 = '" . $this->db->escape($address['address_1']) . "', address_2 = '" . $this->db->escape($address['address_2']) . "', city = '" . $this->db->escape($address['city']) . "', postcode = '" . $this->db->escape($address['postcode']) . "', country_id = '" . (int)$address['country_id'] . "', zone_id = '" . (int)$address['zone_id'] . "', custom_field = '" . $this->db->escape(serialize($address['custom_field'])) . "'");
				} else {
					// new address
					$this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int)$customer_id . "', firstname = '" . $this->db->escape($address['firstname']) . "', lastname = '" . $this->db->escape($address['lastname']) . "', company = '" . $this->db->escape($address['company']) . "', address_1 = '" . $this->db->escape($address['address_1']) . "', address_2 = '" . $this->db->escape($address['address_2']) . "', city = '" . $this->db->escape($address['city']) . "', postcode = '" . $this->db->escape($address['postcode']) . "', country_id = '" . (int)$address['country_id'] . "', zone_id = '" . (int)$address['zone_id'] . "', custom_field = '" . $this->db->escape(serialize($address['custom_field'])) . "'");
					$address_id = $this->db->getLastId();
				}

				if (isset($address['default'])) {
					$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
				}
			}
		}
	}
	
	public function addCustomer($data, $customer_table=false, $address_table=false) {
		$is_sync = ($customer_table) ? true : false;
		
		if (!$customer_table) {
			$customer_table = DB_PREFIX . "customer";
		}
		if (!$address_table) {
			$address_table = DB_PREFIX . "address";
		}
		
		// check if the customer has already been added
		if ($is_sync) {
			$check_query = $this->db->query("SELECT customer_id FROM `" . $customer_table . "` WHERE customer_id = '" . $data['customer_id'] . "'");
			if ($check_query->row) {
				// already exists, return
				return 0;
			}
		}
				
		$this->db->query("INSERT INTO `" . $customer_table . "` SET card_number = '" . $this->db->escape($data['card_number']) . "',resale_number = '" . $this->db->escape($data['resale_number']) . "',notes = '" . $this->db->escape($data['notes']) . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', newsletter = '" . (int)$data['newsletter'] . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', status = '" . (int)$data['status'] . "', safe = '" . (isset($data['safe']) ? (int)$data['safe'] : 0) . "', date_added = NOW()");

		$customer_id = $this->db->getLastId();

		if (isset($data['addresses'])) {
			$address_customer_id = $customer_id;
			if ($is_sync) {
				// for syncing tables, use the original customer id for now
				$address_customer_id = $data['customer_id'];
			}
			
			foreach ($data['addresses'] as $address) {
				$this->db->query("INSERT INTO `" . $address_table . "` SET customer_id = '" . (int)$address_customer_id . "', firstname = '" . $this->db->escape($address['firstname']) . "', lastname = '" . $this->db->escape($address['lastname']) . "', company = '" . $this->db->escape($address['company']) . "', address_1 = '" . $this->db->escape($address['address_1']) . "', address_2 = '" . $this->db->escape($address['address_2']) . "', city = '" . $this->db->escape($address['city']) . "', postcode = '" . $this->db->escape($address['postcode']) . "', country_id = '" . (int)$address['country_id'] . "', zone_id = '" . (int)$address['zone_id'] . "', custom_field = ''");
				$address_id = $this->db->getLastId();

				if (isset($address['default'])) {
					$this->db->query("UPDATE `" . $customer_table . "` SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
				}
				
				if ($is_sync) {
					// for syncing tables, use the original address id for now
					$org_address_id = $address['address_id'];
					$this->db->query("UPDATE `" . $address_table . "` SET address_id = '" . $org_address_id . "' WHERE address_id = '" . $address_id . "'");
					if (isset($address['default'])) {
						$this->db->query("UPDATE `" . $customer_table . "` SET address_id = '" . $org_address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
					}
				}
			}
		}
		
		return $customer_id;
	}
	
	public function saveOrderComment($order_id, $comment) {
		$sql = "UPDATE `" . DB_PREFIX . "order` SET comment = '" . $this->db->escape($comment) . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'";
		$this->db->query($sql);
	}
	
	public function getReturns($data = array()) {
		$sql = "SELECT pr.pos_return_id, CONCAT(pr.firstname, ' ', pr.lastname) AS customer, pr.user_id, pr.return_status_id, pr.email, ";
		$sql .= "(SELECT rs.name FROM " . DB_PREFIX . "return_status rs WHERE rs.return_status_id = pr.return_status_id AND rs.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, ";
		$sql .= "(pr.sub_total+pr.tax) AS total, pr.date_added, pr.date_modified FROM `" . DB_PREFIX . "pos_return` pr";

		if (!empty($data['filter_return_status_id'])) {
			$sql .= " WHERE pr.return_status_id = '" . (int)$data['filter_return_status_id'] . "'";
		} else {
			$sql .= " WHERE pr.return_status_id > '0'";
		}

		// add for user can only manage his/her own orders begin
		if (!empty($data['filter_user_id'])) {
			$sql .= " AND pr.user_id = '" . (int)$data['filter_user_id'] . "'";
		}
		// add for user can only manage his/her own orders end

		if (!empty($data['filter_pos_return_id'])) {
			$sql .= " AND pr.pos_return_id = '" . (int)$data['filter_pos_return_id'] . "'";
		}

		if (!empty($data['filter_return_customer'])) {
			$sql .= " AND CONCAT(pr.firstname, ' ', pr.lastname) LIKE '%" . $this->db->escape($data['filter_return_customer']) . "%'";
		}

		if (!empty($data['filter_return_date_added'])) {
			$sql .= " AND DATE(pr.date_added) = DATE('" . $this->db->escape($data['filter_return_date_added']) . "')";
		}
		
		if (!empty($data['filter_return_date_modified'])) {
			$sql .= " AND DATE(pr.date_modified) = DATE('" . $this->db->escape($data['filter_return_date_modified']) . "')";
		}
		
		if (isset($data['filter_return_total'])) {
			$sql .= " AND ROUND(pr.sub_total+pr.tax, 2) = '" . round((float)$data['filter_return_total'], 2) . "'";
		}

		$sql .= " ORDER BY pr.pos_return_id";

		$sql .= " DESC";

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
	
	public function getTotalReturns($data = array()) {
      	$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "pos_return`";

		if (!empty($data['filter_return_status_id'])) {
			$sql .= " WHERE return_status_id = '" . (int)$data['filter_return_status_id'] . "'";
		} else {
			$sql .= " WHERE return_status_id > '0'";
		}
		
		// add for user can only manage his/her own orders begin
		if (!empty($data['filter_user_id'])) {
			$sql .= " AND user_id = '" . (int)$data['filter_user_id'] . "'";
		}
		// add for user can only manage his/her own orders end

		if (!empty($data['filter_pos_return_id'])) {
			$sql .= " AND pos_return_id = '" . (int)$data['filter_pos_return_id'] . "'";
		}

		if (!empty($data['filter_return_customer'])) {
			$sql .= " AND CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_return_customer']) . "%'";
		}

		if (!empty($data['filter_return_date_added'])) {
			$sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_return_date_added']) . "')";
		}
		
		if (!empty($data['filter_return_date_modified'])) {
			$sql .= " AND DATE(date_modified) = DATE('" . $this->db->escape($data['filter_return_date_modified']) . "')";
		}
		
		if (isset($data['filter_return_total'])) {
			$sql .= " AND ROUND(sub_total+tax,2) = '" . round((float)$data['filter_return_total'], 2) . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	// add for label print begin
	public function get_label_templates($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "pos_label_template`";
		if (!empty($data['label_template_id'])) {
			$sql .= " WHERE label_template_id = '" . (int)$data['label_template_id'] . "'";
		}
		$template_query = $this->db->query($sql);
		return $template_query->rows;
	}
	
	public function save_label_template($data) {
		$label_template_id = (int)$data['label_template_id'];
		$template_query = $this->db->query("SELECT label_template_id FROM `" . DB_PREFIX . "pos_label_template` WHERE label_template_id = '" . ((!empty($data['label_template_id'])) ? (int)$data['label_template_id'] : '0') . "'");
		if ($template_query->row) {
			$sql = "UPDATE `" . DB_PREFIX . "pos_label_template` SET ";
			foreach ($data as $key => $value) {
				$sql .= $key . " = '" . $value . "', ";
			}
			$sql = substr($sql, 0, -2);
			$sql .= " WHERE label_template_id = '" . (int)$data['label_template_id'] . "'";
			$this->db->query($sql);
		} else {
			$sql = "INSERT INTO `" . DB_PREFIX . "pos_label_template` SET ";
			foreach ($data as $key => $value) {
				if ($key != 'label_template_id') {
					$sql .= $key . " = '" . $value . "', ";
				}
			}
			$sql = substr($sql, 0, -2);
			$this->db->query($sql);
			$label_template_id = $this->db->getLastId();
		}
		
		return $label_template_id;
	}
	
	public function delete_label_template($label_template_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "pos_label_template` WHERE label_template_id = '" . (int)$label_template_id . "'");
	}
	// add for label print end
	// add for product low stock begin
	public function getTotalStocks($data = array()) {
		// get product and option level stock using sql union keyword
		$sql = "SELECT count(*) AS total FROM (SELECT lp.product_id FROM (SELECT product_id, 0 AS option_id, 0 AS option_value_id, subtract FROM `" . DB_PREFIX . "product` UNION SELECT product_id, option_id, option_value_id, subtract FROM `" . DB_PREFIX . "product_option_value`) lp LEFT JOIN `" . DB_PREFIX . "product` p ON lp.product_id = p.product_id LEFT JOIN `" . DB_PREFIX . "product_description` pd ON pd.product_id = lp.product_id WHERE pd.language_id = '" . $this->config->get('config_language_id') . "' AND lp.subtract = '1'";

		if (isset($data['filter_product_id']) && !is_null($data['filter_product_id'])) {
			$sql .= " AND lp.product_id = '" . (int)$data['filter_product_id'] . "'";
		}
		if (!empty($data['filter_product_name'])) {
			$sql .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_product_name']) . "%'";
		}
		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '%" . $this->db->escape($data['filter_model']) . "%'";
		}
		
		$sql .= ") x";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	public function getStocks($data = array()) {
		$sql1 = "SELECT p.product_id, p.model, p.quantity, pd.name AS product_name, 0 as product_option_value_id, '' as option_name, '' as option_value_name, p.low_stock FROM `" . DB_PREFIX . "product` p LEFT JOIN `" . DB_PREFIX . "product_description` pd ON p.product_id = pd.product_id WHERE pd.language_id = '" . $this->config->get('config_language_id') . "' AND p.subtract = '1'";
		$sql2 = "SELECT pov.product_id, p.model, pov.quantity, pd.name AS product_name, pov.product_option_value_id, od.name AS option_name, ovd.name AS option_value_name, pov.low_stock FROM `" . DB_PREFIX . "product_option_value` pov LEFT JOIN `" . DB_PREFIX . "product` p ON pov.product_id = p.product_id LEFT JOIN `" . DB_PREFIX . "product_description` pd ON pov.product_id = pd.product_id LEFT JOIN `" . DB_PREFIX . "option_description` od ON pov.option_id = od.option_id LEFT JOIN `" . DB_PREFIX . "option_value_description` ovd ON pov.option_value_id = ovd.option_value_id WHERE pd.language_id = '" . $this->config->get('config_language_id') . "' AND od.language_id = '" . $this->config->get('config_language_id') . "' AND ovd.language_id = '" . $this->config->get('config_language_id') . "' AND pov.subtract = '1'";
		
		if (isset($data['filter_product_id']) && !is_null($data['filter_product_id'])) {
			$sql1 .= " AND p.product_id = '" . (int)$data['filter_product_id'] . "'";
			$sql2 .= " AND pov.product_id = '" . (int)$data['filter_product_id'] . "'";
		} else if (!empty($data['filter_product_name'])) {
			$sql1 .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_product_name']) . "%'";
			$sql2 .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_product_name']) . "%'";
		}
		
		if (!empty($data['filter_model'])) {
			$sql1 .= " AND p.model LIKE '%" . $this->db->escape($data['filter_model']) . "%'";
			$sql2 .= " AND p.model LIKE '%" . $this->db->escape($data['filter_model']) . "%'";
		}
		
		// for render alert only
		if (!empty($data['filter_alert'])) {
			$sql1 .= " AND p.quantity < p.low_stock";
			$sql2 .= " AND pov.quantity < pov.low_stock";
		}
		
		$sql = $sql1 . " UNION " . $sql2 . " ORDER BY product_id";
		$sql .= ", product_option_value_id ASC";

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
	// add for product low stock end
	// add for sales report begin
	public function getSalesAvailableReportItems() {
		// Get the items from table order, order_product
		return $this->getAvailableReportItems(array('order', 'order_product'));
	}
	
	public function getStockAvailableReportItems() {
		// Get the items from table order, order_product
		return $this->getAvailableReportItems(array('product', 'manufacturer', 'product_description'));
	}
	
	private function getAvailableReportItems($tables) {
		$items = array();
		
		foreach ($tables as $table) {
			$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . $table . "`");
			foreach ($query->rows as $row) {
				$values = explode('_', $row['Field']);
				foreach ($values as $key => $value) {
					$values[$key] = strtoupper(substr($value, 0, 1)) . substr($value, 1);
				}
				$items['(' . $table. ') ' . $row['Field']] = array('type'=>$row['Type'], 'title'=>implode(' ', $values));
			}
		}
		
		return $items;
	}
	
	public function getLocationByQuantity($product_id)
	{
		$location_by_quantity = "";
		$sql = "SELECT * FROM " . DB_PREFIX . "product_to_location_quantity WHERE product_id = '" . $product_id . "'";
		$query = $this->db->query($sql);
		$result = $query->rows;
		if( $result )
		{
			foreach($result as $row)
			{
				$location_name = $row['location_id'];
				$location_quantity = $row['location_quantity'];
				$location_by_quantity .= $location_name . "/" . $location_quantity . ":::";
			}
		  $location_by_quantity = rtrim($location_by_quantity, ":::");
		  return $location_by_quantity;
		}
		
		return $location_by_quantity;
	}
	
	public function getOrderedProductData($product_id)
	{
		$sql = "SELECT p.sku, pc.groupindicator, pc.groupindicator_id, p.status, p.pos_status FROM " . DB_PREFIX . "product p LEFT JOIN  product_concat_temp_table pc ON p.product_id = pc.product_id WHERE p.product_id = '" . $product_id . "' LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getCustomerNameFromOrder($order_id)
	{
		$sql = "SELECT CONCAT(firstname, ' ', lastname) as fullname FROM " . DB_PREFIX . "order WHERE order_id = '" . $order_id . "'";
		$query = $this->db->query($sql);
		return $query->row['fullname'];
	}
	
	public function getUnitPriceOfProduct($order_id, $product_id)
	  {
		  $price = 0.00;
		  $sql = "SELECT * FROM " . DB_PREFIX . "order_product WHERE product_id = '" . $product_id . "' AND order_id = '" . $order_id . "'";
		  $query = $this->db->query($sql);
		  $result =  $query->row;
		  if($result)
		  {
			  $unit_conversion_values = $result['unit_conversion_values'];
			  $price = $result['price'];
			  if(!empty($unit_conversion_values) || $unit_conversion_values != 0){
				  $sql_unit_sql = "SELECT unit_id, unit_value_id, convert_price FROM " . DB_PREFIX . "unit_conversion_product WHERE unit_conversion_product_id = $unit_conversion_values AND product_id = $product_id";
							$res_unit_sql = $this->db->query($sql_unit_sql);
							if($res_unit_sql->num_rows >0){
								$convert_price = $res_unit_sql->row['convert_price'];
								$converted_price = $price * $convert_price;
								return number_format((float)$converted_price, 2, '.', '');
							}
			  }
		  }
		  
		  return $price;
	  }
	
	public function getExportReportData($type, $data, $start_date, $end_date) {
		// if export sales report, use order and order_product tables, if export stock report, use order_product, product and manufacturer tables
		$results = array();
		
		$data_list = array();
		$index = 0;
		foreach ($data as $field => $value) {
			if ($value['type'] == '_pos_custom_field' && isset($value['feature']) && $value['feature'] == '{p}') {
				// when a new line is required, split the fields into another array
				$index ++;
			} else {
				if (!isset($data_list[$index])) {
					$data_list[$index] = array();
				}
				
				$data_list[$index][$field] = $value;
			}
		}
		
		if ($type == 'sales') {
			foreach ($data_list as $fields) {
				$sql = "SELECT";
				
				foreach ($fields as $field => $value) {
					if ($value['type'] == '_pos_custom_field') {
						// custom field
						if (strpos($field, '{null}_') !== false) {
							$sql .= " '' AS `" . $field . "`,";
						} elseif ($value['feature'] == '{today}') {
							$sql .= "'" . date('Y-m-d') . "' AS `" . $field . "`,";
						} else {
							$sql .= " '" . substr($field, 0, strpos($field, '_'.$value['order'])) . "' AS `" . $field . "`,";
						}
					} else {
						$field_in_table = false;
						if (strpos($field, '(') !== false && strpos($field, ')') != false) {
							$index = strpos($field, ' ');
							if ($index !== false) {
								$table_name = substr($field, 0, $index);
								$table_name = substr($table_name, 1, -1);
								$field_name = substr($field, $index+1);
								
								if ($table_name == 'order') {
									if ($this->columnInTable($field_name, 'order')) {
										if (strpos($value['type'], 'date') !== false || strpos($value['type'], 'datetime') !== false || strpos($value['type'], 'timestamp') !== false) {
											$sql .= " DATE(o.`" . $field_name . "`) AS `" . $field . "`,";
										} else {
											$sql .= " o.`" . $field_name . "` AS `" . $field . "`,";
										}
										$field_in_table = true;
									}
								} elseif ($table_name = 'order_product') {
									if ($field_name == 'cost') {
										$sql .= " psn.`" . $field_name . "` AS `" . $field . "`,";
										$field_in_table = true;
									} else if ($this->columnInTable($field_name, 'order_product')) {
										if (strpos($value['type'], 'date') !== false || strpos($value['type'], 'datetime') !== false || strpos($value['type'], 'timestamp') !== false) {
											$sql .= " DATE(op.`" . $field_name . "`) AS `" . $field . "`,";
										} else {
											$sql .= " op.`" . $field_name . "` AS `" . $field . "`,";
										}
										$field_in_table = true;
									}
								}
							}
						}
						
						if (!$field_in_table) {
							// a field not in table
							$sql .= " '' AS `" . $field . "`,";
						}
					}
				}
				
				$sql .= " '1' FROM `" . DB_PREFIX . "order_product` op LEFT JOIN `" . DB_PREFIX . "order` o ON op.order_id = o.order_id LEFT JOIN `" . DB_PREFIX . "product_sn` psn ON op.order_product_id = psn.order_product_id";
				
				if ($start_date && $end_date) {
					$sql .= " WHERE DATE(o.date_modified) >= '" . $start_date . "' AND DATE(o.date_modified) <= '" . $end_date . "'";
				}
				
				$query = $this->db->query($sql);
				$results[] = $query->rows;
			}
			//CHM-WA
			foreach($results[0] as $key => $row)
			{
				$product_data = $this->getOrderedProductData($row['(order_product) product_id']);
				$customer_name = $this->getCustomerNameFromOrder($row['(order) order_id']);
				$order_status_name = $this->getOrderStatusByID($row['(order) order_status_id']);
				$unit_price = $this->getUnitPriceOfProduct($row['(order) order_id'], $row['(order_product) product_id']);
				$results[0][$key]['(order) is_pos'] = !empty($row['(order) is_pos']) ? 'POS' : 'Gempacked';
				$results[0][$key]['(order_product) sku'] = $product_data['sku'];
				$results[0][$key]['(order_product) quantity_supplied'] = !empty($row['(order_product) quantity_supplied']) ? $row['(order_product) quantity_supplied'] : 0;
				$results[0][$key]['(order_product) unit_price'] = $unit_price;
				$results[0][$key]['(order) name'] = $customer_name;
				$results[0][$key]['(order_product) groupindicator'] = $product_data['groupindicator'];
				$results[0][$key]['(order_product) groupindicator_id'] = $product_data['groupindicator_id'];
				$results[0][$key]['(order) order_status_id'] = $order_status_name;
				$results[0][$key]['(order_product) product_status'] = $product_data['status'];
				$results[0][$key]['(order_product) pos_status'] = $product_data['pos_status'];
			}
		} elseif ($type == 'stock') {
			foreach ($data_list as $fields) {
				$sql = "SELECT";
				
				foreach ($fields as $field => $value) {
					if ($value['type'] == '_pos_custom_field') {
						if (isset($value['feature']) && $value['feature'] == '{average cost}') {
							// average cost
							$sql .= " (SELECT AVG(cost) FROM `" . DB_PREFIX . "product_sn` ps WHERE ps.product_id = p.product_id) AS `" . $field . "`,";
						} elseif (strpos($field, '{null}_') !== false) {
							$sql .= " '' AS `" . $field . "`,";
						} elseif ($value['feature'] == '{today}') {
							$sql .= "'" . date('Y-m-d') . "' AS `" . $field . "`,";
						} else {
							// custom field
							$sql .= " '" . substr($field, 0, strpos($field, '_'.$value['order'])) . "' AS `" . $field . "`,";
						}
					} else {
						$field_in_table = false;
						
						if (strpos($field, '(') !== false && strpos($field, ')') != false) {
							$index = strpos($field, ' ');
							if ($index !== false) {
								$table_name = substr($field, 0, $index);
								$table_name = substr($table_name, 1, -1);
								$field_name = substr($field, $index+1);
								
								if ($table_name == 'product') {
									if ($this->columnInTable($field_name, 'product')) {
										if (strpos($value['type'], 'date') !== false || strpos($value['type'], 'datetime') !== false || strpos($value['type'], 'timestamp') !== false) {
											$sql .= " DATE(p.`" . $field_name . "`) AS `" . $field . "`,";
										} else {
											$sql .= " p.`" . $field_name . "` AS `" . $field . "`,";
										}
										$field_in_table = true;
									}
								} elseif ($table_name == 'manufacturer') {
									if ($this->columnInTable($field_name, 'manufacturer')) {
										$sql .= " m.`" . $field_name . "` AS `" . $field . "`,";
										$field_in_table = true;
									}
								} elseif ($table_name == 'product_description') {
									if ($this->columnInTable($field_name, 'product_description')) {
										$sql .= " pd.`" . $field_name . "` AS `" . $field . "`,";
										$field_in_table = true;
									}
								}
							}
						}
						
						if (!$field_in_table) {
							// a field not in table
							$sql .= " '' AS `" . $field . "`,";
						}
					}
				}
				
				$sql .= " '1' FROM `" . DB_PREFIX . "product` p LEFT JOIN `" . DB_PREFIX . "manufacturer` m ON p.manufacturer_id = m.manufacturer_id LEFT JOIN `" . DB_PREFIX . "product_description` pd ON p.product_id = pd.product_id WHERE p.quantity > 0 AND pd.language_id = '" . $this->config->get('config_language_id') . "'";
				
				$query = $this->db->query($sql);
				$results[] = $query->rows;
			}
			
			//CHM-WA
			foreach($results[0] as $key => $row)
			{
				$location_by_quantity = $this->getLocationByQuantity($row['(product) product_id']);
				if(!empty($location_by_quantity))
				{
					$results[0][$key]['(product) location_by_quantity'] = $location_by_quantity;
				}
			}
		}
		
		return $results;
	}
	
	private function columnInTable($column, $table) {
		$column_query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . $table . "` LIKE '" . $column . "'");
		if ($column_query->num_rows > 0) {
			return true;
		}
		return false;
	}
	// add for sales report end
	public function unsetOnlineOrderIndicator($order_id) {
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET user_id = '-2' WHERE (user_id IS NULL OR user_id = '-1') AND order_id = '" . (int)$order_id . "'");
	}
	
	public function getCustomerGroup($customer_group_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer_group cg LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cg.customer_group_id = cgd.customer_group_id) WHERE cg.customer_group_id = '" . (int)$customer_group_id . "' AND cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getCustomerGroups($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "customer_group cg LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cg.customer_group_id = cgd.customer_group_id) WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$sort_data = array(
			'cgd.name',
			'cg.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY cgd.name";
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
	
	public function getCustomer($customer_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row;
	}

	public function approve($customer_id) {
		$customer_info = $this->getCustomer($customer_id);

		if ($customer_info) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET approved = '1' WHERE customer_id = '" . (int)$customer_id . "'");

			$this->load->language('mail/customer');

			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore($customer_info['store_id']);

			if ($store_info) {
				$store_name = $store_info['name'];
				$store_url = $store_info['url'] . 'index.php?route=account/login';
			} else {
				$store_name = $this->config->get('config_name');
				$store_url = HTTP_CATALOG . 'index.php?route=account/login';
			}

			$message  = sprintf($this->language->get('text_approve_welcome'), html_entity_decode($store_name, ENT_QUOTES, 'UTF-8')) . "\n\n";
			$message .= $this->language->get('text_approve_login') . "\n";
			$message .= $store_url . "\n\n";
			$message .= $this->language->get('text_approve_services') . "\n\n";
			$message .= $this->language->get('text_approve_thanks') . "\n";
			$message .= html_entity_decode($store_name, ENT_QUOTES, 'UTF-8');

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
			$mail->setSubject(sprintf($this->language->get('text_approve_subject'), html_entity_decode($store_name, ENT_QUOTES, 'UTF-8')));
			$mail->setText($message);
			$mail->send();
		}
	}

	public function getAddresses($customer_id) {
		$address_data = array();

		$query = $this->db->query("SELECT address_id FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "'");

		foreach ($query->rows as $result) {
			$address_info = $this->getAddress($result['address_id']);

			if ($address_info) {
				$address_data[$result['address_id']] = $address_info;
			}
		}

		return $address_data;
	}

	public function getAddress($address_id) {
		$address_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)$address_id . "'");

		if ($address_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$address_query->row['country_id'] . "'");

			if ($country_query->num_rows) {
				$country = $country_query->row['name'];
				$iso_code_2 = $country_query->row['iso_code_2'];
				$iso_code_3 = $country_query->row['iso_code_3'];
				$address_format = $country_query->row['address_format'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$address_query->row['zone_id'] . "'");

			if ($zone_query->num_rows) {
				$zone = $zone_query->row['name'];
				$zone_code = $zone_query->row['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}

			return array(
				'address_id'     => $address_query->row['address_id'],
				'customer_id'    => $address_query->row['customer_id'],
				'firstname'      => $address_query->row['firstname'],
				'lastname'       => $address_query->row['lastname'],
				'company'        => $address_query->row['company'],
				'address_1'      => $address_query->row['address_1'],
				'address_2'      => $address_query->row['address_2'],
				'postcode'       => $address_query->row['postcode'],
				'city'           => $address_query->row['city'],
				'zone_id'        => $address_query->row['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country_id'     => $address_query->row['country_id'],
				'country'        => $country,
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format,
				'custom_field'   => unserialize($address_query->row['custom_field'])
			);
		}
	}
	
	public function deleteCustomer($customer_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "'");
	}
	
	 public function getDiscountPercent($discount_quantity=null, $product_id) {
       /* if ($this->customer->isLogged()) {
            $customer_group_id = $this->customer->getCustomerGroupId();
        } else {*/
            $customer_group_id = $this->config->get('config_customer_group_id');
        //}
        //if(is_null($discount_quantity) && empty($discount_quantity)) {
           // $discount_quantity=1;
        /*}
        if($discount_quantity>0 && $discount_quantity<1) {*/
           // $discount_quantity =1;
        //}
       
        $m = "SELECT
                " . DB_PREFIX . "product_discount.price as discount,
                " . DB_PREFIX . "product.price as base_price 

            FROM
                " . DB_PREFIX . "product_discount
            LEFT JOIN " . DB_PREFIX . "product ON " . DB_PREFIX . "product_discount.product_id = " . DB_PREFIX . "product.product_id
            WHERE
                " . DB_PREFIX . "product_discount.product_id = " . $product_id . "
            AND customer_group_id = ".$customer_group_id."
            AND " . DB_PREFIX . "product_discount.quantity <= " . $discount_quantity . "
            AND (
                (
                    date_start = '0000-00-00'
                    OR date_start < NOW()
                )
                AND (
                    date_end = '0000-00-00'
                    OR date_end > NOW()
                )
            )
            ORDER BY
                " . DB_PREFIX . "product_discount.quantity DESC,
                " . DB_PREFIX . "product_discount.priority ASC,
                " . DB_PREFIX . "product_discount.price ASC
            LIMIT 1";
        $query = $this->db->query($m);
        $discount_result = $query->row;
        if (isset($discount_result['discount'])) {
            $discount_result['base_price']=($discount_result['base_price']==0)?1:$discount_result['base_price'];
            return ($discount_result['discount'] / $discount_result['base_price']);
        } else {
            return 1;
        }
    }

    /**
     * 
     * @param type $pid
     * @param type $quantity
     * @param type $unit_mult
     * @return type
     */
    public function geProductCalculatedPrice($product_id, $quantity = 1, $unit_mult = 1, $option_details=null) {
        $base_price = $this->getMainPrice($product_id);
		
        if(!empty($unit_mult) && !is_null($unit_mult)) {
            $discount_qty = $quantity * $unit_mult;
            $discount_multiplier = $this->getDiscountPercent($discount_qty, $product_id);
            $final_price = $discount_multiplier * $base_price * $unit_mult;
        } else {
            $discount_multiplier = $this->getDiscountPercent($quantity, $product_id);
            $final_price = $discount_multiplier * $base_price ;
           
        }
        if($option_details !=NULL && !empty($option_details) && $option_details['option_id'] !=5) {
            $final_price = $final_price +$option_details['price'];
        }
        return $final_price;
    }
    /**
     * Function geProductCalculatedPrice
     * @param type $pid
     * @return type
     */
    public function geProductCalculatedPriceWithouDiscount($pid, $quantity = null) {
        $opional_price = $this->getOptionPrice($pid);
        $discounts = $this->getProductDiscountsSingle($pid, $quantity);
        $main_price = $this->getMainPrice($pid);
        if ($main_price == 0) {
            $opional_price = $this->getOptionPrice($pid, 0);
            $opional_price_market = $this->getOptionPrice_market($pid);
            $opional_price = $opional_price * $opional_price_market;
            $rPrice = $opional_price;
            return round($rPrice, 2);
        }
        $main_price == 0 ? $main_price = 1 : '';
        $discounts = $this->getProductDiscountsSingle($pid, $quantity);
        $discount_price = (isset($discounts['price'])) ? $discounts['price'] : $main_price;
        $final_price = $main_price + $opional_price;
        return round($final_price, 2);
    }

    private function getOptionPrice_market($pid){
        $get_option = $this->db->query("SELECT `market_price` FROM `oc_option_value` WHERE `option_id` = '5' LIMIT 1");
        return $get_option->row['market_price'];
    }
    /**
     * Function getOptionPrice
     * @param type $pid
     * @return boolean
     */
    public function getOptionPrice($pid, $metal_type = 1, $option_details=null) {
        if($option_details!=null && $option_details['option_id'] == '20'){
            return $option_details['price'];
        }else{
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
            if (!empty($query->row)) {
                return $query->row['price'];
            } else {
                return 0;
            }
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
    }

    /**
     * getMainPrice
     * @param type $pid
     * @return type
     */
    public function getMainPrice($pid) {
        $main_price = $this->db->query("select " . DB_PREFIX . "product.price from " . DB_PREFIX . "product where " . DB_PREFIX . "product.product_id = $pid");
        return $main_price->row['price'];
    }

    /**
     * getProductDiscountsSingle
     * @param type $product_id
     * @return type
     */
    public function getProductDiscountsSingle($product_id, $quantity = 1) {
        if ($this->customer->isLogged()) {
            $customer_group_id = $this->customer->getCustomerGroupId();
        } else {
            $customer_group_id = 1;
        }

        if ($quantity == NULL) {
            $quantity = 1;
        }
        $quer = "SELECT * FROM " . DB_PREFIX . "product_discount WHERE "
                . "product_id = '" . (int) $product_id . "' AND"
                . " customer_group_id = '" . (int) $customer_group_id . "' AND"
                . "  " . DB_PREFIX . "product_discount.quantity <= " . $quantity . " "
                . "AND ((date_start = '0000-00-00' OR date_start < NOW()) "
                . "AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER"
                . " BY quantity DESC, priority ASC, price ASC LIMIT 1";
        $query = $this->db->query($quer);
        return $query->row;
    }
	
	    /*
     * Takes two parameters 
     * @param option price like 0.345
     * @param market price as mp like 23,24,20
     * Returns option price
     */

    public function caclOptionPrice($price, $mp) {
        $result = 0;
        if ($mp > 0) {
            $result = $price * $mp;
        } else {
            $result = $price;
        }
        return $result;
    }

    /*
     * Takes two parameters 
     * @param discounted price like 10% of 126.99 = 114.2910
     * @param original price 126.99
     * Returns discount percent in decimals like 10% = 1-0.10 = 0.9
     */

    public function calcMetalTypeDiscount($price, $originalPrice) {
        $originalPrice == 0 ? $originalPrice = 1 : '';
        $discount = 1 - (($originalPrice - $price) / $originalPrice);
        return $discount;
    }


                
                    public function roundUp( $value, $precision ) { 
                        $pow = pow ( 10, $precision ); 
                        return ( ceil ( $pow * $value ) + ceil ( $pow * $value - ceil ( $pow * $value ) ) ) / $pow; 
  }
  
  public function getProductOptions($product_id, $stat = 0) {
        $product_option_data = array();	
		//echo '<br>';
		//echo "SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int) $product_id . "' AND od.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY o.sort_order";
        $product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int) $product_id . "' AND od.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY o.sort_order");

        foreach ($product_option_query->rows as $product_option) {
            if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
                $product_option_value_data = array();

                if($stat == 1){
                    $product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int) $product_id . "' AND pov.product_option_id = '" . (int) $product_option['product_option_id'] . "' AND ovd.language_id = '" . (int) $this->config->get('config_language_id') . "'");
                } else {
                    $product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int) $product_id . "' AND pov.product_option_id = '" . (int) $product_option['product_option_id'] . "' AND ovd.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY ov.sort_order");
                }

                foreach ($product_option_value_query->rows as $product_option_value) {
                    $product_option_value_data[] = array(
					
						'default' => $product_option_value['default'], //Q: Default Product Option
			
                        'product_option_value_id' => $product_option_value['product_option_value_id'],
                        'option_value_id' => $product_option_value['option_value_id'],
                        'name' => $product_option_value['name'],
                        'image' => $product_option_value['image'],
                        'quantity' => $product_option_value['quantity'],
                        'subtract' => $product_option_value['subtract'],
                        'price' => $this->caclOptionPrice($product_option_value['price'], $product_option_value['market_price']),
                        'price_prefix' => $product_option_value['price_prefix'],
                        'weight' => $product_option_value['weight'],
                        'weight_prefix' => $product_option_value['weight_prefix']
                    );
                }

                $product_option_data[] = array(
                    'product_option_id' => $product_option['product_option_id'],
                    'option_id' => $product_option['option_id'],
                    'name' => $product_option['name'],
                    'metal_type' => $product_option['metal_type'],
                    'type' => $product_option['type'],
                    'option_value' => $product_option_value_data,
                    'required' => $product_option['required']
                );
            } else {
                $product_option_data[] = array(
                    'product_option_id' => $product_option['product_option_id'],
                    'option_id' => $product_option['option_id'],
                    'name' => $product_option['name'],
                    'type' => $product_option['type'],
                    'option_value' => $product_option['option_value'],
                    'required' => $product_option['required']
                );
            }
        }
		
        return $product_option_data;
    }
	
	public function getGroupedProductName($product_id=null) {
                    if($product_id) {
                        $query="SELECT name FROM " . DB_PREFIX . "product_description WHERE product_id='".$product_id."'";
                        $result= $this->db->query($query);
                        
                        if(!empty($result)) {
                            return $result->row['name'];
                        }
                    }
                }
                
}
?>