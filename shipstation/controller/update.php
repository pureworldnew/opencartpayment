<?php
class ControllerUpdate extends Controller {
    /**
     * Function to update order status 
     * 
     * @return string
     */
    public function index() {
        $this->load->language('module/shipstation');
        if (isset($this->request->get['order_number']) && isset($this->request->get['status']) && isset($this->request->get['comment'])) {
            $this->load->model('update');
            //Get order statuses
            $order_statuses = $this->model_update->getOrderStatuses();

            $order = array();
            $order['order_status_id'] = (isset($order_statuses[strtolower($this->request->get['status'])])) ? $order_statuses[strtolower($this->request->get['status'])] : 0;
            $order['notify'] = 0;
            $order['comment'] = $this->request->get['comment'];
            //Update the order status and history
            if (isset($order['order_status_id']) && $order['order_status_id']) {
                $this->model_update->addOrderHistory((int) $this->request->get['order_number'], $order);
                echo $this->language->get('text_update_success');
            } else {
                echo $this->language->get('text_status_not_found');
            }
        }
    }
}