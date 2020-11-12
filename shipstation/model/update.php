<?php
class ModelUpdate extends Model {
    /**
     * Function to update order status and add order history
     * 
     * @param $order_id integer
     * @param $data array
     * @return boolean
     */
    public function addOrderHistory($order_id, $data) {
        $this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int) $data['order_status_id'] . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int) $order_id . "', order_status_id = '" . (int) $data['order_status_id'] . "', notify = '" . (isset($data['notify']) ? (int) $data['notify'] : 0) . "', comment = '" . $this->db->escape(strip_tags($data['comment'])) . "', date_added = NOW()");
    }

    /**
     * Function to get order statuses
     * 
     * @return array
     */
    public function getOrderStatuses() {
        $order_status_data = $this->cache->get('order_status.' . (int) $this->config->get('config_language_id'));
        if (!$order_status_data) {
            $query = $this->db->query("SELECT order_status_id, name FROM " . DB_PREFIX . "order_status WHERE language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY name");
            $order_status_data = $query->rows;
            $this->cache->set('order_status.' . (int) $this->config->get('config_language_id'), $order_status_data);
        }

        foreach ($order_status_data as $result) {
            $order_status[strtolower($result['name'])] = $result['order_status_id'];
        }
        return $order_status;
    }
}