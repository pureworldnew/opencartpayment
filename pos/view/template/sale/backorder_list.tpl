<?php $edit_access = isset($permissions['backorders'][0]) && !empty($permissions['backorders'][0]) && $permissions['backorders'][0] == 'edit_access'; ?>
<div id="content">
	<link type="text/css" href="view/stylesheet/product_labels/stylesheet.css" rel="stylesheet" media="screen" />
  <script type="text/javascript" src="view/javascript/product_labels/pdfobject.js"></script>
  <script type="text/javascript" src="view/javascript/product_labels/product_lebel_edit.js"></script>
  <div class="container-fluid" style="margin-top: 25px;">
  	<?php if(!empty($error_upload)){ ?>
    <div style="color: #a94442; background-color: #f2dede; border-color: #ebccd1;padding: 10px;" class=""><?php foreach($error_upload as $error){ echo $error."<br/>";  } ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>
    <?php  } ?>
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
      <div class="panel-body">
        <div>
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-order-id"><?php echo $entry_order_id; ?></label>
                <input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" placeholder="<?php echo $entry_order_id; ?>" id="input-order-id" class="form-control" />
              </div>
							<div class="form-group">
                <label class="control-label" for="input-model"><?php echo $entry_model; ?></label>
                <input type="text" name="filter_model" value="<?php echo $filter_model; ?>" placeholder="<?php echo $entry_model; ?>" id="input-model" class="form-control" />
              </div>
              <div class="form-group">
								<label class="control-label" for="input-sales-person"><?php echo $entry_sales_person; ?></label>
								<select name="filter_sales_person" id="input-sales-person" class="form-control" multiple>
									<option value="*"></option>
									<?php foreach ($sales_persons as $sales_person) { ?>
									<?php if ( in_array( $sales_person['user_id'], explode( ",", $filter_sales_person ) ) ) { ?>
									<option value="<?php echo $sales_person['user_id']; ?>" selected="selected"><?php echo $sales_person['name']; ?></option>
									<?php } else { ?>
									<option value="<?php echo $sales_person['user_id']; ?>"><?php echo $sales_person['name']; ?></option>
									<?php } ?>
									<?php } ?>
								</select> 
							</div>
            </div>
            <div class="col-sm-4">
            <div class="form-group">
                <label class="control-label" for="input-manufacturer">Manufacturers</label>
                <select name="filter_manufacturer" class="form-control">
                  <option value="">Select Manufacurer</option>
                  <?php foreach($manufacturer_list as $manufacturer){ ?>
                 	<option <?php if($filter_manufacturer==$manufacturer['manufacturer_id']) { echo 'selected'; } ?> value="<?php echo $manufacturer['manufacturer_id']; ?>"><?php echo $manufacturer['name']; ?></option>
                  <?php } ?>
                </select>
							</div>
							<div class="form-group">
                <label class="control-label" for="input-customer"><?php echo $entry_customer; ?></label>
                <input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" placeholder="<?php echo $entry_customer; ?>" id="input-customer" class="form-control" />
              </div> 
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-date-added"><?php echo $entry_date_added; ?></label>
               
                  <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control daterange" />
                  
              </div>
              <div class="form-group">
				<label class="control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
				<select name="filter_order_status" id="input-order-status" class="form-control" multiple>
					<option value="*"></option>
					<?php if ($filter_order_status == '0') { ?>
					<option value="0" selected="selected"><?php echo $text_missing; ?></option>
					<?php } else { ?>
					<option value="0"><?php echo $text_missing; ?></option>
					<?php } ?>
					<?php foreach ($order_statuses as $order_status) { ?>
					<?php if ( in_array( $order_status['order_status_id'], explode( ",", $filter_order_status ) ) ) { ?>
					<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
					<?php } else { ?>
					<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
					<?php } ?>
					<?php } ?>
				</select>
			</div>
			<div class="form-group">
				<label class="control-label" for="input-record-limit">Show: </label>
				<select name="filter_record_limit" id="input-record-limit"  class="form-control" style="max-width: 20%; display: inline-block;"> 
					<option value="10" <?php echo ($filter_record_limit == "10") ? "selected" : ""; ?>>10</option>
					<option value="20" <?php echo ($filter_record_limit == "20") ? "selected" : ""; ?>>20</option>
					<option value="50" <?php echo ($filter_record_limit == "50") ? "selected" : ""; ?>>50</option>
					<option value="100" <?php echo ($filter_record_limit == "100") ? "selected" : ""; ?>>100</option>
				</select>
				<button type="button" id="button-filter-backorder" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
			</div>
            </div>
          </div>
        </div>
        <form method="post" enctype="multipart/form-data" target="_blank" id="form-order">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left"><?php if ($sort == 'o.order_id') { ?>
                    <a href="<?php echo $sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_order_id; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_order; ?>"><?php echo $column_order_id; ?></a>
                    <?php } ?></td>
                  <td>Store</td>
                  <td>Name</td>
                  <td class="text-left"><?php if ($sort == 'status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
										<?php } ?></td>
				 <td>Mfr</td>
                 <td>Model</td>
                 <td>Qty</td>
                  <td class="text-right"><?php if ($sort == 'o.total') { ?>
                    <a href="<?php echo $sort_total; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_total; ?>"><?php echo $column_total; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'o.date_added') { ?>
                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'o.date_modified') { ?>
                    <a href="<?php echo $sort_date_modified; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_modified; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_modified; ?>"><?php echo $column_date_modified; ?></a>
                    <?php } ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($orders) { ?>
                <?php foreach ($orders as $order) { ?>
                <tr>
                  <td class="text-left"><?php echo $order['order_id']; ?>
				  	<input type="hidden" name="shipping_code[]" value="<?php echo $order['shipping_code']; ?>" />
				  </td>
                  <td class="text-left"><?php echo "Backorders"; ?></td>
                  <td class="text-left"><?php echo $order['customer']; ?></td>
				  <?php if( $edit_access ) { ?>
                  <!-- Order Status -->
									<td class="text-left col-md-2" style="background-color:#<?php echo $order['order_status_color'];?>">
									<div class="status-edit" onclick="selectStatus(<?php echo $order['order_id']; ?> , '<?php echo $order['status']; ?>', '<?php echo $order['sales_person']; ?>');" id="status-<?php echo $order['order_id']; ?>"><?php echo $order['status']; ?></div>
									</td>
									<!-- /Order Status -->
				  <?php } else { ?>
					<td class="text-left"><?php echo $order['status']; ?></td>
				  <?php } ?>
									<td class="text-left"><?php foreach($order['manufacturers'] as $manuf){ echo $manuf['name'].",";  } ?></td>
                  <td class="text-left"><?php echo $order['model']; ?></td>
                  <td class="text-left"><?php echo $order['quantity']; ?></td>
                  <td class="text-right"><?php echo $order['total']; ?></td>
                  <td class="text-left"><?php echo $order['date_added']; ?></td>
                  <td class="text-left"><?php echo $order['date_modified']; ?></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="11"><?php echo $text_no_results; ?></td>
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
  </div>

	<!-- Print Label Modal -->
<div class="modal fade" id="printModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Product Label</h4>
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
            <form id="product_labels_form" method="POST" action="index.php?route=module/product_labels/labels_orders&token=<?php echo $token; ?>" target="_blank">
                <input type="hidden" name="orderid" value="">
                <input type="hidden" name="orderids" value="1">
                <input type="hidden" name="sample" value="0">
                <input type="hidden" name="blanks" value="">
                <input type="hidden" name="templateid" id="prolab_templateid" value="">
                <input type="hidden" name="labelid" id="prolab_labelid" value="">
                <input type="hidden" name="orientation" id="prolab_orientation" value="">
                <input type="hidden" name="productid" id="prolab_proids" value="">
                <input type="hidden" name="pl_options_num[0]" id="pl_options_num_0" value="1" class="templateinput pl_serializable form-control">
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
  
              <style type="text/css"> 
                   @import "http://fonts.googleapis.com/css?family=Droid+Sans";
                                #maindiv{
                width:960px;
                margin:10px auto;
                padding:10px;
                font-family:'Droid Sans',sans-serif
                }
                #formdiv{
                width:500px;
                float:left;
                text-align:center
                }
                
                .upload{
                background-color:red;
                border:1px solid red;
                color:#fff;
                border-radius:5px;
                padding:10px;
                text-shadow:1px 1px 0 green;
                box-shadow:2px 2px 15px rgba(0,0,0,.75)
                }
                .upload:hover{
                cursor:pointer;
                background:#c20b0b;
                border:1px solid #c20b0b;
                box-shadow:0 0 5px rgba(0,0,0,.75)
                }
                #file{
                color:green;
                padding:5px;
                border:1px dashed #123456;
                background-color:#f9ffe5
                }
                #upload{
                margin-left:45px
                }
                #noerror{
                color:green;
                text-align:left
                }
                #error{
                color:red;
                text-align:left
                }
                #img{
                width:17px;
                border:none;
                height:17px;
                margin-left:-20px;
                margin-bottom:91px
                }
                .abcd{
                text-align:center
                }
                .abcd img{
                height:100px;
                width:100px;
                padding:5px;
                border:1px solid #e8debd
                }
				.btn-grey {
					color: #fff;
					background-color: #808080;
					border-color: #808080;
				}
				
				.btn-grey:hover {
					color: #fff;
					background-color: #696969;
					border-color: #696969;
				}

            </style>

  <div id="viewImagesModal" class="modal fade" role="dialog">
                <div class="modal-dialog">

                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">View Order Images</h4>
                    </div>
                    <div class="modal-body">
                     

                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default hidden" data-dismiss="modal">Close</button>
                    </div>
                  </div>

                </div>
              </div>
         
  <script type="text/javascript"><!--
	$('#button-filter-backorder').on('click', function() {
	$('#button-filter-backorder').html('<i class="fa fa-spinner fa-spin"></i> Please Wait...');
	var url = 'index.php?route=sale/backorder&token=<?php echo $token; ?>';

	var filter_order_id = $('input[name=\'filter_order_id\']').val();

	if (filter_order_id) {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}

	var filter_model = $('input[name=\'filter_model\']').val();

	if (filter_model) {
		url += '&filter_model=' + encodeURIComponent(filter_model);
	}

  var filter_manufacturer = $('select[name=\'filter_manufacturer\']').val();

  if (filter_manufacturer) {
    url += '&filter_manufacturer=' + encodeURIComponent(filter_manufacturer);
  }

	var filter_customer = $('input[name=\'filter_customer\']').val();

	if (filter_customer) {
		url += '&filter_customer=' + encodeURIComponent(filter_customer);
	}
	
	var filter_order_status = $('select[name=\'filter_order_status\']').val();

	if (filter_order_status != '*' && filter_order_status != null) {
		url += '&filter_order_status=' + encodeURIComponent(filter_order_status);
	}

	var filter_total = $('input[name=\'filter_total\']').val();

	if (filter_total) {
		url += '&filter_total=' + encodeURIComponent(filter_total);
	}

	var filter_date_added = $('input[name=\'filter_date_added\']').val();

	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}

	var filter_date_modified = $('input[name=\'filter_date_modified\']').val();

	if (filter_date_modified) {
		url += '&filter_date_modified=' + encodeURIComponent(filter_date_modified);
	}

	var filter_record_limit = $('select[name=\'filter_record_limit\']').val();
	if (filter_record_limit) {
		url += '&filter_record_limit=' + encodeURIComponent(filter_record_limit);
	}
	
	//location = url;
	$('#tab_backorders').load(url);   
});
//--></script>
  <script type="text/javascript"><!--
$('input[name=\'filter_customer\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=customer/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['customer_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_customer\']').val(item['label']);
	}
});
//--></script>
  <script type="text/javascript"><!--
$('input[name^=\'selected\']').on('change', function() {
	$('#button-shipping, #button-invoice').prop('disabled', true);

	var selected = $('input[name^=\'selected\']:checked');

	if (selected.length) {
		$('#button-invoice').prop('disabled', false);
	}

	for (i = 0; i < selected.length; i++) {
		if ($(selected[i]).parent().find('input[name^=\'shipping_code\']').val()) {
			$('#button-shipping').prop('disabled', false);

			break;
		}
	}
});

$('input[name^=\'selected\']:first').trigger('change');

// Login to the API
var token = '';

$.ajax({
	url: '<?php echo $store; ?>index.php?route=api/login',
	type: 'post',
	data: 'key=<?php echo $api_key; ?>',
	dataType: 'json',
	crossDomain: true,
	success: function(json) {
        $('.alert').remove();

        if (json['error']) {
    		if (json['error']['key']) {
    			$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['key'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
    		}

            if (json['error']['ip']) {
    			$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['ip'] + ' <button type="button" id="button-ip-add" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-danger btn-xs pull-right"><i class="fa fa-plus"></i> <?php echo $button_ip_add; ?></button></div>');
    		}
        }

		if (json['token']) {
			token = json['token'];
		}
	},
	error: function(xhr, ajaxOptions, thrownError) {
		alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	}
});

$(document).delegate('#button-ip-add', 'click', function() {
	$.ajax({
		url: 'index.php?route=user/api/addip&token=<?php echo $token; ?>&api_id=<?php echo $api_id; ?>',
		type: 'post',
		data: 'ip=<?php echo $api_ip; ?>',
		dataType: 'json',
		beforeSend: function() {
			$('#button-ip-add').button('loading');
		},
		complete: function() {
			$('#button-ip-add').button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}

			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('button[id^=\'button-delete\']').on('click', function(e) {
	if (confirm('<?php echo $text_confirm; ?>')) {
		var node = this;

		$.ajax({
			url: '<?php echo $store; ?>index.php?route=api/order/delete&token=' + token + '&order_id=' + $(node).val(),
			dataType: 'json',
			crossDomain: true,
			beforeSend: function() {
				$(node).button('loading');
			},
			complete: function() {
				$(node).button('reset');
			},
			success: function(json) {
				$('.alert').remove();

				if (json['error']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				if (json['success']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
});
//--></script>
  <script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
  <link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
     <script type="text/javascript">
                $(".viewImages").click(function(){
            
      var order_ids=$(this).attr("data-orderid");
                      $.ajax({
                      type:"GET",
                      url: "index.php?route=sale/backorder/getOrderImages&token=<?php echo $token; ?>&order_id="+order_ids,
                      cache:false,
                      dataType:'json',
                      success:function(data){
                        var htmlImages="";
                        $(data.dataRows).each(function(i){
                          ispdf = data.dataRows[i].image.lastIndexOf(".pdf");
                            if (ispdf > 0){
                              htmlImages += '<div class="row"><div class="col-sm-8 col-md-offset-2"><a target="_blank" href="'+data.dataRows[i].image+'"><i class="fa fa-file-pdf-o" style="font-size:150px;color:red"></i></a></div></div>';
                            }else{
                            htmlImages += '<div class="row"><div class="col-sm-8 col-md-offset-2"><img class="img-responsive" src="'+data.dataRows[i].image+'" width="250"></div></div>';
                          }
                        });
						htmlImages += '<div id="maindiv"><div id="formdiv"><h2>Multiple Image Upload For Orders</h2><form enctype="multipart/form-data" action="index.php?route=sale/backorder/uploadOrderImages&token=<?php echo $token; ?>&order_id='+order_ids+'" method="post"><p>Only JPEG,PNG,JPG,PDF and ZIP files are supported.</p><div id="filediv"><input name="file[]" type="file" id="file"/></div><input type="button" id="add_more" class="upload" value="Add More Files"/><input type="hidden" name="order_id"  value="'+order_ids+'"/><input type="submit" value="Upload File" name="submit" id="upload" class="upload"/></form></div></div>';
                        $("#viewImagesModal .modal-body").html(htmlImages);
						var abc = 0;      // Declaring and defining global increment variable.
						$(document).ready(function() {
						//  To add new input file field dynamically, on click of "Add More Files" button below function will be executed.
						$('#add_more').click(function() {
						$(this).before($("<div/>", {
						id: 'filediv'
						}).fadeIn('slow').append($("<input/>", {
						name: 'file[]',
						type: 'file',
						id: 'file'
						}), $("<br/><br/>")));
						});
						// Following function will executes on change event of file input to select different file.
						$('body').on('change', '#file', function() {
						if (this.files && this.files[0]) {
						abc += 1; // Incrementing global variable by 1.
						var z = abc - 1;
						var x = $(this).parent().find('#previewimg' + z).remove();
						$(this).before("<div id='abcd" + abc + "' class='abcd'><img id='previewimg" + abc + "' src=''/></div>");
						var reader = new FileReader();
						reader.onload = imageIsLoaded;
						reader.readAsDataURL(this.files[0]);
						$(this).hide();
						$("#abcd" + abc).append($("<img/>", {
						id: 'img',
						src: 'https://image.flaticon.com/icons/png/128/59/59836.png',
						alt: 'delete'
						}).click(function() {
						$(this).parent().parent().remove();
						}));
						}
						});
						// To Preview Image
						function imageIsLoaded(e) {
							$('#previewimg' + abc).attr('src', e.target.result);
							};
							$('#upload').click(function(e) {
							var name = $(":file").val();
							if (!name) {
							alert("First Image Must Be Selected");
							e.preventDefault();
						}
						});
						});
                      }
                     });
                   
                 });
      
      $("#viewImagesModal").on("hidden.bs.modal", function(){
          $("#viewImagesModal .modal-body").html("");
      });

              </script>
<script type="text/javascript">
function showProducts(order_id) {
	$("#products_" + order_id).toggleClass('hidden');	
}
</script>

<script type="text/javascript"><!--
				
				var store_url = '';
				var admintoken = '<?php echo $token; ?>';
				
				$(".table-responsive").mousemove(function(){
					$( ".alert-success" ).slideUp( "slow", function() {
   						$(".alert-success").remove();
					});
				});
				
				$(document).ready(function() {
					$('.invoice_prefix_hidden').hide();
				});
				
				function showProducts(order_id) {
					$("#products_" + order_id).toggleClass('hidden');	
				}
				
				function showBulkStatus() {
					$('#bulk-status-well').toggleClass('hidden');
				}
				
				function inputValue(param, order_id) {
				
					html  = '<div class="' + param + '_edit" id="' + param + order_id + '">';
					html += '<div class="input-group"><input type="text" value="' + $('#' + param + order_id).html() + '" class="form-control">';
					html += '<span class="input-group-btn">';
					html += '<a onclick="saveValue(\'' + param + '\' , ' + order_id + ')" role="button" class="btn  btn-success" data-toggle="tooltip" title="Save" data-container="body"><i class="fa fa-check"></i></a>';
					html += '<a onclick="closeInput(\'' + param + '\', ' + order_id + ')" role="button" class="btn btn-danger" data-toggle="tooltip" title="Cancel" data-container="body"><i class="fa fa-times"></i></a>';
					html += '</div></span></div>';
					
					$('#' + param + order_id).replaceWith(html);
				
				}
				
				function selectStatus(order_id, status,sales_person=0) {
					
					html  = '<div id="status-' + order_id +'" class="">';
					html += '<div class="input-group"><select class="form-control" name="order_status" id="select-status-' + order_id + '" onchange="saveStatus(' + order_id + ');">';
					<?php foreach ($order_statuses as $order_status) { ?>
						if ('<?php echo $order_status['name']; ?>' ==  status) {
							html += '<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>';
						} else {
							html += '<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>';
						}
					<?php } ?>
					
					html += '</select><span class="input-group-btn"><button onclick="closeSelect(' + order_id + ', \'' + status + '\')" class="btn btn-danger" data-toggle="tooltip" title="Cancel" data-container="body"><i class="fa fa-times"></i></button></span></div>';
					html += '<div class="checkbox" style="margin-top:10px;">';
					html += '<div class="form-group"><label class="col-sm-8 control-label" for="notify-' + order_id +'">Notify Customer</label>';
                    html += '<div class="col-sm-4"><input type="checkbox" id="notify-' + order_id +'" name="notify"></div></div>';
					
					html += '<div class="form-group"><label class="col-sm-8 control-label" for="override' + order_id +'"> <span data-toggle="tooltip" title="If the customers order is being blocked from changing the order status due to a anti-fraud extension enable override.">Override</span></label>';
                    html += '<div class="col-sm-4"><input type="checkbox" name="override" value="1" id="override' + order_id +'" /></div></div>';
					
					html += '<button type="button" class="btn btn-sm btn-default" data-toggle="collapse" data-target="#inputComment_' + order_id + '" aria-expanded="false" aria-controls="inputComment' + order_id + '">Add Customer Comment</button><br><br><button type="button" class="btn btn-sm btn-default" data-toggle="collapse" data-target="#dropdownsp_' + order_id + '" aria-expanded="false" aria-controls="dropdownsp_' + order_id + '">Assign Sales Person</button></div>';
					html += '</div>';
					html += '<div class="collapse" id="inputComment_' + order_id + '" style="margin-top:10px;">';
					html += '<hr /><textarea name="comment" id="comment-' + order_id + '" class="form-control" rows="8"></textarea>';
					html += '<div class="form-group"><button type="button" onclick="saveStatus(' + order_id + ')" class="btn btn-success">Add history</button></div></div>';
					html += '<div class="collapse" id="dropdownsp_' + order_id + '" style="margin-top:10px;">';
					html += '<hr /><strong>Sales Person:<strong>';
					html += '<div class="input-group"><select class="form-control" name="sales_person" id="sales-person-' + order_id + '"><option value="*"></option>';
					<?php foreach ($sales_persons as $sp) { ?>
						
						if ('<?php echo $sp['user_id']; ?>' ==  sales_person) {
						html += '<option value="<?php echo $sp['user_id']; ?>" selected="selected"><?php echo $sp['name']; ?></option>';
							
						} else {
						html += '<option value="<?php echo $sp['user_id']; ?>"><?php echo $sp['name']; ?></option>';
						}
                      	<?php } ?>
					
					html += '</select><br><br>';
					html += '<div class="form-group"><button type="button" onclick="saveStatus(' + order_id + ',1)" class="btn btn-success">Assign</button></div></div>';
					
					html += '</div></div>';
					
					
					$("#status-" + order_id).replaceWith(html);
                
				}				
				
				function saveStatus(order_id,sales_person = 0) {
					
					var order_status_id = $("#select-status-" + order_id).val();
					var order_status_name = $("#select-status-" + order_id).find('option:selected').text();
					var notify = ($("#notify-" + order_id).prop('checked') ? 1 : 0);
					var comment = $('#inputComment_' + order_id + ' textarea').val();
					
					if(sales_person == 1)
					{
						sales_person = $("#sales-person-" + order_id).val();
					}
					
					
					if(typeof verifyStatusChange == 'function'){
						if(verifyStatusChange() == false){
						  return false;
						}else{
						  addOrderInfo(order_status_id, order_id);
						}
					  }else{
						addOrderInfo(order_status_id, order_id);
					  }
					
					store_url = $("#store-url" + order_id).val();
					
					$.ajax({
						url: store_url + 'index.php?route=api/order/history&token=' + token + '&order_id=' + order_id,
						url: "<?=HTTPS_CATALOG?>index.php?route=api/order/history&token=" + token + '&order_id=' + order_id,
						type: 'post',
						dataType: 'json',
						data: 'order_status_id=' + encodeURIComponent($("#select-status-" + order_id).val()) + '&sales_person=' + sales_person + '&username=' + encodeURIComponent($('input[name=\'username\']').val()) + '&notify=' + ($("#notify-" + order_id).prop('checked') ? 1 : 0) + '&override=' + ($('#override-' + order_id).prop('checked') ? 1 : 0) + '&append=0' + '&comment=' + encodeURIComponent($('#inputComment_' + order_id + ' textarea').val()),
						success: function(json) {
							closeSelect(order_id, order_status_name, sales_person);
							alertJson('alert alert-success', json);
						},
						error: function(xhr, ajaxOptions, thrownError) {
							//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" +xhr.responseText);
						}
					});
				}
				
				function closeSelect(order_id, order_status_name,sales_person) {
					$("#status-" + order_id).replaceWith('<div class="status-edit" onclick="selectStatus('+ order_id +', \'' + order_status_name + '\' , \'' + sales_person + '\');" id="status-' + order_id + '">' + order_status_name + '</div>');
					$('#inputComment_' + order_id).remove();
					$('#dropdownsp_' + order_id).remove();	
				}
				
				function bulkStatusUpdate() {
					
					var order_status_id = $('#bulk-status-update').val();
					var order_status_name = $('#bulk-status-update').find('option:selected').text();
					var selected = $('input[name^=\'selected\']:checked');
					
					
					for (i = 0; i < selected.length; i++) {
						var order_id = $(selected[i]).parent().find('input[name^=\'selected\']').val()
						if (order_id) {
							bulkSaveStatus(order_id,order_status_id,order_status_name);
						}
					}
					
					$('input[name^=\'selected\']:checked').prop('checked', false).trigger('change');
				}
				
				function AJAXQueue(selected) {
					var order_status_id = $('#bulk-status-update').val();
					var order_status_name = $('#bulk-status-update').find('option:selected').text();
					
					for (i = 0; i < selected.length; i++) {
						var order_id = $(selected[i]).parent().find('input[name^=\'selected\']').val()
						if (order_id) {
							bulkSaveStatus(order_id,order_status_id,order_status_name);
						}
					}
				}
	
				function bulkSaveStatus(order_id,order_status_id,order_status_name) {

					if (typeof verifyStatusChange == 'function') {
						if (verifyStatusChange() == false) {
						  return false;
						} else {
						  addOrderInfo(order_status_id, order_id);
						}
					} else {
						addOrderInfo(order_status_id, order_id);
					}
					
					store_url = $("#store-url" + order_id).val();
					
					$.ajax({
						url: "<?=HTTPS_CATALOG?>index.php?route=api/order/history&token=" + token + '&order_id=' + order_id,
						type: 'post',
						dataType: 'json',
						data: 'order_status_id=' + encodeURIComponent($("#select-status-" + order_id).val()) + '&notify=' + ($("#notify-" + order_id).prop('checked') ? 1 : 0) + '&override=' + ($('#override-' + order_id).prop('checked') ? 1 : 0) + '&append=0' + '&comment=' + encodeURIComponent($('#inputComment_' + order_id + ' textarea').val()),
						beforeSend: function() {
						},
						success: function(json) {
							alertJson('alert alert-success', json);
							changeStatusText(order_id, order_status_name);
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" +xhr.responseText);
						}
					});								
				}
				
				function changeStatusText(order_id, order_status_name) {
					$("#status-" + order_id).replaceWith('<div class="status-edit" onclick="selectStatus('+ order_id +', \'' + order_status_name + '\' );" id="status-' + order_id + '">' + order_status_name + '</div>');
				}
				
				function saveValue(param, order_id) {
					
					var new_value = $('#' + param + order_id + ' input').val();
					
					$.ajax({
					  type: 'post',	
					  url: 'index.php?route=sale/order/saveValue&token=' + admintoken,
					  dataType: 'json',
					  data: { param: param, new_value: new_value , order_id: order_id},
					  beforeSend: function() {
							$('#' + param + order_id).before('<img scr="view/javascript/jquery/jstree/themes/default/throbber.gif">');
						},
					  success: function(json) { 
						 alertJson('alert alert-success', json);
						 closeInput(param, order_id);
					  },
					  error: function(json) { 
						 alertJson('alert alert-danger', json);
						 closeInput(param, order_id);
					  }
					});
					
				}
				
				function addOrderInfo(status_id, order_id){
					  $.ajax({
						url: 'index.php?route=extension/openbay/addorderinfo&token=<?php echo $token; ?>&order_id='+ order_id +'&status_id=' + status_id,
						type: 'post',
						dataType: 'html',
						data: $(".openbay-data").serialize()
					  });
				}

				function createInvoiceNo(order_id) {
				
					$.ajax({
						url: 'index.php?route=sale/order/createinvoiceno&token=' + admintoken +'&order_id='+ order_id +'',
						dataType: 'json',
						beforeSend: function() {
							$('#generate_invoice_' + order_id).button('loading');	
						},
						complete: function() {
							$('#generate_invoice_' + order_id).button('reset');	
						},
						success: function(json) {
							$('.alert-success, .alert-danger').remove();
						
							if (json['error']) {
				
								$('.alert-danger').fadeIn('slow');
							}
			
							if (json['invoice_no']) {
								$('#generate_invoice_' + order_id).fadeOut('slow', function() {
									$('#generate_invoice_' + order_id).fadeOut('slow', function() {
										
									});
									var prefix = $('#original_invoice_prefix_' + order_id).html();
									
									
									$('#generated_invoice_prefix_' + order_id).html('<div class="invoice_prefix_edit" id="invoice_prefix' + order_id + '" onclick="inputValue(\'invoice_prefix\',' + order_id +');">' + prefix + '</div>' );
									$('#generated_invoice_number_' + order_id).html('<div class="invoice_no_edit" id="invoice_no' + order_id + '" onclick="inputValue(\'invoice_no\',' + order_id +');">' + json['invoice_no'].substring(prefix.length) + '</div>' );
									$('#generated_invoice_prefix_' + order_id).show();
									
								});
							}
						}
					});
				}

				function closeInput(param, order_id) {
					
					 html = '<div class="' + param + '_edit" id="' + param + order_id + '" onclick="inputValue(\'' + param + '\',' + order_id +');" style="display:inline;">' +  $('#' + param + order_id +' input').val() + '</div>';
					 $('#' + param + order_id).fadeOut('slow');
					 $('#' + param + order_id).replaceWith(html);
					
				}
			
				function alertJson(action, json) {
					
					$('.alert-success, .alert-danger').remove();
					
					if (json['success']) {
						$('#order-list-main').before('<div class="' + action + '">' + json['success'] + '</div>');
					} else if (json['error']) {
						$('#order-list-main').before('<div class="' + action + '">' + json['error'] + '</div>');
					}
				}
				
				$('#filter-form input').keydown(function(e) {
					if (e.keyCode == 13) {
						filter();
					}
				});
				
				$(function () {
				  $('.input-group-btn').tooltip({container: "body"});
				});
				
				function addCommission(order_id) {
					$.ajax({
						url: 'index.php?route=sale/order/addcommission&token=<?php echo $token; ?>&order_id=' + order_id,
						type: 'post',
						dataType: 'json',
						beforeSend: function() {
							$('#button-commission-add-' + order_id).button('loading');
						},
						complete: function() {
							$('#button-commission-add-' + order_id).button('reset');
						},			
						success: function(json) {
							$('.alert').remove();
						
							if (json['error']) {
								$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
							}
			
							if (json['success']) {
								$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
				
								$('#button-commission-add-' + order_id).replaceWith('<button id="button-commission-remove-' + order_id + '" type="button" class="btn btn-danger btn-xs pull-right" onclick="removeCommission(' + order_id + ');"><i class="fa fa-minus-circle"></i> Remove Commission</button>');
							}
						},			
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				}
			
				function removeCommission(order_id) {
			
					$.ajax({
						url: 'index.php?route=sale/order/removecommission&token=<?php echo $token; ?>&order_id=' + order_id,
						type: 'post',
						dataType: 'json',
						beforeSend: function() {
							$('#button-commission-remove-' + order_id).button('loading');
		
						},
						complete: function() {
							$('#button-commission-remove-' + order_id).button('reset');
						},		
						success: function(json) {
							$('.alert').remove();
						
							if (json['error']) {
								$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
							}
			
							if (json['success']) {
								$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
				
								$('#button-commission-remove-' + order_id).replaceWith('<button id="button-commission-add-' + order_id + '" type="button" class="btn btn-success btn-xs pull-right" onclick="addCommission(' + order_id + ');"><i class="fa fa-minus-circle"></i> Add Commission</button>');
							}
						},			
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				}
				
				$('#install-button').click(function(){
					$.ajax({
						url: 'index.php?route=sale/order/install&token=<?php echo $token; ?>',
						type: 'post',


						dataType: 'json',
						success: function(json) {
							$('#install-iaol').remove();
							alertJson('alert alert-success', json);
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" +xhr.responseText);
						}
					});
				});
				 
				function AJAXsave(param,action) {
					$.ajax({
						url: 'index.php?route=sale/order/ajaxSave&token=<?php echo $token; ?>',
						type: 'post',
						dataType: 'json',
						data: { key: param, action: action},
						success: function(json) {
							alertJson('alert alert-success', json);
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" +xhr.responseText);
						}
					});
				}
				
				function AJAXsaveColor(param,action) {
					$.ajax({
						url: 'index.php?route=sale/order/ajaxSave&token=<?php echo $token; ?>',
						type: 'post',
						dataType: 'json',
						data: { key: param, action: action, color: $('#config-color-store-' + param).val()},
						success: function(json) {
							alertJson('alert alert-success', json);
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" +xhr.responseText);
						}
					});
				}		
//--></script>
<!-- Product Labels Script -->
      <script type="text/javascript">
	var poppro = 0;
	var allproducts = 0;
	
	$(document).on('click','.productlabelpopup',function(e){
    e.preventDefault();
    var order_token = "<?php echo $token ?>";
    select_label_order(order_token);
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
  

  $("select[name='select_label']").on("change", function() {
      var order_token = "<?php echo $token ?>";
      select_label_order(order_token);
    });
	
	
	$(document).on('click','.popupprintalllabel',function(e){
    
		var current_label 			= $("#popupprolabel").val();
		var current_template 		= $("#popupprotemplateid").val();
		var current_orientation 	= $("#popupproorientation").val();
		
		$("#prolab_templateid").val(current_template);
		$("#prolab_labelid").val(current_label);
		$("#prolab_orientation").val(current_orientation);
		
		$("#product_labels_form").submit();
		$('#printModal').modal('hide');
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

	$(document).ready(function() {
		$('#pltabs a:first').tab('show');
    preview_template();
    var order_token = "<?php echo $token ?>";
		//preview_label_order(order_token);
		check_update();
	});
	$('#shipping_title,#shipping_value').on('change', function() {
		$("#input-shipping-method option[value='free.free']").attr('selected', 'selected');
    });
</script>
<!-- Product Labels Script -->

	$('.daterange').daterangepicker();
<?php  if (empty($filter_date_modified) ){  echo "$('#input-date-modified').val('');"; } ?>
	
<?php  if (empty($filter_date_added) ){  echo "$('#input-date-added').val('');"; } ?>
</script> 
