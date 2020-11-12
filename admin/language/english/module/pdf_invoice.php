<?php
// Heading
$_['heading_title']				= '<img src="view/pdf_invoice_pro/img/icon.png" style="vertical-align:top;padding-right:4px"/>PDF Invoice <span>Pro</span>';

// Text
$_['text_image_manager']	= 'Image Manager';
$_['text_browse']				= 'Browse';
$_['text_clear']					= 'Clear';
$_['text_module']				= 'Modules';
$_['text_success']         		= 'Success: You have modified module PDF Invoice Pro!';
$_['text_all'] = 'All';
$_['text_invoice'] = 'Invoice';
$_['text_packingslip'] = 'Packing Slip';

// Entry
$_['entry_logo']							= 'Header logo :';
$_['entry_mail']							= 'Invoice with mail :<span class="help">Attach the pdf invoice in the order confirmation mail</span>';
$_['entry_admincopy']				= 'Copy for admin:<span class="help">Attach a copy of the pdf invoice in admin email<br/>Don\'t forget to enable alert mail in store settings to use this</span>';
$_['entry_mailcopy']					= 'Same email for admin:<span class="help">Send to admin the same mail than the customer\'s instead of the basic summary</span>';
$_['text_order_language']			= '- Same as order -';
$_['entry_adminlanguage']			= 'Language for admin:<span class="help">Define a specific language for pdf files generated in backoffice or sent to admin.</span>';
$_['entry_forcelanguage']			= 'Force invoice language:<span class="help">Some values are inserted in database during order confirmation, so it cannot be translated on-the-fly. Use this option to impose the invoice in the language of your choice.</span>';
$_['entry_display_mode']			= 'Display mode:<span class="help">How to display pdf file, default is force download, can be changed to open in browser (behavior may be different depending your browser configuration), or html display is only for debug purpose</span>';
$_['entry_watermark']			    = 'Watermark:<span class="help">Display an image in pdf background, full page size is approximately 1300x1800px</span>';
$_['text_display_dl']			    = 'Force download';
$_['text_display_view']			  = 'Open in new tab';
$_['text_display_html']			  = 'Plain HTML (debug)';
$_['entry_invoiced_only']			= 'Only with invoice number:<span class="help">If enabled the pdf file is available for download only once the invoice number has been generated</span>';
$_['entry_auto_generate']			= 'Auto invoice number:<span class="help">Generate the invoice number on-the-fly on order confirmation</span>';
$_['entry_manual_inv_no']			= 'Manual invoice number:<span class="help">Allow to edit or insert manually the invoice number in order edition</span>';
$_['entry_auto_notify']				= 'Attach pdf on status:<span class="help">Automatically attach pdf file to the mail sent on order status update</span>';
$_['entry_return_pdf']				= 'Attach pdf on return:<span class="help">Automatically attach pdf file to the mail sent on return status update</span>';
$_['entry_custom_fields']				= 'Display custom fields:<span class="help">Choose which custom fields to display in customer information on the invoice</span>';
$_['entry_custom_fields_empty']	= 'No custom fields defined, configure them in Sales > Customers > Custom Fields (for example if you want to add customer VAT ID)';
$_['entry_customer_id']				= 'Display customer ID:<span class="help">Show customer ID number</span>';
$_['entry_user_comment']				= 'Display customer comment:<span class="help">Warning: displaying comment in invoice may be used with bad intentions by the customer, anything entered there is considered as legal, we don\'t recommend to activate this</span>';
$_['entry_user_comment_slip']				= 'Display customer comment:';
$_['entry_slip_summary']				= 'Packing summary:<span class="help">Dispaly total item quantity and total weight</span>';
$_['entry_customer_format']		= 'Customer ID format:<span class="help">Set a prefix and the number of decimals you want to show<br/>Ex: CUST-00024<br />- <b>prefix</b>: CUST-<br />- <b>size</b>: 5</span>';
$_['entry_customer_prefix']		= 'Prefix:';
$_['entry_customer_size']			= 'Number size:';
$_['entry_tax']							= 'Display total with tax:<span class="help">Include or exclude tax in total column</span>';
$_['entry_icon']							= 'Icon :<span class="help">Choose the icon displayed in customer account > order history</span>';
$_['entry_size']							= 'Paper format:';
$_['entry_filename']					= 'Filename config:<span class="help">Choose what look like the output filename (a dash is automatically placed between values)</span>';
$_['entry_vat_number']				= 'Store VAT ID:<span class="help">Enter here your store VAT number</span>';
$_['entry_company_id']				= 'Store Company ID:<span class="help">Enter here your store company ID</span>';
$_['entry_duedate']					= 'Due date delay:<span class="help">Display due date on invoice<br />- <b>empty</b>: disabled <br />- <b>0</b>: order date<br />- <b>10</b>: 10 days from order date</span>';
$_['entry_duedate_invoice']	= 'Auto set due date:<span class="help">Set the due date to the date when invoice number is generated</span>';
$_['entry_customer_vat']			= 'Customer Company / VAT:<span class="help">Enable or disable the display of company ID field and VAT ID field for customers</span>';
$_['entry_template']					= 'Template:<span class="help">Click on the preview for full size</span>';
$_['entry_colors']						= 'Colors:<span class="help">Clear a color with the cross to reset it to default value</span>';
$_['entry_color_text']					= 'Main text';
$_['entry_color_title']					= 'Title';
$_['entry_color_thead']				= 'Table head bg';
$_['entry_color_theadtxt']			= 'Table head text';
$_['entry_color_tborder']			= 'Table borders';
$_['entry_color_footertxt']			= 'Footer text';
$_['entry_columns']					= 'Columns:<span class="help">Show or hide columns of your choice<br/><br />Total column cannot be moved and stay last column, you can display it without tax by changing the option in "invoice options" tab.<br /><br />Tax rate is calculated on the price, it means if a products have more than one tax it will give the global rate</span>';
$_['entry_col_image']					= 'Product image';
$_['entry_col_product']				= 'Product name';
$_['entry_col_product_id']	= 'Product ID';
$_['entry_col_model']				= 'Model';
$_['entry_col_description'] = 'Description';
$_['entry_col_mpn']					= 'MPN';
$_['entry_col_isbn']				= 'ISBN';
$_['entry_col_location']		= 'Location';
$_['entry_col_sku']					= 'SKU';
$_['entry_col_upc']					= 'UPC';
$_['entry_col_ean']					= 'EAN';
$_['entry_col_manufacturer']	= 'Manufacturer';
$_['entry_col_weight']				= 'Weight';
$_['entry_col_quantity']			= 'Quantity';
$_['entry_col_slip_qty']			= 'Quantity ordered';
$_['entry_col_qty_check']			= 'Quantity check';
$_['entry_col_qty_shipped']			= 'Quantity Shipped';
$_['entry_col_expected']			= 'Expected';
$_['entry_col_price']					= 'Unit price (ex. tax)';
$_['entry_col_price_tax']			= 'Unit price';
$_['entry_col_tax']						= 'Tax (unit)';
$_['entry_col_tax_total']				= 'Tax (total)';
$_['entry_col_tax_rate']				= 'Tax rate';
$_['entry_col_total']					= 'Total (ex. tax)';
$_['entry_col_total_tax']				= 'Total';
$_['entry_totals_tax']					= 'Tax in totals:<span class="help">Apply tax on subtotal, shipping, etc. if you want to display full tax invoice</span>';
$_['entry_product_options']		= 'Product name options:<span class="help">Display these values directly in product name column instead of specific column</span>';
$_['entry_thumbsize']					= 'Product image size:';
$_['entry_col_barcode']		    = 'Barcode';
$_['entry_barcode_column']		= 'Column barcode:';
$_['entry_barcode']					  = 'Barcode/QRcode:';
$_['entry_barcode_type']	  = 'Type:';
$_['entry_barcode_value']	= 'Value:';
$_['text_barcode_order_id']	= 'Order ID';
$_['text_barcode_invoice_no']	= 'Invoice No (alone)';
$_['text_barcode_full_invoice_no']	= 'Full Invoice No (with prefix)';
$_['text_barcode_customer_id']	= 'Customer ID';
$_['text_barcode_order_url']	= 'Order URL';
$_['entry_custom_comp_display']= 'Enable company ID field';
$_['entry_custom_comp_required']= 'Company ID field required';
$_['entry_custom_tax_display']	= 'Enable tax ID field';
$_['entry_custom_tax_required']	= 'Tax ID field required';
$_['entry_required']					= 'Required';
$_['entry_customer_vat_btn']		= 'Click to manage display settings for each customer groups';
$_['entry_filename_prefix_text']	= 'Filename prefix:<span class="help">Choose the prefix of the generated file to download</span>';
$_['entry_footer']						= 'Footer:<span class="help">Display some text at bottom of your invoice<br/>Use {page_number} tag to display page number</span>';

$_['entry_packingslip']				= 'Packing slip:<span class="help">Send a packing slip to admin with order confirm email</span>';

$_['entry_backup']						= 'Backup pdf invoices:<span class="help">Save all invoices in a folder</span>';
$_['entry_backup_on']				= 'Backup on:<span class="help">When to generate the backup file ?</span>';
$_['entry_backup_onorder']		= 'Order confirm';
$_['entry_backup_oninvoice']		= 'Generated invoice number';
$_['entry_backup_onmanual']		= 'Manually by button';
$_['entry_backup_structure']		= 'Folder structure:<span class="help">Choose how you want to structure your backup directory</span>';
$_['entry_backup_folder']			= 'Backup folder:';
$_['entry_backup_browser']		= 'Backup browser';
$_['text_backup_folder_warning']= 'Important: if you use a custom folder, make sure to copy the file .htaccess from default backup folder for security reasons.';
$_['entry_filename_prefix']			= '[prefix]';
$_['entry_filename_invnum']		= '[invoice number]';
$_['entry_filename_ordnum']		= '[order number]';

$_['entry_block_title']						= 'Title:';
$_['entry_block_message']				= 'Message:';
$_['entry_block_position']				= 'Position:';
$_['entry_block_target']				= 'Target:';
$_['entry_block_display']				= 'Display:';
$_['entry_sort_order']					= 'Sort order:';
$_['text_top']									= 'Top';
$_['text_middle']							= 'Middle';
$_['text_bottom']							= 'Bottom';
$_['text_footer']							= 'Footer';
$_['text_newpage']						= 'New page';
$_['text_has_invnum']				= 'Has invoice number';
$_['text_no_invnum']				= 'Do not have invoice number';
$_['text_is_credit']				= 'Is credit invoice (negative total)';
$_['text_isnot_credit']				= 'Is not credit invoice (positive total)';
$_['entry_display_invnum']				= 'Invoice number';
$_['entry_display_credit']				= 'Credit invoice';
$_['entry_display_always']				= 'Always show';
$_['entry_display_comment']			= 'Order comment';
$_['entry_display_group']				= 'Customer group';
$_['entry_display_orderstatus']		= 'Order status';
$_['entry_display_payment']			= 'Payment method';
$_['entry_display_shipping']			= 'Shipping method';
$_['entry_display_payment_zone']	= 'Payment zone';
$_['entry_display_shipping_zone']	= 'Shipping zone';
$_['entry_display_qosu']	= 'Tracking (Quick status updater)';
$_['entry_display_qosu_tracking']	= 'Tracking number exists';

$_['entry_attach_pdf_invoice']	= 'Attach PDF invoice:';

$_['entry_structure_1']			= 'No subfolders';
$_['entry_structure_2']			= '/ Year';
$_['entry_structure_3']			= '/ Year / Month';
$_['entry_structure_4']			= '/ Year / Month / Day';

$_['text_manual_invoice_edit']	= 'Edit';
$_['text_manual_invoice_save']	= 'Save';
$_['text_none']					= '--- None ---';

$_['text_tab_1']			= 'Main settings';
$_['text_tab_2']			= 'Template';
$_['text_tab_3']			= 'Backup';
$_['text_tab_4']			= 'Custom blocks';
$_['text_tab_5']			= 'Packing slip';
$_['text_tab_6']			= 'Invoice options';
$_['text_tab_about']	= 'About';
$_['tab_block']			= 'Block';
$_['tab_add_block']	= 'Add block';

$_['text_pdf_date_invoice']	 = 'Invoice due date';
$_['text_pdf_edit']	 			 = 'click to edit';
$_['text_pdf_now']	 			 = 'Now';

$_['button_pdf_invoice']			= 'PDF invoice';
$_['button_packing_slip']		= 'PDF Packing slip';
$_['button_invoice_backup']	= 'Backup invoice';

$_['text_pdf_backup_done']	= 'Backup done for order #[order]';

// Error
$_['error_permission']			= 'Warning: You do not have permission to modify this module!';

// Info
$_['info_title_default']		= 'Help';
$_['info_msg_default']	= 'Help section for this topic not found';

$_['info_title_tags']	= 'Available tags';
$_['info_msg_tags']	= '
<div class="infotags">
<h5>PDF invoice Specific</h5>
<p>
<span><b class="tag">{total_words}</b> Order total in words</span>
</p>
<h5>Customer</h5>
<p>
<span><b class="tag">{customer_id}</b> Customer ID</span>
<span><b class="tag">{customer}</b> Full name</span>
<span><b class="tag">{firstname}</b> First name</span>
<span><b class="tag">{lastname}</b> Last name</span>
<span><b class="tag">{telephone}</b> Phone number</span>
<span><b class="tag">{email}</b> Email address</span>
<span><b class="tag">{custom_field.x}</b> Account custom field (x=id)</span>
</p>
<h5>Order</h5>
<p>
<span><b class="tag">{order_id}</b> Order ID</span>
<span><b class="tag">{order_url}</b> Order URL</span>
<span><b class="tag">{total}</b> Total amount</span>
<span><b class="tag">{invoice_no}</b> Invoice number</span>
<span><b class="tag">{invoice_prefix}</b> Invoice prefix</span>
<span><b class="tag">{comment}</b> Comment</span>
<span><b class="tag">{reward}</b> Reward</span>
<span><b class="tag">{commission}</b> Commission</span>
<span><b class="tag">{language_code}</b> Language code</span>
<span><b class="tag">{currency_code}</b> Currency code</span>
<span><b class="tag">{currency_value}</b> Currency value</span>
<span><b class="tag">{amazon_order_id}</b> Amazon order ID</span>
<span><b class="tag">{order_history_comment}</b> Last comment in history</span>
</p>
<h5>Payment</h5>
<p>
<span><b class="tag">{payment_firstname}</b> First name</span>
<span><b class="tag">{payment_lastname}</b> Last name</span>
<span><b class="tag">{payment_company}</b> Company</span>
<span><b class="tag">{payment_address_1}</b> Address 1</span>
<span><b class="tag">{payment_address_2}</b> Address 2</span>
<span><b class="tag">{payment_postcode}</b> Postcode</span>
<span><b class="tag">{payment_city}</b> City</span>
<span><b class="tag">{payment_zone}</b> Zone</span>
<span><b class="tag">{payment_country}</b> Country</span>
<span><b class="tag">{payment_method}</b> Method</span>
<span><b class="tag">{payment_custom_field.x}</b> Custom field (x=id)</span>
</p>
<h5>Shipping</h5>
<p>
<span><b class="tag">{shipping_firstname}</b> First name</span>
<span><b class="tag">{shipping_lastname}</b> Last name</span>
<span><b class="tag">{shipping_company}</b> Company</span>
<span><b class="tag">{shipping_address_1}</b> Address 1</span>
<span><b class="tag">{shipping_address_2}</b> Address 2</span>
<span><b class="tag">{shipping_postcode}</b> Postcode</span>
<span><b class="tag">{shipping_city}</b> City</span>
<span><b class="tag">{shipping_zone}</b> Zone</span>
<span><b class="tag">{shipping_country}</b> Country</span>
<span><b class="tag">{shipping_method}</b> Method</span>
<span><b class="tag">{shipping_custom_field.x}</b> Custom field (x=id)</span>
</p>
<h5>Misc</h5>
<p>
<span><b class="tag">{store_name}</b> Store name</span>
<span><b class="tag">{store_url}</b> Store URL</span>
<span><b class="tag">{ip}</b> User IP</span>
<span><b class="tag">{user_agent}</b> User agent</span>
</p>
<h5>Tracking (Quick order status updater)</h5>
<p>
<span><b class="tag">{tracking_no}</b> Tracking Number</span>
<span><b class="tag">{tracking_url}</b> Tracking URL</span>
<span><b class="tag">{tracking_link}</b> HTML link with tracking URL</span>
</p>
</div>';
?>