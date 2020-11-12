<?php
class ModelExport extends Model {
    /**
     * Function to get order details
     * 
     * @param $order_data array
     * @return array
     */
    public function getOrderData($order_data) {
        if ($this->statuses == '') {
            $this->statuses = $this->getStatusById();
        }
        //Get customer details
        if (!empty($order_data)) {
            //get the customer shipping country code
            $country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int) $order_data['shipping_country_id'] . "'");
            $shipping_iso_code = '';
            if ($country_query->num_rows) {
                $shipping_iso_code = $country_query->row['iso_code_2'];
            }
            //get the customer payment country code
            $country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int) $order_data['payment_country_id'] . "'");
            $payment_iso_code = '';
            if ($country_query->num_rows) {
                $payment_iso_code = $country_query->row['iso_code_2'];
            }
            //Customer billing address details
            $bill_to = array(
                'Name' => $order_data['payment_firstname'] . ' ' . $order_data['payment_lastname'],
                'Company' => $order_data['payment_company'],
                'Phone' => $order_data['telephone'],
                'Email' => $order_data['email']
            );
            //Customer shipping address details
            $ship_address = 'shipping';
            if (!$order_data['shipping_method']) {
                $ship_address = 'payment';
                $shipping_iso_code = $payment_iso_code;
            }
            $ship_to = array(
                'Name' => $order_data[$ship_address . '_firstname'] . ' ' . $order_data[$ship_address . '_lastname'],
                'Company' => $order_data[$ship_address . '_company'],
                'Address1' => $order_data[$ship_address . '_address_1'],
                'Address2' => $order_data[$ship_address . '_address_2'],
                'City' => $order_data[$ship_address . '_city'],
                'State' => $order_data[$ship_address . '_zone'],
                'PostalCode' => $order_data[$ship_address . '_postcode'],
                'Country' => $shipping_iso_code,
                'Phone' => $order_data['telephone']
            );
            $customer = array(
                'CustomerCode' => $order_data['email'],
                'BillTo' => $bill_to,
                'ShipTo' => $ship_to
            );

            /**
             * Get order total from order id
             * Get the order products
             * Get product options
             */
            $order_totals = $this->getOrderTotals($order_data['order_id']);

            $totals = array();
            foreach ($order_totals as $order_total) {
                $total_value = 0;
                if (isset($totals[$order_total['code']]) && $totals[$order_total['code']]) {
                    $total_value = $totals[$order_total['code']];
                }
                $totals[$order_total['code']] = $total_value + $order_total['value'];
            }
            $order_products = $this->getOrderProducts($order_data['order_id']);
            $products = array();
            $this->load->model('tool/image');
            foreach ($order_products as $product) {
                $weight_class = $this->getWeightClass($product['weight_class_id']);
                $order_options = $this->getOrderOptions($product['order_id'], $product['order_product_id']);
                $options = array();
                foreach ($order_options as $option) {
                    $value = '';
                    if ($option['type'] != 'file') {
                        $value = $option['value'];
                    } else {
                        $result_data = $this->getUploadByCode($option['value']);
                        $value = $result_data['name'];
                    }
                    $options[] = array(
                        'Name' => $option['name'],
                        'Value' => $value,
                        'Weight' => $option['weight']
                    );
                }
				$width = $this->config->get('config_image_product_width');
				if (!$width) {
                   $width = '228';
                }
				$height = $this->config->get('config_image_product_height');
				if (!$height) {
                   $height = '228';
                }
                $products[] = array(
                    'SKU' => ($product['sku'] == '') ? (($product['upc'] == '') ? $product['model'] : $product['upc']) : $product['sku'],
                    'Name' => $product['name'],
                    'ImageUrl' => $this->model_tool_image->resize($product['image'], $width, $height),
                    'Weight' => $product['weight'],
                    'WeightUnits' => $weight_class['title'],
                    'Quantity' => $product['quantity'],
                    'UnitPrice' => $product['price'],
                    'Options' => $options
                );
            }
            return array(
                'OrderNumber' => $order_data['order_id'],
                'OrderDate' => date('j/n/Y g:i A', strtotime($order_data['date_added'])),
                'OrderStatus' => $this->statuses[$order_data['order_status_id']],
                'LastModified' => date('j/n/Y g:i A', strtotime($order_data['date_modified'])),
                'ShippingMethod' => ($order_data['shipping_method'] == '') ? 'unknown' : $order_data['shipping_method'],
                'OrderTotal' => (isset($totals['total'])) ? $totals['total'] : '0.00',
                'TaxAmount' => (isset($totals['tax'])) ? $totals['tax'] : '0.00',
                'ShippingAmount' => (isset($totals['shipping'])) ? $totals['shipping'] : '0.00',
                'CustomerNotes' => (isset($order_data['comment'])) ? $order_data['comment'] : '',
                'InternalNotes' => '',
                'Customer' => $customer,
                'Items' => $products
            );
        } else {
            return false;
        }
    }

    /**
     * Function to get name of the file uploaded by customer
     * 
     * @param $code string
     * @return string
     */
    public function getUploadByCode($code) {
        $query = $this->db->query("SELECT name FROM " . DB_PREFIX . "upload WHERE code = '" . $this->db->escape($code) . "'");
        return $query->row;
    }

    /**
     * Function to get order total from order id
     * 
     * @param $order_id integer
     * @return string
     */
    public function getOrderTotals($order_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int) $order_id . "' ORDER BY sort_order");
        return $query->rows;
    }

    /**
     * Function to get order product data from order id
     * 
     * @param $order_id integer
     * @return array
     */
    public function getOrderProducts($order_id) {
        $query = $this->db->query("SELECT *, op.quantity AS quantity, op.price AS price FROM " . DB_PREFIX . "order_product op INNER JOIN " . DB_PREFIX . "product p ON op.product_id = p.product_id WHERE op.order_id = '" . (int) $order_id . "'");
        return $query->rows;
    }

    /**
     * Function to get order ids from start and end dates
     * 
     * @param $data array
     * @return array
     */
    public function getOrders($data) {
        $sql = "SELECT * FROM `" . DB_PREFIX . "order` WHERE order_status_id > 0 AND ";
        if ((isset($data['startdate'])) || (isset($data['enddate']))) {

            if (isset($data['startdate'])) {
                $sql .= " `date_modified` >=  '" . $data['startdate'] . "'";
            }
            if ((isset($data['startdate'])) && (isset($data['enddate']))) {
                $sql .= " AND ";
            }
            if (isset($data['enddate'])) {
                $sql .= " `date_modified` <= '" . $data['enddate'] . "'";
            }
        }
        $query = $this->db->query($sql);
        return $query->rows;
    }

    /**
     * Function to get order status
     * 
     * @return integer
     */
    public function getStatusById() {
        $order_status_data = $this->cache->get('order_status.' . (int) $this->config->get('config_language_id'));
        if (!$order_status_data) {
            $query = $this->db->query("SELECT order_status_id, name FROM " . DB_PREFIX . "order_status WHERE language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY name");
            $order_status_data = $query->rows;
            $this->cache->set('order_status.' . (int) $this->config->get('config_language_id'), $order_status_data);
        }
        foreach ($order_status_data as $result) {
            $order_status[$result['order_status_id']] = $result['name'];
        }
        return $order_status;
    }

    /**
     * Function to get order weight details
     * 
     * @param $weight_class_id integer
     * @return array
     */
    public function getWeightClass($weight_class_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "weight_class wc LEFT JOIN " . DB_PREFIX . "weight_class_description wcd ON (wc.weight_class_id = wcd.weight_class_id) WHERE wc.weight_class_id = '" . (int) $weight_class_id . "' AND wcd.language_id = '" . (int) $this->config->get('config_language_id') . "'");
        return $query->row;
    }

    /**
     * Function to get order option details
     * 
     * @param $order_id integer
     * @param $order_product_id integer
     * @return array
     */
    public function getOrderOptions($order_id, $order_product_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option oo LEFT JOIN " . DB_PREFIX . "product_option_value pov ON (oo.product_option_value_id = pov.product_option_value_id) WHERE oo.order_id = '" . (int) $order_id . "' AND oo.order_product_id = '" . (int) $order_product_id . "'");
        return $query->rows;
    }
}
