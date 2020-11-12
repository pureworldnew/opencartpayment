(function($){
	$(document).ready(function(){
		
		$('.form-filter').change(function(){
			var url = 'index.php?route=module/emailtemplate/logs&token=' + $.urlParam('token');
			
			var filter_emailtemplate_id = $('select[name=\'filter_emailtemplate_id\']').val();			
			if (filter_emailtemplate_id) {
				url += '&filter_emailtemplate_id=' + encodeURIComponent(filter_emailtemplate_id);
			}
			
			var filter_store_id = $('select[name=\'filter_store_id\']').val();			
			if (filter_store_id) {
				url += '&filter_store_id=' + encodeURIComponent(filter_store_id);
			}

			var filter_customer_id = $('input[name=\'filter_customer_id\']').val();
			if (filter_customer_id) {
				url += '&filter_customer_id=' + encodeURIComponent(filter_customer_id);
			}
			
			location = url;
		});
				
		if($.fn.autocomplete){
			$('.input-autocomplete-customer').each(function(){
				var $el = $(this), $field = $($el.data('field'));				
				$el.autocomplete({
					source: function(request, response) {
						if(request.length > 1){
							$el.after("<span class='input-group-addon input-autocomplete-loading'><i class='fa fa-circle-o-notch fa-spin'></i></span>");
							$.ajax({
								url: ('index.php?route=sale/customer/autocomplete&token=' + $.urlParam('token')),
								type: 'GET',
								dataType: 'json',
								data: {
									filter_name : encodeURIComponent(request)
								},
								success: function(json) {
									response($.map(json, function(item) {
										return {
											label: item['name'],
											value: item['customer_id']
										}
									}));
									$el.next('.input-autocomplete-loading').remove();
								}
							});
						}
					},
					select: function(item) {
						$el.val(item['label']);
						$field.val(item['value']).change();
					}
				});
				
				$el.change(function(){
					if($el.val() == ''){
						$field.val('').change();
					}
				});
			});
		}
		
		var $row, $field, id, 
			$emailBox = $('#emailBox'),
			$emailBoxText = $('#emailBoxText'),			
			$buttons = $emailBox.find('[data-button]');
		
		var iframe = document.getElementById('emailBoxFrame');
			iframe = (iframe.contentWindow) ? iframe.contentWindow : (iframe.contentDocument.document) ? iframe.contentDocument.document : iframe.contentDocument;
	
		$(".load-email").click(function(e){
			$row = $(this).parents('tr');
			if($row.hasClass('active')) return;

			$row.siblings().removeClass('active');

			$row.addClass('active');

			$emailBox.find('[data-field]').html('').attr('href', '');
			$emailBox.find('[data-button]').attr('href', '');

			iframe.document.open();
			iframe.document.write('');
			iframe.document.close();

			id = $(this).parents('tr').data('id');
			if(!id) return false;
			$emailBox.data('id', id);

			$emailBox.find('.hide').hide();

			$.ajax({
				url: 'index.php?route=module/emailtemplate/fetch_log&token=' + $.urlParam('token') + '&id=' +  id,
				dataType: 'json',
				success: function(json) {
					for(var key in json){
						$field = $emailBox.find('[data-field=' + key + ']');

						if($field && json[key]){
							if($field.data('type') == 'mailto'){
								$field.attr('href', 'mailto:' + json[key] + '?subject=' + json['subject']);
							}
							
							$field.html(json[key]);
							
							if($field.parent().hasClass('hide')){
								$field.parent().show();
							}
						}
					}

					if(json['text']){
						$emailBoxText.hide().html(json['text']);
						$buttons.filter('[data-button=plaintext]').show();
					} else {
						$buttons.filter('[data-button=plaintext]').hide();
					}

					if(json['html']){
						iframe.document.open();
						iframe.document.write(json['html']);
						iframe.document.close();
					}

					$buttons.filter('[data-button=reply]').attr('href', 'mailto:' + json['to'] + '?subject=' + json['subject']);

					if($emailBox.hasClass("hide")){
						$emailBox.removeClass("hide");
						$("html, body").animate({ scrollTop:($emailBox.offset().top-10) }, 500, "linear");
					}

					$buttons.filter('[data-button=html]:not(.hide)').click();
				}
			});
		});
			
		$buttons.click(function(e){
			switch($(this).data('button')){
				case 'plaintext':
					$emailBox.find('#emailBoxFrame').hide();
					$emailBoxText.show();

					$(this).hide();
					$buttons.filter('[data-button=html]').show();
					e.preventDefault();
				break;

				case 'html':
					$emailBoxText.hide();
					$emailBox.find('#emailBoxFrame').show();

					$(this).hide();
					$buttons.filter('[data-button=plaintext]').show();
					e.preventDefault();
				break;
			}
		});
	});
})(jQuery);