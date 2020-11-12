<?php
// ***************************************************
//               Advanced Smart Search   
//       
// Author : Francesco Pisanò - francesco1279@gmail.com
//              
//                   www.leverod.com		
//               © All rights reserved	  
// ***************************************************

// It's required because constants are defined here
require_once(DIR_SYSTEM . 'adsmart_search.php');

$is_admin = defined('DIR_CATALOG');
$template = $this->config->get('config_template');

// Select the right protocol for the urls that will be prepended to the autocomplete url.  If the protocol or the website name
// doesn't match exactly the url of the current page, browsers will not allow the ajax request. For further details see:
// XMLHttpRequest: Error 0x80070005  Access denied - (visible with IE Development Tools)

// if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {

$is_https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;

if ($is_admin) {

	if ($is_https)	{	$website_url = HTTPS_CATALOG; 
	
	} else			{	$website_url = HTTP_CATALOG; }

} else {

	if ($is_https)	{	$website_url = $this->config->get('config_ssl');
	
	} else			{	$website_url = $this->config->get('config_url'); }
}


// Javascript Settings

$txt_no_results	= $this->config->get('adsmart_search_dropdown_no_results');
$txt_show_all	= $this->config->get('adsmart_search_dropdown_show_all'); 
$relevance		= $this->config->get('adsmart_search_relevance');
$description_relevance = isset($relevance['description'])? $relevance['description'] : '';	

$adsmart_search_js_settings = array (

	'description_relevance'				=> $description_relevance,
	'sort_order'						=> $this->config->get('adsmart_search_sort_order'),
	'dropdown_enabled'					=> $this->config->get('adsmart_search_dropdown_enabled'), 
	'dropdown_text_color'				=> $this->config->get('adsmart_search_dropdown_text_color'), 
	'dropdown_update_on_entire_word'	=> $this->config->get('adsmart_search_dropdown_update_on_entire_word'), 
	'dropdown_display_img'				=> $this->config->get('adsmart_search_dropdown_display_img'),
	'dropdown_display_price'			=> $this->config->get('adsmart_search_dropdown_display_price'),
	'dropdown_show_all'					=> $txt_show_all[(int)$this->config->get('config_language_id')],
	'dropdown_no_results'				=> $txt_no_results[(int)$this->config->get('config_language_id')], 
	'dropdown_width'					=> $this->config->get('adsmart_search_dropdown_width'),
	
	'tpl'								=> $template, 	
	'is_admin'							=> $is_admin,
	'website_url'						=> $website_url,
	
	'version'							=> VERSION,
	'debug'								=> ADSMART_SRC_DEBUG, 
	'debug_show_sql'					=> ADSMART_SRC_DEBUG_SHOW_SQL,	
	'speed_test'						=> ADSMART_SRC_SPEED_TEST, 
);

$this->document->addScript($website_url . 'catalog/view/adsmart_search/js/adsmartsearch_livesrc_js.php?' . http_build_query($adsmart_search_js_settings));




// CSS Settings


$adsmart_search_style = array (

	'dropdown_img_border_color'	=> $this->config->get('adsmart_search_dropdown_img_border_color'),
	'dropdown_img_size'			=> $this->config->get('adsmart_search_dropdown_img_size'),
	'dropdown_width'			=> $this->config->get('adsmart_search_dropdown_width'),
	'dropdown_text_size'		=> $this->config->get('adsmart_search_dropdown_text_size'),
	'dropdown_text_color'		=> $this->config->get('adsmart_search_dropdown_text_color'),
	'dropdown_bg_color'			=> $this->config->get('adsmart_search_dropdown_bg_color'),
	'dropdown_border_color'		=> $this->config->get('adsmart_search_dropdown_border_color'),
	'dropdown_hover_bg_color'	=> $this->config->get('adsmart_search_dropdown_hover_bg_color'),
	
	// Computed Style
	
	'dropdown_lighter_separator_color'	=> $this->config->get('adsmart_search_dropdown_lighter_separator_color'),
	'dropdown_darker_separator_color'	=> $this->config->get('adsmart_search_dropdown_darker_separator_color'),
	'dropdown_hover_border_color'		=> $this->config->get('adsmart_search_dropdown_hover_border_color'),
	'dropdown_hover_bg_color'			=> $this->config->get('adsmart_search_dropdown_hover_bg_color'),
	'dropdown_msg_bg_color'				=> $this->config->get('adsmart_search_dropdown_msg_bg_color'),
	'dropdown_msg_text_color'			=> $this->config->get('adsmart_search_dropdown_msg_text_color'), 
	'dropdown_msg_text_size'			=> $this->config->get('adsmart_search_dropdown_msg_text_size'),		
		
	'tpl'		=> $template, 	
	'is_admin'	=> $is_admin,
		
);

// Do not create urls > 2000 characters
$this->document->addStyle($website_url . 'catalog/view/adsmart_search/css/adsmartsearch_livesrc_css.php?' . http_build_query($adsmart_search_style));
