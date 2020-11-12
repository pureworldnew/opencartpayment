<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-combine" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" id="form-combine" class="form-horizontal">
          <p class="lead col-sm-offset-2"><span style="color:red;">Note:</span> Here you can set Backorder statuses that are consider not completel</p>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_order_status; ?></label>
            <div class="col-sm-10">
              <select name="selected_backorder_statuses[]" id="input-status" class="form-control" multiple size="28">
                <?php foreach ( $order_statuses as $order_status ) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" <?php if(in_array($order_status['order_status_id'], $selected_backorder_statuses)) { echo 'selected'; } ?>><?php echo $order_status['name']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>