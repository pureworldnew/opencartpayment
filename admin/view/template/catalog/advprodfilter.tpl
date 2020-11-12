<?php echo $header; ?>
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
  
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
  
  <div class="box">
    
    <div class="heading">
      <h1><img src="view/image/product.png" alt="" /> <?php echo $apftxt_p_filters_h; ?></h1>
    </div>
    
    <div class="content">
    		
    	<table class="list">
    	
    	<tbody>

    	<tr>
    	  <td class="left" style="width:202px;">
    	  <strong><?php echo $apftxt_name; ?></strong>
    	  </td>
    	  <td colspan="4" class="left">
    	  <input size="22" type="text" value="<?php echo $filter_name; ?>" name="filter_name">
    	  <span style="color:#666666;font-size:11px;">&nbsp;<?php echo $apftxt_name_help; ?></span>
    	  </td>
    	</tr>

    	<tr>
    	  <td class="left">
    	  <strong><?php echo $apftxt_tag; ?></strong>
    	  </td>
    	  <td colspan="4" class="left">
    	  <input size="22" type="text" value="<?php echo $filter_tag; ?>" name="filter_tag">
    	  <span style="color:#666666;font-size:11px;">&nbsp;<?php echo $apftxt_tag_help; ?></span>
    	  </td>
    	</tr>

    	<tr>
    	  <td class="left">
    	  <strong><?php echo $apftxt_model; ?></strong>
    	  </td>
    	  <td colspan="4" class="left">
    	  <input size="22" type="text" value="<?php echo $filter_model; ?>" name="filter_model">
    	  <span style="color:#666666;font-size:11px;">&nbsp;<?php echo $apftxt_model_help; ?></span>
    	  </td>
    	</tr>

    	<tr>
    	  <td class="left">
    	  <strong><?php echo $apftxt_categories; ?></strong>
    	  <br />
    	  <span class="help"><?php echo $apftxt_unselect_all_to_ignore; ?></span>
    	  </td>
    	  <td colspan="4" class="left">
    	  <div class="scrollbox" style="width:510px !important;">
                  <?php $class = 'odd'; ?>
                  
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array(0, $product_category)) { ?>
                    <input type="checkbox" name="product_category[]" value="0" checked="checked" /><?php echo $apftxt_none_cat; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="product_category[]" value="0" /><?php echo $apftxt_none_cat; ?>
                    <?php } ?>
                  </div>
                  
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
                <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $apftxt_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $apftxt_unselect_all; ?></a>
    	  </td>
    	</tr>
    	
    	<tr>
    	  <td class="left">
    	  <strong><?php echo $apftxt_manufacturers; ?></strong>
    	  <br />
    	  <span class="help"><?php echo $apftxt_unselect_all_to_ignore; ?></span>
    	  </td>
    	  <td colspan="4" class="left">
    	  <div class="scrollbox" style="width:510px !important;">
                  <?php $class = 'odd'; ?>
                  
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array(0, $manufacturer_ids)) { ?>
                    <input type="checkbox" name="manufacturer_ids[]" value="0" checked="checked" /><?php echo $apftxt_none; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="manufacturer_ids[]" value="0" /><?php echo $apftxt_none; ?>
                    <?php } ?>
                  </div>
                  
                  <?php foreach ($manufacturers as $manufacturer) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array($manufacturer['manufacturer_id'], $manufacturer_ids)) { ?>
                    <input type="checkbox" name="manufacturer_ids[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" checked="checked" />
                    <?php echo $manufacturer['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="manufacturer_ids[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" />
                    <?php echo $manufacturer['name']; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div>
                <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $apftxt_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $apftxt_unselect_all; ?></a>
    	  </td>
    	</tr>
    	
    	<?php if(version_compare(VERSION, '1.5.4.1', '>')) { ?>
    	<tr>
    	  <td class="left">
    	  <strong><?php echo $apftxt_p_filters; ?></strong>
    	  <br />
    	  <span class="help"><?php echo $apftxt_unselect_all_to_ignore; ?></span>
    	  </td>
    	  <td colspan="4" class="left">
    	  <?php if($p_filters) { ?>
    	  <div class="scrollbox" style="width:510px !important;">
                  <?php $class = 'odd'; ?>
                  
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array(0, $filters_ids)) { ?>
                    <input type="checkbox" name="filters_ids[]" value="0" checked="checked" /><?php echo $apftxt_none_fil; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="filters_ids[]" value="0" /><?php echo $apftxt_none_fil; ?>
                    <?php } ?>
                  </div>
                  
                  <?php foreach ($p_filters as $p_filter) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array($p_filter['filter_id'], $filters_ids)) { ?>
                    <input type="checkbox" name="filters_ids[]" value="<?php echo $p_filter['filter_id']; ?>" checked="checked" />
                    <?php echo $p_filter['group'].' &gt; '.$p_filter['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="filters_ids[]" value="<?php echo $p_filter['filter_id']; ?>" />
                    <?php echo $p_filter['group'].' &gt; '.$p_filter['name']; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div>
                <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $apftxt_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $apftxt_unselect_all; ?></a>
    	  <?php } else { echo $apftxt_p_filters_none; } ?>
    	  </td>
    	</tr>
	<?php } ?>
    	
    	<tr>
    	  <td class="left">
    	  <strong><?php echo $apftxt_price; ?></strong>
    	  <br />
    	  <span class="help"><?php echo $apftxt_price_help; ?></span>
    	  </td>
    	  <td class="right">
    	  <?php echo $apftxt_greater_than_or_equal; ?>
    	  <br />
    	  <span class="help"><?php echo $apftxt_leave_empty_to_ignore; ?></span>
    	  </td>
    	  <td class="left">
    	  <input size="10" type="text" value="<?php echo $price_mmarese; ?>" name="price_mmarese">
    	  </td>
    	  <td class="right">
    	  <?php echo $apftxt_less_than_or_equal; ?>
    	  <br />
    	  <span class="help"><?php echo $apftxt_leave_empty_to_ignore; ?></span>
    	  </td>
    	  <td class="left">
    	  <input size="10" type="text" value="<?php echo $price_mmicse; ?>" name="price_mmicse">
    	  </td>
    	</tr>

    	<tr>
    	  <td class="left">
    	  <strong><?php echo $apftxt_discount; ?></strong>
    	  </td>
    	  <td class="right">
    	  
    	  <div style="float:left;border-right:1px solid #DDDDDD;margin: -7px;padding: 7px;">
    	  <?php echo $apftxt_customer_group; ?><br />
    	  <select name="d_cust_group_filter">
    	  <option value="any"<?php if ($d_cust_group_filter=='any') { echo ' selected="selected"'; } ?>><?php echo $apftxt_all; ?></option>
    	  <?php foreach ($customer_groups as $customer_group) { ?>
    	  <option value="<?php echo $customer_group['customer_group_id']; ?>"<?php if ($customer_group['customer_group_id']==$d_cust_group_filter) { echo ' selected="selected"'; } ?>><?php echo $customer_group['name']; ?></option>
    	  <?php } ?>
    	  </select>
    	  </div>
    	  
    	  <?php echo $apftxt_greater_than_or_equal; ?>
    	  <br />
    	  <span class="help"><?php echo $apftxt_leave_empty_to_ignore; ?></span>
    	  </td>
    	  <td class="left">
    	  <input size="10" type="text" value="<?php echo $d_price_mmarese; ?>" name="d_price_mmarese">
    	  </td>
    	  <td class="right">
    	  <?php echo $apftxt_less_than_or_equal; ?>
    	  <br />
    	  <span class="help"><?php echo $apftxt_leave_empty_to_ignore; ?></span>
    	  </td>
    	  <td class="left">
    	  <input size="10" type="text" value="<?php echo $d_price_mmicse; ?>" name="d_price_mmicse">
    	  </td>
    	</tr>

    	<tr>
    	  <td class="left">
    	  <strong><?php echo $apftxt_special; ?></strong>
    	  </td>
    	  <td class="right">
    	  
    	  <div style="float:left;border-right:1px solid #DDDDDD;margin: -7px;padding: 7px;">
    	  <?php echo $apftxt_customer_group; ?><br />
    	  <select name="s_cust_group_filter">
    	  <option value="any"<?php if ($s_cust_group_filter=='any') { echo ' selected="selected"'; } ?>><?php echo $apftxt_all; ?></option>
    	  <?php foreach ($customer_groups as $customer_group) { ?>
    	  <option value="<?php echo $customer_group['customer_group_id']; ?>"<?php if ($customer_group['customer_group_id']==$s_cust_group_filter) { echo ' selected="selected"'; } ?>><?php echo $customer_group['name']; ?></option>
    	  <?php } ?>
    	  </select>
    	  </div>
    	  
    	  <?php echo $apftxt_greater_than_or_equal; ?>
    	  <br />
    	  <span class="help"><?php echo $apftxt_leave_empty_to_ignore; ?></span>
    	  </td>
    	  <td class="left">
    	  <input size="10" type="text" value="<?php echo $s_price_mmarese; ?>" name="s_price_mmarese">
    	  </td>
    	  <td class="right">
    	  <?php echo $apftxt_less_than_or_equal; ?>
    	  <br />
    	  <span class="help"><?php echo $apftxt_leave_empty_to_ignore; ?></span>
    	  </td>
    	  <td class="left">
    	  <input size="10" type="text" value="<?php echo $s_price_mmicse; ?>" name="s_price_mmicse">
    	  </td>
    	</tr>

    	<tr>
    	  <td class="left">
    	  <strong><?php echo $apftxt_tax_class; ?></strong>
    	  </td>
    	  <td colspan="4" class="left">
    	  <select name="tax_class_filter">
    	  <option value="any"<?php if ($tax_class_filter=='any') { echo ' selected="selected"'; } ?>><?php echo $apftxt_ignore_this; ?></option>
    	  <option value="0"<?php if ($tax_class_filter=='0') { echo ' selected="selected"'; } ?>> <?php echo $apftxt_none; ?> </option>
    	  <?php foreach ($tax_classes as $tax_class) { ?>
    	  <option value="<?php echo $tax_class['tax_class_id']; ?>"<?php if ($tax_class['tax_class_id']==$tax_class_filter) { echo ' selected="selected"'; } ?>><?php echo $tax_class['title']; ?></option>
    	  <?php } ?>
    	  </select>
    	  </td>
    	</tr>
    	
    	<tr>
    	  <td class="left">
    	  <strong><?php echo $apftxt_quantity; ?></strong>
    	  </td>
    	  <td class="right">
    	  <?php echo $apftxt_greater_than_or_equal; ?>
    	  <br />
    	  <span class="help"><?php echo $apftxt_leave_empty_to_ignore; ?></span>
    	  </td>
    	  <td class="left">
    	  <input size="10" type="text" value="<?php echo $stock_mmarese; ?>" name="stock_mmarese">
    	  </td>
    	  <td class="right">
    	  <?php echo $apftxt_less_than_or_equal; ?>
    	  <br />
    	  <span class="help"><?php echo $apftxt_leave_empty_to_ignore; ?></span>
    	  </td>
    	  <td class="left">
    	  <input size="10" type="text" value="<?php echo $stock_mmicse; ?>" name="stock_mmicse">
    	  </td>
    	</tr>

    	<tr>
    	  <td class="left">
    	  <strong><?php echo $apftxt_minimum_quantity; ?></strong>
    	  </td>
    	  <td class="right">
    	  <?php echo $apftxt_greater_than_or_equal; ?>
    	  <br />
    	  <span class="help"><?php echo $apftxt_leave_empty_to_ignore; ?></span>
    	  </td>
    	  <td class="left">
    	  <input size="10" type="text" value="<?php echo $min_q_mmarese; ?>" name="min_q_mmarese">
    	  </td>
    	  <td class="right">
    	  <?php echo $apftxt_less_than_or_equal; ?>
    	  <br />
    	  <span class="help"><?php echo $apftxt_leave_empty_to_ignore; ?></span>
    	  </td>
    	  <td class="left">
    	  <input size="10" type="text" value="<?php echo $min_q_mmicse; ?>" name="min_q_mmicse">
    	  </td>
    	</tr>

    	<tr>
    	  <td class="left">
    	  <strong><?php echo $apftxt_subtract_stock; ?></strong>
    	  </td>
    	  <td colspan="4" class="left">
    	  <select name="subtract_filter">
    	  <option value="any"<?php if ($subtract_filter=='any') { echo ' selected="selected"'; } ?>><?php echo $apftxt_ignore_this; ?></option>
    	  <option value="1"<?php if ($subtract_filter=='1') { echo ' selected="selected"'; } ?>><?php echo $apftxt_yes; ?></option>
    	  <option value="0"<?php if ($subtract_filter=='0') { echo ' selected="selected"'; } ?>><?php echo $apftxt_no; ?></option>
    	  </select>
    	  </td>
    	</tr>

    	<tr>
    	  <td class="left">
    	  <strong><?php echo $apftxt_out_of_stock_status; ?></strong>
    	  </td>
    	  <td colspan="4" class="left">
    	  <select name="stock_status_filter">
    	  <option value="any"<?php if ($stock_status_filter=='any') { echo ' selected="selected"'; } ?>><?php echo $apftxt_ignore_this; ?></option>
    	  <?php foreach ($stock_statuses as $stock_status) { ?>
    	  <option value="<?php echo $stock_status['stock_status_id']; ?>"<?php if ($stock_status['stock_status_id']==$stock_status_filter) { echo ' selected="selected"'; } ?>><?php echo $stock_status['name']; ?></option>
    	  <?php } ?>
    	  </select>
    	  </td>
    	</tr>

    	<tr>
    	  <td class="left">
    	  <strong><?php echo $apftxt_requires_shipping; ?></strong>
    	  </td>
    	  <td colspan="4" class="left">
    	  <select name="shipping_filter">
    	  <option value="any"<?php if ($shipping_filter=='any') { echo ' selected="selected"'; } ?>><?php echo $apftxt_ignore_this; ?></option>
    	  <option value="1"<?php if ($shipping_filter=='1') { echo ' selected="selected"'; } ?>><?php echo $apftxt_yes; ?></option>
    	  <option value="0"<?php if ($shipping_filter=='0') { echo ' selected="selected"'; } ?>><?php echo $apftxt_no; ?></option>
    	  </select>
    	  </td>
    	</tr>

    	<tr>
    	  <td class="left">
    	  <strong><?php echo $apftxt_date_available; ?></strong>
    	  </td>
    	  <td class="right">
    	  <?php echo $apftxt_greater_than_or_equal; ?>
    	  <br />
    	  <span class="help"><?php echo $apftxt_leave_empty_to_ignore; ?></span>
    	  </td>
    	  <td class="left">
    	  <input class="date" size="14" type="text" value="<?php echo $date_mmarese; ?>" name="date_mmarese">
    	  </td>
    	  <td class="right">
    	  <?php echo $apftxt_less_than_or_equal; ?>
    	  <br />
    	  <span class="help"><?php echo $apftxt_leave_empty_to_ignore; ?></span>
    	  </td>
    	  <td class="left">
    	  <input class="date" size="14" type="text" value="<?php echo $date_mmicse; ?>" name="date_mmicse">
    	  </td>
    	</tr>

    	<tr>
    	  <td class="left">
    	  <strong><?php echo $apftxt_date_added; ?></strong>
    	  </td>
    	  <td class="right">
    	  <?php echo $apftxt_greater_than_or_equal; ?>
    	  <br />
    	  <span class="help"><?php echo $apftxt_leave_empty_to_ignore; ?></span>
    	  </td>
    	  <td class="left">
    	  <input class="datetime" size="14" type="text" value="<?php echo $date_added_mmarese; ?>" name="date_added_mmarese">
    	  </td>
    	  <td class="right">
    	  <?php echo $apftxt_less_than_or_equal; ?>
    	  <br />
    	  <span class="help"><?php echo $apftxt_leave_empty_to_ignore; ?></span>
    	  </td>
    	  <td class="left">
    	  <input class="datetime" size="14" type="text" value="<?php echo $date_added_mmicse; ?>" name="date_added_mmicse">
    	  </td>
    	</tr>

    	<tr>
    	  <td class="left">
    	  <strong><?php echo $apftxt_date_modified; ?></strong>
    	  </td>
    	  <td class="right">
    	  <?php echo $apftxt_greater_than_or_equal; ?>
    	  <br />
    	  <span class="help"><?php echo $apftxt_leave_empty_to_ignore; ?></span>
    	  </td>
    	  <td class="left">
    	  <input class="datetime" size="14" type="text" value="<?php echo $date_modified_mmarese; ?>" name="date_modified_mmarese">
    	  </td>
    	  <td class="right">
    	  <?php echo $apftxt_less_than_or_equal; ?>
    	  <br />
    	  <span class="help"><?php echo $apftxt_leave_empty_to_ignore; ?></span>
    	  </td>
    	  <td class="left">
    	  <input class="datetime" size="14" type="text" value="<?php echo $date_modified_mmicse; ?>" name="date_modified_mmicse">
    	  </td>
    	</tr>

    	<tr>
    	  <td class="left">
    	  <strong><?php echo $apftxt_status; ?></strong>
    	  </td>
    	  <td colspan="4" class="left">
    	  <select name="prod_status">
    	  <option value="any"<?php if ($prod_status=='any') { echo ' selected="selected"'; } ?>><?php echo $apftxt_ignore_this; ?></option>
    	  <option value="1"<?php if ($prod_status=='1') { echo ' selected="selected"'; } ?>><?php echo $apftxt_enabled; ?></option>
    	  <option value="0"<?php if ($prod_status=='0') { echo ' selected="selected"'; } ?>><?php echo $apftxt_disabled; ?></option>
    	  </select>
    	  </td>
    	</tr>

    	<tr>
    	  <td class="left">
    	  <strong><?php echo $apftxt_store; ?></strong>
    	  </td>
    	  <td colspan="4" class="left">
    	  <select name="store_filter">
    	  <option value="any"<?php if ($store_filter=='any') { echo ' selected="selected"'; } ?>><?php echo $apftxt_ignore_this; ?></option>
    	  <option value="0"<?php if ($store_filter=='0') { echo ' selected="selected"'; } ?>><?php echo $apftxt_default; ?></option>
    	  <?php foreach ($stores as $store) { ?>
    	  <option value="<?php echo $store['store_id']; ?>"<?php if ($store['store_id']==$store_filter) { echo ' selected="selected"'; } ?>><?php echo $store['name']; ?></option>
    	  <?php } ?>
    	  </select>
    	  </td>
    	</tr>

    	<tr>
    	  <td class="left">
    	  <strong><?php echo $apftxt_with_attribute; ?></strong>
    	  </td>
    	  <td colspan="4" class="left">
    	  <select name="filter_attr">
    	  <option value="any"<?php if ($filter_attr=='any') { echo ' selected="selected"'; } ?>><?php echo $apftxt_ignore_this; ?></option>
    	  <?php foreach ($all_attributes as $attrib) { ?>
    	  <option value="<?php echo $attrib['attribute_id']; ?>"<?php if ($attrib['attribute_id']==$filter_attr) { echo ' selected="selected"'; } ?>><?php echo $attrib['attribute_group']." > ".$attrib['name']; ?></option>
    	  <?php } ?>
    	  </select>
    	  </td>
    	</tr>

    	<tr>
    	  <td class="left">
    	  <strong><?php echo $apftxt_with_attribute_value; ?></strong>
    	  <br />
    	  <span class="help"><?php echo $apftxt_leave_empty_to_ignore; ?></span>
    	  </td>
    	  <td colspan="4" class="left">
    	  <textarea name="filter_attr_val" cols="40" rows="2"><?php echo $filter_attr_val; ?></textarea>
    	  <span style="color:#666666;font-size:11px;">&nbsp;<?php echo $apftxt_with_attribute_value_help; ?></span>
    	  </td>
    	</tr>

    	<tr>
    	  <td class="left">
    	  <strong><?php echo $apftxt_with_this_option; ?></strong>
    	  </td>
    	  <td colspan="4" class="left">
    	  <select name="filter_opti">
    	  <option value="any"<?php if ($filter_opti=='any') { echo ' selected="selected"'; } ?>><?php echo $apftxt_ignore_this; ?></option>
    	  <?php foreach ($all_options as $option) { ?>
    	  <option value="<?php echo $option['option_id']; ?>"<?php if ($option['option_id']==$filter_opti) { echo ' selected="selected"'; } ?>><?php echo $option['name']; ?></option>
    	  <?php } ?>
    	  </select>
    	  </td>
    	</tr>

    	<tr>
    	  <td class="left">
    	  <strong><?php echo $apftxt_with_this_option_value; ?></strong>
    	  </td>
    	  <td colspan="4" class="left">
    	  <select name="filter_opti_val">
    	  <option value="any"<?php if ($filter_opti_val=='any') { echo ' selected="selected"'; } ?>><?php echo $apftxt_ignore_this; ?></option>
    	  <?php foreach ($all_optval as $optval) { ?>
    	  <option value="<?php echo $optval['option_value_id']; ?>"<?php if ($optval['option_value_id']==$filter_opti_val) { echo ' selected="selected"'; } ?>><?php echo $optval['o_name']." > ".$optval['ov_name']; ?></option>
    	  <?php } ?>
    	  </select>
    	  </td>
    	</tr>

    	<tr>
    	  <td colspan="5" class="center">
    	  
    	  <?php echo $apftxt_show_max_prod_per_pag1; ?>
    	  <input size="3" type="text" value="<?php echo $max_prod_per_pag; ?>" name="max_prod_per_pag">
    	  <?php echo $apftxt_show_max_prod_per_pag2; ?>
    	  
    	  &nbsp;&nbsp;&nbsp;
    	  
    	  <input type="submit" value="lista_prod" name="lista_prod" style="display:none;">
    	  
    	  <a onclick="$('input[name=\'lista_prod\']').click();" class="button"><span style="font-size:15px;font-weight:bold;padding-left:43px;padding-right:43px;"><?php echo $apftxt_button_filter_products; ?></span></a>
    	  
    	  <div style="float:right;">
          <input value="reset_filters" type="submit" name="reset_filters" style="display:none;" />
          <a onclick="$('input[name=\'reset_filters\']').click();" class="button"><span><?php echo $apftxt_button_reset_filters; ?></span></a>
          </div>

    	  </td>
    	</tr>

        </tbody>
        </table>    	

    </div>
  </div>
  

  
  <div class="box">
    
    <div class="heading">
      <h1><img src="view/image/product.png" alt="" /> <?php echo $apftxt_results_products; ?></h1>
      <div class="buttons">
        
        <a onclick="location = '<?php echo $insert; ?>'" class="button"><span><?php echo $apftxt_results_insert; ?></span></a>
        
        <input type="submit" value="copy" name="copy" style="display:none;" />
        <a onclick="$('input[name=\'copy\']').click();" class="button"><span><?php echo $apftxt_results_copy; ?></span></a>
    	  
    	<input type="submit" value="delete" name="delete" style="display:none;" />
    	<a onclick="$('input[name=\'delete\']').click();" class="button"><span><?php echo $apftxt_results_delete; ?></span></a>

      </div>
    
    </div>
    
    <div class="content">
    
    	  <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="center"><?php echo $apftxt_results_image; ?></td>
              <td class="left"><?php if ($sort == 'pd.name') { ?>
                <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $apftxt_results_product_name; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_name; ?>"><?php echo $apftxt_results_product_name; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'p.model') { ?>
                <a href="<?php echo $sort_model; ?>" class="<?php echo strtolower($order); ?>"><?php echo $apftxt_results_model; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_model; ?>"><?php echo $apftxt_results_model; ?></a>
                <?php } ?></td>
              <td class="right"><?php if ($sort == 'p.price') { ?>
                <a href="<?php echo $sort_price; ?>" class="<?php echo strtolower($order); ?>"><?php echo $apftxt_results_base_price; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_price; ?>"><?php echo $apftxt_results_base_price; ?></a>
                <?php } ?></td>
              <td class="right"><?php if ($sort == 'p.quantity') { ?>
                <a href="<?php echo $sort_quantity; ?>" class="<?php echo strtolower($order); ?>"><?php echo $apftxt_results_quantity; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_quantity; ?>"><?php echo $apftxt_results_quantity; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'p.status') { ?>
                <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $apftxt_results_status; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_status; ?>"><?php echo $apftxt_results_status; ?></a>
                <?php } ?></td>
              <td class="right"><?php if ($sort == 'p.product_id') { ?>
                <a href="<?php echo $sort_product_id; ?>" class="<?php echo strtolower($order); ?>"><?php echo $apftxt_results_product_id; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_product_id; ?>"><?php echo $apftxt_results_product_id; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'p.date_added') { ?>
                <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $apftxt_results_date_added; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_date_added; ?>"><?php echo $apftxt_results_date_added; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'p.date_modified') { ?>
                <a href="<?php echo $sort_date_modified; ?>" class="<?php echo strtolower($order); ?>"><?php echo $apftxt_results_date_modified; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_date_modified; ?>"><?php echo $apftxt_results_date_modified; ?></a>
                <?php } ?></td>
              <td class="right"><?php if ($sort == 'p.viewed') { ?>
                <a href="<?php echo $sort_viewed; ?>" class="<?php echo strtolower($order); ?>"><?php echo $apftxt_results_viewed; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_viewed; ?>"><?php echo $apftxt_results_viewed; ?></a>
                <?php } ?></td>
              <td class="right"><?php echo $apftxt_results_action; ?></td>
            </tr>
          </thead>
          <tbody class="products_to_upd">
          <?php if ($arr_lista_prod) { ?>
            <?php foreach ($arr_lista_prod as $product) { ?>
            <tr>
              <td style="text-align: center;"><input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" /></td>
              <td class="center"><img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" style="padding: 1px; border: 1px solid #DDDDDD;" /></td>
              <td class="left"><?php echo $product['name']; ?></td>
              <td class="left"><?php echo $product['model']; ?></td>
              <td class="right"><?php echo $product['price']; ?></td>
              <td class="right"><?php if ($product['quantity'] <= 0) { ?>
                <span style="color: #FF0000;"><?php echo $product['quantity']; ?></span>
                <?php } elseif ($product['quantity'] <= 5) { ?>
                <span style="color: #FFA500;"><?php echo $product['quantity']; ?></span>
                <?php } else { ?>
                <span style="color: #008000;"><?php echo $product['quantity']; ?></span>
                <?php } ?></td>
              <td class="left"><?php if ($product['status']==1) { ?>
                <span style="color: #008000;"><?php echo $apftxt_enabled; ?></span>
                <?php } else { ?>
                <span style="color: #FF0000;"><?php echo $apftxt_disabled; ?></span>
                <?php } ?></td>
              <td class="right"><?php echo $product['product_id']; ?></td>
              <td class="left"><?php echo $product['date_added']; ?></td>
              <td class="left"><?php echo $product['date_modified']; ?></td>
              <td class="right"><?php echo $product['viewed']; ?></td>
              <td class="right">[ <a href="<?php echo $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'] . '&advprodfilter=1', 'SSL'); ?>"><?php echo $apftxt_results_edit; ?></a> ]</td>
            </tr>

            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="12"><?php echo $apftxt_results_no_results; ?></td>
            </tr>
            <?php } ?>
        </tbody>
        </table>
        
        <div class="pagination"><?php echo $pagination; ?></div>
        

     <div style="width:100%;text-align:right;margin-top:30px;">
     <a href="http://opencart-market.com" target="_blank">www.opencart-market.com</a>
     </div>

    </div>
    
  </div>
  </form>
  
</div>


<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
<script type="text/javascript"><!--
$('.date').datepicker({dateFormat: 'yy-mm-dd'});
$('.datetime').datetimepicker({
	dateFormat: 'yy-mm-dd',
	timeFormat: 'h:m'
});
//--></script> 



<?php echo $footer; ?>
