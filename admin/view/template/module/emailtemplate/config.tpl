<?php echo $header; ?>
<?php echo $column_left; ?>

<div id="content">
<div id="emailtemplate">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button id="submit_form_button" type="submit" form="form-emailtemplate" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>

				<?php if (isset($action_delete)) { ?>
					<a href="<?php echo $action_delete; ?>" data-confirm="<?php echo $text_confirm; ?>" class="btn btn-danger" data-toggle="tooltip" title="<?php echo $button_delete; ?>"><i class="fa fa-trash-o"></i></a>
				<?php } ?>

				<?php if (isset($action_configs) || isset($action_default)) { ?>
					<div class="btn-group">
						<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-cogs"></i> <?php echo $button_configs; ?> <span class="caret"></span></button>
						<ul class="dropdown-menu dropdown-menu-right" role="menu">
							<?php if (isset($action_default)) { ?><li><a href="<?php echo $action_default; ?>"><?php echo $button_default; ?></a></li><?php } ?>
							<?php if (isset($action_default) && isset($action_configs)) { ?><li class="divider"></li><?php } ?>
							<?php if (isset($action_configs)) { ?>
								<?php foreach($action_configs as $row) { ?>
						    		<li><a href="<?php echo $row['url']; ?>"><?php echo $row['name']; ?></a></li>
						    	<?php } ?>
					    	<?php } ?>
					  	</ul>
					</div>
				<?php } ?>

				<?php if (isset($templates_restore)) { ?>
				<div class="btn-group">
					<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><?php echo $button_restore; ?> <span class="caret"></span></button>
					<ul class="dropdown-menu dropdown-menu-right" role="menu">
						<?php foreach($templates_restore as $row) { ?>
				    	<li><a href="<?php echo $row['url']; ?>"><?php echo $row['name']; ?></a></li>
				    	<?php } ?>
				  	</ul>
				</div>
				<?php } ?>

				<a href="<?php echo $action_insert_config; ?>" class="btn btn-success" data-toggle="tooltip" title="<?php echo $text_create_config; ?>"><i class="fa fa-plus"></i></a>

				<?php if (isset($preview_order_id)) { ?>
					<a href="javascript:void(0)" class="btn btn-warning" id="template-test" data-toggle="tooltip" title="<?php echo $button_test; ?>"><i class="fa fa-envelope-o"></i></a>
				<?php } ?>

				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
			</div>

			<h1><?php echo $heading_config; ?><?php if ($id == 1) { echo ' - ' . $text_default; } ?></h1>

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
	    <?php if (isset($error_warning) && $error_warning) { ?>
	    	<div class="alert alert-danger">
				<i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
	      		<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
	    <?php } ?>

	    <?php if (isset($error_attention) && $error_attention) { ?>
	    	<div class="alert alert-warning">
				<i class="fa fa-exclamation-circle"></i> <?php echo $error_attention; ?>
	      		<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
	    <?php } ?>

	    <?php if (isset($success) && $success) { ?>
	    	<div class="alert alert-success">
				<i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
	      		<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
	    <?php } ?>

		<div class="panel">
			<div class="panel-nav-tabs">
				<ul class="nav nav-tabs" id="config-tabs">
				    <li class="active"><a href="javascript:void(0)" data-target="#tab-settings" data-toggle="tab"><i class="fa fa-wrench hidden-xs"></i> <?php echo $heading_settings; ?></a></li>
    				<li><a href="javascript:void(0)" data-target="#tab-style" data-toggle="tab"><i class="fa fa-paint-brush hidden-xs"></i> <?php echo $heading_style; ?></a></li>
    				<li><a href="javascript:void(0)" data-target="#tab-header" data-toggle="tab"><i class="fa fa-envelope hidden-xs"></i> <?php echo $heading_header; ?></a></li>
    				<li><a href="javascript:void(0)" data-target="#tab-footer" data-toggle="tab"><i class="fa fa-envelope hidden-xs"></i> <?php echo $heading_footer; ?></a></li>
    				<li><a href="javascript:void(0)" data-target="#tab-showcase" data-toggle="tab"><i class="fa fa-rocket hidden-xs"></i> <?php echo $heading_showcase; ?></a></li>
  				</ul>
			</div>

			<div class="tab-content">
				<div class="tab-pane fade in active" id="tab-settings">
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="emailtemplate_config_name"><?php echo $entry_label; ?></label>
						<div class="col-sm-10">
							<input class="form-control" id="emailtemplate_config_name" name="emailtemplate_config_name" value="<?php echo $emailtemplate_config['name']; ?>" required="required" type="text" />
							<?php if (isset($error_emailtemplate_config_name)) { ?><span class="text-danger"><?php echo $error_emailtemplate_config_name; ?></span><?php } ?>
						</div>
					</div>

					<?php if ($id != 1) { ?>
    					<?php if (!empty($languages)) { ?>
    					<div class="form-group">
							<label class="col-sm-2 control-label" for="language_id"><?php echo $entry_language; ?></label>
							<div class="col-sm-10">
								<select class="form-control" name="language_id" id="language_id">
									<option value=""><?php echo $text_select; ?></option>
									<?php foreach($languages as $language) { ?>
									<option value="<?php echo $language['language_id']; ?>"<?php if ($language['language_id'] === $emailtemplate_config['language_id']) { ?> selected="selected"<?php } ?>>
										<?php echo $language['name']; ?>
									</option>
									<?php } ?>
								</select>
							</div>
						</div>
						<?php } ?>

    					<?php if (!empty($stores)) { ?>
    					<div class="form-group">
							<label class="col-sm-2 control-label" for="store_id"><?php echo $entry_store; ?></label>
							<div class="col-sm-10">
								<select class="form-control" name="store_id" id="store_id">
									<option value="-1"><?php echo $text_select; ?></option>
									<?php foreach($stores as $store) { ?>
									<option value="<?php echo $store['store_id']; ?>"<?php if ($store['store_id'] == $emailtemplate_config['store_id']) { ?> selected="selected"<?php } ?>>
										<?php echo $store['store_name']; ?>
									</option>
									<?php } ?>
								</select>
							</div>
						</div>
						<?php } ?>

    					<?php if (!empty($customer_groups)) { ?>
    					<div class="form-group">
							<label class="col-sm-2 control-label" for="customer_group_id"><?php echo $entry_customer_group; ?></label>
							<div class="col-sm-10">
								<select class="form-control" name="customer_group_id" id="customer_group_id">
									<option value="-1"><?php echo $text_select; ?></option>
									<?php foreach($customer_groups as $customer_group) { ?>
									<option value="<?php echo $customer_group['customer_group_id']; ?>"<?php if ($customer_group['customer_group_id'] == $emailtemplate_config['customer_group_id']) { ?> selected="selected"<?php } ?>>
										<?php echo $customer_group['name']; ?>
									</option>
									<?php } ?>
								</select>
							</div>
						</div>
						<?php } ?>
					<?php } ?>

					<?php if (count($themes) > 1) { ?>
                  	<div class="form-group">
						<label class="col-sm-2 control-label" for="emailtemplate_config_theme"><?php echo $entry_theme; ?></label>
						<div class="col-sm-10">
							<select class="form-control" name="emailtemplate_config_theme" id="emailtemplate_config_theme">
								<?php foreach($themes as $theme) { ?>
								<option value="<?php echo $theme; ?>"<?php if ($theme == $emailtemplate_config['theme']) { ?> selected="selected"<?php } ?>><?php echo $theme; ?></option>
								<?php } ?>
							</select>
							<?php if (isset($error_emailtemplate_config_theme)) {?><span class="text-danger"><?php echo $error_emailtemplate_config_theme; ?></span><?php } ?>
						</div>
					</div>
					<?php } ?>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="emailtemplate_config_wrapper_tpl"><?php echo $entry_wrapper; ?></label>
						<div class="col-sm-10">
							<input class="form-control" id="emailtemplate_config_wrapper_tpl" name="emailtemplate_config_wrapper_tpl" value="<?php echo $emailtemplate_config['wrapper_tpl']; ?>" type="text" />
							<?php if (isset($error_emailtemplate_config_name)) { ?><span class="text-danger"><?php echo $error_emailtemplate_config_name; ?></span><?php } ?>
						</div>
					</div>

					<fieldset>
						<div class="row heading"><div class="col-xs-push-2 col-xs-10"><h3><?php echo $heading_logs; ?></h3></div></div>

						<div class="form-group form-group-radio">
							<label class="col-sm-2 control-label" for="emailtemplate_config_log"><?php echo $entry_log; ?></label>
							<div class="col-sm-10">
								<input name="emailtemplate_config_log" id="emailtemplate_config_log" data-control="checkbox" data-off-label="<?php echo $text_no; ?>" data-on-label="<?php echo $text_yes; ?>" value="1" type="checkbox"<?php echo ($emailtemplate_config['log'] == 1) ? ' checked="checked"' : ''; ?>/>
							</div>
						</div>

						<div class="form-group form-group-radio">
							<label class="col-sm-2 control-label" for="emailtemplate_config_view_browser_theme"><?php echo $entry_view_browser_theme; ?></label>
							<div class="col-sm-10">
								<input name="emailtemplate_config_view_browser_theme" id="emailtemplate_config_view_browser_theme" data-control="checkbox" data-off-label="<?php echo $text_no; ?>" data-on-label="<?php echo $text_yes; ?>" value="1" type="checkbox"<?php echo ($emailtemplate_config['view_browser_theme'] == 1) ? ' checked="checked"' : ''; ?>/>
							</div>
						</div>

						<div class="form-group form-group-radio">
							<label class="col-sm-2 control-label" for="emailtemplate_config_log_read"><?php echo $entry_log_read; ?></label>
							<div class="col-sm-10">
								<input name="emailtemplate_config_log_read" id="emailtemplate_config_log_read" data-control="checkbox" data-off-label="<?php echo $text_no; ?>" data-on-label="<?php echo $text_yes; ?>" value="1" type="checkbox"<?php echo ($emailtemplate_config['log_read'] == 1) ? ' checked="checked"' : ''; ?>/>
							</div>
						</div>
					</fieldset>

					<fieldset>
						<div class="row heading"><div class="col-xs-push-2 col-xs-10"><h3><?php echo $heading_tracking; ?></h3></div></div>

						<div class="form-group form-group-radio">
							<label class="col-sm-2 control-label" for="emailtemplate_config_tracking"><?php echo $entry_tracking; ?></label>
							<div class="col-sm-10">
								<input name="emailtemplate_config_tracking" id="emailtemplate_config_tracking" data-control="checkbox" data-off-label="<?php echo $text_no; ?>" data-on-label="<?php echo $text_yes; ?>" value="1" type="checkbox"<?php echo ($emailtemplate_config['tracking'] == 1) ? ' checked="checked"' : ''; ?>/>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label" for="emailtemplate_config_tracking_campaign_name"><?php echo $entry_tracking_campaign_name; ?></label>
							<div class="col-sm-10">
								<input class="form-control" id="emailtemplate_config_tracking_campaign_name" name="emailtemplate_config_tracking_campaign_name" value="<?php echo $emailtemplate_config['tracking_campaign_name']; ?>" type="text" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label" for="emailtemplate_config_tracking_campaign_term"><?php echo $entry_tracking_campaign_term; ?></label>
							<div class="col-sm-10">
								<input class="form-control" id="emailtemplate_config_tracking_campaign_term" name="emailtemplate_config_tracking_campaign_term" value="<?php echo $emailtemplate_config['tracking_campaign_term']; ?>" type="text" />
							</div>
						</div>
					</fieldset>

					<fieldset>
						<div class="row heading"><div class="col-xs-push-2 col-xs-10"><h3><?php echo $text_order; ?></h3></div></div>

						<div class="form-group form-group-radio">
							<label class="col-sm-2 control-label" for="emailtemplate_config_table_quantity"><?php echo $entry_table_quantity; ?></label>
							<div class="col-sm-10">
								<input name="emailtemplate_config_table_quantity" id="emailtemplate_config_table_quantity" data-control="checkbox" data-off-label="<?php echo $text_no; ?>" data-on-label="<?php echo $text_yes; ?>" value="1" type="checkbox"<?php echo ($emailtemplate_config['table_quantity'] == 1) ? ' checked="checked"' : ''; ?>/>
							</div>
						</div>

					</fieldset>
				</div><!-- #tab-settings -->

				<div id="tab-style" class="tab-pane fade">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="emailtemplate_config_style"><?php echo $entry_style; ?></label>
						<div class="col-sm-10">
							<select class="form-control" name="emailtemplate_config_style" id="emailtemplate_config_style">
								<option value=''><?php echo $text_select; ?></option>
								<option value="page"<?php if ('page' == $emailtemplate_config['style']) { ?> selected="selected"<?php } ?>>Page with shadow</option>
								<option value="white"<?php if ('white' == $emailtemplate_config['style']) { ?> selected="selected"<?php } ?>>Page with white body</option>
								<option value="border"<?php if ('border' == $emailtemplate_config['style']) { ?> selected="selected"<?php } ?>>Page with border</option>
								<option value="clean"<?php if ('clean' == $emailtemplate_config['style']) { ?> selected="selected"<?php } ?>>Clean</option>
								<option value="sections"<?php if ('sections' == $emailtemplate_config['style']) { ?> selected="selected"<?php } ?>>Sections</option>
								<option value="inner_page"<?php if ('inner_page' == $emailtemplate_config['style']) { ?> selected="selected"<?php } ?>>Inner Page</option>
							</select>
							<?php if (isset($error_emailtemplate_config_style)) {?><span class="text-danger"><?php echo $error_emailtemplate_config_style; ?></span><?php } ?>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="emailtemplate_config_email_width"><?php echo $entry_email_width; ?></label>
						<div class="col-sm-10">
							<div class="input-group">
								<input class="form-control" id="emailtemplate_config_email_width" name="emailtemplate_config_email_width" value="<?php echo $emailtemplate_config['email_width']; ?>" type="number" />
								<span class="input-group-addon">px</span>
							</div>
						</div>
					</div>

					<div class="form-group form-group-radio">
						<label class="col-sm-2 control-label" for="emailtemplate_config_email_responsive"><?php echo $entry_responsive; ?></label>
						<div class="col-sm-10">
							<input name="emailtemplate_config_email_responsive" id="emailtemplate_config_email_responsive" data-control="checkbox" data-off-label="<?php echo $text_no; ?>" data-on-label="<?php echo $text_yes; ?>" value="1" type="checkbox"<?php echo ($emailtemplate_config['email_responsive'] == 1) ? ' checked="checked"' : ''; ?>/>
						  	<?php if (isset($error_emailtemplate_config_email_responsive)) { ?><span class="text-danger"><?php echo $error_emailtemplate_config_email_responsive; ?></span><?php } ?>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="emailtemplate_config_text_align"><?php echo $entry_text_align; ?></label>
						<div class="col-sm-10">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-align-<?php echo $emailtemplate_config['text_align']; ?>"></i></span>
								<select class="form-control" name="emailtemplate_config_text_align" id="emailtemplate_config_text_align">
									<option value="left"<?php if ($emailtemplate_config['text_align'] == 'left') { ?> selected="selected"<?php } ?>><?php echo $text_left; ?></option>
									<option value="right"<?php if ($emailtemplate_config['text_align'] == 'right') { ?> selected="selected"<?php } ?>><?php echo $text_right; ?></option>
								</select>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="emailtemplate_config_page_align"><?php echo $entry_page_align; ?></label>
						<div class="col-sm-10">
							<select class="form-control " name="emailtemplate_config_page_align" id="emailtemplate_config_page_align">
								<option value="center"<?php if ($emailtemplate_config['page_align'] == 'center') { ?> selected="selected"<?php } ?>><?php echo $text_center; ?></option>
								<option value="left"<?php if ($emailtemplate_config['page_align'] == 'left') { ?> selected="selected"<?php } ?>><?php echo $text_left; ?></option>
								<option value="right"<?php if ($emailtemplate_config['page_align'] == 'right') { ?> selected="selected"<?php } ?>><?php echo $text_right; ?></option>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_body_bg_image; ?></label>
                		<div class="col-sm-4">
                			<a href="javascript:void(0)" id="thumb-body-bg" data-toggle="image" class="img-thumbnail"><img class="img-responsive" src="<?php echo $emailtemplate_config['body_bg_image_thumb']; ?>" alt="" data-placeholder="<?php echo $no_image; ?>" /></a>
                  			<input type="hidden" name="emailtemplate_config_body_bg_image" value="<?php echo $emailtemplate_config['body_bg_image']; ?>" id="input-body-bg" />
                  			<?php if (isset($error_emailtemplate_config_body_bg_image)) {?><span class="text-danger"><?php echo $error_emailtemplate_config_body_bg_image; ?></span><?php } ?>
                		</div>
						<div class="col-sm-3">
							<select class="form-control" name="emailtemplate_config_body_bg_image_repeat">
								<option value="no-repeat"<?php if($emailtemplate_config['body_bg_image_repeat'] == 'no-repeat' || $emailtemplate_config['body_bg_image_repeat'] == ''){ ?> selected="selected"<?php } ?>>No Repeat</option>
								<option value="repeat"<?php if($emailtemplate_config['body_bg_image_repeat'] == 'repeat'){ ?> selected="selected"<?php } ?>>Repeat</option>
								<option value="repeat-x"<?php if($emailtemplate_config['body_bg_image_repeat'] == 'repeat-x'){ ?> selected="selected"<?php } ?>>Repeat Horizontal</option>
								<option value="repeat-y"<?php if($emailtemplate_config['body_bg_image_repeat'] == 'repeat-y'){ ?> selected="selected"<?php } ?>>Repeat Vertical</option>
							</select>
						</div>
						<div class="col-sm-3">
							<input type="text" class="form-control" name="emailtemplate_config_body_bg_image_position" value="<?php echo $emailtemplate_config['body_bg_image_position']; ?>" placeholder="E.g: center top" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="emailtemplate_config_body_bg_color"><?php echo $entry_body_bg_color; ?></label>
						<div class="col-sm-10">
							<div class="input-group input-colorpicker">
								<span class="input-group-addon preview"><i<?php if ($emailtemplate_config['body_bg_color']) { ?> style="background-color:<?php echo $emailtemplate_config['body_bg_color']; ?>;"<?php } ?>></i></span>
								<input class="form-control " type="text" id="emailtemplate_config_body_bg_color" name="emailtemplate_config_body_bg_color" value="<?php echo $emailtemplate_config['body_bg_color']; ?>" />
								<span class="input-group-addon"><i class="fa fa-eyedropper"></i></span>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="emailtemplate_config_page_bg_color"><?php echo $entry_page_bg_color; ?></label>
						<div class="col-sm-10">
							<div class="input-group input-colorpicker">
								<span class="input-group-addon preview"><i<?php if ($emailtemplate_config['page_bg_color']) { ?> style="background-color:<?php echo $emailtemplate_config['page_bg_color']; ?>;"<?php } ?>></i></span>
								<input class="form-control " type="text" id="emailtemplate_config_page_bg_color" name="emailtemplate_config_page_bg_color" value="<?php echo $emailtemplate_config['page_bg_color']; ?>" />
								<span class="input-group-addon"><i class="fa fa-eyedropper"></i></span>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="emailtemplate_config_body_font_color"><?php echo $entry_body_font_color; ?></label>
						<div class="col-sm-10">
							<div class="input-group input-colorpicker">
								<span class="input-group-addon preview"><i<?php if ($emailtemplate_config['body_font_color']) { ?> style="background-color:<?php echo $emailtemplate_config['body_font_color']; ?>;"<?php } ?>></i></span>
								<input class="form-control " type="text" id="emailtemplate_config_body_font_color" name="emailtemplate_config_body_font_color" value="<?php echo $emailtemplate_config['body_font_color']; ?>" />
								<span class="input-group-addon"><i class="fa fa-eyedropper"></i></span>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="emailtemplate_config_body_link_color"><?php echo $entry_body_link_color; ?></label>
						<div class="col-sm-10">
							<div class="input-group input-colorpicker">
								<span class="input-group-addon preview"><i<?php if ($emailtemplate_config['body_link_color']) { ?> style="background-color:<?php echo $emailtemplate_config['body_link_color']; ?>;"<?php } ?>></i></span>
								<input class="form-control " type="text" id="emailtemplate_config_body_link_color" name="emailtemplate_config_body_link_color" value="<?php echo $emailtemplate_config['body_link_color']; ?>" />
								<span class="input-group-addon"><i class="fa fa-eyedropper"></i></span>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="emailtemplate_config_body_heading_color"><?php echo $entry_body_heading_color; ?></label>
						<div class="col-sm-10">
							<div class="input-group input-colorpicker">
								<span class="input-group-addon preview"><i<?php if ($emailtemplate_config['body_heading_color']) { ?> style="background-color:<?php echo $emailtemplate_config['body_heading_color']; ?>;"<?php } ?>></i></span>
								<input class="form-control " type="text" id="emailtemplate_config_body_heading_color" name="emailtemplate_config_body_heading_color" value="<?php echo $emailtemplate_config['body_heading_color']; ?>" />
								<span class="input-group-addon"><i class="fa fa-eyedropper"></i></span>
							</div>
						</div>
					</div>

					<?php if ($emailtemplate_config['style'] == 'sections') { ?>
					<fieldset>
						<div class="row heading"><div class="col-xs-push-2 col-xs-10"><h3><?php echo $heading_sections; ?></h3></div></div>

						<div class="form-group">
							<label class="col-sm-2 control-label" for="emailtemplate_config_head_section_bg_color"><?php echo $entry_head; ?></label>
							<div class="col-sm-10">
								<div class="input-group input-colorpicker">
									<span class="input-group-addon preview"><i<?php if ($emailtemplate_config['head_section_bg_color']) { ?> style="background-color:<?php echo $emailtemplate_config['head_section_bg_color']; ?>;"<?php } ?>></i></span>
									<input class="form-control " type="text" id="emailtemplate_config_head_section_bg_color" name="emailtemplate_config_head_section_bg_color" value="<?php echo $emailtemplate_config['head_section_bg_color']; ?>" />
									<span class="input-group-addon"><i class="fa fa-eyedropper"></i></span>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label" for="emailtemplate_config_header_section_bg_color"><?php echo $entry_header; ?></label>
							<div class="col-sm-10">
								<div class="input-group input-colorpicker">
									<span class="input-group-addon preview"><i<?php if ($emailtemplate_config['header_section_bg_color']) { ?> style="background-color:<?php echo $emailtemplate_config['header_section_bg_color']; ?>;"<?php } ?>></i></span>
									<input class="form-control " type="text" id="emailtemplate_config_header_section_bg_color" name="emailtemplate_config_header_section_bg_color" value="<?php echo $emailtemplate_config['header_section_bg_color']; ?>" />
									<span class="input-group-addon"><i class="fa fa-eyedropper"></i></span>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label" for="emailtemplate_config_body_section_bg_color"><?php echo $entry_body; ?></label>
							<div class="col-sm-10">
								<div class="input-group input-colorpicker">
									<span class="input-group-addon preview"><i<?php if ($emailtemplate_config['body_section_bg_color']) { ?> style="background-color:<?php echo $emailtemplate_config['body_section_bg_color']; ?>;"<?php } ?>></i></span>
									<input class="form-control " type="text" id="emailtemplate_config_body_section_bg_color" name="emailtemplate_config_body_section_bg_color" value="<?php echo $emailtemplate_config['body_section_bg_color']; ?>" />
									<span class="input-group-addon"><i class="fa fa-eyedropper"></i></span>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label" for="emailtemplate_config_showcase_section_bg_color"><?php echo $entry_showcase; ?></label>
							<div class="col-sm-10">
								<div class="input-group input-colorpicker">
									<span class="input-group-addon preview"><i<?php if ($emailtemplate_config['showcase_section_bg_color']) { ?> style="background-color:<?php echo $emailtemplate_config['showcase_section_bg_color']; ?>;"<?php } ?>></i></span>
									<input class="form-control " type="text" id="emailtemplate_config_showcase_section_bg_color" name="emailtemplate_config_showcase_section_bg_color" value="<?php echo $emailtemplate_config['showcase_section_bg_color']; ?>" />
									<span class="input-group-addon"><i class="fa fa-eyedropper"></i></span>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label" for="emailtemplate_config_footer_section_bg_color"><?php echo $entry_footer; ?></label>
							<div class="col-sm-10">
								<div class="input-group input-colorpicker">
									<span class="input-group-addon preview"><i<?php if ($emailtemplate_config['footer_section_bg_color']) { ?> style="background-color:<?php echo $emailtemplate_config['footer_section_bg_color']; ?>;"<?php } ?>></i></span>
									<input class="form-control " type="text" id="emailtemplate_config_footer_section_bg_color" name="emailtemplate_config_footer_section_bg_color" value="<?php echo $emailtemplate_config['footer_section_bg_color']; ?>" />
									<span class="input-group-addon"><i class="fa fa-eyedropper"></i></span>
								</div>
							</div>
						</div>
					</fieldset>
					<?php } ?>

					<div class="row">
						<label class="col-sm-2 control-label"><?php echo $heading_shadow; ?></label>
						<div class="col-sm-10">
							<a href="#fieldset-shadow" class="btn btn-info" data-toggle="collapse">Edit</a>
						</div>
					</div>

					<fieldset class="collapse" id="fieldset-shadow" style="margin-top: 20px;">
						<div class="form-group">
							<label class="col-sm-2 control-label"><?php echo $text_top; ?></label>
							<div class="col-sm-5 col-lg-2">
								<label class="control-label" for="emailtemplate_config_shadow_top_length"><?php echo $entry_height; ?></label>
								<div class="input-group">
									<input class="form-control " name="emailtemplate_config_shadow_top[length]" id="emailtemplate_config_shadow_top_length" value="<?php echo isset($emailtemplate_config['shadow_top']['length']) ? $emailtemplate_config['shadow_top']['length'] : ''; ?>" type="number" />
									<span class="input-group-addon">px</span>
								</div>
							</div>
							<div class="col-sm-5 col-lg-2">
								<label class="control-label" for="emailtemplate_config_shadow_top_overlap"><?php echo $entry_overlap; ?></label>
								<div class="input-group">
									<input class="form-control " name="emailtemplate_config_shadow_top[overlap]" id="emailtemplate_config_shadow_top_overlap" value="<?php echo isset($emailtemplate_config['shadow_top']['overlap']) ? $emailtemplate_config['shadow_top']['overlap'] : ''; ?>" type="number" />
									<span class="input-group-addon">px</span>
								</div>
							</div>
							<div class="col-sm-5 col-sm-offset-2 col-lg-2 col-lg-offset-0">
								<label class="control-label" for="emailtemplate_config_shadow_top_end"><?php echo $entry_start; ?></label>
								<div class="input-group input-colorpicker">
									<span class="input-group-addon preview"><i<?php if ($emailtemplate_config['shadow_top']['start']) { ?> style="background-color:<?php echo $emailtemplate_config['shadow_top']['start']; ?>;"<?php } ?>></i></span>
									<input class="form-control " type="text" id="emailtemplate_config_shadow_top_end" name="emailtemplate_config_shadow_top[start]" value="<?php echo $emailtemplate_config['shadow_top']['start']; ?>" />
									<span class="input-group-addon"><i class="fa fa-eyedropper"></i></span>
								</div>
							</div>
							<div class="col-sm-5 col-lg-2">
								<label class="control-label" for="emailtemplate_config_shadow_top_end"><?php echo $entry_end; ?></label>
								<div class="input-group input-colorpicker">
									<span class="input-group-addon preview"><i<?php if ($emailtemplate_config['shadow_top']['end']) { ?> style="background-color:<?php echo $emailtemplate_config['shadow_top']['end']; ?>;"<?php } ?>></i></span>
									<input class="form-control " type="text" id="emailtemplate_config_shadow_top_end" name="emailtemplate_config_shadow_top[end]" value="<?php echo $emailtemplate_config['shadow_top']['end']; ?>" />
									<span class="input-group-addon"><i class="fa fa-eyedropper"></i></span>
								</div>
							</div>
						</div>

						<?php if ($emailtemplate_config['shadow_bottom']) { ?>
						<div class="form-group">
							<label class="col-sm-2 control-label"><?php echo $text_bottom; ?></label>
							<div class="col-sm-5 col-lg-2">
								<label class="control-label" for="emailtemplate_config_shadow_bottom_length"><?php echo $entry_height; ?></label>
								<div class="input-group">
									<input class="form-control " name="emailtemplate_config_shadow_bottom[length]" id="emailtemplate_config_shadow_bottom_length" value="<?php echo isset($emailtemplate_config['shadow_bottom']['length']) ? $emailtemplate_config['shadow_bottom']['length'] : ''; ?>" type="number" />
									<span class="input-group-addon">px</span>
								</div>
							</div>
							<div class="col-sm-5 col-lg-2">
								<label class="control-label" for="emailtemplate_config_shadow_bottom_overlap"><?php echo $entry_overlap; ?></label>
								<div class="input-group">
									<input class="form-control " name="emailtemplate_config_shadow_bottom[overlap]" id="emailtemplate_config_shadow_bottom_overlap" value="<?php echo isset($emailtemplate_config['shadow_bottom']['overlap']) ? $emailtemplate_config['shadow_bottom']['overlap'] : ''; ?>" type="number" />
									<span class="input-group-addon">px</span>
								</div>
							</div>
							<div class="col-sm-5 col-sm-offset-2 col-lg-2 col-lg-offset-0">
								<label class="control-label" for="emailtemplate_config_shadow_bottom_end"><?php echo $entry_start; ?></label>
								<div class="input-group input-colorpicker">
									<span class="input-group-addon preview"><i<?php if ($emailtemplate_config['shadow_bottom']['start']) { ?> style="background-color:<?php echo $emailtemplate_config['shadow_bottom']['start']; ?>;"<?php } ?>></i></span>
									<input class="form-control " type="text" id="emailtemplate_config_shadow_bottom_end" name="emailtemplate_config_shadow_bottom[start]" value="<?php echo $emailtemplate_config['shadow_bottom']['start']; ?>" />
									<span class="input-group-addon"><i class="fa fa-eyedropper"></i></span>
								</div>
							</div>
							<div class="col-sm-5 col-lg-2">
								<label class="control-label" for="emailtemplate_config_shadow_bottom_end"><?php echo $entry_end; ?></label>
								<div class="input-group input-colorpicker">
									<span class="input-group-addon preview"><i<?php if ($emailtemplate_config['shadow_bottom']['end']) { ?> style="background-color:<?php echo $emailtemplate_config['shadow_bottom']['end']; ?>;"<?php } ?>></i></span>
									<input class="form-control " type="text" id="emailtemplate_config_shadow_bottom_end" name="emailtemplate_config_shadow_bottom[end]" value="<?php echo $emailtemplate_config['shadow_bottom']['end']; ?>" />
									<span class="input-group-addon"><i class="fa fa-eyedropper"></i></span>
								</div>
							</div>
						</div>
						<?php } ?>

						<div class="form-group">
							<label class="col-sm-2 control-label"><?php echo $text_left; ?></label>
							<div class="col-sm-5 col-lg-2">
								<label class="control-label" for="emailtemplate_config_shadow_left_length"><?php echo $entry_width; ?></label>
								<div class="input-group">
									<input class="form-control " name="emailtemplate_config_shadow_left[length]" id="emailtemplate_config_shadow_left_length" value="<?php echo isset($emailtemplate_config['shadow_left']['length']) ? $emailtemplate_config['shadow_left']['length'] : ''; ?>" type="number" />
									<span class="input-group-addon">px</span>
								</div>
							</div>
							<div class="col-sm-5 col-lg-2">
								<label class="control-label" for="emailtemplate_config_shadow_left_overlap"><?php echo $entry_overlap; ?></label>
								<div class="input-group">
									<input class="form-control " name="emailtemplate_config_shadow_left[overlap]" id="emailtemplate_config_shadow_left_overlap" value="<?php echo isset($emailtemplate_config['shadow_left']['overlap']) ? $emailtemplate_config['shadow_left']['overlap'] : ''; ?>" type="number" />
									<span class="input-group-addon">px</span>
								</div>
							</div>
							<div class="col-sm-5 col-sm-offset-2 col-lg-2 col-lg-offset-0">
								<label class="control-label" for="emailtemplate_config_shadow_left_end"><?php echo $entry_start; ?></label>
								<div class="input-group input-colorpicker">
									<span class="input-group-addon preview"><i<?php if ($emailtemplate_config['shadow_left']['start']) { ?> style="background-color:<?php echo $emailtemplate_config['shadow_left']['start']; ?>;"<?php } ?>></i></span>
									<input class="form-control " type="text" id="emailtemplate_config_shadow_left_end" name="emailtemplate_config_shadow_left[start]" value="<?php echo $emailtemplate_config['shadow_left']['start']; ?>" />
									<span class="input-group-addon"><i class="fa fa-eyedropper"></i></span>
								</div>
							</div>
							<div class="col-sm-5 col-lg-2">
								<label class="control-label" for="emailtemplate_config_shadow_left_end"><?php echo $entry_end; ?></label>
								<div class="input-group input-colorpicker">
									<span class="input-group-addon preview"><i<?php if ($emailtemplate_config['shadow_left']['end']) { ?> style="background-color:<?php echo $emailtemplate_config['shadow_left']['end']; ?>;"<?php } ?>></i></span>
									<input class="form-control " type="text" id="emailtemplate_config_shadow_left_end" name="emailtemplate_config_shadow_left[end]" value="<?php echo $emailtemplate_config['shadow_left']['end']; ?>" />
									<span class="input-group-addon"><i class="fa fa-eyedropper"></i></span>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label"><?php echo $text_right; ?></label>
							<div class="col-sm-5 col-lg-2">
								<label class="control-label" for="emailtemplate_config_shadow_right_length"><?php echo $entry_width; ?></label>
								<div class="input-group">
									<input class="form-control" name="emailtemplate_config_shadow_right[length]" id="emailtemplate_config_shadow_right_length" value="<?php echo isset($emailtemplate_config['shadow_right']['length']) ? $emailtemplate_config['shadow_right']['length'] : ''; ?>" type="number" />
									<span class="input-group-addon">px</span>
								</div>
							</div>
							<div class="col-sm-5 col-lg-2">
								<label class="control-label" for="emailtemplate_config_shadow_right_overlap"><?php echo $entry_overlap; ?></label>
								<div class="input-group">
									<input class="form-control" name="emailtemplate_config_shadow_right[overlap]" id="emailtemplate_config_shadow_right_overlap" value="<?php echo isset($emailtemplate_config['shadow_right']['overlap']) ? $emailtemplate_config['shadow_right']['overlap'] : ''; ?>" type="number" />
									<span class="input-group-addon">px</span>
								</div>
							</div>
							<div class="col-sm-5 col-sm-offset-2 col-lg-2 col-lg-offset-0">
								<label class="control-label" for="emailtemplate_config_shadow_right_end"><?php echo $entry_start; ?></label>
								<div class="input-group input-colorpicker">
									<span class="input-group-addon preview"><i<?php if ($emailtemplate_config['shadow_right']['start']) { ?> style="background-color:<?php echo $emailtemplate_config['shadow_right']['start']; ?>;"<?php } ?>></i></span>
									<input type="text" class="form-control" id="emailtemplate_config_shadow_right_end" name="emailtemplate_config_shadow_right[start]" value="<?php echo $emailtemplate_config['shadow_right']['start']; ?>" />
									<span class="input-group-addon"><i class="fa fa-eyedropper"></i></span>
								</div>
							</div>
							<div class="col-sm-5 col-lg-2">
								<label class="control-label" for="emailtemplate_config_shadow_right_end"><?php echo $entry_end; ?></label>
								<div class="input-group input-colorpicker">
									<span class="input-group-addon preview"><i<?php if ($emailtemplate_config['shadow_right']['end']) { ?> style="background-color:<?php echo $emailtemplate_config['shadow_right']['end']; ?>;"<?php } ?>></i></span>
									<input type="text" class="form-control" id="emailtemplate_config_shadow_right_end" name="emailtemplate_config_shadow_right[end]" value="<?php echo $emailtemplate_config['shadow_right']['end']; ?>" />
									<span class="input-group-addon"><i class="fa fa-eyedropper"></i></span>
								</div>
							</div>
						</div>

						<div class="row">
							<label class="col-sm-2 control-label"><?php echo $entry_corner_image; ?></label>
							<div class="col-xs-6 col-md-3 col-lg-2">
								<a href="javascript:void(0)" id="thumb-shadow-top-left" data-toggle="image" class="img-thumbnail" style="vertical-align:middle"><img class="img-responsive" src="<?php echo $emailtemplate_config['shadow_top']['left_thumb']; ?>" alt="" data-placeholder="<?php echo $no_shadow_image; ?>" /></a>
								<label class="control-label" for="image-shadow-top-left"><?php echo $entry_top_left; ?></label>
                  				<input type="hidden" name="emailtemplate_config_shadow_top[left_img]" value="<?php echo $emailtemplate_config['shadow_top']['left_img']; ?>" id="image-shadow-top-left" />
							</div>
							<div class="col-xs-6 col-md-3 col-lg-2">
								<a href="javascript:void(0)" id="thumb-shadow-top-right" data-toggle="image" class="img-thumbnail" style="vertical-align:middle"><img class="img-responsive" src="<?php echo $emailtemplate_config['shadow_top']['right_thumb']; ?>" alt="" data-placeholder="<?php echo $no_shadow_image; ?>" /></a>
								<label class="control-label" for="image-shadow-top-right"><?php echo $entry_top_right; ?></label>
                  				<input type="hidden" name="emailtemplate_config_shadow_top[right_img]" value="<?php echo $emailtemplate_config['shadow_top']['right_img']; ?>" id="image-shadow-top-right" />
							</div>
							<div class="col-sm-5 col-sm-offset-2 col-lg-2 col-lg-offset-0">
								<a href="javascript:void(0)" id="thumb-shadow-bottom-left" data-toggle="image" class="img-thumbnail" style="vertical-align:middle"><img class="img-responsive" src="<?php echo $emailtemplate_config['shadow_bottom']['left_thumb']; ?>" alt="" data-placeholder="<?php echo $no_shadow_image; ?>" /></a>
								<label class="control-label" for="image-shadow-bottom-left"><?php echo $entry_bottom_left; ?></label>
								<input type="hidden" name="emailtemplate_config_shadow_bottom[left_img]" value="<?php echo $emailtemplate_config['shadow_bottom']['left_img']; ?>" id="image-shadow-bottom-left" />
							</div>
							<div class="col-xs-6 col-md-3 col-lg-2">
								<a href="javascript:void(0)" id="thumb-shadow-bottom-right" data-toggle="image" class="img-thumbnail" style="vertical-align:middle"><img class="img-responsive" src="<?php echo $emailtemplate_config['shadow_bottom']['right_thumb']; ?>" alt="" data-placeholder="<?php echo $no_shadow_image; ?>" /></a>
								<label class="control-label" for="image-shadow-bottom-right"><?php echo $entry_bottom_right; ?></label>
                  				<input type="hidden" name="emailtemplate_config_shadow_bottom[right_img]" value="<?php echo $emailtemplate_config['shadow_bottom']['right_img']; ?>" id="image-shadow-bottom-right" />
							</div>
						</div>
					</fieldset>
				</div><!-- #tab-style -->

				<div id="tab-header" class="tab-pane fade">
					<div class="form-group">
                   		<label class="col-sm-2 control-label" for="emailtemplate_config_view_browser_text"><?php echo $entry_view_browser_text; ?></label>
                   		<div class="col-sm-10">
                   			<textarea class="wysiwyg_editor" name="emailtemplate_config_view_browser_text" id="emailtemplate_config_view_browser_text" data-height="40"><?php echo $emailtemplate_config['view_browser_text']; ?></textarea>
                   		</div>
                  	</div>

					<div class="form-group">
                   		<label class="col-sm-2 control-label" for="emailtemplate_config_head_text"><?php echo $entry_head_text; ?></label>
                   		<div class="col-sm-10">
                   			<textarea class="wysiwyg_editor" name="emailtemplate_config_head_text" id="emailtemplate_config_head_text"><?php echo $emailtemplate_config['head_text']; ?></textarea>
                   		</div>
                  	</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="emailtemplate_config_header_bg_color"><?php echo $entry_header_bg_color; ?></label>
						<div class="col-sm-4">
							<div class="input-group input-colorpicker">
								<span class="input-group-addon preview"><i<?php if ($emailtemplate_config['header_bg_color']) { ?> style="background-color:<?php echo $emailtemplate_config['header_bg_color']; ?>;"<?php } ?>></i></span>
								<input type="text" class="form-control" id="emailtemplate_config_header_bg_color" name="emailtemplate_config_header_bg_color" value="<?php echo $emailtemplate_config['header_bg_color']; ?>" />
								<span class="input-group-addon"><i class="fa fa-eyedropper"></i></span>
							</div>
						</div>
						<label class="col-sm-2 control-label" for="emailtemplate_config_header_border_color"><?php echo $entry_header_border_color; ?></label>
						<div class="col-sm-4">
							<div class="input-group input-colorpicker">
								<span class="input-group-addon preview"><i<?php if ($emailtemplate_config['header_bg_color']) { ?> style="background-color:<?php echo $emailtemplate_config['header_border_color']; ?>;"<?php } ?>></i></span>
								<input type="text" class="form-control" id="emailtemplate_config_header_border_color" name="emailtemplate_config_header_border_color" value="<?php echo $emailtemplate_config['header_border_color']; ?>" />
								<span class="input-group-addon"><i class="fa fa-eyedropper"></i></span>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_header_bg_image; ?></label>
                			<div class="col-sm-10">
                				<a href="javascript:void(0)" id="thumb-header-bg" data-toggle="image" class="img-thumbnail"><img class="img-responsive" src="<?php echo $emailtemplate_config['header_bg_image_thumb']; ?>" alt="" data-placeholder="<?php echo $no_image; ?>" /></a>
                  				<input type="hidden" name="emailtemplate_config_header_bg_image" value="<?php echo $emailtemplate_config['header_bg_image']; ?>" id="input-header-bg" />
                  				<?php if (isset($error_emailtemplate_config_header_bg_image)) {?><span class="text-danger"><?php echo $error_emailtemplate_config_header_bg_image; ?></span><?php } ?>
                			</div>
              			</div>

              			<div class="form-group">
							<label class="col-sm-2 control-label" for="emailtemplate_config_header_height"><?php echo $entry_height; ?></label>
						<div class="col-sm-10">
							<div class="input-group">
								<input class="form-control" id="emailtemplate_config_header_height" name="emailtemplate_config_header_height" value="<?php echo $emailtemplate_config['header_height']; ?>" type="number" />
								<span class="input-group-addon">px</span>
							</div>
						</div>
					</div>

					<fieldset>
						<div class="row heading"><div class="col-xs-push-2 col-xs-10"><h3><?php echo $text_logo; ?></h3></div></div>

						<div class="form-group">
							<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_logo; ?>"><?php echo $entry_logo; ?></span></label>
                				<div class="col-sm-10">
                					<a href="javascript:void(0)" id="thumb-logo" data-toggle="image" class="img-thumbnail"><img class="img-responsive" src="<?php echo $emailtemplate_config['logo_thumb']; ?>" alt="" data-placeholder="<?php echo $no_image; ?>" /></a>
                  					<input type="hidden" name="emailtemplate_config_logo" value="<?php echo $emailtemplate_config['logo']; ?>" id="input-logo" />
                  					<?php if (isset($error_emailtemplate_config_logo)) {?><span class="text-danger"><?php echo $error_emailtemplate_config_logo; ?></span><?php } ?>
                				</div>
              				</div>

							<div class="form-group">
								<label class="col-sm-2 control-label" for="emailtemplate_config_logo_width"><?php echo $entry_logo_resize_options; ?></label>
							<div class="col-sm-5">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-arrows-h "></i></span>
									<input class="form-control" name="emailtemplate_config_logo_width" id="emailtemplate_config_logo_width" value="<?php echo $emailtemplate_config['logo_width']; ?>" type="number" />
								</div>
							</div>
							<div class="col-sm-5">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-arrows-v"></i></span>
									<input class="form-control" name="emailtemplate_config_logo_height" id="emailtemplate_config_logo_height" value="<?php echo $emailtemplate_config['logo_height']; ?>" type="number" />
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label" for="emailtemplate_config_logo_align"><?php echo $text_align; ?></label>
							<div class="col-sm-10 col-lg-5">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-align-<?php echo $emailtemplate_config['logo_align']; ?>"></i></span>
									<select name="emailtemplate_config_logo_align" id="emailtemplate_config_logo_align" class="form-control">
										<option value="center"<?php if ($emailtemplate_config['logo_align'] == 'center') { ?> selected="selected"<?php } ?>><?php echo $text_center; ?></option>
										<option value="left"<?php if ($emailtemplate_config['logo_align'] == 'left') { ?> selected="selected"<?php } ?>><?php echo $text_left; ?></option>
										<option value="right"<?php if ($emailtemplate_config['logo_align'] == 'right') { ?> selected="selected"<?php } ?>><?php echo $text_right; ?></option>
									</select>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label" for="emailtemplate_config_logo_font_size"><?php echo $text_font_size; ?></label>
							<div class="col-sm-10 col-lg-5">
								<div class="input-group">
									<input class="form-control" id="emailtemplate_config_logo_font_size" name="emailtemplate_config_logo_font_size" value="<?php echo $emailtemplate_config['logo_font_size']; ?>" type="number" />
									<span class="input-group-addon">px</span>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label" for="emailtemplate_config_logo_font_color"><?php echo $text_text_color; ?></label>
							<div class="col-sm-10">
								<div class="input-group input-colorpicker">
									<span class="input-group-addon preview"><i<?php if ($emailtemplate_config['logo_font_color']) { ?> style="background-color:<?php echo $emailtemplate_config['logo_font_color']; ?>;"<?php } ?>></i></span>
									<input class="form-control" type="text" id="emailtemplate_config_logo_font_color" name="emailtemplate_config_logo_font_color" value="<?php echo $emailtemplate_config['logo_font_color']; ?>" />
									<span class="input-group-addon"><i class="fa fa-eyedropper"></i></span>
								</div>
							</div>
						</div>
					</fieldset>
				</div><!-- #tab-header -->

				<div id="tab-footer" class="tab-pane fade">
					<div class="form-group">
                   		<label class="col-sm-2 control-label" for="emailtemplate_config_page_footer_text"><?php echo $entry_page_footer_text; ?></label>
                   		<div class="col-sm-10">
                   			<textarea class="wysiwyg_editor" name="emailtemplate_config_page_footer_text" id="emailtemplate_config_page_footer_text"><?php echo $emailtemplate_config['page_footer_text']; ?></textarea>
                   		</div>
                  	</div>

					<div class="form-group">
                   		<label class="col-sm-2 control-label" for="emailtemplate_config_footer_text"><?php echo $entry_footer_text; ?></label>
                   		<div class="col-sm-10">
                   			<textarea class="wysiwyg_editor" name="emailtemplate_config_footer_text" id="emailtemplate_config_footer_text"><?php echo $emailtemplate_config['footer_text']; ?></textarea>
                   		</div>
                  	</div>

                  	<div class="form-group">
						<label class="col-sm-2 control-label" for="emailtemplate_config_email_width"><?php echo $text_height; ?></label>
						<div class="col-sm-10">
							<div class="input-group">
								<input class="form-control" id="emailtemplate_config_footer_height" name="emailtemplate_config_footer_height" value="<?php echo $emailtemplate_config['footer_height']; ?>" type="number" />
								<span class="input-group-addon">px</span>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="emailtemplate_config_footer_font_color"><?php echo $text_text_color; ?></label>
						<div class="col-sm-10">
							<div class="input-group input-colorpicker">
								<span class="input-group-addon preview"><i<?php if ($emailtemplate_config['footer_font_color']) { ?> style="background-color:<?php echo $emailtemplate_config['footer_font_color']; ?>;"<?php } ?>></i></span>
								<input type="text" class="form-control" id="emailtemplate_config_footer_font_color" name="emailtemplate_config_footer_font_color" value="<?php echo $emailtemplate_config['footer_font_color']; ?>" />
								<span class="input-group-addon"><i class="fa fa-eyedropper"></i></span>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="emailtemplate_config_footer_align"><?php echo $text_align; ?></label>
						<div class="col-sm-5">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-align-<?php echo $emailtemplate_config['footer_align']; ?>"></i></span>
								<select name="emailtemplate_config_footer_align" id="emailtemplate_config_footer_align" class="form-control">
									<option value="center"<?php if ($emailtemplate_config['footer_align'] == 'center') { ?> selected="selected"<?php } ?>><?php echo $text_center; ?></option>
									<option value="left"<?php if ($emailtemplate_config['footer_align'] == 'left') { ?> selected="selected"<?php } ?>><?php echo $text_left; ?></option>
									<option value="right"<?php if ($emailtemplate_config['footer_align'] == 'right') { ?> selected="selected"<?php } ?>><?php echo $text_right; ?></option>
								</select>
							</div>
						</div>
						<div class="col-sm-5">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-arrows-v"></i></span>
								<select name="emailtemplate_config_footer_valign" id="emailtemplate_config_footer_valign" class="form-control">
									<option value="top"<?php if ($emailtemplate_config['footer_valign'] == 'top') { ?> selected="selected"<?php } ?>><?php echo $text_top; ?></option>
									<option value="middle"<?php if ($emailtemplate_config['footer_valign'] == 'middle') { ?> selected="selected"<?php } ?>><?php echo $text_middle; ?></option>
									<option value="bottom"<?php if ($emailtemplate_config['footer_valign'] == 'bottom') { ?> selected="selected"<?php } ?>><?php echo $text_bottom; ?></option>
									<option value="baseline"<?php if ($emailtemplate_config['footer_valign'] == 'baseline') { ?> selected="selected"<?php } ?>><?php echo $text_baseline; ?></option>
								</select>
							</div>
						</div>
					</div>
				</div><!-- #tab-footer -->

				<div id="tab-showcase" class="tab-pane fade">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="emailtemplate_config_showcase_title"><?php echo $entry_title; ?></label>
						<div class="col-sm-10">
							<input class="form-control" id="emailtemplate_config_showcase_title" name="emailtemplate_config_showcase_title" value="<?php echo $emailtemplate_config['showcase_title']; ?>" type="text" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="emailtemplate_config_showcase"><?php echo $entry_showcase; ?></label>
						<div class="col-sm-10">
							<select class="form-control" name="emailtemplate_config_showcase" id="emailtemplate_config_showcase">
								<option value=""><?php echo $text_none; ?></option>
								<option value="latest"<?php if ($emailtemplate_config['showcase'] == 'latest') { ?> selected="selected"<?php } ?>><?php echo $text_latest; ?></option>
								<option value="bestsellers"<?php if ($emailtemplate_config['showcase'] == 'bestsellers') { ?> selected="selected"<?php } ?>><?php echo $text_bestsellers; ?></option>
								<option value="popular"<?php if ($emailtemplate_config['showcase'] == 'popular') { ?> selected="selected"<?php } ?>><?php echo $text_popular; ?></option>
								<option value="specials"<?php if ($emailtemplate_config['showcase'] == 'specials') { ?> selected="selected"<?php } ?>><?php echo $text_specials; ?></option>
								<option value="products"<?php if ($emailtemplate_config['showcase'] == 'products') { ?> selected="selected"<?php } ?>><?php echo $text_products; ?></option>
							</select>
							<input type="hidden" name="emailtemplate_config_showcase_selection" value="<?php echo $emailtemplate_config['showcase_selection']; ?>" />
						</div>
					</div>

					<div class="showcase_products form-group<?php if ($emailtemplate_config['showcase'] != 'products') echo ' hide' ?>">
						<label class="col-sm-2 control-label" for="emailtemplate_config_showcase_selection"><?php echo $entry_selection; ?></label>
						<div class="col-sm-10">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-search"></i></span>
								<input class="form-control input-autocomplete-product" data-field="input[name=emailtemplate_config_showcase_selection]" data-output="#emailtemplate_config_showcase_selection" type="text" name="" value="" autocomplete="off" placeholder="<?php echo $text_search; ?>" />
							</div>

							<div id="emailtemplate_config_showcase_selection" class="<?php if (empty($showcase_selection)) echo ' hide' ?>">
								<ul class="list-group list-group-small">
								<?php if (isset($showcase_selection)) { ?>
									<?php foreach($showcase_selection as $row) { ?>
										<li class="list-group-item" data-id="<?php echo $row['product_id']; ?>"><a href="javascript:void(0)" class="badge remove list-group-item-danger"><i class="fa fa-times"></i></a> <?php echo $row['name']; ?></li>
									<?php } ?>
								<?php } ?>
								</ul>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="emailtemplate_config_showcase_limit"><?php echo $entry_limit; ?></label>
						<div class="col-sm-10">
							<input class="form-control" id="emailtemplate_config_showcase_limit" name="emailtemplate_config_showcase_limit" value="<?php echo $emailtemplate_config['showcase_limit']; ?>" type="number" />
						</div>
					</div>

					<div class="form-group form-group-radio">
						<label class="col-sm-2 control-label" for="emailtemplate_config_showcase_related"><?php echo $entry_related_products; ?></label>
						<div class="col-sm-10">
							<input name="emailtemplate_config_showcase_related" id="emailtemplate_config_showcase_related" data-control="checkbox" data-off-label="<?php echo $text_no; ?>" data-on-label="<?php echo $text_yes; ?>" value="1" type="checkbox"<?php echo ($emailtemplate_config['showcase_related'] == 1) ? ' checked="checked"' : ''; ?>/>
						</div>
					</div>
				</div><!-- #tab-showcase -->
			</div>
		</div>

		<?php if (!isset($preview_order_id)) { ?>
			<div class="alert alert-warning">
				<i class="fa fa-exclamation-circle"></i> <?php echo $error_preview_order; ?>
	      		<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
		<?php } else { ?>
			<div id="preview-mail" class="panel panel-inbox" data-order="<?php echo $preview_order_id; ?>">
				<div class="panel-heading">
					<h3 class="panel-title"><i class="fa fa-eye"></i> <?php echo $heading_preview; ?></h3>

					<div class="panel-heading-action">
						<span class="well">
							<i data-width="100%" class="media-icon fa fa-desktop selected" data-toggle="tooltip" title="<?php echo $text_desktop; ?>"></i>
							<i data-width="768px" class="media-icon fa fa-tablet" data-toggle="tooltip" title="<?php echo $text_tablet; ?>"></i>
							<i data-width="320px" class="media-icon fa fa-mobile" data-toggle="tooltip" title="<?php echo $text_mobile; ?>"></i>
							<span class="divider"></span>
							<i class="preview-image fa fa-toggle-on selected" data-toggle="tooltip" title="<?php echo $button_withimages; ?>"></i>
							<i class="preview-image preview-no-image fa fa-toggle-off hide selected" data-toggle="tooltip" title="<?php echo $button_withoutimages; ?>"></i>
						</span>
						<i class="fa fa-refresh template-update" data-toggle="tooltip" title="<?php echo $button_update_preview; ?>"></i>
					</div>
				</div>

				<div class="panel-body">
					<div id="preview-with" class="preview-frame loading">
						<i class="fa fa-spinner fa-spin fa-5x"></i>
					</div>

	    			<div id="preview-without" class="preview-frame" style="display:none"></div>
				</div>
    		</div>
		<?php } ?>
	</form>
</div>
</div>

<?php echo $footer; ?>