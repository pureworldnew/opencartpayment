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
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
		 <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
      <h1><?php echo $heading_title; ?></h1>
      <?php if ($recurrings) { ?>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <td class="text-left"><?php echo $column_recurring_id; ?></td>
              <td class="text-left"><?php echo $column_date_added; ?></td>
              <td class="text-left"><?php echo $column_status; ?></td>
              <td class="text-left"><?php echo $column_product; ?></td>
              <td class="text-right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($recurrings as $recurring) { ?>
              <tr>
                <td class="text-left">#<?php echo $recurring['id']; ?></td>
                <td class="text-left"><?php echo $recurring['date_added']; ?></td>
                <td class="text-left"><?php echo $status_types[$recurring['status']]; ?></td>
                <td class="text-left"><?php echo $recurring['name']; ?></td>
                <td class="text-right"><a href="<?php echo $recurring['href']; ?>" class="btn btn-info"><?php echo $button_view; ?></a></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
      <div class="text-right"><?php echo $pagination; ?></div>
      <?php } else { ?>
      <p><?php echo $text_empty; ?></p>
      <?php } ?>
      <div class="buttons clearfix">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>

<?php echo $footer; ?>