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
                            <input type="text" name="quantity[<?php echo $product['cart_id']; ?>]" value="<?php echo $product['quantity']; ?>" size="1" class="form-control">
                            <span class="input-group-btn">
                                <button type="submit" data-toggle="tooltip" title="<?php echo $button_update; ?>" class="btn btn-primary" data-original-title="<?php echo $button_update; ?>"><i class="fa fa-refresh"></i></button>
                                <button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger" onclick="cart.remove('<?php echo $product['cart_id']; ?>');" data-original-title="<?php echo $button_remove; ?>"><i class="fa fa-times-circle"></i></button>
                            </span>
                    </div>
    
    
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