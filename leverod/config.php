<?php
// ***************************************************
//           Leverod Framework for Opencart
//       
// Author : Francesco Pisanò - francesco1279@gmail.com
//              
//                   www.leverod.com		
//               © All rights reserved	  
// ***************************************************


// Version

defined('LEVEROD_VER') or define('LEVEROD_VER', '1.3.2');

defined('LEVEROD_PATH') or define('LEVEROD_PATH', substr(DIR_SYSTEM, 0, -7) . 'leverod/' );  // -7 = system/ -		// trailing slash

defined('ROOT_PATH') or define('ROOT_PATH', substr(DIR_SYSTEM, 0, -7) . '' );										// trailing slash

defined('IMAGE_RELATIVE_PATH') or define('IMAGE_RELATIVE_PATH', substr(DIR_IMAGE, - (strlen(DIR_IMAGE) - strlen(ROOT_PATH)) ));	// trailing slash
