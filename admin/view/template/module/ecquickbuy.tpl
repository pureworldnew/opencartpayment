<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-account" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
      <div class="panel-heading">
     	 <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      
    </div>
    <div class="panel-body">
       <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-account" class="form-horizontal">
        
              
          <div id="tab-module-<?php echo $module_row; ?>" class="vtabs-content">
                         <div class="form-group">
			            	<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_layout; ?></label>
                          
                          <div class="col-sm-10"><select name="ecquickbuy_module[layout_id]" id="input-status" class="form-control">
                            <?php if ($module['layout_id'] == 0) { ?>
                            	<option value="0" selected="selected"><?php echo $text_alllayout; ?></option>
                            <?php } else { ?>
                            	<option value="0"><?php echo $text_alllayout; ?></option>
                            <?php } ?>
                            <?php 

                                if ($module['layout_id'] == 'header') { ?> 

                                <option value="header" selected="selected"><?php echo $text_header; ?></option>

                                <?php } else { ?>

                                <option value="header"><?php echo $text_header; ?></option>

                                <?php } ?>
                            
                          </select>
                        </div>
                       </div>
                        
                        <div class="form-group">
			            	<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                          
                          <div class="col-sm-10">
                        
                          <select name="ecquickbuy_module[status]" class="form-control">
                          <?php if ($module['status']) { ?>
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
			            	<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_limit; ?></label>
                          
                          <div class="col-sm-10">
                        <input type="text" name="ecquickbuy_module[limit]" class="form-control" value="<?php echo isset($module['limit'])?$module['limit']:'5'; ?>" size="5" /></div> 
                        </div>
                         <div class="form-group">
			            	<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_view_direction; ?></label>
                          
                          <div class="col-sm-10">
                          
                          <select name="ecquickbuy_module[view_direction]" class="form-control">
                          <?php if ($module['view_direction'] == "vertical") { ?>
                          <option value="horizontal"><?php echo $text_horizontal; ?></option>
                          <option value="vertical" selected="selected"><?php echo $text_vertical; ?></option>
                          <?php } else { ?>
                          <option value="horizontal"><?php echo $text_horizontal; ?></option>
                          <option value="vertical"><?php echo $text_vertical; ?></option>
                          <?php } ?>
                        </select>
                        </div>
                        </div>
                       
                        <div class="form-group">
			            	<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_width_of_input; ?></label>
                          
                          <div class="col-sm-10">
                        <input type="text" name="ecquickbuy_module[input_width]"  class="form-control" value="<?php echo isset($module['input_width'])?$module['input_width']:'35'; ?>" size="10" />
                        </div>
                        </div>
                        <div class="form-group">
			            	<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_popup_width_height; ?></label>
                          
                          <div class="col-sm-10">
                        	<input type="text" class="form-control" name="ecquickbuy_module[popup_width]" value="<?php echo isset($module['popup_width'])?$module['popup_width']:'800px'; ?>" size="10"  class="form-control" /> - <input type="text" name="ecquickbuy_module[popup_height]" value="<?php echo isset($module['popup_height'])?$module['popup_height']:'550px'; ?>" size="10"  class="form-control" /></div>
                        </div>
                        <div class="form-group">
			            	<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_item_text_color; ?></label>
                          
                          <div class="col-sm-10">
                        <input type="text" class="form-control" name="ecquickbuy_module[text_color]" value="<?php echo isset($module['hover_bgcolor'])?$module['text_color']:'000000'; ?>" size="35" /></div>
                        </div>
                        <div class="form-group">
			            	<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_hover_item_bgcolor; ?></label>
                          
                          <div class="col-sm-10">
                        <input type="text" name="ecquickbuy_module[hover_bgcolor]" value="<?php echo isset($module['hover_bgcolor'])?$module['hover_bgcolor']:'D5E4EB'; ?>" size="35"  class="form-control"/></div>
                        </div>
                        <div class="form-group">
			            	<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_show_title; ?></label>
                          
                          <div class="col-sm-10">
                         <select name="ecquickbuy_module[show_title]"  class="form-control">
                          <?php if ($module['show_title']) { ?>
                          <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                          <option value="0"><?php echo $text_disabled; ?></option>
                          <?php } else { ?>
                          <option value="1"><?php echo $text_enabled; ?></option>
                          <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                          <?php } ?>
                        </select></div>
                        </div>
                        <div class="form-group">
			            	<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_show_qty; ?></label>
                          
                          <div class="col-sm-10">
                       <select name="ecquickbuy_module[show_qty]"  class="form-control">
                          <?php if ($module['show_qty']) { ?>
                          <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                          <option value="0"><?php echo $text_disabled; ?></option>
                          <?php } else { ?>
                          <option value="1"><?php echo $text_enabled; ?></option>
                          <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                          <?php } ?>
                        </select></div>
                        </div>
                        <div class="form-group">
			            	<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_show_category; ?></label>
                          
                          <div class="col-sm-10">
                        <select name="ecquickbuy_module[show_category]"  class="form-control">
                          <?php if ($module['show_category']) { ?>
                          <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                          <option value="0"><?php echo $text_disabled; ?></option>
                          <?php } else { ?>
                          <option value="1"><?php echo $text_enabled; ?></option>
                          <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                          <?php } ?>
                        </select></div>
                        </div>
                        <div class="form-group">
			            	<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_show_manufacturer; ?></label>
                          
                          <div class="col-sm-10">
                        <select name="ecquickbuy_module[show_manufacturer]"  class="form-control">
                          <?php if ($module['show_manufacturer']) { ?>
                          <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                          <option value="0"><?php echo $text_disabled; ?></option>
                          <?php } else { ?>
                          <option value="1"><?php echo $text_enabled; ?></option>
                          <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                          <?php } ?>
                        </select></div>
                        </div>
                        <div class="form-group">
			            	<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_display_see_all; ?></label>
                          
                          <div class="col-sm-10">
                        <select name="ecquickbuy_module[all_result]"  class="form-control">
                          <?php if ($module['all_result']) { ?>
                          <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                          <option value="0"><?php echo $text_disabled; ?></option>
                          <?php } else { ?>
                          <option value="1"><?php echo $text_enabled; ?></option>
                          <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                          <?php } ?>
                        </select></div>
                        </div>
                        <div class="form-group">
			            	<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_display_tag_suggestion; ?></label>
                          
                          <div class="col-sm-10">
                       <select name="ecquickbuy_module[tag_suggestion]"  class="form-control">
                          <?php if ($module['tag_suggestion']) { ?>
                          <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                          <option value="0"><?php echo $text_disabled; ?></option>
                          <?php } else { ?>
                          <option value="1"><?php echo $text_enabled; ?></option>
                          <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                          <?php } ?>
                        </select></div>
                        </div>
                        <div class="form-group">
			            	<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_display_image; ?></label>
                          
                          <div class="col-sm-10">
                        <select name="ecquickbuy_module[show_image]"  class="form-control">
                          <?php if ($module['show_image']) { ?>
                          <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                          <option value="0"><?php echo $text_disabled; ?></option>
                          <?php } else { ?>
                          <option value="1"><?php echo $text_enabled; ?></option>
                          <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                          <?php } ?>
                        </select></div>
                        </div>
                        <div class="form-group">
			            	<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_display_price; ?></label>
                          
                          <div class="col-sm-10">
                        <select name="ecquickbuy_module[show_price]"  class="form-control">
                          <?php if ($module['show_price']) { ?>
                          <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                          <option value="0"><?php echo $text_disabled; ?></option>
                          <?php } else { ?>
                          <option value="1"><?php echo $text_enabled; ?></option>
                          <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                          <?php } ?>
                        </select></div>
                        </div>
                         <div class="form-group">
			            	<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_display_search_sub_category; ?></label>
                          
                          <div class="col-sm-10">
                         <select name="ecquickbuy_module[search_sub_category]"  class="form-control">
                          <?php if ($module['search_sub_category']) { ?>
                          <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                          <option value="0"><?php echo $text_disabled; ?></option>
                          <?php } else { ?>
                          <option value="1"><?php echo $text_enabled; ?></option>
                          <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                          <?php } ?>
                        </select></div>
                        </div>
                        <div class="form-group">
			            	<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_display_search_description; ?></label>
                          
                          <div class="col-sm-10">
                         <select name="ecquickbuy_module[search_description] "  class="form-control">
                          <?php if ($module['search_description']) { ?>
                          <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                          <option value="0"><?php echo $text_disabled; ?></option>
                          <?php } else { ?>
                          <option value="1"><?php echo $text_enabled; ?></option>
                          <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                          <?php } ?>
                        </select></div>
                        </div>
                        
                        <div class="form-group">
			            	<label class="col-sm-2 control-label" for="input-status"><?php echo 'Module Status'; ?></label>
                          
                          	<div class="col-sm-10">
                        
                                <select name="ecquickbuy_status" class="form-control" >
                                    <?php if ($module['status']) { ?>
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
          
      </form>
    </div>
  </div>
</div>
 
<?php echo $footer; ?>