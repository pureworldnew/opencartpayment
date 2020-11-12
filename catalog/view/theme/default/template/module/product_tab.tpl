<script type="text/javascript" src="catalog/view/javascript/responsiveCarousel.js"></script> 

<div class="product-tab-container this_box_tab"> 
    <div class="featured_home">Featured Products</div>
    <div id="tabs-<?php echo $module; ?>" class="htabs">
        <?php if ($latest_products) { ?>
            <a href="#tab-latest-<?php echo $module; ?>"><?php echo $tab_latest; ?></a>
        <?php } ?>

        <?php if ($featured_products) { ?>
            <a href="#tab-featured-<?php echo $module; ?>"><?php echo $tab_featured; ?></a>
        <?php } ?> 
		
         <?php if ($bestseller_products) { ?>
            <a href="#tab-bestseller-<?php echo $module; ?>"><?php echo $tab_bestseller; ?></a>
        <?php } ?> 

    </div>  
    <?php if ($latest_products) { ?>
        <div id="tab-latest-<?php echo $module; ?>" class="tab-content main_home_page_tab only_crousel">
            <div class="box-product ig_slider jcarousel-skin-tango crsl-items"  data-navigation="NAV-ID">
                <ul id="mycarousel" class="jcarousel-skin-tango crsl-wrap" >
                    <?php foreach ($latest_products as $product) { ?>
                        <li class="crsl-item">
                            <div class="name"><a href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>"><?php
                                    if (strlen($product['name']) > 20) {
                                        echo substr($product['name'], 0, 15) . "...";
                                    } else {
                                        echo $product['name'];
                                    }
                                    ?></a></div>
                            <?php if ($product['thumb']) { ?>
                                <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
                            <?php } else { ?>
                                <div class="img-box"><a href="<?php echo $product['href']; ?>"><img src="<?php echo HTTP_SERVER; ?>catalog/view/theme/default/image/no_product.jpg" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>"  /></a></div>
                                <?php } ?>
                                <?php if ($product['price']) { ?>
                                <div class="price">
                                    <?php if (!$product['special']) { ?>
                                        <span><?php echo $product['price']; ?></span>
                                        <span class="unit-products"><?php echo $product['unit']; ?></span>
                                    <?php } else { ?>
                                        <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
                                <?php } ?>
                                </div>
        <?php } ?>

                            <div class="cart"><input type="button" value="<?php echo $button_cart; ?>"  class="button new_cart_button" data-product_id="<?php echo $product['product_id']; ?>"/></div>
                        </li>
    <?php } ?>
                </ul>
            </div>
            <div id="NAV-ID" class="crsl-nav">
                <a href="#" class="previous"></a>
                <a href="#" class="next"></a>
            </div>
        </div>
<?php } ?>

	<?php if($bestseller_products){ ?>
        <div id="tab-bestseller-<?php echo $module; ?>" class="tab-content main_home_page_tab">
            <div class="box-product ig_slider jcarousel-skin-tango">
                <ul id="mycarousel3" class="jcarousel-skin-tango first-and-second-carousel" >
                                <?php foreach ($bestseller_products as $product) { ?>
                        <li>
                            <div class="name"><a href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>"><?php
                                    if (strlen($product['name']) > 20) {
                                        echo substr($product['name'], 0, 15) . "...";
                                    } else {
                                        echo $product['name'];
                                    }
                                    ?></a></div>
                            <?php if ($product['thumb']) { ?>
                                <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
                                <?php } else { ?>
                                <div class="img-box"><a href="<?php echo $product['href']; ?>"><img src="<?php echo HTTP_SERVER; ?>catalog/view/theme/default/image/no_product.jpg" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>"/></a></div>
        <?php } ?>
                                <?php if ($product['price']) { ?>
                                <div class="price">
                                    <?php if (!$product['special']) { ?>
                                        <span><?php echo $product['price']; ?></span>
                                <?php } else { ?>
                                        <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
            <?php } ?>
                                </div>
                        <?php } ?>

                            <div class="cart"><input type="button" value="<?php echo $button_cart; ?>" onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button" /></div>
                        </li>
        <?php } ?>
                </ul>
            </div>
        </div>
<?php } ?>

  <?php if ($featured_products) { ?>
        <div id="tab-featured-<?php echo $module; ?>" class="tab-content main_home_page_tab">
            <div class="box-product ig_slider jcarousel-skin-tango">
                <ul id="mycarousel3" class="jcarousel-skin-tango first-and-second-carousel" >
                                <?php foreach ($featured_products as $product) { ?>
                        <li>
                            <div class="name"><a href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>"><?php
                                    if (strlen($product['name']) > 20) {
                                        echo substr($product['name'], 0, 15) . "...";
                                    } else {
                                        echo $product['name'];
                                    }
                                    ?></a></div>
                            <?php if ($product['thumb']) { ?>
                                <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
                                <?php } else { ?>
                                <div class="img-box"><a href="<?php echo $product['href']; ?>"><img src="<?php echo HTTP_SERVER; ?>catalog/view/theme/default/image/no_product.jpg" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>"/></a></div>
        <?php } ?>
                                <?php if ($product['price']) { ?>
                                <div class="price">
                                    <?php if (!$product['special']) { ?>
                                        <span><?php echo $product['price']; ?></span>
                                        <span class="unit-products"><?php echo $product['unit']; ?></span>
                                <?php } else { ?>
                                        <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
            <?php } ?>
                                </div>
                        <?php } ?>

                            <div class="cart"><input type="button" value="<?php echo $button_cart; ?>" onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button" /></div>
                        </li>
        <?php } ?>
                </ul>
            </div>
        </div>
<?php } ?>




</div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        
//        $('.colorbox').colorbox({
//            overlayClose: true,
//            opacity: 0.5,
//            width: '60%',
//            maxHeight: '80%'
//
//        });
    });
        $('#tabs-<?php echo $module; ?> a').tabs();
        //if (screen.width <= 1024) {
            $('.new_cart_button').on('click touchstart', function() {
                var quantity = typeof (quantity) != 'undefined' ? quantity : 1;
                var product_id = $(this).data('product_id');
                $.ajax({
                    url: 'index.php?route=checkout/cart/add',
                    type: 'post',
                    data: 'product_id=' + product_id + '&quantity=' + quantity,
                    dataType: 'json',
                    success: function(json) {
                        $('.success, .warning, .attention, .information, .error').remove();

                        if (json['redirect']) {
                            location = json['redirect'];
                        }

                        if (json['success']) {
                            $('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

                            $('.success').fadeIn('slow');

                            $('#cart-total').html(json['total']);

                            $('html, body').animate({scrollTop: 0}, 'slow');
                        }
                    }
                });
            });
        //}
            $(window).bind("orientationchange", function() {
                if (window.innerHeight > window.innerWidth) {
                    location.reload();
                }
                if (window.innerHeight < window.innerWidth) {

                    $('.crsl-items').carousel({visible: 2, itemMinWidth: 130});
                     location.reload();
                }

            });

        if (screen.width < 570) {
            //  alert(screen.height);
            if (window.innerHeight > window.innerWidth) {
                $('.crsl-items').carousel({visible: 1, itemMinWidth: 130});
            }

            if (window.innerHeight < window.innerWidth) {
                $('.crsl-items').carousel({visible: 2, itemMinWidth: 130});
            }
            
        }
            else if (window.innerWidth == 768) {
                $('.crsl-items').carousel({visible: 4, itemMinWidth: 130});
            }
            else {
                $('.crsl-items').carousel({visible: 5, itemMinWidth: 130});

            }
             if (screen.width <= 568) {
            $('.first-and-second-carousel').myjcarousel({
                vertical: false,
                visible: 9,
                scroll: 1,
                wrap: "null",
                contentWidth: 200,
                itemFallbackDimension: 300
            });
             }
             else if(screen.width <= 1024){
                $('.first-and-second-carousel').myjcarousel({
                vertical: false,
                visible: 9,
                scroll: 1,
                wrap: "null",
                contentWidth: 600,
                itemFallbackDimension: 300
            });
             }
             else{  $('.first-and-second-carousel').myjcarousel({
                vertical: false,
                visible: 9,
                scroll: 1,
                wrap: "null",
                contentWidth: 940,
                itemFallbackDimension: 300
            });
        }
</script>


