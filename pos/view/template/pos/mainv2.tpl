<?php echo $header; ?>
<link rel="stylesheet" type="text/css" href="view/stylesheet/pos/posv2.css?v=<?php echo time();?>" />
<link rel="stylesheet" type="text/css" href="view/stylesheet/pos/iosbadge.css" />
<link rel="stylesheet" type="text/css" href="view/stylesheet/pos/jquery-ui.css" />
<style>
	.custom_active_color{
		background: #337ab7 !important;
	}
	#save_custom_button{
		width: 31% !important;

    height: 20px !important;
	}

.upload{
    background-color:red;
    border:1px solid red;
    color:#fff;
    border-radius:5px;
    padding:6px;
    text-shadow:1px 1px 0 green;
    box-shadow:2px 2px 15px rgba(0,0,0,.75)
}
.upload:hover{
    cursor:pointer;
    background:#c20b0b;
    border:1px solid #c20b0b;
    box-shadow:0 0 5px rgba(0,0,0,.75)
}
.mainResale{
	margin:10px auto;
	padding:10px;
	font-family:'Droid Sans',sans-serif
}
.formResale{
	width:300px;
	float:left;
	text-align:center;
	height: 160px;
}
.fileResale{
	color:green;
	padding:5px;
	border:1px dashed #123456;
	background-color:#f9ffe5;
}
.uploadResale{
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
.scope-help.show-title:after {
	position: absolute;
	white-space: pre;
	content: 'Exact Match: " OR \0027 \A\A Or: Space \A\A And: :::';
	padding:10px;
	border:1px solid #ccc;
	top:50px;
	right:2%;
	background: white;
	color: #000;
	text-transform: capitalize;
}
</style>
<?php // echo $column_left; ?>

<div id="oc_content" class="container-fluid" style="padding: 0;">
	<div id="divWrap" class="wrapper">
	<div class="header">
		 <div class="sub-title"></div>
		 <div class="select_wrap">
			<select name="work_mode_dropdown" id="work_mode_dropdown" class="select_type">
			  <option value="sale" selected="selected"><?php echo $text_workmode_sale; ?></option>
			  <option value="return_order"><?php echo $text_workmode_return_with_order; ?></option>
			  <!-- <option value="return_without_order"><?php echo $text_workmode_return_no_order; ?></option> -->
			  <!-- <option value="quote"><?php echo $text_workmode_quote; ?></option>
			  <option value="cash_in"><?php echo $text_cash_in; ?></option>
			  <option value="cash_out"><?php echo $text_cash_out; ?></option> -->
			  <option value="stock_manager"><?php echo $text_stock_manager; ?></option>
			  <option value="go_to_admin">Go To Admin</option>
			</select>
		</div>
		<div class="message success" >
			<div class="icon"></div>
			<p style="position:relative;left:-193px"></p>
			<span style="position:relative;left:-170px;color:#000000;font-size:15px">Order # <?php echo $order_id_text; ?></span>
			<span class="user-info">
				<span class="user-name"><?php echo $user; ?> @ COUNTER-1</span>
				<span class="pos-date">
					<span id="header_week"><?php echo date("D");?></span>,
					<span id="header_date"><?php echo date("d");?></span>
					<span id="header_month"><?php echo date("F");?></span>
					<span id="header_year"><?php echo date("Y");?></span>&nbsp;
					<span id="header_hour"><?php echo date("h:m");?></span>
					<span id="header_apm"><?php echo date("a");?></span>
				</span>
			</span>

		</div>

		<a class="menu-toggle"></a>
	</div>
	<div class="main-container">
    	<div style="display: none;" id="stock_manager_panel" class="col-md-12">
		<div class="tabbable-panel">
		<div class="tabbable-line">
		<ul class="nav nav-tabs">
			<li>
				<a href="#tab_product_information" data-toggle="tab" id="tab_product_information_tab" onclick="cancelEditProduct();">Edit Product Information </a>
			</li>
			<li>
				<a href="#tab_update_stock" data-toggle="tab" onclick="cancelEditProduct();" id="update_stock_tab_id">Update Stock </a>
			</li>
			<li>
				<a href="#tab_incoming_orders" data-toggle="tab" onclick="loadIncomingOrders();">Incoming Orders </a>
			</li>
			<li>
				<a href="#tab_backorders" data-toggle="tab" onclick="loadBackOrders();">BackOrders </a>
			</li>
            <li class="active">
				<a href="#tab_returns" data-toggle="tab" id="tab_returns_tab" onclick="loadReturns();">Returns </a>
			</li>
			<li>
				<a href="#tab_sales_report" data-toggle="tab">Sales Report </a>
			</li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane" id="tab_product_information"></div>
			<div class="tab-pane" id="tab_update_stock"></div>
			<div class="tab-pane" id="tab_incoming_orders" ></div>
			<div class="tab-pane" id="tab_backorders" ></div>
            <!-- Returns -->
            <div class="tab-pane active" id="tab_returns">

			</div>
            <!-- End Returns -->
			<div class="tab-pane" id="tab_sales_report">

			</div>
		</div>
		</div>
		</div>
		</div>

		<!-- Trigger the modal with a button -->
	<button type="button" id="editProductModal" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#editProductPage">Open Modal</button>

	<!-- Modal -->
	<div id="editProductPage" class="modal fade bd-example-modal-xl" role="dialog">
	  <div style="width: 70%;" class="modal-dialog modal-xl">

	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title">Edit Product Information</h4>
	      </div>
	      <div class="modal-body">

	      </div>
	      <div class="modal-footer">
	        <button type="button" onclick="updateProductInIframe();" class="btn btn-success" id="update_product_inframe">Update</button>
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	      </div>
	    </div>

	  </div>
	</div>
	<!--right-container-start-->
	<div class="right-container">

		<!--product-container-start-->
		<div class="prdct-container" id="main-container">
			<div class="prdct-header">
				<!-- Use below heading for return for order -->
				<div class="prdct-header-top return-order" id="return_control" style="display:none"></div>
				<div class="prdct-header-top" id="add_product_control">
					<div class="input-box">
						<input type="search" autocomplete="off" id="search" class="search_products" name="filter_product"  placeholder="<?php echo $text_search_placeholder; ?>" >
						<input style="display: none;" type="search" autocomplete="off" id="search" class="search_products" name="filter_product_for_edit"  placeholder="<?php echo $text_search_placeholder; ?>" >
						<input style="display: none;" type="search" autocomplete="off" id="search" class="search_products" name="filter_product_update_stock"  placeholder="<?php echo $text_search_placeholder; ?>" >
						<input style="display: none;" type="search" autocomplete="off" id="search" class="search_products" name="filter_product_sales_report"  placeholder="<?php echo $text_search_placeholder; ?>" >
					</div>


					<a onclick="searchSettings();" class="settings"></a>
					<a onclick="quickSale();" class="non-catalog"><?php echo $text_title_quick_sale; ?></a>
					<a onclick="openScanner();" class="non-catalog" style="color:#FFFFFF;background:#ecae1c;"><span class="fa fa-scanner"></span>Scan Code</a>
					<a onclick="openScanner();" class="non-catalog onlyone" id="scancode" style="color:#FFFFFF;background:#ecae1c;float:left !important;"><span class="fa fa-scanner"></span>Scan Code</a>
					<a onclick="openScannerproductedit();" class="non-catalog onlyone" id="scancode_product_for_edit" style="color:#FFFFFF;background:#ecae1c;float:left !important;display:none;"><span class="fa fa-scanner"></span>Scan Code</a>
					<a onclick="openScannerupdatestock();" class="non-catalog onlyone" id="scancode_product_update_stock" style="color:#FFFFFF;background:#ecae1c;float:left !important;display:none;"><span class="fa fa-scanner"></span>Scan Code</a>
					<a onclick="openScannersalereport();" class="non-catalog onlyone" id="scancode_product_sales_report" style="color:#FFFFFF;background:#ecae1c;float:left !important;display:none;"><span class="fa fa-scanner"></span>Scan Code</a>
				</div>
			</div>
			<div class="product-box-outer" id="browse_list">
				<input type="hidden" name="current_product_id" value="0" />
				<input type="hidden" name="current_product_name" value="" />
				<input type="hidden" name="current_product_weight_price" value="" />
				<input type="hidden" name="current_product_weight_name" value="" />
				<input type="hidden" name="current_product_hasOption" value="" />
				<input type="hidden" name="current_product_price" value="" />
				<input type="hidden" name="current_product_tax" value="" />
				<input type="hidden" name="current_product_points" value="" />
				<input type="hidden" name="current_product_image" value="" />
				<?php if (!empty($browse_items)) { foreach ($browse_items as $browse_item) { ?>
					<?php if ($browse_item['type'] == 'C') { ?>
						<a onclick="showCategoryItems('<?php echo $browse_item['category_id']; ?>')" class="product-box product-folder">
							<span class="product-box-img">
								<span class="product-box-frame-wrap">
									<span class="product-box-frame">
										<img src="<?php echo $browse_item['image']; ?>"  alt="">
									</span>
									<span class="product-count"><?php echo $browse_item['total_items']; ?></span>
								</span>
							</span>
							<span class="product-box-prod">
								<span class="product-box-prod-title"><?php echo $browse_item['name']; ?></span>
							</span>
						</a>
					<?php } else { ?>
						<a onclick="selectProduct(<?php echo $browse_item['product_id']; ?>)" class="product-box product-item">
							<span class="product-box-img">
								<span class="product-box-frame-wrap">
									<span class="product-box-frame">
										<img src="<?php echo $browse_item['image']; ?>"  alt="">
									</span>
									<span class="product-count"><?php echo $browse_item['total_items']; ?></span>
								</span>
							</span>
							<span class="product-box-prod">
								<span class="product-box-prod-title"><?php echo $browse_item['name']; ?></span>
								<span class="product-box-prod-price"><?php echo $browse_item['price_text']; ?></span>
							</span>
						</a>
					<?php } ?>
				<?php } } ?>
			</div>

			<?php
				$order_status   = '';
				$order_comment  = '';
				$order_customer = '';
				$order_discount = '';

				$order_status   = (!empty($order_status_name) ? $order_status_name : 'Change Order Status');
				$order_comment  = $comment;

				//$order_customer = $payment_company.", ".$payment_firstname." ".$payment_lastname.", ".$telephone.", ".$email.", ".$payment_address_1." ".$payment_address_2.", ".$payment_city." ".$payment_zone." ".$payment_country;
$order_customer = "<b>".$payment_firstname." ".$payment_lastname."</b> (".$total_orders."), ".$email.", ".$telephone;
				//$order_customer = htmlentities($order_customer);

				$order_discount = $c_customer_group;
			?>
			<div class="control-area" style="height:auto;">
				<a class="button lg" style="background:#71f071; height:auto; display: flex;" id="btncustomer" onClick="changeOrderCustomer();">
					<span class="info-text" id="order_customer_name"><?php echo $order_customer; ?></span>
				</a>

				<a class="button lg" style="background:#FF8D4E; height:auto; display: flex;" id="btnorderstatus" onClick="changeOrderStatus();">
					<span style="font-weight:bold;text-align:center;">Order Status:</span>
					<span class="info-text" style="margin:0;" id="order_status_name"><?php echo $order_status; ?></span>

				</a>
				<!--<a class="button" style="background:#8BFE8B" id="btndiscount" onClick="setDiscount();">-->
				<!--	<span style="font-weight:bold;text-align:center;">Discount</span>-->
				<!--	<span class="info-text" id="order_discount_name"><?php echo $order_discount; ?></span>-->
				<!--</a>-->
				<a class="button lg" style="background:#E6ECE6; height:auto;display: flex;" id="btnordernote" onClick="changeOrderComment();">
					<span style="font-weight:bold;text-align:center;">Order Notes</span>
					<span class="info-text" id="order_comment"><?php echo $order_comment; ?></span>
				</a>
			</div>
		</div>
	<div class="prdct-container" id="updateStockContainer" style="display:none;margin-left:30px;">
		<div class="row">
			<div class="col-sm-2">

			</div>
		</div>
	</div>
	<div class="prdct-container cart-container">
		<div class="prdct-header">
			<!-- Use below heading for return for order -->
			<div class="prdct-header-top" id="add_product_control">
<span style="color:#000000;font-size:15px">Order # <?php echo $order_id_text; ?></span>
				<!--<a onclick="openScanner();" class="non-catalog" style="color:#FFFFFF;background:#ecae1c;float:left !important;"><span class="fa fa-scanner"></span>Scan Code</a>-->

				<a onclick="getOrderList();" class="non-catalog" style="color:#FFFFFF;background:#000000"><span class="fa fa-shopping-cart"></span>VIEW ALL ORDERS</a>
			</div>
		</div>
		<link type="text/css" href="view/stylesheet/product_labels/stylesheet.css" rel="stylesheet" media="screen" />
		<script type="text/javascript" src="view/javascript/product_labels/pdfobject.js"></script>
		<script type="text/javascript" src="view/javascript/product_labels/jquery.colorPicker.js"></script>
		<script type="text/javascript" src="view/javascript/product_labels/product_lebel_edit.js"></script>

		<div style="display: none;" class="cart-title-bg">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td class="one" align="center" valign="middle"><span class="name"><?php echo $text_items_in_cart; ?></span> <span class="product-count" id="items_in_cart"><?php echo $items_in_cart; ?></span></td>
					<td class="two two-sub" align="left" valign="middle">&nbsp;</td>
					<td class="three" align="center" valign="middle"><?php echo $text_subtotal; ?> <i style="font-size: 20px; cursor: pointer;" onclick="$('.cart-outer-scroller,.cart-footer').toggle();" class="fa fa-shopping-cart"></i></td>
					<td class="four" align="center" valign="middle">&nbsp;</td>
				</tr>
			</table>
		</div>
		<div class="cart-outer-scroller">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<?php $product_row = 0; ?>
				<tbody id="product">
				<?php foreach ($products as $product) { $tr_class = ($product_row % 2) ? 'even' : 'odd'; ?>

					<tr id="product-row<?php echo $product_row; ?>" class="<?php echo $tr_class; ?>">
						<input type="hidden" name="order_product[<?php echo $product_row; ?>][order_product_id]" value="<?php echo $product['order_product_id']; ?>" />
					<input type="hidden" name="order_product[<?php echo $product_row; ?>][product_id]" value="<?php echo $product['product_id']; ?>" />
					<input type="hidden" name="order_product[<?php echo $product_row; ?>][quantity]" value="<?php echo $product['quantity']; ?>" />
					<input type="hidden" name="order_product[<?php echo $product_row; ?>][price]" value="<?php echo $product['price']; ?>" />
					<input type="hidden" name="order_product[<?php echo $product_row; ?>][product_discount_type]" value="<?php echo $product['discount_type']; ?>" />
					<input type="hidden" name="order_product[<?php echo $product_row; ?>][product_discount_value]" value="<?php echo $product['discount_value']; ?>" />
					<input type="hidden" name="order_product[<?php echo $product_row; ?>][product_normal_price]" value="<?php echo $product['normal_price']; ?>" />

						<td align="left" valign="middle" class="two">
							<span class="product-name">
								<span class="raw-name" style="font-weight:bold;text-decoration:none;cursor:pointer;" onclick="showProductDetails(<?php echo $product['product_id'] ?>)" id="order_product[<?php echo $product_row; ?>][order_product_display_name]"><?php echo ($product_row+1).'- '.$product['model']; ?></span>
								<?php if ($product['model']) { ?>
									<br />
									<small>  <?php echo $product['name']; ?> &nbsp;</small>(<small><?php echo round($product['labour_cost'],4); ?> </small> + <small><?php echo round($product['unique_option_price'],6); ?></small>)
								<?php } ?>
								<?php foreach ($product['option'] as $option) { ?>
									<br />
									<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
									<input type="hidden" name="order_product[<?php echo $product_row; ?>][order_option][<?php echo $option['product_option_id']; ?>][product_option_id]" value="<?php echo $option['product_option_id']; ?>" />
									<?php if ($option['type'] == 'checkbox') {?>
										<input type="hidden" name="order_product[<?php echo $product_row; ?>][order_option][<?php echo $option['product_option_id']; ?>][product_option_value_id][<?php echo $option['product_option_value_id'];?>]" value="<?php echo $option['value']; ?>" />
									<?php } else {?>
										<input type="hidden" name="order_product[<?php echo $product_row; ?>][order_option][<?php echo $option['product_option_id']; ?>][product_option_value_id]" value="<?php echo $option['product_option_value_id']; ?>" />
									<?php }?>
									<input type="hidden" name="order_product[<?php echo $product_row; ?>][order_option][<?php echo $option['product_option_id']; ?>][value]" value="<?php echo $option['value']; ?>" />
									<input type="hidden" name="order_product[<?php echo $product_row; ?>][order_option][<?php echo $option['product_option_id']; ?>][type]" value="<?php echo $option['type']; ?>" />
								<?php } ?>
								<?php if (!empty($product['sns'])) { foreach ($product['sns'] as $product_sn) { ?>
									<br />
									&nbsp;<small> - SN: <?php echo $product_sn['sn']; ?></small>
									<input type="hidden" name="order_product[<?php echo $product_row; ?>][product_sns][<?php echo $product_sn['product_sn_id']; ?>]" value="<?php echo $product_sn['product_sn_id']; ?>" />
								<?php }}?>
								<?php if ((int)$product['weight_price']) { ?>
									<br />
									&nbsp;<small> - <?php echo $product['weight_name']; ?>: <?php echo $product['weight']; ?></small>
									<input type="hidden" name="order_product[<?php echo $product_row; ?>][weight_price]" value="<?php echo $product['weight_price']; ?>" />
									<input type="hidden" name="order_product[<?php echo $product_row; ?>][weight]" value="<?php echo $product['weight']; ?>" />
								<?php } ?>
								<?php if( isset($product['converstion_line']) ) { ?>
									<br />&nbsp;<small id="convertion_line<?php echo $product_row; ?>" style="color:#DD0205; font-weight:bold;"><?php echo $product['converstion_line']; ?></small>
								<?php } ?>
							</span>
							<span  style="background:#dddee6; padding:5px;">

							<?php if ( $product['price'] < $product['normal_price']) {
								 if($product['converstion_line'] !='') {
									$percent = number_format((1 - $product['convert_price_raw'] / $product['normal_price']) * 100,0);
								 } else {
									$percent = number_format((1 - $product['price'] / $product['normal_price']) * 100,0);
								 }
								?>
								<a class="quantity_anchor_<?php echo $product_row; ?>" data-unit="<?=$product['unit']['unit_plural']?>" onclick="changeQuantity(this,'<?=$product_row?>')"> <span><?=$product['quantity'].'</span> '.$product['unit']['unit_plural']?> </a>
								&nbsp; X &nbsp;
								 <a id="price_anchor_<?php echo $product_row; ?>" onclick="changePrice(this,'<?=$product_row?>');">
								<?= $product['price'].' / '.$product['unit']['unit_singular'].' ('.$percent.'% off)'?>
								</a>
								<!--<span id="discounted_price_for_percent_<?php echo $product_row; ?>"> <?php echo $product['converstion_line'] !='' ? $product['convert_price'] : $product['price_text']; ?> (<?php echo round($percent); ?>% off!)</span>-->
							<?php } else { ?>
								<a class="quantity_anchor_<?php echo $product_row; ?>" data-unit="" onclick="changeQuantity(this,'<?=$product_row?>')"><?=$product['quantity'] ?></a>
								&nbsp; X &nbsp;
								 <a id="price_anchor_<?php echo $product_row; ?>" onclick="changePrice(this,'<?=$product_row?>');">
								<?= $product['price']?>
								</a>
							<?php } ?>

							</span>
						</td>
						<td align="center" valign="middle" class="one">
							<?php if (!empty($product['discount_type'])) { ?>
								<a onclick="$('#price_anchor_<?php echo $product_row; ?>').closest('span').trigger('click');" class="cart-link">
									<span class="">
										<strike><?php echo $product['text_before_discount']; ?></strike><br/>
										<small>(<?php echo $text_discount; ?>: <?php echo $product['text_discount']; ?>)</small><br/>
										<?php echo $product['text_after_discount']; //echo $product['total_text']; ?>
									</span>
								</a>
								<input type="hidden" name="order_product[<?php echo $product_row; ?>][product_total_text]" value="<?php echo $product['text_before_discount']; ?>" />
							<?php } else {?>
								<?php if ( $product['price'] < $product['normal_price']) { ?>
									<a onclick="$('#price_anchor_<?php echo $product_row; ?>').closest('span').trigger('click');" class="cart-link"><span class="" id="total_text_only-<?php echo $product_row; ?>"><strike><?php echo $product['normal_price_total_text']; ?></strike></span></a>
									<span class="product-price" id="normal_total_text_only-<?php echo $product_row; ?>">Your price: <?php echo $product['total_text']; ?></span>
								<?php } else { ?>
									<a onclick="$('#price_anchor_<?php echo $product_row; ?>').closest('span').trigger('click');" class="cart-link">
										<span class="product-price" id="total_text_only-<?php echo $product_row; ?>"> Your price: <?php echo $product['total_text']; ?></span>
									</a>
								<?php } ?>
								<input type="hidden" name="order_product[<?php echo $product_row; ?>][product_total_text]" value="<?php echo $product['total_text']; ?>" />
							<?php } ?>
						</td>
						<td align="center" valign="middle" class="one">
							<span class="cart-round-img-outr" onclick="changeQuantity(this,'<?php echo $product_row; ?>')">
								<img src="<?php echo $product['image']; ?>" class="cart-round-img" alt="">
								<a class="cart-round-qty quantity_anchor_<?php echo $product_row; ?>"><?php echo $product['quantity']; ?></a>
							</span>

						</td>
						<td align="center" valign="middle" class="four">
							<button type="button" style="margin-bottom: 2px;"  data-loading-text="Loading" class="btn btn-danger" onclick="deleteOrderProduct(this)"><i class="fa fa-minus-circle"></i></button>
							<!--<a href="javascript:void(0);" style="margin-bottom: 2px;" data-href="index.php?route=module/product_labels/printOrderProductlabel&product_id=<?php echo $product['product_id']; ?>&token=<?php echo $token;?>" data-productid="<?php echo $product['product_id']; ?>" target="_blank" title="Print Label" class="btn btn-success productlabelpopup"><i class="fa fa-print"></i></a>-->
							<a href="javascript:void(0);" title="Update Stock" onclick="openStockModel(<?php echo $product['product_id']; ?>);" class="btn btn-info"><i class="fa fa-refresh"></i></a>
						</td>
					</tr>
				<?php $product_row++; } ?>
			</tbody>
			</table>
		</div>

	<div class="buttons-wrap">
			<span id="order_only_buttons">
			<a class="btn close-order create_empty_order_btn"><span class="icon"></span>New Order</a>
			<a class="btn void btn-before-after-payment" onclick="saveOrderStatus('<?php echo $void_status_id; ?>');"><span class="icon"></span><?php echo $button_void_order; ?></a>
			<a class="btn park btn-before-after-payment" onclick="saveOrderStatus('<?php echo $parking_status_id; ?>');"><span class="icon"></span><?php echo $button_park_order; ?></a>

			<?php if (!empty($config['enable_auto_payment'])) { ?>
			<a class="btn complete complete-btn" disabled="disabled" onclick="completeOrder();"><span class="icon"></span><?php echo $button_complete_order; ?></a>
			<?php }?>
			</span>
			<a class="btn print btn-before-after-payment" onclick="printReceipt();"><span class="icon"></span><?php echo $text_print; ?></a>
	</div>
	<!-- Process totals -->
	<?php
		$subtotal = 0;
		$shipping = 0;
		$discount = 0;
		$salestax = 0;
		$gratotal = 0;

		$sub_id = '';
		$gra_id = '';
		$shp_id = '';

		if(!empty( $current_order_subtotal )) {
			$subtotal = "$" . number_format((float)$current_order_subtotal, 2, '.', '');
			$discount = $order_discount_amount;
			$sub_id = 'subtotal_total_res';
		}else{
			$subtotal = "$0.00";
			$discount = $order_discount_amount;
			$sub_id = 'subtotal_total';

		}

		if(isset($current_order_shipping)){
			$shipping = "$" . number_format((float)$current_order_shipping, 2, '.', '');
			$shp_id = 'shipping_total_res';
		}else{
			$shipping = "$0.00";
			$shp_id = 'shipping_total';
		}

		$total_text = '';
		if (!empty($totals)) {
			foreach ($totals as $total) {
				if ($total['code'] == 'total') {
					$total_text = $total['text'];
					break;
				}
			}
		}

		$salestax = "$" . number_format((float)$current_order_tax, 2, '.', '');
		$gratotal = $total_text;


	?>

	<div class="cart-results">
		<div class="pay-area-bg">
			<div class="cart-totals">

	<!-- Show cart totals -->
	<div style="margin: 0px 0px 0px 15px;">
		<a class="slabs btn" style="width:23%;background:#ECF916" onClick="showTotals();">
			<span class="slabs-title"><img src="view/image/pos/subtotal.png" height="20px" width="20px" /></span>
			<span class="slabs-value" id="<?php echo $sub_id;?>"><?php echo $subtotal; ?></span>
		</a>
		<a class="slabs btn" style="width:23%;background:#0AE6D6" onClick="changeShippingDetails();">
			<span class="slabs-title"><img src="view/image/pos/shipping.png" height="20px" width="20px" /></span>
			<span class="slabs-value" id="<?php echo $shp_id;?>"><?php echo $shipping; ?></span>
		</a>
		<a class="slabs btn" style="width:23%;background:#00FF00" onClick="setDiscount();">
			<span class="slabs-title"><img src="view/image/pos/discount.png" height="20px" width="20px" /></span>
			<span class="slabs-value" id="order_discount_amount"><?php echo $discount; ?></span>
		</a>
		<a class="slabs btn" style="width:23%;background:#0AE6D6" onClick="openResaleDialog();">
			<span class="slabs-title"><img src="view/image/pos/tax.png" height="20px" width="20px" /></span>
			<span class="slabs-value" id="tax_total_res"><?php echo $salestax; ?></span>
		</a>
		<a class="slabs lg btn" style="background:rgb(255, 65, 65) none repeat scroll 0% 0%;color:#FFFFFF;text-transform: capitalize" onclick="makePayment();">
			<span class="totalOrderItem" style="border:1px solid; padding:5px; color:black;"><span><?=$product_row?></span><img src="view/image/pos/cart.png" height="20px" width="20px" /></span>
			<span class="slabs-title">Total:</span>
			<span class="slabs-value" id="payment_total"><?php echo $gratotal; ?></span>
			<span class="slabs-value" id="payment_status" style="margin-left:10px;"><?php echo $payment_status; ?></span>

		</a>
	</div>
							<!-- ends here -->

						</div>




						</div>

					</div>
				</div>
				<div id="paymentApplied" style="display: none;"><?php echo $is_paaid_order; ?></div>

			</div>

		</div>

	</div>

	<!-- jzebra print -->
	<?php if (!empty($config['enable_till_control'])) { ?>
		<div id="jzebra_div" style="visibility: hidden; height: 0px;">
			<applet name="jzebra" code="qz.PrintApplet.class" archive="view/template/pos/print/qz-print.jar" width="50px" height="50px">
				<param name="jnlp_href" value="view/template/pos/print/qz-print_jnlp.jnlp">
				<param name="printer" value="opencartPOS">
				<param name="cache_option" value="plugin">
				<param name="disable_logging" value="false">
				<param name="initial_focus" value="false">
			</applet>
		</div>
	<?php } ?>
	<!-- openbay integration used div -->
	<div id="hidden_div" style="display:none;"></div>
	<!-- print message iframe -->
	<iframe id="print_iframe" src="about:blank" style="display:none; width: 0; height: 0;"></iframe>



	<div style="display: none;"> <!-- start the hidden wrapper div to hide all dialogs when load -->

		<div id="return_option_dialog">
			<div style="margin-left:10px"><h3>What Do you want?</h3></div>
          <div class="form-group">
            <div style="text-align:center;">

				<div class="col-sm-6" style="display:inline-block;">
                      <!--<input type="button" class="btn btn-info btn-lg" href="<?php echo $return_url; ?>" id="newReturn" value="Open New Return" class="form-control">-->
                </div>
                <div class="col-sm-6" style="display:inline-block;">
                	<input type="button" class="btn btn-info btn-lg" id="modifyReturn" value="View/Edit Existing Return" class="form-control">
                </div>
            </div>
            <div style="float:right;margin-right:10px;margin-top:50px;margin-bottom:10px">
            	<input type="button" class="btn btn-danger btn-md" id="closebox" value="Cancel" class="form-control">
            </div>
          </div>
		</div>

		<!-- Modified Return list -->
	<div id="nreturn_panel" class="col-md-12">
			<div class="tabbable-panel">
				<div class="tabbable-line">
					<ul class="nav nav-tabs ">
                        <li class="active">
							<a href="#ntab_returns" data-toggle="tab">
							Returns </a>
						</li>

					</ul>
					<div class="tab-content">
	<div class="tab-pane" id="ntab_returns">
  	<div class="container-fluid" style="margin-top: 25px;">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> Returns List</h3>
      </div>
      <div class="panel-body">
      	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
      	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
        <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
          <div class="table-responsive">
            <table id="nreturnLists" class="table table-striped table-bordered" style="width:100%">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-right"><?php if ($sort == 'r.return_id') { ?>
                    <a class="<?php echo strtolower($order); ?>">Return ID</a>
                    <?php } else { ?>
                    <a>Return ID</a>
                    <?php } ?></td>
                  <td class="text-right"><?php if ($sort == 'r.order_id') { ?>
                    <a class="<?php echo strtolower($order); ?>"><?php echo $column_order_id; ?></a>
                    <?php } else { ?>
                    <a><?php echo $column_order_id; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'customer') { ?>
                    <a class="<?php echo strtolower($order); ?>"><?php echo $column_customer; ?></a>
                    <?php } else { ?>
                    <a><?php echo $column_customer; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'r.product') { ?>
                    <a class="<?php echo strtolower($order); ?>"><?php echo $column_product; ?></a>
                    <?php } else { ?>
                    <a><?php echo $column_product; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'r.model') { ?>
                    <a class="<?php echo strtolower($order); ?>"><?php echo $column_model; ?></a>
                    <?php } else { ?>
                    <a><?php echo $column_model; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'r.price') { ?>
                    <a class="<?php echo strtolower($order); ?>">Returned Amount</a>
                    <?php } else { ?>
                    <a>Returned Amount</a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'status') { ?>
                    <a class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a><?php echo $column_status; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'r.date_added') { ?>
                    <a class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                    <?php } else { ?>
                    <a><?php echo $column_date_added; ?></a>
                    <?php } ?></td>

                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($returns) { ?>
                <?php foreach ($returns as $return) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($return['return_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $return['return_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $return['return_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-right"><?php echo $return['return_id']; ?></td>
                  <td class="text-right"><?php echo $return['order_id']; ?></td>
                  <td class="text-left"><?php echo $return['customer']; ?></td>
                  <td class="text-left"><?php echo $return['product']; ?></td>
                  <td class="text-left"><?php echo $return['model']; ?></td>
                  <td class="text-left"><?php echo number_format((float)($return['price'] * $return['quantity']), 2, '.', ''); ?></td>
                  <td class="text-left"><?php echo $return['status']; ?></td>
                  <td class="text-left"><?php echo $return['date_added']; ?></td>
                  <!--<td class="text-left"><?php // echo $return['date_modified']; ?></td>-->
                  <td class="text-right"><a href="<?php echo $return['edit']; ?>" id="oit" data-toggle="tooltip" title="" class="btn btn-primary oit"><i class="fa fa-pencil"></i></a></td>
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
      </div>
    </div>
  </div>
  <script>
  	$(document).ready(function() {
  		closeFancybox(); //any previous instances for overlay bug
  		$(".oit").fancybox({
                'transitionIn' : 'elastic',
                'transitionOut' : 'elastic',
                'speedIn' : 500,
                'speedOut' : 400,
                'width': 1024,
                'overlayShow' : false,
                'overlayOpacity' : 0.7,
                'hideOnOverlayClick' : false,
                'hideOnContentClick' : false,
                'closeClick'  : false, // prevents closing when clicking INSIDE fancybox
  				'openEffect'  : 'none',
  				'closeEffect' : 'none',
  				'helpers'   : {
   					overlay : {closeClick: false} // prevents closing when clicking OUTSIDE fancybox
  				},
                'type' : 'iframe'
        });
      $('#nreturnLists').DataTable();

		var classList = $('.button').attr('class').split(/\s+/);
		$.each(classList, function(index, item) {
			console.log(item);
    		if (item === 'someClass') {
        	//do something
    		}
		});
} );
  </script>
</div>
</div>
</div>
</div>
</div>
		<!-- THIS -->

		<div id="order_list_dialog" class="fbox_cont order-list">
			<h3><?php echo $text_order_list; ?></h3>
        	<div class="table-header">
        		<label><input name="display_locked_orders" type="checkbox" value="0" class=""> <?php echo $text_display_locked_orders; ?></label>
				<?php if ($display_delete) { ?>
        		<a onclick="deleteOrder(this);" class="table-btn-delete"><span class="icon"></span><?php echo $button_delete; ?></a>
				<a onclick="deleteEmptyOrders(this);" class="table-btn-delete" style="margin-right: 10px;"><span class="icon"></span>Delete Blank Orders</a>
				<?php } ?>
       		</div>
			<div class="table-container">
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="table_orderlist">
        			<thead>
  						<tr>
                            <td class="one checkbox-head"><label class="radio_check"><input type="checkbox" onclick="$('input[name*=order_selected]').prop('checked', $(this).is(':checked'));" /> <span class="skip_content">Select All</span></label></td>
                            <td class="two"><?php echo $column_order_id; ?></td>
							<?php if (isset($config['enable_table_management']) && (int)$config['enable_table_management']) {?>
                            <td class="three"><?php echo $column_table_id; ?></td>
							<?php } ?>
                            <td class="four"><?php echo $column_customer; ?></td>
                            <td class="five"><?php echo $column_status; ?></td>
                            <td class="six"><?php echo $column_order_total; ?></td>
                            <td class="seven"><?php echo $column_date_added; ?></td>
                            <td class="eight"><?php echo $column_date_modified; ?></td>
                            <td class="eight">Sales Person</td>
                            <td class="nine"><?php echo $column_action; ?></td>
                 		</tr>
                  	</thead>
                    <tbody>
  						<tr class="first-row">
                            <td class="one filter"><a class="skip_content filter_tab"><?php echo $button_filter; ?><span class="icon"></span></a></td>
                            <td class="two"><label class="skip_content"><?php echo $column_order_id; ?></label><input name="filter_order_id" type="text" value=""></td>
							<?php if (isset($config['enable_table_management']) && (int)$config['enable_table_management']) {?>
                            <td class="three">
								<label class="skip_content"><?php echo $column_table_id; ?></label>
								<select name="table_list">
									<option value="0"></option>
									<?php
										if (!empty($tables)) {
											foreach ($tables as $table) {
									?>
									<?php if (isset($filter_table_id) && $filter_table_id == $table['table_id']) { ?>
										<option value="<?php echo $table['table_id']; ?>" selected="selected"><?php echo $table['name']; ?></option>
									<?php } else { ?>
										<option value="<?php echo $table['table_id']; ?>"><?php echo $table['name']; ?></option>
									<?php } ?>
									<?php
											}
										}
									?>
								</select>
							</td>
							<?php } ?>
                            <td class="four"><label class="skip_content"><?php echo $column_customer; ?></label><input name="filter_customer" type="text" class="auto_complete"></td>
                            <td class="five">
                            	<label class="skip_content"><?php echo $column_status; ?></label>
								<?php if ($text_work_mode == '2') {?>
								<select name="filter_quote_status_id">
									<option value="*"></option>
									<option value="0"><?php echo $text_missing; ?></option>
									<?php foreach ($quote_statuses as $quote_status_top) { ?>
									<option value="<?php echo $quote_status_top['quote_status_id']; ?>"><?php echo $quote_status_top['name']; ?></option>
									<?php } ?>
								</select>
								<?php } else { ?>
								<select name="filter_order_status_id">
									<option value="*"></option>
									<option value="0"><?php echo $text_missing; ?></option>
									<?php foreach ($order_statuses as $order_status_top) { ?>
									<option value="<?php echo $order_status_top['order_status_id']; ?>"><?php echo $order_status_top['name']; ?></option>
									<?php } ?>
								</select>
								<?php } ?>
                            </td>
                            <td class="six"><label class="skip_content"><?php echo $column_order_total; ?></label><input name="filter_total" type="text"></td>
                            <td class="seven"><label class="skip_content"><?php echo $column_date_added; ?></label><input name="filter_date_added" type="text" class="date"></td>
                            <td class="eight"><label class="skip_content"><?php echo $column_date_modified; ?></label><input name="filter_date_modified" type="text" class="date"></td>
                            <td class="eight"><label class="skip_content"><?php echo "Sales Person"; ?></label>
                            	<select name="filter_order_saleper_id">
									<option value="*"></option>
									<?php foreach ($sales_person as $person) { ?>
									<option value="<?php echo $person['user_id']; ?>"><?php echo $person['name']; ?></option>
									<?php } ?>
								</select>
                            </td>
                            <td class="nine"><label class="skip_content">&nbsp;</label><a id="button_filter" onclick="filter();" class="table-btn table-btn-filter"><span class="icon filter"></span> <?php echo $button_filter; ?></a></td>
                   		</tr>
					</tbody>
					<tbody id="order_list_orders"></tbody>
				</table>
			</div>
			<div id="order_list_pagination" class="table-pagination"></div>
		</div>

		<!-- for order status dialog popups, prepare the div for dialogs -->
		<div id="order_status_dialog" class="fbox_cont order-status">
			<h3><?php echo $text_change_order_status; ?></h3>
			<div class="table-container"><ul class="order_status_list"></ul></div>
		</div>

        <!-- for Resale ID Number dialog popups, prepare the div for dialogs -->
		<div id="resale_id_number_dialog" class="fbox_cont resale-id-number">
			<div class="form-group">
				<h3>Take Picture of Resale License</h3>
				<div id="uploadResponse"></div>
				<div class="mainResale">
					<div class="formResale">
						<form enctype="multipart/form-data" name="frmResale" id="frmResale" action="index.php?route=module/pos/uploadResaleImages&token=<?php echo $token; ?>&order_id=<?php echo $order_id;?>&submit=1" method="post">
							<div class="col-sm-12">
							<div id="filediv">
								<?php if (!empty($resale_images)){?>
								<?php foreach ($resale_images['dataRows'] as $resale_image){?>
									<div class="abcd" id="abcd1">
										<img id="previewimg1" src="<?php echo $resale_image['image'];?>" />
										<input type="hidden" name="isUploaded" id="isUploaded" value="<?php echo $resale_image['value'];?>"/>
									</div>
								<?php }}else{ ?>
										<input type="hidden" name="isUploaded" id="isUploaded" value=""/>
								<?php } ?>
								<input id="fileResale" name="fileResale[]" type="file" class="fileResale"/>
							</div>
								<input type="button" id="add_more" class="upload" value="Add More Files"/>
								<input type="hidden" name="order_id"  value="<?php echo $order_id; ?>"/>
								<input type="button" value="Upload File" name="uploadResale" id="uploadResale" class="upload uploadResale" onclick="uploadResaleImage();"/>
								</div>
								<br/>
	                			<input type="button" class="btn btn-info btn-sm" value="Apply and Remove Sales Tax" onclick="validateResaleImage();"/>
						</form>
	                	<input type="hidden" name="isUploadedResale" id="isUploadedResale" value=""/>
					</div>
				</div>
			</div>
            <div class="form-group">
            	<h3>Enter Resale ID Number</h3>
				<div class="col-sm-9">
                      <input type="text" name="resale_id_number" value="" class="form-control">
                </div>
                <div class="col-sm-3">
                	<input type="button" class="btn btn-info btn-sm" value="Apply" onclick="validateResale();"/>
                </div>
            </div>
        </div>

		<!-- for shipping dialog popups, prepare the div ---->
		<div id="order_shipping_dialog" class="fbox_cont shipping">
			<h3><?php echo $text_change_shipping; ?></h3>
			<div id="order_addresses" class="table-container form-box">
				<div class="shipping_top">
					<ul class="form_list">
						<li>
							<label><?php echo $entry_shipping_method; ?> <em>*</em></label>
							<div class="inputbox">
								<select name="shipping" id="shipping">
									<option value="free.free" <?=$shipping_code == 'free.free'?'selected':''?>>Custom Shipping</option>
                                    <option value="instore.instore" <?=$shipping_code == 'instore.instore'?'selected':''?>>Instore Shipping</option>
								</select>
							</div>
						</li>
                        <li style="display:none" id="shipping_title">
							<label>Shipping Title</label>
							<div class="inputbox">
                            	<input type="text" name="shipping_title" value="<?=!empty($order_shipping['title'])?$order_shipping['title']:''?>" />
							</div>
						</li>
                        <li style="display:none" id="shipping_cost">
							<label>Shipping Cost</label>
							<div class="inputbox">
                            	<input type="text" name="shipping_cost" value="<?=!empty($order_shipping['value'])?$order_shipping['value']:''?>" />
							</div>
						</li>
					</ul>
				</div>
				<div class="shipping_l">
					<h4><?php echo $text_order_payment_address; ?></h4>
					<ul class="form_list">
						<li>
							<label><?php echo $entry_order_address; ?></label>
							<div class="inputbox">
								<select name="payment_address">
									<option value="0" selected="selected"><?php echo $text_none; ?></option>
									<?php if (!empty($customer_addresses)) { foreach ($customer_addresses as $customer_address) { ?>
										<option value="<?php echo $customer_address['address_id']; ?>"><?php echo $customer_address['firstname'] . ' ' . $customer_address['lastname'] . ', ' . $customer_address['address_1'] . ', ' . $customer_address['city'] . ', ' . $customer_address['country']; ?></option>
									<?php } } ?>
								</select>
							</div>
						</li>
						<li>
							<label><?php echo $entry_firstname; ?> <em>*</em></label>
							<div class="inputbox"><input name="payment_firstname" type="text" value="<?php echo $payment_firstname; ?>"> </div>
						</li>
						<li>
							<label><?php echo $entry_lastname; ?> <em>*</em></label>
							<div class="inputbox"><input name="payment_lastname" type="text" value="<?php echo $payment_lastname; ?>"></div>
						</li>
						<li>
							<label><?php echo $entry_company; ?></label>
							<div class="inputbox"><input name="payment_company" type="text" value="<?php echo $payment_company; ?>"></div>
						</li>
						<li>
							<label><?php echo $entry_address_1; ?> <em>*</em></label>
							<div class="inputbox"><input name="payment_address_1" type="text" value="<?php echo $payment_address_1; ?>"></div>
						</li>
						<li>
							<label><?php echo $entry_address_2; ?></label>
							<div class="inputbox"><input name="payment_address_2" type="text" value="<?php echo $payment_address_2; ?>"></div>
						</li>
						<li>
							<label><?php echo $entry_city; ?> <em>*</em></label>
							<div class="inputbox"><input name="payment_city" type="text" value="<?php echo $payment_city; ?>"></div>
						</li>
						<li>
							<label><?php echo $entry_postcode; ?> <em>*</em></label>
							<div class="inputbox"><input name="payment_postcode" type="text" value="<?php echo $payment_postcode; ?>"></div>
						</li>
						<li>
							<label><?php echo $entry_country; ?> <em>*</em></label>
							<div class="inputbox">
								<select name="payment_country_id" onchange="order_country(this, 'payment', '<?php echo $payment_zone_id; ?>');">
									<option value=""><?php echo $text_select; ?></option>
									<?php foreach ($customer_countries as $customer_country) {
											if ($customer_country['country_id'] == $payment_country_id) { ?>
												<option value="<?php echo $customer_country['country_id']; ?>" selected="selected"><?php echo $customer_country['name']; ?></option>
									<?php } else { ?>
												<option value="<?php echo $customer_country['country_id']; ?>"><?php echo $customer_country['name']; ?></option>
									<?php } } ?>
								</select>
							</div>
						</li>
						<li>
							<label><?php echo $entry_zone; ?> <em>*</em></label>
							<div class="inputbox">
								<select name="payment_zone_id">
									<option value=""><?php echo $text_select; ?></option>
									<?php if (!empty($zones[$payment_country_id])) { foreach ($zones[$payment_country_id] as $zone) {
											if ($zone['zone_id'] == $payment_zone_id) { ?>
												<option value="<?php echo $zone['zone_id']; ?>" selected="selected"><?php echo $zone['name']; ?></option>
									<?php } else { ?>
												<option value="<?php echo $zone['zone_id']; ?>"><?php echo $zone['name']; ?></option>
									<?php } } } ?>
								</select>
							</div>
						</li>
					</ul>
				</div>
				<div class="shipping_r">
					<h4><?php echo $text_order_shipping_address; ?></h4>
					<ul class="form_list">
						<li>
							<label><?php echo $entry_order_address; ?></label>
							<div class="inputbox">
								<select name="shipping_address">
									<option value="0" selected="selected"><?php echo $text_none; ?></option>
									<?php if (!empty($customer_addresses)) { foreach ($customer_addresses as $customer_address) { ?>
										<option value="<?php echo $customer_address['address_id']; ?>"><?php echo $customer_address['firstname'] . ' ' . $customer_address['lastname'] . ', ' . $customer_address['address_1'] . ', ' . $customer_address['city'] . ', ' . $customer_address['country']; ?></option>
									<?php } } ?>
								</select>
							</div>
						</li>
						<li>
							<label><?php echo $entry_firstname; ?> <em>*</em></label>
							<div class="inputbox"><input name="shipping_firstname" type="text" value="<?php echo $shipping_firstname; ?>"> </div>
						</li>
						<li>
							<label><?php echo $entry_lastname; ?> <em>*</em></label>
							<div class="inputbox"><input name="shipping_lastname" type="text" value="<?php echo $shipping_lastname; ?>"></div>
						</li>
						<li>
							<label><?php echo $entry_company; ?></label>
							<div class="inputbox"><input name="shipping_company" type="text" value="<?php echo $shipping_company; ?>"></div>
						</li>
						<li>
							<label><?php echo $entry_address_1; ?> <em>*</em></label>
							<div class="inputbox"><input name="shipping_address_1" type="text" value="<?php echo $shipping_address_1; ?>"></div>
						</li>
						<li>
							<label><?php echo $entry_address_2; ?></label>
							<div class="inputbox"><input name="shipping_address_2" type="text" value="<?php echo $shipping_address_2; ?>"></div>
						</li>
						<li>
							<label><?php echo $entry_city; ?> <em>*</em></label>
							<div class="inputbox"><input name="shipping_city" type="text" value="<?php echo $shipping_city; ?>"></div>
						</li>
						<li>
							<label><?php echo $entry_postcode; ?> <em>*</em></label>
							<div class="inputbox"><input name="shipping_postcode" type="text" value="<?php echo $shipping_postcode; ?>"></div>
						</li>
						<li>
							<label><?php echo $entry_country; ?> <em>*</em></label>
							<div class="inputbox">
								<select name="shipping_country_id" onchange="order_country(this, 'shipping', '<?php echo $shipping_zone_id; ?>');">
									<option value=""><?php echo $text_select; ?></option>
									<?php foreach ($customer_countries as $customer_country) {
											if ($customer_country['country_id'] == $shipping_country_id) { ?>
												<option value="<?php echo $customer_country['country_id']; ?>" selected="selected"><?php echo $customer_country['name']; ?></option>
									<?php } else { ?>
												<option value="<?php echo $customer_country['country_id']; ?>"><?php echo $customer_country['name']; ?></option>
									<?php } } ?>
								</select>
							</div>
						</li>
						<li>
							<label><?php echo $entry_zone; ?> <em>*</em></label>
							<div class="inputbox">
								<select name="shipping_zone_id">
									<option value=""><?php echo $text_select; ?></option>
									<?php if (!empty($zones[$shipping_country_id])) { foreach ($zones[$shipping_country_id] as $zone) {
											if ($zone['zone_id'] == $shipping_zone_id) { ?>
												<option value="<?php echo $zone['zone_id']; ?>" selected="selected"><?php echo $zone['name']; ?></option>
									<?php } else { ?>
												<option value="<?php echo $zone['zone_id']; ?>"><?php echo $zone['name']; ?></option>
									<?php } } } ?>
								</select>
							</div>
						</li>
					</ul>
				</div>
			</div>
            <div class="fbox_btn_wrap">
				<a onclick="saveShippingDetails();" class="table-btn-common"><?php echo $button_save; ?></a>
            </div>
		</div>
			<style type="text/css">
				.padding10px{
					padding: 1%;
				}
			</style>
		<!-- for customer dialog popup, prepare the div -->
		<div style="height:450px;" id="customer_dialog" class="fbox_cont change_add_details">
			<h3><?php echo $text_change_customer; ?></h3>
            <div class="fbox_btn_wrap margin_b_6">

              <!---  <a onclick="getCustomerList();" class="table-btn-common customer existing fbox_trigger_2"><span class="icon"></span><?php echo $text_select_customer; ?></a>---->
            </div>
			<div class="table-container margin_b_6" id="order_customer">
 				<input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>" />
 				<input type="hidden" name="customer_name" value="<?php echo ucwords($customer); ?>" />
				<?php $address_row = 1; ?>
				<div class="nav_tab_wrap">


                   <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_customer_select" id="customer_select" data-toggle="tab"><?php echo "Select Customer"; ?></a></li>
                        <li class=""><a href="#tab_customer_addresses" id="customer_addresses_tab" data-toggle="tab">Customer Addresses</a></li>
                        <li class=""><a href="#tab_customer_order_history" onclick="orderHistoryTab(<?php echo $customer_id; ?>);" id="customer_order_history" data-toggle="tab">Customer Order History</a></li>
                        <li class=""><a href="#tab_customer_return_history" onclick="getReturnListTab(<?php echo $customer_id; ?>);" id="customer_return_history" data-toggle="tab">Return History</a></li>
                         <!--- Raza Umer ---->
					<li class=""><a href="#tab_customer_notes" id="customer_notes" data-toggle="tab">Customer Notes</a></li>
					<!--- Raza Umer ---->
					<li><a href="#tab_new_customer" id="new_customer" data-toggle="tab">Add New Customer</a></li>
                    </ul>

                  <div class="tab-content">
                  	<div class="tab-pane" id="tab_customer_return_history">

                  			<div id="return_list_dialog" class="fbox_cont order-list">
			<h3><?php echo $text_return_list; ?></h3>

			<div class="table-container">
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="table_orderlist">
        			<thead>
  						<tr>
                           <td class="two">Product ID</td>
                            <td class="four">Product Name</td>

                            <td class="five">Model</td>
                            <td class="six">Quantity</td>
                            <td class="seven">Order Product #</td>
                            <td class="eight">Return Quantity</td>
                 		</tr>
                  	</thead>
                    <tbody>
  						<!-- <tr class="first-row">
                            <td class="one filter"><a class="skip_content filter_tab"><?php echo $button_filter; ?><span class="icon"></span></a></td>
                            <td class="two"><label class="skip_content"><?php echo $column_pos_return_id; ?></label><input name="filter_pos_return_id" type="text" value=""></td>
                            <td class="four"><label class="skip_content"><?php echo $column_customer; ?></label><input name="filter_return_customer" type="text" class="auto_complete"></td>
                            <td class="five">
                            	<label class="skip_content"><?php echo $column_status; ?></label>
								<select name="filter_return_status_id">
									<option value="*"></option>
									<?php foreach ($return_statuses as $return_status) { ?>
									<option value="<?php echo $return_status['return_status_id']; ?>"><?php echo $return_status['name']; ?></option>
									<?php } ?>
								</select>
                            </td>
                            <td class="six"><label class="skip_content"><?php echo $column_return_total; ?></label><input name="filter_return_total" type="text"></td>
                            <td class="seven"><label class="skip_content"><?php echo $column_date_added; ?></label><input name="filter_return_date_added" type="text" class="date"></td>
                            <td class="eight"><label class="skip_content"><?php echo $column_action; ?></label><input name="filter_return_date_modified" type="text" class="date"></td>
                            <td class="nine"><label class="skip_content">&nbsp;</label><a id="button_return_filter" onclick="filterReturn();" class="table-btn table-btn-filter"><span class="icon filter"></span> <?php echo $button_filter; ?></a></td>
                   		</tr> -->
					</tbody>
					<tbody id="return_list_returns_tabs"></tbody>
				</table>
			</div>
			<div id="return_list_pagination" class="table-pagination"></div>
		</div>


                  	</div>
					  <div class="tab-pane active" id="tab_customer_select">
                  	 		<table style="width:100%;" border="0">
								<tr>
									<td width="10%">
										<label class="btn btn-success"><i class="fa fa-user"></i> Select Customer</label>
									</td>
									<td width="90%">
										<div class="inputbox">
											<input class="form-control" name="customer_firstname_tab" type="text" value="">
											<ul id="append_customer"></ul>
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<br /><br /><br />
										<a href="#tab_new_customer" onclick="new_customer_tab();" class="table-btn-common customer new" data-toggle="tab" style="margin-left: 0px; float:left;"><span class="icon"></span><?php echo $text_add_customer; ?></a>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<a onclick="resetCustomer();" class="table-btn-common customer reset" style="min-width: 200px;"><span class="icon"></span><?php echo "Reset Customer"; ?></a>
									</td>
									<br />
								</tr>
							</table>
                  	</div>
                    <div class="tab-pane" id="tab_customer_addresses">
                    <ul class="nav nav-tabs">
                        <li class=""><a href="#tab_customer_general" id="customer_general" data-toggle="tab"><?php echo $tab_customer_general; ?></a></li>

						<?php if (!empty($customer_addresses)) { foreach ($customer_addresses as $customer_address) { ?>
                        <li><a href="tab_customer_address_<?php echo $address_row; ?>" id="customer_address_<?php echo $address_row; ?>"><span onclick="$('#customer_general').trigger('click');$(this).closest('li').remove(); $('#tab_customer_address_<?php echo $address_row; ?>').remove();" class="icon"></span> <?php echo $tab_address . ' ' . $address_row; ?></a></li>
						<?php $address_row++; ?>
						<?php } } ?>
                        <li><a href="#tab_customer_new_address" id="customer_new_address" class="new_address" <?php if (empty($customer_id)) {?> style="display: none;"<?php }?>><span class="icon"></span><?php echo $tab_customer_new_address; ?></a></li>

                    </ul>
                    <div class="tab-content">


					<div class="tab-pane" id="tab_customer_general">
									<table style="width:100%;" border="0">
										<tr>
											<td align="right"><li><label class=""><?php echo $entry_firstname; ?> <em style="color: red;">*</em></label></td>
											<td><div class="inputbox"><input class="form-control" name="customer_firstname" type="text" value="<?php echo empty($customer_id) ? $firstname : $customer_firstname; ?>"></div></li></td>
											<td align="right"><li><label><?php echo $entry_fax; ?></label></td>
											<td><div class="inputbox"><input class="form-control" name="customer_fax" type="text" value="<?php echo empty($customer_id) ? $fax : $customer_fax; ?>"></div></li></td>
										</tr>
										<tr>
											<td align="right"><li><label><?php echo $entry_lastname; ?> <em style="color: red;">*</em></label></td>
											<td><div class="inputbox"><input class="form-control" name="customer_lastname" type="text" value="<?php echo empty($customer_id) ? $lastname : $customer_lastname; ?>"></div></li></td>
											<td align="right"><li><label><?php echo $entry_card_number; ?></label></td>
											<td><div class="inputbox"><input class="form-control" name="customer_card_number" type="text" value="<?php echo empty($customer_card_number) ? '' : $customer_card_number; ?>"></div></li></td>
										</tr>
										<tr>
											<td align="right"><li><label><?php echo $entry_email; ?> <em style="color: red;">*</em></label></td>
											<td><div class="inputbox"><input class="form-control" name="customer_email" type="text" value="<?php echo empty($customer_id) ? $email : $customer_email; ?>"></div></li></td>
											<td align="right"><li><label><?php echo $entry_resale_number; ?></label></td>
											<td><div class="inputbox"><input class="form-control" name="customer_resale_number" type="text" value="<?php echo empty($customer_resale_number) ? '' : $customer_resale_number; ?>"></div></li></td>
										</tr>
										<tr>
											<td align="right"><li><label><?php echo $entry_telephone; ?> <em style="color: red;">*</em></label></td>
											<td><div class="inputbox"><input class="form-control" name="customer_telephone" type="text" value="<?php echo empty($customer_id) ? $telephone : $customer_telephone; ?>"></div></li></td>
											<td align="right"><li><label><?php echo $entry_notes; ?></label></td>
											<td><div class="inputbox"><input class="form-control" name="customer_notes" type="text" value="<?php echo empty($customer_notes) ? '' : $customer_notes; ?>"></div></li></td>
										</tr>
										<!--<li>
												<label><?php echo $entry_password; ?></label>
												<div class="inputbox"><input name="customer_password" type="password"></div>
											</li>
											<li>
												<label><?php echo $entry_confirm; ?>:</label>
												<div class="inputbox"><input name="customer_confirm" type="password"></div>
											</li>
										-->
										<tr class="customer_extra_info" <?php if (empty($customer_id)) {?> style="display: none;"<?php }?>>
											<input name="customer_password" type="hidden">
											<input name="customer_confirm" type="hidden">
											<td align="right"><li><label><?php echo $entry_newsletter; ?></label></td>
											<td>
												<div class="inputbox">
													<select name="customer_newsletter">
														<?php if (!empty($customer_newsletter)) { ?>
														<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
														<option value="0"><?php echo $text_disabled; ?></option>
														<?php } else { ?>
														<option value="1"><?php echo $text_enabled; ?></option>
														<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
														<?php } ?>
													</select>
												</div></li>
											</td>
											<td align="right"><li><label><?php echo $entry_customer_group; ?></label></td>
											<td>
												<div class="inputbox">
													<select name="customer_group_id">
														<?php foreach ($customer_groups as $customer_group) { ?>
															<?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
															<option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
															<?php } else { ?>
															<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
															<?php } ?>
														<?php } ?>
													</select>
												</div></li>
											</td>
										</tr>
										<tr class="customer_extra_info" <?php if (empty($customer_id)) {?> style="display: none;"<?php }?>>
											<td align="right"><li><label><?php echo $entry_status; ?></label></td>
											<td>
												<div class="inputbox">
													<select name="customer_status">
														<?php if (!empty($customer_status)) { ?>
														<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
														<option value="0"><?php echo $text_disabled; ?></option>
														<?php } else { ?>
														<option value="1"><?php echo $text_enabled; ?></option>
														<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
														<?php } ?>
													</select>
												</div></li>
											</td>
											<td align="right"><li><label></label></td>
											<td><div class="inputbox"></div></li></td>
										</tr>
									</table>
                        		</div><!--tab-pane-->
								<?php $address_row = 1; ?>
								<?php if (!empty($customer_addresses)) { foreach ($customer_addresses as $customer_address) { ?>
                        			<div class="tab-pane" id="tab_customer_address_<?php echo $address_row; ?>">
										<input type="hidden" name="customer_addresses[<?php echo $address_row; ?>][address_id]" value="<?php echo $customer_address['address_id']; ?>" />
										<table style="width:100%;" border="0">
											<tr>
												<td align="right"><li><label class=""><?php echo $entry_firstname; ?> <em style="color: red;">*</em></label></td>
												<td><div class="inputbox"><input class="form-control" name="customer_addresses[<?php echo $address_row; ?>][firstname]" type="text" value="<?php echo $customer_address['firstname']; ?>"></div></li></td>
												<td align="right"><li><label><?php echo $entry_company; ?></label></td>
												<td><div class="inputbox"><input class="form-control" name="customer_addresses[<?php echo $address_row; ?>][company]" type="text" value="<?php echo $customer_address['company']; ?>"></div></li></td>
											</tr>
											<tr>
												<td align="right"><li><label><?php echo $entry_lastname; ?> <em style="color: red;">*</em></label></td>
												<td><div class="inputbox"><input class="form-control" name="customer_addresses[<?php echo $address_row; ?>][lastname]" type="text" value="<?php echo $customer_address['lastname']; ?>"></div></li></td>
												<td align="right"><li><label><?php echo $entry_address_1; ?> <em style="color: red;">*</em></label></td>
												<td><div class="inputbox"><input class="form-control" name="customer_addresses[<?php echo $address_row; ?>][address_1]" type="text" value="<?php echo $customer_address['address_1']; ?>"></div></li></td>
											</tr>
											<tr>
												<td align="right"><li><label><?php echo $entry_address_2; ?></label></td>
												<td><div class="inputbox"><input class="form-control" name="customer_addresses[<?php echo $address_row; ?>][address_2]" type="text" value="<?php echo $customer_address['address_2']; ?>"></div></li></td>
												<td align="right"><li><label><?php echo $entry_city; ?> <em style="color: red;">*</em></label></td>
												<td><div class="inputbox"><input class="form-control" name="customer_addresses[<?php echo $address_row; ?>][city]" type="text" value="<?php echo $customer_address['city']; ?>"></div></li></td>
											</tr>
											<tr>
												<td align="right"><li><label><?php echo $entry_postcode; ?> <em style="color: red;">*</em></label></td>
												<td><div class="inputbox"><input class="form-control" name="customer_addresses[<?php echo $address_row; ?>][postcode]" type="text" value="<?php echo $customer_address['postcode']; ?>"></div></li></td>
												<td align="right"><li><label><?php echo $entry_country; ?> <em style="color: red;">*</em></label></td>
												<td>
													<div class="inputbox">
														<select name="customer_addresses[<?php echo $address_row; ?>][country_id]" onchange="country(this, '<?php echo $address_row; ?>', '<?php echo $customer_address['zone_id']; ?>');">
															<option value=""><?php echo $text_select; ?></option>
															<?php foreach ($customer_countries as $customer_country) { ?>
																<?php if ($customer_country['country_id'] == $customer_address['country_id']) { ?>
																	<option value="<?php echo $customer_country['country_id']; ?>" selected="selected"><?php echo $customer_country['name']; ?></option>
																<?php } else { ?>
																	<option value="<?php echo $customer_country['country_id']; ?>"><?php echo $customer_country['name']; ?></option>
																<?php } ?>
															<?php } ?>
														</select>
													</div></li>
												</td>
											</tr>
											<tr>
												<td align="right"><li><label><?php echo $entry_zone; ?> <em style="color: red;">*</em></label></td>
												<td>
													<div class="inputbox">
														<select name="customer_addresses[<?php echo $address_row; ?>][zone_id]">
															<option value=""><?php echo $text_select; ?></option>
															<?php foreach ($customer_address['zones'] as $zone) {
																if ($zone['zone_id'] == $customer_address['zone_id']) { ?>
																	<option value="<?php echo $zone['zone_id']; ?>" selected="selected"><?php echo $zone['name']; ?></option>
																<?php } else { ?>
																	<option value="<?php echo $zone['zone_id']; ?>"><?php echo $zone['name']; ?></option>
															<?php } } ?>
														</select>
													</div></li>
												</td>
												<td align="right"><li><label>&nbsp;</label></td>
												<td>
													<div class="inputbox">
														<?php if (($customer_address['address_id'] == $customer_address_id) || !$customer_addresses) { ?>
															<label class="radio_check"><input type="radio" class="" value="<?php echo $address_row; ?>" name="customer_addresses[<?php echo $address_row; ?>][default]"  checked="checked"><?php echo $entry_default; ?></label>
														<?php } else { ?>
															<label class="radio_check"><input type="radio" class="" value="<?php echo $address_row; ?>" name="customer_addresses[<?php echo $address_row; ?>][default]"><?php echo $entry_default; ?></label>
														<?php } ?>
													</div></li>
												</td>
											</tr>
										</table>
									</div><!--tab-pane-->
									<?php $address_row++; ?>
								<?php } } ?>
                       			<div class="tab-pane" id="tab_customer_new_address"></div><!--tab-pane-->
                    		</div><!--tab-content-->
                  		</div>
                  		<!--- Raza Umer ---->
						<div class="tab-pane" id="tab_customer_notes">
							<br />
							<div class="form-group">
					  			<label class="control-label col-sm-2">Customer Notes</label>
					  			<div class="col-sm-10">
					  				<textarea name="customer_sales_notes" class="form-control" rows="4"><?php if(isset($customer_sales_notes)) echo $customer_sales_notes; ?></textarea>
					  			</div>
					  		</div>
							<br />
					   		<div class="form-group">
								<label class="col-sm-2 control-label" for="input-image_cust">Image</label>
								<div class="col-sm-10">
						  			<a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php if(isset($thumb)) echo $thumb; ?>" alt="" title="" data-placeholder="<?php if(isset($placeholder)) echo $placeholder; ?>" /></a>
						  			<input type="hidden" name="customer_image" value="<?php if(isset($customer_image)) echo $customer_image; ?>" id="input-image_cust" />
								</div>
					  		</div>
					  		<input type="hidden" name="notes_customer_id" id="tab_customer_id" value="<?php echo $customer_id; ?>">
					  		<div class="col-sm-2">
							  	<br />
					  			<button type="button" class="btn btn-success" id="customer_sales_notes" onclick="saveCustomerNotes(<?php echo $customer_id; ?>);">Save Customer Notes</button>
					  		</div>
				  		</div>
						  <div class="tab-pane" id="tab_new_customer">
							<table style="width:100%;" border="0">
								<tr>
									<td align="right"><li><label class=""><?php echo $entry_firstname; ?> <em style="color: red;">*</em></label></td>
									<td><div class="inputbox"><input class="form-control" name="customer_firstname_tab1" type="text" value=""></div></li></td>
									<td align="right"><li><label><?php echo $entry_fax; ?></label></td>
									<td><div class="inputbox"><input class="form-control" name="customer_fax_tab" type="text" value=""></div></li></td>
								</tr>
								<tr>
									<td align="right"><li><label><?php echo $entry_lastname; ?> <em style="color: red;">*</em></label></td>
									<td><div class="inputbox"><input class="form-control" name="customer_lastname_tab" type="text" value=""></div></li></td>
									<td align="right"><li><label><?php echo $entry_card_number; ?></label></td>
									<td><div class="inputbox"><input class="form-control" name="customer_card_number_tab" type="text" value=""></div></li></td>
								</tr>
								<tr>
									<td align="right"><li><label><?php echo $entry_email; ?> <em style="color: red;">*</em></label></td>
									<td><div class="inputbox"><input class="form-control" name="customer_email_tab" type="text" value=""></div></li></td>
									<td align="right"><li><label><?php echo $entry_resale_number; ?></label></td>
									<td><div class="inputbox"><input class="form-control" name="customer_resale_number_tab" type="text" value=""></div></li></td>
								</tr>
								<tr>
                                	<td align="right"><li><label><?php echo $entry_telephone; ?> <em style="color: red;">*</em></label></td>
									<td><div class="inputbox"><input class="form-control" name="customer_telephone_tab" type="text" value=""></div></li></td>
                                	<td align="right"><li><label><?php echo $entry_notes; ?></label></td>
									<td><div class="inputbox"><input class="form-control" name="customer_notes_tab" type="text" value=""></div></li></td>
                                </tr>
								<br />
                            </ul>
                        	</table>
                  		</div>
				  <style>
				  .fancybox-overlay-fixed{
					  z-index:999 !important;
				  }
				  </style>

				  <div class="tab-pane" id="tab_customer_order_history">
					<h3>Order History</h3>
		        	<div class="table-container">
		            	<a class="skip_content filter_tab"><?php echo $button_filter; ?> <span class="icon"></span></a>
		       			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="table_orderlist table_customerlist">
		        			<thead>
		  						<tr>
		                            <td class="two">Order id</td>
		                            <td class="four">Name</td>
		                            <td class="five">Status</td>
		                            <td class="seven"><?php echo $column_date_added; ?></td>
		                            <td class="nine">Total</td>
		                            <td class="nine">Action</td>
		                 		</tr>
		                  	</thead>

							<tbody id="orderhistor_list_customers_tab"></tbody>
						</table>
		        	</div><!--table-container-->
				</div>

				  <!--- Raza Umer ---->
                  </div>
                  <style type="text/css">
                  	#append_customer{
                  		display: none;
                  		position: relative;
					    top: 33.7%;
					    width: 100%;
					    overflow-y: auto;
					    background: #f3f1f1;
					    height: 120px;
					}
					#append_backorder_customer{
                  		display: none;
                  		position: relative;
					    top: 33.7%;
					    width: 100%;
					    overflow-y: auto;
					    background: #f3f1f1;
					    height: 120px;
                  	}
                  </style>
                </div><!--nav_tab_wrap-->
				<div id="customer_action_info" style="padding: 20px; display:none;">
					<i class="fa fa-spinner fa-spin"></i>&nbsp;<?php echo $text_fetching_customers; ?>
				</div>
			</div>
            <div class="fbox_btn_wrap" id="fbox_btn_wrap_save_button">
				<a onclick="saveCustomer(false);" class="table-btn-common"><?php echo $button_save; ?></a>
            </div>
    	</div><!--fbox_cont-->
		    <div id="show_receipt_dialog" class="fbox_cont change_add_details">
      <h3>Show Receipt</h3>
          <div id="receiptdialogue"></div>
    </div>

        <div id="show_refund_dialog" class="fbox_cont change_add_details">
      <h3>Refund Information</h3>
          <div id="refnd_dialogue" class="col-sm-12"></div>
    </div>

		<!-- for customer list dialog popup, prepare the div -->
		<div id="customer_list_dialog" class="fbox_cont existing_customer">
			<h3><?php echo $text_customer_list; ?></h3>
        	<div class="table-container">
            	<a class="skip_content filter_tab"><?php echo $button_filter; ?> <span class="icon"></span></a>
       			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="table_orderlist table_customerlist">
        			<thead>
  						<tr>
                            <td class="two"><?php echo $column_company_id; ?></td>
                            <td class="four"><?php echo $column_customer_name; ?></td>
                            <td class="five"><?php echo $column_email; ?></td>
                            <td class="six"><?php echo $column_telephone; ?></td>
                            <td class="seven"><?php echo $column_date_added; ?></td>
                            <td class="nine"><?php echo $column_action; ?></td>
                 		</tr>
                  	</thead>
                    <tbody>
  						<tr class="first-row">
                            <td class="two"><label class="skip_content"><?php echo $column_customer_id; ?></label> <input name="filter_customer_id" type="text"></td>
                            <td class="four"><label class="skip_content"><?php echo $column_customer_name; ?></label> <input name="filter_customer_name" type="text"></td>
                            <td class="five"><label class="skip_content"><?php echo $column_email; ?></label> <input name="filter_customer_email" type="text"></td>
                            <td class="six"><label class="skip_content"><?php echo $column_telephone; ?></label> <input name="filter_customer_telephone" type="text"></td>
                            <td class="seven"><label class="skip_content"><?php echo $column_date_added; ?></label> <input name="filter_customer_date" type="text" class="date"></td>
                            <td class="nine"><label class="skip_content">&nbsp;</label> <a id="button_customer_filter" onclick="filterCustomer();" class="table-btn table-btn-filter"><span class="icon filter"></span> <?php echo $button_filter; ?></a></td>
                   		</tr>
					</tbody>
					<tbody id="customer_list_customers">
						<tr><td align="center" colspan="6"><i class="fa fa-spinner fa-spin"></i> <?php echo $text_fetching_customers; ?></td></tr>
					</tbody>
				</table>
        	</div><!--table-container-->
        	<div id="customer_list_pagination" class="table-pagination"></div>
		</div>
		<!--- Raza Umer --->
		<!-- for orderHistorList list dialog popup, prepare the div -->
		<!-- Trigger the modal with a button -->

        <div id="resumeOrder_dialog" class="fbox_cont">
			<h3>Resume Order</h3>
             <iframe src="" id="resume_order" width="100%" height="650px"></iframe>

             <div class="col-sm-2">
             	<a style="width: 195px;display: none;" id="button_print_receipt_edit_order" onclick="" class="table-btn-common"><span class="icon"></span><i class="fa fa-print fa-2x"></i> Print Receipt</a>
             </div>

		</div>

		<div id="orderhistory_list_dialog" class="fbox_cont existing_customer">
			<h3>Order History</h3>
        	<div class="table-container">
            	<a class="skip_content filter_tab"><?php echo $button_filter; ?> <span class="icon"></span></a>
       			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="table_orderlist table_customerlist">
        			<thead>
  						<tr>
                            <td class="two">Order id</td>
                            <td class="two">Store</td>
                            <td class="four">Name</td>
                            <td class="five">Status</td>
                            <td class="seven"><?php echo $column_date_added; ?></td>
                            <td class="nine">Total</td>
                            <td class="nine">Paid</td>
                            <td class="nine">Payment Method</td>
                            <td class="nine">Action</td>
                 		</tr>
                  	</thead>

					<tbody id="orderhistor_list_customers">
					</tbody>
				</table>
        	</div><!--table-container-->
		</div>


		<!--- Raza Umer ---->

		<div id="product_details_dialog" class="fbox_cont prod_details">
			<h3><?php echo $text_product_details; ?></h3>
    		<div class="table-container" id="product_details_info">
            	<div class="prod_wrap">
                	<div class="prod_cont">
                    	<div class="prod_l prod_img"><img id="product_details_thumb" src="" width="300" height="300" alt=""></div>
                        <div class="prod_r">
                        	<h5 id="product_details_name"></h5>
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr class="odd">
                                    <td><?php echo $column_details_price; ?></td>
                                    <td class="txt_c">:</td>
                                    <td><span id="product_details_price" class="price"></span></td>
                                </tr>
                                <tr class="even">
                                    <td><?php echo $column_details_model; ?></td>
                                    <td class="txt_c">:</td>
                                    <td id="product_details_model"></td>
                                </tr>
                                <tr class="odd">
                                    <td><?php echo $column_details_quantity; ?></td>
                                    <td class="txt_c">:</td>
                                    <td id="product_details_quantity"></td>
                                </tr>
                                <!-- <tr class="even">
                                    <td><?php echo $column_details_manufacturer; ?></td>
                                    <td class="txt_c">:</td>
                                    <td id="product_details_manufacturer"></td>
                                </tr> -->
                                <tr class="even">
                                    <td><?php echo $column_details_sku; ?></td>
                                    <td class="txt_c">:</td>
                                    <td id="product_details_sku"></td>
                                </tr>
                                <tr class="odd">
                                    <td><?php echo $column_details_upc; ?></td>
                                    <td class="txt_c">:</td>
                                    <td id="product_details_upc"></td>
                                </tr>
                                <tr class="even">
                                    <td><?php echo $column_details_location; ?></td>
                                    <td class="txt_c">:</td>
                                    <td id="product_details_location"></td>
                                </tr>
                                <tr class="odd">
                                    <td>Formula</td>
                                    <td class="txt_c">:</td>
                                    <td id="product_details_labour_cost"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="prod_cont">
                    	<div class="prod_l" id="product_details_description"></div>
                        <div class="prod_r prod_options">
                        	<h6>Attributes</h6>
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            	<thead>
                                    <tr>
                                        <td><?php echo $column_attr_name; ?></td>
                                        <td><?php echo $column_attr_value; ?></td>
                                        <td align="center"><?php echo $column_details_requried; ?></td>
                                    </tr>
                                </thead>
                                <tbody id="product_details_options"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
        	</div><!--table-container-->
		</div>

		<!-- prepare quantity dialog -->
		<div id="quantity_dialog" class="fbox_cont quantity">
			<h3><?php echo $text_change_quantity; ?></h3>
			<div class="table-container">
				<?php $pad_keys = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '.', 'C'); ?>
				<input type="hidden" name="org_quantity" value="" />
				<!-- ali changes 02/03/2020 -->
                <input type="hidden" name="org_unit_conversion" value="" />
                <!-- end ali changes 02/03/2020 -->
				<input type="hidden" name="quantity_index" value="" />
         		<div class="keypad_wrap" id="quantity_pad">
                	<input name="changed_quantity" type="text" class="display">
					<?php foreach ($pad_keys as $pad_key) {?>
                    <a class="btn"><?php echo $pad_key; ?></a>
					<?php }?>
                    <a class="btn ok">OK</a>
                </div><!--keypad_wrap-->
			</div>
		</div>

		<!-- prepare price dialog -->
		<div id="price_discount_dialog" class="fbox_cont price_discount">
			<h3><?php echo $text_change_price; ?></h3>
			<input type="hidden" name="org_price" value="" />
			<input type="hidden" name="price_index" value="" />
			<div class="table-container">
				<div class="popup_discount_selection_bottom">
					<div style="width:50%;float:left;text-transform:uppercase;font-style:normal;">
						<label class="radio_check"><input type="radio" value="discount_group" name="popup_discount_selection_bottom" checked> Discount Group</label>
					</div>
					<div style="width:50%;float:left;text-transform:uppercase;font-style:normal;">
						<label class="radio_check"><input type="radio" value="order_total" name="popup_discount_selection_bottom"> Order Total</label>
					</div>
				</div>
				<div id="popup_discount_selection_bottom_discount_group" style="margin-top:40px;">
					<br><br>
					<?php foreach ($customer_groups as $customer_group) { ?>
						<div class="inputbox">
							<label class="radio_check" style="text-transform: uppercase;"><input type="radio" class="price_disc_radio" value="<?php echo $customer_group['customer_group_id']; ?>" name="discount_customer_group_id" <?php if ($customer_group['customer_group_id'] == $discount_customer_group_id) { echo 'checked'; } ?>> <?php echo $customer_group['name']; ?></label>
						</div>
					<?php } ?>
							 <br><br><br>
							<a class="btn btn-primary" id="apply_to_future_orders" style="text-transform: uppercase;">Apply to future orders</a>&nbsp;&nbsp;
							<a class="btn btn-primary" id="apply_to_this_order" style="text-transform: uppercase;">Apply to this order</a>

				</div>
			 <div id="popup_discount_selection_bottom_order_total" style="display:none;">
        <div class="price_left">
                	<ul class="form_list price_options">
												<li>
                            <div class="inputbox"><label class="radio_check"><input type="radio" class="price_disc_radio" value="discount_level" name="use_discount" checked> Discount Level</label></div>
                        </li>
                        <li>
                            <div class="inputbox"><label class="radio_check"><input type="radio" class="price_disc_radio" value="change_price" name="use_discount"> Unit Price</label></div>
                        </li>
                        <li>
                            <div class="inputbox"><label class="radio_check"><input type="radio" class="price_disc_radio" value="use_discount" name="use_discount"> Product Total</label></div>
                        </li>
                    </ul>
                    <div class="discount_form">
                    	<ul class="form_list">
                            <li>
                                <div class="inputbox"><label class="radio_check"><input type="radio" class="" value="fixed" name="use_discount_type" checked="true"> <?php echo $text_discount_type_amount; ?></label></div>
                            </li>
                            <li>
                                <label class="big"><?php echo $currency_symbol; ?></label>
                                <div class="inputbox"><input name="changed_price_discount_fixed" type="text" value=""></div>
                            </li>
                            <li>
                                <div class="inputbox"><label class="radio_check"><input type="radio" class="" value="percentage" name="use_discount_type"> <?php echo $text_discount_type_percentage; ?></label></div>
                            </li>
                            <li>
                                <label class="big">%</label>
                                <div class="inputbox"><input name="changed_price_discount_percentage" type="text" value="" ></div>
                            </li>
                        </ul>
                        <div class="mask" style="display: none;"></div><!-- Hide this when active -->
                    </div>
                </div><!--price_left-->
         		<div class="price_right">
              		<div class="keypad_wrap" id="price_pad">
                        <input name="changed_price" type="text" class="display" style=" width: 189px;" value="0.00">
						<?php $pad_keys = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '.', 'C'); ?>
						<?php foreach ($pad_keys as $pad_key) {?>
						<a class="btn"><?php echo $pad_key; ?></a>
						<?php }?>
						<a class="btn ok" id="button_ok">OK</a>
						<a class="btn ok" id="button_discount_apply"><?php echo $button_discount; ?></a>
                    </div><!--keypad_wrap-->
                </div><!--price_right-->
					<div id="discount-level-html" style="background-color:#F1F8FF;clear:both;padding:12px;">
							<strong style="font-size:15px;padding-left:10px;">Discount Level Data Loading <i class="fa fa-spinner fa-spin" style="font-size:24px"></i></strong>
							<table width="100%">
									<tr>
										<td width="50%"><input type="radio" name="" id="">&nbsp;&nbsp;&nbsp;Non-Wholesale</td>
										<td width="20%">&nbsp;</td>
										<td style="margin-right:20px;" class="red-price pull-right">&nbsp;</td>
									</tr>
									<tr>
										<td width="50%"><input type="radio" name="" id="">&nbsp;&nbsp;&nbsp;1 - 9</td>
										<td width="20%">&nbsp;</td>
										<td style="margin-right:20px;" class="red-price pull-right">&nbsp;</td>
									</tr>
									<tr>
										<td width="50%"><input type="radio" name="" id="">&nbsp;&nbsp;&nbsp;10 - 49</td>
										<td width="20%">&nbsp;</td>
										<td style="margin-right:20px;" class="red-price pull-right">&nbsp;</td>
									</tr>
									<tr>
										<td width="50%"><input type="radio" name="" id="">&nbsp;&nbsp;&nbsp;50+</td>
										<td width="20%">&nbsp;</td>
										<td style="margin-right:20px;" class="red-price pull-right">&nbsp;</td>
									</tr>
							</table>
							<div class="pull-right" style="clear:both;margin-top:20px;">
								<div class="keypad_wrap">
										<a class="btn ok" id="discount_level_btn">OK</a>
								</div>
							</div>
					</div>
				</div> <!-- popup_discount_selection_bottom_order_total -->
			</div>
		</div>

		<!-- prepare for totals section -->
		<div id="totals_details_dialog" class="fbox_cont totals">
			<h3><?php echo $text_show_totals; ?></h3>
			<div class="table-container form-box margin_0">
         		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table_totals">
					<tbody id="total">
					<?php
					$total_row = 0;
					foreach ($totals as $total_order) {
						$trClass = ($total_row % 2 == 0) ? 'odd' : 'even';
						if ($total_order['code'] == 'total')  {
							$trClass .= ' total';
						}
					?>
						<tr class="<?php echo $trClass; ?>">
							<td><?php echo $total_order['title']; ?> :</td>
							<td><?php echo $total_order['text']; ?></td>
						</tr>
						<?php $total_row++; ?>
					<?php }?>
					</tbody>
                </table>
			</div>
		</div>

		<!-- prepare for payment dialog -->
		<div id="order_payments_dialog" class="fbox_cont payment">
			<h3><?php echo $text_make_payment; ?></h3>
			<div class="table-container form-box" id="return_action_div">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
					<tr class="first-row">
						<td class=""><?php echo $entry_return_action; ?></label>
						<td class="">
							<select name="return_action_id">
								<option value="0"></option>
								<?php if (!empty($return_actions)) { foreach ($return_actions as $return_action) { ?>
								<option value="<?php echo $return_action['return_action_id']; ?>"><?php echo $return_action['name']; ?></option>
								<?php }} ?>
							</select>
						</td>
						<td id="show_order_payments_td" class=""><a class="table-btn"><?php echo $text_order_payment_details; ?></a></div>
					</tr>
				</table>
			</div>

			<div class="table-container" id="payment_action_div">
            	<div class="payment_l">
            		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="payment_list">
            			<tbody>
            			<tr>
                    	<td>
                        	<a href="javascript:changePaymentMethod('cash')"><img src="view/image/pos/dollar.jpg"  alt="" class="payment_images"> </a>
                        </td>
                        <td>
                        	<a href="javascript:changePaymentMethod('credit_card')"><img src="view/image/pos/card.png"  alt="" class="payment_images">
                        </td>
                    </tr><tr>

                    	<td>
                        	<a href="javascript:changePaymentMethod('6')"><img src="view/image/pos/checkbook.png"  alt="" class="payment_images">
                        </td>
                        <td>
                        	<a href="javascript:changePaymentMethod('5')"><img src="view/image/pos/memo.jpg"  alt="" class="payment_images">
                        </td>
                    </tr>

                    </tbody>
                </table>

                </div><!--payment_l-->
         		<div class="payment_r">
         			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="payment_list">
                        <thead>
                            <tr>
                                <td><?php echo $column_payment_type; ?></td>
                                <td class="amount_td"><?php echo $column_payment_amount; ?></td>
                                <td><span id="payment_note_text"><?php echo $column_payment_note; ?></span></td>
                                <td class="action"><?php echo $column_payment_action; ?></td>
                            </tr>
                        </thead>
                        <tbody id="payment_list">
                        	<tr class="first-row" id="button_add_payment_tr">
                            	<td>
                                	<label class="skip_content"><?php echo $column_payment_type; ?></label>
                                	<select name="payment_type" id="payment_type" class="payment_type">
										<?php if (!empty($payment_types)) { foreach($payment_types as $payment_type => $payment_name) { ?>
										<?php if ($payment_type == 'cash') { ?>
										<option value="<?php echo $payment_type ?>" selected="selected"><?php echo $payment_name ?></option>
										<?php } else { ?>
										<option value="<?php echo $payment_type ?>"><?php echo $payment_name ?></option>
										<?php }}} ?>
                                	</select>
                                </td>
								<?php
									$float_amount_due = $payment_due_amount;
									$firstChar = substr($payment_due_amount, 0, 1);
									if (strcmp($firstChar, '0') < 0 || strcmp($firstChar, '9') > 0) {
										$float_amount_due = substr($payment_due_amount, 1);
									}
								?>
                                <td>
                                	<label class="skip_content"><?php echo $column_payment_amount; ?></label>
                                	<div class="inputbox enter_amount enter_amount_2">
                                    	<input type="text" onblur="fillPaidAmount(this.value);" name="tendered_amount" id="tendered_amount" value="<?php echo round(floatval($float_amount_due), 2); ?>">
                                        <a class="clear clear_input" onclick="$(this).closest('div').find('input').val(0);"></a>
                                    </div>
                                </td>
                                <td>
                                	<label class="skip_content"><span id="payment_note_text"><?php echo $column_payment_note; ?></label>
                                	<input type="text" name="payment_note" id="payment_note" value="">
                                </td>
                                <td class="action">
                                	<label class="skip_content">&nbsp;</label>
                                	<a id="button_add_payment" class="table-btn table-btn-add" onclick="addPayment();"><span class="icon"></span> <?php echo $button_add_payment; ?></a>
                                </td>
                            </tr>
							<?php if (!empty($order_payments)) { $trClass = 'even'; foreach ($order_payments as $order_payment) { $trClass = ($trClass == 'even') ? 'odd' : 'even';?>
                            <tr id="order_payment_<?php echo $order_payment['order_payment_id']; ?>" class="<?php echo $trClass; ?>">
                                <td><span class="skip_content label"><?php echo $column_payment_type; ?>:</span><?php echo $order_payment['payment_type']; ?></td>
                                <td><span class="skip_content label"><?php echo $column_payment_amount; ?>:</span>$<?php echo  number_format((float)$order_payment['tendered_amount'], 2, '.', ''); ?></td>
                                <td><span class="skip_content label"><span id="payment_note_text"><?php echo $column_payment_note; ?></span>:</span><?php echo $order_payment['payment_note']; ?></td>
                                <td class="action"><a class="table-btn table-btn-delete-2" onclick="deletePayment(this, '<?php echo $order_payment['order_payment_id']; ?>', '$<?php echo $order_payment['tendered_amount']; ?>');"><span class="icon"></span><?php echo $button_delete; ?></a></td>
                            </tr>
							<?php }} ?>
                        </tbody>
                    </table>
                	<!-- Make this div visible when cash type is selected --->
                	<div id="cash_type_list" class="payment_r_cont payment_cash" style="display:block;">
                        <div class="cash_head">
                            <ul class="form_list">
                                <li>
                                    <label><?php echo $text_accelerate; ?>:</label>
                                    <div class="inputbox">
                                        <label><input name="bx5" type="checkbox" value="5" class=""> <span class="txt">x 5</span></label>
                                        <label><input name="bx10" type="checkbox" value="10" class=""> <span class="txt">x 10</span></label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="currency_wrap">
							<?php if (!empty($cash_types)) { foreach ($cash_types as $cash_type_values) { foreach ($cash_type_values as $cash_type_value) { ?>
								<a onclick="getPaymentCash('<?php echo $cash_type_value['value']; ?>', '<?php echo $cash_type_value['display']; ?>')" class="currency">
									<img src="<?php echo $cash_type_value['image']; ?>" alt="$100">
									<span class="txt"><?php echo $cash_type_value['display']; ?></span>
								</a>
							<?php }}} ?>
                        </div>
                  	</div>

					<!-- add for loyalty customer card begin -->
					<div id="reward_points_payment" style="display: none;">
						<h4 id="reward_points_balance"></h4>
						<div class="table-container">
							<table width="100%" border="0" cellpadding="0" cellspacing="0" class="">
								<thead>
									<tr>
										<td class="text-left"><?php echo $column_product_in_order; ?></td>
										<td class="text-left"><?php echo $column_points; ?></td>
										<td class="text-left"><?php echo $column_rewarded_qty; ?></td>
										<td class="text-left"><?php echo $column_pay_with_points; ?></td>
									</tr>
								</thead>
								<tbody id="reward_points_payment_list"></tbody>
							</table>
						</div>
					</div>
					<!-- add for loyalty customer card end -->
					<!-- for all other cases, use keypad to input payment amount -->
                    <div class="keypad_wrap payment_other" style="display:none;">
						<div class="keypad_wrap" id="payment_pad">
							<!-- <input name="changed_payment" type="text" class="display"> -->
							<?php foreach ($pad_keys as $pad_key) {?>
							<a class="btn"><?php echo $pad_key; ?></a>
							<?php }?>
							<a class="btn ok">OK</a>
						</div><!--keypad_wrap-->
                    </div><!--keypad_wrap-->

                </div><!--payment_r-->
			</div>
            <div class="fbox_btn_wrap">

				<span id="post_payment_action_div">
					<span class="payment_head">

                    	<div class="amount">
                        	Order Total: <span id="dialog_order_total" class="cash"><?php echo $order_total; ?></span>
                        </div>
                        <div class="amount">
                        	Paid: <span id="dialog_total_paid" class="cash"><?php echo $order_paid; ?></span>
                        </div>

                    	<div class="amount">
                        	<?php echo $text_amount_due; ?>: <span id="dialog_due_amount_text" class="cash"><?php echo $payment_due_amount_text; ?></span>
                        </div>
                        <div class="amount">
                        	<?php echo $text_change; ?>: <span id="dialog_change_text" class="cash"><?php echo $order_change_amount; ?></span>
                        </div>

					<a id="button_receipt_complete" class="table-btn-common button_receipt_complete_dev" onclick="confirm_order_status();">Apply Payment</a>

					<?php if (!empty($return_statuses)) { foreach ($return_statuses as $return_status) { ?>
					<a id="button_return_action_<?php echo $return_status['return_status_id']; ?>" class="table-btn-common"  onclick="clickPaymentbtn(<?php echo $return_status['return_status_id']; ?>);" ><?php echo $return_status['name']; ?></a>
					<?php } } ?>
					</span>
				</span>
				<a id="button_email_receipt" onclick="emailReceipt();" class="table-btn-common email hidden"><span class="icon"></span> <?php echo $text_email_receipt; ?></a>
				<div id="emaildialogue" style="display: none;">
				<div class="table-container form-box">
					<ul class="form_list">
	                	<li>
	                        <label><?php echo $entry_receipt_email; ?></label>
	                        <div class="inputbox">
								<input name="receipt_email" id="receipt_email" type="text" value="<?php echo $email; ?>">
								<input type="hidden" name="post_order_id" value="" />
								<input type="hidden" name="post_pos_return_id" value="" />
							</div>
	                    </li>
					</ul>
				</div>
			<div class="fbox_btn_wrap">
				<a onclick="sendReceiptEmail();" class="table-btn-common email"><span class="icon"></span> <?php echo $text_email_receipt; ?></a>
			</div>
				</div>
            </div>
		</div>

		<!-- hidden form for adding products -->
		<div id="product_new" style="display: none;">
			<input type="hidden" name="product_id" value="" />
            <input type="hidden" name="main_product_id" value="" />
			<input type="hidden" name="product" value="" />
			<input type="hidden" name="product_price" value="" />
			<input type="hidden" name="subtract" value="1" />
			<input type="hidden" name="manufacturer_id" value="0" />
			<input type="hidden" name="sku" value="" />
			<input type="hidden" name="upc" value="" />
			<input type="hidden" name="mpn" value="" />
			<input type="hidden" name="model" value="" />
			<!--<input type="hidden" name="quantity" value="1" />-->
			<input type="hidden" name="product_sn_id" value="" />
			<input type="hidden" name="product_image" value="" />
			<input type="hidden" name="product_reward_points" value="" />
			<input type="hidden" name="product_labour_cost" value="" />
			<input type="hidden" name="product_unique_option_price" value="" />
			<div id="option"></div>
			<a id="button_product"><?php echo $button_add_product; ?></a>
		</div>

		<!-- prepare dialog for quick sale -->
		<input type="hidden" id="quick_sale_dialog_fs" value=""/>
		<div id="quick_sale_dialog" class="fbox_cont quick_sale">
			<h3><?php echo $text_title_quick_sale; ?></h3>
			<div class="table-container form-box">
				<h4><?php echo $text_quick_sale; ?></h4>
				<ul class="form_list">
                	<li>
                        <label><?php echo $entry_quick_sale_name; ?> <em>*</em></label>
                        <div class="inputbox"><input name="quick_sale_name" type="text" value=""><input type="hidden" name="quick_sale_product_id" value="0" /></div>
                    </li>
                	<li>
                        <label><?php echo $entry_quick_sale_price; ?> <em>*</em></label>
                        <div class="inputbox"><input name="quick_sale_price" type="text" value=""></div>
                    </li>
                	<li>
                    	<label><?php echo $entry_quick_sale_tax; ?></label>
                        <div class="inputbox">
                            <select name="quick_sale_tax_class_id">
								<option value="<?php echo $tax_classes['tax_class_id']; ?>"><?php echo $tax_classes['title']; ?></option>
								<option value="0">Non-Taxable</option>
                            </select>
                        </div>
                    </li>
                	<li>
                        <label>&nbsp;</label>
                        <div class="inputbox"><label class="radio_check"><input name="quick_sale_include_tax" type="checkbox" value="" disabled="disabled"><?php echo $text_quick_sale_include_tax; ?></label></div>
                    </li>
                	<li>
                        <label><?php echo $entry_quantity; ?> <em>*</em></label>
                        <div class="inputbox"><input name="quick_sale_quantity" type="text" value="1"></div>
                    </li>
				</ul>
			</div>

            <div class="fbox_btn_wrap">
            	<a class="table-btn-common" style="color:#FFFFFF;background:#ecae1c;float:left !important;" onClick="openScannerWithOption('quick_sale_name')"><?php echo $button_scan_product; ?></a>
				<a id="button_quick_sale" class="table-btn-common"><?php echo $button_add_product; ?></a>
            </div>
		</div>

		<!-- prepare for search settings section -->
		<div id="search_settings_dialog" class="fbox_cont search_scope">
		<h3><?php echo $text_search_scope; ?> &nbsp; &nbsp;<span><i class="scope-help fa fa-question-circle" style="color: #0AE6D6; font-size: 14px;" title="Exact Match: &quot; OR ' &#010;Or: Space &#010;And: :::"></i></span></h3>
			<div class="table-container form-box">
         		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table_search_scope">
					<?php $trClass = 'even'; ?>
					<?php $search_scopes = array('name'=>$text_search_product_name, 'model'=>$text_search_model_name, 'manufacturer'=>$text_search_manufacturer, 'sku'=>'SKU', 'upc'=>'UPC', 'mpn'=>'MPN', 'isbn'=>'ISBN', 'location'=>'Location'); ?>
					<?php foreach ($search_scopes as $key => $search_scope) { ?>
					<?php $trClass = ($trClass == 'even') ? 'odd' : 'even'; ?>
                  	<tr class="<?php echo $trClass; ?>">
                    	<td><label><input name="search_scope_<?php echo $key; ?>" type="checkbox" value=""> <?php echo $search_scope; ?></label></td>
                  	</tr>
					<?php }?>
                </table>
			</div>
            <div class="fbox_btn_wrap">
				<a onclick="setSearchScope();" class="table-btn-common"><?php echo $button_set_scope; ?></a>
            </div>
		</div>

		<!-- prepare for search settings section -->
		<div id="shortcuts_dialog" class="fbox_cont shortcuts">
			<h3><?php echo $text_title_more_shortcuts; ?></h3>
			<div class="table-container">
				<ul class="shortcuts_list"></ul>
			</div>
		</div>

		<!-- for order status dialog popups, prepare the div for dialogs -->
		<div id="pos_table_dialog" class="fbox_cont order-status">
			<h3><?php echo $text_table; ?></h3>
			<div class="table-container">
				<ul class="order_status_list">
			<?php
				if (!empty($tables)) {
					foreach ($tables as $table) {
						if ($table['table_id'] == $order_table_id) {
			?>
					<li><a onclick="changeTable(<?php echo $table['table_id']; ?>);" class="table-btn-order-status selected"><span class="icon"></span><?php echo $table['name']; ?><input type="hidden" value="<?php echo $table['table_id']; ?>"></a></li>
			<?php 		} else { ?>
					<li><a onclick="changeTable(<?php echo $table['table_id']; ?>);" class="table-btn-order-status"><?php echo $table['name']; ?><input type="hidden" value="<?php echo $table['table_id']; ?>"></a></li>
			<?php		}
					}
				}
			?>
				</ul>
			</div>
		</div>

		<!-- prepare dialog for order comment -->
		<div id="order_comment_dialog" class="fbox_cont comment">
			<h3><?php echo $text_order_comment; ?></h3>
			<div class="table-container form-box">
         		<ul class="form_list">
                	<li>
                        <div class="inputbox"><textarea name="order_comment"><?php echo $comment; ?></textarea></div>
                    </li>
                 </ul>
			</div>
            <div class="fbox_btn_wrap">
				<a onclick="saveOrderComment();" class="table-btn-common"><?php echo $button_save; ?></a>
            </div>
		</div>

		<!-- prepare dialog for back order customer -->
		<div id="backorder_customer_dialog" class="fbox_cont comment">
			<h3>Select Customer</h3>
			<div class="table-container form-box">
         		<table style="width:100%;" border="0">
                  	<ul class="form_list">
						<tr>
							<td>
								<li>
									<label class="btn btn-success" style="width:15%"><i class="fa fa-user"></i> Customer </label>
									<div class="inputbox" style="display:inline-block;width:70%;margin-left:15px;">
										<input name="backorder_customer_firstname_tab" type="text" value="">
                                		<ul id="append_backorder_customer"></ul>
									</div>
								</li>
							</td>
						</tr>
						<tr>
							<td>
								<li>
									<label class="btn btn-success" style="width:15%"><i class="fa fa-user"></i> Addresses </label>
									<div class="inputbox" style="display:inline-block;width:70%;margin-left:15px;">
										<select id='backorder_customer_addresses' name="backorder_customer_addresses" class="form-control"></select>
									</div>
									<input name="backorder_new_customer_id" id="backorder_new_customer_id" type="hidden">
									<input name="selected_backorder_new_customer_name" id="selected_backorder_new_customer_name" type="hidden">
								</li>
							</td>
						</tr>
					</ul>
				</table>

			</div>
            <div class="fbox_btn_wrap">
			<button id="add_to_new_customer" class="btn btn-primary pull-right" disabled>Create Backorder</button>
            </div>
		</div>

		<!-- prepare dialog for order status confirmation -->
		<div id="order_status_confirm_dialog" class="fbox_cont order_status_update">
			<h3>Confirm Order Status</h3>
			<div class="table-container form-box">
         		<ul class="form_list">
				 	<li class="text-center"><h2><strong>Mark Order As Complete?</strong></h2></li>
					<li></li>
                	<li>
						<?php if (!empty($order_payment_post_status)) { ?>
							<?php foreach ($order_payment_post_status as $post_order_status_id => $post_order_status_name) { ?>
								<?php if($post_order_status_name == "Complete"){ ?>
									<a id="button_order_action_complete" data-id="<?php echo $post_order_status_id; ?>" class="table-btn-order-status <?php if($post_order_status_name == $order_status) { echo ' selected'; } ?>" onclick="clickPaymentbtn(<?php echo $post_order_status_id; ?>);applySelected();" style="width:45%; margin-bottom:15px;margin-left:12px;float:left;">
									<span class="icon"></span>
									<?php echo $post_order_status_name; ?></a>
								<?php } ?>
							<?php } ?>
							<a class="table-btn-order-status" onclick="choose_order_status();" style="width:45%; margin-bottom:15px;margin-left:12px;float:left;"><span class="icon"></span>Not Complete</a>
						<?php } ?>
                 	</li>
					<li></li>
            	</ul>
			</div>
            <div class="fbox_btn_wrap">
				<a class="table-btn-common pull-left"  id="back_to_payment_page" href="javascript:void(0);" onclick="makePayment();backToPaymentPage();"><i class="fa fa-arrow-left" aria-hidden="true"></i> &nbsp;Back To Payment</a>
				<button onclick="makePayment();choose_receipt();" class="btn table-btn-common pull-right btn-proceed-confirm" style="padding: 9px 23px;" disabled>Proceed&nbsp;<i class="fa fa-arrow-right" aria-hidden="true"></i></button>
            </div>
		</div>

		<!-- prepare dialog for order status update -->
		<div id="order_status_update_dialog" class="fbox_cont order_status_update">
			<h3>Change Order Status</h3>
			<div class="table-container form-box">
         		<ul class="form_list">
                	<li>
										<?php if (!empty($order_payment_post_status)) { foreach ($order_payment_post_status as $post_order_status_id => $post_order_status_name) { ?>
										<a id="button_order_action_<?php echo $post_order_status_id; ?>" data-id="<?php echo $post_order_status_id; ?>" class="table-btn-order-status table-btn-common-1 <?php if($post_order_status_name == $order_status) { echo ' selected'; } ?>" onclick="clickPaymentbtn(<?php echo $post_order_status_id; ?>);" style="width:45%; margin-bottom:15px;margin-left:12px;float:left;">
										<span class="icon"></span>
										<?php echo $post_order_status_name; ?></a>
										<?php } } ?>
                 </li>
            </ul>
			</div>
            <div class="fbox_btn_wrap">
						<a class="table-btn-common pull-left"  id="back_to_payment_page" href="javascript:void(0);" onclick="makePayment();backToPaymentPage();"><i class="fa fa-arrow-left" aria-hidden="true"></i> &nbsp;Back To Payment</a>
						<a onclick="makePayment();choose_receipt();" class="table-btn-common pull-right">Proceed To Receipt &nbsp;<i class="fa fa-arrow-right" aria-hidden="true"></i></a>
            </div>
		</div>

		<!-- prepare dialog for cash in and out -->
		<div id="cash_in_out_dialog" class="fbox_cont cash_in_out">
			<h3></h3>
			<div class="table-container">
            	<div class="cash_left">
                	<ul class="form_list">
                        <li>
                            <label><?php echo $column_payment_amount; ?>:</label>
                            <div class="inputbox enter_amount">
                            	<input name="cash_in_out_amount" type="text" class="" value="0">
                            	<a class="clear clear_input"></a>
                                <!--<span class="notes">$100 x 5</span>-->
                            </div>
                        </li>
               		</ul>
                </div><!--cash_left-->
         		<div class="cash_right">
              		<div class="cash_head">
                    	<ul class="form_list">
                            <li>
                                <label><?php echo $text_accelerate; ?>:</label>
                                <div class="inputbox">
                                	<label><input name="ax5" type="checkbox" value="5" class=""> <span class="txt">x 5</span></label>
                                    <label><input name="ax10" type="checkbox" value="10" class=""> <span class="txt">x 10</span></label>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="currency_wrap">
						<?php if (!empty($cash_types)) { foreach ($cash_types as $cash_type_values) { foreach ($cash_type_values as $cash_type_value) { ?>
							<a onclick="getCash('<?php echo $cash_type_value['value']; ?>', '<?php echo $cash_type_value['display']; ?>')" class="currency">
								<img src="<?php echo $cash_type_value['image']; ?>" alt="$100">
								<span class="txt"><?php echo $cash_type_value['display']; ?></span>
							</a>
						<?php }}} ?>
                    </div>

                </div><!--cash_right-->
                <div class="cash_left">
                	<ul class="form_list">
                        <li>
                            <label><?php echo $column_payment_note; ?>:</label>
                            <div class="inputbox"><textarea name="cash_in_out_comment"></textarea></div>
                      	</li>
               		</ul>
                   	<div class="fbox_btn_wrap">
                        <a id="button_cash_in" onclick="cashInOut('cash_in');" class="table-btn-common cash cash_in"><span class="icon"></span> <?php echo $text_cash_in; ?></a>
                        <a id="button_cash_out" onclick="cashInOut('cash_out');" class="table-btn-common cash cash_out"><span class="icon"></span> <?php echo $text_cash_out; ?></a>
                    </div>
                </div><!--cash_left-->
			</div>
		</div>

		<!-- prepare dialog for emailing receipt -->
	<!-- 	<div id="email_receipt_dialog" class="fbox_cont email_receipt">
			<h3><?php echo $text_email_receipt; ?></h3>
			<div class="table-container form-box">
				<ul class="form_list">
                	<li>
                        <label><?php echo $entry_receipt_email; ?></label>
                        <div class="inputbox">
							<input name="receipt_email" type="text" value="<?php echo $email; ?>">
							<input type="hidden" name="post_order_id" value="" />
							<input type="hidden" name="post_pos_return_id" value="" />
						</div>
                    </li>
				</ul>
			</div>
			<div class="fbox_btn_wrap">
				<a onclick="sendReceiptEmail();" class="table-btn-common email"><span class="icon"></span> <?php echo $text_email_receipt; ?></a>
			</div>
		</div> -->

		<!-- for return list dialog popups, prepare the div for dialogs -->
		<div id="return_list_dialog" class="fbox_cont order-list">
			<h3><?php echo $text_return_list; ?></h3>
        	<div class="table-header">
				<?php if ($display_delete) { ?>
        		<a onclick="deleteReturn(this);" class="table-btn-delete"><span class="icon"></span><?php echo $button_delete; ?></a>
				<?php } ?>
       		</div>
			<div class="table-container">
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="table_orderlist">
        			<thead>
  						<tr>
                            <td class="one checkbox-head"><label class="radio_check"><input type="checkbox" onclick="$('input[name*=return_selected]').prop('checked', $(this).is(':checked'));" /> <span class="skip_content">Select All</span></label></td>
                            <td class="two"><?php echo $column_pos_return_id; ?></td>
                            <td class="four"><?php echo $column_customer; ?></td>
                            <td class="five"><?php echo $column_status; ?></td>
                            <td class="six"><?php echo $column_return_total; ?></td>
                            <td class="seven"><?php echo $column_date_added; ?></td>
                            <td class="eight"><?php echo $column_date_modified; ?></td>
                            <td class="nine"><?php echo $column_action; ?></td>
                 		</tr>
                  	</thead>
                    <tbody>
  						<tr class="first-row">
                            <td class="one filter"><a class="skip_content filter_tab"><?php echo $button_filter; ?><span class="icon"></span></a></td>
                            <td class="two"><label class="skip_content"><?php echo $column_pos_return_id; ?></label><input name="filter_pos_return_id" type="text" value=""></td>
                            <td class="four"><label class="skip_content"><?php echo $column_customer; ?></label><input name="filter_return_customer" type="text" class="auto_complete"></td>
                            <td class="five">
                            	<label class="skip_content"><?php echo $column_status; ?></label>
								<select name="filter_return_status_id">
									<option value="*"></option>
									<?php foreach ($return_statuses as $return_status) { ?>
									<option value="<?php echo $return_status['return_status_id']; ?>"><?php echo $return_status['name']; ?></option>
									<?php } ?>
								</select>
                            </td>
                            <td class="six"><label class="skip_content"><?php echo $column_return_total; ?></label><input name="filter_return_total" type="text"></td>
                            <td class="seven"><label class="skip_content"><?php echo $column_date_added; ?></label><input name="filter_return_date_added" type="text" class="date"></td>
                            <td class="eight"><label class="skip_content"><?php echo $column_action; ?></label><input name="filter_return_date_modified" type="text" class="date"></td>
                            <td class="nine"><label class="skip_content">&nbsp;</label><a id="button_return_filter" onclick="filterReturn();" class="table-btn table-btn-filter"><span class="icon filter"></span> <?php echo $button_filter; ?></a></td>
                   		</tr>
					</tbody>
					<tbody id="return_list_returns"></tbody>
				</table>
			</div>
			<div id="return_list_pagination" class="table-pagination"></div>
		</div>

		<div id="return_dialog" class="fbox_cont return_dialog">
			<h3><?php echo $text_return_add; ?></h3>
			<div class="table-container form-box">
				<ul class="form_list">
                	<li>
                        <label><?php echo $column_product_name; ?>:</label>
                        <div class="inputbox"><input id="return_product_name" value="" readonly /></div>
                    </li>
                	<li>
                        <label><?php echo $entry_model; ?></label>
                        <div class="inputbox"><input id="return_product_model" value="" readonly /></div>
                    </li>
                	<li>
                        <label><?php echo $entry_options; ?></label>
                        <div class="inputbox" id="return_product_options"></div>
                    </li>
                	<li>
                        <label><?php echo $entry_quantity; ?></label>
                        <div class="inputbox"><input name="return_quantity" type="text" value="1" /></div>
                    </li>
                	<li>
                        <label><?php echo $entry_opened; ?></label>
                        <div class="inputbox">
							<select name="return_opened">
								<option value="0"><?php echo $text_unopened; ?></option>
								<option value="1"><?php echo $text_opened; ?></option>
							</select>
						</div>
                    </li>
                	<li>
                        <label><?php echo $entry_return_reason; ?></label>
                        <div class="inputbox">
							<select name="return_reason_id">
								<?php if (!empty($return_reasons)) { foreach ($return_reasons as $return_reason) { ?>
								<option value="<?php echo $return_reason['return_reason_id']; ?>"><?php echo $return_reason['name']; ?></option>
								<?php }} ?>
							</select>
						</div>
                    </li>
                	<li>
                        <label><?php echo $column_payment_note; ?>:</label>
                        <div class="inputbox">
							<textarea name="return_comment"></textarea>
							<input type="hidden" name="return_order_product_id" value="" /><input type="hidden" name="return_product_id" value="" />
						</div>
                    </li>
				</ul>
			</div>
			<div class="fbox_btn_wrap">
				<a id="button_return" class="table-btn-common"><?php echo $button_return_product; ?></a>
			</div>
		</div>

		<div id="return_details_dialog" class="fbox_cont return_details">
			<h3><?php echo $text_return_details_title; ?></h3>
			<div class="table-container form-box" id="return_details">
				<ul class="form_list">
                	<li>
                        <label><?php echo $column_product_name; ?>:</label>
                        <div class="inputbox"><input type="text" id="return_details_product_name" value="" readonly></div>
                    </li>
                	<li>
                        <label><?php echo $entry_model; ?></label>
                        <div class="inputbox"><input type="text" id="return_details_product_model" value="" readonly></div>
                    </li>
                	<li>
                        <label><?php echo $entry_options; ?></label>
                        <div class="inputbox"><textarea id="return_details_product_options" readonly></textarea></div>
                    </li>
                	<li>
                        <label><?php echo $entry_quantity; ?></label>
                        <div class="inputbox"><input type="text" id="return_details_product_quantity" value="" readonly></div>
                    </li>
                	<li>
                        <label><?php echo $entry_opened; ?></label>
                        <div class="inputbox"><input type="text" id="return_details_product_opened" value="" readonly></div>
                    </li>
                	<li>
                        <label><?php echo $entry_return_reason; ?></label>
                        <div class="inputbox"><input type="text" id="return_details_return_reason" value="" readonly></div>
                    </li>
                	<li>
                        <label><?php echo $column_payment_note; ?>:</label>
                        <div class="inputbox"><input type="text" id="return_details_comment" value="" readonly></div>
                    </li>
                	<li>
                        <label><?php echo $column_return_time; ?>:</label>
                        <div class="inputbox"><input type="text" id="return_details_return_time" value="" readonly></div>
                    </li>
				</ul>
			</div>
		</div>

		<!-- order payment details dialog, to be used in return mode to use the paid info as references -->
		<div id="order_payments_details_dialog" class="fbox_cont order_payment_details">
			<h3><?php echo $text_order_payment_details; ?></h3>
			<div class="table-container">
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="">
					<thead id="reward_points_payment_header">
						<tr>
							<td class="text-left"><?php echo $column_payment_type; ?></td>
							<td class="text-left"><?php echo $column_payment_amount; ?></td>
							<td class="text-left"><?php echo $column_payment_note; ?></td>
							<td class="text-left"><?php echo $column_payment_time; ?></td>
						</tr>
					</thead>
					<tbody id="details_payment_list"></tbody>
				</table>
			</div>
		</div>

		<!-- existing return products -->
		<div id="existing_returns_dialog" class="fbox_cont existing_returns">
			<h3><?php echo $text_existing_returns; ?></h3>
			<div class="table-container">
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="">
					<thead>
						<tr>
							<td class="text-left"><?php echo $column_return_product; ?></td>
							<td class="text-left"><?php echo $column_return_customer; ?></td>
							<td class="text-left"><?php echo $column_return_email; ?></td>
							<td class="text-left"><?php echo $column_return_quantity; ?></td>
							<td class="text-left"><?php echo $column_return_comment; ?></td>
							<td class="text-left"><?php echo $column_return_time; ?></td>
						</tr>
					</thead>
					<tbody id="existing_returns_list"></tbody>
				</table>
			</div>
		</div>

		</div> <!-- end for the hidden wrapper for all dialogs -->

		<div class="alert_cont" id="alert_dialog">
			<p></p>
			<div class="fbox_btn_wrap">
				<a id="alert_cancel" class="table-btn table-btn-grey alert_hide" onclick="$('.alert_cont').hide(); $('.alert_cont .alert_hide').show();"><?php echo $button_cancel; ?></a>
				<a id="alert_ok" class="table-btn table-btn-delete-2 alert_hide"><?php echo $button_ok; ?></a>
			</div>
		</div>
		<div class="alert_cont" id="pos_wait_msg">
			<p><i class="fa fa-spinner fa-spin"></i> <span></span></p>
		</div>
	</div>

<!-- Module FOR PLUGIN BY WAQAR -->
<!-- Modal -->
<div id="updateStock" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content form-horizontal" id="update_stock_popup_modal">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Stock Manager : Update Stock </h4>
      </div>

      <div class="modal-body">
      	<form method="post"  id="custom_form_raqaw">
        <div class="row" id="product_stock_data">

        </div>

       <div class="form-group">
         <label class="control-label col-sm-2">In Stock</label>
         <div class="col-sm-5">
           <input type="radio" name="instock" checked="checked" value="1" id="instock" > Yes
         </div>
         <div class="col-sm-5">
           <input type="radio" name="instock" id="outstock" value="0"> No
         </div>
       </div>

       <div class="row" id="product_stock_data_html">

        </div>

       <div id="appendStorageHtml"></div>
       <div class="form-group">
         <label class="control-label col-sm-2">Total Instock</label>
         <div class="col-sm-10">
          <input type="text" name="total_instock" value="" class="form-control" placeholder="Total Instock">
         </div>
       </div>
       <div class="form-group">
        <div class="col-sm-12">
				 <button type="button" id="button_update_popup" class="btn btn-success" name="sub_popup"><i class="fa fa-refresh"></i>&nbsp; Update Stock</button>
         <button type="button" id="addStorageLocation" class="btn btn-info pull-right" data-counter="0"><i class="fa fa-plus"></i>&nbsp; Add Location</button>
       </div>
       </div>
       <input type="text" class="hidden" id="productid" name="product_id" value="">

		</form>
			 <div class="row" id="product_incoming_order_history_html">
       </div>
			 <div class="form-group">
         <label class="control-label col-sm-2" for="incoming_backorder_quantity">Quantity</label>
         <div class="col-sm-10">
          <input type="text" name="incoming_backorder_quantity" id="incoming_backorder_quantity" value="1" class="form-control" placeholder="Quantity">
         </div>
       </div>

			 <div class="form-group" style="border-top:none !important;margin-bottom:0;">
        <div class="col-sm-12">
         <input type="hidden" id="current_popup_product_id" value="">
         <button type="button" class="btn btn-primary" id="add_to_incoming_order">Add to Incoming Order</button>
         <button type="button" class="btn btn-success" id="create_backorder">Create Backorder</button>
        </div>
       </div>
			 <div class="form-group" style="border-top:none !important;display:none;margin-top:0;padding-top:0;" id="incoming_order_block">
        <div class="col-sm-6 col-sm-offset-2">

         <button type="button" class="btn btn-primary" id="add_to_pending_incoming_order">Add to Pending</button>
         <button type="button" class="btn btn-success pull-right" id="create_new_incoming_order">Create New</button>
        </div>
       </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
      	<form id="product_labels_form" method="POST" action="index.php?route=module/pos/labels_orders&token=<?php echo $token; ?>" target="_blank"  class="form-horizontal">
			<span id="custom_lable_print_from">

    <script type="text/javascript">

    var row=0;
    var nlabels = 0;
    var token = '<?php echo $token ?>';
    var scale=1;
    var options_list = new Array();
    var label_active = new Array();
    var blanks = new Array();

    //options_list[<?php /*echo $product_options['product_option_id'] ?>] = ['<?php echo join('\',\'',array_map(function($element){return $element['name'];}, $product_options['product_option_value'])) */?>'];


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
    </script>
                  <!-- Product Labels data -->
                  <input type="hidden" id="prolab_orderid" name="orderid" value="<?php echo $order_id; ?>">
                  <input type="hidden" id="prolab_orderids" name="orderids" value="1">
                  <input type="hidden" id="prolab_sample" name="sample" value="0">
                  <input type="hidden" id="prolab_blanks" name="blanks" value="">
                  <input type="hidden" id="prolab_templateid" name="templateid" value="">
                  <input type="hidden" id="prolab_labelid" name="labelid" value="">
                  <input type="hidden" id="prolab_orientation" name="orientation" value="p">
                  <input type="hidden" id="prolab_proids" name="productid" value="">
				 <!-- Product Labels data -->
				<?php if(!empty($products)){ ?>
					<?php foreach($products as $product){  ?>
					<input type="hidden" name="product_name[]" value="<?php echo $product['name'];?>"/>
					<input type="hidden" name="product_model[]" value="<?php echo $product['model'];?>"/>
					<?php } ?>
				<?php } ?>
			</span>
		</form>
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
												<option value="" selected><?php echo $text_option_new_label; ?></option>
												<?php $name_i = 1; ?>
												<?php foreach($labels as $id => $label_name) { ?>
												<?php 	if(empty($label_name)) { ?>
												<?php 		$label_name="Label_".$name_i; ?>
												<?php 		$name_i++; ?>
												<?php 	} ?>
												<option value="<?php echo $id ?>"><?php echo $label_name; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12" class="form-group form-group-sm">
										<form id="edit_label_form" class="form-horizontal">
										<input type="hidden" name="route" value="module/pos/labels">
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
											<button type="button" onclick="preview_label();return false;" id="previewbutton_label" class="btn btn-sm btn-primary"><?php echo $button_preview; ?></button>
										</div>
										<div class="col-sm-6">
											<select name="templateid"  id="popupprotemplateid" class="select_template form-control">
											<?php foreach($label_templates as $id => $label_template) { ?>
												<option value="<?php echo $id ?>"<?php if($settings['product_labels_default_template'] == '$id') echo " selected" ?>><?php echo $label_template; ?></option>
											<?php } ?>
											</select>
										</div>
										<div class="col-sm-4">
											<select name="orientation"  class="form-control" id="popupproorientation">
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
        <!-- Label Code -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary popupprintonelabel">Print Label</button>
        <button type="button" class="btn btn-primary popupprintalllabel">Print All Label</button>
      </div>
    </div>
  </div>
</div>
<!-- Print Label Modal -->

<!-- Module FOR PLUGIN BY WAQAR -->
</div>

<script type="text/javascript" src="view/javascript/pos/jquery.ui.touch-punch.min.js"></script>
<script type="text/javascript" src="view/javascript/pos/barcode.min.js"></script>
<script type="text/javascript" src="view/javascript/pos/pos_vars.js"></script>
<script type="text/javascript" src="view/javascript/pos/pouchdb-3.3.1.min.js"></script>
<script type="text/javascript" src="view/javascript/pos/pouchdb.gql.js"></script>
<script type="text/javascript">
	var token = '<?php echo $token; ?>';
	order_id = '<?php echo $order_id; ?>';
	config = <?php echo json_encode($config); ?>;
</script>
<script type="text/javascript" src="view/javascript/pos/pos_backend.js"></script>
<script type="text/javascript" src="view/javascript/pos/jquery.fancybox.pack.js"></script>
<script type="text/javascript" src="view/javascript/pos/pos.js?v=<?php echo rand(0,9999999);?>"></script>
<!-- <script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui.min.js"></script> -->
<script type="text/javascript">
	$(document).ready(function() {
		$('.scope-help').click(function() {
			$(this).toggleClass('show-title');
		});
	});
	$("#addStorageLocation").click(function(){
    var htmlStorage="";

    var counters=$("#addStorageLocation").attr('data-counter');

    htmlStorage +='<div id="main_storge'+counters+'"><div class="form-group">';
    htmlStorage +='   <label class="col-sm-2 control-label">Quantity </label>';
    htmlStorage +='<div class="col-sm-10">';
    htmlStorage +=' <input type="text" name="locations['+counters+'][location_quantity]" placeholder="Quantity" id="quantity_up'+counters+'" value="" class="form-control">';
    htmlStorage +='</div>';
    htmlStorage +='</div>';

    htmlStorage +='<div class="form-group">';
    htmlStorage +=' <label class="col-sm-2 control-label">Location</label>';
    htmlStorage +='   <div class="col-sm-10">';
    htmlStorage +='    <input type="text" name="locations['+counters+'][location_name]" id="location_popup'+counters+'" value="" class="form-control">';
    htmlStorage +='   </div>';
    htmlStorage +=' </div>';
    htmlStorage += '<div class="col-sm-12 pull-right"><button onclick="removeRow('+counters+');" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></div></div>';

    $("#appendStorageHtml").append(htmlStorage);
counters++;
    $("#addStorageLocation").attr('data-counter',counters);

});

function removeRow(row){
$("#main_storge"+row).remove();
}

</script>
<script type='text/javascript'>
$(document).on('click','#new_customer',function(e){
	$('#fbox_btn_wrap_save_button').html('');
	$('#fbox_btn_wrap_save_button').html('<a onclick="newCustomer();" class="table-btn-common customer new"><span class="icon"></span>Add new customer</a>');
});
$(document).on('click','#customer_select',function(e){
	$('#fbox_btn_wrap_save_button').html('');
	$('#fbox_btn_wrap_save_button').html('<a onclick="saveCustomer(false);" class="table-btn-common">Save</a>');
});
$(document).on('click','#customer_addresses_tab',function(e){
	$('#fbox_btn_wrap_save_button').html('');
	$('#tab_customer_general').addClass('active');
	$('#fbox_btn_wrap_save_button').html('<a onclick="saveCustomer(false);" class="table-btn-common">Save</a>');
});
$(document).on('click','#customer_order_history',function(e){
	$('#fbox_btn_wrap_save_button').html('');
	$('#fbox_btn_wrap_save_button').html('<a onclick="saveCustomer(false);" class="table-btn-common">Save</a>');
});
$(document).on('click','#customer_return_history',function(e){
	$('#fbox_btn_wrap_save_button').html('');
	$('#fbox_btn_wrap_save_button').html('<a onclick="saveCustomer(false);" class="table-btn-common">Save</a>');
});
$(document).on('click','#customer_notes',function(e){
	$('#fbox_btn_wrap_save_button').html('');
	$('#fbox_btn_wrap_save_button').html('<a onclick="saveCustomer(false);" class="table-btn-common">Save</a>');
});
var poppro = 0;
	var allproducts = 0;

	$(document).on('click','.productlabelpopup',function(e){
		e.preventDefault();
		$('#printModal').modal('show');
		poppro = $(this).attr("data-productid");
		allproducts = $("#prolab_proids").val();
	});
	$(document).on('click','.productlabelpopup',function(e){
		e.preventDefault();
		$('#printModal').modal('show');
		poppro = $(this).attr("data-productid");
		allproducts = $("#prolab_proids").val();
	});

	$(document).on('click','.popupprintonelabel',function(e){
		$("#prolab_proids").val(poppro);
		var current_label 			= $("#popupprolabel").val();
		var current_template 		= $("#popupprotemplateid").val();
		var current_orientation 	= $("#popupproorientation").val();

		$("#prolab_templateid").val(current_template);
		$("#prolab_labelid").val(current_label);
		$("#prolab_orientation").val(current_orientation);
		$("#prolab_proids").val(poppro);
		$("#product_labels_form").submit();
		//$('#printModal').modal('hide');
	});

	$(document).on('click','.popupprintalllabel',function(e){
		$("#prolab_proids").val(poppro);
		var current_label 			= $("#popupprolabel").val();
		var current_template 		= $("#popupprotemplateid").val();
		var current_orientation 	= $("#popupproorientation").val();

		$("#prolab_templateid").val(current_template);
		$("#prolab_labelid").val(current_label);
		$("#prolab_orientation").val(current_orientation);
		$("#prolab_proids").val(allproducts);

		$("#product_labels_form").submit();
		//$('#printModal').modal('hide');
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


	//$(function() {
		//$('#pltabs a:first').tab('show');
		preview_template();
		preview_label();
		check_update();
	//});
	function openStockModel(product_id, popup_var = false){
     $.ajax({
      type:'post',
      url: 'index.php?route=module/pos/getStockRelatedData&token='+token,
      cache:false,
      dataType:'json',
      data: {'product_id':product_id},
      success:function(data){
				$("#current_popup_product_id").val(product_id);
        var htmlstockdata1="";
        var htmlstockdata2="";
				var html_incoming_order_history ="";
        var labourcost=data.product_data.labour_cost;
        var unique_option_price=data.product_data.unique_option_price;
        htmlstockdata1 += '<div class="row"><div style="padding:30px;" class="col-sm-2"><img src="../image/'+data.product_data.image+'" width="60"></div><div class="col-sm-10"><h3> '+data.product_data.name+'</h3><div class="col-sm-4"><b>Model: '+data.product_data.model+'</b></div><div class="col-sm-4"><b>MPN: '+data.product_data.mpn+'</b></div><div class="col-sm-4"><b>Price: '+data.product_data.price+'<br/>('+parseFloat(labourcost).toFixed(4)+'+'+parseFloat(unique_option_price).toFixed(8)+')</b></div><div class="col-sm-4"><b>SKU: '+data.product_data.sku+'</b></div><div class="col-sm-4"><b>UPC: '+data.product_data.upc+'</b></div></div></div>';

        if(data.location_data){
        var counter=0;
          $(data.location_data).each(function(i){


        htmlstockdata2 +='<div id="main_storge'+counter+'"><div class="form-group">';
        htmlstockdata2 +='   <label class="col-sm-2 control-label">Quantity </label>';
        htmlstockdata2 +='<div class="col-sm-10">';
        htmlstockdata2 +=' <input type="text" name="locations['+counter+'][location_quantity]" placeholder="Quantity" id="quantity_up'+counter+'" value="'+data.location_data[i].location_quantity+'" class="form-control">';
        htmlstockdata2 +='</div>';
        htmlstockdata2 +='</div>';

        htmlstockdata2 +='<div class="form-group">';
        htmlstockdata2 +=' <label class="col-sm-2 control-label">Location</label>';
        htmlstockdata2 +='   <div class="col-sm-10">';
        htmlstockdata2 +='    <input type="text" name="locations['+counter+'][location_name]" id="location_popup'+counter+'" value="'+data.location_data[i].location_id+'" class="form-control">';
        htmlstockdata2 +='   </div>';
        htmlstockdata2 +=' </div>';
        htmlstockdata2 += '<div class="col-sm-12 pull-right"><button onclick="removeRow('+counter+');" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></div></div>';
        counter++;
             });
      }

			if(!jQuery.isEmptyObject(data.incoming_orders)){
        var incoming_order_data = data.incoming_orders;
        html_incoming_order_history += '<h3 style="margin-left:10px;">Incoming Order History</h3>';
        html_incoming_order_history += '<div class="table-responsive"><table class="table table-bordered table-hover"><thead><tr>';
        html_incoming_order_history += '<td>Order ID</td><td>Manufacturer</td><td>Status</td><td>No Of Items</td><td>Date Added</td><td>Action</td></tr></thead><tbody>';
        $(incoming_order_data).each(function(i){
          //alert(JSON.stringify(incoming_order_data[i].status, null, 4));
            html_incoming_order_history += '<tr><td>' + incoming_order_data[i].order_id + '</td>';
            html_incoming_order_history += '<td>' + incoming_order_data[i].manufacturer + '</td>';
            html_incoming_order_history += '<td>' + incoming_order_data[i].status + '</td>';
            html_incoming_order_history += '<td>' + incoming_order_data[i].no_of_items + '</td>';
            html_incoming_order_history += '<td>' + incoming_order_data[i].date_added + '</td>';
            html_incoming_order_history += '<td>';
            html_incoming_order_history += '<a href="'+incoming_order_data[i].view+'" data-toggle="tooltip" title="View" class="btn btn-sm btn-info"><i class="fa fa-eye"></i></a> <a href="'+incoming_order_data[i].edit+'" data-toggle="tooltip" title="Edit" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i></a><a href="'+incoming_order_data[i].pdfinv_packing+'" data-toggle="tooltip" title="Packing Slip" class="btn btn-warning btn-sm" target="_blank"><i class="fa fa-file-text-o"></i></a><a href="'+incoming_order_data[i].link_pdf_order_request+'" data-toggle="tooltip" title="PDF Order Request" class="btn btn-success btn-sm" target="_blank"><i class="fa fa-file-text-o"></i></a><a href="'+incoming_order_data[i].copy+'" data-toggle="tooltip" title="Copy" class="btn btn-warning btn-sm" onclick="return confirm(\'Do you really want to copy this order?\') ? true : false;"><i class="fa fa-copy"></i></a><button type="button" value="'+incoming_order_data[i].order_id+'" id="button-delete'+incoming_order_data[i].order_id+' data-toggle="tooltip" title="Delete" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></button></td></tr>';
        });
        html_incoming_order_history += '</tbody></table></div>';
      }

        $("#addStorageLocation").attr("data-counter",counter);

        $("#productid").val(product_id);
        $("input[name=total_instock]").val(data.product_data.quantity);

        $("#product_stock_data").html(htmlstockdata1);
        $("#product_stock_data_html").html(htmlstockdata2);
				$("#product_incoming_order_history_html").html(html_incoming_order_history);

				if(popup_var == true)
				{
					$('#update_stock_popup_modal button[data-dismiss=modal]').attr("onclick","reopenSelectProduct()");
				} else {
					$('#update_stock_popup_modal button[data-dismiss=modal]').removeAttr("onclick");
				}
       $('#updateStock').modal({
	        show: 'false'
	    });
      //$("#invoke_update_stock").trigger('click');
        // console.log(data.location);
      }
     });
}

function reopenSelectProduct()
{
	var product_id = $("#product_new input[name=product_id]").val();
	selectProduct(product_id);
}
$("#add_to_incoming_order").click(function() {
	$( '#add_to_incoming_order' ).prop('disabled', true);
	$( '#create_backorder' ).prop('disabled', true);
	$( "#add_to_incoming_order" ).removeClass( "btn-primary" );
	$( "#add_to_incoming_order" ).addClass("btn-gray");
	$( "#create_backorder" ).removeClass( "btn-success" );
	$( "#create_backorder" ).addClass("btn-gray");
	$( "#incoming_order_block" ).show();

});
$("#create_new_incoming_order").click(function() {
	$("#success_html").empty();
	var product_id = $("#current_popup_product_id").val();
	var quantity 		= $("input[name=incoming_backorder_quantity]").val();
  $.ajax({
    url: 'index.php?route=sale/orderq/create_incoming_order&product_id='+product_id+'&quantity='+quantity+'&order_id=' + $('input[name=\'orderid\']').val()+'&token=<?php echo $token; ?>',
    dataType: 'json',
    crossDomain: true,
    success: function(json) {
			$( '#add_to_incoming_order' ).prop('disabled', false);
			$( '#create_backorder' ).prop('disabled', false);
			$( "#add_to_incoming_order" ).addClass( "btn-primary" );
			$( "#add_to_incoming_order" ).removeClass("btn-gray");
			$( "#create_backorder" ).addClass( "btn-success" );
			$( "#create_backorder" ).removeClass("btn-gray");
			$( "#incoming_order_block" ).hide();
      var success_html = '<div class="alert alert-success" id="success_html">'+ json['msg'] +'</div>';
      $( "#add_to_incoming_order" ).before( success_html );
    }
  });

});

$("#add_to_pending_incoming_order").click(function() {
	$("#success_html").empty();
	var product_id = $("#current_popup_product_id").val();
	var quantity 		= $("input[name=incoming_backorder_quantity]").val();
  $.ajax({
    url: 'index.php?route=sale/orderq/add_to_pending_incoming_order&product_id='+product_id+'&quantity='+quantity+'&order_id=' + $('input[name=\'orderid\']').val()+'&token=<?php echo $token; ?>',
    dataType: 'json',
    crossDomain: true,
    success: function(json) {
			$( '#add_to_incoming_order' ).prop('disabled', false);
			$( '#create_backorder' ).prop('disabled', false);
			$( "#add_to_incoming_order" ).addClass( "btn-primary" );
			$( "#add_to_incoming_order" ).removeClass("btn-gray");
			$( "#create_backorder" ).addClass( "btn-success" );
			$( "#create_backorder" ).removeClass("btn-gray");
			$( "#incoming_order_block" ).hide();
      var success_html = '<div class="alert alert-success" id="success_html">'+ json['msg'] +'</div>';
      $( "#add_to_incoming_order" ).before( success_html );
    }
  });

});

$("#create_backorder").click(function() {
	$("#success_html").empty();
  var product_id 	=  $("#current_popup_product_id").val();
  var customer_id = $("input[name=customer_id]").val();
  var quantity 		= $("input[name=incoming_backorder_quantity]").val();
  $.ajax({
    url: 'index.php?route=sale/orderq/create_backorder&product_id='+product_id+'&customer_id='+customer_id+'&quantity='+quantity+'&order_id=' + $('input[name=\'orderid\']').val()+'&token=<?php echo $token; ?>',
    dataType: 'json',
    crossDomain: true,
    success: function(json) {
      var success_html = '<div class="alert alert-success" id="success_html">'+  json['msg'] +'</div>';
      $( "#add_to_incoming_order" ).before( success_html );
    }
  });
});


$("#button_update_popup").click(function(){

  /*if($("#quantity_up").val()==""&&$("input[name=instock_popup]:checked").val()==7){
    alert("Please enter instock quantity");
    $("#quantity_up").focus();
    return false;
  }*/
  //alert($("#updateStock input[type^=text],#updateStock input[type^=hidden], #updateStock input[type=radio]:checked").val());
  $form = $("#custom_form_raqaw");
  $input = $form.find("input")
  var $inputs = $form.find("input, select, button, textarea");
  var serializedData = $form.serialize();
  $("#button_update_popup").html("Updating");
  $.ajax({
    type:'post',
    url:'index.php?route=module/pos/updateStocks&token=<?php echo $token; ?>',
    cache:false,
    data:serializedData,
    success:function(data){

         $("#button_update_popup").html("<i class='fa fa-refresh'></i> updated");

    }
  });
});

	$('#shipping_title,#shipping_value').on('change', function() {
		$("#input-shipping-method option[value='free.free']").attr('selected', 'selected');
    });
</script>
<!----  UPDATE STOKED BY WAQAR  -->

<script type='text/javascript'>
	// Incoming Orders
	$('#tab_incoming_orders').delegate('.pagination a', 'click', function(e) {
	e.preventDefault();
	$('#tab_incoming_orders').load(this.href);
});
	function loadIncomingOrders()
	{
		if($("#tab_incoming_orders").html() == '')
		{
			$("#tab_returns").html("");
			$("#tab_backorders").html("");
			var html = '<div style="margin-top:30px;text-align:center"><i class="fa fa-spinner fa-spin"></i> Loading Incoming Orders, Please Wait!</div>';
			$("#tab_incoming_orders").html(html);
			$('#tab_incoming_orders').load('index.php?route=sale/incoming&token=<?php echo $token; ?>');
		}
	}

	// Returns
	$('#tab_returns').delegate('.pagination a', 'click', function(e) {
		e.preventDefault();
		$('#tab_returns').load(this.href);
	});
	function loadReturns() {
		$("#tab_backorders").html("");
		$("#tab_incoming_orders").html("");
		var html = '<div style="margin-top:30px;text-align:center"><i class="fa fa-spinner fa-spin"></i> Loading Returns, Please Wait!</div>';
		$("#tab_returns").html(html);
		$('#tab_returns').load('index.php?route=sale/return&token=<?php echo $token; ?>');
	}

	// BackOrders
	$('#tab_backorders').delegate('.pagination a', 'click', function(e) {
	e.preventDefault();
	$('#tab_backorders').load(this.href);
});
	function loadBackOrders()
	{
		if($("#tab_backorders").html() == '')
		{
			$("#tab_incoming_orders").html("");
			$("#tab_returns").html("");
			var html = '<div style="margin-top:30px;text-align:center"><i class="fa fa-spinner fa-spin"></i> Loading BackOrders, Please Wait!</div>';
			$("#tab_backorders").html(html);
			$('#tab_backorders').load('index.php?route=sale/backorder&token=<?php echo $token; ?>');
		}
	}

	function fillPaidAmount(amount){
     var prev_amount=$("#paidamount").text();
     $("#paidamount").html(parseFloat(prev_amount)+parseFloat(amount));
	}
            function printQrCodeFromMobile(value)
            {
				document.getElementById('search').value = value
				delay(function() {
					// search product when input product name in search field
					var filter_name = $('input[name=filter_product]').val();
					if (filter_name != '') {
						var url = 'index.php?route=module/pos/autocomplete_product&token=' + token;
						var data = {'filter_name':filter_name, 'customer_group_id':customer_group_id, 'filter_scopes':searchScopes};
						$.ajax({
							url: url,
							type: 'post',
							data: data,
							dataType: 'json',
							cacheCallback: function(json) {
								backendSaveProducts(json);
							},
							cachePreDone: function(cacheKey, callback) {
								backendGetProducts(data, callback);
							},
							success: function(json) {
								if (json && json.length == 1) {
									// a single product
									$('input[name=current_product_id]').val(json[0]['product_id']);
									$('input[name=current_product_name]').val(json[0]['name']);
									$('input[name=current_product_weight_price]').val(json[0]['weight_price']);
									$('input[name=current_product_weight_name]').val(json[0]['weight_name']);
									$('input[name=current_product_hasOption]').val(json[0]['hasOptions']);
									$('input[name=current_product_price]').val(json[0]['price']);
									$('input[name=current_product_tax]').val(json[0]['tax']);
									$('input[name=current_product_points]').val(json[0]['points']);
									$('input[name=current_product_image]').val(json[0]['image']);
								}
								populateBrowseTable(json, true);
							}
						});
					}
				}, 500);
            }
            function getText()
            {
                return document.getElementById('search').value;
            }

            function getOS() {
            	var OSName="Unknown OS";
				if (navigator.appVersion.indexOf("Win")!=-1) OSName="Windows";
				if (navigator.appVersion.indexOf("Mac")!=-1) OSName="MacOS";
				if (navigator.appVersion.indexOf("X11")!=-1) OSName="UNIX";
				if (navigator.appVersion.indexOf("Linux")!=-1) OSName="Linux";

				return OSName;
            }

            function getUserAgent() {
            	var nVer = navigator.appVersion;
				var nAgt = navigator.userAgent;
				var browserName  = navigator.appName;
				var fullVersion  = ''+parseFloat(navigator.appVersion);
				var majorVersion = parseInt(navigator.appVersion,10);
				var nameOffset,verOffset,ix;

				// In Opera 15+, the true version is after "OPR/"
				if ((verOffset=nAgt.indexOf("OPR/"))!=-1) {
					 browserName = "Opera";
					 fullVersion = nAgt.substring(verOffset+4);
				}
				// In older Opera, the true version is after "Opera" or after "Version"
				else if ((verOffset=nAgt.indexOf("Opera"))!=-1) {
					 browserName = "Opera";
					 fullVersion = nAgt.substring(verOffset+6);
					 if ((verOffset=nAgt.indexOf("Version"))!=-1)
					   fullVersion = nAgt.substring(verOffset+8);
				}
				// In MSIE, the true version is after "MSIE" in userAgent
				else if ((verOffset=nAgt.indexOf("MSIE"))!=-1) {
					 browserName = "Microsoft Internet Explorer";
					 fullVersion = nAgt.substring(verOffset+5);
				}
				// In Chrome, the true version is after "Chrome"
				else if ((verOffset=nAgt.indexOf("Chrome"))!=-1) {
					 browserName = "Chrome";
					 fullVersion = nAgt.substring(verOffset+7);
				}
				// In Safari, the true version is after "Safari" or after "Version"
				else if ((verOffset=nAgt.indexOf("Safari"))!=-1) {
					 browserName = "Safari";
					 fullVersion = nAgt.substring(verOffset+7);
					 if ((verOffset=nAgt.indexOf("Version"))!=-1)
					   fullVersion = nAgt.substring(verOffset+8);
				}
				// In Firefox, the true version is after "Firefox"
				else if ((verOffset=nAgt.indexOf("Firefox"))!=-1) {
					 browserName = "Firefox";
					 fullVersion = nAgt.substring(verOffset+8);
				}
				// In most other browsers, "name/version" is at the end of userAgent
				else if ((nameOffset=nAgt.lastIndexOf(' ')+1) < (verOffset=nAgt.lastIndexOf('/'))) {
					 browserName = nAgt.substring(nameOffset,verOffset);
					 fullVersion = nAgt.substring(verOffset+1);
					 if (browserName.toLowerCase()==browserName.toUpperCase()) {
					  browserName = navigator.appName;
					 }
				}
				// trim the fullVersion string at semicolon/space if present
				if ((ix=fullVersion.indexOf(";"))!=-1)
				   	fullVersion=fullVersion.substring(0,ix);
				if ((ix=fullVersion.indexOf(" "))!=-1)
				   	fullVersion=fullVersion.substring(0,ix);

				majorVersion = parseInt(''+fullVersion,10);
				if (isNaN(majorVersion)) {
					 fullVersion  = ''+parseFloat(navigator.appVersion);
					 majorVersion = parseInt(navigator.appVersion,10);
				}

				var info = {name:browserName,version:fullVersion,major:majorVersion,appname:navigator.appName,useragent:navigator.userAgent};
				return info;


            }

            function locationChange()
            {
            	// safari does not read this url that starting with ios:
            	//alert(getUserAgent().name);
            	/*try {
					window.webkit.messageHandlers.iosHandler.postMessage("openBarCodeScanner");
				 } catch(err) {
						console.log('Can not reach native code');
				 }*/
            }

			$( document ).ready(function() {
    	 		/*try {
					//window.webkit.messageHandlers.iosLoginCredentials.postMessage("<?php echo $iosapptoken;?>");
				} catch(err) {
						console.log('Can not reach native code');
				}*/
			});

            function openScanner()
            {
                try {
					window.webkit.messageHandlers.iosHandler.postMessage("openBarCodeScanner:printQrCodeFromMobile");
				 } catch(err) {
						console.log('Can not reach native code');
				 }
            }

			function openScannerproductedit()
            {
                try {
					window.webkit.messageHandlers.iosHandler.postMessage("openBarCodeScanner:printQrCodeproductedit");
				 } catch(err) {
						console.log('Can not reach native code');
				 }
            }

			function openScannerupdatestock()
            {
                try {
					window.webkit.messageHandlers.iosHandler.postMessage("openBarCodeScanner:printQrCodeupdatestock");
				 } catch(err) {
						console.log('Can not reach native code');
				 }
            }

			function openScannersalereport()
            {
                try {
					window.webkit.messageHandlers.iosHandler.postMessage("openBarCodeScanner:printQrCodesalereport");
				 } catch(err) {
						console.log('Can not reach native code');
				 }
            }


			 function printQrCodeproductedit(value)
            {
				//document.getElementById('search').value = value
				$("input[name=filter_product_for_edit]").val(value);
				delay(function() {
					// search product when input product name in search field
					var filter_name = $('input[name=filter_product]').val();
					if (filter_name != '') {
						var url = 'index.php?route=module/pos/autocomplete_product&token=' + token;
						var data = {'filter_name':filter_name, 'customer_group_id':customer_group_id, 'filter_scopes':searchScopes};
						$.ajax({
							url: url,
							type: 'post',
							data: data,
							dataType: 'json',
							cacheCallback: function(json) {
								backendSaveProducts(json);
							},
							cachePreDone: function(cacheKey, callback) {
								backendGetProducts(data, callback);
							},
							success: function(json) {
								if (json && json.length == 1) {
									// a single product
									$('input[name=current_product_id]').val(json[0]['product_id']);
									$('input[name=current_product_name]').val(json[0]['name']);
									$('input[name=current_product_weight_price]').val(json[0]['weight_price']);
									$('input[name=current_product_weight_name]').val(json[0]['weight_name']);
									$('input[name=current_product_hasOption]').val(json[0]['hasOptions']);
									$('input[name=current_product_price]').val(json[0]['price']);
									$('input[name=current_product_tax]').val(json[0]['tax']);
									$('input[name=current_product_points]').val(json[0]['points']);
									$('input[name=current_product_image]').val(json[0]['image']);
								}
								populateBrowseTable(json, true);
							}
						});
					}
				}, 500);
            }
			 function printQrCodeupdatestock(value)
            {
				$("input[name=filter_product_update_stock]").val(value);
				delay(function() {
					// search product when input product name in search field
					var filter_name = $('input[name=filter_product]').val();
					if (filter_name != '') {
						var url = 'index.php?route=module/pos/autocomplete_product&token=' + token;
						var data = {'filter_name':filter_name, 'customer_group_id':customer_group_id, 'filter_scopes':searchScopes};
						$.ajax({
							url: url,
							type: 'post',
							data: data,
							dataType: 'json',
							cacheCallback: function(json) {
								backendSaveProducts(json);
							},
							cachePreDone: function(cacheKey, callback) {
								backendGetProducts(data, callback);
							},
							success: function(json) {
								if (json && json.length == 1) {
									// a single product
									$('input[name=current_product_id]').val(json[0]['product_id']);
									$('input[name=current_product_name]').val(json[0]['name']);
									$('input[name=current_product_weight_price]').val(json[0]['weight_price']);
									$('input[name=current_product_weight_name]').val(json[0]['weight_name']);
									$('input[name=current_product_hasOption]').val(json[0]['hasOptions']);
									$('input[name=current_product_price]').val(json[0]['price']);
									$('input[name=current_product_tax]').val(json[0]['tax']);
									$('input[name=current_product_points]').val(json[0]['points']);
									$('input[name=current_product_image]').val(json[0]['image']);
								}
								populateBrowseTable(json, true);
							}
						});
					}
				}, 500);
            }
			 function printQrCodesalereport(value)
            {
				$("input[name=filter_product_sales_report]").val(value);
				delay(function() {
					// search product when input product name in search field
					var filter_name = $('input[name=filter_product]').val();
					if (filter_name != '') {
						var url = 'index.php?route=module/pos/autocomplete_product&token=' + token;
						var data = {'filter_name':filter_name, 'customer_group_id':customer_group_id, 'filter_scopes':searchScopes};
						$.ajax({
							url: url,
							type: 'post',
							data: data,
							dataType: 'json',
							cacheCallback: function(json) {
								backendSaveProducts(json);
							},
							cachePreDone: function(cacheKey, callback) {
								backendGetProducts(data, callback);
							},
							success: function(json) {
								if (json && json.length == 1) {
									// a single product
									$('input[name=current_product_id]').val(json[0]['product_id']);
									$('input[name=current_product_name]').val(json[0]['name']);
									$('input[name=current_product_weight_price]').val(json[0]['weight_price']);
									$('input[name=current_product_weight_name]').val(json[0]['weight_name']);
									$('input[name=current_product_hasOption]').val(json[0]['hasOptions']);
									$('input[name=current_product_price]').val(json[0]['price']);
									$('input[name=current_product_tax]').val(json[0]['tax']);
									$('input[name=current_product_points]').val(json[0]['points']);
									$('input[name=current_product_image]').val(json[0]['image']);
								}
								populateBrowseTable(json, true);
							}
						});
					}
				}, 500);
            }

            setInterval(function(){ var ifPaymentAdded= $("#payment_total span").html(); if(ifPaymentAdded=='$0.00'){
            	$('.complete-btn').attr('disabled',true);
            }
      if($("#paymentApplied").html()==""){
      	$(".btn-before-after-payment").removeClass('colorApplied');
      	$(".btn-before-after-payment").attr('disabled',false);
      	$('.complete-btn').addClass('colorApplied');
		$(".pay-btn").css("background","#ff4141");
		$(".pay-btn span").css("border","");
		$(".pay-btn span").html("PAY");
      }else{
      	$(".btn-before-after-payment").attr('disabled',true);
      	$(".btn-before-after-payment").addClass('colorApplied');
      	$('.complete-btn').removeClass('colorApplied');
      	$(".pay-btn span").css("border","1px solid green");
      	$(".pay-btn").css("background","green");
      	    $('.complete-btn').attr('disabled',false);
      	$(".pay-btn span").html("PAID");
      }
        }, 1000);
        </script>
<style type="text/css">
	.colorApplied{
		background: #ccc !important;
	}
</style>
<?php echo $footer; ?>