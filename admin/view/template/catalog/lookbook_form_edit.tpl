<?php echo $header; ?><?php echo $column_left; ?>
<script src="view/javascript/popup.js"></script>
<script src="view/javascript/jquery.uploadfile.js"></script>
<script src="view/javascript/jquery-upload-tags.js"></script>

<link href="view/css/popup.css" rel="stylesheet">
<link href="view/stylesheet/stylesheet1.css" rel="stylesheet">

<div id="content" style="padding-left: 250px;" >
      <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right"><button type="submit" form="form-attribute" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><?php echo $button_save; ?></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-primary"><i class="fa fa-reply"></i></a></div>
                <h1><?php echo $heading_title; ?></h1>
              <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
              </ul>

        </div>
      </div>
 
 <div class="container-fluid">
  
  <div class="panel panel-default">
  <div class="content">
      <div class="panel panel-default">
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-attribute" class="form-horizontal">
          
		  <div class="form-group required">
            <label class="col-sm-2 control-label"><?php echo $entry_title; ?></label>
            <div class="col-sm-10">
               <input type="text" name="image_title" value="<?php echo $image_title; ?>" placeholder="<?php echo $entry_title; ?>" id="input-title-image_title" class="form-control" />
                      <?php if(isset($error_image_title)) { ?>
                      <div class="text-danger"><?php echo $error_image_title; ?></div>
                      <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-attribute-group"><?php echo $entry_meta_description; ?></label>
            <div class="col-sm-10">
			   <input type="text" name="image_meta_desciption" value="<?php echo $image_meta_desciption; ?>" placeholder="<?php echo $entry_meta_description; ?>" id="input-title-image_title" class="form-control" />
                      <?php if(isset($error_image_meta_desciption)) { ?>
                      <div class="text-danger"><?php echo $error_image_meta_desciption; ?></div>
                      <?php } ?>
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-attribute-group"><?php echo $entry_category; ?></label>
            <div class="col-sm-10">
            <input type="text" name="category" value=""  class="form-control" /> 
             <div id="lookbook-category" class="scrollbox" style="margin-left:15px;width:403px;">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($lookbook_categories as $lookbook_category) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div id="lookbook-category<?php echo $lookbook_category['category_id']; ?>" class="<?php echo $class; ?>"><?php echo $lookbook_category['name']; ?><img src="view/image/delete.png" alt="" />
                    <input type="hidden" name="lookbook_category[]" value="<?php echo $lookbook_category['category_id']; ?>" />
                  </div>
                  <?php } ?>
          </div>
            
            
            </div>
          </div>   
           
          
         
		 <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_image_view; ?></label>
            <div class="col-sm-10" style="position: relative;padding: 10px 0px;margin-left: 15px;width: 300px;" id="divimgtags">
              <img src="../image/lookbook/<?php echo $image_name; ?>" style="width:100%;border:thin #ccc solid;position:relative;" id="imgtags" /> 
			  
			  <?php 
			  if(count($image_tag_info) > 0){
			     foreach($image_tag_info as $img){ ?>
			      <img src="./../image/price_info.png" style="position:absolute;top: <?php echo $img[4];?>%;left: <?php echo $img[3];?>%;" id="tag-<?php echo $img[3];?>_<?php echo $img[4];?>" class="tagimg" rel="<?php echo $img[3];?>_<?php echo $img[4];?>">
			<?php }
			
			 }   ?>
			  
            </div>
          </div>
		   
         
		  <input type="hidden" name="image_name" value="<?php echo $image_name; ?>"  />
		  <input type="hidden" id="upload_link" name="upload_link"  value="<?php echo $upload; ?>"  />
		  <input type="hidden" name="xco" id="xco" value=""  />
		  <input type="hidden" name="yco" id="yco" value=""  />
		  <input type="hidden" name="currentpanel" id="currentpanel" value="popup_tag"  />
		  
		    <div id="imghidden">
			
			</div>
        </form>
		
		<div class="popbg" id="popup_tag" >
		      <div class="popinner"  align="center">
<button type="button" class="close fa fa-close closepop" data-dismiss="modal" aria-hidden="true">x</button>
		      
              <table>
			     <tr>
				   <th style="width:100px;text-align:right;">Thumbnail:&nbsp;&nbsp;</th>
				   <td style="padding:10px;text-align:left;">
				  <div class="status" style="float:left;" ></div> <div class="mulitplefileuploader">Upload</div>
                  
		          <input type="hidden" name="tag_image[]" value="" class="input-image" />				  
				  </td>
				  </tr>
				  <tr> <th style="width:100px;text-align:right;">Title:&nbsp;&nbsp;</th>
				  <td style="padding:10px;"><input type="text" name="tag_title[]" value=""  class="form-control" style="width:400px;"/></td></tr>
				 <tr>  <th style="width:100px;text-align:right;">Price:&nbsp;&nbsp;</th>
				 <td style="padding:10px;"><input type="text" name="tag_price[]" value=""  class="form-control" style="width:400px;"/></td></tr>
				 <tr>  <th style="width:100px;text-align:right;">Link:&nbsp;&nbsp;</th>
				 <td style="padding:10px;"><input type="text" name="tag_link[]" value=""  class="form-control" style="width:400px;"/></td></tr>
				 </tr>
				 <tr>  <th style="width:100px;text-align:right;">&nbsp;&nbsp;</th>
				 <td style="padding:10px;" id="action"><button type="button" class="btn btn-primary btn-add"><i class="fa">Save</i></button>
				  
</td></tr>
				 </tr>
			  </table>
			   <input type="hidden" name="tagx[]" class="tagx" value=""  />
			   <input type="hidden" name="tagy[]" class="tagy" value=""  />
			
			  </div>
		   </div>
		   
      </div>
      
    </div>
  </div>
  
</div>

</div>
</div>
{literal}
<script type="text/javascript"><!--
$.widget('custom.catcomplete', $.ui.autocomplete, {
	_renderMenu: function(ul, items) {
		var self = this, currentCategory = '';
		
		$.each(items, function(index, item) {
			if (item.category != currentCategory) {
				ul.append('<li class="ui-autocomplete-category">' + item.category + '</li>');
				
				currentCategory = item.category;
			}
			
			self._renderItem(ul, item);
		});
	}
});

// Category
$('input[name=\'category\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					 //alert(item.name);
					return {
						label: item.name,
						value: item.category_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('#lookbook-category' + ui.item.value).remove();
		
		$('#lookbook-category').append('<div id="lookbook-category' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="lookbook_category[]" value="' + ui.item.value + '" /></div>');

		$('#lookbook-category div:odd').attr('class', 'odd');
		$('#lookbook-category div:even').attr('class', 'even');
				
		return false;
	},
	focus: function(event, ui) {
      return false;
   }
});

$('#lookbook-category div img').live('click', function() {
	$(this).parent().remove();
	
	$('#lookbook-category div:odd').attr('class', 'odd');
	$('#lookbook-category div:even').attr('class', 'even');	
});

	
//--></script>
<script type="text/javascript">
$(document).ready(function()
{

var settings = {
	url: $('#upload_link').val(),
	method: "POST",
	allowedTypes:"jpg,png,gif,doc,pdf,zip",
	fileName: "myfile",
	multiple: false,
	dragDrop: false,
	showStatusAfterSuccess:false,
	onSuccess:function(files,data,xhr)
	{
	
		var currentpanel = '#'+$('#currentpanel').val();
	//	alert(currentpanel);
		$(currentpanel).find(".status").html('<img src='+data+' />');
	   	$(currentpanel).find(".input-image").val(data);
		
	},
    afterUploadAll:function()
    {
       // alert("all images uploaded!!");
    },
	onError: function(files,status,errMsg)
	{		
		$("#status").html("<font color='red'>Upload is Failed</font>");
	}
}

$(".mulitplefileuploader").uploadFile(settings);

var countvar =0;

$('body').on('click', '.tagimg',function(e) {

if(countvar == 0)
{			
<?php
 foreach($image_tag_info as $img){
 ?>
$popup_tag = $("#popup_tag").clone().attr('id', 'popup_tag_<?php echo $img[3];?>_<?php echo $img[4];?>');

$popup_tag.find('.tagx').val(<?php echo $img[3];?>);
$popup_tag.find('.tagy').val(<?php echo $img[4];?>);
$popup_tag.find('#action').append('<button type="button" class="btn btn-primary btn-remove"><i class="fa">Delete</i></button>');
$popup_tag.find("input[name='tag_title[]']").val('<?php echo $img[0];?>');
$popup_tag.find("input[name='tag_price[]']").val('<?php echo $img[1];?>');
$popup_tag.find("input[name='tag_link[]']").val('<?php echo $img[2];?>');
$popup_tag.find("input[name='tag_image[]']").val('../../upload/image/<?php echo $img[5];?>');
$popup_tag.find(".status").append("<img src='<?php echo $img[5];?>' >");
$('#imghidden').append($popup_tag);
 <?php }   ?>

countvar=countvar+1; 
 
}
	var rel= $(this).attr("rel");
	$("#popup_tag_"+rel).fadeIn("fast");
				
});



$("#imgtags").dblclick(function(event){ 
						
	if(countvar == 0)
	{			
	<?php
	 foreach($image_tag_info as $img){
	 ?>
		$popup_tag = $("#popup_tag").clone().attr('id', 'popup_tag_<?php echo $img[3];?>_<?php echo $img[4];?>');
		$popup_tag.find('.tagx').val(<?php echo $img[3];?>);
		$popup_tag.find('.tagy').val(<?php echo $img[4];?>);
		$popup_tag.find('#action').append('<button type="button" class="btn btn-primary btn-remove"><i class="fa">Delete</i></button>');
		$popup_tag.find("input[name='tag_title[]']").val('<?php echo $img[0];?>');
		$popup_tag.find("input[name='tag_price[]']").val('<?php echo $img[1];?>');
		$popup_tag.find("input[name='tag_link[]']").val('<?php echo $img[2];?>');
		$popup_tag.find("input[name='tag_image[]']").val('<?php echo $img[5];?>');
		$popup_tag.find(".status").append("<img src='<?php echo $img[5];?>' >");
		$('#imghidden').append($popup_tag);
	 <?php }   ?>
	
	countvar=countvar+1; 
	 
	}
								
	var x = parseInt(event.pageX- $(this).offset().left)-15,
	y = parseInt(event.pageY- $(this).offset().top)-20;
	x = parseInt((x*100)/300);
	y = parseInt((y*100)/300);
	$("#xco").val(x);
	$("#yco").val(y);
	$("#popup_tag").find('.status').html('');
	$("#popup_tag").fadeIn("fast");
});
	
	
$( "#form-attribute" ).submit(function( event ) {
 
 if(countvar == 0)
	{			
	<?php
	 foreach($image_tag_info as $img){
	 ?>
		$popup_tag = $("#popup_tag").clone().attr('id', 'popup_tag_<?php echo $img[3];?>_<?php echo $img[4];?>');
		$popup_tag.find('.tagx').val(<?php echo $img[3];?>);
		$popup_tag.find('.tagy').val(<?php echo $img[4];?>);
		$popup_tag.find('#action').append('<button type="button" class="btn btn-primary btn-remove"><i class="fa">Delete</i></button>');
		$popup_tag.find("input[name='tag_title[]']").val('<?php echo $img[0];?>');
		$popup_tag.find("input[name='tag_price[]']").val('<?php echo $img[1];?>');
		$popup_tag.find("input[name='tag_link[]']").val('<?php echo $img[2];?>');
		$popup_tag.find("input[name='tag_image[]']").val('<?php echo $img[5];?>');
		$popup_tag.find(".status").append("<img src='<?php echo $img[5];?>' >");
		$('#imghidden').append($popup_tag);
	 <?php }   ?>
	
	countvar=countvar+1; 
	 
	}
 
});				

});
</script>
{/literal}

<?php echo $footer; ?>

