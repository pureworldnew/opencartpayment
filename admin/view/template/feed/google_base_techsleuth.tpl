<?php 
//////////////////////////////////
// Author:  Joshua J. Gomes
// E-mail:  josh@techsleuth.com
// Web:  http://www.techsleuth.com
//////////////////////////////////
?>
<?php 
header('Content-Type:text/html; charset=UTF-8');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: Fri, 1 Jan 1999 05:00:00 GMT');
if(defined('_JEXEC')){
define('IS_MIJOSHOP', 1);  // mijoshop environment
}else{
define('IS_MIJOSHOP', 0);  // non-mijoshop environment
}
$original_error_reporting_value=ini_get('error_reporting');
error_reporting(0); //temporarily disable error reporting so warnings are not displayed
ini_set('max_execution_time', 18000); //3600=1 hour
ini_set('memory_limit', '2048M'); //2048M=maximum number of megabytes
ini_set('error_reporting',$original_error_reporting_value); //return error reporting to original value
define('PRODUCT_NAMES_START',0);
define('PRODUCT_NAMES_LIMIT',100);//use increments of 10
define('PRODUCT_ATTRIBUTES_START',0);
define('PRODUCT_ATTRIBUTES_LIMIT',100);//use increments of 10
define('PRODUCT_CATEGORIES_START',0);
define('PRODUCT_CATEGORIES_LIMIT',100);//use increments of 10
define('PRODUCT_OPTIONS_START',0);
define('PRODUCT_OPTIONS_LIMIT',100);//use increments of 10
?>
<?php echo $header; ?>
<?php 
define('THIS_SERVER_URL', get_server_url()); /* server url */
define('THIS_CATALOG_URL', get_catalog_url()); /* catalog url */
define('THIS_IMAGE_URL', get_image_url()); /* image url */
if(!IS_MIJOSHOP==1){
define('OPENCART_ADMIN_DIRECTORY_URL',THIS_SERVER_URL); /* normal admin directory url */
}else{
define('OPENCART_ADMIN_DIRECTORY_URL',THIS_CATALOG_URL.'components/com_mijoshop/opencart/admin/'); /* mijoshop admin directory url */
}
if(!defined('JG_4810C')){define('JG_4810C',$this->jg_cse4yo());}
jg_lj610();
jg_fiofw();
jg_q561v();
switch (VERSION)
{
case (VERSION=='1.4.7'||VERSION=='1.4.8'||VERSION=='1.4.9'||VERSION=='1.4.9.1'||VERSION=='1.4.9.2'||VERSION=='1.4.9.3'||VERSION=='1.4.9.4'||VERSION=='1.4.9.5'||VERSION=='1.4.9.6'):
break;
case (VERSION=='1.5.0'||VERSION=='1.5.0.1'||VERSION=='1.5.0.2'||VERSION=='1.5.0.3'||VERSION=='1.5.0.4'||VERSION=='1.5.0.5'||VERSION=='1.5.1'||VERSION=='1.5.1.1'||VERSION=='1.5.1.2'||VERSION=='1.5.1.3'||VERSION=='1.5.2'||VERSION=='1.5.2.1'||VERSION=='1.5.3'||VERSION=='1.5.3.1'||VERSION=='1.5.4'||VERSION=='1.5.4.1'||VERSION=='1.5.5'||VERSION=='1.5.5.1'||VERSION=='1.5.6'||VERSION=='1.5.6.1'):
echo "<div id=\"content\">";
echo "<div class=\"breadcrumb\">";
foreach ($breadcrumbs as $breadcrumb) {
echo $breadcrumb['separator']."<a href=\"".$breadcrumb['href']."\">".$breadcrumb['text']."</a>";
}
echo "</div>";
break;
default:
require JG_4810C;
echo "<h2>".$_['text_extension_title']." v".$_['text_extension_version']."</h2><b>".$_['text_opencart_version']." ".VERSION."</b><br />".$_['text_unsupported_version_of_opencart'].":  <a href=\"http://www.opencart.com/index.php?route=extension/extension/info&extension_id=3261\">http://www.opencart.com/index.php?route=extension/extension/info&extension_id=3261</a>";
exit;
break;
}
?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php
define("JG_BDW3T", '<img style="vertical-align: middle; cursor: pointer; margin-right: 0px;" src="'.THIS_IMAGE_URL.'data/google-merchant-center-feed/accordion-collapse.png" lowsrc="'.THIS_IMAGE_URL.'data/google-merchant-center-feed/accordion-collapse.png" title="'.$text_collapse_view.'" />');
define("JG_QTRCN", '<img style="vertical-align: middle; cursor: pointer; margin-right: 0px;" src="'.THIS_IMAGE_URL.'data/google-merchant-center-feed/accordion-expand.png" lowsrc="'.THIS_IMAGE_URL.'data/google-merchant-center-feed/accordion-expand.png" title="'.$text_expand_view.'" />');
function get_catalog_url()
{
$this_url='';
if (empty($_SERVER['HTTPS'])) {
$this_url=HTTP_CATALOG;
}
else
{
$this_url=HTTPS_CATALOG;
}
return $this_url;
}
function get_server_url()
{
$this_url='';
if (empty($_SERVER['HTTPS'])) {
$this_url=HTTP_SERVER;
}
else
{
$this_url=HTTPS_SERVER;
}
return $this_url;
}
function get_image_url()
{
$this_url='';
if (empty($_SERVER['HTTPS'])) {
$this_url=HTTP_CATALOG;
}
else
{
$this_url=HTTPS_CATALOG;
}
if(!IS_MIJOSHOP==1)
{
$this_url=$this_url.'image/';
}
else
{
$this_url=$this_url.'components/com_mijoshop/opencart/image/';
}
return $this_url;
}
function jg_cse4yo()
{
$jg_pxcfn=DIR_LANGUAGE.jg_poxic(jg_1t2kj()).'/feed/'.JG_9TVQEW.'.php';
$jg_esxlzp=$jg_pxcfn;
if (!file_exists($jg_esxlzp))
{
$jg_esxlzp='language/'.jg_poxic(jg_1t2kj()).'/feed/'.JG_9TVQEW.'.php';
}
if (!file_exists($jg_esxlzp))
{
$jg_esxlzp='language/english/feed/'.JG_9TVQEW.'.php';
}
if (!file_exists($jg_esxlzp))
{
$jg_esxlzp=$jg_pxcfn;
echo 'Unable to locate the extension language config file:  "'.$jg_esxlzp.'".&nbsp;&nbsp;Please report the problem to:  <a href="mailto:%6a%6f%73%68%40%74%65%63%68%73%6c%65%75%74%68%2e%63%6f%6d&subject='.rawurlencode('OpenCart extension issue').'&body='.rawurlencode('Unable to locate the extension language config file:  "'.$jg_esxlzp.'"').'">josh@techsleuth.com</a>';
}
return $jg_esxlzp;
}
function jg_9ufv6()
{
echo "<tr>";
echo "<td style=\"padding-left: 0px; padding-right: 0px; padding-bottom: 0px !important;\">";
echo "<table class=\"list\" style=\"margin-bottom: 10px !important; margin-top: 0px; width: auto !important;\"><tr><td style=\"margin-top: 0px; padding-left: 5px !important; padding-right: 0px; padding-top: 5px !important; padding-bottom: 5px !important; background: #ffffff url('view/image/field.png') repeat-x; cursor: pointer; width: auto;\" onclick=\"jg_l1qfg();return false;\">";
require JG_4810C;
echo "<div style=\"font-weight: bold; font-size: 120%;white-space: nowrap; width: auto;\" colspan=2>".$_['text_options']."<span style=\"padding-left: 8px; margin-right: 5px !important;\" id=\"show_options_section\"><span id=\"jg_lsq1e\">".JG_QTRCN."</span></span></div>";
echo "</td>";
echo "</tr>";
echo "</table>";
echo "<div id=\"options_section\" style=\"display: none; white-space: nowrap; overflow: visible; width: auto;\"></div>";
}
function jg_6lq4b()
{
echo "<tr>";
echo "<td style=\"padding-left: 0px; padding-right: 0px; padding-bottom: 0px !important;\">";
echo "<table class=\"list\" style=\"margin-bottom: 10px !important; margin-top: 0px; width: auto !important;\"><tr><td style=\"margin-top: 0px; padding-left: 5px !important; padding-right: 0px; padding-top: 5px !important; padding-bottom: 5px !important; background: #ffffff url('view/image/field.png') repeat-x; cursor: pointer; width: auto;\" onclick=\"jg_fv8ys();return false;\">";
require JG_4810C;
echo "<div style=\"font-weight: bold; font-size: 120%;white-space: nowrap; width: auto;\" colspan=2>".$_['text_frequently_asked_questions']."<span style=\"padding-left: 8px; margin-right: 5px !important;\" id=\"show_frequently_asked_questions_section\"><span id=\"toggle_show_frequently_asked_questions_section_text\">".JG_QTRCN."</span></span></div>";
echo "</td>";
echo "</tr>";
echo "</table>";
echo "<div id=\"frequently_asked_questions\" style=\"display: none; white-space: normal; width: 700px;\">";
echo "<ol>";
echo "<li style=\"list-style-type: decimal; font-weight: bold; margin-bottom: 8px;\">";
echo "How do I translate this to another language?<br />";
echo "¿Cómo traducir esto a otro idioma?<br />";
echo "Como faço para traduzir isso para uma outra língua?<br />";
echo "Wie übersetze ich hier eine andere Sprache?<br />";
echo "Come posso tradurre questo in un'altra lingua?<br />";
echo "Comment puis-je traduire cela à une autre langue?<br />";
echo "Hoe kan ik dit vertalen naar een andere taal?<br />";
echo "Как перевести это на другой язык?<br />";
echo "我如何轉化到另一種語言？<br />";
echo "どうすれば他の言語にこれを翻訳するのですか？<br />";
echo "</li>";
echo "<ol style=\"margin-bottom: 10px;\">";
echo "<li style=\"list-style-type: none;\">Google Translate</li>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\"><a href=\"http://translate.google.com\" target=\"_blank\">http://translate.google.com</a></li>";
echo "</ol>";
echo "<ol style=\"margin-bottom: 10px;\">";
echo "<li style=\"list-style-type: none;\">Microsoft Translator</li>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\"><a href=\"http://www.microsofttranslator.com\" target=\"_blank\">http://www.microsofttranslator.com</a></li>";
echo "</ol>";
echo "<li style=\"list-style-type: decimal; font-weight: bold; margin-bottom: 8px;\">How do I assign a Google attribute to an OpenCart field?</li>";
echo "<ol style=\"margin-bottom: 10px;\">";
echo "<li style=\"list-style-type: lower-alpha;\">Log into your store admin area and click Extensions, \"Product Feeds\", Edit \"".$_['heading_title']."\".</li>";
echo "<li style=\"list-style-type: lower-alpha;\">View the \"".$_['text_quick_configuration']."\" section.</li>";
echo "<li style=\"list-style-type: lower-alpha;\">Select an OpenCart Field from the list (Product, Product Attribute, Product Category or Product Option).</li>";
echo "<li style=\"list-style-type: lower-alpha;\">Select a value for the OpenCart Field.</li>";
echo "<li style=\"list-style-type: lower-alpha;\">Select a Google Attribute to assign to the OpenCart Field.</li>";
echo "<li style=\"list-style-type: lower-alpha;\">Select or enter a value for the Google Attribute.</li>";
echo "<li style=\"list-style-type: lower-alpha;\">Click the ".$_['text_add']." button (green plus sign <img style=\"vertical-align: middle; cursor: pointer;\" title=\"".$_['text_add']."\" src=\"".THIS_IMAGE_URL."data/google-merchant-center-feed/edit-add.png\" lowsrc=\"".THIS_IMAGE_URL."data/google-merchant-center-feed/edit-add.png\" /> ).</li>";
echo "<li style=\"list-style-type: lower-alpha;\">Repeat until you have assigned as many Google Attributes as needed.</li>";
echo "</ol>";
echo "<li style=\"list-style-type: decimal; font-weight: bold; margin-bottom: 8px;\">How do I assign a Google product category to an OpenCart product category?</li>";
echo "<ol style=\"margin-bottom: 10px;\">";
echo "<li style=\"list-style-type: lower-alpha;\">Log into your store admin area and click Extensions, \"Product Feeds\", Edit \"".$_['heading_title']."\".</li>";
echo "<li style=\"list-style-type: lower-alpha;\">View the \"".$_['text_quick_configuration']."\" section.</li>";
echo "<li style=\"list-style-type: lower-alpha;\">Select the OpenCart Field \"Product Category\" from the list.</li>";
echo "<li style=\"list-style-type: lower-alpha;\">Select a value for the OpenCart Product Category.</li>";
echo "<li style=\"list-style-type: lower-alpha;\">Select the Google Attribute \"Google Product Category\".</li>";
echo "<li style=\"list-style-type: lower-alpha;\">Select a value for the Google Product Category.</li>";
echo "<li style=\"list-style-type: lower-alpha;\">Click the ".$_['text_add']." button (green plus sign <img style=\"vertical-align: middle; cursor: pointer;\" title=\"".$_['text_add']."\" src=\"".THIS_IMAGE_URL."data/google-merchant-center-feed/edit-add.png\" lowsrc=\"".THIS_IMAGE_URL."data/google-merchant-center-feed/edit-add.png\" /> ).</li>";
echo "<li style=\"list-style-type: lower-alpha;\">Repeat until you have assigned as many categories as needed.</li>";
echo "</ol>";
echo "<li style=\"list-style-type: decimal; font-weight: bold; margin-bottom: 8px;\">How do I assign a Google attribute to an OpenCart product option?</li>";
echo "<ol style=\"margin-bottom: 10px;\">";
echo "<li style=\"list-style-type: lower-alpha;\">Log into your store admin area and click Extensions, \"Product Feeds\", Edit \"".$_['heading_title']."\".</li>";
echo "<li style=\"list-style-type: lower-alpha;\">View the \"".$_['text_quick_configuration']."\" section.</li>";
echo "<li style=\"list-style-type: lower-alpha;\">Select the OpenCart Field \"Product Option\" from the list.</li>";
echo "<li style=\"list-style-type: lower-alpha;\">Select a value for the OpenCart Product Option.</li>";
echo "<li style=\"list-style-type: lower-alpha;\">Select the Google Attribute \"Custom\".</li>";
echo "<li style=\"list-style-type: lower-alpha;\">Enter the Attribute name.</li>";
echo "<li style=\"list-style-type: lower-alpha;\">Enter the Attribute value or click the \"".$_['text_use_opencart_field_value']."\" button (database icon <img style=\"vertical-align: middle; cursor: pointer;\" title=\"".$_['text_use_opencart_field_value']."\" src=\"".THIS_IMAGE_URL."data/google-merchant-center-feed/use-database-value.png\" lowsrc=\"".THIS_IMAGE_URL."data/google-merchant-center-feed/use-database-value.png\" /> ) to use the option value from the OpenCart database.</li>";
echo "<li style=\"list-style-type: lower-alpha;\">Click the ".$_['text_add']." button (green plus sign <img style=\"vertical-align: middle; cursor: pointer;\" title=\"".$_['text_add']."\" src=\"".THIS_IMAGE_URL."data/google-merchant-center-feed/edit-add.png\" lowsrc=\"".THIS_IMAGE_URL."data/google-merchant-center-feed/edit-add.png\" /> ).</li>";
echo "<li style=\"list-style-type: lower-alpha;\">Repeat until you have assigned as many options as needed.</li>";
echo "</ol>";
echo "<li style=\"list-style-type: decimal; font-weight: bold; margin-bottom: 8px;\">How do I assign a Google attribute to an OpenCart product attribute?</li>";
echo "<ol style=\"margin-bottom: 10px;\">";
echo "<li style=\"list-style-type: lower-alpha;\">Log into your store admin area and click Extensions, \"Product Feeds\", Edit \"".$_['heading_title']."\".</li>";
echo "<li style=\"list-style-type: lower-alpha;\">View the \"".$_['text_quick_configuration']."\" section.</li>";
echo "<li style=\"list-style-type: lower-alpha;\">Select the OpenCart Field \"Product Attribute\" from the list.</li>";
echo "<li style=\"list-style-type: lower-alpha;\">Select a value for the OpenCart Product Attribute.</li>";
echo "<li style=\"list-style-type: lower-alpha;\">Select the Google Attribute \"Custom\".</li>";
echo "<li style=\"list-style-type: lower-alpha;\">Enter the Attribute name.</li>";
echo "<li style=\"list-style-type: lower-alpha;\">Enter the Attribute value or click the \"".$_['text_use_opencart_field_value']."\" button (database icon <img style=\"vertical-align: middle; cursor: pointer;\" title=\"".$_['text_use_opencart_field_value']."\" src=\"".THIS_IMAGE_URL."data/google-merchant-center-feed/use-database-value.png\" lowsrc=\"".THIS_IMAGE_URL."data/google-merchant-center-feed/use-database-value.png\" /> ) to use the attribute value from the OpenCart database.</li>";
echo "<li style=\"list-style-type: lower-alpha;\">Click the ".$_['text_add']." button (green plus sign <img style=\"vertical-align: middle; cursor: pointer;\" title=\"".$_['text_add']."\" src=\"".THIS_IMAGE_URL."data/google-merchant-center-feed/edit-add.png\" lowsrc=\"".THIS_IMAGE_URL."data/google-merchant-center-feed/edit-add.png\" /> ).</li>";
echo "<li style=\"list-style-type: lower-alpha;\">Repeat until you have assigned as many attributes as needed.</li>";
echo "</ol>";
echo "<li style=\"list-style-type: decimal; font-weight: bold; margin-bottom: 8px;\">How do I exclude a product from appearing on the data feed?</li>";
echo "<ol style=\"margin-bottom: 10px;\">";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">To exclude a product from appearing on the data feed entirely:</li>";
echo "<ol style=\"margin-bottom: 8px;\">";
echo "<li style=\"list-style-type: none;\">Assign an attribute named \"skip_product\" to a product.&nbsp;&nbsp;This can be easily done using the \"Skip product\" button (fast forward icon <img style=\"vertical-align: middle; cursor: pointer;\" title=\"".$_['text_skip_product']."\" src=\"".THIS_IMAGE_URL."data/google-merchant-center-feed/skip-product.png\" lowsrc=\"".THIS_IMAGE_URL."data/google-merchant-center-feed/skip-product.png\" /> ).&nbsp;&nbsp;See Frequently Asked Questions #2-5 for help with assigning custom Google attributes.</li>";
echo "</ol>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">If a product's status is set to \"Disabled\" in OpenCart it will not appear on the data feed.&nbsp;&nbsp;Products that have a quantity of \"0\" but are still enabled will appear on the feed but by default will be assigned the availability attribute \"out of stock\".</li>";
echo "<li style=\"list-style-type: none;\">The excluded_destination attribute can be used to exclude products from being submitted to Product Search, Product Ads or Commerce Search. See Google's Products Feed Specification for details:  http://support.google.com/merchants/bin/answer.py?hl=en&answer=188494.</li>";
echo "</ol>";
echo "<li style=\"list-style-type: decimal; font-weight: bold; margin-bottom: 8px;\">What reports are available and how can I run them?</li>";
echo "<ol style=\"margin-bottom: 10px;\">";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">Log into your store admin area and click Extensions, \"Product Feeds\", Edit \"".$_['heading_title']."\".</li>";
echo "<ol style=\"margin-bottom: 8px;\">";
echo "<li style=\"list-style-type: decimal; margin-bottom: 8px;\">Server memory usage by product</li>";
echo "<ol style=\"margin-bottom: 8px;\">";
echo "<li style=\"list-style-type: lower-alpha; margin-bottom: 8px;\">For XML RSS 2.0 format:  View the ".$_['text_data_feeds']." section, Reports column for the selected store and click the button titled \"".$_['text_view_report_of_server_memory_usage_by_product'].": XML RSS 2.0 ".$_['text_format']."\" (report icon <img style=\"vertical-align: middle; cursor: pointer;\" title=\"".$_['text_view_report_of_server_memory_usage_by_product'].": XML RSS 2.0 ".$_['text_format']."\" src=\"".THIS_IMAGE_URL."data/google-merchant-center-feed/report-memory-usage-rss.png\" lowsrc=\"".THIS_IMAGE_URL."data/google-merchant-center-feed/report-memory-usage-rss.png\" /> ).</li>";
echo "<li style=\"list-style-type: lower-alpha;\">For TSV format:  View the ".$_['text_data_feeds']." section, Reports column for the selected store and click the button titled \"".$_['text_view_report_of_server_memory_usage_by_product'].": TSV ".$_['text_format']."\" (report icon <img style=\"vertical-align: middle; cursor: pointer;\" title=\"".$_['text_view_report_of_server_memory_usage_by_product'].": TSV ".$_['text_format']."\" src=\"".THIS_IMAGE_URL."data/google-merchant-center-feed/report-memory-usage-tsv.png\" lowsrc=\"".THIS_IMAGE_URL."data/google-merchant-center-feed/report-memory-usage-tsv.png\" /> ).</li>";
echo "</ol>";
echo "<li style=\"list-style-type: decimal; margin-bottom: 8px;\">Product names by row and id number</li>";
echo "<ol>";
echo "<li style=\"list-style-type: lower-alpha;\">View the ".$_['text_data_feeds']." section, Reports column for the selected store and click the button titled \"".$_['text_view_report_of_product_names_by_row_and_id_number']."\" (report icon <img style=\"vertical-align: middle; cursor: pointer;\" title=\"".$_['text_view_report_of_product_names_by_row_and_id_number']."\" src=\"".THIS_IMAGE_URL."data/google-merchant-center-feed/report-product-names.png\" lowsrc=\"".THIS_IMAGE_URL."data/google-merchant-center-feed/report-product-names.png\" /> ).</li>";
echo "</ol>";
echo "</ol>";
echo "</ol>";
echo "<li style=\"list-style-type: decimal; font-weight: bold; margin-bottom: 8px;\">When I download and open the TSV file in Microsoft Excel 2007, it displays some characters wrong.&nbsp;&nbsp;For example the character \"ó\" looks like \"Ã³\".&nbsp;&nbsp;Is this normal??</li>";
echo "<ol style=\"margin-bottom: 10px;\">";
echo "<li style=\"list-style-type: none;\">Excel is not opening the file in the proper data format.&nbsp;&nbsp;To fix:</li>";
echo "<ol style=\"margin-bottom: 10px;\">";
echo "<li style=\"list-style-type: decimal;\">Open Excel 2007.</li>";
echo "<li style=\"list-style-type: decimal;\">Open a new blank workbook.</li>";
echo "<li style=\"list-style-type: decimal;\">Select Data from the menu / ribbon.</li>";
echo "<li style=\"list-style-type: decimal;\">Click the \"From Text\" button.</li>";
echo "<li style=\"list-style-type: decimal;\">Select the TSV file.</li>";
echo "<li style=\"list-style-type: decimal;\">Select \"Delimited\" and click Next.</li>";
echo "<li style=\"list-style-type: decimal;\">Select Tab so it is the only delimiter.</li>";
echo "<li style=\"list-style-type: decimal;\">Set the text qualifier to \"{none}\".</li>";
echo "<li style=\"list-style-type: decimal;\">Click Finish, OK.</li>";
echo "</ol>";
echo "</ol>";
echo "<li style=\"list-style-type: decimal; font-weight: bold; margin-bottom: 8px;\">Does this extension require special directory permissions?</li>";
echo "<ol style=\"margin-bottom: 10px;\">";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">This extension needs to be able to write files within the OpenCart root and admin directories in order to work normally.&nbsp;&nbsp;The directories are:</li>";
echo "<ol>";
echo "<li style=\"list-style-type: none; margin-bottom: 0px;\">\"".THIS_CATALOG_URL."\"</li>";
echo "</ol>";
echo "<ol>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">\"".THIS_SERVER_URL."\"</li>";
echo "</ol>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">If the option \"Use SEF URLs for Data Feed URLs list\" is checked, write permissions are also required for the following directory:</li>";
echo "<ol>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">\"".THIS_SERVER_URL."google-merchant-center-feeds/\"</li>";
echo "</ol>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">If you do receive permissions related errors, it is probably because the permissions on your server for the affected directory do not allow PHP to create files within it, so this extension cannot create the files it uses to save settings or direct SEF URLs.&nbsp;&nbsp;If you check your PHP error log you may find messages like \"DOMDocument::save failed due to Permission denied\" caused when the extension tries to save one of the XML files.&nbsp;&nbsp;Some installations of PHP are implemented as an Apache module which is desirable since it increases speed and performance, but also in that situation a directory must have permissions set to 777 to allow PHP to create files within it.&nbsp;&nbsp;Once the necessary files have been created, locking the permissions back down to 755 should still allow the contents to be read and written to while maximizing security.&nbsp;&nbsp;A copy of the default XML settings files are available <a href=\"http://www.techsleuth.com/google-merchant-center-feed-for-opencart-files/xml.settings.default.v".$_['text_extension_version'].".zip\" target=\"_blank\">here</a>.</li>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">Article referenced:</li>";
echo "<ol style=\"margin-bottom: 10px;\">";
echo "<li style=\"list-style-type: none;\">Website Security Precautions</li>";
echo "<li style=\"list-style-type: none;\"><a href=\"http://25yearsofprogramming.com/blog/20070705.htm\" target=\"_blank\">http://25yearsofprogramming.com/blog/20070705.htm</a></li>";
echo "</ol>";
echo "</ol>";
echo "<li style=\"list-style-type: decimal; font-weight: bold; margin-bottom: 8px;\">When I try to view my product feed I receive the message:&nbsp;&nbsp;\"Fatal error: Allowed memory size of 67108864 bytes exhausted (tried to allocate 9865674 bytes)\".&nbsp;&nbsp;Why?</li>";
echo "<ol style=\"margin-bottom: 10px;\">";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">That message means that your web server's 64MB memory limit for PHP was exceeded.&nbsp;&nbsp;Most likely there are more products than fit on one data feed page, so you may need to split the feed into multiple pages.</li>";
echo "<ol>";
echo "<li style=\"list-style-type: decimal;\">Log into your store admin area and click Extensions, \"Product Feeds\", Edit \"".$_['heading_title']."\".</li>";
echo "<li style=\"list-style-type: decimal;\">View the \"".$_['text_data_feeds']."\" section.</li>";
echo "<li style=\"list-style-type: decimal; margin-bottom: 8px;\">Go to the target country row of your choice and select a number of products per page from the \"".$_['text_products_per_page']."\" column.&nbsp;&nbsp;Normally several hundred or 1,000 should work with 64MB allocated to PHP.</li>";
echo "</ol>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">It may be worthwhile to note that generating large data feeds requires proportional web server resources.&nbsp;&nbsp;If your ISP allows, you also may wish to consider raising the PHP memory limit to 128MB in order to fit all the products on one data feed page.</li>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">The following PHP settings are recommended for stores with large data feeds:</li>";
echo "<ol>";
echo "<li style=\"list-style-type: none;\">memory_limit=512M</li>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">max_execution_time=600</li>";
echo "</ol>";
echo "<li style=\"list-style-type: none;\">These values can be adjusted as needed.</li>";
echo "</ol>";
echo "<li style=\"list-style-type: decimal; font-weight: bold; margin-bottom: 8px;\">After submitting my product feed to Google, I receive a message about an \"XML formatting error\".&nbsp;&nbsp;Also when I try to view my product feed using Firefox I receive a blank browser window.&nbsp;&nbsp;The source code shows that the XML data is there but nothing displays.&nbsp;&nbsp;What is wrong?</li>";
echo "<ol style=\"margin-bottom: 10px;\">";
echo "<li style=\"list-style-type: decimal; margin-bottom: 8px;\">There may be non-compliant character entities within the product or category data.&nbsp;&nbsp;This can cause the data feed to appear blank when viewed in an internet browser.&nbsp;&nbsp;When submitting the feed to Google this can also cause a warning \"XML formatting error\" that prevents the data feed from being accepted.&nbsp;&nbsp;When products and categories are saved using the OpenCart dialog there is no problem.&nbsp;&nbsp;However, sometimes when data is imported into OpenCart invalid characters are introduced.&nbsp;&nbsp;There may only be a few instances or it may be more.&nbsp;&nbsp;XML formatting errors can be located and fixed using the Opera browser.&nbsp;&nbsp;To fix the problem, you can go to ".$_['text_options'].", ".$_['text_data_feeds']." and check the checkbox titled \"".$_['text_convert_non_compliant_character_entities']."\" which will attempt to convert the most common non-compliant characters.&nbsp;&nbsp;If you do identify any non-compliant character entities that are not automatically fixed by turning on the option, please feel free to let me know what they are and I will add support for them.</li>";
echo "<li style=\"list-style-type: decimal; margin-bottom: 8px;\">If the problem persists it may be due to lone ampersands \"&amp;\" which are normally stored in the OpenCart database as \"&amp;amp;\".&nbsp;&nbsp;Lone ampersands are not automatically converted, since converting them breaks existing XML entities.&nbsp;&nbsp;To fix the problem at the cost of breaking / scrambling existing XML entities, check the option \"".$_['text_correct_lone_ampersands_in_product_names_and_descriptions']."\".&nbsp;&nbsp;An alternative would be to use the TSV data feed format since it is not affected by lone ampersands and reserved XML character entities.</li>";
echo "<li style=\"list-style-type: decimal; margin-bottom: 8px;\">Please note that it is best practice to remove non XML compliant characters from the OpenCart database since some can potentially cause serious problems resulting in corruption or loss of data.&nbsp;&nbsp;This is usually done by simply re-saving any affected records which converts non compliant character entities to their compliant values.&nbsp;&nbsp;Fixing the problem that way would also prevent possible future errors or incompatibilities with other services / extensions.</li>";
echo "<li style=\"list-style-type: decimal; margin-bottom: 8px;\">If you are repeatedly importing product data into OpenCart then character encodings should be corrected within the data to be imported.&nbsp;&nbsp;It is recommended to use a text editor or similar tool to find and replace any non compliant characters within the data you are planning to import with their XML friendly equivalent entity.&nbsp;&nbsp;For example the left angle bracket \"<\" is \"&amp;lt;\".&nbsp;&nbsp;Common literal or non compliant characters that can potentially cause issues would be ampersands, single and double left and right quotes, angle brackets, and many others.&nbsp;&nbsp;Some characters such as left single quotes can cause database errors so those are most problematic.&nbsp;&nbsp;Others will appear to work fine until they cause some noticeable issue.</li>";
echo "<ol style=\"margin-bottom: 8px;\">";
echo "<li style=\"list-style-type: none;\">Google article on the subject:</li>";
echo "<li style=\"list-style-type: none;\"><a href=\"http://217.96.43.25/support/forum/p/base/thread?tid=4655cd9513a6a732&hl=en&fid=4655cd9513a6a7320004871870d7691a&hltp=2\" target=\"_blank\">http://217.96.43.25/support/forum/p/base/thread?tid=4655cd9513a6a732&hl=en&fid=4655cd9513a6a7320004871870d7691a&hltp=2</a></li>";
echo "</ol>";
echo "<ol style=\"margin-bottom: 8px;\">";
echo "<li style=\"list-style-type: none;\">This HTML Encoder / Decoder may be useful for obtaining compliant character entities:</li>";
echo "<li style=\"list-style-type: none;\"><a href=\"http://www.web2generators.com/html/entities\" target=\"_blank\">http://www.web2generators.com/html/entities</a></li>";
echo "</ol>";
echo "<ol>";
echo "<li style=\"list-style-type: none;\">There is also a good list of character entities here:</li>";
echo "<li style=\"list-style-type: none;\"><a href=\"http://www.theukwebdesigncompany.com/articles/entity-escape-characters.php\" target=\"_blank\">http://www.theukwebdesigncompany.com/articles/entity-escape-characters.php</a></li>";
echo "</ol>";
echo "</ol>";
echo "<li style=\"list-style-type: decimal; font-weight: bold; margin-bottom: 8px;\">When I log into Google Merchant Center and click on Data Feeds, I see the message: \"Invalid UPC value (16 warnings)\".&nbsp;&nbsp;What does this mean?</li>";
echo "<ol style=\"margin-bottom: 10px;\">";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">That message means that sixteen OpenCart products have a UPC value that does not match anything in Google's UPC database so they are not considered correct, or as Google describes they do not \"meet check digit\".&nbsp;&nbsp;Some of the values stored in the OpenCart UPC field (or SKU for OpenCart 1.5.0.x and earlier) may have spaces in them or have missing digits, or may be otherwise formatted incorrectly.</li>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">This extension submits the UPC field as a GTIN attribute.&nbsp;&nbsp;Global Trade Item Numbers (GTINs) include the Unique Product Identifiers UPC, EAN (in Europe), JAN (in Japan), and ISBN.&nbsp;&nbsp;For OpenCart UPC fields that already have correct values the following Google rule would apply:</li>";
echo "<ol>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">\"Please know that some UPC values do not meet check digit.&nbsp;&nbsp;Therefore, if you're including valid UPC values in your data feed, you may ignore the 'Invalid UPC value' error message and continue to upload your feed as normal.\"</li>";
echo "</ol>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">Articles referenced:</li>";
echo "<ol>";
echo "<li style=\"list-style-type: none;\">GTIN</li>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\"><a href=\"http://www.gtin.info\" target=\"_blank\">http://www.gtin.info</a></li>";
echo "</ol>";
echo "<ol style=\"margin-bottom: 10px;\">";
echo "<li style=\"list-style-type: none;\">Unique Product Identifiers</li>";
echo "<li style=\"list-style-type: none;\"><a href=\"http://www.google.com/support/merchants/bin/answer.py?hl=en_US&answer=160161\" target=\"_blank\">http://www.google.com/support/merchants/bin/answer.py?hl=en_US&answer=160161</a></li>";
echo "</ol>";
echo "</ol>";
echo "<li style=\"list-style-type: decimal; font-weight: bold; margin-bottom: 8px;\">I submitted the attribute \"age group\" but it is not being accepted.&nbsp;&nbsp;Why?</li>";
echo "<ol style=\"margin-bottom: 10px;\">";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">The tag for the Google attribute \"age group\" should be specified with an underscore between the words as \"age_group\".&nbsp;&nbsp;Currently there are two accepted values for the age group attribute, \"adult\" or \"kids\".</li>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">Articles referenced:</li>";
echo "<ol>";
echo "<li style=\"list-style-type: none;\">Products Feed Specification</li>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\"><a href=\"http://www.google.com/support/merchants/bin/answer.py?answer=188494\" target=\"_blank\">http://www.google.com/support/merchants/bin/answer.py?answer=188494</a></li>";
echo "</ol>";
echo "<ol>";
echo "<li style=\"list-style-type: none;\">Summary of Attribute Requirements</li>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\"><a href=\"http://www.google.com/support/merchants/bin/answer.py?answer=1344057\" target=\"_blank\">http://www.google.com/support/merchants/bin/answer.py?answer=1344057</a></li>";
echo "</ol>";
echo "</ol>";
echo "<li style=\"list-style-type: decimal; font-weight: bold; margin-bottom: 8px;\">I received a warning message about \"Missing shipping information\".&nbsp;&nbsp;What exactly is missing?</li>";
echo "<ol style=\"margin-bottom: 10px;\">";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">This message means you have not yet selected a default shipping method for the data feed's target country within your Google account.&nbsp;&nbsp;Per Google you can setup one shipping default per region.&nbsp;&nbsp;The UK is a different region than US, so it would need a separate shipping method specified.</li>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">For the US, carrier based shipping may be the best option.&nbsp;&nbsp;For regions other than the US, setting up weight based shipping tables to match the lowest cost carrier you use may be the easiest and most accurate option since the carrier based shipping is not available.&nbsp;&nbsp;The weight attribute is included on the product feed so as long as your products have an accurate weight input in OpenCart it should work fine.</li>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">The \"Default\" checkbox in Tax and shipping needs to be checked in order to activate the default shipping method.</li>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">You can use this extension to assign custom shipping attributes to any item on your data feed.&nbsp;&nbsp;See Frequently Asked Questions #2-5 for help with assigning custom Google attributes.&nbsp;&nbsp;These values will override your account-level default shipping method for that item.&nbsp;&nbsp;Some examples of valid shipping attributes are:</li>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">Nationwide shipping rate:</li>";
echo "<ol>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">US::ground:8.95 USD</li>";
echo "</ol>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">State-level shipping rate:</li>";
echo "<ol>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">US:CA:ground:7.95 USD</li>";
echo "</ol>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">Zipcode range shipping rate:</li>";
echo "<ol>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">US:943*:ground:6.95 USD</li>";
echo "</ol>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">Zipcode-level shipping rate:</li>";
echo "<ol>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">US:94343:ground:5.95 USD</li>";
echo "</ol>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">Articles referenced:</li>";
echo "<ol>";
echo "<li style=\"list-style-type: none;\">Tax & Shipping</li>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\"><a href=\"http://support.google.com/merchants/bin/answer.py?hl=en&answer=160162\" target=\"_blank\">http://support.google.com/merchants/bin/answer.py?hl=en&answer=160162</a></li>";
echo "</ol>";
echo "<ol>";
echo "<li style=\"list-style-type: none;\">Tax and shipping summary</li>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\"><a href=\"http://www.google.com/merchants/taxshippingsettings\" target=\"_blank\">http://www.google.com/merchants/taxshippingsettings</a></li>";
echo "</ol>";
echo "<ol>";
echo "<li style=\"list-style-type: none;\">Trying to enter foreign shipping tables…</li>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\"><a href=\"http://www.google.com/support/forum/p/base/thread?tid=55b0f5cb385e4e2e&hl=en\" target=\"_blank\">http://www.google.com/support/forum/p/base/thread?tid=55b0f5cb385e4e2e&hl=en</a></li>";
echo "</ol>";
echo "</ol>";
echo "<li style=\"list-style-type: decimal; font-weight: bold; margin-bottom: 8px;\">How do I schedule exporting the data feed as a file on the web server?</li>";
echo "<ol style=\"margin-bottom: 10px;\">";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">An HTTP request to the proper parameterized URL will initiate an export.&nbsp;&nbsp;An example URL would be:</li>";
echo "<ol style=\"margin-bottom: 8px;\">";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\"><a href=\"".THIS_CATALOG_URL."index.php?route=feed/".JG_9TVQEW."&target_country=us&items_per_page=20&page=1&language=en&output_format=xml&save_as_file=true\" target=\"_blank\">".THIS_CATALOG_URL."index.php?route=feed/".JG_9TVQEW."&target_country=us&items_per_page=20&page=1&language=en&output_format=xml&save_as_file=true</a></li>";
echo "</ol>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">Any script or cron job which can be scheduled to reliably navigate to the export URL at the needed interval can be used.&nbsp;&nbsp;There is a scheduled export script available for Microsoft Windows which can be downloaded at whichever website you purchased the extension from.&nbsp;&nbsp;It is the download titled \"Scheduled Product Feed File Export for Windows\".</li>";
echo "<ol style=\"margin-bottom: 8px;\">";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">If you purchased from OpenCart.com you can download here:</li>";
echo "<ol style=\"margin-bottom: 8px;\">";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\"><a href=\"http://www.opencart.com/index.php?route=extension/extension/info&extension_id=3261\" target=\"_blank\">http://www.opencart.com/index.php?route=extension/extension/info&extension_id=3261</a></li>";
echo "</ol>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">If you purchased from TechSleuth.com you can download here:</li>";
echo "<ol style=\"margin-bottom: 8px;\">";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\"><a href=\"http://www.techsleuth.com/google-merchant-center-feed\" target=\"_blank\">http://www.techsleuth.com/google-merchant-center-feed</a></li>";
echo "</ol>";
echo "</ol>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">If you have cPanel hosting you can schedule a cron job using the cPanel interface.</li>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">Example command syntax:</li>";
echo "<ol style=\"margin-bottom: 8px;\">";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">php /home/<span style=\"color: green; font-weight: bold;\">&#60;hosting account name&#62;</span>/public_html/<span style=\"color: green; font-weight: bold;\">&#60;store root directory&#62;</span>/index.php route=feed/google_base_techsleuth target_country=us items_per_page=1000 page=1 output_format=xml save_as_file=true</li>";
echo "</ol>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">Tutorial video:  How to schedule a cron job using cPanel hosting</li>";
echo "<ol style=\"margin-bottom: 8px;\">";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\"><a href=\"https://www.youtube.com/embed/ERWtkHTXtVE\" target=\"_blank\">https://www.youtube.com/embed/ERWtkHTXtVE</a></li>";
echo "</ol>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">You can also schedule a cron job using crontab.</li>";
echo "<ol style=\"margin-bottom: 10px;\">";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\">Instructions for setting up a cron job using crontab:</li>";
echo "<ol style=\"margin-bottom: 8px;\">";
echo "<li style=\"list-style-type: none;\"><a href=\"http://www.thesitewizard.com/general/set-cron-job.shtml\" target=\"_blank\">http://www.thesitewizard.com/general/set-cron-job.shtml</a></li>";
echo "<li style=\"list-style-type: none; margin-bottom: 8px;\"><a href=\"http://www.cyberciti.biz/faq/how-do-i-add-jobs-to-cron-under-linux-or-unix-oses/\" target=\"_blank\">http://www.cyberciti.biz/faq/how-do-i-add-jobs-to-cron-under-linux-or-unix-oses/</a></li>";
echo "</ol>";
echo "</ol>";
echo "</ol>";
echo "<li style=\"list-style-type: decimal; font-weight: bold; margin-bottom: 8px;\">It took a while to enter all those product and category assignments.&nbsp;&nbsp;Will I need to assign them again?</li>";
echo "<ol style=\"margin-bottom: 10px;\">";
echo "<li style=\"list-style-type: none;\">The attribute assignments are saved as the XML file \"".THIS_SERVER_URL."google.merchant.center.feed.attribute.assignments.xml\"so you should not need to assign them again until one of the names change.&nbsp;&nbsp;Then it would mean udpating the old assignment.&nbsp;&nbsp;If you change many category or product names at once you could download the XML file and use a text editor to find and replace all the names that changed with the new ones, then re-upload the file.</li>";
echo "</ol>";
echo "<li style=\"list-style-type: decimal; font-weight: bold; margin-bottom: 8px;\">I am using the Internet Explorer browser.&nbsp;&nbsp;Why do the Chinese and Japanese fonts display incorrectly?</li>";
echo "<ol style=\"margin-bottom: 10px;\">";
echo "<li style=\"list-style-type: none;\">The Internet Explorer browser does not display East Asian languages such as Chinese and Japanese properly unless you have the Windows supplemental language support installed for those countries.&nbsp;&nbsp;You can find instructions for adding supplemental language support to Windows XP here:&nbsp;&nbsp;<a href=\"http://www.microsoft.com/resources/documentation/windows/xp/all/proddocs/en-us/int_pr_install_languages.mspx?mfr=true\" target=\"_blank\">http://www.microsoft.com/resources/documentation/windows/xp/all/proddocs/en-us/int_pr_install_languages.mspx?mfr=true</a>.</li>";
echo "</ol>";
echo "<li style=\"list-style-type: decimal; font-weight: bold; margin-bottom: 8px;\">When I try to view my product feed I receive a blank browser window.&nbsp;&nbsp;The source code displays only this message:&nbsp;&nbsp;\"&lt;!-- SHTML Wrapper - 500 Server Error --&gt;\".&nbsp;&nbsp;What is wrong?</li>";
echo "<ol style=\"margin-bottom: 10px;\">";
echo "<li style=\"list-style-type: none;\">This can occur when the option \"".$_['text_use_sef_urls_for_data_feed_urls_list']."\" is enabled.&nbsp;&nbsp;The directory permissions for \"".THIS_SERVER_URL."google-merchant-center-feeds\" may be set to 777 which on some servers would prevent the SEF URL functionality from working properly.&nbsp;&nbsp;To fix, set permissions for the directory \"".THIS_SERVER_URL."google-merchant-center-feeds\" to 755.</li>";
echo "</ol>";
echo "<li style=\"list-style-type: decimal; font-weight: bold; margin-bottom: 8px;\">When I click the button titled \"".$_['text_quick_configuration']."\" the Ajax loader image appears but the list does not load.&nbsp;&nbsp;Why?</li>";
echo "<ol style=\"margin-bottom: 10px;\">";
echo "<li style=\"list-style-type: lower-alpha; margin-bottom: 8px;\">This extension includes language configuration files which may take some time to upload via FTP.&nbsp;&nbsp;If the extension was recently installed, please verify the FTP program finished uploading all files successfully and then refresh the browser page.</li>";
echo "<li style=\"list-style-type: lower-alpha; margin-bottom: 8px;\">The internet browser may have temporary files that are basically \"stuck\" and are not being cleared as usual when the page is refreshed.&nbsp;&nbsp;It is less common with modern browsers but still happens from time to time.&nbsp;&nbsp;In that case the issue should be fixed by clearing the browser temporary files / cache.&nbsp;&nbsp;For Firefox you can click Tools, Clear Recent History, select the range \"Everything\" then click the down arrow to show the different checkboxes.&nbsp;&nbsp;Check the checkbox titled \"Cache\" and click \"Clear Now\".</li>";
echo "<li style=\"list-style-type: lower-alpha; margin-bottom: 8px;\">The web server session may have timed out causing the data on the drop down lists to be unavailable until a new session is established.&nbsp;&nbsp;In that case the issue should be fixed by logging out of the OpenCart admin area and back in again.&nbsp;&nbsp;To extend the session timeout you can increase the php.ini file value for \"session.gc_maxlifetime\" to a larger number like \"session.gc_maxlifetime=12000000;\".</li>";
echo "</ol>";
echo "<li style=\"list-style-type: decimal; font-weight: bold; margin-bottom: 8px;\">My default language is English and I have \"default\" selected as the data feed language, but when I view the data feed I see another language.&nbsp;&nbsp;Why?</li>";
echo "<ol style=\"margin-bottom: 10px;\">";
echo "<li style=\"list-style-type: none;\">OpenCart saves the last viewed language as an internet browser cookie and uses it when the next page is displayed.&nbsp;&nbsp;Clear your internet browser cookies to see the default language.</li>";
echo "</ol>";
echo "<li style=\"list-style-type: decimal; font-weight: bold; margin-bottom: 8px;\">The only download option I have is the latest version.&nbsp;&nbsp;How do I download an earlier version of this extension?</li>";
echo "<ol style=\"margin-bottom: 10px;\">";
echo "<li style=\"list-style-type: none;\">Earlier versions of this extension are available upon request.&nbsp;&nbsp;Please <a href=\"mailto:%6a%6f%73%68%40%74%65%63%68%73%6c%65%75%74%68%2e%63%6f%6d&subject=%52%65%71%75%65%73%74%20%66%6f%72%20%65%61%72%6c%69%65%72%20%76%65%72%73%69%6f%6e%20%2d%20%47%6f%6f%67%6c%65%20%4d%65%72%63%68%61%6e%74%20%43%65%6e%74%65%72%20%46%65%65%64%20%66%6f%72%20%4f%70%65%6e%63%61%72%74&body=%50%6c%65%61%73%65%20%73%65%6e%64%20%6d%65%20%61%20%63%6f%70%79%20%6f%66%20%74%68%65%20%47%6f%6f%67%6c%65%20%4d%65%72%63%68%61%6e%74%20%43%65%6e%74%65%72%20%46%65%65%64%20%65%78%74%65%6e%73%69%6f%6e%20%66%6f%72%20%4f%70%65%6e%43%61%72%74%2e%0a%0d%56%65%72%73%69%6f%6e%20%72%65%71%75%65%73%74%65%64%3a%20%20%0a%0d%4d%79%20%6e%61%6d%65%20%69%73%3a%20%20%0a%0d%4d%79%20%65%2d%6d%61%69%6c%20%61%64%64%72%65%73%73%20%69%73%3a%20%20\">use this link to contact me</a> if you require a copy of an earlier version.</li>";
echo "</ol>";
echo "</ol>";
echo "</div>";
echo "</td>";
echo "</tr>";
}
function jg_hupza()
{
echo "<tr>";
echo "<td style=\"padding-left: 0px; padding-right: 0px; padding-bottom: 0px !important; border-bottom-width: 0px;\">";
echo "<table class=\"list\" style=\"margin-bottom: 10px !important; margin-top: 0px; width: auto; !important\"><tr><td style=\"margin-top: 0px; padding-left: 5px !important; padding-right: 0px; padding-top: 5px !important; padding-bottom: 5px !important; background: #ffffff url('view/image/field.png') repeat-x; cursor: pointer; width: auto;\" onclick=\"jg_1nzev();return false;\">";
require JG_4810C;
echo "<div style=\"font-weight: bold; font-size: 120%;white-space: nowrap; width: auto;\" colspan=2>".$_['text_help_and_support']."<span style=\"padding-left: 8px; margin-right: 5px !important;\" id=\"button_toggle_show_help_and_support_section\"><span id=\"toggle_show_help_and_support_section_text\">".JG_QTRCN."</span></span></div>";
echo "</td>";
echo "</tr>";
echo "</table>";
echo "<div id=\"help_and_support\" style=\"display: none; white-space: normal; width: 700px;\">";
echo "<b>".$_['heading_title']."</b><br /><br />";
echo "Thank you for using the Google Merchant Center Feed extension for OpenCart.&nbsp;&nbsp;If you have any questions about this contribution, please see the readme.txt file that is included with the download and also visit the extension page on OpenCart's website here:&nbsp;&nbsp;";
echo "<a href=\"http://www.opencart.com/index.php?route=extension/extension/info&extension_id=3261\" target=\"_blank\">http://www.opencart.com/index.php?route=extension/extension/info&extension_id=3261</a>.<br /><br />";
echo "If you experience any problem or technical difficulty with using this extension, please feel free to <a href=\"mailto:%6a%6f%73%68%40%74%65%63%68%73%6c%65%75%74%68%2e%63%6f%6d\">contact me by e-mail</a>.<br /><br />";
echo "Thank you,<br /><br />";
echo "Joshua J. Gomes<br />";
echo "<a href=\"mailto:%6a%6f%73%68%40%74%65%63%68%73%6c%65%75%74%68%2e%63%6f%6d\">josh@techsleuth.com</a><br />";
echo "<a href=\"http://www.techsleuth.com\" target=\"_blank\">http://www.techsleuth.com</a><br /><br />";
echo "<div style=\"margin-bottom: 10px;\">";
echo "<a href=\"mailto:%6a%6f%73%68%40%74%65%63%68%73%6c%65%75%74%68%2e%63%6f%6d\" style=\"margin-left: 0px; margin-right: 8px;\" id=\"button_questions_or_comments\"><img style=\"vertical-align: middle; cursor: pointer; border-width: 0px; margin-left: 0px; margin-right: 0px;\" src=\"".THIS_IMAGE_URL."data/google-merchant-center-feed/email.png\" lowsrc=\"".THIS_IMAGE_URL."data/google-merchant-center-feed/email.png\" title=\"".$_['text_questions_or_comments']."\"/></a>";
echo "<a href=\"mailto:%6a%6f%73%68%40%74%65%63%68%73%6c%65%75%74%68%2e%63%6f%6d&subject=%46%65%61%74%75%72%65%20%6f%72%20%69%6d%70%72%6f%76%65%6d%65%6e%74%20%73%75%67%67%65%73%74%69%6f%6e%20%2d%20%47%6f%6f%67%6c%65%20%4d%65%72%63%68%61%6e%74%20%43%65%6e%74%65%72%20%46%65%65%64%20%66%6f%72%20%4f%70%65%6e%63%61%72%74&body=%49%20%77%6f%75%6c%64%20%6c%69%6b%65%20%74%6f%20%73%75%67%67%65%73%74%20%61%20%66%65%61%74%75%72%65%20%6f%72%20%69%6d%70%72%6f%76%65%6d%65%6e%74%20%66%6f%72%20%74%68%65%20%47%6f%6f%67%6c%65%20%4d%65%72%63%68%61%6e%74%20%43%65%6e%74%65%72%20%46%65%65%64%20%65%78%74%65%6e%73%69%6f%6e%20%66%6f%72%20%4f%70%65%6e%63%61%72%74%2e%0a%0d%44%65%73%63%72%69%70%74%69%6f%6e%20%6f%66%20%66%65%61%74%75%72%65%20%6f%72%20%69%6d%70%72%6f%76%65%6d%65%6e%74%3a%20%20\" style=\"margin-left: 0px; margin-right: 8px;\" id=\"button_questions_or_comments\"><img style=\"vertical-align: middle; cursor: pointer; border-width: 0px; margin-left: 0px; margin-right: 0px;\" src=\"".THIS_IMAGE_URL."data/google-merchant-center-feed/light-bulb.png\" lowsrc=\"".THIS_IMAGE_URL."data/google-merchant-center-feed/light-bulb.png\" title=\"".$_['text_suggest_a_feature_or_improvement']."\"/></a>";
echo "<a href=\"mailto:%6a%6f%73%68%40%74%65%63%68%73%6c%65%75%74%68%2e%63%6f%6d&subject=%42%75%67%20%6f%72%20%70%72%6f%62%6c%65%6d%20%72%65%70%6f%72%74%20%2d%20%47%6f%6f%67%6c%65%20%4d%65%72%63%68%61%6e%74%20%43%65%6e%74%65%72%20%46%65%65%64%20%66%6f%72%20%4f%70%65%6e%63%61%72%74&body=%49%20%77%6f%75%6c%64%20%6c%69%6b%65%20%74%6f%20%72%65%70%6f%72%74%20%61%20%62%75%67%20%6f%72%20%70%72%6f%62%6c%65%6d%20%77%69%74%68%20%74%68%65%20%47%6f%6f%67%6c%65%20%4d%65%72%63%68%61%6e%74%20%43%65%6e%74%65%72%20%46%65%65%64%20%65%78%74%65%6e%73%69%6f%6e%20%66%6f%72%20%4f%70%65%6e%63%61%72%74%2e%0a%0d%4f%70%65%6e%43%61%72%74%20%76%65%72%73%69%6f%6e%3a%20%20".VERSION."%0a%0d%45%78%74%65%6e%73%69%6f%6e%20%76%65%72%73%69%6f%6e%3a%20%20".$_['text_extension_version']."%0a%0d%44%65%73%63%72%69%70%74%69%6f%6e%20%6f%66%20%62%75%67%20%6f%72%20%70%72%6f%62%6c%65%6d%3a%20%20\" style=\"margin-left: 0px; margin-right: 0px;\" id=\"button_report_bug\"><img style=\"vertical-align: middle; cursor: pointer; border-width: 0px; margin-left: 0px; margin-right: 0px;\" src=\"".THIS_IMAGE_URL."data/google-merchant-center-feed/report-bug.png\" lowsrc=\"".THIS_IMAGE_URL."data/google-merchant-center-feed/report-bug.png\" title=\"".$_['text_report_a_bug_or_problem']."\"/></a>";
echo "</div>";
echo "</div>";
echo "</td>";
echo "</tr>";
}
function jg_9n1i9()
{
echo "<tr>";
echo "<td style=\"padding-left: 0px; padding-right: 0px; padding-bottom: 0px !important;\">";
echo "<table class=\"list\" style=\"margin-bottom: 10px !important; margin-top: 0px; width: auto !important;\"><tr><td style=\"margin-top: 0px; padding-left: 5px !important; padding-right: 0px; padding-top: 5px !important; padding-bottom: 5px !important; background: #ffffff url('view/image/field.png') repeat-x; cursor: pointer; width: auto;\" onclick=\"jg_6pg10();return false;\">";
require JG_4810C;
echo "<div style=\"font-weight: bold; font-size: 120%;white-space: nowrap; width: auto;\" colspan=2>".$_['text_advanced_configuration']."<span style=\"padding-left: 8px; margin-right: 5px;\" id=\"button_toggle_show_custom_product_fields_list\"><span id=\"toggle_show_custom_product_fields_list_text\">".JG_QTRCN."</span></span></div>";
echo "</td>";
echo "</tr>";
echo "</table>";
echo "<div id=\"custom_product_fields\" style=\"display: none; white-space: nowrap; overflow: visible; width: auto; margin-bottom: 10px; \"></div>";
}
function jg_8ezau()
{
echo "<tr>";
echo "<td style=\"padding-left: 0px; padding-right: 0px; padding-bottom: 0px !important;\">";
echo "<table class=\"list\" style=\"margin-bottom: 10px !important; margin-top: 0px; width: auto !important;\"><tr><td style=\"margin-top: 0px; padding-left: 5px !important; padding-right: 0px; padding-top: 5px !important; padding-bottom: 5px !important; background: #ffffff url('view/image/field.png') repeat-x; cursor: pointer; width: auto;\" onclick=\"jg_uy7lw();return false;\">";
require JG_4810C;
echo "<div style=\"font-weight: bold; font-size: 120%;white-space: nowrap; width: auto;\" colspan=2>".$_['text_quick_configuration']."<span style=\"padding-left: 8px; margin-right: 5px;\" id=\"button_toggle_show_attribute_assignments_list\"><span id=\"toggle_show_attribute_assignments_list_text\">".JG_QTRCN."</span></span></div>";
echo "</td>";
echo "</tr>";
echo "</table>";
echo "<div id=\"attribute_assignments\" style=\"display: none; white-space: nowrap; overflow: visible; width: auto;\"></div>";
}
function jg_1t2kj()
{
$jg_dm9rp='';
$jg_mo8j3=DB_PREFIX."setting";
$jg_9jhtu=0;
$jg_dm9rp='';
$jg_mmm7a=mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
mysql_set_charset('utf8');
if (function_exists('mb_language')) {
mb_language('uni');
mb_internal_encoding('UTF-8');
}
mysql_query("SET NAMES 'utf8'", $jg_mmm7a);
mysql_query("SET CHARACTER SET utf8", $jg_mmm7a);
mysql_query("SET CHARACTER_SET_CONNECTION=utf8", $jg_mmm7a);
mysql_query("SET CHARACTER_SET_RESULTS=utf8", $jg_mmm7a);
mysql_query("SET SQL_MODE=''", $jg_mmm7a);
mysql_select_db(DB_DATABASE, $jg_mmm7a) or die (mysql_error());
$jg_qq9fr=mysql_query("SELECT DISTINCT * FROM ".$jg_mo8j3." WHERE ".$jg_mo8j3.".group='config' AND ".$jg_mo8j3.".key='config_admin_language'", $jg_mmm7a) or die (mysql_error());
while($jg_e10nu=mysql_fetch_array($jg_qq9fr))
{
$jg_dm9rp=$jg_e10nu["value"];
}
return $jg_dm9rp;
}
function jg_poxic($jg_d410tc)
{
$jg_dm9rp='';
$jg_mo8j3=DB_PREFIX."language";
$jg_9jhtu=0;
$jg_dm9rp='';
$jg_mmm7a=mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
mysql_set_charset('utf8');
if (function_exists('mb_language')) {
mb_language('uni');
mb_internal_encoding('UTF-8');
}
mysql_query("SET NAMES 'utf8'", $jg_mmm7a);
mysql_query("SET CHARACTER SET utf8", $jg_mmm7a);
mysql_query("SET CHARACTER_SET_CONNECTION=utf8", $jg_mmm7a);
mysql_query("SET CHARACTER_SET_RESULTS=utf8", $jg_mmm7a);
mysql_query("SET SQL_MODE=''", $jg_mmm7a);
mysql_select_db(DB_DATABASE, $jg_mmm7a) or die (mysql_error());
$jg_qq9fr=mysql_query("SELECT DISTINCT * FROM ".$jg_mo8j3." WHERE ".$jg_mo8j3.".code='".$jg_d410tc."'", $jg_mmm7a) or die (mysql_error());
while($jg_e10nu=mysql_fetch_array($jg_qq9fr))
{
$jg_dm9rp=$jg_e10nu["directory"];
}
return $jg_dm9rp;
}
?>
<div class="box" style="min-width: 1100px;white-space: nowrap;width: 100%;">
<div class="left"></div>
<div class="right"></div>
<div class="heading">
<?php 
require JG_4810C;
switch (VERSION)
{
case (VERSION=='1.4.7'||VERSION=='1.4.8'||VERSION=='1.4.9'||VERSION=='1.4.9.1'||VERSION=='1.4.9.2'||VERSION=='1.4.9.3'||VERSION=='1.4.9.4'||VERSION=='1.4.9.5'||VERSION=='1.4.9.6'):
echo "<h1 style=\"background-image: url('view/image/feed.png');\"> ".$_['text_extension_title']."</h1>";
break;
case (VERSION=='1.5.0'||VERSION=='1.5.0.1'||VERSION=='1.5.0.2'||VERSION=='1.5.0.3'||VERSION=='1.5.0.4'||VERSION=='1.5.0.5'||VERSION=='1.5.1'||VERSION=='1.5.1.1'||VERSION=='1.5.1.2'||VERSION=='1.5.1.3'||VERSION=='1.5.2'||VERSION=='1.5.2.1'||VERSION=='1.5.3'||VERSION=='1.5.3.1'||VERSION=='1.5.4'||VERSION=='1.5.4.1'||VERSION=='1.5.5'||VERSION=='1.5.5.1'||VERSION=='1.5.6'||VERSION=='1.5.6.1'):
echo "<h1><img src=\"view/image/feed.png\" alt=\"\" /> ".$_['text_extension_title']."</h1>";
break;
default:
break;
}
?>
<div class="buttons"></div>
</div>
<div class="content" style="min-height: 10px; margin-bottom: 10px; padding-bottom: 0px;">
<table class="form" style="table-layout: fixed; width: auto; margin-bottom: 0px;">
<tr>
<td style="padding-left: 0px; padding-right: 0px; padding-bottom: 0px !important; padding-top: 0px; min-width: 400px;">
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
<?php
echo "<table class=\"list\" style=\"margin-bottom: 10px !important; margin-top: 0px; width: auto !important;\"><tr><td style=\"margin-top: 5px !important; padding: 5px !important; background: #ffffff url('view/image/field.png') repeat-x;\">";
?>
<?php
echo '<span style="font-weight: bold; font-size: 120%;white-space: nowrap;margin-right: 8px;">'.$_['entry_status'].'</span>';
echo '<select name="google_base_techsleuth_status" id="google_base_techsleuth_status" onchange="jg_mwd31();return false;">';
if ($google_base_techsleuth_status){
echo '<option value="1" selected="selected">'.$text_enabled.'</option>';
echo '<option value="0">'.$text_disabled.'</option>';
}else{
echo '<option value="1">'.$text_enabled.'</option>';
echo '<option value="0" selected="selected">'.$text_disabled.'</option>';
}
echo '</select>';
if ($google_base_techsleuth_status){
echo "<img title=\"".$_['text_disable']."\" id=\"toggle_extension_enabled\" onclick=\"jg_qn1hl();\" style=\"vertical-align: middle; cursor: pointer; margin-left: 6px; margin-right: 0px;\" src=\"".THIS_IMAGE_URL."data/google-merchant-center-feed/turn-off.png\" lowsrc=\"".THIS_IMAGE_URL."data/google-merchant-center-feed/turn-off.png\" />";
}else{
echo "<img title=\"".$_['text_enable']."\" id=\"toggle_extension_enabled\" onclick=\"jg_qn1hl();\" style=\"vertical-align: middle; cursor: pointer; margin-left: 6px; margin-right: 0px;\" src=\"".THIS_IMAGE_URL."data/google-merchant-center-feed/turn-on.png\" lowsrc=\"".THIS_IMAGE_URL."data/google-merchant-center-feed/turn-on.png\" />";
}
echo "<img title=\"".$_['text_exit']."\" onclick=\"window.location='".$cancel."';\" style=\"vertical-align: middle; cursor: pointer; margin-left: 6px; margin-right: 0px;\" src=\"".THIS_IMAGE_URL."data/google-merchant-center-feed/exit.png\" lowsrc=\"".THIS_IMAGE_URL."data/google-merchant-center-feed/exit.png\" />";
?>
<?php
echo "</td>";
echo "</tr>";
echo "</table>";
?>
</form>
</td>
</tr>
<tr>
<td style="padding-bottom: 0px !important; padding-left: 0px; padding-right: 0px;">
<?php 
echo "<table class=\"list\" style=\"border-bottom-width: 0px; margin-bottom: 10px !important; margin-top: 0px; width: auto !important;\"><tr><td style=\"width: auto; margin-top: 5px !important; padding: 5px !important; background: #ffffff url('view/image/field.png') repeat-x; cursor: pointer;\" onclick=\"jg_psyj8();return false;\">";
echo "<div style=\"font-weight: bold; font-size: 120%;white-space: nowrap; width: auto;\" colspan=2>".$_['text_data_feeds']."<span style=\"padding-left: 8px; margin-right: 0px;\" id=\"button_toggle_show_data_feeds_list\"><span id=\"button_toggle_show_data_feeds_list_text\" style=\"padding-bottom: 0px;\">".JG_QTRCN."</span></span></div>";
echo "</td>";
echo "</tr>";
echo "</table>";
?>
<div id="data_feeds_list" style="display: none; overflow: auto; width: auto;"></div>
</td>
</tr>
<?php jg_8ezau(); ?>
<?php jg_9n1i9(); ?>
<?php jg_9ufv6(); ?>
<?php jg_6lq4b(); ?>
<?php jg_hupza(); ?>
</table>
</div>
</div>
<?php
echo '<div id="google_categories_list_hovered" class="jg_8gh84b">';
echo '<img style="vertical-align: middle;" src="'.THIS_IMAGE_URL.'data/google-merchant-center-feed/ajax-loader-circles-16x16.gif" lowsrc="'.THIS_IMAGE_URL.'data/google-merchant-center-feed/ajax-loader-circles-16x16.gif"/>';
echo '</div>';
echo '<div id="information_basic_hovered" class="jg_8gh84b">';
echo '<img style="vertical-align: middle;" src="'.THIS_IMAGE_URL.'data/google-merchant-center-feed/ajax-loader-circles-16x16.gif" lowsrc="'.THIS_IMAGE_URL.'data/google-merchant-center-feed/ajax-loader-circles-16x16.gif"/>';
echo '</div>';
echo '<div id="select_field_length_hovered" class="jg_8gh84b">';
echo '<img style="vertical-align: middle;" src="'.THIS_IMAGE_URL.'data/google-merchant-center-feed/ajax-loader-circles-16x16.gif" lowsrc="'.THIS_IMAGE_URL.'data/google-merchant-center-feed/ajax-loader-circles-16x16.gif"/>';
echo '</div>';
echo '<div onclick="jg_sbedlz();" id="fade" class="jg_in3ls1" onclick="document.getElementById(\'light1\').style.display=\'none\';document.getElementById(\'fade\').style.display=\'none\'">';
echo '</div>';
?>
<style type="text/css">
.jg_b814hg
{
color: #ffffff;
white-space: nowrap;
position:relative;
width: 100%;
overflow:hidden;
vertical-align:middle;
background-repeat:repeat-x;
display:block;
background-image:url('view/image/header.png');
overflow: hidden;
border-bottom-width: 0px;
}
.jg_gupxf1
{
height:0px;
overflow:auto;
display:none;
overflow: hidden;
position:relative;
width: auto;
}
.jg_63ys2c
{
height:auto;
overflow:auto;
display:block;
overflow: hidden;
position:relative;
width: auto;
}
.jg_d68jo2
{
position:relative;
width: 100%;
float: left;
}
.jg_8gh84b {
display: none;
position: absolute;
top: 5px;
left: 5px;
vertical-align: middle;
width: auto;
height: auto;
padding: 6px;
border: 2px solid #cccccc;
margin: 15px;
background-color: white;
z-index:1002;
overflow: auto;
}
.jg_in3ls1, .jg_8eljwb, .jg_vun1ca {
display: none;
position: fixed;
top: 0%;
left: 0%;
width: 100%;
height: 100%;
background-color: #000000;
z-index:1001;
-moz-opacity: 0.8;
opacity:.80;
filter: alpha(opacity=80);
cursor: pointer;
}
</style>
<script type="text/javascript">
var jg_ul3vh='';
var jg_nrj10s='';
function jg_v3zcs()
{
jg_awm65('1');
jg_qn1hl();
}
function jg_da10d4()
{
jg_awm65('0');
jg_qn1hl();
}
function jg_8fvfpf(jg_gchqy,jg_mqgwi,jg_rm6dc)
{
jg_ul3vh=jg_rm6dc;
jg_vqfaw=jg_gchqy.id;
document.getElementById(jg_mqgwi).style.display='block';
document.getElementById('fade').style.display='block';
switch (jg_mqgwi)
{
case 'google_categories_list_hovered':
switch (jg_rm6dc)
{
case 'default_google_product_category':
case 'default_google_product_category_au':
case 'default_google_product_category_br':
case 'default_google_product_category_ca':
case 'default_google_product_category_ch':
case 'default_google_product_category_cn':
case 'default_google_product_category_cz':
case 'default_google_product_category_de':
case 'default_google_product_category_es':
case 'default_google_product_category_fr':
case 'default_google_product_category_gb':
case 'default_google_product_category_it':
case 'default_google_product_category_jp':
case 'default_google_product_category_nl':
case 'default_google_product_category_us':
var jg_gchqy=document.getElementById(jg_vqfaw).parentNode.parentNode.parentNode.parentNode.parentNode;
break;
default:
var jg_gchqy=document.getElementById(jg_vqfaw).parentNode.parentNode.parentNode;
break;
}
var pos=jg_cvg8l(jg_gchqy);
document.getElementById(jg_mqgwi).style.left=pos.x.toString()+'px';
document.getElementById(jg_mqgwi).style.top=pos.y.toString()+'px';
jg_gchqy=null;
jg_teyx1(jg_mqgwi);
break;
case 'information_basic_hovered':
if(jg_rm6dc=='custom_product_field_id_adwords_queryparam')
{
document.getElementById('information_basic_hovered').innerHTML="<span id=\"information_basic_content\" class=\"help\" style=\"white-space: normal; width: 500px;\"><div>This attribute works in a similar fashion to adwords_redirect, but instead of overriding the product URL, it will append the value to it at the end.&nbsp;&nbsp;This attribute can be defined in three ways, and you may submit any combination of these attributes:<ul><li>\"{adtype}\".&nbsp;&nbsp;You can use {adtype} in this attribute, which will be replaced by \"pe\" or \"pla\" if the click came from a Product Extensions ad or a Product Listings one respectively.</li><li>\"{keyword}\".&nbsp;&nbsp;Google will append the keyword that triggered the ad into the {keyword}.&nbsp;&nbsp;Applies to product extensions on keyword-targeting.</li><li>value.&nbsp;&nbsp;If you have any individually-defined parameters to include, Google will append these values to the end of the destination URL of the offer: http://example.com/offer26?aid=450</li></ul>Examples:<br/><br/>&#60;g:adwords_queryparam&#62;adtype={adtype}&#60;/g:adwords_queryparam&#62;<br/><br/>&#60;g:adwords_queryparam&#62;kw={keyword}&#60;/g:adwords_queryparam&#62;<br/><br/>&#60;g:adwords_queryparam&#62;aid=450&#60;/g:adwords_queryparam&#62;</div><div><img title=\"<?php echo $_['text_okay']; ?>\" onclick=\"jg_sbedlz();return false;\" style=\"float: right; text-align: right; vertical-align: middle; cursor: pointer; margin-left: 6px;\" src=\"<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/check.png\" lowsrc=\"<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/check.png\" /></div></span>";
}
if(jg_rm6dc=='custom_product_field_id_shipping_weight')
{
document.getElementById('information_basic_hovered').innerHTML="<span id=\"information_basic_content\" class=\"help\"><?php echo $_['text_help_enter_the_value_use_weight'] ?><img title=\"<?php echo $_['text_okay']; ?>\" onclick=\"jg_sbedlz();return false;\" style=\"text-align: right; vertical-align: middle; cursor: pointer; margin-left: 6px;\" src=\"<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/check.png\" lowsrc=\"<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/check.png\" /></span>";
}
if(jg_rm6dc=='custom_product_field_id_skip_product')
{
document.getElementById('information_basic_hovered').innerHTML="<span id=\"information_basic_content\" class=\"help\"><?php echo $_['text_help_enter_the_number_one_to_skip_a_product'] ?><img title=\"<?php echo $_['text_okay']; ?>\" onclick=\"jg_sbedlz();return false;\" style=\"text-align: right; vertical-align: middle; cursor: pointer; margin-left: 6px;\" src=\"<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/check.png\" lowsrc=\"<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/check.png\" /></span>";
}
var jg_gchqy=document.getElementById(jg_vqfaw).parentNode;
var pos=jg_cvg8l(jg_gchqy);
document.getElementById(jg_mqgwi).style.left=pos.x.toString()+'px';
document.getElementById(jg_mqgwi).style.top=pos.y.toString()+'px';
jg_gchqy=null;
jg_ul3vh='';
break;
case 'select_field_length_hovered':
if(document.getElementById(jg_vqfaw))
{
var jg_gchqy=document.getElementById(jg_vqfaw).parentNode;
var pos=jg_cvg8l(jg_gchqy);
document.getElementById(jg_mqgwi).style.left=pos.x.toString()+'px';
document.getElementById(jg_mqgwi).style.top=pos.y.toString()+'px';
jg_gchqy=null;
jg_10huu(jg_mqgwi,jg_rm6dc);
jg_ul3vh='';
}else{
jg_sbedlz();
jg_ul3vh='';
}
break;
default:
break;
}
}
function jg_sbedlz()
{
document.getElementById('google_categories_list_hovered').style.display='none';
document.getElementById('information_basic_hovered').style.display='none';
document.getElementById('select_field_length_hovered').style.display='none';
document.getElementById('fade').style.display='none';
document.getElementById('select_field_length_hovered').innerHTML='<img style="vertical-align: middle;" src="<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/ajax-loader-circles-16x16.gif" lowsrc="<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/ajax-loader-circles-16x16.gif"/>';
document.getElementById('google_categories_list_hovered').innerHTML='<img style="vertical-align: middle;" src="<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/ajax-loader-circles-16x16.gif" lowsrc="<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/ajax-loader-circles-16x16.gif"/>';
document.getElementById('information_basic_hovered').innerHTML='<img style="vertical-align: middle;" src="<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/ajax-loader-circles-16x16.gif" lowsrc="<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/ajax-loader-circles-16x16.gif"/>';
}
var JG_610CQ=200;
var JG_T10E1=250.0;
var jg_9cpj1='';
var jg_nkf77='';
var jg_xcbtg=navigator.userAgent.toLowerCase().indexOf('chrome')>-1;
var jg_kligm=navigator.userAgent.toLowerCase().indexOf('firefox')>-1;
var jg_whjj9=navigator.userAgent.toLowerCase().indexOf('msie 8')>-1;
var jg_1owjv=navigator.userAgent.toLowerCase().indexOf('msie 9')>-1;
var jg_onqfv=navigator.userAgent.toLowerCase().indexOf('opera')>-1;
var jg_lairp=navigator.userAgent.toLowerCase().indexOf('safari')>-1;
function jg_ldxcrr(jg_sie1v,jg_g8d10,jg_b586u,jg_35sz4)
{
var jg_1rufm=jg_sie1v.parentNode.parentNode.parentNode.parentNode.getElementsByTagName("div");
for (var i=0;i<jg_1rufm.length; i++)
{
if (jg_1rufm[i].id.indexOf("jg_1gdi17")==-1){}else
{
var jg_4tzyv=0;
var jg_26hij=jg_1rufm[i].getElementsByTagName("img");
for (var j=0;j<jg_26hij.length; j++)
{
if(jg_1rufm[i].id=="jg_1gdi17"+jg_b586u.toString()+"jg_cv8lz6")
{
if(jg_26hij[j].src.indexOf("accordion-expand.png")==-1)
{
if(jg_26hij[j].src.indexOf("accordion-collapse.png")==-1){}else
{
if((document.getElementById("jg_ba5y64"+jg_b586u+"jg_cv8lz6").style.display=='block') ||
(document.getElementById("jg_ba5y64"+jg_b586u+"jg_cv8lz6").style.display==''))
{
jg_26hij[j].lowsrc="<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/accordion-expand.png";
jg_26hij[j].src="<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/accordion-expand.png";
jg_26hij[j].title="<?php echo $text_expand_view ?>";
jg_9cpj1="jg_ba5y64"+jg_b586u+"jg_cv8lz6";
jg_36dm4(jg_g8d10);
}
}
}
else
{
jg_26hij[j].lowsrc="<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/accordion-collapse.png";
jg_26hij[j].src="<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/accordion-collapse.png";
jg_26hij[j].title="<?php echo $text_collapse_view ?>";
jg_olq10(jg_g8d10);
}
}
else
{
if(jg_26hij[j].src.indexOf("accordion-collapse.png")==-1){}else
{
if(document.getElementById("jg_ba5y64"+jg_b586u+"jg_cv8lz6").style.display=='none')
{
jg_26hij[j].lowsrc="<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/accordion-expand.png";
jg_26hij[j].src="<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/accordion-expand.png";
jg_26hij[j].title="<?php echo $text_expand_view ?>";
}
else
{
jg_26hij[j].lowsrc="<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/accordion-collapse.png";
jg_26hij[j].src="<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/accordion-collapse.png";
jg_26hij[j].title="<?php echo $text_collapse_view ?>";
}
}
}
jg_4tzyv+=1;
}
}
}
var jg_4yqt6=0;
if ((jg_xcbtg === true)||(jg_lairp === true))
{
jg_4yqt6+=26;
}
if (jg_kligm === true)
{
jg_4yqt6+=21;
}
if (jg_whjj9 === true)
{
jg_4yqt6+=26;
}
if (jg_1owjv === true)
{
jg_4yqt6+=21;
}
if (jg_onqfv === true)
{
jg_4yqt6+=26;
}
if ((jg_xcbtg!=true)&&(jg_kligm!=true)&&(jg_whjj9!=true)&&(jg_1owjv!=true)&&(jg_lairp!=true)&&(jg_onqfv!=true))
{
jg_4yqt6+=26;
}
var jg_1rufm=jg_sie1v.parentNode.parentNode.getElementsByTagName("tr");
for (var i=0;i<jg_1rufm.length; i++)
{
if (jg_1rufm[i].id.indexOf("data-feed-row-")==-1){}else
{
var jg_6lh6e=jg_1rufm[i].childNodes[4].getElementsByTagName("input");
var jg_u4jo7=0;
for (var j=0;j<jg_6lh6e.length; j++)
{
if (jg_6lh6e[j].id.indexOf("data-feed-url-text-store-")==-1){}else
{
jg_u4jo7+=1;
}
}
if(jg_u4jo7==0)
{
if ((jg_xcbtg === true)||(jg_lairp === true))
{
jg_4yqt6+=28;
}
if (jg_kligm === true)
{
jg_4yqt6+=26;
}
if (jg_whjj9 === true)
{
jg_4yqt6+=28;
}
if (jg_1owjv === true)
{
jg_4yqt6+=27;
}
if (jg_onqfv === true)
{
jg_4yqt6+=28;
}
if ((jg_xcbtg!=true)&&(jg_kligm!=true)&&(jg_whjj9!=true)&&(jg_1owjv!=true)&&(jg_lairp!=true)&&(jg_onqfv!=true))
{
jg_4yqt6+=28;
}
}
else
{
if ((jg_xcbtg === true)||(jg_lairp === true))
{
jg_4yqt6+=(jg_u4jo7*28);
}
if (jg_kligm === true)
{
jg_4yqt6+=(jg_u4jo7*26);
}
if (jg_whjj9 === true)
{
jg_4yqt6+=(jg_u4jo7*28);
}
if (jg_1owjv === true)
{
jg_4yqt6+=(jg_u4jo7*27);
}
if (jg_onqfv === true)
{
jg_4yqt6+=(jg_u4jo7*28);
}
if ((jg_xcbtg!=true)&&(jg_kligm!=true)&&(jg_whjj9!=true)&&(jg_1owjv!=true)&&(jg_lairp!=true)&&(jg_onqfv!=true))
{
jg_4yqt6+=(jg_u4jo7*28);
}
}
if ((jg_xcbtg === true)||(jg_lairp === true))
{
jg_4yqt6+=5;
}
if (jg_kligm === true)
{
jg_4yqt6+=6;
}
if (jg_whjj9 === true)
{
jg_4yqt6+=8;
}
if (jg_1owjv === true)
{
jg_4yqt6+=5;
}
if (jg_onqfv === true)
{
jg_4yqt6+=5;
}
if ((jg_xcbtg!=true)&&(jg_kligm!=true)&&(jg_whjj9!=true)&&(jg_1owjv!=true)&&(jg_lairp!=true)&&(jg_onqfv!=true))
{
jg_4yqt6+=6;
}
}
}
var jg_uas68=0;
var jg_9xfak=29;
jg_9qqpv=32;
jg_tlkjc=28;
jg_79wfx=32;
var jg_1rufm=jg_sie1v.parentNode.parentNode.getElementsByTagName("tr");
var jg_cys1r=0;
var jg_56blj=0;
var jg_cexie=0;
if ((jg_xcbtg === true)||(jg_lairp === true))
{
jg_9xfak=29;
jg_9qqpv=32;
jg_tlkjc=28;
jg_79wfx=32;
}
if (jg_kligm === true)
{
jg_9xfak=27;
jg_9qqpv=32;
jg_tlkjc=28;
jg_79wfx=32;
}
if (jg_whjj9 === true)
{
jg_9xfak=27;
jg_9qqpv=32;
jg_tlkjc=28;
jg_79wfx=32;
}
if (jg_1owjv === true)
{
jg_9xfak=25;
jg_9qqpv=32;
jg_tlkjc=28;
jg_79wfx=32;
}
if (jg_onqfv === true)
{
jg_9xfak=27;
jg_9qqpv=32;
jg_tlkjc=28;
jg_79wfx=32;
}
for (var i=0;i<jg_1rufm.length; i++)
{
if (jg_1rufm[i].id.indexOf("data-feed-row-")==-1){}else
{
var jg_nlcwf=jg_1rufm[i].childNodes[4].getElementsByTagName("div");
for (var j=0;j<jg_nlcwf.length; j++)
{
if (jg_nlcwf[j].id.indexOf("data-feed-urls-first-row-div-store-id-")==-1){}else
{
jg_cys1r+=1;
}
if (jg_nlcwf[j].id.indexOf("data-feed-urls-middle-row-div-store-id-")==-1){}else
{
jg_56blj+=1;
}
if (jg_nlcwf[j].id.indexOf("data-feed-urls-last-row-div-store-id-")==-1){}else
{
jg_cexie+=1;
}
}
}
}
jg_uas68+=jg_9xfak;
jg_uas68+=(jg_cys1r*jg_9qqpv);
jg_uas68+=(jg_56blj*jg_tlkjc);
jg_uas68+=(jg_cexie*jg_79wfx);
var jg_dcfvv="jg_ba5y64" + jg_b586u + "jg_cv8lz6";
if(jg_9cpj1==jg_dcfvv)
jg_dcfvv='';
setTimeout("jg_noe9b(" + new Date().getTime() + "," + JG_T10E1 + ",'" + jg_9cpj1 + "','" + jg_dcfvv + "','" + jg_uas68 + "')", 33);
jg_9cpj1=jg_dcfvv;
jg_nkf77=jg_b586u;
}
function jg_noe9b(jg_pmzkj, jg_ye59a, jg_azg8u, jg_3gd9x, jg_35sz4)
{  
var jg_49stz=new Date().getTime();
var jg_pj4b7=jg_49stz - jg_pmzkj;
var jg_dl18b=(jg_3gd9x=='') ? null : document.getElementById(jg_3gd9x);
var jg_zhzhy=(jg_azg8u=='') ? null : document.getElementById(jg_azg8u);
if(jg_ye59a<=jg_pj4b7)
{
if(jg_dl18b!=null)
jg_dl18b.style.height=jg_35sz4 + 'px';
if(jg_zhzhy!=null)
{
jg_zhzhy.style.display='none';
jg_zhzhy.style.height='0px';
}
return;
}
jg_ye59a -= jg_pj4b7;
var jg_wx917=Math.round((jg_ye59a/JG_T10E1) * jg_35sz4);
if(jg_dl18b!=null)
{
if(jg_dl18b.style.display!='block')
jg_dl18b.style.display='block';
jg_dl18b.style.height=(jg_35sz4 - jg_wx917) + 'px';
}
if(jg_zhzhy!=null)
jg_zhzhy.style.height=jg_wx917 + 'px';
setTimeout("jg_noe9b(" + jg_49stz + "," + jg_ye59a +",'" + jg_azg8u + "','" + jg_3gd9x + "','" + jg_35sz4 + "')", 33);
}
var jg_3g8b1;
var jg_e3uzl;
var jg_xoup2;
var jg_7hj8j;
var jg_owema;
var jg_hxr10;
var jg_e7rlw;
var jg_skrnf;
var jg_7j7wf;
var jg_v429f;
var jg_7fpw8;
var jg_mldok;
var jg_10no2
var jg_4dq22;
var jg_dt9i6;
var jg_ak4ya;
var jg_meza4;
if (window.XMLHttpRequest)
{
jg_meza4=new XMLHttpRequest();
}
else
{
jg_meza4=new ActiveXObject("Microsoft.XMLHTTP");
}
var jg_7tl22;
if (window.XMLHttpRequest)
{
jg_7tl22=new XMLHttpRequest();
}
else
{
jg_7tl22=new ActiveXObject("Microsoft.XMLHTTP");
}
var jg_8oghk;
if (window.XMLHttpRequest)
{
jg_8oghk=new XMLHttpRequest();
}
else
{
jg_8oghk=new ActiveXObject("Microsoft.XMLHTTP");
}
var jg_o410n;
if (window.XMLHttpRequest)
{
jg_o410n=new XMLHttpRequest();
}
else
{
jg_o410n=new ActiveXObject("Microsoft.XMLHTTP");
}
var jg_fayib;
if (window.XMLHttpRequest)
{
jg_fayib=new XMLHttpRequest();
}
else
{
jg_fayib=new ActiveXObject("Microsoft.XMLHTTP");
}
var jg_e5lo2;
if (window.XMLHttpRequest)
{
jg_e5lo2=new XMLHttpRequest();
}
else
{
jg_e5lo2=new ActiveXObject("Microsoft.XMLHTTP");
}
var jg_5c2f2;
if (window.XMLHttpRequest)
{
jg_5c2f2=new XMLHttpRequest();
}
else
{
jg_5c2f2=new ActiveXObject("Microsoft.XMLHTTP");
}
var jg_a8qro;
if (window.XMLHttpRequest)
{
jg_a8qro=new XMLHttpRequest();
}
else
{
jg_a8qro=new ActiveXObject("Microsoft.XMLHTTP");
}
var jg_d7xog;
if (window.XMLHttpRequest)
{
jg_d7xog=new XMLHttpRequest();
}
else
{
jg_d7xog=new ActiveXObject("Microsoft.XMLHTTP");
}
var jg_f1076;
if (window.XMLHttpRequest)
{
jg_f1076=new XMLHttpRequest();
}
else
{
jg_f1076=new ActiveXObject("Microsoft.XMLHTTP");
}
var jg_q8k7x;
if (window.XMLHttpRequest)
{
jg_q8k7x=new XMLHttpRequest();
}
else
{
jg_q8k7x=new ActiveXObject("Microsoft.XMLHTTP");
}
var jg_v9jed;
if (window.XMLHttpRequest)
{
jg_v9jed=new XMLHttpRequest();
}
else
{
jg_v9jed=new ActiveXObject("Microsoft.XMLHTTP");
}
var jg_dgn6t;
if (window.XMLHttpRequest)
{
jg_dgn6t=new XMLHttpRequest();
}
else
{
jg_dgn6t=new ActiveXObject("Microsoft.XMLHTTP");
}
var jg_vt23x;
if (window.XMLHttpRequest)
{
jg_vt23x=new XMLHttpRequest();
}
else
{
jg_vt23x=new ActiveXObject("Microsoft.XMLHTTP");
}
var jg_v1vn6;
if (window.XMLHttpRequest)
{
jg_v1vn6=new XMLHttpRequest();
}
else
{
jg_v1vn6=new ActiveXObject("Microsoft.XMLHTTP");
}
var jg_8w449;
if (window.XMLHttpRequest)
{
jg_8w449=new XMLHttpRequest();
}
else
{
jg_8w449=new ActiveXObject("Microsoft.XMLHTTP");
}
var jg_619dn;
if (window.XMLHttpRequest)
{
jg_619dn=new XMLHttpRequest();
}
else
{
jg_619dn=new ActiveXObject("Microsoft.XMLHTTP");
}
var jg_dgqt9;
if (window.XMLHttpRequest)
{
jg_dgqt9=new XMLHttpRequest();
}
else
{
jg_dgqt9=new ActiveXObject("Microsoft.XMLHTTP");
}
var jg_gsa4m;
if (window.XMLHttpRequest)
{
jg_gsa4m=new XMLHttpRequest();
}
else
{
jg_gsa4m=new ActiveXObject("Microsoft.XMLHTTP");
}
var jg_mleyr;
if (window.XMLHttpRequest)
{
jg_mleyr=new XMLHttpRequest();
}
else
{
jg_mleyr=new ActiveXObject("Microsoft.XMLHTTP");
}
var jg_vu2x1;
if (window.XMLHttpRequest)
{
jg_vu2x1=new XMLHttpRequest();
}
else
{
jg_vu2x1=new ActiveXObject("Microsoft.XMLHTTP");
}
var jg_5ewns;
if (window.XMLHttpRequest)
{
jg_5ewns=new XMLHttpRequest();
}
else
{
jg_5ewns=new ActiveXObject("Microsoft.XMLHTTP");
}
var jg_jieea;
if (window.XMLHttpRequest)
{
jg_jieea=new XMLHttpRequest();
}
else
{
jg_jieea=new ActiveXObject("Microsoft.XMLHTTP");
}
var jg_sk8hb;
if (window.XMLHttpRequest)
{
jg_sk8hb=new XMLHttpRequest();
}
else
{
jg_sk8hb=new ActiveXObject("Microsoft.XMLHTTP");
}
var jg_7aqzq;
if (window.XMLHttpRequest)
{
jg_7aqzq=new XMLHttpRequest();
}
else
{
jg_7aqzq=new ActiveXObject("Microsoft.XMLHTTP");
}
var jg_i3ege;
if (window.XMLHttpRequest)
{
jg_i3ege=new XMLHttpRequest();
}
else
{
jg_i3ege=new ActiveXObject("Microsoft.XMLHTTP");
}
var jg_10sxh;
if (window.XMLHttpRequest)
{
jg_10sxh=new XMLHttpRequest();
}
else
{
jg_10sxh=new ActiveXObject("Microsoft.XMLHTTP");
}
var jg_ptuvp;
if (window.XMLHttpRequest)
{
jg_ptuvp=new XMLHttpRequest();
}
else
{
jg_ptuvp=new ActiveXObject("Microsoft.XMLHTTP");
}
var jg_zsdxl;
if (window.XMLHttpRequest)
{
jg_zsdxl=new XMLHttpRequest();
}
else
{
jg_zsdxl=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_lzk32();
jg_s1xwy=new Image(20,26);
jg_s1xwy.src="<?php echo THIS_SERVER_URL ?>view/image/field.png";
jg_k9kl4=new Image(19,34);
jg_k9kl4.src="<?php echo THIS_SERVER_URL ?>view/image/selected.png";
jg_lwabt=new Image(16,16);
jg_lwabt.src="<?php echo THIS_SERVER_URL ?>view/image/filemanager/edit-rename.png";
jg_ohj10=new Image(16,16);
jg_ohj10.src="<?php echo THIS_SERVER_URL ?>view/image/filemanager/refresh.png";
jg_210ct=new Image(16,16);
jg_210ct.src="<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/arrow-down.png";
jg_5u6hu=new Image(16,16);
jg_5u6hu.src="<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/arrow-up.png";
jg_7eep8=new Image(16,16);
jg_7eep8.src="<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/accordion-collapse.png";
jg_5qkqe=new Image(16,16);
jg_5qkqe.src="<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/currency-update-cache.png";
jg_e105h=new Image(16,16);
jg_e105h.src="<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/currency-edit.png";
jg_q3lvk=new Image(16,16);
jg_q3lvk.src="<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/currency-install.png";
jg_5uuse=new Image(16,16);
jg_5uuse.src="<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/currency-uninstall.png";
jg_q3zel=new Image(16,16);
jg_q3zel.src="<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/edit-add.png";
jg_u10no=new Image(16,16);
jg_u10no.src="<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/edit-remove.png";
jg_t6941=new Image(16,16);
jg_t6941.src="<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/report-bug.png";
jg_g4jeg=new Image(16,16);
jg_g4jeg.src="<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/email.png";
jg_b4ebc=new Image(16,16);
jg_b4ebc.src="<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/accordion-expand.png";
jg_zi10i=new Image(16,16);
jg_zi10i.src="<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/ajax-loader-circles-16x16.gif";
jg_ymjn2=new Image(16,16);
jg_ymjn2.src="<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/turn-off.png";
jg_zlqhz=new Image(16,16);
jg_zlqhz.src="<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/turn-on.png";
function jg_t42akw()
{
document.getElementById('custom_product_field_attribute_presets').selectedIndex=0;
var evt=document.createEvent("Events");
evt.initEvent('change', true, true );
var jg_4bj4n=document.getElementById('custom_product_field_attribute_presets');
var jg_aitjj=!jg_4bj4n.dispatchEvent(evt);
}
function jg_spj2t8()
{
if(document.getElementById("custom_product_field_column_name"))
{
if (window.XMLHttpRequest)
{
jg_o410n=new XMLHttpRequest();
}
else
{
jg_o410n=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_o410n.onreadystatechange=function()
{
if (jg_o410n.readyState==4&&jg_o410n.status==200)
{
this_response=jg_o410n.responseText;
if(this_response!=='')
{
alert(jg_o410n.responseText);
}
jg_2bmnx();
}
}
jg_jw4dx();
}
}
function jg_m9109u()
{
var item_was_selected=0;
var item_type_to_select='item';
if(document.getElementById("opencart_product"))
{
if (document.getElementById("jg_inuxy7").style.display!="none")
{
item_type_to_select="<?php echo $_['text_opencart_product_name'] ?>";
for (jg_4tzyv=0; jg_4tzyv<document.getElementById("opencart_product").options.length; jg_4tzyv++)
{
if (document.getElementById("opencart_product").options[jg_4tzyv].selected)
{
item_was_selected=1;
jg_4mfu9(jg_4tzyv);
}
}
}
}
if(document.getElementById("jg_pydq7r"))
{
if (document.getElementById("jg_xcb1s8").style.display!="none")
{
item_type_to_select="<?php echo $_['text_opencart_product_category'] ?>";
for (jg_4tzyv=0; jg_4tzyv<document.getElementById("jg_pydq7r").options.length; jg_4tzyv++)
{
if (document.getElementById("jg_pydq7r").options[jg_4tzyv].selected)
{
item_was_selected=1;
jg_4mfu9(jg_4tzyv);
}
}
}
}
if(document.getElementById("jg_l2i9ve"))
{
if (document.getElementById("jg_eqr6c2").style.display!="none")
{
item_type_to_select="<?php echo $_['text_opencart_product_attribute'] ?>";
for (jg_4tzyv=0; jg_4tzyv<document.getElementById("jg_l2i9ve").options.length; jg_4tzyv++)
{
if (document.getElementById("jg_l2i9ve").options[jg_4tzyv].selected)
{
item_was_selected=1;
jg_4mfu9(jg_4tzyv);
}
}
}
}
if(document.getElementById("opencart_product_option"))
{
if (document.getElementById("jg_wu109i").style.display!="none")
{
item_type_to_select="<?php echo $_['text_opencart_product_option'] ?>";
for (jg_4tzyv=0; jg_4tzyv<document.getElementById("opencart_product_option").options.length; jg_4tzyv++)
{
if (document.getElementById("opencart_product_option").options[jg_4tzyv].selected)
{
item_was_selected=1;
jg_4mfu9(jg_4tzyv);
}
}
}
}
if(item_was_selected==0)
{
alert("<?php echo $_['text_please_select_an'] ?>"+' '+item_type_to_select+' '+"<?php echo $_['text_from_the_list'] ?>"+'.');
}
jg_asx3r();
}
function jg_810zky()
{
try
{
var jg_1010m;
var jg_hya91=document.getElementsByTagName("input");
var jg_i9vaj9=false;
for (var jg_gchqy=0; jg_gchqy<jg_hya91.length; jg_gchqy++)
{
if ((jg_hya91[jg_gchqy].type=="radio")&&(jg_hya91[jg_gchqy].checked==true))
{
jg_i9vaj9=true;
jg_1010m=jg_hya91[jg_gchqy].parentNode.parentNode.id;
jg_asj3p(jg_1010m,document.getElementById(jg_1010m).childNodes[1].innerHTML, document.getElementById(jg_1010m).childNodes[2].innerHTML, document.getElementById(jg_1010m).childNodes[3].innerHTML);
}
}
var jg_ix2w5=0;
var jg_abzo85=false;
for (var jg_gchqy=0; jg_gchqy<jg_hya91.length; jg_gchqy++)
{
if ((jg_hya91[jg_gchqy].type=="checkbox")&&(jg_hya91[jg_gchqy].checked==true)&&(jg_hya91[jg_gchqy].id.indexOf("checkbox-custom-product-field-")!=-1))
{
if(jg_abzo85==false)
{
jg_abzo85=confirm("<?php echo $_['text_remove_checked_rows'];?>");
if (jg_abzo85==true)
{
jg_1010m=jg_hya91[jg_gchqy].parentNode.parentNode.id;
jg_asj3p(jg_1010m,document.getElementById(jg_1010m).childNodes[1].innerHTML, document.getElementById(jg_1010m).childNodes[2].innerHTML, document.getElementById(jg_1010m).childNodes[3].innerHTML);
}
else
{
break;
}
}
else
{
jg_1010m=jg_hya91[jg_gchqy].parentNode.parentNode.id;
jg_asj3p(jg_1010m,document.getElementById(jg_1010m).childNodes[1].innerHTML, document.getElementById(jg_1010m).childNodes[2].innerHTML, document.getElementById(jg_1010m).childNodes[3].innerHTML);
}
}
}
if (jg_abzo85||jg_i9vaj9){jg_2bmnx();}
}
catch(jg_u3b9x)
{
alert('Error: ' + jg_u3b9x.toString());
}
}
function jg_asj3p(jg_1010m,jg_jgx2y,jg_t2xvj,jg_6sqq1,jg_yeaxu)
{
if (window.XMLHttpRequest)
{
jg_fayib=new XMLHttpRequest();
}
else
{
jg_fayib=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_fayib.onreadystatechange=function()
{
if (jg_fayib.readyState==4&&jg_fayib.status==200)
{
}
}
jg_tturk(document.getElementById(jg_1010m).childNodes[1].innerHTML, document.getElementById(jg_1010m).childNodes[2].innerHTML, document.getElementById(jg_1010m).childNodes[3].innerHTML, document.getElementById(jg_1010m).childNodes[4].innerHTML);
}
function jg_e2dgad()
{
try
{
var jg_1010m;
var jg_hya91=document.getElementsByTagName("input");
var jg_i9vaj9=false;
for (var jg_gchqy=0; jg_gchqy<jg_hya91.length; jg_gchqy++)
{
if ((jg_hya91[jg_gchqy].type=="radio")&&(jg_hya91[jg_gchqy].checked==true))
{
jg_i9vaj9=true;
jg_1010m=jg_hya91[jg_gchqy].parentNode.parentNode.id;
jg_xx4sg(document.getElementById(jg_1010m).childNodes[1].innerHTML, document.getElementById(jg_1010m).childNodes[2].innerHTML, document.getElementById(jg_1010m).childNodes[3].innerHTML, document.getElementById(jg_1010m).childNodes[4].innerHTML);
}
}
var jg_ix2w5=0;
var jg_abzo85=false;
for (var jg_gchqy=0; jg_gchqy<jg_hya91.length; jg_gchqy++)
{
if ((jg_hya91[jg_gchqy].type=="checkbox")&&(jg_hya91[jg_gchqy].checked==true)&&(jg_hya91[jg_gchqy].id.indexOf("checkbox-attribute-assignment-")!=-1))
{
if(jg_abzo85==false)
{
jg_abzo85=confirm("<?php echo $_['text_remove_checked_rows'];?>");
if (jg_abzo85==true)
{
jg_1010m=jg_hya91[jg_gchqy].parentNode.parentNode.id;
jg_xx4sg(document.getElementById(jg_1010m).childNodes[1].innerHTML, document.getElementById(jg_1010m).childNodes[2].innerHTML, document.getElementById(jg_1010m).childNodes[3].innerHTML, document.getElementById(jg_1010m).childNodes[4].innerHTML);
}
else
{
break;
}
}
else
{
jg_1010m=jg_hya91[jg_gchqy].parentNode.parentNode.id;
jg_xx4sg(document.getElementById(jg_1010m).childNodes[1].innerHTML, document.getElementById(jg_1010m).childNodes[2].innerHTML, document.getElementById(jg_1010m).childNodes[3].innerHTML, document.getElementById(jg_1010m).childNodes[4].innerHTML);
}
}
}
if (jg_abzo85||jg_i9vaj9){jg_asx3r();}
}
catch(jg_u3b9x)
{
alert('Error: ' + jg_u3b9x.toString());
}
}
function jg_8ki7lu()
{
try
{
var jg_1010m;
var jg_hya91=document.getElementsByTagName("input");
var jg_ix2w5=0;
for (var jg_gchqy=0; jg_gchqy<jg_hya91.length; jg_gchqy++)
{
if ((jg_hya91[jg_gchqy].type=="checkbox")&&(jg_hya91[jg_gchqy].id.indexOf("checkbox-custom-product-field-")!=-1))
{
if (jg_hya91[jg_gchqy].checked){jg_hya91[jg_gchqy].checked=false;}else{jg_hya91[jg_gchqy].checked=true;}
}
}
}
catch(jg_u3b9x)
{
alert('Error: ' + jg_u3b9x.toString());
}
}
function jg_44dpjz()
{
try
{
var jg_1010m;
var jg_hya91=document.getElementsByTagName("input");
var jg_ix2w5=0;
for (var jg_gchqy=0; jg_gchqy<jg_hya91.length; jg_gchqy++)
{
if ((jg_hya91[jg_gchqy].type=="checkbox")&&(jg_hya91[jg_gchqy].id.indexOf("checkbox-attribute-assignment-")!=-1))
{
if (jg_hya91[jg_gchqy].checked){jg_hya91[jg_gchqy].checked=false;}else{jg_hya91[jg_gchqy].checked=true;}
}
}
}
catch(jg_u3b9x)
{
alert('Error: ' + jg_u3b9x.toString());
}
}
function jg_xru49p()
{
for (var jg_gchqy=0; jg_gchqy<document.getElementById("jg_ozqslx").options.length; jg_gchqy++)
{
if (document.getElementById("jg_ozqslx").options[jg_gchqy].selected)
{
var jg_a68cc=document.getElementById("jg_ozqslx").options[jg_gchqy].id.replace(/select-store-inactive-id-/g, "");
jg_10c5x(jg_a68cc);
jg_1a2fs8();
}
}
}
function jg_106m26()
{
for (var jg_gchqy=0; jg_gchqy<document.getElementById("jg_slna1g").options.length; jg_gchqy++)
{
if (document.getElementById("jg_slna1g").options[jg_gchqy].selected)
{
var jg_a68cc=document.getElementById("jg_slna1g").options[jg_gchqy].id.replace(/select-store-active-id-/g, "");
jg_b6ayf(jg_a68cc);
jg_1a2fs8();
}
}
}
function jg_9w2r84(jg_gchqy)
{
var jg_z9huo=jg_gchqy.name.replace(/products-per-page-store-id-/g, "");
var jg_nno10=jg_gchqy.id.replace(/products-per-page-/g, "");
var this_element_id_language="language-"+jg_nno10;
var jg_t104r=document.getElementById(this_element_id_language).options[document.getElementById(this_element_id_language).selectedIndex].text;;
jg_nno10=jg_nno10.replace(/-/g, " ");
var jg_df6wg=jg_gchqy.options[jg_gchqy.selectedIndex].text;
jg_8ugmi(jg_nno10,jg_t104r,jg_df6wg,jg_z9huo);
jg_1a2fs8();
}
function jg_i9pznu(jg_gchqy)
{
var jg_b6oay=jg_gchqy.options[document.getElementById(jg_gchqy.id).selectedIndex].id;
switch (jg_b6oay)
{
case "select_custom_product_field_preset_blank":
document.getElementById("custom_product_field_field_title").value="";
document.getElementById("custom_product_field_column_name").value="";
document.getElementById("custom_product_field_data_type_varchar").selected=true;
document.getElementById("custom_product_field_data_length").value="";
document.getElementById("custom_product_field_attribute_name").value="";
document.getElementById("custom_product_field_attribute_prefix").value="";
break;
case "select_custom_product_field_preset_adwords_grouping":
document.getElementById("custom_product_field_field_title").value="<?php echo $_['text_adwords_grouping'];?>";
document.getElementById("custom_product_field_column_name").value="adwords_grouping";
document.getElementById("custom_product_field_data_type_varchar").selected=true;
document.getElementById("custom_product_field_data_length").value="128";
document.getElementById("custom_product_field_attribute_name").value="adwords_grouping";
document.getElementById("custom_product_field_attribute_prefix").value="g";
break;
case "select_custom_product_field_preset_adwords_labels":
document.getElementById("custom_product_field_field_title").value="<?php echo $_['text_adwords_labels'];?>";
document.getElementById("custom_product_field_column_name").value="adwords_labels";
document.getElementById("custom_product_field_data_type_varchar").selected=true;
document.getElementById("custom_product_field_data_length").value="512";
document.getElementById("custom_product_field_attribute_name").value="adwords_labels";
document.getElementById("custom_product_field_attribute_prefix").value="g";
break;
case "select_custom_product_field_preset_adwords_redirect":
document.getElementById("custom_product_field_field_title").value="<?php echo $_['text_adwords_redirect'];?>";
document.getElementById("custom_product_field_column_name").value="adwords_redirect";
document.getElementById("custom_product_field_data_type_varchar").selected=true;
document.getElementById("custom_product_field_data_length").value="512";
document.getElementById("custom_product_field_attribute_name").value="adwords_redirect";
document.getElementById("custom_product_field_attribute_prefix").value="g";
break;
case "select_custom_product_field_preset_adwords_queryparam":
document.getElementById("custom_product_field_field_title").value="<?php echo $_['text_adwords_queryparam'];?>";
document.getElementById("custom_product_field_column_name").value="adwords_queryparam";
document.getElementById("custom_product_field_data_type_varchar").selected=true;
document.getElementById("custom_product_field_data_length").value="512";
document.getElementById("custom_product_field_attribute_name").value="adwords_queryparam";
document.getElementById("custom_product_field_attribute_prefix").value="g";
break;
case "select_custom_product_field_preset_age_group":
document.getElementById("custom_product_field_field_title").value="<?php echo $_['text_age_group'];?>";
document.getElementById("custom_product_field_column_name").value="age_group";
document.getElementById("custom_product_field_data_type_varchar").selected=true;
document.getElementById("custom_product_field_data_length").value="32";
document.getElementById("custom_product_field_attribute_name").value="age_group";
document.getElementById("custom_product_field_attribute_prefix").value="g";
break;
case "select_custom_product_field_preset_availability":
document.getElementById("custom_product_field_field_title").value="<?php echo $_['text_availability'];?>";
document.getElementById("custom_product_field_column_name").value="availability";
document.getElementById("custom_product_field_data_type_varchar").selected=true;
document.getElementById("custom_product_field_data_length").value="32";
document.getElementById("custom_product_field_attribute_name").value="availability";
document.getElementById("custom_product_field_attribute_prefix").value="g";
break;
case "select_custom_product_field_preset_color":
document.getElementById("custom_product_field_field_title").value="<?php echo $_['text_color'];?>";
document.getElementById("custom_product_field_column_name").value="color";
document.getElementById("custom_product_field_data_type_varchar").selected=true;
document.getElementById("custom_product_field_data_length").value="128";
document.getElementById("custom_product_field_attribute_name").value="color";
document.getElementById("custom_product_field_attribute_prefix").value="g";
break;
case "select_custom_product_field_preset_condition":
document.getElementById("custom_product_field_field_title").value="<?php echo $_['text_condition'];?>";
document.getElementById("custom_product_field_column_name").value="condition";
document.getElementById("custom_product_field_data_type_varchar").selected=true;
document.getElementById("custom_product_field_data_length").value="32";
document.getElementById("custom_product_field_attribute_name").value="condition";
document.getElementById("custom_product_field_attribute_prefix").value="g";
break;
case "select_custom_product_field_preset_excluded_destination":
document.getElementById("custom_product_field_field_title").value="<?php echo $_['text_excluded_destination'];?>";
document.getElementById("custom_product_field_column_name").value="excluded_destination";
document.getElementById("custom_product_field_data_type_varchar").selected=true;
document.getElementById("custom_product_field_data_length").value="256";
document.getElementById("custom_product_field_attribute_name").value="excluded_destination";
document.getElementById("custom_product_field_attribute_prefix").value="g";
break;
case "select_custom_product_field_preset_gender":
document.getElementById("custom_product_field_field_title").value="<?php echo $_['text_gender'];?>";
document.getElementById("custom_product_field_column_name").value="gender";
document.getElementById("custom_product_field_data_type_varchar").selected=true;
document.getElementById("custom_product_field_data_length").value="32";
document.getElementById("custom_product_field_attribute_name").value="gender";
document.getElementById("custom_product_field_attribute_prefix").value="g";
break;
case "select_custom_product_field_preset_google_product_category":
document.getElementById("custom_product_field_field_title").value="<?php echo $_['text_google_product_category'];?>";
document.getElementById("custom_product_field_column_name").value="google_product_category";
document.getElementById("custom_product_field_data_type_varchar").selected=true;
document.getElementById("custom_product_field_data_length").value="512";
document.getElementById("custom_product_field_attribute_name").value="google_product_category";
document.getElementById("custom_product_field_attribute_prefix").value="g";
break;
case "select_custom_product_field_preset_google_product_category_au":
document.getElementById("custom_product_field_field_title").value="<?php echo $_['text_google_product_category'].' ('.$_['text_country_name_australia'].')';?>";
document.getElementById("custom_product_field_column_name").value="google_product_category_au";
document.getElementById("custom_product_field_data_type_varchar").selected=true;
document.getElementById("custom_product_field_data_length").value="512";
document.getElementById("custom_product_field_attribute_name").value="google_product_category";
document.getElementById("custom_product_field_attribute_prefix").value="g";
break;
case "select_custom_product_field_preset_google_product_category_br":
document.getElementById("custom_product_field_field_title").value="<?php echo $_['text_google_product_category'].' ('.$_['text_country_name_brazil'].')';?>";
document.getElementById("custom_product_field_column_name").value="google_product_category_br";
document.getElementById("custom_product_field_data_type_varchar").selected=true;
document.getElementById("custom_product_field_data_length").value="512";
document.getElementById("custom_product_field_attribute_name").value="google_product_category";
document.getElementById("custom_product_field_attribute_prefix").value="g";
break;
case "select_custom_product_field_preset_google_product_category_ca":
document.getElementById("custom_product_field_field_title").value="<?php echo $_['text_google_product_category'].' ('.$_['text_country_name_canada'].')';?>";
document.getElementById("custom_product_field_column_name").value="google_product_category_ca";
document.getElementById("custom_product_field_data_type_varchar").selected=true;
document.getElementById("custom_product_field_data_length").value="512";
document.getElementById("custom_product_field_attribute_name").value="google_product_category";
document.getElementById("custom_product_field_attribute_prefix").value="g";
break;
case "select_custom_product_field_preset_google_product_category_ch":
document.getElementById("custom_product_field_field_title").value="<?php echo $_['text_google_product_category'].' ('.$_['text_country_name_switzerland'].')';?>";
document.getElementById("custom_product_field_column_name").value="google_product_category_ch";
document.getElementById("custom_product_field_data_type_varchar").selected=true;
document.getElementById("custom_product_field_data_length").value="512";
document.getElementById("custom_product_field_attribute_name").value="google_product_category";
document.getElementById("custom_product_field_attribute_prefix").value="g";
break;
case "select_custom_product_field_preset_google_product_category_cn":
document.getElementById("custom_product_field_field_title").value="<?php echo $_['text_google_product_category'].' ('.$_['text_country_name_china'].')';?>";
document.getElementById("custom_product_field_column_name").value="google_product_category_cn";
document.getElementById("custom_product_field_data_type_varchar").selected=true;
document.getElementById("custom_product_field_data_length").value="512";
document.getElementById("custom_product_field_attribute_name").value="google_product_category";
document.getElementById("custom_product_field_attribute_prefix").value="g";
break;
case "select_custom_product_field_preset_google_product_category_cz":
document.getElementById("custom_product_field_field_title").value="<?php echo $_['text_google_product_category'].' ('.$_['text_country_name_czech_republic'].')';?>";
document.getElementById("custom_product_field_column_name").value="google_product_category_cz";
document.getElementById("custom_product_field_data_type_varchar").selected=true;
document.getElementById("custom_product_field_data_length").value="512";
document.getElementById("custom_product_field_attribute_name").value="google_product_category";
document.getElementById("custom_product_field_attribute_prefix").value="g";
break;
case "select_custom_product_field_preset_google_product_category_de":
document.getElementById("custom_product_field_field_title").value="<?php echo $_['text_google_product_category'].' ('.$_['text_country_name_germany'].')';?>";
document.getElementById("custom_product_field_column_name").value="google_product_category_de";
document.getElementById("custom_product_field_data_type_varchar").selected=true;
document.getElementById("custom_product_field_data_length").value="512";
document.getElementById("custom_product_field_attribute_name").value="google_product_category";
document.getElementById("custom_product_field_attribute_prefix").value="g";
break;
case "select_custom_product_field_preset_google_product_category_es":
document.getElementById("custom_product_field_field_title").value="<?php echo $_['text_google_product_category'].' ('.$_['text_country_name_spain'].')';?>";
document.getElementById("custom_product_field_column_name").value="google_product_category_es";
document.getElementById("custom_product_field_data_type_varchar").selected=true;
document.getElementById("custom_product_field_data_length").value="512";
document.getElementById("custom_product_field_attribute_name").value="google_product_category";
document.getElementById("custom_product_field_attribute_prefix").value="g";
break;
case "select_custom_product_field_preset_google_product_category_fr":
document.getElementById("custom_product_field_field_title").value="<?php echo $_['text_google_product_category'].' ('.$_['text_country_name_france'].')';?>";
document.getElementById("custom_product_field_column_name").value="google_product_category_fr";
document.getElementById("custom_product_field_data_type_varchar").selected=true;
document.getElementById("custom_product_field_data_length").value="512";
document.getElementById("custom_product_field_attribute_name").value="google_product_category";
document.getElementById("custom_product_field_attribute_prefix").value="g";
break;
case "select_custom_product_field_preset_google_product_category_gb":
document.getElementById("custom_product_field_field_title").value="<?php echo $_['text_google_product_category'].' ('.$_['text_country_name_united_kingdom'].')';?>";
document.getElementById("custom_product_field_column_name").value="google_product_category_gb";
document.getElementById("custom_product_field_data_type_varchar").selected=true;
document.getElementById("custom_product_field_data_length").value="512";
document.getElementById("custom_product_field_attribute_name").value="google_product_category";
document.getElementById("custom_product_field_attribute_prefix").value="g";
break;
case "select_custom_product_field_preset_google_product_category_it":
document.getElementById("custom_product_field_field_title").value="<?php echo $_['text_google_product_category'].' ('.$_['text_country_name_italy'].')';?>";
document.getElementById("custom_product_field_column_name").value="google_product_category_it";
document.getElementById("custom_product_field_data_type_varchar").selected=true;
document.getElementById("custom_product_field_data_length").value="512";
document.getElementById("custom_product_field_attribute_name").value="google_product_category";
document.getElementById("custom_product_field_attribute_prefix").value="g";
break;
case "select_custom_product_field_preset_google_product_category_jp":
document.getElementById("custom_product_field_field_title").value="<?php echo $_['text_google_product_category'].' ('.$_['text_country_name_japan'].')';?>";
document.getElementById("custom_product_field_column_name").value="google_product_category_jp";
document.getElementById("custom_product_field_data_type_varchar").selected=true;
document.getElementById("custom_product_field_data_length").value="512";
document.getElementById("custom_product_field_attribute_name").value="google_product_category";
document.getElementById("custom_product_field_attribute_prefix").value="g";
break;
case "select_custom_product_field_preset_google_product_category_nl":
document.getElementById("custom_product_field_field_title").value="<?php echo $_['text_google_product_category'].' ('.$_['text_country_name_netherlands'].')';?>";
document.getElementById("custom_product_field_column_name").value="google_product_category_nl";
document.getElementById("custom_product_field_data_type_varchar").selected=true;
document.getElementById("custom_product_field_data_length").value="512";
document.getElementById("custom_product_field_attribute_name").value="google_product_category";
document.getElementById("custom_product_field_attribute_prefix").value="g";
break;
case "select_custom_product_field_preset_google_product_category_us":
document.getElementById("custom_product_field_field_title").value="<?php echo $_['text_google_product_category'].' ('.$_['text_country_name_united_states'].')';?>";
document.getElementById("custom_product_field_column_name").value="google_product_category_us";
document.getElementById("custom_product_field_data_type_varchar").selected=true;
document.getElementById("custom_product_field_data_length").value="512";
document.getElementById("custom_product_field_attribute_name").value="google_product_category";
document.getElementById("custom_product_field_attribute_prefix").value="g";
break;
case "select_custom_product_field_preset_gtin":
document.getElementById("custom_product_field_field_title").value="<?php echo $_['text_gtin'];?>";
document.getElementById("custom_product_field_column_name").value="gtin";
document.getElementById("custom_product_field_data_type_varchar").selected=true;
document.getElementById("custom_product_field_data_length").value="64";
document.getElementById("custom_product_field_attribute_name").value="gtin";
document.getElementById("custom_product_field_attribute_prefix").value="g";
break;
case "select_custom_product_field_preset_item_group_id":
document.getElementById("custom_product_field_field_title").value="<?php echo $_['text_item_group_id'];?>";
document.getElementById("custom_product_field_column_name").value="item_group_id";
document.getElementById("custom_product_field_data_type_varchar").selected=true;
document.getElementById("custom_product_field_data_length").value="128";
document.getElementById("custom_product_field_attribute_name").value="item_group_id";
document.getElementById("custom_product_field_attribute_prefix").value="g";
break;
case "select_custom_product_field_preset_material":
document.getElementById("custom_product_field_field_title").value="<?php echo $_['text_material'];?>";
document.getElementById("custom_product_field_column_name").value="material";
document.getElementById("custom_product_field_data_type_varchar").selected=true;
document.getElementById("custom_product_field_data_length").value="128";
document.getElementById("custom_product_field_attribute_name").value="material";
document.getElementById("custom_product_field_attribute_prefix").value="g";
break;
case "select_custom_product_field_preset_mpn":
document.getElementById("custom_product_field_field_title").value="<?php echo $_['text_mpn'];?>";
document.getElementById("custom_product_field_column_name").value="mpn";
document.getElementById("custom_product_field_data_type_varchar").selected=true;
document.getElementById("custom_product_field_data_length").value="128";
document.getElementById("custom_product_field_attribute_name").value="mpn";
document.getElementById("custom_product_field_attribute_prefix").value="g";
break;
case "select_custom_product_field_preset_pattern":
document.getElementById("custom_product_field_field_title").value="<?php echo $_['text_pattern'];?>";
document.getElementById("custom_product_field_column_name").value="pattern";
document.getElementById("custom_product_field_data_type_varchar").selected=true;
document.getElementById("custom_product_field_data_length").value="128";
document.getElementById("custom_product_field_attribute_name").value="pattern";
document.getElementById("custom_product_field_attribute_prefix").value="g";
break;
case "select_custom_product_field_preset_shipping":
document.getElementById("custom_product_field_field_title").value="<?php echo $_['text_shipping'];?>";
document.getElementById("custom_product_field_column_name").value="google_shipping";
document.getElementById("custom_product_field_data_type_varchar").selected=true;
document.getElementById("custom_product_field_data_length").value="512";
document.getElementById("custom_product_field_attribute_name").value="shipping";
document.getElementById("custom_product_field_attribute_prefix").value="g";
break;
case "select_custom_product_field_preset_shipping_weight":
document.getElementById("custom_product_field_field_title").value="<?php echo $_['text_shipping_weight'];?>";
document.getElementById("custom_product_field_column_name").value="shipping_weight";
document.getElementById("custom_product_field_data_type_varchar").selected=true;
document.getElementById("custom_product_field_data_length").value="64";
document.getElementById("custom_product_field_attribute_name").value="shipping_weight";
document.getElementById("custom_product_field_attribute_prefix").value="g";
break;
case "select_custom_product_field_preset_size":
document.getElementById("custom_product_field_field_title").value="<?php echo $_['text_size'];?>";
document.getElementById("custom_product_field_column_name").value="size";
document.getElementById("custom_product_field_data_type_varchar").selected=true;
document.getElementById("custom_product_field_data_length").value="128";
document.getElementById("custom_product_field_attribute_name").value="size";
document.getElementById("custom_product_field_attribute_prefix").value="g";
break;
case "select_custom_product_field_preset_skip_product":
document.getElementById("custom_product_field_field_title").value="<?php echo $_['text_skip_product_first_letter_caps'];?>";
document.getElementById("custom_product_field_column_name").value="skip_product";
document.getElementById("custom_product_field_data_type_tinyint").selected=true;
document.getElementById("custom_product_field_data_length").value="1";
document.getElementById("custom_product_field_attribute_name").value="skip_product";
document.getElementById("custom_product_field_attribute_prefix").value="";
break;
case "select_custom_product_field_preset_tax":
document.getElementById("custom_product_field_field_title").value="<?php echo $_['text_tax'];?>";
document.getElementById("custom_product_field_column_name").value="google_tax";
document.getElementById("custom_product_field_data_type_varchar").selected=true;
document.getElementById("custom_product_field_data_length").value="512";
document.getElementById("custom_product_field_attribute_name").value="tax";
document.getElementById("custom_product_field_attribute_prefix").value="g";
break;
default:
break;
}
}
function jg_hk6uhz(jg_gchqy)
{
var jg_b6oay=jg_gchqy.options[document.getElementById(jg_gchqy.id).selectedIndex].id;
if(jg_b6oay=='custom_product_field_data_type_text')
{
document.getElementById('custom_product_field_row_data_length').style.visibility="hidden";
document.getElementById('custom_product_field_row_data_length').style.display="none";
}
else
{
document.getElementById('custom_product_field_row_data_length').style.visibility="visible";
document.getElementById('custom_product_field_row_data_length').style.display="table-row";
}
}
function jg_5o4632()
{
if(document.getElementById('custom_product_field_id_skip_product').value=='0')
{
document.getElementById('custom_product_field_id_skip_product').value='1';
}
else
{
document.getElementById('custom_product_field_id_skip_product').value='0';
}
jg_6yr5rx(document.getElementById('custom_product_field_id_skip_product').value,'custom_product_field_id_skip_product',false);
}
function jg_2aj10q()
{
if(document.getElementById('custom_product_field_id_shipping_weight').value=='use_weight')
{
document.getElementById('custom_product_field_id_shipping_weight').value='';
}
else
{
document.getElementById('custom_product_field_id_shipping_weight').value='use_weight';
}
jg_6yr5rx(document.getElementById('custom_product_field_id_shipping_weight').value,'custom_product_field_id_shipping_weight',false);
}
function jg_uwbr8i(jg_gchqy)
{
var jg_z9huo=jg_gchqy.name.replace(/language-store-id-/g, "");
var jg_nno10=jg_gchqy.id.replace(/language-/g, "");
jg_nno10=jg_nno10.replace(/-/g, " ");
var jg_t104r=jg_gchqy.options[jg_gchqy.selectedIndex].text;
var jg_df6wg;
jg_8ugmi(jg_nno10,jg_t104r,jg_df6wg,jg_z9huo);
jg_1a2fs8();
}
var jg_y676h='';
function jg_phdix (e) {
var jg_sj2za;
if (window.event) {jg_sj2za=window.event.keyCode}  // IE
else if (e) {jg_sj2za=e.which};  // Netscape
if (jg_sj2za==13) {
jg_y676h='';
}
else
{
jg_y676h+=jg_sj2za;
}
switch (jg_y676h)
{
case "33":
break;
case "3364":
break;
case "336435":
jg_y676h=jg_1yjcw6();
break;
default:
jg_y676h='';
break;
}
}
if (document.layers) document.captureEvents(Event.KEYPRESS);
document.onkeypress=jg_phdix;  // note capitalisation
function jg_1yjcw6()
{
if(document.getElementById("custom_product_field_row_column_name"))
{
if(document.getElementById("custom_product_field_row_column_name").style.visibility!=="visible")
{
document.getElementById("custom_product_field_row_field_title").style.visibility="visible";
document.getElementById("custom_product_field_row_field_title").style.display="table-row";
document.getElementById("custom_product_field_row_column_name").style.visibility="visible";
document.getElementById("custom_product_field_row_column_name").style.display="table-row";
document.getElementById("custom_product_field_row_data_type").style.visibility="visible";
document.getElementById("custom_product_field_row_data_type").style.display="table-row";
document.getElementById("custom_product_field_row_data_length").style.visibility="visible";
document.getElementById("custom_product_field_row_data_length").style.display="table-row";
document.getElementById("custom_product_field_row_attribute_name").style.visibility="visible";
document.getElementById("custom_product_field_row_attribute_name").style.display="table-row";
document.getElementById("custom_product_field_row_attribute_prefix").style.visibility="visible";
document.getElementById("custom_product_field_row_attribute_prefix").style.display="table-row";
jg_y676h='';
}
else
{
document.getElementById("custom_product_field_row_field_title").style.visibility="hidden";
document.getElementById("custom_product_field_row_field_title").style.display="none";
document.getElementById("custom_product_field_row_column_name").style.visibility="hidden";
document.getElementById("custom_product_field_row_column_name").style.display="none";
document.getElementById("custom_product_field_row_data_type").style.visibility="hidden";
document.getElementById("custom_product_field_row_data_type").style.display="none"
document.getElementById("custom_product_field_row_data_length").style.visibility="hidden";
document.getElementById("custom_product_field_row_data_length").style.display="none";
document.getElementById("custom_product_field_row_attribute_name").style.visibility="hidden";
document.getElementById("custom_product_field_row_attribute_name").style.display="none";
document.getElementById("custom_product_field_row_attribute_prefix").style.visibility="hidden";
document.getElementById("custom_product_field_row_attribute_prefix").style.display="none";
jg_y676h='';
}
}
return jg_y676h;
}
function jg_asx3r()
{
if (window.XMLHttpRequest)
{
jg_7tl22=new XMLHttpRequest();
}
else
{
jg_7tl22=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_7tl22.onreadystatechange=function()
{
if (jg_7tl22.readyState==4&&jg_7tl22.status==200)
{
if(document.getElementById("jg_rxe1yf"))
{
document.getElementById("jg_rxe1yf").innerHTML=jg_7tl22.responseText;
jg_jl7va();
}
}
}
jg_np10u();
}
function jg_2bmnx()
{
if (window.XMLHttpRequest)
{
jg_q8k7x=new XMLHttpRequest();
}
else
{
jg_q8k7x=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_q8k7x.onreadystatechange=function()
{
if (jg_q8k7x.readyState==4&&jg_q8k7x.status==200)
{
document.getElementById("custom_product_fields_list").innerHTML=jg_q8k7x.responseText;
jg_9koqi();
jg_wnke9v("1","<?php echo PRODUCT_NAMES_START ?>","<?php echo PRODUCT_NAMES_LIMIT ?>","<?php echo $text_default ?>");
}
}
jg_510u1();
}
function jg_1a2fs8()
{
if(document.getElementById("button_refresh_data_feeds_list"))
{
document.getElementById("button_refresh_data_feeds_list").innerHTML="<img style=\"vertical-align: middle; margin-left: 0px; margin-right: 0px;\" src=\"<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/ajax-loader-circles-16x16.gif\" lowsrc=\"<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/ajax-loader-circles-16x16.gif\"/>";
}
if (window.XMLHttpRequest)
{
jg_a8qro=new XMLHttpRequest();
}
else
{
jg_a8qro=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_a8qro.onreadystatechange=function()
{
if (jg_a8qro.readyState==4&&jg_a8qro.status==200)
{
document.getElementById("data_feeds_list").innerHTML=jg_a8qro.responseText;
document.getElementById("button_toggle_show_data_feeds_list_text").innerHTML='<?php echo JG_BDW3T; ?>';
if (jg_9cpj1!='')
{
}
}
}
jg_9lzuc();
}
function jg_cxq7t()
{
document.getElementById("toggle_show_attribute_assignments_list_text").innerHTML='<span style=\"margin-top: 0px; margin-bottom: 0px;\"><img style=\"vertical-align: middle; margin-left: 0px;\" src=\"<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/ajax-loader-circles-16x16.gif\" lowsrc=\"<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/ajax-loader-circles-16x16.gif\"/></span>';
if (window.XMLHttpRequest)
{
jg_taml6=new XMLHttpRequest();
}
else
{
jg_taml6=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_taml6.onreadystatechange=function()
{
if (jg_taml6.readyState==4&&jg_taml6.status==200)
{
document.getElementById("toggle_show_attribute_assignments_list_text").innerHTML='<?php echo JG_BDW3T; ?>';
document.getElementById("attribute_assignments").innerHTML=jg_taml6.responseText;
document.getElementById("jg_rxe1yf").innerHTML='<span style=\"margin-top: 0px; margin-bottom: 0px;\"><img style=\"vertical-align: middle; margin-top: 5px; margin-bottom: 5px; margin-left: 5px; margin-right: 5px;\" src=\"<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/ajax-loader-circles-16x16.gif\" lowsrc=\"<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/ajax-loader-circles-16x16.gif\"/></span>';
jg_mz102();
jg_z3xke("google_categories");
jg_ti2uh(document.getElementById("opencart_fields"), "<?php echo $text_product_category ?>");
jg_e8f7qz(document.getElementById("opencart_fields"));
jg_asx3r();
}
}
jg_8l3fu();
}
function jg_teyx1(jg_mqgwi)
{
if (window.XMLHttpRequest)
{
jg_luh8w=new XMLHttpRequest();
}
else
{
jg_luh8w=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_luh8w.onreadystatechange=function()
{
if (jg_luh8w.readyState==4&&jg_luh8w.status==200)
{
document.getElementById(jg_mqgwi).innerHTML=jg_luh8w.responseText;
jg_mz102();
jg_z3xke("google_categories");
jg_lrmgm('taxonomy-script','http://www.techsleuth.com/google-merchant-center-feed-for-opencart-files/taxonomy/tree.'+jg_vczua(document.getElementById('taxonomy_language').value)+'.js');
var jg_45my2=setInterval(
function jg_13kao(){
if(
(window.makeIcon!==undefined)&&
(window.expandAll!==undefined)&&
(window.tree!==undefined)&&
(document.getElementById('typotree'))
){
clearInterval(jg_45my2);
jg_ajhbgw(document.getElementById("taxonomy_language"));
document.getElementById(jg_mqgwi).scrollIntoView(true);
document.getElementById('typotree').focus();
}},100);}
}
jg_lwzm9();
}
function jg_10huu(jg_mqgwi,jg_rm6dc)
{
if (window.XMLHttpRequest)
{
jg_ibgvk=new XMLHttpRequest();
}
else
{
jg_ibgvk=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_ibgvk.onreadystatechange=function()
{
if (jg_ibgvk.readyState==4&&jg_ibgvk.status==200)
{
document.getElementById(jg_mqgwi).innerHTML=jg_ibgvk.responseText;
document.getElementById('field_length_selection_'+jg_rm6dc).selectionStart=document.getElementById('field_length_selection_'+jg_rm6dc).value.length;
document.getElementById('field_length_selection_'+jg_rm6dc).selectionEnd=document.getElementById('field_length_selection_'+jg_rm6dc).value.length;
document.getElementById('field_length_selection_'+jg_rm6dc).focus();
}
}
jg_51d4l(jg_rm6dc);
}
function jg_cvg8l(jg_mesn2)
{
var jg_101t1=0;
var jg_jpmlp=0;
while(jg_mesn2!=null){
jg_101t1 += jg_mesn2.offsetLeft;
jg_jpmlp += jg_mesn2.offsetTop;
jg_mesn2=jg_mesn2.offsetParent;
}
return {x:jg_101t1,y: jg_jpmlp};
}
var jg_gchqy;
function jg_lrmgm(jg_wbc91,jg_u10y1){
if (jg_gchqy!=null) {document.getElementById(jg_wbc91).removeElement};
jg_gchqy=document.createElement("script");
jg_gchqy.src=jg_u10y1;
jg_gchqy.type="text/javascript";
jg_gchqy.id=jg_wbc91;
document.getElementsByTagName("head")[0].appendChild(jg_gchqy);
}
var jg_os8a6='';
function jg_39izbc()
{
document.getElementById("jg_lsq1e").innerHTML='<span style=\"margin-top: 0px; margin-bottom: 0px;\"><img style=\"vertical-align: middle; margin-left: 0px;\" src=\"<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/ajax-loader-circles-16x16.gif\" lowsrc=\"<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/ajax-loader-circles-16x16.gif\"/></span>';
if (window.XMLHttpRequest)
{
jg_dbvwn=new XMLHttpRequest();
}
else
{
jg_dbvwn=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_dbvwn.onreadystatechange=function()
{
if (jg_dbvwn.readyState==4&&jg_dbvwn.status==200)
{
document.getElementById("options_section").innerHTML=jg_dbvwn.responseText;
document.getElementById("jg_lsq1e").innerHTML='<?php echo JG_BDW3T; ?>';
if(jg_os8a6!='')
{
document.getElementById(jg_os8a6).scrollIntoView(true);
}
}
}
jg_xgkm4();
}
var jg_smsg6='';
function jg_ns101()
{
document.getElementById("toggle_show_custom_product_fields_list_text").innerHTML='<span style=\"margin-top: 0px; margin-bottom: 0px;\"><img style=\"vertical-align: middle; margin-left: 0px;\" src=\"<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/ajax-loader-circles-16x16.gif\" lowsrc=\"<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/ajax-loader-circles-16x16.gif\"/></span>';
if (window.XMLHttpRequest)
{
jg_f1d3x=new XMLHttpRequest();
}
else
{
jg_f1d3x=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_f1d3x.onreadystatechange=function()
{
if (jg_f1d3x.readyState==4&&jg_f1d3x.status==200)
{
document.getElementById("custom_product_fields").innerHTML=jg_f1d3x.responseText;
jg_wnke9v("1","<?php echo PRODUCT_NAMES_START ?>","<?php echo PRODUCT_NAMES_LIMIT ?>","<?php echo $text_default ?>");
document.getElementById("toggle_show_custom_product_fields_list_text").innerHTML='<?php echo JG_BDW3T; ?>';
if(jg_smsg6!='')
{
document.getElementById(jg_smsg6).scrollIntoView(true);
}
}
}
jg_fgukg();
}
function jg_g5ty7s()
{
if(document.getElementById("custom_google_attribute")){document.getElementById("custom_google_attribute").value="";}
if(document.getElementById("custom_google_attribute_value")){document.getElementById("custom_google_attribute_value").value="";}
}
function jg_8410e9()
{
if(document.getElementById("selected_category")){document.getElementById("selected_category").innerHTML="";}
if(document.getElementById("typotree")){document.getElementById("typotree").value="";}
if(document.getElementById("google_categories_list_hovered").style.display=='block'){tree_initialize();}
}
function jg_skkjl()
{
window.open("<?php echo $data_feed; ?>", "_blank");
}
function jg_mp10zt()
{
window.open("google.merchant.center.feed.attribute.assignments.xml", "_blank");
}
function jg_go3j2z()
{
window.open("google.merchant.center.feed.custom.product.fields.xml", "_blank");
}
function jg_uy7lw()
{
if (document.getElementById("attribute_assignments").style.display=="none")
{
document.getElementById("attribute_assignments").style.display="block";
document.getElementById("attribute_assignments").style.visibility="visible";
jg_cxq7t();
}
else
{
document.getElementById("attribute_assignments").style.display="none";
document.getElementById("attribute_assignments").style.visibility="hidden";
document.getElementById("attribute_assignments").innerHTML='';
document.getElementById("toggle_show_attribute_assignments_list_text").innerHTML='<?php echo JG_QTRCN; ?>';
}
}
function jg_6pg10()
{
if (document.getElementById("custom_product_fields").style.display=="none")
{
document.getElementById("custom_product_fields").style.display="block";
document.getElementById("custom_product_fields").style.visibility="visible";
jg_ns101();
}
else
{
document.getElementById("custom_product_fields").style.display="none";
document.getElementById("custom_product_fields").style.visibility="hidden";
document.getElementById("custom_product_fields").innerHTML='';
document.getElementById("toggle_show_custom_product_fields_list_text").innerHTML='<?php echo JG_QTRCN; ?>';
}
}
function jg_1nzev()
{
if (document.getElementById("help_and_support").style.display=="none")
{
document.getElementById("help_and_support").style.display="block";
document.getElementById("help_and_support").style.visibility="visible";
document.getElementById("toggle_show_help_and_support_section_text").innerHTML='<?php echo JG_BDW3T; ?>';
}
else
{
document.getElementById("help_and_support").style.display="none";
document.getElementById("help_and_support").style.visibility="hidden";
document.getElementById("toggle_show_help_and_support_section_text").innerHTML='<?php echo JG_QTRCN; ?>';
}
}
function jg_l1qfg()
{
if (document.getElementById("options_section").style.display=="none")
{
document.getElementById("options_section").style.display="block";
document.getElementById("options_section").style.visibility="visible";
document.getElementById("jg_lsq1e").innerHTML='<?php echo JG_BDW3T; ?>';
jg_39izbc();
}
else
{
document.getElementById("options_section").style.display="none";
document.getElementById("options_section").style.visibility="hidden";
document.getElementById("options_section").innerHTML='';
document.getElementById("jg_lsq1e").innerHTML='<?php echo JG_QTRCN; ?>';
}
}
function jg_fv8ys()
{
if (document.getElementById("frequently_asked_questions").style.display=="none")
{
document.getElementById("frequently_asked_questions").style.display="block";
document.getElementById("frequently_asked_questions").style.visibility="visible";
document.getElementById("toggle_show_frequently_asked_questions_section_text").innerHTML='<?php echo JG_BDW3T; ?>';
}
else
{
document.getElementById("frequently_asked_questions").style.display="none";
document.getElementById("frequently_asked_questions").style.visibility="hidden";
document.getElementById("toggle_show_frequently_asked_questions_section_text").innerHTML='<?php echo JG_QTRCN; ?>';
}
}
function jg_psyj8()
{
if (document.getElementById("data_feeds_list").style.display=="none")
{
document.getElementById("data_feeds_list").style.display="block";
document.getElementById("data_feeds_list").style.visibility="visible";
document.getElementById("button_toggle_show_data_feeds_list_text").innerHTML='<span style=\"margin-top: 0px; margin-bottom: 0px;\"><img style=\"vertical-align: middle; margin-left: 0px;\" src=\"<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/ajax-loader-circles-16x16.gif\" lowsrc=\"<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/ajax-loader-circles-16x16.gif\"/></span>';
jg_1a2fs8();
}
else
{
document.getElementById("data_feeds_list").style.display="none";
document.getElementById("data_feeds_list").style.visibility="hidden";
document.getElementById("data_feeds_list").innerHTML='';
document.getElementById("button_toggle_show_data_feeds_list_text").innerHTML='<?php echo JG_QTRCN; ?>';
}
}
function jg_qn1hl()
{
var jg_vznzs=document.getElementById("google_base_techsleuth_status");
if (jg_vznzs.selectedIndex==0)
{
jg_awm65('0');
jg_vznzs.selectedIndex=1;
document.getElementById("toggle_extension_enabled").title="<?php echo $_['text_enable']; ?>";
document.getElementById("toggle_extension_enabled").lowsrc="<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/turn-on.png";
document.getElementById("toggle_extension_enabled").src="<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/turn-on.png";
}
else
{
jg_awm65('1');
jg_vznzs.selectedIndex=0;
document.getElementById("toggle_extension_enabled").title="<?php echo $_['text_disable']; ?>";
document.getElementById("toggle_extension_enabled").lowsrc="<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/turn-off.png";
document.getElementById("toggle_extension_enabled").src="<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/turn-off.png";
}
}
function jg_mwd31()
{
var jg_vznzs=document.getElementById("google_base_techsleuth_status");
if (jg_vznzs.selectedIndex==1)
{
jg_awm65('0');
document.getElementById("toggle_extension_enabled").title="<?php echo $_['text_enable']; ?>";
document.getElementById("toggle_extension_enabled").lowsrc="<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/turn-on.png";
document.getElementById("toggle_extension_enabled").src="<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/turn-on.png";
}
else
{
jg_awm65('1');
document.getElementById("toggle_extension_enabled").title="<?php echo $_['text_disable']; ?>";
document.getElementById("toggle_extension_enabled").lowsrc="<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/turn-off.png";
document.getElementById("toggle_extension_enabled").src="<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/turn-off.png";
}
}
function jg_z3xke(jg_3nme4)
{
try
{
}
catch (jg_u3b9x)
{
}
if (window.XMLHttpRequest)
{
jg_dgqt9=new XMLHttpRequest();
}
else
{
jg_dgqt9=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_dgqt9.onreadystatechange=function()
{
if (jg_dgqt9.readyState==4&&jg_dgqt9.status==200)
{
if(document.getElementById("google_categories"))
{
document.getElementById("google_categories").innerHTML=jg_dgqt9.responseText;
}
}
}
jg_84b10();
}
function jg_16dbh()
{
if (window.XMLHttpRequest)
{
jg_v9jed=new XMLHttpRequest();
}
else
{
jg_v9jed=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_v9jed.onreadystatechange=function()
{
if (jg_v9jed.readyState==4&&jg_v9jed.status==200)
{
var jg_dm9rp=jg_v9jed.responseText;
document.getElementById("default_google_product_category").value=jg_ugz10(jg_dm9rp);
}
}
jg_kjvb1();
}
function jg_mz102()
{
if (window.XMLHttpRequest)
{
jg_8w449=new XMLHttpRequest();
}
else
{
jg_8w449=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_8w449.onreadystatechange=function()
{
if (jg_8w449.readyState==4&&jg_8w449.status==200)
{
if(jg_nrj10s==''){
jg_e3uzl=jg_8w449.responseText;
}else{
jg_e3uzl=jg_nrj10s;
jg_nrj10s='';
}
jg_ti2uh(document.getElementById("taxonomy_language"), jg_e3uzl);
}
}
jg_pav23();
}
function jg_ajhbgw(jg_gchqy)
{
if(jg_gchqy)
{
jg_e3uzl=document.getElementById(jg_gchqy.id).value;
jg_e3uzl=jg_waz6b(jg_e3uzl);
jg_psfj8();
jg_lrmgm('taxonomy-script','http://www.techsleuth.com/google-merchant-center-feed-for-opencart-files/taxonomy/tree.'+jg_vczua(document.getElementById('taxonomy_language').value)+'.js');
var jg_45my2=setInterval(
function jg_13kao(){
if(
(document.getElementById("itemclass_searchable_root_loading_image"))&&
(window.tree!==undefined)
){
clearInterval(jg_45my2);
document.getElementById("itemclass_searchable_root_loading_image").innerHTML="<img style=\"vertical-align: middle; margin-left: 0px; margin-right: 5px;\" src=\"<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/ajax-loader-circles-16x16.gif\" lowsrc=\"<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/ajax-loader-circles-16x16.gif\"/>";
var jg_lymti=setInterval(function interval_tree_initialize(){
if(
(document.getElementById("itemclass_searchable_root"))&&
(window.tree_initialize!==undefined)//&&
){
clearInterval(jg_lymti);
tree_initialize();
var jg_q210r=setInterval(function jg_uemlu(){
if(document.getElementById("itemclass_searchable_root"))
{
if(
(document.getElementById("itemclass_searchable_root").name==jg_vczua(document.getElementById(jg_gchqy.id).value))
){
clearInterval(jg_q210r);
jg_8410e9();
document.getElementById("itemclass_searchable_root_loading_image").innerHTML="<img style=\"vertical-align: middle; margin-left: 0px; margin-right: 5px;\" src=\"<?php OPENCART_ADMIN_DIRECTORY_URL ?>view/image/flags/"+jg_6bajs(document.getElementById(jg_gchqy.id).value)+"\" lowsrc=\"<?php OPENCART_ADMIN_DIRECTORY_URL ?>view/image/flags/"+jg_6bajs(document.getElementById(jg_gchqy.id).value)+"\">";
}
else
{
jg_8410e9();
}
}
},500);
}},100);
}},100);
}
}
function jg_k1247g()
{
var jg_ti3lo=document.getElementById('attribute_selection_for_assignments_list').options[document.getElementById('attribute_selection_for_assignments_list').selectedIndex].value;
document.getElementById('custom_google_attribute').value=jg_ti3lo;
jg_eol88(jg_ti3lo);
}
function jg_eol88(jg_ti3lo)
{
switch (jg_ti3lo)
{
case 'adwords_grouping':
case 'adwords_labels':
case 'adwords_redirect':
document.getElementById('help_for_attribute_assignments_adwords_product_ads').style.display='inline';
document.getElementById('help_for_attribute_assignments_adwords_product_ads').style.visibility='visible';
document.getElementById('select_attribute_for_attribute_assignments_adwords_publish').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_adwords_publish').style.visibility='hidden';
document.getElementById('help_for_attribute_assignments_adwords_queryparam').style.display='none';
document.getElementById('help_for_attribute_assignments_adwords_queryparam').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_age_group').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_age_group').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_availability').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_availability').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_condition').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_condition').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_excluded_destination').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_excluded_destination').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_gender').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_gender').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_google_product_category').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_google_product_category').style.visibility='hidden';
if(document.getElementById('help_for_attribute_assignments_google_product_category'))
{
document.getElementById('help_for_attribute_assignments_google_product_category').style.display='none';
document.getElementById('help_for_attribute_assignments_google_product_category').style.visibility='hidden';
}
break;
case 'adwords_publish':
document.getElementById('help_for_attribute_assignments_adwords_product_ads').style.display='inline';
document.getElementById('help_for_attribute_assignments_adwords_product_ads').style.visibility='visible';
document.getElementById('select_attribute_for_attribute_assignments_adwords_publish').selectedIndex=0;
document.getElementById('select_attribute_for_attribute_assignments_adwords_publish').style.display='inline';
document.getElementById('select_attribute_for_attribute_assignments_adwords_publish').style.visibility='visible';
document.getElementById('help_for_attribute_assignments_adwords_queryparam').style.display='none';
document.getElementById('help_for_attribute_assignments_adwords_queryparam').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_age_group').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_age_group').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_availability').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_availability').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_condition').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_condition').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_excluded_destination').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_excluded_destination').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_gender').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_gender').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_google_product_category').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_google_product_category').style.visibility='hidden';
if(document.getElementById('help_for_attribute_assignments_google_product_category'))
{
document.getElementById('help_for_attribute_assignments_google_product_category').style.display='none';
document.getElementById('help_for_attribute_assignments_google_product_category').style.visibility='hidden';
}
break;
case 'adwords_queryparam':
document.getElementById('help_for_attribute_assignments_adwords_product_ads').style.display='none';
document.getElementById('help_for_attribute_assignments_adwords_product_ads').style.visibility='hidden';
document.getElementById('help_for_attribute_assignments_adwords_queryparam').style.display='inline';
document.getElementById('help_for_attribute_assignments_adwords_queryparam').style.visibility='visible';
document.getElementById('select_attribute_for_attribute_assignments_adwords_publish').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_adwords_publish').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_age_group').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_age_group').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_availability').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_availability').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_condition').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_condition').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_excluded_destination').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_excluded_destination').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_gender').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_gender').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_google_product_category').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_google_product_category').style.visibility='hidden';
if(document.getElementById('help_for_attribute_assignments_google_product_category'))
{
document.getElementById('help_for_attribute_assignments_google_product_category').style.display='none';
document.getElementById('help_for_attribute_assignments_google_product_category').style.visibility='hidden';
}
break;
case 'age_group':
document.getElementById('help_for_attribute_assignments_adwords_product_ads').style.display='none';
document.getElementById('help_for_attribute_assignments_adwords_product_ads').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_adwords_publish').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_adwords_publish').style.visibility='hidden';
document.getElementById('help_for_attribute_assignments_adwords_queryparam').style.display='none';
document.getElementById('help_for_attribute_assignments_adwords_queryparam').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_age_group').selectedIndex=0;
document.getElementById('select_attribute_for_attribute_assignments_age_group').style.display='inline';
document.getElementById('select_attribute_for_attribute_assignments_age_group').style.visibility='visible';
document.getElementById('select_attribute_for_attribute_assignments_availability').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_availability').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_condition').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_condition').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_excluded_destination').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_excluded_destination').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_gender').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_gender').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_google_product_category').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_google_product_category').style.visibility='hidden';
if(document.getElementById('help_for_attribute_assignments_google_product_category'))
{
document.getElementById('help_for_attribute_assignments_google_product_category').style.display='none';
document.getElementById('help_for_attribute_assignments_google_product_category').style.visibility='hidden';
}
break;
case 'availability':
document.getElementById('help_for_attribute_assignments_adwords_product_ads').style.display='none';
document.getElementById('help_for_attribute_assignments_adwords_product_ads').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_adwords_publish').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_adwords_publish').style.visibility='hidden';
document.getElementById('help_for_attribute_assignments_adwords_queryparam').style.display='none';
document.getElementById('help_for_attribute_assignments_adwords_queryparam').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_age_group').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_age_group').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_availability').selectedIndex=0;
document.getElementById('select_attribute_for_attribute_assignments_availability').style.display='inline';
document.getElementById('select_attribute_for_attribute_assignments_availability').style.visibility='visible';
document.getElementById('select_attribute_for_attribute_assignments_condition').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_condition').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_excluded_destination').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_excluded_destination').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_gender').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_gender').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_google_product_category').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_google_product_category').style.visibility='hidden';
if(document.getElementById('help_for_attribute_assignments_google_product_category'))
{
document.getElementById('help_for_attribute_assignments_google_product_category').style.display='none';
document.getElementById('help_for_attribute_assignments_google_product_category').style.visibility='hidden';
}
break;
case 'condition':
document.getElementById('help_for_attribute_assignments_adwords_product_ads').style.display='none';
document.getElementById('help_for_attribute_assignments_adwords_product_ads').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_adwords_publish').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_adwords_publish').style.visibility='hidden';
document.getElementById('help_for_attribute_assignments_adwords_queryparam').style.display='none';
document.getElementById('help_for_attribute_assignments_adwords_queryparam').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_age_group').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_age_group').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_availability').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_availability').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_condition').selectedIndex=0;
document.getElementById('select_attribute_for_attribute_assignments_condition').style.display='inline';
document.getElementById('select_attribute_for_attribute_assignments_condition').style.visibility='visible';
document.getElementById('select_attribute_for_attribute_assignments_excluded_destination').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_excluded_destination').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_gender').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_gender').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_google_product_category').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_google_product_category').style.visibility='hidden';
if(document.getElementById('help_for_attribute_assignments_google_product_category'))
{
document.getElementById('help_for_attribute_assignments_google_product_category').style.display='none';
document.getElementById('help_for_attribute_assignments_google_product_category').style.visibility='hidden';
}
break;
case 'excluded_destination':
document.getElementById('help_for_attribute_assignments_adwords_product_ads').style.display='none';
document.getElementById('help_for_attribute_assignments_adwords_product_ads').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_adwords_publish').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_adwords_publish').style.visibility='hidden';
document.getElementById('help_for_attribute_assignments_adwords_queryparam').style.display='none';
document.getElementById('help_for_attribute_assignments_adwords_queryparam').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_age_group').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_age_group').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_availability').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_availability').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_condition').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_condition').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_excluded_destination').selectedIndex=0;
document.getElementById('select_attribute_for_attribute_assignments_excluded_destination').style.display='inline';
document.getElementById('select_attribute_for_attribute_assignments_excluded_destination').style.visibility='visible';
document.getElementById('select_attribute_for_attribute_assignments_gender').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_gender').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_google_product_category').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_google_product_category').style.visibility='hidden';
if(document.getElementById('help_for_attribute_assignments_google_product_category'))
{
document.getElementById('help_for_attribute_assignments_google_product_category').style.display='none';
document.getElementById('help_for_attribute_assignments_google_product_category').style.visibility='hidden';
}
break;
case 'gender':
document.getElementById('help_for_attribute_assignments_adwords_product_ads').style.display='none';
document.getElementById('help_for_attribute_assignments_adwords_product_ads').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_adwords_publish').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_adwords_publish').style.visibility='hidden';
document.getElementById('help_for_attribute_assignments_adwords_queryparam').style.display='none';
document.getElementById('help_for_attribute_assignments_adwords_queryparam').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_age_group').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_age_group').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_availability').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_availability').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_condition').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_condition').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_excluded_destination').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_excluded_destination').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_gender').selectedIndex=0;
document.getElementById('select_attribute_for_attribute_assignments_gender').style.display='inline';
document.getElementById('select_attribute_for_attribute_assignments_gender').style.visibility='visible';
document.getElementById('select_attribute_for_attribute_assignments_google_product_category').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_google_product_category').style.visibility='hidden';
if(document.getElementById('help_for_attribute_assignments_google_product_category'))
{
document.getElementById('help_for_attribute_assignments_google_product_category').style.display='none';
document.getElementById('help_for_attribute_assignments_google_product_category').style.visibility='hidden';
}
break;
case 'google_product_category':
document.getElementById('help_for_attribute_assignments_adwords_product_ads').style.display='none';
document.getElementById('help_for_attribute_assignments_adwords_product_ads').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_adwords_publish').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_adwords_publish').style.visibility='hidden';
document.getElementById('help_for_attribute_assignments_adwords_queryparam').style.display='none';
document.getElementById('help_for_attribute_assignments_adwords_queryparam').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_age_group').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_age_group').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_availability').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_availability').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_condition').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_condition').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_excluded_destination').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_excluded_destination').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_gender').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_gender').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_google_product_category').style.display='inline';
document.getElementById('select_attribute_for_attribute_assignments_google_product_category').style.visibility='visible';
if(document.getElementById('help_for_attribute_assignments_google_product_category'))
{
document.getElementById('help_for_attribute_assignments_google_product_category').style.display='inline';
document.getElementById('help_for_attribute_assignments_google_product_category').style.visibility='visible';
}
break;
default:
document.getElementById('help_for_attribute_assignments_adwords_product_ads').style.display='none';
document.getElementById('help_for_attribute_assignments_adwords_product_ads').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_adwords_publish').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_adwords_publish').style.visibility='hidden';
document.getElementById('help_for_attribute_assignments_adwords_queryparam').style.display='none';
document.getElementById('help_for_attribute_assignments_adwords_queryparam').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_age_group').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_age_group').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_availability').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_availability').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_condition').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_condition').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_excluded_destination').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_excluded_destination').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_gender').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_gender').style.visibility='hidden';
document.getElementById('select_attribute_for_attribute_assignments_google_product_category').style.display='none';
document.getElementById('select_attribute_for_attribute_assignments_google_product_category').style.visibility='hidden';
if(document.getElementById('help_for_attribute_assignments_google_product_category'))
{
document.getElementById('help_for_attribute_assignments_google_product_category').style.display='none';
document.getElementById('help_for_attribute_assignments_google_product_category').style.visibility='hidden';
}
break;
}
}
function jg_6bajs(jg_nsqsi)
{
var jg_5umef;
switch (jg_nsqsi)
{
case "cs-CZ":
jg_5umef="cz.png";
break;
case "de":
jg_5umef="de.png";
break;
case "en-AU":
jg_5umef="au.png";
break;
case "en-GB":
jg_5umef="gb.png";
break;
case "en":
jg_5umef="us.png";
break;
case "es":
jg_5umef="es.png";
break;
case "fr":
jg_5umef="fr.png";
break;
case "it":
jg_5umef="it.png";
break;
case "nl":
jg_5umef="nl.png";
break;
case "pt-BR":
jg_5umef="br.png";
break;
case "cn":
jg_5umef="cn.png";
break;
case "jp":
jg_5umef="jp.png";
break;
default:
break;
}
return jg_5umef;
}
function jg_vczua(jg_nsqsi)
{
var jg_5umef;
switch (jg_nsqsi)
{
case "cs-CZ":
jg_5umef=jg_nsqsi;
break;
case "de":
jg_5umef="de-DE";
break;
case "en-AU":
jg_5umef=jg_nsqsi;
break;
case "en-GB":
jg_5umef=jg_nsqsi;
break;
case "en":
jg_5umef="en-US";
break;
case "es":
jg_5umef="es-ES";
break;
case "fr":
jg_5umef="fr-FR";
break;
case "it":
jg_5umef="it-IT";
break;
case "nl":
jg_5umef="nl-NL";
break;
case "pt-BR":
jg_5umef=jg_nsqsi;
break;
case "cn":
jg_5umef="zh-CN";
break;
case "jp":
jg_5umef="ja-JP";
break;
default:
break;
}
return jg_5umef;
}
function jg_9klvcj()
{
if (window.XMLHttpRequest)
{
jg_vt23x=new XMLHttpRequest();
}
else
{
jg_vt23x=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_vt23x.onreadystatechange=function()
{
if (jg_vt23x.readyState==4&&jg_vt23x.status==200)
{
jg_ti2uh(document.getElementById("jg_amo10d"), jg_vt23x.responseText);
}
}
jg_p3sp5();
}
function jg_xlh26a(jg_gchqy)
{
if (window.XMLHttpRequest)
{
jg_vt23x=new XMLHttpRequest();
}
else
{
jg_vt23x=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_vt23x.onreadystatechange=function()
{
if (jg_vt23x.readyState==4&&jg_vt23x.status==200)
{
jg_9klvcj();
}
}
jg_3g8b1=document.getElementById(jg_gchqy.id).value;
jg_3g8b1=jg_waz6b(jg_3g8b1);
jg_ssljh();
}
function jg_uc7943(jg_3nme4,jg_c3pnh,jg_gshng)
{
if(jg_3nme4!=='')
{
jg_ul3vh=jg_3nme4;
}
if (window.XMLHttpRequest)
{
jg_v9jed=new XMLHttpRequest();
}
else
{
jg_v9jed=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_v9jed.onreadystatechange=function()
{
if (jg_v9jed.readyState==4&&jg_v9jed.status==200)
{
if((document.getElementById(jg_ul3vh))&&(jg_gshng==true))
{
document.getElementById(jg_ul3vh).value=jg_il8kg(jg_c3pnh);
}
jg_ul3vh='';
}
}
jg_owema=jg_c3pnh;
jg_owema=jg_waz6b(jg_owema);
jg_q189l();
}
function jg_gxfe25(jg_aste1,jg_gshng)
{
if (window.XMLHttpRequest)
{
jg_wkjc1=new XMLHttpRequest();
}
else
{
jg_wkjc1=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_wkjc1.onreadystatechange=function()
{
if (jg_wkjc1.readyState==4&&jg_wkjc1.status==200)
{
if((document.getElementById("default_availability"))&&(jg_gshng==true))
{
document.getElementById("default_availability").value=jg_il8kg(jg_aste1);
}
}
}
jg_hxr10=jg_aste1;
jg_hxr10=jg_waz6b(jg_hxr10);
jg_cyr2h();
}
function jg_mu7ulh(jg_lgpuq,jg_gshng)
{
if (window.XMLHttpRequest)
{
jg_hmati=new XMLHttpRequest();
}
else
{
jg_hmati=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_hmati.onreadystatechange=function()
{
if (jg_hmati.readyState==4&&jg_hmati.status==200)
{
if((document.getElementById("default_availability_zero"))&&(jg_gshng==true))
{
document.getElementById("default_availability_zero").value=jg_il8kg(jg_lgpuq);
}
}
}
jg_e7rlw=jg_lgpuq;
jg_e7rlw=jg_waz6b(jg_e7rlw);
jg_imxvo();
}
function jg_24v2jq(jg_10yi6,jg_gshng)
{
if (window.XMLHttpRequest)
{
jg_9numc=new XMLHttpRequest();
}
else
{
jg_9numc=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_9numc.onreadystatechange=function()
{
if (jg_9numc.readyState==4&&jg_9numc.status==200)
{
if((document.getElementById("default_color"))&&(jg_gshng==true))
{
document.getElementById("default_color").value=jg_il8kg(jg_10yi6);
}
}
}
jg_skrnf=jg_10yi6;
jg_skrnf=jg_waz6b(jg_skrnf);
jg_bv71q();
}
function jg_gi7m2l(jg_10yi6,jg_gshng)
{
if (window.XMLHttpRequest)
{
jg_kroqw=new XMLHttpRequest();
}
else
{
jg_kroqw=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_kroqw.onreadystatechange=function()
{
if (jg_kroqw.readyState==4&&jg_kroqw.status==200)
{
if((document.getElementById("default_condition"))&&(jg_gshng==true))
{
document.getElementById("default_condition").value=jg_il8kg(jg_10yi6);
}
}
}
jg_7j7wf=jg_10yi6;
jg_7j7wf=jg_waz6b(jg_7j7wf);
jg_sy3fa();
}
function jg_jd8n4e(jg_10yi6,jg_gshng)
{
if (window.XMLHttpRequest)
{
jg_xkzmr=new XMLHttpRequest();
}
else
{
jg_xkzmr=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_xkzmr.onreadystatechange=function()
{
if (jg_xkzmr.readyState==4&&jg_xkzmr.status==200)
{
if((document.getElementById("default_size"))&&(jg_gshng==true))
{
document.getElementById("default_size").value=jg_il8kg(jg_10yi6);
}
}
}
jg_v429f=jg_10yi6;
jg_v429f=jg_waz6b(jg_v429f);
jg_dnkpx();
}
function jg_pzx2jj(jg_10yi6,jg_gshng)
{
if (window.XMLHttpRequest)
{
jg_f7fyg=new XMLHttpRequest();
}
else
{
jg_f7fyg=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_f7fyg.onreadystatechange=function()
{
if (jg_f7fyg.readyState==4&&jg_f7fyg.status==200)
{
if((document.getElementById("default_age_group"))&&(jg_gshng==true))
{
document.getElementById("default_age_group").value=jg_il8kg(jg_10yi6);
}
}
}
jg_7fpw8=jg_10yi6;
jg_7fpw8=jg_waz6b(jg_7fpw8);
jg_irk4t();
}
function jg_ihteb1(jg_10yi6,jg_gshng)
{
if (window.XMLHttpRequest)
{
jg_x59m8=new XMLHttpRequest();
}
else
{
jg_x59m8=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_x59m8.onreadystatechange=function()
{
if (jg_x59m8.readyState==4&&jg_x59m8.status==200)
{
if((document.getElementById("default_gender"))&&(jg_gshng==true))
{
document.getElementById("default_gender").value=jg_il8kg(jg_10yi6);
}
}
}
jg_mldok=jg_10yi6;
jg_mldok=jg_waz6b(jg_mldok);
jg_v956r();
}
function jg_mw31y2(jg_10yi6,jg_gshng)
{
if (window.XMLHttpRequest)
{
jg_tbek9=new XMLHttpRequest();
}
else
{
jg_tbek9=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_tbek9.onreadystatechange=function()
{
if (jg_tbek9.readyState==4&&jg_tbek9.status==200)
{
if((document.getElementById("default_link_suffix"))&&(jg_gshng==true))
{
document.getElementById("default_link_suffix").value=jg_il8kg(jg_10yi6);
}
}
}
jg_10no2=jg_10yi6;
jg_10no2=jg_waz6b(jg_10no2);
jg_bmjyz();
}
function jg_bqm9g4(jg_10yi6,jg_gshng)
{
if (window.XMLHttpRequest)
{
jg_f49dj=new XMLHttpRequest();
}
else
{
jg_f49dj=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_f49dj.onreadystatechange=function()
{
if (jg_f49dj.readyState==4&&jg_f49dj.status==200)
{
if((document.getElementById("default_special_time_of_day"))&&(jg_gshng==true))
{
document.getElementById("default_special_time_of_day").value=jg_il8kg(jg_10yi6);
}
}
}
jg_9rlam=jg_10yi6;
jg_9rlam=jg_waz6b(jg_9rlam);
jg_81c1a();
}
function jg_5ltrfr(jg_10yi6,jg_gshng)
{
if (window.XMLHttpRequest)
{
jg_pvt5p=new XMLHttpRequest();
}
else
{
jg_pvt5p=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_pvt5p.onreadystatechange=function()
{
if (jg_pvt5p.readyState==4&&jg_pvt5p.status==200)
{
if((document.getElementById("default_special_time_zone_offset"))&&(jg_gshng==true))
{
document.getElementById("default_special_time_zone_offset").value=jg_il8kg(jg_10yi6);
}
}
}
jg_aj75c=jg_10yi6;
jg_aj75c=jg_waz6b(jg_aj75c);
jg_z8f4c();
}
function jg_49l10()
{
if (window.XMLHttpRequest)
{
jg_619dn=new XMLHttpRequest();
}
else
{
jg_619dn=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_619dn.onreadystatechange=function()
{
if (jg_619dn.readyState==4&&jg_619dn.status==200)
{
if (jg_619dn.responseText=='true')
{
document.getElementById("jg_zsb1cs").checked=true;
}
if (jg_619dn.responseText=='false')
{
document.getElementById("jg_zsb1cs").checked=false;
}
}
}
jg_wuvg5();
}
function jg_exqq10()
{
if (window.XMLHttpRequest)
{
jg_619dn=new XMLHttpRequest();
}
else
{
jg_619dn=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_619dn.onreadystatechange=function()
{
if (jg_619dn.readyState==4&&jg_619dn.status==200)
{
jg_49l10();
}
}
jg_f1087=document.getElementById("jg_zsb1cs").checked.toString();
jg_ljfyo();
}
function jg_f66oa()
{
if (window.XMLHttpRequest)
{
jg_fitzy=new XMLHttpRequest();
}
else
{
jg_fitzy=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_fitzy.onreadystatechange=function()
{
if (jg_fitzy.readyState==4&&jg_fitzy.status==200)
{
if (jg_fitzy.responseText=='true')
{
document.getElementById("jg_7kdi9c").checked=true;
}
if (jg_fitzy.responseText=='false')
{
document.getElementById("jg_7kdi9c").checked=false;
}
}
}
jg_cm3z6();
}
function jg_2q2bg1()
{
if (window.XMLHttpRequest)
{
jg_fitzy=new XMLHttpRequest();
}
else
{
jg_fitzy=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_fitzy.onreadystatechange=function()
{
if (jg_fitzy.readyState==4&&jg_fitzy.status==200)
{
jg_f66oa();
}
}
jg_2yoyc=document.getElementById("jg_7kdi9c").checked.toString();
jg_4gkg7();
}
function jg_67oyn()
{
if (window.XMLHttpRequest)
{
jg_bdho6=new XMLHttpRequest();
}
else
{
jg_bdho6=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_bdho6.onreadystatechange=function()
{
if (jg_bdho6.readyState==4&&jg_bdho6.status==200)
{
if (jg_bdho6.responseText=='true')
{
document.getElementById("jg_g9g2sf").checked=true;
}
if (jg_bdho6.responseText=='false')
{
document.getElementById("jg_g9g2sf").checked=false;
}
}
}
jg_1bwmd();
}
function jg_lrv1i()
{
if (window.XMLHttpRequest)
{
jg_ryaiz=new XMLHttpRequest();
}
else
{
jg_ryaiz=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_ryaiz.onreadystatechange=function()
{
if (jg_ryaiz.readyState==4&&jg_ryaiz.status==200)
{
if (jg_ryaiz.responseText=='true')
{
document.getElementById("surround_xml_data_feed_attributes_with_cdata_tags").checked=true;
}
if (jg_ryaiz.responseText=='false')
{
document.getElementById("surround_xml_data_feed_attributes_with_cdata_tags").checked=false;
}
}
}
jg_vkdmc();
}
function default_use_weight_for_shipping_weight_load()
{
if (window.XMLHttpRequest)
{
xmlhttp_default_use_weight_for_shipping_weight=new XMLHttpRequest();
}
else
{
xmlhttp_default_use_weight_for_shipping_weight=new ActiveXObject("Microsoft.XMLHTTP");
}
xmlhttp_default_use_weight_for_shipping_weight.onreadystatechange=function()
{
if (xmlhttp_default_use_weight_for_shipping_weight.readyState==4&&xmlhttp_default_use_weight_for_shipping_weight.status==200)
{
if (xmlhttp_default_use_weight_for_shipping_weight.responseText=='true')
{
document.getElementById("use_weight_for_shipping_weight").checked=true;
}
if (xmlhttp_default_use_weight_for_shipping_weight.responseText=='false')
{
document.getElementById("use_weight_for_shipping_weight").checked=false;
}
}
}
send_http_get_default_use_weight_for_shipping_weight_load();
}
function jg_9uxck()
{
if (window.XMLHttpRequest)
{
jg_ljhmf=new XMLHttpRequest();
}
else
{
jg_ljhmf=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_ljhmf.onreadystatechange=function()
{
if (jg_ljhmf.readyState==4&&jg_ljhmf.status==200)
{
if (jg_ljhmf.responseText=='true')
{
document.getElementById("jg_r6qq8d").checked=true;
}
if (jg_ljhmf.responseText=='false')
{
document.getElementById("jg_r6qq8d").checked=false;
}
}
}
jg_yx45v();
}
function jg_qq1f1()
{
if (window.XMLHttpRequest)
{
jg_ywasz=new XMLHttpRequest();
}
else
{
jg_ywasz=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_ywasz.onreadystatechange=function()
{
if (jg_ywasz.readyState==4&&jg_ywasz.status==200)
{
if (jg_ywasz.responseText=='true')
{
document.getElementById("jg_vishj4").checked=true;
}
if (jg_ywasz.responseText=='false')
{
document.getElementById("jg_vishj4").checked=false;
}
}
}
jg_7y8zl();
}
function jg_m6ikn()
{
if (window.XMLHttpRequest)
{
jg_ql443=new XMLHttpRequest();
}
else
{
jg_ql443=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_ql443.onreadystatechange=function()
{
if (jg_ql443.readyState==4&&jg_ql443.status==200)
{
if (jg_ql443.responseText=='true')
{
document.getElementById("checkbox_do_not_merge_custom_attribute_assignments").checked=true;
}
if (jg_ql443.responseText=='false')
{
document.getElementById("checkbox_do_not_merge_custom_attribute_assignments").checked=false;
}
}
}
jg_mf35l();
}
function jg_e64xo()
{
if (window.XMLHttpRequest)
{
jg_xm7k6=new XMLHttpRequest();
}
else
{
jg_xm7k6=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_xm7k6.onreadystatechange=function()
{
if (jg_xm7k6.readyState==4&&jg_xm7k6.status==200)
{
if (jg_xm7k6.responseText=='true')
{
document.getElementById("checkbox_do_not_use_a_cached_image_for_product_image_link").checked=true;
}
if (jg_xm7k6.responseText=='false')
{
document.getElementById("checkbox_do_not_use_a_cached_image_for_product_image_link").checked=false;
}
}
}
jg_nlncy();
}
function jg_kcwdz()
{
if (window.XMLHttpRequest)
{
jg_qgjvy=new XMLHttpRequest();
}
else
{
jg_qgjvy=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_qgjvy.onreadystatechange=function()
{
if (jg_qgjvy.readyState==4&&jg_qgjvy.status==200)
{
if (jg_qgjvy.responseText=='true')
{
document.getElementById("checkbox_do_not_use_model_field_for_mpn").checked=true;
}
if (jg_qgjvy.responseText=='false')
{
document.getElementById("checkbox_do_not_use_model_field_for_mpn").checked=false;
}
}
}
jg_gv10o();
}
function jg_gq7io()
{
if (window.XMLHttpRequest)
{
jg_epgap=new XMLHttpRequest();
}
else
{
jg_epgap=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_epgap.onreadystatechange=function()
{
if (jg_epgap.readyState==4&&jg_epgap.status==200)
{
if (jg_epgap.responseText=='true')
{
document.getElementById("checkbox_do_not_use_upc_field_for_gtin").checked=true;
}
if (jg_epgap.responseText=='false')
{
document.getElementById("checkbox_do_not_use_upc_field_for_gtin").checked=false;
}
}
}
jg_9nqxl();
}
function jg_z710x()
{
if (window.XMLHttpRequest)
{
jg_9if10=new XMLHttpRequest();
}
else
{
jg_9if10=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_9if10.onreadystatechange=function()
{
if (jg_9if10.readyState==4&&jg_9if10.status==200)
{
if (jg_9if10.responseText=='true')
{
document.getElementById("checkbox_do_not_use_product_id_field_for_id").checked=true;
}
if (jg_9if10.responseText=='false')
{
document.getElementById("checkbox_do_not_use_product_id_field_for_id").checked=false;
}
}
}
jg_31vc3();
}
function jg_5l4qd()
{
if (window.XMLHttpRequest)
{
jg_z6fq1=new XMLHttpRequest();
}
else
{
jg_z6fq1=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_z6fq1.onreadystatechange=function()
{
if (jg_z6fq1.readyState==4&&jg_z6fq1.status==200)
{
if (jg_z6fq1.responseText=='true')
{
document.getElementById("checkbox_do_not_use_manufacturer_field_for_brand").checked=true;
}
if (jg_z6fq1.responseText=='false')
{
document.getElementById("checkbox_do_not_use_manufacturer_field_for_brand").checked=false;
}
}
}
jg_61d7d();
}
function jg_18k2p()
{
if (window.XMLHttpRequest)
{
jg_i3v9r=new XMLHttpRequest();
}
else
{
jg_i3v9r=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_i3v9r.onreadystatechange=function()
{
if (jg_i3v9r.readyState==4&&jg_i3v9r.status==200)
{
jg_ti2uh(document.getElementById("select_default_setting_upc"), jg_i3v9r.responseText)
}
}
jg_eyzcb();
}
function jg_7cnyh()
{
if (window.XMLHttpRequest)
{
jg_nx9jr=new XMLHttpRequest();
}
else
{
jg_nx9jr=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_nx9jr.onreadystatechange=function()
{
if (jg_nx9jr.readyState==4&&jg_nx9jr.status==200)
{
if (jg_nx9jr.responseText=='true')
{
document.getElementById("password_protect_the_data_feed").checked=true;
}
if (jg_nx9jr.responseText=='false')
{
document.getElementById("password_protect_the_data_feed").checked=false;
}
}
}
jg_1079t();
}
function jg_28e7ph()
{
if (document.getElementById("jg_g9g2sf").checked==false)
{
document.getElementById("jg_r6qq8d").checked=false;
document.getElementById("jg_r6qq8d").disabled=true;
jg_osbtr2();
}
else
{
document.getElementById("jg_r6qq8d").disabled=false;
}
if (window.XMLHttpRequest)
{
jg_bdho6=new XMLHttpRequest();
}
else
{
jg_bdho6=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_bdho6.onreadystatechange=function()
{
if (jg_bdho6.readyState==4&&jg_bdho6.status==200)
{
jg_67oyn();
}
}
jg_azn91=document.getElementById("jg_g9g2sf").checked.toString();
jg_6s1hg();
}
function default_surround_xml_data_feed_attributes_with_cdata_tags_save()
{
if (window.XMLHttpRequest)
{
jg_ryaiz=new XMLHttpRequest();
}
else
{
jg_ryaiz=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_ryaiz.onreadystatechange=function()
{
if (jg_ryaiz.readyState==4&&jg_ryaiz.status==200)
{
jg_lrv1i();
}
}
jg_2vmnr=document.getElementById("surround_xml_data_feed_attributes_with_cdata_tags").checked.toString();
jg_d5yy1();
}
function default_use_weight_for_shipping_weight_save()
{
if (window.XMLHttpRequest)
{
xmlhttp_default_use_weight_for_shipping_weight=new XMLHttpRequest();
}
else
{
xmlhttp_default_use_weight_for_shipping_weight=new ActiveXObject("Microsoft.XMLHTTP");
}
xmlhttp_default_use_weight_for_shipping_weight.onreadystatechange=function()
{
if (xmlhttp_default_use_weight_for_shipping_weight.readyState==4&&xmlhttp_default_use_weight_for_shipping_weight.status==200)
{
default_use_weight_for_shipping_weight_load();
}
}
default_use_weight_for_shipping_weight=document.getElementById("use_weight_for_shipping_weight").checked.toString();
send_http_get_default_use_weight_for_shipping_weight_save();
}
function jg_osbtr2()
{
if (window.XMLHttpRequest)
{
jg_ljhmf=new XMLHttpRequest();
}
else
{
jg_ljhmf=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_ljhmf.onreadystatechange=function()
{
if (jg_ljhmf.readyState==4&&jg_ljhmf.status==200)
{
jg_9uxck();
}
}
jg_zfvy5=document.getElementById("jg_r6qq8d").checked.toString();
jg_4u2r5();
}
function jg_7dy4hk()
{
if (window.XMLHttpRequest)
{
jg_ywasz=new XMLHttpRequest();
}
else
{
jg_ywasz=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_ywasz.onreadystatechange=function()
{
if (jg_ywasz.readyState==4&&jg_ywasz.status==200)
{
jg_qq1f1();
}
}
jg_v109z=document.getElementById("jg_vishj4").checked.toString();
jg_jolfa();
}
function jg_vx6cqj()
{
if (window.XMLHttpRequest)
{
jg_ql443=new XMLHttpRequest();
}
else
{
jg_ql443=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_ql443.onreadystatechange=function()
{
if (jg_ql443.readyState==4&&jg_ql443.status==200)
{
jg_m6ikn();
}
}
jg_fwn10=document.getElementById("checkbox_do_not_merge_custom_attribute_assignments").checked.toString();
jg_1dciu();
}
function jg_d6a10t()
{
if (window.XMLHttpRequest)
{
jg_xm7k6=new XMLHttpRequest();
}
else
{
jg_xm7k6=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_xm7k6.onreadystatechange=function()
{
if (jg_xm7k6.readyState==4&&jg_xm7k6.status==200)
{
jg_e64xo();
}
}
jg_rwa34=document.getElementById("checkbox_do_not_use_a_cached_image_for_product_image_link").checked.toString();
jg_acjap();
}
function jg_vz3pk1()
{
if (window.XMLHttpRequest)
{
jg_qgjvy=new XMLHttpRequest();
}
else
{
jg_qgjvy=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_qgjvy.onreadystatechange=function()
{
if (jg_qgjvy.readyState==4&&jg_qgjvy.status==200)
{
jg_kcwdz();
}
}
jg_x2v3m=document.getElementById("checkbox_do_not_use_model_field_for_mpn").checked.toString();
jg_8kv9w();
}
function jg_n3ii8r()
{
if (window.XMLHttpRequest)
{
jg_epgap=new XMLHttpRequest();
}
else
{
jg_epgap=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_epgap.onreadystatechange=function()
{
if (jg_epgap.readyState==4&&jg_epgap.status==200)
{
jg_gq7io();
}
}
jg_e2t6u=document.getElementById("checkbox_do_not_use_upc_field_for_gtin").checked.toString();
jg_xizz6();
}
function jg_p6zwiz()
{
if (window.XMLHttpRequest)
{
jg_9if10=new XMLHttpRequest();
}
else
{
jg_9if10=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_9if10.onreadystatechange=function()
{
if (jg_9if10.readyState==4&&jg_9if10.status==200)
{
jg_z710x();
}
}
jg_g17yw=document.getElementById("checkbox_do_not_use_product_id_field_for_id").checked.toString();
jg_uwb48();
}
function jg_zm5z1j()
{
if (window.XMLHttpRequest)
{
jg_z6fq1=new XMLHttpRequest();
}
else
{
jg_z6fq1=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_z6fq1.onreadystatechange=function()
{
if (jg_z6fq1.readyState==4&&jg_z6fq1.status==200)
{
jg_5l4qd();
}
}
jg_eso3h=document.getElementById("checkbox_do_not_use_manufacturer_field_for_brand").checked.toString();
jg_chz8k();
}
function jg_2edelo(this_length)
{
if (window.XMLHttpRequest)
{
jg_i3v9r=new XMLHttpRequest();
}
else
{
jg_i3v9r=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_i3v9r.onreadystatechange=function()
{
if (jg_i3v9r.readyState==4&&jg_i3v9r.status==200)
{
if(jg_i3v9r.responseText.length>0)
{
alert(jg_i3v9r.responseText);
}
jg_18k2p();
}
}
default_lengthen_upc_field=this_length;
jg_9y82s();
}
function jg_hf9ijm()
{
if (window.XMLHttpRequest)
{
jg_nx9jr=new XMLHttpRequest();
}
else
{
jg_nx9jr=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_nx9jr.onreadystatechange=function()
{
if (jg_nx9jr.readyState==4&&jg_nx9jr.status==200)
{
jg_7cnyh();
}
}
jg_mvc6f=document.getElementById("password_protect_the_data_feed").checked.toString();
jg_8yytr();
}
function jg_rfctm3()
{
if (window.XMLHttpRequest)
{
jg_yhlsk=new XMLHttpRequest();
}
else
{
jg_yhlsk=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_yhlsk.onreadystatechange=function()
{
if (jg_yhlsk.readyState==4&&jg_yhlsk.status==200)
{
}
}
jg_ewx35();
}
function jg_1ax3r8()
{
if (window.XMLHttpRequest)
{
jg_flqhr=new XMLHttpRequest();
}
else
{
jg_flqhr=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_flqhr.onreadystatechange=function()
{
if (jg_flqhr.readyState==4&&jg_flqhr.status==200)
{
}
}
jg_x6gp6();
}
function jg_e8f7qz(jg_gchqy)
{
var jg_g8ktj=jg_gchqy.options[jg_gchqy.selectedIndex].value;
if (jg_g8ktj=="<?php echo $text_product_name ?>")
{
document.getElementById("jg_xcb1s8").style.display="none";
document.getElementById("jg_xcb1s8").style.visibility="hidden";
document.getElementById("jg_eqr6c2").style.display="none";
document.getElementById("jg_eqr6c2").style.visibility="hidden";
document.getElementById("jg_inuxy7").style.display="block";
document.getElementById("jg_inuxy7").style.visibility="visible";
document.getElementById("jg_wu109i").style.display="none";
document.getElementById("jg_wu109i").style.visibility="hidden";
jg_xylwf1("1","<?php echo PRODUCT_NAMES_START ?>","<?php echo PRODUCT_NAMES_LIMIT ?>","<?php echo $text_default ?>");
}
if (jg_g8ktj=="<?php echo $text_product_attribute ?>")
{
document.getElementById("jg_xcb1s8").style.display="none";
document.getElementById("jg_xcb1s8").style.visibility="hidden";
document.getElementById("jg_eqr6c2").style.display="block";
document.getElementById("jg_eqr6c2").style.visibility="visible";
document.getElementById("jg_inuxy7").style.display="none";
document.getElementById("jg_inuxy7").style.visibility="hidden";
document.getElementById("jg_wu109i").style.display="none";
document.getElementById("jg_wu109i").style.visibility="hidden";
jg_r2bm16("1","<?php echo PRODUCT_ATTRIBUTES_START ?>","<?php echo PRODUCT_ATTRIBUTES_LIMIT ?>","<?php echo $text_default ?>");
}
if (jg_g8ktj=="<?php echo $text_product_category ?>")
{
document.getElementById("jg_xcb1s8").style.display="block";
document.getElementById("jg_xcb1s8").style.visibility="visible";
document.getElementById("jg_eqr6c2").style.display="none";
document.getElementById("jg_eqr6c2").style.visibility="hidden";
document.getElementById("jg_inuxy7").style.display="none";
document.getElementById("jg_inuxy7").style.visibility="hidden";
document.getElementById("jg_wu109i").style.display="none";
document.getElementById("jg_wu109i").style.visibility="hidden";
jg_h10gc8("1","<?php echo PRODUCT_CATEGORIES_START ?>","<?php echo PRODUCT_CATEGORIES_LIMIT ?>","<?php echo $text_default ?>");
}
if (jg_g8ktj=="<?php echo $text_product_option ?>")
{
document.getElementById("jg_xcb1s8").style.display="none";
document.getElementById("jg_xcb1s8").style.visibility="hidden";
document.getElementById("jg_eqr6c2").style.display="none";
document.getElementById("jg_eqr6c2").style.visibility="hidden";
document.getElementById("jg_inuxy7").style.display="none";
document.getElementById("jg_inuxy7").style.visibility="hidden";
document.getElementById("jg_wu109i").style.display="block";
document.getElementById("jg_wu109i").style.visibility="visible";
jg_3razlp("1","<?php echo PRODUCT_OPTIONS_START ?>","<?php echo PRODUCT_OPTIONS_LIMIT ?>","<?php echo VERSION ?>","<?php echo $text_default ?>");
}
}
function jg_z1gdr1(jg_t2xvj,jg_10yi6)
{
if (window.XMLHttpRequest)
{
jg_mqf3k=new XMLHttpRequest();
}
else
{
jg_mqf3k=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_mqf3k.onreadystatechange=function()
{
if (jg_mqf3k.readyState==4&&jg_mqf3k.status==200)
{
if(jg_mqf3k.responseText.length>0)
{
alert(jg_mqf3k.responseText);
}
}
}
jg_95pp4(jg_10yi6,jg_t2xvj);
}
function jg_eb9z8l()
{
jg_58d92(document.getElementById("opencart_product_for_editing"));
window.open(jg_ak4ya, '_blank');
}
function jg_ezoexo(jg_gchqy)
{
var jg_h2qaa=jg_gchqy.options[jg_gchqy.selectedIndex].id;
var jg_fn9u1=jg_gchqy.options[jg_gchqy.selectedIndex].value;
if (window.XMLHttpRequest)
{
jg_izaop=new XMLHttpRequest();
}
else
{
jg_izaop=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_izaop.onreadystatechange=function()
{
if (jg_izaop.readyState==4&&jg_izaop.status==200)
{
document.getElementById("opencart_products_to_edit").innerHTML=jg_izaop.responseText;
jg_58d92(jg_gchqy);
}
}
jg_4dq22=jg_h2qaa;
jg_4dq22=jg_waz6b(jg_4dq22);
jg_lyuji();
}
function jg_58d92(jg_gchqy)
{
var jg_h2qaa=jg_gchqy.options[jg_gchqy.selectedIndex].id;
var jg_fn9u1=jg_gchqy.options[jg_gchqy.selectedIndex].value;
if (window.XMLHttpRequest)
{
jg_9izbz=new XMLHttpRequest();
}
else
{
jg_9izbz=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_9izbz.onreadystatechange=function()
{
if (jg_9izbz.readyState==4&&jg_9izbz.status==200)
{
jg_ak4ya=jg_9izbz.responseText;
}
}
jg_43mbb();
}
function jg_psfj8()
{
jg_8w449.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=jg_dvhcd2&jg_qb8vjr="+jg_e3uzl,true);
jg_8w449.send();
}
function jg_ssljh()
{
jg_vt23x.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=jg_2s97d4&jg_4n37iv="+jg_3g8b1,true);
jg_vt23x.send();
}
function jg_np10u()
{
jg_7tl22.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=jg_5goxc3",true);
jg_7tl22.send();
}
function jg_510u1()
{
jg_q8k7x.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",false);
jg_q8k7x.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_q8k7x.send("jg_bfrx10=load_custom_product_fields_list");
}
function jg_8l3fu()
{
jg_taml6.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=jg_8rpt6y&jg_y96zcl="+"<?php echo VERSION ?>",true);
jg_taml6.send();
}
function jg_lwzm9()
{
jg_luh8w.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",true);
jg_luh8w.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_luh8w.send("jg_bfrx10=load_google_categories_hovered");
}
function jg_51d4l(jg_rm6dc)
{
jg_ibgvk.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",true);
jg_ibgvk.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_ibgvk.send("jg_bfrx10=load_field_length_hovered&jg_byh10s="+jg_rm6dc);
}
function jg_xgkm4()
{
jg_dbvwn.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",true);
jg_dbvwn.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_dbvwn.send("jg_bfrx10=jg_9ywkh9&jg_y96zcl=<?php echo VERSION ?>");
}
function jg_fgukg()
{
jg_f1d3x.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",true);
jg_f1d3x.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_f1d3x.send("jg_bfrx10=load_custom_product_fields_section&jg_y96zcl=<?php echo VERSION ?>");
}
function jg_9lzuc()
{
jg_a8qro.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=jg_2kecjb&jg_y96zcl="+"<?php echo VERSION ?>",true);
jg_a8qro.send();
}
function jg_p3sp5()
{
jg_vt23x.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=jg_ok8pon",true);
jg_vt23x.send();
}
function jg_pav23()
{
jg_8w449.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=jg_fx8d9o",false);
jg_8w449.send();
}
function jg_kjvb1()
{
jg_v9jed.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",true);
jg_v9jed.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_v9jed.send("jg_bfrx10=jg_l10w25");
}
function jg_q189l()
{
jg_v9jed.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",true);
jg_v9jed.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_v9jed.send("jg_bfrx10=save_"+jg_ul3vh+"&jg_dw98hq="+jg_owema+"&id_of_element_to_populate="+jg_ul3vh);
}
function jg_cyr2h()
{
jg_wkjc1.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",true);
jg_wkjc1.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_wkjc1.send("jg_bfrx10=jg_10zfhu&jg_zh1fn5="+jg_hxr10);
}
function jg_imxvo()
{
jg_hmati.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",true);
jg_hmati.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_hmati.send("jg_bfrx10=jg_bwl1bw&jg_5u651g="+jg_e7rlw);
}
function jg_bv71q()
{
jg_9numc.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",true);
jg_9numc.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_9numc.send("jg_bfrx10=jg_gqoqsw&jg_6vb9a6="+jg_skrnf);
}
function jg_sy3fa()
{
jg_kroqw.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",true);
jg_kroqw.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_kroqw.send("jg_bfrx10=save_default_condition&default_condition_to_save="+jg_7j7wf);
}
function jg_dnkpx()
{
jg_xkzmr.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",true);
jg_xkzmr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_xkzmr.send("jg_bfrx10=jg_7s8hrh&jg_oj1gr1="+jg_v429f);
}
function jg_irk4t()
{
jg_f7fyg.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",true);
jg_f7fyg.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_f7fyg.send("jg_bfrx10=jg_nxxcp6&jg_5w10aq="+jg_7fpw8);
}
function jg_v956r()
{
jg_x59m8.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",true);
jg_x59m8.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_x59m8.send("jg_bfrx10=jg_9dxpfv&jg_dlne43="+jg_mldok);
}
function jg_bmjyz()
{
jg_tbek9.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",true);
jg_tbek9.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_tbek9.send("jg_bfrx10=jg_8zf8w9&jg_gyvbjh="+jg_10no2);
}
function jg_81c1a()
{
jg_f49dj.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",true);
jg_f49dj.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_f49dj.send("jg_bfrx10=save_default_special_time_of_day&time_of_day="+jg_9rlam);
}
function jg_z8f4c()
{
jg_pvt5p.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",true);
jg_pvt5p.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_pvt5p.send("jg_bfrx10=save_default_special_time_zone_offset&offset="+jg_aj75c);
}
function jg_lyuji()
{
jg_izaop.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",true);
jg_izaop.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_izaop.send("jg_bfrx10=save_select_product_for_editing&product_for_editing_to_save="+jg_4dq22);
}
function jg_43mbb()
{
jg_9izbz.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",true);
jg_9izbz.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_9izbz.send("jg_bfrx10=send_update_view_product_link&product_id="+jg_4dq22+"&language_code="+document.getElementById("language-product-names").options[document.getElementById("language-product-names").selectedIndex].value);
}
function jg_ljfyo()
{
jg_619dn.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=jg_10ssx7&jg_sobg57="+jg_f1087,true);
jg_619dn.send();
}
function jg_4gkg7()
{
jg_fitzy.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=jg_1dewra&use_sef_urls="+jg_2yoyc,true);
jg_fitzy.send();
}
function jg_6s1hg()
{
jg_bdho6.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=jg_47mkra&jg_1vxoq2="+jg_azn91,true);
jg_bdho6.send();
}
function jg_d5yy1()
{
jg_ryaiz.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=save_default_surround_xml_data_feed_attributes_with_cdata_tags&use_cdata_tags="+jg_2vmnr,true);
jg_ryaiz.send();
}
function send_http_get_default_use_weight_for_shipping_weight_save()
{
xmlhttp_default_use_weight_for_shipping_weight.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=save_default_use_weight_for_shipping_weight&use_weight="+default_use_weight_for_shipping_weight,true);
xmlhttp_default_use_weight_for_shipping_weight.send();
}
function jg_4u2r5()
{
jg_ljhmf.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=jg_6lytnb&jg_3rvsef="+jg_zfvy5,true);
jg_ljhmf.send();
}
function jg_jolfa()
{
jg_ywasz.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=jg_710ksu&jg_cdd5bq="+jg_v109z,true);
jg_ywasz.send();
}
function jg_8yytr()
{
jg_nx9jr.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",true);
jg_nx9jr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_nx9jr.send("jg_bfrx10=jg_xs2gcu&jg_rmf9jl="+jg_mvc6f);
}
function jg_ewx35()
{
jg_yhlsk.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",true);
jg_yhlsk.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_yhlsk.send("jg_bfrx10=jg_rfctm3&jg_wj2fzj="+document.getElementById("jg_wj2fzj").value);
}
function jg_x6gp6()
{
jg_flqhr.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",true);
jg_flqhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_flqhr.send("jg_bfrx10=jg_1ax3r8&jg_rfcpqv="+document.getElementById("jg_rfcpqv").value);
}
function jg_1dciu()
{
jg_ql443.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=jg_lnnwqp&jg_oxw34h="+jg_fwn10,true);
jg_ql443.send();
}
function jg_acjap()
{
jg_xm7k6.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",true);
jg_xm7k6.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_xm7k6.send("jg_bfrx10=save_default_do_not_use_a_cached_image_for_product_image_link&do_not_use="+jg_rwa34);
}
function jg_8kv9w()
{
jg_qgjvy.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",true);
jg_qgjvy.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_qgjvy.send("jg_bfrx10=save_default_do_not_use_model_field_for_mpn&do_not_use="+jg_x2v3m);
}
function jg_xizz6()
{
jg_epgap.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",true);
jg_epgap.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_epgap.send("jg_bfrx10=save_default_do_not_use_upc_field_for_gtin&do_not_use="+jg_e2t6u);
}
function jg_uwb48()
{
jg_9if10.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",true);
jg_9if10.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_9if10.send("jg_bfrx10=save_default_do_not_use_product_id_field_for_id&do_not_use="+jg_g17yw);
}
function jg_chz8k()
{
jg_z6fq1.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",true);
jg_z6fq1.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_z6fq1.send("jg_bfrx10=save_default_do_not_use_manufacturer_field_for_brand&do_not_use="+jg_eso3h);
}
function jg_9y82s()
{
jg_i3v9r.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",true);
jg_i3v9r.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_i3v9r.send("jg_bfrx10=save_default_lengthen_upc_field&newsize="+default_lengthen_upc_field);
}
function jg_95pp4(this_length,jg_t2xvj)
{
jg_mqf3k.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",true);
jg_mqf3k.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_mqf3k.send("jg_bfrx10=save_this_field_length&columnname="+jg_t2xvj+"&newsize="+this_length);
}
function jg_84b10()
{
jg_dgqt9.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=jg_x2d4q4",true);
jg_dgqt9.send();
}
function jg_wuvg5()
{
jg_619dn.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=jg_76lewf",true);
jg_619dn.send();
}
function jg_7y8zl()
{
jg_ywasz.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=load_default_correct_lone_ampersands_in_product_names_and_descriptions",true);
jg_ywasz.send();
}
function jg_1079t()
{
jg_nx9jr.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",true);
jg_nx9jr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_nx9jr.send("jg_bfrx10=jg_7dvyi6");
}
function jg_gv10o()
{
jg_qgjvy.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",true);
jg_qgjvy.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_qgjvy.send("jg_bfrx10=load_default_do_not_use_model_field_for_mpn");
}
function jg_9nqxl()
{
jg_epgap.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",true);
jg_epgap.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_epgap.send("jg_bfrx10=load_default_do_not_use_upc_field_for_gtin");
}
function jg_31vc3()
{
jg_9if10.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",true);
jg_9if10.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_9if10.send("jg_bfrx10=load_default_do_not_use_product_id_field_for_id");
}
function jg_61d7d()
{
jg_z6fq1.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",true);
jg_z6fq1.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_z6fq1.send("jg_bfrx10=load_default_do_not_use_manufacturer_field_for_brand");
}
function jg_eyzcb()
{
jg_i3v9r.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",true);
jg_i3v9r.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_i3v9r.send("jg_bfrx10=load_default_lengthen_upc_field");
}
function jg_mf35l()
{
jg_ql443.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=do_not_merge_custom_attribute_assignments&jg_oxw34h="+document.getElementById("checkbox_do_not_merge_custom_attribute_assignments").checked,true);
jg_ql443.send();
}
function jg_cm3z6()
{
jg_fitzy.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=jg_6ib95k",true);
jg_fitzy.send();
}
function jg_1bwmd()
{
jg_bdho6.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=jg_2126tm",true);
jg_bdho6.send();
}
function jg_vkdmc()
{
jg_ryaiz.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=load_default_surround_xml_data_feed_attributes_with_cdata_tags",true);
jg_ryaiz.send();
}
function send_http_get_default_use_weight_for_shipping_weight_load()
{
xmlhttp_default_use_weight_for_shipping_weight.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=load_default_use_weight_for_shipping_weight",true);
xmlhttp_default_use_weight_for_shipping_weight.send();
}
function jg_yx45v()
{
jg_ljhmf.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=jg_nlzpsr",true);
jg_ljhmf.send();
}
function jg_jw4dx()
{
var jg_jgx2y=document.getElementById("custom_product_field_field_title").value;
var jg_t2xvj=document.getElementById("custom_product_field_column_name").value;
var jg_q2pjb=document.getElementById("custom_product_field_data_type").options[document.getElementById("custom_product_field_data_type").selectedIndex].value;
var jg_nm7fv=document.getElementById("custom_product_field_data_length").value;
var jg_6sqq1=document.getElementById("custom_product_field_attribute_name").value;
var jg_yeaxu=document.getElementById("custom_product_field_attribute_prefix").value;
jg_jgx2y=jg_waz6b(jg_jgx2y);
jg_jgx2y=jg_ujem4(jg_jgx2y);
jg_t2xvj=jg_waz6b(jg_t2xvj);
jg_t2xvj=jg_ujem4(jg_t2xvj);
jg_q2pjb=jg_waz6b(jg_q2pjb);
jg_q2pjb=jg_ujem4(jg_q2pjb);
jg_nm7fv=jg_waz6b(jg_nm7fv);
jg_nm7fv=jg_ujem4(jg_nm7fv);
jg_6sqq1=jg_waz6b(jg_6sqq1);
jg_6sqq1=jg_ujem4(jg_6sqq1);
jg_yeaxu=jg_waz6b(jg_yeaxu);
jg_yeaxu=jg_ujem4(jg_yeaxu);
jg_xf4dn=jg_jgx2y;
jg_j7rzx=jg_t2xvj;
jg_4h4ql=jg_6sqq1;
jg_yinz1=jg_yeaxu;
jg_o410n.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",true);
jg_o410n.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_o410n.send("jg_bfrx10=add_custom_product_field&field_title_sent="+jg_jgx2y+"&jg_byh10s="+jg_t2xvj+"&data_type_sent="+jg_q2pjb+"&data_length_sent="+jg_nm7fv+"&attribute_name_sent="+jg_6sqq1+"&attribute_prefix_sent="+jg_yeaxu);
}
function jg_4mfu9(this_index)
{
var jg_ogq10=document.getElementById("opencart_fields").options[document.getElementById("opencart_fields").selectedIndex].text;
jg_ogq10=jg_waz6b(jg_ogq10);
jg_ogq10=jg_ujem4(jg_ogq10);
jg_baki5=jg_ogq10;
var jg_u8x5d="";
if (jg_ogq10=="<?php echo $text_product_name ?>")
{
jg_u8x5d=document.getElementById("opencart_product").options[this_index].text;
}
if (jg_ogq10=="<?php echo $text_product_attribute ?>")
{
jg_u8x5d=document.getElementById("jg_l2i9ve").options[this_index].text;
}
if (jg_ogq10=="<?php echo $text_product_category ?>")
{
jg_u8x5d=document.getElementById("jg_pydq7r").options[this_index].text;
}
if (jg_ogq10=="<?php echo $text_product_option ?>")
{
jg_u8x5d=document.getElementById("opencart_product_option").options[this_index].text;
}
jg_u8x5d=jg_waz6b(jg_u8x5d);
jg_u8x5d=jg_ujem4(jg_u8x5d);
jg_7ezr5=jg_u8x5d;
jg_plqo5=document.getElementById("custom_google_attribute").value;
jg_plqo5=jg_waz6b(jg_plqo5);
jg_plqo5=jg_ujem4(jg_plqo5);
jg_y10ru=document.getElementById("custom_google_attribute_value").value;
jg_y10ru=jg_waz6b(jg_y10ru);
jg_y10ru=jg_ujem4(jg_y10ru);
jg_101zz=jg_plqo5;
jg_8oghk.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=jg_1tvtzo&jg_1ji4rm="+jg_ogq10+"&jg_zslnd1="+jg_u8x5d+"&jg_lbv6og="+jg_plqo5+"&jg_9aws9q="+jg_y10ru,false);
jg_8oghk.send();
return jg_8oghk.responseText;
}
function jg_e9msc(jg_n3ien, jg_vpriz, jg_jykal, jg_9jmfo, jg_bg5cp, jg_4gkzs, jg_wxc5j, jg_vf81i)
{
jg_n3ien=jg_waz6b(jg_n3ien);
jg_vpriz=jg_waz6b(jg_vpriz);
jg_jykal=jg_waz6b(jg_jykal);
jg_9jmfo=jg_waz6b(jg_9jmfo);
jg_bg5cp=jg_waz6b(jg_bg5cp);
jg_4gkzs=jg_waz6b(jg_4gkzs);
jg_wxc5j=jg_waz6b(jg_wxc5j);
jg_vf81i=jg_waz6b(jg_vf81i);
jg_5c2f2.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=jg_p4fpcw&jg_gb2dfx="+jg_n3ien+"&jg_52tlxt="+jg_vpriz+"&jg_c1f2zq="+jg_jykal+"&jg_2u3dzr="+jg_9jmfo+"&jg_jf3vhy="+jg_bg5cp+"&jg_fnkjkh="+jg_4gkzs+"&jg_wy649z="+jg_wxc5j+"&jg_9zx7ef="+jg_vf81i,false);
jg_5c2f2.send();
return jg_5c2f2.responseText;
}
function jg_kz510(jg_fqks4,jg_4gzth,jg_hc9gu,jg_a10jb,jg_vipxp,jg_pcdwx,jg_hhbz1,jg_d11ua)
{
jg_fqks4=jg_waz6b(jg_fqks4);
jg_4gzth=jg_waz6b(jg_4gzth);
jg_hc9gu=jg_waz6b(jg_hc9gu);
jg_a10jb=jg_waz6b(jg_a10jb);
jg_vipxp=jg_waz6b(jg_vipxp);
jg_pcdwx=jg_waz6b(jg_pcdwx);
jg_hhbz1=jg_waz6b(jg_hhbz1);
jg_d11ua=jg_waz6b(jg_d11ua);
jg_v1vn6.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",false);
jg_v1vn6.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_v1vn6.send("jg_bfrx10=save_setting_custom_product_field&field_title_original="+jg_fqks4+"&column_name_original="+jg_4gzth+"&attribute_name_original="+jg_hc9gu+"&attribute_prefix_original="+jg_a10jb+"&field_title_new="+jg_vipxp+"&column_name_new="+jg_pcdwx+"&attribute_name_new="+jg_hhbz1+"&attribute_prefix_new="+jg_d11ua);
}
function jg_tturk(jg_jgx2y,jg_t2xvj,jg_6sqq1,jg_yeaxu)
{
jg_jgx2y=jg_waz6b(jg_jgx2y);
jg_t2xvj=jg_waz6b(jg_t2xvj);
jg_6sqq1=jg_waz6b(jg_6sqq1);
jg_yeaxu=jg_waz6b(jg_yeaxu);
jg_fayib.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=remove_custom_product_field_sent&field_title_sent="+jg_jgx2y+"&jg_byh10s="+jg_t2xvj+"&attribute_name_sent="+jg_6sqq1+"&attribute_prefix_sent="+jg_yeaxu,false);
jg_fayib.send();
return jg_fayib.responseText;
}
function jg_xx4sg(jg_ogq10,jg_u8x5d,jg_plqo5,jg_y10ru)
{
jg_ogq10=jg_waz6b(jg_ogq10);
jg_u8x5d=jg_waz6b(jg_u8x5d);
jg_plqo5=jg_waz6b(jg_plqo5);
jg_y10ru=jg_waz6b(jg_y10ru);
jg_8oghk.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=jg_ku10s9&jg_1ji4rm="+jg_ogq10+"&jg_zslnd1="+jg_u8x5d+"&jg_lbv6og="+jg_plqo5+"&jg_9aws9q="+jg_y10ru,false);
jg_8oghk.send();
return jg_8oghk.responseText;
}
function jg_8ugmi(jg_nno10,jg_t104r,jg_df6wg,jg_z9huo)
{
jg_d7xog.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=jg_318m1z&jg_zaom91="+jg_nno10+"&jg_fa93ip="+jg_t104r+"&jg_a61gyq="+jg_df6wg+"&jg_kpskdu="+jg_z9huo,false);
jg_d7xog.send();
}
function jg_10c5x(jg_a68cc)
{
jg_d7xog.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=jg_ot2a8w&jg_k51rvb="+jg_a68cc,false);
jg_d7xog.send();
}
function jg_b6ayf(jg_a68cc)
{
jg_d7xog.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=jg_iw106n&jg_k51rvb="+jg_a68cc,false);
jg_d7xog.send();
}
function jg_36dm4(jg_a68cc)
{
jg_f1076.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=jg_np5cza&jg_k51rvb="+jg_a68cc,false);
jg_f1076.send();
}
function jg_olq10(jg_a68cc)
{
jg_f1076.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=jg_igcbkg&jg_k51rvb="+jg_a68cc,false);
jg_f1076.send();
}
function jg_ugz10(jg_dm9rp)
{
jg_dm9rp=jg_dm9rp.replace(/!!$!!$/g, "&");
jg_dm9rp=jg_dm9rp.replace(/!!!$!!!$/g, "+");
jg_dm9rp=jg_dm9rp.replace(/!!!!$!!!!$/g, "<");
jg_dm9rp=jg_dm9rp.replace(/!!!!!$!!!!!$/g, ">");
jg_dm9rp=jg_dm9rp.replace(/!!!!!!$!!!!!!$/g, "/");
return jg_dm9rp;
}
function jg_waz6b(jg_dm9rp)
{
jg_dm9rp=jg_dm9rp.replace(/&amp;/g, "!!$!!$");
jg_dm9rp=jg_dm9rp.replace(/&/g, "!!$!!$");
jg_dm9rp=jg_dm9rp.replace(/\+/g, "!!!$!!!$");
jg_dm9rp=jg_dm9rp.replace(/\</g, "!!!!$!!!!$");
jg_dm9rp=jg_dm9rp.replace(/\>/g, "!!!!!$!!!!!$");
jg_dm9rp=jg_dm9rp.replace(/\//g, "!!!!!!$!!!!!!$");
return jg_dm9rp;
}
function jg_il8kg(jg_dm9rp)
{
jg_dm9rp=jg_dm9rp.replace(/ &amp; /g, " & ");
jg_dm9rp=jg_dm9rp.replace(/&gt;/g, ">");
jg_dm9rp=jg_dm9rp.replace(/&lt;/g, "<");
jg_dm9rp=jg_dm9rp.replace(/&nbsp;/g, " ");
return jg_dm9rp;
}
function jg_3bxo6(jg_dm9rp)
{
jg_dm9rp=jg_dm9rp.replace(/ & /g, " &amp; ");
jg_dm9rp=jg_dm9rp.replace(/&amp;amp;/g, "&amp;");
jg_dm9rp=jg_dm9rp.replace(/&amp;gt;/g, "&gt;");
jg_dm9rp=jg_dm9rp.replace(/&amp;lt;/g, "&lt;");
jg_dm9rp=jg_dm9rp.replace(/\>/g, "&gt;");
jg_dm9rp=jg_dm9rp.replace(/\</g, "&lt;");
jg_dm9rp=jg_dm9rp.replace(/ /g, "&nbsp;");
return jg_dm9rp;
}
function jg_rwecj9()
{
jg_y57l6("down");
jg_asx3r();
}
function jg_bdchh8()
{
jg_y57l6("up");
jg_asx3r();
}
function jg_lw6rlk(jg_gchqy, jg_uhe68)
{
jg_lep10(jg_gchqy);
jg_urxar7(jg_gchqy.parentNode);
jg_tz9bf(jg_gchqy.parentNode);
jg_ogq10="";
jg_u8x5d="";
jg_plqo5="";
jg_y10ru="";
for (jg_4tzyv=1; jg_4tzyv<=4; jg_4tzyv++)
{
if (jg_4tzyv==1)
{
jg_ogq10=jg_waz6b(jg_il8kg(jg_yw1hu));
jg_baki5=jg_ogq10;
}
if (jg_4tzyv==2)
{
jg_u8x5d=jg_waz6b(jg_il8kg(jg_ehe7x));
jg_7ezr5=jg_u8x5d;
}
if (jg_4tzyv==3)
{
jg_plqo5=jg_waz6b(jg_il8kg(jg_xsbph));
jg_101zz=jg_plqo5;
}
if (jg_4tzyv==4)
{
jg_y10ru=jg_waz6b(jg_il8kg(jg_ofeyk));
jg_slgzj=jg_y10ru;
}
}
jg_j4z55(jg_uhe68, jg_ogq10, jg_u8x5d, jg_plqo5, jg_y10ru);
jg_asx3r();
jg_jl7va();
}
function jg_tdj1xq()
{
jg_f9xs1("down");
jg_2bmnx();
}
function jg_e4rjk1()
{
jg_f9xs1("up");
jg_2bmnx();
}
function jg_u1mjho(jg_gchqy, jg_uhe68)
{
jg_rut107(jg_gchqy);
jg_cl10yy(jg_gchqy.parentNode);
jg_ygnq9(jg_gchqy.parentNode);
jg_jgx2y="";
jg_t2xvj="";
jg_6sqq1="";
jg_yeaxu="";
for (jg_4tzyv=1; jg_4tzyv<=4; jg_4tzyv++)
{
if (jg_4tzyv==1)
{
jg_jgx2y=jg_waz6b(jg_il8kg(jg_jjfu4));
jg_xf4dn=jg_jgx2y;
}
if (jg_4tzyv==2)
{
jg_t2xvj=jg_waz6b(jg_il8kg(jg_k2wsa));
jg_j7rzx=jg_t2xvj;
}
if (jg_4tzyv==3)
{
jg_6sqq1=jg_waz6b(jg_il8kg(jg_btu93));
jg_4h4ql=jg_6sqq1;
}
if (jg_4tzyv==4)
{
jg_yeaxu=jg_waz6b(jg_il8kg(jg_3kboz));
jg_yinz1=jg_yeaxu;
}
}
jg_f9xs1(jg_uhe68);
jg_2bmnx();
jg_9koqi();
}
function jg_j4z55(jg_uhe68, jg_ogq10, jg_u8x5d, jg_plqo5, jg_y10ru)
{
jg_8oghk.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=jg_sl8o4g&jg_41cxen="+jg_uhe68+"&jg_1ji4rm="+jg_ogq10+"&jg_zslnd1="+jg_u8x5d+"&jg_lbv6og="+jg_plqo5+"&jg_9aws9q="+jg_y10ru,false);
jg_8oghk.send();
return jg_8oghk.responseText;
}
function jg_f9xs1(jg_uhe68)
{
try
{
var jg_hya91=document.getElementsByTagName("input");
for (var jg_gchqy=0; jg_gchqy<jg_hya91.length; jg_gchqy++)
{
if (jg_hya91[jg_gchqy].type=="radio")
{
if (jg_hya91[jg_gchqy].checked==true)
{
jg_1010m=jg_hya91[jg_gchqy].parentNode.parentNode.id;
var jg_jgx2y=document.getElementById(jg_1010m).childNodes[1].innerHTML;
jg_jgx2y=jg_waz6b(jg_il8kg(jg_jgx2y));
jg_xf4dn=jg_jgx2y;
var jg_t2xvj=document.getElementById(jg_1010m).childNodes[2].innerHTML;
jg_t2xvj=jg_waz6b(jg_il8kg(jg_t2xvj));
jg_j7rzx=jg_t2xvj;
var jg_6sqq1=document.getElementById(jg_1010m).childNodes[3].innerHTML;
jg_6sqq1=jg_waz6b(jg_il8kg(jg_6sqq1));
jg_4h4ql=jg_6sqq1;
var jg_yeaxu=document.getElementById(jg_1010m).childNodes[4].innerHTML;
jg_yeaxu=jg_waz6b(jg_il8kg(jg_yeaxu));
jg_101zz=jg_yeaxu;
}
}
}
}
catch(jg_u3b9x)
{
alert('Error: ' + jg_u3b9x.toString());
}
jg_e5lo2.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=move_custom_product_field&jg_41cxen="+jg_uhe68+"&field_title_sent="+jg_jgx2y+"&jg_byh10s="+jg_t2xvj+"&attribute_name_sent="+jg_6sqq1+"&attribute_prefix_sent="+jg_yeaxu,false);
jg_e5lo2.send();
return jg_e5lo2.responseText;
}
function jg_59hymu(jg_vc37i)
{
try
{
var jg_hya91=document.getElementsByTagName("input");
for (var jg_gchqy=0; jg_gchqy<jg_hya91.length; jg_gchqy++)
{
if (jg_hya91[jg_gchqy].type=="radio")
{
if (jg_hya91[jg_gchqy].checked==true)
{
jg_1010m=jg_hya91[jg_gchqy].parentNode.parentNode.id;
if (jg_1010m.indexOf("custom-product-field-row-") !== -1)
{
var jg_jgx2y=document.getElementById(jg_1010m).childNodes[1].innerHTML;
jg_jgx2y=jg_waz6b(jg_il8kg(jg_jgx2y));
jg_xf4dn=jg_jgx2y;
var jg_t2xvj=document.getElementById(jg_1010m).childNodes[2].innerHTML;
jg_t2xvj=jg_waz6b(jg_il8kg(jg_t2xvj));
jg_j7rzx=jg_t2xvj;
var jg_6sqq1=document.getElementById(jg_1010m).childNodes[3].innerHTML;
jg_6sqq1=jg_waz6b(jg_il8kg(jg_6sqq1));
jg_4h4ql=jg_6sqq1;
var jg_yeaxu=document.getElementById(jg_1010m).childNodes[4].innerHTML;
jg_yeaxu=jg_waz6b(jg_il8kg(jg_yeaxu));
jg_101zz=jg_yeaxu;
}
}
}
}
}
catch(jg_u3b9x)
{
alert('Error: ' + jg_u3b9x.toString());
}
if(jg_t2xvj)
{
jg_8fvfpf(jg_vc37i,'select_field_length_hovered',jg_t2xvj);return false;
}
}
var jg_xf4dn="";
var jg_j7rzx="";
var jg_4h4ql="";
var jg_yinz1="";
var jg_baki5="";
var jg_7ezr5="";
var jg_101zz="";
var jg_slgzj="";
function jg_y57l6(jg_uhe68)
{
try
{
var jg_hya91=document.getElementsByTagName("input");
for (var jg_gchqy=0; jg_gchqy<jg_hya91.length; jg_gchqy++)
{
if (jg_hya91[jg_gchqy].type=="radio")
{
if (jg_hya91[jg_gchqy].checked==true)
{
jg_1010m=jg_hya91[jg_gchqy].parentNode.parentNode.id;
var jg_ogq10=document.getElementById(jg_1010m).childNodes[1].innerHTML;
jg_ogq10=jg_waz6b(jg_il8kg(jg_ogq10));
jg_baki5=jg_ogq10;
var jg_u8x5d=document.getElementById(jg_1010m).childNodes[2].innerHTML;
jg_u8x5d=jg_waz6b(jg_il8kg(jg_u8x5d));
jg_7ezr5=jg_u8x5d;
var jg_plqo5=document.getElementById(jg_1010m).childNodes[3].innerHTML;
jg_plqo5=jg_waz6b(jg_il8kg(jg_plqo5));
jg_101zz=jg_plqo5;
var jg_y10ru=document.getElementById(jg_1010m).childNodes[4].innerHTML;
jg_y10ru=jg_waz6b(jg_il8kg(jg_y10ru));
jg_slgzj=jg_y10ru;
}
}
}
}
catch(jg_u3b9x)
{
alert('Error: ' + jg_u3b9x.toString());
}
jg_8oghk.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=jg_sl8o4g&jg_41cxen="+jg_uhe68+"&jg_1ji4rm="+jg_ogq10+"&jg_zslnd1="+jg_u8x5d+"&jg_lbv6og="+jg_plqo5+"&jg_9aws9q="+jg_y10ru,false);
jg_8oghk.send();
return jg_8oghk.responseText;
}
function import_outdated_xml_file_v1011_now(jg_bxb21)
{
jg_gsa4m.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=jg_o21lva&jg_nmhchk="+jg_bxb21,false);
jg_gsa4m.send();
alert(jg_gsa4m.responseText);
jg_asx3r();
jg_21ezab('1.0.1.1');
}
function jg_r2bm16(jg_10dvna,jg_sf4jam,jg_yc2xyi,jg_d410tc)
{
if(document.getElementById("img-refresh-list-products-attributes-selection-for-attribute-assignments"))
{
document.getElementById("img-refresh-list-products-attributes-selection-for-attribute-assignments").innerHTML="<img style=\"vertical-align: middle; margin-left: 5px; margin-right: 5px;\" src=\"<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/ajax-loader-circles-16x16.gif\" lowsrc=\"<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/ajax-loader-circles-16x16.gif\"/>";
}
if (window.XMLHttpRequest)
{
jg_mleyr=new XMLHttpRequest();
}
else
{
jg_mleyr=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_mleyr.onreadystatechange=function()
{
if (jg_mleyr.readyState==4&&jg_mleyr.status==200)
{
document.getElementById("jg_eqr6c2").innerHTML=jg_mleyr.responseText;
}
}
jg_a39f6(jg_10dvna,jg_sf4jam,jg_yc2xyi,jg_d410tc)
}
function jg_a39f6(jg_10dvna,jg_sf4jam,jg_yc2xyi,jg_d410tc)
{
var jg_10dvna;
jg_mleyr.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=jg_y81021&jg_xey9ok="+jg_10dvna+"&jg_pdq24d="+jg_sf4jam+"&jg_s1q10z="+jg_yc2xyi+"&language_code="+jg_d410tc,false);
jg_mleyr.send();
}
function jg_3razlp(jg_10dvna,jg_sf4jam,jg_yc2xyi,this_version,jg_d410tc)
{
if(document.getElementById("img-refresh-list-products-options-selection-for-attribute-assignments"))
{
document.getElementById("img-refresh-list-products-options-selection-for-attribute-assignments").innerHTML="<img style=\"vertical-align: middle; margin-left: 5px; margin-right: 5px;\" src=\"<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/ajax-loader-circles-16x16.gif\" lowsrc=\"<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/ajax-loader-circles-16x16.gif\"/>";
}
if (window.XMLHttpRequest)
{
jg_sk8hb=new XMLHttpRequest();
}
else
{
jg_sk8hb=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_sk8hb.onreadystatechange=function()
{
if (jg_sk8hb.readyState==4&&jg_sk8hb.status==200)
{
document.getElementById("jg_wu109i").innerHTML=jg_sk8hb.responseText;
}
}
jg_b3f10(jg_10dvna,jg_sf4jam,jg_yc2xyi,this_version,jg_d410tc);
}
function jg_b3f10(jg_10dvna,jg_sf4jam,jg_yc2xyi,this_version,jg_d410tc)
{
var jg_10dvna;
jg_sk8hb.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=jg_cncqy5&jg_xey9ok="+jg_10dvna+"&jg_pdq24d="+jg_sf4jam+"&jg_s1q10z="+jg_yc2xyi+"&jg_y96zcl="+this_version+"&language_code="+jg_d410tc,false);
jg_sk8hb.send();
}
function jg_h10gc8(jg_10dvna,jg_sf4jam,jg_yc2xyi,jg_d410tc)
{
if(document.getElementById("img-refresh-list-products-categories-selection-for-attribute-assignments"))
{
document.getElementById("img-refresh-list-products-categories-selection-for-attribute-assignments").innerHTML="<img style=\"vertical-align: middle; margin-left: 5px; margin-right: 5px;\" src=\"<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/ajax-loader-circles-16x16.gif\" lowsrc=\"<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/ajax-loader-circles-16x16.gif\"/>";
}
if (window.XMLHttpRequest)
{
jg_vu2x1=new XMLHttpRequest();
}
else
{
jg_vu2x1=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_vu2x1.onreadystatechange=function()
{
if (jg_vu2x1.readyState==4&&jg_vu2x1.status==200)
{
document.getElementById("jg_xcb1s8").innerHTML=jg_vu2x1.responseText;
}
}
jg_qucrt(jg_10dvna,jg_sf4jam,jg_yc2xyi,jg_d410tc)
}
function jg_qucrt(jg_10dvna,jg_sf4jam,jg_yc2xyi,jg_d410tc)
{
var jg_10dvna;
jg_vu2x1.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=jg_mq104x&jg_xey9ok="+jg_10dvna+"&jg_pdq24d="+jg_sf4jam+"&jg_s1q10z="+jg_yc2xyi+"&language_code="+jg_d410tc,false);
jg_vu2x1.send();
}
function jg_xylwf1(jg_10dvna,jg_sf4jam,jg_yc2xyi,jg_d410tc)
{
if(document.getElementById("img-refresh-list-products-names-selection-for-attribute-assignments"))
{
document.getElementById("img-refresh-list-products-names-selection-for-attribute-assignments").innerHTML="<img style=\"vertical-align: middle; margin-left: 5px; margin-right: 5px;\" src=\"<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/ajax-loader-circles-16x16.gif\" lowsrc=\"<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/ajax-loader-circles-16x16.gif\"/>";
}
if (window.XMLHttpRequest)
{
jg_5ewns=new XMLHttpRequest();
}
else
{
jg_5ewns=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_5ewns.onreadystatechange=function()
{
if (jg_5ewns.readyState==4&&jg_5ewns.status==200)
{
document.getElementById("jg_inuxy7").innerHTML=jg_5ewns.responseText;
}
}
jg_c97um(jg_10dvna,jg_sf4jam,jg_yc2xyi,jg_d410tc);
}
function jg_wnke9v(jg_10dvna,jg_sf4jam,jg_yc2xyi,jg_d410tc)
{
if(document.getElementById("refresh_products_names_selection_for_editing"))
{
document.getElementById("refresh_products_names_selection_for_editing").innerHTML="<img style=\"vertical-align: middle; margin-left: 5px; margin-right: 5px;\" src=\"<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/ajax-loader-circles-16x16.gif\" lowsrc=\"<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/ajax-loader-circles-16x16.gif\"/>";
}
if (window.XMLHttpRequest)
{
jg_jieea=new XMLHttpRequest();
}
else
{
jg_jieea=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_jieea.onreadystatechange=function()
{
if (jg_jieea.readyState==4&&jg_jieea.status==200)
{
document.getElementById("opencart_products_list_for_editing").innerHTML=jg_jieea.responseText;
jg_ezoexo(document.getElementById("opencart_product_for_editing"));
}
}
jg_wpl11(jg_10dvna,jg_sf4jam,jg_yc2xyi,jg_d410tc);
}
function jg_c97um(jg_10dvna,jg_sf4jam,jg_yc2xyi,jg_d410tc)
{
jg_5ewns.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=jg_yg5lr2&jg_xey9ok="+jg_10dvna+"&jg_pdq24d="+jg_sf4jam+"&jg_s1q10z="+jg_yc2xyi+"&language_code="+jg_d410tc,false);
jg_5ewns.send();
}
function jg_wpl11(jg_10dvna,jg_sf4jam,jg_yc2xyi,jg_d410tc)
{
jg_jieea.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=update_list_contents_opencart_product_names_list_for_editing&jg_xey9ok="+jg_10dvna+"&jg_pdq24d="+jg_sf4jam+"&jg_s1q10z="+jg_yc2xyi+"&language_code="+jg_d410tc,false);
jg_jieea.send();
}
function jg_6yr5rx(jg_10yi6,jg_3nme4,jg_gshng)
{
if (window.XMLHttpRequest)
{
jg_mz6gm=new XMLHttpRequest();
}
else
{
jg_mz6gm=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_mz6gm.onreadystatechange=function()
{
if (jg_mz6gm.readyState==4&&jg_mz6gm.status==200)
{
if((document.getElementById(jg_3nme4))&&(jg_gshng==true))
{
}
}
}
if((document.getElementById('opencart_product_for_editing'))&&(jg_ul3vh==''))
{
jg_6mq10=document.getElementById('opencart_product_for_editing').options[document.getElementById('opencart_product_for_editing').selectedIndex].id;
jg_6mq10=jg_waz6b(jg_6mq10);
jg_dt9i6=jg_10yi6;
jg_dt9i6=jg_waz6b(jg_dt9i6);
jg_410pk=jg_410pk=document.getElementById(jg_3nme4).name;
jg_410pk=jg_waz6b(jg_410pk);
jg_10qt9();
}
if((document.getElementById('opencart_product_for_editing'))&&(
(jg_ul3vh=='custom_product_field_id_google_product_category')||(jg_ul3vh=='custom_product_field_id_google_category')||
(jg_ul3vh=='custom_product_field_id_google_product_category_au')||(jg_ul3vh=='custom_product_field_id_google_category_au')||
(jg_ul3vh=='custom_product_field_id_google_product_category_br')||(jg_ul3vh=='custom_product_field_id_google_category_br')||
(jg_ul3vh=='custom_product_field_id_google_product_category_ch')||(jg_ul3vh=='custom_product_field_id_google_category_ch')||
(jg_ul3vh=='custom_product_field_id_google_product_category_cn')||(jg_ul3vh=='custom_product_field_id_google_category_cn')||
(jg_ul3vh=='custom_product_field_id_google_product_category_cz')||(jg_ul3vh=='custom_product_field_id_google_category_cz')||
(jg_ul3vh=='custom_product_field_id_google_product_category_de')||(jg_ul3vh=='custom_product_field_id_google_category_de')||
(jg_ul3vh=='custom_product_field_id_google_product_category_es')||(jg_ul3vh=='custom_product_field_id_google_category_es')||
(jg_ul3vh=='custom_product_field_id_google_product_category_fr')||(jg_ul3vh=='custom_product_field_id_google_category_fr')||
(jg_ul3vh=='custom_product_field_id_google_product_category_gb')||(jg_ul3vh=='custom_product_field_id_google_category_gb')||
(jg_ul3vh=='custom_product_field_id_google_product_category_it')||(jg_ul3vh=='custom_product_field_id_google_category_it')||
(jg_ul3vh=='custom_product_field_id_google_product_category_jp')||(jg_ul3vh=='custom_product_field_id_google_category_jp')||
(jg_ul3vh=='custom_product_field_id_google_product_category_nl')||(jg_ul3vh=='custom_product_field_id_google_category_nl')||
(jg_ul3vh=='custom_product_field_id_google_product_category_us')||(jg_ul3vh=='custom_product_field_id_google_category_us')
))
{
jg_6mq10=document.getElementById('opencart_product_for_editing').options[document.getElementById('opencart_product_for_editing').selectedIndex].id;
jg_6mq10=jg_waz6b(jg_6mq10);
jg_dt9i6=jg_10yi6;
jg_dt9i6=jg_waz6b(jg_dt9i6);
jg_410pk=jg_410pk=document.getElementById(jg_3nme4).name;
jg_410pk=jg_waz6b(jg_410pk);
jg_10qt9();
jg_ul3vh='';
}
if(document.getElementById('default_google_product_category')&&(
(jg_ul3vh=='default_google_product_category')||
(jg_ul3vh=='default_google_product_category_au')||
(jg_ul3vh=='default_google_product_category_br')||
(jg_ul3vh=='default_google_product_category_ca')||
(jg_ul3vh=='default_google_product_category_ch')||
(jg_ul3vh=='default_google_product_category_cn')||
(jg_ul3vh=='default_google_product_category_cz')||
(jg_ul3vh=='default_google_product_category_de')||
(jg_ul3vh=='default_google_product_category_es')||
(jg_ul3vh=='default_google_product_category_fr')||
(jg_ul3vh=='default_google_product_category_gb')||
(jg_ul3vh=='default_google_product_category_it')||
(jg_ul3vh=='default_google_product_category_jp')||
(jg_ul3vh=='default_google_product_category_nl')||
(jg_ul3vh=='default_google_product_category_us')
))
{
jg_uc7943('',jg_10yi6,false);
}
}
function jg_v510dd()
{
if(document.getElementById('opencart_product_for_editing').selectedIndex>0)
{
document.getElementById('opencart_product_for_editing').selectedIndex=document.getElementById('opencart_product_for_editing').selectedIndex-1;
}else{
}
jg_ezoexo(document.getElementById("opencart_product_for_editing"));
}
function jg_ix92ag()
{
if(document.getElementById('opencart_product_for_editing').selectedIndex<document.getElementById('opencart_product_for_editing').options.length-1)
{
document.getElementById('opencart_product_for_editing').selectedIndex=document.getElementById('opencart_product_for_editing').selectedIndex+1;
}else{
}
jg_ezoexo(document.getElementById("opencart_product_for_editing"));
}
function jg_10qt9()
{
jg_mz6gm.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",true);
jg_mz6gm.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_mz6gm.send("jg_bfrx10=save_setting_custom_product_field_value&value_sent="+jg_dt9i6+"&id_sent="+jg_6mq10+"&column_sent="+jg_410pk);
}
function jg_isazwy(jg_lym4x,jg_z9huo)
{
jg_i3ege.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=jg_8twrsx&jg_xhbkea="+jg_lym4x+"&jg_k51rvb="+jg_z9huo+"&jg_y96zcl=<?php echo VERSION ?>",false);
jg_i3ege.send();
jg_1a2fs8();
}
function jg_bffcti(jg_lym4x,jg_z9huo)
{
jg_10sxh.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=jg_39j2i1&jg_xhbkea="+jg_lym4x+"&jg_k51rvb="+jg_z9huo,false);
jg_10sxh.send();
jg_1a2fs8();
}
function jg_vbn1sk(jg_lym4x,jg_z9huo)
{
jg_ptuvp.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=jg_l1s1nb&jg_xhbkea="+jg_lym4x+"&jg_k51rvb="+jg_z9huo+"&jg_y96zcl=<?php echo VERSION; ?>",false);
jg_ptuvp.send();
var this_response=jg_ptuvp.responseText;
if(this_response!=''){alert(jg_ptuvp.responseText);}else{jg_1a2fs8();}
}
function jg_21ezab(jg_oqayy)
{
switch (jg_oqayy)
{
case "1.0.1.1":
if (document.getElementById("jg_xqr6pt").style.display=="block")
{
document.getElementById("jg_xqr6pt").style.display="none";
document.getElementById("jg_38jiae").innerHTML="Import v1.0.1.1 XML file";
}
else
{
document.getElementById("jg_xqr6pt").style.display="block";
document.getElementById("jg_38jiae").innerHTML="Cancel v1.0.1.1 XML file import";
}
break;
default:
break;
}
}
function jg_h6r2zg(jg_oqayy, jg_bxb21)
{
switch (jg_oqayy)
{
case "1.0.1.1":
if(confirm('Are you sure you want to delete the outdated XML file\r\nfrom v' + jg_oqayy + '?\r\n\r\nThe data from the file will be permanently deleted.'))
{
jg_zsdxl.open("GET","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php?jg_ftyqkn=jg_4xzfa9&jg_nmhchk="+jg_bxb21,false);
jg_zsdxl.send();
alert(jg_zsdxl.responseText);
jg_21ezab('1.0.1.1');
document.getElementById("jg_jd99cv").style.display="none";
document.getElementById("jg_jd99cv").style.visibility="hidden";
}
break;
default:
break;
}
}
function jg_7s449t(jg_m9a10)
{
if(document.getElementById('selected_category'))
{
if(jg_m9a10=='')
{
jg_m9a10=jg_ul3vh;
}
if(jg_ul3vh=='')
{
return false;
}
jg_3nme4=jg_m9a10;
if(document.getElementById(jg_3nme4))
{
document.getElementById(jg_3nme4).value=jg_il8kg(document.getElementById('selected_category').innerHTML);
jg_6yr5rx(document.getElementById(jg_3nme4).value,jg_3nme4,false);
}
jg_sbedlz();
}
}
function jg_awm65(this_status)
{
jg_meza4.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",false);
jg_meza4.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_meza4.send("jg_bfrx10=update_extension_status&extension_status="+this_status);
}
function update_cache(this_cache_object_name)
{
jg_7aqzq.open("POST","<?php echo OPENCART_ADMIN_DIRECTORY_URL.JG_9TVQEW; ?>.php",true);
jg_7aqzq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
jg_7aqzq.send("jg_bfrx10=update_cache&cache_object_name="+this_cache_object_name);
}
function jg_ues10p()
{
window.open(<?php echo '"'.str_replace("&amp;", "&", $currency).'"'; ?>, '_blank');
}
function jg_jl7va()
{
var image_selected_url='view/image/selected.png';
var jg_hya91=document.getElementsByTagName("input");
for (var jg_gchqy=0; jg_gchqy<jg_hya91.length; jg_gchqy++)
{
if (jg_hya91[jg_gchqy].type=="radio")
{
if (jg_hya91[jg_gchqy].id.indexOf("option-attribute-assignment-") !== -1)
{
jg_1010m=jg_hya91[jg_gchqy].parentNode.parentNode.id;
var jg_ogq10=document.getElementById(jg_1010m).childNodes[1].innerHTML;
jg_ogq10=jg_waz6b(jg_il8kg(jg_ogq10));
var jg_u8x5d=document.getElementById(jg_1010m).childNodes[2].innerHTML;
jg_u8x5d=jg_waz6b(jg_il8kg(jg_u8x5d));
var jg_plqo5=document.getElementById(jg_1010m).childNodes[3].innerHTML;
jg_plqo5=jg_waz6b(jg_il8kg(jg_plqo5));
var jg_y10ru=document.getElementById(jg_1010m).childNodes[4].innerHTML;
jg_y10ru=jg_waz6b(jg_il8kg(jg_y10ru));
if ((jg_ogq10==jg_waz6b(jg_il8kg(jg_baki5)))&&(jg_u8x5d==jg_waz6b(jg_il8kg(jg_7ezr5)))&&(jg_plqo5==jg_waz6b(jg_il8kg(jg_101zz)))&&(jg_y10ru==jg_waz6b(jg_il8kg(jg_slgzj))))
{
jg_hya91[jg_gchqy].checked=true;
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[0].style.cssText ="color: #ffffff; background-image:url('view/image/selected.png') !important; cursor: pointer";
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[1].style.cssText ="color: #ffffff; background-image:url('view/image/selected.png') !important; cursor: pointer";
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[2].style.cssText ="color: #ffffff; background-image:url('view/image/selected.png') !important; cursor: pointer";
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[3].style.cssText ="color: #ffffff; background-image:url('view/image/selected.png') !important; cursor: pointer";
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[4].style.cssText ="color: #ffffff; background-image:url('view/image/selected.png') !important; cursor: pointer";
break;
}
}
}
}
}
function jg_9koqi()
{
var jg_hya91=document.getElementsByTagName("input");
for (var jg_gchqy=0; jg_gchqy<jg_hya91.length; jg_gchqy++)
{
if (jg_hya91[jg_gchqy].type=="radio")
{
if (jg_hya91[jg_gchqy].id.indexOf("option-custom-product-field-") !== -1)
{
jg_1010m=jg_hya91[jg_gchqy].parentNode.parentNode.id;
var jg_jgx2y=document.getElementById(jg_1010m).childNodes[1].innerHTML;
jg_jgx2y=jg_waz6b(jg_il8kg(jg_jgx2y));
var jg_t2xvj=document.getElementById(jg_1010m).childNodes[2].innerHTML;
jg_t2xvj=jg_waz6b(jg_il8kg(jg_t2xvj));
var jg_6sqq1=document.getElementById(jg_1010m).childNodes[3].innerHTML;
jg_6sqq1=jg_waz6b(jg_il8kg(jg_6sqq1));
var jg_yeaxu=document.getElementById(jg_1010m).childNodes[4].innerHTML;
jg_yeaxu=jg_waz6b(jg_il8kg(jg_yeaxu));
if ((jg_jgx2y==jg_waz6b(jg_il8kg(jg_xf4dn)))&&(jg_t2xvj==jg_waz6b(jg_il8kg(jg_j7rzx)))&&(jg_6sqq1==jg_waz6b(jg_il8kg(jg_4h4ql)))&&(jg_yeaxu==jg_waz6b(jg_il8kg(jg_yinz1))))
{
jg_hya91[jg_gchqy].checked=true;
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[0].style.backgroundImage="url('view/image/selected.png')";
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[0].style.color="#ffffff";
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[1].style.backgroundImage="url('view/image/selected.png')";
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[1].style.color="#ffffff";
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[2].style.backgroundImage="url('view/image/selected.png')";
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[2].style.color="#ffffff";
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[3].style.backgroundImage="url('view/image/selected.png')";
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[3].style.color="#ffffff";
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[4].style.backgroundImage="url('view/image/selected.png')";
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[4].style.color="#ffffff";
break;
}
}
}
}
}
function jg_ujem4(s) {
s=s.replace(/(^\s*)|(\s*$)/gi,"");
s=s.replace(/[ ]{2,}/gi," ");
s=s.replace(/\n /,"\n");
return s;
}
function jg_ti2uh(jg_ps2et, jg_10yi6)
{
if(jg_ps2et)
{
var JG_TDC36= jg_ps2et.options;
var JG_464ID=JG_TDC36.length;
while(JG_464ID)
{
if (JG_TDC36[--JG_464ID].value==jg_10yi6)
{
jg_ps2et.selectedIndex=JG_464ID;
JG_464ID= 0;
}
}
}
}
function jg_lpt56(jg_ps2et, jg_10yi6)
{
if(jg_ps2et)
{
var JG_TDC36= jg_ps2et.options;
var JG_464ID=JG_TDC36.length;
while(JG_464ID)
{
if (JG_TDC36[--JG_464ID].id==jg_10yi6)
{
jg_ps2et.selectedIndex=JG_464ID;
JG_464ID= 0;
}
}
}
}
function jg_lzk32()
{
try
{
document.getElementById("container").style.width="auto";
document.getElementById("container").style.whiteSpace="nowrap";
document.getElementById("content").style.width="auto";
document.getElementById("content").style.whiteSpace="nowrap";
}
catch (jg_u3b9x)
{
}
try
{
document.getElementById("container").style.display="inline-block;";
document.getElementById("content").style.display="inline-block;";
}
catch (jg_u3b9x)
{
}
if (document.getElementsByClassName)
{
var jg_68n93=['box'];
for (var jg_7qrhk=0; jg_7qrhk<jg_68n93.length; jg_7qrhk++)
{
j=0;
var jg_uldi3=document.getElementsByClassName(jg_68n93[jg_7qrhk]);
for (var jg_gchqy=0; jg_gchqy<jg_uldi3.length; jg_gchqy++)
{
if (jg_uldi3[jg_gchqy].getAttribute('class')==jg_68n93[jg_7qrhk])
{
j=j+1;
document.getElementsByClassName(jg_68n93[jg_7qrhk])[jg_gchqy].setAttribute('style', 'display: inline-block; width: auto; white-space: nowrap;');
}
if (jg_gchqy >= jg_uldi3.length - 1)
{
break;
}
}
}
}
}
var jg_dncmv="";
var jg_2bl5i="";
var jg_gf2dj="";
var jg_7o1q5="";
function jg_fyp6o8(jg_l2w9p, jg_gchqy)
{
if(typeof jg_gchqy.parentNode.childNodes[1].childNodes[0]=='undefined')
{
jg_dncmv=jg_gchqy.parentNode.childNodes[1].innerHTML
}
else
{
if(typeof jg_gchqy.parentNode.childNodes[1].childNodes[0].type!='undefined')
{
jg_dncmv=jg_gchqy.parentNode.childNodes[1].childNodes[0].value;
}
else
{
jg_dncmv=jg_gchqy.parentNode.childNodes[1].innerHTML;
}
}
if(typeof jg_gchqy.parentNode.childNodes[2].childNodes[0]=='undefined')
{
jg_2bl5i=jg_gchqy.parentNode.childNodes[2].innerHTML
}
else
{
if(typeof jg_gchqy.parentNode.childNodes[2].childNodes[0].type!='undefined')
{
jg_2bl5i=jg_gchqy.parentNode.childNodes[2].childNodes[0].value;
}
else
{
jg_2bl5i=jg_gchqy.parentNode.childNodes[2].innerHTML;
}
}
if(typeof jg_gchqy.parentNode.childNodes[3].childNodes[0]=='undefined')
{
jg_gf2dj=jg_gchqy.parentNode.childNodes[3].innerHTML
}
else
{
if(typeof jg_gchqy.parentNode.childNodes[3].childNodes[0].type!='undefined')
{
jg_gf2dj=jg_gchqy.parentNode.childNodes[3].childNodes[0].value;
}
else
{
jg_gf2dj=jg_gchqy.parentNode.childNodes[3].innerHTML;
}
}
if(typeof jg_gchqy.parentNode.childNodes[4].childNodes[0]=='undefined')
{
jg_7o1q5=jg_gchqy.parentNode.childNodes[4].innerHTML
}
else
{
if(typeof jg_gchqy.parentNode.childNodes[4].childNodes[0].type!='undefined')
{
jg_7o1q5=jg_gchqy.parentNode.childNodes[4].childNodes[0].value;
}
else
{
jg_7o1q5=jg_gchqy.parentNode.childNodes[4].innerHTML;
}
}
if (jg_l2w9p.keyCode==13)
{
jg_gchqy.innerHTML=jg_gchqy.childNodes[0].value;
jg_e9msc(jg_yw1hu, jg_ehe7x, jg_xsbph, jg_ofeyk, jg_dncmv, jg_2bl5i, jg_gf2dj, jg_7o1q5);
jg_baki5=jg_dncmv;
jg_7ezr5=jg_2bl5i;
jg_101zz=jg_gf2dj;
jg_slgzj=jg_7o1q5;
jg_asx3r();
}
if (jg_l2w9p.keyCode==27)
{
var jg_srnld="";
if (jg_gchqy.id.indexOf("opencart-field-")==-1)
{
}
else
{
jg_srnld=jg_yw1hu;
}
if (jg_gchqy.id.indexOf("opencart-field-value-")==-1)
{
}
else
{
jg_srnld=jg_ehe7x;
}
if (jg_gchqy.id.indexOf("google-attribute-")==-1)
{
}
else
{
jg_srnld=jg_xsbph;
}
if (jg_gchqy.id.indexOf("google-attribute-value-")==-1)
{
}
else
{
jg_srnld=jg_ofeyk;
}
jg_gchqy.innerHTML=jg_srnld;
jg_tz9bf(jg_gchqy);
jg_asx3r();
jg_eoy6o();
}
}
var jg_mcy1k="";
var jg_sphyd="";
var jg_pubss="";
var jg_6r2l8="";
function jg_kwxrtx(jg_l2w9p, jg_gchqy)
{
if(typeof jg_gchqy.parentNode.childNodes[1].childNodes[0]=='undefined')
{
jg_mcy1k=jg_gchqy.parentNode.childNodes[1].innerHTML
}
else
{
if(typeof jg_gchqy.parentNode.childNodes[1].childNodes[0].type!='undefined')
{
jg_mcy1k=jg_gchqy.parentNode.childNodes[1].childNodes[0].value;
}
else
{
jg_mcy1k=jg_gchqy.parentNode.childNodes[1].innerHTML;
}
}
if(typeof jg_gchqy.parentNode.childNodes[2].childNodes[0]=='undefined')
{
jg_sphyd=jg_gchqy.parentNode.childNodes[2].innerHTML
}
else
{
if(typeof jg_gchqy.parentNode.childNodes[2].childNodes[0].type!='undefined')
{
jg_sphyd=jg_gchqy.parentNode.childNodes[2].childNodes[0].value;
}
else
{
jg_sphyd=jg_gchqy.parentNode.childNodes[2].innerHTML;
}
}
if(typeof jg_gchqy.parentNode.childNodes[3].childNodes[0]=='undefined')
{
jg_pubss=jg_gchqy.parentNode.childNodes[3].innerHTML
}
else
{
if(typeof jg_gchqy.parentNode.childNodes[3].childNodes[0].type!='undefined')
{
jg_pubss=jg_gchqy.parentNode.childNodes[3].childNodes[0].value;
}
else
{
jg_pubss=jg_gchqy.parentNode.childNodes[3].innerHTML;
}
}
if(typeof jg_gchqy.parentNode.childNodes[4].childNodes[0]=='undefined')
{
jg_6r2l8=jg_gchqy.parentNode.childNodes[4].innerHTML
}
else
{
if(typeof jg_gchqy.parentNode.childNodes[4].childNodes[0].type!='undefined')
{
jg_6r2l8=jg_gchqy.parentNode.childNodes[4].childNodes[0].value;
}
else
{
jg_6r2l8=jg_gchqy.parentNode.childNodes[4].innerHTML;
}
}
if (jg_l2w9p.keyCode==13)
{
jg_gchqy.innerHTML=jg_gchqy.childNodes[0].value;
jg_kz510(jg_jjfu4,jg_k2wsa,jg_btu93,jg_3kboz,jg_mcy1k,jg_sphyd,jg_pubss,jg_6r2l8);
jg_xf4dn=jg_mcy1k;
jg_j7rzx=jg_sphyd;
jg_4h4ql=jg_pubss;
jg_yinz1=jg_6r2l8;
jg_2bmnx();
}
if (jg_l2w9p.keyCode==27)
{
var jg_srnld="";
if (jg_gchqy.id.indexOf("custom_product_field_title")==-1)
{
}
else
{
jg_srnld=jg_jjfu4;
}
if (jg_gchqy.id.indexOf("custom_product_field_column_name")==-1)
{
}
else
{
jg_srnld=jg_k2wsa;
}
if (jg_gchqy.id.indexOf("custom_product_field_attribute_name")==-1)
{
}
else
{
jg_srnld=jg_btu93;
}
if (jg_gchqy.id.indexOf("custom_product_field_attribute_prefix")==-1)
{
}
else
{
jg_srnld=jg_3kboz;
}
jg_gchqy.innerHTML=jg_srnld;
jg_ygnq9(jg_gchqy);
jg_2bmnx();
jg_bi4un();
}
}
function text_column_size_edit_key_press(jg_t2xvj,jg_l2w9p,jg_gchqy)
{
if (jg_l2w9p.keyCode==27)
{
jg_sbedlz();
}
if (jg_l2w9p.keyCode==13)
{
jg_z1gdr1(jg_t2xvj,document.getElementById('field_length_selection_'+jg_t2xvj).value);jg_sbedlz();
}
}
function jg_urxar7(jg_gchqy)
{
if(typeof jg_gchqy.parentNode.childNodes[1].childNodes[0]=='undefined')
{
jg_dncmv=jg_gchqy.parentNode.childNodes[1].innerHTML
}
else
{
if(typeof jg_gchqy.parentNode.childNodes[1].childNodes[0].type!='undefined')
{
jg_dncmv=jg_gchqy.parentNode.childNodes[1].childNodes[0].value;
}
else
{
jg_dncmv=jg_gchqy.parentNode.childNodes[1].innerHTML;
}
}
if(typeof jg_gchqy.parentNode.childNodes[2].childNodes[0]=='undefined')
{
jg_2bl5i=jg_gchqy.parentNode.childNodes[2].innerHTML
}
else
{
if(typeof jg_gchqy.parentNode.childNodes[2].childNodes[0].type!='undefined')
{
jg_2bl5i=jg_gchqy.parentNode.childNodes[2].childNodes[0].value;
}
else
{
jg_2bl5i=jg_gchqy.parentNode.childNodes[2].innerHTML;
}
}
if(typeof jg_gchqy.parentNode.childNodes[3].childNodes[0]=='undefined')
{
jg_gf2dj=jg_gchqy.parentNode.childNodes[3].innerHTML
}
else
{
if(typeof jg_gchqy.parentNode.childNodes[3].childNodes[0].type!='undefined')
{
jg_gf2dj=jg_gchqy.parentNode.childNodes[3].childNodes[0].value;
}
else
{
jg_gf2dj=jg_gchqy.parentNode.childNodes[3].innerHTML;
}
}
if(typeof jg_gchqy.parentNode.childNodes[4].childNodes[0]=='undefined')
{
jg_7o1q5=jg_gchqy.parentNode.childNodes[4].innerHTML
}
else
{
if(typeof jg_gchqy.parentNode.childNodes[4].childNodes[0].type!='undefined')
{
jg_7o1q5=jg_gchqy.parentNode.childNodes[4].childNodes[0].value;
}
else
{
jg_7o1q5=jg_gchqy.parentNode.childNodes[4].innerHTML;
}
}
jg_baki5=jg_dncmv;
jg_7ezr5=jg_2bl5i;
jg_101zz=jg_gf2dj;
jg_slgzj=jg_7o1q5;
if (jg_gchqy.parentNode.childNodes[0].type="radio")
{
jg_lep10(jg_gchqy);
jg_gchqy.parentNode.childNodes[0].childNodes[0].checked=true;
jg_gchqy.parentNode.childNodes[0].style.cssText ="width: 10px; color: #ffffff; background-image:url('view/image/selected.png') !important; cursor: pointer";
jg_gchqy.parentNode.childNodes[1].style.cssText ="width: 10px; color: #ffffff; background-image:url('view/image/selected.png') !important; cursor: pointer";
jg_gchqy.parentNode.childNodes[2].style.cssText ="width: 10px; color: #ffffff; background-image:url('view/image/selected.png') !important; cursor: pointer";
jg_gchqy.parentNode.childNodes[3].style.cssText ="width: 10px; color: #ffffff; background-image:url('view/image/selected.png') !important; cursor: pointer";
jg_gchqy.parentNode.childNodes[4].style.cssText ="width: auto; color: #ffffff; background-image:url('view/image/selected.png') !important; cursor: pointer";
}
}
function jg_cl10yy(jg_gchqy)
{
if(typeof jg_gchqy.parentNode.childNodes[1].childNodes[0]=='undefined')
{
jg_mcy1k=jg_gchqy.parentNode.childNodes[1].innerHTML
}
else
{
if(typeof jg_gchqy.parentNode.childNodes[1].childNodes[0].type!='undefined')
{
jg_mcy1k=jg_gchqy.parentNode.childNodes[1].childNodes[0].value;
}
else
{
jg_mcy1k=jg_gchqy.parentNode.childNodes[1].innerHTML;
}
}
if(typeof jg_gchqy.parentNode.childNodes[2].childNodes[0]=='undefined')
{
jg_sphyd=jg_gchqy.parentNode.childNodes[2].innerHTML
}
else
{
if(typeof jg_gchqy.parentNode.childNodes[2].childNodes[0].type!='undefined')
{
jg_sphyd=jg_gchqy.parentNode.childNodes[2].childNodes[0].value;
}
else
{
jg_sphyd=jg_gchqy.parentNode.childNodes[2].innerHTML;
}
}
if(typeof jg_gchqy.parentNode.childNodes[3].childNodes[0]=='undefined')
{
jg_pubss=jg_gchqy.parentNode.childNodes[3].innerHTML
}
else
{
if(typeof jg_gchqy.parentNode.childNodes[3].childNodes[0].type!='undefined')
{
jg_pubss=jg_gchqy.parentNode.childNodes[3].childNodes[0].value;
}
else
{
jg_pubss=jg_gchqy.parentNode.childNodes[3].innerHTML;
}
}
if(typeof jg_gchqy.parentNode.childNodes[4].childNodes[0]=='undefined')
{
jg_6r2l8=jg_gchqy.parentNode.childNodes[4].innerHTML
}
else
{
if(typeof jg_gchqy.parentNode.childNodes[4].childNodes[0].type!='undefined')
{
jg_6r2l8=jg_gchqy.parentNode.childNodes[4].childNodes[0].value;
}
else
{
jg_6r2l8=jg_gchqy.parentNode.childNodes[4].innerHTML;
}
}
jg_xf4dn=jg_mcy1k;
jg_j7rzx=jg_sphyd;
jg_4h4ql=jg_pubss;
jg_yinz1=jg_6r2l8;
if (jg_gchqy.parentNode.childNodes[0].type="radio")
{
jg_rut107(jg_gchqy);
if(jg_gchqy.parentNode)
{
jg_gchqy.parentNode.childNodes[0].childNodes[0].checked=true;
jg_gchqy.parentNode.childNodes[0].style.backgroundImage="url('view/image/selected.png')";
jg_gchqy.parentNode.childNodes[0].style.color="#ffffff";
jg_gchqy.parentNode.childNodes[1].style.backgroundImage="url('view/image/selected.png')";
jg_gchqy.parentNode.childNodes[1].style.color="#ffffff";
jg_gchqy.parentNode.childNodes[2].style.backgroundImage="url('view/image/selected.png')";
jg_gchqy.parentNode.childNodes[2].style.color="#ffffff";
jg_gchqy.parentNode.childNodes[3].style.backgroundImage="url('view/image/selected.png')";
jg_gchqy.parentNode.childNodes[3].style.color="#ffffff";
jg_gchqy.parentNode.childNodes[4].style.backgroundImage="url('view/image/selected.png')";
jg_gchqy.parentNode.childNodes[4].style.color="#ffffff";
}
}
}
function jg_rut107(jg_9ep2q)
{
var save_when_done=0;
var jg_hya91=document.getElementsByTagName("input");
for (var jg_gchqy=0; jg_gchqy<jg_hya91.length; jg_gchqy++)
{
if (jg_hya91[jg_gchqy].type=="radio")
{
if (jg_hya91[jg_gchqy].id.indexOf("option-custom-product-field-") !== -1)
{
jg_dm9rp=jg_hya91[jg_gchqy].parentNode.id;
if(jg_9ep2q.parentNode)
{
if (jg_dm9rp==jg_9ep2q.parentNode.childNodes[0].id)
{
var jg_c95r7=4;
for (jg_4tzyv=1; jg_4tzyv<=4; jg_4tzyv++)
{
if(typeof jg_9ep2q.parentNode.childNodes[jg_4tzyv].childNodes[0]!='undefined')
{
if(typeof jg_9ep2q.parentNode.childNodes[jg_4tzyv].childNodes[0].type!='undefined')
{
save_when_done=1;
if (jg_9ep2q.parentNode.childNodes[jg_4tzyv].id!=jg_9ep2q.id)
{
jg_9ep2q.parentNode.childNodes[jg_4tzyv].innerHTML=jg_9ep2q.parentNode.childNodes[jg_4tzyv].childNodes[0].value;
switch (jg_4tzyv)
{
case 1:
jg_mcy1k=jg_9ep2q.parentNode.childNodes[jg_4tzyv].innerHTML;
break;
case 2:
jg_sphyd=jg_9ep2q.parentNode.childNodes[jg_4tzyv].innerHTML;
break;
case 3:
jg_pubss=jg_9ep2q.parentNode.childNodes[jg_4tzyv].innerHTML;
break;
case 4:
jg_6r2l8=jg_9ep2q.parentNode.childNodes[jg_4tzyv].innerHTML;
break;
default:
break;
}
}
else
{
switch (jg_4tzyv)
{
case 1:
jg_mcy1k=jg_9ep2q.parentNode.childNodes[jg_4tzyv].childNodes[0].value;
break;
case 2:
jg_sphyd=jg_9ep2q.parentNode.childNodes[jg_4tzyv].childNodes[0].value;
break;
case 3:
jg_pubss=jg_9ep2q.parentNode.childNodes[jg_4tzyv].childNodes[0].value;
break;
case 4:
jg_6r2l8=jg_9ep2q.parentNode.childNodes[jg_4tzyv].childNodes[0].value;
break;
default:
break;
}
}
}
}
}
if(save_when_done==1)
{
jg_kz510(jg_jjfu4, jg_k2wsa, jg_btu93, jg_3kboz, jg_mcy1k, jg_sphyd, jg_pubss, jg_6r2l8);
if(jg_9ep2q.innerHTML.toLowerCase().indexOf("<input")>-1)
{
}
else
{
jg_2bmnx();
}
}
else
{
}
}
else
{
for (jg_4tzyv=1; jg_4tzyv<=4; jg_4tzyv++)
{
if(typeof jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[jg_4tzyv].childNodes[0]!='undefined')
{
if(typeof jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[jg_4tzyv].childNodes[0].type!='undefined')
{
if(jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[jg_4tzyv].innerHTML.toLowerCase().indexOf("<input")>-1)
{
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[jg_4tzyv].innerHTML=jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[jg_4tzyv].childNodes[0].value;
if (jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[1].innerHTML.toLowerCase().indexOf("<input")>-1)
{
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[1].innerHTML=jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[1].childNodes[0].value;
}
if (jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[2].innerHTML.toLowerCase().indexOf("<input")>-1)
{
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[2].innerHTML=jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[2].childNodes[0].value;
}
if (jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[3].innerHTML.toLowerCase().indexOf("<input")>-1)
{
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[3].innerHTML=jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[3].childNodes[0].value;
}
if (jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[4].innerHTML.toLowerCase().indexOf("<input")>-1)
{
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[4].innerHTML=jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[4].childNodes[0].value;
}
jg_kz510(jg_jjfu4, jg_k2wsa, jg_btu93, jg_3kboz, jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[1].innerHTML, jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[2].innerHTML, jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[3].innerHTML, jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[4].innerHTML);
jg_2bmnx();
}
else
{
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[jg_4tzyv].innerHTML=jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[jg_4tzyv].childNodes[0].value;
jg_kz510(jg_jjfu4, jg_k2wsa, jg_btu93, jg_3kboz, jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[1].innerHTML, jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[2].innerHTML, jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[3].innerHTML, jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[4].innerHTML);
jg_2bmnx();
}
}
}
}
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[0].style.backgroundImage="url('view/image/field.png')";
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[0].style.color="#000000";
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[1].style.backgroundImage="url('view/image/field.png')";
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[1].style.color="#000000";
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[2].style.backgroundImage="url('view/image/field.png')";
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[2].style.color="#000000";
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[3].style.backgroundImage="url('view/image/field.png')";
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[3].style.color="#000000";
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[4].style.backgroundImage="url('view/image/field.png')";
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[4].style.color="#000000";
}
}
}
}
}
}
function jg_lep10(jg_9ep2q)
{
var save_when_done=0;
var jg_hya91=document.getElementsByTagName("input");
for (var jg_gchqy=0; jg_gchqy<jg_hya91.length; jg_gchqy++)
{
if (jg_hya91[jg_gchqy].type=="radio")
{
if (jg_hya91[jg_gchqy].id.indexOf("attribute-assignment-") !== -1)
{
jg_dm9rp=jg_hya91[jg_gchqy].parentNode.id;
if (jg_dm9rp==jg_9ep2q.parentNode.childNodes[0].id)
{
var jg_c95r7=4;
for (jg_4tzyv=1; jg_4tzyv<=jg_c95r7; jg_4tzyv++)
{
if(typeof jg_9ep2q.parentNode.childNodes[jg_4tzyv].childNodes[0]!='undefined')
{
if(typeof jg_9ep2q.parentNode.childNodes[jg_4tzyv].childNodes[0].type!='undefined')
{
save_when_done=1;
if (jg_9ep2q.parentNode.childNodes[jg_4tzyv].id!=jg_9ep2q.id)
{
jg_9ep2q.parentNode.childNodes[jg_4tzyv].innerHTML=jg_9ep2q.parentNode.childNodes[jg_4tzyv].childNodes[0].value;
switch (jg_4tzyv)
{
case 1:
jg_dncmv=jg_9ep2q.parentNode.childNodes[jg_4tzyv].innerHTML;
break;
case 2:
jg_2bl5i=jg_9ep2q.parentNode.childNodes[jg_4tzyv].innerHTML;
break;
case 3:
jg_gf2dj=jg_9ep2q.parentNode.childNodes[jg_4tzyv].innerHTML;
break;
case 4:
jg_7o1q5=jg_9ep2q.parentNode.childNodes[jg_4tzyv].innerHTML;
break;
default:
break;
}
}
else
{
switch (jg_4tzyv)
{
case 1:
jg_dncmv=jg_9ep2q.parentNode.childNodes[jg_4tzyv].childNodes[0].value;
break;
case 2:
jg_2bl5i=jg_9ep2q.parentNode.childNodes[jg_4tzyv].childNodes[0].value;
break;
case 3:
jg_gf2dj=jg_9ep2q.parentNode.childNodes[jg_4tzyv].childNodes[0].value;
break;
case 4:
jg_7o1q5=jg_9ep2q.parentNode.childNodes[jg_4tzyv].childNodes[0].value;
break;
default:
break;
}
}
}
}
}
if(save_when_done==1)
{
jg_e9msc(jg_yw1hu, jg_ehe7x, jg_xsbph, jg_ofeyk, jg_dncmv, jg_2bl5i, jg_gf2dj, jg_7o1q5);
if(jg_9ep2q.innerHTML.toLowerCase().indexOf("<input")>-1)
{
}
else
{
jg_asx3r();
}
}
else
{
}
}
else
{
for (jg_4tzyv=1; jg_4tzyv<=4; jg_4tzyv++)
{
if(typeof jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[jg_4tzyv].childNodes[0]!='undefined')
{
if(typeof jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[jg_4tzyv].childNodes[0].type!='undefined')
{
if(jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[jg_4tzyv].innerHTML.toLowerCase().indexOf("<input")>-1)
{
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[jg_4tzyv].innerHTML=jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[jg_4tzyv].childNodes[0].value;
if (jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[1].innerHTML.toLowerCase().indexOf("<input")>-1)
{
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[1].innerHTML=jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[1].childNodes[0].value;
}
if (jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[2].innerHTML.toLowerCase().indexOf("<input")>-1)
{
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[2].innerHTML=jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[2].childNodes[0].value;
}
if (jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[3].innerHTML.toLowerCase().indexOf("<input")>-1)
{
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[3].innerHTML=jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[3].childNodes[0].value;
}
if (jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[4].innerHTML.toLowerCase().indexOf("<input")>-1)
{
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[4].innerHTML=jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[4].childNodes[0].value;
}
jg_e9msc(jg_yw1hu, jg_ehe7x, jg_xsbph, jg_ofeyk, jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[1].innerHTML, jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[2].innerHTML, jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[3].innerHTML, jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[4].innerHTML);
jg_asx3r();
}
else
{
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[jg_4tzyv].innerHTML=jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[jg_4tzyv].childNodes[0].value;
jg_e9msc(jg_yw1hu, jg_ehe7x, jg_xsbph, jg_ofeyk, jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[1].innerHTML, jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[2].innerHTML, jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[3].innerHTML, jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[4].innerHTML);
jg_asx3r();
}
}
}
}
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[0].style.cssText ="width: 10px; color: #000000; background-image:url('view/image/field.png') !important; cursor: pointer";
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[1].style.cssText ="width: 10px; color: #000000; background-image:url('view/image/field.png') !important; cursor: pointer";
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[2].style.cssText ="width: 10px; color: #000000; background-image:url('view/image/field.png') !important; cursor: pointer";
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[3].style.cssText ="width: 10px; color: #000000; background-image:url('view/image/field.png') !important; cursor: pointer";
jg_hya91[jg_gchqy].parentNode.parentNode.childNodes[4].style.cssText ="width: auto; color: #000000; background-image:url('view/image/field.png') !important; cursor: pointer";
}
}
}
}
}
function jg_eoy6o()
{
jg_yw1hu="";
jg_ehe7x="";
jg_xsbph="";
jg_ofeyk="";
}
var jg_yw1hu="";
var jg_ehe7x="";
var jg_xsbph="";
var jg_ofeyk="";
function jg_tz5zm1(jg_gchqy)
{
jg_tz9bf(jg_gchqy);
var jg_z5e76=jg_gchqy.offsetWidth;
jg_wpltw="$!!!!$!!!$";
jg_dm9rp=jg_gchqy.innerHTML;
jg_vsiu1=jg_dm9rp.replace(/\<input /g, jg_wpltw);
jg_vsiu1=jg_vsiu1.replace(/\<INPUT /g, jg_wpltw);
if (jg_vsiu1.indexOf("$!!!!$!!!$")==-1)
{
jg_dm9rp="<input value=\"" + jg_dm9rp + "\" type=\"text\" style=\"width: "+jg_z5e76+"px;\"></input>";
}
else
{
}
return jg_dm9rp;
}
function jg_bi4un()
{
jg_jjfu4="";
jg_k2wsa="";
jg_btu93="";
jg_3kboz="";
}
var jg_jjfu4="";
var jg_k2wsa="";
var jg_btu93="";
var jg_3kboz="";
function jg_mc877d(jg_gchqy)
{
jg_ygnq9(jg_gchqy);
var jg_z5e76=jg_gchqy.offsetWidth;
jg_wpltw="$!!!!$!!!$";
jg_dm9rp=jg_gchqy.innerHTML;
jg_vsiu1=jg_dm9rp.replace(/\<input /g, jg_wpltw);
jg_vsiu1=jg_vsiu1.replace(/\<INPUT /g, jg_wpltw);
if (jg_vsiu1.indexOf("$!!!!$!!!$")==-1)
{
jg_dm9rp="<input value=\"" + jg_dm9rp + "\" type=\"text\" style=\"width: "+jg_z5e76+"px;\"></input>";
}
else
{
}
return jg_dm9rp;
}
function jg_zjuv8g(jg_gchqy)
{
if(jg_z4j10(jg_gchqy)==0)
{
jg_lep10(jg_gchqy);
jg_urxar7(jg_gchqy.parentNode);
for (jg_4tzyv=1; jg_4tzyv<=4; jg_4tzyv++)
{
jg_gchqy.parentNode.parentNode.childNodes[jg_4tzyv].innerHTML=jg_tz5zm1(jg_gchqy.parentNode.parentNode.childNodes[jg_4tzyv]);
}
}
}
function jg_i10pdt(jg_gchqy)
{
if(jg_z4j10(jg_gchqy)==0)
{
jg_rut107(jg_gchqy);
jg_cl10yy(jg_gchqy.parentNode);
for (jg_4tzyv=1; jg_4tzyv<=4; jg_4tzyv++)
{
jg_gchqy.parentNode.parentNode.childNodes[jg_4tzyv].innerHTML=jg_mc877d(jg_gchqy.parentNode.parentNode.childNodes[jg_4tzyv]);
}
}
}
function jg_z4j10(jg_gchqy)
{
for (jg_4tzyv=1; jg_4tzyv<=4; jg_4tzyv++)
{
if(jg_gchqy.parentNode.parentNode.childNodes[jg_4tzyv].innerHTML.toLowerCase().indexOf("<input")>-1){return 1;}else{}
}
return 0;
}
function jg_6tizjd()
{
try
{
var jg_hya91=document.getElementsByTagName("input");
for (var jg_gchqy=0; jg_gchqy<jg_hya91.length; jg_gchqy++)
{
if (jg_hya91[jg_gchqy].type=="radio")
{
if (jg_hya91[jg_gchqy].checked==true)
{
jg_zjuv8g(jg_hya91[jg_gchqy]);
}
}
}
}
catch(jg_u3b9x)
{
alert('Error: ' + jg_u3b9x.toString());
}
}
function jg_44bzt1()
{
try
{
var jg_hya91=document.getElementsByTagName("input");
for (var jg_gchqy=0; jg_gchqy<jg_hya91.length; jg_gchqy++)
{
if (jg_hya91[jg_gchqy].type=="radio")
{
if (jg_hya91[jg_gchqy].checked==true)
{
jg_i10pdt(jg_hya91[jg_gchqy]);
}
}
}
}
catch(jg_u3b9x)
{
alert('Error: ' + jg_u3b9x.toString());
}
}
function jg_e8q101(jg_gchqy)
{
jg_tz9bf(jg_gchqy.parentNode);
jg_ogq10="";
jg_u8x5d="";
jg_plqo5="";
jg_y10ru="";
for (jg_4tzyv=1; jg_4tzyv<=4; jg_4tzyv++)
{
if (jg_4tzyv==1)
{
jg_ogq10=jg_yw1hu;
}
if (jg_4tzyv==2)
{
jg_u8x5d=jg_ehe7x;
}
if (jg_4tzyv==3)
{
jg_plqo5=jg_xsbph;
}
if (jg_4tzyv==4)
{
jg_y10ru=jg_ofeyk;
}
$this_response=jg_xx4sg(jg_ogq10, jg_u8x5d, jg_plqo5, jg_y10ru);
jg_qwj6ug();
}
}
function jg_2l2zid(jg_gchqy)
{
jg_ygnq9(jg_gchqy.parentNode);
jg_jgx2y="";
jg_t2xvj="";
jg_6sqq1="";
jg_yeaxu="";
for (jg_4tzyv=1; jg_4tzyv<=4; jg_4tzyv++)
{
if (jg_4tzyv==1)
{
jg_jgx2y=jg_jjfu4;
}
if (jg_4tzyv==2)
{
jg_t2xvj=jg_k2wsa;
}
if (jg_4tzyv==3)
{
jg_6sqq1=jg_btu93;
}
if (jg_4tzyv==4)
{
jg_yeaxu=jg_3kboz;
}
}
if (window.XMLHttpRequest)
{
jg_fayib=new XMLHttpRequest();
}
else
{
jg_fayib=new ActiveXObject("Microsoft.XMLHTTP");
}
jg_fayib.onreadystatechange=function()
{
if (jg_fayib.readyState==4&&jg_fayib.status==200)
{
jg_2bmnx();
}
}
jg_tturk(jg_jgx2y, jg_t2xvj, jg_6sqq1, jg_yeaxu);
}
function jg_tz9bf(jg_gchqy)
{
if(typeof jg_gchqy.parentNode.childNodes[1].childNodes[0]=='undefined')
{
jg_yw1hu=jg_gchqy.parentNode.childNodes[1].innerHTML
}
else
{
if(typeof jg_gchqy.parentNode.childNodes[1].childNodes[0].type!='undefined')
{
jg_yw1hu=jg_gchqy.parentNode.childNodes[1].childNodes[0].value;
}
else
{
jg_yw1hu= jg_gchqy.parentNode.childNodes[1].innerHTML;
}
}
if(typeof jg_gchqy.parentNode.childNodes[2].childNodes[0]=='undefined')
{
jg_ehe7x=jg_gchqy.parentNode.childNodes[2].innerHTML
}
else
{
if(typeof jg_gchqy.parentNode.childNodes[2].childNodes[0].type!='undefined')
{
jg_ehe7x=jg_gchqy.parentNode.childNodes[2].childNodes[0].value;
}
else
{
jg_ehe7x=jg_gchqy.parentNode.childNodes[2].innerHTML;
}
}
if(typeof jg_gchqy.parentNode.childNodes[3].childNodes[0]=='undefined')
{
jg_xsbph=jg_gchqy.parentNode.childNodes[3].innerHTML
}
else
{
if(typeof jg_gchqy.parentNode.childNodes[3].childNodes[0].type!='undefined')
{
jg_xsbph=jg_gchqy.parentNode.childNodes[3].childNodes[0].value;
}
else
{
jg_xsbph=jg_gchqy.parentNode.childNodes[3].innerHTML;
}
}
if(typeof jg_gchqy.parentNode.childNodes[4].childNodes[0]=='undefined')
{
jg_ofeyk=jg_gchqy.parentNode.childNodes[4].innerHTML
}
else
{
if(typeof jg_gchqy.parentNode.childNodes[4].childNodes[0].type!='undefined')
{
jg_ofeyk=jg_gchqy.parentNode.childNodes[4].childNodes[0].value;
}
else
{
jg_ofeyk=jg_gchqy.parentNode.childNodes[4].innerHTML;
}
}
}
function jg_ygnq9(jg_gchqy)
{
if(typeof jg_gchqy.parentNode.childNodes[1].childNodes[0]=='undefined')
{
jg_jjfu4=jg_gchqy.parentNode.childNodes[1].innerHTML
}
else
{
if(typeof jg_gchqy.parentNode.childNodes[1].childNodes[0].type!='undefined')
{
jg_jjfu4=jg_gchqy.parentNode.childNodes[1].childNodes[0].value;
}
else
{
jg_jjfu4= jg_gchqy.parentNode.childNodes[1].innerHTML;
}
}
if(typeof jg_gchqy.parentNode.childNodes[2].childNodes[0]=='undefined')
{
jg_k2wsa=jg_gchqy.parentNode.childNodes[2].innerHTML;
}
else
{
if(typeof jg_gchqy.parentNode.childNodes[2].childNodes[0].type!='undefined')
{
jg_k2wsa=jg_gchqy.parentNode.childNodes[2].childNodes[0].value;
}
else
{
jg_k2wsa= jg_gchqy.parentNode.childNodes[2].innerHTML;
}
}
if(typeof jg_gchqy.parentNode.childNodes[3].childNodes[0]=='undefined')
{
jg_btu93=jg_gchqy.parentNode.childNodes[3].innerHTML
}
else
{
if(typeof jg_gchqy.parentNode.childNodes[3].childNodes[0].type!='undefined')
{
jg_btu93=jg_gchqy.parentNode.childNodes[3].childNodes[0].value;
}
else
{
jg_btu93=jg_gchqy.parentNode.childNodes[3].innerHTML;
}
}
if(typeof jg_gchqy.parentNode.childNodes[4].childNodes[0]=='undefined')
{
jg_3kboz=jg_gchqy.parentNode.childNodes[4].innerHTML
}
else
{
if(typeof jg_gchqy.parentNode.childNodes[4].childNodes[0].type!='undefined')
{
jg_3kboz=jg_gchqy.parentNode.childNodes[4].childNodes[0].value;
}
else
{
jg_3kboz=jg_gchqy.parentNode.childNodes[4].innerHTML;
}
}
}
function jg_lax9d()
{
jg_baki5="";
jg_7ezr5="";
jg_101zz="";
jg_slgzj="";
}
function jg_qwj6ug()
{
if(document.getElementById("jg_67d52r"))
{
document.getElementById("jg_67d52r").innerHTML="<img style=\"vertical-align: middle; margin-left: 0px; margin-right: 0px;\" src=\"<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/ajax-loader-circles-16x16.gif\" lowsrc=\"<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/ajax-loader-circles-16x16.gif\"/>";
}
jg_lax9d();
jg_np10u();
}
function jg_5t107()
{
jg_xf4dn="";
jg_j7rzx="";
jg_4h4ql="";
jg_yinz1="";
}
function jg_b711zm()
{
if(document.getElementById("button_refresh_custom_product_fields_list"))
{
document.getElementById("button_refresh_custom_product_fields_list").innerHTML="<img style=\"vertical-align: middle; margin-left: 0px; margin-right: 0px;\" src=\"<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/ajax-loader-circles-16x16.gif\" lowsrc=\"<?php echo THIS_IMAGE_URL ?>data/google-merchant-center-feed/ajax-loader-circles-16x16.gif\"/>";
}
jg_5t107();
jg_2bmnx();
}
</script>
</div>
<?php 
switch (VERSION)
{
case (VERSION=='1.4.7'||VERSION=='1.4.8'||VERSION=='1.4.9'||VERSION=='1.4.9.1'||VERSION=='1.4.9.2'||VERSION=='1.4.9.3'||VERSION=='1.4.9.4'||VERSION=='1.4.9.5'||VERSION=='1.4.9.6'):
break;
case (VERSION=='1.5.0'||VERSION=='1.5.0.1'||VERSION=='1.5.0.2'||VERSION=='1.5.0.3'||VERSION=='1.5.0.4'||VERSION=='1.5.0.5'||VERSION=='1.5.1'||VERSION=='1.5.1.1'||VERSION=='1.5.1.2'||VERSION=='1.5.1.3'||VERSION=='1.5.2'||VERSION=='1.5.2.1'||VERSION=='1.5.3'||VERSION=='1.5.3.1'||VERSION=='1.5.4'||VERSION=='1.5.4.1'||VERSION=='1.5.5'||VERSION=='1.5.5.1'||VERSION=='1.5.6'||VERSION=='1.5.6.1'):
echo "</div>";
break;
default:
break;
}
?>
<?php
function jg_fiofw()
{
$jg_mmm7a=mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
mysql_set_charset('utf8');
if (function_exists('mb_language')) {
mb_language('uni');
mb_internal_encoding('UTF-8');
}
mysql_query("SET NAMES 'utf8'", $jg_mmm7a);
mysql_query("SET CHARACTER SET utf8", $jg_mmm7a);
mysql_query("SET CHARACTER_SET_CONNECTION=utf8", $jg_mmm7a);
mysql_query("SET CHARACTER_SET_RESULTS=utf8", $jg_mmm7a);
mysql_query("SET SQL_MODE=''", $jg_mmm7a);
mysql_select_db(DB_DATABASE, $jg_mmm7a) or die (mysql_error());
$jg_9jhtu=0;
$jg_dm9rp='';
$jg_xgtbf=str_replace('\\', '\\\\', DIR_APPLICATION);
$jg_qq9fr=mysql_query("SELECT * FROM ".DB_PREFIX."setting WHERE ".DB_PREFIX."setting.group='google_merchant_center_feed' AND ".DB_PREFIX."setting.key='opencart_admin_directory'", $jg_mmm7a) or die (mysql_error());
while($jg_e10nu=mysql_fetch_array($jg_qq9fr))
{
$jg_dm9rp=$jg_e10nu["value"];
$jg_9jhtu+=1;
}
if($jg_9jhtu>1)
{
$jg_qq9fr=mysql_query("DELETE FROM ".DB_PREFIX."setting WHERE ".DB_PREFIX."setting.group='google_merchant_center_feed' AND `key`='opencart_admin_directory'", $jg_mmm7a) or die (mysql_error());
$jg_9jhtu=0;
}
if($jg_9jhtu==0)
{
$jg_qq9fr=mysql_query("INSERT INTO ".DB_PREFIX."setting SET ".DB_PREFIX."setting.group='google_merchant_center_feed', `key`='opencart_admin_directory', `value`='".$jg_xgtbf."'", $jg_mmm7a) or die (mysql_error());
}
else
{
if($jg_dm9rp!==$jg_xgtbf)
{
$jg_qq9fr=mysql_query("UPDATE ".DB_PREFIX."setting SET `value`='".$jg_xgtbf."' WHERE ".DB_PREFIX."setting.group='google_merchant_center_feed' AND ".DB_PREFIX."setting.key='opencart_admin_directory'", $jg_mmm7a) or die (mysql_error());
}
}
}
function jg_q561v()
{
$jg_mmm7a=mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
mysql_set_charset('utf8');
if (function_exists('mb_language')) {
mb_language('uni');
mb_internal_encoding('UTF-8');
}
mysql_query("SET NAMES 'utf8'", $jg_mmm7a);
mysql_query("SET CHARACTER SET utf8", $jg_mmm7a);
mysql_query("SET CHARACTER_SET_CONNECTION=utf8", $jg_mmm7a);
mysql_query("SET CHARACTER_SET_RESULTS=utf8", $jg_mmm7a);
mysql_query("SET SQL_MODE=''", $jg_mmm7a);
mysql_select_db(DB_DATABASE, $jg_mmm7a) or die (mysql_error());
$jg_9jhtu=0;
$jg_dm9rp='';
$jg_xgtbf=str_replace('\\', '\\\\', DIR_LANGUAGE);
$jg_qq9fr=mysql_query("SELECT * FROM ".DB_PREFIX."setting WHERE ".DB_PREFIX."setting.group='google_merchant_center_feed' AND ".DB_PREFIX."setting.key='opencart_admin_language_directory'", $jg_mmm7a) or die (mysql_error());
while($jg_e10nu=mysql_fetch_array($jg_qq9fr))
{
$jg_dm9rp=$jg_e10nu["value"];
$jg_9jhtu+=1;
}
if($jg_9jhtu>1)
{
$jg_qq9fr=mysql_query("DELETE FROM ".DB_PREFIX."setting WHERE ".DB_PREFIX."setting.group='google_merchant_center_feed' AND `key`='opencart_admin_language_directory'", $jg_mmm7a) or die (mysql_error());
$jg_9jhtu=0;
}
if($jg_9jhtu==0)
{
$jg_qq9fr=mysql_query("INSERT INTO ".DB_PREFIX."setting SET ".DB_PREFIX."setting.group='google_merchant_center_feed', `key`='opencart_admin_language_directory', `value`='".$jg_xgtbf."'", $jg_mmm7a) or die (mysql_error());
}
else
{
if($jg_dm9rp!==$jg_xgtbf)
{
$jg_qq9fr=mysql_query("UPDATE ".DB_PREFIX."setting SET `value`='".$jg_xgtbf."' WHERE ".DB_PREFIX."setting.group='google_merchant_center_feed' AND ".DB_PREFIX."setting.key='opencart_admin_language_directory'", $jg_mmm7a) or die (mysql_error());
}
}
}
function jg_lj610()
{
$jg_mmm7a=mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
mysql_set_charset('utf8');
if (function_exists('mb_language')) {
mb_language('uni');
mb_internal_encoding('UTF-8');
}
mysql_query("SET NAMES 'utf8'", $jg_mmm7a);
mysql_query("SET CHARACTER SET utf8", $jg_mmm7a);
mysql_query("SET CHARACTER_SET_CONNECTION=utf8", $jg_mmm7a);
mysql_query("SET CHARACTER_SET_RESULTS=utf8", $jg_mmm7a);
mysql_query("SET SQL_MODE=''", $jg_mmm7a);
mysql_select_db(DB_DATABASE, $jg_mmm7a) or die (mysql_error());
$jg_9jhtu=0;
$jg_dm9rp='';
$jg_xn10j=VERSION;
$jg_qq9fr=mysql_query("SELECT * FROM ".DB_PREFIX."setting WHERE ".DB_PREFIX."setting.group='google_merchant_center_feed' AND ".DB_PREFIX."setting.key='opencart_version'", $jg_mmm7a) or die (mysql_error());
while($jg_e10nu=mysql_fetch_array($jg_qq9fr))
{
$jg_dm9rp=$jg_e10nu["value"];
$jg_9jhtu+=1;
}
if($jg_9jhtu>1)
{
$jg_qq9fr=mysql_query("DELETE FROM ".DB_PREFIX."setting WHERE ".DB_PREFIX."setting.group='google_merchant_center_feed' AND `key`='opencart_version'", $jg_mmm7a) or die (mysql_error());
$jg_9jhtu=0;
}
if($jg_9jhtu==0)
{
$jg_qq9fr=mysql_query("INSERT INTO ".DB_PREFIX."setting SET ".DB_PREFIX."setting.group='google_merchant_center_feed', `key`='opencart_version', `value`='".$jg_xn10j."'", $jg_mmm7a) or die (mysql_error());
}
else
{
if($jg_dm9rp!==$jg_xn10j)
{
$jg_qq9fr=mysql_query("UPDATE ".DB_PREFIX."setting SET `value`='".$jg_xn10j."' WHERE ".DB_PREFIX."setting.group='google_merchant_center_feed' AND ".DB_PREFIX."setting.key='opencart_version'", $jg_mmm7a) or die (mysql_error());
}
}
}
function convert_to_utf8($this_content) {
if (function_exists('mb_language')) {
if(!mb_check_encoding($this_content, 'UTF-8')
OR !($this_content === mb_convert_encoding(mb_convert_encoding($this_content, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32'))) {
$this_content=mb_convert_encoding($this_content, 'UTF-8');
if (mb_check_encoding($this_content, 'UTF-8')) {
} else {
}
}
}
return $this_content;
}
?>
<?php echo $footer; ?>
