<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $language; ?>" xml:lang="<?php echo $language; ?>">
<head>
<title><?php echo $title; ?></title>
<style type="text/css">
	body, div, table {
		font-size: 12px; font-family: Arial, Georgia, Serif;
	}
</style>
</head>
<body>
<div>
<div id="content" style="width: <?php echo $p_width; ?>px;">
<table border="0" align="center" width="100%">
	<tbody id="header">
	<tr><td colspan="4" align="center"><img src="<?php echo $p_logo; ?>"/></td></tr>
	<tr><td colspan="4" align="center"><?php echo $order['store_address']; ?><br /><?php echo $order['store_telephone']; ?></td></tr>
	<tr><td colspan="4" align="right">Term&nbsp;1</td></tr>
	<tr>
		<td colspan="2" align="left" id="receipt_date_td"><?php echo $date; ?></td>
		<td colspan="2" align="right" id="receipt_time_td"><?php echo $time; ?></td>
	</tr>
	<tr style="border-bottom: 1px solid #000000;">
		<td align="right" width="10%" style="border-bottom: 1px solid #000000;"><b><?php echo $column_qty; ?></b>&nbsp;</td>
		<td align="left" width="45%" style="border-bottom: 1px solid #000000;"><b><?php echo $column_desc; ?></b></td>
		<td align="right" width="25%" style="border-bottom: 1px solid #000000;"><b><?php echo $column_price; ?></b></td>
		<td align="right" width="20%" style="border-bottom: 1px solid #000000;"><b><?php echo $column_total; ?></b></td>
	</tr>
	</tbody>
	<tbody id="receipt_products">
	<?php $discount_data = array(); $row_num = 0; ?>
	<?php foreach ($order['product'] as $product) { ?>
	<tr>
		<td align="right" valign="top"><?php echo $product['quantity']; ?>&nbsp;</td>
		<td align="left" valign="top"><?php echo $product['name']; ?></td>
		<td align="right" valign="top"><?php echo $product['price']; ?></td>
		<?php if (!empty($product['discount_type'])) { $discount_data['text_discount'] = $product['text_discount']; $discount_data['total'] = $product['total']; ?>
		<td align="right"><strike><?php echo $product['text_before_discount']; ?></strike></td>
		<?php } else { $discount_data = array(); ?>
		<td align="right"><?php echo $product['total']; ?></td>
		<?php } ?>
	</tr>
	<?php if (!empty($product['option']) || !empty($product['weight_price']) || !empty($product['sns'])) {?>
	<tr><td></td><td colspan="3" align="left">
	<?php foreach ($product['option'] as $option) { ?>
	&nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small><br/>
	<?php } ?>
	<?php if (!empty($product['weight_price'])) { ?>
	&nbsp;<small> - <?php echo $product['weight_name']; ?>: <?php echo $product['weight']; ?></small><br/>
	<?php }?>
	<!-- add for serial no begin -->
	<?php if (!empty($product['sns'])) { foreach ($product['sns'] as $product_sn) { ?>
	&nbsp;<small> - SN: <?php echo $product_sn['sn']; ?></small><br/>
	<?php }}?>
	<!-- add for serial no end -->
	</td></tr>
	<?php }?>
	<?php if (!empty($discount_data)) { ?>
	<tr>
		<td colspan="3" align="right"><small>(<?php echo $text_discount; ?>: <?php echo $discount_data['text_discount']; ?>)</small></td>
		<td align="right"><?php echo $discount_data['total']; ?></td>
	</tr>
	<?php }?>
	<tr><td colspan="4" align="center"><?php if ($barcode_for_product) { ?><div id="barcode_13_total_<?php echo $row_num; ?>"><?php echo $product['ean']; ?></div><?php } else { echo $product['ean']; }?></td></tr>
	<?php $row_num++; } ?>
	</tbody>
	<tbody id="receipt_totals">
	<?php foreach ($order['total'] as $total) { ?>
	<tr>
		<td colspan="3" align="right" width="80%"><b><?php echo $total['title']; ?>:</b></td>
		<td align="right" width="20%"><?php echo $total['text']; ?></td>
	</tr>
	<?php } ?>
	</tbody>
	<tr>
		<td colspan="4">
		<table width="100%">
			<tr style="border-bottom: 1px solid #000000;">
				<td align="left" width="30%" style="border-bottom: 1px solid #000000;"><b><?php echo $column_type; ?></b></td>
				<td align="right" width="10%" style="border-bottom: 1px solid #000000;"><b><?php echo $column_amount; ?></b>&nbsp;</td>
				<td align="right" width="60%" style="border-bottom: 1px solid #000000;"><b><?php echo $column_note; ?></b></td>
			</tr>
			<tbody id="receipt_payments">
			<?php foreach ($payments as $payment) { if ((float)$payment['amount_float'] > 0) { ?>
			<tr>
				<td align="left" valign="top" style="border-bottom: 1px dashed #000000;"><?php echo $payment['type']; ?></td>
				<td align="right" valign="top" style="border-bottom: 1px dashed #000000;"><?php echo $payment['amount']; ?>&nbsp;</td>
				<td align="right" valign="top" style="border-bottom: 1px dashed #000000;"><?php echo (''==$payment['note'])?'&nbsp;':$payment['note']; ?></td>
			</tr>
			<?php }} ?>
			</tbody>
			<tbody id="receipt_change">
			<?php if(isset($change)) { ?>
			<tr>
				<td align="left" valign="top" style="border-bottom: 1px dashed #000000;"><?php echo $text_change; ?></td>
				<td align="right" valign="top" style="border-bottom: 1px dashed #000000;"><?php echo $change; ?>&nbsp;</td>
				<td align="right" valign="top" style="border-bottom: 1px dashed #000000;">&nbsp;</td>
			</tr>
			<?php } ?>
			</tbody>
		</table>
		</td>
	</tr>
	<tr><td colspan="4" align="center" id="receipt_user_info"><?php echo $user_info; ?></td></tr>
	<tr><td colspan="4" align="center"><?php echo nl2br($term_n_cond); ?></td></tr>
	<tr><td colspan="4" align="center"><div id="barcode_order"><?php echo $order['order_id']; ?></div></td></tr>
</table>
</div>
</div>
</body>
</html>