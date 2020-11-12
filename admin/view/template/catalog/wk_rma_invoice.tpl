<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $language; ?>" xml:lang="<?php echo $language; ?>">
<head>
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<link href="view/javascript/bootstrap/css/bootstrap.css" rel="stylesheet" media="screen" />
<link href="view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="//fonts.googleapis.com/css?family=Open+Sans:400,400i,300,700" rel="stylesheet" type="text/css" />
<link href="view/stylesheet/stylesheet.css" rel="stylesheet">
</head>
<style type="text/css">
td img{
  border: 1px solid #D2DADA;
}
.logo{
  background: url('<?php echo $logo; ?>');
  display: block;
  width: 200px;
  height: 200px;
  position: absolute;
  top: -130px;
}
</style>
<body style="padding: 6%;">
<?php foreach ($orders as $order) { ?>
<div style="page-break-after: always;position:relative;" >
  <span class="logo"></span>
  <h1 class="text-right" style="text-align:center;"><?php echo $text_invoice; ?></h1>
  <table class="table table-bordered table-hover">
    <tr>
      <td style="width:30%;vertical-align: top;" >
        <?php echo $order['store_name']; ?><br />
        <?php echo $order['store_address']; ?><br />
        <?php echo $text_telephone; ?> <?php echo $order['store_telephone']; ?><br />
        <?php if ($order['store_fax']) { ?>
        <?php echo $text_fax; ?> <?php echo $order['store_fax']; ?><br />
        <?php } ?>
        <?php echo $order['store_email']; ?><br />
        <?php echo $order['store_url']; ?></td>
      <td align="left" style="width:40%;vertical-align: top;">
        <table>
          <tr>
            <td><b><?php echo $text_date_added; ?></b></td>
            <td width="50%"><?php echo $order['date_added_rma']; ?></td>
          </tr>
          <tr>
            <td><b><?php echo $text_rmaid; ?> :</b></td>
            <td width="50%"><?php echo $order['id']; ?></td>
          </tr>
          <tr>
            <td><b><?php echo $text_rma_status; ?> :</b></td>
            <td width="50%" style="color:<?php echo $order['color'];?>;font-size:bold;"><?php echo $order['rma_status']; ?></td>
          </tr>
          <?php if($order['tracking']) { ?>
          <tr>
            <td><b><?php echo $text_customer_tracking; ?></b></td>
            <td width="50%"><?php echo $order['tracking']; ?></td>
          </tr>
          <?php } ?>
        </table>
        </td>
        <td align="left" style="vertical-align: top;">
          <table>
              <tr>
                <td><b><?php echo $text_date_added; ?></b></td>
                <td width="50%"><?php echo $order['date_added']; ?></td>
              </tr>
              <?php if ($order['invoice_no']) { ?>
              <tr>
                <td><b><?php echo $text_invoice_no; ?></b></td>
                <td width="50%"><?php echo $order['invoice_no']; ?></td>
              </tr>
              <?php } ?>
              <tr>
                <td><b><?php echo $text_order_id; ?></b></td>
                <td width="50%"><?php echo $order['order_id']; ?></td>
              </tr>
              <tr>
                <td><b><?php echo $text_payment_method; ?></b></td>
                <td width="50%"><?php echo $order['payment_method']; ?></td>
              </tr>
              <?php if ($order['shipping_method']) { ?>
              <tr>
                <td><b><?php echo $text_shipping_method; ?></b></td>
                <td width="50%"><?php echo $order['shipping_method']; ?></td>
              </tr>
          <?php } ?>
        </table>
       </td>
       </tr>
     </table>
    </td>
    </tr>
  </table>

  <table class="table table-bordered table-hover">
    <tr class="heading">
      <td width="50%"><b><?php echo $text_to; ?></b></td>
      <td width="50%"><b><?php echo $text_ship_to; ?></b></td>
    </tr>
    <tr>
      <td><?php echo $order['payment_address']; ?><br/>
        <?php echo $order['email']; ?><br/>
        <?php echo $order['telephone']; ?></td>
      <td><?php echo $order['shipping_address']; ?></td>
    </tr>
  </table>

  <table class="table table-bordered table-hover">
    <tr class="heading">
      <td><b><?php echo $column_product; ?></b></td>
      <td><b><?php echo $column_model; ?></b></td>
      <td align="right"><b><?php echo $column_quantity; ?></b></td>
      <td align="right"><b><?php echo $column_price; ?></b></td>
      <td align="right"><b><?php echo $text_reason; ?></b></td>
      <td align="right"><b><?php echo $column_total; ?></b></td>
    </tr>
    <?php foreach ($order['product'] as $product) { ?>
    <tr>
      <td><?php echo $product['name']; ?>
        <?php foreach ($product['option'] as $option) { ?>
        <br />
        &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
        <?php } ?></td>
      <td><?php echo $product['model']; ?></td>
      <td align="right"><?php echo $product['quantity']; ?></td>
      <td align="right"><?php echo $product['price']; ?></td>
      <td align="right"><?php echo $product['reason']; ?></td>
      <td align="right"><?php echo $product['total']; ?></td>
    </tr>
    <?php } ?>

    <?php foreach ($order['voucher'] as $voucher) { ?>
    <tr>
      <td align="left"><?php echo $voucher['description']; ?></td>
      <td align="left"></td>
      <td align="right">1</td>
      <td align="right"><?php echo $voucher['amount']; ?></td>
      <td align="right"><?php echo $voucher['amount']; ?></td>
    </tr>
    <?php } ?>
    <?php foreach ($order['total'] as $total) { ?>
    <tr>
      <td align="right" colspan="5"><b><?php echo $total['title']; ?>:</b></td>
      <td align="right"><?php echo $total['text']; ?></td>
    </tr>
    <?php } ?>
    <?php if(!$returned_check){ ?>
      <tr>
        <td align="left"><?php echo $text_admin_return_info; ?></td>
        <td align="right" colspan="5"><a class="btn btn-danger" href="<?php echo $order['return_qty']; ?>" ><i class="fa fa-reply"></i> <?php echo $text_admin_return; ?></a></td>
      </tr>
    <?php } ?>
  </table>

  <table class="table table-bordered table-hover">
    <tr class="heading">
      <td width="50%"><b><?php echo $text_addinfo; ?></b></td>
    </tr>
    <tr>
      <td><?php echo $order['add_info']; ?></td>
    </tr>
  </table>

  <?php if($order['images']){ ?>
  <table class="table table-bordered table-hover">
    <tr class="heading">
      <td width="50%"><b><?php echo $text_images; ?></b></td>
    </tr>
    <tr>
      <td>
          <?php foreach($order['images'] as $images){ ?>
            <?php if($images){ ?>
              <img src="<?php echo $images; ?>"> &nbsp;
            <?php } ?>
          <?php } ?>
      </td>
    </tr>
  </table>
  <?php } ?>

  <?php if ($order['comment']) { ?>
  <table class="table table-bordered table-hover">
    <tr class="heading">
      <td><b><?php echo $column_comment; ?></b></td>
    </tr>
    <tr>
      <td><?php echo $order['comment']; ?></td>
    </tr>
  </table>
  <?php } ?>
</div>
<br/><br/><br/><br/>
<?php } ?>
</body>
</html>
