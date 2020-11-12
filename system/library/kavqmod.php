<?php
/*
	Project: Ka Extensions
	Author : karapuz <support@ka-station.com>

	Version: 3 ($Revision: 27 $)

	
	DESCRIPTION:
	
	This class is a wrapper for multiple vqmod interfaces.	
*/

abstract class KaVQMod {

	public static function modCheck($file) {
		global $vqmod;

		if (class_exists('VQMod')) {
		
			if (isset($vqmod) && is_object($vqmod)) {
				return $vqmod->modCheck($file);
			}

			if (isset(VQMod::$_vqversion)) {
				return VQMod::modCheck($file);
			}
		}
		
		return $file;	
	}
}

?>