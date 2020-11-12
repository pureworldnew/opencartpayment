<?php
// Heading
$_['heading_title']   		 = 'UKSB Google Merchant v3.8.0';

// Text   
$_['text_feed']      	 	 = 'Product Feeds';
$_['text_success']    	 	 = 'Success: You have modified the UKSB Google Merchant feed!';
$_['text_model']			 = 'Model field';
$_['text_location']			 = 'Location field';
$_['text_gtin']				 = '*new GTIN field';
$_['text_mpn']				 = '*new MPN field';
$_['text_req_mpn']			 = 'MPN: ';
$_['text_req_gtin']			 = 'GTIN: ';
$_['text_req_brand']		 = 'Brand: ';
$_['text_condition_new']	 = 'New';
$_['text_condition_used']	 = 'Used';
$_['text_condition_ref']	 = 'Refurbished';
$_['text_upc']				 = 'UPC field';
$_['text_sku']				 = 'SKU field';
$_['text_price_inc_tax']	 = 'Prices Include Tax / VAT';
$_['text_price_exc_tax']	 = 'Prices Exclude Tax / VAT';

// Entry
$_['tab_general_settings']	 =	'General Settings';
$_['tab_google_settings']	 =	'Google Merchant Settings';
$_['tab_google_feeds']	 =	'Google Merchant Feeds';
$_['tab_bing_feeds']	 =	'Bing US';
$_['tab_ciao_feeds']	 =	'Ciao UK';
$_['tab_thefind_feeds']	 =	'The Find';
$_['tab_pricegrabber_yahoo_feeds']	 =	'PriceGrabber / Yahoo';
$_['tab_nextag_feeds']	 =	'Nextag';
$_['entry_variant_section']	 =	'Clothing &amp; Apparel and Variant Products';
$_['entry_adwords_section']	 =	'Google Adwords Extension Attribtes';
$_['entry_status']    	 	 = 'Status:';
$_['entry_google_category']  = 'Default Google Product Category:';
$_['entry_choose_google_category']  = 'Click Here to choose your default Google Product Category';
$_['entry_choose_google_category_xml']  = 'Click the green \'+\' icon to choose your Google Product Category for each Google Site you wish to list on.<br /><br />After selecting your Google Product Category, copy the value shown in the XML box above the list of categories on the Google page to the appropriate field here.';
$_['entry_mpn']  			 = 'Manufacturer\'s Part Number:';
$_['entry_condition']  		 = 'Condition:';
$_['entry_history_days']  		 = 'History Days:';
$_['entry_gtin']  			 = 'EAN or UPC or ISBN Number:';
$_['entry_required_attributes'] = 'Required Attributes:';
$_['entry_characters']  	 = 'Remove Non-English Characters:';
$_['entry_split'] 			 = 'Split Feed:';
$_['entry_fullpath'] 		 = 'Full Product Path:';
$_['entry_site'] 			 = 'Google Shopping Site:';
$_['entry_info']  			 = 'Information:';
$_['entry_data_feed']   	 = 'Data Feed Url:';
$_['entry_xml_file']   	 = 'XML file Url:';

// Help
$_['help_google_category']	 = 'By default this is set to none. However, it is recommended you choose here the Google Product Category that best fits the majority of your products.<br /><br />This can be overridden in the new Google Shopping tab when editing a product.<br /><br />You should also choose wether to submit prices Inclusive of Tax / VAT or Exclusive of Tax / VAT';
$_['help_brand']			 = 'By default this is set to use the Manufacturer/Brand you assign via the links tab when editing a product.<br /><br />However, you can choose to ONLY use the *new Brand field added by this extension in the new Google Shopping tab when editing a product, if your OC Manufacturers are not specific enough.';
$_['help_mpn']				 = 'By default this is set to use the Model field from the product data tab when editing a product.<br /><br />However, you can choose to use the *new MPN field (recommended) added by this extension in the new Google Shopping tab when editing a product, if your store is new or has few or zero products.';
$_['help_condition']		 = 'You can choose the default condition of your products here.<br /><br />This can be overridden in the new Google Shopping tab when editing a product.';
$_['help_history_days']		 = 'You can choose the number of days for the purchased product here.<br /><br />By Default this would be set to 60 days.';
$_['help_gtin']				 = 'By default this is set to use the UPC field in 1.5.x or the Location field in 1.4.x from the product data tab when editing a product.<br /><br />However, you can choose to use the *new GTIN field (recommended) added by this extension in the new Google Shopping tab when editing a product, if your store is new or has few or zero products.';
$_['help_required_attributes']		 = 'Selecting any of these will check that all products have a value for the selected attributes.<br /><br />Products will be omitted from the feed if any of the checked values are blank.';
$_['help_characters']		 = 'Setting this option to Enabled will attempt to fix any XML errors caused by non standard or incorrectly encoded characters by removing them.';
$_['help_split']			 = 'If your server is timing out or runs out of memory due to your store having a lot of products, you can choose to split your feed into multiple feeds containing the number of products you set here';
$_['help_fullpath']			 = 'Enabling this will display the full path link to your product including it\'s categories.<br /><br />Only works if you assigned your product to one category or subcategory.';
$_['help_split_help']	     = 'Please Save the feed settings, then edit the feed again to see your new Data Feed Url\'s';
$_['help_site']		  		 = 'You can choose to list on multiple Google Shopping Sites by choosing the Google Shopping site here.<br /><br />PLEASE NOTE - You must have the correct currency and language installed in OC and live on your store for each site you wish to list on.<br /><br />After choosing a site, the Data Feed URL will change to suit.';
$_['help_info']				 = 'View our Video Tutorial for info on how best to set the feed settings for your store and products at <a onclick="window.open(\'http://www.opencart-extensions.co.uk/videos/uksb_google_merchant/\',\'tutorial\');" title="View Video Tutorial">http://www.opencart-extensions.co.uk/videos/uksb_google_merchant/</a><br /><br />This Extension is brought to you by <a onclick="window.open(\'http://www.uksitebuilder.net\',\'uksb\');" title="Web Design, E-Commerce Solutions and Application Deveopment">UK Site Builder Ltd</a>.<br />For more great OpenCart extensions, please visit <a onclick="window.open(\'http://www.opencart-extensions.co.uk\',\'extensions\');" title="Sign Up to our Newsletter to get notified of Updates">http://www.opencart-extensions.co.uk</a>.';

// Error
$_['error_permission'] 		 = 'Warning: You do not have permission to modify the UKSB Google Merchant feed!';
$_['error_duplicate'] 		 = 'Warning: You cannot have the same fields for both (Manufacturer\'s Part Number) and (EAN or UPC or ISBN Number)!';

// Google Merchant Edit Product
$_['tab_google']			 = 'Google Shopping';
$_['help_adwords'] = 'This section allows you to connect your Google Shopping products with your Google Adwords Ads.<br /><br />See <a href="http://support.google.com/merchants/bin/answer.py?hl=en-GB&answer=188479">http://support.google.com/merchants/bin/answer.py?hl=en-GB&answer=188479</a> for details.';
$_['help_variants'] 		 = 'Use the fields below to enter details of your variant products or if the product is clothing & apparel<br /><br />You must enter each variation of the product as if it were a single product when entering product variants.<br /><br />Example - A pair of Jeans in blue 30, blue 32, blue 34 and black 32, black 34<br /><br />You would enter:<br />Colours: blue,blue,blue,black,black<br />Sizes: 30,32,34,32,34<br />Materials: Denim,Denim,Denim,Denim,Denim<br />Patterns: Plain,Plain,Plain,Plain,Plain<br />Price Differences: -10,-5,+0,+5,+10';
$_['entry_ongoogle']		 = 'List on Google Shopping:<br/><span class="help">You can stop individual products from being listed on Google Shopping with this setting.</span>';
$_['entry_pcondition']		 = 'Condition:<br/><span class="help">You can override the default condition for this product here.</span>';
$_['entry_pbrand']			 = 'Brand:<br/><span class="help">You can override the Brand chosen in the Links tab here if you wish to be more specific.</span>';
$_['entry_pmpn']			 = 'MPN:<br/><span class="help">Manufacturer\'s Part Number.<br />This value will be ignored if using Product Variants (see below).</span>';
$_['entry_vmpn']			 = 'MPNs:<br/><span class="help">Manufacturer\'s Part Numbers for each product variant, separated by commas.</span>';
$_['entry_pgtin']			 = 'GTIN:<br/><span class="help">EAN, UPC or ISBN Number.<br />This value will be ignored if using Product Variants (see below).</span>';
$_['entry_vgtin']			 = 'GTINs:<br/><span class="help">EAN, UPC or ISBN Numbers for each product variant, separated by commas.</span>';
$_['entry_vprices']			 = 'Price Differences:<br/><span class="help">Price Additions or Subtractions to the default price.</span>';
$_['entry_pgoogle_category'] = 'Google Product Category:';
$_['link_google_category']	 = 'Click Here to choose your Google Product Category';
$_['help_pgoogle_category']	 = 'This will override the default Google Product Category set in the Product Feed settings';
$_['entry_pgender']			 = 'Gender:';
$_['entry_page_group']		 = 'Age Group:';
$_['entry_pcolour']			 = 'Colours:<br/><span class="help">Colours for each product variant, separated by commas.</span>';
$_['entry_psize']			 = 'Sizes:<br/><span class="help">Sizes for each product variant, separated by commas.</span>';
$_['entry_pmaterial']		 = 'Materials:<br/><span class="help">Materials for each product variant, separated by commas.</span>';
$_['entry_ppattern']		 = 'Patterns:<br/><span class="help">Patterns for each product variant, separated by commas.</span>';
$_['entry_padwords_publish']	 = 'Adwords Publish:<br/><span class="help">Select \'No\' to exclude this product from Product Ads</span>';
$_['entry_padwords_grouping']	 = 'Adwords Grouping:<br/><span class="help">Used to group products in an arbitrary way.<br />It can be used for Product Filters to limit a campaign to a group of products or Product Targets, to bid differently for a group of products.</span>';
$_['entry_padwords_labels']		 = 'Adwords Labels:<br/><span class="help">Very similar to Adwords Grouping, but it will only work on CPC.<br />It can hold multiple comma separated values, allowing a product to be tagged with multiple labels.</span>';
$_['entry_padwords_redirect']	 = 'Adwords Redirect:<br/><span class="help">Allows you to override the product URL when the product is shown within the context of Product Ads.<br />This allows you to track different sources of traffic separately from Google Shopping.</span>';
$_['entry_padwords_queryparam']	 = 'Adwords Query Parameter:<br/><span class="help">This attribute works in a similar fashion to Adwords Redirect, but instead of overriding the product URL, it will append the value to it at the end.</span>';

$_['text_male']				 = 'Male';
$_['text_female']			 = 'Female';
$_['text_unisex']			 = 'Unisex';
$_['text_adult']			 = 'Adult';
$_['text_kids']				 = 'Kids';

$_['warning_mpn_model']		 = 'UKSB Google Merchant Feed Settings are currently set to use the Model field on the Data tab for MPN';
$_['warning_mpn_location']	 = 'UKSB Google Merchant Feed Settings are currently set to use the Location field on the Data tab for MPN';
$_['warning_mpn_sku']	 	 = 'UKSB Google Merchant Feed Settings are currently set to use the SKU field on the Data tab for MPN';
$_['warning_gtin_upc']		 = 'UKSB Google Merchant Feed Settings are currently set to use the UPC field on the Data tab for GTIN';
$_['warning_gtin_location']	 = 'UKSB Google Merchant Feed Settings are currently set to use the Location field on the Data tab for GTIN';
$_['warning_gtin_sku']	 	 = 'UKSB Google Merchant Feed Settings are currently set to use the SKU field on the Data tab for GTIN';
?>