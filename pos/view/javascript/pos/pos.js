
// add for browse begin
var browseQ = [];
// add for browse end
var product_add_option = [];

// add for Cash type begin
var useCashType = false;
// add for Cash type end
var org_customer_id = 0;

var display_locked_orders = 0;
var timezone_offset = 'null';	// in minutes
// when switch customers between with addresses and without addressed, the customer_address variable will be updated and will lost zones info
// use this variable to store all possible zones and will get updated when "saveCustomer" before the customer_addresses is updated
var saved_zones = zones;

var serverReachable = true;
var shortcuts = [];

// save ajax call to localstorage to serve offline mode
function hasLocalStorage() {
	var mod = 'pos';
	try {
		localStorage.setItem(mod, mod);
		localStorage.removeItem(mod);
		return true;
	} catch(e) {
	  return false;
	}
}

$.ajaxPrefilter( function( options, originalOptions, jqXHR ) {
  
  // Cache it ?
  if ( !hasLocalStorage() || (options.localCache != null && !options.localCache) ) return;

  var hourstl = options.cacheTTL || 8760;	// valid for 1 year by default

  var cacheKey = options.cacheKey || 
                 options.url.replace( /jQuery.*/,'' ) + options.type + (options.data || '');
  
  // isCacheValid is a function to validate cache
  if ( options.isCacheValid &&  ! options.isCacheValid() ){
    localStorage.removeItem( cacheKey );
  }
  // if there's a TTL that's expired, flush this item
  var ttl = localStorage.getItem(cacheKey + 'cachettl');
  if ( ttl && ttl < +new Date() ){
    localStorage.removeItem( cacheKey );
    localStorage.removeItem( cacheKey  + 'cachettl' );
    ttl = 'expired';
  }
  
  if (serverReachable && (parseInt(order_id) >= 0 && text_work_mode != '1' || (typeof pos_return_id != 'undefined' && parseInt(pos_return_id) >= 0 || options.url.indexOf('getOrderList') > 0 || options.url.indexOf('module/pos/main') > 0) && text_work_mode == '1')) {
    //If its online, call the cache callback and then call the functional callbacks
    if ( options.success ) {
      options.realsuccess = options.success;
    }  
    options.success = function( data ) {
      var strdata = data;
      if ( this.dataType && this.dataType.indexOf( 'json' ) === 0 ) strdata = JSON.stringify( data );

	  if (options.cacheCallback) {
		  // if cacheCallback is define, call the callback
		  options.cacheCallback( data );
	  } else {
		  // otherwise, save the data to localStorage catching exceptions (possibly QUOTA_EXCEEDED_ERR)
		  try {
			localStorage.setItem( cacheKey, strdata );
		  } catch (e) {
			// Remove any incomplete data that may have been saved before the exception was caught
			localStorage.removeItem( cacheKey );
			localStorage.removeItem( cacheKey + 'cachettl' );
			if ( options.cacheError ) options.cacheError( e, cacheKey, strdata );
		  }
	  }

	  console.log('calling from ajax prefilter while online');
      if ( options.realsuccess ) options.realsuccess( data );
    };

    // store timestamp
    if (!options.cacheCallback && (! ttl || ttl === 'expired') ) {
      localStorage.setItem( cacheKey  + 'cachettl', +new Date() + 1000 * 60 * 60 * hourstl );
    }
  } else {
	// is offline or in the middle of processing the local order
  
	// call the beforeSend before process is either online or offline
	if (options.beforeSend) {
		options.beforeSend();
	}
	
	if (options.cachePreDone) {
		console.log('calling cachePreDone with key ' + cacheKey);
		options.cachePreDone(cacheKey, options.success);
	} else {
		var value = localStorage.getItem( cacheKey );
		console.log('calling function with key ' + cacheKey + ' and value ');
		console.log(value);
		if ( !value ){
			value = [];
		} else {
			//In the cache? So get it, apply success callback & abort the XHR request
			// parse back to JSON if we can.
			if ( options.dataType.indexOf( 'json' ) === 0 ) value = JSON.parse( value );
		}
		options.success( value );
	}
    // Abort is broken on JQ 1.5 :(
    jqXHR.abort();
  }

  // call complete after everything is done
  if (options.complete) {
	options.complete();
  }
});



function openFancybox(selector, type, content, onCloseFn, reOpenFn) {
	var minWidth = 260, maxWidth = 1100;
	if (type == 'narrower') { minWidth = 220; maxWidth = 220; }
	else if (type == 'narrow') { maxWidth = 320; }
	else if (type == 'normal') { maxWidth = 450; }
	else if (type == 'wide') { maxWidth = 760; }
	else if (type == 'wider') { maxWidth = 1100; }
	 var helper='';
	if(selector == "#order_payments_dialog"){
		helper={
			overlay : {closeClick: false} // prevents closing when clicking OUTSIDE fancybox
		};
	}else{
		helper={
			overlay : {closeClick: true} // prevents closing when clicking OUTSIDE fancybox
		};
		if(selector == "#customer_dialog"){
			maxWidth = 810;
		}
		/// Comment by waqar 
		$(document).mouseup(function (e)
		                    {
		  var container = $(".fancybox-wrap"); // YOUR CONTAINER SELECTOR
		  
		  if (!container.is(e.target) // if the target of the click isn't the container...
		      && container.has(e.target).length === 0) // ... nor a descendant of the container
		  {

		    container.hide();
		    $(".fancybox-overlay").hide();
		  }
		});
		/// Comment by waqar
	}


	var data = {
		padding : 0,
		margin  : 10,
		width	: '95%',
		height    : 'auto',
		minWidth  : minWidth,
		maxWidth  : maxWidth,
		minHeight  : '300px',
		autoSize	: false,
		fitToView	: true,
		closeClick : false,
		modal: true,
		openEffect: 'none',
		closeEffect: 'none',
		closeClick  : true, // prevents closing when clicking INSIDE fancybox
	    
	    afterShow : function() {
			$('.fancybox-skin').append('<a title="Close" class="fancybox-item fancybox-close" onclick="' + (reOpenFn ? reOpenFn : '$.fancybox.close') + '();"></a>');
			
		},
		beforeShow: function(){
			$(window).on({
				'resize.fancybox' : function(){
					$.fancybox.update();
				}
			});
		},
		afterClose: function(){
			$(window).off('resize.fancybox');
		},
		
	};


	if (selector) {
		data['href'] = selector;
	} else {
		data['content'] = content;
	}
	if (onCloseFn) {
		data['afterClose'] = onCloseFn;
	}

	$.fancybox(data);
};

function closeFancybox() {
	$.fancybox.close();
};

function resizeFancybox() {
	$.fancybox.update();
};
$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

  var target = $(e.target).attr("href") // activated tab
  
  if(target=="#tab_incoming_orders" || target=="#tab_returns" || target=="#tab_backorders" ){
  	$(".right-container").hide();
  	$("#updateStockContainer").hide();
	$(".control-area").show();
  }else if(target=="#tab_update_stock"){
  	$(".cart-container").hide();
	$("#updateStockContainer").show();
	$("input[name=filter_product]").hide();
  	$("input[name=filter_product_for_edit]").hide();
  	$("input[name=filter_product_sales_report]").hide();
	$("input[name=filter_product_update_stock]").show();
	$(".control-area").hide();
  	$(".right-container").show();
	$("#search").val("");
	$("#scancode_product_for_edit").hide();
	$("#scancode_product_update_stock").show();
	$("#scancode_product_sales_report").hide();
	$("#scancode").hide();
	$(".product-box-outer").html("");
  }else if( target == "#tab_product_information" ){
	$("input[name=filter_product]").hide();
	$("input[name=filter_product_for_edit]").show();
  	$("input[name=filter_product_update_stock]").hide();
	$("input[name=filter_product_sales_report]").hide();
	$("#scancode_product_for_edit").show();
	$("#scancode_product_update_stock").hide();
	$("#scancode_product_sales_report").hide();
	$("#scancode").hide();
	$("#updateStockContainer").hide();
	$(".control-area").hide();
  	$(".cart-container").hide();
  	$(".right-container").show();
	$(".search_products").val("");
	$(".product-box-outer").html("");
  } else if( target == "#tab_sales_report" ){
	$("input[name=filter_product]").hide();
	$("input[name=filter_product_for_edit]").hide();
	$("input[name=filter_product_update_stock]").hide();
	$("input[name=filter_product_sales_report]").show();
	$("#scancode_product_for_edit").hide();
	$("#scancode_product_update_stock").hide();
	$("#scancode_product_sales_report").show();
	$("#scancode").hide();
	$("#updateStockContainer").hide();
	$(".cart-container").hide();
	$(".control-area").hide();
	$(".right-container").show();
	$(".search_products").val("");
	$(".product-box-outer").html("");
 }else{
  	$(".right-container").show();
  	$("#updateStockContainer").hide();
	$(".cart-container").show();
	$(".control-area").show();
  }
});
function getOrderList(data) {
	var url = 'index.php?route=module/pos/getOrderList&token=' + token;
	// add for Quotation begin
	url += '&work_mode=' + text_work_mode;
	// add for Quotation end
	if (data) {
		data['display_locked_orders'] = display_locked_orders;
	} else {
		data = {'display_locked_orders':display_locked_orders};
	}
	
	if (timezone_offset == 'null') {
		// need timezone offset
		data['timezone_offset'] = 1;
		data['browser_time'] = parseInt((new Date()).getTime() / 1000);
	}
	$.ajax({
		url: url,
		type: 'post',
		dataType: 'json',
		data: data,
		beforeSend: function() {
			if ($('#order_list_dialog').is(':visible')) {
				var width = $('#button_filter').width();
				$('#button_filter').closest('td').append('<div style="width:' + width + 'px;"><i class="fa fa-spinner fa-spin"></i></div>');
				$('#button_filter').hide();
			} else {
				openWaitDialog(text_fetching_orders);
			}
		},
		complete: function() {
			if ($('#order_list_dialog').is(':visible')) {
				$('#button_filter').closest('td').find('div').remove();
				$('#button_filter').show();
			}
			closeWaitDialog();
		},
		cacheCallback: function() {
			// do nothing to make sure the server order list will not be saved the local order list
		},
		cachePreDone: function(cacheKey, callback) {
			backendGetOrderList(data, callback);
		},
		success: function(json) {
			if (json['timezone_offset']) {
				timezone_offset = parseInt(json['timezone_offset']);
				localStorage.setItem('timezone_offset', '' + timezone_offset);
			}
			if (!$('#order_list_dialog').is(':visible')) {
				openFancybox('#order_list_dialog', 'wider');
			}
			renderOrderList(json);
		}
	});
};

function renderOrderList(json) {
	// render the order list
	var html = '';
	if (json['orders'] && json['orders'].length > 0) {
		var orderRow = 1;
		for (var i in json['orders']) {
			var rowClass = 'odd';
			if (orderRow % 2 == 0) { rowClass = 'even'; }
			
			var bgcolor = '';
			if (!json['orders'][i]['user_id'] || json['orders'][i]['user_id'] == '-1') {
				bgcolor = ' style="background-color: #FFAA00;"';
			}
			
			var order_in_lock = false;
			if (order_locking_status) {
				for (var locking_status_id in order_locking_status) {
					if (parseInt(json['orders'][i]['status_id']) == parseInt(locking_status_id) && parseInt(display_lock)) {
						order_in_lock = true;
						break;
					}
				}
			}

			html += '<tr class="' + rowClass + '">';
			html += '<td class="one checkbox-item"><label class="radio_check"><input type="checkbox" name="order_selected[]" value="' + json['orders'][i]['order_id'] + '" /> <span class="skip_content">Select</span></label></td>';
			html += '<td class="two"' + bgcolor + '><span class="skip_content label">' + column_order_id + ':</span><span class="txt">' + json['orders'][i]['order_id'] + '</span>' + (order_in_lock ? '<span class="icon-lock"></span>' : '') + '</td>';
			if (typeof config['enable_table_management'] != 'undefined' && parseInt(config['enable_table_management'])) {
				html += '<td class="three"' + bgcolor + '><span class="skip_content label">' + column_table_id + ':</span>' + json['orders'][i]['table_name'] + '</td>';
			}
			html += '<td class="four"' + bgcolor + '><span class="skip_content label">' + column_customer + ':</span>' + json['orders'][i]['customer'] + '</td>';
			html += '<td class="five"' + bgcolor + '><span class="skip_content label">' + column_status + ':</span>' + json['orders'][i]['status'] + '</td>';
			html += '<td class="six"' + bgcolor + '><span class="skip_content label">' + column_order_total + ':</span>' + json['orders'][i]['total'] + '</td>';
			html += '<td class="seven"' + bgcolor + '><span class="skip_content label">' + column_date_added + ':</span>' + offsetDate(json['orders'][i]['date_added']) + '</td>';
			html += '<td class="eight"' + bgcolor + '><span class="skip_content label">' + column_date_modified + ':</span>' + offsetDate(json['orders'][i]['date_modified']) + '</td>';
			html += '<td class="eight"' + bgcolor + '><span class="skip_content label">Sales Person:</span>' + ((json['orders'][i]['salesperson'] ==null)? "N/A": json['orders'][i]['salesperson']) + '</td>';
			if (order_in_lock) {
				html += '<td class="nine"' + bgcolor + '>';
				html += '<a onclick="printReceipt(' + json['orders'][i]['order_id'] + ');" class="table-btn table-btn-print"><span class="icon print"></span></a>';
				html += '<a onclick="emailReceipt(' + json['orders'][i]['order_id'] + ', \'' + json['orders'][i]['email'] + '\');" class="table-btn table-btn-print table-btn-mail"><span class="icon print mail"></a>';
				html += '</td>';
			} else {
				html += '<td class="nine"' + bgcolor + '><a onclick="selectOrderOld(this, ' + json['orders'][i]['order_id'] + ');" class="table-btn"><span class="icon select"></span> ' + text_select + '</a></td>';
			}
			html += '</tr>';
			
			orderRow++;
		}
	} else {
		if (typeof config['enable_table_management'] != 'undefined' && parseInt(config['enable_table_management'])) {
			html += '<tr><td align="center" colspan="9">' + text_no_results + '</td></tr>';
		} else {
			html += '<tr><td align="center" colspan="8">' + text_no_results + '</td></tr>';
		}
	}
	$('#order_list_orders').html(html);
	$('#order_list_pagination').html(json['pagination']);
	resizeFancybox();
};

function getReturnList(data) {
	var url = 'index.php?route=module/pos/getReturnList&token=' + token + '&work_mode=' + text_work_mode;
	
	if (!data) {
		data = {};
	}
	if (timezone_offset == 'null') {
		// need timezone offset
		data['timezone_offset'] = 1;
		data['browser_time'] = parseInt((new Date()).getTime() / 1000);
	}
	$.ajax({
		url: url,
		type: 'post',
		dataType: 'json',
		data: data,
		beforeSend: function() {
			if ($('#return_list_dialog').is(':visible')) {
				var width = $('#button_return_filter').width();
				$('#button_return_filter').closest('td').append('<div style="width:' + width + 'px;"><i class="fa fa-spinner fa-spin"></i></div>');
				$('#button_return_filter').hide();
			} else {
				openWaitDialog(text_fetching_returns);
			}
		},
		complete: function() {
			if ($('#return_list_dialog').is(':visible')) {
				$('#button_return_filter').closest('td').find('div').remove();
				$('#button_return_filter').show();
			}
			closeWaitDialog();
		},
		cacheCallback: function() {
			// do nothing to make sure the server return list will not be saved to the local return list
		},
		cachePreDone: function(cacheKey, callback) {
			backendGetReturnList(data, callback);
		},
		success: function(json) {
			if (json['timezone_offset']) {
				timezone_offset = parseInt(json['timezone_offset']);
				localStorage.setItem('timezone_offset', '' + timezone_offset);
			}
			renderReturnList(json);
			if (!$('#return_list_dialog').is(':visible')) {
				openFancybox('#return_list_dialog', 'wider');
			}
		}
	});
};

function renderReturnList(json) {
	// render the return list
	var html = '';
	if (json['returns']) {
		var trClass = 'even';
		for (var i in json['returns']) {
			trClass = (trClass == 'even') ? 'odd' : 'even';
			html += '<tr class="' + trClass + '">';
			html += '<td class="one checkbox-item"><label class="radio_check"><input type="checkbox" name="return_selected[]" value="' + json['returns'][i]['pos_return_id'] + '" /><span class="skip_content">Select</span></label></td>';
			html += '<td class="two"><span class="skip_content label">' + column_pos_return_id + ':</span><span class="txt">' + json['returns'][i]['pos_return_id'] + '</span></td>';
			html += '<td class="four"><span class="skip_content label">' + column_customer + ':</span>' + json['returns'][i]['customer'] + '</td>';
			html += '<td class="five"><span class="skip_content label">' + column_status + ':</span>' + json['returns'][i]['status'] + '</td>';
			html += '<td class="six"><span class="skip_content label">' + column_return_total + ':</span>' + json['returns'][i]['total'] + '</td>';
			html += '<td class="seven"><span class="skip_content label">' + column_date_added + ':</span>' + offsetDate(json['returns'][i]['date_added']) + '</td>';
			html += '<td class="eight"><span class="skip_content label">' + column_date_modified + ':</span>' + offsetDate(json['returns'][i]['date_modified']) + '</td>';
			html += '<td class="nine"><a onclick="selectReturn(this, ' + json['returns'][i]['pos_return_id'] + ');" class="table-btn"><span class="icon select"></span> ' + text_select + '</a></td>';
			html += '</tr>';
		}
	} else {
		html += '<tr><td class="text-center" colspan="8">' + text_no_results + '</td></tr>';
	}
	$('#return_list_returns').html(html);
	$('#return_list_pagination').html(json['pagination']);
	resizeFancybox();
};




function getReturnListTab(user_id) {
	var url = 'index.php?route=module/pos/getReturnListTab&token=' + token + '&work_mode=' + text_work_mode;
	data={};
	if (!data) {
		data = {};
	}
	if (timezone_offset == 'null') {
		// need timezone offset
		data['timezone_offset'] = 1;
		data['browser_time'] = parseInt((new Date()).getTime() / 1000);
	}
	data['filter_user_id']=user_id;
	$.ajax({
		url: url,
		type: 'post',
		dataType: 'json',
		data: data,
		beforeSend: function() {
			if ($('#return_list_dialog').is(':visible')) {
				var width = $('#button_return_filter').width();
				$('#button_return_filter').closest('td').append('<div style="width:' + width + 'px;"><i class="fa fa-spinner fa-spin"></i></div>');
				$('#button_return_filter').hide();
			} else {
				openWaitDialog(text_fetching_returns);
			}
		},
		complete: function() {
			if ($('#return_list_dialog').is(':visible')) {
				$('#button_return_filter').closest('td').find('div').remove();
				$('#button_return_filter').show();
			}
			closeWaitDialog();
		},
		cacheCallback: function() {
			// do nothing to make sure the server return list will not be saved to the local return list
		},
		cachePreDone: function(cacheKey, callback) {
			backendGetReturnList(data, callback);
		},
		success: function(json) {
			if (json['timezone_offset']) {
				timezone_offset = parseInt(json['timezone_offset']);
				localStorage.setItem('timezone_offset', '' + timezone_offset);
			}
			renderReturnListTab(json);
			if (!$('#return_list_dialog').is(':visible')) {
			//	openFancybox('#return_list_dialog', 'wider');
			}
		}
	});
};

function renderReturnListTab(json) {
	// render the return list
	var html = '';
	if (json['returns']) {
		var trClass = 'even';
		for (var i in json['returns']) {
			trClass = (trClass == 'even') ? 'odd' : 'even';
			html += '<tr class="' + trClass + '">';
			html += '<td class="two">' + json['returns'][i]['product_id'] + '</td>';
			html += '<td class="four"><span class="txt">' + json['returns'][i]['name'] + '</span></td>';
			
			html += '<td class="five">' + json['returns'][i]['model'] + '</td>';
			html += '<td class="six">' + json['returns'][i]['quantity'] + '</td>';
			html += '<td class="seven">' + json['returns'][i]['order_product_id'] + '</td>';
			html += '<td class="eight">' + json['returns'][i]['return_quantity'] + '</td>';
			html += '</tr>';
		}
	} else {
		html += '<tr><td class="text-center" colspan="6">' + text_no_results + '</td></tr>';
	}
	$('#return_list_returns_tabs').html(html);
	$('#return_list_pagination').html(json['pagination']);
	resizeFancybox();
};

function selectOrderPage(page) {
	filter(page);
};

function filter(page) {
	var data = {};
	var filter_table_id = $('select[name=table_list]').val();
	if (filter_table_id) {
		data['filter_table_id'] = filter_table_id;
	}
	var filter_order_id = $('input[name=\'filter_order_id\']').val();
	if (filter_order_id) {
		data['filter_order_id'] = filter_order_id;
	}
	var filter_customer = $('input[name=\'filter_customer\']').val();
	if (filter_customer) {
		data['filter_customer'] = filter_customer;
	}
	// add for Quotation begin
	if (text_work_mode == '2') {
		var filter_quote_status_id = $('select[name=\'filter_quote_status_id\']').val();
		if (filter_quote_status_id != '*') {
			data['filter_quote_status_id'] = filter_quote_status_id;
		}
	} else {
	// add for Quotation end
	var filter_order_status_id = $('select[name=\'filter_order_status_id\']').val();
	if (filter_order_status_id != '*') {
		data['filter_order_status_id'] = filter_order_status_id;
	}
	// add for Quotation begin
	}
	var filter_order_saleper_id = $('select[name=\'filter_order_saleper_id\']').val();
	if (filter_order_saleper_id != '*') {
		data['filter_order_saleper_id'] = filter_order_saleper_id;
	}
	// add for Quotation end
	var filter_total = $('input[name=\'filter_total\']').val();
	if (filter_total != '') {
		data['filter_total'] = filter_total;
	}	
	var filter_date_added = $('input[name=\'filter_date_added\']').val();
	if (filter_date_added) {
		data['filter_date_added'] = filter_date_added;
	}
	var filter_date_modified = $('input[name=\'filter_date_modified\']').val();
	if (filter_date_modified) {
		data['filter_date_modified'] = filter_date_modified;
	}
	if (page) {
		data['page'] = page;
	}
	
	getOrderList(data);
};

function selectReturnPage(page) {
	filterReturn(page);
};

function filterReturn(page) {
	var data = {};
	var filter_pos_return_id = $('input[name=\'filter_pos_return_id\']').val();
	if (filter_pos_return_id) {
		data['filter_pos_return_id'] = filter_pos_return_id;
	}
	var filter_return_customer = $('input[name=\'filter_return_customer\']').val();
	if (filter_return_customer) {
		data['filter_return_customer'] = filter_return_customer;
	}
	var filter_return_status_id = $('select[name=\'filter_return_status_id\']').val();
	if (filter_return_status_id != '*') {
		data['filter_return_status_id'] = filter_return_status_id;
	}
	var filter_return_total = $('input[name=\'filter_return_total\']').val();
	if (filter_return_total != '') {
		data['filter_return_total'] = filter_return_total;
	}	
	var filter_return_date_added = $('input[name=\'filter_return_date_added\']').val();
	if (filter_return_date_added) {
		data['filter_return_date_added'] = filter_return_date_added;
	}
	var filter_return_date_modified = $('input[name=\'filter_return_date_modified\']').val();
	if (filter_return_date_modified) {
		data['filter_return_date_modified'] = filter_return_date_modified;
	}
	if (page) {
		data['page'] = page;
	}
	
	getReturnList(data);
};

function selectOrderOld(anchor, select_order_id) 
{
	order_id = select_order_id;
	window.location.href = 'index.php?route=module/pos/main&token=' + token + '&order_id=' + order_id;
}

function selectOrder(anchor, select_order_id) {
	// refresh the page using the current order_id
	order_id = select_order_id;
	//window.location.href = 'index.php?route=module/pos/main&token=' + token + '&order_id=' + order_id; 
	var td = 0;
	var tdhtml = 0;
	if (anchor) {
		td = $(anchor).closest('td');
		tdhtml = td.html();
	}
	$('.SubtotalTr').hide();
	$('.shippingTr').hide();	
	var url = 'index.php?route=module/pos/main&token=' + token + '&order_id=' + order_id + '&ajax=1&work_mode=' + text_work_mode;
	$.ajax({
		url: url,
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			if (td) {
				td.html('<div><i class="fa fa-spinner fa-spin"></i> ' + text_load_order + '</div>');
			}
		},
		complete: function() {
			if (td) {
				td.find('div').remove();
				td.html(tdhtml);
			}
		},
		cacheCallback: function() {
		},
		cacheKey: CACHE_CURRENT_ORDER,
		cachePreDone: function(cacheKey, callback) {
			backendSelectOrder(order_id, callback);
		},
		success: function(json) {
			removeMessage();
			// update the values of all page element and javascript variables
			for (var name in json) {
				if ($("input[name='" + name + "']").length) {
					if ($("input[name='" + name + "']").is(':radio')) {
						$("input[name='" + name + "'][value='" + json[name] + "']").prop('checked', 1);
					} else {
						// do not set the radio value as it has multiple values
						$("input[name='" + name + "']").val(json[name]);
					}
					
					if ($("input[name='" + name + "']").is(':checkbox')) {
						if (parseInt(json[name])) {
							$("input[name='" + name + "']").prop('checked', 1);
						} else {
							$("input[name='" + name + "']").prop('checked', 0);
						}
					}
				} else if ($("select[name='" + name + "']").length) {
					$("select[name='" + name + "']").val(json[name]);
					$("select[name='" + name + "']").trigger('change');
				} else if ($("span[id='" + name + "']").length) {
					$("span[id='" + name + "']").text(json[name]);
				}
				if (name != 'user_id' && name != 'store_url') {
					// selecting the order should not change the user id
					window[name] = json[name];
				}
			}
			
			if (text_work_mode == '0' || text_work_mode == '2') {
				refreshPageForOrder(json);
			} else if (text_work_mode == '1') {
				refreshPageForReturn(json);
			}
			
			closeFancybox();
		}
	});
};

function selectReturn(anchor, select_pos_return_id) {
	// refresh the page using the current pos_return_id
	pos_return_id = select_pos_return_id;
	var td = 0;
	var tdhtml = 0;
	if (anchor) {
		td = $(anchor).closest('td');
		tdhtml = td.html();
	}
	$('.SubtotalTr').hide();
	$('.shippingTr').hide();
	var url = 'index.php?route=module/pos/main&token=' + token + '&pos_return_id=' + pos_return_id + '&ajax=1' + '&work_mode=' + text_work_mode;
	$.ajax({
		url: url,
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			if (td) {
				td.html('<div><i class="fa fa-spinner fa-spin"></i> ' + text_load_order + '</div>');
			}
		},
		complete: function() {
			if (td) {
				td.find('div').remove();
				td.html(tdhtml);
			}
		},
		cacheCallback: function() {
		},
		cacheKey: CACHE_CURRENT_RETURN,
		cachePreDone: function(cacheKey, callback) {
			backendSelectReturn(pos_return_id, callback);
		},
		success: function(json) {
			removeMessage();
			// update the values of all page element and javascript variables
			for (var name in json) {
				if ($("input[name='" + name + "']").length) {
					if ($("input[name='" + name + "']").is(':radio')) {
						$("input[name='" + name + "'][value='" + json[name] + "']").prop('checked', 1);
					} else {
						// do not set the radio value as it has multiple values
						$("input[name='" + name + "']").val(json[name]);
					}
					
					if ($("input[name='" + name + "']").is(':checkbox')) {
						if (parseInt(json[name])) {
							$("input[name='" + name + "']").prop('checked', 1);
						} else {
							$("input[name='" + name + "']").prop('checked', 0);
						}
					}
				} else if ($("select[name='" + name + "']").length) {
					$("select[name='" + name + "']").val(json[name]);
					$("select[name='" + name + "']").trigger('change');
				} else if ($("span[id='" + name + "']").length) {
					$("span[id='" + name + "']").text(json[name]);
				}
				window[name] = json[name];
			}
			if (!parseInt(select_pos_return_id)) {
				// save to empty return info to be used later in the offline mode
				localStorage.setItem(CACHE_EMPTY_RETURN, JSON.stringify(json));
			}
			
			refreshPageForReturn(json);
			
			closeFancybox();
		}
	});
};

function refreshPageForReturn(json) {
	// replace general info of order with general info of return
	$('#order_id_text').text(json['text_pos_return_id']);
	$('#order_status_name').text(json['return_status']);
	// hide the shipping method
	$('#shipping_method').closest('li').hide();
	// hide the order comment
	$('#order_comment').closest('li').hide();
	// hide the shortcuts
	$('.left-container .shortcuts').hide();
	$('#order_customer_name').text(json['customer']);
	$('#order_id_text').closest('a').attr('onclick', 'getReturnList();');
	$('#order_status_name').closest('a').attr('onclick', 'changeReturnStatus();');
	// hide the non-catalog button
	$('.non-catalog').hide();

	// also hide some of the order processing buttons
	$('#order_only_buttons').hide();
	$('.pay-btn span').text(text_pay);
	
	// display order for return info
	if (json['order_id'] && parseInt(json['order_id'])) {
		$('#add_product_control').hide();
		$('#browse_category_div').hide();
		$('#return_control').html(json['text_return_for_order'] + '&nbsp;(<a onclick="showExistingReturns();">' + text_existing_returns + '</a>)');
		$('#return_control').show();
		$('#show_order_payments_td').show();
		$('#show_order_payments_td a').attr('onclick', 'showPaymentsDetails(' + json['order_id'] + ')');
	} else {
		$('#add_product_control').show();
		$('#return_control').hide();
		$('#browse_category_div').show();
		$('#show_order_payments_td').hide();
	}
	
	// load order products into the browse window
	if (json['browseItems']) {
		populateBrowseTable(json['browseItems'], false, true);
	} else {
		showCategoryItems(0);
	}
	
	// update product list
	updateProducts(json['return_products'], true);
	
	// update the payments
	if (json['return_payments']) {
		updatePayments(json['return_payments']);
	} else {
		updatePayments({});
	}
	
	// clean up the total
	updateTotal(json['totals']);
	
	// save empty return into the local storage
	if (typeof empty_return_info !== 'undefined') {
		localStorage.setItem(CACHE_RETURN_ORDER, JSON.stringify(empty_return_info));
	}
	
	showMessage('success', text_return_ready);
};

function showExistingReturns() {
	if (typeof existing_returns != 'undefined') {
		$('#existing_returns_list').empty();
		html = '';
		var trClass = 'even';
		for (var i in existing_returns) {
			trClass = (trClass == 'even') ? 'odd' : 'even';
			html += '<tr class="' + trClass + '">';
			html += '<td>' + $('<div/>').html(existing_returns[i]['product']).text() + '</td>';
			html += '<td>' + existing_returns[i]['customer'] + '</td>';
			html += '<td>' + existing_returns[i]['email'] + '</td>';
			html += '<td>' + existing_returns[i]['quantity'] + '</td>';
			html += '<td>' + existing_returns[i]['comment'] + '</td>';
			html += '<td>' + offsetDate(existing_returns[i]['date_modified']) + '</td>';
			html += '</tr>'
		}
		$('#existing_returns_list').append(html);
		openFancybox('#existing_returns_dialog', 'wide');
	}
};

function refreshPageForOrder(json) {
	if (text_work_mode == '0') {
		$('#work_mode_dropdown').val('sale');
		$('.pay-btn span').text(text_pay);
	} else {
		$('#work_mode_dropdown').val('quote');
		$('.pay-btn span').text(button_quote_to_order);
	}
	
	// general info of order
	$('#order_id_text').text(json['order_id_text']);
	// show the shipping method
	$('#shipping_method').closest('li').show();
	// show the order comment
	$('#order_comment').closest('li').show();
	// show the shortcuts
	$('.left-container .shortcuts').show();
	$('#customer').text(json['customer']);
	$('#order_id_text').closest('a').attr('onclick', 'getOrderList();');
	$('#order_status_name').closest('a').attr('onclick', 'changeOrderStatus();');
	// hide the non-catalog button
	//$('.non-catalog').show();

	$("#scancode_product_for_edit").hide();
	$("#scancode_product_update_stock").hide();
	$("#scancode_product_sales_report").hide();
	$("#scancode").show();
	$('.onlyone').hide();

	// also hide some of the order processing buttons
	$('#order_only_buttons').show();
	
	// display order for return info
	$('#add_product_control').show();
	$('#return_control').hide();
	$('#browse_category_div').show();
	
	// update order / quote status name
	if (text_work_mode == '0') {
		for (var i in order_statuses) {
			if (order_statuses[i]['order_status_id'] == order_status_id) {
				$('#order_status_name').text(order_statuses[i]['name']);
				break;
			}
		}
	} else if (text_work_mode == '2') {
		for (var i in quote_statuses) {
			if (quote_statuses[i]['quote_status_id'] == quote_status_id) {
				$('#order_status_name').text(quote_statuses[i]['name']);
				break;
			}
		}
	}
	// update table if table is enabled
	var table_display = text_table_not_selected;
	if (tables) {
		for (var i in tables) {
			if (parseInt(tables[i]['table_id']) == parseInt(order_table_id)) {
				table_display = tables[i]['name'];
				break;
			}
		}
	}
	$('#button_table').text(table_display);
	// update shipping method name
	if ($("span[id='shipping_method']").length) {
		$('#shipping_method').text(json['shipping_method']);
	}
	// update shipping variables
	shipping_code = json['shipping_code'];
	shipping_method = json['shipping_method'];
	// update product list
	updateProducts(json['products']);
	
	// update the payments
	if (json['order_payments']) {
		updatePayments(json['order_payments']);
	} else {
		updatePayments({});
	}
	// update change
	$('#payment_change').find('span').text(json['payment_change_text']);
	$('#dialog_change_text').text(json['payment_change_text']);
	
	// update total
	updateTotal(json['totals']);
	
	if (text_work_mode == '0') {
		showMessage('success', text_order_ready);
		
		setOrderNotification(new_order_num);
	} else if (text_work_mode == '2') {
		showMessage('success', text_quote_ready);
	}
};

function updateProducts(orderProducts, forReturn) {
	$('#product').empty();
	
	if (orderProducts) {
		var product_row = 0;
		html = '';
		
		for ( var i in orderProducts) {
			var product = orderProducts[i];
			
			// get the encoded key, for now it is used by "return without order" only
			var addData = {'product_id':product['product_id']};
			
			if (parseInt(product['weight_price']) > 0) {
				addData[product['weight_name']] = product['weight'];
			}
			
			if (product['sns']) {
				for (var j in product['sns']) {
					if (product['sns'][j] && product['sns'][j]['sn']) {
						addData['product_sn'] = product['sns'][j]['sn'];
					}
					break;
				}
			}
			
			var options = [];
			for (var j in product['option']) {
				var option = product['option'][j];
				var option_id = parseInt(option['product_option_id']);
				if (option['type'] == 'checkbox') {
					var product_option_value_id = parseInt(option['product_option_value_id']);
					// checkbox value is an array
					for (var k = 0; k < options.length; k++) {
						if (options[k]['option_id'] == option_id) {
							// found the array element, insert the value in the right position
							for (var l = 0; l < options[k]['value'].length; l++) {
								if (product_option_value_id < options[k]['value'][l]) {
									break;
								}
							}
							// need insert before position l
							options[k]['value'].splice(l, 0, product_option_value_id);
							break;
						}
					}
					if (k == options.length) {
						// no such option_id inserted yet
						options.push({'option_id':option_id, 'value':[product_option_value_id]});
					}
				} else if (option['type'] == 'select' || option['type'] == 'radio' || option['type'] == 'image') {
					options.push({'option_id':option_id, 'value':option['product_option_value_id']});
				} else {
					options.push({'option_id':option_id, 'value':option['value']});
				}
			}
			if (options.length > 0) {
				// sort the array by option_id
				for (var j = 0; j < options.length-1; j++) {
					for (var k = j+1; k < options.length; k++) {
						if (options[k]['option_id'] < options[j]['option_id']) {
							var tmp = options[k];
							options[k] = options[j];
							options[j] = tmp;
						}
					}
				}
				addData['options'] = options;
			}
			encodedKey = base64_encode(JSON.stringify(addData));
			
			html += '<tr id="product-row' + product_row + '" class="' + ((product_row % 2 == 0) ? 'even' : 'odd') + '">';

			if (product['order_id']) {
			html += '	<input type="hidden" name="order_product[' + product_row + '][order_id]" value="' + product['order_id'] + '" />';
			}
			if (forReturn) {
			html += '	<input type="hidden" name="order_product[' + product_row + '][return_id]" value="' + product['return_id'] + '" />';
			}
			html += '	<input type="hidden" name="order_product[' + product_row + '][encodedKey]" value="' + encodedKey + '" />';
			html += '	<input type="hidden" name="order_product[' + product_row + '][order_product_id]" value="' + product['order_product_id'] + '" />';
			html += '	<input type="hidden" name="order_product[' + product_row + '][product_id]" value="' + product['product_id'] + '" />';
			html += '	<input type="hidden" name="order_product[' + product_row + '][quantity]" value="' + product['quantity'] + '" />';
			html += '	<input type="hidden" name="order_product[' + product_row + '][tax_class_id]" value="' + product['tax_class_id'] + '" />';
			html += '	<input type="hidden" name="order_product[' + product_row + '][shipping]" value="' + product['shipping'] + '" />';
			html += '	<input type="hidden" name="order_product[' + product_row + '][subtract]" value="' + product['subtract'] + '" />';
			html += '	<td align="center" valign="middle" class="one"><span class="cart-round-img-outr" onclick="changeQuantity(this,'+product_row+');"><img src="' + product['image'] + '" class="cart-round-img" alt=""><a class="cart-round-qty quantity_anchor_' + product_row + '">' + product['quantity'] + '</a></span></td>';
			html += '	<td align="left" valign="middle" class="two">';
			html += '		<span class="product-name" onclick="' + (forReturn ? 'showReturnDetails(this)' : 'showProductDetails(' + product['product_id'] + ')') + '">'
			html += '			<span class="raw-name" id="order_product[' + product_row + '][order_product_display_name]">' + product['name'] + '</span>';
			for (var j in product['option']) {
				var option = product['option'][j];
			html += '			<br />';
			html += '			&nbsp;<small> - ' + option['name'] + ': ' + option['value'] + '</small>';
			html += '			<input type="hidden" name="order_product[' + product_row + '][order_option][' + option['product_option_id'] + '][product_option_id]" value="' + option['product_option_id'] + '" />';
				if (option['type'] == 'checkbox') {
			html += '			<input type="hidden" name="order_product[' + product_row + '][order_option][' + option['product_option_id'] + '][product_option_value_id][' + option['product_option_value_id'] + ']" value="' + option['value'] + '" />';
				} else {
			html += '			<input type="hidden" name="order_product[' + product_row + '][order_option][' + option['product_option_id'] + '][product_option_value_id]" value="' + option['product_option_value_id'] + '" />';
				}
			html += '			<input type="hidden" name="order_product[' + product_row + '][order_option][' + option['product_option_id'] + '][value]" value="' + option['value'] + '" />';
			html += '			<input type="hidden" name="order_product[' + product_row + '][order_option][' + option['product_option_id'] + '][type]" value="' + option['type'] + '" />';
			}
			if (product['sns']) {
				for (var j in product['sns']) {
					var product_sn = product['sns'][j];
					if (product_sn) {
			html += '			<br />';
			html += '			&nbsp;<small> - SN: ' + product_sn['sn'] + '</small>';
			html += '			<input type="hidden" name="order_product[' + product_row + '][product_sns][' + product_sn['product_sn_id'] + ']" value="' + product_sn['product_sn_id'] + '" />';
					}
				}
			}
			if (parseInt(product['weight_price']) > 0) {
			html += '			<br />';
			html += '			&nbsp;<small> - ' + product['weight_name'] + ': ' + product['weight'] + '</small>';
			}
			html += '			<input type="hidden" name="order_product[' + product_row + '][weight_price]" value="' + product['weight_price'] + '" />';
			html += '			<input type="hidden" name="order_product[' + product_row + '][weight]" value="' + product['weight'] + '" />';
			html += '		</span>';
			html += '		<span class="highlight" onclick="changePrice(this);"><a id="price_anchor_' + product_row + '">@ ' + product['price_text'] + '</a></span>';
			html += '	</td>';
			html += '	<input type="hidden" name="order_product[' + product_row + '][price]" value="' + product['price'] + '" />';
			if (product['tax']) {
			html += '	<input type="hidden" name="order_product[' + product_row + '][tax]" value="' + product['tax'] + '" />';
			}
			if (!forReturn) {
			html += '	<input type="hidden" name="order_product[' + product_row + '][product_discount_type]" value="' + product['discount_type'] + '" />';
			html += '	<input type="hidden" name="order_product[' + product_row + '][product_discount_value]" value="' + product['discount_value'] + '" />';
			}
			html += '	<td align="center" valign="middle" class="three"><a onclick="$(\'#price_anchor_' + product_row + '\').closest(\'span\').trigger(\'click\');" class="cart-link">';
			if (!forReturn && parseInt(product['discount_type'])) {
			html += '		<span class="product-price">';
			html += '			<strike>' + product['text_before_discount'] + '</strike><br />';
			html += '			<small>(' + text_discount + ': ' + product['text_discount'] + ')</small><br />';
			html +=				product['total_text'];
			html += '		</span>';
			html += '		<input type="hidden" name="order_product[' + product_row + '][product_total_text]" value="' + product['text_before_discount'] + '" />';
			} else {
			html += '		<span class="product-price" id="total_text_only-' + product_row + '">' + product['total_text'] + ((text_work_mode == '1' ? '&nbsp;CR' : '')) + '</span>';
			html += '		<input type="hidden" name="order_product[' + product_row + '][product_total_text]" value="' + product['total_text'] + '" />';
			}
			html += '	</a></td>';
			html += '	<td align="center" valign="middle" class="four"><a class="delete" onclick="' + (forReturn ? 'deleteReturnProduct' : 'deleteOrderProduct') + '(this)"></a></td>';
			html += '</tr>';
			
			product_row++;
		}
		
		$('#product').html(html);
	}
};

function updatePayments(order_payments) {
	// remove the existing payments
	$('#payment_list tr:gt(0)').remove();
	
	var html = '';
	var trClass = 'even';
	for ( var i in order_payments) {
		trClass = (trClass == 'even') ? 'odd' : 'even';
		var order_payment = order_payments[i];
		
		html += '<tr id="order_payment_' + order_payment['order_payment_id'] + '" class="' + trClass + '">';
		html += '	<td><span class="skip_content label">' + column_payment_type + ':</span>' + order_payment['payment_type'] + '</td>';
		html += '	<td><span class="skip_content label">' + column_payment_amount + ':</span>' + formatMoney(order_payment['tendered_amount']) + ((text_work_mode == '1') ?  ' CR' : '') + '</td>';
		html += '	<td><span class="skip_content label"><span id="payment_note_text">' + column_payment_note + '</span>:</span>' + order_payment['payment_note'] + '</td>';
		html += '	<td class="action"><a class="table-btn table-btn-delete-2" onclick="deletePayment(this, \'' + order_payment['order_payment_id'] + '\');"><span class="icon"></span>' + button_delete + '</a></td>';
		html += '</tr>';
	}
	
	$('#payment_list').append(html);
};

function deleteOrder(anchor) {

	if ($('#order_list_orders input[type=\'checkbox\']:checked').length == 0) {
		// nothing is selected
		openAlert(text_no_order_selected);
	} else {
		var can_continue = true;
		$('#order_list_orders input[type=\'checkbox\']:checked').each(function() {
			if (parseInt($(this).val()) == parseInt(order_id)) {
				openAlert(text_can_not_delete_current_order);
				can_continue = false;
			}
		});
		if (!can_continue) return false;
		openConfirm(text_confirm_delete_order, function(anchor) {
			var data = '#order_list_orders input[type=\'checkbox\']:checked';
			var url = 'index.php?route=module/pos/deleteOrder&token=' + token + '&work_mode=' + text_work_mode;
			$.ajax({
				url: url,
				type: 'post',
				data: $(data),
				dataType: 'json',
				beforeSend: function() {
					$(anchor).closest('td').append('<div><i class="fa fa-spinner fa-spin"></i> ' + text_wait + '</div>');
					$(anchor).hide();
				},
				complete: function() {
					$(anchor).closest('td').find('div').remove();
					$(anchor).show();
				},
				cacheCallback: function() {
					// do nothing to make sure the new order list returned from deleteOrder function will not be saved
				},
				cachePreDone: function(cacheKey, callback) {
					backendDeleteOrder($(data), callback);
				},
				success: function(json) {
					renderOrderList(json);
					if (json && typeof json['new_order_num'] != 'undefined') {
						setOrderNotification(json['new_order_num']);
					}
				}
			});
		});
	}
};

function deleteEmptyOrders(anchor) {
	var r = confirm("You are about to delete all non-active empty orders, Are you sure?");
	if (r == true) {
		var html = $(anchor).html();
		var data = '';
		var url = 'index.php?route=module/pos/deleteEmptyOrders&token=' + token;
		$.ajax({
			url: url,
			type: 'post',
			data: $(data),
			dataType: 'json',
			beforeSend: function() {
				$(anchor).closest('td').append('<div><i class="fa fa-spinner fa-spin"></i> ' + text_wait + '</div>');
				$(anchor).html('<div><i class="fa fa-spinner fa-spin"></i> ' + text_wait + '</div>');
			},
			complete: function() {
				$(anchor).closest('td').find('div').remove();
				$(anchor).html(html);
			},
			cacheCallback: function() {
				// do nothing to make sure the new order list returned from deleteOrder function will not be saved
			},
			cachePreDone: function(cacheKey, callback) {
			},
			success: function(json) {
				renderOrderList(json);
				if (json && typeof json['new_order_num'] != 'undefined') {
					setOrderNotification(json['new_order_num']);
				}
			}
		});

		getOrderList();
	}
};

function deleteReturn(anchor) {

	if ($('#return_list_returns input[type=\'checkbox\']:checked').length == 0) {
		// nothing is selected
		openAlert(text_no_return_selected);
	} else {
		openConfirm(text_confirm_delete_return, function(anchor) {
			var data = '#return_list_returns input[type=\'checkbox\']:checked';
			var url = 'index.php?route=module/pos/deleteReturn&token=' + token;
			$.ajax({
				url: url,
				type: 'post',
				data: $(data),
				dataType: 'json',
				beforeSend: function() {
					$(anchor).closest('td').append('<div><i class="fa fa-spinner fa-spin"></i> ' + text_wait + '</div>');
					$(anchor).hide();
				},
				complete: function() {
					$(anchor).closest('td').find('div').remove();
					$(anchor).show();
				},
				cacheCallback: function() {
					// do nothing to make sure the new order list returned from deleteOrder function will not be saved
				},
				cachePreDone: function(cacheKey, callback) {
					backendDeleteReturn($(data), callback);
				},
				success: function(json) {
					renderReturnList(json);
				}
			});
		});
	}
};
function populateOrderStatus() {
	$('#order_status_dialog ul').empty();
	
	var html = '';
	if (text_work_mode == '0') {
		$('#order_status_dialog h3').text(text_change_order_status);
		for (var i in order_statuses) {
			if (order_status_id == order_statuses[i]['order_status_id']) {
				html += '<li><a onclick="saveOrderStatus(\'' + order_statuses[i]['order_status_id'] + '\');" class="table-btn-order-status selected"><span class="icon"></span>' + order_statuses[i]['name'] + '</a></li>';
			} else {
				html += '<li><a onclick="saveOrderStatus(\'' + order_statuses[i]['order_status_id'] + '\');" class="table-btn-order-status">' + order_statuses[i]['name'] + '</a></li>';
			}
		}
	} else if (text_work_mode == '2') {
		$('#order_status_dialog h3').text(text_change_quote_status);
		for (var i in quote_statuses) {
			if (quote_status_id == quote_statuses[i]['quote_status_id']) {
				html += '<li><a onclick="saveQuoteStatus(\'' + quote_statuses[i]['quote_status_id'] + '\');" class="table-btn-order-status selected"><span class="icon"></span>' + quote_statuses[i]['name'] + '</a></li>';
			} else {
				html += '<li><a onclick="saveQuoteStatus(\'' + quote_statuses[i]['quote_status_id'] + '\');" class="table-btn-order-status">' + quote_statuses[i]['name'] + '</a></li>';
			}
		}
	}
	
	$('#order_status_dialog ul').append(html);
}
function changeOrderStatus() {
	populateOrderStatus();
	openFancybox('#order_status_dialog', 'wide');
};

function saveOrderStatus(new_order_status_id) {
	var processed = false;	// indicating the save order status has been processed in the syncData function
	
	// before complete or park / void the order, check if this is the local order that was cache before and needs to be synced before further action
	if (serverReachable && (new_order_status_id == parseInt(complete_status_id) || new_order_status_id == parseInt(parking_status_id) || new_order_status_id == parseInt(void_status_id) || new_order_status_id == gift_receipt_status_id || new_order_status_id == gift_collected_status_id)) {
		// sync the current local order if the local current order is a valid order, regardless it's a local created order or a remote cached order
		// save the current order to local storage with specific order id, i.e. move it out from CACHE_CURRENT_ORDER
		var currOrderString = localStorage.getItem(CACHE_CURRENT_ORDER);
		if (currOrderString) {
			currOrder = JSON.parse(currOrderString);
			if (currOrder['products'] && currOrder['products'].length > 0) {
				// not an empty order, save it
				localStorage.setItem(CACHE_ORDER + currOrder['order_id'], currOrderString);
				// sync the order
				if (parseInt(currOrder['order_id']) > 0) {
					processed = true;
					syncData(processSaveOrderStatus, new_order_status_id, text_wait_saving_data_to_server);
				} else {
					syncData();
				}
			}
			// simply remove the CACHE_CURRENT_ORDER
			localStorage.removeItem(CACHE_CURRENT_ORDER);
		}
	}
	
	if (!processed) {
		if (new_order_status_id == parseInt(complete_status_id)) {
			// check if full payment has been made
			var dueAmount = $('#payment_due_amount').text();
			dueAmount = posParseFloat(dueAmount);
			if (dueAmount > 0.009) {
				    openConfirm(text_confirm_complete_without_payment, function() {
					new_order_status_id = 30;
					processSaveOrderStatus(new_order_status_id);
				});
			} else {
				processSaveOrderStatus(new_order_status_id);
			}
		} else {
			processSaveOrderStatus(new_order_status_id);
		}
	}
};

function processSaveOrderStatus(new_order_status_id) {
	var data = {'order_id': order_id, 'order_status_id': new_order_status_id};
	$.ajax({
		url: 'index.php?route=module/pos/saveOrderStatus&token=' + token,
		type: 'post',
		data: data,
		dataType: 'json',
		beforeSend: function() {
			openWaitDialog(text_saving_order_status);
		},
		complete: function() {
			closeWaitDialog();
		},
		cacheCallback: function(json) {
		},
		cachePreDone: function(cacheKey, callback) {
			backendSaveOrderStatus(data, callback);
		},
		success: function(json) {
			if (json['success']) {
				removeMessage();
				if (json['error']) {
					showMessage('error', json['error']);
				} else {
					showMessage('success', json['success']);
				}
				// add for Print begin
				var p_complete = 0;
				if (json['p_complete']) {
					p_complete = parseInt(json['p_complete']);
				}
					
				$("#order_status_name").html(json['order_status_name']);
				// refresh the current variable and the page value
				order_status_id = new_order_status_id;
				for (var i in order_statuses) {
					if (order_statuses[i]['order_status_id'] == new_order_status_id) {
						$('#order_status_name').text(order_statuses[i]['name']);
						order_status_name = order_statuses[i]['name'];
						populateOrderStatus();
						break;
					}
				}
				// change for gift receipt begin
				var gift_receipt_status_id = config['gift_receipt_status_id'] ? parseInt(config['gift_receipt_status_id']) : 0;
				var gift_collected_status_id = config['gift_collected_status_id'] ? parseInt(config['gift_collected_status_id']) : 0;
				if (p_complete > 0 || order_status_id == gift_receipt_status_id) {
				// change for gift receipt end
					// add and update for auto payment begin
					if (config['enable_auto_payment'] && parseInt(config['enable_auto_payment']) > 0 && posParseFloat($('#payment_due_amount').text()) > 0) {
						$('#payment_type').val(config['auto_payment_type']);
						$('#payment_type').trigger('change');
						addPayment(order_id);
					} else {
						// print receipt if set in the settings page
						if (config['config_print_type'] == 'invoice' ) {
							window_print_url(print_receipt_message, 'index.php?route=module/pos/invoice&token=' + token + '&order_id='+order_id + '&work_mode='+text_work_mode, {'change':'1'}, afterPrintReceipt, null);
						}else{
							window_print_url(print_receipt_message, 'index.php?route=module/pos/receipt&token=' + token + '&order_id='+order_id + '&work_mode='+text_work_mode, {'change':'1'}, afterPrintReceipt, null);
						}
					}
					// add and update for auto payment end
				}
				// add for Print end

				// change for gift receipt begin
				if (order_status_id == parseInt(complete_status_id) || order_status_id == parseInt(parking_status_id) || order_status_id == parseInt(void_status_id) || order_status_id == gift_receipt_status_id || order_status_id == gift_collected_status_id || order_status_id == 30) {
				// change for gift receipt end
					
					//refreshPage('order');
					//window.location.href = 'index.php?route=module/pos/main&token=' + token;
				}
				// add for refresh after complete end
			}
			//closeFancybox();
		},
		error: function(xhr, ajaxOptions, thrownError) {
			openAlert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
};

function saveQuoteStatus(new_quote_status_id) {
	var data = {'order_id': order_id, 'quote_status_id': new_quote_status_id};
	$.ajax({
		url: 'index.php?route=module/pos/saveQuoteStatus&token=' + token,
		type: 'post',
		data: data,
		dataType: 'json',
		beforeSend: function() {
			openWaitDialog(text_saving_quote_status);
		},
		complete: function() {
			closeWaitDialog();
		},
		cacheCallback: function(json) {
		},
		cachePreDone: function(cacheKey, callback) {
			backendSaveQuoteStatus(data, callback);
		},
		success: function(json) {
			if (json['success']) {
				removeMessage();
				showMessage('success', json['success']);
					
				// refresh the current variable and the page value
				quote_status_id = new_quote_status_id;
				for (var i in quote_statuses) {
					if (quote_statuses[i]['quote_status_id'] == new_quote_status_id) {
						$('#order_status_name').text(quote_statuses[i]['name']);
						quote_status = quote_statuses[i]['name'];
						break;
					}
				}
				if (new_quote_status_id == parseInt(quote_complete_status_id)) {
					convertQuote2Order(data);
				}
			}
			closeFancybox();
		},
		error: function(xhr, ajaxOptions, thrownError) {
			openAlert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
};

function convertQuote2Order(data) {
	openConfirm(text_convert_quote_to_order, function() {
		$.ajax({
			url: 'index.php?route=module/pos/convertQuote2Order&token=' + token,
			type: 'post',
			data: data,
			dataType: 'json',
			cacheCallback: function(json) {
			},
			cachePreDone: function(cacheKey, callback) {
				backendConvertQuote2Order(data, callback);
			},
			success: function(json) {
				$('#work_mode_dropdown').val('sale').selectmenu('refresh');
				text_work_mode = '0';
				removeMessage();
				showMessage('notification', '');
				selectOrder(0, json['order_id']);
			}
		});
	});
};

function changeReturnStatus() {
	$('#order_status_dialog ul').empty();
	$('#order_status_dialog h3').text(text_change_order_status);
	
	var html = '';
	for (var i in return_statuses) {
		if (return_status_id == return_statuses[i]['return_status_id']) {
			html += '<li><a onclick="saveReturnStatus(\'' + return_statuses[i]['return_status_id'] + '\');" class="table-btn-order-status selected"><span class="icon"></span>' + return_statuses[i]['name'] + '</a></li>';
		} else {
			html += '<li><a onclick="saveReturnStatus(\'' + return_statuses[i]['return_status_id'] + '\');" class="table-btn-order-status">' + return_statuses[i]['name'] + '</a></li>';
		}
	}
	
	$('#order_status_dialog ul').append(html);
	openFancybox('#order_status_dialog', 'wide');
};

function saveReturnStatus(new_return_status_id) {
	var data = {'pos_return_id': pos_return_id, 'return_status_id': new_return_status_id};
	$.ajax({
		url: 'index.php?route=module/pos/saveReturnStatus&token=' + token,
		type: 'post',
		data: data,
		dataType: 'json',
		beforeSend: function() {
			openWaitDialog(text_saving_return_status);
		},
		complete: function() {
			closeWaitDialog();
		},
		cacheCallback: function(json) {
		},
		cachePreDone: function(cacheKey, callback) {
			backendSaveReturnStatus(data, callback);
		},
		success: function(json) {
			if (json['success']) {
				removeMessage();
				showMessage('success', json['success']);
				
				var p_complete = 0;
				if (json['p_complete']) {
					p_complete = parseInt(json['p_complete']);
				}
				if (p_complete > 0) {
					// print receipt if set in the settings page
					window_print_url(print_receipt_message, 'index.php?route=module/pos/receipt&token=' + token + '&pos_return_id='+pos_return_id + '&work_mode='+text_work_mode, {}, afterPrintReceipt, null);
				}
					
				// refresh the current variable and the page value
				return_status_id = new_return_status_id;
				for (var i in return_statuses) {
					if (parseInt(return_statuses[i]['return_status_id']) == parseInt(new_return_status_id)) {
						$('#order_status_name').text(return_statuses[i]['name']);
						return_status = return_statuses[i]['name'];
						break;
					}
				}
				
				if (parseInt(return_status_id) == parseInt(return_complete_status_id)) {
					// once return complete, go back to sale page
					modeOrder();
				}
			}
			closeFancybox();
		},
		error: function(xhr, ajaxOptions, thrownError) {
			openAlert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
};

function changeShippingDetails() {
	if (config['hide_pos_shipping'] && parseInt(config['hide_pos_shipping']) > 0) return;
	
	var addrData = '#order_addresses input[type=\'text\'], #order_addresses input[type=\'hidden\'], #order_addresses select';
	
	$.ajax({
		url: store_url + 'index.php?route=pos/shipping&token=' + token,
		type: 'post',
		data: $(addrData),
		dataType: 'json',	
		beforeSend: function() {
			openWaitDialog(text_fetching_shipping);
		},
		complete: function() {
			closeWaitDialog();
		},
		cacheKey: CACHE_SHIPPING_METHOD,
		cachePreDone: function(cacheKey, callback) {
			var shippingMethod = localStorage.getItem(cacheKey);
			if (!shippingMethod) {
				shippingMethod = {};
			} else {
				shippingMethod = JSON.parse(shippingMethod);
			}
			callback(shippingMethod);
		},
		success: function(json) {
			json["shipping_method"] = false;
			if (json["shipping_method"]) {
				html = '<option value="">' + text_select + '</option>';

				for (i in json['shipping_method']) {
					html += '<optgroup label="' + json['shipping_method'][i]['title'] + '">';
				
					if (!json['shipping_method'][i]['error']) {
						for (j in json['shipping_method'][i]['quote']) {
							if (json['shipping_method'][i]['quote'][j]['code'] == shipping_code) {
								html += '<option value="' + json['shipping_method'][i]['quote'][j]['code'] + '" selected="selected">' + json['shipping_method'][i]['quote'][j]['title'] + '</option>';
							} else {
								html += '<option value="' + json['shipping_method'][i]['quote'][j]['code'] + '">' + json['shipping_method'][i]['quote'][j]['title'] + '</option>';
							}
						}		
					} else {
						html += '<option value="" style="color: #F00;" disabled="disabled">' + json['shipping_method'][i]['error'] + '</option>';
					}
					
					html += '</optgroup>';
				}
		
				$('select[name=\'shipping\']').html(html);	
			}
			openFancybox('#order_shipping_dialog', 'wide');
			$("select[name=payment_address] option:eq(1)").attr('selected','selected');
			var payment_address_id = $("select[name=payment_address] option:eq(1)").val();
			$.ajax({
					url: 'index.php?route=customer/customer/address&token=' + token + '&address_id=' + payment_address_id,
					dataType: 'json',
					cacheCallback: function(json) {
						backendSaveCustomerAddress(json);
					},
					cachePreDone: function(cacheKey, callback) {
						backendGetCustomerAddress(this.value, callback);
					},
					success: function(json) {
						if (json != '' && json !== null) {	
							$('input[name=\'payment_firstname\']').val(json['firstname']);
							$('input[name=\'payment_lastname\']').val(json['lastname']);
							$('input[name=\'payment_company\']').val(json['company']);
							$('input[name=\'payment_company_id\']').val(json['company_id']);
							$('input[name=\'payment_tax_id\']').val(json['tax_id']);
							$('input[name=\'payment_address_1\']').val(json['address_1']);
							$('input[name=\'payment_address_2\']').val(json['address_2']);
							$('input[name=\'payment_city\']').val(json['city']);
							$('input[name=\'payment_postcode\']').val(json['postcode']);
							$('select[name=\'payment_[country_id]\']').val(json['country_id']);
							
							payment_zone_id = json['zone_id'];
							
							order_country($('select[name=\'payment_country_id\']').get(0), 'payment', payment_zone_id);
						}
					}
				});	
			$("select[name=shipping_address] option:eq(1)").attr('selected','selected');
			var shipping_address_id = $("select[name=shipping_address] option:eq(1)").val();
			$.ajax({
					url: 'index.php?route=customer/customer/address&token=' + token + '&address_id=' + shipping_address_id,
					dataType: 'json',
					cacheCallback: function(json) {
						backendSaveCustomerAddress(json);
					},
					cachePreDone: function(cacheKey, callback) {
						backendGetCustomerAddress(this.value, callback);
					},
					success: function(json) {
						if (json) {
							$('input[name=\'shipping_firstname\']').val(json['firstname']);
							$('input[name=\'shipping_lastname\']').val(json['lastname']);
							$('input[name=\'shipping_company\']').val(json['company']);
							$('input[name=\'shipping_address_1\']').val(json['address_1']);
							$('input[name=\'shipping_address_2\']').val(json['address_2']);
							$('input[name=\'shipping_city\']').val(json['city']);
							$('input[name=\'shipping_postcode\']').val(json['postcode']);
							$('select[name=\'shipping_[country_id]\']').val(json['country_id']);
							
							shipping_zone_id = json['zone_id'];
							
							order_country($('select[name=\'shipping_country_id\']').get(0), 'shipping', shipping_zone_id);
						}
					}
				});
			//$("select[name=shipping] option:eq(1)").attr('selected','selected');
		}
	});
};

$(document).on('change', 'select[name=shipping]', function() {
	if ($(this).val()) {
		shipping_method = $('select[name=shipping] option:selected').text();
	} else {
		shipping_method = '';
	}
	
	shipping_code = $(this).val();
});

$(document).on('change', 'input[name=popup_discount_selection_bottom]', function() {
	if ($(this).val() == 'order_total') {
		$("#popup_discount_selection_bottom_order_total").show();
		$("#popup_discount_selection_bottom_discount_group").hide();
	} else {
		$("#popup_discount_selection_bottom_order_total").hide();
		$("#popup_discount_selection_bottom_discount_group").show();
	}	
});

$(document).on('change', 'select[name=\'shipping_address\']', function() {
	$.ajax({
		url: 'index.php?route=customer/customer/address&token=' + token + '&address_id=' + this.value,
		dataType: 'json',
		cacheCallback: function(json) {
			backendSaveCustomerAddress(json);
		},
		cachePreDone: function(cacheKey, callback) {
			backendGetCustomerAddress(this.value, callback);
		},
		success: function(json) {
			if (json) {
				$('input[name=\'shipping_firstname\']').val(json['firstname']);
				$('input[name=\'shipping_lastname\']').val(json['lastname']);
				$('input[name=\'shipping_company\']').val(json['company']);
				$('input[name=\'shipping_address_1\']').val(json['address_1']);
				$('input[name=\'shipping_address_2\']').val(json['address_2']);
				$('input[name=\'shipping_city\']').val(json['city']);
				$('input[name=\'shipping_postcode\']').val(json['postcode']);
				$('select[name=\'shipping_[country_id]\']').val(json['country_id']);
				
				shipping_zone_id = json['zone_id'];
				
				order_country($('select[name=\'shipping_country_id\']').get(0), 'shipping', shipping_zone_id);
			}
		}
	});	
});

$(document).on('change', 'select[name=\'payment_address\']', function() {
	$.ajax({
		url: 'index.php?route=customer/customer/address&token=' + token + '&address_id=' + this.value,
		dataType: 'json',
		cacheCallback: function(json) {
			backendSaveCustomerAddress(json);
		},
		cachePreDone: function(cacheKey, callback) {
			backendGetCustomerAddress(this.value, callback);
		},
		success: function(json) {
			if (json != '') {	
				$('input[name=\'payment_firstname\']').val(json['firstname']);
				$('input[name=\'payment_lastname\']').val(json['lastname']);
				$('input[name=\'payment_company\']').val(json['company']);
				$('input[name=\'payment_company_id\']').val(json['company_id']);
				$('input[name=\'payment_tax_id\']').val(json['tax_id']);
				$('input[name=\'payment_address_1\']').val(json['address_1']);
				$('input[name=\'payment_address_2\']').val(json['address_2']);
				$('input[name=\'payment_city\']').val(json['city']);
				$('input[name=\'payment_postcode\']').val(json['postcode']);
				$('select[name=\'payment_[country_id]\']').val(json['country_id']);
				
				payment_zone_id = json['zone_id'];
				
				order_country($('select[name=\'payment_country_id\']').get(0), 'payment', payment_zone_id);
			}
		}
	});	
});

function refreshTotal() {
	// get total from backend and refresh total area
	data = {};
	data['order_id'] = order_id;
	data['customer_id'] = customer_id;
	data['customer_group_id'] = customer_group_id;
	data['shipping_country_id'] = shipping_country_id;
	data['shipping_zone_id'] = shipping_zone_id;
	data['payment_country_id'] = payment_country_id;
	data['payment_zone_id'] = payment_zone_id;
	data['currency_code'] = currency_code;
	data['currency_value'] = currency_value;
	// as the refreshTotal is only called by saveShippingDetails after its ajax call, this call will not need any cache
	$.ajax({
		url: 'index.php?route=module/pos/update_total&token=' + token,
		type: 'post',
		data: data,
		dataType: 'json',
		localCache: false,
		success: function(json) {
			// if the order does have products added, update the total
			if (json['order_total']) {
				updateTotal(json['order_total']);
			}
		}
	});
};

function saveShippingDetails() {
	// save shipping and payment address to the order
	var data = {};
	$('#order_addresses input[type=\'text\'], #order_addresses input[type=\'hidden\'], #order_addresses select').each(function() {
		data[$(this).attr('name')] = $(this).val();
	});
	data['shipping_code'] = shipping_code;
	data['shipping_method'] = shipping_method;
	// the add customer button was pressed
	$.ajax({
		url: 'index.php?route=module/pos/saveOrderAddresses&token=' + token + '&order_id=' + order_id,
		type: 'post',
		dataType: 'json',
		data: data,
		beforeSend: function() {
			openWaitDialog();
		},
		complete: function() {
			closeWaitDialog();
			if (text_work_mode == '2') {
				showMessage('success', text_quote_ready);
			} else {
				showMessage('success', text_order_ready);
			}
		},
		cacheCallback: function(json) {
		},
		cachePreDone: function(cacheKey, callback) {
			// only need to write the address values to the order and update total, don't need to call the callback as the current success function is to call another ajax to update total
			saveOrderInfo(data);
			
			backendUpdateTotal({'order_id':order_id, 'customer_group_id':customer_group_id}, function(json) {
				if (json && json['order_total']) {
					updateTotal(json['order_total']);
				}
			});
			
			$('#shipping_method').text(shipping_method);
			closeFancybox();
		},
		success: function(json) {
			$('input[name=shipping_title]').val(json.shipping_title);
			$('input[name=shipping_cost]').val(json.shipping_cost);
			$("#shipping_total").val("$" + json.shipping_cost);
			refreshTotal();
			// save the payment method session data
			$.ajax({
				url: store_url + 'index.php?route=pos/shipping&token=' + token,
				type: 'post',
				data: data,
				dataType: 'json',	
				success: function(json) {
					// if the order does have products added, update the total
					if ($('#product tr').length > 1) {
						refreshTotal();
					}
					$('#shipping_method').text(shipping_method);
				}
			});
			// save shipping and payment js variables
			shipping_country_id = $('select[name=shipping_country_id]').val();
			shipping_zone_id = $('select[name=shipping_zone_id]').val();
			payment_country_id = $('select[name=payment_country_id]').val();
			payment_zone_id = $('select[name=payment_zone_id]').val();
			refreshTotal();
			closeFancybox();
		}
	});
};

function changeOrderCustomer() {
	var data = {'customer_id': customer_id, 'customer_group_id' : customer_group_id};
	if (parseInt(customer_id)) {
		data['customer_firstname'] = customer_firstname;
		data['customer_lastname'] = customer_lastname;
		data['customer_email'] = customer_email;
		data['customer_telephone'] = customer_telephone;
		data['customer_fax'] = customer_fax;
		data['customer_password'] = '';
		data['customer_confirm'] = '';
		data['customer_newsletter'] = customer_newsletter;
		data['customer_status'] = customer_status;
		data['customer_resale_number'] = customer_resale_number;
		data['customer_address_id'] = (typeof customer_address_id == 'undefined') ? 0 : customer_address_id;
		
		data['customer_addresses'] = (typeof customer_addresses == 'undefined') ? [] : customer_addresses;
	} else {
		data['customer_firstname'] = firstname;
		data['customer_lastname'] = lastname;
		data['customer_email'] = email;
		data['customer_telephone'] = telephone;
		data['customer_fax'] = fax;
	}

	populateCustomerDialog(data);

	
	openFancybox('#customer_dialog', 'wide');
	resizeFancybox();
};

function saveCustomer( session_discount_customer_group_id = false ) {
	var loaded_customer = $('input[name=customer_id]').val();
	if ( loaded_customer == 0 && session_discount_customer_group_id == false ){

		newCustomer();
		return;
	}
	var data = '#order_customer input[type=\'text\'], #order_customer input[type=\'hidden\'], #order_customer input[type=\'password\'], #order_customer input[type=\'radio\']:checked, #order_customer input[type=\'checkbox\']:checked, #order_customer select, #order_customer textarea';
	var url = 'index.php?route=module/pos/saveCustomer&token=' + token + '&order_id=' + order_id + '&session_discount_customer_group_id=' + session_discount_customer_group_id;
	if (text_work_mode == '1') {
		url = 'index.php?route=module/pos/saveCustomer&token=' + token + '&pos_return_id=' + pos_return_id;
	}
	$.ajax({
		url: url,
		type: 'post',
		data: $(data),
		dataType: 'json',
		beforeSend: function() {
			openWaitDialog(text_saving_customer);
		},
		complete: function() {
			closeWaitDialog();
		},
		cacheCallback: function(json) {
		},
		cachePreDone: function(cacheKey, callback) {
			// only need to write the customer values to the order and customer js variables
			var formData = {};
			$(data).each(function() {
				formData[$(this).attr('name')] = $(this).val();
			});
			backendSaveCustomer(formData, callback);
		},
		success: function(json) {
			populateCustomerData(json);
			refreshTotal();
			location.reload();
		},
		error: function(xhr, ajaxOptions, thrownError) {
			openAlert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
};

function populateCustomerData(json) {
	if (json['success']) {
		removeMessage();
		showMessage('success', json['success']);
		var name = $('#order_customer input[name=\'customer_firstname\']').val() + ' ' + $('#order_customer input[name=\'customer_lastname\']').val();
		$('#order_customer_name').text(name);
		$('#address_warning').remove();
		if (json['hasAddress'] && json['hasAddress'] == '1') {
			$('#order_customer_name').before('<img id="address_warning" src="view/image/pos/warning.png" alt="' + text_customer_no_address + '" title="' + text_customer_no_address + '" />');
			$('#order_customer_name').css('width', ($('#order_customer_name').width() - 32) + 'px');
		}

		if (text_work_mode != '1') {
			// add for edit order address begin
			$('select[name=shipping_address]').empty();
			$('select[name=shipping_address]').append('<option value="0" selected="selected">' + text_none + '</option>');
			$('select[name=payment_address]').empty();
			$('select[name=payment_address]').append('<option value="0" selected="selected">' + text_none + '</option>');
			for (i in json['customer_addresses']) {
				$('select[name=shipping_address]').append('<option value="' + json['customer_addresses'][i]['address_id'] + '">' + json['customer_addresses'][i]['firstname'] + ' ' + json['customer_addresses'][i]['lastname'] + ', ' + json['customer_addresses'][i]['address_1'] + ', ' + json['customer_addresses'][i]['city'] + ', ' + json['customer_addresses'][i]['country'] + '</option>');
				$('select[name=payment_address]').append('<option value="' + json['customer_addresses'][i]['address_id'] + '">' + json['customer_addresses'][i]['firstname'] + ' ' + json['customer_addresses'][i]['lastname'] + ', ' + json['customer_addresses'][i]['address_1'] + ', ' + json['customer_addresses'][i]['city'] + ', ' + json['customer_addresses'][i]['country'] + '</option>');
			}
			// add for edit order address end
		}
		
		// before update the variable, store the zones info for the previous saved addresses
		if (typeof customer_addresses != 'undefined') {
			for (var i in customer_addresses) {
				var address = customer_addresses[i];
				if (address['zones']) {
					saved_zones[address['country_id']] = address['zones'];
				}
			}
		}
		// update the javascript variables
		if (parseInt(json['customer_id']) == 0) {
			for (var name in json) {
				window[name] = json[name];
				$('input[name=' + name + ']').val(json[name]);
			}
		} else {
			for (var name in json) {
				var vName = name;
				if (name != 'customer_id' && name != 'customer_group_id' && name.substring(0, 9) == 'customer_') {
					vName = name.substring(9);
				}
				window[vName] = json[name];
				window[name] = json[name];
				//alert(name);
				$('input[name=' + name + ']').val(json[name]);
				$('input[name=' + name + ']').attr('value', json[name]);
				//alert($('input[name=' + name + ']').val());
			}
			if (typeof json['hasAddress'] != 'undefined' && parseInt(json['hasAddress']) == 0) {
				// no address
				customer_addresses = {};
			}
		}
		
		closeFancybox();
	}
};

function resetCustomer() {
	// Get the default customer info
	$.ajax({
		url: 'index.php?route=module/pos/get_default_customer_ajax&token=' + token,
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			$('input[name=customer_firstname_tab]').val('');
			$('input[name=customer_lastname_tab]').val('');
			$('input[name=customer_email_tab]').val(''); 
			$('input[name=customer_firstname_tab1]').val(''); 
			$('input[name=customer_telephone_tab]').val('');
			$('input[name=customer_fax_tab]').val('');
			$('input[name=customer_card_number_tab]').val('');
			$('input[name=customer_resale_number_tab]').val('');
			$('input[name=customer_notes_tab]').val('');
			$('#customer_select').text('SELECT CUSTOMER');
			$('#append_customer').hide();$("#append_customer").hide();
			$(".table-btn-common.customer.reset").html('<span class="icon"></span>Reset Customer');
			$(".table-btn-common.customer.new").html('<span class="icon"></span>ADD NEW CUSTOMER');
			$('#order_customer').hide();
			$('#customer_action_info').show();
		},
		cacheKey: CACHE_DEFAULT_CUSTOMER,
		success: function(json) {
			// update order_customer section
			populateCustomerDialog(json);
			$('input[name=customer_id]').val(json['customer_id']);
			for (var name in json) {
				window[name] = json[name];
			}
			
			$('#customer_order_history').attr('onclick','');
			$('#customer_return_history').attr('onclick','');
			$('#customer_sales_notes').attr('onclick','');
			$('#customer_order_history').attr('onclick','orderHistoryTab('+json['customer_id']+')');
			$('#customer_sales_notes').attr('onclick','saveCustomerNotes('+json['customer_id']+')');
			$('#customer_return_history').attr('onclick','getReturnListTab('+json['customer_id']+')');
			$("#tab_customer_id").val(json['customer_id']);
			$("#append_customer").empty();
			$("#append_customer").removeAttr('style');
			$.ajax({
              type:'GET',
              url:'index.php?route=module/pos/getCustomerNotes&customer_id='+json['customer_id']+'&token='+token,
              cache:false,
              success:function(data){
              	if(data!='0'){
              	$("textarea[name=customer_sales_notes]").val(data);
              }else{
              	$("textarea[name=customer_sales_notes]").val('');
              }
              }
			});
			
			$('#order_customer').show();
			$('#customer_action_info').hide();
		}
	});
};

function populateCustomerDialog(data) {
	// remove address tabs
	$('a[id^=customer_address_]').each(function() {
		$(this).closest('li').remove();
	});
	$('div[id^=tab_customer_address_]').remove();
		
	if (parseInt(data['customer_id'])) {
		// is real customer
		// show extra info
		$('.customer_extra_info').show();
		// populate customer general info
		$('input[name=customer_firstname]').val(data['customer_firstname']);
		$('input[name=customer_lastname]').val(data['customer_lastname']);
		$('input[name=customer_email]').val(data['customer_email']);
		$('input[name=customer_telephone]').val(data['customer_telephone']);
		$('input[name=customer_fax]').val(data['customer_fax']);
		$('input[name=customer_password]').val(data['customer_password']);
		$('input[name=customer_confirm]').val(data['customer_confirm']);
		$('select[name=customer_newsletter]').val(data['customer_newsletter']);
		$('select[name=customer_newsletter]').trigger('change');
		$('select[name=customer_group_id]').val(data['customer_group_id']);
		$('select[name=customer_group_id]').trigger('change');
		$('select[name=customer_status]').val(data['customer_status']);
		$('select[name=customer_status]').trigger('change');
		$('input[name=customer_resale_number]').val(data['customer_resale_number']);
		$('input[name=customer_notes]').val(data['customer_notes']);
		// add address tabs
		
		if (data['customer_addresses']) {
			var address_row = 1;
			for (var i in data['customer_addresses']) {
				// add an address tab
				addCustomerAddressTab(address_row, data['customer_addresses'][i], data['customer_address_id']);
				
				address_row++;
			}
		}
		// show add address tab
		$('#customer_new_address').show();
	} else {
		// hide extra info
		$('.customer_extra_info').hide();
		// populate general info
		$('input[name=customer_firstname]').val(data['customer_firstname']);
		$('input[name=customer_lastname]').val(data['customer_lastname']);
		$('input[name=customer_email]').val(data['customer_email']);
		$('input[name=customer_telephone]').val(data['customer_telephone']);
		$('input[name=customer_fax]').val(data['customer_fax']);
		// hide add address tab
		$('#customer_new_address').hide();
	}
	$('#customer_general').trigger('click');
};

function addCustomerAddressTab(address_row, address, customer_address_id) {
	if(address!=""){
	var addresstab=address.address_1;
	addresstab=addresstab.substr(0, 11);
	}else{
			var addresstab='Create New';
		}
	var addressTabHtml = '<li><a onclick="$(\'#customer_select\').html($(\'#fname'+address_row+'\').val());" href="#tab_customer_address_' + address_row + '" id="customer_address_' + address_row + '" data-toggle="tab">';
	addressTabHtml += '<span onclick="$(\'#customer_general\').trigger(\'click\');$(this).closest(\'li\').remove(); $(\'#tab_customer_address_' + address_row + '\').remove();" class="icon">';
	addressTabHtml += '</span>' + addresstab + '</a></li>';
	$('#customer_new_address').closest('li').before(addressTabHtml);
	
	// find an address id for the new address
	var new_address_id = -1;
	$('div[id^=tab_customer_address_]').each(function() {
		var address_id = $(this).find("input[name$='[address_id]']").val();
		if (parseInt(address_id) <= new_address_id) {
			new_address_id = parseInt(address_id) - 1;
		}
	});
	var addressHtml = '<div id="tab_customer_address_' + address_row + '" class="tab-pane">';
	addressHtml += '<input type="hidden" name="customer_addresses[' + address_row + '][address_id]" value="' + (address ? address['address_id'] : new_address_id) + '" />';
	addressHtml += '<table style="width:100%;" border="0">';
	addressHtml += '	<tr>';
	addressHtml += '		<td align="right"><li><label>' + entry_firstname + ' <em style="color: red;">*</em></label></td>';
	addressHtml += '		<td><div class="inputbox"><input type="text" name="customer_addresses[' + address_row + '][firstname]" value="' + (address ? address['firstname'] : '') + '" id="fname'+address_row+'" /></div></li></td>';
	addressHtml += '		<td align="right"><li><label>' + entry_company + ' </label></td>';
	addressHtml += '		<td><div class="inputbox"><input type="text" name="customer_addresses[' + address_row + '][company]" value="' + (address ? address['company'] : '') + '" /></div></li></td>';
	addressHtml += '	</tr>';
	addressHtml += '	<tr>';
	addressHtml += '		<td align="right"><li><label>' + entry_lastname + ' <em style="color: red;">*</em></label></td>';
	addressHtml += '		<td><div class="inputbox"><input type="text" name="customer_addresses[' + address_row + '][lastname]" value="' + (address ? address['lastname'] : '') + '" /></div></li></td>';
	addressHtml += '		<td align="right"><li><label>' + entry_address_1 + ' <em style="color: red;">*</em></label></td>';
	addressHtml += '		<td><div class="inputbox"><input type="text" name="customer_addresses[' + address_row + '][address_1]" value="' + (address ? address['address_1'] : '') + '" /></div></li></td>';
	addressHtml += '	</tr>';
	addressHtml += '	<tr>';
	addressHtml += '		<td align="right"><li><label>' + entry_address_2 + ' </label></td>';
	addressHtml += '		<td><div class="inputbox"><input type="text" name="customer_addresses[' + address_row + '][address_2]" value="' + (address ? address['address_2'] : '') + '" /></div></li></td>';
	addressHtml += '		<td align="right"><li><label>' + entry_city + ' <em style="color: red;">*</em></label></td>';
	addressHtml += '		<td><div class="inputbox"><input type="text" name="customer_addresses[' + address_row + '][city]" value="' + (address ? address['city'] : '') + '" /></div></li></td>';
	addressHtml += '	</tr>';
	addressHtml += '	<tr>';
	addressHtml += '		<td align="right"><li><label>' + entry_postcode + ' <em style="color: red;">*</em></label></td>';
	addressHtml += '		<td><div class="inputbox"><input type="text" name="customer_addresses[' + address_row + '][postcode]" value="' + (address ? address['postcode'] : '') + '" /></div></li></td>';
	addressHtml += '		<td align="right"><li><label>' + entry_country + ' <em style="color: red;">*</em></label></td>';
	addressHtml += '		<td><div class="inputbox"><select name="customer_addresses[' + address_row + '][country_id]" onchange="country(this, ' + address_row + ', ' + (address ? address['zone_id'] : shipping_zone_id) + ');">';
	addressHtml += '			<option value="">' + text_select + '</option>';
	for (var j in customer_countries) {
		var customer_country = customer_countries[j];
		if (customer_country['country_id'] == (address ? address['country_id'] : shipping_country_id)) {
	addressHtml += '			<option value="' + customer_country['country_id'] + '" selected="selected">' + customer_country['name'] + '</option>';
		} else {
	addressHtml += '			<option value="' + customer_country['country_id'] + '">' + customer_country['name'] + '</option>';
		}
	}
	addressHtml += '		</select></div></li></td>';
	addressHtml += '	</tr>';
	
	addressHtml += '	<tr>';
	addressHtml += '		<td align="right"><li><label>' + entry_zone + ' <em>*</em></label></td>';
	addressHtml += '		<td><div class="inputbox">';
	addressHtml += '			<select name="customer_addresses[' + address_row + '][zone_id]">';
	addressHtml += '				<option value="">' + text_select + '</option>';
	var zones = customer_shipping_zones;
	if (address) {
		zones = saved_zones[address['country_id']] ? saved_zones[address['country_id']] : address['zones'];
	}
	for (var j in zones) {
		var zone = zones[j];
		if (zone['zone_id'] == (address ? address['zone_id'] : shipping_zone_id)) {
	addressHtml += '				<option value="' + zone['zone_id'] + '" selected="selected">' + zone['name'] + '</option>';
		} else {
	addressHtml += '				<option value="' + zone['zone_id'] + '" >' + zone['name'] + '</option>';
		}
	}
	addressHtml += '			</select></div></li></td>';
	addressHtml += '		<td align="right"><li><label>&nbsp;</label></td>';
	addressHtml += '		<td><div class="inputbox"><label class="radio_check">';
	if (address && address['address_id'] == customer_address_id) {
	addressHtml += '			<input type="checkbox" name="customer_addresses[' + address_row + '][default]" value="' + address_row + '" checked="checked" />' + ' &nbsp; ' + entry_default;
	} else {
	addressHtml += '			<input type="checkbox" name="customer_addresses[' + address_row + '][default]" value="' + address_row + '" />' + ' &nbsp; ' +  entry_default;
	}
	addressHtml += '		</label></div>';
	addressHtml += '	</li></td>';
	addressHtml += '</tr>';
	addressHtml += '</table>';
	addressHtml += '</div>';
	
	$('#tab_customer_new_address').before(addressHtml);
};

function checkCustomer(customer_id) {
	var valid;
	var url = 'index.php?route=module/pos/checkCustomer&token=' + token + '&customer_id=' + customer_id;
	$.ajax({
		url: url,
		type: 'get',
		dataType: 'json',
		beforeSend: function() {
			openWaitDialog("Checking Existing Customers");
		},
		complete: function() {
			closeWaitDialog();
		},
		cacheCallback: function(json) {
		},
		success: function(json) {
			//alert(json);
			//valid = json;
			temp = JSON.stringify(json);
			temp = JSON.parse(temp);
			if (temp == true)
				valid = true;
			else
				valid = false;
		},
		error: function(xhr, ajaxOptions, thrownError) {
			openAlert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		},
		async: false
	});
	return valid;
}

function new_customer_tab(){
	$('#customer_select').parent().removeClass('active');
	$('#new_customer').parent().addClass('active');
	$('#fbox_btn_wrap_save_button').html('');
	$('#fbox_btn_wrap_save_button').html('<a onclick="newCustomer();" class="table-btn-common customer new"><span class="icon"></span>Add new customer</a>');
}

function newCustomer() {
	loaded_customer = $('input[name=customer_id]').val();
	if (loaded_customer > 0){
		if (checkCustomer(loaded_customer)){
			openAlert("Customer is already Exists. Use Save Button to save changes");
			return;
		}
	}
	//alert('save new here');
	//return;
	if ( $('input[name=customer_email]').val() == "customer@instore.com" || $('input[name=customer_firstname]').val() == "In Store" || $('input[name=customer_lastname]').val() == "Guest" || $('input[name=customer_telephone]').val() == "1600") {

		if ( $('input[name=customer_lastname_tab]').val() == "" || $('input[name=customer_email_tab]').val() == "" || $('input[name=customer_firstname_tab1]').val() == "" || $('input[name=customer_telephone_tab]').val() == "") {
			alert("Fill in All Required Details of Customer!");
			return;
		} else {
			var email = $('input[name=customer_email_tab]').val();
			if(!validateEmail(email))
			{
				alert("Please provide a valid email address for customer!");
				return;
			}
			$('input[name=customer_email]').val($('input[name=customer_email_tab]').val());
			$('input[name=customer_firstname]').val($('input[name=customer_firstname_tab1]').val());
			$('input[name=customer_lastname]').val($('input[name=customer_lastname_tab]').val());
			$('input[name=customer_telephone]').val($('input[name=customer_telephone_tab]').val());
		}
	}
	//return;
	$('input[name=customer_id]').val('-1'); //trigger new customer
	session_discount_customer_group_id = false;
	var data = '#order_customer input[type=\'text\'], #order_customer input[type=\'hidden\'], #order_customer input[type=\'password\'], #order_customer input[type=\'radio\']:checked, #order_customer input[type=\'checkbox\']:checked, #order_customer select, #order_customer textarea';
	var url = 'index.php?route=module/pos/addnewCustomer&token=' + token + '&order_id=' + order_id + '&session_discount_customer_group_id=' + session_discount_customer_group_id;
	if (text_work_mode == '1') {
		url = 'index.php?route=module/pos/addnewCustomer&token=' + token + '&pos_return_id=' + pos_return_id;
	}
	$.ajax({
		url: url,
		type: 'post',
		data: $(data),
		dataType: 'json',
		beforeSend: function() {
			openWaitDialog(text_saving_customer);
		},
		complete: function() {
			closeWaitDialog();
		},
		cacheCallback: function(json) {
		},
		success: function(json) {
			info = JSON.parse(JSON.stringify(json));
			openAlert(info.msg);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			openAlert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
	//resizeFancybox();
};

$(".pos-nav-tabs").on("click", "a", function (e) {
     e.preventDefault();
     if ($(this).attr('id') != 'customer_new_address') {
         $(this).tab('show');
     }
})

$('#customer_new_address').click(function(e) {
	e.preventDefault();
	var len = $(this).closest('ul').children().length;
	var address_row = 1;
	if (len > 2) {
		var tabName = $(this).closest('ul').find('li').eq(len-2).find('a').attr('id');
		var index = tabName.lastIndexOf('_');
		address_row = parseInt(tabName.substring(index+1)) + 1;
	}
	addCustomerAddressTab(address_row,'','');

	$('#customer_address_' + address_row).click();
});

function orderHistoryTab(customer_id){
	var url = 'index.php?route=module/pos/getCustomersOrderList&customer_id='+customer_id+'&token=' + token;
	$.ajax({
		url: url,
		type: 'post',
		dataType: 'json',
		cacheCallback: function() {
			// do not save the list as the list only contains the limited info of the customer, need to save customer once the customer is selected
		},
		
		beforeSend: function() {
			openWaitDialog();
		},
		complete: function() {
			closeWaitDialog();
		},
		success: function(json) {
			renderCustomersOrderListTab(json);
			//closeFancybox();
			//openFancybox('#orderhistory_list_dialog', 'wide', false, false, false);
		}
	});
	
			
			
};
function orderHistory(){
	var url = 'index.php?route=module/pos/getCustomersOrderList&customer_id='+customer_id+'&token=' + token;
	$.ajax({
		url: url,
		type: 'post',
		dataType: 'json',
		cacheCallback: function() {
			// do not save the list as the list only contains the limited info of the customer, need to save customer once the customer is selected
		},
		
		beforeSend: function() {
			openWaitDialog();
		},
		complete: function() {
			closeWaitDialog();
		},
		success: function(json) {
			renderCustomersOrderList(json);
			closeFancybox();
			openFancybox('#orderhistory_list_dialog', 'wide', false, false, false);
		}
	});
	
			
			
};
 
function renderCustomersOrderListTab(json) {
	// render the customer list
	var html = '';
	if (json['orders'] && json['orders'].length > 0) {
		var trClass = 'even';
		for (var i in json['orders']) {
			if (trClass == 'even') { trClass = 'odd' } else { trClass = 'even'; };
			html += '<tr class="' + trClass + '">';
			html += '<td class="two"><span class="skip_content label">Order ID</span>' + json['orders'][i]['order_id'] + '</td>';
			html += '<td class="four"><label class="skip_content">' + column_customer_name + '</label>' + json['orders'][i]['name'] + '</td>';
			html += '<td class="five"><label class="skip_content">Status</label>' + json['orders'][i]['status'] + '</td>';
			html += '<td class="six"><label class="skip_content">' + column_date_added + '</label>' + json['orders'][i]['date_added'] + '</td>';
			html += '<td class="six"><label class="skip_content">Total </label>' + json['orders'][i]['total'] + '</td>';
			html += '<td class="six"><label class="skip_content">Action </label><a href="index.php?route=module/pos/invoice&customer_order_invoice=1&token='+token+"&order_id=" + json['orders'][i]['order_id'] + '" class="oit"><img src="view/image/pos/pdf.png" style="width:48px;height:48px;"></a></td>';
			html += '</tr>';
		}
	} else {
		html += '<tr><td align="center" colspan="6">' + text_no_results + '</td></tr>';
	}
	$('#orderhistor_list_customers_tab').html(html);
};
function renderCustomersOrderList(json) {
	// render the customer list
	var html = '';
	if (json['orders'] && json['orders'].length > 0) {
		var trClass = 'even';
		for (var i in json['orders']) {
			if (trClass == 'even') { trClass = 'odd' } else { trClass = 'even'; };
			html += '<tr class="' + trClass + '">';
			html += '<td class="two"><span class="skip_content label">Order ID</span>' + json['orders'][i]['order_id'] + '</td>';
			html += '<td class="two"><span class="skip_content label">Store</span>' + json['orders'][i]['store_name'] + '</td>';
			html += '<td class="four"><label class="skip_content">' + column_customer_name + '</label>' + json['orders'][i]['name'] + '</td>';
			html += '<td class="five"><label class="skip_content">Status</label>' + json['orders'][i]['status'] + '</td>';
			html += '<td class="six"><label class="skip_content">' + column_date_added + '</label>' + json['orders'][i]['date_added'] + '</td>';
			html += '<td class="six"><label class="skip_content">Total </label>' + json['orders'][i]['total'] + '</td>';
			html += '<td class="six"><label class="skip_content">Paid </label>'+json['orders'][i]['currency_code']+'</td>';
			html += '<td class="six"><label class="skip_content">Payment Method </label>' + json['orders'][i]['payment_method'] + '</td>';
			html += '<td class="six"><label class="skip_content">action </label><a onclick="showReceipt('+json['orders'][i]['order_id']+');" data-href="index.php?route=sale/order/info&token='+token+"&order_id=" + json['orders'][i]['order_id'] + '" href="javascript:void(0)">View</a> / <a href="index.php?route=module/pos/main&token='+token+"&order_id=" + json['orders'][i]['order_id'] + '">Edit</a> / <a data-href="index.php?route=sale/order/edit&token='+token+"&order_id=" + json['orders'][i]['order_id'] + '" onclick="refundMenu('+json['orders'][i]['order_id']+')" href="javascript:void(0);">Refund</a>';
				if(json['orders'][i]['resume_button']){
				  html +='/ <a onclick="resumeOrder('+json['orders'][i]['order_id']+')" href="javascript:void(0);" > Resume Order </a>';
				}
			html += '</td>';
			html += '</tr>';
		}
	} else {
		html += '<tr><td align="center" colspan="6">' + text_no_results + '</td></tr>';
	}
	$('#orderhistor_list_customers').html(html);
};

function resumeOrder(order_id){
	closeFancybox();
var url="index.php?route=sale/orderq/edit&token="+ token + "&order_id="+ order_id;
$("#resume_order").attr("src",url);
$("#button_print_receipt_edit_order").attr('onclick','printReceipt('+order_id+');');

$('#resume_order').show();
      $('#button_print_receipt_edit_order').hide();
openFancybox('#resumeOrder_dialog','wider');
resizeFancybox();

	//console.log(order_id);



};

		function refundMenu(order_id){
          $("#pos_wait_msg").show();
			$.ajax({
		  type:"GET",
		  url : "index.php?route=module/pos/AddRma&token="+ token + "&order_id="+ order_id,
		  cache:false,
		  success:function(data){
		  	$("#refnd_dialogue").html(data);
           $("#pos_wait_msg").hide();		  
             closeFancybox();
			openFancybox('#show_refund_dialog', 'wide');
		  }
		});
		}

		
         function showReceipt(order_id){
               $("#pos_wait_msg").show();

               $.ajax({
                   type:"GET",
                   url: "index.php?route=module/pos/getUniqueOrderEmail&token="+ token +"&order_id="+order_id,
                   cache: false,
                   dataType:"json",
                   success:function(data){

                     htmlShowScript = "";
					htmlShowScript += '<div class="receipt_div">';

					htmlShowScript += '<div class="col-sm-4">';				
					htmlShowScript += '<a style="width: 195px;" id="button_email_receipt" onclick="emailReceipt(\''+order_id+'\',\''+data+'\');" class="table-btn-common email"><span class="icon"></span> Email Receipt</a>';
					htmlShowScript += '</div>';
					htmlShowScript += '<div class="col-sm-8 padding10px">';
                   	htmlShowScript += '<a style="width: 195px;" id="button_print_receipt" onclick="printReceipt('+order_id+');" class="table-btn-common"><span class="icon"></span><i class="fa fa-print fa-2x"></i> Print Receipt</a>';
					htmlShowScript += '</div>';
					htmlShowScript += '<div class="col-sm-4">';
						
					htmlShowScript += '<a style="width: 195px;" id="button_email_print_receipt" onclick="emailReceipt(\''+order_id+'\',\''+data+'\');printReceipt('+order_id+');" class="table-btn-common email print"><span class="icon"></span> <i class="fa fa-print fa-2x"></i>  Email + Print</a>';
					htmlShowScript += '</div>';
					htmlShowScript += '<div class="col-sm-8 padding10px">';
                     
                    htmlShowScript += '<a style="width: 195px;" id="button_email_receipt" onclick="$.fancybox.close();" class="table-btn-common"> No Receipt</a>';
					
					htmlShowScript += '</div>';
				    htmlShowScript += '</div>';

				    $("#receiptdialogue").html(htmlShowScript);
                    $("#pos_wait_msg").hide();	
     				closeFancybox();
					openFancybox('#show_receipt_dialog', 'wide');
					resizeFancybox();
                   }
               });

					
         }


function openResaleDialog() {
	openFancybox('#resale_id_number_dialog', 'narrow');
}
function getCustomerList(data) {
	var url = 'index.php?route=module/pos/getCustomerList&token=' + token;
	$.ajax({
		url: url,
		type: 'post',
		dataType: 'json',
		data: data ? data : {},
		cacheCallback: function() {
			// do not save the list as the list only contains the limited info of the customer, need to save customer once the customer is selected
		},
		cachePreDone: function(cacheKey, callback) {
			backendGetCustomerList(data, callback);
		},
		beforeSend: function() {
			openWaitDialog();
		},
		complete: function() {
			closeWaitDialog();
		},
		success: function(json) {
			renderCustomerList(json);
			closeFancybox();
			openFancybox('#customer_list_dialog', 'wide', false, false, 'changeOrderCustomer');
		}
	});
};

function renderCustomerList(json) {
	// render the customer list
	var html = '';
	if (json['customers'] && json['customers'].length > 0) {
		var trClass = 'even';
		for (var i in json['customers']) {
			if (trClass == 'even') { trClass = 'odd' } else { trClass = 'even'; };
			html += '<tr class="' + trClass + '">';
			html += '<td class="two"><span class="skip_content label">' + column_customer_id + '</span>' + json['customers'][i]['company_id'] + '</td>';
			html += '<td class="four"><label class="skip_content">' + column_customer_name + '</label>' + json['customers'][i]['name'] + '</td>';
			html += '<td class="five"><label class="skip_content">' + column_email + '</label>' + json['customers'][i]['email'] + '</td>';
			html += '<td class="six"><label class="skip_content">' + column_telephone + '</label>' + json['customers'][i]['telephone'] + '</td>';
			html += '<td class="seven"><label class="skip_content">' + column_date_added + '</label>' + json['customers'][i]['date_added'] + '</td>';
			html += '<td class="nine"><a onclick="selectCustomer(this, ' + json['customers'][i]['customer_id'] + ');" class="table-btn fbox_trigger_2"><span class="icon select"></span> ' + text_select + '</a></td>';
			html += '</tr>';
		}
	} else {
		html += '<tr><td align="center" colspan="6">' + text_no_results + '</td></tr>';
	}
	$('#customer_list_customers').html(html);
	$('#customer_list_pagination').html(json['pagination']);
};

function selectCustomerPage(page) {
	filterCustomer(page);
};

$('#customer_list_dialog input').keypress(function(e) {
	if (e.keyCode == $.ui.keyCode.ENTER) {
		filterCustomer();
	}
});

function filterCustomer(page) {
	var data = {};
	var filter_customer_id = $('input[name=\'filter_customer_id\']').val();
	if (filter_customer_id) {
		data['filter_customer_id'] = filter_customer_id;
	}
	var filter_customer_name = $('input[name=\'filter_customer_name\']').val();
	if (filter_customer_name) {
		data['filter_name'] = filter_customer_name;
	}
	var filter_customer_email = $('input[name=\'filter_customer_email\']').val();
	if (filter_customer_email != '') {
		data['filter_email'] = filter_customer_email;
	}	
	var filter_customer_telephone = $('input[name=\'filter_customer_telephone\']').val();
	if (filter_customer_telephone != '') {
		data['filter_telephone'] = filter_customer_telephone;
	}	
	var filter_customer_date = $('input[name=\'filter_customer_date\']').val();
	if (filter_customer_date) {
		data['filter_date_added'] = filter_customer_date;
	}
	if (page) {
		data['page'] = page;
	}
	
	getCustomerList(data);
};

function selectCustomer(anchor, customer_id) {
	// select the customer and populate the customer dialog
	var td = 0;
	var tdhtml = 0;
	if (anchor) {
		td = $(anchor).closest('td');
		tdhtml = td.html();
	}
	
	var url = 'index.php?route=module/pos/getCustomerAjax&token=' + token + '&customer_id=' + customer_id;
	$.ajax({
		url: url,
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			if (td) {
				td.html('<div><i class="fa fa-spinner fa-spin"></i> ' + text_load_order + '</div>');
			}
		},
		complete: function() {
			if (td) {
				td.find('div').remove();
				td.html(tdhtml);
			}
		},
		cacheCallback: function(json) {
			backendSelectCustomer(json);
		},
		cachePreDone: function(cacheCache, callback) {
			backendGetCustomer(customer_id, callback);
		},
		success: function(json) {
			// save the zones info that will be referred later
			if (json['customer_addresses']) {
				for (var i in json['customer_addresses']) {
					var address = json['customer_addresses'][i];
					if (address['zones']) {
						saved_zones[address['country_id']] = address['zones'];
					}
				}
			}
			// populate customer dialog
			populateCustomerDialog(json);
			
			$('input[name=customer_id]').val(customer_id);
			
			closeFancybox();
			openFancybox('#customer_dialog', 'wide');
			$('#customer_select').html($('input[name=customer_firstname]').val());
			$('#customer_order_history').attr('onclick','');
			$('#customer_return_history').attr('onclick','');
			$('#customer_sales_notes').attr('onclick','');
			$('#customer_order_history').attr('onclick','orderHistoryTab('+customer_id+')');
			$('#customer_sales_notes').attr('onclick','saveCustomerNotes('+customer_id+')');
			$('#customer_return_history').attr('onclick','getReturnListTab('+customer_id+')');
			$(".table-btn-common.customer.reset").html('<span class="icon"></span>Reset Customer');
			$(".table-btn-common.customer.new").html('<span class="icon"></span>ADD NEW CUSTOMER');
			$("#tab_customer_id").val(customer_id);
			$("#append_customer").empty();
			$("#append_customer").removeAttr('style');
			$.ajax({
              type:'GET',
              url:'index.php?route=module/pos/getCustomerNotes&customer_id='+customer_id+'&token='+token,
              cache:false,
              success:function(data){
              	if(data!='0'){
              	$("textarea[name=customer_sales_notes]").val(data);
              }else{
              	$("textarea[name=customer_sales_notes]").val('');
              }
              }
			});
		}
	});
};

function selectBackOrderCustomer(anchor, customer_id) {
	// select the customer and populate the customer dialog
	var td = 0;
	var tdhtml = 0;
	if (anchor) {
		td = $(anchor).closest('td');
		tdhtml = td.html();
	}
	
	$('#add_to_new_customer').attr('disabled', 'disabled');

	var url = 'index.php?route=module/pos/getCustomerAjax&token=' + token + '&customer_id=' + customer_id;
	$.ajax({
		url: url,
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			if (td) {
				td.html('<div><i class="fa fa-spinner fa-spin"></i> ' + text_load_order + '</div>');
			}
		},
		success: function(json) {
			// save the zones info that will be referred later
			$('#backorder_customer_addresses').empty();
			if (json['customer_addresses']) {
				if(JSON.stringify(json['customer_addresses']).length > 2){
					$('#add_to_new_customer').removeAttr('disabled');
				}
				$.each(json['customer_addresses'], function(key, value) {
					$('#backorder_customer_addresses')
						.append($('<option>', { value : key })
						.text(value.address_1 + ' City: ' + value.city + ' Country: '  + value.country));
						$('#backorder_customer_addresses option[value='+json.customer_address_id+']').attr('selected','selected');
			   });
			}

			$('input[name=backorder_new_customer_id]').val(customer_id);
			$('input[name=selected_backorder_new_customer_name]').val(json.customer_firstname + ' ' + json.customer_lastname);
			
		},
		complete: function() {
			if (td) {
				td.find('div').remove();
				td.html(tdhtml);
			}
			$("#append_backorder_customer").empty();
			$("#append_backorder_customer").removeAttr('style');
			$('#append_backorder_customer').css('display','none');
			var selectedcustomername = $('input[name=selected_backorder_new_customer_name]').val();
			$('input[name=backorder_customer_firstname_tab]').val(selectedcustomername);
		}
	});
};

function saveCustomerNotes(customer_id){
	$("#customer_sales_notes").html("Saving...");
	$.ajax({
      type:'post',
      url:'index.php?route=module/pos/saveCustomerNotes&token='+token,
      data:$('#tab_customer_notes textarea[name=customer_sales_notes],#tab_customer_notes input[type=hidden]'),
      dataType:'json',
      cache:false,
      success:function(data){
		if(data==1){
		$("#customer_sales_notes").html("Save Customer Notes");	
		alert("Customer Notes Saved Successfully");
		}

      }
	});

};

function showProductDetails(product_id) {
	$.ajax({
		url: 'index.php?route=module/pos/getProductDetails&token=' + token + '&product_id='+product_id,
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			openWaitDialog(text_fetching_product_details);
		},
		complete: function() {
			closeWaitDialog();
		},
		cacheCallback: function(json) {
			// do not save the cache as the product info is already in the database
		},
		cachePreDone: function(cacheKey, callback) {
			backendGetProductDetails(product_id, callback);
		},
		success: function(json) {
			// display string attributes
			console.log(json);
			var dispay_attrs_string = ['name', 'sku', 'upc', 'model', 'cost', 'description', 'price', 'quantity', 'thumb', 'location','labour_cost'];
			var unique_price_discount = "unique_price_discount";
			for (i = 0; i < dispay_attrs_string.length; i++) {
				if(dispay_attrs_string[i] =="labour_cost"){
					var value = json[dispay_attrs_string[i]] ? parseFloat(json[dispay_attrs_string[i]]).toFixed(2)+" + "+parseFloat(json["unique_option_price"]).toFixed(6) : ''; 
				}else{
					var value = json[dispay_attrs_string[i]] ? json[dispay_attrs_string[i]] : '';
				}

				if ('thumb' == dispay_attrs_string[i]) {
					$('#product_details_thumb').attr('src', json['thumb']);
					$('#product_details_thumb').attr('alt', json['name']);
				} else {
					$('#product_details_' + dispay_attrs_string[i]).html($('<textarea />').html(value).text());
				}
			}
			
			// dispaly array attributes
			var html = ''
			if(json["product_attributes"] && json["product_attributes"].length > 0){
				var trClass = 'even';
				for (var i = 0; i < json['product_attributes'].length; i++) {
					trClass = (trClass == 'even') ? 'odd' : 'even';
					html += '<tr class="' + trClass + '">';
					html += '<td>' + json['product_attributes'][i]['name'] + '</td><td>' + json['product_attributes'][i]['value'] + '</td><td>' + ((json['product_attributes'][i]['required'] ==1) ? text_yes : text_no) + '</td></tr>';
				}	
			}
			/*if (json['product_options'] && json['product_options'].length > 0) {
				var trClass = 'even';
				for (var i = 0; i < json['product_options'].length; i++) {
					trClass = (trClass == 'even') ? 'odd' : 'even';
					html += '<tr class="' + trClass + '">';
					var option_value = '';
					var product_option = json['product_options'][i];
					if (product_option['type'] == 'text' ||
						product_option['type'] == 'textarea' ||
						product_option['type'] == 'file' ||
						product_option['type'] == 'date' ||
						product_option['type'] == 'datetime' ||
						product_option['type'] == 'time') {
						option_value = product_option['option_value'];
					} else if (product_option['type'] == 'select' || 
						product_option['type'] == 'radio' || 
						product_option['type'] == 'checkbox' || 
						product_option['type'] == 'image') {
						var product_option_id = parseInt(product_option['option_id']);
						if (json['option_values'][product_option_id]) {
							var option_value_value = json['option_values'][product_option_id];
							for (var k in option_value_value) {
								for (var j in product_option['product_option_value']) {
									var product_option_value = product_option['product_option_value'][j];
									if (product_option_value['option_value_id'] == option_value_value[k]['option_value_id']) {
										option_value += option_value_value[k]['name'] + '<br/>';
									}
								}
							}
						}
					}
					
					html += '<td>' + product_option['name'] + '</td><td>' + option_value + '</td><td>' + (product_option['required'] ? text_yes : text_no) + '</td></tr>';
				}
			}*/



			$('#product_details_options').html(html);
			openFancybox('#product_details_dialog', 'wide');
		}
	});
};

function showReturnDetails(anchor) {
	var return_id = $(anchor).closest('tr').find('input[name$="[return_id]"]').val();
	$.ajax({
		url: 'index.php?route=module/pos/getReturnDetails&token=' + token + '&return_id='+return_id,
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			openWaitDialog(text_fetching_return_details);
		},
		cacheCallback: function(json) {
			// do not save the cache
		},
		cachePreDone: function(cacheKey, callback) {
			backendGetReturnDetails(return_id, callback);
		},
		complete: function() {
			closeWaitDialog();
		},
		success: function(json) {
			console.log(json['product']);
			$('#return_details_product_name').val($('<div/>').html($('<div/>').html(json['product']).text()).text());
			$('#return_details_product_model').val(json['model']);
			$('#return_details_product_options').val($('<div/>').html(json['options']).text());
			$('#return_details_product_quantity').val(json['quantity']);
			$('#return_details_product_opened').val(json['opened']);
			$('#return_details_return_reason').val(json['reason']);
			$('#return_details_comment').val(json['comment']);
			$('#return_details_return_time').val(offsetDate(json['return_time']));
			$('#return_details').show();
			openFancybox('#return_details_dialog', 'narrow');
		}
	});
};

$(document).on('keydown', 'input[name=changed_quantity]', function(event) {
	amountInputOnly(event);
});

function changeQuantity(div,rownumber) {
	var quantity = $(div).find('a').text();
	var index = $('#product tr').index($(div).closest('tr')); 
	var product_id = $('input[name="order_product[' + rownumber + '][product_id]"]').val();
	var order_product_id =$('input[name="order_product[' + rownumber + '][order_product_id]"]').val();
	$.ajax({
    	type:'GET',
    	url:'index.php?route=module/pos/loadUnitConversionData&product_id='+product_id+'&order_product_id='+order_product_id+'&token='+token, 
    	dataType:'json',
    	success:function(json){
		   if( json['show_units'] == true )
		   {
			 if($("#quantity-unit-data").length == 0) {
			   $('input[name=changed_quantity]').after(json['html_data']);
			   // ali changes 02/03/2020
				$('input[name=org_unit_conversion]').val(json['org_unit_conversion']);
			  // ali changes 02/03/2020
			 }
		   } else {
			   $("#quantity-unit-data").remove();
			   // ali changes 02/03/2020
			   $('input[name=org_unit_conversion]').val('');
			   // end ali changes 02/03/2020
		   }

			$('input[name=org_quantity]').val(quantity);
			$('input[name=quantity_index]').val(index);
			$('input[name=changed_quantity]').val(quantity);
			first_click_numeric_key = true;
			openFancybox('#quantity_dialog', 'normal');
			// fancybox styling by Raza
			$('.fancybox-opened').attr('style','');
			$(".fancybox-opened").css('width','370px');
			$(".fancybox-opened").css('height','auto');
			$(".fancybox-opened").css('position','absolute');
			$(".fancybox-opened").css('top','61px');
			$(".fancybox-opened").css('left','40%');
			$(".fancybox-opened").css('opacity','1');
			$(".fancybox-opened").css('overflow','visible');
			$(".fancybox-inner").css('width','370px');
			$(".fancybox-inner").css('min-height','300px');
			$(".keypad_wrap").css('max-width','350px');
			$(".keypad_wrap .display").css('width','323px');
    	}
    }); 
	
	

};

function handleInplaceQuantity() {
	var newQuantity = parseInt($('input[name=changed_quantity]').val());
	var orgQuantity = parseInt($('input[name=org_quantity]').val());
	if (newQuantity == 0 || isNaN(newQuantity)) {
		openAlert(text_quantity_invalid);
		return;
	} else if (newQuantity != orgQuantity) {
		// change the total text
		var index = $('input[name=quantity_index]').val();
		
		if (text_work_mode == '1') {
			// if it's return for order, the new quantity cannot be greater than the quantity in the order
			var order_product_id = parseInt($('input[name="order_product[' + index + '][order_product_id]"]').val());
			if (order_product_id > 0) {
				var to_be_returned_quantity = newQuantity;
				var return_id = $('input[name="order_product[' + index + '][return_id]"]').val();
				// check the returned quantity for the product, exclude the current return id
				var data = {'order_product_id':order_product_id, 'return_id':return_id};
				$.ajax({
					url: 'index.php?route=module/pos/checkReturn&token=' + token,
					type: 'post',
					data: data,
					dataType: 'json',
					cacheCallback: function(json) {
						// do not save the cache
					},
					cachePreDone: function(cacheKey, callback) {
						// Get from the local cache to see if any return for that order_product_id
						backendCheckReturn(data, callback);
					},
					success: function(json) {
						to_be_returned_quantity += parseInt(json['quantity']);
						var varIndex = -1;
						for (var index = 0; index < browseItems.length; index ++) {
							if (browseItems[index]['order_product_id'] && browseItems[index]['order_product_id'] == order_product_id) {
								varIndex = index;
								break;
							}
						}
						if (to_be_returned_quantity > browseItems[varIndex]['quantity']) {
							openAlert(text_return_quantity_invalid.replace('%s', to_be_returned_quantity-newQuantity));
							return;
						} else {
							processInplaceQuantity(index, orgQuantity, newQuantity);
						}
					}
				});
			} else {
				processInplaceQuantity(index, orgQuantity, newQuantity);
			}
		} else {
			processInplaceQuantity(index, orgQuantity, newQuantity);
		}
	}
};

function processInplaceQuantity(index, orgQuantity, newQuantity) {
	$('.quantity_anchor_' + index).text(newQuantity);
	var td_total = $('#price_anchor_' + index).closest('tr').find('td:nth-last-child(3)');
	var ex_price = posParseFloat($('#price_anchor_'+index).text().substring(2));
	var weight = 1;
	if (parseInt($('input[name="order_product[' + index + '][weight_price]"]').val()) == 1) {
		weight = parseFloat($('input[name="order_product[' + index + '][weight]"]').val());
	}
	var text_total = formatMoney(newQuantity * ex_price * weight);
	
	var discount_type = parseInt($('input[name="order_product[' + index + '][product_discount_type]"]').val());
	if (discount_type > 0) {
		var discount_value = parseFloat($('input[name="order_product[' + index + '][product_discount_value]"]').val());
		var discount_text = '';
		var discounted_value = 0;
		if (discount_type == 1) {
			discount_text = formatMoney(discount_value);
			discounted_value = newQuantity * ex_price * weight - discount_value;
		} else if (discount_type == 2) {
			discount_text = discount_value + '%';
			discounted_value = newQuantity * ex_price * weight * (100 - discount_value) / 100;
		}
		
		var product_discount_html = '';
		product_discount_html += '<strike>' + text_total + '</strike><br />';
		product_discount_html += '<small>(' + text_discount + ': ' + discount_text + ')</small><br />';
		product_discount_html += formatMoney(discounted_value);
		//td_total.find('span').html(product_discount_html);
	} else {
		if (td_total.find('span').length > 0) {
			//td_total.find('span').text(text_total);
		} else {
			//td_total.text(text_total);
		}
	}
	td_total.find('input').val(text_total);

	if (text_work_mode == '0') {
		var data = getSelectedPostData(index);
		data['action'] = 'modify_quantity';
		data['index'] = index;
		data['quantity_before'] = orgQuantity;
		data['quantity_after'] = newQuantity;
		$('#product tr:eq('+index+') input[name$=\'[quantity]\']').val(newQuantity);
		data['coversion_id'] = $("#quantity-unit-data").find('option:selected').attr('data-value');
		checkAndSaveOrder(data);
	} else if (text_work_mode == '1') {
		var order_product_id = parseInt($('input[name="order_product[' + index + '][order_product_id]"]').val());
		var price = parseFloat($('input[name="order_product[' + index + '][price]"]').val());
		var tax = parseFloat($('input[name="order_product[' + index + '][tax]"]').val());
		
		var data = {'quantity':newQuantity, 'order_product_id':order_product_id, 'pos_return_id':pos_return_id};
		data['tax_change'] = tax * (newQuantity - orgQuantity) * weight;
		data['price_change'] = price * (newQuantity - orgQuantity) * weight;
		data['return_id'] = $('input[name="order_product[' + index + '][return_id]"]').val();
		
		// update totals
		for (var i in totals) {
			if (totals[i]['code'] == 'tax') {
				totals[i]['value'] = parseFloat(totals[i]['value']) + data['tax_change'];
			} else if (totals[i]['code'] == 'subtotal') {
				totals[i]['value'] = parseFloat(totals[i]['value']) + data['price_change'];
			} else if (totals[i]['code'] == 'total') {
				totals[i]['value'] = parseFloat(totals[i]['value']) + data['price_change'] + data['tax_change'];
			}
		}
		// save the updated total to local storage
		updateTotal(totals);
		
		$('input[name="order_product[' + index + '][quantity]"]').val(newQuantity);
		
		$.ajax({
			url: 'index.php?route=module/pos/editReturn&token=' + token,
			type: 'post',
			data: data,
			dataType: 'json',
			cacheCallback: function(json) {
				// do not save the cache
			},
			cachePreDone: function(cacheKey, callback) {
				backendEditReturn(data, callback);
			},
			success: function(json) {
				$('#items_in_cart').text(json['items_in_cart']);
				removeMessage();
				showMessage('success', json['success']);
			}
		});
	}
	closeFancybox();
};

$('#customer_select').click(function() {  
	$("input[name=customer_firstname_tab]").val(""); 
	$("#append_customer").empty();
	$("#append_customer").removeAttr('style');
});

$('#backorder_customer_select').click(function() {  
	$("input[name=backorder_customer_firstname_tab]").val(""); 
	$("#append_backorder_customer").empty();
	$("#append_backorder_customer").removeAttr('style');
});

var first_click_numeric_key = true;

$('.keypad_wrap .btn').click(function() { 
	var parent = $(this).closest('div').attr('id');
	var key = $(this).text();
	// the return discount is special
	if (key == button_discount) return;
	
	var input = $('input[name=changed_quantity]');

	if (parent == 'price_pad') {
		input = $('input[name=changed_price]');
		if ($('input[name=use_discount]').is(':checked')) {
			if ($('input[name=use_discount_type][value=percentage]').is(':checked')) {
				input = $('input[name=changed_price_discount_percentage]');
			} else {
				//input = $('input[name=changed_price_discount_fixed]');
				input = $('input[name=changed_price]');
			}
		}
		currValue = input.val();
	} else if (parent == 'payment_pad') {
		input = $('input[name=tendered_amount]');
	}
	var currValue = input.val();
		
	console.log('currValue: ' + currValue + ', key: ' + key + ', first_click_numeric_key: ' + first_click_numeric_key);
	
	if (key == 'Clear' || key == 'C') {
		input.val('0');
	} else if (key == 'OK') {
		// ali changes 02/03/2020
		if($("#quantity-unit-data" ).length){
			var org_unit_conversion=$('input[name=org_unit_conversion]').val();
			var selected_unit_conversion=$("#quantity-unit-data option:selected").attr('data-value');
			if(org_unit_conversion!=selected_unit_conversion){  
				 alert('For unit conversion, you have to delete the item from cart and add it again with the desired unit');
				 return false;
				}
			}
		// ali changes 02/03/2020
		if (parent == 'price_pad') {
			if ($('input[name=use_discount][value=change_price]').is(':checked')) {
				handleInplacePrice();
			} else {
				applyProductDiscount($('input[name=price_index]').val());
			}
		} else if (parent == 'payment_pad'){
			addPayment();
		} else {
			handleInplaceQuantity();
		}
	} else if (currValue == '0' || currValue == '0.00' || currValue == '') {
		currValue = '0';
		if (key == '.') {
			input.val(currValue+key);
		} else if (key.length == 1 && key != '0') {
			input.val(key);
		}
		if (first_click_numeric_key) {
			first_click_numeric_key = false;
		}
	} else {
		if (first_click_numeric_key) {
			first_click_numeric_key = false;
			if (key == '.') {
				input.val('0.');
			} else {
				input.val(key);
			}
		} else {
			if (key == '.') {
				if (currValue.indexOf('.') < 0) {
					input.val(currValue+key);
				}
			} else if (key.length == 1) {
				if (currValue.indexOf('.') > 0) {
					if (currValue.substring(currValue.indexOf('.')+1).length < 2) {
						input.val(currValue+key);
					}
				} else {
					input.val(currValue+key);
				}
			}
		}
	}
	
	if ( parent == 'price_pad' && input.attr('name') != 'changed_price_discount_fixed' && key != '.' ) {
		if (!$('input[name=use_discount]').is(':visible')) { return; }
		calProductDiscount($('input[name=price_index]').val());
	}
});

$(document).on('keydown', 'input[name=changed_price]', function(event) {
	amountInputOnly(event);
});
$(document).on('keydown', 'input[name=changed_price_discount_fixed]', function(event) {
	amountInputOnly(event);
});
$(document).on('keyup', 'input[name=changed_price_discount_fixed]', function(event) {
	if (!$('input[name=use_discount]').is(':visible')) { return; }
	calProductDiscount($('input[name=price_index]').val());
});
$(document).on('keydown', 'input[name=changed_price_discount_percentage]', function(event) {
	amountInputOnly(event);
});
$(document).on('keyup', 'input[name=changed_price_discount_percentage]', function(event) {
	if (!$('input[name=use_discount]').is(':visible')) { return; }
	calProductDiscount($('input[name=price_index]').val());
});

$(document).on('click', 'input[name=changed_price_discount_percentage]', function(event) {
	if ( this.value == 0 )
	{
		this.value = '';
	}
});

function applyDiscountLevel()
{
  var price = $("input[name='discount_level_radio_check']:checked").val();
  var order_product_id = $("#current_order_product_id").val();
  var data = {'price': price, 'order_product_id' : order_product_id};
  $.ajax({
	url:'index.php?route=module/pos/applyDiscountLevel&token=' + token,
	type: 'post',
	data: data,
	dataType: 'json',
	success:function(data){
		$("#discount_level_btn").html('<i class="fa fa-spinner fa-spin" style="font-size:24px"></i>');
		refreshTotal();
		location.reload();
	}
});

}

$('input[name=use_discount]').click(function(e) { 
	if ($(this).val() == 'discount_level') {
		$(".discount_form").hide();
		$("#price_pad").hide();
		$("#discount-level-html").show();
	} else {
		$(".discount_form").show();
		$("#price_pad").show();
		$("#discount-level-html").hide();
	}
	if ($(this).val() == 'use_discount') {
		$('input[name=use_discount_type]').prop('disabled', false);
		
		if (!$(this).is(':visible')) { return; }
		
		var discount_type = parseInt($('input[name="order_product[' + $('input[name=price_index]').val() + '][product_discount_type]"]').val());
		if (discount_type > 0) {
			var discount_value = $('input[name="order_product[' + $('input[name=price_index]').val() + '][product_discount_value]"]').val();
			$('input[name=use_discount_type]').prop('checked', false);
			if (discount_type == 1) {
				$('input[name=use_discount_type][value=fixed]').prop('checked', true);
				$('input[name=changed_price_discount_fixed]').val(discount_value);
			} else {
				$('input[name=use_discount_type][value=percentage]').prop('checked', true);
				$('input[name=changed_price_discount_percentage]').val(discount_value);
			}
		} else {
			if ($('#product tr:eq('+$('input[name=price_index]').val()+') input[name$=\'[product_normal_price]\']').length > 0) {
				var quantity = parseFloat($('input[name="order_product[' + $('input[name=price_index]').val() + '][quantity]"]').val());
				$('input[name=changed_price]').val(posParseFloat($('#product tr:eq('+$('input[name=price_index]').val()+') input[name$=\'[product_normal_price]\']').val()) * quantity);
			} else {
				$('input[name=changed_price]').val(posParseFloat($('#product tr:eq('+$('input[name=price_index]').val()+') td:nth-last-child(2)').text()));
			}
		}
		calProductDiscount($('input[name=price_index]').val());
	} else {
		//$('input[name=use_discount_type]').prop('disabled', true);
		//$('input[name=changed_price]').val($('input[name=org_price]').val());
		
		$('input[name=use_discount_type]').prop('disabled', false);
		
		if (!$(this).is(':visible')) { return; }
		
		var discount_type = parseInt($('input[name="order_product[' + $('input[name=price_index]').val() + '][product_discount_type]"]').val());
		if (discount_type > 0) {
			var discount_value = $('input[name="order_product[' + $('input[name=price_index]').val() + '][product_discount_value]"]').val();
			$('input[name=use_discount_type]').prop('checked', false);
			if (discount_type == 1) {
				$('input[name=use_discount_type][value=fixed]').prop('checked', true);
				$('input[name=changed_price_discount_fixed]').val(discount_value);
			} else {
				$('input[name=use_discount_type][value=percentage]').prop('checked', true);
				$('input[name=changed_price_discount_percentage]').val(discount_value);
			}
		} else {
			if ($('#product tr:eq('+$('input[name=price_index]').val()+') input[name$=\'[product_normal_price]\']').length > 0) {
				$('input[name=changed_price]').val(posParseFloat($('#product tr:eq('+$('input[name=price_index]').val()+') input[name$=\'[product_normal_price]\']').val()));
			} else {
				$('input[name=changed_price]').val(posParseFloat($('#product tr:eq('+$('input[name=price_index]').val()+') td:nth-last-child(2)').text()));
			}
		}
		calProductDiscount($('input[name=price_index]').val());
	
	}
});

function changePrice(div) {
	var price = posParseFloat($(div).find('a').text());
	var index = $('#product tr').index($(div).closest('tr'));
	$('#popup_discount_selection_bottom_order_total').show();
	$('input[name=org_price]').val(price);
	$('input[name=price_index]').val(index);
	$('input[name=changed_price]').val(price);  
	$('input[name=changed_price]').css("width","189px");
	//$("input[name=popup_discount_selection_bottom][value=order_total]").prop("checked",true);  
	$(".popup_discount_selection_bottom").hide(); 
	first_click_numeric_key = true;
	$('#price_discount_dialog .price_options').show();
	$('#price_discount_dialog input[name=changed_price]').show();
	$('#button_ok').show();
	$('#button_discount_apply').hide();
	$('#popup_discount_selection_bottom_discount_group').hide();
	$('#price_discount_dialog h3').text(text_change_price_title);
	


	// not support product discount function, only the change price in return mode
	if (text_work_mode == '1') {
		$('#price_discount_dialog input[name=use_discount][value=use_discount]').prop('disabled', true);
	} else {
		$('#price_discount_dialog input[name=use_discount][value=use_discount]').prop('disabled', false);
	}

	openFancybox('#price_discount_dialog', 'normal');
	// after the dialog is open, calculate the values because the button visibility is used to calculate the product level discount
	var discount_type = parseInt($('input[name="order_product[' + index + '][product_discount_type]"]').val());
    var productid=$('input[name="order_product[' + index + '][product_id]"]').val();
	var order_product_id =$('input[name="order_product[' + index + '][order_product_id]"]').val();
	var dis_quantity=$('input[name="order_product[' + index + '][quantity]"]').val();
	var current_customer_group_id = $('select[name=customer_group_id]').val();
	$("#discount_customer_group_id").hide();
	$("#entry_customer_group").hide();
    /* $.ajax({
    	type:'GET',
    	url:'index.php?route=module/pos/checkIfCustomerIsWholesaleAndHasDiscount&customer_id='+customer_id+'&product_id='+productid+'&qty='+dis_quantity+'&token='+token,
    	dataType:'json',
    	cache:false,
    	success:function(data){
           $(".inputbox .price_disc_radio").attr('checked','checked');
           $(".inputbox .price_disc_radio").trigger('click');
           $("input[name=changed_price_discount_percentage]").val(data);
           $("input[name=changed_price_discount_percentage]").focus().keyup();
           $('input[name=use_discount_type][value=percentage]').attr('checked','checked');
           $('input[name=use_discount_type][value=percentage]').trigger('click');
    	}
	}); */ 
	
	$.ajax({
    	type:'GET',
    	url:'index.php?route=module/pos/loadDiscountLevelData&customer_group_id='+current_customer_group_id+'&product_id='+productid+'&order_product_id='+order_product_id+'&quantity='+dis_quantity+'&token='+token,
    	dataType:'json',
    	success:function(html){
		   $("#discount-level-html").empty();
           $("#discount-level-html").html(html);
    	}
    }); 

	if (discount_type > 0) {
		// already has product discount
		$('input[name=use_discount][value=use_discount]').trigger('click');
	} else {
		//$('input[name=use_discount][value=change_price]').trigger('click');
		$('input[name=use_discount][value=discount_level]').trigger('click');
	}
	$('input[name=use_discount]').removeAttr("checked");
	$('input[name=use_discount]').filter('[value=discount_level]').prop('checked', true);
	$(".discount_form").hide();
	$("#price_pad").hide();

};

function handleInplacePrice() {
	var newPrice = $('input[name=changed_price]').val();
	if (newPrice > 0) {
		// change the total text
		var index = $('input[name=price_index]').val();
		//$('#price_anchor_' + index).text('@ ' + formatMoney(newPrice));
		var weight = 1;
		if (parseInt($('input[name="order_product[' + index + '][weight_price]"]').val()) == 1) {
			weight = parseFloat($('input[name="order_product[' + index + '][weight]"]').val());
		}

		var td_total = $('#price_anchor_' + index).closest('tr').find('td:nth-last-child(2)');
		var text_quantity = $('.quantity_anchor_' + index).text();
		var text_total = formatMoney(parseFloat(text_quantity) * newPrice * weight);
		if (td_total.find('strike').length) {
			var discount_type = parseInt($('input[name="order_product[' + index + '][product_discount_type]"]').val());
			if (discount_type > 0) {
				var discount_value = parseFloat($('input[name="order_product[' + index + '][product_discount_value]"]').val());
				var discount_text = '';
				var discounted_value = 0;
				if (discount_type == 1) {
					discount_text = formatMoney(discount_value);
					discounted_value = parseFloat(text_quantity) * newPrice * weight - discount_value;
				} else if (discount_type == 2) {
					discount_text = discount_value + '%';
					discounted_value = parseFloat(text_quantity) * newPrice * weight * (100 - discount_value) / 100;
				}
				
				var product_discount_html = '';
				product_discount_html += '<strike>' + text_total + '</strike><br />';
				product_discount_html += '<small>(' + text_discount + ': ' + discount_text + ')</small><br />';
				product_discount_html += formatMoney(discounted_value);
				td_total.find('span').html(product_discount_html);
			} else {
				td_total.find('span').text(text_total);
			}
		} else {
			td_total.find('span').text(text_total);
		}
		// always update the total hidden value
		td_total.find('input').val(text_total);
		
		if (text_work_mode == '0') {
			// modify price
			var data = getSelectedPostData(index);
			data['action'] = 'modify_price';
			data['price_after'] = newPrice;
			data['product_id'] = parseInt($('input[name="order_product[' + index + '][product_id]"]').val());
			data['order_product_id'] = parseInt($('input[name="order_product[' + index + '][order_product_id]"]').val());
			data['quantity'] = parseInt($('input[name="order_product[' + index + '][quantity]"]').val());
			checkAndSaveOrder(data);
			location.reload();
		} else if (text_work_mode == '1') {
			var order_product_id = parseInt($('input[name="order_product[' + index + '][order_product_id]"]').val());
			var return_id = parseInt($('input[name="order_product[' + index + '][return_id]"]').val());
			var order_id = parseInt($('input[name="order_product[' + index + '][order_id]"]').val());
			var product_id = parseInt($('input[name="order_product[' + index + '][product_id]"]').val());
			var price = parseFloat($('input[name="order_product[' + index + '][price]"]').val());
			var tax = parseFloat($('input[name="order_product[' + index + '][tax]"]').val());
			var tax_class_id = parseInt($('input[name="order_product[' + index + '][tax_class_id]"]').val());
			
			var data = {'order_product_id':order_product_id, 'pos_return_id':pos_return_id, 'return_id':return_id, 'order_id':order_id, 'product_id':product_id, 'price':price, 'tax':tax, 'customer_group_id':customer_group_id, 'tax_class_id':tax_class_id};

			data['new_total'] = parseFloat(text_quantity) * newPrice * weight;
			data['org_quantity'] = parseInt(text_quantity);
			data['weight'] = weight;
	
			$.ajax({
				url: 'index.php?route=module/pos/editReturn&token=' + token,
				type: 'post',
				data: data,
				dataType: 'json',
				cacheCallback: function(json) {
					// do not save the cache
				},
				cachePreDone: function(cacheKey, callback) {
					backendEditReturn(data, callback);
				},
				success: function(json) {
					removeMessage();
					showMessage('success', json['success']);
	
					// update totals
					for (var tIndex in totals) {
						if (totals[tIndex]['code'] == 'tax') {
							totals[tIndex]['value'] = parseFloat(totals[tIndex]['value']) + parseFloat(json['tax_change']);
						} else if (totals[tIndex]['code'] == 'subtotal') {
							totals[tIndex]['value'] = parseFloat(totals[tIndex]['value']) + parseFloat(json['price_change']);
						} else if (totals[tIndex]['code'] == 'total') {
							totals[tIndex]['value'] = parseFloat(totals[tIndex]['value']) + parseFloat(json['price_change']) + parseFloat(json['tax_change']);
						}
					}
					updateTotal(totals);
					
					$('input[name="order_product[' + index + '][price]"]').val(price + parseFloat(json['price_change']) / parseInt(text_quantity) / weight);
					$('input[name="order_product[' + index + '][tax]"]').val(tax + parseFloat(json['tax_change']) / parseInt(text_quantity) / weight);
					$('#product-row' + index + ' td:nth-last-child(2)').text(formatMoney(data['new_total']));
				}
			});
		}
	} else {
		openAlert(text_price_invalid);
		return;
	}
	
	closeFancybox();
};

function deleteOrderProduct(anchor) {
	var index = $('#product tr').index($(anchor).closest('tr'));
	console.log('index: ' + index);
	var data = getSelectedPostData(index);
	// before remove, set the style for the rows
	for (var i = index+1; i < $('#product tr').length; i++) {
		if ($('#product tr:eq(' + i + ')').hasClass('odd')) {
			$('#product tr:eq(' + i + ')').removeClass('odd').addClass('even');
		} else {
			$('#product tr:eq(' + i + ')').removeClass('even').addClass('odd');
		}
	}
	$(anchor).closest('tr').remove();
	data['action'] = 'delete';
	checkAndSaveOrder(data);
	location.reload();
};

function deleteReturnProduct(anchor) {
	var index = $('#product tr').index($(anchor).closest('tr'));
	var quantity = parseInt($('input[name="order_product[' + index + '][quantity]"]').val());
	var weight = 1;
	if (parseInt($('input[name="order_product[' + index + '][weight_price]"]').val()) == 1) {
		weight = parseFloat($('input[name="order_product[' + index + '][weight]"]').val());
	}
	var return_id = parseInt($('input[name="order_product[' + index + '][return_id]"]').val());
	var price = parseFloat($('input[name="order_product[' + index + '][price]"]').val());
	var tax = parseFloat($('input[name="order_product[' + index + '][tax]"]').val());
	
	var data = {'return_id':return_id, 'action':'delete', 'pos_return_id':pos_return_id};
	data['tax_change'] = 0 - tax * quantity * weight;
	data['price_change'] = 0 - price * quantity * weight;

	$(anchor).closest('tr').remove();
	
	// update totals
	for (var i in totals) {
		if (totals[i]['code'] == 'tax') {
			totals[i]['value'] = parseFloat(totals[i]['value']) + data['tax_change'];
		} else if (totals[i]['code'] == 'subtotal') {
			totals[i]['value'] = parseFloat(totals[i]['value']) + data['price_change'];
		} else if (totals[i]['code'] == 'total') {
			totals[i]['value'] = parseFloat(totals[i]['value']) + data['price_change'] + data['tax_change'];
		}
	}
	updateTotal(totals);
	
	$.ajax({
		url: 'index.php?route=module/pos/editReturn&token=' + token,
		type: 'post',
		data: data,
		dataType: 'json',
		cacheCallback: function(json) {
			// do not save the cache
		},
		cachePreDone: function(cacheKey, callback) {
			backendEditReturn(data, callback);
		},
		success: function(json) {
			removeMessage();
			showMessage('success', json['success']);
		}
	});
};

function getSelectedPostData(index) {
	console.log('index: ' + index);
	var data = {};
	$('#product tr:eq(' + index + ') input').each(function() {
		var attr_name = $(this).attr('name');
		var index_bracket = attr_name.indexOf(']');
		if (index_bracket > 0) {
			attr_name = attr_name.substring(index_bracket+2);
			var index_bracket = attr_name.indexOf(']');
			attr_name = attr_name.substring(0, index_bracket) + attr_name.substring(index_bracket+1);
		}
		
		data[attr_name] = $(this).val();
	});
	console.log(JSON.stringify(data));
	return data;
};

function showTotals() {
	openFancybox('#totals_details_dialog', 'narrow');
};

function setDiscount() {
	// set values on price_discount_dialog and bring it up, select the use_discount to make sure the use_discount_type radios are enabled
	$("#discount_customer_group_id").show();
	$(".popup_discount_selection_bottom").show();
	$("#entry_customer_group").show();
	$('input[name=use_discount][value=use_discount]').trigger('click');
	var discountType = 'fixed';
	$('input[name=changed_price_discount_fixed]').val(0);
	$('input[name="changed_price_discount_percentage"]').val(0);
	if (totals) {
		for (var i in totals) {
			if (totals[i]['code'] == 'pos_discount_fixed' || totals[i]['code'] == 'pos_discount_percentage') {
				if (totals[i]['code'] == 'pos_discount_fixed') {
					$('input[name=changed_price_discount_fixed]').val(0-toFixed(totals[i]['value'], 2));
				} else {
					var index1 = totals[i]['title'].indexOf('(');
					var index2 = totals[i]['title'].indexOf(')');
					if (index1 > 0 && index2 > index1+2) {
						$('input[name="changed_price_discount_percentage"]').val(totals[i]['title'].substring(index1+1, index2-1));
					} else {
						$('input[name="changed_price_discount_percentage"]').val(0);
					}
					discountType = 'percentage';
				}
			}
		}
	}

	$('input[name=use_discount_type]').prop('checked', false);
	if (discountType == 'fixed') {
		$('input[name=use_discount_type][value="fixed"]').prop('checked', true);
	} else if (discountType == 'percentage'){
		$('input[name=use_discount_type][value="percentage"]').prop('checked', true);
	}
	
	first_click_numeric_key = true;
	$('#price_discount_dialog .price_options').hide();
	$('#price_discount_dialog input[name=changed_price]').hide();
	$('#button_ok').hide();
	$('#button_discount_apply').show();
	$('#price_discount_dialog h3').text(text_order_discount_title);
	openFancybox('#price_discount_dialog', 'normal');
};

function applyDiscount() {
	// add for Maximum Discount begin
	// before apply the discount, check the given discount against the discount limit
	var m_Text = $("input[name='discount_customer_group_id']:checked").parent('label').text();
	var max_discount_fixed = parseFloat(max_discount_fixed);
	var max_discount_percentage = parseFloat(max_discount_percentage);
	var cur_discount_fixed = parseFloat($('input[name=changed_price_discount_fixed]').val());
	var cur_discount_percentage = parseFloat($('input[name=changed_price_discount_percentage]').val());
	if (($('input[name=use_discount_type][value=fixed]').is(':checked') && isNaN(cur_discount_fixed)) ||
		($('input[name=use_discount_type][value=percentage]').is(':checked') && isNaN(cur_discount_percentage))) {
		openAlert(text_discount_value_invalid);
		return;
	}
	if ((max_discount_fixed > 0 && cur_discount_fixed > max_discount_fixed) ||
		(max_discount_percentage > 0 && cur_discount_percentage > max_discount_percentage)) {
		openAlert(text_max_discount_limit);
		return 'continue';
	}
	// add for Maximum Discount end
	// provide order_id, code, title and value
	var data = {};
	
	// the following is required for update total in the backend
	data['order_id'] = order_id;
	//data['customer_id'] = customer_id;
	//data['customer_group_id'] = customer_group_id;
	data['customer_id'] = $('input[name=customer_id]').val(); //customer_id;
	data['customer_group_id'] = $('select[name=customer_group_id]').val(); //customer_group_id;
	data['discount_customer_group_id'] = $('input[name=discount_customer_group_id]:checked').val();
	data['shipping_country_id'] = shipping_country_id;
	data['shipping_zone_id'] = shipping_zone_id;
	data['payment_country_id'] = payment_country_id;
	data['payment_zone_id'] = payment_zone_id;
	data['currency_code'] = currency_code;
	data['currency_value'] = currency_value;
	
	data['code'] = 'pos_discount_fixed';
	data['title'] = text_discount + ' (' + $('input[name=changed_price_discount_fixed]').val() + ')';
	data['value'] = 0- parseFloat($('input[name=changed_price_discount_fixed]').val());
	if ($('input[name=use_discount_type]:checked').val() == 'percentage') {
		data['code'] = 'pos_discount_percentage';
		var percentage = toFixed(parseFloat($('input[name=changed_price_discount_percentage]').val()), 2);
		if (percentage > 100) {
			percentage = 100;
		}
		data['title'] = text_discount + ' (' + percentage + '%)';
		data['value'] = percentage;
	}
	// call apply discount operation
	$.ajax({
		url: 'index.php?route=module/pos/applyDiscount&token=' + token,
		type: 'post',
		dataType: 'json',
		data: data,
		beforeSend: function() {
			openWaitDialog(text_apply_discount);
		},
		complete: function() {
			closeWaitDialog();
		},
		cacheCallback: function(json) {
			// don't need to cache the result
		},
		cachePreDone: function(cacheKey, callback) {
			backendApplyDiscount(data, callback);
		},
		success: function(json) {
			removeMessage();
			if (json['error']) {
				showMessage('error', json['error']);
			} else {
				// update total html
				updateTotal(json['totals']);
				showMessage('success', json['success']);
				// also update the javascript variables to be used later
				discount_type = (data['code'] == 'pos_discount') ? 'amount' : 'percentage';
				discount_value = (discount_type == 'amount') ? parseFloat($('input[name=discount_amount_value]').val()) : percentage;
				$("#order_discount_amount").text(json['discount_amount']);
				$("#order_discount_name").html(m_Text);
				saveCustomer(true);
			}
			closeFancybox();
		}
	});
};

// add for product based discount begin
function applyProductDiscount(index) {
	var data = {};
	var discount_type = 1;
	var discount_value = parseFloat($('input[name=changed_price_discount_fixed]').val());
	if ($('input[name=use_discount_type]:checked').val() == 'fixed') {
		$('#product tr:eq('+index+')').find("input[name$='[product_discount_type]']").val(1);
		$('#product tr:eq('+index+')').find("input[name$='[product_discount_value]']").val(discount_value);
	} else if ($('input[name=use_discount_type]:checked').val() == 'percentage') {
		$('#product tr:eq('+index+')').find("input[name$='[product_discount_type]']").val(2);
		$('#product tr:eq('+index+')').find("input[name$='[product_discount_value]']").val($('input[name=changed_price_discount_percentage]').val());
		discount_type = 2;
		discount_value = parseFloat($('input[name=changed_price_discount_percentage]').val());;
	}
	if (discount_value == 0) {
		var total_text_without_discount = formatMoney($('input[name=changed_price]').val());
		var product_total_html = '<span class="product-price" id="total_text_only-' + index + '">' + total_text_without_discount + '</span>';
		product_total_html += '<input type="hidden" name="order_product[' + index+ '][product_total_text]" value="' + total_text_without_discount + '" />';
		$('#product tr:eq('+index+') td:nth-last-child(2)').html(product_total_html);
	} else {
		var discount_text = '';
		if (discount_type == 1) {
			discount_text = formatMoney(discount_value);
		} else if (discount_type == 2) {
			discount_text = discount_value + '%';
		}

		var before_discount_text = $('#product tr:eq('+index+')').find("input[name$='[product_total_text]']").val();
		var product_discount_html = '<span class="product-price">';
		product_discount_html += '<strike>' + before_discount_text + '</strike><br/>';
		product_discount_html += '<small>(' + text_discount + ': ' + discount_text + ')</small><br/>';
		product_discount_html += formatMoney($('input[name=changed_price]').val()) + '</span>';
		product_discount_html += '<input type="hidden" name="order_product[' + index+ '][product_total_text]" value="' + before_discount_text + '" />';
		//$('#product tr:eq('+index+') td:nth-last-child(2) span').html(product_discount_html);
		//$("#normal_total_text_only-"+index).remove();
	}
	
	// save discount info and update total
	data['product_id'] = parseInt($('input[name="order_product[' + index + '][product_id]"]').val());
	data['order_product_id'] = parseInt($('input[name="order_product[' + index + '][order_product_id]"]').val());
	data['quantity'] = parseInt($('input[name="order_product[' + index + '][quantity]"]').val());
	data['weight'] = 1;
	if ($('#product tr:eq('+index+')').find("input[name$='[weight]']").val()) {
		data['weight'] = $('#product tr:eq('+index+')').find("input[name$='[weight]']").val();
	}
	data['discount_type'] = discount_type;
	data['discount_value'] = discount_value;

	data['order_id'] = order_id;
	data['customer_id'] = customer_id;
	data['customer_group_id'] = customer_group_id;
	data['shipping_country_id'] = shipping_country_id;
	data['shipping_zone_id'] = shipping_zone_id;
	data['payment_country_id'] = payment_country_id;
	data['payment_zone_id'] = payment_zone_id;
	data['currency_code'] = currency_code;
	data['currency_value'] = currency_value;
	$.ajax({
		url: 'index.php?route=module/pos/applyProductDiscount&token=' + token,
		type: 'post',
		data: data,
		dataType: 'json',
		cacheCallback: function(json) {
			// do not save the discount result
		},
		cachePreDone: function(cacheKey, callback) {
			backendApplyProductDiscount(data, callback);
		},
		success: function(json) {
			updateTotal(json['order_total']);
			location.reload();
		},
		error: function(xhr, ajaxOptions, thrownError) {
			openAlert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
	
	closeFancybox();
};
// add for product based discount end

function updateTotal(updated_totals) { 
	// update total from the discount value
	html = '';
	$("#total").empty();
	
	var total_text = formatMoney(0);
	
	var addCR = '';
	if (text_work_mode == '1') {
		addCR = ' CR';
	}
	
	for (var i = 0; i < updated_totals.length; i++ ) {
		var total = updated_totals[i];
		
		var trClass = (i % 2 == 0) ? 'odd' : 'even';
		if (total['code'] == 'total') {
			total_text = formatMoney(total['value']);
			window['total'] = total['value'];
			trClass += ' total';
			html += '<tr id="total-row' + i + '" class="' + trClass + '">';
			html += '  <td>' + total['title'] + ':</td>';
			html += '  <td>' + (total['text'] ? total['text'] : formatMoney(total['value'])) + addCR + '</td>';
			html += '</tr>';
		}
		if (total['code'] == 'shipping') {
		
			$('#shipping_total').html(total['text']);
			$('#shipping_total_res').html(total['text']);
			$('.shippingTr').show();
		}
		
		if (total['code'] == 'sub_total') {
			$('#subtotal_total').html(total['text']);
			$('#subtotal_total_res').html(total['text']);
			$('.SubtotalTr').show();
		}
		
		if (total['code'] == 'tax' && total['text'] != '$0.00') {
			$('#tax_total').html(total['text']);
			$('#tax_total_res').html(total['text']);
			$('.taxTr').show();
			$('#resale_id_number').show();
		}
		
		if (total['code'] == 'total') {
			$('#dialog_order_total').html(total['text']);
			$('#payment_total').html(total['text']);
		}

	}
	
	//For Tax
	var show_tax = false;
	for (var i = 0; i < updated_totals.length; i++ ) {
		var total = updated_totals[i];
		if (total['code'] == 'tax')
		{
			show_tax = true;
			break;
		}
	}
	
	if(!show_tax)
	{
		//$('.taxTr').hide();
		$('#resale_id_number').hide();
	}
	
	$('#total').html(html);
	
	$('#payment_total span').text(total_text + addCR);
	
	// also update the js variable
	totals = updated_totals;

	// recalculate the due amount
	calcDueAmount();
};

function printReceipt(ex_id) {
	if (text_work_mode == '0' || text_work_mode == '2') {
		var print_order_id = ex_id ? ex_id : order_id;
		if (config['config_print_type'] == 'invoice' || text_work_mode == '2') {
			var url = 'index.php?route=module/pos/invoice&token=' + token + '&order_id=' + print_order_id + '&work_mode=' + text_work_mode;
			window_print_url(print_invoice_message, url, {'order_id':print_order_id}, afterPrintReceipt, null);
		} else  {
			var url = 'index.php?route=module/pos/receipt&token=' + token + '&order_id=' + print_order_id + '&work_mode=' + text_work_mode;
			window_print_url(print_receipt_message, url, {'order_id':print_order_id, 'change':'1'}, afterPrintReceipt, null);
		}
	} else if (text_work_mode == '1') {
		var print_return_id = ex_id ? ex_id : pos_return_id;
		var url = 'index.php?route=module/pos/receipt&token=' + token + '&pos_return_id=' + print_return_id + '&work_mode=' + text_work_mode;
		window_print_url(print_receipt_message, url, {'pos_return_id':pos_return_id}, afterPrintReceipt, null);
	}
};

function completeOrder() {
	if (text_work_mode == '0') {
		saveOrderStatus(complete_status_id);
	} else if (text_work_mode == '2') {
		saveQuoteStatus(quote_complete_status_id);
	}
};

function closeOrder() {
	refreshPage('order');
};

function backToPaymentPage()
{
	$("#payment_action_div").show();
	$(".button_receipt_complete_dev").show();
	$(".payment_head").show();
	$("#save_custom_button").hide();
	//$('a[id^=button_order_action_]').addClass('hidden');
	$("#order_payments_dialog h3").html("Make Payment");
	$(".receipt_div").remove();
	resizeFancybox();
}


function confirm_order_status()
{
	addPayment();
	openFancybox('#order_status_confirm_dialog', 'normal');
}

function choose_order_status()
{
	addPayment();
	openFancybox('#order_status_update_dialog', 'normal');
}

function choose_receipt(){
	$("#payment_action_div").hide();
	$(".button_receipt_complete_dev").hide();
	$(".payment_head").hide();
	$('a[id^=button_order_action_]').removeClass('hidden');
	$("#order_payments_dialog h3").html("Choose Receipt");
	$(".receipt_div").remove();
	htmlScript = "";
	htmlScript += '<div class="receipt_div">';

					htmlScript += '<div class="col-sm-4">';				
					htmlScript += '<a style="width: 195px;" id="button_email_receipt" onclick="emailReceipt();" class="table-btn-common email"><span class="icon"></span> Email Receipt</a>';
					htmlScript += '</div>';
					htmlScript += '<div class="col-sm-8 padding10px">';
                   	htmlScript += '<a style="width: 195px;" id="button_print_receipt" onclick="printReceipt();" class="table-btn-common"><span class="icon"></span><i class="fa fa-print fa-2x"></i> Print Receipt</a>';
					htmlScript += '</div>';
					htmlScript += '<div class="col-sm-4">';
						
					htmlScript += '<a style="width: 195px;" id="button_email_print_receipt" onclick="emailReceipt();printReceipt();" class="table-btn-common email print"><span class="icon"></span> <i class="fa fa-print fa-2x"></i>  Email + Print</a>';
					htmlScript += '</div>';
					htmlScript += '<div class="col-sm-8 padding10px">';
                     
                    htmlScript += '<a style="width: 195px;" id="button_email_receipt" onclick="$.fancybox.close();" class="table-btn-common"> No Receipt</a>';
					
					htmlScript += '</div>';
					htmlScript += '<div class="fbox_btn_wrap"><a class="table-btn-common pull-left"  onclick="choose_order_status();" href="javascript:void(0);"><i class="fa fa-arrow-left" aria-hidden="true"></i> &nbsp;Back To Order Status</a></div>';
					htmlScript += '</div>';
				    $("#payment_action_div").after(htmlScript);
				    $("#save_custom_button").show();
					resizeFancybox();
	$(".fancybox-close").prop("onclick", null);
	$(".fancybox-close").attr("onclick","fencyCloseButton()");
}

function clickPaymentbtn(post_status_id){
	$("#save_payment_btn").prop("onclick", null);
	//$("#save_payment_btn").attr("onclick","postPayment("+post_status_id+")");
	$(".table-btn-common-1").removeClass("selected");
	$("#button_order_action_"+post_status_id).addClass("selected");
	postPayment(post_status_id);
};

function applySelected(){
	$("#button_order_action_complete").addClass("selected");
	$(".btn-proceed-confirm").prop('disabled', false)
}

function postPayment(post_status_id) {
	//closeFancybox();
	
	if (text_work_mode == '0') {
		saveOrderStatus(post_status_id);
	} else if (text_work_mode == '1') {
		saveReturnStatus(post_status_id);
	}
};

var firstPaymentCashClick = true;
function getPaymentCash(value, display) {
	var curValue = parseFloat($('input[name=tendered_amount]').val());
	if (isNaN(curValue)) {
		curValue = 0;
	}
	
	var count = 1;
	if ($('input[name="bx10"]').is(':checked')) {
		count = 10;
	} else if ($('input[name="bx5"]').is(':checked')) {
		count = 5;
	}
	
	if (firstPaymentCashClick) {
		firstPaymentCashClick = false;
		$('input[name=tendered_amount]').val(toFixed(count * parseFloat(value), 2));
	} else {
		$('input[name=tendered_amount]').val(toFixed(curValue + count * parseFloat(value), 2));
	}
	
	// add the current selected cash to list and sort it by value
	var index = -1;
	for (var i = 0; i < cashList.length; i++) {
		if(cashList[i]['value'] == parseFloat(value)) {
			index = i;
			break;
		}
	}
	if (index >= 0) {
		cashList[index]['count'] += count;
	} else {
		cashList.push({'value':parseFloat(value), 'count':count, 'display':display});
		cashList.sort(function(a,b) {return b.value - a.value;});
	}
	console.log(cashList);
	// refresh the cash list
	$('#cash_display_tr').remove();
	var cashDisplayHtml = '<tr class="first-row" id="cash_display_tr"><td></td><td colspan="2">';
	for (var i = 0; i < cashList.length; i++) {
		cashDisplayHtml += '<span class="notes">' + cashList[i]['display'] + ' x ' + cashList[i]['count'] + '</span>';
	}
	cashDisplayHtml += '</td><td></td></tr>';
	$('#button_add_payment_tr').after(cashDisplayHtml);
};

function validateResale() {
	var resale_id_number = $('input[name=resale_id_number]').val();
	var data = {'resale_id_number': resale_id_number, 'order_id' : order_id};
	var url = 'index.php?route=module/pos/validateResale&token=' + token;
	$.ajax({
		url: url,
		type: 'post',
		data: data,
		dataType: 'json',
		beforeSend: function() {
			openWaitDialog();
		},
		complete: function() {
			closeWaitDialog();
		},
		success: function(json) {
			if (json['error']) {
				openAlert(json['error']);
			} else {
				openAlert(json['success']);
				closeFancybox();
				refreshTotal();
			}
		}
	});	
}

function validateResaleImage() {
	var resale_id_number = $('input[name=isUploadedResale]').val();
	var isUploaded = $('input[name=isUploaded]').val();
	var data = {'resale_id_number': resale_id_number, 'order_id' : order_id,'isUploaded' : isUploaded};
	var url = 'index.php?route=module/pos/validateResaleImage&token=' + token;
	$.ajax({
		url: url,
		type: 'post',
		data: data,
		dataType: 'json',
		beforeSend: function() {
			openWaitDialog();
		},
		complete: function() {
			closeWaitDialog();
		},
		success: function(json) {
			if (json['error']) {
				openAlert(json['error']);
			} else {
				$('#tax_total_res').html("$0.00");
				openAlert(json['success']);
				closeFancybox();
				refreshTotal();
			}
		}
	});	
}

function makePayment() {

	if (text_work_mode == '2') {
		// convert quote to order
		convertQuote2Order({'order_id': order_id});
	} else {
		cashList = [];
		if (text_work_mode == '1') {
			$('select[name=return_action_id]').val(return_action_id);
			$('select[name=return_action_id] opton[value=' + return_action_id + ']').attr('selected', 'selected');
			$('#return_action_div').show();
			if (parseInt(return_action_id) == 1) {
				$('#payment_action_div').show();
			} else {
				$('#payment_action_div').hide();
			}
			$('a[id^=button_return_action_]').show();
			$('a[id^=button_order_action_]').hide();
			if (parseInt(return_action_id) > 0) {
				$('#post_payment_action_div').show();
			} else {
				$('#post_payment_action_div').hide();
			}
		} else {
			$('#return_action_div').hide();
			$('a[id^=button_return_action_]').hide();
			$('a[id^=button_order_action_]').show();
			// select cash payment if it's there, otherwise select the first payment type
			var hasCash = false;
			$('#payment_type option').each(function() {
				if ($(this).val() == 'cash') {
					hasCash = true;
					$('#payment_type').val('cash');
					$('#payment_type').trigger('change');
				}
			});
			if (!hasCash) {
				$('#payment_type').val($('#payment_type option:first').val());
				$('#payment_type').trigger('change');
			}
		}
		calcDueAmount();
		firstPaymentCashClick = true;
		openFancybox('#order_payments_dialog', 'wider');
	}
};

function showPaymentsDetails(payment_order_id) {
	$.ajax({
		url: 'index.php?route=module/pos/getPaymentsDetails&token=' + token + '&order_id='+payment_order_id,
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			openWaitDialog(text_fetching_payment_details);
		},
		complete: function() {
			closeWaitDialog();
		},
		cacheCallback: function(json) {
			// do not save the payment details as it might be changed
		},
		cachePreDone: function(cacheKey, callback) {
			backendGetPaymentsDetails(payment_order_id, callback);
		},
		success: function(json) {
			if (json && json['payments'] && json['payments'].length > 0) {
				var html = '';
				var trClass = 'even';
				for (var i in json['payments']) {
					var payment = json['payments'][i];
					trClass = (trClass == 'even') ? 'odd' : 'even';
					html += '<tr class="' + trClass + '">';
					html += '<td>' + payment['payment_type'] + '</td>';
					html += '<td>' + formatMoney(payment['tendered_amount']) + '</td>';
					html += '<td>' + payment['payment_note'] + '</td>';
					html += '<td>' + offsetDate(payment['payment_time']) + '</td>';
					html += '</tr>'
				}
				$('#details_payment_list').html(html);
			} else {
				$('#details_payment_list').html('<tr class="odd"><td colspan="4" align="center">' + text_no_order_payment_found + '</td></tr>');
			}
			openFancybox('#order_payments_details_dialog', 'wide', false, false, 'makePayment');
		}
	});
};

$(document).on('change', 'select[name=return_action_id]', function() {
	var post_return_action_id = $(this).val();
	if (parseInt(post_return_action_id)) {
		var data = {'pos_return_id': pos_return_id, 'return_action_id': post_return_action_id};
		$.ajax({
			url: 'index.php?route=module/pos/saveReturnAction&token=' + token,
			type: 'post',
			dataType: 'json',
			data: data,
			cacheCallback: function(json) {
				// do not save the payment details as it might be changed
			},
			cachePreDone: function(cacheKey, callback) {
				backendSaveReturnAction(data, callback);
			},
			success: function(json) {
				if (json['affected'] && parseInt(json['affected'])) {
					return_action_id = post_return_action_id;
				}
			}
		});
		if (parseInt(post_return_action_id) == 1) {
			$('#payment_action_div').show();
		} else {
			$('#payment_action_div').hide();
		}
		$('#post_payment_action_div').show();
	} else {
		$('#post_payment_action_div').hide();
	}
	resizeFancybox();
});

function addPayment(print_order_id) {
	var amount = $('#tendered_amount').val();
	var dueAmount = $('#payment_due_amount').text();
	dueAmount = posParseFloat(dueAmount);
	if (dueAmount <= 0) {
		// nothing can be added
		return false;
	} else {
		// clear the cash display row
		$('#cash_display_tr').remove();
		cashList = [];
		// check if zero is in the text
		if (parseFloat(amount) == 0 && $('#payment_type').val() != 'purchase_order') {
			$('#tendered_amount').css('border', 'solid 2px #FF0000');
			$('#tendered_amount').attr('alt', text_payment_zero_amount);
			$('#tendered_amount').attr('title', text_payment_zero_amount);
			return false;
		} else {
			$('#tendered_amount').css('border', '');
			$('#tendered_amount').attr('alt', '');
			$('#tendered_amount').attr('title', '');
		}
	}
	var ordertotalamount=parseFloat($("#payment_due_amount").text().substring(1, 100))+parseFloat($("#tendered_amount").val());
    $("#dialog_order_total").html(ordertotalamount.toFixed(2));
	// add for Gift Voucher payment begin
	if ($('#payment_type').val() == 'gift_voucher') {
		var gift_voucher_code = $('#payment_note').val();
		if (gift_voucher_code == '') {
			openAlert(text_voucher_code_required);
		} else {
			$.ajax({
				url: 'index.php?route=module/pos/check_gift_voucher&token=' + token + '&gift_voucher_code=' + gift_voucher_code + '&due_amount=' + dueAmount,
				dataType: 'json',
				beforeSend: function() {
					openWaitDialog();
				},
				complete: function() {
					closeWaitDialog();
				},
				success: function(json) {
					if (json['error']) {
						openAlert(json['error']);
					} else {
						openConfirm(json['balance_message'], function() {
							$('#tendered_amount').val(json['due_amount']);
							processAddPayment(json['due_amount'], '');
						});
					}
				}
			});
		}
		return;
	}
	// add for Gift Voucher payment end
	
	// add for customer loyalty card begin
	if ($('#payment_type').val() == 'reward_points') {
		if (reward_points_usage == '1') {
			// get the list of paid order product and its quantity, and save it to the payment note
			var note = '';
			$('input[name^=use_reward_points_]').each(function() {
				if ($(this).is(':checked')) {
					var order_product_id = $(this).attr('name').substring('use_reward_points_'.length);
					var quantity = $(this).val();
					note += order_product_id + ',' + quantity + ',' + quantity * parseInt($(this).closest('tr').find('td').eq(1).text()) + '|';
				}
			});
			if (note) {
				processAddPayment(0, note);
			}
		} else {
			var amount = posParseFloat($('#reward_points_payment_list span').text());
			processAddPayment(amount, $('#reward_points_payment_list input[type=text]').val());
		}
		return;
	}
	// add for customer loyalty card end
	
	processAddPayment(amount, '', print_order_id);
	var dueAmount = $('#payment_due_amount').text();
	dueAmount = posParseFloat(dueAmount);
	if(dueAmount > amount)
	{
		openAlert("Full payment is not received. Please go back to payment page for full payment.");
	}
};
var paymnettype='';
$("#payment_type").change(function(){
  paymnettype=$("#payment_type").val();
  
});

function changePaymentMethod(value)
{
	$('#payment_type').val(value).trigger('change');
}

function processAddPayment(amount, noteAppend, print_order_id) {
	var note = $('#payment_note').val();
	if (noteAppend != '') {
		note += ' ' + noteAppend;
	}
	// add for customer loyalty card begin
	if ($('#payment_type').val() == 'reward_points') {
		note = noteAppend;	// note is important for this type of payment
	}
	// add for customer loyalty card end
	
	var type = $('#payment_type option:selected').text();
	// add for till control begin
	var payment_type = $('#payment_type').val();
	// add for till control end
	if(paymnettype==5){
		payment_type='pay_by_memo';
		type = 'Pay By Memo';
	}
	var url = 'index.php?route=module/pos/addOrderPayment&token=' + token;
	var data = {'user_id':user_id, 'payment_type':type, 'payment_note':note, 'payment_code':payment_type };
	if (text_work_mode == '1') {
		data['pos_return_id'] = pos_return_id;
	} else {
		data['order_id'] = order_id;
	}
	var dueAmount = toFixed(calcDueAmount(), 2);
	if (parseFloat(amount) > dueAmount) {
		data['tendered_amount'] = parseFloat(dueAmount);
		data['change'] = (parseFloat(amount) - dueAmount);
	} else {
		data['tendered_amount'] = parseFloat(amount);
	}

	$.ajax({
		url: url,
		type: 'post',
		data: data,
		dataType: 'json',
		beforeSend: function() {
			openWaitDialog();
		},
		complete: function() {
			closeWaitDialog();
		},
		cacheCallback: function(json) {
			// do not save the order payment
		},
		cachePreDone: function(cacheKey, callback) {
			backendSaveOrderPayment(data, callback);
		},
		success: function(json) {
			if (json['error']) {
				openAlert(json['error']);
			}
			else {
				// translate the amount to money format
				// get rid of non digital first
				amount = parseFloat(amount);
				amount = formatMoney(amount);
				// add for customer loyalty card begin
				if (json['total_value']) {
					amount = formatMoney(json['total_value']);
				}
				if ($('#payment_type').val() == 'reward_points') {
					note = '';	// do not display the note if the payment type is reward point
				}
				// add for customer loyalty card end
				
				var curPaymentLen = $('#payment_list tr').length - 1;
				var trClass = (curPaymentLen % 2 == 0) ? 'odd' : 'even';
				var tr_element = '<tr id="order_payment_' + json['order_payment_id'] +'" class="' + trClass + '">';
				tr_element += '<td><span class="skip_content label">' + column_payment_type + ':</span>' + type + '</td>';
				tr_element += '<td><span class="skip_content label">' + column_payment_amount + ':</span>' + amount + ((text_work_mode == '1') ? ' CR' : '') + '</td>';
				tr_element += '<td><span class="skip_content label"><span id="payment_note_text">' + column_payment_note + '</span>:</span>' + note + '</td>';
				tr_element += '<td class="action"><a class="table-btn table-btn-delete-2" onclick="deletePayment(this, \''+json['order_payment_id']+'\', \'' + amount + '\');"><span class="icon"></span>' + button_delete + '</a></td>';
				$(tr_element).insertAfter('#button_add_payment_tr');
				// clear the current inputs
				var totalDue = calcDueAmount();
				// add for Print begin
				var p_payment = 0;
				if (json['p_payment']) {
					p_payment = json['p_payment'];
				}
				if (totalDue < 0.01 && p_payment) {
					// print receipt if set in the settings page
					window_print_url(print_receipt_message, 'index.php?route=module/pos/receipt&token=' + token + '&order_id='+order_id + '&work_mode='+text_work_mode, {'change':'1'}, afterPrintReceipt, null);
				}
				// add for Print end
				// add for till control begin
				if (config['enable_till_full_payment'] && parseInt(config['enable_till_full_payment']) > 0 && payment_type == 'cash') {
					sendControlKey();
				}
				// add for till control end
				// add for auto payment begin
				if (print_order_id) {
					// print receipt when auto payment is enabled and when print receipt after complete
					window_print_url(print_receipt_message, 'index.php?route=module/pos/receipt&token=' + token + '&order_id='+print_order_id + '&work_mode='+text_work_mode, {'change':'1'}, afterPrintReceipt, null);
				}
				// add for auto payment end
				
				if(isNaN(totalDue)){
					$('#tendered_amount').val(0);
				}else{
					$('#tendered_amount').val(toFixed(totalDue, 2));
				}
				$('#payment_type').val('cash');
				$('#payment_type').trigger('change');
				$('#payment_note').val('');
				$("#paymentApplied").html(json['success']);
				//Update order total section in popup
				$("#dialog_order_total").html(json['order_total']);
				$("#dialog_total_paid").html(json['order_paid']);
				$("#dialog_due_amount_text").html(json['due_amount']);
				$("#dialog_change_text").html(json['order_change_amount']);
				$("#payment_status").html(json['payment_status']);
			}
	
			// add for Cash type begin
			useCashType = false;
			// add for Cash type end
			// reset click flag
			firstPaymentCashClick = true;
		}
	});
}

function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

function deletePayment(anchor, paymentId, amount = null) {
	$.ajax({
			url: 'index.php?route=module/pos/deleteOrderPayment&token=' + token + '&order_payment_id='+paymentId,
			dataType: 'json',
			beforeSend: function() {
				openWaitDialog();
			},
			complete: function() {
				closeWaitDialog();
			},
			cacheCallback: function(json) {
				// do not save the delete result
			},
			cachePreDone: function(cacheKey, callback) {
				// remove from the local list first
				backendRemoveOrderPayment(paymentId, callback);
			},
			success: function(json) {
				if (json['error']) {
					openAlert(json['error']);
				}

				$('#order_payment_'+paymentId).remove();
				$("#paymentApplied").html("");
				//$("#dialog_total_paid").html("$0.00");
				//$("#dialog_change_text").html("$0.00");
				if(amount !== null){
					amount = posParseFloat(amount.replace("$", ""));
					if(amount > 0){
						var total_paid 	= $("#dialog_total_paid").html();
						var change 		= $("#dialog_change_text").html();
						var order_total = $("#dialog_order_total").html();
						total_paid 		= posParseFloat(total_paid.replace("$", ""));
						change 			= posParseFloat(change.replace("$", ""));
						order_total 	= posParseFloat(order_total.replace("$", ""));
						if(amount > change)
						{
							amount = amount - change;
							change = 0;
							total_paid 		= total_paid - amount;
						} else {
							change 			= change - amount;
						}
						due_amount 		= order_total - total_paid;
						due_amount 		= parseFloat(Math.round(due_amount * 100) / 100).toFixed(2);
						total_paid 		= parseFloat(Math.round(total_paid * 100) / 100).toFixed(2);
						change 			= parseFloat(Math.round(change * 100) / 100).toFixed(2);
						order_total 	= parseFloat(Math.round(order_total * 100) / 100).toFixed(2);
						
						$("#dialog_total_paid").html('$' + total_paid);

						if(due_amount >= 0){
							$("#dialog_due_amount_text").html('$' + due_amount);
						}

						if(change > 0){
							$("#dialog_change_text").html('$' + change);
						}else{
							$("#dialog_change_text").html('$0.00');
						}
					}
				}
				calcDueAmount();
				// reset click flag
				firstPaymentCashClick = true;
				$("#payment_status").html(json['payment_status']);
			}
		});
	/*openConfirm(text_del_payment_confirm, function() {
		
	});*/
};

var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();

$(document).on('keyup', 'input[name=filter_product]', function() {
	delay(function() {
		// search product when input product name in search field
		var filter_name = $('input[name=filter_product]').val();
		if (filter_name != '') {
			if (filter_name[0] == "{"){
				//decode string first
				_filter = filter_name.split("///");
				_0x2456 = _filter[1].replace("}","");
				filter_name = _0x2456;
			}

			var url = 'index.php?route=module/pos/autocomplete_product&token=' + token;
			var data = {'filter_name':filter_name, 'customer_group_id':customer_group_id, 'filter_scopes':searchScopes};
			$.ajax({
				url: url,
				type: 'post',
				data: data,
				dataType: 'json',
				cacheCallback: function(json) {
					backendSaveProducts(json);
				},
				cachePreDone: function(cacheKey, callback) {
					backendGetProducts(data, callback);
				},
				success: function(json) {
					if (json && json.length == 1) {
						// a single product
						$('input[name=current_product_id]').val(json[0]['product_id']);
						$('input[name=current_product_name]').val(json[0]['name']);
						$('input[name=current_product_weight_price]').val(json[0]['weight_price']);
						$('input[name=current_product_weight_name]').val(json[0]['weight_name']);
						$('input[name=current_product_hasOption]').val(json[0]['hasOptions']);
						$('input[name=current_product_price]').val(json[0]['price']);
						$('input[name=current_product_tax]').val(json[0]['tax']);
						$('input[name=current_product_points]').val(json[0]['points']);
						$('input[name=current_product_image]').val(json[0]['image']);
						$('input[name=current_product_labour_cost]').val(json[0]['labour_cost']);
						$('input[name=current_product_unique_option_price]').val(json[0]['unique_option_price']);
					}
					populateBrowseTable(json, true);
				}
			});
		}
	}, 500);
});



$(document).on('keyup', 'input[name=filter_product_for_edit]', function() {
	delay(function() {
		// search product when input product name in search field
		var filter_name = $('input[name=filter_product_for_edit]').val();
		if (filter_name != '') {
			var url = 'index.php?route=module/pos/autocomplete_product&token=' + token;
			var data = {'filter_name':filter_name, 'customer_group_id':customer_group_id, 'filter_scopes':searchScopes};
			$.ajax({
				url: url,
				type: 'post',
				data: data,
				dataType: 'json',
				cacheCallback: function(json) {
					backendSaveProducts(json);
				},
				cachePreDone: function(cacheKey, callback) {
					backendGetProducts(data, callback);
				},
				success: function(json) {
					if (json && json.length == 1) {
						// a single product
						$('input[name=current_product_id]').val(json[0]['product_id']);
						$('input[name=current_product_name]').val(json[0]['name']);
						$('input[name=current_product_weight_price]').val(json[0]['weight_price']);
						$('input[name=current_product_weight_name]').val(json[0]['weight_name']);
						$('input[name=current_product_hasOption]').val(json[0]['hasOptions']);
						$('input[name=current_product_price]').val(json[0]['price']);
						$('input[name=current_product_tax]').val(json[0]['tax']);
						$('input[name=current_product_points]').val(json[0]['points']);
						$('input[name=current_product_image]').val(json[0]['image']);
					}
					populateBrowseTableForEditProduct(json, true);
				}
			});
		}
	}, 500);
});


$(document).on('keyup', 'input[name=filter_product_sales_report]', function() {
	delay(function() {
		// search product when input product name in search field
		var filter_name = $('input[name=filter_product_sales_report]').val();
		if (filter_name != '') {
			var url = 'index.php?route=module/pos/autocomplete_product&token=' + token;
			var data = {'filter_name':filter_name, 'customer_group_id':customer_group_id, 'filter_scopes':searchScopes};
			$.ajax({
				url: url,
				type: 'post',
				data: data,
				dataType: 'json',
				cacheCallback: function(json) {
					backendSaveProducts(json);
				},
				cachePreDone: function(cacheKey, callback) {
					backendGetProducts(data, callback);
				},
				success: function(json) {
					if (json && json.length == 1) {
						// a single product
						$('input[name=current_product_id]').val(json[0]['product_id']);
						$('input[name=current_product_name]').val(json[0]['name']);
						$('input[name=current_product_weight_price]').val(json[0]['weight_price']);
						$('input[name=current_product_weight_name]').val(json[0]['weight_name']);
						$('input[name=current_product_hasOption]').val(json[0]['hasOptions']);
						$('input[name=current_product_price]').val(json[0]['price']);
						$('input[name=current_product_tax]').val(json[0]['tax']);
						$('input[name=current_product_points]').val(json[0]['points']);
						$('input[name=current_product_image]').val(json[0]['image']);
					}
					populateBrowseTableForSalesReport(json, true);
				}
			});
		}
	}, 500);
});


$(document).on('keyup', 'input[name=filter_product_update_stock]', function() {
	delay(function() {
		// search product when input product name in search field
		var filter_name = $('input[name=filter_product_update_stock]').val();
		if (filter_name != '') {
			var url = 'index.php?route=module/pos/autocomplete_product&token=' + token;
			var data = {'filter_name':filter_name, 'customer_group_id':customer_group_id, 'filter_scopes':searchScopes};
			$.ajax({
				url: url,
				type: 'post',
				data: data,
				dataType: 'json',
				cacheCallback: function(json) {
					backendSaveProducts(json);
				},
				cachePreDone: function(cacheKey, callback) {
					backendGetProducts(data, callback);
				},
				success: function(json) {
					if (json && json.length == 1) {
						// a single product
						$('input[name=current_product_id]').val(json[0]['product_id']);
						$('input[name=current_product_name]').val(json[0]['name']);
						$('input[name=current_product_weight_price]').val(json[0]['weight_price']);
						$('input[name=current_product_weight_name]').val(json[0]['weight_name']);
						$('input[name=current_product_hasOption]').val(json[0]['hasOptions']);
						$('input[name=current_product_price]').val(json[0]['price']);
						$('input[name=current_product_tax]').val(json[0]['tax']);
						$('input[name=current_product_points]').val(json[0]['points']);
						$('input[name=current_product_image]').val(json[0]['image']);
					}
					populateBrowseTableForUpdateStock(json, true);
				}
			});
		}
	}, 500);
});



// customer addresses autocomplete

$(document).on('keyup', 'input[name=customer_firstname_tab]', function() {
	delay(function() {
		// search product when input product name in search field
		var filter_name = $('input[name=customer_firstname_tab]').val();
		if (filter_name != '') {
			var url = 'index.php?route=module/pos/autocomplete_customers&token=' + token;
			var data = {'filter_name':filter_name, 'customer_group_id':customer_group_id};
			$("#append_customer").show();
			$.ajax({
				url: url,
				type: 'post',
				data: data,
				dataType: 'json',
				success: function(json) {

                var htmlcust='';
				$(json).each(function(i){
					htmlcust += '<li><a onclick="selectCustomer(this,'+json[i].customer_id+');$(\'#customer_addresses_tab\').trigger(\'click\');" href="javascript:void(0);">'+json[i].name+'..('+json[i].email+').. '+json[i].company+'</a></li>';	
				});
				$("#append_customer").html(htmlcust);
				}
			});
		}else{
			$("#append_customer").hide();
		}
	}, 500);
});

// backorder other customer addresses autocomplete
$(document).on('keyup', 'input[name=backorder_customer_firstname_tab]', function() {
	delay(function() {
		// search product when input product name in search field
		var filter_name = $('input[name=backorder_customer_firstname_tab]').val();
		if (filter_name != '') {
			var url = 'index.php?route=module/pos/autocomplete_customers&token=' + token;
			var data = {'filter_name':filter_name, 'customer_group_id':customer_group_id};
			$("#append_backorder_customer").show();
			$.ajax({
				url: url,
				type: 'post',
				data: data,
				dataType: 'json',
				success: function(json) {

                var htmlcust='';
				$(json).each(function(i){
					htmlcust += '<li><a onclick="selectBackOrderCustomer(this,'+json[i].customer_id+');" href="javascript:void(0);">'+json[i].name+'..('+json[i].email+').. '+json[i].company+'</a></li>';		
				});
				$("#append_backorder_customer").html(htmlcust);
				}
			});
		}else{
			$("#append_backorder_customer").hide();
		}
	}, 500);
});

function populateBrowseTable(items, highlight, forReturn) {
	// save in memory for further use
	window['browse_items'] = items;

	$('#browse_list a').remove();
	var filter_name = $('input[name=filter_product]').val();
	
	var html = '';
	if (items) {
		for (var index in items) {
			if (items[index]['type'] == 'C') {
				html += '<a onclick="showCategoryItems(\'' + items[index]['category_id'] + '\')" class="product-box product-folder">';
				html += '	<span class="product-box-img">';
				html += '		<span class="product-box-frame-wrap">';
				html += '			<span class="product-box-frame">';
				html += '				<img src="' + items[index]['image'] + '"  alt="">';
				html += '			</span>';
				html += '			<span class="product-count">' + items[index]['total_items'] + '</span>';
				html += '		</span>'             	
				html += '	</span>';
				html += '	<span class="product-box-prod">';
				html += '		<span class="product-box-prod-title">' + items[index]['name'] + '</span>';
				html += '	</span>';
				html += '</a>';
			} else {
				if (forReturn) {
					// return for order
					html += '<a onclick="selectProductForReturn(' + items[index]['order_product_id'] + ')" class="product-box product-item">';
				} else if (text_work_mode == '1') {
					// return without order
					html += '<a onclick="processSelectProduct(' + items[index]['product_id'] + ')" class="product-box product-item">';
				} else {
					html += '<a onclick="selectProduct(' + items[index]['product_id'] + ')" class="product-box product-item">';
				}
				html += '	<span class="product-box-img">';
				html += '		<span class="product-box-frame-wrap">';
				html += '			<span class="product-box-frame">';
				html += '				<img src="' + items[index]['image'] + '"  alt="">';
				html += '			<span class="product-count">' + items[index]['stock'] + '</span>';
				html += '			</span>';
				html += '		</span>'             	
				html += '	</span>';
				html += '	<span class="product-box-prod">';
				html += '		<span class="product-box-prod-title">';
				html += (highlight ? highlightStr(items[index]['name'], filter_name) : items[index]['name']) + '<br />';
				if (forReturn) {
					/*
					if (items[index]['option'] || parseInt(items[index]['weight_price']) || items[index]['sn']) {
						for (var optioni in items[index]['option']) {
							var option = items[index]['option'][optioni];
							html += '( ' + option['name'] + ': ' + option['value'] + ' )<br />';
						}
						if (items[index]['sn']) {
							html += '( SN: ' + items[index]['sn'] + ' )<br />';
						}
					}
					*/
					html += items[index]['quantity'];
					if (parseInt(items[index]['weight_price'])) {
						html += '*' + items[index]['weight'] + '(' + items[index]['weight_name'] + ')';
					}
					html += ' @ ' + items[index]['price_text'] + '<br />';
				}

				html += 'Model: ' + highlightStr(items[index]['model'], filter_name) + '<br />' + '(' + items[index]['labour_cost'] + ' + ' + items[index]['unique_option_price'] + ')' + '<br />';
				// highlight all matched fields
				if (highlight) {
					for (var i in searchScopes) {
						/* if (searchScopes[i] == 'model' && items[index]['model'] && items[index]['model'].toLowerCase().indexOf(filter_name.toLowerCase()) >= 0) {
							html += text_search_model_short + highlightStr(items[index]['model'], filter_name) + '<br />';
						} */
						if (searchScopes[i] == 'manufacturer' && items[index]['manufacturer'] && items[index]['manufacturer'].toLowerCase().indexOf(filter_name.toLowerCase()) >= 0) {
							html += text_search_manufacturer_short + highlightStr(items[index]['manufacturer'], filter_name) + '<br />';
						}
						if (searchScopes[i] == 'upc' && items[index]['upc'] && items[index]['upc'].toLowerCase().indexOf(filter_name.toLowerCase()) >= 0) {
							html += 'UPC: ' + highlightStr(items[index]['upc'], filter_name) + '<br />';
						}
						/* if (searchScopes[i] == 'sku' && items[index]['sku'] && items[index]['sku'].toLowerCase().indexOf(filter_name.toLowerCase()) >= 0) {
							html += 'SKU: ' + highlightStr(items[index]['sku'], filter_name) + '<br />';
						} */
						if (searchScopes[i] == 'isbn' && items[index]['isbn'] && items[index]['isbn'].toLowerCase().indexOf(filter_name.toLowerCase()) >= 0) {
							html += 'ISBN: ' + highlightStr(items[index]['isbn'], filter_name) + '<br />';
						}
						if (searchScopes[i] == 'mpn' && items[index]['mpn'] && items[index]['mpn'].toLowerCase().indexOf(filter_name.toLowerCase()) >= 0) {
							html += 'MPN: ' + highlightStr(items[index]['mpn'], filter_name) + '<br />';
						}
					}
				}
				html += '		</span>';
				if (!forReturn) {
					html += '		<span class="product-box-prod-price">' + items[index]['price_text'] + '</span>';
				}
				html += '	</span>';
				html += '</a>';
			}
		}
	}
	$('#browse_list').append(html);
};



function populateBrowseTableForEditProduct(items, highlight, forReturn) {
	// save in memory for further use
	window['browse_items'] = items;

	$('#browse_list a').remove();
	var filter_name = $('input[name=filter_product_for_edit]').val();
	
	var html = '';
	if (items) {
		for (var index in items) {
			if (items[index]['type'] == 'C') {
				html += '<a onclick="showCategoryItems(\'' + items[index]['category_id'] + '\')" class="product-box product-folder">';
				html += '	<span class="product-box-img">';
				html += '		<span class="product-box-frame-wrap">';
				html += '			<span class="product-box-frame">';
				html += '				<img src="' + items[index]['image'] + '"  alt="">';
				html += '			</span>';
				html += '			<span class="product-count">' + items[index]['total_items'] + '</span>';
				html += '		</span>'             	
				html += '	</span>';
				html += '	<span class="product-box-prod">';
				html += '		<span class="product-box-prod-title">' + items[index]['name'] + '</span>';
				html += '	</span>';
				html += '</a>';
			} else {
				if (forReturn) {
					// return for order
					html += '<a onclick="selectProductForReturn(' + items[index]['order_product_id'] + ')" class="product-box product-item">';
				} else if (text_work_mode == '1') {
					// return without order
					html += '<a onclick="processSelectProduct(' + items[index]['product_id'] + ')" class="product-box product-item">';
				} else {
					html += '<a onclick="selectProductEdit(' + items[index]['product_id'] + ')" class="product-box product-item">';
				}
				html += '	<span class="product-box-img">';
				html += '		<span class="product-box-frame-wrap">';
				html += '			<span class="product-box-frame">';
				html += '				<img src="' + items[index]['image'] + '"  alt="">';
				html += '			<span class="product-count">' + items[index]['stock'] + '</span>';
				html += '			</span>';
				html += '		</span>'             	
				html += '	</span>';
				html += '	<span class="product-box-prod">';
				html += '		<span class="product-box-prod-title">';
				html += (highlight ? highlightStr(items[index]['name'], filter_name) : items[index]['name']) + '<br />';
				if (forReturn) {
					/*
					if (items[index]['option'] || parseInt(items[index]['weight_price']) || items[index]['sn']) {
						for (var optioni in items[index]['option']) {
							var option = items[index]['option'][optioni];
							html += '( ' + option['name'] + ': ' + option['value'] + ' )<br />';
						}
						if (items[index]['sn']) {
							html += '( SN: ' + items[index]['sn'] + ' )<br />';
						}
					}
					*/
					html += items[index]['quantity'];
					if (parseInt(items[index]['weight_price'])) {
						html += '*' + items[index]['weight'] + '(' + items[index]['weight_name'] + ')';
					}
					html += ' @ ' + items[index]['price_text'] + '<br />';
				}
				// highlight all matched fields
				if (highlight) {
					for (var i in searchScopes) {
						if (searchScopes[i] == 'model' && items[index]['model'] && items[index]['model'].toLowerCase().indexOf(filter_name.toLowerCase()) >= 0) {
							html += text_search_model_short + highlightStr(items[index]['model'], filter_name) + '<br />';
						}
						if (searchScopes[i] == 'manufacturer' && items[index]['manufacturer'] && items[index]['manufacturer'].toLowerCase().indexOf(filter_name.toLowerCase()) >= 0) {
							html += text_search_manufacturer_short + highlightStr(items[index]['manufacturer'], filter_name) + '<br />';
						}
						if (searchScopes[i] == 'upc' && items[index]['upc'] && items[index]['upc'].toLowerCase().indexOf(filter_name.toLowerCase()) >= 0) {
							html += 'UPC: ' + highlightStr(items[index]['upc'], filter_name) + '<br />';
						}
						if (searchScopes[i] == 'sku' && items[index]['sku'] && items[index]['sku'].toLowerCase().indexOf(filter_name.toLowerCase()) >= 0) {
							html += 'SKU: ' + highlightStr(items[index]['sku'], filter_name) + '<br />';
						}
						if (searchScopes[i] == 'ean' && items[index]['ean'] && items[index]['ean'].toLowerCase().indexOf(filter_name.toLowerCase()) >= 0) {
							html += 'EAN: ' + highlightStr(items[index]['ean'], filter_name) + '<br />';
						}
						if (searchScopes[i] == 'mpn' && items[index]['mpn'] && items[index]['mpn'].toLowerCase().indexOf(filter_name.toLowerCase()) >= 0) {
							html += 'MPN: ' + highlightStr(items[index]['mpn'], filter_name) + '<br />';
						}
					}
				}
				html += '		</span>';
				if (!forReturn) {
					html += '		<span class="product-box-prod-price">' + items[index]['price_text'] + '</span>';
				}
				html += '	</span>';
				html += '</a>';
			}
		}
	}
	$('#browse_list').append(html);
};

function populateBrowseTableForSalesReport(items, highlight, forReturn) {
	// save in memory for further use
	window['browse_items'] = items;

	$('#browse_list a').remove();
	var filter_name = $('input[name=filter_product_sales_report]').val();
	
	var html = '';
	if (items) {
		for (var index in items) {
			if (items[index]['type'] == 'C') {
				html += '<a onclick="showCategoryItems(\'' + items[index]['category_id'] + '\')" class="product-box product-folder">';
				html += '	<span class="product-box-img">';
				html += '		<span class="product-box-frame-wrap">';
				html += '			<span class="product-box-frame">';
				html += '				<img src="' + items[index]['image'] + '"  alt="">';
				html += '			</span>';
				html += '			<span class="product-count">' + items[index]['total_items'] + '</span>';
				html += '		</span>'             	
				html += '	</span>';
				html += '	<span class="product-box-prod">';
				html += '		<span class="product-box-prod-title">' + items[index]['name'] + '</span>';
				html += '	</span>';
				html += '</a>';
			} else {
				if (forReturn) {
					// return for order
					html += '<a onclick="selectProductForReturn(' + items[index]['order_product_id'] + ')" class="product-box product-item">';
				} else if (text_work_mode == '1') {
					// return without order
					html += '<a onclick="processSelectProduct(' + items[index]['product_id'] + ')" class="product-box product-item">';
				} else {
					html += '<a onclick="selectProductSalesHistory(' + items[index]['product_id'] + ')" class="product-box product-item">';
				}
				html += '	<span class="product-box-img">';
				html += '		<span class="product-box-frame-wrap">';
				html += '			<span class="product-box-frame">';
				html += '				<img src="' + items[index]['image'] + '"  alt="">';
				html += '			<span class="product-count">' + items[index]['stock'] + '</span>';
				html += '			</span>';
				html += '		</span>'             	
				html += '	</span>';
				html += '	<span class="product-box-prod">';
				html += '		<span class="product-box-prod-title">';
				html += (highlight ? highlightStr(items[index]['name'], filter_name) : items[index]['name']) + '<br />';
				if (forReturn) {
					html += items[index]['quantity'];
					if (parseInt(items[index]['weight_price'])) {
						html += '*' + items[index]['weight'] + '(' + items[index]['weight_name'] + ')';
					}
					html += ' @ ' + items[index]['price_text'] + '<br />';
				}
				// highlight all matched fields
				if (highlight) {
					for (var i in searchScopes) {
						if (searchScopes[i] == 'model' && items[index]['model'] && items[index]['model'].toLowerCase().indexOf(filter_name.toLowerCase()) >= 0) {
							html += text_search_model_short + highlightStr(items[index]['model'], filter_name) + '<br />';
						}
						if (searchScopes[i] == 'manufacturer' && items[index]['manufacturer'] && items[index]['manufacturer'].toLowerCase().indexOf(filter_name.toLowerCase()) >= 0) {
							html += text_search_manufacturer_short + highlightStr(items[index]['manufacturer'], filter_name) + '<br />';
						}
						if (searchScopes[i] == 'upc' && items[index]['upc'] && items[index]['upc'].toLowerCase().indexOf(filter_name.toLowerCase()) >= 0) {
							html += 'UPC: ' + highlightStr(items[index]['upc'], filter_name) + '<br />';
						}
						if (searchScopes[i] == 'sku' && items[index]['sku'] && items[index]['sku'].toLowerCase().indexOf(filter_name.toLowerCase()) >= 0) {
							html += 'SKU: ' + highlightStr(items[index]['sku'], filter_name) + '<br />';
						}
						if (searchScopes[i] == 'ean' && items[index]['ean'] && items[index]['ean'].toLowerCase().indexOf(filter_name.toLowerCase()) >= 0) {
							html += 'EAN: ' + highlightStr(items[index]['ean'], filter_name) + '<br />';
						}
						if (searchScopes[i] == 'mpn' && items[index]['mpn'] && items[index]['mpn'].toLowerCase().indexOf(filter_name.toLowerCase()) >= 0) {
							html += 'MPN: ' + highlightStr(items[index]['mpn'], filter_name) + '<br />';
						}
					}
				}
				html += '		</span>';
				if (!forReturn) {
					html += '		<span class="product-box-prod-price">' + items[index]['price_text'] + '</span>';
				}
				html += '	</span>';
				html += '</a>';
			}
		}
	}
	$('#browse_list').append(html);
};


function populateBrowseTableForUpdateStock(items, highlight, forReturn) {
	// save in memory for further use
	window['browse_items'] = items;

	$('#browse_list a').remove();
	var filter_name = $('input[name=filter_product_update_stock]').val();
	
	var html = '';
	if (items) {
		for (var index in items) {
			if (items[index]['type'] == 'C') {
				html += '<a onclick="showCategoryItems(\'' + items[index]['category_id'] + '\')" class="product-box product-folder">';
				html += '	<span class="product-box-img">';
				html += '		<span class="product-box-frame-wrap">';
				html += '			<span class="product-box-frame">';
				html += '				<img src="' + items[index]['image'] + '"  alt="">';
				html += '			</span>';
				html += '			<span class="product-count">' + items[index]['total_items'] + '</span>';
				html += '		</span>'             	
				html += '	</span>';
				html += '	<span class="product-box-prod">';
				html += '		<span class="product-box-prod-title">' + items[index]['name'] + '</span>';
				html += '	</span>';
				html += '</a>';
			} else {
				if (forReturn) {
					// return for order
					html += '<a onclick="selectProductForReturn(' + items[index]['order_product_id'] + ')" class="product-box product-item">';
				} else if (text_work_mode == '1') {
					// return without order
					html += '<a onclick="processSelectProduct(' + items[index]['product_id'] + ')" class="product-box product-item">';
				} else {
					html += '<a onclick="selectProductUpdateStock(' + items[index]['product_id'] + ')" class="product-box product-item">';
				}
				html += '	<span class="product-box-img">';
				html += '		<span class="product-box-frame-wrap">';
				html += '			<span class="product-box-frame">';
				html += '				<img src="' + items[index]['image'] + '"  alt="">';
				html += '			<span class="product-count">' + items[index]['stock'] + '</span>';
				html += '			</span>';
				html += '		</span>'             	
				html += '	</span>';
				html += '	<span class="product-box-prod">';
				html += '		<span class="product-box-prod-title">';
				html += (highlight ? highlightStr(items[index]['name'], filter_name) : items[index]['name']) + '<br />';
				if (forReturn) {
					/*
					if (items[index]['option'] || parseInt(items[index]['weight_price']) || items[index]['sn']) {
						for (var optioni in items[index]['option']) {
							var option = items[index]['option'][optioni];
							html += '( ' + option['name'] + ': ' + option['value'] + ' )<br />';
						}
						if (items[index]['sn']) {
							html += '( SN: ' + items[index]['sn'] + ' )<br />';
						}
					}
					*/
					html += items[index]['quantity'];
					if (parseInt(items[index]['weight_price'])) {
						html += '*' + items[index]['weight'] + '(' + items[index]['weight_name'] + ')';
					}
					html += ' @ ' + items[index]['price_text'] + '<br />';
				}
				// highlight all matched fields
				if (highlight) {
					for (var i in searchScopes) {
						if (searchScopes[i] == 'model' && items[index]['model'] && items[index]['model'].toLowerCase().indexOf(filter_name.toLowerCase()) >= 0) {
							html += text_search_model_short + highlightStr(items[index]['model'], filter_name) + '<br />';
						}
						if (searchScopes[i] == 'manufacturer' && items[index]['manufacturer'] && items[index]['manufacturer'].toLowerCase().indexOf(filter_name.toLowerCase()) >= 0) {
							html += text_search_manufacturer_short + highlightStr(items[index]['manufacturer'], filter_name) + '<br />';
						}
						if (searchScopes[i] == 'upc' && items[index]['upc'] && items[index]['upc'].toLowerCase().indexOf(filter_name.toLowerCase()) >= 0) {
							html += 'UPC: ' + highlightStr(items[index]['upc'], filter_name) + '<br />';
						}
						if (searchScopes[i] == 'sku' && items[index]['sku'] && items[index]['sku'].toLowerCase().indexOf(filter_name.toLowerCase()) >= 0) {
							html += 'SKU: ' + highlightStr(items[index]['sku'], filter_name) + '<br />';
						}
						if (searchScopes[i] == 'ean' && items[index]['ean'] && items[index]['ean'].toLowerCase().indexOf(filter_name.toLowerCase()) >= 0) {
							html += 'EAN: ' + highlightStr(items[index]['ean'], filter_name) + '<br />';
						}
						if (searchScopes[i] == 'mpn' && items[index]['mpn'] && items[index]['mpn'].toLowerCase().indexOf(filter_name.toLowerCase()) >= 0) {
							html += 'MPN: ' + highlightStr(items[index]['mpn'], filter_name) + '<br />';
						}
					}
				}
				html += '		</span>';
				if (!forReturn) {
					html += '		<span class="product-box-prod-price">' + items[index]['price_text'] + '</span>';
				}
				html += '	</span>';
				html += '</a>';
			}
		}
	}
	$('#browse_list').append(html);
};

function selectProductEdit(product_id)
{
	$("#main-container").hide();
	var html = '<div style="margin-top:30px;text-align:center"><i class="fa fa-spinner fa-spin"></i> Loading Product, Please Wait!</div>';
	$("#tab_product_information").html(html);
	$('#tab_product_information').load('index.php?route=catalog/product/edit&product_id='+product_id+'&token='+token);
}

function selectProductSalesHistory(product_id)
{
	$("#main-container").hide();
	var html = '<div style="margin-top:30px;text-align:center"><i class="fa fa-spinner fa-spin"></i> Loading Product Sales History, Please Wait!</div>';
	$("#tab_sales_report").html(html);
	$('#tab_sales_report').load('index.php?route=catalog/product/history&product_id='+product_id+'&token='+token);
}

function cancelEditProduct()
{
	$("#tab_product_information").html('');
	$("#main-container").show();
	$("input[name=filter_product_update_stock]").val("");
	$("input[name=filter_product_sales_report]").val("");
	$("#updateStockContainer").empty();
}

function cancelSalesReport()
{
	$("#tab_sales_report").html('');
	$("#main-container").show();
	$("input[name=filter_product_update_stock]").val("");
	$("input[name=filter_product_for_edit]").val("");
	$("#updateStockContainer").empty();
}

function saveUpdatedProduct()
{
	var html = '<i class="fa fa-spinner fa-spin"></i> Updating';
	$("#form-product-btn").html(html);
	var form = $('#tab_product_information').find("#form-product");
	var url = form.attr('action');
	$.ajax({
		type: "POST",
		url: url,
		data: form.serialize(), // serializes the form's elements.
		success: function(data)
		{
			$("#tab_product_information").html('');
			$("#main-container").show();
		}
	  });
}

function selectProductUpdateStock(product_id){

	var htmlComplete ="";
	$.ajax({
       type:"GET",
       url: "index.php?route=module/pos/getCompleteProductDetail&token="+token+"&product_id="+product_id,
       cache:false,
       dataType:"json",
       success:function(data){
		if(data.error){
			$("#updateStockContainer").html(data.error); 
			return false;
		}
       	var labourcost=data.product_data.labour_cost;
        var unique_option_price=data.product_data.unique_option_price;
		var stock_status_id = data.product_data.stock_status_id;
		var show_product_label_1 = data.product_data.show_product_label_1;
		var product_label_text_1 = data.product_data.product_label_text_1;
		var show_product_label_2 = data.product_data.show_product_label_2;
		var product_label_text_2 = data.product_data.product_label_text_2;
		if(stock_status_id == 7 )
		{
			var stock_check = '<div class="form-group"><label class="col-sm-4 control-label">In Stock</label><div class="col-sm-4"><input type="radio"  name="instock" value="1" checked="checked">Yes</div><div class="col-sm-4"><input type="radio" name="instock" value="0">No</div></div>';
		} else {
			var stock_check = '<div class="form-group"><label class="col-sm-4 control-label">In Stock</label><div class="col-sm-4"><input type="radio"  name="instock" value="1">Yes</div><div class="col-sm-4"><input type="radio" name="instock" value="0" checked="checked">No</div></div>';
		}
         htmlComplete += '<div id="updatestockbox" style="margin-left:10px;margin-right:10px">';
         htmlComplete += '<h3>'+data.product_data.name +'</h3><div class="row"><div style="padding:30px;" class="col-sm-2"><img src="../image/'+data.product_data.image+'" width="60"></div><div class="col-sm-10"><div class="col-sm-5"><b>Model: '+data.product_data.model+'</b></div><div class="col-sm-5"><b>MPN: '+data.product_data.mpn+'</b></div><div class="col-sm-5"><b>SKU: '+data.product_data.sku+'</b></div><div class="col-sm-5"><b>UPC: '+data.product_data.upc+'</b></div><div class="col-sm-5"><b>Price: '+data.product_data.price+'<br/>('+parseFloat(labourcost).toFixed(2)+'+'+parseFloat(unique_option_price).toFixed(8)+')</b></div><div class="col-sm-5"><b>Location: '+data.product_data.location+'</b></div></div></div>';
         htmlComplete += stock_check;
         htmlComplete += '<div class="row form-group"><label class="control-label col-sm-4">Total Instock</label><div class="col-sm-8"><input type="text" name="total_instock" id="totalinstock" class="form-control" value="'+data.product_data.quantity+'"><input type="hidden" name="productid" id="productid" class="form-control" value="'+product_id+'"></div></div>';
         //htmlComplete += '<div class="row form-group"><div class="col-sm-4" style=""><button id="update_stock_btn" type="button" class="btn btn-success"><i class="fa fa-refresh"></i> Update Stock</button></div><div class="col-sm-4 pull-right" style=""><button data-count ="0" id="addmore" type="button" class="btn btn-info"><i class="fa fa-plus"></i> Add Location</button></div></div>';
         //htmlComplete += '<div id="appendhere"></div>';
          //htmlComplete += '<div class="col-sm-3">Location</div>';
         //htmlComplete += '<div class="col-sm-3">Quantity</div>';
         //htmlComplete += '<div class="col-sm-3">Assigned To</div>';
         //htmlComplete += '<div class="col-sm-3">Action</div>';

        
		if(data.location_data){
		var counter=0;
          $(data.location_data).each(function(i){
             htmlComplete +="   <div id=\"mainrow"+counter+"\" class=\"form-group\"><div class=\"form-group\">";
             htmlComplete +="        <div class=\"col-sm-3\">";
              
            htmlComplete +="  <input type=\"text\"  class=\"form-control\" name=\"locations["+counter+"][location_name]\" value=\""+data.location_data[i].location_id+"\">";
             htmlComplete +=" </div>";
             
             htmlComplete +=" <div class=\"col-sm-3\">";
              htmlComplete +="<input type=\"number\" id=\"qty_to_be_add"+counter+"\"  name=\"locations["+counter+"][location_quantity]\" value=\""+data.location_data[i].location_quantity+"\" class=\"form-control\">";
             htmlComplete +=" </div>";

                htmlComplete +="<div class=\"col-sm-3\">";
              htmlComplete +="<select class=\"form-control\" id=\"assigned_value"+counter+"\" onchange=\"calculateQuantity("+counter+")\" name=\"locations["+counter+"][location_assigned]\" >";
              htmlComplete +="<option selected value=\"\">Please Select</option>";
              if(data.location_data[i].assigned_stock=='front'){
              	htmlComplete +="<option selected value=\"front\">Front Instock</option>";
              }else{
              	htmlComplete +="<option value=\"front\">Front Instock</option>";
              }
              if(data.location_data[i].assigned_stock=='storage'){
              	htmlComplete +="<option selected value=\"storage\">Storage Instock</option>";
              }else{
 				htmlComplete +="<option value=\"storage\">Storage Instock</option>";
              }
              
              
             htmlComplete +="</select></div>";
            
             htmlComplete +="  <div class=\"col-sm-3 hidden\">";
           
             htmlComplete +=" <input type=\"text\"  class=\"form-control\" name=\"locations["+counter+"][location_unit]\" value=\"\">";
             htmlComplete +="   </div><div class=\"col-sm-3\"><button onclick=\"removerow("+counter+")\"  class=\"btn btn-danger\" type=\"button\"><i class=\"fa fa-minus-circle\"></i></button></div>";

            htmlComplete +="  </div></div>";
              counter= counter++;
			 });
			 	if(data.permissions.update_stock == 'edit_access')
			 	{
					htmlComplete += '<div class="row"><div class="col-sm-6" style=""><button id="update_stock_btn" type="button" class="btn btn-success"><i class="fa fa-refresh"></i> Update Stock</button></div><div class="col-sm-6 pull-right" style=""><button data-count ="'+counter+'" id="addmore" type="button" class="btn btn-info"><i class="fa fa-plus"></i> Add Location</button></div></div>';
					 htmlComplete += '<div class="row form-group" id="appendhere"></div>';
				}
			}else{
				if(data.permissions.update_stock == 'edit_access')
			 	{
					htmlComplete += '<div class="row"><div class="col-sm-6" style=""><button id="update_stock_btn" type="button" class="btn btn-success"><i class="fa fa-refresh"></i> Update Stock</button></div><div class="col-sm-6 pull-right" style=""><button data-count ="0" id="addmore" type="button" class="btn btn-info"><i class="fa fa-plus"></i> Add Location</button></div></div>';
				 	htmlComplete += '<div class="row form-group" id="appendhere"></div>';
				}
			}

		htmlComplete += '<div class="row form-group"><label class="col-sm-4 control-label">Quantity</label><div class="col-sm-8"><input type="text" name="qty" class="form-control" value="'+1+'"></div></div>';

		if(data.permissions.update_stock == 'edit_access'){
			htmlComplete += '<div class="row"><div class="col-sm-6" style=""><button id="addtoincoming" type="button" class="btn btn-info">Add to Incoming Order</button></div><div class="col-sm-6 pull-right" style=""><button data-count ="0" id="addtobackorder" type="button" class="btn btn-success">Create Backorder</button></div></div>';
		
        	htmlComplete += '<div class="row" style="border-top:none !important;display:none;margin-top:5px;padding-top:0;" id="incoming_order_block">';
        	htmlComplete += '<div class="col-sm-8">';
        	htmlComplete += '<button type="button" class="btn btn-primary" id="add_to_pending_incoming_order">Add to Pending</button>';
        	htmlComplete += '<button type="button" class="btn btn-success pull-right" id="create_new_incoming_order">Create New</button>';
        	htmlComplete += '</div>';
			htmlComplete += '<div class="alert" id="success_html"></div></div>';
		

			htmlComplete += '<div class="row" style="border-top:none !important;display:none;margin-top:5px;padding-top:0;" id="back_order_customer_block">';
			htmlComplete += '<div class="col-sm-8 pull-right">';
			htmlComplete += '<button type="button" class="btn btn-sm btn-primary" id="add_with_new_customer" onclick="changebackOrderCustomer();" style=" margin: 0 10px 0 30px;">Select Customer</button>';

			var customer_name = $('input[name=customer_name]').val();
        	htmlComplete += '<button type="button" class="btn btn-sm btn-success" id="add_to_current_customer">'+ customer_name +'</button>';
			htmlComplete += '</div></div>';
		}

        htmlComplete += '<div id="spacer" class="row form-group"></div>';
        
        htmlComplete += '<div class="row form-group"><label class="col-sm-4 control-label">Price</label><div class="col-sm-8"><input type="text" name="price" class="form-control" value="'+data.product_data.price+'" id="this-product-price"></div>';
		htmlComplete += '<label class="col-sm-4 control-label">Labour</label><div class="col-sm-8" style=""><input type="text" name="labour" class="form-control" value="'+labourcost+'"></div><label class="col-sm-4 control-label">Increment</label><div class="col-sm-8" style=""><input type="text" name="increment" class="form-control" value="'+unique_option_price+'"></div></div>';
		if(data.permissions.update_stock == 'edit_access'){

        	htmlComplete += '<div class="row"><div class="col-sm-5" style=""><button id="savenupdate" type="button" class="btn btn-info">Save and Update Metal Price + Discounts</button></div><div class="col-sm-4 pull-right landscape" style=""><button data-count ="0" id="saveonly" type="button" class="btn btn-success">Save Only</button></div>';
			htmlComplete += '<div class="col-sm-8 portrait" style="display:none"><button data-count ="0" id="saveonly" type="button" class="btn btn-success">Save Only</button></div></div>';
		}
        
        htmlComplete += '<div id="spacer" class="row form-group"></div>';

		htmlComplete += '<div class="row form-group"><label class="col-sm-8 control-label">Location</label><div class="col-sm-8"><input type="text" name="location" class="form-control" value="'+data.product_data.location+'"></div></div>';
		if(data.permissions.update_stock == 'edit_access'){
			htmlComplete += '<div class="row"><div class="col-sm-4 pull-right" style=""><button id="updatelocation" type="button" class="btn btn-success"><i class="fa fa-refresh"></i>Update</button></div></div>';
		}
        htmlComplete += '</div>';
        //htmlComplete +='<div class="col-sm-2 pull-right"  style="padding:10px;"><button data-count="'+counter+'" id="addmore" type="button" class="btn btn-info"><i class="fa fa-plus"></i>Add Location</button></div>';
        
		htmlComplete += '<script>';
		htmlComplete += '$("#update_stock_btn").click(function(){';
		htmlComplete += '$("#update_stock_btn").html("Updating...");';
        htmlComplete += '$.ajax({';
        htmlComplete += 'type:"post",';
        htmlComplete += 'url:"index.php?route=module/pos/updateStockAndStorageLocations&token="+token,';
        htmlComplete += 'data:$("#updateStockContainer input[type^=text],#productid,#updateStockContainer select[name^=locations],#updateStockContainer input[type^=number],#updateStockContainer input[type^=hidden],#updateStockContainer input[type=radio]:checked"),';
        htmlComplete += 'cache:false,';
        htmlComplete += 'success:function(){';
		htmlComplete += '$("#update_stock_btn").html("Updated");';
        htmlComplete += '}';
        htmlComplete += '});';
		htmlComplete += '});';


		htmlComplete += '$("#updatelocation").click(function(){';
		htmlComplete += '$("#updatelocation").html("Updating...");';
        htmlComplete += '$.ajax({';
        htmlComplete += 'type:"post",';
        htmlComplete += 'url:"index.php?route=module/pos/updateLocation&token="+token,';
        htmlComplete += 'data:$("input[name=location],#productid"),';
        htmlComplete += 'cache:false,';
        htmlComplete += 'success:function(){';
		htmlComplete += '$("#updatelocation").html("Updated");';
        htmlComplete += '}';
        htmlComplete += '});';
		htmlComplete += '});';
		
		htmlComplete += '$("button[id=saveonly]").click(function(){';
		htmlComplete += '$("#saveonly").html("Updating...");';
        htmlComplete += '$.ajax({';
        htmlComplete += 'type:"post",';
        htmlComplete += 'url:"index.php?route=module/pos/updatePriceData&token="+token,';
        htmlComplete += 'data:$("input[name=price],input[name=labour],input[name=increment],#productid"),';
        htmlComplete += 'cache:false,';
        htmlComplete += 'success:function(){';
		htmlComplete += '$("#saveonly").html("Updated");';
        htmlComplete += '}';
        htmlComplete += '});';
		htmlComplete += '});';

		htmlComplete += '$("#savenupdate").click(function(){';
		htmlComplete += '$("#savenupdate").html("Updating...");';
        htmlComplete += '$.ajax({';
        htmlComplete += 'type:"post",';
        htmlComplete += 'url:"index.php?route=module/pos/updateMetalPriceAndDiscount&token="+token,';
		htmlComplete += 'data:$("input[name=price],input[name=labour],input[name=increment],#productid"),';
		htmlComplete += 'dataType: "json",';
        htmlComplete += 'cache:false,';
        htmlComplete += 'success:function(json){';
		htmlComplete += '$("#savenupdate").html("Updated");';
		htmlComplete += '$( "#this-product-price" ).val( json["price"] );';
		htmlComplete += 'setTimeout(function(){$("#savenupdate").html("Save and Update Metal Price + Discounts");},1000);';
        htmlComplete += '}';
        htmlComplete += '});';
		htmlComplete += '});';

		htmlComplete += '$("#add_to_current_customer").click(function(){';
		htmlComplete += '$("#back_order_customer_block").hide();';
		htmlComplete += '$("#addtobackorder").html("Updating...");';
		htmlComplete += 'var pid = $("#productid").val();';
		htmlComplete += 'var customer_id = $("input[name=customer_id]").val();';
		htmlComplete += 'var quantity = $("input[name=qty]").val();';
		htmlComplete += 'var order_id = $("#prolab_orderid").val();';
        htmlComplete += '$.ajax({';
        htmlComplete += 'type:"post",';
        htmlComplete += 'url:"index.php?route=sale/orderq/create_backorder&token="+token+"&product_id="+pid+"&customer_id="+customer_id+"&quantity="+quantity+"&order_id="+order_id,';
        htmlComplete += 'data:$("input[name=price],input[name=labour],input[name=increment],#productid"),';
        htmlComplete += 'cache:false,';
        htmlComplete += 'success:function(){';
		htmlComplete += '$("#addtobackorder").html("Added");';
        htmlComplete += '}';
        htmlComplete += '});';
		htmlComplete += '});';

		htmlComplete += '$("#add_to_new_customer").click(function(){';
		htmlComplete += '$("#add_to_new_customer").html("Updating...");';
		htmlComplete += 'var pid = $("#productid").val();';
		htmlComplete += 'var quantity = $("input[name=qty]").val();';
		htmlComplete += 'var order_id = $("#prolab_orderid").val();';
		htmlComplete += 'var customer_id = $("input[name=backorder_new_customer_id]").val();';
		htmlComplete += 'var address_id = $("#backorder_customer_addresses :selected").val();';
		htmlComplete += '$("#back_order_customer_block").hide();'
        htmlComplete += '$.ajax({';
        htmlComplete += 'type:"post",';
        htmlComplete += 'url:"index.php?route=sale/orderq/create_backorder&token="+token+"&product_id="+pid+"&customer_id="+customer_id+"&address_id="+address_id+"&quantity="+quantity+"&order_id="+order_id,';
        htmlComplete += 'data:$("input[name=price],input[name=labour],input[name=increment],#productid"),';
        htmlComplete += 'cache:false,';
		htmlComplete += 'success:function(){';
		htmlComplete += '$("input[name=backorder_new_customer_id]").val("");';
		htmlComplete += '$("input[name=backorder_customer_firstname_tab]").val("");';
		htmlComplete += '$("input[name=selected_backorder_new_customer_name]").val("");';
		htmlComplete += '$("#backorder_customer_addresses").empty();';
		htmlComplete += '$("#add_to_new_customer").html("Create Backorder");';
		htmlComplete += 'closeFancybox();';
		htmlComplete += '$("#addtobackorder").html("Added");';
        htmlComplete += '}';
        htmlComplete += '});';
		htmlComplete += '});';

		htmlComplete += '$("#addtobackorder").click(function(){';
		htmlComplete += '$( "#back_order_customer_block" ).show();';
		htmlComplete += '});'; 
		
		htmlComplete += '$("#addtoincoming").click(function(){';
		htmlComplete += '$( "#success_html" ).empty();';
		htmlComplete += '$( "#incoming_order_block" ).show();';
		htmlComplete += '});';

		htmlComplete += '$("#create_new_incoming_order").click(function(){';
		htmlComplete += '$( "#success_html" ).empty();';
		htmlComplete += 'var pid = $("#productid").val();';
		htmlComplete += 'var quantity = $("input[name=qty]").val();';
		htmlComplete += 'var order_id = $("input[name=orderid]").val();';
        htmlComplete += '$.ajax({';
        htmlComplete += 'url:"index.php?route=sale/orderq/create_incoming_order&token="+token+"&product_id="+pid+"&quantity="+quantity+"&order_id="+order_id,';
        htmlComplete += 'dataType: "json",';
        htmlComplete += 'crossDomain: true,';
        htmlComplete += 'success:function(json){';
        htmlComplete += '$( "#incoming_order_block" ).hide();';
		htmlComplete += '$( "#success_html" ).html( json["msg"] );';
        htmlComplete += '}';
        htmlComplete += '});';
		htmlComplete += '});';

		htmlComplete += '$("#add_to_pending_incoming_order").click(function(){';
		htmlComplete += '$( "#success_html" ).empty();';
		htmlComplete += 'var pid = $("#productid").val();';
		htmlComplete += 'var quantity = $("input[name=qty]").val();';
		htmlComplete += 'var order_id = $("input[name=orderid]").val();';
        htmlComplete += '$.ajax({';
        htmlComplete += 'url:"index.php?route=sale/orderq/add_to_pending_incoming_order&token="+token+"&product_id="+pid+"&quantity="+quantity+"&order_id="+order_id,';
        htmlComplete += 'dataType: "json",';
        htmlComplete += 'crossDomain: true,';
        htmlComplete += 'success:function(json){';
        htmlComplete += '$( "#incoming_order_block" ).hide();';
		htmlComplete += '$( "#success_html" ).html( json["msg"] );';
        htmlComplete += '}';
        htmlComplete += '});';
		htmlComplete += '});';

        htmlComplete +=' $("#addmore").click(function(){';
          htmlComplete +='     var counter=$("#addmore").attr("data-count");';
              htmlComplete +='    counter++;';
              htmlComplete +='    var htmlAdd="";';
              //htmlComplete += "htmlAdd += '<input type=\"hidden\" name=\"productid\" value=\""+product_id+"\">';";
              htmlComplete +="   htmlAdd +='<div id=\"mainrow'+counter+'\"><div class=\"form-group\">';";
             htmlComplete +=" htmlAdd +='       <div class=\"col-sm-3\">';";
              
            htmlComplete +="  htmlAdd +='<input type=\"text\"  class=\"form-control\" name=\"locations['+counter+'][location_name]\" value=\"\">';";
             htmlComplete +=" htmlAdd +='</div>';";
             
             htmlComplete +=" htmlAdd +='<div class=\"col-sm-3\">';";
              htmlComplete +="htmlAdd +='<input type=\"number\"  id=\"qty_to_be_add'+counter+'\"  name=\"locations['+counter+'][location_quantity]\" value=\"\" class=\"form-control\">';";
             htmlComplete +=" htmlAdd +=' </div>';";

             htmlComplete +=" htmlAdd +='<div class=\"col-sm-3\">';";
              htmlComplete +="htmlAdd +='<select id=\"assigned_value'+counter+'\" onchange=\"calculateQuantity('+counter+')\" class=\"form-control\" name=\"locations['+counter+'][location_assigned]\" ><option value=\"\">Please Select</option><option value=\"front\">Front Instock</option><option value=\"storage\">Storage Instock</option></select>';";
             htmlComplete +=" htmlAdd +=' </div>';";
            
             htmlComplete +=" htmlAdd +=' <div class=\"col-sm-3 hidden\">';";
           
             htmlComplete +=" htmlAdd +='<input type=\"text\" class=\"form-control\" name=\"locations['+counter+'][location_unit]\" value=\"\">';";
             htmlComplete +=" htmlAdd +='  </div><div class=\"col-sm-3\"><button onclick=\"removerow('+counter+')\"  class=\"btn btn-danger\" type=\"button\"><i class=\"fa fa-minus-circle\"></i></button></div>';";

            htmlComplete +="  htmlAdd +=' </div></div>';";
              
             htmlComplete +=" $('#appendhere').append(htmlAdd);";
            
             htmlComplete +=" $('#addmore').attr('data-count',counter);";


           htmlComplete +=" });";
            htmlComplete +="function removerow(row_num){";
               htmlComplete +="$(\"#mainrow\"+row_num).remove();";
            htmlComplete +="};";
		htmlComplete += '</script>';
		  //htmlComplete += '<div class="row form-group"><label class="control-label col-sm-12">Front Instock</label><div class="col-sm-10"><input type="text" name="front_quantity" class="form-control" value="'+data.product_data.front_quantity+'"></div></div><div class="row form-group"><label class="control-label col-sm-12">Storage Instock</label><div class="col-sm-10"><input type="text" name="storage_quantity" class="form-control" value="'+data.product_data.storage_quantity+'"></div></div>';
		  
		  htmlComplete += '<script>';
		  htmlComplete += 'function updateCustomProductLabels() { ';
		  htmlComplete += '$("#update_label_btn").html("Updating...");';
		  htmlComplete += '$.ajax({';
		  htmlComplete += 'type:"post",';
		  htmlComplete += 'url:"index.php?route=module/pos/updateCustomProductLabels&token="+token,'; 
		  htmlComplete += 'data:{"show_product_label_1":$("input[name=show_product_label_1]:checked").val(), "product_label_text_1":$("input[name=product_label_text_1]").val(), "show_product_label_2":$("input[name=show_product_label_2]:checked").val(), "product_label_text_2":$("input[name=product_label_text_2]").val(), "product_id": ' + product_id + '},';
		  htmlComplete += 'cache:false,';
		  htmlComplete += 'success:function(){';
		  htmlComplete += '$("#update_label_btn").html("Updated");';
		  htmlComplete += '}';
		  htmlComplete += '});';
		  htmlComplete += '}';
		  htmlComplete += '</script>'; 
		 
		  /*htmlComplete += '<h3 style="clear:both";>Product Labels</h3>';
		  
		  htmlComplete += '<div class="row form-group"><label class="col-sm-4 control-label">Display Label 1</label><div class="col-sm-4"><input type="radio"  name="show_product_label_1" value="1">Yes</div><div class="col-sm-4"><input type="radio" name="show_product_label_1" value="0">No</div><div class="col-sm-10"><input type="text" name="product_label_text_1" class="form-control" value="'+product_label_text_1+'"></div></div>';

		  htmlComplete += '<div class="row form-group"><label class="col-sm-4 control-label">Display Label 2</label><div class="col-sm-4"><input type="radio"  name="show_product_label_2" value="1">Yes</div><div class="col-sm-4"><input type="radio" name="show_product_label_2" value="0">No</div><div class="col-sm-10"><input type="text" name="product_label_text_2" class="form-control" value="'+product_label_text_2+'"></div></div>';

		  htmlComplete += '<div class="col-sm-2" style="padding:10px;"><button id="update_label_btn" onclick="updateCustomProductLabels();" type="button" class="btn btn-success"> Update</button></div>';
  		  */
		 $("#updateStockContainer").html(htmlComplete);
		 $("#updateStockContainer").find($("input[name=show_product_label_1][value=" + show_product_label_1 + "]")).prop('checked', true);
		 $("#updateStockContainer").find($("input[name=show_product_label_2][value=" + show_product_label_2 + "]")).prop('checked', true);
		 $(".buttons-wrap").css('display','none');
       }
	});
}

function calculateQuantity(id){

	
	var front_quantity_val=0;

	var storage_quantity_val=0;


	if($("#assigned_value"+id).val()=='front'){
			var qtys=$("#qty_to_be_add"+id).val();
			var qty_to_be_added= $("input[name=front_quantity]").val();
			if(qty_to_be_added==''){qty_to_be_added=0;}
		 $("input[name=front_quantity]").val(parseInt(qtys)+parseInt(qty_to_be_added));
		 front_quantity_val=	$("input[name=front_quantity]").val();
		 if(front_quantity_val==""){
		 	front_quantity_val=0;
		 }

		 storage_quantity_val=$("input[name=storage_quantity]").val();
		 if(storage_quantity_val==""){
		 	storage_quantity_val=0;
		 }

}else{
			var qtys=$("#qty_to_be_add"+id).val();
			var qty_to_be_added= $("input[name=storage_quantity]").val();
			if(qty_to_be_added==''){qty_to_be_added=0;}
			 $("input[name=storage_quantity]").val(parseInt(qtys)+parseInt(qty_to_be_added));
			 front_quantity_val=	$("input[name=front_quantity]").val();
			  if(front_quantity_val==""){
		 	front_quantity_val=0;
		 }

			 storage_quantity_val=$("input[name=storage_quantity]").val();
			 if(storage_quantity_val==""){
		 	storage_quantity_val=0;
		 }
}
front_quantity_val = $("#qty_to_be_add"+id).val();
storage_quantity_val = $("#totalinstock").val();

$("input[name=total_instock]").val(parseInt(front_quantity_val)+parseInt(storage_quantity_val));

}
function accessStockManger(tab){

stockManagerShow();


}

  
function updateProductInIframe(){
	//$('iframe').contents().find('#updateProductFromIframe').trigger('click');
	var html = '<i class="fa fa-spinner fa-spin"></i> Please Wait';
	var old_html = $("#update_product_inframe").html();
	$("#update_product_inframe").html(html);
	var form = $('iframe').contents().find("#form-product");
	var url = form.attr('action');
	$.ajax({
		type: "POST",
		url: url,
		data: form.serialize(), // serializes the form's elements.
		success: function(data)
		{
			$("#update_product_inframe").html(old_html);
			$("#editProductPage .close").click(); 
		}
	  });

	  //$("#editProductPage .close").click();
}

function selectProductForReturn(order_product_id) {
	// use order_product_id to retrieve the details
	for (var i in browseItems) {
		var order_product_item = browseItems[i];
		if (order_product_item['order_product_id'] == order_product_id) {
			// populate the return dialog
			$('input[name=return_order_product_id]').val(order_product_id);
			$('input[name=return_product_id]').val(0);
			$('#return_product_name').val($('<div/>').html(order_product_item['name']).text());
			$('#return_product_model').val(order_product_item['model']);
			if (order_product_item['option'] || parseInt(order_product_item['weight_price']) || order_product_item['sn']) {
				html = '';
				for (var optioni in order_product_item['option']) {
					var option = order_product_item['option'][optioni];
					html += option['name'] + ': ' + option['value'] + '<br />';
				}
				if (parseInt(order_product_item['weight_price'])) {
					html += order_product_item['weight_name'] + ': ' + order_product_item['weight'] + '<br />';
				}
				if (order_product_item['sn']) {
					html += 'SN: ' + order_product_item['sn'] + '<br />';
				}
				$('#return_product_options').html(html);
			} else {
				$('#return_product_options').text('');
			}
			openFancybox('#return_dialog', 'normal');
			break;
		}
	}
};

function selectProductForReturnWithOptions(product_id, options) {
	// use product_id to retrieve all required info from browse_item variable, which is populated from loading the categories via ajax
	for (var i in browse_items) {
		var browse_item = browse_items[i];
		if (browse_item['type'] == 'P' && browse_item['product_id'] == product_id) {
			// populate the return dialog
			$('input[name=return_order_product_id]').val(0);
			$('input[name=return_product_id]').val(product_id);
			$('#return_product_name').val($('<div/>').html(browse_item['name']).text());
			$('#return_product_model').val(browse_item['model']);
			if (options) {
				$('#return_product_options').html('<ul class="form_list">' + options + '</ul>');
			} else {
				$('#return_product_options').text('');
			}
			openFancybox('#return_dialog', 'normal');
			break;
		}
	}
};

function checkOrderStatus()
{
	var order_status_name = $("#order_status_name").text();
	if( order_status_name == 'Waiting for Customer Response' )
	{
		openAlert("Please first select an order status.");
		return false;
	}
}

function fencyCloseButton()
{
	var order_status_name = $("#order_status_name").text();
	if( order_status_name == 'Waiting for Customer Response' )
	{
		openAlert("Please first select an order status.");
		return false;
	} else {
		$(".fancybox-close").prop("onclick", null);
		$(".fancybox-close").attr("onclick","$.fancybox.close()");
	}
	$.fancybox.close();
}

$(document).on('click', '.create_empty_order_btn', function() {
	var order_status_name = $("#order_status_name").text();
	if( order_status_name == 'Waiting for Customer Response' )
	{
		openAlert("Please first select an order status.");
		return false;
	}
	$.ajax({
			url: 'index.php?route=module/pos/createEmptyOrderAction&token=' + token,
			type: 'post',
			data: {},
			dataType: 'json',
			cache:false,
			success: function(data) {
				window.location.href = 'index.php?route=module/pos/main&token=' + token;
			}
		});
});

$(document).on('click', '#button_return', function() {
	// add the product to the return list
	// check if the product was returned before
	var return_order_product_id = parseInt($('input[name=return_order_product_id]').val());
	if (isNaN(return_order_product_id)) {
		return_order_product_id = 0;
	}
	
	// if it comes from the return with order
	if (return_order_product_id != 0) {
		var rowIndex = -1;
		var cur_quantity = parseInt($('input[name=return_quantity]').val());
		var return_id = 0;
		var returned_quantity = 0;
		if( $('#product tr').length > 0 )
		{
			for (var index = 0; index < $('#product tr').length; index++) {
				if (parseInt($('#product tr:eq('+index+') input[name$=\'[order_product_id]\']').val()) == return_order_product_id) {
					rowIndex = index;
					//returned_quantity += parseInt($('#product tr:eq('+index+') input[name$=\'[quantity]\']').val());
					returned_quantity += parseInt($('input[name$=\'[return_quantity]\']').val()); alert("asd" + returned_quantity);
					return_id = parseInt($('#product tr:eq('+index+') input[name$=\'[return_id]\']').val());
					break;
				}
			}
		} else {
			//returned_quantity = parseInt($('input[name=return_quantity]').val()); 
		}
		
		var data = {'order_product_id':return_order_product_id, 'return_id':return_id};
		$.ajax({
			url: 'index.php?route=module/pos/checkReturn&token=' + token,
			type: 'post',
			data: data,
			dataType: 'json',
			cacheCallback: function(json) {
				// do not save the cache
			},
			cachePreDone: function(cacheKey, callback) {
				// Get from the local cache to see if any return for that order_product_id
				backendCheckReturn(data, callback);
			},
			success: function(json) {
				returned_quantity += parseInt(json['quantity']);
				var varIndex = -1;
				for (var index = 0; index < browseItems.length; index ++) {
					if (browseItems[index]['order_product_id'] && browseItems[index]['order_product_id'] == return_order_product_id) {
						varIndex = index;
						break;
					}
				}
				if (returned_quantity + cur_quantity > browseItems[varIndex]['quantity']) {
					openAlert(text_return_quantity_invalid.replace('%s', returned_quantity));
				} else {
					processReturn(return_order_product_id, rowIndex, returned_quantity + cur_quantity - parseInt(json['quantity']));
				}
			}
		});
	} else {
		processReturn(0, 0, 0);
	}
});

function processReturn(return_order_product_id, order_product_row_index, order_product_quantity) {
	var return_quantity = order_product_quantity ? order_product_quantity : parseInt($('input[name=return_quantity]').val());
	var encodedKey = '';
	
	var product_id = parseInt($('input[name=return_product_id]').val());
	if (isNaN(product_id)) {
		product_id = 0;
	}
	
	var order_id, product_name, product_model, return_price_text, return_price, return_tax, date_ordered, weight_price, weight_name, weight, return_option = [], return_sn, return_image, tax_class_id, shipping;
	
	var varIndex = -1;	// the index of the order_product in the javascript variable browse_items
	var rowIndex = return_order_product_id ? order_product_row_index : -1;	// the index of the order_product in the return product list
	if (return_order_product_id != 0) {
		for (var index = 0; index < browseItems.length; index ++) {
			if (browseItems[index]['order_product_id'] && parseInt(browseItems[index]['order_product_id']) == parseInt(return_order_product_id)) {
				varIndex = index;
				break;
			}
		}
	} else {
		// base64 encode the product id and the options to match duplications
		var addData = {'product_id':product_id};
		
		weight_price = $('#return_product_options input[name=weight_price]').val();
		weight = $('#return_product_options input[name=weight]').val();
		weight_name = $('#return_product_options input[name=weight_name]').val()
		if (parseInt(weight_price) == 1) {
			addData[weight_name] = weight;
		} else {
			weight = 1;
		}
		
		return_sn = $('#return_product_options input[name=product_sn]').val();
		if (return_sn) {
			addData['product_sn'] = return_sn;
		}
		
		var options = [];
		$('#return_product_options input[type=text], #return_product_options input[type=radio]:checked, #return_product_options input[type=checkbox]:checked, #return_product_options select, #return_product_options textarea').each(function() {
			var attrName = $(this).attr('name');
			var tagType = $(this).prop('tagName').toLowerCase();
			if ((tagType == 'input' && $(this).attr('type') == 'checkbox') || attrName.substring(0, 7) == 'option[') {
				var option_id, value;
				if (tagType == 'input' && $(this).attr('type') == 'checkbox') {
					// seems next with selector doesn't work
					// attrName = $(this).next('input[type=hidden]').attr('name');
					attrName = $(this).next().next().next().attr('name');
					var attrIndex1 = attrName.indexOf('[');
					var attrIndex2 = attrName.indexOf(']');
					option_id = parseInt(attrName.substring(attrIndex1+1, attrIndex2));
					var searchKey = '[product_option_value_id][';
					attrIndex1 = attrName.indexOf(searchKey) + searchKey.length;
					for (attrIndex2 = attrIndex1 + 1; attrIndex2 < attrName.length; attrIndex2++) {
						if (attrName.charAt(attrIndex2) == ']') {
							break;
						}
					}
					value = attrName.substring(attrIndex1, attrIndex2);
					console.log(attrName + ', option id: ' + option_id + ', value: ' + value);
					var product_option_value_id = parseInt(value);
					// checkbox value is an array
					for (var i = 0; i < options.length; i++) {
						if (options[i]['option_id'] == option_id) {
							// found the array element, insert the value in the right position
							for (var j = 0; j < options[i]['value'].length; j++) {
								if (product_option_value_id < options[i]['value'][j]) {
									break;
								}
							}
							// need insert before position j
							options[i]['value'].splice(j, 0, product_option_value_id);
							break;
						}
					}
					if (i == options.length) {
						// no such option_id inserted yet
						options.push({'option_id':option_id, 'value':[product_option_value_id]});
					}
				} else {
					var attrIndex1 = attrName.indexOf('[');
					var attrIndex2 = attrName.indexOf(']');
					option_id = parseInt(attrName.substring(attrIndex1+1, attrIndex2));
					value = $(this).val();
					options.push({'option_id':option_id, 'value':value});
				}
				
				// get the name and value into the array
				for (var optioni in product_add_option) {
					if (parseInt(product_add_option[optioni]['product_option_id']) == option_id) {
						var option_type = product_add_option[optioni]['type'];
						var product_option_value_id = 0;
						if (option_type == 'select' || option_type == 'radio' || option_type == 'image' || option_type == 'checkbox') {
							product_option_value_id = value;
							value = $('#return_product_options input[name="option[' + option_id + '][value]"]').val();
							if (option_type == 'checkbox') {
								value = $('#return_product_options input[name="option[' + option_id + '][product_option_value_id][' + product_option_value_id + '][value]"]').val();
							}
						}
						return_option.push({'product_option_id':option_id, 'product_option_value_id':product_option_value_id, 'type':option_type, 'name':product_add_option[optioni]['name'], 'value':value});
						break;
					}
				}
			}
		});
		if (options.length > 0) {
			// sort the array by option_id
			for (var i = 0; i < options.length-1; i++) {
				for (var j = i+1; j < options.length; j++) {
					if (options[j]['option_id'] < options[i]['option_id']) {
						var tmp = options[j];
						options[j] = options[i];
						options[i] = tmp;
					}
				}
			}
			addData['options'] = options;
		}
		encodedKey = base64_encode(JSON.stringify(addData));
		
		for (var index = 0; index < $('#product tr').length; index++) {
			if ($('#product tr:eq('+index+') input[name$=\'[encodedKey]\']').val() == encodedKey) {
				rowIndex = index;
				return_quantity += parseInt($('#product tr:eq('+index+') input[name$=\'[quantity]\']').val());
				break;
			}
		}
	}
	
	var opened = $('select[name=return_opened]').val();
	var return_reason_id = $('select[name=return_reason_id]').val();
	var return_action_id = 0;
	var comment = $('textarea[name=return_comment]').val();
	
	if (return_order_product_id != 0) {
		product_id = browseItems[varIndex]['product_id'];
		order_id = browseItems[varIndex]['order_id'];
		product_name = $('<div/>').html(browseItems[varIndex]['name']).text();
		product_model = browseItems[varIndex]['model'];
		return_price_text = browseItems[varIndex]['price_text'];
		return_price = browseItems[varIndex]['price'];
		return_tax = browseItems[varIndex]['tax'];
		weight_price = browseItems[varIndex]['weight_price'];
		weight_name = browseItems[varIndex]['weight_name'];
		weight = browseItems[varIndex]['weight'];
		return_sn = browseItems[varIndex]['sn'];
		return_option = browseItems[varIndex]['option'];
		date_ordered = browseItems[varIndex]['date_ordered'];
		return_image = browseItems[varIndex]['image'];
		tax_class_id = browseItems[varIndex]['tax_class_id'];
		shipping = browseItems[varIndex]['shipping'];
	} else {
		order_id = 0;
		for (var index = 0; index < browse_items.length; index ++) {
			if (browse_items[index]['type'] && browse_items[index]['type'] == 'P' && parseInt(browse_items[index]['product_id']) == product_id) {
				product_name = $('<div/>').html(browse_items[index]['name']).text();
				product_model = browse_items[index]['model'];
				return_price_text = browse_items[index]['price_text'];
				return_price = parseFloat(browse_items[index]['price']);
				return_tax = browse_items[index]['tax'];
				weight_price = browse_items[index]['weight_price'];
				weight_name = browse_items[index]['weight_name'];
				return_image = browse_items[index]['image'];
				tax_class_id = browse_items[index]['tax_class_id'];
				shipping = browse_items[index]['shipping'];
				break;
			}
		}
		date_ordered = '0000-00-00';
	}
	
	var data = {'quantity':return_quantity, 'order_product_id':return_order_product_id, 'order_id':order_id, 'product_id':product_id, 'product':product_name, 'model':product_model, 'opened':opened, 'return_reason_id':return_reason_id, 'return_action_id':return_action_id, 'return_status_id':return_status_id, 'pos_return_id':pos_return_id, 'customer_id':customer_id, 'firstname':firstname, 'lastname':lastname, 'email':email, 'telephone':telephone, 'price':return_price, 'tax':return_tax, 'date_ordered':date_ordered, 'comment':comment, 'option': return_option};
	data['price'] = return_price;
	data['tax'] = return_tax;
	data['image'] = return_image;
	if (parseInt(weight_price) == 1) {
		data['weight_name'] = weight_name;
		data['weight'] = weight;
	} else {
		data['weight'] = 1;
	}
	data['price_change'] = parseFloat(return_price) * parseInt($('input[name=return_quantity]').val()) * parseFloat(data['weight']);
	data['tax_change'] = parseFloat(return_tax) * parseInt($('input[name=return_quantity]').val()) * parseFloat(data['weight']);
	if (return_sn) {
		data['sn'] = return_sn;
	}
	
	if (rowIndex >= 0) {
		// row exists, update the quantity and total for the existing row
		var ex_price = posParseFloat($('#price_anchor_'+rowIndex).text().substring(2));
		
		$('.quantity_anchor_'+rowIndex).text(return_quantity);
		$('#product tr:eq('+rowIndex+') input[name$=\'[quantity]\']').val(return_quantity)
		
		var td_total = $('#price_anchor_' + rowIndex).closest('tr').find('td:nth-last-child(2)');
		var text_total = formatMoney(return_quantity * ex_price);
		td_total.text(text_total);
		
		data['price'] = $('input[name="order_product[' + rowIndex + '][price]"]').val();
		data['tax'] = $('input[name="order_product[' + rowIndex + '][tax]"]').val();
		data['return_id'] = $('input[name="order_product[' + rowIndex + '][return_id]"]').val();
		$.ajax({
			url: 'index.php?route=module/pos/editReturn&token=' + token,
			type: 'post',
			data: data,
			dataType: 'json',
			cacheCallback: function(json) {
				// do not save the cache
			},
			cachePreDone: function(cacheKey, callback) {
				backendEditReturn(data, callback);
			},
			success: function(json) {
				removeMessage();
				showMessage('success', json['success']);
			}
		});
	} else {
		// add a new row
		var new_row_num = $('#product tr').length;
		new_row_id = 'product-row' +  new_row_num;
		html = '<tr id="' + new_row_id + '" class="' + ((new_row_num % 2 == 0) ? 'even' : 'odd') + '">';
		html += '<input type="hidden" name="order_product[' + new_row_num + '][encodedKey]" value="' + encodedKey + '" />';
		html += '<input type="hidden" name="order_product[' + new_row_num + '][return_id]" value="" />';
		html += '<input type="hidden" name="order_product[' + new_row_num + '][order_product_id]" value="' + return_order_product_id + '" />';
		html += '<input type="hidden" name="order_product[' + new_row_num + '][order_id]" value="' + order_id + '" />';
		html += '<input type="hidden" name="order_product[' + new_row_num + '][product_id]" value="' + product_id + '" />';
		html += '<input type="hidden" name="order_product[' + new_row_num + '][quantity]" value="' + return_quantity + '" />';
		html += '<input type="hidden" name="order_product[' + new_row_num + '][tax_class_id]" value="' + tax_class_id + '" />';
		html += '<input type="hidden" name="order_product[' + new_row_num + '][shipping]" value="' + shipping + '" />';
		html += '<td align="center" valign="middle" class="one"><span class="cart-round-img-outr" onclick="changeQuantity(this,'+new_row_num+');"><img src="' + data['image'] + '" class="cart-round-img" alt=""><a class="cart-round-qty quantity_anchor_' + new_row_num + '">' + return_quantity + '</a></span></td>';
		html += '<td align="left" valign="middle" class="two">';
		html += '	<span class="product-name">';
		html += '		<span class="raw-name" onclick="showReturnDetails(this)" id="order_product[' + new_row_num + '][order_product_display_name]">' + product_name + '</span>';
		if (parseInt(weight_price) == 1) {
			html += '<br />&nbsp;<small> - ' + weight_name + ': ' + weight;
		}
		html +=	'<input type="hidden" name="order_product[' + new_row_num + '][weight_price]" value="1" />';
		html +=	'<input type="hidden" name="order_product[' + new_row_num + '][weight]" value="' + weight + '" />';
		for (var j in return_option) {
			var name = return_option[j]['name'];
			var value = return_option[j]['value'];
			html +=		'<br />&nbsp;<small> - ' + name + ': ' + value + '</small>';
		}
		// add for serial no begin
		if (return_sn) {
			html += '<br />&nbsp;<small> - SN: ' + return_sn;
		}
		html += '	</span>';
		html += '	<span class="highlight" onclick="changePrice(this);"><a id="price_anchor_' + new_row_num + '">@ ' + return_price_text + '</a></span>';
		html += '</td>';
		html += '<input type="hidden" name="order_product[' + new_row_num + '][price]" value="' + return_price + '" />';
		html += '<input type="hidden" name="order_product[' + new_row_num + '][tax]" value="' + return_tax + '" />';
		html += '<input type="hidden" name="order_product[' + new_row_num + '][tax_class_id]" value="' + tax_class_id + '" />';
		html += '<input type="hidden" name="order_product[' + new_row_num + '][shipping]" value="' + shipping + '" />';
		html += '<td align="center" valign="middle" class="three"><a onclick="$(\'#price_anchor_' + new_row_num + '\').closest(\'span\').trigger(\'click\');" class="cart-link"><span class="product-price" id="total_text_only-' + new_row_num + '">' + formatMoney(return_quantity * (parseFloat(return_price) + parseFloat(return_tax)) * parseFloat(weight)) + '&nbsp;CR</span></a>';
		html += '<input type="hidden" name="order_product[' + new_row_num + '][product_total_text]" value="' + formatMoney(return_quantity * (parseFloat(return_price) + parseFloat(return_tax)) * parseFloat(weight)) + '" /></td>';
		html += '<td align="center" valign="middle" class="four"><a class="delete" onclick="deleteReturnProduct(this)"></a></td>';
		html += '</tr>';
		
		$('#product').append(html);
	
		// send date to backend to store the return info
		$.ajax({
			url: 'index.php?route=module/pos/addReturn&token=' + token,
			type: 'post',
			data: data,
			dataType: 'json',
			cacheCallback: function(json) {
				// do not save the cache
			},
			cachePreDone: function(cacheKey, callback) {
				backendAddReturn(data, callback);
			},
			success: function(json) {
				removeMessage();
				showMessage('success', json['success']);
				// update the return id for the row
				$('input[name="order_product[' + new_row_num + '][return_id]"]').val(json['return_id']);
			}
		});
	}
	
	// update totals
	for (var i in totals) {
		if (totals[i]['code'] == 'tax') {
			totals[i]['value'] = parseFloat(totals[i]['value']) + parseFloat(return_tax) * parseInt($('input[name=return_quantity]').val()) * parseFloat(weight);
		} else if (totals[i]['code'] == 'subtotal') {
			totals[i]['value'] = parseFloat(totals[i]['value']) + parseFloat(return_price) * parseInt($('input[name=return_quantity]').val()) * parseFloat(weight);
		} else if (totals[i]['code'] == 'total') {
			totals[i]['value'] = parseFloat(totals[i]['value']) + (parseFloat(return_price) + parseFloat(return_tax)) * parseInt($('input[name=return_quantity]').val()) * parseFloat(weight);
		}
	}
	
	updateTotal(totals);
	closeFancybox();
};

function highlightStr(str, search) {
	// only highlight the first occurrence as it gives us the information info
	var highlighted = str;
	
	var index = str.toLowerCase().indexOf(search.toLowerCase());
	if (index >= 0) {
		highlighted = str.substring(0, index) + '<span style="background-color:red;color:yellow;">' + str.substring(index, index+search.length) + '</span>' + str.substring(index+search.length);
	}
	
	return highlighted;
};

function showCategoryItems(category_id) {
	var data = {'category_id':category_id, 'currency_code':currency_code, 'currency_value':currency_value, 'customer_group_id':customer_group_id};
	$.ajax({
		url: 'index.php?route=module/pos/getCategoryItemsAjax&token=' + token,
		type: 'post',
		dataType: 'json',
		data: data,
		beforeSend: function() {
			removeMessage();
			showMessage('notification', '');
		},
		cacheCallback: function(json) {
			backendSaveCategory(category_id, json);
		},
		cachePreDone: function(cacheKey, callback) {
			backendGetCategory(category_id, callback);
		},
		complete: function() {
			removeMessage();
			if (text_work_mode == '2') {
				showMessage('success', text_quote_ready);
			} else if (text_work_mode == '1') {
				showMessage('success', text_return_ready);
			} else {
				showMessage('success', text_order_ready);
			}
		},
		success: function(json) {
			// reset the product_id field for shortcut logic
			$('input[name=current_product_id]').val('0');
			
			$('#browse_category').empty();
			var ulhtml = '<li><a onclick="showCategoryItems(\'' + text_top_category_id + '\')" class="home-icon last"></a></li>';
			if (json['path']) {
				if (json['path'].length == 0) {
					$('input[name=current_category_id]').val(0);
					$('input[name=current_category_name]').val('');
					$('input[name=current_category_image]').val('');
				} else {
					ulhtml = '<li><a onclick="showCategoryItems(\'' + text_top_category_id + '\')" class="home-icon"></a></li>';
					for (var i = 0; i < json['path'].length; i++) {
						if (i == json['path'].length - 1) {
							$('input[name=current_category_id]').val(category_id);
							$('input[name=current_category_name]').val(json['path'][i]['name']);
							$('input[name=current_category_image]').val(json['path'][i]['image']);
							ulhtml += '<li><a onclick="showCategoryItems(\'' + json['path'][i]['id'] + '\')">' + json['path'][i]['name'] + '</a></li>';
						} else {
							ulhtml += '<li><a onclick="showCategoryItems(\'' + json['path'][i]['id'] + '\')" class="last">' + json['path'][i]['name'] + '</a></li>';
						}
					}
				}
			}
			$('#browse_category').html(ulhtml);
			if (json['browse_items']) {
				// clean up the display table
				populateBrowseTable(json['browse_items']);
			}
		}
	});
};

function selectProduct(product_id) {
	// check if previous option was not selected and another product is selected
	if ($('#option div').length > 0) {
		deQueue();
		$('#option').empty();
		product_add_option = [];
	}
	if (browseQ.length > 0) {
		enQueue(product_id);
	} else {
		enQueue('processing...');
		processSelectProduct(product_id);
	}
};

function getProduct(product_id) {
	var product;
	if (browse_items && browse_items.length > 0) {
		for (var i = 0; i < browse_items.length; i++) {
			if (browse_items[i]['type'] == 'P' && browse_items[i]['product_id'] == product_id) {
				product =  browse_items[i];
				break;
			}
		}
	}
	return product;
};

function processSelectProduct(product_id, shortcut) {
	var product = getProduct(product_id);
	if (shortcut) {
		product = shortcut;
	}
	if (product) {
		var hasOption = parseInt(product['hasOptions']);
		var product_name = product['name'];
		var sku = product['sku'];
		var weight_price = parseInt(product['weight_price']);
		var weight_name = product['weight_name'];
		var price = (parseInt(config['config_tax']) == 1) ? parseFloat(product['price']) + parseFloat(product['tax']) : parseFloat(product['price']);
		var points = parseInt(product['points']);
		var has_sn = parseInt(product['has_sn']);
		var product_image = product['image'];
		var reward_points = product['reward_points'] ? product['reward_points'] : 0;
		var labour_cost = product['labour_cost'] ?  parseFloat(product['labour_cost']).toFixed(2) : 0;
		var unique_option_price = product['unique_option_price'] ?  parseFloat(product['unique_option_price']).toFixed(5) : 0;
		var subtract = product['subtract'];
		
		// add the given product with the product_id
		$('#product_new input[name=quantity]').val('1');
		$('#product_new input[name=product_id]').val(product_id);
		$('#product_new input[name=main_product_id]').val(product_id);
		$('#product_new input[name=product]').val(product_name);
		$('#product_new input[name=product_price]').val(price);
		$('#product_new input[name=product_points]').val(points);
		$('#product_new input[name=sku]').val(sku);
		$('#product_new input[name=product_image]').val(product_image);
		$('#product_new input[name=product_reward_points]').val(reward_points);
		$('#product_new input[name=subtract]').val(subtract);
		$('#product_new input[name=product_labour_cost]').val(labour_cost);
		$('#product_new input[name=product_unique_option_price]').val(unique_option_price); 

		if (hasOption || weight_price || has_sn) {
			$('#option').empty();
			// set weight as option
			var html = '';
			if (weight_price) {
				html += '<li id="option-pos-weight">';
				html += '<label>' + weight_name + '</label>';
				html += '<div class="inputbox"><input type="text" name="weight" value="" />';
				html += '<input type="hidden" name="weight_price" value="1" />';
				html += '<input type="hidden" name="weight_name" value="' + weight_name + '" />';
				html += '</div></li>';
			}
			if (has_sn) {
				html += '<li id="option-pos-sn">';
				html += '<label>' + entry_product_sn + '</label>';
				html += '<div class="inputbox"><input type="text" name="product_sn" value="" />';
				html += '</div></li>';
			}
			if (html != '') {
				$('#option').append(html);
			}
			if (hasOption) {
				var customer_group_id = $('select[name=customer_group_id]').val();
				$.ajax({
					url: 'index.php?route=module/pos/getProductOptions&token=' + token + '&product_id=' + product_id+ '&customer_group_id=' + customer_group_id,
					type: 'post',
					dataType: 'json',
					data: {},
					beforeSend: function() {
						openWaitDialog();
					},
					complete: function() {
						closeWaitDialog();
						
						$(".optionschange").each(function() {
						  $(this).trigger('change');
						});
											},
					cacheCallback: function(json) {
						if (json) {
							backendSaveProduct({'product_id': product_id, 'option': json['option_data']});
						}
					},
					cachePreDone: function(cacheKey, callback) {
						backendGetProduct(product_id, callback);
					},
					success: function(json) {
						if (json && json['option_data']) {
							if (text_work_mode == '1') {
								handleOptionReturn(product_name, product_id, json['option_data'], false,json['unit_datas'],json['DefaultUnitdata'],json);
								var options = $('#option').html();
								selectProductForReturnWithOptions(product_id, options);
							} else {
								handleOptionReturn(product_name, product_id, json['option_data'], true,json['unit_datas'],json['DefaultUnitdata'],json);
								
							}
						} else if (html != '') {
							if (text_work_mode == '1') {
								selectProductForReturnWithOptions(product_id, html);
							} else {
								// only popup the weight
								popupOption(html);
							}
						} else {
							// although the hasOptions indicates it has options, but if the options were removed, or in offline mode, it's not cached
							if (text_work_mode == '1') {
								selectProductForReturnWithOptions(product_id);
							} else {
								// no option
								chooseProduct();
							}
						}
					}
				});
			} else if (html != '') {
				if (text_work_mode == '1') {
					selectProductForReturnWithOptions(product_id, html);
				} else {
					// only popup the weight
					popupOption(html);
				}
			}
		} else {
			if (text_work_mode == '1') {
				selectProductForReturnWithOptions(product_id);
			} else {
				// no option
				chooseProduct();
			}
		}
	} else {
		openAlert(text_local_product_not_found);
	}
};

function processSelectProduct2(product_id, shortcut) {
	var that=this;
	var product = getProduct(product_id);
	
	if (shortcut) {
		product = shortcut;
	}
	if (product) {
		var hasOption = parseInt(product['hasOptions']);
		var product_name = product['name'];
		var weight_price = parseInt(product['weight_price']);
		var weight_name = product['weight_name'];
		var price = (parseInt(config['config_tax']) == 1) ? parseFloat(product['price']) + parseFloat(product['tax']) : parseFloat(product['price']);
		var points = parseInt(product['points']);
		var has_sn = parseInt(product['has_sn']);
		var product_image = product['image'];
		var reward_points = product['reward_points'] ? product['reward_points'] : 0;
		var subtract = product['subtract'];
		
		// add the given product with the product_id
		$('#product_new input[name=quantity]').val('1');
		$('#product_new input[name=product_id]').val(product_id);
		$('#product_new input[name=product]').val(product_name);
		$('#product_new input[name=product_price]').val(price);
		$('#product_new input[name=product_points]').val(points);
		$('#product_new input[name=product_image]').val(product_image);
		$('#product_new input[name=product_reward_points]').val(reward_points);
		$('#product_new input[name=subtract]').val(subtract);

		if (hasOption || weight_price || has_sn) {
			$('#option').empty();
			// set weight as option
			var html = '';
			if (weight_price) {
				html += '<li id="option-pos-weight">';
				html += '<label>' + weight_name + '</label>';
				html += '<div class="inputbox"><input type="text" name="weight" value="" />';
				html += '<input type="hidden" name="weight_price" value="1" />';
				html += '<input type="hidden" name="weight_name" value="' + weight_name + '" />';
				html += '</div></li>';
			}
			if (has_sn) {
				html += '<li id="option-pos-sn">';
				html += '<label>' + entry_product_sn + '</label>';
				html += '<div class="inputbox"><input type="text" name="product_sn" value="" />';
				html += '</div></li>';
			}
			if (html != '') {
				$('#option').append(html);
			}
			if (hasOption) {
				 var groupbyvalue = $('input[name=groupbyvalue]').val();
				 var group_indicator = parseInt($('input[name=groupindicator_id]').val());
				 var product_id = parseInt($('input[name=main_product_id]').val());
				
				 var selOpt=that.selectedOption();
				
				$.ajax({
					url: 'index.php?route=module/pos/getGroupOptions&token=' + token,
					type: 'post',
					dataType: 'json',
					data: {
						'group_indicator': group_indicator,
						'selChoice':selOpt,
						'groupbyname':groupbyvalue,
						'product_id':product_id
					},
					beforeSend: function() {
						openWaitDialog();
					},
					complete: function() {
						closeWaitDialog();
						
					},
					cacheCallback: function(json) {
						if (json) {
							backendSaveProduct({'product_id': product_id, 'option': json['option_data']});
						}
					},
					cachePreDone: function(cacheKey, callback) {
						backendGetProduct(product_id, callback);
					},
					success: function(json) {
						if (json && json['option_data']) {
							if (text_work_mode == '1') {
								handleOptionReturn(product_name, product_id, json['option_data'], false,json['unit_datas'],json['DefaultUnitdata'],json);
								var options = $('#option').html();
								selectProductForReturnWithOptions(product_id, options);
							} else {
								handleOptionReturn(product_name, product_id, json['option_data'], true,json['unit_datas'],json['DefaultUnitdata'],json);
								
							}
						} else if (html != '') {
							if (text_work_mode == '1') {
								selectProductForReturnWithOptions(product_id, html);
							} else {
								// only popup the weight
								popupOption(html);
							}
						} else {
							// although the hasOptions indicates it has options, but if the options were removed, or in offline mode, it's not cached
							if (text_work_mode == '1') {
								selectProductForReturnWithOptions(product_id);
							} else {
								// no option
								chooseProduct();
							}
						}
					}
				});
			} 
			else if (html != '') {
				if (text_work_mode == '1') {
					selectProductForReturnWithOptions(product_id, html);
				} else {
					// only popup the weight
					popupOption(html);
				}
			}
			changeOption('unit[0]');
		} else {
			if (text_work_mode == '1') {
				selectProductForReturnWithOptions(product_id);
			} else {
				// no option
				chooseProduct();
			}
		}
	} else {
		openAlert(text_local_product_not_found);
	}
};


function enQueue(product_id) {
	var data = {'product_id':product_id};
	browseQ.push(data);
};

function deQueue() {
	if (browseQ.length > 0) {
		var data = browseQ.shift();
		if (data['product_id'] == 'processing...') {
			data = browseQ.shift();
		}
		if (data) {
			processSelectProduct(data['product_id']);
		}
	}
};

function quickSale() {
	$("#quick_sale_dialog_fs").val('1');
	openFancybox('#quick_sale_dialog', 'narrow');
};

$(document).on('focus', 'input[name=\'quick_sale_name\']', function(){
	$(this).autocomplete({
		delay: 500,
		source: function(request, response) {
			var url = 'index.php?route=module/pos/autocomplete_product&token=' + token;
			var data = {'filter_name':request.term, 'filter_scopes':searchScopes, 'quick_sale':'1'};
			$.ajax({
				url: url,
				dataType: 'json',
				type: 'post',
				data: data,
				cacheCallback: function(json) {
					backendSaveProducts(json);
				},
				cachePreDone: function(cacheKey, callback) {
					backendGetProducts(data, callback);
				},
				success: function(json) {	
					response($.map(json, function(item) {
						return {
							label: item.name,
							value: item.id,
							model: item.model,
							price: item.price,
							shipping: item.shipping,
							tax_class_id: item.tax_class_id
						}
					}));
				}
			});
		}, 
		select: function(event, ui) {
			$('input[name=quick_sale_name]').val(ui.item['label']);
			$('input[name=quick_sale_product_id]').val(ui.item['value']);
			$('input[name=quick_sale_model]').val(ui.item['model']);
			$('input[name=quick_sale_price]').val(ui.item['price']);
			$('input[name=quick_sale_shipping]').val(ui.item['shipping']);
			if (ui.item['shipping'] == 1) {
				$('input[name=quick_sale_shipping]').prop('checked', true);
			} else {
				$('input[name=quick_sale_shipping]').prop('checked', false);
			}
			$('select[name=quick_sale_tax_class_id]').val(ui.item['tax_class_id']);
			$('select[name=quick_sale_tax_class_id]').trigger('change');
			// the price read from the table does not include the price
			$('input[name=quick_sale_include_tax]').val('0');
			$('input[name=quick_sale_include_tax]').prop('checked', false);
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
});

$(document).on('keydown', 'input[name=\'quick_sale_name\']', function(event) {
	// once enter something in the name field, reset the product_id to 0, which can allow the controller know it's a new product
	$('input[name=quick_sale_product_id]').val('0');
});

$(document).on('keydown', 'input[name=quick_sale_price]', function(event) {
	amountInputOnly(event);
});

$(document).on('keydown', 'input[name=quick_sale_quantity]', function(event) {
	amountInputOnly(event);
});

$(document).on('change', 'select[name=quick_sale_tax_class_id]', function(event) {
	if ($(this).val() == '0') {
		// no tax is required
		$('input[name=quick_sale_include_tax]').attr('checked', false);
		$('input[name=quick_sale_include_tax]').attr('disabled', true);
	} else {
		$('input[name=quick_sale_include_tax]').attr('disabled', false);
	}
});

$(document).on('click', 'input[name=quick_sale_include_tax], input[name=quick_sale_shipping]', function() {
	if ($(this).is(':checked')) {
		$(this).val('1');
	} else {
		$(this).val('0');
	}
});

$(document).on('click', '#button_quick_sale', function() {
	var data = {};
	var valid = true;
	$('#quick_sale_dialog input[type=text], #quick_sale_dialog input[type=hidden], #quick_sale_dialog input[type=checkbox]:checked, #quick_sale_dialog select ').each(function() {
		$(this).css('border', '');
		
		var attrName = $(this).attr('name');
		attrName = attrName.substring(11);
		data[attrName] = $(this).val();
		
		if ($(this).closest('tr').find('em').length && !($(this).val()) && $(this).attr('type') == 'text') {
			valid = false;
			$(this).css('border', 'solid 1px #FF0000');
		}
	});
	
	if (valid) {
		data['action'] = 'insert_quick';
		data['option'] = [];
		data['image'] = 'view/image/pos/no_image.jpg';
		// add for customer loyalty card begin
		data['reward_points'] = '0';
		// add for customer loyalty card end

		addProduct(data);
		
		closeFancybox();
	}
});

var searchScopes = ['name','model','sku','upc','mpn','manufacturer','isbn','location'];

function searchSettings() {
	for (var i in searchScopes) {
		$('input[name=search_scope_' + searchScopes[i] + ']').prop('checked', true);
	}
	openFancybox('#search_settings_dialog', 'narrower');
};

function setSearchScope() {
	searchScopes = [];
	$('#search_settings_dialog input[type=checkbox]:checked').each(function() {
		var name = $(this).attr('name').substring(13);
		searchScopes.push(name);
	});
	
	if (searchScopes.length == 0) {
		searchScopes.push('name');
	}
	
	// save the searchScopes to the local storage
	localStorage.setItem('pos_searchScopes', JSON.stringify(searchScopes));
	
	closeFancybox();
};

$(document).on('click', '#button_set_shortcut', function(event) {
	setShortcut();
});

$('#shortcuts_dialog tr td').on('click', function(e) {

});

$(document).on('keypress', '#shortcuts_dialog input', function(e) {
	if (e.keyCode == $.ui.keyCode.ENTER) {
		// save the display code to the localStorage, update the variable and also update the page
		var index = parseInt($(this).attr('name').substring('shortcut_code'.length));
		shortcuts[index]['code'] = $(this).val();
		localStorage.setItem('pos_shortcut'+index, JSON.stringify(shortcuts[index]));
		
		if (index < 5) {
			$('#shortcut'+index).find('.bg span').text($(this).val());
		}
	}
});

$(document).on('blur', '#shortcuts_dialog input', function(e) {
	// save the display code to the localStorage, update the variable and also update the page
	var index = parseInt($(this).attr('name').substring('shortcut_code'.length));
	$(this).val(shortcuts[index]['code'] ? shortcuts[index]['code'] : (shortcuts[index]['name'].length > 2 ? shortcuts[index]['name'].substring(0,2) : shortcuts[index]['name']));
});

function loadShortcuts() {
	var trClass = 'even';
	var html = '';
	for (var i = 0; i < 10; i++) {
		var shortcuti = localStorage.getItem('pos_shortcut'+i);
		if (shortcuti) {
			shortcuti = JSON.parse(shortcuti);
			shortcuts[i] = shortcuti;
			
			var name = $('<div/>').html(shortcuti['name']).text();
			var code = (shortcuti['code'] ? shortcuti['code'] : (name.length > 2 ? name.substring(0,2) : name));
			if (i < 5) {
				// update the left panel
				$('#shortcut'+i).find('.bg span').removeClass().addClass('letter one');
				$('#shortcut'+i).find('.bg span').text(code);
				$('#shortcut'+i).find('.txt').text(name);
				if (shortcuti['type'] == 'P') {
					$('#shortcut'+i).find('.bg').removeClass().addClass('bg one_letter product');
				} else if (shortcuti['type'] == 'C') {
					$('#shortcut'+i).find('.bg').removeClass().addClass('bg one_letter category');
				}
				// show the shortcuts if it's defined
				$('#shortcut'+i).closest('li').show();
			}
			
			// update the shutcut list
			trClass = (trClass == 'even') ? 'odd' : 'even';
			html += '<li class="' + (shortcuti['type'] == 'P' ? 'product' : 'category') + ' ' + trClass + '">';
            html += '<span class="icon"></span>';
            html += '<span onclick="gotoShortcut(this)" class="txt">' + name + '</span>';
            html += '<div class="right">';
			html += '<input type="text" name="shortcut_code' + i + '" value="' + code + '" maxlength="2">';
            html += '<a onclick="deleteShortcut(this)" class="delete"></a>';
            html += '</div></li>';
		}
	}
	$('.shortcuts_list').html(html);
};

function setShortcut() {
	if ($('#shortcuts_dialog ul li').length >= 10) {
		openAlert(text_shortcut_list_full);
		return;
	}
	
	// get the index for the new shortcut
	var index = 0;
	for (index = 0; index < 10; index++) {
		if (!shortcuts[index]) {
			break;
		}
	}

	var shortcut = {};
	if ($('#browse_list a').length == 1 && parseInt($('input[name=current_product_id]').val())) {
		// check if a single product is in the list and just had the search done
		shortcut['type'] = 'P';
		shortcut['product_id'] = $('input[name=current_product_id]').val();
		shortcut['name'] = $('<div/>').html($('input[name=current_product_name]').val()).text();
		shortcut['weight_price'] = $('input[name=current_product_weight_price]').val();
		shortcut['weight_name'] = $('input[name=current_product_weight_name]').val();
		shortcut['hasOptions'] = $('input[name=current_product_hasOption]').val();
		shortcut['price'] = $('input[name=current_product_price]').val();
		shortcut['tax'] = $('input[name=current_product_tax]').val();
		shortcut['points'] = $('input[name=current_product_points]').val();
		shortcut['image'] = $('input[name=current_product_image]').val();
	} else {
		var category_id = $('input[name=current_category_id]').val();
		if (parseInt(category_id) == 0) return;
		shortcut['type'] = 'C';
		shortcut['category_id'] = category_id;
		shortcut['name'] = $('<div/>').html($('input[name=current_category_name]').val()).text();
		shortcut['image'] = $('input[name=current_category_image]').val();
	}
	
	// save to the local variable
	shortcuts[index] = shortcut;
	// save to the local storage
	localStorage.setItem('pos_shortcut'+index, JSON.stringify(shortcut));
	// update the shortcust list dialog
	var name = shortcut['name'];
	var code = shortcut['name'].length > 2 ? shortcut['name'].substring(0,2) : shortcut['name'];
	var trClass = (index % 2) ? 'odd' : 'even';
	html = '<li class="' + (shortcut['type'] == 'P' ? 'product' : 'category') + ' ' + trClass + '">';
	html += '<span class="icon"></span>';
	html += '<span onclick="gotoShortcut(this)" class="txt">' + name + '</span>';
	html += '<div class="right">';
	html += '<input type="text" name="shortcut_code' + index + '" value="' + code + '" maxlength="2">';
	html += '<a onclick="deleteShortcut(this)" class="delete"></a>';
	html += '</div></li>';
	$('#shortcuts_dialog ul').append(html);
	// update the left panel
	if (index < 5) {
		$('#shortcut'+index).find('.bg span').removeClass().addClass('letter one');
		$('#shortcut'+index).find('.bg span').text(code);
		$('#shortcut'+index).find('.txt').text(name);
		if (shortcut['type'] == 'P') {
			$('#shortcut'+index).find('.bg').removeClass().addClass('bg one_letter product');
		} else if (shortcut['type'] == 'C') {
			$('#shortcut'+index).find('.bg').removeClass().addClass('bg one_letter category');
		}
		// show the shortcuts if it's not visible previously
		$('#shortcut'+index).closest('li').show();
	}

	// shortcut is added
	openAlert(text_shortcut_added);
};

function deleteShortcut(anchor) {
	var index = $('#shortcuts_dialog ul li').index($(anchor).closest('li'));
	// remove the shortcut from the list and sync the shortcut to UI and localstorage
	$('#shortcuts_dialog ul li:eq(' + index + ')').remove();
	
	var i;
	for (i = index+1; i < 10; i++) {
		var current = i - 1;
		var shortcuti = localStorage.getItem('pos_shortcut'+i);
		if (shortcuti) {
			// replace the current on with the next one
			shortcuts[current] = shortcuts[i];
			localStorage.setItem('pos_shortcut'+current, shortcuti);
			if (current < 5) {
				// replace the shortcut left panel content
				shortcuti = JSON.parse(shortcuti);
				var name = $('<div/>').html(shortcuti['name']).text();
				var code = (shortcuti['code'] ? shortcuti['code'] : (name.length >=2 ? name.substring(0,2) : name));
				$('#shortcut'+current).find('.bg span').text(code);
				$('#shortcut'+current).find('.txt').text(name);
				if (shortcuti['type'] == 'P') {
					$('#shortcut'+current).find('.bg').removeClass().addClass('bg one_letter product');
				} else if (shortcuti['type'] == 'C') {
					$('#shortcut'+current).find('.bg').removeClass().addClass('bg one_letter category');
				}
			}
		} else {
			break;
		}
	}
	// remove the last shortcut from local storage and set the position in the variable to false
	var current = i-1;
	shortcuts[current] = false;
	localStorage.removeItem('pos_shortcut'+current);
	if (current < 5) {
		// replace the shortcut left panel content
		var name = text_no_shortcut;
		$('#shortcut'+current).find('.bg').removeClass().addClass('bg');
		$('#shortcut'+current).find('.bg span').removeClass().addClass('icon shortcut');
		$('#shortcut'+current).find('.bg span').text('');
		$('#shortcut'+current).find('.txt').text(name);
		// hide the shortcut if it's visible
		$('#shortcut'+current).closest('li').hide();
	}
	resizeFancybox();
}

function gotoShortcut(selector) {
	if ($('#shortcuts_dialog').is(':visible')) {
		closeFancybox();
	}
	var index = $(selector).closest('ul').find('li').index($(selector).closest('li'));
	if (shortcuts[index]) {
		if (shortcuts[index]['type'] == 'C') {
			showCategoryItems(shortcuts[index]['category_id']);
		} else if (shortcuts[index]['type'] == 'P') {
			processSelectProduct(shortcuts[index]['product_id'], shortcuts[index]);
		}
	};
};

function moreShortcuts() {
	var data = {
		href: '#shortcuts_dialog',
		padding : 0,
		margin  : 10,
		width	: '95%',
		height    : 'auto',
		minWidth  : 220,
		maxWidth  : 450,
		autoSize	: false,
		fitToView	: false,
		openEffect: 'none',
		closeEffect: 'none',
		
	};
	
	$.fancybox(data);
//	openFancybox('#shortcuts_dialog', 'normal');
};

function setTable() {
	if (tables) {
		$('#pos_table_dialog a').each(function() {
			if ($(this).hasClass('selected')) {
				$(this).removeClass('selected');
				$(this).find('span').remove();
			}
			if (parseInt(order_table_id) == parseInt($(this).find('input').val())) {
				$(this).addClass('selected');
				$(this).prepend('<span class="icon"></span>');
			}
		});
		openFancybox('#pos_table_dialog', 'wide');
	}
};

function changeTable(table_id) {
	var data = {'order_id': order_id, 'table_id':table_id};
	$.ajax({
		url: 'index.php?route=module/pos/saveOrderTableId&token=' + token,
		type: 'post',
		data: data,
		dataType: 'json',
		beforeSend: function() {
			openWaitDialog();
		},
		complete: function() {
			closeWaitDialog();
		},
		cacheCallback: function(json) {
			// do not save the order table info here
		},
		cachePreDone: function(cacheKey, callback) {
			saveOrderInfo({'order_id':order_id, 'order_table_id':table_id});
			callback({'success':text_order_success});
		},
		success: function(json) {
			if (json['success']) {
				removeMessage();
				showMessage('success', json['success']);
			}
			for (var i in tables) {
				if (parseInt(tables[i]['table_id']) == parseInt(table_id)) {
					$('#button_table').text(tables[i]['name']);
					break;
				}
			}
			order_table_id = table_id;
			closeFancybox();
		}
	});
};

function changeOrderComment() {
	$('textarea[name=order_comment]').val(comment);
	openFancybox('#order_comment_dialog', 'narrow');
};

function changebackOrderCustomer() {
	$('textarea[name=order_comment]').val(comment);
	openFancybox('#backorder_customer_dialog', 'wide');
} 


function saveOrderComment() {
	var cmt = $('textarea[name=order_comment]').val();
	$("#order_comment").html(cmt);
	var data = {'order_id': order_id, 'comment':$('textarea[name=order_comment]').val()};
	$.ajax({
		url: 'index.php?route=module/pos/saveOrderComment&token=' + token,
		type: 'post',
		data: data,
		dataType: 'json',
		beforeSend: function() {
			removeMessage();
			showMessage('notification', '');
		},
		cacheCallback: function(json) {
			// do not save the saving result
		},
		cachePreDone: function(cacheKey, callback) {
			// save the comment to the order
			saveOrderInfo(data);
			callback({'success':text_order_success});
		},
		success: function(json) {
			if (json['success']) {
				removeMessage();
				showMessage('success', json['success']);
			}
			comment = $('textarea[name=order_comment]').val();
			closeFancybox();
		}
	});
};

var cashList = [];

$('.clear_input').on('click', function() {
	$('.enter_amount input').val(0);
	$('.enter_amount span').remove();
	// in the payment dialog
	$('#cash_display_tr').remove();
	cashList = [];
});

function clearCashInOutPage() {
	cashList = [];
	$('input[name=cash_in_out_amount]').val(0);
	$('.enter_amount span').remove();
	$('#cash_in_out_dialog input[type=checkbox]').prop('checked', false);
	$('textarea[name=cash_in_out_comment]').val('');
};

function cashIn() {
	clearCashInOutPage();
	$('#cash_in_out_dialog h3').text(text_cash_in);
	$('#button_cash_in').show();
	$('#button_cash_out').hide();
	openFancybox('#cash_in_out_dialog', 'wide', 0, modeOrder);
};

function cashOut() {
	clearCashInOutPage();
	$('#cash_in_out_dialog h3').text(text_cash_out);
	$('#button_cash_in').hide();
	$('#button_cash_out').show();
    $("#stock_manager_panel").hide();
	$("input[name=filter_product_for_edit]").hide();
	$("input[name=filter_product_update_stock]").hide();
	$("input[name=filter_product_sales_report]").hide();
	$("input[name=filter_product]").show();

	openFancybox('#cash_in_out_dialog', 'wide', 0, modeOrder);

};

function getCash(value, display) {
	var curValue = parseFloat($('input[name=cash_in_out_amount]').val());
	if (isNaN(curValue)) {
		curValue = 0;
	}
	
	var count = 1;
	if ($('input[name="ax10"]').is(':checked')) {
		count = 10;
	} else if ($('input[name="ax5"]').is(':checked')) {
		count = 5;
	}
	
	$('input[name=cash_in_out_amount]').val(toFixed(curValue + count * parseFloat(value), 2));
	
	// add the current selected cash to list and sort it by value
	var index = -1;
	for (var i = 0; i < cashList.length; i++) {
		if(cashList[i]['value'] == parseFloat(value)) {
			index = i;
			break;
		}
	}
	if (index >= 0) {
		cashList[index]['count'] += count;
	} else {
		cashList.push({'value':parseFloat(value), 'count':count, 'display':display});
		cashList.sort(function(a,b) {return b.value - a.value;});
	}
	// refresh the cash list
	$('#cash_in_out_dialog .enter_amount span').remove();
	var spanHtml = '';
	for (var i = 0; i < cashList.length; i++) {
		spanHtml += '<span class="notes">' + cashList[i]['display'] + ' x ' + cashList[i]['count'] + '</span>';
	}
	$('#cash_in_out_dialog .enter_amount').append(spanHtml);
};

function cashInOut(paymentAction) {
	var amount = parseFloat($('input[name=cash_in_out_amount]').val());
	if (amount == 0) return;
	
	if (paymentAction == 'cash_out') {
		amount = 0 - amount;
	}
	
	var note = $('textarea[name=cash_in_out_comment]').val();
	var url = 'index.php?route=module/pos/addOrderPayment&token=' + token;
	var data = {'order_id':0, 'user_id':user_id, 'payment_type':paymentAction, 'payment_note':note ? note : '', 'tendered_amount':amount};
	$.ajax({
		url: url,
		type: 'post',
		data: data,
		dataType: 'json',
		beforeSend: function() {
			removeMessage();
			showMessage('notification', '');
		},
		cacheCallback: function(json) {
			// do not save the order payment
		},
		cachePreDone: function(cacheKey, callback) {
			backendSaveOrderPayment(data, callback);
		},
		success: function(json) {
			if (json['success']) {
				removeMessage();
				showMessage('success', json['success']);
			}
			closeFancybox();
			// return back to sale
			modeOrder();
		}
	});
};

function emailReceipt(selected_id, selected_email) {
	if (serverReachable) {
		if (text_work_mode == '0') {
			if (selected_id) {
				$('input[name=post_order_id]').val(selected_id);
			} else {
				$('input[name=post_order_id]').val(order_id);
			}
		} else if (text_work_mode == '1') {
			if (selected_id) {
				$('input[name=post_pos_return_id]').val(selected_id);
			} else {
				$('input[name=post_pos_return_id]').val(pos_return_id);
			}
		}
		
		if (selected_email) {
			$('input[name=receipt_email]').val(selected_email);
		} else {
			$('input[name=receipt_email]').val(email);
		}
		$("#emaildialogue").toggle();
		resizeFancybox();
		//openFancybox('#email_receipt_dialog', 'narrow');
	} else {
		// cannot send email when offline
		openAlert(text_cannot_send_email_when_offline);
	}
};

function sendReceiptEmail() {
	// the function is only called when online
	var post_order_id = $('input[name=post_order_id]').val();
	var post_pos_return_id = $('input[name=post_pos_return_id]').val();
	var post_email = $('input[name=receipt_email]').val();
	
	var url = 'index.php?route=module/pos/email_receipt&token=' + token;
	var data = {'email':post_email};
	if (text_work_mode == '0') {
		data['order_id'] = post_order_id;
		data['change'] = '1';
	} else if (text_work_mode == '1') {
		data['pos_return_id'] = post_pos_return_id;
	}
	
	$.ajax({
		url: url,
		type: 'post',
		data: data,
		dataType: 'json',
		beforeSend: function() {
			openWaitDialog();
		},
		complete: function() {
			closeWaitDialog();
		},
		success: function(json) {
			closeWaitDialog();
			if (json['error']) {
				openAlert(json['error']);
			} else {
				openAlert(json['success']);
				//closeFancybox();
				$("#button_email_receipt").trigger('click');
			}
		}
	});
};

$(document).on('click', 'input[name=display_locked_orders]', function() {
	if ($(this).is(':checked')) {
		display_locked_orders = 1;
	} else {
		display_locked_orders = 0;
	}
	localStorage.setItem('display_locked_orders', '' + display_locked_orders);
	
	getOrderList();
});

var eventSource;
var sortFrom;

var pressed = false; 
var chars = []; 
var resaleImageCount = 0;      // Declaring and defining global increment variable.
function imageIsLoaded(e) {
	$('#previewimg' + resaleImageCount).attr('src', e.target.result);
}

function uploadResaleImage(){
	var name = $(".fileResale").val();
	if (!name) {
		openAlert("First Image Must Be Selected");
		//e.preventDefault();
	}else{
		//e.preventDefault();    
		var url = $("#frmResale").attr('action');
		//alert(url);
		var formData = new FormData($("#frmResale").get(0));

		$.ajax({
			    url: url,
			    type: 'POST',
			    data: formData,
			    dataType: 'json',
			    success: function (data) {
			        if (data.result == "success"){
			        	$("#isUploadedResale").val(data.output[0]);
			        	$("#uploadResponse").css('color','#00FF00');
			        	$("#uploadResponse").html("Image Resale ID uploaded Successfully");
			        }else{
			        	$("#uploadResponse").css('color','#FF0000');
			        	$("#uploadResponse").html(data.output[0]);
			        }
			    },
			    cache: false,
			    contentType: false,
			    processData: false
		});
	}
}

$(document).ready(function() {
	$(".onlyone").css('display','none');
	$(document).on('change','#fileResale',function(){
		if (this.files && this.files[0]) {
			resaleImageCount += 1; // Incrementing global variable by 1.
			var z = resaleImageCount - 1;
			var x = $(this).parent().find('#previewimg' + z).remove();
			$(this).before("<div id='abcd" + resaleImageCount + "' class='abcd'><img id='previewimg" + resaleImageCount + "' src=''/></div>");
			var reader = new FileReader();
			reader.onload = imageIsLoaded;
			reader.readAsDataURL(this.files[0]);
			$(this).hide();
			$("#abcd" + resaleImageCount).append($("<img/>", {
			id: 'img',
			src: 'https://image.flaticon.com/icons/png/128/59/59836.png',
			alt: 'delete'
			}).click(function() {
			$(this).parent().parent().remove();
			}));
		}
	});

	$('#add_more').click(function() {
		$(this).before($("<div/>", {
			id: 'filediv'
		}).fadeIn('slow').append($("<input/>", {
			name: 'fileResale[]',
			type: 'file',
			id: 'fileResale',
			class: 'fileResale'
		}), $("<br/><br/>")));
	});

	$("#frmResale1").submit(function(e) {
		e.preventDefault();    
		var url = $(this).attr('action');
		//alert(url);
		var formData = new FormData(this);

		$.ajax({
			    url: url,
			    type: 'POST',
			    data: formData,
			    dataType: 'json',
			    success: function (data) {
			        if (data.result == "success"){
			        	$("#isUploadedResale").val(data.output[0]);
			        	openAlert("Image Resale ID uploaded Successfully");
			        }else{
			        	openAlert(data.output[0]);
			        }
			    },
			    cache: false,
			    contentType: false,
			    processData: false
		});
	});

	$('#uploadResale1').click(function(e) {
		var name = $(".fileResale").val();
		if (!name) {
			openAlert("First Image Must Be Selected");
			e.preventDefault();
		}
	});
	//$(".input-box").css('width','73%');
	//$('.date').datepicker({dateFormat: 'yy-mm-dd'});
	/*$('.datetime').datetimepicker({
		dateFormat: 'yy-mm-dd',
		timeFormat: 'h:m'
	});
	$('.time').datetimepicker({	pickDate: false	});
*/	
	if (full_screen_mode == "0") {
		$('#header').show();
		$('.breadcrumb').show();
		$('#footer').show();
		$('#column-left').show();
		$('#button_full_screen').attr('src', 'view/image/pos/header_0.png');
		$('#button_full_screen').attr('alt', button_full_screen);
		$('#button_full_screen').attr('title', button_full_screen);
	} else {
		$('#header').hide();
		$('.breadcrumb').hide();
		$('#footer').hide();
		$('#column-left').hide();
		$('#button_full_screen').attr('src', 'view/image/pos/header_1.png');
		$('#button_full_screen').attr('alt', button_normal_screen);
		$('#button_full_screen').attr('title', button_normal_screen);
	}
	
	showMessage('success', text_order_ready);
	CheckSizeZoom();
	
	// add for SKU begin
    // trigger an event on any keypress on this webpage
    $(window).on('keypress', function(e) {
		// can be numbers for barcode, or can be anything for credit card
        chars.push(String.fromCharCode(e.which));
		
        // debug to help you understand how scanner works
		// console.log(e);
        // console.log(e.which + ":" + chars.join("|"));
        if (pressed == false) {
            // we set a timeout function that expires after 1 sec, once it does it clears out a list 
            // of characters 
            setTimeout(function(){
                // check we have a long length e.g. it is a barcode
                if (chars.length >= 8) {
                    // join the chars array to make a string of the barcode scanned
                    var readin = chars.join("");
					readin = readin.replace(/[\n\r]+/g, '');
                    // debug barcode to console (e.g. for use in Firebug)
                    // display the main page and switch to add product page
					// $('input[name=sku]').val(readin);
					if (typeof customer_card_prefix != 'undefined' && customer_card_prefix && readin.indexOf(customer_card_prefix) === 0) {
						setCustomer(readin);
					// add for customer loyalty data end
					// add for caller id begin
					} else if (readin.toLowerCase().indexOf('callerid') === 0) {
						getCallinCustomer(readin.substring(8));
					} else if (readin.indexOf("\x00\x00\x00\x00") === 0) {
						// ignore
					// add for caller id end
					} else {
						var scan_type = config['config_scan_type'];
						if (!scan_type) {
							scan_type = 'upc';
						}
						// barcode
						if (!($('input[name=' + scan_type + ']').is(':focus'))) {
							$('#tab_search').trigger('click');
							$('input[name=' + scan_type + ']').val(readin);
						}
						if (scan_type == 'upc') {
							handleUPCEntry();
						} else if (scan_type == 'sku') {
							var isv1 = $("#quick_sale_dialog").css('display');
							var isv2 = $("#quick_sale_dialog_fs").val();
							if (isv1 == "none" || isv2 == ""){
								handleSKUEntry();
							}else{
								handleNonCatalogEntry();
							}
							
						} else if (scan_type == 'mpn') {
							handleMPNEntry();
						}
					}
                }
                chars = [];
                pressed = false;
            },500);
        }
        // set press to true so we do not reenter the timeout function above
        pressed = true;
    });
	// add for SKU end
	setOrderNotification(new_order_num);
	
	if (localStorage.getItem('display_locked_orders')) {
		display_locked_orders = parseInt(localStorage.getItem('display_locked_orders'));
		$('input[name=display_locked_orders]').val(display_locked_orders);
		if (display_locked_orders) {
			$('input[name=display_locked_orders]').prop('checked', true);
		}
	}
	
	if (localStorage.getItem('timezone_offset')) {
		timezone_offset = parseInt(localStorage.getItem('timezone_offset'));
	}
	
	// get the search scopes from the local storage
	searchScopes = localStorage.getItem('pos_searchScopes');
	if (searchScopes) {
		searchScopes = JSON.parse(searchScopes);
	} else {
		searchScopes = ['name','model','sku','upc','mpn','manufacturer','isbn','location'];
	}
	
	// get the shortcuts from the local storage
	loadShortcuts();
	// make the list sortable
	$('.shortcuts_list').sortable({
		start: function(event, ui) {
			sortFrom = $(ui.item).index();
		},
		update: function(event, ui) {
			var sortTo = $(ui.item).index();
			// update local variable and also local storage
			if (sortFrom < sortTo) {
				// moved down, sortForm = sortForm + 1 until sortTo, then sortTo = sortFrom
				var shortcutFrom = shortcuts[sortFrom];
				for (var i = sortFrom; i < sortTo; i++) {
					shortcuts[i] = shortcuts[i+1];
					localStorage.setItem('pos_shortcut'+i, JSON.stringify(shortcuts[i+1]));
				}
				shortcuts[sortTo] = shortcutFrom;
				localStorage.setItem('pos_shortcut'+sortTo, JSON.stringify(shortcutFrom));
				if (sortFrom < 5) {
					// some of the values need to change at left panel of shortcuts
					for (var i = sortFrom; i < 5; i++) {
						var name = $('<div/>').html(shortcuts[i]['name']).text();
						var code = (shortcuts[i]['code'] ? shortcuts[i]['code'] : (name.length > 2 ? name.substring(0,2) : name));
						$('#shortcut'+i).find('.bg span').removeClass().addClass('letter one');
						$('#shortcut'+i).find('.bg span').text(code);
						$('#shortcut'+i).find('.txt').text(name);
						if (shortcuts[i]['type'] == 'P') {
							$('#shortcut'+i).find('.bg').removeClass().addClass('bg one_letter product');
						} else if (shortcuts[i]['type'] == 'C') {
							$('#shortcut'+i).find('.bg').removeClass().addClass('bg one_letter category');
						}
					}
				}
			} else if (sortFrom > sortTo) {
				// moved up, sortForm = sortForm - 1 until sortTo, then sortTo = sortFrom
				var shortcutFrom = shortcuts[sortFrom];
				for (var i = sortFrom; i > sortTo; i--) {
					shortcuts[i] = shortcuts[i-1];
					localStorage.setItem('pos_shortcut'+i, JSON.stringify(shortcuts[i-1]));
				}
				shortcuts[sortTo] = shortcutFrom;
				localStorage.setItem('pos_shortcut'+sortTo, JSON.stringify(shortcutFrom));
				if (sortTo < 5) {
					// some of the values need to change at left panel of shortcuts
					for (var i = sortTo; i < 5; i++) {
						var name = $('<div/>').html(shortcuts[i]['name']).text();
						var code = (shortcuts[i]['code'] ? shortcuts[i]['code'] : (name.length > 2 ? name.substring(0,2) : name));
						$('#shortcut'+i).find('.bg span').removeClass().addClass('letter one');
						$('#shortcut'+i).find('.bg span').text(code);
						$('#shortcut'+i).find('.txt').text(name);
						if (shortcuts[i]['type'] == 'P') {
							$('#shortcut'+i).find('.bg').removeClass().addClass('bg one_letter product');
						} else if (shortcuts[i]['type'] == 'C') {
							$('#shortcut'+i).find('.bg').removeClass().addClass('bg one_letter category');
						}
					}
				}
			}
			// change the list row style
			var trClass = 'even';
			for (var i = 0; i < shortcuts.length; i++) {
				trClass = (trClass == 'even') ? 'odd' : 'even';
				$('#shortcuts_dialog ul li:eq(' + i + ')').removeClass('even odd').addClass(trClass);
			}
		}
	});
	
	// new UI functions
	// $('.nav_tab_wrap').tabs();
	
	//--------- Custom Select -------
	$('#work_mode_dropdown').selectmenu({
		change: function( event, ui ) {
			$(".onlyone").css('display','none');
			var mode = $(this).val();
			if (mode == 'sale') {
				modeOrder();
				    $("#stock_manager_panel").hide();
	$("input[name=filter_product_for_edit]").hide();
	$("input[name=filter_product_update_stock]").hide();
	$("input[name=filter_product_sales_report]").hide();
	$("input[name=filter_product]").show();
	$("#scancode_product_for_edit").hide();
	$("#scancode_product_update_stock").hide();
	$("#scancode_product_sales_report").hide();
	$("#scancode").show();
	$("#updateStockContainer").hide();
	$("#main-container").show();
	$(".right-container").show();
	$(".cart-container").show();
	$(".control-area").show();
	$("#tab_customer_select").addClass("active");
	} else if (mode == 'return_order') {
				$(".onlyone").css('display','none');
				//modeReturn(1);
				chooseOption();
				    $("#stock_manager_panel").hide();
	$("input[name=filter_product_for_edit]").hide();
	$("input[name=filter_product_update_stock]").hide();
	$("input[name=filter_product_sales_report]").hide();
	$("input[name=filter_product]").show();
	$("#scancode_product_for_edit").hide();
	$("#scancode_product_update_stock").hide();
	$("#scancode_product_sales_report").hide();
	$("#scancode").show();
	$(".right-container").show();
	$(".cart-container").show();
	$(".tab-pane").removeClass( "active" );
	$( "#tab_product_information_tab" ).parent().removeClass( "active" );
	$( "#tab_returns" ).addClass( "active" );
	$( "#tab_returns_tab" ).parent().addClass( "active" ); 
	$("#modifyReturn").trigger("click");
	loadReturns();
			} else if (mode == 'return_without_order') {
				modeReturn();
				    $("#stock_manager_panel").hide();
	$("input[name=filter_product_for_edit]").hide();
	$("input[name=filter_product_update_stock]").hide();
	$("input[name=filter_product_sales_report]").hide();
	$("input[name=filter_product]").show();
	$("#scancode_product_for_edit").hide();
	$("#scancode_product_update_stock").hide();
	$("#scancode_product_sales_report").hide();
	$("#scancode").show();
	$(".right-container").show();
	$(".cart-container").show();
			} else if (mode == 'quote') {
				modeQuote();
				    $("#stock_manager_panel").hide();
	$("input[name=filter_product_for_edit]").hide();
	$("input[name=filter_product_update_stock]").hide();
	$("input[name=filter_product_sales_report]").hide();
	$("input[name=filter_product]").show();
	$(".right-container").show();
	$(".cart-container").show();
			} else if (mode == 'cash_in') {
				cashIn();
				    $("#stock_manager_panel").hide();
	$("input[name=filter_product_for_edit]").hide();
	$("input[name=filter_product_update_stock]").hide();
	$("input[name=filter_product_sales_report]").hide();
	$("input[name=filter_product]").show();
	$(".right-container").show();
	$(".cart-container").show();
			} else if (mode == 'cash_out') {
				cashOut();
			}else if(mode == 'stock_manager'){
				$(".control-area").hide();
				stockManagerShow();
			} else if(mode == 'go_to_admin'){
				var admin_url = baseurl.replace("/pos/", "/admin/");
				window.location.href = admin_url;
			}
		}
	});
	
	//--------- Toggle -----------
	if ($('.menu-toggle').length) {
		$('.menu-toggle').click(function () {
			$(this).toggleClass('clicked');
			return false;
		});	
	}
	
	if ($('.hide-show-nav').length) {
		var size=[];		
		$(".hide-show-nav").click(function(){
			//alert("YES");
			$('.left-container').width() >= 210 ? size=[44] : size=[210];
			$('.left-container').animate({
				width: size[0]
			},0);	
			var lpad=[];	
			currentPadding = parseInt($('.main-container').css('padding-left'));
			//alert(currentPadding);			
			if(currentPadding >= 230) {
				lpad=[44];
				$('.left-container h2').css({'display':'none'});
				$('.prdct-container').css({'left':'0'});
			} else {
				lpad=[230];
				$('.left-container h2').css({'display':'block'});
				$('.prdct-container').css({'left':'-25px'});
			}
			$('.main-container').animate({
				paddingLeft: lpad[0]
			},0);
			return false;
		});
	}
	
	//--------- Right Container Height ---------	
	if ($('.right-container').length) {
		jQuery(window).resize(rightHeight);	
		if (!window.addEventListener) {
			window.attachEvent("orientationchange", rightHeight, false);
		}
		else {
			window.addEventListener("orientationchange", rightHeight, false);
		}
		rightHeight();
	}
	//--------- Product Container Height ---------	
	if ($('.right-container').length) {
		jQuery(window).resize(prodHeight);	
		if (!window.addEventListener) {
			window.attachEvent("orientationchange", prodHeight, false);
		}
		else {
			window.addEventListener("orientationchange", prodHeight, false);
		}
		prodHeight();
	}
	
	//--------- Cart Container Height ---------	
	if ($('.right-container').length) {
		jQuery(window).resize(cartHeight);	
		if (!window.addEventListener) {
			window.attachEvent("orientationchange", cartHeight, false);
		}
		else {
			window.addEventListener("orientationchange", cartHeight, false);
		}
		cartHeight();
	}
	$('td.filter a').click(function () {
  		$("td.filter").siblings().slideToggle(400);
  		$('td.filter a').toggleClass('active');
  		return false;
 	});

	$('.existing_customer .filter_tab').click(function () {
  		$(".table_customerlist .first-row").slideToggle(400);
  		$('.existing_customer .filter_tab').toggleClass('active');
  		return false;
 	});
	
	//------- Price / Discount Radio buttons ------
	
	$(".price_disc_radio").change(function () { //use change event
        if (this.value == "price") { //check value if it is domicilio
            $('.mask').stop(true,true).show(); //than show
        } else {
            $('.mask').stop(true,true).hide(); //else hide
        }
    });
	
	//------- Keypad and Currency panel toggle --------
	

	$( ".payment_type" ).change(function() {
		if($(this).val() == "Cash") {
			$('.payment_cash').css({"display":"block"});
			$('.payment_other').css({"display":"none"});
		} else {
			$('.payment_cash').css({"display":"none"});
			$('.payment_other').css({"display":"block"})
		}
	});
	
	initServerSendEvent();
	// initialize the current order in case it's not in cache
	moveCurrentOrder();
	moveCurrentReturn();
	
	if (typeof empty_order_info != 'undefined') {
		localStorage.setItem(CACHE_EMPTY_ORDER, JSON.stringify(empty_order_info));
	}
	// save the default customer in case it's required for resetting the customer
	if (typeof default_customer != 'undefined') {
		localStorage.setItem(CACHE_DEFAULT_CUSTOMER, JSON.stringify(default_customer));
	}

	if (config['enable_offline_mode'] && parseInt(config['enable_offline_mode']) > 0) {
		// only when offline mode is enabled
		pingServer(true);
		setInterval(pingServer, 10000);
		setInterval(backendSyncTax, 360000);
	}
	
	updateClock();
	setInterval(updateClock, 1000);
});

// new UI functions
function rightHeight(){
	$('.main-container').css('height', $(window).innerHeight()-$(".header").height());
};
function prodHeight(){
	$('.product-box-outer').css('height', $(window).innerHeight()-($(".header").height()+$(".prdct-header").height()+43));
};
function cartHeight(){
	$('.cart-outer-scroller').css('height', $(window).innerHeight()-($(".header").height()+$(".cart-title-bg").height()+$(".cart-footer").height()+63));
};

function stockManagerShow(){
	$("#stock_manager_panel").show();
	$('.hide-show-nav').trigger('click');
	$("input[name=filter_product_for_edit]").show();
	$("input[name=filter_product_update_stock]").hide();
	$("input[name=filter_product_sales_report]").hide();
	$("input[name=filter_product]").hide();
  	$("#updateStockContainer").hide();
  	$(".cart-container").hide();
  	//$(".control-area").hide();
  	$(".right-container").show();
	$(".search_products").val("");
	$(".product-box-outer").html("");
	$(".tab-pane").removeClass( "active" );
	$( "#tab_returns_tab" ).parent().removeClass( "active" );
	$( "#update_stock_tab_id" ).parent().removeClass( "active" );
	$( "#tab_product_information" ).addClass( "active" );
	$( "#tab_product_information_tab" ).parent().addClass( "active" ); 
	$("#scancode_product_for_edit").show();
	$("#scancode_product_update_stock").hide();
	$("#scancode_product_sales_report").hide();
	$("#scancode").hide();
	//$(".onlyone").css('display','block');
	$(".input-box").css('width','41%');
}

function pingServer(initial) {
	$.ajax({
		url: store_admin_url + 'controller/pos/ping.php',
		cache: false,
		localCache: false,
		dataType: 'json',
		timeout: 6000,
		success: function (json) {
			if (initial) {
				// when pos was reloaded, sync data if possible
				serverReachable = true;
				syncData();
			} else if (json['success'] && json['success'] == 'ok') {
				if (!serverReachable) {
					onServerOnline();
				}
			} else {
				onServerOffline();
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			if (initial) {
				// from the document ready call
				serverReachable = false;
				selectOrder(0,0);
			} else {
				onServerOffline();
			}
		}
	});
};

function onServerOnline() {
	console.log('from offline to online');
	serverReachable = true;
	
	// just switched from offline to online, sync data
	var recovering = false;
	var r_order_id = 0, r_pos_return_id = 0;
	if ($('#product tr').length > 0) {
		// the order was stored to the local and might have been changed, restore it back to server
		var currOrderString = localStorage.getItem(CACHE_CURRENT_ORDER);
		if (currOrderString) {
			currOrder = JSON.parse(currOrderString);
			if (parseInt(currOrder['order_id']) > 0) {
				localStorage.setItem(CACHE_ORDER + currOrder['order_id'], currOrderString);
				// simply remove the CACHE_CURRENT_ORDER
				localStorage.removeItem(CACHE_CURRENT_ORDER);
				r_order_id = currOrder['order_id'];
				recovering = true;
			}
		}
		// the return was stored to the local and might have been changed, restore it back to server
		var currReturnString = localStorage.getItem(CACHE_CURRENT_RETURN);
		if (currReturnString) {
			currReturn = JSON.parse(currReturnString);
			if (parseInt(currReturn['pos_return_id']) > 0) {
				localStorage.setItem(CACHE_RETURN + currReturn['pos_return_id'], currReturnString);
				// simply remove the CACHE_CURRENT_RETURN
				localStorage.removeItem(CACHE_CURRENT_RETURN);
				r_pos_return_id = currReturn['pos_return_id'];
				recovering = true;
			}
		}
	}
	
	if (recovering) {
		if (text_work_mode == '1') {
			// load the synced return
			syncData(function(r_pos_return_id) { selectReturn(0, r_pos_return_id); }, r_pos_return_id, text_wait_saving_data_to_server);
		} else {
			// load the synced order / quote
			syncData(function(r_order_id) { selectOrder(0, r_order_id); }, r_order_id, text_wait_saving_data_to_server);
		}
	} else {
		// it's a good time to sync the local data
		syncData();
		if ($('#product tr').length == 0) {
			// no product for the current order, simply switch to online order
			serverReachable = true;
			if (text_work_mode == '0') {
				modeOrder();
			} else if (text_work_mode == '1') {
				modeReturn();
			} else if (text_work_mode == '2') {
				modeQuote();
			}
		}
	}
};

function onServerOffline() {
	if (serverReachable) {
		console.log('from online to offline');
		// just switched from online to offline, save the current data into local storage
		serverReachable = false;
		openWaitDialog(text_wait_saving_data_to_local);
		if (text_work_mode == '0' || text_work_mode == '2') {
			backendFlushCurrentOrder();
			if ($('#product tr').length == 0) {
				// no product for the current order, simply switch to local order
				selectOrder(0, 0);
			}
		} else if (text_work_mode == '1') {
			backendFlushCurrentReturn();
			if ($('#product tr').length == 0) {
				// no product for the current order, simply switch to local order
				selectReturn(0, 0);
			}
		}
		closeWaitDialog();
	}
};

// add for auto online order print begin
var print_order_ids = [];
function printOnlineOrder() {
	if (print_order_ids.length > 1) {
		var print_order_id = print_order_ids.shift();
		if (config['config_print_type'] == 'invoice') {
			var url = 'index.php?route=module/pos/invoice&token=' + token + '&order_id=' + print_order_id + '&work_mode=0';
			window_print_url(print_invoice_message, url, {'order_id':print_order_id}, printOnlineOrder, null);
		} else  {
			var url = 'index.php?route=module/pos/receipt&token=' + token + '&order_id=' + print_order_id + '&work_mode=0';
			window_print_url(print_receipt_message, url, {'order_id':print_order_id, 'change':'1'}, printOnlineOrder, null);
		}
	} else if (print_order_ids.length == 1) {
		var print_order_id = print_order_ids.shift();
		if (config['config_print_type'] == 'invoice') {
			var url = 'index.php?route=module/pos/invoice&token=' + token + '&order_id=' + print_order_id + '&work_mode=0';
			window_print_url(print_invoice_message, url, {'order_id':print_order_id}, afterPrintReceipt, null);
		} else  {
			var url = 'index.php?route=module/pos/receipt&token=' + token + '&order_id=' + print_order_id + '&work_mode=0';
			window_print_url(print_receipt_message, url, {'order_id':print_order_id, 'change':'1'}, afterPrintReceipt, null);
		}
	}
};
// add for auto online order print end

function initServerSendEvent() {
	eventSource = new EventSource(store_admin_url + 'controller/pos/online_order.php');
	eventSource.addEventListener('message', function(event) {
		var data = event.data;
		var index = data.indexOf(' ');
		var numOfOrder = data.substring(0,index);
		new_order_num = parseInt(new_order_num) + parseInt(numOfOrder);
		if (config['enable_online_order_print'] && parseInt(config['enable_online_order_print']) > 0 && parseInt(numOfOrder) > 0) {
			// if auto print online orders, print the new orders one by one
			print_order_ids = data.substring(index+1).split(',');
			printOnlineOrder();
		} else {
			// otherwise, just display the notification
			setOrderNotification(numOfOrder);
		}
	}, false);
};

function setOrderNotification(number) {
	if (parseInt(number) > 0) {
		$('#button_order_list').append('<span class="product-count">' + number + '</span>');
	} else {
		$('#button_order_list .product-count').remove();
	}
};

function showMessage(className, text) {
	if (!$('.message').hasClass('incoming-call')) {
		// if incoming call is not cleared, do not change the status
		$('.message').removeClass('success error notification').addClass(className);
		if (className == 'incoming-call') {
			$('.message').addClass('notification');
		}
		
		if (className == 'notification' && text == '') {
			$('.message p').text(text_wait);
		} else {
			$('.message p').html(text);
		}
	}
};

function removeMessage() {
	$('#order_message').empty();
};

$(document).on('focus', 'input[name=\'filter_customer\']', function(){
	$(this).autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=customer/customer/autocomplete&token=' + token + '&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				cacheCallback: function(json) {
					// dot not save the result for now as the info returned from opencart customer model is not complete
				},
				cachePreDone: function(cacheKey, callback) {
					backendGetCustomers(request.term, callback);
				},
				success: function(json) {
					response($.map(json, function(item) {
						return {
							category: item.customer_group,
							label: item.name,
							value: item.customer_id
						}
					}));
				}
			});
		},
		select: function(event, ui) {
			$('input[name=\'filter_customer\']').val(ui.item.label);
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
});

function handleOptionReturn(product_name, product_id, product_option, popup,unit_datas,defaultunit,alldata) {
	$('input[name=\'product\']').val(product_name);
	$('input[name=\'product_id\']').val(product_id);
	// add for serial no begin
	$('input[name=\'product_sn\']').val('');
	$('input[name=\'product_sn_id\']').val('');
	// add for serial no end
	
	if (product_option) {
		product_add_option = product_option;
		
		html = '';
		html += '<div class="optionspop" style="display:none;">';
		for (var i = 0; i < product_option.length; i++) {
			var option = product_option[i];
			
			html += '<li id="option-' + option['product_option_id'] + '" class="optionpop">';
			
			html += '<label><b>' + option['name'] + '</b>';
			if (parseInt(option['required']) > 0) {
				html += ' <em>*</em>';
			}
			html += '</label><div class="inputbox">';

			if (option['type'] == 'select' || option['type'] == 'radio' || option['type'] == 'image') {
				html += '<select id="option[' + option['product_option_id'] + '][product_option_value_id]" name="option[' + option['product_option_id'] + '][product_option_value_id]" class="optionschange" onchange="$(\'input[name=\\\'option[' + option['product_option_id'] + '][value]\\\']\').val($(this).find(\'option:selected\').text());$(\'input[name=\\\'option[' + option['product_option_id'] + '][price_prefix]\\\']\').val($(this).find(\'option:selected\').attr(\'price_prefix\'));$(\'input[name=\\\'option[' + option['product_option_id'] + '][price]\\\']\').val(posParseFloat($(this).find(\'option:selected\').attr(\'price\')));">';
				html += '<option value="">' + text_none + '</option>';
			
				for (j = 0; j < option['option_value'].length; j++) {
					option_value = option['option_value'][j];
					
					html += '<option value="' + option_value['product_option_value_id'] + '"';
					
					
					if(option_value['is_requested_option_value']){
						html += 'selected';
					}
					if (option_value['price']) {
						html += ' price_prefix="' + option_value['price_prefix'] + '"';
						html += ' price="' + option_value['price'] + '"';
						html += '>' + option_value['name'] + ' (' + option_value['price_prefix'] + option_value['price'] + ')';
					} else {
						html += ' price_prefix="+" price="0">' + option_value['name'];
					}
					
					html += '</option>';
					if(option_value['is_requested_option_value']){
						
					}
					
				}
				html += '<input type="hidden" name="option[' + option['product_option_id'] + '][value]" value="" />';
				html += '<input type="hidden" name="option[' + option['product_option_id'] + '][price_prefix]" value="+" />';
				html += '<input type="hidden" name="option[' + option['product_option_id'] + '][price]" value="0" />';
					
				html += '</select>';
				
			} else if (option['type'] == 'checkbox') {
				for (j = 0; j < option['option_value'].length; j++) {
					option_value = option['option_value'][j];
					
					html += '<input type="checkbox" name="option_checkbox"';
					if (option_value['price']) {
						html += ' price_prefix="' + option_value['price_prefix'] + '" price="' + option_value['price'] + '"';
					} else {
						html += ' price_prefix="+" price="0"';
					}
					html += ' value="' + option_value['name'] + '" id="option-value-' + option_value['product_option_value_id'] + '" onchange="if ($(this).is(\':checked\')) {$(\'input[name=\\\'option['+option['product_option_id']+'][value]\\\']\').val($(\'input[name=\\\'option['+option['product_option_id']+'][value]\\\']\').val()+'+option_value['product_option_value_id']+'+\'|\');$(\'input[name=\\\'option['+option['product_option_id']+'][product_option_value_id]['+option_value['product_option_value_id']+'][value]\\\']\').val($(this).val());$(\'input[name=\\\'option['+option['product_option_id']+'][product_option_value_id]['+option_value['product_option_value_id']+'][price_prefix]\\\']\').val($(this).attr(\'price_prefix\'));$(\'input[name=\\\'option['+option['product_option_id']+'][product_option_value_id]['+option_value['product_option_value_id']+'][price]\\\']\').val(posParseFloat($(this).attr(\'price\')));} else {$(\'input[name=\\\'option['+option['product_option_id']+'][value]\\\']\').val($(\'input[name=\\\'option['+option['product_option_id']+'][value]\\\']\').val().replace('+option_value['product_option_value_id']+'+\'|\', \'\'));$(\'input[name=\\\'option['+option['product_option_id']+'][product_option_value_id]['+option_value['product_option_value_id']+'][value]\\\']\').val(\'\');$(\'input[name=\\\'option['+option['product_option_id']+'][product_option_value_id]['+option_value['product_option_value_id']+'][price_prefix]\\\']\').val(\'+\');$(\'input[name=\\\'option['+option['product_option_id']+'][product_option_value_id]['+option_value['product_option_value_id']+'][price]\\\']\').val(\'0\');} "/>';
					html += ' ' + option_value['name'];
					
					if (option_value['price']) {
						html += ' (' + option_value['price_prefix'] + option_value['price'] + ')';
					}
					html += '<br/>';

					html += '<input type="hidden" name="option[' + option['product_option_id'] + '][product_option_value_id]['+option_value['product_option_value_id']+'][price_prefix]" value="+" />';
					html += '<input type="hidden" name="option[' + option['product_option_id'] + '][product_option_value_id]['+option_value['product_option_value_id']+'][price]" value="0" />';
					html += '<input type="hidden" name="option[' + option['product_option_id'] + '][product_option_value_id]['+option_value['product_option_value_id']+'][value]" value="" />';
				}
				
				html += '<input type="hidden" name="option[' + option['product_option_id'] + '][value]" value="" />';
			} else if (option['type'] == 'text') {
				html += '<input type="hidden" name="option[' + option['product_option_id'] + '][product_option_value_id]" value="0" />';
				html += '<input type="text" name="option[' + option['product_option_id'] + '][value]" value="' + option['option_value'] + '" />';
			} else if (option['type'] == 'textarea') {
				html += '<input type="hidden" name="option[' + option['product_option_id'] + '][product_option_value_id]" value="0" />';
				html += '<textarea name="option[' + option['product_option_id'] + '][value]" cols="40" rows="5">' + option['option_value'] + '</textarea>';
			} else if (option['type'] == 'file') {
				html += '<a id="button-option-' + option['product_option_id'] + '" class="pos_button">' + button_upload + '</a>';
				html += '<input type="hidden" name="option[' + option['product_option_id'] + '][product_option_value_id]" value="0" />';
				html += '<input type="hidden" name="option[' + option['product_option_id'] + '][value]" value="' + option['option_value'] + '" />';
			} else if (option['type'] == 'date') {
				html += '<input type="hidden" name="option[' + option['product_option_id'] + '][product_option_value_id]" value="0" />';
				html += '<input type="text" name="option[' + option['product_option_id'] + '][value]" value="' + option['option_value'] + '" class="date" />';
			} else if (option['type'] == 'datetime') {
				html += '<input type="hidden" name="option[' + option['product_option_id'] + '][product_option_value_id]" value="0" />';
				html += '<input type="text" name="option[' + option['product_option_id'] + '][value]" value="' + option['option_value'] + '" class="datetime" />';
			} else if (option['type'] == 'time') {
				html += '<input type="hidden" name="option[' + option['product_option_id'] + '][product_option_value_id]" value="0" />';
				html += '<input type="text" name="option[' + option['product_option_id'] + '][value]" value="' + option['option_value'] + '" class="time" />';
			}
			html += '<input type="hidden" name="option[' + option['product_option_id'] + '][product_option_id]" value="' + option['product_option_id'] + '" />';
			html += '<input type="hidden" name="option[' + option['product_option_id'] + '][name]" value="' + option['name'] + '" />';
			html += '<input type="hidden" name="option[' + option['product_option_id'] + '][type]" value="' + option['type'] + '" />';
			html += '</div></li>';
		}
		
		
		html += '</div>';
		// Product Details 
		if(alldata !='' && alldata['data']['product_info']){  
			html += '<div class="prod_cont" style="margin-bottom:15px">';
			html += '<h5 id="product_details_name"><a onclick="showProductDetailsCustom(' + alldata['data']['product_info']['product_id'] +')">&nbsp;' + alldata['data']['product_info']['name'] + '</a></h5>';

			html += '<table width="50%" border="0" cellspacing="0" cellpadding="0" style="float:left"><tbody>';
			html += '<tr class="even"><td>Model</td><td class="txt_c">:</td><td id="product_details_model">' + alldata['data']['product_info']['model'] + '</td></tr>';
			html += '<tr class="odd"><td>Quantity</td><td class="txt_c">:</td><td id="product_details_quantity">' + alldata['data']['product_info']['quantity'] + '</td></tr>';
			html += '<tr class="even"><td>SKU</td><td class="txt_c">:</td><td id="product_details_sku">' + alldata['data']['product_info']['sku'] + '</td></tr>';
			html += '<tr class="odd"><td>UPC</td><td class="txt_c">:</td><td id="product_details_upc">' + alldata['data']['product_info']['upc'] + '</td></tr>';
			html += '<tr class="even"><td>MPN</td><td class="txt_c">:</td><td id="product_details_mpn">' + alldata['data']['product_info']['mpn'] + '</td></tr>';
			html += '<tr class="odd"><td>Location</td><td class="txt_c">:</td><td id="product_details_location">' + alldata['data']['product_info']['location'] + '</td></tr>';
			var labour_cost = alldata['data']['product_info']['labour_cost'];
			var unique_option_price = alldata['data']['product_info']['unique_option_price'];
			html += '<tr class="odd"><td>Formula</td><td class="txt_c">:</td><td id="product_details_labour_cost">' + parseFloat(labour_cost).toFixed(2) +" + "+ parseFloat(unique_option_price).toFixed(6) + '</td></tr> ';
			html += '</tbody></table>'; 

			html += '<table width="45%" border="0" cellspacing="0" cellpadding="0" style="float:right"><tbody>';
			var current_customer_group_id = $('input[name=discount_customer_group_id]:checked').val(); 
			var customer_group_name = "";
			switch(current_customer_group_id)
			{
				case '1' : customer_group_name = "Default"; break;
				case '2' : customer_group_name = "Wholesale"; break;
				case '3' : customer_group_name = "Special Discount Group"; break;
				case '4' : customer_group_name = "Extra Special Discount Group"; break;
				case '5' : customer_group_name = "In Store Wholesale"; break;
			}
			html += '<tr class="odd"><td colspan="3" style="text-decoration:underline;font-weight: 600;">' + customer_group_name + '</td></tr>';

			html += '<tr class="odd"><td>Non-Wholesale</td><td class="txt_c">:</td><td class="red-price">' + alldata['data']['orignial_price'] + '</td></tr>';

			var customer_group_prices = alldata['data']['customer_group_prices'];
			for (j = 0; j < customer_group_prices.length; j++) {
				customer_group_price = customer_group_prices[j];
				var customer_group_price_qty = "";
				var customer_group_price_class = "";
				switch(customer_group_price['quantity'])
				{
					case '1' : customer_group_price_qty = "1 to 9"; customer_group_price_class = "even"; break;
					case '10' : customer_group_price_qty = "10 to 49"; customer_group_price_class = "odd"; break;
					case '50' : customer_group_price_qty = "50+ "; customer_group_price_class = "even"; break;
				}
				html += '<tr class="' + customer_group_price_class + '"><td>' + customer_group_price_qty + '</td><td class="txt_c">:</td><td class="red-price">' + customer_group_price['price'] + '</td></tr>';
			}
			
			html += '</tbody></table>'; 

			html += '</div>';

		}
		html += '<li id="option-quantity" style="width:60%">';
		html += '<label style="width:auto">Qty';
		html += '</label><div class="inputbox">';
		html += '<input type="text" name="quantity" value="" id="qunatity" />';
		html += '</div></li>';
		
		if(unit_datas.length > 0){
		html += '<li id="option-unit_value" style="width:60%">';
		if(unit_datas !='' && defaultunit != ''){
            html += '  <input type="hidden" id="unit_conversion_values" name="unit_conversion_values" value="'+defaultunit['unit_conversion_product_id']+'" />';
        }	
			html += '<label style="width:auto">Unit';
			html += '</label><div class="inputbox" id="unit">';
		 	html += '<select id="get-unit-data" name="unit[0]" class="form-control">';					
                        for (j = 0; j < unit_datas.length; j++) {
                                unit_value = unit_datas[j];                               
                                html += '<option data-value ="' + unit_value['unit_conversion_product_id'] + '" value="' + unit_value['convert_price'] + '">' + unit_value['name'];

                            html += '</option>';
                        }

                        html += '</select>';
		
			html += '</div></li>';
		}
		
		if(alldata !='' && alldata['data']['requested_product_data']){
			html += '<input type="hidden" id="groupbyvalue" name="groupbyvalue" value="' + alldata['data']['requested_product_data']['groupbyvalue'] + '" />';
			html += '<input type="hidden" id="groupbyname" name="groupbyname" value="' + alldata['data']['requested_product_data']['groupbyname'] + '" />';
			html += '<input type="hidden" name="groupindicator_id" value="' + alldata['data']['group_indicator_id'] + '" />';
		}
		$('#option').empty();
		$('#option').append(html);
		
		if (popup) {
			popupOption($('#option').html());
		}
		// not going to support file upload (the part has been removed)
		//$('.date').datepicker({dateFormat: 'yy-mm-dd'});
		/*$('.datetime').datetimepicker({
			dateFormat: 'yy-mm-dd',
			timeFormat: 'h:m'
		});*/
		//$('.time').datetimepicker({	pickDate: false	});
		
	} else {
		$('#option').empty();
		product_add_option = [];
	}
};
function chnageproduct_id(){
	var product_id = parseInt($('input[name=product_id]').val());
	
};
    function selectedOption(){
        var that=this;
		$('#cart-button-display').hide();
		$('#loading-display').show();
		
        var selOpt=[];
		var name,value;
        var optionObj=$('.optionspop').find('li');
		
        var i=0;
        optionObj.each(function(){
			
            if(typeof(that.obj)!='undefined'){
                if(that.obj.length>0){
                    if($(this).find('select').attr('name')==that.obj.attr('name')){
                        return false;
                    }
                }
            }
            name=$.trim($(this).find('b').text());
            value=$.trim($(this).find('select option:selected').html());
			if(jQuery.inArray( that.clean(name)+'~'+value, selOpt ) == -1){
					selOpt[i]=that.clean(name)+'~'+value;
				}
           // selOpt[i]=that.clean(name)+'~'+value;
			    
            i++;
        });
		
		    /*$( ".optionspop" ).find("select option:selected").each(function() {
			  	name=$.trim($(this).parent().find('b').text());
				value = $.trim($(this).html());
				if(jQuery.inArray( that.clean(name)+'~'+value, selOpt ) == -1){
					selOpt[i]=that.clean(name)+'~'+value;
				}
				i++;
			});*/
		
        if(selOpt.length>0){
            return selOpt;
        }
    };
	
    function clean(v){
        return $.trim(v.replace(':',''));
    };
    /* Change produt options */ 
    /*changeOption:function(thisObj){
        if(this.run==2){
            this.run=0;
            return false;
        }else{
            this.run+=1;
        }
		$('#cart-button-display').hide();
		$('#loading-display').show();
        var selOpt=this.selectedOption();
        var groupbyvalue=$('.grouped_product_select').val();
        //var gpsku=$('.gpsku').val();
//        alert(gpsku);
       // var groupbyname=$('.grouped_product_select>option[value="'+groupbyvalue+'"]').text();
        var that=this;
        $.ajax({
            url: 'index.php?route=product/product_grouped/getGroupOptions',
            beforeSend: function() {
                $(".gp-loader").show();
            },
            complete: function() {
                $(".gp-loader").hide();
            },
            type: 'post',
            dataType: 'json',
            data: {
                'selChoice':selOpt,
                'group_indicator': $("#group_indicator").data('group_indicator'),
                'groupbyname':groupbyname,
                'product_id':groupbyvalue
            },
            success: function(resp) {
                $(".options").html(resp.options);
                if(typeof(that.obj)!=='undefined'){
                    if(that.obj.length>0){
                        that.obj.length=0;
                    }
                }
                $('.options select').fancySelect();
                that.updateProduct(thisObj);
            }
        });
    };*/
    /* Update product details on change of product options*/
    
	function changeOption(name){
		//
		
        var that=this;
		//alert(that.value);
       // var quantity = $("#quantity_span").html();
        var unit_conversion = '';
        var unit_conversion_text = '';
      /*  if ($('#unit').children().length > 0) {
            unit_conversion = $("#get-unit-data:visible").find('option:selected').val();
            unit_conversion_text = $("#get-unit-data:visible").find('option:selected').html().trim();
        }*/ 
        var groupbyvalue = $('input[name=groupbyvalue]').val();
		var group_indicator = parseInt($('input[name=groupindicator_id]').val());
		var product_id = parseInt($('input[name=main_product_id]').val());
		
        var selOpt=that.selectedOption();
        $.ajax({
            url: 'index.php?route=module/pos/getCombinationData&token=' + token,
            beforeSend: function() {
              //  $(".gp-loader").show();
            },
            complete: function() {
             //   $(".gp-loader").hide();
            },
            type: 'post',
            dataType: 'json',
            data: {
                'group_indicator': group_indicator,
                'selChoice':selOpt,
                'groupbyvalue':groupbyvalue,
				'product_id':product_id
            },
			success: function(resp){
				$('input[name=product_id]').val(resp['product_id']);
				$('input[name=sku]').val(resp['sku']);
				html = '';
				selectedval = $('#get-unit-data').val();
				if(resp['unit_datas'] && resp['unit_datas'].length > 0){
					
					if(resp['unit_datas'] !='' && resp['unit_dates_default'] != ''){
						//$("#unit_conversion_values").val(resp['unit_dates_default']['unit_conversion_product_id']);
						//html += '  <input type="hidden" id="unit_conversion_values" name="unit_conversion_values" value="'+resp['unit_dates_default']['unit_conversion_product_id']+'" />';
					}	
									for (j = 0; j < resp['unit_datas'].length; j++) {
											unit_value = resp['unit_datas'][j];                               
											html += '<option data-value ="' + unit_value['unit_conversion_product_id'] + '" value="' + unit_value['convert_price'] + '">' + unit_value['name'];
			
										html += '</option>';
									}
			
									
					
					}
					$('#get-unit-data').each(function(index, element) {
                     //   alert('okok');
                    });
					//alert($('#get-unit-data').html());
					$('#get-unit-data').empty();
					//alert($('#get-unit-data').html());
					$('#get-unit-data').html(html);
					
					
						$("#get-unit-data").val(selectedval);
					
					
						$("#get-unit-data option[value='"+selectedval+"']").prop('selected', true);
						// you need to specify id of combo to set right combo, if more than one combo
					

					
					$('#get-unit-data').trigger('change');
					if(name != 'unit[0]'){
						//processSelectProduct2(resp['product_id']);
					}
					/*if(alldata !='' && alldata['data']['requested_product_data']){
						html += '<input type="hidden" id="groupbyvalue" name="groupbyvalue" value="' + alldata['data']['requested_product_data']['groupbyvalue'] + '" />';
						html += '<input type="hidden" id="groupbyname" name="groupbyname" value="' + alldata['data']['requested_product_data']['groupbyname'] + '" />';
						html += '<input type="hidden" name="groupindicator_id" value="' + alldata['data']['group_indicator_id'] + '" />';
					}*/
			}
            /*success: function(resp) {
                if(typeof(resp.error)!=='undefined'){
                    GroupProduct.obj=thisObj;
                    if(GroupProduct.run == 2){
                        that.errorMail();
                    }
                    that.changeOption();
                }else{
                    if(typeof(that.obj)!='undefined'){
                        that.obj.length=0;
                    }
                    that.replaceOptionIds(resp.op_ids);
                    $(".options").html(resp.option_data);
                    //manish
                    $('.options select').fancySelect();
                    //manish//
                    $('#review').html('');
                    $('#review').load('index.php?route=product/product/review&product_id=' + resp.product_id);
                    $('#qa').html('');
                    $('#qa').load('index.php?route=product/product/question&product_id=' + resp.product_id);
                    $("#product_name").text(resp.name);
                    $("#item_number").text(resp.sku);
                    $("#show_stock").html(resp.stock_status);
                    $("#refrence_number").text(resp.model);
                   $(".iframe-rwd").html(resp.description);
                    if(resp.image === null){
                        $("#image").attr('src', "catalog/view/theme/default/image/no_product.jpg");
                    }else{
                        $("#image").attr('src', resp.image);
                    }
					$("#additionalimages").html(resp.additional_images);
                    $("#image").attr('title', resp.name);
                    $(".cloud-zoom > img").attr('src', resp.image);
                    $(".cloud-zoom").attr('title', resp.name);
                    $(".cloud-zoom").attr('href', resp.large_image);
                    $("#tab-attribute").html(resp.attribute_html);
                    $("#review_status").html("<span class='flr'><img src='catalog/view/theme/default/image/stars-" + resp.rating + ".png' alt=" + resp.reviews + " /><a id='tabs2' onclick=$(a[href=\'#tab-review\']).trigger(\'click\');'>(" + resp.reviews + ")</a></span>");
                    $("a[href='#tab-review']").text(resp.tab_review);
                    $("a[href='#tab-qa']").text(resp.text_tab_qa);
//                    $(".clearfix.img-box2").html(resp.add_image_data);
                    //$("#price-update").text(resp.price);
                    $("#unit_dis").text(resp.unit);
                    $("#product_id_change").val(resp.product_id);
                    $("ul.update_discount_price_group").html(resp.get_product_discount);
                    $(".ig_Units").html(resp.product_unit_data_ajax);
                    $("#base_price_input").val(resp.base_price);
					$("#additionalimages").html(resp.additional_images);
                    that.updatePrice();
                    that.addUnit();
                    $(".gp-loader").hide();
                    $('.cloud-zoom').CloudZoom();
                    $('.colorbox').colorbox({
                        overlayClose: true,
                        opacity: 0.5,
                        rel: "colorbox"
                    });
                }
            }*/
        });
    };
    /* Update Price : Quantity change, Unit change */
   /* updatePrice:function(){
        var that=this;
        var p_id = $('input[name="product_id"]').val();
        var base_price = $("#base_price_input").val();
        var quantity = $(".quantity:visible").val();
        var unit_type = $("#get-unit-data:visible").find('option:selected').attr('data-value');
        var simplePrice = $(".top-gap").next().find(".price-new").text();
        var unit_fullName = $("#get-unit-data:visible").find('option:selected').attr('name');
        var plural_unit = $("#plural_unit").val();
        var conversion_price = $("#get-unit-data:visible").find('option:selected').val();
		var default_conversion_value_name = $("#default_conversion_value_name").val();
        $.ajax({
            url: 'index.php?route=product/product/calcPrice2',
            type: 'post',
            dataType: 'json',
            data: {
                "p_id": p_id,
                "simplePrice": simplePrice,
                "base_price": base_price,
                "quantity": quantity,
                "unit_type": unit_type,
                "conversion_price": conversion_price,
                "unit_fullName": unit_fullName,
                "plural_unit": plural_unit,
				"default_conversion_value_name": default_conversion_value_name
            },
            success: function(resp) {
//                console.log(resp);
                $('.unit_conversion_values').val(unit_type);
                $(".product-block").find(".price-new").html(resp.calc_price);
                if(resp.discount_quantity){
                	$(".product-block").find("#quantity_span").html(resp.discount_quantity);
				}else{
					$(".product-block").find("#quantity_span").html(resp.quantity);
				}
				//$(".product-block").find("#quantity_span").html(resp.discount_quantity);
                $(".product-block").find("#unit_dis").html(resp.unit_fullName);
                var quan = $(".quantity").val();
                var prodOption = $(".ig_MetalType").find("#get-unit-data:visible").find('option:selected').text();
//                console.log(prodOption);
                if(resp.unit_bulk_pricing){
                   that.updateDiscountBox(resp.unit_bulk_pricing);
                }
                var helpText = that.getHelpText(quan, prodOption);
                $('.option_tooltip:visible').attr('data-original-title', helpText);
				$('#converstion_string_display').html(resp.converstion_string);
				default_conversion_value_name = $.trim($("#default_conversion_value_name").val());
				unit_fullName = $.trim($("#get-unit-data:visible").find('option:selected').attr('name'));
				if(default_conversion_value_name != unit_fullName){
					$('#converstion_string_display').show();
				}else{
					$('#converstion_string_display').hide();
				}
				
                refrshTooltip();
				$('#loading-display').hide();
				$('#cart-button-display').show();
				if(resp.unit_convertion_enable == 0){
					$('#get-unit-data').remove();
				}
              
            }
        });
    };//update discount */
function popupOption(html) {
	var popup_var = true;
	var popup_dialog = '<div id="popup_option_dialog" class="fbox_cont popup_option"><h3>Enter Quantity</h3><div class="table-container form-box"><ul class="form_list">' + html + '</ul></div>';
	popup_dialog += '<div class="fbox_btn_wrap"><a href="javascript:void(0);" title="Update Stock" onclick="$.fancybox.close();openStockModel('+ $("#product_new input[name=product_id]").val() +','+ popup_var + ');" class="btn btn-info pull-left"><i class="fa fa-refresh"></i></a><a onclick="addProductWithOption();" class="table-btn-common">' + button_add_product + '</a></div></div>';
	openFancybox(false, 'normal', popup_dialog, clearOptionMessage);
};

function openScannerWithOption(focusOn = '')
            {
            	if (focusOn !== ''){
            		$("input[name='"+focusOn+"']").focus();
            	}
                try {
					window.webkit.messageHandlers.iosHandler.postMessage("openBarCodeScanner:printQrCodeNoncatalog");
				 } catch(err) {
						console.log('Can not reach native code');
				 }
            }

function printQrCodeNoncatalog(value)
            {
				$("input[name='quick_sale_name']").val(value);
				//document.getElementById('search').value = value
				/* delay(function() {
					// search product when input product name in search field
					var filter_name = $('input[name=filter_product]').val();
					if (filter_name != '') {
						var url = 'index.php?route=module/pos/autocomplete_product&token=' + token;
						var data = {'filter_name':filter_name, 'customer_group_id':customer_group_id, 'filter_scopes':searchScopes};
						$.ajax({
							url: url,
							type: 'post',
							data: data,
							dataType: 'json',
							cacheCallback: function(json) {
								backendSaveProducts(json);
							},
							cachePreDone: function(cacheKey, callback) {
								backendGetProducts(data, callback);
							},
							success: function(json) {
								if (json && json.length == 1) {
									// a single product
									$('input[name=current_product_id]').val(json[0]['product_id']);
									$('input[name=current_product_name]').val(json[0]['name']);
									$('input[name=current_product_weight_price]').val(json[0]['weight_price']);
									$('input[name=current_product_weight_name]').val(json[0]['weight_name']);
									$('input[name=current_product_hasOption]').val(json[0]['hasOptions']);
									$('input[name=current_product_price]').val(json[0]['price']);
									$('input[name=current_product_tax]').val(json[0]['tax']);
									$('input[name=current_product_points]').val(json[0]['points']);
									$('input[name=current_product_image]').val(json[0]['image']);
								}
								populateBrowseTable(json, true);
							}
						});
					}
				}, 500);*/
            }	

function clearOptionMessage() {
	deQueue();
	removeMessage();
	showMessage('success', text_order_ready);
}
$(document).on('change', '.changeoption', function() {
	// change on the dialog will be reflected to the fields on the page
	//changeOption($(this));
});
function addProductWithOption() {
	
	var formData = {};
	var productFormData = '#product_new input[type=\'text\'], #product_new input[type=\'hidden\'], #product_new input[type=\'radio\']:checked, #product_new input[type=\'checkbox\']:checked, #product_new select, #product_new textarea';
	$(productFormData).each(function() {
		formData[$(this).attr('name')] = $(this).val();
	});
	var add_option = {};
	for (var i in formData) {
		if (i.substring(0, 7) == 'option[') {
			add_option[i] = formData[i];
		}
	}
	var option_error = [];
	for (var i in product_add_option) {
		if (parseInt(product_add_option[i]['required']) > 0) {
			var option_attr_name = 'option[' + product_add_option[i]['product_option_id'] + '][value]';
			if (!add_option[option_attr_name]) {
				option_error[product_add_option[i]['product_option_id']] = product_add_option[i]['name'];
			}
		}
	}
	
	$('.has-error').each(function() {
		$(this).find('.text-danger').remove();
		$(this).removeClass('has-error');
	});
	if (!$.isEmptyObject(option_error)) {
		var errorSize = 0;
		for (var i in option_error) {
			$('#popup_option_dialog #option-' + i).addClass('has-error');
			$('#popup_option_dialog #option-' + i).find('div').append('<div class="text-danger">' + error_required.replace('%s', option_error[i]) + '</div>');
			errorSize ++;
		}
		resizeFancybox();
	} else if (matchedSN == 0 && formData['product_sn']) {
		$('.text-danger').remove();
		$('#popup_option_dialog #option-pos-sn div').append('<div class="text-danger">' + text_product_sn_not_found + '</div>');
		resizeFancybox();
	} else {
		$('#button_product').trigger('click');
		closeFancybox();
	}
};

$(document).on('change', '#popup_option_dialog input, #popup_option_dialog textarea, #popup_option_dialog select', function() {
	// change on the dialog will be reflected to the fields on the page
	var type = $(this).prop('tagName').toLowerCase();
	var name = $(this).attr('name');
	var value = $(this).val();
	$("#option " + type + "[name='" + name + "']").val(value);
	//alert($('input[name=\'product_id\']').val());
	
	if (type == 'select' && name != 'unit[0]') {
		
		//processSelectProduct2($('input[name=\'main_product_id\']').val());
	}
	
	if (type == 'select') {
		$("#option " + type + "[name='" + name + "']").trigger('change');
		changeOption(name);
	}
	
});

function handleNonCatalogEntry(){
	var sku = $('input[name=sku]').val();
	if (sku != ''){
		$('input[name=quick_sale_name]').val(sku);
	}
}
// add for SKU begin
function handleSKUEntry() {
	// search the product using SKU
	var sku = $('input[name=sku]').val();
	if (sku != '') {
		$.ajax({
			url: 'index.php?route=module/pos/handleSKUEntry&token=' + token + '&sku=' + sku + '&customer_group_id=' + customer_group_id,
			type: 'post',
			dataType: 'json',
			beforeSend: function() {
				removeMessage();
				showMessage('notification', '');
			},
			cacheCallback: function(json) {
				// do not save the sku search result
			},
			cachePreDone: function(cacheKey, callback) {
				backendGetProductByAttribute('sku', sku, callback);
			},
			success: function(json) {
				if (json['product_id']) {
					// product found by SKU
					$('input[name=\'product\']').val(json['name']);
					$('input[name=\'product_id\']').val(json['product_id']);
					$('input[name=product_points]').val(json['points']);
					$('input[name=product_reward_points]').val(json['reward_points']);
					$('input[name=product_price]').val((config['config_tax'] && parseInt(config['config_tax']) == 1) ? parseFloat(json['price']) + parseFloat(json['tax']) : json['price']);
					$('input[name=sku]').val(sku);
					$('input[name=model]').val(json['model']);
					$('input[name=quantity]').val(1);
					$('input[name=product_image]').val(json['image']);
					
					// add for Weight based price begin
					if (json['weight_price'] == '1' || json['option']) {
						// set weight as option
						var needPopup = false;
						
						var html = '';
						if (json['weight_price'] == '1') {
							$('#option').empty();
							html += '<li id="option-pos-weight">';
							html += '<label>' + json['weight_name'] + '</label>';
							html += '<div class="inputbox"><input type="text" name="weight" value="" />';
							html += '<input type="hidden" name="weight_price" value="1" />';
							html += '<input type="hidden" name="weight_name" value="' + json['weight_name'] + '" />';
							html += '</div></li>';
							$('#option').append(html);
							needPopup = true;
						}
						if (json['option']) {
								removeMessage();
								if (text_work_mode == '1') {
									showMessage('success', text_quote_ready);
								} else {
									showMessage('success', text_order_ready);
								}
								handleOptionReturn(json['name'], json['product_id'], json['option'], true,json['unit_datas'],json['DefaultUnitdata'],json);
						} else if (html != '') {
							// only popup the weight
							popupOption(html);
						}
					} else {
						// no option, add the product straightway
						chooseProduct();
					}
				} else {
					removeMessage();
					showMessage('error', text_no_product_for_sku + sku);
				}
			}
		});
	}
};
// add for SKU end
// add for UPC begin
$(document).on('keydown', 'input[name=upc]', function(e) {
	if (e.keyCode == 13) {
		handleUPCEntry();
	}
});

function handleUPCEntry() {
	// search the product using UPC
	var upc = $('input[name=upc]').val();

	if (upc != '') {
		$.ajax({
			url: 'index.php?route=module/pos/handleUPCEntry&token=' + token + '&upc=' + upc + '&customer_group_id=' + customer_group_id,
			type: 'post',
			dataType: 'json',
			beforeSend: function() {
				removeMessage();
				showMessage('notification', '');
			},
			cacheCallback: function(json) {
				// do not save the upc search result
			},
			cachePreDone: function(cacheKey, callback) {
				backendGetProductByAttribute('upc', upc, callback);
			},
			success: function(json) {
				if (json['product_id']) {
					// product found by UPC
					$('input[name=\'product\']').val(json['name']);
					$('input[name=\'product_id\']').val(json['product_id']);
					$('input[name=product_points]').val(json['points']);
					$('input[name=product_reward_points]').val(json['reward_points']);
					$('input[name=product_price]').val((config['config_tax'] && parseInt(config['config_tax']) == 1) ? parseFloat(json['price']) + parseFloat(json['tax']) : json['price']);
					$('input[name=upc]').val(upc);
					$('input[name=model]').val(json['model']);
					$('input[name=quantity]').val(1);
					$('input[name=product_image]').val(json['image']);
					
					if (json['weight_price'] == '1' || json['option']) {
						// set weight as option
						var needPopup = false;
						
						var html = '';
						if (json['weight_price'] == '1') {
							$('#option').empty();
							html += '<li id="option-pos-weight">';
							html += '<label>' + json['weight_name'] + '</label>';
							html += '<div class="inputbox"><input type="text" name="weight" value="" />';
							html += '<input type="hidden" name="weight_price" value="1" />';
							html += '<input type="hidden" name="weight_name" value="' + json['weight_name'] + '" />';
							html += '</div></li>';
							$('#option').append(html);
							needPopup = true;
						}
						if (json['option']) {
								removeMessage();
								if (text_work_mode == '1') {
									showMessage('success', text_quote_ready);
								} else {
									showMessage('success', text_order_ready);
								}
								handleOptionReturn(json['name'], json['product_id'], json['option'], true,json['unit_datas'],json['DefaultUnitdata'],json);
						} else if (html != '') {
							// only popup the weight
							popupOption(html);
						}
					} else {
						// no option, add the product straightway
						chooseProduct();
					}
				} else {
					removeMessage();
					showMessage('error', text_no_product_for_upc + upc);
				}
			}
		});
	}
};
// add for UPC end
// add for MPN begin
$(document).on('keydown', 'input[name=mpn]', function(e) {
	if (e.keyCode == 13) {
		handleMPNEntry();
	}
});

function handleMPNEntry() {
	// search the product using MPN
	var mpn = $('input[name=mpn]').val();
	if (mpn != '') {
		$.ajax({
			url: 'index.php?route=module/pos/handleMPNEntry&token=' + token + '&mpn=' + mpn + '&customer_group_id=' + customer_group_id,
			type: 'post',
			dataType: 'json',
			beforeSend: function() {
				removeMessage();
				showMessage('notification', '');
			},
			cacheCallback: function(json) {
				// do not save the mpn search result
			},
			cachePreDone: function(cacheKey, callback) {
				backendGetProductByAttribute('mpn', mpn, callback);
			},
			success: function(json) {
				if (json['product_id']) {
					// product found by MPN
					$('input[name=\'product\']').val(json['name']);
					$('input[name=\'product_id\']').val(json['product_id']);
					$('input[name=product_points]').val(json['points']);
					$('input[name=product_reward_points]').val(json['reward_points']);
					$('input[name=product_price]').val((config['config_tax'] && parseInt(config['config_tax']) == 1) ? parseFloat(json['price']) + parseFloat(json['tax']) : json['price']);
					$('input[name=mpn]').val(mpn);
					$('input[name=model]').val(json['model']);
					$('input[name=quantity]').val(1);
					$('input[name=product_image]').val(json['image']);
					
					if (json['weight_price'] == '1' || json['option']) {
						// set weight as option
						var html = '';
						if (json['weight_price'] == '1') {
							$('#option').empty();
							html += '<li id="option-pos-weight">';
							html += '<label>' + json['weight_name'] + '</label>';
							html += '<div class="inputbox"><input type="text" name="weight" value="" />';
							html += '<input type="hidden" name="weight_price" value="1" />';
							html += '<input type="hidden" name="weight_name" value="' + json['weight_name'] + '" />';
							html += '</div></li>';
							$('#option').append(html);
							needPopup = true;
						}
						if (json['option']) {
							removeMessage();
							if (text_work_mode == '1') {
								showMessage('success', text_quote_ready);
							} else {
								showMessage('success', text_order_ready);
							}
							handleOptionReturn(json['name'], json['product_id'], json['option'], true,json['unit_datas'],json['DefaultUnitdata'],json);
						} else if (html != '') {
							// only popup the weight
							popupOption(html);
						}
					} else {
						// no option, add the product straightway
						chooseProduct();
					}
				} else {
					removeMessage();
					showMessage('error', text_no_product_for_mpn + mpn);
				}
			}
		});
	}
};
// add for MPN end
$(document).ready(function(){
	$("#oit").fancybox({
                'transitionIn' : 'elastic',
                'transitionOut' : 'elastic',
                'speedIn' : 500,
                'speedOut' : 400,
                'overlayShow' : true,
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
});


function chooseOption()
{
	if (!$('#return_option_dialog').is(':visible')) {
				openFancybox('#return_option_dialog');
			}
}
function showReturn()
{
	//openFancybox('#ntab_returns');
	//$("#ntab_returns").show();
	//stockManagerShow();
	chooseOption();
	//closeFancybox();

	/*$("#ntab_panel").show();
	$("input[name=filter_product_for_edit]").show();
	$("input[name=filter_product_update_stock]").hide();
	$("input[name=filter_product]").hide();
  	$("#updateStockContainer").hide();
  	$(".cart-container").hide();
  	$(".right-container").show();
	$(".search_products").val("");
	$(".product-box-outer").html("");*/

}
$("#newReturn").click(function() {
	var html = '<div style="margin-top:30px;text-align:center"><i class="fa fa-spinner fa-spin"></i> Please Wait!</div>';
	$("#tab_returns").html(html);
	$('#tab_returns').load('index.php?route=catalog/wk_customer_orders_pos&token=' + token);  
});
/* $("#newReturn").fancybox({
                'transitionIn' : 'elastic',
                'transitionOut' : 'elastic',
                'speedIn' : 500,
                'speedOut' : 400,
                'width': 1024,
                'overlayShow' : true,
                'overlayOpacity' : 0.7,
                'hideOnOverlayClick' : false,
                'hideOnContentClick' : false,
                'type' : 'iframe'
        });  */

$("#modifyReturn").click(function() {
	//showReturn();
	//stockManagerShow();
	//chooseOption();
	//closeFancybox();
	$(".right-container").hide();
  	$("#updateStockContainer").hide();
  	$("#stock_manager_panel").show();
  	closeFancybox();
  	//$('#returnLists tbody').html("hello");
  	//var hash = document.location.hash;
  	//alert(hash)
	//var prefix = "tab_";
	//$('.nav-tabs a[href="#tab_returns"]').trigger('show.bs.tab');
});

$("#closebox").click(function() {
	closeFancybox();
});

function refreshPage(mode) {
	if (mode == 'order' || mode == 'quote') {
		// move the current cache return to its "named" local return
		moveCurrentReturn();
		
		selectOrder(0, 0);
	} else if (mode == 'return'){
		// move the current cache order to its "named" local order
		moveCurrentOrder();
		
		selectReturn(0, 0);
	}
};

// add for Print begin
function afterPrintReceipt() {
	closeWaitDialog();
}
// add for Print end


$(document).on('click', '#button_product', function() {
	chooseProduct();
});

function chooseProduct() {
	var product_id = parseInt($('input[name=product_id]').val());
	if (isNaN(product_id) || product_id == 0) {
		openAlert(text_not_valid_product);
		return;
	}
	var formData = {};
	var productFormData = '#product_new input[type=\'text\'], #product_new input[type=\'hidden\'], #product_new input[type=\'radio\']:checked, #product_new input[type=\'checkbox\']:checked, #product_new select, #product_new textarea';
	$(productFormData).each(function() {
		formData[$(this).attr('name')] = $(this).val();
	});
	var add_option = {};
	for (var i in formData) {
		if (i.substring(0, 7) == 'option[') {
			add_option[i] = formData[i];
		}
	}
	// check if the required option has value set
	var option_error = [];
	for (var i in product_add_option) {
		var option_attr_name = 'option[' + product_add_option[i]['product_option_id'] + '][value]';
		if (!add_option[option_attr_name]) {
			if (parseInt(product_add_option[i]['required']) > 0) {
				option_error[product_add_option[i]['product_option_id']] = product_add_option[i]['name'];
			}
			// remove options without value
			var option_remove_prefix = 'option[' + product_add_option[i]['product_option_id'] + ']';
			for (var remove_index in add_option) {
				if (remove_index.substring(0, option_remove_prefix.length) == option_remove_prefix) {
					delete add_option[remove_index];
				}
			}
		}
	}
	
	$('.has-error').each(function() {
		$(this).find('.text-danger').remove();
		$(this).removeClass('has-error');
	});
	if (option_error.length > 0) {
		for (var i in option_error) {
			$('#option-' + i).addClass('has-error');
			$('#option-' + i).find('div').append('<div class="text-danger">' + error_required.replace('%s', option_error[i]) + '</div>');
		}
		return false;
	}

	var add_quantity = parseFloat($('input[name=quantity]').val());
	var add_price = parseFloat($('input[name=product_price]').val());
	var subtract = parseInt($('input[name=subtract]').val());
	var labour_cost = $('input[name=product_labour_cost]').val(); 
	var unique_option_price = $('input[name=product_unique_option_price]').val(); 
	var name = $('input[name=product]').val();
	//alert($("#get-unit-data").find('option:selected').attr('data-value'));
	//alert($('#unit_conversion_values').val());
	var coversion_id = $('#unit_conversion_values').val();
	//var coversion_id = $("#get-unit-data").find('option:selected').attr('data-value');
	var image = $('input[name=product_image]').val();
	var reward_points = $('input[name=product_reward_points]').val();
	var data = {'action':'insert', 'product_id':product_id, 'option':add_option, 'quantity':add_quantity, 'price':add_price, 'subtract':subtract, 'name':name, 'image':image, 'reward_points':reward_points, 'coversion_id':coversion_id, 'labour_cost':labour_cost, 'unique_option_price':unique_option_price};
	if (formData['weight_price'] && parseInt(formData['weight_price']) == 1) {
		data['weight_price'] = 1;
		data['weight_name'] = formData['weight_name'];
		data['weight'] = formData['weight'];
	}
	addProduct(data);
}
function addUnit(){
	
        var coversion_id = $("#get-unit-data").find('option:selected').attr('data-value');
       // alert(coversion_id);
		document.getElementById("unit_conversion_values").value = coversion_id;
		//alert($('#unit_conversion_values').val());
		$('#unit_conversion_values').val(coversion_id);
		
    }
function addProduct(add_data) {
	// update the order product list, using the stored products object
	
	var data = add_data;
	var product_id = data['product_id'];
	var tax_class_id = 0;
	var shipping = 0;
	var ref_product = getProduct(product_id);
	if (ref_product) {
		tax_class_id = ref_product['tax_class_id'];
		shipping = ref_product['shipping'];
	}
	var add_option = data['option'];
	var product_exists = false;
	var index = 0;
	// use weight string to compare product existing
	var weight = 1;
	if (add_data['weight']) {
		weight = parseFloat(add_data['weight']);
	}
	
	var orderFormData = {};
	$('#product input').each(function() {
		orderFormData[$(this).attr('name')] = $(this).val();
	});
	var order_products = {};
	for (var i in orderFormData) {
		if (i.substring(0,14) == 'order_product[') {
			order_products[i] = orderFormData[i];
		}
	}

	var index_list = [];
	for (var i in order_products) {
		var i_suffix = '[product_id]';
		if (i.length > i_suffix.length && i.substring(i.length-i_suffix.length) == i_suffix && order_products[i] == product_id) {
			var index_bracket_begin = i.indexOf('[');
			var index_bracket_end = i.indexOf(']');
			index = i.substring(index_bracket_begin+1, index_bracket_end);
			product_exists = true;
			index_list.push(index);
		}
	}

	// check weight is the same if the product_id does exists
	if (product_exists && data['weight_price'] == 1) {
		var weight_exist = false;
		for (var index_i in index_list) {
			var index_weight = 'order_product['+index_list[index_i]+'][weight]';
			for (var i in order_products) {
				if (i == index_weight && parseFloat(order_products[i]) == weight) {
					weight_exist = true;
					index = index_i;
					break;
				}
			}
			if (weight_exist) {
				break;
			}
		}
		
		if (!weight_exist) {
			product_exists = false;
		}
	}
	// check sn is the same if the product_id does exists
	var product_sn = $('input[name=product_sn]').val();
	var product_sn_id = $('input[name=product_sn_id]').val();
	if (product_exists && product_sn) {
		var sn_exist = false;
		for (var index_i in index_list) {
			var index_sn = 'order_product['+index_list[index_i]+'][sn]';
			for (var i in order_products) {
				if (i == index_sn && order_products[i] == product_sn) {
					sn_exist = true;
					index = index_i;
					break;
				}
			}
			if (sn_exist) {
				break;
			}
		}
		
		if (!sn_exist) {
			product_exists = false;
		}
	}
	if (product_exists && product_add_option.length > 0) {
		// compare if option is the same
		var order_product_option_min_size = 1;
		
		for (var index_i in index_list) {
			var order_product_option_size = 0;
			for (var i in order_products) {
				var i_prefix = 'order_product['+index_list[index_i]+'][order_option]';
				if (i.substring(0, i_prefix.length) == i_prefix) {
					order_product_option_size++;
				}
			}
			if (order_product_option_size < order_product_option_min_size) {
				order_product_option_min_size = order_product_option_size;
				index = index_list[index_i];
			}
		}
		
		var add_option_size = 0;
		for (var i in add_option) { add_option_size++; }
		
		if (add_option_size == 0) {
			if (order_product_option_min_size > 0) {
				product_exists = false;
			}
		} else {
			product_exists = false;
			for (var index_i in index_list) {
				index = index_list[index_i];
				
				// match add_option with order_products[index]
				var add_option_matched_count = 0;
				var add_option_number = 0;
				for (var i in product_add_option) {
					var add_option_key = 'option[' + product_add_option[i]['product_option_id'] + '][name]';
					var type = product_add_option[i]['type'];
					if (add_option[add_option_key]) {
						if (type == 'select' || type == 'radio' || type == 'image') {
							add_option_number++;
							var add_product_option_value_id = add_option['option[' + product_add_option[i]['product_option_id'] + '][product_option_value_id]'];
							var ext_product_option_value_id = order_products['order_product['+index+'][order_option][' + product_add_option[i]['product_option_id'] + '][product_option_value_id]'];
							if (add_product_option_value_id == ext_product_option_value_id) {
								add_option_matched_count++;
							}
						} else if (type == 'checkbox') {
							if (product_add_option[i]['option_value']) {
								for (var j in product_add_option[i]['option_value']) {
									var product_option_value_id = product_add_option[i]['option_value'][j]['product_option_value_id'];
									var add_option_value_key = 'option[' + product_add_option[i]['product_option_id'] + '][product_option_value_id][' + product_option_value_id + '][value]';
									if (add_option[add_option_value_key]) {
										add_option_number++;
										if (order_products['order_product['+index+'][order_option][' + product_add_option[i]['product_option_id'] + '][product_option_value_id][' + product_option_value_id + ']']) {
											add_option_matched_count++;
										}
									}
								}
							}
						} else {
							add_option_number++;
							var add_option_value = add_option['option[' + product_add_option[i]['product_option_id'] + '][value]'];
							var ext_option_value = order_products['order_product['+index+'][order_option][' + product_add_option[i]['product_option_id'] + '][value]'];
							if (add_option_value == ext_option_value) {
								add_option_matched_count++;
							}
						}
					}
				}
				// match order_products[index] with add_option
				var order_product_matched_count = 0;
				var order_product_option_number = 0;
				for (var i in product_add_option) {
					var order_product_option_key = 'order_product['+index+'][order_option][' + product_add_option[i]['product_option_id'] + '][product_option_id]';
					var type = product_add_option[i]['type'];
					if (order_products[order_product_option_key]) {
						if (type == 'select' || type == 'radio' || type == 'image') {
							order_product_option_number++;
							var add_product_option_value_id = add_option['option[' + product_add_option[i]['product_option_id'] + '][product_option_value_id]'];
							var ext_product_option_value_id = order_products['order_product['+index+'][order_option][' + product_add_option[i]['product_option_id'] + '][product_option_value_id]'];
							if (add_product_option_value_id == ext_product_option_value_id) {
								order_product_matched_count++;
							}
						} else if (type == 'checkbox') {
							if (product_add_option[i]['option_value']) {
								for (var j in product_add_option[i]['option_value']) {
									var product_option_value_id = product_add_option[i]['option_value'][j]['product_option_value_id'];
									var order_product_option_value_key = 'order_product['+index+'][order_option][' + product_add_option[i]['product_option_id'] + '][product_option_value_id][' + product_option_value_id + ']';
									if (order_products[order_product_option_value_key]) {
										order_product_option_number++;
										if (add_option['option[' + product_add_option[i]['product_option_id'] + '][product_option_value_id][' + product_option_value_id + '][value]']) {
											order_product_matched_count++;
										}
									}
								}
							}
						} else {
							order_product_option_number++;
							var add_option_value = add_option['option[' + product_add_option[i]['product_option_id'] + '][value]'];
							var ext_option_value = order_products['order_product['+index+'][order_option][' + product_add_option[i]['product_option_id'] + '][value]'];
							if (add_option_value == ext_option_value) {
								order_product_matched_count++;
							}
						}
					}
				}
				if (add_option_matched_count == add_option_number && order_product_matched_count == order_product_option_number) {
					product_exists = true;
					break;
				}
			}
		}
	}
	
	var add_quantity = data['quantity'];
	var add_price = data['price'];
	var add_price_text = formatMoney(add_price);
	var add_total_text = formatMoney(add_quantity * add_price * weight);
	
	if (product_exists) {
		// update the quantity and total for the existing row
		var ex_quantity = parseInt($('#product tr:eq('+index+') input[name$=\'[quantity]\']').val());
		var ex_price = posParseFloat($('#price_anchor_'+index).text().substring(2));
		
		$('.quantity_anchor_'+index).text(ex_quantity + add_quantity);
		$('#product tr:eq('+index+') input[name$=\'[quantity]\']').val(ex_quantity + add_quantity);
		
		var text_total = formatMoney((ex_quantity + add_quantity) * ex_price * weight);
		$('#total_text_only-'+index).text(text_total);
		$('#total_text_only-'+index).closest('td').find('input').val(text_total);
		
		data['action'] = 'modify_quantity';
		data['index'] = index;
		data['quantity_before'] = ex_quantity;
		data['quantity_after'] = ex_quantity + add_quantity;
		data['order_product_id'] = $('#product tr:eq('+index+') input[name$=\'[order_product_id]\']').val();
	} else {
		// add a new row
		var new_row_num = $('#product tr').length;
		new_row_id = 'product-row' +  new_row_num;
		$('.totalOrderItem span').text((new_row_num+1));
		html = '<tr id="' + new_row_id + '" class="' + ((new_row_num % 2 == 0) ? 'odd' : 'even') + '">';
		html += '<input type="hidden" name="order_product[' + new_row_num + '][order_product_id]" value="" />';
		html += '<input type="hidden" name="order_product[' + new_row_num + '][product_id]" value="' + product_id + '" /></td>';
		html += '<input type="hidden" name="order_product[' + new_row_num + '][quantity]" value="' + add_quantity + '" />';
		html += '<input type="hidden" name="order_product[' + new_row_num + '][tax_class_id]" value="' + tax_class_id + '" />';
		html += '<input type="hidden" name="order_product[' + new_row_num + '][shipping]" value="' + shipping + '" />';
		html += '<input type="hidden" name="order_product[' + new_row_num + '][subtract]" value="' + add_data['subtract'] + '" />';
		html += '<input type="hidden" name="order_product[' + new_row_num + '][product_normal_price]" value="' + add_price_text + '" />';
		
		html += '<td align="left" valign="middle" class="two">';
		html += '	<span class="product-name">';
		var sku = $('input[name=sku]').val();
		html += '		<span class="raw-name" style="font-weight:bold;text-decoration:none;cursor:pointer;" onclick="showProductDetails(' + product_id + ')" id="order_product[' + new_row_num + '][order_product_display_name]">' + (new_row_num+1)+"- "+sku + '</span>';
		
		if (sku) {
			html += '<br /><small>' + data['name']+'</small>' + ' ( <small>' + data['labour_cost'] + '</small> + <small>' + data['unique_option_price'] + '</small> )';
		}
		
		if (data['weight_price'] && data['weight_price'] == 1) {
			html += '	<br />&nbsp;<small> - ' + data['weight_name'] + ': ' + weight + '</small>';
		}
		html +=	'	<input type="hidden" name="order_product[' + new_row_num + '][weight_price]" value="1" />';
		html +=	'	<input type="hidden" name="order_product[' + new_row_num + '][weight]" value="' + weight + '" />';
		for (var j in product_add_option) {
			var add_option_key = 'option[' + product_add_option[j]['product_option_id'] + '][name]';
			if (add_option[add_option_key]) {
				var name = product_add_option[j]['name'];
				var type = product_add_option[j]['type'];
				var product_option_value_id = parseInt(add_option['option[' + product_add_option[j]['product_option_id'] + '][product_option_value_id]']);
				var value = '';
				var product_option_value_ids = [];
				if (type == 'select' || type == 'radio' || type == 'image') {
					for (var k in product_add_option[j]['option_value']) {
						if (product_option_value_id == product_add_option[j]['option_value'][k]['product_option_value_id']) {
							value = product_add_option[j]['option_value'][k]['name'];
							break;
						}
					}
				} else if (type == 'checkbox') {
					for (var k in product_add_option[j]['option_value']) {
						var l_key = 'option[' + product_add_option[j]['product_option_id'] + '][product_option_value_id][' + product_add_option[j]['option_value'][k]['product_option_value_id'] + '][value]';
						if (add_option[l_key]) {
							product_option_value_ids[product_add_option[j]['option_value'][k]['product_option_value_id']] = add_option[l_key];
							value += add_option[l_key] + ', ';
						}
					}
				} else {
					value = add_option['option[' + product_add_option[j]['product_option_id'] + '][value]'];
				}
				
				if (value != '') {
					if (type == 'checkbox') {
						for (var l in product_option_value_ids) {
							html +=		' <input type="hidden" name="order_product[' + new_row_num + '][order_option][' + product_add_option[j]['product_option_id'] + '][product_option_value_id]['+l+']" value="' + product_option_value_ids[l] + '" />';
						}
						value = value.substring(0, value.length-2);
					} else {
						html +=		' <input type="hidden" name="order_product[' + new_row_num + '][order_option][' + product_add_option[j]['product_option_id'] + '][product_option_value_id]" value="' + product_option_value_id + '" />';
					}
					html +=		'<br />&nbsp;<small> - ' + name + ': ' + value + '</small>';
					html +=		' <input type="hidden" name="order_product[' + new_row_num + '][order_option][' + product_add_option[j]['product_option_id'] + '][product_option_id]" value="' + product_add_option[j]['product_option_id'] + '" />';
					html +=		' <input type="hidden" name="order_product[' + new_row_num + '][order_option][' + product_add_option[j]['product_option_id'] + '][type]" value="' + type + '" />';
					html +=		' <input type="hidden" name="order_product[' + new_row_num + '][order_option][' + product_add_option[j]['product_option_id'] + '][value]" value="' + value + '" />';
				}
			}
		}
		// add for serial no begin
		var product_sn = $('input[name=product_sn]').val();
		var product_sn_id = $('input[name=product_sn_id]').val();
		
		
		
		if (product_sn) {
			html += '<br />&nbsp;<small> - SN: ' + $('input[name=product_sn]').val();
		}
		html +='<br />&nbsp;<small id="convertion_line'+new_row_num+'" style="color:#DD0205; font-weight:bold;"></small>';
		if ($('#input_weight').is(':visible')) {
			html +=		'<br />&nbsp;<small> - ' + $('#weight_name').text() + ' ' + weight + '</small>';
		}
		html +=		' <input type="hidden" name="order_product[' + new_row_num + '][weight_price]" value="1" />';
		html +=		' <input type="hidden" name="order_product[' + new_row_num + '][weight]" value="' + weight + '" />';
		html += '	</span>';
		html += '<span  style="background:#dddee6; padding:5px;">	';
		html +='<a class="quantity_anchor_'+new_row_num+'" data-unit="Piece" onclick="changeQuantity(this,'+new_row_num+')">'+add_quantity+' Pieces</a>';
		html +='&nbsp; X &nbsp;'
		html +='<a id="price_anchor_' + new_row_num + '" onclick="changePrice(this,'+new_row_num+');"> '+add_price+' / (% off)</a></span>';
		html += '</td>';
		html += '<input type="hidden" name="order_product[' + new_row_num + '][price]" value="' + add_price + '" />';
		html += ' <input type="hidden" name="order_product[' + new_row_num + '][product_discount_type]" value="0" />';
		html += ' <input type="hidden" name="order_product[' + new_row_num + '][product_discount_value]" value="0" />';
		///// waqar
		/* html += '<td align="center" valign="middle" class="three"><span class="cart-round-img-outr" onclick="changeQuantity(this,'+new_row_num+');"><a class="cart-round-qty-raqaw quantity_anchor_' + new_row_num + '">' + add_quantity + '</a></span></td>'; */
		///// waqar end
		//html += '<td></td>';
		html += '<td align="center" valign="middle" class="one">';
		html +='<a onclick="$(\'#price_anchor_' + new_row_num + '\').closest(\'span\').trigger(\'click\');" class="cart-link">';
		html +='<span id="total_text_only-' + new_row_num + '">' + add_total_text + '</span>';
		html +='</a>';
		html +='<td align="center" valign="middle" class="one">';
		html +='<span class="cart-round-img-outr" onclick="changeQuantity(this,'+new_row_num+');">';
		html +='<img src="' + data['image'] + '" class="cart-round-img" alt="">';
		html +='<a class="cart-round-qty quantity_anchor_' + new_row_num + '">' + add_quantity + '</a>';
		html +='</span></td>';
		html += '<input type="hidden" name="order_product[' + new_row_num + '][product_total_text]" value="' + add_total_text + '" /></td>';
		///// waqar
		html += '<td align="center" valign="middle" class="four"><button type="button" style="margin-bottom: 2px;"  data-loading-text="Loading" class="btn btn-danger" onclick="deleteOrderProduct(this)"><i class="fa fa-minus-circle"></i></button><a href="javascript:void(0);" title="Update Stock" data-attr-id="'+product_id+'" onclick="openStockModel('+product_id+');" class="btn btn-info"><i class="fa fa-refresh"></i></a></td>';
		/// waqar end
		html += '</tr>';
		
		$('#product').append(html);
	}
	
	var scrollIndex = data['index'] ? data['index'] : $('#product tr').length-1;
	// scroll to the updating / adding row
	var divHeight = $('#product').closest('div').height();
	var scrollTop = 0;
	for (var i = 0; i < scrollIndex; i++) {
		scrollTop += $('#product tr:eq('+i+')').height();
	}
	var scrollBottom = scrollTop + $('#product tr:eq('+scrollIndex+')').height();
	var curPosition = $('#product').closest('div').scrollTop();
	if (curPosition > scrollTop || curPosition + divHeight < scrollBottom) {
		$('#product').closest('div').scrollTop(scrollTop);
	}

	$('input[name=product_sn_id]').val('');
	$('input[name=product_sn]').val('');
	// add for serial no end
	$('input[name=\'product\']').val('');
	$('input[name=\'product_id\']').val('');
	$('input[name=\'product_image\']').val('');
	$('#option').empty();
	$('input[name=\'quantity\']').val('1');
	// add for SKU begin
	$('input[name=sku]').val('');
	// add for SKU end
	// add for UPC begin
	$('input[name=upc]').val('');
	// add for UPC end
	// add for Manufacturer Product begin
	$('input[name=manufacturer]').val('');
	$('input[name=manufacturer_id]').val('0');
	// add for Manufacturer Product end
	// add for Model begin
	$('input[name=model]').val('');
	// add for Model end
	// add for Weight based price begin
	$('input[name=weight]').val('1');
	$('#input_quantity').show();
	$('#input_weight').hide();
	// add for Weight based price end
	// add for Quick sale begin
	$('input[name=quick_sale_product_id]').val('0');
	$('input[name=quick_sale_name]').val('');
	$('input[name=quick_sale_model]').val('');
	$('input[name=quick_sale_price]').val('');
	$('input[name=quick_sale_quantity]').val('1');
	$('input[name=quick_sale_shipping]').prop('checked', false);
	$('input[name=quick_sale_include_tax]').prop('checked', false);
	// add for Quick sale end
	product_add_option = [];
		
	// send asynchronous request to get total and save order
	for (var i in add_option) {
		data[i] = add_option[i];
	}
	data['option'] = [];
	data['weight'] = weight;
	data['product_sn'] = product_sn;
	data['product_sn_id'] = product_sn_id;
	refreshTotal();
	checkAndSaveOrder(data);
};

function checkAndSaveOrder(data) {
	
	// for all actions, data will contain order_id and customer_group_id
	// action can be insert, insert_quick, delete, modify_quantity, modify_price
	// for insert, data contains the product id and options (product_option_id, production_option_value_id, value and type), quantity, product sn, product sn id
	// for insert_quick, data contains the product name, model, price, is_tax_included, quantity
	// for delete, data contains the order_product_id, product_id, quantity and options
	// for modify_quantity, data contains the order_product_id, before quantity and after quantity, product_id and options
	// for modify_price, data contains the order_product_id, before price and after price
	data['order_id'] = order_id;
	data['customer_id'] = $('input[name=customer_id]').val(); //customer_id;
	data['customer_group_id'] = $('select[name=customer_group_id]').val(); //customer_group_id;
	data['shipping_country_id'] = shipping_country_id;
	data['shipping_zone_id'] = shipping_zone_id;
	data['payment_country_id'] = payment_country_id;
	data['payment_zone_id'] = payment_zone_id;
	data['currency_code'] = currency_code;
	data['currency_value'] = currency_value;
	data['work_mode'] = text_work_mode;
	data['table_id'] = parseInt($('select[order_table_id]').val());
	//alert(data['coversion_id']);
	// update the browse table to change the product quantity
	if (data['subtract'] && parseInt(data['subtract']) > 0 && (data['action'] == 'insert' || data['action'] == 'modify_quantity' || data['action'] == 'delete')) {
		var qtyChange = 0;
		if (data['action'] == 'insert') {
			qtyChange = data['quantity'];
		} else if (data['action'] == 'modify_quantity') {
			qtyChange = data['quantity_after'] - data['quantity_before'];
		} else if (data['action'] == 'delete') {
			qtyChange = 0 - data['quantity'];
		}
		
		$('#browse_list a').each(function() {
			var onclick = 'selectProduct(' + data['product_id'] + ')';
			if ($(this).attr('onclick') == onclick) {
				var curStock = parseInt($(this).find('.product-count').text());
				$(this).find('.product-count').text(curStock-qtyChange);
			}
		});
	}
	$.ajax({
		url: 'index.php?route=module/pos/check_and_save_order&token=' + token,
		type: 'post',
		data: data,
		dataType: 'json',	
		beforeSend: function() {
			// removeMessage();
			// showMessage('notification', '');
		},
		cacheCallback: function(json) {
			// when quick sale, add the product to the product list
			if (data['action'] == 'insert_quick') {
				var product = {'name': data['name'], 'type':'2', 'image': no_image_url, 'price_text':json['text_price'], 'hasOptions':'0', 'weight_price':'0', 'has_sn':'0', 'price':json['price'], 'tax':(data['include_tax'] ? data['price']-json['price'] : 0), 'points':0, 'model':data['model'], 'parent_category_id':'-1', 'product_id':json['product_id']};
				backendSaveProduct(product);
			}
		},
		cachePreDone: function(cacheKey, callback) {
			// save order and calculate total locally
			backendCheckAndSaveOrder(data, callback);
		},
		success: function(json) {
			// Check for errors
			if (json['error']) {
				removeMessage();
				// Products
				if (json['error']['stock']) {
					showMessage('error', json['error']['stock']);
				}
			}
			deQueue();
			
			if (json['success']) {
				if (json['order_total']) {
					updateTotal(json['order_total']);
				} else {
					html  = '</tr>';
					html += '  <td colspan="5" class="center">' + text_no_results + '</td>';
					html += '</tr>';
					$('#total').html(html);					
				}
				calcDueAmount();
				
				if (data['action'] == 'insert' || data['action'] == 'insert_quick') {
					// get the generated order_product_id and assign it back to the order product on the page
					var new_row_index = $('#product tr').length-1;
					$('input[name=\'order_product[' + new_row_index + '][order_product_id]\']').val(json['order_product_id']);					
					if (data['action'] == 'insert_quick') {
						$('input[name=\'order_product[' + new_row_index + '][product_id]\']').val(json['product_id']);
						$("#order_product\\[" + new_row_index + "\\]\\[order_product_display_name\\]").attr('onclick', 'showProductDetails(' + json['product_id'] + ')');
					}
					if(json['converstion_line'] !=''){
						$("#convertion_line"+new_row_index).html(json['converstion_line']);
					}
					if (json['text_price'] && json['text_total']) {
						if ( parseFloat(json['discounted_price_for_percent']) < parseFloat(json['mainprice']) ) {
							var percent = (1 - json['discounted_price_for_percent'] / json['mainprice']) * 100;
							var discount_html1 = '<span id="discounted_price_for_percent_' + new_row_index +'"> ' + json['text_discounted_price_for_percent'] +'('+ Math.round(percent) +'% off!)</span>';
							var discount_html2 = '<span class="product-price" id="normal_total_text_only-' + new_row_index + '">Your price: ' +  json['text_total'] +'</span>';
							$( '#price_anchor_' + new_row_index ).text(json['price']+' / '+json['unit']['unit_singular']+ ' ('+ Math.round(percent) +'% off!)');
							//$( '#price_anchor_' + new_row_index ).parent().after( discount_html1 );
							//$( '#price_anchor_' + new_row_index ).wrap( "<strike></strike>" );
							$('.two .quantity_anchor_' + new_row_index ).text(json['quantity']+' '+json['unit']['unit_plural']);
							$('input[name=\'order_product[' + new_row_index + '][product_total_text]\']').val(json['text_total']);

							$( '#total_text_only-' + new_row_index ).parent().after(discount_html2);
						}
						else {
							$('.two .quantity_anchor_' + new_row_index ).text(json['quantity']);
						$('#price_anchor_' + new_row_index).text(json['discounted_price_for_percent']);
						$('input[name=\'order_product[' + new_row_index + '][product_total_text]\']').val(json['text_total']);
						$('#total_text_only-' + new_row_index).text(json['text_total']);
						}
						//$('input[name=\'order_product[' + new_row_index + '][product_normal_price]\']').val(json['text_price']);
						$('#total_text_only-' + new_row_index).text(json['text_product_actual_total']);
					}
				} else if (data['action'] == 'modify_price') {
					// update the price field
					$('input[name=\'order_product[' + data['index'] + '][price]\']').val(json['price']);
					var new_row_index = $('#product tr').length-1;
					$('input[name=\'order_product[' + new_row_index + '][product_total_text]\']').val('$' + json['product_total']);
					$('#total_text_only-' + new_row_index).text(json['text_product_actual_total']);
				} else if (data['action'] == 'modify_quantity') { 
					// update discount text as well
					if (json['discount'] && json['discount']['discount_type']) {
						var discount_text = '';
						if (json['discount']['discount_type'] == 1) {
							discount_text = formatMoney(json['discount']['discount_value']);
							$('input[name=discount_amount_value]').val(json['discount']['discount_value']);
						} else if (json['discount']['discount_type'] == 2) {
							discount_text = json['discount']['discount_value'] + '%';
							$('input[name=discount_percentage_value]').val(json['discount']['discount_value']);
						}
						$('#product tr:eq('+data['index']+')').find("input[name$='[product_discount_value]']").val(json['discount']['discount_value']);
						
						var before_discount_text = formatMoney(json['discount']['total_text']);
						var product_discount_html = '';
						product_discount_html += '<strike>' + before_discount_text + '</strike><br />';
						product_discount_html += '<small>(' + text_discount + ': ' + discount_text + ')</small><br />';
						product_discount_html += formatMoney(json['discount']['discounted_total_text']);
						product_discount_html += '<input type="hidden" name="order_product[' + data['index'] + '][product_total_text]" value="' + before_discount_text + '" />';
						$('#product tr:eq('+data['index']+') td:nth-last-child(2) span').html(product_discount_html);
						// update the discount tab display as well
						calProductDiscount(data['index']);
					}

					$('#total_text_only-' + data['index']).text(json['text_product_actual_total']);

					$('.two .quantity_anchor_' + data['index']).text(json['quantity_after']+' '+$('.quantity_anchor_'+data['index']).attr('data-unit'));
					$('.cart-round-qty.quantity_anchor_' + data['index']).text(json['quantity_after']);
					
					if ( parseFloat(json['discounted_price_for_percent']) < parseFloat(json['mainprice']) ) {
						var percent = (1 - json['discounted_price_for_percent'] / json['mainprice']) * 100;
						var discount_percent_html = json['text_discounted_price_for_percent'] +'('+ Math.round(percent) +'% off!)';
						$('#discounted_price_for_percent_'+ data['index']).text(discount_percent_html);
					}
					$('#convertion_line'+ data['index']).html(json['converstion_line']);	
				}
				if (json['extra_info']) {
					// action insert or modify_quantity may have extra_info returned
					$('#product tr').each(function () {
						for (var json_order_product_id in json['extra_info']) {
							if ($(this).find('input[name$=\'[order_product_id]\']').val() == json_order_product_id) {
								$(this).find('input[name$=\'[price]\']').val(json['extra_info'][json_order_product_id]['price']);
								//$(this).find('a[id^=price_anchor_]').text('@ ' + formatMoney(json['extra_info'][json_order_product_id]['price']));
								$(this).find('input[name$=\'[product_total_text]\']').val(formatMoney(json['extra_info'][json_order_product_id]['total']));
								//$(this).find('span[id^=total_text_only-]').text(formatMoney(json['extra_info'][json_order_product_id]['total']));
								var strike_through = "<strike>" + $(this).find('span[id^=total_text_only-]').text() + "</strike>";
								$(this).find('span[id^=normal_total_text_only-]').text("Your price: " + formatMoney(json['extra_info'][json_order_product_id]['total']));
								if ( $(this).find('span[id^=normal_total_text_only-]').length )
								{
									$(this).find('span[id^=total_text_only-]').html(strike_through);
								}
							}
						}
					});
				}
				
				if (json['enable_openbay'] && json['enable_openbay'] == '1') {
					// save the product page
					url = 'index.php?route=catalog/product/update&token=' + token + '&product_id='+json['product_id'];
					$.ajax({
						url: url,
						type: 'get',
						success: function(html) {
							$('#hidden_div').html($(html).find('div[id=\'content\']').html());
							var product_change_url = $('#hidden_div').find('form[id=\'form\']').attr('action');
							var method = $('#hidden_div').find('form[id=\'form\']').attr('method');
							var product_change_data = '#hidden_div input[type=\'text\'], #hidden_div input[type=\'hidden\'], #hidden_div input[type=\'password\'], #hidden_div input[type=\'radio\']:checked, #hidden_div input[type=\'checkbox\']:checked, #hidden_div select, #hidden_div textarea';
							$.ajax({
								url: product_change_url,
								type: method,
								data: $(product_change_data),
								dataType: 'json',
								converters: {
									'text json': true
								},
								success: function(html) {
									if (!json['error']) {
										removeMessage();
										showMessage('success', json['success']);
									}
								}
							});
						}
					});
				} else {
					if (!json['error']) {
						removeMessage();
						showMessage('success', json['success']);
					}
				}

				$("#payment_status").html(json['payment_status']);
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			openAlert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});	
}

function toggleFullScreen() {
	$('#header').toggle();
	$('.breadcrumb').toggle();
	$('#footer').toggle();
	$('#column-left').toggle();
	full_screen_mode = 1 - full_screen_mode;
	if (full_screen_mode) {
		$('.menu-toggle').css('top', '5px');
	} else {
		$('.menu-toggle').css('top', '50px');
	}
};

$(document).on('keydown', 'input[name=quantity]', function(event) {
	amountInputOnly(event);
});

$(document).on('keydown', '#tendered_amount', function(event) {
	amountInputOnly(event);
});

function amountInputOnly(event) {
	// Allow: backspace, delete, tab, escape, and enter
	if ( event.keyCode == 46 || event.keyCode == 110 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || event.keyCode == 190 ||
		 // Allow: Ctrl+A
		(event.keyCode == 65 && event.ctrlKey === true) ||
		 // Allow: home, end, left, right
		(event.keyCode >= 35 && event.keyCode <= 39)) {
		// let it happen, don't do anything
		return;
	} else {
		// Ensure that it is a number and stop the keypress
		if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
			event.preventDefault(); 
		}
	}
};

function calcDueAmount() {
	// count the total quantity
	var totalQuantity = 0;
	for (i = 0; i < $('#product tr').length; i++) {
		totalQuantity += parseInt($('#product tr:eq('+i+') input[name$="[quantity]"]').val());
	}
	$('#items_in_cart').text(totalQuantity);
	
	var totalText = $('#payment_total').text();
	totalAmount = posParseFloat(totalText) / parseFloat((typeof currency_value == 'undefined') ? 1 : currency_value);

	var totalPaid = 0;
	$('#payment_list tr:gt(0)').each(function() {
		// ignore the first line
		var rowAmount = $(this).find('td').eq(1).text();
		rowAmount = posParseFloat(rowAmount);
		totalPaid += rowAmount;
	});
	totalDue = totalAmount - totalPaid;
	
	if (totalDue < 0) {
		$('#payment_change').find('span').text(formatMoney(0-totalDue));
		// update dialog change amount
		//$('#dialog_change_text').text(formatMoney(0-totalDue));
		
		$('#payment_due_amount').text(formatMoney(0));
		$('#payment_due_amount_res').text(formatMoney(0));
		// update dialog due amount
		$('#dialog_due_amount_text').text(formatMoney(0));
		$('#tendered_amount').val('0');
		$('.shippingTr').hide();
		$('.SubtotalTr').hide();
	} else {
		$('#payment_due_amount').text(formatMoney(totalDue));
		$('#payment_due_amount_res').text(formatMoney(totalDue));
		// update dialog due amount
		$('#dialog_due_amount_text').text(formatMoney(totalDue));
		
		$('#payment_change').find('span').text(formatMoney(0));
		// update dialog change amount
		//$('#dialog_change_text').text(formatMoney(0));
		$('#tendered_amount').val(posParseFloat(formatMoney(totalDue)));
	}
	
	if (text_work_mode == '1') {
		$('#dialog_due_amount_text').text($('#dialog_due_amount_text').text() + ' CR');
		$('#payment_due_amount').text($('#payment_due_amount').text() + ' CR');
	}

	if (totalDue < 0.01) {
		// change color to green
		$('#payment_due_amount').css("color", "green");
		$('#payment_due_amount_res').css("color", "green");
	} else {
		// change color to red
		$('#payment_due_amount').css("color", "#bc4c3c");
		$('#payment_due_amount_res').css("color", "#bc4c3c");
	}
	return totalDue;
};

function country(element, index, zone_id) {
  if (element.value != '') {
		var country_id = element.value;
		if (saved_zones[country_id]) {
			html = '<option value="">' + text_select + '</option>';
			
			if (saved_zones[country_id].length > 0) {
				for (i = 0; i < saved_zones[country_id].length; i++) {
					html += '<option value="' + saved_zones[country_id][i]['zone_id'] + '"';
					
					if (saved_zones[country_id][i]['zone_id'] == zone_id) {
						html += ' selected="selected"';
					}
	
					html += '>' + saved_zones[country_id][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0">' + text_none + '</option>';
			}
			
			$('select[name=\'customer_addresses[' + index + '][zone_id]\']').html(html);
		} else {
			$.ajax({
				url: 'index.php?route=module/pos/country&token=' + token + '&country_id=' + country_id,
				dataType: 'json',
				beforeSend: function() {
					$('select[name=\'customer_addresses[' + index + '][country_id]\']').after('<span class="wait"><i class="fa fa-spinner fa-spin"></i></span>');
				},
				complete: function() {
					$('.wait').remove();
				},
				cacheCallback: function(json) {
					// leave the save to the success method
				},
				cachePreDone: function(cacheKey, callback) {
					if (saved_zones[country_id]) {
						callback({'zone':saved_zones[country_id]});
					} else {
						callback({'zone':[]});
					}
				},
				success: function(json) {
					saved_zones[country_id] = json['zone'];
					html = '<option value="">' + text_select + '</option>';
					
					if (json['zone'] != '') {
						for (i = 0; i < json['zone'].length; i++) {
							html += '<option value="' + json['zone'][i]['zone_id'] + '"';
							
							if (json['zone'][i]['zone_id'] == zone_id) {
								html += ' selected="selected"';
							}
			
							html += '>' + json['zone'][i]['name'] + '</option>';
						}
					} else {
						html += '<option value="0">' + text_none + '</option>';
					}
					
					$('select[name=\'customer_addresses[' + index + '][zone_id]\']').html(html);
				},
				error: function(xhr, ajaxOptions, thrownError) {
					openAlert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}
};

function updateClock() {
	var currentTime = new Date ( );

	var currentHours = currentTime.getHours();
	var currentMinutes = currentTime.getMinutes();
	currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
	var timeOfDay = ( currentHours < 12 ) ? "am" : "pm";
	currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;
	currentHours = ( currentHours == 0 ) ? 12 : currentHours;
	var currentDate = currentTime.getDate();
	currentDate = ( currentDate < 10 ? "0" : "" ) + currentDate;
	var currentMonth = currentTime.getMonth();
	var month_name = text_monthes[currentMonth];
	var currentYear = currentTime.getFullYear();
	var currentDay = currentTime.getDay();
	var week_day_name = text_weeks[currentDay];
	
	$('#header_year').text(currentYear);
	$('#header_month').text(month_name);
	$('#header_date').text(currentDate);
	$('#header_week').text(week_day_name);
	$('#header_hour').text(currentHours);
	$('#header_minute').text(currentMinutes);
	$('#header_apm').text(timeOfDay);
};

var resizeTimer;
$(window).resize(function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(CheckSizeZoom, 100);
});

function CheckSizeZoom() {
	if ($(window).width() > 1024) {
		/*
		var zoomLev = $(window).width() / 1080;
		if (720 * zoomLev > $(window).height() && $(window).height() / 720 > 1) {
			zoomLev = $(window).height() / 720;
		}
		
		if ($(window).width() > 1024 && $(window).height() > 680) {
			if (typeof (document.body.style.zoom) != "undefined" && !$.browser.msie) {
				$(document.body).css('zoom', zoomLev);
			}
		}
		*/
		
		$('#divWrap').css('margin', '0 auto');
	} else {
		$(document.body).css('zoom', '');
		$('#divWrap').css('margin', '');
	}
};

/* function window_print_url(msg, url, data, fn, para) {
	// get the page from url and print it
	if (data['change']) {
		// get the change if there is any
	   // var change = $('#payment_change').find('span').text();

        var change =$('#dialog_change_text').text();
		change = posParseFloat(change);
		if (change < 0.01) {
			data['change'] = formatMoney(0);
		} else {
			data['change'] = formatMoney(change);
		}
	}
	
	var type = '';
	if (url.indexOf('receipt') > 0) {
		type = 'receipt';
		if (text_work_mode == '0' && config['gift_receipt_status_id'] && order_status_id == parseInt(config['gift_receipt_status_id'])) {
			type += '_gift';
		} else if (text_work_mode == '1') {
			type += '_return';
		}
	} else if (url.indexOf('invoice') > 0) {
		type = 'invoice';
	} else if (url.indexOf('cc_sign') > 0) {
		type = 'cc_sign';
	}
		
	$.ajax({
		url: url,
		type: 'post',
		data: data,
		dataType: 'json',
		beforeSend: function() {
			openWaitDialog(msg);
		},
		converters: {
			'text json': true
		},
		cacheKey: CACHE_PRINT + type,
		cacheCallback: function(html) {
			// save the page to local repository
			backendSaveURL(type, html);
		},
		cachePreDone: function(cacheKey, callback) {
			backendPrintURL(type, data, cacheKey, callback);
		},
		complete: function() {
			closeWaitDialog();
		},
		success: function(html) {
			// replace media="screen" to media="print" to make sure we have the same style for printing
			html = html.replace('media="screen"', 'media="print"');
			// send html to iframe for printing
			$('#print_iframe').contents().find('html').html(html);

			setTimeout(function() {
				$("#print_iframe").contents().find("div[id^='barcode_']").each(function() {
					var value = $(this).text();
					$(this).text('');
					if ($(this).attr('id').substring(0, 11) == 'barcode_13_') {
						var ean13Value = value;
						if (value.length != 13) {
							for (var i = 0; i < 13 - value.length; i++) {
								ean13Value = '0' + ean13Value;
							}
						}
						$(this).barcode(ean13Value, 'ean13', {barHeight: 20});
					} else {
						$(this).barcode(value, 'codabar', {barHeight: 20});
					}
				});
				
				// append the print script
				if (navigator.appName == 'Microsoft Internet Explorer') {
					$("#print_iframe").get(0).contentWindow.document.execCommand('print', false, null);
				} else {
					w=window.open();
					w.document.write($("#print_iframe").contents().find('html').html());
					w.print();
					w.close();
					//$("#print_iframe").get(0).contentWindow.print();
				}
				// call the function to continue
				if (fn) {
					fn(para);
				}
			}, 1000);
		}
	});
}; */

function window_print_url(msg, url, data, fn, para) {
	// get the page from url and print it
	if (data['change']) {
		// get the change if there is any
		var change = $('#payment_change').find('span').text();
		change = posParseFloat(change);
		if (change < 0.01) {
			data['change'] = formatMoney(0);
		} else {
			data['change'] = formatMoney(change);
		}
	}
	
	var type = '';
	if (url.indexOf('receipt') > 0) {
		type = 'receipt';
		if (text_work_mode == '0' && config['gift_receipt_status_id'] && order_status_id == parseInt(config['gift_receipt_status_id'])) {
			type += '_gift';
		} else if (text_work_mode == '1') {
			type += '_return';
		}
	} else if (url.indexOf('invoice') > 0) {
		type = 'invoice';
	} else if (url.indexOf('cc_sign') > 0) {
		type = 'cc_sign';
	}
		
	$.ajax({
		url: url,
		type: 'post',
		data: data,
		dataType: 'json',
		beforeSend: function() {
			openWaitDialog(msg);
		},
		converters: {
			'text json': true
		},
		cacheKey: CACHE_PRINT + type,
		cacheCallback: function(html) {
			// save the page to local repository
			backendSaveURL(type, html);
		},
		cachePreDone: function(cacheKey, callback) {
			backendPrintURL(type, data, cacheKey, callback);
		},
		complete: function() {
			closeWaitDialog();
		},
		success: function(html) {
			// replace media="screen" to media="print" to make sure we have the same style for printing
			html = html.replace('media="screen"', 'media="print"');
			// send html to iframe for printing
			$('#print_iframe').contents().find('html').html(html);
			try{
				html = minifyHtml(html);
				window.webkit.messageHandlers.iosPrintHandler.postMessage(html);
			} catch(err){
				console.log('Can not reach native print code');
			}
			
			setTimeout(function() {
				$("#print_iframe").contents().find("div[id^='barcode_']").each(function() {
					var value = $(this).text();
					$(this).text('');
					if ($(this).attr('id').substring(0, 11) == 'barcode_13_') {
						var ean13Value = value;
						if (value.length != 13) {
							for (var i = 0; i < 13 - value.length; i++) {
								ean13Value = '0' + ean13Value;
							}
						}
						$(this).barcode(ean13Value, 'ean13', {barHeight: 20});
					} else {
						$(this).barcode(value, 'codabar', {barHeight: 20});
					}
				});
				
				// append the print script
				if (navigator.appName == 'Microsoft Internet Explorer') {
					$("#print_iframe").get(0).contentWindow.document.execCommand('print', false, null);
				} else {
					setTimeout(function() {
						$("#print_iframe").get(0).contentWindow.print();
					}, 2000);
				}
				// call the function to continue
				if (fn) {
					fn(para);
				}
			}, 1000);
		}
	});
};

function minifyHtml(html){
	var result;
	result = html
	.replace(/\<\!--\s*?[^\s?\[][\s\S]*?--\>/g,'')
	.replace(/\>\s*\</g,'><')
	.replace(/\/\*.*\*\/|\/\*[\s\S]*?\*\/|\n|\t|\v|\s{2,}/g,'')
    .replace(/\s*\{\s*/g,'{')
    .replace(/\s*\}\s*/g,'}')
    .replace(/\s*\:\s*/g,':')
    .replace(/\s*\;\s*/g,';')
    .replace(/\s*\,\s*/g,',')
    .replace(/\s*\~\s*/g,'~')
    .replace(/\s*\>\s*/g,'>')
    .replace(/\s*\+\s*/g,'+')
    .replace(/\s*\!\s*/g,'!');
	return result;
}

// add for Discount begin
$(document).on('click', '#button_discount_apply', function() {
	applyDiscount();
});

$(document).on('click', '#apply_to_this_order', function() {
	applyDiscount();
});

$(document).on('click', '#apply_to_future_orders', function() {
	var discount_customer_group_id = $('input[name=discount_customer_group_id]:checked').val();
	var current_customer_id = $('input[name=customer_id]').val();
	$('select[name=customer_group_id]').val(discount_customer_group_id);
	var data = {'customer_group_id': discount_customer_group_id, 'customer_id': current_customer_id};
	$.ajax({
		url: 'index.php?route=module/pos/updateCustomerGroup&token=' + token, 
		type: 'post',
		data: data,
		dataType: 'json',
		success: function(json) {
			applyDiscount();
		}
	});
	
});
// add for product based discount begin
function calProductDiscount(index) {
	var changed_price = $('input[name=changed_price]').val();
	changed_price = Math.round(changed_price * 100) / 100;
	$('input[name=changed_price]').val(changed_price);
	var f_product_total = posParseFloat($('#product tr:eq('+index+') input[name$=\'[product_normal_price]\']').val());
	var quantity = parseFloat($('input[name="order_product[' + index + '][quantity]"]').val());
	if ($('input[name=use_discount]:checked').val() == 'use_discount') {
		f_product_total = f_product_total * quantity;
	}
	if (f_product_total == 0) return;
	
	if ($('input[name=use_discount_type][value=fixed]').is(':checked')) {
		var discount_amount = f_product_total - parseFloat($('input[name=changed_price]').val());
		$('input[name=changed_price_discount_fixed]').val(discount_amount.toFixed(2));
	} else {
		var discount_amount = parseFloat($('input[name=changed_price_discount_fixed]').val());
	}
	
	if (isNaN(discount_amount)) {
		discount_amount = 0;
		$('input[name=changed_price_discount_fixed]').val(discount_amount);
	} else if (discount_amount < 0) {
		discount_amount = 0-discount_amount;
		$('input[name=changed_price_discount_fixed]').val(discount_amount);
	}
	
	var discount_percentage = parseFloat($('input[name=changed_price_discount_percentage]').val());
	if (isNaN(discount_percentage)) {
		discount_percentage = 0;
		$('input[name=changed_price_discount_percentage]').val(discount_percentage);
	}
	
	var discounted = 0;
	if ($('input[name=use_discount_type]:checked').val() == 'fixed') {
		discounted = discount_amount;
		if (discounted > f_product_total) {
			discounted = f_product_total;
			$('input[name=changed_price_discount_fixed]').val(discounted);
		}
		
		discount_percentage = toFixed(discounted * 100 / f_product_total, 2);
		$('input[name=changed_price_discount_percentage]').val(discount_percentage);
	} else if ($('input[name=use_discount_type]:checked').val() == 'percentage') {
		if (discount_percentage > 100) {
			discount_percentage = 100;
			$('input[name=changed_price_discount_percentage]').val(discount_percentage);
		}
		discounted = discount_percentage * f_product_total / 100;
		$('input[name=changed_price_discount_fixed]').val(toFixed(discounted, 2));
	}
	discounted = toFixed(f_product_total-discounted, 2);
	if ($('input[name=use_discount_type][value=percentage]').is(':checked')) {
		$('input[name=changed_price]').val(discounted);
	}
};
// add for product based discount end


// add for Authorize.Net CIM begin, add for Purchase Order Payment begin, add for Cash type begin, add for Credit Card begin
$(document).on('change', '#payment_type', function() {
	// add for Purchase Order Payment begin
	if ($('#payment_type').val() == 'purchase_order') {
		$('#payment_note_text').text(text_purchase_order_number);
	// add for Gift Voucher payment begin
	} else if ($('#payment_type').val() == 'gift_voucher') {
		$('#payment_note_text').text(text_gift_voucher_code);
		$('#tendered_amount').prop('disabled', true);
	// add for Gift Voucher payment end
	} else {
		$('#payment_note_text').text(column_payment_note);
	}
	// add for Purchase Order Payment end
	
	// add for Cash type begin
	if ($('#payment_type').val() == 'cash') {
		$('#cash_type_list').show();
	} else {
		$('#cash_type_list').hide();
	}
	useCashType = false;
	// add for Cash type end
	
	// add for customer loyalty card begin
	if ($('#payment_type').val() == 'reward_points') {
		if (parseInt($('input[name=customer_id]').val()) > 0) {
			$('#payment_list tr').each(function() {
				if ($(this).find('td').eq(0).text() == text_reward_points) {
					// already have reward points payment, cannot continue
					openAlert(text_reward_points_payment_exist);
					return;
				}
			});

			handleRewardPointsPayment();
			$('#reward_points_payment').show();
		}
	} else {
		$('#reward_points_payment').hide();
	}
	// add for customer loyalty card end
	
	// in all other cases, show the keypad
	if ($('#payment_type').val() != 'cash' && $('#payment_type').val() != 'reward_points' && ($('#payment_type').val() != 'credit_card' || ($('#payment_type').val() == 'credit_card' && !(config['enable_ccx'] && config['enable_ccx'] == '1')))) {
		$('.payment_other').show();
	} else {
		$('.payment_other').hide();
	}
	resizeFancybox();
	
	calcDueAmount();
});
// add for Authorize.Net CIM end, add for Purchase Order Payment end, add for Cash type end, add for Credit card end
// add for edit order address begin
function order_country(element, address_type, zone_id) {
  if (element.value != '') {
		$.ajax({
			url: 'index.php?route=module/pos/country&token=' + token + '&country_id=' + element.value,
			dataType: 'json',
			beforeSend: function() {
				$(element).after('<span class="wait"><i class="fa fa-spinner fa-spin"></i></span>');
			},
			complete: function() {
				$('.wait').remove();
			},
			cacheCallback: function(json) {
				// leave the save to the success method
			},
			cachePreDone: function(cacheKey, callback) {
				if (saved_zones[element.value]) {
					callback({'zone':saved_zones[element.value]});
				} else {
					callback({'zone':[]});
				}
			},
			success: function(json) {
				saved_zones[element.value] = json['zone'];
				if (json['postcode_required'] == '1') {
					$('#' + address_type + '-postcode-required').show();
				} else {
					$('#' + address_type + '-postcode-required').hide();
				}
				
				html = '<option value="">' + text_select + '</option>';
				
				if (json['zone'] && json['zone'].constructor == Array) {
					for (i = 0; i < json['zone'].length; i++) {
						html += '<option value="' + json['zone'][i]['zone_id'] + '"';
						
						if (json['zone'][i]['zone_id'] == zone_id) {
							html += ' selected="selected"';
						}
		
						html += '>' + json['zone'][i]['name'] + '</option>';
					}
				} else {
					html += '<option value="0">' + text_none + '</option>';
				}
				
				$('select[name=\'' + address_type + '_zone_id\']').html(html);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				openAlert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
};

// add for Quotation begin
function modeOrder() {
	text_work_mode = '0';
	$('#work_mode_dropdown').val('sale').selectmenu('refresh');
	refreshPage('order');
	showCategoryItems(0);
};

function modeReturn(returnMode) {
	// set the return mode
	text_work_mode = '1';
	removeMessage();
	showMessage('success', text_return_ready);
	// go to order list to select an order for return if return button is clicked
	if (returnMode == 1) {
		getOrderList();
	} else {
		refreshPage('return');
	}
};

function modeQuote() {
	text_work_mode = '2';
	refreshPage('quote');
	showCategoryItems(0);
};

// add for Cash type begin
function selectCashType(cashValue) {
	var value = Number($('input[name=tendered_amount]').val());
	if (value >= 0) {
		if (!useCashType) {
			// begin to use cash type
			value = parseFloat(cashValue);
			useCashType = true;
		} else {
			value += parseFloat(cashValue);
		}
	}
	$('input[name=tendered_amount]').val(toFixed(value, 2));
};
// add for Cash type end

// add for till control begin
function sendControlKey() {
	var till_control_key = config['till_control_key'];
	if (!till_control_key) {
		var applet = document.jzebra;
		if (applet) {
			applet.append(till_control_key);
			applet.print();
		}
	}
};
// add for till control end
// add for serial no begin
var matchedSN = 0;
$(document).on('focus', 'input[name=\'product_sn\']', function(){
	$(this).autocomplete({
		delay: 500,
		source: function(request, response) {
			var product_id = $('input[name=\'product_id\']').val();
			if (parseInt(product_id) > 0) {
				$.ajax({
					url: 'index.php?route=module/pos/sn_autocomplete&token=' + token + '&filter_sn=' +  encodeURIComponent(request.term) + '&filter_product_id=' + product_id,
					dataType: 'json',
					cacheCallback: function(json) {
						// do not save the sn info as the info is already in the product, and also because the info returned here is incomplete
					},
					cachePreDone: function(cacheKey, callback) {
						backendGetProductSNs(product_id, request.term, callback);
					},
					success: function(json) {		
						if (json.length > 0 || (config['enable_non_predefined_sn'] && parseInt(config['enable_non_predefined_sn']) > 0)) {
							matchedSN = 1;
						} else {
							matchedSN = 0;
						}
						
						response($.map(json, function(item) {
							return {
								label: item.name,
								value: item.product_sn_id
							}
						}));
					}
				});
			}
		}, 
		select: function(event, ui) {
			$('input[name=\'product_sn\']').val(ui.item.label);
			$('input[name=\'product_sn_id\']').val(ui.item.value);

			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
});
// add for serial no end
// add for customer loyalty card begin
var rewardPointsBalance = 0;
function handleRewardPointsPayment() {
	// if reward points to cash conversion, the values should be set properly
	if (typeof reward_points_usage != 'undefined' && reward_points_usage == '2' && (typeof reward_points_value == 'undefined' || !parseInt(reward_points_value))) {
		openAlert(text_points_to_cash_ratio_required);
		return;
	}
	
	// get the customer reward points (exclude this order)
	$.ajax({
		url: 'index.php?route=module/pos/check_reward_points&token=' + token + '&order_id=' + order_id,
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			openWaitDialog();
		},
		complete: function() {
			closeWaitDialog();
		},
		success: function(json) {
			removeMessage();
			$('#reward_points_balance').html(text_reward_points_balance + '<span>' + json['reward_points_balance'] + '</span>');
			rewardPointsBalance = parseInt(json['reward_points_balance']);
			if (reward_points_usage == '1') {
				// opencart reward points
				$('#reward_points_payment_list').empty();
				var reward_list_html = '';
				var trClass = 'even';
				for (var i = 0; i < json['reward_products'].length; i++) {
					trClass = (trClass == 'even') ? 'odd' : 'even';
					var product = json['reward_products'][i];
					reward_list_html += '<tr class="' + trClass + '"><td>' + product['name'] + '</td>';
					reward_list_html += '<td>' + product['points'] + '</td>';
					reward_list_html += '<td>';
					if (parseInt(product['quantity']) > 1) {
						reward_list_html += '<select onchange="updateRewardQuantity(this);">';
						for (var j = 1; j <= parseInt(product['quantity']); j++) {
							reward_list_html += '<option value="' + j + '"' + ((j == parseInt(product['quantity'])) ? ' selected=selected' : '') + '>' + j + '</option>';
						}
						reward_list_html += '</select>';
					} else {
						reward_list_html += product['quantity'];
					}
					reward_list_html += '</td>';
					reward_list_html += '<td><input type="checkbox" name="use_reward_points_' + product['order_product_id'] + '" value="' + product['quantity'] + '" onclick="changePaidPoints(this);" /><span>' + (parseInt(product['points']) * parseInt(product['quantity'])) + '</span>&nbsp' + text_reward_points + '</td></tr>';
				}
				if (!reward_list_html) {
					reward_list_html = '<tr><td colspan="4" text-align="center">' + text_no_product_for_points + '</td></tr>';
				}
				$('#reward_points_payment_list').html(reward_list_html);
				$('#reward_points_payment_header').show();
			} else if (reward_points_usage == '2') {
				// use reward points as cash
				// find out how many points can be used
				var maxPointsToUse = rewardPointsBalance;
				var amountDue = parseFloat($('input[name=tendered_amount]').val());
				if (rewardPointsBalance > amountDue * parseInt(reward_points_value)) {
					maxPointsToUse = parseInt(amountDue * parseInt(reward_points_value));
				}
				$('#reward_points_payment_list').empty();
				var reward_list_html = '<tr><td colspan="2">' + text_use_how_many_points + '</td>';
				reward_list_html += '<td colspan="2"><input type="text" name="how_many_points" value="' + maxPointsToUse + '" onkeyup="changeUsedPoints(this);" />&nbsp( = <span>' + formatMoney(maxPointsToUse/parseInt(reward_points_value)) + '</span> )</td></tr>';
				$('#reward_points_payment_list').html(reward_list_html);
				$('#reward_points_payment_header').hide();
			}
		}
	});
};

function updateRewardQuantity(select) {
	var quantity = parseInt($(select).val());
	var totalPoints = quantity * parseInt($(select).closest('tr').find('td').eq(1).text());
	$(select).closest('tr').find('td').eq(3).find('input').val(quantity);
	$(select).closest('tr').find('td').eq(3).find('span').text(totalPoints);
};

function changePaidPoints(check) {
	var points = parseInt($(check).closest('td').find('span').text());
	if (!$(check).is(':checked')) {
		// uncheck the box and return points to the balance
		rewardPointsBalance += points;
	} else {
		// tick the box and deduct points from the balance
		if (points > rewardPointsBalance) {
			openAlert(text_not_enough_reward_points_balance);
			$(check).prop('checked', false);
		} else {
			rewardPointsBalance -= points;
		}
	}
	$('#reward_points_balance span').text(rewardPointsBalance);
};

function changeUsedPoints(input) {
	var points = parseInt($(input).val());
	var amountDue = parseFloat($('input[name=tendered_amount]').val());
	
	var maxPointsToUse = rewardPointsBalance;
	if (rewardPointsBalance > amountDue * parseInt(reward_points_value)) {
		maxPointsToUse = parseInt(amountDue * parseInt(reward_points_value));
	}

	if (points > maxPointsToUse) {
		points = maxPointsToUse;
		$(input).val(points);
	} else if (points < 1) {
		points = 1;
		$(input).val(points);
	}
	// recalculate the equal cash
	$(input).closest('td').find('span').text(formatMoney(points/parseInt(reward_points_value)));
};

function setCustomer(customerCardNumber) {
	var url = 'index.php?route=module/pos/setOrderCustomer&token=' + token + '&order_id=' + order_id;
	if (text_work_mode == '1') {
		url = 'index.php?route=module/pos/setOrderCustomer&token=' + token + '&pos_return_id=' + pos_return_id;
	}
	url += '&customer_card=' + customerCardNumber;
	$.ajax({
		url: url,
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			openWaitDialog(text_saving_customer);
		},
		complete: function() {
			closeWaitDialog();
		},
		success: function(json) {
			populateCustomerData(json);
		}
	});
};
// add for customer loyalty card end
// add for callin customer begin
function getCallinCustomer(phoneNumber) {
	var data = {'filter_telephone': phoneNumber};
	var url = 'index.php?route=module/pos/getCustomerList&token=' + token;
	$.ajax({
		url: url,
		type: 'post',
		dataType: 'json',
		data: data,
		cacheCallback: function() {
			// do not save the list as the list only contains the limited info of the customer, need to save customer once the customer is selected
		},
		cachePreDone: function(cacheKey, callback) {
			backendGetCustomerList(data, callback);
		},
		success: function(json) {
			var notification = text_customer_incoming;
			if (json['customers'] && json['customers'].length > 0) {
				var incomingCustomer = json['customers'][0];
				
				notification += ' <a class="btn btn-xs btn-success" onclick="createOrderForCustomer(' + incomingCustomer['customer_id'] + ')">' + incomingCustomer['name'] + ' (' + phoneNumber + ')</a>';
			} else {
				notification += ' <a class="btn btn-xs btn-success" onclick="createOrderForCustomer(0)">' + phoneNumber + '</a>';
			}
			showMessage('incoming-call', notification);
		}
	});
};
function createOrderForCustomer(customer_id) {
	$('.message').removeClass('incoming-call');
	if (customer_id > 0) {
		// if the current order is empty, assign the customer, otherwise, create an new order with the customer
		if ($('#product tr').length > 0) {
			refreshPage('order');
		}
		selectCustomer(null, customer_id);
	}
};
// add for callin customer end

$('.menu-toggle').on('click', function() {
	toggleFullScreen();
});

$("#receipt_email").on("click", function() {
    $(this).val("")
});

$(document).on("change", "#get-unit-data", function() {
        addUnit();
    });
	
$("#shipping").on("change", function() {
    if ( $(this).val() == 'free.free' )
	{
		$("#shipping_title").show();
		$("#shipping_cost").show();
	} else {
		$("#shipping_title").hide();
		$("#shipping_cost").hide();
	}
});

$( document ).ready(function() {
    if ( $("#shipping").val() == 'free.free' )
	{
		$("#shipping_title").show();
		$("#shipping_cost").show();
	} else {
		$("#shipping_title").hide();
		$("#shipping_cost").hide();
	}
});

function showProductDetailsCustom(product_id) {
	$.ajax({
		url: 'index.php?route=module/pos/getProductDetails&token=' + token + '&product_id='+product_id,
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			openWaitDialog(text_fetching_product_details);
		},
		complete: function() {
			closeWaitDialog();
		},
		cacheCallback: function(json) {
			// do not save the cache as the product info is already in the database
		},
		cachePreDone: function(cacheKey, callback) {
			backendGetProductDetails(product_id, callback);
		},
		success: function(json) {
			// display string attributes
			console.log(json);
			var dispay_attrs_string = ['name', 'sku', 'upc', 'model', 'cost', 'description', 'price', 'quantity', 'thumb', 'location','labour_cost'];
			var unique_price_discount = "unique_price_discount";
			for (i = 0; i < dispay_attrs_string.length; i++) {
				if(dispay_attrs_string[i] =="labour_cost"){
					var value = json[dispay_attrs_string[i]] ? parseFloat(json[dispay_attrs_string[i]]).toFixed(2)+" + "+parseFloat(json["unique_option_price"]).toFixed(6) : ''; 
				}else{
					var value = json[dispay_attrs_string[i]] ? json[dispay_attrs_string[i]] : '';
				}

				if ('thumb' == dispay_attrs_string[i]) {
					$('#product_details_thumb').attr('src', json['thumb']);
					$('#product_details_thumb').attr('alt', json['name']);
				} else {
					$('#product_details_' + dispay_attrs_string[i]).html($('<textarea />').html(value).text());
				}
			}
			
			// dispaly array attributes
			var html = ''
			if(json["product_attributes"] && json["product_attributes"].length > 0){
				var trClass = 'even';
				for (var i = 0; i < json['product_attributes'].length; i++) {
					trClass = (trClass == 'even') ? 'odd' : 'even';
					html += '<tr class="' + trClass + '">';
					html += '<td>' + json['product_attributes'][i]['name'] + '</td><td>' + json['product_attributes'][i]['value'] + '</td><td>' + ((json['product_attributes'][i]['required'] ==1) ? text_yes : text_no) + '</td></tr>';
				}	
			}

			$('#product_details_options').html(html);
			openFancyboxCustom('#product_details_dialog', 'wide', '', '', '', product_id);
		}
	});
}

function openFancyboxCustom(selector, type, content, onCloseFn, reOpenFn, productId) {
	var minWidth = 260, maxWidth = 1100;
	if (type == 'narrower') { minWidth = 220; maxWidth = 220; }
	else if (type == 'narrow') { maxWidth = 320; }
	else if (type == 'normal') { maxWidth = 450; }
	else if (type == 'wide') { maxWidth = 760; }
	else if (type == 'wider') { maxWidth = 1100; }
	 var helper='';
	if(selector == "#order_payments_dialog"){
		helper={
			overlay : {closeClick: false} // prevents closing when clicking OUTSIDE fancybox
		};
	}else{
		helper={
			overlay : {closeClick: true} // prevents closing when clicking OUTSIDE fancybox
		};
		/// Comment by waqar 
		$(document).mouseup(function (e)
		                    {
		  var container = $(".fancybox-wrap"); // YOUR CONTAINER SELECTOR
		  
		  if (!container.is(e.target) // if the target of the click isn't the container...
		      && container.has(e.target).length === 0) // ... nor a descendant of the container
		  {

		    container.hide();
		    $(".fancybox-overlay").hide();
		  }
		});
		/// Comment by waqar
	}


	var data = {
		padding : 0,
		margin  : 10,
		width	: '95%',
		height    : 'auto',
		minWidth  : minWidth,
		maxWidth  : maxWidth,
		minHeight  : '300px',
		autoSize	: false,
		fitToView	: true,
		closeClick : false,
		modal: true,
		openEffect: 'none',
		closeEffect: 'none',
		closeClick  : true, // prevents closing when clicking INSIDE fancybox
	    
	    afterShow : function() {
			$('.fancybox-skin').append('<a title="Close" class="fancybox-item fancybox-close" onclick="' + (reOpenFn ? reOpenFn : '$.fancybox.close') + '();"></a>');
			
		},
		beforeShow: function(){
			$(window).on({
				'resize.fancybox' : function(){
					$.fancybox.update();
				}
			});
		},
		afterClose: function(){
			selectProduct(productId);
			$(window).off('resize.fancybox');
		},
		
	};


	if (selector) {
		data['href'] = selector;
	} else {
		data['content'] = content;
	}
	if (onCloseFn) {
		data['afterClose'] = onCloseFn;
	}

	$.fancybox(data);
}