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
  <div class="container-fluid">
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
							<!-- Manufacturer -->
							<div class="form-group">	
								<label class="control-label" for="input-manufacturer">Manufacturers</label>
								<div class="col-md-4" id="filter_man_id">
								<a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo "Select All"; ?></a> /  
								<a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo "Select none"; ?></a>
								<div class="well well-sm" style="height: 150px;width:250px; overflow: auto;">
								<?php $modify = explode(',',$filter_manufacturer); ?>
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
						 <!-- /Manufacturer -->
              <div class="form-group hidden">
                <label class="control-label" for="input-customer"><?php echo $entry_customer; ?></label>
                <input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" placeholder="<?php echo $entry_customer; ?>" id="input-customer" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
							<div class="form-group">
                <label class="control-label" for="input-model"><?php echo $entry_model; ?></label>
                <input type="text" name="filter_model" value="<?php echo $filter_model; ?>" placeholder="<?php echo $entry_model; ?>" id="input-model" class="form-control" />
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
                  <?php if ($order_status['order_status_id'] == $filter_order_status) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-date-added"><?php echo $entry_date_added; ?></label>
               
                  <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control daterange" />
                  
              </div>
              <div class="form-group">
                <label class="control-label" for="input-date-modified"><?php echo $entry_date_modified; ?></label>
               
                  <input type="text" name="filter_date_modified" value="<?php echo $filter_date_modified; ?>" placeholder="<?php echo $entry_date_modified; ?>" data-date-format="YYYY-MM-DD" id="input-date-modified" class="form-control daterange" />
                  
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
                  <td class="text-right"><?php if ($sort == 'o.order_id') { ?>
                    <a href="<?php echo $sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_order_id; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_order; ?>"><?php echo $column_order_id; ?></a>
                    <?php } ?></td>
                  <td>
                    Store
                  </td>
                  <td>
                    Manufacturer
                  </td>
                  <td class="text-left"><?php if ($sort == 'status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
                 <td>Number Of Items</td>
                 <td>Pending Backorders</td>
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
                  <td class="text-right"><?php echo $order['order_id']; ?></td>
                  <td class="text-right"><?php echo "Vendor"; ?></td>
                  <td class="text-right"><?php foreach($order['manufacturers'] as $manuf){ echo $manuf['name'].",";  } ?></td>
                  <!-- Order Status -->
									<td class="text-left col-md-2" style="background-color:#<?php echo $order['order_status_color'];?>">
									<div class="status-edit" onclick="selectStatus(<?php echo $order['order_id']; ?> , '<?php echo $order['status']; ?>', '<?php echo $order['sales_person']; ?>');" id="status-<?php echo $order['order_id']; ?>"><?php echo $order['status']; ?></div>
									<!-- /Order Status -->
                  <td class="text-left"><?php echo $order['no_of_items']; ?></td>
                  <td class="text-left"><?php echo $order['pending_backorders']; ?></td>
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
									<a href="<?php echo $order['pdfinv_packing']; ?>" data-toggle="tooltip" title="Packing Slip" class="btn btn-warning" target="_blank"><i class="fa fa-file-text-o"></i></a>
									<a href="<?php echo $order['link_pdf_order_request']; ?>" data-toggle="tooltip" title="PDF Order Request" class="btn btn-warning" target="_blank"><i class="fa fa-file-text-o"></i></a>
                  <a href="<?php echo $order['copy']; ?>" data-toggle="tooltip" title="<?php echo $button_copy; ?>" class="btn btn-warning" onclick="return confirm('Do you really want to copy this order?') ? true : false;"><i class="fa fa-copy"></i></a>
                  <a onclick="showProducts(<?php echo $order['order_id']; ?>);" data-toggle="tooltip" title="Quick View" class="btn btn-success "><i class="fa fa-th-list"></i></a>
                    <button type="button" value="<?php echo $order['order_id']; ?>" id="button-delete<?php echo $order['order_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></button></td>
                </tr>
                <!-- Quick View -->
                
								<tr id="products_<?php echo $order['order_id']; ?>" class="hidden">
									<td colspan="1"></td>
									<td colspan="9">
									<div class="panel-group" id="accordion_<?php echo $order['order_id']; ?>" role="tablist" aria-multiselectable="true">
										<div class="panel panel-default">
										<div class="panel-heading" role="tab" id="heading_histories_<?php echo $order['order_id']; ?>">
											<h4 class="panel-title">
											<a role="button" data-toggle="collapse" data-parent="#accordion_<?php echo $order['order_id']; ?>" href="#collapse_history_<?php echo $order['order_id']; ?>" aria-expanded="true" aria-controls="collapse_products_<?php echo $order['order_id']; ?>">
											History
											</a>
											</h4>
										</div>
										<div id="collapse_history_<?php echo $order['order_id']; ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading_history_<?php echo $order['order_id']; ?>">
										<div class="panel-body" id="panel-body-history<?php echo $order['order_id']; ?>">
											<?php if ($order['histories']) { ?>
												<h4 class="text-primary">History</h4>
												<table class="table table-bordered">
													<thead>
													<tr>
														<td class="text-left">Date Added</td>
														<td class="text-left">Comment</td>
														<td class="text-left">Status</td>
														<td class="text-left">Customer Notified</td>
													</tr>
													</thead>
													<tbody>
													<?php foreach ($order['histories'] as $history) { ?>
													<tr>
														<td class="text-left"><?php echo $history['date_added']; ?></td>
														<td class="text-left"><?php echo $history['comment']; ?></td>
														<td class="text-left"><?php echo $history['status']; ?></td>
														<td class="text-left"><?php echo $history['notify']; ?></td>
													</tr>
													<?php } ?>
													</tbody>
												</table>
										<?php } ?>
										</div>
										</div>
										<div class="panel panel-default">
										<div class="panel-heading" role="tab" id="heading_customer_info_<?php echo $order['order_id']; ?>">
											<h4 class="panel-title">
											<a role="button" data-toggle="collapse" data-parent="#accordion_<?php echo $order['order_id']; ?>" href="#collapse_customer_info_<?php echo $order['order_id']; ?>" aria-expanded="false" aria-controls="">
											Customer Details
											</a>
											</h4>
										</div>
										<div id="collapse_customer_info_<?php echo $order['order_id']; ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading_customer_info_<?php echo $order['order_id']; ?>">
											<div class="panel-body">
											<table class="table table-striped table-condensed">
												<thead>
													<tr>
														<td class="text-left">Customer</td>
														<td class="text-left">Payment Details</td>
														<td class="text-left">Shipping Details</td>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>
															<ul class="list-group">
																<li class="list-group-item"><?php echo "Customer" . ' ' . $order['customer']; ?></li>
																<li class="list-group-item"><?php echo "E-Mail" . ' ' . $order['email']; ?></li>
																<li class="list-group-item"><?php echo $order['address']; ?></li>
																<?php if (!empty($order['telephone'])) { ?>
																<li class="list-group-item"><?php echo "Telephone" . ' ' . $order['telephone']; ?></li>
																<?php } ?>
																<?php if (!empty($order['fax'])) { ?>
																<li class="list-group-item"><?php echo "Fax" . ' ' . $order['fax']; ?></li>
																<?php } ?>
															</ul>
														</td>
														<td>
															<ul class="list-group">
																<li class="list-group-item"><?php echo "Payment Method" . ' <b>' . $order['payment_method'] . '</b>'; ?></li>
																<li class="list-group-item"><?php echo "First Name" . ' <b>' . $order['payment_firstname'] . '</b>'; ?></li>
																<li class="list-group-item"><?php echo "Last Name" . ' <b>' . $order['payment_lastname'] . '</b>'; ?></li>
																<?php if (!empty($order['payment_company'])) { ?>
																<li class="list-group-item"><?php echo "Company" . ' <b>' . $order['payment_company'] . '</b>'; ?></li>
																<?php } ?>
																<li class="list-group-item"><?php echo "Address 1" . ' <b>' . $order['payment_address_1'] . '</b>'; ?></li>	
																<?php if (!empty($order['payment_address_2'])) { ?>
																<li class="list-group-item"><?php echo "Address 2" . ' <b>' . $order['payment_address_2'] . '</b>'; ?></li>
																<?php } ?>
																<li class="list-group-item"><?php echo "City" . ' <b>' . $order['payment_city'] . '</b>'; ?></li>
																<li class="list-group-item"><?php echo "Postcode" . ' <b>' . $order['payment_postcode'] . '</b>'; ?></li>
																<?php if (!empty($order['payment_zone'])) { ?>
																<li class="list-group-item"><?php echo "Region / State" . ' <b>' . $order['payment_zone'] . '</b>'; ?></li>
																<?php } ?>
																<li class="list-group-item"><?php echo "Country" . ' <b>' . $order['payment_country'] . '</b>'; ?></li>
															</ul>
														</td>
														<td>
															<ul class="list-group">
																<li class="list-group-item"><?php echo "Shipping Method" . ' <b>' . $order['shipping_method'] . '</b>'; ?></li>
																<li class="list-group-item"><?php echo "First Name" . ' <b>' . $order['shipping_firstname'] . '</b>'; ?></li>
																<li class="list-group-item"><?php echo "Last Name" . ' <b>' . $order['shipping_lastname'] . '</b>'; ?></li>
																<?php if (!empty($order['shipping_company'])) { ?>
																<li class="list-group-item"><?php echo "Company" . ' <b>' . $order['shipping_company'] . '</b>'; ?></li>
																<?php } ?>
																<li class="list-group-item"><?php echo "Address 1" . ' <b>' . $order['shipping_address_1'] . '</b>'; ?></li>	
																<?php if (!empty($order['shipping_address_2'])) { ?>
																<li class="list-group-item"><?php echo "Address 2" . ' <b>' . $order['shipping_address_2'] . '</b>'; ?></li>
																<?php } ?>
																<li class="list-group-item"><?php echo "City" . ' <b>' . $order['shipping_city'] . '</b>'; ?></li>
																<li class="list-group-item"><?php echo "Postcode" . ' <b>' . $order['shipping_postcode'] . '</b>'; ?></li>
																<?php if (!empty($order['shipping_zone'])) { ?>
																<li class="list-group-item"><?php echo "Region / State" . ' <b>' . $order['shipping_zone'] . '</b>'; ?></li>
																<?php } ?>
																<li class="list-group-item"><?php echo "Country" . ' <b>' . $order['shipping_country'] . '</b>'; ?></li>
															</ul>
														</td>
													</tr>
													<?php if (!empty($order['comment'])) { ?>
													<tr>
														<td colspan="6" class="bg-info"><?php echo '<b>' . "Comment" . ':</b> <br />' . $order['comment'] ; ?></td>
													</tr>
													<?php } ?>
												</tbody>
											</table>
											</div>
										</div>
										</div>
										<div class="panel panel-default">
										<div class="panel-heading" role="tab" id="heading_products_<?php echo $order['order_id']; ?>">
											<h4 class="panel-title">
											<a role="button" data-toggle="collapse" data-parent="#accordion_<?php echo $order['order_id']; ?>" href="#collapse_products_<?php echo $order['order_id']; ?>" aria-expanded="true" aria-controls="collapse_products_<?php echo $order['order_id']; ?>">
												Products
											</a>
											</h4>
										</div>
										<div id="collapse_products_<?php echo $order['order_id']; ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading_products_<?php echo $order['order_id']; ?>">
											<div class="panel-body">
											<table class="table table-striped table-condensed">
												<thead>
													<tr>
														<td class="text-left"></td>
														<td class="text-left">Product</td>
														<td class="text-left">Model</td>
														<td class="text-right">Quantity</td>
														<td class="text-right"><?php echo 'Quantity Shipped'; ?></td>
														<td class="text-right">Unit Price</td>
														<td class="text-right">Total</td>
													</tr>
												</thead>
												<?php foreach ($order['products'] as $product) { ?>
													<tr>
														<td class="text-center">
															<div class="product-image">
															<a class="thumbnail" href="<?php echo $product['image']; ?>" title="<?php echo $product['name']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a>
															</div>
														</td>
														<td class="text-left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
														<?php foreach ($product['option'] as $option) { ?>
														<br />
														<?php if ($option['type'] != 'file') { ?>
														&nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
														<?php } else { ?>
														&nbsp;<small> - <?php echo $option['name']; ?>: <a href="<?php echo $option['href']; ?>"><?php echo $option['value']; ?></a></small>
														<?php } ?>
														<?php } ?></td>
														<td class="text-left"><?php echo $product['model']; ?></td>
														<td class="text-right"><?php 
																	if($product['unit']) { 
																				if(trim($product['getDefaultUnitDetails']['name']) != trim($product['unit']['unit_value_name'])){
																					echo number_format(($product['quantity'] * $product['unit']['convert_price']),2).' '.$product['unitdatanames']['unit_plural'].'<br /> = '.$product['quantity'] .' '.$product['unit']['unit_value_name'];
																			}else{
																							echo $product['quantity'] .' '.$product['unitdatanames']['unit_plural']; 
																					}
																	}else{ 
																		echo $product['quantity'];
															} 
														?></td>
														<td class="text-right"><?php echo $product['quantity_supplied']; ?></td>
														<td class="text-right">
														<?php 
																	$product_price = str_replace('$','',$product['price']);
																	
																	if($product['unit']){ 
																			$product_price = number_format(((float)$product_price/(float)$product['unit']['convert_price']),2); 
																			echo '$ '.(float)$product_price.'<br> Per '.$product['unitdatanames']['unit_singular'];
																	}else{ 
																			echo '$ '.$product_price; 
																	}
															?>
												</td>
												<td class="text-right"><?php echo '$ '.$product_price * $product['quantity_supplied']; ?></td>
													</tr>
												<?php } ?>
											</table>
											</div>
										</div>
										</div>
									
									</div> <!-- // Collapse -->

									<!-- BackOrder -->
										<div class="panel panel-default">
											<div class="panel-heading">
												<h3 class="panel-title"><i class="fa fa-shopping-cart"></i> Backorder Summary</h3>
											</div>
											<div class="panel-body">
												<div class="backorder-summary"></div>
											</div>
										</div>
									<!-- /BackOrder -->
									</td>
								</tr>

                <!-- /Quick View -->
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
	url = 'index.php?route=sale/incoming&token=<?php echo $token; ?>';

	var filter_order_id = $('input[name=\'filter_order_id\']').val();

	if (filter_order_id) {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}

	var filter_model = $('input[name=\'filter_model\']').val();

	if (filter_model) {
		url += '&filter_model=' + encodeURIComponent(filter_model);
	}

  var manArray = [];
	var filter_m_id = $("#filter_man_id input:checked").each(function() {
		manArray.push($(this).val());
	});
	var mselected;
	mselected = manArray.join(',') ;
	if (mselected.length > 0){
		url += '&filter_manufacturer=' + encodeURIComponent(mselected);
	}

	/* var filter_customer = $('input[name=\'filter_customer\']').val();

	if (filter_customer) {
		url += '&filter_customer=' + encodeURIComponent(filter_customer);
	} */
	
	/* var filter_pos = $('select[name=\'filter_pos\']').val();

	if (filter_pos != 0) {
		url += '&filter_pos=' + encodeURIComponent(filter_pos);
	} */

	var filter_order_status = $('select[name=\'filter_order_status\']').val();

	if (filter_order_status != '*') {
		url += '&filter_order_status=' + encodeURIComponent(filter_order_status);
	}

	/* var filter_total = $('input[name=\'filter_total\']').val();

	if (filter_total) {
		url += '&filter_total=' + encodeURIComponent(filter_total);
	} */

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
                      url: "index.php?route=sale/incoming/getOrderImages&token=<?php echo $token; ?>&order_id="+order_ids,
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
						htmlImages += '<div id="maindiv"><div id="formdiv"><h2>Multiple Image Upload For Orders</h2><form enctype="multipart/form-data" action="index.php?route=sale/incoming/uploadOrderImages&token=<?php echo $token; ?>&order_id='+order_ids+'" method="post"><p>Only JPEG,PNG,JPG,PDF and ZIP files are supported.</p><div id="filediv"><input name="file[]" type="file" id="file"/></div><input type="button" id="add_more" class="upload" value="Add More Files"/><input type="hidden" name="order_id"  value="'+order_ids+'"/><input type="submit" value="Upload File" name="submit" id="upload" class="upload"/></form></div></div>';
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
          
<?php echo $footer; ?>
<script type="text/javascript">
function showProducts(order_id) {
	$("#products_" + order_id).toggleClass('hidden');	
}
</script>

<script type="text/javascript"><!--
				$('.backorder-summary').load('index.php?route=sale/backorder/backorder_summary&token=<?php echo $token; ?>&limit=3');
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
      <script type="text/javascript">
	$('.daterange').daterangepicker();
<?php  if (empty($filter_date_modified) ){  echo "$('#input-date-modified').val('');"; } ?>
	
<?php  if (empty($filter_date_added) ){  echo "$('#input-date-added').val('');"; } ?>
</script> 
