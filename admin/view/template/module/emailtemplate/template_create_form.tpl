<?php echo $header; ?>

<?php echo $column_left; ?>

<div id="content">
<div id="emailtemplate">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button id="submit_form_button" type="submit" form="form-emailtemplate" data-toggle="tooltip" title="<?php echo $button_create; ?>" class="btn btn-success"><i class="fa fa-save"></i></button>

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

	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-emailtemplate" class="container-fluid form-horizontal">
	    <?php if (!empty($error_warning)) { ?>
	    	<div class="alert alert-danger">
				<i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
	      		<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
	    <?php } ?>

	    <?php if (!empty($error_attention)) { ?>
	    	<div class="alert alert-warning">
				<i class="fa fa-exclamation-circle"></i> <?php echo $error_attention; ?>
	      		<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
	    <?php } ?>

		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-envelope"></i> <?php echo $heading_template_create; ?></h3>
			</div>

			<div class="panel-body">
				<div class="form-group required">
					<label class="col-sm-2 control-label" for="emailtemplate_key"><?php echo $entry_key; ?></label>
					<div class="col-sm-5">
						<select name="emailtemplate_key_select" id="emailtemplate_key_select" class="form-control">
							<option value=""><?php echo $text_select; ?></option>
							<?php foreach($emailtemplate_keys as $row){ ?>
							<option value="<?php echo $row['value']; ?>"<?php if ($emailtemplate['key'] == $row['value'] || $emailtemplate['key_select'] == $row['value']) echo ' selected="selected"'; ?>><?php echo $row['label']; ?></option>
							<?php } ?>
						</select>
						<span class="help-block" style="margin-bottom: 0"><?php echo $help_template_key; ?></span>
					</div>
					<div class="col-sm-5">
						<input class="form-control" type="text" name="emailtemplate_key" value="<?php echo $emailtemplate['key']; ?>" placeholder="<?php echo $text_placeholder_custom; ?>" id="emailtemplate_key" />
					</div>
					<?php if (isset($error_emailtemplate_key)) { ?><div class="col-sm-10 col-sm-push-2"><span class="help-block text-danger"><?php echo $error_emailtemplate_key; ?></span></div><?php } ?>
				</div>

				<div class="form-group required">
					<label class="col-sm-2 control-label" for="emailtemplate_label"><?php echo $entry_label; ?></label>
					<div class="col-sm-10">
						<input required="required" type="text" name="emailtemplate_label" value="<?php echo $emailtemplate['label']; ?>" id="emailtemplate_label" class="form-control" />
						<?php if (isset($error_emailtemplate_label)) { ?><span class="text-danger"><?php echo $error_emailtemplate_label; ?></span><?php } ?>
					</div>
				</div>
			</div>
		</div>
    </form>
</div>
</div>

<?php echo $footer; ?>