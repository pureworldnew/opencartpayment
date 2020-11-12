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
        	<?php echo $menu; ?>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-abandoned-carts">
              <div class="well well-sm">
								<div class="row">
									<div class="col-sm-3">
										<div class="form-group">
											<label class="control-label" for="input-order-id"><?php echo $entry_name; ?></label>
											<input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
										</div>
									</div>
									<div class="col-sm-3">
										<div class="form-group">
											<label class="control-label" for="input-email"><?php echo $entry_email; ?></label>
											<input type="text" name="filter_email" value="<?php echo $filter_email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
										</div>
									</div>
									<div class="col-sm-3">
										<div class="form-group">
											<label class="control-label" for="input-email-notify"><?php echo $entry_email_notify; ?></label>
											<select name="filter_email_notify" id="input-email-notify" class="form-control">
												<option value="*"></option>
												<?php if ($filter_email_notify) { ?>
												<option value="1" selected="selected"><?php echo $text_yes; ?></option>
												<?php } else { ?>
												<option value="1"><?php echo $text_yes; ?></option>
												<?php } ?>
												<?php if (!$filter_email_notify && !is_null($filter_email_notify)) { ?>
												<option value="0" selected="selected"><?php echo $text_no; ?></option>
												<?php } else { ?>
												<option value="0"><?php echo $text_no; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="col-sm-3">
										<div class="form-group">
											<label class="control-label" for="input-account"><?php echo $entry_account; ?></label>
											<select name="filter_account" id="input-account" class="form-control">
												<option value="*"></option>
												<?php if ($filter_account) { ?>
												<option value="1" selected="selected"><?php echo $text_yes; ?></option>
												<?php } else { ?>
												<option value="1"><?php echo $text_yes; ?></option>
												<?php } ?>
												<?php if (!$filter_account && !is_null($filter_account)) { ?>
												<option value="0" selected="selected"><?php echo $text_no; ?></option>
												<?php } else { ?>
												<option value="0"><?php echo $text_no; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="col-sm-3">
										<div class="form-group">
											<label class="control-label" for="input-store"><?php echo $entry_store; ?></label>
											<select name="filter_store_id" id="input-store" class="form-control">
												<option value="*"></option>
												<?php if ($filter_store_id == '0') { ?>
												<option value="0" selected="selected"><?php echo $text_default; ?></option>
												<?php }else{ ?>
												<option value="0"><?php echo $text_default; ?></option>
												<?php } ?>
												<?php foreach ($stores as $store) { ?>
												<?php if ($store['store_id'] == $filter_store_id) { ?>
												<option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
												<?php } else { ?>
												<option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
												<?php } ?>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="col-sm-3">
										<div class="form-group">
											<label class="control-label" for="input-date-added"><?php echo $entry_date_added; ?></label>
											<div class="input-group date">
												<input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
												<span class="input-group-btn">
												<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
												</span></div>
										</div>
									</div>
									<div class="col-sm-3">
										<div class="form-group">
										<label class="control-label" style="display:block"> &nbsp; </label>
											<button type="button" id="button-filter" class="btn btn-primary btn-block"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
										</div>
									</div>
								</div>
							</div>
							<div id="recovery_carts"></div>
						</div>
          </div>
      </div>
    </div>
  </div>
<script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	var url = 'index.php?route=abandoned_carts/abandonedcarts_history/getCarts&token=<?php echo $token; ?>';

	var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

	var filter_email = $('input[name=\'filter_email\']').val();

	if (filter_email) {
		url += '&filter_email=' + encodeURIComponent(filter_email);
	}

	var filter_email_notify = $('select[name=\'filter_email_notify\']').val();

	if (filter_email_notify !='*') {
		url += '&filter_email_notify=' + encodeURIComponent(filter_email_notify);
	}
	
	var filter_account = $('select[name=\'filter_account\']').val();

	if (filter_account !='*') {
		url += '&filter_account=' + encodeURIComponent(filter_account);
	}

	var filter_store_id = $('select[name=\'filter_store_id\']').val();

	if (filter_store_id !='*') {
		url += '&filter_store_id=' + encodeURIComponent(filter_store_id);
	}
	
	var filter_date_added = $('input[name=\'filter_date_added\']').val();

	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}

	getFilterCarts(url);
});
//--></script>
<script type="text/javascript"><!--
function getFilterCarts(url) {
	$.ajax({
		url: url,
		dataType: 'html',
		beforeSend: function() {
			$('#recovery_carts').html('<div class="loading_gif text-center"><img src="view/image/loading_icon.gif" class="img-reponsive"></div>');
		},
		complete: function() {
		},
		success: function(html) {
			$('#recovery_carts').html(html);
		}
	});
}
//--></script>
<script type="text/javascript"><!--
$(document).ready(function(){	$('.abandoned_carts_history_class').addClass('active'); });
$(document).ready(function(){	$('#button-filter').trigger('click'); });
//--></script>

<script type="text/javascript"><!--
$('#recovery_carts').delegate('.pagination a', 'click', function(e) {
	e.preventDefault();

	$('#recovery_carts').load(this.href);
});
//--></script>
<script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script>
</div>
<?php echo $footer; ?>