<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-setting" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-setting" class="form-horizontal">
					<?php echo $menu; ?>
					<div class="tab-content">
            <div class="tab-pane active" id="tab-settings">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#action-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
								<li><a href="#action-crownjob" data-toggle="tab"><?php echo $tab_crown_job; ?></a></li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="action-general">
									<div class="form-group">
										<label class="col-sm-2 control-label" for="input-p-status"><?php echo $entry_deleteproduct_status; ?></label>
										<div class="col-sm-10">
											<select name="abandoned_carts_deleteproduct_status" id="input-p-status" class="form-control">
												<?php if ($abandoned_carts_deleteproduct_status) { ?>
												<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
												<option value="0"><?php echo $text_disabled; ?></option>
												<?php } else { ?>
												<option value="1"><?php echo $text_enabled; ?></option>
												<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label" for="input-p-status"><?php echo $entry_deletecart_status; ?></label>
										<div class="col-sm-10">
											<select name="abandoned_carts_deletecart_status" id="input-p-status" class="form-control">
												<?php if ($abandoned_carts_deletecart_status) { ?>
												<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
												<option value="0"><?php echo $text_disabled; ?></option>
												<?php } else { ?>
												<option value="1"><?php echo $text_enabled; ?></option>
												<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label" for="input-email-send"><?php echo $entry_email_send_status; ?></label>
										<div class="col-sm-10">
											<select name="abandoned_carts_email_send_status" id="input-email-send" class="form-control">
												<?php if ($abandoned_carts_email_send_status) { ?>
												<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
												<option value="0"><?php echo $text_disabled; ?></option>
												<?php } else { ?>
												<option value="1"><?php echo $text_enabled; ?></option>
												<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
								<div class="tab-pane" id="action-crownjob">
									<div class="form-group">
										<label class="col-sm-2 control-label" for="input-crownjob-status"><?php echo $entry_crownjob_status; ?></label>
										<div class="col-sm-10">
											<select name="abandoned_carts_crownjob_status" id="input-crownjob-status" class="form-control">
												<?php if ($abandoned_carts_crownjob_status) { ?>
												<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
												<option value="0"><?php echo $text_disabled; ?></option>
												<?php } else { ?>
												<option value="1"><?php echo $text_enabled; ?></option>
												<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label" for="input-email-status"><?php echo $entry_template; ?></label>
										<div class="col-sm-10">
											<select class="form-control" name="abandoned_carts_abandoned_template_id">
												<?php foreach($abandoned_templates as $abandoned_template) { ?>
												<?php if($abandoned_template['abandoned_template_id'] == $abandoned_carts_abandoned_template_id) { ?>
												<option selected="selected" value="<?php echo $abandoned_template['abandoned_template_id']; ?>"><?php echo $abandoned_template['title']; ?></option>
												<?php } else{ ?>
												<option selected="selected" value="<?php echo $abandoned_template['abandoned_template_id']; ?>"><?php echo $abandoned_template['title']; ?></option>
												<?php } ?>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label" for="input-url"><?php echo $entry_url; ?></label>
										<div class="col-sm-10">
											<div class="form-control">
												<?php echo $joburl; ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
        </form>
      </div>
    </div>
  </div>
<script type="text/javascript">
$(document).ready(function(){	$('.settings_class').addClass('active'); });
</script>
</div>
<?php echo $footer; ?>