<?php
/*
  Project: CSV Product Import
  Author : karapuz <support@ka-station.com>

  Version: 3 ($Revision: 51 $)

*/
?>
<?php echo $header; ?>

<style>
#service_line {
  width: 100%;
  background-color: #EFEFEF;
}

</style>

<div id="content">
<div class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
  <?php } ?>
</div>

<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>

<div class="box">
  <div class="heading">
    <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
   
      <div id="service_line">
      <table>
        <tr>
          <td><b>Full Version</b>: <?php echo $extension_version; ?>&nbsp;&nbsp;&nbsp;</td>
          <td><b>Author</b>: karapuz&nbsp;&nbsp;&nbsp;</td>
          <td><b>Contact Us</b>: <a href="mailto:support@ka-station.com">via email</a>&nbsp;&nbsp;&nbsp;</td>
          <td><a href="https://www.ka-station.com/index.php?route=information/contact" target="_blank">via secure form at www.ka-station.com</a>&nbsp;&nbsp;&nbsp;</td>
        </tr>
      </table>
      </div>

      <br />
      There is a direct link to access the import page <a href="<?php echo $import_page; ?>">CSV Product Import</a>.      
      <br /><br />
            
      <div id="tabs" class="htabs">
        <a href="#tab-general">General</a>
        <a href="#tab-separators">Separators</a>
      </div>
          
      <div id="tab-general">
      <table class="list">
        <thead> 
          <tr>
            <td class="left">Setting</td>
            <td>Value</td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="left">Script update interval in seconds (5-25)<span class="help">Reduce this value if you experience server connection issues during the import. Default value is 10.</span></td>
            <td class="left">
              <input type="text" name="ka_pi_update_interval" value="<?php echo $ka_pi_update_interval; ?>" />
            </td>
          </tr>
          
          <tr>
            <td class="left">Create new product options from the file
              <span class="help">If you enable this setting then new product options will be created otherwise they will be skipped.
              </span>
            </td>
            <td class="left">
              <input type="checkbox" name="ka_pi_create_options" value="Y" <?php if (!empty($ka_pi_create_options)) { ?> checked="checked" <?php } ?>" />
            </td>
          </tr>

          <tr>
            <td class="left">Enable product_id column in the column selection
            </td>
            <td class="left">
              <input type="checkbox" name="ka_pi_enable_product_id" value="Y" <?php if (!empty($ka_pi_enable_product_id)) { ?> checked="checked" <?php } ?>" />
            </td>
          </tr>

          <tr>
            <td class="left">Set status for new products
              <span class="help">This option is ignored if the status field is defined in the file</span>
            </td>
            <td class="left">
              <select name="ka_pi_status_for_new_products">
                <option value="enabled_gt_0" <?php if ($ka_pi_status_for_new_products == 'enabled_gt_0') { ?> selected="selected" <?php } ?>>'Enabled' for products with quantity &gt; 0</option>
                <option value="enabled" <?php if ($ka_pi_status_for_new_products == 'enabled') { ?> selected="selected" <?php } ?>>'Enabled' for all</option>
                <option value="disabled" <?php if ($ka_pi_status_for_new_products == 'disabled') { ?> selected="selected" <?php } ?>>'Disabled' for all</option>
              </select>
            </td>
          </tr>

          <tr>
            <td class="left">Set status for existing products
              <span class="help">This option is ignored if the status field is defined in the file</span>
            </td>
            <td class="left">
              <select name="ka_pi_status_for_existing_products">
                <option value="not_change" <?php if ($ka_pi_status_for_existing_products == 'not_change') { ?> selected="selected" <?php } ?>>Do not change status</option>
                <option value="enabled_gt_0" <?php if ($ka_pi_status_for_existing_products == 'enabled_gt_0') { ?> selected="selected" <?php } ?>>'Enabled' for products with quantity &gt; 0</option>
                <option value="enabled" <?php if ($ka_pi_status_for_existing_products == 'enabled') { ?> selected="selected" <?php } ?>>'Enabled' for all</option>
                <option value="disabled" <?php if ($ka_pi_status_for_existing_products == 'disabled') { ?> selected="selected" <?php } ?>>'Disabled' for all</option>
              </select>
            </td>
          </tr>

          <tr>
            <td class="left">Key fields
              <span class="help">
                These fields are required for each product record in the file unless you use 'product_id' for updating products.
              </span>
            </td>
            <td class="left">
              <div class="scrollbox">
                <?php $class = 'odd'; ?>
                <?php foreach ($key_fields as $field) { ?>
                <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                <div class="<?php echo $class; ?>">
                <?php if (in_array($field['field'], $ka_pi_key_fields)) { ?>
                  <input type="checkbox" name="ka_pi_key_fields[]" value="<?php echo $field['field']; ?>" checked="checked" />
                  <?php echo $field['name']; ?>
                <?php } else { ?>
                  <input type="checkbox" name="ka_pi_key_fields[]" value="<?php echo $field['field']; ?>" />
                  <?php echo $field['name']; ?>
                <?php } ?>
                </div>
                <?php } ?>
              </div>
              <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $this->language->get('text_select_all'); ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $this->language->get('text_unselect_all'); ?></a>
            </td>
          </tr>
          
<!-- static information below -->

          <?php if (empty($is_vqmod_available)) { ?>
            <tr>
              <td class="left" colspan="2">
                <div class="warning">
                VQMod is not found in your store.
                <br /><br />
                You can download VQMod for Opencart at the page:
                <a target="_blank" href="http://code.google.com/p/vqmod/downloads/">
                  http://code.google.com/p/vqmod/downloads/
                </a>
                <br />
                Instructions how to install the mod can be found at:
                <a target="_blank" href="http://code.google.com/p/vqmod/wiki/Install_OpenCart">
                  http://code.google.com/p/vqmod/wiki/Install_OpenCart
                </a>
                </div>
              </td>
            </tr>
          <?php } ?>

        </tbody>
      </table>
      </div>
      
      <div id="tab-separators">
      <table class="list">
        <thead> 
          <tr>
            <td class="left">Setting</td>
            <td>Value</td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="left">General separator for multiple values</td>
            <td class="left">
              <input type="text" name="ka_pi_general_separator" value="<?php echo $ka_pi_general_separator; ?>" />
            </td>
          </tr>
          <tr>
            <td class="left">Separator for multiple values in the <b>category</b> field<span class="help">Leave this parameter empty if you have one value in the cell per row</span></td>
            <td class="left">
              <input type="text" name="ka_pi_multicat_separator" value="<?php echo $ka_pi_multicat_separator; ?>" />
            </td>
          </tr>
          <tr>
            <td class="left">Separator for multiple values in the <b>related product</b> field<span class="help">Leave this parameter empty if you have one value in the cell per row</span></td>
            <td class="left">
              <input type="text" name="ka_pi_related_products_separator" value="<?php echo $ka_pi_related_products_separator; ?>" />
            </td>
          </tr>
          <tr>
            <td class="left">Separator for multiple values in the <b>product option</b> cell field<span class="help">Leave this parameter empty if you have one value in the cell per row</span></td>
            <td class="left">
              <input type="text" name="ka_pi_options_separator" value="<?php echo $ka_pi_options_separator; ?>" />
            </td>
          </tr>
          <tr>
            <td class="left">Separator for multiple values in the <b>additional image</b> field
              <span class="help">You can use \r and \n escape codes for defining a new line separator</span></td>
            <td class="left">
              <input type="text" name="ka_pi_image_separator" value="<?php echo $ka_pi_image_separator; ?>" />
            </td>
          </tr>
        </tbody>
      </table>      
      </div>
      
    </form>
  </div>
</div>

<script type="text/javascript"><!--

$(document).ready(function() {
  $('#tabs a').tabs();
});

//--></script>

<?php echo $footer; ?>