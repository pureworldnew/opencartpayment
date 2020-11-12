<?php

class ControllerModuleVideoPlayer extends Controller {

    public function index($setting = null) {
        if ($setting && $setting['status']) {
            $data = array();
            $filename = $setting['video_path'];
            $fileType = $setting['file_type'];
            $data['file_type'] = $fileType;
            if ($this->request->server['HTTPS']) {
                $data['video_path'] = HTTPS_SERVER . 'image/video/' . $filename;
            } else {
                $data['video_path'] = HTTP_SERVER . 'image/video/' . $filename;
            }
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/video_player.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/module/video_player.tpl', $data);
			} else {
				return $this->load->view('default/template/module/video_player.tpl', $data);
			}
            //return $this->load->view('', $data);
        }
    }

}
