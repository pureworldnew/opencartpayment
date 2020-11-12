<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-product" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-product" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-product-sn" data-toggle="tab"><?php echo $tab_product_sn; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
			  <div class="form-group">
				<label class="col-sm-2 control-label"><?php echo $entry_name; ?></label>
				<div class="col-sm-10">
				  <input class="col-sm-2 form-control" type="text" readonly value="<?php echo $name; ?>" />
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label"><?php echo $entry_model; ?></label>
				<div class="col-sm-10">
				  <input class="col-sm-2 form-control" type="text" readonly value="<?php echo $model; ?>" />
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
				<div class="col-sm-10">
				  <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" /></a>
				</div>
			  </div>            
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-abbreviation"><?php echo $entry_abbreviation; ?></label>
				<div class="col-sm-10">
				  <input type="text" name="abbreviation" value="<?php echo $abbreviation; ?>" class="form-control" />
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label"><?php echo $entry_decimal_quantity; ?></label>
				<div class="col-sm-10">
				  <label class="radio-inline">
					<?php if ($weight_price) { ?>
					<input type="radio" name="weight_price" value="1" checked="checked" />&nbsp;<?php echo $text_yes; ?>
					<?php } else { ?>
					<input type="radio" name="weight_price" value="1" />&nbsp;<?php echo $text_yes; ?>
					<?php } ?>
				  </label>
				  <label class="radio-inline">
					<?php if (!$weight_price) { ?>
					<input type="radio" name="weight_price" value="0" checked="checked" />&nbsp;<?php echo $text_no; ?>
					<?php } else { ?>
					<input type="radio" name="weight_price" value="0" />&nbsp;<?php echo $text_no; ?>
					<?php } ?>
				  </label>
				</div>
			  </div>
			  <div id="weight_name_div" class="form-group" <?php if (!$weight_price) { ?>style="display:none;"<?php }?>>
				<label class="col-sm-2 control-label" for="input-weight-name"><?php echo $entry_decimal_quantity_name; ?></label>
				<div class="col-sm-10">
				  <input type="text" name="weight_name" value="<?php echo $weight_name; ?>" class="form-control" />
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label"><?php echo $entry_commission; ?></label>
				<div class="col-sm-10">
				  <label class="radio-inline">
					<?php if ($commission) { ?>
					<input type="radio" name="commission" value="1" checked="checked" />&nbsp;<?php echo $text_yes; ?>
					<?php } else { ?>
					<input type="radio" name="commission" value="1" />&nbsp;<?php echo $text_yes; ?>
					<?php } ?>
				  </label>
				  <label class="radio-inline">
					<?php if (!$commission) { ?>
					<input type="radio" name="commission" value="0" checked="checked" />&nbsp;<?php echo $text_no; ?>
					<?php } else { ?>
					<input type="radio" name="commission" value="0" />&nbsp;<?php echo $text_no; ?>
					<?php } ?>
				  </label>
				</div>
			  </div>
			  <div id="commission_type_div" class="form-group" <?php if (!$commission) { ?>style="display:none;"<?php }?>>
				<label class="col-sm-2 control-label"><?php echo $entry_commission_type; ?></label>
				<div class="col-sm-10">
				  <label class="radio-inline" style="width: 150px;">
					<?php if ($commission_type == 1) { ?>
					<input type="radio" name="commission_type" value="1" checked="checked" />&nbsp;<?php echo $entry_commission_fixed; ?>
					<?php } else { ?>
					<input type="radio" name="commission_type" value="1" />&nbsp;<?php echo $entry_commission_fixed; ?>
					<?php } ?>
				  </label>
				  <label class="radio-inline">
					<?php if ($commission_type == 2) { ?>
					<input type="radio" name="commission_type" value="2" checked="checked" />&nbsp;<?php echo $entry_commission_percentage; ?>
					<?php } else { ?>
					<input type="radio" name="commission_type" value="2" />&nbsp;<?php echo $entry_commission_percentage; ?>
					<?php } ?>
				  </label>
				</div>
			  </div>
			  <div id="commission_value_div" class="form-group" <?php if (!$commission) { ?>style="display:none;"<?php }?>>
				<label class="col-sm-2 control-label"><?php echo $entry_commission_value; ?></label>
				<div class="col-sm-10" id="commission_fixed_div" <?php if ($commission_type != 1) {?>style="display:none;"<?php }?>>
				  <input type="text" name="commission_fixed" value="<?php echo $commission_value; ?>" class="form-control" />
				</div>
				<div class="col-sm-10" id="commission_percentage_div" <?php if ($commission_type != 2) {?>style="display:none;"<?php }?>>
                  <div class="row">
                    <div class="col-sm-3">
                      <input type="text" name="commission_percentage" value="<?php echo $commission_value; ?>" id="input-commission_percentage" class="form-control" />
                    </div>
                    <div class="col-sm-3" style="width: 100px; margin-top: 10px;">
                      <?php echo $text_commission_percentage_base; ?>
                    </div>
                    <div class="col-sm-3">
                      <input type="text" name="commission_base" value="<?php echo $commission_base; ?>" id="input-commission_base" class="form-control" />
                    </div>
                  </div>
				</div>
			  </div>
			</div>
            <div class="tab-pane" id="tab-product-sn">
			  <div class="table-responsive">
				<table class="table table-stripped table-hover table-bordered">
					<thead>
						<tr>
							<td colspan="4" class="text-right" style="background-color: #E7EFEF;">
								<a class="btn btn-danger" onclick="deleteSN(this)"><i class="fa fa-trash-o fa-lg"></i> <?php echo $button_delete; ?></a>
								<a class="btn btn-info" onclick="addSN()"><i class="fa fa-plus-circle fa-lg"></i> <?php echo $button_add_sn; ?></a>
							</td>
						</tr>
						<tr>
							<td width="1" class="text-center"><input type="checkbox" onclick="$('input[name*=sn_selected]').prop('checked', $(this).is(':checked'));" /></td>
							<td class="text-left"><?php echo $column_sn_product_sn; ?></td>
							<td class="text-left"><?php echo $column_sn_product_status; ?></td>
							<td class="text-right"><?php echo $column_action; ?></td>
						</tr>
					</thead>
					<tbody id="product_serial_no_list">
						<tr class="filter">
							<td></td>
							<td class="text-left"><input type="text" class="form-control" name="filter_sn_sn" value="" /></td>
							<td class="text-left">
								<select class="form-control" name="filter_sn_status">
									<option value="0"></option>
									<option value="1"><?php echo $text_sn_in_store; ?></option>
									<option value="2"><?php echo $text_sn_sold; ?></option>
								</select>
							</td>
							<td class="text-right">
								<a class="btn btn-info" id="button_sn_filter" onclick="filterSN();"><i class="fa fa-search fa-lg"></i> <?php echo $button_search; ?></a>
							</td>
						</tr>
						<?php if (!empty($product_sns)) { ?>
						<?php foreach ($product_sns as $product_sn) {?>
						<tr>
							<td class="text-center"><input type="checkbox" name="sn_selected[]" value="<?php echo $product_sn['product_sn_id']; ?>" /></td>
							<td class="text-left" id="product_sn<?php echo $product_sn['product_sn_id']; ?>"><?php echo $product_sn['name']; ?></td>
							<td class="text-left"><?php echo $product_sn['status']; ?></td>
							<td class="text-right">
								<?php if (empty($product_sn['order_id'])) { ?>
								<a class="btn btn-danger btn-sm" onclick="modifySN(this);"><i class="fa fa-pencil fa-lg"></i> <?php echo $button_modify; ?></a>
								<?php }?>
							</td>
						</tr>
						<?php }} else { ?>
						<tr><td colspan="4" class="text-center"><?php echo $text_no_results; ?></td></tr>
						<?php } ?>
					</tbody>
				</table>
			  </div>
			  <div class="row" id="sn_pagination"><?php echo $pagination; ?></div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div style="display: none;">
	<div id="add_sn_dialog" title="<?php echo $button_add_sn; ?>" class="ui-helper-hidden">
		<table class="table table-bordered table-hover">
			<tbody id="new_serial_no_list">
				<tr>
					<td class="text-left"><?php echo $entry_sn; ?> #1</td>
					<td class="text-left"><input type="text" name="product_sn[1]" value="" class="form-control" /></td>
				</tr>
				<tr id="tr_sn_save">
					<td class="text-right" colspan="2"><a onclick="saveProductSN(this);" class="btn btn-info"><i class="fa fa-save fa-lg"></i> <?php echo $button_save; ?></a></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div id="modify_sn_dialog" title="<?php echo $button_modify; ?>" class="ui-helper-hidden">
		<div style="width: 100%;">
			<input type="text" name="modify_product_sn" value="" class="form-control"/>
			<input type="hidden" name="modify_product_sn_id" value="" />
		<div>
		<div style="margin-top: 10px;">
			<a class="btn btn-info pull-right" onclick="saveModifedSN()"><i class="fa fa-save fa-lg"></i> <?php echo $button_save; ?></a>
		</div>
	</div>
</div>

<link rel="stylesheet" type="text/css" href="view/javascript/jquery/ui/jquery-ui.min.css" />
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui.min.js"></script>
<script type="text/javascript"><!--
// Filter
$('input[name=\'filter\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/filter/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['filter_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter\']').val('');
		
		$('#product-filter' + item['value']).remove();
		
		$('#product-filter').append('<div id="product-filter' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_filter[]" value="' + item['value'] + '" /></div>');	
	}	
});

$('input[name=weight_price]').click(function() {
	if ($(this).val() == '0') {
		$('#weight_name_div').hide();
	} else {
		$('#weight_name_div').show();
	}
});

$('input[name=commission]').click(function() {
	if ($(this).val() == '0') {
		$('#commission_type_div').hide();
		$('#commission_value_div').hide();
	} else {
		$('#commission_type_div').show();
		if ($('input[name=commission_type][value=1]').is(':checked')) {
			$('#commission_fixed_div').show();
			$('#commission_percentage_div').hide();
		} else {
			$('#commission_fixed_div').hide();
			$('#commission_percentage_div').show();
		}
		$('#commission_value_div').show();
	}
});

$('input[name=commission_type]').click(function() {
	if ($(this).val() == '1') {
		$('#commission_fixed_div').show();
		$('#commission_percentage_div').hide();
	} else {
		$('#commission_fixed_div').hide();
		$('#commission_percentage_div').show();
	}
});

$(document).on('keydown', 'input[name^=product_sn]', function() {
	var len = $('#new_serial_no_list tr').length;
	var index = $('#new_serial_no_list tr').index($(this).closest('tr'));
	if (index == len - 2) {
		// create another line
		$('#tr_sn_save').before('<tr><td class="text-left"><?php echo $entry_sn; ?> #' + (index+2) + '</td><td class="text-left"><input type="text" name="product_sn[' + (index+2) + ']" value="" class="form-control" /></td></tr>');
	}
});

$('#add_sn_dialog').dialog({
	autoOpen: false,
	modal: true,
	width: 420,
	height: 360,
	buttons: {}
});

function addSN() {
	$('#add_sn_dialog').dialog('open');
};

function saveProductSN(anchor) {
	var product_id = <?php echo $product_id; ?>;
	var data = {};
	$('input[name^=\'product_sn\']').each(function() {
		if ($(this).val().trim() != '') {
			data[$(this).attr('name')] = $(this).val();
		}
	});
	data['product_id'] = product_id;

	$.ajax({
		url: 'index.php?route=pos/extended_product/saveProductSN&token=<?php echo $token; ?>',
		type: 'post',
		dataType: 'json',
		data: data,
		beforeSend: function() {
			$(anchor).hide();
			$(anchor).before('<img src="view/image/pos/loading.gif" class="loading" style="padding-left: 5px;" />');	
		},
		complete: function() {
			$('.loading').remove();
			$(anchor).show();
		},
		success: function(json) {
			if (json['error']) {
				alert(json['error']);
			}
			alert(json['success']);

			resetSNQuantity();
			reloadSN(json, true);
			
			$('#add_sn_dialog').dialog('close');
		}
	});
};

function reloadSN(json, resetValues) {
	html = '';
	$('#product_serial_no_list tr:gt(0)').remove();
	if (json['product_sns'] && json['product_sns'].length > 0) {
		for (var i in json['product_sns']) {
			html += '<tr>';
			html += '	<td class="text-center"><input type="checkbox" name="sn_selected[]" value="' + json['product_sns'][i]['product_sn_id'] + '" /></td>';
			html += '	<td class="text-left" id="product_sn' + json['product_sns'][i]['product_sn_id'] + '">' + json['product_sns'][i]['name'] + '</td>';
			html += '	<td class="text-left">' + json['product_sns'][i]['status'] + '</td>';
			html += '	<td class="text-right">';
			if (!json['product_sns'][i]['order_id'] || parseInt(json['product_sns'][i]['order_id']) == 0) {
			html += '		<a class="btn btn-danger btn-sm" onclick="modifySN(this);"><i class="fa fa-pencil fa-lg"></i> <?php echo $button_modify; ?></a>';
			}
			html += '	</td>';
			html += '</tr>';
		}
	} else {
		html = '<tr><td colspan="4" class="text-center"><?php echo $text_no_results; ?></td></tr>';
	}
	$('#product_serial_no_list').append(html);
	$('#sn_pagination').html(json['pagination']);
	
	if (resetValues) {
		$('input[name=filter_sn_sn]').val('');
		$('select[name=filter_sn_status]').val(0);
		$('select[name=filter_sn_status]').trigger('change');
	}
};

function resetSNQuantity() {
	$('#new_serial_no_list tr').slice(1, -1).remove();
	$('input[name^=\'product_sn[1]\']').val('');
};

function deleteSN(anchor) {
	if ($('#product_serial_no_list input[type=\'checkbox\']:checked').length == 0) {
		// nothing is selected
		alert('<?php echo $text_no_sn_selected; ?>');
	} else {
		if (confirm('<?php echo $text_confirm_delete_sn; ?>')) {
			var product_id = <?php echo $product_id; ?>;
			var data = $('#product_serial_no_list input[type=\'checkbox\']:checked');
			
			$.ajax({
				url: 'index.php?route=pos/extended_product/deleteProductSN&token=<?php echo $token; ?>&product_id=' + product_id,
				type: 'post',
				dataType: 'json',
				data: data,
				beforeSend: function() {
					$(anchor).hide();
					$(anchor).before('<img src="view/image/pos/loading.gif" class="loading" style="padding-left: 5px;" />');	
				},
				complete: function() {
					$('.loading').remove();
					$(anchor).show();
				},
				success: function(json) {
					if (json['error']) {
						alert(json['error']);
					}
					reloadSN(json, true);
				}
			});
		}
	}
};

function filterSN(page) {
	var anchor = $('#button_sn_filter');
	var filter_product_id = <?php echo $product_id; ?>;
	var filter_sn = $('input[name=\'filter_sn_sn\']').val();
	var filter_status = $('select[name=filter_sn_status]').val();
	var data = {'product_id':filter_product_id, 'sn':filter_sn, 'status':filter_status};
	if (page) {
		data['page'] = page;
	}
	// get the SN from the database with filters
	$.ajax({
		url: 'index.php?route=pos/extended_product/getProductSN&token=<?php echo $token; ?>',
		type: 'post',
		dataType: 'json',
		data: data,
		beforeSend: function() {
			$(anchor).hide();
			$(anchor).before('<img src="view/image/pos/loading.gif" class="loading" style="padding-left: 5px;" />');	
		},
		complete: function() {
			$('.loading').remove();
			$(anchor).show();
		},
		success: function(json) {
			if (json) {
				reloadSN(json, false);
			}
		}
	});
};

function selectProductSNPage(page) {
	filterSN(page);
};

$('#modify_sn_dialog').dialog({
	autoOpen: false,
	modal: true,
	width: 300,
	height: 160,
	buttons: {}
});

function modifySN(anchor) {
	var product_sn_id = $(anchor).closest('tr').find('input').val();
	var product_sn = $('#product_sn' + product_sn_id).text();
	$('input[name=modify_product_sn]').val(product_sn);
	$('input[name=modify_product_sn_id]').val(product_sn_id);
	$('#modify_sn_dialog').dialog('open');
};

function saveModifedSN() {
	var product_sn = $('input[name=modify_product_sn]').val();
	var product_sn_id = $('input[name=modify_product_sn_id]').val();
	var data = {'product_sn_id':product_sn_id, 'product_sn':product_sn};
	var anchor = $('#product_sn' + product_sn_id).closest('tr').find('a');
	if (product_sn && product_sn_id) {
		$('#modify_sn_dialog').dialog('close');
		$.ajax({
			url: 'index.php?route=pos/extended_product/editProductSN&token=<?php echo $token; ?>',
			type: 'post',
			dataType: 'json',
			data: data,
			beforeSend: function() {
				$(anchor).hide();
				$(anchor).before('<img src="view/image/pos/loading.gif" class="loading" style="padding-left: 5px;" />');	
			},
			complete: function() {
				$('.loading').remove();
				$(anchor).show();
			},
			success: function(json) {
				$('#product_sn' + product_sn_id).text(product_sn);
			}
		});
	}
}

//--></script> 
</div>
<?php echo $footer; ?> 