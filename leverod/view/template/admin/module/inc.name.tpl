<?php if ( version_compare(VERSION, '2.0.1.0', '>=') ) { ?>
	<div class="form-group required">
		<label class="col-xs-3 control-label" for="input-name"><?php echo $entry_name; ?></label>
		<div class="col-xs-9">
			<input type="text" name="module[<?php echo $module['key']; ?>][name]" value="<?php echo $module['name']; ?>" id="input-name" class="form-control" />
			<?php if (!empty($error_name[$module['key']])) { ?>
				<div class="text-danger"><?php echo $error_name[$module['key']]; ?></div>
			<?php } ?>
		</div>
	</div>
<?php } ?>