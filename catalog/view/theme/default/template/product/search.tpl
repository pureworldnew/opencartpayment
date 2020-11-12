<?php echo $header; ?>
    <?php echo $column_left; ?> 
<?php echo $column_right; ?>
<div id="content" class="cate-right">
    <?php echo $content_top; ?>
    <div class="flt searchpage">
        <div class="breadcrumb clr">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
               <a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
            <?php } ?>
        </div>

        <h1><?php echo $heading_title; ?></h1>
        
        <b><?php echo $text_critea; ?></b>
        <div class="content">
            <div class="content-border"> 
                <div class="ig_searchtext">
                    <span class="ig_search_text"><?php echo $entry_search; ?></span>
                    <?php if ($search) { ?>
                        <input type="text" name="search" value="<?php echo $search; ?>" />
                    <?php } else { ?>
                        <input type="text" name="search" value="<?php echo $search; ?>" onclick="this.value = '';" onkeydown="this.style.color = '000000'" style="color: #999;" />
                    <?php } ?>
                </div>
                <div class="ig_catid">
                    <select name="category_id">
                        <option value="0"><?php echo $text_category; ?></option>
                        <?php foreach ($categories as $category_1) { ?>
                            <?php if ($category_1['category_id'] == $category_id) { ?>
                                <option value="<?php echo $category_1['category_id']; ?>" selected="selected"><?php echo $category_1['name']; ?></option>
                            <?php } else { ?>
                                <option value="<?php echo $category_1['category_id']; ?>"><?php echo $category_1['name']; ?></option>
                            <?php } ?>
                            <?php foreach ($category_1['children'] as $category_2) { ?>
                                <?php if ($category_2['category_id'] == $category_id) { ?>
                                    <option value="<?php echo $category_2['category_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_2['name']; ?></option>
                                <?php } else { ?>
                                    <option value="<?php echo $category_2['category_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_2['name']; ?></option>
                                <?php } ?>
                                <?php foreach ($category_2['children'] as $category_3) { ?>
                                    <?php if ($category_3['category_id'] == $category_id) { ?>
                                        <option value="<?php echo $category_3['category_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_3['name']; ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $category_3['category_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_3['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    </select>
                </div>
                <div class="search_subcategories">
                    <p>
                        <?php if ($sub_category) { ?>
                            <input class="search-checkbox" type="checkbox" name="sub_category" value="1" id="sub_category" checked="checked" />
                        <?php } else { ?>
                            <input type="checkbox" name="sub_category" value="1" id="sub_category" />
                        <?php } ?>
                        <label for="sub_category"><?php echo $text_sub_category; ?></label>
                    </p>
                    <p>
                        <?php if ($description) { ?>
                            <input type="checkbox" name="description" value="1" id="description" checked="checked" />
                        <?php } else { ?>
                            <input type="checkbox" name="description" value="1" id="description" />
                        <?php } ?>
                        <label for="description"><?php echo $entry_description; ?></label>
                    </p>
                </div>
            </div>
            <div id="right_button">
                <div class="right"><input type="button" value="<?php echo $button_search; ?>" id="button-search" class="button" /></div>
            </div>
            <h2><?php echo $text_search; ?></h2>
            <?php if ($products) { ?>
                <div class="product-filter">
                    <div class="pagination pagination_category pagi_search"><?php echo $pagination; ?></div>
    <!--                    <div class="display"><b><?php //echo $text_display;   ?></b> <a class="list_link_filter" href="javascript:void(0);"><?php //echo $text_list;   ?></a> <b>/</b> <a href="javascript:void(0);" class="grid_link_filter"><?php // echo $text_grid;   ?></a></div>-->
                    <div class="limit"><?php echo $text_limit; ?>
                        <select onchange="location = this.value;">
                            <?php foreach ($limits as $limits) { ?>
                                <?php if ($limits['value'] == $limit) { ?>
                                    <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
                                <?php } else { ?>
                                    <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="sort search_sort"><?php echo $text_sort; ?>
                        <select onchange="location = this.value;">
                            <?php foreach ($sorts as $sorts) { ?>
                                <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
                                    <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
                                <?php } else { ?>
                                    <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="product-compare"><a href="<?php echo $compare; ?>" id="compare-total"><?php echo $text_compare; ?></a></div>
                <div class="product-list search-page">
                    <?php foreach ($products as $product) { ?>
                        <div>

                            <?php if ($product['thumb']) { ?>
                                <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
                            <?php } else { ?>
                                <div class="image"><a href="<?php echo $product['href']; ?>"><img width="150" height="150" src="<?php echo HTTP_SERVER; ?>catalog/view/theme/default/image/no-image.gif" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
                            <?php } ?>
                            <div class="name prodcut_category_name"><a href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>"><?php
                                    if (strlen($product['name']) > 36) {
                                        echo substr($product['name'], 0, 36) . "...";
                                    } else {
                                        echo $product['name'];
                                    }
                                    ?></a></div>
                            <div class="description"><?php echo $product['description']; ?></div>
                            <?php if ($product['price']) { ?>
                                <div class="price price_cat_product">
                                    <?php if (!$product['special']) { ?>
                                        <?php echo $product['price'] . $product['unit']; ?>
                                    <?php } else { ?>
                                        <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
                                    <?php } ?>
                                    <?php if ($product['tax']) { ?>
                                        <br />
                                        <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
                                    <?php } ?>
                                </div>

                                <?php /* ?><div class="price">
                                  <?php if (!$product['special']) { ?>
                                  <?php echo $product['price']; ?>
                                  <?php } else { ?>
                                  <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
                                  <?php } ?>
                                  <?php if ($product['tax']) { ?>
                                  <br />
                                  <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
                                  <?php } ?>
                                  </div><?php */ ?>
                            <?php } ?>
                            <?php if ($product['rating']) { ?>
                                                <!--<div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /></div>-->
                            <?php } ?>
                            <!--                            <div class="cart">
                                                            <input type="button" value="<?php //echo $button_cart;   ?>" onclick="addToCart('<?php // echo $product['product_id'];   ?>');" class="button ship" />
                                                        </div>-->
                            <div class="wishlist"><a onclick="addToWishList('<?php echo $product['product_id']; ?>');"><?php echo $button_wishlist; ?></a></div>
                            <div class="compare"><a onclick="addToCompare('<?php echo $product['product_id']; ?>');"><?php echo $button_compare; ?></a></div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="pagination"><?php echo $pagination; ?></div>
        <?php } else { ?>
            <div class="col-lg-12">
            <div class="content"><?php echo $text_empty; ?></div>
            </div>
        <?php } ?>
        <?php echo $content_bottom; ?>
    </div>
 </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function() {
        $('#content input[name=\'search\']').keydown(function(e) {
            if (e.keyCode == 13) {
                $('#button-search').trigger('click');
            }
        });

        $('select[name=\'category_id\']').bind('change', function() {
            if (this.value == '0') {
                $('input[name=\'sub_category\']').attr('disabled', 'disabled');
                $('input[name=\'sub_category\']').removeAttr('checked');
            } else {
                $('input[name=\'sub_category\']').removeAttr('disabled');
            }
        });

        $('select[name=\'category_id\']').trigger('change');


        $('.colorbox').colorbox({
            overlayClose: true,
            opacity: 0.5,
            width: '800',
            maxHeight: '600',
        });

        $(".sortby_container").click(function(e) {
            e.preventDefault();
            $(".sort_content").slideToggle('fast');
        });

        var hitEvent = 'ontouchstart' in document.documentElement
                ? 'touchstart'
                : 'click';

        $('.grid_link_filter').bind(hitEvent, function() {
            mycustomdispay('grid');
        });
        $('.list_link_filter').bind(hitEvent, function() {
            mycustomdispay('list');
        });


        mycustomdispay('grid');
        $('.product-list').hide();

		if ($("#filtermenuadv").hasClass("respNav_btn respNav_collapsed") && $( window ).width() > 700) {
		  	$("#filtermenuadv").trigger("click");
		}
    });

    function mycustomdispay(view) {
        if (view == "list") {
            $('.product-grid ').addClass('product-list').removeClass('product-grid');
            $('.list_link_filter').addClass("bld");
            $('.grid_link_filter').removeClass("bld");
        } else {
            $('.product-list').addClass('product-grid').removeClass('product-list');
            $('.grid_link_filter').addClass("bld");
            $('.list_link_filter').removeClass("bld");
        }
    }


    $('#button-search').bind('click', function() {
        url = 'index.php?route=product/search';

        var search = $('#content input[name=\'search\']').attr('value');

        if (search) {
            url += '&search=' + encodeURIComponent(search);
        }

        var category_id = $('#content select[name=\'category_id\']').attr('value');

        if (category_id > 0) {
            url += '&category_id=' + encodeURIComponent(category_id);
        }

        var sub_category = $('#content input[name=\'sub_category\']:checked').attr('value');

        if (sub_category) {
            url += '&sub_category=true';
        }

        var filter_description = $('#content input[name=\'description\']:checked').attr('value');

        if (filter_description) {
            url += '&description=true';
        }

        location = url;
        return false;
    });




</script> 

<?php echo $footer; ?>
