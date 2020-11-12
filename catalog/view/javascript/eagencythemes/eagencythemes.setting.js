initCPanel();
function initCPanel() {
	var $marginRighty = $('.cpanelContainer .boss-themedesign');
	$marginRighty.animate({
		marginLeft: -($marginRighty.outerWidth()-10)
	});
	$marginRighty.addClass(parseInt($marginRighty.css('marginLeft'),10) == 0 ? "cpanel_closed" : "cpanel_opened").removeClass(parseInt($marginRighty.css('marginLeft'),10) == 0 ? "cpanel_opened" : "cpanel_closed");
}
$('.cpanelContainer .cpanel_icon').click(function() {
	$('.cpanelContainer .boss-themedesign').show(); 
	
	var $marginRighty = $('.cpanelContainer .boss-themedesign');
	$marginRighty.animate({
		marginLeft: parseInt($marginRighty.css('marginLeft'),10) == 0 ? -($marginRighty.outerWidth()-10) : 0
	});
	
	$marginRighty.addClass(parseInt($marginRighty.css('marginLeft'),10) == 0 ? "cpanel_closed" : "cpanel_opened").removeClass(parseInt($marginRighty.css('marginLeft'),10) == 0 ? "cpanel_opened" : "cpanel_closed");
});
function selectedFontStyle($id,$class_name){
	var idSelect = $('#'+$id);
	var id = $id;
	var class_name = $class_name;
	var font = 	idSelect.val().replace(/\+/g," ");
	if((idSelect.val() != 'default') && (idSelect.val() != 'Arial') && (idSelect.val() != 'Verdana') && (idSelect.val() != 'Helvetica') && (idSelect.val() != 'Lucida Grande') && (idSelect.val() != 'Trebuchet MS') && (idSelect.val() != 'Times New Roman') && (idSelect.val() != 'Tahoma') && (idSelect.val() != 'Georgia')){
		
		$('head').append('<link id="link_' + idSelect + '" rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=' + font + '">');
	}
	if(idSelect.val() != 'default'){
		$(class_name).css('font-family',font);
	}else{
		$(class_name).css('font-family','');
	}
}

function selectedFontSize($id,$class_name){
	var idSelect = $('#'+$id);
	var id = $id;
	var class_name = $class_name;
	if(idSelect.val() != 'default'){
		$(class_name).css('font-size',idSelect.val());
	}else{
		$(class_name).css('font-size','');
	}
}
$(document).ready(function() {
	$('#bt_category').dcAccordion({
		menuClose: false,
		autoClose: true,
		saveState: false,
		disableLink: false,	
		autoExpand: true
	});
});
