<?php if (!isset($redirect)) { ?>
  <?php if ($confirmation_page) { ?>
  <?php
      //echo '<pre>';
      //print_r($order_data);
    ?>
    <div id="payment-address">
    <div class="quickcheckout-heading">
       <div class="table-responsive">
      <table style="width:100%">
          <tr>
              <td style="width: 20%;    vertical-align: middle"><strong style="font-size: 22px;font-weight: bold;">1. Billing Address </strong></td>
                <td style="width: 60%;">
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
          </td>


        <td style="width: 20%;">
              <a href="<?php echo $back; ?>"><?php echo 'Change' ?></a>
        </td>
        </tr>
        </table>
        </div>
     </div>
    </div>

    <div id="shipping-address">

      <div class="quickcheckout-heading">
          <div class="table-responsive">
            <table style="width:100%">
          <tr>
              <td style="width: 20%;    vertical-align: middle"><strong style="font-size: 22px;font-weight: bold;">2. Delivery Address </strong></td>
                <td style="width: 60%;">
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
          </td>


        <td style="width: 20%;">
              <a href="<?php echo $back; ?>"><?php echo 'Change' ?></a>
        </td>
        </tr>
        </table>
        </div>
         </div>
    </div>
    <div id="shipping-address">

      <div class="quickcheckout-heading">
          <div>
              <strong style="font-size: 22px;font-weight: bold;">3. Review Items & Shipping</strong>
            </div>
        <div style="width: 20%;float: right;margin-top: -20px; color:white">
              <a href="<?php echo $back_to_cart; ?>"><?php echo 'Change' ?></a>
            </div>
        </div>
    <div class="table-responsive">
    <table class="table table-bordered table-hover">
    <thead>
      <tr>
      <td class="text-left"><?php echo $column_image; ?></td>
            <td class="text-left"><?php echo $column_name; ?></td>

      <td class="text-right"><?php echo $column_quantity; ?></td>
      <td class="text-right"><?php echo $column_price; ?></td>
      <td class="text-right"><?php echo $column_total; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($products as $product) { ?>
      <tr>
            <td class="image"><?php if ($product['thumb']) { ?>
            <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
            <?php } ?></td>
      <td class="text-left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a><br>
            <small> - <?php echo $product['model']; ?></small>

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
           - <small style="color:#DD0205; font-weight:bold;"><?php echo number_format(($product['quantity']),2); ?> <?php echo $product['unit_dates_default']['name']; ?> = <?php echo number_format(($product['quantity']/$product['unit']['convert_price']),2); ?> <?php echo $product['unit']['unit_value_name']; ?></small><br />
           <?php } ?>
        </td>
      <td class="text-right"><?php 
                    			//print_r($product['unit']);
                            if(!empty($product['unit']) && $product['unit']['convert_price'] !=1) {
                          		
		                             echo $product['quantity'] .' <br><b>'.$product['DefaultUnitName']['unit_plural'].'</b>';
      	               			
                    		}
                            else{ 
                    			echo $product['quantity'];
                          if($product['quantity'] > 1) echo "<br/><b>".$product['DefaultUnitName']['unit_plural']."</b>"; else echo "<br/><b>".$product['DefaultUnitName']['unit_singular']."</b>";
                     		} 
                  ?><?php //echo $product['quantity']; ?></td>
      <td class="text-right"><?php echo $product['price']; ?>
                  <?php if(!empty($product['unit']) && $product['unit']['convert_price'] !=1) { ?>
                             <br> <b style="font-weight:bold;"> per <?php echo $product['DefaultUnitName']['unit_singular']; ?> </b>
                             <?php }else{ echo "<br/><b>per ".$product['DefaultUnitName']['unit_singular']."</b>"; } ?></td>
      <td class="text-right"><?php echo $product['total']; ?></td>
      </tr>
      <?php } ?>
      <?php foreach ($vouchers as $voucher) { ?>
      <tr>
      <td class="text-left"><?php echo $voucher['description']; ?></td>
      <td class="text-left"></td>
      <td class="text-right">1</td>
      <td class="text-right"><?php echo $voucher['amount']; ?></td>
      <td class="text-right"><?php echo $voucher['amount']; ?></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <?php foreach ($totals as $total) { ?>
      <tr>
            <?php
                      if(isset($total['desc'])){
                          if($total['desc'] == 'additional'){
            ?>
                            <!-- add tooltip and HTML for Tool Tip -->
                    <td class="text-right" colspan="4"><a href="javascript:void(0)" data-toggle="tooltip"  title="The wire may end up weighing more or less than the amount listed in your order. We charge a little bit more at checkout, then refund the difference after we ship your order. Don't worry, we will never charge more than what we send." class="tooltp"><img width="24" height="24" src="catalog/view/theme/default/img/help_black.png" /></a><b><?php echo $total['title']; ?>:</b></td>
        <td class="text-right"><?php echo $total['text']; ?></td>



            <?php
                    }else{
             ?>
                      <td colspan="4" class="text-right"><strong><?php echo $total['title']; ?>:</strong>

                                <?php echo $total['desc'];?>
                                </td>
                <td class="text-right"><?php echo $total['text']; ?></td>

             <?php

                          }
                       }else{
             ?>
                      <td colspan="4" class="text-right"><strong><?php echo $total['title']; ?>:</strong>


                                </td>
                <td class="text-right"><?php echo $total['text']; ?></td>

             <?php

                       }
             ?>


      </tr>
      <?php } ?>
    </tfoot>
    </table>
  </div>
    </div>
  <?php } ?>
  <div class="payment" style="width:100%"><?php echo $payment; ?></div>
<!--  <div style="float:right"><a class="btn btn-danger pull-left" href="<?php echo $back; ?>"><?php echo $button_back; ?></a></div>
-->
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