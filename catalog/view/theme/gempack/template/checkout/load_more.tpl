<?php foreach ($products as $product) { ?>
                            <tr class="cart-id" id="<?php echo $product['cart_id']; ?>">
                                <td class="text-center"><?php if ($product['thumb']) { ?><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-thumbnail" /></a><?php } ?></td>
                                <td class="text-left">
                                    <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a><?php if (!$product['stock']) { ?> <span class="text-danger">***</span><?php } ?>
<?php foreach ($product['option'] as $option) { ?>
<?php  $option_name = (explode(" ",$option['name'])); 
$option_unit_name = trim(end($option_name)); ?>
<?php if($option_unit_name == 'units') {
 $option['name'] = $option_unit_name;
} ?>
                                    <br />- <small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
<?php } ?>
<?php if($product['unit'] && $product['unit']['convert_price'] !=1) { ?>
                                    <br />- <small style="color:#DD0205; font-weight:bold;"><?php echo number_format(($product['unit']['convert_price'] * $product['quantity']),2); ?> <?php echo $product['unit_dates_default']['name']; ?> = <?php echo $product['quantity']; ?> <?php echo $product['unit']['unit_value_name']; ?></small>
<?php } ?>
<?php if ($product['reward']) { ?>
                                    <br /><small><?php echo $product['reward']; ?></small>
<?php } ?>
<?php if ($product['recurring']) { ?>
                                    <br /><span class="label label-info"><?php echo $text_recurring_item; ?></span> <small><?php echo $product['recurring']; ?></small>
<?php } ?>
                                </td>
                                <td class="text-center"><?php echo $product['model']; ?></td>
                                <td class="text-left">
                                    <div class="input-group btn-block" style="max-width: 200px;">
                                        <input type="text" name="quantity[<?php echo $product['cart_id']; ?>]" value="<?php echo $product['quantity']; ?>" size="1" class="form-control">
                                        <span class="input-group-btn">
                                            <button type="submit" data-toggle="tooltip" title="<?php echo $button_update; ?>" class="btn btn-primary" data-original-title="<?php echo $button_update; ?>"><i class="fa fa-refresh"></i></button>
                                            <button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger" onclick="cart.remove('<?php echo $product['cart_id']; ?>');" data-original-title="<?php echo $button_remove; ?>"><i class="fa fa-times-circle"></i></button>
                                        </span>
                                    </div>
                                    <br />
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
                                            <option  name="<?php echo $unit_data['name']; ?>" data-value ="<?php echo $unit_data['unit_conversion_product_id']; ?>" value="<?php echo $unit_data['unit_conversion_product_id']; ?>" <?php echo $checked; ?>><?php echo $unit_data['name']; ?></option>
<?php } ?>
                                        </select>
                                    </div>
<?php } ?>
                                </td>
                                <td class="text-right">
                                    <strong><?php echo $product['price']; ?></strong>
<?php if($product['unit']) { ?>
                                    <br /><small> per <?php echo $product['DefaultUnitName']['unit_singular']; ?> </small>
<?php } else { ?>
                                    <br /><small> per <?php echo $product['DefaultUnitName']['unit_singular']; ?> </small>
<?php } ?>
                                </td>
                                <td class="text-right"><?php echo $product['total']; ?></td>
                            </tr>
                            <tr>
                            <td colspan="6"><label><input type="checkbox" class="select-for-remove" value="<?php echo $product['cart_id']; ?>"> Select</label></td>
                            </tr>
<?php } ?>