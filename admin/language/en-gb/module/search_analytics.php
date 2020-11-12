<?php

// ***************************************************
//                  Search Analytics    
//  
//       Standalone extension and component of 
//               Advanced Smart Search
//
// Author : Francesco Pisanò - francesco1279@gmail.com
//              
//                   www.leverod.com		
//               © All rights reserved	  
// ***************************************************


// Heading

$ext_name = 'Search Analytics';
$ext_ver = '1.3.1';


if (strpos($_GET['route'], substr(basename(__FILE__), 0, -4)) === false && strpos($_GET['route'], 'layout') === false) {
	$_['heading_title']    = '<span style="position:relative; padding-left:26px;font-weight:bold;"><img style="width:20px; position:absolute; bottom:-3px; left:-1px;" src="view/image/src_ analytics_ logo.gif" />' . $ext_name . ' ' . $ext_ver .'</span>';
}
else {
	$_['heading_title']    = $ext_name . ' ' . $ext_ver;
}



// Text
$_['text_module']					= 'Modules';
$_['text_success']					= 'Success: You have modified module ' . $ext_name . '!';

$_['text_export_csv']				= 'Export CSV';

$_['text_delete_history']			= 'Clear history';
$_['text_confirm_delete_history']	= 'Are you sure you want to delete the search history?';
$_['text_confirm_delete_rows']		= 'Delete {s0} row{s1}?';

$_['text_hbar_pie_label']			= '<b>{s0}</b> searches {s1} from<br /><b>{s2}</b> to <b>{s3}</b>';
$_['text_bar_line_label']			= '{s0} {s1} search{s2} {s3} {s4}';

$_['text_compare']					= 'Compare';
$_['text_added']					= 'Added';

$_['text_search_history']			= 'Search history';
$_['text_top_searches']				= 'Top searches';
$_['text_top_searches_n']			= 'Top 10 searches (n)';
$_['text_top_searches_percent']		= 'Top 10 searches (%)';

$_['text_total_daily_searches']		= 'Total daily searches';
$_['text_total_monthly_searches']	= 'Total monthly searches';
$_['text_total_yearly_searches']	= 'Total yearly searches';
$_['text_page_number']				= 'Displaying {s0} of {s1} results';


// Table column names
$_['text_keyphrase']				= 'Keyphrase';
$_['text_total']					= 'Total Searches';

$_['text_id']						= 'Id';
$_['text_ip']						= 'IP';
$_['text_customer_name']			= 'Customer Name';
$_['text_date']						= 'Date';
$_['text_time']						= 'Time';
$_['text_delete']					= 'Delete selected';

// Entry
$_['entry_date_start']				= 'From:';
$_['entry_date_end']				= 'To:';
$_['entry_filter_keyphrase_0']		= 'keyphrase 1';
$_['entry_filter_keyphrase_1']		= 'keyphrase 2';
$_['entry_filter_keyphrase_2']		= 'keyphrase 3';
$_['entry_exact_match']				= 'Exact match';
$_['entry_broad_match']				= 'Broad match';
$_['entry_day']						= 'Daily';
$_['entry_month']					= 'Monthly';
$_['entry_year']					= 'Yearly';


// Buttons
$_['button_filter']					= 'Filter';
$_['button_reset']					= 'Reset';

// Error

$_['error_permission']      = 'Warning: You do not have permission to modify '.$ext_name .'!';
$_['success_install']		= 'To complete the installation click <b>Edit</b>, then click <b>Save</b>.';
$_['success_uninstall']		= '<b>'.$ext_name .'</b> uninstalled successfully.';
$_['error_install']			= 'Warning: Unable to install <b>'.$ext_name .'</b>. XML missing?';
$_['error_uninstall']		= 'Warning: An error occurred while uninstalling <b>'.$ext_name .'</b>. XML missing?';
