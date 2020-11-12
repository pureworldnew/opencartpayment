<table id="Settings" class="table">
    <tr>
        <td class="col-xs-3">
            <h5><strong><?php echo $text_panel_name; ?></strong></h5>
            <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_panel_name_help; ?></span></td>
        <td class="col-xs-9">
            <div class="col-xs-4">
                <?php foreach ($languages as $lang) : ?>
                        <div class="input-group">
                            <span class="input-group-addon"><img src="<?php echo $lang['flag_url'] ?>" title="<?php echo $lang['name']; ?>" /> <?php echo $lang['name']; ?>:</span> 
                            <input type="text" class="form-control" name="<?php echo $moduleNameSmall; ?>[PanelName][<?php echo $lang['code']; ?>]" value="<?php if(isset($moduleData['PanelName'][$lang['code']])) { echo $moduleData['PanelName'][$lang['code']]; } else { echo "Last Viewed"; }?>" />           
                        </div>
                    <br />
                <?php endforeach;?>
            </div>
        </td>
    </tr>
    <tr>
        <td class="col-xs-3">
            <h5><strong><?php echo  $show_add_to_cart; ?></strong></h5>
            <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $show_add_to_cart_help; ?></span></td>
        <td class="col-xs-9">
            <div class="col-xs-4">
                <select name="<?php echo $moduleNameSmall; ?>[show_add_button]" class="form-control">
                    <option value="yes" <?php echo (!empty($moduleData['show_add_button']) && $moduleData['show_add_button'] == 'yes') ? 'selected=selected' : '' ?>><?php echo $text_enabled; ?></option>
                    <option value="no" <?php echo (empty($moduleData['show_add_button']) || $moduleData['show_add_button'] == 'no') ? 'selected=selected' : '' ?>><?php echo $text_disabled; ?></option>
                </select>
            </div>
        </td>
    </tr>
    <tr>
        <td class="col-xs-3">
            <h5><strong><?php echo $text_products; ?></strong></h5>
            <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_products_help; ?></span></td>
        <td class="col-xs-9">
            <div class="col-xs-4">
                <div class="input-group">
                    <input type="number" class="form-control" name="<?php echo $moduleNameSmall; ?>[Limit]" value="<?php if(isset($moduleData['Limit'])) { echo $moduleData['Limit']; } else { echo "5"; }?>" />
                    <span class="input-group-addon"><?php echo $text_products_small; ?></span>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td class="col-xs-3">
            <h5><strong><?php echo $text_image_dimensions; ?></strong></h5>
            <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_image_dimensions_help; ?></span></td>
        <td class="col-xs-9">
            <div class="col-xs-4">
                <div class="input-group">
                  <span class="input-group-addon">Width:&nbsp;</span>
                  <input type="text" class="form-control" name="<?php echo $moduleNameSmall; ?>[ImageWidth]" value="<?php if(isset($moduleData['ImageWidth'])) { echo $moduleData['ImageWidth']; } else { echo "80"; }?>" />
                  <span class="input-group-addon"><?php echo $text_pixels; ?></span>
                </div><br />
                <div class="input-group">
                  <span class="input-group-addon">Height:</span>
                  <input type="text" class="form-control" name="<?php echo $moduleNameSmall; ?>[ImageHeight]" value="<?php if(isset($moduleData['ImageHeight'])) { echo $moduleData['ImageHeight']; } else { echo "80"; }?>" />
                  <span class="input-group-addon"><?php echo $text_pixels; ?></span>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td class="col-xs-3">
            <h5><strong><?php echo $wrap_widget; ?></strong></h5>
            <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $wrap_widget_help; ?></span></td>
        <td class="col-xs-9">
            <div class="col-xs-4">
                <select name="<?php echo $moduleNameSmall; ?>[wrap_widget]" class="form-control">
                    <option value="yes" <?php echo (!empty($moduleData['wrap_widget']) && $moduleData['wrap_widget'] == 'yes') ? 'selected=selected' : '' ?>><?php echo $text_enabled; ?></option>
                    <option value="no" <?php echo (empty($moduleData['wrap_widget']) || $moduleData['wrap_widget'] == 'no') ? 'selected=selected' : '' ?>><?php echo $text_disabled; ?></option>
                </select>
            </div>
        </td>
    </tr>
    <tr>
        <td class="col-xs-3">
            <h5><strong><?php echo $custom_css; ?></strong></h5>
            <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $custom_css_help; ?></span></td>
        <td class="col-xs-9">
            <div class="col-xs-4">
                <div class="form-group" style="padding-top:10px;">
                    <textarea class="form-control" name="<?php echo $moduleNameSmall; ?>[CustomCSS]" placeholder="<?php echo $custom_css_placeholder; ?>" rows="4"><?php if(isset($moduleData['CustomCSS'])) { echo $moduleData['CustomCSS']; } else { echo ""; }?></textarea>
                </div>
            </div>
        </td>
    </tr>
</table>