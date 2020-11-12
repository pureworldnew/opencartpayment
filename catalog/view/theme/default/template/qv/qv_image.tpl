<?php if ($quick_thumb) { ?>
      	<div class="image"><a href="<?php echo $product_url; ?>" title="<?php echo $heading_title; ?>" ><img src="<?php echo $quick_thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" id="image" class="quick-image" style="width: <?php echo $quick_config_image_thumb_width; ?>px;" /></a></div><br /><br />
<?php } else{ ?>
        <div class="image"><a href="<?php echo $product_url; ?>" title="<?php echo $heading_title; ?>" ><img src="<?php echo HTTP_SERVER; ?>catalog/view/theme/default/image/no-image.gif" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" id="image" class="quick-image" style="width: <?php echo $quick_config_image_thumb_width; ?>px;" /></a></div><br /><br />
<?php } ?>
