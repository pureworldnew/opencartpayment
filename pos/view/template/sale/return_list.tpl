<?php $edit_access = isset($permissions['returns'][0]) && !empty($permissions['returns'][0]) && $permissions['returns'][0] == 'edit_access'; ?>
<div id="content">
  	<div class="container-fluid" style="margin-top: 25px;">
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
          <div class="row pull-right" style="margin-right:1px;">
          <?php if(isset($permissions['returns'][0]) && !empty($permissions['returns'][0]) && $permissions['returns'][0] == 'edit_access'){ ?>
                  <button id="newReturn" class="pull-right btn btn-success">Create New Return</button>
          <?php } ?>
          </div>
          <div class="row" style="clear:both;">
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
              <div class="form-group">
                <label class="control-label" for="input-record-limit">Show: </label>
								<select name="filter_record_limit" id="input-record-limit"  class="form-control" style="max-width: 20%; display: inline-block;"> 
									<option value="10" <?php echo ($filter_record_limit == "10") ? "selected" : ""; ?>>10</option>
									<option value="20" <?php echo ($filter_record_limit == "20") ? "selected" : ""; ?>>20</option>
									<option value="50" <?php echo ($filter_record_limit == "50") ? "selected" : ""; ?>>50</option>
									<option value="100" <?php echo ($filter_record_limit == "100") ? "selected" : ""; ?>>100</option>
								</select>
                <button type="button" id="button-filter" class="btn btn-primary"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
              </div>
            </div>
          </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-return">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
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
									<td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if($result_rmaadmin) { ?>
                  <?php foreach ($result_rmaadmin as $result_rmaadmins) { ?>
                    <tr>
                      <td class="text-left"><?php echo  ucfirst($result_rmaadmins['oid']); ?></td>
                      <td class="text-left"><?php echo  ucfirst($result_rmaadmins['name']); ?></td>
                      <td class="text-left"><?php echo  ucfirst($result_rmaadmins['product']); ?></td>
                      <td class="text-left"><?php echo $result_rmaadmins['quantity']; ?></td>
                      <td class="text-left"><?php echo  ucfirst($result_rmaadmins['reason']); ?></td>
                      <td class="text-left" style="color:<?php echo  $result_rmaadmins['color']; ?>;font-weight:bold;"><?php echo  ucfirst($result_rmaadmins['rma_status']); ?></td>
                      <td class="text-left"><?php echo  ucfirst($result_rmaadmins['date']); ?></td>
                      <td class="text-left"><?php echo (($result_rmaadmins['type'])==0?"Gempacked":"POS"); ?></td>
                      <td class="text-center">
                        
                        <a href="<?php echo $result_rmaadmins['view']; ?>" id="oit" title="<?php echo $button_view; ?>" class="btn btn-primary"><i class="fa fa-eye"></i></a>
                       
                      </td>
                    </tr>
                  <?php } ?>
                <?php } else { ?>
                  <tr>
                    <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
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
  <script type="text/javascript"><!--
    $('#button-filter').on('click', function() {
      url = 'index.php?route=sale/return&token=<?php echo $token; ?>';
      
      var filter_return_id = $('input[name=\'filter_return_id\']').val();
      
      if (filter_return_id) {
        url += '&filter_return_id=' + encodeURIComponent(filter_return_id);
      }
      
      var filter_order_id = $('input[name=\'filter_order_id\']').val();
      
      if (filter_order_id) {
        url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
      }	
        
      var filter_customer = $('input[name=\'filter_customer\']').val();
      
      if (filter_customer) {
        url += '&filter_customer=' + encodeURIComponent(filter_customer);
      }
      
      var filter_product = $('input[name=\'filter_product\']').val();
      
      if (filter_product) {
        url += '&filter_product=' + encodeURIComponent(filter_product);
      }

      var filter_model = $('input[name=\'filter_model\']').val();
      
      if (filter_model) {
        url += '&filter_model=' + encodeURIComponent(filter_model);
      }
        
      var filter_return_status_id = $('select[name=\'filter_return_status_id\']').val();
      
      if (filter_return_status_id != '*') {
        url += '&filter_return_status_id=' + encodeURIComponent(filter_return_status_id);
      }	
      
      var filter_date_added = $('input[name=\'filter_date_added\']').val();
      
      if (filter_date_added) {
        url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
      }

      var filter_date_modified = $('input[name=\'filter_date_modified\']').val();
      
      if (filter_date_modified) {
        url += '&filter_date_modified=' + encodeURIComponent(filter_date_modified);
      }

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

      var filter_record_limit = $('select[name=\'filter_record_limit\']').val();
        if (filter_record_limit) {
          url += '&filter_record_limit=' + encodeURIComponent(filter_record_limit);
        }
          
      //location = url;
      $('#tab_returns').load(url);  
    });
    $("#newReturn").click(function() {
      var html = '<div style="margin-top:30px;text-align:center"><i class="fa fa-spinner fa-spin"></i> Please Wait!</div>';
      $("#tab_returns").html(html);
      $('#tab_returns').load('index.php?route=catalog/wk_customer_orders_pos&token=' + token);  
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

$('input[name=\'filter_product\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_product\']').val(item['label']);
	}	
});

$('input[name=\'filter_model\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_model=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['model'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_model\']').val(item['label']);
	}	
});
//--></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?> 