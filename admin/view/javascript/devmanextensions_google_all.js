$(document).ready(function() {
  $.ajaxSetup({ cache: false });
});

$(document).on('ready', function(){
	$('input.select_all_categories').on('change', function(){
		var cotainer = $(this).closest('div.tab-pane');
		cotainer.find('input[name*="category_allowed"]:visible').prop('checked', $(this).is(':checked'));
	});

	check_conversion_params($('input.conversion_status'));
	check_dynamic_remarketing_params($('input.dynamic_remarketing_status'));
	check_dynamic_remarketing_dynx_params($('input.dynamic_remarketing_dynx_status'));
	check_facebook_pixel_params($('input.facebook_pixel_status'));
	check_criteo_params($('input.criteo_status'));
	check_google_reviews_params($('input.google_reviews_status'));
	check_multichannel_funnel_params($('input.multichannel_funnel_status'))
	check_bing_ads_params($('input.bing_ads_status'));
	check_hotjar_params($('input.hotjar_status'));
	check_pinterest_params($('input.pinterest_status'));
	check_crazyegg_params($('input.crazyegg_status'));

	$('.special_attributes_feed').closest('div.form-group').addClass('params_tag');

	hide_feed_tabs();
});

$(document).on('click', 'ul.nav li a', function(){
	$('select.feed_type_selector').each(function(){
		$(this).val('').selectpicker('refresh');
	});

	hide_feed_containers();
});

function hide_feed_tabs() {
	if(typeof(feed_types) != 'undefined') {
        $.each(feed_types, function (tab_id, name) {
            if (tab_id != '')
                $('a[href="#' + tab_id + '"]').closest('li').hide();
        });
    }
}
function hide_feed_containers() {
	$.each(feed_types, function(tab_id,name) {
		if(tab_id != '')
			$('div#'+tab_id).hide();
	});
}
function show_feed_container(select_changed) {
	tab_id = select_changed.val();
	hide_feed_containers();
	if(tab_id != '')
		$('div#'+tab_id).attr('style', 'visibility:visible;').fadeIn('slow');
}


function check_conversion_params(checkbox)
{
	if(checkbox.closest('div.tab-pane').length > 0)
	{
		var panel = checkbox.closest('div.tab-pane');
		var params = panel.find('input.conversion_params').closest('div.form-group');
	}
	else
	{
		var panel = checkbox.closest('table');
		var params = panel.find('input.conversion_params').closest('tr');
	}

	var checked = checkbox.is(':checked');

	if(checked)
	{
		params.removeClass('force_hide');
		params.addClass('params_tag');
	}
	else
	{
		params.addClass('force_hide');
		params.removeClass('params_tag');
	}
}

function check_dynamic_remarketing_params(checkbox)
{
	if(checkbox.closest('div.tab-pane').length > 0)
	{
		var panel = checkbox.closest('div.tab-pane');
		var params = panel.find('input.dynamic_remarketing_params, select.dynamic_remarketing_params').closest('div.form-group');
	}
	else
	{
		var panel = checkbox.closest('table');
		var params = panel.find('input.dynamic_remarketing_params, select.dynamic_remarketing_params').closest('tr');
	}

	var checked = checkbox.is(':checked');

	if(checked)
	{
		params.removeClass('force_hide');
		params.addClass('params_tag');
	}
	else
	{
		params.addClass('force_hide');
		params.removeClass('params_tag');
	}
}

function check_dynamic_remarketing_dynx_params(checkbox)
{
	if(checkbox.closest('div.tab-pane').length > 0)
	{
		var panel = checkbox.closest('div.tab-pane');
		var params = panel.find('select.dynamic_remarketing_dynx_params').closest('div.form-group');
	}
	else
	{
		var panel = checkbox.closest('table');
		var params = panel.find('select.dynamic_remarketing_dynx_params').closest('tr');
	}

	var checked = checkbox.is(':checked');

	if(checked)
	{
		params.removeClass('force_hide');
		params.addClass('params_tag');
	}
	else
	{
		params.addClass('force_hide');
		params.removeClass('params_tag');
	}
}

function check_facebook_pixel_params(checkbox)
{
	if(checkbox.closest('div.tab-pane').length > 0)
	{
		var panel = checkbox.closest('div.tab-pane');
		var params = panel.find('input.facebook_pixel_params').closest('div.form-group');
	}
	else
	{
		var panel = checkbox.closest('table');
		var params = panel.find('input.facebook_pixel_params').closest('tr');
	}

	var checked = checkbox.is(':checked');

	if(checked)
	{
		params.removeClass('force_hide');
		params.addClass('params_tag');
	}
	else
	{
		params.addClass('force_hide');
		params.removeClass('params_tag');
	}
}

function check_criteo_params(checkbox)
{
	if(checkbox.closest('div.tab-pane').length > 0)
	{
		var panel = checkbox.closest('div.tab-pane');
		var params = panel.find('input.criteo_params').closest('div.form-group');
	}
	else
	{
		var panel = checkbox.closest('table');
		var params = panel.find('input.criteo_params').closest('tr');
	}

	var checked = checkbox.is(':checked');

	if(checked)
	{
		params.removeClass('force_hide');
		params.addClass('params_tag');
	}
	else
	{
		params.addClass('force_hide');
		params.removeClass('params_tag');
	}
}

function check_google_reviews_params(checkbox)
{
	if(checkbox.closest('div.tab-pane').length > 0)
	{
		var panel = checkbox.closest('div.tab-pane');
		var params = panel.find('input.google_reviews_params, select.google_reviews_params').closest('div.form-group');
	}
	else
	{
		var panel = checkbox.closest('table');
		var params = panel.find('input.google_reviews_params, select.google_reviews_params').closest('tr');
	}

	var checked = checkbox.is(':checked');

	if(checked)
	{
		params.removeClass('force_hide');
		params.addClass('params_tag');
	}
	else
	{
		params.addClass('force_hide');
		params.removeClass('params_tag');
	}
}

function check_multichannel_funnel_params(checkbox) {
	if(checkbox.closest('div.tab-pane').length > 0)
	{
		var panel = checkbox.closest('div.tab-pane');
		var params = panel.find('input.multichannel_funnel_params, select.multichannel_funnel_params').closest('div.form-group');
	}
	else
	{
		var panel = checkbox.closest('table');
		var params = panel.find('input.multichannel_funnel_params, select.multichannel_funnel_params').closest('tr');
	}

	var checked = checkbox.is(':checked');

	if(checked)
	{
		params.removeClass('force_hide');
		params.addClass('params_tag');
	}
	else
	{
		params.addClass('force_hide');
		params.removeClass('params_tag');
	}
}

function check_bing_ads_params(checkbox)
{
	if(checkbox.closest('div.tab-pane').length > 0)
	{
		var panel = checkbox.closest('div.tab-pane');
		var params = panel.find('input.bing_ads_params').closest('div.form-group');
	}
	else
	{
		var panel = checkbox.closest('table');
		var params = panel.find('input.bing_ads_params').closest('tr');
	}

	var checked = checkbox.is(':checked');

	if(checked)
	{
		params.removeClass('force_hide');
		params.addClass('params_tag');
	}
	else
	{
		params.addClass('force_hide');
		params.removeClass('params_tag');
	}
}

function check_hotjar_params(checkbox)
{
	if(checkbox.closest('div.tab-pane').length > 0)
	{
		var panel = checkbox.closest('div.tab-pane');
		var params = panel.find('input.hotjar_params').closest('div.form-group');
	}
	else
	{
		var panel = checkbox.closest('table');
		var params = panel.find('input.hotjar_params').closest('tr');
	}

	var checked = checkbox.is(':checked');

	if(checked)
	{
		params.removeClass('force_hide');
		params.addClass('params_tag');
	}
	else
	{
		params.addClass('force_hide');
		params.removeClass('params_tag');
	}
}

function check_pinterest_params(checkbox)
{
	if(checkbox.closest('div.tab-pane').length > 0)
	{
		var panel = checkbox.closest('div.tab-pane');
		var params = panel.find('input.pinterest_params').closest('div.form-group');
	}
	else
	{
		var panel = checkbox.closest('table');
		var params = panel.find('input.pinterest_params').closest('tr');
	}

	var checked = checkbox.is(':checked');

	if(checked)
	{
		params.removeClass('force_hide');
		params.addClass('params_tag');
	}
	else
	{
		params.addClass('force_hide');
		params.removeClass('params_tag');
	}
}

function check_crazyegg_params(checkbox)
{
	if(checkbox.closest('div.tab-pane').length > 0)
	{
		var panel = checkbox.closest('div.tab-pane');
		var params = panel.find('input.crazyegg_params').closest('div.form-group');
	}
	else
	{
		var panel = checkbox.closest('table');
		var params = panel.find('input.crazyegg_params').closest('tr');
	}

	var checked = checkbox.is(':checked');

	if(checked)
	{
		params.removeClass('force_hide');
		params.addClass('params_tag');
	}
	else
	{
		params.addClass('force_hide');
		params.removeClass('params_tag');
	}
}


function generate_workspace()
{
	var id_shop = $('select[name="google_stores"]').val();
	if($('div#tab-general .store_input.store_'+id_shop).length > 0)
		var data = $('div#tab-general .store_input.store_'+id_shop+' input[type="text"], div#tab-general .store_input.store_'+id_shop+' input[type="checkbox"]:checked, div#tab-general .store_input.store_'+id_shop+' select');
	else
		var data = $('div#tab-general tr.store_input.store_'+id_shop+' input[type="text"], div#tab-general tr.store_input.store_'+id_shop+' input[type="checkbox"]:checked, div#tab-general tr.store_input.store_'+id_shop+' select');

	$.ajax({
		url: link_get_workspace,
		data: data,
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
			alert('Error getting Workspace.');
		},
	});
}


$(document).on('ready', function(){
	feed_load_select_configurations('google');
	feed_load_select_configurations('google_reviews');
	feed_load_select_configurations('google_business');
	feed_load_select_configurations('google_facebook');
	feed_load_select_configurations('google_criteo');
	feed_load_select_configurations('google_twenga');

	feed_clean_inputs('google');
	feed_clean_inputs('google_reviews');
	feed_clean_inputs('google_business');
	feed_clean_inputs('google_facebook');
	feed_clean_inputs('google_criteo');
	feed_clean_inputs('google_twenga');

	ajax_get_feed_urls('google');
	ajax_get_feed_urls('google_reviews');
	ajax_get_feed_urls('google_business');
	ajax_get_feed_urls('google_facebook');
	ajax_get_feed_urls('google_criteo');
	ajax_get_feed_urls('google_twenga');
});

function feed_save_configuration(button_pressed, type)
{
	var store_id = $('select[name="google_stores"]').val();

	if(type == 'google')
	{
		var id_selector = '#tab-feed-shopping';
		var error_saving_configuration = 'Error saving google merchant center configuration: Make sure that you gave RECURSIVE permissions 775 to folder "/catalog/controler/extension/feed"';
	}else if(type == 'google_reviews')
	{
		var id_selector = '#tab-feed-shopping-reviews';
		var error_saving_configuration = 'Error saving feed shopping reviews configuration: Make sure that you gave RECURSIVE permissions 775 to folder "/catalog/controler/extension/feed"';
	}else if(type == 'google_business')
	{
		var id_selector = '#tab-csv-adwords';
		var error_saving_configuration = 'Error saving google my business CSV configuration: Make sure that you gave RECURSIVE permissions 775 to folder "/catalog/controler/extension/feed"';
	}else if(type == 'google_facebook')
	{
		var id_selector = '#tab-feed-fb';
		var error_saving_configuration = 'Error saving facebook feeds configuration: Make sure that you gave RECURSIVE permissions 775 to folder "/catalog/controler/extension/feed"';
	}else if(type == 'google_criteo')
	{
		var id_selector = '#tab-feed-criteo';
		var error_saving_configuration = 'Error saving criteo feeds configuration: Make sure that you gave RECURSIVE permissions 775 to folder "/catalog/controler/extension/feed"';
	}
	else if(type == 'google_twenga')
	{
		var id_selector = '#tab-feed-twenga';
		var error_saving_configuration = 'Error saving twenga feeds configuration: Make sure that you gave RECURSIVE permissions 775 to folder "/catalog/controler/extension/feed"';
	}

	var container_inputs = button_pressed.closest(id_selector);
	var inputs_containers = container_inputs.find('.store_input.store_'+store_id);

	inputs_containers.find('input[type="checkbox"]').each(function(){
		if($(this).is(':checked'))
			$(this).val(1);
		else
			$(this).val(0);
	});

	inputs_containers.find('input[name="type"]').remove();
	inputs_containers.append('<input type="hidden" name="type" value="'+type+'">');

	$.ajax({
		url: link_save_json_config,
		data: inputs_containers.find('input, select, textarea'),
		type: "POST",
		dataType: 'json',
		beforeSend:function()
		{
			ajax_loading_open();
		},
		success: function(data) {
			ajax_loading_close();
			if(!data.error)
			{
				open_manual_notification(data.message, 'success', 'check');
				feed_load_select_configurations(type);
				ajax_get_feed_urls(type);
			}
			else
				open_manual_notification(data.message, 'warning', 'exclamation');
		},
		error: function(data) {
			ajax_loading_close();
			alert(error_saving_configuration);
		},
	});
}

function feed_restore_configuration(button_pressed, type)
{
	$.ajax({
		url: link_restore_json_config,
		data: {type:type},
		type: "POST",
		dataType: 'json',
		beforeSend:function()
		{
			ajax_loading_open();
		},
		success: function(data) {
			ajax_loading_close();
			if(!data.error)
			{
				open_manual_notification(data.message, 'success', 'check');
				feed_load_select_configurations(type);
				ajax_get_feed_urls(type);
			}
			else
				open_manual_notification(data.message, 'warning', 'exclamation');
		},
		error: function(data) {
			ajax_loading_close();
			alert('Error restoring configuration: Make sure that you gave RECURSIVE permissions 775 to folder "/catalog/controler/extension/feed"');
		},
	});
}

function feed_delete_configuration(button_pressed, type)
{
	if(type == 'google')
	{
		var selector_name = 'google_base_pro_config_selected_';
	}
	else if(type == 'google_reviews')
	{
		var selector_name = 'google_reviews_base_pro_config_selected_';
	}
	else if(type == 'google_business')
	{
		var selector_name = 'google_business_base_pro_config_selected_';
	}
	else if(type == 'google_facebook')
	{
		var selector_name = 'google_facebook_base_pro_config_selected_';
	}
	else if(type == 'google_criteo')
	{
		var selector_name = 'google_criteo_base_pro_config_selected_';
	}
	else if(type == 'google_twenga')
	{
		var selector_name = 'google_twenga_base_pro_config_selected_';
	}

	var store_id = $('select[name="google_stores"]').val();
	var configuration_name = $('select[name="'+selector_name+store_id+'"]').val();

	$.ajax({
		url: link_delete_json_config,
		data: {type : type, store_id:store_id, configuration_name:configuration_name},
		type: "POST",
		dataType: 'json',
		beforeSend:function()
		{
			ajax_loading_open();
		},
		success: function(data) {
			ajax_loading_close();
			if(!data.error)
			{
				open_manual_notification(data.message, 'success', 'check');
				feed_clean_inputs(type);
				feed_load_select_configurations(type);
				ajax_get_feed_urls(type);
			}
			else
				open_manual_notification(data.message, 'warning', 'exclamation');
		},
		error: function(data) {
			ajax_loading_close();
			alert('Error deleting google merchant center configuration.');
		},
	});
}

function feed_load_configuration(button_pressed, type)
{
	if(type == 'google')
	{
		var selector_name = 'google_base_pro_config_selected_';
		var error_message = 'Error loading google merchant center configuration.';
	}
	else if(type == 'google_reviews')
	{
		var selector_name = 'google_reviews_base_pro_config_selected_';
		var error_message = 'Error loading google reviews feed configuration.';
	}
	else if(type == 'google_business')
	{
		var selector_name = 'google_business_base_pro_config_selected_';
		var error_message = 'Error loading google my business CSV configuration.';
	}
	else if(type == 'google_facebook')
	{
		var selector_name = 'google_facebook_base_pro_config_selected_';
		var error_message = 'Error loading facebook feed configuration.';
	}
	else if(type == 'google_criteo')
	{
		var selector_name = 'google_criteo_base_pro_config_selected_';
		var error_message = 'Error loading criteo feed configuration.';
	}
	else if(type == 'google_twenga')
	{
		var selector_name = 'google_twenga_base_pro_config_selected_';
		var error_message = 'Error loading twenga feed configuration.';
	}

	var store_id = $('select[name="google_stores"]').val();
	var select_configuration = $('select[name="'+selector_name+store_id+'"]');
	var configuration_name = select_configuration.val();

	$.ajax({
		url: link_load_json_config,
		data: {type : type, store_id : store_id, configuration_name : configuration_name},
		type: "POST",
		dataType: 'json',
		beforeSend:function()
		{
			feed_clean_inputs(type);
			ajax_loading_open();
		},
		success: function(data) {
			ajax_loading_close();
			if(!data.error)
			{
				$.each( data, function( field_name, val ){
					var input = $('input[name="'+field_name+'"]');

					if(input.length > 0)
					{
						var type = input.attr('type');
						if(type == 'text')
				    		input.val(val);
				    	else if(type == 'checkbox')
				    	{
				    		if(val == 1)
				    			input.prop('checked', true);
				    		else
				    			input.prop('checked', false);

				    		if(input.hasClass('special_attributes_feed_checkbox'))
				    			input.trigger('change');
				    	}
					}
					else
					{
						var select = $('select[name="'+field_name+'"]');
						if(select.length > 0)
						{
							select.val(val);
							select.selectpicker('refresh');
						}
						else
						{
							var textarea = $('textarea[name="'+field_name+'"]');
							if(textarea.length > 0)
								textarea.val(val);
						}
					}
				});
			}
			else
				open_manual_notification(data.message, 'warning', 'exclamation');
		},
		error: function(data) {
			ajax_loading_close();
			alert(error_message);
		},
	});
}

function feed_clean_inputs(type)
{
	if(type == 'google')
	{
		var container_inputs = $('#tab-feed-shopping');
	}else if(type == 'google_reviews')
	{
		var container_inputs = $('#tab-feed-shopping-reviews');
	}else if(type == 'google_business')
	{
		var container_inputs = $('#tab-csv-adwords');
	}else if(type == 'google_facebook')
	{
		var container_inputs = $('#tab-feed-fb');
	}else if(type == 'google_criteo')
	{
		var container_inputs = $('#tab-feed-criteo');
	}else if(type == 'google_twenga')
	{
		var container_inputs = $('#tab-feed-twenga');
	}

	container_inputs.find('input[type="checkbox"]').each(function(){
		$(this).prop('checked', false);
		$(this).val(0);
	});

	container_inputs.find('input[type="text"]').each(function(){
		$(this).val('');
	});

	container_inputs.find('textarea').each(function(){
		$(this).val('');
	});
}

function feed_load_select_configurations(type)
{
	if($('form#google_all').length > 0)
	{
		$.ajax({
			url: link_get_select_json_config,
			data: {type:type},
			type: "POST",
			dataType: 'json',
			beforeSend:function()
			{
				ajax_loading_open();
			},
			success: function(data) {
				ajax_loading_close();
				if(!data.error)
				{
					$.each( data, function( store_id, configurations ){
						var select_html = '';
						$.each( configurations, function( key, conf_name ){
							select_html += '<option value="'+conf_name+'">'+conf_name+'</option>';
						});
						
						$('select[name="'+type+'_base_pro_config_selected_'+store_id+'"]').html(select_html);
						$('select[name="'+type+'_base_pro_config_selected_'+store_id+'"]').selectpicker('refresh');
					});
				}
				else
					open_manual_notification(data.message, 'warning', 'exclamation');
			},
			error: function(data) {
				ajax_loading_close();
				alert('Error getting configuration feed json of type "'+type+'".');
			},
		});
	}
}

function ajax_get_feed_urls(type)
{
	if($('form#google_all').length > 0)
	{
		$.ajax({
			url: link_ajax_get_feed_urls,
			data: {type:type},
			type: "POST",
			dataType: 'json',
			beforeSend:function()
			{
				ajax_loading_open();
			},
			success: function(data) {
				ajax_loading_close();
				if(!data.error)
				{
					$('div.'+type+'_base_pro_feed_urls').html(data.html);
				}
				else
					open_manual_notification(data.message, 'warning', 'exclamation');
			},
			error: function(data) {
				ajax_loading_close();
				alert('Error getting feed urls.');
			},
		});
	}
}

function send_conversion(negative) {
    var negative = typeof negative != 'undefined' ? 1 : 0;
    var order_id = negative ? $('input.negative_conversion_input:visible').val() : $('input.positive_conversion_input:visible').val();
    var gtm_id = $('input[name*="google_container_id_"]:visible').val();

	if(order_id == '')
		open_manual_notification(conversion_error_empty_order_id, 'warning', 'exclamation');

	$.ajax({
		url: link_ajax_generate_conversion,
		data: {order_id:order_id, gtm_id:gtm_id, negative:negative},
		type: "POST",
		dataType: 'json',
		beforeSend:function()
		{
			ajax_loading_open();
		},
		success: function(data) {
			ajax_loading_close();

			if(data.error)
				open_manual_notification(data.message, 'warning', 'exclamation');
			else {
				open_manual_notification(data.message, 'success', 'check');
                $('input.negative_conversion_input:visible').val('')
                $('head').append(data.script.begin_head);
                $('body').append(data.script.begin_body);
            }
		},
		error: function(data) {
			ajax_loading_close();
			alert('Error getting feed urls.');
		},
	});
}