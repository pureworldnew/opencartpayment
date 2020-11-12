<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
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
            <div class="col-sm-12">
              <div class="form-group">
                <label class="control-label" for="input-groupindicator"><?php echo $entry_groupindicator; ?></label>
                <input type="text" name="filter_groupindicator" value="<?php echo $filter_groupindicator; ?>" placeholder="<?php echo $entry_groupindicator; ?>" id="input-groupindicator" class="form-control" />
              </div>
              <button type="button" id="button-export" class="btn btn-primary pull-right"><i class="fa fa-file"></i> <?php echo $button_export; ?></button>
            </div>
          </div>
          
          <div class="row">
            <div class="col-sm-12">
              <form method="post" action="" enctype="multipart/form-data">
              <div class="form-group">
              	<label class="control-label">Browse file to Import sorting:</label>
                <input type="file" name="csv_file"class="form-control" />
              </div>
              <button type="submit" id="button-import" class="btn btn-primary pull-right"><i class="fa fa-file"></i> Import</button>
              </form>
            </div>
          </div>
        </div>
       </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-export').on('click', function() {
	var url = 'index.php?route=catalog/grouped_batch_sort&mode=export&token=<?php echo $token; ?>';

	var filter_groupindicator = $('input[name=\'filter_groupindicator\']').val();

	if (filter_groupindicator) {
		url += '&filter_groupindicator=' + encodeURIComponent(filter_groupindicator);
	}

	location = url;
});
//--></script>
  </div>
<?php echo $footer; ?>