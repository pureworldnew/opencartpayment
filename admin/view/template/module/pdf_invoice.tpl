<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
<div id="modal-info" class="modal <?php if ($OC_V2) echo ' fade'; ?>" tabindex="-1" role="dialog" aria-hidden="true"></div>
	<?php if ($OC_V2) { ?>
		<div class="page-header">
		    <div class="container-fluid">
		      <div class="pull-right">
		        <button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
		        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
			  </div>
		      <h1><?php echo $heading_title; ?></h1>
		      <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
		        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
		      </ul>
		    </div>
  </div>
				<?php } else { ?>
		<div class="breadcrumb">
			<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
				<?php } ?>
		</div>
	<?php } ?>
	
  <div class="container-fluid">
	<?php if (isset($success) && $success) { ?><div class="alert alert-success success"><i class="fa fa-check-circle"></i> <?php echo $success; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div><script type="text/javascript">setTimeout("$('.alert-success').slideUp();",5000);</script><?php } ?>
	<?php if (isset($info) && $info) { ?><div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $info; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div><?php } ?>
	<?php if (isset($error) && $error) { ?><div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div><?php } ?>
    <?php if (isset($error_warning) && $error_warning) { ?><div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div><?php } ?>
<div class="<?php if(!$OC_V2) echo 'box'; ?> panel panel-default">
  <div class="heading panel-heading">
    <h3 class="panel-title"><img style="vertical-align:top;padding-right:4px" src="<?php echo $_img_path; ?>icon-big.png"/> <?php echo $heading_title; ?></h3>
    <div class="buttons"><a onclick="$('#form').submit();" class="button blue"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button red"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content panel-body">
	<div id="stores" <?php if ($OC_V2) echo 'class="v2"'; ?>>
		Store:
		<select name="store">
			<?php foreach ($stores as $store) { ?>
			<?php if ($store_id == $store['store_id']) { ?>
			<option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
			<?php } else { ?>
			<option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
			<?php } ?>
			<?php } ?>
		</select>
	</div>
		<ul class="nav nav-tabs">
			<?php if (!$store_id) { ?>
			<li class="active"><a href="#tab-1" data-toggle="tab"><i class="fa fa-cog"></i><?php echo $_language->get('text_tab_1'); ?></a></li>
			<?php } ?>
			<li <?php if ($store_id) echo 'class="active"'; ?>><a href="#tab-6" data-toggle="tab"><i class="fa fa-check"></i><?php echo $_language->get('text_tab_6'); ?></a></li>
			<li><a href="#tab-2" data-toggle="tab"><i class="fa fa-file-pdf-o"></i><?php echo $_language->get('text_tab_2'); ?></a></li>
			<li><a href="#tab-4" data-toggle="tab"><i class="fa fa-pencil-square-o"></i><?php echo $_language->get('text_tab_4'); ?></a></li>
			<li><a href="#tab-5" data-toggle="tab"><i class="fa fa-file-text-o"></i><?php echo $_language->get('text_tab_5'); ?></a></li>
			<li><a href="#tab-3" data-toggle="tab"><i class="fa fa-floppy-o"></i><?php echo $_language->get('text_tab_3'); ?></a></li>
			<li><a href="#tab-about" data-toggle="tab"><i class="fa fa-info"></i><?php echo $_language->get('text_tab_about'); ?></a></li>
		</ul>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
		<div class="tab-content">
		<?php if (!$store_id) { ?>
		<div class="tab-pane active" id="tab-1">
		  <table class="form">
			<tr>
			  <td><?php echo $_language->get('entry_mail'); ?></td>
			  <td>
				<input class="switch" type="checkbox" id="pdf_invoice_mail" name="pdf_invoice_mail" value="1" <?php if($pdf_invoice_mail) echo 'checked="checked"'; ?>/>
			  </td>
			</tr>
			<tr>
			  <td><?php echo $_language->get('entry_admincopy'); ?></td>
			  <td>
				<input class="switch" type="checkbox" id="pdf_invoice_admincopy" name="pdf_invoice_admincopy" value="1" <?php if($pdf_invoice_admincopy) echo 'checked="checked"'; ?>/>
			  </td>
			</tr>
      <?php /*
			<tr>
			  <td><?php echo $_language->get('entry_mailcopy'); ?></td>
			  <td>
				<input class="switch" type="checkbox" id="pdf_invoice_adminalertcopy" name="pdf_invoice_adminalertcopy" value="1" <?php if($pdf_invoice_adminalertcopy) echo 'checked="checked"'; ?>/>
			  </td>
			</tr>
      */ ?>
			<tr>
			  <td><?php echo $_language->get('entry_invoiced_only'); ?></td>
			  <td>
				<input class="switch" type="checkbox" id="pdf_invoice_invoiced" name="pdf_invoice_invoiced" value="1" <?php if($pdf_invoice_invoiced) echo 'checked="checked"'; ?>/>
			  </td>
			</tr>
			<tr>
			  <td><?php echo $_language->get('entry_auto_generate'); ?></td>
			  <td>
				<input class="switch" type="checkbox" id="pdf_invoice_auto_generate" name="pdf_invoice_auto_generate" value="1" <?php if($pdf_invoice_auto_generate) echo 'checked="checked"'; ?>/>
			  </td>
			</tr>
			<tr>
			  <td><?php echo $_language->get('entry_manual_inv_no'); ?></td>
			  <td>
				<input class="switch" type="checkbox" id="pdf_invoice_manual_inv_no" name="pdf_invoice_manual_inv_no" value="1" <?php if($pdf_invoice_manual_inv_no) echo 'checked="checked"'; ?>/>
			  </td>
			</tr>
			<tr>
				<td><?php echo $_language->get('entry_auto_notify'); ?></td>
				<td class="checkbox_list">
						<?php foreach ($order_statuses as $order_status) { ?>
						
						<span><input class="checkable" type="checkbox" name="pdf_invoice_auto_notify[]" value="<?php echo $order_status['order_status_id']; ?>" <?php if (in_array($order_status['order_status_id'], (array) $pdf_invoice_auto_notify)) echo ' checked="checked"'; ?> data-label="<?php echo $order_status['name']; ?>"/></span>
						<?php } ?>
				</td>
			</tr>
      <tr>
				<td><?php echo $_language->get('entry_return_pdf'); ?></td>
				<td class="checkbox_list">
						<?php foreach ($return_statuses as $return_status) { ?>
						<span><input class="checkable" type="checkbox" name="pdf_invoice_return_pdf[]" value="<?php echo $return_status['return_status_id']; ?>" <?php if (in_array($return_status['return_status_id'], (array) $pdf_invoice_return_pdf)) echo ' checked="checked"'; ?> data-label="<?php echo $return_status['name']; ?>"/></span>
						<?php } ?>
				</td>
			</tr>
			<tr>
				<td><?php echo $_language->get('entry_adminlanguage'); ?></td>
				<td>
					<select class="form-control" name="pdf_invoice_adminlang">
						<option value=""><?php echo $_language->get('text_order_language'); ?></option>
					<?php foreach ($languages as $language) { ?>
						<?php if ($language['language_id'] == $pdf_invoice_adminlang) { ?>
						<option value="<?php echo $language['language_id']; ?>" selected="selected" style="background:url('<?php echo $language['image']; ?>') 3px 2px no-repeat;padding-left:25px;"><?php echo $language['name']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $language['language_id']; ?>" style="background:url('<?php echo $language['image']; ?>') 3px 2px no-repeat;padding-left:25px"> <?php echo $language['name']; ?></option>
						<?php } ?>
					<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td><?php echo $_language->get('entry_forcelanguage'); ?></td>
				<td>
					<select class="form-control" name="pdf_invoice_force_lang">
						<option value=""><?php echo $_language->get('text_order_language'); ?></option>
					<?php foreach ($languages as $language) { ?>
						<?php if ($language['language_id'] == $pdf_invoice_force_lang) { ?>
						<option value="<?php echo $language['language_id']; ?>" selected="selected" style="background:url('<?php echo $language['image']; ?>') 3px 2px no-repeat;padding-left:25px;"><?php echo $language['name']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $language['language_id']; ?>" style="background:url('<?php echo $language['image']; ?>') 3px 2px no-repeat;padding-left:25px"> <?php echo $language['name']; ?></option>
						<?php } ?>
					<?php } ?>
					</select>
				</td>
			</tr>
      <tr>
				<td><?php echo $_language->get('entry_display_mode'); ?></td>
				<td>
					<select class="form-control" name="pdf_invoice_display_mode">
						<option value=""><?php echo $_language->get('text_display_dl'); ?></option>
						<option value="view" <?php if ($pdf_invoice_display_mode == 'view') echo 'selected="selected"';?>><?php echo $_language->get('text_display_view'); ?></option>
						<option value="html" <?php if ($pdf_invoice_display_mode == 'html') echo 'selected="selected"';?>><?php echo $_language->get('text_display_html'); ?></option>
					</select>
				</td>
			</tr>
			<tr>
			  <td><?php echo $_language->get('entry_icon'); ?></td>
			  <td>
				<div style="width:380px">
				<?php foreach ($icons as $icon) { ?>
				  <label style="display:inline-block;width:70px;"><input type="radio" name="pdf_invoice_icon" value="<?php echo $icon; ?>" <?php if($pdf_invoice_icon == $icon) echo 'checked="checked"'; ?>/><img src="../image/invoice/<?php echo $icon; ?>"/></label>
				<?php } ?>
				</div>
			  </td>
			</tr>
		</table>
		</div>
		<?php } /*end: store_id*/ ?>
		<div class="tab-pane <?php if ($store_id) echo 'active'; ?>" id="tab-6">
			<table class="form">
			<tr>
				<td><?php echo $_language->get('entry_company_id'); ?></td>
				<td><input class="form-control" type="text" name="pdf_invoice_company_id" value="<?php echo $pdf_invoice_company_id; ?>" size="63"/></td>
			</tr>
				<tr>
					<td><?php echo $_language->get('entry_vat_number'); ?></td>
					<td><input class="form-control" type="text" name="pdf_invoice_vat_number" value="<?php echo $pdf_invoice_vat_number; ?>" size="63"/></td>
				</tr>
			<?php if ($group_settings && !$store_id) { ?>
			<tr>
				<td><?php echo $_language->get('entry_customer_vat'); ?></td>
				<td class="customer_groups">
					<div class="inlineEdit">
						<div class="switchBtn"><?php echo $_language->get('entry_customer_vat_btn'); ?></div>
						<div class="switchContent">
							<?php $i=0; foreach ($customer_groups as $group) { ?>
								<h4><?php echo $group['name']; ?></h4>
								<div>
									<span><?php echo $_language->get('entry_custom_comp_display'); ?></span>
									<input class="switch" type="checkbox"  id="customergroup<?php echo $i++; ?>" name="customer_groups[<?php echo $group['customer_group_id']; ?>][company_id_display]" value="1" <?php if($group['company_id_display']) echo 'checked="checked"'; ?> />
								</div>
								<div>
									<span><?php echo $_language->get('entry_custom_comp_required'); ?></span>
									<input class="switch" type="checkbox"  id="customergroup<?php echo $i++; ?>" name="customer_groups[<?php echo $group['customer_group_id']; ?>][company_id_required]" value="1" <?php if($group['company_id_required']) echo 'checked="checked"'; ?> />
								</div>
								<br />
								<div>
									<span><?php echo $_language->get('entry_custom_tax_display'); ?></span>
									<input class="switch" type="checkbox"  id="customergroup<?php echo $i++; ?>" name="customer_groups[<?php echo $group['customer_group_id']; ?>][tax_id_display]" value="1" <?php if($group['tax_id_display']) echo 'checked="checked"'; ?> />
								</div>
								<div>
									<span><?php echo $_language->get('entry_custom_tax_required'); ?></span>
									<input class="switch" type="checkbox"  id="customergroup<?php echo $i++; ?>" name="customer_groups[<?php echo $group['customer_group_id']; ?>][tax_id_required]" value="1" <?php if($group['tax_id_required']) echo 'checked="checked"'; ?> />
								</div>
							<?php } ?>
						</div>
					</div>
				</td>
			</tr>
			<?php } ?>
      <?php if ($OC_V2) { ?>
      <tr>
        <td><?php echo $_language->get('entry_custom_fields'); ?></td>
        <td class="colors">
        <?php if (!$custom_fields) { echo $_language->get('entry_custom_fields_empty'); } ?>
        <?php foreach ($custom_fields as $item) { ?>
        <div>
          <span><?php echo $item['name']; ?></span>
          <!--<input type="hidden" name="pdf_invoice_custom_fields[<?php echo $item['custom_field_id']; ?>]" value="0"/>-->
          <input class="switch" type="checkbox"  id="pdf_invoice_custom_fields_<?php echo $item['custom_field_id']; ?>" name="pdf_invoice_custom_fields[]" value="<?php echo $item['custom_field_id']; ?>" <?php echo in_array($item['custom_field_id'], (array) $_config->get('pdf_invoice_custom_fields')) ? 'checked="checked"':''; ?>/>
        </div>
        <?php } ?>
        </td>
      </tr>
      <?php } ?>
			<tr>
			  <td><?php echo $_language->get('entry_customer_id'); ?></td>
			  <td>
				<input class="switch" type="checkbox" id="pdf_invoice_customerid" name="pdf_invoice_customerid" value="1" <?php if($pdf_invoice_customerid) echo 'checked="checked"'; ?>/>
			  </td>
		  </tr>
      <tr>
			  <td><?php echo $_language->get('entry_user_comment'); ?></td>
			  <td>
				<input class="switch" type="checkbox" id="pdf_invoice_usercomment" name="pdf_invoice_usercomment" value="1" <?php if($_config->get('pdf_invoice_usercomment')) echo 'checked="checked"'; ?>/>
			  </td>
			</tr>
		  <tr>
				<td><?php echo $_language->get('entry_customer_format'); ?></td>
				<td>
					<?php echo $_language->get('entry_customer_prefix'); ?> <input class="form-control" type="text" name="pdf_invoice_customerprefix" value="<?php echo $pdf_invoice_customerprefix; ?>" size="9"  style="margin-right:30px"/>
					<?php echo $_language->get('entry_customer_size'); ?> <input class="form-control" type="text" name="pdf_invoice_customersize" value="<?php echo $pdf_invoice_customersize; ?>" size="1"/>
				</td>
			</tr>
			<tr>
				<td><?php echo $_language->get('entry_duedate'); ?></td>
				<td><input class="form-control" type="text" name="pdf_invoice_duedate" value="<?php echo $pdf_invoice_duedate; ?>" size="3"/></td>
			</tr>
      <tr>
			  <td><?php echo $_language->get('entry_duedate_invoice'); ?></td>
			  <td>
				<input class="switch" type="checkbox" id="pdf_invoice_duedate_invoice" name="pdf_invoice_duedate_invoice" value="1" <?php if($_config->get('pdf_invoice_duedate_invoice')) echo 'checked="checked"'; ?>/>
			  </td>
			</tr>
      <tr>
					<td><?php echo $_language->get('entry_filename'); ?></td>
					<td>
						<div class="inlineEdit">
							<div class="switchBtn">
								<?php
								$pdfFilename = array();
								if($_config->get('pdf_invoice_filename_prefix') && $_config->get('pdf_invoice_filename_'.$_config->get('config_language')))
									$pdfFilename[] = trim($_language->get('entry_filename_prefix'));
								if($_config->get('pdf_invoice_filename_invnum'))
									$pdfFilename[] = $_language->get('entry_filename_invnum');
								if($_config->get('pdf_invoice_filename_ordnum'))
									$pdfFilename[] =  $_language->get('entry_filename_ordnum');
								$pdfFilename = implode('-', $pdfFilename) ? implode('-', $pdfFilename).'.pdf' : 'invoice.pdf';
								?>
								<img style="position:relative;top:3px" src="<?php echo $_img_path; ?>pdf.png" alt="filename"/> <?php echo $pdfFilename; ?>
							</div>
							<div class="switchContent">
								<img style="position:relative;top:3px" src="<?php echo $_img_path; ?>pdf.png" alt="filename"/>
						<label><input type="checkbox" name="pdf_invoice_filename_prefix" value="1" <?php if($pdf_invoice_filename_prefix) echo 'checked="checked"'; ?> /><?php echo $_language->get('entry_filename_prefix'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="pdf_invoice_filename_invnum" value="1" <?php if($pdf_invoice_filename_invnum) echo 'checked="checked"'; ?> /><?php echo $_language->get('entry_filename_invnum'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="pdf_invoice_filename_ordnum" value="1" <?php if($pdf_invoice_filename_ordnum) echo 'checked="checked"'; ?> /><?php echo $_language->get('entry_filename_ordnum'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.pdf
							</div>
						</div>
					</td>
				</tr>
			</table>
			<table class="form">
			<tr>
			  <td colspan="2">
					<ul class="nav nav-tabs">
						<?php $f=0; foreach ($languages as $language) { ?>
							<li<?php if(!$f) { echo ' class="active"'; $f=1;} ?>><a href="#tab-language-<?php echo  $language['code']; ?>" data-toggle="tab"><img src="<?php echo $language['image']; ?>" alt=""/> <?php echo $language['name']; ?></a></li>
						<?php } ?>
					</ul>
					<div class="tab-content">
						<?php $f=0; foreach ($languages as $language) { ?>
							<div id="tab-language-<?php echo $language['code']; ?>" class="tab-pane<?php if(!$f) {echo ' active'; $f=1;} ?>">
								<table class="form">
									<tr>
										<td><?php echo $_language->get('entry_filename_prefix_text'); ?></td>
										<td><input class="form-control" type="text" name="pdf_invoice_filename_<?php echo $language['language_id']; ?>" value="<?php echo ${'pdf_invoice_filename_'.$language['language_id'] }; ?>" size="63"/></td>
									</tr>
									<tr>
										<td><?php echo $_language->get('entry_size'); ?></td>
										<td>
											<select class="form-control" name="pdf_invoice_size_<?php echo $language['language_id']; ?>">
												<option value="A4" <?php if(${'pdf_invoice_size_'.$language['language_id']} == 'A4') echo 'selected="selected"'; ?>>A4</option>
												<option value="A4-L" <?php if(${'pdf_invoice_size_'.$language['language_id']} == 'A4-L') echo 'selected="selected"'; ?>>A4 landscape</option>
												<option value="Letter" <?php if(${'pdf_invoice_size_'.$language['language_id']} == 'Letter') echo 'selected="selected"'; ?>>Letter</option>
												<option value="Letter-L" <?php if(${'pdf_invoice_size_'.$language['language_id']} == 'Letter-L') echo 'selected="selected"'; ?>>Letter landscape</option>
												<option value="Legal" <?php if(${'pdf_invoice_size_'.$language['language_id']} == 'Legal') echo 'selected="selected"'; ?>>Legal</option>
												<option value="Legal-L" <?php if(${'pdf_invoice_size_'.$language['language_id']} == 'Legal-L') echo 'selected="selected"'; ?>>Legal landscape</option>
                        <option value="A5" <?php if(${'pdf_invoice_size_'.$language['language_id']} == 'A5') echo 'selected="selected"'; ?>>A5</option>
												<option value="A5-L" <?php if(${'pdf_invoice_size_'.$language['language_id']} == 'A5-L') echo 'selected="selected"'; ?>>A5 landscape</option>
											</select>
										</td>
									</tr>
									<tr>
										<td><?php echo $_language->get('entry_footer'); ?></td>
										<td>
                      						<textarea name="pdf_invoice_footer_<?php echo $language['language_id']; ?>" id="pdf_invoice_footer_<?php echo $language['language_id']; ?>"><?php echo ${'pdf_invoice_footer_'.$language['language_id'] }; ?></textarea>
										</td>
									</tr>
								</table>
							</div>
						<?php } ?>
					</div>
				</td>
			</tr>
		</table>
		</div>
		<div class="tab-pane" id="tab-2">
			<table class="form">
				<tr>
				  <td><?php echo $_language->get('entry_logo'); ?></td>
				  <td valign="top">
					  
					  <?php if ($OC_V2) { ?>
							<a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb_header; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
							<input type="hidden" name="pdf_invoice_logo" value="<?php echo $pdf_invoice_logo; ?>" id="input-image" />
					  <?php } else { ?>
						<div class="image" style="text-align:center; float:left;"><img src="<?php echo $thumb_header; ?>" alt="" id="thumb_header" />
					  <input type="hidden" name="pdf_invoice_logo" value="<?php echo $pdf_invoice_logo; ?>" id="pdf_invoice_logo" />
					  <br />
					  </div>
					  <div style="margin-left:10px;float:left;"><br /><a onclick="image_upload('pdf_invoice_logo', 'thumb_header');"><?php echo $_language->get('text_browse'); ?></a><br /><br /><a onclick="$('#thumb_header').attr('src', '<?php echo $no_image; ?>'); $('#pdf_invoice_logo').attr('value', '');"><?php echo $_language->get('text_clear'); ?></a></div>
					  <?php } ?>
					</td>
				</tr>
				<tr>
				  <td><?php echo $_language->get('entry_template'); ?></td>
				  <td><select class="form-control" name="pdf_invoice_template">
					  <?php foreach ($templates as $template) { ?>
					  <?php if ($template == $pdf_invoice_template) { ?>
					  <option value="<?php echo $template; ?>" selected="selected"><?php echo $template; ?></option>
					  <?php } else { ?>
					  <option value="<?php echo $template; ?>"><?php echo $template; ?></option>
					  <?php } ?>
					  <?php } ?>
					</select></td>
				</tr>
				<tr>
				  <td></td>
				  <td id="template"><img src="<?php echo HTTP_CATALOG . 'catalog/view/theme/default/template/pdf/' . $pdf_invoice_template . '.png'; ?>" alt="No preview available for this template"/></td>
				</tr>
				<tr>
				  <td><?php echo $_language->get('entry_columns'); ?></td>
				  <td class="sortable">
					<?php foreach ($pdf_invoice_columns as $col => $val) { ?>
					<div class="sortable_div">
						<i class="fa fa-arrows-v"></i> <span class="label"><?php echo $_language->get('entry_col_'.$col); ?></span>
						<input type="hidden" name="pdf_invoice_columns[<?php echo $col; ?>]" value="0"/>
						<input class="switch" type="checkbox"  id="pdf_invoice_col_<?php echo $col; ?>" name="pdf_invoice_columns[<?php echo $col; ?>]" value="1" <?php echo $val ? 'checked="checked"':''; ?>/>
						<div class="clear"></div>
					</div>
					<?php } ?>
					<div class="unsortable_div">
						<span class="label"><?php echo $_language->get('entry_col_total_tax'); ?></span>
						<div class="clear"></div>
					</div>
				   </td>
				</tr>
				<tr>
				  <td><?php echo $_language->get('entry_tax'); ?></td>
				  <td class="colors">
					<div>
							<input type="hidden" name="pdf_invoice_total_tax" value="0"/>
							<input class="switch" type="checkbox"  id="pdf_invoice_total_tax" name="pdf_invoice_total_tax" value="1" <?php echo $pdf_invoice_total_tax ? 'checked="checked"':''; ?>/>
						</div>
				  </td>
				</tr>
				<tr>
				  <td><?php echo $_language->get('entry_totals_tax'); ?></td>
				  <td><select class="form-control" name="pdf_invoice_totals_tax">
					  <option value="0"><?php echo $_language->get('text_none'); ?></option>
					  <?php foreach ($tax_classes as $tax_class) { ?>
					  <?php if ($tax_class['tax_class_id'] == $pdf_invoice_totals_tax) { ?>
					  <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
					  <?php } else { ?>
					  <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
					  <?php } ?>
					  <?php } ?>
					</select></td>
				</tr>
				<tr>
				  <td><?php echo $_language->get('entry_product_options'); ?></td>
				  <td class="colors">
					<?php foreach ($pdf_invoice_options as $col => $val) { ?>
					<div>
						<span><?php echo $_language->get('entry_col_'.$col); ?></span>
						<input type="hidden" name="pdf_invoice_options[<?php echo $col; ?>]" value="0"/>
						<input class="switch" type="checkbox"  id="pdf_invoice_opt_<?php echo $col; ?>" name="pdf_invoice_options[<?php echo $col; ?>]" value="1" <?php echo $val ? 'checked="checked"':''; ?>/>
					</div>
					<?php } ?>
				  </td>
				</tr>
				<tr>
				  <td><?php echo $_language->get('entry_thumbsize'); ?></td>
				  <td class="form-inline"><input class="form-control" type="text" name="pdf_invoice_thumbwidth" value="<?php echo $pdf_invoice_thumbwidth; ?>" size="5"/>
					x
					<input class="form-control" type="text" name="pdf_invoice_thumbheight" value="<?php echo $pdf_invoice_thumbheight; ?>" size="5" />
				  </td>
				</tr>
       			<tr>
				  <td><?php echo $_language->get('entry_barcode'); ?></td>
				  <td class="multiple">
            <div>
							<input type="hidden" name="pdf_invoice_barcode[status]" value="0"/>
							<input class="switch" type="checkbox" id="pdf_invoice_barcode_status" name="pdf_invoice_barcode[status]" value="1" <?php echo !empty($pdf_invoice_barcode['status']) ? 'checked="checked"':''; ?>/>
						</div>
            <div>
              	<?php echo $_language->get('entry_barcode_type'); ?>
                <select class="form-control" name="pdf_invoice_barcode[type]">
                <?php foreach ($barcode_types as $bcode) { ?>
                  <option <?php if(!empty($pdf_invoice_barcode['type']) && $pdf_invoice_barcode['type'] == $bcode) echo 'selected="selected"'; ?>><?php echo $bcode; ?></option>
                <?php } ?>
              </select>
						</div>
            <div>
              	<?php echo $_language->get('entry_barcode_value'); ?>
                <select class="form-control" name="pdf_invoice_barcode[value]">
                  <option <?php if(!empty($pdf_invoice_barcode['value']) && $pdf_invoice_barcode['value'] == '{order_id}') echo 'selected="selected"'; ?> value="{order_id}"><?php echo $_language->get('text_barcode_order_id'); ?></option>
                  <option <?php if(!empty($pdf_invoice_barcode['value']) && $pdf_invoice_barcode['value'] == '{invoice_no}') echo 'selected="selected"'; ?> value="{invoice_no}"><?php echo $_language->get('text_barcode_invoice_no'); ?></option>
                  <option <?php if(!empty($pdf_invoice_barcode['value']) && $pdf_invoice_barcode['value'] == '{invoice_prefix}{invoice_no}') echo 'selected="selected"'; ?> value="{invoice_prefix}{invoice_no}"><?php echo $_language->get('text_barcode_full_invoice_no'); ?></option>
                  <option <?php if(!empty($pdf_invoice_barcode['value']) && $pdf_invoice_barcode['value'] == '{customer_id}') echo 'selected="selected"'; ?> value="{customer_id}"><?php echo $_language->get('text_barcode_customer_id'); ?></option>
                  <option <?php if(!empty($pdf_invoice_barcode['value']) && $pdf_invoice_barcode['value'] == '{order_url}') echo 'selected="selected"'; ?> value="{order_url}"><?php echo $_language->get('text_barcode_order_url'); ?></option>
              </select>
						</div>
				  </td>
				</tr>
				<tr>
				  <td><?php echo $_language->get('entry_colors'); ?></td>
				  <td class="colors">
					<div>
						<span><?php echo $_language->get('entry_color_text'); ?></span>
						<input name="pdf_invoice_color_text" class="colorpicker" value="<?php echo $pdf_invoice_color_text; ?>" />
					</div>
					<div>
						<span><?php echo $_language->get('entry_color_title'); ?></span>
						<input name="pdf_invoice_color_title" class="colorpicker" value="<?php echo $pdf_invoice_color_title; ?>" />
					</div>
					<div>
						<span><?php echo $_language->get('entry_color_thead'); ?></span>
						<input name="pdf_invoice_color_thead" class="colorpicker" value="<?php echo $pdf_invoice_color_thead; ?>" />
					</div>
					<div>
						<span><?php echo $_language->get('entry_color_theadtxt'); ?></span>
						<input name="pdf_invoice_color_theadtxt" class="colorpicker" value="<?php echo $pdf_invoice_color_theadtxt; ?>" />
					</div>
					<div>
						<span><?php echo $_language->get('entry_color_tborder'); ?></span>
						<input name="pdf_invoice_color_tborder" class="colorpicker" value="<?php echo $pdf_invoice_color_tborder; ?>" />
					</div>
					<div>
						<span><?php echo $_language->get('entry_color_footertxt'); ?></span>
						<input name="pdf_invoice_color_footertxt" class="colorpicker" value="<?php echo $pdf_invoice_color_footertxt; ?>" />
					</div>
				   </td>
				</tr>
        <tr>
				  <td><?php echo $_language->get('entry_watermark'); ?></td>
				  <td valign="top">
					  <?php if ($OC_V2) { ?>
							<a href="" id="thumb-watermark" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb_watermark; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
							<input type="hidden" name="pdf_invoice_watermark" value="<?php echo $pdf_invoice_watermark; ?>" id="input-watermark" />
					  <?php } else { ?>
						<div class="image" style="text-align:center; float:left;"><img src="<?php echo $thumb_watermark; ?>" alt="" id="thumb_header" />
					  <input type="hidden" name="pdf_invoice_watermark" value="<?php echo $pdf_invoice_watermark; ?>" id="pdf_invoice_watermark" />
					  <br />
					  </div>
					  <div style="margin-left:10px;float:left;"><br /><a onclick="image_upload('pdf_invoice_watermark', 'thumb_watermark');"><?php echo $_language->get('text_browse'); ?></a><br /><br /><a onclick="$('#thumb_watermark').attr('src', '<?php echo $no_image; ?>'); $('#pdf_invoice_watermark').attr('value', '');"><?php echo $_language->get('text_clear'); ?></a></div>
					  <?php } ?>
					</td>
				</tr>
		</table>
		</div>
		<div class="tab-pane" id="tab-4">
			<input type="hidden" name="pdf_invoice_blocks" value=""/>
			<ul class="nav nav-pills nav-stacked col-md-2" id="custom_blocks_tabs">
				<?php $module_row = 1; ?>
				 <?php foreach ($pdf_invoice_blocks as $module) { ?>
					<li<?php if($module_row === 1) echo ' class="active"'; ?>><a href="#tab-module-<?php echo $module_row; ?>" data-toggle="pill"><i class="fa fa-minus-circle" onclick="$('#tab-module-<?php echo $module_row; ?>').remove(); $(this).closest('li').remove(); $('#custom_blocks_tabs li:first a').trigger('click'); return false;"></i><?php echo $_language->get('tab_block') . ' ' . $module_row; ?></a></li>
					<?php $module_row++; ?>
				<?php } ?>
				<li id="module-add"><a><i onclick="addModule();" class="fa fa-plus-circle"></i><?php echo $_language->get('tab_add_block'); ?></a></li>
			</ul>
			<div class="tab-content col-md-10">
				<?php $module_row = 1; ?>
				<?php foreach ($pdf_invoice_blocks as $module) { ?>
				<div class="tab-pane <?php if($module_row === 1) echo ' active'; ?>" id="tab-module-<?php echo $module_row; ?>" class="vtabs-content">
					<ul class="nav nav-tabs">
						<?php $f=0; foreach ($languages as $language) { ?>
							<li class="<?php if(!$f) echo ' active'; $f=1; ?>"><a href="#tab-language-<?php echo $module_row; ?>-<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
						<?php } ?>
					</ul>
				   <div class="tab-content">
					  <?php $f=0; foreach ($languages as $language) { ?>
					  <div class="tab-pane<?php if(!$f) echo ' active'; $f=1; ?>" id="tab-language-<?php echo $module_row; ?>-<?php echo $language['language_id']; ?>">
						<table class="form">
						 <tr>
							<td><?php echo $_language->get('entry_block_title'); ?></td>
							<td><input class="form-control" type="text" name="pdf_invoice_blocks[<?php echo $module_row; ?>][title][<?php echo $language['language_id']; ?>]; ?>" value="<?php echo isset($module['title'][$language['language_id']]) ? $module['title'][$language['language_id']] : ''; ?>" size="63"/></td>
						 </tr>
						  <tr>
							<td><button type="button" class="btn btn-default btn-xs info-btn" data-toggle="modal" data-target="#modal-info" data-info="tags"><i class="fa fa-info"></i></button><?php echo $_language->get('entry_block_message'); ?></td>
							<td>
                <?php if (defined('_JEXEC')) {
                    $desc = isset($module['description'][$language['language_id']]) ? $module['description'][$language['language_id']] : '';
                    echo MijoShop::get('base')->editor()->display("pdf_invoice_blocks[".$module_row."][description][".$language['language_id']."]", $desc, '97% !important', '320', '50', '11');
                  } else { ?>
                  <textarea name="pdf_invoice_blocks[<?php echo $module_row; ?>][description][<?php echo $language['language_id']; ?>]" id="description-<?php echo $module_row; ?>-<?php echo $language['language_id']; ?>"><?php echo isset($module['description'][$language['language_id']]) ? $module['description'][$language['language_id']] : ''; ?></textarea>
                <?php } ?>
              </td>
						  </tr>
						</table>
					  </div>
					  <?php } ?>
					</div>
				  <table class="form">
          <tr>
					  <td><?php echo $_language->get('entry_block_target'); ?></td>
					  <td><select class="form-control" name="pdf_invoice_blocks[<?php echo $module_row; ?>][target]">
						  <?php foreach ($block_targets as $key => $name) { ?>
						  <?php if ($key == $module['target']) { ?>
						  <option value="<?php echo $key; ?>" selected="selected"><?php echo $name; ?></option>
						  <?php } else { ?>
						  <option value="<?php echo $key; ?>"><?php echo $name; ?></option>
						  <?php } ?>
						  <?php } ?>
						</select></td>
					</tr>
					<tr>
					  <td><?php echo $_language->get('entry_block_position'); ?></td>
					  <td><select class="form-control" name="pdf_invoice_blocks[<?php echo $module_row; ?>][position]">
						  <?php foreach ($block_positions as $key => $name) { ?>
						  <?php if ($key == $module['position']) { ?>
						  <option value="<?php echo $key; ?>" selected="selected"><?php echo $name; ?></option>
						  <?php } else { ?>
						  <option value="<?php echo $key; ?>"><?php echo $name; ?></option>
						  <?php } ?>
						  <?php } ?>
						</select></td>
					</tr>
					<tr>
					  <td><?php echo $_language->get('entry_block_display'); ?></td>
					  <td><select class="form-control" name="pdf_invoice_blocks[<?php echo $module_row; ?>][display]">
						<optgroup label="<?php echo $_language->get('entry_display_always'); ?>">
							<option value="always|1"<?php echo ($module['display'] == 'always|1') ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
							<option value="always|0"<?php echo ($module['display'] == 'always|0') ? ' selected="selected"' : ''; ?>><?php echo $text_disabled; ?></option>
						</optgroup>
            <optgroup label="<?php echo $_language->get('entry_display_comment'); ?>">
							<option value="comment|1"<?php echo ($module['display'] == 'comment|1') ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
						</optgroup>
						<optgroup label="<?php echo $_language->get('entry_display_group'); ?>">
						<?php foreach ($customer_groups as $value) { ?>
							 <option value="customer_group_id|<?php echo $value['customer_group_id']; ?>"<?php echo ($module['display'] == 'customer_group_id|'.$value['customer_group_id']) ? ' selected="selected"' : ''; ?>><?php echo  $value['name']; ?></option>
						<?php } ?>
						</optgroup>
						<optgroup label="<?php echo $_language->get('entry_display_orderstatus'); ?>">
						<?php foreach ($order_statuses as $value) { ?>
							 <option value="order_status_id|<?php echo $value['order_status_id']; ?>"<?php echo ($module['display'] == 'order_status_id|'.$value['order_status_id']) ? ' selected="selected"' : ''; ?>><?php echo  $value['name']; ?></option>
						<?php } ?>
						</optgroup>
            <optgroup label="<?php echo $_language->get('entry_display_credit'); ?>">
							<option value="credit|1"<?php echo ($module['display'] == 'credit|1') ? ' selected="selected"' : ''; ?>><?php echo $_language->get('text_is_credit'); ?></option>
							<option value="credit|0"<?php echo ($module['display'] == 'credit|0') ? ' selected="selected"' : ''; ?>><?php echo $_language->get('text_isnot_credit'); ?></option>
						</optgroup>
            <optgroup label="<?php echo $_language->get('entry_display_invnum'); ?>">
							<option value="invnum|1"<?php echo ($module['display'] == 'invnum|1') ? ' selected="selected"' : ''; ?>><?php echo $_language->get('text_has_invnum'); ?></option>
							<option value="invnum|0"<?php echo ($module['display'] == 'invnum|0') ? ' selected="selected"' : ''; ?>><?php echo $_language->get('text_no_invnum'); ?></option>
						</optgroup>
						<optgroup label="<?php echo $_language->get('entry_display_payment'); ?>">
						<?php foreach ($payment_methods as $value) { ?>
							 <option value="payment_code|<?php echo $value['code']; ?>"<?php echo ($module['display'] == 'payment_code|'.$value['code']) ? ' selected="selected"' : ''; ?>><?php echo  $value['name']; ?></option>
						<?php } ?>
						</optgroup>
						<optgroup label="<?php echo $_language->get('entry_display_shipping'); ?>">
						<?php foreach ($shipping_methods as $value) { ?>
							 <option value="shipping_code|<?php echo $value['code']; ?>"<?php echo ($module['display'] == 'shipping_code|'.$value['code']) ? ' selected="selected"' : ''; ?>><?php echo  $value['name']; ?></option>
						<?php } ?>
						</optgroup>
						<optgroup label="<?php echo $_language->get('entry_display_payment_zone'); ?>">
						<?php foreach ($geo_zones as $value) { ?>
							 <option value="shipping_zone|<?php echo $value['geo_zone_id']; ?>"<?php echo ($module['display'] == 'shipping_zone|'.$value['geo_zone_id']) ? ' selected="selected"' : ''; ?>><?php echo  $value['name']; ?></option>
						<?php } ?>
						</optgroup>
						<optgroup label="<?php echo $_language->get('entry_display_shipping_zone'); ?>">
						<?php foreach ($geo_zones as $value) { ?>
							 <option value="payment_zone|<?php echo $value['geo_zone_id']; ?>"<?php echo ($module['display'] == 'payment_zone|'.$value['geo_zone_id']) ? ' selected="selected"' : ''; ?>><?php echo  $value['name']; ?></option>
						<?php } ?>
						</optgroup>
            <?php if (in_array('quick_status_updater', $installed_modules)) { ?>
            <optgroup label="<?php echo $_language->get('entry_display_qosu'); ?>">
							 <option value="tracking|1"<?php echo ($module['display'] == 'tracking|1') ? ' selected="selected"' : ''; ?>><?php echo $_language->get('entry_display_qosu_tracking'); ?></option>
						</optgroup>
						<?php } ?>
						</select></td>
					</tr>
					<tr>
					  <td><?php echo $_language->get('entry_sort_order'); ?></td>
					  <td><input class="form-control" type="text" name="pdf_invoice_blocks[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
					</tr>
				  </table>
				</div>
				<?php $module_row++; ?>
				<?php } ?>
			</div>
      <div class="clearfix"></div>
		</div>
		<?php if (true || !$store_id) { ?>
		<div class="tab-pane" id="tab-5">
			<table class="form">
				<tr>
				  <td><?php echo $_language->get('entry_packingslip'); ?></td>
				  <td>
					<input class="switch" type="checkbox" id="pdf_invoice_packingslip" name="pdf_invoice_packingslip" value="1" <?php if($pdf_invoice_packingslip) echo 'checked="checked"'; ?>/>
				  </td>
				</tr>
				<tr>
				  <td><?php echo $_language->get('entry_template'); ?></td>
				  <td><select class="form-control" name="pdf_invoice_sliptemplate">
					  <?php foreach ($slip_templates as $slip_template) { ?>
					  <?php if ($slip_template == $pdf_invoice_sliptemplate) { ?>
					  <option value="<?php echo $slip_template; ?>" selected="selected"><?php echo $slip_template; ?></option>
					  <?php } else { ?>
					  <option value="<?php echo $slip_template; ?>"><?php echo $slip_template; ?></option>
					  <?php } ?>
					  <?php } ?>
					</select></td>
				</tr>
				<tr>
				  <td></td>
				  <td id="sliptemplate"><img src="<?php echo HTTP_CATALOG . 'catalog/view/theme/default/template/pdf/packingslip/' . $pdf_invoice_sliptemplate . '.png'; ?>" alt="No preview available for this template"/></td>
				</tr>
				<tr>
				  <td><?php echo $_language->get('entry_logo'); ?></td>
				  <td>
					<input class="switch" type="checkbox" id="pdf_invoice_sliplogo" name="pdf_invoice_sliplogo" value="1" <?php if($pdf_invoice_sliplogo) echo 'checked="checked"'; ?>/>
				  </td>
				</tr>
        <tr>
          <td><?php echo $_language->get('entry_user_comment_slip'); ?></td>
          <td>
          <input class="switch" type="checkbox" id="pdf_invoice_slip_usercomment" name="pdf_invoice_slip_usercomment" value="1" <?php if($_config->get('pdf_invoice_slip_usercomment')) echo 'checked="checked"'; ?>/>
          </td>
        </tr>
        <tr>
          <td><?php echo $_language->get('entry_slip_summary'); ?></td>
          <td>
          <input class="switch" type="checkbox" id="pdf_invoice_slip_summary" name="pdf_invoice_slip_summary" value="1" <?php if($_config->get('pdf_invoice_slip_summary')) echo 'checked="checked"'; ?>/>
          </td>
        </tr>
				<tr>
				  <td><?php echo $_language->get('entry_columns'); ?></td>
				  <td class="sortable">
					<?php foreach ($pdf_invoice_slip_columns as $col => $val) { ?>
					<div class="sortable_div">
						<i class="fa fa-arrows-v"></i> <span class="label"><?php echo $_language->get('entry_col_'.$col); ?></span>
						<input type="hidden" name="pdf_invoice_slip_columns[<?php echo $col; ?>]" value="0"/>
						<input class="switch" type="checkbox"  id="pdf_invoice_slip_col_<?php echo $col; ?>" name="pdf_invoice_slip_columns[<?php echo $col; ?>]" value="1" <?php echo $val ? 'checked="checked"':''; ?>/>
						<div class="clear"></div>
					</div>
					<?php } ?>
				   </td>
				</tr>
        </tr>
       			<tr>
				  <td><?php echo $_language->get('entry_barcode_column'); ?></td>
				  <td class="multiple">
            <div>
              <?php echo $_language->get('entry_barcode_type'); ?>
              <select class="form-control" class="form-control" name="pdf_invoice_slip_col_barcode[type]">
              <?php foreach ($barcode_types as $bcode) { ?>
                <option <?php if(!empty($pdf_invoice_slip_col_barcode['type']) && $pdf_invoice_slip_col_barcode['type'] == $bcode) echo 'selected="selected"'; ?>><?php echo $bcode; ?></option>
              <?php } ?>
              </select>
						</div>
            <div>
              	<?php echo $_language->get('entry_barcode_value'); ?>
                <select class="form-control" class="form-control" name="pdf_invoice_slip_col_barcode[value]">
                  <option <?php if(!empty($pdf_invoice_slip_col_barcode['value']) && $pdf_invoice_slip_col_barcode['value'] == 'product_id') echo 'selected="selected"'; ?> value="product_id"><?php echo $_language->get('entry_col_product_id'); ?></option>
                  <option <?php if(!empty($pdf_invoice_slip_col_barcode['value']) && $pdf_invoice_slip_col_barcode['value'] == 'sku') echo 'selected="selected"'; ?> value="sku"><?php echo $_language->get('entry_col_sku'); ?></option>
                  <option <?php if(!empty($pdf_invoice_slip_col_barcode['value']) && $pdf_invoice_slip_col_barcode['value'] == 'upc') echo 'selected="selected"'; ?> value="upc"><?php echo $_language->get('entry_col_upc'); ?></option>
                  <option <?php if(!empty($pdf_invoice_slip_col_barcode['value']) && $pdf_invoice_slip_col_barcode['value'] == 'ean') echo 'selected="selected"'; ?> value="ean"><?php echo $_language->get('entry_col_ean'); ?></option>
              </select>
						</div>
				  </td>
				</tr>
       	<tr>
				  <td><?php echo $_language->get('entry_barcode'); ?></td>
				  <td class="multiple">
            <div>
							<input type="hidden" name="pdf_invoice_slip_barcode[status]" value="0"/>
							<input class="switch" type="checkbox" id="pdf_invoice_slip_barcode_status" name="pdf_invoice_slip_barcode[status]" value="1" <?php echo !empty($pdf_invoice_slip_barcode['status']) ? 'checked="checked"':''; ?>/>
						</div>
            <div>
              	<?php echo $_language->get('entry_barcode_type'); ?>
                <select class="form-control" class="form-control" name="pdf_invoice_slip_barcode[type]">
                <?php foreach ($barcode_types as $bcode) { ?>
                  <option <?php if(!empty($pdf_invoice_slip_barcode['type']) && $pdf_invoice_slip_barcode['type'] == $bcode) echo 'selected="selected"'; ?>><?php echo $bcode; ?></option>
                <?php } ?>
              </select>
						</div>
            <div>
              	<?php echo $_language->get('entry_barcode_value'); ?>
                <select class="form-control" class="form-control" name="pdf_invoice_slip_barcode[value]">
                  <option <?php if(!empty($pdf_invoice_slip_barcode['value']) && $pdf_invoice_slip_barcode['value'] == '{order_id}') echo 'selected="selected"'; ?> value="{order_id}"><?php echo $_language->get('text_barcode_order_id'); ?></option>
                  <option <?php if(!empty($pdf_invoice_slip_barcode['value']) && $pdf_invoice_slip_barcode['value'] == '{invoice_no}') echo 'selected="selected"'; ?> value="{invoice_no}"><?php echo $_language->get('text_barcode_invoice_no'); ?></option>
                  <option <?php if(!empty($pdf_invoice_slip_barcode['value']) && $pdf_invoice_slip_barcode['value'] == '{invoice_prefix}{invoice_no}') echo 'selected="selected"'; ?> value="{invoice_prefix}{invoice_no}"><?php echo $_language->get('text_barcode_full_invoice_no'); ?></option>
                  <option <?php if(!empty($pdf_invoice_slip_barcode['value']) && $pdf_invoice_slip_barcode['value'] == '{customer_id}') echo 'selected="selected"'; ?> value="{customer_id}"><?php echo $_language->get('text_barcode_customer_id'); ?></option>
                  <option <?php if(!empty($pdf_invoice_slip_barcode['value']) && $pdf_invoice_slip_barcode['value'] == '{order_url}') echo 'selected="selected"'; ?> value="{order_url}"><?php echo $_language->get('text_barcode_order_url'); ?></option>
              </select>
						</div>
				  </td>
				</tr>
			</table>
		</div>
		<div class="tab-pane" id="tab-3">
			<table class="form">
				<tr>
				  <td><?php echo $_language->get('entry_backup'); ?></td>
				  <td>
					<input class="switch" type="checkbox" id="pdf_invoice_backup" name="pdf_invoice_backup" value="1" <?php if($pdf_invoice_backup) echo 'checked="checked"'; ?>/>
				  </td>
				</tr>
				<tr>
					<td><?php echo $_language->get('entry_backup_on'); ?></td>
					<td>
						<select class="form-control" name="pdf_invoice_backup_moment">
							<option value="order" <?php if($pdf_invoice_backup_moment == 'order') echo 'selected="selected"'; ?>><?php echo $_language->get('entry_backup_onorder'); ?></option>
							<option value="invoiceno" <?php if($pdf_invoice_backup_moment == 'invoiceno') echo 'selected="selected"'; ?>><?php echo $_language->get('entry_backup_oninvoice'); ?></option>
							<option value="manual" <?php if($pdf_invoice_backup_moment == 'manual') echo 'selected="selected"'; ?>><?php echo $_language->get('entry_backup_onmanual'); ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td><?php echo $_language->get('entry_backup_structure'); ?></td>
					<td>
						<select class="form-control" name="pdf_invoice_backup_structure">
							<option value="" <?php if($pdf_invoice_backup_structure == '') echo 'selected="selected"'; ?>><?php echo $_language->get('entry_structure_1'); ?></option>
							<option value="Y" <?php if($pdf_invoice_backup_structure == 'Y') echo 'selected="selected"'; ?>><?php echo $_language->get('entry_structure_2'); ?></option>
							<option value="Y/m" <?php if($pdf_invoice_backup_structure == 'Y/m') echo 'selected="selected"'; ?>><?php echo $_language->get('entry_structure_3'); ?></option>
							<option value="Y/m/d" <?php if($pdf_invoice_backup_structure == 'Y/m/d') echo 'selected="selected"'; ?>><?php echo $_language->get('entry_structure_4'); ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td><?php echo $_language->get('entry_size'); ?></td>
					<td>
						<select class="form-control" name="pdf_invoice_backup_size">
							<option value="A4" <?php if($pdf_invoice_backup_size == 'A4') echo 'selected="selected"'; ?>>A4</option>
							<option value="A4-L" <?php if($pdf_invoice_backup_size == 'A4-L') echo 'selected="selected"'; ?>>A4 landscape</option>
							<option value="Letter" <?php if($pdf_invoice_backup_size == 'Letter') echo 'selected="selected"'; ?>>Letter</option>
							<option value="Letter-L" <?php if($pdf_invoice_backup_size == 'Letter-L') echo 'selected="selected"'; ?>>Letter landscape</option>
							<option value="Legal" <?php if($pdf_invoice_backup_size == 'Legal') echo 'selected="selected"'; ?>>Legal</option>
							<option value="Legal-L" <?php if($pdf_invoice_backup_size == 'Legal-L') echo 'selected="selected"'; ?>>Legal landscape</option>
						</select>
					</td>
				</tr>
				<?php
				$pdfFilename = array();
				if($_config->get('pdf_invoice_backup_prefix'))
					$pdfFilename[] = trim($_config->get('pdf_invoice_backup_prefix'));
				if($_config->get('pdf_invoice_backup_invnum'))
					$pdfFilename[] = $_language->get('entry_filename_invnum');
				if($_config->get('pdf_invoice_backup_ordnum'))
					$pdfFilename[] = $_language->get('entry_filename_ordnum');
				$pdfFilename = implode('-', $pdfFilename);
				?>
				<tr>
					<td><?php echo $_language->get('entry_filename'); ?></td>
					<td>
						<div class="inlineEdit">
							<div class="switchBtn">
								<img style="position:relative;top:3px" src="<?php echo $_img_path; ?>pdf.png" alt="filename"/> <?php echo $pdfFilename; ?>.pdf
							</div>
							<div class="switchContent form-inline">
								<img style="position:relative;top:3px" src="<?php echo $_img_path; ?>pdf.png" alt="filename"/>
								<input class="form-control" type="text" name="pdf_invoice_backup_prefix" value="<?php echo $pdf_invoice_backup_prefix; ?>"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<label><input type="checkbox" name="pdf_invoice_backup_invnum" value="1" <?php if($pdf_invoice_backup_invnum) echo 'checked="checked"'; ?> /><?php echo $_language->get('entry_filename_invnum'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<label><input type="checkbox" name="pdf_invoice_backup_ordnum" value="1" <?php if($pdf_invoice_backup_ordnum) echo 'checked="checked"'; ?> /><?php echo $_language->get('entry_filename_ordnum'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.pdf
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td style="vertical-align:top"><?php echo $_language->get('entry_backup_folder'); ?></td>
					<td>
						<div class="inlineEdit">
							<div class="switchBtn"><img style="position:relative;top:2px" src="<?php echo $_img_path; ?>directory.png" alt="folder"/> <?php echo $pdf_invoice_backup_folder; ?></div>
							<div class="switchContent form-inline">
								<img style="position:relative;top:2px" src="<?php echo $_img_path; ?>directory.png" alt="folder"/> <input class="form-control" type="text" name="pdf_invoice_backup_folder" value="<?php echo $pdf_invoice_backup_folder; ?>" size="63"/>
								<br /><br /><span style="color:#049606" onclick="$('.backup_folder').hide();$('.backup_folder_btn').show();"><?php echo $_language->get('text_backup_folder_warning'); ?>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td><?php echo $_language->get('entry_backup_browser'); ?></td>
					<td><div id="pdfbrowser"></div></td>
				</tr>
			</table>
		</div>
		<div class="tab-pane" id="tab-about">
			<table class="form about">
				<tr>
					<td colspan="2" style="text-align:center;padding:30px 0 50px"><img src="<?php echo $_img_path; ?>logo<?php echo rand(1,2); ?>.gif"/></td>
				</tr>
				<tr>
					<td>Version</td>
					<td><?php echo $module_version; ?> - <?php echo $module_type; ?></td>
				</tr>
				<tr>
					<td>Free support</td>
					<td>I take care of maintaining my modules at top quality and affordable price.<br/>In case of bug, incompatibility, or if you want a new feature, just contact me on my mail.</td>
				</tr>
				<tr>
					<td>Contact</td>
					<td><a href="mailto:support@geekodev.com">support@geekodev.com</a></td>
				</tr>
				<tr>
					<td>Links</td>
					<td>
						If you like this module, please consider to make a star rating <span style="position:relative;top:3px;width:80px;height:17px;display:inline-block;background:url(data:data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAARCAYAAADUryzEAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEgAACxIB0t1+/AAAABZ0RVh0Q3JlYXRpb24gVGltZQAwNy8wNy8xMrG4sToAAAAcdEVYdFNvZnR3YXJlAEFkb2JlIEZpcmV3b3JrcyBDUzbovLKMAAACr0lEQVQ4jX1US0+TURA98/Xri0KBYqG8BDYItBoIBhFBBdRNTTQx0Q0gujBiAkEXxoXxD6iJbRcaY1iQEDXqTgwQWkWDIBU3VqWQoEgECzUU+n5910VbHhacZHLvzD05c+fMzaVhgxYJIwIYi+8B8FJ5bzjob9ucB4DmLttGMGyoAGMsyc1G7bEvA91roz2NL7Y7TziHHSxFmWsorbuUFgn79BaTLnMn3LYEZqPukCKruFAUGEd54w1ekqK69x8CSkoqMnJv72noTmN+O9Q5KlE44GqxmHTS7Qho5MH+X8SJUuMhAIbM/CrS1tSnCYsmkOoUnO7SiP3dHV8Mw5AoKkRCfTwR96ei+ZZGVVDDJQhIWAVbfhjDe8eQnd/Aq8+/VAIsAcGbR8ejQiR8jcwGbYZEkTFVd7I9B4IXcL+GEPwdK4SN0XJSDaCoAvHZsA4/93hWHNVNnbZpjoG5gl7XvpFnxggxAZRaA0rokliIAIkaxMnwdWLE7XW77jd12qYBgCMiNHfZlhgTCkZfPfUDBAYGItoiL0lK8N0+51txzD1u7Ji8njTGpk6bg/iUhSiU4GT5YOtPL940AOfiDyHod9/dMsYEzmLS5bBoKE/ES8ECCyACSF4IFledAdhd2SIFUdtmAp7i92QM+uKqVg6RJXDKakCcjyjSwcldMUDgG7I0h8WKdI0ewM2kFuTpmlb1bp2UMYBJyjBjm/FYh57MjA/1+1wuESNZOfjoLPwe516zUSdLIgi6l+sl3CIW5leD7/v7HPNTE+cOtr8tDXhWy+zWAcvnDx/XoiEPiirPBomgXxd32KAFEWp3FR0YdP60pop4sfHI5cmr+MfMRl2tXKnqzS5pyFuaHRusu2A5EyeoAEAQS2Q94VDg4pY/YUOf9ZgxnBaJJSeOdny6AgB/AYEpKtpaTusRAAAAAElFTkSuQmCC)"></span> on the module page :]<br/><br/>
						<!-- links -->
						<b>Module page :</b> <a target="new" href="https://www.opencart.com/index.php?route=extension/extension/info&extension_id=15075">PDF Invoice Pro</a><br/>
						<b>Other modules :</b> <a target="new" href="https://www.opencart.com/index.php?route=marketplace/extension&filter_member=GeekoDev">My modules on opencart</a><br/>
					</td>
				</tr>
			</table>
		</div>
		<?php } /*end: store_id*/ ?>
		</div>
      </form>
    </div>
  </div>
</div>
</div>
<script type="text/javascript"><!--
$('input.switch').iToggle({easing: 'swing',speed: 200});
jQuery.each( jQuery('input.checkable'), function(){ jQuery(this).prettyCheckable(); });

$('select[name=store]').change(function(){
	document.location = 'index.php?route=module/pdf_invoice&<?php echo $token; ?>&store_id='+$(this).val();
});

$('select[name=pdf_invoice_template]').change(function(){
	$('#template img').attr('src', '<?php echo HTTP_CATALOG; ?>catalog/view/theme/default/template/pdf/'+$(this).val()+'.png');
});
$('select[name=pdf_invoice_sliptemplate]').change(function(){
	$('#sliptemplate img').attr('src', '<?php echo HTTP_CATALOG; ?>catalog/view/theme/default/template/pdf/packingslip/'+$(this).val()+'.png');
});
$('#template img, #sliptemplate img').click(function(){$(this).toggleClass('full');});

$('#pdfbrowser').fileTree({script:'index.php?route=module/pdf_invoice/tree&<?php echo $token; ?>'});
--></script>
<script type="text/javascript"><!--
$('.inlineEdit .switchBtn').click(function(){
	$(this).toggle();
	$(this).next().toggle();
});
$(".colorpicker").spectrum({
	preferredFormat: "hex",
    showInput: true,
    allowEmpty:true,
	clickoutFiresChange: true,
	showInitial: true,
	showButtons: false
	//showPalette: true
});
--></script>
<?php if (!defined('_JEXEC') && !$OC_V2) { ?>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<?php } ?>
<!-- custom blocks -->
<?php if (!defined('_JEXEC')) { ?>
<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
  <?php if ($OC_V2) { ?>
    $('#pdf_invoice_footer_<?php echo $language['language_id']; ?>').summernote({
      height: 300
    });
  <?php } else { ?>
    CKEDITOR.replace('pdf_invoice_footer_<?php echo $language['language_id']; ?>', {
      filebrowserBrowseUrl: 'index.php?route=common/filemanager&<?php echo $token; ?>',
      filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&<?php echo $token; ?>',
      filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&<?php echo $token; ?>',
      filebrowserUploadUrl: 'index.php?route=common/filemanager&<?php echo $token; ?>',
      filebrowserImageUploadUrl: 'index.php?route=common/filemanager&<?php echo $token; ?>',
      filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&<?php echo $token; ?>'
    });
  <?php } ?>
<?php } ?>
    
<?php $module_row = 1; ?>
<?php foreach ($pdf_invoice_blocks as $module) { ?>
	<?php foreach ($languages as $language) { ?>
		<?php if ($OC_V2) { ?>
			$('#description-<?php echo $module_row; ?>-<?php echo $language['language_id']; ?>').summernote({
				height: 300
			});
		<?php } else { ?>
			CKEDITOR.replace('description-<?php echo $module_row; ?>-<?php echo $language['language_id']; ?>', {
				filebrowserBrowseUrl: 'index.php?route=common/filemanager&<?php echo $token; ?>',
				filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&<?php echo $token; ?>',
				filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&<?php echo $token; ?>',
				filebrowserUploadUrl: 'index.php?route=common/filemanager&<?php echo $token; ?>',
				filebrowserImageUploadUrl: 'index.php?route=common/filemanager&<?php echo $token; ?>',
				filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&<?php echo $token; ?>'
			});
		<?php } ?>
	<?php } ?>
	<?php $module_row++; ?>
<?php } ?>
//--></script> 
<?php } ?>
<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {	
	html  = '<div class="tab-pane" id="tab-module-' + module_row + '">';
	html += '  <ul class="nav nav-tabs">';
    <?php $f=0; foreach ($languages as $language) { ?>
    html += '    <li class="<?php if(!$f) echo ' active'; $f=1; ?>"><a href="#tab-language-'+ module_row + '-<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>';
    <?php } ?>
	html += '  </ul>';
	
	html += '  <div class="tab-content">';

	<?php $f=0; foreach ($languages as $language) { ?>
	html += '    <div class="tab-pane<?php if(!$f) echo ' active'; $f=1; ?>" id="tab-language-'+ module_row + '-<?php echo $language['language_id']; ?>">';
	html += '      <table class="form">';
	html += '        <tr>';
	html += '          <td><?php echo htmlspecialchars($_language->get('entry_block_title'), ENT_QUOTES); ?></td>';
	html += '          <td><input class="form-control" type="text" name="pdf_invoice_blocks[' + module_row + '][title][<?php echo $language['language_id']; ?>]; ?>" size="63"/></td>';
	html += '        </tr>';
	html += '        <tr>';
	html += '          <td><button type="button" class="btn btn-default btn-xs info-btn" data-toggle="modal" data-target="#modal-info" data-info="tags"><i class="fa fa-info"></i></button><?php echo htmlspecialchars($_language->get('entry_block_message'), ENT_QUOTES); ?></td>';
  <?php if (defined('_JEXEC')) { ?>
  html += '          <td><?php echo preg_replace(array('/\s+/',"/'/"), array(' ',"\\'"), MijoShop::get('base')->editor()->display("pdf_invoice_blocks[".$module_row."][description][".$language['language_id']."]", '', '97% !important', '320', '50', '11')); ?></td>';
  <?php } else { ?>
	html += '          <td><textarea name="pdf_invoice_blocks[' + module_row + '][description][<?php echo $language['language_id']; ?>]" id="description-' + module_row + '-<?php echo $language['language_id']; ?>"></textarea></td>';
  <?php } ?>
	html += '        </tr>';
	html += '      </table>';
	html += '    </div>';
	<?php } ?>

	html += '  <table class="form">';
  html += '    <tr>';
	html += '      <td><?php echo $_language->get('entry_block_target'); ?></td>';
	html += '      <td><select class="form-control" name="pdf_invoice_blocks[' + module_row + '][target]">';
	<?php foreach ($block_targets as $key => $block_target) { ?>
	html += '           <option value="<?php echo $key; ?>"><?php echo addslashes($block_target); ?></option>';
	<?php } ?>
	html += '      </select></td>';
	html += '    </tr>';
	html += '    <tr>';
	html += '      <td><?php echo $_language->get('entry_block_position'); ?></td>';
	html += '      <td><select class="form-control" name="pdf_invoice_blocks[' + module_row + '][position]">';
	<?php foreach ($block_positions as $key => $block_position) { ?>
	html += '           <option value="<?php echo $key; ?>"><?php echo addslashes($block_position); ?></option>';
	<?php } ?>
	html += '      </select></td>';
	html += '    </tr>';
	html += '    <tr>';
	html += '      <td><?php echo htmlspecialchars($_language->get('entry_block_display'), ENT_QUOTES); ?></td>';
	html += '      <td><select class="form-control" name="pdf_invoice_blocks[' + module_row + '][display]">';
	html += '      <optgroup label="<?php echo $_language->get('entry_display_always'); ?>">';
	html += '        <option value="always|1"><?php echo htmlspecialchars($text_enabled, ENT_QUOTES); ?></option>';
	html += '        <option value="always|0"><?php echo htmlspecialchars($text_disabled, ENT_QUOTES); ?></option>';
	html += '      </optgroup>';
	html += '      <optgroup label="<?php echo htmlspecialchars($_language->get('entry_display_group'), ENT_QUOTES); ?>">';
	<?php foreach ($customer_groups as $value) { ?>
	html += '           <option value="customer_group_id|<?php echo $value['customer_group_id']; ?>"><?php echo htmlspecialchars($value['name'], ENT_QUOTES); ?></option>';
	<?php } ?>
	html += '      </optgroup>';
	html += '      <optgroup label="<?php echo htmlspecialchars($_language->get('entry_display_orderstatus'), ENT_QUOTES); ?>">';
	<?php foreach ($order_statuses as $value) { ?>
	html += '           <option value="order_status_id|<?php echo $value['order_status_id']; ?>"><?php echo htmlspecialchars($value['name'], ENT_QUOTES); ?></option>';
	<?php } ?>
	html += '      </optgroup>';
	html += '      <optgroup label="<?php echo htmlspecialchars($_language->get('entry_display_payment'), ENT_QUOTES); ?>">';
	<?php foreach ($payment_methods as $value) { ?>
	html += '           <option value="payment_code|<?php echo $value['code']; ?>"><?php echo htmlspecialchars($value['name'], ENT_QUOTES); ?></option>';
	<?php } ?>
	html += '      </optgroup>';
	html += '      <optgroup label="<?php echo htmlspecialchars($_language->get('entry_display_shipping'), ENT_QUOTES); ?>">';
	<?php foreach ($shipping_methods as $value) { ?>
	html += '           <option value="shipping_code|<?php echo $value['code']; ?>"><?php echo htmlspecialchars($value['name'], ENT_QUOTES); ?></option>';
	<?php } ?>
	html += '      </optgroup>';
	html += '      <optgroup label="<?php echo htmlspecialchars($_language->get('entry_display_payment_zone'), ENT_QUOTES); ?>">';
	<?php foreach ($geo_zones as $value) { ?>
	html += '           <option value="shipping_zone|<?php echo $value['geo_zone_id']; ?>"><?php echo htmlspecialchars($value['name'], ENT_QUOTES); ?></option>';
	<?php } ?>
	html += '      </optgroup>';
	html += '      <optgroup label="<?php echo htmlspecialchars($_language->get('entry_display_payment'), ENT_QUOTES); ?>">';
	<?php foreach ($geo_zones as $value) { ?>
	html += '           <option value="payment_zone|<?php echo $value['geo_zone_id']; ?>"><?php echo htmlspecialchars($value['name'], ENT_QUOTES); ?></option>';
	<?php } ?>
	html += '      </optgroup>';
	html += '      </select></td>';
	html += '    </tr>';
	html += '    <tr>';
	html += '      <td><?php echo htmlspecialchars($_language->get('entry_sort_order'), ENT_QUOTES); ?></td>';
	html += '      <td><input class="form-control" type="text" name="pdf_invoice_blocks[' + module_row + '][sort_order]" value="" size="3" /></td>';
	html += '    </tr>';
	html += '  </table>'; 
	html += '</div>';
	
	html += '</div>'; //tab-content
	
	$('#tab-4 > .tab-content').append(html);
	
  $('#module-add').before('<li><a href="#tab-module-' + module_row + '" id="module-' + module_row + '" data-toggle="pill"><i class="fa fa-minus-circle" onclick="$(\'#tab-module-' + module_row + '\').remove(); $(this).closest(\'li\').remove(); $(\'#custom_blocks_tabs li:first a\').trigger(\'click\'); return false;"></i><?php echo htmlspecialchars($_language->get('tab_block'), ENT_QUOTES); ?> ' + module_row + '</a></li>');
	//$('.vtabs a').tabs();
	$('#module-' + module_row).trigger('click');
  
	<?php foreach ($languages as $language) { ?>
		<?php if (defined('_JEXEC')) { ?>
		<?php } else if ($OC_V2) { ?>
			$('#description-' + module_row + '-<?php echo $language['language_id']; ?>').summernote({
				height: 300
			});
		<?php } else { ?>
			CKEDITOR.replace('description-' + module_row + '-<?php echo $language['language_id']; ?>', {
				filebrowserBrowseUrl: 'index.php?route=common/filemanager&<?php echo $token; ?>',
				filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&<?php echo $token; ?>',
				filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&<?php echo $token; ?>',
				filebrowserUploadUrl: 'index.php?route=common/filemanager&<?php echo $token; ?>',
				filebrowserImageUploadUrl: 'index.php?route=common/filemanager&<?php echo $token; ?>',
				filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&<?php echo $token; ?>'
			});  
		<?php } ?>
	<?php } ?>
	
	//$('#language-' + module_row + ' a').tabs();
	
	module_row++;
}
//--></script> 
<script type="text/javascript"><!--
$('.sortable').sortable({
	items: "div:not(.unsortable_div)"
	/*
	update: function() {
		var order = $('#qosu_statuses').sortable('toArray');
		//alert(typeOf(order));
		//order = order.split(',');
		$.each(order, function(i, v) {
			$('input[name="qosu_order_statuses['+v.replace('status-','')+'][sort_order]"]').val(i+1);
		});
		//$.post('ajax.php',order);
	}*/
});

$('body').on('click', '.info-btn', function() {
  $('#modal-info').html('<div style="text-align:center"><img src="view/pdf_invoice/img/loader.gif" alt=""/></div>');
  $('#modal-info').load('index.php?route=module/pdf_invoice/modal_info&<?php echo $token; ?>', {'info': $(this).attr('data-info')});
});
//--></script> 
<?php if(!$OC_V2) { ?>
<script type="text/javascript"><!--
function image_upload(field, thumb) {
	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: '<?php echo htmlspecialchars($_language->get('text_image_manager'), ENT_QUOTES); ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).val()),
					dataType: 'text',
					success: function(data) {
						$('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 800,
		height: 400,
		resizable: false,
		modal: false
	});
};
//--></script> 
<?php } ?>
<?php if(defined('_JEXEC') && !$OC_V2) { ?>
<style>
#header *{box-sizing: content-box!important;}
.panel-body{padding: 10px;}
</style>
<?php } ?>
<?php echo $footer; ?>