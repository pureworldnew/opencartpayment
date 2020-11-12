<?php echo $header; ?><?php echo $column_left; ?>
<?php if( !isset( $prm_access_permission ) ) { $prm_access_permission = true; } ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
<?php if (file_exists(DIR_APPLICATION . 'model/module/adv_settings.php')) { include(DIR_APPLICATION . 'model/module/adv_settings.php'); } else { echo $module_page; } ?><?php if (!$ldata) { include(DIR_APPLICATION . 'view/image/adv_reports/line.png'); } ?>

				<button type="submit" id="save_apply" name="save_apply" form="form-product" data-toggle="tooltip" title="<?php echo $button_apply; ?>" value="1" class="btn btn-success"><i class="fa fa-check"></i></button>
				
        <button type="submit" form="form-product" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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

			<?php if ($success) { ?>
  			<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $text_result_success; ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>
  			<?php } ?>
  		    
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?><span class="product_name"></span></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-product" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a <?php echo ($prm_access_permission && $laccess) ? 'style="color:#F99;"' : '' ?> href="#tab-data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
            <li><a href="#tab-links" data-toggle="tab"><?php echo $tab_links; ?></a></li>
            <li><a href="#tab-attribute" data-toggle="tab"><?php echo $tab_attribute; ?></a></li>
            <li><a href="#tab-estimate-time" data-toggle="tab"><?php echo 'Delivery Estimate Time'; ?></a></li>
            <li><a id="optInitialize" <?php echo ($prm_access_permission && $laccess) ? 'style="color:#F99;"' : '' ?> href="#tab-option" data-toggle="tab"><?php echo $tab_option; ?></a></li>
            <li><a href="#tab-recurring" data-toggle="tab"><?php echo $tab_recurring; ?></a></li>
            <li><a id="discInitialize" <?php echo ($prm_access_permission && $laccess) ? 'style="color:#F99;"' : '' ?> href="#tab-discount" data-toggle="tab"><?php echo $tab_discount; ?></a></li>
            <li><a id="specInitialize" <?php echo ($prm_access_permission && $laccess) ? 'style="color:#F99;"' : '' ?> href="#tab-special" data-toggle="tab"><?php echo $tab_special; ?></a></li>
            <li><a href="#tab-image" data-toggle="tab"><?php echo $tab_image; ?></a></li>
            <li><a href="#tab-reward" data-toggle="tab"><?php echo $tab_reward; ?></a></li>
            <li><a href="#tab-design" data-toggle="tab"><?php echo $tab_design; ?></a></li>

<?php if ($product_id) { ?><?php if ($prm_access_permission && $laccess) { ?><li><a id="chart_resize" href="#tab-history" data-toggle="tab"><span style="color:#F99;"><?php echo $tab_stock_history; ?></span></a></li><li><a href="#tab-sales" data-toggle="tab"><span style="color:#F99;"><?php echo $tab_sales_history; ?></span></a></li><?php } ?><?php } ?>
			
            <li><a href="#tab-location" data-toggle="tab">Location by quantity</a></li>
            <li><a href="#tab-blog" data-toggle="tab">Relevant articles/videos</a></li>
            <li><a href="#tab-video" data-toggle="tab">Product Video</a></li>
            <li><a href="#tab-qrcode" data-toggle="tab">QR Code</a></li>
          </ul>
          <div class="tab-content">
           <div class="tab-pane" id="tab-location">
          <div class="row">
            <label class="text-left control-label col-sm-3">Location:</label>
            <label class="text-left control-label col-sm-3">Quantity</label>
            <label class="text-left control-label col-sm-3">Unit Coversion:</label>
            <label class="text-left control-label col-sm-3"></label>
          </div>
             <?php $count=0; foreach($getlistdata as $listdata){ ?>
           <div id="mainrow<?php echo $count; ?>"> <div class="form-group">
              
              <div class="col-sm-3">
                <!-- <select class="form-control" name="locations[<?php echo $count; ?>]['location_name']">
                  <option value="0">Select Location.</option>
                 <?php foreach($locations as $locationdata){ ?>
                 <option <?php if($locationdata['location_id']==$listdata['location_id'])echo 'selected'; ?> value="<?php echo $locationdata['location_id']; ?>"><?php echo $locationdata['location_name']; ?></option>
                 <?php } ?>
                </select> -->
                <input type="text"  class="form-control" name="locations[<?php echo $count; ?>]['location_name']" value="<?php echo $listdata['location_id']; ?>">
              </div>

           
              
              <div class="col-sm-3">
                <input type="number"  name="locations[<?php echo $count; ?>]['location_quantity']" value="<?php echo $listdata['location_quantity']; ?>" class="form-control">
              </div>
            
              
              <div class="col-sm-3">
                <!-- <select class="form-control" name="locations[<?php echo $count; ?>]['location_unit']">
                  <option value="0">Select Unit.</option>
                       <?php foreach($unitconversions as $unit){ ?>
                 <option  <?php if($unit['unit_conversion_product_id']==$listdata['unit_id'])echo 'selected'; ?> value="<?php echo $unit['unit_conversion_product_id']; ?>"><?php echo $unit['name']; ?></option>
                 <?php } ?>
                </select> -->
                <input type="text"  class="form-control" name="locations[<?php echo $count; ?>]['location_unit']" value="<?php echo $listdata['unit_id']; ?>">
              </div>
              <div class="col-sm-3">
                <button class="btn btn-danger" onclick="removerow('<?php echo $count; ?>')" type="button"><i class="fa fa-minus-circle"></i></button>
              </div>
            </div>
            </div>
            <?php } ?>
            <div id="appendhere"></div>
            <div class="col-sm-2 col-md-offset-2">
              <button data-count="<?php echo $count; ?>" id="addmore" type="button" class="btn btn-info"><i class="fa fa-plus"></i></button>
            </div>
          </div>

          <script type="text/javascript">
            $("#addmore").click(function(){
              var counter=$("#addmore").attr('data-count');
              counter++;
              var htmlAdd='';
              htmlAdd +='<div id="mainrow'+counter+'"><div class="form-group">';
              htmlAdd +='       <div class="col-sm-3">';
              /*htmlAdd +='         <select class="form-control" name="locations['+counter+'][\'location_name\']">';
              htmlAdd +='           <option value="0">Select Location.</option>';
              htmlAdd +='           <?php foreach($locations as $locationdata){ ?>';
              htmlAdd +='           <option value="<?php echo $locationdata["location_id"]; ?>"><?php echo $locationdata["location_name"]; ?></option>';
              htmlAdd +='           <?php } ?>';
              htmlAdd +='</select>';*/
              htmlAdd +='<input type="text"  class="form-control" name="locations['+counter+'][\'location_name\']" value="">';
              htmlAdd +='</div>';
             
              htmlAdd +='<div class="col-sm-3">';
              htmlAdd +='<input type="number"  name="locations['+counter+'][\'location_quantity\']" value="" class="form-control">';
              htmlAdd +=' </div>';
            
              htmlAdd +=' <div class="col-sm-3">';
             /* htmlAdd +=' <select class="form-control" name="locations['+counter+'][\'location_unit\']">';
              htmlAdd +=' <option value="0">Select Unit.</option>';
              htmlAdd +='  <?php foreach($unitconversions as $unit){ ?>';
              htmlAdd +='  <option value="<?php echo $unit["unit_conversion_product_id"]; ?>"><?php echo $unit["name"]; ?></option>';
              htmlAdd +=' <?php } ?>';
              htmlAdd +=' </select>';*/
              htmlAdd +='<input type="text"  class="form-control" name="locations['+counter+'][\'location_unit\']" value="">';
              htmlAdd +='  </div><div class="col-sm-3"><button onclick="removerow('+counter+')"  class="btn btn-danger" type="button"><i class="fa fa-minus-circle"></i></button></div>';

              htmlAdd +=' </div></div>';
              
              $("#appendhere").append(htmlAdd);
              $("#addmore").attr('data-count',counter);


            });
            function removerow(row_num){
               $("#mainrow"+row_num).remove();
            }
          </script>

            <div class="tab-pane active" id="tab-general">
              <ul class="nav nav-tabs" id="language">
                <?php foreach ($languages as $language) { ?>
                <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                <?php } ?>
              </ul>
              <div class="tab-content">
                <?php foreach ($languages as $language) { ?>
                <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-name<?php echo $language['language_id']; ?>"><?php echo $entry_name; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="product_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_name[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="product_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['description'] : ''; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-meta-title<?php echo $language['language_id']; ?>"><?php echo $entry_meta_title; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="product_description[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_title'] : ''; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_meta_title[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_meta_title[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-description<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="product_description[<?php echo $language['language_id']; ?>][meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-keyword<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keyword; ?></label>
                    <div class="col-sm-10">
                      <textarea name="product_description[<?php echo $language['language_id']; ?>][meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-tag<?php echo $language['language_id']; ?>"><span data-toggle="tooltip" title="<?php echo $help_tag; ?>"><?php echo $entry_tag; ?></span></label>
                    <div class="col-sm-10">
                      <input type="text" name="product_description[<?php echo $language['language_id']; ?>][tag]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['tag'] : ''; ?>" placeholder="<?php echo $entry_tag; ?>" id="input-tag<?php echo $language['language_id']; ?>" class="form-control" />
                    </div>
                  </div>
                </div>
                <?php } ?>
              </div>
            </div>
            <div class="tab-pane" id="tab-data">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
                <div class="col-sm-10">
                  <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                  <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-model"><?php echo $entry_model; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="model" value="<?php echo $model; ?>" placeholder="<?php echo $entry_model; ?>" id="input-model" class="form-control" />
                  <?php if ($error_model) { ?>
                  <div class="text-danger"><?php echo $error_model; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-sku"><span data-toggle="tooltip" title="<?php echo $help_sku; ?>"><?php echo $entry_sku; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="sku" value="<?php echo $sku; ?>" placeholder="<?php echo $entry_sku; ?>" id="input-sku" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-upc"><span data-toggle="tooltip" title="<?php echo $help_upc; ?>"><?php echo $entry_upc; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="upc" value="<?php echo $upc; ?>" placeholder="<?php echo $entry_upc; ?>" id="input-upc" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-ean"><span data-toggle="tooltip" title="<?php echo $help_ean; ?>"><?php echo $entry_ean; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="ean" value="<?php echo $ean; ?>" placeholder="<?php echo $entry_ean; ?>" id="input-ean" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-jan"><span data-toggle="tooltip" title="<?php echo $help_jan; ?>"><?php echo $entry_jan; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="jan" value="<?php echo $jan; ?>" placeholder="<?php echo $entry_jan; ?>" id="input-jan" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-isbn"><span data-toggle="tooltip" title="<?php echo $help_isbn; ?>"><?php echo $entry_isbn; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="isbn" value="<?php echo $isbn; ?>" placeholder="<?php echo $entry_isbn; ?>" id="input-isbn" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-mpn"><span data-toggle="tooltip" title="<?php echo $help_mpn; ?>"><?php echo $entry_mpn; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="mpn" value="<?php echo $mpn; ?>" placeholder="<?php echo $entry_mpn; ?>" id="input-mpn" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-location"><?php echo $entry_location; ?></label>
                <div class="col-sm-10">
                  
                  <input type="text" name="location" value="<?php echo $location; ?>" placeholder="<?php echo $entry_location; ?>" id="input-location" class="form-control" />
                </div>
              </div>			
<?php if ($prm_access_permission && $laccess) { ?>
			<div class="form-group">
<style type="text/css">
#show_restock_cost {
    display: none;
}
</style> 						
<script type="text/javascript">
$(document).ready(function(){
	if (document.getElementById('form-product').price.value > 0 && (document.getElementById('form-product').cost.value > 0 || document.getElementById('form-product').cost_amount.value > 0)) {
		var profit = (parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)).toFixed(4)
		var profit_margin = (((parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)) / parseFloat(document.getElementById('form-product').price.value))*100).toFixed(2)+'%'
		var profit_markup = (((parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)) / parseFloat(document.getElementById('form-product').cost.value))*100).toFixed(2)+'%'
	} else {
		var profit = '0'
		var profit_margin = '0%'
		var profit_markup = '0%'
	}
	document.getElementById('form-product').profit.value = profit
	document.getElementById('form-product').profit_margin.value = profit_margin
	document.getElementById('form-product').profit_markup.value = profit_markup
	
	$('#input-costing-method').on('change', function() {
    	if ($("#cost_average").is(":selected")) {		
			
			$("#cont").html("<span style='color:#ed9999;'><?php echo $text_cost_average_set; ?></span>");
			$(".show_restock_cost").show();
			
			if (document.getElementById('form-product').qty_by_option_checkbox.checked == 1) {			
				if (document.getElementById('form-product').restock_quantity.value != '' && document.getElementById('form-product').restock_quantity.value != 0) {
					var cost = parseFloat(document.getElementById('form-product').cost_amount.value) + parseFloat((document.getElementById('form-product').cost_percentage.value / 100)*document.getElementById('form-product').price.value) + parseFloat(document.getElementById('form-product').cost_additional.value)
					var restock_quantity_temp = parseFloat(document.getElementById('form-product').restock_quantity_temp.value) + ((parseFloat(document.getElementById('form-product').quantity_temp.value) - parseFloat(document.getElementById('form-product').restock_quantity_temp.value)) - parseFloat(document.getElementById('form-product').remove_temp.value))
					var oldcost = parseFloat(document.getElementById('form-product').oldcost_temp.value)
					var stockquantity = parseFloat(document.getElementById('form-product').quantity_temp.value) - (parseFloat(document.getElementById('form-product').restock_quantity_temp.value) + ((parseFloat(document.getElementById('form-product').quantity_temp.value) - parseFloat(document.getElementById('form-product').restock_quantity_temp.value)) - parseFloat(document.getElementById('form-product').remove_temp.value)))
					var quantity_temp = parseFloat(document.getElementById('form-product').quantity_temp.value)
					var totalcost_opt = (((oldcost*stockquantity)+(restock_quantity_temp*cost))/quantity_temp).toFixed(4)
					document.getElementById('form-product').restock_cost.value = (parseFloat(document.getElementById('form-product').cost_amount.value) + parseFloat((document.getElementById('form-product').cost_percentage.value / 100)*document.getElementById('form-product').price.value) + parseFloat(document.getElementById('form-product').cost_additional.value)).toFixed(4)						
					document.getElementById('form-product').cost.value = totalcost_opt
				}
				if (document.getElementById('form-product').restock_quantity.value == '' || document.getElementById('form-product').restock_quantity.value == 0) {
					var totalcost_opt = (parseFloat(document.getElementById('form-product').oldcost_temp.value)).toFixed(4)
					document.getElementById('form-product').restock_cost.value = (parseFloat(document.getElementById('form-product').cost_amount.value) + parseFloat((document.getElementById('form-product').cost_percentage.value / 100)*document.getElementById('form-product').price.value) + parseFloat(document.getElementById('form-product').cost_additional.value)).toFixed(4)					
					document.getElementById('form-product').cost.value = totalcost_opt
				}
				if (document.getElementById('form-product').price.value > 0 && (document.getElementById('form-product').cost.value > 0 || document.getElementById('form-product').cost_amount.value > 0)) {
					var profit = (parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)).toFixed(4)
					var profit_margin = (((parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)) / parseFloat(document.getElementById('form-product').price.value))*100).toFixed(2)+'%'
					var profit_markup = (((parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)) / parseFloat(document.getElementById('form-product').cost.value))*100).toFixed(2)+'%'
				} else {
					var profit = '0'
					var profit_margin = '0%'
					var profit_markup = '0%'
				}
				document.getElementById('form-product').profit.value = profit
				document.getElementById('form-product').profit_margin.value = profit_margin
				document.getElementById('form-product').profit_markup.value = profit_markup				
			} else if (document.getElementById('form-product').qty_by_option_checkbox.checked == 0) {			
				if (document.getElementById('form-product').restock_quantity.value != '' && document.getElementById('form-product').restock_quantity.value != 0) {				
					var cost = parseFloat(document.getElementById('form-product').cost_amount.value) + parseFloat((document.getElementById('form-product').cost_percentage.value / 100)*document.getElementById('form-product').price.value) + parseFloat(document.getElementById('form-product').cost_additional.value)
					var restock_quantity = parseFloat(document.getElementById('form-product').restock_quantity.value)
					var oldcost = parseFloat(document.getElementById('form-product').oldcost_temp.value)
					var stockquantity = parseFloat(document.getElementById('form-product').stockquantity_temp.value)
					var quantity = parseFloat(document.getElementById('form-product').quantity.value)
					var totalcost = (((oldcost*stockquantity)+(restock_quantity*cost))/quantity).toFixed(4)
					document.getElementById('form-product').restock_cost.value = (parseFloat(document.getElementById('form-product').cost_amount.value) + parseFloat((document.getElementById('form-product').cost_percentage.value / 100)*document.getElementById('form-product').price.value) + parseFloat(document.getElementById('form-product').cost_additional.value)).toFixed(4)
					document.getElementById('form-product').cost.value = totalcost
				}					
				if (document.getElementById('form-product').restock_quantity.value == '' || document.getElementById('form-product').restock_quantity.value == 0) {				
					var totalcost = (parseFloat(document.getElementById('form-product').oldcost_temp.value)).toFixed(4)
					document.getElementById('form-product').restock_cost.value = (parseFloat(document.getElementById('form-product').cost_amount.value) + parseFloat((document.getElementById('form-product').cost_percentage.value / 100)*document.getElementById('form-product').price.value) + parseFloat(document.getElementById('form-product').cost_additional.value)).toFixed(4)
					document.getElementById('form-product').cost.value = totalcost
				}
				if (document.getElementById('form-product').price.value > 0 && (document.getElementById('form-product').cost.value > 0 || document.getElementById('form-product').cost_amount.value > 0)) {
					var profit = (parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)).toFixed(4)
					var profit_margin = (((parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)) / parseFloat(document.getElementById('form-product').price.value))*100).toFixed(2)+'%'
					var profit_markup = (((parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)) / parseFloat(document.getElementById('form-product').cost.value))*100).toFixed(2)+'%'
				} else {
					var profit = '0'
					var profit_margin = '0%'
					var profit_markup = '0%'
				}
				document.getElementById('form-product').profit.value = profit
				document.getElementById('form-product').profit_margin.value = profit_margin
				document.getElementById('form-product').profit_markup.value = profit_markup					
			}
			
    	} else if ($("#cost_fixed").is(":selected")) {		
		
        	$("#cont").html("<span style='color:#ed9999;'><?php echo $text_cost; ?></span>");
			$(".show_restock_cost").hide();
			
			if (document.getElementById('form-product').qty_by_option_checkbox.checked == 1) {			
				if (document.getElementById('form-product').restock_quantity.value != '' && document.getElementById('form-product').restock_quantity.value != 0) {
					var totalcost_opt = (parseFloat(document.getElementById('form-product').cost_amount.value) + parseFloat((document.getElementById('form-product').cost_percentage.value / 100)*document.getElementById('form-product').price.value) + parseFloat(document.getElementById('form-product').cost_additional.value)).toFixed(4)
					document.getElementById('form-product').cost.value = totalcost_opt
				}
				if (document.getElementById('form-product').restock_quantity.value == '' || document.getElementById('form-product').restock_quantity.value == 0) {
					var totalcost_opt = (parseFloat(document.getElementById('form-product').cost_amount.value) + parseFloat((document.getElementById('form-product').cost_percentage.value / 100)*document.getElementById('form-product').price.value) + parseFloat(document.getElementById('form-product').cost_additional.value)).toFixed(4)
					document.getElementById('form-product').cost.value = totalcost_opt
				}
				if (document.getElementById('form-product').price.value > 0 && (document.getElementById('form-product').cost.value > 0 || document.getElementById('form-product').cost_amount.value > 0)) {
					var profit = (parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)).toFixed(4)
					var profit_margin = (((parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)) / parseFloat(document.getElementById('form-product').price.value))*100).toFixed(2)+'%'
					var profit_markup = (((parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)) / parseFloat(document.getElementById('form-product').cost.value))*100).toFixed(2)+'%'
				} else {
					var profit = '0'
					var profit_margin = '0%'
					var profit_markup = '0%'
				}
				document.getElementById('form-product').profit.value = profit
				document.getElementById('form-product').profit_margin.value = profit_margin
				document.getElementById('form-product').profit_markup.value = profit_markup					
			} else if (document.getElementById('form-product').qty_by_option_checkbox.checked == 0) {			
				if (document.getElementById('form-product').restock_quantity.value != '' && document.getElementById('form-product').restock_quantity.value != 0) {				
					var totalcost = (parseFloat(document.getElementById('form-product').cost_amount.value) + parseFloat((document.getElementById('form-product').cost_percentage.value / 100)*document.getElementById('form-product').price.value) + parseFloat(document.getElementById('form-product').cost_additional.value)).toFixed(4)
					document.getElementById('form-product').cost.value = totalcost
				}					
				if (document.getElementById('form-product').restock_quantity.value == '' || document.getElementById('form-product').restock_quantity.value == 0) {				
					var totalcost = (parseFloat(document.getElementById('form-product').cost_amount.value) + parseFloat((document.getElementById('form-product').cost_percentage.value / 100)*document.getElementById('form-product').price.value) + parseFloat(document.getElementById('form-product').cost_additional.value)).toFixed(4)
					document.getElementById('form-product').cost.value = totalcost
				}
				if (document.getElementById('form-product').price.value > 0 && (document.getElementById('form-product').cost.value > 0 || document.getElementById('form-product').cost_amount.value > 0)) {
					var profit = (parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)).toFixed(4)
					var profit_margin = (((parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)) / parseFloat(document.getElementById('form-product').price.value))*100).toFixed(2)+'%'
					var profit_markup = (((parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)) / parseFloat(document.getElementById('form-product').cost.value))*100).toFixed(2)+'%'
				} else {
					var profit = '0'
					var profit_margin = '0%'
					var profit_markup = '0%'
				}
				document.getElementById('form-product').profit.value = profit
				document.getElementById('form-product').profit_margin.value = profit_margin
				document.getElementById('form-product').profit_markup.value = profit_markup				
			}			
	    }
	});	

  	$("#price").keypress(function (e) {
     	if (e.which != 8 && e.which != 0 && (e.which < 46 || e.which > 57)) {
			return false;
    	}
	});	
  	$("#cost_amount").keypress(function (e) {
     	if (e.which != 8 && e.which != 0 && (e.which < 46 || e.which > 57)) {
			return false;
    	}
	});
  	$("#cost_percentage").keypress(function (e) {
     	if (e.which != 8 && e.which != 0 && (e.which < 46 || e.which > 57)) {
			return false;
    	}
	});
  	$("#cost_additional").keypress(function (e) {
     	if (e.which != 8 && e.which != 0 && (e.which < 46 || e.which > 57)) {
			return false;
    	}
	}); 
  	$("#restock_quantity").keypress(function (e) {
     	if (e.which != 8 && e.which != 0 && (e.which < 45 || e.which > 57)) {
			return false;
    	}
	});   
	
	$("#qty_by_option_checkbox").change(function() {
    if (this.checked) {
		$("#restock_quantity").prop('readonly', 'readonly');
		$("#restock_quantity").css('background-color', '#EEE');
		$("#qupdatemsg").html("<?php echo $text_qty_set_by_option; ?>").show().delay(1000).fadeOut(1500);
		if (document.getElementById('form-product').restock_quantity_temp.value == 0 && document.getElementById('form-product').quantity_temp.value == 0 && document.getElementById('form-product').remove_temp.value == 0) {
			var restock = 0;
			var total = 0;

	  		$('.addRestock').each(function () {
			  	restock += parseInt($(this).val()); 
			});	
	 	 	$('.addOption').each(function () {
			  	restock += parseInt($(this).val()); 
			});
	
			$('.addTotal').each(function () {
			  	total += parseInt($(this).val()); 
	 		});		
			$('.addOption').each(function () {
			 	total += parseInt($(this).val()); 
	  		});			
					
			$('#restock_quantity').val(restock);
			$('#quantity').val(total);
		}

		if (document.getElementById('input-costing-method').value == '1') {
			if (document.getElementById('form-product').restock_quantity.value != '' && document.getElementById('form-product').restock_quantity.value != 0) {
				var cost = parseFloat(document.getElementById('form-product').cost_amount.value) + parseFloat((document.getElementById('form-product').cost_percentage.value / 100)*document.getElementById('form-product').price.value) + parseFloat(document.getElementById('form-product').cost_additional.value)
				var restock_quantity_temp = parseFloat(document.getElementById('form-product').restock_quantity_temp.value) + ((parseFloat(document.getElementById('form-product').quantity_temp.value) - parseFloat(document.getElementById('form-product').restock_quantity_temp.value)) - parseFloat(document.getElementById('form-product').remove_temp.value))
				var oldcost = parseFloat(document.getElementById('form-product').oldcost_temp.value)
				var stockquantity = parseFloat(document.getElementById('form-product').quantity_temp.value) - (parseFloat(document.getElementById('form-product').restock_quantity_temp.value) + ((parseFloat(document.getElementById('form-product').quantity_temp.value) - parseFloat(document.getElementById('form-product').restock_quantity_temp.value)) - parseFloat(document.getElementById('form-product').remove_temp.value)))
				var quantity_temp = parseFloat(document.getElementById('form-product').quantity_temp.value)
				var totalcost_opt = (((oldcost*stockquantity)+(restock_quantity_temp*cost))/quantity_temp).toFixed(4)
				document.getElementById('form-product').restock_cost.value = (parseFloat(document.getElementById('form-product').cost_amount.value) + parseFloat((document.getElementById('form-product').cost_percentage.value / 100)*document.getElementById('form-product').price.value) + parseFloat(document.getElementById('form-product').cost_additional.value)).toFixed(4)
				document.getElementById('form-product').cost.value = totalcost_opt
			}
			if (document.getElementById('form-product').restock_quantity.value == '' || document.getElementById('form-product').restock_quantity.value == 0) {
				var totalcost_opt = (parseFloat(document.getElementById('form-product').oldcost_temp.value)).toFixed(4)
				document.getElementById('form-product').restock_cost.value = (parseFloat(document.getElementById('form-product').cost_amount.value) + parseFloat((document.getElementById('form-product').cost_percentage.value / 100)*document.getElementById('form-product').price.value) + parseFloat(document.getElementById('form-product').cost_additional.value)).toFixed(4)
				document.getElementById('form-product').cost.value = totalcost_opt
			}
			if (document.getElementById('form-product').price.value > 0 && (document.getElementById('form-product').cost.value > 0 || document.getElementById('form-product').cost_amount.value > 0)) {
				var profit = (parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)).toFixed(4)
				var profit_margin = (((parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)) / parseFloat(document.getElementById('form-product').price.value))*100).toFixed(2)+'%'
				var profit_markup = (((parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)) / parseFloat(document.getElementById('form-product').cost.value))*100).toFixed(2)+'%'
			} else {
				var profit = '0'
				var profit_margin = '0%'
				var profit_markup = '0%'
			}
			document.getElementById('form-product').profit.value = profit
			document.getElementById('form-product').profit_margin.value = profit_margin
			document.getElementById('form-product').profit_markup.value = profit_markup				
		} else if (document.getElementById('input-costing-method').value == '0') {
			if (document.getElementById('form-product').restock_quantity.value != '' && document.getElementById('form-product').restock_quantity.value != 0) {				
				var totalcost = (parseFloat(document.getElementById('form-product').cost_amount.value) + parseFloat((document.getElementById('form-product').cost_percentage.value / 100)*document.getElementById('form-product').price.value) + parseFloat(document.getElementById('form-product').cost_additional.value)).toFixed(4)
				document.getElementById('form-product').cost.value = totalcost
			}					
			if (document.getElementById('form-product').restock_quantity.value == '' || document.getElementById('form-product').restock_quantity.value == 0) {				
				var totalcost = (parseFloat(document.getElementById('form-product').cost_amount.value) + parseFloat((document.getElementById('form-product').cost_percentage.value / 100)*document.getElementById('form-product').price.value) + parseFloat(document.getElementById('form-product').cost_additional.value)).toFixed(4)
				document.getElementById('form-product').cost.value = totalcost
			}
			if (document.getElementById('form-product').price.value > 0 && (document.getElementById('form-product').cost.value > 0 || document.getElementById('form-product').cost_amount.value > 0)) {
				var profit = (parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)).toFixed(4)
				var profit_margin = (((parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)) / parseFloat(document.getElementById('form-product').price.value))*100).toFixed(2)+'%'
				var profit_markup = (((parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)) / parseFloat(document.getElementById('form-product').cost.value))*100).toFixed(2)+'%'
			} else {
				var profit = '0'
				var profit_margin = '0%'
				var profit_markup = '0%'
			}
			document.getElementById('form-product').profit.value = profit
			document.getElementById('form-product').profit_margin.value = profit_margin
			document.getElementById('form-product').profit_markup.value = profit_markup			
		}	
				
	} else {
		$("#restock_quantity").removeAttr('readonly');
		$("#restock_quantity").css('background-color', '');	
		
		var restock = 0;
		var quantity = parseFloat(document.getElementById('form-product').stockquantity_temp.value)
		document.getElementById('form-product').restock_quantity.value = restock	
		document.getElementById('form-product').quantity.value = quantity	
		
		if (document.getElementById('input-costing-method').value == '1') {
			if (document.getElementById('form-product').restock_quantity.value != '' && document.getElementById('form-product').restock_quantity.value != 0) {
				var cost = parseFloat(document.getElementById('form-product').cost_amount.value) + parseFloat((document.getElementById('form-product').cost_percentage.value / 100)*document.getElementById('form-product').price.value) + parseFloat(document.getElementById('form-product').cost_additional.value)
				var restock_quantity_temp = parseFloat(document.getElementById('form-product').restock_quantity_temp.value) + ((parseFloat(document.getElementById('form-product').quantity_temp.value) - parseFloat(document.getElementById('form-product').restock_quantity_temp.value)) - parseFloat(document.getElementById('form-product').remove_temp.value))
				var oldcost = parseFloat(document.getElementById('form-product').oldcost_temp.value)
				var stockquantity = parseFloat(document.getElementById('form-product').quantity_temp.value) - (parseFloat(document.getElementById('form-product').restock_quantity_temp.value) + ((parseFloat(document.getElementById('form-product').quantity_temp.value) - parseFloat(document.getElementById('form-product').restock_quantity_temp.value)) - parseFloat(document.getElementById('form-product').remove_temp.value)))
				var quantity_temp = parseFloat(document.getElementById('form-product').quantity_temp.value)
				var totalcost_opt = (((oldcost*stockquantity)+(restock_quantity_temp*cost))/quantity_temp).toFixed(4)
				document.getElementById('form-product').restock_cost.value = (parseFloat(document.getElementById('form-product').cost_amount.value) + parseFloat((document.getElementById('form-product').cost_percentage.value / 100)*document.getElementById('form-product').price.value) + parseFloat(document.getElementById('form-product').cost_additional.value)).toFixed(4)
				document.getElementById('form-product').cost.value = totalcost_opt
			}
			if (document.getElementById('form-product').restock_quantity.value == '' || document.getElementById('form-product').restock_quantity.value == 0) {
				var totalcost_opt = (parseFloat(document.getElementById('form-product').oldcost_temp.value)).toFixed(4)
				document.getElementById('form-product').restock_cost.value = (parseFloat(document.getElementById('form-product').cost_amount.value) + parseFloat((document.getElementById('form-product').cost_percentage.value / 100)*document.getElementById('form-product').price.value) + parseFloat(document.getElementById('form-product').cost_additional.value)).toFixed(4)
				document.getElementById('form-product').cost.value = totalcost_opt
			}
			if (document.getElementById('form-product').price.value > 0 && (document.getElementById('form-product').cost.value > 0 || document.getElementById('form-product').cost_amount.value > 0)) {
				var profit = (parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)).toFixed(4)
				var profit_margin = (((parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)) / parseFloat(document.getElementById('form-product').price.value))*100).toFixed(2)+'%'
				var profit_markup = (((parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)) / parseFloat(document.getElementById('form-product').cost.value))*100).toFixed(2)+'%'
			} else {
				var profit = '0'
				var profit_margin = '0%'
				var profit_markup = '0%'
			}
			document.getElementById('form-product').profit.value = profit
			document.getElementById('form-product').profit_margin.value = profit_margin
			document.getElementById('form-product').profit_markup.value = profit_markup				
		} else if (document.getElementById('input-costing-method').value == '0') {
			if (document.getElementById('form-product').restock_quantity.value != '' && document.getElementById('form-product').restock_quantity.value != 0) {				
				var totalcost = (parseFloat(document.getElementById('form-product').cost_amount.value) + parseFloat((document.getElementById('form-product').cost_percentage.value / 100)*document.getElementById('form-product').price.value) + parseFloat(document.getElementById('form-product').cost_additional.value)).toFixed(4)
				document.getElementById('form-product').cost.value = totalcost
			}					
			if (document.getElementById('form-product').restock_quantity.value == '' || document.getElementById('form-product').restock_quantity.value == 0) {				
				var totalcost = (parseFloat(document.getElementById('form-product').cost_amount.value) + parseFloat((document.getElementById('form-product').cost_percentage.value / 100)*document.getElementById('form-product').price.value) + parseFloat(document.getElementById('form-product').cost_additional.value)).toFixed(4)
				document.getElementById('form-product').cost.value = totalcost
			}
			if (document.getElementById('form-product').price.value > 0 && (document.getElementById('form-product').cost.value > 0 || document.getElementById('form-product').cost_amount.value > 0)) {
				var profit = (parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)).toFixed(4)
				var profit_margin = (((parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)) / parseFloat(document.getElementById('form-product').price.value))*100).toFixed(2)+'%'
				var profit_markup = (((parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)) / parseFloat(document.getElementById('form-product').cost.value))*100).toFixed(2)+'%'
			} else {
				var profit = '0'
				var profit_margin = '0%'
				var profit_markup = '0%'
			}
			document.getElementById('form-product').profit.value = profit
			document.getElementById('form-product').profit_margin.value = profit_margin
			document.getElementById('form-product').profit_markup.value = profit_markup				
		}			
    }
	});		     
});

$(function () {
    $('.button-checkbox').each(function () {

        // Settings
        var $widget = $(this),
            $button = $widget.find('button'),
            $checkbox = $widget.find('input:checkbox'),
            color = $button.data('color'),
            settings = {
                on: {
                    icon: 'fa fa-check'
                },
                off: {
                    icon: ''
                }
            };

        // Event Handlers
        $button.on('click', function () {
            $checkbox.prop('checked', !$checkbox.is(':checked'));
            $checkbox.triggerHandler('change');
            updateDisplay();
        });
        $checkbox.on('change', function () {
            updateDisplay();
        });

        // Actions
        function updateDisplay() {
            var isChecked = $checkbox.is(':checked');

            // Set the button's state
            $button.data('state', (isChecked) ? "on" : "off");

            // Set the button's icon
            $button.find('.fa')
                .removeClass()
                .addClass('fa ' + settings[$button.data('state')].icon);

            // Update the button's color
            if (isChecked) {
                $button
                    .removeClass('btn-default')
                    .addClass('btn btn-' + color + ' active');
            }
            else {
                $button
                    .removeClass('btn btn-' + color + ' active')
                    .addClass('btn btn-default');
            }
        }

        // Initialization
        function init() {

            updateDisplay();

            // Inject the icon if applicable
            if ($button.find('.fa').length == 0) {
                $button.prepend('<i class="fa ' + settings[$button.data('state')].icon + '"></i> ');
            }
        }
        init();
    });
});

function addRestock() {
	var restock = 0;
	var total = 0;

	$('.addRestock').each(function () {
	  	restock += parseInt($(this).val()); 
	});
	$('.addOption').each(function () {
	  	restock += parseInt($(this).val()); 
	});
			
	$('.addTotal').each(function () {
	 	total += parseInt($(this).val()); 
  	});	
	$('.addOption').each(function () {
	 	total += parseInt($(this).val()); 
  	});	
		
	$('#restock_quantity_temp').val(restock);
	$('#quantity_temp').val(total);			
}

function addOption() {
	var restock = 0;
	var total = 0;

  	$('.addRestock').each(function () {
	  	restock += parseInt($(this).val()); 
	});	
  	$('.addOption').each(function () {
	  	restock += parseInt($(this).val()); 
	});
			
  	$('.addTotal').each(function () {
	 	total += parseInt($(this).val()); 
	});		
  	$('.addOption').each(function () {
	 	total += parseInt($(this).val()); 
	});				

	$('#restock_quantity_temp').val(restock);
	$('#quantity_temp').val(total);
}	

function optRemove() {
	var total = 0;
			
  	$('.addTotal').each(function () {
	 	total += parseInt($(this).val()); 
	});		
  	$('.addOption').each(function () {
	 	total += parseInt($(this).val()); 
	});				

	$('#quantity_temp').val(total);			
}		

function optRemoveGroup() {
	var total = 0;
			
  	$('.addTotal').each(function () {
	 	total += parseInt($(this).val()); 
	});		
  	$('.addOption').each(function () {
	 	total += parseInt($(this).val()); 
	});				

	$('#quantity_temp').val(total);		
}	

$(document).on('mouseup', '#optInitialize', function(event) {
	var total = 0;
			
	$('.addTotal').each(function () {
	 	total += parseInt($(this).val()); 
  	});		
	$('.addOption').each(function () {
	 	total += parseInt($(this).val()); 
  	});				

	$('#quantity_temp').val(total);
	$('#remove_temp').val(total);
	$(this).off(event);
});
	 
function totalcost() {
	if (document.getElementById('input-costing-method').value == '0') {
		var totalcost = (parseFloat(document.getElementById('form-product').cost_amount.value) + parseFloat((document.getElementById('form-product').cost_percentage.value / 100)*document.getElementById('form-product').price.value) + parseFloat(document.getElementById('form-product').cost_additional.value)).toFixed(4)
		document.getElementById('form-product').cost.value = totalcost
	}	
	
	if (document.getElementById('input-costing-method').value == '1') {
		if ($('#qty_by_option_checkbox').is(':checked')) {
			if (document.getElementById('form-product').restock_quantity.value != '' && document.getElementById('form-product').restock_quantity.value != 0) {
				var cost = parseFloat(document.getElementById('form-product').cost_amount.value) + parseFloat((document.getElementById('form-product').cost_percentage.value / 100)*document.getElementById('form-product').price.value) + parseFloat(document.getElementById('form-product').cost_additional.value)
				var restock_quantity_temp = parseFloat(document.getElementById('form-product').restock_quantity_temp.value) + ((parseFloat(document.getElementById('form-product').quantity_temp.value) - parseFloat(document.getElementById('form-product').restock_quantity_temp.value)) - parseFloat(document.getElementById('form-product').remove_temp.value))
				var oldcost = parseFloat(document.getElementById('form-product').oldcost_temp.value)
				var stockquantity = parseFloat(document.getElementById('form-product').quantity_temp.value) - (parseFloat(document.getElementById('form-product').restock_quantity_temp.value) + ((parseFloat(document.getElementById('form-product').quantity_temp.value) - parseFloat(document.getElementById('form-product').restock_quantity_temp.value)) - parseFloat(document.getElementById('form-product').remove_temp.value)))
				var quantity_temp = parseFloat(document.getElementById('form-product').quantity_temp.value)
				var totalcost_opt = (((oldcost*stockquantity)+(restock_quantity_temp*cost))/quantity_temp).toFixed(4)
				document.getElementById('form-product').restock_cost.value = (parseFloat(document.getElementById('form-product').cost_amount.value) + parseFloat((document.getElementById('form-product').cost_percentage.value / 100)*document.getElementById('form-product').price.value) + parseFloat(document.getElementById('form-product').cost_additional.value)).toFixed(4)
				document.getElementById('form-product').cost.value = totalcost_opt
			}
		} else {
			if (document.getElementById('form-product').restock_quantity.value != '' && document.getElementById('form-product').restock_quantity.value != 0) {		
				var cost = parseFloat(document.getElementById('form-product').cost_amount.value) + parseFloat((document.getElementById('form-product').cost_percentage.value / 100)*document.getElementById('form-product').price.value) + parseFloat(document.getElementById('form-product').cost_additional.value)
				var restock_quantity = parseFloat(document.getElementById('form-product').restock_quantity.value)
				var oldcost = parseFloat(document.getElementById('form-product').oldcost_temp.value)
				var stockquantity = parseFloat(document.getElementById('form-product').stockquantity_temp.value)
				var quantity = parseFloat(document.getElementById('form-product').quantity.value)
				var totalcost = (((oldcost*stockquantity)+(restock_quantity*cost))/quantity).toFixed(4)
				document.getElementById('form-product').restock_cost.value = (parseFloat(document.getElementById('form-product').cost_amount.value) + parseFloat((document.getElementById('form-product').cost_percentage.value / 100)*document.getElementById('form-product').price.value) + parseFloat(document.getElementById('form-product').cost_additional.value)).toFixed(4)
				document.getElementById('form-product').cost.value = totalcost
			}
		}					
		if (document.getElementById('form-product').restock_quantity.value == '' || document.getElementById('form-product').restock_quantity.value == 0) {				
			var totalcost = (parseFloat(document.getElementById('form-product').oldcost_temp.value)).toFixed(4)
			document.getElementById('form-product').restock_cost.value = (parseFloat(document.getElementById('form-product').cost_amount.value) + parseFloat((document.getElementById('form-product').cost_percentage.value / 100)*document.getElementById('form-product').price.value) + parseFloat(document.getElementById('form-product').cost_additional.value)).toFixed(4)
			document.getElementById('form-product').cost.value = totalcost
		}
	}
	if (document.getElementById('form-product').price.value > 0 && (document.getElementById('form-product').cost.value > 0 || document.getElementById('form-product').cost_amount.value > 0)) {
		var profit = (parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)).toFixed(4)
		var profit_margin = (((parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)) / parseFloat(document.getElementById('form-product').price.value))*100).toFixed(2)+'%'
		var profit_markup = (((parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)) / parseFloat(document.getElementById('form-product').cost.value))*100).toFixed(2)+'%'
	} else {
		var profit = '0'
		var profit_margin = '0%'
		var profit_markup = '0%'
	}
	document.getElementById('form-product').profit.value = profit
	document.getElementById('form-product').profit_margin.value = profit_margin
	document.getElementById('form-product').profit_markup.value = profit_markup		
}

function stockquantity() {
	if (document.getElementById('form-product').restock_quantity.value != '') {
			var stockquantity = parseFloat(document.getElementById('form-product').restock_quantity.value) + parseFloat(document.getElementById('form-product').stockquantity_temp.value)
			document.getElementById('form-product').quantity.value = stockquantity	
	}
	if (document.getElementById('form-product').price.value > 0 && (document.getElementById('form-product').cost.value > 0 || document.getElementById('form-product').cost_amount.value > 0)) {
		var profit = (parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)).toFixed(4)
		var profit_margin = (((parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)) / parseFloat(document.getElementById('form-product').price.value))*100).toFixed(2)+'%'
		var profit_markup = (((parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)) / parseFloat(document.getElementById('form-product').cost.value))*100).toFixed(2)+'%'
	} else {
		var profit = '0'
		var profit_margin = '0%'
		var profit_markup = '0%'
	}
	document.getElementById('form-product').profit.value = profit
	document.getElementById('form-product').profit_margin.value = profit_margin
	document.getElementById('form-product').profit_markup.value = profit_markup		
}

function totalcost_opt() {
  if ($('#qty_by_option_checkbox').is(':checked')) {
	if (document.getElementById('form-product').restock_quantity.value != '' && document.getElementById('form-product').restock_quantity.value != 0) {
		if (document.getElementById('input-costing-method').value == '1') {
			var cost = parseFloat(document.getElementById('form-product').cost_amount.value) + parseFloat((document.getElementById('form-product').cost_percentage.value / 100)*document.getElementById('form-product').price.value) + parseFloat(document.getElementById('form-product').cost_additional.value)
			var restock_quantity_temp = parseFloat(document.getElementById('form-product').restock_quantity_temp.value) + ((parseFloat(document.getElementById('form-product').quantity_temp.value) - parseFloat(document.getElementById('form-product').restock_quantity_temp.value)) - parseFloat(document.getElementById('form-product').remove_temp.value))
			var oldcost = parseFloat(document.getElementById('form-product').oldcost_temp.value)
			var stockquantity = parseFloat(document.getElementById('form-product').quantity_temp.value) - (parseFloat(document.getElementById('form-product').restock_quantity_temp.value) + ((parseFloat(document.getElementById('form-product').quantity_temp.value) - parseFloat(document.getElementById('form-product').restock_quantity_temp.value)) - parseFloat(document.getElementById('form-product').remove_temp.value)))
			var quantity_temp = parseFloat(document.getElementById('form-product').quantity_temp.value)
			var totalcost_opt = (((oldcost*stockquantity)+(restock_quantity_temp*cost))/quantity_temp).toFixed(4)
			document.getElementById('form-product').cost.value = totalcost_opt
		}
	}
	if (document.getElementById('form-product').restock_quantity.value == '' || document.getElementById('form-product').restock_quantity.value == 0) {
		if (document.getElementById('input-costing-method').value == '1') {			
			var totalcost_opt = (parseFloat(document.getElementById('form-product').oldcost_temp.value)).toFixed(4)
			document.getElementById('form-product').cost.value = totalcost_opt
		}
	}		
  }
	if (document.getElementById('form-product').price.value > 0 && (document.getElementById('form-product').cost.value > 0 || document.getElementById('form-product').cost_amount.value > 0)) {
		var profit = (parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)).toFixed(4)
		var profit_margin = (((parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)) / parseFloat(document.getElementById('form-product').price.value))*100).toFixed(2)+'%'
		var profit_markup = (((parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)) / parseFloat(document.getElementById('form-product').cost.value))*100).toFixed(2)+'%'
	} else {
		var profit = '0'
		var profit_margin = '0%'
		var profit_markup = '0%'
	}
	document.getElementById('form-product').profit.value = profit
	document.getElementById('form-product').profit_margin.value = profit_margin
	document.getElementById('form-product').profit_markup.value = profit_markup	
}

function restockquantity_opt() {
  if ($('#qty_by_option_checkbox').is(':checked')) {
	if (document.getElementById('form-product').restock_quantity.value != '') {
		var restockquantity_opt = parseFloat(document.getElementById('form-product').restock_quantity_temp.value) + ((parseFloat(document.getElementById('form-product').quantity_temp.value) - parseFloat(document.getElementById('form-product').restock_quantity_temp.value)) - parseFloat(document.getElementById('form-product').remove_temp.value))
		document.getElementById('form-product').restock_quantity.value = restockquantity_opt
	}
  }
	if (document.getElementById('form-product').price.value > 0 && (document.getElementById('form-product').cost.value > 0 || document.getElementById('form-product').cost_amount.value > 0)) {
		var profit = (parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)).toFixed(4)
		var profit_margin = (((parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)) / parseFloat(document.getElementById('form-product').price.value))*100).toFixed(2)+'%'
		var profit_markup = (((parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)) / parseFloat(document.getElementById('form-product').cost.value))*100).toFixed(2)+'%'
	} else {
		var profit = '0'
		var profit_margin = '0%'
		var profit_markup = '0%'
	}
	document.getElementById('form-product').profit.value = profit
	document.getElementById('form-product').profit_margin.value = profit_margin
	document.getElementById('form-product').profit_markup.value = profit_markup	
}

function quantity_opt() {
  if ($('#qty_by_option_checkbox').is(':checked')) {
	var quantity_opt = parseFloat(document.getElementById('form-product').quantity_temp.value)
	document.getElementById('form-product').quantity.value = quantity_opt
  }
	if (document.getElementById('form-product').price.value > 0 && (document.getElementById('form-product').cost.value > 0 || document.getElementById('form-product').cost_amount.value > 0)) {
		var profit = (parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)).toFixed(4)
		var profit_margin = (((parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)) / parseFloat(document.getElementById('form-product').price.value))*100).toFixed(2)+'%'
		var profit_markup = (((parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)) / parseFloat(document.getElementById('form-product').cost.value))*100).toFixed(2)+'%'
	} else {
		var profit = '0'
		var profit_margin = '0%'
		var profit_markup = '0%'
	}
	document.getElementById('form-product').profit.value = profit
	document.getElementById('form-product').profit_margin.value = profit_margin
	document.getElementById('form-product').profit_markup.value = profit_markup		
}

function restockquantity_ini() {
	if (document.getElementById('form-product').restock_quantity.value != '') {
		var restockquantity_opt = parseFloat(document.getElementById('form-product').restock_quantity_temp.value) + ((parseFloat(document.getElementById('form-product').quantity_temp.value) - parseFloat(document.getElementById('form-product').restock_quantity_temp.value)) - parseFloat(document.getElementById('form-product').remove_temp.value))
		document.getElementById('form-product').restock_quantity.value = restockquantity_opt
	}
	if (document.getElementById('form-product').price.value > 0 && (document.getElementById('form-product').cost.value > 0 || document.getElementById('form-product').cost_amount.value > 0)) {
		var profit = (parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)).toFixed(4)
		var profit_margin = (((parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)) / parseFloat(document.getElementById('form-product').price.value))*100).toFixed(2)+'%'
		var profit_markup = (((parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)) / parseFloat(document.getElementById('form-product').cost.value))*100).toFixed(2)+'%'
	} else {
		var profit = '0'
		var profit_margin = '0%'
		var profit_markup = '0%'
	}
	document.getElementById('form-product').profit.value = profit
	document.getElementById('form-product').profit_margin.value = profit_margin
	document.getElementById('form-product').profit_markup.value = profit_markup	
}

function quantity_ini() {
	var quantity_opt = parseFloat(document.getElementById('form-product').quantity_temp.value)
	document.getElementById('form-product').quantity.value = quantity_opt
	
	if (document.getElementById('form-product').price.value > 0 && (document.getElementById('form-product').cost.value > 0 || document.getElementById('form-product').cost_amount.value > 0)) {
		var profit = (parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)).toFixed(4)
		var profit_margin = (((parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)) / parseFloat(document.getElementById('form-product').price.value))*100).toFixed(2)+'%'
		var profit_markup = (((parseFloat(document.getElementById('form-product').price.value) - parseFloat(document.getElementById('form-product').cost.value)) / parseFloat(document.getElementById('form-product').cost.value))*100).toFixed(2)+'%'
	} else {
		var profit = '0'
		var profit_margin = '0%'
		var profit_markup = '0%'
	}
	document.getElementById('form-product').profit.value = profit
	document.getElementById('form-product').profit_margin.value = profit_margin
	document.getElementById('form-product').profit_markup.value = profit_markup	
}
</script>            				
			  <label class="col-sm-2 control-label" for="input-adv-proft-module"></label>
			  <div class="col-sm-10">
			  <div class="panel panel-default">
				<div class="panel-heading">
				  <h3 class="panel-title"><i class="fa fa-calculator"></i> ADV Profit Module</h3>
				</div>
			  <div class="panel-body">
			  <!--Supplier-->
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-supplier"><?php echo $column_supplier; ?></label>
				<div class="col-sm-10">
				  <select name="supplier_id" id="input-supplier" class="form-control">
					<option value="0"><?php echo $text_none; ?></option>
					<?php foreach ($suppliers as $supplier) { ?>
					  <?php if ($supplier['supplier_id'] == $supplier_id) { ?>
						<option value="<?php echo $supplier['supplier_id']; ?>" selected="selected"><?php echo $supplier['name']; ?></option>
					  <?php } else { ?>
						<option value="<?php echo $supplier['supplier_id']; ?>"><?php echo $supplier['name']; ?></option>
					  <?php } ?>
					<?php } ?>
				  </select>
				</div>
			  </div>
			  <!--Price-->
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="price"><?php echo $entry_price; ?></label>
				<div <?php echo ($adv_price_tax) ? 'class="col-sm-4"' : 'class="col-sm-10"' ?> >
				  <input type="text" onKeyUp="totalcost(this.form); if(!this.value) this.value=0; totalcost(this.form);" onClick="this.setSelectionRange(0, this.value.length)" name="price" value="<?php echo $price; ?>" placeholder="<?php echo $entry_price; ?>" id="price" class="form-control" style="border:thin solid #b5e08b;" />
				</div>
				<?php if ($adv_price_tax) { ?>
				<label class="col-sm-2 control-label" for="input-price"><?php echo $entry_price_tax; ?></label>
				<div class="col-sm-4">
				  <input type="text" onKeyUp="totalcost(this.form); if(!this.value) this.value=0; totalcost(this.form);" onClick="this.setSelectionRange(0, this.value.length)" name="price_tax" value="<?php echo $price_tax; ?>" placeholder="<?php echo $entry_price_tax; ?>" id="input-price" class="form-control" style="border:thin solid #b5e08b;" />
				</div>
				<?php } ?>
			  </div>
              <!--Costing Method-->
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-costing-method"><span data-toggle="tooltip" title="<?php echo $help_costing_method; ?>"><?php echo $entry_costing_method; ?></span><br /><?php echo $entry_costing_method_doc; ?>&nbsp;&nbsp;&nbsp;</label>
                <div class="col-sm-10">
                  <select name="costing_method" id="input-costing-method" class="form-control" style="border:thin solid #ed9999;">
                	<?php if (!$costing_method or $costing_method == '0') { ?>
            			<option value="0" id="cost_fixed" selected="selected"><?php echo $text_cost_fixed; ?></option>
            		<?php } else { ?>
            			<option value="0" id="cost_fixed"><?php echo $text_cost_fixed; ?></option>
                	<?php } ?> 
                	<?php if ($costing_method == '1') { ?>
            			<option value="1" id="cost_average" selected="selected"><?php echo $text_cost_average; ?></option>
            		<?php } else { ?>
            			<option value="1" id="cost_average"><?php echo $text_cost_average; ?></option>
                	<?php } ?> 
                	<?php if ($costing_method == '2') { ?>
            			<option value="2" id="cost_fifo" selected="selected" disabled="disabled" style="color:#999"><?php echo $text_cost_fifo; ?></option>
            		<?php } else { ?>
            			<option value="2" id="cost_fifo" disabled="disabled" style="color:#999"><?php echo $text_cost_fifo; ?></option>
                	<?php } ?> 
                  </select>
                </div>
              </div>						  
              <!--Cost-->
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-cost"><span data-toggle="tooltip" title="<?php echo $help_product_cost; ?>"><?php echo $entry_cost; ?></span></label>
                <div class="col-sm-10"> 
                  <div class="row">
					<div class="col-sm-6">
					  <table width="100%" cellspacing="0" cellpadding="0"> 
						<tr>
						  <td width="34%" style="padding-left:5px; padding-right:3px; padding-bottom:5px; padding-top:5px;"><input onKeyUp="totalcost(this.form); if(!this.value) this.value=0; totalcost(this.form);" onClick="this.setSelectionRange(0, this.value.length)" type="text" id="cost_amount" name="cost_amount" value="<?php echo $cost_amount; ?>" class="form-control" style="border:thin solid #ed9999; width:100%;" /></td>
						  <td align="left" style="padding-left:3px; padding-bottom:5px; padding-top:5px;"><?php echo $text_cost_or; ?></td>
 						  <td width="31%" style="padding-left:5px; padding-bottom:5px; padding-top:5px;"><input onKeyUp="totalcost(this.form); if(!this.value) this.value=0; totalcost(this.form);" onClick="this.setSelectionRange(0, this.value.length)" type="text" id="cost_percentage" name="cost_percentage" maxlength="5" value="<?php echo $cost_percentage; ?>" class="form-control" style="border:thin solid #ed9999; width:100%;" /></td>
						  <td align="left" style="padding-right:3px; padding-bottom:5px; padding-top:5px;"> %</td>
						  <td align="center" style="padding-left:3px; padding-right:5px; padding-bottom:5px; padding-top:5px;">+</td>
						  <td width="34%" style="padding-right:5px; padding-bottom:5px; padding-top:5px;"><input onKeyUp="totalcost(this.form); if(!this.value) this.value=0; totalcost(this.form);" onClick="this.setSelectionRange(0, this.value.length)" type="text" id="cost_additional" name="cost_additional" value="<?php echo $cost_additional; ?>" class="form-control" style="border:thin solid #ed9999; width:100%;" /></td>
						</tr>
						<tr>
						  <td width="34%" align="center" valign="top"><?php echo $text_cost_amount; ?></td>
						  <td>&nbsp;</td>
						  <td width="31%" align="center" valign="top"><?php echo $text_cost_percentage; ?></td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
						  <td width="34%" align="center" valign="top"><?php echo $text_cost_additional; ?></td>
						</tr>	
					  </table>
                    </div>
                    <div class="col-sm-1">
					  <table width="100%" cellspacing="0" cellpadding="3"> 
						<tr>
						  <td align="center" style="padding-bottom:5px; padding-top:13px; font-size:20px;">=</td>
						</tr>
						<tr>
						  <td>&nbsp;</td>
						<tr>  
					  </table>
                    </div>	
                    <div class="col-sm-5">
					  <table width="100%" cellspacing="0" cellpadding="3"> 
						<tr>
						  <td class="show_restock_cost" <?php echo ($costing_method == 1) ? 'style="padding-bottom:5px; padding-top:5px;"' : 'style="padding-bottom:5px; padding-top:5px; display:none;"' ?> ><input type="text" id="restock_cost" name="restock_cost" value="<?php echo $restock_cost; ?>" class="form-control" style="background-color:#EEE; border:thin solid #c0c0c0; width:100%; cursor:not-allowed;" readonly /></td>
						  <td class="show_restock_cost" align="center" nowrap="nowrap" <?php echo ($costing_method == 1) ? '' : 'style="display:none;"' ?>><span class="show_restock_cost" <?php echo ($costing_method == 1) ? 'style="padding-right:5px; padding-left:5px; padding-bottom:5px; padding-top:5px; font-size:16px;"' : 'style="padding-right:5px; padding-left:5px; padding-bottom:5px; padding-top:5px; font-size:16px; display:none;"' ?>><></span></td>
						  <td nowrap="nowrap" style="padding-right:5px; padding-bottom:5px; padding-top:5px;"><input type="hidden" id="oldcost_temp" name="oldcost_temp" value="<?php echo $cost; ?>" /><input type="text" id="cost" name="cost" value="<?php echo $cost; ?>" class="form-control" style="background-color:#f7e9e3; border:thin solid #ed9999; width:100%; cursor:not-allowed;" readonly /></td>
						</tr>
						<tr>
						  <td class="show_restock_cost" align="center" valign="top" nowrap="nowrap" <?php echo ($costing_method == 1) ? 'style="padding-bottom:3px;"' : 'style="padding-bottom:3px; display:none;"' ?> ><span style="color:#a0a0a0;"><?php echo $text_restock_cost; ?></span></td>
						  <td class="show_restock_cost" <?php echo ($costing_method == 1) ? '' : 'style="display:none;"' ?> >&nbsp;</td>
						  <td align="center" valign="top" nowrap="nowrap" style="padding-bottom:3px;"><?php if ($costing_method == '1') { ?><span id="cont" style="color:#ed9999;"><?php echo $text_cost_average_set; ?></span><?php } else { ?><span id="cont" style="color:#ed9999;"><?php echo $text_cost; ?></span><?php } ?></td>
						<tr>  
					  </table>
                    </div>
                  </div>
                </div>
              </div> 
              <!--Qantity-->
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-quantity"><?php echo $entry_quantity; ?></label>
                <div class="col-sm-10"><input type="hidden" id="restock_quantity_temp" name="restock_quantity_temp" value="<?php echo $restock_quantity_temp; ?>" /><input type="hidden" id="quantity_temp" name="quantity_temp" value="<?php echo $quantity_temp; ?>" /><input type="hidden" id="remove_temp" name="remove_temp" value="<?php echo $remove_temp; ?>" />
                  <div class="row">
                    <div class="col-lg-4">
						<input type="text" id="restock_quantity" onKeyUp="stockquantity(this.form); if(!this.value) this.value=0; stockquantity(this.form); totalcost(this.form);" onClick="this.setSelectionRange(0, this.value.length)" name="restock_quantity" value="<?php echo $restock_quantity; ?>" class="form-control" style="border:thin solid #ebd685;" /><div align="center" style="padding:3px;"><?php echo $column_hrestock_quantity; ?></div>
                    </div>
                    <div class="col-lg-4">
						<input type="hidden" id="stockquantity_temp" name="stockquantity_temp" value="<?php echo $quantity; ?>" />
						<input type="text" id="quantity" name="quantity" value="<?php echo $quantity; ?>" class="form-control" style="background-color:#f9f3db; border:thin solid #ebd685; cursor:not-allowed;" readonly /><div align="center" style="padding:3px;"><span style="color:#decc88;"><?php echo $text_totalstock; ?></span></div>
                    </div>
                    <div class="col-lg-4">
    					<span class="button-checkbox">
        				<button type="button" onclick="restockquantity_ini(this.form); quantity_ini(this.form);" data-color="primary" class="form-control" style="height:auto;"><?php echo $text_qty_by_option; ?></button>
        				<input type="checkbox" id="qty_by_option_checkbox" class="hidden" /><div align="center" style="padding:3px;"><span id="qupdatemsg" style="color:#ed9999;"></span></div>
    					</span>
                    </div>					
                  </div>
                </div>
              </div>  
              <!--Profit-->
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-profit"><span data-toggle="tooltip" title="<?php echo $help_product_profit; ?>"><?php echo $entry_profit; ?></span></label>
                <div class="col-sm-10">
                  <div class="row">
                    <div class="col-sm-4">				
                  	  <input type="text" id="profit" value="0" class="form-control" style="background-color:#dfe7ee; border:thin solid #739cc3; cursor:not-allowed;" readonly /><div align="center" style="padding:3px;"><span style="color:#739cc3;"><?php echo $column_profit; ?></span></div>
                    </div>
                    <div class="col-sm-4">
                      <input type="text" id="profit_margin" value="0" class="form-control" style="background-color:#dfe7ee; border:thin solid #739cc3; cursor:not-allowed;" readonly /><div align="center" style="padding:3px;"><span style="color:#739cc3;"><?php echo $entry_gmargin; ?></span></div>
                    </div>
                    <div class="col-sm-4">
                      <input type="text" id="profit_markup" value="0" class="form-control" style="background-color:#dfe7ee; border:thin solid #739cc3; cursor:not-allowed;" readonly /><div align="center" style="padding:3px;"><span style="color:#739cc3;"><?php echo $entry_gmarkup; ?></span></div>
                    </div>	
                  </div>
                </div>
              </div>	
			  </div>
			  </div>
			  </div>
			</div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-tax-class"><?php echo $entry_tax_class; ?></label>
                <div class="col-sm-10">
                  <select name="tax_class_id" id="input-tax-class" class="form-control">
                    <option value="0"><?php echo $text_none; ?></option>
                    <?php foreach ($tax_classes as $tax_class) { ?>
                    <?php if ($tax_class['tax_class_id'] == $tax_class_id) { ?>
                    <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div> 
<?php } else { ?>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-price"><?php echo $entry_price; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="price" value="<?php echo $price; ?>" placeholder="<?php echo $entry_price; ?>" id="input-price" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-tax-class"><?php echo $entry_tax_class; ?></label>
                <div class="col-sm-10">
                  <select name="tax_class_id" id="input-tax-class" class="form-control">
                    <option value="0"><?php echo $text_none; ?></option>
                    <?php foreach ($tax_classes as $tax_class) { ?>
                    <?php if ($tax_class['tax_class_id'] == $tax_class_id) { ?>
                    <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-quantity"><?php echo $entry_quantity; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="quantity" value="<?php echo $quantity; ?>" placeholder="<?php echo $entry_quantity; ?>" id="input-quantity" class="form-control" />
		  		  <input type="hidden" name="costing_method" value="<?php echo $costing_method; ?>" />
		  		  <input type="hidden" name="cost" value="<?php echo $cost; ?>" />
		  		  <input type="hidden" name="restock_cost" value="<?php echo $restock_cost; ?>" />
		  		  <input type="hidden" name="cost_amount" value="<?php echo $cost_amount; ?>" />
		  		  <input type="hidden" name="cost_percentage" value="<?php echo $cost_percentage; ?>" />
		  		  <input type="hidden" name="cost_additional" value="<?php echo $cost_additional; ?>" />
		  		  <input type="hidden" name="restock_quantity" value="<?php echo $restock_quantity; ?>" />
				  <input type="hidden" name="supplier_id" value="0" />
				  <?php foreach ($suppliers as $supplier) { ?>
				  <?php if ($supplier['supplier_id'] == $supplier_id) { ?>
				  <input type="hidden" name="supplier_id" value="<?php echo $supplier['supplier_id']; ?>" />
				  <?php } ?>
				  <?php } ?>				  
          		</div>
		  	  </div>		  
<?php } ?>	
			

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-fornt">Front inStock</label>
                <div class="col-sm-10">
                  <input type="text" name="front_quantity" value="<?php echo $front_quantity; ?>" placeholder="Front inStock" id="input-front" class="form-control" />
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-storage">Storage inStock</label>
                <div class="col-sm-10">
                  <input type="text" name="storage_quantity" value="<?php echo $storage_quantity; ?>" placeholder="Storage inStock" id="input-storage" class="form-control" />
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-quantity">Total Instock</label>
                <div class="col-sm-10">
                  <input type="text" name="total_instock_quantity" value="<?php echo $quantity; ?>" placeholder="Total InStock" id="input-quantity" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-minimum"><span data-toggle="tooltip" title="<?php echo $help_minimum; ?>"><?php echo $entry_minimum; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="minimum" value="<?php echo $minimum; ?>" placeholder="<?php echo $entry_minimum; ?>" id="input-minimum" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-minimum-amount"><span data-toggle="tooltip" title="<?php echo $help_minimum; ?>"><?php echo $entry_minimum_amount; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="minimum_amount" value="<?php echo $minimum_amount; ?>" placeholder="<?php echo $entry_minimum_amount; ?>" id="input-minimum-amount" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-subtract"><?php echo $entry_subtract; ?></label>
                <div class="col-sm-10">
                  <select name="subtract" id="input-subtract" class="form-control">
                    <?php if ($subtract) { ?>
                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                    <option value="0"><?php echo $text_no; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_yes; ?></option>
                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-stock-status"><span data-toggle="tooltip" title="<?php echo $help_stock_status; ?>"><?php echo $entry_stock_status; ?></span></label>
                <div class="col-sm-10">
                  <select name="stock_status_id" id="input-stock-status" class="form-control">
                    <?php foreach ($stock_statuses as $stock_status) { ?>
                    <?php if ($stock_status['stock_status_id'] == $stock_status_id) { ?>
                    <option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_shipping; ?></label>
                <div class="col-sm-10">
                  <label class="radio-inline">
                    <?php if ($shipping) { ?>
                    <input type="radio" name="shipping" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="shipping" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$shipping) { ?>
                    <input type="radio" name="shipping" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="shipping" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-keyword"><span data-toggle="tooltip" title="<?php echo $help_keyword; ?>"><?php echo $entry_keyword; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="keyword" value="<?php echo $keyword; ?>" placeholder="<?php echo $entry_keyword; ?>" id="input-keyword" class="form-control" />
                  <?php if ($error_keyword) { ?>
                  <div class="text-danger"><?php echo $error_keyword; ?></div>
                  <?php } ?>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-date-available"><?php echo $entry_date_available; ?></label>
                <div class="col-sm-3">
                  <div class="input-group date">
                    <input type="text" name="date_available" value="<?php echo $date_available; ?>" placeholder="<?php echo $entry_date_available; ?>" data-date-format="YYYY-MM-DD" id="input-date-available" class="form-control" />
                    <span class="input-group-btn">
                    <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                    </span></div>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-frontend-date-available"><?php echo $entry_frontend_date_available; ?></label>
                <div class="col-sm-3">
                  <div class="input-group date">
                    <input type="text" name="frontend_date_available" value="<?php echo $frontend_date_available; ?>" placeholder="<?php echo $entry_frontend_date_available; ?>" data-date-format="YYYY-MM-DD" id="input-frontend-date-available" class="form-control" />
                    <span class="input-group-btn">
                    <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                    </span></div>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-date-sold-out"><?php echo $entry_date_sold_out; ?></label>
                <div class="col-sm-3">
                  <div class="input-group date">
                    <input type="text" name="date_sold_out" value="<?php echo $date_sold_out; ?>" placeholder="<?php echo $entry_date_sold_out; ?>" data-date-format="YYYY-MM-DD" id="input-date-sold-out" class="form-control" />
                    <span class="input-group-btn">
                    <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                    </span></div>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-date-ordered"><?php echo $entry_date_ordered; ?></label>
                <div class="col-sm-3">
                  <div class="input-group date">
                    <input type="text" name="date_ordered" value="<?php echo $date_ordered; ?>" placeholder="<?php echo $entry_date_ordered; ?>" data-date-format="YYYY-MM-DD" id="input-date-ordered" class="form-control" />
                    <span class="input-group-btn">
                    <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                    </span></div>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-esmtimate-delivery-time"><?php echo $entry_estimate_deliver_time; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="estimate_deliver_time" value="<?php echo $estimate_deliver_time; ?>" placeholder="<?php echo $entry_estimate_deliver_time; ?>" id="input-estimate-deliver-time" class="form-control" />
                </div>
              </div>
              
              
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-length"><?php echo $entry_dimension; ?></label>
                <div class="col-sm-10">
                  <div class="row">
                    <div class="col-sm-4">
                      <input type="text" name="length" value="<?php echo $length; ?>" placeholder="<?php echo $entry_length; ?>" id="input-length" class="form-control" />
                    </div>
                    <div class="col-sm-4">
                      <input type="text" name="width" value="<?php echo $width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-width" class="form-control" />
                    </div>
                    <div class="col-sm-4">
                      <input type="text" name="height" value="<?php echo $height; ?>" placeholder="<?php echo $entry_height; ?>" id="input-height" class="form-control" />
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-length-class"><?php echo $entry_length_class; ?></label>
                <div class="col-sm-10">
                  <select name="length_class_id" id="input-length-class" class="form-control">
                    <?php foreach ($length_classes as $length_class) { ?>
                    <?php if ($length_class['length_class_id'] == $length_class_id) { ?>
                    <option value="<?php echo $length_class['length_class_id']; ?>" selected="selected"><?php echo $length_class['title']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-weight"><?php echo $entry_weight; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="weight" value="<?php echo $weight; ?>" placeholder="<?php echo $entry_weight; ?>" id="input-weight" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-weight-class"><?php echo $entry_weight_class; ?></label>
                <div class="col-sm-10">
                  <select name="weight_class_id" id="input-weight-class" class="form-control">
                    <?php foreach ($weight_classes as $weight_class) { ?>
                    <?php if ($weight_class['weight_class_id'] == $weight_class_id) { ?>
                    <option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
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
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-pos-status">POS Status</label>
                <div class="col-sm-10">
                  <select name="pos_status" id="input-pos-status" class="form-control">
                    <?php if ($pos_status) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
<?php /* //karapuz (ka_csv_product_import.ocmod.xml)  */?>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-skip_import"><span data-toggle="tooltip" title="<?php echo $this->t('help_skip_import'); ?>"><?php echo $this->t('Skip Import'); ?></span></label>
                <div class="col-sm-10">
                  <select name="skip_import" id="input-skip_import" class="form-control">
                    <option value="0" <?php if ($skip_import == 0) { ?> selected="selected"<?php } ?>><?php echo $text_no; ?></option>
                    <option value="1" <?php if ($skip_import == 1) { ?> selected="selected"<?php } ?>><?php echo $text_yes; ?></option>
                  </select>
                </div>
              </div>
<?php /* ///karapuz (ka_csv_product_import.ocmod.xml)  */?>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
                </div>
              </div>

              
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-unit-singular">Unit Singular</label>
                <div class="col-sm-10">
                  <input type="text" name="unit_singular" value="<?php echo $unit_singular; ?>" placeholder="Unit Singular" id="input-unit-singular" class="form-control" />
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-unit-plural">Unit Plural</label>
                <div class="col-sm-10">
                  <input type="text" name="unit_plural" value="<?php echo $unit_plural; ?>" placeholder="Unit Plural" id="input-unit-plural" class="form-control" />
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-unique-price-discount">Unique Price Discount</label>
                <div class="col-sm-10">
                  <input type="text" name="unique_price_discount" value="<?php echo $unique_price_discount; ?>" placeholder="Unique Price Discount" id="input-unique-price-discount" class="form-control" />
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-unique-option-price">Unique Option Price</label>
                <div class="col-sm-10">
                  <input type="text" name="unique_option_price" value="<?php echo $unique_option_price; ?>" placeholder="Unique Option Price" id="input-unique-option-price" class="form-control" />
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-labour-cost">Labour Cost</label>
                <div class="col-sm-10">
                  <input type="text" name="labour_cost" value="<?php echo $labour_cost; ?>" placeholder="Labour Cost" id="input-labour-cost" class="form-control" />
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-default-vendor-unit">Default Vendor Unit</label>
                <div class="col-sm-10">
                  <input type="text" name="default_vendor_unit" value="<?php echo $default_vendor_unit; ?>" placeholder="Default Vendor Unit" id="input-default-vendor-unit" class="form-control" />
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-show-product-label-1"><?php echo $entry_show_product_label_1; ?></label>
                <div class="col-sm-10">
                  <select name="show_product_label_1" id="input-show-product-label-1" class="form-control">
                    <?php if ($show_product_label_1) { ?>
                    <option value="1" selected="selected">Yes</option>
                    <option value="0">No</option>
                    <?php } else { ?>
                    <option value="1">Yes</option>
                    <option value="0" selected="selected">No</option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-product-label-text-1"><?php echo $entry_product_label_text_1; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="product_label_text_1" value="<?php echo $product_label_text_1; ?>" placeholder="<?php echo $entry_product_label_text_1; ?>" id="input-product-label-text-1" class="form-control" />
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-show-product-label-2"><?php echo $entry_show_product_label_2; ?></label>
                <div class="col-sm-10">
                  <select name="show_product_label_2" id="input-show-product-label-2" class="form-control">
                    <?php if ($show_product_label_2) { ?>
                    <option value="1" selected="selected">Yes</option>
                    <option value="0">No</option>
                    <?php } else { ?>
                    <option value="1">Yes</option>
                    <option value="0" selected="selected">No</option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-product-label-text-2"><?php echo $entry_product_label_text_2; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="product_label_text_2" value="<?php echo $product_label_text_2; ?>" placeholder="<?php echo $entry_product_label_text_2; ?>" id="input-product-label-text-2" class="form-control" />
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-multiplier-name"><?php echo $entry_multiplier_name; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="multiplier_name" value="<?php echo $multiplier_name; ?>" placeholder="<?php echo $entry_multiplier_name; ?>" id="input-multiplier-name" class="form-control" />
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-multiplier-value"><?php echo $entry_multiplier_value; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="multiplier_value" value="<?php echo $multiplier_value; ?>" placeholder="<?php echo $entry_multiplier_value; ?>" id="input-multiplier-value" class="form-control" />
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-is-gift-product"><?php echo $entry_is_gift_product; ?></label>
                <div class="col-sm-10">
                  <select name="is_gift_product" id="input-is-gift-product" class="form-control">
                    <?php if ($is_gift_product) { ?>
                    <option value="1" selected="selected">Yes</option>
                    <option value="0">No</option>
                    <?php } else { ?>
                    <option value="1">Yes</option>
                    <option value="0" selected="selected">No</option>
                    <?php } ?>
                  </select>
                </div>
              </div>

             </div>
            <div class="tab-pane" id="tab-links">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-manufacturer"><span data-toggle="tooltip" title="<?php echo $help_manufacturer; ?>"><?php echo $entry_manufacturer; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="manufacturer" value="<?php echo $manufacturer ?>" placeholder="<?php echo $entry_manufacturer; ?>" id="input-manufacturer" class="form-control" />
                  <input type="hidden" name="manufacturer_id" value="<?php echo $manufacturer_id; ?>" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-category"><span data-toggle="tooltip" title="<?php echo $help_category; ?>"><?php echo $entry_category; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="category" value="" placeholder="<?php echo $entry_category; ?>" id="input-category" class="form-control" />
                  <div id="product-category" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($product_categories as $product_category) { ?>
                    <div id="product-category<?php echo $product_category['category_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product_category['name']; ?>
                      <input type="hidden" name="product_category[]" value="<?php echo $product_category['category_id']; ?>" />
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-filter"><span data-toggle="tooltip" title="<?php echo $help_filter; ?>"><?php echo $entry_filter; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="filter" value="" placeholder="<?php echo $entry_filter; ?>" id="input-filter" class="form-control" />
                  <div id="product-filter" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($product_filters as $product_filter) { ?>
                    <div id="product-filter<?php echo $product_filter['filter_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product_filter['name']; ?>
                      <input type="hidden" name="product_filter[]" value="<?php echo $product_filter['filter_id']; ?>" />
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_store; ?></label>
                <div class="col-sm-10">
                  <div class="well well-sm" style="height: 150px; overflow: auto;">
                    <div class="checkbox">
                      <label>
                        <?php if (in_array(0, $product_store)) { ?>
                        <input type="checkbox" name="product_store[]" value="0" checked="checked" />
                        <?php echo $text_default; ?>
                        <?php } else { ?>
                        <input type="checkbox" name="product_store[]" value="0" />
                        <?php echo $text_default; ?>
                        <?php } ?>
                      </label>
                    </div>
                    <?php foreach ($stores as $store) { ?>
                    <div class="checkbox">
                      <label>
                        <?php if (in_array($store['store_id'], $product_store)) { ?>
                        <input type="checkbox" name="product_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                        <?php echo $store['name']; ?>
                        <?php } else { ?>
                        <input type="checkbox" name="product_store[]" value="<?php echo $store['store_id']; ?>" />
                        <?php echo $store['name']; ?>
                        <?php } ?>
                      </label>
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-download"><span data-toggle="tooltip" title="<?php echo $help_download; ?>"><?php echo $entry_download; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="download" value="" placeholder="<?php echo $entry_download; ?>" id="input-download" class="form-control" />
                  <div id="product-download" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($product_downloads as $product_download) { ?>
                    <div id="product-download<?php echo $product_download['download_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product_download['name']; ?>
                      <input type="hidden" name="product_download[]" value="<?php echo $product_download['download_id']; ?>" />
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-related"><span data-toggle="tooltip" title="<?php echo $help_related; ?>"><?php echo $entry_related; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="related" value="" placeholder="<?php echo $entry_related; ?>" id="input-related" class="form-control" />
                  <div id="product-related" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($product_relateds as $product_related) { ?>
                    <div id="product-related<?php echo $product_related['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product_related['name']; ?>
                      <input type="hidden" name="product_related[]" value="<?php echo $product_related['product_id']; ?>" />
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-wwell"><span data-toggle="tooltip" title="<?php echo $help_related; ?>"><?php echo "Works Well"; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="wwell" value="" placeholder="<?php echo "Works Well With"; ?>" id="input-wwell" class="form-control" />
                  <div id="product-wwell" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($product_wwell as $product_related) { ?>
                    <div id="product-related<?php echo $product_related['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product_related['name']; ?>
                      <input type="hidden" name="product_wwell[]" value="<?php echo $product_related['product_id']; ?>" />
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>
            
            
            <div class="tab-pane" id="tab-attribute">
              <div class="table-responsive">
                <table id="attribute" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_attribute; ?></td>
                      <td class="text-left"><?php echo $entry_text; ?></td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $attribute_row = 0; ?>
                    <?php foreach ($product_attributes as $product_attribute) { ?>
                    <tr id="attribute-row<?php echo $attribute_row; ?>">
                      <td class="text-left" style="width: 40%;"><input type="text" name="product_attribute[<?php echo $attribute_row; ?>][name]" value="<?php echo $product_attribute['name']; ?>" placeholder="<?php echo $entry_attribute; ?>" class="form-control" />
                        <input type="hidden" name="product_attribute[<?php echo $attribute_row; ?>][attribute_id]" value="<?php echo $product_attribute['attribute_id']; ?>" /></td>
                      <td class="text-left"><?php foreach ($languages as $language) { ?>
                        <div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                          <textarea name="product_attribute[<?php echo $attribute_row; ?>][product_attribute_description][<?php echo $language['language_id']; ?>][text]" rows="5" placeholder="<?php echo $entry_text; ?>" class="form-control"><?php echo isset($product_attribute['product_attribute_description'][$language['language_id']]) ? $product_attribute['product_attribute_description'][$language['language_id']]['text'] : ''; ?></textarea>
                        </div>
                        <?php } ?></td>
                      <td class="text-left"><button type="button" onclick="$('#attribute-row<?php echo $attribute_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                    </tr>
                    <?php $attribute_row++; ?>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="2"></td>
                      <td class="text-left"><button type="button" onclick="addAttribute();" data-toggle="tooltip" title="<?php echo $button_attribute_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
            
            
            
            <!-- Delivery Estimate Tab -->
 			<div class="tab-pane" id="tab-estimate-time">
              <div class="table-responsive">
                <table id="estimatedays" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo 'Estimate Days'; ?></td>
                      <td class="text-left"><?php echo $entry_text; ?></td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $estimatedays_row = 0;?>
                    <?php foreach ($estimatedays as $get_estimatedays) { ?>
                    <tr id="estimatedays-row<?php echo $estimatedays_row; ?>">
                      <td class="text-left" style="width: 40%;">
                       <input type="text" name="estimatedays[<?php echo $estimatedays_row; ?>][days]" value="<?php echo $get_estimatedays['estimate_days']; ?>" placeholder="<?php echo 'Estimate Days'; ?>" class="form-control yes" />
                      </td>
                      <td class="text-left">
                        <div class="input-group"><span class="input-group-addon"</span>
                          <textarea name="estimatedays[<?php echo $estimatedays_row; ?>][estimatedays_description]" rows="5" placeholder="<?php echo $entry_text; ?>" class="form-control"><?php echo isset($get_estimatedays['text']) ? $get_estimatedays['text'] : ''; ?></textarea>
                        </div>
                      </td>
                      <td class="text-left"><button type="button" onclick="$('#estimatedays-row<?php echo $estimatedays_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                    </tr>
                    <?php $estimatedays_row++; ?>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="2"></td>
                      <td class="text-left"><button type="button" onclick="addEstimatedays();" data-toggle="tooltip" title="<?php echo $button_attribute_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>  
            <!-- Delivery Estimate Tab -->          
            
            
            
            
            <div class="tab-pane" id="tab-option">
              <div class="row">
                <div class="col-sm-2">
                  <ul class="nav nav-pills nav-stacked" id="option">
                    <?php $option_row = 0; ?>
                    <?php foreach ($product_options as $product_option) { ?>
                    
<?php if ($prm_access_permission && $laccess) { ?>
			<li><a href="#tab-option<?php echo $option_row; ?>" data-toggle="tab"><i id="remove-tab-option<?php echo $option_row; ?>" class="fa fa-minus-circle" onclick="$('a[href=\'#tab-option<?php echo $option_row; ?>\']').parent().remove(); $('#tab-option<?php echo $option_row; ?>').remove(); optRemoveGroup(this.form); restockquantity_opt(this.form); quantity_opt(this.form); totalcost_opt(this.form); $('#option a:first').tab('show');"></i> <?php echo $product_option['name']; ?></a></li>
<?php } else { ?>
            <li><a href="#tab-option<?php echo $option_row; ?>" data-toggle="tab"><i class="fa fa-minus-circle" onclick="$('a[href=\'#tab-option<?php echo $option_row; ?>\']').parent().remove(); $('#tab-option<?php echo $option_row; ?>').remove(); $('#option a:first').tab('show');"></i> <?php echo $product_option['name']; ?></a></li>
<?php } ?>
            
                    <?php $option_row++; ?>
                    <?php } ?>
                    <li>
                      <input type="text" name="option" value="" placeholder="<?php echo $entry_option; ?>" id="input-option" class="form-control" />
                    </li>
                  </ul>
                </div>
                <div class="col-sm-10">
                  <div class="tab-content">
                    <?php $option_row = 0; ?>
                    <?php $option_value_row = 0; ?>
                    <?php foreach ($product_options as $product_option) { ?>
                    <div class="tab-pane" id="tab-option<?php echo $option_row; ?>">
                      <input type="hidden" name="product_option[<?php echo $option_row; ?>][product_option_id]" value="<?php echo $product_option['product_option_id']; ?>" />
                      <input type="hidden" name="product_option[<?php echo $option_row; ?>][name]" value="<?php echo $product_option['name']; ?>" />
                      <input type="hidden" name="product_option[<?php echo $option_row; ?>][option_id]" value="<?php echo $product_option['option_id']; ?>" />
                      <input type="hidden" name="product_option[<?php echo $option_row; ?>][type]" value="<?php echo $product_option['type']; ?>" />
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-required<?php echo $option_row; ?>"><?php echo $entry_required; ?></label>
                        <div class="col-sm-10">
                          <select name="product_option[<?php echo $option_row; ?>][required]" id="input-required<?php echo $option_row; ?>" class="form-control">
                            <?php if ($product_option['required']) { ?>
                            <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                            <option value="0"><?php echo $text_no; ?></option>
                            <?php } else { ?>
                            <option value="1"><?php echo $text_yes; ?></option>
                            <option value="0" selected="selected"><?php echo $text_no; ?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                      <?php if ($product_option['type'] == 'text') { ?>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-value<?php echo $option_row; ?>"><?php echo $entry_option_value; ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="product_option[<?php echo $option_row; ?>][value]" value="<?php echo $product_option['value']; ?>" placeholder="<?php echo $entry_option_value; ?>" id="input-value<?php echo $option_row; ?>" class="form-control" />
                        </div>
                      </div>
                      <?php } ?>
                      <?php if ($product_option['type'] == 'textarea') { ?>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-value<?php echo $option_row; ?>"><?php echo $entry_option_value; ?></label>
                        <div class="col-sm-10">
                          <textarea name="product_option[<?php echo $option_row; ?>][value]" rows="5" placeholder="<?php echo $entry_option_value; ?>" id="input-value<?php echo $option_row; ?>" class="form-control"><?php echo $product_option['value']; ?></textarea>
                        </div>
                      </div>
                      <?php } ?>
                      <?php if ($product_option['type'] == 'file') { ?>
                      <div class="form-group" style="display: none;">
                        <label class="col-sm-2 control-label" for="input-value<?php echo $option_row; ?>"><?php echo $entry_option_value; ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="product_option[<?php echo $option_row; ?>][value]" value="<?php echo $product_option['value']; ?>" placeholder="<?php echo $entry_option_value; ?>" id="input-value<?php echo $option_row; ?>" class="form-control" />
                        </div>
                      </div>
                      <?php } ?>
                      <?php if ($product_option['type'] == 'date') { ?>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-value<?php echo $option_row; ?>"><?php echo $entry_option_value; ?></label>
                        <div class="col-sm-3">
                          <div class="input-group date">
                            <input type="text" name="product_option[<?php echo $option_row; ?>][value]" value="<?php echo $product_option['value']; ?>" placeholder="<?php echo $entry_option_value; ?>" data-date-format="YYYY-MM-DD" id="input-value<?php echo $option_row; ?>" class="form-control" />
                            <span class="input-group-btn">
                            <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                            </span></div>
                        </div>
                      </div>
                      <?php } ?>
                      <?php if ($product_option['type'] == 'time') { ?>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-value<?php echo $option_row; ?>"><?php echo $entry_option_value; ?></label>
                        <div class="col-sm-10">
                          <div class="input-group time">
                            <input type="text" name="product_option[<?php echo $option_row; ?>][value]" value="<?php echo $product_option['value']; ?>" placeholder="<?php echo $entry_option_value; ?>" data-date-format="HH:mm" id="input-value<?php echo $option_row; ?>" class="form-control" />
                            <span class="input-group-btn">
                            <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                            </span></div>
                        </div>
                      </div>
                      <?php } ?>
                      <?php if ($product_option['type'] == 'datetime') { ?>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-value<?php echo $option_row; ?>"><?php echo $entry_option_value; ?></label>
                        <div class="col-sm-10">
                          <div class="input-group datetime">
                            <input type="text" name="product_option[<?php echo $option_row; ?>][value]" value="<?php echo $product_option['value']; ?>" placeholder="<?php echo $entry_option_value; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-value<?php echo $option_row; ?>" class="form-control" />
                            <span class="input-group-btn">
                            <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                            </span></div>
                        </div>
                      </div>
                      <?php } ?>
                      <?php if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') { ?>
                      <div class="table-responsive">
                        <table id="option-value<?php echo $option_row; ?>" class="table table-striped table-bordered table-hover">
                          <thead>
                            <tr>
                              <td class="text-left"><?php echo $entry_option_value; ?></td>

<?php if ($prm_access_permission && $laccess) { ?>		
				<td class="text-right"><?php echo $entry_option_sku; ?></td>
<?php } ?>	
			
                              <td class="text-right"><?php echo $entry_quantity; ?></td>
                              <td class="text-left"><?php echo $entry_subtract; ?></td>
                              <td class="text-right"><?php echo $entry_price; ?></td>

<?php if ($prm_access_permission && $laccess) { ?>		
				<td class="text-right"><?php echo $entry_option_cost; ?></td>
<?php } ?>	
			
                              <td class="text-right"><?php echo $entry_option_points; ?></td>
                              <td class="text-right"><?php echo $entry_weight; ?></td>
                              <td></td>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach ($product_option['product_option_value'] as $product_option_value) { ?>
                            <tr id="option-value-row<?php echo $option_value_row; ?>">
                              <td class="text-left"><select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][option_value_id]" class="form-control">
                                  <?php if (isset($option_values[$product_option['option_id']])) { ?>
                                  <?php foreach ($option_values[$product_option['option_id']] as $option_value) { ?>
                                  <?php if ($option_value['option_value_id'] == $product_option_value['option_value_id']) { ?>
                                  <option value="<?php echo $option_value['option_value_id']; ?>" selected="selected"><?php echo $option_value['name']; ?></option>
                                  <?php } else { ?>
                                  <option value="<?php echo $option_value['option_value_id']; ?>"><?php echo $option_value['name']; ?></option>
                                  <?php } ?>
                                  <?php } ?>
                                  <?php } ?>
                                </select>
                                <input type="hidden" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][product_option_value_id]" value="<?php echo $product_option_value['product_option_value_id']; ?>" /></td>
                              
<?php if ($prm_access_permission && $laccess) { ?>
                  <td class="text-right"><input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][sku]" value="<?php echo $product_option_value['sku']; ?>" placeholder="<?php echo $entry_option_sku; ?>" class="form-control" /></td>
                  <td class="text-right" nowrap="nowrap">
<script type="text/javascript">
$(document).ready(function(){
	$('#input-costing-method_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>').on('change', function() {
    	if ($("#cost_average_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>").is(":selected")) {	
			$("#cont_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>").html("<span style='color:#ed9999;'><?php echo $text_option_cost_average_set; ?></span>");
			$("#recost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>").html("<span style='color:#666;'><?php echo $text_option_restock_cost; ?></span>");
			$("#equal_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>").html("<>");
			
		if (document.getElementById('form-product').option_restock_quantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>.value != '' && document.getElementById('form-product').option_restock_quantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>.value != 0) {	
			var option_cost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?> = parseFloat(document.getElementById('form-product').option_cost_amount_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>.value)
			var option_restockquantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?> = parseFloat(document.getElementById('form-product').option_restock_quantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>.value)
			var option_oldcost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?> = parseFloat(document.getElementById('form-product').option_oldcost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>.value)
			var option_stockquantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?> = parseFloat(document.getElementById('form-product').option_stock_quantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>.value)
			var option_quantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?> = parseFloat(document.getElementById('form-product').option_quantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>.value)
			var option_totalcost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?> = (((option_oldcost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>*option_stockquantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>)+(option_cost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>*option_restockquantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>))/option_quantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>).toFixed(4)
			document.getElementById('form-product').option_cost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>.value = option_totalcost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>
		}				
		if (document.getElementById('form-product').option_restock_quantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>.value == '' || document.getElementById('form-product').option_restock_quantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>.value == 0) {				
			var option_totalcost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?> = (parseFloat(document.getElementById('form-product').option_oldcost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>.value)).toFixed(4)
			document.getElementById('form-product').option_cost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>.value = option_totalcost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>
		}
					
    	} else if ($("#cost_fixed_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>").is(":selected")) {
		
        	$("#cont_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>").html("<span style='color:#ed9999;'><?php echo $text_option_cost; ?></span>");
			$("#recost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>").html("<span style='color:#666;'><?php echo $text_cost_option_cost; ?></span>");
			$("#equal_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>").html("=");
			
			var option_totalcost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?> = (parseFloat(document.getElementById('form-product').option_cost_amount_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>.value)).toFixed(4)
			document.getElementById('form-product').option_cost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>.value = option_totalcost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>			
	    }	
	});
	
  	$("#option_restock_quantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>").keypress(function (e) {
     	if (e.which != 8 && e.which != 0 && (e.which < 45 || e.which > 57)) {
			return false;
    	}
	});
  	$("#option_cost_amount_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>").keypress(function (e) {
     	if (e.which != 8 && e.which != 0 && (e.which < 46 || e.which > 57)) {
			return false;
    	}
	});	
	
	$('#option-value-row<?php echo $option_value_row; ?>').data('row', <?php echo $option_value_row; ?>);	
});

function option_totalcost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>() {
	if (document.getElementById('input-costing-method_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>').value == '0') {
		var option_totalcost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?> = (parseFloat(document.getElementById('form-product').option_cost_amount_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>.value)).toFixed(4)
		document.getElementById('form-product').option_cost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>.value = option_totalcost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>
	}	
	
	if (document.getElementById('input-costing-method_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>').value == '1') {
		if (document.getElementById('form-product').option_restock_quantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>.value != '' && document.getElementById('form-product').option_restock_quantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>.value != 0) {	
			var option_cost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?> = parseFloat(document.getElementById('form-product').option_cost_amount_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>.value)
			var option_restockquantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?> = parseFloat(document.getElementById('form-product').option_restock_quantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>.value)
			var option_oldcost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?> = parseFloat(document.getElementById('form-product').option_oldcost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>.value)
			var option_stockquantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?> = parseFloat(document.getElementById('form-product').option_stock_quantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>.value)
			var option_quantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?> = parseFloat(document.getElementById('form-product').option_quantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>.value)
			var option_totalcost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?> = (((option_oldcost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>*option_stockquantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>)+(option_cost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>*option_restockquantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>))/option_quantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>).toFixed(4)
			document.getElementById('form-product').option_cost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>.value = option_totalcost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>
		}				
		if (document.getElementById('form-product').option_restock_quantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>.value == '' || document.getElementById('form-product').option_restock_quantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>.value == 0) {				
			var option_totalcost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?> = (parseFloat(document.getElementById('form-product').option_oldcost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>.value)).toFixed(4)
			document.getElementById('form-product').option_cost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>.value = option_totalcost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>
		}						
	}			
}

function option_stockquantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>() {
	if (document.getElementById('form-product').option_restock_quantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>.value != '') {
		var option_stockquantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?> = parseFloat(document.getElementById('form-product').option_restock_quantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>.value) + parseFloat(document.getElementById('form-product').option_stock_quantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>.value)
		document.getElementById('form-product').option_quantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>.value = option_stockquantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>
	}
}
</script>
    				<div style="width:100%; display:table-row;">
					  <div style="display:table-cell;"><input onKeyUp="option_stockquantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>(this.form); if(!this.value) this.value=0; option_stockquantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>(this.form); option_totalcost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>(this.form); addRestock(this.form); restockquantity_opt(this.form); quantity_opt(this.form); totalcost_opt(this.form);" onClick="this.setSelectionRange(0, this.value.length)" type="text" id="option_restock_quantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][option_restock_quantity]" value="<?php echo $product_option_value['option_restock_quantity']; ?>" class="form-control addRestock" style="border:thin solid #ebd685; width:100%;" /></div>	
					</div> 
    				<div style="width:100%; display:table-row;">
					  <div style="display:table-cell; text-align:center; padding-top:3px;"><?php echo $text_restock_quantity; ?></div>
					</div>
    				<div style="width:100%; display:table-row;">
					  <div style="display:table-cell; padding-top:8px;"><input type="text" id="option_quantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][quantity]" value="<?php echo $product_option_value['quantity']; ?>" class="form-control addTotal" style="background-color:#f9f3db; border:thin solid #ebd685; cursor:not-allowed;" readonly /><input type="hidden" id="option_stock_quantity_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>" value="<?php echo $product_option_value['quantity']; ?>" /></div>
					</div> 					 					
    				<div style="width:100%; display:table-row;">
					  <div style="display:table-cell; text-align:center; padding-top:3px;"><span style="color:#decc88;"><?php echo $text_option_totalstock; ?></span></div>
					</div>  
				</td>  
<?php } else { ?>
                  <td class="text-right"><input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][quantity]" value="<?php echo $product_option_value['quantity']; ?>" placeholder="<?php echo $entry_quantity; ?>" class="form-control" /></td>
<?php } ?>	
			
                              <td class="text-left"><select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][subtract]" class="form-control">
                                  <?php if ($product_option_value['subtract']) { ?>
                                  <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                                  <option value="0"><?php echo $text_no; ?></option>
                                  <?php } else { ?>
                                  <option value="1"><?php echo $text_yes; ?></option>
                                  <option value="0" selected="selected"><?php echo $text_no; ?></option>
                                  <?php } ?>
                                </select></td>
                              
<?php if ($prm_access_permission && $laccess) { ?>			
				  <td class="text-right" nowrap="nowrap">
					<div style="display:table-row;"><div style="display:table-cell;">
					  <select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][price_prefix]" class="form-control" style="border:thin solid #b5e08b;">
						<?php if ($product_option_value['price_prefix'] == '+') { ?>
							<option value="+" selected="selected">+</option>
						<?php } else { ?>
							<option value="+">+</option>
						<?php } ?>
						<?php if ($product_option_value['price_prefix'] == '-') { ?>
							<option value="-" selected="selected">-</option>
						<?php } else { ?>
							<option value="-">-</option>
						<?php } ?>
					  </select>
					  <input type="text" onKeyUp="if(!this.value) this.value=0;" onClick="this.setSelectionRange(0, this.value.length)" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][price]" value="<?php echo $product_option_value['price']; ?>" placeholder="<?php echo $entry_price; ?>" class="form-control option_price" style="border:thin solid #b5e08b;" /></div> 
					</div> 	
					<div style="display:table-row;">
					  <div style="display:table-cell; text-align:center; padding-top:3px;"><?php echo $text_option_price; ?></div> 
					</div>
					<?php if ($adv_price_tax) { ?>
					<div style="display:table-row;">
					  <div style="display:table-cell; padding-top:8px;"><input type="text" onKeyUp="if(!this.value) this.value=0;" onClick="this.setSelectionRange(0, this.value.length)" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][price_tax]" value="<?php echo $product_option_value['price_tax']; ?>" placeholder="<?php echo $entry_price_tax; ?>" class="form-control option_price_tax" style="border:thin solid #b5e08b;" /></div>
					</div>
					<div style="display:table-row;">
					  <div style="display:table-cell; text-align:center; padding-top:3px;"><?php echo $entry_option_price_tax; ?></div> 
					</div>	
					<?php } ?>									
				  </td>  
<?php } else { ?>
				  <td class="text-right">
				  <select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][price_prefix]" class="form-control">
					<?php if ($product_option_value['price_prefix'] == '+') { ?>
						<option value="+" selected="selected">+</option>
					<?php } else { ?>
						<option value="+">+</option>
					<?php } ?>
					<?php if ($product_option_value['price_prefix'] == '-') { ?>
						<option value="-" selected="selected">-</option>

					<?php } else { ?>
						<option value="-">-</option>
					<?php } ?>
				  </select>
				  <input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][price]" value="<?php echo $product_option_value['price']; ?>" placeholder="<?php echo $entry_price; ?>" class="form-control" /></td>				
<?php } ?>	
			

<?php if ($prm_access_permission && $laccess) { ?>				
			<td class="text-right" nowrap="nowrap">
 			<div style="display:table; margin-bottom:3px; width:100%;">
   			  <div style="display:table-row;">  
				<div style="display:table-cell; text-align:center;">
                  <select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][costing_method]" id="input-costing-method_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>" class="form-control" style="border:thin solid #ed9999; width:100%;">
                	<?php if (!$product_option_value['costing_method'] or $product_option_value['costing_method'] == '0') { ?>
            			<option value="0" id="cost_fixed_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>" selected="selected"><?php echo $text_cost_fixed; ?></option>
            		<?php } else { ?>
            			<option value="0" id="cost_fixed_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>"><?php echo $text_cost_fixed; ?></option>
                	<?php } ?> 
                	<?php if ($product_option_value['costing_method'] == '1') { ?>
            			<option value="1" id="cost_average_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>" selected="selected"><?php echo $text_cost_average; ?></option>
            		<?php } else { ?>
            			<option value="1" id="cost_average_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>"><?php echo $text_cost_average; ?></option>
                	<?php } ?> 
                	<?php if ($product_option_value['costing_method'] == '2') { ?>
            			<option value="2" id="cost_fifo_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>" selected="selected" disabled="disabled" style="color:#999;"><?php echo $text_cost_fifo; ?></option>
            		<?php } else { ?>
            			<option value="2" id="cost_fifo_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>" disabled="disabled" style="color:#999;"><?php echo $text_cost_fifo; ?></option>
                	<?php } ?> 
                  </select>
				</div>
			  </div>
			</div>
  			<div style="display:table; width:100%;">  
   			  <div style="display:table-row;">
				<div style="display:table-cell; text-align:center;">
				    <select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][cost_prefix]" class="form-control" style="border:thin solid #ed9999;">
                      <?php if ($product_option_value['cost_prefix'] == '+') { ?>
                      <option value="+" selected="selected">+</option>
                      <?php } else { ?>
                      <option value="+">+</option>
                      <?php } ?>
                      <?php if ($product_option_value['cost_prefix'] == '-') { ?>
                      <option value="-" selected="selected">-</option>
                      <?php } else { ?>
                      <option value="-">-</option>
                      <?php } ?>
                    </select>
                    <input type="hidden" id="option_oldcost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>" value="<?php echo $product_option_value['cost']; ?>" />
				</div>
				<div style="display:table-cell;"></div>						
			  </div>
			  <div style="display:table-row;">	
				<div style="display:table-cell; text-align:center;">
					<input onKeyUp="option_totalcost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>(this.form); if(!this.value) this.value=0; option_totalcost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>(this.form);" onClick="this.setSelectionRange(0, this.value.length)" type="text" id="option_cost_amount_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][cost_amount]" value="<?php echo $product_option_value['cost_amount']; ?>" class="form-control" style="border:thin solid #ed9999;" />
				</div>			  				
				<div style="display:table-cell; padding-left:5px; text-align:center; padding-right:5px;">
					<?php if ($product_option_value['costing_method'] == '1') { ?><span id="equal_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>"><></span><?php } else { ?><span id="equal_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>">=</span><?php } ?>
				</div>
				<div style="display:table-cell; text-align:center;">
					<input type="text" id="option_cost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][cost]" value="<?php echo $product_option_value['cost']; ?>" class="form-control" style="background-color:#f7e9e3; border:thin solid #ed9999; cursor:not-allowed;" readonly />
				</div>	
			  </div>
			  <div style="display:table-row;">			
				<div style="display:table-cell; text-align:center; padding-top:3px;">
					<?php if ($product_option_value['costing_method'] == '1') { ?><span id="recost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>"><?php echo $text_option_restock_cost; ?></span><?php } else { ?><span id="recost_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>"><?php echo $text_cost_option_cost; ?></span><?php } ?>
				</div>
				<div style="display:table-cell;"></div>
				<div style="display:table-cell; text-align:center; padding-top:3px;">
					<?php if ($product_option_value['costing_method'] == '1') { ?><span id="cont_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>" style="color:#ed9999;"><?php echo $text_option_cost_average_set; ?></span><?php } else { ?><span id="cont_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>" style="color:#ed9999;"><?php echo $text_option_cost; ?></span><?php } ?>
				</div>
			  </div>
			</div>
			</td>	
<?php } else { ?>
				  <input type="hidden" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][sku]" value="<?php echo $product_option_value['sku']; ?>" />
<?php if ($product_option_value['cost_prefix'] == '+') { ?>
		  		  <input type="hidden" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][cost_prefix]" value="+" />
<?php } ?>
<?php if ($product_option_value['cost_prefix'] == '-') { ?>
		  		  <input type="hidden" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][cost_prefix]" value="-" />
<?php } ?>				  
		  		  <input type="hidden" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][costing_method]" value="<?php echo $product_option_value['costing_method']; ?>" />
		  		  <input type="hidden" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][cost_amount]" value="<?php echo $product_option_value['cost_amount']; ?>" />
		  		  <input type="hidden" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][cost]" value="<?php echo $product_option_value['cost']; ?>" />
				  <input type="hidden" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][option_restock_quantity]" value="<?php echo $product_option_value['option_restock_quantity']; ?>" />
<?php } ?>						
            
                              <td class="text-right"><select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][points_prefix]" class="form-control">
                                  <?php if ($product_option_value['points_prefix'] == '+') { ?>
                                  <option value="+" selected="selected">+</option>
                                  <?php } else { ?>
                                  <option value="+">+</option>
                                  <?php } ?>
                                  <?php if ($product_option_value['points_prefix'] == '-') { ?>
                                  <option value="-" selected="selected">-</option>
                                  <?php } else { ?>
                                  <option value="-">-</option>
                                  <?php } ?>
                                </select>
                                <input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][points]" value="<?php echo $product_option_value['points']; ?>" placeholder="<?php echo $entry_points; ?>" class="form-control" /></td>
                              <td class="text-right"><select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][weight_prefix]" class="form-control">
                                  <?php if ($product_option_value['weight_prefix'] == '+') { ?>
                                  <option value="+" selected="selected">+</option>
                                  <?php } else { ?>
                                  <option value="+">+</option>
                                  <?php } ?>
                                  <?php if ($product_option_value['weight_prefix'] == '-') { ?>
                                  <option value="-" selected="selected">-</option>
                                  <?php } else { ?>
                                  <option value="-">-</option>
                                  <?php } ?>
                                </select>
                                <input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][weight]" value="<?php echo $product_option_value['weight']; ?>" placeholder="<?php echo $entry_weight; ?>" class="form-control" /></td>
                              
<?php if ($prm_access_permission && $laccess) { ?>
				<td class="text-left"><button id="remove-option-value-row<?php echo $option_value_row; ?>" type="button" onclick="$(this).tooltip('destroy'); $('#option-value-row<?php echo $option_value_row; ?>').remove(); optRemove(this.form); restockquantity_opt(this.form); quantity_opt(this.form); totalcost_opt(this.form);" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
<?php } else { ?>
                <td class="text-left"><button type="button" onclick="$(this).tooltip('destroy');$('#option-value-row<?php echo $option_value_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
<?php } ?>				
                            </tr>
                            <?php $option_value_row++; ?>
                            <?php } ?>
                          </tbody>
                          <tfoot>
                            <tr>
<?php if ($prm_access_permission && $laccess) { ?>				
                  <td colspan="8"></td>
<?php } else { ?>
                  <td colspan="6"></td>	
<?php } ?>					  
            
                              <td class="text-left"><button type="button" onclick="addOptionValue('<?php echo $option_row; ?>');" data-toggle="tooltip" title="<?php echo $button_option_value_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                            </tr>
                          </tfoot>
                        </table>
                      </div>
                      <select id="option-values<?php echo $option_row; ?>" style="display: none;">
                        <?php if (isset($option_values[$product_option['option_id']])) { ?>
                        <?php foreach ($option_values[$product_option['option_id']] as $option_value) { ?>
                        <option value="<?php echo $option_value['option_value_id']; ?>"><?php echo $option_value['name']; ?></option>
                        <?php } ?>
                        <?php } ?>
                      </select>
                      <?php } ?>
                    </div>
                    <?php $option_row++; ?>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-recurring">
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_recurring; ?></td>
                      <td class="text-left"><?php echo $entry_customer_group; ?></td>
                      <td class="text-left"></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $recurring_row = 0; ?>
                    <?php foreach ($product_recurrings as $product_recurring) { ?>

                    <tr id="recurring-row<?php echo $recurring_row; ?>">
                      <td class="text-left"><select name="product_recurring[<?php echo $recurring_row; ?>][recurring_id]" class="form-control">
                          <?php foreach ($recurrings as $recurring) { ?>
                          <?php if ($recurring['recurring_id'] == $product_recurring['recurring_id']) { ?>
                          <option value="<?php echo $recurring['recurring_id']; ?>" selected="selected"><?php echo $recurring['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $recurring['recurring_id']; ?>"><?php echo $recurring['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select></td>
                      <td class="text-left"><select name="product_recurring[<?php echo $recurring_row; ?>][customer_group_id]" class="form-control">
                          <?php foreach ($customer_groups as $customer_group) { ?>
                          <?php if ($customer_group['customer_group_id'] == $product_recurring['customer_group_id']) { ?>
                          <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select></td>
                      <td class="text-left"><button type="button" onclick="$('#recurring-row<?php echo $recurring_row; ?>').remove()" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                    </tr>
                    <?php $recurring_row++; ?>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="2"></td>
                      <td class="text-left"><button type="button" onclick="addRecurring()" data-toggle="tooltip" title="<?php echo $button_recurring_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
            <div class="tab-pane" id="tab-discount">
              <div class="table-responsive">
                <table id="discount" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_customer_group; ?></td>
                      <td class="text-right"><?php echo $entry_quantity; ?></td>
                      <td class="text-right"><?php echo $entry_priority; ?></td>
                      <td class="text-right"><?php echo $entry_price; ?></td>

<?php if ($prm_access_permission && $laccess) { ?>
		<?php if ($adv_price_tax) { ?>
		<td class="text-right"><?php echo $entry_price_tax; ?></td>
		<?php } ?>
		<td class="text-right"><?php echo $entry_cost; ?></td>
		<td class="text-right"><?php echo $entry_profit; ?></td>
<?php } ?>		
            
                      <td class="text-left"><?php echo $entry_date_start; ?></td>
                      <td class="text-left"><?php echo $entry_date_end; ?></td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $discount_row = 0; ?>
                    <?php foreach ($product_discounts as $product_discount) { ?>
                    <tr id="discount-row<?php echo $discount_row; ?>">
                      <td class="text-left"><select name="product_discount[<?php echo $discount_row; ?>][customer_group_id]" class="form-control">
                          <?php foreach ($customer_groups as $customer_group) { ?>
                          <?php if ($customer_group['customer_group_id'] == $product_discount['customer_group_id']) { ?>
                          <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select></td>
                      <td class="text-right"><input type="text" name="product_discount[<?php echo $discount_row; ?>][quantity]" value="<?php echo $product_discount['quantity']; ?>" placeholder="<?php echo $entry_quantity; ?>" class="form-control" /></td>
                      <td class="text-right"><input type="text" name="product_discount[<?php echo $discount_row; ?>][priority]" value="<?php echo $product_discount['priority']; ?>" placeholder="<?php echo $entry_priority; ?>" class="form-control" /></td>
                      <td class="text-right" style="width: 20%;">
                    <label>Discount(%)</label>
                    <input name="product_discount[<?php echo $discount_row; ?>][discount_percent]" style="" type="text" onblur="calcDiscount(this);" data-price="<?php echo $price; ?>" class="prod_discount  form-control" value="<?php echo $product_discount['discount_percent']; ?>"/>
                        <input type="text" class="prod_discount_val form-control"  name="product_discount[<?php echo $discount_row; ?>][price]" value="<?php echo $product_discount['price']; ?>" placeholder="<?php echo $entry_price; ?>" /></td>
                      <td class="text-left" style="width: 20%;"><div class="input-group date">
                          <input type="text" name="product_discount[<?php echo $discount_row; ?>][date_start]" value="<?php echo $product_discount['date_start']; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" class="form-control" />
                          <span class="input-group-btn">
                          <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                          </span></div></td>
                      <td class="text-left" style="width: 20%;"><div class="input-group date">
                          <input type="text" name="product_discount[<?php echo $discount_row; ?>][date_end]" value="<?php echo $product_discount['date_end']; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" class="form-control" />
                          <span class="input-group-btn">
                          <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                          </span></div></td>
                      <td class="text-left"><button type="button" onclick="$('#discount-row<?php echo $discount_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                    </tr>
                    <?php $discount_row++; ?>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="6"></td>
                      <td class="text-left"><button type="button" onclick="addDiscount();" data-toggle="tooltip" title="<?php echo $button_discount_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
            <div class="tab-pane" id="tab-special">
              <div class="table-responsive">
                <table id="special" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_customer_group; ?></td>
                      <td class="text-right"><?php echo $entry_priority; ?></td>
                      <td class="text-right"><?php echo $entry_price; ?></td>
                      <td class="text-left"><?php echo $entry_date_start; ?></td>
                      <td class="text-left"><?php echo $entry_date_end; ?></td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $special_row = 0; ?>
                    <?php foreach ($product_specials as $product_special) { ?>
                    <tr id="special-row<?php echo $special_row; ?>">
                      <td class="text-left"><select name="product_special[<?php echo $special_row; ?>][customer_group_id]" class="form-control">
                          <?php foreach ($customer_groups as $customer_group) { ?>
                          <?php if ($customer_group['customer_group_id'] == $product_special['customer_group_id']) { ?>
                          <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select></td>
                      <td class="text-right"><input type="text" name="product_special[<?php echo $special_row; ?>][priority]" value="<?php echo $product_special['priority']; ?>" placeholder="<?php echo $entry_priority; ?>" class="form-control" /></td>
                      <td class="text-right"><input type="text" name="product_special[<?php echo $special_row; ?>][price]" value="<?php echo $product_special['price']; ?>" placeholder="<?php echo $entry_price; ?>" class="form-control" /></td>
                      <td class="text-left" style="width: 20%;"><div class="input-group date">
                          <input type="text" name="product_special[<?php echo $special_row; ?>][date_start]" value="<?php echo $product_special['date_start']; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" class="form-control" />
                          <span class="input-group-btn">
                          <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                          </span></div></td>
                      <td class="text-left" style="width: 20%;"><div class="input-group date">
                          <input type="text" name="product_special[<?php echo $special_row; ?>][date_end]" value="<?php echo $product_special['date_end']; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" class="form-control" />
                          <span class="input-group-btn">
                          <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                          </span></div></td>
                      <td class="text-left"><button type="button" onclick="$('#special-row<?php echo $special_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                    </tr>
                    <?php $special_row++; ?>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="5"></td>
                      <td class="text-left"><button type="button" onclick="addSpecial();" data-toggle="tooltip" title="<?php echo $button_special_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
            <div class="tab-pane" id="tab-image">
              <div class="table-responsive">
                <table id="images" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_image; ?></td>
                      <td class="text-right"><?php echo $entry_sort_order; ?></td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $image_row = 0; ?>
                    <?php foreach ($product_images as $product_image) { ?>
                    <tr id="image-row<?php echo $image_row; ?>">
                      <td class="text-left"><a href="" id="thumb-image<?php echo $image_row; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $product_image['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="product_image[<?php echo $image_row; ?>][image]" value="<?php echo $product_image['image']; ?>" id="input-image<?php echo $image_row; ?>" /></td>
                      <td class="text-right"><input type="text" name="product_image[<?php echo $image_row; ?>][sort_order]" value="<?php echo $product_image['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>
                      <td class="text-left"><button type="button" onclick="$('#image-row<?php echo $image_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                    </tr>
                    <?php $image_row++; ?>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="2"></td>
                      <td class="text-left"><button type="button" onclick="addImage();" data-toggle="tooltip" title="<?php echo $button_image_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
            <div class="tab-pane" id="tab-reward">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-points"><span data-toggle="tooltip" title="<?php echo $help_points; ?>"><?php echo $entry_points; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="points" value="<?php echo $points; ?>" placeholder="<?php echo $entry_points; ?>" id="input-points" class="form-control" />
                </div>
              </div>
              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_customer_group; ?></td>
                      <td class="text-right"><?php echo $entry_reward; ?></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($customer_groups as $customer_group) { ?>
                    <tr>
                      <td class="text-left"><?php echo $customer_group['name']; ?></td>
                      <td class="text-right"><input type="text" name="product_reward[<?php echo $customer_group['customer_group_id']; ?>][points]" value="<?php echo isset($product_reward[$customer_group['customer_group_id']]) ? $product_reward[$customer_group['customer_group_id']]['points'] : ''; ?>" class="form-control" /></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="tab-pane" id="tab-design">
              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_store; ?></td>
                      <td class="text-left"><?php echo $entry_layout; ?></td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="text-left"><?php echo $text_default; ?></td>
                      <td class="text-left"><select name="product_layout[0]" class="form-control">
                          <option value=""></option>
                          <?php foreach ($layouts as $layout) { ?>
                          <?php if (isset($product_layout[0]) && $product_layout[0] == $layout['layout_id']) { ?>
                          <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select></td>
                    </tr>
                    <?php foreach ($stores as $store) { ?>
                    <tr>
                      <td class="text-left"><?php echo $store['name']; ?></td>
                      <td class="text-left"><select name="product_layout[<?php echo $store['store_id']; ?>]" class="form-control">
                          <option value=""></option>
                          <?php foreach ($layouts as $layout) { ?>
                          <?php if (isset($product_layout[$store['store_id']]) && $product_layout[$store['store_id']] == $layout['layout_id']) { ?>
                          <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
            
            <!-- Tab Blog -->
            <div class="tab-pane" id="tab-blog">
               <div class="table-responsive">
                <table id="article" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left">Article/Video From Blog</td>

                    <td class="text-left" style="width:15%">Display Text</td>
                    <td class="text-left" style="width:15%">Embed in Description? <br> (Internal links only)</td>
                      <td class="text-left" style="width:15%">Sort Order</td>
                
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $article_row = 0; ?>
                    <?php foreach ($product_articles as $product_article) { ?>
                    <tr id="article-row<?php echo $article_row; ?>">
                      <td class="text-left"><input type="text" name="product_article[<?php echo $article_row; ?>][name]" value="<?php echo $product_article['name']; ?>" placeholder="<?php echo $entry_article; ?>" class="form-control" />

<td class="text-left"><input type="text" name="product_article[<?php echo $article_row; ?>][display_text]" value="<?php echo $product_article['display_text']; ?>" placeholder="Enter Display Text" class="form-control" />                   
<td class="text-left"><input type="checkbox" id="product_article[<?php echo $article_row; ?>][embed_in_description]" value="<?php echo $product_article['embed_in_description']; ?>" onclick="updateEmbedCheck('<?php echo $article_row; ?>')" <?=($product_article['embed_in_description']=='1'?'checked':'')  ?> class="form-control" />
                      <td class="text-left"><input type="number" name="product_article[<?php echo $article_row; ?>][sort_order]" value="<?php echo $product_article['sort_order']; ?>" class="form-control" />
                                        <input type="hidden" name="product_article[<?php echo $article_row; ?>][embed_in_description]" value="<?php echo $product_article['embed_in_description']; ?>" />
                
                        <input type="hidden" name="product_article[<?php echo $article_row; ?>][article_id]" value="<?php echo $product_article['article_id']; ?>" /></td>
                      <td class="text-left"><button type="button" onclick="$('#article-row<?php echo $article_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                    </tr>
                    <?php $article_row++; ?>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td></td>

                     <td></td>
                     <td></td>
                     <td></td>
                  
                      <td class="text-left"><button type="button" onclick="addArticle();" data-toggle="tooltip" title="Add Article/Video" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
            <!-- / Tab Blog -->

            <!-- Tab Video Product -->
            <div class="tab-pane" id="tab-video">
               <div class="table-responsive">
                <table id="video" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left">Enter Product Video Link</td>
                    </tr>
                    <tr>
                      <td class="text-left">YouTube videos URLs must be in HTTP/HTTPS url Format <br>e.g. https://www.youtube.com/something/something... <br><br>Local Video URLs must be reference from image direcotry e.g <br>data/video/something.mp4</td>
                      <td class="text-left"><button type="button" onclick="addVideo();" data-toggle="tooltip" title="Add Video" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                      $video_row = 0;
                      foreach ($product_vlink as $vlink) {
                     ?>
                    <tr id="video-row<?php echo $video_row;?>">
                      <td>
                        <input name="p_v_link[<?php echo $video_row;?>]" id="p_v_link" type="text" class="form-control" value="<?php echo $vlink; ?>"/>
                      </td>
                      <td class="text-left"><button type="button" onclick="$('#video-row<?php echo $video_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>

                    </tr>
                    <?php $video_row++;} ?>
                  </tbody>
                </table>

                <table class="table table-striped table-bordered table-hover">
                  <tbody>
                    <tr>
                      <td class="text-left"><strong>Video Banner/Poster/Thumbnail to display just in case.</strong></td>
                    </tr>
                    <tr>
                      <td>
                        <input name="p_p_link" id="p_p_link" type="text" class="form-control" value="<?php echo $product_plink; ?>"/>
                      </td>
                    </tr>
                  </tbody>
                  
                </table>
              </div>
            </div>
            <!-- / Tab Product Video -->

            <!-- Tab Video Product -->
            <div class="tab-pane" id="tab-qrcode">
               <div class="table-responsive">
                <table id="qrcode" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left">QR CODE FORMAT</td>
                      <td><input name="p_qr_code" id="p_qr_code" type="text" class="form-control" value="<?php echo $product_qrcode; ?>"/></td>
                    </tr>
                    <tr>
                      <td colspan="2" class="text-right">
                        
                        <a href="" id="qr-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $qrcode; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                    </td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $qrcode_row = 0; ?>
                    <?php if (!empty($product_qrcode_fields)) : ?>
                    <?php foreach ($product_qrcode_fields as $product_qrcode_field) { ?>
                    <tr id="qrcode-row<?php echo $qrcode_row; ?>">
                      <td class="text-left">
                        <select id="qrcode_val<?php echo $qrcode_row;?>" onChange="updateQRFormat(<?php echo $qrcode_row; ?>)">
                        <?php foreach ($qrcode_fields as $qrcode_field) { ?>
                          <?php if ($qrcode_field == $product_qrcode_field) { ?>
                            <option value="<?php echo $qrcode_field; ?>" selected><?php echo $qrcode_field; ?></option>
                          <?php }else{ ?>
                            <option value="<?php echo $qrcode_field; ?>"><?php echo $qrcode_field; ?></option>
                          <?php } ?>
                        <?php } ?>
                        </select>
                      </td>
                      <td class="text-left"><button type="button" onclick="removeQRFormat(<?php echo $qrcode_row; ?>)" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                    </tr>
                    <?php $qrcode_row++; ?>
                    <?php } ?>
                  <?php endif; ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td></td>
                      <td class="text-left"><button type="button" onclick="addQRFormat();" data-toggle="tooltip" title="Add QR Format" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
            <!-- / Tab Product Video -->

<?php if ($product_id) { ?> 
<?php if ($prm_access_permission && $laccess) { ?>
<div class="tab-pane" id="tab-history">
<style type="text/css">
.list_main_history {
	border-collapse: collapse;
	width: 100%;
	border-top: 1px solid #DDDDDD;
	border-left: 1px solid #DDDDDD;	
	margin-bottom: 20px;
}
.list_main_history td {
	border-right: 1px solid #DDDDDD;
	border-bottom: 1px solid #DDDDDD;	
}
.list_main_history thead td {
	background-color: #f5f5f5;
	padding: 0px 5px;
}

.list_main_history thead td a, .list_main_history thead td {

	text-decoration: none;
	color: #222222;
	font-weight: bold;	
}
.list_main_history tbody td {
	vertical-align: middle;
	padding: 0px 5px;
}
.list_main_history .left {
	text-align: left;
	padding: 7px;
}
.list_main_history .right {
	text-align: right;
	padding: 7px;
}
.list_main_history .center {
	text-align: center;
	padding: 3px;
}
.list_main_history a.asc:after {
	content: " \f107";
	font-family: FontAwesome;
	font-size: 14px;
}
.list_main_history a.desc:after {
	content: " \f106";
	font-family: FontAwesome;
	font-size: 14px;
}
.list_main_history .noresult {
	text-align: center;
	padding: 7px;
}

.btn-type {
	background-color: #fcfcfc;
	border: 1px solid #CCC;
}
.btn-select {
	background-color: #fcfcfc;
	border: 1px solid #CCC;
}
.hloading {
	position: fixed;
	left: 0px;
	top: 0px;
	width: 100%;
	height: 100%;
	z-index: 9999;
	background: url('view/image/adv_reports/page_loading.gif') 50% 50% no-repeat rgb(255,255,255);
}
</style> 
<div class="hloading"></div>
<input type="hidden" id="page_history" value="<?php echo $page_history ?>">
<input type="hidden" id="sort_history" value="<?php echo $sort_history ?>">
<input type="hidden" id="order_history" value="<?php echo $order_history ?>">
<script type="text/javascript">
$(document).ready(function() {
var $filter_history_range = $('#filter_history_range'), $date_start = $('#date-start-history'), $date_end = $('#date-end-history');
$filter_history_range.change(function () {
    if ($filter_history_range.val() == 'custom') {
        $date_start.removeAttr('disabled');
        $date_end.removeAttr('disabled');
    } else {	
        $date_start.prop('disabled', 'disabled').val('');
        $date_end.prop('disabled', 'disabled').val('');
    }
}).trigger('change');
});
</script>
<div align="right"><button type="button" onclick="history_download();" class="btn btn-info" style="margin-bottom:10px;"><i class="fa fa-download"></i> <?php echo $button_hdownload;?></button>&nbsp;<button type="button" onclick="history_delete();" class="btn btn-danger" style="margin-bottom:10px;"><i class="fa fa-eraser"></i> <?php echo $button_hdelete;?></button></div> 
<div class="well">
    <div class="row">
      <div class="col-lg-6" style="padding-bottom:5px;">	  
        <div class="row">
          <div class="col-sm-6" style="padding-bottom:5px;">
		  <label class="control-label" for="filter_history_range"><?php echo $entry_hrange; ?></label>
            <select name="filter_history_range" id="filter_history_range" data-style="btn-select" class="form-control select">
              <?php foreach ($ranges_history as $range_history) { ?>
              <?php if ($range_history['value'] == $filter_history_range) { ?>
              <option id="<?php echo $range_history['id']; ?>" value="<?php echo $range_history['value']; ?>" title="<?php echo $range_history['text']; ?>" style="<?php echo $range_history['style']; ?>" selected="selected"><?php echo $range_history['text']; ?></option>
              <?php } else { ?>
              <option id="<?php echo $range_history['id']; ?>" value="<?php echo $range_history['value']; ?>" title="<?php echo $range_history['text']; ?>" style="<?php echo $range_history['style']; ?>"><?php echo $range_history['text']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></div>
          <div class="col-sm-3" style="padding-bottom:5px;">
		  <label class="control-label" for="date-start-history"><?php echo $entry_hdate_start; ?></label>
            <input type="text" name="filter_history_date_start" value="<?php echo $filter_history_date_start; ?>" data-date-format="YYYY-MM-DD" id="date-start-history" class="form-control" style="color:#F90;" />
		  </div>
          <div class="col-sm-3" style="padding-bottom:5px;">
		  <label class="control-label" for="date-end-history"><?php echo $entry_hdate_end; ?></label>
            <input type="text" name="filter_history_date_end" value="<?php echo $filter_history_date_end; ?>" data-date-format="YYYY-MM-DD" id="date-end-history" class="form-control" style="color:#F90;" />
          </div>
        </div>
	  </div>   
      <div class="col-lg-3" style="padding-bottom:5px;">
	  <label class="control-label" for="filter_history_option"><?php echo $entry_hoption; ?></label>
          <select name="filter_history_option" id="filter_history_option" data-style="btn-type" class="form-control select" <?php echo ($option_histories) ? '' : 'disabled' ?>>
			<option id="main_product" value="0"><?php echo $text_nooption; ?></option>
            <?php foreach ($option_histories as $option_history) { ?>
			<?php if ($option_history['options'] == $filter_history_option) { ?>             
            <option value="<?php echo $option_history['options']; ?>" selected="selected"><?php echo $option_history['option_name']; ?>: <?php echo $option_history['option_value']; ?> <?php if ($option_history['option_sku']) { ?>[<?php echo $option_history['option_sku']; ?>]<?php } ?></option>
            <?php } else { ?>
            <option value="<?php echo $option_history['options']; ?>"><?php echo $option_history['option_name']; ?>: <?php echo $option_history['option_value']; ?> <?php if ($option_history['option_sku']) { ?>[<?php echo $option_history['option_sku']; ?>]<?php } ?></option>
            <?php } ?>
            <?php } ?>  
          </select>
	  </div>
      <div class="col-lg-3" style="padding-bottom:5px;">
	  <label class="control-label" for="filter_history_supplier"><?php echo $entry_hsupplier; ?></label>
          <select name="filter_history_supplier" id="filter_history_supplier" data-style="btn-type" class="form-control select" <?php echo ($supplier_histories) ? '' : 'disabled' ?>>
			<option value=""><?php echo $text_all_hsuppliers; ?></option>
            <?php foreach ($supplier_histories as $supplier_history) { ?>
			<?php if ($supplier_history['supplier_id'] == $filter_history_supplier) { ?>             
            <option value="<?php echo $supplier_history['supplier_id']; ?>" selected="selected"><?php echo $supplier_history['supplier_name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $supplier_history['supplier_id']; ?>"><?php echo $supplier_history['supplier_name']; ?></option>
            <?php } ?>
            <?php } ?>  
          </select>
	  </div>	  
    </div> 
</div>	
<?php if ($histories) { ?>	
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<div id="tab_chart_history">
<script type="text/javascript">
	google.charts.load('current', {'packages':['corechart']});
	google.charts.setOnLoadCallback(drawChart);    
	function drawChart() {  
   		var data = google.visualization.arrayToDataTable([
			<?php if ($ghistories) {
				echo "['','". $entry_gstock_quantity . "'],";
					foreach ($ghistories as $key => $ghistory) {
						if (count($ghistories)==($key+1)) {
							echo "['" . $ghistory['ghdate_added'] . "',". $ghistory['ghstock_quantity'] . "]";
						} else {
							echo "['" . $ghistory['ghdate_added'] . "',". $ghistory['ghstock_quantity'] . "],";
						}
					}		
			} 
			;?>
		]);

        var options = {
			title: 'Stock Quantity History',
			height: 266,
			colors: ['#ebd685'],
			pointSize: '4',
			hAxis: {title: '<?php echo $column_hdate_added; ?>', titleTextStyle: {color: '#333', fontSize: 12}, baselineColor: '#fff', gridlineColor: '#fff', textPosition: 'none'},
			legend: {position: 'right', alignment: 'start', textStyle: {color: '#666666', fontSize: 12}}	  
        };

			var chart = new google.visualization.AreaChart(document.getElementById('chart1_div'));
			chart.draw(data, options);
			
    		$("#chart_resize").mousemove(function() {
        		chart.draw(data, options);
    		});	
									
    		$(window).resize(function() {
        		chart.draw(data, options);
    		});			
	}
</script>
<script type="text/javascript">
	google.charts.load('current', {'packages':['corechart']});
	google.charts.setOnLoadCallback(drawChart);    
	function drawChart() {  
   		var data = google.visualization.arrayToDataTable([
			<?php if ($ghistories) {
				echo "['','". $entry_gprice . "','" . $entry_gcost . "','" . $entry_gprofit . "'],";
					foreach ($ghistories as $key => $ghistory) {
						if (count($ghistories)==($key+1)) {
							echo "['" . $ghistory['ghdate_added'] . "',". $ghistory['ghprice'] . ",". $ghistory['ghcost'] . ",". $ghistory['ghprofit'] . "]";
						} else {
							echo "['" . $ghistory['ghdate_added'] . "',". $ghistory['ghprice'] . ",". $ghistory['ghcost'] . ",". $ghistory['ghprofit'] . "],";
						}
					}		
			} 
			;?>
		]);

        var options = {
			title: 'Price, Cost and Profit History of Stock',
			height: 266,
			colors: ['#b5e08b', '#ed9999', '#739cc3'],
			pointSize: '4',
			hAxis: {title: '<?php echo $column_hdate_added; ?>', titleTextStyle: {color: '#333', fontSize: 12}, baselineColor: '#fff', gridlineColor: '#fff', textPosition: 'none'},
			legend: {position: 'right', alignment: 'start', textStyle: {color: '#666666', fontSize: 12}}	  
        };

			var chart = new google.visualization.AreaChart(document.getElementById('chart2_div'));
			chart.draw(data, options);

    		$("#chart_resize").mousemove(function() {
        		chart.draw(data, options);
    		});	
									
    		$(window).resize(function() {
        		chart.draw(data, options);
    		});			
	}
</script>  
<script type="text/javascript">
function save_comment(id) {
	var input_comment = $('#comment-'+id+' textarea');
	var comment = $(input_comment).val();
	$.ajax({
		<?php if ($modify_permission) { ?>
			url: 'index.php?route=catalog/product/saveProductStockHistoryComment&product_stock_history_id='+id+'&comment='+comment+'&token=<?php echo $token; ?>',
		<?php } else { ?>
			url: '',
		<?php } ?>
		dataType: 'html',
		data: {},
		success: function(comment) { 
			$('#comment-'+id).next().html(comment);
		}
	});
	$(input_comment).css('cursor','text');
}
function save_comment_option(id) {
	var input_comment = $('#comment-'+id+' textarea');
	var comment = $(input_comment).val();
	$.ajax({
		<?php if ($modify_permission) { ?>
			url: 'index.php?route=catalog/product/saveProductOptionStockHistoryComment&product_option_stock_history_id='+id+'&comment='+comment+'&token=<?php echo $token; ?>',
		<?php } else { ?>
			url: '',
		<?php } ?>
		dataType: 'html',
		data: {},
		success: function(comment) { 
			$('#comment-'+id).next().html(comment);
		}
	});
	$(input_comment).css('cursor','text');
}
</script>
<script type="text/javascript">
function stopRKey(evt) { 
	var evt = (evt) ? evt : ((event) ? event : null); 
	var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null); 
	if ((evt.keyCode == 13) && (node.type=="text")) {
		return false;
	} 
} 
document.onkeypress = stopRKey; 
</script>
    <div class="row">
      <div class="col-lg-12"><div class="form-group">
      	<div style="width:100%;" id="chart1_div"></div>
      </div></div>  
    </div>	 
    <div class="row">	 
      <div class="col-lg-12"><div class="form-group">      
        <div style="width:100%;" id="chart2_div"></div>
      </div></div>  
    </div>
</div>
<?php } ?>	
<div class="table-responsive">
    <table class="list_main_history">
      <thead>
        <tr id="head_history">
          <td class="left" style="min-width:90px;"><?php if ($sort_history == 'psh.date_added') { ?>
                <a href="<?php echo $sort_history_date_added; ?>" class="<?php echo strtolower($order_history); ?>"><?php echo $column_hdate_added; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_history_date_added; ?>"><?php echo $column_hdate_added; ?></a>
                <?php } ?></td>
          <td class="left"><?php if ($sort_history == 'comment') { ?>
                <a href="<?php echo $sort_history_comment; ?>" class="<?php echo strtolower($order_history); ?>"><label class="control-label" style="cursor:pointer;"><span data-toggle="tooltip" title="<?php echo $help_comment; ?>"><?php echo $column_comment; ?></span></label></a>
                <?php } else { ?>
                <a href="<?php echo $sort_history_comment; ?>"><label class="control-label" style="cursor:pointer;"><span data-toggle="tooltip" title="<?php echo $help_comment; ?>"><?php echo $column_comment; ?></span></label></a>
                <?php } ?></td>	
          <td class="left"><?php if ($sort_history == 'supplier') { ?>
                <a href="<?php echo $sort_history_supplier; ?>" class="<?php echo strtolower($order_history); ?>"><label class="control-label"><?php echo $column_hsupplier; ?></label></a>
                <?php } else { ?>
                <a href="<?php echo $sort_history_supplier; ?>"><label class="control-label"><?php echo $column_hsupplier; ?></label></a>
                <?php } ?></td>					
          <td class="right"><?php if ($sort_history == 'costing_method') { ?>
                <a href="<?php echo $sort_history_costing_method; ?>" class="<?php echo strtolower($order_history); ?>"><label class="control-label" style="cursor:pointer;"><span data-toggle="tooltip" title="<?php echo $help_costing_methods; ?>"><?php echo $column_hcosting_method; ?></span></label></a>
                <?php } else { ?>
                <a href="<?php echo $sort_history_costing_method; ?>"><label class="control-label" style="cursor:pointer;"><span data-toggle="tooltip" title="<?php echo $help_costing_methods; ?>"><?php echo $column_hcosting_method; ?></span></label></a>
                <?php } ?></td>					  			
          <td class="right"><?php if ($sort_history == 'restock_quantity') { ?>
                <a href="<?php echo $sort_history_restock_quantity; ?>" class="<?php echo strtolower($order_history); ?>"><?php echo $column_hrestock_quantity; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_history_restock_quantity; ?>"><?php echo $column_hrestock_quantity; ?></a>
                <?php } ?></td>
          <td class="right"><?php if ($sort_history == 'stock_quantity') { ?>
                <a href="<?php echo $sort_history_stock_quantity; ?>" class="<?php echo strtolower($order_history); ?>"><?php echo $column_hstock_quantity; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_history_stock_quantity; ?>"><?php echo $column_hstock_quantity; ?></a>
                <?php } ?></td>
          <td class="right"><?php if ($sort_history == 'restock_cost') { ?>
                <a href="<?php echo $sort_history_restock_cost; ?>" class="<?php echo strtolower($order_history); ?>"><?php echo $column_hrestock_cost; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_history_restock_cost; ?>"><?php echo $column_hrestock_cost; ?></a>
                <?php } ?></td>				
          <td class="right"><?php if ($sort_history == 'cost') { ?>
                <a href="<?php echo $sort_history_cost; ?>" class="<?php echo strtolower($order_history); ?>"><?php echo $column_hcost; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_history_cost; ?>"><?php echo $column_hcost; ?></a>
                <?php } ?></td>
          <td class="right"><?php if ($sort_history == 'price') { ?>
                <a href="<?php echo $sort_history_price; ?>" class="<?php echo strtolower($order_history); ?>"><?php echo $column_hprice; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_history_price; ?>"><?php echo $column_hprice; ?></a>
                <?php } ?></td>
          <td class="right"><?php if ($sort_history == 'profit') { ?>
                <a href="<?php echo $sort_history_profit; ?>" class="<?php echo strtolower($order_history); ?>"><?php echo $column_hprofit; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_history_profit; ?>"><?php echo $column_hprofit; ?></a>
                <?php } ?></td>	
          <td class="right"><?php if ($sort_history == 'profit_margin') { ?>
                <a href="<?php echo $sort_history_profit_margin; ?>" class="<?php echo strtolower($order_history); ?>"><?php echo $entry_gmargin; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_history_profit_margin; ?>"><?php echo $entry_gmargin; ?></a>
                <?php } ?></td>		
          <td class="right"><?php if ($sort_history == 'profit_markup') { ?>
                <a href="<?php echo $sort_history_profit_markup; ?>" class="<?php echo strtolower($order_history); ?>"><?php echo $entry_gmarkup; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_history_profit_markup; ?>"><?php echo $entry_gmarkup; ?></a>
                <?php } ?></td>										
        </tr>
      </thead>
      <tbody>
        <?php if ($histories) { ?>		
        <?php foreach ($histories as $history) { ?>		
        <tr>
          <td class="left"><?php echo $history['hdate_added']; ?></td> 		  
		  <td class="left">
		  <?php if ($history['nooption'] == 1) { ?>
		  <span id="comment-<?php echo $history['product_stock_history_id']; ?>" value="<?php echo $history['product_stock_history_id']; ?>">
		  <textarea onchange="save_comment(<?php echo $history['product_stock_history_id']; ?>)" placeholder="<?php echo $column_comment; ?>" style="border:none; width:100%;"><?php echo $history['comment']; ?></textarea>
		  </span> 
		  <?php } else { ?>
		  <span id="comment-<?php echo $history['product_option_stock_history_id']; ?>" value="<?php echo $history['product_option_stock_history_id']; ?>">
		  <textarea onchange="save_comment_option(<?php echo $history['product_option_stock_history_id']; ?>)" placeholder="<?php echo $column_comment; ?>" style="border:none; width:100%;"><?php echo $history['comment']; ?></textarea>
		  </span> 
		  <?php } ?>
		  </td>
          <td class="left"><?php echo $history['hsupplier']; ?></td>		  
          <td class="right"><?php echo $history['hcosting_method']; ?></td>		  
          <td class="right"><?php echo $history['hrestock_quantity']; ?></td>
          <td class="right" style="background:#f9f3db;"><?php echo $history['hstock_quantity']; ?></td>
          <td class="right"><?php echo $history['hrestock_cost']; ?></td>
		  <td class="right" style="background:#f7e9e3;"><?php echo $history['hcost']; ?></td>
          <td class="right" style="background:#f1f9e9;"><?php echo $history['hprice']; ?></td>
		  <td class="right" style="background:#dfe7ee;"><?php echo $history['hprofit']; ?></td>
		  <td class="right" style="background:#dfe7ee;"><?php echo $history['hprofit_margin']; ?></td>
		  <td class="right" style="background:#dfe7ee;"><?php echo $history['hprofit_markup']; ?></td>
        </tr>       
        <?php } ?>
        <?php } else { ?>
        <tr>
          <td class="center" colspan="12"><?php echo $text_no_results; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
</div>	
		<div class="row">
		<div class="col-sm-6 text-left" id="pagination_history"><?php echo $pagination_history; ?></div>
        <div class="col-sm-6 text-right" id="pagination_history_count"><?php echo $results_history; ?></div>
		</div>	
</div>
<script id="historyTemplate" type="text/x-jquery-tmpl">
        <tr>
          <td class="left">${hdate_added}</td>
		  <td class="left">
		  {{if nooption == 1}}
		  <span id="comment-${product_stock_history_id}" value="${product_stock_history_id}">
		  <textarea onchange="save_comment(${product_stock_history_id})" placeholder="<?php echo $column_comment; ?>" style="border:none; width:100%;">${comment}</textarea>
		  </span>
		  {{else}}
		  <span id="comment-${product_option_stock_history_id}" value="${product_option_stock_history_id}">
		  <textarea onchange="save_comment_option(${product_option_stock_history_id})" placeholder="<?php echo $column_comment; ?>" style="border:none; width:100%;">${comment}</textarea>
		  </span>
		  {{/if}}
		  </td>
          <td class="left">${hsupplier}</td>		  
          <td class="right">${hcosting_method}</td>		  
          <td class="right">${hrestock_quantity}</td>
          <td class="right" style="background:#f9f3db; width:auto;">${hstock_quantity}</td>
          <td class="right">${hrestock_cost}</td>
		  <td class="right" style="background:#f7e9e3;">${hcost}</td>
          <td class="right" style="background:#f1f9e9;">${hprice}</td>
		  <td class="right" style="background:#dfe7ee;">${hprofit}</td>
		  <td class="right" style="background:#dfe7ee;">${hprofit_margin}</td>
		  <td class="right" style="background:#dfe7ee;">${hprofit_markup}</td>
        </tr>  
</script>

<div class="tab-pane" id="tab-sales">
<style type="text/css">
.list_main_sale {
	border-collapse: collapse;
	width: 100%;
	border-top: 1px solid #DDDDDD;
	border-left: 1px solid #DDDDDD;	
	margin-bottom: 20px;
}
.list_main_sale td {
	border-right: 1px solid #DDDDDD;
	border-bottom: 1px solid #DDDDDD;	
}
.list_main_sale thead td {
	background-color: #f5f5f5;
	padding: 0px 5px;
}

.list_main_sale thead td a, .list_main_sale thead td {
	text-decoration: none;
	color: #222222;
	font-weight: bold;	
}
.list_main_sale tbody td {
	vertical-align: middle;
	padding: 0px 5px;
}
.list_main_sale .left {
	text-align: left;
	padding: 7px;
}
.list_main_sale .right {
	text-align: right;
	padding: 7px;
}
.list_main_sale .center {
	text-align: center;
	padding: 3px;
}
.list_main_sale a.asc:after {
	content: " \f107";
	font-family: FontAwesome;
	font-size: 14px;
}
.list_main_sale a.desc:after {
	content: " \f106";
	font-family: FontAwesome;
	font-size: 14px;
}
.list_main_sale .noresult {
	text-align: center;
	padding: 7px;
}

.btn-select {
	background-color: #fcfcfc;
	border: 1px solid #CCC;
}
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
.sloading {
	position: fixed;
	left: 0px;
	top: 0px;
	width: 100%;
	height: 100%;
	z-index: 9999;
	background: url('view/image/adv_reports/page_loading.gif') 50% 50% no-repeat rgb(255,255,255);
}
</style> 
<div class="sloading"></div>
<input type="hidden" id="page_sale" value="<?php echo $page_sale ?>">
<input type="hidden" id="sort_sale" value="<?php echo $sort_sale ?>">
<input type="hidden" id="order_sale" value="<?php echo $order_sale ?>">
<script type="text/javascript">
$(document).ready(function() {
var $filter_sale_range = $('#filter_sale_range'), $date_start = $('#date-start-sale'), $date_end = $('#date-end-sale');
$filter_sale_range.change(function () {
    if ($filter_sale_range.val() == 'custom') {
        $date_start.removeAttr('disabled');
        $date_end.removeAttr('disabled');
    } else {	
        $date_start.prop('disabled', 'disabled').val('');
        $date_end.prop('disabled', 'disabled').val('');
    }
}).trigger('change');
});
</script>
<div class="well">
    <div class="row">
      <div class="col-lg-6" style="padding-bottom:5px;">	  
        <div class="row">
          <div class="col-sm-6" style="padding-bottom:5px;">
		  <label class="control-label" for="filter_sale_range"><?php echo $entry_hrange; ?></label>
            <select name="filter_sale_range" id="filter_sale_range" data-style="btn-select" class="form-control select">
              <?php foreach ($ranges_sale as $range_sale) { ?>
              <?php if ($range_sale['value'] == $filter_sale_range) { ?>
              <option value="<?php echo $range_sale['value']; ?>" title="<?php echo $range_sale['text']; ?>" style="<?php echo $range_sale['style']; ?>" selected="selected"><?php echo $range_sale['text']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $range_sale['value']; ?>" title="<?php echo $range_sale['text']; ?>" style="<?php echo $range_sale['style']; ?>"><?php echo $range_sale['text']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></div>
          <div class="col-sm-3" style="padding-bottom:5px;">
		  <label class="control-label" for="date-start-sale"><?php echo $entry_hdate_start; ?></label>
            <input type="text" name="filter_sale_date_start" value="<?php echo $filter_sale_date_start; ?>" data-date-format="YYYY-MM-DD" id="date-start-sale" class="form-control" style="color:#F90;" />
		  </div>
          <div class="col-sm-3" style="padding-bottom:5px;">
		  <label class="control-label" for="date-end-sale"><?php echo $entry_hdate_end; ?></label>
            <input type="text" name="filter_sale_date_end" value="<?php echo $filter_sale_date_end; ?>" data-date-format="YYYY-MM-DD" id="date-end-sale" class="form-control" style="color:#F90;" />
          </div>
        </div>
	  </div>   
      <div class="col-lg-3" style="padding-bottom:5px;">
	  <label class="control-label" for="sale_order_status"><?php echo $entry_sstatus; ?></label>
            <select name="filter_sale_order_status" id="sale_order_status" class="form-control" multiple="multiple" size="1">		
            <?php foreach ($order_statuses as $order_status) { ?>
			<?php if (isset($filter_sale_order_status[$order_status['order_status_id']])) { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></div>
      <div class="col-lg-3" style="padding-bottom:5px;">
	  <label class="control-label" for="sale_option"><?php echo $entry_soption; ?></label>
            <select name="filter_sale_option" id="sale_option" class="form-control" multiple="multiple" size="1">
            <?php foreach ($order_options as $order_option) { ?>
			<?php if (isset($filter_sale_option[$order_option['options']])) { ?>
            <option value="<?php echo $order_option['options']; ?>" selected="selected"><?php echo $order_option['option_name']; ?>: <?php echo $order_option['option_value']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $order_option['options']; ?>"><?php echo $order_option['option_name']; ?>: <?php echo $order_option['option_value']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></div>		  
    </div> 
</div>	
<div class="table-responsive">
    <table class="list_main_sale">
      <thead id="head_sale">
        <tr>
          <td class="left"><?php if ($sort_sale == 'product_order_id') { ?>
                <a href="<?php echo $sort_sale_date_added; ?>" class="<?php echo strtolower($order_sale); ?>"><?php echo $column_prod_order_id; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_sale_date_added; ?>"><?php echo $column_prod_order_id; ?></a>
                <?php } ?></td>
          <td class="left"><?php if ($sort_sale == 'product_date_added') { ?>
                <a href="<?php echo $sort_sale_date_added; ?>" class="<?php echo strtolower($order_sale); ?>"><?php echo $column_prod_date_added; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_sale_date_added; ?>"><?php echo $column_prod_date_added; ?></a>
                <?php } ?></td>
          <td class="left" style="min-width:120px;"><?php if ($sort_sale == 'product_option') { ?>
                <a href="<?php echo $sort_sale_name; ?>" class="<?php echo strtolower($order_sale); ?>"><?php echo $column_prod_name; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_sale_name; ?>"><?php echo $column_prod_name; ?></a>
                <?php } ?></td> 
          <td class="right"><?php if ($sort_sale == 'product_sold') { ?>
                <a href="<?php echo $sort_sale_quantity; ?>" class="<?php echo strtolower($order_sale); ?>"><?php echo $column_prod_sold; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_sale_quantity; ?>"><?php echo $column_prod_sold; ?></a>
                <?php } ?></td>
          <td class="right" style="min-width:70px;"><?php if ($sort_sale == 'product_total_excl_vat') { ?>
                <a href="<?php echo $sort_sale_total_excl_tax; ?>" class="<?php echo strtolower($order_sale); ?>"><?php echo $column_prod_total_excl_vat; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_sale_total_excl_tax; ?>"><?php echo $column_prod_total_excl_vat; ?></a>
                <?php } ?></td>
          <td class="right"><?php if ($sort_sale == 'product_tax') { ?>
                <a href="<?php echo $sort_sale_tax; ?>" class="<?php echo strtolower($order_sale); ?>"><?php echo $column_prod_tax; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_sale_tax; ?>"><?php echo $column_prod_tax; ?></a>
                <?php } ?></td>
          <td class="right" style="min-width:70px;"><?php if ($sort_sale == 'product_total_incl_vat') { ?>
                <a href="<?php echo $sort_sale_total_incl_tax; ?>" class="<?php echo strtolower($order_sale); ?>"><?php echo $column_prod_total_incl_vat; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_sale_total_incl_tax; ?>"><?php echo $column_prod_total_incl_vat; ?></a>
                <?php } ?></td>	
          <td class="right"><?php if ($sort_sale == 'product_revenue') { ?>
                <a href="<?php echo $sort_sale_revenue; ?>" class="<?php echo strtolower($order_sale); ?>"><?php echo $column_prod_sales; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_sale_revenue; ?>"><?php echo $column_prod_sales; ?></a>
                <?php } ?></td>	
          <td class="right"><?php if ($sort_sale == 'product_cost') { ?>
                <a href="<?php echo $sort_sale_cost; ?>" class="<?php echo strtolower($order_sale); ?>"><?php echo $column_prod_cost; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_sale_cost; ?>"><?php echo $column_prod_cost; ?></a>
                <?php } ?></td>	
          <td class="right"><?php if ($sort_sale == 'product_profit') { ?>
                <a href="<?php echo $sort_sale_profit; ?>" class="<?php echo strtolower($order_sale); ?>"><?php echo $column_prod_profit; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_sale_profit; ?>"><?php echo $column_prod_profit; ?></a>
                <?php } ?></td>
          <td class="right" style="min-width:75px;"><?php if ($sort_sale == 'product_margin') { ?>
                <a href="<?php echo $sort_sale_profit_margin; ?>" class="<?php echo strtolower($order_sale); ?>"><?php echo $column_prod_margin; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_sale_profit_margin; ?>"><?php echo $column_prod_margin; ?></a>
                <?php } ?></td>
          <td class="right" style="min-width:75px;"><?php if ($sort_sale == 'product_markup') { ?>
                <a href="<?php echo $sort_sale_profit_markup; ?>" class="<?php echo strtolower($order_sale); ?>"><?php echo $column_prod_markup; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_sale_profit_markup; ?>"><?php echo $column_prod_markup; ?></a>
                <?php } ?></td>	
        </tr>
      </thead>
      <tbody>
        <?php if ($sales) { ?>
        <?php foreach ($sales as $sale) { ?>
        <tr>
          <td class="left"><?php echo $sale['product_order_id']; ?></td>
		  <td class="left"><?php echo $sale['product_date_added']; ?></td>
          <td class="left"><?php echo $sale['product_name']; ?> 
		  <?php if ($sale['product_option']) { ?>
          <table cellpadding="0" cellspacing="0" border="0" style="border:none;">
          <tr>
		  <td nowrap="nowrap" style="font-size:11px; border:0;"><?php echo $sale['product_option']; ?><?php echo $sale['product_order_product_id']; ?></td>
          </tr>
          </table>
		  <?php } ?>
		  </td>
          <td class="right"><?php echo $sale['product_sold']; ?></td>
		  <td class="right"><?php echo $sale['product_total_excl_vat']; ?></td>
		  <td class="right"><?php echo $sale['product_tax']; ?></td>
		  <td class="right"><?php echo $sale['product_total_incl_vat']; ?></td>
		  <td class="right" style="background-color:#DCFFB9;"><?php echo $sale['product_revenue']; ?></td>
		  <td class="right" style="background-color:#ffd7d7;"><?php echo $sale['product_cost']; ?></td>
		  <?php if ($sale['product_profit_raw'] >= 0) { ?>
		  <td class="right" style="background-color:#c4d9ee; font-weight:bold;"><?php echo $sale['product_profit']; ?></td>
		  <td class="right" style="background-color:#c4d9ee; font-weight:bold;"><?php echo $sale['product_profit_margin']; ?></td>
		  <td class="right" style="background-color:#c4d9ee; font-weight:bold;"><?php echo $sale['product_profit_markup']; ?></td>
		  <?php } else { ?>
		  <td class="right" style="background-color:#F99; font-weight:bold;"><?php echo $sale['product_profit']; ?></td>
		  <td class="right" style="background-color:#F99; font-weight:bold;"><?php echo $sale['product_profit_margin']; ?></td>
		  <td class="right" style="background-color:#F99; font-weight:bold;"><?php echo $sale['product_profit_markup']; ?></td>
		  <?php } ?>
        </tr>	
        <?php } ?>
		<tr style="border-top:2px solid #CCC;">
		  <td colspan="3" class="right" style="background-color:#E5E5E5; font-weight:bold;"><?php echo $column_prod_totals; ?></td>
          <td class="right" style="background-color:#E5E5E5; color:#003A88; font-weight:bold;"><?php echo $sale['product_sold_total']; ?></td>
		  <td class="right" style="background-color:#E5E5E5; color:#003A88; font-weight:bold;"><?php echo $sale['product_total_excl_vat_total']; ?></td>
		  <td class="right" style="background-color:#E5E5E5; color:#003A88; font-weight:bold;"><?php echo $sale['product_tax_total']; ?></td>
		  <td class="right" style="background-color:#E5E5E5; color:#003A88; font-weight:bold;"><?php echo $sale['product_total_incl_vat_total']; ?></td>
		  <td class="right" style="background-color:#DCFFB9; color:#003A88; font-weight:bold;"><?php echo $sale['product_revenue_total']; ?></td>
		  <td class="right" style="background-color:#ffd7d7; color:#003A88; font-weight:bold;"><?php echo $sale['product_cost_total']; ?></td>
		  <?php if ($sale['product_profit_raw_total'] >= 0) { ?>
		  <td class="right" style="background-color:#c4d9ee; color:#003A88; font-weight:bold;"><?php echo $sale['product_profit_total']; ?></td>
		  <td class="right" style="background-color:#c4d9ee; color:#003A88; font-weight:bold;"><?php echo $sale['product_profit_margin_total']; ?></td>
		  <td class="right" style="background-color:#c4d9ee; color:#003A88; font-weight:bold;"><?php echo $sale['product_profit_markup_total']; ?></td>
		  <?php } else { ?>
		  <td class="right" style="background-color:#F99; color:#003A88; font-weight:bold;"><?php echo $sale['product_profit_total']; ?></td>
		  <td class="right" style="background-color:#F99; color:#003A88; font-weight:bold;"><?php echo $sale['product_profit_margin_total']; ?></td>
		  <td class="right" style="background-color:#F99; color:#003A88; font-weight:bold;"><?php echo $sale['product_profit_markup_total']; ?></td>
		  <?php } ?>
        </tr>			
        <?php } else { ?>
        <tr>
          <td class="center" colspan="12"><?php echo $text_no_results; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
</div>		
		<div class="row">
		<div class="col-sm-6 text-left" id="pagination_sale"><?php echo $pagination_sale; ?></div>
        <div class="col-sm-6 text-right" id="pagination_sale_count"><?php echo $results_sale; ?></div>
		</div>	
</div>
<script id="saleTemplate" type="text/x-jquery-tmpl">
        <tr>
          <td class="left">{{html product_order_id}}</td>
		  <td class="left">${product_date_added}</td>
          <td class="left">${product_name} 
		  {{if product_option}}
          <table cellpadding="0" cellspacing="0" border="0" style="border:none;">
          <tr>
		  <td nowrap="nowrap" style="font-size:11px; border:0;">{{html product_option}}<span style="font-size:1px; color:#FFF;">${product_order_product_id}</span></td>
          </tr>
          </table>
		  {{/if}}
		  </td>
          <td class="right">${product_sold}</td>
		  <td class="right">${product_total_excl_vat}</td>
		  <td class="right">${product_tax}</td>
		  <td class="right">${product_total_incl_vat}</td>
		  <td class="right" style="background-color:#DCFFB9;">${product_revenue}</td>
		  <td class="right" style="background-color:#ffd7d7;">${product_cost}</td>
		  {{if product_profit_raw >= 0}}
		  <td class="right" style="background-color:#c4d9ee; font-weight:bold;">${product_profit}</td>
		  <td class="right" style="background-color:#c4d9ee; font-weight:bold;">${product_profit_margin}</td>
		  <td class="right" style="background-color:#c4d9ee; font-weight:bold;">${product_profit_markup}</td>
		  {{else}}
		  <td class="right" style="background-color:#F99; font-weight:bold;">${product_profit}</td>
		  <td class="right" style="background-color:#F99; font-weight:bold;">${product_profit_margin}</td>
		  <td class="right" style="background-color:#F99; font-weight:bold;">${product_profit_markup}</td>
		  {{/if}}
        </tr>		
</script>
<script id="sale_totalTemplate" type="text/x-jquery-tmpl">
		<tr style="border-top:2px solid #CCC;">
		  <td colspan="3" class="right" style="background-color:#E5E5E5; font-weight:bold;"><?php echo $column_prod_totals; ?></td>
          <td class="right" style="background-color:#E5E5E5; color:#003A88; font-weight:bold;">${product_sold_total}</td>
		  <td class="right" style="background-color:#E5E5E5; color:#003A88; font-weight:bold;">${product_total_excl_vat_total}</td>
		  <td class="right" style="background-color:#E5E5E5; color:#003A88; font-weight:bold;">${product_tax_total}</td>
		  <td class="right" style="background-color:#E5E5E5; color:#003A88; font-weight:bold;">${product_total_incl_vat_total}</td>
		  <td class="right" style="background-color:#DCFFB9; color:#003A88; font-weight:bold;">${product_revenue_total}</td>
		  <td class="right" style="background-color:#ffd7d7; color:#003A88; font-weight:bold;">${product_cost_total}</td>
		  {{if product_profit_raw_total >= 0}}
		  <td class="right" style="background-color:#c4d9ee; color:#003A88; font-weight:bold;">${product_profit_total}</td>
		  <td class="right" style="background-color:#c4d9ee; color:#003A88; font-weight:bold;">${product_profit_margin_total}</td>
		  <td class="right" style="background-color:#c4d9ee; color:#003A88; font-weight:bold;">${product_profit_markup_total}</td>
		  {{else}}
		  <td class="right" style="background-color:#F99; color:#003A88; font-weight:bold;">${product_profit_total}</td>
		  <td class="right" style="background-color:#F99; color:#003A88; font-weight:bold;">${product_profit_margin_total}</td>
		  <td class="right" style="background-color:#F99; color:#003A88; font-weight:bold;">${product_profit_markup_total}</td>
		  {{/if}}
        </tr>		
</script>
<?php } ?>
<?php } ?>
            
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
$('#input-description<?php echo $language['language_id']; ?>').summernote({height: 300});
<?php } ?>
//--></script>
  <script type="text/javascript"><!--
// Manufacturer

function calcDiscount(e){ 
    var price = parseFloat($(e).data("price"));
    var percent = parseFloat($(e).val());
    if(price != 0){
        percent = 1- (percent/100);
        var setPrice = parseFloat(price*percent).toFixed(4);
        $(e).next(".prod_discount_val").val(setPrice);
    }else{
        $(e).next(".prod_discount_val").val(0);
    }

}

function calcDiscountPrice(e){ 
  $(".prod_discount").each(function(){
    var dicount_calculated_price=$(e).val()-($(this).val()/100)*$(e).val();
$(this).next(".prod_discount_val").val(dicount_calculated_price);
});


}
$('input[name=\'manufacturer\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/manufacturer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        json.unshift({
          manufacturer_id: 0,
          name: '<?php echo $text_none; ?>'
        });

        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['manufacturer_id']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'manufacturer\']').val(item['label']);
    $('input[name=\'manufacturer_id\']').val(item['value']);
  }
});

// Category
$('input[name=\'category\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['category_id']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'category\']').val('');

    $('#product-category' + item['value']).remove();

    $('#product-category').append('<div id="product-category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_category[]" value="' + item['value'] + '" /></div>');
  }
});

$('#product-category').delegate('.fa-minus-circle', 'click', function() {
  $(this).parent().remove();
});

// Filter
$('input[name=\'filter\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/filter/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['filter_id']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'filter\']').val('');

    $('#product-filter' + item['value']).remove();

    $('#product-filter').append('<div id="product-filter' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_filter[]" value="' + item['value'] + '" /></div>');
  }
});

$('#product-filter').delegate('.fa-minus-circle', 'click', function() {
  $(this).parent().remove();
});

// Downloads
$('input[name=\'download\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/download/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['download_id']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'download\']').val('');

    $('#product-download' + item['value']).remove();

    $('#product-download').append('<div id="product-download' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_download[]" value="' + item['value'] + '" /></div>');
  }
});

$('#product-download').delegate('.fa-minus-circle', 'click', function() {
  $(this).parent().remove();
});

// Related
$('input[name=\'related\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/product/autocomplete1&token=<?php echo $token; ?>&filter_model=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['product_id']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'related\']').val('');

    $('#product-related' + item['value']).remove();

    $('#product-related').append('<div id="product-related' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_related[]" value="' + item['value'] + '" /></div>');
  }
});

$('#product-related').delegate('.fa-minus-circle', 'click', function() {
  $(this).parent().remove();
});

// Related
$('input[name=\'wwell\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/product/autocomplete1&token=<?php echo $token; ?>&filter_model=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['product_id']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'wwell\']').val('');

    $('#product-wwell' + item['value']).remove();

    $('#product-wwell').append('<div id="product-related' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_wwell[]" value="' + item['value'] + '" /></div>');
  }
});

$('#product-wwell').delegate('.fa-minus-circle', 'click', function() {
  $(this).parent().remove();
});
//--></script>
  <script type="text/javascript"><!--
var attribute_row = <?php echo $attribute_row; ?>;
var estimatedays_row = <?php echo $estimatedays_row; ?>;
var article_row = <?php echo $article_row; ?>;
var qrcode_row = <?php echo $qrcode_row; ?>;
var qrcode_cols= "<?php echo $qrcode_cols; ?>";
var video_row = <?php echo $video_row; ?>;

function addAttribute() {
    html  = '<tr id="attribute-row' + attribute_row + '">';
  html += '  <td class="text-left" style="width: 20%;"><input type="text" name="product_attribute[' + attribute_row + '][name]" value="" placeholder="<?php echo $entry_attribute; ?>" class="form-control" /><input type="hidden" name="product_attribute[' + attribute_row + '][attribute_id]" value="" /></td>';
  html += '  <td class="text-left">';
  <?php foreach ($languages as $language) { ?>
  html += '<div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span><textarea name="product_attribute[' + attribute_row + '][product_attribute_description][<?php echo $language['language_id']; ?>][text]" rows="5" placeholder="<?php echo $entry_text; ?>" class="form-control"></textarea></div>';
    <?php } ?>
  html += '  </td>';
  html += '  <td class="text-left"><button type="button" onclick="$(\'#attribute-row' + attribute_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
    html += '</tr>';

  $('#attribute tbody').append(html);

  attributeautocomplete(attribute_row);

  attribute_row++;
}


function updateEmbedCheck(row){
    var article = $('input[name=\'product_article[' + row + '][name]\']').val();
    var article_id = $('input[name=\'product_article[' + row + '][article_id]\']').val();
    if(article_id=='0'&&article.search( 'gempacked.com' )<0){
        alert('cannot embed outer links');
        event.preventDefault();
        event.stopPropagation();
        return false;
    }
  var checkBox = document.getElementById('product_article['+row+'][embed_in_description]');
  if (checkBox.checked == true){
   $('input[name=\'product_article[' + row + '][embed_in_description]\']').val('1');
  } else {
     $('input[name$=\'[embed_in_description]\']').val('0');
  }
}

function addArticle() {
    html  = '<tr id="article-row' + article_row + '">';
  html += '  <td class="text-left"><input type="text" name="product_article[' + article_row + '][name]" value="" placeholder="Enter article/video from blog" class="form-control" /><input type="hidden" name="product_article[' + article_row + '][article_id]" value="" /></td>';

html += '  <td class="text-left"><input type="text" name="product_article[' + article_row + '][display_text]" value="" placeholder="Enter Display Text" class="form-control" /></td>';                    
html += '  <td class="text-left"><input type="checkbox" id="product_article[' + article_row + '][embed_in_description]" value="" placeholder="Enter article/video from blog" class="form-control" onclick="updateEmbedCheck('+article_row+')" /></td>';
                    html += '  <td class="text-left"><input type="number" name="product_article[' + article_row + '][sort_order]" value="" placeholder="Sort Order" class="form-control" /></td>';
                    html += ' <input type="hidden" name="product_article[' + article_row + '][embed_in_description]" value="" />';
                      
  
  html += '  <td class="text-left"><button type="button" onclick="$(\'#article-row' + article_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
    html += '</tr>';

  $('#article tbody').append(html);

  articleautocomplete(article_row);

  article_row++;
}

function addVideo() {
    html  = '<tr id="video-row' + video_row + '">';
  html += '  <td class="text-left"><input type="text" name="p_v_link[' + video_row + ']" value="" placeholder="Enter video Link" class="form-control" /></td>';
  
  html += '  <td class="text-left"><button type="button" onclick="$(\'#video-row' + video_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
    html += '</tr>';

  $('#video tbody').append(html);

  video_row++;
}

function addQRFormat() {
    qr_cols = qrcode_cols.split(',');
    console.log(qr_cols);
    html  = '<tr id="qrcode-row' + qrcode_row + '">';
    html += '<td><select id="qrcode_val'+qrcode_row+'" onChange="updateQRFormat('+qrcode_row+')">';
    for (i = 0 ;i < qr_cols.length ; i++) {
      html += '<option value="'+qr_cols[i]+'">' + qr_cols[i] + '</option>';
    }
    html += '</select></td>';
    //html += '  <td class="text-left"><input type="text" name="product_qrcode[' + qrcode_row + '][name]" value="" placeholder="Enter QR Format" class="form-control" /><input type="hidden" name="product_qrcode[' + qrcode_row + '][qrcode_id]" value="" /></td>';
  
    html += '  <td class="text-left"><button type="button" onclick="$(\'#qrcode-row' + qrcode_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
    html += '</tr>';
  $('#qrcode tbody').append(html);

  //articleautocomplete(article_row);

  qrcode_row++;
}

function updateQRFormat(id) {
  code = '{' + $('#qrcode_val'+id).val() + '}';
  m_code = $("#p_qr_code").val();
  n_code = m_code.split(":::");
  m_code = [];
  for (i = 0 ; i<n_code.length; i++) {
    if (n_code[i] === code){
      return;
    }
  }

  n_code.push(code);
  n_code = n_code.join(":::",n_code);
  $("#p_qr_code").val(n_code);


}

function removeQRFormat(id) {
  code = '{' + $('#qrcode_val'+id).val() + '}';
  m_code = $("#p_qr_code").val();
  n_code = m_code.split(":::");
  m_code = [];
  for (i = 0 ; i<n_code.length; i++) {
    if (n_code[i] === code){
      continue;
    }else{
      m_code.push(n_code[i]);
    }
  }
  m_code = m_code.join(":::",m_code);
  $("#p_qr_code").val(m_code);
  $('#qrcode-row'+id).remove();
  

}

function addEstimatedays() {
    html  = '<tr id="estimatedays-row' + estimatedays_row + '">';
  html += '  <td class="text-left" style="width: 20%;"><input type="text" name="estimatedays[' + estimatedays_row + '][days]" value="" placeholder="Estimate Days" class="form-control" /></td>';
  html += '  <td class="text-left">';
  
  html += '<div class="input-group"><textarea name="estimatedays[' + estimatedays_row + '][estimatedays_description]" rows="5" placeholder="<?php echo $entry_text; ?>" class="form-control"></textarea></div>';
  
  html += '  </td>';
  html += '  <td class="text-left"><button type="button" onclick="$(\'#estimatedays-row' + estimatedays_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
    html += '</tr>';

  $('#estimatedays tbody').append(html);

  //attributeautocomplete(estimatedays_row);

  estimatedays_row++;
}

function attributeautocomplete(attribute_row) {
  $('input[name=\'product_attribute[' + attribute_row + '][name]\']').autocomplete({
    'source': function(request, response) {
      $.ajax({
        url: 'index.php?route=catalog/attribute/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
        dataType: 'json',
        success: function(json) {
          response($.map(json, function(item) {
            return {
              category: item.attribute_group,
              label: item.name,
              value: item.attribute_id
            }
          }));
        }
      });
    },
    'select': function(item) {
      $('input[name=\'product_attribute[' + attribute_row + '][name]\']').val(item['label']);
      $('input[name=\'product_attribute[' + attribute_row + '][attribute_id]\']').val(item['value']);
    }
  });
}

$('#attribute tbody tr').each(function(index, element) {
  attributeautocomplete(index);
});

function articleautocomplete(article_row) {
  $('input[name=\'product_article[' + article_row + '][name]\']').autocomplete({
    'source': function(request, response) {
      $.ajax({
        url: 'index.php?route=catalog/information/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
        dataType: 'json',
        success: function(json) {
          response($.map(json, function(item) {
            return {
              label: item.name,
              value: item.article_id
            }
          }));
        }
      });
    },
    'select': function(item) {
      $('input[name=\'product_article[' + article_row + '][name]\']').val(item['label']);
      $('input[name=\'product_article[' + article_row + '][article_id]\']').val(item['value']);
    }
  });
}
//--></script>
  <script type="text/javascript"><!--
var option_row = <?php echo $option_row; ?>;

$('input[name=\'option\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/option/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        response($.map(json, function(item) {
          return {
            category: item['category'],
            label: item['name'],
            value: item['option_id'],
            type: item['type'],
            option_value: item['option_value']
          }
        }));
      }
    });
  },
  'select': function(item) {
    html  = '<div class="tab-pane" id="tab-option' + option_row + '">';
    html += ' <input type="hidden" name="product_option[' + option_row + '][product_option_id]" value="" />';
    html += ' <input type="hidden" name="product_option[' + option_row + '][name]" value="' + item['label'] + '" />';
    html += ' <input type="hidden" name="product_option[' + option_row + '][option_id]" value="' + item['value'] + '" />';
    html += ' <input type="hidden" name="product_option[' + option_row + '][type]" value="' + item['type'] + '" />';

    html += ' <div class="form-group">';
    html += '   <label class="col-sm-2 control-label" for="input-required' + option_row + '"><?php echo $entry_required; ?></label>';
    html += '   <div class="col-sm-10"><select name="product_option[' + option_row + '][required]" id="input-required' + option_row + '" class="form-control">';
    html += '       <option value="1"><?php echo $text_yes; ?></option>';
    html += '       <option value="0"><?php echo $text_no; ?></option>';
    html += '   </select></div>';
    html += ' </div>';

    if (item['type'] == 'text') {
      html += ' <div class="form-group">';
      html += '   <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
      html += '   <div class="col-sm-10"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" id="input-value' + option_row + '" class="form-control" /></div>';
      html += ' </div>';
    }

    if (item['type'] == 'textarea') {
      html += ' <div class="form-group">';
      html += '   <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
      html += '   <div class="col-sm-10"><textarea name="product_option[' + option_row + '][value]" rows="5" placeholder="<?php echo $entry_option_value; ?>" id="input-value' + option_row + '" class="form-control"></textarea></div>';
      html += ' </div>';
    }

    if (item['type'] == 'file') {
      html += ' <div class="form-group" style="display: none;">';
      html += '   <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
      html += '   <div class="col-sm-10"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" id="input-value' + option_row + '" class="form-control" /></div>';
      html += ' </div>';
    }

    if (item['type'] == 'date') {
      html += ' <div class="form-group">';
      html += '   <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
      html += '   <div class="col-sm-3"><div class="input-group date"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" data-date-format="YYYY-MM-DD" id="input-value' + option_row + '" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></div>';
      html += ' </div>';
    }

    if (item['type'] == 'time') {
      html += ' <div class="form-group">';
      html += '   <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
      html += '   <div class="col-sm-10"><div class="input-group time"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" data-date-format="HH:mm" id="input-value' + option_row + '" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></div>';
      html += ' </div>';
    }

    if (item['type'] == 'datetime') {
      html += ' <div class="form-group">';
      html += '   <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
      html += '   <div class="col-sm-10"><div class="input-group datetime"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-value' + option_row + '" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></div>';
      html += ' </div>';
    }

    if (item['type'] == 'select' || item['type'] == 'radio' || item['type'] == 'checkbox' || item['type'] == 'image') {
      html += '<div class="table-responsive">';
      html += '  <table id="option-value' + option_row + '" class="table table-striped table-bordered table-hover">';
      html += '    <thead>';
      html += '      <tr>';
      html += '        <td class="text-left"><?php echo $entry_option_value; ?></td>';

<?php if ($prm_access_permission && $laccess) { ?>		
			html += '        <td class="text-right"><?php echo $entry_option_sku; ?></td>';
<?php } ?>	
			
      html += '        <td class="text-right"><?php echo $entry_quantity; ?></td>';
      html += '        <td class="text-left"><?php echo $entry_subtract; ?></td>';
      html += '        <td class="text-right"><?php echo $entry_price; ?></td>';

<?php if ($prm_access_permission && $laccess) { ?>			
			html += '        <td class="text-right"><?php echo $entry_option_cost; ?></td>';
<?php } ?>				
            
      html += '        <td class="text-right"><?php echo $entry_option_points; ?></td>';
      html += '        <td class="text-right"><?php echo $entry_weight; ?></td>';
      html += '        <td></td>';
      html += '      </tr>';
      html += '    </thead>';
      html += '    <tbody>';
      html += '    </tbody>';
      html += '    <tfoot>';
      html += '      <tr>';
      
<?php if ($prm_access_permission && $laccess) { ?>
			html += '        <td colspan="8"></td>';
<?php } else { ?>
			html += '        <td colspan="6"></td>';
<?php } ?>
            
      html += '        <td class="text-left"><button type="button" onclick="addOptionValue(' + option_row + ');" data-toggle="tooltip" title="<?php echo $button_option_value_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>';
      html += '      </tr>';
      html += '    </tfoot>';
      html += '  </table>';
      html += '</div>';

            html += '  <select id="option-values' + option_row + '" style="display: none;">';

            for (i = 0; i < item['option_value'].length; i++) {
        html += '  <option value="' + item['option_value'][i]['option_value_id'] + '">' + item['option_value'][i]['name'] + '</option>';
            }

            html += '  </select>';
      html += '</div>';
    }

    $('#tab-option .tab-content').append(html);

    
<?php if ($prm_access_permission && $laccess) { ?>
		$('#option > li:last-child').before('<li><a href="#tab-option' + option_row + '" data-toggle="tab"><i class="fa fa-minus-circle" onclick="$(\'a[href=\\\'#tab-option' + option_row + '\\\']\').parent().remove(); $(\'#tab-option' + option_row + '\').remove(); optRemoveGroup(this.form); restockquantity_opt(this.form); quantity_opt(this.form); totalcost_opt(this.form); $(\'#option a:first\').tab(\'show\');"></i> ' + item['label'] + '</li>');
<?php } else { ?>
		$('#option > li:last-child').before('<li><a href="#tab-option' + option_row + '" data-toggle="tab"><i class="fa fa-minus-circle" onclick="$(\'a[href=\\\'#tab-option' + option_row + '\\\']\').parent().remove(); $(\'#tab-option' + option_row + '\').remove(); $(\'#option a:first\').tab(\'show\')"></i> ' + item['label'] + '</li>');
<?php } ?>		
            

    $('#option a[href=\'#tab-option' + option_row + '\']').tab('show');

    $('.date').datetimepicker({
      pickTime: false
    });

    $('.time').datetimepicker({
      pickDate: false
    });

    $('.datetime').datetimepicker({
      pickDate: true,
      pickTime: true
    });

    option_row++;
  }
});
//--></script>
  <script type="text/javascript"><!--
var option_value_row = <?php echo $option_value_row; ?>;

function addOptionValue(option_row) {
  html  = '<tr id="option-value-row' + option_value_row + '">';
  html += '  <td class="text-left"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][option_value_id]" class="form-control">';
  html += $('#option-values' + option_row).html();
  html += '  </select><input type="hidden" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][product_option_value_id]" value="" /></td>';
  
<?php if ($prm_access_permission && $laccess) { ?>
	html += '  <td class="text-right"><input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][sku]" value="" placeholder="<?php echo $entry_option_sku; ?>" class="form-control" /></td>';
	html += '  <td class="text-right"><input type="text" onKeyUp="if(!this.value) this.value=0; addOption(this.form); restockquantity_opt(this.form); quantity_opt(this.form); totalcost_opt(this.form);" onClick="this.setSelectionRange(0, this.value.length)" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][quantity]" value="" placeholder="<?php echo $entry_quantity; ?>" class="form-control addOption" style="border:thin solid #ebd685;" />';
	html += '  <div style="text-align:center; padding-top:3px;"><?php echo $entry_quantity; ?></div>';	
	html += '  </td>';	
<?php } else { ?>
	html += '  <td class="text-right"><input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][quantity]" value="" placeholder="<?php echo $entry_quantity; ?>" class="form-control" /></td>';
<?php } ?>		
            
  html += '  <td class="text-left"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][subtract]" class="form-control">';
  html += '    <option value="1"><?php echo $text_yes; ?></option>';
  html += '    <option value="0"><?php echo $text_no; ?></option>';
  html += '  </select></td>';
  
<?php if ($prm_access_permission && $laccess) { ?>
	html += '  <td class="text-right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price_prefix]" class="form-control" style="border:thin solid #b5e08b;">';
	html += '    <option value="+">+</option>';
	html += '    <option value="-">-</option>';
	html += '  </select>';
	html += '  <input type="text" onKeyUp="if(!this.value) this.value=0;" onClick="this.setSelectionRange(0, this.value.length)" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price]" value="" placeholder="<?php echo $entry_price; ?>" class="form-control option_price_new" style="border:thin solid #b5e08b;" />';
	html += '  <div style="text-align:center; padding-top:3px;"><?php echo $text_option_price; ?></div>';
	<?php if ($adv_price_tax) { ?>
	html += '  <div style="padding-top:8px;"><input type="text" onKeyUp="if(!this.value) this.value=0;" onClick="this.setSelectionRange(0, this.value.length)" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price_tax]" value="" placeholder="<?php echo $entry_price_tax; ?>" class="form-control option_price_tax_new" style="border:thin solid #b5e08b;" /></div>';
	html += '  <div style="text-align:center; padding-top:3px;"><?php echo $entry_option_price_tax; ?></div>';
	<?php } ?>
	html += '  </td>';
<?php } else { ?>
	html += '  <td class="text-right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price_prefix]" class="form-control">';
	html += '    <option value="+">+</option>';
	html += '    <option value="-">-</option>';
	html += '  </select>';
	html += '  <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price]" value="" placeholder="<?php echo $entry_price; ?>" class="form-control" /></td>';
<?php } ?>		
            

<?php if ($prm_access_permission && $laccess) { ?>	
	html += '  <td class="text-right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][cost_prefix]" class="form-control" style="border:thin solid #ed9999;">';
	html += '    <option value="+">+</option>';
	html += '    <option value="-">-</option>';
	html += '  </select>';
	html += '  <input type="hidden" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][option_restock_quantity]" value="" /><input type="hidden" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][costing_method]" value="" /><input type="hidden" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][cost_amount]" value="" /><input type="text" onKeyUp="if(!this.value) this.value=0;" onClick="this.setSelectionRange(0, this.value.length)" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][cost]" value="" placeholder="<?php echo $entry_cost; ?>" class="form-control" style="border:thin solid #ed9999;" />';
	html += '  <div style="text-align:center; padding-top:3px;"><?php echo $text_cost_option_cost; ?></div>';		
	html += '  </td>';	
<?php } else { ?>
	html += '    <input type="hidden" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][sku]" value="" />';
	html += '    <input type="hidden" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][cost_prefix]" value="" />';
	html += '    <input type="hidden" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][costing_method]" value="" />';
	html += '    <input type="hidden" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][cost_amount]" value="" />';		
	html += '    <input type="hidden" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][cost]" value="" />';
	html += '    <input type="hidden" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][option_restock_quantity]" value="" />';
<?php } ?>		
            
  html += '  <td class="text-right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][points_prefix]" class="form-control">';
  html += '    <option value="+">+</option>';
  html += '    <option value="-">-</option>';
  html += '  </select>';
  html += '  <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][points]" value="" placeholder="<?php echo $entry_points; ?>" class="form-control" /></td>';
  html += '  <td class="text-right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][weight_prefix]" class="form-control">';
  html += '    <option value="+">+</option>';
  html += '    <option value="-">-</option>';
  html += '  </select>';
  html += '  <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][weight]" value="" placeholder="<?php echo $entry_weight; ?>" class="form-control" /></td>';
  
<?php if ($prm_access_permission && $laccess) { ?>
	html += '  <td class="text-left"><button type="button" onclick="$(this).tooltip(\'destroy\');$(\'#option-value-row' + option_value_row + '\').remove(); optRemove(this.form); restockquantity_opt(this.form); quantity_opt(this.form); totalcost_opt(this.form);" data-toggle="tooltip" rel="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
<?php } else { ?>
	html += '  <td class="text-left"><button type="button" onclick="$(this).tooltip(\'destroy\');$(\'#option-value-row' + option_value_row + '\').remove();" data-toggle="tooltip" rel="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
<?php } ?>		
            
  html += '</tr>';

html += '<script type="text/javascript">$(document).ready(function(){$("#option-value-row' + option_value_row + '").data("row", ' + option_value_row + ');});</script>';
  $('#option-value' + option_row + ' tbody').append(html);
        $('[rel=tooltip]').tooltip();

  option_value_row++;
}
//--></script>
  <script type="text/javascript"><!--
var discount_row = <?php echo $discount_row; ?>;

function addDiscount() {
  html  = '<tr id="discount-row' + discount_row + '">';
    html += '  <td class="text-left"><select name="product_discount[' + discount_row + '][customer_group_id]" class="form-control">';
    <?php foreach ($customer_groups as $customer_group) { ?>
    html += '    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo addslashes($customer_group['name']); ?></option>';
    <?php } ?>
    html += '  </select></td>';
    html += '  <td class="text-right"><input type="text" name="product_discount[' + discount_row + '][quantity]" value="" placeholder="<?php echo $entry_quantity; ?>" class="form-control" /></td>';
    html += '  <td class="text-right"><input type="text" name="product_discount[' + discount_row + '][priority]" value="" placeholder="<?php echo $entry_priority; ?>" class="form-control" /></td>';
  html += '  <td class="text-right"><label style="padding-right:4px;">Discount(%)</label><input style="" type="text" onblur="calcDiscount(this);" data-price="<?php echo $price; ?>" class="prod_discount form-control" value=""/><input type="text" name="product_discount[' + discount_row + '][price]" value="" placeholder="<?php echo $entry_price; ?>"class="prod_discount_val form-control" /></td>';
    html += '  <td class="text-left" style="width: 20%;"><div class="input-group date"><input type="text" name="product_discount[' + discount_row + '][date_start]" value="" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
  html += '  <td class="text-left" style="width: 20%;"><div class="input-group date"><input type="text" name="product_discount[' + discount_row + '][date_end]" value="" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
  html += '  <td class="text-left"><button type="button" onclick="$(\'#discount-row' + discount_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
  html += '</tr>';

  $('#discount tbody').append(html);

  $('.date').datetimepicker({
    pickTime: false
  });

  discount_row++;
}
//--></script>
  <script type="text/javascript"><!--
var special_row = <?php echo $special_row; ?>;

function addSpecial() {
  html  = '<tr id="special-row' + special_row + '">';
    html += '  <td class="text-left"><select name="product_special[' + special_row + '][customer_group_id]" class="form-control">';
    <?php foreach ($customer_groups as $customer_group) { ?>
    html += '      <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo addslashes($customer_group['name']); ?></option>';
    <?php } ?>
    html += '  </select></td>';
    html += '  <td class="text-right"><input type="text" name="product_special[' + special_row + '][priority]" value="" placeholder="<?php echo $entry_priority; ?>" class="form-control" /></td>';
  html += '  <td class="text-right"><input type="text" name="product_special[' + special_row + '][price]" value="" placeholder="<?php echo $entry_price; ?>" class="form-control" /></td>';
    html += '  <td class="text-left" style="width: 20%;"><div class="input-group date"><input type="text" name="product_special[' + special_row + '][date_start]" value="" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
  html += '  <td class="text-left" style="width: 20%;"><div class="input-group date"><input type="text" name="product_special[' + special_row + '][date_end]" value="" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
  html += '  <td class="text-left"><button type="button" onclick="$(\'#special-row' + special_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
  html += '</tr>';

  $('#special tbody').append(html);

  $('.date').datetimepicker({
    pickTime: false
  });

  special_row++;
}
//--></script>
  <script type="text/javascript"><!--
var image_row = <?php echo $image_row; ?>;

function addImage() {
  html  = '<tr id="image-row' + image_row + '">';
  html += '  <td class="text-left"><a href="" id="thumb-image' + image_row + '"data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /><input type="hidden" name="product_image[' + image_row + '][image]" value="" id="input-image' + image_row + '" /></td>';
  html += '  <td class="text-right"><input type="text" name="product_image[' + image_row + '][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>';
  html += '  <td class="text-left"><button type="button" onclick="$(\'#image-row' + image_row  + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
  html += '</tr>';

  $('#images tbody').append(html);

  image_row++;
}
//--></script>
  <script type="text/javascript"><!--
var recurring_row = <?php echo $recurring_row; ?>;

function addRecurring() {
  recurring_row++;

  html  = '';
  html += '<tr id="recurring-row' + recurring_row + '">';
  html += '  <td class="left">';
  html += '    <select name="product_recurring[' + recurring_row + '][recurring_id]" class="form-control">>';
  <?php foreach ($recurrings as $recurring) { ?>
  html += '      <option value="<?php echo $recurring['recurring_id']; ?>"><?php echo $recurring['name']; ?></option>';
  <?php } ?>
  html += '    </select>';
  html += '  </td>';
  html += '  <td class="left">';
  html += '    <select name="product_recurring[' + recurring_row + '][customer_group_id]" class="form-control">>';
  <?php foreach ($customer_groups as $customer_group) { ?>
  html += '      <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>';
  <?php } ?>
  html += '    <select>';
  html += '  </td>';
  html += '  <td class="left">';
  html += '    <a onclick="$(\'#recurring-row' + recurring_row + '\').remove()" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>';
  html += '  </td>';
  html += '</tr>';

  $('#tab-recurring table tbody').append(html);
}
//--></script>
  <script type="text/javascript"><!--
$('.date').datetimepicker({
  pickTime: false
});

$('.time').datetimepicker({
  pickDate: false
});

$('.datetime').datetimepicker({
  pickDate: true,
  pickTime: true
});
//--></script>
  <script type="text/javascript"><!--
$('#language a:first').tab('show');
$('#option a:first').tab('show');
//--></script></div>

				<script type="text/javascript">
				$(document).ready(function(){
			        $(".alert-success").show(0).delay(1000).fadeOut(2000)
				});
								
				$name = $('input[name="product_description[<?php echo $language['language_id']; ?>][name]"]'); 
				$product_name = $('.product_name');


				if ($name.val() != '') {
					$product_name.html(' - ' + $name.val()); 
				}

				$name.keyup(function() {
					if ($(this).val() != '') {
						$('.product_name').html(' - ' + $(this).val()); 
					} else {
						$('.product_name').html('');
					}
				});
				</script> 
			
			
<?php if ($product_id) { ?>	
<?php if ($prm_access_permission && $laccess) { ?>
<script type="text/javascript">
function filter_history() {
	url = 'index.php?route=catalog/product/filter_history&token=<?php echo $token; ?>&product_id=<?php echo $product_ids; ?>';

	url += '&page_history=' + $('#page_history').val();

	if ($('#sort_history').val()) {
		url += '&sort_history=' + $('#sort_history').val();
	}
	if ($('#order_history').val()) {
		url += '&order_history=' + $('#order_history').val();
	}
		
	var filter_history_date_start = $('input[name=\'filter_history_date_start\']').val();
	
	if (filter_history_date_start) {
		url += '&filter_history_date_start=' + encodeURIComponent(filter_history_date_start);
	}

	var filter_history_date_end = $('input[name=\'filter_history_date_end\']').val();
	
	if (filter_history_date_end) {
		url += '&filter_history_date_end=' + encodeURIComponent(filter_history_date_end);
	}
		
	var filter_history_range = $('select[name=\'filter_history_range\']').val();
	
	if (filter_history_range) {
		url += '&filter_history_range=' + encodeURIComponent(filter_history_range);
	}

	var filter_history_option = $('select[name=\'filter_history_option\']').val();
	
	if (filter_history_option) {
		url += '&filter_history_option=' + encodeURIComponent(filter_history_option);
	}

	var filter_history_supplier = $('select[name=\'filter_history_supplier\']').val();
	
	if (filter_history_supplier) {
		url += '&filter_history_supplier=' + encodeURIComponent(filter_history_supplier);
	}
			
	$.ajax({
		url: url,
		dataType: 'json',
    	beforeSend: function(){$( ".hloading" ).show();},
		complete: function(){$( ".hloading" ).hide();},
		cache: false,
		success: function(json) {
				  $('table.list_main_history tr:gt(0)').empty();
				  $("#historyTemplate").tmpl(json.histories).appendTo("table.list_main_history");
				  if (document.getElementById('main_product').selected && document.getElementById('chart_all_time').selected) {
				  	$('#tab_chart_history').slideDown('fast');
				  } else {
				  	$('#tab_chart_history').slideUp('fast');
				  }
				  $('#pagination_history_count').html(json.results_history);
				  $('#pagination_history').html(json.pagination_history);
				  $('#page_history').val(1);				  
			  }
	});
}

function filter_sale() {
	url = 'index.php?route=catalog/product/filter_sale&token=<?php echo $token; ?>&product_id=<?php echo $product_ids; ?>';

	url += '&page_sale=' + $('#page_sale').val();

	if ($('#sort_sale').val()) {
		url += '&sort_sale=' + $('#sort_sale').val();
	}
	if ($('#order_sale').val()) {
		url += '&order_sale=' + $('#order_sale').val();
	}
			
	var filter_sale_date_start = $('input[name=\'filter_sale_date_start\']').val();
	
	if (filter_sale_date_start) {
		url += '&filter_sale_date_start=' + encodeURIComponent(filter_sale_date_start);
	}

	var filter_sale_date_end = $('input[name=\'filter_sale_date_end\']').val();
	
	if (filter_sale_date_end) {
		url += '&filter_sale_date_end=' + encodeURIComponent(filter_sale_date_end);
	}
		
	var filter_sale_range = $('select[name=\'filter_sale_range\']').val();
	
	if (filter_sale_range) {
		url += '&filter_sale_range=' + encodeURIComponent(filter_sale_range);
	}

	var sale_order_statuses = [];
	$('#sale_order_status option:selected').each(function() {
		sale_order_statuses.push($(this).val());
	});
	
	var filter_sale_order_status = sale_order_statuses.join('_');
	
	if (filter_sale_order_status) {
		url += '&filter_sale_order_status=' + encodeURIComponent(filter_sale_order_status);
	}

	var sale_options = [];
	$('#sale_option option:selected').each(function() {
		sale_options.push($(this).val());
	});
	
	var filter_sale_option = sale_options.join('_');
	
	if (filter_sale_option) {
		url += '&filter_sale_option=' + encodeURIComponent(filter_sale_option);
	}
		
	$.ajax({
		url: url,
		dataType: 'json',
    	beforeSend: function(){$( ".sloading" ).show();},
		complete: function(){$( ".sloading" ).hide();},
		cache: false,
		success: function(json) {
				  $('table.list_main_sale tr:gt(0)').empty();				  
				  $("#saleTemplate").tmpl(json.sales).appendTo("table.list_main_sale");	
				  $("#sale_totalTemplate").tmpl(json.sales).appendTo("table.list_main_sale");
				  var seen = {};
					$('table.list_main_sale tr').each(function() {
    				var txt = $(this).text();
    				if (seen[txt])
        				$(this).remove();
    				else
        				seen[txt] = true;
				  });
				  $('#pagination_sale_count').html(json.results_sale);
				  $('#pagination_sale').html(json.pagination_sale);
				  $('#page_sale').val(1);				  
			  }
	});	
} 
</script>
<script type="text/javascript">
function gsUVhistory(e, t, v) {
    var n = String(e).split("?");
    var r = "";
    if (n[1]) {
        var i = n[1].split("&");
        for (var s = 0; s <= i.length; s++) {
            if (i[s]) {
                var o = i[s].split("=");
                if (o[0] && o[0] == t) {
                    r = o[1];
                    if (v != undefined) {
                        i[s] = o[0] +'=' + v;
                    }
                    break;
                }
            }
        }
    }
    if (v != undefined) {
        return n[0] +'?'+ i.join('&');
    }
    return r
}

$('#filter_history_range').on("change", function() {
	$('#page_history').val(1);
	filter_history();
});

$('#date-start-history').on("change", function() {
	$('#page_history').val(1);
	filter_history();
});

$('#date-end-history').on("change", function() {
	$('#page_history').val(1);
	filter_history();
});

$('#filter_history_option').on("change", function() {
	$('#page_history').val(1);
	filter_history();
});

$('#filter_history_supplier').on("change", function() {
	$('#page_history').val(1);
	filter_history();
});

$('#pagination_history').delegate('.pagination a', 'click', function() {
	var page_history = gsUVhistory($(this).prop('href'), 'page_history');
	$('#page_history').val(page_history);
	filter_history();
	return false;
});

$('#head_history a').on("click", function() {
	var sort_history = gsUVhistory($(this).prop('href'), 'sort_history');
	$('#sort_history').val(sort_history);
	var order_history = gsUVhistory($(this).prop('href'), 'order_history');
	$('#order_history').val(order_history);
	$(this).prop('href', gsUVhistory($(this).prop('href'), 'order_history', order_history=='DESC'?'ASC':'DESC'));
	$('#head_history a').removeAttr('class');
	this.className = order_history.toLowerCase();
	filter_history();
	return false;
});


function gsUVsale(e, t, v) {
    var n = String(e).split("?");
    var r = "";
    if (n[1]) {
        var i = n[1].split("&");
        for (var s = 0; s <= i.length; s++) {
            if (i[s]) {
                var o = i[s].split("=");
                if (o[0] && o[0] == t) {
                    r = o[1];
                    if (v != undefined) {
                        i[s] = o[0] +'=' + v;
                    }
                    break;
                }
            }
        }
    }
    if (v != undefined) {
        return n[0] +'?'+ i.join('&');
    }
    return r
}

$('#filter_sale_range').on("change", function() {
	filter_sale();
});

$('#date-start-sale').on("change", function() {
	filter_sale();
});

$('#date-end-sale').on("change", function() {
	filter_sale();
});

$('#sale_order_status').on("change", function() {
	filter_sale();
});

$('#sale_option').on("change", function() {
	filter_sale();
});

$('#pagination_sale').delegate('.pagination a', 'click', function() {
	var page_sale = gsUVsale($(this).prop('href'), 'page_sale');
	$('#page_sale').val(page_sale);
	filter_sale();
	return false;
});

$('#head_sale a').on("click", function() {
	var sort_sale = gsUVsale($(this).prop('href'), 'sort_sale');
	$('#sort_sale').val(sort_sale);
	var order_sale = gsUVsale($(this).prop('href'), 'order_sale');
	$('#order_sale').val(order_sale);
	$(this).prop('href', gsUVsale($(this).prop('href'), 'order_sale', order_sale=='DESC'?'ASC':'DESC'));
	$('#head_sale a').removeAttr('class');
	this.className = order_sale.toLowerCase();
	filter_sale();
	return false;
});

$('.select').selectpicker();
</script>
<script type="text/javascript">
$(document).ready(function() {	
	$('#sale_order_status').multiselect({
		checkboxName: 'sale_order_statuses[]',
		includeSelectAllOption: true,
		enableFiltering: true,
		selectAllText: '<?php echo $text_all; ?>',
		allSelectedText: '<?php echo $text_selected; ?>',
		nonSelectedText: '<?php echo $text_all_sstatus; ?>',
		numberDisplayed: 0,
		disableIfEmpty: true,
		maxHeight: 300
	});
	$('#sale_option').multiselect({
		checkboxName: 'sale_options[]',
		includeSelectAllOption: true,
		enableFiltering: true,
		selectAllText: '<?php echo $text_all; ?>',
		allSelectedText: '<?php echo $text_selected; ?>',
		nonSelectedText: '<?php echo $text_all_soption; ?>',
		numberDisplayed: 0,
		disableIfEmpty: true,
		maxHeight: 300
	});	
});
</script> 
<script type="text/javascript">
$('#date-start-history').datetimepicker({
	pickTime: false
});
$('#date-end-history').datetimepicker({
	pickTime: false
});

$('#date-start-sale').datetimepicker({
	pickTime: false
});
$('#date-end-sale').datetimepicker({
	pickTime: false
});
</script>
<script type="text/javascript">
	function history_download() {
		var url = 'index.php?route=catalog/product/download_history&token=<?php echo $token; ?>&product_id=<?php echo $product_ids; ?>';
		location = url;
	}
	
	function history_delete() {
		<?php if ($modify_permission) { ?>
		if(confirm("<?php echo $text_confirm_delete_history;?>") == false) return false;
		var durl = 'index.php?route=catalog/product/delete_history&token=<?php echo $token; ?>&product_id=<?php echo $product_ids; ?>';
		location = durl;
		<?php } else { ?>
		alert("You do not have permission to delete Stock History!");
		<?php } ?>			
	}
</script>
<?php } ?>
<?php } ?>
            
<?php echo $footer; ?>
