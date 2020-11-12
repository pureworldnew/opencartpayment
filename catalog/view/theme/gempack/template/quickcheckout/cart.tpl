<div id="cart1">
        <div class="quickcheckout-heading"><i class="fa fa-shopping-cart"></i> Review your Cart</div>
				<div class="quickcheckout-content" style="border:none; padding: 0px;">
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
 </div>
 </div>

			  
			  <div id="voucher">
				<div class="quickcheckout-content" style="border:none; padding: 0px;overflow: hidden;"><?php echo $voucher; ?></div>
			  </div>
			  
              <div id="resalenumber">
              		<div class="quickcheckout-content" style="border:none; padding: 0px;overflow: hidden;">
                      <div id="resalenumber-heading"><?php echo "Enter Resale ID Number "; ?>&nbsp;&nbsp;<i>(Optional)</i></div>
                      <div id="resalenumber-content">
                      		<?php echo "Resale ID Number"; ?>
                          
                    
                          <a data-toggle="tooltip"  title="If you have a resale number from the California Board of Equalization, please enter it to exempt yourself from State Sales Tax. Resale Numbers consist of 4 Letters, followed by 9 numbers, i.e: ABCD-123456789. If you do not have a resale number, then don't worry! You can still complete your order, but we are required to apply sales tax on the products" class="tooltp"><img width="24" height="24" src="catalog/view/theme/default/img/help_black.png" /></a><input type="text" name="resale_id_number"   value="<?php echo $resale_id_number; ?>" />
                        &nbsp;<a href="JavaScript:void(0);" id="button-resale_id_number" class="btn" 
                        style="background: #fe5930;color:#fff;font-weight:600;"><span><?php echo "Apply"; ?></span></a></div>
                    </div>
                    </div>
                    
 <script>
 $('#resalenumber-heading').on('click', function() {
	
    if($('#resalenumber-content').is(':visible')){
      $('#resalenumber-content').slideUp('slow');
    } else {
      $('#resalenumber-content').slideDown('slow');
    };
});

$('#button-resale_id_number').bind('click', function() {
 
 $.ajax({
   type: 'POST',
   url: 'index.php?route=quickcheckout/cart/validateResale',
   data: $('#resalenumber-content :input'),
   dataType: 'json',		
   beforeSend: function() {
     $('.success, .warning').remove();
     $('#button-resale_id_number').attr('disabled', true);
     $('#button-resale_id_number').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
   },
   complete: function() {
     $('#button-resale_id_number').attr('disabled', false);
     $('.wait').remove();
   },		
   success: function(json) {
     if (json['error']) {
       alert(json['error']);
       $('#warning-messages').prepend('<div class="alert alert-danger" style="display: none;"><i class="fa fa-exclamation-circle"></i> ' + json['error_html'] + '</div>');
       $('#warning-messages').fadeIn('slow');
     }
     
     if (json['success']) {
         loadCart();
     }
   }
 });
});

 </script>

<script type="text/javascript">
    var lowest_cart_id  = $("#lowest-cart-id").val();
    var last_cart_id    = $(".cart-id:last").attr("id");
    if( lowest_cart_id > 0 && lowest_cart_id < last_cart_id )
    {
      $('.ajax-load').show();
    }

    function loadAjaxData()
    {
      var last_cart_id = $(".cart-id:last").attr("id");
      loadMoreData(last_cart_id);
    }

    function loadMoreData(last_cart_id){
      
          $.ajax({
                    url: 'index.php?route=checkout/cart/loadMoreData',
                    type: 'post',
                    data: { 'last_cart_id': last_cart_id, 'page' : 'checkout'},
                    dataType: 'html',
                    beforeSend: function()
                    {
                      var html = '<button class="btn btn-primary"><i class="fa fa-spinner fa-spin" style="font-size:24px"></i></button>';
                      $("#load_more_button").html(html);
                    },
                    success: function(html) {
                      $('.ajax-load').hide();
                      $("#cart-data").append(html);
                      var last_cart_id = $(".cart-id:last").attr("id");
                      var lowest_cart_id  = $("#lowest-cart-id").val();
                      if( lowest_cart_id > 0 && lowest_cart_id < last_cart_id )
                      {
                        var html = '<button class="btn btn-primary" onclick="loadAjaxData();">Load More</button>';
                        $("#load_more_button").html(html); 
                        $('.ajax-load').show();
                      }
                    }
                });
    }
</script> 