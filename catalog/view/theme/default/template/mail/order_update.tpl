<div class="emailContent">{$emailtemplate.content1}</div>

<?php if (isset($order_status)) { ?>
<br /><?php echo $text_update_order_status; ?><br />
<b><?php echo $order_status; ?></b><br />
<?php } ?>

<?php if (isset($order_url)) { ?>
<br />
<div class="link">
	<b><?php echo $text_update_link; ?></b><br />
	<span>&raquo; </span>
	<a href="<?php echo $order_url_tracking; ?>" target="_blank">
		<b><?php echo $order_url; ?></b>
	</a>
</div>
<?php } ?>

<?php if (!empty($comment)) { ?>
<br /><b><?php echo $text_update_comment; ?></b><br />
<div><?php echo $comment; ?></div>
<?php } ?>

<?php if (!empty($products) || !empty($vouchers)) { ?>
<table class="emailSpacer" cellpadding="0" cellspacing="0" width="100%"><tr><td height="15">&nbsp;</td></tr></table>

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
	<?php if (!empty($products)) { ?>
	<?php foreach ($products as $product) { $row_style_background = ($i++ % 2) ? "#f6f6f6" : "#fafafa"; ?>
	    <tr>
			<td bgcolor="<?php echo $row_style_background; ?>">
				<?php if ($product['image']) { ?>
					<a href="<?php echo $product['url_tracking']; ?>">
						<img class="productImage" src="<?php echo $product['image']; ?>" width="50" height="50" alt="" style="float: left; margin-right: 5px;" />
					</a>
				<?php } ?>

				<a href="<?php echo $product['url_tracking']; ?>"><?php echo $product['name']; ?></a>

				<?php if (!empty($product['option'])) { ?>
				<br style="clear:both" />
				<p class="list-product-options">
					<?php foreach ($product['option'] as $option) { ?>
						&raquo; <strong><?php echo $option['name']; ?>:</strong>&nbsp;<?php echo $option['value']; ?><?php if ($option['price']) echo "&nbsp;(".$option['price'].")"; ?><br />
					<?php } ?>
				</p>
				<?php } ?>

				<?php if ($product['model']) { ?><br /><span class="list-product-options"><b><?php echo $text_model; ?>:</b>&nbsp;<?php echo $product['model']; ?></span><?php } ?>
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

<br />
<div class="emailContent">{$emailtemplate.content2}</div>