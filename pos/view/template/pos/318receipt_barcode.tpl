<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $language; ?>" xml:lang="<?php echo $language; ?>">
	<head>
		<title><?php echo $title; ?></title>
		<style type="text/css">
			body, div, table {
				font-size: 12px; font-family: Arial, Georgia, Serif;
			}
			th, td {
				padding: 5px;
			}
		</style>
	</head>
	<body>
		<div>
			<div id="content" style="width: 600px; margin: 10px auto; text-align: center;">
				<table align="center" width="100%">
					<tbody id="header">
						<tr><td colspan="4" align="center"><img src="<?php echo $p_logo; ?>"/></td></tr>
						<tr><td colspan="4" align="center"><?php echo $order['store_address']; ?><br /><?php echo $order['store_telephone']; ?></td></tr>
						<tr><td style="border: 1px solid #ddd;" colspan="4" align="left"><strong>Invoice #<?php echo $order['order_id']; ?></strong></td></tr>
					</tbody>
					<tbody>
						<tr>
							<td style="border: 1px solid #ddd;" colspan="2" align="left" width="50%"><strong>Order Status: </strong><?php echo $order['order_status']; ?> <br / ><strong>Date Added: </strong><?php echo $order['date_added']; ?> <br /><strong>Shipping Method: </strong><?php echo $order['shipping_method']; ?></td>
							<td style="border: 1px solid #ddd;" colspan="2" align="left" width="50%"><strong>Payment Type: </strong><?php echo $all_payment_types; ?> <br /><strong>Notes: </strong><?php echo $order['comment']; ?></td>
						</tr>
					</tbody>
					<tbody>
						<tr>
							<td align="left" style="border: 1px solid #ddd;"><strong>Billing Address</strong></td>
							<td align="left" style="border: 1px solid #ddd;" colspan="3"><?php echo $order['billing_info']; ?></td>
						</tr>
					</tbody>
					<tbody id="receipt_products">
						<tr>
							<td align="left" width="45%" style="border: 1px solid #ddd; background-color: #f1f1f1;"><b><?php echo 'Products'; ?></b></td>
							<td align="right" width="10%" style="border: 1px solid #ddd; background-color: #f1f1f1; "><b><?php echo $column_qty; ?></b>&nbsp;</td>
							<td align="right" width="25%" style="border: 1px solid #ddd; background-color: #f1f1f1;"><b><?php echo $column_price; ?></b></td>
							<td align="right" width="20%" style="border: 1px solid #ddd; background-color: #f1f1f1;"><b><?php echo $column_total; ?></b></td>
						</tr>
						<?php $discount_data = array(); $row_num = 0; ?>
						<?php foreach ($order['product'] as $product) { ?>
							<tr>
								<td style="border: 1px solid #ddd;" align="left" valign="top"><?php echo $product['name']; ?> 
									<?php if (!empty($product['option']) || !empty($product['weight_price']) || !empty($product['sns'])) { ?>
										<br />
										<?php foreach ($product['option'] as $option) { ?>
											&nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small><br/>
										<?php } ?>
										<?php if (!empty($product['weight_price'])) { ?>
											&nbsp;<small> - <?php echo $product['weight_name']; ?>: <?php echo $product['weight']; ?></small><br/>
										<?php } ?>
										<!-- add for serial no begin -->
										<?php if (!empty($product['sns'])) { foreach ($product['sns'] as $product_sn) { ?>
											&nbsp;<small> - SN: <?php echo $product_sn['sn']; ?></small><br/>
										<?php } } ?>
										<!-- add for serial no end -->
									<?php } ?>
									<?php if (!empty($discount_data)) { ?>
										<br /><small>(<?php echo $text_discount; ?>: <?php echo $discount_data['text_discount']; ?>)</small>
										<?php echo $discount_data['total']; ?>
									<?php } ?>
									<br/ > <?php echo $product['model']; ?>
								</td>
								<td style="border: 1px solid #ddd;" align="right" valign="top"><?php echo $product['quantity']; ?>&nbsp;</td>
								<td style="border: 1px solid #ddd;" align="right" valign="top">
									<?php if ( $product['discounted_price'] < $product['normal_price'] ) { 
										echo "<strike>" . $product['normal_price_text'] . "</strike><br>";
										echo $product['price'];
									} else {
										echo $product['price'];
									} ?>
								</td>
								<?php if (!empty($product['discount_type'])) { $discount_data['text_discount'] = $product['text_discount']; $discount_data['total'] = $product['total']; ?>
									<td style="border: 1px solid #ddd;" align="right" valign="top"><strike><?php echo $product['text_before_discount']; ?></strike></td>
								<?php } else { $discount_data = array(); ?>
									<td style="border: 1px solid #ddd;" align="right" valign="top">
										<?php if ( $product['discounted_price'] < $product['normal_price'] ) { 
											echo "<strike>" . $product['normal_price_total_text'] . "</strike><br>";
											echo $product['total'];
										} else {
											echo $product['total'];
										} ?>
									</td>
								<?php } ?>
							</tr>
							<?php $row_num++; 
						} ?>
					</tbody>
					<tbody id="receipt_totals">
						<?php foreach ($order['total'] as $total) { if($total['code'] = "total") { $total_amount = $total['value']; } ?>
							<tr>
								<td colspan="3" align="left" width="80%" style="border: 1px solid #ddd;"><b><?php echo $total['title']; ?>:</b></td>
								<td align="right" width="20%" style="border: 1px solid #ddd;"><?php echo $total['text']; ?></td>
							</tr>
						<?php } ?>
					</tbody>
					<tr>
						<td colspan="4">
							<table width="100%">
								<tbody id="receipt_payments">
									<?php $total_paid = 0; ?>
									<?php foreach ($payments as $payment) { if((float)$payment['amount_float'] > 0) { $total_paid += $payment['amount_float']; } }  ?>
									<?php if($total_paid >= $total_amount) $payment_status = "Paid ";  else  $payment_status = "Partial Paid ";  ?>
									<?php foreach ($payment_combined as $payment) { if((float)$payment['amount_float'] > 0) { ?>
										<tr>
											<td align="left" width="80%" valign="top" style="border: 1px solid #ddd;"><strong><?php echo $payment_status . $payment['type']; ?></strong></td>
											<td align="right" width="20%" valign="top" style="border: 1px solid #ddd;"><?php echo $payment['amount']; ?>&nbsp;</td>
										</tr>
									<?php } } ?>
								</tbody>
								<tbody id="receipt_change">
									<?php if(isset($change)) { ?>
										<tr>
											<td align="left"  width="80%" valign="top" style="border: 1px solid #ddd;"><strong><?php echo $text_change; ?></strong></td>
											<td align="right"  width="20%" valign="top" style="border: 1px solid #ddd;"><?php echo $change; ?>&nbsp;</td>
										</tr>
									<?php } ?>
								</tbody>
								<tbody id="resale_text">
									<?php if(!empty($order['resale_text'])) { ?>
										<tr>
											<td align="left" valign="top" style="border-bottom: 1px dashed #ddd;" colspan="2">Resale ID #</td>
											<td align="right" valign="top" style="border-bottom: 1px dashed #ddd;"><?php echo $order['resale_text']; ?></td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</td>
					</tr>
					
					<?php if( !empty( $order['comment'] ) ) : ?>
						<tr><td colspan="4" align="left" id="order_comment">Order Notes : <?php echo $order['comment']; ?></td></tr>
					<?php endif; ?>
					<tr><td colspan="4" align="left"><?php echo nl2br($term_n_cond); ?></td></tr>
					<tr><td colspan="4" align="center" id="receipt_user_info"><?php echo $user_info; ?></td></tr>
					<tr><td colspan="4" align="center"><div id="barcode_order"><?php echo $order['order_id']; ?></div></td></tr>
				</table>
			</div>
		</div>
	</body>
</html>