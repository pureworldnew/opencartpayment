<?php
// ***************************************************
//                       Language
//                         
//
// Author : Francesco Pisanò - francesco1279@gmail.com
//              
//                   www.leverod.com		
//               © All rights reserved	  
// ***************************************************


class LevLanguage {

	protected $registry;

	public function __construct($registry) {
		
		$this->registry = $registry;
	
		$this->config = $registry->get('config');
		$this->load = $registry->get('load');
		$this->language = $registry->get('language');			
	}

	
	public function __get($key) {
	
		return $this->registry->get($key);
	}

	
	public function __set($key, $value) {
	
		$this->registry->set($key, $value);
	}

	
	/**
	 * Language loader
	 *
	 * @param string	$filename  			language filename
	 * @param array		$lang_array			language array(s) from the back end. The function can handle infinite language arrays
	 * 
	 * @return array
	 */ 
	
	public function load($filename, $lang_array = array() /* , $lang_array1, ... */ ) {

		$language = $this->language->load($filename);	

		$args = func_get_args();

		array_shift($args); // shift the the language file argument off the beginning of the array
		
		foreach ($args as $lang_array) {
			
			if (is_array($lang_array) && !empty($lang_array) ) { // check whether the variable is an array, in some
																 //	cases it might be NULL value or empty an array
				$language_array_to_merge = array();

				if (isset($lang_array[$this->config->get('config_language_id')])) {
				
					foreach ($lang_array[$this->config->get('config_language_id')] as $key => $value ) {
						
						// If value is empty use the $key as check mark
						
						if (!empty($value)) {
							$language_array_to_merge[$key] = html_entity_decode($value);
						} else {
							$language_array_to_merge[$key] = $key;
						}
					}
		
				} else {
				
					// In case a language has been installed after the installation of an extension that uses this class,
					// return an array where values are the keys of the strings to translate.
					
					$keys = array_keys(reset($lang_array));
				
					foreach ($keys as $key) {
						$language_array_to_merge[$key] = $key;
					}	
				}
				$language = array_merge($language_array_to_merge, $language);
				
			}
		}

		return $language;	
	}
	
	
	/**
	 * Get language info
	 *
	 * @return array
	 */ 
	
	public function getLanguages() {
	
		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();
				
		// Change flag paths on Oc >= 2.2.0.0	
		foreach ($languages as $i => $language) {
		
			$languages[$i]['image_path'] =  version_compare(VERSION, '2.1.0.2', '<=')  ?   'view/image/flags/'.$language['image']   :   'language/'.$language['code'].'/'.$language['code'].'.png';
		}
		
		return $languages;
	}	
		
	
}