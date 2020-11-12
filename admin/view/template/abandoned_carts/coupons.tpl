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
    <div class="panel panel-default">
      <div class="panel-body">
        <div class="form-horizontal">
					<?php echo $menu; ?>
					<div class="tab-content">
            <div class="tab-pane active" id="tab-coupons">
							<div class="table-responsive">
								<table class="table table-bordered table-hover">
									<thead>
										<tr>
											<td class="text-left"><?php if ($sort == 'cd.name') { ?>
												<a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
												<?php } else { ?>
												<a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
												<?php } ?></td>
											<td class="text-left"><?php if ($sort == 'c.code') { ?>
												<a href="<?php echo $sort_code; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_code; ?></a>
												<?php } else { ?>
												<a href="<?php echo $sort_code; ?>"><?php echo $column_code; ?></a>
												<?php } ?></td>
											<td class="text-right"><?php if ($sort == 'c.discount') { ?>
												<a href="<?php echo $sort_discount; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_discount; ?></a>
												<?php } else { ?>
												<a href="<?php echo $sort_discount; ?>"><?php echo $column_discount; ?></a>
												<?php } ?></td>
											<td class="text-left"><?php if ($sort == 'c.date_start') { ?>
												<a href="<?php echo $sort_date_start; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_start; ?></a>
												<?php } else { ?>
												<a href="<?php echo $sort_date_start; ?>"><?php echo $column_date_start; ?></a>
												<?php } ?></td>
											<td class="text-left"><?php if ($sort == 'c.date_end') { ?>
												<a href="<?php echo $sort_date_end; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_end; ?></a>
												<?php } else { ?>
												<a href="<?php echo $sort_date_end; ?>"><?php echo $column_date_end; ?></a>
												<?php } ?></td>
											<td class="text-right"><?php if ($sort == 'c.status') { ?>
												<a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
												<?php } else { ?>
												<a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
												<?php } ?></td>
										</tr>
									</thead>
									<tbody>
										<?php if ($coupons) { ?>
										<?php foreach ($coupons as $coupon) { ?>
										<tr>
											<td class="text-left"><?php echo $coupon['name']; ?></td>
											<td class="text-left"><?php echo $coupon['code']; ?></td>
											<td class="text-right"><?php echo $coupon['discount']; ?></td>
											<td class="text-left"><?php echo $coupon['date_start']; ?></td>
											<td class="text-left"><?php echo $coupon['date_end']; ?></td>
											<td class="text-right"><?php echo $coupon['status']; ?></td>
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
						</div>
					</div>
        </div>
				<div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function(){	$('.coupons_class').addClass('active'); });
</script>
<?php echo $footer; ?>
