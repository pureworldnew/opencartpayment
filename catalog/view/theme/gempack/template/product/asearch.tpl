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
            <h1><?php echo $totales; ?> <?php echo $heading_title; ?></h1>
            <div class="hidden-lg hidden-md filter-mobile-only">
                <button data-toggle="collapse" data-target="#mo-filter" class="btn btn-yellow">Filter by <i class="fa fa-filter"></i></button>
                <div id="mo-filter" class="collapse">
                    <?php echo $content_top; ?>
                </div>
            </div>
            <div class="search-page"></div>
            <div class="flt searchpage search-pagemain ">
<?php if ($values_selected) { ?>
                <div id="filter_del_nav">
                <div id="current_search">
                    <h2 class="refine_txt"><?php echo $entry_selected; ?></h2>
                    <?php
                    if (count($values_selected) > 1) {
                        echo $reset_all_filter;
                    }
                    ?>
                    <?php foreach ($values_selected as $value_selected) { ?>
                    	
                        <?php 
                        	$str_value_selected = explode('?',$value_selected['href']);
                            
                            if($str_value_selected[1] == 'filtering=C'){
                            	$value_selected['href'] = str_replace('?filtering=C','',$value_selected['href']);
                            }
                                
                        
                        
                        ?>
                    	
                        <a class="filter_del_link link_filter_del smenu {dnd:'<?php echo $value_selected['href']; ?>', ajaxurl:'<?php echo $value_selected['ajax_url']; ?>', gapush:'no'}" href="javascript:void(0)" <?php echo $nofollow; ?>> <?php echo $value_selected['dnd']; ?>: <?php echo $value_selected['name']; ?><span> <img src="image/supermenu/spacer.gif" alt="<?php echo $remove_filter_text; ?>" class="filter_del_nav_img" /> </span></a>
                    <?php } ?>
                </div>
            </div>
<?php } ?>
<?php if ($products) { ?>
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
                <div class="pagination-outer text-right"><?php echo $pagination ?></div>
                <hr />
                <div class="asearch_product_grid">
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
                                            ?></a></div>
<?php if ($product['thumb']) { ?>
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
                                    <div class="cart"><button type="button" class="btn btn-default" onclick="cart.add('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>');"><?php echo $button_cart; ?></button></div>
                                </div>
                            </div>
							<div class="product-list-item">
							    <div class="row">
								    <div class="col-md-3 col-sm-4 col-xs-6">
<?php if ($product['thumb']) { ?>
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
<?php foreach($product['product_attributes'] as $pro_attr) { ?>
                                                    <tr>
                                                        <td><?php echo $pro_attr['text']; ?></td>
                                                        <td><?php echo $pro_attr['value']; ?></td>
                                                    </tr>
<?php } ?>
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
                                            <button type="button" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
                                            <button type="button" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-bar-chart"></i></button>
                                            <button type="button" class="btn btn-default" onclick="cart.add('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>');"><?php echo $button_cart; ?></button>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                            </div>
                        </div>
<?php } ?>
                    </div>
                </div>
                <div class="pagination-outer text-right"><?php echo $pagination ?></div>
<?php } ?>
            </div>
<?php if (!$categories && !$products) { ?>
            <?php echo $text_empty; ?>
<?php } ?>
            <?php echo $content_bottom; ?>
        </div>
<script type="text/javascript"><!--
$("div.pagination div.links a").click(function(event){
		event.preventDefault();
		var hreflink = $(this).attr("href");
		if (hreflink.indexOf("product/asearch")!=-1){//don't have seo
			var someVar=hreflink.replace(/http(.*?)&/i,"");
		}else{//have seo
			var someVar=hreflink.replace(/http(.*?)\?/i,"");
		}
		<?php if ($is_ajax){ ?> 
		
		 if (history.pushState) {
          History.pushState(null, someVar, hreflink); 
          }else{
         Ajaxmenup(someVar);
          }
		
		<?php }else{ ?>
			window.location.href =hreflink;
		<?php } ?>
		
		return false;
	 });//--></script> 
        <?php echo $column_right; ?>
    </div>
<?php echo $ocscroll; ?>
<?php echo $footer; ?> 