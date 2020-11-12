<?php if(isset($_newpage)) { ?><pagebreak /><?php } ?>
<style type="text/css">
@page{
	margin: 12mm;
	footer: html_footer;
}
#footer{
	width:100%;
	font-size:11px;
	color: <?php echo $config->get('pdf_invoice_color_footertxt') ? $config->get('pdf_invoice_color_footertxt') : '#777'; ?>;
	padding-top: 5px;
	border-top: 1px solid <?php echo $config->get('pdf_invoice_color_tborder') ? $config->get('pdf_invoice_color_tborder') : '#aaa'; ?>;
}
body{
	direction:<?php echo $direction; ?>;
	font-family: dejavusanscondensed, sans-serif;
	font-size: 12px;
	color: <?php echo $config->get('pdf_invoice_color_text') ? $config->get('pdf_invoice_color_text') : '#000'; ?>;
}
.comment p{
	margin:0
}
#title {
	position:absolute;
	<?php echo $direction == 'rtl' ? 'left':'right'; ?>:50pt;
	text-transform:uppercase;
	font-size: 24px;
	font-weight: normal;
	color: <?php echo $config->get('pdf_invoice_color_title') ? $config->get('pdf_invoice_color_title') : '#ccc'; ?>
}
#logo {
  width:100%;
	margin-bottom: 20px;
	padding-bottom: 10px;
  border-bottom:1px solid <?php echo $config->get('pdf_invoice_color_tborder') ? $config->get('pdf_invoice_color_tborder') : '#000'; ?>;
}
.list {
	border-collapse: collapse;
	width: 100%;
	border: 1px solid <?php echo $config->get('pdf_invoice_color_tborder') ? $config->get('pdf_invoice_color_tborder') : '#aaa'; ?>;
	margin-bottom: 20px;
}
.list td {
	padding: 7px;
	border: 1px solid <?php echo $config->get('pdf_invoice_color_tborder') ? $config->get('pdf_invoice_color_tborder') : '#ddd'; ?>;
}
.list thead td {
	background-color: <?php echo $config->get('pdf_invoice_color_thead') ? $config->get('pdf_invoice_color_thead') : '#efefef'; ?>;
	color: <?php echo $config->get('pdf_invoice_color_theadtxt') ? $config->get('pdf_invoice_color_theadtxt') : '#000'; ?>;
	font-weight: bold;
}
.list tbody td {
	vertical-align: top;
}
.products{
  border-collapse:collapse;
  width:100%;
  margin-bottom: 20px;
}
.products thead td{
  font-weight:bold;
  border-top:2px solid <?php echo $config->get('pdf_invoice_color_tborder') ? $config->get('pdf_invoice_color_tborder') : '#999'; ?>;
  border-bottom:2px solid <?php echo $config->get('pdf_invoice_color_tborder') ? $config->get('pdf_invoice_color_tborder') : '#999'; ?>;
}
.products td{
  border-bottom:1px solid <?php echo $config->get('pdf_invoice_color_tborder') ? $config->get('pdf_invoice_color_tborder') : '#999'; ?>;
}
.store{
  border-collapse:collapse;
  width:100%;
  margin-bottom: 20px;
}
.store td{
  vertical-align:top;
}
.table{
  border-collapse:collapse;
  width:100%;
  margin-bottom: 20px;
}
.table td{
  padding: 5px 5px;
}
.lines h3{
  margin-bottom:10px;
}
h3,b,thead td{
  color:#333;
}
.lines{
  vertical-align:top;
  border-top:1px solid <?php echo $config->get('pdf_invoice_color_tborder') ? $config->get('pdf_invoice_color_tborder') : '#999'; ?>;
  border-bottom:1px solid <?php echo $config->get('pdf_invoice_color_tborder') ? $config->get('pdf_invoice_color_tborder') : '#999'; ?>;
}
.bottomlines{
  vertical-align:top;
  border-bottom:1px solid <?php echo $config->get('pdf_invoice_color_tborder') ? $config->get('pdf_invoice_color_tborder') : '#999'; ?>;
}
.inter td{
  padding:1px;
}
.black .lines, .black .bottomlines{
  border-color:#000;
}
.spacer{
  width:30px;
}
.rtl{text-align:right}
.rtl .right, .rtl .left{text-align:left}
.ltr .right, .rtl .left{text-align:right}
.center{text-align:center}
</style>
<h1 id="title"><?php if($invoice_no){ ?><?php echo $text_invoice; ?><?php }else{ ?><?php echo $language->get('text_proformat'); ?><?php } ?></h1>
<div class="<?php echo $direction; ?>">
<table id="logo">
  <tr>
    <td><a href="<?php echo $store_url; ?>"><img src="<?php echo $logo; ?>"/></a></td>
    <td></td>
    <td></td>
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
  <table class="comment table">
    <tbody><tr><td class="lines"><h3><?php echo $block['title']; ?></h3><br /><?php echo $block['description']; ?></td></tr></tbody>
  </table>
  <?php } ?>
  
  <?php if ($comment) { ?>
  <table class="comment table">
    <tbody><tr><td class="lines"><h3><?php echo $text_instruction; ?></h3><br /><?php echo $comment; ?></td></tr></tbody>
  </table>
  <?php } ?>
  
  <?php foreach($blocks_middle as $block) { ?>
  <table class="comment table">
    <tbody><tr><td class="lines"><h3><?php echo $block['title']; ?></h3><br /><?php echo $block['description']; ?></td></tr></tbody>
  </table>
  <?php } ?>
  
  <table class="table">
    <tbody>
      <tr>
        <td class="lines">
          <h3><?php echo strtoupper($text_payment_address); ?></h3><br />
          <?php echo $payment_address; ?>
          <br /><?php echo $text_email; ?> <?php echo $email; ?>
          <br /><?php echo $text_telephone; ?> <?php echo $telephone; ?>
          <?php if ($payment_company_id || $payment_tax_id) { ?><br/><?php } ?>
          <?php if ($payment_company_id) { ?><br /><?php echo $language->get('text_company_id'); ?> <?php echo $payment_company_id; ?><?php } ?>
          <?php if ($payment_tax_id) { ?><br /><?php echo $language->get('text_tax_id'); ?> <?php echo $payment_tax_id; ?><?php } ?>
          <?php foreach ($custom_fields as $custom_field) { ?>
            <br /><?php echo $custom_field['name']; ?>: <?php echo $custom_field['value']; ?>
          <?php } ?>
        </td>
        <?php if ($shipping_address) { ?>
        <td class="spacer"></td>
        <td class="lines" style="width:50%">
          <h3><?php echo strtoupper($text_shipping_address); ?></h3><br />
    		  <?php echo $shipping_address; ?>
    		  <?php foreach ($custom_fields as $custom_field) { ?>
            <br /><?php echo $custom_field['name']; ?>: <?php echo $custom_field['value']; ?>
          <?php } ?>
        </td>
        <?php } ?>
      </tr>
    </tbody>
  </table>
  
  <table class="table products">
    <thead>
      <tr>
		<?php foreach ($columns as $col) { ?>
			<td><?php echo $language->get('column_'.$col); ?></td>
		<?php } ?>
        <td><?php if ($config->get('pdf_invoice_total_tax')) { echo $language->get('column_total_tax'); } else { echo $language->get('column_total'); } ?></td>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($products as $product) { ?>
      <tr>
		<?php foreach ($columns as $col) { ?>
			<td <?php if(in_array($col, array('weight', 'quantity', 'price', 'tax', 'tax_rate', 'tax_total', 'price_tax', 'total'))){ ?>class="right"<?php } ?> <?php if(in_array($col, array('image', 'quantity'))){ ?>style="width:1px"<?php } ?>>
				<?php 
                    		if($col == 'quantity'){
                            
                    		if($product['unit']) { 
                          		if(trim($product['getDefaultUnitDetails']['name']) != trim($product['unit']['unit_value_name'])){
		                             echo $product['quantity'] .' '.$product['unitdatanames']['unit_plural'].'<br /> = '.number_format(($product['quantity'] / $product['unit']['convert_price']),2).' '.$product['unit']['unit_value_name'];
      	               			 }else{
                                  	echo $product['quantity'] .' '.$product['unitdatanames']['unit_plural']; 
                                 }
                    		}else{ 
                    			echo $product['quantity'];
                     		}
                     }else if($col == 'qty_shipped'){
                     		if($product['unit']) { 
                          		if(trim($product['getDefaultUnitDetails']['name']) != trim($product['unit']['unit_value_name'])){
		                             echo $product['qty_shipped'] .' '.$product['unitdatanames']['unit_plural'].'<br /> = '.number_format(($product['qty_shipped'] / $product['unit']['convert_price']),2).' '.$product['unit']['unit_value_name'];
      	               			 }else{
                                  	echo $product['qty_shipped'] .' '.$product['unitdatanames']['unit_plural']; 
                                 }
                    		}else{ 
                    			echo $product['qty_shipped'];
                     		}
                     
                     }else if($col == 'product'){ ?>
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
        <td class="right"><?php echo $product['total_tax']; ?></td>
      </tr>
      <?php } ?>
      <?php if(isset($vouchers)) foreach ($vouchers as $voucher) { ?>
      <tr>
		<?php foreach ($columns as $col) { ?>
			<?php if($col == 'product'){ ?>
				<td><?php echo $voucher['description']; ?></td>
			<?php }elseif($col == 'quantity'){ ?>
				<td class="right">1</td>
			<?php }elseif($col == 'price'){ ?>
				<td class="right"><?php echo $voucher['amount']; ?></td>
			<?php }else{ ?>
				<td></td>
			<?php } ?>
		<?php } ?>
        <td class="right"><?php echo $voucher['amount']; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
  
  <hr style="margin: 45px 0 25px;"/>
  
  <table class="table black">
    <tbody>
      <?php foreach ($totals as $total) { ?>
      <?php if ($total['code']=='total') { ?>
       <tr class="inter">
        <td style="width:60%"></td>
        <td class="bottomlines"></td>
        <td class="bottomlines"></td>
      </tr>
      <?php } ?>
      <tr>
        <td style="width:60%"></td>
        <td class="bottomlines"><b><?php echo $total['title']; ?>:</b></td>
        <td class="right bottomlines"><?php echo $total['text']; ?></td>
      </tr>
      <?php } ?>
      <?php if ($total['code']=='total') { ?>
       <tr class="inter">
        <td style="width:60%"></td>
        <td class="bottomlines"></td>
        <td class="bottomlines"></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
  
  <?php foreach($blocks_bottom as $block) { ?>
  <table class="comment table">
    <tbody><tr><td><h3><?php echo $block['title']; ?></h3><br /><?php echo $block['description']; ?></td></tr></tbody>
  </table>
  <?php } ?>
  <?php foreach($blocks_newpage as $block) { ?>
  <pagebreak />
  <table class="comment table">
    <tbody><tr><td><h3><?php echo $block['title']; ?></h3><br /><?php echo $block['description']; ?></td></tr></tbody>
  </table>
  <?php } ?>
 </div>

<watermarkimage src="<?php echo $watermark; ?>"/>
<?php if(!empty($blocks_footer) || $config->get('pdf_invoice_footer_'.$lang_id)) { ?>
<htmlpagefooter name="footer" style="display:none">
  <?php if(!empty($blocks_footer)) { ?>
    <?php foreach($blocks_footer as $block) { ?>
    <table class="comment list">
      <thead><tr><td><?php echo $block['title']; ?></td></tr></thead>
      <tbody><tr><td><?php echo $block['description']; ?></td></tr></tbody>
    </table>
    <?php } ?>
  <?php } ?>
  <?php if($config->get('pdf_invoice_footer_'.$lang_id)) { ?>
  <div id="footer"><?php echo html_entity_decode($config->get('pdf_invoice_footer_'.$lang_id), ENT_QUOTES, 'UTF-8'); ?></div>
  <?php } ?>
</htmlpagefooter>
<?php } ?>