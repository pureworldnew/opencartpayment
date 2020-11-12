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