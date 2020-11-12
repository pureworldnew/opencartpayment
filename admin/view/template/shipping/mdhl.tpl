<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">

  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>

   <div class="page-header">
		<div class="container-fluid">
		  <div class="pull-right">
			<button type="submit" form="form-mdhl" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
			<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
			</div>
		  <h1><i class="fa fa-puzzle-piece"></i> <?php echo $heading_title; ?></h1>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-mdhl" class="form-horizontal">
	   
			<div class="form-group required">
				<label class="col-sm-2 control-label" for="input-mdhl-site-id"><span data-toggle="tooltip" title="<?php echo $help_site_id; ?>"><?php echo $entry_site_id; ?></span></label>
				<div class="col-sm-10">
					<input type="text" name="mdhl_site_id" value="<?php echo $mdhl_site_id; ?>" placeholder="" class="form-control" />
					<?php if (isset($error_site_id)) { ?>
					<div class="text-danger"><?php echo $error_site_id; ?></div>
					<?php } ?>
				</div>	
			</div>
			<div class="form-group required">
				<label class="col-sm-2 control-label" for="input-mdhl-password"><span data-toggle="tooltip" title="<?php echo $help_password; ?>"><?php echo $entry_password; ?></span></label>
				<div class="col-sm-10">
					<input type="text" name="mdhl_password" value="<?php echo $mdhl_password; ?>" placeholder="" class="form-control" />
					<?php if (isset($error_password)) { ?>
					<div class="text-danger"><?php echo $error_password; ?></div>
					<?php } ?>
				</div>	
			</div>					
			<div class="form-group required">
				<label class="col-sm-2 control-label" for="input-mdhl-shipper-zip"><span data-toggle="tooltip" title="<?php echo $help_zip; ?>"><?php echo $entry_zip; ?></span></label>
				<div class="col-sm-10">
					<input type="text" name="mdhl_zip" value="<?php echo $mdhl_zip; ?>" placeholder="" class="form-control" />
					<?php if (isset($error_zip)) { ?>
					<div class="text-danger"><?php echo $error_zip; ?></div>
					<?php } ?>
				</div>	
			</div>	
			<div class="form-group required">
				<label class="col-sm-2 control-label" for="input-mdhl-country"><span data-toggle="tooltip" title="<?php echo $help_country; ?>"><?php echo $entry_country; ?></span></label>
				<div class="col-sm-10">
					 <select name="mdhl_country" id="mdhl_country" class="form-control">
						<?php foreach ($countries as $country) { ?>
						<?php if ($country['iso_code_2'] == $mdhl_country) { ?>
						<option value="<?php echo $country['iso_code_2']; ?>" selected="selected"><?php echo $country['name']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $country['iso_code_2']; ?>"><?php echo $country['name']; ?></option>
						<?php } ?>
						<?php } ?>
					 </select>
				</div>	
			</div>			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-mdhl-mode"><span data-toggle="tooltip" title="<?php echo $help_mode; ?>"><?php echo $entry_mode; ?></span></label>
				<div class="col-sm-10">
					  <select name="mdhl_mode" id="mdhl_mode" class="form-control">
					  <?php if ($mdhl_mode) { ?>
					  <option value="1" selected="selected"><?php echo $text_production; ?></option>
					  <option value="0"><?php echo $text_test; ?></option>
					  <?php } else { ?>
					  <option value="1"><?php echo $text_production; ?></option>
					  <option value="0" selected="selected"><?php echo $text_test; ?></option>
					  <?php } ?>
					  </select>
				</div>	
			</div>	
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-mdhl-geo-zone"><?php echo $entry_geo_zone; ?></label>
				<div class="col-sm-10">
					  <select name="mdhl_geo_zone_id" id="mdhl_geo_zone_id" class="form-control">
						<option value="0"><?php echo $text_all_zones; ?></option>
						<?php foreach ($geo_zones as $geo_zone) { ?>
						<?php if ($geo_zone['geo_zone_id'] == $mdhl_geo_zone_id) { ?>
						<option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
						<?php } ?>
						<?php } ?>
					  </select>
				</div>	
			</div>			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-mdhl-status"><?php echo $entry_status; ?></label>
				<div class="col-sm-10">
					  <select name="mdhl_status" id="mdhl_status" class="form-control">
					  <?php if ($mdhl_status) { ?>
					  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					  <option value="0"><?php echo $text_disabled; ?></option>
					  <?php } else { ?>
					  <option value="1"><?php echo $text_enabled; ?></option>
					  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
					  <?php } ?>
				  </select>
				</div>	
			</div>			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-mdhl-sort-order"><?php echo $entry_sort_order; ?></label>
				<div class="col-sm-10">
					<input type="text" name="mdhl_sort_order" value="<?php echo $mdhl_sort_order; ?>" placeholder="" class="form-control" />
				</div>	
			 </div>	 
		 </form>
      </div>
    </div>
  </div>
 

<?php echo $footer; ?>