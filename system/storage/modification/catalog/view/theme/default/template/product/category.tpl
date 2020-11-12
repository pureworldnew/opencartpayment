<link href="catalog/view/javascript/css/productlabel.css" rel="stylesheet" type="text/css" />
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

 <script type="text/javascript">

        
$(document).ready(function() {
    $('.showCategory').click(function() {
         $('#Div_1').toggle();
    });

    
});
</script>
        <!---Recommiting Files --->
   <div class="col_hide">  <h1 class="head_title" id="device_head_title"><?php echo $heading_title; ?></h1>
<div id="Div_1"> 
<div class="category-info device_category-info visible-tablet visible-phone"> 
    <?php if ($banner) { ?>
        <div class="image"><img src="<?php echo HTTP_SERVER . 'image/' . $banner; ?>" alt="<?php echo $heading_title; ?>" style="width:100%;"></div>
    <?php  } elseif ($thumb) { ?>
        <div class="image"><img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>"></div>
    <?php } else { ?> 
        <div class="image"><img width="150" height="150" src="<?php echo HTTP_SERVER; ?>catalog/view/theme/default/image/no_category.jpg" alt="<?php echo $heading_title; ?>"></div>
    <?php } if ($description) { ?>
        <div class="cate-decp"><?php echo $description; ?></div>
    <?php } ?>
</div>  
</div><div class="visible-phone"><div class="showCategory">[ + ] About <?php echo $category_title; ?></div></div>
  <?php echo $column_right; ?>
    <!--<div class="common_filter">-->
    <?php echo $column_left; ?>
    <div id="content" class="cate-right"><?php echo $content_top; ?>
        <div class="bred">
            <div class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
                <?php } ?>
             
            </div>
               <div class="lower_block">
                <h1 class="head_title"><?php echo $heading_title; ?></h1>
                <div class="category-info visible-desktop"> 
                    <?php if ($banner) { ?>
                        <div class="image"><img src="<?php echo HTTP_SERVER . 'image/' . $banner; ?>" alt="<?php echo $heading_title; ?>" style="width:100%;"></div>
                    <?php  } elseif ($thumb) { ?>
                        <div class="image"><img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>"></div>
                    <?php } else { ?> 
                        <div class="image"><img width="150" height="150" src="<?php echo HTTP_SERVER; ?>catalog/view/theme/default/image/no_category.jpg" alt="<?php echo $heading_title; ?>"></div>
                    <?php } if ($description) { ?>
                        <div class="cate-decp"><?php echo $description; ?></div>
                    <?php } ?>
                </div>
            </div> 

        </div>
        <?php if ($products || $categories) { ?>
            <div class="row-fluid">
                
                    <div class="product-filter">
                        <div class="pagination pagination_category"><?php echo $pagination; ?></div>
                        <div class="row">
                        <div class="col-sm-12" style="">
                         <?php
                        	if ($categories) { 
                                                foreach ($categories as $category) {
                                                    ?>
                                                    <div class="col-sm-3 caytegorymain">
                                                        <div class="name visible-desktop"><a href="<?php echo $category['href']; ?>" title="<?php echo $category['name']; ?>"><?php
                                                                if (strlen($category['name']) > 45) {
                                                                    echo substr($category['name'], 0, 45) . "...";
                                                                } else {
                                                                    echo $category['name'];
                                                                }
                                                                ?></a></div>
                                                        <?php if ($category['thumb']) { ?>
                                                            <div class="image" style="border-bottom:0;">
                                                                <a href="<?php echo $category['href']; ?>">
                                                                    <img src="<?php echo $category['thumb']; ?>" title="<?php echo $category['name']; ?>" alt="<?php echo $category['name']; ?>"></a>
                                                            </div>
                                                        <?php } else { ?>
                                                            <div class="image" style="border-bottom:0;">
                                                                <a href="<?php echo $category['href']; ?>">
                                                                    <img width="150" height="150" src="<?php echo HTTP_SERVER; ?>catalog/view/theme/default/image/no_category.jpg" title="<?php echo $category['name']; ?>" alt="<?php echo $category['name']; ?>" ></a>
                                                            </div>
                                                        <?php } ?>
                                                          <div class="name visible-tablet visible-phone"><a href="<?php echo $category['href']; ?>" title="<?php echo $category['name']; ?>"><?php
                                                                if (strlen($category['name']) > 45) {
                                                                    echo substr($category['name'], 0, 45) . "...";
                                                                } else {
                                                                    echo $category['name'];
                                                                }
                                                                ?></a></div>
                                                    </div>
                                                <?php } ?>
                                                <?php if ($latest_products) { require("catalog/view/theme/default/template/product/latest.tpl"); } ?>
                                            <?php } 
                        ?>
                    </div>
                    </div>
                    <style>
                        .caytegorymain {
                            border: 1px solid #fff;
                            
                        }
                        col-xs-11,.col-xs-12,.col-xs-2,.col-xs-3,.col-xs-4,.col-xs-5,.col-xs-6,.col-xs-7,.col-xs-8,.col-xs-9{position:relative;/* min-height:1px;  padding-top:15px; padding-right:15px; padding-left:15px;*/}
						}
						@media (max-width: 767px) {
							#column-left + #column-right + #content, #column-left + #content.cate-right{
								width:75% !important;
							}
							.col-sm-3,.col-sm-4,.col-sm-5,.col-sm-6,.col-sm-7,.col-sm-8,.col-sm-9,.col-xs-1,.col-xs-10,.col-xs-11,.col-xs-12,.col-xs-2,.col-xs-3,.col-xs-4,.col-xs-5,.col-xs-6,.col-xs-7,.col-xs-8,.col-xs-9{position:relative; float:left;}
						}
                        .name a{
                                font-weight: bold !important;
                                text-decoration: underline !important;
                        }
                        .col-xs-1,.col-xs-10,.col-xs-11,.col-xs-12,.col-xs-2,.col-xs-3,.col-xs-4,.col-xs-5,.col-xs-6,.col-xs-7,.col-xs-8,.col-xs-9{float:left}.col-xs-12{width:100%}.col-xs-11{width:91.66666667%}.col-xs-10{width:83.33333333%}.col-xs-9{width:75%}.col-xs-8{width:66.66666667%}.col-xs-7{width:58.33333333%}.col-xs-6{width:50%}.col-xs-5{width:41.66666667%}.col-xs-4{width:33.33333333%}.col-xs-3{width:25%}.col-xs-2{width:16.66666667%}.col-xs-1{width:8.33333333%}.col-xs-pull-12{right:100%}.col-xs-pull-11{right:91.66666667%}.col-xs-pull-10{right:83.33333333%}.col-xs-pull-9{right:75%}.col-xs-pull-8{right:66.66666667%}.col-xs-pull-7{right:58.33333333%}.col-xs-pull-6{right:50%}.col-xs-pull-5{right:41.66666667%}.col-xs-pull-4{right:33.33333333%}.col-xs-pull-3{right:25%}.col-xs-pull-2{right:16.66666667%}.col-xs-pull-1{right:8.33333333%}.col-xs-pull-0{right:auto}.col-xs-push-12{left:100%}.col-xs-push-11{left:91.66666667%}.col-xs-push-10{left:83.33333333%}.col-xs-push-9{left:75%}.col-xs-push-8{left:66.66666667%}.col-xs-push-7{left:58.33333333%}.col-xs-push-6{left:50%}.col-xs-push-5{left:41.66666667%}.col-xs-push-4{left:33.33333333%}.col-xs-push-3{left:25%}.col-xs-push-2{left:16.66666667%}.col-xs-push-1{left:8.33333333%}.col-xs-push-0{left:auto}.col-xs-offset-12{margin-left:100%}.col-xs-offset-11{margin-left:91.66666667%}.col-xs-offset-10{margin-left:83.33333333%}.col-xs-offset-9{margin-left:75%}.col-xs-offset-8{margin-left:66.66666667%}.col-xs-offset-7{margin-left:58.33333333%}.col-xs-offset-6{margin-left:50%}.col-xs-offset-5{margin-left:41.66666667%}.col-xs-offset-4{margin-left:33.33333333%}.col-xs-offset-3{margin-left:25%}.col-xs-offset-2{margin-left:16.66666667%}.col-xs-offset-1{margin-left:8.33333333%}.col-xs-offset-0{margin-left:0}@media (min-width:768px){.col-sm-1,.col-sm-10,.col-sm-11,.col-sm-12,.col-sm-2,.col-sm-3,.col-sm-4,.col-sm-5,.col-sm-6,.col-sm-7,.col-sm-8,.col-sm-9{float:left}.col-sm-12{width:100%}.col-sm-11{width:91.66666667%}.col-sm-10{width:83.33333333%}.col-sm-9{width:75%}.col-sm-8{width:66.66666667%}.col-sm-7{width:58.33333333%}.col-sm-6{width:50%}.col-sm-5{width:41.66666667%}.col-sm-4{width:33.33333333%}.col-sm-3{width:24%}
                     </style>
                         <?php if (!$categories){?>
                        	<div class="limit"><!-- <b class="result_per">Results Per Page:</b> -->
                            <select class="select_left" onchange="location = this.value;">
                                <?php foreach ($limits as $limits) { ?>
                                    <?php if ($limits['value'] == $limit) { ?>
                                        <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div> <p><?php echo $text_sort; ?></p>
                        <?php foreach ($sorts as $sorts) { ?>
                       <div class="col-sm-2"> <a style="color: #6b5e5e;" href="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></a>&nbsp;&nbsp;&nbsp;</div>
                        <?php } ?>
                        <div class="col-md-2 col-sm-6 hidden-xs">
                            <div class="btn-group btn-group-sm">
                            <button type="button" id="list-view" class="list_link_filter btn btn-default" data-toggle="tooltip" title="" data-original-title="List"><i class="fa fa-th-list"></i></button>
                            <button type="button" id="grid-view" class="grid_link_filter btn btn-default active" data-toggle="tooltip" title="" data-original-title="Grid"><i class="fa fa-th"></i></button>
                            </div><br><br>
                        </div>
                    		<div class="sort search_sort hidden">
                            </div>
                    	<?php }?>

                    </div>
                
				 <?php if (!$categories){?>
                	<div class="span12">
                    <div class="product-grid">
                        <?php 
                        if ($products) {
                            foreach ($products as $product) {
                                ?>
                                <div class="category-newone">

                                    <?php if ($product['thumb']) {
                                        ?>
                                        <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>"></a></div>
<!--New product count-->
              <?php $datetime1 = new DateTime($product['date']);
        $datetime2 = new DateTime();
        $interval = $datetime1->diff($datetime2);
              $date_diff_count=$interval->format('%a');
              if($new_product_status == 1 ) {
              if($new_product_db_days > $date_diff_count) {
              if($position_new==1) { ?>
              <div class="new_product_category1" style="background-color: <?php echo $new_product_db_label_color;?>;color:<?php echo $new_product_db_text_color;?>;">
                  <?php echo $new_product_db_text; ?>
              </div>
              <?php } elseif($position_new==2) { ?>
              <div class="new_product_category2" style="background-color: <?php echo $new_product_db_label_color;?>;color:<?php echo $new_product_db_text_color;?>;">
                  <?php echo $new_product_db_text; ?>
              </div>
              <?php } elseif($position_new==3) { ?>
              <div class="new_product_category3" style="background-color: <?php echo $new_product_db_label_color;?>;color:<?php echo $new_product_db_text_color;?>;">
                  <?php echo $new_product_db_text; ?>
              </div>
              <?php } elseif($position_new==4) { ?>
              <div class="new_product_category4" style="background-color: <?php echo $new_product_db_label_color;?>;color:<?php echo $new_product_db_text_color;?>;">
                  <?php echo $new_product_db_text; ?>
              </div>
              <?php } } } ?>
              <!--New product count-->

              <!--discount product-->
              <?php if($discount_product_status==1) {
        if($discount_product_db_percent < $product['percent_value']) { ?>
              <?php if ($product['special'])  {
         if($position_discount==1) { ?>
              <div class="discount_product_category1" style=" background-color: <?php echo $discount_product_db_label_color?>;color:<?php echo $discount_product_db_text_color;?>;">
                  <?php echo $product['percent']; ?>
              </div>
              <?php } elseif($position_discount==2) { ?>
              <div class="discount_product_category2" style=" background-color: <?php echo $discount_product_db_label_color?>;color:<?php echo $discount_product_db_text_color;?>;">
                  <?php echo $product['percent']; ?>
              </div>
              <?php } elseif($position_discount==3) { ?>
              <div class="discount_product_category3" style=" background-color: <?php echo $discount_product_db_label_color?>;color:<?php echo $discount_product_db_text_color;?>;">
                  <?php echo $product['percent']; ?>
              </div>
              <?php } elseif($position_discount==4) { ?>
              <div class="discount_product_category4" style=" background-color: <?php echo $discount_product_db_label_color?>;color:<?php echo $discount_product_db_text_color;?>;">
                  <?php echo $product['percent']; ?>
              </div>
              <?php } } } } ?>
              <!--discount product-->

              <!--outofstock product-->
              <?php if($outofstock_product_status==1) {
               if($product['stockquantity']==0) {

                if($position_outofstock==1) { ?>
              <div class="outofstock_product_category1" style="background-color: <?php echo $outofstock_product_db_label_color?>;color:<?php echo $outofstock_product_db_text_color;?>;">
                  <?php echo $outofstock_product_db_text;  ?>
              </div>
              <?php } elseif($position_outofstock==2) { ?>
              <div class="outofstock_product_category2" style="background-color: <?php echo $outofstock_product_db_label_color?>;color:<?php echo $outofstock_product_db_text_color;?>;">
                  <?php echo $outofstock_product_db_text;  ?>
              </div>
              <?php } elseif($position_outofstock==3) { ?>
              <div class="outofstock_product_category3" style="background-color: <?php echo $outofstock_product_db_label_color?>;color:<?php echo $outofstock_product_db_text_color;?>;">
                  <?php echo $outofstock_product_db_text;  ?>
              </div>
              <?php } elseif($position_outofstock==3) { ?>
              <div class="outofstock_product_category4" style="background-color: <?php echo $outofstock_product_db_label_color?>;color:<?php echo $outofstock_product_db_text_color;?>;">
                  <?php echo $outofstock_product_db_text;  ?>
              </div>

              <?php } } } ?>
              <!--outofstock product-->

                                    <?php } else { ?>
                                        <div class="image"><a href="<?php echo $product['href']; ?>"><img style="width:150px; height:150px;" src="<?php echo HTTP_SERVER; ?>catalog/view/theme/default/image/no-image.gif" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>"></a></div>
<!--New product count-->
              <?php $datetime1 = new DateTime($product['date']);
        $datetime2 = new DateTime();
        $interval = $datetime1->diff($datetime2);
              $date_diff_count=$interval->format('%a');
              if($new_product_status == 1 ) {
              if($new_product_db_days > $date_diff_count) {
              if($position_new==1) { ?>
              <div class="new_product_category1" style="background-color: <?php echo $new_product_db_label_color;?>;color:<?php echo $new_product_db_text_color;?>;">
                  <?php echo $new_product_db_text; ?>
              </div>
              <?php } elseif($position_new==2) { ?>
              <div class="new_product_category2" style="background-color: <?php echo $new_product_db_label_color;?>;color:<?php echo $new_product_db_text_color;?>;">
                  <?php echo $new_product_db_text; ?>
              </div>
              <?php } elseif($position_new==3) { ?>
              <div class="new_product_category3" style="background-color: <?php echo $new_product_db_label_color;?>;color:<?php echo $new_product_db_text_color;?>;">
                  <?php echo $new_product_db_text; ?>
              </div>
              <?php } elseif($position_new==4) { ?>
              <div class="new_product_category4" style="background-color: <?php echo $new_product_db_label_color;?>;color:<?php echo $new_product_db_text_color;?>;">
                  <?php echo $new_product_db_text; ?>
              </div>
              <?php } } } ?>
              <!--New product count-->

              <!--discount product-->
              <?php if($discount_product_status==1) {
        if($discount_product_db_percent < $product['percent_value']) { ?>
              <?php if ($product['special'])  {
         if($position_discount==1) { ?>
              <div class="discount_product_category1" style=" background-color: <?php echo $discount_product_db_label_color?>;color:<?php echo $discount_product_db_text_color;?>;">
                  <?php echo $product['percent']; ?>
              </div>
              <?php } elseif($position_discount==2) { ?>
              <div class="discount_product_category2" style=" background-color: <?php echo $discount_product_db_label_color?>;color:<?php echo $discount_product_db_text_color;?>;">
                  <?php echo $product['percent']; ?>
              </div>
              <?php } elseif($position_discount==3) { ?>
              <div class="discount_product_category3" style=" background-color: <?php echo $discount_product_db_label_color?>;color:<?php echo $discount_product_db_text_color;?>;">
                  <?php echo $product['percent']; ?>
              </div>
              <?php } elseif($position_discount==4) { ?>
              <div class="discount_product_category4" style=" background-color: <?php echo $discount_product_db_label_color?>;color:<?php echo $discount_product_db_text_color;?>;">
                  <?php echo $product['percent']; ?>
              </div>
              <?php } } } } ?>
              <!--discount product-->

              <!--outofstock product-->
              <?php if($outofstock_product_status==1) {
               if($product['stockquantity']==0) {

                if($position_outofstock==1) { ?>
              <div class="outofstock_product_category1" style="background-color: <?php echo $outofstock_product_db_label_color?>;color:<?php echo $outofstock_product_db_text_color;?>;">
                  <?php echo $outofstock_product_db_text;  ?>
              </div>
              <?php } elseif($position_outofstock==2) { ?>
              <div class="outofstock_product_category2" style="background-color: <?php echo $outofstock_product_db_label_color?>;color:<?php echo $outofstock_product_db_text_color;?>;">
                  <?php echo $outofstock_product_db_text;  ?>
              </div>
              <?php } elseif($position_outofstock==3) { ?>
              <div class="outofstock_product_category3" style="background-color: <?php echo $outofstock_product_db_label_color?>;color:<?php echo $outofstock_product_db_text_color;?>;">
                  <?php echo $outofstock_product_db_text;  ?>
              </div>
              <?php } elseif($position_outofstock==3) { ?>
              <div class="outofstock_product_category4" style="background-color: <?php echo $outofstock_product_db_label_color?>;color:<?php echo $outofstock_product_db_text_color;?>;">
                  <?php echo $outofstock_product_db_text;  ?>
              </div>

              <?php } } } ?>
              <!--outofstock product-->

                                    <?php } ?>
                                    <div class="name prodcut_category_name"><a class="short_length_name" href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>"><?php
                                            if (strlen($product['name']) > 50) {
                                                echo substr($product['name'], 0, 50) . "...";
                                            } else {
                                                echo $product['name'];
                                            }
                                            ?></a>
                                            <a class="long_length_name" style="display: none;">
                                                <?php
                                            if (strlen($product['name']) > 100) {
                                                echo substr($product['name'], 0, 100) . "...";
                                            } else {
                                                echo $product['name'];
                                            }
                                            ?>

                                            </a>
                                    </div>
                                    <div class="description catee-des"><table class="table table-hover"><?php 
                                        //echo $product['description'];
                                        foreach($product['product_attributes'] as $pro_attr){
                                        ?>
                                        
                                            <tr>
                                                <td><?php echo $pro_attr['text']; ?></td>
                                                <td><?php echo $pro_attr['value']; ?></td>
                                            </tr>
                                        
                                        <?php
                                    }
                                         ?></table></div>
                                        
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
                        </div>
                        <div class="pagination"><?php echo $pagination; ?></div>
                        <?php
                    } 
                    
                        ?>
                </div>
				<?php }?>
            </div>

         
        </div>
        <?php if (!$categories && !$products) { ?>
            <div class="content"><?php echo $text_empty; ?></div>
            <div class="buttons">
                <div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
            </div>
        <?php } ?>
        <?php echo $content_bottom; ?></div>
<?php } ?> 
</div>
<script type="text/javascript">
    jQuery(document).ready(function() {
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

        $("#filtermenuadv").trigger('click');

    });
    function mycustomdispay(view) {
        if (view == "list") {
            $('.product-grid').addClass('product-list').removeClass('product-grid');
            $('.list_link_filter').addClass("bld");
            $('.grid_link_filter').removeClass("bld");
            $('.list_link_filter').addClass("active");
            $('.grid_link_filter').removeClass("active");
            $('.prodcut_category_name').addClass('list_view_style');
            $(".long_length_name").show();
            $(".short_length_name").hide();
            $(".price_cat_product").addClass('list_view_price');
        } else {
            $('.product-list').addClass('product-grid').removeClass('product-list');
            $('.grid_link_filter').addClass("bld");
            $('.list_link_filter').removeClass("bld");
            if ( !$(".grid_link_filter").hasClass("active") )
                $('.grid_link_filter').addClass("active");
            $('.list_link_filter').removeClass("active");
            $('.prodcut_category_name').removeClass('list_view_style');
            $(".long_length_name").hide();
            $(".short_length_name").show();
            $(".price_cat_product").removeClass('list_view_price');
        }
    }

</script> 
<?php echo $footer; ?>
