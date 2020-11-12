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
            <h1><?php echo $heading_title; ?></h1>
            <form id="quick-buy" method="post" enctype="multipart/form-data">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td class="text-center" style="max-width:250px"><?php echo $column_search; ?></td>
                                <td class="text-center" style="width:150px"><?php echo $column_image; ?></td>
                                <td class="text-left"><?php echo $column_name; ?></td>
                                <td class="text-center" style="width:200px"><?php echo $column_quantity; ?></td>
                                <td class="text-right"><?php echo $column_price; ?></td>
                                <td class="text-right"><?php echo $column_total; ?></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="addtocart5">
                                <td>
                                    <!--<div id="search[0]" class="input-group btn-block">-->
                                    <input type="text" value="" size="35" autocomplete="off" placeholder="SKU,Ref #,Name" name="search" class="ui-autocomplete-input" role="textbox" aria-autocomplete="list" aria-haspopup="true">
                                        <!--<span class="input-group-btn"><button name="btn-search[0]" type="button" data-toggle="tooltip" title="Search" class="btn btn-primary"><i class="fa fa-search"></i></button></span>
                                    </div>-->
                                </td>
                                <td class="text-center"><a href="#" id="p_img_link"><img class="img-thumbnail" id="p_img" src="<?php echo HTTP_SERVER; ?>catalog/view/theme/default/image/no_product.jpg" alt="" /></a></td>
                                <td class="text-left">
                                    <input type="hidden" id="p_id" value="0"/>
                                    <input type="hidden" id="p_p" value="0"/>
                                    <a href="#" id="p_link">Product Name</a>
                                    <br />- <small>Product options if any</small>
                                </td>
                                <td class="text-left">
                                    <div class="input-group btn-block" style="max-width: 200px;">
                                        <input type="text" name="input-quantity[0]" id="p_qty" value="1" size="1" class="form-control">
                                        <span class="input-group-btn">
                                            <button name="refresh" id="refresh" type="button" data-toggle="tooltip" title="Update" class="btn btn-primary"><i class="fa fa-refresh"></i></button>
                                            <button name="del" id="del" type="button" data-toggle="tooltip" title="Delete" class="btn btn-danger"><i class="fa fa-times-circle"></i></button>
                                        </span>
                                    </div>
                                </td>
                                <td class="text-right" id="p_price"><strong>$0</strong> <small></small></td>
                                <td class="text-right" id="p_total">$0</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6" class="text-right"><button id="add-product" type="button" class="btn btn-success">Add More Products</button></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </form>
            <div class="row">
                <div class="col-sm-4 col-sm-offset-8">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="text-right"><strong>Subtotal:</strong></td>
                                    <td class="text-right" id="p_subtotal">$0</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="buttons text-center">
                <a href="<?php echo $checkout; ?>" class="btn btn-lg btn-block"><?php echo $button_checkout; ?></a>
            </div>
            <?php echo $content_bottom; ?>
	    </div>
        <?php echo $column_right; ?>
    </div>
    
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css?v=<?php echo rand();?>" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/gempack/stylesheet/jquery.autocomplete.css?v=<?php echo rand();?>" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/gempack/stylesheet/responsive.css?v=<?php echo rand();?>" />
<script type="text/javascript" src="catalog/view/javascript/jquery/autocomplete.js?v=<?php echo rand();?>"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-1.8.16.custom.min.js?v=<?php echo rand();?>"></script>
<script type="text/javascript" src="catalog/view/javascript/ecquickbuy/common.js?v=<?php echo rand();?>"></script>


<script>
    $(document).ready(function(){
        $('#add-row').on('click', function() {
            var myhtml = '<tr id="row[1]">';
            myhtml += '<td class="text-center">';
            myhtml += '<div id="search[1]" class="input-group btn-block">';
            myhtml += '<input type="text" name="input-search[1]" placeholder="search" class="form-control" />';
            myhtml += '<span class="input-group-btn"><button name="btn-search[1]" type="button" data-toggle="tooltip" title="Search" class="btn btn-primary"><i class="fa fa-search"></i></button></span>';
            myhtml += '</div>';
            myhtml += '</td>';
            myhtml += '<td class="text-center"></td>';
            myhtml += '<td class="text-left"></td>';
            myhtml += '<td class="text-left">';
            myhtml += '<div class="input-group btn-block" style="max-width: 200px;">';
            myhtml += '<input type="text" name="input-quantity[1]" value="0" size="1" class="form-control">';
            myhtml += '<span class="input-group-btn">';
            myhtml += '<button name="btn-refresh[1]" type="button" data-toggle="tooltip" title="Update" class="btn btn-primary"><i class="fa fa-refresh"></i></button>';
            myhtml += '<button name="btn-delete[1]" type="button" data-toggle="tooltip" title="Delete" class="btn btn-danger delete-row"><i class="fa fa-times-circle"></i></button>';
            myhtml += '</span>';
            myhtml += '</div>';
            myhtml += '</td>';
            myhtml += '<td class="text-right"></td>';
            myhtml += '<td class="text-right"></td>';
            myhtml += '</tr>';
            $("#quick-buy table tbody").append(myhtml);
        });
    });

    $(document).delegate('.delete-row','click',function() {
        var row = $(this).closest("tr");
        row.remove();
    });    
</script>


<script type="text/javascript">
$(document).ready(function() {
	var languages = {text_price:"<?php echo $text_price;?>",
					 text_viewall:"<?php echo $text_viewall; ?>"};
	var options = {show_image:<?php echo ($show_image==1)?'true':'false';?>,
				   show_price:<?php echo ($show_price==1)?'true':'false';?>,
				   show_viewmore:<?php echo ($all_result==1)?'true':'false';?>,
				   search_sub_category:<?php echo ($search_sub_category==1)?'1':'0';?>,
				   search_description:<?php echo ($search_description==1)?'1':'0';?>,
				   reload: <?php echo $reload?'true':'false'; ?>,
				   base_url: "<?php echo isset($base)?$base:'';?>",
				   quickview_title: "<?php echo $text_quickview;?>",
				   popup_width: "<?php echo ($popup_width)?$popup_width:'50%';?>",
				   popup_height: "<?php echo ($popup_height)?$popup_height:'550px';?>",
				   link_more: "index.php?route=product/search&search={search}{manufacturer_id}{category_id}&sub_category=<?php echo ($search_sub_category==1)?'true':'false';?>&description=<?php echo ($search_description==1)?'true':'false';?>"
				  }
	initAutocomplete('#addtocart5', languages, options);

    $("#add-product").click(function(){
        pid = $("#p_id").val();
        qty = $("#p_qty").val();
        addToCart2(pid,qty);
    });

    $("#refresh").click(function(){
        qty = $("#p_qty").val();
        qty = parseInt(qty);

        price = $("#p_p").val();
        price = price.replace("$","");
        price = parseFloat(price);

        total = qty * price;
        

        $("#p_total").text("$"+total);
        $("#p_subtotal").text("$"+total);
    });

    $("#del").click(function(){
            $("input[name='search']").val("");
            $("#p_id").val(0);
            $("#p_p").val("$0");
            $("#p_link").text("");
            $("#p_link").attr('href',"#");
            $("#p_img_link").attr('href',"#");
            $("#p_img").attr("src",'<?php echo HTTP_SERVER; ?>'+'catalog/view/theme/default/image/no_product.jpg');

            $("#p_qty").text("1");
            $("#p_price").text("$0");
            $("#p_total").text("$0");
            $("#p_subtotal").text("$0");
    });
})
</script>

<?php echo $footer; ?>