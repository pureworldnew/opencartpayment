$(document).ready(function() {
	function addStyle(i){
		$("#" + id_Color_List[i]).css('background', '#' + $("#" + id_Color_List[i]).val());
	}
	for(var i = 0 ; i < id_Color_List.length; i++ ){
			addStyle(i);
	};
});

(function($){
	function changeColorPicker(i){
		$('#' + id_Color_List[i]).ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				$(el).val(hex);
				$(el).css({backgroundColor:"#" + hex});
				$(el).ColorPickerHide();
			},
			onChange: function (hsb, hex, rgb) {
				if ($("temp_setting").val() != "custom") {
                  $("#temp_setting").val("custom");
                }
				$("#" + id_Color_List[i]).css('background', '#' + hex);
				$("#" + id_Color_List[i]).val(hex);
			},
			onBeforeShow: function () {
				$(this).ColorPickerSetColor(this.value);
			}
			
		});
	};	
	var initLayout = function() {
		for(var i = 0 ; i < id_Color_List.length; i++ ){
			changeColorPicker(i);
		};
	};	
	EYE.register(initLayout, 'init');
})(jQuery)

function ResetColor(){
	$('.hex, .rgb').attr('value', '');
	$('.hex, .rgb').attr('style', '');
	$("#temp_setting").val("custom");
	$('.bg_image .g_bg_image:eq(0) input').attr('checked','checked');
	$('.bg_image .h_bg_image:eq(0) input').attr('checked','checked');
	$('.bg_image .f_bg_image:eq(0) input').attr('checked','checked');
	$('.bg_image .m_bg_image:eq(0) input').attr('checked','checked');
	$('.bg_image .md_bg_image:eq(0) input').attr('checked','checked');
	$('.bg_image .s_bg_image:eq(0) input').attr('checked','checked');
}

function ResetFont(){
	for(var i = 0 ; i < id_Font_List.length; i++ ){
		$('#span_' + id_Font_List[i]).css("display",'none');
		$('#' + id_Font_List[i]).val('default');
	};
}





















