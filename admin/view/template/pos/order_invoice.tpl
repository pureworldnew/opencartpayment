<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<link href="view/javascript/bootstrap/css/bootstrap.css" rel="stylesheet" media="screen" />
<link href="view/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
<link type="text/css" href="view/stylesheet/stylesheet.css" rel="stylesheet" media="screen" />
</head>
<body>
<div class="container">
  <div style="page-break-after: always;">
    <h1><?php echo $text_invoice; ?> #<span id="invoice_order_id"><?php echo $order['order_id']; ?></span></h1>
    <table class="table table-bordered">
      <thead>
        <tr>
          <td colspan="2"><?php echo $text_order_detail; ?></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td style="width: 50%;"><address>
            <strong><?php echo $order['store_name']; ?></strong><br />
            <?php echo $order['store_address']; ?>
            </address>
            <b><?php echo $text_telephone; ?></b> <?php echo $order['store_telephone']; ?><br />
            <?php if ($order['store_fax']) { ?>
            <b><?php echo $text_fax; ?></b> <?php echo $order['store_fax']; ?><br />
            <?php } ?>
            <b><?php echo $text_email; ?></b> <?php echo $order['store_email']; ?><br />
            <b><?php echo $text_website; ?></b> <a href="<?php echo $order['store_url']; ?>"><?php echo $order['store_url']; ?></a></td>
          <td style="width: 50%;"><b><?php echo $text_date_added; ?></b> <span id="invoice_date_added"><?php echo $order['date_added']; ?></span><br />
			<span id="invoice_invoice_no">
            <?php if ($order['invoice_no']) { ?>
            <b><?php echo $text_invoice_no; ?></b> <?php echo $order['invoice_no']; ?><br />
            <?php } ?>
			</span>
            <b><?php echo $text_order_id; ?></b> <span id="receipt_order_id_1"><?php echo $order['order_id']; ?></span><br />
            <b><?php echo $text_payment_method; ?></b> <span id="invoice_payment_method"><?php echo $order['payment_method']; ?></span><br />
            <?php if ($order['shipping_method']) { ?>
            <b><?php echo $text_shipping_method; ?></b> <span id="invoice_shipping_method"><?php echo $order['shipping_method']; ?></span><br />
            <?php } ?></td>
        </tr>
      </tbody>
    </table>
    <table class="table table-bordered">
      <thead>
        <tr>
          <td style="width: 50%;"><b><?php echo $text_to; ?></b></td>
          <td style="width: 50%;"><b><?php echo $text_ship_to; ?></b></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><address id="invoice_payment_address">
            <?php echo $order['payment_address']; ?>
            </address></td>
          <td><address id="invoice_shipping_address">
            <?php echo $order['shipping_address']; ?>
            </address></td>
        </tr>
      </tbody>
    </table>
    <table class="table table-bordered">
      <thead>
        <tr>
          <td><b><?php echo $column_product; ?></b></td>
          <td><b><?php echo $column_model; ?></b></td>
          <td class="text-right"><b><?php echo $column_quantity; ?></b></td>
          <td class="text-right"><b><?php echo $column_price; ?></b></td>
          <td class="text-right"><b><?php echo $column_total; ?></b></td>
        </tr>
      </thead>
      <tbody id="invoice_products">
        <?php foreach ($order['product'] as $product) { ?>
        <tr>
          <td><?php echo $product['name']; ?>
            <?php foreach ($product['option'] as $option) { ?>
            <br />
            &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
            <?php } ?>
			<?php if (!empty($product['weight_price'])) { ?>
			<br />
			&nbsp;<small> - <?php echo $product['weight_name']; ?>: <?php echo $product['weight']; ?></small>
			<?php }?>
			<?php if (!empty($product['sns'])) { foreach ($product['sns'] as $product_sn) { ?>
			<br />
			&nbsp;<small> - SN: <?php echo $product_sn['sn']; ?></small>
			<?php }}?>
		  </td>
          <td><?php echo $product['model']; ?></td>
          <td class="text-right"><?php echo $product['quantity']; ?></td>
          <td class="text-right"><?php echo $product['price']; ?></td>
          <td class="text-right"><?php echo $product['total']; ?></td>
        </tr>
        <?php } ?>
	  </tbody>
	  <tobdy id="invoice_totals">
        <?php foreach ($order['total'] as $total) { ?>
        <tr>
          <td class="text-right" colspan="4"><b><?php echo $total['title']; ?></b></td>
          <td class="text-right"><?php echo $total['text']; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
	<?php if (!empty($payments)) { ?>
	<table class="table table-bordered">
		<thead>
		<tr>
			<td class="text-left" width="40%"><b><?php echo $column_type; ?></b></td>
			<td class="text-right" width="15%"><b><?php echo $column_amount; ?></b>&nbsp;</td>
			<td class="text-left" width="45%"><b><?php echo $column_note; ?></b></td>
		</tr>
		</thead>
		<tbody id="receipt_payments">
		<?php foreach ($payments as $payment) { if ((float)$payment['amount_float'] > 0) { ?>
		<tr>
			<td class="text-left"><?php echo $payment['type']; ?></td>
			<td class="text-right"><?php echo $payment['amount']; ?>&nbsp;</td>
			<td class="text-left"><?php echo (''==$payment['note'])?'&nbsp;':$payment['note']; ?></td>
		</tr>
		<?php }} ?>
		<tbody id="receipt_change">
		<?php if(isset($change)) { ?>
		<tr>
			<td class="text-left"><?php echo $text_change; ?></td>
			<td class="text-right"><?php echo $change; ?>&nbsp;</td>
			<td class="text-left">&nbsp;</td>
		</tr>
		</tbody>
		<?php } ?>
		</tbody>
	</table>
	<?php } ?>
	<div id="invoice_comment">
    <?php if ($order['comment']) { ?>
    <table class="table table-bordered">
      <thead>
        <tr>
          <td><b><?php echo $column_comment; ?></b></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php echo $order['comment']; ?></td>
        </tr>
      </tbody>
    </table>
    <?php } ?>
	</div>
  </div>
</div>
</body>
</html>