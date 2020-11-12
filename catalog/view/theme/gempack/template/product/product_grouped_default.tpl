<?php echo $header; ?>
<?php $currency = "USD"; ?>
<script type="application/ld+json">
{
    "@context": "http://schema.org/",
    "@type": "Product",
    "name": "<?php echo $heading_title; ?>",
    "offers": {
        "@type": "Offer",
        "priceCurrency": "<?php echo $currency; ?>",
        "price": "<?php if (!$google_ads_schema_special) { echo preg_replace('/[^0-9.]+/','',$google_ads_schema_price);}else{echo preg_replace('/[^0-9.]+/','',$google_ads_schema_special);} ?>",
        "itemCondition" : "http://schema.org/NewCondition",
        "sku" : "<?php echo $google_ads_schema_sku; ?>",
        "image" : "<?php echo $google_ads_schema_image; ?>",
        "availability" : "<?php if ($stock == 'In Stock') { echo 'InStock'; } else { echo $stock; } ?>"
    }
}
</script>
<div class="row">
    <?php echo $column_left; ?>
    <?php
        if ($column_left and $column_right) {
            $class="col-lg-8 col-md-6 col-sm-4 col-xs-12";
        } elseif ($column_left or $column_right) {
            $class="col-lg-10 col-md-9 col-sm-8 col-xs-12";
        } else {
            $class="col-xs-12";
        }
    ?>
    <div class="<?php echo $class; ?>" id="content">
        <?php echo $content_top; ?>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
            <span id='group_indicator' style="display: none;" data-group_indicator='<?php if (isset($group_indicator_id)) { echo $group_indicator_id; } ?>'></span>
            <div class="product-info">
                <h3 class="hidden-lg hidden-md hidden-sm"><?php echo $heading_title; ?></h3>
                <div class="row">
                    <div class="col-sm-4">
                        <ul class="thumbnails hidden-xs">
                            <?php if ($thumb) { ?>
                                <li><a class="thumbnail cloud-zoom" href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>"><img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" id="image" /></a></li>
                            <?php } else { ?>
                                <li><a class="thumbnail cloud-zoom" href="<?php echo HTTP_SERVER; ?>catalog/view/theme/default/image/no_product.jpg" title="<?php echo $heading_title; ?>"><img src="<?php echo HTTP_SERVER; ?>catalog/view/theme/default/image/no_product.jpg" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" id="image" /></a></li>
                            <?php } ?>
                            <div class="thumbnails-images">
                                <?php if (count($images) + count($videos) >= 2) { ?>
                                    <?php if ($images) { ?>
                                        <?php foreach($images as $image) { ?>
                                            <?php if(!empty($image['thumb'])) { ?>
                                                <li class="media_additional" style="display: inline-block;width: 50%;padding: 3px;"><a id="image" class="media_thumbnail" href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>"> <img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a></li>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                    <?php if ($videos) { $i = 0; ?>
                                        <?php foreach ($videos as $video) { ?>
                                            <?php if ($video['file_type'] == 'youtube'){ ?>
                                                <li class="media_additional" style="display: inline-block;width: 50%;padding: 3px;">
                                                    <iframe class="media_thumbnail" src="<?php echo $video['video']; ?>"></iframe> 
                                                </li>
                                            <?php } else { ?>
                                                <?php if ($poster) { ?>
                                                    <li class="media_additional" style="display: inline-block;width: 50%;padding: 3px;">
                                                        <a class="media_thumbnail" href="<?php echo $video['video'];?>" id="video">
                                                            <video class="media_thumbnail" controls preload="none" poster = '<?php echo $poster;?>'>
                                                                <source src="<?php echo $video['video'];?>" type="<?php echo $video['video_type']; ?>">
                                                                Your browser does not support the video tag.
                                                            </video>
                                                        </a>
                                                    </li>
                                                <?php } else { ?>
                                                    <li class="media_additional" style="display: inline-block;width: 50%;padding: 3px;">
                                                        <a class="media_thumbnail" href="<?php echo $video['video'];?>" id="video">
                                                        <video class="media_thumbnail" onloadedmetadata="loadVideoThumb()">
                                                            <source src="<?php echo $video['video'];?>" type="<?php echo $video['video_type']; ?>">
                                                            Your browser does not support the video tag.
                                                        </video>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php $i++; } ?>
                                    <?php } ?>
                                <?php } else { ?>
                                    <?php if ($images) { ?>
                                        <?php foreach ($images as $image) { ?>
                                            <li class="image-additional"><a class="thumbnail" href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>"> <img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a></li>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                            </div>     
                        </ul>
                        <!-- video player -->
                        <div class="video_player thumbnail"></div> 
                        <!-- ends video Player -->
                    </div>
                    <div class="hidden-lg hidden-md hidden-sm mobile-product-images">
                        <div id="mobile-images" class="owl-carousel">
                            <!--<div class="item"><img src="<?php echo $popup; ?>" alt="<?php echo $heading_title; ?>" class="img-responsive" id="image_mob"/></div>-->
                            <?php if ($images) { ?>
                                <?php foreach ($images as $image) {  if(!empty($image['thumb'])) { ?>
                                    <div class="item"><img src="<?php echo $image['popup']; ?>" alt="<?php echo $heading_title; ?>" class="img-responsive" /></div>
                                <?php } } ?>
                            <?php } ?>
                        </div>
                        <script type="text/javascript"><!--
                            $('#mobile-images').owlCarousel({
                                items: 1,
                                autoPlay: false,
                                loop: false,
                                singleItem: true,
                                navigation: false,
                                pagination: true
                            });
                        --></script>
                       <!-- </div> -->
                    </div>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-xs-8"><h3 id="product_name" class="hidden-xs"><?php echo $heading_title; ?></h3></div>
                            <div class="col-xs-4">
                                <div class="stock_status">
                                    <span id="show_stock"><?php //echo $stock; ?></span>
                                    <div class="help_tool_image hidden">
                                        <?php
                                        if ($quantity <= 0) {
                                            if($stock == '2-3 Days') : ?>
                                                <span class='two_three_days'></span>
                                            <?php
                                            elseif($stock == 'Pre-Order') : ?>
                                                <span class='pre_order'></span>
                                            <?php
                                            elseif($stock == 'In Stock') : ?> 
                                                <span class='inofstock'></span>
                                            <?php
                                            else : ?>
                                                <span class='outofstock'></span>
                                            <?php
                                            endif;
                                            if (!empty($frontend_date_available) && $frontend_date_available > date("Y-m-d") && $date_sold_out < $frontend_date_available) {
                                                echo "<div class='date_available'>Estimated Arrival: {$frontend_date_available} </div>";
                                            }
                                            $datetoday = date("Y-m-d");
                                            if ($datetoday > $date_ordered) {
                                                $count = count($estimate_deliver_days);
                                                $val = 0;
                                                foreach($estimate_deliver_days as $get_days){
                                                    $val++;
                                                    $availabledate = date("Y-m-d",strtotime($date_ordered ."+".$get_days['estimate_days']." days"));
                                                    if($datetoday > $availabledate){
                                                        if($count == $val){
                                                            echo "<span class='stocktext'> We expected this item back in stock a few weeks ago. There may be a manufacturer delay, please contact us for details </span>";
                                                        }
                                                        continue;
                                                    }else{
                                                        echo "<span class='stocktext'>".str_replace('%s',date( "M d", strtotime($availabledate) ),$get_days['text']) ."</span>";
                                                        break;
                                                    }
                                                }
                                            }else{
                                                if($date_ordered != "0000-00-00"){
                                                    foreach($estimate_deliver_days as $get_days){
                                                        $availabledate = date("Y-m-d",strtotime($date_ordered . " +".$get_days['estimate_days']." days"));
                                                        $availabledate = date( "M d", strtotime($availabledate) );
                                                        echo "<span class='stocktext'>".str_replace('%s',$availabledate,$get_days['text']) ."</span>";
                                                        break;
                                                    }
                                                }
                                            }
                                        } else { ?>
                                            <span class='inofstock'></span>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <?php if ($review_status) { ?>
                                    <div class="rating">
                                        <p>
                                            <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                <?php if ($rating < $i) { ?>
                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                <?php } else { ?>
                                                    <span class="fa fa-stack"><i class="fa fa-star fa-stack-1x"></i><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                <?php } ?>
                                            <?php } ?>
                                            ( <a href="#" id="goto-reviews"><?php echo $reviews; ?></a> | <a href="#" id="goto-qa">Ask a question below!</a> )
                                        </p>
                                    </div>
                                <?php } ?>
                                <?php if ($profiles): ?>
                                    <div class="option">
                                        <h2><span class="required">*</span><?php echo $text_payment_profile ?></h2>
                                        <select name="profile_id">
                                            <option value=""><?php echo $text_select; ?></option>
                                            <?php foreach ($profiles as $profile): ?>
                                                <option value="<?php echo $profile['profile_id'] ?>"><?php echo $profile['name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <span id="profile-description"></span>
                                    </div>
                                <?php endif; ?>
                                <div class="grouped-product gp-group">
                                    <?php if ($product_grouped) { ?>
                                        <b><?php echo "Select " . $text_groupby ?>:</b>
                                        <select name="select_product" class='grouped_product_select fancySelect'>
                                            <?php foreach ($product_grouped as $product) { ?>
                                                <option <?php if($product['is_requested_product']) { echo "selected='selected'"; } ?> value="<?php echo $product['product_id']; ?>"><?php echo trim($product['product_name']); ?> </option>
                                            <?php } ?>
                                        </select>
                                    <?php } ?>
                                    <div style="position:absolute;">
                                        <div class="gp-loader"></div>
                                    </div>
                                </div>
                                <?php if ($options) { ?>
                                    <div class="options">
                                        <!--<h2><?php echo $text_option; ?></h2>-->
                                        <?php
                                        foreach ($options as $option) {
                                            if ($option['type'] == 'select') {
                                                $UnitArry = explode(' ', $option['name']);
                                                if (strtolower(end($UnitArry)) != "units") { ?>
                                                    <div id="option-<?php echo $option['product_option_id']; ?>" class="option ig_<?php echo str_replace(' ', '', $option['name']); ?>">
                                                        <?php if ($option['required']) { ?>
                                                            <span class="required">*</span>
                                                        <?php } ?>
                                                        <b><?php echo $option['name']; ?>:</b>
                                                        <select name="option[<?php echo $option['product_option_id']; ?>]">
                                                            <?php if ($option['metal_type'] > 1) { ?>
                                                                <option value=""><?php echo $text_select; ?></option>
                                                            <?php } ?>
                                                            <?php foreach ($option['option_value'] as $option_value) { 
                                                                if($option_value['quantity'] <= 0) { ?>
                                                                    <option qty="<?php echo $option_value['quantity'];?>" <?php if($option_value['is_requested_option_value']) { echo "selected='selected'";} ?> data-price="<?php echo ($option_value['price2'] ? $option_value['price2'] : '' ) ?>" value="<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name'].' - '.$option_out_of_stock; ?></option>
                                                                <?php } else { ?>
                                                                    <option qty="<?php echo $option_value['quantity'];?>" <?php if($option_value['is_requested_option_value']) { echo "selected='selected'";} ?> data-price="<?php echo ($option_value['price2'] ? $option_value['price2'] : '' ) ?>" value="<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?></option>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <?php
                                                }
                                            }
                                            if ($option['type'] == 'radio') {
                                                $UnitArry = explode(' ', $option['name']);
                                                if (strtolower(end($UnitArry)) != "units") { ?>
                                                    <div id="option-<?php echo $option['product_option_id']; ?>" class="option ig_<?php echo str_replace(' ', '', $option['name']); ?>">
                                                        <?php if ($option['required']) { ?>
                                                            <span class="required">*</span>
                                                        <?php } ?>
                                                        <b><?php echo $option['name']; ?>:</b><br />
                                                        <?php foreach ($option['option_value'] as $option_value) { ?>
                                                            <label for="option-value-<?php echo $option_value['product_option_value_id']; ?>" data-val="<?php echo $option_value['name']; ?>" ><?php echo $option_value['name']; ?></label>
                                                            <input data-price="<?php echo ($option_value['price2'] ? $option_value['price2'] : '' ) ?>" type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" />
                                                        <?php } ?>
                                                    </div>
                                                    <?php
                                                }
                                            }
                                        } ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li>Item Numbers: <span id="item_number"><?php echo $sku; ?></span></li>
                                    <li class="hidden"><?php echo $text_stock; ?> <?php echo $stock; ?></li>
                                </ul>
                                <input type="hidden" id="base_price_input" value="<?php echo isset($base_price) ? $base_price : '0' ?>">
                                <ul class="list-unstyled price">
                                    <?php if (!$special) { ?>
                                        <li>Price: <strong ><span id="new_price"><?php echo $price; ?></span><span class="unit"> /</span> <span id="quantity_span"> </span>&nbsp;<span id="unit_dis">  <?php echo $unit_singular; ?></span></strong></li>
                                    <?php } else { ?>
                                        <li>Price: <span id="new_price" style="text-decoration: line-through;"><?php echo $price; ?></span> <strong><?php echo $special; ?><span class="unit"> / </span> <span id="quantity_span"> </span> &nbsp; <span id="unit_dis"> <?php echo $unit_singular; ?></span></strong></li>
                                    <?php } ?>
								    <li><span id="converstion_string_display" style="background-color: rgb(69, 106, 158); margin-left: 75px; color: rgb(255, 255, 255);"></span></li>
                                </ul>
                                <input id="plural_unit" type="hidden" value ="<?php echo $unit_plural; ?>">
        						<input type="hidden" class="unit_conversion_values" name ="unit_conversion_values" value="">
                                <?php if(isset($this->request->get['gpsku'])){ ?>
                                    <input class="gpsku" type="hidden" value ="<?php echo $this->request->get['gpsku']; ?>">
                                <?php }else{ ?>
                                    <input class="gpsku" type="hidden" value ="">
                                <?php } ?>
                                <?php if ($points) { ?>
                                    <ul class="list-unstyled price">
                                        <li><small><?php echo $text_points; ?> <strong><?php echo $points; ?></strong></small></li>
                                    </ul>
                                <?php } ?>
                                <p class="bulk-product-tex">
                                    <?php if ($discounts&&$logged || $logged) { ?>
                                        <a style="cursor: pointer;" data-toggle="modal" data-target="#seeBulkPrice"><i class="fa fa-plus-square"></i> See Bulk Price</a>
                                    <?php } else { ?>
                                        <a id="bulkcontent" style="cursor: pointer;" data-toggle="popover" data-placement="bottom"><i class="fa fa-plus-square"></i> See Bulk Price</a></span>
                                        <div id="content-bulk" class="hidden">
                                            <p class="text-center">For Wholesale Pricing</p>
                                            <p><a href="javascript:void(0);" onclick="$('html, body').animate({ scrollTop: 0 }, 'slow');$('#login-desktop').css('display','block'); " type="button" class="btn btn-block btn-info">Login</a></p>
                                            <p class="text-center">Or</p>
                                            <p><a href="account/register" class="btn btn-block btn-info">Signup</a></p>
                                            <p class="text-center">For Quotes on Significantly Larger Qunatities. Please <a href="index.php?route=information/contact">Contact Us</a></p>
                                        </div>
                                        <script>
                                            var ops = {
                                                'html':true,
                                                content: function(){
                                                    return $('#content-bulk').html();
                                                }
                                            };
                                            $(function(){
                                                $('#bulkcontent').popover(ops);
                                            });
                                        </script>
                                    <?php } ?>
                                </p>
                                <script type="text/javascript">
                                    $(document).ready(function(){
                                        $('#seeBulkPrice').on('shown', function() {
                                            var datavalue= $("#get-unit-data option:selected").attr('name');
                                            if(datavalue==null){
                                                datavalue='';
                                            } else {
                                                datavalue=datavalue;
                                            }
                                            var unitname=  $("#unit_dis").html();
                                            $(".scale-quantity").after( "<span class='current_unitnames'>   <b>"+ datavalue +"</b> </span>");
                                        });
                                        $('#seeBulkPrice').on('hidden', function() {
                                            $(".current_unitnames").remove();
                                        });
                                    });
                                </script>
                                <div  class="price_grouped_product_box">
                                    <div class="well">
                                        <div class="row">
                                            <div class="col-xs-7">
                                                <div class="input-group">
                                                    <span class="input-group-addon" id="sizing-addon1"><?php echo $text_qty; ?></span>
                                                    <input type="text" class="form-control quantity qty_group_product"   name="quantity" id="quantity"  value="<?php echo $minimum; ?>" />
                                                </div>
                                            </div>
                                            <div class="col-xs-5">
                                                <div class="btn-group" id="product-wishlist">
                                                    <button type="button" id="add_wishlist" data-toggle="tooltip" class="btn btn-default" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product_id; ?>');"><i class="fa fa-heart"></i></button>
                                                    <button type="button" id="add_compare" data-toggle="tooltip" class="btn btn-default" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product_id; ?>');"><i class="fa fa-exchange"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-7">
                                                <?php if(!empty($unit_datas)){ ?>
                                                    <div class="ig_MetalType ig_Units units_grouped">
                                                        <select class="get-unit-data id_convert_long form-control" id="get-unit-data">
                                                            <?php foreach ($unit_datas as $unit_data) { ?>
                                                                <option name="<?php echo $unit_data['name']; ?>" data-value ="<?php echo $unit_data['unit_conversion_product_id']; ?>" value="<?php echo $unit_data['convert_price']; ?>"><?php echo $unit_data['name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div class="information_image information_image_unit"><img class="option_tooltip" src="catalog/view/theme/default/img/information.png" alt=""/></div>
                                                <?php } ?>
        									</div>
                                            <div class="col-xs-5">
                                                <div class="row">
                                                    <div class="col-xs-6">
                                                        <img src="catalog/view/theme/default/img/satiscfaction_image.png" alt="" class="money-back img-responsive"/>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <img src="catalog/view/theme/default/img/paypal_group.png" alt="" class="pay-img img-responsive"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="cart-button-display">
                                            <input type="button" id="button-cart-2" class="qty-cart button-cart-2 button-cart btn btn-default btn-block btn-orange" value="Add to Cart" />
                                            <button style="display:none" id="button-cart-2" class="qty-cart button-cart-2 button-cart btn btn-default btn-block btn-orange">Add to Cart</button>
                                        </div>
                                        <div id="loading-display" style="padding-left: 15px; display:none;">
                                            <img src="catalog/view/theme/default/img/ajax-loading.gif" alt="" class="loading" width="75px"/>
                                        </div>
                                        <input type="hidden" name="original_price" value="<?php echo $old_price; ?>" >
                                        <input type="hidden" id="product_id_change" name="product_id" value="<?php echo $product_id; ?>" >
                                        <input type="hidden" id="default_conversion_value_name" name ="default_conversion_value_name" value="">
                                        <div id="loading-display" class="hidden"><img src="catalog/view/theme/default/img/ajax-loading.gif" alt="" class="loading" width="75px"/></div>
                                    </div>
                                </div>
                                <div class="panel-group product-panel-group" style="max-width: 400px;">
                                    <?php if ($options) { ?>
                                        <div class="panel panel-default" style="margin-top: 5px;">
                                            <div class="panel-heading">
                                                <h4 class="panel-title text-center"><small>There are <span id="item-number" style="font-size:16px;"><?php echo $total_options_product; ?></span> different options for this item.</small></h4>
                                            </div>
                                            <div id="option-price" class="panel-collapse collapse in">
                                                <div class="panel-footer">
                                                    <p id="items-result" class="text-center"><a class="btn btn-yellow" href="<?php echo $group_products_href; ?>">Show All</a></p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr />
                <ul class="nav nav-tabs" id="product-tabs">
                    <li class="active"><a href="#tab-description" data-toggle="tab"><?php echo $tab_description; ?></a></li>
                    <?php if ($review_status) { ?>
                        <li><a href="#tab-review" data-toggle="tab"><?php echo $tab_review; ?></a></li>
                    <?php } ?>
                    <li><a href="#tab-qa" data-toggle="tab"><?php echo $tab_qa; ?></a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab-description">
                        <div class="iframe-rwd"><?php echo $description; ?></div>
                        <?php if ($attribute_groups) { ?>
                            <div id="tab-attribute" class="tab_product_attribute">
                                <table class="table table-bordered attribute">
                                    <?php foreach ($attribute_groups as $attribute_group) { ?>
                                        <thead>
                                            <tr>
                                                <td colspan="2" class="background_td"><strong><?php echo $attribute_group['name']; ?></strong></td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
                                                <tr>
                                                    <td class="background_td"><?php echo $attribute['name']; ?></td>
                                                    <td><?php echo $attribute['text']; ?></td>
                                               </tr>
                                            <?php } ?>
                                        </tbody>
                                    <?php } ?>
                                </table>
                            </div>
                        <?php } ?>
                        <div for="ticket785"></div>
                    </div>
                    <?php if ($review_status) { ?>
                        <div class="tab-pane" id="tab-review">
                            <form class="form-horizontal" id="form-review">
                                <div id="review"></div>
                                <h2><?php echo $text_write; ?></h2>
                                <div class="form-group required">
                                    <div class="col-sm-12">
                                        <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                                        <input type="text" name="name" value="" id="input-name" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <div class="col-sm-12">
                                        <label class="control-label" for="input-review"><?php echo $entry_review; ?></label>
                                        <textarea name="text" rows="5" id="input-review" class="form-control"></textarea>
                                        <div class="help-block"><?php echo $text_note; ?></div>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <div class="col-sm-12">
                                        <label class="control-label"><?php echo $entry_rating; ?></label>
                                        &nbsp;&nbsp;&nbsp; <?php echo $entry_bad; ?>&nbsp;
                                        <input type="radio" name="rating" value="1" />
                                        &nbsp;
                                        <input type="radio" name="rating" value="2" />
                                        &nbsp;
                                        <input type="radio" name="rating" value="3" />
                                        &nbsp;
                                        <input type="radio" name="rating" value="4" />
                                        &nbsp;
                                        <input type="radio" name="rating" value="5" />
                                        &nbsp;<?php echo $entry_good; ?>
                                    </div>
                                </div>
                                <?php echo $captcha; ?>
                                <div class="buttons clearfix">
                                    <div class="pull-right">
                                        <button type="button" id="button-review" data-loading-text="" class="btn btn-primary"><?php echo $button_continue; ?></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php } ?>
                    <div class="tab-pane" id="tab-qa">
                        <div id="qa"><?php echo $qas; ?></div>
                        <h2 id="qa-title"><?php echo $text_ask; ?></h2>
                        <div class="form-horizontal">
                            <div class="form-group required">
                                <div class="col-sm-12">
                                    <label class="control-label" for="questioner"><?php echo $entry_name; ?></label>
                                    <input class="form-control" type="text" name="questioner" value="<?php echo $qa_name; ?>" />
                                </div>
                            </div>
                            <?php if ( $qa_notify = true ) { ?>
                                <div class="form-group required">
                                    <div class="col-sm-12">
                                        <label class="control-label" for="q_email"><?php echo $entry_email; ?></label>
                                        <input class="form-control" type="text" name="q_email" value="<?php echo $qa_email; ?>" />
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="form-group required">
                                <div class="col-sm-12">
                                    <label class="control-label" for="question"><?php echo $entry_question; ?></label>
                                    <textarea name="question" rows="5" class="form-control"></textarea>
                                    <div class="help-block"><?php echo $text_note; ?></div>
                                </div>
                            </div>
                            <div class="form-group required">
                                <div class="col-sm-12">
                                    <label class="control-label"><input type="checkbox" id="email_copy" class="input-checkpox" name="email_copy" value="1"> Email a copy to yourself</label>
                                </div>
                            </div>
                            <div class="action">
                                <div class="buttons clearfix">
                                    <div class="pull-right">
                                        <a id="button-qa" class="product-also-add-to-cart button btn btn-primary" onclick="askQuestion();"><?php echo "Submit Question"; ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Works well with -->   
                <?php if(true) : ?>
                    <?php //echo "<pre>";print_r($wproducts);echo "</pre>"; ?>
                    <div class="box" id="wwell_wrapper" style="display:<?php echo ($wproducts)?'block':'none' ?>">
                        <div class="box-heading"><h3>Works well with</h3></div>
                        <div class="box-content" id="wwell_prods">
                            <div id="wwell_prods_load" class="owl-carousel product-carousel">
                                <?php 
                                    $a = (int)count($wproducts);
                                    if ($a > 4){
                                        $flag = true;
                                    }else{
                                        $flag = false;
                                    }
                                    $i = 0;
                                ?>
                                <?php foreach ( $wproducts as $rproduct ) : ?>
                                    <?php if($i >= 4) break; ?>
                                    <div class="item text-center">
                                        <div class="product-item">
                                            <div class="name">
                                                <a href="<?php echo $rproduct['href']; ?>" title="<?php echo $rproduct['name']; ?>"><?php echo $rproduct['name']?></a>
                                            </div>
                                            <?php if ($rproduct['thumb']) { ?>
                                                <div class="image"><a href="<?php echo $rproduct['href'] ?>"><img src="<?php echo $rproduct['thumb'] ?>" title="<?php echo $rproduct['name']; ?>" alt="<?php echo $rproduct['name']; ?>"></a></div>
                                            <?php } else { ?>
                                                <div class="image"><img src="<?php echo HTTP_SERVER; ?>catalog/view/theme/default/image/no_product.jpg" title="<?php echo $heading_title; ?>" alt="<?php echo $rproduct['name']; ?>" /></div>
                                            <?php } ?>
                                            <div class="price">
                                                <span><?php echo ($rproduct['special'])?$rproduct['special']:$rproduct['price']; ?></span>
                                                <span class="unit-products"> per <?php echo $rproduct['unit'];?></span>
                                            </div>
                                            <div class="cart"><input id="button-cart" value="Add to Cart" class="button button-cart" data-product_id="<?php echo $rproduct['product_id'];?>" type="button" onClick="javascript:addToCart('<?php echo $rproduct['product_id'];?>,1)"></div>
                                        </div>
                                    </div>
                                    <?php $i++; ?>
                                <?php endforeach; ?>
                                <?php if ($flag) { ?>
                                    <?php $seeall = "index.php?route=product/search&getAllRelated&product_id=" . $product_id; ?>
                                    <div class="item text-center">
                                        <div class="product-item">
                                            <div class="name"><a href="" title="See All">See All</a></div>
                                            <div class="image"><a href="<?php echo $seeall; ?>"><img src="<?php echo HTTP_SERVER; ?>catalog/view/theme/default/image/seeall.png" height="200" width="200" title="" alt="See all" /></a></div>
                                            <div class="price">
                                                <span>SEE ALL</span>
                                                <span class="unit-products"></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <!-- Related Products -->   
                <?php if(true) : ?>
                    <div class="box" id="related_wrapper" style="display:<?php echo ($products)?'block':'none' ?>">
                        <div class="box-heading"><h3>Related Products</h3></div>
                        <div class="box-content" id="related_prods">
                            <div id="related_prods_load" class="owl-carousel product-carousel">
                                <?php 
                                    $a = (int)count($products);
                                    if ($a > 4){
                                        $flag = true;
                                    }else{
                                        $flag = false;
                                    }
                                    $i = 0;
                                ?>
                                <?php foreach ( $products as $rproduct ) : ?>
                                    <?php if($i >= 4) break; ?>
                                    <div class="item text-center">
                                        <div class="product-item">
                                            <div class="name">
                                                <a href="<?php echo $rproduct['href']; ?>" title="<?php echo $rproduct['name']; ?>"><?php echo $rproduct['name']?></a>
                                            </div>
                                            <?php if ($rproduct['thumb']) { ?>
                                                <div class="image"><a href="<?php echo $rproduct['href'] ?>"><img src="<?php echo $rproduct['thumb'] ?>" title="<?php echo $rproduct['name']; ?>" alt="<?php echo $rproduct['name']; ?>"></a></div>
                                            <?php } else { ?>
                                                <div class="image"><img src="<?php echo HTTP_SERVER; ?>catalog/view/theme/default/image/no_product.jpg" title="<?php echo $heading_title; ?>" alt="<?php echo $rproduct['name']; ?>" /></div>
                                            <?php } ?>
                                            <div class="price">
                                                <span><?php echo ($rproduct['special'])?$rproduct['special']:$rproduct['price']; ?></span>
                                                <span class="unit-products"> per <?php echo $rproduct['unit'];?></span>
                                            </div>
                                            <div class="cart"><input id="button-cart" value="Add to Cart" class="button button-cart" data-product_id="<?php echo $rproduct['product_id'];?>" type="button" onClick="javascript:addToCart('<?php echo $rproduct['product_id'];?>,1)"></div>
                                        </div>
                                    </div>
                                    <?php $i++; ?>
                                <?php endforeach; ?>
                                <?php if ($flag) { ?>
                                    <?php $seeall = "index.php?route=product/search&getAllRelated&product_id=" . $product_id; ?>
                                    <div class="item text-center">
                                        <div class="product-item">
                                            <div class="name"><a href="" title="See All">See All</a></div>
                                            <div class="image"><a href="<?php echo $seeall; ?>"><img src="<?php echo HTTP_SERVER; ?>catalog/view/theme/default/image/seeall.png" height="200" width="200" title="" alt="See all" /></a></div>
                                            <div class="price">
                                                <span>SEE ALL</span>
                                                <span class="unit-products"></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="modal fade" id="imageDetails" tabindex="-1" role="dialog" aria-labelledby="imageDetails" aria-hidden="true">
                    <div class="modal-dialog" role="document" style="width:85%">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="">Product Images</h5>
                            </div>
                            <div class="modal-body">
                                <ul class="nav nav-pills">
                                    <li class="active"><a data-toggle="pill" href="#cimages">Images</a></li>
                                    <li><a data-toggle="pill" href="#cvideos">Videos</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div id="cvideos" class="tab-pane fade">
                                        <div class="row">
                                            <div class="col-sm-8" id="mainVideo"></div>
                                            <!-- video player -->
                                            <!-- <?php if (!empty($videos)){?>
                                                <?php if (isset($videos[0])) { $video = $videos[0]; ?>
                                                    <?php if ($video['file_type'] == 'youtube') { ?>
                                                        <?php if (!empty($video)) : ?>
                                                            <div class="video_player">
                                                                <iframe width="100%" height="400px" src="<?php echo $video; ?>"></iframe> 
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php } else { ?>
                                                        <?php if ($video['video']) : ?>
                                                            <div class="thumbnails hidden-xs video_player">
                                                                <?php if ($poster) { ?>
                                                                    <video controls preload="none" poster = '<?php echo $poster;?>' style="width: auto;height:500px;" id="bigVideoPlayer"><source src="<?php echo $video['video'];?>" type="<?php echo $video['video_type']; ?>">Your browser does not support the video tag.</video>
                                                                <?php } else { ?>
                                                                    <video controls preload="metadata" style="width: auto;height:500px;" id="bigVideoPlayer"><source src="<?php echo $video['video'];?>#t=0.1" type="<?php echo $video['video_type']; ?>">Your browser does not support the video tag.</video>
                                                                <?php } ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php } ?> 
                                                <?php }
                                            } ?> -->
                                            <!-- ends video Player -->
                                            <div class="col-sm-4">
                                                <span style="font-weight:bold;font-size:20px"><?php echo $heading_title;?></span>
                                                <div id="subVideo"></div>
                                                <!-- <div class="thumbnails hidden-xs" id="subVideo">
                                                    <?php if ($videos) { ?>
                                                        <?php foreach($videos as $video ) { ?>
                                                            <?php if ($video['file_type'] == 'youtube') { ?>
                                                                <div class="video_player">
                                                                    <iframe width="49%" height="100px" src="<?php echo $video; ?>"></iframe> 
                                                            </div>
                                                            <?php } else { ?>
                                                                <?php if ($poster) { ?>
                                                                    <a class="media_thumbnail" href="<?php echo $video['video'];?>"><video preload="none" poster = '<?php echo $poster;?>' style="width: 48%;"><source src="<?php echo $video['video'];?>" type="<?php echo $video['video_type']; ?>">Your browser does not support the video.</video></a>
                                                                <?php }else{ ?>
                                                                    <a class="media_thumbnail" href="<?php echo $video['video'];?>"><video preload="metadata" style="width: 48%;"><source  src="<?php echo $video['video'];?>#t=1" type="<?php echo $video['video_type']; ?>"></video></a>
                                                                <?php } ?>
                                                            <?php } ?> 
                                                        <?php } ?>
                                                    <?php } ?>
                                                </div> -->
                                            </div>
                                        </div>
                                    </div>
                                    <div id="cimages" class="tab-pane fade in active">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <img id="popupImage" src="<?php echo $popup; ?>" class="cloudzoom_popup" data-cloudzoom = "zoomSizeMode: 'image',startMagnification:2,tintColor:'#000',tintOpacity:0.25,captionPosition:'bottom',maxMagnification:4,autoInside: true,zoomPosition:'inside'"/> 
                                                <!-- <a href="<?php echo $zoom_image; ?>" title="<?php echo $heading_title; ?>" id="bigImagePlayer"><img id="popupImage" src="<?php echo $popup; ?>" class="cloudzoom_popup" data-cloudzoom = "zoomSizeMode: 'image',startMagnification:1.9,tintColor:'#000',tintOpacity:0.25,captionPosition:'bottom',maxMagnification:4,autoInside: true,zoomPosition:'inside'"/></a> -->
                                            </div>
                                            <div class="col-sm-4">
                                                <span class="imageDetailsText" style="font-weight:bold;font-size:20px"><?php echo $heading_title;?></span>
                                                <?php if ($images) { ?>
                                                    <div class="thumbnails hidden-xs">
                                                        <div class="thumb-images">
                                                            <?php foreach($images as $image ) { ?>
                                                                <a href="<?php echo $image['zoom_image']; ?>" title="<?php echo $heading_title; ?>" class="cloudzoom-gallery" data-cloudzoom="useZoom: '.cloudzoom_popup', image: '<?php echo $image['popup']; ?>', zoomImage:'<?php echo $image['zoom_image']; ?>' "><img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    $(".thumbnails").on('click',function(e){
                        e.preventDefault();
                        $("#imageDetails").modal();
                    });

                    $("#popupImage").CloudZoom();
                    
                    $('#wwell_prods_load').owlCarousel({
                        items: 4,
                        navigation: true,
                        navigationText: ['<i class="fa fa-chevron-left fa-5x"></i>', '<i class="fa fa-chevron-right fa-5x"></i>'],
                        pagination: false
                    });

                    $('#related_prods_load').owlCarousel({
                        items: 4,
                        navigation: true,
                        navigationText: ['<i class="fa fa-chevron-left fa-5x"></i>', '<i class="fa fa-chevron-right fa-5x"></i>'],
                        pagination: false
                    });
                </script>
                <div id="seeBulkPrice" style="display: none;" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Bulk Prices</h4>
                            </div>
                            <div class="modal-body">
                                <?php if ($logged) { ?>   
                                    <?php if ($discounts) { ?>
                                        <p class="he group_he">Bulk Pricing and Quantity Discounts</p>
                                        <p class="mid-group">
                                            <span class="flt">Quantity</span>
                                            <span class="flr scale-group">Price</span>
                                        </p>
                                        <ul class="update_discount_price_group">
                                            <li>
                                                <span class="scale-quantity"><?php echo "Non-Wholesale"; ?></span>
                                                <span style="text-align: right !important;float: right;" class="scale-price"><?php echo $price_without_discount; ?></span>
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
                                                    <span style="text-align: right !important;float: right;" class="scale-price">
                                                        <?php echo $discount['price']; ?>
                                                    </span>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo $content_bottom; ?>
        </div>
        <?php echo $column_right; ?>
    </div>
    <!-- <script src="catalog/view/javascript/group.product.fix.js" type="text/javascript"></script> -->
    <?php if ($logged) { ?>
	    <script>
            $(document).on('click', '.bulk_price_image, .bulk_price_image_minus, .text-bulk-price', function() {
                $('.discount_product_group').toggle();
                if ($(".bulk_price_image_minus").css('display') == 'none') {
                    $(".bulk_price_image_minus").show();
                    $(".bulk_price_image").hide();
                    $(".text-bulk-price").html("Hide Bulk Price");
                } else {
                    $(".bulk_price_image_minus").hide();
                    $(".bulk_price_image").show();
                    $(".text-bulk-price").html("See Bulk Price");
                }
            });
		</script>
    <?php } ?>
    <script type="text/javascript"><!--
        $('body').on('hidden.bs.modal', '.modal', function () {
            $('#bigVideoPlayer').trigger('pause');
        });
        $('.video_player').bind('contextmenu', function(e) {
            return false;
        });
        $('#cvideos').bind('contextmenu', function(e) {
            return false;
        });
        function askQuestion() { 
            var email_copy = $('#email_copy').is(':checked');
            var product_changed_id = $('#product_id_change').val();
            $.ajax({
                type: 'POST',
                url: 'index.php?route=product/product/ask&product_id='+product_changed_id,
                dataType: 'json',
                data: 'name=' + encodeURIComponent($('input[name=\'questioner\']').val()) + '&email=' + encodeURIComponent(($('input[name=\'q_email\']').length != 0) ? $('input[name=\'q_email\']').val() : '') + '&question=' + encodeURIComponent($('textarea[name=\'question\']').val()) + '&email_copy=' + encodeURIComponent(email_copy) + '&g-recaptcha-response=' + encodeURIComponent($('textarea[name=\'g-recaptcha-response\']').val()),
                beforeSend: function() {
                    $('.success, .warning').remove();
                    $('#button-qa').attr('disabled', true);
                    $('#qa-title').after('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
                },
                complete: function() {
                    $('#button-qa').attr('disabled', false);
                    $('.attention').remove();
                },
                success: function(data) {
                    if (data.error) {
                        $('#qa-title').after('<div class="warning alert alert-danger">' + data.error + '</div>');
                    }
                    if (data.success) {
                        $('#qa-title').after('<div class="success">' + data.success + '</div>');
                        $('textarea[name=\'question\']').val('');
                        $('input[name=\'q_captcha\']').val('');
                    }
                },
                error: function(xhr, status, error) {
                    $('#qa-title').after('<div class="warning alert alert-danger">' + xhr.statusText + '</div>');
                    //console.log(xhr.responseText);
                }
            });
        }
        //-->
    //--></script>
    <?php if ($qa_status) { ?>
        <script type="text/javascript"><!--
            $("#help_out_stock_tooltip").tooltip({
                show: {
                    effect: "slideDown",
                    delay: 250
                }
            });
            $('.container-fluid').not('#help_out_stock_tooltip').click(function(event) {
                $('#help_out_stock_tooltip').tooltip('hide');
            });
            $('#qa .pagination a').bind('click', function() {
                var href = this.href;
                $('#qa').slideUp('slow', function() {
                    $('#qa').load(href, function() {
                        $('#qa').slideDown('slow');
                    });
                });
                return false;
            });
            <?php if ($preload != 1) { ?>
                var product_changed_id = $('#product_id_change').val();
                $('#qa').load('index.php?route=product/product/question&gp_product=1&product_id='+product_changed_id); 
            <?php } ?>
            function myFunction() {
                var quan = $(".prod-desktop").find("input[name=quantity]").val();
                var prodOption = $(".prod-desktop").find(".id_convert_long:visible").find('option:selected').text();
                return quan + " " + prodOption + " = ";
            }
            // refrshTooltip();
            $("information_image discount_box.option_tooltip unit-tooltip").tooltip({
                show: {
                    effect: "slideDown",
                    delay: 250
                }
            });
            $('information_image discount_box.container-fluid').not('information_image discount_box.option_tooltip').click(function(event) {
                $('information_image discount_box.option_tooltip').tooltip('hide');
            });
            $(".id_convert_long:visible").find('option').each(function() {
                var b = /^<?php echo strtolower($unit_plural); ?>/;
                var a = $(this).text().toLowerCase();
                if ((a).match(b)) {
                    $(this).attr('selected', true);
                }
            });
            $(".id_convert_long:visible").trigger("change");
            function refrshTooltip() {
                $(".option_tooltip").tooltip({
                    show: {
                        effect: "slideDown",
                        delay: 250
                    }
                });
                $('.container-fluid').not('.option_tooltip:visible').click(function(event) {
                    $('.option_tooltip:visible').tooltip('hide');
                });
            }
            $(document).ready(function() {
                <?php if (!$logged) { ?>
                    $('.bulk-product-tex span.text-bulk').bind('click', function() {
                        $(".show_hide_box").toggle();
                    });
                <?php } ?>
            });
            //-->
            //login down
            $('.whole_sale_login').bind('click', function() {
                //$('#checkout #login').slideToggle('slow');
                $("#quick_login").attr('class', 'active');
                window.scrollTo(0, 0);
                $(".show_hide_box").hide();
            });
        
            $(document).on('touchstart',function(e){
                var clickElement = e.target;  // get the dom element clicked.
                var elementClassName = e.target.className;  // get the classname of the element clicked
                if($('.'+elementClassName).parents(".show_hide_box").length == '0') {
                    $(".show_hide_box").hide();
                }
            }); 
        </script> 
    <?php } ?>
    <script type="text/javascript"><!--
        $('.date').datetimepicker({
	        pickTime: false
        });
        $('.datetime').datetimepicker({
            pickDate: true,
            pickTime: true
        });

        $('.time').datetimepicker({
            pickDate: false
        });

        $('button[id^=\'button-upload\']').on('click', function() {
            var node = this;
            $('#form-upload').remove();
            $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');
            $('#form-upload input[name=\'file\']').trigger('click');
            if (typeof timer != 'undefined') {
                clearInterval(timer);
            }
            timer = setInterval(function() {
                if ($('#form-upload input[name=\'file\']').val() != '') {
                    clearInterval(timer);
                    $.ajax({
                        url: 'index.php?route=tool/upload',
                        type: 'post',
                        dataType: 'json',
                        data: new FormData($('#form-upload')[0]),
                        cache: false,
                        contentType: false,
                        processData: false,
                        beforeSend: function() {
                            $(node).button('loading');
                        },
                        complete: function() {
                            $(node).button('reset');
                        },
                        success: function(json) {
                            $('.text-danger').remove();
                            if (json['error']) {
                                $(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
                            }
                            if (json['success']) {
                                alert(json['success']);
                                $(node).parent().find('input').attr('value', json['code']);
                            }
                        },
				        error: function(xhr, ajaxOptions, thrownError) {
					        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			        	}
			        });
		        }
	        }, 500);
        });
    
        $('#review').delegate('.pagination a', 'click', function(e) {
            e.preventDefault();
            $('#review').fadeOut('slow');
            $('#review').load(this.href);
            $('#review').fadeIn('slow');
        });
        $('#review').load('index.php?route=product/product/review&gp_product=1&product_id=<?php echo $product_id; ?>');
        $('#button-review').on('click', function() {
            var product_changed_id = $('#product_id_change').val();
            $.ajax({
                url: 'index.php?route=product/product/write&product_id='+product_changed_id,
                type: 'post',
                dataType: 'json',
                data: $("#form-review").serialize(),
                beforeSend: function() {
                    $('#button-review').button('loading');
                },
                complete: function() {
                    $('#button-review').button('reset');
                },
                success: function(json) {
                    $('.alert-success, .alert-danger').remove();
                    if (json['error']) {
                        $('#review').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
                    }
                    if (json['success']) {
                        $('#review').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
                        $('input[name=\'name\']').val('');
                        $('textarea[name=\'text\']').val('');
                        $('input[name=\'rating\']:checked').prop('checked', false);
                    }
                }
            });
        });
    
        function getThumbs(selector){
            var run = [];
            var i = 0;
            $(selector).each(function(){
                i += 1;
                run[i] = $.extend(true,{},imageThumb);
                run[i].setVideo($(this).attr('data'));
                run[i].setContainer($(this).attr('id'));
                console.log($(this).attr('id'));
                run[i].generateThumb(true);
            }); 
        }

        $(document).on("click", ".media_additional" , function(e) {
            e.preventDefault();
            console.log($(this));
            var type = $(this).children("a").attr('id');
            var link = $(this).children("a").attr('href');
            console.log(type);
            console.log(link);
            if (type === "image"){
                var tab = "cimages"; //popupImage
                /*$("#bigImagePlayer").attr('href',link);
                $("#bigImagePlayer img").attr('src',link);
                $('.cloudzoom_popup, .cloudzoom-gallery').CloudZoom();
                $("#popupImage").CloudZoom();*/
                $('#imageDetails .nav-pills li a[href="' + '#'+ tab + '"]').trigger('click');
            }else{
                var vd = document.getElementById('bigVideoPlayer');
                vd.src = link;
                vd.load();
                vd.addEventListener('loadeddata', function() {
                    vd.play();
                },false);
                var tab = "cvideos";//bigVideoPlayer
                $('#imageDetails .nav-pills li a[href="' + '#'+ tab + '"]').trigger('click');
            }
            $("#imageDetails").modal();
        });

        $(document).on("click", ".media_load" , function(e) {
            e.preventDefault();
            console.log($(this));
            var type = $(this).attr('id');
            var link = $(this).attr('href');
            console.log(type);
            console.log(link);
            if (type === "image"){
                var tab = "cimages"; //popupImage
                /*$("#bigImagePlayer").attr('href',link);
                $("#bigImagePlayer img").attr('src',link);
                $('.cloudzoom_popup, .cloudzoom-gallery').CloudZoom();
                $("#popupImage").CloudZoom();*/
                $('#imageDetails .nav-pills li a[href="' + '#'+ tab + '"]').trigger('click');
            }else{
                var vd = document.getElementById('bigVideoPlayer');
                vd.src = link;
                vd.load();
                vd.addEventListener('loadeddata', function() {
                    vd.play();
                },false);
                var tab = "cvideos";//bigVideoPlayer
                $('#imageDetails .nav-pills li a[href="' + '#'+ tab + '"]').trigger('click');
            }
            $("#imageDetails").modal();
        });

        $("#popupImage").CloudZoom();

        /*$("#imageDetails").on('hidden.bs.modal',function(){
            var videoElement = document.getElementById('bigVideoPlayer');
            videoElement.pause();
            videoElement.removeAttribute('src'); // empty source
            videoElement.load();
        });*/
    
        $(document).ready(function() {
            /*$('.thumbnails').magnificPopup({
                type:'image',
                delegate: 'a',
                gallery: {
                    enabled:true
                }
            });*/
        });
        $("#goto-reviews").on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({scrollTop: $("#product-tabs").offset().top}, 1000);
            $('a[href=\'#tab-review\']').trigger('click');
        });
        $("#goto-qa").on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({scrollTop: $("#product-tabs").offset().top}, 1000);
            $('a[href=\'#tab-qa\']').trigger('click');
        });
        $(document).ready(function() {
            $('.fancySelect').fancySelect();
            $('.options select').fancySelect();
        });
    </script>
<?php echo $footer; ?>
