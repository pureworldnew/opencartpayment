<?php
class ControllerModuleIsearch extends Controller {
	private $error = array(); 
	
	public function __construct($registry) {
		parent::__construct($registry);
		$this->load->config('isense/isearch');
	}

	public function index() {   
		$this->load->language($this->config->get('isearch_module_path'));

		$this->document->setTitle($this->language->get('heading_title_dashboard'));
		
		$this->document->addStyle('view/stylesheet/isearch.css');
		
        $this->init_search_term_db();

		$this->load->model('setting/setting');
				
		$data['error_warning'] = '';
		
        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

            if (!$this->user->hasPermission('modify', $this->config->get('isearch_module_path'))) {
                $this->session->data['isearch_error'][] = $this->language->get('error_permission');
                $this->response->redirect($this->url->link($this->config->get('isearch_extension_route'), 'token=' . $this->session->data['token'].'$type=module', 'SSL'));
            }

            if (!empty($_POST['OaXRyb1BhY2sgLSBDb21'])) {
                $this->request->post['iSearch']['LicensedOn'] = $_POST['OaXRyb1BhY2sgLSBDb21'];
            }

            if (!empty($_POST['cHRpbWl6YXRpb24ef4fe'])) {
                $this->request->post['iSearch']['License'] = json_decode(base64_decode($_POST['cHRpbWl6YXRpb24ef4fe']), true);
            }

            //{HOOK_CHECK_IF_CACHE_DISABLED}

            $module_status = ($this->request->post['iSearch']['Enabled'] == 'yes') ? 1 : 0;
            $data = array('isearch' => $this->request->post['iSearch'], 'isearch_status' => $module_status);
            $this->editSetting('isearch', $data);		

            $this->cache->delete('product');
            $this->cache->delete('productstandard');

            $this->session->data['isearch_success'][] = $this->language->get('text_success');

            //{HOOK_REFRESH_CACHE_ON_ENABLE}

            $this->response->redirect($this->url->link($this->config->get('isearch_module_path'), 'token=' . $this->session->data['token'], 'SSL'));
        }

		$data['error_warning'] = '';
		$data['success_message'] = '';

        $data['href_clear_suggestions'] = $this->url->link($this->config->get('isearch_module_path') . '/clear_search_suggestions', 'token=' . $this->session->data['token'], 'SSL');

		if (!empty($this->session->data['isearch_success'])) {
			$data['success_message'] = implode('<br />', $this->session->data['isearch_success']);
			unset($this->session->data['isearch_success']);
		}

		if (!empty($this->session->data['isearch_error'])) {
			$this->error = array_merge($this->error, $this->session->data['isearch_error']);
			unset($this->session->data['isearch_error']);
		}

		if (!empty($this->error)) {
			$data['error_warning'] = implode('<br />', $this->error);
		}
				
		$data['heading_title'] = $this->language->get('heading_title_dashboard');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_content_top'] = $this->language->get('text_content_top');
		$data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$data['text_column_left'] = $this->language->get('text_column_left');
		$data['text_column_right'] = $this->language->get('text_column_right');
		
		$data['entry_code'] = $this->language->get('entry_code');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_add_module'] = $this->language->get('button_add_module');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['entry_layouts_active'] = $this->language->get('entry_layouts_active');
		$data['entry_highlightcolor'] = $this->language->get('entry_highlightcolor');

		$data['entry_layout_options'] = $this->language->get('entry_layout_options');
		$data['entry_position_options'] = $this->language->get('entry_position_options');
		$data['entry_action_options'] = $this->language->get('entry_action_options');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_position'] = $this->language->get('entry_position');
		$data['entry_layout'] = $this->language->get('entry_layout');
		$data['text_column_right'] = $this->language->get('text_column_right');
		$data['text_column_left'] = $this->language->get('text_column_left');
		$data['text_content_bottom'] = $this->language->get('text_content_bottom');
		$data['text_content_top'] = $this->language->get('text_content_top');
		
		$data['moduleName'] = 'iSearch';
		$data['moduleNameSmall'] = 'isearch';
		$data['moduleData_module'] = 'isearch_module';
		$data['moduleModel'] = 'model_module_isearch';

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		$data['tabs'] = $this->getTabs();
		
  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link($this->config->get('isearch_extension_route'), 'token=' . $this->session->data['token'].'&type=module', 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_dashboard'),
			'href'      => $this->url->link($this->config->get('isearch_module_path'), 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$data['action'] = $this->url->link($this->config->get('isearch_module_path'), 'token=' . $this->session->data['token'], 'SSL');
		
		$data['cancel'] = $this->url->link($this->config->get('isearch_extension_route'), 'token=' . $this->session->data['token'].'&type=module', 'SSL');

		if (isset($this->request->post['iSearch'])) {
			foreach ($this->request->post['iSearch'] as $key => $value) {
				$data['data']['iSearch'][$key] = $this->request->post['iSearch'][$key];
			}
		} else {
			$configValue = $this->config->get('isearch');
			$data['data']['iSearch'] = $configValue;
		}
		
		$data['modules'] = array();
			
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();

        foreach ($data['languages'] as &$l) {
            if (version_compare(VERSION, '2.2', '>=')) {
                $l['image'] = 'language/' . $l['code'] . '/'. $l['code'] . '.png';
            } else {
                $l['image'] = 'view/image/flags/' . $l['image'];
            }
        }

		$data['moduleData'] = $data['data']['iSearch'];	
		$this->load->model('design/layout');
		
		$data['layouts'] = $this->model_design_layout->getLayouts();

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');
		$data['column_left'] = $this->load->controller('common/column_left');

		$this->response->setOutput($this->load->view($this->config->get('isearch_module_path') . '.tpl', $data));
	}
	
	//{HOOK_CACHE_BUILDING_FUNCTIONS}
	
	public function editSetting($group, $data, $store_id = 0) {
        $this->load->model('setting/setting');
        $this->model_setting_setting->editSetting($group, $data, $store_id);
        $this->after_save();
	}

    private function init_search_term_db() {
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "isearch_terms` (`id` int(11) NOT NULL AUTO_INCREMENT, `term` varchar(255) NOT NULL, `count` int(11) NOT NULL, PRIMARY KEY (`id`), UNIQUE KEY `term` (`term`), KEY `count` (`count`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");
    }

    private function after_save() {
        $this->load->model('design/layout');

        $layouts = $this->model_design_layout->getLayouts();

        foreach ($layouts as $layout) {
        	$this->layoutchangehandler($layout['layout_id']);
        }
    }

    public function layoutchangehandler($layout_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "layout_module WHERE code='isearch' AND layout_id=" . $layout_id);
        $this->db->query("INSERT INTO " . DB_PREFIX . "layout_module SET layout_id = '" . $layout_id . "', code = 'isearch', position = 'content_top', sort_order = '0'");
    }

    public function newLayoutAdded($route, $args, $layout_id)    {
    	$this->layoutchangehandler($layout_id);
    }

    public function install() {
        if (VERSION == '2.0.0.0') {
            $this->load->model('tool/event');
            $event_model = $this->model_tool_event;
        } else {
            $this->load->model('extension/event');
            $event_model = $this->model_extension_event;
        }
        if (version_compare(VERSION,'2.2.0.0', '<')) {
        	$event_model->addEvent('isearch', 'post.admin.layout.add', 'module/isearch/layoutchangehandler');
        } else {
        	$event_model->addEvent('isearch', 'admin/model/design/layout/addLayout/after', $this->config->get('isearch_module_path') . '/newLayoutAdded');
        }
        
    }

    public function uninstall() {
        if (VERSION == '2.0.0.0') {
            $this->load->model('tool/event');
            $event_model = $this->model_tool_event;
        } else {
            $this->load->model('extension/event');
            $event_model = $this->model_extension_event;
        }
        $event_model->deleteEvent('isearch');

        $this->load->model('setting/setting');
        $this->model_setting_setting->deleteSetting('isearch');
    }

    public function clear_search_suggestions() {
        if ($this->validate()) {
            $this->init_search_term_db();
            $this->db->query("TRUNCATE TABLE `" . DB_PREFIX . "isearch_terms`");
            $this->session->data['isearch_success'][] = 'Search terms have been cleared.';
        } else {
            $this->session->data['isearch_error'] = 'No modify permissions for ' . $this->config->get('isearch_module_path') . '.';
        }

        $this->response->redirect($this->url->link($this->config->get('isearch_module_path'), 'token=' . $this->session->data['token'], 'SSL'));
    }

	private function validate() {
		if (!$this->user->hasPermission('modify', $this->config->get('isearch_module_path'))) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}

	private function getTabs() {

		if (!function_exists('modification_vqmod')) {
	    function modification_vqmod($file) {
	      if (class_exists('VQMod')) {
	        return VQMod::modCheck(modification($file), $file);
	      } else {
	        return modification($file);
	      }
	    }
	  }
		
		$dir = 
			DIR_APPLICATION . 'view/template/' . $this->config->get('isearch_module_path') . '/';

		$files = scandir($dir);
		$result = array();

		$name_map = array(
			'tab_control_panel.php' => array(
				'name' => 'Control Panel',
				'id' => 'control_panel'
			),
			'tab_improving_results.php' => array(
				'name' => 'Improving Results',
				'id' => 'improving_results'
			),
			'tab_support.php' => array(
				'name' => 'Support',
				'id' => 'support'
			),
		);

		foreach ($files as $file) {
			if (!in_array($file, array_keys($name_map))) continue;

			$result[] = array(
				'file' => modification_vqmod($dir . $file),
				'name' => $name_map[$file]['name'],
				'id' => $name_map[$file]['id']
			);
		}

		return $result;
	}
}
?>
