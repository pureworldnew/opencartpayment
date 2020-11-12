<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <link type="text/css" href="view/stylesheet/product_labels/stylesheet.css" rel="stylesheet" media="screen" />
  <script type="text/javascript" src="view/javascript/product_labels/pdfobject.js"></script>
  <script type="text/javascript" src="view/javascript/product_labels/product_lebel_edit.js"></script>
  <div class="container-fluid" id="main-content-area">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-model"><?php echo $entry_model; ?></label>
                <input type="text" name="filter_model" value="<?php echo $filter_model; ?>" placeholder="<?php echo $entry_model; ?>" id="input-model" class="form-control" />
              </div>
			</div>
			
			  <div class="col-sm-6">
				  <div class="form-group">
					<label class="control-label" for="filter_location">Location</label>
					<input type="text" name="filter_location" value="<?php echo $filter_location; ?>" placeholder="Location" id="filter-location" class="form-control" />
				  </div>
			  </div>   
           </div>
           
           <div class="row">
            <div class="col-sm-5">
              <div class="form-group">
                <label class="control-label" for="input-custom">Custom QR Format</label>
                <input type="text" name="filter_custom" value="<?php echo $filter_custom; ?>" placeholder="QR Custom Format" id="input-custom" class="form-control" />
              </div>
			</div>
			
			  <div class="col-sm-5">
				  <div class="form-group">
					<label class="control-label" for="filter_text">Default QR Text</label>
					<input type="text" name="filter_text" value="<?php echo $filter_text; ?>" placeholder="Text for QR (Default will be model)" id="filter-text" class="form-control" />
				  </div>
			  </div> 
			  
			  <div class="col-sm-2">
				  <div class="form-group">
					<label class="control-label" for="filter_cpp">Columns Per Page</label>
					<select class="form-control" name="filter_cpp">
						<option value="1">One</option>
						<option value="2">Two</option>
						<option value="3">Three</option>
					</select>
				  </div>
			  </div>   
           </div>
           
           <div class="row">
			   <div class="col-sm-6">
				    <div class="form-group">			
	  			<label class="control-label" for="input-category"><?php echo $entry_category; ?></label>
	  			<div id="filter_cat_id">
	  			<a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo "Select All"; ?></a> /  
               <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo "Select none"; ?></a>
	  			<div class="well well-sm" style="height: 150px;width:auto; overflow: auto;">
	  			<?php $access = explode(',',$filter_category_id); ?>
                <?php foreach ($categories as $category) { ?>
                <div class="checkbox">
                  <label>
                    <?php if (in_array($category['category_id'],$access)) { ?>
                    <input type="checkbox" name="category[access][]" value="<?php echo $category['category_id']; ?>" checked="checked" />
                    <?php echo $category['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="category[access][]" value="<?php echo $category['category_id']; ?>" />
                    <?php echo $category['name']; ?>
                    <?php } ?>
                  </label>
                </div>
                <?php } ?>
              </div>
              </div>
              
             </div>
			   </div>
			   <div class="col-sm-6">
				   <div class="form-group">	
	  			<label class="control-label" for="input-manufacturer"><?php echo $entry_manufacturer; ?></label>
	  			<div id="filter_man_id">
	  			<a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo "Select All"; ?></a> /  
               <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo "Select none"; ?></a>
	  			<div class="well well-sm" style="height: 150px;width:auto; overflow: auto;">
	  			<?php $modify = explode(',',$filter_manufacturer_id); ?>
                <?php foreach ($manufacturers as $manufacturer) { ?>
                <div class="checkbox">
                  <label>
                    <?php if (in_array($manufacturer['manufacturer_id'], $modify)) { ?>
                    <input type="checkbox" name="manufacturer[modify][]" value="<?php echo $manufacturer['manufacturer_id']; ?>" checked="checked" />
                    <?php echo $manufacturer['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="manufacturer[modify][]" value="<?php echo $manufacturer['manufacturer_id']; ?>" />
                    <?php echo $manufacturer['name']; ?>
                    <?php } ?>
                  </label>
                </div>
                <?php } ?>
              </div>
              
             </div>
			   </div>
           </div>
           </div>

              <button type="button" id="button-gene" class="btn btn-primary pull-right"><i class="fa fa-save"></i> <?php echo $button_generate; ?></button>
              <button type="button" id="button-pl" class="btn btn-success pull-right"><i class="fa fa-save"></i> Print Labels</button>
              <button type="button" id="reset" class="btn btn-primary"><i class="fa fa-save"></i> Reset</button>
            </div>
          </div>
        </div>
        <form action="<?php echo $link_d; ?>" method="post" enctype="multipart/form-data" id="form-product">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  
                </tr>
              </thead>
              <tbody>
                <?php if ($qr_lists) { ?>
                <?php foreach ($qr_lists as $product) { ?>
                <tr pid="<?php echo $product['product_id']?>">
                  
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="13"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
     <!-- Print Label Modal -->
<div class="modal fade" id="printModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Print Product QR Labels</h4>
      </div>
      <div class="modal-body">
        <!--  Label Code -->
        <div class="row">
							<div class="col-sm-7">
								<legend><?php echo $text_template_settings; ?></legend>
							</div>
							<div class="col-sm-5">
								<legend><?php echo $text_preview; ?></legend>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-7">
								<div class="row">
									<div class="form-group form-group-sm">
										<label class="col-sm-4 control-label text-right" for="template"><?php echo $text_select_label; ?></label>
										<div class="col-sm-8">
											<select id="popupprolabel" name="select_label" class="select_label form-control" style="margin-bottom:10px;">
												<option value=""><?php echo $text_option_new_label; ?></option>
												<?php $name_i = 1; ?>
												<?php foreach($labels as $id => $label_name) { ?>
												<?php 	if(empty($label_name)) { ?>
												<?php 		$label_name="Label_".$name_i; ?>
												<?php 		$name_i++; ?>
												<?php 	} ?>
												<option value="<?php echo $id ?>" <?php if($settings['product_labels_default_label'] == $id) echo " selected" ?>><?php echo $label_name; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12" class="form-group form-group-sm">
										<form id="edit_label_form" class="form-horizontal">
										<input type="hidden" name="route" value="module/product_labels/labels">
										<input type="hidden" name="token" value="<?php echo $token ?>">
										<input type="hidden" name="sample" value="1">
										<input type="hidden" name="edit" value="1">
										<input type="hidden" name="labelid" value="">
										<div id="form_elements">
										<?php foreach(array("rect","img","text","barcode") as $element_type) { ?>
											<div class="well" style="padding:5px;"> <div class="row">
												<div class="col-sm-12">
													<legend style="margin-bottom:2px;"><?php echo ${'text_'.$element_type} ?></legend>
												</div>
											</div>
											<div class="col-xs-12">
												<div class="row" id="element_test">
													<div class="col-xs-2 col-lg-2 oc2-pl-label-input-header"><p><?php echo $text_add; ?></p></div>
													<div class="col-xs-2 col-lg-2 oc2-pl-label-input-header <?php //echo ControllerModuleProductLabels::toggle($element_type,0) ?>"><p data-toggle="tooltip" data-original-title="<?php echo $text_tip_font_f; ?>"><?php echo $text_font_f; ?></p></div>
													<div class="col-xs-1 col-lg-1 oc2-pl-label-input-header <?php //echo ControllerModuleProductLabels::toggle($element_type,0) ?>"><p data-toggle="tooltip" data-original-title="<?php echo $text_tip_font_s; ?>"><?php echo $text_font_s; ?></p></div>
													<div class="col-xs-1 col-lg-1 oc2-pl-label-input-header <?php //echo ControllerModuleProductLabels::toggle($element_type,9) ?>"><p data-toggle="tooltip" data-original-title="<?php echo $text_tip_font_a; ?>"><?php echo $text_font_a; ?></p></div>
													<div class="col-xs-3 col-lg-3 oc2-pl-label-input-header <?php //echo ControllerModuleProductLabels::toggle($element_type,1) ?>"><p data-toggle="tooltip" data-original-title="<?php echo $text_tip_text; ?>"><?php echo $text_text; ?></p></div>
													<div class="col-xs-5 col-lg-5 oc2-pl-label-input-header <?php //echo ControllerModuleProductLabels::toggle($element_type,2) ?>"><p data-toggle="tooltip" data-original-title="<?php echo $text_tip_img; ?>"><?php echo $text_img; ?></p></div>
													<div class="col-xs-1 col-lg-1 oc2-pl-label-input-header "><p data-toggle="tooltip" data-original-title="<?php echo $text_tip_xpos; ?>"><?php echo $text_xpos; ?></p></div>
													<div class="col-xs-1 col-lg-1 oc2-pl-label-input-header "><p data-toggle="tooltip" data-original-title="<?php echo $text_tip_ypos; ?>"><?php echo $text_ypos; ?></p></div>
													<div class="col-xs-1 col-lg-1 oc2-pl-label-input-header <?php //echo ControllerModuleProductLabels::toggle($element_type,5) ?>"><p data-toggle="tooltip" data-original-title="<?php echo $text_tip_width; ?>"><?php echo $text_width; ?></p></div>
													<div class="col-xs-1 col-lg-1 oc2-pl-label-input-header <?php //echo ControllerModuleProductLabels::toggle($element_type,6) ?>"><p data-toggle="tooltip" data-original-title="<?php echo $text_tip_height; ?>"><?php echo $text_height; ?></p></div>
													<div class="col-xs-1 col-lg-1 oc2-pl-label-input-header <?php //echo ControllerModuleProductLabels::toggle($element_type,7) ?>"><p data-toggle="tooltip" data-original-title="<?php echo $text_tip_color; ?>"><?php echo $text_color; ?></p></div>
													<div class="col-xs-1 col-lg-1 oc2-pl-label-input-header <?php //echo ControllerModuleProductLabels::toggle($element_type,8) ?>"><p data-toggle="tooltip" data-original-title="<?php echo $text_tip_fill; ?>"><?php echo $text_fill; ?></p></div>
												</div>
											</div>
											<!-- labels here -->
											<div class="row" id="tfoot_<?php echo $element_type ?>">
												<div class="col-sm-12">
													<button type="button" class="btn btn-default btn-xs" style="margin-bottom:2px;margin-top:10px;" onclick="add_label_element('<?php echo $element_type ?>');return false;"><?php echo $text_addnew; ?> <b><?php echo ${'text_'.$element_type} ?></b></button>
												</div>
											</div> </div>
										<?php } ?>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="form-group form-group-sm">
										<div class="col-sm-6">
											<button type="button" id="deletebutton_label" onclick="delete_label();" class="btn btn-sm btn-primary" style="visibility:hidden;"><i class="fa fa-times"></i> <?php echo $button_delete; ?></button>
											<button type="button" id="savebutton_label" onclick="save_label();" class="btn btn-sm btn-primary" style="visibility:hidden;"><i class="fa fa-check"></i> <?php echo $button_save; ?></button>
										</div>
										<div class="col-sm-6">
											<div class="input-group input-group-sm">
												<span class="input-group-btn">
	        										<button type="button" id="saveasbutton_label" onclick="pl_saveas_label();" class="btn btn-primary"><?php echo $button_saveas; ?></button>
	      										</span>
	      										<input type="text" name="saveas_label_name" class="form-control" value="">
											</div>
										</div>
									</div>
								</div>
								</form>
							</div>
							<div class="col-sm-5">
								<div class="row">
									<div class="form-group form-group-sm">
										<div class="col-sm-2">
											<button type="button" onclick="preview_label_order('<?php echo $token ?>');return false;" id="previewbutton_label" class="btn btn-sm btn-primary"><?php echo $button_preview; ?></button>
										</div>
										<div class="col-sm-6">
											<select name="templateid" id="popupprotemplateid" class="select_template form-control">
											<?php foreach($label_templates as $id => $label_template) { ?>
												<option value="<?php echo $id ?>" <?php if($settings['product_labels_default_template'] == $id) echo " selected" ?>><?php echo $label_template; ?></option>
											<?php } ?>
											</select>
										</div>
										<div class="col-sm-4">
											<select name="orientation" class="form-control" id="popupproorientation">
												<option value="P" <?php if($settings['product_labels_default_orientation'] == "P") echo " selected" ?>><?php echo $text_portrait; ?></option>
												<option value="L" <?php if($settings['product_labels_default_orientation'] == "L") echo " selected" ?>><?php echo $text_landscape; ?></option>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<div id="preview_pdf_label" style="width:100%;height:350px;margin-top:10px;"></div>
										<div id="debug"></div>
									</div>
								</div>
							</div>
						</div>
            
            <div id="product-label-options">
              <div class="row" id="option_0">
              <input type="hidden" name="pl_options_num[0]" id="pl_options_num_0" value="1" class="templateinput pl_serializable form-control">
              <!--<select name="pl_options_name[0,0]" class="templateinput pl_serializable form-control" onchange="populate_opt_dropdown('pl_options_0_0',$(this).val(),0,0,0)"><option value=""></option><option value="_c">Custom Text</option></select>
              <select name="pl_options_value[0,0]" id="pl_options_0_0" class="templateinput pl_serializable form-control" onchange="toggle_textinput('div_options_string_0_0',$(this).val())"><option value="" selected="selected"></option></select>
              --></div> 
            </div>


            <select style="display: none" class="templateinput form-control" id="pl_labelid" onchange="update_preview();">
              <option value="2">Shoe label</option>
              <option value="3">100 barcodes</option>
              <option value="4">Dymo Label</option>
              <option value="5">Dymo with Image</option>
              <option value="6" selected>1.75x1 Inch, Image, </option>
              <option value="7">1x1.75 inch, Image, </option>
            </select>
            <select style="display: none" class="templateinput form-control" id="pl_templateid" onchange="get_template($(this).val())">
              <option value="1">8 labels</option>
              <option value="2">Avery 63.5 x 72</option>
              <option value="3">Avery 63.5 x 38.1</option>
              <option value="4">100 labels</option>
              <option value="5">Dymo 11356 (89x41)</option>
              <option value="6" selected>1.75x1 Inch</option>
            </select>

            <select style="display: none" class="templateinput form-control" id="pl_orientation" onchange="toggle_orientation();">
              <option value="P" selected="">Portrait</option>
              <option value="L">Landscape</option>
            </select>

            <script type="text/javascript">

            <!--

            var row=0;
            var nlabels = 0;
            var token = '<?php echo $token ?>';
            var scale=1;
            var options_list = new Array();
            var label_active = new Array();
            var blanks = new Array();

            var page_width = 44;
            var page_height = 25;
            var label_width = 44;
            var label_height = 24;
            var number_h = 1;
            var number_v = 1;
            var space_h = 0;
            var space_v = 0;
            var rounded = 0;
            var margint = 0;
            var marginl = 0;
            var orientation = "p";
            var template_id = 6;
            var label_id = 6;
            var template_name = "";
            var template_viewer = 340;
            //var product_id = 2373;
            
            /*for (i=1;i<=1;i++) {
              label_active['label'+i] = 1;
            }*/
            //get_template(template_id);
            //update_preview();
            //1558

            //-->
            </script>
            <form id="product_labels_form" method="POST" action="index.php?route=module/product_labels/labels_qrcode&token=<?php echo $token; ?>" target="_blank">
                <input type="hidden" name="orderid" value="">
                <input type="hidden" name="orderids" value="1">
                <input type="hidden" name="sample" value="0">
                <input type="hidden" name="blanks" value="">
                <input type="hidden" name="templateid" id="prolab_templateid" value="">
                <input type="hidden" name="labelid" id="prolab_labelid" value="">
                <input type="hidden" name="orientation" id="prolab_orientation" value="">
                <input type="hidden" name="productid" id="prolab_proids" value="">
                <input type="hidden" name="pl_options_num[0]" id="pl_options_num_0" value="1" class="templateinput pl_serializable form-control">
                
                <input type="hidden" name="filter_model_pl" id="filter_model_pl" value="">
                <input type="hidden" name="filter_location_pl" id="filter_location_pl" value="">
                <input type="hidden" name="filter_custom_pl" id="filter_custom_pl" value="">
                <input type="hidden" name="filter_text_pl" id="filter_text_pl" value="">
                <input type="hidden" name="filter_cpp_pl" id="filter_cpp_pl" value="">
                <input type="hidden" name="filter_catid_pl" id="filter_catid_pl" value="">
                <input type="hidden" name="filter_manid_pl" id="filter_manid_pl" value="">
			</form>
        <!-- Label Code -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary popupprintalllabel" id="pl_submit_button">Print All Label</button>
      </div>
    </div>
  </div>
</div>
<!-- Print Label Modal -->
  </div>
<script type="text/javascript"><!--
$(document).keypress(function(event){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '13'){
        event.preventDefault();
        return false;
    }
});
$('#button-gene').on('click', function() {
	var url = 'index.php?route=qrlabel/qrlabel&token=<?php echo $token; ?>';

	var filter_model = $('input[name=\'filter_model\']').val();

	if (filter_model) {
		url += '&filter_model=' + encodeURIComponent(filter_model);
	}

	var filter_location = $('input[name=\'filter_location\']').val();

	if (filter_location) {
		url += '&filter_location=' + encodeURIComponent((filter_location).trim());
	}
	
	var filter_custom = $('input[name=\'filter_custom\']').val();

	if (filter_custom) {
		url += '&filter_custom=' + encodeURIComponent((filter_custom).trim());
	}
	
	var filter_text = $('input[name=\'filter_text\']').val();

	if (filter_text) {
		url += '&filter_text=' + encodeURIComponent((filter_text).trim());
	}
	
	var filter_cpp = $('select[name=\'filter_cpp\']').val();

	if (filter_cpp) {
		url += '&filter_cpp=' + encodeURIComponent((filter_cpp).trim());
	}

	var catArray = [];
	var filter_c_id = $("#filter_cat_id input:checked").each(function() {
		catArray.push($(this).val());
	});
	var cselected;
	cselected = catArray.join(',') ;
	//alert(cselected);
	if (cselected.length > 1){
		url += '&filter_category_id=' + encodeURIComponent(cselected);
	}

	var manArray = [];
	var filter_m_id = $("#filter_man_id input:checked").each(function() {
		manArray.push($(this).val());
	});
	var mselected;
	mselected = manArray.join(',') ;
	if (mselected.length > 1){
		url += '&filter_manufacturer_id=' + encodeURIComponent(mselected);
	}
	
	url += '&gen=1';
	
	//alert(url);

	window.open(url);
	//location = url;
});

$('#reset').on('click', function() {
	var url = 'index.php?route=qrlabel/qrlabel&token=<?php echo $token; ?>';
	location = url;
});
//--></script>
  <script type="text/javascript"><!--
$('input[name=\'filter_model1\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_model=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['model'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_model\']').val(item['label']);
	}
});

$(document).on('click','#button-pl',function(e){
    e.preventDefault();
    var order_token = "<?php echo $token ?>";
    //select_label_order(order_token);
	$('#printModal').modal('show');
    var this_order_id = $(this).attr("data-orderid");
    $('input[name="orderid"]').val(this_order_id);
    $.ajax({
      url: 'index.php?route=sale/order/getOrderProducts&token=<?php echo $token; ?>&order_id=' +  this_order_id,
      dataType: 'json',
			success: function(json) {
          $("#prolab_proids").val(json.products);
			}
		});
  });
  
	var token = '<?php echo $token; ?>';
	var label_element_type = ['rect', 'img', 'text', 'barcode'];
	var label_element_name = ['<?php echo $text_rect; ?>','<?php echo $text_img; ?>','<?php echo $text_text; ?>', '<?php echo $text_barcode; ?>'];
	var elements;
	var row = <?php echo $row; ?>;
	var autocomp_label_elements = {
		delay: 0,
		source: ["{Shipping_Address}","{firstname}","{lastname}","{address_1}","{address_2}","{city}","{postcode}","{zone}","{zone_code}","{country}","{iso_code_2}","{iso_code_3}","{company}","{method}","{comment}", "{payment_code}", "{payment_method}", "{shipping_method}", "{tracking_number}", "{order_id}", "{store_name}", "{customer_id}", "{currency_code}","{date_added}", "{date_modified}","{date}", "{kg}", "{gr}", "{lb}", "{oz}"],
		minLength: 0
	};
	var colorpicker_color={
		pickerDefault: "000000",
		colors: ["<?php echo join('","', explode(",",$settings['product_labels_fgcolours'])) ?>"],
		showHexField: false
	};
	var colorpicker_fill={
		pickerDefault: "FFFFFF",
		colors: ["<?php echo join('","', explode(",",$settings['product_labels_bgcolours'])) ?>"],
		showHexField: false
	};
	var error_saveas_template = '<?php echo $error_saveas_template; ?>';
	var error_nopdf = '<?php echo $error_nopdf; ?>';
	var error_pdf = '<?php echo $error_pdf; ?>';
	var error_delete_template = '<?php echo $error_delete_template; ?>';
	var error_delete_label = '<?php echo $error_delete_label; ?>';
	var error_saveas_label = '<?php echo $error_saveas_label; ?>';
	var text_add = '<?php echo $text_add; ?>';
	var text_tip_font_f = '<?php echo $text_tip_font_f; ?>';
	var text_font_f = '<?php echo $text_font_f; ?>';
	var text_tip_font_s = '<?php echo $text_tip_font_s; ?>';
	var text_font_s = '<?php echo $text_font_s; ?>';
	var text_tip_font_a = '<?php echo $text_tip_font_a; ?>';
	var text_font_a = '<?php echo $text_font_a; ?>';
	var text_tip_text = '<?php echo $text_tip_text; ?>';
	var text_text = '<?php echo $text_text; ?>';
	var text_tip_img = '<?php echo $text_tip_img; ?>';
	var text_img = '<?php echo $text_img; ?>';
	var text_tip_xpos = '<?php echo $text_tip_xpos; ?>';
	var text_xpos = '<?php echo $text_xpos; ?>';
	var text_tip_ypos = '<?php echo $text_tip_ypos; ?>';
	var text_ypos = '<?php echo $text_ypos; ?>';
	var text_tip_width = '<?php echo $text_tip_width; ?>';
	var text_width = '<?php echo $text_width; ?>';
	var text_tip_height = '<?php echo $text_tip_height; ?>';
	var text_height = '<?php echo $text_height; ?>';
	var text_tip_color = '<?php echo $text_tip_color; ?>';
	var text_color = '<?php echo $text_color; ?>';
	var text_tip_fill = '<?php echo $text_tip_fill; ?>';
	var text_fill = '<?php echo $text_fill; ?>';
	var text_addnew = '<?php echo $text_addnew; ?>';
	var text_option_delete = '<?php echo $text_option_delete; ?>';
	var text_placeholder_text = '<?php echo $text_placeholder_text; ?>';
	var text_placeholder_img = '<?php echo $text_placeholder_img; ?>';
	var text_placeholder_xpos = '<?php echo $text_placeholder_xpos; ?>';
	var text_placeholder_ypos = '<?php echo $text_placeholder_ypos; ?>';
	var text_placeholder_width = '<?php echo $text_placeholder_width; ?>';
	var text_placeholder_height = '<?php echo $text_placeholder_height; ?>';
	var update_needed = '<?php echo $update_needed; ?>';
	var this_version = '<?php echo $this_version; ?>';
	var new_version = '<?php echo $new_version ?>';
	var please_update = '<?php echo $please_update ?>';
	var settings = {product_labels_default_template:'<?php echo $settings['product_labels_default_template'] ?>',product_labels_default_pagew:'<?php echo $settings['product_labels_default_pagew']; ?>',product_labels_default_pageh:'<?php echo $settings['product_labels_default_pageh']; ?>',product_labels_default_label:'<?php echo $settings['product_labels_default_label'] ?>'}
  
	var poppro = 0;
	var allproducts = 0;
 $(document).on('click','.popupprintalllabel',function(e){
	 
		var filter_model = $('input[name=\'filter_model\']').val();

		if (filter_model) {
			$("#filter_model_pl").val(encodeURIComponent((filter_model).trim()));
		}

		var filter_location = $('input[name=\'filter_location\']').val();

		if (filter_location) {
			$("#filter_location_pl").val(encodeURIComponent((filter_location).trim()));
		}
		
		var filter_custom = $('input[name=\'filter_custom\']').val();

		if (filter_custom) {
			$("#filter_custom_pl").val(encodeURIComponent((filter_custom).trim()));
		}
		
		var filter_text = $('input[name=\'filter_text\']').val();

		if (filter_text) {
			$("#filter_text_pl").val(encodeURIComponent((filter_text).trim()));
		}
		
		var filter_cpp = $('select[name=\'filter_cpp\']').val();

		if (filter_cpp) {
			$("#filter_cpp_pl").val(encodeURIComponent((filter_cpp).trim()));
		}

		var catArray = [];
		var filter_c_id = $("#filter_cat_id input:checked").each(function() {
			catArray.push($(this).val());
		});
		var cselected;
		cselected = catArray.join(',') ;
		if (cselected.length > 1){
			$("#filter_catid_pl").val(encodeURIComponent((cselected).trim()));
		}

		var manArray = [];
		var filter_m_id = $("#filter_man_id input:checked").each(function() {
			manArray.push($(this).val());
		});
		var mselected;
		mselected = manArray.join(',') ;
		if (mselected.length > 1){
			$("#filter_manid_pl").val(encodeURIComponent((mselected).trim()));
		}
    
		var current_label 			= $("#popupprolabel").val();
		var current_template 		= $("#popupprotemplateid").val();
		var current_orientation 	= $("#popupproorientation").val();
		
		$("#prolab_templateid").val(current_template);
		$("#prolab_labelid").val(current_label);
		$("#prolab_orientation").val(current_orientation);
		
		$("#product_labels_form").submit();
		$('#printModal').modal('hide');
	});
	
	$(document).ready(function() {
		$('#pltabs a:first').tab('show');
    preview_template();
    var order_token = "<?php echo $token ?>";
		preview_label_order(order_token);
		check_update();
	});
//--></script></div>
<?php echo $footer; ?>
