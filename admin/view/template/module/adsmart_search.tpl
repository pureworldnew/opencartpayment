<?php
// ***************************************************
//               Advanced Smart Search   
//       
// Author : Francesco Pisanò - francesco1279@gmail.com
//              
//                   www.leverod.com		
//               © All rights reserved	  
// ***************************************************
//
// Admin page template 
//
echo $header; 


// Init (See bootstrap 3 breakpoints, ref: http://getbootstrap.com/css/ - grid options) 
$device_xxs =  480;    // xxs : <   480 // It doesn't exist on bootstrap 
$device_xs  =  768;    // xs :  <   768 
$device_sm  =  992;    // sm :  <   992 
$device_md  = 1200;    // md :  <  1200 
$device_lg  = 1200;    // lg :  >= 1200 

?>


<script>

	// Js spinner
	$(document).ready( function(){
		spin_options = {lines: 9, length: 4, width: 2, radius: 3, rotate: 90,
		color: '#<?php $adsmart_search_dropdown_text_color // on Oc <= 1.5.6.4 was: echo $this->config->get('adsmart_search_dropdown_text_color') ?>',
		speed: 2,trail: 45, shadow: false, hwaccel: false, className: 'spinner', zIndex: 9999999, /* left: 'auto' */ top: '14px' };
		spinner = new Spinner(spin_options);
	});

// Touch device enhancement 

function isTouchSupported() {

    return (window.navigator.msMaxTouchPoints || ("ontouchstart" in document.documentElement) )? true: false;
}


$(window).load(function(){
		
	if ( isTouchSupported() ){
	
		if ( $('.ui-slider').length ) {
			$('.ui-slider .ui-slider-handle').css({
												   'width'	: '30px',
												   'height'	: '30px',
												   'top'	: '-10px'
												});
		}	
	}
});

</script>



<?php // js minify hook - ob_start - do not remove or modify this line ?> 


<script>
// Clean the Control Panel title from unwanted tags 
$(document).ready( function(){

	$('title').html('<?php echo $heading_title ?>');
});

</script>



<script>

// ███████╗██╗   ██╗███╗   ██╗ ██████╗████████╗██╗ ██████╗ ███╗   ██╗███████╗
// ██╔════╝██║   ██║████╗  ██║██╔════╝╚══██╔══╝██║██╔═══██╗████╗  ██║██╔════╝
// █████╗  ██║   ██║██╔██╗ ██║██║        ██║   ██║██║   ██║██╔██╗ ██║███████╗
// ██╔══╝  ██║   ██║██║╚██╗██║██║        ██║   ██║██║   ██║██║╚██╗██║╚════██║
// ██║     ╚██████╔╝██║ ╚████║╚██████╗   ██║   ██║╚██████╔╝██║ ╚████║███████║
// ╚═╝      ╚═════╝ ╚═╝  ╚═══╝ ╚═════╝   ╚═╝   ╚═╝ ╚═════╝ ╚═╝  ╚═══╝╚══════╝
                                                                          

// *************************************************************************************************************************	
//                                                 Search Option FUNCTIONS	
// *************************************************************************************************************************
	
	// update the field index (relevance value)
	function field_list_update(){

		num_of_fields = $('#sort_flds_by_rel li').length;
		
		var x;
		var relevance;
		
		$('#form input.relevance, #form input.sorting').remove(); // clear the old values

		
		$('#sort_flds_by_rel li').each(function(){
			
			relevance = 0;
			
			checkbox = $(this).find('input[type=checkbox][class="relevance_field"]'); 
			
			var field_position = $(this).index() + 1;
			
			if (checkbox.is(':checked')){

				// Relevance calculation:
				x =  num_of_fields - $(this).index(); // invert the index, so the fields on top have a greater value
				// The relevance of a field must have a value greater than the sum of the previous relevances. This 
				// ensures that the sum of two relevances is never higher than the relevance fields placed on higher 
				// positions in the list.

				relevance = Math.pow(2, x);

				$('#form #tab-search-options').append('<input class="relevance" type="hidden" name="adsmart_search_relevance[' + checkbox.attr('name') + ']" value="' + relevance + '" />'); 
			}

			$(this).children('span.number').html(field_position);
		});
		
		
		// Save field list with the new sorting
		$('#sort_flds_by_rel li').each(function(){

			checkbox = $(this).find('input[type=checkbox][class="relevance_field"]'); 
			
			var field_position = $(this).index() + 1;

			$('#form #tab-search-options').append('<input class="sorting" type="hidden" name="adsmart_search_fields[' + field_position + '] " value="' + checkbox.attr('name') + '" />'); 
		});
		
	}


	// Update search cache
	function update_search_cache() {
		$.ajax({
			url: '<?php echo $website_url ?>index.php?route=module/adsmart_search/update_search_cache&token=<?php echo $token; ?>',
			dataType: 'html',
			beforeSend: function() {
				$('input[name="adsmart_search_update_search_cache"]').prop('disabled', true);
				$('table.cache_manager .message').empty().append('<div class="wait"><span class="wait-icon"></span><?php echo $text_wait; ?></div>');
				$('table.cache_manager .message').slideDown('fast');
			},			
			success: function(html) {
				
				$('input[name="adsmart_search_update_search_cache"]').prop('disabled', false);
				$('table.cache_manager .message').empty();
				if (html == true) {
					$('table.cache_manager .message').append('<div class="success"><span class="success-icon"></span><?php echo $text_search_cache_updated; ?></div>');	
				}
				else {
					$('table.cache_manager .message').append('<div class="warning"><?php echo $text_error; ?></div>');
				}
				// fade out the message
				$('table.cache_manager .message').delay(10000).fadeOut(400);
			},
			
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			},
			
			complete: function() {
			}	
		});
	}
		
		
	// Clear search cache
	function clear_search_cache() {
		$.ajax({
			url: 'index.php?route=module/adsmart_search/clear_cache&label=search_string&token=<?php echo $token; ?>',
			dataType: 'html',
			beforeSend: function() {
				$('input[name="adsmart_search_clear_search_cache"]').prop('disabled', true);
				$('table.cache_manager .message').empty().append('<div class="wait"><span class="wait-icon"></span><?php echo $text_wait; ?></div>');
				$('table.cache_manager .message').slideDown('fast');
			},				
			success: function(html) {
			
				$('input[name="adsmart_search_clear_search_cache"]').prop('disabled', false);
				$('table.cache_manager .message').empty();
				if (html == true) {
					$('table.cache_manager .message').append('<div class="success"><span class="success-icon"></span><?php echo $text_search_cache_cleared; ?></div>');	
				}
				else {
					$('table.cache_manager .message').append('<div class="warning"><?php echo $text_error; ?></div>');
				}
				// fade out the message
				$('table.cache_manager .message').delay(10000).fadeOut(400);

			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			},
			
			complete: function() {
			}	
		});	
	}
		
		
	// Checkbox index db
	function show_hide_rebuild_btn(elem) {
		if ($(elem).is(':checked')){
			$('input[name="adsmart_search_rebuild_indexes"]').prop("disabled",false);
			$('.rebuild_indexes').slideDown();
		}
		else {
			$('input[name="adsmart_search_rebuild_indexes"]').prop("disabled",true);	
			$('.rebuild_indexes').slideUp();
		}
	}
	
	

	$(document).ready(function() {
		example_word = $('.example_word').text();
		example_word_length = example_word.length;
	});
		
	function highlight_partial_word() {

		var value			= parseInt($('input[name="adsmart_search_partial_word_length"]').val());
		
		var algorithm = $( 'input[name="adsmart_search_algorithm"]:checked' ).val();
		
		// for the FAST algorithm start highlighting from the first character
		var highlight_from	= 0;
		if ( algorithm == 'default' ){
		
			// when the search algorithm is DEFAULT, start highlighting from the middle of the word;
			highlight_from	= Math.floor((example_word_length - value)/2);
		}
		
		var start			= example_word.substring(0, highlight_from);
		var highlight		= example_word.substring(highlight_from, highlight_from + value);
		var end				= example_word.substring(highlight_from + value, example_word_length);

		var dots;

		if (highlight.length >= example_word_length ){
			dots = '...';
		}
		else dots = '';
		
		$('.example_word').html(dots + start + '<b>'+highlight+'</b>' + end + dots);		

	}
		
		





// *************************************************************************************************************************	
//                                          Live search Options & Style FUNCTIONS 	
// *************************************************************************************************************************



// **************  Display/hide images   **************

	function display_hide_image(checkbox){
		if( checkbox.is(':checked') ){
		
			$('.adsmart_search div.image').show();
			$('#row_img_size_slider').show();
		}
		else {
			$('.adsmart_search div.image').hide();
			$('#row_img_size_slider').hide();
		}
	}
	
		
	
// **************  Display/hide Prices   **************
	
	function display_hide_prices(checkbox){
		if( checkbox.is(':checked') ){

			$('.adsmart_search div.price').show();
			$('.adsmart_search div.name').css('width','99%');		
		}
		else {

			$('.adsmart_search div.price').hide();	
			$('.adsmart_search div.name').css('width','auto');
		}
	}
	

	
// **************  Set dropdown width   ***************

	function set_width(width) {
		$('input[name="adsmart_search_dropdown_width"]').val(width);
		
		if ( $(window).width() > width) {
			$('.adsmart_search').width(width);
		}
		else {
			$('.adsmart_search').width($(window).width() -30);
		}		
	}
	
	

// **************  Refresh viewport   *****************	
	
	function update_viewport() {
	
		// Update the scrollbar, according to the new content size (images and texts can change their height)
		
		var adsmartSearchDIV = $('.adsmart_search');
		
		var scrollbar = adsmartSearchDIV.data('plugin_adsmart_scroll');

		if (typeof scrollbar !== 'undefined' ){
			scrollbar.update('relative');
		}

		var viewportULHeight	= adsmartSearchDIV.find('ul').outerHeight(true);
		adsmartSearchDIV.find('.viewport').height(viewportULHeight);	
	}

	

// ******************  Sliders   **********************	
	
	// Init slider cursor positions according to their values
	function set_cursor_positions(){
	
		$('#dropdown_width_slider').slider("option", "value", $('input[name="adsmart_search_dropdown_width"]').val());
		$('#text_size_slider').slider("option", "value", $('input[name="adsmart_search_dropdown_text_size"]').val());
		$('#img_size_slider').slider("option", "value", $('input[name="adsmart_search_dropdown_img_size"]').val());
		$('#max_num_results_slider').slider("option", "value", $('input[name="adsmart_search_dropdown_max_num_results"]').val());
	}
	
// End Slider Functions	


// ***************   Text Options   *******************	

	function set_text_size(text_size){
	
		text_size = parseInt(text_size);
		$('input[name="adsmart_search_dropdown_text_size"]').val(text_size);
		$('.adsmart_search div.name').css('font-size', text_size + 'px' ); 
		$('.adsmart_search div.price').css('font-size', parseInt(text_size * 80/100) + 'px' ); 
		
		var dropdown_msg_text_size = (text_size > 18)? parseInt(text_size * 80/100) : text_size;
		$('input[name="adsmart_search_dropdown_msg_text_size"]').val(dropdown_msg_text_size);
		$('.adsmart_search .show_all_results a, .no_results').css('font-size', dropdown_msg_text_size  + 'px' ); 
	}

	


// ***************   Color Options   ******************	

	function is_valid_hex_color(hex){
		
		// strip the leading #
		hex = hex.replace(/^\s*#|\s*$/g, '');
		
		return /^[0-9A-F]{6}$/i.test(hex);
	}
			
	// Contrast the text color according to the background
	function contrastingColor(color){
		return (luma(color) >= 165) ? '000000' : 'ffffff';
	}

	
	function luma(color) { // color can be a hex string or an array of RGB values 0-255
	
		var rgb = (typeof color === 'string') ? hexToRGB(color) : color;
		return (0.2126 * rgb[0]) + (0.7152 * rgb[1]) + (0.0722 * rgb[2]); // SMPTE C, Rec. 709 weightings
	}

	
	// Returns the array rgb[r,g,b]
	function hexToRGB(hex) {
	
		 // strip the leading # if it's there
		hex = hex.replace(/^\s*#|\s*$/g, '');
	
		// PATCH - fix the hex code if is in a wrong format
		if (hex.length != 6) hex = 'ffffff';
	
	
		if (hex.length === 3)
			hex = hex.charAt(0) + hex.charAt(0) + hex.charAt(1) + hex.charAt(1) + hex.charAt(2) + hex.charAt(2);
		else if (hex.length !== 6)
			throw('Invalid hex color: ' + hex);
		var rgb = [];
		for (var i = 0; i <= 2; i++)
			rgb[i] = parseInt(hex.substr(i * 2, 2), 16);
		return rgb;
	}
	
	
	function rgbToHex(r, g, b) {
		return  ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
	}
	
	
	// Function Brightness
	// Input: - hex string with or without # 
	//        - a percentage from (-100% to 100%), with or without the %
	// Output: a hex value
	function brightness(hex, percent){
		
		 // strip the leading # if it's there
		hex = hex.replace(/^\s*#|\s*$/g, '');
		
		 // strip the trailing % if it's there and convert to integer
		percent = parseInt(percent.replace(/%$/g, ''));
		
		var rgb = hexToRGB(hex);
	
		if (percent < 0 ){
			// returns a darker color (-100% , 0%) 
			 return ((0|(1<<8) + rgb[0] * (100 + percent) / 100).toString(16)).substr(1) +
					((0|(1<<8) + rgb[1] * (100 + percent) / 100).toString(16)).substr(1) +
					((0|(1<<8) + rgb[2] * (100 + percent) / 100).toString(16)).substr(1);
		}
		else {
			// returns a brighter color (0% , + 100%) 
			 return ((0|(1<<8) + rgb[0] + (256 - rgb[0]) * percent / 100).toString(16)).substr(1) +
					((0|(1<<8) + rgb[1] + (256 - rgb[1]) * percent / 100).toString(16)).substr(1) +
					((0|(1<<8) + rgb[2] + (256 - rgb[2]) * percent / 100).toString(16)).substr(1);
		}

	}





// ****************   Color Picker   ******************		

	// Color input field background and text
	function style_color_picker_input(input, hex){
		$(input).css('background-color', '#' + hex);
		$(input).css('color', '#' + contrastingColor(hex));
		
		// set the new value	
		$(input).val(hex);
		$(input).attr('value',hex); // for Firebug, to display the current hex color on the attribute "value"	
	}

	function show_warning(elem){
		elem.next('span').remove();
		elem.after('<span class="warning-icon adsmart-warning"></span>');
	}
	
	function hide_warning(elem){
		elem.next('.adsmart-warning').remove();
	}
	




// *********   Live Search & hidden fields   **********		

	// set the style to the test dropdown window and assign the values to the hidden input fields
	function set_dropdown_style_and_hidden_input_fields(){
	
		// Update the spinner color:
		spin_options.color  =  '#'+$('input[name=adsmart_search_dropdown_text_color]').val();
		spinner = new Spinner(spin_options);
	

		// Style the search result test window
		$('.adsmart_search').css('background-color', '#'+$('input[name=adsmart_search_dropdown_bg_color]').val() );
		$('.adsmart_search').css('border-color',     '#'+$('input[name=adsmart_search_dropdown_border_color]').val() );

		set_width($('input[name=adsmart_search_dropdown_width]').val());
		
		// see file adsmartsearch_livesrc_js.php
		set_dropdown_position($('input[name="search"]'));	
		
		set_text_size($('input[name=adsmart_search_dropdown_text_size]').val());

		
		// image settings
		if( $('input[name="adsmart_search_dropdown_display_img"]').is(':checked') ){
			$('.adsmart_search div.image img').css( 'width',$('input[name=adsmart_search_dropdown_img_size]').val()+'px' ).css('height', 'auto');
			$('.adsmart_search .image img').css('border-color', '#'+$('input[name=adsmart_search_dropdown_img_border_color]').val() );
		}
		
		
		$('.adsmart_search .name, .adsmart_search .price').css('color', '#'+$('input[name=adsmart_search_dropdown_text_color]').val() );
		

		// for a better color contrast we calculate the bg luma and set the other colors from that value:
		var sign;
		var opposite_sign;
		if (luma($('input[name=adsmart_search_dropdown_bg_color]').val()) >= 165){
			sign = '+'; opposite_sign = '-';	
		} 
		else {
			sign = '-'; opposite_sign = '+';
		}		

		// the class "lastfocus" is set in adsmart_livesrc_js.php in the method focus()
		$('.adsmart_search li.lastfocus').css('background-color', '#'+$('input[name=adsmart_search_dropdown_hover_bg_color]').val() );
		// hover border color
		var hover_border_color = brightness($('input[name="adsmart_search_dropdown_hover_bg_color"]').val(), sign + '40%' );
		$('.adsmart_search .item_link[style]').css('border-color', '#' + hover_border_color );

		// separator
		var lighter_separator_color = brightness($('input[name="adsmart_search_dropdown_bg_color"]').val(), '+20%' );
		var darker_separator_color = brightness($('input[name="adsmart_search_dropdown_bg_color"]').val(),  '-20%' );
		$('.adsmart_search li.menu_item').css('border-top', '1px solid #' + lighter_separator_color );
		$('.adsmart_search li.menu_item').css('border-bottom', '1px solid #' + darker_separator_color );
		
		
		// Show all /No results texts
		var dropdown_msg_bg_color	= brightness($('input[name=adsmart_search_dropdown_bg_color]').val(), opposite_sign + '10%');
		var dropdown_msg_text_color	= brightness($('input[name=adsmart_search_dropdown_text_color]').val(), opposite_sign + '10%');

		
		$('.adsmart_search .show_all_results a, .no_results').css('color',  '#'+dropdown_msg_text_color );
		$('.adsmart_search .show_all_results').css('background-color',  '#'+dropdown_msg_bg_color );
		$('.no_results').css('background-color', '#'+$('input[name=adsmart_search_dropdown_bg_color]').val() );
		

		// Scrollbar
		$('.adsmart_search.scroll .scrollbar').css('background-color', '#' + darker_separator_color );
		
		$('.adsmart_search.scroll .thumb').css('background-color', '#' + lighter_separator_color );
		$('.adsmart_search.scroll .thumb').css('border-color', '#' + darker_separator_color );

		$('.adsmart_search.scroll .src_lst_up, .adsmart_search .src_lst_down ').css('color', '#' + dropdown_msg_text_color );
		$('.adsmart_search.scroll .src_lst_up, .adsmart_search .src_lst_down ').css('background-color', '#' + dropdown_msg_bg_color );
		$('.adsmart_search.scroll .src_lst_up, .adsmart_search .src_lst_down ').css('border-color', '#' + darker_separator_color );
		
		// Update the viewport, the content height can change when text and images resize
		update_viewport();
		

		// Save the colors in the hidden inputs
		$('input[name=adsmart_search_dropdown_lighter_separator_color]').val(lighter_separator_color);
		$('input[name=adsmart_search_dropdown_darker_separator_color]').val(darker_separator_color);
		$('input[name=adsmart_search_dropdown_hover_border_color]').val(hover_border_color);
		
		$('input[name=adsmart_search_dropdown_msg_text_color]').val(dropdown_msg_text_color);
		$('input[name=adsmart_search_dropdown_msg_bg_color]').val(dropdown_msg_bg_color);
		
		
		
		// display or hide the image
		display_hide_image($('input[name="adsmart_search_dropdown_display_img"]'));
		
		// display or hide the price
		display_hide_prices($('input[name="adsmart_search_dropdown_display_price"]'));
				
	}




	
// ***************   Preset Styles   ******************		
		
	function load_preset_style(style){
	
		var setting = new Array(); // { img_border_color, img_size, width, text_size, text_color, bg_color, border_color, hover_bg_color }

		
		switch(style) {
		
			case 'opencart_classic':
				setting = { 
					'img_border_color'	: 'E7E7E7', 					
					'img_size'			: '30', 
					'width'				: '340', 
					'text_size'			: '16', 
					'text_color'		: '454545', 
					'bg_color'			: 'fafafa', 
					'border_color'		: 'E7E7E7', 
					'hover_bg_color' 	: 'ededed'
				};
			break;
			
			case 'ocean':
				setting = { 
					'img_border_color'	: '6dc1db', 
					'img_size'			: '30', 
					'width'				: '340', 
					'text_size'			: '16', 
					'text_color'		: 'ffffff', 
					'bg_color'			: '08a2d1', 
					'border_color'		: '6dc1db', 
					'hover_bg_color' 	: '2fdaed'
				};
			break;
			
			case 'coca_cola':
				setting = { 
					'img_border_color'	: 'e6bba1', 
					'img_size'			: '30', 
					'width'				: '340', 
					'text_size'			: '16', 
					'text_color'		: 'ffebe0', 
					'bg_color'			: '301101', 
					'border_color'		: '914d20', 
					'hover_bg_color' 	: 'd4a182'
				};
			break;
			
			case 'teal':
				setting = { 
					'img_border_color'	: '37aba7', 
					'img_size'			: '30', 
					'width'				: '340', 
					'text_size'			: '16', 
					'text_color'		: 'ebfffe', 
					'bg_color'			: '003545', 
					'border_color'		: '37aba7', 
					'hover_bg_color' 	: '24aba9'
				};
			break;
			
			case 'blue_sky':
				setting = { 
					'img_border_color'	: 'c4f3ff', 
					'img_size'			: '30', 
					'width'				: '340', 
					'text_size'			: '16', 
					'text_color'		: '00486e', 
					'bg_color'			: '42d0ff', 
					'border_color'		: 'b8f0ff', 
					'hover_bg_color' 	: 'e5f5ff'
				};
			break;
			
			case 'dark_gray':
				setting = { 
					'img_border_color'	: 'cccccc', 
					'img_size'			: '30', 
					'width'				: '340', 
					'text_size'			: '16', 
					'text_color'		: 'ebebeb', 
					'bg_color'			: '242424', 
					'border_color'		: '787878', 
					'hover_bg_color' 	: '787878'
				};
			break;
			
			case 'sand':
				setting = { 
					'img_border_color'	: 'dbc686', 
					'img_size'			: '30', 
					'width'				: '340', 
					'text_size'			: '16', 
					'text_color'		: '544c34', 
					'bg_color'			: 'f0d173', 
					'border_color'		: 'dbc686', 
					'hover_bg_color' 	: 'ffe8a1'
				};
			break;
			
			case 'mint':
				setting = { 
					'img_border_color'	: '36822a', 
					'img_size'			: '30', 
					'width'				: '340', 
					'text_size'			: '16', 
					'text_color'		: 'ebffee', 
					'bg_color'			: '094000', 
					'border_color'		: '36822a', 
					'hover_bg_color' 	: '099414'
				};
			break;
			
			case 'spicy':
				setting = { 
					'img_border_color'	: 'f56767', 
					'img_size'			: '30', 
					'width'				: '340', 
					'text_size'			: '16', 
					'text_color'		: 'ffebeb', 
					'bg_color'			: '850000', 
					'border_color'		: 'd44444', 
					'hover_bg_color' 	: 'cf1f00'
				};
			break;
			
			case 'pink':
				setting = { 
					'img_border_color'	: 'ffd4ec', 
					'img_size'			: '30', 
					'width'				: '340', 
					'text_size'			: '16', 
					'text_color'		: '5c0d38', 
					'bg_color'			: 'ffa3ce', 
					'border_color'		: 'ffd4ec', 
					'hover_bg_color' 	: 'ff6bbc'
				};
			break;
			
			case 'sunlight':
				setting = { 
					'img_border_color'	: 'ffee00', 
					'img_size'			: '30', 
					'width'				: '340', 
					'text_size'			: '16', 
					'text_color'		: '454545', 
					'bg_color'			: 'ffe45e', 
					'border_color'		: 'ffee00', 
					'hover_bg_color' 	: 'ffe600'
				};
			break;
			
			case 'orange_juice':
				setting = { 
					'img_border_color'	: 'ffdb3d', 
					'img_size'			: '30', 
					'width'				: '340', 
					'text_size'			: '16', 
					'text_color'		: '6e3200', 
					'bg_color'			: 'ff7300', 
					'border_color'		: 'ffdb3d', 
					'hover_bg_color' 	: 'ffc919'
				};
			break;
			
			case '---':
				setting = { 
					'img_border_color'	: '', 
					'img_size'			: '30', 
					'width'				: '340', 
					'text_size'			: '16', 
					'text_color'		: '', 
					'bg_color'			: '', 
					'border_color'		: '', 
					'hover_bg_color' 	: ''
				};
			break;
			
			
			case 'custom_style':
				setting = { 
					'img_border_color'	: '<?php echo $adsmart_search_dropdown_img_border_color ?>', 
					'img_size'			: '<?php echo $adsmart_search_dropdown_img_size ?>', 
					'width'				: '<?php echo $adsmart_search_dropdown_width ?>', 
					'text_size'			: '<?php echo $adsmart_search_dropdown_text_size ?>', 
					'text_color'		: '<?php echo $adsmart_search_dropdown_text_color ?>', 
					'bg_color'			: '<?php echo $adsmart_search_dropdown_bg_color ?>', 
					'border_color'		: '<?php echo $adsmart_search_dropdown_border_color ?>', 
					'hover_bg_color' 	: '<?php echo $adsmart_search_dropdown_hover_bg_color ?>'
				};
			break;

		}
		
		
		$('input[name=adsmart_search_dropdown_img_border_color]').val(setting['img_border_color']);
		$('input[name="adsmart_search_dropdown_img_size"]').val(setting['img_size']);
		$('input[name=adsmart_search_dropdown_width]').val(setting['width']);
		$('input[name=adsmart_search_dropdown_text_size]').val(setting['text_size']);
		$('input[name=adsmart_search_dropdown_text_color]').val(setting['text_color']);	
		$('input[name=adsmart_search_dropdown_bg_color]').val(setting['bg_color']);
		$('input[name=adsmart_search_dropdown_border_color]').val(setting['border_color']);
		$('input[name=adsmart_search_dropdown_hover_bg_color]').val(setting['hover_bg_color']);
		

		// set the style to the test dropdown window and assign the values to the hidden input fields
		set_dropdown_style_and_hidden_input_fields();
		
		// set the slider cursor positions according to their values
		set_cursor_positions();
		

		$('.colpick').each(function() {	
			$(this).minicolors('value', $(this).val() );
		});
	}
	

	// Select the Custom Style option and displays an info message
	function set_custom_style_option(){
		var select = $('select[name="adsmart_search_dropdown_preset_style"]');
		select.val('custom_style');
		select.next('span').remove();
		select.after('<span class="info-icon adsmart-info" style="display:inline-block; width:250px; height:40px; background-color:transparent; background-position:0% 20%;" ><span><?php echo $text_info_save_in_custom_style ?></span>');
		
		pulsate_search_input();
	}
	

	





// *************************************************************************************************************************	
//                                           Header, Menu & Tabs FUNCTIONS 	
// *************************************************************************************************************************


  	// Tabs
	function open_tab(tab, e){
	
		current_tab = tab.attr('href').replace(/^#/,""); // is global
	
		$('.menutabs a').removeClass('selected');
		tab.addClass('selected');
		
		$('.tab-content').removeClass('active');
		$('.tab-content'+ tab.attr('href') ).addClass('active'); 
	}
	

// ********************   Menu   **********************			
		
	function toggle_menu() {
		if ($(window).width() >= <?php echo $device_xs ?>) {	
			$('.menutabs').removeClass('compact');
			$('.menutabs').show(); // menu always visible for screens bigger than xs
		}
		else {
			$('.menutabs').addClass('compact');
		}
	};

	
	<?php
	// ******************* 
	// Sticky header
	// *******************
	// Arguments:
	// edge_top :	set this value if you want start the effect n° pixels before or after 
	// 				$(window).scrollTop() to be equal to the element's offset top 
	
	// top, left :	position where to place the fixed element
	// min_width and min_height are the window min w/h allowed to apply the effect
	// (don't stick the header on top when the window height is too small)
	?>

	function sticky(elem, edge_top, top, left, min_width, min_height) {
		
		var sticky = $(elem);
		var sticky_offset_top	= sticky.offset().top - edge_top;
		
		$(window).scroll(function(){

				if ( $(window).scrollTop() > sticky_offset_top && $(window).width() >= min_width && $(window).height() >= min_height  ) {
				
					sticky.addClass('fixed');		
					sticky.css({position: 'fixed', top: top+'px', left: left+'px'});
					
					sticky.data('header_is_sticky', true);	
		
				} else {
					sticky.removeClass('fixed');
					sticky.removeAttr('style');
					
					sticky.data('header_is_sticky', false);
				}
			
			sticky.trigger('sticky_change'); // attach the custom event sticky_change 	
		});
	}	

	

// *************************************************************************************************************************	
//                                              User Guide FUNCTIONS 	
// *************************************************************************************************************************		
	

	// Sticky Table of Contents
	function sticky_toc(){

		if ($('a[href="#tab-user-guide"]').hasClass('selected') ) {
		
			var index_position;
			var index_offset_top;
			var index_height;
			
			var header_height =  $('#header-wrapper').height();
	
			// If the header is fixed
			if ( $(window).scrollTop() >  header_height && $('#sticky-header').data('header_is_sticky') == true ) {
				index_position = 'fixed';
				index_offset_top = $('#sticky-header.fixed').height()+20;
				index_height = $(window).height() - $('#sticky-header.fixed').height();
				
				$('#user-guide .index').css('overflow-y','scroll');
				$('#user-guide .index').css('overflow-x','hidden');
				
			}
			else {
			
				if ( $(window).scrollTop() >  header_height && $('#sticky-header').data('header_is_sticky') != true ) { // header_is_sticky could be equal to false or undefined  
					index_position = 'fixed';
					index_offset_top = 0;
					index_height = $(window).height();	
					
					$('#user-guide .index').css('overflow-y','scroll');
					$('#user-guide .index').css('overflow-x','hidden');
				}
				else {
					index_position = 'relative';
					index_offset_top = 0;	
				}
			
				$('#user-guide .index').height('auto');
				$('#user-guide .index').css('overflow-y','visible');
			}
			
			$('#user-guide .index').css('position',index_position).css('top', index_offset_top +'px').css('height', index_height +'px');
		}
	}
	

	
	
	
	
// *************************************************************************************************************************	
//                                                 Misc FUNCTIONS 	
// *************************************************************************************************************************
	

	

	// input_snapshot() stores the current input values (Only for the "General Search Options" input fields).
	// Then they will be compared  with the values posted when saving the page. The function is used to know if 
	// the search cache is out of sync with the current settings.
	
	function input_snapshot(){
		
		$('#tab-search-options input').removeData(); // clear all previously stored data
		
		$('#tab-search-options input:text, #tab-search-options input:hidden, #tab-search-options input[type="checkbox"], #tab-search-options select, #tab-search-options input[type="radio"]:checked').each(function() { 
			$(this).data('initialValue', $(this).val());
		}); 
	} 


	
	
// ***************   Save settings   ******************			
	
	// This function saves the page. It also checks for input data changes 
	// (Only for inputs within the tab "General Search Options") before 
	// posting the form and prompts three choices to the user if the cache is 
	// out of sync:
	// - Save and update the cache
	// - Save and clear the cache
	// - Save only

	// Parameters:
	// 1)  "mode" -  values: 
	// 						(empty)  the page is saved and the admin is 
	// 								 redirected to the module pages	
	//
	//						'ajax'  Save the settings without leaving the page

	function save(mode){
	 
		fields_changed = false; 

		// Check if the input fields have changed:
		
		// IMPORTANT: 
		// to check if input field have been changed, we must open the tab #tab-search-options first. Hidden tabs have the property 
		// display set on "none" and hidden input elements can't be read to check if their values has changed or not. Then, first 
		// we open the tab #tab-search-options, we read the input values and bring the user back to the tab that was opened before 
		// saving the module. (The call to the method .click() will be too fast to be seen on the screen)
		
		// Save the current opened tab
		var current_tab = $('.menutabs a.selected');
		
		// Open the tab #tab-search-options
		$('a[href="#tab-search-options"]').click();
		
		// Check value changes
		$('#tab-search-options input:text, #tab-search-options input:hidden, #tab-search-options input[type="checkbox"], #tab-search-options select, #tab-search-options input[type="radio"]:checked').not('#tab-search-options input[name="adsmart_search_index_db"]').each(function() { 
			if($(this).data('initialValue') != $(this).val()){ 
				fields_changed = true; 
				return false; // breaks the $.each() (equivalent to the command "break" for loops like for, while ecc.)
			} 
		}); 
		
		// back to the current tab
		current_tab.click();
		
		
		if ( $('input[name="adsmart_search_enable_search_cache"]').prop('checked') && fields_changed == true ){ 		
			
			$( "#dialog-confirm" ).dialog({
				resizable: false,
				width: Math.min( 500, ($(window).width() - 20) ),
				height: 300,
				minHeight: 300,
				modal: true,
				buttons: {
					"Save + Update cache": function() {
						$( this ).dialog( "close" );
						fields_changed = false;
						update_search_cache();
						
						if (mode == 'ajax'){
							ajax_save();
						}
						else {
							// $('#form').submit();
							ajax_save('', '<?php echo $redirect; ?>');
						}
						
					},	
					"Save + Clear cache": function() {
						$( this ).dialog( "close" );
						fields_changed = false;
						clear_search_cache();
						
						if (mode == 'ajax'){
							ajax_save();
						}
						else {
						//	$('#form').submit();
							ajax_save('', '<?php echo $redirect; ?>');
						}
					},
					"Save only": function() {
						fields_changed = false;
						$( this ).dialog( "close" );
						
						if (mode == 'ajax'){
							ajax_save();
						}
						else {
							// $('#form').submit();
							ajax_save('', '<?php echo $redirect; ?>');
						}
					}
				}
			});			
		} 
		else {
			if (mode == 'ajax'){
				ajax_save();
			}
			else {
				// $('#form').submit();
				ajax_save('', '<?php echo $redirect; ?>');
			}
		}
		
		// reset the initial input values:
		input_snapshot(); 
	}
 

	// save the current settings without reloading the page
	// Parameters: 
	// 1) "mode"  -  values:
	// 							''  		Saves the page
	// 								 	
	//							'silent'	doesn't display any confirmation/error message 
	//										to the user after saving the page
	// 2) "redirect" - values: 
	//							''			Doesn't leave the current page
	//							'url'		Redirect to the specified url						
	
	function ajax_save(mode, redirect){
	
		xhr_save = $.ajax( {
			type: 'post',
			url: $('#form').attr('action'),
			data: $('#form').serialize()+'&save=true&is_ajax_request=true',
			dataType: 'json',
			
			beforeSend: function() {
				
				if (mode != 'silent' && $('body .saving').length == 0 ){
					$('body').prepend('<div class="saving"><div class="shadow"></div><span class="wait"><em class="wait-icon"></em>&nbsp;&nbsp;<?php echo  $text_saving; ?></span></div>');
				}
			},	
			
			complete: function(json) {	
			},
			
			success: function(json) {
				
				if (json['saved'] && mode != 'silent') {
					$('.saving').remove();
					$('.message-bottom').html('<div class="success"><span class="success-icon"></span><?php echo $text_save_success; ?></div>');
					// fade out the message
					$('.success').delay(10000).fadeOut(400);
					
					if (typeof redirect != 'undefined'){
						window.location.replace(redirect);
					}				
				}
				
				if (json['max_time_exceeded']){ // see admin/controller/adsmart_search.php -> public function shutdown() 
					$('.saving').append('<div class="warning-icon notice"><?php echo $text_wait_slow_save; ?> &nbsp;&nbsp;<a class="cancel-save button red"><?php echo $text_cancel_save; ?></a>'+'</div>');
				
					if (typeof redirect != 'undefined'){
						ajax_save('', redirect);
					}
					else {
						ajax_save();
					}
				}
			},
			
			// when dataType: 'json', the function 'error' is also fired when the HTTP status code is 200. It happens
			// when the response is not in a valid json format. Otherwise, with a 200 status code, the function 'error' 
			// will never be fired, the response will be handled by the function 'success'.
			
			// There are three choices:
			// 1) Move the json error messages into the function 'success' [V]
			// 2) Use json but make sure that in case of errors the response is not in json format.
			// 3) don't use json at all.
						
			error: function(jqXHR, ajaxOptions, thrownError) {

				if ( typeof jqXHR.responseText != 'undefined'){ // jqXHR.responseText is undefined when the user clicks on the button ".cancel-save" (status code = Aborted , but no responseText from the server)
					$('.saving').remove();
					// $('.saving').html(<div class="warning-icon notice">'+ thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText+'</div>');
					$('.message-bottom').html('<div class="warning">'+jqXHR.responseText+'</div>');
					$('.warning').delay(20000).fadeOut(400);		
				}
			}	
		});

		$('.cancel-save').click(function(){
			// For the abort method, see:
			// http://msdn.microsoft.com/en-us/library/ms535920%28VS.85%29.aspx
			xhr_save.abort();
			$('.saving').remove();
			$('.message-bottom').html('<div class="warning-icon notice"><?php echo $save_aborted; ?></div>');
			$('.notice').delay(10000).fadeOut(400);	
		});
		
	}
	



	
	

// **************   Rebuild Indexes   *****************		
	
	$(document).ready(function() {	
		max_time_exceeded = false;
	});
	
	function rebuild_db_indexes(){

		$.ajax( {
			type: 'post',
			url: 'index.php?route=module/adsmart_search/rebuild_db_indexes&token=<?php echo $token; ?>',
			data: $('#form input[name^="adsmart_search_relevance["], #form input[name="adsmart_search_index_db"]').serialize(),

			dataType: 'json',
			
			beforeSend: function() {
			
				if ( !max_time_exceeded ){
					$('table.db_optimization .message').html('<div class="wait"><span class="wait-icon"></span>&nbsp;&nbsp;<?php echo $text_wait; ?></div>');
				}
				else {
					$('table.db_optimization .message').html('<div class="notice" style="white-space:normal;position:relative" ><span class="warning-icon" style="position:absolute;left:8px;top:8px"></span> <span class="wait-icon" style="position:absolute;left:4px;bottom:7px"></span><?php echo $text_wait_slow_indexing; ?></div>');
				}
				
				$('input[name="adsmart_search_rebuild_indexes"]').prop('disabled', true);
				
				$('table.db_optimization .message').slideDown('fast');	
			},	
		
			success: function(json) {

				if (json['built']) {
				
					$('input[name="adsmart_search_rebuild_indexes"]').prop('disabled', false);
					$('table.db_optimization .message').empty();
				
					$('table.db_optimization .message').append('<div class="success"><span class="success-icon"></span><?php echo $text_indexes_rebuilt; ?></div>');	
					max_time_exceeded = false;
					// fade out the message
					$('table.db_optimization .message').delay(10000).fadeOut(400);
				}
				
				if (json['max_time_exceeded']){ // see admin/controller/adsmart_search.php -> public function shutdown() 
					max_time_exceeded = true;
					rebuild_db_indexes();
				}
			},

			error: function(jqXHR, ajaxOptions, thrownError) {
			
				$('input[name="adsmart_search_rebuild_indexes"]').prop('disabled', false);
				$('table.db_optimization .message').empty();
		
				if ( typeof jqXHR.responseText != 'undefined'){ // jqXHR.responseText is undefined when the user aborts the ajax request (for example, see the button ".cancel-save", status code = Aborted , but no responseText from the server)

					$('.message-bottom').html('<div class="warning">'+jqXHR.responseText+'</div>');
					$('.warning').delay(20000).fadeOut(400);		
				}
			}	
		});
	}


	
	
	
// ******************   Other   ***********************		
 
	function pulsate_search_input(){
		
		var search_field = $('#search input[name="search"]');
		
		if ( $('.adsmart_search').length == 0 && $('#tab-style').is(':visible') ) {
			
			search_field.css('background-color', '#ffee52');
			
			search_field.effect( "pulsate", {times:3}, 150 );
			
			setTimeout(function () {
				search_field.css('background-color', '#fff')
			}, 550);
		}
	}

</script>




<script>

//  ███████╗██╗   ██╗███████╗███╗   ██╗████████╗███████╗
//  ██╔════╝██║   ██║██╔════╝████╗  ██║╚══██╔══╝██╔════╝
//  █████╗  ██║   ██║█████╗  ██╔██╗ ██║   ██║   ███████╗
//  ██╔══╝  ╚██╗ ██╔╝██╔══╝  ██║╚██╗██║   ██║   ╚════██║
//  ███████╗ ╚████╔╝ ███████╗██║ ╚████║   ██║   ███████║
//  ╚══════╝  ╚═══╝  ╚══════╝╚═╝  ╚═══╝   ╚═╝   ╚══════╝
//      

$(document).ready(function() {
	
// *************************************************************************************************************************	
//                                                 Search Option EVENTS	
// *************************************************************************************************************************


	// Enable FULL TEXT indexes when the algorithm type is set to "FAST"
	$('input[name="adsmart_search_algorithm"][value="fast"]').click( function(){
		
		var index_db_checkbox = $('input[name="adsmart_search_index_db"]');
		if ( index_db_checkbox.prop('checked') == false  ){
		
			$( "#dialog-fast-algorithm-and-indexing" ).dialog({
				resizable: false,
				width: 300,
				height: 200,
				minHeight: 300,
				modal: true,
				buttons: {
					" Yes ": function() {
						$( this ).dialog( "close" );
						index_db_checkbox.prop('checked', true).val('1');
						show_hide_rebuild_btn(index_db_checkbox);
					},	
					
					" No ": function() {
						$( this ).dialog( "close" );						
					}
				}
			});
		}
	});
	
	// After the page loads, always check that FULL TEXT indexing is enabled when the search algorithm is set to "FAST"
	if ( $('input[name="adsmart_search_algorithm"][value="fast"]').is(':checked') ) {
		$('input[name="adsmart_search_index_db"]').prop('checked', true).val('1');
	}
	
	
	<?php
	if (empty($adsmart_search_fields)) {
	
		$adsmart_search_fields = array(
			'meta_keyword',
			'meta_description',
			'name',
			'description',
			'tag',
			'model',
			'sku',
			'upc',
			'ean',
			'jan',
			'isbn',
			'mpn',
			'location',
			'manufacturer_name',
			'attribute_group_name',
			'attribute_name',
			'attribute_description',
			'option_name',
			'option_value',
			'category_name'
		);
	}
	?>
	
	
	// Call the function sortable to enable the drag&drop sorting option
	$( "#sort_flds_by_rel ul").sortable({ 

		create: function(event, ui){  
		//	field_list_update(); // moved into the "Initialization" section so it will be called only after all
								 //	input fields (hidden inputs included) have been created and initialized
		},

		update: function(event, ui){  
			field_list_update();			
		}
	}).disableSelection();
	
	$("#sort_flds_by_rel li").mouseenter(function(){
		$(this).siblings().removeAttr('style');
		$(this).css('position', 'relative').css('background-color','#f6f7ca');
	});
	
	$("#sort_flds_by_rel li").mouseleave(function(){
		$(this).removeAttr('style');
	});
	
	$("#sort_flds_by_rel li").mouseup(function(){
		$(this).removeAttr('style');
		$(this).css('background-color','#fad35f');
		$(this).animate({backgroundColor: '#FFFFFF'}, 1000);
	});
	
	$("#sort_flds_by_rel li").mousedown(function(){
		$(this).css('background-color','#fad35f');
	});

	
// When the user clicks on a checkbox update the relevance values for the selected fields
	$('#sort_flds_by_rel input[type=checkbox]').on('click', function(){
			field_list_update();
	});

		
// Enable / disable all fields
	$('#enable_all_fields').on('click', function(){
		$('#sort_flds_by_rel input[type=checkbox]').prop('checked',true);

		field_list_update();
	});	
	$('#disable_all_fields').on('click', function(){
		$('#sort_flds_by_rel input[type=checkbox]').prop('checked', false);
		field_list_update();
	});	
		

// Button Update search cache
	$('input[name="adsmart_search_update_search_cache"]').on('click', function() {
		update_search_cache();
	});	

	
// Button Clear search cache	
	$('input[name="adsmart_search_clear_search_cache"]').on('click', function() {
		clear_search_cache();
	});	
	
	
	
	
	
	
	$('input[name="adsmart_search_index_db"]').on('click', function(e) {
	
		if ( $('input[name="adsmart_search_algorithm"][value="fast"]').prop('checked') == true ) {
		
			 e.preventDefault();
		
			$( "#dialog-no-indexing-then-default-algorithm" ).dialog({
				resizable: false,
				width: 300,
				height: 200,
				minHeight: 300,
				modal: true,
				buttons: {
					" Yes ": function() {
						
						// switch to the default algorithm and uncheck the table indexing checkbox
						$('input[name="adsmart_search_algorithm"][value="default"]').prop('checked', true);
						$('input[name="adsmart_search_index_db"]').prop('checked', false);
						$('.rebuild_indexes').slideUp();
						$( this ).dialog( "close" );	
					},	
					
					" No ": function() {
						$( this ).dialog( "close" );						
					}
				}
			});
		} 
		else {
			show_hide_rebuild_btn(this);
		}
		
	});	
	
	
	
	// Button Rebuild db indexes
	$('input[name="adsmart_search_rebuild_indexes"]').on('click', function() {
		rebuild_db_indexes();
	});	
	
	

// ***********************  Sliders   ************************

	// **************  Partial word length slider   **************
		 
		<?php 
		if ( $adsmart_search_algorithm == 'fast' ) { $min_partial_word_len = $adsmart_search_ft_min_word_len; }
		else { $min_partial_word_len = 1; }
		?>
		$("#partial_word_length_slider").slider({                   
			value: 3,
			min: <?php echo $min_partial_word_len ?>, /* number of characters */
			max: 30,
			step: 1,
		
			slide: function(event, ui) {
				$('input[name="adsmart_search_partial_word_length"]').val(ui.value); 
				highlight_partial_word();
			}
		});
		

		// Change the minimum partial word length when the user switches from fast to default algorithm
		$( 'input[name="adsmart_search_algorithm"]' ).change( function() {
			if ($(this).val() == 'fast' ){
			
				// left handle should be at the left end, but it doesn't move
				$('#partial_word_length_slider').slider("option", "min", <?php echo $adsmart_search_ft_min_word_len ?>); 
				$('#partial_word_search_algorithm').html('<b><?php echo $text_fast ?></b>');
				$('.partial_word_ft_min_word_len').show().css('color','red');
			}
			else {
				$('#partial_word_length_slider').slider("option", "min", 1); 
				$('#partial_word_search_algorithm').html('<b><?php echo $text_default ?></b>');
				$('.partial_word_ft_min_word_len').hide();
			}
			
			//force the view refresh, re-setting the current value
			$('input[name="adsmart_search_partial_word_length"]').val($("#partial_word_length_slider").slider("value")); 
			highlight_partial_word();

		});

	
	// ************  Misspelling slider   *************
	
		$("#misspelling_tolerance_slider").slider({                   
			value: 20,
			min: 0, /* in% */
			max: 100,
			step: 5,
			slide: function(event, ui) {
				$('input[name="adsmart_search_misspelling_tolerance"]').val(ui.value); 
			}
		});

		
	// ***********  Cache frequency slider   **********	
		
		var frequency = ["Hourly","Daily","Weekly","Monthly","Yearly"];
		var frequency_in_seconds = ["3600","86400","604800","2419200","29030400"]; 
		
		$("#cache_update_frequency_slider").slider({                   
			value: 1,
			min: 0, 
			max: 4,
			step: 1,
			slide: function(event, ui) {
				
				$('input[name="adsmart_search_cache_update_frequency"]').val(frequency_in_seconds[ui.value]);	// hidden field
				$('span.adsmart_search_cache_update_frequency').html(frequency[ui.value]);	// text displayed to user 
			}
		});

	
	
	
	
	

// *************************************************************************************************************************	
//                                         Live search Options & Style  EVENTS	
// *************************************************************************************************************************	

	// init "Enable Live Search" radio buttons
	$('input[name="adsmart_search_dropdown_enabled"][value="<?php echo $adsmart_search_dropdown_enabled ?>"]').prop('checked', true);

	// init "update on entire word" radio buttons
	$('input[name="adsmart_search_dropdown_update_on_entire_word"][value="<?php echo $adsmart_search_dropdown_update_on_entire_word ?>"]').prop('checked', true);
	$('input[name="adsmart_search_dropdown_update_on_entire_word"]').on('change', function() {pulsate_search_input();});
	
	
	
// **************  Display/hide images   **************
	
	$('input[name="adsmart_search_dropdown_display_img"]').click( function(){
		display_hide_image($(this));
		pulsate_search_input();
	});	
	
	
// **************  Display/hide Prices   **************	
	
	$('input[name="adsmart_search_dropdown_display_price"]').click( function(){
		display_hide_prices($(this));
		update_viewport();
	});	
	
	

// ******************  Sliders   **********************		
	
	$(".dropdown_slider").click( function(){
		set_custom_style_option();
	});    
	
	$("#dropdown_width_slider").slider({                   
		value: 200,
		min: 170, /* in px */
		max: 500,
		step: 1,
		slide: function(event, ui) {
			set_width(ui.value);
			update_viewport();
			set_dropdown_position($('input[name="search"]'));	// see file adsmartsearch_livesrc_js.php
		}
	});

	$("#text_size_slider").slider({                   
		value: 14,
		min: 10, /* in px */
		max: 30,
		step: 1,
		slide: function(event, ui) {
			set_text_size(ui.value);
			update_viewport();
		}
	});
	
	$("#max_num_results_slider").slider({                   
		value: 10,
		min: 1,
		max: 25,
		step: 1,
		slide: function(event, ui) {
			$('input[name="adsmart_search_dropdown_max_num_results"]').val(ui.value);
			
			var num_results = $('.adsmart_search li').length-1;
			var max_num_results = ui.value;

			for (var i = 0; i <= max_num_results; i++) {
				$('.adsmart_search li:nth-child('+i+')').css('display','block');
			}
		
			for (var i =  max_num_results+1; i <= num_results+1; i++) {
			
				// To be fixed in the next releases:
				// For some reason, css('display','block') or hide() doesn't work, so for now
				// copy the attribute "style" in a temp var e change the property "display"
				// by a string substitution
				var tmp_style = $('.adsmart_search li:nth-child('+i+')').attr('style');
				tmp_style = tmp_style.replace('display: block', 'display: none !important');
				$('.adsmart_search li:nth-child('+i+')').attr('style',tmp_style);	
			}
			update_viewport();
		}
	});
		
	$("#img_size_slider").slider({                   
		value: 40,
		min: 15, /* in px */
		max: 80,
		step: 1,
		slide: function(event, ui) {
			$('input[name="adsmart_search_dropdown_img_size"]').val(ui.value);
			$('.adsmart_search div.image, .adsmart_search div.image img').css( 'width',ui.value+'px' ).css('height', 'auto'); 
			update_viewport();
		}
	});
	
	
	
	
// ***************   Text Options   *******************	

	$('input[name="adsmart_search_dropdown_show_all[<?php echo $config_language_id ?>]"]').keyup(function(){

		$('.show_all_results a').html($('input[name="adsmart_search_dropdown_show_all[<?php echo $config_language_id ?>]"]').val());
	});
		
	$('input[name="adsmart_search_dropdown_no_results[<?php echo $config_language_id ?>]"]').keyup(function(){

		$('.no_results').html($('input[name="adsmart_search_dropdown_no_results[<?php echo $config_language_id ?>]"]').val());
	});


	

// ****************   Color Picker   ******************		

	// Colors can be:
	// 1) Picked with Colorpicker
	// 2) typed/pasted in the text input
	
// 1) ColorPicker (minicolors)
	
	// Init default values before to instantiate the plugin 
	$.minicolors.defaults.changeDelay = 5;
	$.minicolors.defaults.position = 'bottom left';

	// Call Minicolors when the mouse focuses an input field
	$('.colpick').minicolors({

		show: function () {
			hide_warning($(this));			
			set_custom_style_option();
		},
		
		change: function (hex, opacity) {
		
			if ( is_valid_hex_color($(this).val() ) ) {	
				hide_warning($(this));
				style_color_picker_input($(this),hex);
				set_dropdown_style_and_hidden_input_fields();	
			}
			else {
				show_warning($(this));
			}	
		},
		
		hide: function () {

			if ( !is_valid_hex_color($(this).val() ) ) {
				show_warning($(this));
			}
		}
	});
	
	// Fix the position of the color panel according on the available space on right or left
	$('.colpick').on('click', function () { 
		
		setTimeout(function(){ 
				 
			var offset_fix = 0;
			
			if ( $('#sticky-header').hasClass('fixed') ){
				offset_fix = $('#sticky-header').outerHeight();
			}
			
			if ( $(window).width() - (that.offset().left + 150) < 0 ){
				$('.minicolors-panel').css('right','0px').css('left','auto');
			}
			else {
				$('.minicolors-panel').css('left','0px').css('right','auto');
			}
			// move on top the field if the device win height is too small (and also leave space for the virtual keyboard)
			if ($(window).height() < 768 ){
				$('html,body').scrollTop(that.offset().top - offset_fix);
			}	
		}, 200, that = $(this));	
	});
				

				

// ***************   Preset Styles   ******************					
		
	$('select[name="adsmart_search_dropdown_preset_style"]').on({

		focus: function() {
			$(this).next('span').remove();
			$(this).after('<span class="warning-icon adsmart-warning" style="display:inline-block; width:250px; height:40px; background-color:transparent; background-position:0% 20%;" ><span><?php echo $text_warning_select_style ?></span>');
		},
		change: function() {
		
			load_preset_style($(this).val());
		
			$(this).next('.adsmart-warning').fadeOut(300);
			$(this).blur();
			pulsate_search_input();
		}
	});	
	

	
	
	
	
// *************************************************************************************************************************	
//                                             Header, Menu & Tabs EVENTS 	
// *************************************************************************************************************************	
	
	
// ********************   Menu   **********************			
	
	// Compact menu
	$('.icon-menu').click(function(e) {
	
		$('.menutabs.compact').toggle(300);
	});
	
	$('.menutabs').mouseleave(function() {

		if ( $(this).hasClass('compact') ){
			$(this).toggle(300);
		}
    });
	
	var toggle_menu_timer;
	var dropdown_width_timer;
	
	$(window).resize(function() {
	
		var timeout = 50;
	
		// Switch between compact/regular menu view
		clearTimeout(toggle_menu_timer);
		toggle_menu_timer = setTimeout( toggle_menu , timeout);
		
		// Resize the dropdown on window resize
		clearTimeout(dropdown_width_timer);
		dropdown_width_timer = setTimeout( set_width($('input[name=adsmart_search_dropdown_width]').val()), timeout);
		
	});
	
	// init:
	toggle_menu();
	

	
// ***************   Sticky header   ******************			


	if ( !isTouchSupported() && !( $(window).width() <= 768 || $(window).height() <= 400) ){ // disable sticky header for mobiles and low res devices

		sticky('#sticky-header',20, 0, 0, 0, 480); 	// id or class ,edge_top, position(top,left) min w, min h	
	}
	
	// change the position of elements outside the header
	$('#sticky-header').on('sticky_change', function (e) {    

		// Don't stick the header on top when the tab "Search Analytics" is selected 
		if ( $('.menutabs a[href="#tab-search-analytics"]').hasClass('selected') ) {
			
			$('#sticky-header').removeClass('fixed');

		} else {
	
			if ($(this).data('header_is_sticky') == true) {
			 
				$('#header').addClass('fixed');		
			}
			 else {
				$('#header').removeClass('fixed');
			}
		}
			
		if ( $('#header').length ) {
			header += $('#header').height();
		}

		sticky_toc();
	});
	



	
	
// *************************************************************************************************************************	
//                                                User Guide EVENTS 	
// *************************************************************************************************************************		
	
	
	header_offset_correction = $('#header-wrapper').height() + 20; // added a correction of 20px from the top

	// Enables links within the tab page (the user guide links are handled by another script)
	$('a').click(function(e){
	
		// If the attribute href exists and if the link is a inner link (starts with #):
		if ( $(this).attr('href') && $(this).attr('href').charAt(0) == '#'){
		

			// If the link is a Tab
			if ($(this).parents('.menutabs').length) {
				open_tab($(this), e);		
			}
		

			// If the link is a TOC entry
			if ($(this).parents('#user-guide .index').length) {
			
				$('#user-guide .index a').removeClass('selected');
				$(this).addClass('selected');
			}

			var destination = $(this).attr('href').replace(/^#/,"");

			// If the destination is inside a tab		
			var container_tab_id = $('a[name="'+destination+'"]').closest('.tab-content').attr('id');

			// if the tab exists
			if (typeof container_tab_id != "undefined") {
			
				// Open the current tab (Don't use .click() to open a tab! It would mess up the browser history
				open_tab( $('.menutabs a[href="#'+container_tab_id+'"]'));
			}
			
			
			if ( $('a[name="'+destination+'"]').length ) { // check if defined
				$('html, body').animate({ scrollTop: $('a[name="'+destination+'"]').offset().top - header_offset_correction }, 300); // go to destination + animation	
			}


			if (history.pushState) {
				history.pushState({id:destination}, e.target.textContent, 'index.php?route=module/adsmart_search&token=<?php echo $token; ?>#'+destination);
			}
			e.preventDefault();
		}
	});
	

	$("#user-guide .hide-index").on("click", function (e) {

		$("#user-guide .index, #user-guide .content, .hide-index").toggleClass('hidden-index');	
		$('#user-guide .index a.selected').trigger('click');
	});
	
	
	// highlight the current table of content entry
	$(window).scroll(function() {

		var winTop = $(this).scrollTop() + header_offset_correction;
		var $index_titles = $('#user-guide .content :header a[name]');

		// grep all titles before wintop
		var top = $.grep($index_titles, function(item) {
			 return $(item).position().top <= winTop;		
		});
		
		// select the last element from the array top (the last header shown after scrolling)
		var current_title = $(top).last().attr('name');
		
		// Highlight the TOC entry:
		$('#user-guide .index a').removeClass('selected');
		$('#user-guide .index a[href="#'+current_title+'"]').addClass('selected');	
	});
	
	
	
	
// *************************************************************************************************************************	
//                                                User Guide EVENTS	
// *************************************************************************************************************************		
	

	$(window).on('scroll resize', function() {
		clearTimeout(this.resize_id); // call to the function sticky_toc() only every 25ms, not million times for each resizing/scrolling
		this.resize_id = setTimeout(sticky_toc, 25);	
	});

	
	// Init jscroll for the help (DISABLED)
	// var $tablescroll = $('.jscroll-x.jscroll');
	// $tablescroll.jscroll({ axis: 'x',   scrollInvert : true });;

	

	// See hashchange plugin
	// Bind an event to window.onhashchange that, when the hash changes, gets the
	// hash and adds the class "selected" to any matching nav link.
	$(window).hashchange( function(){

		$('a[href="'+location.hash+'"]').click();
	});
	  
	// Since the event is only triggered when the hash changes, we need to trigger
	// the event now, to handle the hash the page may have loaded with.
	// $(window).hashchange();
	$('a[href="#tab-search-analytics"]').click();	// Open the first tab on page load
	

	
	
	
// *************************************************************************************************************************	
//                                                Misc EVENTS	
// *************************************************************************************************************************	
	

	
// ****************   Style tables   ******************		
	
	// add the class .skip if you want to skip a row
	
	$("#form  tbody > tr:even").addClass('even');
	
	$('#form table tbody table thead > tr, #form table .skip').each(function() {
		$(this).removeClass('even');	
	});
	

	// When a checkbox is checked the value posted is an empty string. Better to assign the value 1 rather than
    // submitting an empty value so will be more clear when checking via php if the flag is true or false.
	// Sometimes we cannot use the php function "isset" in "if" statements when the condition is a value returned from a
	// function, for example "if (isset($this->config->get('adsmart_search_include_plurals')))" would return a fatal error.
	// This is the right way to check if the value is set:
	// if (empty($this->config->get('adsmart_search_include_plurals'))) but it could be misunderstood because it has a negative 
	// meaning. If the flag value is 1 we can use this:
	// if ($this->config->get('adsmart_search_include_plurals')) that makes things more clear

	// The function input_snapshot() makes use of this checkbox manager. DO NOT REMOVE IT.
	
	// checkbox manager
	$('input[type="checkbox"]').click(function() {

		if($(this).is(':checked')) {  
			$(this).prop('checked', true).val('1');
		}
		else {  
			$(this).prop('checked', false).val('');
		}
	});
	
	// init checkboxes
	$('input[type="checkbox"]').each(function(){
		if($(this).is(':checked')) {  
			$(this).prop('checked', true).val('1');
		}
		else {  
			$(this).prop('checked', false).val('');
		}
	});
	
	
	
	
// ***************   Save settings   ******************		
	
	$('a.ajax_save').on('click', function(){	
		save('ajax');			
	});
	
	$('a.save').on('click', function(){	
		save();			
	});	
	


	
// ******************   Other   ***********************		
 
	$('input[readonly]').focus(function(){
		this.blur();
	});
	
	$('#search input[name="search"]').on('focus', function(){  
		$(this).css('background-color', '#fff'); 
	});
	
	
	// Add the word "New!" to the ".adsmart_search_sort_order" select elements
	$('select[name="adsmart_search_sort_order"] option.new').append(' - NEW!');


	
	
	
	
	
// *************************************************************************************************************************	
//                                                 Initialization 	
// *************************************************************************************************************************

// Init Search Options window
	
	// Sliders
	
	$('#partial_word_length_slider').slider("option", "value", $('input[name="adsmart_search_partial_word_length"]').val());
	$( 'input[name="adsmart_search_algorithm"]' ).trigger('change'); // update the partial word panel according to the search algorithm type

	
	$('#misspelling_tolerance_slider').slider("option", "value", $('input[name="adsmart_search_misspelling_tolerance"]').val());

	// get the index for the arrays "frequency" and "frequency_in_seconds" that will be used as slider cursor value:
	var slider_cursor_value = frequency_in_seconds.indexOf($('input[name="adsmart_search_cache_update_frequency"]').val());
	$('span.adsmart_search_cache_update_frequency').html(frequency[slider_cursor_value]); // text displayed to user 
	$('#cache_update_frequency_slider').slider("option", "value", slider_cursor_value ); // set the slider cursor when the page loads
	
	// Update the field list
	field_list_update();
	
	// Init Sort Order select
	$('select[name="adsmart_search_sort_order"] option[value="<?php echo $adsmart_search_sort_order; ?>"]').prop('selected',true);

		
	// show/hide the button "rebuild indexes" according to the flag "Index db tables"
	show_hide_rebuild_btn($('input[name="adsmart_search_index_db"]'));
	
		
	// Init Style window
	$('select[name="adsmart_search_dropdown_preset_style"] option[value="<?php echo $adsmart_search_dropdown_preset_style; ?>"]').prop('selected',true);
	$('select[name="adsmart_search_dropdown_preset_style"]').trigger('change');

	
	<?php if ($adsmart_search_first_boot ) { ?>
 
		// save the default settings	
		ajax_save('silent');
		
	<?php } ?>
	
	
	// Call input_snapshot() when the page loads.
	input_snapshot();
	
});	

</script>


<?php // js minify hook - ob_end_flush - do not remove or modify this line ?> 




<style>
<?php 
//  ********************************
//  ****** Mobile layouts **********
//  ********************************
?>		

	
@media screen and (min-width: <?php echo $device_xs ?>px) {

	.hide-index {
		margin-left:50px;
	}	
}		

@media screen and (max-width: <?php echo $device_sm ?>px) {

	#sticky-header.fixed .buttons {
		top:10px;
		right:390px;
	}
	
	#sticky-header.fixed  #search {	
		top:0px;
		right:0px;
	}
}



@media screen and (max-width: <?php echo $device_xs ?>px) {

	#sticky-header {
		margin-left:0px;
	}
	
	#sticky-header h1 {
		display:none;
	}
		
	#sticky-header .menutabs.compact {
		display:none;
		width: 270px;
		top:68px; left:10px;	
		z-index:20; /*DON'T SET A LOWER VALUE, IT WOULD BE DISPLAYED BELOW THE FIXED INDEX */
	}
	#sticky-header .menutabs.compact a {
		text-align:left;
		float:none;
		display:block;
	}
	
	#sticky-header > .image {
		display:none;
	}
	
	#sticky-header .icon-menu {
		display:block;
		top:20px;
		left:10px;
	}

	
	/* Fixed style */
	
		#header.fixed,
		#sticky-header.fixed .icon-menu,
		#sticky-header.fixed .menutabs.compact {
			left:0;
		}
		
		#sticky-header.fixed .icon-menu {
			display:block;
			top:38px;
		}
						
		#sticky-header.fixed .menutabs.compact {	
			display:none;
			top:88px;	
		}
		
		#sticky-header.fixed .menutabs.compact a {
			text-align:left;
			float:none;
			display:block;
		}
		
		#sticky-header.fixed .buttons {
			top:5px;
			right:10px;
		}
	
		#sticky-header.fixed .button.save {
			display:none;
		}
	
		#sticky-header.fixed  #search {	
			top:40px;
			right:0px;
		}
		

	
		#column-left {
			width:0;
		}		
		
		.hide-index {
			margin-left:0px;
		}
		
}

@media screen and (max-width: <?php echo $device_xxs ?>px) {
	
	.breadcrumb {
		color:transparent;
	}
	
	.breadcrumb a {
		display:none;	
	}
	#search {
		width: 220px;
	}	
	
	#content table.container {
	table-layout: auto; 
	}
	
	table.container .one {
		 width: 100%;
	}
			
	table.container td.help {
		visibility:hidden;
		display:none;
		width:0%;
	}
	
	table.container .subpanel {
		 width: 100%;
	}
	
}

<?php 
//  ********************************
//  ***** END  Mobile layouts ******
//  ********************************
?>	
</style>


	 

<?php
if ( version_compare(VERSION, '2.0.0.0', '>=') ) {
	echo $column_left; 
}
?>

<div id="content">
	
	<div class="dialogs">
		<div  id="dialog-confirm" title="<?php echo $text_dialog_update_cache_title ?>">
			<?php echo $text_dialog_update_cache_text ?>
		</div>
		
		<div  id="dialog-fast-algorithm-and-indexing" title="<?php echo $text_dialog_algorithm_fast_and_indexing_title ?>">
			<?php echo $text_dialog_algorithm_fast_and_indexing_text ?>
		</div>
		
		<div  id="dialog-no-indexing-then-default-algorithm" title="<?php echo $text_no_indexing_then_default_algorithm_title ?>">
			<?php echo $text_no_indexing_then_default_algorithm_text ?>
		</div>
		
		
		
	</div>

	
	<div class="box">
			
		<div id="header-wrapper">
		
			<div class="breadcrumb">
			  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
			 &raquo; <a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
			  <?php } ?>
			</div>
		
			<div id="sticky-header">

				<!-- Menu -->
				<div class="icon-menu">Menu</div>
				<div class="menutabs">
					<a href="#tab-search-analytics"><i class="fa fa-bar-chart-o fa-fw"></i><?php echo  $text_search_analytics; ?></a>		
					<a href="#tab-search-options"><i class="fa fa-cogs"></i><?php echo  $text_search_options; ?></a>					
					<a href="#tab-style"><i class="fa fa-cogs"></i><?php  echo $text_style; ?></a>
					<a href="#tab-user-guide"><i class="fa fa-info-circle"></i><?php echo  $text_user_guide; ?></a>
					<a href="#tab-license"><i class="fa fa-file-text-o"></i><?php  echo  $text_license; ?></a>
				</div>
				
				<div class="image">
					<img src="view/image/adsmartsrc_logo.jpg" alt="" />
				</div>
				<h1><?php echo $heading_title; ?></h1>
				<div class="buttons">
					
					<?php if( ADSMART_SRC_DEMO ) { ?>
					<a class="button orange" target="_blank" style="margin-right:10px;" href="<?php echo $website_url ?>index.php">Demo Store Front</a>
					<?php } ?>
				
					<!-- <a onclick="$('#form').submit();" class="button green"><span><?php echo $button_save; ?></span></a> -->
					<a class="button green save"><span><?php echo $button_save; ?></span></a>
					<a class="button green ajax_save" ><span><?php echo $button_save_continue; ?></span></a>
					<a onclick="location = '<?php echo $cancel; ?>';" class="button green"><span><?php echo $button_cancel; ?></span></a>			
				</div>				
				
				
				<?php if( ADSMART_SRC_DEMO ) { ?>
				<!--
				<div id="demo">
					<i class="pin"></i>
					<h1>Demo</h1><p><?php echo $text_demo_postit; ?></p>
				</div>
				-->
				<?php } ?>
				
				
				<div id="search">		
					<label><?php echo $text_keep_open ?>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="keep_dropdown_open" value="" /></label>
					<input type="text" name="search" placeholder="<?php echo $text_test_it ?>" value="" />
				</div>
					
					
			</div>
			
		</div>

			
		<div class="content">

			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
		
<?php 	if ( isset($search_analytics) )  { ?>	
		
<!-- TAB SEARCH ANALYTICS -->				
				<div id="tab-search-analytics" class="tab-content">
				
					<?php echo $search_analytics; ?>

				</div>
<?php } ?>		
	
<!-- TAB SEARCH OPTIONS -->				
				<div id="tab-search-options" class="tab-content">
				
				
<!-- Module Status -->						
					<table class="container">			
						<thead>
							<tr>
								<th style="width:50%"><h2><?php echo $text_enable_module ?></h2></th>
								
								<th style="text-align:left;">
								
									<input type="radio" name="adsmart_search_status" value="1" <?php if ($adsmart_search_status == '1'){ ?> checked="checked" <?php } ?> >
									<?php echo $text_yes ?>	
									
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									
									<input type="radio" name="adsmart_search_status" value="0" <?php if ($adsmart_search_status == '0'){ ?> checked="checked" <?php } ?> >
									<?php echo $text_no ?>
								</th>
								
							</tr>
						</thead>

					</table>
				
				

<!-- Search Algorithm -->				
					<table class="container">	

						<thead>
							<tr>
								<th colspan="2">
									<h2><?php echo $text_search_algorithm; ?><a class="button blue help" href="#search-algorithm"><?php echo $text_help; ?></a></h2>
								</th>
							</tr>
						</thead>
					
						<tbody>
							<tr>
								<td class="one">
									<input type="radio" name="adsmart_search_algorithm" value="fast" <?php if ($adsmart_search_algorithm == 'fast'){ ?> checked="checked" <?php } ?> /><?php echo $text_fast; ?>
								</td>
								
								<td class="two help">
									<?php echo $text_help_fast; ?>	
								</td>	
							</tr>
						
							<tr>
								<td class="one">
									<input type="radio" name="adsmart_search_algorithm" value="default" <?php if ($adsmart_search_algorithm == 'default'){ ?> checked="checked" <?php } ?> /><?php echo $text_default; ?>	
								</td>
								
								<td class="two help">
									<?php echo $text_help_default; ?>
								</td>
							</tr>
							
						</tbody>
					</table>
					

	
					
<!-- Match type -->	
					<table class="container">			
					
						<thead>
							<tr>
								<th colspan="2">
									<h2><?php echo $text_match_type; ?><a class="button blue help" href="#match-type"><?php echo $text_help; ?></a></h2>
								</th>
							</tr>
						</thead>
					
						<tbody>
							
							<tr>
								<td class="one">
									<input type="radio" name="adsmart_search_exact_broad" value="exact" <?php if ($adsmart_search_exact_broad == 'exact'){ ?> checked="checked" <?php } ?> /><?php echo $text_exact_match; ?>
								</td>
								
								<td class="two help">
										<?php echo $text_help_exact_match; ?>
								</td>
								
							</tr>
						
							<tr>
								<td class="one">
									<input type="radio" name="adsmart_search_exact_broad" value="broad" <?php if ($adsmart_search_exact_broad == 'broad'){ ?> checked="checked" <?php } ?> /><?php echo $text_broad_match; ?>
								</td>
								
								<td class="help">
									<?php echo $text_help_broad_match; ?>
								</td>
								
							</tr>
						
						</tbody>
					</table>
							
							
							
<!-- Sort Order -->								
					<table class="container">	

						<thead>
							<tr>
								<th colspan="2">
									<h2><?php echo $text_sort_order; ?></h2>
								</th>
							</tr>
						</thead>
					
						<tbody>
							<tr>
								<td class="one">								
									<select name="adsmart_search_sort_order">
										<option value="p.sort_order-ASC"><?php echo $text_optn_default; ?></option>
										<option value="relevance-DESC" class="new" ><?php echo $text_optn_relevance; ?></option>
										<option value="pd.name-ASC"><?php echo $text_optn_name_asc; ?></option>
										<option value="pd.name-DESC"><?php echo $text_optn_name_desc; ?></option>
										<option value="p.price-ASC"><?php echo $text_optn_price_asc; ?></option>
										<option value="p.price-DESC"><?php echo $text_optn_price_desc; ?></option>
										<option value="rating-DESC"><?php echo $text_optn_rating_desc; ?></option>
										<option value="rating-ASC"><?php echo $text_optn_rating_asc; ?></option>
										<option value="p.model-ASC"><?php echo $text_optn_model_asc; ?></option>
										<option value="p.model-DESC"><?php echo $text_optn_model_desc; ?></option>
										<option value="p.date_added-DESC" class="new"><?php echo $text_optn_date_desc; ?></option>
										<option value="p.date_added-ASC" class="new"><?php echo $text_optn_date_asc; ?></option>
									</select> 

								</td>
								
								<td class="two help">
									<?php echo $text_help_sort_order; ?>
								</td>
								
							</tr>
						
						</tbody>
					</table>
					
					
					<table class="container">
						<tbody>
					
							<tr>
								<td class="one">
								
									<table style="width:100%">		

										<thead>
											<tr>
												<th><?php echo $text_translate_extra_sort; ?></th>
											</tr>
										</thead>
										
										<tbody>								
											<tr>
												<td>
													<div style="padding-left:28px"><?php echo $text_optn_relevance; ?></div>
													<?php foreach ($languages as $language) { ?>

													<img src="<?php echo $language['image_path']; ?>" title="<?php echo $language['name']; ?>" />
													<input placeholder="<?php echo $text_add_translation ?>" class="translation" type="text" name="adsmart_search_translation_txt_relevance[<?php echo $language['language_id']?>]" value="<?php echo $adsmart_search_translation_txt_relevance[$language['language_id']]?>" />
													<br />
													<?php } ?>	
												</td>

											</tr>
											
											<tr>											
												<td>
													<div style="padding-left:28px"><?php echo $text_optn_date_desc; ?></div>
													<?php foreach ($languages as $language) { ?>

													<img src="<?php echo $language['image_path']; ?>" title="<?php echo $language['name']; ?>" />
													<input placeholder="<?php echo $text_add_translation ?>" class="translation" type="text" name="adsmart_search_translation_txt_date_desc[<?php echo $language['language_id']?>]" value="<?php echo $adsmart_search_translation_txt_date_desc[$language['language_id']]?>" />
													<br />
													<?php } ?>	
												</td>
											</tr>
											
											<tr>
												<td>
													<div style="padding-left:28px"><?php echo $text_optn_date_asc; ?></div>
													<?php foreach ($languages as $language) { ?>
													<img src="<?php echo $language['image_path']; ?>" title="<?php echo $language['name']; ?>" />
													<input placeholder="<?php echo $text_add_translation ?>" class="translation" type="text" name="adsmart_search_translation_txt_date_asc[<?php echo $language['language_id']?>]" value="<?php echo $adsmart_search_translation_txt_date_asc[$language['language_id']]?>" />
													<br />
													<?php } ?>	
												</td>
											</tr>
											
										</tbody>	
									</table>

								</td>
								
								<td class="two help">
									<?php echo $text_help_translate_sort_order; ?>
								</td>
							
							</tr>
							
							
						</tbody>
					</table>

		
		
<!-- Relevance -->				
					<table class="container">	

						<thead>
							<tr>
								<th colspan="2">
									<h2><?php echo $text_relevance; ?><a class="button blue help" href="#relevance"><?php echo $text_help; ?></a></h2>
								</th>
							</tr>
						</thead>
					
						<tbody>	
							<tr>
								<td class="one" id="sort_flds_by_rel">

									<div class="head">
										<span class="number"></span>
										
										<span class="checkbox">
											<a id="enable_all_fields"><?php echo $text_enable_all; ?></a><br />
											<a id="disable_all_fields"><?php echo $text_disable_all; ?></a>
										</span>
										<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
										<span class="field_name"><?php echo $text_field_name; ?></span>
									</div>
									
									
									<ul>
										<?php 

										foreach ($adsmart_search_fields as $field){ ?>
												<li class="ui-state-default">
													<span class="number"><?php if(isset($adsmart_search_relevance[$field])){ echo $adsmart_search_relevance[$field]; } ?></span>
													<span class="checkbox"><input type="checkbox" name="<?php echo $field ?>" class="relevance_field" <?php if( isset($adsmart_search_relevance[$field]) ){ ?> checked="checked" value="1" <?php } else { ?> value="" <?php } ?> /></span>
													<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
													<span class="field_name"><?php echo ${'entry_'.$field}; ?></span>
												</li>
										<?php } ?>
									</ul>
								</td>
								
								<td class="two help">
									<?php echo $text_help_relevance; ?>
								</td>
								
							</tr>
							
						</tbody>	
					</table>		


<!-- Hide O quantity products  -->
					<table class="container">		
						<thead>
							<tr>
								<th colspan="2">
									<h2><?php echo $text_hide_zeroqty_products; ?><a class="button blue help" href="#zeroqty"><?php echo $text_help; ?></a></h2>
								</th>
							</tr>
						</thead>
						
						<tbody>
							<tr>
								<td class="one">
									<input type="checkbox" name="adsmart_search_hide_zeroqty_products" <?php if(isset($adsmart_search_hide_zeroqty_products)){ ?> checked="checked" value="1" <?php } else { ?> value="" <?php } ?> />&nbsp;&nbsp;&nbsp;<?php echo $text_enable; ?>
								</td>
								
								<td class="two help">
								</td>
								
							</tr>
						</tbody>	
					</table>						
					
<!-- Plurals  -->
					<table class="container">		
						<thead>
							<tr>
								<th colspan="2">
									<h2><?php echo $text_include_plurals; ?><a class="button blue help" href="#plurals"><?php echo $text_help; ?></a></h2>
								</th>
							</tr>
						</thead>
						
						<tbody>
							<tr>
								<td class="one">
									<input type="checkbox" name="adsmart_search_include_plurals" <?php if(isset($adsmart_search_include_plurals)){ ?> checked="checked" value="1" <?php } else { ?> value="" <?php } ?> />&nbsp;&nbsp;&nbsp;<?php echo $text_enable; ?>
								</td>
								
								<td class="two help">
								</td>
								
							</tr>
						</tbody>	
					</table>		

<!-- Partial words -->
					<table class="container">		

						<thead>
							<tr>
								<th colspan="2">
									<h2><?php echo $text_include_partial_words; ?><a class="button blue help" href="#partial-words"><?php echo $text_help; ?></a></h2>
								</th>
							</tr>
						</thead>
						
						<tbody>
							<tr>
								<td class="one" style="vertical-align:middle;">

									<table class="subpanel">
									
										<thead>
											<tr>
												<th><?php echo $text_enable; ?></th>
												<th><?php echo $text_partial_word_length; ?></th>
											</tr>
										</thead>
										
										<tbody>
											<tr>
												<td style="text-align:center">
													<input type="checkbox" name="adsmart_search_include_partial_words" <?php if(isset($adsmart_search_include_partial_words)){ ?> checked="checked" value="1" <?php } else { ?> value="" <?php } ?> />
												</td>
												<td class="slider">
													<input type="text" name="adsmart_search_partial_word_length" readonly="readonly" value="<?php echo $adsmart_search_partial_word_length; ?>" />
													<div id="partial_word_length_slider" class="dropdown_slider"></div>
												</td>
											</tr>
											
											<tr>
												<td colspan="2" class="center" style="padding:10px 0">
													<span class="example_word">antidisestablishmentarisms</span>
												</td>
											</tr>
											
										</tbody>

									</table>
									
									<br/>
									
									<table class="subpanel">

										<tfoot>
										
											<tr>
												<td><b><?php echo $text_current_search_algorithm; ?></b></td>
												<td id="partial_word_search_algorithm" class="center" ></td>
											</tr>
											<tr>
												<td class="partial_word_ft_min_word_len" ><b>ft_min_word_len</b> (<a href="#ft-min-word-length"><?php echo $text_what_is_this ?></a>)</td>
												<td class="partial_word_ft_min_word_len center" ><b><?php echo $adsmart_search_ft_min_word_len; ?></b></td>
											</tr>
										</tfoot>
										
									</table>
									
									
									
								</td>

								<td class="two help">
									<?php echo $text_help_partial_words; ?>
								</td>
								
							</tr>
							
						</tbody>	
					</table>		


					
<!-- Misspellings -->
					<table class="container">	

						<thead>
							<tr>
								<th colspan="2">
									<h2><?php echo $text_include_misspellings; ?><a class="button blue help" href="#misspellings"><?php echo $text_help; ?></a></h2>
								</th>
							</tr>
						</thead>
						
						<tbody>
							
							<tr>
								<td class="one" style="vertical-align:middle;">

									<table class="misspellings subpanel">
									
										<thead>
											<tr>
												<th><?php echo $text_enable; ?></th>
												<th><?php echo $text_misspelling_tolerance; ?></th>
											</tr>
										</thead>
										
										<tbody>
											<tr>
												<td style="text-align:center">
													<input type="checkbox" name="adsmart_search_include_misspellings" <?php if(isset($adsmart_search_include_misspellings)){ ?> checked="checked" value="1" <?php } else { ?> value="" <?php } ?> />
												</td>
												<td class="slider">
													<input type="text" name="adsmart_search_misspelling_tolerance" readonly="readonly" value="<?php echo $adsmart_search_misspelling_tolerance; ?>" /> %
													<div id="misspelling_tolerance_slider" class="dropdown_slider"></div>
												</td>
											</tr>

										</tbody>
										
									</table>
								</td>
								
								<td class="two help">
								</td>
								
							</tr>
							
						</tbody>	
					</table>		


					
<!--  Cache manager -->
					<table class="container">	
						<thead>
							<tr>
								<th colspan="2">
									<h2><?php echo $text_cache_manager; ?><a class="button blue help" href="#cache-manager"><?php echo $text_help; ?></a></h2>
								</th>
							</tr>
						</thead>
						
						<tbody>
							
							<tr>
								
								<td class="one" style="vertical-align:middle;">

									<table class="cache_manager subpanel">
										<thead>
											<tr>
												<th><?php echo $text_enable; ?></th>
												<th><?php echo $text_cache_update_frequency; ?></th>
											</tr>
										</thead>
										
										<tbody>
											<tr class="skip">
												<td style="text-align:center">
													<input type="checkbox" name="adsmart_search_enable_search_cache" <?php if(isset($adsmart_search_enable_search_cache)){ ?> checked="checked" value="1" <?php } else { ?> value="" <?php } ?> />
												</td>
												<td class="slider">
													<div id="cache_update_frequency_slider" class="dropdown_slider"></div>
													<span class="adsmart_search_cache_update_frequency"></span>
													<input type="hidden" name="adsmart_search_cache_update_frequency" value="<?php echo $adsmart_search_cache_update_frequency; ?>" />
												</td>
												
											</tr>
											
											<tr>
												<td colspan="2" style="text-align:center">
													<input class="button blue" type="button" name="adsmart_search_update_search_cache" value="<?php echo $text_update_search_cache; ?>" />
													&nbsp;
													<input class="button blue" type="button" name="adsmart_search_clear_search_cache" value="<?php echo $text_clear_search_cache; ?>"/>
												</td>
											</tr>
											
											<tr>
												<td class="message" colspan="2"></td>
											</tr>
											
										</tbody>
									</table>
								</td>
								
								<td class="two help">
								</td>
								
							</tr>
								
						</tbody>	
					</table>		


					
<!-- DB Optimization  -->
					<table class="container">	
						<thead>
							<tr>
								<th colspan="2">
									<h2><?php echo $text_db_optimization; ?><a class="button blue help" href="#db-optimization"><?php echo $text_help; ?></a></h2>
								</th>
							</tr>
						</thead>
						
						<tbody>
							
							<tr>
								
								<td class="one">

									<table class="db_optimization subpanel">

										<tr>
											<td>
												<?php echo $text_index_db_tables; ?>
											</td>
											
											<td style="text-align:center">
												<input type="checkbox" name="adsmart_search_index_db" <?php if(isset($adsmart_search_index_db)){ ?> checked="checked" value="1" <?php } else { ?> value="" <?php } ?> />
											</td>
										</tr>
										
										<tr class="rebuild_indexes">
											<td>
												<?php echo $text_rebuild_indexes; ?>
											</td>
											
											<td style="text-align:center">
												<input class="button blue" type="button" name="adsmart_search_rebuild_indexes" value="<?php echo $text_rebuild; ?>"/>
											</td>
										</tr>
										
										<tr class="rebuild_indexes">
											<td class="message" colspan="2"></td>
										</tr>
										
									</table>
	
								</td>
								
								<td class="two help">
									<?php echo $text_help_db_optimization; ?>
								</td>	
							</tr>
							
						</tbody>	
					</table>		


					
<!-- MySQL conf  -->
					<table class="container">	
					
						<thead>
							<tr>
								<th>
									<h2><?php echo $text_curr_srv_conf; ?></h2>
								</th>
							</tr>
						</thead>
						
						<tbody>						
							
							<tr>

								<td>
									<table style="width:100%">
										<thead>
											<tr>
												<th><?php echo $text_mysql_setting_name; ?></th>
												<th><?php echo $text_mysql_var_value; ?></th>
												<th class="help"><?php echo $text_help; ?></th>	
											</tr>
										</thead>
										
										<tbody>
											<tr>
												<td><b>ft_min_word_len</b></td>
												<td><?php echo $adsmart_search_ft_min_word_len; ?></td>
												<td></td>	
											</tr>
											
											<tr>
												<td><b>ft_stopword_file</b></td>
												<td><?php echo $adsmart_search_ft_stopword_file; ?></td>
												<td class="help"></td>	
											</tr>
											
											<tr>
												<td><b>Mysql Engine</b></td>
												<td><?php echo $adsmart_search_mysql_engine; ?></td>
												<td class="help">(It should be set on MyISAM if you want to use FULL TEXT searches)</td>	
											</tr>
											
											<tr>
												<td><b>Table Collation</b></td>
												<td><?php echo $adsmart_search_mysql_collation; ?></td>
												<td class="help">(It's recommended to use a case insensitive collation, like utf8_general_ci)</td>	
											</tr>
											
										</tbody>

									</table>
								</td>			
							</tr>
	
						</tbody>
					</table>
					
					
<!-- Product stats  -->
					<?php if( !ADSMART_SRC_DEMO ) { ?>
					<table class="container">	
						<thead>
							<tr>
								<td colspan="2">
									<h2><?php echo $text_product_stats; ?></h2>
								</th>
							</tr>
						</thead>
						
						<tbody>						
							<tr>
								<td colspan="2">
									<table style="width:50%">
									
										<tbody>
											<tr>
												<td><?php echo $text_product_total; ?></td>
												<td><?php echo $product_total; ?></td>
											</tr>
										</tbody>

									</table>
								</td>			
							</tr>
	
						</tbody>
					</table>
					<?php } ?>

					
				</div>   <!-- end tab General Search Options -->
				

				
<!-- TAB STYLE  -->			
				<div id="tab-style" class="tab-content">
							
					<table class="container">			
						<thead>
							<tr>
								<th style="width:30%"><h2><?php echo $text_enable_live_search ?></h2></th>
								
								<th style="text-align:left; padding-left: 100px;">
								
									<input type="radio" name="adsmart_search_dropdown_enabled" value="1">
									<?php echo $text_yes ?>	
									
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									
									<input type="radio" name="adsmart_search_dropdown_enabled" value="0">
									<?php echo $text_no ?>	
								
								
								</th>
								
							</tr>
						</thead>

					</table>
					
					
					<table id="module_container" class="container">			
						<thead>
							<tr>
								<th><h2><?php echo $text_add_modules; ?></h2></th>
								<th class="help"><?php echo $text_help; ?></th>
							</tr>
						</thead>
						
						<tbody> 
							<tr>						
								<?php if (version_compare(VERSION, '2.0.0.0', '>=')) { ?>
									<td class="center"><a href="index.php?route=design/layout&token=<?php echo $token; ?>" class="button blue"><?php echo $button_add_module; ?></a></td>
								<?php } else { ?>		
									<td class="center"><a onclick="addModule();" class="button blue"><?php echo $button_add_module; ?></a></td>			
								<?php } ?>
									<td class="help"><?php echo $text_help_add_modules; ?></td>							
							</tr>
						</tbody>
						
						<tbody> 
							<tr>
								<td colspan="2">				
								
									<table id="module" class="list">
										<thead>
											<tr>
												<td class="left"><span class="required">*</span> <?php echo $entry_dimension; ?></td>
												<td class="left"><?php echo $entry_layout; ?></td>
												<td class="left"><?php echo $entry_position; ?></td>
												<td class="left"><?php echo $entry_status; ?></td>
												<td class="right"><?php echo $entry_sort_order; ?></td>
												<td></td>
											</tr>
										</thead>

										
										<?php $module_row = 0; ?>
										<?php foreach ($modules as $module) { ?>
										<tbody id="module-row<?php echo $module_row; ?>">
										
											<tr>
												<td class="left">
												<input class="right" type="text" name="adsmart_search_module[<?php echo $module_row; ?>][width]" value="<?php echo $module['width']; ?>" size="3" /> px
												&nbsp;&nbsp;&nbsp;
												<input class="right" type="text" name="adsmart_search_module[<?php echo $module_row; ?>][height]" value="<?php echo $module['height']; ?>" size="3" /> px
												<?php if (isset($error_dimension[$module_row])) { ?>
												<span class="error"><?php echo $error_dimension[$module_row]; ?></span>
												<?php } ?></td>
												<td class="left"><select name="adsmart_search_module[<?php echo $module_row; ?>][layout_id]">
												<?php foreach ($layouts as $layout) { ?>
												<?php if ($layout['layout_id'] == $module['layout_id']) { ?>
												<option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
												<?php } else { ?>
												<option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
												<?php } ?>
												<?php } ?>
												</select></td>
												<td class="left"><select name="adsmart_search_module[<?php echo $module_row; ?>][position]">
												<?php if ($module['position'] == 'content_top') { ?>
												<option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
												<?php } else { ?>
												<option value="content_top"><?php echo $text_content_top; ?></option>
												<?php } ?>
												<?php if ($module['position'] == 'content_bottom') { ?>
												<option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
												<?php } else { ?>
												<option value="content_bottom"><?php echo $text_content_bottom; ?></option>
												<?php } ?>
												<?php if ($module['position'] == 'column_left') { ?>
												<option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
												<?php } else { ?>
												<option value="column_left"><?php echo $text_column_left; ?></option>
												<?php } ?>
												<?php if ($module['position'] == 'column_right') { ?>
												<option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
												<?php } else { ?>
												<option value="column_right"><?php echo $text_column_right; ?></option>
												<?php } ?>
												</select></td>
												<td class="left"><select name="adsmart_search_module[<?php echo $module_row; ?>][status]">
												<?php if ($module['status']) { ?>
												<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
												<option value="0"><?php echo $text_disabled; ?></option>
												<?php } else { ?>
												<option value="1"><?php echo $text_enabled; ?></option>
												<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
												<?php } ?>
												</select></td>
												<td class="right"><input class="center" type="text" name="adsmart_search_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
												<td class="left"><a onclick="$('#module-row<?php echo $module_row; ?>').remove(); if ($('#module tbody').length <= 0) $('#module thead').hide();" class="button red"><?php echo $button_remove; ?></a></td>
											</tr>
										</tbody>
										<?php $module_row++; ?>
										<?php } ?>
										
									</table>
										
										
										
									<script type="text/javascript"><!--
									var module_row = <?php echo $module_row; ?>;
									
									if ($('#module tbody').length <= 0) $('#module thead').hide();
									function addModule() {	
										html  = '<tbody id="module-row' + module_row + '">';
										html += '  <tr>';

										html += '    <td class="left"><input class="right" type="text" name="adsmart_search_module[' + module_row + '][width]" value="" size="3" /> px';
										html += '    &nbsp;&nbsp;&nbsp;'; 
										html += '    <input class="right" type="text" name="adsmart_search_module[' + module_row + '][height]" value="" size="3" /> px </td>'; 
										html += '    <td class="left"><select name="adsmart_search_module[' + module_row + '][layout_id]">';
										<?php foreach ($layouts as $layout) { ?>
										html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
										<?php } ?>
										html += '    </select></td>';
										html += '    <td class="left"><select name="adsmart_search_module[' + module_row + '][position]">';
										html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
										html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
										html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
										html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
										html += '    </select></td>';
										html += '    <td class="left"><select name="adsmart_search_module[' + module_row + '][status]">';
										html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
										html += '      <option value="0"><?php echo $text_disabled; ?></option>';
										html += '    </select></td>';
										html += '    <td class="right"><input class="center" type="text" name="adsmart_search_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
										html += '    <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove(); if ($(\'#module tbody\').length <= 0) $(\'#module thead\').hide();" class="button red"><?php echo $button_remove; ?></a></td>';
										html += '  </tr>';
										html += '</tbody>';
										
										$('#module').append(html);
										
										module_row++;
										if ($('#module tbody').length > 0) $('#module thead').show();
									}
									//--></script> 

								</td>
								


							</tr>
						</tbody>

					</table>
					
				

					<table class="container">		
		
						<thead>
							<tr>
								<th colspan="2"><?php echo $text_live_search_options; ?></th>
								
								<th class="help"><?php echo $text_help; ?></th>
								
							</tr>
						</thead>
						
						<tbody>

		
							<tr>
								<td rowspan="2" >
									<?php echo $text_dropdown_update_on ?>	
								</td>
								
								<td>
									<input type="radio" name="adsmart_search_dropdown_update_on_entire_word" value="0">
									<?php echo $text_single_char ?>	
								</td>
								
								<td class="help">
									<?php echo $text_help_update_single_char ?>		
								</td>
							
								
							</tr>
							
							<tr>
							
								<td>
									<input type="radio" name="adsmart_search_dropdown_update_on_entire_word" value="1">
									<?php echo $text_entire_word ?>		
								</td>
								
								<td class="help">								
									<?php echo $text_help_update_entire_word ?>					
								</td>

							</tr>	
	
						</tbody>
						
					</table>
					
					
				
					<table class="container">			
					
						<thead>
							<tr>
								<th>
									<?php echo $text_live_search_style; ?>
								</th>
								<th>
								</th>
							</tr>
						</thead>
						
						<tbody>

							<tr>
								<td>
									<?php echo $text_preset_styles; ?>	
								</td>
								<td>
									<select name="adsmart_search_dropdown_preset_style">
										<option value="opencart_classic">Opencart Classic</option>
										<option value="ocean">Ocean</option>
										<option value="coca_cola">Coca Cola</option>
										<option value="teal">Teal</option>
										<option value="blue_sky">Blue Sky</option>
										<option value="dark_gray">Dark Gray</option>
										<option value="sand">Sand</option>
										<option value="mint">Mint</option>
										<option value="spicy">Spicy</option>
										<option value="pink">Pink</option>
										<option value="sunlight">Sunlight</option>
										<option value="orange_juice">Orange Juice</option>
										<option value="custom_style">Custom Style</option>
									</select> 
								</td>
							</tr>
						
							
							<tr>
								<td>
									<?php echo $text_dropdown_display_img; ?>	
								</td>
								<td>
									<input type="checkbox" name="adsmart_search_dropdown_display_img" <?php if(isset($adsmart_search_dropdown_display_img)){ ?> checked="checked" value="1" <?php } else { ?> value="" <?php } ?> />
								</td>
							</tr>
							
							<tr id="row_img_size_slider" class="skip">
								<td></td>
								<td>
									<table>
									
										<tr>
											<td>
												<?php echo $text_dropdown_img_size; ?>	
											</td>
											<td class="slider">
												<input type="text" name="adsmart_search_dropdown_img_size" readonly="readonly" value="<?php echo $adsmart_search_dropdown_img_size; ?>" /> px
												<div id="img_size_slider" class="dropdown_slider"></div>
											</td>
										</tr>
										
										<tr>
											<td>
												<?php echo $text_dropdown_img_border_color; ?>	
											</td>
											<td>
												<input type="text" class="colpick" name="adsmart_search_dropdown_img_border_color" maxlength="6" value="<?php echo $adsmart_search_dropdown_img_border_color; ?>" />
											</td>
										</tr>	
									
									</table>
								</td>
							</tr>
							
							
							<tr>
								<td>
									<?php echo $text_dropdown_width; ?>	
								</td>
								<td class="slider">
									<input type="text" name="adsmart_search_dropdown_width" readonly="readonly" value="<?php echo $adsmart_search_dropdown_width; ?>" /> px
									<div id="dropdown_width_slider" class="dropdown_slider"></div>
								</td>
							</tr>	
							
							<tr>
								<td>
									<?php echo $text_dropdown_display_price; ?>	
								</td>
								<td>
									<input type="checkbox" name="adsmart_search_dropdown_display_price" <?php if(isset($adsmart_search_dropdown_display_price)){ ?> checked="checked" value="1" <?php } else { ?> value="" <?php } ?> />
								</td>
							</tr>	
							
							<!--
							<tr>
								<td>
									<?php echo $text_dropdown_display_rating; ?>	
								</td>
								<td>
									<input type="checkbox" name="adsmart_search_dropdown_display_rating" <?php if(isset($adsmart_search_dropdown_display_rating)){ ?> checked="checked" value="1" <?php } else { ?> value="" <?php } ?> />
								</td>
							</tr>	
							-->

							<tr>
								<td>
									<?php echo $text_dropdown_text_size; ?>	
								</td>
								<td class="slider">
									<input type="text" name="adsmart_search_dropdown_text_size" readonly="readonly" value="<?php echo $adsmart_search_dropdown_text_size; ?>" /> px
									<div id="text_size_slider" class="dropdown_slider"></div>
								</td>
							</tr>							
							
							<tr>
								<td>
									<?php echo $text_dropdown_text_color; ?>	
								</td>
								<td>
									<input type="text" class="colpick" name="adsmart_search_dropdown_text_color" maxlength="6" value="<?php echo $adsmart_search_dropdown_text_color; ?>" />
								</td>
							</tr>		
							
							<tr>
								<td>
									<?php echo $text_dropdown_bg_color; ?>	
								</td>
								<td>
									<input type="text" class="colpick" name="adsmart_search_dropdown_bg_color" maxlength="6" value="<?php echo $adsmart_search_dropdown_bg_color; ?>" />
								</td>
							</tr>

							<tr>
								<td>
									<?php echo $text_dropdown_border_color; ?>	
								</td>
								<td>
									<input type="text" class="colpick" name="adsmart_search_dropdown_border_color" maxlength="6" value="<?php echo $adsmart_search_dropdown_border_color; ?>" />
								</td>
							</tr>
							
							<tr>
								<td>
									<?php echo $text_dropdown_hover_bg_color; ?>	
								</td>
								<td>
									<input type="text" class="colpick" name="adsmart_search_dropdown_hover_bg_color" maxlength="6" value="<?php echo $adsmart_search_dropdown_hover_bg_color; ?>" />
								</td>
							</tr>
							
							<tr>
								<td>
									<?php echo $text_dropdown_max_num_results; ?>	
								</td>
								<td class="slider">
									<input type="text" name="adsmart_search_dropdown_max_num_results" readonly="readonly" value="<?php echo $adsmart_search_dropdown_max_num_results; ?>" />									
									<div id="max_num_results_slider" class="dropdown_slider"></div>
								</td>
							</tr>	
							
							<tr>
								<td>
									<?php echo $text_dropdown_show_all; ?>	
								</td>
								
								<td>
									<?php foreach ($languages as $language) { ?>
										<img src="<?php echo $language['image_path']; ?>" title="<?php echo $language['name']; ?>" />
										<input class="translation" type="text" name="adsmart_search_dropdown_show_all[<?php echo $language['language_id']?>]" value="<?php echo $adsmart_search_dropdown_show_all[$language['language_id']]?>" />
										<br />
									<?php } ?>
								</td>
							</tr>
							
							<tr>
								<td>
									<?php echo $text_dropdown_no_results; ?>	
								</td>
								
								<td>
									<?php foreach ($languages as $language) { ?>
										<img src="<?php echo $language['image_path']; ?>" title="<?php echo $language['name']; ?>" />
										<input class="translation" type="text" name="adsmart_search_dropdown_no_results[<?php echo $language['language_id']?>]" value="<?php echo $adsmart_search_dropdown_no_results[$language['language_id']]?>" />
										<br />
									<?php } ?>
								</td>
							</tr>
							
							
						</tbody>
					</table>
					
					<input type="hidden" name="adsmart_search_dropdown_lighter_separator_color"	value="<?php echo $adsmart_search_dropdown_lighter_separator_color; ?>"	/>
					<input type="hidden" name="adsmart_search_dropdown_darker_separator_color"	value="<?php echo $adsmart_search_dropdown_darker_separator_color; ?>"	/>
					<input type="hidden" name="adsmart_search_dropdown_hover_border_color"		value="<?php echo $adsmart_search_dropdown_hover_border_color; ?>"		/>
					<input type="hidden" name="adsmart_search_dropdown_msg_bg_color"			value="<?php echo $adsmart_search_dropdown_msg_bg_color; ?>"			/>
					<input type="hidden" name="adsmart_search_dropdown_msg_text_color"			value="<?php echo $adsmart_search_dropdown_msg_text_color; ?>"			/>
					<input type="hidden" name="adsmart_search_dropdown_msg_text_size"			value="<?php echo $adsmart_search_dropdown_msg_text_size; ?>"			/>
					
					<input type="hidden" name="adsmart_search_last_cp_save_date"				value="<?php echo $current_date; ?>" />
										
					
				</div>  <!-- end tab style -->
				
				
<!-- TAB USER GUIDE  -->	
				<div id="tab-user-guide" class="tab-content">
					
					<?php echo $user_guide; ?>
	
				</div> <!-- end tab user guide -->
				

<!-- TAB LICENSE  -->				
				<div id="tab-license" class="tab-content">
				
					<h2 style="text-align:center">License Agreement</h2>
					<p class="center" style="color:red; margin: 30px 0"><b>Before using this extension, please read carefully the following End-User License Agreement.</b></p>

					
					<div id="license">
					<?php 
					$license_file = file_get_contents(DIR_APPLICATION.'advsmrtsrch-license.html');
					echo $license_file;
					?>
					</div>
				</div>   <!-- end tab license -->

				<input type="hidden" name="adsmart_search_first_boot"  value="0" />	
			</form>
			
		</div> <!-- end .content -->	
		
		<div class="message-bottom">
			<?php if ($error_warning) { ?> <div class="warning"><?php echo $error_warning; ?></div><?php } ?>
			<?php if (isset($_GET['saved']) && $_GET['saved'] == 1){ ?><div class="success"><span class="success-icon"></span><?php echo $text_save_success; ?></div><?php } ?>
		</div>
		
	</div> <!-- end box -->
</div> <!-- end #content -->

<div style="clear:both"></div>

<?php echo $footer; ?>