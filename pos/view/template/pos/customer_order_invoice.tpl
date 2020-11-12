<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $language; ?>" xml:lang="<?php echo $language; ?>">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />

<style type="text/css">
#title {
	position:absolute;
	right:30pt;
  top:45pt;
	text-transform:uppercase;
	font-size: 24px;
	font-weight: normal;
	color: #ccc;
}
#logo {
	margin-bottom: 12px;
}
img.center {
    display: block;
    margin: 0 auto;
}
.list {
	border-collapse: collapse;
	width: 100%;
	border: 1px solid #aaa;
	margin-bottom: 20px;
}
.list td {
	padding: 7px;
	border: 1px solid #ddd;
}
.list thead td {
	background-color: #efefef;
	color: #000;
	font-weight: bold;
}
.list tbody td {
	vertical-align: top;
}
.rtl{text-align:right}
.rtl .right, .rtl .left{text-align:left}
.ltr .right, .rtl .left{text-align:right}
.center{text-align:center}
b,strong {visibility: visible;display:inline-block;color:#000000}

.text-center{
  text-align: center;
}

html,body{
  font-family: 'Open Sans', 'sans-serif';
  height: 100%;
  width:90%;
  margin: 10px auto;
  padding: 0;
  font-size: 12px;
  color: #666666;
}

body{
  line-height: 1.42857143;
  background-color: #fff;
}

p{
  margin: 0 0 10px;
}

table{
  background-color: transparent;
  border-spacing: 0;
  border-collapse: collapse;
}
</style>
</head>
<body>
<div class="container">
  <div>
    <div id="logo"><img src="<?php echo $logo; ?>" class="center"></div>
    <p style="font-size:16px;" class="text-center"><?php echo str_replace("Bella Findings | ", "", strip_tags($order['store_address'])); ?></p>
    <p style="font-size:16px;" class="text-center"><strong>Tel:</strong> <?php echo $order['store_telephone']; ?> | <?php if ($order['store_fax']) { echo "<strong>" . $text_fax. "</strong>"; ?>: <?php echo $order['store_fax']; } ?> |  <?php echo "<strong>" .$text_email . "</strong>"; ?>: <?php echo $order['store_email']; ?></p>
    <table class="list">
      <thead>
        <tr>
          <td colspan="2">Invoice #<?php echo $order['order_id']; ?></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td style="width: 40%;">
            <b><?php echo $text_order_status; ?></b><?php echo $order['order_status']; ?><br />
            <b><?php echo $text_order_date; ?></b> <span id="invoice_date_added"><?php echo $order['date_added']; ?></span><br />
            <?php if ($order['shipping_method']) { ?>
              <b><?php echo $text_shipping_method; ?></b> <span id="invoice_shipping_method"><?php echo $order['shipping_method']; ?></span><br />
            <?php } ?>
          </td>
          <td style="width: 60%;">
            <table width="100%" class="table table-bordered table-condensed">
                <tr style="border-bottom: 1px solid #000000;">
                  <td align="left" width="40%">Payment Type</td>
                  <td align="right" width="10%"><?php echo $column_amount; ?></td>
                  <td align="right" width="50%"><?php echo $column_note; ?></td>
                </tr>
                <tbody id="receipt_payments">
                <?php if($payments) { 
                        foreach ($payments as $payment) { 
                          if ((float)$payment['amount_float'] > 0) { ?>
                <tr>
                  <td align="left" valign="top"><?php echo $payment['type']; ?></td>
                  <td align="right" valign="top"><?php echo $payment['amount']; ?>&nbsp;</td>
                  <td align="right" valign="top"><?php echo (''==$payment['note'])?'&nbsp;':$payment['note']; ?></td>
                </tr>
                <?php   } 
                  }
                 } else { ?>
                    <tr><td align="left" valign="top" colspan="3">N/A</td></tr>
                 <?php } ?>
                </tbody>
                <tbody id="receipt_change">
                <?php if(isset($change)) { ?>
                <tr>
                  <td align="left" valign="top"><?php echo $text_change; ?></td>
                  <td align="right" valign="top"><?php echo $change; ?>&nbsp;</td>
                  <td align="right" valign="top">&nbsp;</td>
                </tr>
                <?php } ?>
                </tbody>
                      <tbody id="resale_text">
                <?php if(!empty($order['resale_text'])) { ?>
                <tr>
                  <td align="left" valign="top" colspan="2">Resale ID #</td>
                  <td align="right" valign="top"><?php echo $order['resale_text']; ?></td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
          </td>
        </tr>
      </tbody>
    </table>
    <table class="list">
      
        <tr>
          <td style="width: 30%;"><b><?php echo $text_to; ?></b></td>
          <td style="width: 70%;"><?php echo str_replace(["<br>", "\n","<br />", "\n\n"], ", ", $order['payment_address']); ?></td>
        </tr>
        <tr>
          <td style="width: 30%;"><b><?php echo $text_ship_to; ?></b></td>
          <td style="width: 70%;"><?php echo str_replace(["<br>", "\n","<br />", "\n\n"], ", ", $order['shipping_address']); ?></td>
        </tr>
    </table>
    <table class="list">
      <thead>
        <tr>
          <td></td>
          <td><b>Product Name</b></td>
          <td><b>Model</b></td>
          <td class="text-right"><b><?php echo $column_quantity; ?></b></td>
          <td class="text-right"><b><?php echo $column_price; ?></b></td>
          <td class="text-right"><b><?php echo $column_total; ?></b></td>
        </tr>
      </thead>
      <tbody id="invoice_products">
        <?php foreach ($order['product'] as $product) { ?>
        <tr>
          <td><img src="<?php echo $product['image'] ?>" alt=""/></td>
          <td><?php echo $product['name']; ?>
            <?php if ( !empty($product['option']) ) { 
              foreach ($product['option'] as $k => $option) { 
                if($k == 0)
                { ?>
                    <br />
                    &nbsp;<strong> -</strong>
                <?php } ?>
            
            &nbsp;<strong><?php echo $option['name']; ?>:</strong> <small><?php echo $option['value']; ?></small>
            <?php if ( $k != count($product['option']) - 1 ) { echo ","; } 
              } } ?>
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
          <td class="text-right" colspan="5"><b><?php echo $total['title']; ?></b></td>
          <td class="text-right"><?php echo $total['text']; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
	
	<div id="invoice_comment">
    <?php if ($order['comment']) { ?>
    <table class="list">
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
  <p><?php echo nl2br($term_n_cond); ?></p>
  <p class="center"><?php echo $user_info; ?></p>
</div>
</body>
</html>
