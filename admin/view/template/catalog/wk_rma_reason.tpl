<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $insert; ?>" data-toggle="tooltip" title="<?php echo $button_insert; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $error_delete; ?>') ? $('#form-filter').submit() : false;"><i class="fa fa-trash-o"></i></button>
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
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-name"><?php echo $entry_reason; ?></label>
                <input type="text" name="filter_reason" value="<?php echo $filter_reason; ?>" placeholder="<?php echo $entry_reason; ?>" id="input-name" class="form-control" />
              </div>
            </div>

            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                <select name="filter_status" id="input-status" class="form-control">
                  <option value="*"></option>
                  <?php if ($filter_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enable; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enable; ?></option>
                  <?php } ?>
                  <?php if (($filter_status !== null) && !$filter_status) { ?>
                  <option value="0" selected="selected"><?php echo $text_disable; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_disable; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
              </div>
              <div class="form-group">
              </div>
              <div class="pull-right">
                <button type="button" onclick="filter();" class="btn btn-primary"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
                <button type="button" onclick="clrfilter();" class="btn btn-primary"><i class="fa fa-search"></i> <?php echo $button_clrfilter; ?></button>
              </div>
            </div>
          </div>
        </div>

        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-filter">
          <div class="table-responsive">
            <table class="table table-bordered table-hove">
              <thead>
                <tr>
                  <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
                  <td class="text-left">
                    <?php if ($sort == 'wrr.reason') { ?>
                      <a href="<?php echo $sort_reason; ?>" class="<?php echo strtolower($order); ?>"><?php echo $entry_reason; ?></a>
                    <?php } else { ?>
                      <a href="<?php echo $sort_reason; ?>" > <?php echo $entry_reason; ?> </a>
                    <?php } ?>
                  </td>
                  <td class="text-left">
                    <?php if ($sort == 'wrr.status') { ?>
                      <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $entry_status; ?></a>
                    <?php } else { ?>
                      <a href="<?php echo $sort_status; ?>" > <?php echo $entry_status; ?> </a>
                    <?php } ?>
                  </td>
                  <td class="text-right"></td>
                </tr>
              </thead>

              <tbody>
                <?php if($result){ ?>
                  <?php foreach($result as $value){ ?>
                  <tr>
                    <td><input type="checkbox" name="selected[]" value="<?php echo $value['id']; ?>" /></td>
                    <td class="text-left"><?php echo $value['reason']; ?></td>
                    <td class="text-left"><?php echo $value['status'] ? $text_enable : $text_disable ; ?></td>
                    <td class="text-right"><a href="<?php echo $value['action']['href']; ?>" class="btn btn-primary" data-toggle="tooltip" title="<?php echo $value['action']['text']; ?>"><i class="fa fa-pencil"></i></a></td>
                  </tr>
                  <?php } ?>
                <?php }else{ ?>
                  <tr>
                    <td colspan="4" class="text-center"><?php echo $text_no_records; ?></td>
                  </tr>
                <?php } ?>

              </tbody>
            </table>
          </div>
        </form>

        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
<script type="text/javascript">

function clrfilter() {
  url = 'index.php?route=catalog/wk_rma_reason&token=<?php echo $token; ?>';
  location = url;
}

function filter() {
  url = 'index.php?route=catalog/wk_rma_reason&token=<?php echo $token; ?>';

  var filter_reason = $('input[name=\'filter_reason\']').val();

  if (filter_reason) {
    url += '&filter_reason=' + encodeURIComponent(filter_reason);
  }

  var filter_status = $('select[name=\'filter_status\']').val();

  if (filter_status != '*') {
    url += '&filter_status=' + encodeURIComponent(filter_status);
  }

  location = url;
}
</script>
