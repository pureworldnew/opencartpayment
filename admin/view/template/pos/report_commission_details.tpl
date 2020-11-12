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
		<div class="pull-right" style="margin-top: -10px;"><a class="btn btn-primary" onclick="filter();"><i class="fa fa-filter fa-lg"></i> <?php echo $button_filter; ?></a></div>
      </div>
    <div class="panel-body">
      <form action="" method="post" enctype="multipart/form-data" id="form">
        <table class="table table-stripped table-bordered table-hover">
          <thead>
            <tr>
              <td class="text-left"><?php echo $column_order_id; ?></td>
              <td class="text-left"><?php echo $column_commission; ?></td>
              <td class="text-left"><?php echo $column_user_name; ?></td>
              <td class="text-left"><?php echo $column_commission_date; ?></td>
            </tr>
          </thead>
          <tbody>
            <tr class="filter">
              <td class="text-left"><input class="form-control" type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" /></td>
              <td class="text-left"><input class="form-control" type="text" name="filter_commission" value="<?php echo $filter_commission; ?>" /></td>
              <td class="text-left"><input class="form-control" type="text" name="filter_user_name" value="<?php echo $filter_user_name; ?>" /><input type="hidden" name="filter_user_id" value="<?php echo $filter_user_id; ?>" /></td>
              <td class="text-left"><input class="form-control date" type="text" name="filter_commission_date" value="<?php echo $filter_commission_date; ?>" data-format="YYYY-MM-DD" /></td>
            </tr>
            <?php if ($order_commissions) { ?>
            <?php foreach ($order_commissions as $order_commission) { ?>
            <tr>
              <td class="text-left"><?php echo $order_commission['order_id']; ?></td>
              <td class="text-left"><?php echo $order_commission['commission']; ?></td>
              <td class="text-left"><?php echo $order_commission['username']; ?></td>
              <td class="text-left"><?php echo $order_commission['date_modified']; ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
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
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=report/pos_commission&token=<?php echo $token; ?>';
	
	var filter_order_id = $('input[name=\'filter_order_id\']').val();
	
	if (filter_order_id) {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}
	
	var filter_commission = $('input[name=\'filter_commission\']').val();
	
	if (filter_commission) {
		url += '&filter_commission=' + encodeURIComponent(filter_commission);
	}
	
	var filter_user_name = $('input[name=\'filter_user_name\']').val();

	if (filter_user_name) {
		url += '&filter_user_name=' + encodeURIComponent(filter_user_name);
	}	
	
	var filter_commission_date = $('input[name=\'filter_commission_date\']').val();
	
	if (filter_commission_date) {
		url += '&filter_commission_date=' + encodeURIComponent(filter_commission_date);
	}

	var filter_user_id = $('input[name=\'filter_user_id\']').val();
	
	if (filter_user_id && filter_user_name != '') {
		url += '&filter_user_id=' + encodeURIComponent(filter_user_id);
	}

	location = url;
}

$(document).on('focus', 'input[name=\'filter_user_name\']', function(){
	$(this).autocomplete({
		delay: 500,
		source: function(request, response) {
			var url = 'index.php?route=report/pos_commission/autocompleteByUserName&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term);
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
//--></script> 
<?php echo $footer; ?>