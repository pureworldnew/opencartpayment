<?php
/*
  Project: Ka Extensions
  Author : karapuz <support@ka-station.com>

  Version: 3 ($Revision: 35 $)


  HOW TO USE
  =======================
  
  In any place of template files you can insert a code like this:
  <?php KaElements::showSelector(...); ?> 
    
*/
abstract class KaElements {

	protected static $registry;
	
	public static function init($registry) {
		self::$registry = $registry;
	}

	public static function showTemplate($template, $data) {
	
		if (!isset(self::$registry)) {
			self::$registry = Loader::getRegistry();
		}

		$tdir = '';
		if (!defined('DIR_CATALOG')) {  
			$tdir = self::$registry->get('config')->get('config_template') . '/template/';
		}
		
		echo self::$registry->get('load')->view($tdir . $template, $data);
	}
		
	public static function showSelector($name, $options, $value = '', $extra = '', $first_line = '') {
	
		if (!isset(self::$registry)) {
			self::$registry = Loader::getRegistry();
		}
	
		$data['name']     = $name;
		$data['options']  = $options;
		$data['value']    = $value;
		$data['extra']    = $extra;
		$data['first_line'] = $first_line;

		$tdir = '';
		if (!defined('DIR_CATALOG')) {  
			$tdir = self::$registry->get('config')->get('config_template') . '/template/';
		}

		echo self::$registry->get('load')->view($tdir . "ka_extensions/elements/select.tpl", $data);
 	}
}

?>