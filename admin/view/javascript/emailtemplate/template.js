(function($){
	    
    $(document).ready(function(){
    	
	    $('#add-condition').change(function(){
			var $conditions = $('#emailtemplate_conditions');
			var i = $conditions.children(':last-child').data('count');
			if(i >= 1){
				i++;
			} else {
				i = 1;
			}

			var html = '<div class="row row-spacing" data-count="' + i + '">';
			    html += '	<div class="col-md-3"><input type="text" name="emailtemplate_condition[' + i + '][key]" class="form-control" value="' + $(this).find(":selected").text() + '" readonly="readonly" /></div>';
			    html += '	<div class="col-md-3"><select name="emailtemplate_condition[' + i + '][operator]" class="form-control">';
			    html += '		<option value="==">(==) Equal</option>';
				html += '		<option value="!=">(!=) &nbsp;Not Equal</option>';
				html += '		<option value="&gt;">(&gt;) &nbsp;&nbsp;Greater than</option>';
				html += '		<option value="&lt;">(&lt;) &nbsp;&nbsp;Less than</option>';
				html += '		<option value="&gt;=">(&gt;=) Greater than or equal to </option>';
				html += '		<option value="&lt;=">(&lt;=) Less than or equal to </option>';
				html += '		<option value="IN">(IN) Checks if a value exists in comma-delimited string </option>';
				html += '		<option value="NOTIN">(NOTIN) Checks if a value does not exist in comma-delimited string </option>';
				html += '	</select></div>';
				html += '	<div class="col-md-6"><div class="input-group"><input type="text" name="emailtemplate_condition[' + i + '][value]" class="form-control" value="" placeholder="Value" />';
				html += '	<span class="input-group-btn"><button class="btn btn-default btn-remove-row" type="button"><i class="fa fa-trash-o"></i></button></span></div>';
				html += '</div>';
			$conditions.append(html);

			$(this).find('option:selected').removeAttr("selected");
		});
	    
	    $('.shortcode-select').click(function(){
	        $(this).select();
	        return false;
	    });
	    
	    $('.add-editor').click(function(){	
	    	var $el = $('#emailtemplate_description_content' + $(this).data('content') + '_' + $(this).data('lang'));	    	
	    	$el.parents('.emailtemplate_content').removeClass('hide');	
	    	
	    	if(typeof CKEDITOR !== "undefined") {
	    		CKEDITOR.replace($el.attr('id'));
	    	} else if($.fn.ckeditor) {
            	$el.ckeditor({
					height: 180
				});
            } else if($.fn.summernote){
				$el.summernote({
					height: 180
				});
            }
	    		    	
	    	$(this).remove();
	    });

	    $(document).on('click', '.btn-remove-row', function(){
			$(this).parents('.row').remove();
		});
		
	    /**
	     * Preview
	     */
	    $('.btn-inbox-preview').click(function(e){
	    	e.preventDefault();
	    	
			var $this = $(this);
			
		    var $preview = $($this.data('target'));
			
			$this.button('loading');
		    
		    var url_preview = 'index.php?route=module/emailtemplate/preview_email&token=' + $.urlParam('token') + '&emailtemplate_id=' + $.urlParam('id') + '&store_id=' + $preview.data('store') + '&language_id=' + $preview.data('language');
		    
		    if(typeof CKEDITOR !== "undefined"){
				for(var instanceName in CKEDITOR.instances){
					CKEDITOR.instances[instanceName].updateElement();
				} 
			} else if($.fn.summernote){
				$('textarea.wysiwyg_editor').each(function(){
					$(this).val($(this).code());
				});
            }
			
			$.ajax({
				url: url_preview,
				type: 'POST',
				data: {
					emailtemplate_id: $.urlParam('id'),
					store_id: $preview.data('store'),
					language_id: $preview.data('language'),
					data: $("#form-emailtemplate").serialize()
				},
				dataType: 'text',
				success: function(data) {
					if(data){
						var iframe = $preview.find('#preview-with').removeClass('loading').html('<iframe></iframe>').children().get(0);
						iframe = (iframe.contentWindow) ? iframe.contentWindow : (iframe.contentDocument.document) ? iframe.contentDocument.document : iframe.contentDocument;
						iframe.document.open();
						iframe.document.write(data);
						iframe.document.close();
						
						$preview.removeClass('hide');
						
						$this.button('reset');
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
					$preview.find('#preview-without').hide();
					$preview.find('#preview-with').show();
					
					$el.addClass('hide');
					$el.prev().removeClass('hide');				
				} else {
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
		}); //preview		
			
		var $model_frame = $('#modal-frame');
		
		var initModalFrame = function(e){
			e.preventDefault();
			
			var $el = $(this);
			
			switch($(this).data('modal')){			
				case 'remote':
					$.ajax({
						url: $el.data('url'),
						type: 'GET',
						dataType: 'html',
						success: function(response_load) {
							if(response_load){														
								$model_frame.find('.modal-content').html(response_load);
								
								$model_frame.find('[data-action=save]').click(function(e){
									$.ajax({
										url: $el.data('url'),
										type: 'POST',										        
								        data: $model_frame.find('.modal-content form').serialize(),
								        dataType: 'json',
								        success: function(response_save){
								        	if(response_save['success']){
								        		$('#form-emailtemplate').prepend("<div class='alert alert-success'><i class='fa fa-exclamation-circle'></i> " + response_save['success'] + "<button type='button' class='close' data-dismiss='alert'>&times;</button></div>")
								        									
								        		$model_frame.removeClass('in');
								        		
								        		if($el.data('refresh')){
								        			$.ajax({
														url: window.location.href,
												        dataType: 'html',
												        success: function(response_html){
												        	$($el.data('refresh')).removeClass('loading').html($('<div />').html(response_html).find($el.data('refresh')).html())
												        		.find('[data-modal]').click(initModalFrame);
												        	
												        	$model_frame.modal('hide');
												        }
												    });										        			
								        		} else {
								        			$model_frame.modal('hide');
								        		}
								        		
								        		$model_frame.find('.modal-content').html('');
								        	}
								        }
								    });
									e.preventDefault();
								});
								
								$model_frame.modal();
							}
						}
					});
				break;
			}			
		};
		
		$('[data-modal]').click(initModalFrame);

    }); //docReady
})(jQuery);