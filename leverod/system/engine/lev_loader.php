<?php
// ***************************************************
//           Leverod Framework for Opencart
//       
// Author : Francesco Pisanò - francesco1279@gmail.com
//              
//                   www.leverod.com		
//               © All rights reserved	  
// ***************************************************


final class LevLoader {

	protected $registry;

	
	public function __construct($registry) {
		$this->registry = $registry;
	}
	
	
	public function __get($key) {
		return $this->registry->get($key);
	}

	
	public function __set($key, $value) {
		$this->registry->set($key, $value);
	}
	
	
	public function levModel($model) {
	
		$file  = LEVEROD_PATH . 'model/' . $model . '.php';
		$class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $model);

		if (file_exists($file)) { 
			include_once($file);
			
			$this->registry->set('levModel_' . str_replace('/', '_', $model), new $class($this->registry));
		} else {
			trigger_error('Error: Could not load model ' . $model . '!');
			exit();					
		}
	}	
}