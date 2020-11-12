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
  <div class="container-fluid" id="container-fluid">
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
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>  
          </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-product">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center">
                    <select id="data-action" onchange="getAction();">
                      <option value="*">Action</option>
                      <option value="2">Select All</option>
                      <option value="1">Enable</option>
                      <option value="0">Disable</option>
                    </select>
                  <!-- <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td> -->
                  <td class="text-left"><?php echo $column_name; ?></td>
                  <td class="text-left"><?php echo $column_model; ?></td>
                  <td class="text-left"><?php echo $column_groupindicator; ?></td>
                  <td class="text-left"><?php echo $column_groupindicator_id; ?></td>
                  <td class="text-left"><?php echo $column_new; ?></td>
                  <td class="text-right"><button type="button" id="button-update-bulk-action" class="btn btn-sm btn-success" onclick="updateBulkAction();">Update</button></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($products) { ?>
                <?php foreach ($products as $product) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($product['product_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-left"><?php echo $product['name']; ?></td>
                  <td class="text-left"><?php echo $product['sku']; ?></td>
                  <td class="text-left"><?php echo $product['groupindicator']; ?></td>
                  <td class="text-left"><?php echo $product['groupindicator_id']; ?></td>
                  <td class="text-left">
                      <select class="form-control" id="new_grouping_system_<?php echo $product['groupindicator_id']; ?>">
                          <option value="1" <?php if($product['new_grouping_system'] == 1 ) { echo "selected"; } ?>><?php echo $text_enabled; ?></option>
                          <option value="0" <?php if($product['new_grouping_system'] == 0 ) { echo "selected"; } ?>><?php echo $text_disabled; ?></option>
                      </select>
                  </td>
                  <td class="text-right"><a href="javascript:void(0)" data-toggle="tooltip" title="<?php echo $button_update; ?>" class="btn btn-primary" onclick="updateGroupProduct(<?php echo $product['groupindicator_id']; ?>);"><i class="fa fa-refresh"></i></a></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
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
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	var url = 'index.php?route=catalog/new_grouping_system&token=<?php echo $token; ?>';

	var filter_groupindicator = $('input[name=\'filter_groupindicator\']').val();

	if (filter_groupindicator) {
		url += '&filter_groupindicator=' + encodeURIComponent(filter_groupindicator);
	}

	location = url;
});

function updateGroupProduct(groupindicator_id)
{
  $(".fa-refresh").addClass("fa-spin");
  var data = {};
      data['groupindicator_id'] = groupindicator_id;
      new_grouping_system_status = $('#new_grouping_system_'+ groupindicator_id +' :selected').val();
      data['new_grouping_system'] = new_grouping_system_status;

      $.ajax({
      url: 'index.php?route=catalog/new_grouping_system/updateGroupProduct&token=<?php echo $token; ?>',
      type: 'post',
		  data: data,
			dataType: 'json',
			success: function(json) {
        $(".fa-refresh").removeClass("fa-spin");
        $(".fa-refresh").addClass("fa-check");
        $(".fa-check").removeClass("fa-refresh");
			}
		});

  
}

function getAction()
{
    value = $('#data-action :selected').val();
    if ( value == 2 )
    {
      $('input[name*=\'selected\']').prop('checked', true);
    }

    if ( value == '*' )
    {
      $('input[name*=\'selected\']').prop('checked', false);
    }
}

function updateBulkAction()
{
  var selected = $('#data-action :selected').val();
  var products = [];
  $('input[name*=\'selected\']:checked').each(function() {
      products.push($(this).attr('value'));
  });

  var data = {};
      data['selected'] = selected;
      data['products'] = products;
      
  if( selected == 0 || selected == 1 )
  {
    $.ajax({
      url: 'index.php?route=catalog/new_grouping_system/updateBulkGroupProducts&token=<?php echo $token; ?>',
      type: 'post',
		  data: data,
			dataType: 'json',
      success: function(json) { 
        if( json.error )
        {
          $(".alert-success").hide();
          var html = '<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ';
          html += json.error;
          html += '<button type="button" class="close" data-dismiss="alert">&times;</button></div>';
          $('#container-fluid').prepend(html);
        }

        if( json.success )
        {
          $(".alert-danger").hide();
          var html = '<div class="alert alert-success"><i class="fa fa-check-circle"></i> ';
          html += json.success;
          html += '<button type="button" class="close" data-dismiss="alert">&times;</button></div>';
          $('#container-fluid').prepend(html);
          $('input[name*=\'selected\']:checked').each(function() {
            $('#new_grouping_system_'+ $(this).attr('value')).val(selected);
          });
        }
        
			}
		});

  } else {
    alert("Please select atleast one group product and select enable or disable from action dropdown.");
    return false;
  }
}
//--></script>
  <script type="text/javascript"><!--
$('input[name=\'filter_name\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_name\']').val(item['label']);
	}
});

$('input[name=\'filter_model\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_model=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['model'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_model\']').val(item['label']);
	}
});
//--></script></div>
<?php echo $footer; ?>