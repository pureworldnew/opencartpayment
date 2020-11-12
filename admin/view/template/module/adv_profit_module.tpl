<?php echo $header; ?>
<style type="text/css">
.btn-group-ms {
	width: 100%;
	height: 35px;	
}
.btn-group-ms > .multiselect.btn {
	width: 100%;
	height: 35px;	
}
.multiselect ul {
	width: 100%;
	height: 35px;	
}
.wrap-url {
	-ms-word-break: break-all;
	word-break: break-all;
	word-break: break-word;
	-webkit-hyphens: auto;
   	-moz-hyphens: auto;
	hyphens: auto;
}
</style>
<?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <?php if ($laccess) { ?><button type="submit" id="submit" form="form-adv" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button><?php } ?> 
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>&nbsp;<button type="button" onclick="window.open('http://www.opencartreports.com/documentation/prm/index.html');" title="<?php echo $button_documentation; ?>" class="btn btn-warning"><i class="fa fa-book"></i> <?php echo $button_documentation; ?></button></div>
      <h1><?php echo $heading_title_main; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
  <?php if ($laccess) { ?>
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
  <button type="button" class="close" data-dismiss="alert">&times;</button></div>
  <?php } ?>  
  <?php if ($warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $warning; ?>
  <button type="button" class="close" data-dismiss="alert">&times;</button></div>
  <?php } ?>  
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
  <button type="button" class="close" data-dismiss="alert">&times;</button></div>
  <?php } ?>         
  <?php foreach ($sc_geo_zones as $sc_geo_zone) { ?>
  <?php if (${'error_shipping_cost_' . $sc_geo_zone['geo_zone_id'] . '_total'}) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo ${'error_shipping_cost_' . $sc_geo_zone['geo_zone_id'] . '_total'}; ?> [<?php echo $sc_geo_zone['name']; ?>]
  <button type="button" class="close" data-dismiss="alert">&times;</button></div>
  <?php } ?>   
  <?php if (${'error_shipping_cost_' . $sc_geo_zone['geo_zone_id'] . '_rate'}) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo (${'error_shipping_cost_' . $sc_geo_zone['geo_zone_id'] . '_rate'}); ?> [<?php echo $sc_geo_zone['name']; ?>]
  <button type="button" class="close" data-dismiss="alert">&times;</button></div>
  <?php } ?> 
  <?php } ?> 
  <?php if ($error_extra_cost) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_extra_cost; ?>
  <button type="button" class="close" data-dismiss="alert">&times;</button></div>
  <?php } ?> 
  <?php } ?>  
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>    
      <div class="panel-body">
      	  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-adv">        
          <ul class="nav nav-tabs">
          <?php if ($laccess) { ?>
          <li class="active"><a href="#tab_product_cost" data-toggle="tab"><?php echo $tab_product_cost; ?></a></li>
          <li><a href="#tab_payment_cost" data-toggle="tab"><?php echo $tab_payment_cost; ?></a></li>
          <li><a href="#tab_shipping_cost" data-toggle="tab"><?php echo $tab_shipping_cost; ?></a></li>
          <li><a href="#tab_extra_cost" data-toggle="tab"><?php echo $tab_extra_cost; ?></a></li>
          <li><a href="#tab_settings" data-toggle="tab"><?php echo $tab_settings; ?></a></li>
          <li><a id="about" href="#tab_about" data-toggle="tab"><?php echo $tab_about; ?></a></li>          
          <?php } else { ?>
          <li class="active"><a id="about" href="#tab_about" data-toggle="tab"><?php echo $tab_about; ?></a></li>          
          <?php } ?>
      	  </ul>              
          <div class="tab-content">
         
        <?php if ($laccess) { ?>            
        <div id="tab_product_cost" class="tab-pane active">
        <fieldset style="padding-bottom:20px;">
        <legend><?php echo $entry_import_export; ?></legend>
          	<div style="padding-bottom:20px;"><?php echo $text_import_export_note; ?></div>
                <div class="col-sm-12" style="padding-bottom:10px;">
                  <div class="row" style="border:1px solid #6db8e0; -moz-border-radius:3px; border-radius:3px;">
                   <div class="input-group"><span class="input-group-btn"><button class="btn btn-primary btn-sm" disabled="disabled" style="height:90px;">1</button></span>               
                    <div class="col-lg-3" style="padding-bottom:5px; padding-top:5px;">
                      <label class="control-label" for="filter_category"><?php echo $entry_category; ?></label>
            		  <select name="filter_category" id="filter_category" class="form-control" multiple="multiple" size="1">
            			<?php foreach ($categories as $category) { ?>         
						<?php if (in_array($category['category_id'], $filter_category)) { ?>
						<option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo $category['name']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
						<?php } ?>
						<?php } ?>
          			  </select>
                    </div>
                    <div class="col-lg-3" style="padding-bottom:5px; padding-top:5px;">
                      <label class="control-label" for="filter_manufacturer"><?php echo $entry_manufacturer; ?></label>
            		  <select name="filter_manufacturer" id="filter_manufacturer" class="form-control" multiple="multiple" size="1">
              			<?php foreach ($manufacturers as $manufacturer) { ?>
              			<?php if (isset($filter_manufacturer[$manufacturer['manufacturer_id']])) { ?>
              			<option value="<?php echo $manufacturer['manufacturer_id']; ?>" selected="selected"><?php echo $manufacturer['name']; ?></option>
              			<?php } else { ?>
              			<option value="<?php echo $manufacturer['manufacturer_id']; ?>"><?php echo $manufacturer['name']; ?></option> 
              			<?php } ?>
              			<?php } ?>
            		  </select>
                    </div>
                    <div class="col-lg-3" style="padding-bottom:5px; padding-top:5px;">
                      <label class="control-label" for="filter_supplier"><?php echo $entry_supplier; ?></label>
            		  <select name="filter_supplier" id="filter_supplier" class="form-control" multiple="multiple" size="1">
              			<?php foreach ($suppliers as $supplier) { ?>
              			<?php if (isset($filter_supplier[$supplier['supplier_id']])) { ?>
              			<option value="<?php echo $supplier['supplier_id']; ?>" selected="selected"><?php echo $supplier['name']; ?></option>
              			<?php } else { ?>
              			<option value="<?php echo $supplier['supplier_id']; ?>"><?php echo $supplier['name']; ?></option> 
              			<?php } ?>
              			<?php } ?>
            		  </select>
                    </div>                    
                    <div class="col-lg-3" style="padding-bottom:5px; padding-top:5px;">
                      <label class="control-label" for="filter_status"><?php echo $entry_prod_status; ?></label>
            		  <select name="filter_status" id="filter_status" class="form-control" multiple="multiple" size="1">
                		<?php if ($filter_status) { ?>
                		<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                		<?php } else { ?>
                		<option value="1"><?php echo $text_enabled; ?></option>
                		<?php } ?>
                		<?php if (!is_null($filter_status) && !$filter_status) { ?>
                		<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                		<?php } else { ?>
                		<option value="0"><?php echo $text_disabled; ?></option>
                		<?php } ?>
            		  </select>
                    </div>
                  </div>
                 </div>
                </div>               
              <div class="col-sm-12" style="padding-bottom:10px;">
                <div class="row" style="border:1px solid #6db8e0; -moz-border-radius:3px; border-radius:3px;"> 
                   <div class="input-group"><span class="input-group-btn"><button class="btn btn-primary btn-sm" disabled="disabled" style="height:90px;">2</button></span>               
                    <div class="col-lg-5" style="padding-top:5px;">
                      <div style="padding-bottom:5px;"><?php echo $text_price_rounding; ?></div>
            		  <select name="filter_rounding" id="filter_rounding" class="form-control">
			  			<?php if ($filter_rounding == 'RD10DW') { ?>
			  			<option value="RD10DW" selected="selected">110.90 (round down to the whole number minus ten hundredths)</option>
			  			<?php } else { ?>
			  			<option value="RD10DW">110.90 (round down to the whole number minus ten hundredths)</option>
			  			<?php } ?>  
			  			<?php if ($filter_rounding == 'RD5DW') { ?>
			  			<option value="RD5DW" selected="selected">110.95 (round down to the whole number minus five hundredths)</option>
			  			<?php } else { ?>
			  			<option value="RD5DW">110.95 (round down to the whole number minus five hundredths)</option>
			  			<?php } ?>  
			  			<?php if ($filter_rounding == 'RD1DW') { ?>
			  			<option value="RD1DW" selected="selected">110.99 (round down to the whole number minus one hundredth)</option>
			  			<?php } else { ?>
			  			<option value="RD1DW">110.99 (round down to the whole number minus one hundredth)</option>
			  			<?php } ?>  
			  			<?php if ($filter_rounding == 'RD00DW') { ?>
			  			<option value="RD00DW" selected="selected">111.00 (round down to the whole number)</option>
			  			<?php } else { ?>
			  			<option value="RD00DW">111.00 (round down to the whole number)</option>
			  			<?php } ?>          
			  			<?php if ($filter_rounding == 'RD0DW') { ?>
			  			<option value="RD0DW" selected="selected">111.10 (round down to the nearest tenths place)</option>
			  			<?php } else { ?>
			  			<option value="RD0DW">111.10 (round down to the nearest tenths place)</option>
			  			<?php } ?>            
			  			<?php if ($filter_rounding == 'RD') { ?>
			  			<option value="RD" selected="selected">111.11 (without rounding) - default</option>
			  			<?php } else { ?>
			  			<option value="RD">111.11 (without rounding - default)</option>
			  			<?php } ?>
			  			<?php if ($filter_rounding == 'RD0UP') { ?>
			  			<option value="RD0UP" selected="selected">111.20 (round up to the nearest tenths place)</option>
			  			<?php } else { ?>
			  			<option value="RD0UP">111.20 (round up to the nearest tenths place)</option>
			  			<?php } ?> 
			  			<?php if ($filter_rounding == 'RD10UP') { ?>
			  			<option value="RD10UP" selected="selected">111.90 (round up to the whole number minus ten hundredths)</option>
			  			<?php } else { ?>
			  			<option value="RD10UP">111.90 (round up to the whole number minus ten hundredths)</option>
			  			<?php } ?>   
			  			<?php if ($filter_rounding == 'RD5UP') { ?>
			  			<option value="RD5UP" selected="selected">111.95 (round up to the whole number minus five hundredths)</option>
			  			<?php } else { ?>
			  			<option value="RD5UP">111.95 (round up to the whole number minus five hundredths)</option>
			  			<?php } ?>   
			  			<?php if ($filter_rounding == 'RD1UP') { ?>
			  			<option value="RD1UP" selected="selected">111.99 (round up to the whole number minus one hundredth)</option>
			  			<?php } else { ?>
			  			<option value="RD1UP">111.99 (round up to the whole number minus one hundredth)</option>
			  			<?php } ?>   
			  			<?php if ($filter_rounding == 'RD00UP') { ?>
			  			<option value="RD00UP" selected="selected">112.00 (round up to the whole number)</option>
			  			<?php } else { ?>
			  			<option value="RD00UP">112.00 (round up to the whole number)</option>
			  			<?php } ?>                                                                        
            		  </select>
                    </div>
                  </div>
                </div>
              </div> 
              <div class="col-sm-12" style="padding-bottom:10px;">
                <div class="row" style="border:1px solid #6db8e0; -moz-border-radius:3px; border-radius:3px;"> 
                   <div class="input-group"><span class="input-group-btn"><button class="btn btn-primary btn-sm" disabled="disabled" style="height:90px;">3</button></span>               
                    <div class="col-lg-12" style="padding-top:5px;">
                      <div style="padding-bottom:5px;"><?php echo $text_export; ?></div>
            		  <a id="button-export" data-toggle="tooltip" title="<?php echo $text_export; ?>" class="btn btn-primary" /><i class="fa fa-download"></i> <?php echo $button_export; ?></a>
                    </div>
                  </div>
                </div>
              </div> 
              <div class="col-sm-12" style="padding-bottom:10px;">
                <div class="row" style="border:1px solid #6db8e0; -moz-border-radius:3px; border-radius:3px;"> 
                   <div class="input-group"><span class="input-group-btn"><button class="btn btn-primary btn-sm" disabled="disabled" style="height:90px;">4</button></span>               
                    <div class="col-lg-5" style="padding-top:23px;">
                      <input type="file" name="upload" class="filestyle" />
                    </div>
                  </div>
                </div>
              </div> 
              <div class="col-sm-12">
                <div class="row" style="border:1px solid #6db8e0; -moz-border-radius:3px; border-radius:3px;"> 
                   <div class="input-group"><span class="input-group-btn"><button class="btn btn-primary btn-sm" disabled="disabled" style="height:90px;">5</button></span>               
                    <div class="col-lg-12" style="padding-top:5px;">
                      <div style="padding-bottom:5px;"><?php echo $text_import; ?></div>
            		  <button onclick="$('#form-adv').submit();" id="import" data-toggle="tooltip" title="<?php echo $text_import; ?>" class="btn btn-primary" disabled><i class="fa fa-upload"></i> <?php echo $button_import; ?></button>
                    </div>
                  </div>
                </div>
              </div> 
        </fieldset>
        <fieldset>                                         
        <legend><?php echo $entry_set_order_product_cost; ?></legend>
              <div class="col-sm-12" style="padding-bottom:20px;">
                <div class="row">               
                    <div class="col-lg-12" style="padding-top:3px;">
                      <div style="padding-bottom:5px;"><?php echo $text_set_set_order_product_cost; ?></div>
            		  <a onclick="show_order_product_cost_confirm()" data-toggle="tooltip" title="<?php echo $entry_set_order_product_cost; ?>" class="btn btn-success" style="margin-top:10px; white-space:normal;" /><i class="fa fa-plus-circle"></i> <?php echo $button_set_order_product_cost; ?></a>
                    </div>
                </div>
              </div> 
        </fieldset>                   
		</div>
            
		<div id="tab_payment_cost" class="tab-pane">
		<table width="100%" class="table table-bordered">
        <tr>
          <td width="15%" class="text-left"><?php echo $entry_adv_payment_cost_status; ?></td>
          <td width="85%" class="text-left"><select name="adv_payment_cost_status" class="form-control">
              <?php if ($adv_payment_cost_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>       
     	</table>
	  <br/>
      <div class="table-responsive">
        <table width="100%" id="adv_payment_cost" class="table table-bordered">
          <thead>
            <tr>
              <td width="18%" class="text-left" style="font-weight:normal; white-space:normal;"><?php echo $entry_adv_payment_cost_payment_type; ?></td>
              <td width="18%" class="text-left" style="font-weight:normal; white-space:normal;"><?php echo $entry_adv_payment_cost_total; ?></td>
              <td width="18%" class="text-left" style="font-weight:normal; white-space:normal;"><?php echo $entry_adv_payment_cost_percentage; ?></td>              
              <td width="18%" class="text-left" style="font-weight:normal; white-space:normal;"><?php echo $entry_adv_payment_cost_fixed_fee; ?></td>
			  <td width="18%" class="text-left" style="font-weight:normal; white-space:normal;"><?php echo $entry_adv_payment_cost_geo_zone; ?></td>
              <td></td>
            </tr>
          </thead>
          <?php if ($adv_payment_cost_types) { ?>
		   <?php $adv_payment_cost_types_row = 0; ?>
			<?php foreach ($adv_payment_cost_types as $adv_payment_cost_type) { ?>
			  <tbody id="adv_payment_cost_types_row<?php echo $adv_payment_cost_types_row; ?>">
				<tr>
				  <td width="18%" class="text-left">
					<select name="adv_payment_cost_type[<?php echo $adv_payment_cost_types_row; ?>][pc_paymentkey]" class="form-control">
					  <?php foreach ($payment_types as $payment_type) { ?>
						  <?php  if ($payment_type['paymentkey'] == $adv_payment_cost_type['pc_paymentkey']) { ?>
							<option value="<?php echo $payment_type['paymentkey']; ?>" selected><?php echo $payment_type['name']; ?></option>
						  <?php } else { ?>
							<option value="<?php echo $payment_type['paymentkey']; ?>"><?php echo $payment_type['name']; ?></option>
						  <?php } ?>
					  <?php } ?>
					</select>
				  </td> 
				  <td width="18%" class="text-left">
				  <input type="text" name="adv_payment_cost_type[<?php echo $adv_payment_cost_types_row; ?>][pc_order_total]" value="<?php echo $adv_payment_cost_type['pc_order_total']; ?>" class="form-control" />
				  </td>                  
				  <td width="18%" class="text-left">
				  <input type="text" name="adv_payment_cost_type[<?php echo $adv_payment_cost_types_row; ?>][pc_percentage]" value="<?php echo $adv_payment_cost_type['pc_percentage']; ?>" class="form-control" />
				  </td>
				  <td width="18%" class="text-left">
				  <input type="text" name="adv_payment_cost_type[<?php echo $adv_payment_cost_types_row; ?>][pc_fixed]" value="<?php echo $adv_payment_cost_type['pc_fixed']; ?>" class="form-control" />
				  </td>
				  <td width="18%" class="text-left">
				    <select name="adv_payment_cost_type[<?php echo $adv_payment_cost_types_row; ?>][pc_geozone]" class="form-control">
					  <option value="0" <?php if($adv_payment_cost_type['pc_geozone'] == 0) { echo 'selected'; } ?>><?php echo $text_all_zones; ?></option>
					  <?php foreach ($pc_geo_zones as $pc_geo_zone) { ?>
						  <?php  if ($pc_geo_zone['geo_zone_id'] == $adv_payment_cost_type['pc_geozone']) { ?>
							<option value="<?php echo $pc_geo_zone['geo_zone_id']; ?>" selected><?php echo $pc_geo_zone['name']; ?></option>
						  <?php } else { ?>
							<option value="<?php echo $pc_geo_zone['geo_zone_id']; ?>"><?php echo $pc_geo_zone['name']; ?></option>
						  <?php } ?>
					  <?php } ?>
					</select>
				  </td>
				  <td class="text-left"><button type="button" onclick="$('#adv_payment_cost_types_row<?php echo $adv_payment_cost_types_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove_payment; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
				</tr>
			  </tbody>
            <?php $adv_payment_cost_types_row++; ?>
  		    <?php } ?>
          <?php } else { ?>
		     <?php $adv_payment_cost_types_row = 0; ?>
		  <?php } ?>
		  
		  <tfoot>
            <tr>
              <td colspan="5"></td>
              <td class="text-left"><button type="button" onclick="addPaymentType();" data-toggle="tooltip" title="<?php echo $button_add_payment; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
            </tr>
          </tfoot>
        </table>
        </div>
        <fieldset>                                         
        <legend><?php echo $entry_set_order_payment_cost; ?></legend>
              <div class="col-sm-12" style="padding-bottom:20px;">
                <div class="row">               
                    <div class="col-lg-12" style="padding-top:3px;">
                      <div style="padding-bottom:5px;"><?php echo $text_set_set_order_payment_cost; ?></div>
            		  <a onclick="show_order_payment_cost_confirm()" data-toggle="tooltip" title="<?php echo $entry_set_order_payment_cost; ?>" class="btn btn-success" style="margin-top:10px; white-space:normal;" /><i class="fa fa-plus-circle"></i> <?php echo $button_set_order_payment_cost; ?></a>
                    </div>
                </div>
              </div> 
        </fieldset>
        </div>

		<div id="tab_shipping_cost" class="tab-pane">
          <div class="row" style="margin-bottom:10px;">
            <div class="col-sm-2">
              <ul class="nav nav-pills nav-stacked">
                <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                <?php foreach ($sc_geo_zones as $sc_geo_zone) { ?>
                <li><a href="#tab-geo-zone<?php echo $sc_geo_zone['geo_zone_id']; ?>" data-toggle="tab"><?php echo $sc_geo_zone['name']; ?></a></li>
                <?php } ?>
              </ul>
            </div>
            <div class="col-sm-10">
              <div class="tab-content">
                <div class="tab-pane active" id="tab-general">
				<table width="100%" class="table table-bordered">
            	<tr>
              	<td width="30%" class="text-left"><?php echo $entry_adv_shipping_cost_status; ?></td>
              	<td width="70%" class="text-left"><select name="adv_shipping_cost_weight_status" class="form-control">
                  <?php if ($adv_shipping_cost_weight_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                  </select></td>
            	</tr>         
        		</table>
				</div>   
        <?php foreach ($sc_geo_zones as $sc_geo_zone) { ?>
        <div class="tab-pane" id="tab-geo-zone<?php echo $sc_geo_zone['geo_zone_id']; ?>">
          <table width="100%" class="table table-bordered">
        	<tr>
          	  <td width="30%" class="text-left"><?php echo $entry_adv_shipping_cost_total; ?></td>
			  <td width="70%" class="text-left"><input type="text" name="adv_shipping_cost_weight_<?php echo $sc_geo_zone['geo_zone_id']; ?>_total" value="<?php echo ${'adv_shipping_cost_weight_' . $sc_geo_zone['geo_zone_id'] . '_total'}; ?>" class="form-control" />
          	  <br />
          	  <?php if (${'error_shipping_cost_' . $sc_geo_zone['geo_zone_id'] . '_total'}) { ?>
          	  <span class="text-danger"><?php echo ${'error_shipping_cost_' . $sc_geo_zone['geo_zone_id'] . '_total'}; ?></span>
          	  <?php } ?></td>
            </tr>              
            <tr>
              <td width="30%" class="text-left"><?php echo $entry_adv_shipping_cost_rate; ?></td>
              <td width="70%" class="text-left"><textarea name="adv_shipping_cost_weight_<?php echo $sc_geo_zone['geo_zone_id']; ?>_rate" cols="40" rows="5" class="form-control"><?php echo ${'adv_shipping_cost_weight_' . $sc_geo_zone['geo_zone_id'] . '_rate'}; ?></textarea>
          	  <br />
         	  <?php if (${'error_shipping_cost_' . $sc_geo_zone['geo_zone_id'] . '_rate'}) { ?>
         	  <span class="text-danger"><?php echo ${'error_shipping_cost_' . $sc_geo_zone['geo_zone_id'] . '_rate'}; ?></span>
        	  <?php } ?></td>              
            </tr>        
            <tr>
              <td width="30%" class="text-left"><?php echo $entry_status; ?></td>
              <td width="70%" class="text-left"><select name="adv_shipping_cost_weight_<?php echo $sc_geo_zone['geo_zone_id']; ?>_status" class="form-control">
                  <?php if (${'adv_shipping_cost_weight_' . $sc_geo_zone['geo_zone_id'] . '_status'}) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
          </table>
        </div>
        <?php } ?>
        </div>
        </div>
        </div>
        <fieldset>                                         
        <legend><?php echo $entry_set_order_shipping_cost; ?></legend>
              <div class="col-sm-12" style="padding-bottom:20px;">
                <div class="row">               
                    <div class="col-lg-12" style="padding-top:3px;">
                      <div style="padding-bottom:5px;"><?php echo $text_set_set_order_shipping_cost; ?></div>
            		  <a onclick="show_order_shipping_cost_confirm()" data-toggle="tooltip" title="<?php echo $entry_set_order_shipping_cost; ?>" class="btn btn-success" style="margin-top:10px; white-space:normal;" /><i class="fa fa-plus-circle"></i> <?php echo $button_set_order_shipping_cost; ?></a>
                    </div>
                </div>
              </div> 
        </fieldset>        
        </div>

		<div id="tab_extra_cost" class="tab-pane">
		<table width="100%" class="table table-bordered">
			<tr>
            	<td width="30%" class="text-left"><?php echo $entry_adv_extra_cost_status; ?></td>
              	<td width="70%" class="text-left"><select name="adv_extra_cost_status" class="form-control">
                  <?php if ($adv_extra_cost_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                  </select></td>
            </tr>                    
            <tr>
              <td width="30%" class="text-left"><?php echo $entry_adv_extra_cost; ?></td>
              <td width="70%" class="text-left"><textarea name="adv_extra_cost" cols="40" rows="5" class="form-control"><?php echo $adv_extra_cost; ?></textarea>
          	  <br />
         	  <?php if ($error_extra_cost) { ?>
         	  <span class="text-danger"><?php echo $error_extra_cost; ?></span>
        	  <?php } ?></td>              
            </tr>        
          </table>
        <fieldset>                                         
        <legend><?php echo $entry_set_order_extra_cost; ?></legend>
              <div class="col-sm-12" style="padding-bottom:20px;">
                <div class="row">               
                    <div class="col-lg-12" style="padding-top:3px;">
                      <div style="padding-bottom:5px;"><?php echo $text_set_set_order_extra_cost; ?></div>
            		  <a onclick="show_order_extra_cost_confirm()" data-toggle="tooltip" title="<?php echo $entry_set_order_extra_cost; ?>" class="btn btn-success" style="margin-top:10px; white-space:normal;" /><i class="fa fa-plus-circle"></i> <?php echo $button_set_order_extra_cost; ?></a>
                    </div>
                </div>
              </div> 
        </fieldset>            
        </div> 

		<div id="tab_settings" class="tab-pane">
        <fieldset> 
        <legend><?php echo $text_plist_settings; ?></legend>
		<table width="100%" class="table table-bordered">
			<tr>
            	<td width="30%" class="text-left"><?php echo $text_adv_plist_price_tax_status; ?></td>
              	<td width="70%" class="text-left"><select name="adv_plist_price_tax_status" class="form-control">
                  <?php if ($adv_plist_price_tax_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                  <option value="0"><?php echo $text_no; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_yes; ?></option>
                  <option value="0" selected="selected"><?php echo $text_no; ?></option>
                  <?php } ?>
                  </select></td>
            </tr>                        
			<tr>
            	<td width="30%" class="text-left"><?php echo $text_adv_plist_cost_status; ?></td>
              	<td width="70%" class="text-left"><select name="adv_plist_cost_status" class="form-control">
                  <?php if ($adv_plist_cost_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                  <option value="0"><?php echo $text_no; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_yes; ?></option>
                  <option value="0" selected="selected"><?php echo $text_no; ?></option>
                  <?php } ?>
                  </select></td>
            </tr>     
			<tr>
            	<td width="30%" class="text-left"><?php echo $text_adv_plist_profit_status; ?></td>
              	<td width="70%" class="text-left"><select name="adv_plist_profit_status" class="form-control">
                  <?php if ($adv_plist_profit_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                  <option value="0"><?php echo $text_no; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_yes; ?></option>
                  <option value="0" selected="selected"><?php echo $text_no; ?></option>
                  <?php } ?>
                  </select></td>
            </tr>     
			<tr>
            	<td width="30%" class="text-left"><?php echo $text_adv_plist_profit_margin_status; ?></td>
              	<td width="70%" class="text-left"><select name="adv_plist_profit_margin_status" class="form-control">
                  <?php if ($adv_plist_profit_margin_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                  <option value="0"><?php echo $text_no; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_yes; ?></option>
                  <option value="0" selected="selected"><?php echo $text_no; ?></option>
                  <?php } ?>
                  </select></td>
            </tr>   
			<tr>
            	<td width="30%" class="text-left"><?php echo $text_adv_plist_profit_markup_status; ?></td>
              	<td width="70%" class="text-left"><select name="adv_plist_profit_markup_status" class="form-control">
                  <?php if ($adv_plist_profit_markup_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                  <option value="0"><?php echo $text_no; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_yes; ?></option>
                  <option value="0" selected="selected"><?php echo $text_no; ?></option>
                  <?php } ?>
                  </select></td>
            </tr>  
			<tr>
            	<td width="30%" class="text-left"><?php echo $text_adv_plist_category_status; ?></td>
              	<td width="70%" class="text-left"><select name="adv_plist_category_status" class="form-control">
                  <?php if ($adv_plist_category_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                  <option value="0"><?php echo $text_no; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_yes; ?></option>
                  <option value="0" selected="selected"><?php echo $text_no; ?></option>
                  <?php } ?>
                  </select></td>
            </tr>  
			<tr>
            	<td width="30%" class="text-left"><?php echo $text_adv_plist_manufacturer_status; ?></td>
              	<td width="70%" class="text-left"><select name="adv_plist_manufacturer_status" class="form-control">
                  <?php if ($adv_plist_manufacturer_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                  <option value="0"><?php echo $text_no; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_yes; ?></option>
                  <option value="0" selected="selected"><?php echo $text_no; ?></option>
                  <?php } ?>
                  </select></td>
            </tr>               
			<tr>
            	<td width="30%" class="text-left"><?php echo $text_adv_plist_supplier_status; ?></td>
              	<td width="70%" class="text-left"><select name="adv_plist_supplier_status" class="form-control">
                  <?php if ($adv_plist_supplier_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                  <option value="0"><?php echo $text_no; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_yes; ?></option>
                  <option value="0" selected="selected"><?php echo $text_no; ?></option>
                  <?php } ?>
                  </select></td>
            </tr>             
			<tr>
            	<td width="30%" class="text-left"><?php echo $text_adv_plist_sold_status; ?></td>
              	<td width="70%" class="text-left"><select name="adv_plist_sold_status" class="form-control">
                  <?php if ($adv_plist_sold_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                  <option value="0"><?php echo $text_no; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_yes; ?></option>
                  <option value="0" selected="selected"><?php echo $text_no; ?></option>
                  <?php } ?>
                  </select></td>
            </tr>  
			<tr>
            	<td width="30%" class="text-left"><?php echo $text_sold_order_status; ?></td>
              	<td width="70%" class="text-left"><div class="row"><div class="col-lg-12" style="padding-bottom:5px; padding-top:5px;">
                <select name="adv_sold_order_status[]" id="adv_sold_order_status" class="form-control" multiple="multiple" size="1">
              		<?php foreach ($order_statuses as $order_status) { ?>
                    <?php if (is_array($adv_sold_order_status) && in_array($order_status['order_status_id'], $adv_sold_order_status)) { ?>
              		<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              		<?php } else { ?>
              		<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              		<?php } ?>
             		<?php } ?>
            	</select></div></div></td>
            </tr>                                                                           
          </table> 
        </fieldset>
        <fieldset>                                         
        <legend><?php echo $text_pedit_settings; ?></legend>
		<table width="100%" class="table table-bordered">
			<tr>
            	<td width="30%" class="text-left"><?php echo $text_price_incl_tax; ?></td>
              	<td width="70%" class="text-left">
              		<?php if ($adv_price_tax) { ?>
                	<input type="radio" name="adv_price_tax" value="1" checked="checked">
                	<?php echo $text_yes; ?>
                	<input type="radio" name="adv_price_tax" value="0">
                	<?php echo $text_no; ?>
                	<?php } else { ?>
	                <input type="radio" name="adv_price_tax" value="1">
	                <?php echo $text_yes; ?>
	                <input type="radio" name="adv_price_tax" value="0" checked="checked">
	                <?php echo $text_no; ?>
	                <?php } ?>                
                </td>
            </tr>   
			<tr>
            	<td width="30%" class="text-left"><?php echo $text_tax_store_based; ?></td>
              	<td width="70%" class="text-left">
              	  	<?php if ($adv_price_tax_store_based) { ?>
	                <input type="radio" name="adv_price_tax_store_based" value="1" checked="checked"<?php if (!$adv_price_tax) { echo " disabled"; } ?>>
	                <?php echo $text_yes; ?>
	                <input type="radio" name="adv_price_tax_store_based" value="0"<?php if (!$adv_price_tax) { echo " disabled"; } ?>>
	                <?php echo $text_no; ?>
	                <?php } else { ?>
	                <input type="radio" name="adv_price_tax_store_based" value="1"<?php if (!$adv_price_tax) { echo " disabled"; } ?>>
	                <?php echo $text_yes; ?>
	                <input type="radio" name="adv_price_tax_store_based" value="0" checked="checked"<?php if (!$adv_price_tax) { echo " disabled"; } ?>>
	                <?php echo $text_no; ?>
	                <?php } ?>
                </td>
            </tr>  
			<tr>
            	<td width="30%" class="text-left"><?php echo $text_tax_country; ?></td>
              	<td width="70%" class="text-left">
              	  	<select name="adv_price_tax_country_id" id="input-country" class="form-control"<?php if (!$adv_price_tax or $adv_price_tax_store_based) { echo " disabled"; } ?>>
	                <?php foreach ($countries as $country) { ?>
	                <?php if ($country['country_id'] == $adv_price_tax_country_id) { ?>
	                <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
	                <?php } else { ?>
	                <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
	                <?php } ?>
	                <?php } ?>
	        	    </select>
                </td>
            </tr>   
			<tr>
            	<td width="30%" class="text-left"><?php echo $text_tax_zone; ?></td>
              	<td width="70%" class="text-left">
	              	<select name="adv_price_tax_zone_id" id="input-zone" class="form-control"<?php if (!$adv_price_tax or $adv_price_tax_store_based) { echo " disabled"; } ?>>
	                </select>
                </td>
            </tr>                        
          </table> 
        </fieldset>          
        <fieldset>  
        <legend><?php echo $text_local_settings; ?></legend>
		<table width="100%" class="table table-bordered">
			<tr>
            	<td width="30%" class="text-left"><?php echo $text_format_date; ?></td>
              	<td width="70%" class="text-left"><select name="adv_date_format" class="form-control">
					<?php if ($adv_date_format == 'DDMMYYYY') { ?>
					<option value="DDMMYYYY" selected="selected"><?php echo $text_format_date_eu; ?></option>
					<option value="MMDDYYYY"><?php echo $text_format_date_us; ?></option>
					<?php } else { ?>
					<option value="DDMMYYYY"><?php echo $text_format_date_eu; ?></option>
					<option value="MMDDYYYY" selected="selected"><?php echo $text_format_date_us; ?></option>
					<?php } ?>
				</select></td>
            </tr>   
			<tr>
            	<td width="30%" class="text-left"><?php echo $text_format_hour; ?></td>
              	<td width="70%" class="text-left"><select name="adv_hour_format" class="form-control">
					<?php if ($adv_hour_format == '24') { ?>
					<option value="24" selected="selected"><?php echo $text_format_hour_24; ?></option>
					<option value="12"><?php echo $text_format_hour_12; ?></option>
					<?php } else { ?>
					<option value="24"><?php echo $text_format_hour_24; ?></option>
					<option value="12" selected="selected"><?php echo $text_format_hour_12; ?></option>
					<?php } ?>
				</select></td>
            </tr>  
          </table> 
        </fieldset>            
        </div> 
                          
		<div id="tab_about" class="tab-pane">  
        <?php } else { ?>
		<div id="tab_about" class="tab-pane active">           
        <?php } ?>
      
     	<div id="adv_profit_module"></div>
		<div align="center" class="wrapper col-md-12"><a href="http://www.opencartreports.com" target="_blank"><img class="img-responsive" src="view/image/adv_reports/adv_logo.png" /></a></div>        
        </div>
      
      </div>
      </form>      
      </div>
  	  </div>
      </div>
<?php if ($adv_prm_version && $adv_prm_version['version'] != $adv_current_version) { ?>  
<script type="text/javascript">
$('#about').append('&nbsp;<i class=\"fa fa-exclamation-circle\"></i>'); 
$('#about').css({'background-color': '#FFD1D1','border': '1px solid #F8ACAC','color': 'red','text-decoration': 'blink'});
</script> 
<?php } ?>
<?php if ($laccess) { ?>
<script type="text/javascript">
$('#button-export').on('click', function() {
	url = 'index.php?route=module/adv_profit_module&token=<?php echo $token; ?>';

	var filtercategory = [];
	$('#filter_category option:selected').each(function() {
		filtercategory.push($(this).val());
	});
	
	var filter_category = filtercategory.join('_');
	
	if (filter_category) {
		url += '&filter_category=' + encodeURIComponent(filter_category);
	}	

	var filtermanufacturer = [];
	$('#filter_manufacturer option:selected').each(function() {
		filtermanufacturer.push($(this).val());
	});
	
	var filter_manufacturer = filtermanufacturer.join('_');
	
	if (filter_manufacturer) {
		url += '&filter_manufacturer=' + encodeURIComponent(filter_manufacturer);
	}	

	var filtersupplier = [];
	$('#filter_supplier option:selected').each(function() {
		filtersupplier.push($(this).val());
	});
	
	var filter_supplier = filtersupplier.join('_');
	
	if (filter_supplier) {
		url += '&filter_supplier=' + encodeURIComponent(filter_supplier);
	}	
	
	var filterstatus = [];
	$('#filter_status option:selected').each(function() {
		filterstatus.push($(this).val());
	});
	
	var filter_status = filterstatus.join('_');
	
	if (filter_status) {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}	
	
	var filter_rounding = $('select[name=\'filter_rounding\']').val();

	if (filter_rounding) {
		url += '&filter_rounding=' + encodeURIComponent(filter_rounding);
	}		
	
		url += '&export=xls';
	
	location = url;
});

function show_order_product_cost_confirm() {
	var r = confirm("<?php echo $text_set_order_product_cost_confirm ;?>");
	if (r==true) {
		window.location = "<?php echo htmlspecialchars_decode($url_set_order_product_cost) ;?>";
	} else {
		//alert("You pressed Cancel!");
	}
}
function show_order_payment_cost_confirm() {
	var r = confirm("<?php echo $text_set_order_payment_cost_confirm ;?>");
	if (r==true) {
		window.location = "<?php echo htmlspecialchars_decode($url_set_order_payment_cost) ;?>";
	} else {
		//alert("You pressed Cancel!");
	}
}
function show_order_shipping_cost_confirm() {
	var r = confirm("<?php echo $text_set_order_shipping_cost_confirm ;?>");
	if (r==true) {
		window.location = "<?php echo htmlspecialchars_decode($url_set_order_shipping_cost) ;?>";
	} else {
		//alert("You pressed Cancel!");
	}
}
function show_order_extra_cost_confirm() {
	var r = confirm("<?php echo $text_set_order_extra_cost_confirm ;?>");
	if (r==true) {
		window.location = "<?php echo htmlspecialchars_decode($url_set_order_extra_cost) ;?>";
	} else {
		//alert("You pressed Cancel!");
	}
}
</script>
<script type="text/javascript">
$(document).ready(function() {
	$('#filter_category').multiselect({
		checkboxName: 'filtercategory[]',
		includeSelectAllOption: true,
		enableFiltering: true,
		selectAllText: '<?php echo $text_all; ?>',
		allSelectedText: '<?php echo $text_selected; ?>',
		nonSelectedText: '<?php echo $text_all_categories; ?>',
		numberDisplayed: 0,
		disableIfEmpty: true,
		maxHeight: 300
	});
	$('#filter_manufacturer').multiselect({
		checkboxName: 'filtermanufacturer[]',
		includeSelectAllOption: true,
		enableFiltering: true,
		selectAllText: '<?php echo $text_all; ?>',
		allSelectedText: '<?php echo $text_selected; ?>',
		nonSelectedText: '<?php echo $text_all_manufacturers; ?>',
		numberDisplayed: 0,
		disableIfEmpty: true,
		maxHeight: 300
	});	
	$('#filter_supplier').multiselect({
		checkboxName: 'filtersupplier[]',
		includeSelectAllOption: true,
		enableFiltering: true,
		selectAllText: '<?php echo $text_all; ?>',
		allSelectedText: '<?php echo $text_selected; ?>',
		nonSelectedText: '<?php echo $text_all_suppliers; ?>',
		numberDisplayed: 0,
		disableIfEmpty: true,
		maxHeight: 300
	});		
	$('#filter_status').multiselect({
		checkboxName: 'filterstatus[]',
		includeSelectAllOption: true,
		enableFiltering: true,
		selectAllText: '<?php echo $text_all; ?>',
		allSelectedText: '<?php echo $text_selected; ?>',
		nonSelectedText: '<?php echo $text_all_statuses; ?>',
		numberDisplayed: 0,
		disableIfEmpty: true,
		maxHeight: 300
	});	
	
	$('#adv_sold_order_status').multiselect({
		includeSelectAllOption: true,
		enableFiltering: true,
		selectAllText: '<?php echo $text_all; ?>',
		allSelectedText: '<?php echo $text_selected; ?>',
		nonSelectedText: '<?php echo $text_all_statuses; ?>',
		numberDisplayed: 0,
		disableIfEmpty: true,
		maxHeight: 300
	});	
	
	$('input:file').change(function(){
		if ($(this).val()) {
			$('#import').attr('disabled',false);
	    } 
	});	
});
</script>
<script type="text/javascript">
var adv_payment_cost_types_row = <?php echo $adv_payment_cost_types_row; ?>;

function addPaymentType() {
	html  = '<tbody id="adv_payment_cost_types_row' + adv_payment_cost_types_row + '">';
	html += '<tr>';
	html += '<td class="text-left"><select name="adv_payment_cost_type[' + adv_payment_cost_types_row + '][pc_paymentkey]" class="form-control">';
	html += '<?php foreach ($payment_types as $payment_type) { ?><option value="<?php echo $payment_type["paymentkey"]; ?>"><?php echo $payment_type["name"]; ?></option><?php } ?></select></td>';
	html += '<td class="text-left"><input type="text" name="adv_payment_cost_type[' + adv_payment_cost_types_row + '][pc_order_total]" value="0.00" class="form-control" /></td>';
	html += '<td class="text-left"><input type="text" name="adv_payment_cost_type[' + adv_payment_cost_types_row + '][pc_percentage]" value="0.00" class="form-control" /></td>';
	html += '<td class="text-left"><input type="text" name="adv_payment_cost_type[' + adv_payment_cost_types_row + '][pc_fixed]" value="0.00" class="form-control" /></td>';
	html += '<td class="text-left"><select name="adv_payment_cost_type[' + adv_payment_cost_types_row + '][pc_geozone]" class="form-control">';
    html += '<option value="0" selected><?php echo $text_all_zones; ?></option>';
    html += '<?php foreach ($pc_geo_zones as $pc_geo_zone) { ?>';
    html += '<option value="<?php echo $pc_geo_zone["geo_zone_id"]; ?>"><?php echo $pc_geo_zone["name"]; ?></option>';
    html += '<?php } ?></select></td>';
	html += '<td class="text-left"><button type="button" onclick="$(\'#adv_payment_cost_types_row' + adv_payment_cost_types_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove_payment; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';
	html += '</tbody>';

	$('#adv_payment_cost > tfoot').before(html);

	adv_payment_cost_types_row++;
}
</script>
<script type="text/javascript">
$(document).ready(function(){
	if ($('#form-adv input:radio[name=adv_price_tax_store_based]:checked').val() == '1') {
		$('#form-adv select[name=adv_price_tax_country_id]').parent().parent().hide();
		$('#form-adv select[name=adv_price_tax_zone_id]').parent().parent().hide();
	}

$('#form-adv input[name=adv_price_tax]').change(function() {
	if ($(this).val() == '1') {
		$('#form-adv input[name=adv_price_tax_store_based]').prop('disabled', false);
			if ($('#form-adv input:radio[name=adv_price_tax_store_based]:checked').val() == '0') {
				$('#form-adv select[name=adv_price_tax_country_id]').parent().parent().show();
				$('#form-adv select[name=adv_price_tax_zone_id]').parent().parent().show();
				$('#form-adv select[name=adv_price_tax_country_id]').prop('disabled', false);
				$('#form-adv select[name=adv_price_tax_zone_id]').prop('disabled', false);
			}
	} else {
		$('#form-adv input[name=adv_price_tax_store_based]').prop('disabled', true);
		$('#form-adv select[name=adv_price_tax_country_id]').prop('disabled', true);
		$('#form-adv select[name=adv_price_tax_zone_id]').prop('disabled', true);
		$('#form-adv select[name=adv_price_tax_country_id]').parent().parent().hide();
		$('#form-adv select[name=adv_price_tax_zone_id]').parent().parent().hide();
	}
});

$('#form-adv input[name=adv_price_tax_store_based]').change(function() {
	if ($(this).val() == '1') {
		$('#form-adv select[name=adv_price_tax_country_id]').prop('disabled', true);
		$('#form-adv select[name=adv_price_tax_zone_id]').prop('disabled', true);
		$('#form-adv select[name=adv_price_tax_country_id]').parent().parent().hide();
		$('#form-adv select[name=adv_price_tax_zone_id]').parent().parent().hide();
	} else {
		$('#form-adv select[name=adv_price_tax_country_id]').parent().parent().show();
		$('#form-adv select[name=adv_price_tax_zone_id]').parent().parent().show();
		$('#form-adv select[name=adv_price_tax_country_id]').prop('disabled', false);
		$('#form-adv select[name=adv_price_tax_zone_id]').prop('disabled', false);
	}
});
});

$('select[name=\'adv_price_tax_country_id\']').bind('change', function() {
	$.ajax({
	url: 'index.php?route=localisation/country/country&token=<?php echo $token; ?>&country_id=' + this.value,
	dataType: 'json',
	beforeSend: function() {
		$('select[name=\'adv_price_tax_country_id\']').after('<span class="wait"></span>');
	},		
	complete: function() {
		$('.wait').remove();
	},			
	success: function(json) {
		if (json['postcode_required'] == '1') {
			$('#postcode-required').show();
		} else {
			$('#postcode-required').hide();
		}
	html = '<option value=""><?php echo $text_select; ?></option>';
						
	if (json['zone'] != '') {
		for (i = 0; i < json['zone'].length; i++) {
			html += '<option value="' + json['zone'][i]['zone_id'] + '"';
			if (json['zone'][i]['zone_id'] == '<?php echo $adv_price_tax_zone_id; ?>') {
				html += ' selected="selected"';
			}
			html += '>' + json['zone'][i]['name'] + '</option>';
		}
	} else {
		html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
	}
	$('select[name=\'adv_price_tax_zone_id\']').html(html);
	},
	error: function(xhr, ajaxOptions, thrownError) {
	alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	}
	});
});
$('select[name=\'adv_price_tax_country_id\']').trigger('change');
</script>
<?php } ?>
</div>
<?php echo $footer; ?>