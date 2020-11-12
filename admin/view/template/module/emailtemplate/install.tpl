<?php echo $header; ?>
<?php echo $column_left; ?>

<div id="content">
<div id="emailtemplate">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<?php if ($install) { ?><button type="submit" form="form-account" data-toggle="tooltip" title="<?php echo $button_install; ?>" class="btn btn-success"><i class="fa fa-plus"></i></button><?php } ?>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
			</div>

			<ul class="breadcrumb">
        		<?php $i=1; foreach ($breadcrumbs as $breadcrumb) { ?>
        		<?php if ($i == count($breadcrumbs)) { ?>
        			<li class="active"><?php echo $breadcrumb['text']; ?></li>
        		<?php } else { ?>
        			<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        		<?php } ?>
        		<?php $i++; } ?>
      		</ul>
		</div>
	</div>

	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-account" class="container-fluid">
	    <?php if ($error_warning) { ?>
	    	<div class="alert alert-danger">
				<i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
	      		<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
	    <?php } ?>

	    <?php if ($error_attention) { ?>
	    	<div class="alert alert-warning">
				<i class="fa fa-exclamation-circle"></i> <?php echo $error_attention; ?>
	      		<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
	    <?php } ?>

	    <?php if ($success) { ?>
	    	<div class="alert alert-success">
				<i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
	      		<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
	    <?php } ?>

	    <?php if ($install) { ?>
	    <div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-envelope-o"></i> <?php echo $heading_install; ?></h3>
			</div>

			<div class="panel-body">
	            <div class="list-group">
	            	<div class="list-group-item"><h4 style="margin-bottom:0"><?php echo $text_order; ?></h4></div>
	            	<label class="list-group-item">
	          			<input name="original_templates[order.customer]" value="1" checked="checked" type="checkbox" />
	          			<?php echo $template_order_customer; ?>
	          		</label>
	          		<label class="list-group-item">
	          			<input name="original_templates[order.admin]" value="1" checked="checked" type="checkbox" />
	          			<?php echo $template_order_admin; ?>
	          		</label>
	          		<label class="list-group-item">
	          			<input name="original_templates[order.update]" value="1" checked="checked" type="checkbox" />
	          			<?php echo $template_order_update; ?>
	          		</label>
	          		<label class="list-group-item">
	          			<input name="original_templates[order.voucher]" value="1" checked="checked" type="checkbox" />
	          			<?php echo $template_order_voucher; ?>
	          		</label>
	          		<label class="list-group-item">
	          			<input name="original_templates[admin.voucher]" value="1" checked="checked" type="checkbox" />
	          			<?php echo $template_admin_voucher; ?>
	          		</label>
	          		<label class="list-group-item">
	          			<input name="original_templates[order.return]" value="1" checked="checked" type="checkbox" />
	          			<?php echo $template_order_return; ?>
	          		</label>
	          		<label class="list-group-item">
	          			<input name="original_templates[admin.return_history]" value="1" checked="checked" type="checkbox" />
	          			<?php echo $template_admin_return_history; ?>
	          		</label>

	            	<div class="list-group-item"><h4 style="margin-bottom:0"><?php echo $text_openbay; ?></h4></div>
	            	<label class="list-group-item">
	          			<input name="original_templates[order.openbay.confirm]" value="1" checked="checked" type="checkbox" />
	          			<?php echo $template_order_openbay_confirm; ?>
	          		</label>
	            	<label class="list-group-item">
	          			<input name="original_templates[order.openbay.update]" value="1" checked="checked" type="checkbox" />
	          			<?php echo $template_order_openbay_update; ?>
	          		</label>
	            	<label class="list-group-item">
	          			<input name="original_templates[openbay.admin]" value="1" checked="checked" type="checkbox" />
	          			<?php echo $template_openbay_admin; ?>
	          		</label>

	            	<div class="list-group-item"><h4 style="margin-bottom:0"><?php echo $text_customer; ?></h4></div>
	            	<label class="list-group-item">
	          			<input name="original_templates[customer.register]" value="1" checked="checked" type="checkbox" />
	          			<?php echo $template_customer_register; ?>
	          		</label>
	          		<label class="list-group-item">
	          			<input name="original_templates[customer.forgotten]" value="1" checked="checked" type="checkbox" />
	          			<?php echo $template_customer_forgotten; ?>
	          		</label>
	          		<label class="list-group-item">
	          			<input name="original_templates[information.contact_customer]" value="1" checked="checked" type="checkbox" />
	          			<?php echo $template_information_contact_customer; ?>
	          		</label>
	          		<label class="list-group-item">
	          			<input name="original_templates[admin.customer_approve]" value="1" checked="checked" type="checkbox" />
	          			<?php echo $template_admin_customer_approve; ?>
	          		</label>
	          		<label class="list-group-item">
	          			<input name="original_templates[admin.customer_create]" value="1" checked="checked" type="checkbox" />
	          			<?php echo $template_admin_customer_create; ?>
	          		</label>
	          		<label class="list-group-item">
	          			<input name="original_templates[admin.customer_reward]" value="1" checked="checked" type="checkbox" />
	          			<?php echo $template_admin_customer_reward; ?>
	          		</label>
	          		<label class="list-group-item">
	          			<input name="original_templates[admin.customer_transaction]" value="1" checked="checked" type="checkbox" />
	          			<?php echo $template_admin_customer_transaction; ?>
	          		</label>

	          		<div class="list-group-item"><h4 style="margin-bottom:0"><?php echo $text_affiliate; ?></h4></div>
	          		<label class="list-group-item">
	          			<input name="original_templates[affiliate.register]" value="1" checked="checked" type="checkbox" />
	          			<?php echo $template_affiliate_register; ?>
	          		</label>
	          		<label class="list-group-item">
	          			<input name="original_templates[affiliate.forgotten]" value="1" checked="checked" type="checkbox" />
	          			<?php echo $template_affiliate_forgotten; ?>
	          		</label>
	          		<label class="list-group-item">
	          			<input name="original_templates[admin.affiliate_approve]" value="1" checked="checked" type="checkbox" />
	          			<?php echo $template_admin_affiliate_approve; ?>
	          		</label>
	          		<label class="list-group-item">
	          			<input name="original_templates[admin.affiliate_transaction]" value="1" checked="checked" type="checkbox" />
	          			<?php echo $template_admin_affiliate_transaction; ?>
	          		</label>

	          		<div class="list-group-item"><h4 style="margin-bottom:0"><?php echo $text_admin; ?></h4></div>
	          		<label class="list-group-item">
	          			<input name="original_templates[admin.newsletter]" value="1" checked="checked" type="checkbox" />
	          			<?php echo $template_admin_newsletter; ?>
	          		</label>
	          		<label class="list-group-item">
	          			<input name="original_templates[customer.register_admin]" value="1" checked="checked" type="checkbox" />
	          			<?php echo $template_customer_register_admin; ?>
	          		</label>
	          		<label class="list-group-item">
	          			<input name="original_templates[information.contact]" value="1" checked="checked" type="checkbox" />
	          			<?php echo $template_information_contact; ?>
	          		</label>
	          		<label class="list-group-item">
	          			<input name="original_templates[affiliate.register_admin]" value="1" checked="checked" type="checkbox" />
	          			<?php echo $template_affiliate_register_admin; ?>
	          		</label>
	          		<label class="list-group-item">
	          			<input name="original_templates[product.review]" value="1" checked="checked" type="checkbox" />
	          			<?php echo $template_order_admin; ?>
	          		</label>
	          	</div>
			</div>
		</div>
		<?php } ?>
	</form>
</div>
</div>

<?php echo $footer; ?>