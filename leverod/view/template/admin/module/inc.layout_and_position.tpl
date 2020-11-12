<?php if (version_compare(VERSION, '1.5.6.4', '<=')) { ?>

	<!-- Layout (Oc 1.5 ) -->	
		<div class="form-group">
			<label class="col-xs-3 control-label"><?php echo $entry_layout; ?></label>
			<div class="col-xs-9">
				
				<select class="form-control" name="module[<?php echo $module['key']; ?>][layout_id]" >
					
					<?php foreach ($layouts as $layout) { ?>
						<?php if ($layout['layout_id'] == $module['layout_id']) { ?>
							<option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
						<?php } else { ?>
							<option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
						<?php } ?>
					<?php } ?>
				
				</select> 
			</div>
		</div>
	<!-- End Layout  -->	
		

	<!-- Position (Oc 1.5 ) -->				
		<div class="form-group">
			<label class="col-xs-3 control-label"><?php echo $entry_position; ?></label>
			<div class="col-xs-9">
				
				<select class="form-control" name="module[<?php echo $module['key']; ?>][position]" >
					
					<?php if ($module['position'] == 'content_top') { ?>
					<option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
					<?php } else { ?>
					<option value="content_top"><?php echo $text_content_top; ?></option>
					<?php } ?>
					<?php if ($module['position'] == 'content_bottom') { ?>
					<option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
					<?php } else { ?>
					<option value="content_bottom"><?php echo $text_content_bottom; ?></option>
					<?php } ?>
					<?php if ($module['position'] == 'column_left') { ?>
					<option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
					<?php } else { ?>
					<option value="column_left"><?php echo $text_column_left; ?></option>
					<?php } ?>
					<?php if ($module['position'] == 'column_right') { ?>
					<option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
					<?php } else { ?>
					<option value="column_right"><?php echo $text_column_right; ?></option>
					<?php } ?>
				</select> 
			</div>
		</div>
	<!-- End Position  -->	

<?php } ?>