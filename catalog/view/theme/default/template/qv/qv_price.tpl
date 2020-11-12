<?php if ($price) { ?>
    <div class="price">

        <?php if ($logged) { ?>
            <span class="ig_qk_price_label"><?php echo $text_wholesale_login; ?></span>
        <?php } else { ?>
            <span class="ig_qk_price_label"><?php echo $text_price; ?></span>
        <?php } ?>
 <input type="hidden" id="base_price_input" value="<?php echo isset($base_price) ? $base_price : '0' ?>">
        <?php if (!$special) { ?>
            <?php
            echo "<span class='price-new'>" . $price . " "
            . "</span> <span class='price_red'> / </span> "
            . "<span class = 'price_red' id='quantity_span'></span> "
            . "<span class='price_red' id='unit_dis'>" . $unit_singular . "</span>";
            
            ?>
        <?php } else { ?>
            <span class="cossed-price">
                <?php echo $price; ?>
            </span> 
            <span data-price="<?php echo $special; ?>" class="price price-new">
                <?php echo $special; ?>
            </span>        <?php } ?>
   <div id="information_image_price" style="display: none;">
                        <img id="option_tooltip"   src="admin/view/image/information.png" alt="" class="pay-img"/>
            </div>
        <br />
        <span id='converstion_string_display' style="display:none; background-color:#456a9e;margin-left: 75px; color:#FFFFFF;"></span>
         <input id="plural_unit" type="hidden" value ="<?php echo $unit_plural;?>">
        <?php /* if ($tax) { ?>
            <span class="price-tax">
            <div class="width_100Float" style="">
               
                <strong>Unit Price (Tax  Excluded): <?php echo $tax . " / " . $unit_singular;?> </strong>
            </div>
            </span><br />
            <?php //echo $text_tax;?> <?php //echo $tax;?>
        <?php }*/ ?>
        <?php if ($points) { ?>
            <span class="reward"><small><?php echo $text_points; ?> <?php echo $points; ?></small></span><br />
        <?php } ?>
        <?php if ($discounts) { ?>
            <br />
             <?php } ?>
          

    </div>
<?php } ?>
