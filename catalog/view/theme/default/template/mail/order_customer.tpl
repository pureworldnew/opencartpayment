<div class="emailContent">{$emailtemplate.content1}</div>

<?php if (!empty($customer_id) && !empty($link)) { ?>
<br />
<div class="link">
	<b><?php echo $text_link; ?></b><br />
	<span>&raquo;</span>
	<a href="<?php echo $link_tracking; ?>" target="_blank">
		<b><?php echo $link; ?></b>
	</a>
</div>
<?php } ?>

<?php if (!empty($download)) { ?>
<br />
<div class="link">
	<b><?php echo $text_download; ?></b><br />
	<span class="icon">&raquo;</span>
	<a href="<?php echo $download_tracking; ?>" target="_blank">
		<b><?php echo $download; ?></b>
	</a>
</div>
<?php } ?>

<?php if (!empty($instruction)) { ?>
	<br />
	<div><?php echo $instruction; ?></div><br />
<?php } ?>

<table class="emailSpacer" cellpadding="0" cellspacing="0" width="100%"><tr><td height="15">&nbsp;</td></tr></table>

<?php if (!empty($order_id)) { ?>
<table cellpadding="5" cellspacing="0" width="100%" class="table1">
<thead>
	<tr>
    	<th bgcolor="#ededed" class="textCenter"><?php echo $text_order_detail; ?></th>
   	</tr>
</thead>
<tbody>
	<tr>
		<td bgcolor="#fafafa" style="padding:0">
			<table cellpadding="0" cellspacing="0" width="100%" class="tableStack">
			<tbody>
				<tr>
			    	<td width="50%">
			          	<b><?php echo $text_order_id; ?></b> <?php echo $order_id; ?><br />
			    		<?php if (!empty($invoice_no)) {?><b><?php echo $text_invoice_no; ?></b> <?php echo $invoice_no; ?><br /><?php } ?>
			          	<b><?php echo $text_date_added; ?></b> <?php echo $date_added; ?><br />
			          	<b><?php echo $text_order_status; ?></b> <?php echo $new_order_status; ?>
			        </td>
			        <td width="50%" class="last-child">
			        	<b><?php echo $text_email; ?></b> <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a><br />
			          	<b><?php echo $text_telephone; ?></b> <a href="tel:<?php echo $telephone; ?>"><?php echo $telephone; ?></a>
			        </td>
				</tr>
				</tbody>
			</table>
		</td>
	</tr>
	<tr class="last-child">
		<td bgcolor="#f6f6f6" style="padding:0">
			<table cellpadding="0" cellspacing="0" width="100%" class="tableStack">
			<tbody>
				<tr>
			    	<td class="address" width="50%">
		    			<b><?php echo $text_payment_address; ?></b><br />
		    			<p><?php echo $payment_address; ?></p>
		    			<?php if ($payment_method) { ?><b><?php echo $text_payment_method; ?></b> <?php echo $payment_method; } ?>
			    	</td>
			    	<?php if ($shipping_address) { ?>
			        <td class="address last-child" width="50%">
		        		<b><?php echo $text_shipping_address; ?></b><br />
		        		<p><?php echo $shipping_address; ?></p>
		        		<?php if ($shipping_method) { ?><b><?php echo $text_shipping_method; ?></b> <?php echo $shipping_method; } ?>
			        </td>
			        <?php } ?>
				</tr>
			</tbody>
			</table>
		</td>
	</tr>
</tbody>
</table>

<table class="emailSpacer" cellpadding="0" cellspacing="0" width="100%"><tr><td height="15">&nbsp;</td></tr></table>
<?php } ?>

<?php if (!empty($products)) { ?>
<table cellpadding="5" cellspacing="0" width="100%" class="table1">
<thead>
	<tr>
        <th width="50%" bgcolor="#ededed" class="textCenter"><?php echo $text_product; ?></th>
        <?php if ($config['table_quantity']) { ?>
	        <th width="10%" bgcolor="#ededed" align="center" class="textCenter"><b><?php echo $text_quantity; ?></b></th>
        <?php } ?>
        <th width="<?php if ($config['table_quantity']) { ?>20<?php } else { ?>25<?php } ?>%" bgcolor="#ededed" align="right" class="textRight"><?php echo $text_price; ?></th>
        <th width="<?php if ($config['table_quantity']) { ?>20<?php } else { ?>25<?php } ?>%" bgcolor="#ededed" align="right" class="textRight"><?php echo $text_total; ?></th>
	</tr>
</thead>
<tbody>
	<?php $i = 0; ?>
	<?php $colspan = ($config['table_quantity']) ? 3 : 2; ?>
	<?php foreach ($products as $product) { $row_style_background = ($i++ % 2) ? "#f6f6f6" : "#fafafa"; ?>
	    <tr>
			<td bgcolor="<?php echo $row_style_background; ?>">
				<?php if ($product['image']) { ?>
					<a href="<?php echo $product['url_tracking']; ?>">
						<img src="<?php echo $product['image']; ?>" width="50" height="50" alt="" class="product-image" />
					</a>
				<?php } ?>

				<a href="<?php echo $product['url_tracking']; ?>"><strong class="product-name"><?php echo $product['name']; ?></strong></a>

				<?php /*		<div style="font-size:12px;"><?php echo $product['description']; ?></div>		*/ ?>

				<?php if (!empty($product['option'])) { ?>
				<br style="clear:both" />
				<p class="list-product-options">
					<?php foreach ($product['option'] as $option) { ?>
						&raquo; <strong><?php echo $option['name']; ?>:</strong>&nbsp;<?php echo $option['value']; ?><?php if ($option['price']) echo "&nbsp;(".$option['price'].")"; ?><br />
					<?php } ?>
				</p>
				<?php } ?>

				<?php if ($product['model']) { ?><div class="list-product-options"><b><?php echo $text_model; ?>:</b>&nbsp;<?php echo $product['model']; ?></div><?php } ?>
			</td>
			<?php if ($config['table_quantity']) { ?>
				<td bgcolor="<?php echo $row_style_background; ?>" align="center" class="textCenter"><?php echo $product['quantity']; ?></td>
				<td bgcolor="<?php echo $row_style_background; ?>" align="right" class="textRight price"><?php echo $product['price']; ?></td>
			<?php } else { ?>
				<td bgcolor="<?php echo $row_style_background; ?>" align="right" class="textRight price"><?php if ($product['quantity'] > 1) { echo $product['quantity']; ?> <b>x</b> <?php } echo $product['price']; ?></td>
			<?php } ?>
			<td bgcolor="<?php echo $row_style_background; ?>" align="right" class="textRight price">
				<?php echo $product['total']; ?>
			</td>
		</tr>
	<?php } ?>

	<?php if (isset($vouchers)) { ?>
		<?php foreach ($vouchers as $voucher) { $row_style_background = ($i++ % 2) ? "#f6f6f6" : "#fafafa"; ?>
		<tr>
	        <td colspan="<?php echo $colspan; ?>" bgcolor="<?php echo $row_style_background; ?>"><?php echo $voucher['description']; ?></td>
			<td bgcolor="<?php echo $row_style_background; ?>" align="right" class="textRight price"><?php echo $voucher['amount']; ?></td>
		</tr>
		<?php } ?>
	<?php } ?>
</tbody>
<?php if (isset($totals)) { ?>
<tfoot>
	<?php foreach ($totals as $total) {
		$row_style_background = ($i++ % 2) ? "#f6f6f6" : "#fafafa"; ?>
	<tr>
		<td bgcolor="<?php echo $row_style_background; ?>" colspan="<?php echo $colspan; ?>" align="right" class="textRight"><b><?php echo $total['title']; ?></b></td>
		<td bgcolor="<?php echo $row_style_background; ?>" align="right" class="textRight price"><?php echo $total['text']; ?></td>
	</tr>
	<?php } ?>
</tfoot>
<?php } ?>
</table>

<table class="emailSpacer" cellpadding="0" cellspacing="0" width="100%"><tr><td height="15">&nbsp;</td></tr></table>
<?php } ?>

<?php if (!empty($order_comment)) { ?>
	<br /><b><?php echo $text_new_comment; ?></b><br />
	<div><?php echo $order_comment; ?></div><br />
<?php } ?>

<div class="emailContent">{$emailtemplate.content2}</div>