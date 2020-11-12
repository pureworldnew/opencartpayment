<?php 
/*
  Project: CSV Product Export
  Author : karapuz <support@ka-station.com>

  Version: 4 ($Revision: 39 $)

*/

?>

<?php echo $header; ?>

<style type="text/css">
<!--

div.scroll {
  height: 200px;
  width: 100%;
  overflow: auto;
  border: 1px solid black;
  background-color: #ccc;
  padding: 8px;
}

a.download {
  font-size: 28px;
  align: justify;
  width: 100%;
}

.list td a.link {
  text-decoration: underline;
  color: blue;
}

#export_status {
  color: black;
}

.scrollbox {
	height: 150px;
	overflow: auto;
}

-->
</style>

<?php echo $column_left; ?>
<div id="content">

  <?php if (!empty($is_wrong_db)) { ?>

    <?php echo $this->t('Database is not compatible...'); ?>

  <?php } elseif ($params['step'] == 1) { ?>

		<div class="page-header">
			<div class="container-fluid">
				<div class="pull-right">
					<button type="submit" form="form-input" class="btn btn-primary"><?php echo $this->t('Next'); ?></button>
				</div>
				<h1><?php echo $heading_title; ?>: <?php echo $this->t('STEP 1 of 3'); ?></h1>
				<?php echo $ka_breadcrumbs; ?>
			</div>
		</div>

		<div class="container-fluid">
			<?php echo $ka_top; ?>
			
			<?php echo $this->t('This page allows you to export...'); ?><br /><br />
			
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-input">
				<input type="hidden" name="mode" value="" />

				<table class="table table-condensed table-striped table-hover">
					<tr>
						<td width="25%"><label class="control-label"><span data-toggle="popover" data-content="<?php echo htmlspecialchars($this->t('Profiles may store export p...')); ?>"><?php echo $this->t('Profile'); ?></span></label></td>
						<td colspan="2">
							<?php if (!empty($profiles)) { ?>
								<?php echo KaElements::showSelector("profile_id", $profiles, $params['profile_id'], 'style="width:300px;"'); ?>
								<input type="button" value="Load" onclick="javascript: loadProfile();" />
								<input type="button" value="Delete" onclick="javascript: deleteProfile();" />
							 <?php } else { ?>
								<?php echo $this->t('no profiles present'); ?>
							 <?php } ?>
						</td>
					</tr>
				</table>
			
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $this->t('General'); ?></a></li>
					<li><a href="#tab-advanced" data-toggle="tab"><?php echo $this->t('Advanced'); ?></a></li>
					<li><a href="#tab-extra" data-toggle="tab"><?php echo $this->t('Extra'); ?></a></li>
				</ul>
					
				<div class="tab-content">
					<div class="tab-pane active" id="tab-general">
						<table class="table table-condensed table-striped table-hover">
							<tr>
								<td width="25%"><label class="control-label"><span data-toggle="popover" data-content="This option sets 'Field Delimiter' and 'File Charset' to recommended values">File Settings</span></label></td>
								<td>
									<input style="vertical-align: middle;" type="radio" id="default_file_setting" name="file_settings" onclick="javascript: onFileSettings('custom');" value="custom" <?php if ($params['file_settings'] == 'custom') { ?> checked="checked" <?php } ?> /> <?php echo $this->t('custom'); ?>&nbsp;&nbsp;
									<input style="vertical-align: middle;" type="radio" name="file_settings" onclick="javascript: onFileSettings('msexcel');" value="msexcel" <?php if ($params['file_settings'] == 'msexcel') { ?> checked="checked" <?php } ?> /> <?php echo $this->t('best for opening in MS Excel'); ?>
								</td>
								<td width="50%">&nbsp;</td>
							</tr>
							<tr>
								<td width="25%"><label class="control-label"><span data-toggle="popover" data-content="<?php echo $this->t('the \'tab\' delimiter is reco...'); ?>"><?php echo $this->t('Field Delimiter'); ?></span></label></td>
								<td>
									<select name="delimiter" style="width:300px;" onchange="javascript: onFileSettingsChange();">
										<option <?php if ($params['delimiter'] == 't') { ?>selected="selected" <?php } ?>value="t"><?php echo $this->t('tab'); ?></option>
										<option <?php if ($params['delimiter'] == 's') { ?>selected="selected" <?php } ?>value="s"><?php echo $this->t('semicolon'); ?> ";"</option>
										<option <?php if ($params['delimiter'] == 'c') { ?>selected="selected" <?php } ?>value="c"><?php echo $this->t('comma'); ?> ","</option>
									</select>
								</td>
								<td width="50%">&nbsp;</td>
							</tr>
							<tr>
								<td width="25%"><label class="control-label"><span data-toggle="popover" data-content="<?php echo $this->t('You have to keep in mind th...'); ?>"><?php echo $this->t('File Charset'); ?></span></label></td>
								<td colspan="2">
									<input type="hidden" id="charset_option" name="charset_option" value="<?php echo $params['charset_option']; ?>" />
									<table width="500px">
										<tr id="predefined_charset_row" <?php if ($params['charset_option'] != 'predefined') { ?> style="display:none" <?php } ?>>
											<td width="280px">
												<?php echo KaElements::showSelector("charset", $charsets, $params['charset'], 'style="width:300px;" onchange="javascript: onFileSettingsChange();"'); ?>
												<br/><a href="javascript: void(0);" onclick="javascript: activateCharset('custom');">make editable</a>
											</td>
											<td>&nbsp;</td>
										</tr>
										<tr id="custom_charset_row" <?php if ($params['charset_option'] == 'predefined') { ?> style="display:none" <?php } ?>>
											<td width="250px">
												<input type="text" style="width: 240px" id="custom_charset" name="custom_charset" value="<?php echo $params['charset']; ?>" />
												<br /><a href="javascript: void(0);" onclick="javascript: activateCharset('predefined');"><?php echo $this->t('select from predefined values'); ?></a>
											</td>
											<td>&nbsp;</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td width="25%"><label class="control-label"><?php echo $this->t('Store'); ?></label></td>
								<td>              
									<select name="store_id" style="width:300px;">
										<?php foreach($stores as $store) { ?>
											<option <?php if ($store['store_id'] == $params['store_id']) { ?>selected="selected" <?php } ?>value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
										<?php } ?>
									</select>
								</td>
								<td width="50%">&nbsp;</td>
							</tr>
							<tr>
								<td width="25%"><label class="control-label"><?php echo $this->t('Language'); ?></label></td>
								<td>
									<?php if (count($languages) > 1) { ?>
										<select name="language_id" style="width:300px;">
											<?php foreach ($languages as $language) { ?>
												<option value="<?php echo $language['language_id']; ?>" <?php if ($language['language_id'] == $params['language_id']) { ?>selected="selected"<?php } ?>><?php echo $language['name']; ?></option>
											<?php } ?>
										</select>
									<?php } else { ?>
										<?php $language = reset($languages); echo $language['name']; ?>
										<input type="hidden" name="language_id" value="<?php echo $language['language_id']; ?>" />
									<?php } ?>
								</td>
								<td width="50%">&nbsp;</td>
							</tr>
							
							<tr>
								<td width="25%"><label class="control-label"><?php echo $this->t('Products From Categories'); ?></label></td>
								<td colspan="2">
									<input type="radio" name="products_from_categories" onclick="javascript: showCategories('all');" value="all" <?php if ($params['products_from_categories'] == 'all') { ?> checked="checked" <?php } ?> /><?php echo $this->t('All'); ?>&nbsp;&nbsp;
									<input type="radio" name="products_from_categories" onclick="javascript: showCategories('selected');" value="selected" <?php if ($params['products_from_categories'] == 'selected') { ?> checked="checked" <?php } ?> /><?php echo $this->t('Selected'); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="checkbox" id="include_subcategories" name="include_subcategories" value="Y" <?php if (!empty($params['include_subcategories'])) { ?>checked="checked" <?php } ?> />&nbsp;<?php echo $this->t('Include Subcategories'); ?>
								</td>
							</tr>
							<tr id="export_selected_categories" <?php if ($params['products_from_categories'] != 'selected') { ?>style="display:none" <?php } ?>>
								<td width="25%"><label class="control-label"><?php echo $this->t('Categories'); ?></label></td>
								<td colspan="2">
										<div class="well well-sm scrollbox">
											<?php $class = 'odd'; ?>
											<?php foreach ($categories as $category) { ?>
											<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
											<div class="<?php echo $class; ?>">
												<?php if (in_array($category['category_id'], $params['category_ids'])) { ?>
												<input type="checkbox" name="category_ids[]" value="<?php echo $category['category_id']; ?>" checked="checked" />
												<?php echo $category['name']; ?>
												<?php } else { ?>
												<input type="checkbox" name="category_ids[]" value="<?php echo $category['category_id']; ?>" />
												<?php echo $category['name']; ?>
												<?php } ?>
											</div>
											<?php } ?>
										</div>
										<a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $this->t('Select All'); ?></a> / <a onclick="$(this).parent().find(':checkbox')..prop('checked', false);"><?php echo $this->t('Unselect All'); ?></a>
								</td>
							</tr>
							<tr>
								<td width="25%"><label class="control-label"><?php echo $this->t('Products From Manufacturers'); ?></label></td>
								<td colspan="2">
										<input type="radio" name="products_from_manufacturers" onclick="javascript: showManufacturers('all');" value="all" <?php if ($params['products_from_manufacturers'] == 'all') { ?> checked="checked" <?php } ?> /><?php echo $this->t('All'); ?>&nbsp;&nbsp;
										<input type="radio" name="products_from_manufacturers" onclick="javascript: showManufacturers('selected');" value="selected" <?php if ($params['products_from_manufacturers'] == 'selected') { ?> checked="checked" <?php } ?> /><?php echo $this->t('Selected'); ?>
								</td>
							</tr>
							<tr id="export_selected_manufacturers" <?php if ($params['products_from_manufacturers'] != 'selected') { ?>style="display:none" <?php } ?>>
								<td width="25%"><label class="control-label"><?php echo $this->t('Manufacturers'); ?></label></td>
								<td colspan="2">
										<div class="well well-sm scrollbox">
											<?php $class = 'odd'; ?>
											<?php foreach ($manufacturers as $manufacturer) { ?>
											<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
											<div class="<?php echo $class; ?>">
												<?php if (in_array($manufacturer['manufacturer_id'], $params['manufacturer_ids'])) { ?>
												<input type="checkbox" name="manufacturer_ids[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" checked="checked" />
												<?php echo $manufacturer['name']; ?>
												<?php } else { ?>
												<input type="checkbox" name="manufacturer_ids[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" />
												<?php echo $manufacturer['name']; ?>
												<?php } ?>
											</div>
											<?php } ?>
										</div>
										<a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $this->t('Select All'); ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $this->t('Unselect All'); ?></a>
								</td>
							</tr>
						</table>
					</div>

					<div class="tab-pane" id="tab-advanced">
						<table class="table table-condensed table-striped table-hover">
							<tr>
								<td width="25%"><label class="control-label"><?php echo $this->t('Category Separator'); ?></label></td>
								<td colspan="2">
									<input type="text" name="cat_separator" maxlength="8" size="8" value="<?php echo $params['cat_separator']; ?>" />
								</td>
							</tr>
							<tr>
								<td width="25%"><label class="control-label"><?php echo $this->t('Image Path'); ?></label></td>
								<td>
									<select name="image_path" style="width:300px;">
										<option value="path" <?php if ($params['image_path'] == 'path') { ?>selected="selected"<?php } ?>><?php echo $this->t('Server Path'); ?></option>
										<option value="url" <?php if ($params['image_path'] == 'url') { ?>selected="selected"<?php } ?>><?php echo $this->t('URL'); ?></option>
									</select>                
								</td>
								<td width="50%">&nbsp;</td>
							</tr>
							<tr>
								<td width="25%"><label class="control-label"><span data-toggle="popover" data-content="<?php echo $this->t('it may be used when the exp...'); ?>"><?php echo $this->t('Copy the exported file to'); ?></span></label></td>
								<td colspan="2">
									<?php echo $store_root_dir . DIRECTORY_SEPARATOR; ?> <input type="text" name="copy_path" value="<?php echo $params['copy_path'];?>" /><br />
								</td>
							</tr>
							<tr>
								<td width="25%"><label class="control-label"><span data-toggle="popover" data-content="This is just a tip on how to configure automatic export. It is not an option."><?php echo $this->t('Automatic export schedule'); ?></span></label></td>
								<td colspan="2">
									You can schedule periodical export with the '<a href="http://www.opencart.com/index.php?route=extension/extension/info&extension_id=21452" target="_blank">Task Scheduler</a>' extension.
								</td>
							</tr>
						</table>
					</div>
					
					<div class="tab-pane" id="tab-extra">
						<table class="table table-condensed table-striped table-hover">
							<tr>
								<td width="25%"><label class="control-label"><?php echo $this->t('Currency'); ?></label></td>
								<td>
									<select name="currency" style="width:300px;">
										<?php foreach ($currencies as $cur) { ?>
											<option value="<?php echo $cur['code']; ?>" <?php if ($cur['code'] == $params['currency']['code']) { ?>selected="selected"<?php } ?>><?php echo $cur['title']; ?></option>
										<?php } ?>
									</select>
								</td>
								<td width="50%">&nbsp;</td>
							</tr>
							<tr>
								<td width="25%"><label class="control-label"><?php echo $this->t('Apply Taxes to Prices'); ?></label></td>
								<td>
									<input type="checkbox" id="apply_taxes" name="apply_taxes" value="Y" <?php if (!empty($params['apply_taxes'])) { ?>checked="checked" <?php } ?> onclick="javascript: applyTaxesChanged();" />
								</td>
								<td width="50%">&nbsp;</td>
							</tr>
							<?php if (!empty($geo_zones)) { ?>
								<tr id="geo_zone_select" <?php if (empty($params['apply_taxes'])) { ?> style="display: none" <?php } ?>>
									<td width="25%"><?php echo $this->t('Use Taxes for Geo Zone'); ?></td>
									<td>
										<select name="geo_zone_id" style="width:300px;">
											<?php foreach($geo_zones as $gz) { ?>
												<option <?php if ($gz['geo_zone_id'] == $params['geo_zone_id']) { ?>selected="selected" <?php } ?>value="<?php echo $gz['geo_zone_id']; ?>"><?php echo $gz['name']; ?></option>
											<?php } ?>
										</select>
									</td>
									<td width="50%">&nbsp;</td>
								</tr>
							<?php } ?>         

							<tr>
								<td width="25%"><label class="control-label"><span data-toggle="popover" data-content="<?php echo $this->t('main product price will be ...'); ?>"><?php echo $this->t('Use special/discounted price'); ?></span></label></td>
								<td>
									<input type="checkbox" id="use_special_price" name="use_special_price" value="Y" <?php if (!empty($params['use_special_price'])) { ?>checked="checked" <?php } ?> onclick="javascript: useSpecialPriceChanged();" />
								</td>
								<td width="50%">&nbsp;</td>
							</tr>
							
							<?php if (!empty($customer_groups)) { ?>
								<tr id="customer_group_select" <?php if (empty($params['use_special_price'])) { ?> style="display: none" <?php } ?>>
									<td width="25%"><?php echo $this->t('Use Prices for Customer Group'); ?></td>
									<td>
										<select name="customer_group_id" style="width:300px;">
											<?php foreach($customer_groups as $cg) { ?>
												<option <?php if ($cg['customer_group_id'] == $params['customer_group_id']) { ?>selected="selected" <?php } ?>value="<?php echo $cg['customer_group_id']; ?>"><?php echo $cg['name']; ?></option>
											<?php } ?>
										</select>
									</td>
									<td width="50%">&nbsp;</td>
								</tr>
							<?php } ?>

							<tr>
								<td width="25%"><label class="control-label"><span data-toggle="popover" data-content="the checkbox allows to skip products with 'disabled' status"><?php echo $this->t('Exclude inactive products'); ?></span></label></td>
								<td>
									<input type="checkbox" id="exclude_inactive" name="exclude_inactive" value="Y" <?php if (!empty($params['exclude_inactive'])) { ?>checked="checked" <?php } ?>  />
								</td>
								<td width="50%">&nbsp;</td>
							</tr>

							<tr>
								<td width="25%"><label class="control-label"><?php echo $this->t('Exclude products with zero quantity'); ?></label></td>
								<td>
									<input type="checkbox" id="exclude_zero_qty" name="exclude_zero_qty" value="Y" <?php if (!empty($params['exclude_zero_qty'])) { ?>checked="checked" <?php } ?>  />
								</td>
								<td width="50%">&nbsp;</td>
							</tr>
							<tr>
								<td width="25%"><label class="control-label"><?php echo $this->t('Export weight/length units separately'); ?></label></td>
								<td>
									<input type="checkbox" id="separate_units" name="separate_units" value="Y" <?php if (!empty($params['separate_units'])) { ?>checked="checked" <?php } ?>  />
								</td>
								<td width="50%">&nbsp;</td>
							</tr>							
						</table>
					</div>
				</div>
			</form>
		</div>

<script type="text/javascript"><!--

function applyTaxesChanged() {
  if ($('#apply_taxes').prop('checked')) {
    $('#geo_zone_select').fadeIn(300);
        
  } else {
    $('#geo_zone_select').fadeOut(300);
  }
}

function useSpecialPriceChanged() {

  if ($('#use_special_price').prop('checked')) {
    $('#customer_group_select').fadeIn(300);
        
  } else {
    $('#customer_group_select').fadeOut(300);
  }
}

function onFileSettingsChange() {
	$('#default_file_setting').prop('checked', true);
}

function onFileSettings(value) {

	if (value == 'msexcel') {
		$('select[name=delimiter]').val('t');
		$('select[name=charset]').val('UTF-16LE');
		activateCharset('predefined');
	}
}

function showCategories(id) {

	var delay = 300;

  if (id == 'all') {
    $('#export_selected_categories').fadeOut(delay);
  } else if (id == 'selected') {
    $('#export_selected_categories').fadeIn(delay);
  }
}

function showManufacturers(id) {
	var delay = 300;

  if (id == 'all') {
    $('#export_selected_manufacturers').fadeOut(delay);
  } else if (id == 'selected') {
    $('#export_selected_manufacturers').fadeIn(delay);
  }
}


function activateCharset(id) {
  if (id == 'predefined') {
    $('#predefined_charset_row').show();
    $('#custom_charset_row').hide();
    $('#charset_option').val('predefined');

  } else if (id == 'custom') {
    $('#predefined_charset_row').hide();
    $('#custom_charset_row').show();
    $('#charset_option').val('custom');
    onFileSettingsChange();
  }  
}

function loadProfile() {

  $("#form-input input[name='mode']").prop('value', 'load_profile');
  $("#form-input").submit();
}

function deleteProfile() {

  $("#form-input input[name='mode']").prop('value', 'delete_profile');
  $("#form-input").submit();
}

$(document).ready(function() {

	$('[data-toggle=\'popover\']').popover({
			html: true,
			trigger:'click hover focus',
			delay: {show:"300", "hide": 1500}
	});

});

//--></script> 

  <?php } elseif ($params['step'] == 2) { ?>

		<div class="page-header">
			<div class="container-fluid">
				<div class="pull-right">
					<button type="submit" form="form-input" data-toggle="tooltip" title="<?php echo $this->t('Next'); ?>" class="btn btn-primary"><?php echo $this->t('Next'); ?></button>
					<a onclick="location='<?php echo $back_action; ?>'" class="btn btn-default"><span><?php echo $this->t('Cancel'); ?></span></a>
				</div>
				<h1><?php echo $heading_title; ?>: <?php echo $this->t('STEP 2 of 3'); ?></h1>
				<?php echo $ka_breadcrumbs; ?>
			</div>
		</div>

		<div class="container-fluid">
			<?php echo $ka_top; ?>
  
    	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-input">

        <input type="hidden" name="mode" value="" />
    
				<table width="300px" class="table table-condensed table-striped table-hover">
					<tr>
						<td width="100px">Profile</td>
						<td width="150px">
							<?php
							$params['profile_id'] 	= isset($params['profile_id']) 		? $params['profile_id'] 	: '';
							$params['profile_name'] = isset($params['profile_name']) 	? $params['profile_name'] 	: '';
							?>
							<input type="hidden" name="profile_id" value="<?php echo $params['profile_id']; ?>" />
							<input type="text" name="profile_name" value="<?php echo $params['profile_name']; ?>" />
						</td>
						<td>
							<input type="button" value="Save" onclick="javascript: saveProfile();" />
						</td>
					</tr>
				</table>
        
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $this->t('General'); ?></a></li>
					<li><a href="#tab-attributes" data-toggle="tab"><?php echo $this->t('Attributes'); ?></a></li>
					<li><a href="#tab-filters" data-toggle="tab"><?php echo $this->t('Filters'); ?></a></li>
					<li><a href="#tab-options" data-toggle="tab"><?php echo $this->t('Options'); ?></a></li>
					<li><a href="#tab-discounts" data-toggle="tab"><?php echo $this->t('Discounts'); ?></a></li>
					<li><a href="#tab-specials" data-toggle="tab"><?php echo $this->t('Specials'); ?></a></li>
					<li><a href="#tab-reward_points" data-toggle="tab"><?php echo $this->t('Reward Points'); ?></a></li>
				</ul>

	      <div class="tab-content">
  		    <div class="tab-pane active" id="tab-general">
  		    
						<?php echo $this->t('Check fields to include the...'); ?><br /><br />
		        <table class="table table-condensed table-condensed table-striped table-hover">
							<thead>
								<tr>
									<td class="left" width="25%"><?php echo $this->t('Product Field'); ?></td>
									<td><?php echo $this->t('Column in File'); ?></td>
									<td>Include <br />(<a class="link" href="javascript: void(0);" onclick="javascript: $('input[name^=&quot;columns[general]&quot;]:checkbox').prop('checked', true);"><?php echo $this->t('all'); ?></a>
									/ <a class="link" href="javascript: void(0);" onclick="javascript: $('[name^=&quot;columns[general]&quot;]').not('[disabled=&quot;disabled&quot;]').prop('checked', false);"><?php echo $this->t('none'); ?></a>)</td>
									<td width="50%"><?php echo $this->t('Notes'); ?></td>
								</tr>
							</thead>

							<tbody>
							<?php foreach($general as $k => $v) { 
								if (!empty($columns['general'][$v['field']]['column'])) {
									$v['column'] = $columns['general'][$v['field']]['column'];
								} else {
									$v['column'] = $v['field'];
								}            
							?>
								<tr>
									<td width="25%"><?php echo $v['name'] ?>
										<?php if (!empty($v['required'])) { ?><span class="required">*</span><?php } ?>
									</td>
									<td>
										<input type="input" value="<?php echo $v['column']; ?>" name="columns[general][<?php echo $v['field']; ?>][column]" />
									</td>
									<td>
										<input type="checkbox" value="Y" name="columns[general][<?php echo $v['field']; ?>][checked]" 
											<?php if (!empty($v['required'])) { ?> disabled="disabled" <?php } ?> 
											<?php if (!empty($v['required']) || !empty($columns['general'][$v['field']]['checked'])) { ?> checked="checked" <?php } ?> 
										/>
									</td>
									<td width="50%"><span class="help"><?php echo $v['descr']; ?></span></td>
								</tr>
							<?php } ?>
							</tbody>
		        </table>
      		</div>

	        <div class="tab-pane" id="tab-attributes">
						<?php echo $this->t('Check attributes to include...'); ?><br /><br />
						<table class="table table-condensed table-striped table-hover">
							<thead>
								<tr>
									<td class="left" width="25%"><?php echo $this->t('Atribute Name'); ?></td>
									<td><?php echo $this->t('Column in File'); ?></td>
									<td><?php echo $this->t('Include'); ?> <br />(<a class="link" href="javascript: void(0);" onclick="javascript: $('[name^=&quot;columns[attributes]&quot;]').prop('checked', true);"><?php echo $this->t('all'); ?></a>
									/ <a class="link" href="javascript: void(0);" onclick="javascript: $('[name^=&quot;columns[attributes]&quot;]').not('[disabled=&quot;disabled&quot;]').prop('checked', false);"><?php echo $this->t('none'); ?></a>)</td>
									<td width="5%"><?php echo $this->t('Attribute Group'); ?></td>
								</tr>
							</thead>

							<tbody>
							<?php foreach($attributes as $k => $v) { 
								if (!empty($columns['attributes'][$v['attribute_id']]['column'])) {
									$v['column'] = $columns['attributes'][$v['attribute_id']]['column'];
								} else {
									$v['column'] = 'attribute:' . $v['name'];
								}
							?>
								<tr>
									<td width="25%"><?php echo $v['name'] ?></td>
									<td>
										<input type="text" value="<?php echo $v['column']; ?>" name="columns[attributes][<?php echo $v['attribute_id']; ?>][column]" />
									</td>
									<td>
										<input type="checkbox" value="Y" name="columns[attributes][<?php echo $v['attribute_id']; ?>][checked]" 
											<?php if (!empty($columns['attributes'][$v['attribute_id']]['checked'])) { ?> checked="checked" <?php } ?> 
										/>
									</td>
									<td width="50%"><?php echo $v['attribute_group']; ?></td>
								</tr>
							<?php } ?>
							</tbody>
						</table>
					</div>
      
					<div class="tab-pane" id="tab-filters">
						<?php echo $this->t('Check filters to include th...'); ?><br /><br />
						<table class="table table-condensed table-striped table-hover">
							<thead>
								<tr>
									<td class="left" width="25%"><?php echo $this->t('Filter Group'); ?></td>
									<td><?php echo $this->t('Column in File'); ?></td>
									<td><?php echo $this->t('Include'); ?> <br />(<a class="link" href="javascript: void(0);" onclick="javascript: $('[name^=&quot;columns[filter_groups]&quot;]').prop('checked', true);"><?php echo $this->t('all'); ?></a>
									/ <a class="link" href="javascript: void(0);" onclick="javascript: $('[name^=&quot;columns[filter_groups]&quot;]').not('[disabled=&quot;disabled&quot;]').prop('checked', false);"><?php echo $this->t('none'); ?></a>)</td>
									<td width="5%">&nbsp;</td>
								</tr>
							</thead>

							<tbody>
							<?php foreach($filter_groups as $k => $v) { ?>           
								<tr>
									<td width="25%"><?php echo $v['name'] ?></td>
									<td>
										<?php echo $v['name']; ?>
										<input type="hidden" value="<?php echo $v['name']; ?>" name="columns[filter_groups][<?php echo $v['filter_group_id']; ?>][name]" />
									</td>
									<td>
										<input type="checkbox" value="Y" name="columns[filter_groups][<?php echo $v['filter_group_id']; ?>][checked]" 
											<?php if (!empty($columns['filter_groups'][$v['filter_group_id']]['checked'])) { ?> checked="checked" <?php } ?> 
										/>
									</td>
									<td width="50%">&nbsp;</td>
								</tr>
							<?php } ?>
							</tbody>
						</table>
					</div>
      
					<div class="tab-pane" id="tab-options">
						<?php echo $this->t('Check options to include th...'); ?><br /><br />
						<table class="table table-condensed table-striped table-hover">
							<thead>
								<tr>
									<td class="left" width="25%"><?php echo $this->t('Option Name'); ?></td>
									<td><?php echo $this->t('Column in File'); ?></td>
									<td><?php echo $this->t('Include'); ?> <br />(<a class="link" href="javascript: void(0);" onclick="javascript: $('[name^=&quot;columns[options]&quot;]').prop('checked', true);"><?php echo $this->t('all'); ?></a>
									/ <a class="link" href="javascript: void(0);" onclick="javascript: $('[name^=&quot;columns[options]&quot;]').not('[disabled=&quot;disabled&quot;]').prop('checked', false);"><?php echo $this->t('none'); ?></a>)</td>
									<td width="50%"><?php echo $this->t('Type'); ?></td>
								</tr>
							</thead>

							<tbody>
							<?php foreach($options as $k => $v) { ?>            
								<tr>
									<td width="25%"><?php echo $v['name'] ?></td>
									<td>
										<?php echo $v['name']; ?>
										<input type="hidden" value="<?php echo $v['name']; ?>" name="columns[options][<?php echo $v['option_id']; ?>][name]" />
									</td>
									<td>
										<input type="checkbox" value="Y" name="columns[options][<?php echo $v['option_id']; ?>][checked]" 
											<?php if (!empty($columns['options'][$v['option_id']]['checked'])) { ?> checked="checked" <?php } ?> 
										/>
									</td>
									<td width="45%"><?php echo $v['type']; ?></td>
								</tr>
							<?php } ?>

						</table>
					</div>

					<div class="tab-pane" id="tab-discounts">
						<?php echo $this->t('Check discounts to include ...'); ?><br /><br />
						<table class="table table-condensed table-striped table-hover">
							<thead>
								<tr>
									<td class="left" width="25%"><?php echo $this->t('Field'); ?></td>
									<td><?php echo $this->t('Column in File'); ?></td>
									<td><?php echo $this->t('Include'); ?> <br />(<a class="link" href="javascript: void(0);" onclick="javascript: $('[name^=&quot;columns[discounts]&quot;]').prop('checked', true);"><?php echo $this->t('all'); ?></a>
									/ <a class="link" href="javascript: void(0);" onclick="javascript: $('[name^=&quot;columns[discounts]&quot;]').not('[disabled=&quot;disabled&quot;]').prop('checked', false);"><?php echo $this->t('none'); ?></a>)</td>
									<td width="50%"></td>
								</tr>
							</thead>

							<tbody>
							<?php foreach($discounts as $k => $v) { ?>
								<tr>
									<td width="25%"><?php echo $v['name'] ?></td>
									<td>
										<?php echo $v['name']; ?>
										<input type="hidden" value="<?php echo $v['name']; ?>" name="columns[discounts][<?php echo $v['field']; ?>][name]" />
									</td>
									<td>
										<input type="checkbox" value="Y" name="columns[discounts][<?php echo $v['field']; ?>][checked]" 
											<?php if (!empty($columns['discounts'][$v['field']]['checked'])) { ?> checked="checked" <?php } ?> 
										/>
									</td>
									<td width="50%">&nbsp;</td>
								</tr>
							<?php } ?>
							</tbody>
						</table>
					</div>

	        <div class="tab-pane" id="tab-specials">
						<?php echo $this->t('Check special prices to inc...'); ?><br /><br />
						<table class="table table-condensed table-striped table-hover">
							<thead>
								<tr>
									<td class="left" width="25%"><?php echo $this->t('Field'); ?></td>
									<td><?php echo $this->t('Column in File'); ?></td>
									<td><?php echo $this->t('Include'); ?> <br />(<a class="link" href="javascript: void(0);" onclick="javascript: $('[name^=&quot;columns[specials]&quot;]').prop('checked', true);"><?php echo $this->t('all'); ?></a>
									/ <a class="link" href="javascript: void(0);" onclick="javascript: $('[name^=&quot;columns[specials]&quot;]').not('[disabled=&quot;disabled&quot;]').prop('checked', false);"><?php echo $this->t('none'); ?></a>)</td>
									<td width="50%"></td>
								</tr>
							</thead>

							<tbody>
							<?php foreach($specials as $k => $v) { ?>
								<tr>
									<td width="25%"><?php echo $v['name'] ?></td>
									<td>
										<?php echo $v['name']; ?>
										<input type="hidden" value="<?php echo $v['name']; ?>" name="columns[specials][<?php echo $v['field']; ?>][name]" />
									</td>
									<td>
										<input type="checkbox" value="Y" name="columns[specials][<?php echo $v['field']; ?>][checked]" 
											<?php if (!empty($columns['specials'][$v['field']]['checked'])) { ?> checked="checked" <?php } ?> 
										/>
									</td>
									<td width="50%">&nbsp;</td>
								</tr>
							<?php } ?>
							</tbody>
						</table>
					</div>

					<div class="tab-pane" id="tab-reward_points">
						<?php echo $this->t('Check reward points to incl...'); ?><br /><br />
						<table class="table table-condensed table-striped table-hover">
							<thead>
								<tr>
									<td class="left" width="25%"><?php echo $this->t('Field'); ?></td>
									<td><?php echo $this->t('Column in File'); ?></td>
									<td><?php echo $this->t('Include'); ?> <br />(<a class="link" href="javascript: void(0);" onclick="javascript: $('[name^=&quot;columns[reward_points]&quot;]').prop('checked', true);"><?php echo $this->t('all'); ?></a>
									/ <a class="link" href="javascript: void(0);" onclick="javascript: $('[name^=&quot;columns[reward_points]&quot;]').not('[disabled=&quot;disabled&quot;]').prop('checked', false);"><?php echo $this->t('none'); ?></a>)</td>
									<td width="50%"></td>
								</tr>
							</thead>

							<tbody>
							<?php foreach($reward_points as $k => $v) { ?>
								<tr>
									<td width="25%"><?php echo $v['name'] ?></td>
									<td>
										<?php echo $v['name']; ?>
										<input type="hidden" value="<?php echo $v['name']; ?>" name="columns[reward_points][<?php echo $v['field']; ?>][name]" />
									</td>
									<td>
										<input type="checkbox" value="Y" name="columns[reward_points][<?php echo $v['field']; ?>][checked]" 
											<?php if (!empty($columns['reward_points'][$v['field']]['checked'])) { ?> checked="checked" <?php } ?> 
										/>
									</td>
									<td width="50%">&nbsp;</td>
								</tr>
							<?php } ?>
							</tbody>
						</table>
					</div>
  			</div>
			</form>
		</div>		
<script type="text/javascript"><!--

$(document).ready(function() {
  $('#tabs a').tabs();
});

function saveProfile() {

  $("#form-input input[name='mode']").prop('value', 'save_profile');
  $("#form-input").submit();
}

//--></script> 

  <?php } elseif ($params['step'] == 3) { ?>

		<div class="page-header">  
				<div class="container-fluid">
						<div class="pull-right" id="buttons_in_progress">
							<button class="btn btn-primary" onclick="javascript: ka_stop_export();" class="button"><?php echo $this->t('Stop'); ?></button>
						</div>
						<div class="pull-right" id="buttons_stopped" style="display: none">
							<button class="btn btn-primary" onclick="javascript: ka_continue_export();" class="button"><?php echo $this->t('Continue'); ?></button>
						</div>
						<div class="pull-right" id="buttons_completed" style="display: none">
							<button class="btn btn-primary" onclick="location='<?php echo $done_action; ?>'" class="button"><?php echo $this->t('Done'); ?></button>
						</div>							
						<h1><?php echo $heading_title; ?>: STEP 3 of 3</h1>
						<?php echo $ka_breadcrumbs; ?>
				</div>
		</div>

		<div class="container-fluid">
			<?php echo $ka_top; ?>
  
      <h2 id="export_status"><?php echo $this->t('Export is in progress'); ?></h2>
			<table class="table table-striped table-hover">
				<tr>
					<td colspan="2"><?php echo $this->t('The export statistics updat...'); ?></td>
				</tr>
				<tr>
					<td width="25%"><?php echo $this->t('Completion at'); ?></td>
					<td id="completion_at">0%</td>
				</tr>
				<tr>
					<td width="25%"><?php echo $this->t('File Size'); ?></td>
					<td id="file_size">0</td>
				</tr>
				<tr>
					<td width="25%"><?php echo $this->t('Time Passed'); ?></td>
					<td id="time_passed">0</td>
				</tr>
				<tr>
					<td width="25%"><?php echo $this->t('Lines Processed'); ?></td>
					<td id="lines_processed">0</td>
				</tr>
				<tr>
					<td width="25%"><?php echo $this->t('Products Processed'); ?></td>
					<td id="products_processed">0</td>
				</tr>

				<tr id="download_row" style="display: none">
					<td colspan="2" style="text-align: center">
						<a class="download" href="<?php echo $download_link; ?>"><?php echo $this->t('download'); ?></a>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<h4><?php echo $this->t('Export messages'); ?>:</h4>
						<div class="scroll" id="scroll">
						</div>
						<input type="checkbox" id="autoscroll" checked="checked" /> <?php echo $this->t('Autoscrolling'); ?>
					</td>
				</tr>
    	</table>
  	</div>

<script type="text/javascript"><!--

var ka_page_url = '<?php echo $page_url; ?>';
var ka_timer    = null;

/*
  possible statuses
    in_progress -
    completed   -
    stopped     -
*/
var ka_export_status = 'in_progress';

/*
  possible ajax statuses:
    not_started -
    in_progress -
*/
var ka_ajax_status   = 'not_started';

function ka_update_interface(status) {
  $("#buttons_in_progress").hide();
  $("#buttons_completed").hide();
  $("#buttons_stopped").hide();

  if (status == 'stopped') {
    $("#export_status").html("<?php echo $this->t('Export stopped'); ?>");
    $("#buttons_stopped").show();

  } else if (status == 'completed') {
    $("#buttons_completed").show();
    $("#export_status").html("<?php echo $this->t('Export is complete!'); ?>");
    $("#download_row").show();
  
  } else if (status == 'in_progress') {
    $("#export_status").html('<?php echo $this->t('Export is in progress'); ?>');
    $("#buttons_in_progress").show();
  }
}


function ka_stop_export() {
  ka_export_status = 'stopped';
  $("#export_status").html('<?php echo $this->t('Export has been stopped'); ?>');
  ka_update_interface('stopped');
}


function ka_continue_export() {
  ka_export_status = 'in_progress';
  ka_update_interface('in_progress');
}


function ka_ajax_error(jqXHR, textStatus, errorThrown) {

  if ($.inArray(textStatus, ['abort', 'parseerror', 'error'])) {
    ka_export_status = 'stopped';

    if (jqXHR.status == '200') {
      ka_add_message("<?php echo $this->t('Server error (status='); ?>200). Details:" + jqXHR.responseText);
    } else {
      ka_add_message("<?php echo $this->t('Server error (status='); ?>" + jqXHR.status + ").");
    }
    ka_update_interface('stopped');
  } else {
    ka_add_message('<?php echo $this->t('Temporary connection problems.'); ?>');
  }

  ka_ajax_status = 'not_started';
}


function ka_ajax_success(data, textStatus, jqXHR) {

  if (data['messages']) {
    for (i in data['messages']) {
      ka_add_message(data['messages'][i]);
    }
  }

  if (data['status'] == 'error') {

    $("#export_status").html("Error: " + data['message']);
    ka_export_status = 'stopped';
    ka_update_interface('stopped');

  } else {

    $("#completion_at").html(data['completion_at']);
    $("#lines_processed").html(data['lines_processed']);
    $("#products_processed").html(data['products_processed']);
    $("#time_passed").html(data['time_passed']);
    $("#file_size").html(data['file_size']);

    if (data['status'] == 'completed') {
        ka_export_status = 'completed';
        ka_update_interface('completed');
    }
  }

  ka_ajax_status = 'not_started';
}


function ka_add_message(msg) {
  var dt       = new Date();
  var log_time = "[" + dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds() + "] ";
  $("#scroll").append(log_time + msg + "<br />");

  if ($("#autoscroll").prop("checked")) {
    $("#scroll").scrollTop(999999);
  }
}

var ka_dots     = 0;
var status_text = '';

function ka_export_loop() {

  if ($.inArray(ka_export_status, ['stopped', 'completed']) >= 0) {
    return;
  }

  if ($.inArray(ka_export_status, ['in_progress']) >= 0) {

    // show animation

    if (ka_dots == 0) {
      status_text = "<?php echo $this->t('Export is in progress'); ?>";
    } else {
      status_text = status_text + '.';
    }
    if (ka_dots++ > 5)
      ka_dots = 0;
    $("#export_status").html(status_text);
  }

  if ($.inArray(ka_ajax_status, ['not_started']) >= 0) {
    ka_ajax_status = 'in_progress';
    $.ajax({
      url: ka_page_url,
      dataType: 'json',
      cache : false,
      success: ka_ajax_success,
      error: ka_ajax_error
    });
  }
}

  
$(document).ready(function() {
  ka_timer = setInterval('ka_export_loop()', 750);
});

//--></script> 

  <?php } ?>

  <span class="help-block text-right" style="padding-right: 40px">'CSV Product Export' extension developed by <a href="mailto:support@ka-station.com?subject=CSV Product Export">karapuz</a></span>
</div>

<?php echo $footer; ?>