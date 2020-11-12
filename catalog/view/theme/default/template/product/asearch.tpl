<?php echo $header; ?>
<div class="col_hide">
    <div class="common_filter"> <?php echo $column_left; ?></div>
    <?php echo $column_right; ?>
    <div class="search-page"></div>

<div id="content" class="flt searchpage search-pagemain ">
        <?php echo $content_top; ?>
        <div class="breadcrumb bred_asearch ">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
            <?php } ?>
        </div>
        <h1><?php echo $totales; ?> <?php echo $heading_title; ?></h1>
       
        
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
            <div class="product-filter">
               <!-- <div class="pagination pagination_category pagination_asearch"><?php echo $pagination; ?></div>-->

                <div class="limit"><b class="result_per">Results Per Page:</b>
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
                <div class="sort asearch_sort" style="margin-top:29px;margin-right:10px;"><b class="result_per"><?php echo $text_sort; ?></b>
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
			
               
                    <div class="product-grid">
                        <?php
                        if ($products) {
                            foreach ($products as $product) {
                                ?>
                                <div class="asearch_product_grid">

                                    <?php if ($product['thumb']) {
                                        ?>
                                        <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>"></a></div>
                                    <?php } else { ?>
                                        <div class="image"><a href="<?php echo $product['href']; ?>"><img style="width:150px; height:150px;" src="<?php echo HTTP_SERVER; ?>catalog/view/theme/default/image/no-image.gif" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>"></a></div>
                                    <?php } ?>
                                    <div class="name prodcut_category_name"><a href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>"><?php
                                            if (strlen($product['name']) > 36) {
                                                echo substr($product['name'], 0, 36) . "...";
                                            } else {
                                                echo $product['name'];
                                            }
                                            ?></a>
                                    </div>
                                    <div class="description catee-des"><?php echo $product['description']; ?></div>
                                    <?php if ($product['price']) { ?>
                                        <div class="price price_cat_product">
                                            <?php if (!$product['special']) { ?>
                                                <?php echo $product['price'] . $product['unit']; ?>
                                            <?php } else { ?>
                                                <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
                                            <?php } ?>
                                            <?php if ($product['tax']) { ?>
                                                <br>
                                                <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                    <?php if ($product['rating']) { ?>

                                    <?php } ?>

                                    <div class="wishlist"><a onclick="addToWishList('<?php echo $product['product_id']; ?>');"><?php echo $button_wishlist; ?></a></div>
                                    <div class="compare"><a onclick="addToCompare('<?php echo $product['product_id']; ?>');"><?php echo $button_compare; ?></a></div>
                                </div>
                            <?php } ?>
                        
                       
                        <?php
                    } ?>
                    
                </div>    	
              
          
            <div class="pagination"><?php echo $pagination; ?></div>
		<?php } ?>
        <?php if (!$categories && !$products) { ?>
            <div class="content"><?php echo $text_empty; ?></div>
            <div class="buttons">
                <div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
            </div>
		<?php } ?>
    </div>
<?php echo $content_bottom; ?></div>
<script type="text/javascript"><!--
function display(view) {

        if (view == 'list') {
            $('.product-grid').attr('class', 'product-list search-content');

            $('.product-list > div').each(function(index, element) {
                html = '<div class="right">';
//			html += '  <div class="cart">' + $(element).find('.cart').html() + '</div>';
                html += '  <div class="wishlist">' + $(element).find('.wishlist').html() + '</div>';
                html += '  <div class="compare">' + $(element).find('.compare').html() + '</div>';
                html += '</div>';

                html += '<div class="left">';

                var image = $(element).find('.image').html();

                if (image != null) {
                    html += '<div class="image">' + image + '</div>';
                }
                html += '  <div class="name prodcut_category_name ">' + $(element).find('.name').html() + '</div>';
                var price = $(element).find('.price').html();

                if (price != null) {
                    html += '<div class="price price_cat_product">' + price + '</div>';
                }

                html += '  <div class="description tt">' + $(element).find('.description').html() + '</div>';

                var rating = $(element).find('.rating').html();

                if (rating != null) {
                    html += '<div class="rating">' + rating + '</div>';
                }

                html += '</div>';

                $(element).html(html);
            });

            $('.display').html('<b><?php echo $text_display; ?></b> <?php echo $text_list; ?> <b>/</b> <a onclick="display(\'grid\');"><?php echo $text_grid; ?></a>');

           
        } else {
            $('.product-list').attr('class', 'product-grid search-page');

            $('.product-grid > div').each(function(index, element) {
                html = '';

                var image = $(element).find('.image').html();

                if (image != null) {
                    html += '<div class="image">' + image + '</div>';
                }
                html += '<div class="name prodcut_category_name ">' + $(element).find('.name').html() + '</div>';

                html += '<div class="description tt">' + $(element).find('.description').html() + '</div>';

                var price = $(element).find('.price').html();

                if (price != null) {
                    html += '<div class="price price_cat_product">' + price + '</div>';
                }

                var rating = $(element).find('.rating').html();

                if (rating != null) {
                    html += '<div class="rating">' + rating + '</div>';
                }

//			html += '<div class="cart">' + $(element).find('.cart').html() + '</div>';
                html += '<div class="wishlist">' + $(element).find('.wishlist').html() + '</div>';
                html += '<div class="compare">' + $(element).find('.compare').html() + '</div>';

                $(element).html(html);
            });

            $('.display').html('<b><?php echo $text_display; ?></b> <a onclick="display(\'list\');"><?php echo $text_list; ?></a> <b>/</b> <?php echo $text_grid; ?>');

            
        }
    }

   
  display('grid');
   
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
     
  <style>
  	@media (min-width: 901px){
		  .flt {
		   /* float: left;*/
			float: right;
			width: 78% !important;
			margin-left: 0px;
		}
  	}
	@media (min-width: 768px) and (max-width: 900px) {
		
		.flt {
	   		/* float: left;*/
			float: right;
			width: 68% !important;
			margin-left: 0px;
		}
	}
  </style>
<?php echo $ocscroll; ?> <?php echo $footer; ?> 