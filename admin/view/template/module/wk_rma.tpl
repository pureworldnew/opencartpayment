<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right" style="position:relative;">
        <a onclick="confirm('<?php echo $text_refresh_module; ?>') ? document.location.href='<?php echo $refresh; ?>' : false;" data-toggle="tooltip" class="btn btn-warning" title="<?php echo $button_refresh; ?>" >
          <i class="fa fa-refresh"></i>
        </a>
        <a onclick="confirm('<?php echo $text_drop_module; ?>') ? document.location.href='<?php echo $uninstall; ?>' : false;" data-toggle="tooltip" class="btn btn-danger" title="<?php echo $button_drop; ?>" >
          <i class="fa fa-trash"></i>
        </a>
        <a href="http://webkul.com/blog/opencart-product-return-rma-2" target="_blank" title="Blog" data-toggle="tooltip" class="btn btn-lg mp-button">Blog</a>
        <a href="https://webkul.uvdesk.com/" target="_blank" title="Generate Ticket" data-toggle="tooltip" class="btn btn-lg mp-button mp-demo">Generate Ticket</a>
        <button type="submit" form="form-account" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <button class="btn btn-default dropdown-toggle" type="button" id="rma-option" data-toggle="dropdown" aria-expanded="true">
          <span><i class="fa fa-cogs"></i></span>
        </button>
        <ul class="dropdown-menu" role="menu" aria-labelledby="rma-option">
          <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo $manage_rma; ?>"><?php echo $text_rma_manage; ?></a></li>
          <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo $status_rma; ?>"><?php echo $text_rma_add_status; ?></a></li>
          <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo $reason_rma; ?>"><?php echo $text_rma_add_reasons; ?></a></li>
        </ul>

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
    <?php foreach ($error as $key => $value) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $value; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-account" class="form-horizontal">
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-status"><?php echo $text_rma_enable; ?></label>
                    <div class="col-sm-10">
                      <select name="wk_rma_status" id="input-status" class="form-control">
                        <?php if ($wk_rma_status) { ?>
                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                        <option value="0"><?php echo $text_disabled; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_enabled; ?></option>
                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-time"><span data-toggle="tooltip" title="<?php echo $text_rma_time_info; ?>"><?php echo $text_rma_time; ?></span></label>
                    <div class="col-sm-10">
                      <input type="number" min="0" step="1" name="wk_rma_system_time" id="input-time" value="<?php echo $wk_rma_system_time; ?>" class="form-control"/>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-time-admin"><span data-toggle="tooltip" title="<?php echo $text_rma_time_admin_info; ?>"><?php echo $text_rma_time_admin; ?></span></label>
                    <div class="col-sm-10">
                      <input type="number" min="0" step="1" name="wk_rma_system_time_admin" id="input-time-admin" value="<?php echo $wk_rma_system_time_admin; ?>" class="form-control"/>
                    </div>
                  </div>

                  <div class="form-group required <?php if (isset($error['error_system_orders']) && $error['error_system_orders']) { echo "has-error"; } ?>">
                    <label class="col-sm-2 control-label" for="input-order-status"><span data-toggle="tooltip" title="<?php echo $text_order_status_info; ?>"><?php echo $text_order_status; ?></span></label>
                    <div class="col-sm-10">
                      <div class="well well-sm" style="height: 150px; overflow: auto;">
                        <?php if($order_status){  ?>
                          <?php foreach($order_status as $orders){ ?>
                            <div class="checkbox">
                              <label>
                                <?php if (is_array($wk_rma_system_orders) AND in_array($orders['order_status_id'],$wk_rma_system_orders)) { ?>
                                <input type="checkbox" name="wk_rma_system_orders[]" value="<?php echo $orders['order_status_id']; ?>" checked="checked" />
                                <?php echo $orders['name']; ?>
                                <?php } else { ?>
                                <input type="checkbox" name="wk_rma_system_orders[]" value="<?php echo $orders['order_status_id']; ?>" />
                                <?php echo $orders['name']; ?>
                                <?php } ?>
                              </label>
                            </div>
                          <?php } ?>
                        <?php } ?>
                      </div>
                      <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-ex-type"><?php echo $text_image_extenstion; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="wk_rma_system_image" id="input-ex-type" value="<?php echo $wk_rma_system_image; ?>" placeholder="<?php echo $text_extenstion_holder; ?>" class="form-control"/>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-ex-type"><?php echo $text_file_extenstion; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="wk_rma_system_file" id="input-ex-type" value="<?php echo $wk_rma_system_file; ?>" placeholder="<?php echo $text_extenstion_holder; ?>" class="form-control"/>
                    </div>
                  </div>

                  <div class="form-group required <?php if (isset($error['error_system_size']) && $error['error_system_size']) { echo "has-error"; } ?>">
                    <label class="col-sm-2 control-label" for="input-ex-size"><span data-toggle="tooltip" title="<?php echo $text_size_info; ?>"><?php echo $text_extenstion_size; ?></span></label>
                    <div class="col-sm-10">
                      <input type="text" name="wk_rma_system_size" id="input-ex-size" value="<?php echo $wk_rma_system_size; ?>" placeholder="200" class="form-control"/>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-information"><span data-toggle="tooltip" title="<?php echo $text_rma_info_policy_info; ?>"><?php echo $text_rma_info_policy; ?></span></label>
                    <div class="col-sm-10">
                      <select name="wk_rma_system_information" class="form-control" id="input-information">
                          <option value=""></option>
                          <?php if($information){  ?>
                            <?php foreach($information as $info){ ?>
                              <option value="<?php echo $info['information_id']; ?>" <?php if($wk_rma_system_information == $info['information_id']) echo 'selected'; ?> > <?php echo $info['title']; ?> </option>
                            <?php } ?>
                          <?php } ?>
                      </select>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-theme"><?php echo $entry_theme; ?></label>
                    <div class="col-sm-10">
                      <select name="wk_rma_voucher_theme" id="input-theme" class="form-control">
                        <?php foreach ($voucher_themes as $voucher_theme) { ?>
                        <?php if ($voucher_theme['voucher_theme_id'] == $wk_rma_voucher_theme) { ?>
                        <option value="<?php echo $voucher_theme['voucher_theme_id']; ?>" selected="selected"><?php echo $voucher_theme['name']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $voucher_theme['voucher_theme_id']; ?>"><?php echo $voucher_theme['name']; ?></option>
                        <?php } ?>
                        <?php } ?>
                      </select>
                    </div>
                  </div>

                  <div class="form-group required <?php if (isset($error['error_address']) && $error['error_address']) { echo "has-error"; } ?>">
                    <label class="col-sm-2 control-label" for="input-policy"><span data-toggle="tooltip" title="<?php echo $text_rma_return_add_info; ?>"><?php echo $text_rma_return_add; ?></span></label>
                    <div class="col-sm-10">
                      <textarea name="wk_rma_address" class="form-control" id="input-policy" rows="5"><?php echo $wk_rma_address;?></textarea>
                    </div>
                  </div>
          </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
<style type="text/css">
    .mp-button{
    background-color: #0667B4;
    color: white;
    border-radius: 2px;
    font-size: 12px;
  }

  .mp-button:focus{
    outline: none !important;
  }

  .mp-demo{
    background-color: #2196F3;
  }
</style>
