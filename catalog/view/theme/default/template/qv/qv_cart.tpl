      <div  class="price_grouped_product_box">
                                <div class="quantity_box_product_group">
                                    <p class=" entry flt group_qty_box">
                                        <label class="group_product_text_qty"><?php echo $text_qty; ?></label>
                                        <input type="text" class="quantity qty_group_product" id="qv_qty" name="quantity"  value="<?php echo $minimum; ?>" />
                                        <input type="hidden" name="original_price" value="<?php echo $old_price; ?>" >
                                        <input id="product_id_change" type="hidden" value="<?php echo $product_id ?>"  name="product_id">
                                    </p>
                                    <br>
                                    
                                    <?php if (empty($unit_datas)) { ?>
                               <!-- <span class='price_red' id='unit_dis'><?php echo $unit_singular; ?></span> -->
                                    <?php } else { ?>
                                
                               			 <div class="qv_ig_MetalType qv_ig_Units units_grouped">
                                    <select class="get-unit-data id_convert_long" id="qv_get-unit-data">
                                        <?php foreach ($unit_datas as $unit_data) { ?>
                                            <option name="<?php echo $unit_data['name']; ?>" data-value ="<?php echo $unit_data['unit_conversion_product_id']; ?>" value="<?php echo $unit_data['convert_price']; ?>">
                                                <?php echo $unit_data['name']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="information_image">
                                    <img class=" option_tooltip"   src="admin/view/image/information.png" alt=""/>
                                </div>  
<?php } ?>
                            <input type="hidden" class="unit_conversion_values" name ="unit_conversion_values" value="">
                            <input type="hidden" id="default_conversion_value_name" name ="default_conversion_value_name" value="">
                                  
                                    <div class="add_img_group">
                                        <img src="catalog/view/theme/default/img/satiscfaction_image.png" alt="" class="money-back"/>
                                         <!--                            <a href="#" class="qty-cart">Add to cart <img src="img/cart.png" alt=""/></a>-->
                                        <img src="catalog/view/theme/default/img/paypal_group.png" alt="" class="pay-img"/>
                                    </div>
                                </div>
                                <div class="pay-100 cart_product_group"> 
                                	<div class="cart-button-display">
                                     		<input type="button" id="button-cart" class="qty-cart button-cart" value="Add to Cart" />
                                        </div>
                                        <div  class="loading-display" style="padding-left: 15px; display:none;">
                                        	<img src="catalog/view/theme/default/img/ajax-loading.gif" alt="" class="loading" width="75px"/>
                                        </div>
                                        
                                    <div class="links link_product"><a onclick="addToWishList('<?php echo $product_id; ?>');"><?php echo $button_wishlist; ?></a>
                                        <a onclick="addToCompare('<?php echo $product_id; ?>');"><?php echo $button_compare; ?></a></div>
                                    <div id="notification-quick" style="width: 240px;margin-top:25px;padding-top:5px"></div>

                                </div>
                                <div class="clearfix"></div>



                            </div>
        <?php if ($minimum > 1) { ?>
        <div class="minimum"><?php echo $text_minimum; ?></div>
        <?php } ?>
      </div>
      
<script>
    $(document).ready(function() {
		
		
//        var qua = $("table.quickbuy").find('.quy').val();
//        alert(qua);
        
//        GroupProduct.updatePrice_qv();
    });
</script>