<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button onclick="$('#form').submit();" type="submit" form="form-special" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
		<div class="alert alert-info updateneeded attention" style="display:none;"><i class="fa fa-exclamation-circle"></i> test
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<div class="alert warningpdf alert-warning" style="display:none;"><i class="fa fa-exclamation-circle"></i> test test
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php if ($error_warning) { ?>
    	<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      		<button type="button" class="close" data-dismiss="alert">&times;</button>
    	</div>
    	<?php } ?>
    	<div class="panel panel-default">
      		<div class="panel-heading">
        		<h3 class="panel-title"><i class="fa fa-list"></i>Edit Labels</h3>
      		</div>
      		<div class="panel-body">
				<ul id="pltabs" class="nav nav-tabs">
		        	<li class="active"><a href="#tab-product_labels_settings" data-toggle="tab"><?php echo $settings_tab; ?></a></li>
		        	<li><a href="#tab-label_templates" data-toggle="tab"><?php echo $label_templates_tab; ?></a></li>
		        	<li><a href="#tab-labels" data-toggle="tab"><?php echo $labels_tab; ?></a></li>
		        	<li id="update_tab" style="display:none;"><a href="#tab-update" data-toggle="tab"><?php echo $update_tab; ?></a></li>
		        </ul>
        		<div class="tab-content">
					<div class="tab-pane active" id="tab-product_labels_settings">
					<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
						<div class="form-group">
							<label class="col-sm-2 control-label" for="default_template"><?php echo $text_default_template; ?></label>
							<div class="col-sm-10">
								<select name="default_template" class="form-control">
								<?php foreach($label_templates as $id => $template_name) { ?>
									<option value="<?php echo $id ?>"<?php if($settings['product_labels_default_template'] == $id) echo ' selected="selected"' ?>><?php echo $template_name; ?></option>
								<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="default_label"><?php echo $text_default_label; ?></label>
							<div class="col-sm-10">
								<select name="default_label" class="select_label form-control">
								<?php foreach($labels as $id => $label_name) { ?>
									<option value="<?php echo $id ?>"<?php if($settings['product_labels_default_label'] == $id) echo " selected" ?>><?php echo $label_name; ?></option>
								<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="default_orientation"><?php echo $text_default_orientation; ?></label>
							<div class="col-sm-10">
								<select name="default_orientation" class="form-control">
									<option value="P" <?php if($settings['product_labels_default_orientation'] == "P") echo ' selected="selected"' ?>><?php echo $text_portrait; ?></option>
									<option value="L" <?php if($settings['product_labels_default_orientation'] == "L") echo ' selected="selected"' ?>><?php echo $text_landscape; ?></option>
								</select>
							</div>
						</div>
						<div class="form-group">
		                	<label class="col-sm-2 control-label" for="default_pagew"><?php echo $text_default_pagesize; ?></label>
		                	<div class="col-sm-5">
		                		<div class="input-group">
		                			<span class="input-group-addon">Width</span>
		                  			<input type="text" name="default_pagew" value="<?php echo $settings['product_labels_default_pagew']; ?>" placeholder="width" class="form-control" />
		               			</div>
		               		</div>
		               		<div class="col-sm-5">
		                		<div class="input-group">
		                			<span class="input-group-addon">Height</span>
		                  			<input type="text" name="default_pageh" value="<?php echo $settings['product_labels_default_pageh']; ?>" placeholder="height" class="form-control" />
		               			</div>
		               		</div>
		              	</div>
		              	<!-- div class="form-group">
		                	<label class="col-sm-2 control-label" for="default_addr_format"><?php echo $text_default_addr_format; ?></label>
		                	<div class="col-sm-10">
		                  		<textarea name="default_addr_format" class="form-control" rows="6"><?php echo $settings['product_labels_default_addr_format']; ?></textarea>
		               		</div>
		              	</div -->
		              	<div class="form-group">
		                	<label class="col-sm-2 control-label" for="fgcolours"><?php echo $text_foreground_colours; ?></label>
		                	<div class="col-sm-10">
		                  		<input type="text" name="fgcolours" value="<?php echo $settings['product_labels_fgcolours']; ?>" placeholder="" class="form-control" />
		               		</div>
		              	</div>
		              	<div class="form-group">
		                	<label class="col-sm-2 control-label" for="bgcolours"><?php echo $text_background_colours; ?></label>
		                	<div class="col-sm-10">
		                  		<input type="text" name="bgcolours" value="<?php echo $settings['product_labels_bgcolours']; ?>" placeholder="" class="form-control" />
		               		</div>
		              	</div>
		              	<div class="form-group">
		                	<label class="col-sm-2 control-label" for="border"><?php echo $text_print_borders; ?></label>
		                	<div class="col-sm-10">
		                  		<input type="checkbox" name="border"<?php echo ($settings['product_labels_border'])?' checked':''; ?> value="1" />
		               		</div>
		              	</div>
		              	<div class="form-group">
							<label class="col-sm-2 control-label" for="download"><?php echo $text_generate_labels; ?></label>
							<div class="col-sm-10">
								<select name="download" class="form-control">
									<option value="1"<?php echo ($settings['product_labels_download'])?' selected="selected"':''; ?>><?php echo $text_option_download; ?></option>
									<option value="0"<?php echo ($settings['product_labels_download'])?'':' selected="selected"'; ?>><?php echo $text_option_no_download; ?></option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="filename"><?php echo $text_filename; ?></label>
							<div class="col-sm-10">
								<input type="text" name="filename" value="<?php echo $settings['product_labels_filename']; ?>" placeholder="" class="form-control" />
							</div>
						</div>
					</form>
					<a class="pull-right" id="checkinstall" onclick="return false;">check installation</a>
					</div>
					<div class="tab-pane" id="tab-label_templates">
						<div class="row">
							<div class="col-sm-7">
								<legend><?php echo $text_template_settings; ?></legend>
							</div>
							<div class="col-sm-5">
								<legend><?php echo $text_preview; ?></legend>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-7">
								<div class="row">
								<form id="edit_template_form" class="form-horizontal">
								<input type="hidden" name="templateid" value="">
									<div class="form-group form-group-sm">
										<label class="col-sm-4 control-label " for="template"></label>
										<div class="col-sm-8">
											<select name="template" class="select_template form-control">
												<option value="" selected><?php echo $text_option_new_template; ?></option>
												<?php foreach($label_templates as $id => $label_template) { ?>
												<option value="<?php echo $id ?>"<?php if($settings['product_labels_default_template'] == '$id') echo " selected" ?>><?php echo $label_template; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
					              	<div class="form-group form-group-sm">
					                	<label class="col-sm-4 control-label" for="pw"><?php echo $text_page_size; ?></label>
					                	<div class="col-sm-4">
					                		<div class="input-group input-group-sm">
					                			<span style="min-width:55px;" class="input-group-addon">Width</span>
					                  			<input type="text" name="pw" value="<?php echo $settings['product_labels_default_pagew']; ?>" placeholder="mm." class="templateinput form-control" />
					               			</div>
					               		</div>
					               		<div class="col-sm-4">
					                		<div class="input-group input-group-sm">
					                			<span style="min-width:55px;" class="input-group-addon">Height</span>
					                  			<input type="text" name="ph" value="<?php echo $settings['product_labels_default_pageh']; ?>" placeholder="mm." class="templateinput form-control" />
					               			</div>
					               		</div>
					              	</div>
					              	<div class="form-group form-group-sm">
					                	<label class="col-sm-4 control-label" for="lw"><?php echo $text_label_width; ?></label>
					                	<div class="col-sm-4">
					                		<div class="input-group input-group-sm">
					                			<span style="min-width:55px;" class="input-group-addon">Width</span>
					                  			<input type="text" name="lw" value="" placeholder="mm." class="templateinput form-control" />
					               			</div>
					               		</div>
					               		<div class="col-sm-4">
					                		<div class="input-group input-group-sm">
					                			<span style="min-width:55px;" class="input-group-addon">Height</span>
					                  			<input type="text" name="lh" value="" placeholder="mm." class="templateinput form-control" />
					               			</div>
					               		</div>
					              	</div>
					              	<div class="form-group form-group-sm">
					                	<label class="col-sm-4 control-label" for="nw"><?php echo $text_labels_hor; ?></label>
					                	<div class="col-sm-4">
					                		<div class="input-group input-group-sm">
					                			<span style="min-width:55px;" class="input-group-addon">Hor.</span>
					                  			<input type="text" name="nw" value="" placeholder="" class="templateinput form-control" />
					               			</div>
					               		</div>
					               		<div class="col-sm-4">
					                		<div class="input-group input-group-sm">
					                			<span style="min-width:55px;" class="input-group-addon">Vert.</span>
					                  			<input type="text" name="nh" value="" placeholder="" class="templateinput form-control" />
					               			</div>
					               		</div>
					              	</div>
					              	<div class="form-group form-group-sm">
					                	<label class="col-sm-4 control-label" for="hspace"><?php echo $text_spacer_hor; ?></label>
					                	<div class="col-sm-4">
					                		<div class="input-group input-group-sm">
					                			<span style="min-width:55px;" class="input-group-addon">Left</span>
					                  			<input type="text" name="hspace" value="" placeholder="" class="templateinput form-control" />
					               			</div>
					               		</div>
					               		<div class="col-sm-4">
					                		<div class="input-group input-group-sm">
					                			<span style="min-width:55px;" class="input-group-addon">Top</span>
					                  			<input type="text" name="vspace" value="" placeholder="" class="templateinput form-control" />
					               			</div>
					               		</div>
					              	</div>
					              	<div class="form-group form-group-sm">
					                	<label class="col-sm-4 control-label" for="margint"><?php echo $text_margin_top; ?></label>
					                	<div class="col-sm-4">
					                		<div class="input-group input-group-sm">
					                			<span style="min-width:55px;" class="input-group-addon">Top</span>
					                  			<input type="text" name="margint" value="" placeholder="auto" value="auto" class="templateinput form-control" />
					               			</div>
					               		</div>
					               		<div class="col-sm-4">
					                		<div class="input-group input-group-sm">
					                			<span style="min-width:55px;" class="input-group-addon">Left</span>
					                  			<input type="text" name="marginl" value="" placeholder="auto" value="auto" class="templateinput form-control" />
					               			</div>
					               		</div>
					              	</div>
					              	<div class="form-group">
					                	<label class="col-sm-4 control-label" for="border"><?php echo $text_rounded; ?></label>
					                	<div class="col-sm-8">
					                  		<input type="checkbox" name="rounded" value="1" class="templateinput form-control" />
					               		</div>
					              	</div>

									<div class="form-group form-group-sm">
										<div class="col-sm-4"></div>
										<div class="col-sm-4">
											<button type="button" id="deletebutton_template" onclick="delete_template();" class="btn btn-sm btn-primary" style="visibility:visible;"><i class="fa fa-times"></i> <?php echo $button_delete; ?></button>
											<button type="button" id="savebutton_template" onclick="save_template();" class="btn btn-sm btn-primary" style="visibility:visible;"><i class="fa fa-check"></i> <?php echo $button_save; ?></button>
											<div style="margin-top:10px;width=100%;">
												<button type="button" id="previewbutton_template" onclick="preview_template();" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> <?php echo $button_preview;?></button>
												<button type="button" id="printpreviewbutton_template" target="_blank" onclick="window.open('index.php?route=module/product_labels/labels&token=<?php echo $token; ?>&printpreview=1&templateid='+$('input[name=\'templateid\']').val(),'_blank')" class="btn btn-sm btn-primary" style="visibility:visible;"><?php echo $button_printpreview;?></button>
											</div>
										</div>
										<div class="col-sm-4">
											<div class="input-group input-group-sm">
												<span class="input-group-btn">
	        										<button type="button" id="saveasbutton_template" onclick="pl_saveas_template();" class="btn btn-primary"><?php echo $button_saveas; ?></button>
	      										</span>
	      										<input type="text" name="saveas_template" class="templateinput form-control" value="">
											</div>
										</div>
									</div>
								</form>
								</div>
							</div>
							<div class="col-sm-5">
								<div id="preview_template_div" style="width:270px;height:340px;"></div>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab-labels">
						<div class="row">
							<div class="col-sm-7">
								<legend><?php echo $text_template_settings; ?></legend>
							</div>
							<div class="col-sm-5">
								<legend><?php echo $text_preview; ?></legend>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-7">
								<div class="row">
									<div class="form-group form-group-sm">
										<label class="col-sm-4 control-label text-right" for="template"><?php echo $text_select_label; ?></label>
										<div class="col-sm-8">
											<select name="select_label" class="select_label form-control" style="margin-bottom:10px;">
												<option value="" selected><?php echo $text_option_new_label; ?></option>
												<?php $name_i = 1; ?>
												<?php foreach($labels as $id => $label_name) { ?>
												<?php 	if(empty($label_name)) { ?>
												<?php 		$label_name="Label_".$name_i; ?>
												<?php 		$name_i++; ?>
												<?php 	} ?>
												<option value="<?php echo $id ?>"><?php echo $label_name; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12" class="form-group form-group-sm">
										<form id="edit_label_form" class="form-horizontal">
										<input type="hidden" name="route" value="module/product_labels/labels">
										<input type="hidden" name="token" value="<?php echo $token ?>">
										<input type="hidden" name="sample" value="1">
										<input type="hidden" name="edit" value="1">
										<input type="hidden" name="labelid" value="">
										<div id="form_elements">
										<?php foreach(array("rect","img","text","barcode") as $element_type) { ?>
											<div class="well" style="padding:5px;"> <div class="row">
												<div class="col-sm-12">
													<legend style="margin-bottom:2px;"><?php echo ${'text_'.$element_type} ?></legend>
												</div>
											</div>
											<div class="col-xs-12">
												<div class="row" id="element_test">
													<div class="col-xs-2 col-lg-2 oc2-pl-label-input-header"><p><?php echo $text_add; ?></p></div>
													<div class="col-xs-2 col-lg-2 oc2-pl-label-input-header <?php echo ControllerModuleProductLabels::toggle($element_type,0) ?>"><p data-toggle="tooltip" data-original-title="<?php echo $text_tip_font_f; ?>"><?php echo $text_font_f; ?></p></div>
													<div class="col-xs-1 col-lg-1 oc2-pl-label-input-header <?php echo ControllerModuleProductLabels::toggle($element_type,0) ?>"><p data-toggle="tooltip" data-original-title="<?php echo $text_tip_font_s; ?>"><?php echo $text_font_s; ?></p></div>
													<div class="col-xs-1 col-lg-1 oc2-pl-label-input-header <?php echo ControllerModuleProductLabels::toggle($element_type,9) ?>"><p data-toggle="tooltip" data-original-title="<?php echo $text_tip_font_a; ?>"><?php echo $text_font_a; ?></p></div>
													<div class="col-xs-3 col-lg-3 oc2-pl-label-input-header <?php echo ControllerModuleProductLabels::toggle($element_type,1) ?>"><p data-toggle="tooltip" data-original-title="<?php echo $text_tip_text; ?>"><?php echo $text_text; ?></p></div>
													<div class="col-xs-5 col-lg-5 oc2-pl-label-input-header <?php echo ControllerModuleProductLabels::toggle($element_type,2) ?>"><p data-toggle="tooltip" data-original-title="<?php echo $text_tip_img; ?>"><?php echo $text_img; ?></p></div>
													<div class="col-xs-1 col-lg-1 oc2-pl-label-input-header "><p data-toggle="tooltip" data-original-title="<?php echo $text_tip_xpos; ?>"><?php echo $text_xpos; ?></p></div>
													<div class="col-xs-1 col-lg-1 oc2-pl-label-input-header "><p data-toggle="tooltip" data-original-title="<?php echo $text_tip_ypos; ?>"><?php echo $text_ypos; ?></p></div>
													<div class="col-xs-1 col-lg-1 oc2-pl-label-input-header <?php echo ControllerModuleProductLabels::toggle($element_type,5) ?>"><p data-toggle="tooltip" data-original-title="<?php echo $text_tip_width; ?>"><?php echo $text_width; ?></p></div>
													<div class="col-xs-1 col-lg-1 oc2-pl-label-input-header <?php echo ControllerModuleProductLabels::toggle($element_type,6) ?>"><p data-toggle="tooltip" data-original-title="<?php echo $text_tip_height; ?>"><?php echo $text_height; ?></p></div>
													<div class="col-xs-1 col-lg-1 oc2-pl-label-input-header <?php echo ControllerModuleProductLabels::toggle($element_type,7) ?>"><p data-toggle="tooltip" data-original-title="<?php echo $text_tip_color; ?>"><?php echo $text_color; ?></p></div>
													<div class="col-xs-1 col-lg-1 oc2-pl-label-input-header <?php echo ControllerModuleProductLabels::toggle($element_type,8) ?>"><p data-toggle="tooltip" data-original-title="<?php echo $text_tip_fill; ?>"><?php echo $text_fill; ?></p></div>
												</div>
											</div>
											<!-- labels here -->
											<div class="row" id="tfoot_<?php echo $element_type ?>">
												<div class="col-sm-12">
													<button type="button" class="btn btn-default btn-xs" style="margin-bottom:2px;margin-top:10px;" onclick="add_label_element('<?php echo $element_type ?>');return false;"><?php echo $text_addnew; ?> <b><?php echo ${'text_'.$element_type} ?></b></button>
												</div>
											</div> </div>
										<?php } ?>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="form-group form-group-sm">
										<div class="col-sm-6">
											<button type="button" id="deletebutton_label" onclick="delete_label();" class="btn btn-sm btn-primary" style="visibility:hidden;"><i class="fa fa-times"></i> <?php echo $button_delete; ?></button>
											<button type="button" id="savebutton_label" onclick="save_label();" class="btn btn-sm btn-primary" style="visibility:hidden;"><i class="fa fa-check"></i> <?php echo $button_save; ?></button>
										</div>
										<div class="col-sm-6">
											<div class="input-group input-group-sm">
												<span class="input-group-btn">
	        										<button type="button" id="saveasbutton_label" onclick="pl_saveas_label();" class="btn btn-primary"><?php echo $button_saveas; ?></button>
	      										</span>
	      										<input type="text" name="saveas_label_name" class="form-control" value="">
											</div>
										</div>
									</div>
								</div>
								</form>
							</div>
							<div class="col-sm-5">
								<div class="row">
									<div class="form-group form-group-sm">
										<div class="col-sm-2">
											<button type="button" onclick="preview_label();return false;" id="previewbutton_label" class="btn btn-sm btn-primary"><?php echo $button_preview; ?></button>
										</div>
										<div class="col-sm-6">
											<select name="templateid" class="select_template form-control">
											<?php foreach($label_templates as $id => $label_template) { ?>
												<option value="<?php echo $id ?>"<?php if($settings['product_labels_default_template'] == '$id') echo " selected" ?>><?php echo $label_template; ?></option>
											<?php } ?>
											</select>
										</div>
										<div class="col-sm-4">
											<select name="orientation" class="form-control">
												<option value="P" <?php if($settings['product_labels_default_orientation'] == "P") echo " selected" ?>><?php echo $text_portrait; ?></option>
												<option value="L" <?php if($settings['product_labels_default_orientation'] == "L") echo " selected" ?>><?php echo $text_landscape; ?></option>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<div id="preview_pdf_label" style="width:100%;height:350px;margin-top:10px;"></div>
										<div id="debug"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab-update">
						<div class="row">
							<div class="col-sm-12">
								To update this module, please go to <a href="http://www.opencart.com/index.php?route=extension/extension/info&extension_id=14062" target="_blank">Opencart extensions</a>
								<pre>
									<div id="notes"></div>
								</pre>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fademodal" id="pl-modal" tabindex="-1" role="dialog" aria-labelledby="PlModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" id="pl-modal-content"></div>
  </div>
</div>
<script type="text/javascript">
	var token = '<?php echo $token; ?>';
	var label_element_type = ['rect', 'img', 'text', 'barcode'];
	var label_element_name = ['<?php echo $text_rect; ?>','<?php echo $text_img; ?>','<?php echo $text_text; ?>', '<?php echo $text_barcode; ?>'];
	var elements;
	var row = <?php echo $row; ?>;
	var autocomp_label_elements = {
		delay: 0,
		source: ["{Shipping_Address}","{firstname}","{lastname}","{address_1}","{address_2}","{city}","{postcode}","{zone}","{zone_code}","{country}","{iso_code_2}","{iso_code_3}","{company}","{method}","{comment}", "{payment_code}", "{payment_method}", "{shipping_method}", "{tracking_number}", "{order_id}", "{store_name}", "{customer_id}", "{currency_code}","{date_added}", "{date_modified}","{date}", "{kg}", "{gr}", "{lb}", "{oz}"],
		minLength: 0
	};
	var colorpicker_color={
		pickerDefault: "000000",
		colors: ["<?php echo join('","', explode(",",$settings['product_labels_fgcolours'])) ?>"],
		showHexField: false
	};
	var colorpicker_fill={
		pickerDefault: "FFFFFF",
		colors: ["<?php echo join('","', explode(",",$settings['product_labels_bgcolours'])) ?>"],
		showHexField: false
	};
	var error_saveas_template = '<?php echo $error_saveas_template; ?>';
	var error_nopdf = '<?php echo $error_nopdf; ?>';
	var error_pdf = '<?php echo $error_pdf; ?>';
	var error_delete_template = '<?php echo $error_delete_template; ?>';
	var error_delete_label = '<?php echo $error_delete_label; ?>';
	var error_saveas_label = '<?php echo $error_saveas_label; ?>';
	var text_add = '<?php echo $text_add; ?>';
	var text_tip_font_f = '<?php echo $text_tip_font_f; ?>';
	var text_font_f = '<?php echo $text_font_f; ?>';
	var text_tip_font_s = '<?php echo $text_tip_font_s; ?>';
	var text_font_s = '<?php echo $text_font_s; ?>';
	var text_tip_font_a = '<?php echo $text_tip_font_a; ?>';
	var text_font_a = '<?php echo $text_font_a; ?>';
	var text_tip_text = '<?php echo $text_tip_text; ?>';
	var text_text = '<?php echo $text_text; ?>';
	var text_tip_img = '<?php echo $text_tip_img; ?>';
	var text_img = '<?php echo $text_img; ?>';
	var text_tip_xpos = '<?php echo $text_tip_xpos; ?>';
	var text_xpos = '<?php echo $text_xpos; ?>';
	var text_tip_ypos = '<?php echo $text_tip_ypos; ?>';
	var text_ypos = '<?php echo $text_ypos; ?>';
	var text_tip_width = '<?php echo $text_tip_width; ?>';
	var text_width = '<?php echo $text_width; ?>';
	var text_tip_height = '<?php echo $text_tip_height; ?>';
	var text_height = '<?php echo $text_height; ?>';
	var text_tip_color = '<?php echo $text_tip_color; ?>';
	var text_color = '<?php echo $text_color; ?>';
	var text_tip_fill = '<?php echo $text_tip_fill; ?>';
	var text_fill = '<?php echo $text_fill; ?>';
	var text_addnew = '<?php echo $text_addnew; ?>';
	var text_option_delete = '<?php echo $text_option_delete; ?>';
	var text_placeholder_text = '<?php echo $text_placeholder_text; ?>';
	var text_placeholder_img = '<?php echo $text_placeholder_img; ?>';
	var text_placeholder_xpos = '<?php echo $text_placeholder_xpos; ?>';
	var text_placeholder_ypos = '<?php echo $text_placeholder_ypos; ?>';
	var text_placeholder_width = '<?php echo $text_placeholder_width; ?>';
	var text_placeholder_height = '<?php echo $text_placeholder_height; ?>';
	var update_needed = '<?php echo $update_needed; ?>';
	var this_version = '<?php echo $this_version; ?>';
	var new_version = '<?php echo $new_version ?>';
	var please_update = '<?php echo $please_update ?>';
	var settings = {product_labels_default_template:'<?php echo $settings['product_labels_default_template'] ?>',product_labels_default_pagew:'<?php echo $settings['product_labels_default_pagew']; ?>',product_labels_default_pageh:'<?php echo $settings['product_labels_default_pageh']; ?>',product_labels_default_label:'<?php echo $settings['product_labels_default_label'] ?>'}

	$(document).ready(function() {
		$('#pltabs a:first').tab('show');
		preview_template();
		preview_label();
		check_update();
	});

</script>
<?php echo $footer; ?>