<?php
class ControllerModuleFaqmanager extends Controller {
	public function index($setting) {
		
		static $module = 0;
		
		if (isset($setting['sections'])) {        
            $data['sections'] = array();

            $section_row = 0;
            
            foreach($setting['sections'] as $section) {
                $this->load->model('tool/image');

                if (isset($section['title'][$this->config->get('config_language_id')])){
                    $title = html_entity_decode($section['title'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
                } else {
                    $title = false;
                }

                $groups = array();

                $group_row = 0;

                if (isset($section['groups'])) {
                    foreach($section['groups'] as $group){
                       if (isset($group['title'][$this->config->get('config_language_id')])){
                           $group_title = html_entity_decode($group['title'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
                       } else {
                           $group_title = false;
                       }

                       if (isset($group['description'][$this->config->get('config_language_id')])){
                           $description = html_entity_decode($group['description'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
                       } else {
                           $description = false;
                       }

                       $group_row++;

                       $groups[] = array(
                           'id'          => $group_row,
                           'title'       => $group_title,
						   'sort'       => $group['sort'],
                           'description' => $description
                       );
                     }
                }

                $section_row++;
                array_multisort(array_column($groups, 'sort'), SORT_ASC, $groups);
                $data['sections'][] = array(
                    'index'   => $section_row,
                    'title'   => $title,
					'sort'   => $section['sort'],
                    'groups'  => $groups
                );
            }
			
			$data['module'] = $module++;
		
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/faqmanager.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/module/faqmanager.tpl', $data);
			} else {
				return $this->load->view('default/template/module/faqmanager.tpl', $data);
			}
		}
	}
}