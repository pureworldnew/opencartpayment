<?php
	ini_set("memory_limit","256M");
	$results = $export_data['results'];
	if ($results) {
	
	$c = 0;
	$export_html ="<html><head>";
	$export_html .="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
	$export_html .="</head>";
	$export_html .="<body>";
	$export_html .="<style type='text/css'>
	.list_criteria {
		border-collapse: collapse;
		width: 100%;
		border-top: 1px solid #DBE5F1;
		border-left: 1px solid #DBE5F1;	
		padding: 3px;		
		font-family: Arial, Helvetica, sans-serif;
		font-size: 12px;
		background: url('$logo') 5px 18px no-repeat #DBE5F1;
		background-size: 268px 50px;
	}
	.list_criteria td {
		border-right: 1px solid #DBE5F1;
		border-bottom: 1px solid #DBE5F1;	
	}
	
	.list_main {
		border-collapse: collapse;
		width: 100%;
		border-top: 1px solid #DDDDDD;
		border-left: 1px solid #DDDDDD;	
		font-family: Arial, Helvetica, sans-serif;
		font-size: 12px;
	}
	.list_main td {
		border-right: 1px solid #DDDDDD;
		border-bottom: 1px solid #DDDDDD;	
	}
	.list_main thead td {
		background-color: #F0F0F0;
		padding: 3px;
		font-weight: bold;
	}
	.list_main tbody a {
		text-decoration: none;
	}
	.list_main tbody td {
		vertical-align: middle;
		padding: 3px;
	}

	.list_detail {
		border-collapse: collapse;
		width: 100%;
		border-top: 1px solid #DDDDDD;
		border-left: 1px solid #DDDDDD;
		font-family: Arial, Helvetica, sans-serif;	
		margin-top: 5px;
		margin-bottom: 5px;
	}
	.list_detail td {
		border-right: 1px solid #DDDDDD;
		border-bottom: 1px solid #DDDDDD;
	}
	.list_detail thead td {
		background-color: #f5f5f5;
		padding: 0px 3px;
		font-size: 10px;
		font-weight: bold;	
	}
	.list_detail tbody td {
		padding: 0px 3px;
		font-size: 10px;	
	}
	
	.sales {
		background-color: #DCFFB9;	
	}	
	.cost {
		background-color: #ffd7d7;	
	}	
	.plusprofit {
		background-color: #c4d9ee;
		font-weight: bold;
	}
	.minusprofit {
		background-color: #F99;
		font-weight: bold;	
	}
	.total {
		background-color: #E7EFEF;
		color: #003A88;
		font-weight: bold;
	}	
	.total_sales {
		background-color: #DCFFB9;
		color: #003A88;
		font-weight: bold;
	}	
	.total_cost {
		background-color: #ffd7d7;
		color: #003A88;
		font-weight: bold;
	}	
	.total_plusprofit {
		background-color: #c4d9ee;
		color: #003A88;	
		font-weight: bold;
	}
	.total_minusprofit {
		background-color: #F99;
		color: #003A88;	
		font-weight: bold;	
	}	
	</style>";
	if ($export_logo_criteria) {
	$export_html .="<table class='list_criteria'>";
	$export_html .="<tr>";
	$export_html .= "<td colspan='3' align='center'>".$this->language->get('text_report_date').": ".date($this->config->get('advppp' . $user_id . '_hour_format') == '24' ? "Y-m-d H:i:s" : "Y-m-d h:i:s A")."</td><td></td></tr>";
	$export_html .="<tr>";
	$export_html .= "<td colspan='3' align='center' style='height:50px; font-size:24; font-weight:bold;'>".$this->language->get('adv_ext_name')."</td><td width='1%' align='left' valign='top' nowrap='nowrap'><b>".$this->config->get('config_name')."</b> <br>".$this->config->get('config_address')." <br>".$this->language->get('text_email')."".$this->config->get('config_email')." <br>".$this->language->get('text_telephone')."".$this->config->get('config_telephone')." </td></tr>";
	$export_html .="<tr>";
	$export_html .= "<td align='right' valign='top' style='height:50px; width:150px; font-weight:bold;'>".$this->language->get('text_report_criteria')." </td>";	
			$filter_criteria = "";
			if ($filter_report) {	
				if ($filter_report == 'all_products_with_without_orders') {
					$filter_report_name = $this->language->get('text_all_products')." ".$this->language->get('text_with_without_orders');
				} elseif ($filter_report == 'products_purchased_without_options') {
					$filter_report_name = $this->language->get('text_products_purchased')." ".$this->language->get('text_without_options');
				} elseif ($filter_report == 'products_purchased_with_options') {
					$filter_report_name = $this->language->get('text_products_purchased')." ".$this->language->get('text_with_options');
				} elseif ($filter_report == 'new_products_purchased') {
					$filter_report_name = $this->language->get('text_new_products_purchased');
				} elseif ($filter_report == 'old_products_purchased') {
					$filter_report_name = $this->language->get('text_old_products_purchased');
				} elseif ($filter_report == 'products_without_orders') {
					$filter_report_name = $this->language->get('text_products')." ".$this->language->get('text_without_orders');
				} elseif ($filter_report == 'products_options') {
					$filter_report_name = $this->language->get('text_product_options')." ".$this->language->get('text_products_options');
				} elseif ($filter_report == 'categories') {
					$filter_report_name = $this->language->get('text_categories');						
				} elseif ($filter_report == 'manufacturers') {
					$filter_report_name = $this->language->get('text_manufacturers');
				} elseif ($filter_report == 'suppliers') {
					$filter_report_name = $this->language->get('text_suppliers');						
				} elseif ($filter_report == 'products_abandoned_orders') {
					$filter_report_name = $this->language->get('text_products_abandoned_orders');					
				} elseif ($filter_report == 'products_shopping_carts') {
					$filter_report_name = $this->language->get('text_products_shopping_carts');					
				} elseif ($filter_report == 'products_wishlists') {
					$filter_report_name = $this->language->get('text_products_wishlists');					
				}				
				$filter_criteria .= $this->language->get('entry_report')." ".$filter_report_name."; ";
			}
			if ($filter_details) {
				if ($filter_details == 'no_details') {
					$filter_details_name = $this->language->get('text_no_details');
				} elseif ($filter_details == 'basic_details') {
					$filter_details_name = $this->language->get('text_basic_details');
				} elseif ($filter_details == 'all_details_products') {
					$filter_details_name = $this->language->get('text_all_details')." ".$this->language->get('text_all_details_products');				
				} elseif ($filter_details == 'all_details_orders') {
					$filter_details_name = $this->language->get('text_all_details')." ".$this->language->get('text_all_details_orders');		
				}				
				$filter_criteria .= $this->language->get('entry_show_details')." ".$filter_details_name."; ";
			}
			if ($filter_group) {	
				if ($filter_group == 'no_group') {
					$filter_group_name = $this->language->get('text_no_group');
				} elseif ($filter_group == 'year') {
					$filter_group_name = $this->language->get('text_year');
				} elseif ($filter_group == 'quarter') {
					$filter_group_name = $this->language->get('text_quarter');	
				} elseif ($filter_group == 'month') {
					$filter_group_name = $this->language->get('text_month');
				} elseif ($filter_group == 'week') {
					$filter_group_name = $this->language->get('text_week');
				} elseif ($filter_group == 'day') {
					$filter_group_name = $this->language->get('text_day');
				} elseif ($filter_group == 'order') {
					$filter_group_name = $this->language->get('text_order');					
				}				
				$filter_criteria .= $this->language->get('entry_group')." ".$filter_group_name."; ";
			}
			if ($filter_sort) {	
				if ($filter_sort == 'date') {
					$filter_sort_name = $this->language->get('column_date');
				} elseif ($filter_sort == 'id') {
					$filter_sort_name = $this->language->get('column_id');
				} elseif ($filter_sort == 'sku') {
					$filter_sort_name = $this->language->get('column_sku');	
				} elseif ($filter_sort == 'name') {
					$filter_sort_name = $this->language->get('column_prod_name');
				} elseif ($filter_sort == 'model') {
					$filter_sort_name = $this->language->get('column_model');
				} elseif ($filter_sort == 'category') {
					$filter_sort_name = $this->language->get('column_category');
				} elseif ($filter_sort == 'manufacturer') {
					$filter_sort_name = $this->language->get('column_manufacturer');	
				} elseif ($filter_sort == 'supplier') {
					$filter_sort_name = $this->language->get('column_supplier');					
				} elseif ($filter_sort == 'attribute') {
					$filter_sort_name = $this->language->get('column_attribute');
				} elseif ($filter_sort == 'status') {
					$filter_sort_name = $this->language->get('column_status');	
				} elseif ($filter_sort == 'location') {
					$filter_sort_name = $this->language->get('column_location');
				} elseif ($filter_sort == 'tax_class') {
					$filter_sort_name = $this->language->get('column_tax_class');
				} elseif ($filter_sort == 'price') {
					$filter_sort_name = $this->language->get('column_price');
				} elseif ($filter_sort == 'cost') {
					$filter_sort_name = $this->language->get('column_cost');	
				} elseif ($filter_sort == 'profit') {
					$filter_sort_name = $this->language->get('column_profit');
				} elseif ($filter_sort == 'profit_margin') {
					$filter_sort_name = $this->language->get('column_profit_margin');	
				} elseif ($filter_sort == 'profit_markup') {
					$filter_sort_name = $this->language->get('column_profit_markup');						
				} elseif ($filter_sort == 'viewed') {
					$filter_sort_name = $this->language->get('column_viewed');
				} elseif ($filter_sort == 'stock_quantity') {
					$filter_sort_name = $this->language->get('column_stock_quantity');
				} elseif ($filter_sort == 'cart_quantity') {
					$filter_sort_name = $this->language->get('column_cart_quantity');
				} elseif ($filter_sort == 'wishlist_quantity') {
					$filter_sort_name = $this->language->get('column_wishlist_quantity');						
				} elseif ($filter_sort == 'sold_quantity') {
					$filter_sort_name = $this->language->get('column_sold_quantity');
				} elseif ($filter_sort == 'total_excl_vat') {
					$filter_sort_name = $this->language->get('column_prod_total_excl_vat');	
				} elseif ($filter_sort == 'total_tax') {
					$filter_sort_name = $this->language->get('column_total_tax');
				} elseif ($filter_sort == 'total_incl_vat') {
					$filter_sort_name = $this->language->get('column_prod_total_incl_vat');	
				} elseif ($filter_sort == 'app') {
					$filter_sort_name = $this->language->get('column_app');
				} elseif ($filter_sort == 'discount') {
					$filter_sort_name = $this->language->get('column_product_discount');
				} elseif ($filter_sort == 'refunds') {
					$filter_sort_name = $this->language->get('column_product_refunds');
				} elseif ($filter_sort == 'reward_points') {
					$filter_sort_name = $this->language->get('column_product_reward_points');	
				} elseif ($filter_sort == 'total_sales') {
					$filter_sort_name = $this->language->get('column_total_sales');
				} elseif ($filter_sort == 'total_costs') {
					$filter_sort_name = $this->language->get('column_total_costs');	
				} elseif ($filter_sort == 'total_profit') {
					$filter_sort_name = $this->language->get('column_total_profit');
				} elseif ($filter_sort == 'total_profit_margin') {
					$filter_sort_name = $this->language->get('column_total_profit_margin');
				} elseif ($filter_sort == 'total_profit_markup') {
					$filter_sort_name = $this->language->get('column_total_profit_markup');				
				}				
				if ($filter_order == 'asc') {
					$filter_order_name = $this->language->get('text_asc');
				} elseif ($filter_order == 'desc') {
					$filter_order_name = $this->language->get('text_desc');
				}				
				$filter_criteria .= $this->language->get('entry_sort_by')." ".$filter_sort_name." ".$filter_order_name."; ";
			}
			if ($filter_limit) {	
				$filter_criteria .= $this->language->get('entry_limit')." ".$filter_limit."; ";
			}	
			$filter_criteria .= "<br />";
			if ($filter_range) {	
				if ($filter_range == 'custom') {
					$filter_range_name = $this->language->get('stat_custom');
				} elseif ($filter_range == 'today') {
					$filter_range_name = $this->language->get('stat_today');
				} elseif ($filter_range == 'yesterday') {
					$filter_range_name = $this->language->get('stat_yesterday');	
				} elseif ($filter_range == 'week') {
					$filter_range_name = $this->language->get('stat_week');
				} elseif ($filter_range == 'month') {
					$filter_range_name = $this->language->get('stat_month');
				} elseif ($filter_range == 'quarter') {
					$filter_range_name = $this->language->get('stat_quarter');
				} elseif ($filter_range == 'year') {
					$filter_range_name = $this->language->get('stat_year');	
				} elseif ($filter_range == 'current_week') {
					$filter_range_name = $this->language->get('stat_current_week');
				} elseif ($filter_range == 'current_month') {
					$filter_range_name = $this->language->get('stat_current_month');	
				} elseif ($filter_range == 'current_quarter') {
					$filter_range_name = $this->language->get('stat_current_quarter');
				} elseif ($filter_range == 'current_year') {
					$filter_range_name = $this->language->get('stat_current_year');
				} elseif ($filter_range == 'last_week') {
					$filter_range_name = $this->language->get('stat_last_week');
				} elseif ($filter_range == 'last_month') {
					$filter_range_name = $this->language->get('stat_last_month');	
				} elseif ($filter_range == 'last_quarter') {
					$filter_range_name = $this->language->get('stat_last_quarter');
				} elseif ($filter_range == 'last_year') {
					$filter_range_name = $this->language->get('stat_last_year');
				} elseif ($filter_range == 'all_time') {
					$filter_range_name = $this->language->get('stat_all_time');					
				}				
				$filter_criteria .= $this->language->get('entry_range')." ".$filter_range_name;
				if ($filter_date_start) {	
					$filter_criteria .= " [".$this->language->get('entry_date_start')." ".$filter_date_start;
				}
				if ($filter_date_end) {	
					$filter_criteria .= ", ".$this->language->get('entry_date_end')." ".$filter_date_end."]";
				}
				$filter_criteria .= "; ";
			}
			if ($filter_order_status_id) {	
				$filter_criteria .= $this->language->get('entry_status')." ".$filter_order_status_id;
				if ($filter_status_date_start) {	
					$filter_criteria .= " [".$this->language->get('entry_date_start')." ".$filter_status_date_start;
				}
				if ($filter_status_date_end) {	
					$filter_criteria .= ", ".$this->language->get('entry_date_end')." ".$filter_status_date_end."]";
				}
				$filter_criteria .= "; ";				
			}
			if ($filter_order_id_from or $filter_order_id_to) {
				$filter_criteria .= $this->language->get('entry_order_id').": ".$filter_order_id_from."-".$filter_order_id_to."; ";
			}
			if ($filter_prod_price_min or $filter_prod_price_max) {
				$filter_criteria .= $this->language->get('entry_price_value').": ".$filter_prod_price_min."-".$filter_prod_price_max."; ";
			}
			$filter_criteria .= "<br />";
			if ($filter_store_id) {	
				$filter_criteria .= $this->language->get('entry_store')." ".$filter_store_id."; ";
			}
			if ($filter_currency) {	
				$filter_criteria .= $this->language->get('entry_currency')." ".$filter_currency."; ";
			}
			if ($filter_taxes) {	
				$filter_criteria .= $this->language->get('entry_tax')." ".$filter_taxes."; ";
			}
			if ($filter_tax_classes) {	
				$filter_criteria .= $this->language->get('entry_tax_classes')." ".$filter_tax_classes."; ";
			}
			if ($filter_geo_zones) {	
				$filter_criteria .= $this->language->get('entry_geo_zone')." ".$filter_geo_zones."; ";
			}
			if ($filter_customer_group_id) {	
				$filter_criteria .= $this->language->get('entry_customer_group')." ".$filter_customer_group_id."; ";
			}
			if ($filter_customer_name) {	
				$filter_criteria .= $this->language->get('entry_customer_name')." ".$filter_customer_name."; ";
			}
			if ($filter_customer_email) {	
				$filter_criteria .= $this->language->get('entry_customer_email')." ".$filter_customer_email."; ";
			}
			if ($filter_customer_telephone) {	
				$filter_criteria .= $this->language->get('entry_customer_telephone')." ".$filter_customer_telephone."; ";
			}
			if ($filter_ip) {	
				$filter_criteria .= $this->language->get('entry_ip')." ".$filter_ip."; ";
			}
			if ($filter_payment_company) {	
				$filter_criteria .= $this->language->get('entry_payment_company')." ".$filter_payment_company."; ";
			}
			if ($filter_payment_address) {	
				$filter_criteria .= $this->language->get('entry_payment_address')." ".$filter_payment_address."; ";
			}
			if ($filter_payment_city) {	
				$filter_criteria .= $this->language->get('entry_payment_city')." ".$filter_payment_city."; ";
			}
			if ($filter_payment_zone) {	
				$filter_criteria .= $this->language->get('entry_payment_zone')." ".$filter_payment_zone."; ";
			}
			if ($filter_payment_postcode) {	
				$filter_criteria .= $this->language->get('entry_payment_postcode')." ".$filter_payment_postcode."; ";
			}
			if ($filter_payment_country) {	
				$filter_criteria .= $this->language->get('entry_payment_country')." ".$filter_payment_country."; ";
			}
			if ($filter_payment_method) {	
				$filter_criteria .= $this->language->get('entry_payment_method')." ".$filter_payment_method."; ";
			}
			if ($filter_shipping_company) {	
				$filter_criteria .= $this->language->get('entry_shipping_company')." ".$filter_shipping_company."; ";
			}
			if ($filter_shipping_address) {	
				$filter_criteria .= $this->language->get('entry_shipping_address')." ".$filter_shipping_address."; ";
			}
			if ($filter_shipping_city) {	
				$filter_criteria .= $this->language->get('entry_shipping_city')." ".$filter_shipping_city."; ";
			}
			if ($filter_shipping_zone) {	
				$filter_criteria .= $this->language->get('entry_shipping_zone')." ".$filter_shipping_zone."; ";
			}
			if ($filter_shipping_postcode) {	
				$filter_criteria .= $this->language->get('entry_shipping_postcode')." ".$filter_shipping_postcode."; ";
			}
			if ($filter_shipping_country) {	
				$filter_criteria .= $this->language->get('entry_shipping_country')." ".$filter_shipping_country."; ";
			}
			if ($filter_shipping_method) {	
				$filter_criteria .= $this->language->get('entry_shipping_method')." ".$filter_shipping_method."; ";
			}
			if ($filter_category) {	
				$filter_criteria .= $this->language->get('entry_category')." ".$filter_category."; ";
			}
			if ($filter_manufacturer) {	
				$filter_criteria .= $this->language->get('entry_manufacturer')." ".$filter_manufacturer."; ";
			}
			if ($filter_supplier) {	
				$filter_criteria .= $this->language->get('entry_supplier')." ".$filter_supplier."; ";
			}				
			if ($filter_sku) {	
				$filter_criteria .= $this->language->get('entry_sku')." ".$filter_sku."; ";
			}
			if ($filter_product_name) {	
				$filter_criteria .= $this->language->get('entry_product')." ".$filter_product_name."; ";
			}
			if ($filter_model) {	
				$filter_criteria .= $this->language->get('entry_model')." ".$filter_model."; ";
			}
			if ($filter_option) {	
				$filter_criteria .= $this->language->get('entry_option')." ".$filter_option."; ";
			}	
			if ($filter_attribute) {	
				$filter_criteria .= $this->language->get('entry_attributes')." ".$filter_attribute."; ";
			}
			if ($filter_product_status) {	
				$filter_criteria .= $this->language->get('entry_product_status')." ".$filter_product_status."; ";
			}				
			if ($filter_location) {	
				$filter_criteria .= $this->language->get('entry_location')." ".$filter_location."; ";
			}		
			if ($filter_affiliate_name) {	
				$filter_criteria .= $this->language->get('entry_affiliate_name')." ".$filter_affiliate_name."; ";
			}		
			if ($filter_affiliate_email) {	
				$filter_criteria .= $this->language->get('entry_affiliate_email')." ".$filter_affiliate_email."; ";
			}		
			if ($filter_coupon_name) {	
				$filter_criteria .= $this->language->get('entry_coupon_name')." ".$filter_coupon_name."; ";
			}		
			if ($filter_coupon_code) {	
				$filter_criteria .= $this->language->get('entry_coupon_code')." ".$filter_coupon_code."; ";
			}	
			if ($filter_voucher_code) {	
				$filter_criteria .= $this->language->get('entry_voucher_code')." ".$filter_voucher_code."; ";
			}		
	$export_html .= "<td colspan='2' align='left' valign='top'>".$filter_criteria."</td><td></td></tr>";
	$export_html .="</table>";
	}	
	$export_html .="<table class='list_main'>";
	$export_html .="<thead>";
	$export_html .="<tr>";
	if ($filter_report == 'all_products_with_without_orders' or $filter_report == 'products_without_orders') {
	$export_html .= "<td align='left' nowrap='nowrap'>".$this->language->get('column_date_added')."</td>";
	} elseif ($filter_report == 'products_shopping_carts' or $filter_report == 'products_wishlists') {
	$c++ . $export_html .= "<td align='left' width='80' nowrap='nowrap'>".$this->language->get('column_date_start')."</td>";
	$c++ . $export_html .= "<td align='left' width='80' nowrap='nowrap'>".$this->language->get('column_date_end')."</td>";	
	} else {
	if ($filter_group == 'year') {				
	$export_html .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_year')."</td>";
	} elseif ($filter_group == 'quarter') {
	$export_html .= "<td align='left' nowrap='nowrap'>".$this->language->get('column_year')."</td>";
	$export_html .= "<td align='left' nowrap='nowrap'>".$this->language->get('column_quarter')."</td>";				
	} elseif ($filter_group == 'month') {
	$export_html .= "<td align='left' nowrap='nowrap'>".$this->language->get('column_year')."</td>";
	$export_html .= "<td align='left' nowrap='nowrap'>".$this->language->get('column_month')."</td>";
	} elseif ($filter_group == 'day') {
	$export_html .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_date')."</td>";
	} elseif ($filter_group == 'order') {
	$export_html .= "<td align='left' nowrap='nowrap'>".$this->language->get('column_order_order_id')."</td>";
	$export_html .= "<td align='left' nowrap='nowrap'>".$this->language->get('column_order_date_added')."</td>";
	} else {
	$export_html .= "<td align='left' width='80' nowrap='nowrap'>".$this->language->get('column_date_start')."</td>";
	$export_html .= "<td align='left' width='80' nowrap='nowrap'>".$this->language->get('column_date_end')."</td>";	
	}
	}
	if ($filter_report == 'products_shopping_carts' or $filter_report == 'products_wishlists') {
	in_array('scw_id', $advppp_settings_scw_columns) ? $c++ . $export_html .= "<td align='right'>".$this->language->get('column_id')."</td>" : '';
	in_array('scw_sku', $advppp_settings_scw_columns) ? $c++ . $export_html .= "<td align='left'>".$this->language->get('column_sku')."</td>" : '';
	if ($filter_report == 'products_shopping_carts') {
	in_array('scw_name', $advppp_settings_scw_columns) ? $c++ . $export_html .= "<td align='left'>".$this->language->get('column_name')."</td>" : '';
	} elseif ($filter_report == 'products_wishlists') {
	in_array('scw_name', $advppp_settings_scw_columns) ? $c++ . $export_html .= "<td align='left'>".$this->language->get('column_prod_name')."</td>" : '';
	}	
	in_array('scw_model', $advppp_settings_scw_columns) ? $c++ . $export_html .= "<td align='left'>".$this->language->get('column_model')."</td>" : '';
	in_array('scw_category', $advppp_settings_scw_columns) ? $c++ . $export_html .= "<td align='left'>".$this->language->get('column_category')."</td>" : '';
	in_array('scw_manufacturer', $advppp_settings_scw_columns) ? $c++ . $export_html .= "<td align='left'>".$this->language->get('column_manufacturer')."</td>" : '';
	in_array('scw_supplier', $advppp_settings_scw_columns) ? $c++ . $export_html .= "<td align='left'>".$this->language->get('column_supplier')."</td>" : '';
	in_array('scw_attribute', $advppp_settings_scw_columns) ? $c++ . $export_html .= "<td align='left'>".$this->language->get('column_attribute')."</td>" : '';
	in_array('scw_status', $advppp_settings_scw_columns) ? $c++ . $export_html .= "<td align='left'>".$this->language->get('column_status')."</td>" : '';
	in_array('scw_location', $advppp_settings_scw_columns) ? $c++ . $export_html .= "<td align='left'>".$this->language->get('column_location')."</td>" : '';
	in_array('scw_tax_class', $advppp_settings_scw_columns) ? $c++ . $export_html .= "<td align='left'>".$this->language->get('column_tax_class')."</td>" : '';
	in_array('scw_price', $advppp_settings_scw_columns) ? $c++ . $export_html .= "<td align='right'>".$this->language->get('column_price')."</td>" : '';
	in_array('scw_cost', $advppp_settings_scw_columns) ? $c++ . $export_html .= "<td align='right'>".$this->language->get('column_cost')."</td>" : '';
	in_array('scw_profit', $advppp_settings_scw_columns) ? $c++ . $export_html .= "<td align='right'>".$this->language->get('column_profit')."</td>" : '';
	in_array('scw_profit_margin', $advppp_settings_scw_columns) ? $c++ . $export_html .= "<td align='right'>".$this->language->get('column_profit_margin')."</td>" : '';
	in_array('scw_profit_markup', $advppp_settings_scw_columns) ? $c++ . $export_html .= "<td align='right'>".$this->language->get('column_profit_markup')."</td>" : '';
	in_array('scw_viewed', $advppp_settings_scw_columns) ? $c++ . $export_html .= "<td align='right'>".$this->language->get('column_viewed')."</td>" : '';
	in_array('scw_stock_quantity', $advppp_settings_scw_columns) ? $c++ . $export_html .= "<td align='right'>".$this->language->get('column_stock_quantity')."</td>" : '';
	if ($filter_report == 'products_shopping_carts') {
	in_array('scw_cart_quantity', $advppp_settings_scw_columns) ? $c++ . $export_html .= "<td align='right'>".$this->language->get('column_cart_quantity')."</td>" : '';
	} elseif ($filter_report == 'products_wishlists') {
	in_array('scw_wishlist_quantity', $advppp_settings_scw_columns) ? $c++ . $export_html .= "<td align='right'>".$this->language->get('column_wishlist_quantity')."</td>" : '';
	}
	} elseif ($filter_report == 'products_without_orders') {
	in_array('pnp_id', $advppp_settings_pnp_columns) ? $export_html .= "<td align='right'>".$this->language->get('column_id')."</td>" : '';
	in_array('pnp_sku', $advppp_settings_pnp_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_sku')."</td>" : '';
	in_array('pnp_name', $advppp_settings_pnp_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_prod_name')."</td>" : '';
	in_array('pnp_model', $advppp_settings_pnp_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_model')."</td>" : '';
	in_array('pnp_category', $advppp_settings_pnp_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_category')."</td>" : '';
	in_array('pnp_manufacturer', $advppp_settings_pnp_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_manufacturer')."</td>" : '';
	in_array('pnp_supplier', $advppp_settings_pnp_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_supplier')."</td>" : '';
	in_array('pnp_attribute', $advppp_settings_pnp_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_attribute')."</td>" : '';
	in_array('pnp_status', $advppp_settings_pnp_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_status')."</td>" : '';
	in_array('pnp_location', $advppp_settings_pnp_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_location')."</td>" : '';
	in_array('pnp_tax_class', $advppp_settings_pnp_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_tax_class')."</td>" : '';
	in_array('pnp_price', $advppp_settings_pnp_columns) ? $export_html .= "<td align='right'>".$this->language->get('column_price')."</td>" : '';
	in_array('pnp_cost', $advppp_settings_pnp_columns) ? $export_html .= "<td align='right'>".$this->language->get('column_cost')."</td>" : '';
	in_array('pnp_profit', $advppp_settings_pnp_columns) ? $export_html .= "<td align='right'>".$this->language->get('column_profit')."</td>" : '';
	in_array('pnp_profit_margin', $advppp_settings_pnp_columns) ? $export_html .= "<td align='right'>".$this->language->get('column_profit_margin')."</td>" : '';
	in_array('pnp_profit_markup', $advppp_settings_pnp_columns) ? $export_html .= "<td align='right'>".$this->language->get('column_profit_markup')."</td>" : '';
	in_array('pnp_viewed', $advppp_settings_pnp_columns) ? $export_html .= "<td align='right'>".$this->language->get('column_viewed')."</td>" : '';
	in_array('pnp_stock_quantity', $advppp_settings_pnp_columns) ? $export_html .= "<td align='right'>".$this->language->get('column_stock_quantity')."</td>" : '';
	} elseif ($filter_report == 'manufacturers' or $filter_report == 'categories' or $filter_report == 'suppliers') {
	if ($filter_report == 'manufacturers') {
	$export_html .= "<td align='left'>".$this->language->get('column_manufacturer')."</td>";	
	} elseif ($filter_report == 'categories') {
	$export_html .= "<td align='left'>".$this->language->get('column_category')."</td>";
	} elseif ($filter_report == 'suppliers') {
	$export_html .= "<td align='left'>".$this->language->get('column_supplier')."</td>";	
	}
	in_array('cm_sold_quantity', $advppp_settings_cm_columns) ? $export_html .= "<td align='right'>".$this->language->get('column_sold_quantity')."</td>" : '';
	in_array('cm_sold_percent', $advppp_settings_cm_columns) ? $export_html .= "<td align='right'>".$this->language->get('column_sold_percent')."</td>" : '';
	in_array('cm_total_excl_vat', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' style='min-width:65px;'>".$this->language->get('column_prod_total_excl_vat')."</td>" : '';
	in_array('cm_total_tax', $advppp_settings_cm_columns) ? $export_html .= "<td align='right'>".$this->language->get('column_total_tax')."</td>" : '';
	in_array('cm_total_incl_vat', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' style='min-width:65px;'>".$this->language->get('column_prod_total_incl_vat')."</td>" : '';
	in_array('cm_app', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' style='min-width:75px;'>".$this->language->get('column_app')."</td>" : '';
	in_array('cm_discount', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' style='min-width:75px;'>".$this->language->get('column_product_discount')."</td>" : '';
	in_array('cm_refunds', $advppp_settings_cm_columns) ? $export_html .= "<td align='right'>".$this->language->get('column_product_refunds')."</td>" : '';
	in_array('cm_reward_points', $advppp_settings_cm_columns) ? $export_html .= "<td align='right'>".$this->language->get('column_product_reward_points')."</td>" : '';
	in_array('cm_total_sales', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' style='min-width:80px;'>".$this->language->get('column_total_sales')."</td>" : '';	
	in_array('cm_total_costs', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' style='min-width:80px;'>".$this->language->get('column_total_costs')."</td>" : '';
	in_array('cm_total_profit', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' style='min-width:80px;'>".$this->language->get('column_total_profit')."</td>" : '';
	in_array('cm_total_profit_margin', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' style='min-width:65px;'>".$this->language->get('column_total_profit_margin')."</td>" : '';
	in_array('cm_total_profit_markup', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' style='min-width:65px;'>".$this->language->get('column_total_profit_markup')."</td>" : '';
	} elseif ($filter_report == 'products_options') {
	$export_html .= "<td align='left'>".$this->language->get('column_prod_option')."</td>";
	$export_html .= "<td align='right'>".$this->language->get('column_sold_quantity')."</td>";
	$export_html .= "<td align='right'>".$this->language->get('column_sold_percent')."</td>";
	} else {
	in_array('mv_id', $advppp_settings_mv_columns) ? $export_html .= "<td align='right'>".$this->language->get('column_id')."</td>" : '';
	in_array('mv_sku', $advppp_settings_mv_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_sku')."</td>" : '';
	if ($filter_report == 'products_purchased_with_options' or $filter_report == 'products_abandoned_orders') {
	in_array('mv_name', $advppp_settings_mv_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_name')."</td>" : '';
	} else {
	in_array('mv_name', $advppp_settings_mv_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_prod_name')."</td>" : '';
	}	
	in_array('mv_model', $advppp_settings_mv_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_model')."</td>" : '';
	in_array('mv_category', $advppp_settings_mv_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_category')."</td>" : '';
	in_array('mv_manufacturer', $advppp_settings_mv_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_manufacturer')."</td>" : '';
	in_array('mv_supplier', $advppp_settings_mv_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_supplier')."</td>" : '';
	in_array('mv_attribute', $advppp_settings_mv_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_attribute')."</td>" : '';
	in_array('mv_status', $advppp_settings_mv_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_status')."</td>" : '';
	in_array('mv_location', $advppp_settings_mv_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_location')."</td>" : '';
	in_array('mv_tax_class', $advppp_settings_mv_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_tax_class')."</td>" : '';
	in_array('mv_price', $advppp_settings_mv_columns) ? $export_html .= "<td align='right'>".$this->language->get('column_price')."</td>" : '';
	in_array('mv_cost', $advppp_settings_mv_columns) ? $export_html .= "<td align='right'>".$this->language->get('column_cost')."</td>" : '';
	in_array('mv_profit', $advppp_settings_mv_columns) ? $export_html .= "<td align='right'>".$this->language->get('column_profit')."</td>" : '';
	in_array('mv_profit_margin', $advppp_settings_mv_columns) ? $export_html .= "<td align='right'>".$this->language->get('column_profit_margin')."</td>" : '';
	in_array('mv_profit_markup', $advppp_settings_mv_columns) ? $export_html .= "<td align='right'>".$this->language->get('column_profit_markup')."</td>" : '';
	in_array('mv_viewed', $advppp_settings_mv_columns) ? $export_html .= "<td align='right'>".$this->language->get('column_viewed')."</td>" : '';
	in_array('mv_stock_quantity', $advppp_settings_mv_columns) ? $export_html .= "<td align='right'>".$this->language->get('column_stock_quantity')."</td>" : '';
	in_array('mv_sold_quantity', $advppp_settings_mv_columns) ? $export_html .= "<td align='right'>".$this->language->get('column_sold_quantity')."</td>" : '';
	in_array('mv_sold_percent', $advppp_settings_mv_columns) ? $export_html .= "<td align='right'>".$this->language->get('column_sold_percent')."</td>" : '';
	in_array('mv_total_excl_vat', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' style='min-width:65px;'>".$this->language->get('column_prod_total_excl_vat')."</td>" : '';
	in_array('mv_total_tax', $advppp_settings_mv_columns) ? $export_html .= "<td align='right'>".$this->language->get('column_total_tax')."</td>" : '';
	in_array('mv_total_incl_vat', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' style='min-width:65px;'>".$this->language->get('column_prod_total_incl_vat')."</td>" : '';
	in_array('mv_app', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' style='min-width:75px;'>".$this->language->get('column_app')."</td>" : '';
	in_array('mv_discount', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' style='min-width:75px;'>".$this->language->get('column_product_discount')."</td>" : '';
	in_array('mv_refunds', $advppp_settings_mv_columns) ? $export_html .= "<td align='right'>".$this->language->get('column_product_refunds')."</td>" : '';
	in_array('mv_reward_points', $advppp_settings_mv_columns) ? $export_html .= "<td align='right'>".$this->language->get('column_product_reward_points')."</td>" : '';
	in_array('mv_total_sales', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' style='min-width:80px;'>".$this->language->get('column_total_sales')."</td>" : '';	
	in_array('mv_total_costs', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' style='min-width:80px;'>".$this->language->get('column_total_costs')."</td>" : '';
	in_array('mv_total_profit', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' style='min-width:80px;'>".$this->language->get('column_total_profit')."</td>" : '';
	in_array('mv_total_profit_margin', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' style='min-width:65px;'>".$this->language->get('column_total_profit_margin')."</td>" : '';
	in_array('mv_total_profit_markup', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' style='min-width:65px;'>".$this->language->get('column_total_profit_markup')."</td>" : '';
	}
	$export_html .="</tr>";
	$export_html .="</thead><tbody>";
	
	foreach ($results as $result) {
	$export_html .="<tr>";
	if ($filter_report == 'all_products_with_without_orders' or $filter_report == 'products_without_orders') {
	$export_html .= "<td align='left' nowrap='nowrap' style='background-color:#F9F9F9;'>".$result['date_added']."</td>";
	} elseif ($filter_report == 'products_shopping_carts' or $filter_report == 'products_wishlists') {
	$export_html .= "<td align='left' nowrap='nowrap' style='background-color:#F9F9F9;'>".$result['date_start']."</td>";
	$export_html .= "<td align='left' nowrap='nowrap' style='background-color:#F9F9F9;'>".$result['date_end']."</td>";
	} else {
	if ($filter_group == 'year') {				
	$export_html .= "<td colspan='2' align='left' nowrap='nowrap' style='background-color:#F9F9F9;'>".$result['year']."</td>";
	} elseif ($filter_group == 'quarter') {
	$export_html .= "<td align='left' nowrap='nowrap' style='background-color:#F9F9F9;'>".$result['year']."</td>";	
	$export_html .= "<td align='left' nowrap='nowrap' style='background-color:#F9F9F9;'>".$result['quarter']."</td>";						
	} elseif ($filter_group == 'month') {
	$export_html .= "<td align='left' nowrap='nowrap' style='background-color:#F9F9F9;'>".$result['year']."</td>";	
	$export_html .= "<td align='left' nowrap='nowrap' style='background-color:#F9F9F9;'>".$result['month']."</td>";	
	} elseif ($filter_group == 'day') {
	$export_html .= "<td colspan='2' align='left' nowrap='nowrap' style='background-color:#F9F9F9;'>".$result['date_start']."</td>";
	} elseif ($filter_group == 'order') {
	$export_html .= "<td align='left' nowrap='nowrap' style='background-color:#F9F9F9;'>".$result['order_id']."</td>";	
	$export_html .= "<td align='left' nowrap='nowrap' style='background-color:#F9F9F9;'>".$result['date_start']."</td>";
	} else {
	$export_html .= "<td align='left' nowrap='nowrap' style='background-color:#F9F9F9;'>".$result['date_start']."</td>";
	$export_html .= "<td align='left' nowrap='nowrap' style='background-color:#F9F9F9;'>".$result['date_end']."</td>";
	}
	}
	if ($filter_report == 'products_shopping_carts' or $filter_report == 'products_wishlists') {
	in_array('scw_id', $advppp_settings_scw_columns) ? $export_html .= "<td align='right'>".$result['product_id']."</td>" : '';
	in_array('scw_sku', $advppp_settings_scw_columns) ? $export_html .= "<td align='left'>".$result['sku']."</td>" : '';
	in_array('scw_name', $advppp_settings_scw_columns) ? $export_html .= "<td align='left' style='color:#03C;'><strong>".$result['name']."</strong>" : '';
	if ($filter_report == 'products_shopping_carts') {	
	if ($result['option']) {
	in_array('scw_name', $advppp_settings_scw_columns) ? $export_html .= "<div style='display:table; margin-left:3px;'>" : '';			
	foreach ($result['option'] as $option) {
	in_array('scw_name', $advppp_settings_scw_columns) ? $export_html .= "<div style='display:table-row; white-space:nowrap;'>" : '';		
	in_array('scw_name', $advppp_settings_scw_columns) ? $export_html .= "<div style='display:table-cell; white-space:nowrap; font-size:11px; color:#03C;'>".$option['name'].":</div>" : '';		
	in_array('scw_name', $advppp_settings_scw_columns) ? $export_html .= "<div style='display:table-cell; white-space:nowrap; font-size:11px; color:#03C; padding-left:5px;'>".$option['value']."</div>" : '';
	in_array('scw_name', $advppp_settings_scw_columns) ? $export_html .= "</div>" : '';
	}
	in_array('scw_name', $advppp_settings_scw_columns) ? $export_html .= "</div>" : '';	
	}
	}	
	in_array('scw_name', $advppp_settings_scw_columns) ? $export_html .= "</td>" : '';
	in_array('scw_model', $advppp_settings_scw_columns) ? $export_html .= "<td align='left'>".$result['model']."</td>" : '';	
	in_array('scw_category', $advppp_settings_scw_columns) ? $export_html .= "<td align='left'>".$result['categories']."</td>" : '';
	in_array('scw_manufacturer', $advppp_settings_scw_columns) ? $export_html .= "<td align='left'>".$result['manufacturers']."</td>" : '';
	in_array('scw_supplier', $advppp_settings_scw_columns) ? $export_html .= "<td align='left'>".$result['suppliers']."</td>" : '';
	in_array('scw_attribute', $advppp_settings_scw_columns) ? $export_html .= "<td align='left'>".$result['attribute']."</td>" : '';
	in_array('scw_status', $advppp_settings_scw_columns) ? $export_html .= "<td align='left'>".$result['status']."</td>" : '';
	in_array('scw_location', $advppp_settings_scw_columns) ? $export_html .= "<td align='left'>".$result['location']."</td>" : '';
	in_array('scw_tax_class', $advppp_settings_scw_columns) ? $export_html .= "<td align='left'>".$result['tax_class']."</td>" : '';
	in_array('scw_price', $advppp_settings_scw_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='background-color:#f1f9e9;'>".$result['price']."</td>" : '';
	in_array('scw_cost', $advppp_settings_scw_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='background-color:#f7e9e3;'>".$result['cost']."</td>" : '';
	if ($result['profit_raw'] >= 0) {
	in_array('scw_profit', $advppp_settings_scw_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='background-color:#dfe7ee; font-weight:bold;'>".$result['profit']."</td>" : '';
	} else {
	in_array('scw_profit', $advppp_settings_scw_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='background-color:#F99; font-weight:bold;'>".$result['profit']."</td>" : '';
	}
	if ($result['profit_raw'] >= 0) {
	in_array('scw_profit_margin', $advppp_settings_scw_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='background-color:#dfe7ee; font-weight:bold;'>".$result['profit_margin']."</td>" : '';
	} else {
	in_array('scw_profit_margin', $advppp_settings_scw_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='background-color:#F99; font-weight:bold;'>".$result['profit_margin']."</td>" : '';
	}
	if ($result['profit_raw'] >= 0) {
	in_array('scw_profit_markup', $advppp_settings_scw_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='background-color:#dfe7ee; font-weight:bold;'>".$result['profit_markup']."</td>" : '';
	} else {
	in_array('scw_profit_markup', $advppp_settings_scw_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='background-color:#F99; font-weight:bold;'>".$result['profit_markup']."</td>" : '';
	}	
	in_array('scw_viewed', $advppp_settings_scw_columns) ? $export_html .= "<td align='right'>".$result['viewed']."</td>" : '';
	in_array('scw_stock_quantity', $advppp_settings_scw_columns) ? $export_html .= "<td align='right'>".$result['stock_quantity']."" : '';
	if ($filter_report == 'products_shopping_carts') {
	if ($result['option']) {
	foreach ($result['option'] as $option) {
	in_array('scw_stock_quantity', $advppp_settings_scw_columns) ? $export_html .= "<br /><small>".$option['quantity']."</small>" : '';		
	}
	}
	}	
	in_array('scw_stock_quantity', $advppp_settings_scw_columns) ? $export_html .= "</td>" : '';
	if ($filter_report == 'products_shopping_carts') {
	in_array('scw_cart_quantity', $advppp_settings_scw_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='background-color:#FFC;'>".$result['cart_quantity']."</td>" : '';
	}
	if ($filter_report == 'products_wishlists') {
	in_array('scw_wishlist_quantity', $advppp_settings_scw_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='background-color:#FFC;'>".$result['wishlist_quantity']."</td>" : '';
	}
	} elseif ($filter_report == 'products_without_orders') {
	in_array('pnp_id', $advppp_settings_pnp_columns) ? $export_html .= "<td align='right'>".$result['product_id']."</td>" : '';
	in_array('pnp_sku', $advppp_settings_pnp_columns) ? $export_html .= "<td align='left'>".$result['sku']."</td>" : '';
	in_array('pnp_name', $advppp_settings_pnp_columns) ? $export_html .= "<td align='left' style='color:#03C;'><strong>".$result['name']."</strong>" : '';
	in_array('pnp_model', $advppp_settings_pnp_columns) ? $export_html .= "<td align='left'>".$result['model']."</td>" : '';	
	in_array('pnp_category', $advppp_settings_pnp_columns) ? $export_html .= "<td align='left'>".$result['categories']."</td>" : '';
	in_array('pnp_manufacturer', $advppp_settings_pnp_columns) ? $export_html .= "<td align='left'>".$result['manufacturers']."</td>" : '';
	in_array('pnp_supplier', $advppp_settings_pnp_columns) ? $export_html .= "<td align='left'>".$result['suppliers']."</td>" : '';
	in_array('pnp_attribute', $advppp_settings_pnp_columns) ? $export_html .= "<td align='left'>".$result['attribute']."</td>" : '';
	in_array('pnp_status', $advppp_settings_pnp_columns) ? $export_html .= "<td align='left'>".$result['status']."</td>" : '';
	in_array('pnp_location', $advppp_settings_pnp_columns) ? $export_html .= "<td align='left'>".$result['location']."</td>" : '';
	in_array('pnp_tax_class', $advppp_settings_pnp_columns) ? $export_html .= "<td align='left'>".$result['tax_class']."</td>" : '';
	in_array('pnp_price', $advppp_settings_pnp_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='background-color:#f1f9e9;'>".$result['price']."</td>" : '';
	in_array('pnp_cost', $advppp_settings_pnp_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='background-color:#f7e9e3;'>".$result['cost']."</td>" : '';
	if ($result['profit_raw'] >= 0) {
	in_array('pnp_profit', $advppp_settings_pnp_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='background-color:#dfe7ee; font-weight:bold;'>".$result['profit']."</td>" : '';
	} else {
	in_array('pnp_profit', $advppp_settings_pnp_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='background-color:#F99; font-weight:bold;'>".$result['profit']."</td>" : '';
	}
	if ($result['profit_raw'] >= 0) {
	in_array('pnp_profit_margin', $advppp_settings_pnp_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='background-color:#dfe7ee; font-weight:bold;'>".$result['profit_margin']."</td>" : '';
	} else {
	in_array('pnp_profit_margin', $advppp_settings_pnp_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='background-color:#F99; font-weight:bold;'>".$result['profit_margin']."</td>" : '';
	}
	if ($result['profit_raw'] >= 0) {
	in_array('pnp_profit_markup', $advppp_settings_pnp_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='background-color:#dfe7ee; font-weight:bold;'>".$result['profit_markup']."</td>" : '';
	} else {
	in_array('pnp_profit_markup', $advppp_settings_pnp_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='background-color:#F99; font-weight:bold;'>".$result['profit_markup']."</td>" : '';
	}	
	in_array('pnp_viewed', $advppp_settings_pnp_columns) ? $export_html .= "<td align='right'>".$result['viewed']."</td>" : '';
	in_array('pnp_stock_quantity', $advppp_settings_pnp_columns) ? $export_html .= "<td align='right'>".$result['stock_quantity']."</td>" : '';
	} elseif ($filter_report == 'manufacturers' or $filter_report == 'categories' or $filter_report == 'suppliers') {
	if ($filter_report == 'manufacturers') {
	$export_html .= "<td align='left'>".$result['manufacturers']."</td>";
	} elseif ($filter_report == 'categories') {
	$export_html .= "<td align='left'>".$result['categories']."</td>";
	} elseif ($filter_report == 'suppliers') {
	$export_html .= "<td align='left'>".$result['suppliers']."</td>";
	}
	in_array('cm_sold_quantity', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='background-color:#FFC;'>".$result['sold_quantity']."</td>" : '';
	in_array('cm_sold_percent', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='background-color:#FFC;'>".($result['sold_quantity'] != 0 ? round(100 * ($result['sold_quantity'] / $result['sold_quantity_total']), 2) : 0) . '%'."</td>" : '';
	in_array('cm_total_excl_vat', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='color:#090;'>".$result['total_excl_vat']."</td>" : '';
	in_array('cm_total_tax', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap'>".$result['total_tax']."</td>" : '';
	in_array('cm_total_incl_vat', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap'>".$result['total_incl_vat']."</td>" : '';
	in_array('cm_app', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap'>".$result['app']."</td>" : '';
	in_array('cm_discount', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap'>".$result['discount']."</td>" : '';
	if ($this->config->get('advppp' . $user_id . '_formula_6')) {	
	in_array('cm_refunds', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='color:#090;'>".$result['refunds']."</td>" : '';
	} else {
	in_array('cm_refunds', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap'>".$result['refunds']."</td>" : '';
	}		
	in_array('cm_reward_points', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap'>".$result['reward_points']."</td>" : '';
	in_array('cm_total_sales', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='sales'>".$result['total_sales']."</td>" : '';
	in_array('cm_total_costs', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='cost'>".$result['total_costs']."</td>" : '';
	if ($result['total_profit_raw'] >= 0) {
	in_array('cm_total_profit', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='plusprofit'>".$result['total_profit']."</td>" : '';
	} else {
	in_array('cm_total_profit', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='minusprofit'>".$result['total_profit']."</td>" : '';
	}
	if ($result['sold_quantity'] > 0) {
	if ($result['total_profit_raw'] >= 0) {
	in_array('cm_total_profit_margin', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='plusprofit'>".$result['total_profit_margin']."</td>" : '';
	} else {
	in_array('cm_total_profit_margin', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='minusprofit'>".$result['total_profit_margin']."</td>" : '';
	}
	} else {
	in_array('cm_total_profit_margin', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='plusprofit'>".'0%'."</td>" : '';
	}	
	if ($result['sold_quantity'] > 0) {
	if ($result['total_profit_raw'] >= 0) {
	in_array('cm_total_profit_markup', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='plusprofit'>".$result['total_profit_markup']."</td>" : '';
	} else {
	in_array('cm_total_profit_markup', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='minusprofit'>".$result['total_profit_markup']."</td>" : '';
	}	
	} else {
	in_array('cm_total_profit_markup', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='plusprofit'>".'0%'."</td>" : '';
	}	
	} elseif ($filter_report == 'products_options') {
	$export_html .= "<td align='left'>".$result['option_name']."</td>";
	$export_html .= "<td align='right' nowrap='nowrap' style='background-color:#FFC;'>".$result['sold_quantity']."</td>";
	$export_html .= "<td align='right' nowrap='nowrap' style='background-color:#FFC;'>".($result['sold_quantity'] != 0 ? round(100 * ($result['sold_quantity'] / $result['sold_quantity_total']), 2) : 0) . '%'."</td>";
	} else {
	in_array('mv_id', $advppp_settings_mv_columns) ? $export_html .= "<td align='right'>".$result['product_id']."</td>" : '';
	in_array('mv_sku', $advppp_settings_mv_columns) ? $export_html .= "<td align='left'>".$result['sku']."</td>" : '';
	in_array('mv_name', $advppp_settings_mv_columns) ? $export_html .= "<td align='left' style='color:#03C;'><strong>".$result['name']."</strong>" : '';
	if ($filter_report == 'products_purchased_with_options' or $filter_report == 'products_abandoned_orders') {	
	if ($result['option']) {
	in_array('mv_name', $advppp_settings_mv_columns) ? $export_html .= "<div style='display:table; margin-left:3px;'>" : '';			
	foreach ($result['option'] as $option) {
	in_array('mv_name', $advppp_settings_mv_columns) ? $export_html .= "<div style='display:table-row; white-space:nowrap;'>" : '';		
	in_array('mv_name', $advppp_settings_mv_columns) ? $export_html .= "<div style='display:table-cell; white-space:nowrap; font-size:11px; color:#03C;'>".$option['name'].":</div>" : '';		
	in_array('mv_name', $advppp_settings_mv_columns) ? $export_html .= "<div style='display:table-cell; white-space:nowrap; font-size:11px; color:#03C; padding-left:5px;'>".$option['value']."</div>" : '';
	in_array('mv_name', $advppp_settings_mv_columns) ? $export_html .= "</div>" : '';
	}
	in_array('mv_name', $advppp_settings_mv_columns) ? $export_html .= "</div>" : '';	
	}
	}	
	in_array('mv_name', $advppp_settings_mv_columns) ? $export_html .= "</td>" : '';	
	in_array('mv_model', $advppp_settings_mv_columns) ? $export_html .= "<td align='left'>".$result['model']."</td>" : '';	
	in_array('mv_category', $advppp_settings_mv_columns) ? $export_html .= "<td align='left'>".$result['categories']."</td>" : '';
	in_array('mv_manufacturer', $advppp_settings_mv_columns) ? $export_html .= "<td align='left'>".$result['manufacturers']."</td>" : '';
	in_array('mv_supplier', $advppp_settings_mv_columns) ? $export_html .= "<td align='left'>".$result['suppliers']."</td>" : '';
	in_array('mv_attribute', $advppp_settings_mv_columns) ? $export_html .= "<td align='left'>".$result['attribute']."</td>" : '';
	in_array('mv_status', $advppp_settings_mv_columns) ? $export_html .= "<td align='left'>".$result['status']."</td>" : '';
	in_array('mv_location', $advppp_settings_mv_columns) ? $export_html .= "<td align='left'>".$result['location']."</td>" : '';
	in_array('mv_tax_class', $advppp_settings_mv_columns) ? $export_html .= "<td align='left'>".$result['tax_class']."</td>" : '';
	in_array('mv_price', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='background-color:#f1f9e9;'>".$result['price']."</td>" : '';
	in_array('mv_cost', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='background-color:#f7e9e3;'>".$result['cost']."</td>" : '';
	if ($result['profit_raw'] >= 0) {
	in_array('mv_profit', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='background-color:#dfe7ee; font-weight:bold;'>".$result['profit']."</td>" : '';
	} else {
	in_array('mv_profit', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='background-color:#F99; font-weight:bold;'>".$result['profit']."</td>" : '';
	}
	if ($result['profit_raw'] >= 0) {
	in_array('mv_profit_margin', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='background-color:#dfe7ee; font-weight:bold;'>".$result['profit_margin']."</td>" : '';
	} else {
	in_array('mv_profit_margin', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='background-color:#F99; font-weight:bold;'>".$result['profit_margin']."</td>" : '';
	}
	if ($result['profit_raw'] >= 0) {
	in_array('mv_profit_markup', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='background-color:#dfe7ee; font-weight:bold;'>".$result['profit_markup']."</td>" : '';
	} else {
	in_array('mv_profit_markup', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='background-color:#F99; font-weight:bold;'>".$result['profit_markup']."</td>" : '';
	}	
	in_array('mv_viewed', $advppp_settings_mv_columns) ? $export_html .= "<td align='right'>".$result['viewed']."</td>" : '';
	in_array('mv_stock_quantity', $advppp_settings_mv_columns) ? $export_html .= "<td align='right'>".$result['stock_quantity']."" : '';
	if ($filter_report == 'products_purchased_with_options' or $filter_report == 'products_abandoned_orders') {
	if ($result['option']) {
	foreach ($result['option'] as $option) {
	in_array('mv_stock_quantity', $advppp_settings_mv_columns) ? $export_html .= "<br /><small>".$option['quantity']."</small>" : '';		
	}
	}
	}	
	in_array('mv_stock_quantity', $advppp_settings_mv_columns) ? $export_html .= "</td>" : '';
	if ($filter_report != 'products_abandoned_orders') {
	in_array('mv_sold_quantity', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='background-color:#FFC;'>".$result['sold_quantity']."</td>" : '';
	} else {
	in_array('mv_sold_quantity', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='background-color:#FFC; text-decoration:line-through;'>".$result['sold_quantity']."</td>" : '';
	}
	if ($filter_report != 'products_abandoned_orders') {
	in_array('mv_sold_percent', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='background-color:#FFC;'>".($result['sold_quantity'] != 0 ? round(100 * ($result['sold_quantity'] / $result['sold_quantity_total']), 2) : 0) . '%'."</td>" : '';
	} else {
	in_array('mv_sold_percent', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='background-color:#FFC; text-decoration:line-through;'>".($result['sold_quantity'] != 0 ? round(100 * ($result['sold_quantity'] / $result['sold_quantity_total']), 2) : 0) . '%'."</td>" : '';
	}	
	if ($filter_report != 'products_abandoned_orders') {	
	in_array('mv_total_excl_vat', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='color:#090;'>".$result['total_excl_vat']."</td>" : '';
	} else {
	in_array('mv_total_excl_vat', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='color:#090; text-decoration:line-through;'>".$result['total_excl_vat']."</td>" : '';
	}
	if ($filter_report != 'products_abandoned_orders') {	
	in_array('mv_total_tax', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap'>".$result['total_tax']."</td>" : '';
	} else {
	in_array('mv_total_tax', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='text-decoration:line-through;'>".$result['total_tax']."</td>" : '';
	}		
	if ($filter_report != 'products_abandoned_orders') {		
	in_array('mv_total_incl_vat', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap'>".$result['total_incl_vat']."</td>" : '';
	} else {
	in_array('mv_total_incl_vat', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='text-decoration:line-through;'>".$result['total_incl_vat']."</td>" : '';
	}
	if ($filter_report != 'products_abandoned_orders') {	
	in_array('mv_app', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap'>".$result['app']."</td>" : '';
	} else {
	in_array('mv_app', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='text-decoration:line-through;'>".$result['app']."</td>" : '';
	}	
	if ($filter_report != 'products_abandoned_orders') {	
	in_array('mv_discount', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap'>".$result['discount']."</td>" : '';
	} else {
	in_array('mv_discount', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='text-decoration:line-through;'>".$result['discount']."</td>" : '';
	}	
	if ($this->config->get('advppp' . $user_id . '_formula_6')) {	
	if ($filter_report != 'products_abandoned_orders') {	
	in_array('mv_refunds', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='color:#090;'>".$result['refunds']."</td>" : '';
	} else {
	in_array('mv_refunds', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='color:#090; text-decoration:line-through;'>".$result['refunds']."</td>" : '';
	}	
	} else {
	if ($filter_report != 'products_abandoned_orders') {		
	in_array('mv_refunds', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap'>".$result['refunds']."</td>" : '';
	} else {
	in_array('mv_refunds', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='text-decoration:line-through;'>".$result['refunds']."</td>" : '';
	}	
	}		
	if ($filter_report != 'products_abandoned_orders') {		
	in_array('mv_reward_points', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap'>".$result['reward_points']."</td>" : '';
	} else {
	in_array('mv_reward_points', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='text-decoration:line-through;'>".$result['reward_points']."</td>" : '';
	}	
	if ($filter_report != 'products_abandoned_orders') {	
	in_array('mv_total_sales', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='sales'>".$result['total_sales']."</td>" : '';
	} else {
	in_array('mv_total_sales', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='sales' style='text-decoration:line-through;'>".$result['total_sales']."</td>" : '';
	}		
	if ($filter_report != 'products_abandoned_orders') {	
	in_array('mv_total_costs', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='cost'>".$result['total_costs']."</td>" : '';
	} else {
	in_array('mv_total_costs', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='cost' style='text-decoration:line-through;'>".$result['total_costs']."</td>" : '';
	}		
	if ($result['total_profit_raw'] >= 0) {
	if ($filter_report != 'products_abandoned_orders') {
	in_array('mv_total_profit', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='plusprofit'>".$result['total_profit']."</td>" : '';
	} else {
	in_array('mv_total_profit', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='plusprofit' style='text-decoration:line-through;'>".$result['total_profit']."</td>" : '';
	}	
	} else {
	if ($filter_report != 'products_abandoned_orders') {
	in_array('mv_total_profit', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='minusprofit'>".$result['total_profit']."</td>" : '';
	} else {
	in_array('mv_total_profit', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='minusprofit' style='text-decoration:line-through;'>".$result['total_profit']."</td>" : '';
	}	
	}
	if ($result['sold_quantity'] > 0) {
	if ($filter_report != 'products_abandoned_orders') {
	if ($result['total_profit_raw'] >= 0) {
	in_array('mv_total_profit_margin', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='plusprofit'>".$result['total_profit_margin']."</td>" : '';
	} else {
	in_array('mv_total_profit_margin', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='minusprofit'>".$result['total_profit_margin']."</td>" : '';
	}
	} else {
	if ($result['total_profit_raw'] >= 0) {
	in_array('mv_total_profit_margin', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='plusprofit' style='text-decoration:line-through;'>".$result['total_profit_margin']."</td>" : '';
	} else {
	in_array('mv_total_profit_margin', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='minusprofit' style='text-decoration:line-through;'>".$result['total_profit_margin']."</td>" : '';
	}
	}
	} else {
	in_array('mv_total_profit_margin', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='plusprofit'>".'0%'."</td>" : '';
	}
	if ($result['sold_quantity'] > 0) {
	if ($filter_report != 'products_abandoned_orders') {
	if ($result['total_profit_raw'] >= 0) {
	in_array('mv_total_profit_markup', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='plusprofit'>".$result['total_profit_markup']."</td>" : '';
	} else {
	in_array('mv_total_profit_markup', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='minusprofit'>".$result['total_profit_markup']."</td>" : '';
	}
	} else {
	if ($result['total_profit_raw'] >= 0) {
	in_array('mv_total_profit_markup', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='plusprofit' style='text-decoration:line-through;'>".$result['total_profit_markup']."</td>" : '';
	} else {
	in_array('mv_total_profit_markup', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='minusprofit' style='text-decoration:line-through;'>".$result['total_profit_markup']."</td>" : '';
	}
	}
	} else {
	in_array('mv_total_profit_markup', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='plusprofit'>".'0%'."</td>" : '';
	}
	}
	$export_html .="</tr>";
	if ($filter_report == 'products_shopping_carts' or $filter_report == 'products_wishlists') {
	$export_html .="<tr>";
	$export_html .= "<td colspan='".$c."' align='center'>";
		$export_html .="<table class='list_detail'>";
		$export_html .="<thead>";
		$export_html .="<tr>";
		in_array('scw_customer_id', $advppp_settings_scw_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_customer_cust_id')."</td>" : '';		
		in_array('scw_date_added', $advppp_settings_scw_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_customer_date_added')."</td>" : '';
		in_array('scw_customer_name', $advppp_settings_scw_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_order_customer')."</td>" : '';
		in_array('scw_customer_email', $advppp_settings_scw_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_order_email')."</td>" : '';
		in_array('scw_customer_telephone', $advppp_settings_scw_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_customer_telephone')."</td>" : '';
		in_array('scw_customer_group', $advppp_settings_scw_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_order_customer_group')."</td>" : '';
		in_array('scw_billing_company', $advppp_settings_scw_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_company')."</td>" : '';
		in_array('scw_billing_address_1', $advppp_settings_scw_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_address_1')."</td>" : '';
		in_array('scw_billing_address_2', $advppp_settings_scw_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_address_2')."</td>" : '';
		in_array('scw_billing_city', $advppp_settings_scw_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_city')."</td>" : '';
		in_array('scw_billing_zone', $advppp_settings_scw_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_region_state')."</td>" : '';
		in_array('scw_billing_postcode', $advppp_settings_scw_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_postcode')."</td>" : '';
		in_array('scw_billing_country', $advppp_settings_scw_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_country')."</td>" : '';
		$export_html .="</tr>";
		$export_html .="</thead><tbody>";
		foreach ($result['customer'] as $customer) {
		$export_html .="<tr>";
		in_array('scw_customer_id', $advppp_settings_scw_columns) ? $export_html .= "<td align='left' nowrap='nowrap' style='background-color:#fff2d0;'>".$customer['customer_id']."</td>" : '';		
		in_array('scw_date_added', $advppp_settings_scw_columns) ? $export_html .= "<td align='left' nowrap='nowrap'>".$customer['date_added']."</td>" : '';
		in_array('scw_customer_name', $advppp_settings_scw_columns) ? $export_html .= "<td align='left' nowrap='nowrap'>".$customer['customer_name']."</td>" : '';
		in_array('scw_customer_email', $advppp_settings_scw_columns) ? $export_html .= "<td align='left' nowrap='nowrap'>".$customer['email']."</td>" : '';
		in_array('scw_customer_telephone', $advppp_settings_scw_columns) ? $export_html .= "<td align='left' nowrap='nowrap'>".$customer['telephone']."</td>" : '';
		in_array('scw_customer_group', $advppp_settings_scw_columns) ? $export_html .= "<td align='left' nowrap='nowrap'>".$customer['customer_group']."</td>" : '';
		in_array('scw_billing_company', $advppp_settings_scw_columns) ? $export_html .= "<td align='left' nowrap='nowrap'>".$customer['company']."</td>" : '';
		in_array('scw_billing_address_1', $advppp_settings_scw_columns) ? $export_html .= "<td align='left' nowrap='nowrap'>".$customer['address_1']."</td>" : '';
		in_array('scw_billing_address_2', $advppp_settings_scw_columns) ? $export_html .= "<td align='left' nowrap='nowrap'>".$customer['address_2']."</td>" : '';
		in_array('scw_billing_city', $advppp_settings_scw_columns) ? $export_html .= "<td align='left' nowrap='nowrap'>".$customer['city']."</td>" : '';
		in_array('scw_billing_zone', $advppp_settings_scw_columns) ? $export_html .= "<td align='left' nowrap='nowrap'>".$customer['zone']."</td>" : '';
		in_array('scw_billing_postcode', $advppp_settings_scw_columns) ? $export_html .= "<td align='left' nowrap='nowrap'>".$customer['postcode']."</td>" : '';
		in_array('scw_billing_country', $advppp_settings_scw_columns) ? $export_html .= "<td align='left' nowrap='nowrap'>".$customer['country']."</td>" : '';	
		$export_html .="</tr>";	
		}
		$export_html .="</tbody></table>";
	$export_html .="</td>";
	$export_html .="</tr>";	
	}
	}
	$export_html .="</tbody>";
	
	if ($filter_report != 'products_without_orders' && $filter_report != 'products_shopping_carts' && $filter_report != 'products_wishlists') {	
	$export_html .="<tbody><tr>";	
	if ($filter_report == 'all_products_with_without_orders') {	
	$export_html .= "<td align='right' style='background-color:#E5E5E5; font-weight:bold;'>".$this->language->get('text_filter_total')."</td>";
	} else {
	$export_html .= "<td colspan='2' align='right' style='background-color:#E5E5E5; font-weight:bold;'>".$this->language->get('text_filter_total')."</td>";
	}	
	if ($filter_report == 'manufacturers' or $filter_report == 'categories' or $filter_report == 'suppliers') {
	if ($filter_report == 'manufacturers') {
	in_array('cm_manufacturer', $advppp_settings_cm_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';	
	} elseif ($filter_report == 'categories') {
	in_array('cm_category', $advppp_settings_cm_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';	
	} elseif ($filter_report == 'suppliers') {
	in_array('cm_supplier', $advppp_settings_cm_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';	
	}	
	in_array('cm_sold_quantity', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total' style='background-color:#FFC;'>".$result['sold_quantity_total']."</td>" : '';
	in_array('cm_sold_percent', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total' style='background-color:#FFC;'>".$result['sold_percent_total']."</td>" : '';
	in_array('cm_total_excl_vat', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total'>".$result['total_excl_vat_total']."</td>" : '';
	in_array('cm_total_tax', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total'>".$result['total_tax_total']."</td>" : '';
	in_array('cm_total_incl_vat', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total'>".$result['total_incl_vat_total']."</td>" : '';
	in_array('cm_app', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total'>".$result['app_total']."</td>" : '';	
	in_array('cm_discount', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total'>".$result['discount_total']."</td>" : '';	
	in_array('cm_refunds', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total'>".$result['refunds_total']."</td>" : '';	
	in_array('cm_reward_points', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total'>".$result['reward_points_total']."</td>" : '';	
	in_array('cm_total_sales', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total_sales'>".$result['total_sales_total']."</td>" : '';
	in_array('cm_total_costs', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total_cost'>".$result['total_costs_total']."</td>" : '';
	if ($result['total_profit_total_raw'] >= 0) {
	in_array('cm_total_profit', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total_plusprofit'>".$result['total_profit_total']."</td>" : '';
	} else {
	in_array('cm_total_profit', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total_minusprofit'>".$result['total_profit_total']."</td>" : '';
	}
	if ($result['sold_quantity'] > 0) {
	if ($result['total_profit_total_raw'] >= 0) {
	in_array('cm_total_profit_margin', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total_plusprofit'>".$result['total_profit_margin_total']."</td>" : '';
	} else {
	in_array('cm_total_profit_margin', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total_minusprofit'>".$result['total_profit_margin_total']."</td>" : '';
	}
	} else {
	in_array('cm_total_profit_margin', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total_plusprofit'>".'0%'."</td>" : '';	
	}
	if ($result['sold_quantity'] > 0) {
	if ($result['total_profit_total_raw'] >= 0) {
	in_array('cm_total_profit_markup', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total_plusprofit'>".$result['total_profit_markup_total']."</td>" : '';
	} else {
	in_array('cm_total_profit_markup', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total_minusprofit'>".$result['total_profit_markup_total']."</td>" : '';
	}
	} else {
	in_array('cm_total_profit_markup', $advppp_settings_cm_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total_plusprofit'>".'0%'."</td>" : '';	
	}
	} elseif ($filter_report == 'products_options') {
	$export_html .= "<td style='background-color:#E5E5E5;'></td>";
	$export_html .= "<td align='right' nowrap='nowrap' class='total' style='background-color:#E5E5E5;'>".$result['sold_quantity_total']."</td>";
	$export_html .= "<td align='right' nowrap='nowrap' class='total' style='background-color:#E5E5E5;'>".$result['sold_percent_total']."</td>";
	} elseif ($filter_report != 'products_without_orders' && $filter_report != 'products_shopping_carts' && $filter_report != 'products_wishlists' && $filter_report != 'manufacturers' && $filter_report != 'categories' && $filter_report != 'suppliers') {
	in_array('mv_id', $advppp_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	in_array('mv_sku', $advppp_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	in_array('mv_name', $advppp_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	in_array('mv_model', $advppp_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';	
	in_array('mv_category', $advppp_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	in_array('mv_manufacturer', $advppp_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	in_array('mv_supplier', $advppp_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	in_array('mv_attribute', $advppp_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	in_array('mv_status', $advppp_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	in_array('mv_location', $advppp_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	in_array('mv_tax_class', $advppp_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	in_array('mv_price', $advppp_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	in_array('mv_cost', $advppp_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	in_array('mv_profit', $advppp_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	in_array('mv_profit_margin', $advppp_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	in_array('mv_profit_markup', $advppp_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	in_array('mv_viewed', $advppp_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	in_array('mv_stock_quantity', $advppp_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	if ($filter_report != 'products_abandoned_orders') {
	in_array('mv_sold_quantity', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total' style='background-color:#FFC;'>".$result['sold_quantity_total']."</td>" : '';
	} else {
	in_array('mv_sold_quantity', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total' style='background-color:#FFC; text-decoration:line-through;'>".$result['sold_quantity_total']."</td>" : '';		
	}
	if ($filter_report != 'products_abandoned_orders') {
	in_array('mv_sold_percent', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total' style='background-color:#FFC;'>".$result['sold_percent_total']."</td>" : '';
	} else {
	in_array('mv_sold_percent', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total' style='background-color:#FFC; text-decoration:line-through;'>".$result['sold_percent_total']."</td>" : '';		
	}	
	if ($filter_report != 'products_abandoned_orders') {
	in_array('mv_total_excl_vat', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total'>".$result['total_excl_vat_total']."</td>" : '';
	} else {
	in_array('mv_total_excl_vat', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total' style='text-decoration:line-through;'>".$result['total_excl_vat_total']."</td>" : '';	
	}	
	if ($filter_report != 'products_abandoned_orders') {
	in_array('mv_total_tax', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total'>".$result['total_tax_total']."</td>" : '';
	} else {
	in_array('mv_total_tax', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total' style='text-decoration:line-through;'>".$result['total_tax_total']."</td>" : '';
	}
	if ($filter_report != 'products_abandoned_orders') {
	in_array('mv_total_incl_vat', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total'>".$result['total_incl_vat_total']."</td>" : '';
	} else {
	in_array('mv_total_incl_vat', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total' style='text-decoration:line-through;'>".$result['total_incl_vat_total']."</td>" : '';
	}
	if ($filter_report != 'products_abandoned_orders') {
	in_array('mv_app', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total'>".$result['app_total']."</td>" : '';	
	} else {
	in_array('mv_app', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total' style='text-decoration:line-through;'>".$result['app_total']."</td>" : '';	
	}
	if ($filter_report != 'products_abandoned_orders') {
	in_array('mv_discount', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total'>".$result['discount_total']."</td>" : '';
	} else {
	in_array('mv_discount', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total' style='text-decoration:line-through;'>".$result['discount_total']."</td>" : '';	
	}
	if ($filter_report != 'products_abandoned_orders') {
	in_array('mv_refunds', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total'>".$result['refunds_total']."</td>" : '';	
	} else {
	in_array('mv_refunds', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total' style='text-decoration:line-through;'>".$result['refunds_total']."</td>" : '';	
	}
	if ($filter_report != 'products_abandoned_orders') {
	in_array('mv_reward_points', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total'>".$result['reward_points_total']."</td>" : '';	
	} else {
	in_array('mv_reward_points', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total' style='text-decoration:line-through;'>".$result['reward_points_total']."</td>" : '';	
	}
	if ($filter_report != 'products_abandoned_orders') {
	in_array('mv_total_sales', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total_sales'>".$result['total_sales_total']."</td>" : '';
	} else {
	in_array('mv_total_sales', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total_sales' style='text-decoration:line-through;'>".$result['total_sales_total']."</td>" : '';
	}
	if ($filter_report != 'products_abandoned_orders') {
	in_array('mv_total_costs', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total_cost'>".$result['total_costs_total']."</td>" : '';
	} else {
	in_array('mv_total_costs', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total_cost' style='text-decoration:line-through;'>".$result['total_costs_total']."</td>" : '';
	}
	if ($result['total_profit_total_raw'] >= 0) {
	if ($filter_report != 'products_abandoned_orders') {
	in_array('mv_total_profit', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total_plusprofit'>".$result['total_profit_total']."</td>" : '';
	} else {
	in_array('mv_total_profit', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total_plusprofit' style='text-decoration:line-through;'>".$result['total_profit_total']."</td>" : '';
	}	
	} else {
	if ($filter_report != 'products_abandoned_orders') {
	in_array('mv_total_profit', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total_minusprofit'>".$result['total_profit_total']."</td>" : '';
	} else {
	in_array('mv_total_profit', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total_minusprofit' style='text-decoration:line-through;'>".$result['total_profit_total']."</td>" : '';
	}	
	}
	if ($result['sold_quantity_total'] > 0) {
	if ($filter_report != 'products_abandoned_orders') {
	if ($result['total_profit_total_raw'] >= 0) {
	in_array('mv_total_profit_margin', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total_plusprofit'>".$result['total_profit_margin_total']."</td>" : '';
	} else {
	in_array('mv_total_profit_margin', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total_minusprofit'>".$result['total_profit_margin_total']."</td>" : '';
	}
	} else {
	if ($result['total_profit_total_raw'] >= 0) {
	in_array('mv_total_profit_margin', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total_plusprofit' style='text-decoration:line-through;'>".$result['total_profit_margin_total']."</td>" : '';
	} else {
	in_array('mv_total_profit_margin', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total_minusprofit' style='text-decoration:line-through;'>".$result['total_profit_margin_total']."</td>" : '';
	}
	}
	} else {
	in_array('mv_total_profit_margin', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total_plusprofit'>".'0%'."</td>" : '';
	}
	if ($result['sold_quantity_total'] > 0) {
	if ($filter_report != 'products_abandoned_orders') {
	if ($result['total_profit_total_raw'] >= 0) {
	in_array('mv_total_profit_markup', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total_plusprofit'>".$result['total_profit_markup_total']."</td>" : '';
	} else {
	in_array('mv_total_profit_markup', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total_minusprofit'>".$result['total_profit_markup_total']."</td>" : '';
	}
	} else {
	if ($result['total_profit_total_raw'] >= 0) {
	in_array('mv_total_profit_markup', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total_plusprofit' style='text-decoration:line-through;'>".$result['total_profit_markup_total']."</td>" : '';
	} else {
	in_array('mv_total_profit_markup', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total_minusprofit' style='text-decoration:line-through;'>".$result['total_profit_markup_total']."</td>" : '';
	}
	}
	} else {
	in_array('mv_total_profit_markup', $advppp_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total_plusprofit'>".'0%'."</td>" : '';
	}
	}
	$export_html .="</tr></tbody>";
	}
	$export_html .="</table>";	
	$export_html .="</body></html>";

	if (!isset($_GET['cron'])) {
		$filename = "products_profit_report_".date($this->config->get('advppp' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A");
		header('Expires: 0');
		header('Cache-control: private');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Content-Description: File Transfer');			
		header('Content-Disposition: attachment; filename='.$filename.".html");
		print $export_html;		
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
		
		$filename = $file_name."_".date($this->config->get('advppp' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A").".html";
		$file_to_download = $server . $file_save_path . '/' . $file_name."_".date($this->config->get('advppp' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A").".html";
		$file = $file_path . '/' . $file_name."_".date($this->config->get('advppp' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A").".html";		
		
		file_put_contents($file, $export_html);
		
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