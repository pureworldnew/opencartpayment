<?php
// ***************************************************
//           Leverod Framework for Opencart
//       
// Author : Francesco Pisanò - francesco1279@gmail.com
//              
//                   www.leverod.com		
//               © All rights reserved	  
// ***************************************************
?>

<?php // See module tpl files  ?>
<style>
<?php if ( version_compare(VERSION, '2.0.0.0', '>=') ) { ?> 

.hidden-if-gte-2-0-0-0 { 
	width:0;
	height:0;
	padding:0;
	overflow:hidden;
}	

<?php } ?>

<?php if ( version_compare(VERSION, '2.0.1.0', '>=') ) { ?> 

.hidden-if-gte-2-0-1-0 { 
	width:0;
	height:0;
	padding:0;
	overflow:hidden;
}	

<?php } ?>


<?php // Breadcrumb fix for Oc 1.5   ?>
<?php if ( version_compare(VERSION, '1.5.6.4', '<=') ) { ?> 

.breadcrumb li {display:inline-block;}

.breadcrumb li+li:before { content : " > "}

<?php } ?>
</style>

<style>
	#sticky-save-buttons a {text-decoration:none}
	#sticky-save-buttons i {margin-right:5px;}
	.page-header i {font-size:1.2em;}
	#sticky-save-buttons i.lev-icon-exit {position:relative;top:4px;}
</style>

<?php // Clean the Control Panel title from unwanted tags  ?>
<script>

$(function() {

	// heading_title
	<?php $heading_title = strip_tags($heading_title); ?> 
	$('title').text('<?php echo $heading_title ?>');
});

</script>



<div class="page-header">
	<div class="lev-container-fluid">
		<div id="sticky-save-buttons">

			<button type="button" name="save_stay" value="1" form="form" data-toggle="tooltip" title="<?php echo $button_save_stay; ?>" class="lev-btn lev-btn-success"><?php echo $button_save_stay; ?></button>		
			<button type="button" name="save_stay" value="0" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>"      class="lev-btn lev-btn-primary"><i class="lev-icon-floppy-disk"></i> Save</button>
			<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="lev-btn lev-btn-default"><i class="lev-icon-exit"></i> Cancel</a>
			
			<script>
			$(function(){
				$("#sticky-save-buttons").sticky({topSpacing:0});
				
				$('button[name="save_stay"]').click(function() {
					
					if (typeof doBeforeSaving == 'function') {
					
						$.when(doBeforeSaving()).then(levSave($(this)));  // Include the function doBeforeSaving() in any extension, when you want to execute some code before saving
					} else {
					
						levSave($(this));
					} 
				});
			});
			
			function levSave($button) {
				$('form#form-upload').remove();
				$('#form').append('<input type="hidden" name="' + $button.attr('name') + '" value="' + $button.val() + '" />');
				$('#form').submit();
			}
					
			</script>
			
		</div>
		<h1><?php echo $heading_title; ?></h1>
		<ul class="breadcrumb">
			<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
			<?php } ?>
		</ul>
	</div>
	
	<div class="lev-container-fluid lev-alerts">
	
	<?php if ($error_warning) { ?>
		<div class="lev-alert lev-alert-danger"><i class="lev-icon-warning"></i> <?php echo $error_warning; ?>
			<button type="button" class="lev-close lev-icon-close" data-dismiss="alert"></button>
		</div>
	<?php } ?>
	
	 <?php if ($success) { ?>
		<div class="lev-alert lev-alert-success"><i class="lev-icon-checkmark"></i> <?php echo $success; ?>
			<button type="button" class="lev-close lev-icon-close" data-dismiss="alert"></button>
		</div>
	<?php } ?>
	
	</div>
	
</div>