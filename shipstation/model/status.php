<?php
class ModelStatus extends Model {
    /**
     * Function to get order status
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
        return $order_status_data;
    }

    /**
     * Function to check order status
     * 
     * @param $status string
     * @return boolean
     */
    public function checkOrderStatus($status) {
        $data = $this->getOrderStatuses();
        //check for the status in result data
        foreach ($data as $result) {
            if (isset($result['name']) && (strtolower($result['name']) == strtolower($status))) {
                return true;
            }
        }
        return false;
    }
}