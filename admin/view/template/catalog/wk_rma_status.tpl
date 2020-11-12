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
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              </div>
            </div>
            <div class="col-sm-6">
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
                <div class="form-group">
                </div>
              <div class="pull-right">
                <button type="button" onclick="clrfilter();" class="btn btn-primary"><i class="fa fa-search"></i> <?php echo $button_clrfilter; ?></button>
                <button type="button" onclick="filter();" class="btn btn-primary"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
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
                     <?php if ($sort == 'wrs.name') { ?>
                        <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $entry_name; ?></a>
                      <?php } else { ?>
                        <a href="<?php echo $sort_name; ?>" > <?php echo $entry_name; ?> </a>
                      <?php } ?>
                  </td>
                  <td class="text-left">
                    <?php if ($sort == 'wrs.id') { ?>
                        <a href="<?php echo $sort_assign; ?>" class="<?php echo strtolower($order); ?>"><?php echo $entry_assign_to; ?></a>
                      <?php } else { ?>
                        <a href="<?php echo $sort_assign; ?>" > <?php echo $entry_assign_to; ?> </a>
                      <?php } ?>
                  </td>
                  <td class="text-left"><?php echo $entry_color; ?></td>
                  <td class="text-right" style="max-width:150px"></td>
                </tr>
              </thead>
              <tbody>
                <?php if($result){ ?>
                  <?php foreach($result as $value){ ?>
                  <tr>
                    <td><input type="checkbox" name="selected[]" value="<?php echo $value['id']; ?>" /></td>
                    <td class="text-left"><?php echo $value['name']; ?></td>
                    <td class="text-left"><?php echo $value['status'] ? $text_enable : $text_disable ; ?></td>
                    <td class="text-left" style="color: <?php echo $value['color']; ?>;"><?php echo $value['color'] ? $value['color'] : $text_color; ?></td>
                    <td class="text-right">
                    <div class="btn-group">
                      <?php if ($defaultRmaStatus && isset($value['id']) && ($defaultRmaStatus == $value['id'])) {?>
                          <button class="btn btn-info button-status"><?php echo $text_admin; ?></button>
                      <?php } else if ($solveRmaStatus && isset($value['id']) && ($solveRmaStatus == $value['id'])) {?>
                          <button class="btn btn-info button-status"><?php echo $text_solve; ?></button>
                      <?php } else if ($cancelRmaStatus && isset($value['id']) && ($cancelRmaStatus == $value['id'])) {?>
                          <button class="btn btn-info button-status"><?php echo $text_cancel; ?></button>
                      <?php } ?>
                      <a href="<?php echo $value['action']['href']; ?>" class="btn btn-primary" data-toggle="tooltip" title="<?php echo $value['action']['text']; ?>"><i class="fa fa-pencil"></i></a>
                      <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle"><span class="caret"></span></button>
                      <ul class="dropdown-menu dropdown-menu-right" style="min-width:230px;padding:10px;text-align:center;">
                        <span class="btn-group">
                        <?php if ($defaultRmaStatus && isset($value['id']) && ($defaultRmaStatus == $value['id'])) {?>
                          <button class="btn btn-danger approve_admin admin<?php echo $value['id']; ?>" data-id="<?php echo $value['id']; ?>" data-value= "disapprove" data-toggle="tooltip" title="<?php echo $text_disapprove . ' ' . $value['name'] . ' ' . $tip_approve_automatic_admin ; ?>" >
                            <?php echo $text_admin; ?>
                          </button>
                        <?php } else { ?>
                          <button class="btn btn-success approve_admin admin<?php echo $value['id']; ?>" data-id="<?php echo $value['id']; ?>" data-value= "approve" data-toggle="tooltip" title="<?php echo $text_approve . ' ' . $value['name'] . ' ' . $tip_approve_automatic_admin; ?>" >
                            <?php echo $text_admin; ?>
                          </button>
                        <?php } ?>
                        <?php if ($solveRmaStatus && isset($value['id']) && ($solveRmaStatus == $value['id'])) {?>
                          <button class="btn btn-danger approve_solve admin<?php echo $value['id']; ?>" data-id="<?php echo $value['id']; ?>" data-value= "disapprove" data-toggle="tooltip" title="<?php echo $text_disapprove . ' ' . $value['name'] . ' ' . $tip_approve_automatic_solve ; ?>" >
                            <?php echo $text_solve; ?>
                          </button>
                        <?php } else { ?>
                          <button class="btn btn-success approve_solve admin<?php echo $value['id']; ?>" data-id="<?php echo $value['id']; ?>" data-value= "approve" data-toggle="tooltip" title="<?php echo $text_approve . ' ' . $value['name'] . ' ' . $tip_approve_automatic_solve; ?>" >
                            <?php echo $text_solve; ?>
                          </button>
                        <?php } ?>
                        <?php if ($cancelRmaStatus && isset($value['id']) && ($cancelRmaStatus == $value['id'])) {?>
                          <button class="btn btn-danger approve_cancel admin<?php echo $value['id']; ?>" data-id="<?php echo $value['id']; ?>" data-value= "disapprove" data-toggle="tooltip" title="<?php echo $text_disapprove . ' ' . $value['name'] . ' ' . $tip_approve_automatic_cancel ; ?>" >
                            <?php echo $text_cancel; ?>
                          </button>
                        <?php } else { ?>
                          <button class="btn btn-success approve_cancel admin<?php echo $value['id']; ?>" data-id="<?php echo $value['id']; ?>" data-value= "approve" data-toggle="tooltip" title="<?php echo $text_approve . ' ' . $value['name'] . ' ' . $tip_approve_automatic_cancel; ?>" >
                            <?php echo $text_cancel; ?>
                          </button>
                        <?php } ?>
                        </span>
                      </ul>
                    </div>
                    </td>
                  </tr>
                  <?php } ?>
                <?php }else{ ?>
                  <tr>
                    <td colspan="6" class="text-center"><?php echo $text_no_records; ?></td>
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
  url = 'index.php?route=catalog/wk_rma_status&token=<?php echo $token; ?>';
  location = url;
}

function filter() {
  url = 'index.php?route=catalog/wk_rma_status&token=<?php echo $token; ?>';

  var filter_name = $('input[name=\'filter_name\']').val();

  if (filter_name) {
    url += '&filter_name=' + encodeURIComponent(filter_name);
  }

  var filter_assign = $('select[name=\'filter_assign\']').val();

  if (filter_assign != '*') {
    url += '&filter_assign=' + encodeURIComponent(filter_assign);
  }

  var filter_status = $('select[name=\'filter_status\']').val();

  if (filter_status != '*') {
    url += '&filter_status=' + encodeURIComponent(filter_status);
  }

  location = url;
}

</script>
<script type="text/javascript">
$(document).ready(function(){
  $('body').on('click','.button-status',function(event) {
    event.preventDefault();
  });
  $('body').on('click','.approve_admin',function(event) {
    event.preventDefault();
    var disapprove = '<?php echo $text_disapprove ; ?>';
    var approve = '<?php echo $text_approve ; ?>';
    var that = $(this);
    var title = that.attr('data-original-title') ? that.attr('data-original-title') : that.attr('title');
    var string = '';
    var restString = '';
    $.ajax({
      url: 'index.php?route=catalog/wk_rma_status/approveAdminStatus&token=<?php echo $token; ?>',
      dataType: 'json',
      data: {id: that.attr('data-id'),approve: that.attr('data-value')},
      type: 'post',
      beforeSend: function(){
        $('div.tooltip.fade.top.in').removeClass('in');
        $('.approve_admin').attr('disabled','disabled');
      },
      complete: function() {
        $('.approve_admin').removeAttr('disabled');
      },
      success: function(json) {
        location = 'index.php?route=catalog/wk_rma_status&token=<?php echo $token; ?>';
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
  });
  $('body').on('click','.approve_solve',function(event) {
    event.preventDefault();
    var disapprove = '<?php echo $text_disapprove ; ?>';
    var approve = '<?php echo $text_approve ; ?>';
    var that = $(this);
    var title = that.attr('data-original-title') ? that.attr('data-original-title') : that.attr('title');
    var string = '';
    var restString = '';
    $.ajax({
      url: 'index.php?route=catalog/wk_rma_status/approveSolveStatus&token=<?php echo $token; ?>',
      dataType: 'json',
      data: {id: that.attr('data-id'),approve: that.attr('data-value')},
      type: 'post',
      beforeSend: function(){
        $('div.tooltip.fade.top.in').removeClass('in');
        $('.approve_solve').attr('disabled','disabled');
      },
      complete: function() {
        $('.approve_solve').removeAttr('disabled');
      },
      success: function(json) {
        location = 'index.php?route=catalog/wk_rma_status&token=<?php echo $token; ?>';
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
  });
  $('body').on('click','.approve_cancel',function(event) {
    event.preventDefault();
    var disapprove = '<?php echo $text_disapprove ; ?>';
    var approve = '<?php echo $text_approve ; ?>';
    var that = $(this);
    var title = that.attr('data-original-title') ? that.attr('data-original-title') : that.attr('title');
    var string = '';
    var restString = '';
    $.ajax({
      url: 'index.php?route=catalog/wk_rma_status/approveCancelStatus&token=<?php echo $token; ?>',
      dataType: 'json',
      data: {id: that.attr('data-id'),approve: that.attr('data-value')},
      type: 'post',
      beforeSend: function(){
        $('div.tooltip.fade.top.in').removeClass('in');
        $('.approve_cancel').attr('disabled','disabled');
      },
      complete: function() {
        $('.approve_cancel').removeAttr('disabled');
      },
      success: function(json) {
        location = 'index.php?route=catalog/wk_rma_status&token=<?php echo $token; ?>';
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
  });
});
</script>
