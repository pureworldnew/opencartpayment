<?php
class ControllerVerifyStatus extends Controller {
    /**
     * Function to check order status
     * 
     * @return boolean
     */
    public function index() {
        if (isset($this->request->get['status'])) {
            $this->load->model('status');
            if ($this->model_status->checkOrderStatus($this->request->get['status'])) {
                echo 'true';
            } else {
                echo 'false';
            }
        } else {
            echo 'false';
        }
    }
}