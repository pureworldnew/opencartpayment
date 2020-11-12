
<div class="panel-heading tabs">
	<ul class="nav nav-tabs" id="language-<?php echo $module['identifier'] ?>">
		<?php foreach ($languages as $language) { ?>
			<li><a href="#language<?php echo $language['language_id']; ?>-<?php echo $module['identifier'] ?>" data-toggle="tab"><img src="<?php echo $language['image_path']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
		<?php } ?>
	</ul>
</div>

<div class="lev-panel-body">
	 <div class="tab-content lev-tab-content">

		<?php foreach ($languages as $language) { ?>
			<div class="tab-pane" id="language<?php echo $language['language_id']; ?>-<?php echo $module['identifier'] ?>">

				<?php foreach ($text_entries as $text_entry => $input_type) { ?>

					<div class="form-group">
						<label class="col-sm-3 col-xs-12 control-label"><?php echo ${'entry_' . $text_entry}; ?> <span data-toggle="tooltip" title="<?php echo ${'help_' . $text_entry}; ?>"></span></label>
						<div class="col-sm-9 col-xs-12">
							<input type="text" name="module[<?php echo $module['key']; ?>][texts][<?php echo $language['language_id']; ?>][<?php echo $text_entry; ?>]" value="<?php echo isset($module['texts'][$language['language_id']][$text_entry]) ? $module['texts'][$language['language_id']][$text_entry] : ''; ?>" placeholder="<?php echo !empty(${'placeholder_' . $text_entry})? ${'placeholder_' . $text_entry} : '' ; ?>" class="form-control" />
						</div>
					</div> 
					
				<?php } ?>
			</div>
		 <?php } ?>
	</div>	
</div>