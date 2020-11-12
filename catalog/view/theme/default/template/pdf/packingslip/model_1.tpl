<?php if(isset($_newpage)) { ?><pagebreak /><?php } ?>
<style type="text/css">
@page{
	margin: 12mm;
	footer: html_footer;
}
#footer{
	font-size:11px;
	text-align: center;
	color: <?php echo $config->get('pdf_invoice_color_footertxt') ? $config->get('pdf_invoice_color_footertxt') : '#777'; ?>;
	padding-top: 5px;
	border-top: 1px solid <?php echo $config->get('pdf_invoice_color_tborder') ? $config->get('pdf_invoice_color_tborder') : '#cddddd'; ?>;
}
body, table{
	direction:<?php echo $direction; ?>;
	font-family: dejavusanscondensed, sans-serif;
	font-size: 11px;
	color: <?php echo $config->get('pdf_invoice_color_text') ? $config->get('pdf_invoice_color_text') : '#000'; ?>;
}
.comment p{
	margin:0
}
#head{
	width: 100%;
	border-bottom: 1px solid <?php echo $config->get('pdf_invoice_color_tborder') ? $config->get('pdf_invoice_color_tborder') : '#cddddd'; ?>;
	margin-bottom: 15px;
	padding-bottom: 5px;
}
#head td.title{
	vertical-align:bottom;
	text-transform: uppercase;
	color: <?php echo $config->get('pdf_invoice_color_title') ? $config->get('pdf_invoice_color_title') : '#ccc'; ?>;
	text-align: right;
	font-size: 28px;
	font-weight: normal;
}
.store {
	width: 100%;
	margin-bottom: 20px;
}
.store td{
	width:50%;
}
.div2 {
	float: left;
	display: inline-block;
}
.div3 {
	float: right;
	display: inline-block;
	padding: 5px;
}
.heading td, thead td {
	background: <?php echo $config->get('pdf_invoice_color_tborder') ? $config->get('pdf_invoice_color_tborder') : '#E7EFEF'; ?>;
	font-weight:bold;
}
.address, .product {
	border-collapse: collapse;
}
.address {
	width: 100%;
	margin-bottom: 20px;
	border-top: 1px solid <?php echo $config->get('pdf_invoice_color_tborder') ? $config->get('pdf_invoice_color_tborder') : '#cddddd'; ?>;
	border-right: 1px solid <?php echo $config->get('pdf_invoice_color_tborder') ? $config->get('pdf_invoice_color_tborder') : '#cddddd'; ?>;
}
.address th, .address td {
	border-left: 1px solid <?php echo $config->get('pdf_invoice_color_tborder') ? $config->get('pdf_invoice_color_tborder') : '#cddddd'; ?>;
	border-bottom: 1px solid <?php echo $config->get('pdf_invoice_color_tborder') ? $config->get('pdf_invoice_color_tborder') : '#cddddd'; ?>;
	padding: 5px;
	vertical-align: text-bottom;
}
.address td {
	width: 50%;
}
.product {
	width: 100%;
	margin-bottom: 20px;
	border-top: 1px solid <?php echo $config->get('pdf_invoice_color_tborder') ? $config->get('pdf_invoice_color_tborder') : '#cddddd'; ?>;
	border-right: 1px solid <?php echo $config->get('pdf_invoice_color_tborder') ? $config->get('pdf_invoice_color_tborder') : '#cddddd'; ?>;
}
.product td {
	border-left: 1px solid <?php echo $config->get('pdf_invoice_color_tborder') ? $config->get('pdf_invoice_color_tborder') : '#cddddd'; ?>;
	border-bottom: 1px solid <?php echo $config->get('pdf_invoice_color_tborder') ? $config->get('pdf_invoice_color_tborder') : '#cddddd'; ?>;
	padding: 5px;
}
.comment{
	width: 100%;
	border-collapse: collapse;
	margin-bottom: 20px;
}
.comment td{
	padding: 5px;
	border: 1px solid <?php echo $config->get('pdf_invoice_color_tborder') ? $config->get('pdf_invoice_color_tborder') : '#cddddd'; ?>;
}
.rtl{text-align:right}
.rtl .right, .ltr .left{text-align:left}
.ltr .right, .rtl .left{text-align:right}
.center{text-align:center}
</style>
<div class="<?php echo $direction; ?>">
	<table id="head">
		<tr>
			<td><?php if ($logo && $config->get('pdf_invoice_sliplogo')) { ?><div id="logo"><a href="<?php echo $store_url; ?>"><img src="<?php echo $logo; ?>"/></a></div><?php } ?></td>
			<td class="title"><?php echo $language->get('text_packingslip'); ?></td>
		</tr>
	</table>
  <table class="store">
    <tr>
      <td>
        <?php if ($shipping_address) { ?>
          <b><?php echo $text_shipping_address; ?></b><br/><br/>
          <?php echo $shipping_address ?>
          <?php echo $telephone; ?>
        <?php } ?>
      </td>
      <td align="right" valign="top"><table>
          <tr>
            <td><b><?php echo $text_date_added; ?></b></td>
            <td><?php echo $date_added; ?></td>
          </tr>
		  <?php if($date_due){ ?>
		  <tr>
            <td><b><?php echo $text_date_due; ?></b></td>
            <td><?php echo $date_due; ?></td>
          </tr>
		  <?php } ?>
		  <?php if($invoice_no){ ?>
		  <tr>
            <td><b><?php echo $text_invoice_no; ?></b></td>
            <td><?php echo $invoice_prefix . $invoice_no; ?></td>
          </tr>
		  <?php } ?>
          <tr>
            <td><b><?php echo $text_order_id; ?></b></td>
            <td><?php echo $order['order_id']; ?></td>
          </tr>
		   <?php if($customer_id){ ?>
		  <tr>
            <td><b><?php echo $text_customer_id; ?></b></td>
            <td><?php echo $customer_id; ?></td>
          </tr>
		  <?php } ?>
          <tr>
            <td><b><?php echo $text_payment_method; ?></b></td>
            <td><?php echo $order['payment_method']; ?></td>
          </tr>
          <?php if ($order['shipping_method']) { ?>
          <tr>
            <td><b><?php echo $text_shipping_method; ?></b></td>
            <td><?php echo $order['shipping_method']; ?></td>
          </tr>
          <?php } ?>
        </table>
        <?php if (!empty($barcode['status'])) { ?>
          <table style="width:100%;text-align:right;margin-top:10px;"><tr><td style="border:0;padding:0">
          <barcode type="<?php echo $barcode['type']; ?>" code="<?php echo $barcode['value']; ?>"/>
          </td></tr></table>
          <?php } ?>
        </td>
    </tr>
  </table>
  <?php foreach($blocks_top as $block) { ?>
  <table class="comment list">
    <thead><tr><td><?php echo $block['title']; ?></td></tr></thead>
    <tbody><tr><td><?php echo $block['description']; ?></td></tr></tbody>
  </table>
  <?php } ?>

  <?php foreach($blocks_middle as $block) { ?>
  <table class="comment list">
    <thead><tr><td><?php echo $block['title']; ?></td></tr></thead>
    <tbody><tr><td><?php echo $block['description']; ?></td></tr></tbody>
  </table>
  <?php } ?>
  <table class="product">
    <tr class="heading">
		<?php foreach ($columns as $col) { ?>
			<td><?php echo $language->get('column_'.$col); ?></td>
		<?php } ?>
    </tr>
    <?php foreach ($products as $product) { ?>
	<tr>
		<?php foreach ($columns as $col) { ?>
			<td <?php if(in_array($col, array('weight', 'quantity', 'price', 'tax'))){ ?>class="right"<?php } ?> <?php if(in_array($col, array('image', 'weight', 'quantity', 'price', 'tax'))){ ?>style="width:1px"<?php } ?>>
				<?php if($col == 'product'){ ?>
					<?php if(isset($prod_options['quantity'])){echo $product['quantity'].' x ';} ?><?php echo $product['name']; ?>
					<?php foreach ($product['option'] as $option) { ?>
					<br />
					&nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
					<?php } ?>
				<?php }elseif($col == 'image' && $product['image']){ ?>
					<img src="<?php echo $product['image'] ?>" alt=""/>
				<?php }else{ ?>
					<?php echo isset($product[$col]) ? $product[$col] : ''; ?>
				<?php } ?>
			</td>
		<?php } ?>
	</tr>
    <?php } ?>
  </table>
  <?php if ($comment) { ?>
  <table class="comment">
    <tr class="heading">
      <td><b><?php echo $text_instruction; ?></b></td>
    </tr>
    <tr>
      <td><?php echo $comment; ?></td>
    </tr>
  </table>
  <?php } ?>
  <?php if ($config->get('pdf_invoice_slip_summary')) { ?>
  <table class="comment">
    <tr class="heading">
      <td colspan="2"><b><?php echo $language->get('text_slip_summary'); ?></b></td>
    </tr>
    <tr>
      <td><?php echo $language->get('text_slip_total_items'); ?>: <b><?php echo $total_items; ?></b></td>
      <td><?php echo $language->get('text_slip_total_weight'); ?>: <b><?php echo $total_weight; ?></b></td>
    </tr>
  </table>
  <?php } ?>
  <?php foreach($blocks_bottom as $block) { ?>
  <table class="comment list">
    <thead><tr><td><?php echo $block['title']; ?></td></tr></thead>
    <tbody><tr><td><?php echo $block['description']; ?></td></tr></tbody>
  </table>
  <?php } ?>
  <?php foreach($blocks_newpage as $block) { ?>
  <pagebreak />
  <table class="comment list">
    <thead><tr><td><?php echo $block['title']; ?></td></tr></thead>
    <tbody><tr><td><?php echo $block['description']; ?></td></tr></tbody>
  </table>
  <?php } ?>
</div>
<?php if($config->get('pdf_invoice_footer_'.$lang_code)) { ?>
<htmlpagefooter name="footer" style="display:none">
  <div id="footer"><?php echo html_entity_decode($config->get('pdf_invoice_footer_'.$lang_id), ENT_QUOTES, 'UTF-8'); ?></div>
</htmlpagefooter>
<?php } ?>