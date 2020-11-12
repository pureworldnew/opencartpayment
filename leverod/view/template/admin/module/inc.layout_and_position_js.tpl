<?php if (version_compare(VERSION, '1.5.6.4', '<=')) { ?>

	html += '   <div class="lev-panel-body">';

	// Layout (Oc 1.5 ) 
	html += '   	<div class="form-group">';
	html += '   		<label class="col-xs-3 control-label"><?php echo $entry_layout; ?></label>';
	html += '   		<div class="col-xs-9">';
	html += '   			<select class="form-control" name="module[' + identifier + '][layout_id]" >';
							<?php foreach ($layouts as $layout) { ?>
	html += '   				<option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>';
							<?php } ?>
	html += ' 				</select>';
	html += ' 		</div>';
	html += ' 	</div>';


	// Position (Oc 1.5 )	
		
	html += '   	<div class="form-group">';
	html += '   		<label class="col-xs-3 control-label"><?php echo $entry_position; ?></label>';
	html += '   		<div class="col-xs-9">';
	html += '   			<select class="form-control" name="module[' + identifier + '][position]" >';
	html += '   				<option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>';			
	html += '   				<option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
	html += '   				<option value="column_left"><?php echo $text_column_left; ?></option>';
	html += '   				<option value="column_right"><?php echo $text_column_right; ?></option>';			
	html += '   			</select>';
	html += '   		</div>';
	html += '   	</div>';

	html += '   </div>';
	
<?php } ?>