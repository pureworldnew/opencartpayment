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
      <table class="table">
        <tr>
          <td class="text-left"><?php echo $entry_date_start; ?>
            <input class="form-control" type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" data-format="YYYY-MM-DD" data-date-format="YYYY-MM-DD" id="date-start" /></td>
          <td class="text-left"><?php echo $entry_date_end; ?>
            <input class="form-control" type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" data-format="YYYY-MM-DD" data-date-format="YYYY-MM-DD" id="date-end" /></td>
          <td class="text-left"><?php echo $entry_group; ?>
            <select class="form-control" name="filter_group">
              <?php foreach ($groups as $groups) { ?>
              <?php if ($groups['value'] == $filter_group) { ?>
              <option value="<?php echo $groups['value']; ?>" selected="selected"><?php echo $groups['text']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $groups['value']; ?>"><?php echo $groups['text']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
      </table>
      <table class="table table-stripped table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-left"><?php echo $column_user_name; ?></td>
			<td class="text-left"><?php echo $column_date_start; ?></td>
            <td class="text-left"><?php echo $column_date_end; ?></td>
            <?php foreach($payment_types as $payment_type) { if ($payment_type != 'cash_in' && $payment_type != 'cash_out') {?>
			<td class="text-right"><?php echo $payment_type; ?></td>
			<?php }} ?>
			<td class="text-right" style="background-color: #d2d2ff"><?php echo $column_subtotal; ?></td>
			<td class="text-right">cash_in</td>
			<td class="text-right">cash_out</td>
            <td class="text-right" style="background-color: #d2d2ff"><?php echo $column_total; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($payments)) { ?>
          <?php foreach ($payments as $payment) { ?>
          <tr>
		    <td class="text-left"><?php echo $payment['username']; ?></td>
            <td class="text-left"><?php echo $payment['date_start']; ?></td>
            <td class="text-left"><?php echo $payment['date_end']; ?></td>
			<?php foreach($payment_types as $payment_type) { if ($payment_type != 'cash_in' && $payment_type != 'cash_out') {?>
			<td class="text-right"><?php echo $payment['amounts'][$payment_type]; ?></td>
			<?php }} ?>
            <td class="text-right" style="background-color: #d2d2ff"><?php echo $payment['subtotal']; ?></td>
			<td class="text-right"><?php echo $payment['amounts']['cash_in']; ?></td>
			<td class="text-right"><?php echo $payment['amounts']['cash_out']; ?></td>
            <td class="text-right" style="background-color: #d2d2ff"><?php echo $payment['total']; ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="text-center" colspan="<?php echo 5 + count($payment_types); ?>"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
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
	url = 'index.php?route=report/order_payment/summary&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
		
	var filter_group = $('select[name=\'filter_group\']').val();
	
	if (filter_group) {
		url += '&filter_group=' + encodeURIComponent(filter_group);
	}
	
	url += '&browser_time=' + ((new Date()).getTime());

	location = url;
}
function exportCSV() {
	url = 'index.php?route=report/order_payment/generate_report_csv&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
		
	var filter_group = $('select[name=\'filter_group\']').val();
	
	if (filter_group) {
		url += '&filter_group=' + encodeURIComponent(filter_group);
	}
	
	url += '&browser_time=' + ((new Date()).getTime()) + '&type=summary';

	location = url;
}
//--></script> 
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date-start').datetimepicker({pickTime: false});
	
	$('#date-end').datetimepicker({pickTime: false});
});
$(document).on('click', '.pagination a', function() {
	event.preventDefault();
	var href = $(this).attr('href');
	var browser_time = (new Date()).getTime();
	location = href + '&browser_time=' + browser_time;
});
//--></script> 
<?php echo $footer; ?>