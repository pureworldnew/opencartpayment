<?php echo $header; ?>
<?php /*
  #file: admin/view/template/catalog/product_form_configurable.tpl
  #powered by fabiom7 - www.fabiom7.com - fabiome77@hotmail.it - copyright fabiom7 2012 - 2013 - 2014
  #switched: v1.5.4.1 - v1.5.5.1 - v1.5.6
*/ ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/product_grouped.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="saveContinue();" class="button"><?php echo $button_save_continue; ?></a><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <div id="tabs" class="htabs"><a href="#tab-general"><?php echo $tab_general; ?></a><a href="#tab-data"><?php echo $tab_data; ?></a><a href="#tab-links"><?php echo $tab_links; ?></a><a href="#tab-attribute"><?php echo $tab_attribute; ?></a><a href="#tab-image"><?php echo $tab_image; ?></a><a href="#tab-grouped"><?php echo $tab_grouped; ?></a><a href="#tab-system-identifier"><?php echo $tab_system_identifier; ?></a><a href="#tab-reward"><?php echo $tab_reward; ?></a></div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-general">
          <div id="languages" class="htabs">
            <?php foreach ($languages as $language) { ?>
            <a href="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
            <?php } ?>
          </div>
          <?php foreach ($languages as $language) { ?>
          <div id="language<?php echo $language['language_id']; ?>">
            <table class="form">
              <tr>
                <td><span class="required">*</span> <?php echo $entry_name; ?></td>
                <td><input type="text" name="product_description[<?php echo $language['language_id']; ?>][name]" size="100" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['name'] : ''; ?>" />
                  <?php if (isset($error_name[$language['language_id']])) { ?>
                  <span class="error"><?php echo $error_name[$language['language_id']]; ?></span>
                  <?php } ?></td>
              </tr>
              <tr>
                <td><?php echo $entry_tag_title; ?></td>
                <td><input type="text" name="product_description[<?php echo $language['language_id']; ?>][tag_title]" value="<?php echo isset($product_description[$language['language_id']]['tag_title']) ? $product_description[$language['language_id']]['tag_title'] : ''; ?>" size="100" /></td>
              </tr>
              <tr>
                <td><?php echo $entry_meta_description; ?></td>
                <td><textarea name="product_description[<?php echo $language['language_id']; ?>][meta_description]" cols="40" rows="5"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_description'] : ''; ?></textarea></td>
              </tr>
              <tr>
                <td><?php echo $entry_meta_keyword; ?></td>
                <td><textarea name="product_description[<?php echo $language['language_id']; ?>][meta_keyword]" cols="40" rows="5"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea></td>
              </tr>
              <tr>
                <td><?php echo $entry_description; ?></td>
                <td><textarea name="product_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['description'] : ''; ?></textarea></td>
              </tr>
              <tr>
                <td><?php echo $entry_tag; ?></td>
                <td><input type="text" name="product_description[<?php echo $language['language_id']; ?>][tag]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['tag'] : ''; ?>" size="80" /></td>
              </tr>
            </table>
          </div>
          <?php } ?>
        </div>
        
        <div id="tab-data">
          <table class="form">
            <tr>
              <td><?php echo $entry_tax_class; ?></td>
              <td><select name="tax_class_id">
                  <option value="0"><?php echo $text_none; ?></option>
                  <?php foreach ($tax_classes as $tax_class) { ?>
                  <?php if ($tax_class['tax_class_id'] == $tax_class_id) { ?>
                  <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_keyword; ?></td>
              <td><input type="text" name="keyword" value="<?php echo $keyword; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_image; ?></td>
              <td valign="top"><div class="image"><img src="<?php echo $thumb; ?>" alt="" id="thumb" />
                <input type="hidden" name="image" value="<?php echo $image; ?>" id="image" />
                <br /><a onclick="image_upload('image', 'thumb');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb').attr('src', '<?php echo $no_image; ?>'); $('#image').attr('value', '');"><?php echo $text_clear; ?></a></div></td>
            </tr>
            <tr>
              <td><?php echo $entry_date_available; ?></td>
              <td><input type="text" name="date_available" value="<?php echo $date_available; ?>" size="12" class="date" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="status">
                  <?php if ($status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_sort_order; ?></td>
              <td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="2" /></td>
            </tr>
          </table> 
        </div>
        
<?php if (VERSION > '1.5.4.1') { ?>       
        <div id="tab-links">
          <table class="form">
            <tr>
              <td><?php echo $entry_manufacturer; ?></td>
              <td><input type="text" name="manufacturer" value="<?php echo $manufacturer ?>" /><input type="hidden" name="manufacturer_id" value="<?php echo $manufacturer_id; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_category; ?></td>
              <td><input type="text" name="category" value="" /></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><div id="product-category" class="scrollbox">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($product_categories as $product_category) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div id="product-category<?php echo $product_category['category_id']; ?>" class="<?php echo $class; ?>"><?php echo $product_category['name']; ?><img src="view/image/delete.png" alt="" />
                    <input type="hidden" name="product_category[]" value="<?php echo $product_category['category_id']; ?>" />
                  </div>
                  <?php } ?>
                </div></td>
            </tr>
            <tr>
              <td><?php echo $entry_filter; ?></td>
              <td><input type="text" name="filter" value="" /></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><div id="product-filter" class="scrollbox">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($product_filters as $product_filter) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div id="product-filter<?php echo $product_filter['filter_id']; ?>" class="<?php echo $class; ?>"><?php echo $product_filter['name']; ?><img src="view/image/delete.png" alt="" />
                    <input type="hidden" name="product_filter[]" value="<?php echo $product_filter['filter_id']; ?>" />
                  </div>
                  <?php } ?>
                </div></td>
            </tr>
            <tr>
              <td><?php echo $entry_store; ?></td>
              <td><div class="scrollbox">
                  <?php $class = 'even'; ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array(0, $product_store)) { ?>
                    <input type="checkbox" name="product_store[]" value="0" checked="checked" />
                    <?php echo $text_default; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="product_store[]" value="0" />
                    <?php echo $text_default; ?>
                    <?php } ?>
                  </div>
                  <?php foreach ($stores as $store) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array($store['store_id'], $product_store)) { ?>
                    <input type="checkbox" name="product_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                    <?php echo $store['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="product_store[]" value="<?php echo $store['store_id']; ?>" />
                    <?php echo $store['name']; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div></td>
            </tr>
            <tr>
              <td><?php echo $entry_download; ?></td>
              <td><input type="text" name="download" value="" /></td>
            </tr>			
            <tr>
              <td>&nbsp;</td>
              <td><div id="product-download" class="scrollbox">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($product_downloads as $product_download) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div id="product-download<?php echo $product_download['download_id']; ?>" class="<?php echo $class; ?>"> <?php echo $product_download['name']; ?><img src="view/image/delete.png" alt="" />
                    <input type="hidden" name="product_download[]" value="<?php echo $product_download['download_id']; ?>" />
                  </div>
                  <?php } ?>
                </div></td>
            </tr>
            <tr>
              <td><?php echo $entry_related; ?></td>
              <td><input type="text" name="related" value="" /></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><div id="product-related" class="scrollbox">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($product_related as $product_related) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div id="product-related<?php echo $product_related['product_id']; ?>" class="<?php echo $class; ?>"> <?php echo $product_related['name']; ?><img src="view/image/delete.png" />
                    <input type="hidden" name="product_related[]" value="<?php echo $product_related['product_id']; ?>" />
                  </div>
                  <?php } ?>
                </div></td>
            </tr>
          </table>
        </div>
        
<?php } else { ?> 
        <div id="tab-links">
          <table class="form">
            <tr>
              <td><?php echo $entry_manufacturer; ?></td>
              <td><select name="manufacturer_id">
                  <option value="0" selected="selected"><?php echo $text_none; ?></option>
                  <?php foreach ($manufacturers as $manufacturer) { ?>
                  <?php if ($manufacturer['manufacturer_id'] == $manufacturer_id) { ?>
                  <option value="<?php echo $manufacturer['manufacturer_id']; ?>" selected="selected"><?php echo $manufacturer['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $manufacturer['manufacturer_id']; ?>"><?php echo $manufacturer['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_category; ?></td>
              <td><div class="scrollbox">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($categories as $category) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array($category['category_id'], $product_category)) { ?>
                    <input type="checkbox" name="product_category[]" value="<?php echo $category['category_id']; ?>" checked="checked" />
                    <?php echo $category['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="product_category[]" value="<?php echo $category['category_id']; ?>" />
                    <?php echo $category['name']; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div>
                <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a></td>
            </tr>
            <tr>
              <td><?php echo $entry_store; ?></td>
              <td><div class="scrollbox">
                  <?php $class = 'even'; ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array(0, $product_store)) { ?>
                    <input type="checkbox" name="product_store[]" value="0" checked="checked" />
                    <?php echo $text_default; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="product_store[]" value="0" />
                    <?php echo $text_default; ?>
                    <?php } ?>
                  </div>
                  <?php foreach ($stores as $store) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array($store['store_id'], $product_store)) { ?>
                    <input type="checkbox" name="product_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                    <?php echo $store['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="product_store[]" value="<?php echo $store['store_id']; ?>" />
                    <?php echo $store['name']; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div></td>
            </tr>
            <tr>
              <td><?php echo $entry_download; ?></td>
              <td><div class="scrollbox">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($downloads as $download) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array($download['download_id'], $product_download)) { ?>
                    <input type="checkbox" name="product_download[]" value="<?php echo $download['download_id']; ?>" checked="checked" />
                    <?php echo $download['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="product_download[]" value="<?php echo $download['download_id']; ?>" />
                    <?php echo $download['name']; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div></td>
            </tr>
            <tr>
              <td><?php echo $entry_related; ?></td>
              <td><input type="text" name="related" value="" /></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><div id="product-related" class="scrollbox">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($product_related as $product_related) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div id="product-related<?php echo $product_related['product_id']; ?>" class="<?php echo $class; ?>"> <?php echo $product_related['name']; ?><img src="view/image/delete.png" />
                    <input type="hidden" name="product_related[]" value="<?php echo $product_related['product_id']; ?>" />
                  </div>
                  <?php } ?>
                </div></td>
            </tr>
          </table>
        </div>
<?php } ?>
        
        <div id="tab-attribute">
          <table id="attribute" class="list">
            <thead>
              <tr>
                <td class="left"><?php echo $entry_attribute; ?></td>
                <td class="left"><?php echo $entry_text; ?></td>
                <td></td>
              </tr>
            </thead>
            <?php $attribute_row = 0; ?>
            <?php foreach ($product_attributes as $product_attribute) { ?>
            <tbody id="attribute-row<?php echo $attribute_row; ?>">
              <tr>
                <td class="left"><input type="text" name="product_attribute[<?php echo $attribute_row; ?>][name]" value="<?php echo $product_attribute['name']; ?>" />
                  <input type="hidden" name="product_attribute[<?php echo $attribute_row; ?>][attribute_id]" value="<?php echo $product_attribute['attribute_id']; ?>" /></td>
                <td class="left"><?php foreach ($languages as $language) { ?>
                  <textarea name="product_attribute[<?php echo $attribute_row; ?>][product_attribute_description][<?php echo $language['language_id']; ?>][text]" cols="40" rows="5"><?php echo isset($product_attribute['product_attribute_description'][$language['language_id']]) ? $product_attribute['product_attribute_description'][$language['language_id']]['text'] : ''; ?></textarea>
                  <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" align="top" /><br />
                  <?php } ?></td>
                <td class="left"><a onclick="$('#attribute-row<?php echo $attribute_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
              </tr>
            </tbody>
            <?php $attribute_row++; ?>
            <?php } ?>
            <tfoot>
              <tr>
                <td colspan="2"></td>
                <td class="left"><a onclick="addAttribute();" class="button"><?php echo $button_add_attribute; ?></a></td>
              </tr>
            </tfoot>
          </table>
        </div>
        
        <div id="tab-image">
          <table id="images" class="list">
            <thead>
              <tr>
                <td class="left"><?php echo $entry_image; ?></td>
                <td class="right"><?php echo $entry_sort_order; ?></td>
                <td></td>
              </tr>
            </thead>
            <?php $image_row = 0; ?>
            <?php foreach ($product_images as $product_image) { ?>
            <tbody id="image-row<?php echo $image_row; ?>">
              <tr>
                <td class="left"><div class="image"><img src="<?php echo $product_image['thumb']; ?>" alt="" id="thumb<?php echo $image_row; ?>" />
                  <input type="hidden" name="product_image[<?php echo $image_row; ?>][image]" value="<?php echo $product_image['image']; ?>" id="image<?php echo $image_row; ?>" /><br />
                  <a onclick="image_upload('image<?php echo $image_row; ?>', 'thumb<?php echo $image_row; ?>');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb<?php echo $image_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php echo $image_row; ?>').attr('value', '');"><?php echo $text_clear; ?></a></div></td>
                <td class="right"><input type="text" name="product_image[<?php echo $image_row; ?>][sort_order]" value="<?php echo $product_image['sort_order']; ?>" size="2" /></td>
                <td class="left"><a onclick="$('#image-row<?php echo $image_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
              </tr>
            </tbody>
            <?php $image_row++; ?>
            <?php } ?>
            <tfoot>
              <tr>
                <td colspan="2"></td>
                <td class="left"><a onclick="addImage();" class="button"><?php echo $button_add_image; ?></a></td>
              </tr>
            </tfoot>
          </table>
        </div>
        
        
        <!-- Inizio -->
        <div id="tab-grouped">
          <table class="form">
            <tr>
              <td><?php echo $entry_pg_layout; ?></td>
              <td><select name="pg_layout"><?php foreach ($pg_available_layouts as $pg_available_layout) { ?>
                  <?php if ($pg_available_layout['pg_layout'] == $pg_layout) { ?>
                  <option value="<?php echo $pg_available_layout['pg_layout']; ?>" selected="selected"><?php echo $pg_available_layout['pg_label']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $pg_available_layout['pg_layout']; ?>"><?php echo $pg_available_layout['pg_label']; ?></option>
                  <?php }} ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_price; ?></td>
              <td><select name="price_type">
                  <option value="price_from">Price From</option>
                  <option value="price_from_to"<?php if((float)$price_to){ echo ' selected="selected"'; } ?>>Price From / To</option>
                  <option value="price_fixed"<?php if((float)$price_fixed){ echo ' selected="selected"'; } ?>>Price Fixed</option>
                </select>
                <input type="text" name="price_from" value="<?php echo $price_from; ?>" class="price-from" />
                <input type="text" name="price_to" value="<?php echo $price_to; ?>" class="price-to" />
                <input type="text" name="price_fixed" value="<?php echo $price_fixed; ?>" class="price-fixed" />
                <span class="help">
                  <span class="price-from"><?php echo $entry_price_from; ?></span>
                  <span class="price-to"><?php echo $entry_price_to; ?></span>
                  <span class="price-fixed"><?php echo $entry_price_fixed; ?></span>
                </span></td>
              <script type="text/javascript"><!--
			  $('select[name="price_type"]').on("change",function(){ switch($(this).val()){
			  case "price_from":    $('.price-from').show();$('.price-to').hide();$('.price-fixed').hide();break;
			  case "price_from_to": $('.price-from').show();$('.price-to').show();$('.price-fixed').hide();break;
			  case "price_fixed":   $('.price-from').hide();$('.price-to').hide();$('.price-fixed').show();break;
			  }}).trigger("change"); 
			  //--></script>
            </tr>
            <tr>
              <td><?php echo $entry_group_discount; ?></td>
              <td><select name="group_discount_type">
                <option value="F"<?php if($group_discount_type == 'F'){ echo ' selected="selected"'; } ?>><?php echo $text_amount; ?></option>
                <option value="P"<?php if($group_discount_type == 'P'){ echo ' selected="selected"'; } ?>><?php echo $text_percent; ?></option></select>
                <input type="text" name="group_discount" value="<?php echo $group_discount; ?>" /></td>
            </tr>
          </table>
          
          <style type="text/css">tr.gp-first td{background-color:#efefef;} input.option-name{width:200px;margin-bottom:2px;}</style>
          <?php if ($error_option_type) { ?>
          <div class="attention"><span class="error"><?php echo $error_option_type; ?></span></div>
          <?php } ?>
          <table id="product-configurable-option" class="list">
            <thead>
              <tr>
                <td class="left"><?php echo $column_config_option_type; ?></td>
                <td class="left"><?php echo $column_config_option_sort_order; ?></td>
                <td class="left"><?php echo $column_config_option_required; ?></td>
                <td class="left"><?php echo $column_config_option_quantity; ?></td>
                <td class="left"><?php echo $column_config_option_hide_qty; ?></td>
                <td class="left"><?php echo $column_config_option_label; ?></td>
                <td class="left"></td>
              </tr>
            </thead>
            <?php $pc_row = 0; ?>
            <?php foreach ($option_configs as $gpo) { ?>
            <tbody id="pc-row<?php echo $pc_row; ?>">
              <tr class="gp-first">
                <td class="left"><input type="text" name="gpo[<?php echo $pc_row; ?>][option_type]" value="<?php echo $gpo['option_type']; ?>" size="2" maxlength="3" /></td>
                <td class="left"><input type="text" name="gpo[<?php echo $pc_row; ?>][option_sort_order]" value="<?php echo $gpo['option_sort_order']; ?>" size="2" /></td>
                <td class="left"><select name="gpo[<?php echo $pc_row; ?>][option_required]">
                    <option value="1"<?php if($gpo['option_required']){ echo ' selected="selected"'; } ?>><?php echo $text_yes; ?></option>
                    <option value="0"<?php if(!$gpo['option_required']){ echo ' selected="selected"'; } ?>><?php echo $text_no; ?></option>
                  </select></td>
                <td class="left"><input type="text" name="gpo[<?php echo $pc_row; ?>][option_min_qty]" value="<?php echo $gpo['option_min_qty']; ?>" size="2" /></td>
                <td class="left"><select name="gpo[<?php echo $pc_row; ?>][option_hide_qty]">
                    <option value="0"<?php if(!$gpo['option_hide_qty']){ echo ' selected="selected"'; } ?>><?php echo $text_no; ?></option>
                    <option value="1"<?php if($gpo['option_hide_qty']==1){ echo ' selected="selected"'; } ?>><?php echo $text_yes; ?></option>
                    <option value="2"<?php if($gpo['option_hide_qty']==2){ echo ' selected="selected"'; } ?>><?php echo $text_yes_add_no_thanks; ?></option>
                  </select></td>
                <td class="left"><?php foreach ($languages as $language) { ?><input type="text" name="gpo[<?php echo $pc_row; ?>][option_name][<?php echo $language['language_id']; ?>][option_name]" value="<?php echo isset($gpo['option_name'][$language['language_id']]) ? $gpo['option_name'][$language['language_id']]['option_name'] : ''; ?>" class="option-name" /> <img src="view/image/flags/<?php echo $language['image']; ?>" alt="" title="<?php echo $language['name']; ?>" align="top" /><br /><?php } ?></td>
                <td class="left"><a onclick="$('#pc-row<?php echo $pc_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
              </tr>
              <!-- s nuovo //-->
              <tr>
                <td>&nbsp;</td>
                <td class="left" colspan="6" style="background:#efefef;">
          <!-- S child product //-->
          <table id="table<?php echo $pc_row; ?>" class="list table-gp-options sortable">
            <thead>
              <tr>
                <td class="left"><?php echo $column_child_image; ?></td>
                <td class="left"><?php echo $column_child_name; ?></td>
                <td class="left"><?php echo $column_child_model; ?></td>
                <td class="left"><?php echo $column_child_price; ?></td>
                <td class="left"><?php echo $column_child_info; ?></td>
                <td class="left"><?php echo $column_child_visibility; ?></td>
                <td class="left"><?php echo $column_child_nocart; ?></td>
                <td class="left"></td>
              </tr>
            </thead>
            <?php foreach ($gpo['childs'] as $child) { ?>
            <tbody id="tbody<?php echo $pc_row . '-' . $child['child_id']; ?>">
              <tr>
                <td class="left"><img src="<?php echo $child['image']; ?>" alt="" /></td>
                <td class="left">[<?php echo $child['child_id']; ?>] <a onclick="wopen('<?php echo $child['href']; ?>');"><?php echo $child['name']; ?></a>
                  <input type="hidden" name="gpo[<?php echo $pc_row; ?>][child_id][<?php echo $child['child_id']; ?>]" value="<?php echo $child['child_id']; ?>" />
                  <div id="child-to-hide-<?php echo $pc_row; ?>-<?php echo $child['child_id']; ?>"><script type="text/javascript"><!--
					$("input[name=\"gpo[<?php echo $pc_row; ?>][option_type]\" ]").on("keyup", function(){
						if($(this).val().charAt(0) == "s" || $(this).val().charAt(0) == "r"){
							$("#child-to-hide-<?php echo $pc_row; ?>-<?php echo $child['child_id']; ?>").html("<span title=\"ids, comma separated\">Hide:</span> <input type=\"text\" name=\"gpo[<?php echo $pc_row; ?>][child_to_hide][<?php echo $child['child_id']; ?>]\" value=\"<?php echo $child['child_to_hide']; ?>\" />");
						}else{
							$("#child-to-hide-<?php echo $pc_row; ?>-<?php echo $child['child_id']; ?>").html("<input type=\"hidden\" name=\"gpo[<?php echo $pc_row; ?>][child_to_hide][<?php echo $child['child_id']; ?>]\" value=\"0\" />");
						}
					}).trigger("keyup"); //--></script>
                  </div></td>
                <td class="left"><?php echo $child['model']; ?></td>
                <td class="right"><?php if ($child['special']) { ?>
                  <span style="text-decoration:line-through;"><?php echo $child['price']; ?></span><br/>
                  <span style="color:#b00;"><?php echo $child['special']; ?></span>
                  <?php } else { ?>
                  <?php echo $child['price']; ?>
                  <?php } ?></td>
                <td class="left"><?php echo $entry_quantity . ' ' . $child['quantity']; ?><br />
                  <?php echo $entry_status . ' ' . $child['status']; ?><br />
                  <?php if($child['subtract']){ echo $entry_subtract . ' ' . $text_yes; }else{ echo $entry_subtract . ' ' . $text_no; } ?></td>
                <td class="left"><select name="gpo[<?php echo $pc_row; ?>][pgvisibility][<?php echo $child['child_id']; ?>]">
                    <option value="1"<?php if($child['pgvisibility']==1){ echo ' selected="selected"'; } ?>><?php echo $text_visible; ?></option>
                    <option value="2"<?php if($child['pgvisibility']==2){ echo ' selected="selected"'; } ?>><?php echo $text_invisible_searchable; ?></option>
                    <option value="0"<?php if(!$child['pgvisibility']){ echo ' selected="selected"'; } ?>><?php echo $text_invisible; ?></option>
                  </select></td>
                <td class="left"><select name="gpo[<?php echo $pc_row; ?>][grouped_stock_status_id][<?php echo $child['child_id']; ?>]"><option value="0"> </option>
                    <?php foreach ($stock_statuses as $stock_status) { if ($child['grouped_stock_status_id'] == $stock_status['stock_status_id']) { ?>
                    <option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
                    <?php }} ?>
                  </select></td>
                <td class="left"><a onclick="$('#tbody<?php echo $pc_row . '-' . $child['child_id']; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
              </tr>
            </tbody>
            <?php } ?>
            <tfoot>
              <tr>
                <td class="left"></td>
                <td class="left"><input type="text" class="child-search" data-childfilter="name" name="child_search_name<?php echo $pc_row; ?>" value="" /></td>
                <td class="left"><input type="text" class="child-search" data-childfilter="model" name="child_search_model<?php echo $pc_row; ?>" value="" /></td>
                <td class="left" colspan="5"></td>
              </tr>
            </tfoot>
           </table>
           <!-- E child product //-->
                </td>
              </tr>
              <tr>
                <td colspan="7">&nbsp;</td><!-- separator //-->
              </tr>
              <!-- e nuovo //-->
            </tbody>
            <?php $pc_row++; ?>
            <?php } ?>
            <tfoot class="product-configurable-option">
              <tr>
                <td class="left" colspan="6"><a id="link-help-config-option">Help</a><br />
                  <div class="help-config-option" style="display:none;"><?php echo $text_help_config_option; ?></div></td>
                  <script type="text/javascript">$('#link-help-config-option').click(function(){$('.help-config-option').toggle();});</script>
                <td class="left"><a onclick="addProductConfigOption();" class="button"><?php echo $button_add_config_option; ?></a></td>
              </tr>
            </tfoot>
          </table>
        </div>
        
        <div id="tab-system-identifier">
          <table class="form">
            <tr>
              <td><?php echo $entry_quantity; ?><?php echo $text_auto_identifier_system; ?></td>
              <td><input type="text" name="quantity" value="99" size="2" style="background:#ccc;color:#fff;" readonly="readonly" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_model; ?><?php echo $text_auto_identifier_system; ?></td>
              <td><input type="text" name="model" value="<?php echo $model; ?>" style="background:#ccc;color:#fff;" readonly="readonly" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_sku; ?></td>
              <td><input type="text" name="sku" value="<?php echo $sku; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_dimension; ?></td>
              <td><input type="text" name="length" value="<?php echo $length; ?>" size="4" />
                <input type="text" name="width" value="<?php echo $width; ?>" size="4" />
                <input type="text" name="height" value="<?php echo $height; ?>" size="4" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_length; ?></td>
              <td><select name="length_class_id">
                  <?php foreach ($length_classes as $length_class) { ?>
                  <?php if ($length_class['length_class_id'] == $length_class_id) { ?>
                  <option value="<?php echo $length_class['length_class_id']; ?>" selected="selected"><?php echo $length_class['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_weight; ?></td>
              <td><input type="text" name="weight" value="<?php echo $weight; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_weight_class; ?></td>
              <td><select name="weight_class_id">
                  <?php foreach ($weight_classes as $weight_class) { ?>
                  <?php if ($weight_class['weight_class_id'] == $weight_class_id) { ?>
                  <option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
          </table>
        </div>
        
        <div id="tab-reward">
          <table class="form">
            <tr>
              <td><?php echo $entry_points; ?></td>
              <td>** <input type="text" name="points" value="<?php echo $points; ?>" /></td>
            </tr>
          </table>
          <table class="list">
            <thead>
              <tr>
                <td class="left"><?php echo $entry_customer_group; ?></td>
                <td class="right"><?php echo $entry_reward; ?></td>
              </tr>
            </thead>
            <?php foreach ($customer_groups as $customer_group) { ?>
            <tbody>
              <tr>
                <td class="left"><?php echo $customer_group['name']; ?></td>
                <td class="right">** <input type="text" name="product_reward[<?php echo $customer_group['customer_group_id']; ?>][points]" value="<?php echo isset($product_reward[$customer_group['customer_group_id']]) ? $product_reward[$customer_group['customer_group_id']]['points'] : ''; ?>" /></td>
              </tr>
            </tbody>
            <?php } ?>
          </table><span class="help">** <?php echo $text_help_reward_point; ?></span>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
CKEDITOR.replace("description<?php echo $language['language_id']; ?>", {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
<?php } ?>
//--></script> 
<script type="text/javascript"><!--
$.widget('custom.catcomplete', $.ui.autocomplete, {
	_renderMenu: function(ul, items) {
		var self = this, currentCategory = '';
		
		$.each(items, function(index, item) {
			if (item.category != currentCategory) {
				ul.append('<li class="ui-autocomplete-category">' + item.category + '</li>');
				
				currentCategory = item.category;
			}
			
			self._renderItem(ul, item);
		});
	}
});
//--></script> 

<?php if (VERSION > '1.5.4.1') { ?>
<script type="text/javascript"><!--
// Manufacturer
$('input[name=\'manufacturer\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/manufacturer/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.manufacturer_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'manufacturer\']').attr('value', ui.item.label);
		$('input[name=\'manufacturer_id\']').attr('value', ui.item.value);
	
		return false;
	},
	focus: function(event, ui) {
      return false;
   }
});

// Category
$('input[name=\'category\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.category_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('#product-category' + ui.item.value).remove();
		
		$('#product-category').append('<div id="product-category' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="product_category[]" value="' + ui.item.value + '" /></div>');

		$('#product-category div:odd').attr('class', 'odd');
		$('#product-category div:even').attr('class', 'even');
				
		return false;
	},
	focus: function(event, ui) {
      return false;
   }
});

$('#product-category div img').live('click', function() {
	$(this).parent().remove();
	
	$('#product-category div:odd').attr('class', 'odd');
	$('#product-category div:even').attr('class', 'even');	
});

// Filter
$('input[name=\'filter\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/filter/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.filter_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('#product-filter' + ui.item.value).remove();
		
		$('#product-filter').append('<div id="product-filter' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="product_filter[]" value="' + ui.item.value + '" /></div>');

		$('#product-filter div:odd').attr('class', 'odd');
		$('#product-filter div:even').attr('class', 'even');
				
		return false;
	},
	focus: function(event, ui) {
      return false;
   }
});

$('#product-filter div img').live('click', function() {
	$(this).parent().remove();
	
	$('#product-filter div:odd').attr('class', 'odd');
	$('#product-filter div:even').attr('class', 'even');	
});

// Downloads
$('input[name=\'download\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/download/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.download_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('#product-download' + ui.item.value).remove();
		
		$('#product-download').append('<div id="product-download' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="product_download[]" value="' + ui.item.value + '" /></div>');

		$('#product-download div:odd').attr('class', 'odd');
		$('#product-download div:even').attr('class', 'even');
				
		return false;
	},
	focus: function(event, ui) {
      return false;
   }
});

$('#product-download div img').live('click', function() {
	$(this).parent().remove();
	
	$('#product-download div:odd').attr('class', 'odd');
	$('#product-download div:even').attr('class', 'even');	
});
//--></script> 
<?php } /*END VERSION*/ ?>

<script type="text/javascript"><!--
// Related
$('input[name=\'related\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.product_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('#product-related' + ui.item.value).remove();
		
		$('#product-related').append('<div id="product-related' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="product_related[]" value="' + ui.item.value + '" /></div>');

		$('#product-related div:odd').attr('class', 'odd');
		$('#product-related div:even').attr('class', 'even');
				
		return false;
	},
	focus: function(event, ui) {
      return false;
   }
});

$('#product-related div img').live('click', function() {
	$(this).parent().remove();
	
	$('#product-related div:odd').attr('class', 'odd');
	$('#product-related div:even').attr('class', 'even');	
});
//--></script> 
<script type="text/javascript"><!--
var attribute_row = <?php echo $attribute_row; ?>;

function addAttribute() {
	html  = '<tbody id="attribute-row' + attribute_row + '">';
    html += '  <tr>';
	html += '    <td class="left"><input type="text" name="product_attribute[' + attribute_row + '][name]" value="" /><input type="hidden" name="product_attribute[' + attribute_row + '][attribute_id]" value="" /></td>';
	html += '    <td class="left">';
	<?php foreach ($languages as $language) { ?>
	html += "<textarea name=\"product_attribute[" + attribute_row + "][product_attribute_description][<?php echo $language['language_id']; ?>][text]\" cols=\"40\" rows=\"5\"></textarea><img src=\"view/image/flags/<?php echo $language['image']; ?>\" title=\"<?php echo $language['name']; ?>\" align=\"top\" /><br />";
    <?php } ?>
	html += '    </td>';
	html += '    <td class="left"><a onclick="$(\'#attribute-row' + attribute_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
    html += '  </tr>';	
    html += '</tbody>';
	
	$('#attribute tfoot').before(html);
	
	attributeautocomplete(attribute_row);
	
	attribute_row++;
}

function attributeautocomplete(attribute_row) {
	$('input[name=\'product_attribute[' + attribute_row + '][name]\']').catcomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/attribute/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {	
					response($.map(json, function(item) {
						return {
							category: item.attribute_group,
							label: item.name,
							value: item.attribute_id
						}
					}));
				}
			});
		}, 
		select: function(event, ui) {
			$('input[name=\'product_attribute[' + attribute_row + '][name]\']').attr('value', ui.item.label);
			$('input[name=\'product_attribute[' + attribute_row + '][attribute_id]\']').attr('value', ui.item.value);
			
			return false;
		},
		focus: function(event, ui) {
      		return false;
   		}
	});
}

$('#attribute tbody').each(function(index, element) {
	attributeautocomplete(index);
});
//--></script> 
<script type="text/javascript"><!--
function image_upload(field, thumb) {
	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).attr('value')),
					dataType: 'text',
					success: function(text) {
						$('#' + thumb).replaceWith('<img src="' + text + '" alt="" id="' + thumb + '" />');
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
<script type="text/javascript"><!--
var image_row = <?php echo $image_row; ?>;

function addImage() {
    html  = '<tbody id="image-row' + image_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><div class="image"><img src="<?php echo $no_image; ?>" alt="" id="thumb' + image_row + '" /><input type="hidden" name="product_image[' + image_row + '][image]" value="" id="image' + image_row + '" /><br /><a onclick="image_upload(\'image' + image_row + '\', \'thumb' + image_row + '\');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$(\'#thumb' + image_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image' + image_row + '\').attr(\'value\', \'\');"><?php echo $text_clear; ?></a></div></td>';
	html += '    <td class="right"><input type="text" name="product_image[' + image_row + '][sort_order]" value="" size="2" /></td>';
	html += '    <td class="left"><a onclick="$(\'#image-row' + image_row  + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#images tfoot').before(html);
	
	image_row++;
}
//--></script> 
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
<script type="text/javascript"><!--
$('.date').datepicker({dateFormat: 'yy-mm-dd'});
$('.datetime').datetimepicker({
	dateFormat: 'yy-mm-dd',
	timeFormat: 'h:m'
});
$('.time').timepicker({timeFormat: 'h:m'});
//--></script> 
<script type="text/javascript"><!--
$('#tabs a').tabs(); 
$('#languages a').tabs(); 
$('#vtab-option a').tabs();
//--></script>


<script type="text/javascript"><!--
function saveContinue(){$('#form').append('<input type="hidden" name="save_continue" value="1" />');$('#form').submit();}
function wopen(aHref){window.open(aHref,'','width=950,height=550,scrollbars=1,resizable=1,toolbar=0,menubar=0');}
//--></script>
<script type="text/javascript"><!--
var pc_row = <?php echo $pc_row; ?>;
function addProductConfigOption(){
    html  = '<tbody id="pc-row' + pc_row + '">';
	html += '<tr class="gp-first">';
	html += '  <td class="left"><input type="text" name="gpo[' + pc_row + '][option_type]" value="" size="2" maxlength="3" /></td>';
	html += '  <td class="left"><input type="text" name="gpo[' + pc_row + '][option_sort_order]" value="" size="2" /></td>';
	html += '  <td class="left"><select name="gpo[' + pc_row + '][option_required]">';
	html += '      <option value="1"><?php echo $text_yes; ?></option>';
	html += '      <option value="0" selected="selected"><?php echo $text_no; ?></option>';
	html += '    </select></td>';
	html += '  <td class="left"><input type="text" name="gpo[' + pc_row + '][option_min_qty]" value="1" size="2" /></td>';
	html += '  <td class="left"><select name="gpo[' + pc_row + '][option_hide_qty]">';
	html += '      <option value="0" selected="selected"><?php echo $text_no; ?></option>';
	html += '      <option value="1"><?php echo $text_yes; ?></option>';
	html += '      <option value="2"><?php echo $text_yes_add_no_thanks; ?></option>';
	html += '    </select></td>';
	html += '  <td class="left">'; <?php foreach ($languages as $language) { ?>
    html += '    <input type="text" name="gpo[' + pc_row + '][option_name][<?php echo $language['language_id']; ?>][option_name]" value="" class="option-name" />';
    html += '    <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" align="top" /><br />'; <?php } ?>
	html += '  </td>';
	html += '  <td class="left"><a onclick="$(\'#pc-row' + pc_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '</tr>';
	// S child
	html += '<tr>';
    html += '  <td>&nbsp;</td>';
    html += '  <td class="left" colspan="6" style="background:#efefef;">Click "<?php echo $button_save_continue; ?>" to add products.</td>';
	html += '</tr>';
	// E child
	html += '</tbody>';
	$('#product-configurable-option tfoot.product-configurable-option').before(html);
	pc_row++;
}
//--></script>
<script type="text/javascript"><!--
$('.child-search').each(function(){
var theName = $(this).attr('name');
var theFilter = $(this).attr('data-childfilter');
var theRow = theName.replace("child_search_name","").replace("child_search_model","");
var filterInput = $('input[name="' + theName + '"]');
if(theFilter=="name"){var theMinLength="<?php echo $this->config->get('min_chars_search_product_name'); ?>";}else{var theMinLength=1;}
filterInput.autocomplete({
	minLength:theMinLength,
	delay:500,
	source:function(request, response){
		$.ajax({
			url: "index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_"+theFilter+"=" + encodeURIComponent("%"+request.term),
			dataType: "json",
			beforeSend: function(){filterInput.css("background","#fff url('view/image/loading.gif') no-repeat center");},
			complete: function(){filterInput.css("background","#fff");},
			success: function(json){
				response($.map(json,function(item){
					if(item.model != 'grouped'){return{
						label: (theFilter=="name") ? item.name : item.model,
						image: item.image ? item.image : "",
						name: item.name,
						model: item.model,
						price: item.price,
						value: item.product_id
					}}
				}));
			}
		});
	},
	select:function(event, ui) {
		$('#tbody' + theRow + '-' + ui.item.value).remove();
		html  = '<tbody id="tbody' + theRow + '-' + ui.item.value + '"><tr>';
		html += '  <td class="center"><img src="' + ui.item.image + '" /></td>';
		html += '  <td class="left">[' + ui.item.value + '] ' + ui.item.name;
        html += '    <input type="hidden" name="gpo[' + theRow + '][child_id][' + ui.item.value + ']" value="' + ui.item.value + '" />';
		html += '    <div id="child-to-hide-' + theRow + '-' + ui.item.value + '"><span title="ids, comma separated">Hide:</span>';
		html += '       <input type="text" name="gpo[' + theRow + '][child_to_hide][' + ui.item.value + ']" value="" placeholder="If option is: r or s" />';
		html += '    </div></td>';
		html += '  <td class="left">' + ui.item.model + '</td>';
		html += '  <td class="right">' + ui.item.price + '</td>';
		html += '  <td class="left"> </td>';//more infos
		html += '  <td class="left"><select name="gpo[' + theRow + '][pgvisibility][' + ui.item.value + ']"><option value="1"><?php echo $text_visible; ?></option><option value="2"><?php echo $text_invisible_searchable; ?></option><option value="0"><?php echo $text_invisible; ?></option></select></td>';
		html += '  <td class="left"><select name="gpo[' + theRow + '][grouped_stock_status_id][' + ui.item.value + ']"><option value="0"> </option>';
						<?php foreach($stock_statuses as $stock_status){ if($this->config->get('default_product_nocart') == $stock_status['stock_status_id']){ ?>
						html += '<option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>';
						<?php }else{ ?>
						html += '<option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>';
						<?php }} ?>
        html += '    </select></td>';
		html += '  <td class="left"><a onclick="$(\'#tbody' + theRow + '-' + ui.item.value + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
		html += '</tr></tbody>';
		$('#table' + theRow + ' tfoot').before(html);
		$("table.sortable > tbody").sortable({items: 'tr:has(td)', connectWith: 'tbody'});
		return false;
	},
	focus: function(event, ui) {
      return false;
    }
}).data("autocomplete")._renderItem=function(ul,item){
	var theregex= new RegExp("("+this.term.split(" ").join("|")+")","gi");return $("<li></li>").data("item.autocomplete",item).append('<a style="white-space:nowrap;"><img src="'+item.image+'" style="vertical-align:middle;" /> '+item.label.replace(theregex,'<strong style="color:#C00;">$1</strong>')+'</a>').appendTo(ul);
};});
//var theregex= new RegExp("(?![^&;]+;)(?!<[^<>]*)("+this.term.replace(/([\^\$\(\)\[\]\{\}\*\.\+\?\|\\])/gi,"\\$1")+")(?![^<>]*>)(?![^&;]+;)","gi");
//--></script>
<script type="text/javascript"><!--
$("table.sortable > tbody").sortable({items: 'tr:has(td)', connectWith: 'tbody'});
//--></script>
<?php echo $footer; ?>