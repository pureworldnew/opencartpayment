(function($){
	$(document).ready(function(){
		var $form = $('#form-emailtemplate');
			
		if($.fn.colorpicker){
			$('.input-colorpicker').colorpicker()
		}

		if($.fn.autocomplete){
			$('.input-autocomplete-product').each(function(){
				var $el = $(this),
					$field = $($el.data('field')), 
					$output = $($el.data('output'));	
								
				$el.autocomplete({
					source: function(request, response) {
						if(request.length > 1){
							$el.after("<span class='input-group-addon input-autocomplete-loading'><i class='fa fa-circle-o-notch fa-spin'></i></span>");
							$.ajax({
								url: ('index.php?route=catalog/product/autocomplete&token=' + $.urlParam('token')),
								type: 'GET',
								dataType: 'json',
								data: {
									filter_name : encodeURIComponent(request)
								},
								success: function(json) {
									response($.map(json, function(item) {
										return {
											label: item['name'],
											value: item['product_id']
										}
									}));
									$el.next('.input-autocomplete-loading').remove();
								}
							});
						}
					},
					select: function(item) {
						if($field.val() == '') {
							$field.val(item['value']);
						} else {
							var selection = $field.val().split(',');
							if($.inArray(item['value'], selection) == -1){
								selection.push(item['value']);
								$field.val(selection.join(','));
							}
						}										
						$output.removeClass('hide').find('.list-group').append('<li class="list-group-item" data-id="' + item['value'] + '"><a href="javascript:void(0)" class="badge remove list-group-item-danger"><i class="fa fa-times"></i></a> ' + item['label'] + '</li>');
					}
				});				
				return true;
			});
		}
		
		// Change showcase type
		$('#emailtemplate_config_showcase').change(function(){
			var $tab = $(this).parents('.tab-pane');

			switch($(this).val()){
				case 'products':
					$tab.find('.showcase_products').removeClass('hide');
		  		break;
				default:
					$('#emailtemplate_config_showcase_selection').val('');

					$tab.find('.showcase_selection').empty('');
					$tab.find('.showcase_products').addClass('hide');
			}
		});
		
		// Remove showcase product
		$('#emailtemplate_config_showcase_selection').on('click', '.remove', function(){
			var $item = $(this).parents('li');
			var $field = $('input[name=emailtemplate_config_showcase_selection]');
			
			var values = $.map($field.val().split(','), function(value){ return parseInt(value, 10) });
			var index = $.inArray($item.data('id'), values);
			if(index !== -1){
				values.splice(index, 1);
			}
			$field.val(values.join(','));
			
			$item.remove();
		});	
		
		// Test
		$('#template-test').click(function(e){
			e.preventDefault();
			
			var $el = $(this);
			
			$form.children(':first.alert').remove();
			
			$el.children('.fa').removeClass("fa-envelope-o").addClass("fa-circle-o-notch fa-spin");
			
			$.ajax({
				url: ('index.php?route=module/emailtemplate/config&token=' + $.urlParam('token')),
				type: 'GET',
				dataType: 'json',
				data: {
					id: $.urlParam('id'),
					action: 'test_send'
				},
				success: function(data) {
					$el.children('.fa').removeClass("fa-circle-o-notch fa-spin").addClass("fa-envelope-o");
					
					if(data['success']){
						$form.prepend("<div class='alert alert-success'><i class='fa fa-exclamation-circle'></i> " + data['success'] + "<button type='button' class='close' data-dismiss='alert'>&times;</button></div>");
					}
				}
			});
							
			$el.toggleClass('active');
		});	
		
		// Preview
		var $preview = $('#preview-mail');
		if($preview.length){			
			var requestData = {};
			
			requestData['order_id'] = $preview.data('order');
			
			requestData['emailtemplate_config_id'] = $.urlParam('id');
			
			requestData['key'] = 'order.customer';
			
			if(document.getElementById('store_id')){
				requestData['store_id'] = document.getElementById('store_id').value;
			}
			
			if(document.getElementById('language_id')){
				requestData['language_id'] = document.getElementById('language_id').value;
			}
			
			// OnLoad fetch preview
			$.ajax({
				url: ('index.php?route=module/emailtemplate/preview_email&token=' + $.urlParam('token')),
				type: 'POST',
				dataType: 'text',
				data: requestData,
				success: function(data) {
					if(data){
						var iframe = $preview.find('#preview-with').removeClass('loading').html('<iframe></iframe>').children().get(0);
						iframe = (iframe.contentWindow) ? iframe.contentWindow : (iframe.contentDocument.document) ? iframe.contentDocument.document : iframe.contentDocument;
						iframe.document.open();
						iframe.document.write(data);
						iframe.document.close();
					}
				}
			});

			$preview.find('.media-icon').click(function(){
				$(this).siblings().removeClass('selected');
				$(this).addClass('selected');
				$preview.find('.preview-frame > iframe').css('width', $(this).data('width'));
			});
			
			$preview.find('.preview-image').click(function(){
				var $el = $(this);
				
				if($el.hasClass('preview-no-image')){
					// With Image
					$preview.find('#preview-without').hide();
					$preview.find('#preview-with').show();
					
					$el.addClass('hide');
					$el.prev().removeClass('hide');				
				} else {
					// Without Image
					$preview.find('#preview-without:eq(0)').each(function(){
						if($(this).is(':empty')){
							var $src = $($preview.find('#preview-with > iframe').contents().find("html:eq(0)").clone());
							
							$src.find("img").removeAttr("src");
							$src.find("table,td,div").css("backgroundImage", "").removeAttr("background");
															
							var iframe = $(this).html('<iframe style="width:' + $preview.find('#preview-with > iframe').css('width') + '"></iframe>').children().get(0);
							iframe = (iframe.contentWindow) ? iframe.contentWindow : (iframe.contentDocument.document) ? iframe.contentDocument.document : iframe.contentDocument;
							iframe.document.open();
							iframe.document.write($src.html());
							iframe.document.close();
						}
						$preview.find('#preview-with').hide();
						$(this).show();
					});
					
					$el.addClass('hide');
					$el.next().removeClass('hide');					
				}
			});
			
			
			$preview.find('.template-update').click(function(e){
				e.preventDefault();
				var $el = $(this);
				
				$el.addClass('fa-spin');
				
				var iframe_width;
				$preview.find('.preview-frame').each(function(){
					iframe_width = $('> iframe', this).css('width');
					
					$(this).addClass('loading').html('<i class="fa fa-spinner fa-spin fa-5x"></i>')
				});
				
				$.ajax({
					url: ('index.php?route=module/emailtemplate/preview_email&token=' + $.urlParam('token')),
					type: 'POST',
					dataType: 'text',
					data: {
						emailtemplate_config_id: $.urlParam('id'),
						order_id: $preview.data('order'),
						key: 'order.customer',
						data: $("#form-emailtemplate").serialize()
					},
					success: function(data) {
						if(data){									
							var iframe = $preview.find('#preview-with').removeClass('loading').html('<iframe style="width:' + iframe_width + '"></iframe>').contents().get(0);
							iframe = (iframe.contentWindow) ? iframe.contentWindow : (iframe.contentDocument.document) ? iframe.contentDocument.document : iframe.contentDocument;
							iframe.document.open();
							iframe.document.write(data);
							iframe.document.close();
							
							var $src = $($preview.find('#preview-with > iframe').contents().find("html:eq(0)").clone());
							
							$src.find("img").removeAttr("src");
							$src.find("table,td,div").css("backgroundImage", "").removeAttr("background");
							
							var iframe = $preview.find('#preview-without').removeClass('loading').html('<iframe style="width:' + iframe_width + '"></iframe>').contents().get(0);
							iframe = (iframe.contentWindow) ? iframe.contentWindow : (iframe.contentDocument.document) ? iframe.contentDocument.document : iframe.contentDocument;
							iframe.document.open();
							iframe.document.write($src.html());
							iframe.document.close();								
						}
						$el.removeClass('fa-spin');
					}
				});
				
				$el.toggleClass('active');
			});	
		}
	});
	
})(jQuery);