<?php  
class ControllerModuleALTHEMISTtabs extends Controller {
	protected function index($setting) {
		static $module = 0;
		$this->document->addScript('catalog/view/javascript/jquery/tabs.js?v='.rand());
		$this->document->addStyle('catalog/view/theme/default/althemist/css/althemisttabs.css?v='.rand());
		$this->language->load('module/ALTHEMISTtabs');
		$this->load->model('tool/image');
		
		$data['module_title']     = $setting['module_title'];
		if (isset($setting['module_title'][$this->config->get('config_language_id')])){
				$data['module_title'] = html_entity_decode($setting['module_title'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
			} else {
				$data['module_title'] = false;
			}
		$data['tabsmode']     = $setting['tabsmode'];
		$number_sections = count($setting['sections']);	
		
		$data['sections'] = array();
		
		$section_row = 0;
		
		foreach($setting['sections'] as $section){
			if (isset($section['title'][$this->config->get('config_language_id')])){
				$title = html_entity_decode($section['title'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
			} else {
				$title = false;
			}
			
			if (isset($section['description'][$this->config->get('config_language_id')])){
				$description = html_entity_decode($section['description'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
			} else {
				$description = false;
			}

			$data['sections'][] = array(
				'id'          => 'slide-' . $module . '-' . $section_row,
				'title'       => $title,
				'description' => $description,
				'icon'       => $section['icon']
			);
			
		}

		$data['module'] = $module++;
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/ALTHEMISTtabs.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/ALTHEMISTtabs.tpl', $data);
		} else {
			return $this->load->view('default/template/module/ALTHEMISTtabs.tpl', $data);
		}
		
		/*if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/ALTHEMISTtabs.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/ALTHEMISTtabs.tpl';
		} else {
			$this->template = 'default/template/module/ALTHEMISTtabs.tpl';
		}
		
		$this->render();*/
	}
}
?>