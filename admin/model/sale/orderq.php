<?php
class ModelSaleOrderq extends Model {
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
				'email'                   => $order_query->row['email'],
				'telephone'               => $order_query->row['telephone'],
				'fax'                     => $order_query->row['fax'],
				'custom_field'            => json_decode($order_query->row['custom_field'], true),
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
				'payment_custom_field'    => json_decode($order_query->row['payment_custom_field'], true),
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
				'shipping_custom_field'   => json_decode($order_query->row['shipping_custom_field'], true),
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
				'payment_address_id'      => $order_query->row['payment_address_id'],
				'shipping_address_id'     => $order_query->row['shipping_address_id'],
				'order_type'     		  => $order_query->row['order_type'],
				'is_pos'				  => $order_query->row['is_pos'],
				'authorization_amount'    => $order_query->row['authorization_amount']
			);
		} else {
			return;
		}
	}

	public function addOrderShippingIfNotExists($order_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' AND code = 'shipping'");
		$shipping = $query->row ? true : false;
		if($shipping)
		{
			return true;
		} else {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', code = 'shipping', title = 'Shipping', sort_order = 3");
			return true;
		}

	}

	public function getOrders($data = array()) {
		$sql = "SELECT o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, o.shipping_code, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . "order` o";

		if (isset($data['filter_order_status'])) {
			$implode = array();

			$order_statuses = explode(',', $data['filter_order_status']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "o.order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}

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

		if (!empty($data['filter_total'])) {
			$sql .= " AND o.total = '" . (float)$data['filter_total'] . "'";
		}

		 $sql .= " AND o.customer_id != 0 ";

		$sort_data = array(
			'o.order_id',
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

	public function getOrderProducts($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "' ORDER BY order_item_sort_order ASC");

		return $query->rows;
	}

	public function getSimpleInvoiceOrderProduct($order_id)
	{
		$products = $this->getOrderProducts($order_id);

		foreach( $products as $k => $product )
		{
			$parent_category_name = $this->getParentCategoryName($product['product_id']);
			if($product['unit_conversion_values'] > 0)
			{
				$unit_name = $this->getUnitNameAgaintUnitValue($product['unit_conversion_values']);
			} else {
				$unit_name = $this->getProductPluralUnit($product['product_id']);
			}
			
			$products[$k]['parent_category_name'] = $parent_category_name;
			$products[$k]['unit_name'] = $unit_name;
		}

		return $products;
	}
 
	public function getProductPluralUnit($product_id)
	{
		$query = $this->db->query("SELECT unit_plural FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");

		return $query->row['unit_plural'];
	}

	public function getUnitNameAgaintUnitValue($unit_conversion_product_id)
	{
		$query = $this->db->query("SELECT unit_id, unit_value_id FROM " . DB_PREFIX . "unit_conversion_product WHERE unit_conversion_product_id = '" . (int)$unit_conversion_product_id . "'");

		$unit_id = $query->row['unit_id'];
		$unit_value_id = $query->row['unit_value_id'];

		$query = $this->db->query("SELECT name FROM " . DB_PREFIX . "unit_conversion_value WHERE unit_id = '" . (int)$unit_id . "' AND unit_value_id = '" . (int)$unit_value_id . "'");

		$name = $query->row ? $query->row['name'] : "";

		return $name;
	}

	public function getParentCategoryName($product_id)
	{

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

		$categories = $query->rows;
		$cat_string = "";
		foreach( $categories as $category )
		{
			$cat_string .= $category['category_id'] . ",";
		}

		$cat_string = rtrim($cat_string,",");

		$query = $this->db->query("SELECT path_id FROM " . DB_PREFIX . "category_path WHERE category_id IN (" . $cat_string . ") AND level = 0");

		$cat_id = $query->row['path_id'];

		$query = $this->db->query("SELECT name FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int)$cat_id . "' AND language_id = 1");

		$name = $query->row ? $query->row['name'] : "";

		return $name;
	}

	public function checkOrderHasLocationQuantity($products)
	{
		if( $products )
		{
			$product_ids = "";
			foreach( $products as $product)
			{
				$product_ids .= $product['product_id'] . ",";
			}

			$product_ids = rtrim($product_ids,",");
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_location_quantity WHERE product_id IN (" . $product_ids . ")");

			return $query->rows ? true : false;
		}

		return false;
	}
	
	public function getConvertedPrice($unit_conversion_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "unit_conversion_product WHERE unit_conversion_product_id = '" . (int)$unit_conversion_id . "'");

		return $query->row ? $query->row['convert_price'] : 1;
	}
	
	
	public function getOrderOptions($order_id, $order_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");

		return $query->rows;
	}

	public function getOrderVouchers($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'");

		return $query->rows;
	}

	public function getOrderVoucherByVoucherId($voucher_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_voucher` WHERE voucher_id = '" . (int)$voucher_id . "'");

		return $query->row;
	}

	public function getOrderTotals($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order");

		return $query->rows;
	}

	public function getTotalOrders($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order`";

		if (isset($data['filter_order_status'])) {
			$implode = array();

			$order_statuses = explode(',', $data['filter_order_status']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} else {
			$sql .= " WHERE order_status_id > '0'";
		}

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

		if (!empty($data['filter_total'])) {
			$sql .= " AND total = '" . (float)$data['filter_total'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalOrdersByStoreId($store_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE store_id = '" . (int)$store_id . "'");

		return $query->row['total'];
	}

	public function getTotalOrdersByOrderStatusId($order_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id = '" . (int)$order_status_id . "' AND order_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalOrdersByProcessingStatus() {
		$implode = array();

		$order_statuses = $this->config->get('config_processing_status');

		foreach ($order_statuses as $order_status_id) {
			$implode[] = "order_status_id = '" . (int)$order_status_id . "'";
		}

		if ($implode) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE " . implode(" OR ", $implode));

			return $query->row['total'];
		} else {
			return 0;
		}
	}

	public function getTotalOrdersByCompleteStatus() {
		$implode = array();

		$order_statuses = $this->config->get('config_complete_status');

		foreach ($order_statuses as $order_status_id) {
			$implode[] = "order_status_id = '" . (int)$order_status_id . "'";
		}

		if ($implode) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE " . implode(" OR ", $implode) . "");

			return $query->row['total'];
		} else {
			return 0;
		}
	}

	public function getTotalOrdersByLanguageId($language_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE language_id = '" . (int)$language_id . "' AND order_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalOrdersByCurrencyId($currency_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE currency_id = '" . (int)$currency_id . "' AND order_status_id > '0'");

		return $query->row['total'];
	}

	public function createInvoiceNo($order_id) {
		$order_info = $this->getOrder($order_id);

		if ($order_info && !$order_info['invoice_no']) {
			$query = $this->db->query("SELECT MAX(invoice_no) AS invoice_no FROM `" . DB_PREFIX . "order` WHERE invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "'");

			if ($query->row['invoice_no']) {
				$invoice_no = $query->row['invoice_no'] + 1;
			} else {
				$invoice_no = 1;
			}

			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET invoice_no = '" . (int)$invoice_no . "', invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "' WHERE order_id = '" . (int)$order_id . "'");

			return $order_info['invoice_prefix'] . $invoice_no;
		}
	}

	public function getOrderHistories($order_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT oh.date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "order_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int)$order_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalOrderHistories($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_history WHERE order_id = '" . (int)$order_id . "'");

		return $query->row['total'];
	}

	public function getTotalOrderHistoriesByOrderStatusId($order_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_history WHERE order_status_id = '" . (int)$order_status_id . "'");

		return $query->row['total'];
	}

	public function getEmailsByProductsOrdered($products, $start, $end) {
		$implode = array();

		foreach ($products as $product_id) {
			$implode[] = "op.product_id = '" . (int)$product_id . "'";
		}

		$query = $this->db->query("SELECT DISTINCT email FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id) WHERE (" . implode(" OR ", $implode) . ") AND o.order_status_id <> '0' LIMIT " . (int)$start . "," . (int)$end);

		return $query->rows;
	}

	public function getTotalEmailsByProductsOrdered($products) {
		$implode = array();

		foreach ($products as $product_id) {
			$implode[] = "op.product_id = '" . (int)$product_id . "'";
		}

		$query = $this->db->query("SELECT DISTINCT email FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id) WHERE (" . implode(" OR ", $implode) . ") AND o.order_status_id <> '0'");

		return $query->row['total'];
	}

	public function getLatestOrders($customer_id) {
		$query = $this->db->query("SELECT DISTINCT order_id FROM " . DB_PREFIX . "order WHERE customer_id = '" . (int)$customer_id . "' ORDER BY order_id DESC LIMIT 20");
		return $query->rows;
	}

	public function getPersonalDetails($customer_id) {
		$implode = array();

		foreach ($products as $product_id) {
			$implode[] = "op.product_id = '" . (int)$product_id . "'";
		}

		$query = $this->db->query("SELECT DISTINCT email FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id) WHERE (" . implode(" OR ", $implode) . ") AND o.order_status_id <> '0'");

		return $query->row['total'];
	}

	public function savepdetails($customer_id, $data) {
		if (!isset($data['custom_field'])) {
			$data['custom_field'] = array();
		}

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? json_encode($data['custom_field']) : '') . "' WHERE customer_id = '" . (int)$customer_id . "'");
	}

	public function editAddress($address_id, $customer_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "address SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company = '" . $this->db->escape($data['company']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', city = '" . $this->db->escape($data['city']) . "', zone_id = '" . (int)$data['zone_id'] . "', country_id = '" . (int)$data['country_id'] . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? json_encode($data['custom_field']) : '') . "' WHERE address_id  = '" . (int)$address_id . "' AND customer_id = '" . (int)$customer_id . "'");
	}

	public function getCustomFields($customer_group_id = 0) {
		$custom_field_data = array();

		if (!$customer_group_id) {
			$custom_field_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custom_field` cf LEFT JOIN `" . DB_PREFIX . "custom_field_description` cfd ON (cf.custom_field_id = cfd.custom_field_id) WHERE cf.status = '1' AND cfd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cf.status = '1' ORDER BY cf.sort_order ASC");
		} else {
			$custom_field_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custom_field_customer_group` cfcg LEFT JOIN `" . DB_PREFIX . "custom_field` cf ON (cfcg.custom_field_id = cf.custom_field_id) LEFT JOIN `" . DB_PREFIX . "custom_field_description` cfd ON (cf.custom_field_id = cfd.custom_field_id) WHERE cf.status = '1' AND cfd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cfcg.customer_group_id = '" . (int)$customer_group_id . "' ORDER BY cf.sort_order ASC");
		}

		foreach ($custom_field_query->rows as $custom_field) {
			$custom_field_value_data = array();

			if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio' || $custom_field['type'] == 'checkbox') {
				$custom_field_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "custom_field_value cfv LEFT JOIN " . DB_PREFIX . "custom_field_value_description cfvd ON (cfv.custom_field_value_id = cfvd.custom_field_value_id) WHERE cfv.custom_field_id = '" . (int)$custom_field['custom_field_id'] . "' AND cfvd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY cfv.sort_order ASC");

				foreach ($custom_field_value_query->rows as $custom_field_value) {
					$custom_field_value_data[] = array(
						'custom_field_value_id' => $custom_field_value['custom_field_value_id'],
						'name'                  => $custom_field_value['name']
					);
				}
			}

			$custom_field_data[] = array(
				'custom_field_id'    => $custom_field['custom_field_id'],
				'custom_field_value' => $custom_field_value_data,
				'name'               => $custom_field['name'],
				'type'               => $custom_field['type'],
				'value'              => $custom_field['value'],
				'location'           => $custom_field['location'],
				'required'           => empty($custom_field['required']) || $custom_field['required'] == 0 ? false : true,
				'sort_order'         => $custom_field['sort_order']
			);
		}

		return $custom_field_data;
	}

	public function getProductManufacturerID($product_id)
	{
		$query = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "product WHERE product_id ='".$product_id."'");

		return $query->row ? $query->row['manufacturer_id'] : 0;
	}

	public function createBackOrder($request, $product_id, $customer_id, $order_id)
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
	  
		if( $customer_id > 0 )
		{
			$customer_info = $this->getCustomerByID($customer_id);
		} else {
			$customer_info = $this->getCustomerByOrderID($order_id); 
		}
		
		$this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET invoice_prefix = '', store_id = '".$customer_info['store_id']."', store_name = 'Gempacked', store_url = '".$store_url."', customer_id = '".$customer_info['customer_id']."', customer_group_id = '".$customer_info['customer_group_id']."', firstname = '".$customer_info['firstname']."', lastname = '".$customer_info['lastname']."', email = '".$customer_info['email']."', telephone = '".$customer_info['telephone']."', fax = '".$customer_info['fax']."', custom_field = '', payment_firstname = '".$customer_info['firstname']."', payment_lastname = '".$customer_info['lastname']."', payment_company = '', payment_address_1 = '".$customer_info['address_1']."', payment_address_2 = '".$customer_info['address_2']."', payment_city = '".$customer_info['city']."', payment_postcode = '".$customer_info['postcode']."', payment_country = '', payment_country_id = '".$customer_info['country_id']."', payment_zone = '', payment_zone_id = '".$customer_info['zone_id']."', payment_address_format = '', payment_custom_field = '', payment_method = 'Bill Me Later', payment_code = 'cheque', shipping_firstname = '".$customer_info['firstname']."', shipping_lastname = '".$customer_info['lastname']."', shipping_company = '".$customer_info['company']."', shipping_address_1 = '".$customer_info['address_1']."', shipping_address_2 = '".$customer_info['address_2']."', shipping_city = '".$customer_info['city']."', shipping_postcode = '".$customer_info['postcode']."', shipping_country = '', shipping_country_id = '".$customer_info['country_id']."', shipping_zone = '', shipping_zone_id = '".$customer_info['zone_id']."', shipping_address_format = '', shipping_custom_field = '', shipping_method = 'In-Store Pick up', shipping_code = 'xshippingpro.xshippingpro2', comment = '', total = '', affiliate_id = '',order_status_id='1', commission = '', marketing_id = '', tracking = '', language_id = '', currency_id = '',currency_value='1', currency_code = 'USD', order_type='3', manufacturer_id = '0', date_added = NOW(), date_modified = NOW()");

		$backorder_id = $this->db->getLastId();

		$product_data = $this->getOrderProductData($order_id, $product_id);  
		if( $product_data )
		{
			$total = $product_data['price'] * $product_data['quantity'];
			$this->db->query("INSERT INTO ".DB_PREFIX."order_product SET order_id='".$backorder_id."',product_id='".$product_id."' ,name='".$this->db->escape($product_data['name'])."',model='".$this->db->escape($product_data['model'])."',price='".$product_data['price']."',quantity='".$product_data['quantity']."',default_vendor_unit='".$this->db->escape($product_data['default_vendor_unit'])."',total='".$total."'");
			
			$this->db->query("UPDATE ".DB_PREFIX."order set total='".$total."', order_type='3' WHERE order_id='".$backorder_id."'");

			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->user->getId());
			if ($user_info) {
				$sales_person = $user_info['firstname'] . " " . $user_info['lastname'];
			}

			$comment = "Created by " . $sales_person . " from Order " . $order_id; 
			$this->db->query("INSERT INTO ".DB_PREFIX."order_history SET order_id='".$backorder_id."',order_status_id='1',comment='".$comment."',date_added=NOW() ");

			$this->db->query("INSERT INTO ".DB_PREFIX."order_total SET order_id='".$backorder_id."',code='sub_total',title='Sub Total',text='$".$total."',value='".$total."',sort_order='1' ");

			$this->db->query("INSERT INTO ".DB_PREFIX."order_total SET order_id='".$backorder_id."',code='total',title='Total',text='$".$total."',value='".$total."',sort_order='2' "); 

			$this->db->query("UPDATE ".DB_PREFIX."order set comment='".$comment."' WHERE order_id='".$backorder_id."'");
		}

        

	}

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

	public function getCustomerByEmail($email) {
		$query = $this->db->query("SELECT DISTINCT c.*,a.* FROM " . DB_PREFIX . "customer c LEFT JOIN ".DB_PREFIX."address a ON(a.customer_id=c.customer_id) WHERE LOWER(c.email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}

	public function getCustomerByID($customer_id) {
		$query = $this->db->query("SELECT DISTINCT c.*,a.* FROM " . DB_PREFIX . "customer c LEFT JOIN ".DB_PREFIX."address a ON(a.customer_id=c.customer_id) WHERE c.customer_id = '" . $customer_id . "'");
		return $query->row;
	}

	public function getCustomerByOrderID($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order WHERE order_id = '" . $order_id . "'");
		return $query->row;
	}

	public function getProductEstimateDeliveryTime($product_id)
	{
		$query = $this->db->query("SELECT estimate_deliver_time FROM ". DB_PREFIX ."product WHERE product_id = '". $product_id ."'");
		return $query->row ? $query->row['estimate_deliver_time'] : 0;
	}

	public function addFirstProductToIncomingOrder( $order_id, $product_id, $previous_order_id)
	{
		$product_data = $this->getProductData($product_id);
		if( $product_data )
		{
			$total = $product_data['price'];
			$this->db->query("INSERT INTO ".DB_PREFIX."order_product SET order_id='".$order_id."',product_id='".$product_id."' ,name='".$this->db->escape($product_data['name'])."',model='".$this->db->escape($product_data['model'])."',price='".$this->db->escape($product_data['price'])."',quantity='1', default_vendor_unit='".$this->db->escape($product_data['default_vendor_unit'])."',total='".$total."'");
			
			$date_ordered = date("Y-m-d");
			$this->db->query("UPDATE ".DB_PREFIX."product SET incoming_request='1', date_ordered = '". $date_ordered ."'  WHERE product_id='".$product_id."'");

			$estimate_deliver_time = $this->getProductEstimateDeliveryTime($product_id);

			if($estimate_deliver_time > 0)
			{
				$date_available = date('Y-m-d', strtotime($date_ordered. " + {$estimate_deliver_time} days"));
				$this->db->query("UPDATE " . DB_PREFIX . "product SET frontend_date_available = '" . $date_available . "', stock_status_id = 5 WHERE product_id = '" . (int)$product_id . "' AND quantity < 1");
			}
			
			$this->db->query("UPDATE ".DB_PREFIX."order set total='".$total."', order_type='2' WHERE order_id='".$order_id."'");

			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->user->getId());
			if ($user_info) {
				$sales_person = $user_info['firstname'] . " " . $user_info['lastname'];
			}

			$comment = "Created by " . $sales_person . " from Order " . $previous_order_id; 

			$this->db->query("INSERT INTO ".DB_PREFIX."order_history SET order_id='".$order_id."',order_status_id='1',comment='".$comment."',date_added=NOW() ");

			$this->db->query("INSERT INTO ".DB_PREFIX."order_total SET order_id='".$order_id."',code='sub_total',title='Sub Total',text='$".$total."',value='".$total."',sort_order='1' ");

			$this->db->query("INSERT INTO ".DB_PREFIX."order_total SET order_id='".$order_id."',code='total',title='Total',text='$".$total."',value='".$total."',sort_order='2' ");

			$this->db->query("UPDATE ".DB_PREFIX."order set comment='".$comment."' WHERE order_id='".$order_id."'"); 
		}
	}

	public function getOrderProductData($order_id, $product_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE product_id ='".$product_id."' AND order_id ='".$order_id."'");

		return $query->row;
	}

	public function getProductData($product_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p INNER JOIN " . DB_PREFIX . "product_description pd ON p.product_id = pd.product_id WHERE pd.language_id = 1 AND p.product_id ='".$product_id."'");
		return $query->row;
	}
}
