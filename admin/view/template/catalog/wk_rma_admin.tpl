<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
       <div class="pull-right" style="display:inline-flex;">
        <strong><span style="white-space: nowrap;position: relative;top: 10px;left: -5px;">Create Return:</span>&nbsp;</strong> <input placeholder="By Order ID" type="text" class="form-control" value="" onfocusout="getReturnByOrderID(this.value)">&nbsp;  
        <input type="text" name="filter_rma_customer" value="<?php echo $filter_name; ?>" placeholder="By Customer Name" id="input-name" class="form-control" /> &nbsp;&nbsp;&nbsp;
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-filter').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>
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
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-admin_oid"><?php echo $text_admin_oid; ?></label>
                <input type="text" name="filter_order" value="<?php echo $filter_order; ?>" placeholder="<?php echo $text_admin_oid; ?>" id="input-admin_oid" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-admin_adminstatus"><?php echo $text_admin_adminstatus; ?></label>
                <select name="filter_admin_status" id="input-admin_adminstatus" class="form-control">
                  <option value="*">  </option>
                  <?php if($admin_status){ ?>
                  <?php foreach($admin_status as $admin_statuss){ ?>
                    <option value="<?php echo $admin_statuss['id']; ?>" <?php if($filter_admin_status==$admin_statuss['id']) echo 'selected'; ?>><?php echo $admin_statuss['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-name"><?php echo $text_admin_cname; ?></label>
                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $text_admin_cname; ?>" id="input-name" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-admin_date"><?php echo $text_admin_date; ?></label>
                <div class='input-group date'>
                  <input type='text' name="filter_date" value="<?php echo $filter_date; ?>" data-date-format="YYYY-MM-DD" placeholder="<?php echo $text_admin_date; ?>" id="input-admin_date" class="form-control" />
                  <span class="input-group-addon"><span class="fa fa-calendar"></span>
                  </span>
                </div>
              </div>
            </div>

            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-status"><?php echo $text_status; ?></label>
                <select name="filter_rma_status_id" id="input-status" class="form-control">
                  <?php if ($filter_rma_status_id) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="pull-right">
                <button type="button" onclick="filter();" class="btn btn-primary"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
                <button type="button" onclick="clrfilter();" class="btn btn-primary"><i class="fa fa-search"></i> <?php echo $button_clrfilter; ?></button>
              </div>
            </div>
          </div>
        </div>

        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-filter">
          <div class="table-responsive">
            <table class="table table-bordered table-hove">
            <thead>
              <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>

                <td class="text-left">
                  <?php if ($sort == 'wro.order_id') { ?>
                    <a href="<?php echo $sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $text_admin_oid; ?></a>
                  <?php } else { ?>
                    <a href="<?php echo $sort_order; ?>" > <?php echo $text_admin_oid; ?> </a>
                  <?php } ?>
                </td>

                <td class="text-left">
                  <?php if ($sort == 'c.firstname') { ?>
                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $text_admin_cname; ?></a>
                  <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>" > <?php echo $text_admin_cname; ?> </a>
                  <?php } ?>
                </td>
                <td class="text-left">
                  <?php if ($sort == 'wro.id') { ?>
                    <a href="<?php echo $sort_product; ?>" class="<?php echo strtolower($order); ?>"><?php echo $text_admin_product; ?></a>
                  <?php } else { ?>
                    <a href="<?php echo $sort_product; ?>" > <?php echo $text_admin_product; ?> </a>
                  <?php } ?>
                </td>
                <td class="text-left"><?php echo $text_admin_return; ?></td>

                <td class="text-left">
                <?php if ($sort == 'wrr.id') { ?>
                  <a href="<?php echo $sort_reason; ?>" class="<?php echo strtolower($order); ?>"><?php echo $text_admin_reason; ?></a>
                <?php } else { ?>
                  <a href="<?php echo $sort_reason; ?>" > <?php echo $text_admin_reason; ?> </a>
                <?php } ?>
                </td>
                <td class="text-left">
                  <?php echo $text_rma_status;?>
                </td>

                <td class="text-left">
                <?php if ($sort == 'wro.date') { ?>
                  <a href="<?php echo $sort_date; ?>" class="<?php echo strtolower($order); ?>"><?php echo $text_admin_date; ?></a>
                <?php } else { ?>
                  <a href="<?php echo $sort_date; ?>" > <?php echo $text_admin_date; ?> </a>
                <?php } ?>
                </td>

                <td class="text-left">
                <?php if ($sort == 'wro.return_pos') { ?>
                  <a href="<?php echo $sort_date; ?>" class="<?php echo strtolower($order); ?>"><?php echo $text_admin_date; ?></a>
                <?php } else { ?>
                  <a href="<?php echo $sort_date; ?>" > <?php echo "Type" ?> </a>
                <?php } ?>
                </td>

                <td></td>
              </tr>
            </thead>
            <tbody>
              <?php if($result_rmaadmin) { ?>
                <?php foreach ($result_rmaadmin as $result_rmaadmins) { ?>
                <tr>
                  <td style="text-align: center;"><?php if ($result_rmaadmins['selected']) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $result_rmaadmins['id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $result_rmaadmins['id']; ?>" />
                    <?php } ?>
                  </td>
                  <td class="text-left"><?php echo  ucfirst($result_rmaadmins['oid']); ?></td>
                  <td class="text-left"><?php echo  ucfirst($result_rmaadmins['name']); ?></td>

                  <td class="text-left"><?php echo  ucfirst($result_rmaadmins['product']); ?></td>
                  <td class="text-left"><?php echo $result_rmaadmins['quantity']; ?></td>
                  <td class="text-left"><?php echo  ucfirst($result_rmaadmins['reason']); ?></td>
                  <td class="text-left" style="color:<?php echo  $result_rmaadmins['color']; ?>;font-weight:bold;"><?php echo  ucfirst($result_rmaadmins['rma_status']); ?></td>
                  <td class="text-left"><?php echo  ucfirst($result_rmaadmins['date']); ?></td>
                  <td class="text-left"><?php echo (($result_rmaadmins['type'])==0?"Gempacked":"POS"); ?></td>

                  <td class="text-center">
                    <?php foreach ($result_rmaadmins['action'] as $action) { ?>
                    <a href="<?php echo $action['href']; ?>" class="btn btn-info" data-toggle="tooltip" title="<?php echo $action['text']; ?>"><i class="fa fa-eye"></i></a>
                    <?php } ?>
                  </td>
                </tr>
                <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="12"><?php echo $text_no_recored; ?></td>
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
</div>

<script type="text/javascript"><!--
$('.date').datetimepicker({
  pickTime: false,
  format:"YYYY-MM-DD",
});

function clrfilter() {
  location = 'index.php?route=catalog/wk_rma_admin&token=<?php echo $token; ?>';
}

function filter() {
  url = 'index.php?route=catalog/wk_rma_admin&token=<?php echo $token; ?>';

  var filter_name = $('input[name=\'filter_name\']').val();

  if (filter_name) {
    url += '&filter_name=' + encodeURIComponent(filter_name);
  }

  var filter_order = $('input[name=\'filter_order\']').val();

  if (filter_order) {
    url += '&filter_order=' + encodeURIComponent(filter_order);
  }

  // var filter_reason = $('select[name=\'filter_reason\']').val();
  //
  // if (filter_reason != '*') {
  //   url += '&filter_reason=' + encodeURIComponent(filter_reason);
  // }

  var filter_rma_status = $('select[name=\'filter_rma_status\']').val();

  if (filter_rma_status != '*') {
    url += '&filter_rma_status=' + encodeURIComponent(filter_rma_status);
  }

  var filter_admin_status = $('select[name=\'filter_admin_status\']').val();

  if (filter_admin_status != '*') {
    url += '&filter_admin_status=' + encodeURIComponent(filter_admin_status);
  }

  var filter_date = $('input[name=\'filter_date\']').val();

  if (filter_date) {
    url += '&filter_date=' + encodeURIComponent(filter_date);
  }

  var filter_rma_status_id = $('select[name=\'filter_rma_status_id\']').val();

  if (filter_rma_status_id) {
    url += '&filter_rma_status_id=' + encodeURIComponent(filter_rma_status_id);
  }

  location = url;
}
$('input[name=\'filter_rma_customer\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=<?php echo $customer_link; ?>/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'] + '(' + item['email'] + ')',
						value: item['customer_id'],
            email: item['email']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_rma_customer\']').val(item['label']);
    location = 'index.php?route=catalog/wk_customer_orders&token=<?php echo $token; ?>&customer_id='+encodeURIComponent(item['value'])+'&customer_email=' + encodeURIComponent(item['email']);
	}
});

function getReturnByOrderID(val)
{
  val = parseInt(val);
  if(isNaN(val))
  {
    val = 0;
  }
  if(val > 0)
  {
    location = 'index.php?route=catalog/wk_customer_orders&token=<?php echo $token; ?>&order_id='+encodeURIComponent(val);
  }
}
//--></script>
<?php echo $footer; ?>
