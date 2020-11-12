<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
      	<input type="hidden" value="<?=$logedin_user?>" name="username"/>
        <button type="submit" id="button-shipping" form="form-order" formaction="<?php echo $shipping; ?>" data-toggle="tooltip" title="<?php echo $button_shipping_print; ?>" class="btn btn-info"><i class="fa fa-truck"></i></button>
        <button type="submit" id="button-invoice" form="form-order" formaction="<?php echo $invoice; ?>" data-toggle="tooltip" title="<?php echo $button_invoice_print; ?>" class="btn btn-info"><i class="fa fa-print"></i></button>
        <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a></div>
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
  <div class="container-fluid">
  	<?php if(isset($error_upload)){ ?>
    <div style="color: #a94442; background-color: #f2dede; border-color: #ebccd1;padding: 10px;" class=""><?php foreach($error_upload as $error){ echo $error."<br/>";  } ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>
    <?php  } ?>
    
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-order-id"><?php echo $entry_order_id; ?></label>
                <input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" placeholder="<?php echo $entry_order_id; ?>" id="input-order-id" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-payment-method"><?php echo $entry_payment_method; ?></label>
                <select name="filter_payment_method" id="input-payment-method" class="form-control" multiple>
                  <option value="*"></option>
                  <?php foreach ($payment_methods as $payment_method) { ?>
                  <?php if ( in_array( $payment_method['code'], explode( ",", $filter_payment_method ) ) ) { ?>
                  <option value="<?php echo $payment_method['code']; ?>" selected="selected"><?php echo $payment_method['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $payment_method['code']; ?>"><?php echo $payment_method['name']; ?></option>
                  <?php } ?>
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
				<label class="control-label" for="input-order-type"><?php echo $entry_store; ?></label>
				<select name="filter_order_type" id="input-order-type" class="form-control">
				          <option value="0" <?php if ($filter_order_type == 0) { echo 'selected'; } ?> >Gempacked</option>
                  <option value="1" <?php if ($filter_order_type == 1) { echo 'selected'; } ?> >POS</option>
                  <option value="2" <?php if ($filter_order_type == 2) { echo 'selected'; } ?> >Incoming</option>
                  <option value="3" <?php if ($filter_order_type == 3) { echo 'selected'; } ?> >Backorder</option>
                  <option value="*" <?php if ($filter_order_type === '*') { echo 'selected'; } ?> >All Stores</option>
				</select>
			  </div>
        <div class="form-group">
            <label class="control-label" for="input-total"><?php echo $entry_total; ?></label>
            <input type="text" name="filter_total" value="<?php echo $filter_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
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
        <div class="form-group">
          <label class="control-label" for="input-model"><?php echo $entry_model; ?></label>
          <input type="text" name="filter_model" value="<?php echo $filter_model; ?>" placeholder="<?php echo $entry_model; ?>" id="input-model" class="form-control" />
        </div>
        </div>
        <div class="col-sm-4">
          <div class="form-group">
            <label class="control-label" for="input-date-modified"><?php echo $entry_date_modified; ?></label>
           
              <input type="text" name="filter_date_modified" value="<?php echo $filter_date_modified; ?>" placeholder="<?php echo $entry_date_modified; ?>" data-date-format="YYYY-MM-DD" id="input-date-modified"  class="form-control daterange" />
            
          </div>
			
	
          <div class="form-group">
            <label class="control-label" for="input-date-added"><?php echo $entry_date_added; ?></label>
           
              <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control daterange" />
             
          </div>
          <div class="form-group">
            <label class="control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
            <select name="filter_order_status" id="input-order-status" class="form-control">
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
            <label class="control-label" for="input-payment-status">Payment Status</label>
            <select name="filter_payment_status" id="input-payment-status" class="form-control">
              <option value="*">Select Payment Status</option>
                <option value="#00e93c" <?php echo ($filter_payment_status == "#00e93c") ? "selected" : ""; ?>>Completed</option>
                <option value="nc" <?php echo ($filter_payment_status == "nc") ? "selected" : ""; ?>>Not Completed</option>
                <option value="#ffff00" <?php echo ($filter_payment_status == "#ffff00") ? "selected" : ""; ?>>Partially Refunded</option>
                <option value="#ff0000" <?php echo ($filter_payment_status == "#ff0000") ? "selected" : ""; ?>>Refunded</option>
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
            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
          </div>
        </div>
        </div>
        </div>
        <form method="post" enctype="multipart/form-data" target="_blank" id="form-order">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-right" style="width: 7%;"><?php if ($sort == 'o.order_id') { ?>
                    <a href="<?php echo $sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_order_id; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_order; ?>"><?php echo $column_order_id; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'customer') { ?>
                    <a href="<?php echo $sort_customer; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_customer; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_customer; ?>"><?php echo $column_customer; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'o.payment_method') { ?>
                    <a href="<?php echo $sort_payment_method; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_payment_method; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_payment_method; ?>"><?php echo $column_payment_method; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'o.shipping_method') { ?>
                    <a href="<?php echo $sort_shipping_method; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_shipping_method; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_shipping_method; ?>"><?php echo $column_shipping_method; ?></a>
                    <?php } ?></td>
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
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($orders) { ?>
                <?php foreach ($orders as $order) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($order['order_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" />
                    <?php } ?>
                    <input type="hidden" name="shipping_code[]" value="<?php echo $order['shipping_code']; ?>" /></td>
                  <td class="text-right"><?php echo ($order["pos_status"] == 1)? "BF-".$order['order_id']: $order['order_id']; ?></td>
                  <td class="text-left"><?php echo $order['customer']; ?></td>
                  <td class="text-left"><?php echo $order['status']; ?></td>
                  <td class="text-left" <?php if( !empty($order['payment_background']) ) { echo 'style="background-color: ' .  $order['payment_background'] . '"'; } ?>><?php echo $order['payment_method']; ?></td>
                  <td class="text-left" <?php if( !empty($order['shipping_background']) ) { echo 'style="' . $order['shipping_background'] .'"'; } ?>>
                                <?php if ( $order['combine_shipping'] ) {  ?>
                                    <p style="color:red; font-weight:600;"><img src="<?php echo HTTPS_CATALOG; ?>image/red_flag.png" width="32px;" height="32px;">Combine Order</p>
                                <?php } ?>
                                <?php echo $order['shipping_method']; ?>
                  </td>
                  <td class="text-right"><?php echo $order['total']; ?></td>
                  <td class="text-left"><?php echo $order['date_added']; ?></td>
                  <td class="text-left"><?php echo $order['date_modified']; ?></td>
                  <td class="text-right"> 
                  <?php if( $order['order_has_image'] ) : ?>
                  <button type="button" data-orderid="<?php echo $order['order_id']; ?>" data-toggle="modal" data-target="#viewImagesModal" class="btn btn-success btn-md viewImages"><i class="fa fa-image"></i></button>
                  <?php else : ?>
                  <button type="button" data-orderid="<?php echo $order['order_id']; ?>" data-toggle="modal" data-target="#viewImagesModal" class="btn btn-grey btn-md viewImages"><i class="fa fa-image"></i></button>
                  <?php endif; ?>
                  <a href="<?php echo $order['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a> <a href="<?php echo $order['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                    
                  <a href="javascript:void(0);" data-toggle="tooltip" title="Print Order Label" data-orderid="<?php echo $order['order_id']; ?>" class="btn btn-success productlabelpopup"><i class="fa fa-print"></i></a>

                    <button type="button" value="<?php echo $order['order_id']; ?>" id="button-delete<?php echo $order['order_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></button></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="10"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
          <div class="col-sm-12 text-right"><button type="submit" id="button-batch-capture" form="form-order" formaction="<?php echo $batchcapture; ?>" class="btn btn-primary">Batch Capture</button></div>

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
$('#button-filter').on('click', function() {
	url = 'index.php?route=sale/order&token=<?php echo $token; ?>';

	var filter_order_id = $('input[name=\'filter_order_id\']').val();

	if (filter_order_id) {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}

  var filter_model = $('input[name=\'filter_model\']').val();

	if (filter_model) {
		url += '&filter_model=' + encodeURIComponent(filter_model);
	}

	var filter_customer = $('input[name=\'filter_customer\']').val();

	if (filter_customer) {
		url += '&filter_customer=' + encodeURIComponent(filter_customer);
	}
	
	var filter_order_type = $('select[name=\'filter_order_type\']').val();

	if (filter_order_type) {
		url += '&filter_order_type=' + encodeURIComponent(filter_order_type);
	}

	var filter_order_status = $('select[name=\'filter_order_status\']').val();

	if (filter_order_status != '*' && filter_order_status) {
		url += '&filter_order_status=' + encodeURIComponent(filter_order_status);
	}

	var filter_payment_status = $('select[name=\'filter_payment_status\']').val();

	if (filter_payment_status != '*' && filter_payment_status) {
		url += '&filter_payment_status=' + encodeURIComponent(filter_payment_status);
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
	
	var filter_payment_method = $('select[name=\'filter_payment_method\']').val();
	
	if (filter_payment_method) {
		url += '&filter_payment_method=' + encodeURIComponent(filter_payment_method);
	}	
	
	var filter_sales_person = $('select[name=\'filter_sales_person\']').val();
	
	if (filter_sales_person) {
		url += '&filter_sales_person=' + encodeURIComponent(filter_sales_person);
	}

  var filter_record_limit = $('select[name=\'filter_record_limit\']').val();
  if (filter_record_limit) {
    url += '&filter_record_limit=' + encodeURIComponent(filter_record_limit);
  }

	location = url;
		  
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
	$('#button-shipping, #button-invoice, #button-batch-capture').prop('disabled', true);

	var selected = $('input[name^=\'selected\']:checked');

	if (selected.length) {
		$('#button-invoice').prop('disabled', false);
    $('#button-batch-capture').prop('disabled', false);
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
                      url: "index.php?route=sale/order/getOrderImages&token=<?php echo $token; ?>&order_id="+order_ids,
                      cache:false,
                      dataType:'json',
                      success:function(data){
                        var htmlImages="";
                        $(data.dataRows).each(function(i){
                            ispdf = data.dataRows[i].image.lastIndexOf(".pdf");
                            if (ispdf > 0){
                              htmlImages += '<div class="row"><div class="col-sm-8 col-md-offset-2"><a target="_blank" href="'+data.dataRows[i].image+'"><i class="fa fa-file-pdf-o" style="font-size:150px;color:red"></i></a></div></div>';
                            }else{
                              iszip = data.dataRows[i].image.lastIndexOf(".zip");
                              if (iszip > 0){
                              htmlImages += '<div class="row"><div class="col-sm-8 col-md-offset-2"><a target="_blank" href="'+data.dataRows[i].image+'"><i class="fa fa-file-archive-o" style="font-size:150px;color:red"></i></a></div></div>';
                            }else{
                            htmlImages += '<div class="row"><div class="col-sm-8 col-md-offset-2"><a target="_blank" href="'+data.dataRows[i].image+'"><img class="img-responsive" src="'+data.dataRows[i].image+'" width="250"></a></div></div>';
                            }
                          }
                        });
						htmlImages += '<div id="maindiv"><div id="formdiv"><h2>Multiple Image Upload For Orders</h2><form enctype="multipart/form-data" action="index.php?route=sale/order/uploadOrderImages&token=<?php echo $token; ?>&order_id='+order_ids+'" method="post"><p>Only JPEG,PNG,JPG,PDF and ZIP files are supported.</p><div id="filediv"><input name="file[]" type="file" id="file"/></div><input type="button" id="add_more" class="upload" value="Add More Files"/><input type="hidden" name="order_id"  value="'+order_ids+'"/><input type="submit" value="Upload File" name="submit" id="upload" class="upload"/></form></div></div>';
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
		preview_label_order(order_token);
		check_update();
	});
	$('#shipping_title,#shipping_value').on('change', function() {
		$("#input-shipping-method option[value='free.free']").attr('selected', 'selected');
    });
</script>
<!-- Product Labels Script -->
         <script type="text/javascript">
	$('.daterange').daterangepicker();
<?php  if (empty($filter_date_modified) ){  echo "$('#input-date-modified').val('');"; } ?>
	
<?php  if (empty($filter_date_added) ){  echo "$('#input-date-added').val('');"; } ?>
</script> 
<script type="text/javascript"><!--//

  function refreshAdminPayapl(value, order_id) {
    $('#ppat_response_'+ order_id).html('');
    $('#msgsent_'+ order_id).html('');
    $('#msgrcvd_'+ order_id).html(''); 
    if ( value == 'Partial') 
    { 
        $('input[name=ppat_amount]').show(); 
    } else { 
        $('input[name=ppat_amount]').show(); 
      }
  }
	function submitPaypalAdminForm(order_id, submitButonObject) {
		if (!confirm('Are you sure?')) {
			return false;
		}

		if($('#paypal_admin_form_'+ order_id).serialize().length != 0){
		var paypal_form_data =  $('#paypal_admin_form_'+ order_id).serialize();
		} else {
		var paypal_form_data = $(submitButonObject).closest("form").serialize();
		}
	
		$.ajax({
			url: 'index.php?route=sale/order/ppat_doaction&order_id='+ order_id +'&token=<?php echo $token; ?>',
			type: 'post',
			data: paypal_form_data,
			dataType: 'json',
			beforeSend: function() {
				$('#ppat_submit_'+ order_id).attr('disabled', 'disabled');
			},
			complete: function() {
				$('#ppat_submit_'+ order_id).removeAttr('disabled');
			},
			success: function(json) {
				$('.success, .warning').remove();

				if (json['error']) {
          var json_error = json['error'];
          json_error = json_error.replace('Failure:', '<strong style="color:red;">Failure:</strong>');
          json_error = json_error.replace('Error:', '<strong style="color:red;">Error:</strong>');
          json_error += ' &nbsp;<button class="btn btn-primary btn-sm" id="paypal_admintool_see_details_'+ order_id +'">See Details</button>';
					$('#ppat_response_'+ order_id).html('<div class="warning" style="display: none;">' + json_error + '</div>');

					$('.warning').fadeIn('slow');
				}

				if (json['success']) {
                  var json_success = json['success'];
                  json_success = '<strong style="color:green;">Success: </strong>' + json_success;
	                $('#ppat_response_'+ order_id).html('<div class="success" style="display: none;">' + json_success + '</div>'); 

					$('.success').fadeIn('slow');
				}

				if (json['sent']) {
					$('#msgsent_'+ order_id).html(json['sent']);
          $('#msgsent_'+ order_id).hide();
				}

				if (json['rcvd']) {
					$('#msgrcvd_'+ order_id).html(json['rcvd']);
          $('#msgrcvd_'+ order_id).hide();
				}

        $('#paypal_admintool_see_details_'+ order_id).on('click', function() {
            $(this).remove();
            $('#msgsent_'+ order_id).fadeIn('slow');
            $('#msgrcvd_' + order_id).fadeIn('slow');
        });

			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
			//--></script>
<?php echo $footer; ?>
