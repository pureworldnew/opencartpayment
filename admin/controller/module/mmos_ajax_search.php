<?php

class ControllerModuleMmosAjaxSearch extends Controller {

    private $error = array();

    public function index() {
        if (!isset($this->request->get['store_id'])) {
            $this->response->redirect($this->url->link('module/mmos_ajax_search', 'token=' . $this->session->data['token'] . '&store_id=0', 'SSL'));
        }

        $this->load->language('module/mmos_ajax_search');

        $this->document->setTitle($this->language->get('heading_title1'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

            $this->model_setting_setting->editSetting('mmos_ajax_search', $this->request->post, $this->request->get['store_id']);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('module/mmos_ajax_search', 'token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id'], 'SSL'));
        }

        //WWw.MMOsolution.com config data -- DO NOT REMOVE--- 
        $data['MMOS_version'] = '2.2';
        $data['MMOS_code_id'] = 'MMOSOC107';

        $data['heading_title'] = $this->language->get('heading_title1');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_help'] = $this->language->get('text_help');

        $data['entry_store'] = $this->language->get('entry_store');
        $data['entry_limit'] = $this->language->get('entry_limit');
        $data['entry_width'] = $this->language->get('entry_width');
        $data['entry_height'] = $this->language->get('entry_height');
        $data['entry_maxtext'] = $this->language->get('entry_maxtext');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_search_in'] = $this->language->get('entry_search_in');
        $data['entry_search_name'] = $this->language->get('entry_search_name');
        $data['entry_search_tag'] = $this->language->get('entry_search_tag');
        $data['entry_search_des'] = $this->language->get('entry_search_des');
      


        $data['tab_setting'] = $this->language->get('tab_setting');
        $data['tab_support'] = $this->language->get('tab_support');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['width'])) {
            $data['error_width'] = $this->error['width'];
        } else {
            $data['error_width'] = '';
        }

        if (isset($this->error['height'])) {
            $data['error_height'] = $this->error['height'];
        } else {
            $data['error_height'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title1'),
            'href' => $this->url->link('module/mmos_ajax_search', 'token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id'], 'SSL')
        );

        $data['action'] = $this->url->link('module/mmos_ajax_search', 'token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id'], 'SSL');

        $data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

        $mmos_ajax_search = $this->model_setting_setting->getSetting('mmos_ajax_search', $this->request->get['store_id']);

        if (isset($this->request->post['mmos_ajax_search'])) {
            $data['mmos_ajax_search'] = $this->request->post['mmos_ajax_search'];
        } elseif ($mmos_ajax_search) {
            $data['mmos_ajax_search'] = $mmos_ajax_search['mmos_ajax_search'];
        } else {
            $data['mmos_ajax_search'] = array();
        }
       
        if (isset($this->request->get['store_id'])) {
            $data['store_id'] = $this->request->get['store_id'];
        } else {
            $data['store_id'] = 0;
        }

        $data['stores'] = array();

        $data['stores'][] = array(
            'store_id' => 0,
            'name' => $this->config->get('config_name') . $this->language->get('text_default')
        );

        $this->load->model('setting/store');

        $stores = $this->model_setting_store->getStores();

        foreach ($stores as $store) {
            $data['stores'][] = array(
                'store_id' => $store['store_id'],
                'name' => $store['name']
            );
        }

        $data['token'] = $this->session->data['token'];

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('module/mmos_ajax_search.tpl', $data));
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'module/mmos_ajax_search')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (isset($this->request->post['mmos_ajax_search']['image'])) {
            if (!$this->request->post['mmos_ajax_search']['width']) {
                $this->error['width'] = $this->language->get('error_width');
            }

            if (!$this->request->post['mmos_ajax_search']['height']) {
                $this->error['height'] = $this->language->get('error_height');
            }
        }

        return !$this->error;
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'module/mmos_ajax_search')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        return !$this->error;
    }

    public function uninstall() {
        if ($this->user->hasPermission('modify', 'extension/module')) {
            $this->load->model('setting/setting');

            $this->load->model('setting/store');

            $this->model_setting_setting->deleteSetting('mmos_ajax_search', 0);

            $stores = $this->model_setting_store->getStores();

            foreach ($stores as $store) {
                $this->model_setting_setting->deleteSetting('mmos_ajax_search', $store['store_id']);
            }

            $this->vqmod_protect(1);
        }
    }

    public function install() {
        if ($this->user->hasPermission('modify', 'extension/module')) {
            $data_initial = array(
                'mmos_ajax_search' => array(
                    'status' => '0',
                    'limit' => '5',
                    'image' => '1',
                    'width' => '75',
                    'height' => '75'
                )
            );

            $this->load->model('setting/setting');

            $this->load->model('setting/store');

            $this->model_setting_setting->editSetting('mmos_ajax_search', $data_initial, 0);

            $stores = $this->model_setting_store->getStores();

            foreach ($stores as $store) {
                $this->model_setting_setting->editSetting('mmos_ajax_search', $data_initial, $store['store_id']);
            }

            $this->vqmod_protect(1);
            $this->response->redirect($this->url->link('module/mmos_ajax_search', 'token=' . $this->session->data['token'], 'SSL'));
        }
    }

    protected function vqmod_protect($action = 0) {
        // action 1 =  install; 0: uninstall
        $vqmod_file = 'MMOSolution_ajax_search.xml';
        if ($this->user->hasPermission('modify', 'extension/module')) {
            $MMOS_ROOT_DIR = substr(DIR_APPLICATION, 0, strrpos(DIR_APPLICATION, '/', -2)) . '/vqmod/xml/';
            if ($action == 1) {
                if (is_file($MMOS_ROOT_DIR . $vqmod_file . '_mmosolution')) {
                    @rename($MMOS_ROOT_DIR . $vqmod_file . '_mmosolution', $MMOS_ROOT_DIR . $vqmod_file);
                }
            } else {
                if (is_file($MMOS_ROOT_DIR . $vqmod_file)) {
                    @rename($MMOS_ROOT_DIR . $vqmod_file, $MMOS_ROOT_DIR . $vqmod_file . '_mmosolution');
                }
            }
        }
    }

}
