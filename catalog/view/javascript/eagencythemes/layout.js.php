<script type="text/javascript"><!--
$(document).ready(function() {
	function addStyle(i){
		$("#" + id_Color_List[i]).css('background', '#' + $("#" + id_Color_List[i]).val());
	}
	for(var i = 0 ; i < id_Color_List.length; i++ ){
			addStyle(i);
	};
});
(function($){
	function changeColorPicker(id_name,class_name,style){
		$('#' + id_name).ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				$(el).val(hex);
				$(el).css({backgroundColor:"#" + hex});
				$(el).ColorPickerHide();
			},
			onChange: function (hsb, hex, rgb) {
				if ($("#temp_setting").val() != "custom") {
                  $("#temp_setting").val("custom");
                }
				var idName = id_name;
				var className = class_name;
				$("#" + id_name).css('background', '#' + hex);
				$("#" + id_name).val(hex);
				$(class_name).css(style,'#' + hex);
			},
			onBeforeShow: function () {
				$(this).ColorPickerSetColor(this.value);
			}
			
		});
	};	
	var initLayout = function() {
		<?php	
			foreach ($objXML->children() as $child){
				foreach($child->children() as $childOFchild){
					foreach($childOFchild->children() as $childOF){ 
						if($childOF->name!=''){ ?>
							changeColorPicker('<?php echo $childOF->name ?>','<?php echo $childOF->class ?>','<?php echo $childOF->style; ?>');
						<?php }
					}
				}	
			}
		?>
	};	
	EYE.register(initLayout, 'init');
})(jQuery)
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
function changeTemplate($themeName) {
	themeName = $themeName;
	if(themeName!='custom'){
	themeData = colorsData[themeName];
	<?php	
		foreach ($objXML->children() as $child){
			foreach($child->children() as $childOFchild){
				foreach($childOFchild->children() as $childOF){ 
					if($childOF->name!=''){ ?>
						
						<?php if($childOFchild->getName()=='background') {
								$name = $childOF->name; 
								$pieces = explode("_", $name);
								$pre = $pieces[0];
								
							if($b_Color_Data[''.$childOF->name.'']){ ?>
								$("<?php echo $childOF->class ?>").css('<?php echo $childOF->style; ?>','#' + themeData['<?php echo $childOF->name ?>']);
								$("#<?php echo $childOF->name ?>").css('background-color', '#' + themeData['<?php echo $childOF->name ?>']);
								$("#<?php echo $childOF->name ?>").val(themeData['<?php echo $childOF->name ?>']);
							<?php } ?>
							<?php if($b_Color_Data[''.$pre.'_bg_image']==''.$pre.'_upload_bg_image') {?>
								$("<?php echo $childOF->class ?>").css('background-image',"url('image/<?php echo $b_Color_Data[''.$pre.'_upload_bg_image']; ?>')");
								$("<?php echo $childOF->class ?>").css('background-position',"<?php echo $b_Color_Data[''.$pre.'_bg_image_position']; ?>");
								$("<?php echo $childOF->class ?>").css('background-repeat',"<?php echo $b_Color_Data[''.$pre.'_bg_image_repeat']; ?>");
							<?php } else {?>
								<?php if($b_Color_Data[''.$pre.'_bg_image']=='default') {}else {?>
									$("<?php echo $childOF->class ?>").css('background-image',"url('image/data/background/<?php echo $b_Color_Data[''.$pre.'_bg_image']; ?>')");
									$("<?php echo $childOF->class ?>").css('background-position',"<?php echo $b_Color_Data[''.$pre.'_bg_image_position']; ?>");
									$("<?php echo $childOF->class ?>").css('background-repeat',"<?php echo $b_Color_Data[''.$pre.'_bg_image_repeat']; ?>");
								<?php } }?>
						<?php }else { ?>
						
						$("<?php echo $childOF->class ?>").css('<?php echo $childOF->style; ?>','#' + themeData['<?php echo $childOF->name ?>']);
						$("#<?php echo $childOF->name ?>").css('background-color', '#' + themeData['<?php echo $childOF->name ?>']);
						$("#<?php echo $childOF->name ?>").val(themeData['<?php echo $childOF->name ?>']);
					<?php } }
				}
			}	
		}
	?>	
	}

function changeProductView(class_left_right,class_content_str,class_product_str){
	var class_left = class_left_right;
	var class_right = class_left_right;
	var class_content_arr = new Array();
	var class_product_arr = new Array();
	class_content_arr = class_content_str.split("-");
	class_product_arr = class_product_str.split("-");
	if($("#column-right").length > 0 && $("#column-left").length > 0){
		class_content = class_content_arr[2];
		class_product = class_product_arr[2];
	}else if($("#column-right").length > 0 || $("#column-left").length > 0){
		class_content = class_content_arr[1];
		class_product = class_product_arr[1];
	}else{
		class_content = class_content_arr[0];
		class_product = class_product_arr[0];
	}
	$('.product-grid > div').removeClass();
	$('.product-grid > div').addClass('grid-'+class_product + ' tablet-grid-'+class_product + ' mobile-grid-100');
	
	$('#content').removeClass();
	$('#content').addClass('grid-'+class_content + ' tablet-grid-'+class_content + ' mobile-grid-100 grid-parent');
	
	if($("#column-left").length > 0)
	{
		$('#column-left').removeClass();
		$('#column-left').addClass('grid-'+class_left + ' tablet-grid-'+class_left + ' mobile-grid-100 grid-parent');
	}
	if($("#column-right").length > 0)
	{
		$('#column-right').removeClass();
		$('#column-right').addClass('grid-'+class_right + ' tablet-grid-'+class_right + ' mobile-grid-100 grid-parent');
	}
}
function changeModeCSS($mode_class){
	var mode_class = $mode_class;
	$('#container').removeClass();
	$('#container').addClass(mode_class);
	$.totalStorage('changeModeCSS', mode_class);
}
function addBackground($link_image,$location){
	var link_image = $link_image;
	var location = $location;
	var repeat = 'repeat';
	var position = 'left top';
	switch(location){
		case 'body':
			if(link_image != ''){
				$("#container").css('background-image', "url(image/data/background/" + link_image + ")");
				$("#container").css('background-repeat', repeat);
				$("#container").css('background-position', position);
			}else{
				$("#container").css('background-image','');
				$("#container").css('background-repeat', '');
				$("#container").css('background-position','');
			}
			$.totalStorage('addBackground_body', link_image);
			
			break;
		case 'footer':
			if(link_image != ''){
				$("#footer").css('background-image', "url(image/data/background/" + link_image + ")");
				$("#footer").css('background-repeat', repeat);
				$("#footer").css('background-position', position);
			}else{
				$("#footer").css('background-image', '');
				$("#footer").css('background-repeat', '');
				$("#footer").css('background-position','');
			}
			$.totalStorage('addBackground_footer', link_image);
			
			break;
		case 'header':
			if(link_image != ''){
				$("#header_top").css('background-image', "url(image/data/background/" + link_image + ")");
				$("#header_top").css('background-repeat', repeat);
				$("#header_top").css('background-position', position);
			}else{
				$("#header_top").css('background-image', '');
				$("#header_top").css('background-repeat', '');
				$("#header_top").css('background-position', '');
			}
			$.totalStorage('addBackground_header', link_image);
			
			break;
	}
}
function storeTotalStorage(class_left_right,class_content_str,class_product_str){
	var class_array_new = new Array();
	class_array_new[0] = class_left_right;
	class_array_new[1] = class_content_str;
	class_array_new[2] = class_product_str;
	$.totalStorage('changeProductView', class_array_new);
}
function loadGird()
{
	<?php $b_Layout_Settings = explode(',', $product_gird); ?>
	var class_left_right = '<?php echo $b_Layout_Settings[0] ; ?>';
	var class_content_str = '<?php echo $b_Layout_Settings[1] ; ?>';
	var class_product_str = '<?php echo $b_Layout_Settings[2] ; ?>';
	changeProductView(class_left_right,class_content_str,class_product_str);
	document.getElementById('view_'+ class_left_right + '_' + class_content_str + '_' + class_product_str).checked=true;
}
function resetFont($class_name,$id_style,$id_size){
	$($class_name).css('font-family','');
	$($class_name).css('font-size','');
}
function ResetAll(){
	var Mode_class_old = '<?php echo $this->config->get('b_Mode_CSS') ;?>';
	changeModeCSS(Mode_class_old);
	document.getElementById('mode_'+ Mode_class_old).checked=true;
	loadGird();
	$.totalStorage('changeProductView', null);
	var template = '<?php echo($b_Setting['temp_setting']); ?>';
	$("#temp_setting").val(template);
	addBackground('','body');
	addBackground('','header');
	addBackground('','footer');
	<?php $objXMLFont = simplexml_load_file("eagency/font_setting.xml"); ?>
	<?php
	foreach ($objXMLFont->children() as $child){
		foreach($child->children() as $childOFchild){ ?>
			$('<?php echo $childOFchild->class_name; ?>').css('font-family','');
			resetFont('<?php echo $childOFchild->class_name; ?>','<?php echo $childOFchild->style; ?>','<?php echo $childOFchild->size; ?>');
		<?php }	
	}
	?>
	changeTemplate(template);
}
mode_class = $.totalStorage('changeModeCSS');
if (mode_class) {
	changeModeCSS(mode_class);
	document.getElementById('mode_'+ mode_class).checked=true;
}

link_image_body = $.totalStorage('addBackground_body');
if (link_image_body) {
	addBackground(link_image_body,'body');
}
link_image_header = $.totalStorage('addBackground_header');
if (link_image_header) {
	addBackground(link_image_header,'header');
}
//--></script>