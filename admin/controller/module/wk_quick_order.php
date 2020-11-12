<?php
class ControllerModuleWkquickorder extends Controller {
    private $error = array();

    public function index() {
        $data = array();
        $data = array_merge($data, $this->load->language('module/wk_quick_order'));

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');


        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

            $this->model_setting_setting->editSetting('wk_quick_order', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'] , true));
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if(isset($this->request->post['wk_quick_order_status'])){
          $data['wk_quick_order_status'] = $this->request->post['wk_quick_order_status'];
        }else if($this->config->get('wk_quick_order_status')){
          $data['wk_quick_order_status'] = $this->config->get('wk_quick_order_status');
        }else{
          $data['wk_quick_order_status'] = '';
        }

        if(isset($this->request->post['wk_quick_order_limit'])){
          $data['wk_quick_order_limit'] = $this->request->post['wk_quick_order_limit'];
        }else if($this->config->get('wk_quick_order_limit')){
          $data['wk_quick_order_limit'] = $this->config->get('wk_quick_order_limit');
        }else{
          $data['wk_quick_order_limit'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'] . '&type=module', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('module/wk_quick_order', 'token=' . $this->session->data['token'], true)
        );

        $data['action'] = $this->url->link('module/wk_quick_order', 'token=' . $this->session->data['token'], true);

        $data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'] . '&type=module', true);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('module/wk_quick_order.tpl', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'module/wk_quick_order')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        if($this->request->post['wk_quick_order_limit'] < 1){
          if(!isset($this->error['warning']))
          $this->error['warning'] = $this->language->get('row_limit_error');
        }

        return !$this->error;
    }
}
