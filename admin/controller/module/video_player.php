<?php

class ControllerModuleVideoPlayer extends Controller {

    private $DEFAULT_MODULE_SETTINGS = array(
        'name' => 'Video Player',
        'video_path' => 'Path To Video',
        'file_type'=>'',
        'status' => 0 /* Enabled by default */
    );

    

    private $error = array();

    public function index() {
        if (!isset($this->request->get['module_id'])) {
            $module_id = $this->addModule();
            $this->response->redirect($this->url->link('module/video_player', '&token=' . $this->session->data['token'] . '&module_id=' . $module_id));
        } else {
            $this->editModule($this->request->get['module_id']);
        }
    }

    private function addModule() {
        $this->load->model('extension/module');

        $this->model_extension_module->addModule('video_player', $this->DEFAULT_MODULE_SETTINGS);

        return $this->db->getLastId();
    }

    protected function editModule($module_id) {

        $this->load->model('extension/module');

        /* Set page title */
        $this->load->language('extension/module/video_player');
        //echo "<pre>";print_r($this->language); echo "</pre>";
        //die();
        $this->document->setTitle($this->language->get('heading_title'));

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
            $this->model_extension_module->editModule($this->request->get['module_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'] . '&type=module', true));
        }
        $data = array();
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
            'href' => $this->url->link('module/video_player', 'token=' . $this->session->data['token'], true)
        );

        $module_setting = $this->model_extension_module->getModule($module_id);
        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } else {
            $data['name'] = $module_setting['name'];
        }
        if (isset($this->request->post['video_path'])) {
            $data['video_path'] = $this->request->post['video_path'];
        } else {
            $data['video_path'] = $module_setting['video_path'];
        }
        
        if (isset($this->request->post['file_type'])) {
            $data['file_type'] = $this->request->post['file_type'];
        } else {
            $data['file_type'] = $module_setting['file_type'];
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } else {
            $data['status'] = $module_setting['status'];
        }
        $data['cancel'] = $this->url->link('marketplace/extension', 'token=' . $this->session->data['token'] . '&type=module');
        $data['save'] = "";
        $data['token'] = $this->session->data['token'];
        //echo $data['token'];
        //die();
        $data['heading_title'] = $this->language->get('heading_title');
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['button_del'] = $this->language->get('button_del');
        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_message'] = $this->language->get('entry_message');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['help_message'] = $this->language->get('help_message');
        $data['text_loading'] = $this->language->get('text_loading');
        $data['placeholder_name'] = $this->language->get('placeholder_name');
        $data['placeholder_message'] = $this->language->get('placeholder_message');

        $data['error_name']        = $this->language->get('error_name');
        $data['error_message']         = $this->language->get('error_message');
        $data['error_redirect_url']    = $this->language->get('error_redirect_url');
        $data['error_video_exists']    = $this->language->get('error_video_exists');
        $data['error_video_upload']    = $this->language->get('error_video_upload');
        $data['error_video_path']   = $this->language->get('error_video_path');


        if ($this->request->server['HTTPS']) {
            $data['path'] = HTTPS_CATALOG . 'image/video/';
        } else {
            $data['path'] = HTTP_CATALOG . 'image/video/';
        }
        $data['error'] = $this->error;
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $htmlOutput = $this->load->view('module/video_player.tpl', $data);
        $this->response->setOutput($htmlOutput);
    }

    public function validate() {

        if (!$this->user->hasPermission('modify', 'extension/module/video_player')) {
            $this->error['permission'] = true;
            return false;
        }
        if (!utf8_strlen($this->request->post['name'])) {
            $this->error['name'] = true;
        }
        if (!utf8_strlen($this->request->post['video_path']) || $this->request->post['video_path'] == $this->DEFAULT_MODULE_SETTINGS['video_path']) {
            $this->error['video_path'] = true;
        }
        $video_file = DIR_IMAGE . 'video/' . $this->request->post['video_path'];
        if (is_file($video_file)) {
            $this->error['error_video_exists'] = true;
        }

        if (!is_file($video_file)) {
            $result = move_uploaded_file($this->request->files['video_file']['tmp_name'], $video_file);
            

            if ($result != 1)
                $this->error['video_path'] = true;
            if (!is_file($video_file)) {
                $this->error['video_path'] = true;
            }
        }
        return empty($this->error);
    }

    public function install() {
        $this->load->model('setting/setting');
        $this->model_setting_setting->editSetting('module_video_player', ['module_video_player_status' => 0]);
        if (!is_dir(DIR_IMAGE . 'video')) {
            mkdir(DIR_IMAGE . 'video', 0777, true);
        }
    }

    public function uninstall() {
        $this->load->model('setting/setting');
        $this->model_setting_setting->deleteSetting('module_video_player');
    }

    public function del() {
        $this->load->language('extension/module/video_player');

        $json = array();

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->request->post['video_path'] == $this->DEFAULT_MODULE_SETTINGS['video_path']) {
                $json['error'] = $this->language->get('error_name');
            }


            if (!isset($json['error'])) {
                $video_file = DIR_IMAGE . 'video/' . $this->request->post['video_path'];

                unlink($video_file);

                $json['success'] = $this->language->get('text_success');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
