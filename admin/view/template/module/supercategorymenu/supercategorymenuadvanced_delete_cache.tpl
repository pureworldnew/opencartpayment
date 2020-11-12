<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en" xml:lang="en">
<head>
<title>Super Category Menu</title>
<link rel="stylesheet" type="text/css" href="view/stylesheet/stylesheet.css" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js" ></script>


<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" integrity="sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ==" crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">


</head>
<body>




    <div class="panel panel-default">

        <div class="panel-heading">
		
		<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $entry_cache_del_list; ; ?></h3>
		<div class="pull-right">
			
			<?php if (!$text_error_no_cache) { ?>
      <a id="delete_caches" class="btn btn-danger"><span><?php echo $button_delete; ?></span></a>
      <?php } ?>
      <a onclick="parent.$.fancybox.close();" class="btn btn-info"><span><?php echo $button_cancel; ?></span></a>
			
			
			
			
			</div>
		</div>

        <div class="panel-body">
		
		
		<div class="alert alert-info"><?php echo $text_cache_del_remenber; ?></div>
		
		
		
		
		
		<?php if ($cache_records) { ?>
			<form action="<?php echo $action_del_cache; ?>" method="post" enctype="multipart/form-data" id="form">
					<table class="table table-bordered table-hover">
					  <thead>
						<tr>
						  <td width="1" style="text-align: center;">
						  <input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
						  <td class="text-right">cat/man</td>
						  <td class="text-left">reference</td>
						  <td class="text-left">cached</td>
						  <td class="text-left">date</td>
						</tr>
					  </thead>
					  <tbody>
					
						<?php foreach ($cache_records as $cache_record) { ?>
						<tr>
						  <td style="text-align: center;">
						  <input type="checkbox" name="selected[]" value="<?php echo $cache_record['cache_id']; ?>" /></td>
						  <td class="text-right"><?php echo $cache_record['cat']; ?> / <?php echo $cache_record['man']; ?></td>
						  <td class="text-left"><?php echo $cache_record['name']; ?></td>
						  <td class="text-left"><?php echo $cache_record['cached']; ?></td>
						  <td class="text-left"><?php echo $cache_record['date']; ?></td>
						</tr>
						<?php } ?>
					
						
					
					  </tbody>
					</table>
			</form>
		
		<?php }else{ ?>
		
		   <div class="form-group">
            
            <div class="alert alert-danger">
         <?php echo $text_error_no_cache; ?>
            </div> 
          </div> 
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		<?php } ?>
		
		</div>

    </div>


















<script>

  

  $('a#delete_caches').click(function() { 
	 if(!$('input[type=checkbox]:checked').length) {
        //stop the form from submitting
		alert("Please select at least one to upgrade.");
        return false
     }else{
	   	$('#form').submit();
	  }
});

  <?php if ($successdel) { ?>

 $("#notification").html('<div class="success" style="display: none;"><?php echo $successdel; ?><img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

   $(".success").fadeIn("slow");

   $('.success').delay(2500).fadeOut('slow');

   $("html, body").animate({

      scrollTop: 0

   }, "slow")

  <?php } ?>

  </script>
</body>
</html>