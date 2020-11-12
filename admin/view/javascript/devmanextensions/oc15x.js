function change_store(store_id)
{
	$('div.content form tr.store_input').hide();
	$('div.content form tr.store_'+store_id).fadeIn('slow');
}

var options_autocomplete = {
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token='+token+'&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {   
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.product_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		var input_name = $(this).attr('name');

		if($(this).next().hasClass('scrollbox'))
			var div_result = $(this).next();
		else
			var div_result = $(this).closest('tr.field_tr').next('tr').find('.scrollbox');

		div_result.find('div#element-' + ui.item.value).remove();
		div_result.append('<div id="element-' + ui.item.value + '">' + ui.item.label + '<img class="delete_item_autocomplete" src="view/image/delete.png" alt="" /><input type="hidden" name="' +input_name+ '[]" value="' + ui.item.value + '" /></div>');

		div_result.find('div:odd').attr('class', 'odd');
		div_result.find('div:even').attr('class', 'even');

		return false;
	},
	focus: function(event, ui) {
		return false;
	}
};

$(document).on('ready', function()
{
	$('input.products_autocomplete').autocomplete(options_autocomplete);

	$(document).on('click', 'div.scrollbox img.delete_item_autocomplete', function(){
		$(this).parent().remove();
	});

	$(document).on('click', 'div.btn-group.bootstrap-select', function(){
		$(this).addClass('open');
	});
	$(document).on('click', function(event){
		if($(event.target).parents('div.dropdown-menu').length <= 0 && $(event.target).attr('class') != 'btn dropdown-toggle btn-default')
		{
			$('div.btn-group.bootstrap-select').removeClass('open');
		}
	});
});

function open_manual_notification(message, class_custom)
{
	$('div#content div.warning, div#content div.success').remove();
	$('div#content').children('div.breadcrumb').after('<div class="'+class_custom+'">'+message+'</div>');
	$('html, body').animate({
        scrollTop: $("div#content div."+class_custom).offset().top-15
    }, 800);
}

function image_upload(field, thumb) {

	field = escape_brackets(field);
	thumb = escape_brackets(thumb);

	$('#dialog').remove();
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token='+ token +'&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: text_image_manager,
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token='+token+'&image=' + encodeURIComponent($('#' + field).val()),
					dataType: 'text',
					success: function(data) {
						$('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 800,
		height: 400,
		resizable: false,
		modal: false
	});
};


function escape_brackets( myid ) {
    return myid.replace( /(:|\.|\[|\]|,|=|@)/g, "\\$1" );
}
function autocomplete_input(input, input_final_result, id_name, url, token, none)
{
	input.autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: url+'&token='+token+'&filter_name=' +  encodeURIComponent(request.term),
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
		select: function(event, ui) {
			input.val(ui.item.label);
			input_final_result.val(ui.item.value);
			input.removeAttr('autocomplete');
    		input.next('ul.dropdown-menu').remove();

			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
}

function events_after_add_new_row_table_inputs(button_pressed)
{
	var row_inseted = button_pressed.closest('table').find('tbody tr.model_row').prev('tr');
	row_inseted.find('input.date').removeClass('hasDatepicker');
	row_inseted.find('input.date').datepicker({dateFormat: 'yy-mm-dd'});
    row_inseted.find('input.products_autocomplete').autocomplete(options_autocomplete);
}