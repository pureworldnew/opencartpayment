<?php
// Heading
$_['heading_title']    				= 'Google Product Feed';
$_['heading_title_bulk']    		= 'Product Feed Bulk Update - Select Products';
$_['heading_title_bulk_update']    	= 'Google Product Feed - Bulk Update';

// Button
$_['button_slect'] 					= 'Select';
$_['button_copy_gtin'] 				= 'Copy';
$_['button_bulk_update'] 			= 'Bulk Update';
$_['button_view_feed'] 				= 'View Feed';
$_['button_enable_all_products'] 	= 'Enable All Products';
$_['button_update'] 				= 'Update Selected Products';
$_['button_cancel'] 				= 'Cancel';
$_['button_add_variant'] 			= 'Add Variant';
$_['button_remove'] 				= 'Remove';

// Alert
$_['alert_enable']        			= 'You are about to enable all products for Google Shopping';
$_['alert_copy_manufacturer']       = 'Please Select Yes if you want to copy Manufacturer Field!';
$_['alert_google_product_category']	= 'This will copy the default Google Product Category to all products that do not have a Google Product Category set';
$_['alert_gtin']        			= 'Please Select Field to Copy From!';


// Text
$_['text_feed']        				= 'Product Feeds';
$_['text_success']     				= 'Success: You have modified Google Product Feed!';
$_['text_success_bulk_update']     	= 'Success: Your products have been updated.!';
$_['text_true']     				= 'TRUE';
$_['text_false']     				= 'FALSE';
$_['text_clear_keyword']       		= '[Clear]'; // This is the Keyword to clear existing values from selected products
$_['text_clear']       				= 'Clear';
$_['text_ignore']      				= 'Ignore  ';
$_['text_product']        			= 'Product';
$_['text_products']        			= 'Products';
$_['text_instructions_1']      		= '<h1 style="color: #ff8000">How to use Bulk Update:</h1><span class="help"><ol>
	<li>The products you have selected are listed at the bottom of this page</li>
	<li>Complete any fields that are common to <u>all</u> 
	of the products you have selected</li>
	<li>Any fields that are left blank will be ignored by the update (ie any 
	existing data in those fields will remain)</li>
	<li>If you want to clear any fields that may have data, click the [-] icon or 
	type <strong>';
$_['text_instructions_2']      		= '</strong> in the field</li>
	<li>Click "<strong>Update Selected Products</strong>" to save your changes</li>
</ol>
</span>';
$_['text_general']        			= '<h1 style="color: #ff8000">General:</h1>';

// Column
$_['column_name']            		= 'Product Name';
$_['column_model']           		= 'Model';
$_['column_image']           		= 'Image';
$_['column_manufacturer']    		= 'Manufacturer';
$_['column_quantity']        		= 'Quantity';
$_['column_status']          		= 'Google Shopping';
$_['column_category']        		= 'Category';

// Entry
$_['entry_status']     = 'Status:';
$_['entry_enable_all_products']     = 'Enable All Products:<br /><span class="help">By default, none of your products are enabled for Google Shopping. Click the Copy button to enable all products for your product data feed.</span>';
$_['entry_data_feed']  = 'Data Feed Url:<br/><span class="help">This url points to your feed. Paste it into your feed server.</span>';
$_['entry_currency']   = 'Default Currency:<br/><span class="help">Select the currency you would like your feed to use.<br /><br />You must ensure that your default language is set to the correct language. <a href="http://support.google.com/merchants/bin/answer.py?hl=en&answer=160637" target="_blank">Click here</a> for more details.</span>';
$_['entry_language']   = 'Google Taxonomy:<br/><span class="help">Select the country where you would like to submit your feed. This will ensure you access the correct product categories list to choose from.<br /><br />You must ensure that your default language is set to the correct language. <a href="http://support.google.com/merchants/bin/answer.py?hl=en&answer=160637" target="_blank">Click here</a> for more details.</span>';
$_['entry_copy_gtin']   = 'Global Trade Item Number:<br/><span class="help">This is the UPC, EAN, JAN or ISBN number of the item. If you already have the GTIN data in another field, you can copy it across rather than have to re-input the information.
 Simply select the field to copy from and click the COPY button.</span>';
$_['entry_copy_mpn']   = 'Manufacturer Part Number:<br/><span class="help">If you already have the MPN data in another field, you can copy it across rather than have to re-input the information.
 Simply select the field to copy from and click the COPY button.</span>';
$_['entry_default_google_product_category'] = 'Default Google Product Category:<br/><span class="help">Click on the "+" icon to open a new window showing the Google categories. Copy and Paste the category which best describes the majority of your products.
 <br /><br />Click the Copy button to copy this value to every product in your catalog.
 <br /><br />This default value can be overridden in the <strong>Product Feed</strong> tab when editing individual products.
 <br /><br /><span style="color:rgb(255, 0, 0)"><strong>IMPORTANT: </STRONG>Make sure the default currency is correctly set before selecting the product category or your entire product feed could be rejected by Google!</span></span>';
$_['entry_shipping']  = 'Shipping:<br/><span class="help">Google require you to provide shipping costs. This can either be included in your feed or set up in your Google Merchant Account. Select your preferred method.</span>';
$_['entry_copy_manufacturer'] = 'Brand:<br/><span class="help">Would you like to copy data from the Manufacturer field?';
$_['entry_condition']          	 = 'Default Condition:<br/><span class="help">Google only allows 3 options for condition of your products<br /><br />This setting can be overriden in the <strong>Product Feed</strong> tab when editing individual products.</span>';
$_['entry_oos_status']     = 'Out of Stock Status:<br/><span class="help">Please select the status you would like to be shown in your product feed when there is no stock.</span>';
$_['entry_identifier_exists']     = 'Identifier Exists:<br/><span class="help">Please select the status you would like to be shown for your products.</span>';
$_['entry_information'] = '<h1 style="color: #ff8000">Other information:</h1><span class="help">Google are very specific about their requirements for product feeds and it is extremely important that you adhere to their specifications otherwise your whole feed may be rejected.<br />
<br />It is strongly recommended that you familiarise yourself with Googles\' policies on Product Feeds before deciding which fields to complete.<br />
<br />Check out the Google Products Specification <a href="http://support.google.com/merchants/bin/answer.py?hl=en&answer=188494#GB" target="_blank">here</a><br />
<br />There is a useful Summary of Attribute Requirements <a href="http://support.google.com/merchants/bin/answer.py?hl=en&answer=1344057&topic=2473824&ctx=topic#GB" target="_blank">here</a><br />
<br />This <a href="http://support.google.com/merchants/bin/answer.py?hl=en&answer=160161&topic=2473824&ctx=topic" target="_blank">link</a> explains the different Unique Product Identifiers available (Global Trade Item Numbers and Manufacturers Part Numbers)<br />
<br />Clothing (Apparel) has a particular specification and this is summarised <a href="http://support.google.com/merchants/bin/answer.py?hl=en&answer=1347943&topic=2473824&ctx=topic" target="_blank">here</a>.</span>';
$_['entry_troubleshooting'] = '<h1 style="color: #ff8000">Troubleshooting:</h1><span class="help">Click on the links below for the solution to the most common problems encountered</span><ul>
<li><a href="http://www.showmeademo.co.uk/help/troubleshooting.htm#q3" target="_blank">I am getting an error message "Undefined Index: gpf_status"</a></li><li><a href="http://www.showmeademo.co.uk/help/troubleshooting.htm#q1" target="_blank">I have 450 products, but only 27 have uploaded to Google Merchant Center</a></li><li><a href="http://www.showmeademo.co.uk/help/troubleshooting.htm#q2" target="_blank">My feed has uploaded, but I\'m getting the error "0 of 22 items inserted"</a></li></ul>';
$_['entry_clear_variants'] = 'Product Variants:<br/><span class="help">How would you like this module to deal with an empty Product Variants set?<br /><br />Select <strong>Ignore</strong> to leave any existing product variants in place<br />Select <strong>Clear</strong> to delete existing product variants in the selected products';

// Bulk Updating Products
$_['entry_google_product_category'] = 'Google Product Category:<br/><span class="help">Click on the "+" icon to open a new window showing the Google categories. Select the category which best describes the selected products, and copy and paste the information into this box.';
$_['entry_brand']       = 'Brand:';
$_['entry_gtin']        = 'Global Trade Item Number:<br /><span class="help">Enter the UPC, EAN, ISBN or JAN number here.</span>';
$_['entry_mpn']         = 'Manufacturer Part Number:';
$_['entry_list']        = 'List on Google Shopping:<br /><span class="help">Select <strong>No</strong> if you don\'t want these items included in your product feed.</span>';
$_['entry_gender']      = 'Gender:';
$_['entry_agegroup']    = 'Age Group:';
$_['entry_colour']      = 'Colour:<br /><span class="help">Leave this blank if the colour attribute is not relevant for these items or if you are completing the Product Variants section below.</span>';
$_['entry_size']        = 'Size:<br /><span class="help">Leave this blank if the size attribute is not relevant for these items or if you are completing the Product Variants section below.</span>';
$_['entry_condition']   = 'Condition:<br /><span class="help">You can specify the condition of these products here.</span>';
$_['entry_variant']   	= '<h1 style="color: #ff8000">Product Variants:</h1><span class="help">Enter all combinations of variations here. For example if there are three different colours (red, green, blue) each with three different sizes (S, M, L) and two different patterns (stripes, circles) there would be 3 x 3 x 2 entries (18) ie.<br /><br />Small  Red  Circle<br />Medium  Red  Circle<br />Large  Red  Circle<br />Small Red Stripes<br />etc...<br /><br />Leave blank any attributes that are not relevant.</span>';
$_['entry_products']     = 'Selected Products: ';
$_['entry_adwords']     = '<h1 style="color: #ff8000">Google Adwords:</h1><span class="help">Use this section only if you are subscribing to Google Adwords Product Ad Listings. Find out more <a href="http://support.google.com/adwords/answer/2454022" target="_blank">here</a>.</span>';
$_['entry_adwords_grouping']     = 'Adwords Grouping:';
$_['entry_adwords_labels']     = 'Adwords Labels:<br /><span class="help">Up to 10 Adwords Labels are allowed. Seperate your labels using a comma eg. <br /><span style="font-family: courier new; color: blue;">Clothing, Tops, T Shirts</span><br />If you enter more than the allowed number of labels, only the first 10 will included in the feed.</span>';
$_['entry_adwords_redirect']     = 'Adwords Redirect:<br/><span class="help">Select "<strong>Yes</strong>" to track website traffic coming from Google Shopping (only works if you are using Google Adwords Product Listing Ads).</span>';

// Error
$_['error_permission'] 			= 'Warning: You do not have permission to modify Google Product Feed!';
$_['error_none_selected'] 		= 'Warning: Please select at least one product!';



?>