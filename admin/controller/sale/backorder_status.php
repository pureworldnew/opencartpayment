<?php

class ControllerSaleBackorderStatus extends Controller {

    private $error = array();

    public function index() {
        $this->language->load('sale/backorder_status');
        $this->document->setTitle( $this->language->get('heading_title') );
        $this->load->model('sale/backorder');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            
            $this->model_sale_backorder->editSetting('backorder_status', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');
            
            $this->response->redirect($this->url->link('sale/backorder_status', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $data['heading_title'] = $this->language->get('heading_title');
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
        $data['entry_order_status'] = $this->language->get('entry_order_status');
        
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('sale/backorder_status', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['action'] = $this->url->link('sale/backorder_status', 'token=' . $this->session->data['token'], 'SSL');
        $backorder_status_info = $this->model_sale_backorder->getSetting('backorder_status');
        $data['order_statuses'] = $this->model_sale_backorder->getAllOrderStatuses();

        if (isset($this->request->post['selected_backorder_statuses'])) {
			$data['selected_backorder_statuses'] = $this->request->post['selected_backorder_statuses'];
		} elseif (!empty($backorder_status_info)) {
			$data['selected_backorder_statuses'] = $backorder_status_info['selected_backorder_statuses'];
		} else {
			$data['selected_backorder_statuses'] = '';
        }
        
        if (!empty($data['selected_backorder_statuses']) )
        {
            $data['selected_backorder_statuses'] = explode( ",", $data['selected_backorder_statuses'] );
        }

        if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
        }
    
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/backorder_status.tpl', $data));
        
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'sale/backorder_status')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }  
}

?>