<style type="text/css">
body { background-color: transparent }
#header, #footer { display: none !important }
</style>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h4 class="modal-title"><?php echo $heading_template_shortcode; ?></h4>
</div>

<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="container-fluid form-horizontal">
	<div class="modal-body">
		<div class="form-group required">
			<label class="control-label" for="emailtemplate_shortcode_code"><?php echo $entry_code; ?></label>
			<input type="text" required="required" id="emailtemplate_shortcode_code" class="form-control" name="emailtemplate_shortcode_code" value="<?php echo $shortcode['code']; ?>" />
		</div>
	
		<div class="form-group required">
			<label class="control-label" for="emailtemplate_shortcode_code"><?php echo $entry_type; ?></label>
			<select class="form-control" name="emailtemplate_shortcode_type" id="emailtemplate_shortcode_type">
				<option value="auto" <?php if ($shortcode['type'] == 'auto') { ?> selected="selected" <?php } ?>><?php echo $text_auto; ?></option>
				<option value="language" <?php if ($shortcode['type'] == 'language') { ?> selected="selected" <?php } ?>><?php echo $text_language; ?></option>
			</select>
		</div>
	
		<div class="form-group">
			<label class="control-label" for="emailtemplate_shortcode_example"><?php echo $entry_example; ?></label>
			<input type="text" id="emailtemplate_shortcode_example" class="form-control" name="emailtemplate_shortcode_example" value="<?php echo $shortcode['example']; ?>" />
		</div>
	</div>
	
	<div class="modal-footer">
		<button type="submit" class="btn btn-success" data-action="save"><i class="fa fa-save"></i> <?php echo $button_save; ?></button>
	</div>
</form>