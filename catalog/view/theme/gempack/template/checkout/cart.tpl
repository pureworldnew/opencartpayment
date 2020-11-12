<?php echo $header; ?>
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
<?php if ($attention) { ?>
            <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $attention; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div>
<?php } ?>
<?php if ($success) { ?>
            <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div>
<?php } ?>
<?php if ($error_warning) { ?>
            <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div>
<?php } ?>
            <h1><?php echo $heading_title; ?><?php if ($weight) { ?> (<?php echo $weight; ?>)<?php } ?></h1>
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" id="lowest-cart-id" value="<?php echo $lowest_cart_id; ?>">
                <div class="table-responsive" id="cart-details"> 
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td class="text-center" style="width:150px"><?php echo $column_image; ?></td>
                                <td class="text-left"><?php echo $column_name; ?></td>
                                <td class="text-center"><?php echo $column_model; ?></td>
                                <td class="text-center" style="width:200px"><?php echo $column_quantity; ?></td>
                                <td class="text-right"><?php echo $column_price; ?></td>
                                <td class="text-right"><?php echo $column_total; ?></td>
                            </tr>
                        </thead>
                        <tbody id="cart-data">
<?php foreach ($products as $product) { ?>
                            <tr class="cart-id" id="<?php echo $product['cart_id']; ?>">
                                <td class="text-center"><?php if ($product['thumb']) { ?><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-thumbnail" /></a><?php } ?></td>
                                <td class="text-left">
                                    <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a><?php if (!$product['stock']) { ?> <span class="text-danger">***</span><?php } ?>
<?php foreach ($product['option'] as $option) { ?>
<?php  $option_name = (explode(" ",$option['name'])); 
$option_unit_name = trim(end($option_name)); ?>
<?php if($option_unit_name == 'units') {
 $option['name'] = $option_unit_name;
} ?>
                                    <br />- <small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
<?php } ?>
<?php if($product['unit'] && $product['unit']['convert_price'] !=1) { ?>
                                    <br />- <small style="color:#DD0205; font-weight:bold;"><?php echo number_format(($product['unit']['convert_price'] * $product['quantity']),2); ?> <?php echo $product['unit_dates_default']['name']; ?> = <?php echo $product['quantity']; ?> <?php echo $product['unit']['unit_value_name']; ?></small>
<?php } ?>
<?php if ($product['reward']) { ?>
                                    <br /><small><?php echo $product['reward']; ?></small>
<?php } ?>
<?php if ($product['recurring']) { ?>
                                    <br /><span class="label label-info"><?php echo $text_recurring_item; ?></span> <small><?php echo $product['recurring']; ?></small>
<?php } ?>
                                </td>
                                <td class="text-center"><?php echo $product['model']; ?></td>
                                <td class="text-left">
                                    <div class="input-group btn-block" style="max-width: 200px;">
                                        <input type="text" name="quantity[<?php echo $product['cart_id']; ?>]" value="<?php echo $product['quantity']; ?>" size="1" class="form-control">
                                        <span class="input-group-btn">
                                            <button type="submit" data-toggle="tooltip" title="<?php echo $button_update; ?>" class="btn btn-primary" data-original-title="<?php echo $button_update; ?>"><i class="fa fa-refresh"></i></button>
                                            <button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger" onclick="cart.remove('<?php echo $product['cart_id']; ?>');" data-original-title="<?php echo $button_remove; ?>"><i class="fa fa-times-circle"></i></button>
                                        </span>
                                    </div>
                                    <br />
<?php if (!empty($product['unit_dates'])) { ?>
                                    <div class="ig_MetalType ig_Units units_grouped">
                                        <select class="get-unit-data id_convert_long" id="<?php echo $product['key']; ?>" name="get-unit-data[<?php echo $product['key']; ?>]">
<?php foreach ($product['unit_dates'] as $unit_data) { ?>
<?php   
if( isset($product['unit']['unit_conversion_values']) && $product['unit']['unit_conversion_values'] == $unit_data['unit_conversion_product_id'] ) {
    $checked = 'selected';
}else{
    $checked = '';
}
if( isset($product['unit']['unit_conversion_values']) )
{
    $new_value = str_replace ($product['unit']['unit_conversion_values'] , $unit_data['unit_conversion_product_id'] , $product['key']);
}
?>
                                            <option  name="<?php echo $unit_data['name']; ?>" data-value ="<?php echo $unit_data['unit_conversion_product_id']; ?>" value="<?php echo $unit_data['unit_conversion_product_id']; ?>" <?php echo $checked; ?>><?php echo $unit_data['name']; ?></option>
<?php } ?>
                                        </select>
                                    </div>
<?php } ?>
                                </td>
                                <td class="text-right">
                                    <strong><?php echo $product['price']; ?></strong>
<?php if($product['unit']) { ?>
                                    <br /><small> per <?php echo $product['DefaultUnitName']['unit_singular']; ?> </small>
<?php } else { ?>
                                    <br /><small> per <?php echo $product['DefaultUnitName']['unit_singular']; ?> </small>
<?php } ?>
                                </td>
                                <td class="text-right"><?php echo $product['total']; ?></td>
                            </tr>
                            <tr>
                                <td colspan="6"><label><input type="checkbox" class="select-for-remove" value="<?php echo $product['cart_id']; ?>"> Select</label></td>
                            </tr>
<?php } ?>
<?php foreach ($vouchers as $vouchers) { ?>
                            <tr>
                                <td></td>
                                <td class="text-left"><?php echo $vouchers['description']; ?></td>
                                <td class="text-left"></td>
                                <td class="text-left">
                                    <div class="input-group btn-block" style="max-width: 200px;">
                                        <input type="text" name="" value="1" size="1" disabled="disabled" class="form-control" />
                                        <span class="input-group-btn">
                                            <button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger" onclick="voucher.remove('<?php echo $vouchers['key']; ?>');"><i class="fa fa-times-circle"></i></button>
                                        </span>
                                    </div>
                                </td>
                                <td class="text-right"><?php echo $vouchers['amount']; ?></td>
                                <td class="text-right"><?php echo $vouchers['amount']; ?></td>
                            </tr>
<?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6"><button id="cart-delete" class="btn btn-danger" onclick="removeSelectedFromCart();">Delete Selected</button></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </form>
            <div class="ajax-load text-center" style="display:none;">
                <p id="load_more_button"><button class="btn btn-primary" onclick="loadAjaxData();">Load More</button></p>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
<?php foreach ($totals as $total) { ?>
                        <tr>
                            <td class="text-right"><strong><?php echo $total['title']; ?>:</strong></td>
                            <td class="text-right"><?php echo $total['text']; ?></td>
                        </tr>
<?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="buttons text-center">
                <a href="<?php echo $checkout; ?>" class="btn btn-lg btn-block"><?php echo $button_checkout; ?></a>
            </div>
            <hr />
<?php if ($coupon || $voucher || $reward || $shipping) { ?>
            <h2><?php echo $text_next; ?></h2>
            <div class="panel-group" id="accordion"><?php echo $voucher; ?><?php echo $reward; ?><?php echo $shipping; ?></div>
<?php } ?>
            <?php echo $content_bottom; ?>
        </div>
        <?php echo $column_right; ?>
    </div>
<?php echo $footer; ?>
<script type="text/javascript">
    var lowest_cart_id  = $("#lowest-cart-id").val();
    var last_cart_id    = $(".cart-id:last").attr("id");
    if( lowest_cart_id > 0 && lowest_cart_id < last_cart_id )
    {
      $('.ajax-load').show();
    }

    function loadAjaxData()
    {
      var last_cart_id = $(".cart-id:last").attr("id");
      loadMoreData(last_cart_id);
    }

    function loadMoreData(last_cart_id){
      
          $.ajax({
                    url: 'index.php?route=checkout/cart/loadMoreData',
                    type: 'post',
                    data: { 'last_cart_id': last_cart_id},
                    dataType: 'html',
                    beforeSend: function()
                    {
                      var html = '<button class="btn btn-primary"><i class="fa fa-spinner fa-spin" style="font-size:24px"></i></button>';
                      $("#load_more_button").html(html);
                    },
                    success: function(html) {
                      $('.ajax-load').hide();
                      $("#cart-data").append(html);
                      var last_cart_id = $(".cart-id:last").attr("id");
                      var lowest_cart_id  = $("#lowest-cart-id").val();
                      if( lowest_cart_id > 0 && lowest_cart_id < last_cart_id )
                      {
                        var html = '<button class="btn btn-primary" onclick="loadAjaxData();">Load More</button>';
                        $("#load_more_button").html(html);
                        $('.ajax-load').show();
                      }
                    }
                });
    }
</script>
