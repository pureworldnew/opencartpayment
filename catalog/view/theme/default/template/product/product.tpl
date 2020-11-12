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
	"price": "<?php if (!$special) { echo preg_replace('/[^0-9.]+/','',$price);}else{echo preg_replace('/[^0-9.]+/','',$special);} ?>",
	"itemCondition" : "http://schema.org/NewCondition",
	"availability" : "<?php if ($stock == 'In Stock') { echo 'InStock'; } else { echo $stock; } ?>"
  }
}
</script>


                    <script>
                        window.onload = function(){
                            _gaq.push(['_trackPageview', '/product-virtual']);
                        }
                    </script>
                <link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/magnific/magnific-popup.css" />
<script type="text/javascript" src="catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js"></script>
            
<?php if (isset($error_cmailid) and !empty($error_cmailid)) { ?>
<div id="notification"><div class="warning"><?php echo $error_cmailid; ?><img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div></div>
<?php }else if(isset($success) and !empty($success)) { ?>
<div id="notification"><div class="success"><?php echo $success;?><img src="catalog/view/theme/default/image/close.png" alt="" class="close" /> </div></div>
<?php } ?><?php echo $column_left; ?>
<?php echo $content_top; ?>
<!--mid-block starts here-->
<div class="mid-block">
    <div class="product-info">

			
				<?php 
				if (isset($richsnippets['breadcrumbs'])) { ?>
				<span xmlns:v="http://rdf.data-vocabulary.org/#">
				<?php foreach ($mbreadcrumbs as $mbreadcrumb) { ?>
				<span typeof="v:Breadcrumb"><a rel="v:url" property="v:title" href="<?php echo $mbreadcrumb['href']; ?>" alt="<?php echo $mbreadcrumb['text']; ?>"></a></span>
				<?php } ?>				
				</span>
				<?php }
				if (isset($richsnippets['product'])) {
				?>
				<span itemscope itemtype="http://schema.org/Product">
				<meta itemprop="url" content="<?php $mlink = end($breadcrumbs); echo $mlink['href']; ?>" >
				<meta itemprop="name" content="<?php echo $heading_title; ?>" >
				<meta itemprop="model" content="<?php echo $model; ?>" >
				<meta itemprop="manufacturer" content="<?php echo $manufacturer; ?>" >
				
				<?php if ($thumb) { ?>
				<meta itemprop="image" content="<?php echo $thumb; ?>" >
				<?php } ?>
				
				<?php if ($images) { foreach ($images as $image) {?>
				<meta itemprop="image" content="<?php echo $image['thumb']; ?>" >
				<?php } } ?>
				
				<?php if (isset($richsnippets['offer'])) { ?>
				<span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
				<meta itemprop="price" content="<?php echo ($special ? $special : $price); ?>" />
				<meta itemprop="priceCurrency" content="<?php echo $this->currency->getCode(); ?>" />
				<link itemprop="availability" href="http://schema.org/<?php echo (($quantity > 0) ? "InStock" : "OutOfStock") ?>" />
				</span>
				<?php } ?>
				
				<?php if (isset($richsnippets['rating'])) { ?>
				<span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
				<meta itemprop="reviewCount" content="<?php echo ($review_no)?$review_no:0; ?>">
				<meta itemprop="ratingValue" content="<?php echo $rating; ?>">
				</span>
				<?php } ?>
				
				</span>
				<?php } ?>
            
			
<!--        <h1 class="visible-phone"><?php //echo $heading_title; ?></h1>-->
        <div class="row-fluid">
            <!--content-block starts here-->
            <div class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
                <?php } ?>
            </div>
            
            <div class="span12 content-block bulk_show ">
                <div class="show_hide_box_product">
                     <span class="whole-sale-heading">For wholesale pricing</span>
                     <span class="whole_sale_login whole_sale_login_product"><a href="javascript:void(0)">Log In</a></span>
                     <span class="or_group"> OR</span>
                     <h1><a href="account/register">Sign Up</a></h1>
                     <p class="text-contact">For Quotes on Significantly Larger Quantities, 
                        Please <a  class ="contact_group" href="<?php echo $contact ?>"> Contact Us</a></p>
                  </div>
                <!--product block starts here-->
                <div class="product-block span12">
                    <div class="row-fluid">
                       <!--- <div class="image-block span7">
                            <?php if ($thumb) { ?>
                            <div class="img-box"><a rel="adjustY:-4, tint:'#000000',tintOpacity:0.2, zoomWidth:360" href="<?php echo $popup; ?>" title="<?php echo $custom_imgtitle; ?>" class="colorbox cloud-zoom"><img src="<?php echo $thumb; ?>" title="<?php echo $custom_imgtitle; ?>" alt="<?php echo $custom_alt; ?>" id="image" /></a></div>
                            <?php } else { ?>
                            <div class="img-box"><img class="img-responsive" src="<?php echo HTTP_SERVER; ?>catalog/view/theme/default/image/no_product.jpg" title="<?php echo $custom_imgtitle; ?>" alt="<?php echo $custom_alt; ?>" /></div>
                            <?php } if ($images) { ?>
                            <div class="clearfix img-box2">
                                <?php foreach ($images as $image) { ?>
                                <div class="space">
                                <?php if ($thumb) { ?>
                                <a href="<?php echo $image['popup']; ?>" title="<?php echo $custom_imgtitle; ?>" class="changeMain "><img src="<?php echo $image['thumb']; ?>" title="<?php echo $custom_imgtitle; ?>" alt="<?php //echo $heading_title; ?>" /></a>
                                <?php } else { ?>
                                <img class="img-responsive" src="<?php echo HTTP_SERVER; ?>catalog/view/theme/default/image/no_product.jpg" title="<?php echo $custom_imgtitle; ?>" alt="<?php //echo $heading_title; ?>" width="74px" height="74px" />
                                <?php } ?>
                                </div>
                                <?php } ?>
                            </div>

                            <?php } ?>
                        </div> -->
                        
                               <div class="image-block span7">
                            <?php if ($thumb) { ?>
                                <div class="img-box"><a class="cloud-zoom colorbox" rel="adjustY:-4, tint:'#000000',tintOpacity:0.2, zoomWidth:360" href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>"><img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" id="image" /></a></div>
                            <?php } else { ?>
                                <div class="img-box "><img src="<?php echo HTTP_SERVER; ?>catalog/view/theme/default/image/no_product.jpg" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" id="image" /></div>
                                <?php } if ($images) { ?>
                                <ul class="thumbnails">
                                    <?php if ($images) { ?>
                                    <?php foreach ($images as $image) { ?>
                                    <li class="image-additional"><a class="thumbnail" href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>"> <img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a></li>
                                    <?php } ?>
                                    <?php } ?>
                                  </ul>
                               <!--- <div class="clearfix img-box2">
                                    <?php foreach ($images as $image) { ?>
                                        <a href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>" class="changeMainGroup thumbnail"><img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>
                                <?php } ?>
                                </div>--->

<?php } ?>
                        </div>
                        <!---right block-->
                        
                        <div class="span5 detail m0">
                            <div style="float:left;width:100%; margin-bottom: 15px;">
                                <div class="product_heading_group">
                                    <h1 class="visible-desktop visible-tablet" id="product_name">
                                        <?php echo $heading_title; ?>
                                    </h1>
                                    <?php if ($review_status) { ?>
                                        <div class="review_count" id="review_status">
                                            <span class="flr"><img src="catalog/view/theme/default/image/stars-<?php echo $rating; ?>.png" alt="<?php echo $reviews; ?>" />
                                                <a id="tabs2" onclick="$('a[href=\'#tab-review\']').trigger('click');">(<?php echo $reviews; ?>)</a>
                                                <a onclick="$('a[href=\'#tab-review\']').trigger('click');"><?php //echo $text_write; ?></a>
                                            </span>
                                        </div>
                                    <?php } ?>  
                                </div>
                                <div class="stock_status">
                                    <span id="show_stock"><?php //echo $stock; ?></span>
                                    <div class="help_tool_image">
                                        <?php if ($quantity <= 0) {  ?>
                                           <?php if($stock == '2-3 Days') : ?> <span class='two_three_days'></span>
                                           <?php elseif($stock == 'Pre-Order') : ?> <span class='pre_order'></span>
                                           <?php elseif($stock == 'In Stock') : ?> <span class='inofstock'></span>
                                           <?php else : ?> <span class='outofstock'></span> <?php endif; ?>
                                            
                                            	<?php
                                                
                                                	if(!empty($frontend_date_available) && $frontend_date_available > date("Y-m-d") && $date_sold_out < $frontend_date_available )
                                                    {
                                                    	echo "<div class='date_available'>Estimated Arrival: {$frontend_date_available} </div>";
                                                    }
                                            		//echo '<pre>';print_r($estimate_deliver_days);exit;
                                            		//echo $estimate_deliver_time;
                                            		$datetoday = date("Y-m-d");
                                            		//echo $datetoday;
                                            		if($datetoday > $date_ordered){
														//echo "<span class='stocktext'>Will take at least (".$estimate_deliver_time.") days to come back in stock" . "</span>";
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
                                            		
                                            		
                                            		/*if($datetoday > $date_ordered)
                                            		{
														echo "<span class='stocktext'>Will take at least (".$estimate_deliver_time.") days to come back in stock" . "</span>";
													}
													else
													{
														if($date_ordered != "0000-00-00")
														{
															$availabledate = date("Y-m-d",strtotime($date_ordered . " +".$estimate_deliver_time." days"));
															echo "<span class='stocktext'>Will be available on '.$date_ordered.' or around " . date( "M d", strtotime($availabledate) ) . "</span>";
														}
													}*/
                                            	?>
<!--                                            <img id="help_out_stock_tooltip" src="admin/view/image/information.png" alt="" title ="This item is currently out of stock, but you can add this item to your wishlist and we will notify you when they become available." class="help_tool_out_img"/>-->
                                        <?php } else { ?>
                                            <span class='inofstock'></span>
<!--                                            <img id="help_out_stock_tooltip" src="admin/view/image/information.png" alt="" title ="Product availability is updated every few days and may not be current. If we have sold out since the last update, we will contact you before shipping your order." class="help_tool_out_img"/>-->
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="top-gap">
                                <span class=" flt number_group_product "><strong class="item_numbersss">Item Numbers: </strong><span id="item_number"><?php echo $sku; ?></span></span>
                              
                            </div>
                            
                            <p class="clearfix"></p>
                            <div class="clearfix"></div>
                            <input type="hidden" id="base_price_input" value="<?php echo isset($base_price) ? $base_price : '0' ?>">


                            <?php if ($logged) { ?>
                            <p class="top-gap"><strong class="price_new">Price :</strong></p>
                            <?php } else { ?>
                            <p class="top-gap"><strong class="price_new">Price :</strong></p>
                            <?php } ?>
                            <p class="price_group_prod">
                                <?php if (!$special) { ?>
                                    <?php
                                    echo "<span class='price-new'>" . $price . " "
                                    . "</span> <span class='price_red'> / </span> "
                                    . "<span class = 'price_red' id='quantity_span'></span> "
                                  . "<span class='price_red' id='unit_dis'>" . $unit_singular . "</span>";
                                    ?>
                                <?php } else { ?>
                                    <span class="cossed-price">
                                        <?php echo $price; ?>
                                    </span> 
                                    <span data-price="<?php echo $special; ?>" class="price price-new">
                                        <?php echo $special; ?>
                                    </span>
                                <?php } ?>

                            </p>
                            <br><br>
                            <span id='converstion_string_display' style="display:none; background-color:#456a9e;margin-left: 75px; color:#FFFFFF;"></span>
                                <input id="plural_unit" type="hidden" value ="<?php echo $unit_plural; ?>">
                                
                           <?php if(empty($unit_datas) && $special){ ?>
                                 <span class='price_red' id='unit_dis'><?php echo $unit_singular; ?></span>
                       <?php } else { ?>
                                 
                     
                                  
                       <?php } ?>
                                            
                                
                                 <input type="hidden" class="unit_conversion_values" name ="unit_conversion_values" value="">

                               <!-- price and taxes -->
                            <div class="price">
                               <?php if ($points) { ?>
                                    <span class="reward"><small><?php echo $text_points; ?> <?php echo $points; ?></small></span><br />
                                <?php } ?>
                                <br />

                            </div>
                          <?php if ($discounts&&$logged||$logged) { ?>
                             <div  style="z-index: 999999999999999;"  class="bulk-product-tex">
                                <span class="bulk_price_image_product"> </span>
                                <span class="bulk_price_image_minus_product" style="display:none;"></span>
                                <span class="text-bulk text-bulk-price text_bulk_product"><a style="cursor: pointer;" data-toggle="modal" data-target="#seeBulkPrice">See Bulk Price</a></span>
                                
                            </div>
                              <?php }else{
                    ?>
                          <div style="z-index: 999999999999999;" class="bulk-product-tex">

                                <span class="bulk_price_image"> </span>
                                <span class="bulk_price_image_minus"></span>
                                <span class=""><a id="bulkcontent" style="cursor: pointer;"  data-container="body" data-toggle="popover" data-placement="bottom">See Bulk Price</a></span>

                            </div>
                             <div id="content-bulk">
        
                            
                            <p>
                            For Wholesale Pricing
                            </p>
                            <a href="javascript:void(0);" onclick="$('#link_login_desktop').click()" style="width: 90%;" type="button" class="btn btn-info">Login</a>
                            <p class="text-center">Or</p>
                            <h5 class="text-center"><a href="account/register">Signup</a></5>
                            <p>For Quotes on Significantly Larger Qunatities. Please <a href="index.php?route=information/contact">Contact Us</a></p>
                                </div>
                                <style type="text/css">
                                    #content-bulk{
                                display:none;
                                }
                                </style>
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
                    <?php
                } ?>

                   <script type="text/javascript">
                    $(document).ready(function(){
                        $('#seeBulkPrice').on('shown', function() {
                           
                           var datavalue= $("#get-unit-data option:selected").attr('name');
                               if(datavalue==null){
                            datavalue='';
                        }else{
                            datavalue=datavalue
                        }
                         var unitname=  $("#unit_dis").html();
                         
                            $(".scale-quantity").after( "<span class='current_unitnames'>   <b>"+ datavalue +"</b> </span>");
                                });
                         $('#seeBulkPrice').on('hidden', function() {
                            $(".current_unitnames").remove();
                          });
                    });
                </script>
                            <!-- discount box product start here-->
                              <!-- Modal -->
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
                                   <div class="login-box-desk discount_product_group_product" style="display:none;">
                        
                        <?php if ($discounts) { ?>

                        <div class="price-scale price_scale_group_product">
                            <p class="he group_he">Bulk Pricing and Quantity Discounts</p>
<!--                            <div class="information_image discount_box">
                                <img class="option_tooltip" src="admin/view/image/information.png" alt="" class="pay-img" title="We offer discounts for wholesale customers depending on the quantity of each item ordered. Discounts are automatically applied in your cart."/>
                            </div> -->
                            <p class="mid-group">
                                <span class="flt">Quantity</span>
                                <span class="flr scale-group">Price</span></p>
                            <ul class="update_discount_price_group">
                                <li>

                                    <span class="scale-quantity"><?php echo "Non-Wholesale"; ?></span>

                                    <span class="scale-price"><?php echo $price_without_discount; ?></span>

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
                                    <span class="scale-price">
                                        <?php echo $discount['price']; ?>
                                    </span>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>

                        <?php } ?>
                    </div>
                          <?php  }  ?>
                            
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                              </div>
                            </div>

                          </div>
                        </div>
                            
                            <!-- Payment Profiles -->

                            <?php if ($profiles): ?>
                            <div class="option">
                                <h2><span class="required">*</span><?php echo $text_payment_profile ?></h2>
                                <br />
                                <select name="profile_id">
                                    <option value=""><?php echo $text_select; ?></option>
                                    <?php foreach ($profiles as $profile): ?>
                                    <option value="<?php echo $profile['profile_id'] ?>"><?php echo $profile['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <br />
                                <br />
                                <span id="profile-description"></span>
                                <br />
                                <br />
                            </div>
                            <?php endif; ?>


                            <!-- Payment Profiles -->

                            <!-- Options Type -->
                            <?php //pr($options); die;?>
                            <?php if ($options) { ?>
                            <div class="options">
                                <!--                            <h2><?php echo $text_option; ?></h2>-->
                                <?php foreach ($options as $option) { ?>

                                <!-- Custom Radio Options -->
                                <?php if ($option['type'] == 'radio') {
                                ?>
                                <div id="option-<?php echo $option['product_option_id']; ?>" class="option option_custom length_content ig_<?php echo str_replace(' ', '', $option['name']); ?>">
                                    <?php if ($option['required']) { ?>
                                    <span class="required option_label">* <b><?php echo $option['name']; ?>:</b></span>
                                    <?php } else {
                                    ?>
                                    <span class="option_label"><b><?php echo $option['name']; ?>:</b></span>
                                    <?php } ?>
                                    <div class="option_container">
                                        <?php foreach ($option['option_value'] as $option_value) { ?>
                                        <?php if ($option_value['quantity'] <= 0) { ?>
            <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" <?php if ($option_value['default']) { echo 'selected="selected" checked="checked" onclick="$(this).parent().change();"'; } ?> id="option-value-<?php echo $option_value['product_option_value_id']; ?>"  /> - <?php echo $option_out_of_stock; ?>
            <?php } else { ?>
            <input type="radio" name="option[<?php echo $option['product_option_id'];?>]" value="<?php echo $option_value['product_option_value_id'];?>" id="option-value-<?php echo $option_value['product_option_value_id'];?>" />
            <?php } ?>
<?php if ($option_value['quantity'] <= 0) { ?>
             - <?php echo $option_out_of_stock; ?>
            <?php } else { ?>
            <?php echo ""; ?>
            <?php } ?>
                                        <?php if ($option_value['quantity'] <= 0) { ?>
            <label style='color:grey;' for="option-
            <?php } else { ?>
            <label for="option-
            <?php } ?>value-<?php echo $option_value['product_option_value_id']; ?>" data-val="<?php echo $option_value['name']; ?>" ><?php echo $option_value['name']; ?>
                                            <!--                                        <?php if ($option_value['price']) { ?>
                                                                                                                                                                                    (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                                            <?php } ?>-->
                                        </label>
                                        <?php } ?>
                                    </div>
                                </div>
                                <?php } ?>
                                <!-- Custom Radio Options -->

                                <?php if ($option['type'] == 'select') { ?>
                                <?php 
                                $UnitArry = explode(' ', $option['name']);
                                if (strtolower(end($UnitArry)) != "units") {
                                ?>
                                <div id="option-<?php echo $option['product_option_id']; ?>" class="option ig_<?php echo str_replace(' ', '', $option['name']); ?>">
                                    <?php if ($option['required']) { ?>
                                    <span class="required">*</span>
                                    <?php } ?>
                                    <b><?php echo $option['name']; ?>:</b>
                                    <select name="option[<?php echo $option['product_option_id']; ?>]">
                                        <?php
                                        echo $option['metal_type'];?>
                                        <?php if ($option['metal_type'] > 1) {
                                        ?>
                                        <option value=""><?php echo $text_select; ?></option>
                                        <?php }
                                        ?>
                                        <?php foreach ($option['option_value'] as $option_value) { ?>
                                        <?php if ($option_value['quantity'] <= 0) { ?>
                                        <option data-option_value_image="<?php if(isset($option_value['image']) && !empty($option_value['image']) && !strpos($option_value['image'], 'no_image')){ echo $option_value['image'];}?>" data-price="<?php echo ($option_value['price2'] ? $option_value['price2'] : '' )?>" value="<?php echo $option_value['product_option_value_id']; ?>" <?php if ($option_value['default']) { echo 'selected="selected" checked="checked" onclick="$(this).parent().change();"'; } ?> qty="<?php echo $option_value['quantity'];?>"><?php echo $option_value['name']; ?> - <?php echo $option_out_of_stock; ?>
                                        <?php } else { ?>
                                        <option data-option_value_image="<?php if(isset($option_value['image']) && !empty($option_value['image']) && !strpos($option_value['image'], 'no_image')){ echo $option_value['image'];}?>" data-price="<?php echo ($option_value['price2'] ? $option_value['price2'] : '' )?>" value="<?php echo $option_value['product_option_value_id'];?>" qty="<?php echo $option_value['quantity'];?>"><?php echo $option_value['name'];?>
<?php if ($option_value['quantity'] <= 0) { ?>
             - <?php echo $option_out_of_stock; ?>
            <?php } else { ?>
            <?php echo ""; ?>
            <?php } ?>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                                <?php
                                }
                                }
                                ?>

                                <?php if ($option['type'] == 'checkbox') {?>
                                            <div id="option-<?php echo $option['product_option_id']; ?>" class="option product_checkboxes">
                                                <?php if ($option['required']) { ?>
                                                    <span class="required">* <b><?php echo $option['name']; ?>:</b></span><br/>
                                                <?php } ?>
                                <?php foreach ($option['option_value'] as $option_value) { ?>
                                <?php if ($option_value['quantity'] <= 0) { ?>
            <input type="checkbox" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" <?php if ($option_value['default']) { echo 'selected="selected" checked="checked" onclick="$(this).parent().change();"'; } ?> id="option-value-<?php echo $option_value['product_option_value_id']; ?>"  /> - <?php echo $option_out_of_stock; ?>
            <?php } else { ?>
            <input type="checkbox" name="option[<?php echo $option['product_option_id'];?>][]" value="<?php echo $option_value['product_option_value_id'];?>" id="option-value-<?php echo $option_value['product_option_value_id'];?>" />
            <?php } ?>
                                <?php if ($option_value['quantity'] <= 0) { ?>
            <label style='color:grey;' for="option-
            <?php } else { ?>
            <label for="option-
            <?php } ?>value-<?php echo $option_value['product_option_value_id']; ?>" ><?php echo $option_value['name']; ?>
                                    <?php if ($option_value['price']) { ?>
                                    (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                                    <?php } ?>
                                </label><br/>
<?php if ($option_value['quantity'] <= 0) { ?>
             - <?php echo $option_out_of_stock; ?>
            <?php } else { ?>
            <?php echo ""; ?>
            <?php } ?>
                                <?php } ?>
                            </div>
                            <?php } ?>
                            <?php if ($option['type'] == 'image') {?>
                                            <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
                                                <?php if ($option['required']) { ?>
                                                    <span class="required">* <b><?php echo $option['name']; ?>:</b></span>
            <?php } ?>

                            <table class="option-image">
                                <?php foreach ($option['option_value'] as $option_value) { ?>
                                <tr>
                                    <td style="width: 1px;"><input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" <?php if ($option_value['default']) { echo 'selected="selected" checked="checked" onclick="$(this).parent().change();"'; } ?> id="option-value-<?php echo $option_value['product_option_value_id']; ?>" /></td>
                                    <td><?php if ($option_value['quantity'] <= 0) { ?>
            <label style='color:grey;' for="option-
            <?php } else { ?>
            <label for="option-
            <?php } ?>value-<?php echo $option_value['product_option_value_id']; ?>"><img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" /></label></td>
                                    <td><?php if ($option_value['quantity'] <= 0) { ?>
            <label style='color:grey;' for="option-
            <?php } else { ?>
            <label for="option-
            <?php } ?>value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
                                            <?php if ($option_value['price']) { ?>
                                            (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                                            <?php } ?>
<?php if ($option_value['quantity'] <= 0) { ?>
             - <?php echo $option_out_of_stock; ?>
            <?php } else { ?>
            <?php echo ""; ?>
            <?php } ?>
                                        </label></td>
                                </tr>
                                <?php } ?>
                            </table>
                        </div>
                        <?php } ?>
                        <?php if ($option['type'] == 'text') { ?>
                        <div id="option-<?php echo $option['product_option_id']; ?>" class="option product_textfield">
                            <?php if ($option['required']) { ?>
                            <span class="required">* <b><?php echo $option['name']; ?>:</b></span>
                            <?php } ?>

                            <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" />
                        </div>
                        <?php } ?>
                        <?php if ($option['type'] == 'textarea') { ?>
                        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
                            <?php if ($option['required']) { ?>
                            <span class="required">* <b><?php echo $option['name']; ?>:</b></span>
                            <?php } ?>

                            <textarea name="option[<?php echo $option['product_option_id']; ?>]" cols="40" rows="5"><?php echo $option['option_value']; ?></textarea>
                        </div>
                        <?php } ?>
                        <?php if ($option['type'] == 'file') { ?>
                        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
                            <?php if ($option['required']) { ?>
                            <span class="required">* <b><?php echo $option['name']; ?>:</b></span>
                            <?php } ?>

                            <input type="button" value="<?php echo $button_upload; ?>" id="button-option-<?php echo $option['product_option_id']; ?>" class="button">
                            <input type="hidden" name="option[<?php echo $option['product_option_id']; ?>]" value="" />
                        </div>
                        <?php } ?>
                        <?php if ($option['type'] == 'date') { ?>
                        <div id="option-<?php echo $option['product_option_id']; ?>" class="option product_textfield">
                            <?php if ($option['required']) { ?>
                            <span class="required">* <b><?php echo $option['name']; ?>:</b></span>
                            <?php } else { ?>
                            <b><?php echo $option['name']; ?>:</b>
                            <?php } ?>
                            <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="date" />
                        </div>
                        <?php } ?>
                        <?php if ($option['type'] == 'datetime') { ?>
                        <div id="option-<?php echo $option['product_option_id']; ?>" class="option product_textfield">
                            <?php if ($option['required']) { ?>
                            <span class="required">* <b><?php echo $option['name']; ?>:</b></span>
                            <?php } else { ?>
                            <b><?php echo $option['name']; ?>:</b>
                            <?php } ?>
                            <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="datetime" />
                        </div>
                        <?php } ?>
                        <?php if ($option['type'] == 'time') { ?>
                        <div id="option-<?php echo $option['product_option_id']; ?>" class="option product_textfield">
                            <?php if ($option['required']) { ?>
                            <span class="required">*</span>
                            <?php } ?>
                            <b><?php echo $option['name']; ?>:</b><br />
                            <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="time" />
                        </div>
                        <?php } ?>
                        <?php } ?>

                    </div>

                    <?php } ?>
                          
                         <!--add to cart box-->
          
                
                 <div  class="price_grouped_product_box">
                    <div class="quantity_box_product_group">
                                <p class=" entry flt group_qty_box">
                            <label class="group_product_text_qty"><?php echo $text_qty; ?></label>
                            <input type="text" class="quantity qty_group_product" name="quantity" size="2" value="<?php echo $minimum; ?>" />
                            <input type="hidden" name="original_price" value="<?php echo $old_price; ?>" >
                            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" >
                                                  </p>
                          <?php if(!empty($unit_datas)){ ?>
                         <div class="ig_MetalType ig_Units units_grouped">
                                <select class="get-unit-data id_convert_long" id="get-unit-data">
                                    <?php foreach ($unit_datas as $unit_data) { ?>
                                    <option name="<?php echo $unit_data['name']; ?>" data-value ="<?php echo $unit_data['unit_conversion_product_id']; ?>" value="<?php echo $unit_data['convert_price']; ?>">
                                        <?php echo $unit_data['name']; ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                                  <div class="information_image information_image_unit">
                        <img class="option_tooltip" src="admin/view/image/information.png" alt=""/>
                    </div>   
                       <?php }?> 
                           <input type="hidden" id="default_conversion_value_name" name ="default_conversion_value_name" value="">
                          <div class="add_img_group">
                           <img src="catalog/view/theme/default/img/satiscfaction_image.png" alt="" class="money-back"/>
                            <!--                            <a href="#" class="qty-cart">Add to cart <img src="img/cart.png" alt=""/></a>-->
                            <img src="catalog/view/theme/default/img/paypal_group.png" alt="" class="pay-img"/>
                          </div>
                                
                            </div>
                                                  <div class="pay-100 cart_product_group"> <input type="button" id="button-cart-2" class="qty-cart button-cart-2" value="Add to Cart" />
                                                       <span class="links link_product">
			<a onclick="addToWishList('<?php echo $product_id; ?>'); <?php if (isset($ga_tracking_type) && $ga_tracking_type == 2) { echo "ga('send', 'event', 'Product Page', 'Add to Wishlist', '".htmlspecialchars($heading_title,ENT_QUOTES)."');";} else{echo "_gaq.push(['_trackEvent', 'Product Page', 'Add to Wishlist', '".htmlspecialchars($heading_title,ENT_QUOTES)."']);"; } ?>">
			<?php echo $button_wishlist; ?></a>
                                        
			<a id="compare_pro" onclick="addToCompare('<?php echo $product_id; ?>'); <?php if (isset($ga_tracking_type) && $ga_tracking_type == 2) { echo "ga('send', 'event', 'Product Page', 'Add to Compare', '".htmlspecialchars($heading_title,ENT_QUOTES)."');";} else{echo "_gaq.push(['_trackEvent', 'Product Page', 'Add to Compare', '".htmlspecialchars($heading_title,ENT_QUOTES)."']);"; } ?>">
			<?php echo $button_compare; ?></a></span>
                                
                                </div>
                        <div class="clearfix"></div>
                       
                           

                            </div>
                
                <div class="information_right">
                    <?php echo $column_right; ?>
                </div>
             
              
                    <!-- Block For Unit -->
                </div><div class="row-fluid">
                    <div class="span12">
                        <div class="ui_tabs products-uitabs">
                            <div id="tabs" class="htabs">
                                <a href="#tab-description"><?php echo $tab_description; ?></a>
<?php if($youtube_embed_tab && $this->data['youtube_videos']) { ?><a href="#tab-youtube"><?php echo $youtube_embed_tab_text; ?></a><?php } ?>
                                <!--<?php if ($attribute_groups) { ?>
                                    <a href="#tab-attribute"><?php echo $tab_attribute; ?></a>
                                <?php } ?>-->
                                <?php if ($review_status) { ?>
                                <a href="#tab-review">
                                    <?php echo $tab_review; ?></a>
                                <?php } ?>
                                <a href="#tab-qa"><?php echo $tab_qa; ?> </a>
                                
                            </div>
                            <div id="tab-description" class="tab-content tab_content_product">
                                <div class="tab_contents">
                                    <div class="iframe-rwd">
                                        <?php echo $description; ?>
                                    </div>
                                    <?php if ($attribute_groups) { ?>

                                    <table class="attribute">
                                        <?php foreach ($attribute_groups as $attribute_group) { ?>
                                        <thead>
                                            <tr>
                                                <td class="background_td" colspan="2"><?php echo $attribute_group['name']; ?></td>
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

                                    <?php } ?>
                                </div>
                            </div>



                            <?php if ($review_status) { ?>
                            <div id="tab-review" class="tab-content tab_content_product ">
                                <div class="product_reviews">
                                    <div id="review"></div>
                                    <div class="review_main">
                                        <div class="contact-form">
                                            <h2 id="review-title"><?php echo $text_write; ?></h2>
                                            <!--                                <b><?php echo $entry_name; ?></b><br />-->
                                            <p>
                                                <img alt="Name" src="catalog/view/theme/default/img/name-icon.jpg">
                                                <input placeholder="Enter your Name" type="text" name="name" value="" />
                                            </p>
                                            <p>
                                                <img alt="Message" src="catalog/view/theme/default/img/message-icon.jpg">
                                                <textarea  placeholder="Enter your review" name="text" cols="40" rows="8" style="width: 98%;"></textarea>
                                            </p>
                                            <!--                                    <span style="font-size: 11px;"><?php echo $text_note; ?></span><br />-->
                                            <div class="product_ratings">
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
                                                &nbsp;<span><?php echo $entry_good; ?></span>
                                            </div>
                                            <!--                                                <div class="product_captchas">
                                                                                                                                        <b><?php //echo $entry_captcha;   ?></b><br />
                                                                                                <p><input placeholder="Enter the captcha" type="text" name="captcha" value="" /></p>
                                                                                                <img src="index.php?route=product/product/captcha" alt="" id="captcha" /><br />
                                                                                                <br />
                                                                                            </div>-->
                                            <div class="buttons">
                                                <div class="right"><a id="button-review" class="button"><?php echo "Submit Review"; ?></a></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="tab-qa" class="tab-content product-tabs-content std tab_content_product">
                                <div class ="tab_contents">
                                    <div id="qa"><?php echo $qas; ?></div>
                                    <h2 id="qa-title"><?php echo $text_ask; ?></h2>
                                    <span style="float:left; padding-right:2em;">
                                        <b><?php echo $entry_name; ?></b><br />
                                        <input class="input-text" type="text" name="questioner" value="<?php echo $qa_name; ?>" />
                                    </span>
                                    <?php if ( $qa_notify = true ) { ?>
                                    <span style="float:left; padding-right:2em;">
                                        <b><?php echo $entry_email; ?></b><br />
                                        <input class="input-text" type="text" name="q_email" value="<?php echo $qa_email; ?>" />
                                    </span>
                                    <?php } ?>
                                    <br style="clear:both;" />
                                    <br />
                                    <b><?php echo $entry_question; ?></b>
                                    <textarea name="question" style="width: 98%;" rows="8"></textarea>
                                    <span><input type="checkbox" id="email_copy" class="input-checkpox" name="email_copy" value="1">Email a copy to yourself</span><br>
                                    <span style="font-size: 11px;"><?php echo $text_note; ?></span><br />
                                    <!--                                        <br />
                                                                            <b><?php if(isset($entry_captcha)) { echo  $entry_captcha; } ?></b><br />
                                                                            <input class="input-text" type="text" name="q_captcha" value="" autocomplete="off" />
                                                                            <br />
                                                                            <br />
                                                                            <img src="index.php?route=product/product/captcha" id="q_captcha" /><br />
                                                                            <br />-->
                                    <?php echo $captcha; ?>
                                    <br>
                                    <div class="action">
                                        <div class="buttons">
                                            <div class="left">
                                                <a id="button-qa" class="product-also-add-to-cart button" onclick="askQuestion();">
                                                    <?php echo "Submit Question"; ?></a>
                                            </div>
                                        </div>
                                    </div>

                                    
                                </div>
                            </div>
                            <?php } ?>
                            <!--                <?php if ($tags) { ?>
                                                                                                                                            <div class="tags"><b><?php echo $text_tags; ?></b>
                                <?php for ($i = 0; $i < count($tags); $i++) { ?>
                                    <?php if ($i < (count($tags) - 1)) { ?>
                                                                                                                                                                                                        <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>,
                                    <?php } else { ?>
                                                                                                                                                                                                        <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>
                                    <?php } ?>
                                <?php } ?>
                                                                                                                                            </div>
<?php } ?>-->
						<!-- Related Article/Video Blog -->
                        <?php if($product_articles) : ?>
                            <h2>Related Article/Video</h2>
                            <div class="row-fluid">
                            	<?php foreach ( $product_articles as $product_article ) : ?>
                                    <div class="span3">
                                        <img src="<?=$product_article['image']?>" width="150" height="200" alt="<?=$product_article['post_title']?>"/>
                                        <p><a href="<?=$product_article['guid']?>" style="display:block;"><?=$product_article['post_title']?></a></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <!-- /Related Article/Video Blog -->

<!-- Works well with -->   
    <?php if(true) : ?>
    <?php //echo "<pre>";print_r($wproducts);echo "</pre>"; ?>
    <div id="wwell_prods" style="display:<?php echo ($wproducts)?'block':'none' ?>">
    <div class="rp_heading">Works well with</div>
            <div class="related-products jcarousel-wrapper" style="">
            <div id="wwell" class="box-product ig_slider jcarousel crsl-items-wwell" data-navigation="NAV-ID" style="width: 90%; overflow: hidden;">
                <ul>
            
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
                <?php if($i >= 4)    break; ?>
                <li>
                    <div class="name">
                        <a href="<?php echo $rproduct['href']; ?>" title="<?php echo $rproduct['description']; ?>">
                            <?php echo $rproduct['name']?></a></div>

                    <?php if ($rproduct['thumb']) { ?>
                        <div class="img-box"><a href="<?php echo $rproduct['href'] ?>"><img src="<?php echo $rproduct['thumb'] ?>" title="<?php echo $rproduct['name']; ?>" alt="<?php echo $rproduct['name']; ?>"></a></div>
                    <?php } else { ?>
                        <div class="img-box "><img src="<?php echo HTTP_SERVER; ?>catalog/view/theme/default/image/no_product.jpg" title="<?php echo $heading_title; ?>" alt="<?php echo $rproduct['name']; ?>" /></div>
                    <?php } ?>
                    
                    <div class="price">
                        <span><?php echo ($rproduct['special'])?$rproduct['special']:$rproduct['price']; ?></span>
                        <span class="unit-products"> per <?php echo $rproduct['unit'];?></span>
                    </div>
            
                        <div class="cart"><input id="button-cart" value="Add to Cart" class="button button-cart" data-product_id="<?php echo $rproduct['product_id'];?>" type="button" onClick="javascript:addToCart('<?php echo $rproduct['product_id'];?>,1)"></div>
                </li>
                <?php $i++; ?>

                <?php endforeach; ?>

                <?php if ($flag) { ?>
                    <li>
                    <div class="name">
                        <a href="" title="See All">
                            </a></div>

                    
                        <div class="img-box "><img src="<?php echo HTTP_SERVER; ?>catalog/view/theme/default/image/seeall.png" height="200" width="200" title="" alt="See all" /></div>
                    
                    <div class="price">
                        <span>SEE ALL</span>
                        <span class="unit-products"></span>
                    </div>
            
                    
                </li>
                <?php } ?>
                
                            
                    </ul>
            </div>
            <a href="#" class="jcarousel-control-prev">&lsaquo;</a>
            <a href="#" class="jcarousel-control-next">&rsaquo;</a>

            
        </div>
    </div>
        <?php endif; ?>


<!-- Related Products -->   
    <?php if(true) : ?>
    <div id="related_prods" style="display:<?php echo ($products)?'block':'none' ?>">
    <div class="rp_heading">Related Products</div>
            <div class="related-products jcarousel-wrapper" style="">
            <div id="related" class="box-product ig_slider jcarousel crsl-items-related" data-navigation="NAV-ID" style="width: 90%; overflow: hidden;">
                <ul>

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
                <?php if($i >= 4)    break; ?>
                
                <li>
                    <div class="name">
                        <a href="<?php echo $rproduct['href']; ?>" title="<?php echo $rproduct['description']; ?>">
                            <?php echo $rproduct['name']?></a></div>

                    <?php if ($rproduct['thumb']) { ?>
                        <div class="img-box"><a href="<?php echo $rproduct['href'] ?>"><img src="<?php echo $rproduct['thumb'] ?>" title="<?php echo $rproduct['name']; ?>" alt="<?php echo $rproduct['name']; ?>"></a></div>
                    <?php } else { ?>
                        <div class="img-box "><img src="<?php echo HTTP_SERVER; ?>catalog/view/theme/default/image/no_product.jpg" title="<?php echo $heading_title; ?>" alt="<?php echo $rproduct['name']; ?>" /></div>
                    <?php } ?>
                    
                    <div class="price">
                        <span><?php echo ($rproduct['special'])?$rproduct['special']:$rproduct['price']; ?></span>
                        <span class="unit-products"> per <?php echo $rproduct['unit'];?></span>
                    </div>
            
                        <div class="cart"><input id="button-cart" value="Add to Cart" class="button button-cart" data-product_id="<?php echo $rproduct['product_id'];?>" type="button" onClick="javascript:addToCart('<?php echo $rproduct['product_id'];?>,1)"></div>
                </li>
                <?php $i++; ?>

                <?php endforeach; ?>

                <?php if ($flag) { ?>
                    <li>
                    <div class="name">
                        <a href="" title="See All">
                            </a></div>

                    
                        <div class="img-box "><img src="<?php echo HTTP_SERVER; ?>catalog/view/theme/default/image/seeall.png" height="200" width="200" title="" alt="See All" /></div>
                    
                    <div class="price">
                        <span>SEE ALL</span>
                        <span class="unit-products"></span>
                    </div>
            
                    
                </li>
                <?php } ?>
                
                            
                    </ul>
            </div>
            <a href="#" class="jcarousel-control-prev">&lsaquo;</a>
            <a href="#" class="jcarousel-control-next">&rsaquo;</a>
        </div>
    </div>
        <?php endif; ?>

            



                        </div>
                    </div>
                </div>
            </div>
        </div>
       
       
            </div>
      
        <!--tabs starts here-->
        <div class="group_product_bottom span12">
        <?php echo $content_bottom; ?>
        </div>
    </div>
    <!--content-block end here-->
</div>
</div>
<!--mid-block starts here-->
<script type="text/javascript" src="catalog/view/javascript/jquery/ajaxupload.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script>
    $('#qa .pagination a').live('click', function() {
            var href = this.href;
            $('#qa').slideUp('slow', function() {
                        $('#qa').load(href, function() {
                            $('#qa').slideDown('slow');
                        });
            });
            return false;
    });
    <?php if($preload != 1) { ?>
        $('#qa').load('index.php?route=product/product/question&product_id=<?php echo $product_id; ?>');
    <?php } ?>
    function askQuestion() {
        var email_copy = $('#email_copy').is(':checked');
        $.ajax({
                    type: 'POST',
                            url: 'index.php?route=product/product/ask&product_id=<?php echo $product_id; ?>',
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
                            }
        });
    }
    <?php if($logged){ ?>   
   $(document).on('click', '.bulk_price_image_product, .bulk_price_image_minus_product,.text_bulk_product', function(){
    $('.discount_product_group_product').toggle();
    if($(".bulk_price_image_minus_product").css('display') == 'none'){
        $(".bulk_price_image_minus_product").show();
        $(".bulk_price_image_product").hide();
        $(".text_bulk_product").html("Hide Bulk Price");
    }else{
        $(".bulk_price_image_minus_product").hide();
        $(".bulk_price_image_product").show();
        $(".text_bulk_product").html("See Bulk Price");
    }
   });
<?php } ?>

</script>
<script type="text/javascript"><!--
$(document).ready(function() {
    $('.fancySelect').fancySelect();
    $('.options select').fancySelect();
    /*for address ticket # 000251 */
    $('.thumbnails').magnificPopup({
        type:'image',
        delegate: 'a',
        gallery: {
            enabled: true
        }
    });

    //$('.crsl-items').carousel({visible: 2, itemMinWidth: 130});
    $('.crsl-items').myjcarousel({
                vertical: false,
                visible: 2,
                scroll: 1,
                wrap: "null",
                contentWidth: 300,
                itemFallbackDimension: 300
            });
    /* ends here ***************/
   $('.changeMain').live('hover',function(event){
      event.preventDefault();
       var src = $(this).attr('href');
       var actw = $('#image').width();
       $('#image').attr('src', src);
       $('#image').parent().attr('href', src);
       $('#image').css({'width': actw + 'px'});
       $('.cloud-zoom').CloudZoom();

        $('.thumbnails').magnificPopup({
        type:'image',
        delegate: 'a',
        gallery: {
            enabled: true
        }
    });
       
   });
   
   var default_conversion_value_name = $("#get-unit-data:visible").find('option:selected').attr('name');
		$('#default_conversion_value_name').val(default_conversion_value_name);
                       
       addUnit();
    if($('.id_convert_long').length>0) {
        updatePrice();
    }

    $('.new_cart_button').on('click touchstart', function() {
                var quantity = typeof (quantity) != 'undefined' ? quantity : 1;
                var product_id = $(this).data('product_id');
                $.ajax({
                    url: 'index.php?route=checkout/cart/add',
                    type: 'post',
                    data: 'product_id=' + product_id + '&quantity=' + quantity,
                    dataType: 'json',
                    success: function(json) {
                        $('.success, .warning, .attention, .information, .error').remove();

                        if (json['redirect']) {
                            location = json['redirect'];
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
    function updatePrice(){
        var p_id = $('input[name="product_id"]').val();
        var base_price = $("#base_price_input").val();
        var quantity = $(".quantity:visible").val();
        var unit_type = $(".id_convert_long:visible").find('option:selected').attr('data-value');
        var simplePrice = $(".top-gap").next().find(".price-new").text();
        var unit_fullName = $(".get-unit-data:visible").find('option:selected').attr('name');
        var plural_unit = $("#plural_unit").val();
        var conversion_price =$(".get-unit-data:visible").find('option:selected').val();
		var default_conversion_value_name = $("#default_conversion_value_name").val();
        $.ajax({
            url: 'index.php?route=product/product/calcPrice2',
            type: 'post',
            dataType: 'json',
            data: {
                "p_id": p_id,
                "simplePrice": simplePrice,
                "base_price": base_price,
                "quantity": quantity,
                "unit_type": unit_type,
                "conversion_price": conversion_price,
                "unit_fullName": unit_fullName,
                "plural_unit": plural_unit,
				"default_conversion_value_name": default_conversion_value_name
            },
            success: function(resp) {
                if(resp.unit_bulk_pricing){
                    $.each(resp.unit_bulk_pricing, function(index, data){
                       $(".update_discount_price_group").children().eq(index).find(".scale-quantity").html(data.quantity);
                       $(".update_discount_price_group").children().eq(index).find(".scale-price").html(data.price);
                   });
                }
                $(".product-block").find(".price-new").html(resp.calc_price);
                $(".product-block").find("#quantity_span").html(resp.discount_quantity);
                $(".product-block").find("#unit_dis").html(resp.unit_fullName);
                var quan = $(".quantity").val();
                var prodOption = $(".ig_MetalType").find(".id_convert_long:visible").find('option:selected').text();
              
                var helpText = getHelpText(quan, prodOption);
                $('.information_image_unit').show();
               
                 $('.option_tooltip:visible').attr('data-original-title', helpText);
                refrshTooltip();
				$('#converstion_string_display').html(resp.converstion_string);
				default_conversion_value_name = $.trim($("#default_conversion_value_name").val());
				unit_fullName = $.trim($("#get-unit-data:visible").find('option:selected').attr('name'));
				if(default_conversion_value_name != unit_fullName){
					$('#converstion_string_display').show();
				}else{
					$('#converstion_string_display').hide();
				}
            }
        });
        }
   
    
    function getHelpText(quan, prodOption) {
        var default_option_price = $('.id_convert_long option:eq(0)').val();

        var default_option_text = $('.id_convert_long option:eq(0)').text();

        var requested_unit_price = $('.id_convert_long > option:contains('+prodOption+')').val();
      
        var resUnits =quan * (requested_unit_price/default_option_price).toFixed(2);
        if(quan==resUnits){
          
            return 'Use this menu to calculate prices in different units';
        } else {
            return quan + " " + prodOption + " = " + resUnits + " " + default_option_text;
        }

    }
    $('.quantity').blur(function() {
        updatePrice();
    });
    $('.visible-phone > .quantity').blur(function() {
        updatePrice();
    });
    $(".get-unit-data").live('change',function(){
         updatePrice();
    });
    $('.colorbox').colorbox({
        overlayClose: true,
        opacity: 0.5,
        rel: "colorbox"
    });
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
    //priceChangeonchange('.options .option select');
});
    
function addUnit(){
    var coversion_id = $(".id_convert_long:visible").find('option:selected').attr('data-value');
    $('.unit_conversion_values').val(coversion_id);
}
$( ".get-unit-data" ).change(function() {
    addUnit();
});
    
    
$('select[name="profile_id"], input[class="quantity" name="quantity"]').change(function() {
    $.ajax({
        url: 'index.php?route=product/product/getRecurringDescription',
        type: 'post',
        data: $('input[name="product_id"], input[class="quantity" name="quantity"], select[name="profile_id"]'),
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
$('#button-cart-2').bind('click', function() {
    var quan = $('.quantity').val();
    $.ajax({
        url: 'index.php?route=checkout/cart/add',
        type: 'post',
        data: $('.product-info input[type=\'text\']:visible, \n\
                    .product-info input[type=\'hidden\'], \n\
                    .sidebar input[type=\'hidden\'], \n\
                    .id_convert_long:visible.find("option:selected").attr("data-value"), \n\
                    .product-info input[type=\'radio\']:checked, \n\
                    .product-info input[type=\'checkbox\']:checked, \n\
                    .product-info select, \n\
                    .product-info textarea'),

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
				
				if (json['error']['minimum']) {
                   // alert(json['error']['minimum']);
				   $('#notification').html('<div class="alert alert-danger alert-dismissible" role="alert" style="margin-top:10px;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' + json['error']['minimum'] + '</div>');
                }
            }

            if (json['success']) {
                $('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

                $('.success').fadeIn('slow');
 if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
                $('#cart-total').html("");
            }else{
                $('#cart-total').html(json['total']);
            }
				
				$('#cart-total-desktop').html(json['total_desktop']);
				

                $('html, body').animate({scrollTop: 0}, 'slow');
            }
        }
    });
});
<?php if ($options) { ?>
    <?php foreach ($options as $option) { ?>
        <?php if ($option['type'] == 'file') { ?>
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
        <?php } ?>
    <?php } ?>
<?php } ?>

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
$('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');
$('#button-review').bind('click', function() {
    $.ajax({
    url: 'index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',
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
$('#tabs a').tabs();
 
</script>

<?php  if ($qa_status) { ?>
 <script>
    $("#help_out_stock_tooltip").tooltip({
    show: {
    effect: "slideDown",
            delay: 250
    }
    });
            $("#help_out_stock_tooltip_email ,#help_out_stock_tooltip_emailoutstock,#help_out_stock_tooltip").tooltip({
    show: {
    effect: "slideDown",
            delay: 250
    }
    });
            $('.container-fluid').not('#help_out_stock_tooltip, #help_out_stock_tooltip_email ,#help_out_stock_tooltip_emailoutstock').click(function(event) {
    $('#help_out_stock_tooltip, #help_out_stock_tooltip_email, #help_out_stock_tooltip_emailoutstock').tooltip('hide');
    });
            
            function myFunction() {
            var quan = $(".prod-desktop").find("input[name=quantity]").val();
                    var prodOption = $(".prod-desktop").find(".id_convert_long:visible").find('option:selected').text();
                    return quan + " " + prodOption + " = ";
            }
    // refrshTooltip();
    $(".option_tooltip_bulk").tooltip({
    show: {
    effect: "slideDown",
            delay: 250
    }
    });
            $('.container-fluid').not('.option_tooltip_bulk').click(function(event) {
    $('.option_tooltip_bulk').tooltip('hide');
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
    //-->
    jQuery(document).ready(function() {
    jQuery('.iframe-rwd object').attr('data', function(index, value) {
    var param = "autoplay=1";
            return value + '&' + param;
    });
            jQuery('.iframe-rwd param[name=movie]').attr('value', function(index, value) {
    var param = "autoplay=1";
            return value + '&' + param;
    });
            jQuery('.iframe-rwd param[name=autoplay]').attr({value: 'true'});
    });

<?php if(!$logged){ ?>
    $('.text_bulk_product').live('click', function() {
        $(".show_hide_box_product").toggle();
    });
<?php } ?>
            
            
$('.whole_sale_login_product').live('click', function() {
    //$('#checkout #login').slideToggle('slow');
    $("#quick_login").attr('class','active');
    window.scrollTo(0, 0);
    $(".show_hide_box_product").hide();
});          

</script>
<script>
     $(document).on('touchstart',function(e){
                    var clickElement = e.target;  // get the dom element clicked.
                    var elementClassName = e.target.className;  // get the classname of the element clicked
                    if($('.'+elementClassName).parents(".show_hide_box_product").length == '0') {
                        $(".show_hide_box_product").hide();
                    }
                }); 
</script>

 <script>
    $('.changeMain').click(function(e){
        e.preventDefault();
        
    });
    
  </script>

<?php }

?>
</script>
<?php echo $footer;?>