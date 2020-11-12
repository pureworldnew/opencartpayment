<?php
// ***************************************************
//                      LevUtility
//                     Utility Class       
//
// Author : Francesco Pisanò - francesco1279@gmail.com
//              
//                   www.leverod.com		
//               © All rights reserved	  
// ***************************************************


class LevUtility {

	protected $registry;

	public function __construct($registry=array()) {
		
		$this->registry = $registry;
	
		$this->session = $registry->get('session');			
	}

	
	public function __get($key) {
	
		return $this->registry->get($key);
	}

	
	public function __set($key, $value) {
	
		$this->registry->set($key, $value);
	}

	public function set_user_token_parameter() {

		if (version_compare(VERSION, '3.0.0.0_a1', '>=')) {

			return 'user_token=' . $this->session->data['user_token'];
		
		} else {
			return 'token=' . $this->session->data['token'];
		}	
	}
	
		
	public function is_valid_user_token() {

		if (version_compare(VERSION, '3.0.0.0_a1', '>=')) {

			$token = 'user_token';
		
		} else {
			$token = 'token';
		}	
		
		return isset($_GET[$token]) && isset($this->session->data[$token]) && $_GET[$token] == $this->session->data[$token];
	}
	
	
	public static function is_admin() {

		return defined('DIR_CATALOG');
	}
	
	
	public function loadBootstrap() {

		// Bootstrap 2.1.1 (for jquery 1.7.1, installed from Oc 1.5.2 to 1.5.6.4)
		// Oc 1.5.1.3.1 has jQuery 1.6.1 which is not compatible with Bootstrap 2.1.1)
		if ( version_compare(VERSION, '1.5.6.4', '<=') ) { 
		
			// Bootstrap js 2.1.1
			$this->document->addScript('../leverod/view/javascript/bootstrap/js/bootstrap.min.js');
			
			// Bootstrap css taken from Bootstrap v3.3.1
			$this->document->addStyle('../leverod/view/javascript/bootstrap/css/bootstrap.css');		
		}	
	}

}