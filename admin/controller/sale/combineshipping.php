<?php

class ControllerSaleCombineshipping extends Controller {

    private $error = array();

    public function index() {
        $this->language->load('sale/combineshipping');
        $this->document->setTitle( $this->language->get('heading_title') );
        $this->load->model('sale/combineshipping');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            
            $this->model_sale_combineshipping->editSetting('combine_shipping', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');
            
            $this->response->redirect($this->url->link('sale/combineshipping', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $data['heading_title'] = $this->language->get('heading_title');
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_note'] = $this->language->get('entry_note');
        $data['entry_time'] = $this->language->get('entry_time');
        
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('sale/combineshipping', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['action'] = $this->url->link('sale/combineshipping', 'token=' . $this->session->data['token'], 'SSL');
        $combine_shipping_info = $this->model_sale_combineshipping->getSetting('combine_shipping');
        $data['order_statuses'] = $this->model_sale_combineshipping->getAllOrderStatuses();

        if (isset($this->request->post['time_frame'])) {
			$data['time_frame'] = $this->request->post['time_frame'];
		} elseif (!empty($combine_shipping_info)) {
			$data['time_frame'] = $combine_shipping_info['time_frame'];
		} else {
			$data['time_frame'] = '';
        }
        
        if (isset($this->request->post['note'])) {
			$data['note'] = $this->request->post['note'];
		} elseif (!empty($combine_shipping_info)) {
			$data['note'] = $combine_shipping_info['note'];
		} else {
			$data['note'] = '';
        }
        
        if (isset($this->request->post['selected_order_statuses'])) {
			$data['selected_order_statuses'] = $this->request->post['selected_order_statuses'];
		} elseif (!empty($combine_shipping_info)) {
			$data['selected_order_statuses'] = $combine_shipping_info['selected_order_statuses'];
		} else {
			$data['selected_order_statuses'] = '';
        }
        
        if (!empty($data['selected_order_statuses']) )
        {
            $data['selected_order_statuses'] = explode( ",", $data['selected_order_statuses'] );
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

		$this->response->setOutput($this->load->view('sale/combineshipping.tpl', $data));
        
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'sale/combineshipping')) {
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