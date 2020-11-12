<?php if ($images) { ?>
      <div class="quick-additional" style="<?php if ($quick_column_right_width) { ?>width: <?php echo $quick_column_right_width; ?><?php } ?>">
        <?php foreach ($images as $image) { ?>
        <a href="<?php echo $image['popup']; ?>" onclick="$('.quick-image').attr('src', $(this).attr('href')); return false;" title="<?php echo $heading_title; ?>" ><img style="border: 1px solid #E7E7E7;" src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>
        <?php } ?>
      </div>
<?php } ?>

