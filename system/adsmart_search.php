<?php
// ***************************************************
//               Advanced Smart Search   
//       
// Author : Francesco Pisanò - francesco1279@gmail.com
//              
//                   www.leverod.com		
//               © All rights reserved	  
// ***************************************************

		
defined('ADSMART_SRC_VERSION')			OR define('ADSMART_SRC_VERSION',		'4.5.1'	);
defined('ADSMART_SRC_DEBUG')			OR define('ADSMART_SRC_DEBUG',			false	);
defined('ADSMART_SRC_DEBUG_SHOW_SQL')	OR define('ADSMART_SRC_DEBUG_SHOW_SQL',	false	);
defined('ADSMART_SRC_SPEED_TEST')		OR define('ADSMART_SRC_SPEED_TEST',		false	);
defined('ADSMART_SRC_DEMO_RESET_TIME')	OR define('ADSMART_SRC_DEMO_RESET_TIME',	45	);

defined('ADSMART_SRC_DEMO') OR define('ADSMART_SRC_DEMO', false); // Do not edit this line

// When this file is included from the css adsmartsearch_livesrc_css.php, the Constant 
// DIR_SYSTEM doesn't exist and the library inflect.php is not needed.
if (defined('DIR_SYSTEM')) {
	require_once(DIR_SYSTEM . 'library/inflect.php');
}

$adsmart_search_style = array(	

	'dropdown_img_border_color'	=> 'E7E7E7',
	'dropdown_img_size'			=> 30,
	'dropdown_width'			=> 340,
	'dropdown_text_size'		=> 16,
	'dropdown_text_color'		=> '454545',
	'dropdown_bg_color'			=> 'fafafa',
	'dropdown_border_color'		=> 'E7E7E7',
	'dropdown_hover_bg_color'	=> 'EDEDED',
	
	// Computed Style
	
	'dropdown_lighter_separator_color'	=> 'EDEDED',
	'dropdown_darker_separator_color'	=> 'EDEDED',
	'dropdown_hover_border_color'		=> 'EDEDED',
	'dropdown_hover_bg_color'			=> 'f0f0f0',
	'dropdown_msg_bg_color'				=> 'fafafa',
	'dropdown_msg_text_color'			=> '000000', 
	'dropdown_msg_text_size'			=> 13,		
);										

defined('ADSMART_SEARCH_STYLE') or define('ADSMART_SEARCH_STYLE', serialize ($adsmart_search_style));	


/*

$adsmart_search_js_settings = array(	

	'dropdown_update_on_entire_word'	=> 0,
	'dropdown_display_img'				=> 1,
	'dropdown_show_all'					=> '',
	'dropdown_no_results'				=> '',
	'dropdown_width'					=> '',
);										

defined('ADSMART_SEARCH_JS_SETTINGS') or define('ADSMART_SEARCH_JS_SETTINGS', serialize ($adsmart_search_js_settings));	

*/


