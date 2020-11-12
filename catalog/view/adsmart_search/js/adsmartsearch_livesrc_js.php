<?php
// ***************************************************
//               Advanced Smart Search   
//       
// Author : Francesco Pisanò - francesco1279@gmail.com
//              
//                   www.leverod.com		
//               © All rights reserved	  
// ***************************************************


// Live Search Javascript

// Note:
// Some code is wrapped berween the "if ($is_admin) {",
// it is only necessary for the Live Search loaded from the back end.
// The constant DIR_CATALOG is defined in the back end only.
header("Content-Type:application/javascript; charset:UTF-8"); 

// Journal 3 PATCH
// Journal 3 appends its version info to the array $_GET, so the last array element contains wrong data. Remove that garbage:

	end($_GET);    				    // move the internal pointer to the end of the array
	$last_array_key = key($_GET);	// fetches the key of the element pointed to by the internal pointer

	$messy_var = explode("?v=", $_GET[$last_array_key], 2);
	$_GET[$last_array_key] = $messy_var[0];
// End Journal 3 patch
	

extract($_GET);

/*
$is_admin						= isset($is_admin)?							$is_admin						: $adsmart_search_settings['is_admin']; 
$website_url					= isset($website_url)?						$website_url					: $adsmart_search_settings['website_url']; 

$dropdown_text_color			= isset($dropdown_text_color)?				$dropdown_text_color			: $adsmart_search_settings['dropdown_text_color']; 
$dropdown_update_on_entire_word	= isset($dropdown_update_on_entire_word)?	$dropdown_update_on_entire_word	: $adsmart_search_settings['dropdown_update_on_entire_word']; 
$dropdown_display_img			= isset($dropdown_display_img)?				$dropdown_display_img			: $adsmart_search_settings['dropdown_display_img']; 
$dropdown_show_all				= isset($dropdown_show_all)?				$dropdown_show_all				: $adsmart_search_settings['dropdown_show_all']; 
$dropdown_no_results			= isset($dropdown_no_results)?				$dropdown_no_results			: $adsmart_search_settings['dropdown_no_results']; 
$dropdown_width					= isset($dropdown_width)?					$dropdown_width					: $adsmart_search_settings['dropdown_width']; 
*/

?>



<?php

// append the variable "description" (or "filter_description" for OC < 1.5.5 ) to the url if the search includes the field "description"

$flag_filter_description = '';

if ( !empty($description_relevance) ) {

	// for Oc => 1.5.5
	if (version_compare($version, '1.5.5', '>=')) {
		$flag_filter_description = '&description=true';
	}
	// for Oc < 1.5.5
	else {
	
		$flag_filter_description = '&filter_description=true';
	}
}


// Get the sort order:
$sort_order = explode('-', $sort_order);
$sort		= isset($sort_order[0]) ? '&sort=' . $sort_order[0] : '';
$order		= isset($sort_order[1]) ? '&order=' . $sort_order[1] : '';
?>




<?php // This Code controls clicks and the Enter key for the SEARCH MODULE and the default Opencart search field: ?>


function build_search_url(input_field) {

	var url = $('base').attr('href') + 'index.php?route=product/search';
	 
	var search = input_field.val();

	if (search) {
		url += '&search=' + encodeURIComponent(search) + '<?php echo $flag_filter_description . $sort . $order ?>';
		
		location = url;
	}
}

$(document).ready(function() {

	<?php 
	
	// Steps to prevent firing the event bind('click') in common.js:
	
	// 	1) disable the event click (see Opencart file common.js)

	//	2) bind the event click, and then

	//	3) quickly unbind it to prevent firing the bind('click') in common.js

	?>		

	
	/* Search */
	
	<?php // Search - Default themes for Oc <= 1.5.6.4 ?>
	
	// 1)
	$('.button-search').unbind('click'); 
	
	// 2)	
		$('.button-search').bind('click').on('click', function(e) {	 	
	
	// 3)		
		$(this).unbind('click'); 
		
		var input_field = $(this).parent().find('input[name="search"], input[name="filter_search"]');
		build_search_url(input_field);
	});
	

	<?php // Search - Default theme for Oc  >= 2+ ?>	
	
	// 1)
		$('#search input[name=\'search\']').parent().find('button').off('click').unbind('click');
	
	// 2)
		$('#search input[name=\'search\']').parent().find('button').on('click', function() {
	
	// 3)
		$(this).off('click').unbind('click'); <?php // disable the event click, Opencart file common.js ?>
	
		var input_field = $('#search input[name=\'search\']');
		build_search_url(input_field);			
	});

	<?php // Search - Advanced Smart Search custom module ?>
	
	$('.search_btn').on('click', function(e) {				

		var input_field = $(this).parent().find('input[name="adsmart_search"]');
		build_search_url(input_field);
	});

	
	$('input[name="adsmart_search"],input[name="search"], input[name="filter_search"]').on('keydown', function(e) {
		
		if (e.keyCode == 13) {
			build_search_url($(this));
		}		
	});

});


				

function isTouchSupported() {
	return (window.navigator.msMaxTouchPoints || ("ontouchstart" in document.documentElement) )? true: false;
}



<?php // js spinner ?>

!function(a,b){"object"==typeof exports?module.exports=b():"function"==typeof define&&define.amd?define(b):a.Spinner=b()}(this,function(){"use strict";function a(a,b){var c,d=document.createElement(a||"div");for(c in b)d[c]=b[c];return d}function b(a){for(var b=1,c=arguments.length;c>b;b++)a.appendChild(arguments[b]);return a}function c(a,b,c,d){var e=["opacity",b,~~(100*a),c,d].join("-"),f=.01+c/d*100,g=Math.max(1-(1-a)/b*(100-f),a),h=j.substring(0,j.indexOf("Animation")).toLowerCase(),i=h&&"-"+h+"-"||"";return l[e]||(m.insertRule("@"+i+"keyframes "+e+"{0%{opacity:"+g+"}"+f+"%{opacity:"+a+"}"+(f+.01)+"%{opacity:1}"+(f+b)%100+"%{opacity:"+a+"}100%{opacity:"+g+"}}",m.cssRules.length),l[e]=1),e}function d(a,b){var c,d,e=a.style;for(b=b.charAt(0).toUpperCase()+b.slice(1),d=0;d<k.length;d++)if(c=k[d]+b,void 0!==e[c])return c;return void 0!==e[b]?b:void 0}function e(a,b){for(var c in b)a.style[d(a,c)||c]=b[c];return a}function f(a){for(var b=1;b<arguments.length;b++){var c=arguments[b];for(var d in c)void 0===a[d]&&(a[d]=c[d])}return a}function g(a,b){return"string"==typeof a?a:a[b%a.length]}function h(a){this.opts=f(a||{},h.defaults,n)}function i(){function c(b,c){return a("<"+b+' xmlns="urn:schemas-microsoft.com:vml" class="spin-vml">',c)}m.addRule(".spin-vml","behavior:url(#default#VML)"),h.prototype.lines=function(a,d){function f(){return e(c("group",{coordsize:k+" "+k,coordorigin:-j+" "+-j}),{width:k,height:k})}function h(a,h,i){b(m,b(e(f(),{rotation:360/d.lines*a+"deg",left:~~h}),b(e(c("roundrect",{arcsize:d.corners}),{width:j,height:d.width,left:d.radius,top:-d.width>>1,filter:i}),c("fill",{color:g(d.color,a),opacity:d.opacity}),c("stroke",{opacity:0}))))}var i,j=d.length+d.width,k=2*j,l=2*-(d.width+d.length)+"px",m=e(f(),{position:"absolute",top:l,left:l});if(d.shadow)for(i=1;i<=d.lines;i++)h(i,-2,"progid:DXImageTransform.Microsoft.Blur(pixelradius=2,makeshadow=1,shadowopacity=.3)");for(i=1;i<=d.lines;i++)h(i);return b(a,m)},h.prototype.opacity=function(a,b,c,d){var e=a.firstChild;d=d.shadow&&d.lines||0,e&&b+d<e.childNodes.length&&(e=e.childNodes[b+d],e=e&&e.firstChild,e=e&&e.firstChild,e&&(e.opacity=c))}}var j,k=["webkit","Moz","ms","O"],l={},m=function(){var c=a("style",{type:"text/css"});return b(document.getElementsByTagName("head")[0],c),c.sheet||c.styleSheet}(),n={lines:12,length:7,width:5,radius:10,rotate:0,corners:1,color:"#000",direction:1,speed:1,trail:100,opacity:.25,fps:20,zIndex:2e9,className:"spinner",top:"50%",left:"50%",position:"absolute"};h.defaults={},f(h.prototype,{spin:function(b){this.stop();{var c=this,d=c.opts,f=c.el=e(a(0,{className:d.className}),{position:d.position,width:0,zIndex:d.zIndex});d.radius+d.length+d.width}if(e(f,{left:d.left,top:d.top}),b&&b.insertBefore(f,b.firstChild||null),f.setAttribute("role","progressbar"),c.lines(f,c.opts),!j){var g,h=0,i=(d.lines-1)*(1-d.direction)/2,k=d.fps,l=k/d.speed,m=(1-d.opacity)/(l*d.trail/100),n=l/d.lines;!function o(){h++;for(var a=0;a<d.lines;a++)g=Math.max(1-(h+(d.lines-a)*n)%l*m,d.opacity),c.opacity(f,a*d.direction+i,g,d);c.timeout=c.el&&setTimeout(o,~~(1e3/k))}()}return c},stop:function(){var a=this.el;return a&&(clearTimeout(this.timeout),a.parentNode&&a.parentNode.removeChild(a),this.el=void 0),this},lines:function(d,f){function h(b,c){return e(a(),{position:"absolute",width:f.length+f.width+"px",height:f.width+"px",background:b,boxShadow:c,transformOrigin:"left",transform:"rotate("+~~(360/f.lines*k+f.rotate)+"deg) translate("+f.radius+"px,0)",borderRadius:(f.corners*f.width>>1)+"px"})}for(var i,k=0,l=(f.lines-1)*(1-f.direction)/2;k<f.lines;k++)i=e(a(),{position:"absolute",top:1+~(f.width/2)+"px",transform:f.hwaccel?"translate3d(0,0,0)":"",opacity:f.opacity,animation:j&&c(f.opacity,f.trail,l+k*f.direction,f.lines)+" "+1/f.speed+"s linear infinite"}),f.shadow&&b(i,e(h("#000","0 0 4px #000"),{top:"2px"})),b(d,b(i,h(g(f.color,k),"0 0 1px rgba(0,0,0,.1)")));return d},opacity:function(a,b,c){b<a.childNodes.length&&(a.childNodes[b].style.opacity=c)}});var o=e(a("group"),{behavior:"url(#default#VML)"});return!d(o,"transform")&&o.adj?i():j=d(o,"animation"),h});
$(document).ready( function(){					
	spin_options = {lines: 9, length: 4, width: 2, radius: 3, rotate: 90,
	color: '#<?php echo $dropdown_text_color; ?>',
	speed: 2,trail: 45, shadow: false, hwaccel: false, className: 'spinner', zIndex: 9999999, /* left: 'auto' */ top: '14px' };
	spinner = new Spinner(spin_options);
});




<?php // Scrollbar ?>

;(function (factory)
{
	if (typeof define === 'function' && define.amd)
	{
		define(['jquery'], factory);
	}
	else if (typeof exports === 'object')
	{
		factory(require('jquery'));
	}
	else
	{
		factory(jQuery);
	}
}
(function ($)
{
	"use strict";

	var pluginName = "adsmart_scroll"
	,   defaults   =
		{
			axis         : 'y'    <?php // Vertical or horizontal scrollbar? ( x || y ). ?>
		,   wheel        : true   <?php // Enable or disable the mousewheel; ?>
		,   wheelSpeed   : 40     <?php // How many pixels must the mouswheel scroll at a time. ?>
		,   wheelLock    : true   <?php // Lock default scrolling window when there is no more content. ?>
		,   scrollInvert : false  <?php // Enable invert style scrolling ?>
		,   trackSize    : false  <?php // Set the size of the scrollbar to auto or a fixed number. ?>
		,   thumbSize    : false  <?php // Set the size of the thumb to auto or a fixed number ?>
		}
	;

	function Plugin($container, options)
	{
		this.options   = $.extend({}, defaults, options);
		this._defaults = defaults;
		this._name     = pluginName;

		$container.children('.viewport').prepend('<div class="scrollbar"><div class="track"><div class="src_lst_up">&#9650;</div><div class="thumb"></div><div class="src_lst_down">&#9660;</div></div></div>');	
				
		
		var self        = this
		,   $viewport   = $container.find(".viewport")
		,   $overview   = $container.find("ul")
		,   $scrollbar  = $container.find(".scrollbar")
		,   $track      = $scrollbar.find(".track")
		,   $thumb      = $scrollbar.find(".thumb")

		,   mousePosition  = 0

		,   isHorizontal   = this.options.axis === 'x'
		,   hasTouchEvents = ("ontouchstart" in document.documentElement)
		,   wheelEvent     = ("onwheel" in document || document.documentMode >= 9) ? "wheel" :
							 (document.onmousewheel !== undefined ? "mousewheel" : "DOMMouseScroll")

		,   sizeLabel  = isHorizontal ? "width" : "height"
		,	widthLabel = !isHorizontal ? "width" : "height"
		,   posiLabel  = isHorizontal ? "left" : "top"
		;

		this.contentPosition = 0;
		this.viewportSize    = 0;
		this.contentSize     = 0;
		this.contentRatio    = 0;
		this.trackSize       = 0;
		this.trackRatio      = 0;
		this.thumbSize       = 0;
		this.thumbPosition   = 0;
		
		this.scrollbarWidth = 15;
		
		this.currentPos		= 0;
		this.timeout_id		= 0;
		

		function initialize()
		{
			self.update();
			setEvents();
			self.update('relative'); // update again to fix a height problem with the 
									 // content, this.contentSize height in some cases (when product names 
									 // didn't fit in one line) was smaller than the actual height and products
									 // on bottom failed to display in the viewport
			return self;
		}

		this.update = function(scrollTo)
		{
			var sizeLabelCap  = sizeLabel.charAt(0).toUpperCase() + sizeLabel.slice(1).toLowerCase();

			this.viewportSize = $viewport[0]['offset'+ sizeLabelCap];
			this.contentSize  = $overview[0]['scroll'+ sizeLabelCap];
		
			this.contentRatio = this.viewportSize / this.contentSize;
			this.trackSize    = this.options.trackSize || this.viewportSize;
			
			if ( this.trackSize > 100 ){
				this.thumbSize    = Math.min(this.trackSize, Math.max(0, (this.options.thumbSize || (this.trackSize * this.contentRatio))));
			} else {
				this.thumbSize = 40;
				this.options.thumbSize = this.thumbSize;
			}
		
			this.trackRatio   = this.options.thumbSize ? (this.contentSize - this.viewportSize) / (this.trackSize - this.thumbSize) : (this.contentSize / this.trackSize);
			this.trackRatio   = this.trackRatio;


			mousePosition     = $track.offset().top;

			$scrollbar.toggleClass("disable", this.contentRatio >= 1);
			
			switch (scrollTo)  {
			
				case "bottom":
					this.contentPosition = this.contentSize - this.viewportSize;
					break;

				case "relative":
					this.contentPosition = Math.min(Math.max(this.contentSize - this.viewportSize, 0), Math.max(0, this.contentPosition));
					break;

				default:
					this.contentPosition = parseInt(scrollTo, 10) || 0;
			}

			setSize();
			
			if ( !$scrollbar.hasClass('disable')){

				$overview.css('padding-right',(this.scrollbarWidth+5) + 'px' );
			}

			return self;
		};

		function setSize()
		{
			$thumb.css(posiLabel, self.contentPosition / self.trackRatio);
			$overview.css(posiLabel, -self.contentPosition);
			$scrollbar.css(sizeLabel, self.trackSize);
			$scrollbar.css(widthLabel, self.scrollbarWidth);
			$track.css(sizeLabel, self.trackSize);
			$thumb.css(sizeLabel, self.thumbSize);
		}

		
		function setEvents()
		{
						
			if(hasTouchEvents)
			{
			
				$viewport[0].ontouchstart = function(event)
				{
					if (1 === event.touches.length)
					{
				//		event.preventDefault(); 
				//      event.returnValue = false;
						event.stopPropagation();
								
						start(event.touches[0]);
					}
				};
			}
			else
			{
				$thumb.bind("mousedown", start);
				$track.bind("mousedown", drag);
			}

			$(window).resize(function()
			{
				self.update("relative");
			});

			if(self.options.wheel && window.addEventListener)
			{
				$container[0].addEventListener(wheelEvent, wheel, false );
			}
			else if(self.options.wheel)
			{
				$container[0].onmousewheel = wheel;
			}
		}

		function start(event)
		{
			$("body").addClass("noSelect");
			
			mousePosition      = isHorizontal ? event.pageX : event.pageY;
			self.thumbPosition = parseInt($thumb.css(posiLabel), 10) || 0;

			if(hasTouchEvents)
			{
				document.ontouchmove = function(event)
				{
					event.preventDefault(); 
					
			//		event.stopPropagation();
			//		event.returnValue = false; 
					
					drag(event.touches[0]);	
				};
				document.ontouchend = end;
			}
			else
			{
				$(document).bind("mousemove", drag);
				$(document).bind("mouseup", end);
				$thumb.bind("mouseup", end);
			}
		}
		

		
		function wheel(event) {
		
			if(self.contentRatio < 1) {
			
			
				var evntObj		= event || window.event;
				var deltaDir	= "delta" + self.options.axis.toUpperCase();
				var deltaVal	= (evntObj[deltaDir] || evntObj.detail || (-1 / 3 * evntObj.wheelDelta));
				
				var deltaSign 	= deltaVal ? deltaVal < 0 ? -1 : 1 : 0;
				var wheelSpeedDelta = -(deltaSign);

				self.contentPosition -= wheelSpeedDelta * self.options.wheelSpeed;
				
				self.contentPosition = Math.min((self.contentSize - self.viewportSize), Math.max(0, self.contentPosition));

			   $container.trigger("move");
			   
				<?php
				// 	If you don't want smooth scrolling:
				//  $thumb.css(posiLabel, self.contentPosition / self.trackRatio);
				//	overview.css(posiLabel, -self.contentPosition);
				?>	
				
				scroll(deltaSign);
				
				if(self.options.wheelLock || (self.contentPosition !== (self.contentSize - self.viewportSize) && self.contentPosition !== 0) )
				{
					evntObj = $.event.fix(evntObj);
					evntObj.preventDefault();	
				}
	
			}
		}

		
		function scroll(direction) {
				
			<?php 
			// clear the previous setTimeout to avoid overlapping calls
			?>
			window.clearTimeout(self.timeout_id);

			<?php
			// Begin  of content & scroll down	  : ( self.currentPos <= 0 && direction > 0 )
			// Middle of content & scroll up/down : ( self.currentPos > 0 && (self.currentPos + self.viewportSize) < self.contentSize )
			// End    of content & scroll up	  : ( self.currentPos > 0 && ( (self.currentPos + self.viewportSize) >= self.contentSize && direction < 0 ) )
			?>
			if ( ( self.currentPos <= 0 && direction > 0 ) || ( self.currentPos > 0 && (self.currentPos + self.viewportSize) < self.contentSize )  ||  ( self.currentPos > 0 && ( (self.currentPos + self.viewportSize) >= self.contentSize && direction < 0 ) ) ) { 
			
				<?php 
				// ref: http://scottiestech.info/2014/07/01/javascript-fun-looping-with-a-delay/ 
				?>
				(function theLoop () {
					<?php
					// When we scroll down, before to reach the end of the document:  
					// (self.currentPos + self.viewportSize) < self.contentSize 
					?>
					self.currentPos += direction * 4;

					self.timeout_id = setTimeout(function () {

						$('.timeout_id').html('timeout_id: ' + self.timeout_id);
						<?php
						// self.currentPos and self.contentPosition could become floating point numbers in some cases
						// Convert numbers to integers before comparing them. See double bitwise operator ~~, it's faster than js Math functions
						?>
						if ( ( (~~self.currentPos) - (~~self.contentPosition ) <0 && direction > 0 ) || ( (~~self.currentPos) - (~~self.contentPosition ) >0 && direction < 0 ) )  { 

						
							$overview.css( posiLabel, -self.currentPos );
							$thumb.css( posiLabel, self.currentPos / self.trackRatio  );

							theLoop(); 
						
						}
						else {
							self.currentPos = self.contentPosition;
						}
													
					}, 1); <?php // scrolling speed ?>

				})();
			}
		}
		

		function drag(event) {

			if(self.contentRatio < 1) {
			
				var mousePositionNew   = isHorizontal ? event.pageX : event.pageY
				,   thumbPositionDelta = mousePositionNew - mousePosition
				;

				if(self.options.scrollInvert && hasTouchEvents)
				{
					thumbPositionDelta = mousePosition - mousePositionNew;
				}

				var thumbPositionNew = Math.min((self.trackSize - self.thumbSize), Math.max(0, self.thumbPosition + thumbPositionDelta));  
				self.contentPosition = thumbPositionNew * self.trackRatio;
			
				self.currentPos = self.contentPosition;
				
				$container.trigger("move");

				$thumb.css(posiLabel, thumbPositionNew);
				$overview.css(posiLabel, -self.contentPosition);
							
			}	
		}

		function end()
		{
			$("body").removeClass("noSelect");
			$(document).unbind("mousemove", drag);
			$(document).unbind("mouseup", end);
			$thumb.unbind("mouseup", end);
			document.ontouchmove = document.ontouchend = null;
		}
		return initialize();
	}

	$.fn[pluginName] = function(options)
	{
		return this.each(function()
		{
			if(!$.data(this, "plugin_" + pluginName))
			{
				$.data(this, "plugin_" + pluginName, new Plugin($(this), options));
			}
		});
	};
}));



$(document).ready(function() {

	<?php
	// SEARCH INPUT FIELD DETECTOR 
	
	// STEP 1
	// Check if the header (id = #header) contains any search input field
	?>

	<?PHP 
		// The search input field name is "filter_name"  from Opencart <= 1.5.4.1 and is "search"  from Opencart >= 1.5.5  
		// Some templates have both the search inputs, one named "search" and the other named "filter_name" then we must
		// add an array of fields (search_inputs) and add the autocomplete to every field found.	
	?>

	search_inputs = new Array();

	var i=0;
<?php // Try 1 ?>
	search_inputs[i] = $('#header input[name="filter_name"]');
	
<?php // Try 2 ?>
	if ($(search_inputs[i]).length <= 0) { 					<?php // if the search input doesn't exists: ?>
		search_inputs[i] = $('input[name="filter_name"]');	<?php // remove the id #header to increase the chances to catch the search field ?>
	}
	
	if ($(search_inputs[i]).length > 0){ 					<?php // if the search input exists: ?>
		i=1;
	}
	else {
		i=0;
	}
	

	search_inputs[i] = $('#header input[name="search"]');
	
<?php // Try 3 ?>
	if ($(search_inputs[i]).length <= 0) { 
		search_inputs[i] = $('input[name="search"]');
	}
	
	<?php
	// if still empty, remove the array element
	?>
	
<?php // If empty del ?>					
	if ($(search_inputs[i]).length <= 0) {
		search_inputs.splice(i, 1);
	}
	

	<?php	
	
	// STEP 2		
	// The page product/search contains a search input that is usually next to
	// a select box used to choose categories. I use this select as marker to identify
	// the search input field to exclude from the autocomplete. Otherwise the ajax calls are made
	// while typing into the other input search field in the page product/search. 
	
	// Loop through the array search_inputs to find inputs to exclude:
	?>
	
	var search_inputs_length = search_inputs.length;
	for (var i=0; i < search_inputs_length; i++) {

		<?php // The category field name is "filter_category_id"  from Opencart <= 1.5.4.1 ?>
		search_field_to_exclude = $('body').find($('select[name="filter_category_id"]')).siblings(search_inputs[i]);
		
		<?php 
		// The category field name is "category_id"  from Opencart >= 1.5.5 && <= 1.5.6.4
		// For Oc 2.0+ we need to add #input-search to the matched set of elements, it is the main search field on the search page
		?>
		if ($(search_field_to_exclude).length <= 0) { 
		
			search_field_to_exclude = $('body').find($('select[name="category_id"]')).siblings($('input[name="search"]')).add('#input-search');
		}

		search_inputs[i] = search_inputs[i].not(search_field_to_exclude);
	}
	

	
	
	<?php // Add search box modules: ?>
	$('input[id^="adsmart_search"]').each(function() {
		search_inputs.push($(this));
	});
	
	
	space_or_typing = false; <?php // this flag tells whether the user is typing something or has entered a space ?>
	
	<?php
	// All the time value are in milliseconds
	?>
	typing_speed			= 250;				<?php // 500; ?>
	max_sleep_time			= 1000;				<?php // force an ajax request when this time is exceeded ?>
	keypress_elapsed_time	= 0;
	elapsed_time_for_new_keypress_sequence = 0; <?php // Counts the elapsed time from the first character typed. >>> It restarts from 0 on every ajax call.
													  // THE FO(Ajax_call)X JUMPS OV(Ajax_call)ER THE LAZY DOG 
													  // keypress sequences:  1)the fo  2)x jumps ov   3)er the lazy dog ?>
	timeout = 100;
	
	ajax_request = null;

	max_waiting_time = 3000; 	<?php 
								// in milliseconds. If the ajax calls are made after each word entered,
								// when the max waiting time is reached, the user should have stopped writing 
								// the search string. This time tells how long to wait before sending the last
								// ajax request.
								?>
								

	

	<?php if ($is_admin) { ?>
	//************************************
	// ***** Admin Live Search ONLY ******
	
	
		// Set the description flag value. Useful when the administrator clicks on the description
		// field checkbox without saving the module:
		
		if ( $('input[name="description"]').is(':checked')){
			description_flag = true;
		} 
		else {
			description_flag = false;
		}
		$('input[name="description"]').on('click',function(){
			
			if ($(this).is(':checked')){
				description_flag = true;
			}
			else {
				description_flag = false;
			}
		});
		
		

		// Set the sort and order flags:
		
		sort_order = $('select[name="adsmart_search_sort_order"]').val();
		split_sort_order = sort_order.split('-');
		sort  = split_sort_order[0];
		order =	split_sort_order[1];

		$('select[name="adsmart_search_sort_order"]').on('click', function(){
			sort_order = $(this).val();
			split_sort_order = sort_order.split('-');
			sort  = split_sort_order[0];
			order =	split_sort_order[1];
		});
		
		
		
	// ********** End Admin **************	
	//************************************
	<?php } ?>
	

	<?php					
	// Add the autocomplete to every input field found
	?>
	search_inputs_length = search_inputs.length;
	for (var i=0; i < search_inputs_length; i++) {

		search_inputs[i].attr('autocomplete','off');
	
	
		search_inputs[i].after('<div class="adsmart_container"></div>');
	
		<?php 
		//*************************************** 
		// Typing speed controller & ajax request 
		//***************************************
		
		// Work with keyup() only, keydown and keypress would not fire the first character
		// the event "input" works for Firefox for Android , because of a bug it doesn't fire the
		// events keydown, keypress and keyup. To prevent the ajax call from firing twice on some browsers
		// (one for keyup and one for input), the variable $.active (see function beforeSend() in the 
		// ajax call checks that there are not pending ajax requests
		?>
		search_inputs[i].on('keyup input', function(e) {
		
			var search_string = $(this).val();
			
			if (space_or_typing == false) {
				space_or_typing = true;
				keypress_start_time = new Date().getTime();
			}

			keypress_elapsed_time = new Date().getTime() - keypress_start_time;  // time between two consecutive key presses

			elapsed_time_for_new_keypress_sequence +=  keypress_elapsed_time;
				
			keypress_start_time = new Date().getTime();
			
			<?php if ( $dropdown_update_on_entire_word == 1 ) { ?>						

			if ( keypress_elapsed_time < max_waiting_time ) {
				timeout = max_waiting_time;
				clearTimeout(ajax_request);
			}
			
			if (keypress_elapsed_time > max_waiting_time || (search_string.substr(search_string.length - 1,1) == ' ' && search_string.substr(search_string.length - 2,1) != ' ') ){                          
				timeout = 0;
			}

			<?php  } else { ?> 
			
			<?php
			// the ajax request is sent when the time between a keypress and the subsequent one is greater than the "typing_speed"
			// and always when the "elapsed_time_for_new_keypress_sequence" exceeds the "max_sleep_time":

			
			// $('body .debug').html(keypress_elapsed_time);
			?>

			if ( keypress_elapsed_time < typing_speed  ) {
			
				if ( elapsed_time_for_new_keypress_sequence > max_sleep_time){
					timeout = 0;
					elapsed_time_for_new_keypress_sequence = 0;
				}
				else {					
					timeout = typing_speed;
					clearTimeout(ajax_request);
				}
			}
			
			<?php 
			// Don't make ajax calls when:
			// 1) the user types a space 
			// 2) The string is empty
			
			// e.which: 0 works in mozilla and 32 in other browsers
			// Detect a space by directly reading the input string with:
			// search_string.substr(search_string.length - 1,1)
			// don't rely on e.which = 0/32 because on ff for android events keypress/down/up 
			// are not correctly handled (tested only on Android 4.4.2+ff 34)
			// and e.which could be always 0 except for the enter key.

			// if last char is a space OR user typed a backspace (code=8) OR string is empty 
			?>
			if ( search_string.substr(search_string.length - 1,1) == ' ' || (search_string.substr(search_string.length - 1,1) == ' ' && e.which  == 8) || search_string.length == 0  ) {
		
				// Stop the spinner:
				spinner.stop();

				<?php // hide the search list if there is still one from a previous search ?>
				if (search_string.length == 0 && $(this).next('.adsmart_container').is(":visible") ) {
					$(this).next('.adsmart_container').hide();
				}
			}
			else 
			<?php } ?> {

				adsmart_search($(this), search_string);
			}
		});
		<?php 
		//******************************************* 
		// End Typing speed controller & ajax request 
		//*******************************************
		?>	

		
		// Set the cookie used by Search Analytics to record the current search
		// An element (the input field) need to lose focus (fires the blur) before another element (the search button, 
		// an item in the dropdown or the link "show all") can have it.
		// Use this behaviour to set the cookie with the search string as soon as the input field loses the focus.
		
		search_inputs[i].on('blur', function(e) {
		
			levSetCookie('adsmart_search_string', levBase64.encode($(this).val()), 1);
		});
		
	} // end foreach search_inputs
});

	
	


function adsmart_search(search_input, search_string) {

	currentSearchInput	= search_input; <?php // currentSearchInput has a global scope ?>
	adsmart_container		= currentSearchInput.next('.adsmart_container');

	<?php
	// Some themes set the parent element overflow to "hidden", so the dropdown is not displayed. This code
	// forces the overflow to "visible". When the dropdown is closed we restore the default value,
	// see the function close_adsmart_search(). The variable default_parent_overflow has a global scope.
	// Also increase the z-index value to avoid overlapping with other elements
	?>
	default_parent_overflow = currentSearchInput.parent().css('overflow');
	default_parent_zindex 	= currentSearchInput.parent().css('z-index');
	
	currentSearchInput.parent().css('overflow','visible');
	currentSearchInput.parent().css('z-index','9999');
	
	
	<?php
	// Append the js spinner icon
	?>
	spinner.spin();
	$('div.adsmart_loading').html(spinner.el);

	
	ajax_request = setTimeout(function() {
	
		xhr = $.ajax({

			<?php if ($is_admin) {  ?>
				//************************************
				// ***** Admin Live Search ONLY ******
				
				// the description flag name is "filter_description" for OC < 1.5.5 and "description" for OC >= 1.5.5
				url: '<?php echo $website_url ?>index.php?route=product/search/autocomplete&filter_name=' + encodeURIComponent(search_string)+'&admin_dropdown_limit='+$('input[name="adsmart_search_dropdown_max_num_results"]').val()+'&description='+description_flag+'&filter_description='+description_flag,
			
				// ********** End Admin **************	
				//************************************
			
			<?php } else { ?>
				url: '<?php echo $website_url ?>index.php?route=product/search/autocomplete&filter_name=' + encodeURIComponent(search_string),
			<?php } ?>

			dataType: 'json',

			beforeSend: function() {
				
				<?php 
				// abort the current ajax call if there are pending calls 
				// $.active counts the number of pending calls
				?>
				if ($.active > 1 ) {	
					xhr.abort();
				}
			},
			
			success: function(json) {
			
				// Stop the spinner:
				spinner.stop();
				
				$('.adsmart_loading').remove();
				
				<?php if ($debug || $debug_show_sql || $speed_test ) { ?>
					$('body .debug').remove();
					$('body').prepend('<div class="debug" style="height: 200px;overflow-y:scroll;">' + json[json.length-1]['debug'] + '</div>');

					json.splice(json.length-1, 1); // remove debug infos
				<?php } ?>
				
				
				adsmart_container.show();

				
				<?php // add the search list (empty for now) ?>
				adsmart_container.html('<div class="adsmart_search"><div class="viewport"><ul></ul></div></div>');
				
				adsmartSearchDIV 		= adsmart_container.find('.adsmart_search');

				if (json.length > 0) {

					 $.each(json, function (key, data) {
					 
						<?php
						// Array data keys:
						// product_id, image, name, model, price, special, option, rating, reviews, href		
						?>
						 
						 <?php 
						// Bugfix for the variable data['href']: 
						// When jQuery sends back the url, it encodes entities like & (it becomes &amp;)
						// Convert them to regular chars with $.html().text() ('<b></b>' is just a dummy selector)
						?>
						data['href'] = $('<b></b>').html(data['href']).text();
						 
						
						
						<?php 
						$display_img = '';
						if ($dropdown_display_img) {
							$display_img = '<div class="image"><img src="\' + data[\'image\'] + \'"></div>';
						} 
						?> 
						 
						 
						<?php // Price + Special: ?>
						var price;
						if (data['special'] != '') {
							price = '<s>'+data['price']+'</s><br />' + data['special']; 
						}
						else {
							price = data['price'];
						}
						
						<?php 
						$display_price = '';
						if ($dropdown_display_price) {
							$display_price = '<div class="price">\' + price + \'</div>';
						} 
						?>
						
						
						<?php if ( $is_admin ) {  ?>
						//************************************
						// ***** Admin Live Search ONLY ******
						
							<?php 
							$display_img = '<div class="image"><img src="\' + data[\'image\'] + \'"></div>';
							$display_price = '<div class="price">\' + price + \'</div>';
							?>
					
						// ********** End Admin **************	
						//************************************
						<?php } ?>
						adsmartSearchDIV.find('ul').append('<li class="menu_item"><a class="item_link" href="'+data['href']+'"><?php echo $display_img ?><div class="name">'+data['name']+'</div><?php echo $display_price ?></a></li>');
					});

					 
					if ( adsmart_container.find('.no_results').length == 0 ){
					
						adsmartSearchDIV.prepend($('<div class="adsmart_info_msg" ><div class="adsmart_loading" ></div></div>'));

					
						var text = "<?php echo $dropdown_show_all ?>";

						<?php if ($is_admin) {  ?>
						//************************************
						// ***** Admin Live Search ONLY ******
						
						// the description flag name is "filter_description" for OC < 1.5.5 and "description" for OC >= 1.5.5
					
						adsmartSearchDIV.append('<div class="show_all_results"><a onclick="javascript:show_all_results_href = this.href" href="<?php echo $website_url ?>index.php?route=product/search&search=' + encodeURIComponent(search_string) + '&description='+description_flag + '&filter_description='+description_flag + '&sort='+sort + '&order'+order + '">' + text + '</a></div>');					

						// ********** End Admin **************	
						//************************************
					
						<?php } else { ?>
						
							adsmartSearchDIV.append('<div class="show_all_results"><a onclick="javascript:show_all_results_href = this.href" href="<?php  echo $website_url ?>index.php?route=product/search&search=' + encodeURIComponent(search_string) + '<?php echo $flag_filter_description . $sort . $order ?>">' + text + '</a></div>');					

						<?php } ?>
					}
					
					

					<?php 
						// Class lastfocus - see  the template file (admin) adsmart_search.tpl, 
						// js function set_dropdown_style_and_hidden_input_fields()
					?>
					$('.adsmart_search li.menu_item').mouseover(function() {
						$('.adsmart_search li').removeClass('lastfocus');
						$('.adsmart_search li:not(.show_all_results)').css('background','transparent');
						$(this).addClass('lastfocus');
						
						
						<?php if ($is_admin) { ?>
						//************************************
						// ***** Admin Live Search ONLY ******

						$(this, this + ' a:hover').css('background-color', '#'+$('input[name=adsmart_search_dropdown_hover_bg_color]').val() );							

						// ********** End Admin **************	
						//************************************
						<?php } ?>
					});
				}

				<?php // if no items found, display the message "No results" ?>
				else {
				
					var text = "<?php echo $dropdown_no_results ?>";

					adsmartSearchDIV.append('<div class="adsmart_info_msg no_results">' + text + '</div>' );
				}
				

				<?php // Control the dropdown closing ?>

				mouse_inside = false;
				adsmartSearchDIV.mouseenter(function(){
				
					clearTimeout($(this).data('timeout_id'));
					$(this).fadeTo( "fast" , 1.0);
					mouse_inside = true;
				});
				
				
				adsmartSearchDIV.parent().prev().mouseover(function(){ <?php // input field ?>	
					adsmartSearchDIV.fadeTo( "fast" , 1.0, function(){
						clearTimeout(adsmartSearchDIV.data('timeout_id'));
					});
				});
				

				adsmartSearchDIV.mouseleave(function(){
				
					mouse_inside = false;
				
					var that = $(this),
						timeout_id = setTimeout(function(){
							that.fadeTo( "fast" , 0.85);
							
						}, 2000);
					<?php //set the timeout_id, allowing us to clear this trigger if the mouse comes back over ?>
					that.data('timeout_id', timeout_id); 
				});
				
	
				
				
				$('body').mousedown( function() {					
					
					<?php if ($is_admin) {  ?>
					//************************************
					// ***** Admin Live Search ONLY ******
					
					// hide the dropdown list if it is visible and the checkbox "keep dropdown open" is unchecked
				
					if ( $('input[name="keep_dropdown_open"]').prop('checked') == false && mouse_inside == false ){
					
						adsmart_container.hide();
					}
					
					// ********** End Admin **************	
					//************************************

				<?php } else { ?>
					
					if (mouse_inside == false) {

						close_adsmart_search(currentSearchInput);
					}	
				<?php }  ?>
						
				});
				

				<?php
				// Resize the result list. This task must be done before adding the scrollbar to avoiding
				// a miscalculation of the viewport height.
				?>
				set_dropdown_width(currentSearchInput);
				

				<?php 
					 // Set the dropdown height  
					 // actual height = dropdown list + extra height
					 // extra height  = info message on top + show all results on bottom
				?>
				
				var viewportULHeight		= adsmartSearchDIV.find('ul').outerHeight(true);
				var infoMsgShowAllHeight	= adsmartSearchDIV.find('.adsmart_info_msg').outerHeight() + adsmartSearchDIV.find('.show_all_results').outerHeight();
				var actualHeight 			= viewportULHeight + infoMsgShowAllHeight ;
				
				
				if ( isTouchSupported() ||  $(window).width() < 768 ) {
					adsmartSearchDIV.removeClass('scroll');	
				}
				else {
				
					if ( adsmartSearchDIV.offset().top + actualHeight > $(window).height() ) {
					
						var h1 = $(window).height() - adsmartSearchDIV.offset().top + $('html, body').scrollTop() - infoMsgShowAllHeight;
						
						<?php 
						// offset().top is relative to the document, so when on some templates the search input is fixed on top and the user scrolls down the page,
						// offset().top returns a value greater than the actual input position. h1 works good for absolute/relatve/static positioning of search inputs, 
						// h2 is for fixed input fields.
						?>
						if (h1 > 40) { <?php // Let's say that the minimum acceptable viewport height is 40px ?>
					
							adsmartSearchDIV.find('.viewport').height( Math.min(h1, viewportULHeight));	
						} else {
							adsmartSearchDIV.find('.viewport').height( viewportULHeight );
						}	

						<?php // Append the scrollbar ?>
						adsmartSearchDIV.adsmart_scroll();	
					}		
					else {					
						adsmartSearchDIV.find('.viewport').height( viewportULHeight );
					}
					
					<?php	
					// The class .scroll doesn't change the way the scrollbar is displayed, it tells whether the scrollbar is allowed in the dropdown.
					// The scrollbar will be displayed depending on the dropdown height and the available space on the page. 
					?>
					adsmartSearchDIV.addClass('scroll');
				}
				
				
				<?php 
				// Position the dropdown under the search input 
				// This function must be called only after all elements (list, scrollbar ecc.) have been created
				// and after resizing the dropdown width (see set_dropdown_width());
				?>
				set_dropdown_position(currentSearchInput);
				

				<?php if ($is_admin) { ?>
				//************************************
				// ***** Admin Live Search ONLY ******
				
				
				// Keep open the dropdown list when the checkbox "keep dropdown open" is checked	
				$('input[name="keep_dropdown_open"]').click(function() {
					if ( $('input[name="keep_dropdown_open"]').prop('checked') == true){
					
						adsmart_container.show();
					}
				});

				// apply the styles to the test search input:
				set_dropdown_style_and_hidden_input_fields();
				

				// ********** End Admin **************	
				//************************************
				<?php } ?>
						
			}
		});	
	
		space_or_typing = false; 
		
	}, timeout);
}


function close_adsmart_search(search_input) {

	<?php 
	// Restore old overflow and z-index:
	?>
	if ( typeof default_parent_overflow !== 'undefined' ) {
		search_input.parent().css('overflow', default_parent_overflow);
	}
	if ( typeof default_parent_zindex !== 'undefined' ) {
		search_input.parent().css('z-index', default_parent_zindex);
	}	
																																										
	currentSearchInput.next('.adsmart_container').css('display', 'none');		
}


function set_dropdown_position(search_input) {
	<?php 
	// Position the dropdown: 

	// If the search input has the float property or absolute position, the search results will be displayed
	// over it, hiding the field. We calculate the new dropdown top with
	// $(this).outerHeight(false) (input field outer height, borders + padding) and
	// $(this).position().top
	
	// If the search input has a relative or absolute position, the search result is displayed in the position
	// where the search input should be without the relative coordinates. We adjust the search 
	// result box coordinates (only the top) by adding $(this).position().top
	?>
	
	<?php 
	// Vertical Position:  
	?>

	var dropdown_top = 2;
	
	if ( search_input.css('float') == 'left' || search_input.css('float') == 'right' || search_input.css('position') == "absolute" ){
		dropdown_top += search_input.outerHeight(false);
	}
	
	if ( (search_input.css('position') == "relative" || search_input.css('position') == "absolute") && search_input.css('top')  ){
		dropdown_top += search_input.position().top;
	}

	search_input.next('.adsmart_container').css({ top:dropdown_top});


	<?php
	// Horizontal Position:     Left <-- O --> Right
	
	// choose the position where to display the list (left or right) according to
	// the dropdown list width and the amount of available space on the window
	// -15 is to take into account the scrollbar that in some browsers overlaps the dropdown scrollbar
	
	// dropdown (.adsmart_search) can have the position property set on "absolute" or "relative", depending
	// on the current display width and on the device capabilities (touchscreen or not, see the line:
	// if ( isTouchSupported() ||  $(window).width() < 768 ) { ) 
	
	// If position:absolute we can use the properties left and right to position the dropdown
	?>
	
	var dropdown = search_input.next('.adsmart_container').children('.adsmart_search');
	var scrollbar_width = ($('.scrollbar').width())?  $('.scrollbar').width() : 0;
	var dropdown_width = dropdown.width() + scrollbar_width;

	
	<?php	// window width             <----------------------->
			// search input offset left ─────┐___________
			// search input width            │_ip________│<-----> extra width
			// dropdown width                ┌──────────────────┐
			//                               │ ipod        $150 │
			//                               │ ipad        $250 │            
	?>
	
	<?php // extra_width > 0 tells whether the dropdown list is wider than the input field: ?>
	var extra_width = dropdown.width() - search_input.width();
	extra_width = (extra_width > 0)? extra_width : 0;


	<?php // if the list width doesn't exceed the window right margin: ?>
	if ( $(window).width() - (search_input.offset().left + dropdown_width) > 0 ){
		dropdown.css('left','0px').css('right', 'auto');
	}
	else{
		if (dropdown.css('position') == 'absolute'){
			dropdown.css('left','auto').css('right', '0px');
		}
		
		if (dropdown.css('position') == 'relative'){
			dropdown.css('left','auto').css('right', extra_width + 'px');	
		}
	}
}

	
function set_dropdown_width(search_input) {

	var adsmart_container	= search_input.next('.adsmart_container');
	var	adsmartSearchDIV 	= adsmart_container.find('.adsmart_search');
	
	<?php 
	// On low res devices, responsive themes usually extend the search input field width to
	// the window width, in that case .adsmart_container would be larger than the dropdown
	// list which can be max 500px large.
	// Force the result list to have the same container width (which has the same input width)
	?>

	if ( $(window).width() < 768  && adsmart_container.width() > adsmartSearchDIV.width() ) {	
		adsmartSearchDIV.css('width', 'auto');	
	}
	else {
		adsmartSearchDIV.css('width', '<?php echo $dropdown_width; ?>px');
	}
}	


