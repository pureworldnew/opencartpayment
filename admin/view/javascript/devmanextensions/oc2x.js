function change_store(store_id)
{
	$('div.store_input').hide();
	$('div.store_'+store_id).fadeIn('slow');
}
$(document).on('ready', function(){
	$('input.date').datetimepicker({
		pickTime: false
	});
});

function open_manual_notification(message, class_custom, icon_class)
{
	if(class_custom == 'warning') class_custom = 'danger';
	$('div#content > div.container-fluid div.alert').remove();
	$('div#content > div.container-fluid').prepend('<div class="alert alert-'+class_custom+'"><i class="fa fa-'+icon_class+'-circle"></i> '+message+'<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
	$('html, body').animate({
        scrollTop: $("div#content > div.container-fluid > div.alert").offset().top-15
    }, 800);
}

var options_autocomplete = {
	source: function(request, response) {
		var input_changed = $(this);
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&'+token_name+'='+token+'&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	select: function(item) {
		var input_name = $(this).attr('name');

		$(this).val('');
		var div_products = $(this).parent().children('div.well');
		$(div_products).find('div#element-' + item['value']).remove();

		$(div_products).append('<div id="element-' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="'+input_name+'[]" value="' + item['value'] + '" /></div>');  
	}
};

$(document).on('ready', function()
{
	$('input.products_autocomplete').autocomplete2(options_autocomplete);

	$(document).on('click', 'div.well i.fa-minus-circle', function(){
		$(this).parent().remove();
	});
});

// Autocomplete */
(function($) {
	$.fn.autocomplete2 = function(option) {
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

				value = $(event.target).parent().attr('data-value');

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

function autocomplete_input(input, input_final_result, id_name, url, token, none)
{
	input.next('ul.dropdown-menu').remove();
	input.autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: url+'&'+token_name+'='+token+'&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',			
				success: function(json) {
					if(none)
					{
						json.unshift({
							id_name: 0,
							name: text_none
						});
					}
					
					response($.map(json, function(item) {
						return {
							label: item['name'],
							value: item[id_name]
						}
					}));
				}
			});
		},
		'select': function(item) {
			input.val(item['label']);
			input_final_result.val(item['value']);
			input.removeAttr('autocomplete');
    		input.next('ul.dropdown-menu').remove();
		}	
	});
}

function events_after_add_new_row_table_inputs(button_pressed)
{
	var row_inseted = button_pressed.closest('table').find('tbody tr.model_row').prev('tr');

	row_inseted.find('input.date').datetimepicker({
      	pickTime: false
    });

    row_inseted.find('input.DateTimePicker_date').each(function(){
    	$(this).data('date-format','YYYY-MM-DD');
		$(this).data('format','YYYY-MM-DD');
    });
    row_inseted.find('input.DateTimePicker_datetime').each(function(){
    	$(this).data('date-format','YYYY-MM-DD HH:mm');
		$(this).data('format','YYYY-MM-DD HH:mm');
    });
    row_inseted.find('input.DateTimePicker_time').each(function(){
    	$(this).data('date-format','HH:mm');
		$(this).data('format','HH:mm');
    });

    row_inseted.find('input.DateTimePicker_date').datetimepicker({
      	pickTime: false
    });

    row_inseted.find('input.DateTimePicker_datetime').datetimepicker({
      	pickTime: true
    });
    row_inseted.find('input.DateTimePicker_time').datetimepicker({
      	pickTime: true,
      	pickDate: false
    });

    row_inseted.find('input.products_autocomplete').autocomplete2(options_autocomplete);
}