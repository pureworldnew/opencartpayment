<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
            		<button onclick="$('#form').submit();" type="submit" form="form" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Save"><i class="fa fa-save"></i></button>
            		<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-primary"><i class="fa fa-reply"></i></a></div>
                <h1><?php echo $heading_title; ?></h1>
              <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
              </ul>
        </div>
      </div>

        <div class="container-fluid">
            <form action="<?php echo $action;?>" method="post" enctype="multipart/form-data" id="form">
               <table class="table table-bordered table-hover">
                    <tr>
                        <td><?php echo $entry_status;?></td>
                        <td><select name="ie_tool_status">
                                <?php if ($ie_tool_status) {?>
                                    <option value="1" selected="selected"><?php echo $text_enabled;?></option>
                                    <option value="0"><?php echo $text_disabled;?></option>
                                <?php } else {?>
                                    <option value="1"><?php echo $text_enabled;?></option>
                                    <option value="0" selected="selected"><?php echo $text_disabled;?></option>
                                <?php }?>
                            </select></td>
                    </tr>
                    </table>
            </form>
            <?php /* <h2>Export Simpler Products.</h2>
            <form action="<?php echo $action_export;?>" method="post" enctype="multipart/form-data" id="form-export">
                <table class="table table-bordered table-hover">
                    <tr>
                        <td>
                            <select name="select_category">
                                <option value="0" selected="selected"><?php echo $text_select_cate;?></option>
                                <?php foreach ($category_list as $cate_list) {?>
                                    <option value="<?php echo $cate_list['category_id'];?>"><?php echo $cate_list['name'];?></option>
                                <?php }?>
                            </select>
                        </td>
                        <td>
                            <?php echo $text_or;?>
                        </td>
                        <td>
                            <select name="select_manufacture">
                                <option value="0" selected="selected"><?php echo $text_select_manu;?></option>
                                <?php foreach ($manufacture_list as $manu_list) {?>
                                    <option value="<?php echo $manu_list['manufacturer_id'];?>"><?php echo $manu_list['name'];?></option>
                                <?php }?>
                            </select>
                        </td>
                        <td>
                            <input type="submit" value="<?php echo $button_export;?>">                         
                            <!--<div class="buttons"><a onclick="$('#form-export').submit();" href="<?php echo $export_link;?>" class="button"><?php echo $button_export;?></a></div>-->
                        </td>
                    </tr>          
                </table>
            </form> */ ?>
            <h2>Export Concatenated Products.</h2>
            <span>*Note : If no Category and Manufacture is selected then all products will be exported.</span>
            <form action="<?php echo $action_concat_export;?>" method="post" enctype="multipart/form-data" id="form-concat-export">
                <table class="table table-bordered table-hover">
                    <tr>
                        <td>
                            <select name="select_concat_category">
                                <option value="0" selected="selected"><?php echo $text_select_cate;?></option>
                                <?php foreach ($category_list as $cate_list) {?>
                                    <option value="<?php echo $cate_list['category_id'];?>"><?php echo $cate_list['name'];?></option>
                                <?php }?>
                            </select>
                        </td>
                        <td>
                            <?php echo $text_or;?>
                        </td>
                        <td>
                            <select name="select_concat_manufacture">
                                <option value="0" selected="selected"><?php echo $text_select_manu;?></option>
                                <?php foreach ($manufacture_list as $manu_list) {?>
                                    <option value="<?php echo $manu_list['manufacturer_id'];?>"><?php echo $manu_list['name'];?></option>
                                <?php }?>
                            </select>
                        </td>
                        <td>
                            <input type="submit" value="<?php echo $button_concat_export;?>">                         
                            <!--<div class="buttons"><a onclick="$('#form-export').submit();" href="<?php echo $export_link;?>" class="button"><?php echo $button_export;?></a></div>-->
                        </td>
                    </tr>          
                </table>
            </form>
        </div>
    </div>
</div>
<?php echo $footer;?>