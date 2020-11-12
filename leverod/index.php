<?php
// ***************************************************
//           Leverod Framework for Opencart
//       
// Author : Francesco Pisanò - francesco1279@gmail.com
//              
//                   www.leverod.com		
//               © All rights reserved	  
// ***************************************************


// Config
require_once(substr(__FILE__, 0, -9) . 'config.php'); // -9 = index.php	

// Loader
require_once(LEVEROD_PATH . 'system/engine/lev_loader.php');	

// Controller
require_once(LEVEROD_PATH . 'system/engine/lev_controller.php');	

// Model
require_once(LEVEROD_PATH . 'system/engine/lev_model.php');

// Language
require_once(LEVEROD_PATH . 'system/library/language.php');	

// Graphic library
require_once(LEVEROD_PATH . 'system/library/graphics.php');	

// String library
require_once(LEVEROD_PATH . 'system/library/string.php');	

// File library
require_once(LEVEROD_PATH . 'system/library/file.php');	


// JsMinifier
require_once(LEVEROD_PATH . 'system/library/jsminifier.php');	


// Utility library (Class)
 require_once(LEVEROD_PATH . 'system/library/lev_utility.php');	

if (!isset($registry)) { // Oc <=2.3.0.2 & Php < 5.4 
	global $registry;
}

// Loader
$lev_loader = new LevLoader($registry);
$registry->set('levLoad', $lev_loader);

// Controller
$lev_controller = new LevController($registry);
$registry->set('levController', $lev_controller);

// Language
$levLanguage = new LevLanguage($registry);
$registry->set('levLanguage', $levLanguage);

// Utility
$levUtility = new LevUtility($registry);
$registry->set('levUtility', $levUtility);

