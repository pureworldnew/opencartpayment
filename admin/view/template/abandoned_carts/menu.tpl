<link href="view/stylesheet/abandoned_carts.css" type="text/css" rel="stylesheet" />
<div class="text-center">
<ul class="custom-ul clearfix">
	<li class="dashboard_class">
		<a href="<?php echo $dashboard; ?>">
			<div class="icon"><i class="fa fa-dashboard"></i></div>
			<div class="labels"><?php echo $tab_dashboard; ?></div>
		</a>
	</li>
	<li class="abandoned_carts_class">
		<a href="<?php echo $abandoned_carts; ?>">
			<div class="icon"><i class="fa fa-shopping-cart"></i></div>
			<div class="labels"><?php echo $tab_abandoned_carts; ?></div>
		</a>
	</li>
	<li class="abandoned_carts_history_class">
		<a href="<?php echo $abandoned_carts_history; ?>">
			<div class="icon"><i class="fa fa-cart-arrow-down"></i></div>
			<div class="labels"><?php echo $tab_abandoned_carts_history; ?></div>
		</a>
	</li>
	<li class="mail_template_class">
		<a href="<?php echo $mail_template; ?>">
			<div class="icon"><i class="fa fa-envelope"></i></div>
			<div class="labels"><?php echo $tab_mail_template; ?></div>
		</a>
	</li>
	<li class="coupons_class">
		<a href="<?php echo $coupons; ?>">
			<div class="icon"><i class="fa fa-gift"></i></div>
			<div class="labels"><?php echo $tab_coupons; ?></div>
		</a>
	</li>
	<li class="settings_class">
		<a href="<?php echo $setting; ?>">
			<div class="icon"><i class="fa fa-gear"></i></div>
			<div class="labels"><?php echo $tab_setting; ?></div>
		</a>
	</li>
</ul>
</div>