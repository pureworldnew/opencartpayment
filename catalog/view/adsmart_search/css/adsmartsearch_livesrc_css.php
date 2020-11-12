<?php
// ***************************************************
//               Advanced Smart Search   
//       
// Author : Francesco Pisanò - francesco1279@gmail.com
//              
//                   www.leverod.com		
//               © All rights reserved	  
// ***************************************************
header("Content-Type:text/css; charset:UTF-8"); 

// Live Search CSS

// Journal 3 PATCH
// Journal 3 appends its version info to the array $_GET, so the last array element contains wrong data. Remove that garbage:

	end($_GET);    				    // move the internal pointer to the end of the array
	$last_array_key = key($_GET);	// fetches the key of the element pointed to by the internal pointer

	$messy_var = explode("?v=", $_GET[$last_array_key], 2);
	$_GET[$last_array_key] = $messy_var[0];
// End Journal 3 patch
	

extract($_GET);

require_once('../../../../system/adsmart_search.php');	

/*
$dropdown_img_border_color	= 'E7E7E7';
$dropdown_img_size			= 30;
$dropdown_width				= 340;
$dropdown_text_size			= 16;
$dropdown_text_color		= '454545';
$dropdown_bg_color			= 'fafafa';
$dropdown_border_color		= 'E7E7E7';
$dropdown_hover_bg_color	= 'EDEDED';
*/

$adsmart_search_style = unserialize(ADSMART_SEARCH_STYLE);

$dropdown_img_border_color		= isset($dropdown_img_border_color)?	$dropdown_img_border_color	: $adsmart_search_style['dropdown_img_border_color']; 
$dropdown_img_size				= isset($dropdown_img_size)?			$dropdown_img_size			: $adsmart_search_style['dropdown_img_size'];
$dropdown_width					= isset($dropdown_width)?				$dropdown_width				: $adsmart_search_style['dropdown_width']; 
$dropdown_text_size				= isset($dropdown_text_size)?			$dropdown_text_size			: $adsmart_search_style['dropdown_text_size']; 
$dropdown_text_color			= isset($dropdown_text_color)?			$dropdown_text_color		: $adsmart_search_style['dropdown_text_color']; 
$dropdown_bg_color				= isset($dropdown_bg_color)?			$dropdown_bg_color			: $adsmart_search_style['dropdown_bg_color']; 
$dropdown_border_color			= isset($dropdown_border_color)?		$dropdown_border_color		: $adsmart_search_style['dropdown_border_color']; 
$dropdown_hover_bg_color		= isset($dropdown_hover_bg_color)?		$dropdown_hover_bg_color	: $adsmart_search_style['dropdown_hover_bg_color']; 

// Computed styles - to be removed in the next releases
$dropdown_lighter_separator_color	= isset($dropdown_lighter_separator_color)?	$dropdown_lighter_separator_color	: $adsmart_search_style['dropdown_lighter_separator_color']; 
$dropdown_darker_separator_color	= isset($dropdown_darker_separator_color)?	$dropdown_darker_separator_color	: $adsmart_search_style['dropdown_darker_separator_color']; 
$dropdown_hover_border_color		= isset($dropdown_hover_border_color)?		$dropdown_hover_border_color		: $adsmart_search_style['dropdown_hover_border_color']; 
$dropdown_hover_bg_color			= isset($dropdown_hover_bg_color)?			$dropdown_hover_bg_color			: $adsmart_search_style['dropdown_hover_bg_color']; 
$dropdown_msg_bg_color				= isset($dropdown_msg_bg_color)?			$dropdown_msg_bg_color				: $adsmart_search_style['dropdown_msg_bg_color']; 
$dropdown_msg_text_color			= isset($dropdown_msg_text_color)?			$dropdown_msg_text_color			: $adsmart_search_style['dropdown_msg_text_color']; 
$dropdown_msg_text_size				= isset($dropdown_msg_text_size)?			$dropdown_msg_text_size				: $adsmart_search_style['dropdown_msg_text_size']; 

?>


<?php // Template fixes - Oc 2.0+ (Default template and Journal) ?>

		<?php // z-index fix - field doesn't focus on low res devices ?>
		#search {
			z-index:102;
		}
		
		<?php // Remove extra viewport height on top - fix for Oc 2.0+, default template (do not add this on Journal theme) ?>
		<?php if (stripos($tpl, 'journal') === false) { ?>
		#search.input-group .form-control {
			float:none !important;
		}
		<?php } ?>
		
		<?php // Search button position fix when the dropdown is displayed ?>
		.adsmart_container  + .input-group-btn {
			vertical-align:top !important;	
		}

<?php // End Default template fixes ?>


<?php // We need this container for the absolute positioning of the dropdown list ?>
.adsmart_container {
	position:relative !important;
	padding:0; margin:0;
}


.adsmart_search {

	z-index:99999999 !important; 
	position:relative;

	<?php 
	// width: !!! // For the property "width", see the function set_dropdown_width(), adsmartsearch_livesrc_js.php ?>
	
	display:block; <?php // DO NOT ADD the attribute !important here ?>
	
	padding:2px !important;
	padding-bottom:0 !important;
	padding-right: 10px; 
	
	border: 1px solid #<?php echo $dropdown_border_color ?>	<?php if (!$is_admin) { ?> !important <?php } ?>;
	background-color: #<?php echo $dropdown_bg_color ?>		<?php if (!$is_admin) { ?> !important <?php } ?>;	

	
	-webkit-border-radius: 4px;
	-webkit-border-top-left-radius: 2px;
	-webkit-border-top-right-radius: 2px;
	-moz-border-radius: 4px;
	-moz-border-radius-topleft: 2px;
	-moz-border-radius-topright: 2px;
	border-radius: 4px;
	border-top-left-radius: 2px;
	border-top-right-radius: 2px;
	
	<?php	
	// "user-select: none" prevents an uwanted mouse selection of the scrollbar (it's
	// just a div box and if it is selected instead of being scrolled, it doesn't work) 
	?>
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

.adsmart_search * {
	box-sizing: border-box;
}

.adsmart_search ul{
	box-sizing: border-box;
	list-style-type: none !important;
	padding:0;margin:0;
}

.adsmart_search li {
	list-style-type: none !important;
	display: table !important;	
	width:100%;
	padding: 4px !important;
} 

.adsmart_search a.item_link {
	display: table-row !important;	
	width:100% !important;	
	margin:0;
	line-height:normal;
	text-decoration:none;
	overflow:visible !important;
}


.adsmart_search .adsmart_info_msg {
	background-image:none;
	border-top:none;
	list-style-type: none;
}


.adsmart_search div.image {

	display: table-cell <?php if (!$is_admin) { ?> !important <?php } ?>;
	
	vertical-align: middle !important;
	margin:0 !important;
	padding:0px !important;
	box-sizing: content-box !important;
}

.adsmart_search div.image, 
.adsmart_search div.image img {			<?php
										// set witdh and height also for img because there seems to be a problem with display: table-cell, + width/height, 
										// when images and their sizes are loaded, jQuery already computed the viewport height and if images are big, they
										// alter the total viewport height which, overlapping the bar 	"Show all results"								
										// and their size are loaded AFTER jquery measures the viewport height
										?>

	width: <?php echo  $dropdown_img_size ?>px <?php if (!$is_admin) { ?> !important <?php } ?>;
	height: <?php echo  $dropdown_img_size ?>px <?php if (!$is_admin) { ?> !important <?php } ?>;
	
}

.adsmart_search div.image img {
	float:left !important;
	padding:0 !important; 
	margin:0 !important;
	border:1px solid #<?php echo $dropdown_img_border_color ?> <?php if (!$is_admin) { ?> !important <?php } ?>;
	-webkit-border-radius: 2px;
	-moz-border-radius: 2px;
	border-radius: 2px;
}


.adsmart_search div.name {

	display: table-cell !important;	
	width:auto !important;
	padding-left:10px !important;	
	vertical-align: middle !important;
	
	font-style: italic !important;
	font-size: <?php echo $dropdown_text_size ?>px	<?php if (!$is_admin) { ?> !important <?php } ?>;
	font-weight:normal;
	color: #<?php echo $dropdown_text_color ?>		<?php if (!$is_admin) { ?> !important <?php } ?>;	
}

.adsmart_search div.price {

	display: table-cell <?php if (!$is_admin) { ?> !important <?php } ?>;
	width:auto !important;
	padding: 0 10px 0 20px !important;
	vertical-align: middle !important;
	
	font-style: normal !important;
	font-weight: bold !important;

	font-size: <?php echo intval(intval($dropdown_text_size*80/100)) ?>px <?php if (!$is_admin) { ?> !important <?php } ?>;
	color: #<?php echo $dropdown_text_color ?> <?php if (!$is_admin) { ?> !important <?php } ?>;

	white-space: nowrap !important;
	text-align:right !important;
}

.adsmart_search div.price s {
	color: #FF0000 !important;
}


<?php // "loading" & "no results" ?>

.adsmart_search .adsmart_info_msg {
	height: 10px !important;	
	
	position:relative; top:0px; <?php // keeps the icon above the second li element ?>
}

.adsmart_search .adsmart_info_msg div.adsmart_loading {
	
	margin: 0 auto !important;
	position:relative; top:0px;
	width: 30px !important;
	height: 30px !important;	
	background-color: transparent !important;	
	z-index:10000 !important;
}

.adsmart_search .adsmart_info_msg.no_results {
	text-align:center !important;
	padding: 5px 0 !important; 
	height:auto !important;
}

<?php // End "loading" ?>


.adsmart_search li.menu_item {
	padding:0;margin:0;
	line-height:0;
	border-top:		1px solid	#<?php echo $dropdown_lighter_separator_color ?>	<?php if (!$is_admin) { ?> !important <?php } ?>;
	border-bottom:	1px solid	#<?php echo $dropdown_darker_separator_color ?>	<?php if (!$is_admin) { ?> !important <?php } ?>;
}

.adsmart_search li.menu_item:hover { 
	border-color:	#<?php echo $dropdown_hover_border_color ?>	<?php if (!$is_admin) { ?> !important <?php } ?>;
	background-color: #<?php echo $dropdown_hover_bg_color ?>	<?php if (!$is_admin) { ?> !important <?php } ?>;
}

.adsmart_search .show_all_results {
	vertical-align:middle !important;
	text-align:right !important;
	border: none !important;
	background-color:#<?php echo $dropdown_msg_bg_color ?> <?php if (!$is_admin) { ?> !important <?php } ?>;					
}

.adsmart_search .show_all_results a {

	color:#<?php echo $dropdown_msg_text_color ?>		<?php if (!$is_admin) { ?> !important <?php } ?>;
	font-size:<?php echo $dropdown_msg_text_size ?>px	<?php if (!$is_admin) { ?> !important <?php } ?>;
	background:transparent !important;
	display:block !important;
	margin:0 !important;
	height:40px !important;
	text-decoration:none !important;
	padding-top:10px !important;
	padding-right:30px !important;
}

.adsmart_search .show_all_results a:hover {
	text-decoration:underline !important;
	background:transparent !important;
	margin:0 !important;
	border:none !important;
}
	
	

	

/* Scrollbar */	
	
<?php // viewport ?>
.adsmart_search .viewport {
	overflow:visible;
	height:100%; 
}

<?php // overview ?>
.adsmart_search .viewport ul {
    list-style:none;
	position:relative;
	padding:0;
    margin:0;
	width:100%;
}


<?php // dropdown scroll ?>
.adsmart_search.scroll  {
	position:absolute;
}

.adsmart_search.scroll .viewport {
	overflow:hidden;
	position:relative;	
}	

.adsmart_search.scroll .viewport ul {
    position:absolute;
    top:0; left:0;	
}


.adsmart_search.scroll .scrollbar {
    position:absolute;
    top:0px; right:0px;
	<?php // width: see plugin adsmart_scroll, this.scrollbarWidth = 15; ?>
	background-color: #<?php echo $dropdown_darker_separator_color ?>	<?php if (!$is_admin) { ?> !important <?php } ?>;
	z-index:10;
	-webkit-box-sizing: border-box; 
	-moz-box-sizing: border-box;   
	box-sizing: border-box;         
}

.adsmart_search.scroll .track { 
    height:100%;
    position:relative;
    padding:0px 1px;
}

.adsmart_search.scroll .thumb{ 
    height:20px;
    overflow:hidden;
    position:absolute;
    top:0; left:0px;
	box-sizing: border-box;
	-moz-box-sizing:border-box;
	-webkit-box-sizing:border-box;
	background-color: #<?php echo $dropdown_lighter_separator_color ?>	<?php if (!$is_admin) { ?> !important <?php } ?>;
	
	border-style: solid;
	border-color: #<?php echo $dropdown_darker_separator_color ?>	<?php if (!$is_admin) { ?> !important <?php } ?>;
	border-width: 15px 1px;
	
	-webkit-box-shadow: inset 0px 0px 8px 0px rgba(202, 202, 202, 0.5);
	-moz-box-shadow:    inset 0px 0px 8px 0px rgba(202, 202, 202, 0.5);
	box-shadow:         inset 0px 0px 8px 0px rgba(202, 202, 202, 0.5);
}

.adsmart_search.scroll .track,
.adsmart_search.scroll .thumb,
.adsmart_search.scroll .src_lst_up,
.adsmart_search.scroll .src_lst_down {
	width:100%;
}

.adsmart_search.scroll .src_lst_up,
.adsmart_search.scroll .src_lst_down {
	left:0px;
    height:15px;
	background:gray;
	z-index:10;
	position:absolute;
	cursor:pointer;
	line-height:11px;
	font-size:12px;
	text-align:center;
	padding:0;
	color: #<?php echo $dropdown_text_color ?> <?php if (!$is_admin) { ?> !important <?php } ?>;
	background-color: #<?php echo $dropdown_lighter_separator_color ?>	<?php if (!$is_admin) { ?> !important <?php } ?>;
	border:1px solid #<?php echo $dropdown_darker_separator_color ?>	<?php if (!$is_admin) { ?> !important <?php } ?>;
}

.adsmart_search.scroll .src_lst_up {
	top:0px;
}


.adsmart_search.scroll .src_lst_down {
	bottom:0px;
}

.adsmart_search.scroll .disable {
    display:none;
}

.noSelect {
    user-select:none;
    -o-user-select:none;
    -moz-user-select:none;
    -khtml-user-select:none;
    -webkit-user-select:none;
}	
