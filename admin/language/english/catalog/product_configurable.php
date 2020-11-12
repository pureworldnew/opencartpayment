<?php
/*
  #file: admin/language/english/catalog/product_configurable.php
  #powered by fabiom7 - www.fabiom7.com - fabiome77@hotmail.it - copyright fabiom7 2012 - 2013 - 2014
*/

// Heading
$_['heading_title'] = 'Grouped Products';

// Tab
$_['tab_grouped']           = 'Grouped Products';
$_['tab_system_identifier'] = 'System Identifier';

// Column
$_['column_config_option_sort_order'] = 'Sort Order';
$_['column_config_option_type']       = 'Option Type <span class="required">*</span><br /><span class="help">c1,i1,r1,s1,n1</span>';
$_['column_config_option_required']   = 'Option Required';
$_['column_config_option_quantity']   = 'Option Quantity<br /><span class="help">Minimum or Range Allowed.</span>';
$_['column_config_option_hide_qty']   = 'Hide Quantity box<br /><span class="help" style="color:red;">Don\'t use 0 as Min Qty</span>';
$_['column_config_option_label']      = 'Option Label<br /><span class="help">Doesn\'t work with option type "n".</span>';
$_['column_child_image']              = 'Image';
$_['column_child_name']               = 'Name';
$_['column_child_model']              = 'Model';
$_['column_child_price']              = 'Price';
$_['column_child_info']               = 'More Informations';
$_['column_child_visibility']         = 'Individually';
$_['column_child_nocart']             = 'Disable "AddToCart"';

// Text
$_['text_success']                = 'Success: You have modified Grouped Products!';
$_['text_visible']                = 'Visible';
$_['text_invisible_searchable']   = 'Not Visible, Searchable';
$_['text_invisible']              = 'Not Visible';
$_['text_product_with_options']   = '<br /><span class="help">(!) Product with options</span>';
$_['text_auto_identifier_system'] = '<br /><span class="help">(Auto identifier for system)</span>';
$_['text_yes_add_no_thanks']      = 'Yes, add No thanks (r,s)';
$_['text_price_start']            = 'Starting:';
$_['text_price_from']             = 'From:';
$_['text_price_to']               = 'To:';
$_['text_price_fixed']            = 'Fixed:';

// Entry
$_['entry_name']                  = 'Product Name:<br /><span class="help">The same part of child-product name will be replaced.</span>';
$_['entry_tax_class']             = 'Tax Class:<br /><span class="help">Doesn\'t overwrite the product tax. It will be used to display prices (starting, from, to), and for taxes recalculation after discount.</span>';
$_['entry_pg_layout']             = 'Configurable Layout:';
$_['entry_tag_title']             = 'Tag Title (max 99 chars):<br /><span class="help">SEO optimize. If empty will be the product name.</span>';
$_['entry_price']                 = 'Price:';
$_['entry_price_from']            = 'P-FROM: Auto if empty and option required.';
$_['entry_price_to']              = 'P-TO: Auto if empty.';
$_['entry_price_fixed']           = 'The price of all child-product is ignored.';
$_['entry_group_discount']        = 'Discount:<br /><span class="help">Buying all options(Checkbox ignored). Make sure not to have options without child-products!</span>';
$_['entry_weight'] = 'Weight:<br /><span class="help">Enable progressbar and weight in product name. You can use only the same weight class for all child-products.</span>';

// Button
$_['button_save_continue']     = 'Save &amp; Continue';
$_['button_add_config_option'] = 'Add Option';

// Error
$_['error_permission']  = 'Warning: You do not have permission to modify Grouped Products!';
$_['error_warning']     = 'Warning: Please check the form carefully for errors!';
$_['error_name']        = 'Group Name must be between 2 and 32 characters!';
$_['error_option_type'] = 'Option Type is required! Please add again and don\'t forget it.';

// Help
$_['text_help_reward_point'] = 'Take care:<br />Points and prices division work exactly as default system.<br />Use 1 to enable. Use any other number to assign new points. Make several test before using it. It works only if ALL child-products in cart have points.<br />As example: if there are 5 child in the cart and only one of them is without points it doesn\'t work. Remove the product without points from cart, and it will work again.';

$_['text_help_config_option'] = '<br />
  <b style="color:#F00;">OPTION TYPE:</b><br />
  <b>c</b> = Checkbox,<br /><b>i</b> = Inline,<br /><b>r</b> = Radio,<br /> <b>s</b> = Select<br />
  For each option, after a letter digit also a number:<br />
  <div style="margin-left:10px;">
    You can put <b>c1</b>, <b>i1</b>, <b>r1</b>, <b>s1</b> for all products in the same option group.<br />
    You can put <b>c2</b>, <b>i2</b>, <b>r2</b>, <b>s2</b> for the next option group. (and <b>c3</b>, <b>i3</b>, <b>r3</b>, <b>s3</b>...)<br />
    <br />
    Example:<br />
    Products: A, B, C = <b style="color:#F00;">s1</b> (Select one)<br />
    Products: D, E, F = <b style="color:#F00;">s2</b> (Select two) ...<br />
    Products: G, H, I = <b style="color:#F00;">r1</b> (Radio one) ...<br />
    Products: J, K, L = <b style="color:#F00;">c1</b> (Checkbox one) ...<br />
	Products: M, N, O = <b style="color:#F00;">i1</b> (Inline one) ...<br />
  </div><br />
  <b>n</b> = Null<br />
  <div style="margin-left:10px;">
    This option accepts only one product. Make sure to use a different number for each "n":<br />
    Product X = <b style="color:#F00;">n1</b><br />
    Product Y = <b style="color:#F00;">n2</b><br />
    Product Z = <b style="color:#F00;">n3</b> ...<br />
  </div><br />
  <b style="color:#F00;">OPTION QUANTITY:</b><br />
  <div style="margin-left:10px;">
    You can put the minimun quantity, or range allowed. Example: <b>2</b> or <b>2:8</b>,<b>1:1</b>...<br />
    If you put the range allowed, dropdown men&ugrave; will be displayed.<br />
    <br />
    The default product minimum quantity will be ignored in any case, with or without option quantity.<br />
  </div><br />
  <b style="color:#F00;">OPTION REQUIRED and HIDDEN CHILD:</b><br />
  <div style="margin-left:10px;">
    The hidden child can\'t be included into the required option, if this option has only this product.<br />
	It is not possible to hide child-product into dropdown men&ugrave;.
  </div><br />
  <b style="color:#F00;">CHILD PRODUCT SORT ORDER:</b><br />
  <div style="margin-left:10px;">
    Click and move in desired position. You can not move the product from one option to another.
  </div><br />
  <b style="color:#F00;">DEFAULT CONFIGURATION OR SUGGESTED CONFIGURATIONS:</b><br />
  <div style="margin-left:10px;">
    If you want to help your customers by your professional experience, you can add one or more configuration of this product.<br />
    After setting all variants, and after saving this page, Go to store front and add to cart this configured product.<br />
    <b>Click the [ edit ] link (in shopping cart page) and copy the url.</b> Return in admin, and paste this url in product description tab.<br />
    <br />
    Example:<br />
    <b>Our suggestion for Home Computer:</b><br />
    &lt;a href="' . HTTP_CATALOG . 'index.php?route=product/product_configurable&product_id=<b style="color:#F00;">123&cset=AbCdEf12345==</b>"&gt; Home PC &lt;/a&gt;<br />
    <b>Our suggestion for Working Computer:</b><br />
    &lt;a href="' . HTTP_CATALOG . 'index.php?route=product/product_configurable&product_id=<b style="color:#F00;">456&cset=etc1ETC2eTc3EtC=</b>"&gt; Work PC &lt;/a&gt;<br />
    <b>Our suggestion for Gaming Computer:</b> <b style="color:#F00;">try yourself !!!</b>
  </div><br />
  <b style="color:#F00;">KNOWN ISSUES:</b><br />
  <div style="margin-left:10px;">
    Notice: Undefined index: (in store front::product page)<br />
    #1) name (and description,manufacturer,category_name) in */vqmod/vqcache/vq2-catalog_view_theme_default_template_common_header.tpl on line .. <br />
    - Solution: Assign the category to Grouped Product!<br />
	 <br />Remember to clear the error logs from admin :: System.
  </div><br />
';
?>