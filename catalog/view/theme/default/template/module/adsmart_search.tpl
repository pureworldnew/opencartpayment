<?php
// ***************************************************
//               Advanced Smart Search   
//       
// Author : Francesco Pisanò - francesco1279@gmail.com
//              
//                   www.leverod.com		
//               © All rights reserved	  
// ***************************************************


// Catalog Module Template

?>


<div class="adsmart_search_module module_<?php echo $module; ?>">
	<div class="adsmart_wrapper">
		<input type="text" id="adsmart_search_<?php echo $module; ?>" name="adsmart_search" placeholder="<?php echo $text_search; ?>" value="<?php echo $search; ?>" />
		<span class="search_btn"></span>
	</div>
</div>

<?php  

	// How to position the search button and provide a good compatibility with any theme,
	// static and responsive:
	
	// 1)	The module gets width and height from the control panel;
	
	// 2)	adsmart_wrapper is a block element that expands its width to the whole available space;
	
	// 3)	The input field has a margin-right of n pixels, width of 100% and (the most important thing)
	//		the box model is set on "border box" so we include the margin within the 100% of width
	
	// 4)	Now simply position absolutely the search button on the right corner of .adsmart_wrapper
	//		but outside the box, in the empty space left by the input field margin-right.
	
	// The method described, has shown to be the best way for the module to adjust itself to all kind of
	// templates.

	$container_padding	= 20;
	$input_padding		= 3;
	$input_height		= 25 +$input_padding*2 ;  // see  box-sizing : border-box;
	$default_box_height	= round($input_height*2.3);
	$input_padding_left = 10;
	$search_btn_width	= $input_height;
	$input_margin_right	= round($input_height * 1.3);
	$distance_input_btn	= 20;
	
	if (is_numeric($adsmart_search_height)) {	
		$input_top		= round(( $adsmart_search_height - $input_height - $input_padding*2 - $container_padding*3/2  )/2 );
	}
	else {
		$input_top		= round(( $default_box_height -    $input_height - $input_padding*2 - $container_padding*3/2  )/2 );
	}
?>


<style>
	.adsmart_search_module.module_<?php echo $module; ?> {

		-webkit-box-sizing	: border-box; 
		-moz-box-sizing		: border-box;    
		box-sizing			: border-box;  
	
		position:relative;
						
		padding: <?php echo $container_padding ?>px;
	
		
		<?php if (is_numeric($adsmart_search_width)) { ?>
		width:  <?php echo round($adsmart_search_width) ?>px;
		<?php } else { ?>
		width:auto;	
		<?php }  ?>
		
			
		<?php if (is_numeric($adsmart_search_height)) { ?>
		min-height: <?php echo $adsmart_search_height ?>px;
		<?php } else { ?>
		min-height: <?php echo $default_box_height ?>px;
		<?php }  ?>
				
		background-color: rgba(200, 200, 200, 0.2);
		background		: rgba(200, 200, 200, 0.2);
		
		margin: 0 0 20px;
	}


	.adsmart_search_module.module_<?php echo $module; ?> .adsmart_wrapper {
		
		-webkit-box-sizing	: content-box; 
		-moz-box-sizing		: content-box;
        box-sizing			: content-box; <?php // box sizing forced to be on content-box to override any theme styles ?>
		
		display:block;
		
		position:relative;
		top		: <?php echo $input_top; ?>px;

		padding:0;
		margin:0;
		margin-right:<?php echo $input_margin_right ?>px;

		width		: auto !important; <?php // DO NOT TOUCH THIS ?>
		max-width	: auto !important;
		min-height		: <?php echo $input_height + $input_padding -2 ?>px; <?php // 2 is for the two input borders ?>
	}
	
	
	.adsmart_search_module.module_<?php echo $module; ?>  input#adsmart_search_<?php echo $module ?>,
	.adsmart_search_module.module_<?php echo $module; ?> .search_btn	{
	
		border:1px solid #ccc;
	
		-webkit-box-sizing	: border-box; 
		-moz-box-sizing		: border-box;
        box-sizing			: border-box; <?php // box sizing set on border-box doesn't mess up things with the widths ?>
	}
	
	.adsmart_search_module.module_<?php echo $module; ?>  input#adsmart_search_<?php echo $module ?> {
			
		display: block;

		position: relative;
		top		: 0;
		left	: 0;	
		
		width		: 100% !important; <?php // DO NOT TOUCH THIS ?>
		min-width	: 50px !important;
		max-width	: 100% !important;
		height		:  <?php echo $input_height ?>px;
		
		padding			: <?php echo $input_padding ?>px;
		padding-left	: <?php echo $input_padding_left ?>px;
		margin			:0;
		
		font-size	: 14px;
		background	: #fff;
	}

	
	.adsmart_search_module.module_<?php echo $module; ?> .search_btn {
				
		top		: 0;
		right	: -<?php echo $input_margin_right ?>px;
		
		width	: <?php echo  $search_btn_width ?>px;
		height	: <?php echo $input_height ?>px;
	}
	
	
	
<?php // rounded corners for input box and search button ?>
	.adsmart_search_module, 
	.adsmart_search_module input[name="adsmart_search"], 
	.adsmart_search_module .search_btn {
	
		-webkit-border-radius: 3px;
		-moz-border-radius: 3px;
		border-radius: 3px;
	}
	
	
<?php // Search icon ?>
	.adsmart_search_module .search_btn {
	
		display: block;
		position: absolute;
		margin: 0;
		border: 1px solid #bbb;
		background: #ffffff url('data:image/gif;base64,R0lGODlhDQANALMAAP////n5+fDw8Obm5tra2s/Pz7KysqampoqKinx8fHFxcWVlZVtbW1JSUkxMTP///yH5BAEAAA8ALAAAAAANAA0AAARJEMg5zCkzh+S6Y0MGcEpBHE4jUI6SGQ4yoVjGuBJKiAozFY5DRuBIsIISwcJRGzQ8C0VHBnBOEYxGSWItiiaIziLwlQAV5DIgAgA7') no-repeat scroll center center;
		cursor: pointer;
	}
	
	
	
<?php // Hover effect for the search button ?>
	.adsmart_search_module .search_btn:hover {
		-webkit-box-shadow: inset 0px 0px 9px -5px rgba(125,125,125,1);
		-moz-box-shadow: inset 0px 0px 9px -5px rgba(125,125,125,1);
		box-shadow: inset 0px 0px 9px -5px rgba(125,125,125,1);
	}
	
		
</style>