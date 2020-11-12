<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-adminmonitor" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="save-changes btn btn-primary"><i class="fa fa-save"></i></button>

        <a href="<?php echo $rehook; ?>" data-toggle="tooltip" title="<?php echo $button_rehook; ?>" class="btn btn-warning"><i class="fa fa-refresh"></i></a>
        
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title_dashboard; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($alert_success) { ?>
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $alert_success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($alert_error) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $alert_error; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if (empty($setting['adminmonitor']['LicensedOn'])) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $license_missing; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-eye"></i> <?php echo $heading_title_dashboard; ?></h3>
      </div>
      <div class="panel-body">
        <ul class="nav nav-tabs">
          <?php foreach ($tabs as $i => $tab) { ?>
          <li <?php if ($i == 0) echo 'class="active"'; ?>><a class="isearch_tab" href="#<?php echo $tab['id']; ?>" data-toggle="tab"><i class="fa fa-<?php echo $tab['icon'] ?>"></i> <?php echo $tab['name']; ?></a></li>
          <?php } ?>
        </ul>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-adminmonitor" class="form-horizontal">
            <div class="tab-content">
            <?php foreach ($tabs as $i => $tab) { ?>
              <div class="tab-pane <?php if ($i == 0) echo 'active'; ?>" id="<?php echo $tab['id']; ?>">
                <?php require_once $tab['file']; ?>
              </div>
            <?php } ?>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>