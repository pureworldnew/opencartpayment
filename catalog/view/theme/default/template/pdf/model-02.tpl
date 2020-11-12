<?php if(isset($_newpage)) { ?><pagebreak /><?php } ?>
<style type="text/css">
@page{
	margin: 12mm;
	footer: html_footer;
}
#footer{
	font-size:11px;
	color: <?php echo $config->get('pdf_invoice_color_footertxt') ? $config->get('pdf_invoice_color_footertxt') : '#777'; ?>;
	padding-top: 5px;
	border-top: 1px solid <?php echo $config->get('pdf_invoice_color_tborder') ? $config->get('pdf_invoice_color_tborder') : '#aaa'; ?>;
}
body, table{
	direction:<?php echo $direction; ?>;
	font-family: dejavusanscondensed, sans-serif;
	font-size: 12px;
	color: <?php echo $config->get('pdf_invoice_color_text') ? $config->get('pdf_invoice_color_text') : '#000'; ?>;
}
.comment p{
	margin:0
}
#head{
	vertical-align:top;
	width: 100%;
	margin-bottom: 15px;
	padding-bottom: 5px;
}
#head td{
	vertical-align:top;
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
.address, .product {
	border-collapse: collapse;
}
.address {
	width: 100%;
	margin-bottom: 20px;
}
.address th, .address td {
	border: 1px solid <?php echo $config->get('pdf_invoice_color_tborder') ? $config->get('pdf_invoice_color_tborder') : '#ddd'; ?>;
	padding: 5px;
	vertical-align: text-bottom;
}
.address td table td{
	border:0;
	padding:0;
}
.address td {
	width: 50%;
}
.product {
	width: 100%;
	margin-bottom: 20px;
	border-top: 1px solid <?php echo $config->get('pdf_invoice_color_tborder') ? $config->get('pdf_invoice_color_tborder') : '#ddd'; ?>;
	border-right: 1px solid <?php echo $config->get('pdf_invoice_color_tborder') ? $config->get('pdf_invoice_color_tborder') : '#ddd'; ?>;
}
.product td {
	border-left: 1px solid <?php echo $config->get('pdf_invoice_color_tborder') ? $config->get('pdf_invoice_color_tborder') : '#ddd'; ?>;
	border-bottom: 1px solid <?php echo $config->get('pdf_invoice_color_tborder') ? $config->get('pdf_invoice_color_tborder') : '#ddd'; ?>;
	padding: 5px;
}
.comment{
	width: 100%;
	border-collapse: collapse;
	margin-bottom: 20px;
}
.comment td{
	padding: 5px;
	border: 1px solid <?php echo $config->get('pdf_invoice_color_tborder') ? $config->get('pdf_invoice_color_tborder') : '#ddd'; ?>;
}
.rtl{text-align:right;}
.rtl .right{text-align:left;}
.ltr .right{text-align:right;}
.list thead td {
	background-color: <?php echo $config->get('pdf_invoice_color_thead') ? $config->get('pdf_invoice_color_thead') : '#efefef'; ?>;
	color: <?php echo $config->get('pdf_invoice_color_theadtxt') ? $config->get('pdf_invoice_color_theadtxt') : '#000'; ?>;
	font-weight: bold;
}
</style>
<div class="<?php echo $direction; ?>">
	<table id="head">
		<tr>
			<td style="width:70%">
        <?php if ($logo) { ?><a href="<?php echo $store_url; ?>"><img src="<?php echo $logo; ?>"/></a><?php } ?>
        <?php if (!empty($barcode['status'])) { ?>
          <table style="width:100%;margin-top:35px;"><tr><td style="border:0;padding:0">
          <barcode type="<?php echo $barcode['type']; ?>" code="<?php echo $barcode['value']; ?>"/>
          </td></tr></table>
          <?php } ?>
      </td>
			<td>
			<div>
				<b><?php echo $store_name; ?></b><br /><br />
        <?php echo $store_address; ?><br />
		<?php if ($config->get('pdf_invoice_vat_number')) { ?><?php echo $language->get('text_store_vat'); ?> <?php echo $config->get('pdf_invoice_vat_number'); ?><br /><?php } ?>
		<?php if ($config->get('pdf_invoice_company_id')) { ?><?php echo $language->get('text_store_company'); ?> <?php echo $config->get('pdf_invoice_company_id'); ?><br /><?php } ?>
        <?php if ($store_telephone) { ?><?php echo $store_telephone; ?><br /><?php } ?>
        <?php if ($store_fax) { ?><?php echo $store_fax; ?><br /><?php } ?>
        <?php if ($store_email) { ?><?php echo $store_email; ?><br /><?php } ?>
        <?php if ($store_url) { ?><?php echo $store_url; ?><?php } ?>
				</div>
			</td>
		</tr>
	</table>
  <table class="address list">
	<thead><tr><td><?php echo $text_order_detail; ?></td></tr></thead>
	<tbody>
    <tr>
      <td>
			<table>
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
			  <tr>
				<td><b><?php echo $text_payment_method; ?></b></td>
				<td><?php echo $order['payment_method']; ?></td>
			  </tr>
			</table>
		</td>
    </tr>
	</tbody>
  </table>
  <?php foreach($blocks_top as $block) { ?>
  <table class="comment list">
    <thead><tr><td><?php echo $block['title']; ?></td></tr></thead>
    <tbody><tr><td><?php echo $block['description']; ?></td></tr></tbody>
  </table>
  <?php } ?>
  <table class="address list">
    <thead><tr>
      <td><?php echo $text_payment_address; ?></td>
      <?php if ($shipping_address) { ?>
	  <td width="50%"><?php echo $text_shipping_address; ?></td>
	  <?php } ?>
    </tr></thead>
    <tbody><tr>
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
        <?php } ?>
        <?php foreach ($payment_custom_fields as $custom_field) { ?>
          <br /><?php echo $custom_field['name']; ?>: <?php echo $custom_field['value']; ?>
        <?php } ?>
        <?php foreach ($custom_fields as $custom_field) { ?>
          <br /><?php echo $custom_field['name']; ?>: <?php echo $custom_field['value']; ?>
        <?php } ?>
        </td>
      <?php if ($shipping_address) { ?>
      <td>
	    <?php echo $shipping_address; ?>
        <?php foreach ($shipping_custom_fields as $custom_field) { ?>
          <br /><?php echo $custom_field['name']; ?>: <?php echo $custom_field['value']; ?>
        <?php } ?>
      </td>
	  <?php } ?>
    </tr></tbody>
  </table>
  <?php foreach($blocks_middle as $block) { ?>
  <table class="comment list">
    <thead><tr><td><?php echo $block['title']; ?></td></tr></thead>
    <tbody><tr><td><?php echo $block['description']; ?></td></tr></tbody>
  </table>
  <?php } ?>
  <table class="product list">
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
      <?php foreach ($totals as $total) { ?>
      <tr>
        <td colspan="<?php echo count($columns); ?>" class="right"><b><?php echo $total['title']; ?>:</b></td>
        <td class="right"><?php echo $total['text']; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
  <?php if ($comment) { ?>
  <table class="comment list">
    <tr class="heading">
      <td><b><?php echo $text_instruction; ?></b></td>
    </tr>
    <tr>
      <td><?php echo $comment; ?></td>
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