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
			<td><?php if ($logo) { ?><div id="logo"><a href="<?php echo $store_url; ?>"><img src="<?php echo $logo; ?>"/></a></div><?php } ?></td>
			<td class="title"><?php echo $language->get('text_return'); ?></td>
		</tr>
	</table>
  <table class="store">
    <tr>
      <td><?php echo $store_name; ?><br />
        <?php echo $store_address; ?><br />
		<?php if ($config->get('pdf_invoice_vat_number')) { ?><?php echo $language->get('text_store_vat'); ?> <?php echo $config->get('pdf_invoice_vat_number'); ?><br /><?php } ?>
		<?php if ($config->get('pdf_invoice_company_id')) { ?><?php echo $language->get('text_store_company'); ?> <?php echo $config->get('pdf_invoice_company_id'); ?><br /><?php } ?>
        <?php echo $text_telephone; ?> <?php echo $store_telephone; ?><br />
        <?php if ($store_fax) { ?>
        <?php echo $text_fax; ?> <?php echo $store_fax; ?><br />
        <?php } ?>
        <?php echo $store_email; ?><br />
        <?php echo $store_url; ?></td>
      <td align="right" valign="top"><table>
          <tr>
            <td><b><?php echo $language->get('text_return_id'); ?>:</b></td>
            <td><?php echo $return['return_id']; ?></td>
          </tr>
          <tr>
            <td><b><?php echo $language->get('text_order_id'); ?></b></td>
            <td><?php echo $return['order_id']; ?></td>
          </tr>
          <tr>
            <td><b><?php echo $text_date_added; ?></b></td>
            <td><?php echo $return['date_added']; ?></td>
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
        </td>
    </tr>
  </table>
  <table class="address">
    <tr class="heading">
      <td><?php echo $text_payment_address; ?></td>
      <?php if ($shipping_address) { ?>
	  <td width="50%"><?php echo $text_shipping_address; ?></td>
	  <?php } ?>
    </tr>
    <tr>
      <td><?php echo $payment_address; ?><br/>
        <?php echo $email; ?><br/>
        <?php echo $telephone; ?>
        <?php if ($payment_company_id) { ?>
        <br/>
        <br/>
        <?php echo $language->get('text_company_id'); ?> <?php echo $payment_company_id; ?>
        <?php } ?>
        <?php if ($payment_tax_id) { ?>
        <br/>
        <?php echo $language->get('text_tax_id'); ?> <?php echo $payment_tax_id; ?>
        <?php } ?></td>
      <?php if ($shipping_address) { ?>
		<td><?php echo $shipping_address; ?></td>
	  <?php } ?>
    </tr>
  </table>
  <table class="address">
    <tr class="heading">
      <td><?php echo $language->get('text_return_info'); ?></td>
      <td width="50%"><?php echo $language->get('text_return_product'); ?></td>
    </tr>
    <tr>
      <td>
        <b><?php echo $language->get('text_return_reason'); ?>:</b> <?php echo $return['return_reason']; ?><br/><br/>
        <b><?php echo $language->get('text_return_action'); ?>:</b> <?php echo $return['return_action']; ?><br/><br/>
        <b><?php echo $language->get('text_return_status'); ?>:</b> <?php echo $return['return_status']; ?>
      </td>
      <td>
        <b><?php echo $language->get('column_product'); ?>:</b> <?php echo $return['product']; ?><br/><br/>
        <b><?php echo $language->get('column_model'); ?>:</b> <?php echo $return['model']; ?><br/><br/>
        <b><?php echo $language->get('column_quantity'); ?>:</b> <?php echo $return['quantity']; ?>
      </td>
    </tr>
  </table>
  <?php if ($return['comment']) { ?>
  <table class="comment">
    <tr class="heading">
      <td><b><?php echo $language->get('text_return_comment'); ?></b></td>
    </tr>
    <tr>
      <td><?php echo nl2br($return['comment']); ?></td>
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
</div>
<?php if($config->get('pdf_invoice_footer_'.$lang_code)) { ?>
<htmlpagefooter name="footer" style="display:none">
  <div id="footer"><?php echo html_entity_decode($config->get('pdf_invoice_footer_'.$lang_id), ENT_QUOTES, 'UTF-8'); ?></div>
</htmlpagefooter>
<?php } ?>