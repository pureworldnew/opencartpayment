<?php echo $header; ?><?php echo $column_left; ?><?php /* echo $column_right; */ ?>
<?php /*
  #file: catalog/view/theme/default/template/product/product_bundle_default.tpl
  #powered by fabiom7 - www.fabiom7.com - fabiome77@hotmail.it - copyright fabiom7 2012 - 2013 - 2014
  #switched: v1.5.4.1 - v1.5.5.1 - v1.5.6
*/ ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  
<!-- Start Grouped Product powered by www.fabiom7.com - Italy -->
  <div class="product-info-grouped-product">
  
    <?php if ($this->config->get('gp_table_position_bundle') == 'right') { ?>
    <?php if ($thumb || $images) { ?>
    <div class="image-container-grouped-product">
      <?php if ($thumb) { ?>
      <div class="image-grouped-product">
        <a href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>" class="colorbox"><img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" id="image" /></a>
      </div>
      <?php } ?>
      <?php if ($images) { ?>
      <div class="image-additional-grouped-product image-additional-grouped-product-left">
        <?php foreach ($images as $image) { ?>
        <a href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>" class="colorbox"><img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>
        <?php } ?>
      </div>
      <?php } ?>
    </div>
    <?php } ?>
    <?php } elseif ($this->config->get('gp_table_position_bundle') == 'bottom') { ?>
    <div class="image-container-grouped-product">
      <?php if ($thumb) { ?>
      <div class="image-grouped-product">
        <a href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>" class="colorbox"><img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" id="image" /></a>
      </div>
      <?php } ?>
      <?php if ($images) { ?>
      <?php foreach ($images as $image) { ?>
      <div class="image-additional-grouped-product image-additional-grouped-product-right">
        <a href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>" class="colorbox"><img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>
      </div>
      <?php } ?>
      <?php } ?>
    </div>
    <div style="clear:both;"></div>
    <?php } ?>
    
    <div class="grouped-product gp-bundle">
      <?php if ($error_bundle) { ?>
      <div class="gp-error-txt"><?php echo $error_bundle; ?></div>
      <?php } ?>
      
      <?php if ($product_grouped) { ?>
      <form action="<?php echo $action_add_bundle; ?>" method="post" enctype="multipart/form-data" id="form-bundle-addtocart">
<table id="gp-table" class="gp-table">
  <thead>
    <tr class="gp-thead">
      <?php if ($gp_column_image) { ?>
      <td><?php echo $gp_column_image; ?></td>
      <?php } ?>
      <td class="gp-toggle"><?php echo $gp_column_name; ?> <span class="gp-toggle-piu">+</span><span class="gp-toggle-meno">-</span></td>
      <?php if ($gp_column_option) { ?>
      <td><?php echo $gp_column_option; ?></td>
      <?php } ?>
      <td><?php echo $gp_column_price; ?></td>
      <td><?php echo $gp_column_qty; ?></td>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($product_grouped as $product) { ?>
  <tr class="gp-tbody" id="gp-tbody<?php echo $product['product_id'] ?>">
    <!-- Inizio cella 1 -->
    <?php if ($product['image_column']) { ?>
    <td class="gp-tcell-1"><div style="<?php echo $img_col_w . $img_col_h; ?>">
      <div class="gp-box-img">
        <a href="<?php echo $product['image_column_popup']; ?>" title="" class="colorbox"><img src="<?php echo $product['image_column']; ?>" alt="" /></a>
      </div>
    </div></td>
    <?php } ?>
    <!-- Fine cella 1 //-->
    <!-- Inizio cella 2 -->
    <td class="gp-tcell-2">
      <div class="gp-box-name">
        <h2><?php if($product['details']){ ?><a href="<?php echo $product['details']; ?>" class="gp-details" title=""><?php echo $product['name']; ?></a><?php }else{ echo $product['name']; } ?></h2>
      </div>
      
      <?php if ($product['saving']) { ?>
      <div class="saving"><?php echo $product['saving']; ?></div>
      <?php } ?>
      <?php if ($product['rating']) { ?>
      <div class="rating"><img src="catalog/view/theme/<?php echo $tcg_template; ?>/image/stars-<?php echo $product['rating']; ?>.png" alt="" /></div>
      <?php } ?>
      
      <div class="gp-box-info">
        <span><?php echo $text_model; ?></span> <?php echo $product['model']; ?><br />
        <?php if ($product['sku']) { ?>
        <span><?php echo $text_sku; ?></span> <?php echo $product['sku']; ?><br />
        <?php } ?>
        <?php if ($product['manufacturer']) { ?>
        <span><?php echo $text_manufacturer; ?></span> <a href="<?php echo $product['manufacturers']; ?>"><?php echo $product['manufacturer']; ?></a><br />
        <?php } ?>
        <?php if ($product['reward']) { ?>
        <span><?php echo $text_reward; ?></span> <?php echo $product['reward']; ?><br />
        <?php } ?>
        <span><?php echo $text_stock; ?></span> <?php echo $product['stock']; ?><br />        
      </div>
    </td>
    <!-- Fine cella 2 //-->
    <!-- Inizio cella 3 -->
    <?php if ($gp_column_option) { ?>
    <td class="gp-tcell-3">
      <div class="gp-box-options">
      
      <?php if ($product['options']) { ?>
      <div class="options">
        <?php foreach ($product['options'] as $option) { ?>
        <?php if ($option['type'] == 'select') { ?>
        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['name']; ?>:</b><br />
          <select name="option[<?php echo $option['product_option_id']; ?>]">
            <option value=""><?php echo $text_select; ?></option>
            <?php foreach ($option['option_value'] as $option_value) { ?>
            <option value="<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
            <?php if ($option_value['price']) { ?>
            (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
            <?php } ?>
            </option>
            <?php } ?>
          </select>
        </div>
        <br />
        <?php } ?>
        <?php if ($option['type'] == 'radio') { ?>
        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['name']; ?>:</b><br />
          <?php foreach ($option['option_value'] as $option_value) { ?>
          <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" />
          <label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
            <?php if ($option_value['price']) { ?>
            (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
            <?php } ?>
          </label>
          <br />
          <?php } ?>
        </div>
        <br />
        <?php } ?>
        <?php if ($option['type'] == 'checkbox') { ?>
        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['name']; ?>:</b><br />
          <?php foreach ($option['option_value'] as $option_value) { ?>
          <input type="checkbox" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" />
          <label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
            <?php if ($option_value['price']) { ?>
            (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
            <?php } ?>
          </label>
          <br />
          <?php } ?>
        </div>
        <br />
        <?php } ?>
        <?php if ($option['type'] == 'image') { ?>
        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['name']; ?>:</b><br />
          <table class="option-image">
            <?php foreach ($option['option_value'] as $option_value) { ?>
            <tr>
              <td style="width: 1px;"><input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" /></td>
              <td><label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" /></label></td>
              <td><label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
                  <?php if ($option_value['price']) { ?>
                  (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                  <?php } ?>
                </label></td>
            </tr>
            <?php } ?>
          </table>
        </div>
        <br />
        <?php } ?>
        <?php if ($option['type'] == 'text') { ?>
        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['name']; ?>:</b><br />
          <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" />
        </div>
        <br />
        <?php } ?>
        <?php if ($option['type'] == 'textarea') { ?>
        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['name']; ?>:</b><br />
          <textarea name="option[<?php echo $option['product_option_id']; ?>]" cols="40" rows="5"><?php echo $option['option_value']; ?></textarea>
        </div>
        <br />
        <?php } ?>
        <?php if ($option['type'] == 'file') { ?>
        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['name']; ?>:</b><br />
          <input type="button" value="<?php echo $button_upload; ?>" id="button-option-<?php echo $option['product_option_id']; ?>" class="button">
          <input type="hidden" name="option[<?php echo $option['product_option_id']; ?>]" value="" />
        </div>
        <br />
        <?php } ?>
        <?php if ($option['type'] == 'date') { ?>
        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['name']; ?>:</b><br />
          <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="date" />
        </div>
        <br />
        <?php } ?>
        <?php if ($option['type'] == 'datetime') { ?>
        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['name']; ?>:</b><br />
          <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="datetime" />
        </div>
        <br />
        <?php } ?>
        <?php if ($option['type'] == 'time') { ?>
        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['name']; ?>:</b><br />
          <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="time" />
        </div>
        <br />
        <?php } ?>
        <?php } ?>
      </div>
      <?php } /*options*/ ?>
      
      </div>
    </td>
    <?php } ?>
    <!-- Fine cella 3 //-->
    <!-- Inizio cella 4 -->
    <td class="gp-tcell-4"><?php if ($tcg_config_customer_price) { ?>
      <div class="gp-box-price">
        <?php if ($product['rr_price']) { ?>
        <div class="rr-price"><?php echo $text_rrp; ?> <span><?php echo $product['rr_price']; ?></span></div>
        <?php } ?>
        <?php if (!$product['special']) { ?>
        <span class="price"><?php echo $product['price']; ?></span>
        <?php } else { ?>
        <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
        <?php } ?>
        <br />
        <?php if ($product['tax']) { ?>
        <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
        <br />
        <?php } ?>
        <?php if ($product['points']) { ?>
        <span class="reward"><small><?php echo $text_points; ?> <?php echo $product['points']; ?></small></span>
        <br />
        <?php } ?>
        <?php if ($product['discounts']) { ?>
        <div class="discount">
          <?php foreach ($product['discounts'] as $discount) { ?>
          <?php echo sprintf($text_discount, $discount['quantity'], $discount['price']); ?><br />
          <?php } ?>
        </div>
        <?php } ?>
      </div>
    <?php } ?></td>
    <!-- Fine cella 4 //-->
    <!-- Inizio cella 5 -->
    <td class="gp-tcell-5">
      <div class="gp-box-qty">
        <input type="hidden" id="sum-price<?php echo $product['product_id']; ?>" value="0" class="sum-price" />
        <input type="hidden" id="sum-taxable<?php echo $product['product_id']; ?>" value="0" class="sum-taxable" />
        <input type="hidden" id="sum-quantity<?php echo $product['product_id']; ?>" value="0" class="sum-quantity" />
        <?php $cqty=0;foreach($this->cart->getProducts() as $cgp)if($product['product_id'] == $cgp['product_id']){$cqty += $cgp['quantity'];} ?>
        
        <?php if (!$product['out_of_stock']) { ?>
          <?php if ($product['maximum']) { ?>
          <select name="quantity[<?php echo $product['product_id']; ?>]" class="updating">
            <option value="0">0</option>
            <?php for ($qx = $product['minimum']; $qx <= $product['maximum']; $qx++) { ?>
            <option value="<?php echo $qx; ?>"<?php if($cqty == $qx){ echo ' selected="selected"'; } ?>><?php echo $qx; ?></option>
            <?php } ?>
          </select>
          <?php } elseif (!$product['maximum']) { ?>
          <input type="text" name="quantity[<?php echo $product['product_id']; ?>]" value="<?php echo $cqty; ?>" class="updating" />
          <?php } ?>
          <script type="text/javascript"><!--
			$("[name=\"quantity[<?php echo $product['product_id']; ?>]\"]").on("keyup change", function(){qty = $(this).val();
				$("#sum-price<?php echo $product['product_id']; ?>").val(qty * <?php echo $product['price_value']; ?>);
				$("#sum-taxable<?php echo $product['product_id']; ?>").val(qty * <?php echo $product['price_value_ex_tax']; ?>);
				$("#sum-quantity<?php echo $product['product_id']; ?>").val(qty * 1);
			}).trigger("change");
          //--></script>
        <?php } elseif ($product['out_of_stock']) { ?>
        <input type="text" name="" value="0" class="disabled" readonly="readonly" title="<?php echo $button_cart_out; ?>" />
        <?php } ?>
      </div>
      
      <?php if ($product['minimum'] > 1) { ?>
      <span class="minimum"><?php echo $product['minimum_text']; ?></span>
      <?php } ?>
    </td>
    <!-- Fine cella 5 //-->
  </tr>
  <?php } ?>
  </tbody>
  <tfoot>
    <tr class="gp-tfoot">
      <?php if ($gp_column_image) { ?><td style="border:0;"></td><?php } ?>
      <?php if ($gp_column_option) { ?><td style="border:0;"></td><?php } ?>
      <td class="gp-tcell-1"><?php echo $text_total; ?>
        <div style="position:relative;"><div class="gp-loader"></div></div></td>
      <td class="gp-tcell-2"><span class="price" id="sum_total_price"></span><br />
        <?php if ($tax) { ?>
        <span class="price-tax" id="sum_total_taxable"></span>
        <?php } ?></td>
      <td class="gp-tcell-3"><span class="price" id="sum_total_quantity"></span></td>
    </tr>
  </tfoot>
</table>

        <!-- S after table -->
        <div class="gp-after-t">
          <?php if ($gp_discount) { ?>
          <div class="gp-discount-bundle"><?php echo $gp_discount; ?></div>
          <?php } ?>
          
          <div class="gp-actions">
            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
            <input type="button" value="<?php echo $button_cart; ?>" onclick="$('#form-bundle-addtocart').submit();" class="button" />
            <span class="gp-after-t-text-or"><?php echo $text_or; ?></span>
            <span class="gp-after-t-links"><a onclick="addToWishList('<?php echo $product_id; ?>');"><?php echo $button_wishlist; ?></a><br />
              <a onclick="addToCompare('<?php echo $product_id; ?>');"><?php echo $button_compare; ?></a></span>
          </div>
        </div>
        <!-- E after table -->

        <?php if ($review_status) { ?>
        <div class="review">
          <div><img src="catalog/view/theme/<?php echo $tcg_template; ?>/image/stars-<?php echo $rating; ?>.png" alt="<?php echo $reviews; ?>" />&nbsp;&nbsp;<a onclick="$('a[href=\'#tab-review\']').trigger('click');"><?php echo $reviews; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('a[href=\'#tab-review\']').trigger('click');"><?php echo $text_write; ?></a></div>
          <div class="share"><!-- AddThis Button BEGIN -->
            <div class="addthis_default_style"><a class="addthis_button_compact"><?php echo $text_share; ?></a> <a class="addthis_button_email"></a><a class="addthis_button_print"></a> <a class="addthis_button_facebook"></a> <a class="addthis_button_twitter"></a></div>
            <script type="text/javascript" src="//s7.addthis.com/js/250/addthis_widget.js"></script> 
            <!-- AddThis Button END --> 
          </div>
        </div>
        <?php } ?>
        
      </form>
      <?php } else { ?>
      <div style="text-align:center;"> No Products found! </div>
      <?php } ?>
    </div><!-- right-grouped-product //-->
    <div style="clear:both;"></div>
  </div><!-- grouped-product-product-info //-->
<!-- End Grouped Product powered by www.fabiom7.com - Italy //-->
  
  
  <!--<div class="product-info">
    <div class="right">
    </div>
  </div>-->
  <div id="tabs" class="htabs"><a href="#tab-description"><?php echo $tab_description; ?></a>
    <?php if ($attribute_groups) { ?>
    <a href="#tab-attribute"><?php echo $tab_attribute; ?></a>
    <?php } ?>
    <?php if ($review_status) { ?>
    <a href="#tab-review"><?php echo $tab_review; ?></a>
    <?php } ?>
    <?php if ($products) { ?>
    <a href="#tab-related"><?php echo $tab_related; ?> (<?php echo count($products); ?>)</a>
    <?php } ?>
  </div>
  <div id="tab-description" class="tab-content"><?php echo $description; ?></div>
  <?php if ($attribute_groups) { ?>
  <div id="tab-attribute" class="tab-content">
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
  <?php if ($review_status) { ?>
  <div id="tab-review" class="tab-content">
    <div id="review"></div>
    <h2 id="review-title"><?php echo $text_write; ?></h2>
    
    <!-- Start Grouped Product powered by www.fabiom7.com -->
    <?php if ($product_grouped && $this->config->get('use_individual_review')) { ?>
    <b><?php echo $text_review_for; ?></b><br />
    <select id="switch_review_for">
      <option value="0"><?php echo $text_review_all; ?></option>
      <?php foreach ($product_grouped as $product) { ?>
      <option value="<?php echo $product['product_id'] ?>"><?php echo $product['name']; ?></option>
      <?php } ?>
    </select><br /><br />
    <?php } else { ?>
    <input type="hidden" id="switch_review_for" value="0" />
    <?php } ?>
    <!-- End Grouped Product powered by www.fabiom7.com -->
    
    <b><?php echo $entry_name; ?></b><br />
    <input type="text" name="name" value="" />
    <br />
    <br />
    <b><?php echo $entry_review; ?></b>
    <textarea name="text" cols="40" rows="8" style="width: 98%;"></textarea>
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
      <div class="right"><a id="button-review" class="button"><?php echo $button_continue; ?></a></div>
    </div>
  </div>
  <?php } ?>
  <?php if ($products) { ?>
  <div id="tab-related" class="tab-content">
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
          <?php echo $product['price']; ?>
          <?php } else { ?>
          <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
          <?php } ?>
        </div>
        <?php } ?>
        <?php if ($product['rating']) { ?>
        <div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /></div>
        <?php } ?>
        <a onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button"><?php echo $button_cart; ?></a></div>
      <?php } ?>
    </div>
  </div>
  <?php } ?>
  <?php if ($tags) { ?>
  <div class="tags"><b><?php echo $text_tags; ?></b>
    <?php for ($i = 0; $i < count($tags); $i++) { ?>
    <?php if ($i < (count($tags) - 1)) { ?>
    <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>,
    <?php } else { ?>
    <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>
    <?php } ?>
    <?php } ?>
  </div>
  <?php } ?>
  <?php echo $content_bottom; ?></div>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.colorbox').colorbox({
		overlayClose: true,
		opacity: 0.5,
		rel: "colorbox"
	});
});
//--></script> 
<?php if ($options) { ?>
<script type="text/javascript" src="catalog/view/javascript/jquery/ajaxupload.js"></script>
<?php foreach ($options as $option) { ?>
<?php if ($option['type'] == 'file') { ?>
<script type="text/javascript"><!--
new AjaxUpload("#button-option-<?php echo $option['product_option_id']; ?>", {
	action: 'index.php?route=product/product/upload',
	name: 'file',
	autoSubmit: true,
	responseType: 'json',
	onSubmit: function(file, extension) {
		$("#button-option-<?php echo $option['product_option_id']; ?>").after('<img src="catalog/view/theme/default/image/loading.gif" class="loading" style="padding-left: 5px;" />');
		$("#button-option-<?php echo $option['product_option_id']; ?>").attr("disabled", true);
	},
	onComplete: function(file, json) {
		$("#button-option-<?php echo $option['product_option_id']; ?>").attr("disabled", false);
		
		$('.error').remove();
		
		if (json['success']) {
			alert(json['success']);
			$("input[name=\"option[<?php echo $option['product_option_id']; ?>]\"]").attr("value", json['file']);
		}
		
		if (json['error']) {
			$("#option-<?php echo $option['product_option_id']; ?>").after('<span class="error">' + json['error'] + '</span>');
		}
		
		$('.loading').remove();	
	}
});
//--></script>
<?php } ?>
<?php } ?>
<?php } ?>
<script type="text/javascript"><!--
$('#review .pagination a').live('click', function() {
	$('#review').fadeOut('slow');
	$('#review').load(this.href);
	$('#review').fadeIn('slow');
	return false;
});			

$('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');

// Grouped Product new function
$('#button-review').bind('click', function() {
	$.ajax({
		url: 'index.php?route=product/product_bundle/write&product_id=<?php echo $product_id; ?>&grouped_id='+$('#switch_review_for').val(),
		type: 'post',
		dataType: 'json',
		data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : '') + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#button-review').attr('disabled', true);
			$('#review-title').after('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
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

<!-- Grouped Product New script //-->
<script type="text/javascript"><!--
$(document).ready(function(){
	$("#gp-table tbody tr").addClass("gp-tcell-state-default").on({
		mouseover:function(){$(this).addClass("gp-tcell-state-hover").removeClass("gp-tcell-state-normal");},
		mouseout:function(){$(this).removeClass("gp-tcell-state-hover").addClass("gp-tcell-state-normal");}
	});
	$(".gp-details").colorbox({overlayClose:true,opacity:0.5,rel:"gp-details"});
	$(".gp-toggle").click(function(){$(".gp-box-info,.discount").slideToggle("slow");$(".gp-toggle-meno,.gp-toggle-piu").toggle(0);});
});
//--></script>

<?php if($product_grouped && $use_image_replace){$gp_imgr_data='';foreach($product_grouped as $product){$gp_imgr_data .= '<span id="gp-imgr'.$product['product_id'].'" style="display:none;"><img src="'.$product['image_replace'].'" alt="" /></span>';} ?><script type="text/javascript"><!--
$(document).ready(function(){$("a[href=\"<?php echo $popup; ?>\"]").wrap("<span class=\"gp-imgr-default\"></span>");$(".gp-imgr-default").after('<?php echo $gp_imgr_data; ?>');$(".gp-tbody").mouseover(function(){$(".gp-imgr-default").hide();$("#"+$(this).attr("id").replace("gp-tbody","gp-imgr")).show()}).mouseout(function(){$(".gp-imgr-default").show();$("#"+$(this).attr("id").replace("gp-tbody","gp-imgr")).hide()});}); //--></script><?php } ?>

<script type="text/javascript"><!--
$(".updating").on("keyup change",function(){
	var sumTotalPrice=0; var sumTotalTaxable=0; var sumTotalQuantity=0;
	$(".sum-quantity").each(function(){sumTotalQuantity+= 1* $(this).val();}); $("#sum_total_quantity").text(sumTotalQuantity);
	$(".sum-price").each(function(){sumTotalPrice+= 1* $(this).val();});
	$(".sum-taxable").each(function(){sumTotalTaxable+= 1* $(this).val();});
	$.ajax({
		url:'index.php?route=product/product_bundle/updateSumPrice',type:'post',dataType:'json',
		data:{"bundle_price_sum":sumTotalPrice,"bundle_price_sum_ex_tax":sumTotalTaxable},
		beforeSend: function(){$(".gp-loader").show();},
		complete: function(){$(".gp-loader").hide();},
		success: function(json){
			if(json['text_sum_price']){$("#sum_total_price").html(json['text_sum_price']);}
			if(json['text_sum_price_ex_tax']){$("#sum_total_taxable").html("<?php echo $text_tax; ?> " + json['text_sum_price_ex_tax']);}
		}
	});
}).trigger('keyup');
//--></script>
<?php echo $footer; ?>