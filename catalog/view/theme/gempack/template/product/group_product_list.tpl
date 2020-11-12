<?php echo $header; ?> 
<script type="application/ld+json">
{
  "@context": "http://schema.org/", 
  "@type": "BreadcrumbList", 
  "itemListElement": [
  <?php 
    $i = 1; 
    $keys = array_keys($breadcrumbs);
    $num_keys = count($keys);   ?>
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    { 
      "@type": "ListItem", 
      "position": "<?php echo $i; ?>", 
      "item": { 
        "@id": "<?php echo $breadcrumb['href']; ?>", 
        "name": "<?php echo $breadcrumb['href']; ?>",
        "image": "" 
      } 
    }
    <?php 
    if ($i < $num_keys) //we have next element
    {
        echo ","; //echo last comma for work with JSON-LD
    }
    $i++;
    ?>
  <?php } ?>
  ]
}
</script>
<div class="row">
    <?php echo str_replace('class="col-lg-2 col-md-3 col-sm-4 hidden-xs" id="column-left"','class="col-lg-2 col-md-3 col-sm-4 hidden-sm hidden-xs" id="column-left"', $column_left); ?>
    <?php $class="col-md-offset-1 col-lg-10 col-md-10 col-sm-12 col-xs-12"; ?>
    <div class="<?php echo $class; ?>" id="content">
        <ul class="breadcrumb">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } ?>
        </ul>
        <h1><?php echo $heading_title; ?></h1>
        <?php if ($products) { ?>
            <div class="row product-filters">
                <div class="col-md-4 col-sm-4 col-xs-6 hidden-lg">
                    <div class="form-group input-group input-group-sm">
                        <label class="input-group-addon" for="input-sort"><?php echo $text_sort; ?></label>
                        <select class="form-control" onchange="location = this.value;">
                            <option value="" selected>Sort by...</option>
                            <?php foreach ($sorts as $item) { ?>
                                <a href="<?php echo $item['href']; ?>" class="btn btn-default"><?php echo $item['text']; ?></a>
                                <option value="<?php echo $item['href']; ?>"><?php echo $item['text']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
				<div class="col-lg-10 hidden-md hidden-sm hidden-xs">
					<strong><?php echo $text_sort; ?></strong>
					<div class="btn-group" id="sort-by-buttons">
						<?php foreach ($sorts as $item) { ?>
						    <a href="<?php echo $item['href']; ?>" class="btn btn-default"><?php echo $item['text']; ?></a>
						<?php } ?>
					</div>
				</div>
                <div class="col-lg-2 col-md-4 col-sm-4 col-xs-6">
                    <div class="form-group input-group input-group-sm">
                        <label class="input-group-addon" for="input-limit">Show:</label>
                        <select class="form-control" onchange="location = this.value;">
                            <?php foreach ($limits as $limi) { ?>
                                <?php if ($limi['value'] == $limit) { ?>
                                    <option value="<?php echo $limi['href']; ?>" selected="selected"><?php echo $limi['text']; ?></option>
                                <?php } else { ?>
                                    <option value="<?php echo $limi['href']; ?>"><?php echo $limi['text']; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <hr />
            <div class="row">
              <div class="col-sm-7 text-left"><?php echo $pagination; ?></div>
              <div class="col-sm-5 text-right"><?php echo $results; ?></div>
            </div>
            <hr />
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <?php foreach ($products as $product) { ?>
                        <div class="product-list-item">
                            <div class="row">
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <?php if ($product['thumb']) { ?>
                                        <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
                                    <?php } else { ?>
                                        <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo HTTP_SERVER; ?>catalog/view/theme/default/image/no-image.gif" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" class="img-responsive"  /></a></div>
                                    <?php } ?>
                                </div>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <div class="namefull">
                                        <h5><strong>
                                        <a href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>"><?php echo $product['name']; ?></a>
                                        </strong></h5>
                                    </div>
                                    <div class="col-md-7 col-sm-12 col-xs-12">
                                        <div class="description catee-des">
                                            <table class="table table-hover">
                                                <tbody>
                                                    <?php foreach($product['options'] as $key => $pro_attr){ ?>
                                                        <tr>
                                                            <td><?php echo $key; ?>:</td>
                                                            <td><?php echo $pro_attr; ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-5 col-sm-12 col-xs-12">
                                        <p style="display: inline-block;">
                                            <?php if ($product['quantity'] <= 0) {
                                                if ($product['stock'] == '2-3 Days') : ?><span class='two_three_days'></span>
                                                    <?php elseif($product['stock'] == 'Pre-Order') : ?><span class='pre_order'></span>
                                                    <?php elseif($product['stock'] == 'In Stock') : ?><span class='inofstock'></span>
                                                    <?php else : ?><span class='outofstock'></span>
                                                <?php endif;
                                            } else { ?>
                                                <span class='inofstock'></span>
                                            <?php } ?>
                                        </p>
                                        <p><b>Item Number: <?php echo $product['model']; ?></b></p>
                                        <?php if ($product['price']) { ?>
                                            <p class="price">
                                                <?php if (!$product['special']) { ?>
                                                    <span id="new_price" style="color: #ff4040; font-size: 18px;"><?php echo $product['price']; ?></span><span class="unit" style="color: #ff4040; font-size: 18px;"> /</span> <span id="quantity_span" style="color: #ff4040; font-size: 18px;"><?php echo number_format($product['minimum'], 2); ?></span>&nbsp;<span id="unit_dis"  class="unit-products" style="color: #ff4040; font-size: 18px;"><?php echo $unit_singular; ?></span>
                                                <?php } else { ?>
                                                    <span id="new_price" style="text-decoration: line-through; font-size: 18px;"><?php echo $price; ?></span> <strong><?php echo $product['special']; ?><span class="unit"> / </span> <span id="quantity_span"> <?php echo number_format($product['minimum'], 2); ?></span> &nbsp; <span id="unit_dis"  class="unit-products"><?php echo $unit_singular; ?></span></strong>
                                                <?php } ?>
                                                <?php if ($product['tax']) { ?>
                                                    <span id="new_price" class="unit-products"> <?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
                                                <?php } ?>
                                            </p>
                                        <?php } ?>
                                        <p class="bulk-product-tex">
                                            <?php if ($product['discounts'] && $logged || $logged) { ?>
                                                <a style="cursor: pointer;" data-toggle="modal" data-target="#seeBulkPrice<?php echo $product['product_id'] ?>"><i class="fa fa-plus-square"></i> See Bulk Price</a>
                                            <?php } else { ?>
                                                <a id="bulkcontent<?php echo $product['product_id'] ?>" style="cursor: pointer;" data-toggle="popover" data-placement="bottom"><i class="fa fa-plus-square"></i> See Bulk Price</a></span>
                                                <div id="content-bulk-<?php echo $product['product_id'] ?>" class="hidden">
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
                                                            return $("#content-bulk-<?php echo $product['product_id'] ?>").html();
                                                        }
                                                    };
                                                    $(function(){
                                                        $('#bulkcontent<?php echo $product['product_id'] ?>').popover(ops);
                                                    });
                                                </script>
                                            <?php } ?>
                                        </p>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
                                            <button type="button" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-bar-chart"></i></button>
                                        </div>
                                        <br /><br />
                                        <div class="btn-group" style="display: block;">
                                            <a href="<?php echo $product['href']; ?>"><button type="button" class="btn btn-default" style="Width: 100%; font-size: 18px;">Select</button></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr />
                        </div>
                        <div id="seeBulkPrice<?php echo $product['product_id'] ?>" style="display: none;" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Bulk Prices</h4>
                                    </div>
                                    <div class="modal-body">
                                        <?php if ($logged) { ?>   
                                            <?php if ($product['discounts']) { ?>
                                                <p class="he group_he">Bulk Pricing and Quantity Discounts</p>
                                                <p class="mid-group">
                                                    <span class="flt">Quantity</span>
                                                    <span class="flr scale-group">Price</span>
                                                </p>
                                                <ul class="update_discount_price_group">
                                                    <li>
                                                        <span class="scale-quantity"><?php echo "Non-Wholesale"; ?></span>
                                                        <span style="text-align: right !important;float: right;" class="scale-price"><?php echo $product['price_without_discount']; ?></span>
                                                    </li>
                                                    <?php foreach ($product['discounts'] as $key => $discount) {
                                                        if ($key == 0) {
                                                            $nextArray = current($product['discounts']);
                                                        } else {
                                                            $nextArray = next($product['discounts']);
                                                        }
                                                        if (!empty($nextArray)) {
                                                            $nextQuan = $nextArray['quantity'];
                                                            $nextQuan--;
                                                            $nextQuan = " - " . $nextQuan;
                                                        } else {
                                                            $nextQuan = "+";
                                                        } ?>
                                                        <li>
                                                            <span class="scale-quantity"><?php echo $discount['quantity'] . $nextQuan . " " . $product['unit_plural']; ?></span>
                                                            <span style="text-align: right !important;float: right;" class="scale-price"><?php echo $discount['price']; ?></span>
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
                    <?php } ?>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 options">
                    <?php if(!empty($text_groupby)){ ?>
                        <div class="panel panel-default panel-default-options">
                            <div class="panel-heading">
                                <h4 class="panel-title"><a data-toggle="collapse" href="#option-<?php echo str_replace(' ', '-', strtolower($text_groupby)); ?>"><?php echo $text_groupby; ?><i class="fa fa-caret-down pull-right"></i></a></h4>
                            </div>
                            <div id="option-<?php echo str_replace(' ', '-', strtolower($text_groupby)); ?>" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <span id="select_product_name" style="display: none;"><?php echo $text_groupby; ?></span>
                                    <select name="select_product" id="select_product" class='form-control optionselectbox'>
                                        <option value="">-- Please Select --</option>
                                        <?php foreach ($product_grouped as $product) { ?>
                                            <option <?php if($product['is_requested_product']) { echo "selected='selected'"; } ?> value="<?php echo $product['product_id']; ?>"><?php echo trim($product['product_name']); ?> </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    <?php } ?>    
                    <?php if (isset($options)) { ?>
                        <?php foreach ($options as $option) {
                            if ($option['type'] == 'select') {
                                $UnitArry = explode(' ', $option['name']);
                                if (strtolower(end($UnitArry)) != "units") { ?>
                                    <div class="panel panel-default panel-default-options">
                                        <div class="panel-heading">
                                            <h4 class="panel-title"><a data-toggle="collapse" href="#option-<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?><i class="fa fa-caret-down pull-right"></i></a></h4>
                                        </div>
                                        <div id="option-<?php echo $option['product_option_id']; ?>" class="option ig_<?php echo str_replace(' ', '', $option['name']); ?> panel-collapse collapse in">
                                            <b style="display: none;"><?php echo $option['name']; ?>:</b>
                                            <div class="panel-body">
                                                <select name="option[<?php echo $option['product_option_id']; ?>]" class="form-control optionselectbox">
                                                    <option value="">-- Please Select --</option>
                                                    <?php if ($option['metal_type'] > 1) { ?>
                                                        <option value=""><?php echo $text_select; ?></option>
                                                    <?php } ?>
                                                    <?php foreach ($option['option_value'] as $option_value) { 
                                                        if($option_value['quantity'] <= 0) { ?>
                                                            <option qty="<?php echo $option_value['quantity'];?>" <?php if($option_value['is_requested_option_value']) { echo "selected='selected'";} ?> data-price="" value="<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name'].' - '.$option_out_of_stock; ?></option>
                                                        <?php } else { ?>
                                                            <option qty="<?php echo $option_value['quantity'];?>" <?php if($option_value['is_requested_option_value']) { echo "selected='selected'";} ?> data-price="" value="<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?></option>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                <?php }
                            }
                            if ($option['type'] == 'radio') {
                                $UnitArry = explode(' ', $option['name']);
                                if (strtolower(end($UnitArry)) != "units") { ?>
                                    <div class="panel panel-default panel-default-options">
                                        <div class="panel-heading">
                                            <h4 class="panel-title"><a data-toggle="collapse" href="#option-<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?><i class="fa fa-caret-down pull-right"></i></a></h4>
                                        </div>
                                        <div id="option-<?php echo $option['product_option_id']; ?>" class="option ig_<?php echo str_replace(' ', '', $option['name']); ?> panel-collapse collapse in">
                                            <div class="panel-body">
                                                <?php foreach ($option['option_value'] as $option_value) { ?>
                                                    <label for="option-value-<?php echo $option_value['product_option_value_id']; ?>" data-val="<?php echo $option_value['name']; ?>" ><?php echo $option_value['name']; ?></label>
                                                    <input data-price="" type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" />
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php }
                            }
                        } ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><a data-toggle="collapse" href="#option-price">Price range<i class="fa fa-caret-down pull-right"></i></a></h4>
                            </div>
                            <div id="option-price" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <?php if(isset($_REQUEST['price_from'])){
                                        $selected_price_range_from = $_REQUEST['price_from'];
                                    }else{
                                        $selected_price_range_from = 0;
                                    } ?>
                                    
                                    <?php if(isset($_REQUEST['price_to'])){
                                        $selected_price_range_to = $_REQUEST['price_to'];
                                    }else{
                                        $selected_price_range_to = $max_price;
                                    } ?>
                                    <input type="text" class="js-range-slider" name="price_range" id="price_range" value="" data-type="double" data-min="0" data-max="<?php echo $max_price; ?>" data-from="<?php echo $selected_price_range_from; ?>" data-to="<?php echo $selected_price_range_to; ?>" data-grid="true" />
                                </div>
                                <div class="panel-footer">
                                    <button id="reset_filters" class="btn btn-blue">Clear Filters</button> <button id="apply_filters" class="btn btn-yellow pull-right">Apply Filters</button>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="row product-filters">
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 text-left"><?php echo $pagination; ?></div>
                <div class="col-lg-4 col-md-9 col-sm-9 col-xs-12 text-right"><?php echo $results; ?></div>
                <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6">
                    <div class="form-group input-group input-group-sm">
                        <label class="input-group-addon" for="input-limit">Show:</label>
                        <select class="form-control" onchange="location = this.value;">
                            <?php foreach ($limits as $limits) { ?>
                                <?php if ($limits['value'] == $limit) { ?>
                                    <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
                                <?php } else { ?>
                                    <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if (!$products) { ?>
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                <div class="content"><?php echo 'No Products are found!'; ?></div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 options">
                <?php if(!empty($text_groupby)){ ?>
                    <div class="panel panel-default panel-default-options">
                        <div class="panel-heading">
                            <h4 class="panel-title"><a data-toggle="collapse" href="#option-<?php echo str_replace(' ', '-', strtolower($text_groupby)); ?>"><?php echo $text_groupby; ?><i class="fa fa-caret-down pull-right"></i></a></h4>
                        </div>
                        <div id="option-<?php echo str_replace(' ', '-', strtolower($text_groupby)); ?>" class="panel-collapse collapse in">
                            <div class="panel-body">
                                <span id="select_product_name" style="display: none;"><?php echo $text_groupby; ?></span>
                                <select name="select_product" id="select_product" class='form-control optionselectbox'>
                                    <option value="">-- Please Select --</option>
                                    <?php foreach ($product_grouped as $product) { ?>
                                        <option <?php if($product['is_requested_product']) { echo "selected='selected'"; } ?> value="<?php echo $product['product_id']; ?>"><?php echo trim($product['product_name']); ?> </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                <?php } ?>    
                <?php if (isset($options)) { ?>
                    <?php foreach ($options as $option) {
                        if ($option['type'] == 'select') {
                            $UnitArry = explode(' ', $option['name']);
                            if (strtolower(end($UnitArry)) != "units") { ?>
                                <div class="panel panel-default panel-default-options">
                                    <div class="panel-heading">
                                        <h4 class="panel-title"><a data-toggle="collapse" href="#option-<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?><i class="fa fa-caret-down pull-right"></i></a></h4>
                                    </div>
                                    <div id="option-<?php echo $option['product_option_id']; ?>" class="option ig_<?php echo str_replace(' ', '', $option['name']); ?> panel-collapse collapse in">
                                        <b style="display: none;"><?php echo $option['name']; ?>:</b>
                                        <div class="panel-body">
                                            <select name="option[<?php echo $option['product_option_id']; ?>]" class="form-control optionselectbox">
                                                <option value="">-- Please Select --</option>
                                                <?php if ($option['metal_type'] > 1) { ?>
                                                    <option value=""><?php echo $text_select; ?></option>
                                                <?php } ?>
                                                <?php foreach ($option['option_value'] as $option_value) { 
                                                    if($option_value['quantity'] <= 0) { ?>
                                                        <option qty="<?php echo $option_value['quantity'];?>" <?php if($option_value['is_requested_option_value']) { echo "selected='selected'";} ?> data-price="" value="<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name'].' - '.$option_out_of_stock; ?></option>
                                                    <?php } else { ?>
                                                        <option qty="<?php echo $option_value['quantity'];?>" <?php if($option_value['is_requested_option_value']) { echo "selected='selected'";} ?> data-price="" value="<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            <?php }
                        }
                        if ($option['type'] == 'radio') {
                            $UnitArry = explode(' ', $option['name']);
                            if (strtolower(end($UnitArry)) != "units") { ?>
                                <div class="panel panel-default panel-default-options">
                                    <div class="panel-heading">
                                        <h4 class="panel-title"><a data-toggle="collapse" href="#option-<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?><i class="fa fa-caret-down pull-right"></i></a></h4>
                                    </div>
                                    <div id="option-<?php echo $option['product_option_id']; ?>" class="option ig_<?php echo str_replace(' ', '', $option['name']); ?> panel-collapse collapse in">
                                        <div class="panel-body">
                                            <?php foreach ($option['option_value'] as $option_value) { ?>
                                                <label for="option-value-<?php echo $option_value['product_option_value_id']; ?>" data-val="<?php echo $option_value['name']; ?>" ><?php echo $option_value['name']; ?></label>
                                                <input data-price="" type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" />
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php }
                        }
                    } ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title"><a data-toggle="collapse" href="#option-price">Price range<i class="fa fa-caret-down pull-right"></i></a></h4>
                        </div>
                        <div id="option-price" class="panel-collapse collapse in">
                            <div class="panel-body">
                                <?php if(isset($_REQUEST['price_from'])){
                                    $selected_price_range_from = $_REQUEST['price_from'];
                                }else{
                                    $selected_price_range_from = 0;
                                } ?>
                                <?php if(isset($_REQUEST['price_to'])){
                                    $selected_price_range_to = $_REQUEST['price_to'];
                                }else{
                                    $selected_price_range_to = $max_price;
                                } ?>
                                <input type="text" class="js-range-slider" name="price_range" id="price_range" value="" data-type="double" data-min="0" data-max="<?php echo $max_price; ?>" data-from="<?php echo $selected_price_range_from; ?>" data-to="<?php echo $selected_price_range_to; ?>" data-grid="true" />
                            </div>
                            <div class="panel-footer">
                                <button id="reset_filters" class="btn btn-blue">Clear Filters</button> <button id="apply_filters" class="btn btn-yellow pull-right">Apply Filters</button>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
        <?php echo $content_bottom; ?>
	</div>
    <?php echo $column_right; ?>
</div>
<script>
    $("#price_range").ionRangeSlider({
        prefix: '$'
    });

    $(function () {
        $("#reset_filters").bind("click", function () {
            $(".optionselectbox").each(function() {
                $(this)[0].selectedIndex = 0;
            });
            var my_range = $("#price_range").data("ionRangeSlider");
            my_range.update({
                from: 0,
                to: $('.irs-max').html().replace('$','')
            });
            my_range.reset();
        });
    });

    $(document).ready(function() {
        <?php if (!$logged) { ?>
            $('.bulk-product-tex span.text-bulk').bind('click', function() {
                $(".show_hide_box").toggle();
            });
        <?php } ?>
    });

    $(function () {
        $("#apply_filters").bind("click", function () {
            var url = '<?php echo $page_url; ?>';
            var that = this;
            var selOpt = [];
            var name, value, grouped;
            var price_to = $('.irs-to').html().replace('$','');
            var price_from = $('.irs-from').html().replace('$','');
            var groupedname = $.trim($('#select_product_name').html());
            var groupedvalue = $.trim($('#select_product').find(":selected").text());
            if(groupedvalue !== '-- Please Select --'){
                grouped = encodeURIComponent(groupedname+'~'+groupedvalue);
            }else{
                grouped = '';
            }
            url = url+'&price_to='+price_to+'&price_from='+price_from+'&grouped='+grouped+'&options=';
            var optionObj=$('.options>.panel-default-options>.option');
            var i=0;
            optionObj.each(function(){
                if(typeof(that.obj)!='undefined'){
                    if(that.obj.length>0){
                        if($(this).find('select').attr('name')==that.obj.attr('name')){
                            return false;
                        }
                    }
                }
                name=$.trim($(this).find('b').text());
                value=$.trim($(this).find('select>option:selected').html());
                name=$.trim(name.replace(':',''));
                if(value !== '-- Please Select --'){
                    url += encodeURIComponent(name+'~'+value+',');
                    i++;
                }
            });
            window.location.replace(url);
        });
    });
</script>
<?php echo $footer; ?>