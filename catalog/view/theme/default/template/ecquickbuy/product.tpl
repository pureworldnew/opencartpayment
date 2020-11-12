<?php $revision=20; ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/stylesheet.css?v=<?php echo $revision;?>" />
<?php foreach ($styles as $style) { ?>
<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>?v=<?php echo $revision;?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script type="text/javascript" src="catalog/view/javascript/jquery/jquery-1.7.1.min.js?v=<?php echo $revision;?>"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-1.8.16.custom.min.js?v=<?php echo $revision;?>"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css?v=<?php echo $revision;?>" />
<script type="text/javascript" src="catalog/view/javascript/common.js?v=<?php echo $revision;?>"></script>
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>?v=<?php echo $revision;?>"></script>
<?php } ?>
<!--[if IE 7]> 
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/ie7.css" />
<![endif]-->
<!--[if lt IE 7]>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/ie6.css" />
<script type="text/javascript" src="catalog/view/javascript/DD_belatedPNG_0.0.8a-min.js"></script>
<script type="text/javascript">
DD_belatedPNG.fix('#logo img');
</script>
<![endif]-->

<script type="text/javascript"><!--

function addToWishList(product_id) {
  $.ajax({
  url: 'index.php?route=account/wishlist/add',
  type: 'post',
  data: 'product_id=' + product_id,
  dataType: 'json',
  success: function(json) {
    $('.success, .warning, .attention, .information').remove();
      
    if (json['success']) {
    $('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
    $('#notification').find('a').each(function(){
      $(this).attr('target','_PARENT');
    });
    $('.success').fadeIn('slow');
    
    $('#wishlist-total').html(json['total']);
    
    $('html, body').animate({ scrollTop: 0 }, 'slow');
    } 
  }
  });
}

function addToCompare(product_id) {
  $.ajax({
  url: 'index.php?route=product/compare/add',
  type: 'post',
  data: 'product_id=' + product_id,
  dataType: 'json',
  success: function(json) {
    $('.success, .warning, .attention, .information').remove();
      
    if (json['success']) {
    $('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
    $('#notification').find('a').each(function(){
      $(this).attr('target','_PARENT');
    });
    $('.success').fadeIn('slow');
    
    $('#compare-total').html(json['total']);
    
    $('html, body').animate({ scrollTop: 0 }, 'slow'); 
    } 
  }
  });
}
//--></script>
<div id="notification"></div>
<div id="content" class="ecquickbuy"><?php echo $content_top; ?>
  <h1><?php echo $heading_title; ?></h1>
  <div class="product-info">
  <?php if ($thumb || $images) { ?>
  <div class="left">
    <?php if ($thumb) { ?>
    <div class="image">
    <?php if( $special )  { ?>
      <div class="product-label-special label"><?php echo $this->language->get( 'text_sale' ); ?></div>
    <?php } ?>
      <a href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>" class="colorbox">
      <img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" id="image"  data-zoom-image="<?php echo $popup; ?>" class="product-image-zoom"/></a></div>
    <?php } ?>
    <?php if ($images) { ?>
    <div id="image-additional" class="image-additional slide">
    <?php foreach ($images as $image) { ?>
     <a href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>" class="colorbox" data-zoom-image="<?php echo $image['popup']; ?>" data-image="<?php echo $image['popup']; ?>">
        <img src="<?php echo $image['thumb']; ?>" style="max-width:<?php echo $this->config->get('config_image_additional_width');?>px"  title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" data-zoom-image="<?php echo $image['popup']; ?>" class="product-image-zoom" />
        </a>
    <?php } ?>
    </div>
    <?php } ?>
  </div>
  <?php } ?>
  <div class="right">
    <div class="description">
    <?php if ($manufacturer) { ?>
    <span><?php echo $text_manufacturer; ?></span> <a href="<?php echo $manufacturers; ?>" target="_parent"><?php echo $manufacturer; ?></a><br />
    <?php } ?>
    <span><?php echo $text_model; ?></span> <?php echo $model; ?><br />
    <?php if ($reward) { ?>
    <span><?php echo $text_reward; ?></span> <?php echo $reward; ?><br />
    <?php } ?>
    <span><?php echo $text_stock; ?></span> <?php echo $stock; ?></div>
    <?php if ($price) { ?>
    <div class="price"><?php echo $text_price; ?>
    <?php if (!$special) { ?>
    <?php echo $price; ?>
    <?php } else { ?>
    <span class="price-old"><?php echo $price; ?></span> <span class="price-new"><?php echo $special; ?></span>
    <?php } ?>
    <br />
    <?php if ($tax) { ?>
    <span class="price-tax"><?php echo $text_tax; ?> <?php echo $tax; ?></span><br />
    <?php } ?>
    <?php if ($points) { ?>
    <span class="reward"><small><?php echo $text_points; ?> <?php echo $points; ?></small></span><br />
    <?php } ?>
    <?php if ($discounts) { ?>
    <br />
    <div class="discount">
      <?php foreach ($discounts as $discount) { ?>
      <?php echo sprintf($text_discount, $discount['quantity'], $discount['price']); ?><br />
      <?php } ?>
    </div>
    <?php } ?>
    </div>
    <?php } ?>
    <?php if ($options) { ?>
    <div class="options">
    <h2><?php echo $text_option; ?></h2>
    <br />
    <?php foreach ($options as $option) { ?>
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
    <?php } ?>
    <div class="cart">
    <div><?php echo $text_qty; ?>
      <input type="text" name="quantity" size="2" value="<?php echo $minimum; ?>" />
      <input type="hidden" name="product_id" size="2" value="<?php echo $product_id; ?>" />
      &nbsp;
      <input type="button" value="<?php echo $button_cart; ?>" id="button-cart" class="button" />
      <span>&nbsp;&nbsp;<?php echo $text_or; ?>&nbsp;&nbsp;</span>
      <span class="links"><a onclick="addToWishList('<?php echo $product_id; ?>');"><?php echo $button_wishlist; ?></a><br />
      <a onclick="addToCompare('<?php echo $product_id; ?>');"><?php echo $button_compare; ?></a></span>
    </div>
    <?php if ($minimum > 1) { ?>
    <div class="minimum"><?php echo $text_minimum; ?></div>
    <?php } ?>
    </div>
    <?php if ($review_status) { ?>
    <div class="review">
    <div><img src="catalog/view/theme/default/image/stars-<?php echo $rating; ?>.png" alt="<?php echo $reviews; ?>" />&nbsp;&nbsp;<a href="javascript:;"><?php echo $reviews; ?></a></div>
    <div class="share"><!-- AddThis Button BEGIN -->
      <div class="addthis_default_style"><a class="addthis_button_compact"><?php echo $text_share; ?></a> <a class="addthis_button_email"></a><a class="addthis_button_print"></a> <a class="addthis_button_facebook"></a> <a class="addthis_button_twitter"></a></div>
      <script type="text/javascript" src="//s7.addthis.com/js/250/addthis_widget.js"></script> 
      <!-- AddThis Button END --> 
    </div>
    </div>
    <?php } ?>
  </div>
  </div>
  <div id="tabs" class="htabs"><a href="#tab-description"><?php echo $tab_description; ?></a>
  <?php if ($attribute_groups) { ?>
  <a href="#tab-attribute"><?php echo $tab_attribute; ?></a>
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
  <?php if ($tags) { ?>
  <div class="tags"><b><?php echo $text_tags; ?></b>
  <?php for ($i = 0; $i < count($tags); $i++) { ?>
  <?php if ($i < (count($tags) - 1)) { ?>
  <a href="<?php echo $tags[$i]['href']; ?>" target="_parent"><?php echo $tags[$i]['tag']; ?></a>,
  <?php } else { ?>
  <a href="<?php echo $tags[$i]['href']; ?>" target="_parent"><?php echo $tags[$i]['tag']; ?></a>
  <?php } ?>
  <?php } ?>
  </div>
  <?php } ?>
  <?php echo $content_bottom; ?></div>
<script type="text/javascript"><!--
  $("#image").elevateZoom({gallery:'image-additional', cursor: 'pointer', galleryActiveClass: 'active'}); 
//--></script>
<script type="text/javascript"><!--
$('#button-cart').bind('click', function() {
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
      }
      if (json['success']) {
    window.parent.$.colorbox.close();
    window.parent.document.getElementById('cart-total').innerHTML = json['total'];
    window.parent.document.getElementById('notification').innerHTML='<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>';
    $('.success', window.parent.document).fadeIn('slow');
    $('#cart-total').html(json['total']);
    $('html, body', window.parent.document).animate({ scrollTop: 0 }, 'slow'); 
      }
    }
  });
});
//--></script>

<?php if ($options) { ?>
<script type="text/javascript" src="catalog/view/javascript/jquery/ajaxupload.js"></script>
<?php foreach ($options as $option) { ?>
<?php if ($option['type'] == 'file') { ?>
<script type="text/javascript"><!--
new AjaxUpload('#button-option-<?php echo $option['product_option_id']; ?>', {
  action: 'index.php?route=product/product/upload',
  name: 'file',
  autoSubmit: true,
  responseType: 'json',
  onSubmit: function(file, extension) {
    $('#button-option-<?php echo $option['product_option_id']; ?>').after('<img src="catalog/view/theme/default/image/loading.gif" class="loading" style="padding-left: 5px;" />');
    $('#button-option-<?php echo $option['product_option_id']; ?>').attr('disabled', true);
  },
  onComplete: function(file, json) {
    $('#button-option-<?php echo $option['product_option_id']; ?>').attr('disabled', false);
    
    $('.error').remove();
    
    if (json['success']) {
      alert(json['success']);
      
      $('input[name=\'option[<?php echo $option['product_option_id']; ?>]\']').attr('value', json['file']);
    }
    
    if (json['error']) {
      $('#option-<?php echo $option['product_option_id']; ?>').after('<span class="error">' + json['error'] + '</span>');
    }
    
    $('.loading').remove(); 
  }
});
//--></script>
<?php } ?>
<?php } ?>
<?php } ?>
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
<?php echo $footer; ?>