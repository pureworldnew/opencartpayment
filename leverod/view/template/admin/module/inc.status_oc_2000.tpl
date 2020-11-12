<?php // On Oc 2.0.0.0 the module status is not saved together with the other module settings but in a separate variable: ?>
<?php if (version_compare(VERSION, '2.0.0.0', '==')) { ?>

	<div class="form-group">
		<label class="col-xs-3 control-label"><?php echo $entry_status; ?></label>
		<div class="col-xs-9">
			<select name="<?php echo $module_name . '_status'; ?>" class="form-control">
				<?php if (${$module_name . '_status'}) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0"><?php echo $text_disabled; ?></option>
				<?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
				<?php } ?>
			</select>
		</div>
	</div>
<?php } ?>