/*****************************************************
              Leverod Javascript Library
// Author : Francesco Pisanò - francesco1279@gmail.com
//              
//                   www.leverod.com		
//               © All rights reserved	  
// ***************************************************/


// It sets the same height for all the columns
function setLevColSize() { 
	
	/*
	if ( $('.lev-equal-height .lev-box').length ) {
		 // To make the code simple for now assume that columns don't have margin top/bottom and border top/bottom
	}
	*/
	$('.lev-equal-height').each(function() {
		
		var row			= $(this);
		var rowWidth	= row.innerWidth();
		var $column		= row.find('> *');
		var $box		= row.find('.lev-box');
		var numColumns	= $column.length; // Number of columns in the current row
		var columnWidth	= $column.outerWidth(true);
		
		
		// Instead of summing border top and margin top, get them as difference of outerHeight(true) and innerHeight	
		var boxMarginPlusBorder = $box.outerHeight(true) - $box.innerHeight();
		var boxPadding = $box.innerHeight() - $box.height(); 

		$column.css('height','auto');
		$box.css('height','auto');
		
		if (columnWidth != rowWidth) { // apply the style only if columns are not already stacked
		
			var rowHeight = row.innerHeight() - boxPadding;

			// halve the row height if two pairs of columns within the same row have to be stacked
			if ( ($column.hasClass('lev-col-sm-6') && numColumns == 4 || numColumns == 3)  && columnWidth * 2  >= rowWidth ) {
				rowHeight = rowHeight/2; 	
			}
			
			$column.height(rowHeight /*+ boxMarginPlusBorder*/);
			$box.height(rowHeight - boxPadding - boxMarginPlusBorder );
		}	
	});	

}


// Fix the width of ".lev-container" on pages where the theme container width is smaller than ".lev-container" width
function setLevContainerWidth() {

	$('.lev-container').each(function() {
	
		$(this).removeAttr('style');
		var width = $(this).outerWidth();
		var parentWidth = $(this).parent().outerWidth();
		
		if (width > parentWidth ) {
		
			$(this).outerWidth(parentWidth);
		}
	});
}


function levAutoAdjust() {
	
	setLevContainerWidth();
	setLevColSize();
}


$(function() {
	var resizeTimer;
	$(window).resize(function() {
		clearTimeout(resizeTimer);
		resizeTimer = setTimeout(levAutoAdjust, 100);
	});
	levAutoAdjust();
	
	// Sometimes, when images are not yet loaded, column heights are not correctly computed.
	// Call again the function levAutoAdjust() after all the page elements have been loaded, 
	// to makes sure that column heights will be correctly set. 
	$(window).load (function() {
		levAutoAdjust(); 
	});
	
});

// Sticky header plugin
!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):"object"==typeof module&&module.exports?module.exports=a(require("jquery")):a(jQuery)}(function(a){var b=Array.prototype.slice,c=Array.prototype.splice,d={topSpacing:0,bottomSpacing:0,className:"is-sticky",wrapperClassName:"sticky-wrapper",center:!1,getWidthFrom:"",widthFromWrapper:!0,responsiveWidth:!1,zIndex:"auto"},e=a(window),f=a(document),g=[],h=e.height(),i=function(){for(var b=e.scrollTop(),c=f.height(),d=c-h,i=b>d?d-b:0,j=0,k=g.length;j<k;j++){var l=g[j],m=l.stickyWrapper.offset().top,n=m-l.topSpacing-i;if(l.stickyWrapper.css("height",l.stickyElement.outerHeight()),b<=n)null!==l.currentTop&&(l.stickyElement.css({width:"",position:"",top:"","z-index":""}),l.stickyElement.parent().removeClass(l.className),l.stickyElement.trigger("sticky-end",[l]),l.currentTop=null);else{var o=c-l.stickyElement.outerHeight()-l.topSpacing-l.bottomSpacing-b-i;if(o<0?o+=l.topSpacing:o=l.topSpacing,l.currentTop!==o){var p;l.getWidthFrom?p=a(l.getWidthFrom).width()||null:l.widthFromWrapper&&(p=l.stickyWrapper.width()),null==p&&(p=l.stickyElement.width()),l.stickyElement.css("width",p).css("position","fixed").css("top",o).css("z-index",l.zIndex),l.stickyElement.parent().addClass(l.className),null===l.currentTop?l.stickyElement.trigger("sticky-start",[l]):l.stickyElement.trigger("sticky-update",[l]),l.currentTop===l.topSpacing&&l.currentTop>o||null===l.currentTop&&o<l.topSpacing?l.stickyElement.trigger("sticky-bottom-reached",[l]):null!==l.currentTop&&o===l.topSpacing&&l.currentTop<o&&l.stickyElement.trigger("sticky-bottom-unreached",[l]),l.currentTop=o}var q=l.stickyWrapper.parent(),r=l.stickyElement.offset().top+l.stickyElement.outerHeight()>=q.offset().top+q.outerHeight()&&l.stickyElement.offset().top<=l.topSpacing;r?l.stickyElement.css("position","absolute").css("top","").css("bottom",0).css("z-index",""):l.stickyElement.css("position","fixed").css("top",o).css("bottom","").css("z-index",l.zIndex)}}},j=function(){h=e.height();for(var b=0,c=g.length;b<c;b++){var d=g[b],f=null;d.getWidthFrom?d.responsiveWidth&&(f=a(d.getWidthFrom).width()):d.widthFromWrapper&&(f=d.stickyWrapper.width()),null!=f&&d.stickyElement.css("width",f)}},k={init:function(b){return this.each(function(){var c=a.extend({},d,b),e=a(this),f=e.attr("id"),h=f?f+"-"+d.wrapperClassName:d.wrapperClassName,i=a("<div></div>").attr("id",h).addClass(c.wrapperClassName);e.wrapAll(function(){if(0==a(this).parent("#"+h).length)return i});var j=e.parent();c.center&&j.css({width:e.outerWidth(),marginLeft:"auto",marginRight:"auto"}),"right"===e.css("float")&&e.css({float:"none"}).parent().css({float:"right"}),c.stickyElement=e,c.stickyWrapper=j,c.currentTop=null,g.push(c),k.setWrapperHeight(this),k.setupChangeListeners(this)})},setWrapperHeight:function(b){var c=a(b),d=c.parent();d&&d.css("height",c.outerHeight())},setupChangeListeners:function(a){if(window.MutationObserver){var b=new window.MutationObserver(function(b){(b[0].addedNodes.length||b[0].removedNodes.length)&&k.setWrapperHeight(a)});b.observe(a,{subtree:!0,childList:!0})}else window.addEventListener?(a.addEventListener("DOMNodeInserted",function(){k.setWrapperHeight(a)},!1),a.addEventListener("DOMNodeRemoved",function(){k.setWrapperHeight(a)},!1)):window.attachEvent&&(a.attachEvent("onDOMNodeInserted",function(){k.setWrapperHeight(a)}),a.attachEvent("onDOMNodeRemoved",function(){k.setWrapperHeight(a)}))},update:i,unstick:function(b){return this.each(function(){for(var b=this,d=a(b),e=-1,f=g.length;f-- >0;)g[f].stickyElement.get(0)===b&&(c.call(g,f,1),e=f);e!==-1&&(d.unwrap(),d.css({width:"",position:"",top:"",float:"","z-index":""}))})}};window.addEventListener?(window.addEventListener("scroll",i,!1),window.addEventListener("resize",j,!1)):window.attachEvent&&(window.attachEvent("onscroll",i),window.attachEvent("onresize",j)),a.fn.sticky=function(c){return k[c]?k[c].apply(this,b.call(arguments,1)):"object"!=typeof c&&c?void a.error("Method "+c+" does not exist on jQuery.sticky"):k.init.apply(this,arguments)},a.fn.unstick=function(c){return k[c]?k[c].apply(this,b.call(arguments,1)):"object"!=typeof c&&c?void a.error("Method "+c+" does not exist on jQuery.sticky"):k.unstick.apply(this,arguments)},a(function(){setTimeout(i,0)})});
//Usage:

//	$(function(){
//	    $("#sticker").sticky({topSpacing:0});
//	});



function createVideoMarkup(url) {
	
	// trim empty spaces
	url = url.trim();
	
	var $video;
	
// Youtube	
	var ytMatch = url.match(/^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/);
	if (ytMatch && ytMatch[1].length === 11) {
		var youtubeId = ytMatch[1];
		$video = $('<iframe>')
		.attr('frameborder', 0)
		.attr('src', '//www.youtube.com/embed/' + youtubeId + '?rel=0')
		.attr('width', '640').attr('height', '360');
		
		return $video[0].outerHTML;
	} 
	
// Instagram	
	var igMatch = url.match(/\/\/instagram.com\/p\/(.[a-zA-Z0-9_-]*)/);
	if (igMatch && igMatch[0].length) {
		$video = $('<iframe>')
		.attr('frameborder', 0)
		.attr('src', igMatch[0] + '/embed/')
		.attr('width', '612').attr('height', '710')
		.attr('scrolling', 'no')
		.attr('allowtransparency', 'true');
		
		return $video[0].outerHTML;
	}  
	
// Vine.co
	var vMatch = url.match(/\/\/vine.co\/v\/(.[a-zA-Z0-9]*)/);
	if (vMatch && vMatch[0].length) {
		$video = $('<iframe>')
		.attr('frameborder', 0)
		.attr('src', vMatch[0] + '/embed/simple')
		.attr('width', '600').attr('height', '600')
		.attr('class', 'vine-embed');
		
		return $video[0].outerHTML;
	} 
	
// Vimeo 
	var vimMatch = url.match(/\/\/(player.)?vimeo.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/);
	if (vimMatch && vimMatch[3].length) {
	$video = $('<iframe webkitallowfullscreen mozallowfullscreen allowfullscreen>')
		.attr('frameborder', 0)
		.attr('src', '//player.vimeo.com/video/' + vimMatch[3])
		.attr('width', '640').attr('height', '360');
		
		return $video[0].outerHTML;
	} 
	
// Dailymotion		
	var dmMatch = url.match(/.+dailymotion.com\/(video|hub)\/([^_]+)[^#]*(#video=([^_&]+))?/);
	if (dmMatch && dmMatch[2].length) {
		$video = $('<iframe>')
		.attr('frameborder', 0)
		.attr('src', '//www.dailymotion.com/embed/video/' + dmMatch[2])
		.attr('width', '640').attr('height', '360');
		
		return $video[0].outerHTML;
	} 
	
// Youku	
	var youkuMatch = url.match(/\/\/v\.youku\.com\/v_show\/id_(\w+)=*\.html/);
	if (youkuMatch && youkuMatch[1].length) {
		$video = $('<iframe webkitallowfullscreen mozallowfullscreen allowfullscreen>')
		.attr('frameborder', 0)
		.attr('height', '498')
		.attr('width', '510')
		.attr('src', '//player.youku.com/embed/' + youkuMatch[1]);
		
		return $video[0].outerHTML;
	}  
	
// Mp4, Ogg, Webm	
	var mp4Match = url.match(/^.+.(mp4|m4v)$/);
	var oggMatch = url.match(/^.+.(ogg|ogv)$/);
	var webmMatch = url.match(/^.+.(webm)$/);
	if (mp4Match || oggMatch || webmMatch) {
		$video = $('<video controls>')
		.attr('src', url)
		.attr('width', '640').attr('height', '360');
		
		return $video[0].outerHTML;
	} 
	

	// If the video link is not valid return false:
	return false;
}




// Lev Autocomplete - (	It can select elements also when clicking on the 
// 						highlighted (green) part of the selected text)
(function($) {
	$.fn.levAutocomplete = function(option) {
		return this.each(function() {
			this.timer = null;
			this.items = new Array();
	
			$.extend(this, option);
	
			$(this).attr('autocomplete', 'off');
			
			// Focus
			$(this).on('focus', function() {
				this.request();
			});
			
			// Blur
			$(this).on('blur', function() {
				setTimeout(function(object) {
					object.hide();
				}, 200, this);				
			});
			
			// Keydown
			$(this).on('keydown', function(event) {
				switch(event.keyCode) {
					case 27: // escape
						this.hide();
						break;
					default:
						this.request();
						break;
				}				
			});
			
			// Click
		
			this.click = function(event) {
				event.preventDefault();
	
				// Old
				// value = $(event.target).parent().attr('data-value');
	
				// New : select elements also when clicking on the highlighted part
				 value = $(event.target).closest('li').attr('data-value');
	
				if (value && this.items[value]) {
					this.select(this.items[value]);
				}
			}
			
			// Show
			this.show = function() {
				var pos = $(this).position();
	
				$(this).siblings('ul.dropdown-menu').css({
					top: pos.top + $(this).outerHeight(),
					left: pos.left
				});
	
				$(this).siblings('ul.dropdown-menu').show();
			}
			
			// Hide
			this.hide = function() {
				$(this).siblings('ul.dropdown-menu').hide();
			}		
			
			// Request
			this.request = function() {
				clearTimeout(this.timer);
		
				this.timer = setTimeout(function(object) {
					object.source($(object).val(), $.proxy(object.response, object));
				}, 200, this);
			}
			
			// Response
			this.response = function(json) {
				html = '';
	
				if (json.length) {
					for (i = 0; i < json.length; i++) {
						this.items[json[i]['value']] = json[i];
					}
	
					for (i = 0; i < json.length; i++) {
						if (!json[i]['category']) {
							html += '<li data-value="' + json[i]['value'] + '"><a href="#">' + json[i]['label'] + '</a></li>';
						}
					}
	
					// Get all the ones with a categories
					var category = new Array();
	
					for (i = 0; i < json.length; i++) {
						if (json[i]['category']) {
							if (!category[json[i]['category']]) {
								category[json[i]['category']] = new Array();
								category[json[i]['category']]['name'] = json[i]['category'];
								category[json[i]['category']]['item'] = new Array();
							}
	
							category[json[i]['category']]['item'].push(json[i]);
						}
					}
	
					for (i in category) {
						html += '<li class="dropdown-header">' + category[i]['name'] + '</li>';
	
						for (j = 0; j < category[i]['item'].length; j++) {
							html += '<li data-value="' + category[i]['item'][j]['value'] + '"><a href="#">&nbsp;&nbsp;&nbsp;' + category[i]['item'][j]['label'] + '</a></li>';
						}
					}
				}
	
				if (html) {
					this.show();
				} else {
					this.hide();
				}
	
				$(this).siblings('ul.dropdown-menu').html(html);
			}
			
			$(this).after('<ul class="dropdown-menu"></ul>');
			$(this).siblings('ul.dropdown-menu').delegate('a', 'click', $.proxy(this.click, this));		
		});
	}
})(window.jQuery);	



// Function for getting url parameters (useful to get the token)
if (typeof getURLVar != 'function') { 

   window.getURLVar = function(key) {
							var value = [];

							var query = String(document.location).split('?');

							if (query[1]) {
								var part = query[1].split('&');

								for (i = 0; i < part.length; i++) {
									var data = part[i].split('=');

									if (data[0] && data[1]) {
										value[data[0]] = data[1];
									}
								}

								if (value[key]) {
									return value[key];
								} else {
									return '';
								}
							}
						};
}

function setToken() {

	var token = getURLVar('token');

	if (token) { 	
		return 'token=' + token; // Oc <= 2.3.0.2
	} else {
		
		token = getURLVar('user_token');
		
		if (token) {
			return 'user_token=' + getURLVar('user_token');
		} else {
			return '';
		}
	}
}

// Highlight tabs containing errors
$(function() {

	$('#form').find('.text-danger').each(function() {

		// Pages with top tabs (see Setting page)
		var second_level_tab = $(this).closest('div.tab-pane').attr('id');
		var tab_id = $(this).closest('div.top-tab').attr('id');

		var tabs = [tab_id, second_level_tab];
		var tLen = tabs.length;
		
		for (i = 0; i < tLen; i++) {
		
			if (tabs[i] == null) {
						
				// Module pages
				tabs[i] = $(this).closest('div[id^="tab-module"]').attr('id');
			}

			if (tabs[i] != null) {

				$('a[href="#' + tabs[i] + '"]').addClass('alert alert-danger').css('border-bottom', '3px solid red');
			}
		}
	});
});



// (Partial) Bootstrap modal/dialog support
$(function() {
	$('.lev-close[data-dismiss="alert"]').click(function() {
		$(this).closest('.alert, .lev-alert').hide();
	});
});



// Read cookies
function levGetCookie(name) {
  // Get name followed by anything except a semicolon
  var cookiestring=RegExp(""+name+"[^;]+").exec(document.cookie);
  // Return everything after the equal sign, or an empty string if the cookie name not found
  cookieValue =  decodeURIComponent(!!cookiestring ? cookiestring.toString().replace(/^[^=]+./,"") : "");

  return cookieValue;
}



// Set Cookies (it should support also path and domain...fix it)
function levSetCookie(key, value, days) {

    var date = new Date();
    // Default at 365 days.
    days = days || 365;
    // Get unix milliseconds at current time plus number of days
    date.setTime(+ date + (days * 86400000)); //24 * 60 * 60 * 1000
	document.cookie = key + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    document.cookie = key + "=" + value + "; expires=" + date.toGMTString() + "; path=/";

    return value;
};

 
function levDeleteCookie(name, path, domain) {
	if( get_cookie(name)) {
		document.cookie = name + "=" + ((path) ? ";path="+path:"")+ ((domain)?";domain="+domain:"") + ";expires=Thu, 01 Jan 1970 00:00:01 GMT";
	}
}



// Cross Browser base64 encoder/decoder (atob() and btoa() have issues with old IE verisons)
// Usage:
// Base64.encode(string)  
// Base64.decode(string)

// Create Base64 Object
var levBase64 = {_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){if (typeof e === 'undefined') {return 'undefined';} var t="";var n,r,i,s,o,u,a;var f=0;e=levBase64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){if (typeof e === 'undefined') {return 'undefined';}  var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=levBase64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/rn/g,"n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}};
