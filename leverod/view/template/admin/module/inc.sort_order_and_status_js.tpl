html += '		<div class="form-group hidden-if-gte-2-0-0-0">';
html += '			<label class="col-xs-3 control-label"><?php echo $entry_sort_order; ?></label>';
html += '			<div class="col-xs-9">';
html += '				<input type="text" name="module[' + identifier + '][sort_order]" value="" class="form-control" />';
html += '			</div>';
html += '		</div>';


// On Oc 2.0.0.0 the module status is not saved together with the other module settings but in a separate variable:				

<?php if  (version_compare(VERSION, '2.0.0.0', '!=')) { ?>
	
html += '		<div class="form-group">';
html += '       	<label class="col-xs-3 control-label"><?php echo $entry_status; ?></label>';
html += '			<div class="col-xs-9">';
html += '				<select name="module[' + identifier + '][status]" class="form-control">';
html += '					<option value="1"><?php echo $text_enabled; ?></option>';
html += '					<option value="0"><?php echo $text_disabled; ?></option>';
html += '				</select>';
html += '			</div>';
html += '		</div>';

<?php } ?>