<div class="box">
    <div class="box-heading"><h3><?php echo $heading_title; ?></h3></div>
    <div class="box-content">
        <div id="bestseller-carousel" class="owl-carousel product-carousel">
<?php foreach ($products as $product) { ?>
            <div class="item text-center">
                <div class="product-item">
                    <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
                    <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
<?php if ($product['price']) { ?>
                    <div class="price">
<?php if (!$product['special']) { ?>
                        <span><?php echo $product['price']; ?></span>
<?php } else { ?>
                        <span class="price-new"><?php echo $product['special']; ?></span> <span class="price-old"><?php echo $product['price']; ?></span>
<?php } ?>
<?php if ($product['tax']) { ?>
                        <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
<?php } ?>
                        <span class="unit-products"> <?php echo $product['unit']; ?> </span>
                    </div>
<?php } ?>
                    <div class="cart"><input type="button" value="<?php echo $button_cart; ?>"  class="btn btn-orange" onclick="cart.add('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>');" data-product_id="<?php echo $product['product_id']; ?>" /></div>
                </div>
            </div>
<?php } ?>
        </div>
    </div>
</div>
<script type="text/javascript"><!--
$('#bestseller-carousel').owlCarousel({
	items: 6,
	autoPlay: 5000,
	navigation: true,
	navigationText: ['<i class="fa fa-chevron-left fa-5x"></i>', '<i class="fa fa-chevron-right fa-5x"></i>'],
	pagination: false
});
--></script>