<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-information" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <div class="panel panel-default">
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-information" class="form-horizontal">
					<?php echo $menu; ?>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-mail-template">
              <ul class="nav nav-tabs" id="language">
                <?php foreach ($languages as $language) { ?>
                <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                <?php } ?>
              </ul>
              <div class="tab-content">
                <?php foreach ($languages as $language) { ?>
                <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-title<?php echo $language['language_id']; ?>"><?php echo $entry_title; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="abandoned_template_description[<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($abandoned_template_description[$language['language_id']]) ? $abandoned_template_description[$language['language_id']]['title'] : ''; ?>" placeholder="<?php echo $entry_title; ?>" id="input-title<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_title[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_title[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
									<div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-subject<?php echo $language['language_id']; ?>"><?php echo $entry_subject; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="abandoned_template_description[<?php echo $language['language_id']; ?>][subject]" value="<?php echo isset($abandoned_template_description[$language['language_id']]) ? $abandoned_template_description[$language['language_id']]['title'] : ''; ?>" placeholder="<?php echo $entry_subject; ?>" id="input-subject<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_subject[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_subject[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-message<?php echo $language['language_id']; ?>"><?php echo $entry_message; ?></label>
                    <div class="col-sm-10">
                      <textarea name="abandoned_template_description[<?php echo $language['language_id']; ?>][message]" placeholder="<?php echo $entry_message; ?>" id="input-message<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($abandoned_template_description[$language['language_id']]) ? $abandoned_template_description[$language['language_id']]['message'] : ''; ?></textarea>
                      <?php if (isset($error_message[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_message[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
                <?php } ?>
             
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
									<div class="col-sm-10">
										<select name="status" id="input-status" class="form-control">
											<?php if ($status) { ?>
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
									<label class="col-sm-2 control-label" for="input-status">&nbsp;</label>
									<div class="col-sm-10">
										<div class="panel panel-default">
											<div class="panel-heading">
												<h3 class="panel-title">Email Short-codes</h3>
											</div>
											<div class="panel-body">
												<p><span>{firstname} = First Name</h4></span></p>
												<p><span>{lastname} - Last Name</h4></span></p>
												<p><span>{email} - E-Mail Address</h4></span></p>
												<p><span>{telephone} - Telephone</h4></span></p>
												<p><span>{customer_id} - Customer Id</h4></span></p>
												<p><span>{date_added} - Date Added</h4></span></p>
												<p><span>{logo} - Logo</h4></span></p>
												<p><span>{cart} - Cart Information (Products) </h4></span></p>
												<p><span>{store} - Store Name</h4></span></p>
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
  <script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
$('#input-message<?php echo $language['language_id']; ?>').summernote({
	height: 300
});
<?php } ?>
//--></script>
  <script type="text/javascript"><!--
$('#language a:first').tab('show');
//--></script>
<script type="text/javascript">
$(document).ready(function(){	$('.mail_template_class').addClass('active'); });
</script></div>
<?php echo $footer; ?>