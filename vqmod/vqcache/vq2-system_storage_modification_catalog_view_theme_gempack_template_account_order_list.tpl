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

        
<link rel="stylesheet" href="catalog/view/theme/default/stylesheet/bootstrap.min.css">
		
      
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
		  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
      <h1><?php echo $heading_title; ?></h1>
      <?php if ($orders) { ?>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <td class="text-right"><?php echo $column_order_id; ?></td>
              <td class="text-left hidden-xs"><?php echo $column_status; ?></td>
              <td class="text-left"><?php echo $column_date_added; ?></td>
              <td class="text-right hidden-xs"><?php echo $column_product; ?></td>
              <td class="text-left hidden-xs"><?php echo $column_customer; ?></td>
              <td class="text-right hidden-xs"><?php echo $column_total; ?></td>
              <td></td>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($orders as $order) { ?>
            <tr>
              <td class="text-right">#<?php echo $order['order_id']; ?></td>
              <td class="text-left hidden-xs"><?php echo $order['status']; ?></td>
              <td class="text-left"><?php echo $order['date_added']; ?></td>
              <td class="text-right hidden-xs"><?php echo $order['products']; ?></td>
              <td class="text-left hidden-xs"><?php echo $order['name']; ?></td>
              <td class="text-right hidden-xs"><?php echo $order['total']; ?></td>
              <td class="text-right"><?php if(!($pdf_invoice_invoiced && !$order['invoice_no'])){ ?><a href="<?php echo $order['pdf_href']; ?>" target="_new" data-toggle="tooltip" title="<?php echo $button_pdfinv_invoice; ?>" class="btn btn-info"><i class="fa fa-file-pdf-o"></i></a><?php } ?> <a href="<?php echo $order['href']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
      <div class="pagination-outer text-right"><?php echo $pagination ?></div>
      <?php } else { ?>
      <p><?php echo $text_empty; ?></p>
      <?php } ?>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
<?php echo $footer; ?>