<div class="table-responsive" id="form_delete_reponse_<?php echo $store_id; ?>">
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <td width="1" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected_del\']').prop('checked', this.checked);" /></td>
        <td class="text-right">cat</td>
        <td class="text-left">reference</td>
        <td class="text-left">cached</td>
        <td class="text-left">date</td>
      </tr>
    </thead>
    <tbody>
      <?php if ($cache_records) { ?>
      <?php foreach ($cache_records as $key=>$val) { ?>
      <tr>
        <td style="text-align: center;"><input type="checkbox" name="selected_del[]" value="<?php echo $val['cache_id']; ?>" /></td>
        <td class="text-right"><?php echo $val['cat']; ?></td>
        <td class="text-left"><?php echo $val['name']; ?></td>
        <td class="text-left"><?php echo $val['cached']; ?></td>
        <td class="text-left"><?php echo $val['date']; ?></td>
      </tr>
      <?php } ?>
      <?php } else { ?>
      <tr>
        <td class="center" colspan="5"><?php echo $text_error_no_cache; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
