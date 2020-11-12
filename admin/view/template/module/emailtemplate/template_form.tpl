<?php echo $header; ?>
<?php echo $column_left; ?>

<div id="content">
<div id="emailtemplate">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button id="submit_form_button" type="submit" form="form-emailtemplate" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>

				<?php if ($emailtemplate['emailtemplate_id'] != 1) { ?>
					<a href="<?php echo $action; ?>&action=delete" data-confirm="<?php echo $text_confirm; ?>" class="btn btn-danger" data-toggle="tooltip" title="<?php echo $button_delete; ?>"><i class="fa fa-trash-o"></i></a>

					<a href="<?php echo $action_insert_template; ?>" class="btn btn-success" data-toggle="tooltip" title="<?php echo $text_create_template; ?>"><i class="fa fa-plus"></i></a>
				<?php } ?>

				<a href="<?php echo $config_url; ?>" data-toggle="tooltip" title="<?php echo $text_config; ?>" class="btn btn-info"><i class="fa fa-cogs"></i></a>

				<?php if (isset($template_default_url) || $template_similar) { ?>
				<div class="btn-group">
					<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-envelope"></i> <?php echo $button_templates; ?> <span class="caret"></span></button>
					<ul class="dropdown-menu dropdown-menu-right" role="menu">
						<?php if (isset($template_default_url)) { ?>
						<li><a href="<?php echo $template_default_url; ?>"><?php echo $button_default_template; ?></a></li>
						<?php } ?>
						<?php if ($template_similar && isset($template_default_url)) { ?><li class="divider"></li><?php } ?>
						<?php if ($template_similar) { ?>
							<?php foreach($template_similar as $row) { ?>
					    		<li><a href="<?php echo $row['url']; ?>"><?php echo $row['name']; ?></a></li>
					    	<?php } ?>
				    	<?php } ?>
				  	</ul>
				</div>
				<?php } ?>

				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
			</div>

			<h1 class="panel-title"><?php echo $emailtemplate['label']; ?></h1>

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
				<ul id="language-body" class="nav nav-tabs">
					<?php $i = 0; foreach ($languages as $language) { $i++; ?>
					<li<?php if ($i == 1) { ?> class="active"<?php } ?>><a href="javascript:void(0)" data-target="#tab-language-<?php echo $language['language_id']; ?>" data-toggle="tab" role="tab">
						<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
						<?php echo $language['name'] . ($language['default'] == 1 ? ' <small>('.$text_default.')</small>' : ''); ?>
					</a></li>
					<?php } ?>
				</ul>
			</div>

			<div class="tab-content">
				<?php $i = 0; foreach ($emailtemplate_description as $langId => $description) { $i++; ?>
				<div id="tab-language-<?php echo $langId; ?>" class="tab-pane fade<?php if ($i == 1) { ?> active in<?php } ?>">

					<?php if ($emailtemplate['key'] == 'order.update' || $emailtemplate['key'] == 'admin.return_history' || $emailtemplate['key'] == 'admin.newsletter') { ?>
					<div class="form-group">
                   		<label class="col-sm-2 control-label" for="emailtemplate_description_comment_<?php echo $langId; ?>"><?php echo $entry_comment; ?></label>
                   		<div class="col-sm-10">
                   			<textarea class="wysiwyg_editor" name="emailtemplate_description_comment[<?php echo $langId; ?>]" id="emailtemplate_description_comment_<?php echo $langId; ?>"><?php echo $description['comment']; ?></textarea>
                   			<?php if (isset($error_emailtemplate_description_comment[$langId])) {?><span class="text-danger"><?php echo $error_emailtemplate_description_comment[$langId]; ?></span><?php } ?>
                   		</div>
                  	</div>
					<?php } ?>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="emailtemplate_description_subject_<?php echo $langId; ?>"><?php echo $entry_subject; ?></label>
						<div class="col-sm-10">
							<input type="text" name="emailtemplate_description_subject[<?php echo $langId; ?>]" value="<?php echo $description['subject']; ?>" id="emailtemplate_description_subject_<?php echo $langId; ?>" class="form-control input-block-level" />
							<?php if (isset($error_emailtemplate_description_subject[$langId])) {?><span class="text-danger"><?php echo $error_emailtemplate_description_subject[$langId]; ?></span><?php } ?>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="emailtemplate_description_preview_<?php echo $langId; ?>"><?php echo $entry_preheader; ?></label>
						<div class="col-sm-10">
							<input type="text" name="emailtemplate_description_preview[<?php echo $langId; ?>]" value="<?php echo $description['preview']; ?>" id="emailtemplate_description_preview_<?php echo $langId; ?>" class="form-control input-block-level" />
							<?php if (isset($error_emailtemplate_description_preview[$langId])) {?><span class="text-danger"><?php echo $error_emailtemplate_description_preview[$langId]; ?></span><?php } ?>
						</div>
					</div>

					<?php for ($i = 1; $i <= EmailTemplateDescriptionDAO::$content_count; $i++) { ?>
						<?php if ($i < EmailTemplateDescriptionDAO::$content_count && empty($description['content'.($i+1)])) $_add_editor_btn = true; ?>
						<div class="emailtemplate_content<?php if ($i != 1 && empty($description['content'.$i])) { ?> hide<?php } ?>">
							<div class="form-group">
                    			<label class="control-label col-sm-2" for="emailtemplate_description_content_<?php echo $i; ?>_<?php echo $langId; ?>">
                    				<?php echo sprintf($entry_content_count, $i); ?>
                    				<?php if ($i < EmailTemplateDescriptionDAO::$content_count && empty($description['content'.($i+1)]) && ($i == 1 || !empty($description['content'.($i)]))) { ?>
		                    			<a href="javascript:void(0)" class="add-editor btn btn-info btn-xs" data-content="<?php echo $i+1; ?>" data-lang="<?php echo $langId; ?>"><i class="fa fa-plus"></i></a>
		                    		<?php } ?>
                    			</label>
	                    		<div class="col-sm-10">
	                      			<textarea class="wysiwyg_editor" name="emailtemplate_description_content<?php echo $i; ?>[<?php echo $langId; ?>]" id="emailtemplate_description_content<?php echo $i; ?>_<?php echo $langId; ?>"><?php echo $description['content'.$i]; ?></textarea>
	                      			<?php if (isset(${"error_emailtemplate_description_content".$i}[$langId])) {?><span class="text-danger"><?php echo ${"error_emailtemplate_description_content".$i}[$langId]; ?></span><?php } ?>
	                    		</div>
	                  		</div>
						</div>
						<?php if ($i != 1 && empty($description['content'.$i])) {
							break;
						} ?>
					<?php } ?>

					<?php if ($emailtemplate['key'] == 'admin.newsletter') { ?>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="emailtemplate_description_unsubscribe_text_<?php echo $langId; ?>"><?php echo $entry_unsubscribe; ?></label>
						<div class="col-sm-10">
							<input type="text" name="emailtemplate_description_unsubscribe_text[<?php echo $langId; ?>]" value="<?php echo $description['unsubscribe_text']; ?>" id="emailtemplate_description_unsubscribe_text_<?php echo $langId; ?>" class="form-control" />
							<?php if (isset($error_emailtemplate_description_unsubscribe_text[$langId])) {?><span class="text-danger"><?php echo $error_emailtemplate_description_unsubscribe_text[$langId]; ?></span><?php } ?>
						</div>
					</div>
					<?php } ?>

					<div class="form-group">
						<div class="col-sm-10 col-sm-offset-2">
							<a href="javascript:void(0)" class="btn btn-primary pull-right btn-sm btn-inbox-preview" data-target="#preview-<?php echo $langId; ?>"><i class="fa fa-eye"></i> <?php echo $button_preview; ?></a>
						</div>
					</div>

					<div class="panel panel-inbox hide" id="preview-<?php echo $langId; ?>" data-language="<?php echo $langId; ?>" data-store="<?php echo $emailtemplate_config['store_id']; ?>">
						<div class="panel-heading">
							<h3 class="panel-title"><i class="fa fa-eye"></i> <?php echo $heading_preview; ?></h3>

							<div class="panel-heading-action">
								<span class="well">
									<i data-width="100%" class="media-icon fa fa-desktop selected" data-toggle="tooltip" title="<?php echo $text_desktop; ?>"></i>
									<i data-width="768px" class="media-icon fa fa-tablet" data-toggle="tooltip" title="<?php echo $text_tablet; ?>"></i>
									<i data-width="320px" class="media-icon fa fa-mobile" data-toggle="tooltip" title="<?php echo $text_mobile; ?>"></i>
								</span>
								<i class="preview-image fa fa-toggle-on selected" data-toggle="tooltip" title="<?php echo $button_withimages; ?>"></i>
								<i class="preview-image preview-no-image fa fa-toggle-off hide selected" data-toggle="tooltip" title="<?php echo $button_withoutimages; ?>"></i>
							</div>
						</div>

						<div class="panel-body">
							<div id="preview-with" class="preview-frame loading">
								<i class="fa fa-spinner fa-spin fa-5x"></i>
							</div>

			    			<div id="preview-without" class="preview-frame" style="display:none"></div>
						</div>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>

		<div class="panel panel-tabs panel-tab-default">
			<div class="panel-nav-tabs">
				<ul class="nav nav-tabs" id="tabs-main">
	    			<li class="active"><a href="javascript:void(0)" data-target="#tab-setting" data-toggle="tab" role="tab"><i class="fa fa-wrench hidden-xs"></i> <?php echo $heading_settings; ?></a></li>
	    			<li><a href="javascript:void(0)" data-target="#tab-mail" data-toggle="tab" role="tab"><i class="fa fa-envelope hidden-xs"></i> <?php echo $heading_mail; ?></a></li>
	    			<li><a href="javascript:void(0)" data-target="#tab-shortcodes" data-toggle="tab" role="tab"><i class="fa fa-magic hidden-xs"></i> <?php echo $heading_shortcodes; ?></a></li>
	    		</ul>
    		</div>

    		<div class="tab-content panel-tab-default">
				<div id="tab-setting" class="tab-pane fade active in">
					<?php if ($emailtemplate['default'] == 0) { ?>
					<input type="hidden" name="emailtemplate_condition" value="" />

					<?php if (!empty($emailtemplate_shortcodes)) { ?>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="add-condition"><?php echo $entry_condition; ?></label>
							<div class="col-sm-10">
								<select id="add-condition" class="form-control">
									<option value=""><?php echo $text_select; ?></option>
									<?php foreach($emailtemplate_shortcodes as $row) { ?>
										<?php if (substr($row['code'], 0, 5) == 'text_' || substr($row['code'], 0, 7) == 'button_' || substr($row['code'], 0, 6) == 'error_' || substr($row['code'], 0, 6) == 'entry_') continue; ?>
										<option><?php echo $row['code']; ?></option>
									<?php } ?>
								</select>

								<div id="emailtemplate_conditions">
									<?php if (!empty($emailtemplate['condition']) && is_array($emailtemplate['condition'])) { ?>
									<?php foreach($emailtemplate['condition'] as $i => $row) { ?>
									<div class="row row-spacing" data-count="<?php echo $i; ?>">
										<div class="col-md-3">
											<input type="text" name="emailtemplate_condition[<?php echo $i; ?>][key]" class="form-control" value="<?php echo $row['key']; ?>" readonly="readonly" />
										</div>
										<div class="col-md-3">
											<select name="emailtemplate_condition[<?php echo $i; ?>][operator]" class="form-control">
												<option value="=="<?php if ($row['operator'] == '==') echo ' selected="selected"'; ?>>(==) Equal</option>
												<option value="!="<?php if ($row['operator'] == '!=') echo ' selected="selected"'; ?>>(!=) &nbsp;Not Equal</option>
												<option value="&gt;"<?php if ($row['operator'] == '&gt;') echo ' selected="selected"'; ?>>(&gt;) &nbsp;&nbsp;Greater than</option>
												<option value="&lt;"<?php if ($row['operator'] == '&lt;') echo ' selected="selected"'; ?>>(&lt;) &nbsp;&nbsp;Less than</option>
												<option value="&gt;="<?php if ($row['operator'] == '&gt;=') echo ' selected="selected"'; ?>>(&gt;=) Greater than or equal to </option>
												<option value="&lt;="<?php if ($row['operator'] == '&lt;=') echo ' selected="selected"'; ?>>(&lt;=) Less than or equal to </option>
												<option value="IN"<?php if ($row['operator'] == 'IN') echo ' selected="selected"'; ?>>(IN) Checks if a value exists in comma-delimited string </option>
												<option value="NOTIN"<?php if ($row['operator'] == 'NOTIN') echo ' selected="selected"'; ?>>(NOTIN) Checks if a value does not exist in comma-delimited string </option>
											</select>
										</div>
										<div class="col-md-6">
											<div class="input-group">
												<input type="text" name="emailtemplate_condition[<?php echo $i; ?>][value]" class="form-control" value="<?php echo $row['value']; ?>" placeholder="Value" />
												<span class="input-group-btn">
													<button class="btn btn-default btn-remove-row" type="button"><i class="fa fa-trash-o"></i></button>
												</span>
											</div>
										</div>
									</div>
									<?php } ?>
									<?php } ?>
								</div>
							</div>
						</div>
					<?php } ?>

					<?php if (!empty($order_statuses)) { ?>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="order_status_id"><?php echo $entry_order_status; ?></label>
							<div class="col-sm-10">
								<select name="order_status_id" id="order_status_id" class="form-control">
									<option value=""><?php echo $text_select; ?></option>
									<?php foreach($order_statuses as $row) { ?>
									<option value="<?php echo $row['order_status_id']; ?>"<?php if ($emailtemplate['order_status_id'] == $row['order_status_id']) echo ' selected="selected"'; ?>><?php echo $row['name']; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					<?php } ?>

					<?php if (!empty($customer_groups)) { ?>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="customer_group_id"><?php echo $entry_customer_group; ?></label>
							<div class="col-sm-10">
								<select name="customer_group_id" id="customer_group_id" class="form-control">
									<option value=""><?php echo $text_select; ?></option>
									<?php foreach($customer_groups as $row) { ?>
									<option value="<?php echo $row['customer_group_id']; ?>"<?php if ($emailtemplate['customer_group_id'] == $row['customer_group_id']) echo ' selected="selected"'; ?>><?php echo $row['name']; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					<?php } ?>

					<?php if (!empty($stores)) { ?>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="store_id"><?php echo $entry_store; ?></label>
							<div class="col-sm-10">
								<select name="store_id" id="store_id" class="form-control">
									<option value="NULL"><?php echo $text_select; ?></option>
									<?php foreach($stores as $store) { ?>
									<option value="<?php echo $store['store_id']; ?>"<?php if ($emailtemplate['store_id'] == $store['store_id'] && is_numeric($emailtemplate['store_id'])) echo ' selected="selected"'; ?>><?php echo $store['store_name']; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					<?php } ?>

					<hr />
					<?php } ?>

					<div class="form-group required">
						<label class="col-sm-2 control-label" for="emailtemplate_key"><?php echo $entry_key; ?></label>
						<div class="col-sm-10">
							<input required="required" type="text" name="emailtemplate_key" value="<?php echo $emailtemplate['key']; ?>" id="emailtemplate_key" class="form-control" />
							<?php if (isset($error_emailtemplate_key)) { ?><span class="text-danger"><?php echo $error_emailtemplate_key; ?></span><?php } ?>
						</div>
					</div>

					<div class="form-group required">
						<label class="col-sm-2 control-label" for="emailtemplate_label"><?php echo $entry_label; ?></label>
						<div class="col-sm-10">
							<input required="required" type="text" name="emailtemplate_label" value="<?php echo $emailtemplate['label']; ?>" id="emailtemplate_label" class="form-control" />
							<?php if (isset($error_emailtemplate_label)) { ?><span class="text-danger"><?php echo $error_emailtemplate_label; ?></span><?php } ?>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="emailtemplate_type"><?php echo $entry_type; ?></label>
						<div class="col-sm-10">
							<select name="emailtemplate_type" id="emailtemplate_type" class="form-control">
								<option value=""></option>
								<option value="customer"<?php if ($emailtemplate['type'] == 'customer') echo ' selected="selected"'; ?>>Customer</option>
								<option value="order"<?php if ($emailtemplate['type'] == 'order') echo ' selected="selected"'; ?>>Order</option>
								<option value="admin"<?php if ($emailtemplate['type'] == 'admin') echo ' selected="selected"'; ?>>Admin</option>
								<option value="affiliate"<?php if ($emailtemplate['type'] == 'affiliate') echo ' selected="selected"'; ?>>Affiliate</option>
							</select>
						</div>
					</div>

					<?php if (!empty($emailtemplate_configs)) { ?>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="emailtemplate_config_id"><?php echo $entry_template_config; ?></label>
						<div class="col-sm-10">
							<select name="emailtemplate_config_id" id="emailtemplate_config_id" class="form-control">
								<option value="0"></option>
								<?php foreach($emailtemplate_configs as $row): ?>
								<option value="<?php echo $row['emailtemplate_config_id']; ?>"<?php echo ($emailtemplate['emailtemplate_config_id'] == $row['emailtemplate_config_id']) ? 'selected="selected"' : ''; ?>><?php echo $row['name']; ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<?php } ?>

					<?php if (!empty($emailtemplate_files)) { ?>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="emailtemplate_template"><?php echo $entry_template_file; ?></label>
						<div class="col-sm-10">
							<select name="emailtemplate_template" id="emailtemplate_template" class="form-control">
								<option value=""></option>
								<?php if (!empty($emailtemplate_files['catalog'])): ?>
								<optgroup label="<?php echo $emailtemplate_files['dirs']['catalog']; ?>" data-type="catalog">
									<?php foreach($emailtemplate_files['catalog'] as $file) { ?>
										<?php if ($emailtemplate['template'] == $file) { ?>
											<option value="<?php echo $file; ?>" selected="selected"><?php echo $file; ?></option>
										<?php } else { ?>
											<option value="<?php echo $file; ?>"><?php echo $file; ?></option>
										<?php } ?>
									<?php } ?>
								</optgroup>
								<?php endif; ?>

								<?php if (!empty($emailtemplate_files['catalog_default'])): ?>
								<optgroup label="<?php echo $emailtemplate_files['dirs']['catalog_default']; ?>" data-type="catalog">
									<?php foreach($emailtemplate_files['catalog_default'] as $file) { ?>
										<?php if ($emailtemplate['template'] == $file) { ?>
											<option value="<?php echo $file; ?>" selected="selected"><?php echo str_replace($emailtemplate_files['dirs']['catalog_default'], '', $file); ?></option>
										<?php } else { ?>
											<option value="<?php echo $file; ?>"><?php echo str_replace($emailtemplate_files['dirs']['catalog_default'], '', $file); ?></option>
										<?php } ?>
									<?php } ?>
								</optgroup>
								<?php endif; ?>

								<?php if (!empty($emailtemplate_files['admin'])): ?>
								<optgroup label="<?php echo $emailtemplate_files['dirs']['admin']; ?>" data-type="admin">
									<?php foreach($emailtemplate_files['admin'] as $file) { ?>
										<?php if ($emailtemplate['template'] == $file) { ?>
											<option value="<?php echo $file; ?>" selected="selected"><?php echo str_replace($emailtemplate_files['dirs']['admin'], '', $file); ?></option>
										<?php } else { ?>
											<option value="<?php echo $file; ?>"><?php echo str_replace($emailtemplate_files['dirs']['admin'], '', $file); ?></option>
										<?php } ?>
									<?php } ?>
								</optgroup>
								<?php endif; ?>
							</select>
							<?php if ($emailtemplate['template']) { ?>
								<span class="help-block"><?php echo $emailtemplate_template_path . $emailtemplate['template']; ?></span>
							<?php } ?>
						</div>
					</div>
					<?php } ?>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="emailtemplate_wrapper_tpl"><?php echo $entry_wrapper; ?></label>
						<div class="col-sm-10">
							<input type="text" name="emailtemplate_wrapper_tpl" value="<?php echo $emailtemplate['wrapper_tpl']; ?>" id="emailtemplate_wrapper_tpl" class="form-control" />
							<?php if (isset($error_emailtemplate_wrapper_tpl)) { ?><span class="text-danger"><?php echo $error_emailtemplate_wrapper_tpl; ?></span><?php } ?>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="emailtemplate_language_files"><?php echo $entry_language_files; ?></label>
						<div class="col-sm-10">
							<input type="text" name="emailtemplate_language_files" value="<?php echo $emailtemplate['language_files']; ?>" id="emailtemplate_language_files" class="form-control" />
							<?php if (isset($error_emailtemplate_language_files)) { ?><span class="text-danger"><?php echo $error_emailtemplate_language_files; ?></span><?php } ?>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="emailtemplate_tracking_campaign_source"><?php echo $entry_tracking_campaign_source; ?></label>
						<div class="col-sm-10">
							<input type="text" name="emailtemplate_tracking_campaign_source" value="<?php echo $emailtemplate['tracking_campaign_source']; ?>" id="emailtemplate_tracking_campaign_source" class="form-control" />
						</div>
					</div>

					<?php if (!empty($shortcodes_data['order_id'])) { ?>
					<div class="form-group form-group-radio">
						<label class="col-sm-2 control-label" for="emailtemplate_attach_invoice"><?php echo $entry_attach_invoice; ?></label>
						<div class="col-sm-10">
							<input name="emailtemplate_attach_invoice" id="emailtemplate_attach_invoice" data-control="checkbox" data-off-label="<?php echo $text_no; ?>" data-on-label="<?php echo $text_yes; ?>" value="1" type="checkbox"<?php echo ($emailtemplate['attach_invoice'] == 1) ? ' checked="checked"' : ''; ?>/>
                            <?php if (!isset($pdf_invoice_installed)) { echo $entry_attach_invoice_info; } ?>
                            <?php if (isset($error_emailtemplate_attach_invoice)) { ?><span class="help-block text-danger"><?php echo $error_emailtemplate_attach_invoice; ?></span><?php } ?>
						</div>
					</div>
					<?php } ?>

					<div class="form-group form-group-radio">
						<label class="col-sm-2 control-label" for="emailtemplate_log"><?php echo $entry_log_template; ?></label>
						<div class="col-sm-10">
							<input name="emailtemplate_log" id="emailtemplate_log" data-control="checkbox" data-off-label="<?php echo $text_no; ?>" data-on-label="<?php echo $text_yes; ?>" value="1" type="checkbox"<?php echo ($emailtemplate['log'] == 1) ? ' checked="checked"' : ''; ?>/>
						</div>
					</div>

					<?php if ($emailtemplate['log'] == 1) { ?>
					<div class="form-group form-group-radio">
						<label class="col-sm-2 control-label" for="emailtemplate_view_browser"><?php echo $entry_view_browser; ?></label>
						<div class="col-sm-10">
							<input name="emailtemplate_view_browser" id="emailtemplate_view_browser" data-control="checkbox" data-off-label="<?php echo $text_no; ?>" data-on-label="<?php echo $text_yes; ?>" value="1" type="checkbox"<?php echo ($emailtemplate['view_browser'] == 1) ? ' checked="checked"' : ''; ?>/>
						</div>
					</div>
					<?php } ?>

					<div class="form-group form-group-radio">
						<label class="col-sm-2 control-label" for="emailtemplate_showcase"><?php echo $entry_showcase; ?></label>
						<div class="col-sm-10">
							<input name="emailtemplate_showcase" id="emailtemplate_showcase" data-control="checkbox" data-off-label="<?php echo $text_no; ?>" data-on-label="<?php echo $text_yes; ?>" value="1" type="checkbox"<?php echo ($emailtemplate['showcase'] == 1) ? ' checked="checked"' : ''; ?>/>
						</div>
					</div>

					<div class="form-group form-group-radio">
						<label class="col-sm-2 control-label" for="emailtemplate_status"><?php echo $entry_status; ?></label>
						<div class="col-sm-10">
							<input name="emailtemplate_status" id="emailtemplate_status" data-control="checkbox" data-off-label="<?php echo $text_no; ?>" data-on-label="<?php echo $text_yes; ?>" value="1" type="checkbox"<?php echo ($emailtemplate['status'] == 1) ? ' checked="checked"' : ''; ?>/>
							<?php if (isset($error_emailtemplate_status)) { ?><span class="text-danger"><?php echo $error_emailtemplate_status; ?></span><?php } ?>
						</div>
					</div>
				</div><!-- #tab-setting -->

				<div id="tab-mail" class="tab-pane fade">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="emailtemplate_mail_to"><?php echo $entry_mail_to; ?></label>
						<div class="col-sm-10">
							<input type="text" name="emailtemplate_mail_to" value="<?php echo $emailtemplate['mail_to']; ?>" id="emailtemplate_mail_to" class="form-control" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="emailtemplate_mail_from"><?php echo $entry_mail_from; ?></label>
						<div class="col-sm-10">
							<input type="text" name="emailtemplate_mail_from" value="<?php echo $emailtemplate['mail_from']; ?>" id="emailtemplate_mail_from" class="form-control" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="emailtemplate_mail_sender"><?php echo $entry_mail_sender; ?></label>
						<div class="col-sm-10">
							<input type="text" name="emailtemplate_mail_sender" value="<?php echo $emailtemplate['mail_sender']; ?>" id="emailtemplate_mail_sender" class="form-control" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="emailtemplate_mail_replyto"><?php echo $entry_mail_replyto; ?></label>
						<div class="col-sm-10">
							<input type="text" name="emailtemplate_mail_replyto" value="<?php echo $emailtemplate['mail_replyto']; ?>" id="emailtemplate_mail_replyto" class="form-control" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="emailtemplate_mail_replyto_name"><?php echo $entry_mail_replyto_name; ?></label>
						<div class="col-sm-10">
							<input type="text" name="emailtemplate_mail_replyto_name" value="<?php echo $emailtemplate['mail_replyto_name']; ?>" id="emailtemplate_mail_replyto_name" class="form-control" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="emailtemplate_mail_cc"><?php echo $entry_mail_cc; ?></label>
						<div class="col-sm-10">
							<input type="text" name="emailtemplate_mail_cc" value="<?php echo $emailtemplate['mail_cc']; ?>" id="emailtemplate_mail_cc" class="form-control" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="emailtemplate_mail_bcc"><?php echo $entry_mail_bcc; ?></label>
						<div class="col-sm-10">
							<input type="text" name="emailtemplate_mail_bcc" value="<?php echo $emailtemplate['mail_bcc']; ?>" id="emailtemplate_mail_bcc" class="form-control" />
						</div>
					</div>

					<div class="form-group form-group-radio">
						<label class="col-sm-2 control-label" for="emailtemplate_mail_plain_text"><?php echo $entry_mail_plain_text; ?></label>
						<div class="col-sm-10">
							<input name="emailtemplate_mail_plain_text" id="emailtemplate_mail_plain_text" data-control="checkbox" data-off-label="<?php echo $text_no; ?>" data-on-label="<?php echo $text_yes; ?>" value="1" type="checkbox"<?php echo ($emailtemplate['mail_plain_text'] == 1) ? ' checked="checked"' : ''; ?>/>
						</div>
					</div>

					<div class="form-group form-group-radio">
						<label class="col-sm-2 control-label" for="emailtemplate_mail_html"><?php echo $entry_mail_html; ?></label>
						<div class="col-sm-10">
							<input name="emailtemplate_mail_html" id="emailtemplate_mail_html" data-control="checkbox" data-off-label="<?php echo $text_no; ?>" data-on-label="<?php echo $text_yes; ?>" value="1" type="checkbox"<?php echo ($emailtemplate['mail_html'] == 1) ? ' checked="checked"' : ''; ?>/>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="emailtemplate_mail_attachment"><?php echo $entry_mail_attachment; ?></label>
						<div class="col-sm-10">
							<input type="text" name="emailtemplate_mail_attachment" value="<?php echo $emailtemplate['mail_attachment']; ?>" id="emailtemplate_mail_attachment" class="form-control" />
							<?php if (isset($error_emailtemplate_mail_attachment)) { ?><span class="text-danger"><?php echo $error_emailtemplate_mail_attachment; ?></span><?php } ?>
						</div>
					</div>
				</div><!-- #tab-mail -->

				<div id="tab-shortcodes" class="tab-pane fade">
					<?php if ($shortcodes) { ?>
					<div class="table-responsive">
			           	<table class="table table-hover table-row-check" id="shortcodes_list">
			           		<thead>
								<tr>
									<th width="1"></th>
									<th width="240"><a href="<?php echo $sort_code; ?>" class="<?php if ($sort == 'code') echo strtolower($order); ?>"><?php echo $column_code; ?></a></th>
									<th><a href="<?php echo $sort_example; ?>" class="<?php if ($sort == 'example') echo strtolower($order); ?>"><?php echo $column_example; ?></a></th>
									<th width="1" class="text-center"><input type="checkbox" data-checkall="input[name^='shortcode_selection']" /></th>
				            	</tr>
			            	</thead>
			            	<tbody>
				          		<?php foreach ($shortcodes as $row) { ?>
					            <tr>
			                		<td><a href="javascript:void(0)" class="btn btn-sm" data-modal="remote" data-url="<?php echo $row['url_edit']; ?>" data-refresh="#shortcodes_list"><i class="fa fa-pencil"></i></a></td>
			                		<td><input type="text" class="shortcode-select" value="{$<?php echo $row['code']; ?>}" /></td>
							        <td style="white-space:normal; word-wrap:break-word;"><?php if (is_array($row['example'])) { ?><pre><?php print_r($row['example']); ?></pre><?php } else { ?><?php echo $row['example']; ?><?php } ?></td>
				              		<td class="text-center"><input type="checkbox" name="shortcode_selection[]" value="<?php echo $row['id']; ?>" /></td>
				            	</tr>
								<?php } ?>
							</tbody>
							<tfoot>
								<tr>
									<td colspan="3">&nbsp;</td>
									<td width="1"><button class="btn btn-sm btn-danger" data-confirm="<?php echo $text_confirm; ?>" data-action="<?php echo $action; ?>&action=delete_shortcode"><i class="fa fa-trash"></i></button></td>
								</tr>
							</tfoot>
						</table>
					</div>

					<div class="row row-spacing">
						<div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
						<div class="col-sm-6 text-right"><?php echo $pagination_results; ?></div>
					</div>
			        <?php } ?>

			        <?php if (!$shortcodes) { ?>
			        <div class="buttons clearfix">
				        <div class="btn-group">
				        	<a href="<?php echo $action; ?>&action=default_shortcode" class="btn btn-sm btn-info"><i class="fa fa-plus"></i> <?php echo $button_default; ?></a>
				        </div>
			        </div>
			        <?php } ?>
				</div>

			</div>
		</div>
    </form>
</div>
</div>

<div class="modal fade" id="modal-frame" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog modal-lg">
	<div class="modal-content"></div>
</div>
</div>

<?php echo $footer; ?>