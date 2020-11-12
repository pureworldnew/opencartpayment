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
			<a onclick="printStock(this);" class="btn btn-info"><i class="fa fa-print fa-lg"></i> <?php echo $button_print_stock_report; ?></a>
			<a onclick="printRestock(this);" class="btn btn-info"><i class="fa fa-print fa-lg"></i> <?php echo $button_print_restock_report; ?></a>
			<a onclick="filter();" class="btn btn-primary"><i class="fa fa-filter fa-lg"></i> <?php echo $button_filter; ?></a>
		</div>
      </div>
      <div class="panel-body">
		<form id="stock_manager_form" method="post" enctype="multipart/form-data">
        <table class="table table-stripped table-bordered table-hover" style="table-layout:fixed; width: 100%;">
			<col width="25" />
			<col width="25" />
			<col width="20" />
			<col width="15" />
			<col width="15" />
          <thead>
            <tr>
              <td class="text-left"><?php echo $column_product_name; ?></td>
              <td class="left"><?php echo $column_model; ?></td>
              <td class="text-left"><?php echo $column_product_option; ?></td>
              <td class="text-left"><?php echo $column_product_quantity; ?></td>
			  <td class="text-left"><?php echo $column_alert; ?></td>
            </tr>
          </thead>
          <tbody id="stock_list">
            <tr class="filter">
              <td align="left">
				<input type="text" name="filter_product_name" class="form-control" value="<?php echo $filter_product_name; ?>" />
				<input type="hidden" name="filter_product_id" value="<?php echo $filter_product_id; ?>" />
			  </td>
              <td align="left">
				<input type="text" name="filter_model" class="form-control" value="<?php echo $filter_model; ?>" />
			  </td>
			  <td></td>
			  <td></td>
			  <td></td>
            </tr>
            <?php if ($stocks) { $row_num = 0; ?>
            <?php foreach ($stocks as $stock) { $bgcolor = (!empty($stock['children'])) ? ((strpos($stock['children'], '|') === false) ? 'style="background-color: #aaa"' : 'style="background-color: #ddd"') : ''; ?>
            <tr>
              <td class="text-left" <?php echo $bgcolor; ?>>
				<?php echo $stock['product_name']; ?>
				<input type="hidden" name="product_id_<?php echo $row_num; ?>" value="<?php echo $stock['product_id']; ?>" />
				<input type="hidden" name="product_option_value_id_<?php echo $row_num; ?>" value="<?php echo $stock['product_option_value_id']; ?>" />
				<input type="hidden" name="children_<?php echo $row_num; ?>" value="<?php echo $stock['children']; ?>" />
			  </td>
              <td class="left" <?php echo $bgcolor; ?>>
				<?php echo $stock['model']; ?>
			  </td>
              <td class="text-left" <?php echo $bgcolor; ?>><?php echo $stock['option_name']; ?></td>
              <td class="text-left" id="product_quantity_<?php echo $row_num; ?>" <?php echo ((int)$stock['quantity'] < (int)$stock['low_stock']) ? 'style="background-color: #cc5151"' : $bgcolor; ?>><?php echo $stock['quantity']; ?></td>
			  <td class="text-left" id="product_low_stock_<?php echo $row_num; ?>" <?php echo $bgcolor; ?>><?php echo $stock['low_stock']; ?></td>
            </tr>
            <?php $row_num++; } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="5"><?php echo $text_no_results; ?></td>
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
	<iframe id="print_iframe" src="about:blank" style="display:none; width: 0; height: 0;"></iframe>
  </div>
</div>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=module/pos/stock_manager&token=<?php echo $token; ?>';
	
	var filter_product_id = $('input[name=\'filter_product_id\']').val();
	if (filter_product_id) {
		url += '&filter_product_id=' + encodeURIComponent(filter_product_id);
	}
	var filter_product_name = $('input[name=\'filter_product_name\']').val();
	if (filter_product_name) {
		url += '&filter_product_name=' + encodeURIComponent(filter_product_name);
	}
	var filter_model = $('input[name=\'filter_model\']').val();
	if (filter_model) {
		url += '&filter_model=' + encodeURIComponent(filter_model);
	}
	
	location = url;
}

$('input[name=\'filter_product_name\']').autocomplete({
	delay: 500,
	source: function(request, response) {
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
	},
	focus: function(event, ui) {
		return false;
	}
});

$('input[name=\'filter_product_name\']').focus(function(){
	$('input[name=\'filter_product_id\']').val('');
});

$('input[name=\'filter_model\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		var url = 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_model=' + encodeURIComponent(request);
		$.ajax({
			url: url,
			dataType: 'json',
			success: function(json) {	
				response($.map(json, function(item) {
					return {
						label: item.model,
						value: item.product_id,
					}
				}));
			}
		});
	}, 
	select: function(item) {
		$('input[name=\'filter_model\']').val(item.label);
		$('input[name=\'filter_product_id\']').val(item.value);
		return false;
	},
	focus: function(event, ui) {
		return false;
	}
});

$('input[name=\'filter_model\']').focus(function(){
	$('input[name=\'filter_product_id\']').val('');
});

$('td[id^=product_quantity_]').click(function(e) {
	
	if ($(this).find('input[type=text]').length > 0) return;

	var orgText = $(this).text();
	var width = $(this).width()-2;

	$(this).text('');
	$('<input style="width:'+width+'px;" type="text" name="temp_input" class="onenter onblur"/>').appendTo($(this));
	$('.onenter').val(orgText).select().keyup(function(e) {
		if (e.keyCode == 13) {
			changeProductStock(this, orgText);
		} else if (e.keyCode == 27) {
			$('input[name=temp_input]').parent().text(orgText);
			$('input[name=temp_input]').remove();
		}
	});
	$('.onblur').val(orgText).select().blur(function() {
		changeProductStock(this, orgText);
	});
});

$('td[id^=product_low_stock_]').click(function(e) {
	
	if ($(this).find('input[type=text]').length > 0) return;

	var orgText = $(this).text();
	var width = $(this).width()-2;

	$(this).text('');
	$('<input style="width:'+width+'px;" type="text" name="temp_input" class="onenter onblur"/>').appendTo($(this));
	$('.onenter').val(orgText).select().keyup(function(e) {
		if (e.keyCode == 13) {
			changeProductLowStock(this, orgText);
		} else if (e.keyCode == 27) {
			$('input[name=temp_input]').parent().text(orgText);
			$('input[name=temp_input]').remove();
		}
	});
	$('.onblur').val(orgText).select().blur(function() {
		changeProductLowStock(this, orgText);
	});
});

function changeProductStock(input, orgText) {
	var text = $(input).val();
	var parent = $(input).parent();
	$(input).parent().find('input[type=text]').remove();
	if (text != '' && parseInt(text) != 'undefined') {
		parent.text('');

		var data = {'quantity':text, 'quantity_before':orgText};
		$(parent).closest('tr').find('input').each(function() {
			data[$(this).attr('name')] = $(this).val();
		});
		$.ajax({
			url: 'index.php?route=module/pos/save_product_stock&token=<?php echo $token; ?>',
			type: 'post',
			dataType: 'json',
			data: data,
			beforeSend: function() {
				$(parent).append('<img src="view/image/pos/loading.gif" class="loading" style="padding-left: 5px;" />');	
			},
			complete: function() {
				$('.loading').remove();
				parent.text(text);
				var row_alert = parseInt($(parent).closest('tr').find('td:last').text());
				if (parseInt(text) > row_alert) {
					if ($(parent).closest('tr').find('input[name^=children]').val()) {
						// has children
						if ($(parent).closest('tr').find('input[name^=children]').val().indexOf('|') > 0) {
							$(parent).css('background-color', '#ddd');
						} else {
							$(parent).css('background-color', '#aaa');
						}
					} else {
						$(parent).css('background-color', 'transparent');
					}
				} else {
					$(parent).css('background-color', '#cc5151');
				}
			},
			success: function(json) {
				if (json['error']) {
					alert(json['error']);
					parent.text(orgText);
				} else if (json['update_product']){
					// find all parent in the list and update
					$('#stock_list tr').each(function() {
						var row_product_id = parseInt($(this).find('input[name^=product_id]').val());
						var row_product_option_value_id = parseInt($(this).find('input[name^=product_option_value_id]').val());
						var row_alert = parseInt($(this).find('td:last').text());
						if (json['update_product'] && json['update_product'][row_product_id] && row_product_option_value_id == '0') {
							// update the quantity
							$(this).find('td:nth-last-child(2)').text(json['update_product'][row_product_id]);
							if (parseInt(json['update_product'][row_product_id]) > row_alert) {
								if ($(this).find('input[name^=children]').val()) {
									// has children
									$(this).find('td:nth-last-child(2)').css('background-color', '#aaa');
								} else {
									$(this).find('td:nth-last-child(2)').css('background-color', 'transparent');
								}
							} else {
								$(this).find('td:nth-last-child(2)').css('background-color', '#cc5151');
							}
						}
					});
				}
			}
		});
	}
};

function changeProductLowStock(input, orgText) {
	var text = $(input).val();
	var parent = $(input).parent();
	$(input).parent().find('input[type=text]').remove();
	if (text != '' && parseInt(text) != 'undefined') {
		parent.text('');

		var data = {'low_stock':text};
		$(parent).closest('tr').find('input').each(function() {
			data[$(this).attr('name')] = $(this).val();
		});
		$.ajax({
			url: 'index.php?route=module/pos/save_product_low_stock&token=<?php echo $token; ?>',
			type: 'post',
			dataType: 'json',
			data: data,
			beforeSend: function() {
				$(parent).append('<img src="view/image/pos/loading.gif" class="loading" style="padding-left: 5px;" />');	
			},
			complete: function() {
				$('.loading').remove();
				parent.text(text);
				var quantity_td = $(parent).closest('tr').find('td:nth-last-child(2)');
				var row_quantity = parseInt(quantity_td.text());
				if (parseInt(text) <= row_quantity) {
					if ($(parent).closest('tr').find('input[name^=children]').val()) {
						// has children
						if ($(parent).closest('tr').find('input[name^=children]').val().indexOf('|') > 0) {
							quantity_td.css('background-color', '#ddd');
						} else {
							quantity_td.css('background-color', '#aaa');
						}
					} else {
						quantity_td.css('background-color', 'transparent');
					}
				} else {
					quantity_td.css('background-color', '#cc5151');
				}
			},
			success: function(json) {
				if (json['error']) {
					alert(json['error']);
					parent.text(orgText);
				}
			}
		});
	}
};

function printStock(button) {
	window_print_url(button, 'index.php?route=module/pos/print_stock&token=<?php echo $token; ?>');
};

function printRestock(button) {
	window_print_url(button, 'index.php?route=module/pos/print_restock&token=<?php echo $token; ?>');
};

function window_print_url(button, url) {
	var data = {};
	// get the page from url and print it
	$.ajax({
		url: url,
		type: 'post',
		data: data,
		dataType: 'json',
		beforeSend: function() {
			$(button).hide();
			$(button).before('<img src="view/image/pos/loading.gif" class="loading" style="padding-left: 5px;" />');	
		},
		converters: {
			'text json': true
		},
		complete: function() {
			$('.loading').remove();
			$(button).show();
		},
		success: function(html) {
			// send html to iframe for printing
			$('#print_iframe').contents().find('html').html(html);

			setTimeout(function() {
				// append the print script
				if (navigator.appName == 'Microsoft Internet Explorer') {
					$("#print_iframe").get(0).contentWindow.document.execCommand('print', false, null);
				} else {
					$("#print_iframe").get(0).contentWindow.print();
				}
			}, 500);
		}
	});
};


</script> 
<?php echo $footer; ?>