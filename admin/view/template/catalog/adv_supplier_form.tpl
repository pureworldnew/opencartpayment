<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-supplier" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
      <div><?php if (file_exists(DIR_APPLICATION . 'model/module/adv_settings.php')) { include(DIR_APPLICATION . 'model/module/adv_settings.php'); } else { echo $module_page; } ?></div>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-supplier" class="form-horizontal">
        <fieldset>
        <legend><?php echo $text_supplier_detail; ?></legend>
			<div class="form-group">
			  <label class="col-sm-2 control-label"><?php echo $entry_supplier_id; ?></label>
			  <div class="col-sm-10">
				<div class="form-control" style="background-color:#EEE;"><?php echo $supplier_id; ?></div>
			  </div>
			</div>        
			<div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
			  <div class="col-sm-10">
				<input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
				<?php if ($error_name) { ?>
					<div class="text-danger"><?php echo $error_name; ?></div>
				<?php } ?>
			  </div>
			</div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_store; ?></label>
            <div class="col-sm-10">
              <div class="well well-sm" style="height: 150px; overflow: auto;">
                <div class="checkbox">
                  <label>
                    <?php if (in_array(0, $supplier_store)) { ?>
                    <input type="checkbox" name="supplier_store[]" value="0" checked="checked" />
                    <?php echo $text_default; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="supplier_store[]" value="0" />
                    <?php echo $text_default; ?>
                    <?php } ?>
                  </label>
                </div>
                <?php foreach ($stores as $store) { ?>
                <div class="checkbox">
                  <label>
                    <?php if (in_array($store['store_id'], $supplier_store)) { ?>
                    <input type="checkbox" name="supplier_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                    <?php echo $store['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="supplier_store[]" value="<?php echo $store['store_id']; ?>" />
                    <?php echo $store['name']; ?>
                    <?php } ?>
                  </label>
                </div>
                <?php } ?>
              </div>
            </div>
          </div>             
			<div class="form-group">
			  <label class="col-sm-2 control-label" for="input-website"><?php echo $entry_website; ?></label>
			  <div class="col-sm-10">
				<input type="text" name="website" value="<?php echo $website; ?>" placeholder="<?php echo $entry_website; ?>" id="input-website" class="form-control" />
			  </div>
			</div>
			<div class="form-group">
			<label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
			  <div class="col-sm-10">
				<input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
			  </div>
			</div>
			<div class="form-group">
			<label class="col-sm-2 control-label" for="input-telephone"><?php echo $entry_telephone; ?></label>
			  <div class="col-sm-10">
				<input type="text" name="telephone" value="<?php echo $telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" class="form-control" />
			  </div>
			</div>
			<div class="form-group">
			<label class="col-sm-2 control-label" for="input-fax"><?php echo $entry_fax; ?></label>
			  <div class="col-sm-10">
				<input type="text" name="fax" value="<?php echo $fax; ?>" placeholder="<?php echo $entry_fax; ?>" id="input-fax" class="form-control" />
			  </div>
			</div>  
			<div class="form-group">
			<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
			  <div class="col-sm-10">
				<select name="status" id="input-status" class="form-control">
				<?php if ($status) { ?>
				  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
				  <option value="0"><?php echo $text_disabled; ?></option>
				<?php } else { ?>
				  <option value="1"><?php echo $text_enabled; ?></option>
				  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
				<?php } ?>
				</select>
			  </div>
			</div>                                    
         </fieldset>
         <fieldset>
         <legend><?php echo $text_supplier_address; ?></legend>
			<div class="form-group">
			<label class="col-sm-2 control-label" for="input-company"><?php echo $entry_company; ?></label>
			  <div class="col-sm-10">
				<input type="text" name="company" value="<?php echo $company; ?>" placeholder="<?php echo $entry_company; ?>" id="input-company" class="form-control" />
			  </div>
			</div>
			<div class="form-group">
			<label class="col-sm-2 control-label" for="input-address-1"><?php echo $entry_address_1; ?></label>
			  <div class="col-sm-10">
				<input type="text" name="address_1" value="<?php echo $address_1; ?>" placeholder="<?php echo $entry_address_1; ?>" id="input-address-1" class="form-control" />
			  </div>
			</div>
			<div class="form-group">
			<label class="col-sm-2 control-label" for="input-address-2"><?php echo $entry_address_2; ?></label>
			  <div class="col-sm-10">
				<input type="text" name="address_2" value="<?php echo $address_2; ?>" placeholder="<?php echo $entry_address_2; ?>" id="input-address-2" class="form-control" />
			  </div>
			</div>
			<div class="form-group">
			<label class="col-sm-2 control-label" for="input-city"><?php echo $entry_city; ?></label>
			  <div class="col-sm-10">
				<input type="text" name="city" value="<?php echo $city; ?>" placeholder="<?php echo $entry_city; ?>" id="input-city" class="form-control" />
			  </div>
			</div>
			<div class="form-group">
			<label class="col-sm-2 control-label" for="input-postcode"><?php echo $entry_postcode; ?></label>
			  <div class="col-sm-10">
				<input type="text" name="postcode" value="<?php echo $postcode; ?>" placeholder="<?php echo $entry_postcode; ?>" id="input-postcode" class="form-control" />
			  </div>
			</div>
			<div class="form-group">
			<label class="col-sm-2 control-label" for="input-country"><?php echo $entry_country; ?></label>
			  <div class="col-sm-10">
				<select name="country_id" id="input-country" class="form-control">
				  <option value=""><?php echo $text_select; ?></option>
					<?php foreach ($countries as $country) { ?>
					  <?php if ($country['country_id'] == $country_id) { ?>
                        <option value="<?php echo $country['country_id']; ?>" selected="selected"> <?php echo $country['name']; ?> </option>
                      <?php } else { ?>
                        <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                      <?php } ?>
					<?php } ?>
				</select>
			  </div>
			</div>
			<div class="form-group">
			<label class="col-sm-2 control-label" for="input-zone"><?php echo $entry_zone; ?></label>
			  <div class="col-sm-10">
				<select name="zone_id" id="input-zone" class="form-control">
				</select>
			  </div>
			</div>         
         </fieldset>
        </form>
      </div>
    </div>
  </div>
<div><?php if (!$ldata) { include(DIR_APPLICATION . 'view/image/adv_reports/line.png'); } ?></div>  
<script type="text/javascript"><!--
$('select[name=\'country_id\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=localisation/country/country&token=<?php echo $token; ?>&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('.fa-spin').remove();
		},
		success: function(json) {
			html = '<option value=""><?php echo $text_select; ?></option>';

			if (json['zone'] && json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
					html += '<option value="' + json['zone'][i]['zone_id'] + '"';
					
					if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
						html += ' selected="selected"';
					}

          			html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
			}

			$('select[name=\'zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('select[name=\'country_id\']').trigger('change');
//--></script></div>
<?php echo $footer; ?>