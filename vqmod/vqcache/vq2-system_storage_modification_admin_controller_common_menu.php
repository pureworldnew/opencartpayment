<?php
class ControllerCommonMenu extends Controller {
	public function index() {
		$this->load->language('common/menu');

			$this->load->model('extension/extension');
			if (in_array('universal_import', $this->model_extension_extension->getInstalled('module'))) {
				$data['text_univimportpro'] = 'Universal Import Pro';
				$data['link_univimportpro'] = $this->url->link('module/universal_import', 'token=' . $this->session->data['token'], 'SSL');
			} else {
				$data['text_univimportpro'] = 'Install Universal Import Pro';
				$data['link_univimportpro'] = $this->url->link('extension/module/install', 'extension=universal_import&token=' . $this->session->data['token'], 'SSL');
			}
			

			$this->load->model('extension/extension');
			if (in_array('pdf_invoice', $this->model_extension_extension->getInstalled('module'))) {
				$data['text_pdfinvpro'] = 'PDF Invoice Pro';
				$data['link_pdfinvpro'] = $this->url->link('module/pdf_invoice', (isset($this->session->data['user_token']) ? 'user_token='.$this->session->data['user_token'] : 'token='.$this->session->data['token']), 'SSL');
			} else {
				$data['text_pdfinvpro'] = 'Install PDF Invoice Pro';
				$data['link_pdfinvpro'] = $this->url->link('extension/module/install', 'extension=pdf_invoice&'.(isset($this->session->data['user_token']) ? 'user_token='.$this->session->data['user_token'] : 'token='.$this->session->data['token']), 'SSL');
			}
			

		$data['text_analytics'] = $this->language->get('text_analytics');

		$data['text_redirects'] = "Redirects 301";
            

		$data['text_qr_code'] = "QR Code Generator";
        $data['text_qr_code_csv'] = "Generator QR CSV";
            
		$data['text_affiliate'] = $this->language->get('text_affiliate');
		$data['text_api'] = $this->language->get('text_api');
		$data['text_attribute'] = $this->language->get('text_attribute');
		$data['text_attribute_group'] = $this->language->get('text_attribute_group');
		$data['text_backup'] = $this->language->get('text_backup');
		$data['text_banner'] = $this->language->get('text_banner');
		$data['text_captcha'] = $this->language->get('text_captcha');
		$data['text_catalog'] = $this->language->get('text_catalog');
		$data['text_category'] = $this->language->get('text_category');
		$data['text_category_seo_url'] = $this->language->get('text_category_seo_url');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_contact'] = $this->language->get('text_contact');
		$data['text_country'] = $this->language->get('text_country');
		$data['text_coupon'] = $this->language->get('text_coupon');
		$data['text_giveaway'] = $this->language->get('text_giveaway');
		$data['text_currency'] = $this->language->get('text_currency');
		$data['text_customer'] = $this->language->get('text_customer');
		$data['text_customer_group'] = $this->language->get('text_customer_group');
		$data['text_customer_field'] = $this->language->get('text_customer_field');
		$data['text_custom_field'] = $this->language->get('text_custom_field');
		$data['text_sale'] = $this->language->get('text_sale');
		$data['text_paypal'] = $this->language->get('text_paypal');
		$data['text_paypal_search'] = $this->language->get('text_paypal_search');
		$data['text_design'] = $this->language->get('text_design');
		$data['text_download'] = $this->language->get('text_download');
		$data['text_error_log'] = $this->language->get('text_error_log');
		$data['text_extension'] = $this->language->get('text_extension');
		$data['text_feed'] = $this->language->get('text_feed');
		$data['text_fraud'] = $this->language->get('text_fraud');
		$data['text_filter'] = $this->language->get('text_filter');
		$data['text_geo_zone'] = $this->language->get('text_geo_zone');
		$data['text_dashboard'] = $this->language->get('text_dashboard');
		$data['text_help'] = $this->language->get('text_help');
		$data['text_information'] = $this->language->get('text_information');
		$data['text_installer'] = $this->language->get('text_installer');
		$data['text_language'] = $this->language->get('text_language');
		$data['text_layout'] = $this->language->get('text_layout');
		$data['text_localisation'] = $this->language->get('text_localisation');
		$data['text_location'] = $this->language->get('text_location');
		$data['text_marketing'] = $this->language->get('text_marketing');
		$data['text_modification'] = $this->language->get('text_modification');
		$data['text_manufacturer'] = $this->language->get('text_manufacturer');

$data['text_adv_supplier'] = $this->language->get('text_adv_supplier');
            
		$data['text_module'] = $this->language->get('text_module');
		$data['text_option'] = $this->language->get('text_option');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_order_status'] = $this->language->get('text_order_status');
		$data['text_opencart'] = $this->language->get('text_opencart');
		$data['text_payment'] = $this->language->get('text_payment');
		$data['text_product'] = $this->language->get('text_product');

			$data['text_seopack'] = $this->language->get('text_seopack');
			$data['text_seoimages'] = $this->language->get('text_seoimages');
			$data['text_seoeditor'] = $this->language->get('text_seoeditor');
			$data['text_seoreport'] = $this->language->get('text_seoreport');
			$data['text_autolinks'] = $this->language->get('text_autolinks');
			$data['text_canonicals'] = $this->language->get('text_canonicals');
			$data['text_mlseo'] = $this->language->get('text_mlseo');
			$data['text_richsnippets'] = $this->language->get('text_richsnippets');
			$data['text_seopagination'] = $this->language->get('text_seopagination');
			$data['text_redirect'] = $this->language->get('text_redirect');
			$data['text_not_found_report'] = $this->language->get('text_not_found_report');
			$data['text_seoreplacer'] = $this->language->get('text_seoreplacer');
			$data['text_extendedseo'] = $this->language->get('text_extendedseo');
			$data['text_about'] = $this->language->get('text_about');
			
		$data['text_reports'] = $this->language->get('text_reports');
		$data['text_report_sale_order'] = $this->language->get('text_report_sale_order');
		$data['text_report_sale_tax'] = $this->language->get('text_report_sale_tax');
		$data['text_report_sale_shipping'] = $this->language->get('text_report_sale_shipping');
		$data['text_report_sale_return'] = $this->language->get('text_report_sale_return');
		$data['text_report_sale_coupon'] = $this->language->get('text_report_sale_coupon');
		$data['text_report_sale_return'] = $this->language->get('text_report_sale_return');
		$data['text_report_product_viewed'] = $this->language->get('text_report_product_viewed');
		$data['text_report_product_purchased'] = $this->language->get('text_report_product_purchased');

$data['text_report_adv_products_profit'] = $this->language->get('text_report_adv_products_profit');
            
		$data['text_report_customer_activity'] = $this->language->get('text_report_customer_activity');
		$data['text_report_customer_online'] = $this->language->get('text_report_customer_online');
		$data['text_report_customer_order'] = $this->language->get('text_report_customer_order');
		$data['text_report_customer_reward'] = $this->language->get('text_report_customer_reward');
		$data['text_report_customer_credit'] = $this->language->get('text_report_customer_credit');
		$data['text_report_customer_order'] = $this->language->get('text_report_customer_order');
		$data['text_report_affiliate'] = $this->language->get('text_report_affiliate');
		$data['text_report_affiliate_activity'] = $this->language->get('text_report_affiliate_activity');
		$data['text_review'] = $this->language->get('text_review');
		$data['text_return'] = $this->language->get('text_return');
		$data['text_return_action'] = $this->language->get('text_return_action');
		$data['text_return_reason'] = $this->language->get('text_return_reason');
		$data['text_return_status'] = $this->language->get('text_return_status');
		$data['text_shipping'] = $this->language->get('text_shipping');
		$data['text_setting'] = $this->language->get('text_setting');
		$data['text_stock_status'] = $this->language->get('text_stock_status');
		$data['text_system'] = $this->language->get('text_system');
		$data['text_tax'] = $this->language->get('text_tax');
		$data['text_tax_class'] = $this->language->get('text_tax_class');
		$data['text_tax_rate'] = $this->language->get('text_tax_rate');
		$data['text_tools'] = $this->language->get('text_tools');
		$data['text_total'] = $this->language->get('text_total');
		$data['text_upload'] = $this->language->get('text_upload');
		$data['text_tracking'] = $this->language->get('text_tracking');
		$data['text_user'] = $this->language->get('text_user');
		$data['text_user_group'] = $this->language->get('text_user_group');
		$data['text_users'] = $this->language->get('text_users');
		$data['text_voucher'] = $this->language->get('text_voucher');
		$data['text_voucher_theme'] = $this->language->get('text_voucher_theme');
		$data['text_weight_class'] = $this->language->get('text_weight_class');
		$data['text_length_class'] = $this->language->get('text_length_class');
		$data['text_zone'] = $this->language->get('text_zone');
		$data['text_recurring'] = $this->language->get('text_recurring');
		$data['text_order_recurring'] = $this->language->get('text_order_recurring');
		$data['text_openbay_extension'] = $this->language->get('text_openbay_extension');
		$data['text_openbay_dashboard'] = $this->language->get('text_openbay_dashboard');
		$data['text_openbay_orders'] = $this->language->get('text_openbay_orders');
		$data['text_openbay_items'] = $this->language->get('text_openbay_items');
		$data['text_openbay_ebay'] = $this->language->get('text_openbay_ebay');
		$data['text_openbay_etsy'] = $this->language->get('text_openbay_etsy');
		$data['text_openbay_amazon'] = $this->language->get('text_openbay_amazon');
		$data['text_openbay_amazonus'] = $this->language->get('text_openbay_amazonus');
		$data['text_openbay_settings'] = $this->language->get('text_openbay_settings');
		$data['text_openbay_links'] = $this->language->get('text_openbay_links');
		$data['text_openbay_report_price'] = $this->language->get('text_openbay_report_price');
		$data['text_openbay_order_import'] = $this->language->get('text_openbay_order_import');
		$data['text_lookbook'] = $this->language->get('text_lookbook');
		$data['text_qa'] = $this->language->get('text_qa');
		$data['text_category_price_updater'] = $this->language->get('text_category_price_updater');
		$data['text_unit_conversion'] = $this->language->get('text_unit_conversion');
		$data['text_grouped_product'] = $this->language->get('text_grouped_product');
		$data['text_new_grouping_system'] = $this->language->get('text_new_grouping_system');
		$data['text_grouped_product_sort'] = $this->language->get('text_grouped_product_sort');
		$data['text_grouped_batch_sort'] = $this->language->get('text_grouped_batch_sort');
		$data['text_concat_product'] = $this->language->get('text_concat_product');
		$data['text_account_report'] = $this->language->get('text_account_report');
		$data['text_combine_shipping'] = $this->language->get('text_combine_shipping');
		$data['text_backorder'] = $this->language->get('text_backorder');
		$data['text_backorder_status'] = $this->language->get('text_backorder_status');
		$data['text_import_export'] = $this->language->get('text_import_export');
		$data['text_simple_product'] = $this->language->get('text_simple_product');
		$data['text_grouped_product'] = $this->language->get('text_grouped_product');
		$data['text_unit_conversion'] = $this->language->get('text_unit_conversion');
		$data['text_incoming_order'] = $this->language->get('text_incoming_order');
		$data['text_export'] = $this->language->get('text_export');
		$data['text_import'] = $this->language->get('text_import');
		$data['text_setting_export'] = $this->language->get('text_setting_export');
		$data['text_setting_import'] = $this->language->get('text_setting_import');
		$data['text_setting_subcategory'] = 'Additional cost Categories';

		$data['analytics'] = $this->url->link('extension/analytics', 'token=' . $this->session->data['token'], 'SSL');

        $data['redirects'] = $this->url->link('module/redirects', 'token=' . $this->session->data['token'], true);
                

        $data['qr_code'] = $this->url->link('qrlabel/qrlabel', 'token=' . $this->session->data['token'], true);
        $data['qr_code_csv'] = $this->url->link('qrlabel/qrlabel/generateQrFromCsv', 'token=' . $this->session->data['token'], true);
                

		$data['text_abandoned_carts'] = $this->language->get('text_abandoned_carts');
$data['text_sitemap'] = $this->language->get('text_sitemap');

		$data['abandoned_carts'] = $this->url->link('abandoned_carts/dashboard', 'token=' . $this->session->data['token'], 'SSL');
$data['sitemap'] = $this->url->link('hbseo/hb_sitemap', 'token=' . $this->session->data['token'], 'SSL');
			
		$result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension` WHERE `code` = 'emailtemplate'");
		if ($result->num_rows) {
			$data['text_emailtemplate'] = $this->language->get('text_emailtemplate');
			$data['module_emailtemplate'] = $this->url->link('module/emailtemplate', 'token=' . $this->session->data['token'], 'SSL');
		}
            
		$data['home'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL');
$data['product_label'] = $this->url->link('localisation/products_label', 'token=' . $this->session->data['token'], 'SSL');
		$data['affiliate'] = $this->url->link('marketing/affiliate', 'token=' . $this->session->data['token'], 'SSL');
		$data['api'] = $this->url->link('user/api', 'token=' . $this->session->data['token'], 'SSL');
		$data['attribute'] = $this->url->link('catalog/attribute', 'token=' . $this->session->data['token'], 'SSL');
		$data['attribute_group'] = $this->url->link('catalog/attribute_group', 'token=' . $this->session->data['token'], 'SSL');
		$data['backup'] = $this->url->link('tool/backup', 'token=' . $this->session->data['token'], 'SSL');
		//karapuz (ka_csv_product_import.ocmod.xml) 
		if (method_exists($this->db, 'isKaInstalled') && $this->db->isKaInstalled('ka_product_import')) {
			$data['ka_product_import'] = $this->url->link('tool/ka_product_import', 'token=' . $this->session->data['token'], 'SSL');
		}
		///karapuz (ka_csv_product_import.ocmod.xml) 
//karapuz (ka_csv_product_export.ocmod.xml) 
		if (method_exists($this->db, 'isKaInstalled') && $this->db->isKaInstalled('ka_product_export')) {
			$data['ka_product_export'] = $this->url->link('tool/ka_product_export', 'token=' . $this->session->data['token'], 'SSL');
		}
///karapuz (ka_csv_product_export.ocmod.xml) 
		$data['banner'] = $this->url->link('design/banner', 'token=' . $this->session->data['token'], 'SSL');
		$data['captcha'] = $this->url->link('extension/captcha', 'token=' . $this->session->data['token'], 'SSL');
		$data['category'] = $this->url->link('catalog/category', 'token=' . $this->session->data['token'], 'SSL');
		$data['category_seo_url'] = $this->url->link('catalog/category/seourls', 'token=' . $this->session->data['token'], 'SSL');
		$data['country'] = $this->url->link('localisation/country', 'token=' . $this->session->data['token'], 'SSL');
		$data['contact'] = $this->url->link('marketing/contact', 'token=' . $this->session->data['token'], 'SSL');
		$data['coupon'] = $this->url->link('marketing/coupon', 'token=' . $this->session->data['token'], 'SSL');
		$data['giveaway'] = $this->url->link('marketing/giveaway', 'token=' . $this->session->data['token'], 'SSL');
		$data['currency'] = $this->url->link('localisation/currency', 'token=' . $this->session->data['token'], 'SSL');
		$data['customer'] = $this->url->link('customer/customer', 'token=' . $this->session->data['token'], 'SSL');
		$data['customer_fields'] = $this->url->link('customer/customer_field', 'token=' . $this->session->data['token'], 'SSL');
		$data['customer_group'] = $this->url->link('customer/customer_group', 'token=' . $this->session->data['token'], 'SSL');
		$data['custom_field'] = $this->url->link('customer/custom_field', 'token=' . $this->session->data['token'], 'SSL');
		$data['download'] = $this->url->link('catalog/download', 'token=' . $this->session->data['token'], 'SSL');
		$data['error_log'] = $this->url->link('tool/error_log', 'token=' . $this->session->data['token'], 'SSL');
		$data['feed'] = $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL');
		$data['filter'] = $this->url->link('catalog/filter', 'token=' . $this->session->data['token'], 'SSL');
		$data['fraud'] = $this->url->link('extension/fraud', 'token=' . $this->session->data['token'], 'SSL');
		$data['geo_zone'] = $this->url->link('localisation/geo_zone', 'token=' . $this->session->data['token'], 'SSL');
		$data['information'] = $this->url->link('catalog/information', 'token=' . $this->session->data['token'], 'SSL');
		$data['installer'] = $this->url->link('extension/installer', 'token=' . $this->session->data['token'], 'SSL');
		$data['language'] = $this->url->link('localisation/language', 'token=' . $this->session->data['token'], 'SSL');
		$data['layout'] = $this->url->link('design/layout', 'token=' . $this->session->data['token'], 'SSL');
		$data['location'] = $this->url->link('localisation/location', 'token=' . $this->session->data['token'], 'SSL');
		$data['modification'] = $this->url->link('extension/modification', 'token=' . $this->session->data['token'], 'SSL');
		//karapuz (ka_extensions.ocmod.xml) 
		$data['ka_extensions'] = $this->url->link('extension/ka_extensions', 'token=' . $this->session->data['token'], 'SSL');
		///karapuz (ka_extensions.ocmod.xml) 
		$data['manufacturer'] = $this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'], 'SSL');

$data['adv_supplier'] = $this->url->link('catalog/adv_supplier', 'token=' . $this->session->data['token'], 'SSL');
            
		$data['qrcode'] = $this->url->link('catalog/qrgenerator', 'token=' . $this->session->data['token'], 'SSL');
		$data['marketing'] = $this->url->link('marketing/marketing', 'token=' . $this->session->data['token'], 'SSL');
		$data['module'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		$data['option'] = $this->url->link('catalog/option', 'token=' . $this->session->data['token'], 'SSL');
		$data['order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL');
		$data['order_status'] = $this->url->link('localisation/order_status', 'token=' . $this->session->data['token'], 'SSL');
		$data['payment'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		$data['paypal_search'] = $this->url->link('payment/pp_express/search', 'token=' . $this->session->data['token'], 'SSL');
		$data['product'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'], 'SSL');

			$data['seopack'] = $this->url->link('catalog/seopack', 'token=' . $this->session->data['token'], 'SSL');
			$data['seoimages'] = $this->url->link('catalog/seoimages', 'token=' . $this->session->data['token'], 'SSL');
			$data['seoeditor'] = $this->url->link('catalog/seoeditor', 'token=' . $this->session->data['token'], 'SSL');
			$data['seoreport'] = $this->url->link('catalog/seoreport', 'token=' . $this->session->data['token'], 'SSL');
			$data['autolinks'] = $this->url->link('catalog/autolinks', 'token=' . $this->session->data['token'], 'SSL');
			$data['canonicals'] = $this->url->link('catalog/canonicals', 'token=' . $this->session->data['token'], 'SSL');
			$data['mlseo'] = $this->url->link('catalog/mlseo', 'token=' . $this->session->data['token'], 'SSL');
			$data['richsnippets'] = $this->url->link('catalog/richsnippets', 'token=' . $this->session->data['token'], 'SSL');
			$data['seopagination'] = $this->url->link('catalog/seopagination', 'token=' . $this->session->data['token'], 'SSL');
			$data['redirect'] = $this->url->link('catalog/redirect', 'token=' . $this->session->data['token'], 'SSL');
			$data['not_found_report'] = $this->url->link('catalog/not_found_report', 'token=' . $this->session->data['token'], 'SSL');
			$data['seoreplacer'] = $this->url->link('catalog/seoreplacer', 'token=' . $this->session->data['token'], 'SSL');
			$data['extendedseo'] = $this->url->link('catalog/extendedseo', 'token=' . $this->session->data['token'], 'SSL');
			$data['about'] = $this->url->link('catalog/l', 'token=' . $this->session->data['token'], 'SSL');
			
		$data['incomingorders'] = $this->url->link('catalog/incomingorders', 'token=' . $this->session->data['token'], 'SSL');
		$data['incoming'] = $this->url->link('sale/incoming', 'token=' . $this->session->data['token'], 'SSL');
		$data['combine_shipping'] = $this->url->link('sale/combineshipping', 'token=' . $this->session->data['token'], 'SSL');
		$data['backorder'] = $this->url->link('sale/backorder', 'token=' . $this->session->data['token'], 'SSL');
		$data['backorder_status'] = $this->url->link('sale/backorder_status', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_sale_order'] = $this->url->link('report/sale_order', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_sale_tax'] = $this->url->link('report/sale_tax', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_sale_shipping'] = $this->url->link('report/sale_shipping', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_sale_return'] = $this->url->link('report/sale_return', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_sale_coupon'] = $this->url->link('report/sale_coupon', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_product_viewed'] = $this->url->link('report/product_viewed', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_product_purchased'] = $this->url->link('report/product_purchased', 'token=' . $this->session->data['token'], 'SSL');

$data['report_adv_products_profit'] = $this->url->link('report/adv_products_profit', 'token=' . $this->session->data['token'], 'SSL');
            
		$data['report_customer_activity'] = $this->url->link('report/customer_activity', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_customer_online'] = $this->url->link('report/customer_online', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_customer_order'] = $this->url->link('report/customer_order', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_customer_reward'] = $this->url->link('report/customer_reward', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_customer_credit'] = $this->url->link('report/customer_credit', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_marketing'] = $this->url->link('report/marketing', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_affiliate'] = $this->url->link('report/affiliate', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_affiliate_activity'] = $this->url->link('report/affiliate_activity', 'token=' . $this->session->data['token'], 'SSL');
		$data['review'] = $this->url->link('catalog/review', 'token=' . $this->session->data['token'], 'SSL');
		$data['return'] = $this->url->link('sale/return', 'token=' . $this->session->data['token'], 'SSL');
		$data['return_action'] = $this->url->link('localisation/return_action', 'token=' . $this->session->data['token'], 'SSL');
		$data['return_reason'] = $this->url->link('localisation/return_reason', 'token=' . $this->session->data['token'], 'SSL');
		$data['return_status'] = $this->url->link('localisation/return_status', 'token=' . $this->session->data['token'], 'SSL');
		$data['shipping'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');
		$data['setting'] = $this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL');
		$data['stock_status'] = $this->url->link('localisation/stock_status', 'token=' . $this->session->data['token'], 'SSL');
		$data['tax_class'] = $this->url->link('localisation/tax_class', 'token=' . $this->session->data['token'], 'SSL');
		$data['tax_rate'] = $this->url->link('localisation/tax_rate', 'token=' . $this->session->data['token'], 'SSL');
		$data['total'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');
		$data['upload'] = $this->url->link('tool/upload', 'token=' . $this->session->data['token'], 'SSL');
		$data['user'] = $this->url->link('user/user', 'token=' . $this->session->data['token'], 'SSL');
		$data['user_group'] = $this->url->link('user/user_permission', 'token=' . $this->session->data['token'], 'SSL');
		$data['voucher'] = $this->url->link('sale/voucher', 'token=' . $this->session->data['token'], 'SSL');
		$data['voucher_theme'] = $this->url->link('sale/voucher_theme', 'token=' . $this->session->data['token'], 'SSL');
		$data['weight_class'] = $this->url->link('localisation/weight_class', 'token=' . $this->session->data['token'], 'SSL');
		$data['length_class'] = $this->url->link('localisation/length_class', 'token=' . $this->session->data['token'], 'SSL');
		$data['zone'] = $this->url->link('localisation/zone', 'token=' . $this->session->data['token'], 'SSL');
		$data['recurring'] = $this->url->link('catalog/recurring', 'token=' . $this->session->data['token'], 'SSL');

        
		$data['advsettings'] = $this->url->link('module/supercategorymenuadvanced/settings', 'token=' . $this->session->data['token'], 'SSL');
		$data['advfilters'] = $this->url->link('module/supercategorymenuadvanced', 'token=' . $this->session->data['token'], 'SSL');
		$data['advseo'] = $this->url->link('advancedmenuseo/supercategorymenuadvancedseo', 'token=' . $this->session->data['token'], 'SSL');
		
      
		$data['order_recurring'] = $this->url->link('sale/recurring', 'token=' . $this->session->data['token'], 'SSL');

			$data['pos_console'] = $this->url->link('module/pos/main', 'token=' . $this->session->data['token'], 'SSL');
			$data['pos_console'] = str_replace("/admin/","/pos/",$data['pos_console']);
			$data['pos_settings'] = $this->url->link('module/pos', 'token=' . $this->session->data['token'], 'SSL');
			$data['pos_stock_manager'] = $this->url->link('module/pos/stock_manager', 'token=' . $this->session->data['token'], 'SSL');
			$data['pos_payment_summary'] = $this->url->link('report/order_payment/summary', 'token=' . $this->session->data['token'], 'SSL');
			$data['pos_payment_details'] = $this->url->link('report/order_payment', 'token=' . $this->session->data['token'], 'SSL');
			$data['pos_extended_product'] = $this->url->link('pos/extended_product', 'token=' . $this->session->data['token'], 'SSL');
			$data['pos_commission_summary'] = $this->url->link('report/pos_commission/summary', 'token=' . $this->session->data['token'], 'SSL');
			$data['pos_commission_details'] = $this->url->link('report/pos_commission', 'token=' . $this->session->data['token'], 'SSL');
			$data['pos_clean_tables'] = $this->url->link('pos/clean_tables', 'token=' . $this->session->data['token'], 'SSL');
			$data['pos_sn_manager'] = $this->url->link('pos/sn_manager', 'token=' . $this->session->data['token'], 'SSL');
			$data['is_pos_user'] = (empty($this->session->data['is_pos_user'])) ? 0 : 1;
			$data['pos_export_report_sales'] = $this->url->link('module/pos/export_report_csv', 'token=' . $this->session->data['token'] . '&type=sales', 'SSL');
			$data['pos_export_report_stock'] = $this->url->link('module/pos/export_report_csv', 'token=' . $this->session->data['token'] . '&type=stock', 'SSL');
			
		$data['lookbook'] = $this->url->link('catalog/lookbook', 'token=' . $this->session->data['token'], 'SSL');
		$data['openbay_show_menu'] = $this->config->get('openbaypro_menu');
		$data['openbay_link_extension'] = $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], 'SSL');
		$data['openbay_link_orders'] = $this->url->link('extension/openbay/orderlist', 'token=' . $this->session->data['token'], 'SSL');
		$data['openbay_link_items'] = $this->url->link('extension/openbay/items', 'token=' . $this->session->data['token'], 'SSL');
		$data['openbay_link_ebay'] = $this->url->link('openbay/ebay', 'token=' . $this->session->data['token'], 'SSL');
		$data['openbay_link_ebay_settings'] = $this->url->link('openbay/ebay/settings', 'token=' . $this->session->data['token'], 'SSL');
		$data['openbay_link_ebay_links'] = $this->url->link('openbay/ebay/viewitemlinks', 'token=' . $this->session->data['token'], 'SSL');
		$data['openbay_link_etsy'] = $this->url->link('openbay/etsy', 'token=' . $this->session->data['token'], 'SSL');
		$data['openbay_link_etsy_settings'] = $this->url->link('openbay/etsy/settings', 'token=' . $this->session->data['token'], 'SSL');
		$data['openbay_link_etsy_links'] = $this->url->link('openbay/etsy_product/links', 'token=' . $this->session->data['token'], 'SSL');
		$data['openbay_link_ebay_orderimport'] = $this->url->link('openbay/ebay/vieworderimport', 'token=' . $this->session->data['token'], 'SSL');
		$data['openbay_link_amazon'] = $this->url->link('openbay/amazon', 'token=' . $this->session->data['token'], 'SSL');
		$data['openbay_link_amazon_settings'] = $this->url->link('openbay/amazon/settings', 'token=' . $this->session->data['token'], 'SSL');
		$data['openbay_link_amazon_links'] = $this->url->link('openbay/amazon/itemlinks', 'token=' . $this->session->data['token'], 'SSL');
		$data['openbay_link_amazonus'] = $this->url->link('openbay/amazonus', 'token=' . $this->session->data['token'], 'SSL');
		$data['openbay_link_amazonus_settings'] = $this->url->link('openbay/amazonus/settings', 'token=' . $this->session->data['token'], 'SSL');
		$data['openbay_link_amazonus_links'] = $this->url->link('openbay/amazonus/itemlinks', 'token=' . $this->session->data['token'], 'SSL');
		$data['openbay_markets'] = array(
			'ebay' => $this->config->get('ebay_status'),
			'amazon' => $this->config->get('openbay_amazon_status'),
			'amazonus' => $this->config->get('openbay_amazonus_status'),
			'etsy' => $this->config->get('etsy_status'),
		);
		$data['qa'] = $this->url->link('catalog/qa', 'token=' . $this->session->data['token'], 'SSL');
		$data['marketprice'] = $this->url->link('catalog/marketp', 'token=' . $this->session->data['token'], 'SSL');
		$data['unit_conversion'] = $this->url->link('catalog/unit_conversion', 'token=' . $this->session->data['token'], 'SSL');
		$data['product_concate'] = $this->url->link('productconcat/upload', 'token=' . $this->session->data['token'], 'SSL');
		$data['product_bundle'] = $this->url->link('catalog/product_bundle', 'token=' . $this->session->data['token'], 'SSL');
		$data['product_configurable'] = $this->url->link('catalog/product_configurable', 'token=' . $this->session->data['token'], 'SSL');
		$data['product_grouped'] = $this->url->link('catalog/product_grouped', 'token=' . $this->session->data['token'], 'SSL');
		$data['grouped_product'] = $this->url->link('catalog/product_list_gp', 'token=' . $this->session->data['token'], 'SSL');
		$data['grouped_product_sort'] = $this->url->link('catalog/product_grouped_sort', 'token=' . $this->session->data['token'], 'SSL');
		$data['new_grouping_system'] = $this->url->link('catalog/new_grouping_system', 'token=' . $this->session->data['token'], 'SSL');
		$data['grouped_batch_sort'] = $this->url->link('catalog/grouped_batch_sort', 'token=' . $this->session->data['token'], 'SSL');
		$data['account_report'] = $this->url->link('sale/account', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['setting_subcat'] = $this->url->link('module/subcategorypercentage', 'token=' . $this->session->data['token'], 'SSL');
		
	
		$data['ka_export'] = $this->url->link('tool/ka_product_export', 'token=' . $this->session->data['token'], 'SSL');
		$data['simple_product_export'] = $this->url->link('tool/ka_product_export', 'token=' . $this->session->data['token'], 'SSL');
		$data['simple_product_setting_export'] = $this->url->link('ka_extensions/ka_product_export', 'token=' . $this->session->data['token'], 'SSL');
		$data['simple_product_import'] = $this->url->link('tool/ka_product_import', 'token=' . $this->session->data['token'], 'SSL');
		$data['simple_product_setting_import'] = $this->url->link('ka_extensions/ka_product_import', 'token=' . $this->session->data['token'], 'SSL');
		$data['grouped_product_export'] = $this->url->link('feed/ie_tool', 'token=' . $this->session->data['token'], 'SSL');
		$data['grouped_product_import'] = $this->url->link('productconcat/upload', 'token=' . $this->session->data['token'], 'SSL');
		$data['unit_conversion_export'] = $this->url->link('feed/ie_tool/unit_export', 'token=' . $this->session->data['token'], 'SSL');
		$data['unit_conversion_import'] = $this->url->link('feed/import_tool', 'token=' . $this->session->data['token'], 'SSL');
		$data['incoming_orders_export'] = $this->url->link('tool/ka_order_export', 'token=' . $this->session->data['token'], 'SSL');
		$data['incoming_orders_import'] = $this->url->link('tool/ka_order_import', 'token=' . $this->session->data['token'], 'SSL');
		$data['backorders_export'] = $this->url->link('tool/ka_backorder_export', 'token=' . $this->session->data['token'], 'SSL');
		$data['backorders_import'] = $this->url->link('tool/ka_backorder_import', 'token=' . $this->session->data['token'], 'SSL');
		$data['sorting_import'] = $this->url->link('feed/ie_sorting', 'token=' . $this->session->data['token'], 'SSL');
		$data['sorting_export'] = $this->url->link('feed/ie_sorting/export', 'token=' . $this->session->data['token'], 'SSL');


    $data['rma_rma'] = $this->url->link('catalog/wk_rma_admin', 'token=' . $this->session->data['token'], 'SSL');
    $data['rma_status'] = $this->url->link('catalog/wk_rma_status', 'token=' . $this->session->data['token'], 'SSL');
    $data['rma_reason'] = $this->url->link('catalog/wk_rma_reason', 'token=' . $this->session->data['token'],'SSL');
    $data['rma_label'] = $this->url->link('catalog/shipping_label', 'token=' . $this->session->data['token'],'SSL');
    $data['text_rma'] = $this->language->get('text_rma');
    $data['text_manage_rma_status'] =  $this->language->get('text_manage_rma_status');
    $data['text_manage_rma_reason'] =  $this->language->get('text_manage_rma_reason');
    $data['text_manage_rma'] =  $this->language->get('text_manage_rma');
    $data['wk_rma_status'] = $this->config->get('wk_rma_status');

                    
		return $this->load->view('common/menu.tpl', $data);
	}
}
