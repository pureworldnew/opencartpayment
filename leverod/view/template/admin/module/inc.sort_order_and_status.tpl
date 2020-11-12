<div class="form-group hidden-if-gte-2-0-0-0">
	<label class="col-xs-3 control-label"><?php echo $entry_sort_order; ?></label>
	<div class="col-xs-9">
		<input type="text" name="module[<?php echo $module['key']; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" class="form-control" />
	</div>
</div>


<?php // On Oc != 2.0.0.0 the module status is saved together with the other module settings: ?>
<?php if (version_compare(VERSION, '2.0.0.0', '!=')) { ?>

<div class="form-group">
	<label class="col-xs-3 control-label"><?php echo $entry_status; ?></label>
	<div class="col-xs-9">
	
		<select name="module[<?php echo $module['key']; ?>][status]" class="form-control">
			<?php if ($module['status']) { ?>
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