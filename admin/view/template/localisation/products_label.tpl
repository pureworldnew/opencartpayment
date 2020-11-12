<!--new product first set-->
<?php foreach($labels as $labelvalues) {
 if($labelvalues['label_id'] == 1) { ?>
<style>
    .main_image_new{
        height: 400px;
        width: 400px;
        position: relative;
    }
    .image_new{
        position: absolute;
        left: 24%;
        top: 13%;
        margin-top:
        margin-left:
    }
</style>
<?php if($labelvalues['position'] == 1) { ?>
<style>
    .new_product{
        background-color: <?php echo $labelvalues['label_color'];?>;
        color:<?php echo $labelvalues['label_text_color'];?>;
        font-size: 14pt;
        font-weight: 700;
        padding: 8px 45px 15px;
        z-index: 1;
        border-bottom-right-radius: 10px;
        border-top-left-radius: 10px;
        position: absolute;
        top: 10px;
        left: 0px;
        margin: 0 auto;
        width: 135px;
        height: 33px;
    }
</style>

<?php }  if($labelvalues['position'] ==2) { ?>
<style>
    .new_product{
        background-color: <?php echo $labelvalues['label_color'];?>;
        color:<?php echo $labelvalues['label_text_color'];?>;
        font-size: 14pt;
        font-weight: 700;
        padding: 8px 45px 15px;
        z-index: 1;
        border-bottom-right-radius: 10px;
        border-top-left-radius: 10px;
        position: absolute;
        top: 10px;
        left: 129px;
        margin: 0 auto;
        width: 135px;
        height: 33px;
    }
</style>
<?php } if($labelvalues['position'] == 3) { ?>
<style>
    .new_product{
        background-color: <?php echo $labelvalues['label_color'];?>;
        color:<?php echo $labelvalues['label_text_color'];?>;
        font-size: 14pt;
        font-weight: 700;
        padding: 8px 45px 15px;
        z-index: 1;
        border-bottom-right-radius: 10px;
        border-top-left-radius: 10px;
        position: absolute;
        top: 213px;
        left: 0px;
        margin: 0 auto;
        width: 135px;
        height: 33px;
    }
</style>
<?php } if($labelvalues['position'] == 4) { ?>
<style>
    .new_product{
        background-color: <?php echo $labelvalues['label_color'];?>;
        color:<?php echo $labelvalues['label_text_color'];?>;
        font-size: 14pt;
        font-weight: 700;
        padding: 8px 45px 15px;
        z-index: 1;
        border-bottom-right-radius: 10px;
        border-top-left-radius: 10px;
        position: absolute;
        top: 213px;
        left: 129px;
        margin: 0 auto;
        width: 135px;
        height: 33px;
    }
</style>
<?php } } } ?>
<!--new product first set-->

<!--new product second set-->

<?php foreach($labels as $labelvalues) {
 if($labelvalues['label_id'] == 2) { ?>
<style>
    .main_image_discount{
        height: 400px;
        width: 400px;
        position: relative;
    }
    .image_discount{
        position: absolute;
        left: 24%;
        top: 13%;
    }
</style>
<?php if($labelvalues['position'] == 1) { ?>
<style>
    .discount_product {
        background-color: <?php echo $labelvalues['label_color'];?>;
        color:<?php echo $labelvalues['label_text_color'];?>;
        float: left;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        text-align: center;
        top: 5%;
        z-index: 1;
        left:0px;
    }

</style>
<?php }  if($labelvalues['position'] == 2) { ?>
<style>
    .discount_product {
        background-color: <?php echo $labelvalues['label_color'];?>;
        color:<?php echo $labelvalues['label_text_color'];?>;
        float: left;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        text-align: center;
        top: 5%;
        z-index: 1;
        left:173px;
    }

</style>
<?php }  if($labelvalues['position'] == 3) { ?>
<style>
    .discount_product {
        background-color: <?php echo $labelvalues['label_color'];?>;
        color:<?php echo $labelvalues['label_text_color'];?>;
        float: left;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        text-align: center;
        top: 83%;
        z-index: 1;
        left:0px;
    }

</style>
<?php }  if($labelvalues['position'] == 4) { ?>
<style>
    .discount_product {
        background-color: <?php echo $labelvalues['label_color'];?>;
        color:<?php echo $labelvalues['label_text_color'];?>;
        float: left;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        text-align: center;
        top: 83%;
        z-index: 1;
        left:173px;
    }

</style>
<?php } } } ?>
<!--new product second set-->

<!--shipping product third set-->
<?php foreach($labels as $labelvalues) {
 if($labelvalues['label_id'] == 3) { ?>
<style>
    .main_image_shipping{
        height: 400px;
        width: 400px;
        position: relative;
    }
    .image_shipping{
        position: absolute;
        left: 24%;
        top: 13%;
    }
</style>
<?php  if($labelvalues['position'] == 1) { ?>
<style>
    .shipping_product{
        background-color: <?php echo $labelvalues['label_color'];?>;
        color:<?php echo $labelvalues['label_text_color'];?>;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        z-index: 1;
        border-bottom-right-radius: 10px;
        border-bottom-left-radius: 10px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        position: absolute;
        top: 20px;
        left: 0px;
        margin: 0 auto;
        width: 129px;
        height: 32px;
    }
</style>
<?php } if($labelvalues['position'] == 2) { ?>
<style>
    .shipping_product{
        background-color: <?php echo $labelvalues['label_color'];?>;
        color:<?php echo $labelvalues['label_text_color'];?>;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        z-index: 1;
        border-bottom-right-radius: 10px;
        border-bottom-left-radius: 10px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        position: absolute;
        top: 20px;
        left: 134px;
        margin: 0 auto;
        width: 129px;
        height: 32px;
    }
</style>
<?php } if($labelvalues['position'] == 3) { ?>
<style>
    .shipping_product{
        background-color: <?php echo $labelvalues['label_color'];?>;
        color:<?php echo $labelvalues['label_text_color'];?>;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        z-index: 1;
        border-bottom-right-radius: 10px;
        border-bottom-left-radius: 10px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        position: absolute;
        top: 217px;
        left: 0px;
        margin: 0 auto;
        width: 129px;
        height: 32px;
    }
</style>
<?php } if($labelvalues['position'] == 4) { ?>
<style>
    .shipping_product{
        background-color: <?php echo $labelvalues['label_color'];?>;
        color:<?php echo $labelvalues['label_text_color'];?>;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        z-index: 1;
        border-bottom-right-radius: 10px;
        border-bottom-left-radius: 10px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        position: absolute;
        top: 217px;
        left: 134px;
        margin: 0 auto;
        width: 129px;
        height: 32px;
    }
</style>
<?php } } } ?>

<!--shipping product third set-->
<!--outofstock product fourth set-->
<?php foreach($labels as $labelvalues) {
 if($labelvalues['label_id'] == 4) { ?>
<style>
    .main_image_outofstock{
        height: 400px;
        width: 400px;
        position: relative;
    }
    .image_outofstock{
        position: absolute;
        left: 24%;
        top: 13%;
    }
</style>
<?php  if($labelvalues['position'] == 1) { ?>
<style>
    .outofstock_product{
        background-color: <?php echo $labelvalues['label_color'];?>;
        color:<?php echo $labelvalues['label_text_color'];?>;
        float: left;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        text-align: center;
        top: 5%;
        z-index: 1;
        left:0px;
    }
</style>
<?php } if($labelvalues['position'] == 2) { ?>
<style>
    .outofstock_product{
        background-color: <?php echo $labelvalues['label_color'];?>;
        color:<?php echo $labelvalues['label_text_color'];?>;
        float: left;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        text-align: center;
        top: 5%;
        z-index: 1;
        left:150px;
    }
</style>
<?php } if($labelvalues['position'] == 3) { ?>
<style>
    .outofstock_product{
        background-color: <?php echo $labelvalues['label_color'];?>;
        color:<?php echo $labelvalues['label_text_color'];?>;
        float: left;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        text-align: center;
        top: 83%;
        z-index: 1;
        left:0px;
    }
</style>
<?php } if($labelvalues['position'] == 4) { ?>
<style>
    .outofstock_product{
        background-color: <?php echo $labelvalues['label_color'];?>;
        color:<?php echo $labelvalues['label_text_color'];?>;
        float: left;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        text-align: center;
        top: 83%;
        z-index: 1;
        left:140px;
    }
</style>
<?php } } } ?>

<!--outofstock product fourth set-->

<!--custom product fifth set-->
<?php foreach($labels as $labelvalues) {
 if($labelvalues['label_id'] == 5) { ?>
<style>
    .main_image_custom{
        height: 400px;
        width: 400px;
        position: relative;
    }
    .image_custom{
        position: absolute;
        left: 24%;
        top: 13%;
    }
</style>
<?php  if($labelvalues['position'] == 1) { ?>
<style>
    .custom_product{
        background-color: <?php echo $labelvalues['label_color'];?>;
        color:<?php echo $labelvalues['label_text_color'];?>;
        float: left;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        text-align: center;
        top: 5%;
        z-index: 1;
        left:0px;
    }
</style>
<?php } if($labelvalues['position'] == 2) { ?>
<style>
    .custom_product{
        background-color: <?php echo $labelvalues['label_color'];?>;
        color:<?php echo $labelvalues['label_text_color'];?>;
        float: left;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        text-align: center;
        top: 5%;
        z-index: 1;
        left:150px;
    }
</style>
<?php } if($labelvalues['position'] == 3) { ?>
<style>
    .custom_product{
        background-color: <?php echo $labelvalues['label_color'];?>;
        color:<?php echo $labelvalues['label_text_color'];?>;
        float: left;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        text-align: center;
        top: 83%;
        z-index: 1;
        left:0px;
    }
</style>
<?php } if($labelvalues['position'] == 4) { ?>
<style>
    .custom_product{
        background-color: <?php echo $labelvalues['label_color'];?>;
        color:<?php echo $labelvalues['label_text_color'];?>;
        float: left;
        font-size: 12pt;
        font-weight: 700;
        padding: 6px 8px 5px;
        position: absolute;
        text-align: center;
        top: 83%;
        z-index: 1;
        left:140px;
    }
</style>
<?php } } } ?>

<!--custom product fifth set-->

<link type="text/css" href="view/javascript/bootstrap/css/bootstrap-colorpicker.css" rel="stylesheet" media="screen" />
<link type="text/css" href="view/javascript/bootstrap/css/bootstrap-colorpicker.min.css" rel="stylesheet" media="screen" />
<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-language" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>

        <div class="panel-body">
            <form action="<?php echo $action; ?>" method="POST" enctype="multipart/form-data" id="form-language" class="form-horizontal">
        </div>

        <!--First Set-->
        <?php foreach($labels as $labelvalues) {
                if($labelvalues['label_id'] == 1) { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> New</h3>
            </div>
            <!--<div class="panel-body">
                <form action="<?php echo $action; ?>" method="POST" enctype="multipart/form-data" id="form-language" class="form-horizontal">-->
            <div class="panel-body">
                <div class="well">

                    <div class="row">

                        <div class="col-sm-6">

                            <div class="form-group">
                                <label for="input-name" class="control-label"><?php echo "Label Text"; ?></label>
                                <input type="text" name="label_text[1]" value="<?php echo $labelvalues['label_text']; ?>" placeholder="<?php echo $text_label_text; ?>" id="input_new" class="form-control"  />
                            </div>

                            <label for="input-name" class="control-label"><?php echo "Label Color"; ?></label>
                            <div class="input-group color">
                                <input type="text" name="label_color[1]" value="<?php echo $labelvalues['label_color']; ?>"  class="form-control" id="input_label_color" />
                                <span class="input-group-addon"><i></i></span>
                            </div>

                            <label for="input-name" class="control-label"><?php echo "Label Text Color"; ?></label>
                            <div class="input-group color">
                                <input type="text" name="label_text_color[1]" value="<?php echo $labelvalues['label_text_color']; ?>"  class="form-control" id="input_label_text_color" />
                                <span class="input-group-addon"><i></i></span>
                            </div>

                            <div class="form-group">
                                <label for="input-name" class="control-label"><?php echo "New Product Duration Days"; ?></label>
                                <input type="text" name="condition_type[1]" value="<?php echo $labelvalues['condition_type']; ?>"  class="form-control" />
                            </div>


                            <label for="input-name" class="control-label"><?php echo "Label Position"; ?></label><br>

                            <input type="radio" name="position1" value="1" class="position_set_new"  <?php echo ($labelvalues['position']=='1' ? 'checked=checked' : ''); ?> ><label for="input-name" class="control-label"><?php echo "Top Left"; ?></label>
                            <input type="radio" name="position1" value="2" class="position_set_new" <?php echo ($labelvalues['position']=='2' ? 'checked=checked' : ''); ?> ><label for="input-name" class="control-label"><?php echo "Top Right"; ?></label>
                            <input type="radio" name="position1" value="3" class="position_set_new" <?php echo ($labelvalues['position']=='3' ? 'checked=checked' : ''); ?> ><label for="input-name" class="control-label"><?php echo "Bottom Left"; ?></label>
                            <input type="radio" name="position1" value="4" class="position_set_new" <?php echo ($labelvalues['position']=='4' ? 'checked=checked' : ''); ?> ><label for="input-name" class="control-label"><?php echo "Bottom Right"; ?></label>



                            <div class="form-group">
                                <label for="input-name" class="control-label"><?php echo "Status"; ?></label>
                                <select name="status[1]" id="input-status" class="form-control"  >
                                    <?php if ($labelvalues['status']) { ?>
                                    <option value="1" selected="selected"><?php echo $text_addlabel; ?></option>
                                    <option value="0"><?php echo $text_disabled; ?></option>
                                    <?php } else { ?>
                                    <option value="1"><?php echo $text_addlabel; ?></option>
                                    <option value="0"selected="selected"><?php echo $text_disabled; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <input type="hidden" name="label_id[1]" value="<?php echo $labelvalues['label_id']; ?>" placeholder="<?php echo $text_label_id; ?>" id="input-code" class="form-control"  />


                        </div>


                        <div class="main_image_new col-sm-6">
                            <div class="image_new ">
                                <!--<label for="input-name" class="control-label"><?php echo "Preview New"; ?></label>-->
                                <img  src="view/image/dummy_product_image_big.gif"  >
                                <div class="new_product"><?php echo $labelvalues['label_text']; ?></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <?php } } ?>

        <!--First Set-->

        <!--second Set-->
        <?php foreach($labels as $labelvalues) {
                            if($labelvalues['label_id'] == 2) { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> Discount</h3>
            </div>
            <div class="panel-body">

                <div class="well">
                    <div class="row">

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="input-name" class="control-label"><?php echo "Label Text"; ?></label>
                                <input type="text" name="label_text[2]" value="<?php echo $labelvalues['label_text']; ?>"  placeholder="<?php echo $text_label_text; ?>" id="input_discount"class="form-control" />
                            </div>

                            <label for="input-name" class="control-label"><?php echo "Label Color"; ?></label>
                            <div class="input-group color">
                                <input type="text" name="label_color[2]" value="<?php echo $labelvalues['label_color']; ?>"  class="form-control" id="input_discount_label_color" />
                                <span class="input-group-addon"><i></i></span>
                            </div>

                            <label for="input-name" class="control-label"><?php echo "Label Text Color"; ?></label>
                            <div class="input-group color">
                                <input type="text" name="label_text_color[2]" value="<?php echo $labelvalues['label_text_color']; ?>"  class="form-control" id="input_discount_label_text_color" />
                                <span class="input-group-addon"><i></i></span>
                            </div>

                            <div class="form-group">
                                <label for="input-name" class="control-label"><?php echo "Product Minimum Percentage"; ?></label>
                                <input type="text" name="condition_type[2]" value="<?php echo $labelvalues['condition_type']; ?>"  class="form-control"  />
                            </div>

                            <label for="input-name" class="control-label"><?php echo "Label Position"; ?></label><br>
                            <input type="radio" name="position2" value="1" class="position_set_discount" <?php echo ($labelvalues['position'] == '1' ? 'checked=checked' : ''); ?> ><label for="input-name" class="control-label"><?php echo "Top Left"; ?></label>
                            <input type="radio" name="position2" value="2" class="position_set_discount" <?php echo ($labelvalues['position'] == '2' ? 'checked=checked' : ''); ?> ><label for="input-name" class="control-label"><?php echo "Top Right"; ?></label>
                            <input type="radio" name="position2" value="3" class="position_set_discount" <?php echo ($labelvalues['position'] == '3' ? 'checked=checked' : ''); ?> ><label for="input-name" class="control-label"><?php echo "Bottom Left"; ?></label>
                            <input type="radio" name="position2" value="4" class="position_set_discount" <?php echo ($labelvalues['position'] == '4' ? 'checked=checked' : ''); ?> ><label for="input-name" class="control-label"><?php echo "Bottom Right"; ?></label>

                            <div class="form-group">
                                <label for="input-name" class="control-label"><?php echo "Status"; ?></label>
                                <select name="status[2]" id="input-status"  class="form-control">
                                    <?php if ($labelvalues['status']) { ?>
                                    <option value="1" selected="selected"><?php echo $text_addlabel; ?></option>
                                    <option value="0"><?php echo $text_disabled; ?></option>
                                    <?php } else { ?>
                                    <option value="1"><?php echo $text_addlabel; ?></option>
                                    <option value="0"selected="selected"><?php echo $text_disabled; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <input type="hidden" name="label_id[2]" value="<?php echo $labelvalues['label_id']; ?>" placeholder="<?php echo $text_label_id; ?>" id="input-code" class="form-control"  />

                        </div>

                        <div class="main_image_discount col-sm-6">
                            <div class="image_discount"><img src="view/image/dummy_product_image_big.gif">
                                <div class="discount_product"><?php echo $labelvalues['label_text']; ?></div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
        <?php } } ?>
        <!--second Set-->

        <!--fourth Set-->
        <?php foreach($labels as $labelvalues) {
                                if($labelvalues['label_id'] == 4) { ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> Out Of Stock</h3>
            </div>
            <div class="panel-body">
                <div class="well">
                    <div class="row">

                        <div class="col-sm-6">

                            <div class="form-group">
                                <label for="input-name" class="control-label"><?php echo "Label Text"; ?></label>
                                <input type="text"  name="label_text[4]" value="<?php echo $labelvalues['label_text']; ?>" placeholder="<?php echo $text_label_text; ?>" id="input_outofstock" class="form-control" />
                            </div>
                            <label for="input-name" class="control-label"><?php echo "Label Color"; ?></label>
                            <div class="input-group color">
                                <input type="text" name="label_color[4]" value="<?php echo $labelvalues['label_color']; ?>"  class="form-control" id="input_outofstock_label_color" />
                                <span class="input-group-addon"><i></i></span>
                            </div>

                            <label for="input-name" class="control-label"><?php echo "Label Text Color"; ?></label>
                            <div class="input-group color">
                                <input type="text" name="label_text_color[4]" value="<?php echo $labelvalues['label_text_color']; ?>"  class="form-control" id="input_outofstock_label_text_color" />
                                <span class="input-group-addon"><i></i></span><br>
                            </div>

                            <label for="input-name" class="control-label"><?php echo "Label Position"; ?></label><br>
                            <input type="radio" name="position4" value="1" class="position_set_outofstock" <?php echo ($labelvalues['position'] == '1' ? 'checked=checked' : ''); ?>  ><label for="input-name" class="control-label"><?php echo "Top Left"; ?></label>
                            <input type="radio" name="position4" value="2" class="position_set_outofstock" <?php echo ($labelvalues['position'] == '2' ? 'checked=checked' : ''); ?> ><label for="input-name" class="control-label"><?php echo "Top Right"; ?></label>
                            <input type="radio" name="position4" value="3" class="position_set_outofstock" <?php echo ($labelvalues['position'] == '3' ? 'checked=checked' : ''); ?> ><label for="input-name" class="control-label"><?php echo "Bottom Left"; ?></label>
                            <input type="radio" name="position4" value="4" class="position_set_outofstock" <?php echo ($labelvalues['position'] == '4' ? 'checked=checked' : ''); ?> ><label for="input-name" class="control-label"><?php echo "Bottom Right"; ?></label>


                            <div class="form-group">
                                <label for="input-name" class="control-label"><?php echo "Status"; ?></label>
                                <select name="status[4]" id="input-status" class="form-control" id="input_outofstock_status">
                                    <?php if ($labelvalues['status']) { ?>
                                    <option value="1" selected="selected"><?php echo $text_addlabel; ?></option>
                                    <option value="0"><?php echo $text_disabled; ?></option>
                                    <?php } else { ?>
                                    <option value="1"><?php echo $text_addlabel; ?></option>
                                    <option value="0"selected="selected"><?php echo $text_disabled; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <input type="hidden" name="label_id[4]" value="<?php echo $labelvalues['label_id']; ?>" placeholder="<?php echo $text_label_id; ?>" id="input-code" class="form-control"  />

                        </div>

                        <div class="main_image_outofstock col-sm-6">
                            <div class="image_outofstock">
                                <img src="view/image/dummy_product_image_big.gif">
                                <div class="outofstock_product"><?php echo $labelvalues['label_text']; ?></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <?php } } ?>
        <!--fourth Set-->
        <!--fifth Set-->
        <?php foreach($labels as $labelvalues) {
                                if($labelvalues['label_id'] == 5) { ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> Custom Product Label 1</h3>
            </div>
            <div class="panel-body">
                <div class="well">
                    <div class="row">

                        <div class="col-sm-6">

                            <div class="form-group">
                                <label for="input-name" class="control-label"><?php echo "Label Text"; ?></label>
                                <input type="text"  name="label_text[5]" value="<?php echo $labelvalues['label_text']; ?>" placeholder="<?php echo $text_label_text; ?>" id="input_custom" class="form-control" />
                            </div>
                            <label for="input-name" class="control-label"><?php echo "Label Color"; ?></label>
                            <div class="input-group color">
                                <input type="text" name="label_color[5]" value="<?php echo $labelvalues['label_color']; ?>"  class="form-control" id="input_custom_label_color" />
                                <span class="input-group-addon"><i></i></span>
                            </div>

                            <label for="input-name" class="control-label"><?php echo "Label Text Color"; ?></label>
                            <div class="input-group color">
                                <input type="text" name="label_text_color[5]" value="<?php echo $labelvalues['label_text_color']; ?>"  class="form-control" id="input_custom_label_text_color" />
                                <span class="input-group-addon"><i></i></span><br>
                            </div>

                            <label for="input-name" class="control-label"><?php echo "Label Position"; ?></label><br>
                            <input type="radio" name="position5" value="1" class="position_set_custom" <?php echo ($labelvalues['position'] == '1' ? 'checked=checked' : ''); ?>  ><label for="input-name" class="control-label"><?php echo "Top Left"; ?></label>
                            <input type="radio" name="position5" value="2" class="position_set_custom" <?php echo ($labelvalues['position'] == '2' ? 'checked=checked' : ''); ?> ><label for="input-name" class="control-label"><?php echo "Top Right"; ?></label>
                            <input type="radio" name="position5" value="3" class="position_set_custom" <?php echo ($labelvalues['position'] == '3' ? 'checked=checked' : ''); ?> ><label for="input-name" class="control-label"><?php echo "Bottom Left"; ?></label>
                            <input type="radio" name="position5" value="4" class="position_set_custom" <?php echo ($labelvalues['position'] == '4' ? 'checked=checked' : ''); ?> ><label for="input-name" class="control-label"><?php echo "Bottom Right"; ?></label>


                            <div class="form-group">
                                <label for="input-name" class="control-label"><?php echo "Status"; ?></label>
                                <select name="status[5]" id="input-status" class="form-control" id="input_custom_status">
                                    <?php if ($labelvalues['status']) { ?>
                                    <option value="1" selected="selected"><?php echo $text_addlabel; ?></option>
                                    <option value="0"><?php echo $text_disabled; ?></option>
                                    <?php } else { ?>
                                    <option value="1"><?php echo $text_addlabel; ?></option>
                                    <option value="0"selected="selected"><?php echo $text_disabled; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <input type="hidden" name="label_id[5]" value="<?php echo $labelvalues['label_id']; ?>" placeholder="<?php echo $text_label_id; ?>" id="input-code" class="form-control"  />

                        </div>

                        <div class="main_image_custom col-sm-6">
                            <div class="image_custom">
                                <img src="view/image/dummy_product_image_big.gif">
                                <div class="custom_product"><?php echo $labelvalues['label_text']; ?></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <?php } } ?>
        <!--fifth Set-->

        <!--third Set-->
        <?php foreach($labels as $labelvalues) {
                                if($labelvalues['label_id'] == 3) { ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> Custom Product Label 2</h3>
            </div>
            <div class="panel-body">
                <div class="well">
                    <div class="row">

                        <div class="col-sm-6">

                            <div class="form-group">
                                <label for="input-name" class="control-label"><?php echo "Label Text"; ?></label>
                                <input type="text"  name="label_text[3]" value="<?php echo $labelvalues['label_text']; ?>" placeholder="<?php echo $text_label_text; ?>" id="input_shipping" class="form-control" />
                            </div>
                            <label for="input-name" class="control-label"><?php echo "Label Color"; ?></label>
                            <div class="input-group color">
                                <input type="text" name="label_color[3]" value="<?php echo $labelvalues['label_color']; ?>"  class="form-control" id="input_shipping_label_color" />
                                <span class="input-group-addon"><i></i></span>
                            </div>

                            <label for="input-name" class="control-label"><?php echo "Label Text Color"; ?></label>
                            <div class="input-group color">
                                <input type="text" name="label_text_color[3]" value="<?php echo $labelvalues['label_text_color']; ?>"  class="form-control" id="input_shipping_label_text_color" />
                                <span class="input-group-addon"><i></i></span><br>
                            </div>

                            <label for="input-name" class="control-label"><?php echo "Label Position"; ?></label><br>
                            <input type="radio" name="position3" value="1" class="position_set_shipping" <?php echo ($labelvalues['position'] == '1' ? 'checked=checked' : ''); ?>  ><label for="input-name" class="control-label"><?php echo "Top Left"; ?></label>
                            <input type="radio" name="position3" value="2" class="position_set_shipping" <?php echo ($labelvalues['position'] == '2' ? 'checked=checked' : ''); ?> ><label for="input-name" class="control-label"><?php echo "Top Right"; ?></label>
                            <input type="radio" name="position3" value="3" class="position_set_shipping" <?php echo ($labelvalues['position'] == '3' ? 'checked=checked' : ''); ?> ><label for="input-name" class="control-label"><?php echo "Bottom Left"; ?></label>
                            <input type="radio" name="position3" value="4" class="position_set_shipping" <?php echo ($labelvalues['position'] == '4' ? 'checked=checked' : ''); ?> ><label for="input-name" class="control-label"><?php echo "Bottom Right"; ?></label>


                            <div class="form-group">
                                <label for="input-name" class="control-label"><?php echo "Status"; ?></label>
                                <select name="status[3]" id="input-status" class="form-control" id="input_shipping_status">
                                    <?php if ($labelvalues['status']) { ?>
                                    <option value="1" selected="selected"><?php echo $text_addlabel; ?></option>
                                    <option value="0"><?php echo $text_disabled; ?></option>
                                    <?php } else { ?>
                                    <option value="1"><?php echo $text_addlabel; ?></option>
                                    <option value="0"selected="selected"><?php echo $text_disabled; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <input type="hidden" name="label_id[3]" value="<?php echo $labelvalues['label_id']; ?>" placeholder="<?php echo $text_label_id; ?>" id="input-code" class="form-control"  />

                        </div>

                        <div class="main_image_shipping col-sm-6">
                            <div class="image_shipping">
                                <img src="view/image/dummy_product_image_big.gif">
                                <div class="shipping_product"><?php echo $labelvalues['label_text']; ?></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <?php } } ?>
        <!--third Set-->

    </div>
    </form>
</div>
<?php echo $footer; ?>
<script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap-colorpicker.min.js"></script>
<script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap-colorpicker.js"></script>

<script type="text/javascript">

    $(document).ready(function(){
        $('.color').colorpicker();

        $('.color').colorpicker().on('changeColor.colorpicker', function(event){
            //bodyStyle.backgroundColor = event.color.toHex();
            //console.log("Change Evevt");
            //take new product label and text color
            var label_color= $("#input_label_color").val();
            $('.new_product').css('background-color',label_color);

            var label_text_color=$("#input_label_text_color").val();
            $(".new_product").css('color',label_text_color);

            //take discount product label and text color
            var label_color_discount= $("#input_discount_label_color").val();
            $('.discount_product').css('background-color',label_color_discount);

            var label_text_color_dicount=$("#input_discount_label_text_color").val();
            $(".discount_product").css('color',label_text_color_dicount);

            //take shipping product label and text color
            var label_color_shipping= $("#input_shipping_label_color").val();
            $('.shipping_product').css('background-color',label_color_shipping);

            var label_text_color_shipping=$("#input_shipping_label_text_color").val();
            $(".shipping_product").css('color',label_text_color_shipping);

            //take outofstock product label and text color
            var label_color_outofstock= $("#input_outofstock_label_color").val();
            $('.outofstock_product').css('background-color',label_color_outofstock);

            var label_text_color_outofstock=$("#input_outofstock_label_text_color").val();
            $(".outofstock_product").css('color',label_text_color_outofstock);

            //take custom product label and text color
            var label_color_custom= $("#input_custom_label_color").val();
            $('.custom_product').css('background-color',label_color_custom);

            var label_text_color_custom=$("#input_custom_label_text_color").val();
            $(".custom_product").css('color',label_text_color_custom);
        });


    });
</script>
<!--new product first set-->
<script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
<script>
    $(document).ready(function(){

        //$("div.colorpicker").find('div.colorpicker-saturation').find("i").attr("id","arun-id");

        $("#input_new").on("keyup",function(){
            var text= $("#input_new").val();
            //alert(text);
            $(".new_product").html(text);
        });

    });
</script>
<!--new product first set-->


<!--discount product second set-->
<script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
<script>
    $(document).ready(function(){

        $("#input_discount").on("keyup",function(){
            var text_discount= $("#input_discount").val();
            $(".discount_product").html(text_discount);
        });

    });
</script>
<!--discount product second set-->

<!--shipping product third set-->
<script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
<script>
    $(document).ready(function(){

        $("#input_shipping").on("keyup",function(){
            var text_shipping=$("#input_shipping").val();
            $(".shipping_product").html(text_shipping);
        });


    });
</script>
<!--shipping product third set-->

<!--outofstock_product fourth set-->
<script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
<script>
    $(document).ready(function(){

        $("#input_outofstock").on("keyup",function(){
            var text_outofstock=$("#input_outofstock").val();
            $(".outofstock_product").html(text_outofstock);
        });
    });
</script>
<!--outofstock_product fourth set-->

<!--custom_product fourth set-->
<script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
<script>
    $(document).ready(function(){

        $("#input_custom").on("keyup",function(){
            var text_custom=$("#input_custom").val();
            $(".custom_product").html(text_custom);
        });
    });
</script>
<!--custom_product fourth set-->

<!--set position for the new product-->
<?php foreach($labels as $labelvalues) {
 if($labelvalues['label_id'] == 1) { ?>
<script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
<script>
    $(document).ready(function(){

        $('.color').colorpicker();

        $('.color').colorpicker().on('changeColor.colorpicker', function(event){

            var label_color= $("#input_label_color").val();
            $('.new_product').css('background-color',label_color);

            var label_text_color=$("#input_label_text_color").val();
            $(".new_product").css('color',label_text_color);

        });

        $(".position_set_new").on('click',function(){
            var position=$(this).val();
            //alert(topright);
            if(position==1){
                $('.new_product').css({'left':'0px','top': '10px','position': 'absolute','margin': '0 auto','width': '135px','height': '33px','font-size': '14pt','font-weight': '700','padding': '8px 45px 15px','z-index':'1'});
            }
            if(position==2){
                $('.new_product').css({'left':'128','top': '10px','position': 'absolute','margin': '0 auto','width': '135px','height': '33px','font-size': '14pt','font-weight': '700','padding': '8px 45px 15px','z-index':'1'});
            }
            if(position==3){
                $('.new_product').css({'top':'213px','left':'0px','position': 'absolute','margin': '0 auto','width': '135px','height': '33px','font-size': '14pt','font-weight': '700','padding': '8px 45px 15px','z-index':'1'});
            }
            if(position==4){
                $('.new_product').css({'top':'213px','left':'128','position': 'absolute','margin': '0 auto','width': '135px','height': '33px','font-size': '14pt','font-weight': '700','padding': '8px 45px 15px','z-index':'1'});
            }
        });
    });

</script>
<?php } } ?>
<!--set position for the new product-->

<!--discount product second set-->
<?php foreach($labels as $labelvalues) {
if($labelvalues['label_id'] == 2) { ?>
<script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
<script>
    $(document).ready(function(){

        $('.color').colorpicker();

        $('.color').colorpicker().on('changeColor.colorpicker', function(event){

            //take discount product label and text color
            var label_color_discount= $("#input_discount_label_color").val();
            $('.discount_product').css('background-color',label_color_discount);

            var label_text_color_dicount=$("#input_discount_label_text_color").val();
            $(".discount_product").css('color',label_text_color_dicount);


        });

        $('.position_set_discount').on('click',function(){
            var position=$(this).val();
            //alert(position);
            if(position==1){
                //alert(1);
                $('.discount_product').css({'left':'0px','float': 'left','font-size': '12pt','font-weight': '700','padding': '6px 8px 5px','position': 'absolute','text-align': 'center','top': '5%','z-index': '1'});
            }
            if(position==2){
                //alert(1);
                $('.discount_product').css({'left':'173px','float': 'left','font-size': '12pt','font-weight': '700','padding': '6px 8px 5px','position': 'absolute','text-align': 'center','top': '5%','z-index': '1'});
            }
            if(position==3){
                //alert(1);
                $('.discount_product').css({'left':'0px','float': 'left','font-size': '12pt','font-weight': '700','padding': '6px 8px 5px','position': 'absolute','text-align': 'center','top': '83%','z-index': '1'});
            }
            if(position==4){
                //alert(1);
                $('.discount_product').css({'left':'173px','float': 'left','font-size': '12pt','font-weight': '700','padding': '6px 8px 5px','position': 'absolute','text-align': 'center','top': '83%','z-index': '1'});
            }
        }) ;
    });
</script>
<?php } } ?>
<!--discount product second set-->

<!--shipping product third set-->
<?php foreach($labels as $labelvalues) {
 if($labelvalues['label_id'] == 3) { ?>
<script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
<script>
    $(document).ready(function(){


        $('.color').colorpicker();

        $('.color').colorpicker().on('changeColor.colorpicker', function(event){

            //take shipping product label and text color
            var label_color_shipping= $("#input_shipping_label_color").val();
            $('.shipping_product').css('background-color',label_color_shipping);

            var label_text_color_shipping=$("#input_shipping_label_text_color").val();
            $(".shipping_product").css('color',label_text_color_shipping);


        });

        $(".position_set_shipping").on('click',function(){
            var position=$(this).val();
            //alert(topright);
            if(position==1){
                $('.shipping_product').css({'left':'0px','top': '20px','position': 'absolute','margin': '0 auto','width': '129px','height': '32px','font-size': '12pt','font-weight': '700','padding': '6px 8px 5px','z-index':'1','border-bottom-right-radius': '10px','border-bottom-right-radius': '10px','border-top-right-radius': '10px','border-top-left-radius': '10px'});
            }
            if(position==2){
                $('.shipping_product').css({'left':'134px','top': '20px','position': 'absolute','margin': '0 auto','width': '129px','height': '32px','font-size': '12pt','font-weight': '700','padding': '6px 8px 5px','z-index':'1','border-bottom-right-radius': '10px','border-bottom-right-radius': '10px','border-top-right-radius': '10px','border-top-left-radius': '10px'});
            }
            if(position==3){
                $('.shipping_product').css({'top':'217px','left':'0px','position': 'absolute','margin': '0 auto','width': '129px','height': '32px','font-size': '12pt','font-weight': '700','padding': '6px 8px 5px','z-index':'1','border-bottom-right-radius': '10px','border-bottom-right-radius': '10px','border-top-right-radius': '10px','border-top-left-radius': '10px'});
            }
            if(position==4){
                $('.shipping_product').css({'top':'217px','left':'134px','position': 'absolute','margin': '0 auto','width': '129px','height': '32px','font-size': '12pt','font-weight': '700','padding': '6px 8px 5px','z-index':'1','border-bottom-right-radius': '10px','border-bottom-right-radius': '10px','border-top-right-radius': '10px','border-top-left-radius': '10px'});
            }
        });
    });

</script>
<?php } } ?>
<!--shipping product third set-->


<!--outofstock_product fourth set-->
<?php foreach($labels as $labelvalues) {
 if($labelvalues['label_id'] == 4) { ?>
<script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
<script>
    $(document).ready(function(){


        $('.color').colorpicker();

        $('.color').colorpicker().on('changeColor.colorpicker', function(event){

            //take shipping product label and text color
            var label_color_outofstock= $("#input_outofstock_label_color").val();
            $('.outofstock_product').css('background-color',label_color_outofstock);

            var label_text_color_outofstock=$("#input_outofstock_label_text_color").val();
            $(".outofstock_product").css('color',label_text_color_outofstock);


        });

        $(".position_set_outofstock").on('click',function(){
            var position=$(this).val();
            //alert(topright);



            if(position==1){
                //alert(1);
                $('.outofstock_product').css({'left':'0px','float': 'left','font-size': '12pt','font-weight': '700','padding': '6px 8px 5px','position': 'absolute','text-align': 'center','top': '5%','z-index': '1'});
            }
            if(position==2){
                //alert(1);
                $('.outofstock_product').css({'left':'150px','float': 'left','font-size': '12pt','font-weight': '700','padding': '6px 8px 5px','position': 'absolute','text-align': 'center','top': '5%','z-index': '1'});
            }
            if(position==3){
                //alert(1);
                $('.outofstock_product').css({'left':'0px','float': 'left','font-size': '12pt','font-weight': '700','padding': '6px 8px 5px','position': 'absolute','text-align': 'center','top': '83%','z-index': '1'});
            }
            if(position==4){
                //alert(1);
                $('.outofstock_product').css({'left':'140px','float': 'left','font-size': '12pt','font-weight': '700','padding': '6px 8px 5px','position': 'absolute','text-align': 'center','top': '83%','z-index': '1'});
            }

        });
    });

</script>
<?php } } ?>
<!--outofstock_product fourth set-->

<!--custom_product fifth set-->
<?php foreach($labels as $labelvalues) {
 if($labelvalues['label_id'] == 5) { ?>
<script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
<script>
    $(document).ready(function(){


        $('.color').colorpicker();

        $('.color').colorpicker().on('changeColor.colorpicker', function(event){

            //take shipping product label and text color
            var label_color_custom= $("#input_custom_label_color").val();
            $('.custom_product').css('background-color',label_color_custom);

            var label_text_color_custom=$("#input_custom_label_text_color").val();
            $(".custom_product").css('color',label_text_color_custom);


        });

        $(".position_set_custom").on('click',function(){
            var position=$(this).val();
            //alert(topright);



            if(position==1){
                //alert(1);
                $('.custom_product').css({'left':'0px','float': 'left','font-size': '12pt','font-weight': '700','padding': '6px 8px 5px','position': 'absolute','text-align': 'center','top': '5%','z-index': '1'});
            }
            if(position==2){
                //alert(1);
                $('.custom_product').css({'left':'150px','float': 'left','font-size': '12pt','font-weight': '700','padding': '6px 8px 5px','position': 'absolute','text-align': 'center','top': '5%','z-index': '1'});
            }
            if(position==3){
                //alert(1);
                $('.custom_product').css({'left':'0px','float': 'left','font-size': '12pt','font-weight': '700','padding': '6px 8px 5px','position': 'absolute','text-align': 'center','top': '83%','z-index': '1'});
            }
            if(position==4){
                //alert(1);
                $('.custom_product').css({'left':'140px','float': 'left','font-size': '12pt','font-weight': '700','padding': '6px 8px 5px','position': 'absolute','text-align': 'center','top': '83%','z-index': '1'});
            }

        });
    });

</script>
<?php } } ?>
<!--custom_product fifth set-->
 