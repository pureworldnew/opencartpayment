// Plugins

	// Js spinner
	//
	!function(a,b){"object"==typeof exports?module.exports=b():"function"==typeof define&&define.amd?define(b):a.Spinner=b()}(this,function(){"use strict";function a(a,b){var c,d=document.createElement(a||"div");for(c in b)d[c]=b[c];return d}function b(a){for(var b=1,c=arguments.length;c>b;b++)a.appendChild(arguments[b]);return a}function c(a,b,c,d){var e=["opacity",b,~~(100*a),c,d].join("-"),f=.01+c/d*100,g=Math.max(1-(1-a)/b*(100-f),a),h=j.substring(0,j.indexOf("Animation")).toLowerCase(),i=h&&"-"+h+"-"||"";return l[e]||(m.insertRule("@"+i+"keyframes "+e+"{0%{opacity:"+g+"}"+f+"%{opacity:"+a+"}"+(f+.01)+"%{opacity:1}"+(f+b)%100+"%{opacity:"+a+"}100%{opacity:"+g+"}}",m.cssRules.length),l[e]=1),e}function d(a,b){var c,d,e=a.style;for(b=b.charAt(0).toUpperCase()+b.slice(1),d=0;d<k.length;d++)if(c=k[d]+b,void 0!==e[c])return c;return void 0!==e[b]?b:void 0}function e(a,b){for(var c in b)a.style[d(a,c)||c]=b[c];return a}function f(a){for(var b=1;b<arguments.length;b++){var c=arguments[b];for(var d in c)void 0===a[d]&&(a[d]=c[d])}return a}function g(a,b){return"string"==typeof a?a:a[b%a.length]}function h(a){this.opts=f(a||{},h.defaults,n)}function i(){function c(b,c){return a("<"+b+' xmlns="urn:schemas-microsoft.com:vml" class="spin-vml">',c)}m.addRule(".spin-vml","behavior:url(#default#VML)"),h.prototype.lines=function(a,d){function f(){return e(c("group",{coordsize:k+" "+k,coordorigin:-j+" "+-j}),{width:k,height:k})}function h(a,h,i){b(m,b(e(f(),{rotation:360/d.lines*a+"deg",left:~~h}),b(e(c("roundrect",{arcsize:d.corners}),{width:j,height:d.width,left:d.radius,top:-d.width>>1,filter:i}),c("fill",{color:g(d.color,a),opacity:d.opacity}),c("stroke",{opacity:0}))))}var i,j=d.length+d.width,k=2*j,l=2*-(d.width+d.length)+"px",m=e(f(),{position:"absolute",top:l,left:l});if(d.shadow)for(i=1;i<=d.lines;i++)h(i,-2,"progid:DXImageTransform.Microsoft.Blur(pixelradius=2,makeshadow=1,shadowopacity=.3)");for(i=1;i<=d.lines;i++)h(i);return b(a,m)},h.prototype.opacity=function(a,b,c,d){var e=a.firstChild;d=d.shadow&&d.lines||0,e&&b+d<e.childNodes.length&&(e=e.childNodes[b+d],e=e&&e.firstChild,e=e&&e.firstChild,e&&(e.opacity=c))}}var j,k=["webkit","Moz","ms","O"],l={},m=function(){var c=a("style",{type:"text/css"});return b(document.getElementsByTagName("head")[0],c),c.sheet||c.styleSheet}(),n={lines:12,length:7,width:5,radius:10,rotate:0,corners:1,color:"#000",direction:1,speed:1,trail:100,opacity:.25,fps:20,zIndex:2e9,className:"spinner",top:"50%",left:"50%",position:"absolute"};h.defaults={},f(h.prototype,{spin:function(b){this.stop();{var c=this,d=c.opts,f=c.el=e(a(0,{className:d.className}),{position:d.position,width:0,zIndex:d.zIndex});d.radius+d.length+d.width}if(e(f,{left:d.left,top:d.top}),b&&b.insertBefore(f,b.firstChild||null),f.setAttribute("role","progressbar"),c.lines(f,c.opts),!j){var g,h=0,i=(d.lines-1)*(1-d.direction)/2,k=d.fps,l=k/d.speed,m=(1-d.opacity)/(l*d.trail/100),n=l/d.lines;!function o(){h++;for(var a=0;a<d.lines;a++)g=Math.max(1-(h+(d.lines-a)*n)%l*m,d.opacity),c.opacity(f,a*d.direction+i,g,d);c.timeout=c.el&&setTimeout(o,~~(1e3/k))}()}return c},stop:function(){var a=this.el;return a&&(clearTimeout(this.timeout),a.parentNode&&a.parentNode.removeChild(a),this.el=void 0),this},lines:function(d,f){function h(b,c){return e(a(),{position:"absolute",width:f.length+f.width+"px",height:f.width+"px",background:b,boxShadow:c,transformOrigin:"left",transform:"rotate("+~~(360/f.lines*k+f.rotate)+"deg) translate("+f.radius+"px,0)",borderRadius:(f.corners*f.width>>1)+"px"})}for(var i,k=0,l=(f.lines-1)*(1-f.direction)/2;k<f.lines;k++)i=e(a(),{position:"absolute",top:1+~(f.width/2)+"px",transform:f.hwaccel?"translate3d(0,0,0)":"",opacity:f.opacity,animation:j&&c(f.opacity,f.trail,l+k*f.direction,f.lines)+" "+1/f.speed+"s linear infinite"}),f.shadow&&b(i,e(h("#000","0 0 4px #000"),{top:"2px"})),b(d,b(i,h(g(f.color,k),"0 0 1px rgba(0,0,0,.1)")));return d},opacity:function(a,b,c){b<a.childNodes.length&&(a.childNodes[b].style.opacity=c)}});var o=e(a("group"),{behavior:"url(#default#VML)"});return!d(o,"transform")&&o.adj?i():j=d(o,"animation"),h});

	// jQuery hashchange event - v1.3 - 7/21/2010 // rev. 9 dec 2014, fixed $.browser.msie issue
	// http://benalman.com/projects/jquery-hashchange-plugin/
	//
	(function($,e,b){var c="hashchange",h=document,f,g=$.event.special,i=h.documentMode,d="on"+c in e&&(i===b||i>7);function a(j){j=j||location.href;return"#"+j.replace(/^[^#]*#?(.*)$/,"$1")}$.fn[c]=function(j){return j?this.bind(c,j):this.trigger(c)};$.fn[c].delay=50;g[c]=$.extend(g[c],{setup:function(){if(d){return false}$(f.start)},teardown:function(){if(d){return false}$(f.stop)}});f=(function(){var j={},p,m=a(),k=function(q){return q},l=k,o=k;j.start=function(){p||n()};j.stop=function(){p&&clearTimeout(p);p=b};function n(){var r=a(),q=o(m);if(r!==m){l(m=r,q);$(e).trigger(c)}else{if(q!==m){location.href=location.href.replace(/#.*/,"")+q}}p=setTimeout(n,$.fn[c].delay)}(navigator.appVersion.indexOf("MSIE") !== -1)&&!d&&(function(){var q,r;j.start=function(){if(!q){r=$.fn[c].src;r=r&&r+a();q=$('<iframe tabindex="-1" title="empty"/>').hide().one("load",function(){r||l(a());n()}).attr("src",r||"javascript:0").insertAfter("body")[0].contentWindow;h.onpropertychange=function(){try{if(event.propertyName==="title"){q.document.title=h.title}}catch(s){}}}};j.stop=k;o=function(){return a(q.location.href)};l=function(v,s){var u=q.document,t=$.fn[c].domain;if(v!==s){u.title=h.title;u.open();t&&u.write('<script>document.domain="'+t+'"<\/script>');u.close();q.location.hash=v}}})();return j})()})(jQuery,this);



	// jQuery UI Touch Punch 0.2.3
	// 
	//  Depends:
	//   jquery.ui.widget.js
	//   jquery.ui.mouse.js
	// 
	!function(a){function f(a,b){if(!(a.originalEvent.touches.length>1)){a.preventDefault();var c=a.originalEvent.changedTouches[0],d=document.createEvent("MouseEvents");d.initMouseEvent(b,!0,!0,window,1,c.screenX,c.screenY,c.clientX,c.clientY,!1,!1,!1,!1,0,null),a.target.dispatchEvent(d)}}if(a.support.touch="ontouchend"in document,a.support.touch){var e,b=a.ui.mouse.prototype,c=b._mouseInit,d=b._mouseDestroy;b._touchStart=function(a){var b=this;!e&&b._mouseCapture(a.originalEvent.changedTouches[0])&&(e=!0,b._touchMoved=!1,f(a,"mouseover"),f(a,"mousemove"),f(a,"mousedown"))},b._touchMove=function(a){e&&(this._touchMoved=!0,f(a,"mousemove"))},b._touchEnd=function(a){e&&(f(a,"mouseup"),f(a,"mouseout"),this._touchMoved||f(a,"click"),e=!1)},b._mouseInit=function(){var b=this;b.element.bind({touchstart:a.proxy(b,"_touchStart"),touchmove:a.proxy(b,"_touchMove"),touchend:a.proxy(b,"_touchEnd")}),c.call(b)},b._mouseDestroy=function(){var b=this;b.element.unbind({touchstart:a.proxy(b,"_touchStart"),touchmove:a.proxy(b,"_touchMove"),touchend:a.proxy(b,"_touchEnd")}),d.call(b)}}}(jQuery);	


	
	// jQuery MiniColors: A tiny color picker built on jQuery
	//
	// Copyright: Cory LaViska for A Beautiful Site, LLC
	//
	// Contributions and bug reports: https://github.com/claviska/jquery-minicolors
	//
	// @license: http://opensource.org/licenses/MIT
	//
	if(jQuery) (function($) {

		// Defaults
		$.minicolors = {
			defaults: {
				animationSpeed: 50,
				animationEasing: 'swing',
				change: null,
				changeDelay: 0,
				control: 'hue',
				dataUris: true,
				defaultValue: '',
				hide: null,
				hideSpeed: 100,
				inline: false,
				letterCase: 'lowercase',
				opacity: false,
				position: 'bottom left',
				show: null,
				showSpeed: 100,
				theme: 'default'
			}
		};

		// Public methods
		$.extend($.fn, {
			minicolors: function(method, data) {

				switch(method) {

					// Destroy the control
					case 'destroy':
						$(this).each( function() {
							destroy($(this));
						});
						return $(this);

					// Hide the color picker
					case 'hide':
						hide();
						return $(this);

					// Get/set opacity
					case 'opacity':
						// Getter
						if( data === undefined ) {
							// Getter
							return $(this).attr('data-opacity');
						} else {
							// Setter
							$(this).each( function() {
								updateFromInput($(this).attr('data-opacity', data));
							});
						}
						return $(this);

					// Get an RGB(A) object based on the current color/opacity
					case 'rgbObject':
						return rgbObject($(this), method === 'rgbaObject');

					// Get an RGB(A) string based on the current color/opacity
					case 'rgbString':
					case 'rgbaString':
						return rgbString($(this), method === 'rgbaString');

					// Get/set settings on the fly
					case 'settings':
						if( data === undefined ) {
							return $(this).data('minicolors-settings');
						} else {
							// Setter
							$(this).each( function() {
								var settings = $(this).data('minicolors-settings') || {};
								destroy($(this));
								$(this).minicolors($.extend(true, settings, data));
							});
						}
						return $(this);

					// Show the color picker
					case 'show':
						show( $(this).eq(0) );
						return $(this);

					// Get/set the hex color value
					case 'value':
						if( data === undefined ) {
							// Getter
							return $(this).val();
						} else {
							// Setter
							$(this).each( function() {
								updateFromInput($(this).val(data));
							});
						}
						return $(this);

					// Initializes the control
					default:
						if( method !== 'create' ) data = method;
						$(this).each( function() {
							init($(this), data);
						});
						return $(this);

				}

			}
		});
		

		// Initialize input elements
		function init(input, settings) {

			var minicolors = $('<div class="minicolors" />'),
				defaults = $.minicolors.defaults;

			// Do nothing if already initialized
			if( input.data('minicolors-initialized') ) return;

			// Handle settings
			settings = $.extend(true, {}, defaults, settings);

			// The wrapper
			minicolors
				.addClass('minicolors-theme-' + settings.theme)
				.toggleClass('minicolors-with-opacity', settings.opacity)
				.toggleClass('minicolors-no-data-uris', settings.dataUris !== true);

			// Custom positioning
			if( settings.position !== undefined ) {
				$.each(settings.position.split(' '), function() {
					minicolors.addClass('minicolors-position-' + this);
				});
			}

			// The input
			input
				.addClass('minicolors-input')
				.data('minicolors-initialized', false)
				.data('minicolors-settings', settings)
				.prop('size', 7)
				.wrap(minicolors)
				.after(
					'<div class="minicolors-panel minicolors-slider-' + settings.control + '">' +
						'<div class="minicolors-slider minicolors-sprite">' +
							'<div class="minicolors-picker"></div>' +
						'</div>' +
						'<div class="minicolors-opacity-slider minicolors-sprite">' +
							'<div class="minicolors-picker"></div>' +
						'</div>' +
						'<div class="minicolors-grid minicolors-sprite">' +
							'<div class="minicolors-grid-inner"></div>' +
							'<div class="minicolors-picker"><div></div></div>' +
						'</div>' +
					'</div>'
				);

			// The swatch
			if( !settings.inline ) {
			 //   input.after('<span class="minicolors-swatch minicolors-sprite"><span class="minicolors-swatch-color"></span></span>');
				input.next('.minicolors-swatch').on('click', function(event) {
					event.preventDefault();
					input.focus();
				});
			}

			// Prevent text selection in IE
			input.parent().find('.minicolors-panel').on('selectstart', function() { return false; }).end();

			// Inline controls
			if( settings.inline ) input.parent().addClass('minicolors-inline');

			updateFromInput(input, false);

			input.data('minicolors-initialized', true);

		}

		// Returns the input back to its original state
		function destroy(input) {

			var minicolors = input.parent();

			// Revert the input element
			input
				.removeData('minicolors-initialized')
				.removeData('minicolors-settings')
				.removeProp('size')
				.removeClass('minicolors-input');

			// Remove the wrap and destroy whatever remains
			minicolors.before(input).remove();

		}

		// Shows the specified dropdown panel
		function show(input) {

			var minicolors = input.parent(),
				panel = minicolors.find('.minicolors-panel'),
				settings = input.data('minicolors-settings');

			// Do nothing if uninitialized, disabled, inline, or already open
			if( !input.data('minicolors-initialized') ||
				input.prop('disabled') ||
				minicolors.hasClass('minicolors-inline') ||
				minicolors.hasClass('minicolors-focus')
			) return;

			hide();

			minicolors.addClass('minicolors-focus');
			panel
				.stop(true, true)
				.fadeIn(settings.showSpeed, function() {
					if( settings.show ) settings.show.call(input.get(0));
				});

		}

		// Hides all dropdown panels
		function hide() {

			$('.minicolors-focus').each( function() {

				var minicolors = $(this),
					input = minicolors.find('.minicolors-input'),
					panel = minicolors.find('.minicolors-panel'),
					settings = input.data('minicolors-settings');

				panel.fadeOut(settings.hideSpeed, function() {
					if( settings.hide ) settings.hide.call(input.get(0));
					minicolors.removeClass('minicolors-focus');
				});

			});
		}

		// Moves the selected picker
		function move(target, event, animate) {

			var input = target.parents('.minicolors').find('.minicolors-input'),
				settings = input.data('minicolors-settings'),
				picker = target.find('[class$=-picker]'),
				offsetX = target.offset().left,
				offsetY = target.offset().top,
				x = Math.round(event.pageX - offsetX),
				y = Math.round(event.pageY - offsetY),
				duration = animate ? settings.animationSpeed : 0,
				wx, wy, r, phi;

			// Touch support
			if( event.originalEvent.changedTouches ) {
				x = event.originalEvent.changedTouches[0].pageX - offsetX;
				y = event.originalEvent.changedTouches[0].pageY - offsetY;
			}

			// Constrain picker to its container
			if( x < 0 ) x = 0;
			if( y < 0 ) y = 0;
			if( x > target.width() ) x = target.width();
			if( y > target.height() ) y = target.height();

			// Constrain color wheel values to the wheel
			if( target.parent().is('.minicolors-slider-wheel') && picker.parent().is('.minicolors-grid') ) {
				wx = 75 - x;
				wy = 75 - y;
				r = Math.sqrt(wx * wx + wy * wy);
				phi = Math.atan2(wy, wx);
				if( phi < 0 ) phi += Math.PI * 2;
				if( r > 75 ) {
					r = 75;
					x = 75 - (75 * Math.cos(phi));
					y = 75 - (75 * Math.sin(phi));
				}
				x = Math.round(x);
				y = Math.round(y);
			}

			// Move the picker
			if( target.is('.minicolors-grid') ) {
				picker
					.stop(true)
					.animate({
						top: y + 'px',
						left: x + 'px'
					}, duration, settings.animationEasing, function() {
						updateFromControl(input, target);
					});
			} else {
				picker
					.stop(true)
					.animate({
						top: y + 'px'
					}, duration, settings.animationEasing, function() {
						updateFromControl(input, target);
					});
			}

		}

		// Sets the input based on the color picker values
		function updateFromControl(input, target) {

			function getCoords(picker, container) {

				var left, top;
				if( !picker.length || !container ) return null;
				left = picker.offset().left;
				top = picker.offset().top;

				return {
					x: left - container.offset().left + (picker.outerWidth() / 2),
					y: top - container.offset().top + (picker.outerHeight() / 2)
				};

			}

			var hue, saturation, brightness, x, y, r, phi,

				hex = input.val(),
				opacity = input.attr('data-opacity'),

				// Helpful references
				minicolors = input.parent(),
				settings = input.data('minicolors-settings'),
				swatch = minicolors.find('.minicolors-swatch'),

				// Panel objects
				grid = minicolors.find('.minicolors-grid'),
				slider = minicolors.find('.minicolors-slider'),
				opacitySlider = minicolors.find('.minicolors-opacity-slider'),

				// Picker objects
				gridPicker = grid.find('[class$=-picker]'),
				sliderPicker = slider.find('[class$=-picker]'),
				opacityPicker = opacitySlider.find('[class$=-picker]'),

				// Picker positions
				gridPos = getCoords(gridPicker, grid),
				sliderPos = getCoords(sliderPicker, slider),
				opacityPos = getCoords(opacityPicker, opacitySlider);

			// Handle colors
			if( target.is('.minicolors-grid, .minicolors-slider') ) {

				// Determine HSB values
				switch(settings.control) {

					case 'wheel':
						// Calculate hue, saturation, and brightness
						x = (grid.width() / 2) - gridPos.x;
						y = (grid.height() / 2) - gridPos.y;
						r = Math.sqrt(x * x + y * y);
						phi = Math.atan2(y, x);
						if( phi < 0 ) phi += Math.PI * 2;
						if( r > 75 ) {
							r = 75;
							gridPos.x = 69 - (75 * Math.cos(phi));
							gridPos.y = 69 - (75 * Math.sin(phi));
						}
						saturation = keepWithin(r / 0.75, 0, 100);
						hue = keepWithin(phi * 180 / Math.PI, 0, 360);
						brightness = keepWithin(100 - Math.floor(sliderPos.y * (100 / slider.height())), 0, 100);
						hex = hsb2hex({
							h: hue,
							s: saturation,
							b: brightness
						});

						// Update UI
						slider.css('backgroundColor', hsb2hex({ h: hue, s: saturation, b: 100 }));
						break;

					case 'saturation':
						// Calculate hue, saturation, and brightness
						hue = keepWithin(parseInt(gridPos.x * (360 / grid.width()), 10), 0, 360);
						saturation = keepWithin(100 - Math.floor(sliderPos.y * (100 / slider.height())), 0, 100);
						brightness = keepWithin(100 - Math.floor(gridPos.y * (100 / grid.height())), 0, 100);
						hex = hsb2hex({
							h: hue,
							s: saturation,
							b: brightness
						});

						// Update UI
						slider.css('backgroundColor', hsb2hex({ h: hue, s: 100, b: brightness }));
						minicolors.find('.minicolors-grid-inner').css('opacity', saturation / 100);
						break;

					case 'brightness':
						// Calculate hue, saturation, and brightness
						hue = keepWithin(parseInt(gridPos.x * (360 / grid.width()), 10), 0, 360);
						saturation = keepWithin(100 - Math.floor(gridPos.y * (100 / grid.height())), 0, 100);
						brightness = keepWithin(100 - Math.floor(sliderPos.y * (100 / slider.height())), 0, 100);
						hex = hsb2hex({
							h: hue,
							s: saturation,
							b: brightness
						});

						// Update UI
						slider.css('backgroundColor', hsb2hex({ h: hue, s: saturation, b: 100 }));
						minicolors.find('.minicolors-grid-inner').css('opacity', 1 - (brightness / 100));
						break;

					default:
						// Calculate hue, saturation, and brightness
						hue = keepWithin(360 - parseInt(sliderPos.y * (360 / slider.height()), 10), 0, 360);
						saturation = keepWithin(Math.floor(gridPos.x * (100 / grid.width())), 0, 100);
						brightness = keepWithin(100 - Math.floor(gridPos.y * (100 / grid.height())), 0, 100);
						hex = hsb2hex({
							h: hue,
							s: saturation,
							b: brightness
						});

						// Update UI
						grid.css('backgroundColor', hsb2hex({ h: hue, s: 100, b: 100 }));
						break;

				}

				// Adjust case and strip off hash
				var hex_no_hash = hex.replace(/^\s*#|\s*$/g, ''); // strip the leading #	
				input.val( convertCase(hex_no_hash, settings.letterCase) );

			}

			// Handle opacity
			if( target.is('.minicolors-opacity-slider') ) {
				if( settings.opacity ) {
					opacity = parseFloat(1 - (opacityPos.y / opacitySlider.height())).toFixed(2);
				} else {
					opacity = 1;
				}
				if( settings.opacity ) input.attr('data-opacity', opacity);
			}

			// Set swatch color
			swatch.find('SPAN').css({
				backgroundColor: hex,
				opacity: opacity
			});

			// Handle change event
			doChange(input, hex, opacity);

		}

		// Sets the color picker values from the input
		function updateFromInput(input, preserveInputValue) {


			var hex,
				hsb,
				opacity,
				x, y, r, phi,

				// Helpful references
				minicolors = input.parent(),
				settings = input.data('minicolors-settings'),
				swatch = minicolors.find('.minicolors-swatch'),

				// Panel objects
				grid = minicolors.find('.minicolors-grid'),
				slider = minicolors.find('.minicolors-slider'),
				opacitySlider = minicolors.find('.minicolors-opacity-slider'),

				// Picker objects
				gridPicker = grid.find('[class$=-picker]'),
				sliderPicker = slider.find('[class$=-picker]'),
				opacityPicker = opacitySlider.find('[class$=-picker]');

			// Determine hex/HSB values
			
			hex = convertCase(parseHex(input.val(), false), settings.letterCase);
			
			if( !hex ){
				hex = convertCase(parseHex(settings.defaultValue, false), settings.letterCase);
			}

			hsb = hex2hsb(hex);
			
		
			
			// Update input value
			if( !preserveInputValue ){
				var hex_no_hash = hex.replace(/^\s*#|\s*$/g, ''); // strip the leading #
				input.val(hex_no_hash);
			} 

			// Determine opacity value
			if( settings.opacity ) {
				// Get from data-opacity attribute and keep within 0-1 range
				opacity = input.attr('data-opacity') === '' ? 1 : keepWithin(parseFloat(input.attr('data-opacity')).toFixed(2), 0, 1);
				if( isNaN(opacity) ) opacity = 1;
				input.attr('data-opacity', opacity);
				swatch.find('SPAN').css('opacity', opacity);

				// Set opacity picker position
				y = keepWithin(opacitySlider.height() - (opacitySlider.height() * opacity), 0, opacitySlider.height());
				opacityPicker.css('top', y + 'px');
			}

			// Update swatch (disabled, see the line :  input.after('<span class="minicolors-swatch ..... )
			// If hex is not in a valid format, background = white
			if( hex.length !== 7 ) {
				swatch.find('SPAN').css('backgroundColor', '#ffffff');
				input.css('color', '#000000');
				input.css('backgroundColor', '#ffffff');
			}
			else {
				swatch.find('SPAN').css('backgroundColor', hex);
				input.css('backgroundColor', hex);
			}

			
			// Determine picker locations
			switch(settings.control) {

				case 'wheel':
					// Set grid position
					r = keepWithin(Math.ceil(hsb.s * 0.75), 0, grid.height() / 2);
					phi = hsb.h * Math.PI / 180;
					x = keepWithin(75 - Math.cos(phi) * r, 0, grid.width());
					y = keepWithin(75 - Math.sin(phi) * r, 0, grid.height());
					gridPicker.css({
						top: y + 'px',
						left: x + 'px'
					});

					// Set slider position
					y = 150 - (hsb.b / (100 / grid.height()));
					if( hex === '' ) y = 0;
					sliderPicker.css('top', y + 'px');

					// Update panel color
					slider.css('backgroundColor', hsb2hex({ h: hsb.h, s: hsb.s, b: 100 }));
					break;

				case 'saturation':
					// Set grid position
					x = keepWithin((5 * hsb.h) / 12, 0, 150);
					y = keepWithin(grid.height() - Math.ceil(hsb.b / (100 / grid.height())), 0, grid.height());
					gridPicker.css({
						top: y + 'px',
						left: x + 'px'
					});

					// Set slider position
					y = keepWithin(slider.height() - (hsb.s * (slider.height() / 100)), 0, slider.height());
					sliderPicker.css('top', y + 'px');

					// Update UI
					slider.css('backgroundColor', hsb2hex({ h: hsb.h, s: 100, b: hsb.b }));
					minicolors.find('.minicolors-grid-inner').css('opacity', hsb.s / 100);
					break;

				case 'brightness':
					// Set grid position
					x = keepWithin((5 * hsb.h) / 12, 0, 150);
					y = keepWithin(grid.height() - Math.ceil(hsb.s / (100 / grid.height())), 0, grid.height());
					gridPicker.css({
						top: y + 'px',
						left: x + 'px'
					});

					// Set slider position
					y = keepWithin(slider.height() - (hsb.b * (slider.height() / 100)), 0, slider.height());
					sliderPicker.css('top', y + 'px');

					// Update UI
					slider.css('backgroundColor', hsb2hex({ h: hsb.h, s: hsb.s, b: 100 }));
					minicolors.find('.minicolors-grid-inner').css('opacity', 1 - (hsb.b / 100));
					break;

				default:
					// Set grid position
					x = keepWithin(Math.ceil(hsb.s / (100 / grid.width())), 0, grid.width());
					y = keepWithin(grid.height() - Math.ceil(hsb.b / (100 / grid.height())), 0, grid.height());
					gridPicker.css({
						top: y + 'px',
						left: x + 'px'
					});

					// Set slider position
					y = keepWithin(slider.height() - (hsb.h / (360 / slider.height())), 0, slider.height());
					sliderPicker.css('top', y + 'px');

					// Update panel color
					grid.css('backgroundColor', hsb2hex({ h: hsb.h, s: 100, b: 100 }));
					break;

			}

			// Fire change event, but only if minicolors is fully initialized
			if( input.data('minicolors-initialized') ) {
				doChange(input, hex, opacity);
			}

			
		}

		// Runs the change and changeDelay callbacks
		function doChange(input, hex, opacity) {

			var settings = input.data('minicolors-settings'),
				lastChange = input.data('minicolors-lastChange');

			// Only run if it actually changed
			if( !lastChange || lastChange.hex !== hex || lastChange.opacity !== opacity ) {

				// Remember last-changed value
				input.data('minicolors-lastChange', {
					hex: hex,
					opacity: opacity
				});

				// Fire change event
				if ( settings.change ) {
				
					if ( hex.length>=6 ) { // #123456
						
						var hex_no_hash = hex.replace(/^\s*#|\s*$/g, ''); // strip the leading #	

						if( settings.changeDelay ) {
							// Call after a delay
							clearTimeout(input.data('minicolors-changeTimeout'));
							input.data('minicolors-changeTimeout', setTimeout( function() {
								settings.change.call(input.get(0), hex_no_hash, opacity);
							}, settings.changeDelay));
						} else {
							 // Call immediately
							settings.change.call(input.get(0), hex_no_hash, opacity);
						}
						
						input.trigger('change').trigger('input');
					}
				}
			}
		}

		
		// Generates an RGB(A) object based on the input's value
		function rgbObject(input) {
			var hex = parseHex($(input).val(), false),
				rgb = hex2rgb(hex),
				opacity = $(input).attr('data-opacity');
			if( !rgb ) return null;
			if( opacity !== undefined ) $.extend(rgb, { a: parseFloat(opacity) });
			return rgb;
		}

		// Genearates an RGB(A) string based on the input's value
		function rgbString(input, alpha) {
			var hex = parseHex($(input).val(), false),
				rgb = hex2rgb(hex),
				opacity = $(input).attr('data-opacity');
			if( !rgb ) return null;
			if( opacity === undefined ) opacity = 1;
			if( alpha ) {
				return 'rgba(' + rgb.r + ', ' + rgb.g + ', ' + rgb.b + ', ' + parseFloat(opacity) + ')';
			} else {
				return 'rgb(' + rgb.r + ', ' + rgb.g + ', ' + rgb.b + ')';
			}
		}

		// Converts to the letter case specified in settings
		function convertCase(string, letterCase) {
			return letterCase === 'uppercase' ? string.toUpperCase() : string.toLowerCase();
		}

		// Parses a string and returns a valid hex string when possible
		function parseHex(string, expand) {
			string = string.replace(/[^A-F0-9]/ig, '');
		  
			// 	If we want to parse both 3 and 6 char hex:	
			//  if( string.length !== 3 && string.length !== 6 ) return '';
			
			// 	If we want to parse only 6 char hex:	
			// (I have disabled this to return the wrong hex code in the input field
			//  if(string.length !== 6 ) return '';
			
			if( string.length === 3 && expand ) {
				string = string[0] + string[0] + string[1] + string[1] + string[2] + string[2];
			}
			return '#' + string;
		}

		// Keeps value within min and max
		function keepWithin(value, min, max) {
			if( value < min ) value = min;
			if( value > max ) value = max;
			return value;
		}

		// Converts an HSB object to an RGB object
		function hsb2rgb(hsb) {
			var rgb = {};
			var h = Math.round(hsb.h);
			var s = Math.round(hsb.s * 255 / 100);
			var v = Math.round(hsb.b * 255 / 100);
			if(s === 0) {
				rgb.r = rgb.g = rgb.b = v;
			} else {
				var t1 = v;
				var t2 = (255 - s) * v / 255;
				var t3 = (t1 - t2) * (h % 60) / 60;
				if( h === 360 ) h = 0;
				if( h < 60 ) { rgb.r = t1; rgb.b = t2; rgb.g = t2 + t3; }
				else if( h < 120 ) {rgb.g = t1; rgb.b = t2; rgb.r = t1 - t3; }
				else if( h < 180 ) {rgb.g = t1; rgb.r = t2; rgb.b = t2 + t3; }
				else if( h < 240 ) {rgb.b = t1; rgb.r = t2; rgb.g = t1 - t3; }
				else if( h < 300 ) {rgb.b = t1; rgb.g = t2; rgb.r = t2 + t3; }
				else if( h < 360 ) {rgb.r = t1; rgb.g = t2; rgb.b = t1 - t3; }
				else { rgb.r = 0; rgb.g = 0; rgb.b = 0; }
			}
			return {
				r: Math.round(rgb.r),
				g: Math.round(rgb.g),
				b: Math.round(rgb.b)
			};
		}

		// Converts an RGB object to a hex string
		function rgb2hex(rgb) {
			var hex = [
				rgb.r.toString(16),
				rgb.g.toString(16),
				rgb.b.toString(16)
			];
			$.each(hex, function(nr, val) {
				if (val.length === 1) hex[nr] = '0' + val;
			});
			return '#' + hex.join('');
		}

		// Converts an HSB object to a hex string
		function hsb2hex(hsb) {
			return rgb2hex(hsb2rgb(hsb));
		}

		// Converts a hex string to an HSB object
		function hex2hsb(hex) {

			var hsb = rgb2hsb(hex2rgb(hex));
			if( hsb.s === 0 ) hsb.h = 360;
			return hsb;
		}

		// Converts an RGB object to an HSB object
		function rgb2hsb(rgb) {
			var hsb = { h: 0, s: 0, b: 0 };
			var min = Math.min(rgb.r, rgb.g, rgb.b);
			var max = Math.max(rgb.r, rgb.g, rgb.b);
			var delta = max - min;
			hsb.b = max;
			hsb.s = max !== 0 ? 255 * delta / max : 0;
			if( hsb.s !== 0 ) {
				if( rgb.r === max ) {
					hsb.h = (rgb.g - rgb.b) / delta;
				} else if( rgb.g === max ) {
					hsb.h = 2 + (rgb.b - rgb.r) / delta;
				} else {
					hsb.h = 4 + (rgb.r - rgb.g) / delta;
				}
			} else {
				hsb.h = -1;
			}
			hsb.h *= 60;
			if( hsb.h < 0 ) {
				hsb.h += 360;
			}
			hsb.s *= 100/255;
			hsb.b *= 100/255;
			return hsb;
		}

		// Converts a hex string to an RGB object
		function hex2rgb(hex) {

			hex = parseInt(((hex.indexOf('#') > -1) ? hex.substring(1) : hex), 16);
			return {
				/* jshint ignore:start */
				r: hex >> 16,
				g: (hex & 0x00FF00) >> 8,
				b: (hex & 0x0000FF)
				/* jshint ignore:end */
			};
		}

		// Handle events
		$(document)
			// Hide on clicks outside of the control
			.on('mousedown.minicolors touchstart.minicolors', function(event) {
				if( !$(event.target).parents().add(event.target).hasClass('minicolors') ) {
					hide();
				}
			})
			// Start moving
			.on('mousedown.minicolors touchstart.minicolors', '.minicolors-grid, .minicolors-slider, .minicolors-opacity-slider', function(event) {
				var target = $(this);
				event.preventDefault();
				$(document).data('minicolors-target', target);
				move(target, event, true);
			})
			// Move pickers
			.on('mousemove.minicolors touchmove.minicolors', function(event) {
				var target = $(document).data('minicolors-target');
				if( target ) move(target, event);
			})
			// Stop moving
			.on('mouseup.minicolors touchend.minicolors', function() {
				$(this).removeData('minicolors-target');
			})
			// Show panel when swatch is clicked
			.on('mousedown.minicolors touchstart.minicolors', '.minicolors-swatch', function(event) {
				var input = $(this).parent().find('.minicolors-input');
				event.preventDefault();
				show(input);
			})
			// Show on focus
			.on('focus.minicolors', '.minicolors-input', function() {
				var input = $(this);
				if( !input.data('minicolors-initialized') ) return;
				show(input);
			})
			// Fix hex on blur
			.on('blur.minicolors', '.minicolors-input', function() {
				var input = $(this),
					settings = input.data('minicolors-settings');
				if( !input.data('minicolors-initialized') ) return;

				
				// Parse Hex
		  //      input.val(parseHex(input.val(), true));

				// Is it blank?
				if( input.val() === '' ) input.val(parseHex(settings.defaultValue, false));

				
				// Adjust case and strip off hash
				var hex_no_hash = input.val().replace(/^\s*#|\s*$/g, ''); // strip the leading #	
				input.val( convertCase(hex_no_hash, settings.letterCase) );

				
				updateFromInput(input, true);
				
			})
			// Handle keypresses
			.on('keydown.minicolors', '.minicolors-input', function(event) {
				var input = $(this);
				if( !input.data('minicolors-initialized') ) return;
				switch(event.keyCode) {
					case 9: // tab
						hide();
						break;
					case 13: // enter
					case 27: // esc
						hide();
						input.blur();
						break;
				}
			})
			// Update on keyup
			.on('keyup.minicolors', '.minicolors-input', function() {
				var input = $(this);
				if( !input.data('minicolors-initialized') ) return;
				updateFromInput(input, true);
			})
			// Update on paste
			.on('paste.minicolors', '.minicolors-input', function() {
				var input = $(this);
				if( !input.data('minicolors-initialized') ) return;
				setTimeout( function() {
					updateFromInput(input, true);
				}, 1);
			});

	})(jQuery);


// END Plugins


