<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="button" data-toggle="tooltip" title="Update" class="btn btn-primary" data-original-title="Save" 
        onclick="$('#form-product').submit();"><i class="fa fa-save"></i></button>
        
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
            <div class="col-sm-12">
              <div class="form-group">
                <label class="control-label" for="input-model"><?php echo $entry_model; ?></label>
                <input type="text" name="filter_model" value="<?php echo $filter_model; ?>" placeholder="<?php echo $entry_model; ?>" id="input-model" class="form-control" />
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <form action="<?php echo $update; ?>" method="post" id="form-product">
          <br />
          <h4><b><u><?php echo $entry_groupby_option; ?></u></b></h4>
          <br />
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left"><?php echo $column_groupby_type; ?></td>
                  <td class="text-left"><?php echo $column_option; ?></td>
                  <td class="text-left"><?php echo $column_sort_order; ?></td>
                  
                </tr>
              </thead>
              <tbody>
                <?php if ( !empty( $main_options ) ) { ?>
                <?php foreach ($main_options as $main_option) { ?>
                <tr>
                  <td class="text-left"><?php echo $main_option['type']; ?></td>
                  <td class="text-left"><?php echo $main_option['option_name']; ?></td>
                  <td class="text-left">
                  <input type="text" name="grouped_sort_order[<?php echo $main_option['product_grouped_id']; ?>]" 
                  value="<?php echo $main_option['sort_order']; ?>" class="form-control" />
                  </td>
                  
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="4"><?php echo $entry_nogroupby_option; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
            <br />
            <h4><b><u><?php echo $entry_other_option; ?></u></b></h4>
            <br />
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  
                  <td class="text-left"><?php echo $column_type; ?></td>
                  <td class="text-left"><?php echo $column_option; ?></td>
                  <td class="text-left"><?php echo $column_sort_order; ?></td>
                  
                </tr>
              </thead>
              <tbody>
                <?php if ( !empty( $other_options ) ) { ?>
                <?php foreach ($other_options as $other_option) { ?>
                <tr>
                  <td class="text-left"><?php echo $other_option['name']; ?></td>
                  <td class="text-left"><?php echo $other_option['option_value_name']; ?></td>
                  <td class="text-left"><input type="text" name="other_option[<?php echo $other_option['product_option_value_id']; ?>]" 
                  value="<?php echo $other_option['sort_order']; ?>" class="form-control" /></td>
                  
                  
                  
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="3"><?php echo $entry_noother_option; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
            
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	var url = 'index.php?route=catalog/product_grouped_sort&token=<?php echo $token; ?>';

	var filter_model = $('input[name=\'filter_model\']').val();

	if (filter_model) {
		url += '&filter_model=' + encodeURIComponent(filter_model);
	}

	location = url;
});
//--></script>
  </div>
<?php echo $footer; ?>