<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
      <a href="javascript:void(0)" id="export_missing_images" data-toggle="tooltip" title="Export Missing Image Product List" class="btn btn-success"><i class="fa fa-image"></i></a>
      <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_copy; ?>" class="btn btn-default" onclick="$('#form-product').attr('action', '<?php echo $copy; ?>').submit()"><i class="fa fa-copy"></i></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-product').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid" id="main-content-area">
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
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-model"><?php echo $entry_model; ?></label>
                <input type="text" name="filter_model" value="<?php echo $filter_model; ?>" placeholder="<?php echo $entry_model; ?>" id="input-model" class="form-control" />
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-price"><?php echo $entry_price; ?></label>
                <input type="text" name="filter_price" value="<?php echo $filter_price; ?>" placeholder="<?php echo $entry_price; ?>" id="input-price" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-quantity"><?php echo $entry_quantity; ?></label>
                <input type="text" name="filter_quantity" value="<?php echo $filter_quantity; ?>" placeholder="<?php echo $entry_quantity; ?>" id="input-quantity" class="form-control" />
              </div>
            </div>

            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-price"><?php echo "Price Operator"; ?></label>
                <select name="filter_price_type" value="<?php echo $filter_price; ?>" placeholder="<?php echo "=(Equal)"; ?>" id="input-price" class="form-control" />
                  <option <?php if ($filter_price_type == -2) echo 'selected'; ?> value="-2"></option>
                  <option <?php if ($filter_price_type == 0) echo 'selected'; ?> value="0">= (Equal)</option>
                  <option <?php if ($filter_price_type == 1) echo 'selected'; ?> value="1">> (Greater than)</option>
                  <option <?php if ($filter_price_type == 2) echo 'selected'; ?> value="2">< (Less than)</option>
                </select>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-quantity"><?php echo "Quantity Operator"; ?></label>
                <select name="filter_quantity_type" value="<?php echo $filter_quantity_type; ?>" class="form-control" />
                  <option <?php if ($filter_quantity_type == -2) echo 'selected'; ?> value="-2"></option>
                  <option <?php if ($filter_quantity_type == 0) echo 'selected'; ?> value="0">= (Equal)</option>
                  <option <?php if ($filter_quantity_type == 1) echo 'selected'; ?> value="1">> (Greater than)</option>
                  <option <?php if ($filter_quantity_type == 2) echo 'selected'; ?> value="2">< (Less than)</option>
                </select>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-product-type">Product Type</label>
                <select name="filter_product_type"  id="input-product-type" class="form-control">
                  <option <?php if ($filter_product_type == 1) echo 'selected'; ?> value="1">Regular Product</option>
                  <option <?php if ($filter_product_type == 2) echo 'selected'; ?> value="2">Non Catalog Product</option>
                  <option <?php if ($filter_product_type == 3) echo 'selected'; ?> value="3">Incoming Product</option>
                </select>
              </div>
            </div>

            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                <select name="filter_status" id="input-status" class="form-control">
                  <option value="*"></option>
                  <?php if ($filter_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <?php } ?>
                  <?php if (!$filter_status && !is_null($filter_status)) { ?>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
              </div>

              <div class="form-group">
                <label class="control-label" for="filter_location">Location</label>
                <input type="text" name="filter_location" value="<?php echo $filter_location; ?>" placeholder="Location" id="filter-location" class="form-control" />
              </div>

              <div class="form-group">
                <label class="control-label" for="input-record-limit">Show: </label>
                <select name="filter_record_limit" id="input-record-limit"  class="form-control"> 
                  <option value="10" <?php echo ($filter_record_limit == "10") ? "selected" : ""; ?>>10</option>
                  <option value="20" <?php echo ($filter_record_limit == "20") ? "selected" : ""; ?>>20</option>
                  <option value="50" <?php echo ($filter_record_limit == "50") ? "selected" : ""; ?>>50</option>
                  <option value="100" <?php echo ($filter_record_limit == "100") ? "selected" : ""; ?>>100</option>
                </select>
              </div>

              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
              <button type="button" id="button_incoming_orders" class="btn btn-primary" onclick="$('#form-product').attr('action', '<?php echo $action_create; ?>').submit()"><i class="fa fa-save"></i> Add to Incoming Order</button>
              
                  <button type="button" id="button-export" class="btn btn-primary" style="margin-top: 3px;"><i class="fa fa-save"></i> &nbsp; Add All to CSV Export</button>
              
            </div>
          </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-product">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-center"><?php echo $column_image; ?></td>
                  <td class="text-left"><?php if ($sort == 'pd.name') { ?>
                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'p.model') { ?>
                    <a href="<?php echo $sort_model; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_model; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_model; ?>"><?php echo $column_model; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php if ($sort == 'p.price') { ?>
                    <a href="<?php echo $sort_price; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_price; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_price; ?>"><?php echo $column_price; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php if ($sort == 'p.quantity') { ?>
                    <a href="<?php echo $sort_quantity; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_quantity; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_quantity; ?>"><?php echo $column_quantity; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'p.status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($products) { ?>
                <?php foreach ($products as $product) { ?>
                <tr pid="<?php echo $product['product_id']?>">
                  <td class="text-center"><?php if (in_array($product['product_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-center"><?php if ($product['image']) { ?>
                    <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="img-thumbnail" />
                    <?php } else { ?>
                    <span class="img-thumbnail list"><i class="fa fa-camera fa-2x"></i></span>
                    <?php } ?></td>
                  <td class="text-left"><?php echo $product['name']; ?></td>
                  <td class="text-left"><?php echo $product['model']; ?></td>
                  <td class="text-right"><?php if ($product['special']) { ?>
                    <span style="text-decoration: line-through;"><?php echo $product['price']; ?></span><br/>
                    <div class="text-danger"><?php echo $product['special']; ?></div>
                    <?php } else { ?>
                    <?php echo $product['price']; ?>
                    <?php } ?></td>
                  <td id="editable" ondblclick="newInput(this)" class="text-right"><?php if ($product['quantity'] <= 0) { ?>
                    <span class="label label-warning"><?php echo $product['quantity']; ?></span>
                    <?php } elseif ($product['quantity'] <= 5) { ?>
                    <span class="label label-danger"><?php echo $product['quantity']; ?></span>
                    <?php } else { ?>
                    
                    <span class="label label-success"><?php echo $product['quantity']; ?></span>
                  
                    <?php } ?></td>
                  <td ondblclick="newStatus(this)" class="text-left"><?php echo $product['status']; ?></td>
                  <td class="text-right"><a href="<?php echo $product['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="13"><?php echo $text_no_results; ?></td>
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

var ch;
function closeInput(elm) {
    var value = $(elm).find('input').val();
    $(elm).empty().text(value);
    var pID = $(elm).closest('tr').attr("pid");
    //alert(pID);
    if (value != ch){
      //only send update request when actual content has been changed
    $.ajax({
      url: 'index.php?route=catalog/product/updateQuantity&token=<?php echo $token; ?>&product_id=' + pID + '&quantity='+ value, 
      dataType:'json',
      success: function(result){
        alert(JSON.Stringify(result));
    },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
  });
}

    $(elm).bind("dblclick", function () {
        newInput(elm);
    });
}  
        
function newInput(elm) {
    $(elm).unbind('dblclick');

    var value = $(elm).text();
    value = value.trim();
    ch = value;
    //alert(pID);
    $(elm).empty();
    
    $("<input>")
        .attr('type', 'text')
        .val(value)
        .blur(function() {
            closeInput(elm);
        })
        .appendTo($(elm))
        .focus();
}

var st;

function closeStatus(elm)
{
  var value = $(elm).find('select').val();
    $(elm).empty().text(value);

    var pID = $(elm).closest('tr').attr("pid");
    //alert(pID);
    if (value != st){
      //only send update request when actual content has been changed
    $.ajax({
      url: 'index.php?route=catalog/product/updateStatus&token=<?php echo $token; ?>&product_id=' + pID + '&status='+ value, 
      dataType:'json',
      success: function(result){
        alert(JSON.Stringify(result));
    },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
  });
}

    $(elm).bind("dblclick", function () {
        newStatus(elm);
    });
}

function newStatus(elm) {
    $(elm).unbind('dblclick');

    var value = $(elm).text();
    value = value.trim();
    st = value;
    //alert(st);
    //alert(pID);
    $(elm).empty();
    
    $("<select>")
        .append(new Option("Enable", "Enabled"),new Option("Disable","Disabled"))
        .val(value)
        .blur(function() {
            closeStatus(elm);
        })
        .appendTo($(elm))
        .focus();
}

$(document).keypress(function(event){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '13'){
        event.preventDefault();
        return false;
    }
});


$('#export_missing_images').on('click', function() {

	$.ajax({
			url: 'index.php?route=catalog/product/exportmissingimage&token=<?php echo $token; ?>',
			dataType: 'json',
			success: function(json) {	
        if(json['error'])
        {
			    html = '<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>';
        }	
        
        if(json['success'])
        {
          html = '<div class="alert alert-success"><i class="fa fa-check-circle"></i> CSV is ready. Please download it from <a href="<?php echo HTTPS_CATALOG; ?>' + 'system/storage/download/' + json['success'] + '"><b>Here</b></a><button type="button" class="close" data-dismiss="alert">&times;</button></div>';
        }

        $( "#main-content-area" ).prepend( html );
			}
		});
});
$('#button-filter').on('click', function() {
	var url = 'index.php?route=catalog/product&token=<?php echo $token; ?>';

	var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

	var filter_model = $('input[name=\'filter_model\']').val();

	if (filter_model) {
		url += '&filter_model=' + encodeURIComponent(filter_model);
	}

	var filter_price = $('input[name=\'filter_price\']').val();

	if (filter_price) {
		url += '&filter_price=' + encodeURIComponent(filter_price);
	}

  var filter_price_type = $('select[name=\'filter_price_type\']').val();

  if (filter_price_type) {
    url += '&filter_price_type=' + encodeURIComponent(filter_price_type);
  }

	var filter_quantity = $('input[name=\'filter_quantity\']').val();

	if (filter_quantity) {
		url += '&filter_quantity=' + encodeURIComponent(filter_quantity);
	}

  var filter_quantity_type = $('select[name=\'filter_quantity_type\']').val();

  if (filter_quantity_type) {
    url += '&filter_quantity_type=' + encodeURIComponent(filter_quantity_type);
  }

  var filter_product_type = $('select[name=\'filter_product_type\']').val();

  if (filter_product_type) {
    url += '&filter_product_type=' + encodeURIComponent(filter_product_type);
  }

  var filter_location = $('input[name=\'filter_location\']').val();

  if (filter_location) {
    url += '&filter_location=' + encodeURIComponent((filter_location).trim());
  }

	var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

  var filter_record_limit = $('select[name=\'filter_record_limit\']').val();
  if (filter_record_limit) {
    url += '&filter_record_limit=' + encodeURIComponent(filter_record_limit);
  }

  var catArray = [];
  var filter_c_id = $("#filter_cat_id input:checked").each(function() {
    catArray.push($(this).val());
  });
  var cselected;
  cselected = catArray.join(',') ;
  //alert(cselected);
  if (cselected.length > 1){
    url += '&filter_category_id=' + encodeURIComponent(cselected);
  }
  
  var manArray = [];
  var filter_m_id = $("#filter_man_id input:checked").each(function() {
    manArray.push($(this).val());
  });
  var mselected;
  mselected = manArray.join(',') ;
  if (mselected.length > 1){
    url += '&filter_manufacturer_id=' + encodeURIComponent(mselected);
  }
  //alert(url);


	location = url;
});

$('#button-export').on('click', function() {
	var url = 'index.php?route=catalog/product/export&token=<?php echo $token; ?>';

	var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

	var filter_model = $('input[name=\'filter_model\']').val();

	if (filter_model) {
		url += '&filter_model=' + encodeURIComponent(filter_model);
	}

	var filter_price = $('input[name=\'filter_price\']').val();

	if (filter_price) {
		url += '&filter_price=' + encodeURIComponent(filter_price);
	}

  var filter_price_type = $('select[name=\'filter_price_type\']').val();

  if (filter_price_type) {
    url += '&filter_price_type=' + encodeURIComponent(filter_price_type);
  }

	var filter_quantity = $('input[name=\'filter_quantity\']').val();

	if (filter_quantity) {
		url += '&filter_quantity=' + encodeURIComponent(filter_quantity);
	}

  var filter_quantity_type = $('select[name=\'filter_quantity_type\']').val();

  if (filter_quantity_type) {
    url += '&filter_quantity_type=' + encodeURIComponent(filter_quantity_type);
  }

  var filter_product_type = $('select[name=\'filter_product_type\']').val();

  if (filter_product_type) {
    url += '&filter_product_type=' + encodeURIComponent(filter_product_type);
  }

  var filter_location = $('input[name=\'filter_location\']').val();

  if (filter_location) {
    url += '&filter_location=' + encodeURIComponent((filter_location).trim());
  }

	var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

  var catArray = [];
  var filter_c_id = $("#filter_cat_id input:checked").each(function() {
    catArray.push($(this).val());
  });
  var cselected;
  cselected = catArray.join(',') ;
  //alert(cselected);
  if (cselected.length > 1){
    url += '&filter_category_id=' + encodeURIComponent(cselected);
  }
  
  var manArray = [];
  var filter_m_id = $("#filter_man_id input:checked").each(function() {
    manArray.push($(this).val());
  });
  var mselected;
  mselected = manArray.join(',') ;
  if (mselected.length > 1){
    url += '&filter_manufacturer_id=' + encodeURIComponent(mselected);
  }
  //alert(url);


	location = url;
});

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