function ajax_loading_open() {
  jQuery('body').prepend('<div class="ajax_loading"><i class="fa fa-refresh fa-spin"></i></div>');
}

function ajax_loading_close() {
  jQuery('body div.ajax_loading').fadeOut('fast', function(){
    jQuery(this).remove();
  });
}

function add_row_table_input(element)
{
	var table = element.closest('table');
	var model_row = table.find('tr.model_row').html();
	var num_rows = table.data('rows');

	var new_row = model_row.replace(/replace_by_number/gi, num_rows);
	table.find('tbody').find('tr.model_row').before('<tr>'+new_row+'</tr>');
	table.data('rows', (num_rows+1));

	var last_row_inserted = table.find('tbody').find('tr.model_row').prev('tr');
	last_row_inserted.attr('data-numrow', num_rows);
	last_row_inserted.find('.bootstrap-select').replaceWith(function() { return $(this).find('select'); });

	last_row_inserted.find('.selectpicker').each(function(){
		var select = $(this);
		var text_empty = select.children('option[value=""]').text();

		if(select.attr('multiple') && text_empty)
			select.selectpicker({noneSelectedText:text_empty});
		else
			select.selectpicker();
	});
}

function clone_tr(button_pressed)
{
	var textarea_values = [];

	button_pressed.closest('tr').find('textarea').each(function(){
		var array_to_push = {
			'name': $(this).attr('name'),
			'value': $(this).val()
		}
		textarea_values.push(array_to_push)
	});

	var table = button_pressed.closest('table');
	var row = button_pressed.closest('tr');

	var row_cloned = row.clone();
	
	var number_search = button_pressed.closest('tr').data('numrow');
	var number_replace = table.data('rows');

	row_cloned.find('input, select, a.img-thumbnail').each(function(){
		var input_name = $(this).attr('name');
		if(input_name)
		{
			var input_name_replaced = input_name.replace('['+number_search+']', '['+number_replace+']');
			$(this).attr('name', input_name_replaced);
			$(this).attr('id', input_name_replaced);
		}
	});

	row_cloned.find('textarea').each(function(){
		var textarea = $(this);
		var input_name = $(this).attr('name');
		if(input_name)
		{
			textarea_value = '';

			$.each( textarea_values, function( index, val ){
			    if(input_name == val.name)
			    	textarea_value = val.value;
			});

			var input_name_replaced = input_name.replace('['+number_search+']', '['+number_replace+']');

			$(this).attr('name', input_name_replaced);
			$(this).attr('id', input_name_replaced);
			$(this).val(textarea_value);
		}
	});

	row_cloned.find('select.selectpicker').each(function(){
		var select = $(this);

		select.children('option').removeAttr('selected');

		var list_dropdown = select.parent('div.btn-group').find('ul.dropdown-menu');

		list_dropdown.children('li.selected').each(function(){
			var option_selected = $(this).data('original-index');
			select.find('option[data-optionposition='+option_selected+']').attr('selected', 'selected').prop('selected', true);
		});

		select.parent('.bootstrap-select').replaceWith(function() { return $(this).find('select'); });
		
		var text_empty = select.children('option[value=""]').text();
		
		if(select.attr('multiple') && text_empty)
			select.selectpicker({noneSelectedText:text_empty});
		else
			select.selectpicker();
	});

	row_cloned.find('a.img-thumbnail').each(function(){
		var input_id = $(this).attr('id');
		if(input_id)
		{
			var input_id_replaced = input_id.replace('-'+number_search+'-', '-'+number_replace+'-');
			$(this).attr('id', input_id_replaced);
			
			var input_id_replaced_changed = input_id_replaced.replace('thumb','input');
			$(this).next('input').attr('id', input_id_replaced_changed);
		}
	});

	row.after(row_cloned);
	row_cloned.attr('data-numrow', table.data('rows'));
	table.data('rows', (table.data('rows')+1));

	events_after_add_new_row_table_inputs($(button_pressed).closest('table').find('tfoot').find('a.btn.btn-primary'));
}

function hide_column(button_pressed, number_column)
{
	button_pressed.closest('table').find('tbody tr').each(function(){
		$(this).children('td:visible:eq('+number_column+')').toggleClass('hide_column');
	});
	button_pressed.children('i').toggleClass('fa-toggle-left fa-toggle-right');
}

function save_configuration_ajax(form_extension, force_function)
{
	if(typeof force_function !== 'undefined')
		$('input[name="force_function"]').val(force_function);

	$.ajax({
		url: form_extension.attr('action'),
		data: form_extension.serialize(),
		type: "POST",
		dataType: 'json',
		beforeSend:function()
		{
			ajax_loading_open();
		},
		success: function(data) {
			ajax_loading_close();
			if(!data.error)
				open_manual_notification(data.message, 'success', 'check');
			else
				open_manual_notification(data.message, 'warning', 'exclamation');
		},
		error: function(data) {
			ajax_loading_close();
			alert('Error saving configuration.');
		},
	});
}

function ajax_get_form()
{
	var form_license = $('form#license_form');
	
	$.ajax({
		url: link_ajax_get_form,
		data: form_license.serialize(),
		type: "POST",
		dataType: 'json',
		beforeSend:function()
		{
			ajax_loading_open();
		},
		success: function(data) {
			if(!data.error)
				location.reload();
			else
			{
				ajax_loading_close();
				open_manual_notification(data.message, 'warning', 'exclamation');
			}
		},
		error: function(data) {
			ajax_loading_close();
			alert('Error getting form.');
		},
	});
}


function send_ticket()
{
	var tab_help = $('div#tab-support input,div#tab-support textarea,div#tab-support select');
	
	$.ajax({
		url: link_ajax_open_ticket,
		data: tab_help,
		type: "POST",
		dataType: 'json',
		beforeSend:function()
		{
			ajax_loading_open();
		},
		success: function(data) {
			ajax_loading_close();
			if(!data.error)
				open_manual_notification(data.message, 'success', 'check');			
			else
				open_manual_notification(data.message, 'warning', 'exclamation');
		},
		error: function(data) {
			ajax_loading_close();
			alert('Error openning ticket.');
		},
	});
}

$('.selectpicker').each(function(){
	var select = $(this);
	var text_empty = select.children('option[value=""]').text();
	
	if(select.attr('multiple') && text_empty)
		select.selectpicker({noneSelectedText:text_empty});
	else
		select.selectpicker();
});