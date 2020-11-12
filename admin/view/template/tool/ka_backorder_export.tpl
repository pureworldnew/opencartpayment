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

  <?php if ($params['step'] == 1) { ?>

		<div class="page-header">
			<div class="container-fluid">
				<div class="pull-right">
					<button type="submit" form="form-input" class="btn btn-primary"><?php echo $this->t('Export'); ?></button>
				</div>
				<h1><?php echo $heading_title; ?></h1>
				<?php echo $ka_breadcrumbs; ?>
			</div>
		</div>

		<div class="container-fluid">
			<?php if ($error_warning) { ?>
				<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
					<button type="button" class="close" data-dismiss="alert">&times;</button>
				</div>
			<?php } ?>
			<?php echo $ka_top; ?>
			
			<?php echo "This page allows you to export Backorder data to a file in CSV format."; ?><br /><br />
			
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-input">
				<input type="hidden" name="mode" value="" />

				
			
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $this->t('General'); ?></a></li>
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
							<tr>
								<td width="25%"><label class="control-label"><?php echo $this->t('Date From'); ?></label></td>
								<td colspan="2">
								<div class="input-group date">
                  <input type="text" name="filter_date_from" value="" placeholder="Date From" data-date-format="YYYY-MM-DD" id="input-date-from" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
								</td>
							</tr>
							<tr>
								<td width="25%"><label class="control-label"><?php echo $this->t('Date To'); ?></label></td>
								<td colspan="2">
								<div class="input-group date">
                  <input type="text" name="filter_date_to" value="" placeholder="Date To" data-date-format="YYYY-MM-DD" id="input-date-to" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</form>
		</div>


<script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
  <link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script>

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

  <?php } ?>

  
</div>

<?php echo $footer; ?>