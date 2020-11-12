	
<?php if ( version_compare(VERSION, '2.0.1.0', '>=') ) { ?>

html += '   <div class="form-group required">';
html += '       <label class="col-xs-3 control-label"><?php echo $entry_name; ?></label>';
html += '       <div class="col-xs-9">';
html += '           <input type="text" name="module[' + identifier + '][name]" value="" class="form-control" />';
html += '       </div>';
html += '   </div>';

<?php } ?>
