<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-user-group" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-user-group" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              <?php if ($error_name) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php  } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_access; ?></label>
            <div class="col-sm-10">
              <div class="well well-sm" style="height: 150px; overflow: auto;">
                <?php foreach ($permissions as $permission) { ?>
                <div class="checkbox">
                  <label>
                    <?php if (in_array($permission, $access)) { ?>
                    <input type="checkbox" name="permission[access][]" value="<?php echo $permission; ?>" checked="checked" />
                    <?php echo $permission; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="permission[access][]" value="<?php echo $permission; ?>" />
                    <?php echo $permission; ?>
                    <?php } ?>
                  </label>
                </div>
                <?php } ?>
              </div>
              <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a></div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_modify; ?></label>
            <div class="col-sm-10">
              <div class="well well-sm" style="height: 150px; overflow: auto;">
                <?php foreach ($permissions as $permission) { ?>
                <div class="checkbox">
                  <label>
                    <?php if (in_array($permission, $modify)) { ?>
                    <input type="checkbox" name="permission[modify][]" value="<?php echo $permission; ?>" checked="checked" />
                    <?php echo $permission; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="permission[modify][]" value="<?php echo $permission; ?>" />
                    <?php echo $permission; ?>
                    <?php } ?>
                  </label>
                </div>
                <?php } ?>
              </div>
              <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a></div>
          </div>
          <!-- POS Tabs Permission -->

          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_pos_access; ?></label>
            <div class="col-sm-10">
              <table class="table table-header-rotated">
                <thead>
                  <tr>
                    <!-- First column header is not rotated -->
                    <th></th>
                    <!-- Following headers are rotated -->
                    <th class="rotate"><div><span>Edit Product Information</span></div></th>
                    <th class="rotate"><div><span>Update Stock</span></div></th>
                    <th class="rotate"><div><span>Incoming Orders</span></div></th>
                    <th class="rotate"><div><span>Backorders</span></div></th>
                    <th class="rotate"><div><span>Returns</span></div></th>
                    <th class="rotate"><div><span>Sales Report</span></div></th>
                  </tr> 
                </thead>
                <tbody>
                  <tr>
                    <th class="row-header">View Access</th>
                    <td><input name="permission[product_info][]" type="radio" value="view_access" <?php echo (isset($product_info[0]) && $product_info[0] == "view_access") ? 'checked="checked"' : "" ?>></td>
                    <td><input name="permission[update_stock][]" type="radio" value="view_access" <?php echo (isset($update_stock[0]) && $update_stock[0] == "view_access") ? 'checked="checked"' : "" ?>></td>
                    <td><input name="permission[incoming_orders][]" type="radio" value="view_access" <?php echo (isset($incoming_orders[0]) && $incoming_orders[0] == "view_access") ? 'checked="checked"' : "" ?>></td>
                    <td><input name="permission[backorders][]" type="radio" value="view_access" <?php echo (isset($backorders[0]) && $backorders[0] == "view_access") ? 'checked="checked"' : "" ?>></td>
                    <td><input name="permission[returns][]" type="radio" value="view_access" <?php echo (isset($returns[0]) && $returns[0] == "view_access") ? 'checked="checked"' : "" ?>></td>
                    <td><input name="permission[sales_report][]" type="radio" value="view_access" <?php echo (isset($sales_report[0]) && $sales_report[0] == "view_access") ? 'checked="checked"' : "" ?>></td>
                  </tr>
                  <tr>
                    <th class="row-header">Edit Access</th>
                    <td><input name="permission[product_info][]" type="radio" value="edit_access" <?php echo (isset($product_info[0]) && $product_info[0] == "edit_access") ? 'checked="checked"' : "" ?>></td>
                    <td><input name="permission[update_stock][]" type="radio" value="edit_access" <?php echo (isset($update_stock[0]) && $update_stock[0] == "edit_access") ? 'checked="checked"' : "" ?>></td>
                    <td><input name="permission[incoming_orders][]" type="radio" value="edit_access" <?php echo (isset($incoming_orders[0]) && $incoming_orders[0] == "edit_access") ? 'checked="checked"' : "" ?>></td>
                    <td><input name="permission[backorders][]" type="radio" value="edit_access" <?php echo (isset($backorders[0]) && $backorders[0] == "edit_access") ? 'checked="checked"' : "" ?>></td>
                    <td><input name="permission[returns][]" type="radio" value="edit_access" <?php echo (isset($returns[0]) && $returns[0] == "edit_access") ? 'checked="checked"' : "" ?>></td>
                    <td><input name="permission[sales_report][]" type="radio" value="edit_access" <?php echo (isset($sales_report[0]) && $sales_report[0] == "edit_access") ? 'checked="checked"' : "" ?>></td>
                  </tr>
                  <tr>
                    <th class="row-header">No Access</th>
                    <td><input name="permission[product_info][]" type="radio" value="no_access" <?php echo (isset($product_info[0]) && $product_info[0] == "no_access") ? 'checked="checked"' : "" ?>></td>
                    <td><input name="permission[update_stock][]" type="radio" value="no_access" <?php echo (isset($update_stock[0]) && $update_stock[0] == "no_access") ? 'checked="checked"' : "" ?>></td>
                    <td><input name="permission[incoming_orders][]" type="radio" value="no_access" <?php echo (isset($incoming_orders[0]) && $incoming_orders[0] == "no_access") ? 'checked="checked"' : "" ?>></td>
                    <td><input name="permission[backorders][]" type="radio" value="no_access" <?php echo (isset($backorders[0]) && $backorders[0] == "no_access") ? 'checked="checked"' : "" ?>></td>
                    <td><input name="permission[returns][]" type="radio" value="no_access" <?php echo (isset($returns[0]) && $returns[0] == "no_access") ? 'checked="checked"' : "" ?>></td>
                    <td><input name="permission[sales_report][]" type="radio" value="no_access" <?php echo (isset($sales_report[0]) && $sales_report[0] == "no_access") ? 'checked="checked"' : "" ?>></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>


          <!-- /POS Tabs Permission -->
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?> 