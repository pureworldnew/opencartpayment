<table class="table table-bordered">
  <thead>
    <tr>
      <td class="text-left">Order ID</td>
      <td class="text-left">Customer</td>
      <td class="text-left">Status</td>
      <td class="text-left">Manufacturer</td>
      <td class="text-left">Model</td>
      <td class="text-left">Quantity</td>
      <td class="text-left">Date Added</td>
      <td class="text-left">Date Modified</td>
      <td class="text-right">Action</td> 
    </tr>
  </thead>
  <tbody>
    <?php if ($backorders) { ?>
    <?php foreach ($backorders as $backorder) { ?>
    <tr>
      <td class="text-left"><?php echo $backorder['order_id']; ?></td>
      <td class="text-left"><?php echo $backorder['customer']; ?></td>
      <td class="text-left col-md-2" style="background-color:#<?php echo $backorder['order_status_color'];?>">
        <div class="status-edit" onclick="selectStatus(<?php echo $backorder['order_id']; ?> , '<?php echo $backorder['status']; ?>', '<?php echo $backorder['sales_person']; ?>');" id="status-<?php echo $backorder['order_id']; ?>"><?php echo $backorder['status']; ?></div>
        <!-- /Order Status -->
      <td class="text-left"><?php foreach($backorder['manufacturers'] as $manuf){ echo $manuf['name'].",";  } ?></td>
      <td class="text-left"><?php echo $backorder['model']; ?></td>
      <td class="text-left"><?php echo $backorder['quantity']; ?></td>
      <td class="text-left"><?php echo $backorder['date_added']; ?></td>
      <td class="text-left"><?php echo $backorder['date_modified']; ?></td>
      <td class="text-right"> 
        <a href="<?php echo $backorder['view']; ?>" data-toggle="tooltip" title="View" class="btn btn-info"><i class="fa fa-eye"></i></a> <a href="<?php echo $backorder['edit']; ?>" data-toggle="tooltip" title="Edit" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
        <a href="<?php echo $backorder['pdfinv_packing']; ?>" data-toggle="tooltip" title="Packing Slip" class="btn btn-warning" target="_blank"><i class="fa fa-file-text-o"></i></a>
        <a href="<?php echo $backorder['link_pdf_order_request']; ?>" data-toggle="tooltip" title="PDF Order Request" class="btn btn-warning" target="_blank"><i class="fa fa-file-text-o"></i></a>
        <a href="<?php echo $backorder['copy']; ?>" data-toggle="tooltip" title="Copy" class="btn btn-warning" onclick="return confirm('Do you really want to copy this order?') ? true : false;"><i class="fa fa-copy"></i></a>
        <button type="button" value="<?php echo $backorder['order_id']; ?>" id="button-delete<?php echo $backorder['order_id']; ?>" data-toggle="tooltip" title="Delete" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>
      </td>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td class="text-center" colspan="9"><?php echo $text_no_results; ?></td>
    </tr>
    <?php } ?>
  </tbody>
</table>
<div class="row">
	<?php if ( empty( $_GET['limit'] ) ) : ?>
  	<div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
  	<div class="col-sm-6 text-right"><?php echo $results; ?></div>
	<?php endif; ?>
</div>

<script type="text/javascript"><!--
				
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
