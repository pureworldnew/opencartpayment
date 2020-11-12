<span id='group_indicator' style="display: none;" data-group_indicator='<?php if(isset($group_indicator_id)) {echo $group_indicator_id;} ?>'></span>
<?php if (isset($product_grouped) && !empty($product_grouped)) { ?>
    <div class="grouped-product gp-group">
        <b><?php echo "Select ".$text_groupby ?></b>
        <select name="select_product" class='qv_grouped_product_select fancySelect'>
            <?php foreach ($product_grouped as $product) { ?> 
                <option <?php if($product['is_requested_product']) { echo "selected='selected'"; } ?> value="<?php echo $product['product_id']; ?>"><?php echo $product['product_name']; ?> </option>
            <?php } ?>
        </select>
        <div style="position:absolute;"><div class="gp-loader"></div></div>
    </div>
<?php } ?>

<?php if ($options) { ?>
    <div class="options">
    <?php foreach ($options as $option) {  
        if ($option['type'] == 'radio') { ?>
        <div id="option-<?php echo $option['product_option_id']; ?>" class="option option_custom length_content ig_<?php echo str_replace(' ', '', $option['name']); ?>">
            <?php if ($option['required']) { ?>
            <span class="required option_label">* </span><b><?php echo $option['name']; ?>:</b>
            <?php } ?>
            <div class="option_container">
                <?php foreach ($option['option_value'] as $option_value) { ?>
                <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" />
                <label for="option-value-<?php echo $option_value['product_option_value_id']; ?>" data-val="<?php echo $option_value['name']; ?>" ><?php echo $option_value['name']; ?></label>
                <?php } ?> 
            </div>
        </div>
    <?php } ?>
    <!---------- Custom Radio Options ------------->
    <?php if ($option['type'] == 'select') { ?>
        <div id="option-<?php echo $option['product_option_id']; ?>" class="option ig_<?php echo str_replace(' ', '', $option['name']); ?>">
            <?php if ($option['required']) { ?>
                <span class="required">*</span>
            <?php } ?>
            <b><?php echo $option['name']; ?>:</b>
            <select name="option[<?php echo $option['product_option_id']; ?>]">
                <?php echo $option['metal_type'];
                if ($option['metal_type'] > 1) { ?>
                    <option value=""><?php echo $text_select; ?></option>
                <?php }
                ?>
                <?php foreach ($option['option_value'] as $option_value) { ?>
                <?php if ($option_value['quantity'] <= 0) { ?>
                <option qty="<?php echo $option_value['quantity'];?>" <?php if(isset($option_value['is_requested_option_value']) && $option_value['is_requested_option_value']) { echo "selected='selected'";} ?> data-option_value_image="<?php if(isset($option_value['image']) && !empty($option_value['image']) && !strpos($option_value['image'], 'no_image')){ echo $option_value['image'];}?>" data-price="<?php echo ($option_value['price2'] ? $option_value['price2'] : '' ) ?>" value="<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']." - ".$option_out_of_stock; ?></option>
                <?php } else { ?>
                <option qty="<?php echo $option_value['quantity'];?>" <?php if(isset($option_value['is_requested_option_value']) && $option_value['is_requested_option_value']) { echo "selected='selected'";} ?> data-option_value_image="<?php if(isset($option_value['image']) && !empty($option_value['image']) && !strpos($option_value['image'], 'no_image')){ echo $option_value['image'];}?>" data-price="<?php echo ($option_value['price2'] ? $option_value['price2'] : '' ) ?>" value="<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?></option>
    <?php }} ?>
            </select>
        </div>
    <?php } ?>

    <?php if ($option['type'] == 'checkbox') { ?>
    <div id="option-<?php echo $option['product_option_id']; ?>" class="option product_checkboxes">
        <?php if ($option['required']) { ?>
        <span class="required">* </span><b><?php echo $option['name']; ?>:</b><br/>
        <?php } ?>
        <?php foreach ($option['option_value'] as $option_value) { ?>
        <input type="checkbox" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" />
        <label for="option-value-<?php echo $option_value['product_option_value_id']; ?>" ><?php echo $option_value['name']; ?>
            <?php if ($option_value['price']) { ?>
            (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
            <?php } ?>
        </label><br/>
        <?php } ?>
    </div>
    <?php } ?>
    <?php if ($option['type'] == 'image') { ?>
    <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
        <?php if ($option['required']) { ?>
        <span class="required">* </span><b><?php echo $option['name']; ?>:</b>
        <?php } ?>

        <table class="option-image">
            <?php foreach ($option['option_value'] as $option_value) { ?>
            <tr>
                <td style="width: 1px;"><input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" /></td>
                <td><label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" /></label></td>
                <td><label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
                        <?php if ($option_value['price']) { ?>
                        (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                        <?php } ?>
                    </label></td>
            </tr>
            <?php } ?>
        </table>
    </div>
    <?php } ?>
    <?php if ($option['type'] == 'text') { ?>
    <div id="option-<?php echo $option['product_option_id']; ?>" class="option product_textfield">
        <?php if ($option['required']) { ?>
        <span class="required">* </span><b><?php echo $option['name']; ?>:</b>
        <?php } ?>

        <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" />
    </div>
    <?php } ?>
    <?php if ($option['type'] == 'textarea') { ?>
    <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
        <?php if ($option['required']) { ?>
        <span class="required">* </span><b><?php echo $option['name']; ?>:</b>
        <?php } ?>

        <textarea name="option[<?php echo $option['product_option_id']; ?>]" cols="40" rows="5"><?php echo $option['option_value']; ?></textarea>
    </div>
    <?php } ?>
    <?php if ($option['type'] == 'file') { ?>
    <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
        <?php if ($option['required']) { ?>
        <span class="required">* </span><b><?php echo $option['name']; ?>:</b>
        <?php } ?>

        <input type="button" value="<?php echo $button_upload; ?>" id="button-option-<?php echo $option['product_option_id']; ?>" class="button">
        <input type="hidden" name="option[<?php echo $option['product_option_id']; ?>]" value="" />
    </div>
    <?php } ?>
    <?php if ($option['type'] == 'date') { ?>
    <div id="option-<?php echo $option['product_option_id']; ?>" class="option product_textfield">
        <?php if ($option['required']) { ?>
        <span class="required">* </span><b><?php echo $option['name']; ?>:</b>
        <?php } else { ?>
        <b><?php echo $option['name']; ?>:</b>
        <?php } ?>
        <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="date" />
    </div>
    <?php } ?>
    <?php if ($option['type'] == 'datetime') { ?>
    <div id="option-<?php echo $option['product_option_id']; ?>" class="option product_textfield">
        <?php if ($option['required']) { ?>
        <span class="required">* </span><b><?php echo $option['name']; ?>:</b>
        <?php } else { ?>
        <b><?php echo $option['name']; ?>:</b>
        <?php } ?>
        <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="datetime" />
    </div>
    <?php } ?>
    <?php if ($option['type'] == 'time') { ?>
    <div id="option-<?php echo $option['product_option_id']; ?>" class="option product_textfield">
        <?php if ($option['required']) { ?>
        <span class="required">*</span>
        <?php } ?>
        <b><?php echo $option['name']; ?>:</b><br />
        <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="time" />
    </div>
    <?php } } ?>
</div>
<?php } ?>
<script>
function showdata()
{
    GroupProduct.updatePrice();
}
</script>