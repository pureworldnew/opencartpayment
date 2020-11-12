<ul id="menu">

			<?php if (empty($is_pos_user)) { ?>
  <li id="dashboard"><a href="<?php echo $home; ?>"><i class="fa fa-dashboard fa-fw"></i> <span><?php echo $text_dashboard; ?></span></a></li>
<li id="pos"><a class="parent"><i class="fa fa-desktop fa-fw"></i> <span>Point Of Sale</span></a>
					<ul>
						<li id="pos_console"><a href="<?php echo $pos_console; ?>">POS Console</a></li>
						<li id="pos_settings"><a href="<?php echo $pos_settings; ?>">POS Settings</a></li>
						<li id="pos_stock_manager"><a href="<?php echo $pos_stock_manager; ?>">POS Stock Manager</a></li>
						<li><a class="parent">POS Report</a>
							<ul>
								<li><a id="pos_payment_summary" onclick="var browser_time = (new Date()).getTime(); location='<?php echo $pos_payment_summary; ?>&browser_time='+browser_time;">Payment Summary</a></li>
								<li><a id="pos_payment_details" onclick="var browser_time = (new Date()).getTime(); location='<?php echo $pos_payment_details; ?>&browser_time='+browser_time;">Payment Details</a></li>
								<li><a id="pos_commission_summary" onclick="var browser_time = (new Date()).getTime(); location='<?php echo $pos_commission_summary; ?>&browser_time='+browser_time;">Commission Summary</a></li>
								<li><a id="pos_commission_details" onclick="var browser_time = (new Date()).getTime(); location='<?php echo $pos_commission_details; ?>&browser_time='+browser_time;">Commission Details</a></li>
								<li><a onclick="promptReportDates();">Export Sales Report</a></li>
								<li><a href="<?php echo $pos_export_report_stock; ?>">Export Stock Report</a></li>
							</ul>
						</li>
						<li id="pos_extended_product"><a href="<?php echo $pos_extended_product; ?>">POS Product Extension</a></li>
						<li id="pos_extended_product"><a href="<?php echo $pos_clean_tables; ?>">Clean POS Tables</a></li>
						<li id="pos_extended_product"><a href="<?php echo $pos_sn_manager; ?>">Serial No. Manager</a></li>
					</ul>
				</li>
				<script type="text/javascript">
					function promptReportDates() {
						var html = '<div id="export_sales_report_dialog" title="Select Date Range" style="overflow: hidden; ">';
						html += '<form class="form-horizontal" role="form">';
						html += '<div class="form-group">';
						html += '<label class="control-label col-sm-4" for="startDate">Start Date:</label>';
						html += '<div class="col-sm-6"><input type="text" class="form-control" id="startDate" data-format="YYYY-MM-DD" data-date-format="YYYY-MM-DD"></div>';
						html += '</div>';
						html += '<div class="form-group">';
						html += '<label class="control-label col-sm-4" for="endDate">End Date:</label>';
						html += '<div class="col-sm-6"><input type="text" class="form-control" id="endDate" data-format="YYYY-MM-DD" data-date-format="YYYY-MM-DD"></div>';
						html += '</div>';
						html += '<div class="form-group">';
						html += '<div class="col-sm-offset-4 col-sm-6"><button type="button" id="exportButton" onclick="exportSalesReport()" class="btn btn-default">Export</button></div>'
						html += '</div></form>';
						html += '</div>';
						$(html).dialog({
							modal: true,
							width: 420,
							height: 220,
							buttons: {},
							close: function() {
								$('#export_sales_report_dialog').remove();
							}
						});
						var today = formatDate(new Date());
						$('#startDate').val(today);
						$('#endDate').val(today);
						$('#startDate').datetimepicker({pickTime: false});
						$('#endDate').datetimepicker({pickTime: false});
						$('#exportButton').focus();
					};
					function exportSalesReport() {
						var startDate = $('#startDate').val();
						var endDate = $('#endDate').val();
						$('#export_sales_report_dialog').dialog('close');
						$('#export_sales_report_dialog').remove();
						location = $('<div/>').html('<?php echo $pos_export_report_sales; ?>').text() + '&startDate=' + startDate + '&endDate=' + endDate;
					};
					function formatDate(date) {
						var month = date.getMonth()+1;
						var day = date.getDate();
						month = ( month < 10 ? "0" : "" ) + month;
						day = ( day < 10 ? "0" : "" ) + day;
						
						return  date.getFullYear() + '-' + month + '-' + day;
					};
				</script>
				

    <li id="rma"><a class="parent"><i class="fa fa-cubes"></i> <span><?php echo $text_rma; ?></span></a>
      <ul>
        <li><a href="<?php echo $rma_rma ?>"><?php echo $text_manage_rma; ?></a></li>
        <li><a href="<?php echo $rma_status ?>"><?php echo $text_manage_rma_status; ?></a></li>
        <li><a href="<?php echo $rma_reason ?>"><?php echo $text_manage_rma_reason; ?></a></li>
      </ul>
    </li>
            
  <li id="catalog"><a class="parent"><i class="fa fa-tags fa-fw"></i> <span><?php echo $text_catalog; ?></span></a>
    <ul>
      <li><a href="<?php echo $category; ?>"><?php echo $text_category; ?></a></li>
      <li><a href="<?php echo $category_seo_url; ?>"><?php echo $text_category_seo_url; ?></a></li>
      <li><a href="<?php echo $product; ?>"><?php echo $text_product; ?></a></li>
<li><a class="parent">SEO</a>
			<ul>			
			<li><a href="<?php echo $seopack; ?>"><?php echo $text_seopack; ?></a></li>
			<li><a href="<?php echo $seoimages; ?>"><?php echo $text_seoimages; ?></a></li>
			<li>
				<?php if (file_exists(DIR_APPLICATION.'controller/catalog/autolinks.php')) { ?>
				<a href="<?php echo $autolinks; ?>"><?php echo $text_autolinks; ?></a>
				<?php } else { ?>
				<a onclick="alert('Auto Internal Links is not installed!\nYou can purchase Auto Internal Links from\n http://www.opencart.com/index.php?route=extension/extension/info&extension_id=5650\nor you can purchase the whole Opencart SEO Pack PRO:\n http://www.opencart.com/index.php?route=extension/extension/info&extension_id=6182');" class="button"><?php echo $text_autolinks; ?></a>
				<?php } ?>
			</li>
			<li>
				<?php if (file_exists(DIR_APPLICATION.'controller/catalog/canonicals.php')) { ?>
				<a href="<?php echo $canonicals; ?>"><?php echo $text_canonicals; ?></a>
				<?php } else { ?>
				<a onclick="alert('Canonical Links is not installed!\nYou can purchase Opencart SEO Pack PRO from:\n http://www.opencart.com/index.php?route=extension/extension/info&extension_id=6182');" class="button"><?php echo $text_canonicals; ?></a>
				<?php } ?>
			</li>
			<li>
				<?php if (file_exists(DIR_APPLICATION.'controller/catalog/mlseo.php')) { ?>
				<a href="<?php echo $mlseo; ?>"><?php echo $text_mlseo; ?></a>
				<?php } else { ?>
				<a onclick="alert('Multi-Language SEO is not installed!\nYou can purchase Opencart SEO Pack PRO from:\n http://www.opencart.com/index.php?route=extension/extension/info&extension_id=6182');" class="button"><?php echo $text_mlseo; ?></a>
				<?php } ?>
			</li>
			<li>
				<?php if (file_exists(DIR_APPLICATION.'controller/catalog/richsnippets.php')) { ?>
				<a href="<?php echo $richsnippets; ?>"><?php echo $text_richsnippets; ?></a>
				<?php } else { ?>
				<a onclick="alert('Rich Snippets is not installed!\nYou can purchase Opencart SEO Pack PRO from:\n http://www.opencart.com/index.php?route=extension/extension/info&extension_id=6182');" class="button"><?php echo $text_richsnippets; ?></a>
				<?php } ?>
			</li>
			<li>
				<?php if (file_exists(DIR_APPLICATION.'controller/catalog/seopagination.php')) { ?>
				<a href="<?php echo $seopagination; ?>"><?php echo $text_seopagination; ?></a>
				<?php } else { ?>
				<a onclick="alert('SEO Pagination is not installed!\nYou can purchase Opencart SEO Pack PRO from:\n http://www.opencart.com/index.php?route=extension/extension/info&extension_id=6182');" class="button"><?php echo $text_seopagination; ?></a>
				<?php } ?>
			</li>
			<li>
				<?php if (file_exists(DIR_APPLICATION.'controller/catalog/redirect.php')) { ?>
				<a href="<?php echo $redirect; ?>"><?php echo $text_redirect; ?></a>
				<?php } else { ?>
				<a onclick="alert('SEO Redirector is not installed!\nYou can purchase Opencart SEO Pack PRO from:\n http://www.opencart.com/index.php?route=extension/extension/info&extension_id=6182');" class="button"><?php echo $text_redirect; ?></a>
				<?php } ?>
			</li>
			<li>
				<?php if (file_exists(DIR_APPLICATION.'controller/catalog/not_found_report.php')) { ?>
				<a href="<?php echo $not_found_report; ?>"><?php echo $text_not_found_report; ?></a>
				<?php } else { ?>
				<a onclick="alert('404s Report is not installed!\nYou can purchase Opencart SEO Pack PRO from:\n http://www.opencart.com/index.php?route=extension/extension/info&extension_id=6182');" class="button"><?php echo $text_not_found_report; ?></a>
				<?php } ?>
			</li>
			<li>
				<?php if (file_exists(DIR_APPLICATION.'controller/catalog/seoreplacer.php')) { ?>
				<a href="<?php echo $seoreplacer; ?>"><?php echo $text_seoreplacer; ?></a>
				<?php } else { ?>
				<a onclick="alert('Extended SEO is not installed!\nYou can purchase Opencart SEO Pack PRO from:\n http://www.opencart.com/index.php?route=extension/extension/info&extension_id=6182');" class="button"><?php echo $text_seoreplacer; ?></a>
				<?php } ?>
			</li>
			<li>
				<?php if (file_exists(DIR_APPLICATION.'controller/catalog/extendedseo.php')) { ?>
				<a href="<?php echo $extendedseo; ?>"><?php echo $text_extendedseo; ?></a>
				<?php } else { ?>
				<a onclick="alert('Extended SEO is not installed!\nYou can purchase Opencart SEO Pack PRO from:\n http://www.opencart.com/index.php?route=extension/extension/info&extension_id=6182');" class="button"><?php echo $text_extendedseo; ?></a>
				<?php } ?>
			</li>
			<li>
				<?php if (file_exists(DIR_APPLICATION.'controller/catalog/seoeditor.php')) { ?>
				<a href="<?php echo $seoeditor; ?>"><?php echo $text_seoeditor; ?></a>
				<?php } else { ?>
				<a onclick="alert('Advanced SEO Editor is not installed!\nYou can purchase Advanced SEO Editor from\n http://www.opencart.com/index.php?route=extension/extension/info&extension_id=6183\nor you can purchase the whole Opencart SEO Pack PRO:\n http://www.opencart.com/index.php?route=extension/extension/info&extension_id=6182');" class="button"><?php echo $text_seoeditor; ?></a>
				<?php } ?>
			</li>
			<li><a href="<?php echo $seoreport; ?>"><?php echo $text_seoreport; ?></a></li>
			<li><a href="<?php echo $about; ?>"><?php echo $text_about; ?></a></li>
			</ul>
			</li>

			<li><a class="parent"><?php echo 'QR Code(s)'; ?></a>
              <ul>
                  <li><a href="<?php echo $qr_code; ?>"><?php echo $text_qr_code; ?></a></li>
                  <li><a href="<?php echo $qr_code_csv; ?>"><?php echo $text_qr_code_csv; ?></a></li>
            
              </ul>
          </li> 
		  
                
                
      <li><a href="<?php echo $recurring; ?>"><?php echo $text_recurring; ?></a></li>
      <li><a href="<?php echo $filter; ?>"><?php echo $text_filter; ?></a></li>
      <li><a class="parent"><?php echo $text_attribute; ?></a>

        <ul>
          <li><a href="<?php echo $attribute; ?>"><?php echo $text_attribute; ?></a></li>
          <li><a href="<?php echo $attribute_group; ?>"><?php echo $text_attribute_group; ?></a></li>
        </ul>
      </li>
      <li><a href="<?php echo $option; ?>"><?php echo $text_option; ?></a></li>
      <li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>

<li><a href="<?php echo $adv_supplier; ?>"><?php echo $text_adv_supplier; ?></a></li>
            
      <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
      <li><a href="<?php echo $review; ?>"><?php echo $text_review; ?></a></li>
      <li><a href="<?php echo $information; ?>"><?php echo $text_information; ?></a></li>

                <li><a href="<?php echo $redirects; ?>"><?php echo $text_redirects; ?></a></li>
                
      <li><a href="<?php echo $lookbook; ?>"><?php echo $text_lookbook; ?></a></li>
      <li><a href="<?php echo $qa; ?>"><?php echo $text_qa; ?></a></li>
      <li><a href="<?php echo $product_concate; ?>"><?php echo $text_concat_product; ?></a></li>
      <li><a href="<?php echo $marketprice; ?>"><?php echo $text_category_price_updater; ?></a></li>
      <li><a href="<?php echo $new_grouping_system; ?>"><?php echo $text_new_grouping_system; ?></a></li>
      <li><a href="<?php echo $grouped_product; ?>"><?php echo $text_grouped_product; ?></a></li>
      <li><a href="<?php echo $grouped_product_sort; ?>"><?php echo $text_grouped_product_sort; ?></a></li>
      <li><a href="<?php echo $grouped_batch_sort; ?>"><?php echo $text_grouped_batch_sort; ?></a></li>
      <li><a href="<?php echo $unit_conversion; ?>"><?php echo $text_unit_conversion; ?></a></li>
      <!--<li><a href="<?php echo $qa; ?>"><?php echo $text_qa; ?></a></li>-->
    </ul>
  </li>
  <li id="extension"><a class="parent"><i class="fa fa-puzzle-piece fa-fw"></i> <span><?php echo $text_extension; ?></span></a>
    <ul>

        <li><a class="parent">Advanced Menu V4</a>
        <ul>
          <li><a href="<?php echo $advsettings; ?>">Settings</a></li>
          <li><a href="<?php echo $advfilters; ?>">Filters</a></li>
          <li><a href="<?php echo $advseo; ?>">Seo Pack</a></li>
         </ul>
      </li>
      
      <li><a href="<?php echo $installer; ?>"><?php echo $text_installer; ?></a></li>
      <li><a href="<?php echo $modification; ?>"><?php echo $text_modification; ?></a></li>
<?php /* //karapuz (ka_extensions.ocmod.xml)  */?>
				<li><a href="<?php echo $ka_extensions; ?>"><?php echo $this->t('Ka Extensions'); ?></a></li>
<?php /* ///karapuz (ka_extensions.ocmod.xml)  */?>

	<li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
			
      <li><a href="<?php echo $analytics; ?>"><?php echo $text_analytics; ?></a></li>
      <li><a href="<?php echo $captcha; ?>"><?php echo $text_captcha; ?></a></li>
<?php if (isset($module_emailtemplate)) { ?><li><a href="<?php echo $module_emailtemplate; ?>"><?php echo $text_emailtemplate; ?></a></li><?php } ?>
      <li><a href="<?php echo $feed; ?>"><?php echo $text_feed; ?></a></li>

			<li><a href="<?php echo $link_univimportpro; ?>"><img style="vertical-align:top" src="view/universal_import/img/icon.png"/> <?php echo $text_univimportpro; ?></a></li>
			

			<li><a href="<?php echo $link_pdfinvpro; ?>"><img style="vertical-align:top" src="view/pdf_invoice_pro/img/icon.png"/> <?php echo $text_pdfinvpro; ?></a></li>
			
      <li><a href="<?php echo $fraud; ?>"><?php echo $text_fraud; ?></a></li>
      <li><a href="<?php echo $module; ?>"><?php echo $text_module; ?></a></li>
      <li><a href="<?php echo $payment; ?>"><?php echo $text_payment; ?></a></li>
      <li><a href="<?php echo $shipping; ?>"><?php echo $text_shipping; ?></a></li>
      <li><a href="<?php echo $total; ?>"><?php echo $text_total; ?></a></li>
      <?php if ($openbay_show_menu == 1) { ?>
      <li><a class="parent"><?php echo $text_openbay_extension; ?></a>
        <ul>
          <li><a href="<?php echo $openbay_link_extension; ?>"><?php echo $text_openbay_dashboard; ?></a></li>
          <li><a href="<?php echo $openbay_link_orders; ?>"><?php echo $text_openbay_orders; ?></a></li>
          <li><a href="<?php echo $openbay_link_items; ?>"><?php echo $text_openbay_items; ?></a></li>
          <?php if ($openbay_markets['ebay'] == 1) { ?>
          <li><a class="parent"><?php echo $text_openbay_ebay; ?></a>
            <ul>
              <li><a href="<?php echo $openbay_link_ebay; ?>"><?php echo $text_openbay_dashboard; ?></a></li>
              <li><a href="<?php echo $openbay_link_ebay_settings; ?>"><?php echo $text_openbay_settings; ?></a></li>
              <li><a href="<?php echo $openbay_link_ebay_links; ?>"><?php echo $text_openbay_links; ?></a></li>
              <li><a href="<?php echo $openbay_link_ebay_orderimport; ?>"><?php echo $text_openbay_order_import; ?></a></li>
            </ul>
          </li>
          <?php } ?>
          <?php if ($openbay_markets['amazon'] == 1) { ?>
          <li><a class="parent"><?php echo $text_openbay_amazon; ?></a>
            <ul>
              <li><a href="<?php echo $openbay_link_amazon; ?>"><?php echo $text_openbay_dashboard; ?></a></li>
              <li><a href="<?php echo $openbay_link_amazon_settings; ?>"><?php echo $text_openbay_settings; ?></a></li>
              <li><a href="<?php echo $openbay_link_amazon_links; ?>"><?php echo $text_openbay_links; ?></a></li>
            </ul>
          </li>
          <?php } ?>
          <?php if ($openbay_markets['amazonus'] == 1) { ?>
          <li><a class="parent"><?php echo $text_openbay_amazonus; ?></a>
            <ul>
              <li><a href="<?php echo $openbay_link_amazonus; ?>"><?php echo $text_openbay_dashboard; ?></a></li>
              <li><a href="<?php echo $openbay_link_amazonus_settings; ?>"><?php echo $text_openbay_settings; ?></a></li>
              <li><a href="<?php echo $openbay_link_amazonus_links; ?>"><?php echo $text_openbay_links; ?></a></li>
            </ul>
          </li>
          <?php } ?>
          <?php if ($openbay_markets['etsy'] == 1) { ?>
          <li><a class="parent"><?php echo $text_openbay_etsy; ?></a>
            <ul>
              <li><a href="<?php echo $openbay_link_etsy; ?>"><?php echo $text_openbay_dashboard; ?></a></li>
              <li><a href="<?php echo $openbay_link_etsy_settings; ?>"><?php echo $text_openbay_settings; ?></a></li>
              <li><a href="<?php echo $openbay_link_etsy_links; ?>"><?php echo $text_openbay_links; ?></a></li>
            </ul>
          </li>
          <?php } ?>
        </ul>
      </li>
      <?php } ?>
    </ul>
  </li>
  <li id="design"><a class="parent"><i class="fa fa-television fa-fw"></i> <span><?php echo $text_design; ?></span></a>
    <ul>
      <li><a href="<?php echo $layout; ?>"><?php echo $text_layout; ?></a></li>
      <li><a href="<?php echo $banner; ?>"><?php echo $text_banner; ?></a></li>
    </ul>
  </li>
  <li id="sale"><a class="parent"><i class="fa fa-shopping-cart fa-fw"></i> <span><?php echo $text_sale; ?></span></a>
    <ul>
      <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
      <li><a href="<?php echo $backorder; ?>"><?php echo $text_backorder; ?></a></li>
      <li><a href="<?php echo $backorder_status; ?>"><?php echo $text_backorder_status; ?></a></li>
      <li><a href="<?php echo $incoming; ?>">Incoming Orders</a></li>
      <li><a href="<?php echo $incomingorders; ?>">Order List</a></li>
      <li><a href="<?php echo $combine_shipping; ?>"><?php echo $text_combine_shipping; ?></a></li>
      <li><a href="<?php echo $account_report; ?>"><?php echo $text_account_report; ?></a></li>
      <li><a href="<?php echo $order_recurring; ?>"><?php echo $text_order_recurring; ?></a></li>
      <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
      <li><a class="parent"><?php echo $text_voucher; ?></a>
        <ul>
          <li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
          <li><a href="<?php echo $voucher_theme; ?>"><?php echo $text_voucher_theme; ?></a></li>
        </ul>
      </li>
      <li><a class="parent"><?php echo $text_paypal ?></a>
        <ul>
          <li><a href="<?php echo $paypal_search ?>"><?php echo $text_paypal_search ?></a></li>
        </ul>
      </li>
    </ul>
  </li>
  <li id="customer"><a class="parent"><i class="fa fa-user fa-fw"></i> <span><?php echo $text_customer; ?></span></a>
    <ul>
      <li><a href="<?php echo $customer; ?>"><?php echo $text_customer; ?></a></li>
      <li><a href="<?php echo $customer_group; ?>"><?php echo $text_customer_group; ?></a></li>
      <li><a href="<?php echo $custom_field; ?>"><?php echo $text_custom_field; ?></a></li>
    </ul>
  </li>
  <li><a class="parent"><i class="fa fa-share-alt fa-fw"></i> <span><?php echo $text_marketing; ?></span></a>
    <ul>
      <li><a href="<?php echo $marketing; ?>"><?php echo $text_marketing; ?></a></li>
      <li><a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a></li>
      <li><a href="<?php echo $coupon; ?>"><?php echo $text_coupon; ?></a></li>
      <li><a href="<?php echo $giveaway; ?>"><?php echo $text_giveaway; ?></a></li>
      <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
    </ul>
  </li>

	<li id="abandoned"><a class="parent"><i class="fa fa-shopping-cart fa-fw"></i> <span><?php echo $text_abandoned_carts; ?></span></a>
		<ul>
			<li><a href="<?php echo $abandoned_carts; ?>"><?php echo $text_abandoned_carts; ?></a></li>
		</ul>
	</li>
			
  <li id="system"><a class="parent"><i class="fa fa-cog fa-fw"></i> <span><?php echo $text_system; ?></span></a>
    <ul>
      <li><a href="<?php echo $setting; ?>"><?php echo $text_setting; ?></a></li>
       <li><a href="<?php echo $setting_subcat; ?>"><?php echo $text_setting_subcategory; ?></a></li>
      <li><a class="parent"><?php echo $text_users; ?></a>
        <ul>
         
          <li><a href="<?php echo $user; ?>"><?php echo $text_user; ?></a></li>
          <li><a href="<?php echo $user_group; ?>"><?php echo $text_user_group; ?></a></li>
          <li><a href="<?php echo $api; ?>"><?php echo $text_api; ?></a></li>
        </ul>
      </li>
      <li><a class="parent"><?php echo $text_localisation; ?></a>
        <ul>
<li><a href="<?php echo $product_label; ?>"><?php echo "Product Label "; ?></a></li>
          <li><a href="<?php echo $location; ?>"><?php echo $text_location; ?></a></li>
          <li><a href="<?php echo $language; ?>"><?php echo $text_language; ?></a></li>
          <li><a href="<?php echo $currency; ?>"><?php echo $text_currency; ?></a></li>
          <li><a href="<?php echo $stock_status; ?>"><?php echo $text_stock_status; ?></a></li>
          <li><a href="<?php echo $order_status; ?>"><?php echo $text_order_status; ?></a></li>
          <li><a class="parent"><?php echo $text_return; ?></a>
            <ul>
              <li><a href="<?php echo $return_status; ?>"><?php echo $text_return_status; ?></a></li>
              <li><a href="<?php echo $return_action; ?>"><?php echo $text_return_action; ?></a></li>
              <li><a href="<?php echo $return_reason; ?>"><?php echo $text_return_reason; ?></a></li>
            </ul>
          </li>
          <li><a href="<?php echo $country; ?>"><?php echo $text_country; ?></a></li>
          <li><a href="<?php echo $zone; ?>"><?php echo $text_zone; ?></a></li>
          <li><a href="<?php echo $geo_zone; ?>"><?php echo $text_geo_zone; ?></a></li>
          <li><a class="parent"><?php echo $text_tax; ?></a>
            <ul>
              <li><a href="<?php echo $tax_class; ?>"><?php echo $text_tax_class; ?></a></li>
              <li><a href="<?php echo $tax_rate; ?>"><?php echo $text_tax_rate; ?></a></li>
            </ul>
          </li>
          <li><a href="<?php echo $length_class; ?>"><?php echo $text_length_class; ?></a></li>
          <li><a href="<?php echo $weight_class; ?>"><?php echo $text_weight_class; ?></a></li>
        </ul>
      </li>
      <li><a class="parent"><?php echo $text_tools; ?></a>
        <ul>
          <li><a href="<?php echo $upload; ?>"><?php echo $text_upload; ?></a></li>
          <li><a href="<?php echo $backup; ?>"><?php echo $text_backup; ?></a></li>
<?php /* //karapuz (ka_csv_product_import.ocmod.xml)  */?>
      <?php if (!empty($ka_product_import)) { ?>
        <li><a href="<?php echo $ka_product_import; ?>"><?php echo $this->t('CSV Product Import'); ?></a></li>
      <?php } ?>
<?php ///karapuz (ka_csv_product_import.ocmod.xml)  ?>
<?php /* //karapuz (ka_csv_product_export.ocmod.xml)  */?>
      <?php if (!empty($ka_product_export)) { ?>
        <li><a href="<?php echo $ka_product_export; ?>"><?php echo $this->t('CSV Product Export'); ?></a></li>
      <?php } ?>
<?php /* ///karapuz (ka_csv_product_export.ocmod.xml)  */?>
          <li><a href="<?php echo $error_log; ?>"><?php echo $text_error_log; ?></a></li>
        </ul>
      </li>
    </ul>
  </li>
  <li id="reports"><a class="parent"><i class="fa fa-bar-chart-o fa-fw"></i> <span><?php echo $text_reports; ?></span></a>
    <ul>
      <li><a class="parent"><?php echo $text_sale; ?></a>
        <ul>
          <li><a href="<?php echo $report_sale_order; ?>"><?php echo $text_report_sale_order; ?></a></li>
          <li><a href="<?php echo $report_sale_tax; ?>"><?php echo $text_report_sale_tax; ?></a></li>
          <li><a href="<?php echo $report_sale_shipping; ?>"><?php echo $text_report_sale_shipping; ?></a></li>
          <li><a href="<?php echo $report_sale_return; ?>"><?php echo $text_report_sale_return; ?></a></li>
          <li><a href="<?php echo $report_sale_coupon; ?>"><?php echo $text_report_sale_coupon; ?></a></li>
        </ul>
      </li>
      <li><a class="parent"><?php echo $text_product; ?></a>

			<li><a class="parent"><?php echo 'QR Code(s)'; ?></a>
              <ul>
                  <li><a href="<?php echo $qr_code; ?>"><?php echo $text_qr_code; ?></a></li>
                  <li><a href="<?php echo $qr_code_csv; ?>"><?php echo $text_qr_code_csv; ?></a></li>
            
              </ul>
          </li> 
		  
                
                
        <ul>

<li><a href="<?php echo $report_adv_products_profit; ?>"><?php echo $text_report_adv_products_profit; ?></a></li>
            
          
<li style="border-top:1px dashed #888;"><a href="<?php echo $report_product_viewed; ?>"><?php echo $text_report_product_viewed; ?></a></li>
            
          <li><a href="<?php echo $report_product_purchased; ?>"><?php echo $text_report_product_purchased; ?></a></li>
        </ul>
      </li>
      <li><a class="parent"><?php echo $text_customer; ?></a>
        <ul>
          <li><a href="<?php echo $report_customer_online; ?>"><?php echo $text_report_customer_online; ?></a></li>
          <li><a href="<?php echo $report_customer_activity; ?>"><?php echo $text_report_customer_activity; ?></a></li>
          <li><a href="<?php echo $report_customer_order; ?>"><?php echo $text_report_customer_order; ?></a></li>
          <li><a href="<?php echo $report_customer_reward; ?>"><?php echo $text_report_customer_reward; ?></a></li>
          <li><a href="<?php echo $report_customer_credit; ?>"><?php echo $text_report_customer_credit; ?></a></li>
        </ul>
      </li>
      <li><a class="parent"><?php echo $text_marketing; ?></a>
        <ul>
          <li><a href="<?php echo $report_marketing; ?>"><?php echo $text_marketing; ?></a></li>
          <li><a href="<?php echo $report_affiliate; ?>"><?php echo $text_report_affiliate; ?></a></li>
          <li><a href="<?php echo $report_affiliate_activity; ?>"><?php echo $text_report_affiliate_activity; ?></a></li>
        </ul>
      </li>
    </ul>
  </li>
  <li id="ie_tool"><a class="top"><i class="fa fa-upload fa-fw"></i><span><?php echo $text_import_export; ?></span></a>
      <ul>
          <li><a class="parent"><?php echo $text_simple_product; ?></a>
              <ul>
                  <li><a href="<?php echo $simple_product_export; ?>"><?php echo $text_export; ?></a></li>
                  <li><a href="<?php echo $simple_product_setting_export; ?>"><?php echo $text_setting_export; ?></a></li>
                  <li><a href="<?php echo $simple_product_import; ?>"><?php echo $text_import; ?></a></li>
                  <li><a href="<?php echo $simple_product_setting_import; ?>"><?php echo $text_setting_import; ?></a></li>
              </ul>
          </li>
          <li><a class="parent"><?php echo $text_grouped_product; ?></a>
              <ul>
                  <li><a href="<?php echo $grouped_product_export; ?>"><?php echo $text_export; ?></a></li>
                  <li><a href="<?php echo $grouped_product_import; ?>"><?php echo $text_import; ?></a></li>
              </ul>
          </li>
          <li><a class="parent"><?php echo $text_unit_conversion; ?></a>
              <ul>
                  <li><a href="<?php echo $unit_conversion_export; ?>"><?php echo $text_export; ?></a></li>
                  <li><a href="<?php echo $unit_conversion_import; ?>"><?php echo $text_import; ?></a></li>
              </ul>
          </li>
          <li><a class="parent"><?php echo $text_incoming_order; ?></a>
              <ul>
                  <li><a href="<?php echo $incoming_orders_export; ?>"><?php echo $text_export; ?></a></li>
                  <li><a href="<?php echo $incoming_orders_import; ?>"><?php echo $text_import; ?></a></li>
              </ul>
          </li>
          <li><a class="parent"><?php echo $text_backorder; ?></a>
              <ul>
                  <li><a href="<?php echo $backorders_export; ?>"><?php echo $text_export; ?></a></li>
                  <li><a href="<?php echo $backorders_import; ?>"><?php echo $text_import; ?></a></li>
              </ul>
          </li>
          <li><a class="parent"><?php echo 'Import/Export Sorting'; ?></a>
              <ul>
                  <li><a href="<?php echo $sorting_import; ?>"><?php echo 'Import Sorting'; ?></a></li>
                  <li><a href="<?php echo $sorting_export; ?>"><?php echo 'Export Sorting'; ?></a></li>
              </ul>
          </li>
      </ul>
  </li>
<?php } else { ?>
				<li id="pos"><a class="parent"><i class="fa fa-desktop fa-fw"></i> <span>Point Of Sale</span></a>
					<ul>
						<li id="pos_settings"><a href="<?php echo $pos_console; ?>">Console</a></li>
						<li><a href="<?php echo $pos_payment_summary; ?>">Payment Summary</a></li>
					</ul>
				</li>
				<?php } ?></ul>
