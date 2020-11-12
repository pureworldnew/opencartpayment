<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"> <a data-toggle="tooltip" title="<?php echo $button_save; ?>" onclick="$('#form-settings').submit();" class="btn btn-success"><i class="fa fa-save"></i> <?php echo $button_save; ?></a> <a data-toggle="tooltip" title="<?php echo $button_cancel; ?>" onclick="location = '<?php echo $cancel; ?>';" class="btn btn-danger"><i class="fa fa-reply"></i> <?php echo $button_exit; ?></a> </div>
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
  <div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $error_warning; ?></div>
  <?php } ?>
	<?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="mensajes"></div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form_settings; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-settings" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></span></label>
            <div class="col-sm-10">
              <select name="supercategorymenuadvanced_status" id="input-status" class="form-control">
                <?php if ($supercategorymenuadvanced_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div> 
          </div> 
          <ul class="nav nav-tabs">
            <?php $f=0; foreach ($stores as $store){ ?>
            <li <?php if(!$f) echo 'class="active"'; $f = 1; ?>> <a data-toggle="tab" href="#tab-store_id_<?php echo $store['store_id']; ?>" class="tab-store_id_<?php echo $store['store_id']; ?>" ><i class="fa fa-shopping-cart"></i>&nbsp;<?php echo $store['name']; ?></a></li>
            <?php  }  ?> 
            <li><a href="#tab-register" id="licensing" data-toggle="tab" ><i class="fa fa-keyboard-o"></i>&nbsp;<?php echo $tab_register; ?></a> </li>
            <li><a href="#tab-contact" id="contact" data-toggle="tab"><i class="fa fa-external-link"></i>&nbsp;<?php echo $tab_contact; ?></a></li>
            <li><a href="#tab-seo" id="seo" data-toggle="tab"><i class="fa fa-globe"></i>&nbsp;SEO</a></li>
          </ul>
          <div class="tab-content"> 
            <div id="tab-seo" class="tab-pane fade">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-resp-name"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_seo_keyword_explanation; ?>"><?php echo $entry_seo_keyword_category; ?></span></label>
                <div class="col-sm-10">
                  <input class="form-control col-md-4" type="text" id="seo_cat" name="supercategorymenuadvanced_seo_cat" value="<?php echo $settings_seo_cat; ?>" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-resp-name"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_seo_keyword_explanation2; ?>"><?php echo $entry_seo_keyword_manufacturer; ?></span></label>
                <div class="col-sm-10">
                  <input class="form-control col-md-4" type="text" name="supercategorymenuadvanced_seo_man" value="<?php echo $settings_seo_man; ?>" />
                </div>
              </div>
            </div>
            <div id="tab-register" class="tab-pane fade">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-enable"><?php echo $register_status; ?></span></label>
                <div class="col-sm-10">
				<input type="hidden" name="supercategorymenuadvanced_code" value="<?php echo $settings_code; ?>" />
				<div class="alert alert-info fade in"><?php echo $supercategorymenuadvanced_accountDetails; ?></div>
				<div id="addcode"></div>
                </div>
              </div>
            </div>
            <script type="text/javascript">        
        	<?php if (!$rg){ ?>
			$('#licensing').css({'background-color': '#FFD1D1','border': '1px solid #F8ACAC','color': 'red','text-decoration': 'blink'});
           	<?php }else{ ?>
			$('#licensing').css({'color': 'green'});
			<?php } ?>
  			</script>
            <div id="tab-contact" class="tab-pane fade">
              <table width="100%" border="0" cellpadding="2">
                <tr>
                  <td width="4%" valign="top"></td>
                  <td width="96%" rowspan="2"><table width="100%" border="0" cellpadding="2">
                      <tr>
                        <td rowspan="4"><span style="font-size:150pt;"><i class="fa fa-connectdevelop fa-6x"></i></span></td>
                        <td>Contact: (Only registered users will be supported)</td>
                        <td><a href="http://support.ocmodules.com" target="_blank">support.ocmodules.com</a></td>
                      </tr>
                      <tr>
                        <td>Current Version:</td>
                        <td><?php echo $current_version; ?>
                          <div id="newversion"></div></td>
                      </tr>
                      <tr>
                        <td></td>
                        <td><div id="what_is_new"></div></td>
                      </tr>
                      <tr>
                        <td height="40">OpenCart Url</td>
                        <td><a href="http://www.opencart.com/index.php?route=extension/extension/info&amp;extension_id=<?php echo $version['extension_opencart_url']; ?>" target="_blank"> http://www.opencart.com/index.php?route=extension/extension/info&amp;extension_id=<?php echo $version['extension_opencart_url']; ?></a></td>
                      </tr>
                    </table>
                    <br />
                    <br />
                    <?php if (isset($version['modules'])){ ?>
                    <strong>Other modules:</strong><br />
                    <table  border="0"  cellpadding="2">
                      <?php foreach ($version['modules'] as $modules) { ?>
                      <tr>
                        <td  height="66"><strong><br />
                          <?php echo $modules['name']; ?> - v<?php echo $modules['version']; ?></strong><br />
                          <?php echo str_replace("@@@","<br>",$modules['resume']); ?><br />
                          OC: <a href="http://www.opencart.com/index.php?route=extension/extension/info&amp;extension_id=<?php echo $modules['extension_opencart_url']; ?>" target="_blank">http://www.opencart.com/index.php?route=extension/extension/info&extension_id=<?php echo $modules['extension_opencart_url']; ?> </a>
                          <?php if ($modules['video']) { ?>
                          Video: <a href="<?php echo $modules['video']; ?>" target="_blank"><br />
                          <?php } ?></td>
                      </tr>
                      <?php } ?>
                    </table>
                    <?php } ?></td>
                  <?php  if ($version){ 
            if ($version['current_version']!=$current_version){ ?>
                  <script type="text/javascript">
               
			   $('#contact').css({'background-color': '#FFD1D1','border': '1px solid #F8ACAC','color': 'red'});
               $('#newversion').append ('<span style=\"color:red\"><strong>New version for this extension available <?php echo $version["current_version"]; ?></strong></span>');
               $('#what_is_new').append('<?php echo html_entity_decode(str_replace("@@@","<br>",$version['whats_new']), ENT_QUOTES, 'UTF-8'); ?> ');
              </script>
                  <?php } else{ ?>
                  <script type="text/javascript">
           
			$('#contact').css({'color': 'green'});
			$('#newversion').append ('<span style=\"color:green\"><strong>Correct, you have the last version.</strong></span>');
	        </script>
                  <?php  }} ?>
                </tr>
                <tr>
                  <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td width="4%" valign="top">&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
              </table>
            </div>
            <?php $f=0; foreach ($stores as $store){ ?>
            <div id="tab-store_id_<?php echo $store['store_id']; ?>" class="tab-pane  <?php if(!$f) {echo ' active'; $f=1;} ?>">
			   <ul class="nav nav-pills nav-stacked col-md-2" id="principal2_<?php echo $store['store_id']; ?>" role="tablist" style="border-right:1px dotted #1e91cf;">
                <li class="active"><a href="#tab-settings_<?php echo $store['store_id']; ?>" data-toggle="tab"><i class="fa fa-cogs"></i>&nbsp;<?php echo $tab_settings; ?></a></li>
                <li><a href="#tab-ajax_<?php echo $store['store_id']; ?>" data-toggle="tab"><i class="fa fa-cloud"></i>&nbsp;<?php echo $tab_ajax; ?></a> </li>
                <li><a href="#tab-responsive_<?php echo $store['store_id']; ?>" data-toggle="tab"><i class="fa fa-code"></i>&nbsp;<?php echo $tab_responsive; ?></a> </li>
                <li><a href="#tab-categories_<?php echo $store['store_id']; ?>" data-toggle="tab"><i class="fa fa-cube"></i>&nbsp;<?php echo $tab_categories; ?></a> </li>
                <li><a href="#tab-manufacturer_<?php echo $store['store_id']; ?>" data-toggle="tab" ><i class="fa fa-book"></i>&nbsp;<?php echo $tab_manufacturer; ?></a></li>
                <li><a href="#tab-options_<?php echo $store['store_id']; ?>" data-toggle="tab"><i class="fa fa-thumb-tack"></i>&nbsp;<?php echo $tab_options; ?></a> </li>
                <li><a href="#tab-pricerange_<?php echo $store['store_id']; ?>" data-toggle="tab"><i class="fa fa-credit-card"></i>&nbsp;<?php echo $tab_pricerange; ?></a> </li>
                <li><a href="#tab-reviews_<?php echo $store['store_id']; ?>" data-toggle="tab"><i class="fa fa-pencil-square-o"></i>&nbsp;<?php echo $tab_reviews; ?></a> </li>
                <li><a href="#tab-ordening_<?php echo $store['store_id']; ?>" data-toggle="tab"><i class="fa fa-sort-amount-desc"></i>&nbsp;<?php echo $tab_filters_order; ?></a> </li>
                <li><a href="#tab-stock_<?php echo $store['store_id']; ?>" data-toggle="tab"><i class="fa fa-puzzle-piece"></i>&nbsp;<?php echo $tab_stock; ?></a> </li>
                <li><a href="#tab-styles_<?php echo $store['store_id']; ?>" id="stylestab_<?php echo $store['store_id']; ?>" class="info" data-toggle="tab"><i class="fa fa-adjust"></i>&nbsp;<?php echo $tab_styles; ?></a> </li>
                <li><a href="#tab-admincache_<?php echo $store['store_id']; ?>" data-toggle="tab"><i class="fa fa-archive"></i>&nbsp;<?php echo $tab_admincache; ?></a></li>
              </ul>
              <div class="tab-content col-md-10">
                <div class="tab-pane fade" id="tab-responsive_<?php echo $store['store_id']; ?>">
				<h3 style="color:#1e91cf;"><i class="fa fa-code"></i>&nbsp;<?php echo $tab_responsive; ?> <?php echo $tab_settings; ?> <?php echo $store['name']; ?></h3>
				<br>
                  <?php $value = $settings['str'.$store['store_id']]['responsive'] ?>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-resp-enable"><?php echo $responsibe_enable; ?></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][responsive][enable]" id="input-resp-enable" class="form-control">
                        <?php if ($value['enable']) { ?>
                        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                        <option value="0"><?php echo $text_no; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_yes; ?></option>
                        <option value="0" selected="selected"><?php echo $text_no; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-resp-name"><?php echo $resp_btn_name; ?></label>
                    <div class="col-sm-10 text-left">
                      <input type="text" class="form-control" name="SETTINGS[<?php echo $store['store_id']; ?>][responsive][name]" value="<?php echo $value['name']; ?>" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-resp-name"><span title="" data-toggle="tooltip" data-original-title="<?php echo $resp_btn_position_explanation; ?>"><?php echo $resp_btn_position; ?></span></label>
                    <div class="col-sm-10 text-left">
                      <input type="text" class="form-control" name="SETTINGS[<?php echo $store['store_id']; ?>][responsive][appendto]" value="<?php echo $value['appendto']; ?>" />
                    </div>
                  </div>
                </div>
    			  <div class="tab-pane fade" id="tab-options_<?php echo $store['store_id']; ?>">
				<h3 style="color:#1e91cf;"><i class="fa fa-thumb-tack"></i>&nbsp;<?php echo $tab_options; ?> <?php echo $tab_settings; ?> <?php echo $store['name']; ?></h3>
				<br>
                  <?php $value = $settings['str'.$store['store_id']]['general_data'] ?>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-nodata"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_image_option_menu_explanation; ?>"><?php echo $entry_image_option_menu; ?></span></label>
                    <div class="col-sm-4 text-left">
					
					
					<div class="input-group">
					  <span class="input-group-addon">Width:&nbsp;</span>
					  <input type="text" value="<?php echo $value['image_option_width']; ?>" name="SETTINGS[<?php echo $store['store_id']; ?>][general_data][image_option_width]" class="form-control">
					  <span class="input-group-addon">px</span>
					</div>
					
					<br>
					<div class="input-group">
					  <span class="input-group-addon">Height:</span>
					  <input type="text" value="<?php echo $value['image_option_height']; ?>" name="SETTINGS[<?php echo $store['store_id']; ?>][general_data][image_option_height]" class="form-control">
					  <span class="input-group-addon">px</span>
					</div>
					 </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-nodata"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_option_tip_explanation; ?>"><?php echo $entry_option_tip; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][general_data][option_tip]" id="input-initval" class="form-control">
                        <?php if ($value['option_tip']) { ?>
                        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                        <option value="0"><?php echo $text_no; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_yes; ?></option>
                        <option value="0" selected="selected"><?php echo $text_no; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-nodata"><span title="" data-toggle="tooltip" data-original-title="<?php echo $option_stock_explanation; ?>"><?php echo $option_stock; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][general_data][option_stock]" id="input-initval" class="form-control">
                        <?php if ($value['option_stock']) { ?>
                        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                        <option value="0"><?php echo $text_no; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_yes; ?></option>
                        <option value="0" selected="selected"><?php echo $text_no; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-nodata"><span title="" data-toggle="tooltip" data-original-title="<?php echo $option_images_explanation; ?>"><?php echo $option_images; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][general_data][option_images]" id="input-initval" class="form-control">
                        <?php if ($value['option_images']) { ?>
                        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                        <option value="0"><?php echo $text_no; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_yes; ?></option>
                        <option value="0" selected="selected"><?php echo $text_no; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>
			  <div class="tab-pane active" id="tab-settings_<?php echo $store['store_id']; ?>">
                  <?php $value = $settings['str'.$store['store_id']]['general_data'] ?>
				  <h3 style="color:#1e91cf;"><i class="fa fa-cogs"></i>&nbsp;<?php echo $tab_settings; ?> <?php echo $store['name']; ?></h3>
					<br>
				  <div class="form-group">
                    <label class="col-sm-2 control-label" for="num_registros_<?php echo $store['store_id']; ?>"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_truncate_explanation; ?>"><?php echo $entry_truncate; ?></span></label>
                    <div class="col-sm-2 control-label" id="num_registros_<?php echo $store['store_id']; ?>"> <?php echo $value['num_registros']; ?> <?php echo $text_records; ?> </div>
                    <div class="col-sm-2">
                      <div class="buttons"> <a id="truncate_<?php echo $store['store_id']; ?>" class="button btn btn-warning"><span><?php echo $button_truncate; ?></span></a></div>
                    </div>
                    <div class="col-sm-2"></div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-menu_mode"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_mode_explanation; ?>"><?php echo $entry_mode; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][general_data][menu_mode]" id="input-menu_mode" class="form-control">
                        <?php if ($value['menu_mode'] == "Production") { ?>
                        <option value="Production" selected="selected"><?php echo $text_production; ?></option>
                        <?php } else { ?>
                        <option value="Production"><?php echo $text_production; ?></option>
                        <?php } ?>
                        <?php if ($value['menu_mode']== "Developing") { ?>
                        <option value="Developing" selected="selected"><?php echo $text_developing; ?></option>
                        <?php } else { ?>
                        <option value="Developing"><?php echo $text_developing; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-nodata"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_asearch_explanation; ?>"><?php echo $entry_asearch_filters; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][general_data][asearch_filters]" id="input-initval" class="form-control">
                        <?php if ($value['asearch_filters']) { ?>
                        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                        <option value="0"><?php echo $text_no; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_yes; ?></option>
                        <option value="0" selected="selected"><?php echo $text_no; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-nodata"><span title="" data-toggle="tooltip" data-original-title="<?php echo $enable_scroll_explanation; ?>"><?php echo $enable_scroll; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][general_data][enable_scroll]" id="input-initval" class="form-control">
                        <?php if ($value['enable_scroll']) { ?>
                        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                        <option value="0"><?php echo $text_no; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_yes; ?></option>
                        <option value="0" selected="selected"><?php echo $text_no; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-nodata"><span title="" data-toggle="tooltip" data-original-title="<?php echo $enable_scroll_explanation_layout; ?>"><?php echo $enable_scroll_layout; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][general_data][enable_scroll_layout]" id="input-initval" class="form-control">
                        <?php if ($value['enable_scroll_layout']=="column_left") { ?>
                        <option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
                        <?php } else { ?>
                        <option value="column_left"><?php echo $text_column_left; ?></option>
                        <?php } ?>
                        <?php if ($value['enable_scroll_layout']=="column_right") { ?>
                        <option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
                        <?php } else { ?>
                        <option value="column_right"><?php echo $text_column_right; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-nodata"><span title="" data-toggle="tooltip" data-original-title="<?php echo $totales_explanation; ?>"><?php echo $totales; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][general_data][totales]" id="input-initval" class="form-control">
                        <?php if ($value['totales']) { ?>
                        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                        <option value="0"><?php echo $text_no; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_yes; ?></option>
                        <option value="0" selected="selected"><?php echo $text_no; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-nodata"><span title="" data-toggle="tooltip" data-original-title="<?php echo $link_to_product_explanation; ?>"><?php echo $link_to_product; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][general_data][link_to_product]" id="input-initval" class="form-control">
                        <?php if ($value['link_to_product']) { ?>
                        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                        <option value="0"><?php echo $text_no; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_yes; ?></option>
                        <option value="0" selected="selected"><?php echo $text_no; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-nodata"><span title="" data-toggle="tooltip" data-original-title="<?php echo $enable_no_data_explanation; ?>"><?php echo $enable_no_data; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][general_data][nodata]" id="input-nodata" class="form-control">
                        <?php if ($value['nodata']) { ?>
                        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                        <option value="0"><?php echo $text_no; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_yes; ?></option>
                        <option value="0" selected="selected"><?php echo $text_no; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="mode_catalog"><span title="" data-toggle="tooltip" data-original-title="<?php echo $enable_mode_catalog_expalantion; ?>"><?php echo $enable_mode_catalog; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][general_data][modecatalog]" id="mode_catalog" class="form-control">
                        <?php if ($value['modecatalog']) { ?>
                        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                        <option value="0"><?php echo $text_no; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_yes; ?></option>
                        <option value="0" selected="selected"><?php echo $text_no; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-see_more_trigger"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_see_more_trigger_explanation; ?>"><?php echo $entry_trigger_see_more; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][general_data][see_more_trigger]" id="input-see_more_trigger" class="form-control">
                        <?php if ($value['see_more_trigger']) { ?>
                        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                        <option value="0"><?php echo $text_no; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_yes; ?></option>
                        <option value="0" selected="selected"><?php echo $text_no; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-menu_filters"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_menu_filters_explanation; ?>"><?php echo $entry_menu_filters; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][general_data][menu_filters]" id="input-menu_filters" class="form-control">
                        <?php if ($value['menu_filters']) { ?>
                        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                        <option value="0"><?php echo $text_no; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_yes; ?></option>
                        <option value="0" selected="selected"><?php echo $text_no; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-countp"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_count_explanation; ?>"><?php echo $entry_count; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][general_data][countp]" id="input-countp" class="form-control">
                        <?php if ($value['countp']) { ?>
                        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                        <option value="0"><?php echo $text_no; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_yes; ?></option>
                        <option value="0" selected="selected"><?php echo $text_no; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-nofollow"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_count_explanation; ?>"><?php echo $entry_nofollow; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][general_data][nofollow]" id="input-nofollow" class="form-control">
                        <?php if ($value['nofollow']) { ?>
                        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                        <option value="0"><?php echo $text_no; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_yes; ?></option>
                        <option value="0" selected="selected"><?php echo $text_no; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-track_google"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_track_google_explanation; ?>"><?php echo $entry_track_google; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][general_data][track_google]" id="input-track_google" class="form-control">
                        <?php if ($value['track_google']) { ?>
                        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                        <option value="0"><?php echo $text_no; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_yes; ?></option>
                        <option value="0" selected="selected"><?php echo $text_no; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-ocscroll"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_ocscroll_explanation; ?>"><?php echo $entry_ocscroll; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][general_data][ocscroll]" id="input-ocscroll" class="form-control">
                        <?php if ($value['ocscroll']) { ?>
                        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                        <option value="0"><?php echo $text_no; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_yes; ?></option>
                        <option value="0" selected="selected"><?php echo $text_no; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>
          
		  <div class="tab-pane fade" id="tab-ajax_<?php echo $store['store_id']; ?>">
                 <h3 style="color:#1e91cf;"><i class="fa fa-cloud"></i>&nbsp;<?php echo $tab_ajax; ?> <?php echo $tab_settings; ?> <?php echo $store['name']; ?></h3><br>

				 <?php $value = $settings['str'.$store['store_id']]['ajax'] ?>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-enable"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_ajax_explanation; ?>"><?php echo $entry_ajax; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][ajax][enable]" id="input-enable" class="form-control">
                        <?php if ($value['enable']) { ?>
                        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                        <option value="0"><?php echo $text_no; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_yes; ?></option>
                        <option value="0" selected="selected"><?php echo $text_no; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-scrollto"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_ajax_explanation_scrollto; ?>"><?php echo $entry_ajax_scrollto; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][ajax][scrollto]" id="input-scrollto" class="form-control">
                        <?php if ($value['scrollto']) { ?>
                        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                        <option value="0"><?php echo $text_no; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_yes; ?></option>
                        <option value="0" selected="selected"><?php echo $text_no; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-loader"><?php echo $entry_ajax_loader; ?></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][ajax][loader]" id="input-loader" class="form-control">
                        <?php if ($value['loader']) { ?>
                        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                        <option value="0"><?php echo $text_no; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_yes; ?></option>
                        <option value="0" selected="selected"><?php echo $text_no; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-ajax_image"><?php echo $entry_ajax_image; ?></label>
                    <div class="col-sm-4">
                      <style type="text/css">
						.checked{ border: thin dotted #666;	}
						.picker{padding: 15px;}
						img.picker:hover {border: 1px solid #494949;} 
						ol.sorting_filters {cursor: pointer;}
						ol.vertical {margin: 0 0 9px;}
						ol.vertical li {background: none repeat scroll 0 0 #eeeeee;border: 1px solid #cccccc;color: #0088cc;display: block;margin: 5px;padding: 5px;height:25 px;width:250px;}
						</style>
                      <?php                  
                  $wjx=1;
                   foreach ($ajax_loaders as $ajax_loader) { ?>
                      <?php if ($ajax_loader == $value['loader_image']) { ?>
                      <label for="radio_<?php echo $wjx; ?>"> <img class="picker<?php echo $store['store_id']; ?> checked" src="<?php echo HTTP_CATALOG.'image/advancedmenu/loaders/'.$ajax_loader; ?>" width="50"/>
                        <input  id="radio_<?php echo $wjx; ?>" value="<?php echo $ajax_loader; ?>" type="radio" checked="checked" name="SETTINGS[<?php echo $store['store_id']; ?>][ajax][loader_image]" style="display:none;"/>
                        </span></label>
                      <?php } else { ?>
                      <label for="radio_<?php echo $wjx; ?>"> <img class="picker<?php echo $store['store_id']; ?>" src="<?php echo HTTP_CATALOG.'image/advancedmenu/loaders/'.$ajax_loader; ?>" width="50" />
                        <input id="radio_<?php echo $wjx; ?>" value="<?php echo $ajax_loader; ?>" type="radio"  name="SETTINGS[<?php echo $store['store_id']; ?>][ajax][loader_image]" style="display:none;" />
                        </span></label>
                      <?php } ?>
                      <?php $wjx++; 
                  } ?>
                      <script type="text/javascript">
			  	var themeChooser = $("img.picker<?php echo $store['store_id']; ?>");
				themeChooser.click(function() {
					$('.checked').removeClass('checked');
					if(!themeChooser.hasClass('checked')) {
						$(this).addClass('checked');        
					} else {
						$(this).removeClass('checked');
					}
				});
			    </script> 
                    </div>
                    <div class="col-sm-4"><span class="small text-info"><?php echo $loader_explanation; ?></span></div>
                  </div>
                </div>
				 <div class="tab-pane fade" id="tab-ordening_<?php echo $store['store_id']; ?>"> 
				<h3 style="color:#1e91cf;"><i class="fa fa-sort-amount-desc"></i>&nbsp;<?php echo $tab_filters_order; ?> <?php echo $tab_settings; ?> <?php echo $store['name']; ?></h3><br>

                  <script type="text/javascript">
                $(function() {
                $( "#sortable_<?php echo $store['store_id']; ?>" ).sortable({
                      group: "sortable_<?php echo $store['store_id']; ?>",
                      pullPlaceholder: false,	
                      onDrop: function  (item, targetContainer, _super,event) {
                        var clonedItem = $('<li/>').css({height: 0})
                        item.before(clonedItem)
                        clonedItem.animate({'height': item.height()})
                        item.animate(clonedItem.position(), function  () {
                          clonedItem.detach()
                        _super(item)
                        })
                         ids = $($("#sortable_<?php echo $store['store_id']; ?> li>span").map(function() {  return this.id; })).toArray() ;
                            for (var key in ids) {
                                $("input[name='SETTINGS[<?php echo $store['store_id']; ?>]["+ids[key]+"][super_order]']").val(parseInt(key)+1);
                            }
                  },
                  // set item relative to cursor position
                  onDragStart: function ($item, container, _super) {
                    var offset = $item.offset(),
                    pointer = container.rootGroup.pointer
                    adjustment = {
                      left: pointer.left - offset.left,
                      top: pointer.top - offset.top
                    }
                    _super($item, container)
                  },
                  onDrag: function ($item, position) {
                    $item.css({
                      left: position.left - adjustment.left,
                      top: position.top - adjustment.top
                    })
                  }
                });	});
                </script>
                  <?php 
             $val="SETTINGS_".$store['store_id'];
			 $ordening_values=array();
			 
            //mount array with all values.
            $ordening_values[$settings['str'.$store['store_id']]['stock']['super_order']]=array('name'=>'Stock','name2'=>'stock','order'=>$settings['str'.$store['store_id']]['stock']['super_order']);
            $ordening_values[$settings['str'.$store['store_id']]['filter']['super_order']]=array('name'=>'Option/attributes/ProductInfo','name2'=>'filter','order'=>$settings['str'.$store['store_id']]['filter']['super_order']);
            $ordening_values[$settings['str'.$store['store_id']]['categories']['super_order']]=array('name'=>'Category','name2'=>'category','order'=>$settings['str'.$store['store_id']]['categories']['super_order']);
            $ordening_values[$settings['str'.$store['store_id']]['manufacturer']['super_order']]=array('name'=>'Manufacturer','name2'=>'manufacturer','order'=>$settings['str'.$store['store_id']]['manufacturer']['super_order']);
            $ordening_values[$settings['str'.$store['store_id']]['pricerange']['super_order']]=array('name'=>'Price Range','name2'=>'pricerange','order'=>$settings['str'.$store['store_id']]['pricerange']['super_order']);
            $ordening_values[$settings['str'.$store['store_id']]['reviews']['super_order']]=array('name'=>'Reviews','name2'=>'reviews','order'=>$settings['str'.$store['store_id']]['reviews']['super_order']);
            ksort($ordening_values);
            ?>
                  <input name="SETTINGS[<?php echo $store['store_id']; ?>][categories][super_order]" type="hidden" value="<?php echo $settings['str'.$store['store_id']]['categories']['super_order']; ?>" />
                  <input name="SETTINGS[<?php echo $store['store_id']; ?>][manufacturer][super_order]" type="hidden" value="<?php echo $settings['str'.$store['store_id']]['manufacturer']['super_order']; ?>" />
                  <input name="SETTINGS[<?php echo $store['store_id']; ?>][stock][super_order]" type="hidden" value="<?php echo $settings['str'.$store['store_id']]['stock']['super_order']; ?>" />
                  <input name="SETTINGS[<?php echo $store['store_id']; ?>][pricerange][super_order]" type="hidden" value="<?php echo $settings['str'.$store['store_id']]['pricerange']['super_order']; ?>" />
                  <input name="SETTINGS[<?php echo $store['store_id']; ?>][reviews][super_order]" type="hidden" value="<?php echo $settings['str'.$store['store_id']]['reviews']['super_order']; ?>" />
                  <input name="SETTINGS[<?php echo $store['store_id']; ?>][filter][super_order]" type="hidden" value="<?php echo $settings['str'.$store['store_id']]['filter']['super_order']; ?>" />
                  <ol id="sortable_<?php echo $store['store_id']; ?>" class="sorting_filters vertical">
                    <?php foreach($ordening_values as $ordening){?>
                    <li><span id="<?php echo $ordening['name2']; ?>"></span><?php echo $ordening['name']; ?></li>
                    <?php }?>
                  </ol>
                </div>
                <div class="tab-pane fade" id="tab-stock_<?php echo $store['store_id']; ?>">  
				<h3 style="color:#1e91cf;"><i class="fa fa-puzzle-piece"></i>&nbsp;<?php echo $tab_stock; ?> <?php echo $tab_settings; ?> <?php echo $store['name']; ?></h3><br>

                  <?php $value = $settings['str'.$store['store_id']]['stock']; ?>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-scrollto"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_stock_explanation; ?>"><?php echo $entry_stock; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][stock][enable]" id="input-enable" class="form-control">
                        <?php if ($value['enable']) { ?>
                        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                        <option value="0"><?php echo $text_no; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_yes; ?></option>
                        <option value="0" selected="selected"><?php echo $text_no; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-scrollto"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_special_explanation; ?>"><?php echo $entry_special; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][stock][special]" id="input-special" class="form-control">
                        <?php if ($value['special']) { ?>
                        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                        <option value="0"><?php echo $text_no; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_yes; ?></option>
                        <option value="0" selected="selected"><?php echo $text_no; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-arrivals"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_arrivals_explanation; ?>"><?php echo $entry_arrivals; ?></span></label>
                    <div class="col-sm-4">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][stock][arrivals]" id="input-arrivals" class="form-control">
                        <?php if ($value['arrivals']) { ?>
                        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                        <option value="0"><?php echo $text_no; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_yes; ?></option>
                        <option value="0" selected="selected"><?php echo $text_no; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <label class="col-sm-2 control-label" for="input-number_day"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_number_day; ?>"><?php echo $entry_arrivals_days; ?></span></label>
                    <div class="col-sm-2">
                      <input type="text" id="input-number_day" class="form-control" name="SETTINGS[<?php echo $store['store_id']; ?>][stock][number_day]" value="<?php echo $settings['str'.$store['store_id']]['stock']['number_day']; ?>" size="5" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-clearance"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_special_explanation; ?>"><?php echo $entry_clearance; ?></span></label>
                    <div class="col-sm-4">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][stock][clearance]" id="input-clearance" class="form-control">
                        <?php if ($value['clearance']) { ?>
                        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                        <option value="0"><?php echo $text_no; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_yes; ?></option>
                        <option value="0" selected="selected"><?php echo $text_no; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <label class="col-sm-2 control-label" for="input-clearance_value"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_clearance_explanation; ?>"><?php echo $entry_select_clearance; ?></span></label>
                    <div class="col-sm-2">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][stock][clearance_value]" id="input-clearance_value" class="form-control">
                        <?php foreach ($stock_statuses as $stock_status) { ?>
                        <?php if ($stock_status['stock_status_id'] == $settings['str'.$store['store_id']]['stock']['clearance_value']) { ?>
                        <option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
                        <?php } ?>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-view"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_view_explanation; ?>"><?php echo $entry_view; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][stock][view]" id="input-view" class="form-control">
                        <?php if ($value['view'] == "list") { ?>
                        <option value="list" selected="selected"><?php echo $entry_list; ?></option>
                        <?php } else { ?>
                        <option value="list"><?php echo $entry_list; ?></option>
                        <?php } ?>
                        <?php if ($value['view'] == "sele") { ?>
                        <option value="sele" selected="selected"><?php echo $entry_select; ?></option>
                        <?php } else { ?>
                        <option value="sele"><?php echo $entry_select; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-initval"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_open_explanation; ?>"><?php echo $entry_open; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][stock][initval]" id="input-initval" class="form-control">
                        <?php if ($value['initval'] == "opened") { ?>
                        <option value="opened" selected="selected"><?php echo $text_open; ?></option>
                        <?php } else { ?>
                        <option value="opened"><?php echo $text_open; ?></option>
                        <?php } ?>
                        <?php if ($value['initval']== "closed") { ?>
                        <option value="closed" selected="selected"><?php echo $text_close; ?></option>
                        <?php } else { ?>
                        <option value="closed"><?php echo $text_close; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="tab-categories_<?php echo $store['store_id']; ?>">				
				<h3 style="color:#1e91cf;"><i class="fa fa-cube"></i>&nbsp;<?php echo $tab_categories; ?> <?php echo $tab_settings; ?> <?php echo $store['name']; ?></h3><br>

                  <?php $value = $settings['str'.$store['store_id']]['categories'] ?>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-enable-cat"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_category_explanation; ?>"><?php echo $entry_category; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][categories][enable]" id="input-enable-cat" class="form-control">
                        <?php if ($value['enable']) { ?>
                        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                        <option value="0"><?php echo $text_no; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_yes; ?></option>
                        <option value="0" selected="selected"><?php echo $text_no; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-asearch"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_asearch_explanation; ?>"><?php echo $entry_category_asearch; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][categories][asearch]" id="input-asearch" class="form-control">
                        <?php if ($value['asearch']) { ?>
                        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                        <option value="0"><?php echo $text_no; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_yes; ?></option>
                        <option value="0" selected="selected"><?php echo $text_no; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-reset"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_reset_category_explanation; ?>"><?php echo $entry_reset_category; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][categories][reset]" id="input-reset" class="form-control">
                        <?php if ($value['reset']) { ?>
                        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                        <option value="0"><?php echo $text_no; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_yes; ?></option>
                        <option value="0" selected="selected"><?php echo $text_no; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-nodata"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_list_number_explanation; ?>"><?php echo $entry_list_number; ?></span></label>
                    <div class="col-sm-10">
                      <input type="text" name="SETTINGS[<?php echo $store['store_id']; ?>][categories][list_number]" value="<?php echo $value['list_number']; ?>" placeholder="<?php echo $entry_list_number; ?>" id="input-list_number" class="form-control" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-order"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_order_explanation; ?>"><?php echo $entry_order; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][categories][order]" id="input-order" class="form-control">
                        <?php if ($value['order'] == "OCASC") { ?>
                        <option value="OCASC" selected="selected"><?php echo $opencart; ?> <?php echo $ASC; ?></option>
                        <?php } else { ?>
                        <option value="OCASC"><?php echo $opencart; ?> <?php echo $ASC; ?></option>
                        <?php } ?>
                        <?php if ($value['order'] == "OCDESC") { ?>
                        <option value="OCDESC" selected="selected"><?php echo $opencart; ?> <?php echo $DESC; ?></option>
                        <?php } else { ?>
                        <option value="OCDESC"><?php echo $opencart; ?> <?php echo $DESC; ?></option>
                        <?php } ?>
                        <?php if ($value['order'] == "OHASC") { ?>
                        <option value="OHASC" selected="selected"><?php echo $text_human; ?> <?php echo $ASC; ?></option>
                        <?php } else { ?>
                        <option value="OHASC"><?php echo $text_human; ?> <?php echo $ASC; ?></option>
                        <?php } ?>
                        <?php if ($value['order'] == "OHDESC") { ?>
                        <option value="OHDESC" selected="selected"><?php echo $text_human; ?> <?php echo $DESC; ?></option>
                        <?php } else { ?>
                        <option value="OHDESC"><?php echo $text_human; ?> <?php echo $DESC; ?></option>
                        <?php } ?>
                        <?php if ($value['order'] == "OTASC") { ?>
                        <option value="OTASC" selected="selected"><?php echo $text_count; ?> <?php echo $ASC; ?></option>
                        <?php } else { ?>
                        <option value="OTASC"><?php echo $text_count; ?> <?php echo $ASC; ?></option>
                        <?php } ?>
                        <?php if ($value['order'] == "OTDESC") { ?>
                        <option value="OTDESC" selected="selected"><?php echo $text_count; ?> <?php echo $DESC; ?></option>
                        <?php } else { ?>
                        <option value="OTDESC"><?php echo $text_count; ?> <?php echo $DESC; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-searchinput"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_search_explanation; ?>"><?php echo $entry_search; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][categories][searchinput]" id="input-searchinput" class="form-control">
                        <?php if ($value['searchinput'] == "yes") { ?>
                        <option value="yes" selected="selected"><?php echo $text_yes; ?></option>
                        <?php } else { ?>
                        <option value="yes"><?php echo $text_yes; ?></option>
                        <?php } ?>
                        <?php if ($value['view'] == "no") { ?>
                        <option value="no" selected="selected"><?php echo $text_no; ?></option>
                        <?php } else { ?>
                        <option value="no"><?php echo $text_no; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-view"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_view_explanation; ?>"><?php echo $entry_view; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][categories][view]" id="input-view" class="form-control">
                        <?php if ($value['view'] == "list") { ?>
                        <option value="list" selected="selected"><?php echo $entry_list; ?></option>
                        <?php } else { ?>
                        <option value="list"><?php echo $entry_list; ?></option>
                        <?php } ?>
                        <?php if ($value['view'] == "sele") { ?>
                        <option value="sele" selected="selected"><?php echo $entry_select; ?></option>
                        <?php } else { ?>
                        <option value="sele"><?php echo $entry_select; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-nodata"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_open_explanation; ?>"><?php echo $entry_open; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][categories][initval]" id="input-initval" class="form-control">
                        <?php if ($value['initval'] == "opened") { ?>
                        <option value="opened" selected="selected"><?php echo $text_open; ?></option>
                        <?php } else { ?>
                        <option value="opened"><?php echo $text_open; ?></option>
                        <?php } ?>
                        <?php if ($value['initval']== "closed") { ?>
                        <option value="closed" selected="selected"><?php echo $text_close; ?></option>
                        <?php } else { ?>
                        <option value="closed"><?php echo $text_close; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-style"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_style_explanation; ?>"><?php echo $entry_style; ?></span></label>
                    <div class="col-sm-10">
                      <?php if ($value['style'] == "imagen1") { ?>
                      <input type="radio" name="SETTINGS[<?php echo $store['store_id']; ?>][categories][style]" value="imagen1" checked="checked" />
                      <img src="<?php echo HTTP_CATALOG ?>/catalog/view/javascript/advancedmenu/images/imagen1.gif" />
                      <?php } else { ?>
                      <input type="radio" name="SETTINGS[<?php echo $store['store_id']; ?>][categories][style]" value="imagen1"/>
                      <img src="<?php echo HTTP_CATALOG ?>/catalog/view/javascript/advancedmenu/images/imagen1.gif" />
                      <?php } ?>
                      <?php if ($value['style'] == "imagen2") { ?>
                      <input type="radio" name="SETTINGS[<?php echo $store['store_id']; ?>][categories][style]" value="imagen2" checked="checked" />
                      <img src="<?php echo HTTP_CATALOG ?>/catalog/view/javascript/advancedmenu/images/imagen2.gif" />
                      <?php } else { ?>
                      <input type="radio" name="SETTINGS[<?php echo $store['store_id']; ?>][categories][style]" value="imagen2"/>
                      <img src="<?php echo HTTP_CATALOG ?>/catalog/view/javascript/advancedmenu/images/imagen2.gif" />
                      <?php } ?>
                    </div>
                  </div>
                  <?php // } ?>
                </div>
                <div class="tab-pane fade" id="tab-styles_<?php echo $store['store_id']; ?>">				
				<h3 style="color:#1e91cf;"><i class="fa fa-adjust"></i>&nbsp;<?php echo $tab_styles; ?> <?php echo $tab_settings; ?> <?php echo $store['name']; ?></h3><br>

                  <?php $value = $settings['str'.$store['store_id']]['styles'] ?>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-nodata"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_template_explanation; ?>"><?php echo $entry_template; ?></span></label>
                    <div class="col-sm-10">
                      <select id="menutemplates_<?php echo $store['store_id']; ?>" name="SETTINGS[<?php echo $store['store_id']; ?>][styles][template_menu]"  class="form-control">
                        <?php foreach ($templates as $template) { ?>
                        <?php if ($template == $value['template_menu']) { ?>
                        <option value="<?php echo $template; ?>" selected="selected"><?php echo $template; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $template; ?>"><?php echo $template; ?></option>
                        <?php } ?>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-10" id="template_<?php echo $store['store_id']; ?>"> </div>
                  </div>
                  <?php if (empty($templates)) { ?>
                  <div class="form-group">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-10"> 
                      <script type="text/javascript">
						$('#stylestab_<?php echo $store['store_id']; ?>').prepend('  <i class="fa fa-exclamation-triangle"></i>  ');  
						$('#stylestab_<?php echo $store['store_id']; ?>').css({'background-color': '#FFD1D1','border': '1px solid #F8ACAC','color': 'red'});
						</script>
                      <div class="alert alert-danger"> <strong>Note:</strong> if you use special theme, you MUST install the menu templates under your theme folder, if you follow the instructions to install advanced Menu V4 the menu files will be installed under default opencart theme,so you MUST change the default folder to your THEME_NAME and install it, also you must upload asearch templates also installed in default folder.</br>
                        </br>
                        <strong>ON MENU TEMPLATES FOLDER:</strong></br>
                        catalog\view\theme\<strong>default</strong>\template\module\advancedmenu\templates</br>
                        <strong>CHANGE TO:</strong></br>
                        catalog\view\theme\<strong>THEME_NAME</strong>\template\module\advancedmenu\templates</br>
                        </br>
                        <strong>ON MENU FILES FOLDER:</strong></br>
                        catalog\view\theme\<strong>default</strong>\template\product</br>
                        <strong>CHANGE TO:</strong></br>
                        catalog\view\theme\<strong>THEME_NAME</strong>\template\product</br>
                        </br>
                        also you MUST adapt the asearch templates to your theme these templates are based on category template, so you can do it your self or go to support.ocmodules.com,
                        <ul>
                          <li> Check under download section if your theme is there, download and install the files.</li>
                          <li> if not, create a support ticket and send us <strong>ftp and admin</strong> credentials we will adapt the files for your theme</li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <?php } ?>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-nodata"><span title="" data-toggle="tooltip" data-original-title="<?php echo $rtl_lang_explanation; ?>"><?php echo $rtl_lang; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][general_data][rtl_lang]" id="input-initval" class="form-control">
                        <?php if ($settings['str'.$store['store_id']]['general_data']['rtl_lang']) { ?>
                        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                        <option value="0"><?php echo $text_no; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_yes; ?></option>
                        <option value="0" selected="selected"><?php echo $text_no; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-nodata"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_style_explanation; ?>"><?php echo $entry_style_menu; ?></span></label>
                    <div class="col-sm-10">
                      <select onchange="$('#style_<?php echo $store['store_id']; ?>').load('index.php?route=module/supercategorymenuadvanced/style&token=<?php echo $token; ?>&style=' + encodeURIComponent(this.value));" name="SETTINGS[<?php echo $store['store_id']; ?>][styles][css]" id="input-initval" class="form-control">
                        <?php foreach ($styles as $style) { ?>
                        <?php if ($style == $value['css']) { ?>
                        <option value="<?php echo $style; ?>" selected="selected"><?php echo $style; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $style; ?>"><?php echo $style; ?></option>
                        <?php } ?>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-10" id="style_<?php echo $store['store_id']; ?>"> </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-skin_slider"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_style_slider_explanation; ?>"><?php echo $entry_style_slider; ?></span></label>
                    <div class="col-sm-10">
                      <select onchange="$('#skin_style<?php echo $store['store_id']; ?>').load('index.php?route=module/supercategorymenuadvanced/SliderStyle&token=<?php echo $token; ?>&style=' + encodeURIComponent(this.value));" name="SETTINGS[<?php echo $store['store_id']; ?>][styles][skin_slider]" id="input-skin_slider" class="form-control">
                        <?php foreach ($skin_sliders as $skin_slider) { ?>
                        <?php if ($style == $value['skin_slider']) { ?>
                        <option value="<?php echo $skin_slider; ?>" selected="selected"><?php echo $skin_slider; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $skin_slider; ?>"><?php echo $skin_slider; ?></option>
                        <?php } ?>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-10" id="skin_style<?php echo $store['store_id']; ?>">
                      <div style="width: 500px; position: relative; top: 32px;" class="slider_content">
                        <input id="Slider6_<?php echo $store['store_id']; ?>" type="slider" name="price" value="30000.5;60000" />
                        <script type="text/javascript" charset="utf-8">
      jQuery("#Slider6_<?php echo $store['store_id']; ?>").slider({ from: 1000, to: 100000, step: 500, smooth: true, round: 0, dimension: "&nbsp;$" });
    </script> 
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="tab-manufacturer_<?php echo $store['store_id']; ?>">
				<h3 style="color:#1e91cf;"><i class="fa fa-book"></i>&nbsp;<?php echo $tab_manufacturer; ?> <?php echo $tab_settings; ?> <?php echo $store['name']; ?></h3><br>

                  <?php $value=$settings['str'.$store['store_id']]['manufacturer']; ?>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-manufacturer-enable"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_manufacturer_explanation; ?>"><?php echo $entry_manufacturer; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][manufacturer][enable]" id="input-manufacturer-enable" class="form-control">
                        <?php if ($value['enable']) { ?>
                        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                        <option value="0"><?php echo $text_no; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_yes; ?></option>
                        <option value="0" selected="selected"><?php echo $text_no; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-list_number"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_list_number_explanation; ?>"><?php echo $entry_list_number; ?></span></label>
                    <div class="col-sm-10">
                      <input type="text" name="SETTINGS[<?php echo $store['store_id']; ?>][manufacturer][list_number]" value="<?php echo $value['list_number']; ?>" placeholder="<?php echo $entry_list_number; ?>" id="input-list_number" class="form-control" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-manufacturer-order"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_order_explanation; ?>"><?php echo $entry_order; ?></span></label>
                    <div class="col-sm-4">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][manufacturer][order]" id="input-manufacturer-order" class="form-control">
                        <?php if ($value['order'] == "OCASC") { ?>
                        <option value="OCASC" selected="selected"><?php echo $opencart; ?> <?php echo $ASC; ?></option>
                        <?php } else { ?>
                        <option value="OCASC"><?php echo $opencart; ?> <?php echo $ASC; ?></option>
                        <?php } ?>
                        <?php if ($value['order'] == "OCDESC") { ?>
                        <option value="OCDESC" selected="selected"><?php echo $opencart; ?> <?php echo $DESC; ?></option>
                        <?php } else { ?>
                        <option value="OCDESC"><?php echo $opencart; ?> <?php echo $DESC; ?></option>
                        <?php } ?>
                        <?php if ($value['order'] == "OHASC") { ?>
                        <option value="OHASC" selected="selected"><?php echo $text_human; ?> <?php echo $ASC; ?></option>
                        <?php } else { ?>
                        <option value="OHASC"><?php echo $text_human; ?> <?php echo $ASC; ?></option>
                        <?php } ?>
                        <?php if ($value['order'] == "OHDESC") { ?>
                        <option value="OHDESC" selected="selected"><?php echo $text_human; ?> <?php echo $DESC; ?></option>
                        <?php } else { ?>
                        <option value="OHDESC"><?php echo $text_human; ?> <?php echo $DESC; ?></option>
                        <?php } ?>
                        <?php if ($value['order'] == "OTASC") { ?>
                        <option value="OTASC" selected="selected"><?php echo $text_count; ?> <?php echo $ASC; ?></option>
                        <?php } else { ?>
                        <option value="OTASC"><?php echo $text_count; ?> <?php echo $ASC; ?></option>
                        <?php } ?>
                        <?php if ($value['order'] == "OTDESC") { ?>
                        <option value="OTDESC" selected="selected"><?php echo $text_count; ?> <?php echo $DESC; ?></option>
                        <?php } else { ?>
                        <option value="OTDESC"><?php echo $text_count; ?> <?php echo $DESC; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-manufacturer-view"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_view_explanation; ?>"><?php echo $entry_view; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][manufacturer][view]" id="input-manufacturer-view" class="form-control">
                        <?php if ($value['view'] == "list") { ?>
                        <option value="list" selected="selected"><?php echo $entry_list; ?></option>
                        <?php } else { ?>
                        <option value="list"><?php echo $entry_list; ?></option>
                        <?php } ?>
                        <?php if ($value['view'] == "sele") { ?>
                        <option value="sele" selected="selected"><?php echo $entry_select; ?></option>
                        <?php } else { ?>
                        <option value="sele"><?php echo $entry_select; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-manufacturer-searchinput"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_search_explanation; ?>"><?php echo $entry_search; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][manufacturer][searchinput]" id="input-manufacturer-searchinput" class="form-control">
                        <?php if ($value['searchinput'] == "yes") { ?>
                        <option value="yes" selected="selected"><?php echo $text_yes; ?></option>
                        <?php } else { ?>
                        <option value="yes"><?php echo $text_yes; ?></option>
                        <?php } ?>
                        <?php if ($value['view'] == "no") { ?>
                        <option value="no" selected="selected"><?php echo $text_no; ?></option>
                        <?php } else { ?>
                        <option value="no"><?php echo $text_no; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-manufacturer-initval"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_open_explanation; ?>"><?php echo $entry_open; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][manufacturer][initval]" id="input-manufacturer-initval" class="form-control">
                        <?php if ($value['initval'] == "opened") { ?>
                        <option value="opened" selected="selected"><?php echo $text_open; ?></option>
                        <?php } else { ?>
                        <option value="opened"><?php echo $text_open; ?></option>
                        <?php } ?>
                        <?php if ($value['initval']== "closed") { ?>
                        <option value="closed" selected="selected"><?php echo $text_close; ?></option>
                        <?php } else { ?>
                        <option value="closed"><?php echo $text_close; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <?php //} ?>
                </div>
                <div class="tab-pane fade" id="tab-pricerange_<?php echo $store['store_id']; ?>">
				 <h3 style="color:#1e91cf;"><i class="fa fa-credit-card"></i>&nbsp;<?php echo $tab_pricerange; ?> <?php echo $tab_settings; ?> <?php echo $store['name']; ?></h3><br>
                  <?php $value=$settings['str'.$store['store_id']]['pricerange']; ?>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-pricerange-enable"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_pricerange_explanation; ?>"><?php echo $entry_pricerange; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][pricerange][enable]" id="input-pricerange-enable" class="form-control">
                        <?php if ($value['enable']) { ?>
                        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                        <option value="0"><?php echo $text_no; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_yes; ?></option>
                        <option value="0" selected="selected"><?php echo $text_no; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-pricerange-view"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_view_explanation; ?>"><?php echo $entry_view; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][pricerange][view]" id="input-pricerange-view" class="form-control">
                        <?php if ($value['view'] == "slider") { ?>
                        <option value="slider" selected="selected"><?php echo $entry_slider; ?></option>
                        <?php } else { ?>
                        <option value="slider"><?php echo $entry_slider; ?></option>
                        <?php } ?>
                        <?php if ($value['view']== "list") { ?>
                        <option value="list" selected="selected"><?php echo $entry_list; ?></option>
                        <?php } else { ?>
                        <option value="list"><?php echo $entry_list; ?></option>
                        <?php } ?>
                        <?php if ($value['view']== "select") { ?>
                        <option value="select" selected="selected"><?php echo $entry_select; ?></option>
                        <?php } else { ?>
                        <option value="select"><?php echo $entry_select; ?></option>
                        <?php } ?>
                        <?php if ($value['view']== "smart") { ?>
                        <option value="smart" selected="selected"><?php echo $entry_smart; ?></option>
                        <?php } else { ?>
                        <option value="smart"><?php echo $entry_smart; ?></option>
                        <?php } ?>
                      </select>
                      <div id="smart-control"></div>
                      <div id="smart-price"></div>
                      <div id="add-new"></div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-pricerange-setvat"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_set_vat_explanation; ?>"><?php echo $entry_set_vat; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][pricerange][setvat]" id="input-pricerange-setvat" class="form-control">
                        <?php if ($value['setvat']) { ?>
                        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                        <option value="0"><?php echo $text_no; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_yes; ?></option>
                        <option value="0" selected="selected"><?php echo $text_no; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-pricerange-tax_class_id"><?php echo $default_vat_price_range; ?></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][pricerange][tax_class_id]" id="input-pricerange-tax_class_id" class="form-control">
                        <?php foreach ($tax_classes as $tax_class) { ?>
                        <?php if ($tax_class['tax_class_id'] == $value['tax_class_id']) { ?>
                        <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                        <?php } ?>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-pricerange-initval"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_open_explanation; ?>"><?php echo $entry_open; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][pricerange][initval]" id="input-pricerange-initval" class="form-control">
                        <?php if ($value['initval'] == "opened") { ?>
                        <option value="opened" selected="selected"><?php echo $text_open; ?></option>
                        <?php } else { ?>
                        <option value="opened"><?php echo $text_open; ?></option>
                        <?php } ?>
                        <?php if ($value['initval']== "closed") { ?>
                        <option value="closed" selected="selected"><?php echo $text_close; ?></option>
                        <?php } else { ?>
                        <option value="closed"><?php echo $text_close; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="tab-reviews_<?php echo $store['store_id']; ?>"> 
				<h3 style="color:#1e91cf;"><i class="fa fa-pencil-square-o"></i>&nbsp;<?php echo $tab_reviews; ?> <?php echo $tab_settings; ?> <?php echo $store['name']; ?></h3><br>
                  <?php $value=$settings['str'.$store['store_id']]['reviews']; ?>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-reviews-enable"><?php echo $entry_reviews; ?></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][reviews][enable]" id="input-reviews-enable" class="form-control">
                        <?php if ($value['enable']) { ?>
                        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                        <option value="0"><?php echo $text_no; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_yes; ?></option>
                        <option value="0" selected="selected"><?php echo $text_no; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-reviews-tipo"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_reviews_tipo_explanation; ?>"><?php echo $entry_reviews_tipo; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][reviews][tipo]" id="input-reviews-tipo" class="form-control">
                        <?php if ($value['tipo'] == "avg") { ?>
                        <option value="avg" selected="selected"><?php echo $entry_reviews_avg; ?></option>
                        <?php } else { ?>
                        <option value="avg"><?php echo $entry_reviews_avg; ?></option>
                        <?php } ?>
                        <?php if ($value['tipo']== "num") { ?>
                        <option value="num" selected="selected"><?php echo $entry_reviews_num; ?></option>
                        <?php } else { ?>
                        <option value="num"><?php echo $entry_reviews_num; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-nodata"><span title="" data-toggle="tooltip" data-original-title="<?php echo $entry_open_explanation; ?>"><?php echo $entry_open; ?></span></label>
                    <div class="col-sm-10">
                      <select name="SETTINGS[<?php echo $store['store_id']; ?>][reviews][initval]" id="input-initval" class="form-control">
                        <?php if ($value['initval'] == "opened") { ?>
                        <option value="opened" selected="selected"><?php echo $text_open; ?></option>
                        <?php } else { ?>
                        <option value="opened"><?php echo $text_open; ?></option>
                        <?php } ?>
                        <?php if ($value['initval']== "closed") { ?>
                        <option value="closed" selected="selected"><?php echo $text_close; ?></option>
                        <?php } else { ?>
                        <option value="closed"><?php echo $text_close; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="tab-pane" id="tab-admincache_<?php echo $store['store_id']; ?>">
                 <h3 style="color:#1e91cf;"><i class="fa fa-archive"></i>&nbsp;<?php echo $entry_cache_del_list; ?> <?php echo $store['name']; ?></h3><br>
					<?php $values = $settings['str'.$store['store_id']]['cache_records']['cache_records'] ?>
                  <div id="content">
                    <div class="page-header">
                      <div class="container-fluid">
                        <div class="pull-right">
                          <?php if (!$text_error_no_cache && $values) { ?>
                          <a data-toggle="tooltip" id="deletecacheadmin_<?php echo $store['store_id']; ?>" title="<?php echo $button_delete; ?>"  class="btn btn-danger"><i class="fa fa-reply"></i> <?php echo $button_delete; ?></a>
                          <?php } ?>
                        </div>
                        
                      </div>
                    </div>
                    <div class="container-fluid">
                      <?php if ($values) { ?>
                      <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $text_cache_del_remenber_setting; ?>
                        <button  type="button" class="close" data-dismiss="alert">&times;</button>
                      </div>
                      <?php } ?>
                      <?php if ($error_warning) { ?>
                      <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                      </div>
                      <?php } ?>
                      <div class="panel panel-default">
                        <div class="panel-body">
                          <div class="table-responsive" id="form_delete_reponse_<?php echo $store['store_id']; ?>">
                            <table class="table table-bordered table-hover">
                              <thead>
                                <tr>
                                  <td width="1" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected_del\']').prop('checked', this.checked);" /></td>
                                  <td class="text-right">cat</td>
                                  <td class="text-left">reference</td>
                                  <td class="text-left">cached</td>
                                  <td class="text-left">date</td>
                                </tr>
                              </thead>
                              <tbody>
                                <?php if ($values) { ?>
                                <?php foreach ($values as $key=>$val) { ?>
                                <tr>
                                  <td style="text-align: center;"><input type="checkbox" name="selected_del[]" value="<?php echo $val['cache_id']; ?>" /></td>
                                  <td class="text-right"><?php echo $val['cat']; ?></td>
                                  <td class="text-left"><?php echo $val['name']; ?></td>
                                  <td class="text-left"><?php echo $val['cached']; ?></td>
                                  <td class="text-left"><?php echo $val['date']; ?></td>
                                </tr>
                                <?php } ?>
                                <?php } else { ?>
                                <tr>
                                  <td class="center" colspan="5">No Cache records!<?php echo $text_error_no_cache; ?></td>
                                </tr>
                                <?php } ?>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <?php  }  ?>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
 <?php foreach ($stores as $store){ ?>
	function showResponseAjaxDeleteAdmin_<?php echo $store['store_id']; ?>(responseText, statusText, xhr)  { 
		$('#form_delete_reponse_<?php echo $store['store_id']; ?>').fadeOut('slow', function(){
		$("#form_delete_reponse_<?php echo $store['store_id']; ?>").html('');
		$("#form_delete_reponse_<?php echo $store['store_id']; ?>").replaceWith(responseText).fadeIn(2000);
	});
	$('div.mensajes').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $successdel; ?></div>');
		$(".alert").fadeIn("slow");
		$('.alert').delay(3500).fadeOut('slow');
		$("html, body").animate({scrollTop: 0}, "slow")	
	}
	$('a#deletecacheadmin_<?php echo $store['store_id']; ?>').on( "click", function(e){
      e.preventDefault(); // preventing default click action
	 	$.ajax({
			success:showResponseAjaxDeleteAdmin_<?php echo $store['store_id']; ?>,  // post-submit callback 
			url: 'index.php?route=module/supercategorymenuadvanced/DeleteCacheSettings&token=<?php echo $token; ?>&store_id=<?php echo $store['store_id']; ?>&catergory_id=admin&Are_you_sure=admin',
			type: 'get',
			data: $('#form_delete_reponse_<?php echo $store['store_id']; ?> input[type=\'checkbox\']:checked'),
		});
	});
	$('a#truncate_<?php echo $store['store_id']; ?>').on( "click", function(e){
      e.preventDefault(); // preventing default click action
		$.ajax({
			url: 'index.php?route=module/supercategorymenuadvanced/DeleteCacheDB&token=<?php echo $token; ?>&store_id=<?php echo $store['store_id']; ?>',
			dataType: 'json',
			beforeSend: function() {
				$('#truncate_<?php echo $store['store_id']; ?> span').prepend('<i class="fa fa-cog fa-spin"></i>');},
			complete: function() { $('.loading').remove();},
			success: function(json) {
				$('.fa-spin').remove();
				if (json['error']) {
				$('div.mensajes').html(' <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
				$('.alert').fadeIn('slow');
				}
				if (json.success) {
				 $("div.mensajes").html('<div class="alert alert-success"><i class="fa fa-check-circle"></i> '+json.success+'</div>');
				 $(".alert").fadeIn("slow");
				 $('.alert').delay(3500).fadeOut('slow');
				 $('#num_registros_<?php echo $store['store_id']; ?>').html(json.registros +" <?php echo $text_records; ?>");
				}
		}});
	}); 
	$('#principal a.tab-store_id_<?php echo $store['store_id']; ?>').on( "click", function(e) {
		$('li').removeClass('active')   
		e.preventDefault()
		$('#principal2_<?php echo $store['store_id']; ?> a:first').tab('show') // Select first tab
})
		$('#style_<?php echo $store['store_id']; ?>').load('index.php?route=module/supercategorymenuadvanced/style&token=<?php echo $token; ?>&style=' + encodeURIComponent($('select[name=\'supercategorymenuadvanced_settings[styles]\']').attr('value')));
		$('#skin_style_<?php echo $store['store_id']; ?>').load('index.php?route=module/supercategorymenuadvanced/SliderStyle&token=<?php echo $token; ?>&style=' + encodeURIComponent($('select[name=\'supercategorymenuadvanced_settings[skin_slider]\']').attr('value')));
		$('#menutemplates_<?php echo $store['store_id']; ?>').change(function() {
			var file=$(this).val();
			var n=file.split("."); 
			var uxml ='<?php echo $url_templates; ?>'+ n[0]+'.xml';
			$.ajax({
			type: 'GET',
			url: uxml,
			cache: false,
			dataType: ($.browser.msie) ? 'text' : 'xml', // Reconocemos el browser.
			success: function(data){
			var xml;
			if(typeof data == 'string'){
				 xml = new
				 ActiveXObject('Microsoft.XMLDOM');
				xml.async = false;
				xml.loadXML(data);
			} else {
				 xml = data;
			}
			$(xml).find('menu').each(function(){
			var name = $(this).find('name').text();
			var designed = $(this).find('designed').text();
			var extension = $(this).find('extension').text();
			var explantion = $(this).find('explantion').text();
			var intructions = $(this).find('intructions').text();
			var version = $(this).find('version').text();
			html='';
			html+='<strong>Name: </strong>'+ name+' v'+version+'<br>';
			html+='<strong>Designed: </strong>'+designed +'<br>';
			html+='<strong>Extension: </strong>'+ extension+'<br>';
			html+='<strong>Explantion: </strong>'+ explantion+'<br>';
			html+='<strong>Intructions: </strong>'+ intructions+'<br>';
			$( "#template" ).html( '' );
			$( "#template" ).append( html );
			});
			}
			});
		});
	<?php } ?>   
	
	$("a.register").click(function() {
		html='<div class="form-group fade in">';
                html+='<label for="input-enable" class="col-sm-2 control-label">Please enter register code:</label>';
                html+='<div class="col-sm-4">';				
				html+='<div class=col-md-4">';
				html+=' <input class="form-control" type="input" name="supercategorymenuadvanced_code" value="" />';
				html+='</div></div></div>';
		$('div#addcode').html(html);
	});
	 $('a.register').fancybox({'type':'iframe', 'transitionIn':'elastic', 'transitionOut':'elastic', 'speedIn':600, 'speedOut':200, 'scrolling':'no', 'showCloseButton':true, 'overlayShow':false,'autoDimensions':false,'width': 600,'height': 300,	});
//--></script> 
<script type="text/javascript">
function getSmartPrices() {
	$.ajax({
			url: 'index.php?route=module/supercategorymenuadvanced/getSmartPrices&token=<?php echo $token; ?>&store_id=<?php echo $store['store_id']; ?>',
			dataType: 'json',
			beforeSend: function() {
			},
			complete: function() {},
			success: function(json) {
				if (json === null){
					i = 0;
					html = '<div><a href="#" onClick="AddToList(event,'+i+')" data-toggle="tooltip" title="" class="btn btn-success" data-original-title=""><i class="fa fa-plus"></i></a></div>';
					$("#add-new").html(html);
					
					html = '<div><a href="#" onClick="saveList(event)" data-toggle="tooltip" title="" class="btn btn-success" data-original-title=""><i class="fa fa-save"></i></a></div>';
					$("#smart-control").html(html);
					return;
				}
				json = JSON.parse(JSON.stringify(json));
				if (json.length > 0) {
					html = '';
					//console.log(json);
					for (i = 0 ; i < json.length ; i++){
						html += '<div class="price-list" id="'+i+'">Add Price '+(i+1)+'<input type="text" class="smart-value" value="'+json[i].max+'" /><a href="#" onClick="deleteFromList(event,'+i+')" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title=""><i class="fa fa-minus"></i></a></div>';
					}
					$("#smart-price").html(html);
					
					html = '<div><a href="#" onClick="AddToList(event,'+i+')" data-toggle="tooltip" title="" class="btn btn-success" data-original-title=""><i class="fa fa-plus"></i></a></div>';
					$("#add-new").html(html);
					
					html = '<div><a href="#" onClick="saveList(event)" data-toggle="tooltip" title="" class="btn btn-success" data-original-title=""><i class="fa fa-save"></i></a></div>';
					$("#smart-control").html(html);
				}else{
					i = 0;
					html = '<div><a href="#" onClick="AddToList(event,'+i+')" data-toggle="tooltip" title="" class="btn btn-success" data-original-title=""><i class="fa fa-plus"></i></a></div>';
					$("#add-new").html(html);
					
					html = '<div><a href="#" onClick="saveList(event)" data-toggle="tooltip" title="" class="btn btn-success" data-original-title=""><i class="fa fa-save"></i></a></div>';
					$("#smart-control").html(html);
				}
		}});
		
}
function saveSmartPrices(prices) {
	//alert(prices);
	var pdata = {prices : prices};
	$.ajax({
			url: 'index.php?route=module/supercategorymenuadvanced/saveSmartPrices&token=<?php echo $token; ?>&store_id=<?php echo $store['store_id']; ?>',
			type: 'post',
			data: pdata,
			beforeSend: function() {
			},
			complete: function() {},
			success: function(json) {
		}});
}
function saveList(event) {
	event.preventDefault();
	
	var arrText= new Array();
	$('input[class=smart-value]').each(function(){
		arrText.push($(this).val());
	});
	console.log(arrText);
	saveSmartPrices(arrText);
	
	
}
function deleteFromList(event,id) {
	event.preventDefault();
	$("div[id="+id+"]").html('');
}
function AddToList(event,id) {
	event.preventDefault();
	i = id;
	html = '<div class="price-list" id="'+i+'">Add Price '+(i+1)+'<input type="text" class="smart-value" value="" /><a href="#" onClick="deleteFromList(event,'+i+')" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="Delete"><i class="fa fa-minus"></i></a></div>';
	$("#smart-price").append(html);
	i = i + 1;
	html = '<div><a href="#" onClick="AddToList(event,'+i+')" data-toggle="tooltip" title="" class="btn btn-success" data-original-title="Add New"><i class="fa fa-plus"></i></a></div>';
	$("#add-new").html(html);
}
	$("#input-pricerange-view").change(function(){
		val = $(this).val();
		if (val == "smart") {
			getSmartPrices();
		}else{
			$("#smart-price").html('');
			$("#add-new").html('');
			$("#smart-control").html('');
		}
	});
	
	$(document).ready(function(){
		$("#input-pricerange-view").trigger('change');
	});
</script>
</div>
<?php echo $footer; ?>
