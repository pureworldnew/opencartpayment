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
            <div class="tab-pane active" id="tab-dashboard">
							
							<div class="row">
								<div class="col-lg-4 col-md-4">
                    <div class="panel panel-blue">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-shopping-cart fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="boofonts"><?php echo $total_abandoned_carts; ?></div>
                                    <div><?php echo $text_abandoned_carts ?></div>
                                </div>
                            </div>
                        </div>
                        <a href="<?php echo $abandoned_carts ?>">
                            <div class="panel-footer">
                                <span class="pull-left"><?php echo $text_view; ?></span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
								<div class="col-lg-4 col-md-4">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-tags fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="boofonts"><?php echo $total_products; ?></div>
                                    <div><?php echo $total_abandoned_cart_products; ?></div>
                                </div>
                            </div>
                        </div>
                        <a href="<?php echo $cart_products ?>">
                            <div class="panel-footer">
                                <span class="pull-left"><?php echo $text_view; ?></span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
								<div class="col-lg-4 col-md-4">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-envelope fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="boofonts"><?php echo $total_notified_abandoned_carts; ?></div>
                                    <div><?php echo $text_notified_abandoned_carts; ?></div>
                                </div>
                            </div>
                        </div>
                        <a href="<?php echo $notified_abandoned_carts ?>">
                            <div class="panel-footer">
                                <span class="pull-left"><?php echo $text_view; ?></span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
								<div class="col-lg-4 col-md-4">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-cart-arrow-down fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="boofonts"><?php echo $total_abandoned_carts_histories; ?></div>
                                    <div><?php echo $tab_abandoned_carts_history; ?></div>
                                </div>
                            </div>
                        </div>
                        <a href="<?php echo $abandoned_carts_histories ?>">
                            <div class="panel-footer">
                                <span class="pull-left"><?php echo $text_view; ?></span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
								<div class="col-lg-4 col-md-4">
                    <div class="panel panel-pink">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-gift fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="boofonts"><?php echo $total_coupons_histories; ?></div>
                                    <div><?php echo $text_use_coupons ?></div>
                                </div>
                            </div>
                        </div>
                        <a href="<?php echo $coupons_histories ?>">
                            <div class="panel-footer">
                                <span class="pull-left"><?php echo $text_view; ?></span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
								<div class="col-lg-4 col-md-4">
                    <div class="panel panel-gray">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-times fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="boofonts"><?php echo $total_missing_orders; ?></div>
                                    <div><?php echo $text_missing_orders; ?></div>
                                </div>
                            </div>
                        </div>
                        <a href="<?php echo $missing_orders ?>">
                            <div class="panel-footer">
                                <span class="pull-left"><?php echo $text_view; ?></span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
							</div>
						</div>
					</div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function(){	$('.dashboard_class').addClass('active'); });
</script>
<?php echo $footer; ?>
