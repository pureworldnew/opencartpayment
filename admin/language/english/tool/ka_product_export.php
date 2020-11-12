<?php
/*
  Project: CSV Product Export
  Author : karapuz <support@ka-station.com>

  Version: 4 ($Revision: 31 $)

*/
// Heading
$_['heading_title']           = 'CSV Product Export';

// messages
$_['error_field_required']    = "'%s' field is required but not selected.";

// text
$_['Database is not compatible...'] = 'Database is not compatible with the extension. Please re-install the extension on the \'Product Feeds\' page.';
$_['STEP 1 of 3'] = 'STEP 1 of 3';
$_['This page allows you to export...'] = 'This page allows you to export  the product data to a file in <a href="http://en.wikipedia.org/wiki/Comma-separated_values" target="_blank">CSV</a> format.';
$_['Profile'] = 'Profile';

$_['no profiles present'] = 'no profiles present';
$_['Profiles may store export p...'] = 'Profiles may store export parameters to simplify management of different export configurations. 
            You can save export parameters to a profile on the next step';
$_['General'] = 'General';
$_['Extra'] = 'Extra';
$_['Field Delimiter'] = 'Field Delimiter';
$_['tab'] = 'tab';
$_['semicolon'] = 'semicolon';
$_['comma'] = 'comma';
$_['the \'tab\' delimiter is reco...'] = 'the \'tab\' delimiter is recommended for MS Excel users';
$_['File Charset'] = 'File Charset';
$_['select from predefined values'] = 'select from predefined values';
$_['You have to keep in mind th...'] = 'You have to keep in mind the charset of the export file. UTF-8 is the most universal format suitable for any language.';
$_['Category Separator'] = 'Category Separator';
$_['Image Path'] = 'Image Path';
$_['Server Path'] = 'Server Path';
$_['URL'] = 'URL';
$_['Products From Categories'] = 'Products From Categories';
$_['All'] = 'All';
$_['Selected'] = 'Selected';
$_['Categories'] = 'Categories';
$_['Select All'] = 'Select All';
$_['Unselect All'] = 'Unselect All';
$_['Products From Manufacturers'] = 'Products From Manufacturers';
$_['Manufacturers'] = 'Manufacturers';
$_['Copy the exported file to'] = 'Copy the exported file to';
$_['it may be used when the exp...'] = 'it may be used when the export results should be allowed to download by other users';
$_['Language'] = 'Language';
$_['Currency'] = 'Currency';
$_['Store'] = 'Store';
$_['Apply Taxes to Prices'] = 'Apply Taxes to Prices';
$_['Use Taxes for Geo Zone'] = 'Use Taxes for Geo Zone';
$_['Use special/discounted price'] = 'Use special/discounted price';
$_['main product price will be ...'] = 'main product price will be replaced with special/discounted price if they are available. It should be used for generating public listings only.';
$_['Use Prices for Customer Group'] = 'Use Prices for Customer Group';
$_['STEP 2 of 3'] = 'STEP 2 of 3';
$_['Back'] = 'Back';
$_['Next'] = 'Next';
$_['Attributes'] = 'Attributes';
$_['Filters'] = 'Filters';
$_['Options'] = 'Options';
$_['Discounts'] = 'Discounts';
$_['Specials'] = 'Specials';
$_['Reward Points'] = 'Reward Points';
$_['Check fields to include the...'] = 'Check fields to include them into the export file.';
$_['Product Field'] = 'Product Field';
$_['Column in File'] = 'Column in File';
$_['all'] = 'all';
$_['none'] = 'none';
$_['Notes'] = 'Notes';
$_['Check attributes to include...'] = 'Check attributes to include them into the export file.';
$_['Atribute Name'] = 'Atribute Name';
$_['Include'] = 'Include';
$_['Attribute Group'] = 'Attribute Group';
$_['Check filters to include th...'] = 'Check filters to include them into the export file.';
$_['Filter Group'] = 'Filter Group';
$_['Check options to include th...'] = 'Check options to include them into the export file.';
$_['Option Name'] = 'Option Name';
$_['Type'] = 'Type';
$_['Check discounts to include ...'] = 'Check discounts to include them into the export file.';
$_['Field'] = 'Field';
$_['Check special prices to inc...'] = 'Check special prices to include them into the export file.';
$_['Check reward points to incl...'] = 'Check reward points to include them into the export file.';
$_['STEP 3 of 3'] = 'STEP 3 of 3';
$_['Stop'] = 'Stop';
$_['Continue'] = 'Continue';
$_['Done'] = 'Done';
$_['Export is in progress'] = 'Export is in progress';
$_['The export statistics updat...'] = 'The export statistics updates every <? echo $update_interval; ?> seconds. Please do not close the window.';
$_['Completion at'] = 'Completion at';
$_['File Size'] = 'File Size';
$_['Time Passed'] = 'Time Passed';
$_['Lines Processed'] = 'Lines Processed';
$_['Products Processed'] = 'Products Processed';
$_['download'] = 'download';
$_['Export messages'] = 'Export messages';
$_['Autoscrolling'] = 'Autoscrolling';
$_['Export stopped'] = 'Export stopped';
$_['Export is complete!'] = 'Export is complete!';
$_['Export has been stopped'] = 'Export has been stopped';
$_['Server error (status='] = 'Server error (status=';
$_['Temporary connection problems.'] = 'Temporary connection problems.';

$_['Model'] = 'Model';
$_['A unique product code required by Opencart'] = 'A unique product code required by Opencart';
$_['UPC'] = 'UPC';
$_['Universal Product Code'] = 'Universal Product Code';
$_['Name'] = 'Name';
$_['Product name'] = 'Product name';
$_['Description'] = 'Description';
$_['Product description'] = 'Product description';
$_['Category'] = 'Category';
$_['Full category path. If the field is not defined or empty then the default category will be used.'] = 'Full category path. If the field is not defined or empty then the default category will be used.';
$_['Location'] = 'Location';
$_['Quantity'] = 'Quantity';
$_['Minimum Quantity'] = 'Minimum Quantity';
$_['Subtract Stock'] = 'Subtract Stock';
$_['Out of Stock Status'] = 'Out of Stock Status';
$_['Requires Shipping'] = 'Requires Shipping';
$_['Status'] = 'Status';
$_['Image file'] = 'Image file';
$_['Relative server directory path or URL'] = 'Relative server directory path or URL';
$_['Additional Image files'] = 'Additional Image files';
$_['Manufacturer'] = 'Manufacturer';
$_['Manufacturer name'] = 'Manufacturer name';
$_['Price'] = 'Price';
$_['Regular product price in the selected currency'] = 'Regular product price in the selected currency';
$_['Tax class'] = 'Tax class';
$_['Tax class name'] = 'Tax class name';
$_['Weight'] = 'Weight';
$_['Length'] = 'Length';
$_['Width'] = 'Width';
$_['Height'] = 'Height';
$_['Meta tag keywords'] = 'Meta tag keywords';
$_['Meta tag description'] = 'Meta tag description';
$_['Points Required'] = 'Points Required';
$_['Number of reward points required to make purchase'] = 'Number of reward points required to make purchase';
$_['Sort Order'] = 'Sort Order';
$_['SEO Keyword'] = 'SEO Keyword';
$_['Product Tags'] = 'Product Tags';
$_['List of product tags separated by comma'] = 'List of product tags separated by comma';
$_['Date Available'] = 'Date Available';
$_['Format: YYYY-MM-DD'] = 'Format: YYYY-MM-DD';
$_['Product URL'] = 'Product URL';
$_['Date Added'] = 'Date Added';
$_['Time when the product was added to the store (Format: YYYY-MM-DD HH:MM:SS)'] = 'Time when the product was added to the store (Format: YYYY-MM-DD HH:MM:SS)';
$_['Date Modified'] = 'Date Modified';
$_['Last time when product was modified (Format: YYYY-MM-DD HH:MM:SS)'] = 'Last time when product was modified (Format: YYYY-MM-DD HH:MM:SS)';
$_['Related Product'] = 'Related Product';
$_['model identifiers of related products'] = 'model identifiers of related products';
$_['European Article Number'] = 'European Article Number';
$_['Japanese Article Number'] = 'Japanese Article Number';
$_['Customer Group'] = 'Customer Group';
$_['Prioirity'] = 'Prioirity';
$_['Date Start'] = 'Date Start';
$_['Date End'] = 'Date End';
$_['Reward Points'] = 'Reward Points';

$_['Exclude inactive products'] = 'Exclude inactive products';
$_['Exclude products with zero quantity'] = 'Exclude products with zero quantity';

?>