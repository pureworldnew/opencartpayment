function eagency_addToCart(product_id) {
  $.ajax({
      url: 'index.php?route=eagencythemes/cart/add',
      type: 'post',
      data: 'product_id=' + product_id,
      dataType: 'json',
      success: function(json) {
          if (json['redirect']) {
              location = json['redirect'];
          }

          if (json['success']) {
              addProductNotice(json['title'], json['thumb'], json['success'], 'success');
			  $('#cart-total').html(json['total']);
          }
      }
  });
}

function eagency_addToWishList(product_id) {
	$.ajax({
		url: 'index.php?route=eagencythemes/wishlist/add',
		type: 'post',
		data: 'product_id=' + product_id,
		dataType: 'json',
		success: function(json) {
			if (json['success']) {
				addProductNotice(json['title'], json['thumb'], json['success'], 'success');
				$('#wishlist-total').html(json['total']);
			}
		}
	});
}

function eagency_addToCompare(product_id) {
	$.ajax({
		url: 'index.php?route=eagencythemes/compare/add',
		type: 'post',
		data: 'product_id=' + product_id,
		dataType: 'json',
		success: function(json) {
			if (json['success']) {
                addProductNotice(json['title'], json['thumb'], json['success'], 'success');
				$('#compare-total').html(json['total']);
			}
		}
	});
}

function addProductNotice(title, thumb, text, type) {
    if ($.browser.msie && parseInt($.browser.version) < 8) {
		simpleNotice(title, text, type);
        return false;
    }
    appendNoticeTemplates();
    $("#notification-container").notify("create", "thumb-template", {
        title: title,
        thumb: thumb,
        text:  text,
        type: type
        },{
        expires: 4000
        }
    );
}

function simpleNotice(title, text, type) {
    appendNoticeTemplates();
    $("#notification-container").notify("create", "nothumb-template", {
        title: title,
        text:  text,
        type: type
        },{
        expires: 4000
        }
    );
}

function appendNoticeTemplates() {
  if (!$("#notification-container").length) {
    var tpl = '<div id="notification-container" style="display: none">\
                 <div id="thumb-template">\
                   <a class="ui-notify-cross ui-notify-close eagency_button_remove" href="javascript:;"></a>\
                   <h2 class="eagency_icon_success"><span class="eagency_title">#{title}</span></h2>\
                   <div class="eagency_text">\
                     #{thumb}\
                     <h3>#{text}</h3>\
                   </div>\
                 </div>\
                 <div id="nothumb-template">\
                   <a class="ui-notify-cross ui-notify-close eagency_button_remove" href="javascript:;"></a>\
                   <h2 class="eagency_icon_success"><span class="eagency_title">#{title}</span></h2>\
                   <div class="eagency_text">\
                     <h3>#{text}</h3>\
                   </div>\
                 </div>\
               </div>';
    $(tpl).appendTo("body");
    $("#notification-container").notify();
  }
}


$(document).ready(function() {
	// search
	$('#eagency-search input[name=\'filter_name\']').bind('keydown', function(e) {
		if (e.keyCode == 13) {
			$('#eagency-button-search').trigger('click');
		}
	});

	$('#eagency-button-search').bind('click', function() {
		url =  $('base').attr('href') + 'index.php?route=product/search';
		
		var search = $('#eagency-search input[name=\'filter_name\']').attr('value');
		
		if (search) {
			url += '&search=' + encodeURIComponent(search);
		}

		var category_id = $('#eagency-search select[name=\'filter_category_id\']').attr('value');
		
		if (category_id > 0) {
			url += '&category_id=' + encodeURIComponent(category_id);
		}

		location = url;
	});
	
	// mega menu vertical
	$('.quick-select #eagency_menu > ul > li > a').hover(
		function(){
			hover();
			$(this).next().slideDown(0);	
			$(this).find('b').addClass("active");
		},
		function () {
			$(this).parent().mouseleave(function() {
				$(".dropdown").slideUp(0);
				$(this).find("a b").removeClass("active");
			});
		}
	);	
});

/* mega eagency menu */
$(window).load(function(){
	resizeWidth();		
});
(function($,sr){
  // debouncing function from John Hann
  // http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
  var debounce = function (func, threshold, execAsap) {
      var timeout;

      return function debounced () {
          var obj = this, args = arguments;
          function delayed () {
              if (!execAsap)
                  func.apply(obj, args);
              timeout = null; 
          };

          if (timeout)
              clearTimeout(timeout);
          else if (execAsap)
              func.apply(obj, args);

          timeout = setTimeout(delayed, threshold || 80); 
      };
  }
// smartresize 
 jQuery.fn[sr] = function(fn){  return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr); };

})(jQuery,'smartresize');

var TO = false;
$(window).smartresize(function(){
if(TO !== false)
    clearTimeout(TO);
 TO = setTimeout(resizeWidth, 400); //400 is time in miliseconds
 //resizeWidth();
});

function resizeWidth()
{
	var menuWidth = 904;
	var numColumn = 6;
	//kich thuoc dropdown
	var currentWidth = $("#frame_menu_dropdown").outerWidth()-40;
	
	if (currentWidth < menuWidth) {
		new_width_column = currentWidth / numColumn;
		$('#eagency_menu div.dropdown').each(function(index, element) { 
			var options_list = $(this).next();
			$(this).width(parseFloat(options_list.css("width"))/menuWidth*numColumn * new_width_column); 		
		});
		$('#eagency_menu div.option').each(function(index, element) {
			var option = $(this).next();
		$(this).width(parseFloat(option.css("width"))/menuWidth*numColumn * new_width_column);
			$("ul", this).width(parseFloat(option.css("width"))/menuWidth*numColumn * new_width_column);
		
		});
		$('#eagency_menu ul.column').each(function(index, element) {
			var column = $(this).next();
			$(this).width(parseFloat(column.css("width"))/menuWidth*numColumn * new_width_column);
		});
	}
}
function hover(){
	var menu = jQuery("#eagency_menu");
	if(menu.length){
		menuY = menu.offset().top;
		menuOuterH = menu.outerHeight();
		var list_li = jQuery("#eagency_menu ul.display-menu>li");
		list_li.each(function(idx) {
			
			var li_menuY = jQuery(this).offset().top;
			var li_outerH = jQuery(this).outerHeight();
			
			var dropdown = jQuery(this).find('div.dropdown');
			var dropdownOuterH = dropdown.outerHeight();
			
			if(dropdownOuterH){
				// hien tren top do xuong, neu dowpdowwn lon hon( khoang cach, hoac menuOUterH)
				if(menuOuterH <= dropdownOuterH || li_menuY + li_outerH - menuY <= dropdownOuterH){
					if(menuY == li_menuY){
						dropdown.css('top', menuY - li_menuY + 'px');
					}else{
						dropdown.css('top', menuY - li_menuY - 1 + 'px');
					}
				}else{
				// 2 truong hop = so xuong binh thuong hoac so nguoc len
					var temp = li_menuY - menuY + li_outerH;
					//nguoc len
					if(temp > dropdownOuterH){
						//alert(dropdownOuterH);
						dropdown.css('top', 0 - dropdownOuterH + li_outerH + 'px');
					}else{//binh thuong
						dropdown.css('top', 0 + 'px');
					}
				}
			}
		});
	}
}