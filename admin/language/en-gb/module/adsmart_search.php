<?php
// ***************************************************
//               Advanced Smart Search   
//       
// Author : Francesco Pisanò - francesco1279@gmail.com
//              
//                   www.leverod.com		
//               © All rights reserved	  
// ***************************************************


// Admin language file

require_once(DIR_SYSTEM . 'adsmart_search.php');

$ext_name = 'Advanced Smart Search '. ADSMART_SRC_VERSION;

if (strpos($_GET['route'], substr(basename(__FILE__), 0, -4)) === false && strpos($_GET['route'], 'layout') === false) {
	$_['heading_title']    = '<span style="position:relative; padding-left:26px;font-weight:bold;"><img style="width:20px; position:absolute; bottom:-3px; left:-1px;" src="view/image/adsmartsrc_logo.jpg" />' . $ext_name .'</span>';
}
else {
	$_['heading_title']    = $ext_name;
}

// Text

if (defined('ADSMART_SRC_DEMO_RESET_TIME'))  $demo_reset_time = ADSMART_SRC_DEMO_RESET_TIME;
else $demo_reset_time = '';

$_['text_demo_postit']			=  'On the demo store settings get reset every ' . $demo_reset_time . ' minutes';

$_['text_test_it']				= 'Type some product name to test the Live Search';
$_['text_test_it_placeholder']	= 'Search';
$_['text_keep_open']			= 'Keep open the dropdown list';

$_['text_search_analytics']	= 'Search Analytics';
$_['text_search_options']	= 'General Search Options';
$_['text_style']    		= 'Live Search Options &amp; Style';
$_['text_user_guide']		= 'User Guide';
$_['text_license']			= 'License';

$_['text_enable_module']	= 'Enable ' . $ext_name;
$_['text_search_algorithm']	= 'Search Algorithm';
$_['text_default']			= 'Default';
$_['text_fast']				= 'Fast';

$_['text_match_type']		= 'Match type';
$_['text_exact_match']		= 'Exact Match';
$_['text_broad_match']		= 'Broad Match';

$_['text_sort_order']		= 'Default Sort Order';
$_['text_optn_relevance']	= 'Relevance';
$_['text_optn_date_desc']	= 'Date (New > Old)';
$_['text_optn_date_asc']	= 'Date (Old > New)';


/*
$_['text_optn_default']		= 'Default';
$_['text_optn_name_asc']	= 'Name (A-Z)';
$_['text_optn_name_desc']	= 'Name (Z-A)';
$_['text_optn_price_asc']	= 'Price (Low > High)';
$_['text_optn_price_desc']	= 'Price (High > Low)';
$_['text_optn_rating_asc']	= 'Rating (Lowest)';
$_['text_optn_rating_desc']	= 'Rating (Highest)';
$_['text_optn_model_asc']	= 'Model (A-Z)';
$_['text_optn_model_desc']	= 'Model (Z-A)';
*/

$_['text_translate_extra_sort']	= 'Custom texts for the new types of sort orders';
$_['text_add_translation']		= 'Add a translation!';

$_['text_relevance']			= 'Fields to search within &nbsp;&nbsp;&nbsp; + &nbsp;&nbsp;&nbsp; Relevance Setup';
$_['text_enable_all']			= 'Enable all';
$_['text_disable_all']			= 'Disable all';
$_['text_field_name']			= 'Product Field';

$_['text_hide_zeroqty_products']	= 'Hide 0 quantity products from searches';
$_['text_include_plurals']			= 'Include plurals';

$_['text_include_partial_words']	= 'Include partial words';
$_['text_partial_word_length']		= 'Minimum partial word length';
$_['text_current_search_algorithm']	= 'Current Search Algorithm';
$_['text_what_is_this']				= 'What is this?';


$_['text_include_misspellings']	= 'Detect misspellings';
$_['text_misspelling_tolerance']= 'Misspelling tolerance';


$_['text_cache_manager']			= 'Cache Manager';
$_['text_cache_update_frequency']	= 'Cache update frequency';
$_['text_update_search_cache']		= 'Update cache now';
$_['text_clear_search_cache']		= 'Clear cache';
$_['text_search_cache_updated']		= 'Cache updated!';
$_['text_search_cache_cleared']		= 'Cache cleared!';

$_['text_dialog_update_cache_title']	= 'Update cache before saving?';
$_['text_dialog_update_cache_text']		= '<span>The search cache might not be updated with the current search settings. Choose one of the following options:</span><ul><li><b>Save and update the cache (recommended)</b>. If the cache is too big, the update process might take several seconds to complete.</li><li><b>Save and clear the old cache.</b></li><li><b>Save only</b>. The future search results not previously cached will be cached with the new settings. The old search results still in cache <u>will be displayed to users</u> until they expire at the given expiration time. </li></ul>';
	
$_['text_dialog_algorithm_fast_and_indexing_title']	= 'Table indexing required';
$_['text_dialog_algorithm_fast_and_indexing_text']	= 'To improve the search speed, this feature will add FULL TEXT indexes to your database. Do you want to continue?';

$_['text_no_indexing_then_default_algorithm_title']	= 'Switch algorithm type?';
$_['text_no_indexing_then_default_algorithm_text']	= 'The current search algorithm is set to FAST. If you disable table indexing, the search algorithm will be set to DEFAULT. Do you want to continue?';


	
$_['text_db_optimization']		= 'Database optimization';
$_['text_index_db_tables']		= 'Index database tables';
$_['text_rebuild_indexes']		= 'Rebuild db indexes?';
$_['text_rebuild']				= 'Rebuild';
$_['text_indexes_rebuilt']		= 'Indexes have been rebuilt.';
$_['text_wait_slow_indexing']	= 'Please wait, indexing is requiring more time than expected...';

$_['text_curr_srv_conf']		= 'Current MySQL server configuration';	
$_['text_mysql_setting_name']	= 'Setting';	
$_['text_mysql_var_value']		= 'Value';	

$_['text_product_stats']	= 'Product Stats';	
$_['text_product_total']	= 'Number of active products';	
				
				
	

// Help

$_['text_help_fast']				= 'It provides <b>fast searches</b> also on large databases. It is always recommended to use this algorithm, unless you need specific features available only with the default algorithm.';

$_['text_help_default']				= 'The main difference with the fast algorithm is the ability to also find <i>"words inside words"</i>, not just <i>"words beginning with"</i> (the option <b>include partial words</b> must be enabled).';
	
$_['text_help_exact_match']			= 'Search results match <b>all</b> the words of the search query. Words may be in any order. <a href="#exact-match">Read more and see some examples</a>';

$_['text_help_broad_match']			= 'Results contain <b>at least one or more</b> keywords from the search query, in any order. The search query may have extra words not present in any product field. <a href="#broad-match">Read more</a>.';

$_['text_help_sort_order']			= 'Select the default sort order for the result list. Notes: <br/>- If you choose the sort order "<i>Relevance</i>", remember to configure it in the section <b>Relevance Setup</b>.<br />- The sort order "<i>Default</i>" can be configured from each Product page (from the back end, go to <i>Catalog</i> -> <i>Products</i> -> <i>Edit</i> -> tab <i>Data</i> -> <i>Sort order</i>.';	

$_['text_help_translate_sort_order']= 'Translate/change texts for the new sort order types available with this extension. <b>Texts for the other built-in sort orders (name, price ecc.) are read from the Opencart language files</b>.';	


$_['text_help_relevance']			= 'A product field is a container of text stored in the database. Select <b>which product fields to search within</b> by clicking on the ckecboxes on the left of each field name. To optimize the search speed, select only fields containing <b><i>keywords</i></b> useful to identify a product.<br /><br />Next, setup the <b>field relevance</b>. Choose which fields are more/less relevant by moving them up or down on the list (move the mouse on a field and when you see the four headed arrow cursor, drag and drop it in the desired position). <a  href="#relevance">Click here to learn more</a> about this feature.';


$_['text_help_partial_words']		= 'This option extends matches to partial words. For example, the word <b><i>shelf</i></b> will also match compound words like <b>book<i class="match">shelf</i></b>. Partial word searches are performed by the two search algorithms <b>Defalut</b> and <b>Fast</b> in two slightly different ways. <a href="#partial-words">Click here to learn more</a>.';


$_['text_help_db_optimization']		= 'Select this checkbox to speed up the search process. <a href="#index-tables">Read more</a> to learn what FULL TEXT indexes are and how they can improve query performances. To create indexes, just select the checkbox and click on <b>Save</b>.<br />The button "Rebuild" (displayed when the checkbox is checked) can be used to rebuild broken indexes or after updating MySQL configuration files (these files can be modified on VPS/dedicated servers only, read the paragraph <a href="#mysql-config">Fine tuning MySQL configuration</a>). For large databases, rebuilding indexes may require several time. <a href="#rebuild-indexes">Read more</a>';


$_['text_help_add_modules']			= 'Do you need a search box on a particular layout and position? Then use this feature to add your custom search field!';

$_['text_help_update_single_char']	= 'Recommended for: <br /> - Fast servers<br /> - small/medium databases<br /> - a well balanced search engine configuration. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#refresh-mode">Read more</a>';

$_['text_help_update_entire_word']	= 'Recommended for: <br /> - slow servers<br /> - big databases<br /> - a too heavy search engine configuration. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#refresh-mode">Read more</a>';

$_['text_preset_styles']			= 'Preset Styles';

$_['text_enable_live_search']		= 'Enable Live Search';

$_['text_add_modules']				= 'Add your custom search boxes';
$_['text_content_top']    			= 'Content Top';
$_['text_content_bottom'] 			= 'Content Bottom';
$_['text_column_left']    			= 'Column Left';
$_['text_column_right']				= 'Column Right';

$_['text_live_search_options']		= 'Refresh Mode';
$_['text_live_search_style']		= 'Live Search Style';

$_['text_dropdown_display_img']		= 'Display Images';
$_['text_dropdown_img_size']		= 'Image Size';
$_['text_dropdown_img_border_color']= 'Image Border Color';

$_['text_dropdown_display_price']	= 'Display price';
$_['text_dropdown_display_rating']	= 'Display rating';


$_['text_dropdown_width']			= 'Dropdown window width';
$_['text_dropdown_text_size']		= 'Text Size';
$_['text_dropdown_text_color']		= 'Text Color';

$_['text_dropdown_bg_color']			= 'Background Color';
$_['text_dropdown_border_color']		= 'Border Color';
$_['text_dropdown_hover_bg_color']		= 'Mouse hover';

$_['text_dropdown_max_num_results']		= 'Max number of results displayed';

$_['text_dropdown_update_on']	= 'Update results after';
$_['text_entire_word']			= 'each word entered';
$_['text_single_char']			= 'each character typed';

				
				
$_['text_dropdown_no_results']				= 'Text "No results"';
$_['text_dropdown_no_results_sample_text']	= 'No results';

$_['text_dropdown_show_all']				= 'Text "Show all results"';
$_['text_dropdown_show_all_sample_text']	= 'Show all results';

$_['text_module']			= 'Modules';
$_['text_entry']			= 'Entry';
$_['text_show']				= 'Show';
$_['text_enable']			= 'Enable';
$_['text_enabled']			= 'Enabled';
$_['text_disable']			= 'Disable';
$_['text_disabled']			= 'Disabled';
$_['text_yes']				= 'Yes';
$_['text_no']				= 'No';


$_['text_help']				= 'help';
$_['text_select']			= 'Select';
$_['text_select_all']		= 'All';
$_['text_deselect_all']		= 'None';
$_['text_required']			= 'Required';
$_['text_reset_default']	= 'Reset to default <br /> values';


$_['text_warning_select_style']			= '<b>Note</b>: When you select a preset style, any custom style not previously saved will be overwritten. If you don&#39;t want to lose the current style, save this page first.';
$_['text_info_save_in_custom_style']	= 'Your settings will be saved in the Custom Style (Click on <b>Save</b> to do that)';

// buttons
$_['button_add_module']    	= 'Add search box';
$_['button_remove']    		= 'Remove box';

$_['button_save']			= 'Save';
$_['button_save_continue']	= 'Save & Stay';

// Entry

$_['entry_meta_keyword'] 	 = 'SEO Meta Tag Keywords';
$_['entry_meta_description'] = 'SEO Meta Tag Description';

$_['entry_name']             = 'Product Name';
$_['entry_description']      = 'Product Description';
$_['entry_tag']          	 = 'Product Tags';

$_['entry_model']            = 'Model';
$_['entry_sku']              = 'SKU';
$_['entry_upc']              = 'UPC';
$_['entry_ean']              = 'EAN';
$_['entry_jan']              = 'JAN';
$_['entry_isbn']             = 'ISBN';
$_['entry_mpn']              = 'MPN';

$_['entry_location']			= 'Location';
$_['entry_manufacturer_name']	= 'Manufacturer Name';

$_['entry_attribute_group_name']	= 'Attribute Group Name';
$_['entry_attribute_name']     		= 'Attribute Name';
$_['entry_attribute_description']	= 'Attribute description';

$_['entry_option_name']				= 'Option Name';
$_['entry_option_value']			= 'Option Value';

$_['entry_category_name']			= 'Category Name';

$_['entry_dimension']     			= 'Dimension (W x H)<br />(leave empty to auto resize):';
$_['entry_layout']        			= 'Layout:';
$_['entry_position']      			= 'Position:';
$_['entry_status']        			= 'Status:';
$_['entry_sort_order']    			= 'Sort Order:';

										$user_guide = file_get_contents(DIR_APPLICATION.'language/english/module/adSmartSrc_help.php');
$_['user_guide'] 					=	$user_guide;

// install / uninstall / Save

$_['error_permission']      = 'Warning: You do not have permission to modify '.$ext_name .'!';
$_['success_install']		= 'To complete the installation click <b>Edit</b>, then click <b>Save</b>.';
$_['success_uninstall']		= '<b>'.$ext_name .'</b> uninstalled successfully.';
$_['error_install']			= 'Warning: Unable to install <b>'.$ext_name .'</b>. XML missing?';
$_['error_uninstall']		= 'Warning: An error occurred while uninstalling <b>'.$ext_name .'</b>. XML missing?';

$_['text_save_success']		= 'The extension <b>'.$ext_name .'</b> has been saved successfully';
$_['text_error']			= 'Error';
$_['text_saving']			= 'Saving';
$_['text_cancel_save']		= 'Cancel save';

$_['text_wait_slow_save']	= '<b>Please wait</b>, the save operation is taking longer than expected. It may happen if PHP is running on <b>Windows systems</b> and if the <b>database is big</b>.<br /> If you cancel the saving process, some table indexes <b>might not update</b>.';
$_['save_aborted']			= 'Save operation aborted.';
$_['text_wait']				= 'Please wait...';

