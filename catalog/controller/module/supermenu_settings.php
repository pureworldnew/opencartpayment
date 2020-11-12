<?php  
class ControllerModuleSupermenusettings extends Controller {

	public function index() {
	
		$data['supermenu_settings_status'] = $this->config->get('supermenu_settings_status');
		$data['skin'] = $this->config->get('supermenu_skin');
		$data['supermenu_settings'] = $this->config->get('supermenu_settings');
		$data['supermenuisresponsive'] = ($this->config->get('supermenu_supermenuisresponsive') ? true : false);
		$data['usehoverintent'] = ($this->config->get('supermenu_usehoverintent') ? false : true);
		$data['direction'] = $this->language->get('direction');
		
		if (version_compare(VERSION, '2.2.0.0') >= 0) {
			return $this->load->view('module/supermenu_settings', $data);
		} else {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/supermenu_settings.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/module/supermenu_settings.tpl', $data);
			} else {
				return $this->load->view('default/template/module/supermenu_settings.tpl', $data);
			}
		}

  	}
}
?>