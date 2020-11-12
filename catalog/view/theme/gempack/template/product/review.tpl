<?php if ($reviews) { ?>
  <?php foreach ($reviews as $review) { ?>
    <table class="table table-striped table-bordered">
      <tr>
        <td style="width: 40%;"><strong><?php echo ucfirst($review['author']); ?></strong></td>
        <td class="text-right"><?php echo $review['date_added']; ?></td>
      </tr>
      <tr>
        <td class="background_td">
        <?php if($review['image']){ ?>
          <img src="<?php echo $review['image']; ?>" alt="<?php echo $review['name']; ?>" style="width:150px;" />
        <?php } else { ?> 
          <img src="<?php echo HTTP_SERVER; ?>catalog/view/theme/default/image/no_product.jpg" style="width:150px;" />
        <?php } ?>
          <div class="pull-right">
            <a href="<?php echo $review['href']; ?>"><?php echo $review['name']; ?> </a><br><?php echo ' - ' . $review['sku'] . '<br />'; ?>
            <?php if ($review['product_options']) { ?>
              <?php foreach ($review['product_options'] as $key => $product_option) { ?>
                  <span><?php echo '- ' . $key . ' : ' . $product_option . '<br />'; ?></span>
              <?php } ?>
            <?php } ?>
          </div>
          <div class="clearfix"></div>
          <div style="margin-top:10px;">
          <?php for ($i = 1; $i <= 5; $i++) { ?>
            <?php if ($review['rating'] < $i) { ?>
              <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
            <?php } else { ?>
              <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
            <?php } ?>
          <?php } ?>
          </div>
        </td>
        <td>
          <p><?php echo $review['text']; ?></p>
        </td>
      </tr>
    </table>
  <?php } ?>
  <div class="text-right"><?php echo $pagination; ?></div>
<?php } else { ?>
  <p><?php echo 'Be the first to review!'; ?></p>
<?php } ?>
