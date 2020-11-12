<?php echo $header; ?>
	<div class="row">
	    <?php
            echo str_replace('class="col-lg-2 col-md-3 col-sm-4 hidden-xs" id="column-left"','class="col-lg-2 col-md-3 col-sm-4 hidden-sm hidden-xs" id="column-left"', $column_left);
            // echo $column_left;
        ?>
<?php
if ($column_left and $column_right) {
    $class="col-lg-8 col-md-6 col-sm-8 col-xs-12";
} elseif ($column_left or $column_right) {
     $class="col-lg-10 col-md-9 col-sm-12 col-xs-12";
} else {
     $class="col-xs-12";
}
?>
        <div class="<?php echo $class; ?>" id="content">
            <ul class="breadcrumb">
<?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
<?php } ?>
            </ul>
            <?php if( !empty($content_top) ) : ?>
            <div class="hidden-sm hidden-xs">
                <?php echo $content_top; ?>
            </div>
            <?php endif; ?>
            <h1><?php echo $heading_title; ?></h1>
            <div class="hidden-lg hidden-md filter-mobile-only">
                <button data-toggle="collapse" data-target="#mo-filter" class="btn btn-yellow">Filter by <i class="fa fa-filter"></i></button>
                <div id="mo-filter" class="collapse">
                    <?php echo $content_top; ?>
                </div>
            </div>
            <div class="panel panel-default hidden">
                <div class="panel-body">
                    <h4><?php echo $text_critea; ?></h4>
                    <div class="row">
                        <div class="col-sm-6">
                            <input type="text" name="search" value="<?php echo $search; ?>" placeholder="<?php echo $text_keyword; ?>" id="input-search" class="form-control" />
                        </div>
                        <div class="col-sm-6">
                            <select name="category_id" class="form-control">
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
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <p><label class="checkbox-inline"><?php if ($description) { ?><input type="checkbox" name="description" value="1" id="description" checked="checked" /><?php } else { ?><input type="checkbox" name="description" value="1" id="description" /><?php } ?><?php echo $entry_description; ?></label></p>
                        </div>
                        <div class="col-sm-6">
                            <p><label class="checkbox-inline"><?php if ($sub_category) { ?><input type="checkbox" name="sub_category" value="1" checked="checked" /><?php } else { ?><input type="checkbox" name="sub_category" value="1" /><?php } ?><?php echo $text_sub_category; ?></label></p>
                        </div>
                    </div>
                    
                    <p><input type="button" value="<?php echo $button_search; ?>" id="button-search" class="btn btn-primary" /></p>
                </div>
            </div>
            <h2><?php echo $text_search; ?></h2>
<?php  if ($products) { ?>
            <div class="row product-filters">
					<div class="col-lg-1 col-md-2 col-sm-4 col-xs-3 hidden-sm hidden-xs hidden-md">
						<div class="btn-group">
							<button type="button" id="list-view" class="btn btn-default" data-toggle="tooltip" title="List View"><i class="fa fa-th-list"></i></button>
							<button type="button" id="grid-view" class="btn btn-default" data-toggle="tooltip" title="Grid View"><i class="fa fa-th"></i></button>
						</div>
					</div>
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
					<div class="col-lg-9 hidden-md hidden-sm hidden-xs text-center">
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
            <hr />
            <div class="pagination-outer text-right">
                <?php echo $pagination ?>
            </div>
            <hr />
            <div class="row">
<?php foreach ($products as $product) { ?>
                <div class="product-layout product-grid col-lg-3 col-md-3 col-sm-6 col-xs-6">
						<div class="product-grid-item">
							
							<div class="product-item">
								<div class="namefull"><a href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>"><?php
                                            if (strlen($product['name']) > 60) {
                                                echo substr($product['name'], 0, 60) . "...";
                                            } else {
                                                echo $product['name'];
                                            }
                                            ?>
									</a></div>
								<?php if ($product['thumb']) {
                                        ?>
                                        <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
                                    <?php } else { ?>
                                        <div class="image"><a href="<?php echo $product['href']; ?>"><img style="width:150px; height:150px;" src="<?php echo HTTP_SERVER; ?>catalog/view/theme/default/image/no-image.gif" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" class="img-responsive"  /></a></div>
                                    <?php } ?>
								
								<p class="modal-no"><strong> <?php echo $text_model; ?> <?php echo $product['model']; ?></strong></p>
								    
								 <?php if ($product['price']) { ?>
								<p class="price">
									 <?php if (!$product['special']) { ?>
                                    <span><?php echo $product['price']; ?></span>            
									<span class="unit-products"> <?php echo  $product['unit']; ?> </span>
									<?php } else { ?>
									 <span><?php echo $product['price']; ?></span>            
									<span class="unit-products"> <?php echo $product['special']; ?></span>
									<?php } ?>
                                            <?php if ($product['tax']) { ?>
									<span class="unit-products"> <?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
									 <?php } ?>
								</p>
								<?php } ?>
								<div class="cart"><input type="button" value="Add to Cart"  class="btn btn-orange" data-product_id="77137"/></div>
							</div>
						</div>
							<div class="product-list-item">
							<div class="row">
								<div class="col-md-3 col-sm-4 col-xs-6">
									<?php if ($product['thumb']) {
                                        ?>
                                        <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
                                    <?php } else { ?>
                                        <div class="image"><a href="<?php echo $product['href']; ?>"><img style="width:150px; height:150px;" src="<?php echo HTTP_SERVER; ?>catalog/view/theme/default/image/no-image.gif" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" class="img-responsive"  /></a></div>
                                    <?php } ?>
								</div>
								<div class="col-md-6 col-sm-4 col-xs-6">
									<div class="namefull"><a href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>"><?php
                                            if (strlen($product['name']) > 60) {
                                                echo substr($product['name'], 0, 60) . "...";
                                            } else {
                                                echo $product['name'];
                                            }
                                            ?></a></div>
									<div class="description catee-des">
										<table class="table table-hover">
											<tbody>
												<?php 
                                        //echo $product['description'];
                                        if ( !empty($product['product_attributes']) ) 
                                        {
                                            foreach($product['product_attributes'] as $pro_attr){
                                            ?>
                                            
                                                <tr>
                                                    <td><?php echo $pro_attr['text']; ?></td>
                                                    <td><?php echo $pro_attr['value']; ?></td>
                                                </tr>
                                            
                                            <?php
                                            }
                                        }
                                         ?>
												
											</tbody>
										</table>
									</div>
								</div>
								<div class="col-md-3 col-sm-4 col-xs-12 text-center">
									<p class="modal-no"><strong><?php echo $text_model; ?> <?php echo $product['model']; ?></strong></p>
									 <?php if ($product['price']) { ?>
								<p class="price">
									 <?php if (!$product['special']) { ?>
                                    <span><?php echo $product['price']; ?></span>            
									<span class="unit-products"> <?php echo  $product['unit']; ?> </span>
									<?php } else { ?>
									 <span><?php echo $product['price']; ?></span>            
									<span class="unit-products"> <?php echo $product['special']; ?></span>
									<?php } ?>
                                            <?php if ($product['tax']) { ?>
									<span class="unit-products"> <?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
									 <?php } ?>
								</p>
								<?php } ?>
									<div class="btn-group">
										<a onclick="addToWishList('35816');" class="btn btn-default" data-toggle="tooltip" title="Add to Wish List"><i class="fa fa-heart"></i></a>
										<a onclick="addToCompare('35816');" class="btn btn-default" data-toggle="tooltip" title="Compare this Product"><i class="fa fa-bar-chart"></i></a>
										<input type="button" value="Add to Cart"  class="btn btn-default" data-product_id="35816"/>
									</div>
								</div>
							</div>
							<hr />
						</div>
					</div>
<?php } ?>
            </div>
            <div class="pagination-outer text-right">
                <?php echo $pagination ?>
            </div>
<?php } ?>
            <?php echo $content_bottom; ?>
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
