<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($attention) { ?>
  <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $attention; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?>
        <?php if ($weight) { ?>
        &nbsp;(<?php echo $weight; ?>)
        <?php } ?>
      </h1>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <div class="cart-info">
            
                <table class="table">
                    <thead>

              			<tr>
                 
                            <td class="image"><?php echo $column_image; ?></td>
                            <td class="name"><?php echo $column_name; ?></td>
                            <td class="model"><?php echo $column_model; ?></td>
                            <td class="quantity"><?php echo $column_quantity; ?></td>
                            <td class="price"><?php echo $column_price; ?></td>
                            <td class="total"><?php echo $column_total; ?></td>
                        
              			</tr>
            </thead>
            <tbody id="cart-data">
              <input type="hidden" id="lowest-cart-id" value="<?php echo $lowest_cart_id; ?>">
              <?php foreach ($products as $product) { ?>
              <tr class="cart-id" id="<?php echo $product['cart_id']; ?>">
                <td class="text-center" style="padding-left:2px;">
                	<div style="float:left">
                		<div style="float:left;width:50%">	
                	<?php if ($product['thumb']) { ?>
                  
                  	
                 		 <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-thumbnail" /></a>
                  <?php } ?>
                  </div>
                  		<div style="float:right; width:50%">
                     <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                      <?php if (!$product['stock']) { ?>
                      <span class="text-danger">***</span>
                      <?php } ?>
                      <?php /* if ($product['option']) { ?>
                      <?php foreach ($product['option'] as $option) { ?>
                      <br />
                      <small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                      <?php } ?>
                      <?php } */ ?>
                       <?php foreach ($product['option'] as $option) { ?>
                                                <?php  $option_name = (explode(" ",$option['name'])); 
                                                $option_unit_name = trim(end($option_name)); ?>
                                                <?php if($option_unit_name == 'units') {
                                                    $option['name'] = $option_unit_name;
                                                } ?>
                                                - <small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small><br />
                                            <?php } ?>
                                            
                                           
                      
                      <?php if ($product['reward']) { ?>
                      <br />
                      <small><?php echo $product['reward']; ?></small>
                      <?php } ?>
                      <?php if ($product['recurring']) { ?>
                      <br />
                      <span class="label label-info"><?php echo $text_recurring_item; ?></span> <small><?php echo $product['recurring']; ?></small>
                      <?php } ?>
                  		<br>
                  		<?php echo $product['model']; ?>
                        <hr>
                        <strong style="color:red; font-size:20px;"><?php echo $product['total']; ?></strong>
                  </div>
                	</div>
                    
                    <div style="float:left; width:100%">
                    	<hr>
                    	<div style="float:left; width:50%">
                        		<div style="float:left">
                                	<span style="float:left">
                               		 	Unit Price : 
                               	   </span>
                                
                                <span style="float:right">
                                	<?php echo $product['price']; ?> 
                                    
                                	<?php if($product['unit']) { ?>       
                                     	<br> <b style="font-weight:bold;"> per <?php echo $product['DefaultUnitName']['unit_singular']; ?> </b>
                                <?php }?>
                             	</span>
                                </div>
                                
                               <br>
                                <div style="float:left">
                                 	
                             		<?php if($product['unit'] && $product['unit']['convert_price'] !=1) { ?>
                                               <!-- - <small><?php echo $product['unit']['unit_name']; ?>: <?php echo $product['unit']['unit_value_name']; ?></small><br />-->
                                               - <small style="color:#DD0205; font-weight:bold;"><?php echo number_format(($product['unit']['convert_price'] * $product['quantity']),2); ?> <?php echo $product['unit_dates_default']['name']; ?> = <?php echo $product['quantity']; ?> <?php echo $product['unit']['unit_value_name']; ?></small><br />
                                            <?php } ?>
                               </div>
                        </div>
                        
                        <div style="float:right; width:50%">
                        	 <div class="input-group btn-block" style="max-width: 200px;">
                    <input type="text" name="quantity[<?php echo $product['cart_id']; ?>]" value="<?php echo $product['quantity']; ?>" size="1" class="form-control" />
                    <span class="input-group-btn">
                          <input type="image" src="catalog/view/theme/default/image/update.png" alt="<?php echo $button_update; ?>" title="<?php echo $button_update; ?>" />
                          &nbsp;<!--<button type="button" onclick="cart.remove('<?php echo $product['key']; ?>');" title="<?php echo $button_remove; ?>" class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button>-->
                          
                          <!--<a href="#" onclick="cart.remove('<?php echo $product['cart_id']; ?>');"><img src="catalog/view/theme/default/image/remove.png" alt="<?php echo $button_remove; ?>" title="<?php echo $button_remove; ?>" /></a>-->
                    <!--<button type="submit" data-toggle="tooltip" title="<?php echo $button_update; ?>" class="btn btn-primary"><i class="fa fa-refresh"></i></button>
                    !-->
                    <button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger" onclick="cart.remove('<?php echo $product['cart_id']; ?>');"><img src="catalog/view/theme/default/image/remove.png" alt="<?php echo $button_remove; ?>" title="<?php echo $button_remove; ?>" /></button></span></div>
                    
                    
                    <?php if (!empty($product['unit_dates'])) { ?>
                               			 <div class="ig_MetalType ig_Units units_grouped">
                                    <select class="get-unit-data id_convert_long" id="<?php echo $product['key']; ?>" name="get-unit-data[<?php echo $product['key']; ?>]">
                                        <?php foreach ($product['unit_dates'] as $unit_data) { ?>
                                            
                                            <?php 
                                              		if($product['unit']['unit_conversion_values'] == $unit_data['unit_conversion_product_id']){
                                                    	$checked = 'selected';
                                                    }else{
                                                    	$checked = '';
                                                    }
                                                    
                                                    $new_value = str_replace ($product['unit']['unit_conversion_values'] , $unit_data['unit_conversion_product_id'] , $product['key']);
                                            ?>
                                            <option  name="<?php echo $unit_data['name']; ?>" data-value ="<?php echo $unit_data['unit_conversion_product_id']; ?>" value="<?php echo $unit_data['unit_conversion_product_id']; ?>" <?php echo $checked; ?>>
                                                <?php echo $unit_data['name']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                	<?php }?>
                        </div>
                    </div>
                </td>
                <!-- <td class="text-left"></td>
                <td class="text-left">
               
                                 
                                 </td>
                                <td data-title="Unit Price" class="price">
                                		<?php echo $product['price']; ?> 
                                 		<?php if($product['unit']) { ?>       
                                        	<br> <b style="font-weight:bold;"> per <?php echo $product['DefaultUnitName']['unit_singular']; ?> </b>
                                        <?php }?>
                                </td>
               --> 
              </tr>
              <?php } ?>
              </tbody>
              </table>
              <div class="ajax-load text-left" style="display:none;margin-top:10px;">
                  <p id="load_more_button"><button class="btn btn-primary" onclick="loadAjaxData();">Load More</button></p>
              </div>
              <table class="table">
              <tbody>
              <?php foreach ($vouchers as $vouchers) { ?>
              <tr>
                <td></td>
                <td class="text-left"><?php echo $vouchers['description']; ?></td>
                <td class="text-left"></td>
                <td class="text-left"><div class="input-group btn-block" style="max-width: 200px;">
                    <input type="text" name="" value="1" size="1" disabled="disabled" class="form-control" />
                    <span class="input-group-btn"><button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger" onclick="voucher.remove('<?php echo $vouchers['key']; ?>');"><i class="fa fa-times-circle"></i></button></span></div></td>
                <td class="text-right"><?php echo $vouchers['amount']; ?></td>
                <td class="text-right"><?php echo $vouchers['amount']; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
          
        </div>
      </form>
      <?php if ($coupon || $voucher || $reward || $shipping) { ?>
      <h2><?php echo $text_next; ?></h2>
      <!--<p><?php echo $text_next_choice; ?></p>-->
      <div class="panel-group" id="accordion"><?php echo $voucher; ?><?php echo $reward; ?><?php echo $shipping; ?></div>
      <?php } ?>
      <br />
      <div>
        <div class="col-sm-4 col-sm-offset-8">
          <table class="table table-bordered">
            <?php foreach ($totals as $total) { ?>
            <tr>
              <td class="text-right"><strong><?php echo $total['title']; ?>:</strong></td>
              <td class="text-right"><?php echo $total['text']; ?></td>
            </tr>
            <?php } ?>
          </table>
        </div>
      </div>
      <div class="buttons">
        <div class="right"><a href="<?php echo $checkout; ?>" class="button"><?php echo $button_checkout; ?></a></div>
        <div class="center"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_shopping; ?></a></div>
    </div>
      <!--<div class="buttons">
        <div class="pull-left"><a href="<?php echo $continue; ?>" class="btn btn-default"><?php echo $button_shopping; ?></a></div>
        <div class="pull-right"><a href="<?php echo $checkout; ?>" class="btn btn-primary"><?php echo $button_checkout; ?></a></div>
      </div>-->
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
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
                    data: { 'last_cart_id': last_cart_id, 'page' : 'mobile_cart'},
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
