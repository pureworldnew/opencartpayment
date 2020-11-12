<link href="catalog/view/javascript/css/productlabel.css" rel="stylesheet" type="text/css" />
<h3><?php echo $heading_title; ?></h3>
<div class="row">
  <?php foreach ($products as $product) { ?>
  <div class="product-layout col-lg-3 col-md-3 col-sm-6 col-xs-12">
    <div class="product-thumb transition">
  <!--New product count-->
        <?php $datetime1 = new DateTime($product['date']);
        $datetime2 = new DateTime();
        $interval = $datetime1->diff($datetime2);
        $date_diff_count=$interval->format('%a');
        if($new_product_status == 1 ) {
        if($new_product_db_days > $date_diff_count) {
        if($position_new==1) { ?>
        <div class="new_product1" style="background-color: <?php echo $new_product_db_label_color;?>;color:<?php echo $new_product_db_text_color;?>;">
            <?php echo $new_product_db_text; ?>
        </div>
        <?php } elseif($position_new==2) { ?>
        <div class="new_product2" style="background-color: <?php echo $new_product_db_label_color;?>;color:<?php echo $new_product_db_text_color;?>;">
            <?php echo $new_product_db_text; ?>
        </div>
        <?php } elseif($position_new==3) { ?>
        <div class="new_product3" style="background-color: <?php echo $new_product_db_label_color;?>;color:<?php echo $new_product_db_text_color;?>;">
            <?php echo $new_product_db_text; ?>
        </div>
        <?php } elseif($position_new==4) { ?>
        <div class="new_product4" style="background-color: <?php echo $new_product_db_label_color;?>;color:<?php echo $new_product_db_text_color;?>;">
            <?php echo $new_product_db_text; ?>
        </div>
        <?php } } } ?>
        <!--New product count-->

        <!--discount product-->
        <?php if($discount_product_status==1) {
        if($discount_product_db_percent < $product['percent_value']) { ?>
        <?php if ($product['special'])  {
         if($position_discount==1) { ?>
        <div class="discount_product1" style=" background-color: <?php echo $discount_product_db_label_color?>;color:<?php echo $discount_product_db_text_color;?>;">
            <?php echo $product['percent']; ?>
        </div>
        <?php } elseif($position_discount==2) { ?>
        <div class="discount_product2" style=" background-color: <?php echo $discount_product_db_label_color?>;color:<?php echo $discount_product_db_text_color;?>;">
            <?php echo $product['percent']; ?>
        </div>
        <?php } elseif($position_discount==3) { ?>
        <div class="discount_product3" style=" background-color: <?php echo $discount_product_db_label_color?>;color:<?php echo $discount_product_db_text_color;?>;">
            <?php echo $product['percent']; ?>
        </div>
        <?php } elseif($position_discount==4) { ?>
        <div class="discount_product4" style=" background-color: <?php echo $discount_product_db_label_color?>;color:<?php echo $discount_product_db_text_color;?>;">
            <?php echo $product['percent']; ?>
        </div>
        <?php } } } } ?>
        <!--discount product-->
        <!--outofstock product-->
        <?php if($outofstock_product_status==1) {
               if($product['stockquantity']==0) {

                if($position_outofstock==1) { ?>
        <div class="outofstock_product1" style="background-color: <?php echo $outofstock_product_db_label_color?>;color:<?php echo $outofstock_product_db_text_color;?>;">
            <?php echo $outofstock_product_db_text;  ?>
        </div>
        <?php } elseif($position_outofstock==2) { ?>
        <div class="outofstock_product2" style="background-color: <?php echo $outofstock_product_db_label_color?>;color:<?php echo $outofstock_product_db_text_color;?>;">
            <?php echo $outofstock_product_db_text;  ?>
        </div>
        <?php } elseif($position_outofstock==3) { ?>
        <div class="outofstock_product3" style="background-color: <?php echo $outofstock_product_db_label_color?>;color:<?php echo $outofstock_product_db_text_color;?>;">
            <?php echo $outofstock_product_db_text;  ?>
        </div>
        <?php } elseif($position_outofstock==3) { ?>
        <div class="outofstock_product4" style="background-color: <?php echo $outofstock_product_db_label_color?>;color:<?php echo $outofstock_product_db_text_color;?>;">
            <?php echo $outofstock_product_db_text;  ?>
        </div>

        <?php } } } ?>
        <!--outofstock product-->

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
          <?php if ($product['tax']) { ?>
          <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
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
