
<script type="text/javascript" src="view/javascript/jquery/jquery.tmpl.min.js"></script>
<script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap-multiselect.js"></script>
<script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap-select.min.js"></script>
<link href="view/javascript/bootstrap/css/bootstrap-multiselect.css" type="text/css" rel="stylesheet" />
<link href="view/javascript/bootstrap/css/bootstrap-select.css" type="text/css" rel="stylesheet" />
<style type="text/css">
.list_main_sale {
	border-collapse: collapse;
	width: 100%;
	border-top: 1px solid #DDDDDD;
	border-left: 1px solid #DDDDDD;	
	margin-bottom: 20px;
}
.list_main_sale td {
	border-right: 1px solid #DDDDDD;
	border-bottom: 1px solid #DDDDDD;	
}
.list_main_sale thead td {
	background-color: #f5f5f5;
	padding: 0px 5px;
}

.list_main_sale thead td a, .list_main_sale thead td {
	text-decoration: none;
	color: #222222;
	font-weight: bold;	
}
.list_main_sale tbody td {
	vertical-align: middle;
	padding: 0px 5px;
}
.list_main_sale .left {
	text-align: left;
	padding: 7px;
}
.list_main_sale .right {
	text-align: right;
	padding: 7px;
}
.list_main_sale .center {
	text-align: center;
	padding: 3px;
}
.list_main_sale a.asc:after {
	content: " \f107";
	font-family: FontAwesome;
	font-size: 14px;
}
.list_main_sale a.desc:after {
	content: " \f106";
	font-family: FontAwesome;
	font-size: 14px;
}
.list_main_sale .noresult {
	text-align: center;
	padding: 7px;
}

.btn-select {
	background-color: #fcfcfc;
	border: 1px solid #CCC;
}
.btn-group-ms {
	width: 100%;
	height: 35px;	
}
.btn-group-ms > .multiselect.btn {
	width: 100%;
	height: 35px;	
}
.multiselect ul {
	width: 100%;
	height: 35px;	
}
.sloading {
	position: fixed;
	left: 0px;
	top: 0px;
	width: 100%;
	height: 100%;
	z-index: 9999;
	background: url('view/image/adv_reports/page_loading.gif') 50% 50% no-repeat rgb(255,255,255);
}
</style> 
<input type="hidden" id="page_sale" value="<?php echo $page_sale ?>">
<input type="hidden" id="sort_sale" value="<?php echo $sort_sale ?>">
<input type="hidden" id="order_sale" value="<?php echo $order_sale ?>">
<script type="text/javascript">
$(document).ready(function() {
var $filter_sale_range = $('#filter_sale_range'), $date_start = $('#date-start-sale'), $date_end = $('#date-end-sale');
$filter_sale_range.change(function () {
    if ($filter_sale_range.val() == 'custom') {
        $date_start.removeAttr('disabled');
        $date_end.removeAttr('disabled');
    } else {	
        $date_start.prop('disabled', 'disabled').val('');
        $date_end.prop('disabled', 'disabled').val('');
    }
}).trigger('change');
});
</script>
<div class="well">
      <div class="row">
            <div class="pull-right">
            <button title="Back" class="btn btn-default" onclick="cancelSalesReport();"><i class="fa fa-reply"></i> Back</button>
            </div>
      </div>
    <div class="row">
      <div class="col-lg-6" style="padding-bottom:5px;">	  
        <div class="row">
          <div class="col-sm-6" style="padding-bottom:5px;">
		  <label class="control-label" for="filter_sale_range"><?php echo $entry_hrange; ?></label>
            <select name="filter_sale_range" id="filter_sale_range" data-style="btn-select" class="form-control select">
              <?php foreach ($ranges_sale as $range_sale) { ?>
              <?php if ($range_sale['value'] == $filter_sale_range) { ?>
              <option value="<?php echo $range_sale['value']; ?>" title="<?php echo $range_sale['text']; ?>" style="<?php echo $range_sale['style']; ?>" selected="selected"><?php echo $range_sale['text']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $range_sale['value']; ?>" title="<?php echo $range_sale['text']; ?>" style="<?php echo $range_sale['style']; ?>"><?php echo $range_sale['text']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></div>
          <div class="col-sm-3" style="padding-bottom:5px;">
		  <label class="control-label" for="date-start-sale"><?php echo $entry_hdate_start; ?></label>
            <input type="text" name="filter_sale_date_start" value="<?php echo $filter_sale_date_start; ?>" data-date-format="YYYY-MM-DD" id="date-start-sale" class="form-control" style="color:#F90;" />
		  </div>
          <div class="col-sm-3" style="padding-bottom:5px;">
		  <label class="control-label" for="date-end-sale"><?php echo $entry_hdate_end; ?></label>
            <input type="text" name="filter_sale_date_end" value="<?php echo $filter_sale_date_end; ?>" data-date-format="YYYY-MM-DD" id="date-end-sale" class="form-control" style="color:#F90;" />
          </div>
        </div>
	  </div>   
      <div class="col-lg-3" style="padding-bottom:5px;">
	  <label class="control-label" for="sale_order_status"><?php echo $entry_sstatus; ?></label>
            <select name="filter_sale_order_status" id="sale_order_status" class="form-control" multiple="multiple" size="1">		
            <?php foreach ($order_statuses as $order_status) { ?>
			<?php if (isset($filter_sale_order_status[$order_status['order_status_id']])) { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></div>
      <div class="col-lg-3" style="padding-bottom:5px;">
	  <label class="control-label" for="sale_option"><?php echo $entry_soption; ?></label>
            <select name="filter_sale_option" id="sale_option" class="form-control" multiple="multiple" size="1">
            <?php foreach ($order_options as $order_option) { ?>
			<?php if (isset($filter_sale_option[$order_option['options']])) { ?>
            <option value="<?php echo $order_option['options']; ?>" selected="selected"><?php echo $order_option['option_name']; ?>: <?php echo $order_option['option_value']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $order_option['options']; ?>"><?php echo $order_option['option_name']; ?>: <?php echo $order_option['option_value']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></div>		  
    </div> 
</div>	
<div class="table-responsive">
    <table class="list_main_sale">
      <thead id="head_sale">
        <tr>
          <td class="left"><?php if ($sort_sale == 'product_order_id') { ?>
                <a href="<?php echo $sort_sale_date_added; ?>" class="<?php echo strtolower($order_sale); ?>"><?php echo $column_prod_order_id; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_sale_date_added; ?>"><?php echo $column_prod_order_id; ?></a>
                <?php } ?></td>
          <td class="left"><?php if ($sort_sale == 'product_date_added') { ?>
                <a href="<?php echo $sort_sale_date_added; ?>" class="<?php echo strtolower($order_sale); ?>"><?php echo $column_prod_date_added; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_sale_date_added; ?>"><?php echo $column_prod_date_added; ?></a>
                <?php } ?></td>
          <td class="left" style="min-width:120px;"><?php if ($sort_sale == 'product_option') { ?>
                <a href="<?php echo $sort_sale_name; ?>" class="<?php echo strtolower($order_sale); ?>"><?php echo $column_prod_name; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_sale_name; ?>"><?php echo $column_prod_name; ?></a>
                <?php } ?></td> 
          <td class="right"><?php if ($sort_sale == 'product_sold') { ?>
                <a href="<?php echo $sort_sale_quantity; ?>" class="<?php echo strtolower($order_sale); ?>"><?php echo $column_prod_sold; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_sale_quantity; ?>"><?php echo $column_prod_sold; ?></a>
                <?php } ?></td>
          <td class="right" style="min-width:70px;"><?php if ($sort_sale == 'product_total_excl_vat') { ?>
                <a href="<?php echo $sort_sale_total_excl_tax; ?>" class="<?php echo strtolower($order_sale); ?>"><?php echo $column_prod_total_excl_vat; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_sale_total_excl_tax; ?>"><?php echo $column_prod_total_excl_vat; ?></a>
                <?php } ?></td>
          <td class="right"><?php if ($sort_sale == 'product_tax') { ?>
                <a href="<?php echo $sort_sale_tax; ?>" class="<?php echo strtolower($order_sale); ?>"><?php echo $column_prod_tax; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_sale_tax; ?>"><?php echo $column_prod_tax; ?></a>
                <?php } ?></td>
          <td class="right" style="min-width:70px;"><?php if ($sort_sale == 'product_total_incl_vat') { ?>
                <a href="<?php echo $sort_sale_total_incl_tax; ?>" class="<?php echo strtolower($order_sale); ?>"><?php echo $column_prod_total_incl_vat; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_sale_total_incl_tax; ?>"><?php echo $column_prod_total_incl_vat; ?></a>
                <?php } ?></td>	
          <td class="right"><?php if ($sort_sale == 'product_revenue') { ?>
                <a href="<?php echo $sort_sale_revenue; ?>" class="<?php echo strtolower($order_sale); ?>"><?php echo $column_prod_sales; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_sale_revenue; ?>"><?php echo $column_prod_sales; ?></a>
                <?php } ?></td>	
          <td class="right"><?php if ($sort_sale == 'product_cost') { ?>
                <a href="<?php echo $sort_sale_cost; ?>" class="<?php echo strtolower($order_sale); ?>"><?php echo $column_prod_cost; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_sale_cost; ?>"><?php echo $column_prod_cost; ?></a>
                <?php } ?></td>	
          <td class="right"><?php if ($sort_sale == 'product_profit') { ?>
                <a href="<?php echo $sort_sale_profit; ?>" class="<?php echo strtolower($order_sale); ?>"><?php echo $column_prod_profit; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_sale_profit; ?>"><?php echo $column_prod_profit; ?></a>
                <?php } ?></td>
          <td class="right" style="min-width:75px;"><?php if ($sort_sale == 'product_margin') { ?>
                <a href="<?php echo $sort_sale_profit_margin; ?>" class="<?php echo strtolower($order_sale); ?>"><?php echo $column_prod_margin; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_sale_profit_margin; ?>"><?php echo $column_prod_margin; ?></a>
                <?php } ?></td>
          <td class="right" style="min-width:75px;"><?php if ($sort_sale == 'product_markup') { ?>
                <a href="<?php echo $sort_sale_profit_markup; ?>" class="<?php echo strtolower($order_sale); ?>"><?php echo $column_prod_markup; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_sale_profit_markup; ?>"><?php echo $column_prod_markup; ?></a>
                <?php } ?></td>	
        </tr>
      </thead>
      <tbody>
        <?php if ($sales) { ?>
        <?php foreach ($sales as $sale) { ?>
        <tr>
          <td class="left"><?php echo $sale['product_order_id']; ?></td>
		  <td class="left"><?php echo $sale['product_date_added']; ?></td>
          <td class="left"><?php echo $sale['product_name']; ?> 
		  <?php if ($sale['product_option']) { ?>
          <table cellpadding="0" cellspacing="0" border="0" style="border:none;">
          <tr>
		  <td nowrap="nowrap" style="font-size:11px; border:0;"><?php echo $sale['product_option']; ?><?php echo $sale['product_order_product_id']; ?></td>
          </tr>
          </table>
		  <?php } ?>
		  </td>
          <td class="right"><?php echo $sale['product_sold']; ?></td>
		  <td class="right"><?php echo $sale['product_total_excl_vat']; ?></td>
		  <td class="right"><?php echo $sale['product_tax']; ?></td>
		  <td class="right"><?php echo $sale['product_total_incl_vat']; ?></td>
		  <td class="right" style="background-color:#DCFFB9;"><?php echo $sale['product_revenue']; ?></td>
		  <td class="right" style="background-color:#ffd7d7;"><?php echo $sale['product_cost']; ?></td>
		  <?php if ($sale['product_profit_raw'] >= 0) { ?>
		  <td class="right" style="background-color:#c4d9ee; font-weight:bold;"><?php echo $sale['product_profit']; ?></td>
		  <td class="right" style="background-color:#c4d9ee; font-weight:bold;"><?php echo $sale['product_profit_margin']; ?></td>
		  <td class="right" style="background-color:#c4d9ee; font-weight:bold;"><?php echo $sale['product_profit_markup']; ?></td>
		  <?php } else { ?>
		  <td class="right" style="background-color:#F99; font-weight:bold;"><?php echo $sale['product_profit']; ?></td>
		  <td class="right" style="background-color:#F99; font-weight:bold;"><?php echo $sale['product_profit_margin']; ?></td>
		  <td class="right" style="background-color:#F99; font-weight:bold;"><?php echo $sale['product_profit_markup']; ?></td>
		  <?php } ?>
        </tr>	
        <?php } ?>
		<tr style="border-top:2px solid #CCC;">
		  <td colspan="3" class="right" style="background-color:#E5E5E5; font-weight:bold;"><?php echo $column_prod_totals; ?></td>
          <td class="right" style="background-color:#E5E5E5; color:#003A88; font-weight:bold;"><?php echo $sale['product_sold_total']; ?></td>
		  <td class="right" style="background-color:#E5E5E5; color:#003A88; font-weight:bold;"><?php echo $sale['product_total_excl_vat_total']; ?></td>
		  <td class="right" style="background-color:#E5E5E5; color:#003A88; font-weight:bold;"><?php echo $sale['product_tax_total']; ?></td>
		  <td class="right" style="background-color:#E5E5E5; color:#003A88; font-weight:bold;"><?php echo $sale['product_total_incl_vat_total']; ?></td>
		  <td class="right" style="background-color:#DCFFB9; color:#003A88; font-weight:bold;"><?php echo $sale['product_revenue_total']; ?></td>
		  <td class="right" style="background-color:#ffd7d7; color:#003A88; font-weight:bold;"><?php echo $sale['product_cost_total']; ?></td>
		  <?php if ($sale['product_profit_raw_total'] >= 0) { ?>
		  <td class="right" style="background-color:#c4d9ee; color:#003A88; font-weight:bold;"><?php echo $sale['product_profit_total']; ?></td>
		  <td class="right" style="background-color:#c4d9ee; color:#003A88; font-weight:bold;"><?php echo $sale['product_profit_margin_total']; ?></td>
		  <td class="right" style="background-color:#c4d9ee; color:#003A88; font-weight:bold;"><?php echo $sale['product_profit_markup_total']; ?></td>
		  <?php } else { ?>
		  <td class="right" style="background-color:#F99; color:#003A88; font-weight:bold;"><?php echo $sale['product_profit_total']; ?></td>
		  <td class="right" style="background-color:#F99; color:#003A88; font-weight:bold;"><?php echo $sale['product_profit_margin_total']; ?></td>
		  <td class="right" style="background-color:#F99; color:#003A88; font-weight:bold;"><?php echo $sale['product_profit_markup_total']; ?></td>
		  <?php } ?>
        </tr>			
        <?php } else { ?>
        <tr>
          <td class="center" colspan="12"><?php echo $text_no_results; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
</div>		
		<div class="row">
		<div class="col-sm-6 text-left" id="pagination_sale"><?php echo $pagination_sale; ?></div>
        <div class="col-sm-6 text-right" id="pagination_sale_count"><?php echo $results_sale; ?></div>
		</div>
            <script id="saleTemplate" type="text/x-jquery-tmpl">
        <tr>
          <td class="left">{{html product_order_id}}</td>
		  <td class="left">${product_date_added}</td>
          <td class="left">${product_name} 
		  {{if product_option}}
          <table cellpadding="0" cellspacing="0" border="0" style="border:none;">
          <tr>
		  <td nowrap="nowrap" style="font-size:11px; border:0;">{{html product_option}}<span style="font-size:1px; color:#FFF;">${product_order_product_id}</span></td>
          </tr>
          </table>
		  {{/if}}
		  </td>
          <td class="right">${product_sold}</td>
		  <td class="right">${product_total_excl_vat}</td>
		  <td class="right">${product_tax}</td>
		  <td class="right">${product_total_incl_vat}</td>
		  <td class="right" style="background-color:#DCFFB9;">${product_revenue}</td>
		  <td class="right" style="background-color:#ffd7d7;">${product_cost}</td>
		  {{if product_profit_raw >= 0}}
		  <td class="right" style="background-color:#c4d9ee; font-weight:bold;">${product_profit}</td>
		  <td class="right" style="background-color:#c4d9ee; font-weight:bold;">${product_profit_margin}</td>
		  <td class="right" style="background-color:#c4d9ee; font-weight:bold;">${product_profit_markup}</td>
		  {{else}}
		  <td class="right" style="background-color:#F99; font-weight:bold;">${product_profit}</td>
		  <td class="right" style="background-color:#F99; font-weight:bold;">${product_profit_margin}</td>
		  <td class="right" style="background-color:#F99; font-weight:bold;">${product_profit_markup}</td>
		  {{/if}}
        </tr>		
</script>
<script id="sale_totalTemplate" type="text/x-jquery-tmpl">
		<tr style="border-top:2px solid #CCC;">
		  <td colspan="3" class="right" style="background-color:#E5E5E5; font-weight:bold;"><?php echo $column_prod_totals; ?></td>
          <td class="right" style="background-color:#E5E5E5; color:#003A88; font-weight:bold;">${product_sold_total}</td>
		  <td class="right" style="background-color:#E5E5E5; color:#003A88; font-weight:bold;">${product_total_excl_vat_total}</td>
		  <td class="right" style="background-color:#E5E5E5; color:#003A88; font-weight:bold;">${product_tax_total}</td>
		  <td class="right" style="background-color:#E5E5E5; color:#003A88; font-weight:bold;">${product_total_incl_vat_total}</td>
		  <td class="right" style="background-color:#DCFFB9; color:#003A88; font-weight:bold;">${product_revenue_total}</td>
		  <td class="right" style="background-color:#ffd7d7; color:#003A88; font-weight:bold;">${product_cost_total}</td>
		  {{if product_profit_raw_total >= 0}}
		  <td class="right" style="background-color:#c4d9ee; color:#003A88; font-weight:bold;">${product_profit_total}</td>
		  <td class="right" style="background-color:#c4d9ee; color:#003A88; font-weight:bold;">${product_profit_margin_total}</td>
		  <td class="right" style="background-color:#c4d9ee; color:#003A88; font-weight:bold;">${product_profit_markup_total}</td>
		  {{else}}
		  <td class="right" style="background-color:#F99; color:#003A88; font-weight:bold;">${product_profit_total}</td>
		  <td class="right" style="background-color:#F99; color:#003A88; font-weight:bold;">${product_profit_margin_total}</td>
		  <td class="right" style="background-color:#F99; color:#003A88; font-weight:bold;">${product_profit_markup_total}</td>
		  {{/if}}
        </tr>		
</script>
            <?php if ($product_id) { ?>	

<script type="text/javascript">
function filter_history() {
	url = 'index.php?route=catalog/product/filter_history&token=<?php echo $token; ?>&product_id=<?php echo $product_ids; ?>';

	url += '&page_history=' + $('#page_history').val();

	if ($('#sort_history').val()) {
		url += '&sort_history=' + $('#sort_history').val();
	}
	if ($('#order_history').val()) {
		url += '&order_history=' + $('#order_history').val();
	}
		
	var filter_history_date_start = $('input[name=\'filter_history_date_start\']').val();
	
	if (filter_history_date_start) {
		url += '&filter_history_date_start=' + encodeURIComponent(filter_history_date_start);
	}

	var filter_history_date_end = $('input[name=\'filter_history_date_end\']').val();
	
	if (filter_history_date_end) {
		url += '&filter_history_date_end=' + encodeURIComponent(filter_history_date_end);
	}
		
	var filter_history_range = $('select[name=\'filter_history_range\']').val();
	
	if (filter_history_range) {
		url += '&filter_history_range=' + encodeURIComponent(filter_history_range);
	}

	var filter_history_option = $('select[name=\'filter_history_option\']').val();
	
	if (filter_history_option) {
		url += '&filter_history_option=' + encodeURIComponent(filter_history_option);
	}

	var filter_history_supplier = $('select[name=\'filter_history_supplier\']').val();
	
	if (filter_history_supplier) {
		url += '&filter_history_supplier=' + encodeURIComponent(filter_history_supplier);
	}
			
	$.ajax({
		url: url,
		dataType: 'json',
    	beforeSend: function(){$( ".hloading" ).show();},
		complete: function(){$( ".hloading" ).hide();},
		cache: false,
		success: function(json) {
				  $('table.list_main_history tr:gt(0)').empty();
				  $("#historyTemplate").tmpl(json.histories).appendTo("table.list_main_history");
				  if (document.getElementById('main_product').selected && document.getElementById('chart_all_time').selected) {
				  	$('#tab_chart_history').slideDown('fast');
				  } else {
				  	$('#tab_chart_history').slideUp('fast');
				  }
				  $('#pagination_history_count').html(json.results_history);
				  $('#pagination_history').html(json.pagination_history);
				  $('#page_history').val(1);				  
			  }
	});
}

function filter_sale() {
	url = 'index.php?route=catalog/product/filter_sale&token=<?php echo $token; ?>&product_id=<?php echo $product_ids; ?>';

	url += '&page_sale=' + $('#page_sale').val();

	if ($('#sort_sale').val()) {
		url += '&sort_sale=' + $('#sort_sale').val();
	}
	if ($('#order_sale').val()) {
		url += '&order_sale=' + $('#order_sale').val();
	}
			
	var filter_sale_date_start = $('input[name=\'filter_sale_date_start\']').val();
	
	if (filter_sale_date_start) {
		url += '&filter_sale_date_start=' + encodeURIComponent(filter_sale_date_start);
	}

	var filter_sale_date_end = $('input[name=\'filter_sale_date_end\']').val();
	
	if (filter_sale_date_end) {
		url += '&filter_sale_date_end=' + encodeURIComponent(filter_sale_date_end);
	}
		
	var filter_sale_range = $('select[name=\'filter_sale_range\']').val();
	
	if (filter_sale_range) {
		url += '&filter_sale_range=' + encodeURIComponent(filter_sale_range);
	}

	var sale_order_statuses = [];
	$('#sale_order_status option:selected').each(function() {
		sale_order_statuses.push($(this).val());
	});
	
	var filter_sale_order_status = sale_order_statuses.join('_');
	
	if (filter_sale_order_status) {
		url += '&filter_sale_order_status=' + encodeURIComponent(filter_sale_order_status);
	}

	var sale_options = [];
	$('#sale_option option:selected').each(function() {
		sale_options.push($(this).val());
	});
	
	var filter_sale_option = sale_options.join('_');
	
	if (filter_sale_option) {
		url += '&filter_sale_option=' + encodeURIComponent(filter_sale_option);
	}
		
	$.ajax({
		url: url,
		dataType: 'json',
    	
		cache: false,
		success: function(json) {
				  $('table.list_main_sale tr:gt(0)').empty();				  
				  $("#saleTemplate").tmpl(json.sales).appendTo("table.list_main_sale");	
				  $("#sale_totalTemplate").tmpl(json.sales).appendTo("table.list_main_sale");
				  var seen = {};
					$('table.list_main_sale tr').each(function() {
    				var txt = $(this).text();
    				if (seen[txt])
        				$(this).remove();
    				else
        				seen[txt] = true;
				  });
				  $('#pagination_sale_count').html(json.results_sale);
				  $('#pagination_sale').html(json.pagination_sale);
				  $('#page_sale').val(1);				  
			  }
	});	
} 
</script>
<script type="text/javascript">
function gsUVhistory(e, t, v) {
    var n = String(e).split("?");
    var r = "";
    if (n[1]) {
        var i = n[1].split("&");
        for (var s = 0; s <= i.length; s++) {
            if (i[s]) {
                var o = i[s].split("=");
                if (o[0] && o[0] == t) {
                    r = o[1];
                    if (v != undefined) {
                        i[s] = o[0] +'=' + v;
                    }
                    break;
                }
            }
        }
    }
    if (v != undefined) {
        return n[0] +'?'+ i.join('&');
    }
    return r
}

$('#filter_history_range').on("change", function() {
	$('#page_history').val(1);
	filter_history();
});

$('#date-start-history').on("change", function() {
	$('#page_history').val(1);
	filter_history();
});

$('#date-end-history').on("change", function() {
	$('#page_history').val(1);
	filter_history();
});

$('#filter_history_option').on("change", function() {
	$('#page_history').val(1);
	filter_history();
});

$('#filter_history_supplier').on("change", function() {
	$('#page_history').val(1);
	filter_history();
});

$('#pagination_history').delegate('.pagination a', 'click', function() {
	var page_history = gsUVhistory($(this).prop('href'), 'page_history');
	$('#page_history').val(page_history);
	filter_history();
	return false;
});

$('#head_history a').on("click", function() {
	var sort_history = gsUVhistory($(this).prop('href'), 'sort_history');
	$('#sort_history').val(sort_history);
	var order_history = gsUVhistory($(this).prop('href'), 'order_history');
	$('#order_history').val(order_history);
	$(this).prop('href', gsUVhistory($(this).prop('href'), 'order_history', order_history=='DESC'?'ASC':'DESC'));
	$('#head_history a').removeAttr('class');
	this.className = order_history.toLowerCase();
	filter_history();
	return false;
});


function gsUVsale(e, t, v) {
    var n = String(e).split("?");
    var r = "";
    if (n[1]) {
        var i = n[1].split("&");
        for (var s = 0; s <= i.length; s++) {
            if (i[s]) {
                var o = i[s].split("=");
                if (o[0] && o[0] == t) {
                    r = o[1];
                    if (v != undefined) {
                        i[s] = o[0] +'=' + v;
                    }
                    break;
                }
            }
        }
    }
    if (v != undefined) {
        return n[0] +'?'+ i.join('&');
    }
    return r
}

$('#filter_sale_range').on("change", function() {
	filter_sale();
});

$('#date-start-sale').on("change", function() {
	filter_sale();
});

$('#date-end-sale').on("change", function() {
	filter_sale();
});

$('#sale_order_status').on("change", function() {
	filter_sale();
});

$('#sale_option').on("change", function() {
	filter_sale();
});

$('#pagination_sale').delegate('.pagination a', 'click', function() {
	var page_sale = gsUVsale($(this).prop('href'), 'page_sale');
	$('#page_sale').val(page_sale);
	filter_sale();
	return false;
});

$('#head_sale a').on("click", function() {
	var sort_sale = gsUVsale($(this).prop('href'), 'sort_sale');
	$('#sort_sale').val(sort_sale);
	var order_sale = gsUVsale($(this).prop('href'), 'order_sale');
	$('#order_sale').val(order_sale);
	$(this).prop('href', gsUVsale($(this).prop('href'), 'order_sale', order_sale=='DESC'?'ASC':'DESC'));
	$('#head_sale a').removeAttr('class');
	this.className = order_sale.toLowerCase();
	filter_sale();
	return false;
});

$('.select').selectpicker();
</script>
<script type="text/javascript">
$(document).ready(function() {	
	$('#sale_order_status').multiselect({
		checkboxName: 'sale_order_statuses[]',
		includeSelectAllOption: true,
		enableFiltering: true,
		selectAllText: '<?php echo $text_all; ?>',
		allSelectedText: '<?php echo $text_selected; ?>',
		nonSelectedText: '<?php echo $text_all_sstatus; ?>',
		numberDisplayed: 0,
		disableIfEmpty: true,
		maxHeight: 300
	});
	$('#sale_option').multiselect({
		checkboxName: 'sale_options[]',
		includeSelectAllOption: true,
		enableFiltering: true,
		selectAllText: '<?php echo $text_all; ?>',
		allSelectedText: '<?php echo $text_selected; ?>',
		nonSelectedText: '<?php echo $text_all_soption; ?>',
		numberDisplayed: 0,
		disableIfEmpty: true,
		maxHeight: 300
	});	
});
</script> 
<script type="text/javascript">
$('#date-start-history').datetimepicker({
	pickTime: false
});
$('#date-end-history').datetimepicker({
	pickTime: false
});

$('#date-start-sale').datetimepicker({
	pickTime: false
});
$('#date-end-sale').datetimepicker({
	pickTime: false
});
</script>
<script type="text/javascript">
	function history_download() {
		var url = 'index.php?route=catalog/product/download_history&token=<?php echo $token; ?>&product_id=<?php echo $product_ids; ?>';
		location = url;
	}
	
	function history_delete() {
		<?php if ($modify_permission) { ?>
		if(confirm("<?php echo $text_confirm_delete_history;?>") == false) return false;
		var durl = 'index.php?route=catalog/product/delete_history&token=<?php echo $token; ?>&product_id=<?php echo $product_ids; ?>';
		location = durl;
		<?php } else { ?>
		alert("You do not have permission to delete Stock History!");
		<?php } ?>			
	}
</script>

<?php } ?>	
