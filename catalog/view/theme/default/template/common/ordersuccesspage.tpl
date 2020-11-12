<?php echo $header; ?>
<?php if(!empty($OSP['CustomCSS'])): ?>
	<style>
        <?php echo htmlspecialchars_decode($OSP['CustomCSS']); ?>
    </style>
<?php endif; ?>
<?php if(!empty($OSP['CustomJS'])): ?>
	<script type="text/javascript">
        <?php echo htmlspecialchars_decode($OSP['CustomJS']); ?>
    </script>
<?php endif; ?>
<div class="container">
    <ul class="breadcrumb">
    	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
    		<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
	    <?php } ?>
    </ul>

	<div class="row">
        <?php echo $column_left; ?>
        <?php if ($column_left && $column_right) { ?>
            <?php $class = 'col-sm-6'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
            <?php $class = 'col-sm-9'; ?>
        <?php } else { ?>
            <?php $class = 'col-sm-12'; ?>
        <?php } ?>
        <div id="content" class="<?php echo $class; ?>">
            <?php echo $content_top; ?>
            <h1 class='h1-osp'><?php echo $heading_title; ?></h1>
            <div class='osp-content'>
            	<?php if (!empty($OSP['PageText'][$current_language])) { ?>
                	<span class='osp-pagetext'>
                    	<?php echo $OSP['PageText'][$current_language]; ?>
                    </span>
                <?php } ?>
                
                <?php if (isset($OSP['AddThisShow']) && $OSP['AddThisShow'] == 'yes' && !empty($OSP['AddThisURL'])) { ?>
                    <div class="osp-sharing" style="text-align:center;">
                        <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-55534c28671b3948" async="async"></script>
                        <div class="addthis_sharing_toolbox" data-url="<?php echo $OSP['AddThisURL']; ?>"></div>
                    </div>
                <?php } ?>
                
                <?php if ($OSP['OrderDetailsShow'] == 'yes' || $OSP['OrderPaymentShippingShow'] == 'yes' || $OSP['OrderProductsShow'] == 'yes') { ?>
                	<h2 class="osp-h2 osp-orderdetails"><?php echo $text_order_detail; ?></h2>
                <?php } ?>
                
                <?php if (isset($OSP['OrderDetailsShow']) && $OSP['OrderDetailsShow'] == 'yes') { ?>
                
                	<?php
                    	$tracking_info = "<script>
                                            dataLayer.push({
                                            'event':'enhanceEcom transactionSuccess',
                                            'ecommerce': {
                                            'purchase': {
                                            'actionField': {";
                        $tracking_info .= 	"'id': '" . $osp_order_id . "',
                                            'affiliation': 'Gempacked.com', 
                                            'payment-method': '" . $OSP_orderinfo['payment_method'] . "',
                                            'shipping-method': '" . $OSP_orderinfo['shipping_method'] . "',
                                            'revenue': " . $OSP_ordertotal . ",
                                            'tax': " . $OSP_ordertax . ",
                                            'shipping': " . $OSP_ordershipping . ",
                                            'coupon': ''";
                        $tracking_info .=  "},
                                            'products': [
                                            ";
                    
                    
                    ?>
                    <table class="table table-bordered table-hover table-orderdetails">
                        <thead>
                            <tr>
                                <td class="text-left" colspan="2"><?php echo $text_order_detail; ?></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-left" style="width: 50%;">
                                    <?php if ($OSP_orderinfo['invoice_no']) { ?>
                                        <b><?php echo $text_invoice_no; ?></b> <?php echo $OSP_orderinfo['invoice_no']; ?><br />
                                    <?php } ?>
                                    <b><?php echo str_replace('%s', $osp_order_id, $text_order_id); ?></b><br />
                                    <b><?php echo $text_date_added; ?></b> <?php echo $OSP_orderinfo['date_added']; ?>
                                </td>
                                <td class="text-left">
                                    <?php if ($OSP_orderinfo['payment_method']) { ?>
                                        <b><?php echo $text_payment_method; ?></b> <?php echo $OSP_orderinfo['payment_method']; ?><br />
                                    <?php } ?>
                                        <?php if ($OSP_orderinfo['shipping_method']) { ?>
                                    <b><?php echo $text_shipping_method; ?></b> <?php echo $OSP_orderinfo['shipping_method']; ?>
                                    <?php } ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                <?php } ?>
				
                <?php if (isset($OSP['OrderPaymentShippingShow']) && $OSP['OrderPaymentShippingShow'] == 'yes') { ?>
                    <table class="table table-bordered table-hover table-orderpaymentshipping">
                        <thead>
                            <tr>
                                <td class="text-left" style="width: 50%;"><?php echo $text_payment_address; ?></td>
                                <?php if ($OSP_orderinfo['shipping_address']) { ?>
                                    <td class="text-left"><?php echo $text_shipping_address; ?></td>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-left"><?php echo $OSP_orderinfo['payment_address']; ?></td>
                                <?php if ($OSP_orderinfo['shipping_address']) { ?>
                                    <td class="text-left"><?php echo $OSP_orderinfo['shipping_address']; ?></td>
                                <?php } ?>
                            </tr>
                        </tbody>
                    </table>                
                 <?php } ?>
                 
                <?php if (isset($OSP['OrderProductsShow']) && $OSP['OrderProductsShow'] == 'yes') { ?>
                   
                    <div class="mobile_space" style="display:none">
                        <table class="table table-bordered table-hover" >
                            <thead>
                                <tr>
                                    <td class="image" style="width:50%"><strong>Image</strong></td>
                                    <td class="name" colspan="5"><strong><?php echo $column_name; ?></strong></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($OSP_orderinfo['products'] as $product) { ?>
                                    <tr>
                                        <td class="image" style="text-align:center;vertical-align: middle;"><a href="<?php echo $product['href']; ?>"><img class="osp-productimage" src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" /></a></td>
                                        <td class="name" colspan="5">
                                            <?php echo $product['name']; ?>
                                            <?php foreach ($product['option'] as $option) { ?>
                                                  
                                                  <?php  $option_name = (explode(" ",$option['name']));
                                                    $option_unit_name = trim(end($option_name)); ?>
                                                    <?php if($option_unit_name == 'units') {
                                                        $option['name'] = $option_unit_name;
                                                    } ?>
                                                  &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                                            <?php } ?><br />
                                            

                                           <?php 
                                                $qty = "";
                                                if(!empty($product['unit']) && $product['unit']['convert_price'] !=1) {
                                                    $qty = $product['quantity'] .' '.$product['DefaultUnitName']['unit_plural'];
                                                }else{
                                                    $qty = $product['quantity'];
                                                }
                                           ?>

                                           <div class="center"><?php echo $product['model']; ?></div>
                                           <hr style="margin-top:5%;margin-bottom:5%"/>

                                           <?php if(!empty($product['unit']) && $product['unit']['convert_price'] !=1) { ?>
                                                   <!--  - <small><?php echo $product['unit']['unit_name']; ?>: <?php echo $product['unit']['unit_value_name']; ?></small><br /> -->
                                                   - <small style="color:#DD0205; font-weight:bold;"><?php echo number_format(($product['quantity']),2); ?> <?php echo $product['unit_dates_default']['name']; ?> = <?php echo number_format(($product['quantity']/$product['unit']['convert_price']),2); ?> <?php echo $product['unit']['unit_value_name']; ?></small><br />
                                           <?php } ?>
                                           
                                           <div class="center"><?php echo $qty.' x '."$".number_format(round(substr($product['price'],1,strlen($product['price'])), 2), 2, ".", ","); ?></div>

                                           <div class="center" style="color:red;font-size:17px"><?php echo $product['total']; ?></div>



                                        </td>
                                    </tr>
                                <?php } ?>

                            </tbody>
                            <tfoot>
                                <?php foreach ($OSP_orderinfo['totals'] as $total) { ?>
                                    <tr>
                                        <td class="text-left" style="width:70%" colspan="5"><b><?php echo $total['title']; ?></b></td>
                                        <td class="text-right" style="<?php echo ($total['title'] == 'Total')?'color:red;font-weight:bold':''; ?>" >
                                            <?php echo $total['text'];?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tfoot>
                        </table>
                        <?php if (isset($OSP['OrderCommentsShow']) && $OSP['OrderCommentsShow'] == 'yes') { ?>
                            <?php if ($OSP_orderinfo['comment']) { ?>
                                <table class="table table-bordered table-hover table-ordercomments">
                                    <thead>
                                        <tr>
                                            <td class="text-left"><?php echo $text_comment; ?></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-left"><?php echo $OSP_orderinfo['comment']; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            <?php } ?>                
                        <?php } ?>
                    </div>
                        <table class="quickcheckout-cart desktop_space">
                            <thead>
                                <tr>
                                	<td class="image"></td>
                                    <td class="name"><strong><?php echo $column_name; ?></strong></td>
                                    <td class="model"><strong><?php echo $column_model; ?></strong></td>
                                    <td class="quantity"><strong><?php echo $column_quantity; ?></strong></td>
                                    <td class="price1"><strong><?php echo $column_price; ?></strong></td>
                                    <td class="total"><strong><?php echo $column_total; ?></strong></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($OSP_orderinfo['products'] as $product) { ?>
                                
                                <?php
                                	$tracking_info .= 	"
                                    {
                                        'name': '" . $product['name'] . "', 
                                        'id': '" . $product['model'] . "', 
                                        'price': '" . $product['gtm_price'] . "', 
                                        'brand': '" . $product['manufacturer'] . "', 
                                        'category': '" . $product['category'] . "', 
                                        'quantity': '" . $product['quantity'] . "'
                                    },";
                                
                                
                                ?>
                                    <tr>
                                    	<td class="image"><a href="<?php echo $product['href']; ?>"><img class="osp-productimage" src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" /></a></td>
                                        <td class="name"><?php echo $product['name']; ?>
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
                                        <td class="model"><?php echo $product['model'];?></td>
                                        <td class="quantity"><?php 
                    			//print_r($product['unit']);
                            if(!empty($product['unit']) && $product['unit']['convert_price'] !=1) {
                          		
		                             echo $product['quantity'] .' '.$product['DefaultUnitName']['unit_plural'];
      	               			
                    		}
                            else{ 
                    			echo $product['quantity'];
                     		} 
                  ?><?php //echo $product['quantity']; ?></td>
                                        <td class="price1"><?php echo "$".number_format(round(substr($product['price'],1,strlen($product['price'])), 2), 2, ".", ","); ?>
                  <?php if(!empty($product['unit']) && $product['unit']['convert_price'] !=1) { ?>
                             <br> <b style="font-weight:bold;"> per <?php echo $product['DefaultUnitName']['unit_singular']; ?> </b>
                             <?php }?></td>
                                        <td class="total"><?php echo $product['total']; ?></td>
                                    </tr>
                                    <?php } ?>
                                    
                                    <?php
                                    $tracking_info = rtrim($tracking_info, ",");
                                    $tracking_info .= "]
                                                      }
                                                     }
                                                   });
                                               </script>";
                                    
                                    ?>
                                    <?php foreach ($OSP_orderinfo['vouchers'] as $voucher) { ?>
                                        <tr>
                                        	<td class="image"></td>
                                            <td class="name"><?php echo $voucher['description']; ?></td>
                                            <td class="text-left"></td>
                                            <td class="quantity">1</td>
                                            <td class="price1"><?php echo $voucher['amount']; ?></td>
                                            <td class="total"><?php echo $voucher['amount']; ?></td>
                                        </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <?php foreach ($OSP_orderinfo['totals'] as $total) { ?>
                                    <tr>
                                        <!--<td colspan="2" class="mobile_space" style="display:none"></td> -->
                                        <td colspan="4" class="desktop_space"></td>
                                        <td class="text-left" colspan="4"><b><?php echo $total['title']; ?></b></td>
                                        <td class="text-right" style="<?php echo ($total['title'] == 'Total')?'color:red;font-weight:bold':''; ?>" >
                                            <?php echo $total['text'];?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tfoot>
                        </table>
                    
				<?php } ?>
                
                <?php if (isset($OSP['OrderCommentsShow']) && $OSP['OrderCommentsShow'] == 'yes') { ?>
                    <?php if ($OSP_orderinfo['comment']) { ?>
                        <table class="table table-bordered table-hover table-ordercomments">
                            <thead>
                                <tr>
                                    <td class="text-left"><?php echo $text_comment; ?></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-left"><?php echo $OSP_orderinfo['comment']; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    <?php } ?>                
				<?php } ?>
				
                <?php if (isset($OSP['ShowProducts']) && $OSP['ShowProducts'] == 'yes' && isset($promoted_products) && sizeof($promoted_products)>0) { ?>
                	<h2 class="osp-h2 osp-promotedproducts"><?php echo $OSP['PromotedTitle'][$current_language]; ?></h2>
                    <div class="row osp-promotedwidget">
                        <?php foreach ($promoted_products as $product) { ?>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="product-thumb transition">
                                <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
                                <div class="caption">
                                    <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
                                    <p><?php echo $product['description']; ?></p>
                                    <?php if ($product['rating']) { ?>
                                        <div class="rating">
                                            <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                <?php if ($product['rating'] < $i) { ?>
                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
                                                <?php } else { ?>
                                                    <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
                                                <?php } ?>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                    <?php if ($product['price']) { ?>
                                        <p class="price">
                                            <?php if (!$product['special']) { ?>
                                                <?php echo $product['price']; ?>
                                            <?php } else { ?>
                                                <span class="price-new"><?php echo $product['special']; ?></span> <span class="price-old"><?php echo $product['price']; ?></span>
                                            <?php } ?>
                                        </p>
                                    <?php } ?>
                                </div>
                                <div class="button-group">
                                    <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span></button>
                                    <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
                                    <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>                   
                <?php } ?>
                
            </div>
            <div class="buttons">
                <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
            </div>
            <?php echo $content_bottom; ?>
        </div>
        <?php echo $column_right; ?>
	</div>
</div>
<?php echo $tracking_info; ?>
<!-- Giveaway Modal -->
<div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content12">
            <form method="post" action="<?php echo $giveaway_action; ?>">
                <input type="hidden" name="giveaway_order_id" value="<?php echo $osp_order_id; ?>">
                <input type="hidden" name="giveaway_product_id" value="">
                <div class="row">
                    <div class="col model-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="close-button" aria-hidden="true">&times;</span>
                        </button>
                        <h5 class="title">BEFORE YOU GO!</h5>
                        <h6 class="sub-title">Select a free gift as a token of our appreciation.</h6>
                    </div>
                </div>
                <?php foreach( $giveaway_products as $giveaway_product ) : ?>
                <div class="row model-row focus" id="<?php echo $giveaway_product['product_id']; ?>" style="padding-top: 6%;">
                    <div class="col-sm-4 model-col">
                        <img class="gift-image" src="<?php echo $giveaway_product['image']; ?>" width="80px" height="80px">
                    </div>
                    <div class="col-sm-8">
                        <span class="description"><?php echo $giveaway_product['name']; ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
                <div class="row model-row">
                    <div class="col model-col">
                        <button type="submit" class="btn btn-primary model-submit">Submit</button>
                        <span class="badge badge-secondary" id="no-gift">No Gift, thank you!</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        
                    </div>
                </div>
            </form>   
        </div>
    </div>
</div>

<!-- Giveaway Modal -->
<?php echo $footer; ?>
<script type="text/javascript">
<?php if( $giveaway_status == 1 && $OSP_ordertotal >= $giveaway_minimum_order && $giveaway_already_added == false ) : ?>
    $('.bd-example-modal-sm').modal();
    $(".focus").click(function() {
        $(".focus").css("background-color", "#ecf5f2");
        $(this).css("background-color", "#50c5c0");
        var product_id = $(this).attr('id');
        $('input[name=\'giveaway_product_id\']').val(product_id);
    });
    $("#no-gift").click(function() {
        $('.bd-example-modal-sm').modal('hide'); 
    });
<?php endif; ?>
</script>