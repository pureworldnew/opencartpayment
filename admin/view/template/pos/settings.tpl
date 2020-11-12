<?php echo $header; ?>
<!-- add for label print begin -->
<style id="label_print_style">
	.label-template-table { width: 100%; height: 100%; border: 0; padding:0; margin: auto;}
	.label-template-table tr td { background-color: white; border: 1px dotted #d1d1d1; padding: 0; margin: 0;}
	.toolbox { width: 100%;  border: 0; margin: auto; }
	.toolbox a.button { box-sizing: content-box; background-color: #d1d1d1; margin-right: 15px; font-size: 10px; padding: 5px; margin: 0; width: 45px; height: 14px; display: inline-block; text-decoration: none; -webkit-border-radius: 10px 10px 10px 10px; -moz-border-radius: 10px 10px 10px 10px; -khtml-border-radius: 10px 10px 10px 10px;	border-radius: 10px 10px 10px 10px; }
	.toolbox a.button-small { box-sizing: content-box; background-color: #d1d1d1; margin-right: 15px; font-size: 12px; padding: 5px; margin: 0; width: 30px; height: 16px; display: inline-block; text-decoration: none; -webkit-border-radius: 10px 10px 10px 10px; -moz-border-radius: 10px 10px 10px 10px; -khtml-border-radius: 10px 10px 10px 10px;	border-radius: 10px 10px 10px 10px; }
	.toolbox a.button span { color: black; font-size: 12px; }
	.draggable {
		margin: 3px 0 auto;
		/* needed when details container is displayed to be wrapped nicely */
		display: inline-block;
		text-align: center;
		width: 100px;
		height: 22px;
		line-height: 22px;
		color: #555;
		border: 1px solid #bebebe;
		background-color: #dbdbdb;
		/* round corners */
		border-radius: 4px;
		/* opacity */
		opacity: 0.9;
		filter: alpha(opacity=90);
		cursor: move;
		z-index: 999;
	}
	.clone {
		margin: 0px auto;
		/* needed when details container is displayed to be wrapped nicely */
		display: inline-block;
		text-align: center;
		width: 100px;
		height: 22px;
		line-height: 12px;
		color: #555;
		border: 1px dashed #bebebe;
		background-color: transparent;
		cursor: move;
		box-sizing: content-box;
	}
	.label-text-wrapper div {box-sizing: content-box; font-family: Arial;}
	.label-text-wrapper {display: table; vertical-align: middle; position: relative; margin: 0 auto; width: 100%; height: 100%; font-weight: normal; font-size: 12px;}
	.label-text-wrapper p {display: table-cell; vertical-align: middle; align: left;}
	.close-button {
		min-width: 10px;
		height: 12px;
		padding:0 2px;
		background: #a1a1a1;
		border-radius: 12px;
		border: 1px solid #fff;
		position: absolute;
		top: 0;
		right: 0;
		color: #FFF;
		font-size: 10px;
		text-align: center;
		line-height:12px;
		float:left;
		-webkit-box-shadow: 0px 0px 3px 0px rgba(0,0,0,0.3);
		-moz-box-shadow: 0px 0px 3px 0px rgba(0,0,0,0.3);
		box-shadow: 0px 0px 3px 0px rgba(0,0,0,0.3);
		box-sizing: content-box;
		cursor: pointer;
	}
	.components { width: 100%;  border: 0; margin: auto; }
	.components tbody td { padding:1px; border:0; }
	.list tbody tr:hover td { background-color: transparent;}
	.item-selected {
		border: 2px dashed #f15151;
	}
	.color-bar { box-sizing: content-box; width:45px; height: 12px; border: 1px solid #bebebe; };
</style>
<!-- add for label print end -->
<?php echo $column_left; ?>
<div id="content">
<div class="page-header">
	<div class="container-fluid">
		<div class="pull-right">
			<button type="submit" form="form-pos" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
			<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
		</div>
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
			<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit_pos_settings; ?></h3>
		</div>
		<div class="panel-body">
			<?php $payment_type_row_no = 0; ?>
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-pos" class="form-horizontal">
			<?php if (!$is_label_user) { ?>
			<div id="more_settings" style="border: 1px solid #DBDBDB; height: 25px; padding-left: 7px; padding-top: 3px; margin-bottom: 3px; ">
				<b><?php echo $entry_more_settings; ?></b>&nbsp;
				<?php foreach ($more_settings as $more_setting) { ?>
					<input type="checkbox" class="toggle_tab" name="enable_settings_<?php echo $more_setting; ?>" value="<?php echo ${'enable_settings_'.$more_setting}; ?>" <?php if(${'enable_settings_'.$more_setting}=='1') { ?>checked="checked"<?php } ?> />
					<?php echo ${'tab_settings_'.$more_setting}; ?>&nbsp;
				<?php }?>
			</div>
			<?php }?>
			<ul class="nav nav-tabs">
				<?php if (!$is_label_user) { ?>
				<li class="active"><a href="#tab_settings_payment_type" data-toggle="tab"><?php echo $tab_settings_payment_type; ?></a></li>
				<li><a href="#tab_settings_options" data-toggle="tab"><?php echo $tab_settings_options; ?></a></li>
				<!-- add for receipt begin -->
				<li><a href="#tab_settings_receipt" data-toggle="tab"><?php echo $tab_settings_receipt; ?></a></li>
				<!-- add for receipt end -->
				<li><a href="#tab_settings_order" data-toggle="tab"><?php echo $tab_settings_order; ?></a></li>
				<!-- add for Default customer begin -->
				<li><a href="#tab_settings_customer" data-toggle="tab"><?php echo $tab_settings_customer; ?></a></li>
				<!-- add for Default customer end -->
				<!-- add for Discount begin -->
				<li><a href="#tab_settings_discount" data-toggle="tab" id="tab_enable_settings_discount"><?php echo $tab_settings_discount; ?></a></li>
				<!-- add for Discount end -->
				<!-- add for Rounding begin -->
				<li><a href="#tab_settings_rounding" data-toggle="tab" id="tab_enable_settings_rounding"><?php echo $tab_settings_rounding; ?></a></li>
				<!-- add for Rounding end -->
				<!-- add for User as Affiliate begin -->
				<li><a href="#tab_settings_affiliate" data-toggle="tab" id="tab_enable_settings_affiliate"><?php echo $tab_settings_affiliate; ?></a></li>
				<!-- add for User as Affiliate end -->
				<!-- add for Quotation begin -->
				<li><a href="#tab_settings_quote" data-toggle="tab" id="tab_enable_settings_quote"><?php echo $tab_settings_quote; ?></a></li>
				<!-- add for Quotation end -->
				<!-- add for table management begin -->
				<li><a href="#tab_settings_table_management" data-toggle="tab" id="tab_enable_settings_table_management"><?php echo $tab_settings_table_management; ?></a></li>
				<!-- add for table management end -->
				<!-- add for stock / sales report begin -->
				<li><a href="#tab_settings_sales_report" data-toggle="tab" id="tab_enable_settings_sales_report"><?php echo $tab_settings_sales_report; ?></a></li>
				<!-- add for stock / sales report end -->
				<?php } else { ?>
				<li class="active"><a href="#tab_settings_label" data-toggle="tab" id="tab_enable_settings_label"><?php echo $tab_settings_label; ?></a></li>
				<?php } ?>
			</ul>

			<div class="tab-content">
				<?php if (!$is_label_user) { ?>
				<!-- add for Empty order control begin -->
				<input type="hidden" name="initial_status_id" value="<?php echo $initial_status_id; ?>" />
				<!-- add for Empty order control end -->
				<div class="tab-pane active settings_div" id="tab_settings_payment_type">
					<table id="payment_type_table" class="table table-striped table-bordered table-hover">
						<col width="70%" />
						<col width="20%" />
						<col width="10%" />
						<thead>
							<tr><td colspan="3" class="text-left" style="background-color: #E7EFEF;"><?php echo $text_payment_type_setting; ?></td></tr>
							<tr>
								<td class="text-left"><?php echo $text_order_payment_type; ?></td>
								<td class="text-left"><?php echo $text_order_payment_eanble; ?></td>
								<td class="text-center"><?php echo $text_action; ?></td>
							</tr>
						</thead>
						<tbody id="payment_type_list">
							<tr class='filter' id="payment_type_add">
								<td class="text-left"><input type="text" name="payment_type" id="payment_type" class="form-control" value="" onkeypress="return addPaymentOnEnter(event)" /></td>
								<td class="text-left"><input type="checkbox" name="payment_type_enable" class="form-control" value="0" /></td>
								<td class="text-center"><a id="button_add_payment_type" onclick="addPaymentType();" class="btn btn-primary"><i class="fa fa-plus-circle fa-lg"></i> <?php echo $button_add_type; ?></a></td>
							</tr>
							<?php
								if (isset($payment_types)) {
								foreach ($payment_types as $payment_type=>$payment_name) {
							?>
							<tr id="<?php echo 'payment_type_'.$payment_type_row_no; ?>">
								<td class="text-left"><?php echo $payment_name; ?></td>
								<td class="text-left"><input type="checkbox" name="payment_type_enables[<?php echo $payment_type; ?>]" class="form-control" value="<?php echo (empty($payment_type_enables[$payment_type])) ? 0 : 1; ?>" <?php if(!empty($payment_type_enables[$payment_type])) {?>checked="checked"<?php }?> /></td>
								<td class="text-center">
									<?php if (!$payment_type || $payment_type != 'cash' && $payment_type != 'credit_card' && $payment_type != 'gift_voucher' && $payment_type != 'reward_points' && $payment_type != 'purchase_order') { ?>
									<a onclick="deletePaymentType('<?php echo 'payment_type_'.$payment_type_row_no; ?>');" class="btn btn-danger btn-sm"><i class="fa fa-trash-o fa-lg"></i> <?php echo $button_remove; ?></a>
									<?php } ?>
									<input type="hidden" name="POS_payment_types[<?php echo $payment_type; ?>]" value="<?php echo $payment_name; ?>"/>
								</td>
							</tr>
							<?php $payment_type_row_no ++; }} ?>
						</tbody>
					</table>
					<table id="payment_post_action" class="table table-bordered table-hover">
						<thead>
							<tr><td class="text-left" style="background-color: #E7EFEF;"><?php echo $text_payment_post_action_setting; ?></td></tr>
						</thead>
						<tbody>
							<tr><td class="text-left"><?php echo $entry_payment_post_action; ?></td></tr>
							<tr>
								<td class="text-left">
									<div>
										<table class="table">
										<?php $num_per_row = 5; $total_row = (count($order_statuses) % $num_per_row == 0) ? count($order_statuses) / $num_per_row : count($order_statuses) / $num_per_row + 1; ?>
										<?php for ($row = 0; $row < $total_row; $row++) { ?>
											<tr>
												<?php for ($col = 0; $col < $num_per_row; $col++) { ?>
												<?php 	if ($row*$num_per_row+$col < count($order_statuses)) { ?>
												<?php 		$order_status = $order_statuses[$row*$num_per_row+$col]; ?>
												<?php 		if (array_key_exists($order_status['order_status_id'], $order_payment_post_status)) { ?>
												<td class="text-left" style="border: 0;"><input type="checkbox" name="order_payment_post_status[<?php echo $order_status['order_status_id']; ?>]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />&nbsp;<?php echo $order_status['name']; ?></td>
												<?php 		} else { ?>
												<td class="text-left" style="border: 0;"><input type="checkbox" name="order_payment_post_status[<?php echo $order_status['order_status_id']; ?>]" value="<?php echo $order_status['order_status_id']; ?>" />&nbsp;<?php echo $order_status['name']; ?></td>
												<?php 		} ?>
												<?php 	} ?>
												<?php } ?>
											</tr>
										<?php } ?>
										</table>
									</div>
									<a class="btn btn-info btn-xs" onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a>&nbsp;<a class="btn btn-info btn-xs" onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
								</td>
							</tr>
						</tbody>
					</table>
					<!-- add for customer loyalty card begin -->
					<table class="table table-striped table-bordered table-hover">
					  <thead>
						<tr>
							<td colspan="3" class="text-left" style="background-color: #E7EFEF;"><?php echo $text_reward_point_setting; ?></td>
						</tr>
					  </thead>
					  <tbody id="reward_points">
						<tr>
							<td class="text-center" style="border-right: 0; width: 5px;"><input type="radio" name="reward_points_usage" value="1" class="form-control" <?php if (!empty($reward_points_usage) && $reward_points_usage == '1') {?> checked="checked"<?php }?> /></td>
							<td class="text-left" colspan="2"><?php echo $text_use_opencart_reward_points; ?></td>
						</tr>
						<tr>
							<td class="text-center" style="border-right: 0; width: 5px;"><input type="radio" name="reward_points_usage" value="2" class="form-control" <?php if (!empty($reward_points_usage) && $reward_points_usage == '2') {?> checked="checked"<?php }?> /></td>
							<td class="text-left" colspan="2"><?php echo $text_use_cash_converting; ?></td>
						</tr>
						<tr>
							<td style="border-right: 0; width: 5px;"></td>
							<td class="text-left" colspan="2"><?php echo '1 ' . $currency_symbol . ' = '; ?><input type="text" name="reward_points_value" value="<?php echo empty($reward_points_value) ? '' : $reward_points_value; ?>" style="width: 50px;"/><?php echo ' ' . $text_reward_points; ?></td>
						</tr>
						<tr>
							<td colspan="3" class="text-left" style="background-color: #E7EFEF;"><?php echo $text_reward_point_initial; ?><a class="btn btn-danger" style="margin-left:20px;" onclick="setProductRewardPoints(this);"><?php echo $button_reward_point_initial_set; ?></a></td>
						</tr>
						<tr>
							<td style="border-right: 0; width: 5px;"></td>
							<td class="text-left"><?php echo $text_point_needed; ?></td>
							<td class="text-left"><?php echo $text_reward_point_initial_price . ' * '; ?><input type="text" name="points_ratio" value="<?php echo empty($points_ratio) ? '' : $points_ratio; ?>" style="width: 50px;"/><?php echo ' = ' . $text_reward_points; ?></td>
						</tr>
						<?php if (!empty($c_groups)) { foreach ($c_groups as $customer_group) { ?>
						<tr>
							<td style="border-right: 0; width: 5px;"></td>
							<td class="text-left"><?php echo $text_reward_point_initial_customer_group . ' <b>' . $customer_group['name'] . '</b>: '; ?></td>
							<td class="text-left"><?php echo $text_reward_point_initial_price . ' * '; ?><input type="text" name="reward_points_ratio[<?php echo $customer_group['customer_group_id']; ?>]" value="<?php echo empty($reward_points_ratio[$customer_group['customer_group_id']]) ? '' : $reward_points_ratio[$customer_group['customer_group_id']]; ?>" style="width: 50px;"/><?php echo ' = ' . $text_reward_points; ?></td>
						</tr>
						<?php }}?>
					  </tbody>
					</table>
					<!-- add for customer loyalty card end -->
					<!-- add for cash type begin -->
					<table class="table table-striped table-bordered table-hover" style="table-layout: fixed;">
						  <col width="10%" />
						  <col width="50%" />
						  <col width="10%" />
						  <col width="20%" />
						  <col width="10%" />
						<thead>
							<tr>
								<td colspan="5" class="text-left" style="background-color: #E7EFEF;"><?php echo $text_cash_type_setting; ?></td>
							</tr>
							<tr>
								<td class="text-left"><?php echo $column_cash_type; ?></td>
								<td class="text-left"><?php echo $column_cash_image; ?></td>
								<td class="text-right"><?php echo $column_cash_value; ?></td>
								<td class="text-left"><?php echo $column_cash_display; ?></td>
								<td class="text-center"><?php echo $column_cash_action; ?></td>
							</tr>
						</thead>
						<tbody id="cash_type_list">
							<tr class='filter' id="cash_type_tr">
								<td class="text-left">
									<select name="cash_type" class="form-control">
										<option value="<?php echo $text_cash_type_note; ?>"><?php echo $text_cash_type_note; ?></option>
										<option value="<?php echo $text_cash_type_coin; ?>"><?php echo $text_cash_type_coin; ?></option>
									</select>
								</td>
								<td class="text-left">
									<div class="image" id="cash_image">
										<img src="" style="max-width: 240px; max-height: 120px; width: auto; height: auto;" /><br />
										<input type="hidden" name="cash_image_path" value="" id="cash_image_path" />
										<a class="btn btn-info btn-xs" onclick="image_upload('cash_image_path', 'cash_image', true);"><i class="fa fa-folder-open-o fa-lg"></i> <?php echo $text_print_browse; ?></a>
									</div>
								</td>
								<td class="text-right"><input type="text" class="form-control" name="cash_value" value="" style="text-align: right;" /></td>
								<td class="text-left"><input type="text" class="form-control" name="cash_display" value="" style="text-align: left;" /></td>
								<td class="text-center"><a id="button_add_cash_type" class="btn btn-primary" onclick="addCashType();"><i class="fa fa-plus-circle fa-lg"></a></td>
							</tr>
							<?php
								$cash_type_row = 0;
								if (!empty($cash_types)) {
									foreach ($cash_types as $cash_type) { ?>
							<tr id="cash_type-<?php echo $cash_type_row; ?>">
								<td class="text-left">
									<?php echo $cash_type['type']; ?>
									<input type="hidden" name="cash_types[<?php echo $cash_type_row; ?>][type]" value="<?php echo $cash_type['type']; ?>" />
								</td>
								<td class="text-left">
									<img src="<?php echo $cash_type['image']; ?>" style="max-width: 240px; max-height: 120px; width: auto; height: auto;" />
									<input type="hidden" name="cash_types[<?php echo $cash_type_row; ?>][image]" value="<?php echo $cash_type['image']; ?>" />
								</td>
								<td class="text-right">
									<?php echo $cash_type['value']; ?>
									<input type="hidden" name="cash_types[<?php echo $cash_type_row; ?>][value]" value="<?php echo $cash_type['value']; ?>" />
								</td>
								<td class="text-left" id="cash_display_<?php echo $cash_type['value']; ?>">
									<span><?php echo $cash_type['display']; ?></span>
									<input type="hidden" name="cash_types[<?php echo $cash_type_row; ?>][display]" value="<?php echo $cash_type['display']; ?>" />
								</td>
								<td class="text-center"><a class="btn btn-danger btn-sm" onclick="deleteCashType('cash_type-<?php echo $cash_type_row; ?>');"><i class="fa fa-minus-circle fa-lg"></a></td>
							</tr>
							<?php $cash_type_row++; }} ?>
						</tbody>
					</table>
					<!-- add for cash type end -->
				</div>

				<div class="tab-pane settings_div" id="tab_settings_options">
					<table id="page_display" class="table table-striped table-bordered table-hover">
						<thead>
							<tr><td colspan="2" class="text-left" style="background-color: #E7EFEF;"><?php echo $text_display_setting; ?></td></tr>
						</thead>
						<tbody>
							<tr><td colspan="2" class="text-left">
								<input type="checkbox" name="display_once_login" value="<?php echo $display_once_login; ?>" <?php if($display_once_login=='1') { ?>checked="checked"<?php } ?> />&nbsp;
								<?php echo $text_display_once_login; ?>
							</td></tr>
						</tbody>
					</table>

					<!-- add for offline support begin -->
					<table id="enable_offline_mode" class="table table-striped table-bordered table-hover">
						<thead>
							<tr><td class="text-left" style="background-color: #E7EFEF;"><?php echo $text_offline_mode_setting; ?></td></tr>
						</thead>
						<tbody>
							<tr><td class="text-left">
								<input type="checkbox" name="enable_offline_mode" value="<?php echo $enable_offline_mode; ?>" <?php if($enable_offline_mode=='1') { ?>checked="checked"<?php } ?> />&nbsp;
								<?php echo $text_offline_mode_enable; ?>
							</td></tr>
						</tbody>
					</table>
					<!-- add for offline mode end -->
					<!-- add for auto payment begin -->
					<table id="enable_auto_payment" class="table table-bordered">
					  <thead>
						<tr>
							<td class="text-left" style="background-color: #E7EFEF;"><?php echo $text_auto_payment_setting; ?></td>
						</tr>
					  </thead>
					  <tbody>
						<tr><td class="text-left">
							<input type="checkbox" name="enable_auto_payment" value="<?php echo $enable_auto_payment; ?>" <?php if($enable_auto_payment=='1') { ?>checked="checked"<?php } ?> />&nbsp;
							<?php echo $text_auto_payment_enable; ?>&nbsp;
							<select name="auto_payment_type">
								<?php if (!empty($payment_types)) { foreach ($payment_types as $payment_type=>$payment_name) {?>
								<option value="<?php echo $payment_type; ?>" <?php if ($auto_payment_type == $payment_type ) {?>selected=selected<?php }?>><?php echo $payment_name; ?></option>
								<?php }}?>
							</select>
						</td></tr>
					  </tbody>
					</table>
					<!-- add for auto payment end -->
					<!-- add for product low stock begin -->
					<table id="enable_product_low_stock" class="table table-bordered" style="table-layout: fixed;">
					  <col width="15%" />
					  <col width="15%" />
					  <col width="20%" />
					  <col width="30%" />
					  <col width="20%" />
					  <thead>
						<tr>
							<td class="text-left" colspan="5" style="background-color: #E7EFEF;"><?php echo $text_product_low_stock_setting; ?></td>
						</tr>
					  </thead>
					  <tbody>
						<!--
						<tr><td class="text-left" colspan="5">
							<input type="checkbox" name="enable_product_low_stock" value="<?php echo $enable_product_low_stock; ?>" <?php if($enable_product_low_stock=='1') { ?>checked="checked"<?php } ?> />&nbsp;
							<?php echo $text_product_low_stock_enable; ?>
						</td></tr>
						<tr>
							<td class="text-left" colspan="5" style="background-color: #E7EFEF;"><?php echo $text_set_product_low_stock; ?></td>
						</tr>
						-->
						<tr>
							<td class="text-left"><?php echo $entry_set_product_low_stock; ?></td>
							<td class="text-left"><input type="text" name="product_low_stock" value="" class="form-control" /></td>
								<td class="text-left" valign="center">
									<div>
										<?php foreach ($user_groups as $user_group) { ?>
											<div>
												<?php if (in_array($user_group['user_group_id'], $excluded_groups)) { ?>
												<input type="checkbox" name="excluded_groups[]" value="<?php echo $user_group['user_group_id']; ?>" checked="checked" />
												<?php } else { ?>
												<input type="checkbox" name="excluded_groups[]" value="<?php echo $user_group['user_group_id']; ?>" />
												<?php } ?>
												&nbsp;<?php echo $user_group['name']; ?>
											</div>
										<?php } ?>
									</div>
									<a class="btn btn-info btn-xs" onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a>&nbsp;<a class="btn btn-info btn-xs" onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
								</td>
							<td class="text-left">
							  <div>
								<?php foreach ($categories as $category) { ?>
								<div>
								  <input type="checkbox" name="category_id[]" value="<?php echo $category['category_id']; ?>" />&nbsp;<?php echo $category['name']; ?>
								</div>
								<?php } ?>
							  </div>
							  <a class="btn btn-info btn-xs" onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a>&nbsp;<a class="btn btn-info btn-xs" onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
							</td>
							<td class="text-center"><a class="btn btn-primary" onclick="setProductLowStock(this);"><?php echo $button_set_product_low_stock; ?></a></td>
						</tr>
					  </tbody>
					</table>
					<!-- add for product low stock end -->

					<!-- add for Openbay begin -->
					<table id="openbay" class="table table-striped table-bordered table-hover">
						<thead>
							<tr><td class="text-left" style="background-color: #E7EFEF;"><?php echo $text_openbay_setting; ?></td></tr>
						</thead>
						<tbody>
							<tr><td class="text-left">
								<input type="checkbox" name="enable_openbay" value="<?php echo $enable_openbay; ?>" <?php if($enable_openbay=='1') { ?>checked="checked"<?php } ?> />&nbsp;
								<?php echo $text_openbay_enable; ?>
							</td></tr>
						</tbody>
					</table>
					<!-- add for Openbay end -->
					<!-- add for till control begin -->
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr><td colspan="3" class="text-left" style="background-color: #E7EFEF;"><?php echo $text_till_control_setting; ?></td></tr>
						</thead>
						<tbody>
							<tr>
								<td colspan="3" class="text-left">
									<input type="checkbox" name="enable_till_control" value="<?php echo $enable_till_control; ?>" <?php if($enable_till_control=='1') { ?>checked="checked"<?php } ?> />&nbsp;
									<?php echo $text_till_control_enable; ?>
								</td>
							</tr>
							<tr>
								<td class="text-left"><?php echo $entry_till_control_key; ?></td>
								<td class="text-left"><input type="text" class="form-control" name="till_control_key" value="<?php echo empty($till_control_key) ? '' : $till_control_key; ?>"/></td>
								<td class="text-right" style="width: 86px;"><a class="btn btn-success" onclick="testTillControl();"><i class="fa fa-wrench fa-lg"></i> <?php echo $button_test; ?></a></td>
							</tr>
							<tr>
								<td class="text-left" colspan="3">
								<input type="checkbox" name="enable_till_full_payment" value="<?php echo $enable_till_full_payment; ?>" <?php if($enable_till_full_payment=='1') { ?>checked="checked"<?php } ?> />&nbsp;
								<?php echo $text_till_full_payment_enable; ?>
								</td>
							</tr>
						</tbody>
					</table>
					<!-- add for till control end -->
					<table id="enable_non_predefined_sn" class="table table-bordered table-hover">
						<thead>
							<tr><td class="text-left" style="background-color: #E7EFEF;"><?php echo $text_product_sn_setting; ?></td></tr>
						</thead>
						<tbody>
							<tr><td class="text-left">
								<input type="checkbox" name="enable_non_predefined_sn" value="<?php echo $enable_non_predefined_sn; ?>" <?php if($enable_non_predefined_sn=='1') { ?>checked="checked"<?php } ?> />&nbsp;
								<?php echo $text_non_predefined_sn_enable; ?>
							</td></tr>
						</tbody>
					</table>		
					<table id="pos_ui_control" class="table table-striped table-bordered table-hover">
						<thead>
							<tr><td class="text-left" style="background-color: #E7EFEF;"><?php echo $text_pos_ui_setting; ?></td></tr>
						</thead>
						<tbody>
							<tr><td class="text-left">
								<input type="checkbox" name="hide_pos_shipping" value="<?php echo $hide_pos_shipping; ?>" <?php if($hide_pos_shipping=='1') { ?>checked="checked"<?php } ?> />&nbsp;
								<?php echo $text_hide_pos_shipping; ?>
							</td></tr>
						</tbody>
					</table>
					<table id="online_order_printing" class="table table-striped table-bordered table-hover">
						<thead>
							<tr><td class="text-left" style="background-color: #E7EFEF;"><?php echo $text_online_order_print_setting; ?></td></tr>
						</thead>
						<tbody>
							<tr><td class="text-left">
								<input type="checkbox" name="enable_online_order_print" value="<?php echo $enable_online_order_print; ?>" <?php if($enable_online_order_print=='1') { ?>checked="checked"<?php } ?> />&nbsp;
								<?php echo $text_print_online_order_enable; ?>
							</td></tr>
						</tbody>
					</table>
				</div>

				<div class="tab-pane settings_div" id="tab_settings_receipt">
					<table id="invoice_receipt" class="table table-striped table-bordered table-hover">
						<thead>
							<tr><td colspan="2" class="text-left" style="background-color: #E7EFEF;"><?php echo $text_print_type_setting; ?></td></tr>
						</thead>
						<tbody>
							<tr>
								<td class="text-center" size="1"><input type="radio" name="config_print_type" value="receipt" <?php if ($config_print_type == 'receipt') { ?> checked="checked" <?php } ?>/>&nbsp;<?php echo $text_print_type_receipt; ?></td>
								<td class="text-center" size="1"><input type="radio" name="config_print_type" value="invoice" <?php if ($config_print_type == 'invoice') { ?> checked="checked" <?php } ?>/>&nbsp;<?php echo $text_print_type_invoice; ?></td>
							</tr>
							<tr>
								<td class="text-left" colspan="2"><input type="checkbox" name="barcode_for_product" value="<?php echo $barcode_for_product; ?>" <?php if ($barcode_for_product == '1') { ?> checked="checked" <?php } ?>/>&nbsp;<?php echo $text_barcode_for_product; ?></td>
							</tr>
						</tbody>
					</table>
					<!-- cc site admin when email receipt begin -->
					<table id="email_receipt_cc" class="table table-bordered">
						<thead>
							<tr><td colspan="3" class="text-left" style="background-color: #E7EFEF;"><?php echo $text_email_receipt_cc_setting; ?></td></tr>
						</thead>
						<tbody>
							<tr>
								<td class="text-center" size="1"><input type="checkbox" name="enable_email_receipt_cc" value="<?php echo $enable_email_receipt_cc; ?>" <?php if($enable_email_receipt_cc=='1') { ?>checked="checked"<?php } ?> /></td>
								<td class="text-left"><?php echo $text_email_receipt_cc; ?></td>
								<td class="text-left"><input type="text" class="form-control email" name="email_receipt_cc_account" value="<?php echo $email_receipt_cc_account; ?>" /></td>
							</tr>
						</tbody>
					</table>
					<!-- cc site admin when email receipt end -->
					<!-- add for Print begin -->
					<table id="pos_print" class="table table-striped table-bordered table-hover">
						<thead>
							<tr><td class="text-left" colspan="2" style="background-color: #E7EFEF;"><?php echo $text_print_setting; ?></td></tr>
						</thead>
						<tbody>
							<tr>
								<td class="text-left"><?php echo $entry_print_log; ?></td>
								<td class="text-left">
									<div id="pos_logo">
										<img src="<?php echo $p_logo; ?>" alt="" /><br />
										<input type="hidden" name="p_logo" value="<?php echo $p_logo; ?>" id="logo"/>
										<a class="btn btn-info btn-xs" onclick="image_upload('logo', 'pos_logo', true);"><i class="fa fa-folder-open-o fa-lg"></i> <?php echo $text_print_browse; ?></a>
									</div>
								</td>
							</tr>
							<tr>
								<td class="text-left"><?php echo $entry_print_width; ?></td>
								<td class="text-left"><input type="text" name="p_width" value="<?php echo $p_width; ?>" class="form-control"/></td>
							</tr>
							<tr>
								<td class="text-left" colspan="2">
									<input type="checkbox" name="p_complete" value="<?php echo $p_complete; ?>" <?php if($p_complete=='1') { ?>checked="checked"<?php } ?> />&nbsp;
									<?php echo $text_p_complete; ?>
								</td>
							</tr>
							<tr>
								<td class="text-left" colspan="2">
									<input type="checkbox" name="p_payment" value="<?php echo $p_payment; ?>" <?php if($p_payment=='1') { ?>checked="checked"<?php } ?> />&nbsp;
									<?php echo $text_p_payment; ?>
								</td>
							</tr>
							<tr>
								<td class="text-left"><?php echo $entry_term_n_cond; ?></td>
								<td class="text-left"><textarea name="p_term_n_cond" class="form-control" row="3" class="form-control"><?php echo $p_term_n_cond; ?></textarea></td>
							</tr>
						</tbody>
					</table>
					<!-- add for Print end -->
				</div>
	
				<div class="tab-pane settings_div" id="tab_settings_order">
					<!-- add for Hiding Delete begin -->
					<table id="hide_delete" class="table table-striped table-bordered table-hover">
						<thead>
							<tr><td colspan="2" class="text-left" style="background-color: #E7EFEF;"><?php echo $text_hide_delete_setting; ?></td></tr>
						</thead>
						<tbody>
							<tr><td colspan="2" class="text-left">
								<input type="checkbox" name="enable_hide_delete" value="<?php echo $enable_hide_delete; ?>" <?php if($enable_hide_delete=='1') { ?>checked="checked"<?php } ?> />&nbsp;
								<?php echo $text_hide_delete_enable; ?>
							</td></tr>
							<tr>
								<td class="text-left" valign="center"><?php echo $column_exclude; ?></td>
								<td class="text-left" valign="center">
									<div>
										<?php foreach ($user_groups as $user_group) { ?>
											<div>
												<?php if (in_array($user_group['user_group_id'], $delete_excluded_groups)) { ?>
												<input type="checkbox" name="delete_excluded_groups[]" value="<?php echo $user_group['user_group_id']; ?>" checked="checked" />
												<?php echo $user_group['name']; ?>
												<?php } else { ?>
												<input type="checkbox" name="delete_excluded_groups[]" value="<?php echo $user_group['user_group_id']; ?>" />
												<?php echo $user_group['name']; ?>
												<?php } ?>
											</div>
										<?php } ?>
									</div>
									<a class="btn btn-info btn-xs" onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a>&nbsp;<a class="btn btn-info btn-xs" onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
								</td>
							</tr>
						</tbody>
					</table>
					<!-- add for Hiding Delete end -->
					<!-- add for Hiding Order Status begin -->
					<table id="hide_order_status" class="table table-striped table-bordered table-hover">
						<thead>
							<tr><td class="text-left" style="background-color: #E7EFEF;"><?php echo $text_hide_order_status_setting; ?></td></tr>
						</thead>
						<tbody>
							<tr><td class="text-left"><?php echo $text_hide_order_status_message; ?></td></tr>
							<tr>
								<td class="text-left">
									<div>
										<table class="table">
										<?php $num_per_row = 5; $total_row = (count($order_statuses) % $num_per_row == 0) ? count($order_statuses) / $num_per_row : count($order_statuses) / $num_per_row + 1; ?>
										<?php for ($row = 0; $row < $total_row; $row++) { ?>
											<tr>
												<?php for ($col = 0; $col < $num_per_row; $col++) { ?>
												<?php 	if ($row*$num_per_row+$col < count($order_statuses)) { ?>
												<?php 		$order_status = $order_statuses[$row*$num_per_row+$col]; ?>
												<?php 		if (array_key_exists($order_status['order_status_id'], $order_hiding_status)) { ?>
												<td class="text-left" style="border: 0;"><input type="checkbox" name="order_hiding_status[<?php echo $order_status['order_status_id']; ?>]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />&nbsp;<?php echo $order_status['name']; ?></td>
												<?php 		} else { ?>
												<td class="text-left" style="border: 0;"><input type="checkbox" name="order_hiding_status[<?php echo $order_status['order_status_id']; ?>]" value="<?php echo $order_status['order_status_id']; ?>" />&nbsp;<?php echo $order_status['name']; ?></td>
												<?php 		} ?>
												<?php 	} ?>
												<?php } ?>
											</tr>
										<?php } ?>
										</table>
									</div>
									<a class="btn btn-info btn-xs" onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a>&nbsp;<a class="btn btn-info btn-xs" onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
								</td>
							</tr>
						</tbody>
					</table>
					<!-- add for Hiding Order Status end -->
					<!-- add for Locking Order Status begin -->
					<table id="lock_order_status" class="table table-striped table-bordered table-hover">
						<thead>
							<tr><td class="text-left" colspan="2" style="background-color: #E7EFEF;"><?php echo $text_lock_order_status_setting; ?></td></tr>
						</thead>
						<tbody>
							<tr>
								<td class="text-left"><?php echo $entry_lock_group; ?></td>
								<td class="text-left">
									<div>
										<?php foreach ($user_groups as $user_group) { ?>
											<div>
												<?php if (in_array($user_group['user_group_id'], $order_lock_groups)) { ?>
												<input type="checkbox" name="order_lock_groups[]" value="<?php echo $user_group['user_group_id']; ?>" checked="checked" />
												<?php echo $user_group['name']; ?>
												<?php } else { ?>
												<input type="checkbox" name="order_lock_groups[]" value="<?php echo $user_group['user_group_id']; ?>" />
												<?php echo $user_group['name']; ?>
												<?php } ?>
											</div>
										<?php } ?>
									</div>
									<a class="btn btn-info btn-xs" onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a>&nbsp;<a class="btn btn-info btn-xs" onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
								</td>
							</tr>
							<tr><td class="text-left" colspan="2"><?php echo $text_lock_order_status_message; ?></td></tr>
							<tr>
								<td class="text-left" colspan="2">
									<div>
										<table class="table">
										<?php $num_per_row = 5; $total_row = (count($order_statuses) % $num_per_row == 0) ? count($order_statuses) / $num_per_row : count($order_statuses) / $num_per_row + 1; ?>
										<?php for ($row = 0; $row < $total_row; $row++) { ?>
											<tr>
												<?php for ($col = 0; $col < $num_per_row; $col++) { ?>
												<?php 	if ($row*$num_per_row+$col < count($order_statuses)) { ?>
												<?php 		$order_status = $order_statuses[$row*$num_per_row+$col]; ?>
												<?php 		if (array_key_exists($order_status['order_status_id'], $order_locking_status)) { ?>
												<td class="text-left" style="border: 0;"><input type="checkbox" name="order_locking_status[<?php echo $order_status['order_status_id']; ?>]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />&nbsp;<?php echo $order_status['name']; ?></td>
												<?php 		} else { ?>
												<td class="text-left" style="border: 0;"><input type="checkbox" name="order_locking_status[<?php echo $order_status['order_status_id']; ?>]" value="<?php echo $order_status['order_status_id']; ?>" />&nbsp;<?php echo $order_status['name']; ?></td>
												<?php 		} ?>
												<?php 	} ?>
												<?php } ?>
											</tr>
										<?php } ?>
										</table>
									</div>
									<a class="btn btn-info btn-xs" onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a>&nbsp;<a class="btn btn-info btn-xs" onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
								</td>
							</tr>
						</tbody>
					</table>
					<!-- add for Hiding Order Status end -->
					<!-- add for UPC/SKU/MPN begin -->
					<table id="c_default" class="table table-striped table-bordered table-hover">
						<thead>
							<tr><td colspan="3" class="text-left" style="background-color: #E7EFEF;"><?php echo $text_scan_type_setting; ?></td></tr>
						</thead>
						<tbody>
							<tr>
								<td class="text-center" size="1"><input type="radio" name="config_scan_type" value="upc" <?php if ($config_scan_type == 'upc') { ?> checked="checked" <?php } ?>/>&nbsp;<?php echo $text_scan_type_upc; ?></td>
								<td class="text-center" size="1"><input type="radio" name="config_scan_type" value="sku" <?php if ($config_scan_type == 'sku') { ?> checked="checked" <?php } ?>/>&nbsp;<?php echo $text_scan_type_sku; ?></td>
								<td class="text-center" size="1"><input type="radio" name="config_scan_type" value="mpn" <?php if ($config_scan_type == 'mpn') { ?> checked="checked" <?php } ?>/>&nbsp;<?php echo $text_scan_type_mpn; ?></td>
							</tr>
						</tbody>
					</table>
					<!-- add for UPC/SKU/MPN end -->
					<!-- add for order Status begin -->
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr><td colspan="2" class="text-left" style="background-color: #E7EFEF;"><?php echo $text_order_status_setting; ?></td></tr>
						</thead>
						<tbody>
							<tr>
								<td class="text-left"><?php echo $entry_complete_status; ?></td>
								<td class="text-left">
									<select class="form-control" name="complete_status_id">
										<?php foreach ($order_statuses as $order_status) { ?>
											<?php if (!empty($complete_status_id) && $order_status['order_status_id'] == $complete_status_id) { ?>
											<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td class="text-left"><?php echo $entry_parking_status; ?></td>
								<td class="text-left">
									<select class="form-control" name="parking_status_id">
										<?php foreach ($order_statuses as $order_status) { ?>
											<?php if (!empty($parking_status_id) && $order_status['order_status_id'] == $parking_status_id) { ?>
											<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td class="text-left"><?php echo $entry_void_status; ?></td>
								<td class="text-left">
									<select class="form-control" name="void_status_id">
										<?php foreach ($order_statuses as $order_status) { ?>
											<?php if (!empty($void_status_id) && $order_status['order_status_id'] == $void_status_id) { ?>
											<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td class="text-left"><?php echo $entry_return_complete_status; ?></td>
								<td class="text-left">
									<select class="form-control" name="return_complete_status_id">
										<?php foreach ($return_statuses as $return_status) { ?>
											<?php if (!empty($return_complete_status_id) && $return_status['return_status_id'] == $return_complete_status_id) { ?>
											<option value="<?php echo $return_status['return_status_id']; ?>" selected="selected"><?php echo $return_status['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $return_status['return_status_id']; ?>"><?php echo $return_status['name']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td class="text-left"><?php echo $entry_quote_complete_status; ?></td>
								<td class="text-left">
									<select class="form-control" name="quote_complete_status_id">
										<?php foreach ($quote_statuses as $quote_status) { ?>
											<?php if (!empty($quote_complete_status_id) && $quote_status['quote_status_id'] == $quote_complete_status_id) { ?>
											<option value="<?php echo $quote_status['quote_status_id']; ?>" selected="selected"><?php echo $quote_status['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $quote_status['quote_status_id']; ?>"><?php echo $quote_status['name']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</td>
							</tr>
						</tbody>
					</table>
					<!-- add for order Status end -->
					<!-- add for Status Change Notification begin -->
					<table id="notification" class="table table-striped table-bordered table-hover">
						<thead>
							<tr><td class="text-left" style="background-color: #E7EFEF;"><?php echo $text_notification_setting; ?></td></tr>
						</thead>
						<tbody>
							<tr><td class="text-left">
								<input type="checkbox" name="enable_notification" value="<?php echo $enable_notification; ?>" <?php if($enable_notification=='1') { ?>checked="checked"<?php } ?> />&nbsp;
								<?php echo $text_notification_enable; ?>
							</td></tr>
						</tbody>
					</table>
					<!-- add for Status Change Notification begin -->
				</div>
	
				<div class="tab-pane settings_div" id="tab_settings_customer">
					<!-- add for Default Customer begin -->
					<table id="c_default" class="table table-striped table-bordered table-hover">
						<thead>
							<tr><td colspan="6" class="text-left" style="background-color: #E7EFEF;"><?php echo $text_customer_setting; ?></td></tr>
						</thead>
						<tbody>
							<tr>
								<td class="text-center" size="1"><input type="radio" name="c_type" value="1" <?php if ($c_type == 1) { ?> checked="checked" <?php } ?>/></td>
								<td class="text-left"><?php echo $text_customer_system; ?></td>
								<td class="text-center" size="1"><input type="radio" name="c_type" value="2" <?php if ($c_type == 2) { ?> checked="checked" <?php } ?>/></td>
								<td class="text-left"><?php echo $text_customer_custom; ?></td>
								<td class="text-center" size="1"><input type="radio" name="c_type" value="3" <?php if ($c_type == 3) { ?> checked="checked" <?php } ?>/></td>
								<td class="text-left"><?php echo $text_customer_existing; ?></td>
							</tr>
							<tr>
								<td class="text-left" colspan="6" style="color: #FF802B; font-size: 12px; font-weight: bold; "><?php echo $text_customer_info; ?>
									<input type="hidden" name="c_id" value="<?php echo $c_id; ?>" />
								</td>
							</tr>
							<tr>
								<td class="text-left" colspan="3"><?php echo $text_customer_group; ?></td>
								<td class="text-left" colspan="3">
									<select class="form-control" name="c_group_id">
										<?php foreach ($c_groups as $customer_group) { ?>
											<?php if ($customer_group['customer_group_id'] == $c_group_id) { ?>
											<option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr id="c_autocomplete" <?php if ($c_id ==0) { ?>style="display:none;"<?php } ?>>
								<td class="text-left" colspan="3"><?php echo $text_customer; ?></td>
								<td class="text-left" colspan="3"><input class="form-control" type="text" name="c_name" value="<?php echo $c_name; ?>" />&nbsp;<?php echo '('.$text_autocomplete.')'; ?></td>
							</tr>
							<tr>
								<td class="text-left" colspan="3"><span class="required">*</span> <?php echo $entry_firstname; ?></td>
								<td class="text-left" colspan="3"><input class="form-control" type="text" name="c_firstname" value="<?php echo $c_firstname; ?>" /></td>
							</tr>
							<tr>
								<td class="text-left" colspan="3"><span class="required">*</span> <?php echo $entry_lastname; ?></td>
								<td class="text-left" colspan="3"><input class="form-control" type="text" name="c_lastname" value="<?php echo $c_lastname; ?>" /></td>
							</tr>
							<tr>
								<td class="text-left" colspan="3"><span class="required">*</span> <?php echo $entry_email; ?></td>
								<td class="text-left" colspan="3"><input class="form-control" type="text" name="c_email" value="<?php echo $c_email; ?>" /></td>
							</tr>
							<tr>
								<td class="text-left" colspan="3"><span class="required">*</span> <?php echo $entry_telephone; ?></td>
								<td class="text-left" colspan="3"><input class="form-control" type="text" name="c_telephone" value="<?php echo $c_telephone; ?>" /></td>
							</tr>
							<tr>
								<td class="text-left" colspan="3"><?php echo $entry_fax; ?></td>
								<td class="text-left" colspan="3"><input class="form-control" type="text" name="c_fax" value="<?php echo $c_fax; ?>" /></td>
							</tr>
							<tr>
								<td class="text-left" colspan="6" style="color: #FF802B; font-size: 12px; font-weight: bold; "><?php echo $text_address_info; ?></td>
							</tr>
							<tr>
								<td class="text-left" colspan="3"><span class="required">*</span> <?php echo $entry_firstname; ?></td>
								<td class="text-left" colspan="3"><input class="form-control" type="text" name="a_firstname" value="<?php echo $a_firstname; ?>" /></td>
							</tr>
							<tr>
								<td class="text-left" colspan="3"><span class="required">*</span> <?php echo $entry_lastname; ?></td>
								<td class="text-left" colspan="3"><input class="form-control" type="text" name="a_lastname" value="<?php echo $a_lastname; ?>" /></td>
							</tr>
							<tr>
								<td class="text-left" colspan="3"><span class="required">*</span> <?php echo $entry_address_1; ?></td>
								<td class="text-left" colspan="3"><input class="form-control" type="text" name="a_address_1" value="<?php echo $a_address_1; ?>" /></td>
							</tr>
							<tr>
								<td class="text-left" colspan="3"><?php echo $entry_address_2; ?></td>
								<td class="text-left" colspan="3"><input class="form-control" type="text" name="a_address_2" value="<?php echo $a_address_2; ?>" /></td>
							</tr>
							<tr>
								<td class="text-left" colspan="3"><span class="required">*</span> <?php echo $entry_city; ?></td>
								<td class="text-left" colspan="3"><input class="form-control" type="text" name="a_city" value="<?php echo $a_city; ?>" /></td>
							</tr>
							<tr>
								<td class="text-left" colspan="3"><span id="postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></td>
								<td class="text-left" colspan="3"><input class="form-control" type="text" name="a_postcode" value="<?php echo $a_postcode; ?>" /></td>
							</tr>
							<tr>
								<td class="text-left" colspan="3"><span class="required">*</span> <?php echo $entry_country; ?></td>
								<td class="text-left" colspan="3">
									<select class="form-control" name="a_country_id" onchange="country('<?php echo $a_zone_id; ?>');">
										<option value=""><?php echo $text_select; ?></option>
										<?php foreach ($c_countries as $customer_country) { ?>
											<?php if ($customer_country['country_id'] == $a_country_id) { ?>
											<option value="<?php echo $customer_country['country_id']; ?>" selected="selected"><?php echo $customer_country['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $customer_country['country_id']; ?>"><?php echo $customer_country['name']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td class="text-left" colspan="3"><span class="required">*</span> <?php echo $entry_zone; ?></td>
								<td class="text-left" colspan="3"><select class="form-control" name="a_zone_id"></select></td>
							</tr>
						</tbody>
					</table>
					<!-- add for Default Customer end -->
				</div>
	
				<div class="tab-pane settings_div" id="tab_settings_rounding">
					<!-- add for Rounding begin -->
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr><td colspan="2" class="text-left" style="background-color: #E7EFEF;"><?php echo $text_rounding_setting; ?></td></tr>
						</thead>
						<tbody>
							<tr>
								<td class="text-center" width="1"><input type="checkbox" name="enable_rounding" value="<?php echo $enable_rounding; ?>" <?php if($enable_rounding=='1') { ?>checked="checked"<?php } ?> /></td>
								<td class="text-left"><?php echo $text_rounding_enable; ?></td>
							</tr>
							<tr>
								<td class="text-center" width="1"><input type="radio" name="config_rounding" value="5c" <?php if ($config_rounding == '5c') { ?> checked="checked" <?php } ?>/></td>
								<td class="text-left"><?php echo $text_rounding_5c; ?></td>
							</tr>
							</tr>
								<td class="text-center" width="1"><input type="radio" name="config_rounding" value="10c" <?php if ($config_rounding == '10c') { ?> checked="checked" <?php } ?>/></td>
								<td class="text-left"><?php echo $text_rounding_10c; ?></td>
							</tr>
							</tr>
								<td class="text-center" width="1"><input type="radio" name="config_rounding" value="50c" <?php if ($config_rounding == '50c') { ?> checked="checked" <?php } ?>/></td>
								<td class="text-left"><?php echo $text_rounding_50c; ?></td>
							</tr>
						</tbody>
					</table>
					<!-- add for Rounding end -->
				</div>
				
				<div class="tab-pane settings_div" id="tab_settings_discount">
					<!-- add for Maximum Discount begin -->
					<table id="max_discount_setting" class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<td class="text-left" colspan="3" style="background-color: #E7EFEF;"><?php echo $text_max_discount_setting; ?></td>
							</tr>
							<tr>
								<td class="text-left"><?php echo $column_group; ?></td>
								<td class="text-left" colspan="2"><?php echo $column_discount_limit; ?></td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($user_groups as $user_group) { ?>
							<tr>
								<td class="text-left" rowspan="2"><b><?php echo $user_group['name']; ?></b></td>
								<td class="text-left"><?php echo $entry_max_discount_fixed; ?></td>
								<td class="text-left">
									<input type="text" class="form-control" name="<?php echo $user_group['user_group_id']; ?>_max_discount_fixed" value="<?php echo $user_group['max_discount_fixed']; ?>" />
								</td>
							</tr>
							<tr>
								<td class="text-left"><?php echo $entry_max_discount_percentage; ?></td>
								<td class="text-left">
									<input type="text" class="form-control" name="<?php echo $user_group['user_group_id']; ?>_max_discount_percentage" value="<?php echo $user_group['max_discount_percentage']; ?>" />
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
					<!-- add for Maximum Discount begin -->
				</div>
				
				<div class="tab-pane settings_div" id="tab_settings_table_management">
					<!-- add for table management begin -->
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr><td colspan="3" class="text-left" style="background-color: #E7EFEF;"><?php echo $text_table_management_setting; ?></td></tr>
							<tr><td class="text-left" colspan="3">
								<input type="checkbox" name="enable_table_management" value="<?php echo $enable_table_management; ?>" <?php if($enable_table_management=='1') { ?>checked="checked"<?php } ?> />&nbsp;
								<?php echo $text_table_management_enable; ?>
							</td></tr>
						</thead>
						<tbody id="table_list">
							<tr>
								<td class="text-left"><?php echo $entry_table_number; ?></td>
								<td class="text-left"><input type="text" name="table_number" class="form-control" value="<?php echo $table_number; ?>" /></td>
								<td class="text-left">
									<a id="button_set_number" onclick="setTableNumber();" class="btn btn-info"><?php echo $button_table_set_number; ?></a>
								</td>
							</tr>
							<tr class='filter'>
								<td class="text-left"><?php echo $column_table_id; ?></td>
								<td class="text-left"><?php echo $column_table_desc; ?></td>
								<td class="text-left"><?php echo $column_table_action; ?></td>
							</tr>
							<?php if (!empty($tables)) { foreach ($tables as $table) { ?>
							<tr>
								<td class="text-left"><input type="text" name="name_<?php echo $table['table_id']; ?>" value="<?php echo $table['name']; ?>" class="form-control"/></td>
								<td class="text-left"><input type="text" name="desc_<?php echo $table['table_id']; ?>" value="<?php echo $table['description']; ?>" class="form-control"/></td>
								<td class="text-left">
									<input type="hidden" value="<?php echo $table['table_id']?>" />
									<a class="btn btn-success btn-sm" onclick="modifyTable(this);"><i class="fa fa-save fa-lg"></i> <?php echo $button_table_modify; ?></a>&nbsp;&nbsp;
									<a class="btn btn-danger btn-sm" onclick="removeTable(this);"><i class="fa fa-trash-o fa-lg"></i> <?php echo $button_table_remove; ?></a>
								</td>
							</tr>
							<?php }}?>
						</tbody>
					</table>
					<!-- add for table management end -->
				</div>

				<div class="tab-pane settings_div" id="tab_settings_affiliate">
					<!-- add for User as Affiliate begin -->
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<td colspan="3" class="text-left" style="background-color: #E7EFEF;"><?php echo $text_user_affi_setting; ?></td>
							</tr>
							<tr>
								<td class="text-left"><?php echo $column_ua_user; ?></td>
								<td class="text-left"><?php echo $column_ua_affiliate; ?></td>
								<td class="text-right"><?php echo $column_ua_action; ?></td>
							</tr>
						</thead>
						<tbody id="user_affi_list">
							<tr class='filter' id="user_affi_tr">
								<td class="text-left" width="45%">
									<select name="user_name" class="form-control">
										<?php foreach($ua_users as $user) { ?>
										<option value="<?php echo $user['user_id']; ?>"><?php echo $user['username']; ?></option>
										<?php } ?>
									</select>
								</td>
								<td class="text-left" width="45%">
									<select name="affiliate_name" class="form-control">
										<?php foreach($ua_affiliates as $affiliate) { ?>
										<option value="<?php echo $affiliate['affiliate_id']; ?>"><?php echo $affiliate['firstname'].' '.$affiliate['lastname']; ?></option>
										<?php } ?>
									</select>
								</td>
								<td calss="text-center" width="10%"><a class="btn btn-info" id="button_add_ua" onclick="addUA();"><i class="fa fa-plus-circle fa-lg"></i></a></td>
							</tr>
							<?php 
								if (!empty($user_affis)) {
									foreach ($user_affis as $user_affi) { ?>
							<tr id="user_affi-<?php echo $user_affi['user_id']; ?>-<?php echo $user_affi['affiliate_id']; ?>">
								<td class="text-left" width="45%"><?php echo $user_affi['username']; ?></td>
								<td class="text-left" width="45%"><?php echo $user_affi['firstname'].' '.$user_affi['lastname']; ?></td>
								<td class="text-center" width="10%"><a class="btn btn-danger btn-sm" onclick="deleteUA('user_affi-<?php echo $user_affi['user_id']; ?>-<?php echo $user_affi['affiliate_id']; ?>');"><i class="fa fa-trash-o fa-lg"></i></a></td>
							</tr>
							<?php }} ?>
						</tbody>
					</table>
					<!-- add for User as Affiliate begin -->
				</div>
	
				<div class="tab-pane settings_div" id="tab_settings_quote">
					<!-- add for Quotation begin -->
					<table class="table table-striped table-bordered table-hover">
						<col width="420" />
						<col width="180" />
						<thead>
							<tr>
								<td colspan="2" class="text-left" style="background-color: #E7EFEF;"><?php echo $text_quote_status_setting; ?></td>
							</tr>
							<tr>
								<td class="text-left"><?php echo $column_quote_status_name; ?></td>
								<td class="text-center"><?php echo $column_quote_status_action; ?></td>
							</tr>
						</thead>
						<tbody id="quote_status_list">
							<tr class='filter' id="quote_status_tr">
								<td class="text-left">
									<input name="quote_status_name" type="text" class="form-control" value="" onkeypress="return addStatusOnEnter(event);"/>
								</td>
								<td class="text-center"><a class="btn btn-info" id="button_add_quote_status" onclick="addQuoteStatus();"><i class="fa fa-plus-circle fa-lg"></i> <?php echo $button_add_type; ?></a></td>
							</tr>
							<?php 
								if (!empty($quote_statuses)) {
									foreach ($quote_statuses as $quote_status) { ?>
							<tr>
								<td class="text-left">
									<span><?php echo $quote_status['name']; ?></span>
									<input type="hidden" name="quote_status_id_<?php echo $quote_status['quote_status_id']; ?>" value="<?php echo $quote_status['quote_status_id']; ?>" />
								</td>
								<td class="text-center">
									<a class="btn btn-success btn-sm" onclick="renameQuoteStatus(this);"><i class="fa fa-edit fa-lg"></i> <?php echo $button_rename; ?></a>
									<a class="btn btn-danger btn-sm" onclick="deleteQuoteStatus(this);"><i class="fa fa-trash-o fa-lg"></i> <?php echo $button_delete; ?></a>
								</td>
							</tr>
							<?php }} ?>
						</tbody>
					</table>
					<!-- add for Quotation end -->
				</div>
				
				<div class="tab-pane settings_div" id="tab_settings_sales_report">
					<div style="float: left; padding: 5px;">
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<td colspan="4" class="text-left" style="background-color: #E7EFEF;"><?php echo $text_sales_report_setting; ?></td>
								<!--
								<td colspan="2" class="text-left" style="background-color: #E7EFEF; border-right: 0;"><?php echo $text_sales_report_setting; ?></td>
								<td colspan="2" class="text-right" style="background-color: #E7EFEF; border-left: 0;"><a class="btn btn-primary" onclick="exportReport('sales');"><i class="fa fa-table fa-lg"></i> <?php echo $button_export_to_csv; ?></a></td>
								-->
							</tr>
							<tr>
								<td class="text-left"><input type="checkbox" name="sales_report_use_title" value="<?php echo $sales_report_use_title; ?>" <?php if($sales_report_use_title=='1') { ?>checked="checked"<?php } ?> /></td>
								<td class="text-left" colspan="3"><?php echo $text_use_title_in_report; ?></td>
							</tr>
							<tr>
								<td class="text-left" colspan="3" style="border-right: 0;">
									<select name="sales_report_item_add" class="form-control">
										<?php foreach ($sales_report_available_items as $label => $value) { ?>
										<option value="<?php echo $value['title']; ?>" type="<?php echo $value['type']; ?>"><?php echo $label; ?></option>
										<?php } ?>
									</select>
								</td>
								<td class="text-right" style="border-left: 0;"><a class="btn btn-success" onclick="addItem('sales');"><i class="fa fa-plus fa-lg"></i> <?php echo $button_add_item; ?></a></td>
							</tr>
							<tr>
								<td class="text-right" colspan="4">
									<a class="btn btn-default" onclick="moveItemUp('sales');"><i class="fa fa-chevron-up fa-lg"></i></a>
									<a class="btn btn-default" onclick="moveItemDown('sales');"><i class="fa fa-chevron-down fa-lg"></i></a>
									<a class="btn btn-danger" onclick="deleteItem('sales');"><i class="fa fa-close fa-lg"></i></a>
								</td>
							</tr>
							<tr>
								<td></td>
								<td class="text-left"><?php echo $text_display_title; ?></td>
								<td class="text-left"><?php echo $text_data_source; ?></td>
								<td class="text-left"><?php echo $text_item_speical; ?></td>
							</tr>
						</thead>
						<tbody id="sales_report_item_list">
							<?php foreach ($sales_report_items as $table_field => $report_item) { ?>
							<tr>
								<td class="text-center">
									<input type="radio" class="form-control" name="sales_report_item" value="<?php echo $table_field; ?>" />
									<input type="hidden" name="sales_report_items[<?php echo $table_field; ?>][order]" value="<?php echo $report_item['order']; ?>" />
									<input type="hidden" name="sales_report_items[<?php echo $table_field; ?>][type]" value="<?php echo $report_item['type']; ?>" />
									<input type="hidden" name="sales_report_items[<?php echo $table_field; ?>][feature]" value="<?php echo (isset($report_item['feature']) ? $report_item['feature'] : ''); ?>" />
									<input type="hidden" name="sales_report_items[<?php echo $table_field; ?>][title]" value="<?php echo $report_item['title']; ?>" />
								</td>
								<td class="text-left item-title">
									<?php echo $report_item['title']; ?>
								</td>
								<td class="text-left"><?php echo ($report_item['type'] == '_pos_custom_field' ? (strpos($table_field, '{null}_') !== false ? '' : ((isset($report_item['feature']) && $report_item['feature'] == '{today}') ? date('Y-m-d') : substr($table_field, 0, strpos($table_field, '_'.$report_item['order'])))) : $table_field); ?></td>
								<td class="text-left"><?php echo (isset($report_item['feature']) ? $report_item['feature'] : ''); ?></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
					</div>
					<div style="float: left;  padding: 5px;">
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<td colspan="4" class="text-left" style="background-color: #E7EFEF;"><?php echo $text_stock_report_setting; ?></td>
								<!--
								<td colspan="2" class="text-left" style="background-color: #E7EFEF; border-right: 0;"><?php echo $text_stock_report_setting; ?></td>
								<td colspan="2" class="text-right" style="background-color: #E7EFEF; border-left: 0;"><a class="btn btn-primary" onclick="exportReport('stock');"><i class="fa fa-table fa-lg"></i> <?php echo $button_export_to_csv; ?></a></td>
								-->
							</tr>
							<tr>
								<td class="text-left"><input type="checkbox" name="stock_report_use_title" value="<?php echo $stock_report_use_title; ?>" <?php if($stock_report_use_title=='1') { ?>checked="checked"<?php } ?> /></td>
								<td class="text-left" colspan="3"><?php echo $text_use_title_in_report; ?></td>
							</tr>
							<tr>
								<td class="text-left" colspan="3" style="border-right: 0;">
									<select name="stock_report_item_add" class="form-control">
										<?php foreach ($stock_report_available_items as $label => $value) { ?>
										<option value="<?php echo $value['title']; ?>" type="<?php echo $value['type']; ?>"><?php echo $label; ?></option>
										<?php } ?>
									</select>
								</td>
								<td class="text-right" style="border-left: 0;"><a class="btn btn-success" onclick="addItem('stock');"><i class="fa fa-plus fa-lg"></i> <?php echo $button_add_item; ?></a></td>
							</tr>
							<tr>
								<td class="text-right" colspan="4">
									<a class="btn btn-default" onclick="moveItemUp('stock');"><i class="fa fa-chevron-up fa-lg"></i></a>
									<a class="btn btn-default" onclick="moveItemDown('stock');"><i class="fa fa-chevron-down fa-lg"></i></a>
									<a class="btn btn-danger" onclick="deleteItem('stock');"><i class="fa fa-close fa-lg"></i></a>
								</td>
							</tr>
							<tr>
								<td></td>
								<td class="text-left"><?php echo $text_display_title; ?></td>
								<td class="text-left"><?php echo $text_data_source; ?></td>
								<td class="text-left"><?php echo $text_item_speical; ?></td>
							</tr>
						</thead>
						<tbody id="stock_report_item_list">
							<?php foreach ($stock_report_items as $table_field => $report_item) { ?>
							<tr>
								<td class="text-center">
									<input type="radio" class="form-control" name="stock_report_item" value="<?php echo $table_field; ?>" />
									<input type="hidden" name="stock_report_items[<?php echo $table_field; ?>][order]" value="<?php echo $report_item['order']; ?>" />
									<input type="hidden" name="stock_report_items[<?php echo $table_field; ?>][type]" value="<?php echo $report_item['type']; ?>" />
									<input type="hidden" name="stock_report_items[<?php echo $table_field; ?>][feature]" value="<?php echo (isset($report_item['feature']) ? $report_item['feature'] : ''); ?>" />
									<input type="hidden" name="stock_report_items[<?php echo $table_field; ?>][title]" value="<?php echo $report_item['title']; ?>" />
								</td>
								<td class="text-left item-title">
									<?php echo $report_item['title']; ?>
								</td>
								<td class="text-left"><?php echo ((isset($report_item['feature']) && $report_item['feature'] == '{average cost}') ? $table_field : ($report_item['type'] == '_pos_custom_field' ? (strpos($table_field, '{null}_') !== false ? '' : ((isset($report_item['feature']) && $report_item['feature'] == '{today}') ? date('Y-m-d') : substr($table_field, 0, strpos($table_field, '_'.$report_item['order'])))) : $table_field)); ?></td>
								<td class="text-left"><?php echo (isset($report_item['feature']) ? $report_item['feature'] : ''); ?></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
					</div>
				</div>
			</div>
			<?php } ?>
			<?php if ($is_label_user) { ?>
			<div class="tab-pane active settings_div" id="tab_settings_label" style="width: 680px; padding: 5px;">
			<!-- add for label settings begin -->
			<table class="table table-bordered" id="label_settings_table" style="table-layout: fixed;">
				<?php $cur_template = false; ?>
				<thead>
					<tr>
						<td colspan="4" class="text-left" style="background-color: #E7EFEF;"><?php echo $text_label_layout_setting; ?></td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td colspan="4" class="text-center"><img src="view/image/pos/label_print.png" /></td>
					</tr>
					<tr>
						<td colspan="4" class="text-left" style="background-color: #E7EFEF;"><?php echo $text_label_adjust_setting; ?></td>
					</tr>
					<tr>
						<td class="text-left"><?php echo $entry_label_adjust_width; ?></td>
						<td class="text-left"><input name="label_adjust_width" class="form-control" style="text-align: right;" value="<?php echo $label_adjust_width; ?>" /></td>
						<td class="text-left"><?php echo $entry_label_adjust_height; ?></td>
						<td class="text-left"><input name="label_adjust_height" class="form-control" style="text-align: right;" value="<?php echo $label_adjust_height; ?>" /></td>
					</tr>
					<tr>
						<td class="text-left" style="background-color: #E7EFEF;"><?php echo $text_label_template_setting; ?></td>
						<td colspan="2" class="text-left" style="background-color: #E7EFEF;">
							<select name="label_templates" class="form-control">
								<option value=""></option>
								<?php foreach ($label_templates as $key => $label_template) { ?>
								<?php if (!$cur_template) { $cur_template = $label_template; } ?>
								<option value="<?php echo $key; ?>"><?php echo $label_template['name']; ?></option>
								<?php }?>
								<option value="-100" disabled>──────────</option>
								<option value="new"><?php echo $text_new_template; ?></option>
							</select>
						</td>
						<td class="text-left" style="background-color: #E7EFEF;">
							<span id="view_buttons" style="display:none;" >
							<a class="btn btn-info" id="label_edit" onclick="editTemplate($('select[name=label_templates]').val());"><i class="fa fa-pencil fa-lg"></i> <?php echo $button_edit; ?></a>
							<a class="btn btn-danger" id="label_delete" onclick="deleteTemplate($('select[name=label_templates]').val());"><i class="fa fa-trash fa-lg"></i> <?php echo $button_delete; ?></a>
							</span>
							<a class="btn btn-success" id="label_save" style="display:none;" onclick="saveTemplate(this);"><i class="fa fa-save fa-lg"></i> <?php echo $button_save; ?></a>
						</td>
					</tr>
				</tbody>
				<tbody id="template_general" style="display:none;">
					<tr>
						<td class="text-left"><?php echo $entry_label_template_name; ?><input type="hidden" name="label_template_id" value="" /></td>
						<td colspan="3" class="text-left"><input type="text" class="form-control" name="label_template_name" value="" /></td>
					</tr>
					<tr>
						<td colspan="4" class="text-left" style="background-color: #D0EF72;"><?php echo $text_label_template_layout; ?></td>
					</tr>
					<tr>
						<td class="text-left"><?php echo $entry_label_top_margin; ?> (mm)</td>
						<td class="text-left"><input type="text" name="label_top_margin" class="form-control" id="label_top_margin" style="text-align: right;" value="" /></td>
						<td class="text-left"><?php echo $entry_label_height; ?> (mm)</td>
						<td class="text-left"><input type="text" name="label_height" class="form-control" id="label_height" style="text-align: right;" value="" /></td>
					</tr>
					<tr>
						<td class="text-left"><?php echo $entry_label_side_margin; ?> (mm)</td>
						<td class="text-left"><input type="text" name="label_side_margin" class="form-control" id="label_side_margin" style="text-align: right;" value="" /></td>
						<td class="text-left"><?php echo $entry_label_width; ?> (mm)</td>
						<td class="text-left"><input type="text" name="label_width" class="form-control" id="label_width" style="text-align: right;" value="" /></td>
					</tr>
					<tr>
						<td class="text-left"><?php echo $entry_label_vertical_pitch; ?> (mm)</td>
						<td class="text-left"><input type="text" name="label_vertical_pitch" class="form-control" id="label_vertical_pitch" style="text-align: right;" value="" /></td>
						<td class="text-left"><?php echo $entry_label_number_across; ?></td>
						<td class="text-left"><input type="text" name="label_number_across" class="form-control" id="label_number_across" style="text-align: right;" value="" /></td>
					</tr>
					<tr>
						<td class="text-left"><?php echo $entry_label_horizontal_pitch; ?> (mm)</td>
						<td class="text-left"><input type="text" name="label_horizontal_pitch" class="form-control" id="label_horizontal_pitch" style="text-align: right;" value="" /></td>
						<td class="text-left"><?php echo $entry_label_number_down; ?></td>
						<td class="text-left"><input type="text" name="label_number_down" class="form-control" id="label_number_down" style="text-align: right;" value="" /></td>
					</tr>
					<tr>
						<td colspan="4" class="text-left" style="background-color: #D0EF72;"><?php echo $text_label_template_content; ?></td>
					</tr>
				</tbody>
				<?php 
					$label_canvas_width = 381; $label_canvas_height = 254;
					if (!empty($cur_template['width']) && !empty($cur_template['height'])) {
						$label_width = (int)$cur_template['width'];
						$label_height = (int)$cur_template['height'];
						if ($label_canvas_width * $label_height / $label_width > $label_canvas_height) {
							$label_canvas_width = (int)($label_canvas_height * $label_width / $label_height);
						} elseif ($label_canvas_height * $label_width / $label_height > $label_canvas_width) {
							$label_canvas_height = (int)($label_canvas_width * $label_height / $label_width);
						}
						
						// get the actual size with transformation
						$label_width = $label_width * (float)$label_adjust_width;
						$label_height = $label_height * (float)$label_adjust_height;
					}
				?>
				<tbody id="label_viewer" style="display:none;">
					<tr><td class="center" colspan="4">
						<div id="label_viewer_canvas" style="position: relative; height: <?php echo (!empty($label_height)) ? $label_height . 'mm' : $label_canvas_height . 'px'; ?>; width: <?php echo (!empty($label_width)) ? $label_width . 'mm' : $label_canvas_width . 'px'; ?>; margin: 10px auto; border: 2px dashed #bebebe; border-radius: 10px; overflow: hidden;"></div>
					</td></tr>
				<tbody>
				<!-- for the label content editor -->
				<tbody id="label_editor" style="display:none;">
					<tr>
						<td colspan="4" class="text-left"><?php echo $text_label_template_editor_title; ?></td>
					</tr>
					<tr>
						<td class="text-center" colspan="4" style="-webkit-touch-callout: none; -webkit-user-select: none; -khtml-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none;">
							<div id="drag" style="padding: 0; margin: auto; width: 540px; height: 320px;">
								<div id="editor-left" style="width: 120px; float: left; margin-right: 10px; margin-top: 10px; ">
									<div class="draggable" id="label_text"><?php echo $text_label_text; ?></div>
									<div class="draggable" id="label_logo"><?php echo $text_label_image; ?></div>
									<div class="draggable" id="product_name"><?php echo $text_label_product_name; ?></div>
									<div class="draggable" id="product_description"><?php echo $text_label_product_description; ?></div>
									<div class="draggable" id="product_model"><?php echo $text_label_product_model; ?></div>
									<div class="draggable" id="product_price"><?php echo $text_label_product_price; ?></div>
									<div class="draggable" id="product_sku"><?php echo $text_label_product_sku; ?></div>
									<div class="draggable" id="product_upc"><?php echo $text_label_product_upc; ?></div>
									<div class="draggable" id="product_ean"><?php echo $text_label_product_ean; ?></div>
									<div class="draggable" id="product_mpn"><?php echo $text_label_product_mpn; ?></div>
									<div class="draggable" id="product_manufacturer"><?php echo $text_label_product_manufacturer; ?></div>
									<div class="draggable" id="product_image"><?php echo $text_label_product_image; ?></div>
								</div>
								<div id="editor-right" style="float: left;">
									<input type="hidden" name="label_image" value="" id="label_image" />
									<div id="tblToolbox-top" class="toolbox" style="margin-bottom: 5px;">
										<a class="button-small" onclick="setTextEffect('b');"><span style="font-family: 'Arial Black';"><bold>B</bold></span></a>
										<a class="button-small" onclick="setTextEffect('i');"><span><i>I</i></span></a>
										<a class="button-small" onclick="setTextEffect('u');"><span><u>U</u></span></a>
										<a class="button-small" onclick="setTextEffect('s');"><span><strike>&nbsp;S&nbsp;</strike></span></a>
										<a class="button-small" onclick="setTextEffect('-');"><span style="font-size: 10px;">A-</span></a>
										<a class="button-small" onclick="setTextEffect('+');"><span>A+</span></a>
										<a class="button-small" onclick="setTextEffect('l');"><span>|----</span></a>
										<a class="button-small" onclick="setTextEffect('c');"><span>--|--</span></a>
										<a class="button-small" onclick="setTextEffect('r');"><span>----|</span></a>
									</div>
									<div id="label_template_table" class="label-template-table" style="position: relative; height: <?php echo $label_canvas_height; ?>px; width: <?php echo $label_canvas_width; ?>px; margin-top: 8px; margin-bottom: 10px; background-image: url('view/image/pos/grid-dot.png'); background-repeat:repeat; overflow: hidden;">
									</div>
									<div id="tblToolbox-bottom" class="toolbox">
										<?php $colors = array('#ffffff', '#5484ed', '#a4bdfc', '#46d6db', '#7ae7bf', '#51b749'); ?>
										<div style="width: 100%;">
											<?php foreach ($colors as $color) { ?>
											<a class="button" onclick="setBackground('<?php echo $color; ?>')"><div class="color-bar" style="background-color: <?php echo $color; ?>"></div></a>
											<?php }?>
										</div>
										<?php $colors = array('#fbd75b', '#ffb878', '#dc2127', '#dbadff', '#e1e1e1', '#afafaf'); ?>
										<div>
											<?php foreach ($colors as $color) { ?>
											<a class="button" onclick="setBackground('<?php echo $color; ?>')"><div class="color-bar" style="background-color: <?php echo $color; ?>"></div></a>
											<?php }?>
										</div>
									</div>
								</div>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
			<table class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<td colspan="5" class="text-left" style="background-color: #E7EFEF;"><?php echo $text_label_print; ?></td>
					</tr>
					<tr>
						<td colspan="2" class="text-left" style="background-color: #E7EFEF;"><?php echo $text_label_template_setting; ?></td>
						<td colspan="3" class="text-left" style="background-color: #E7EFEF;">
							<select name="label_print_templates" class="form-control">
								<?php foreach ($label_templates as $key => $label_template) { ?>
								<option value="<?php echo $key; ?>"><?php echo $label_template['name']; ?></option>
								<?php }?>
							</select>
						</td>
					</tr>
				</thead>
				<tbody>
					<tr id="label_product_add_tr">
						<td class="text-left"><?php echo $entry_label_product; ?></td>
						<td class="text-left"><input name="label_product" class="form-control" type="text" value="" /><input type="hidden" name="label_product_id" value="0" /></td>
						<td class="text-left"><?php echo $entry_label_quantity; ?></td>
						<td class="text-left"><input name="label_quantity" class="form-control" type="text" value="1" /></td>
						<td class="text-left"><a class="btn btn-info" onclick="addToPrint()"><?php echo $button_label_add; ?></a></td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="5" class="text-left"><a class="btn btn-primary" onclick="print_label();"><i class="fa fa-print fa-lg"></i> <?php echo $text_label_test_print; ?></a></td>
					</tr>
				</tfoot>
			</table>
			</div>
			<?php } ?>
			<!-- add for label settings end -->
			</form>
		</div>
	</div>
</div>
</div>
<iframe id="print_iframe" src="about:blank" style="display:none; width: 0; height: 0;"></iframe>
<!-- add for jzebra printer begin -->
<?php if (isset($enable_till_control) && $enable_till_control) { ?>
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
<div id="hidden_dialog_area" style="display: none;">
	<div id="sales_report_custom_field_dialog" title="<?php echo $text_title_prompt; ?>">
		<table class="table">
			<tr><td></td><td class="text-left">
				<div>
					<form class="form-inline" role="form">
						<div class="form-group">
							<label for="custom_field_title"><?php echo $entry_title; ?></label>
							<input type="text" class="form-control" id="custom_field_title" />
						</div>
						<div class="form-group">
							<label for="custom_field_value"><?php echo $entry_value; ?></label>
							<input type="text" class="form-control" id="custom_field_value" />
						</div>
						<div class="checkbox">
							<label><input type="checkbox" id="custom_field_value_in_quote"> <?php echo $entry_value_in_quote; ?></label>
						</div>
						<button type="button" class="btn btn-primary" id="button_add_custom_field"><i class="fa fa-plus fa-lg"></i> <?php echo $button_add_item; ?></button>
						<input type="hidden" id="custom_field_order" value="0" />
						<input type="hidden" id="custom_field_section" value="" />
					</form>
				</div>
			</td></tr>
			<tr><td class="text-left">
				<?php echo $text_or; ?>
			</td><td></td></tr>
			<tr><td></td><td class="text-left">
				<div>
					<button type="button" class="btn btn-info" id="button_add_new_line"><i class="fa fa-level-down fa-lg"></i> <?php echo $text_new_line; ?></button>
					<button type="button" class="btn btn-warning" id="button_add_today_date"><i class="fa fa-calendar fa-lg"></i> <?php echo $text_today_date; ?></button>
					<button type="button" class="btn btn-success" id="button_add_average_cost"><i class="fa fa-calculator fa-lg"></i> <?php echo $text_average_cost; ?></button>
				</div>
			</td></tr>
		</table>
	</div>
</div>
<!-- add for jzebra printer end -->
</div>

<link rel="stylesheet" type="text/css" href="view/javascript/jquery/ui/jquery-ui.min.css" />
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="view/javascript/pos/barcode.min.js"></script>
<script type="text/javascript">
// add for table management begin
var jcrop_api;
var selectIndex = -1;
var tables = new Array();
// add for table management end

$(document).on('click', '.toggle_tab', function() {
	if ($(this).is(':checked')) {
		$(this).val('1');
	} else {
		$(this).val('0');
		$('.nav-tabs a:first').trigger('click');
	}
	$('#tab_'+$(this).attr('name')).toggle();
});

$('input[type=checkbox]').click(function() {
	var inputName = $(this).attr('name');
	if (inputName.indexOf('[]') < 0) {
		if ($(this).is(':checked')) {
			$(this).val('1');
		} else {
			$(this).val('0');
		}
	}
});

$('td[id^=cash_display]').click(function() {
	var orgText = $(this).find('span').text();
	$(this).find('span').text('');
	var cash_value = $(this).attr('id').substring('cash_display_'.length);
	console.log(cash_value);

	var width = $(this).width()-2;
	$('<input style="width:'+width+'px;" type="text" name="temp_input" class="onenter onblur"/>').appendTo($(this));
	$('.onenter').val(orgText).select().keypress(function(e) {
		if (e.keyCode == 13) {
			var value = $('input[name=temp_input]').val();
			$('input[name=temp_input]').parent().find('span').text(value);
			$('input[name=temp_input]').remove();
			$('input[name=temp_input]').parent().find('input').val(value);
			// have to return false, otherwise, the keypress event will be used by opencart itself to submit the form!!
			return false;
		}
	});
	$('.onblur').val(orgText).select().blur(function() {
		setTimeout(function() {
			$('input[name=temp_input]').parent().text(orgText);
			$('input[name=temp_input]').remove();
		}, 30);
	});
});

var payment_type_row = <?php echo $payment_type_row_no; ?>;

function addPaymentType() {
	var checkValue = checkPaymentType();

	if (checkValue == 1) {
		// already in the list
		warning_tips = '<img src="view/image/warning.png" id="type_warning_tips" alt="<?php echo $text_type_already_exist; ?>" title="<?php echo $text_type_already_exist; ?>" />';
		$('#type_warning_tips').remove();
		$(warning_tips).insertAfter($('#payment_type'));
		return false;
	}

	$('#type_warning_tips').remove();
	var value = $('#payment_type').val();
	var checked = $('input[name=payment_type_enable]').is(':checked');
	var new_payment_type_html = '<tr id="payment_type_' + payment_type_row + '">';
	new_payment_type_html += '<td class="text-left">' + value + '</td>';
	new_payment_type_html += '<td class="text-left"><input type="checkbox" name="payment_type_enables[' + payment_type_row + ']" class="form-control" value="' + (checked ? 1 : 0) + '" ' + (checked ? 'checked="checked"' : '') + ' /></td>';
	new_payment_type_html += '<td class="text-center"><a onclick="deletePaymentType(\'payment_type_' + payment_type_row + '\');" class="btn btn-danger btn-sm"><i class="fa fa-trash-o fa-lg"></i> <?php echo $button_remove; ?></a>';
	new_payment_type_html += '	<input type="hidden" name="POS_payment_types[' + payment_type_row + ']" value="' + value + '"/>';
	new_payment_type_html += '</td></tr>';
	$(new_payment_type_html).insertAfter('#payment_type_add');
	$('#payment_type').val("");
	$('input[name=payment_type_enable]').prop('checked', false);
	payment_type_row ++;

};

function deletePaymentType(rowId) {
	$('#'+rowId).remove();
};

function checkPaymentType() {
	retValue = 0;
	curValue = $('#payment_type').val().toLowerCase();
	$("#payment_type_table tr").each(function(){
		value = $(this).find('td:first-child').text().toLowerCase();
		if (curValue == value) {
			retValue = 1;
		}
	});

	return retValue;
};

function addPaymentOnEnter(e) {
	var key;
	if (window.event)
		key = window.event.keyCode; //IE
	else
		key = e.which; //Firefox & others

	if(key == 13) {
		addPaymentType();
		return false;
	}
}

// add for Print begin
function image_upload(field, thumb, keep_size, fn) {
	$('#modal-image').remove();

	$.ajax({
		url: 'index.php?route=common/filemanager&token=<?php echo $token; ?>&target=' + field + '&thumb=' + thumb,
		dataType: 'html',
		success: function(html) {
			$('body').append('<div id="modal-image" class="modal">' + html + '</div>');
			$('#modal-image').modal('show');
		}
	});
};
// add for Print end
// add for User as Affiliate begin
function addUA() {
	var user_id = $('select[name=user_name]').val();
	var username = $('select[name=user_name] option:selected').text();
	var affi_id = $('select[name=affiliate_name]').val();
	var affiname = $('select[name=affiliate_name] option:selected').text();
	if (username != '' && affiname != '') {
		var data = {'user_id':user_id, 'affiliate_id':affi_id};
		$.ajax({
			url: 'index.php?route=module/pos/addUA&token=<?php echo $token; ?>',
			type: 'post',
			dataType: 'json',
			data: data,
			beforeSend: function() {
				$('#button_add_ua').hide();
				$('#button_add_ua').before('<img src="view/image/pos/loading.gif" class="loading" style="padding-left: 5px;" />');	
			},
			complete: function() {
				$('.loading').remove();
				$('#button_add_ua').show();
			},
			success: function(json) {
				var trId = 'user_affi-' + user_id + '-' + affi_id;
				var tr_element = '<tr id="' + trId + '"><td class="text-left" width="45%">' + username + '</td><td class="text-left" width="45%">' + affiname + '</td><td class="text-center" width="10%"><a class="btn btn-danger btn-sm" onclick="deleteUA(\'' + trId + '\');"><i class="fa fa-trash-o fa-lg"></i></a></td></tr>'
				$(tr_element).insertAfter('#user_affi_tr');
				// remove value from the list
				$('select[name=user_name] option:selected').remove();
				$('select[name=affiliate_name] option:selected').remove();
			}
		});
	}
};

function deleteUA(uaTrId) {
	var ids = uaTrId.split('-');
	var data = {'user_id':ids[1], 'affiliate_id':ids[2]};
	var username = $('#'+uaTrId).find('td').eq(0).text();
	var affiname = $('#'+uaTrId).find('td').eq(1).text();
	var xButton = $('#'+uaTrId).find('td').eq(2).find('a').eq(0);
	$.ajax({
		url: 'index.php?route=module/pos/deleteUA&token=<?php echo $token; ?>',
		type: 'post',
		dataType: 'json',
		data: data,
		beforeSend: function() {
			xButton.hide();
			xButton.before('<img src="view/image/pos/loading.gif" class="loading" style="padding-left: 5px;" />');	
		},
		complete: function() {
			$('.loading').remove();
		},
		success: function(json) {
			$('#'+uaTrId).remove();
			// add value back to the list
			$('select[name=user_name]').append('<option value="' + ids[1] + '">' + username + '</option>');
			$('select[name=affiliate_name]').append('<option value="' + ids[2] + '">' + affiname + '</option>');
		}
	});
};
// add for User as Affiliate end
// add for Default Customer begin
function country(zone_id) {
  if ($('select[name=a_country_id]').val() != '') {
		$.ajax({
			url: 'index.php?route=module/pos/country&token=<?php echo $token; ?>&country_id=' + $('select[name=a_country_id]').val(),
			dataType: 'json',
			beforeSend: function() {
				$('select[name=a_country_id]').after('<span class="wait">&nbsp;<img src="view/image/pos/loading.gif" alt="" /></span>');
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
						
						if (json['zone'][i]['zone_id'] == zone_id) {
							html += ' selected="selected"';
						}
		
						html += '>' + json['zone'][i]['name'] + '</option>';
					}
				} else {
					html += '<option value="0"><?php echo $text_none; ?></option>';
				}
				
				$('select[name=a_zone_id]').html(html);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
};

$(document).on('change', 'input[name=c_type]', function(event) {
	if ($('input[name=c_type]:checked').val() == '1') {
		$('#c_autocomplete').hide();
		// disable all values
		$('#c_default input[type=text]').each(function() {
			// disable the input
			$(this).attr('disabled', true);
		});
		$('#c_default select').each(function() {
			// disable the input
			$(this).attr('disabled', true);
		});
		setBuildinValue();
	} else if ($('input[name=c_type]:checked').val() == '2') {
		$('#c_autocomplete').hide();
		// enable all values
		$('#c_default input[type=text]').each(function() {
			// disable the input
			$(this).attr('disabled', false);
		});
		$('#c_default select').each(function() {
			// disable the input
			$(this).attr('disabled', false);
		});
		setConfigValue();
	} else {
		// disable all values
		$('#c_default input[type=text]').each(function() {
			// disable the input
			$(this).attr('disabled', true);
		});
		$('#c_default select').each(function() {
			// disable the input
			$(this).attr('disabled', true);
		});
		$('input[name=c_name]').attr('disabled', false);
		$('#c_autocomplete').show();
		setConfigValue();
	}
});

function setBuildinValue() {
	$('input[name=c_name]').val('<?php echo $buildin['c_name']; ?>');
	$('input[name=c_id]').val('0');
	$('select[name=c_group_id]').val('1');
	$('select[name=c_group_id]').trigger('change');
	$('input[name=c_firstname]').val('<?php echo $buildin['c_firstname']; ?>');
	$('input[name=c_lastname]').val('<?php echo $buildin['c_lastname']; ?>');
	$('input[name=c_email]').val('<?php echo $buildin['c_email']; ?>');
	$('input[name=c_telephone]').val('<?php echo $buildin['c_telephone']; ?>');
	$('input[name=c_fax]').val('<?php echo $buildin['c_fax']; ?>');
	$('select[name=a_country_id]').val('<?php echo $buildin['a_country_id']; ?>');
	$('input[name=a_firstname]').val('<?php echo $buildin['a_firstname']; ?>');
	$('input[name=a_lastname]').val('<?php echo $buildin['a_lastname']; ?>');
	$('input[name=a_address_1]').val('<?php echo $buildin['a_address_1']; ?>');
	$('input[name=a_address_2]').val('<?php echo $buildin['a_address_2']; ?>');
	$('input[name=a_city]').val('<?php echo $buildin['a_city']; ?>');
	$('input[name=a_postcode]').val('<?php echo $buildin['a_postcode']; ?>');
	$('select[name=a_country_id]').attr('onchange', 'country(\'<?php echo $buildin['a_zone_id']; ?>\')');
	$('select[name=a_country_id]').trigger('change');
};

function setConfigValue() {
	$('input[name=c_id]').val('<?php echo $c_id; ?>');
	$('select[name=c_group_id]').val('<?php echo $c_group_id; ?>');
	if ($('input[name=c_type]:checked').val() == '2') {
		$('input[name=c_id]').val('0');
	}
	$('select[name=c_group_id]').trigger('change');
	$('input[name=c_name]').val('<?php echo $c_name; ?>');
	$('input[name=c_firstname]').val('<?php echo $c_firstname; ?>');
	$('input[name=c_lastname]').val('<?php echo $c_lastname; ?>');
	$('input[name=c_email]').val('<?php echo $c_email; ?>');
	$('input[name=c_telephone]').val('<?php echo $c_telephone; ?>');
	$('input[name=c_fax]').val('<?php echo $c_fax; ?>');
	$('select[name=a_country_id]').val('<?php echo $a_country_id; ?>');
	$('input[name=a_firstname]').val('<?php echo $a_firstname; ?>');
	$('input[name=a_lastname]').val('<?php echo $a_lastname; ?>');
	$('input[name=a_address_1]').val('<?php echo $a_address_1; ?>');
	$('input[name=a_address_2]').val('<?php echo $a_address_2; ?>');
	$('input[name=a_city]').val('<?php echo $a_city; ?>');
	$('input[name=a_postcode]').val('<?php echo $a_postcode; ?>');
	$('select[name=a_country_id]').attr('onchange', 'country(\'<?php echo $a_zone_id; ?>\')');
	$('select[name=a_country_id]').trigger('change');
};

$(document).on('focus', 'input[name=c_name]', function(){
	$(this).autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {	
					response($.map(json, function(item) {
						return {
							category: item['customer_group'],
							label: item['name'],
							value: item['customer_id'],
							customer_group_id: item['customer_group_id'],
							firstname: item['firstname'],
							lastname: item['lastname'],
							email: item['email'],
							telephone: item['telephone'],
							fax: item['fax'],
							address: item['address']
						}
					}));
				}
			});
		}, 
		select: function(event, ui) { 
			$('input[name=c_name]').val(ui.item['label']);
			$('input[name=c_id]').val(ui.item['value']);
			$('select[name=c_group_id]').val(ui.item['customer_group_id']);
			$('select[name=c_group_id]').trigger('change');
			$('input[name=c_firstname]').val(ui.item['firstname']);
			$('input[name=c_lastname]').val(ui.item['lastname']);
			$('input[name=c_email]').val(ui.item['email']);
			$('input[name=c_telephone]').val(ui.item['telephone']);
			$('input[name=c_fax]').val(ui.item['fax']);
			
			for (i in ui.item['address']) {
				$('select[name=a_country_id]').val(ui.item['address'][i]['country_id']);
				$('input[name=a_firstname]').val(ui.item['address'][i]['firstname']);
				$('input[name=a_lastname]').val(ui.item['address'][i]['lastname']);
				$('input[name=a_address_1]').val(ui.item['address'][i]['address_1']);
				$('input[name=a_address_2]').val(ui.item['address'][i]['address_2']);
				$('input[name=a_city]').val(ui.item['address'][i]['city']);
				$('input[name=a_postcode]').val(ui.item['address'][i]['postcode']);
				$('select[name=a_country_id]').attr('onchange', 'country(\'' + ui.item['address'][i]['zone_id'] + '\')');
				$('select[name=a_country_id]').trigger('change');
				break;
			}

			return false; 
		},
		focus: function(event, ui) {
			return false;
		}
	});
});
$('input[name=c_type]').trigger('change');
$('select[name=a_country_id]').trigger('change');
// add for Default Customer end
// add for Quotation begin
function addQuoteStatus() {
	var newStatus = $('input[name=quote_status_name]').val();
	if (validateQuoteStatus(newStatus)) {
		var warning_tips = '<img src="view/image/pos/warning.png" id="status_warning_tips" alt="<?php echo $text_quote_status_already_exist; ?>" title="<?php echo $text_quote_status_already_exist; ?>" />';
		$('#status_warning_tips').remove();
		$(warning_tips).insertAfter($('input[name=quote_status_name]'));
		return false;
	}
	$('#status_warning_tips').remove();
	var data = {'status' : newStatus};
	$.ajax({
		url: 'index.php?route=module/pos/addQuoteStatus&token=<?php echo $token; ?>',
		type: 'post',
		dataType: 'json',
		data: data,
		beforeSend: function() {
			$('#button_add_quote_status').hide();
			$('#button_add_quote_status').before('<img src="view/image/pos/loading.gif" class="loading" style="padding-left: 5px;" />');	
		},
		complete: function() {
			$('.loading').remove();
			$('#button_add_quote_status').show();
		},
		success: function(json) {
			if (json['error']) {
				var error_tips = '<img src="view/image/warning.png" id="status_warning_tips" alt="' + json['error'] + '" title="' + json['error'] +'" />';
				$('#status_warning_tips').remove();
				$(error_tips).insertAfter($('input[name=quote_status_name]'));
			} else {
				// add to the list
				var new_quote_status_html = '<tr><td class="text-left"><span>' + newStatus + '</span><input type="hidden" name="quote_status_id_' + json['quote_status_id'] + '" value="' + json['quote_status_id'] + '" /></td><td class="text-right"><a class="btn btn-success btn-sm" onclick="renameQuoteStatus(this);"><i class="fa fa-edit fa-lg"></i> <?php echo $button_rename; ?></a>&nbsp;<a class="btn btn-danger btn-sm" onclick="deleteQuoteStatus(this);"><i class="fa fa-trash-o fa-lg"></i> <?php echo $button_delete; ?></a></td></tr>';
				$(new_quote_status_html).insertAfter($('#quote_status_tr'));
				$('input[name=quote_status_name]').val('');
			}
		}
	});		
};

function validateQuoteStatus(status) {
	retValue = 0;
	var newValue = status.toLowerCase();
	$("#quote_status_list tr").each(function(){
		var value = $(this).find('span').text().toLowerCase();
		if (newValue == value) {
			retValue = 1;
		}
	});
	return retValue;
};

function renameQuoteStatus(anchor) {
	// rename the quote status
	var curValue = $(anchor).closest('tr').find('span').text();
	var newValue = prompt('<?php echo $text_rename; ?>', curValue);
	if (curValue == newValue ) {
		return false;
	}
	if (validateQuoteStatus(newValue)) {
		var warning_tips = '<img src="view/image/warning.png" id="status_warning_tips" alt="<?php echo $text_quote_status_already_exist; ?>" title="<?php echo $text_quote_status_already_exist; ?>" />';
		$('#status_warning_tips').remove();
		$(warning_tips).insertAfter($('input[name=quote_status_name]'));
		return false;
	}
	$('#status_warning_tips').remove();
	
	var quote_status_id = $(anchor).closest('tr').find('input').val();
	var data = {'status' : newValue, 'status_id' : quote_status_id};
	$.ajax({
		url: 'index.php?route=module/pos/renameQuoteStatus&token=<?php echo $token; ?>',
		type: 'post',
		dataType: 'json',
		data: data,
		beforeSend: function() {
			$(anchor).hide();
			$(anchor).before('<img src="view/image/pos/loading.gif" class="loading" style="padding-left: 5px;" />');	
		},
		complete: function() {
			$('.loading').remove();
			$(anchor).show();
		},
		success: function(json) {
			if (json['error']) {
				alert(json['error']);
			} else {
				// add to the list
				$(anchor).closest('tr').find('span').text(newValue);
			}
		}
	});
};

function deleteQuoteStatus(anchor) {
	// delete the quote status
	var quote_status_id = $(anchor).closest('tr').find('input').val();
	var data = {'status_id' : quote_status_id};
	$.ajax({
		url: 'index.php?route=module/pos/deleteQuoteStatus&token=<?php echo $token; ?>',
		type: 'post',
		dataType: 'json',
		data: data,
		beforeSend: function() {
			$(anchor).hide();
			$(anchor).before('<img src="view/image/pos/loading.gif" class="loading" style="padding-left: 5px;" />');	
		},
		complete: function() {
			$('.loading').remove();
			$(anchor).show();
		},
		success: function(json) {
			if (json['error']) {
				alert(json['error']);
			} else {
				// add to the list
				$(anchor).closest('tr').remove();
			}
		}
	});
};

function addStatusOnEnter(e) {
	var key;
	if (window.event)
		key = window.event.keyCode; //IE
	else
		key = e.which; //Firefox & others

	if(key == 13) {
		addQuoteStatus();
		return false;
	}
}

// add for Quotation end
// add for Cash type begin
function addCashType() {
	// add the current values into the list
	var rowId = $('#cash_type_list tr').length - 1;
	var html = '<tr id="cash_type-' + rowId + '">';
	html += '<td class="text-left">' + $('select[name=cash_type]').val();
	html += '<input type="hidden" name="cash_types[' + rowId + '][type]" value="' + $('select[name=cash_type]').val() + '" /></td>';
	html += '<td class="text-left"><img src="<?php echo HTTP_CATALOG; ?>/image/' + $('input[name=cash_image_path]').val() + '" style="max-width: 360px; max-height: 200px; width: auto; height: auto;" />';
	html += '<input type="hidden" name="cash_types[' + rowId + '][image]" value="<?php echo HTTP_CATALOG; ?>/image/' + $('input[name=cash_image_path]').val() + '" /></td>';
	html += '<td class="text-right">' + $('input[name=cash_value]').val() + '<input type="hidden" name="cash_types[' + rowId + '][value]" value="' + $('input[name=cash_value]').val() + '" /></td>';
	html += '<td class="text-left" id="cash_display_' + $('input[name=cash_value]').val() + '"><span>' + $('input[name=cash_display]').val() + '</span><input type="hidden" name="cash_types[' + rowId + '][display]" value="' + $('input[name=cash_display]').val() + '" /></td>';
	html += '<td align="center"><a class="btn btn-danger btn-sm" onclick="deleteCashType(\'cash_type-' + rowId + '\');"><i class="fa fa-minus-circle fa-lg"></a></td>';
	html += '</tr>';
	$('#cash_type_list').append(html);
	// clear the current value row
	$('#cash_image').find('img').attr('src', '');
	$('#cash_image_path').val('');
	$('input[name=cash_value]').val('');
	$('input[name=cash_display]').val('');
};

function deleteCashType(rowId) {
	$('#'+rowId).remove();
};
// add for Cash type end

$(document).ready(function() {
	$('.time').datetimepicker({
		pickDate: false
	});

	<?php
		foreach ($more_settings as $more_setting) {
			if (!${'enable_settings_'.$more_setting}) {
	?>
				$('#tab_enable_settings_<?php echo $more_setting; ?>').hide();
	<?php
			}
		}
	?>
	
	$('#sales_report_custom_field_dialog').dialog({
		autoOpen: false,
		height: 230,
		width: 760,
		modal: true,
		buttons: {}
	});
});

function setTableNumber() {
	// check the number of tables against the existing number of tables
	var curNumber = $('#table_list tr').length - 2;
	var newNumber = parseInt($('input[name=table_number]').val());
	if (newNumber < curNumber) {
		var table_ids = new Array();
		$('#table_list tr:gt(' + (newNumber+1) + ')').each(function() {
			table_ids.push($(this).find('input[type=hidden]').val());
			$(this).remove();
		});
		var table_ids_str = table_ids.join(',');
		var data = {'table_ids':table_ids_str};
		$.ajax({
			url: 'index.php?route=module/pos/deleteTableBatch&token=<?php echo $token; ?>',
			type: 'post',
			dataType: 'json',
			data: data,
			beforeSend: function() {
				$('#button_set_number').hide();
				$('#button_set_number').before('<img src="view/image/pos/loading.gif" class="loading" style="padding-left: 5px;" />');	
			},
			complete: function() {
				$('.loading').remove();
				$('#button_set_number').show();
			},
			success: function(json) {
				if (json['error']) {
					alert(json['error']);
				}
			}
		});
	} else if (newNumber > curNumber) {
		var startNum = curNumber + 1;
		var data = {'startNum':startNum, 'total':(newNumber-curNumber)};
		$.ajax({
			url: 'index.php?route=module/pos/addTableBatch&token=<?php echo $token; ?>',
			type: 'post',
			dataType: 'json',
			data: data,
			beforeSend: function() {
				$('#button_set_number').hide();
				$('#button_set_number').before('<img src="view/image/pos/loading.gif" class="loading" style="padding-left: 5px;" />');	
			},
			complete: function() {
				$('.loading').remove();
				$('#button_set_number').show();
			},
			success: function(json) {
				if (json['error']) {
					alert(json['error']);
				} else if (json['table_ids']) {
					for (var i in json['table_ids']) {
						var trHtml = '<tr><td class="text-left"><input type="text" name="name_' + json['table_ids'][i]['table_id'] + '" value="' + json['table_ids'][i]['name'] + '" class="form-control"/></td><td class="text-left"><input type="text" name="desc_' + json['table_ids'][i]['table_id'] + '" value="" class="form-control"/></td>';
						trHtml    += '<td class="text-left"><input type="hidden" value="' + json['table_ids'][i]['table_id'] + '" /><a onclick="modifyTable(this);" class="btn btn-info btn-sm"><i class="fa fa-save fa-lg"></i> <?php echo $button_table_modify; ?></a>&nbsp;&nbsp;<a onclick="removeTable(this);" class="btn btn-danger btn-sm"><i class="fa fa-trash-o fa-lg"></i> <?php echo $button_table_remove; ?></a></td></tr>';
						$('#table_list').append(trHtml);
					}
				}
			}
		});
	}
};

function modifyTable(anchor) {
	var table_id = $(anchor).closest('td').find('input').val();
	var name = $(anchor).closest('tr').find('input[name^=name]').val();
	var desc = $(anchor).closest('tr').find('input[name^=desc]').val();
	var data = {'index':table_id, 'name':name, 'desc':desc, 'coors':''};
	$.ajax({
		url: 'index.php?route=module/pos/addTable&token=<?php echo $token; ?>',
		type: 'post',
		dataType: 'json',
		data: data,
		beforeSend: function() {
			$(anchor).hide();
			$(anchor).before('<img src="view/image/pos/loading.gif" class="loading" style="padding-left: 5px;" />');	
		},
		complete: function() {
			$('.loading').remove();
			$(anchor).show();
		},
		success: function(json) {
			if (json['error']) {
				alert(json['error']);
			}
		}
	});
};

function removeTable(anchor) {
	var table_id = $(anchor).closest('td').find('input').val();
	var data = {'index':table_id};

	$.ajax({
		url: 'index.php?route=module/pos/deleteTable&token=<?php echo $token; ?>',
		type: 'post',
		dataType: 'json',
		data: data,
		beforeSend: function() {
			$(anchor).hide();
			$(anchor).before('<img src="view/image/pos/loading.gif" class="loading" style="padding-left: 5px;" />');	
		},
		complete: function() {
			$('.loading').remove();
			$(anchor).show();
		},
		success: function(json) {
			if (json['error']) {
				alert(json['error']);
			} else {
				$(anchor).closest('tr').remove();
			}
		}
	});
};
// add for table management begin
// add for till control begin
function testTillControl() {
	<?php if (isset($enable_till_control) && $enable_till_control) { ?>
	var applet = document.jzebra;
	if (applet) {
		var key = $('input[name=till_control_key]').val();
		applet.append(eval("'" + key + "'"));
		applet.print();
	}
	<?php } ?>
};
// add for till control end

// add for label print begin
function print_label() {
	var length = $('#label_product_add_tr').closest('tbody').find('tr').length;
	if (length == 1) {
		alert('<?php echo $text_label_add_product_before_print; ?>');
	} else {
		var data = [];
		var post_data = {};
		for (var i = 0; i < length-1; i++) {
			var curTr = $('#label_product_add_tr').closest('tbody').find('tr').eq(i);
			data.push({'product_id': curTr.find('input').val(), 'quantity': curTr.find('td').eq(1).text()});
			post_data['products[' + i + ']'] = curTr.find('input').val();
		}
		// get the print data from url
		var url = 'index.php?route=module/pos/getLabelProductDetails&token=<?php echo $token; ?>';
		
		var msg_dialog = $('<div id="msg_dialog" title="<?php echo $print_wait_message; ?>"><p><?php echo $print_wait_message; ?></p></div>');
		$.ajax({
			url: url,
			type: 'post',
			data: post_data,
			dataType: 'json',
			beforeSend: function() {
				msg_dialog.dialog({height: 100,modal: true});
			},
			complete: function() {
				msg_dialog.dialog('close');
			},
			success: function(json) {
				var index = $('select[name=label_print_templates]').val();
				if (label_templates[index]) {
					var template = label_templates[index];
					
					if (template['content']) {
						var label_adjust_width = parseFloat($('input[name=label_adjust_width]').val());
						var label_adjust_height = parseFloat($('input[name=label_adjust_height]').val());

						var totalLabels = 0;
						for (var i = 0; i < data.length; i++) {
							totalLabels += parseInt(data[i]['quantity']);
						}
						
						var label_side_margin = parseInt(template['side_margin']);
						var label_top_margin = parseInt(template['top_margin']);
						var label_number_across = parseInt(template['number_across']);
						var label_number_down = parseInt(template['number_down']);
						var label_horizontal_pitch = parseInt(template['horizontal_pitch']);
						var label_vertical_pitch = parseInt(template['vertical_pitch']);
						var label_width = parseInt(template['width']) * label_adjust_width;
						var label_height = parseInt(template['height']) * label_adjust_height;
						
						var totalLabelPerPage = label_number_down * label_number_across;
						var totalPage = (totalLabels % totalLabelPerPage == 0) ? parseInt(totalLabels / totalLabelPerPage) : (parseInt(totalLabels / totalLabelPerPage) + 1);
						console.log(totalLabelPerPage + ',' + totalPage);
						
						var html = '<style>' + $('#label_print_style').text() + '</style>';
						var items = JSON.parse($("<div/>").html(template['content']).text());
						
						for (var index_page = 0; index_page < totalPage; index_page ++) {
							html += '<div style="position: relative; left: ' + (label_adjust_width * label_side_margin) + 'mm; top: ' + (label_adjust_height * label_top_margin) + 'mm; width: ' + (label_adjust_width * label_horizontal_pitch * label_number_across) + 'mm; height: ' + (label_adjust_height * label_vertical_pitch * label_number_down) + 'mm; overflow: hidden;">';
							for (var index_row = 0; index_row < label_number_down; index_row++) {
								for (var index_column = 0; index_column < label_number_across; index_column++) {
									var index_item = index_page * totalLabelPerPage + index_row * label_number_across + index_column;
									if (index_item < totalLabels) {
										html += '<div style="display: table; table-layout: fixed; text-align: center; position: absolute; left: ' + (label_adjust_width * index_column * label_horizontal_pitch) + 'mm; top: ' + (label_adjust_height * index_row * label_vertical_pitch) + 'mm; width: ' + (label_adjust_width * label_width) + 'mm; height: ' + (label_adjust_height * label_height) + 'mm; overflow: hidden;">';
										var start = 0, end = -1;
										for (var product_index in data) {
											var product = data[product_index];
											start = end + 1;
											end = start + parseInt(product['quantity']) - 1;
											product['found'] = false;
											for (var json_index in json) {
												if (parseInt(json[json_index]['product_id']) == parseInt(product['product_id'])) {
													product['found'] = true;
													for (var para in json[json_index]) {
														product[para] = json[json_index][para];
													}
												}
											}
											if (product['found'] && index_item >= start && index_item <= end) {
												html += '<div style="position: relative; height: 100%; width: 100%; ?>; overflow: hidden;">';
												for (var i = 0; i < items.length; i++) {
													var item = items[i];
													var left = parseFloat(item['left']) * label_width;
													var top = parseFloat(item['top']) * label_height;
													var width = parseFloat(item['width']) * label_width;
													var height = parseFloat(item['height']) * label_height;
													var background = item['background'] ? item['background'] : 'transparent';
													html += '<div style="position: absolute; top: ' + top + 'mm; left: ' + left + 'mm; width: ' + width + 'mm; height: ' + height + 'mm; background-color: ' + background + '; -webkit-print-color-adjust: exact;">';
													if (item['barcode'] && parseInt(item['barcode'])) {
														var text = '';
														if (item['id'] == 'product_sku' || item['id'] == 'product_upc' || item['id'] == 'product_ean' || item['id'] == 'product_mpn') {
															text = product[item['id'].substring('product_'.length)];
														}
														html += '<span class="label-text-wrapper"><div style="display: table-cell; vertical-align: middle;"><input type="hidden" value="' + item['barcodeType'] + '" /><div style="display:inline-block;" id="barcode_' + i + '">' + text + '</div></div></span>';
													} else if (item['img']) {
														html += '<img src="' + item['img'] + '" style="height: auto; max-width: 100%;" />';
													} else {
														var text = item['text'];
														if (item['id'].indexOf('product_') === 0) {
															text = product[item['id'].substring('product_'.length)];
														}
														if (item['id'] == 'product_image') {
															html += '<img src="' + text + '" style="height: auto; max-width: 100%;"/>';
														} else {
															var style = '';
															for (var para in item['style']) {
																style += para + ':' + item['style'][para] + ';';
															}
															html += '<span class="label-text-wrapper"><p style="' + style + '">' + text + '</p></span>';
														}
													}
													html += '</div>';
												}
												html += '</div>';
												break;
											}
										}
										html += '</div>';
									}
								}
							}
							html += '</div>';
							if (index_page < totalPage - 1) {
								html += '<p><div style="page-break-after:always;" clear="all"></div></p>';
							}
						}
						// send html to iframe for printing
						$('#print_iframe').contents().find('html').html(html);

						setTimeout(function() {
							$("#print_iframe").contents().find("div[id^='barcode']").each(function() {
								var value = $(this).text();
								$(this).text('');
								var barcode_text = value;
								var barcodeType = $(this).parent().find('input').val();
								$(this).barcode(barcode_text, barcodeType, {barHeight: 20});
							});
							
							// append the print script
							if (navigator.appName == 'Microsoft Internet Explorer') {
								$("#print_iframe").get(0).contentWindow.document.execCommand('print', false, null);
							} else {
								$("#print_iframe").get(0).contentWindow.print();
							}
						}, 1000);
					}
				}
			}
		});
	}
};

function addToPrint() {
	var product_id = parseInt($('input[name=label_product_id]').val());
	var quantity = parseInt($('input[name=label_quantity]').val());
	if (isNaN(product_id) || product_id == 0) {
		alert('<?php echo $text_label_product_name_not_valid; ?>');
	} else if (isNaN(quantity) || quantity == 0) {
		alert('<?php echo $text_label_quantity_not_valid; ?>');
	} else {
		var html = '<tr>'
		html += '<td colspan="2" class="text-left">' + $('input[name=label_product]').val() + '<input type="hidden" value="' + product_id + '" /></td>';
		html += '<td colspan="2" class="text-left">' + quantity + '</td>';
		html += '<td class="text-left"><a class="btn btn-danger" onclick="$(this).closest(\'tr\').remove();"><i class="fa fa-trash fa-lg"></i> </a></td>';
		html += '</tr>';
		$('#label_product_add_tr').before(html);
		$('input[name=label_product]').val('');
		$('input[name=label_product_id]').val(0);
		$('input[name=label_quantity]').val(1);
	}
};

$('input[name=\'label_product\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.product_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'label_product\']').val(ui.item.label);
		$('input[name=\'label_product_id\']').val(ui.item.value);

		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});

// for the label templating
$('#label_template_table').droppable({
	accept: '.draggable',
	drop: function(event, ui) {
		var offset = $('#label_template_table').position();
		var left = event.pageX - offset.left - 75;
		var top = event.pageY - offset.top - 20;
		left = parseInt(left/10) * 10 + (left%10 >= 5 ? 10 : 0);
		top = parseInt(top/10) * 10 + (top%10 >= 5 ? 10 : 0);
		
		// handle the drop for different type of element
		var clone = $(ui.draggable).clone();
		handleDrag(clone, function(clone) {
			$('#label_template_table').append(clone);
			$(clone).addClass('clone').removeClass('draggable ui-draggable');
			$(clone).css({position: 'absolute', top: top, left: left, width: '150px', height: '40px'});
			$(clone).draggable({grid: [10,10], containment: $('#label_template_table')});
			$(clone).resizable({containment: $('#label_template_table')});
		});
	}
});
$('.draggable').draggable({helper: 'clone', containment: $('#drag')});

function handleDrag(el, callback) {
	// the element to be populated with values
	var id = $(el).attr('id');
	if (id == 'label_text') {
		var text = prompt('<?php echo $entry_label_text; ?>', '');
		if (text != null) {
			$(el).html('<span class="label-text-wrapper"><p class="label-text">' + text + '</p><span class="close-button" onclick="$(this).closest(\'div\').remove();">X</span></span>');
			callback(el);
		}
	} else if (id == 'label_logo') {
		// call the image loader
		$('#label_image').val('');
		image_upload('label_image', '', false, function() {
			// check if the value is set properly
			var imgPath = $('#label_image').val();
			if (imgPath) {
				$(el).html('<span class="label-text-wrapper"><img src="' + imgPath + '" style="max-width: 100%; max-heigth: 100%;"/><span class="close-button" onclick="$(this).closest(\'div\').remove();">X</span></span>');
				callback(el);
			}
		});
	} else if (id == 'product_name' || id == 'product_description' || id == 'product_manufacturer' || id == 'product_model' || id == 'product_price' || id == 'product_image') {
		$(el).html('<span class="label-text-wrapper"><p class="label-text">' + $(el).text() + '</p><span class="close-button" onclick="$(this).closest(\'div\').remove();">X</span></span>');
		callback(el);
	} else if (id == 'product_sku' || id == 'product_upc' || id == 'product_ean' || id == 'product_mpn') {
		var html = '<div id="template_barcode_dialog" title="<?php echo $text_barcode_selection; ?>">';
		html += '<div style="margin-top: 10px;"><table class="table">';
		html += '<tr><td class="text-right" style="width: 20px;"><input name="require_barcode" type="checkbox" value="0" class="form-control" onclick="if ($(this).is(\':checked\')) { $(this).val(1); } else { $(this).val(0); }" /></td>';
		html += '<td class="text-right"><?php echo $text_use_barcode; ?></td>';
		html += '<td class="text-left">';
		html += '<select name="barcode_type" class="form-control" id="template_barcode_type">';
		<?php foreach ($label_barcode_types as $barcode_type) { ?>
			<?php if ($barcode_type == 'ean13') { ?>
			html += '<option value="<?php echo $barcode_type; ?>" selected="selected"><?php echo $barcode_type; ?></option>';
			<?php } else {?>
			html += '<option value="<?php echo $barcode_type; ?>"><?php echo $barcode_type; ?></option>';
			<?php } ?>
		<?php }?>
		html += '</select></td></tr></table></div></div>';
		$(html).dialog({
			modal: true,
			width: 400,
			height: 200,
			buttons: {
				OK: function() {
					if (parseInt($('#template_barcode_dialog input[name=require_barcode]').val()) > 0) {
						var barcodeType = $('#template_barcode_dialog #template_barcode_type').val();
						$(el).html('<span class="label-text-wrapper"><div style="display: table-cell; vertical-align: middle;"><input type="hidden" value="' + barcodeType + '" /><div style="display:inline-block;"></div></div><span class="close-button" onclick="$(this).closest(\'div\').remove();">X</span></span>');
						$(el).find('div div').barcode('1234567890123', barcodeType, {barHeight: 20});
					} else {
						var text = '{' + id.substring(id.length-3, id.length).toUpperCase() + '}';
						$(el).html('<span class="label-text-wrapper"><p class="label-text">' + text + '</p><span class="close-button" onclick="$(this).closest(\'div\').remove();">X</span></span>');
					}
					callback(el);
					$('#template_barcode_dialog').dialog('close');
					$('#template_barcode_dialog').dialog('destroy');
				}
			}
		});
	}
};

$('#label_template_table').click(function(event) {
	// find the top element before '#label_template_table'
	var foundClone = false;
	if ($(event.target).hasClass('clone') || $(event.target).parents('.clone').length) {
		var clone = $(event.target);
		if (!clone.hasClass('clone')) {
			clone = $(event.target).parents('.clone')[0];
		}
		if ($(clone).hasClass('item-selected')) {
			$(clone).removeClass('item-selected');
		} else {
			$(clone).addClass('item-selected');
		}
		foundClone = true;
	}
	
	if (!foundClone) {
		$('.clone').removeClass('item-selected');
	}
});

function setBackground(color) {
	// set the selected background color of the selected div to color
	$('.clone').each(function() {
		if ($(this).hasClass('item-selected')) {
			$(this).css('background-color', color);
		}
	});
};

function setTextEffect(effect) {
	$('.clone').each(function() {
		if ($(this).hasClass('item-selected') && $(this).find('.label-text-wrapper').find('.label-text').length > 0) {
			var effects = {};
			if (effect == 'b') {
				if ($(this).find('.label-text-wrapper').find('.label-text').css('font-weight') == 'bold') {
					effects['font-weight'] = 'normal';
				} else {
					effects['font-weight'] = 'bold';
				}
			}
			if (effect == 'i') {
				if ($(this).find('.label-text-wrapper').find('.label-text').css('font-style') == 'italic') {
					effects['font-style'] = 'normal';
				} else {
					effects['font-style'] = 'italic';
				}
			}
			if (effect == 'u') {
				if ($(this).find('.label-text-wrapper').find('.label-text').css('text-decoration') == 'underline') {
					effects['text-decoration'] = 'none';
				} else {
					effects['text-decoration'] = 'underline';
				}
			}
			if (effect == 's') {
				if ($(this).find('.label-text-wrapper').find('.label-text').css('text-decoration') == 'line-through') {
					effects['text-decoration'] = 'none';
				} else {
					effects['text-decoration'] = 'line-through';
				}
			}
			if (effect == '-') {
				var curSize = $(this).find('.label-text-wrapper').find('.label-text').css('font-size');
				var size = curSize.substring(0, curSize.length-2);
				var unit = curSize.substring(curSize.length-2);
				effects['font-size'] = (parseInt(size) - 2) + unit;
			}
			if (effect == '+') {
				var curSize = $(this).find('.label-text-wrapper').find('.label-text').css('font-size');
				var size = curSize.substring(0, curSize.length-2);
				var unit = curSize.substring(curSize.length-2);
				effects['font-size'] = (parseInt(size) + 2) + unit;
			}
			if (effect == 'l') {
				effects['text-align'] = 'left';
			} else if (effect == 'c') {
				effects['text-align'] = 'center';
			} else if (effect == 'r') {
				effects['text-align'] = 'right';
			}
			
			$(this).find('.label-text-wrapper').find('.label-text').css(effects);
		}
	});
};

var label_templates = <?php echo (!empty($label_templates)) ? json_encode($label_templates, JSON_FORCE_OBJECT) : json_encode(array(), JSON_FORCE_OBJECT) ; ?>;
label_templates['new'] = <?php echo json_encode($label_default_template) ; ?>;
var label_demos = <?php echo json_encode($label_demos) ; ?>;

function viewTemplate(index) {
	if (label_templates[index]) {
		var template = label_templates[index];
		$('input[name=label_template_id]').val(template['label_template_id']);
		$('input[name=label_template_name]').val(template['name']);
		$('input[name=label_top_margin]').val(template['top_margin']);
		$('input[name=label_side_margin]').val(template['side_margin']);
		$('input[name=label_height]').val(template['height']);
		$('input[name=label_width]').val(template['width']);
		$('input[name=label_vertical_pitch]').val(template['vertical_pitch']);
		$('input[name=label_horizontal_pitch]').val(template['horizontal_pitch']);
		$('input[name=label_number_across]').val(template['number_across']);
		$('input[name=label_number_down]').val(template['number_down']);
		$('#template_general input[type=text]').each(function() {
			$(this).prop('disabled', 'true');
		});
		// redraw the label viewer canvas
		var label_adjust_width = parseFloat($('input[name=label_adjust_width]').val());
		var label_adjust_height = parseFloat($('input[name=label_adjust_height]').val());
		var label_width = parseInt(template['width']) * label_adjust_width;
		var label_height = parseInt(template['height']) * label_adjust_height;
		$('#label_viewer_canvas').css({'width': label_width+'mm', 'height': label_height+'mm'});
		$('#label_viewer_canvas').empty();
		if (template['content']) {
			var items = JSON.parse($("<div/>").html(template['content']).text());
			var html = '';
			for (var i = 0; i < items.length; i++) {
				var item = items[i];
				var left = parseFloat(item['left']) * label_width;
				var top = parseFloat(item['top']) * label_height;
				var width = parseFloat(item['width']) * label_width;
				var height = parseFloat(item['height']) * label_height;
				var background = item['background'] ? item['background'] : 'transparent';
				html += '<div style="position: absolute; top: ' + top + 'mm; left: ' + left + 'mm; width: ' + width + 'mm; height: ' + height + 'mm; background-color: ' + background + ';">';
				if (item['barcode'] && parseInt(item['barcode'])) {
					html += '<span class="label-text-wrapper"><div style="display: table-cell; vertical-align: middle;"><input type="hidden" value="' + item['barcodeType'] + '" /><div style="display:inline-block;" id="barcode_' + i + '"></div></div></span>';
				} else if (item['img']) {
					html += '<img src="' + item['img'] + '" style="height: auto; max-width: 100%;" />';
				} else {
					var text = item['text'];
					if (label_demos[item['id']]) {
						text = label_demos[item['id']];
					}
					if (item['id'] == 'product_image') {
						html += '<img src="' + text + '" style="height: auto; max-width: 100%;"/>';
					} else {
						var style = '';
						for (var para in item['style']) {
							style += para + ':' + item['style'][para] + ';';
						}
						html += '<span class="label-text-wrapper"><p style="' + style + '">' + text + '</p></span>';
					}
				}
				html += '</div>';
			}
			$('#label_viewer_canvas').html(html);
			$('#label_viewer_canvas div[id^=barcode_]').each(function() {
				var barcodeType = $(this).parent().find('input').val();
				$(this).barcode('1234567890123', barcodeType, {barHeight: 20});
				$(this).find('div:last').css({'margin-top': '0', 'height': '12px', 'line-height': '12px'});
			});
		}
		
		$('#template_general').show();
		// show the viewer and hide the editor
		$('#label_viewer').show();
		$('#label_editor').hide();
		// show edit button and hide save button
		$('#label_save').hide();
		$('#view_buttons').show();
	}
};

$('select[name=label_templates]').change(function() {
	var index = $('select[name=label_templates]').val();
	if (index == 'new') {
		$('input[name=label_template_id]').val('0');
		viewTemplate(index);
		editTemplate(index);
	} else if (index == '') {
		$('#template_general').hide();
		$('#label_viewer').hide();
		$('#label_editor').hide();
		$('#label_save').hide();
		$('#view_buttons').hide();
	} else {
		viewTemplate(index);
	}
});

function editTemplate(index) {
	if (label_templates[index]) {
		if (index != 'new') {
			$('input[name=label_template_id]').val(label_templates[index]['label_template_id']);
		}
		$('#template_general input[type=text]').each(function() {
			$(this).prop('disabled', false);
		});
		var template = label_templates[index];
		// redraw the label editor canvas
		$('#label_template_table').empty();
		if (template['content']) {
			var items = JSON.parse($("<div/>").html(template['content']).text());

			var label_canvas_width = 381, label_canvas_height = 254;
			var label_width = parseInt(template['width']);
			var label_height = parseInt(template['height']);
			if (label_canvas_width * label_height / label_width > label_canvas_height) {
				label_canvas_width = parseInt(label_canvas_height * label_width / label_height);
			} else if (label_canvas_height * label_width / label_height > label_canvas_width) {
				label_canvas_height = parseInt(label_canvas_width * label_height / label_width);
			}
			$('#label_template_table').css({'width': label_canvas_width+'px', 'height': label_canvas_height+'px'});
			
			var html = '';
			for (var i = 0; i < items.length; i++) {
				var item = items[i];
				var left = parseInt(parseFloat(item['left']) * label_canvas_width);
				var top = parseInt(parseFloat(item['top']) * label_canvas_height);
				var width = parseInt(parseFloat(item['width']) * label_canvas_width);
				var height = parseInt(parseFloat(item['height']) * label_canvas_height);
				var background = item['background'] ? item['background'] : 'transparent';
				html += '<div class="clone ui-draggable ui-resizable" id="' + item['id'] + '" style="position: absolute; top: ' + top + 'px; left: ' + left + 'px; width: ' + width + 'px; height: ' + height + 'px; background-color: ' + background + ';">';
				if (item['barcode'] && parseInt(item['barcode'])) {
					html += '<span class="label-text-wrapper"><div style="display: table-cell; vertical-align: middle;"><input type="hidden" value="' + item['barcodeType'] + '" /><div style="display:inline-block;" id="barcode_' + i + '"></div></div><span class="close-button" onclick="$(this).closest(\'div\').remove();">X</span></span>';
				} else if (item['img']) {
					html += '<span class="label-text-wrapper"><img src="' + item['img'] + '" style="height: auto; max-width: 100%;" /><span class="close-button" onclick="$(this).closest(\'div\').remove();">X</span></span>';
				} else {
					var style = '';
					for (var para in item['style']) {
						style += para + ':' + item['style'][para] + ';';
					}
					html += '<span class="label-text-wrapper"><p class="label-text" style="' + style + '">' + item['text'] + '</p><span class="close-button" onclick="$(this).closest(\'div\').remove();">X</span></span>';
				}
				html += '</div>';
			}
			$('#label_template_table').html(html);
			$('#label_template_table div').each(function() {
				if ($(this).hasClass('clone')) {
					$(this).draggable({grid: [10,10], containment: $('#label_template_table')});
					$(this).resizable({containment: $('#label_template_table')});
					$(this).selectable();
				}
			});
			$('#label_template_table div[id^=barcode_]').each(function() {
				var barcodeType = $(this).parent().find('input').val();
				$(this).barcode('1234567890123', barcodeType, {barHeight: 20});
				$(this).find('div:last').css({'margin-top': '0', 'height': '12px', 'line-height': '12px'});
			});
		}
		
		$('#template_general').show();
		// show the editor and hide the viewer
		$('#label_viewer').hide();
		$('#label_editor').show();
		// show save button and hide edit button
		$('#label_save').show();
		$('#view_buttons').hide();
	}
};

function deleteTemplate(index) {
	if (label_templates[index] && confirm('<?php echo $text_label_template_delete_confirm; ?>')) {
		var label_template_id = label_templates[index]['label_template_id'];
		$.ajax({
			url: 'index.php?route=module/pos/delete_label_template&token=<?php echo $token; ?>&label_template_id=' + label_template_id,
			type: 'post',
			dataType: 'json',
			beforeSend: function() {
				$('#label_delete').hide();
				$('#label_delete').before('<img src="view/image/pos/loading.gif" class="loading" style="padding-left: 5px;" />');	
			},
			complete: function() {
				$('.loading').remove();
			},
			success: function(json) {
				// remove the item from list
				$('select[name=label_templates] option:selected').remove();
				$('select[name=label_templates]').val($('select[name=label_templates] option:first').val());
				$('select[name=label_templates]').trigger('change');
				$('select[name=label_print_templates] option').each(function() {
					if ($(this).val() == index) {
						$(this).remove();
					}
				});
			}
		});
	}
};

function saveTemplate(button) {
	// get all data
	var data = {};
	var dataError = false;
	$('#template_general input').each(function() {
		var key = $(this).attr('name');
		if (key != 'label_template_id') {
			if (key == 'label_template_name') {
				key = 'name';
			} else {
				key = key.substring('label_'.length, key.length);
			}
		}
		data[key] = $(this).val();
		if (!$(this).val()) {
			dataError = true;
		}
	});
	
	if (dataError) {
		alert('<?php echo $text_label_template_all_required; ?>');
		return;
	}
	
	var content = [];
	
	var labelWidth = $('#label_template_table').width();
	var labelHeight = $('#label_template_table').height();
	$('#label_template_table div').each(function() {
		if ($(this).hasClass('clone')) {
			var top = $(this).css('top');
			top = parseInt(top.substring(0, top.length-2)) / labelHeight;
			var left = $(this).css('left');
			left = parseInt(left.substring(0, left.length-2)) / labelWidth;
			var width = $(this).css('width');
			width = parseInt(width.substring(0, width.length-2)) / labelWidth;
			var height = $(this).css('height');
			height = parseInt(height.substring(0, height.length-2)) / labelHeight;
			
			var background = $(this).css('background-color');
			var text = ($(this).find('.label-text-wrapper').find('.label-text').length > 0) ? $(this).find('.label-text-wrapper').find('.label-text').text() : '';
			var barcode = ($(this).find('.label-text-wrapper').find('div').length > 0) ? 1 : 0;
			var barcodeType = '';
			if (barcode > 0) {
				barcodeType = $(this).find('.label-text-wrapper').find('div').find('input').val();
			}
			var img = ($(this).find('img').length > 0) ? $(this).find('img').attr('src') : '';
			
			// save the text style
			var style = {};
			if ($(this).find('.label-text-wrapper').find('.label-text').length > 0) {
				// only get font-weight, font-style, text-decoration , text-align and font-size
				var paras = ['font-weight', 'font-style', 'text-decoration', 'text-align', 'font-size'];
				for (var i = 0; i < paras.length; i++) {
					style[paras[i]] = $(this).find('.label-text-wrapper').find('.label-text').css(paras[i]);
				}
			}
			
			content.push({'id':$(this).attr('id'), 'left':left, 'top':top, 'width':width, 'height':height, 'background':background, 'text':text, 'barcode':barcode, 'barcodeType':barcodeType, 'img':img, 'style':style});
		}
	});
	
	if (content.length == 0) {
		alert('<?php echo $text_label_template_empty_content; ?>');
	}
	
	data['content'] = JSON.stringify(content);
	
	$.ajax({
		url: 'index.php?route=module/pos/save_label_template&token=<?php echo $token; ?>',
		type: 'post',
		dataType: 'json',
		data: data,
		beforeSend: function() {
			$(button).hide();
			$(button).before('<img src="view/image/pos/loading.gif" class="loading" style="padding-left: 5px;" />');	
		},
		complete: function() {
			$('.loading').remove();
		},
		success: function(json) {
			// save the value to local variable and to the select element
			if (parseInt(data['label_template_id']) <= 0) {
				// a new template
				var maxId = 1;
				for (var i in label_templates) {
					if (parseInt(i) >= maxId) {
						maxId = parseInt(i) + 1;
					}
				}
				label_templates[maxId] = json;
				$('select[name=label_templates] option:nth-last-child(2)').before('<option value="' + maxId + '">' + data['name'] + '</option>');
				$('select[name=label_templates]').val(maxId);
				$('select[name=label_templates]').trigger('change');
				// also update the print template list
				$('select[name=label_print_templates]').append('<option value="' + maxId + '">' + data['name'] + '</option>');
			} else {
				var refreshKey = $('select[name=label_templates]').val();
				label_templates[refreshKey] = json;
				viewTemplate(refreshKey);
				// also update the names in the two select boxes
				$('select[name=label_templates] option:selected').text(data['name']);
				$('select[name=label_print_templates] option').each(function() {
					if ($(this).val() == refreshKey) {
						$(this).text(data['name']);
					}
				});
			}
		}
	});
};

// add for label print end

function setProductRewardPoints(button) {
	var data = $('#reward_points input');
	$.ajax({
		url: 'index.php?route=module/pos/set_products_reward_points&token=<?php echo $token; ?>',
		type: 'post',
		dataType: 'json',
		data: $(data),
		beforeSend: function() {
			$(button).hide();
			$(button).before('<img src="view/image/pos/loading.gif" class="loading" style="padding-left: 5px;" />');	
		},
		complete: function() {
			$('.loading').remove();
			$(button).show();
		},
		success: function(json) {
			// save the value to local variable and to the select element
			alert(json['message']);
		}
	});
};

// add for product low stock begin
function setProductLowStock(button) {
	var lowStock = parseInt($('input[name=product_low_stock]').val());
	if (lowStock != 'undefined' && lowStock > 0) {
		var data = $('#enable_product_low_stock input[type=\'text\'], #enable_product_low_stock input[type=\'checkbox\']:checked');
		$.ajax({
			url: 'index.php?route=module/pos/set_products_low_stock&token=<?php echo $token; ?>',
			type: 'post',
			dataType: 'json',
			data: $(data),
			beforeSend: function() {
				$(button).hide();
				$(button).before('<img src="view/image/pos/loading.gif" class="loading" style="padding-left: 5px;" />');	
			},
			complete: function() {
				$('.loading').remove();
				$(button).show();
			},
			success: function(json) {
				// save the value to local variable and to the select element
				alert(json['message']);
				$('input[name=product_low_stock]').val('');
				$(button).closest('tr').find(':checkbox').attr('checked', false);
			}
		});
	}
};
// add for product low stock end
// add for sales report begin
function addItem(section) {
	var option = $('select[name=' + section + '_report_item_add] option:selected');
	var title = option.val();
	var field = option.text();
	var type = option.attr('type');
	var feature = '';
	var order = $('#' + section + '_report_item_list tr').length + 1;
	
	if (type == '_pos_custom_field') {
		$('#sales_report_custom_field_dialog input').val('');
		$('#sales_report_custom_field_dialog #custom_field_order').val(order);
		$('#sales_report_custom_field_dialog #custom_field_section').val(section);
		$('#sales_report_custom_field_dialog #custom_field_value_in_quote').prop('checked', false);
		$('#sales_report_custom_field_dialog').dialog('open');
	} else {
		addItemInList(section, field, title, order, type, feature);
		option.remove();
	}
};

$('#button_add_custom_field').click(function() {
	var section = $('#custom_field_section').val();
	var title = $('#custom_field_title').val();
	var order = $('#custom_field_order').val();
	var field = $('#custom_field_value').val();
	if (field == null || field == '') {
		field =  '{null}_' + order;
	} else {
		field += '_' + order;
	}
	var feature = '';
	if ($('#custom_field_value_in_quote').is(':checked')) {
		feature = '{in_quote}';
	}
	addItemInList(section, field, title, order, '_pos_custom_field', feature);
	$('#sales_report_custom_field_dialog').dialog('close');
});

$('#button_add_new_line').click(function() {
	var section = $('#custom_field_section').val();
	var title = '{<?php echo $text_new_line; ?>}';
	var order = $('#custom_field_order').val();
	var field = '{new line}_' + order;
	addItemInList(section, field, title, order, '_pos_custom_field', '{p}');
	$('#sales_report_custom_field_dialog').dialog('close');
});

$('#button_add_today_date').click(function() {
	var section = $('#custom_field_section').val();
	var title = '<?php echo $text_today; ?>';
	var order = $('#custom_field_order').val();
	var field = formatDate(new Date()) + '_' + order;
	addItemInList(section, field, title, order, '_pos_custom_field', '{today}');
	$('#sales_report_custom_field_dialog').dialog('close');
});

$('#button_add_average_cost').click(function() {
	var section = $('#custom_field_section').val();
	var order = $('#custom_field_order').val();
	if (section == 'stock') {
		title = '<?php echo $text_average_cost; ?>';
		field = '(product) cost';
		addItemInList(section, field, title, order, '_pos_custom_field', '{average cost}');
		$('#sales_report_custom_field_dialog').dialog('close');
	} else {
		alert('<?php echo $text_average_cost_alert; ?>');
		return;
	}
});

function addItemInList(section, field, title, order, type, feature) {
	var html = '<tr>';
	html += '<td class="text-center">';
	html += '<input type="radio" class="form-control" name="' + section + '_report_item" value="' + title + '" />';
	html += '<input type="hidden" name="' + section + '_report_items[' + field + '][order]" value="' + order + '" />';
	html += '<input type="hidden" name="' + section + '_report_items[' + field + '][type]" value="' + type + '" />';
	html += '<input type="hidden" name="' + section + '_report_items[' + field + '][feature]" value="' + feature + '" />';
	html += '<input type="hidden" name="' + section + '_report_items[' + field + '][title]" value="' + title + '" />';
	html += '</td>';
	html += '<td class="text-left item-title">'+ title + '</td>';
	html += '<td class="text-left">' + ((type != '_pos_custom_field' || feature == '{average cost}') ? field : (field == ('{null}_' + order) ? '' : field.substring(0, field.indexOf('_'+order)))) + '</td>';
	html += '<td class="text-left">' + feature + '</td>';
	html += '</tr>';
	$('#' + section + '_report_item_list').append(html);
};

function deleteItem(section) {
	if ($('#' + section + '_report_item_list input[type=radio]:checked').length > 0) {
		var row = $('#' + section + '_report_item_list input[type=radio]:checked').closest('tr');
		var field = row.find('input[type=radio]').val();
		var type = row.find('input[name*=\'[type]\']').val();
		var title = row.find('td').eq(1).text();
		
		if (type != '_pos_custom_field') {
			// add back to the available list
			for (var i = 0; i < $('select[name=' + section + '_report_item_add] option').length; i++) {
				var option = $('select[name=' + section + '_report_item_add] option:eq(' + i + ')');
				if (field.localeCompare(option.text()) < 0) {
					var restoreOption = '<option value="' + title + '" type="' + type + '">' + field + '</option>';
					option.before(restoreOption);
					break;
				}
			}
		}
		
		// change the order of the remaining items
		var index = $('#' + section + '_report_item_list tr').index(row);
		// remove from the current list
		row.remove();
		while (index < $('#' + section + '_report_item_list tr').length) {
			$('#' + section + '_report_item_list tr:eq(' + index + ') input[name*=\'[order]\']').val(index);
			index++;
		}
	}
};

function moveItemUp(section) {
	if ($('#' + section + '_report_item_list input[type=radio]:checked').length > 0) {
		var row = $('#' + section + '_report_item_list input[type=radio]:checked').closest('tr');
		var index = $('#' + section + '_report_item_list tr').index(row);
		
		if (index > 0) {
			var order = row.find('input[name*=\'[order]\']').val();
			var type = row.find('input[name*=\'[type]\']').val();
			
			// swap current row and the row above
			var preRow = $('#' + section + '_report_item_list tr:eq(' + (index-1) + ')');
			var preOrder = preRow.find('input[name*=\'[order]\']').val();
			var preType = preRow.find('input[name*=\'[type]\']').val();
			
			row.find('input[name*=\'[order]\']').val(preOrder);
			preRow.find('input[name*=\'[order]\']').val(order);
			
			// also exchange the key if they are custom fields
			if (type == '_pos_custom_field') {
				row.find('input').each(function() {
					var name = $(this).attr('name').replace('_'+order+'][', '_'+preOrder+'][');
					$(this).attr('name', name);
				});
			}
			if (preType == '_pos_custom_field') {
				preRow.find('input').each(function() {
					var name = $(this).attr('name').replace('_'+preOrder+'][', '_'+order+'][');
					$(this).attr('name', name);
				});
			}
			
			row.after(preRow);
		}
	}
};

function moveItemDown(section) {
	if ($('#' + section + '_report_item_list input[type=radio]:checked').length > 0) {
		var row = $('#' + section + '_report_item_list input[type=radio]:checked').closest('tr');
		var index = $('#' + section + '_report_item_list tr').index(row);
		
		if (index < $('#' + section + '_report_item_list tr').length-1) {
			var order = row.find('input[name*=\'[order]\']').val();
			var type = row.find('input[name*=\'[type]\']').val();
			
			// swap current row and the row above
			var nextRow = $('#' + section + '_report_item_list tr:eq(' + (index+1) + ')');
			var nextOrder = nextRow.find('input[name*=\'[order]\']').val();
			var nextType = nextRow.find('input[name*=\'[type]\']').val();
			
			row.find('input[name*=\'[order]\']').val(nextOrder);
			nextRow.find('input[name*=\'[order]\']').val(order);
			
			// also exchange the key if they are custom fields
			if (type == '_pos_custom_field') {
				row.find('input').each(function() {
					var name = $(this).attr('name').replace('_'+order+'][', '_'+nextOrder+'][');
					$(this).attr('name', name);
				});
			}
			if (nextType == '_pos_custom_field') {
				nextRow.find('input').each(function() {
					var name = $(this).attr('name').replace('_'+nextOrder+'][', '_'+order+'][');
					$(this).attr('name', name);
				});
			}
			
			row.before(nextRow);
		}
	}
};

$(document).on('click', '.item-title', function(e) {
	
	if ($(this).find('input[type=text]').length > 0) return;

	var orgText = $(this).closest('tr').find('input[name*=\'[title]\']').val();
	var width = $(this).width()-2;
	var td = $(this);

	$(this).text('');
	$('<input style="width:'+width+'px;" type="text" name="temp_input" class="onenter onblur"/>').appendTo($(this));
	$('.onenter').val(orgText).select().keydown(function(e) {
		// have to use keydown as keypress does not have ESC event in some browsers while keyup is too late that the keypress message has been sent to opencart
		if (e.keyCode == 13) {
			var text = $('input[name=temp_input]').val();
			td.text(text);
			td.closest('tr').find('input[name*=\'[title]\']').val(text);
			$('input[name=temp_input]').remove();
			// have to return false, otherwise, the keydown event will be used by opencart itself to submit the form!!
			return false;
		} else if (e.keyCode == 27) {
			td.text(orgText);
			$('input[name=temp_input]').remove();
		}
	});
	$('.onblur').val(orgText).select().blur(function() {
		var text = $('input[name=temp_input]').val();
		td.text(text);
		td.closest('tr').find('input[name*=\'[title]\']').val(text);
		$('input[name=temp_input]').remove();
	});
});

function exportReport(section) {
	// export the configured report to CSV file
	url = 'index.php?route=module/pos/export_report_csv&token=<?php echo $token; ?>&type=' + section;
	location = url;
};
// add for sales report end

function formatDate(date, includeTime) {
	var hours = date.getHours();
	var minutes = date.getMinutes();
	var seconds = date.getMinutes();
	hours = ( hours < 10 ? "0" : "" ) + hours;
	minutes = ( minutes < 10 ? "0" : "" ) + minutes;
	seconds = ( seconds < 10 ? "0" : "" ) + seconds;

	var month = date.getMonth()+1;
	var day = date.getDate();
	month = ( month < 10 ? "0" : "" ) + month;
	day = ( day < 10 ? "0" : "" ) + day;
	
	var value = date.getFullYear() + '-' + month + '-' + day;
	if (includeTime ) {
		value += ' ' + hours + ':' + minutes + ':' + seconds;
	}
	
	return value;
};
</script> 

<?php echo $footer; ?>