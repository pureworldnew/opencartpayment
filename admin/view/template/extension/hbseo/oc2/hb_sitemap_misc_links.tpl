		 <div class="table-responsive">
			<table class="table table-bordered table-hover">
			<thead>
				<tr>
				<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
				<td class="text-left">Links</td>
				<td class="text-center">Change Frequency</td>
				<td class="text-center">Priority</td>
				<td class="text-right">Date Added</td>
			  </tr>
			</thead>
			<tbody>
				<?php if ($records) { ?>
				<?php foreach ($records as $record) { ?>
				<tr>
				<td style="text-align: center;">
						<?php if ($record['selected']) { ?>
                             <input type="checkbox" name="selected[]" value="<?php echo $record['id']; ?>" checked="checked" />
                        <?php } else { ?>
                             <input type="checkbox" name="selected[]" value="<?php echo $record['id']; ?>" />
                         <?php } ?></td>
								
				<td class="text-left col-sm-6"><?php echo $record['link']; ?> </td>
				<td class="text-center col-sm-2"><?php echo $record['freq']; ?> </td>
				<td class="text-center col-sm-2"><?php echo $record['priority']; ?> </td>
				<td class="text-right col-sm-2"><?php echo $record['date_added']; ?></td>
				</tr>
				<?php } ?>
				<?php } else { ?>
				<tr><td class="text-center" colspan="5">No Records Found</td></tr>
				<?php } ?>				
			</tbody>
			</table>
		</div>
		
		<div class="row">
		  <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
		  <div class="col-sm-6 text-right"><?php echo $results; ?></div>
		</div>

