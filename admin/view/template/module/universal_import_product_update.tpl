<?php include DIR_TEMPLATE.'module/universal_import_functions.tpl'; ?>
  
  <ul class="nav nav-tabs">
    <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $_language->get('tab_quick_update'); ?></a></li>
    <li class="pull-right"><a href="#tab-functions" data-toggle="tab"><?php echo $_language->get('tab_functions'); ?></a></li>
  </ul>
  <div class="tab-content alternateColors">
  
    <div class="tab-pane active" id="tab-general">
      <div class="well quickUpdateWell">
        <h4><?php echo $_language->get('tab_quick_update'); ?></h4>
        <p><?php echo $_language->get('text_quick_update_identifier'); ?></p>
        <?php
          if (!isset($profile['item_identifier'])) {
            echo 'Please select an item identifier in step 2';
          } else {
          if ($profile['item_identifier'] == $type.'_id') {
            dataField($type.'_id', $_language->get('entry_'.$type.'_id'), $columns, $profile, $_language);
          } else {
            dataField($profile['item_identifier'], $_language->get('entry_'.$profile['item_identifier']), $columns, $profile, $_language);
            }
          }
        ?>
      </div>
      <?php dataField('price', $_language->get('entry_price'), $columns, $profile, $_language); ?>
      <?php /* dataField('quantity', $_language->get('entry_quantity'), $columns, $profile, $_language, 'text'); */ ?>
       
      <div class="row">
        <label class="col-sm-2 control-label"><?php echo $_language->get('entry_quantity'); ?></label>
        <div class="col-sm-4">
          <?php dataField('quantity', false, $columns, $profile, $_language); ?>
        </div>
        <label class="col-sm-1 control-label"><span data-toggle="tooltip" title="<?php echo $_language->get('entry_value_modifier_i'); ?>"><?php echo $_language->get('entry_value_modifier'); ?></span></label>
        <div class="col-sm-2">
          <select class="form-control" name="quantity_modifier">
            <option value="">Replace</option>
            <option value="+">Add</option>
            <option value="-">Subtract</option>
          </select>
        </div>
        <label class="col-sm-1 control-label"><span data-toggle="tooltip" title="<?php echo $_language->get('entry_default_i'); ?>"><?php echo $_language->get('import_default_value'); ?></span></label>
        <div class="col-sm-2">
          <input type="text" class="form-control" name="defaults[quantity]" value="<?php if (isset($profile['defaults']['quantity'])) echo $profile['defaults']['quantity']; ?>" />
        </div>
      </div>
      
      <hr class="dotted"/>
      
      <?php dataField('status', $_language->get('entry_status'), $columns, $profile, $_language, 'select',
        array(
          '' => $_language->get('text_no_change'),
          '1' => $_language->get('text_enabled'),
          '0' => $_language->get('text_disabled'),
        ));
      ?>
    </div>
    
    <div class="tab-pane" id="tab-functions">
    
      <ul class="nav nav-pills nav-stacked col-md-2">
        <li class="active"><a href="#tab-extra-func-1" data-toggle="pill"><?php echo $_language->get('tab_functions'); ?></a></li>
        <li><a href="#tab-extra-func-2" data-toggle="pill"><?php echo $_language->get('tab_extra'); ?></a></li>
        <li><a href="#tab-extra-func-4" data-toggle="pill"><?php echo $_language->get('tab_disable_cfg'); ?></a></li>
      </ul>
      <div class="tab-content col-md-10" style="min-height:400px;padding-bottom:120px">
        <div class="tab-pane active" id="tab-extra-func-1">
          <div class="well">
            <h4><?php echo $_language->get('tab_functions'); ?></h4>
            <p><?php echo $_language->get('info_extra_functions'); ?></p>
          </div>
          
          <?php extraImportFunctions($columns, $profile, $_language, $languages); ?>
        </div>
        <div class="tab-pane" id="tab-extra-func-2">
          <div class="well">
            <h4><?php echo $_language->get('tab_extra'); ?></h4>
            <p><?php echo $_language->get('info_extra_field'); ?></p>
          </div>
          
          <?php dataField('_extra_', $_language->get('entry_extra'), $columns, $profile, $_language); ?>
          <?php if (!empty($profile['extra'])) { foreach ($profile['extra'] as $extra) { ?>
            <?php dataField($extra, $_language->get('entry_extra'), $columns, $profile, $_language); ?>
          <?php }} ?>
          <div class="row">
            <div class="col-md-offset-2 col-md-7">
              <button type="button" class="btn btn-success btn-block add-extra"><i class="fa fa-plus"></i> <?php echo $_language->get('text_add_extra_field'); ?></button>
            </div>
          </div>
          
          <hr class="dotted"/>
          
          <?php dataFieldML('_extra_', $_language->get('entry_extra_ml'), $columns, $profile, $_language, $languages, $type); ?>
          <?php if (!empty($profile['extraml'])) { foreach ($profile['extraml'] as $extra) { ?>
            <?php dataFieldML($extra, $_language->get('entry_extra_ml'), $columns, $profile, $_language, $languages, $type); ?>
          <?php }} ?>
          <div class="row">
            <div class="col-md-offset-2 col-md-7">
              <button type="button" class="btn btn-success btn-block add-extra-ml"><i class="fa fa-plus"></i> <?php echo $_language->get('text_add_extra_field_ml'); ?></button>
            </div>
          </div>
        </div>
        <div class="tab-pane" id="tab-extra-func-4">
          <div class="well">
            <h4><?php echo $_language->get('tab_disable_cfg'); ?></h4>
            <p><?php echo $_language->get('info_disable_cfg'); ?></p>
          </div>
          
          <div class="row">
            <label class="col-sm-2 control-label"><?php echo $_language->get('entry_disable_config'); ?></label>
            <div class="col-md-8">
              <input class="form-control" type="text" name="disable_cfg" value="<?php echo isset($profile['disable_cfg']) ? $profile['disable_cfg'] : ''; ?>" placeholder="<?php echo $_language->get('placeholder_disable_config'); ?>"/>
            </div>
          </div>
        </div>
      </div>
    </div>
    
  </div>
  
  <hr />

  <div class="pull-right">
    <button type="button" class="btn btn-default cancel" data-step="3"><i class="fa fa-reply"></i> <?php echo $_language->get('text_previous_step'); ?></button>
    <button type="button" class="btn btn-success submit" data-step="3"><i class="fa fa-check"></i> <?php echo $_language->get('text_next_step'); ?></button>
  </div>

<div class="spacer"></div>