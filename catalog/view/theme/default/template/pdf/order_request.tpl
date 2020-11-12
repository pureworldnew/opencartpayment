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
	margin-bottom: 20px;
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
.rtl{text-align:right}
.rtl .right, .rtl .left{text-align:left}
.ltr .right, .rtl .left{text-align:right}
.center{text-align:center}
</style>
<?php $logo_bella = str_replace("logo_gem.png","logo_bella.png", $logo); ?>
<h1 id="title">Order Request</h1>
<div class="<?php echo $direction; ?>">
<div id="logo"><a href="<?php echo $store_url; ?>"><img src="<?php echo $logo_bella; ?>"/></a>&nbsp; &nbsp; &nbsp; &nbsp;<a href="<?php echo $store_url; ?>"><img src="<?php echo $logo; ?>"/></a></div>
  <?php foreach($blocks_top as $block) { ?>
  <table class="comment list">
    <thead><tr><td><?php echo $block['title']; ?></td></tr></thead>
    <tbody><tr><td><?php echo $block['description']; ?></td></tr></tbody>
  </table>
  <?php } ?>
  <table class="list">
    <thead>
      <tr>
        <td colspan="3"><?php echo $text_order_detail; ?></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="width:31%;">
		      <b><?php echo $text_order_id; ?></b> <?php echo $order_id; ?><br />
          <b><?php echo $text_date_added; ?></b> <?php echo $date_added; ?><br />
        </td>
        <td style="width:31%;">
		      <?php echo $store_name; ?><br />
          <?php echo $store_address; ?><br />
        </td>
        <td style="width:38%;">
          <b><?php echo $text_telephone; ?></b> <?php echo $store_telephone; ?><br />
          <b><?php if ($store_fax) { ?><?php echo $text_fax; ?></b> <?php echo $store_fax; ?><br /><?php } ?>
          <b><?php echo $text_url; ?></b> <?php echo $store_url; ?>
        </td>
      </tr>
    </tbody>
  </table>
  <?php if ($comment) { ?>
  <table class="comment list">
    <thead><tr><td><?php echo $text_instruction; ?></td></tr></thead>
    <tbody><tr><td><?php echo $comment; ?></td></tr></tbody>
  </table>
  <?php } ?>
  <?php foreach($blocks_middle as $block) { ?>
  <table class="comment list">
    <thead><tr><td><?php echo $block['title']; ?></td></tr></thead>
    <tbody><tr><td><?php echo $block['description']; ?></td></tr></tbody>
  </table>
  <?php } ?>
  <table class="list">
    <thead>
      <tr>
        <td><?php echo $text_payment_address; ?></td>
        <?php if ($shipping_address) { ?>
        <td><?php echo $text_shipping_address; ?></td>
        <?php } ?>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><?php echo $payment_address; ?>
			<br /><?php echo $text_email; ?> <?php echo $email; ?>
			<br /><?php echo $text_telephone; ?> <?php echo $telephone; ?>
			<?php if ($payment_company_id || $payment_tax_id) { ?><br/><?php } ?>
			<?php if ($payment_company_id) { ?><br /><?php echo $language->get('text_company_id'); ?> <?php echo $payment_company_id; ?><?php } ?>
			<?php if ($payment_tax_id) { ?><br /><?php echo $language->get('text_tax_id'); ?> <?php echo $payment_tax_id; ?><?php } ?>
      <?php foreach ($payment_custom_fields as $custom_field) { ?>
        <br /><?php echo $custom_field['name']; ?>: <?php echo $custom_field['value']; ?>
      <?php } ?>
      <?php foreach ($custom_fields as $custom_field) { ?>
        <br /><?php echo $custom_field['name']; ?>: <?php echo $custom_field['value']; ?>
      <?php } ?>
		</td>
        <?php if ($shipping_address) { ?>
        <td style="width:50%">
    		  <?php echo $shipping_address; ?>
    		  <?php foreach ($shipping_custom_fields as $custom_field) { ?>
            <br /><?php echo $custom_field['name']; ?>: <?php echo $custom_field['value']; ?>
          <?php } ?>
        </td>
        <?php } ?>
      </tr>
    </tbody>
  </table>
  <table class="list">
    <thead>
      <tr>
      <?php 
        $new_col = array( 'mpn' ); 
        array_splice( $columns, 2, 0, $new_col ); 
        array_splice( $columns, 5 ); 
      ?>
      <td>&nbsp;</td>
		  <?php foreach ($columns as $col) { ?>
			<td>
          <?php if($col == 'model')    { echo "Bella Number"; } 
                elseif($col == 'mpn')  { echo "Vendor Number"; }
                elseif($col == 'image')  { echo "Image"; }
                elseif($col == 'quantity')  { echo "Qty"; }
                else { echo $language->get('column_'.$col); }  ?>
      </td>
		<?php } ?>
        <td>Remarks</td>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($products as $k => $product) { ?>
      <tr>
      <td><?php echo $k+1 . "."; ?></td>
		<?php foreach ($columns as $col) { ?>
			<td <?php if(in_array($col, array('weight', 'quantity', 'price', 'tax', 'tax_rate', 'tax_total', 'price_tax', 'total'))){ ?>class="right"<?php } ?> <?php if(in_array($col, array('image'))){ ?>style="width:1px"<?php } ?>>
				<?php 
                    		if($col == 'quantity'){
                            
                    		if($product['unit']) { 
                          		if(trim($product['getDefaultUnitDetails']['name']) != trim($product['unit']['unit_value_name'])){
		                             /*echo $product['quantity'] .' '.$product['unitdatanames']['unit_plural'].'<br /> = '.number_format(($product['quantity'] / $product['unit']['convert_price']),2).' '.$product['unit']['unit_value_name'];*/
                                     echo $product['quantity'] * $product['unit']['convert_price'] .' '.$product['unitdatanames']['unit_plural'].'<br /> = ' . $product['quantity'] .' '.$product['unit']['unit_value_name'];
      	               			 }else{
                                  	echo $product['quantity'] .' '.$product['unitdatanames']['unit_plural']; 
                                 }
                    		}else{ 
                          echo $product['quantity'];
                          echo "<br>" . $product['default_vendor_unit'];
                         }
                     }else if($col == 'qty_shipped'){
                     		if($product['unit'] && !empty($product['qty_shipped'])) { 
                          		if(trim($product['getDefaultUnitDetails']['name']) != trim($product['unit']['unit_value_name'])){
		                             echo $product['qty_shipped'] .' '.$product['unitdatanames']['unit_plural'].'<br /> = '.number_format(($product['qty_shipped'] / $product['unit']['convert_price']),2).' '.$product['unit']['unit_value_name'];
      	               			 }else{
                                  	echo $product['qty_shipped'] .' '.$product['unitdatanames']['unit_plural']; 
                                 }
                    		}else{ 
                    			echo $product['qty_shipped'] > 0 ?  $product['qty_shipped'] : "";
                         }
                         
                         if( isset($_GET['type']) && $_GET['type'] == 'incoming' )
                         {
                           echo "<br>" . $product['updated_vendor_unit'];
                         } 
                     
                     }else if($col == 'product'){ ?>
					<?php if(isset($prod_options['quantity'])){echo $product['quantity'].' x ';} ?><?php echo $product['name']; ?>
					<?php foreach ($product['option'] as $option) { ?>
					<br />
					&nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
					<?php } ?>
				<?php }elseif($col == 'image' && $product['image']){ ?>
					<img src="<?php echo $product['image'] ?>" alt=""/>
                <?php } elseif($col == 'price') { 
                			if($product['unit']) { 
                          		if(trim($product['getDefaultUnitDetails']['name']) != trim($product['unit']['unit_value_name'])){
                                	$product_price = str_replace('$','',$product['price']);
                                	$product_total_price = $product['quantity'] * floatval($product_price);
                                    $product_total_qty = $product['quantity'] * $product['unit']['convert_price'];
                                    echo number_format((float)$product_total_price/$product_total_qty, 2, '.', '');
                                } else {
                                	echo isset($product[$col]) ? $product[$col] : ''; 
                                }
                             } else {
                             	echo isset($product[$col]) ? $product[$col] : ''; 
                             } 
                           if( isset($_GET['type']) && $_GET['type'] == 'incoming' )
                          {                          echo "<br><div style='white-space: nowrap;'>"; 
                            echo "(" . number_format((float)$product['labour_cost'], 2, '.', '') . " + " . number_format((float)$product['unique_option_price'], 5, '.', '') . ")<br>"; 
                            $product_price = str_replace('$','',$product['price']);
                            $expected_cost = (float)$product_price * (float)$product['unique_price_discount'];
                            echo "Expected Cost: $" . number_format((float)$expected_cost, 4, '.', '') . "<br>";
                            $markup = ($product['unique_price_discount'] > 0 ) ? 1/(float)$product['unique_price_discount'] : 0; echo "Markup: " . number_format((float)($markup), 4, '.', '') . "</div>";
                          }  ?>
				<?php }else{ ?>
					<?php echo isset($product[$col]) ? $product[$col] : ''; ?>
				<?php } ?>
			</td>
		<?php } ?>
        <td><?php echo $product['remark']; ?></td>
      </tr>
    <?php } ?>
    </tbody>
  </table>
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