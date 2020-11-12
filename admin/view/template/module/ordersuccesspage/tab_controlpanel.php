<div class="container-fluid">
    <div class="row">
      <div class="col-md-3">
        <h5><strong><span class="required">* </span>Module status:</strong></h5>
        <span class="help"><i class="fa fa-info-circle"></i>&nbsp;Enable or disable the module.</span>
      </div>
      <div class="col-md-3">
        <select id="Checker" name="<?php echo $moduleName; ?>[Enabled]" class="form-control">
              <option value="yes" <?php echo (!empty($moduleData['Enabled']) && $moduleData['Enabled'] == 'yes') ? 'selected=selected' : '' ?>><?php echo $text_enabled; ?></option>
              <option value="no"  <?php echo (empty($moduleData['Enabled']) || $moduleData['Enabled']== 'no') ? 'selected=selected' : '' ?>><?php echo $text_disabled; ?></option>
        </select>
      </div>
    </div>
    <br />
</div>
<div class="tabbable tabs-left" id="module_tabs">

    <ul class="nav nav-tabs module-list">
        <li>
        	<a href="#maintext" data-toggle="tab"><i class="fa fa-comment-o"></i>&nbsp;&nbsp; Main info &amp; message</a>
        </li>
        <li>
        	<a href="#orderinfo" data-toggle="tab"><i class="fa fa-shopping-cart"></i>&nbsp;&nbsp; Order info</a>
        </li>
        <li>
        	<a href="#promoteproducts" data-toggle="tab"><i class="fa fa-tags"></i>&nbsp;&nbsp; Promote products</a>
        </li>
    </ul>
    <div class="tab-content module-settings">
        <?php require(DIR_APPLICATION.'view/template/module/'.$moduleNameSmall.'/tab_mainsettings.tpl'); ?>
        <?php require(DIR_APPLICATION.'view/template/module/'.$moduleNameSmall.'/tab_orderinfo.tpl'); ?>
        <?php require(DIR_APPLICATION.'view/template/module/'.$moduleNameSmall.'/tab_promoteproducts.tpl'); ?>
    </div>
</div>
        
<script type="text/javascript"><!--
// Show the EDITOR
<?php foreach ($languages as $language) { ?>
	$('#PageText_<?php echo $language['language_id']; ?>').summernote({
		height: 215
	});
<?php } ?>
</script>