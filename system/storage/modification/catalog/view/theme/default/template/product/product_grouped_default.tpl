
<style>
.image-block{
position: relative !important;
}
</style>
<!--new product-->
<?php if($install_status==1){ if($position_new==1){ ?>
<style>
    .new_product {
        background-color: <?php echo $new_product_db_label_color;?>;
        color:<?php echo $new_product_db_text_color;?>;
        float: left;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        top: 8px;
        z-index: 1;
        left:15px;
        border-bottom-right-radius: 10px;
        border-top-left-radius: 10px;
    }
</style>
<?php } if($position_new==2){ ?>
<style>
    .new_product {
        background-color: <?php echo $new_product_db_label_color;?>;
        color:<?php echo $new_product_db_text_color;?>;
        float:right;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        top: 8px;
        z-index: 1;
        right: 15px;
        border-bottom-right-radius: 10px;
        border-top-left-radius: 10px;
    }
</style>
<?php } if($position_new==3){ ?>
<style>
    .new_product {
        background-color: <?php echo $new_product_db_label_color;?>;
        color:<?php echo $new_product_db_text_color;?>;
        float: left;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        text-align: center;
        z-index: 1;
        bottom: 55px;
        left: 20px;
        border-bottom-right-radius: 10px;
        border-top-left-radius: 10px;
    }
</style>
<?php } if($position_new==4){ ?>
<style>
    .new_product {
        background-color: <?php echo $new_product_db_label_color;?>;
        color:<?php echo $new_product_db_text_color;?>;
        float: right;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        z-index: 1;
        right: 15px;
        bottom: 55px;
        border-bottom-right-radius: 10px;
        border-top-left-radius: 10px;
    }
</style>
<?php } ?>
<!--new product-->
<!--new product_related-->
<?php if($position_new==1){ ?>
<style>
    .new_product_related {
        background-color: <?php echo $new_product_db_label_color;?>;
        color:<?php echo $new_product_db_text_color;?>;
        float: left;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        top: 8px;
        z-index: 1;
        left:15px;
        border-bottom-right-radius: 10px;
        border-top-left-radius: 10px;
    }
</style>
<?php } if($position_new==2){ ?>
<style>
    .new_product_related {
        background-color: <?php echo $new_product_db_label_color;?>;
        color:<?php echo $new_product_db_text_color;?>;
        float: right;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        top: 8px;
        z-index: 1;
        right: 15px;
        border-bottom-right-radius: 10px;
        border-top-left-radius: 10px;
    }
</style>
<?php } if($position_new==3){ ?>
<style>
    .new_product_related {
        background-color: <?php echo $new_product_db_label_color;?>;
        color:<?php echo $new_product_db_text_color;?>;
        float: left;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        text-align: center;
        top: 8px;
        z-index: 1;
        left:15px;
        border-bottom-right-radius: 10px;
        border-top-left-radius: 10px;
            }
</style>
<?php } if($position_new==4){ ?>
<style>
    .new_product_related {
        background-color: <?php echo $new_product_db_label_color;?>;
        color:<?php echo $new_product_db_text_color;?>;
        float: right;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        z-index: 1;
        bottom: 55px;
        right: 15px;
        border-bottom-right-radius: 10px;
        border-top-left-radius: 10px;
    }
</style>
<?php } ?>
<!--new product related-->

<!--discount product-->
<?php if($position_discount==1) { ?>
<style>
    .discount_product {
        background-color: <?php echo $discount_product_db_label_color?>;
        color:<?php echo $discount_product_db_text_color;?>;
        float: left;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        z-index: 1;
        left:15px;
        top: 8px;
    }

</style>
<?php } if($position_discount==2) { ?>
<style>
    .discount_product {
        background-color: <?php echo $discount_product_db_label_color?>;
        color:<?php echo $discount_product_db_text_color;?>;
        float: right;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        z-index: 1;
        right: 15px;
        top: 8px;
    }

</style>
<?php } if($position_discount==3) { ?>
<style>
    .discount_product {
        background-color: <?php echo $discount_product_db_label_color?>;
        color:<?php echo $discount_product_db_text_color;?>;
        float: left;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        z-index: 1;
        bottom: 55px;
        left:15px;
    }
</style>
<?php } if($position_discount==4) { ?>
<style>
    .discount_product {
        background-color: <?php echo $discount_product_db_label_color?>;
        color:<?php echo $discount_product_db_text_color;?>;
        float: right;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        z-index: 1;
        right: 15px;
        bottom: 55px;
    }
</style>
<?php } ?>
<!--discount product-->

<!--discount product related-->
<?php if($position_discount==1) { ?>
<style>
    .discount_product_related {
        background-color: <?php echo $discount_product_db_label_color?>;
        color:<?php echo $discount_product_db_text_color;?>;
        float: left;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        text-align: center;
        top: 8px;
        z-index: 1;
        left:15px;
    }
</style>
<?php } if($position_discount==2) { ?>
<style>
    .discount_product_related {
        background-color: <?php echo $discount_product_db_label_color?>;
        color:<?php echo $discount_product_db_text_color;?>;
        float: right;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        top: 8px;
        z-index: 1;
        right: 15px;
    }

</style>
<?php } if($position_discount==3) { ?>
<style>
    .discount_product_related {
        background-color: <?php echo $discount_product_db_label_color?>;
        color:<?php echo $discount_product_db_text_color;?>;
        float: left;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
       bottom: 55px;
        z-index: 1;
        left:15px;
    }
</style>
<?php } if($position_discount==4) { ?>
<style>
    .discount_product_related {
        background-color: <?php echo $discount_product_db_label_color?>;
        color:<?php echo $discount_product_db_text_color;?>;
        float: right;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        bottom: 55px;
        right:15px;
        z-index: 1;
    }
</style>
<?php } ?>
<!--discount product related-->

<!--shipping product-->
<?php if($position_shipping==1) { ?>
<style>
    .shipping_product{
        background-color: <?php echo $shipping_product_db_label_color;?>;
        color:<?php echo $shipping_product_db_text_color;?>;
        float: left;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        top: 8px;
        z-index: 1;
        left:15px;
        border-bottom-right-radius: 8px;
        border-bottom-left-radius: 8px;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }
</style>
<?php } if($position_shipping==2) { ?>
<style>
    .shipping_product{
        background-color: <?php echo $shipping_product_db_label_color;?>;
        color:<?php echo $shipping_product_db_text_color;?>;
        float: right;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        top: 8px;
        z-index: 1;
        right: 15px;
        border-bottom-right-radius: 8px;
        border-bottom-left-radius: 8px;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }
</style>
<?php } if($position_shipping==3) { ?>
<style>
    .shipping_product{
        background-color: <?php echo $shipping_product_db_label_color;?>;
        color:<?php echo $shipping_product_db_text_color;?>;
        float: left;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        z-index: 1;
        bottom: 55px;
        left:15px;
        border-bottom-right-radius: 8px;
        border-bottom-left-radius: 8px;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }
</style>
<?php } if($position_shipping==4) { ?>
<style>
    .shipping_product{
        background-color: <?php echo $shipping_product_db_label_color;?>;
        color:<?php echo $shipping_product_db_text_color;?>;
        float: right;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        bottom: 55px;
        z-index: 1;
        right: 15px;

        border-bottom-right-radius: 8px;
        border-bottom-left-radius: 8px;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }
</style>
<?php } ?>
<!--shipping product-->
<!--shipping product related-->
<?php if($position_shipping==1) { ?>
<style>
    .shipping_product_related{
        background-color: <?php echo $shipping_product_db_label_color;?>;
        color:<?php echo $shipping_product_db_text_color;?>;
        float: left;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        text-align: center;
        top: 8px;
        z-index: 1;
        left:15px;
        border-bottom-right-radius: 8px;
        border-bottom-left-radius: 8px;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }
</style>
<?php } if($position_shipping==2) { ?>
<style>
    .shipping_product_related{
        background-color: <?php echo $shipping_product_db_label_color;?>;
        color:<?php echo $shipping_product_db_text_color;?>;
        float: right;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        top: 8px;
        z-index: 1;
        right: 15px;
        border-bottom-right-radius: 8px;
        border-bottom-left-radius: 8px;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }
</style>
<?php } if($position_shipping==3) { ?>
<style>
    .shipping_product_related{
        background-color: <?php echo $shipping_product_db_label_color;?>;
        color:<?php echo $shipping_product_db_text_color;?>;
        float: left;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        z-index: 1;
        left:15px;
        bottom: 55px;
        border-bottom-right-radius: 8px;
        border-bottom-left-radius: 8px;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }
</style>
<?php } if($position_shipping==4) { ?>
<style>
    .shipping_product_related{
        background-color: <?php echo $shipping_product_db_label_color;?>;
        color:<?php echo $shipping_product_db_text_color;?>;
        float: right;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        z-index: 1;
        bottom: 55px;
        right: 15px;
        border-bottom-right-radius: 8px;
        border-bottom-left-radius: 8px;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }
</style>
<?php } ?>
<!--shipping product related-->
<!--outofstock product-->

<?php if($position_outofstock==1) { ?>
<style>
    .outofstock_product {
        background-color: <?php echo $outofstock_product_db_label_color?>;
        color:<?php echo $outofstock_product_db_text_color;?>;
        float: left;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        z-index: 1;
        left:15px;
        top: 8px;
    }

</style>
<?php } if($position_outofstock==2) { ?>
<style>
    .outofstock_product {
        background-color: <?php echo $outofstock_product_db_label_color?>;
        color:<?php echo $outofstock_product_db_text_color;?>;
        float: right;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        z-index: 1;
        right: 15px;
        top: 8px;
    }

</style>
<?php } if($position_outofstock==3) { ?>

<style>
    .outofstock_product {
        background-color: <?php echo $outofstock_product_db_label_color?>;
        color:<?php echo $outofstock_product_db_text_color;?>;
        float: left;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        z-index: 1;
        bottom: 55px;
        left:15px;
    }

</style>
<?php } if($position_outofstock==4) { ?>
<style>
    .outofstock_product {
        background-color: <?php echo $outofstock_product_db_label_color?>;
        color:<?php echo $outofstock_product_db_text_color;?>;
        float: right;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        z-index: 1;
        right: 15px;
        bottom: 55px;
    }

</style>
<?php } ?>

<!--outofstock product-->

<!--outofstock product related-->
<?php if($position_outofstock==1) { ?>
<style>
    .outofstock_product_relatedt {
        background-color: <?php echo $outofstock_product_db_label_color?>;
        color:<?php echo $outofstock_product_db_text_color;?>;
        float: left;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        text-align: center;
        top: 8px;
        z-index: 1;
        left:15px;
    }

</style>
<?php } if($position_outofstock==2) { ?>
<style>
    .outofstock_product_relatedt {
        background-color: <?php echo $outofstock_product_db_label_color?>;
        color:<?php echo $outofstock_product_db_text_color;?>;
        float: right;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        top: 8px;
        z-index: 1;
        right: 15px;
    }

</style>
<?php } if($position_outofstock==3) { ?>

<style>
    .outofstock_product_relatedt {
        background-color: <?php echo $outofstock_product_db_label_color?>;
        color:<?php echo $outofstock_product_db_text_color;?>;
        float: left;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        bottom: 55px;
        z-index: 1;
        left:15px;
    }

</style>
<?php } if($position_outofstock==4) { ?>
<style>
    .outofstock_product_relatedt {
        background-color: <?php echo $outofstock_product_db_label_color?>;
        color:<?php echo $outofstock_product_db_text_color;?>;
        float: right;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        bottom: 55px;
        right:15px;
        z-index: 1;
            }

</style>
<?php }  ?>
<!--outofstock product related-->

<!--custom product-->

<?php if($position_custom==1) { ?>
<style>
    .custom_product {
        background-color: <?php echo $custom_product_db_label_color?>;
        color:<?php echo $custom_product_db_text_color;?>;
        float: left;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        z-index: 1;
        left:15px;
        top: 8px;
    }

</style>
<?php } if($position_custom==2) { ?>
<style>
    .custom_product {
        background-color: <?php echo $custom_product_db_label_color?>;
        color:<?php echo $custom_product_db_text_color;?>;
        float: right;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        z-index: 1;
        right: 15px;
        top: 8px;
    }

</style>
<?php } if($position_custom==3) { ?>

<style>
    .custom_product {
        background-color: <?php echo $custom_product_db_label_color?>;
        color:<?php echo $custom_product_db_text_color;?>;
        float: left;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        z-index: 1;
        bottom: 55px;
        left:15px;
    }

</style>
<?php } if($position_custom==4) { ?>
<style>
    .custom_product {
        background-color: <?php echo $custom_product_db_label_color?>;
        color:<?php echo $custom_product_db_text_color;?>;
        float: right;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        z-index: 1;
        right: 15px;
        bottom: 55px;
    }

</style>
<?php } ?>

<!--custom product-->

<!--custom product related-->
<?php if($position_custom==1) { ?>
<style>
    .custom_product_related {
        background-color: <?php echo $custom_product_db_label_color?>;
        color:<?php echo $custom_product_db_text_color;?>;
        float: left;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        text-align: center;
        top: 8px;
        z-index: 1;
        left:15px;
    }

</style>
<?php } if($position_custom==2) { ?>
<style>
    .custom_product_related {
        background-color: <?php echo $custom_product_db_label_color?>;
        color:<?php echo $custom_product_db_text_color;?>;
        float: right;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        top: 8px;
        z-index: 1;
        right: 15px;
    }

</style>
<?php } if($position_custom==3) { ?>

<style>
    .custom_product_related {
        background-color: <?php echo $custom_product_db_label_color?>;
        color:<?php echo $custom_product_db_text_color;?>;
        float: left;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        bottom: 55px;
        z-index: 1;
        left:15px;
    }

</style>
<?php } if($position_custom==4) { ?>
<style>
    .custom_product_related {
        background-color: <?php echo $custom_product_db_label_color?>;
        color:<?php echo $custom_product_db_text_color;?>;
        float: right;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        bottom: 55px;
        right:15px;
        z-index: 1;
            }

</style>
<?php } } ?>
<!--custom product related-->

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

<?php echo $column_left; ?>
<?php echo $content_top; ?>


<!--<link rel="stylesheet" type="text/css" href="catalog/view/css/jquery.fancy-select.css" />-->
<!--mid-block starts here-->
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/magnific/magnific-popup.css" />
<script type="text/javascript" src="catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js"></script>
<div class="mid-block">
    <span id='group_indicator' style="display: none;" data-group_indicator='<?php
    if (isset($group_indicator_id)) {
        echo $group_indicator_id;
    }
    ?>'></span>
    <div class="product-info">

        <div class="row-fluid">
            <!--content-block starts here-->
            <div class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
            <?php } ?>
            </div>

            <div class="span12 content-block testback_image bulk_show">
                <div class="show_hide_box">
                    <span class="whole-sale-heading">For wholesale pricing</span>
                    <span class="whole_sale_login"><a href="javascript:void(0)">Log In</a></span>
                    <span class="or_group"> OR</span>
                    <h1><a href="account/register">Sign Up</a></h1>
                    <p class="text-contact">For Quotes on Significantly Larger Quantities, 
                        Please <a  class ="contact_group" href="<?php echo $contact ?>"> Contact Us</a></p>


                </div>
                <!--product block starts here-->
                <div class="product-block span12">
                    <div class="row-fluid">
                        <div class="image-block span7">
                            <?php if ($thumb) { ?>
                                <div class="img-box"><a class="cloud-zoom colorbox" rel="adjustY:-4, tint:'#000000',tintOpacity:0.2, zoomWidth:360" href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>"><img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" id="image" /></a></div>
 <!--New product count-->
                    <?php foreach($images as $product) {  } ?>
                    <?php $datetime1 = new DateTime($product['date']);
                    $datetime2 = new DateTime();
                    $interval = $datetime1->diff($datetime2);
                    $date_diff_count=$interval->format('%a');
                    if($new_product_status == 1 ) {
                    if($new_product_db_days > $date_diff_count) { ?>
                    <div class="new_product">
                        <?php echo $new_product_db_text; ?>
                    </div>
                    <?php } } ?>
                    <!--New product count-->

                    <!--discount product-->
                    <?php if($discount_product_status==1) {
                    if($discount_product_db_percent < $product['percent_value']) { ?>
                    <?php if ($product['special'])  { ?>
                    <div class="discount_product">
                        <?php echo $product['percent']; ?>
                    </div>
                    <?php } } } ?>
                    <!--discount product-->

                    <!--custom product 2-->
                    <?php if($custom_product_label_2_status==1 && $show_product_label_2) {
                        if(empty($product_label_text_2)) { ?>
                            <div class="shipping_product">
                                <?php echo $shipping_product_db_text;  ?>
                            </div>
                        <?php } else { ?>
                            <div class="shipping_product">
                                <?php echo $product_label_text_2;  ?>
                            </div>
                    <?php }  } ?>
                    <!--custom product 2-->

                    <!--outofstock product-->
                    <?php if($outofstock_product_status==1) {
               if($product['stockquantity']==0) { ?>
                    <div class="outofstock_product">
                        <?php echo $outofstock_product_db_text;  ?>
                    </div>
                    <?php }  } ?>
                    <!--outofstock product-->
                    
                    <!--custom product-->
                    <?php if($custom_product_label_1_status==1 && $show_product_label_1) {
                        if(empty($product_label_text_1)) { ?>
                            <div class="custom_product">
                                <?php echo $custom_product_db_text;  ?>
                            </div>
                        <?php } else { ?>
                            <div class="custom_product">
                                <?php echo $product_label_text_1;  ?>
                            </div>
                    <?php }  } ?>
                    <!--custom product-->
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
        <!--                                                        <a onclick="$('a[href=\'#tab-review\']').trigger('click');"><?php // echo $text_write;   ?></a>-->
                                            </span>
                                        </div>
<?php } ?>
                                </div>
                                <div class="stock_status">
                                    <span id="show_stock">
                                    <div class="help_tool_image"></div>
                                    <?php if ($quantity <= 0) { ?>
                                            <?php if($stock == '2-3 Days') : ?> <span class='two_three_days'></span>
                                           <?php elseif($stock == 'Pre-Order') : ?> <span class='pre_order'></span>
                                           <?php elseif($stock == 'In Stock') : ?> <span class='inofstock'></span>
                                           <?php else : ?> <span class='outofstock'></span> <?php endif; ?>
                                            	<?php
                                                	if( !empty($frontend_date_available) && $frontend_date_available > date("Y-m-d") && $date_sold_out < $frontend_date_available )
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
															//echo $date_ordered;
															$availabledate = date("Y-m-d",strtotime($date_ordered ."+".$get_days['estimate_days']." days"));
															//echo $availabledate;
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
                                            		
                                            		/*$datetoday = date("Y-m-d");
                                            		if($datetoday > $date_ordered)
                                            		{
														echo "<span class='stocktext'>Will take at least (".$estimate_deliver_time.") days to come back in stock" . "</span>";
													}
													else
													{
														if($date_ordered != "0000-00-00")
														{
															$availabledate = date("Y-m-d",strtotime($date_ordered . " +".$estimate_deliver_time." days"));
															echo "<span class='stocktext'>Will be available on or around " . date( "M d", strtotime($availabledate) ) . "</span>";
														}
													}*/
                                            	?>
                                        <?php } else { ?>
                                            <span class='inofstock'></span> 
                                        <?php } ?>
                                </div>
                            </div>

                            <div class="top-gap">
                                <span class=" flt number_group_product "><strong class="item_numbersss">Item Number: </strong><span id="item_number"><?php echo $sku; ?></span></span>

                            </div>

                            <p class="clearfix"></p>
                            <div class="clearfix"></div>
                            <input type="hidden" id="base_price_input" value="<?php echo isset($base_price) ? $base_price : '0' ?>">
                            <?php if ($logged) { ?>
                                <p class="top-gap"><strong class="price_gro">Price :       </strong></p>
                            <?php } else { ?>
                                <p class="top-gap"><strong class="price_group">Price :</strong></p>
                                <?php } ?>

                            <p class="price_group_prod">
                                    <?php if (!$special) {
                                        ?>
                                    <span class='price-new' id="price-update">
    <?php echo $price; ?>
                                    </span>
                                    <span class='price_red'> / </span>
                                    <span class = 'price_red' id='quantity_span'></span>
									<span class = 'price_red' id='unit_dis'></span>
                                    <br>
                                    
                                    
                                    <?php } else {
                                        ?>
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
                            <?php if(isset($this->request->get['gpsku'])){ ?>
                                <input class="gpsku" type="hidden" value ="<?php echo $this->request->get['gpsku']; ?>">
                            <?php }else{ ?>
                                <input class="gpsku" type="hidden" value ="">
                            <?php } ?>
                            
                           <!-- price and taxes -->
                            <div class="price">

                                <?php if ($points) { ?>
                                    <span class="reward"><small><?php echo $text_points; ?> <?php echo $points; ?></small></span><br />
<?php } ?>


                            </div>
                    <?php if ($discounts&&$logged || $logged) { ?>
                            <div style="z-index: 999999999999999;" class="bulk-product-tex">

                                <span class="bulk_price_image"> </span>
                                <span class="bulk_price_image_minus"></span>
                                <span class=""><a style="cursor: pointer;" data-toggle="modal" data-target="#seeBulkPrice">See Bulk Price</a></span>

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
                            datavalue=datavalue;
                        }
                        
                            var unitname=  $("#unit_dis").html();
                         
                            $(".scale-quantity").after( "<span class='current_unitnames'>   <b>"+ datavalue +"</b> </span>");
                                });
                          $('#seeBulkPrice').on('hidden', function() {
                            $(".current_unitnames").remove();
                          });
                        
                    });
                </script>

                            <!-- price-->

                         

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
                            <div class="grouped-product gp-group">

<?php if ($product_grouped) { ?>
                                    <b><?php echo "Select " . $text_groupby ?>:</b>

                                    <select name="select_product" class='grouped_product_select fancySelect'>
                                        <?php foreach ($product_grouped as $product) { ?> 
                                            <option <?php if($product['is_requested_product']) { echo "selected='selected'"; } ?> value="<?php echo $product['product_id']; ?>"><?php echo trim($product['product_name']); ?> </option>
                                        <?php } ?>
                                    </select>
<?php } ?>
                                <div style="position:absolute;"><div class="gp-loader"></div></div>
                            </div>

                            <!-- Options Type -->
                                <?php if ($options) { ?>
                                <div class="options">
                                    <!--<h2><?php echo $text_option; ?></h2>-->
                                    <?php
                                    foreach ($options as $option) {
                                        if ($option['type'] == 'select') {
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
                                                        if ($option['metal_type'] > 1) {
                                                            ?>
                                                            <option value=""><?php echo $text_select; ?></option>
                                                        <?php }
                                                        ?>
                                                            <?php foreach ($option['option_value'] as $option_value) { 
                                                             if($option_value['quantity'] <= 0) { ?>
                                                            <option qty="<?php echo $option_value['quantity'];?>" <?php if($option_value['is_requested_option_value']) { echo "selected='selected'";} ?> data-price="<?php echo ($option_value['price2'] ? $option_value['price2'] : '' ) ?>" value="<?php echo $option_value['product_option_value_id']; ?>">
                                                            <?php echo $option_value['name'].' - '.$option_out_of_stock; ?>
                                                            </option>
                                                             <?php } else { ?>
                                                            <option qty="<?php echo $option_value['quantity'];?>" <?php if($option_value['is_requested_option_value']) { echo "selected='selected'";} ?> data-price="<?php echo ($option_value['price2'] ? $option_value['price2'] : '' ) ?>" value="<?php echo $option_value['product_option_value_id']; ?>">
                                                            <?php echo $option_value['name']; ?>
                                                            </option>
                                                             <?php } ?>
                <?php } ?>
                                                    </select>

                                                </div>
                                                <?php
                                            }
                                        }
                                        if ($option['type'] == 'radio') {
                                            $UnitArry = explode(' ', $option['name']);
                                            if (strtolower(end($UnitArry)) != "units") {
                                                ?>
                                                <div id="option-<?php echo $option['product_option_id']; ?>" class="option ig_<?php echo str_replace(' ', '', $option['name']); ?>">
                                                    <?php if ($option['required']) { ?>
                                                        <span class="required">*</span>
                                                    <?php } ?>
                                                    <b><?php echo $option['name']; ?>:</b><br />
                                                    
                                                        <?php foreach ($option['option_value'] as $option_value) { ?>
                                                        <label for="option-value-<?php echo $option_value['product_option_value_id']; ?>" data-val="<?php echo $option_value['name']; ?>" >
                    <?php echo $option_value['name']; ?>
                                                        </label>

                                                        <input data-price="<?php echo ($option_value['price2'] ? $option_value['price2'] : '' ) ?>" type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" />

                <?php } ?>
                                                    <!--</select>-->

                                                </div>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>

                                </div>
<?php } ?>
                            <!-- cart box on mobile-->
                            <div  class="price_grouped_product_box">
                                <div class="quantity_box_product_group">
                                    <p class=" entry flt group_qty_box">
                                        <label class="group_product_text_qty"><?php echo $text_qty; ?></label>
                                        <input type="text" class="quantity qty_group_product" name="quantity"  value="<?php echo $minimum; ?>" />
                                        <input type="hidden" name="original_price" value="<?php echo $old_price; ?>" >
                                        <input id="product_id_change" type="hidden" value="<?php echo $product_id ?>"  name="product_id">
                                    </p>
                                    <br>
                                    
                                    <?php if (empty($unit_datas)) { ?>
                               <!-- <span class='price_red' id='unit_dis'><?php echo $unit_singular; ?></span> -->
                                    <?php } else { ?>
                                
                               			 <div class="ig_MetalType ig_Units units_grouped">
                                    <select class="get-unit-data id_convert_long" id="get-unit-data">
                                        <?php foreach ($unit_datas as $unit_data) { ?>
                                            <option name="<?php echo $unit_data['name']; ?>" data-value ="<?php echo $unit_data['unit_conversion_product_id']; ?>" value="<?php echo $unit_data['convert_price']; ?>">
                                                <?php echo $unit_data['name']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="information_image">
                                    <img class=" option_tooltip"   src="catalog/view/theme/default/img/information.png" alt=""/>
                                </div>  
<?php } ?>
                            <input type="hidden" class="unit_conversion_values" name ="unit_conversion_values" value="">
                            <input type="hidden" id="default_conversion_value_name" name ="default_conversion_value_name" value="">
                                  
                                    <div class="add_img_group">
                                        <img src="catalog/view/theme/default/img/satiscfaction_image.png" alt="" class="money-back"/>
                                         <!--                            <a href="#" class="qty-cart">Add to cart <img src="img/cart.png" alt=""/></a>-->
                                        <img src="catalog/view/theme/default/img/paypal_group.png" alt="" class="pay-img"/>
                                    </div>
                                </div>
                                <div class="pay-100 cart_product_group"> 
                                	<div id="cart-button-display">
                                     		<input type="button" id="button-cart" class="qty-cart button-cart" value="Add to Cart" />
                                        </div>
                                        <div id="loading-display" style="padding-left: 15px; display:none;">
                                        	<img src="catalog/view/theme/default/img/ajax-loading.gif" alt="" class="loading" width="75px"/>
                                        </div>
                                        
                                    <span class="links link_product"><a onclick="addToWishList('<?php echo $product_id; ?>');"><?php echo $button_wishlist; ?></a>
                                        <a id="compare_pro" onclick="addToCompare('<?php echo $product_id; ?>');"><?php echo $button_compare; ?></a></span>

                                </div>
                                <div class="clearfix"></div>



                            </div>


       


                            <!--sidebaar monu-->

                        </div>


                        <div class="row-fluid">
                            <div class="span12">
                                <div class="ui_tabs products-uitabs">
                                    <div id="tabs" class="htabs">
                                        <a href="#tab-description"><?php echo $tab_description; ?></a>
                                        
                                            <?php if ($review_status) { ?>
                                            <a href="#tab-review">
    <?php echo $tab_review; ?></a>
<?php } ?>
                                        <a href="#tab-qa"><?php echo $tab_qa; ?> </a>
                                        
                                    </div>
                                    <div id="tab-description" class="tab-content tab_content_product"><div class="tab_contents"><div class="iframe-rwd"><?php echo $description; ?></div>

                                                    <?php if ($attribute_groups) { ?>
                                                <div id="tab-attribute" class="tab_product_attribute">
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
                                                </div>
<?php } ?>
                                        </div></div>


<?php if ($review_status) { ?>
                                        <div id="tab-review" class="tab-content tab_content_product">
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
    <?php if ($qa_notify = true) { ?>
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
                                      <div id="article">
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
                                       </div>
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






                <!--sidebar starts here-->


                <div class="information_right">
                    <?php echo $column_right; ?>
                </div>
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
                                        <?php if ($discounts) { ?>

                                            <p class="he group_he">Bulk Pricing and Quantity Discounts</p>

                                            <p class="mid-group">
                                                <span class="flt">Quantity</span>
                                                <span class="flr scale-group">Price</span></p>
                                            <ul class="update_discount_price_group">
                                                <li>
                                                    <span class="scale-quantity"><?php echo "Non-Wholesale"; ?></span>

                                                    <span style="text-align: right !important;float: right;" class="scale-price"><?php echo $price_without_discount; ?></span>

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
                                                        <span style="text-align: right !important;float: right;" class="scale-price">
                                                    <?php echo $discount['price']; ?>
                                                        </span>
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
                <div class="group_product_bottom span12">
<?php echo $content_bottom; ?>
                </div>
            </div>
        </div>
    </div>
</div>
    <?php if ($logged) { ?>
	<script>
            $(document).on('click', '.bulk_price_image, .bulk_price_image_minus, .text-bulk-price', function() {

                $('.discount_product_group').toggle();
                if ($(".bulk_price_image_minus").css('display') == 'none') {
                    $(".bulk_price_image_minus").show();
                    $(".bulk_price_image").hide();
                    $(".text-bulk-price").html("Hide Bulk Price");
                } else {
                    $(".bulk_price_image_minus").hide();
                    $(".bulk_price_image").show();
                    $(".text-bulk-price").html("See Bulk Price");
                }


            });
			</script>
    <?php } ?>
<!--mid-block starts here-->
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-timepicker-addon.js?v=<?php echo rand();?>"></script>
<?php if ($options) { ?>
    <script type="text/javascript" src="catalog/view/javascript/jquery/ajaxupload.js?v=<?php echo rand();?>"></script>
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
                    //console.log(xhr.responseText);
                }
        });
    }
    $('.changeMainGroup').live('hover', function(event) {
                 event.preventDefault();
               
                    var src = $(this).attr('href');
                    var actw = $('#image').width();
                    $('#image').attr('src', src);
                    $('#image').parent().attr('href', src);
                    $('#image').css({'width': actw + 'px'});
                      $('.cloud-zoom').CloudZoom();
                    
                    
                });
                
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
//-->
    $(document).ready(function() {
        $('.fancySelect').fancySelect();
        $('.options select').fancySelect();

        $('#tabs a').tabs();
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
        
        $('.thumbnails').magnificPopup({
        type:'image',
        delegate: 'a',
        gallery: {
            enabled: true
        }
    });

    /*var wwell = $('.crsl-items-wwell').myjcarousel({
                vertical: false,
                visible: 2,
                scroll: 1,
                wrap: "null",
                contentWidth: 200,
                itemFallbackDimension: 200
            });
     
    var related = $('.crsl-items-related').myjcarousel({
                vertical: false,
                visible: 2,
                scroll: 1,
                wrap: "null",
            });*/

$( window ).resize(function() {
    console.log("IT");

    //var jcarousel = $('.crsl-items-wwell').myjcarousel();
    //$('.crsl-item').css('width','180px');
    //jcarousel.myjcarousel('reload');

    //console.log("ITF");
});
    }); 

//--></script>

<?php if ($qa_status) { ?>
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
    <?php if ($preload != 1) { ?>
            $('#qa').load('index.php?route=product/product/question&product_id=<?php echo $product_id; ?>');
    <?php } ?>
        
        function myFunction() {
            var quan = $(".prod-desktop").find("input[name=quantity]").val();
            var prodOption = $(".prod-desktop").find(".id_convert_long:visible").find('option:selected').text();
            return quan + " " + prodOption + " = ";
        }
        // refrshTooltip();
        $("information_image discount_box.option_tooltip unit-tooltip").tooltip({
            show: {
                effect: "slideDown",
                delay: 250
            }
        });
        $('information_image discount_box.container-fluid').not('information_image discount_box.option_tooltip').click(function(event) {
            $('information_image discount_box.option_tooltip').tooltip('hide');
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

        $(document).ready(function() {
    <?php if (!$logged) { ?>
                $('.bulk-product-tex span.text-bulk').live('click', function() {

                    $(".show_hide_box").toggle();

                });
    <?php } ?>
        
        $('.colorbox').colorbox({
            overlayClose: true,
            opacity: 0.5,
            rel: "colorbox"
        });
        
        });

        //-->

    //login down

        $('.whole_sale_login').live('click', function() {
            //$('#checkout #login').slideToggle('slow');
            $("#quick_login").attr('class', 'active');
            window.scrollTo(0, 0);
            $(".show_hide_box").hide();

        });


        
    </script> 
    <script>
        
     $(document).on('touchstart',function(e){
                    var clickElement = e.target;  // get the dom element clicked.
                    var elementClassName = e.target.className;  // get the classname of the element clicked
                    if($('.'+elementClassName).parents(".show_hide_box").length == '0') {
                        $(".show_hide_box").hide();
                    }
                }); 
                
    </script> 
   
    <script>
    $('.changeMainGroup').click(function(e){
        e.preventDefault();
        
    });
    </script>
    
<?php }echo $footer; ?>