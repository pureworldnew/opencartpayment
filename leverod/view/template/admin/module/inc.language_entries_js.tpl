html += '	<div class="panel-heading tabs">';
html += '		<ul class="nav nav-tabs" id="language-' + identifier + '">';		
				<?php foreach ($languages as $language) { ?>
html += '				<li><a href="#language<?php echo $language['language_id']; ?>-' + identifier + '" data-toggle="tab"><img src="<?php echo $language['image_path']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>';
				<?php } ?>
html += '		</ul>';
html += '	</div>';

html += '	<div class="lev-panel-body">';						
html += '		 <div class="tab-content lev-tab-content">';
				<?php foreach ($languages as $language) { ?>
html += '				<div class="tab-pane" id="language<?php echo $language['language_id']; ?>-' + identifier + '">';
							<?php foreach ($text_entries as $text_entry => $input_type) { ?>
html += '						<div class="form-group">';
html += '							<label class="col-sm-3 col-xs-12 control-label"><?php echo ${'entry_' . $text_entry}; ?> <span data-toggle="tooltip" title="<?php echo ${'help_' . $text_entry}; ?>" ></span></label>';
html += '							<div class="col-sm-9 col-xs-12">';
html += '								<input type="text" name="module[' + identifier + '][texts][<?php echo $language['language_id']; ?>][<?php echo $text_entry; ?>]" value="" placeholder="<?php echo !empty(${'placeholder_' . $text_entry})? ${'placeholder_' . $text_entry} : '' ; ?>" class="form-control" />';
html += '							</div>';
html += '						</div>';
							<?php } ?>
html += '				</div>';
					<?php } ?>
html += '		</div>';		
html += '	</div>';