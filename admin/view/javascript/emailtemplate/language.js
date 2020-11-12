(function($){
	$(document).ready(function(){
		
		$('#form-filter .form-change').change(function(){
			var url = 'index.php?route=module/emailtemplate/language_files&token=' + $.urlParam('token');
			
			var filter_language = $('select[name=\'filter_language\']').val();			
			if (filter_language) {
				url += '&filter_language=' + encodeURIComponent(filter_language);
			}
			
			var filter_admin = $('input[name=\'filter_admin\']').is(':checked');			
			if (filter_admin) {
				url += '&filter_admin=1';
			}

			var filter_search = $('input[name=\'filter_search\']').val();
			if (filter_search) {
				url += '&filter_search=' + encodeURIComponent(filter_search);
			}
						
			window.location.href = url;
		})/* Prevent form submit on enter
		.on('keypress keyup', function(e){
			if(event.keyCode == 13 || event.keyCode == 169) {
				e.preventDefault();
				return false;
			}
		});*/
		
		$('.show-editor').click(function(){
			var $field = $(this).parents('.input-group').find('.form-control');
			$field.parent().after($field).remove();	
			
			if(typeof CKEDITOR !== "undefined"){
				CKEDITOR.instances[$field.attr('name')].setData($field.val());
			} else if($.fn.summernote){
				$field.summernote({ focus: true }).code($field.val());
			}
									
			$(this).remove();
		});
		
	});
})(jQuery);