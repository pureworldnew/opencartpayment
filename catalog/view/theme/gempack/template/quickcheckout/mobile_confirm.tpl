<?php if (!isset($redirect)) { ?>
<div class="container">
<div class="quickcheckoutleft">
	<div id="payment-address">
  		<div class="quickcheckout-heading"><i class="fa fa-user"></i> <?php echo '1. Billing Address' ?></div>
  		<div class="quickcheckout-content">
  		  	<div>
            <?php 
                    echo $order_data['payment_firstname'].' '.$order_data['payment_lastname'];
                    if(trim($order_data['payment_company']) != ''){
                        echo '<br>'.$order_data['payment_company'];
                    }
                    echo '<br>'.$order_data['payment_address_1'].'<br>';
                    if(trim($order_data['payment_address_2']) != ''){
                        echo $order_data['payment_address_2'].'<br>';
                    }
                    echo $order_data['payment_city'].','.$order_data['payment_zone'].','.$order_data['payment_postcode'].'<br>';
                    echo $order_data['payment_country'].'<br>';
            ?>
            </div>
            <div style="width: 20%;float: right;margin-top: -50px; color:white">
                    <a href="<?php echo $back; ?>"><?php echo 'Change' ?></a>
            </div>
  		</div>
	</div>
	<div id="shipping-address">
  		<div class="quickcheckout-heading"><i class="fa fa-user"></i> <?php echo '2. Delivery Address' ?></div>
  		<div class="quickcheckout-content">
  		  	<div>
                <?php 
                        echo $order_data['shipping_firstname'].' '.$order_data['shipping_lastname'];
                        if(trim($order_data['shipping_company']) != ''){
                            echo '<br>'.$order_data['shipping_company'];
                        }
                         echo '<br>'.$order_data['shipping_address_1'].'<br>';
                       
                        if(trim($order_data['shipping_address_2']) != ''){
                            echo $order_data['shipping_address_2'].'<br>';
                        }
                        echo $order_data['shipping_city'].','.$order_data['shipping_zone'].','.$order_data['shipping_postcode'].'<br>';
                        echo $order_data['shipping_country'].'<br>';
                 ?>
         	</div>
            <div style="width: 20%;float: right;margin-top: -50px; color:white">
            	<a href="<?php echo $back; ?>"><?php echo 'Change' ?></a>
            </div>
  		</div>
	</div>
    

<div id="shipping-address">
  <div class="quickcheckout-heading"><i class="fa fa-shopping-cart"></i> <?php echo '3. Review Items' ?></div>
  <div class="quickcheckout-content" style="border:none; padding: 0px;">
  		  <?php if ($confirmation_page) { ?>
            
              <table class="quickcheckout-cart">
	<thead>
		<tr>
		  <td class="image"><?php echo $column_image; ?></td>
		  <td class="name"><?php echo $column_name; ?></td>
		  <td class="quantity"><?php echo $column_quantity; ?></td>
		  <td class="price1"><?php echo $column_price; ?></td>
		  <td class="total"><?php echo $column_total; ?></td>
		</tr>
	</thead>
    <?php if ($products || $vouchers) { ?>
    <tbody id="cart-data">
        <input type="hidden" id="lowest-cart-id" value="<?php echo $lowest_cart_id; ?>">
        <?php foreach ($products as $product) { ?>
        <tr class="cart-id" id="<?php echo $product['cart_id']; ?>">
          <td class="image"><?php if ($product['thumb']) { ?>
            <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
            <?php } ?></td>
          <td class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
          <?php foreach ($product['option'] as $option) { ?>
          <br />
          <?php  $option_name = (explode(" ",$option['name'])); 
            $option_unit_name = trim(end($option_name)); ?>
            <?php if($option_unit_name == 'units') {
                $option['name'] = $option_unit_name;
            } ?>
          &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
          <?php } ?><br />
          
          <?php 
          		//print_r($product['unit']);
          		if(!empty($product['unit']) && $product['unit']['convert_price'] !=1) { ?>
           <!--  - <small><?php echo $product['unit']['unit_name']; ?>: <?php echo $product['unit']['unit_value_name']; ?></small><br /> -->
           - <small style="color:#DD0205; font-weight:bold;"><?php echo number_format(($product['unit']['convert_price'] * $product['quantity']),2); ?> <?php echo $product['unit_dates_default']['name']; ?> = <?php echo $product['quantity']; ?> <?php echo $product['unit']['unit_value_name']; ?></small><br />
           <?php } ?>
        </td>
         
          <td class="quantity"><?php /* if ($edit_cart) { ?>
        <div class="input-group btn-block">
          <input type="text" name="quantity[<?php echo $product['key']; ?>]" size="1" value="<?php echo $product['quantity']; ?>" class="form-control" />
        <span class="input-group-btn">
        <button data-toggle="tooltip" title="<?php echo $button_update; ?>" class="btn btn-primary button-update"><i class="fa fa-refresh"></i></button>
        <button data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger button-remove" data-remove="<?php echo $product['key']; ?>"><i class="fa fa-times-circle"></i></button>
        </span>
      </div>
      <?php } else { */?>
      x&nbsp;<?php if(!empty($product['unit']) && $product['unit']['convert_price'] !=1) { echo number_format(($product['unit']['convert_price'] * $product['quantity']),2); echo "<br/><b>".$product['unit_dates_default']['name']."</b>"; }else{ echo $product['quantity']; if($product['quantity'] > 1) echo "<br/><b>".$product['DefaultUnitName']['unit_plural']."</b>"; else echo "<br/><b>".$product['DefaultUnitName']['unit_singular']."</b>"; } ?>
      <?php // } ?></td>
      <td class="price1"><?php echo $product['price']; ?>
                  <?php if(!empty($product['unit']) && $product['unit']['convert_price'] !=1) { ?>
                             <br> <b style="font-weight:bold;"> per <?php echo $product['DefaultUnitName']['unit_singular']; ?> </b>
                             <?php }else{ echo "<br/><b>per ".$product['DefaultUnitName']['unit_singular']."</b>"; } ?></td>
          <td class="total"><?php echo $product['total']; ?></td>
        </tr>
        <?php } ?>
        </tbody>
        </table>
        <div class="ajax-load text-center" style="display:none;margin-top:10px;">
          <p id="load_more_button"><button class="btn btn-primary" onclick="loadAjaxData();">Load More</button></p>
        </div>
        <table class="quickcheckout-cart">
        <tbody>
        <?php foreach ($vouchers as $voucher) { ?>
        <tr>
          <td class="image"></td>
          <td class="name"><?php echo $voucher['description']; ?></td>
          <td class="quantity">x&nbsp;1</td>
		  <td class="price1"><?php echo $voucher['amount']; ?></td>
          <td class="total"><?php echo $voucher['amount']; ?></td>
        </tr>
        <?php } ?>
		<?php foreach ($totals as $total) { ?>
        	<?php if(isset($total['desc']) && $total['desc'] == 'additional'){ ?>
            		<!-- add tooltip and HTML for Tool Tip -->
			<tr>
				<td class="text-right" colspan="4"><a data-toggle="tooltip"  title="The wire may end up weighing more or less than the amount listed in your order. We charge a little bit more at checkout, then refund the difference after we ship your order. Don't worry, we will never charge more than what we send." class="tooltp"><img width="24" height="24" src="catalog/view/theme/default/img/help_black.png" /></a><b><?php echo $total['title']; ?>:</b></td>
				<td class="text-right"><?php echo $total['text']; ?></td>
			</tr>
			<?php }else{?>
            <tr>
				<td class="text-right" colspan="4"><b><?php echo $total['title']; ?>:</b></td>
				<td class="text-right"><?php echo $total['text']; ?></td>
			</tr>
            <?php }?>
        <?php } ?>
	</tbody>
    <?php } ?>
</table>
            
 		 <?php } ?>
  </div>
</div>
            

 </div> 
  <div class="quickcheckoutright">
 	 <div id="payment-method">
     	<div class="payment"><?php echo $payment; ?></div>
     </div>
  </div>
  <!--<div style="float:right"><a class="btn btn-danger pull-left" href="<?php echo $back; ?>"><?php echo $button_back; ?></a></div>-->
</div>  
  <script type="text/javascript"><!--
  <?php if ($payment_target && $auto_submit) { ?>
  $('.payment').find('<?php echo $payment_target; ?>').trigger('click');
  
  setTimeout(function() {
	  $('#quickcheckoutconfirm').show();
	  $('#payment').show();
	  $('.fa-spinner').remove();
  }, 4000);
  <?php } ?>
  //--></script> 
<?php } else { ?>
<script type="text/javascript"><!--
location = '<?php echo $redirect; ?>';
//--></script>
<?php } ?>
