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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $heading_title; ?></h3>
 		<div class="pull-right" style="margin-top: -10px;">
			<a onclick="newPackaging();" class="btn btn-primary"><i class="fa fa-plus fa-lg"></i> <?php echo $button_new_packaging; ?></a>
			<a onclick="deleteSn();" id="button_delete" class="btn btn-danger"><i class="fa fa-trash fa-lg"></i> <?php echo $button_delete; ?></a>
			<a onclick="filter();" class="btn btn-info"><i class="fa fa-filter fa-lg"></i> <?php echo $button_filter; ?></a>
		</div>
      </div>
      <div class="panel-body">
		<form id="sn_manager_form" method="post" enctype="multipart/form-data">
        <table class="table table-stripped table-bordered table-hover" style="table-layout:fixed; width: 100%;">
		  <col width="2%" style="min-width: 10px;" />
		  <col width="15%" />
		  <col width="15%" />
		  <col width="14%" />
		  <col width="14%" />
		  <col width="10%" />
		  <col width="10%" />
		  <col width="10%" />
		  <col width="10%" />
          <thead>
            <tr>
              <td class="text-left"><input type="checkbox" onclick="$('input[name*=packaging_selected]').prop('checked', $(this).is(':checked'));" class="form-control"/></td>
			  <td class="text-left"><?php echo $column_packaging_slip; ?></td>
              <td class="text-left"><?php echo $column_order_number; ?></td>
              <td class="text-left"><?php echo $column_product_name; ?></td>
              <td class="text-left"><?php echo $column_product_sn; ?></td>
			  <td class="text-left"><?php echo $column_cost; ?></td>
			  <td class="text-left"><?php echo $column_product_sn_status; ?></td>
			  <td class="text-left"><?php echo $column_date; ?></td>
			  <td class="text-left"><?php echo $column_note; ?></td>
            </tr>
          </thead>
          <tbody id="sn_list">
            <tr class="filter">
			  <td></td>
              <td align="left">
				<input type="text" name="filter_packaging_slip" class="form-control" value="<?php echo $filter_packaging_slip; ?>" />
				<input type="hidden" name="filter_packaging_id" value="<?php echo $filter_packaging_id; ?>" />
			  </td>
              <td align="left">
				<input type="text" name="filter_order_number" class="form-control" value="<?php echo $filter_order_number; ?>" />
			  </td>
              <td align="left">
				<input type="text" name="filter_product_name" class="form-control" value="<?php echo $filter_product_name; ?>" />
				<input type="hidden" name="filter_product_id" value="<?php echo $filter_product_id; ?>" />
			  </td>
              <td align="left">
				<input type="text" name="filter_product_sn" class="form-control" value="<?php echo $filter_product_sn; ?>" />
				<input type="hidden" name="filter_product_sn_id" value="<?php echo $filter_product_sn_id; ?>" />
			  </td>
              <td align="left">
				<input type="text" name="filter_product_sn_cost" class="form-control" value="<?php echo $filter_product_sn_cost; ?>" />
			  </td>
              <td align="left">
			    <select name="filter_product_sn_status" class="form-control">
				  <option value="0"></option>
				  <option value="1" <?php if ($filter_product_sn_status == '1') { ?> selected="selected" <?php } ?>><?php echo $text_sn_in_store; ?></option>
				  <option value="2" <?php if ($filter_product_sn_status == '2') { ?> selected="selected" <?php } ?>><?php echo $text_sn_sold; ?></option>
                  </select>
			  </td>
              <td align="left">
				<input type="text" name="filter_packaging_date" class="form-control" value="<?php echo $filter_packaging_date; ?>" data-format="YYYY-MM-DD" data-date-format="YYYY-MM-DD" />
			  </td>
              <td align="left">
				<input type="text" name="filter_packaging_note" class="form-control" value="<?php echo $filter_packaging_note; ?>" />
			  </td>
            </tr>
            <?php if (!empty($sns)) { $row = 0;?>
            <?php foreach ($sns as $sn) { $row ++; ?>
            <tr>
			  <input type="hidden" name="packaging_id_<?php echo $row; ?>" value="<?php echo $sn['packaging_id']; ?>" />
			  <input type="hidden" name="product_sn_id_<?php echo $row; ?>" value="<?php echo $sn['product_sn_id']; ?>" />
			  <td class="text-left"><input type="checkbox" name="packaging_selected[<?php echo $row; ?>]" value="<?php echo $sn['product_sn_id']; ?>" /></td>
              <td class="text-left" id="packaging_slip_<?php echo $row; ?>"><?php echo $sn['packaging_slip']; ?></td>
              <td class="text-left" id="order_number_<?php echo $row; ?>"><?php echo $sn['order_number']; ?></td>
              <td class="text-left"><?php echo $sn['product_name']; ?></td>
              <td class="text-left" id="product_sn_<?php echo $row; ?>"><?php echo $sn['product_sn']; ?></td>
              <td class="text-left" id="product_sncost_<?php echo $row; ?>"><?php echo $sn['cost']; ?></td>
			  <td class="text-left"><?php echo ($sn['product_sn_status'] == '2' ? $text_sn_sold : $text_sn_in_store); ?></td>
			  <td class="text-left" id="packaging_date_<?php echo $row; ?>"><?php echo $sn['date']; ?></td>
			  <td class="text-left" id="packaging_note_<?php echo $row; ?>"><?php echo $sn['note']; ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="text-center" colspan="9"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
		</form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
	  </div>
    </div>
  </div>
  <div style="display: none;">
    <div id="new_packaging_dialog" title="<?php echo $text_new_packaging; ?>">
	  <table class="table table-striped table-bordered">
	    <tr>
			<td class="text-left"><?php echo $column_packaging_slip; ?>:</td>
			<td class="text-left"><input name="packaging[packaging_slip]" type="text" class="form-control" /></td>
			<td class="text-left"><?php echo $column_order_number; ?>:</td>
			<td class="text-left"><input name="packaging[order_number]" type="text" class="form-control" /></td>
		</tr>
	    <tr>
			<td class="text-left"><?php echo $column_date; ?>:</td>
			<td class="text-left"><input name="packaging[date]" type="text" class="form-control" data-format="YYYY-MM-DD" data-date-format="YYYY-MM-DD" /></td>
			<td class="text-left"><?php echo $column_note; ?>:</td>
			<td class="text-left"><input name="packaging[note]" type="text" class="form-control" /></td>
		</tr>
	    <tr>
			<td class="text-left" style="background: #d1d1d1;"><?php echo $column_product_name; ?>:</td>
			<td class="text-left" style="background: #d1d1d1;" colspan="3">
				<input name="product_name[0]" type="text" class="form-control" />
				<input name="product_id[0]" type="hidden" />
			</td>
		</tr>
	  </table>
	</div>
  </div>
</div>

<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=pos/sn_manager&token=<?php echo $token; ?>';
	
	var filter_packaging_id = $('input[name=\'filter_packaging_id\']').val();
	if (filter_packaging_id) {
		url += '&filter_packaging_id=' + encodeURIComponent(filter_packaging_id);
	}
	
	var filter_packaging_slip = $('input[name=\'filter_packaging_slip\']').val();
	if (filter_packaging_slip) {
		url += '&filter_packaging_slip=' + encodeURIComponent(filter_packaging_slip);
	}
	
	var filter_order_number = $('input[name=\'filter_order_number\']').val();
	if (filter_order_number) {
		url += '&filter_order_number=' + encodeURIComponent(filter_order_number);
	}
	
	var filter_product_sn_id = $('input[name=\'filter_product_sn_id\']').val();
	if (filter_product_sn_id) {
		url += '&filter_product_sn_id=' + encodeURIComponent(filter_product_sn_id);
	}
	
	var filter_product_sn = $('input[name=\'filter_product_sn\']').val();
	if (filter_product_sn) {
		url += '&filter_product_sn=' + encodeURIComponent(filter_product_sn);
	}
	
	var filter_product_sn_cost = $('input[name=\'filter_product_sn_cost\']').val();
	if (filter_product_sn_cost) {
		url += '&filter_product_sn_cost=' + encodeURIComponent(filter_product_sn_cost);
	}
	
	var filter_product_id = $('input[name=\'filter_product_id\']').val();
	if (filter_product_id) {
		url += '&filter_product_id=' + encodeURIComponent(filter_product_id);
	}
	
	var filter_product_name = $('input[name=\'filter_product_name\']').val();
	if (filter_product_name) {
		url += '&filter_product_name=' + encodeURIComponent(filter_product_name);
	}
	
	var filter_product_sn_status = $('select[name=\'filter_product_sn_status\']').val();
	if (filter_product_sn_status) {
		url += '&filter_product_sn_status=' + encodeURIComponent(filter_product_sn_status);
	}
	
	var filter_packaging_date = $('input[name=\'filter_packaging_date\']').val();
	if (filter_packaging_date) {
		url += '&filter_packaging_date=' + encodeURIComponent(filter_packaging_date);
	}
	
	var filter_packaging_note = $('input[name=\'filter_packaging_note\']').val();
	if (filter_packaging_note) {
		url += '&filter_packaging_note=' + encodeURIComponent(filter_packaging_note);
	}
	
	location = url;
}

$('input[name=\'filter_packaging_slip\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		var url = 'index.php?route=pos/sn_manager/autocomplete_packaging&token=<?php echo $token; ?>&filter_packaging_slip=' + encodeURIComponent(request);
		$.ajax({
			url: url,
			dataType: 'json',
			success: function(json) {	
				response($.map(json, function(item) {
					return {
						packaging_id: item['packaging_id'],
						label: item['packaging_slip']
					}
				}));
			}
		});
	}, 
	'select': function(item) {
		$('input[name=\'filter_packaging_id\']').val(item['packaging_id']);
		$('input[name=\'filter_packaging_slip\']').val(item['label']);
		return false;
	},
	focus: function(event, ui) {
		return false;
	}
});

$('input[name=\'filter_packaging_slip\']').focus(function(){
	$('input[name=\'filter_packaging_id\']').val('');
});

$('input[name=\'filter_order_number\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		var url = 'index.php?route=pos/sn_manager/autocomplete_packaging&token=<?php echo $token; ?>&filter_order_number=' + encodeURIComponent(request);
		$.ajax({
			url: url,
			dataType: 'json',
			success: function(json) {	
				response($.map(json, function(item) {
					return {
						packaging_id: item['packaging_id'],
						label: item['order_number']
					}
				}));
			}
		});
	}, 
	'select': function(item) {
		$('input[name=\'filter_packaging_id\']').val(item['packaging_id']);
		$('input[name=\'filter_order_number\']').val(item['label']);
		return false;
	},
	focus: function(event, ui) {
		return false;
	}
});

$('input[name=\'filter_order_number\']').focus(function(){
	$('input[name=\'filter_packaging_id\']').val('');
});

$('input[name=\'filter_product_sn\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		var url = 'index.php?route=pos/sn_manager/autocomplete_product_sn&token=<?php echo $token; ?>&filter_product_sn=' + encodeURIComponent(request);
		$.ajax({
			url: url,
			dataType: 'json',
			success: function(json) {	
				response($.map(json, function(item) {
					return {
						label: item['product_sn'],
						product_sn_id: item['product_sn_id']
					}
				}));
			}
		});
	}, 
	'select': function(item) {
		$('input[name=\'filter_product_sn_id\']').val(item['product_sn_id']);
		$('input[name=\'filter_product_sn\']').val(item['label']);
		return false;
	},
	focus: function(event, ui) {
		return false;
	}
});

$('input[name=\'filter_product_sn\']').focus(function(){
	$('input[name=\'filter_product_sn_id\']').val('');
});

$('input[name=\'filter_product_name\']').autocomplete({
	'source': function(request, response) {
		var url = 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request);
		$.ajax({
			url: url,
			dataType: 'json',
			success: function(json) {	
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id'],
					}
				}));
			}
		});
	}, 
	'select': function(item) {
		$('input[name=\'filter_product_name\']').val(item['label']);
		$('input[name=\'filter_product_id\']').val(item['value']);
		return false;
	}
});

$('input[name=\'filter_product_name\']').focus(function(){
	$('input[name=\'filter_product_id\']').val('');
});

$('td[id^=packaging_slip_]').click(function(e) {
	
	if ($(this).find('input[type=text]').length > 0) return;

	var orgText = $(this).text();
	var width = $(this).width()-2;
	
	var packaging_id = $(this).closest('tr').find('input[name^=packaging_id_]').val();
	var product_sn_id = $(this).closest('tr').find('input[name^=product_sn_id_]').val();

	$(this).text('');
	$('<input style="width:'+width+'px;" type="text" name="temp_input" class="onenter onblur"/>').appendTo($(this));
	$('.onenter').val(orgText).select().keyup(function(e) {
		if (e.keyCode == 13) {
			changePackagingSlip(this, orgText, packaging_id, product_sn_id);
		} else if (e.keyCode == 27) {
			$('input[name=temp_input]').parent().text(orgText);
			$('input[name=temp_input]').remove();
		}
	});
	$('.onblur').val(orgText).select().blur(function() {
		changePackagingSlip(this, orgText, packaging_id, product_sn_id);
	});
});

function changePackagingSlip(input, orgText, packaging_id, product_sn_id) {
	var text = $(input).val();
	var parent = $(input).parent();
	$(input).parent().find('input[type=text]').remove();
	if (text != '') {
		parent.text('');

		var data = {'packaging_id':packaging_id, 'packaging_slip':text};
		$.ajax({
			url: 'index.php?route=pos/sn_manager/change_packaging_slip&token=<?php echo $token; ?>',
			type: 'post',
			dataType: 'json',
			data: data,
			beforeSend: function() {
				$(parent).append('<img src="view/image/pos/loading.gif" class="loading" style="padding-left: 5px;" />');	
			},
			complete: function() {
				$('.loading').remove();
			},
			success: function(json) {
				if (json['error']) {
					alert(json['error']);
					parent.text(orgText);
				} else {
					parent.text(text);
					// find all rows with the same packaging id and update the packaging slips
					$('#sn_list tr').each(function() {
						var row_packaging_id = parseInt($(this).find('input[name^=packaging_id_]').val());
						if (parseInt(packaging_id) == row_packaging_id) {
							// update the quantity
							$(this).find('td:eq(1)').text(text);
						}
					});
				}
			}
		});
	}
};

$('td[id^=order_number_]').click(function(e) {
	
	if ($(this).find('input[type=text]').length > 0) return;

	var orgText = $(this).text();
	var width = $(this).width()-2;
	
	var packaging_id = $(this).closest('tr').find('input[name^=packaging_id_]').val();
	var product_sn_id = $(this).closest('tr').find('input[name^=product_sn_id_]').val();

	$(this).text('');
	$('<input style="width:'+width+'px;" type="text" name="temp_input" class="onenter onblur"/>').appendTo($(this));
	$('.onenter').val(orgText).select().keyup(function(e) {
		if (e.keyCode == 13) {
			changeOrderNumber(this, orgText, packaging_id, product_sn_id);
		} else if (e.keyCode == 27) {
			$('input[name=temp_input]').parent().text(orgText);
			$('input[name=temp_input]').remove();
		}
	});
	$('.onblur').val(orgText).select().blur(function() {
		changeOrderNumber(this, orgText, packaging_id, product_sn_id);
	});
});

function changeOrderNumber(input, orgText, packaging_id, product_sn_id) {
	var text = $(input).val();
	var parent = $(input).parent();
	$(input).parent().find('input[type=text]').remove();
	if (text != '') {
		parent.text('');

		var data = {'packaging_id':packaging_id, 'order_number':text};
		$.ajax({
			url: 'index.php?route=pos/sn_manager/change_order_number&token=<?php echo $token; ?>',
			type: 'post',
			dataType: 'json',
			data: data,
			beforeSend: function() {
				$(parent).append('<img src="view/image/pos/loading.gif" class="loading" style="padding-left: 5px;" />');	
			},
			complete: function() {
				$('.loading').remove();
			},
			success: function(json) {
				if (json['error']) {
					alert(json['error']);
					parent.text(orgText);
				} else {
					parent.text(text);
					// find all rows with the same packaging id and update the order number
					$('#sn_list tr').each(function() {
						var row_packaging_id = parseInt($(this).find('input[name^=packaging_id_]').val());
						if (parseInt(packaging_id) == row_packaging_id) {
							$(this).find('td:eq(2)').text(text);
						}
					});
				}
			}
		});
	}
};

$('td[id^=packaging_date_]').click(function(e) {
	
	if ($(this).find('input[type=text]').length > 0) return;

	var orgText = $(this).text();
	var width = $(this).width()-2;
	
	var packaging_id = $(this).closest('tr').find('input[name^=packaging_id_]').val();
	var product_sn_id = $(this).closest('tr').find('input[name^=product_sn_id_]').val();

	$(this).text('');
	$('<input style="width:'+width+'px;" type="text" name="temp_input" class="onenter onblur date" data-format="YYYY-MM-DD" />').appendTo($(this));
	$('.onenter').val(orgText).select().keyup(function(e) {
		if (e.keyCode == 13) {
			changePackagingDate(this, orgText, packaging_id, product_sn_id);
		} else if (e.keyCode == 27) {
			$('input[name=temp_input]').parent().text(orgText);
			$('input[name=temp_input]').remove();
		}
	});
	$('.onblur').val(orgText).select().blur(function() {
		changePackagingDate(this, orgText, packaging_id, product_sn_id);
	});
});

function changePackagingDate(input, orgText, packaging_id, product_sn_id) {
	var text = $(input).val();
	var parent = $(input).parent();
	$(input).parent().find('input[type=text]').remove();
	if (text != '') {
		parent.text('');

		var data = {'packaging_id':packaging_id, 'date':text};
		$.ajax({
			url: 'index.php?route=pos/sn_manager/change_packaging_date&token=<?php echo $token; ?>',
			type: 'post',
			dataType: 'json',
			data: data,
			beforeSend: function() {
				$(parent).append('<img src="view/image/pos/loading.gif" class="loading" style="padding-left: 5px;" />');	
			},
			complete: function() {
				$('.loading').remove();
			},
			success: function(json) {
				if (json['error']) {
					alert(json['error']);
					parent.text(orgText);
				} else {
					parent.text(text);
					// find all rows with the same packaging id and update the packaging date
					$('#sn_list tr').each(function() {
						var row_packaging_id = parseInt($(this).find('input[name^=packaging_id_]').val());
						if (parseInt(packaging_id) == row_packaging_id) {
							// update the quantity
							$(this).find('td:nth-last-child(2)').text(text);
						}
					});
				}
			}
		});
	}
};

$('td[id^=packaging_note_]').click(function(e) {
	
	if ($(this).find('input[type=text]').length > 0) return;

	var orgText = $(this).text();
	var width = $(this).width()-2;
	
	var packaging_id = $(this).closest('tr').find('input[name^=packaging_id_]').val();
	var product_sn_id = $(this).closest('tr').find('input[name^=product_sn_id_]').val();

	$(this).text('');
	$('<input style="width:'+width+'px;" type="text" name="temp_input" class="onenter onblur"/>').appendTo($(this));
	$('.onenter').val(orgText).select().keyup(function(e) {
		if (e.keyCode == 13) {
			changePackagingNote(this, orgText, packaging_id, product_sn_id);
		} else if (e.keyCode == 27) {
			$('input[name=temp_input]').parent().text(orgText);
			$('input[name=temp_input]').remove();
		}
	});
	$('.onblur').val(orgText).select().blur(function() {
		changePackagingNote(this, orgText, packaging_id, product_sn_id);
	});
});

function changePackagingNote(input, orgText, packaging_id, product_sn_id) {
	var text = $(input).val();
	var parent = $(input).parent();
	$(input).parent().find('input[type=text]').remove();
	if (text != '') {
		parent.text('');

		var data = {'packaging_id':packaging_id, 'note':text};
		$.ajax({
			url: 'index.php?route=pos/sn_manager/change_packaging_note&token=<?php echo $token; ?>',
			type: 'post',
			dataType: 'json',
			data: data,
			beforeSend: function() {
				$(parent).append('<img src="view/image/pos/loading.gif" class="loading" style="padding-left: 5px;" />');	
			},
			complete: function() {
				$('.loading').remove();
			},
			success: function(json) {
				if (json['error']) {
					alert(json['error']);
					parent.text(orgText);
				} else {
					parent.text(text);
					// find all rows with the same packaging id and update the packaging note
					$('#sn_list tr').each(function() {
						var row_packaging_id = parseInt($(this).find('input[name^=packaging_id_]').val());
						if (parseInt(packaging_id) == row_packaging_id) {
							// update the quantity
							$(this).find('td:last').text(text);
						}
					});
				}
			}
		});
	}
};

$('td[id^=product_sn_]').click(function(e) {
	
	if ($(this).find('input[type=text]').length > 0) return;

	var orgText = $(this).text();
	var width = $(this).width()-2;
	
	var packaging_id = $(this).closest('tr').find('input[name^=packaging_id_]').val();
	var product_sn_id = $(this).closest('tr').find('input[name^=product_sn_id_]').val();

	$(this).text('');
	$('<input style="width:'+width+'px;" type="text" name="temp_input" class="onenter onblur"/>').appendTo($(this));
	$('.onenter').val(orgText).select().keyup(function(e) {
		if (e.keyCode == 13) {
			changeProductSN(this, orgText, packaging_id, product_sn_id);
		} else if (e.keyCode == 27) {
			$('input[name=temp_input]').parent().text(orgText);
			$('input[name=temp_input]').remove();
		}
	});
	$('.onblur').val(orgText).select().blur(function() {
		changeProductSN(this, orgText, packaging_id, product_sn_id);
	});
});

function changeProductSN(input, orgText, packaging_id, product_sn_id) {
	var text = $(input).val();
	var parent = $(input).parent();
	$(input).parent().find('input[type=text]').remove();
	if (text != '') {
		parent.text('');

		var data = {'product_sn_id':product_sn_id, 'product_sn':text};
		$.ajax({
			url: 'index.php?route=pos/sn_manager/change_product_sn&token=<?php echo $token; ?>',
			type: 'post',
			dataType: 'json',
			data: data,
			beforeSend: function() {
				$(parent).append('<img src="view/image/pos/loading.gif" class="loading" style="padding-left: 5px;" />');	
			},
			complete: function() {
				$('.loading').remove();
			},
			success: function(json) {
				if (json['error']) {
					alert(json['error']);
					parent.text(orgText);
				} else {
					parent.text(text);
				}
			}
		});
	}
};

$('td[id^=product_sncost_]').click(function(e) {
	
	if ($(this).find('input[type=text]').length > 0) return;

	var orgText = $(this).text();
	var width = $(this).width()-2;
	
	var packaging_id = $(this).closest('tr').find('input[name^=packaging_id_]').val();
	var product_sn_id = $(this).closest('tr').find('input[name^=product_sn_id_]').val();

	$(this).text('');
	$('<input style="width:'+width+'px;" type="text" name="temp_input" class="onenter onblur"/>').appendTo($(this));
	$('.onenter').val(orgText).select().keyup(function(e) {
		if (e.keyCode == 13) {
			changeProductSNCost(this, orgText, packaging_id, product_sn_id);
		} else if (e.keyCode == 27) {
			$('input[name=temp_input]').parent().text(orgText);
			$('input[name=temp_input]').remove();
		}
	});
	$('.onblur').val(orgText).select().blur(function() {
		changeProductSNCost(this, orgText, packaging_id, product_sn_id);
	});
});

function changeProductSNCost(input, orgText, packaging_id, product_sn_id) {
	var text = $(input).val();
	var parent = $(input).parent();
	$(input).parent().find('input[type=text]').remove();
	if (text != '' && parseFloat(text) != 'undefined') {
		parent.text('');

		var data = {'product_sn_id':product_sn_id, 'cost':parseFloat(text)};
		$.ajax({
			url: 'index.php?route=pos/sn_manager/change_product_sn_cost&token=<?php echo $token; ?>',
			type: 'post',
			dataType: 'json',
			data: data,
			beforeSend: function() {
				$(parent).append('<img src="view/image/pos/loading.gif" class="loading" style="padding-left: 5px;" />');	
			},
			complete: function() {
				$('.loading').remove();
			},
			success: function(json) {
				if (json['error']) {
					alert(json['error']);
					parent.text(orgText);
				} else {
					parent.text(text);
				}
			}
		});
	}
};

function deleteSn() {
	if ($('#sn_list input[type=\'checkbox\']:checked').length == 0) {
		// nothing is selected
		alert('<?php echo $text_no_item_selected; ?>');
	} else {
		if (confirm('<?php echo $text_confirm_delete; ?>')) {
			var data = '#sn_list input[type=\'checkbox\']:checked';
			var url = 'index.php?route=pos/sn_manager/delete_product_sn&token=<?php echo $token; ?>';
			var msg_dialog = $('<div id="msg_modal" title="..."><p style="text-align: center;"><img src="view/image/loading.gif" alt="" /><?php echo $text_deleting; ?></p></div>');
			$.ajax({
				url: url,
				type: 'post',
				data: $(data),
				dataType: 'json',
				beforeSend: function() {
					msg_dialog.dialog({
						modal: true,
						width: 500,
						height: 100
					});
				},
				complete: function() {
					msg_dialog.dialog('close');
				},
				success: function(json) {
					filter();
				}
			});
		}
	}
};

function newPackaging() {
	$('#new_packaging_dialog').dialog('open');
};

$('#new_packaging_dialog').on('focus', 'input[name^=\'product_name\']', function() {
	el = $(this);
	var pre_product_id = $(this).closest('td').find('input[name^=product_id]').val();
	el.autocomplete({
		delay: 500,
		source: function(request, response) {
			var url = 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term);
			$.ajax({
				url: url,
				dataType: 'json',
				success: function(json) {	
					response($.map(json, function(item) {
						return {
							label: item['name'],
							value: item['product_id'],
						}
					}));
				}
			});
		}, 
		select: function(event, ui) {
			el.val(ui.item['label']);
			var product_id = ui.item['value'];
			
			el.closest('td').find('input[name^=\'product_id\']').val(product_id);
			
			var name = el.attr('name');
			var index1 = name.indexOf('[');
			var index2 = name.indexOf(']');
			var index = parseInt(name.substring(index1+1, index2));
			
			// check if the tbody for this product has been created
			var html = '';
			var existed = false;
			if ($('#product_sns_' + pre_product_id).length > 0) {
				// if created already, remove the whole body as the product has been changed
				$('#product_sns_' + pre_product_id).empty();
				existed = true;
			}
			
			if (!existed) {
				// create the tbody for the product
				html += '<tbody id="product_sns_' + product_id + '">';
			}
			html += '<tr><td class="text-left"><?php echo $column_product_sn; ?>:</td>';
			html += '<td class="text-left"><input type="text" name="product_sn[' + product_id + '][0][product_sn]" class="form-control"/></td>';
			html += '<td class="text-left"><?php echo $column_cost; ?>:</td>';
			html += '<td class="text-left"><input type="text" name="product_sn[' + product_id + '][0][cost]" class="form-control"/></td></tr>'
			if (!existed) {
				html += '</tbody>';
				// not created yet, need to create the next product entry area
				html += '<tr><td class="text-left" style="background: #d1d1d1;"><?php echo $column_product_name; ?>:</td>';
				html += '<td class="text-left" style="background: #d1d1d1;" colspan="3">';
				html += '<input name="product_name[' + (index+1) + ']" type="text" class="form-control" />';
				html += '<input name="product_id[' + (index+1) + ']" type="hidden" /></td></tr>';
			}
			
			// append right after the current product title
			if (!existed) {
				$('#new_packaging_dialog table').append(html);
			} else {
				$('#product_sns_' + pre_product_id).html(html);
				// change the id
				$('#product_sns_' + pre_product_id).attr('id', 'product_sns_' + product_id);
			}

			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
});
</script>

<link rel="stylesheet" type="text/css" href="view/javascript/jquery/ui/jquery-ui.min.css" />
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui.min.js"></script>

<script type="text/javascript">
$('#new_packaging_dialog').dialog({
	autoOpen: false,
	width: 720,
	height: 600,
	modal: true,
	buttons: {
		OK: function() {
			savePackaging();
		}
	}
});

$(document).on('keydown', 'input[name^=\'product_sn[\']', function() {
	var name = $(this).attr('name');
	var index1 = name.indexOf('[');
	var index2 = name.indexOf(']');
	var product_id = name.substring(index1+1, index2);
	var len = $('#product_sns_' + product_id + ' tr').length;
	var index = $('#product_sns_' + product_id + ' tr').index($(this).closest('tr'));
	if (index == len - 1) {
		// it's the last line, create another line
		html = '<tr><td class="text-left"><?php echo $column_product_sn; ?>:</td>';
		html += '<td class="text-left"><input type="text" name="product_sn[' + product_id + '][' + len + '][product_sn]" class="form-control"/></td>';
		html += '<td class="text-left"><?php echo $column_cost; ?>:</td>';
		html += '<td class="text-left"><input type="text" name="product_sn[' + product_id + '][' + len + '][cost]" class="form-control"/></td></tr>'
		$('#product_sns_' + product_id).append(html);
	}
});

function savePackaging() {
	// save all inputs in the new packaging page
	var data = '#new_packaging_dialog input[name^=\'product_sn[\'], #new_packaging_dialog input[name^=\'packaging[\']';
	
	var msg_dialog = $('<div id="msg_modal" title="..."><p style="text-align: center;"><img src="view/image/loading.gif" alt="" /><?php echo $text_saving_packaging; ?></p></div>');
	$.ajax({
		url: 'index.php?route=pos/sn_manager/save_packaging&token=<?php echo $token; ?>',
		type: 'post',
		dataType: 'json',
		data: $(data),
		beforeSend: function() {
			msg_dialog.dialog({
				modal: true,
				width: 500,
				height: 100
			});
		},
		complete: function() {
			msg_dialog.dialog('close');
		},
		success: function(json) {
			$('#new_packaging_dialog').dialog('close');
			filter();
		}
	});
}
$(document).ready(function() {
	$('input[name=\'packaging[date]\']').datetimepicker({pickTime: false});
	$('input[name=filter_packaging_date]').datetimepicker({pickTime: false});
});

</script>
<?php echo $footer; ?>