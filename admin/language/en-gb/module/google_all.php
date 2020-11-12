<?php

$extension_name = "Google Marketing Tools";
$extension_name_image = '<a href="https://devmanextensions.com/" target="_blank"><img src="'.HTTP_SERVER.'view/image/devmanextensions/devman_face.png"> DevmanExtensions.com</a> - '.$extension_name;

// Heading
$_['heading_title']    = $extension_name_image.' (V.11.6.4)';
$_['heading_title_2']    = $extension_name;

//Generals
    $_['text_module']       = 'Modules';
    $_['button_save']         = 'Save';
    $_['button_cancel']       = 'Cancel';
    $_['status']        = 'Status';
    $_['active']        = 'Enabled';
    $_['disabled']        = 'Disabled';
    $_['choose_store']      = 'Choose store';
    $_['text_success']      = 'Success: You have modified module '.$extension_name.'!<br><b><u>IMPORTANT:</u></b> If you change something in tab "Feeds", these changes didn\'t save, have to use feed "Configuration Management" zone.';
    $_['error_permission']      = 'Warning: You do not have permission to modify module '.$extension_name.'!';
    $_['apply_changes']     = 'Apply changes';
    $_['text_image_manager']     = 'Image Manager';
    $_['text_browse']            = 'Browse';
    $_['text_clear']             = 'Clear';
    $_['text_none_user'] = ' - None User - ';
    $_['text_validate_license'] = 'Validate your license';
    $_['text_license_id'] = 'Please, enter your Opencart order ID...';
    $_['text_send'] = 'Send';
    $_['choose_store'] = 'Choose store';
    $_['type']          = 'Type';
    $_['code']          = 'Code';
    $_['code_head']         = 'Code head';
    $_['code_body']         = 'Code body';
    $_['configuration']     = 'Configuration';
    $_['additional_information']= 'Additional Information';
    $_['text_none'] = ' - None -';
    $_['text_yes'] = 'Yes';
    $_['text_no'] = 'No';
    $_['only_these_products'] = 'Only these products';
    $_['only_these_products_help'] = 'The feed only will include these products and will ignore another filters.';

//Tabs
  $_['tab_help']    = 'Support';
  $_['tab_changelog']     = 'Changelog';
  $_['tab_faq']   = 'FAQ';


//Tab configuration
    $_['tab_configuration'] = 'General';
    $_['gmt_configuration'] = 'Google Marketing Tools configuration';

    $_['container_id']  = 'GTM Container ID';
    $_['container_id_help'] = '<b>REQUIRED</b>. Login in your Tag Manager Account and put here the complete GTM-XXXXXXX';

    $_['container_optimize_id']  = 'G. Optimize Container ID';
    $_['container_optimize_id_help'] = '<b>NOT REQUIRED</b>. Login in your Google Optimize account and put here the complete GTM-XXXXXXX';

    $_['negative_conversion'] = 'Negative transaction';
    $_['negative_conversion_send'] = 'Send negative conversion';
    $_['negative_conversion_help'] = 'Put here the order ID that you want register like negative conversion and press link, <b>RECOMMEDED</b> active and enable Google Tag Assistant plugin before send to check if your conversion was sent successful';
    $_['negative_conversion_success'] = 'Negative conversion sent sucessfull';

    $_['positive_conversion'] = 'Positive transaction';
    $_['positive_conversion_send'] = 'Send positive conversion';
    $_['positive_conversion_help'] = 'Put here the order ID that you want register like positive conversion and press link, <b>RECOMMEDED</b> active and enable Google Tag Assistant plugin before send to check if your conversion was sent successful';
    $_['positive_conversion_success'] = 'Positive conversion sent sucessfull';

    $_['conversion_error_empty_order_id'] = 'Put an order id';
    $_['conversion_error_empty_order'] = 'Order not found';

    $_['positive_conversion_status_id'] = 'Conversions: Valid order status';
    $_['positive_conversion_status_id_help'] = 'When a client finish order, the conversion will be triggered if his order status is some of marked in this list (if empty, all order statuses will be considered like valid).';

    $_['configure_workspace'] = 'Google Tag Manager Workspace generator';
    $_['license_id'] = 'License Order ID*';
    $_['gdpr'] = 'GDPR adaptation';
    $_['gdpr_help'] = 'Configure bar style and texts in tab GDPR';
    $_['license_id_help'] = 'Get your Order ID from your Opencart account and paste here';
    $_['google_analytics_ua'] = 'Google Analytics UA';
    $_['google_analytics_ua_help'] = 'Get your Tracking ID from your Google Analytics account (UA-XXXXXXXX-X)';
    $_['enhanced_ecommerce_status'] = 'Enhanced ecommerce';
    $_['enhanced_ecommerce_status_help'] = 'Measure product lists, product clicks, add/remove to cart, purchases, promotions...';
    $_['conversion_status'] = 'Adwords conversion';
    $_['conversion_id'] = 'Adwords conversion ID*';
    $_['conversion_id_help'] = 'Get your Adwords Conversion ID and paste here';
    $_['conversion_label'] = 'Adwords conversion label*';
    $_['conversion_label_help'] = 'Get your Adwords Conversion Label and paste here';

    $_['dynamic_remarketing_status'] = 'Standard/Dynamic Remarketing';
    $_['dynamic_remarketing_id'] = 'Standard/Dynamic Remarketing conversion ID*';
    $_['dynamic_remarketing_id_help'] = 'Get your Standard/Dynamic Remarketing Conversion ID and paste here';
    $_['dynamic_remarketing_label'] = 'Standard/Dynamic Remarketing label';
    $_['dynamic_remarketing_label_help'] = 'Get your Standard/Dynamic Remarketing Conversion Label and paste here';
    $_['dynamic_remarketing_id_prefix'] = 'Prefix id';
    $_['dynamic_remarketing_id_prefix_help'] = '<b>NOT REQUIRED</b><br><br>Google Merchant locales: <b>US UK AU DE FR NL IT ES</b><br><br>Example: <b>es_59,</b>';
    $_['dynamic_remarketing_id_sufix'] = 'Sufix id';
    $_['dynamic_remarketing_id_sufix_help'] = '<b>NOT REQUIRED</b><br><br>Google Merchant locales: <b>US UK AU DE FR NL IT ES</b><br><br>Example: <b>59_es,</b>';
    $_['dynamic_remarketing_dynx'] = 'Dynx compatibility';
    $_['dynamic_remarketing_dynx2'] = 'dynx_itemid2 like';
    $_['dynamic_remarketing_dynx2_product_id'] = 'Product ID';
    $_['dynamic_remarketing_dynx2_model'] = 'Model';
    $_['dynamic_remarketing_dynx2_upc'] = 'UPC';
    $_['dynamic_remarketing_dynx2_ean'] = 'EAN';
    $_['dynamic_remarketing_dynx2_jan'] = 'JAN';
    $_['dynamic_remarketing_dynx2_isbn'] = 'ISBN';
    $_['dynamic_remarketing_dynx2_mpn'] = 'MPN';
    $_['dynamic_remarketing_dynx2_location'] = 'Location';

    $_['rich_snippets'] = 'Rich Snippets';

    $_['hotjar_status'] = 'Hotjar';
    $_['hotjar_site_id'] = 'Hotjar site ID';
    $_['hotjar_site_id_help'] = 'http://insights.hotjar.com/sites/**[SITE_ID]**/dashboard.';

    $_['pinterest_status'] = 'Pinterest Pixel';
    $_['pinterest_id'] = 'Pinterest ID';
    $_['pinterest_id_help'] = 'You can find it in your code shown on ads.pinterest.com/conversion_tag -> printrk(\'load\', \'YOUR_PINTEREST_ID\')';

    $_['crazyegg_status'] = 'Crazyegg Tracking';
    $_['crazyegg_id'] = 'Crazyegg ID';
    $_['crazyegg_id_help'] = 'Copy the account number at the top of the Google Tag Manager Instructions page. https://app.crazyegg.com/';

    $_['multichannel_funnel'] = 'GA Multichannel-Funnel';
    $_['multichannel_funnel_action_visit_product_page'] = 'Visit product page';
    $_['multichannel_funnel_action_add_to_cart'] = 'Add product to cart';
    $_['multichannel_funnel_action_visit_cart_page'] = 'Visit cart page';
    $_['multichannel_funnel_action_visit_checkout_page'] = 'Visit checkout page';
    $_['multichannel_funnel_action_finish_order'] = 'Finish order';
    $_['multichannel_step_1'] = 'Step 1';
    $_['multichannel_step_2'] = 'Step 2';
    $_['multichannel_step_3'] = 'Step 3';
    $_['multichannel_step_4'] = 'Step 4';
    $_['multichannel_step_5'] = 'Step 5';

    $_['google_reviews_status'] = 'Google Reviews';
    $_['google_reviews_id'] = 'Merchant ID';
    $_['google_reviews_id_help'] = 'Your Merchant Center ID. You can get this value from the Google Merchant Center.';
    $_['google_reviews_style'] = 'Style';
    $_['google_reviews_style_help'] = 'Specifies how the opt-in module\'s dialog box is displayed';
    $_['google_reviews_style_center_dialog'] = 'Displayed as a dialog box in the center of the view.';
    $_['google_reviews_style_bottom_right_dialog'] = 'Displayed as a dialog box at the bottom right of the view.';
    $_['google_reviews_style_bottom_left_dialog'] = 'Displayed as a dialog box at the bottom left of the view.';
    $_['google_reviews_style_top_right_dialog'] = 'Displayed as a dialog box at the top right of the view.';
    $_['google_reviews_style_top_left_dialog'] = 'Displayed as a dialog box at the top left of the view.';
    $_['google_reviews_style_bottom_tray'] = 'Displayed in the bottom of the view.';
    $_['google_reviews_delivery_days'] = 'Estimated days';
    $_['google_reviews_delivery_days_help'] = 'The estimated delivery days after purchase.';
    $_['google_reviews_lang_default_code'] = 'Language default code';
    $_['google_reviews_lang_default_code_help'] = 'NOT REQUIRED: If you detected that your google reviews forms language is not correct, force putting here your language code.';
    $_['google_reviews_gtin'] = 'Add product GTIN';
    $_['google_reviews_gtin_help'] = 'Required from some countries/feeds. Choose GTIN used in your feed.';
    $_['google_reviews_gtin_none'] = 'Nothing selected';
    $_['google_reviews_gtin_mpn'] = 'MPN';
    $_['google_reviews_gtin_model'] = 'Model';
    $_['google_reviews_gtin_ean'] = 'EAN';
    $_['google_reviews_gtin_upc'] = 'UPC';
    $_['google_reviews_badge_code'] = 'Badge';
    $_['google_reviews_badge_code_style'] = 'Badge style';
    $_['google_reviews_badge_code_style_bottom_right'] = 'Causes the badge to float in the bottom right of the page';
    $_['google_reviews_badge_code_style_bottom_left'] = 'Causes the badge to float in the bottom left of the page';
    $_['google_reviews_badge_code_style_bottom_inline'] = 'Renders the badge in the place in which the code appears';

    $_['google_product_id_like'] = 'Product Identificator like';
    $_['google_product_id_like_help'] = 'Affect only to Google Dynamic Remarketing. Will set Product ID if custom identificator is not found.';

    $_['criteo_status'] = 'Criteo OneTag';
  	$_['criteo_id'] = 'Partner ID';
  	$_['criteo_id_help'] = 'Get it from your criteo account, is a numeric string';

    $_['twenga_status'] = 'Twenga OneTag';
    $_['twenga_id'] = 'Partner ID';
    $_['twenga_id_help'] = 'Get it from your twenga account, is a numeric string';

    $_['facebook_pixel_status'] = 'Facebook Pixel';
    $_['facebook_pixel_id'] = 'Facebook Pixel ID*';
    $_['facebook_pixel_id_help'] = 'Create your facebook pixel and paste here the ONLY THE PIXEL ID';

    $_['bing_ads_status'] = 'Bing Ads';
    $_['bing_ads_tag_id'] = 'Bing Ads tag ID*';
    $_['bing_ads_tag_id_help'] = 'Get your Bing Ads tag id from your account and paste here';

    $_['generate_workspace'] = 'Generate Google Tag Manager Workspace';

//Tab GDPR
    $_['tab_gdpr'] = 'GDPR';
    $_['gdpr_configuration'] = 'GDPR Configuration';
    $_['gdpr_status'] = 'Status';
    $_['gdpr_conatiner_width'] = 'Container max width';
    $_['gdpr_position'] = 'Bar position';
    $_['gdpr_position_top'] = 'Top';
    $_['gdpr_position_bottom'] = 'Bottom';
    $_['gdpr_title'] = 'Bar title';
    $_['gdpr_text'] = 'Bar text';
    $_['gdpr_text_help'] = 'The link in this text, need to be changed to your real privacy policy link.';
    $_['gdpr_button_accept_text'] = 'Button accept text';
    $_['gdpr_button_checkbox_statistics'] = 'Checkbox statistics';
    $_['gdpr_button_checkbox_marketing'] = 'Checkbox marketing';
    $_['gdpr_button_position'] = 'Button configure cookies';
    $_['gdpr_button_title'] = 'Button configure cookies title';
    $_['gdpr_button_none_button'] = 'No button';
    $_['gdpr_button_position_top_left'] = 'Top left';
    $_['gdpr_button_position_top_right'] = 'Top right';
    $_['gdpr_button_position_bottom_left'] = 'Bottom left';
    $_['gdpr_button_position_bottom_right'] = 'Bottom right';
    $_['gdpr_bar_background'] = 'Bar background color';
    $_['gdpr_bar_color'] = 'Bar text color';
    $_['gdpr_button_configure_background'] = 'Button configure cookies background';
    $_['gdpr_bar_background_button'] = 'Bar button background color';
    $_['gdpr_bar_background_button_hover'] = 'Bar button background color hover';
    $_['gdpr_bar_background_link'] = 'Bar link color';
    $_['gdpr_bar_background_link_hover'] = 'Bar link color hover';
    $_['gdpr_bar_color_button'] = 'Bar button text color';
    $_['gdpr_custom_code'] = 'Custom code';
    $_['gdpr_custom_code_help'] = 'To CSS changes or another actions.';

//Tab feeds configurations
    $_['tab_feeds_configurations'] = 'Feeds';
    $_['feeds_choose_type_legend'] = 'Select your feed type to load configuration panel';
    $_['feeds_choose_type'] = 'Select feed type';
    $_['feed_type_choose_type'] = '- Choose one feed type -';
    $_['feed_type_google_shopping'] = 'Google shopping merchant center';
    $_['feed_type_google_shopping_reviews'] = 'Google shopping reviews';
    $_['feed_type_adwords'] = 'Google Adwords CSV format';
    $_['feed_type_facebook_catalog'] = 'Facebook catalog';
    $_['feed_type_criteo'] = 'Criteo';
    $_['feed_type_twenga'] = 'Twenga';

//Tab Merchant Center
  $_['tab_merchant_center'] = 'Feed shopping';

    $_['google_base_pro_configuration_legend'] = 'Configuration Management';
    $_['google_base_pro_configuration_select'] = 'Select a configuration';
    $_['google_base_pro_configuration_load'] = 'Load configuration selected';
    $_['google_base_pro_configuration_delete'] = 'Delete configuration selected';

    $_['google_base_pro_configuration_name'] = 'Configuration name';
    $_['google_base_pro_configuration_name_error'] = 'Error: fill a configuration name';
    $_['google_base_pro_configuration_type_error'] = 'Error: type didn\'t send';
    $_['google_base_pro_configuration_save'] = 'Save this configuration';
    $_['google_base_pro_configuration_restore'] = 'Restore configuration from backup';
    $_['google_base_pro_configuration_restore_help'] = 'If you lost configurations in some extension update, press here to restore your old configuration';
    $_['google_base_pro_configuration_restore_backup_success'] = 'Success: your configuration was restored from backup.';
    $_['google_base_pro_configuration_restore_backup_error_copy'] = 'Error: error restoring your backup file, make sure that you gave recursive permission 775 to folder "/catalog/controler/feed"';
    $_['google_base_pro_configuration_restore_backup_not_found'] = 'Backup file <b>"/catalog/controller/extension/feed/google_base_pro_configurations_backup.json"</b> not found. Make sure that you pressed first button <b>"Save this configuration"</b> some time.';
    $_['google_base_pro_configuration_save_successfully'] = 'Configuration "<b>%s</b>" saved successfully!';
    $_['google_base_pro_configuration_not_found'] = 'Error: Configuration not found';
    $_['google_base_pro_configuration_deleted_successfully'] = 'Configuration "<b>%s</b>" deleted successfully!';

  $_['google_base_pro_title'] = 'Feed title';
  $_['google_base_pro_description'] = 'Feed description';
  $_['google_base_pro_link'] = 'Link shop';
  $_['google_base_pro_thumb_width'] = 'Product thumb width';
  $_['google_base_pro_thumb_width_help'] = 'Only numbers (px)';
  $_['google_base_pro_thumb_height'] = 'Product thumb height';
  $_['google_base_pro_thumb_height_help'] = 'Only numbers (px)';
  $_['google_base_pro_show_out_stock'] = 'Show even out of stock';
  $_['google_base_pro_option_split'] = 'Create new product each product option';
  $_['google_base_pro_option_split_help'] = 'The system will insert new product in feed for each option or option, with different stock, price...';
  $_['google_base_pro_ignore_products'] = 'Product ignores';
  $_['google_base_pro_ignore_products_help'] = 'Insert models products separated by commas';

  $_['google_base_pro_products_attributes'] = 'Products Attributes';
  $_['google_base_pro_product_id'] = 'Product id';
  $_['google_base_pro_product_id_like'] = 'Product id like';
  $_['google_base_pro_multiples_identificators'] = 'Multiples products identificators';
  $_['google_base_pro_multiples_identificators_help'] = '<b>MPN or MODEL and EAN or UPC</b><br><br>If a product has mpn then mpn will be mpn, else mpn will be model.<br><br>If a product has EAN, GTIN will be EAN, else GTIN will be UPC';
  $_['google_base_pro_identifier_exists'] = 'Identifier exists FALSE';
  $_['google_base_pro_identifier_exists_help'] = '<b>When to active</b>: Required when unique product identifiers do not exist (GTIN (ean or upc), BRAND and MPN).';
  $_['google_base_pro_product_title'] = 'Title';
  $_['google_base_pro_product_link'] = 'Link';
  $_['google_base_pro_product_description'] = 'Description';
  $_['google_base_pro_product_brand'] = 'Brand';
  $_['google_base_pro_product_condition'] = 'Condition';
  $_['google_base_pro_product_image_link'] = 'Image link';
  $_['google_base_pro_product_additional_images'] = 'Additional images links';
  $_['google_base_pro_product_additional_images_help'] = 'Maximum allowed by Google: 10';
  $_['google_base_pro_product_price'] = 'Price';
  $_['google_base_pro_product_sale_price'] = 'Sale Price';
  $_['google_base_pro_product_sale_price_customer_group'] = 'Sale price customer group';
  $_['google_base_pro_product_sale_price_customer_group_help'] = 'Select customer group that is related to your specials';
  $_['google_base_pro_product_include_tax'] = 'Include tax in prices';
  $_['google_base_pro_product_type'] = 'Type (google categories)';
  $_['google_base_pro_product_quantity'] = 'Quantity';
  $_['google_base_pro_product_weight'] = 'Weight';
  $_['google_base_pro_product_availability'] = 'Availability';

  $_['google_base_pro_product_size'] = 'Size';
  $_['google_base_pro_product_size_help'] = 'The system will split the products in new products for each size available in this product, with different quantity, price...';
  $_['google_base_pro_product_size_attribute'] = 'Size in Attribute';
  $_['google_base_pro_product_size_filter'] = 'Size in filter';
  $_['google_base_pro_product_size_option'] = 'Size in option';

  $_['google_base_pro_product_color'] = 'Color';
  $_['google_base_pro_product_color_slipt'] = 'Split products';
  $_['google_base_pro_product_color_slipt_help'] = 'Recommended to Colors in Options. The system will split the products in new products for each color available in this product, with different quantity, price... If is disabled will send multiples colors in same main product, example: &quot;red/blue/green&quot;';
  $_['google_base_pro_product_color_attribute'] = 'Color in Attribute';
  $_['google_base_pro_product_color_filter'] = 'Color in filter';
  $_['google_base_pro_product_color_option'] = 'Color in option';

  $_['google_base_pro_product_material'] = 'Material';
  $_['google_base_pro_product_material_attribute'] = 'Material in Attribute';
  $_['google_base_pro_product_material_filter'] = 'Material in filter';
  $_['google_base_pro_product_material_option'] = 'Material in option';

  $_['google_base_pro_product_gender'] = 'Gender';
  $_['google_base_pro_product_gender_help'] = 'Google Merchant Center requires 1 of this values: male, female or unisex';
  $_['google_base_pro_product_gender_attribute'] = 'Gender in Attribute';
  $_['google_base_pro_product_gender_filter'] = 'Gender in filter';
  $_['google_base_pro_product_gender_option'] = 'Gender in option';

  $_['google_base_pro_product_age_group'] = 'Age group';
  $_['google_base_pro_product_age_group_help'] = 'Google Merchant Center requires 1 of this values: newborn, infant, toddler, kids, adult';
  $_['google_base_pro_product_age_group_attribute'] = 'Age group in Attribute';
  $_['google_base_pro_product_age_group_filter'] = 'Age group in filter';
  $_['google_base_pro_product_age_group_option'] = 'Age group in option';

  $_['google_base_pro_product_custom_label_help'] = 'Label that you assign to a product to help organize bidding and reporting in Shopping campaigns';
  $_['google_base_pro_product_custom_label_0'] = 'Custom label 0';
  $_['google_base_pro_product_custom_label_0_fixed_word'] = 'Custom label 0 Fixed word';
  $_['google_base_pro_product_custom_label_0_attribute'] = 'Custom label 0 in Attribute';
  $_['google_base_pro_product_custom_label_0_filter'] = 'Custom label 0 in Filter';
  $_['google_base_pro_product_custom_label_0_option'] = 'Custom label 0 in Option';

  $_['google_base_pro_product_custom_label_1'] = 'Custom label 1';
  $_['google_base_pro_product_custom_label_1_fixed_word'] = 'Custom label 1 Fixed word';
  $_['google_base_pro_product_custom_label_1_attribute'] = 'Custom label 1 in Attribute';
  $_['google_base_pro_product_custom_label_1_filter'] = 'Custom label 1 in Filter';
  $_['google_base_pro_product_custom_label_1_option'] = 'Custom label 1 in Option';

  $_['google_base_pro_product_custom_label_2'] = 'Custom label 2';
  $_['google_base_pro_product_custom_label_2_fixed_word'] = 'Custom label 2 Fixed word';
  $_['google_base_pro_product_custom_label_2_attribute'] = 'Custom label 2 in Attribute';
  $_['google_base_pro_product_custom_label_2_filter'] = 'Custom label 2 in Filter';
  $_['google_base_pro_product_custom_label_2_option'] = 'Custom label 2 in Option';

  $_['google_base_pro_product_custom_label_3'] = 'Custom label 3';
  $_['google_base_pro_product_custom_label_3_fixed_word'] = 'Custom label 3 Fixed word';
  $_['google_base_pro_product_custom_label_3_attribute'] = 'Custom label 3 in Attribute';
  $_['google_base_pro_product_custom_label_3_filter'] = 'Custom label 3 in Filter';
  $_['google_base_pro_product_custom_label_3_option'] = 'Custom label 3 in Option';

  $_['google_base_pro_product_custom_label_4'] = 'Custom label 4';
  $_['google_base_pro_product_custom_label_4_fixed_word'] = 'Custom label 4 Fixed word';
  $_['google_base_pro_product_custom_label_4_attribute'] = 'Custom label 4 in Attribute';
  $_['google_base_pro_product_custom_label_4_filter'] = 'Custom label 4 in Filter';
  $_['google_base_pro_product_custom_label_4_option'] = 'Custom label 4 in Option';

  $_['google_base_pro_product_custom_label_5'] = 'Custom label 5';
  $_['google_base_pro_product_custom_label_5_fixed_word'] = 'Custom label 5 Fixed word';
  $_['google_base_pro_product_custom_label_5_attribute'] = 'Custom label 5 in Attribute';
  $_['google_base_pro_product_custom_label_5_filter'] = 'Custom label 5 in Filter';
  $_['google_base_pro_product_custom_label_5_option'] = 'Custom label 5 in Option';

  $_['google_base_pro_categories_legend'] = 'Allowed categories - Mark categories whose products you want that appear in feed';
  $_['google_base_pro_select_all'] = 'SELECT/UNSELECT ALL';

  $_['google_base_pro_products_google_categories'] = 'Google Merchant Center Categories (Autocomplete category name)';
  $_['google_base_pro_country'] = 'Country';

  $_['google_base_pro_typing'] = 'Type, the system will search google merchant center categories.';

  $_['google_base_pro_ai'] = '<b>Your feed url:</b> http://www.yourshopurl.com/<b>index.php?route=feed/google_base_pro</b><br><br>About feed: <a target="_blank" href="https://support.google.com/merchants/answer/188494?hl=en">visit Google Merchant Center Documentation</a>';

  $_['google_base_pro_feed_urls'] = 'Feeds URLs to Google Merchant Center';
//END Tab Merchant Center

//Tab Google reviews feed
    $_['tab_feed_shopping_reviews'] = 'Feed shopping reviews';
    $_['reviews_base_pro_configuration_legend'] = 'Configuration Management - Feed shopping reviews';

    $_['reviews_base_pro_configuration_select'] = 'Select a configuration';
    $_['reviews_base_pro_configuration_load'] = 'Load configuration selected';
    $_['reviews_base_pro_configuration_delete'] = 'Delete configuration selected';

    $_['reviews_base_pro_configuration_name'] = 'Configuration name';
    $_['reviews_base_pro_configuration_name_error'] = 'Error: fill a configuration name';
    $_['reviews_base_pro_configuration_type_error'] = 'Error: type didn\'t send';
    $_['reviews_base_pro_configuration_save'] = 'Save this configuration';
    $_['reviews_base_pro_configuration_restore'] = 'Restore configuration from backup';
    $_['reviews_base_pro_configuration_restore_help'] = 'If you lost configurations in some extension update, press here to restore your old configuration';
    $_['google_reviews_base_pro_configuration_restore_backup_success'] = 'Success: your configuration was restored from backup.';
    $_['google_reviews_base_pro_configuration_restore_backup_error_copy'] = 'Error: error restoring your backup file, make sure that you gave recursive permission 775 to folder "/catalog/controler/feed"';
    $_['google_reviews_base_pro_configuration_restore_backup_not_found'] = 'Backup file <b>"/catalog/controller/extension/feed/reviews_base_pro_configurations_backup.json"</b> not found. Make sure that you pressed first button <b>"Save this configuration"</b> some time.';
    $_['google_reviews_base_pro_configuration_save_successfully'] = 'Configuration "<b>%s</b>" saved successfully!';
    $_['google_reviews_base_pro_configuration_not_found'] = 'Error: Configuration not found';
    $_['google_reviews_base_pro_configuration_deleted_successfully'] = 'Configuration "<b>%s</b>" deleted successfully!';
    $_['reviews_base_pro_feed_urls'] = 'Feeds URLs';
    $_['reviews_base_pro_ignore_products'] = 'Product ignores';
    $_['reviews_base_pro_ignore_products_help'] = 'Insert models products separated by commas';
    $_['reviews_base_pro_categories_legend'] = 'Allowed categories - Mark categories whose products you want that appear in feed XML';
    $_['reviews_base_pro_select_all'] = 'SELECT/UNSELECT ALL';
//END Tab Google reviews feed

//Tab Google Business feed
  $_['tab_google_business_feed'] = 'CSV Adwords';

  $_['business_base_pro_configuration_legend'] = 'Configuration Management';
    $_['business_base_pro_configuration_select'] = 'Select a configuration';
    $_['business_base_pro_configuration_load'] = 'Load configuration selected';
    $_['business_base_pro_configuration_delete'] = 'Delete configuration selected';

    $_['business_base_pro_configuration_name'] = 'Configuration name';
    $_['google_business_base_pro_configuration_name_error'] = 'Error: fill a configuration name';
    $_['google_business_base_pro_configuration_type_error'] = 'Error: type didn\'t send';
    $_['business_base_pro_configuration_save'] = 'Save this configuration';
    $_['business_base_pro_configuration_restore'] = 'Restore configuration from backup';
    $_['business_base_pro_configuration_restore_help'] = 'If you lost configurations in some extension update, press here to restore your old configuration';
    $_['google_business_base_pro_configuration_restore_backup_success'] = 'Success: your configuration was restored from backup.';
    $_['google_business_base_pro_configuration_restore_backup_error_copy'] = 'Error: error restoring your backup file, make sure that you gave recursive permission 775 to folder "/catalog/controler/feed"';
    $_['google_business_base_pro_configuration_restore_backup_not_found'] = 'Backup file <b>"/catalog/controller/extension/feed/google_business_base_pro_configurations.json"</b> not found. Make sure that you pressed first button <b>"Save this configuration"</b> some time.';
    $_['google_business_base_pro_configuration_save_successfully'] = 'Configuration "<b>%s</b>" saved successfully!';
    $_['google_business_base_pro_configuration_not_found'] = 'Error: Configuration not found';
    $_['google_business_base_pro_configuration_deleted_successfully'] = 'Configuration "<b>%s</b>" deleted successfully!';

  $_['business_base_pro_thumb_width'] = 'Product thumb width';
  $_['business_base_pro_thumb_width_help'] = 'Only numbers (px)';
  $_['business_base_pro_thumb_height'] = 'Product thumb height';
  $_['business_base_pro_thumb_height_help'] = 'Only numbers (px)';
  $_['business_base_pro_show_out_stock'] = 'Show even out of stock';
  $_['business_base_pro_ignore_products'] = 'Product ignores';
  $_['business_base_pro_ignore_products_help'] = 'Insert models products separated by commas';

  $_['business_base_pro_products_attributes'] = 'Products Attributes';
  $_['business_base_pro_product_id'] = 'Product id';
  $_['business_base_pro_product_id2'] = 'Product id 2';
  $_['business_base_pro_product_id2_help'] = 'Will be product model';
  $_['business_base_pro_product_title'] = 'Title';
  $_['business_base_pro_product_keywords'] = 'Keywords';
  $_['business_base_pro_product_category'] = 'Category';
  $_['business_base_pro_product_link'] = 'Link';
  $_['business_base_pro_product_description'] = 'Description';
  $_['business_base_pro_product_image_link'] = 'Image link';
  $_['business_base_pro_product_price'] = 'Price';
  $_['business_base_pro_product_price_formatted'] = 'Price formatted';
  $_['business_base_pro_product_sale_price'] = 'Sale price';
  $_['business_base_pro_product_sale_price_formatted'] = 'Sale price formatted';
  $_['business_base_pro_product_sale_price_customer_group'] = 'Sale price customer group';
  $_['business_base_pro_product_sale_price_customer_group_help'] = 'Select customer group that is related to your specials';
  $_['business_base_pro_product_include_tax'] = 'Include tax in prices';

  $_['google_business_pro_categories_legend'] = 'Allowed categories - Mark categories whose products you want that appear in CSV';
  $_['google_business_pro_select_all'] = 'SELECT/UNSELECT ALL';

  $_['business_base_pro_feed_urls'] = 'CSV URLs to Google My Business';
//END Google Business feed

//Tab Facebook feed
  $_['tab_facebook_base'] = 'Feed FB';

  $_['facebook_base_pro_configuration_legend'] = 'Configuration Management';
  $_['facebook_base_pro_configuration_select'] = 'Select a configuration';
  $_['facebook_base_pro_configuration_load'] = 'Load configuration selected';
  $_['facebook_base_pro_configuration_delete'] = 'Delete configuration selected';
  $_['facebook_base_pro_configuration_name'] = 'Configuration name';
  $_['google_facebook_base_pro_configuration_name_error'] = 'Error: fill a configuration name';
    $_['google_facebook_base_pro_configuration_type_error'] = 'Error: type didn\'t send';
  $_['facebook_base_pro_configuration_save'] = 'Save this configuration';
  $_['facebook_base_pro_configuration_restore'] = 'Restore configuration from backup';
  $_['facebook_base_pro_configuration_restore_help'] = 'If you lost configurations in some extension update, press here to restore your old configuration';
  $_['google_facebook_base_pro_configuration_restore_backup_success'] = 'Success: your configuration was restored from backup.';
  $_['google_facebook_base_pro_configuration_restore_backup_error_copy'] = 'Error: error restoring your backup file, make sure that you gave recursive permission 775 to folder "/catalog/controler/feed"';
  $_['google_facebook_base_pro_configuration_restore_backup_not_found'] = 'Backup file <b>"/catalog/controller/extension/feed/facebook_base_pro_configurations.json"</b> not found. Make sure that you pressed first button <b>"Save this configuration"</b> some time.';
  $_['google_facebook_base_pro_configuration_save_successfully'] = 'Configuration "<b>%s</b>" saved successfully!';
    $_['google_facebook_base_pro_configuration_not_found'] = 'Error: Configuration not found';
    $_['google_facebook_base_pro_configuration_deleted_successfully'] = 'Configuration "<b>%s</b>" deleted successfully!';
  $_['facebook_base_pro_feed_urls'] = 'Feeds URLs to Facebook';

  $_['facebook_base_pro_title'] = 'Feed title';
  $_['facebook_base_pro_description'] = 'Feed description';
  $_['facebook_base_pro_link'] = 'Link shop';
  $_['facebook_base_pro_thumb_width'] = 'Product thumb width';
  $_['facebook_base_pro_thumb_width_help'] = 'Only numbers (px)';
  $_['facebook_base_pro_thumb_height'] = 'Product thumb height';
  $_['facebook_base_pro_thumb_height_help'] = 'Only numbers (px)';
  $_['facebook_base_pro_show_out_stock'] = 'Show even out of stock';
  $_['facebook_base_pro_ignore_products'] = 'Product ignores';
  $_['facebook_base_pro_ignore_products_help'] = 'Insert models products separated by commas';

  $_['facebook_base_pro_products_attributes'] = 'Products Attributes';
  $_['facebook_base_pro_product_id'] = 'Product id';
  $_['facebook_base_pro_multiples_identificators'] = 'GTIN';
  $_['facebook_base_pro_multiples_identificators_help'] = 'If a product has EAN, GTIN will be EAN, else GTIN will be UPC';
  $_['facebook_base_pro_identifier_exists'] = 'Identifier exists FALSE';
  $_['facebook_base_pro_identifier_exists_help'] = '<b>When to active</b>: Required when unique product identifiers do not exist (GTIN (ean or upc), BRAND and MPN).';
  $_['facebook_base_pro_product_title'] = 'Title';
  $_['facebook_base_pro_product_link'] = 'Link';
  $_['facebook_base_pro_product_description'] = 'Description';
  $_['facebook_base_pro_product_brand'] = 'Brand';
  $_['facebook_base_pro_product_condition'] = 'Condition';
  $_['facebook_base_pro_product_image_link'] = 'Image link';
  $_['facebook_base_pro_product_price'] = 'Price';
  $_['facebook_base_pro_product_sale_price'] = 'Sale price';
  $_['facebook_base_pro_product_sale_price_customer_group'] = 'Sale price customer group';
  $_['facebook_base_pro_product_sale_price_customer_group_help'] = 'Select customer group that is related to your specials';
  $_['facebook_base_pro_product_include_tax'] = 'Include tax in prices';
  $_['facebook_base_pro_product_type'] = 'Type (google categories)';
  $_['facebook_base_pro_product_quantity'] = 'Quantity';
  $_['facebook_base_pro_product_weight'] = 'Weight';
  $_['facebook_base_pro_product_availability'] = 'Availability';
  $_['facebook_base_pro_products_google_categories'] = 'Google Merchant Center Categories (Autocomplete category name)';
  $_['facebook_base_pro_country'] = 'Country:';


  $_['facebook_base_pro_typing'] = 'Type, the system will search google merchant center categories.';

  $_['facebook_base_pro_categories_legend'] = 'Allowed categories - Mark categories whose products you want that appear in feed';
  $_['facebook_base_pro_select_all'] = 'SELECT/UNSELECT ALL';
//END Tab Facebook feed

//Tab Criteo feed
  $_['tab_criteo_base'] = 'Feed Criteo';

    $_['criteo_base_pro_configuration_legend'] = 'Configuration Management';
  $_['criteo_base_pro_configuration_select'] = 'Select a configuration';
  $_['criteo_base_pro_configuration_load'] = 'Load configuration selected';
  $_['criteo_base_pro_configuration_delete'] = 'Delete configuration selected';
  $_['criteo_base_pro_configuration_name'] = 'Configuration name';
  $_['google_criteo_base_pro_configuration_name_error'] = 'Error: fill a configuration name';
    $_['google_criteo_base_pro_configuration_type_error'] = 'Error: type didn\'t send';
  $_['criteo_base_pro_configuration_save'] = 'Save this configuration';
  $_['criteo_base_pro_configuration_restore'] = 'Restore configuration from backup';
  $_['criteo_base_pro_configuration_restore_help'] = 'If you lost configurations in some extension update, press here to restore your old configuration';
  $_['google_criteo_base_pro_configuration_restore_backup_success'] = 'Success: your configuration was restored from backup.';
  $_['google_criteo_base_pro_configuration_restore_backup_error_copy'] = 'Error: error restoring your backup file, make sure that you gave recursive permission 775 to folder "/catalog/controler/feed"';
  $_['google_criteo_base_pro_configuration_restore_backup_not_found'] = 'Backup file <b>"/catalog/controller/extension/feed/criteo_base_pro_configurations.json"</b> not found. Make sure that you pressed first button <b>"Save this configuration"</b> some time.';
  $_['google_criteo_base_pro_configuration_save_successfully'] = 'Configuration "<b>%s</b>" saved successfully!';
    $_['google_criteo_base_pro_configuration_not_found'] = 'Error: Configuration not found';
    $_['google_criteo_base_pro_configuration_deleted_successfully'] = 'Configuration "<b>%s</b>" deleted successfully!';
  $_['criteo_base_pro_feed_urls'] = 'Feeds URLs to Criteo';

  $_['criteo_base_pro_title'] = 'Feed title';
  $_['criteo_base_pro_description'] = 'Feed description';
  $_['criteo_base_pro_link'] = 'Link shop';
  $_['criteo_base_pro_show_out_stock'] = 'Show even out of stock';
  $_['criteo_base_pro_product_include_tax'] = 'Include tax in prices';
  $_['criteo_base_pro_ignore_products'] = 'Product ignores';
  $_['criteo_base_pro_ignore_products_help'] = 'Insert models products separated by commas';

  $_['criteo_base_pro_required'] = 'Required values';
  $_['criteo_base_pro_product_adult'] = 'Adult';
  $_['criteo_base_pro_product_adult_no'] = 'No';
  $_['criteo_base_pro_product_adult_yes'] = 'Yes';
  $_['criteo_base_pro_product_out_of_stock'] = 'Out of stock status';
  $_['criteo_base_pro_product_out_of_stock_out_of_stock'] = 'Out of stock';
  $_['criteo_base_pro_product_out_of_stock_preorder'] = 'Preorder';

  $_['criteo_base_pro_products_google_categories'] = 'Google Merchant Center Categories (Autocomplete category name)';
  $_['criteo_base_pro_country'] = 'Country:';
  $_['criteo_base_pro_typing'] = 'Type, the system will search google merchant center categories.';

  $_['criteo_base_pro_categories_legend'] = 'Allowed categories - Mark categories whose products you want that appear in feed';
  $_['criteo_base_pro_select_all'] = 'SELECT/UNSELECT ALL';
//END Tab Criteo feed

//Tab Twenga feed
  $_['tab_twenga_base'] = 'Feed Twenga';

    $_['twenga_base_pro_configuration_legend'] = 'Configuration Management';
  $_['twenga_base_pro_configuration_select'] = 'Select a configuration';
  $_['twenga_base_pro_configuration_load'] = 'Load configuration selected';
  $_['twenga_base_pro_configuration_delete'] = 'Delete configuration selected';
  $_['twenga_base_pro_configuration_name'] = 'Configuration name';
  $_['google_twenga_base_pro_configuration_name_error'] = 'Error: fill a configuration name';
    $_['google_twenga_base_pro_configuration_type_error'] = 'Error: type didn\'t send';
  $_['twenga_base_pro_configuration_save'] = 'Save this configuration';
  $_['twenga_base_pro_configuration_restore'] = 'Restore configuration from backup';
  $_['twenga_base_pro_configuration_restore_help'] = 'If you lost configurations in some extension update, press here to restore your old configuration';
  $_['google_twenga_base_pro_configuration_restore_backup_success'] = 'Success: your configuration was restored from backup.';
  $_['google_twenga_base_pro_configuration_restore_backup_error_copy'] = 'Error: error restoring your backup file, make sure that you gave recursive permission 775 to folder "/catalog/controler/feed"';
  $_['google_twenga_base_pro_configuration_restore_backup_not_found'] = 'Backup file <b>"/catalog/controller/extension/feed/twenga_base_pro_configurations.json"</b> not found. Make sure that you pressed first button <b>"Save this configuration"</b> some time.';
  $_['google_twenga_base_pro_configuration_save_successfully'] = 'Configuration "<b>%s</b>" saved successfully!';
    $_['google_twenga_base_pro_configuration_not_found'] = 'Error: Configuration not found';
    $_['google_twenga_base_pro_configuration_deleted_successfully'] = 'Configuration "<b>%s</b>" deleted successfully!';
  $_['twenga_base_pro_feed_urls'] = 'Feeds URLs to twenga';

  $_['twenga_base_pro_thumbs_width'] = 'Products thumbs width';
  $_['twenga_base_pro_thumbs_width_help'] = 'Min 500 max 2000';
  $_['twenga_base_pro_thumbs_height'] = 'Products thumbs height';
  $_['twenga_base_pro_thumbs_height_help'] = 'Min 500 max 2000';

  $_['twenga_base_pro_option_split'] = 'Create new product each product option';
  $_['twenga_base_pro_option_split_help'] = 'The system will insert new product in feed for each option or option, with different stock, price...';

  $_['twenga_base_pro_show_out_stock'] = 'Show even out of stock';
  $_['twenga_base_pro_ignore_products'] = 'Product ignores';
  $_['twenga_base_pro_ignore_products_help'] = 'Insert models products separated by commas';

  $_['twenga_base_pro_required'] = 'Product values';

  $_['twenga_base_pro_product_shipping'] = 'Shipping cost';
  $_['twenga_base_pro_product_shipping_help'] = 'If you don\'t want show it put here <b>NC</b> instead of 0!';

  $_['twenga_base_pro_product_out_of_stock'] = 'Out of stock status';
  $_['twenga_base_pro_product_out_of_stock_out_of_stock'] = 'Out of stock';
  $_['twenga_base_pro_product_out_of_stock_restocking'] = 'Restocking';

  $_['twenga_base_pro_product_stock_detail'] = 'Stock detail';
  $_['twenga_base_pro_product_stock_detail_help'] = 'The sentence will be dislayed exactly as it is povided';

  $_['twenga_base_pro_product_stock_margin'] = 'Product\'s margin percentage';
  $_['twenga_base_pro_product_stock_margin_help'] = 'Only numbers, perfectage.';

  $_['twenga_base_pro_categories_legend'] = 'Allowed categories - Mark categories whose products you want that appear in feed';
  $_['twenga_base_pro_select_all'] = 'SELECT/UNSELECT ALL';
//END Tab Twenga feed

//Tab Abandoned cart
  $_['tab_abandoned_cart'] = 'Abandoned carts';
  $_['google_ac_api_key'] = 'Mailchimp API key';
  $_['google_ac_list_id'] = 'Mailchimp List ID';
  $_['google_ac_input_selector_firstname'] = 'Firstname input selector';
  $_['google_ac_input_selector_firstname_help'] = 'To guest users.<br>When a guest user leaves this input, the information of this field be send to mailchimp to subscribe.';
  $_['google_ac_input_selector_lastname'] = 'Lastname input selector';
  $_['google_ac_input_selector_lastname_help'] = 'To guest users.<br>When a guest user leaves this input, the information of this field be send to mailchimp to subscribe.';
  $_['google_ac_input_selector_email'] = 'Email input selector';
  $_['google_ac_input_selector_email_help'] = 'To guest users.<br>When a guest user leaves this input, the information of this field be send to mailchimp to subscribe.';
  $_['google_ac_tutorial_title'] = 'Mailchimp Tutorial';

  $_['google_ac_tutorial_step_1'] = '1.- Create your API KEY';

  $_['google_ac_tutorial_step_1_text'] = '
    <h4>1.- Click in your account icon/username in header right <span class="point">(1)</span> and after press in "<b>Profile</b>" <span class="point">(2)</span></h4>
    <img class="mailchimp_tutorial img-responsive" src="'.HTTP_SERVER.'view/image/gmt/mailchimp/api_01.jpg">
    <h4>2.- Click "<b>Extras</b>" <span class="point">(1)</span> and after press in "<b>API keys</b>" <span class="point">(2)</span></h4>
    <img class="mailchimp_tutorial img-responsive" src="'.HTTP_SERVER.'view/image/gmt/mailchimp/api_02.jpg">
    <h4>3.- Finally, press in button "<b>Create A Key</b>" <span class="point">(1)</span>, copy your API Key <span class="point">(2)</span> and paste in this configuration. Apply chages.</h4>
    <img class="mailchimp_tutorial img-responsive" src="'.HTTP_SERVER.'view/image/gmt/mailchimp/api_03.jpg">
  ';

  $_['google_ac_tutorial_step_2'] = '2.- Create "Abandoned carts" list and get List ID';

  $_['google_ac_tutorial_step_2_text'] = '
    <h4>1.- Access to main menu button "Lists", in this section press button "<b>Create list</b>" <span class="point">(1)</span>, a new section will appear, again, press in new button "<b>Create list</b>" <span class="point">(2)</span></h4>
    <img class="mailchimp_tutorial img-responsive" src="'.HTTP_SERVER.'view/image/gmt/mailchimp/list_01.jpg">
    <h4>2.- Fill all basic datas in list form and save it, return again to "<b>Lists</b>" section, in your new list, press button with an icon arrow <span class="point">(1)</span>, and after press in "<b>Settings</b>" <span class="point">(2)</span></h4>
    <img class="mailchimp_tutorial img-responsive" src="'.HTTP_SERVER.'view/image/gmt/mailchimp/list_02.jpg">
    <h4>3.- In this new section, press in tab "<b>Settings</b>" <span class="point">(1)</span>, and after press in "<b>List fields and *|MERGE|* tags</b>" <span class="point">(2)</span></h4>
    <img class="mailchimp_tutorial img-responsive" src="'.HTTP_SERVER.'view/image/gmt/mailchimp/list_03.jpg">
    <h4>4.- Inside of this section, press in button "<b>Add a field</b>" <span class="point">(1)</span>, select after type "<b>Text</b>", put his name "<b>Cart Session ID</b>" <span class="point">(2)</span>, put his tag "<b>CART_ID</b>" <span class="point">(3)</span>, and finally press in button "<b>Save Changes</b>"<span class="point">(4)</span></h4>
    <img class="mailchimp_tutorial img-responsive" src="'.HTTP_SERVER.'view/image/gmt/mailchimp/list_04.jpg">
    <h4>5.- To get the "<b>List ID</b>" go again to main section "<b>Lists</b>", press button with an icon arrow <span class="point">(1)</span>, and after press in "<b>Settings</b>" <span class="point">(2)</span>
    <img class="mailchimp_tutorial img-responsive" src="'.HTTP_SERVER.'view/image/gmt/mailchimp/list_05.jpg">
    <h4>6.- In this new section, press in tab "<b>Settings</b>" <span class="point">(1)</span>, and after press in "<b>List name and defaults</b>" <span class="point">(2)</span></h4>
    <img class="mailchimp_tutorial img-responsive" src="'.HTTP_SERVER.'view/image/gmt/mailchimp/list_06.jpg">
    <h4>7.- You can find the "<b>List ID</b>" in this new page (in red), copy it and paste in this configuration. Apply chages.
    <img class="mailchimp_tutorial img-responsive" src="'.HTTP_SERVER.'view/image/gmt/mailchimp/list_07.jpg">
  ';

  $_['google_ac_tutorial_step_3'] = '3.- Create "Abandoned carts campaign" campaign and customize your email';

  $_['google_ac_tutorial_step_3_text'] = '
    <h4>1.- Access to main menu button "Campaigns" <span class="point">(1)</span>, after press in button "<b>Create Campaign</b>" <span class="point">(2)</span></h4>
    <img class="mailchimp_tutorial img-responsive" src="'.HTTP_SERVER.'view/image/gmt/mailchimp/campaign_01.jpg">
    <h4>2.- In next window, press in button "<b>Create an email</b>" (in red)</h4>
    <img class="mailchimp_tutorial img-responsive" src="'.HTTP_SERVER.'view/image/gmt/mailchimp/campaign_02.jpg">
    <h4>3.- In next window, press in tab "<b>Automated</b>" <span class="point">(1)</span>, after press in tap "<b>Date Based</b>" <span class="point">(2)</span> and finally press in button "<b>List added date</b>" <span class="point">(3)</span></h4>
    <img class="mailchimp_tutorial img-responsive" src="'.HTTP_SERVER.'view/image/gmt/mailchimp/campaign_03.jpg">

    <h4>4.- Put your campaign title "<b>Abandoned Carts Campaign</b>" <span class="point">(1)</span>, select the previus created list "<b>Abandone Carts</b>" <span class="point">(2)</span>, and finally press in button "<b>Begin</b>" <span class="point">(3)</span></h4>
    <img class="mailchimp_tutorial img-responsive" src="'.HTTP_SERVER.'view/image/gmt/mailchimp/campaign_04.jpg">

    <h4>5.- You can see that by default have <b>3 Emails</b> (in red),  will be send like this way -> Email 1: 1 day after subscribers join your list, Email 2: 2 day after subscribers join your list, Email 3: 3 day after subscribers join your list.</h4>
    <img class="mailchimp_tutorial img-responsive" src="'.HTTP_SERVER.'view/image/gmt/mailchimp/campaign_05.jpg">

    <h4>6.- If you don\'t need 3 emails, you can delete each one pressing in button with arrow icon <span class="point">(1)</span> and after in "<b>Delete email</b>" <span class="point">(2)</span>
    <img class="mailchimp_tutorial img-responsive" src="'.HTTP_SERVER.'view/image/gmt/mailchimp/campaign_06.jpg">

    <h4>7.- Press button "<b>Design Email</b>" to configurate it (in red) (in this tutorial only go to edit the first email, you have to do same process if you decided have more emails in this campaign).</h4>
    <img class="mailchimp_tutorial img-responsive" src="'.HTTP_SERVER.'view/image/gmt/mailchimp/campaign_07.jpg">

    <h4>8.- Complete email basic information like the next image (of course that you can change texts!)</h4>
    <img class="mailchimp_tutorial img-responsive" src="'.HTTP_SERVER.'view/image/gmt/mailchimp/campaign_08.jpg">

    <h4>9.- In "<b>template selector</b>", press in tab "<b>Code your own</b>" <span class="point">(1)</span> and after press in "<b>Paste in code</b>" <span class="point">(2)</span></h4>
    <img class="mailchimp_tutorial img-responsive" src="'.HTTP_SERVER.'view/image/gmt/mailchimp/campaign_09.jpg">

    <h4>10.- Paste your email code in textarea <span class="point">(1)</span> and after press in button "<b>Save and Continue</b>" <span class="point">(2)</span>. A good example of this email template can be:</h4>
    <br>
    <b>Insert your domain to email template example:</b>
    <input class="form-control" onkeyup="$(\'span.tutorial_domain\').html($(this).val())" value="http://mydomain.com">
    <br>
    <pre>'.htmlspecialchars('
<p>Hi *|FNAME|* *|LNAME|*!</p>
<p>We detected that you tried to buy in our store, did you have any problems?</p>
<p>You can continue with your shopping process in the <a href="').'<span class="tutorial_domain">http://mydomain.com</span>'.htmlspecialchars('/?cart_id=*|CART_ID|*">following link</a>!</p>').'</pre>
    <img class="mailchimp_tutorial img-responsive" src="'.HTTP_SERVER.'view/image/gmt/mailchimp/campaign_10.jpg">

    <h4>11.- Finally, you can edit the "<b>Schedule</b>" of your emails pressing in link "<b>Edit schedule</b>" (in red) (by default is 9am):</h4>
    <img class="mailchimp_tutorial img-responsive" src="'.HTTP_SERVER.'view/image/gmt/mailchimp/campaign_11.jpg">
  ';
//END

//Tab Create database views
  $_['tab_create_mysql_views'] = 'DB views';
  $_['mysql_views_create_views_help'] = 'If you notice that your <b>feed takes a long time to generate</b>, create MYSQL views to do faster queries.<br><b>This process can take several minutes</b> depending your dataset volume.<br><b>If you installed new language or created new store to multistore press the button again.</b>';
  $_['mysql_views_create_views_button'] = 'Create views';
  $_['mysql_views_create_views_success'] = 'Success: Your MYSQL views was created successfully';

  $_['mysql_views_delete_views_button'] = 'Delete views';
  $_['mysql_views_delete_views_success'] = 'Success: Your MYSQL views was deleted successfully';
//END
?>