<?php
/*
	Project : Ka Extensions
	Author  : karapuz <support@ka-station.com>

	Version : 3 ($Revision: 37 $)
*/

class ControllerExtensionKaExtensions extends KaController {

	protected $extension_version = '3.2.3';

	public function index() {
		$this->loadLanguage('extension/ka_extensions');
		$this->document->setTitle($this->language->get('heading_title'));

		// getList functionality
		//
		$this->data['heading_title']   = $this->language->get('heading_title');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_confirm']    = $this->language->get('text_confirm');

		$this->data['column_name']     = $this->language->get('column_name');
		$this->data['column_action']   = $this->language->get('column_action');

		$this->data['extension_version'] = $this->extension_version;
		
		$this->load->model('extension/extension');

		$extensions = $this->model_extension_extension->getInstalled('ka_extensions');
		
		foreach ($extensions as $key => $value) {
			if (!file_exists(DIR_APPLICATION . 'controller/ka_extensions/' . $value . '.php')) {
				$this->model_extension_extension->uninstall('ka_extensions', $value);
				
				unset($extensions[$key]);
			}
		}
	
		$this->data['extensions'] = array();
		$files = glob(DIR_APPLICATION . 'controller/ka_extensions/*.php');
		
		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');
				
				$this->loadLanguage('ka_extensions/' . $extension);

				require_once(modification(DIR_APPLICATION . 'controller/ka_extensions/' . $extension . '.php'));
				$class = 'ControllerKaExtensions' . str_replace('_', '', $extension);
				$class = new $class($this->registry);

				if (method_exists($class, 'getTitle')) {
					$heading_title = $class->getTitle();
				} else {
					$heading_title = $this->language->get('heading_title');
				}
				
				$action = array();
				
				$ext = array(
					'name'      => $heading_title,
					'extension' => $extension,
				);
				
				if (!method_exists($class, 'unpack')) {
					if (!in_array($extension, $extensions)) {
						$action[] = array(
							'text' => $this->language->get('button_install'),
							'href' => $this->url->link('extension/ka_extensions/install', 'token=' . $this->session->data['token'] . '&extension=' . $extension, 'SSL')
						);
					} else {
						$action[] = array(
							'text' => $this->language->get('button_edit'),
							'href' => $this->url->link('ka_extensions/' . $extension . '', 'token=' . $this->session->data['token'], 'SSL')
						);
						
						$action[] = array(
							'text' => $this->language->get('button_uninstall'),
							'href' => $this->url->link('extension/ka_extensions/uninstall', 'token=' . $this->session->data['token'] . '&extension=' . $extension, 'SSL')
						);
					}
				} else {
					$action[] = array(
						'text' => $this->language->get('Unpack'),
						'href' => 'unpack'
					);					
				}
				
				$ext['action'] = $action;

				if (method_exists($class, 'unpack')) {
					$this->data['upgrades'][] = $ext;
				} else {
					$this->data['extensions'][] = $ext;
				}
			}
		}

		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		$this->template = 'extension/ka_extensions.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
			'common/column_left'
		);
		$this->response->setOutput($this->render());
	}
	
	
	public function install() {
		$this->loadLanguage('extension/ka_extensions');

		if (!$this->user->hasPermission('modify', 'extension/ka_extensions') &&
			!$this->user->hasPermission('modify', 'user/user_permission')
		) {
			$this->addTopMessage($this->language->get('error_permission'), 'E'); 
			
			$this->redirect($this->url->link('extension/ka_extensions', 'token=' . $this->session->data['token'], 'SSL'));
		} else {

			$config_maintenance = $this->config->get('config_maintenance');					
			try {
				require_once(modification(DIR_APPLICATION . 'controller/ka_extensions/' . $this->request->get['extension'] . '.php'));
				
				$class = 'ControllerKaExtensions' . str_replace('_', '', $this->request->get['extension']);
				$class = new $class($this->registry);
				
				if (method_exists($class, 'prepareInstallation')) {
					$res = $class->prepareInstallation();
					
					if (empty($res)) {
						$this->redirect($this->url->link('extension/ka_extensions', 'token=' . $this->session->data['token'], 'SSL'));
					} elseif ($res == 'redirect') {
						$this->redirect($this->url->link('extension/ka_extensions/install', 'extension=' . $this->request->get['extension'] . '&token=' . $this->session->data['token'], 'SSL'));
					}
				}
				
				$success = false;
				if (method_exists($class, 'install')) {
					$success = $class->install();
				}
			} catch (Exception $e) {
			
			}

			if (strlen($config_maintenance) > 0) {
				$this->load->model('setting/setting');
				$config = $this->model_setting_setting->getSetting('config');
				if ($config_maintenance != $config['config_maintenance']) {
					$this->model_setting_setting->editSettingValue('config', 'config_maintenance', $config_maintenance);
				}
			}
			
			if ($success) {
				$this->load->model('extension/extension');
				$this->model_extension_extension->install('ka_extensions', $this->request->get['extension']);

				$this->load->model('user/user_group');
			
				$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'ka_extensions/' . $this->request->get['extension']);
				$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'ka_extensions/' . $this->request->get['extension']);
				
				$this->addTopMessage('Extension is installed successfully.');
			} else {
				$this->addTopMessage("Extension is not installed", 'E');
			}
			
			$this->redirect($this->url->link('extension/ka_extensions', 'token=' . $this->session->data['token'], 'SSL'));
		}
	}
	
	
	public function uninstall() {
		$this->loadLanguage('extension/ka_extensions');

		if (!$this->user->hasPermission('modify', 'extension/ka_extensions') &&
			!$this->user->hasPermission('modify', 'user/user_permission')
		) {
			$this->addTopMessage($this->language->get('error_permission'), 'E'); 
			
			$this->redirect($this->url->link('extension/ka_extensions', 'token=' . $this->session->data['token'], 'SSL'));
		} else {		
			$this->load->model('extension/extension');
			$this->load->model('setting/setting');
					
			$this->model_extension_extension->uninstall('ka_extensions', $this->request->get['extension']);
		
			$this->model_setting_setting->deleteSetting($this->request->get['extension']);
		
			require_once(modification(DIR_APPLICATION . 'controller/ka_extensions/' . $this->request->get['extension'] . '.php'));
			
			$class = 'ControllerKaExtensions' . str_replace('_', '', $this->request->get['extension']);
			$class = new $class($this->registry);
			
			if (method_exists($class, 'uninstall')) {
				$class->uninstall();
			}
		
			$this->redirect($this->url->link('extension/ka_extensions', 'token=' . $this->session->data['token'], 'SSL'));	
		}
	}
	
	/* not used */
	public function inputCode() {	

		$this->data['token'] = $this->session->data['token'];
		$this->data['extension'] = $this->request->get['extension'];
	
		$this->template = 'extension/ka_input_code.tpl';
		$this->response->setOutput($this->render());
	}
	
	/* not used */
	public function activateCode() {
	
		$json = array();		
		
		$args = array(
			'code' => $this->request->post['code']
		);
		$res = $this->load->controller('ka_extensions/' . $this->request->post['extension'] . '/unpack', $args);

		// Check its a directory
//		$json['error'] = "Error!!!";
		$json['success'] = $res;

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
?>