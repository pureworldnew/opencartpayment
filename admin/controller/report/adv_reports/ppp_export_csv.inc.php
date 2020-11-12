<?php
	ini_set("memory_limit","256M");
	
	$results = $export_data['results'];
	if ($results) {

	$csv_delimiter = strtr($export_csv_delimiter, array(
		'comma'			=> ",",
		'semi'			=> ";",
		'tab'			=> "\t"
	));
	$csv_enclosed = '"';
	$csv_row = "\n";

	if ($filter_report == 'all_products_with_without_orders' or $filter_report == 'products_without_orders') {	
	$export_csv = $csv_enclosed . $this->language->get('column_date_added') . $csv_enclosed;
	} elseif ($filter_report == 'products_shopping_carts' or $filter_report == 'products_wishlists') {
	$export_csv = $csv_enclosed . $this->language->get('column_date_start') . $csv_enclosed;					
	$export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_date_end') . $csv_enclosed;		
	} else {
	if ($filter_group == 'year') {
	$export_csv = $csv_enclosed . $this->language->get('column_year') . $csv_enclosed;
	} elseif ($filter_group == 'quarter') {
	$export_csv = $csv_enclosed . $this->language->get('column_year') . $csv_enclosed;				
	$export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_quarter') . $csv_enclosed;			
	} elseif ($filter_group == 'month') {
	$export_csv = $csv_enclosed . $this->language->get('column_year') . $csv_enclosed;			
	$export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_month') . $csv_enclosed;	
	} elseif ($filter_group == 'day') {
	$export_csv = $csv_enclosed . $this->language->get('column_date') . $csv_enclosed;
	} elseif ($filter_group == 'order') {
	$export_csv = $csv_enclosed . $this->language->get('column_order_order_id') . $csv_enclosed;				
	$export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_order_date_added') . $csv_enclosed;	
	} else {
	$export_csv = $csv_enclosed . $this->language->get('column_date_start') . $csv_enclosed;					
	$export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_date_end') . $csv_enclosed;	
	}
	}
	
	if ($filter_report == 'products_shopping_carts' or $filter_report == 'products_wishlists') {
		
	in_array('scw_id', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_id') . $csv_enclosed : '';
	in_array('scw_sku', $advppp_settings_scw_columns) ?  $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_sku') . $csv_enclosed : '';
	in_array('scw_name', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_pname') . $csv_enclosed : '';
	if ($filter_report == 'products_shopping_carts') {
	in_array('scw_name', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_poption') . $csv_enclosed : '';
	}
	in_array('scw_model', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_model') . $csv_enclosed : '';
	in_array('scw_category', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_category') . $csv_enclosed : '';	
	in_array('scw_manufacturer', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_manufacturer') . $csv_enclosed : '';
	in_array('scw_supplier', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_supplier') . $csv_enclosed : '';
	in_array('scw_attribute', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_attribute') . $csv_enclosed : '';	
	in_array('scw_status', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_status') . $csv_enclosed : '';	
	in_array('scw_location', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_location') . $csv_enclosed : '';	
	in_array('scw_tax_class', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_tax_class') . $csv_enclosed : '';	
	in_array('scw_price', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_price') . $csv_enclosed : '';	
	in_array('scw_cost', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_cost') . $csv_enclosed : '';	
	in_array('scw_profit', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_profit') . $csv_enclosed : '';
	in_array('scw_profit_margin', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_profit_margin') . $csv_enclosed : '';
	in_array('scw_profit_markup', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_profit_markup') . $csv_enclosed : '';
	in_array('scw_viewed', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_viewed') . $csv_enclosed : '';
	in_array('scw_stock_quantity', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_stock_quantity') . $csv_enclosed : '';
	if ($filter_report == 'products_shopping_carts') {
	in_array('scw_cart_quantity', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_cart_quantity') . $csv_enclosed : '';		
	} elseif ($filter_report == 'products_wishlists') {
	in_array('scw_wishlist_quantity', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_wishlist_quantity') . $csv_enclosed : '';		
	}
	in_array('scw_customer_id', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_customer_cust_id') . $csv_enclosed : '';
	in_array('scw_date_added', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_customer_date_added') . $csv_enclosed : '';
	in_array('scw_customer_name', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_order_customer') . $csv_enclosed : '';
	in_array('scw_customer_email', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_order_email') . $csv_enclosed : '';
	in_array('scw_customer_telephone', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_customer_telephone') . $csv_enclosed : '';
	in_array('scw_customer_group', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_order_customer_group') . $csv_enclosed : '';
	in_array('scw_billing_company', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_company') . $csv_enclosed : '';
	in_array('scw_billing_address_1', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_address_1') . $csv_enclosed : '';
	in_array('scw_billing_address_2', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_address_2') . $csv_enclosed : '';
	in_array('scw_billing_city', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_city') . $csv_enclosed : '';
	in_array('scw_billing_zone', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_region_state') . $csv_enclosed : '';
	in_array('scw_billing_postcode', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_postcode') . $csv_enclosed : '';
	in_array('scw_billing_country', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_country') . $csv_enclosed : '';

	} elseif ($filter_report == 'products_without_orders') {

	in_array('pnp_id', $advppp_settings_pnp_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_id') . $csv_enclosed : '';
	in_array('pnp_sku', $advppp_settings_pnp_columns) ?  $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_sku') . $csv_enclosed : '';
	in_array('pnp_name', $advppp_settings_pnp_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_pname') . $csv_enclosed : '';
	in_array('pnp_model', $advppp_settings_pnp_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_model') . $csv_enclosed : '';
	in_array('pnp_category', $advppp_settings_pnp_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_category') . $csv_enclosed : '';	
	in_array('pnp_manufacturer', $advppp_settings_pnp_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_manufacturer') . $csv_enclosed : '';	
	in_array('pnp_supplier', $advppp_settings_pnp_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_supplier') . $csv_enclosed : '';	
	in_array('pnp_attribute', $advppp_settings_pnp_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_attribute') . $csv_enclosed : '';	
	in_array('pnp_status', $advppp_settings_pnp_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_status') . $csv_enclosed : '';	
	in_array('pnp_location', $advppp_settings_pnp_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_location') . $csv_enclosed : '';	
	in_array('pnp_tax_class', $advppp_settings_pnp_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_tax_class') . $csv_enclosed : '';	
	in_array('pnp_price', $advppp_settings_pnp_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_price') . $csv_enclosed : '';	
	in_array('pnp_cost', $advppp_settings_pnp_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_cost') . $csv_enclosed : '';	
	in_array('pnp_profit', $advppp_settings_pnp_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_profit') . $csv_enclosed : '';
	in_array('pnp_profit_margin', $advppp_settings_pnp_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_profit_margin') . $csv_enclosed : '';
	in_array('pnp_profit_markup', $advppp_settings_pnp_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_profit_markup') . $csv_enclosed : '';
	in_array('pnp_viewed', $advppp_settings_pnp_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_viewed') . $csv_enclosed : '';
	in_array('pnp_stock_quantity', $advppp_settings_pnp_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_stock_quantity') . $csv_enclosed : '';
	
	} elseif ($filter_report == 'manufacturers' or $filter_report == 'categories' or $filter_report == 'suppliers') {
		
	if ($filter_report == 'manufacturers') {
	$export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_manufacturer') . $csv_enclosed;
	} elseif ($filter_report == 'categories') {
	$export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_category') . $csv_enclosed;	
	} elseif ($filter_report == 'suppliers') {
	$export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_supplier') . $csv_enclosed;	
	}
	in_array('cm_sold_quantity', $advppp_settings_cm_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_sold_quantity') . $csv_enclosed : '';
	in_array('cm_sold_percent', $advppp_settings_cm_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_sold_percent') . $csv_enclosed : '';
	in_array('cm_total_excl_vat', $advppp_settings_cm_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_total_excl_vat') . $csv_enclosed : '';
	in_array('cm_total_tax', $advppp_settings_cm_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_total_tax') . $csv_enclosed : '';
	in_array('cm_total_incl_vat', $advppp_settings_cm_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_total_incl_vat') . $csv_enclosed : '';
	in_array('cm_app', $advppp_settings_cm_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_app') . $csv_enclosed : '';
	in_array('cm_discount', $advppp_settings_cm_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_product_discount') . $csv_enclosed : '';
	in_array('cm_refunds', $advppp_settings_cm_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_product_refunds') . $csv_enclosed : '';
	in_array('cm_reward_points', $advppp_settings_cm_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_product_reward_points') . $csv_enclosed : '';
	in_array('cm_total_sales', $advppp_settings_cm_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_total_sales') . $csv_enclosed : '';	
	in_array('cm_total_costs', $advppp_settings_cm_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_total_costs') . $csv_enclosed : '';
	in_array('cm_total_profit', $advppp_settings_cm_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_total_profit') . $csv_enclosed : '';
	in_array('cm_total_profit_margin', $advppp_settings_cm_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_total_profit_margin') . $csv_enclosed : '';
	in_array('cm_total_profit_markup', $advppp_settings_cm_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_total_profit_markup') . $csv_enclosed : '';
	
	} elseif ($filter_report == 'products_options') {
		
	$export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_option') . $csv_enclosed;
	$export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_sold_quantity') . $csv_enclosed;
	$export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_sold_percent') . $csv_enclosed;
	
	} else {
	
	in_array('mv_id', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_id') . $csv_enclosed : '';
	in_array('mv_sku', $advppp_settings_mv_columns) ?  $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_sku') . $csv_enclosed : '';
	in_array('mv_name', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_pname') . $csv_enclosed : '';
	if ($filter_report == 'products_purchased_with_options' or $filter_report == 'products_abandoned_orders') {
	in_array('mv_name', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_poption') . $csv_enclosed : '';
	}
	in_array('mv_model', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_model') . $csv_enclosed : '';
	in_array('mv_category', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_category') . $csv_enclosed : '';	
	in_array('mv_manufacturer', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_manufacturer') . $csv_enclosed : '';	
	in_array('mv_supplier', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_supplier') . $csv_enclosed : '';	
	in_array('mv_attribute', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_attribute') . $csv_enclosed : '';	
	in_array('mv_status', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_status') . $csv_enclosed : '';	
	in_array('mv_location', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_location') . $csv_enclosed : '';	
	in_array('mv_tax_class', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_tax_class') . $csv_enclosed : '';	
	in_array('mv_price', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_price') . $csv_enclosed : '';	
	in_array('mv_cost', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_cost') . $csv_enclosed : '';	
	in_array('mv_profit', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_profit') . $csv_enclosed : '';
	in_array('mv_profit_margin', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_profit_margin') . $csv_enclosed : '';
	in_array('mv_profit_markup', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_profit_markup') . $csv_enclosed : '';
	in_array('mv_viewed', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_viewed') . $csv_enclosed : '';
	in_array('mv_stock_quantity', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_stock_quantity') . $csv_enclosed : '';	
	in_array('mv_sold_quantity', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_sold_quantity') . $csv_enclosed : '';
	in_array('mv_sold_percent', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_sold_percent') . $csv_enclosed : '';
	in_array('mv_total_excl_vat', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_total_excl_vat') . $csv_enclosed : '';
	in_array('mv_total_tax', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_total_tax') . $csv_enclosed : '';
	in_array('mv_total_incl_vat', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_total_incl_vat') . $csv_enclosed : '';
	in_array('mv_app', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_app') . $csv_enclosed : '';
	in_array('mv_discount', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_product_discount') . $csv_enclosed : '';
	in_array('mv_refunds', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_product_refunds') . $csv_enclosed : '';
	in_array('mv_reward_points', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_product_reward_points') . $csv_enclosed : '';
	in_array('mv_total_sales', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_total_sales') . $csv_enclosed : '';	
	in_array('mv_total_costs', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_total_costs') . $csv_enclosed : '';
	in_array('mv_total_profit', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_total_profit') . $csv_enclosed : '';
	in_array('mv_total_profit_margin', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_total_profit_margin') . $csv_enclosed : '';
	in_array('mv_total_profit_markup', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_total_profit_markup') . $csv_enclosed : '';
	}
	$export_csv .= $csv_row;

	foreach ($results as $result) {
	if ($filter_report == 'all_products_with_without_orders' or $filter_report == 'products_without_orders') {
	$export_csv .= $csv_enclosed . $result['date_added'] . $csv_enclosed;
	} elseif ($filter_report == 'products_shopping_carts' or $filter_report == 'products_wishlists') {
	$export_csv .= $csv_enclosed . $result['date_start'] . $csv_enclosed;					
	$export_csv .= $csv_delimiter . $csv_enclosed . $result['date_end'] . $csv_enclosed;
	} else {
	if ($filter_group == 'year') {				
	$export_csv .= $csv_enclosed . $result['year'] . $csv_enclosed;
	} elseif ($filter_group == 'quarter') {
	$export_csv .= $csv_enclosed . $result['year'] . $csv_enclosed;				
	$export_csv .= $csv_delimiter . $csv_enclosed . 'Q' . $result['quarter'] . $csv_enclosed;			
	} elseif ($filter_group == 'month') {
	$export_csv .= $csv_enclosed . $result['year'] . $csv_enclosed;			
	$export_csv .= $csv_delimiter . $csv_enclosed . $result['month'] . $csv_enclosed;	
	} elseif ($filter_group == 'day') {
	$export_csv .= $csv_enclosed . $result['date_start'] . $csv_enclosed;
	} elseif ($filter_group == 'order') {
	$export_csv .= $csv_enclosed . $result['order_id'] . $csv_enclosed;				
	$export_csv .= $csv_delimiter . $csv_enclosed . $result['date_start'] . $csv_enclosed;	
	} else {
	$export_csv .= $csv_enclosed . $result['date_start'] . $csv_enclosed;					
	$export_csv .= $csv_delimiter . $csv_enclosed . $result['date_end'] . $csv_enclosed;	
	}
	}
	
	if ($filter_report == 'products_shopping_carts' or $filter_report == 'products_wishlists') {

	in_array('scw_id', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['product_id'] . $csv_enclosed : '';
	in_array('scw_sku', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['sku'] . $csv_enclosed : '';
	in_array('scw_name', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['name'] . $csv_enclosed : '';
	if ($filter_report == 'products_shopping_carts') {	
	if ($result['option']) {	
	$options = '';
	foreach ($result['option'] as $option) {
	$options .= $option['name'].': '.$option['value'].'; ';
	}
	in_array('scw_name', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . rtrim($options, "; ") . $csv_enclosed : '';
	} else {
	in_array('scw_name', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';					
	}				
	}
	in_array('scw_model', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['model'] . $csv_enclosed : '';
	in_array('scw_category', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . html_entity_decode($result['categories']) . $csv_enclosed : '';
	in_array('scw_manufacturer', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . html_entity_decode($result['manufacturers']) . $csv_enclosed : '';
	in_array('scw_supplier', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . html_entity_decode($result['suppliers']) . $csv_enclosed : '';
	in_array('scw_attribute', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . html_entity_decode(str_replace('<br>','; ',$result['attribute'])) . $csv_enclosed : '';	
	in_array('scw_status', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['status'] . $csv_enclosed : '';	
	in_array('scw_location', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['location'] . $csv_enclosed : '';	
	in_array('scw_tax_class', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['tax_class'] . $csv_enclosed : '';	
	in_array('scw_price', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['price_raw'], 2) . $csv_enclosed : '';	
	in_array('scw_cost', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round(-$result['cost_raw'], 2) . $csv_enclosed : '';	
	in_array('scw_profit', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['profit_raw'], 2) . $csv_enclosed : '';
	in_array('scw_profit_margin', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['profit_margin'] . $csv_enclosed : '';	
	in_array('scw_profit_markup', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['profit_markup'] . $csv_enclosed : '';	
	in_array('scw_viewed', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['viewed'] . $csv_enclosed : '';
	if ($filter_report == 'products_shopping_carts') {	
	if ($result['option']) {	
	$oquantity = '';
	foreach ($result['option'] as $option) {
	$oquantity .= $option['quantity'].'; ';
	}
	in_array('scw_stock_quantity', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['stock_quantity'] . " [" . rtrim($oquantity, "; ") . "]" . $csv_enclosed : '';				
	} else {
	in_array('scw_stock_quantity', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';					
	}
	} else {
	in_array('scw_stock_quantity', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['stock_quantity'] . $csv_enclosed : '';
	}
	if ($filter_report == 'products_shopping_carts') {
	in_array('scw_cart_quantity', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cart_quantity'] . $csv_enclosed : '';					
	}
	if ($filter_report == 'products_wishlists') {
	in_array('scw_wishlist_quantity', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['wishlist_quantity'] . $csv_enclosed : '';					
	}
	if ($result['customer']) {	
	foreach ($result['customer'] as $customer) {
	in_array('scw_customer_id', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $customer['customer_id'] . $csv_enclosed : '';
	in_array('scw_date_added', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $customer['date_added'] . $csv_enclosed : '';
	in_array('scw_customer_name', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $customer['customer_name'] . $csv_enclosed : '';
	in_array('scw_customer_email', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $customer['email'] . $csv_enclosed : '';
	in_array('scw_customer_telephone', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $customer['telephone'] . $csv_enclosed : '';
	in_array('scw_customer_group', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $customer['customer_group'] . $csv_enclosed : '';
	in_array('scw_billing_company', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $customer['company'] . $csv_enclosed : '';
	in_array('scw_billing_address_1', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $customer['address_1'] . $csv_enclosed : '';
	in_array('scw_billing_address_2', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $customer['address_2'] . $csv_enclosed : '';
	in_array('scw_billing_city', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $customer['city'] . $csv_enclosed : '';
	in_array('scw_billing_zone', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $customer['zone'] . $csv_enclosed : '';
	in_array('scw_billing_postcode', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $customer['postcode'] . $csv_enclosed : '';
	in_array('scw_billing_country', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $customer['country'] . $csv_enclosed : '';
	$export_csv .= $csv_row;
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;					
	$export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed;		
	in_array('scw_id', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('scw_sku', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('scw_name', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	if ($filter_report == 'products_shopping_carts') {	
	in_array('scw_name', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';					
	}
	in_array('scw_model', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('scw_category', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('scw_manufacturer', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('scw_supplier', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('scw_attribute', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('scw_status', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('scw_location', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('scw_tax_class', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('scw_price', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('scw_cost', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('scw_profit', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('scw_profit_margin', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('scw_profit_markup', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('scw_viewed', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('scw_stock_quantity', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';		
	if ($filter_report == 'products_shopping_carts') {
	in_array('scw_cart_quantity', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';					
	}
	if ($filter_report == 'products_wishlists') {
	in_array('scw_wishlist_quantity', $advppp_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';					
	}	
	}
	}
	
	} elseif ($filter_report == 'products_without_orders') {

	in_array('pnp_id', $advppp_settings_pnp_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['product_id'] . $csv_enclosed : '';
	in_array('pnp_sku', $advppp_settings_pnp_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['sku'] . $csv_enclosed : '';
	in_array('pnp_name', $advppp_settings_pnp_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['name'] . $csv_enclosed : '';
	in_array('pnp_model', $advppp_settings_pnp_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['model'] . $csv_enclosed : '';
	in_array('pnp_category', $advppp_settings_pnp_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . html_entity_decode($result['categories']) . $csv_enclosed : '';
	in_array('pnp_manufacturer', $advppp_settings_pnp_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . html_entity_decode($result['manufacturers']) . $csv_enclosed : '';
	in_array('pnp_supplier', $advppp_settings_pnp_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . html_entity_decode($result['suppliers']) . $csv_enclosed : '';
	in_array('pnp_attribute', $advppp_settings_pnp_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . html_entity_decode(str_replace('<br>','; ',$result['attribute'])) . $csv_enclosed : '';	
	in_array('pnp_status', $advppp_settings_pnp_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['status'] . $csv_enclosed : '';	
	in_array('pnp_location', $advppp_settings_pnp_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['location'] . $csv_enclosed : '';	
	in_array('pnp_tax_class', $advppp_settings_pnp_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['tax_class'] . $csv_enclosed : '';	
	in_array('pnp_price', $advppp_settings_pnp_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['price_raw'], 2) . $csv_enclosed : '';	
	in_array('pnp_cost', $advppp_settings_pnp_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round(-$result['cost_raw'], 2) . $csv_enclosed : '';	
	in_array('pnp_profit', $advppp_settings_pnp_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['profit_raw'], 2) . $csv_enclosed : '';
	in_array('pnp_profit_margin', $advppp_settings_pnp_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['profit_margin'] . $csv_enclosed : '';	
	in_array('pnp_profit_markup', $advppp_settings_pnp_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['profit_markup'] . $csv_enclosed : '';	
	in_array('pnp_viewed', $advppp_settings_pnp_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['viewed'] . $csv_enclosed : '';
	in_array('pnp_stock_quantity', $advppp_settings_pnp_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['stock_quantity'] . $csv_enclosed : '';

	} elseif ($filter_report == 'manufacturers' or $filter_report == 'categories' or $filter_report == 'suppliers') {

	if ($filter_report == 'manufacturers') {
	$export_csv .= $csv_delimiter . $csv_enclosed . html_entity_decode($result['manufacturers']) . $csv_enclosed;	
	} elseif ($filter_report == 'categories') {
	$export_csv .= $csv_delimiter . $csv_enclosed . html_entity_decode($result['categories']) . $csv_enclosed;
	} elseif ($filter_report == 'suppliers') {
	$export_csv .= $csv_delimiter . $csv_enclosed . html_entity_decode($result['suppliers']) . $csv_enclosed;
	}
	in_array('cm_sold_quantity', $advppp_settings_cm_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['sold_quantity'] . $csv_enclosed : '';
	in_array('cm_sold_percent', $advppp_settings_cm_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . ($result['sold_quantity'] != 0 ? round(100 * ($result['sold_quantity'] / $result['sold_quantity_total']), 2) : round((100), 2)) . '%' . $csv_enclosed : '';
	in_array('cm_total_excl_vat', $advppp_settings_cm_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['total_excl_vat_raw'], 2) . $csv_enclosed : '';
	in_array('cm_total_tax', $advppp_settings_cm_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['total_tax_raw'], 2) . $csv_enclosed : '';
	in_array('cm_total_incl_vat', $advppp_settings_cm_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['total_incl_vat_raw'], 2) . $csv_enclosed : '';
	in_array('cm_app', $advppp_settings_cm_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['app_raw'], 2) . $csv_enclosed : '';
	in_array('cm_discount', $advppp_settings_cm_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['discount_raw'], 2) . $csv_enclosed : '';
	if ($this->config->get('advppp' . $user_id . '_formula_6')) {
	in_array('cm_refunds', $advppp_settings_cm_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round(-$result['refunds_raw'], 2) . $csv_enclosed : '';
	} else {
	in_array('cm_refunds', $advppp_settings_cm_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['refunds_raw'], 2) . $csv_enclosed : '';
	}		
	in_array('cm_reward_points', $advppp_settings_cm_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['reward_points'] . $csv_enclosed : '';	
	in_array('cm_total_sales', $advppp_settings_cm_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['total_sales_raw'], 2) . $csv_enclosed : '';	
	in_array('cm_total_costs', $advppp_settings_cm_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round(-$result['total_costs_raw'], 2) . $csv_enclosed : '';	
	in_array('cm_total_profit', $advppp_settings_cm_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['total_profit_raw'], 2) . $csv_enclosed : '';
	if ($result['sold_quantity'] > 0) {
	in_array('cm_total_profit_margin', $advppp_settings_cm_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['total_profit_margin'] . $csv_enclosed : '';				
	} else {
	in_array('cm_total_profit_margin', $advppp_settings_cm_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round((0), 2) . '%' . $csv_enclosed : '';
	}
	if ($result['sold_quantity'] > 0) {
	in_array('cm_total_profit_markup', $advppp_settings_cm_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['total_profit_markup'] . $csv_enclosed : '';				
	} else {
	in_array('cm_total_profit_markup', $advppp_settings_cm_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round((0), 2) . '%' . $csv_enclosed : '';
	}
	
	} elseif ($filter_report == 'products_options') {
		
	$export_csv .= $csv_delimiter . $csv_enclosed . $result['option_name'] . $csv_enclosed;
	$export_csv .= $csv_delimiter . $csv_enclosed . $result['sold_quantity'] . $csv_enclosed;
	$export_csv .= $csv_delimiter . $csv_enclosed . ($result['sold_quantity'] != 0 ? round(100 * ($result['sold_quantity'] / $result['sold_quantity_total']), 2) : round((100), 2)) . '%' . $csv_enclosed;
	
	} else {
				
	in_array('mv_id', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['product_id'] . $csv_enclosed : '';
	in_array('mv_sku', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['sku'] . $csv_enclosed : '';
	in_array('mv_name', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['name'] . $csv_enclosed : '';
	if ($filter_report == 'products_purchased_with_options' or $filter_report == 'products_abandoned_orders') {	
	if ($result['option']) {	
	$options = '';
	foreach ($result['option'] as $option) {
	$options .= $option['name'].': '.$option['value'].'; ';
	}
	in_array('mv_name', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . rtrim($options, "; ") . $csv_enclosed : '';
	} else {
	in_array('mv_name', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';					
	}	
	}	
	in_array('mv_model', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['model'] . $csv_enclosed : '';
	in_array('mv_category', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . html_entity_decode($result['categories']) . $csv_enclosed : '';
	in_array('mv_manufacturer', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . html_entity_decode($result['manufacturers']) . $csv_enclosed : '';
	in_array('mv_supplier', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . html_entity_decode($result['suppliers']) . $csv_enclosed : '';
	in_array('mv_attribute', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . html_entity_decode(str_replace('<br>','; ',$result['attribute'])) . $csv_enclosed : '';	
	in_array('mv_status', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['status'] . $csv_enclosed : '';	
	in_array('mv_location', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['location'] . $csv_enclosed : '';	
	in_array('mv_tax_class', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['tax_class'] . $csv_enclosed : '';	
	in_array('mv_price', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['price_raw'], 2) . $csv_enclosed : '';	
	in_array('mv_cost', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round(-$result['cost_raw'], 2) . $csv_enclosed : '';	
	in_array('mv_profit', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['profit_raw'], 2) . $csv_enclosed : '';
	in_array('mv_profit_margin', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['profit_margin'] . $csv_enclosed : '';	
	in_array('mv_profit_markup', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['profit_markup'] . $csv_enclosed : '';	
	in_array('mv_viewed', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['viewed'] . $csv_enclosed : '';
	if ($filter_report == 'products_purchased_with_options' or $filter_report == 'products_abandoned_orders') {
	if ($result['option']) {	
	$oquantity = '';
	foreach ($result['option'] as $option) {
	$oquantity .= $option['quantity'].'; ';
	}
	in_array('mv_stock_quantity', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['stock_quantity'] . " [" . rtrim($oquantity, "; ") . "]" . $csv_enclosed : '';				
	} else {
	in_array('mv_stock_quantity', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';					
	}
	} else {
	in_array('mv_stock_quantity', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['stock_quantity'] . $csv_enclosed : '';
	}
	in_array('mv_sold_quantity', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['sold_quantity'] . $csv_enclosed : '';
	in_array('mv_sold_percent', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . ($result['sold_quantity'] != 0 ? round(100 * ($result['sold_quantity'] / $result['sold_quantity_total']), 2) : round((100), 2)) . '%' . $csv_enclosed : '';
	in_array('mv_total_excl_vat', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['total_excl_vat_raw'], 2) . $csv_enclosed : '';
	in_array('mv_total_tax', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['total_tax_raw'], 2) . $csv_enclosed : '';
	in_array('mv_total_incl_vat', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['total_incl_vat_raw'], 2) . $csv_enclosed : '';
	in_array('mv_app', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['app_raw'], 2) . $csv_enclosed : '';
	in_array('mv_discount', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['discount_raw'], 2) . $csv_enclosed : '';
	if ($this->config->get('advppp' . $user_id . '_formula_6')) {
	in_array('mv_refunds', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round(-$result['refunds_raw'], 2) . $csv_enclosed : '';
	} else {
	in_array('mv_refunds', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['refunds_raw'], 2) . $csv_enclosed : '';
	}		
	in_array('mv_reward_points', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['reward_points'] . $csv_enclosed : '';	
	in_array('mv_total_sales', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['total_sales_raw'], 2) . $csv_enclosed : '';	
	in_array('mv_total_costs', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round(-$result['total_costs_raw'], 2) . $csv_enclosed : '';	
	in_array('mv_total_profit', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['total_profit_raw'], 2) . $csv_enclosed : '';
	if ($result['sold_quantity'] > 0) {
	in_array('mv_total_profit_margin', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['total_profit_margin'] . $csv_enclosed : '';				
	} else {
	in_array('mv_total_profit_margin', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round((0), 2) . '%' . $csv_enclosed : '';
	}
	if ($result['sold_quantity'] > 0) {
	in_array('mv_total_profit_markup', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['total_profit_markup'] . $csv_enclosed : '';				
	} else {
	in_array('mv_total_profit_markup', $advppp_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round((0), 2) . '%' . $csv_enclosed : '';
	}
	}
	$export_csv .= $csv_row;
	}

	if (!isset($_GET['cron'])) {
		$filename = "products_profit_report_".date($this->config->get('advppp' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A");
		header('Pragma: public');
		header('Expires: 0');
		header('Content-Description: File Transfer');
		header('Content-Type: text/csv; charset=utf-8');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');		
		header('Content-Disposition: attachment; filename='.$filename.".csv");
		print chr(255) . chr(254) . mb_convert_encoding($export_csv, 'UTF-16LE', 'UTF-8');			
	} else if (isset($_GET['cron'])) {
		$file_path_parts = explode('/', $file_save_path);
		$file_path = dirname(DIR_APPLICATION);
		foreach ($file_path_parts as $file_path_part) {
			$file_path .= '/' . $file_path_part;
			if (!file_exists($file_path)) {
				mkdir($file_path, 0755);
				if (file_exists(DIR_DOWNLOAD . 'index.html')) {
					copy(DIR_DOWNLOAD  . 'index.html', $file_path . DIRECTORY_SEPARATOR . 'index.html');
				}
			}
		}
		
		if ($this->request->server['HTTPS']) {
			$server = HTTPS_CATALOG;
		} else {
			$server = HTTP_CATALOG;
		}
		
		$filename = $file_name."_".date($this->config->get('advppp' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A").".csv";
		$file_to_download = $server . $file_save_path . '/' . $file_name."_".date($this->config->get('advppp' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A").".csv";
		$file = $file_path . '/' . $file_name."_".date($this->config->get('advppp' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A").".csv";		
		
		file_put_contents($file, $export_csv);
		
		$message  = '<html dir="ltr" lang="en">' . "\n";
		$message .= '  <head>' . "\n";
		$message .= '    <title>' . $this->language->get('text_email_subject') . '</title>' . "\n";
		$message .= '    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
		$message .= '  </head>' . "\n";
		$message .= '  <body>' . "\n";
		if ($export_file == 'save_on_server') {			
			$message .= '<p>' . $this->language->get('text_email_message_save_file') . ' <a href="' . $file_to_download . '">' . $filename . '</a>.</p><br />' . "\n";
		} else if ($export_file == 'send_to_email') {
			$message .= '<p>' . $this->language->get('text_email_message_send_file') . '</p><br />' . "\n";
		}
		$message .= '<p><b>' . $this->config->get('config_name') . '</b><br />' . "\n";
		$message .= $this->config->get('config_address') . '</p>' . "\n";			
		$message .= '</body>' . "\n";
		$message .= '</html>' . "\n";

		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
	
		$mail->setTo($email);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
		$mail->setSubject($this->language->get('text_email_subject'));
		if ($export_file == 'save_on_server') {			
			$mail->setHtml($message);
		} else if ($export_file == 'send_to_email') {
		$mail->setHtml($message);
		$mail->addAttachment($file);
		}			
		$mail->send();
	}			
	exit;
	}
?>