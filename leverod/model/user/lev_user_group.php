<?php
// ***************************************************
//           Leverod Framework for Opencart
//       
// Author : Francesco Pisanò - francesco1279@gmail.com
//              
//                   www.leverod.com		
//               © All rights reserved	  
// ***************************************************


class ModelUserLevUserGroup extends LevModel {

	/**
	 * Set user permissions
	 *
	 * @param array		$permissions  	List of permissions (access, modify)
	 * @param string	$route			Controller route
	 * @param string	$user_group		If the user group is not specified, permissions will be set 
	 *                                  for the current user group
	 * 
	 * @return -
	 */ 
	
	public function setPermissions($permissions, $route, $user_group = '') {
		
		if (version_compare(VERSION, '2.0.0.0', '<=')) {
		
			$id = $this->user->getId();
	
		} else {
			$id = $this->user->getGroupId();	
		}
	
		$this->load->model('user/user_group');
		
		foreach ($permissions as $permission) {
		
			$this->model_user_user_group->addPermission($id, $permission, $route);
		}
	
		/*
		DISABLED: now files are moved from the old location to the new one.

		// Create a dummy file for Opencart 2.3+, it's necessary to correctly set the group permissions.	
		if (version_compare(VERSION, '2.3.0.2', '>=') && substr($route, 0, 7 ) === 'module/' ) {

			$content = '// This file allows to correctly set user permissions on Opencart 2.3+. Do not delete it.';
			$fp = fopen(ROOT_PATH . 'admin/controller/extension/' . $route . '.readme', 'wb');
			fwrite($fp,$content);
			fclose($fp);
			
			// Set user permissions for routes like "extension/module/mymodule" (on Oc < 2.3 the same route is "module/mymodule"):
			foreach ($permissions as $permission) {
			
				$this->model_user_user_group->addPermission($id, $permission, 'extension/' . $route);
			}
		}	
		*/
		
    }
	
	
	
	/**
	 * Unset user permissions
	 *
	 * @param array		$permissions  	List of permissions (access, modify)
	 * @param string	$route			Controller route
	 * @param string	$user_group		If the user group is not specified, permissions will be set 
	 *                                  for the current user group
	 * 
	 * @return -
	 */ 
	 
	public function unsetPermissions($permissions, $route, $user_group = '') {
		
		if (version_compare(VERSION, '2.0.0.0', '<=')) {
		
			$id = $this->user->getId();
	
		} else {
			$id = $this->user->getGroupId();	
		}
	
		$this->load->model('user/user_group');
		
		foreach ($permissions as $permission) {
		
			$this->model_user_user_group->removePermission($id, $permission, $route);
		}
		
		if (version_compare(VERSION, '2.3.0.2', '>=') && substr($route, 0, 7 ) === 'module/' ) {

			// Unset user permissions for routes like "extension/module/mymodule" (on Oc < 2.3 the same route is "module/mymodule"):
			foreach ($permissions as $permission) {
			
				$this->model_user_user_group->removePermission($id, $permission, 'extension/' . $route);
			}
		}			
    }
	
}
