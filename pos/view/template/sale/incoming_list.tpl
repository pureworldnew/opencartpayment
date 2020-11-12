<?php $edit_access = isset($permissions['incoming_orders'][0]) && !empty($permissions['incoming_orders'][0]) && $permissions['incoming_orders'][0] == 'edit_access'; ?>
<div id="content">
  <div class="container-fluid" style="margin-top: 25px;">
  	<?php if(!empty($error_upload)){ ?>
    <div style="color: #a94442; background-color: #f2dede; border-color: #ebccd1;padding: 10px;" class=""><?php foreach($error_upload as $error){ echo $error."<br/>";  } ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>
    <?php  } ?>
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
      <div class="panel-body">
        <div>
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-order-id"><?php echo $entry_order_id; ?></label>
                <input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" placeholder="<?php echo $entry_order_id; ?>" id="input-order-id" class="form-control" />
              </div>
			  <div class="form-group">
				<label class="control-label" for="input-date-added"><?php echo $entry_date_added; ?></label>
				<input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control daterange" />
			  </div>
              <div class="form-group hidden">
                <label class="control-label" for="input-customer"><?php echo $entry_customer; ?></label>
                <input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" placeholder="<?php echo $entry_customer; ?>" id="input-customer" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
				<div class="form-group">
					<label class="control-label" for="input-model"><?php echo $entry_model; ?></label>
					<input type="text" name="filter_model" value="<?php echo $filter_model; ?>" placeholder="<?php echo $entry_model; ?>" id="input-model" class="form-control" />
					<a onclick="openScanner();" class="non-catalog onlyone" style="color:#FFFFFF; background:#ecae1c; margin-top: 2px;"><span class="fa fa-scanner"></span>Scan Code</a>
				</div>
              <div class="form-group">
                <label class="control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
                <select name="filter_order_status" id="input-order-status" class="form-control">
                  <option value="*"></option>
                  <?php if ($filter_order_status == '0') { ?>
                  <option value="0" selected="selected"><?php echo $text_missing; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_missing; ?></option>
                  <?php } ?>
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $filter_order_status) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            
            </div>
            <div class="col-sm-4">
			  <!-- Manufacturer -->
			  <div class="form-group">	
					<label class="control-label" for="input-manufacturer">Manufacturers:</label>
					<div id="filter_man_id">
						<a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo "Select All"; ?></a> /  
						<a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo "Select none"; ?></a>
						<div class="well well-sm" style="height: 150px; width:100%; overflow: auto;">
							<?php $modify = explode(',',$filter_manufacturer); ?>
							<?php foreach ($manufacturers as $manufacturer) { ?>
								<div class="checkbox">
									<label>
										<?php if (in_array($manufacturer['manufacturer_id'], $modify)) { ?>
											<input class="form-control" type="checkbox" name="manufacturer[modify][]" value="<?php echo $manufacturer['manufacturer_id']; ?>" checked="checked" />
											<?php echo $manufacturer['name']; ?>
										<?php } else { ?>
											<input class="form-control" type="checkbox" name="manufacturer[modify][]" value="<?php echo $manufacturer['manufacturer_id']; ?>" />
											<?php echo $manufacturer['name']; ?>
										<?php } ?>
									</label>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
				<!-- /Manufacturer -->
				<div class="form-group">
					<label class="control-label" for="input-record-limit">Show: </label>
					<select name="filter_record_limit" id="input-record-limit"  class="form-control" style="max-width: 20%; display: inline-block;"> 
						<option value="10" <?php echo ($filter_record_limit == "10") ? "selected" : ""; ?>>10</option>
						<option value="20" <?php echo ($filter_record_limit == "20") ? "selected" : ""; ?>>20</option>
						<option value="50" <?php echo ($filter_record_limit == "50") ? "selected" : ""; ?>>50</option>
						<option value="100" <?php echo ($filter_record_limit == "100") ? "selected" : ""; ?>>100</option>
					</select>
					<button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
				</div>
            </div>
          </div>
        </div>
        <form method="post" enctype="multipart/form-data" target="_blank" id="form-order">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-right"><?php if ($sort == 'o.order_id') { ?>
                    <a href="<?php echo $sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_order_id; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_order; ?>"><?php echo $column_order_id; ?></a>
                    <?php } ?></td>
                  <td>Store</td>
                  <td>Mfr</td>
                  <td class="text-left"><?php if ($sort == 'status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
                 <td>Number Of Items</td>
                 <td>Pending Backorders</td>
                  <td class="text-right"><?php if ($sort == 'o.total') { ?>
                    <a href="<?php echo $sort_total; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_total; ?>"><?php echo $column_total; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'o.date_added') { ?>
                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'o.date_modified') { ?>
                    <a href="<?php echo $sort_date_modified; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_modified; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_modified; ?>"><?php echo $column_date_modified; ?></a>
                    <?php } ?></td>
				  <?php if( $edit_access ) { ?>
                  	<td class="text-right"><?php echo $column_action; ?></td>
				  <?php } ?>
                </tr>
              </thead>
              <tbody>
                <?php if ($orders) { ?>
                <?php foreach ($orders as $order) { ?>
                <tr>
				  <td class="text-right"><?php echo $order['order_id']; ?>
                    <input type="hidden" name="shipping_code[]" value="<?php echo $order['shipping_code']; ?>" />
				  </td>
                  <td class="text-right"><?php echo "Vendor"; ?></td>
                  <td class="text-left"><?php foreach($order['manufacturers'] as $manuf){ echo $manuf['name'].",";  } ?></td>
				  <?php if( $edit_access ) { ?>
                  <!-- Order Status -->
									<td class="text-left col-md-2" style="background-color:#<?php echo $order['order_status_color'];?>">
									<div class="status-edit" onclick="selectStatus(<?php echo $order['order_id']; ?> , '<?php echo $order['status']; ?>', '<?php echo $order['sales_person']; ?>');" id="status-<?php echo $order['order_id']; ?>"><?php echo $order['status']; ?></div>
									</td>
									<!-- /Order Status -->
				  <?php } else { ?>
					<td class="text-left"><?php echo $order['status']; ?></td>
				  <?php } ?>
                  <td class="text-left"><?php echo $order['no_of_items']; ?></td>
                  <td class="text-left"><?php echo $order['pending_backorders']; ?></td>
                  <td class="text-right"><?php echo $order['total']; ?></td>
                  <td class="text-left"><?php echo $order['date_added']; ?></td>
                  <td class="text-left"><?php echo $order['date_modified']; ?></td>
				  <?php if( $edit_access ) { ?>
                  <td class="text-right"> 
					<a href="<?php echo $order['pdfinv_packing']; ?>" data-toggle="tooltip" title="Packing Slip" class="btn btn-warning fency-incoming" target="_blank"><i class="fa fa-file-text-o"></i></a>
					<a href="<?php echo $order['link_pdf_order_request']; ?>" data-toggle="tooltip" title="PDF Order Request" class="btn btn-info fency-incoming" target="_blank"><i class="fa fa-file-text-o"></i></a>
                  </td>
				  <?php } ?>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="11"><?php echo $text_no_results; ?></td>
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
  
              <style type="text/css"> 
                   @import "http://fonts.googleapis.com/css?family=Droid+Sans";
                                #maindiv{
                width:960px;
                margin:10px auto;
                padding:10px;
                font-family:'Droid Sans',sans-serif
                }
                #formdiv{
                width:500px;
                float:left;
                text-align:center
                }
                
                .upload{
                background-color:red;
                border:1px solid red;
                color:#fff;
                border-radius:5px;
                padding:10px;
                text-shadow:1px 1px 0 green;
                box-shadow:2px 2px 15px rgba(0,0,0,.75)
                }
                .upload:hover{
                cursor:pointer;
                background:#c20b0b;
                border:1px solid #c20b0b;
                box-shadow:0 0 5px rgba(0,0,0,.75)
                }
                #file{
                color:green;
                padding:5px;
                border:1px dashed #123456;
                background-color:#f9ffe5
                }
                #upload{
                margin-left:45px
                }
                #noerror{
                color:green;
                text-align:left
                }
                #error{
                color:red;
                text-align:left
                }
                #img{
                width:17px;
                border:none;
                height:17px;
                margin-left:-20px;
                margin-bottom:91px
                }
                .abcd{
                text-align:center
                }
                .abcd img{
                height:100px;
                width:100px;
                padding:5px;
                border:1px solid #e8debd
                }
				.btn-grey {
					color: #fff;
					background-color: #808080;
					border-color: #808080;
				}
				
				.btn-grey:hover {
					color: #fff;
					background-color: #696969;
					border-color: #696969;
				}

            </style>         
<script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	$('#button-filter').html('<i class="fa fa-spinner fa-spin"></i> Please Wait...');
	url = 'index.php?route=sale/incoming&token=<?php echo $token; ?>';

	var filter_order_id = $('input[name=\'filter_order_id\']').val();

	if (filter_order_id) {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}

	var filter_model = $('input[name=\'filter_model\']').val();

	if (filter_model) {
		url += '&filter_model=' + encodeURIComponent(filter_model);
	}

  var manArray = [];
	var filter_m_id = $("#filter_man_id input:checked").each(function() {
		manArray.push($(this).val());
	});
	var mselected;
	mselected = manArray.join(',') ;
	if (mselected.length > 0){
		url += '&filter_manufacturer=' + encodeURIComponent(mselected);
	}

	/* var filter_customer = $('input[name=\'filter_customer\']').val();

	if (filter_customer) {
		url += '&filter_customer=' + encodeURIComponent(filter_customer);
	} */
	
	/* var filter_pos = $('select[name=\'filter_pos\']').val();

	if (filter_pos != 0) {
		url += '&filter_pos=' + encodeURIComponent(filter_pos);
	} */

	var filter_order_status = $('select[name=\'filter_order_status\']').val();

	if (filter_order_status != '*') {
		url += '&filter_order_status=' + encodeURIComponent(filter_order_status);
	}

	/* var filter_total = $('input[name=\'filter_total\']').val();

	if (filter_total) {
		url += '&filter_total=' + encodeURIComponent(filter_total);
	} */

	var filter_date_added = $('input[name=\'filter_date_added\']').val();

	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}

	var filter_date_modified = $('input[name=\'filter_date_modified\']').val();

	if (filter_date_modified) {
		url += '&filter_date_modified=' + encodeURIComponent(filter_date_modified);
	}
	
	var filter_record_limit = $('select[name=\'filter_record_limit\']').val();
		if (filter_record_limit) {
			url += '&filter_record_limit=' + encodeURIComponent(filter_record_limit);
		}

	//location = url;
	$('#tab_incoming_orders').load(url);  
});
//--></script>
  <script type="text/javascript"><!--
$('input[name=\'filter_customer\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=customer/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['customer_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_customer\']').val(item['label']);
	}
});
//--></script>
  <script type="text/javascript"><!--
$('input[name^=\'selected\']').on('change', function() {
	$('#button-shipping, #button-invoice').prop('disabled', true);

	var selected = $('input[name^=\'selected\']:checked');

	if (selected.length) {
		$('#button-invoice').prop('disabled', false);
	}

	for (i = 0; i < selected.length; i++) {
		if ($(selected[i]).parent().find('input[name^=\'shipping_code\']').val()) {
			$('#button-shipping').prop('disabled', false);

			break;
		}
	}
});

$('input[name^=\'selected\']:first').trigger('change');

// Login to the API
var token = '';

$.ajax({
	url: '<?php echo $store; ?>index.php?route=api/login',
	type: 'post',
	data: 'key=<?php echo $api_key; ?>',
	dataType: 'json',
	crossDomain: true,
	success: function(json) {
        $('.alert').remove();

        if (json['error']) {
    		if (json['error']['key']) {
    			$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['key'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
    		}

            if (json['error']['ip']) {
    			$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['ip'] + ' <button type="button" id="button-ip-add" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-danger btn-xs pull-right"><i class="fa fa-plus"></i> <?php echo $button_ip_add; ?></button></div>');
    		}
        }

		if (json['token']) {
			token = json['token'];
		}
	},
	error: function(xhr, ajaxOptions, thrownError) {
		alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	}
});

$(document).delegate('#button-ip-add', 'click', function() {
	$.ajax({
		url: 'index.php?route=user/api/addip&token=<?php echo $token; ?>&api_id=<?php echo $api_id; ?>',
		type: 'post',
		data: 'ip=<?php echo $api_ip; ?>',
		dataType: 'json',
		beforeSend: function() {
			$('#button-ip-add').button('loading');
		},
		complete: function() {
			$('#button-ip-add').button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}

			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('button[id^=\'button-delete\']').on('click', function(e) {
	if (confirm('<?php echo $text_confirm; ?>')) {
		var node = this;

		$.ajax({
			url: '<?php echo $store; ?>index.php?route=api/order/delete&token=' + token + '&order_id=' + $(node).val(),
			dataType: 'json',
			crossDomain: true,
			beforeSend: function() {
				$(node).button('loading');
			},
			complete: function() {
				$(node).button('reset');
			},
			success: function(json) {
				$('.alert').remove();

				if (json['error']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				if (json['success']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
});
//--></script>
  <script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
  <link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
     
          

<script type="text/javascript">
function showProducts(order_id) {
	$("#products_" + order_id).toggleClass('hidden');	
}
</script>

<script type="text/javascript"><!--
				$('.backorder-summary').load('index.php?route=sale/backorder/backorder_summary&token=<?php echo $token; ?>&limit=3'); 
				var store_url = '';
				var admintoken = '<?php echo $token; ?>';
				
				$(".table-responsive").mousemove(function(){
					$( ".alert-success" ).slideUp( "slow", function() {
   						$(".alert-success").remove();
					});
				});
				
				$(document).ready(function() {
					$('.invoice_prefix_hidden').hide();
				});
				
				function showProducts(order_id) {
					$("#products_" + order_id).toggleClass('hidden');	
				}
				
				function showBulkStatus() {
					$('#bulk-status-well').toggleClass('hidden');
				}
				
				function inputValue(param, order_id) {
				
					html  = '<div class="' + param + '_edit" id="' + param + order_id + '">';
					html += '<div class="input-group"><input type="text" value="' + $('#' + param + order_id).html() + '" class="form-control">';
					html += '<span class="input-group-btn">';
					html += '<a onclick="saveValue(\'' + param + '\' , ' + order_id + ')" role="button" class="btn  btn-success" data-toggle="tooltip" title="Save" data-container="body"><i class="fa fa-check"></i></a>';
					html += '<a onclick="closeInput(\'' + param + '\', ' + order_id + ')" role="button" class="btn btn-danger" data-toggle="tooltip" title="Cancel" data-container="body"><i class="fa fa-times"></i></a>';
					html += '</div></span></div>';
					
					$('#' + param + order_id).replaceWith(html);
				
				}
				
				function selectStatus(order_id, status,sales_person=0) {
					
					html  = '<div id="status-' + order_id +'" class="">';
					html += '<div class="input-group"><select class="form-control" name="order_status" id="select-status-' + order_id + '" onchange="saveStatus(' + order_id + ');">';
					<?php foreach ($order_statuses as $order_status) { ?>
						if ('<?php echo $order_status['name']; ?>' ==  status) {
							html += '<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>';
						} else {
							html += '<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>';
						}
					<?php } ?>
					
					html += '</select><span class="input-group-btn"><button onclick="closeSelect(' + order_id + ', \'' + status + '\')" class="btn btn-danger" data-toggle="tooltip" title="Cancel" data-container="body"><i class="fa fa-times"></i></button></span></div>';
					html += '<div class="checkbox" style="margin-top:10px;">';
					html += '<div class="form-group"><label class="col-sm-8 control-label" for="notify-' + order_id +'">Notify Customer</label>';
                    html += '<div class="col-sm-4"><input type="checkbox" id="notify-' + order_id +'" name="notify"></div></div>';
					
					html += '<div class="form-group"><label class="col-sm-8 control-label" for="override' + order_id +'"> <span data-toggle="tooltip" title="If the customers order is being blocked from changing the order status due to a anti-fraud extension enable override.">Override</span></label>';
                    html += '<div class="col-sm-4"><input type="checkbox" name="override" value="1" id="override' + order_id +'" /></div></div>';
					
					html += '<button type="button" class="btn btn-sm btn-default" data-toggle="collapse" data-target="#inputComment_' + order_id + '" aria-expanded="false" aria-controls="inputComment' + order_id + '">Add Customer Comment</button><br><br><button type="button" class="btn btn-sm btn-default" data-toggle="collapse" data-target="#dropdownsp_' + order_id + '" aria-expanded="false" aria-controls="dropdownsp_' + order_id + '">Assign Sales Person</button></div>';
					html += '</div>';
					html += '<div class="collapse" id="inputComment_' + order_id + '" style="margin-top:10px;">';
					html += '<hr /><textarea name="comment" id="comment-' + order_id + '" class="form-control" rows="8"></textarea>';
					html += '<div class="form-group"><button type="button" onclick="saveStatus(' + order_id + ')" class="btn btn-success">Add history</button></div></div>';
					html += '<div class="collapse" id="dropdownsp_' + order_id + '" style="margin-top:10px;">';
					html += '<hr /><strong>Sales Person:<strong>';
					html += '<div class="input-group"><select class="form-control" name="sales_person" id="sales-person-' + order_id + '"><option value="*"></option>';
					<?php foreach ($sales_persons as $sp) { ?>
						
						if ('<?php echo $sp['user_id']; ?>' ==  sales_person) {
						html += '<option value="<?php echo $sp['user_id']; ?>" selected="selected"><?php echo $sp['name']; ?></option>';
							
						} else {
						html += '<option value="<?php echo $sp['user_id']; ?>"><?php echo $sp['name']; ?></option>';
						}
                      	<?php } ?>
					
					html += '</select><br><br>';
					html += '<div class="form-group"><button type="button" onclick="saveStatus(' + order_id + ',1)" class="btn btn-success">Assign</button></div></div>';
					
					html += '</div></div>';
					
					
					$("#status-" + order_id).replaceWith(html);
                
				}				
				
				function saveStatus(order_id,sales_person = 0) {
					
					var order_status_id = $("#select-status-" + order_id).val();
					var order_status_name = $("#select-status-" + order_id).find('option:selected').text();
					var notify = ($("#notify-" + order_id).prop('checked') ? 1 : 0);
					var comment = $('#inputComment_' + order_id + ' textarea').val();
					
					if(sales_person == 1)
					{
						sales_person = $("#sales-person-" + order_id).val();
					}
					
					
					if(typeof verifyStatusChange == 'function'){
						if(verifyStatusChange() == false){
						  return false;
						}else{
						  addOrderInfo(order_status_id, order_id);
						}
					  }else{
						addOrderInfo(order_status_id, order_id);
					  }
					
					store_url = $("#store-url" + order_id).val();
					
					$.ajax({
						url: store_url + 'index.php?route=api/order/history&token=' + token + '&order_id=' + order_id,
						url: "<?=HTTPS_CATALOG?>index.php?route=api/order/history&token=" + token + '&order_id=' + order_id,
						type: 'post',
						dataType: 'json',
						data: 'order_status_id=' + encodeURIComponent($("#select-status-" + order_id).val()) + '&sales_person=' + sales_person + '&username=' + encodeURIComponent($('input[name=\'username\']').val()) + '&notify=' + ($("#notify-" + order_id).prop('checked') ? 1 : 0) + '&override=' + ($('#override-' + order_id).prop('checked') ? 1 : 0) + '&append=0' + '&comment=' + encodeURIComponent($('#inputComment_' + order_id + ' textarea').val()),
						success: function(json) {
							closeSelect(order_id, order_status_name, sales_person);
							alertJson('alert alert-success', json);
						},
						error: function(xhr, ajaxOptions, thrownError) {
							//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" +xhr.responseText);
						}
					});
				}
				
				function closeSelect(order_id, order_status_name,sales_person) {
					$("#status-" + order_id).replaceWith('<div class="status-edit" onclick="selectStatus('+ order_id +', \'' + order_status_name + '\' , \'' + sales_person + '\');" id="status-' + order_id + '">' + order_status_name + '</div>');
					$('#inputComment_' + order_id).remove();
					$('#dropdownsp_' + order_id).remove();	
				}
				
				function bulkStatusUpdate() {
					
					var order_status_id = $('#bulk-status-update').val();
					var order_status_name = $('#bulk-status-update').find('option:selected').text();
					var selected = $('input[name^=\'selected\']:checked');
					
					
					for (i = 0; i < selected.length; i++) {
						var order_id = $(selected[i]).parent().find('input[name^=\'selected\']').val()
						if (order_id) {
							bulkSaveStatus(order_id,order_status_id,order_status_name);
						}
					}
					
					$('input[name^=\'selected\']:checked').prop('checked', false).trigger('change');
				}
				
				function AJAXQueue(selected) {
					var order_status_id = $('#bulk-status-update').val();
					var order_status_name = $('#bulk-status-update').find('option:selected').text();
					
					for (i = 0; i < selected.length; i++) {
						var order_id = $(selected[i]).parent().find('input[name^=\'selected\']').val()
						if (order_id) {
							bulkSaveStatus(order_id,order_status_id,order_status_name);
						}
					}
				}
	
				function bulkSaveStatus(order_id,order_status_id,order_status_name) {

					if (typeof verifyStatusChange == 'function') {
						if (verifyStatusChange() == false) {
						  return false;
						} else {
						  addOrderInfo(order_status_id, order_id);
						}
					} else {
						addOrderInfo(order_status_id, order_id);
					}
					
					store_url = $("#store-url" + order_id).val();
					
					$.ajax({
						url: "<?=HTTPS_CATALOG?>index.php?route=api/order/history&token=" + token + '&order_id=' + order_id,
						type: 'post',
						dataType: 'json',
						data: 'order_status_id=' + encodeURIComponent($("#select-status-" + order_id).val()) + '&notify=' + ($("#notify-" + order_id).prop('checked') ? 1 : 0) + '&override=' + ($('#override-' + order_id).prop('checked') ? 1 : 0) + '&append=0' + '&comment=' + encodeURIComponent($('#inputComment_' + order_id + ' textarea').val()),
						beforeSend: function() {
						},
						success: function(json) {
							alertJson('alert alert-success', json);
							changeStatusText(order_id, order_status_name);
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" +xhr.responseText);
						}
					});								
				}
				
				function changeStatusText(order_id, order_status_name) {
					$("#status-" + order_id).replaceWith('<div class="status-edit" onclick="selectStatus('+ order_id +', \'' + order_status_name + '\' );" id="status-' + order_id + '">' + order_status_name + '</div>');
				}
				
				function saveValue(param, order_id) {
					
					var new_value = $('#' + param + order_id + ' input').val();
					
					$.ajax({
					  type: 'post',	
					  url: 'index.php?route=sale/order/saveValue&token=' + admintoken,
					  dataType: 'json',
					  data: { param: param, new_value: new_value , order_id: order_id},
					  beforeSend: function() {
							$('#' + param + order_id).before('<img scr="view/javascript/jquery/jstree/themes/default/throbber.gif">');
						},
					  success: function(json) { 
						 alertJson('alert alert-success', json);
						 closeInput(param, order_id);
					  },
					  error: function(json) { 
						 alertJson('alert alert-danger', json);
						 closeInput(param, order_id);
					  }
					});
					
				}
				
				function addOrderInfo(status_id, order_id){
					  $.ajax({
						url: 'index.php?route=extension/openbay/addorderinfo&token=<?php echo $token; ?>&order_id='+ order_id +'&status_id=' + status_id,
						type: 'post',
						dataType: 'html',
						data: $(".openbay-data").serialize()
					  });
				}

				function createInvoiceNo(order_id) {
				
					$.ajax({
						url: 'index.php?route=sale/order/createinvoiceno&token=' + admintoken +'&order_id='+ order_id +'',
						dataType: 'json',
						beforeSend: function() {
							$('#generate_invoice_' + order_id).button('loading');	
						},
						complete: function() {
							$('#generate_invoice_' + order_id).button('reset');	
						},
						success: function(json) {
							$('.alert-success, .alert-danger').remove();
						
							if (json['error']) {
				
								$('.alert-danger').fadeIn('slow');
							}
			
							if (json['invoice_no']) {
								$('#generate_invoice_' + order_id).fadeOut('slow', function() {
									$('#generate_invoice_' + order_id).fadeOut('slow', function() {
										
									});
									var prefix = $('#original_invoice_prefix_' + order_id).html();
									
									
									$('#generated_invoice_prefix_' + order_id).html('<div class="invoice_prefix_edit" id="invoice_prefix' + order_id + '" onclick="inputValue(\'invoice_prefix\',' + order_id +');">' + prefix + '</div>' );
									$('#generated_invoice_number_' + order_id).html('<div class="invoice_no_edit" id="invoice_no' + order_id + '" onclick="inputValue(\'invoice_no\',' + order_id +');">' + json['invoice_no'].substring(prefix.length) + '</div>' );
									$('#generated_invoice_prefix_' + order_id).show();
									
								});
							}
						}
					});
				}

				function closeInput(param, order_id) {
					
					 html = '<div class="' + param + '_edit" id="' + param + order_id + '" onclick="inputValue(\'' + param + '\',' + order_id +');" style="display:inline;">' +  $('#' + param + order_id +' input').val() + '</div>';
					 $('#' + param + order_id).fadeOut('slow');
					 $('#' + param + order_id).replaceWith(html);
					
				}
			
				function alertJson(action, json) {
					
					$('.alert-success, .alert-danger').remove();
					
					if (json['success']) {
						$('#order-list-main').before('<div class="' + action + '">' + json['success'] + '</div>');
					} else if (json['error']) {
						$('#order-list-main').before('<div class="' + action + '">' + json['error'] + '</div>');
					}
				}
				
				$('#filter-form input').keydown(function(e) {
					if (e.keyCode == 13) {
						filter();
					}
				});
				
				$(function () {
				  $('.input-group-btn').tooltip({container: "body"});
				});
				
				function addCommission(order_id) {
					$.ajax({
						url: 'index.php?route=sale/order/addcommission&token=<?php echo $token; ?>&order_id=' + order_id,
						type: 'post',
						dataType: 'json',
						beforeSend: function() {
							$('#button-commission-add-' + order_id).button('loading');
						},
						complete: function() {
							$('#button-commission-add-' + order_id).button('reset');
						},			
						success: function(json) {
							$('.alert').remove();
						
							if (json['error']) {
								$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
							}
			
							if (json['success']) {
								$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
				
								$('#button-commission-add-' + order_id).replaceWith('<button id="button-commission-remove-' + order_id + '" type="button" class="btn btn-danger btn-xs pull-right" onclick="removeCommission(' + order_id + ');"><i class="fa fa-minus-circle"></i> Remove Commission</button>');
							}
						},			
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				}
			
				function removeCommission(order_id) {
			
					$.ajax({
						url: 'index.php?route=sale/order/removecommission&token=<?php echo $token; ?>&order_id=' + order_id,
						type: 'post',
						dataType: 'json',
						beforeSend: function() {
							$('#button-commission-remove-' + order_id).button('loading');
		
						},
						complete: function() {
							$('#button-commission-remove-' + order_id).button('reset');
						},		
						success: function(json) {
							$('.alert').remove();
						
							if (json['error']) {
								$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
							}
			
							if (json['success']) {
								$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
				
								$('#button-commission-remove-' + order_id).replaceWith('<button id="button-commission-add-' + order_id + '" type="button" class="btn btn-success btn-xs pull-right" onclick="addCommission(' + order_id + ');"><i class="fa fa-minus-circle"></i> Add Commission</button>');
							}
						},			
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				}
				
				$('#install-button').click(function(){
					$.ajax({
						url: 'index.php?route=sale/order/install&token=<?php echo $token; ?>',
						type: 'post',


						dataType: 'json',
						success: function(json) {
							$('#install-iaol').remove();
							alertJson('alert alert-success', json);
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" +xhr.responseText);
						}
					});
				});
				 
				function AJAXsave(param,action) {
					$.ajax({
						url: 'index.php?route=sale/order/ajaxSave&token=<?php echo $token; ?>',
						type: 'post',
						dataType: 'json',
						data: { key: param, action: action},
						success: function(json) {
							alertJson('alert alert-success', json);
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" +xhr.responseText);
						}
					});
				}
				
				function AJAXsaveColor(param,action) {
					$.ajax({
						url: 'index.php?route=sale/order/ajaxSave&token=<?php echo $token; ?>',
						type: 'post',
						dataType: 'json',
						data: { key: param, action: action, color: $('#config-color-store-' + param).val()},
						success: function(json) {
							alertJson('alert alert-success', json);
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" +xhr.responseText);
						}
					});
				}		
//--></script>
      <script type="text/javascript">
			//$('.daterange').daterangepicker(); 
<?php  if (empty($filter_date_modified) ){  echo "$('#input-date-modified').val('');"; } ?>
	
<?php  if (empty($filter_date_added) ){  echo "$('#input-date-added').val('');"; } ?>

$(".fency-incoming").fancybox({
	'transitionIn' : 'elastic',
	'transitionOut' : 'elastic',
	'speedIn' : 500,
	'speedOut' : 400,
	'width': 800,
	'height': 800,
	'autoSize' : false,
	'overlayShow' : false,
	'overlayOpacity' : 0.7,
	'hideOnOverlayClick' : false,
	'hideOnContentClick' : false,
	'closeClick'  : false, // prevents closing when clicking INSIDE fancybox 
	'openEffect'  : 'none',
	'closeEffect' : 'none',
	'helpers'   : { 
		overlay : {closeClick: false} // prevents closing when clicking OUTSIDE fancybox 
	},
	'type' : 'iframe'
});
</script> 
