<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="button" id="button-import" data-toggle="tooltip" title="<?php echo $button_import; ?>" class="btn btn-success"><i class="fa fa fa-upload"></i></button>
        <button type="submit" form="form-google-base" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
            <form onsubmit="return ValidateMe()" action="<?php echo $action_export; ?>" method="post" enctype="multipart/form-data" id="form-export" class="col-sm-6 col-xs-12">
                <table class="form table table-bordered">
                    <tr>
                        <td>
                            <select id="select_category" name="select_category" class="form-control">
                                <option value="0" selected="selected"><?php echo $text_select_cate; ?></option>
                                <?php foreach ($category_list as $cate_list) { ?>
                                    <option value="<?php echo $cate_list['category_id']; ?>"><?php echo $cate_list['name']; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        
                        <td>
                            <select id="select_cust_group" name="select_cust_group" class="form-control">
                                <option value="0" selected="selected"><?php echo $text_select_cust; ?></option>
                                <?php foreach ($cust_group_list as $cust_list) { ?>
                                    <option value="<?php echo $cust_list['customer_group_id']; ?>"><?php echo $cust_list['name']; ?></option>
                                <?php } ?>
                            </select>
                        </td>

                    <table class="list table table-bordered" id="discount">
                        <thead class="">
                            <tr>
                                <td class="right">Quantity:</td>
                                <td class="right">Discount Percentage:</td>
                            </tr>
                        </thead>
                        <tbody id="discount-row0">
                            <tr>
                                <td class="right"><input type="text" value="1" name="product_discount[0][quantity]" class=" form-control"></td>
                                <td class="right">
                                    <input type="text" value="8" name="product_discount[0][price]" class="prod_discount_val form-control">
                                </td>
                            </tr>
                        </tbody>
                           <tbody id="discount-row0">
                            <tr>
                                <td class="right"><input type="text" value="10" name="product_discount[1][quantity]" class=" form-control"></td>
                                <td class="right">
                                    <input type="text" value="13" name="product_discount[1][price]" class="prod_discount_val form-control">
                                </td>
                            </tr>
                        </tbody>
                           <tbody id="discount-row0">
                            <tr>
                                <td class="right"><input type="text" value="50" name="product_discount[2][quantity]" class=" form-control"></td>
                                <td class="right">
                                    <input type="text" value="18" name="product_discount[2][price]" class="prod_discount_val form-control">
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>
                    <td>
                        <input type="submit" value="UPDATE DISCOUNTS" class="button" style="background: none repeat scroll 0 0 #003A88;
border-radius: 10px;
color: #FFFFFF;
display: inline-block;
padding: 5px 15px;
text-decoration: none;
cursor: pointer;
">                         
                        <!--<div class="buttons"><a onclick="$('#form-export').submit();" href="<?php echo $export_link; ?>" class="button"><?php echo $button_export; ?></a></div>-->
                    </td>
                    </tr>          
                </table>
            </form>
        </div>
    </div>
</div>
<script>
    function ValidateMe(){
        var selectVal = $( "#select_category" ).val();
        if(selectVal == 0){
            alert("Please Select Category")
            return false;
        }else{
            return true;
        }
    }
    </script>
<?php echo $footer; ?>