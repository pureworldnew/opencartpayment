
<table class="table">
 <tr>
    <td class="col-xs-3">
    	<h5><span class="required">* </span><strong><?php echo $entry_code; ?></strong></h5>
        <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $entry_code_help; ?></span>
    </td>
    <td class="col-xs-9">
        <div class="col-xs-4">
        	<select id="Checker" name="<?php echo $moduleNameSmall; ?>[Enabled]" class="form-control">
                  <option value="yes" <?php echo (!empty($moduleData['Enabled']) && $moduleData['Enabled'] == 'yes') ? 'selected=selected' : '' ?>><?php echo $text_enabled; ?></option>
                  <option value="no"  <?php echo (empty($moduleData['Enabled']) || $moduleData['Enabled']== 'no') ? 'selected=selected' : '' ?>><?php echo $text_disabled; ?></option>
            </select>
        </div>
    </td>
 </tr>
 <tr>
     <td class="col-xs-3">
        <h5><span class="required">* </span><strong>Tracking Method</strong></h5>
        <span class="help"><i class="fa fa-info-circle"></i>&nbsp;Choose whether the module should track by session or by IP address.<br><br> <strong>Disclaimer: </strong>Tracking by IP may cause database performance bottleneck in very large scale stores!</span>
     </td>
     <td class="col-xs-9">
        <div class="col-xs-4">
            <select name="<?php echo $moduleNameSmall; ?>[Track_method]" class="form-control">
                  <option value="session" <?php echo (!empty($moduleData['Track_method']) && $moduleData['Track_method'] == 'session') ? 'selected=selected' : '' ?>>Track by session</option>
                  <option value="ip"  <?php echo (empty($moduleData['Track_method']) || $moduleData['Track_method']== 'ip') ? 'selected=selected' : '' ?>>Track by IP</option>
            </select>
        </div>
    </td>
 </tr>
</table>
<script>
$(function() {
    var $typeSelector = $('#Checker');
	var $toggleArea2 = $('#mainSettingsTab');
	 if ($typeSelector.val() === 'yes') {
			$toggleArea2.show();
        }
        else {
			$toggleArea2.hide();
        }
    $typeSelector.change(function(){
        if ($typeSelector.val() === 'yes') {
			$toggleArea2.show(300);
        }
        else {
			$toggleArea2.hide(300);
        }
    });
});
</script>
