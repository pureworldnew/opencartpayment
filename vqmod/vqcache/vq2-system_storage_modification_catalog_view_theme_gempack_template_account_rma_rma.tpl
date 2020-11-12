<?php echo $header; ?>

                <link type="text/css" rel="stylesheet" href="catalog/view/css/account.css" />
				<link type="text/css" rel="stylesheet" href="catalog/view/theme/default/stylesheet/ele-style.css" />
				<link type="text/css" rel="stylesheet" href="catalog/view/theme/default/stylesheet/dashboard.css" />
			
  <div class="row"><?php echo $column_left; ?>
        <?php
if ($column_left and $column_right) {
    $class="col-lg-8 col-md-6 col-sm-4 col-xs-12";
} elseif ($column_left or $column_right) {
     $class="col-lg-10 col-md-9 col-sm-8 col-xs-12";
} else {
     $class="col-xs-12";
}
?>
  <div id="content" class="<?php echo $class; ?>">
	  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"> </i> <?php echo $success; ?></div>
  <?php } ?>

        
<link rel="stylesheet" href="catalog/view/theme/default/stylesheet/bootstrap.min.css">
		
      
    <?php echo $content_top; ?>
    <h1 class="clearfix">
      <?php echo $heading_title; ?>
      <div class="pull-right">
        <a href="<?php echo $newrma; ?>" data-toggle="tooltip" title="" class="btn btn-primary"><?php echo $text_add; ?></a>
      </div>
    </h1>
      <div class="well">
        <legend>Check my recent returns</legend>
        <div class="row">
          <div class="col-sm-3">
            <div class="form-group">
              <label class="control-label" for="input-id"><?php echo $text_id; ?></label>
              <input type="text" name="filter_id" value="<?php echo $filter_id; ?>" placeholder="<?php echo $text_id; ?>" id="input-id" class="form-control" />
            </div>
              <input type="hidden" name="filter_qty" value="<?php echo $filter_qty; ?>" id="input-qty" class="form-control" />
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label class="control-label" for="input-order"><?php echo $text_orderid; ?></label>
              <input type="text" name="filter_order" value="<?php echo $filter_order; ?>" placeholder="<?php echo $text_orderid; ?>" id="input-order" class="form-control" />
            </div>
          </div>
           <div class="col-sm-3">
            <div class="form-group">
              <label class="control-label" for="input-date-from"><?php echo $text_date_from; ?></label>
              <div class="input-group date">
              	<input type="date" name="filter_date_from" value="<?php echo $filter_date_from; ?>" placeholder="<?php echo $text_date_from; ?>" id="input-date-from" class="form-control" />
              </div>
            </div>
          </div> 
          <div class="col-sm-3">
            <div class="form-group">
              <label class="control-label" for="input-date-to"><?php echo $text_date_to; ?></label>
              <div class="input-group date">
              	<input type="date" name="filter_date_to" value="<?php echo $filter_date_to; ?>" placeholder="<?php echo $text_date_to; ?>" id="input-date-to" class="form-control" /> 
              </div>
            </div>
            <div class="pull-right">
              <a onclick="filter();" class="btn btn-primary"><?php echo $button_filter; ?></a>
            </div>
          </div>
        </div>
      </div>
      <form action="<?php echo $newrma; ?>" method="post" enctype="multipart/form-data" id="form-product">
        <div class="table-responsive">
          <table class="table table-bordered table-hover" style="width:100%;">
            <thead>
              <tr>
                <td>
                    <?php if ($sort == 'wro.id') { ?>
                      <a href="<?php echo $sort_id; ?>" class="<?php echo strtolower($order); ?>"><?php echo $text_id; ?></a>
                    <?php } else { ?>
                      <a href="<?php echo $sort_id; ?>" > <?php echo $text_id; ?> </a>
                    <?php } ?>
                </td>
                <td class="text-left">
                   <?php if ($sort == 'wro.order_id') { ?>
                      <a href="<?php echo $sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $text_orderid; ?></a>
                    <?php } else { ?>
                      <a href="<?php echo $sort_order; ?>" > <?php echo $text_orderid; ?> </a>
                    <?php } ?>
                </td>
                <td class="text-center hidden-sm hidden-xs"><?php echo $text_product; ?></td>
                <td class="text-center hidden-sm hidden-xs"><?php echo $text_reason; ?></td>
                <td class="text-left hidden-sm hidden-xs">
                  <?php if ($sort == 'wrp.quantity') { ?>
                    <a href="<?php echo $sort_qty; ?>" class="<?php echo strtolower($order); ?>"><?php echo $text_quantity; ?></a>
                  <?php } else { ?>
                    <a href="<?php echo $sort_qty; ?>" > <?php echo $text_quantity; ?> </a>
                  <?php } ?>
                </td>
                <td class="text-left hidden-sm hidden-xs">
                  <?php if ($sort == 'wrs.order_status_id') { ?>
                    <a href="<?php echo $sort_rma_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $text_status; ?></a>
                  <?php } else { ?>
                    <a href="<?php echo $sort_rma_status; ?>" > <?php echo $text_status; ?> </a>
                  <?php } ?>
                </td>
                <td class="text-left">
                  <?php if ($sort == 'wro.date') { ?>
                    <a href="<?php echo $sort_date; ?>" class="<?php echo strtolower($order); ?>"><?php echo $text_date; ?></a>
                  <?php } else { ?>
                    <a href="<?php echo $sort_date; ?>" > <?php echo $text_date; ?> </a>
                  <?php } ?>
                </td>
                <td class="text-center" style="Width:12%;"><?php echo $text_action; ?></td>
              </tr>
            </thead>
            <tbody>
            <?php if($rma_ress){ ?>
            <?php foreach ($rma_ress as $res) { ?>
              <tr>
                <td><?php echo $res['id']; ?></td>
                <td class="text-left"><?php echo $res['order_id']; ?></td>
                <td class="text-left hidden-sm hidden-xs"><?php echo $res['product']; ?></td>
                <td class="text-left hidden-sm hidden-xs"><?php echo $res['reason']; ?></td>
                <td class="text-left hidden-sm hidden-xs"> <?php echo $res['quantity']; ?></td>
                <td class="text-left hidden-sm hidden-xs" style="color:<?php echo $res['color']; ?>;"><?php echo $res['rma_status']; ?></td>
                <td class="text-left" id="rma_date"><?php echo $res['date']; ?></td>
                <td class="text-center">
                    <?php foreach ($res['action'] as $action) { ?>
                      <a href="<?php echo $action['href']; ?>" class="btn btn-primary" data-toggle="tooltip" title="<?php echo $action['text'] ;?>"><i class="fa fa-eye"></i></a>
                      <?php if(!$res['cancel_rma'] AND !$res['solve_rma']){ ?>
                        <a onclick="confirm('<?php echo $text_r_u_sure; ?>') ? location = '<?php echo $res['cancel_rma_link']; ?>' : false;" class="btn btn-info" data-toggle="tooltip" title="<?php echo $text_cancel ;?>"><i class="fa fa-times"></i></a>
                      <?php } ?>
                    <?php } ?>
                </td>
              </tr>
            <?php } } else{ ?>
              <tr>
                <td colspan="8" class="text-center"><?php echo $text_empty; ?></td>
              </tr>
            </tbody>
            <?php } ?>
          </table>
        </div>
      </form>
      <div class="row">
        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
        <div class="col-sm-6 text-right"><?php echo $results; ?></div>
      </div>
    <?php echo $content_bottom; ?>
  </div>
  <?php echo $column_right; ?>
  </div>
<?php echo $footer; ?>
<script type="text/javascript">
$('.row input').keydown(function(e) {
  if (e.keyCode == 13) {
    filter();
  }
});

$('.datetime').datetimepicker({
  pickTime: false
});
function clear_filter() {
  url = 'index.php?route=account/rma/rma';
  location = url;
}
function filter() {
  url = 'index.php?route=account/rma/rma';

  var filter_id = $('input[name=\'filter_id\']').val();

  if (filter_id) {
    url += '&filter_id=' + encodeURIComponent(filter_id);
  }

  var filter_order = $('input[name=\'filter_order\']').val();

  if (filter_order) {
    url += '&filter_order=' + encodeURIComponent(filter_order);
  }

  var filter_qty = $('input[name=\'filter_qty\']').val();

  if (filter_qty) {
    url += '&filter_qty=' + encodeURIComponent(filter_qty);
  }

  var filter_date = $('input[name=\'filter_date\']').val();

  if (filter_date) {
    url += '&filter_date=' + encodeURIComponent(filter_date);
  }
  
  var filter_date_from = $('input[name=\'filter_date_from\']').val();

  if (filter_date_from) {
    url += '&filter_date_from=' + encodeURIComponent(filter_date_from);
  }
  
  var filter_date_to = $('input[name=\'filter_date_to\']').val();

  if (filter_date_to) {
    url += '&filter_date_to=' + encodeURIComponent(filter_date_to);
  }



  location = url;
}
</script>
