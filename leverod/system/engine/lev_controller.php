<?php
// ***************************************************
//           Leverod Framework for Opencart
//       
// Author : Francesco Pisanò - francesco1279@gmail.com
//              
//                   www.leverod.com		
//               © All rights reserved	  
// ***************************************************


class LevController extends Controller {

	protected $registry;

	protected $admin_routes;
	
	protected $levUtility;
	
	protected $extension_page_path;
	
	public function __construct($registry) {

		$this->registry = $registry;

		// Variable Porting
		$this->variable_porting();	
		
		$this->levUtility = $this->registry->get('levUtility');
	}
	
	
	public function __get($key) {
	
		return $this->registry->get($key);
	}

	
	public function __set($key, $value) {
	
		$this->registry->set($key, $value);
	}
	
	
	protected function variable_porting() {

		$config_vars = array();
		
																																			//		KEY					VALUE  					
		$config_vars =	array(	'config_admin_limit'				=> 'config_limit_admin',												//	oc <= 1.5.6.4		oc >= 2.0.0.0 
								'config_mail_smtp_host'				=> 'config_mail_smtp_hostname',											//	oc <= 2.0.2.0		oc >= 2.0.3.1	
								'config_product_description_length'	=>	$this->config->get('config_theme') . '_product_description_length',	//	oc <= 2.1.0.2		oc >= 2.2.0.0 
								'config_template'					=>	'theme_default_directory',											//	oc <= 2.2.0.0		oc >= 2.3.0.0 
						);
	
		foreach ($config_vars as $old_key => $new_key) {

			if ($this->config->get($new_key) == null && $this->config->get($old_key) ) {
			
				$this->config->set($new_key, $this->config->get($old_key) );
			
			} else if ($this->config->get($old_key) == null && $this->config->get($new_key) ) {
				
				$this->config->set($old_key, $this->config->get($new_key) );
			}
		}	

			
		if (LevUtility::is_admin()) {
			if (version_compare(VERSION, '1.5.6.4', '<=')) {
				$this->admin_routes['home'] = 'common/home';
			} else {
				$this->admin_routes['home'] = 'common/dashboard';
			}
			
			/*
			if (version_compare(VERSION, '3.0.0.0_a1', '>=')) {

				if (!isset($this->session->data['token'])) {
					$this->session->data['token'] = &$this->session->data['user_token'];
				}
			}	
			*/
		}		
	}
	
		
	protected function load_children($children, $data) {

		if (version_compare(VERSION, '1.5.6.4', '<=')) {
		
			$routes = array();
			foreach ($children as $child) {
		
				$routes[] = $child[1];
			}	
			$this->children = $routes;
		}
		else {
		
			foreach ($children as $child) {
		
				$var_name = $child[0];
				$route	  = $child[1];
				$args     = $child[2];
		
				$data[$var_name] = $this->load->controller($route, $args);
			}	
		}
		
		return $data;
	}
	
	
	protected function load_view($path, $data, $set_output = true, $engine = 'template') {
	
		// Small fix for oc 3.0.0.0_a1
		$engine = version_compare(VERSION, '3.0.0.0_a1', '==')? 'php' : 'template';
	
	
		// Opencart 3+ - Template engine manager:
		if ( version_compare(VERSION, '3.0.0.0_a1', '>=') ) {
				
			// Get the current template engine, later we will restore this value to 
			// allow other extension to correctly load their templates
			$current_template_engine = $this->config->get('template_engine');
		
			// Change the template engine:
			$this->config->set('template_engine', $engine);		
		}
	
		if ( version_compare(VERSION, '2.1.0.2', '<=') ) {
			$path .= '.tpl';
		}
		
	// CATALOG
	
		if (!LevUtility::is_admin()) {
		
			if ( version_compare(VERSION, '1.5.6.4', '<=') ) {

				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/' . $path)) {
					$this->template = $this->config->get('config_template') . '/template/' . $path;
				} else {
					$this->template = 'default/template/' . $path;
				}

				$this->data = $data;
				
				$view = $this->render();

			} else {
			
				if ( version_compare(VERSION, '2.1.0.2', '<=') ) {
			
					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/' . $path)) {
					
						$view = $this->load->view($this->config->get('config_template') . '/template/' . $path, $data);
					} else {
						$view = $this->load->view('default/template/' . $path, $data);
					}
				} else {
				
					if ( version_compare(VERSION, '2.3.0.2', '<=') ) {
				
						$view = $this->load->view($path, $data);
				
					} else { // OC 3
					
						// 'config_template' = 'theme_default_directory', see variable porting
					
						if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/' . $path)) {
						
							$view = $this->load->view($this->config->get('config_template') . '/template/' . $path, $data);
						} else {
							$view = $this->load->view('default/template/' . $path, $data);
						}
					}
				}
			}
		}

		
	// ADMIN
	
		if (LevUtility::is_admin()) {
		
			if ( version_compare(VERSION, '1.5.6.4', '<=') ) {

				$this->template = $path;
			
				$this->data = $data;
				
				$view = $this->render();

			} else {

				$view = $this->load->view($path, $data);
			}
		}

		// OC 3+ Restore the previous template engine
		if ( version_compare(VERSION, '3.0.0.0_a1', '>=') ) {
			$this->config->set('template_engine', $current_template_engine);	
		}
		
		if ($set_output) {
			
			$this->response->setOutput($view);
		} else {

			return $view;
		}	
	}
	
	
	// Admin Edit Module Settings		
	protected function editModuleSetting($module_id = 0, $module_name, $data, $multiple_instance_module = true) {
	
		$store_id = isset($data['store_id'])? $data['store_id'] : 0;
	
		$this->load->model('setting/setting');
		
		// For the demo 
		//(The group is not $module_name but $module_name . '_demo', to prevent deleting the existing settings (see model->setting->setting->editSetting)
		// Reminder: the group/code name and each key must start with the same string ($MODULE_NAME . '_DEMO' --> $MODULE_NAME . '_DEMO_last_save')
		$this->model_setting_setting->editSetting($module_name . '_demo', array($module_name . '_demo_last_save' => date('Y-m-d H:i:s')), $store_id);
		

		if ( version_compare(VERSION, '2.0.0.0', '<=') ) {

			// $data['module'] is set on modules with multiple instances like "Cart module". Modules like "Out of stock"
			// have a single instance and they just save settings in the db table "Setting".
			// Note: 
			// On Opencart <= 2.0.0.0 there is no "module" table in the database, that's why we need to save module settings
			// in $data['module'] (it's serialized)
			
			if (isset($data['module'])) {
			
				$data[$module_name . '_module'] = $data['module'];
			}
			
			
			$this->model_setting_setting->editSetting($module_name, $data, $store_id);

		} else {
		
			if (!empty($multiple_instance_module)) {
				
				if (version_compare(VERSION, '2.3.0.2', '<=')) {			 // From Oc 2.0.1.0 to 2.3.0.2
				
					$this->load->model('extension/module');
				}
				
				if (version_compare(VERSION, '3.0.0.0_a1', '>=')) {			//  Oc 3+

					$this->load->model('setting/module');
				}
				
				// $data may contain other elements than the module settings (like the button "save_stay")
				// Extract the module settings from the array $data (their key ends by "module") and format
				// them to be compatible with Oc >= 2.0.1.0 (copy $data['xyz_module']['randomkey'] to $data
				
				// Extract the module settings ($data[xyz_module])
				foreach($data as $key => $value) {
					if(substr($key, -6) == 'module') {
					

						$data = reset($data[$key]); // copy $data['xyz_module']['randomkey'] to $data
													// reset(array()) returns the first element of the array
						break;

					}
				}
				
				// Module settings:
				
				if ($module_id) {
					
					if (version_compare(VERSION, '2.3.0.2', '<=')) { 
					
						$this->model_extension_module->editModule($module_id, $data);	// Oc 2
					
					} else {
						$this->model_setting_module->editModule($module_id, $data);		// Oc 3+
					}
			
				} else {
				
					if (version_compare(VERSION, '2.3.0.2', '<=')) { 
					
						$this->model_extension_module->addModule($module_name, $data);	// Oc 2
					} else {	
						$this->model_setting_module->addModule($module_name, $data);	// Oc 3+
					}
					
					$module_id = $this->db->getLastId();	
				}
				
					
			} else {
				$this->model_setting_setting->editSetting($module_name, $data);
			}	
		}
		
		// On Oc <= 2.0.0.0 it will be returned a 0
		return $module_id;
		
	}
	
	
	// Admin Get Module Settings (For Oc 2.0.1.0 and later)
	protected function getModuleSetting($module_id) {
		
		$module_info = array();
		
		if (version_compare(VERSION, '2.0.1.0', '>=')) {
		
			if (version_compare(VERSION, '2.3.0.2', '<=')) {
			
				$this->load->model('extension/module');
				$module_info = $this->model_extension_module->getModule($module_id);
			
			} else {
			
				$this->load->model('setting/module');
				$module_info = $this->model_setting_module->getModule($module_id);
			}	
		}
		
		return $module_info;
	}
	
	
	protected function editSettingValue($group, $key, $value, $store_id = 0) {
	
		if ( version_compare(VERSION, '2.0.0.0', '>=') ) {	
		
			$this->load->model('setting/setting');
		
			$this->model_setting_setting->editSettingValue($group, $key, $value, $store_id);
		
		} else {
		
			// On Opencart <= 1.5.6.4 the Model function editSettingValue() is buggy, 
			// it has been replaced with the following query (not yet in a model file :P ) 
			if (!is_array($value)) {
				$this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = '" . $this->db->escape($value) . "' WHERE `group` = '" . $this->db->escape($group) . "' AND `key` = '" . $this->db->escape($key) . "' AND store_id = '" . (int)$store_id . "'");
			} else {
			
				if ( version_compare(VERSION, '1.5.0.5', '<=') ) {
			
					$this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = '" . $this->db->escape(serialize($value)) . "' WHERE `group` = '" . $this->db->escape($group) . "' AND `key` = '" . $this->db->escape($key) . "' AND store_id = '" . (int)$store_id . "'");
			
				} else {
			
					$this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = '" . $this->db->escape(serialize($value)) . "', serialized = '1' WHERE `group` = '" . $this->db->escape($group) . "' AND `key` = '" . $this->db->escape($key) . "' AND store_id = '" . (int)$store_id . "'");
				}
			}
		}
	}
	
	
	protected function levRedirect($url) {

		if ( version_compare(VERSION, '2.0.0.0', '>=') ) {
			$this->response->redirect($url);
		} else {
			$this->redirect($url);
		}
	}
	
	
	// Oc 2.3.0.2+ Compatibility bug fixes 

	protected function extensionRouteBugfix($route) {

	
// 1)	// *** Buggy events made to solve compatibility issues with extension routes from oc 2.2.0.0 to oc 2.3.0.2+) ***
		// A pre-event trimmed off the last part of the route (install & uninstall) and it caused a call to the wrong method
		// (by default is "index()"). 
		
		if (version_compare(VERSION, '2.3.0.2', '>=')) {
		
	// ADMIN
			if(LevUtility::is_admin()) {
	
				$parts = explode('/', $route);
			
				$extension_type = $parts[0]; // module, payment, etc.
		
				$extension = !empty($this->request->get['extension'])? $this->request->get['extension'] : '';
			
				if ($extension) {
				
					$methods = array('install', 'uninstall');
					
					foreach ($methods as $method) {
	 
						if ( method_exists($this, $method) && $this->request->get['route'] == 'extension/extension/' . $extension_type . '/'. $method) {
		 
							$this->$method();	
							
							$this->levRedirect($this->url->link('extension/extension/'.$extension_type, $this->levUtility->set_user_token_parameter(), 'SSL'));
						}
					}
				}
	// CATALOG
			} else {
				return;
			}	
		}
	
	
// 2)	// Rename the folders 	admin/controller/extension/module	to admin/controller/extension/module_
		//						admin/controller/extension/payment	to admin/controller/extension/payment_
		// on Opencart <= 2.0.3.1 the following folders must be renamed (they are required by Opencart 2.3.0.2+)
		// (if they are not renamed, module and payment pages return a "Page not found")
		
		if (version_compare(VERSION, '2.0.3.1', '<=')) {
			$dirs = array(	'admin/controller/extension/module',
							'admin/controller/extension/payment'
			);
			foreach ($dirs as $dir) {
			
				if (is_dir(ROOT_PATH . $dir)) {
					rename(ROOT_PATH . $dir, ROOT_PATH . $dir . '_'); // add an underscore at the end of the dir name.
				}
			}
		}	
	}
	
	
	/**
	 * Set user permissions - Alias of levModel_user_user_group->setPermissions
	 *
	 * @param array		$permissions  	List of permissions (access, modify)
	 * @param string	$route			Controller route
	 * @param string	$user_group		If the user group is not specified, permissions will be set 
	 *                                  for the current user group
	 * 
	 * @return -
	 */ 
	
	public function setPermissions($permissions, $route, $user_group = '') {
		
		$this->levLoad->levModel('user/lev_user_group');	
		
		$this->levModel_user_lev_user_group->setPermissions($permissions, $route, $user_group);	
    }
	
	
	
	/**
	 * Unset user permissions - Alias of levModel_user_user_group->unsetPermissions
	 *
	 * @param array		$permissions  	List of permissions (access, modify)
	 * @param string	$route			Controller route
	 * @param string	$user_group		If the user group is not specified, permissions will be set 
	 *                                  for the current user group
	 * 
	 * @return -
	 */ 
	 
	public function unsetPermissions($permissions, $route, $user_group = '') {
		
		$this->levLoad->levModel('user/lev_user_group');	
		
		$this->levModel_user_lev_user_group->unsetPermissions($permissions, $route, $user_group);			
    }
	
	
	
	/**
	 * Check user permission - Alias of levModel_user_user_group->hasPermission
	 *
	 * @param string	$permission  	permissions ("access" or "modify")
	 * @param string	$route			Controller route
	 * @param string	$user_group		If the user group is not specified, permissions will be set 
	 *                                  for the current user group
	 * 
	 * @return boolean
	 */ 
	 
	public function hasPermission($permission, $route, $user_group = '') {
		
		$this->levLoad->levModel('user/lev_user_group');	
		
		$this->levModel_user_lev_user_group->hasPermission($permission, $route, $user_group);				
    }
	
	
	
	
	/**
	 * Vqmod initialization
	 *
	 * @param array		$extension_list  	extension list
	 * 
	 * @return -
	 */ 
	public function levVqmodInit($extensions) {
	
		$this->vqmod_scripts	= $extensions;
		
		$this->vqmod_dir		= ROOT_PATH . 'vqmod/';
		$this->vqmod_xml_dir 	= ROOT_PATH . 'vqmod/xml/';
		$this->vqcache_files	= ROOT_PATH . 'vqmod/vqcache/vq*';
		$this->vqmod_modcache	= ROOT_PATH . 'vqmod/mods.cache';
		
		clearstatcache();
	}
	
	
	
	/**
	 * Vqmod clear cache
	 *
	 * @return true
	 */ 

	public function clear_vqcache() {
	
		$files = glob($this->vqcache_files);
		if ($files) {
			foreach ($files as $file) {
				if (is_file($file)) {
					unlink($file);
				}
			}
		}
		if (is_file($this->vqmod_modcache)) {
			unlink($this->vqmod_modcache);
		}
		return true;
	}
	
	
	
	/**
	 * Enable vqmod xml
	 *
	 * @param array		$vqmod_xmls  	array of xml files
	 *
	 * @return boolean
	 */ 

	public function enableVqmodXml($vqmod_xmls = array()) {
	
		$vqmod_xmls = !empty($vqmod_xmls)? $vqmod_xmls : $this->vqmod_scripts;
	
		$result = false;
	
		foreach ($vqmod_xmls as $vqmod_xml) {
		
			// If there is no need of renaming the file
			if (is_file($this->vqmod_xml_dir . $vqmod_xml . '.xml')) {

				$this->clear_vqcache();
				$result = true;
			
			// If the extension was previously uninstalled
			} elseif (is_file($this->vqmod_xml_dir . $vqmod_xml . '.xml_')) {
			
				rename($this->vqmod_xml_dir . $vqmod_xml . '.xml_', $this->vqmod_xml_dir . $vqmod_xml . '.xml');

				$this->clear_vqcache();
				$result = true;
			
			} else {
			
				$result = false;
				break;
			}
		}
		return $result;
	}
	
	
	
	/**
	 * Disable vqmod xml
	 *
	 * @param array		$vqmod_xmls  	array of xml files
	 *
	 * @return boolean
	 */ 
	public function disableVqmodXml($vqmod_xmls = array()) {
	
		$vqmod_xmls = !empty($vqmod_xmls)? $vqmod_xmls : $this->vqmod_scripts;
	
		$result = false;

		foreach ($vqmod_xmls as $vqmod_xml) {

			// Rename the vQmod file extension from .xml to .xml_ and clear the vQmod cache
			if (is_file($this->vqmod_xml_dir . $vqmod_xml . '.xml')) {
				
				rename($this->vqmod_xml_dir . $vqmod_xml . '.xml', $this->vqmod_xml_dir . $vqmod_xml . '.xml_');

				$this->clear_vqcache();
				$result = true;
			
			// Return "true" if the file has already the extension .xml_
			} else if (is_file($this->vqmod_xml_dir . $vqmod_xml . '.xml_')) {
			
				$result = true;
				
			} else {
			
				$result = false;
				break;	
			}
		}
		return $result;
	}
	
}