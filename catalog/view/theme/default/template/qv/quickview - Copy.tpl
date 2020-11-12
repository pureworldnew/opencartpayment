<script type="text/javascript" src="catalog/view/javascript/tooltipsy.min.js"></script>
<style>
    .tooltipsy
    {
        padding: 10px;
        max-width: 200px;
        color: #303030;
        background-color: #DDDDDD;
    }
    #content {display: block;margin-bottom: 0;}
    .quick-additional {padding-bottom: 5px;}
    .product-info, .product-info .price, .product-info .description  {margin-bottom: 0;}
    .product-info .price, .product-info .cart, .product-info .image {padding-top: 5px; border:none;margin-bottom: 5px}
    .product-info .image {padding-left: 0;padding-right: 0; float: none;}
    .htabs a {padding: 7px 6px 6px;}
    .product-info .qv-left, .product-info .qv-right {display: table-cell;vertical-align: top;}
    .tab-content {margin-bottom: 0;}
    .product-info .description {border:none;}
    p {margin-bottom: 0;}
    .product-info .cart div { vertical-align: middle;}
    .product-info .cart div > span {color: #999999;display: block;padding-top: 7px;}
    .success {width: 80%}
</style>

<div id="content">
    <div class="description"><?php echo $quick_ck_des; ?></div>
    <div id="qvtabs" class="htabs"><a href="#tab-general"><?php echo $quick_name_general; ?></a>
        <?php if ($quick_tab_description) { ?>
            <a href="#tab-descriptionqv"><?php echo $tab_description; ?></a>
        <?php } ?>
        <?php if ($quick_tab_attribute && $attribute_groups) { ?>
            <a href="#tab-attributeqv"><?php echo $tab_attribute; ?></a>
        <?php } ?>
        <?php if ($quick_tab_review && $review_status) { ?>
            <a href="#tab-reviewqv"><?php echo $tab_review; ?></a>
        <?php } ?>
        <?php if ($quick_tab_related && $products) { ?>
            <a href="#tab-relatedqv"><?php echo $tab_related; ?> (<?php echo count($products); ?>)</a>
        <?php } ?>
    </div>
    <input id="plural_unit" type="hidden" value ="<?php echo $unit_plural; ?>">
    <div id="tab-general" class="tab-content"><div id="notification-quick"></div>
        <div class="product-info" id='product-info-qv'>
            <div class="qv-left" style="<?php if ($quick_column_left_width) { ?>width: <?php echo $quick_column_left_width; ?><?php } ?>">
                <?php if (!empty($quick_posleft1)) include (DIR_TEMPLATE .  'default/template/qv/qv_' . $quick_posleft1 . '.tpl'); ?>
                <?php if (!empty($quick_posleft2)) include (DIR_TEMPLATE .  'default/template/qv/qv_' . $quick_posleft2 . '.tpl'); ?>
                <?php if (!empty($quick_posleft3)) include (DIR_TEMPLATE .  'default/template/qv/qv_' . $quick_posleft3 . '.tpl'); ?>
                <?php if (!empty($quick_posleft4)) include (DIR_TEMPLATE .  'default/template/qv/qv_' . $quick_posleft4 . '.tpl'); ?>
                <?php if (!empty($quick_posleft5)) include (DIR_TEMPLATE .  'default/template/qv/qv_' . $quick_posleft5 . '.tpl'); ?>
                <?php if (!empty($quick_posleft6)) include (DIR_TEMPLATE .  'default/template/qv/qv_' . $quick_posleft6 . '.tpl'); ?>
            </div>
            <div class="qv-right" style="<?php if ($quick_column_right_width) { ?>width: <?php echo $quick_column_right_width; ?><?php } ?> " >
                <?php if ($quick_h2) { ?><h2 class="quick_product_title"><?php echo $heading_title; ?></h2><?php } ?>
                <div class="price-div">
                    <?php if ($logged) { ?>
                        <?php if ($discounts) { ?>
                            <div class="price-scale">
                                <p class="he">Bulk Pricing and <br />Quantity Discounts</p>
                                <!--                                <div class="information_image discount_box">
                                                                    <img id="simpletooltip" src="admin/view/image/information.png" alt="" class="pay-img" 
                                                                         title="We offer discounts for wholesale customers depending on the quantity of each item ordered. Discounts are automatically applied in your cart."/>
                                                                </div> -->
                                <p class="mid"><span class="flt">

                                    </span><span class="flr scale-pr">Price</span></p>
                                <ul class="update_discount_price_group" >
                                    <li>
                                        <span class="scale-quantity"><?php echo "Non-Wholesale"; ?></span>

                                        <span class="scale-price"><?php echo $price_without_discount; ?></span>

                                    </li>
                                    <?php
                                    foreach ($discounts as $key => $discount) {
                                        if ($key == 0) {
                                            $nextArray = current($discounts);
                                        } else {
                                            $nextArray = next($discounts);
                                        }
                                        if (!empty($nextArray)) {
                                            $nextQuan = $nextArray['quantity'];
                                            $nextQuan--;
                                            $nextQuan = " - " . $nextQuan;
                                        } else {
                                            $nextQuan = "+";
                                        }
                                        ?>
                                        <li>
                                            <span class="scale-quantity">
                                                <?php echo $discount['quantity'] . $nextQuan . " " . $unit_plural; ?>
                                            </span>
                                            <span class="scale-price">
                                                <?php echo $this->currency->format($discount['price']); ?>
                                            </span>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>


                            <?php
                        }
                    } else {
                        ?>
                        <div class="price-scale-login">
                            <p><?php echo $text_price_login; ?></p>
                        </div>
                    <?php } ?> <div class="more_information">For Quotes on Significantly Larger Quantities, 
                        Please <a href="<?php echo $contact ?>"> Contact Us</a></div>
                </div>
                <?php if (!empty($quick_posright1)) include (DIR_TEMPLATE .  'default//template/qv/qv_' . $quick_posright1 . '.tpl'); ?>
                <?php if (!empty($quick_posright2)) include (DIR_TEMPLATE .  'default//template/qv/qv_' . $quick_posright2 . '.tpl'); ?>
                <?php if (!empty($quick_posright3)) include (DIR_TEMPLATE .  'default//template/qv/qv_' . $quick_posright3 . '.tpl'); ?>
                <?php if (!empty($quick_posright4)) include (DIR_TEMPLATE .  'default//template/qv/qv_' . $quick_posright4 . '.tpl'); ?>
                <?php if (!empty($quick_posright5)) include (DIR_TEMPLATE .  'default//template/qv/qv_' . $quick_posright5 . '.tpl'); ?>
                <?php if (!empty($quick_posright6)) include (DIR_TEMPLATE .  'default//template/qv/qv_' . $quick_posright6 . '.tpl'); ?>
            </div>
        </div>

        <?php if ($quick_general_bottom_description) { ?>
            <?php echo $description; ?>
        <?php } ?>
        <?php echo $quick_ck_des_bottom; ?>
    </div>

    <?php if ($quick_tab_description) { ?>
        <div id="tab-descriptionqv" class="tab-content"><?php echo $description; ?></div>
    <?php } ?>
    <?php if ($quick_tab_attribute && $attribute_groups) { ?>
        <div id="tab-attributeqv" class="tab-content">
            <table class="attribute">
                <?php foreach ($attribute_groups as $attribute_group) { ?>
                    <thead>
                        <tr>
                            <td colspan="2"><?php echo $attribute_group['name']; ?></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
                            <tr>
                                <td><?php echo $attribute['name']; ?></td>
                                <td><?php echo $attribute['text']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                <?php } ?>
            </table>
        </div>
    <?php } ?>
    <?php if ($quick_tab_review && $review_status) { ?>
        <div id="tab-reviewqv" class="tab-content">
            <div id="reviewqv"></div>
            <h2 id="reviewqv-title"><?php echo $text_write; ?></h2>
            <b><?php echo $entry_name; ?></b><br />
            <input type="text" name="name" value="" />
            <br />
            <br />
            <b><?php echo $entry_review; ?></b>
            <textarea name="text"  rows="8" style="width: 98%;"></textarea>
            <span style="font-size: 11px;"><?php echo $text_note; ?></span><br />
            <br />
            <b><?php echo $entry_rating; ?></b> <span><?php echo $entry_bad; ?></span>&nbsp;
            <input type="radio" name="rating" value="1" />
            &nbsp;
            <input type="radio" name="rating" value="2" />
            &nbsp;
            <input type="radio" name="rating" value="3" />
            &nbsp;
            <input type="radio" name="rating" value="4" />
            &nbsp;
            <input type="radio" name="rating" value="5" />
            &nbsp;<span><?php echo $entry_good; ?></span><br />
            <br />
            <b><?php echo $entry_captcha; ?></b><br />
            <input type="text" name="captcha" value="" />
            <br />
            <img src="index.php?route=product/product/captcha" alt="" id="captcha" /><br />
            <br />
            <div class="buttons">
                <div class="right"><a id="button-reviewqv" class="button"><?php echo $button_continue; ?></a></div>
            </div>
        </div>
    <?php } ?>
    <?php if ($quick_tab_related && $products) { ?>
        <div id="tab-relatedqv" class="tab-content">
            <div class="box-product">
                <?php foreach ($products as $product) { ?>
                    <div>
                        <?php if ($product['thumb']) { ?>
                            <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
                        <?php } ?>
                        <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
                        <?php if ($product['price']) { ?>
                            <div class="price">
                                <?php if (!$product['special']) { ?>
                                    <<?php echo $product['price']; ?>
                                <?php } else { ?>
                                    <span class="cossed-price"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
                                <?php } ?>
                            </div>
                        <?php } ?> 
                        <?php if ($product['rating']) { ?> 
                            <div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /></div>
                        <?php } ?>
                        <a onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button"><?php echo $button_cart; ?></a>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
</div>

<script type="text/javascript" src="catalog/view/javascript/fancySelect.js"></script>
<script type="text/javascript" src="catalog/view/javascript/qv_group.product.page.js?t=<?php echo time(); ?>"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
<script>
    $('#simpletooltip').tooltipsy({
        css: {
            'padding': '5px',
            'max-width': '200px',
            'color': '#33333',
            'background-color': '#DDDDD',
            '-moz-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
            '-webkit-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
            'text-shadow': 'none'
        }
    });

    $('#cboxWrapper').not('#simpletooltip').click(function(event) {
        $('#simpletooltip').tooltipsy({
            hide: function(e, $el) {
                $el.hide();
            }
        });
    });

    $('#button-quickcart').bind('click', function() {
        $.ajax({
            url: 'index.php?route=checkout/cart/add',
            type: 'post',
            data: $('#product-info-qv input[type=\'text\'], #product-info-qv input[type=\'hidden\'], #product-info-qv input[type=\'radio\']:checked, #product-info-qv input[type=\'checkbox\']:checked, #product-info-qv select, #product-info-qv textarea'),
            dataType: 'json',
            success: function(json) {
                $('.success, .warning, .attention, information, .error').remove();

                if (json['error']) {
                    if (json['error']['option']) {
                        for (i in json['error']['option']) {
                            $('#option-' + i).after('<span class="error">' + json['error']['option'][i] + '</span>');
                        }
                    }
                }

                if (json['success']) {
                    $('#notification-quick').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

                    $('.success').fadeIn('slow');

                    $('#cart-total').html(json['total']);
                }
            }
        });
    });
    $('#reviewqv .pagination a').live('click', function() {
        $('#reviewqv').fadeOut('slow');

        $('#reviewqv').load(this.href);

        $('#reviewqv').fadeIn('slow');

        return false;
    });

    $('#reviewqv').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');

    $('#button-reviewqv').bind('click', function() {
        $.ajax({
            url: 'index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',
            type: 'post',
            dataType: 'json',
            data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : '') + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),
            beforeSend: function() {
                $('.success, .warning').remove();
                $('#button-reviewqv').attr('disabled', true);
                $('#reviewqv-title').after('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
            },
            complete: function() {
                $('#button-reviewqv').attr('disabled', false);
                $('.attention').remove();
            },
            success: function(data) {
                if (data['error']) {
                    $('#reviewqv-title').after('<div class="warning">' + data['error'] + '</div>');
                }

                if (data['success']) {
                    $('#reviewqv-title').after('<div class="success">' + data['success'] + '</div>');

                    $('input[name=\'name\']').val('');
                    $('textarea[name=\'text\']').val('');
                    $('input[name=\'rating\']:checked').attr('checked', '');
                    $('input[name=\'captcha\']').val('');
                }
            }
        });
    });
    $('#qvtabs a').tabs();
    $(document).ready(function() {
		
		var default_conversion_value_name = $("#get-unit-data-qv").find('option:selected').attr('name');
		$('#default_conversion_value_name').val(default_conversion_value_name);
		
        if ($.browser.msie && $.browser.version == 6) {
            $('.date, .datetime, .time').bgIframe();
        }

        $('.date').datepicker({dateFormat: 'yy-mm-dd'});
        $('.datetime').datetimepicker({
            dateFormat: 'yy-mm-dd',
            timeFormat: 'h:m'
        });
        $('.time').timepicker({timeFormat: 'h:m'});
    });
    function addToWishListQuick(product_id) {
        $.ajax({
            url: 'index.php?route=account/wishlist/add',
            type: 'post',
            data: 'product_id=' + product_id,
            dataType: 'json',
            success: function(json) {
                $('.success, .warning, .attention, .information').remove();

                if (json['success']) {
                    $('#notification-quick').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

                    $('.success').fadeIn('slow');
                    $('#wishlist-total').html(json['total']);



                }
            }
        });
    }

    function addToCompareQuick(product_id) {
        $.ajax({
            url: 'index.php?route=product/compare/add',
            type: 'post',
            data: 'product_id=' + product_id,
            dataType: 'json',
            success: function(json) {
                $('.success, .warning, .attention, .information').remove();

                if (json['success']) {
                    $('#notification-quick').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

                    $('.success').fadeIn('slow');

                    $('#compare-total').html(json['total']);




                }
            }
        });
    }
    $('select[name="profile_id"], input[name="quantity"]').change(function() {
        $.ajax({
            url: 'index.php?route=product/product/getRecurringDescription',
            type: 'post',
            data: $('input[name="product_id"], input[name="quantity"], select[name="profile_id"]'),
            dataType: 'json',
            beforeSend: function() {
                $('#profile-description').html('');
            },
            success: function(json) {
                $('.success, .warning, .attention, information, .error').remove();

                if (json['success']) {
                    $('#profile-description').html(json['success']);
                }
            }
        });
    });
//--></script>

