<div class="emailContent">{$emailtemplate.content1}</div>

<?php if (!empty($order_comment)) { ?>
	<br /><b><?php echo $text_new_comment; ?></b>
	<br /><?php echo $order_comment; ?><br />
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
		<td bgcolor="#fafafa">
			<table cellpadding="5" cellspacing="0" width="100%" class="tableStack">
			<tbody>
				<tr>
			    	<td>
			          	<b><?php echo $text_order_id; ?></b> <?php echo $order_id; ?><br />
			    		<?php if (!empty($invoice_no)) {?><b><?php echo $text_invoice_no; ?></b> <?php echo $invoice_no; ?><br /><?php } ?>
			          	<?php if (!empty($date_added)) { ?><b><?php echo $text_date_added; ?></b> <?php echo $date_added; ?><br /><?php } ?>
			          	<?php if (!empty($new_order_status)) { ?><b><?php echo $text_new_order_status; ?></b> <?php echo $new_order_status; } ?>
			        </td>
			        <td>
			        	<?php if (!empty($email)) { ?><b><?php echo $text_email; ?></b> <a href="mailto:<?php echo $email; ?>" style="color:<?php echo $config['body_link_color']; ?>; word-wrap:break-word;"><?php echo $email; ?></a><br /><?php } ?>
			          	<?php if (!empty($telephone)) { ?><b><?php echo $text_telephone; ?></b> <a href="tel:<?php echo $telephone; ?>"><?php echo $telephone; ?></a><br /><?php } ?>
			          	<?php if (!empty($ip)) { ?><b><?php echo $text_ip; ?></b> <?php echo $ip; } ?>
			          	<?php if (!empty($customer_group)) { ?><br /><b><?php echo $text_customer_group; ?></b> <?php echo $customer_group['name']; ?><?php } ?>
			          	<?php if (!empty($affiliate)) { ?><br /><b><?php echo $text_affiliate; ?></b> [#<?php echo $affiliate['affiliate_id']; ?>] <a href="mailto:<?php echo $affiliate['email']; ?>"><?php echo $affiliate['firstname'].' '.$affiliate['lastname']; ?></a><?php } ?>
			        </td>
				</tr>
				</tbody>
			</table>
		</td>
	</tr>
	<?php if (!empty($payment_address) || !empty($shipping_address)) { ?>
	<tr>
		<td bgcolor="#f6f6f6">
			<table cellpadding="5" cellspacing="0" width="100%" class="tableStack">
			<tbody>
				<tr>
					<?php if (!empty($payment_address)) { ?>
			    	<td class="address">
		    			<b><?php echo $text_new_payment_address; ?></b><br />
		    			<p><?php echo $payment_address; ?></p>
		    			<?php if ($payment_method) { ?><b><?php echo $text_new_payment_method; ?></b> <?php echo $payment_method; } ?>
			    	</td>
			    	<?php } ?>

			    	<?php if (!empty($shipping_address)) { ?>
			        <td class="address">
		        		<b><?php echo $text_new_shipping_address; ?></b><br />
		        		<p><?php echo $shipping_address; ?></p>
		        		<?php if ($shipping_method) { ?><b><?php echo $text_new_shipping_method; ?></b> <?php echo $shipping_method; } ?>
			        </td>
			        <?php } ?>
				</tr>
			</tbody>
			</table>
		</td>
	</tr>
	<?php } ?>
</tbody>
</table>

<table class="emailSpacer" cellpadding="0" cellspacing="0" width="100%"><tr><td height="15">&nbsp;</td></tr></table>
<?php } ?>

<?php if (!empty($products)) { ?>
<table cellpadding="5" cellspacing="0" width="100%" class="table1">
<thead>
	<tr>
        <th width="50%" bgcolor="#ededed"><b><?php echo $text_product; ?></b></th>
        <?php if ($config['table_quantity']) { ?>
        	<th width="10%" bgcolor="#ededed" align="center" class="textCenter"><b><?php echo $text_quantity; ?></b></th>
        <?php } ?>
        <th width="<?php if ($config['table_quantity']) { ?>20<?php } else { ?>25<?php } ?>%" bgcolor="#ededed" align="right" class="textRight"><b><?php echo $text_price; ?></b></th>
        <th width="<?php if ($config['table_quantity']) { ?>20<?php } else { ?>25<?php } ?>%" bgcolor="#ededed" align="right" class="textRight"><b><?php echo $text_total; ?></b></th>
	</tr>
</thead>
<tbody>
	<?php $i = 0; ?>
	<?php $colspan = ($config['table_quantity']) ? 3 : 2; ?>
	<?php foreach ($products as $product) { $row_style_background = ($i++ % 2) ? "#f6f6f6" : "#fafafa"; ?>
	    <tr>
			<td bgcolor="<?php echo $row_style_background; ?>">
				<?php if (!empty($product['image'])) { ?>
					<img src="<?php echo $product['image']; ?>" alt="" style="float:left; inline:inline; margin-right:5px;" />
				<?php } ?>

				<strong class="product-name"><?php echo $product['name']; ?></strong>

				<?php if (!empty($product['option'])) { ?>
				<br style="clear:both" />
				<p class="list-product-options">
					<?php foreach ($product['option'] as $option) { ?>
						&raquo; <strong><?php echo $option['name']; ?>:</strong>&nbsp;<?php echo $option['value']; ?><?php if ($option['price']) echo "&nbsp;(".$option['price'].")"; ?><br />
					<?php } ?>
				</p>
				<?php } ?>

				<?php if (!empty($product['model'])) { ?><br /><b><?php echo $text_model; ?>:</b> <?php echo $product['model']; ?><?php } ?>

				<?php if (!empty($product['sku'])) { ?><br /><b><?php echo $text_sku; ?>:</b> <?php echo $product['sku']; ?><?php } ?>

				<?php if (!empty($product['product_id'])) { ?><br /><b><?php echo $text_id; ?>:</b> <?php echo $product['product_id']; ?><?php } ?>

				<?php if (!empty($product['stock_quantity'])) { ?>
                  <div style="color: <?php if ($product['stock_quantity'] <= 0) { echo '#FF0000'; } elseif ($product['stock_quantity'] <= 5) { echo '#FFA500'; } else { echo '#008000'; }?>"><b><?php echo $text_stock_quantity; ?>:</b> <?php echo $product['stock_quantity']; ?></div>
                  <?php if ($product['stock_backorder']) { ?>
                    <div style="color: <?php if ($product['stock_backorder'] <= 0) { echo '#FF0000'; } elseif ($product['stock_backorder'] <= 5) { echo '#FFA500'; } else { echo '#008000'; }?>"><b><?php echo $text_backorder_quantity; ?>:</b> <?php echo $product['stock_backorder']; ?></div>
                  <?php } ?>
                <?php } ?>
			</td>
			<?php if ($config['table_quantity']) { ?>
			<td bgcolor="<?php echo $row_style_background; ?>" align="center" class="textCenter">
				<?php echo $product['quantity']; ?>
			</td>
			<td bgcolor="<?php echo $row_style_background; ?>" align="right" class="textRight price">
				<?php echo $product['price']; ?>
			</td>
			<?php } else { ?>
			<td bgcolor="<?php echo $row_style_background; ?>" align="right" class="textRight price">
				<?php if ($product['quantity'] > 1) { echo $product['quantity']; ?> <b>x</b> <?php } echo $product['price']; ?>
			</td>
			<?php } ?>
			<td bgcolor="<?php echo $row_style_background; ?>" align="right" class="textRight price">
				<?php echo $product['total']; ?>
			</td>
		</tr>
	<?php } ?>

	<?php if (!empty($vouchers)) { ?>
	<?php foreach ($vouchers as $voucher) { $row_style_background = ($i++ % 2) ? "#f6f6f6" : "#fafafa"; ?>
	<tr>
        <td bgcolor="<?php echo $row_style_background; ?>" colspan="<?php echo $colspan; ?>"><?php echo $voucher['description']; ?></td>
		<td bgcolor="<?php echo $row_style_background; ?>" align="right" class="textRight price"><?php echo $voucher['amount']; ?></td>
	</tr>
	<?php }
	} ?>
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

<?php if (!empty($order_link)) { ?>
	<div class="link"><b>{$text_order_link}</b><br /><span class="icon">&raquo;</span><a href="<?php echo $order_link; ?>" target="_blank"><b><?php echo $order_link; ?></b></a></div>
<?php } ?>

<div class="emailContent">{$emailtemplate.content2}</div>