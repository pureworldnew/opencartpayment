<?php
/*
  Project: CSV Product Import
  Author : karapuz <support@ka-station.com>

  Version: 3 ($Revision: 71 $)

*/

?>
<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">

  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-input" data-toggle="tooltip" title="<?php echo $this->t('button_save'); ?>" class="btn btn-primary"><i class="fa fa-check-circle"></i> <?php echo $this->t('button_save'); ?></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $this->t('button_cancel'); ?>" class="btn btn-default"><i class="fa fa-reply"></i>  <?php echo $this->t('button_cancel'); ?></a>
      </div>
      <h1><i class="fa fa-puzzle-piece"></i> <?php echo $heading_title; ?></h1>
      <?php echo $ka_breadcrumbs; ?>
    </div>
  </div>
	<div class="container-fluid">
		<?php echo $ka_top; ?>
		
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-input">
			<div id="service_line">
				<table>
					<tr>
						<td><b>Full Version</b>: <?php echo $extension_version; ?>&nbsp;&nbsp;&nbsp;</td>
						<td><b>Author</b>: karapuz&nbsp;&nbsp;&nbsp;</td>
						<td><b>Contact Us</b>: <a href="mailto:support@ka-station.com">via email</a>&nbsp;&nbsp;&nbsp;</td>
						<td><a href="https://www.ka-station.com/index.php?route=information/contact" target="_blank">via secure form at www.ka-station.com</a>&nbsp;&nbsp;&nbsp;</td>
					</tr>
				</table>
				<br />
			</div>
			
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $this->t('General'); ?></a></li>
        <li><a href="#tab-separators" data-toggle="tab"><?php echo $this->t('Separators'); ?></a></li>
        <li><a href="#tab-optimization" data-toggle="tab"><?php echo $this->t('Optimization'); ?></a></li>
      </ul>
			
      <div class="tab-content">      
				<div class="tab-pane active" id="tab-general">			
					<table class="table table-striped table-bordered table-hover">
						<thead> 
							<tr>
								<td style="width: 360px" class="text-left">Setting</td>
								<td class="text-left">Value</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="left">Create new product options from the file
									<span class="help-block">If you enable this setting then new product options will be created otherwise they will be skipped.
									</span>
								</td>
								<td class="left">
									<input type="checkbox" name="ka_pi_create_options" value="Y" <?php if (!empty($ka_pi_create_options)) { ?> checked="checked" <?php } ?>" />
								</td>
							</tr>
							<tr>
								<td class="left">Generate SEO keyword for new products
									<span class="help-block">SEO keyword is generated when it is not defined in the file
									</span>
								</td>
								<td class="left">
									<input type="checkbox" name="ka_pi_generate_seo_keyword" value="Y" <?php if (!empty($ka_pi_generate_seo_keyword)) { ?> checked="checked" <?php } ?>" />
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
									<span class="help-block">This option is ignored if the status field is defined in the file</span>
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
									<span class="help-block">This option is ignored if the status field is defined in the file</span>
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
									<span class="help-block">
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
									<a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $this->t('text_select_all'); ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $this->t('text_unselect_all'); ?></a>
								</td>
							</tr>
							
						</tbody>
					</table>
				</div>
			
				<div class="tab-pane" id="tab-separators">
					<table class="table table-striped table-bordered table-hover">
						<thead> 
							<tr>
								<td style="width: 360px" class="text-left">Setting</td>
								<td class="text-left">Value</td>
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
								<td class="left">Separator for multiple values in the <b>category</b> field<span class="help-block">Leave this parameter empty if you have one value in the cell per row</span></td>
								<td class="left">
									<input type="text" name="ka_pi_multicat_separator" value="<?php echo $ka_pi_multicat_separator; ?>" />
								</td>
							</tr>
							<tr>
								<td class="left">Separator for multiple values in the <b>related product</b> field<span class="help-block">Leave this parameter empty if you have one value in the cell per row</span></td>
								<td class="left">
									<input type="text" name="ka_pi_related_products_separator" value="<?php echo $ka_pi_related_products_separator; ?>" />
								</td>
							</tr>
							<tr>
								<td class="left">Separator for multiple values in the <b>product option</b> cell field<span class="help-block">Leave this parameter empty if you have one value in the cell per row</span></td>
								<td class="left">
									<input type="text" name="ka_pi_options_separator" value="<?php echo $ka_pi_options_separator; ?>" />
								</td>
							</tr>
							<tr>
								<td class="left">Separator for multiple values in the <b>additional image</b> field
									<span class="help-block">You can use \r and \n escape codes for defining a new line separator</span></td>
								<td class="left">
									<input type="text" name="ka_pi_image_separator" value="<?php echo $ka_pi_image_separator; ?>" />
								</td>
							</tr>
						</tbody>
					</table>      
				</div>				
				
				<div class="tab-pane" id="tab-optimization">
					<table class="table table-striped table-bordered table-hover">
						<thead> 
							<tr>
								<td style="width: 360px" class="text-left">Setting</td>
								<td class="text-left">Value</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="left">Script update interval in seconds (5-25)<span class="help-block">Reduce this value if you experience server connection issues during the import. Default value is 10.</span></td>
								<td class="left">
									<input  type="text" name="ka_pi_update_interval" value="<?php echo $ka_pi_update_interval; ?>" />
								</td>
							</tr>
							<tr>
								<td class="left">Skip downloading images for existing files
								<span class="help-block">this option is applicable to image URLs only</span>
								</td>
								<td class="left">
									<input type="checkbox" name="ka_pi_skip_img_download" value="Y" <?php if (!empty($ka_pi_skip_img_download)) { ?> checked="checked" <?php } ?> />
								</td>
							</tr>							
							<tr>
								<td class="left">Enable compatibility with csv files generated by iOS applications</td>
								<td class="left">
									<input type="checkbox" name="ka_pi_enable_macfix" value="Y" <?php if (!empty($ka_pi_enable_macfix)) { ?> checked="checked" <?php } ?> />
								</td>
							</tr>
							<tr>
								<td class="left">Ignore letter-case and leading/trailing spaces in string comparison</td>
								<td class="left">
									<input type="checkbox" name="ka_pi_compare_as_is" value="Y" <?php if (!empty($ka_pi_compare_as_is)) { ?> checked="checked" <?php } ?> />
								</td>
							</tr>
						</tbody>
					</table>      
				</div>				
				
				
			</div>
		</form>			
  </div>
</div>

<?php echo $footer; ?>