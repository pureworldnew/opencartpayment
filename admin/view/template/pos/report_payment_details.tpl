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
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> <?php echo $heading_title; ?></h3>
 		<div class="pull-right" style="margin-top: -10px;">
			<a class="btn btn-primary" onclick="filter();"><i class="fa fa-filter fa-lg"></i> <?php echo $button_filter; ?></a>
			<a class="btn btn-success" onclick="exportCSV();"><i class="fa fa-table fa-lg"></i> <?php echo $button_export_csv; ?></a>
		</div>
     </div>
    <div class="panel-body">
      <form action="" method="post" enctype="multipart/form-data" id="form">
        <table class="table table-stripped table-bordered table-hover">
          <thead>
            <tr>
              <td class="text-left"><?php echo $column_order_payment_id; ?></td>
              <td class="text-right"><?php echo $column_order_id; ?></td>
              <td class="text-right"><?php echo $column_pos_return_id; ?></td>
              <td class="text-left"><?php echo $column_payment_type; ?></td>
              <td class="text-right"><?php echo $column_tendered_amount; ?></td>
              <td class="text-left"><?php echo $column_payment_note; ?></td>
              <td class="text-left"><?php echo $column_payment_time; ?></td>
			  <!-- add for admin payment details begin -->
              <td class="text-left"><?php echo $column_user_name; ?></td>
			  <!-- add for admin payment details end -->
            </tr>
          </thead>
          <tbody>
            <tr class="filter">
			  <td></td>
              <td class="text-right"><input class="form-control" type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" style="text-align: right;" /></td>
              <td class="text-right"><input class="form-control" type="text" name="filter_pos_return_id" value="<?php echo $filter_pos_return_id; ?>" style="text-align: right;" /></td>
              <td class="text-left"><input class="form-control" type="text" name="filter_payment_type" value="<?php echo $filter_payment_type; ?>" /></td>
              <td class="text-right"><input class="form-control" type="text" name="filter_tendered_amount" value="<?php echo $filter_tendered_amount; ?>" style="text-align: right;" /></td>
			  <td></td>
              <td class="text-left"><input class="form-control date" type="text" name="filter_payment_date" value="<?php echo $filter_payment_date; ?>" data-format="YYYY-MM-DD" data-date-format="YYYY-MM-DD" /></td>
			  <!-- add for admin payment details begin -->
              <td class="text-left">
				<input type="text" class="form-control" name="filter_user_name" value="<?php echo $filter_user_name; ?>" />
				<input type="hidden" name="filter_user_id" value="<?php echo $filter_user_id; ?>" />
			  </td>
			  <!-- add for admin payment details end -->
            </tr>
            <?php if ($order_payments) { ?>
            <?php foreach ($order_payments as $order_payment) { ?>
            <tr>
              <td class="text-left"><?php echo $order_payment['order_payment_id']; ?></td>
              <td class="text-right"><?php echo $order_payment['order_id']; ?></td>
              <td class="text-right"><?php echo $order_payment['pos_return_id']; ?></td>
              <td class="text-left"><?php echo $order_payment['payment_type']; ?></td>
              <td class="text-right"><?php echo $order_payment['tendered_amount']; ?></td>
              <td class="text-left"><?php echo $order_payment['payment_note']; ?></td>
              <td class="text-left"><?php echo $order_payment['payment_time']; ?></td>
			  <td class="text-left"><?php echo $order_payment['user_name']; ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
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
</div>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=report/order_payment&token=<?php echo $token; ?>';
	
	var filter_order_id = $('input[name=\'filter_order_id\']').val();
	
	if (filter_order_id) {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}
	
	var filter_pos_return_id = $('input[name=\'filter_pos_return_id\']').val();
	
	if (filter_pos_return_id) {
		url += '&filter_pos_return_id=' + encodeURIComponent(filter_pos_return_id);
	}
	
	var filter_payment_type = $('input[name=\'filter_payment_type\']').val();
	
	if (filter_payment_type) {
		url += '&filter_payment_type=' + encodeURIComponent(filter_payment_type);
	}
	
	var filter_tendered_amount = $('input[name=\'filter_tendered_amount\']').val();

	if (filter_tendered_amount) {
		url += '&filter_tendered_amount=' + encodeURIComponent(filter_tendered_amount);
	}	
	
	var filter_payment_date = $('input[name=\'filter_payment_date\']').val();
	
	if (filter_payment_date) {
		url += '&filter_payment_date=' + encodeURIComponent(filter_payment_date);
	}

	// add for admin payment details begin
	var filter_user_name = $('input[name=\'filter_user_name\']').val();
	
	if (filter_user_name) {
		url += '&filter_user_name=' + encodeURIComponent(filter_user_name);
	}
	
	var filter_user_id = $('input[name=\'filter_user_id\']').val();
	
	if (filter_user_id && filter_user_name != '') {
		url += '&filter_user_id=' + encodeURIComponent(filter_user_id);
	}
	// add for admin payment details end
	
	url += '&browser_time=' + ((new Date()).getTime());
	
	location = url;
}

function exportCSV() {
	url = 'index.php?route=report/order_payment/generate_report_csv&token=<?php echo $token; ?>';
	
	var filter_order_id = $('input[name=\'filter_order_id\']').val();
	
	if (filter_order_id) {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}
	
	var filter_pos_return_id = $('input[name=\'filter_pos_return_id\']').val();
	
	if (filter_pos_return_id) {
		url += '&filter_pos_return_id=' + encodeURIComponent(filter_pos_return_id);
	}
	
	var filter_payment_type = $('input[name=\'filter_payment_type\']').val();
	
	if (filter_payment_type) {
		url += '&filter_payment_type=' + encodeURIComponent(filter_payment_type);
	}
	
	var filter_tendered_amount = $('input[name=\'filter_tendered_amount\']').val();

	if (filter_tendered_amount) {
		url += '&filter_tendered_amount=' + encodeURIComponent(filter_tendered_amount);
	}	
	
	var filter_payment_date = $('input[name=\'filter_payment_date\']').val();
	
	if (filter_payment_date) {
		url += '&filter_payment_date=' + encodeURIComponent(filter_payment_date);
	}

	// add for admin payment details begin
	var filter_user_name = $('input[name=\'filter_user_name\']').val();
	
	if (filter_user_name) {
		url += '&filter_user_name=' + encodeURIComponent(filter_user_name);
	}
	
	var filter_user_id = $('input[name=\'filter_user_id\']').val();
	
	if (filter_user_id && filter_user_name != '') {
		url += '&filter_user_id=' + encodeURIComponent(filter_user_id);
	}
	// add for admin payment details end
	
	url += '&browser_time=' + ((new Date()).getTime()) + '&type=details';
	
	location = url;
}

// add for admin payment details
$(document).on('focus', 'input[name=\'filter_user_name\']', function(){
	$(this).autocomplete({
		delay: 500,
		source: function(request, response) {
			var url = 'index.php?route=report/order_payment/autocompleteByUserName&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term);
			$.ajax({
				url: url,
				dataType: 'json',
				success: function(json) {	
					response($.map(json, function(item) {
						return {
							label: item.user_name,
							value: item.user_id
						}
					}));
				}
			});
		}, 
		select: function(event, ui) {
			$('input[name=filter_user_name]').val(ui.item['label']);
			$('input[name=filter_user_id]').val(ui.item['value']);
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
});
// add for admin payment details end
//--></script>  
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datetimepicker({pickTime: false});
});
//--></script> 
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
$(document).on('click', '.pagination a', function() {
	event.preventDefault();
	var href = $(this).attr('href');
	var browser_time = (new Date()).getTime();
	location = href + '&browser_time=' + browser_time;
});
//--></script> 
<?php echo $footer; ?>