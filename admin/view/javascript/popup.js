$(function() {
//$(".popbg").fadeOut("fast");

	
	$('body').on('click', '.closepop',function(e) {							  
		$(".popbg").fadeOut("fast");				
	});
	
	
	
	    //$("div").off().on( "click",'img.tagimg' ,function(e) {
		
		$('body').on('click', '.btn-add',function(e) {
		//$(".btn-add").click(function(){		
	      if($(this).parent().parent().parent().parent().parent().find("input[name='tag_title[]']").val() == '')
		  {
			  alert('PLease enter image');
			  return false;
			  }
		  var parent_id = $(this).parent().parent().parent().parent().parent().parent().attr('id');
		  if(parent_id == "popup_tag"){
				$popup_tag = $("#popup_tag").clone().attr('id', 'popup_tag_'+$("#xco").val()+'_'+$("#yco").val());
				$('#imghidden').append($popup_tag);
				$popup_tag.find('.tagx').val($("#xco").val());
				$popup_tag.find('.tagy').val($("#yco").val());
				$popup_tag.find('#action').append('<button type="button" class="btn btn-primary btn-remove"><i class="fa">Delete</i></button>');
				$("#popup_tag").find('input[type=text]').val('');
				$("#popup_tag").find("input[name='tag_image[]']").val('');
				$("#popup_tag").find(".img-thumb-upload").attr('src','');
				
				$('#divimgtags').append('<img src="./../image/price_info.png" style="position:absolute;top: '+$("#yco").val()+'%;left: '+$("#xco").val()+'%;" id="tag-'+$("#xco").val()+'_'+$("#yco").val()+'" class="tagimg" rel="'+$("#xco").val()+'_'+$("#yco").val()+'" />');
		  }
				$(".popbg").fadeOut("fast");
		

		
	});
		$('body').on('click', '.btn-remove',function(e) {
            $(this).parent().parent().parent().parent().parent().parent().remove();
			var valx = $(this).parent().parent().parent().parent().parent().find('.tagx').val();
		    var valy = $(this).parent().parent().parent().parent().parent().find('.tagy').val();
		//	alert("#tag-"+valx+"_"+valy);
			$("#tag-"+valx+"_"+valy).remove();
			 $(this).parent().parent().parent().parent().parent().parent().remove();
			
		  });
		
		
		
		
	
});

