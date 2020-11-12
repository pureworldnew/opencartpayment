<?php echo $header;?><?php echo $column_left;?>
<?php echo $content_top;?>
<!--mid-block starts here-->
<div class="mid-block">
    <div class="product-info">
        <h1 class="visible-phone"><?php echo $heading_title;?></h1>
        <div class="row-fluid">
            <!--content-block starts here-->
            <div class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) {?>
                    <?php echo $breadcrumb['separator'];?><a href="<?php echo $breadcrumb['href'];?>"><?php echo $breadcrumb['text'];?></a>
                <?php }?>
            </div>
            <div class="span9 content-block">
                <!--product block starts here-->
                <div class="product-block ">
                    <div class="row-fluid">
                        <div class="image-block span6">
                            <?php if ($thumb) {?>
                                <div class="img-box"><a href="<?php echo $popup;?>" title="<?php echo $heading_title;?>" class="colorbox"><img src="<?php echo $thumb;?>" title="<?php echo $heading_title;?>" alt="<?php echo $heading_title;?>" id="image" /></a></div>
                            <?php } else {?>
                                <div class="img-box"><img src="<?php echo HTTP_SERVER;?>catalog/view/theme/default/image/no_product.jpg" title="<?php echo $heading_title;?>" alt="<?php echo $heading_title;?>" id="image" /></div>
                            <?php } if ($images) {?>
                                <div class="clearfix img-box2">
                                    <?php foreach ($images as $image) {?>
                                        <a href="<?php echo $image['popup'];?>" title="<?php echo $heading_title;?>" class="colorbox"><img src="<?php echo $image['thumb'];?>" title="<?php echo $heading_title;?>" alt="<?php echo $heading_title;?>" /></a>
                                    <?php }?>
                                </div>
                                <div class="share">
                                    <!-- AddThis Button BEGIN -->
                                    <div class="addthis_toolbox addthis_default_style">
                                        <a class="addthis_counter addthis_pill_style"></a>
                                    </div>
                                    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=xa-5257b0da51eea779"></script>
                                    <!-- AddThis Button END -->
                                </div>
                            <?php }?>
                        </div>
                        <div class="span6 detail">
                            <h1 class="visible-desktop visible-tablet"><?php echo $heading_title;?></h1>

                            <div class="top-gap">
                                <span class=" flt"><strong>Item Number: </strong><?php echo $sku;?></span>
                                <?php if ($review_status) {?>
                                    <div class="review_count">
                                        <span class="flr"><img src="catalog/view/theme/default/image/stars-<?php echo $rating;?>.png" alt="<?php echo $reviews;?>" />
                                            <a id="tabs2" onclick="$('a[href=\'#tab-review\']').trigger('click');">(<?php echo $reviews;?>)</a>
                                            <!--                <a onclick="$('a[href=\'#tab-review\']').trigger('click');"><?php echo $text_write;?></a>-->
                                        </span>
                                    </div>
                                <?php }?>
                            </div>
                            <p class="clearfix"></p>
                            <p style="float: left;width: 60%"><strong>Availability :</strong><span><?php echo $stock;?></span></p>
                            <div class="help_tool_image">
                                <?php if ($quantity <= 0) {?>
                                    <img id="help_out_stock_tooltip" src="admin/view/image/information.png" alt="" title ="This item is currently out of stock, but you can add this item to your wishlist and we will notify you when they become available." class="help_tool_out_img"/>
                                <?php } else {?>
                                    <img id="help_out_stock_tooltip" src="admin/view/image/information.png" alt="" title ="Product availability is updated every few days and may not be current. If we have sold out since the last update, we will contact you before shipping your order." class="help_tool_out_img"/>
                                <?php }?>
                            </div>
                            <div class="clearfix"></div>
                            <input type="hidden" id="base_price_input" value="<?php echo isset($base_price) ? $base_price : '0'?>">
                            <p><strong>Reference Number : </strong><span><?php echo $upc_ref;?></span></p>

                            <?php if ($logged) {?>
                                <p class="top-gap"><strong><?php echo $text_wholesale_login;?></strong></p>
                            <?php } else {?>
                                <p class="top-gap"><strong>Price :</strong></p>
                            <?php }?>
                            <p>
                                <?php if (!$special) {?>
                                    <?php
                                    echo "<span class='price-new'>" . $price . " "
                                    . "</span> <span class='price_red'> / </span> "
                                    . "<span class = 'price_red' id='quantity_span'></span> "
                                    . "<span class='price_red' id='unit_dis'>" . $unit_singular . "</span>";
                                    ?>
                                <?php } else {?>
                                    <span class="cossed-price">
                                        <?php echo $price;?>
                                    </span> 
                                    <span data-price="<?php echo $special;?>" class="price price-new">
                                        <?php echo $special;?>
                                    </span>
                                <?php }?>

                            <div class="width_100Float" style="">
                                <input id="plural_unit" type="hidden" value ="<?php echo $unit_plural;?>">
                                <strong>Unit Price (Tax  Excluded): <?php echo $price . " / " . $unit_singular;?> </strong>
                            </div>


                            <!--- price and taxes ---------->
                            <div class="price">
                                <?php if ($tax) {?>
                                    <span class="price-tax"><?php echo $text_tax;?> <?php echo $tax;?></span><br />
                                <?php }?>
                                <?php if ($points) {?>
                                    <span class="reward"><small><?php echo $text_points;?> <?php echo $points;?></small></span><br />
                                <?php }?>

                                <span class="links"><a onclick="addToWishList('<?php echo $product_id;?>');"><?php echo $button_wishlist;?></a>
                                    <a onclick="addToCompare('<?php echo $product_id;?>');"><?php echo $button_compare;?></a></span>
                            </div>
                            <!--- price ---------->

                            <!----- Payment Profiles --------------->

                            <?php if ($profiles):?>
                                <div class="option">
                                    <h2><span class="required">*</span><?php echo $text_payment_profile?></h2>
                                    <br />
                                    <select name="profile_id">
                                        <option value=""><?php echo $text_select;?></option>
                                        <?php foreach ($profiles as $profile):?>
                                            <option value="<?php echo $profile['profile_id']?>"><?php echo $profile['name']?></option>
                                        <?php endforeach;?>
                                    </select>
                                    <br />
                                    <br />
                                    <span id="profile-description"></span>
                                    <br />
                                    <br />
                                </div>
                            <?php endif;?>


                            <!----- Payment Profiles --------------->

                            <!--------- Options Type -------------->
                            <?php if ($options) {?>
                                <div class="options">
                                    <!--                            <h2><?php echo $text_option;?></h2>-->
                                    <?php foreach ($options as $option) {?>

                                        <!---------- Custom Radio Options ------------->
                                        <?php if ($option['type'] == 'radio') {
                                            ?>
                                            <div id="option-<?php echo $option['product_option_id'];?>" class="option option_custom length_content ig_<?php echo str_replace(' ', '', $option['name']);?>">
                                                <?php if ($option['required']) {?>
                                                    <span class="required option_label">* <b><?php echo $option['name'];?>:</b></span>
                                                <?php } else {
                                                    ?>
                                                    <span class="option_label"><b><?php echo $option['name'];?>:</b></span>
                                                <?php }?>
                                                <div class="option_container">
                                                    <?php foreach ($option['option_value'] as $option_value) {?>
                                                        <input type="radio" name="option[<?php echo $option['product_option_id'];?>]" value="<?php echo $option_value['product_option_value_id'];?>" id="option-value-<?php echo $option_value['product_option_value_id'];?>" />
                                                        <label for="option-value-<?php echo $option_value['product_option_value_id'];?>" data-val="<?php echo $option_value['name'];?>" ><?php echo $option_value['name'];?>
                                                            <!--                                        <?php if ($option_value['price']) {?>
                                                                                                                                                                                        (<?php echo $option_value['price_prefix'];?><?php echo $option_value['price'];?>)
                                                            <?php }?>-->
                                                        </label>
                                                    <?php }?>
                                                </div>
                                            </div>
                                        <?php }?>
                                        <!---------- Custom Radio Options ------------->

                                        <?php if ($option['type'] == 'select') {?>
                                            <div id="option-<?php echo $option['product_option_id'];?>" class="option ig_<?php echo str_replace(' ', '', $option['name']);?>">
                                                <?php if ($option['required']) {?>
                                                    <span class="required">*</span>
                                                <?php }?>
                                                <b><?php echo $option['name'];?>:</b>
                                                <select name="option[<?php echo $option['product_option_id'];?>]">
                                                    <?php
                                                    echo $option['metal_type'];
                                                    if ($option['metal_type'] > 1) {
                                                        ?>
                                                        <option value=""><?php echo $text_select;?></option>
                                                    <?php }
                                                    ?>
                                                    <?php foreach ($option['option_value'] as $option_value) {?>
                                                        <option data-price="<?php echo ($option_value['price2'] ? $option_value['price2'] : '' )?>" value="<?php echo $option_value['product_option_value_id'];?>"><?php echo $option_value['name'];?>
                                                            <?php /* if ($option_value['price']) { ?>
                                                              (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                                                              <?php } */?>
                                                        </option>
                                                    <?php }?>
                                                </select>
                                                
                                            </div>


                                        <?php }?>

                                        <?php if ($option['type'] == 'checkbox') {?>
                                            <div id="option-<?php echo $option['product_option_id'];?>" class="option product_checkboxes">
                                                <?php if ($option['required']) {?>
                                                    <span class="required">* <b><?php echo $option['name'];?>:</b></span><br/>
                                                <?php }?>
                                                <?php foreach ($option['option_value'] as $option_value) {?>
                                                    <input type="checkbox" name="option[<?php echo $option['product_option_id'];?>][]" value="<?php echo $option_value['product_option_value_id'];?>" id="option-value-<?php echo $option_value['product_option_value_id'];?>" />
                                                    <label for="option-value-<?php echo $option_value['product_option_value_id'];?>" ><?php echo $option_value['name'];?>
                                                        <?php if ($option_value['price']) {?>
                                                            (<?php echo $option_value['price_prefix'];?><?php echo $option_value['price'];?>)
                                                        <?php }?>
                                                    </label><br/>
                                                <?php }?>
                                            </div>
                                        <?php }?>
                                        <?php if ($option['type'] == 'image') {?>
                                            <div id="option-<?php echo $option['product_option_id'];?>" class="option">
                                                <?php if ($option['required']) {?>
                                                    <span class="required">* <b><?php echo $option['name'];?>:</b></span>
                                                <?php }?>

                                                <table class="option-image">
                                                    <?php foreach ($option['option_value'] as $option_value) {?>
                                                        <tr>
                                                            <td style="width: 1px;"><input type="radio" name="option[<?php echo $option['product_option_id'];?>]" value="<?php echo $option_value['product_option_value_id'];?>" id="option-value-<?php echo $option_value['product_option_value_id'];?>" /></td>
                                                            <td><label for="option-value-<?php echo $option_value['product_option_value_id'];?>"><img src="<?php echo $option_value['image'];?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : '');?>" /></label></td>
                                                            <td><label for="option-value-<?php echo $option_value['product_option_value_id'];?>"><?php echo $option_value['name'];?>
                                                                    <?php if ($option_value['price']) {?>
                                                                        (<?php echo $option_value['price_prefix'];?><?php echo $option_value['price'];?>)
                                                                    <?php }?>
                                                                </label></td>
                                                        </tr>
                                                    <?php }?>
                                                </table>
                                            </div>
                                        <?php }?>
                                        <?php if ($option['type'] == 'text') {?>
                                            <div id="option-<?php echo $option['product_option_id'];?>" class="option product_textfield">
                                                <?php if ($option['required']) {?>
                                                    <span class="required">* <b><?php echo $option['name'];?>:</b></span>
                                                <?php }?>

                                                <input type="text" name="option[<?php echo $option['product_option_id'];?>]" value="<?php echo $option['option_value'];?>" />
                                            </div>
                                        <?php }?>
                                        <?php if ($option['type'] == 'textarea') {?>
                                            <div id="option-<?php echo $option['product_option_id'];?>" class="option">
                                                <?php if ($option['required']) {?>
                                                    <span class="required">* <b><?php echo $option['name'];?>:</b></span>
                                                <?php }?>

                                                <textarea name="option[<?php echo $option['product_option_id'];?>]" cols="40" rows="5"><?php echo $option['option_value'];?></textarea>
                                            </div>
                                        <?php }?>
                                        <?php if ($option['type'] == 'file') {?>
                                            <div id="option-<?php echo $option['product_option_id'];?>" class="option">
                                                <?php if ($option['required']) {?>
                                                    <span class="required">* <b><?php echo $option['name'];?>:</b></span>
                                                <?php }?>

                                                <input type="button" value="<?php echo $button_upload;?>" id="button-option-<?php echo $option['product_option_id'];?>" class="button">
                                                <input type="hidden" name="option[<?php echo $option['product_option_id'];?>]" value="" />
                                            </div>
                                        <?php }?>
                                        <?php if ($option['type'] == 'date') {?>
                                            <div id="option-<?php echo $option['product_option_id'];?>" class="option product_textfield">
                                                <?php if ($option['required']) {?>
                                                    <span class="required">* <b><?php echo $option['name'];?>:</b></span>
                                                <?php } else {?>
                                                    <b><?php echo $option['name'];?>:</b>
                                                <?php }?>
                                                <input type="text" name="option[<?php echo $option['product_option_id'];?>]" value="<?php echo $option['option_value'];?>" class="date" />
                                            </div>
                                        <?php }?>
                                        <?php if ($option['type'] == 'datetime') {?>
                                            <div id="option-<?php echo $option['product_option_id'];?>" class="option product_textfield">
                                                <?php if ($option['required']) {?>
                                                    <span class="required">* <b><?php echo $option['name'];?>:</b></span>
                                                <?php } else {?>
                                                    <b><?php echo $option['name'];?>:</b>
                                                <?php }?>
                                                <input type="text" name="option[<?php echo $option['product_option_id'];?>]" value="<?php echo $option['option_value'];?>" class="datetime" />
                                            </div>
                                        <?php }?>
                                        <?php if ($option['type'] == 'time') {?>
                                            <div id="option-<?php echo $option['product_option_id'];?>" class="option product_textfield">
                                                <?php if ($option['required']) {?>
                                                    <span class="required">*</span>
                                                <?php }?>
                                                <b><?php echo $option['name'];?>:</b><br />
                                                <input type="text" name="option[<?php echo $option['product_option_id'];?>]" value="<?php echo $option['option_value'];?>" class="time" />
                                            </div>
                                        <?php }?>
                                    <?php }?>

                                </div>
                                    
                            <?php }?>
                            <!--  <div class="prod-mobile qty visible-phone" style="float: left; margin-top: 10px;">
                                  <p class=" entry flt">
                                      <label><?php echo $text_qty;?></label>
                                      <input type="text" name="quantity" size="2" value="<?php echo $minimum;?>" />
                                      <input type="hidden" name="product_id" size="2" value="<?php echo $product_id;?>" />
                                  </p><img src="catalog/view/theme/default/img/100percent.png" alt="" class="flt"/>
                                  <div class="clearfix"></div>
                                  <p class="pay-100">
                                      <input type="button" id="button-cart" class="qty-cart button-cart" />-->
                                      <!--                                         <a href="#" class="qty-cart">Add to cart <img src="img/cart.png" alt=""/></a>-->
                                    <!--   <img src="catalog/view/theme/default/img/small-paypal.png" alt="" class="pay-img"/></p>
                            <?php if ($minimum > 1) {?>
                                                                          <div class="minimum"><?php echo $text_minimum;?></div>
                            <?php }?>
                              </div>-->
                            <!--------- Product Type -------------->
                            <!-- Block For Unit -->
<div class="prod-desktop qty visible-phone ">
                    <div class="information_image" style="display:none;">
                        <img id="option_tooltip"   src="admin/view/image/information.png" alt="" class="pay-img"/>
                    </div>
                    <div class="ig_MetalType ig_Units">
                        <?php
                        //pr($unit_conversion_help); 

                        if ($options) {
                            ?>
                            <?php foreach ($options as $option) {?>
                                <?php if ($option['type'] == 'select') {?>
                                    <?php
                                    $UnitArry = explode(' ', $option['name']);
                                    if (strtolower(end($UnitArry)) == "units") {
                                        ?>
                                        <select id="id_convert_long" name="option[<?php echo $option['product_option_id'];?>]">
                                            <?php
                                            if ($option['metal_type'] > 1) {
                                                ?> <option value=""><?php echo $text_select;?></option>
                                                <?php
                                            }
                                            foreach ($option['option_value'] as $option_value) {
                                                ?>
                                                <option data-price="<?php echo ($option_value['price2'] ? $option_value['price2'] : '' )?>" value="<?php echo $option_value['product_option_value_id'];?>"><?php echo $option_value['name'];?>
                                                </option>
                                            <?php }?>
                                        </select>
                                        <?php
                                    }
                                }
                            }
                        }
                        ?>
                    </div>
                    <p class=" entry flt">
                        <label><?php echo $text_qty;?></label>
                        <input type="text" name="quantity" size="2" value="<?php echo $minimum;?>" />
                        <input type="hidden" name="original_price" value="<?php echo $old_price;?>" >
                        <input type="hidden" name="product_id" size="2" value="<?php echo $product_id;?>" />
                    </p><img src="catalog/view/theme/default/img/100percent.png" alt="" class="flt money-back"/>
                    <div class="clearfix"></div>
                    <p class="pay-100">
                        <input type="button" id="button-cart" class="qty-cart button-cart" />
                        <!--                            <a href="#" class="qty-cart">Add to cart <img src="img/cart.png" alt=""/></a>-->
                        <img src="catalog/view/theme/default/img/small-paypal.png" alt="" class="pay-img"/></p>
                    <?php if ($minimum > 1) {?>
                        <div class="minimum"><?php echo $text_minimum;?></div>
                    <?php }?>
                </div>
                        </div><div class="row-fluid">
                    <div class="span12">
                        <div class="ui_tabs products-uitabs">
                            <div id="tabs" class="htabs">
                                <a href="#tab-description"><?php echo $tab_description;?></a>
                                <!--<?php if ($attribute_groups) { ?>
                                    <a href="#tab-attribute"><?php echo $tab_attribute;?></a>
                                <?php }?> -->
                                <?php if ($review_status) {?>
                                    <a href="#tab-review">
                                        <?php echo $tab_review;?></a>
                                <?php }?>
                                <a href="#tab-qa"><?php echo $tab_qa;?> </a>
                                </li>
                            </div>
                                <div id="tab-description" class="tab-content"><div class="tab_contents"><?php echo $description;?></div>
                                    <?php if ($attribute_groups) {?>
                                <div id="tab-attribute" class="tab-content">
                                    <table class="attribute">
                                        <?php foreach ($attribute_groups as $attribute_group) {?>
                                            <thead>
                                                <tr>
                                                    <td colspan="2"><?php echo $attribute_group['name'];?></td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($attribute_group['attribute'] as $attribute) {?>
                                                    <tr>
                                                        <td><?php echo $attribute['name'];?></td>
                                                        <td><?php echo $attribute['text'];?></td>
                                                    </tr>
                                                <?php }?>
                                            </tbody>
                                        <?php }?>
                                    </table>
                                </div>
                            <?php }?>
                                </div>


                            
                            <?php if ($review_status) {?>
                                <div id="tab-review" class="tab-content">
                                    <div class="product_reviews">
                                        <div id="review"></div>
                                        <div class="review_main">
                                            <div class="contact-form">
                                                <h2 id="review-title"><?php echo $text_write;?></h2>
                                                <!--                                <b><?php echo $entry_name;?></b><br />-->
                                                <p>
                                                    <img alt="Name" src="catalog/view/theme/default/img/name-icon.jpg">
                                                    <input placeholder="Enter your Name" type="text" name="name" value="" />
                                                </p>
                                                <p>
                                                    <img alt="Message" src="catalog/view/theme/default/img/message-icon.jpg">
                                                    <textarea  placeholder="Enter your review" name="text" cols="40" rows="8" style="width: 98%;"></textarea>
                                                </p>
                                                <!--                                    <span style="font-size: 11px;"><?php echo $text_note;?></span><br />-->
                                                <div class="product_ratings">
                                                    <b><?php echo $entry_rating;?></b> <span><?php echo $entry_bad;?></span>&nbsp;
                                                    <input type="radio" name="rating" value="1" />
                                                    &nbsp;
                                                    <input type="radio" name="rating" value="2" />
                                                    &nbsp;
                                                    <input type="radio" name="rating" value="3" />
                                                    &nbsp;
                                                    <input type="radio" name="rating" value="4" />
                                                    &nbsp;
                                                    <input type="radio" name="rating" value="5" />
                                                    &nbsp;<span><?php echo $entry_good;?></span>
                                                </div>
                                                <!--                                                <div class="product_captchas">
                                                                                                                                            <b><?php //echo $entry_captcha;?></b><br />
                                                                                                    <p><input placeholder="Enter the captcha" type="text" name="captcha" value="" /></p>
                                                                                                    <img src="index.php?route=product/product/captcha" alt="" id="captcha" /><br />
                                                                                                    <br />
                                                                                                </div>-->
                                                <div class="buttons">
                                                    <div class="right"><a id="button-review" class="button"><?php echo "Submit Review";?></a></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="tab-qa" class="tab-content product-tabs-content std">
                                    <div class ="tab_contents">
                                        <div id="qa"><?php echo $qas;?></div>
                                        <h2 id="qa-title"><?php echo $text_ask;?></h2>
                                        <span style="float:left; padding-right:2em;">
                                            <b><?php echo $entry_name;?></b><br />
                                            <input class="input-text" type="text" name="questioner" value="<?php echo $qa_name;?>" />
                                        </span>
                                        <?php if ($qa_notify) {?>
                                            <span style="float:left; padding-right:2em;">
                                                <b><?php echo $entry_email;?></b><br />
                                                <input class="input-text" type="text" name="q_email" value="<?php echo $qa_email;?>" />
                                            </span>
                                        <?php }?>
                                        <br style="clear:both;" />
                                        <br />
                                        <b><?php echo $entry_question;?></b>
                                        <textarea name="question" style="width: 98%;" rows="8"></textarea>
                                        <span style="font-size: 11px;"><?php echo $text_note;?></span><br />
                                        <!--                                        <br />
                                                                                <b><?php echo $entry_captcha;?></b><br />
                                                                                <input class="input-text" type="text" name="q_captcha" value="" autocomplete="off" />
                                                                                <br />
                                                                                <br />
                                                                                <img src="index.php?route=product/product/captcha" id="q_captcha" /><br />
                                                                                <br />-->
                                        <div class="action">
                                            <div class="buttons">
                                                <div class="left">
                                                    <a id="button-qa" class="product-also-add-to-cart" class="button">
                                                        <?php echo "Submit Question";?></a>
                                                </div>
                                            </div>
                                        </div>

                                        </br>
                                    </div>
                                </div>
                            <?php }?>
                            <!--                <?php if ($tags) {?>
                                                                                                                                <div class="tags"><b><?php echo $text_tags;?></b>
                                <?php for ($i = 0; $i < count($tags); $i++) {?>
                                    <?php if ($i < (count($tags) - 1)) {?>
                                                                                                                                                                                                                                                                                                            <a href="<?php echo $tags[$i]['href'];?>"><?php echo $tags[$i]['tag'];?></a>,
                                    <?php } else {?>
                                                                                                                                                                                                                                                                                                            <a href="<?php echo $tags[$i]['href'];?>"><?php echo $tags[$i]['tag'];?></a>
                                    <?php }?>
                                <?php }?>
                                                                                                                                </div>
                            <?php }?>-->
                        </div>
                    </div>
                </div>
                    </div>
                </div>
  <!--sidebar starts here-->
            <div class="span3 sidebar">
                <div class="prod-desktop qty qty1">
                    <div class="information_image" style="display:none;">
                        <img id="option_tooltip"   src="admin/view/image/information.png" alt="" class="pay-img"/>
                    </div>
                    <div class="ig_MetalType ig_Units">
                        <?php
                        //pr($unit_conversion_help); 

                        if ($options) {
                            ?>
                            <?php foreach ($options as $option) {?>
                                <?php if ($option['type'] == 'select') {?>
                                    <?php
                                    $UnitArry = explode(' ', $option['name']);
                                    if (strtolower(end($UnitArry)) == "units") {
                                        ?>
                                        <select id="id_convert_long" name="option[<?php echo $option['product_option_id'];?>]">
                                            <?php
                                            if ($option['metal_type'] > 1) {
                                                ?> <option value=""><?php echo $text_select;?></option>
                                                <?php
                                            }
                                            foreach ($option['option_value'] as $option_value) {
                                                ?>
                                                <option data-price="<?php echo ($option_value['price2'] ? $option_value['price2'] : '' )?>" value="<?php echo $option_value['product_option_value_id'];?>"><?php echo $option_value['name'];?>
                                                </option>
                                            <?php }?>
                                        </select>
                                        <?php
                                    }
                                }
                            }
                        }
                        ?>
                    </div>
                    <p class=" entry flt">
                        <label><?php echo $text_qty;?></label>
                        <input type="text" name="quantity" size="2" value="<?php echo $minimum;?>" />
                        <input type="hidden" name="original_price" value="<?php echo $old_price;?>" >
                        <input type="hidden" name="product_id" size="2" value="<?php echo $product_id;?>" />
                    </p><img src="catalog/view/theme/default/img/100percent.png" alt="" class="flt money-back"/>
                    <div class="clearfix"></div>
                    <p class="pay-100">
                        <input type="button" id="button-cart" class="qty-cart button-cart" />
                        <!--                            <a href="#" class="qty-cart">Add to cart <img src="img/cart.png" alt=""/></a>-->
                        <img src="catalog/view/theme/default/img/small-paypal.png" alt="" class="pay-img"/></p>
                    <?php if ($minimum > 1) {?>
                        <div class="minimum"><?php echo $text_minimum;?></div>
                    <?php }?>
                </div>
                <!--            <?php if ($discounts) {?>

                    <?php foreach ($discounts as $discount) {?>
                        <?php echo sprintf($text_discount, $discount['quantity'], $discount['price']);?><br />
                    <?php }?>

                <?php }?>-->
                <?php if ($logged) {?>
                    <?php if ($discounts) {?>

                        <div class="price-scale">
                            <p class="he">Bulk Pricing and <br />Quantity Discounts</p>
                            <div class="information_image discount_box">
                                <img id="option_tooltip_bulk" src="admin/view/image/information.png" alt="" class="pay-img" title="We offer discounts for wholesale customers depending on the quantity of each item ordered. Discounts are automatically applied in your cart."/>
                            </div> <p class="mid"><span class="flt">

                                </span><span class="flr scale-pr">Price</span></p>
                            <ul>
                                <li>
                                    <span class="scale-quantity"><?php echo "Non-Wholesale";?></span>

                                    <span class="scale-price"><?php echo $price_without_discount;?></span>

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
                                            <?php echo $discount['quantity'] . $nextQuan . " " . $unit_plural;?>
                                        </span>
                                        <span class="scale-price">
                                            <?php echo $discount['price'];?>
                                        </span>
                                    </li>
                                <?php }?>
                            </ul>
                        </div>

                    <?php }?>
                <?php } else {?>
                    <div class="price-scale-login">
                        <p><?php echo $text_price_login;?></p>
                    </div>
                <?php }?>
                <div class="more_information">For Quotes on Significantly Larger Quantities, 
                    Please <a href="<?php echo $contact?>"> Contact Us</a></div>
                <div class="information_right">

                    <?php if ($products) {?>
                        <div class="product_related">
                            <div class="box-heading">May We Recommend
                                <!--      <?php //echo $heading_title;           ?>-->
                            </div>
                            <div class="box-product">
                                <?php foreach ($products as $product) {?>
                                    <div class="may">
                                        <ul>
                                            <?php if ($product['thumb']) {?>
                                                <li>
                                                    <div class="li_content">
                                                        <a href="<?php echo $product['href'];?>"><img width="54" height="62" src="<?php echo $product['thumb'];?>" alt="<?php echo $product['name'];?>"/></a>
                                                    <?php }?>
                                                    <div class="info">
                                                        <h3><a href="<?php echo $product['href'];?>"><?php
                                                                if (strlen($product['name']) > 12) {
                                                                    echo substr($product['name'], 0, 12) . "...";
                                                                } else {
                                                                    echo $product['name'];
                                                                }
                                                                ?></a></h3>
                                                        <?php if ($product['price']) {?>
                                                            <p>
                                                                <span>
                                                                    <?php if (!$product['special']) {?>
                                                                        <?php echo $product['price'];?>
                                                                    <?php } else {?>
                                                                        <span class="price-old"><?php echo $product['price'];?></span><br />

                                                                        <span class="price-new"><?php echo $product['special'];?></span>
                                                                    <?php }?>
                                                                </span>
                                                            <?php }?>
                                                            <!--    <?php if ($product['rating']) {?>
                                                                                                                                                                                <div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $product['rating'];?>.png" alt="<?php echo $product['reviews'];?>" /></div>
                                                            <?php }?>-->
                                                            <a onclick="addToCart('<?php echo $product['product_id'];?>');" class="button">Buy now</a>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                <?php }?>

                            </div>
                        </div>
                        
                    <?php }?>
                    <?php echo $column_right;?>
                </div>
            </div>
            <!--sidebar end here-->
                <!--tabs starts here-->
              
                
                <?php echo $content_bottom;?>
            </div>
            <!--content-block end here-->



          
        </div>
    </div>
</div>

<!--mid-block starts here-->



<script type="text/javascript"><!--
    $(document).ready(function() {
        $('.colorbox').colorbox({
            overlayClose: true,
            opacity: 0.5,
            rel: "colorbox"
        });
    });
//--></script>
<script type="text/javascript"><!--


    $('.ig_Units select').change(function() {
//        var quantity = $('.prod-desktop .entry input[name="quantity"]').val();
//        var option_value = $(this).val();
//        var price2 = $('input[name="original_price"]').val();
//        var prod_price = (price2*option_value)*quantity;
//        $(".product-block").find(".price-new").html("$"+parseFloat(prod_price).toFixed(2));
    });

    $('.ig_MetalType select').change(function() {
        if ($('.prod-mobile').is(':visible')) {
            updatePriceMobile();
        }
        else if ($('.prod-desktop').is(':visible')) {
            updatePrice();
        }
    });

    $('.prod-desktop .entry input[name="quantity"]').blur(function() {
        updatePrice();
    });

    function updatePrice() {
        //refrshTooltip();
        var p_id = $('input[name="product_id"]').val();
        var base_price = $("#base_price_input").val();
        var quantity = $(".prod-desktop input[name=quantity]").val();
        var unit_type = $("#id_convert_long").find('option:selected').attr('data-value');
        var unit_conversion = $("#id_convert_long").find('option:selected').val();
        var unit_conversion_text = $(".convert_select").find('option:selected').text();
        var simplePrice = $(".top-gap").next().find(".price-new").text();
        var unit_fullName = $("#id_convert_long").find('option:selected').text();
        var plural_unit = $("#plural_unit").val();
        $(".ig_LengthUnits").find('select').find('option').each(function() {
            if ($.trim($(this).text()) == $.trim(unit_conversion_text)) {
                $(this).attr('selected', true);
            }
        });
        $(".ig_Weightunits").find('select').find('option').each(function() {
            if ($.trim($(this).text()) == $.trim(unit_conversion_text)) {
                $(this).attr('selected', true);
            }
        });
        $.ajax({
            url: 'index.php?route=product/product/calcPrice2',
            type: 'post',
            dataType: 'json',
            data: {"p_id": p_id,
                "simplePrice": simplePrice,
                "base_price": base_price,
                "quantity": quantity,
                "unit_type": unit_type,
                "unit_conversion": unit_conversion,
                "unit_fullName": unit_fullName,
                "plural_unit": plural_unit
            },
            success: function(resp) {
                $(".product-block").find(".price-new").html(resp.calc_price);
                $(".product-block").find("#quantity_span").html(resp.quantity);
                $(".product-block").find("#unit_dis").html(resp.unit_fullName);
                var quan = $(".prod-desktop").find("input[name=quantity]").val();
                var prodOption = $(".prod-desktop").find("#id_convert_long").find('option:selected').text();
                var resUnits = resp.discount_quantity;
                var units = (resUnits > 1) ? "<?php echo $unit_plural?>" : "<?php echo $unit_singular;?>"
                var helpText = quan + " " + prodOption + " = " + resUnits + " " + units;
                var defaultUnit = $("#plural_unit").val();
                var a = prodOption;
                var b = /^<?php echo $unit_plural;?>/;
                if ((a).match(b)) {
                    helpText = "Use this menu to calculate prices in different units";
                }
                $('#option_tooltip').attr('data-original-title', helpText);
                if (resp.show_tooltip) {
                    $(".information_image").show();
                }
                refrshTooltip();
            }
        });
    }

    /*Update price functionality for mobile*/
    $('.prod-mobile .entry input[name="quantity"]').blur(function() {
        updatePriceMobile();
    });

    function updatePriceMobile() {

        var p_id = $('input[name="product_id"]').val();
        var quantity = $('.prod-mobile .entry input[name="quantity"]').val();
        var option_value = $(".ig_MetalType select option").filter(":selected").attr('data-price');
        var price2 = $('input[name="original_price"]').val();

        $.ajax({
            url: 'index.php?route=product/product/calcPrice',
            type: 'post',
            data: {"p_id": p_id, "quantity": quantity, "option_value": option_value, "price": price2},
            success: function(resp) {
                $(".product-block").find(".price-new").html("$" + parseFloat(resp).toFixed(2));
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

    $('.button-cart').bind('click', function() {
        var quan = $(".prod-desktop").find("input[name=quantity]").val();
        var quan2 = $(".prod-mobile").find("input[name=quantity]").val();
        if ((quan < 1) || (quan2 < 1)) {
            alert("Please Enter Valid Quantity");
            return false;
        }
        $.ajax({
            url: 'index.php?route=checkout/cart/add',
            type: 'post',
            data: $('.product-info input[type=\'text\'], .product-info input[type=\'hidden\'], .product-info input[type=\'radio\']:checked, .product-info input[type=\'checkbox\']:checked, .product-info select, .product-info textarea'),
            dataType: 'json',
            success: function(json) {
                $('.success, .warning, .attention, information, .error').remove();

                if (json['error']) {
                    if (json['error']['option']) {
                        for (i in json['error']['option']) {
                            $('#option-' + i).after('<span class="error">' + json['error']['option'][i] + '</span>');
                        }
                    }

                    if (json['error']['profile']) {
                        $('select[name="profile_id"]').after('<span class="error">' + json['error']['profile'] + '</span>');
                    }
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
//--></script>
<?php if ($options) {?>
    <script type="text/javascript" src="catalog/view/javascript/jquery/ajaxupload.js"></script>
    <?php foreach ($options as $option) {?>
        <?php if ($option['type'] == 'file') {?>
            <script type="text/javascript"><!--
                new AjaxUpload('#button-option-<?php echo $option['product_option_id'];?>', {
                    action: 'index.php?route=product/product/upload',
                    name: 'file',
                    autoSubmit: true,
                    responseType: 'json',
                    onSubmit: function(file, extension) {
                        $('#button-option-<?php echo $option['product_option_id'];?>').after('<img src="catalog/view/theme/default/image/loading.gif" class="loading" style="padding-left: 5px;" />');
                        $('#button-option-<?php echo $option['product_option_id'];?>').attr('disabled', true);
                    },
                    onComplete: function(file, json) {
                        $('#button-option-<?php echo $option['product_option_id'];?>').attr('disabled', false);

                        $('.error').remove();

                        if (json['success']) {
                            alert(json['success']);

                            $('input[name=\'option[<?php echo $option['product_option_id'];?>]\']').attr('value', json['file']);
                        }

                        if (json['error']) {
                            $('#option-<?php echo $option['product_option_id'];?>').after('<span class="error">' + json['error'] + '</span>');
                        }

                        $('.loading').remove();
                    }
                });
                //--></script>
        <?php }?>
    <?php }?>
<?php }?>
<script type="text/javascript"><!--
   jQuery('a#tabs2').click(function(e) {
        e.preventDefault();
        var scrollto = $(".products-uitabs");
        jQuery('html, body').animate({scrollTop: scrollto.offset().top}, 1500);
    });
    $('#review .pagination a').live('click', function() {
        $('#review').fadeOut('slow');

        $('#review').load(this.href);

        $('#review').fadeIn('slow');

        return false;
    });

    $('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id;?>');

    $('#button-review').bind('click', function() {
        $.ajax({
            url: 'index.php?route=product/product/write&product_id=<?php echo $product_id;?>',
            type: 'post',
            dataType: 'json',
            data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : '') + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),
            beforeSend: function() {
                $('.success, .warning').remove();
                $('#button-review').attr('disabled', true);
                $('#review-title').after('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_wait;?></div>');
            },
            complete: function() {
                $('#button-review').attr('disabled', false);
                $('.attention').remove();
            },
            success: function(data) {
                if (data['error']) {
                    $('#review-title').after('<div class="warning">' + data['error'] + '</div>');
                }

                if (data['success']) {
                    $('#review-title').after('<div class="success">' + data['success'] + '</div>');

                    $('input[name=\'name\']').val('');
                    $('textarea[name=\'text\']').val('');
                    $('input[name=\'rating\']:checked').attr('checked', '');
                    $('input[name=\'captcha\']').val('');
                }
            }
        });
    });
//--></script>
<script type="text/javascript"><!--
    $('#tabs a').tabs();
//--></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript"><!--
    $(document).ready(function() {
        $(".ig_LengthUnits").css('display', 'none');
        $(".ig_Weightunits").css('display', 'none');

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
//--></script>


<?php echo $footer;?>
<?php if ($qa_status) {?>
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

        $('#qa .pagination a').live('click', function() {
            var href = this.href;
            $('#qa').slideUp('slow', function() {
                $('#qa').load(href, function() {
                    $('#qa').slideDown('slow');
                });
            });
            return false;
        });
    <?php if ($preload != 1) {?>
            $('#qa').load('index.php?route=product/product/question&product_id=<?php echo $product_id;?>');
    <?php }?>
        $('#button-qa').bind('click', function() {
            $.ajax({
                type: 'POST',
                url: 'index.php?route=product/product/ask&product_id=<?php echo $product_id;?>',
                dataType: 'json',
                data: 'name=' + encodeURIComponent($('input[name=\'questioner\']').val()) + '&email=' + encodeURIComponent(($('input[name=\'q_email\']').length != 0) ? $('input[name=\'q_email\']').val() : '') + '&question=' + encodeURIComponent($('textarea[name=\'question\']').val()) + '&captcha=' + encodeURIComponent($('input[name=\'q_captcha\']').val()),
                beforeSend: function() {
                    $('.success, .warning').remove();
                    $('#button-qa').attr('disabled', true);
                    $('#qa-title').after('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_wait;?></div>');
                },
                complete: function() {
                    $('#button-qa').attr('disabled', false);
                    $('.attention').remove();
                },
                success: function(data) {
                    if (data.error) {
                        $('#qa-title').after('<div class="warning">' + data.error + '</div>');
                    }
                    if (data.success) {
                        $('#qa-title').after('<div class="success">' + data.success + '</div>');
                        $('textarea[name=\'question\']').val('');
                        $('input[name=\'q_captcha\']').val('');
                    }
                },
                error: function(xhr, status, error) {
                    $('#qa-title').after('<div class="warning">' + xhr.statusText + '</div>');
                    //console.log(xhr.responseText);
                }
            });
        });

        function myFunction() {
            var quan = $(".prod-desktop").find("input[name=quantity]").val();
            var prodOption = $(".prod-desktop").find("#id_convert_long").find('option:selected').text();
            return quan + " " + prodOption + " = ";
        }
        // refrshTooltip();
        $("#option_tooltip_bulk").tooltip({
            show: {
                effect: "slideDown",
                delay: 250
            }
        });

        $('.container-fluid').not('#option_tooltip_bulk').click(function(event) {
            $('#option_tooltip_bulk').tooltip('hide');
        });
        $("#id_convert_long").find('option').each(function() {
            var b = /^<?php echo strtolower($unit_plural);?>/;
            var a = $(this).text().toLowerCase();
            if ((a).match(b)) {
                $(this).attr('selected', true);
            }
        });
        $("#id_convert_long").trigger("change");

        function refrshTooltip() {
            $("#option_tooltip").tooltip({
                show: {
                    effect: "slideDown",
                    delay: 250
                }
            });

            $('.container-fluid').not('#option_tooltip').click(function(event) {
                $('#option_tooltip').tooltip('hide');
            });
        }
        //--></script>
    <?php
}?>