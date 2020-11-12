<?php
	ini_set("memory_limit","256M");
	
	$results = $export_data['results'];
	if ($results) {

	if ($filter_report == 'products_purchased_with_options' or $filter_report == 'products_abandoned_orders') {
	$this->load->model('report/adv_products_profit');
	$option_names = $this->model_report_adv_products_profit->getOrderOptionsNames();
	}
	
	$this->objPHPExcel = new PHPExcel();
	$this->objPHPExcel->getActiveSheet()->setTitle('ADV Products + Profit Report');
	$this->objPHPExcel->getProperties()->setCreator("ADV Reports & Statistics")
									   ->setLastModifiedBy("ADV Reports & Statistics")
									   ->setTitle("ADV Products + Profit Report")
									   ->setSubject("ADV Products + Profit Report")
									   ->setDescription("ADV Products + Profit Report with Basic Details")
									   ->setKeywords("office 2007 excel")
									   ->setCategory("www.opencartreports.com");
	$this->objPHPExcel->setActiveSheetIndex(0);
	$export_logo_criteria ? $this->mainCounter = 2 : $this->mainCounter = 1;
	if ($export_logo_criteria ? $this->mainCounter = 2 : $this->mainCounter = 1) {
		 $j = 0;
		 
		 if ($filter_group == 'year') {	 
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_year'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);		 
		 } elseif ($filter_group == 'quarter') {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_year'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);		 
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_quarter'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);		 
		 } elseif ($filter_group == 'month') {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_year'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);	
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);		
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_month'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);		 
		 } elseif ($filter_group == 'day') {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_date'));
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);		 
		 } elseif ($filter_group == 'order') {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_order_id'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);	 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);		 
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_date_added'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);		 
		 } else {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_date_start'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);		 
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_date_end'));	
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);		 
		 }

		 if ($filter_report == 'manufacturers' or $filter_report == 'categories' or $filter_report == 'suppliers') {

		 if ($filter_report == 'manufacturers') {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_manufacturer'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 } elseif ($filter_report == 'categories') {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_category'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 } elseif ($filter_report == 'suppliers') {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_supplier'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('cm_sold_quantity', $advppp_settings_cm_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_sold_quantity'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('cm_sold_percent', $advppp_settings_cm_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_sold_percent'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('cm_total_excl_vat', $advppp_settings_cm_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_total_excl_vat'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('cm_total_tax', $advppp_settings_cm_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_total_tax'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('cm_total_incl_vat', $advppp_settings_cm_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_total_incl_vat'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('cm_app', $advppp_settings_cm_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_app'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('cm_discount', $advppp_settings_cm_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_product_discount'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('cm_refunds', $advppp_settings_cm_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_product_refunds'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('cm_reward_points', $advppp_settings_cm_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_product_reward_points'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('cm_total_sales', $advppp_settings_cm_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_total_sales'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('cm_total_costs', $advppp_settings_cm_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_total_costs'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('cm_total_profit', $advppp_settings_cm_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_total_profit'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('cm_total_profit_margin', $advppp_settings_cm_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_total_profit_margin'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('cm_total_profit_markup', $advppp_settings_cm_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_total_profit_markup'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 
		 } elseif ($filter_report == 'products_options') {

		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_option'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);

		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_sold_quantity'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);

		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_sold_percent'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 
		 } else {

		 if (in_array('mv_id', $advppp_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_id'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);			 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('mv_sku', $advppp_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_sku'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('mv_name', $advppp_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_pname'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);		 
		 if ($filter_report == 'products_purchased_with_options' or $filter_report == 'products_abandoned_orders') {
		 $n = 0;
		 if ($option_names) {
		 foreach ($option_names as $option_name) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $option_name['name']);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 $n++;
		 }		 
		 }				
		 }	 
		 }
		 if (in_array('mv_model', $advppp_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_model'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('mv_category', $advppp_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_category'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('mv_manufacturer', $advppp_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_manufacturer'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('mv_supplier', $advppp_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_supplier'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }		 
		 if (in_array('mv_attribute', $advppp_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_attribute'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('mv_status', $advppp_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_status'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('mv_location', $advppp_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);			 
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_location'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('mv_tax_class', $advppp_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_tax_class'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('mv_price', $advppp_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_price'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('mv_cost', $advppp_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_cost'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('mv_profit', $advppp_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_profit'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('mv_profit_margin', $advppp_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_profit_margin'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('mv_profit_markup', $advppp_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_profit_markup'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }		 
		 if (in_array('mv_viewed', $advppp_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_viewed'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('mv_stock_quantity', $advppp_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_stock_quantity'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('mv_sold_quantity', $advppp_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_sold_quantity'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('mv_sold_percent', $advppp_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_sold_percent'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('mv_total_excl_vat', $advppp_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_total_excl_vat'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('mv_total_tax', $advppp_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_total_tax'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('mv_total_incl_vat', $advppp_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_total_incl_vat'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('mv_app', $advppp_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_app'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('mv_discount', $advppp_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_product_discount'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('mv_refunds', $advppp_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_product_refunds'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('mv_reward_points', $advppp_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_product_reward_points'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('mv_total_sales', $advppp_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_total_sales'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('mv_total_costs', $advppp_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_total_costs'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('mv_total_profit', $advppp_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_total_profit'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('mv_total_profit_margin', $advppp_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_total_profit_margin'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('mv_total_profit_markup', $advppp_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_total_profit_markup'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 
		 }

 		 if ($filter_report == 'manufacturers' or $filter_report == 'categories' or $filter_report == 'suppliers' or $filter_report == 'products_options') {
			 
		 if (in_array('pl_prod_order_id', $advppp_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_order_id'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);			 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('pl_prod_date_added', $advppp_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_date_added'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('pl_prod_inv_no', $advppp_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_inv_no'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }	 
		 if (in_array('pl_prod_id', $advppp_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_id'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('pl_prod_sku', $advppp_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_sku'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('pl_prod_model', $advppp_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_model'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('pl_prod_name', $advppp_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_name'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('pl_prod_option', $advppp_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_option'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if ($filter_report == 'manufacturers') {
		 if (in_array('pl_prod_category', $advppp_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_category'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('pl_prod_supplier', $advppp_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_supplier'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }		 
		 } elseif ($filter_report == 'categories') {
		 if (in_array('pl_prod_manu', $advppp_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_manu'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }				 
		 if (in_array('pl_prod_supplier', $advppp_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_supplier'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }	
		 } elseif ($filter_report == 'suppliers') {
		 if (in_array('pl_prod_category', $advppp_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_category'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('pl_prod_manu', $advppp_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_manu'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 } elseif ($filter_report == 'products_options') {
		 if (in_array('pl_prod_category', $advppp_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_category'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('pl_prod_manu', $advppp_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_manu'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('pl_prod_supplier', $advppp_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_supplier'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }	
		 }
		 if (in_array('pl_prod_attributes', $advppp_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_attributes'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }		 
		 if (in_array('pl_prod_currency', $advppp_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_currency'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('pl_prod_price', $advppp_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_price'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('pl_prod_quantity', $advppp_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_quantity'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('pl_prod_total_excl_vat', $advppp_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_total_excl_vat'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('pl_prod_tax', $advppp_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_tax'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('pl_prod_total_incl_vat', $advppp_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_total_incl_vat'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('pl_prod_sales', $advppp_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_sales'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('pl_prod_cost', $advppp_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_cost'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('pl_prod_profit', $advppp_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_profit'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('pl_prod_profit_margin', $advppp_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_profit_margin'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('pl_prod_profit_markup', $advppp_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_profit_markup'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }		
		 
		 } else {
			 
		 if (in_array('ol_order_order_id', $advppp_settings_ol_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_order_id'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);			 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('ol_order_date_added', $advppp_settings_ol_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_date_added'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('ol_order_inv_no', $advppp_settings_ol_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_inv_no'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }	 
		 if (in_array('ol_order_customer', $advppp_settings_ol_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_customer'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('ol_order_email', $advppp_settings_ol_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_email'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('ol_order_customer_group', $advppp_settings_ol_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_customer_group'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('ol_order_shipping_method', $advppp_settings_ol_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_shipping_method'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('ol_order_payment_method', $advppp_settings_ol_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_payment_method'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('ol_order_status', $advppp_settings_ol_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_status'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('ol_order_store', $advppp_settings_ol_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_store'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('ol_order_currency', $advppp_settings_ol_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_currency'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('ol_prod_price', $advppp_settings_ol_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_price'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('ol_prod_quantity', $advppp_settings_ol_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_quantity'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('ol_prod_total_excl_vat', $advppp_settings_ol_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_total_excl_vat'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('ol_prod_tax', $advppp_settings_ol_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_tax'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('ol_prod_total_incl_vat', $advppp_settings_ol_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_total_incl_vat'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('ol_prod_sales', $advppp_settings_ol_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_sales'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('ol_prod_cost', $advppp_settings_ol_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_cost'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('ol_prod_profit', $advppp_settings_ol_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_profit'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('ol_prod_profit_margin', $advppp_settings_ol_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_profit_margin'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('ol_prod_profit_markup', $advppp_settings_ol_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_profit_markup'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 
		 } 
		 
		 if (in_array('cl_customer_cust_id', $advppp_settings_cl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_customer_cust_id'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);			 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }	
		 if (in_array('cl_billing_name', $advppp_settings_cl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_billing_name')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('cl_billing_company', $advppp_settings_cl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_billing_company')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('cl_billing_address_1', $advppp_settings_cl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_billing_address_1')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('cl_billing_address_2', $advppp_settings_cl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_billing_address_2')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('cl_billing_city', $advppp_settings_cl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_billing_city')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('cl_billing_zone', $advppp_settings_cl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_billing_zone')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('cl_billing_postcode', $advppp_settings_cl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_billing_postcode')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('cl_billing_country', $advppp_settings_cl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_billing_country')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('cl_customer_telephone', $advppp_settings_cl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_customer_telephone'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('cl_shipping_name', $advppp_settings_cl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_shipping_name')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('cl_shipping_company', $advppp_settings_cl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_shipping_company')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('cl_shipping_address_1', $advppp_settings_cl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_shipping_address_1')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('cl_shipping_address_2', $advppp_settings_cl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_shipping_address_2')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('cl_shipping_city', $advppp_settings_cl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_shipping_city')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('cl_shipping_zone', $advppp_settings_cl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_shipping_zone')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('cl_shipping_postcode', $advppp_settings_cl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_shipping_postcode')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('cl_shipping_country', $advppp_settings_cl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_shipping_country')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 
		 if ($export_logo_criteria) {
			$this->objPHPExcel->getActiveSheet()->insertNewRowBefore(1,1);
			$this->objPHPExcel->getActiveSheet()->insertNewRowBefore(1,1);
			$this->objPHPExcel->getActiveSheet()->insertNewRowBefore(1,1);
			$lastCell = $this->objPHPExcel->getActiveSheet()->getHighestDataColumn();
			$lastRow = $this->objPHPExcel->getActiveSheet()->getHighestDataRow();
			
			$this->objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(15);
			$this->objPHPExcel->getActiveSheet()->mergeCells('A1:B1');
			$this->objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$this->objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setRGB('DBE5F1');	
			$this->objPHPExcel->getActiveSheet()->setCellValue('C1', $this->language->get('text_report_date').": ".date($this->config->get('advppp' . $user_id . '_hour_format') == '24' ? "Y-m-d H:i:s" : "Y-m-d h:i:s A"));
			$this->objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(10);
			$this->objPHPExcel->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$this->objPHPExcel->getActiveSheet()->getStyle('C1:' . $lastCell . '1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$this->objPHPExcel->getActiveSheet()->getStyle('C1:' . $lastCell . '1')->getFill()->getStartColor()->setRGB('DBE5F1');
			$this->objPHPExcel->getActiveSheet()->mergeCells('C1:' . $lastCell . '1');
				
			//Add logo to header
			$objDrawing = new PHPExcel_Worksheet_Drawing();
			$objDrawing->setName($this->config->get('config_name'));
			$objDrawing->setDescription($this->config->get('config_name'));
			$objDrawing->setPath($logo);
			$objDrawing->setCoordinates('A2');
			$objDrawing->setWidth(155);
			$objDrawing->setOffsetX(5);
			$objDrawing->setOffsetY(20);
			$objDrawing->setResizeProportional(true);
			$objDrawing->setWorksheet($this->objPHPExcel->getActiveSheet());
			
			$this->objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(50);
			$this->objPHPExcel->getActiveSheet()->mergeCells('A2:B2');
			$this->objPHPExcel->getActiveSheet()->getStyle('A2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$this->objPHPExcel->getActiveSheet()->getStyle('A2')->getFill()->getStartColor()->setRGB('DBE5F1');	
			$this->objPHPExcel->getActiveSheet()->setCellValue('C2', $this->language->get('adv_ext_name'));
			$this->objPHPExcel->getActiveSheet()->getStyle('C2')->getFont()->setSize(24);
			$this->objPHPExcel->getActiveSheet()->getStyle('C2')->getFont()->setBold(true);
			$this->objPHPExcel->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$this->objPHPExcel->getActiveSheet()->getStyle('C2:' . $lastCell . $lastRow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->objPHPExcel->getActiveSheet()->getStyle('C2:' . $lastCell . '2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$this->objPHPExcel->getActiveSheet()->getStyle('C2:' . $lastCell . '2')->getFill()->getStartColor()->setRGB('DBE5F1');
			$this->objPHPExcel->getActiveSheet()->mergeCells('C2:' . $lastCell . '2');
			
			$this->objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(30);
			$this->objPHPExcel->getActiveSheet()->mergeCells('A3:B3');
			$this->objPHPExcel->getActiveSheet()->getStyle('A3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$this->objPHPExcel->getActiveSheet()->getStyle('A3')->getFill()->getStartColor()->setRGB('DBE5F1');	
			$this->objPHPExcel->getActiveSheet()->setCellValue('C3', $this->config->get('config_name').", ".$this->config->get('config_address').", ".$this->language->get('text_email')."".$this->config->get('config_email').", ".$this->language->get('text_telephone')."".$this->config->get('config_telephone'));
			$this->objPHPExcel->getActiveSheet()->getStyle('C3')->getFont()->setSize(14);
			$this->objPHPExcel->getActiveSheet()->getStyle('C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$this->objPHPExcel->getActiveSheet()->getStyle('C3:' . $lastCell . $lastRow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->objPHPExcel->getActiveSheet()->getStyle('C3:' . $lastCell . '3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$this->objPHPExcel->getActiveSheet()->getStyle('C3:' . $lastCell . '3')->getFill()->getStartColor()->setRGB('DBE5F1');
			$this->objPHPExcel->getActiveSheet()->mergeCells('C3:' . $lastCell . '3');
			
			$this->objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(40);
			$this->objPHPExcel->getActiveSheet()->mergeCells('A4:B4');
			$this->objPHPExcel->getActiveSheet()->setCellValue('A4', $this->language->get('text_report_criteria'));
			$this->objPHPExcel->getActiveSheet()->getStyle('A4')->getFont()->setSize(10);
			$this->objPHPExcel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);
			$this->objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$this->objPHPExcel->getActiveSheet()->getStyle('A4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$this->objPHPExcel->getActiveSheet()->getStyle('A4')->getFill()->getStartColor()->setRGB('DBE5F1');				
			$this->objPHPExcel->getActiveSheet()->mergeCells('C4:' . $lastCell . '4');
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
			$filter_criteria .= "\r";
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
			$filter_criteria .= "\r";
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
			$this->objPHPExcel->getActiveSheet()->setCellValue('C4', $filter_criteria);
			$this->objPHPExcel->getActiveSheet()->getStyle('C4')->getAlignment()->setWrapText(true);
			$this->objPHPExcel->getActiveSheet()->getStyle('C4')->getFont()->setSize(10);
			$this->objPHPExcel->getActiveSheet()->getStyle('C4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$this->objPHPExcel->getActiveSheet()->getStyle('C4:' . $lastCell . '4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$this->objPHPExcel->getActiveSheet()->getStyle('C4:' . $lastCell . '4')->getFill()->getStartColor()->setRGB('DBE5F1');				
		 }	
		 
		$freeze = ($export_logo_criteria ? 'A6' : 'A2');
		$this->objPHPExcel->getActiveSheet()->freezePane($freeze);
	}
	
	$counter = ($export_logo_criteria ? $this->mainCounter+4 : $this->mainCounter+1);
		
	foreach ($results as $result) {	
		$j = 0;
				
		if ($filter_group == 'year') {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['year']);
		} elseif ($filter_group == 'quarter') {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['year']);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);	
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['quarter']);			
		} elseif ($filter_group == 'month') {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['year']);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);	
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['month']);
		} elseif ($filter_group == 'day') {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['date_start']);
		} elseif ($filter_group == 'order') {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['order_id']);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);	
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['date_start']);
		} else {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['date_start']);
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['date_end']);				 
		}
		
		if ($filter_report == 'manufacturers' or $filter_report == 'categories' or $filter_report == 'suppliers') {
			
		if ($filter_report == 'manufacturers') {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, html_entity_decode($result['manufacturers']));
		} elseif ($filter_report == 'categories') {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, html_entity_decode($result['categories']));
		} elseif ($filter_report == 'suppliers') {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, html_entity_decode($result['suppliers']));
		}
		if (in_array('cm_sold_quantity', $advppp_settings_cm_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['sold_quantity']);
		}
		if (in_array('cm_sold_percent', $advppp_settings_cm_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);					
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->applyFromArray(array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['sold_quantity'] != 0 ? round(100 * ($result['sold_quantity'] / $result['sold_quantity_total']), 2)/100 : 0);
		}
		if (in_array('cm_total_excl_vat', $advppp_settings_cm_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['total_excl_vat_raw'] != NULL ? $result['total_excl_vat_raw'] : '0.00');
		}
		if (in_array('cm_total_tax', $advppp_settings_cm_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['total_tax_raw'] != NULL ? $result['total_tax_raw'] : '0.00');
		}
		if (in_array('cm_total_incl_vat', $advppp_settings_cm_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['total_incl_vat_raw'] != NULL ? $result['total_incl_vat_raw'] : '0.00');
		}
		if (in_array('cm_app', $advppp_settings_cm_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['app_raw'] != NULL ? $result['app_raw'] : '0.00');
		}
		if (in_array('cm_discount', $advppp_settings_cm_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['discount_raw'] != NULL ? $result['discount_raw'] : '0.00');
		}
		if (in_array('cm_refunds', $advppp_settings_cm_columns)) {
		if ($this->config->get('advppp' . $user_id . '_formula_6')) {	
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, -$result['refunds_raw'] != NULL ? -$result['refunds_raw'] : '0.00');
		} else {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['refunds_raw'] != NULL ? $result['refunds_raw'] : '0.00');
		}
		}
		if (in_array('cm_reward_points', $advppp_settings_cm_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['reward_points']);
		}
		if (in_array('cm_total_sales', $advppp_settings_cm_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['total_sales_raw'] != NULL ? $result['total_sales_raw'] : '0.00');
		}
		if (in_array('cm_total_costs', $advppp_settings_cm_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['total_costs_raw'] != NULL ? -$result['total_costs_raw'] : '0.00');
		}		
		if (in_array('cm_total_profit', $advppp_settings_cm_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['total_profit_raw'] != NULL ? $result['total_profit_raw'] : '0.00');
		}
		if (in_array('cm_total_profit_margin', $advppp_settings_cm_columns)) {
		if ($result['sold_quantity'] > 0) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->applyFromArray(array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('%','',$result['total_profit_margin'])/100);
		} else {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->applyFromArray(array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '0');
		}
		}
		if (in_array('cm_total_profit_markup', $advppp_settings_cm_columns)) {
		if ($result['sold_quantity'] > 0) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->applyFromArray(array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('%','',$result['total_profit_markup'])/100);
		} else {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->applyFromArray(array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '0');
		}
		}
		
		} elseif ($filter_report == 'products_options') {

		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['option_name']);

		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['sold_quantity']);

		$col = PHPExcel_Cell::stringFromColumnIndex($j++);					
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->applyFromArray(array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['sold_quantity'] != 0 ? round(100 * ($result['sold_quantity'] / $result['sold_quantity_total']), 2)/100 : 0);
		 
		} else {

		if (in_array('mv_id', $advppp_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['product_id']);
		}
		if (in_array('mv_sku', $advppp_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['sku']);
		}
		if (in_array('mv_name', $advppp_settings_mv_columns)) {		
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, html_entity_decode($result['name']));	
		if ($filter_report == 'products_purchased_with_options' or $filter_report == 'products_abandoned_orders') {
		$t = $j;	
		if ($result['option']) {
		foreach ($result['option'] as $index => $option) {
		if ($option['name'] == $option_names[$index]['name']) {				
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $option['value']);
		//$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $option['name'].': '.$option['value']);		
		} else {
		foreach ($result['option'] as $option) {
		foreach ($option_names as $option_name) {			
		if ($option['name'] == $option_name['name']) {		
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $option['value']);
		//$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $option['name'].': '.$option['value']);	
		} else {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');	
		}			
		}
		}			
		}
		}	
		} else {
		foreach ($option_names as $option_name) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		}
		$j=$t+$n;		
		}
		}
		if (in_array('mv_model', $advppp_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['model']);
		}
		if (in_array('mv_category', $advppp_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, html_entity_decode($result['categories']));
		}
		if (in_array('mv_manufacturer', $advppp_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, html_entity_decode($result['manufacturers']));
		}
		if (in_array('mv_supplier', $advppp_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, html_entity_decode($result['suppliers']));
		}		
		if (in_array('mv_attribute', $advppp_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, html_entity_decode(str_replace('<br>','; ',$result['attribute'])));
		}	
		if (in_array('mv_status', $advppp_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['status']);
		}
		if (in_array('mv_location', $advppp_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['location']);
		}
		if (in_array('mv_tax_class', $advppp_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['tax_class']);
		}
		if (in_array('mv_price', $advppp_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['price_raw']);
		}
		if (in_array('mv_cost', $advppp_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, -$result['cost_raw']);
		}
		if (in_array('mv_profit', $advppp_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['profit_raw']);
		}
		if (in_array('mv_profit_margin', $advppp_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->applyFromArray(array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('%','',$result['profit_margin'])/100);					
		}
		if (in_array('mv_profit_markup', $advppp_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->applyFromArray(array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('%','',$result['profit_markup'])/100);					
		}		
		if (in_array('mv_viewed', $advppp_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['viewed']);
		}
		if (in_array('mv_stock_quantity', $advppp_settings_mv_columns)) {
		if ($filter_report == 'products_purchased_with_options' or $filter_report == 'products_abandoned_orders') {
		if ($result['option']) {	
		$oquantity = '';
		foreach ($result['option'] as $option) {
		$oquantity .= $option['quantity'].'; ';
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['stock_quantity'] . " [" . rtrim($oquantity, "; ") . "]");				
		} else {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');	
		}
		} else {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['stock_quantity']);
		}			
		}	
		if (in_array('mv_sold_quantity', $advppp_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['sold_quantity']);
		if ($filter_report == 'products_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		}
		if (in_array('mv_sold_percent', $advppp_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->applyFromArray(array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['sold_quantity'] != 0 ? round(100 * ($result['sold_quantity'] / $result['sold_quantity_total']), 2)/100 : 0);
		if ($filter_report == 'products_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}
		}
		if (in_array('mv_total_excl_vat', $advppp_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['total_excl_vat_raw'] != NULL ? $result['total_excl_vat_raw'] : '0.00');
		if ($filter_report == 'products_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		}
		if (in_array('mv_total_tax', $advppp_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['total_tax_raw'] != NULL ? $result['total_tax_raw'] : '0.00');
		if ($filter_report == 'products_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		}
		if (in_array('mv_total_incl_vat', $advppp_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['total_incl_vat_raw'] != NULL ? $result['total_incl_vat_raw'] : '0.00');
		if ($filter_report == 'products_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		}
		if (in_array('mv_app', $advppp_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['app_raw'] != NULL ? $result['app_raw'] : '0.00');
		if ($filter_report == 'products_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		}
		if (in_array('mv_discount', $advppp_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['discount_raw'] != NULL ? $result['discount_raw'] : '0.00');
		if ($filter_report == 'products_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		}
		if (in_array('mv_refunds', $advppp_settings_mv_columns)) {
		if ($this->config->get('advppp' . $user_id . '_formula_6')) {	
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, -$result['refunds_raw'] != NULL ? -$result['refunds_raw'] : '0.00');
		if ($filter_report == 'products_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		} else {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['refunds_raw'] != NULL ? $result['refunds_raw'] : '0.00');
		if ($filter_report == 'products_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		}
		}
		if (in_array('mv_reward_points', $advppp_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['reward_points']);
		if ($filter_report == 'products_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		}
		if (in_array('mv_total_sales', $advppp_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['total_sales_raw'] != NULL ? $result['total_sales_raw'] : '0.00');
		if ($filter_report == 'products_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		}
		if (in_array('mv_total_costs', $advppp_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['total_costs_raw'] != NULL ? -$result['total_costs_raw'] : '0.00');
		if ($filter_report == 'products_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		}
		if (in_array('mv_total_profit', $advppp_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['total_profit_raw'] != NULL ? $result['total_profit_raw'] : '0.00');
		if ($filter_report == 'products_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		}
		if (in_array('mv_total_profit_margin', $advppp_settings_mv_columns)) {	
		if ($result['sold_quantity'] > 0) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->applyFromArray(array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('%','',$result['total_profit_margin'])/100);
		if ($filter_report == 'products_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		} else {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->applyFromArray(array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '0');
		if ($filter_report == 'products_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		}
		}
		if (in_array('mv_total_profit_markup', $advppp_settings_mv_columns)) {	
		if ($result['sold_quantity'] > 0) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->applyFromArray(array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('%','',$result['total_profit_markup'])/100);
		if ($filter_report == 'products_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		} else {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->applyFromArray(array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '0');
		if ($filter_report == 'products_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		}
		}
		 
		}

		if ($filter_report == 'manufacturers' or $filter_report == 'categories' or $filter_report == 'suppliers' or $filter_report == 'products_options') {
			
		$dcounter = $counter;	
		if (in_array('pl_prod_order_id', $advppp_settings_pl_columns)) {		
		$counter = $dcounter;		
		$details = explode('<br>', $result['product_ord_id']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('pl_prod_date_added', $advppp_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_ord_date']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('pl_prod_inv_no', $advppp_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_inv_no']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $counter, str_replace('&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}	
		if (in_array('pl_prod_id', $advppp_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_prod_id']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}	
		if (in_array('pl_prod_sku', $advppp_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_sku']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}	
		if (in_array('pl_prod_model', $advppp_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_model']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}	
		if (in_array('pl_prod_name', $advppp_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_name']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, html_entity_decode($value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}	
		if (in_array('pl_prod_option', $advppp_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_option']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}	
		if ($filter_report == 'manufacturers') {
		if (in_array('pl_prod_category', $advppp_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_category']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, html_entity_decode(str_replace('&nbsp;','',$value)));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}			
		if (in_array('pl_prod_supplier', $advppp_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_supplier']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, html_entity_decode(str_replace('&nbsp;','',$value)));
		$j = $c;
		$counter += 1;		
		}	
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}			
		} elseif ($filter_report == 'categories') {
		if (in_array('pl_prod_manu', $advppp_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_manu']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, html_entity_decode(str_replace('&nbsp;','',$value)));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}			
		if (in_array('pl_prod_supplier', $advppp_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_supplier']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, html_entity_decode(str_replace('&nbsp;','',$value)));
		$j = $c;
		$counter += 1;		
		}	
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}			
		} elseif ($filter_report == 'suppliers') {
		if (in_array('pl_prod_category', $advppp_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_category']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, html_entity_decode(str_replace('&nbsp;','',$value)));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}			
		if (in_array('pl_prod_manu', $advppp_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_manu']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, html_entity_decode(str_replace('&nbsp;','',$value)));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}	
		} elseif ($filter_report == 'products_options') {
		if (in_array('pl_prod_category', $advppp_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_category']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, html_entity_decode(str_replace('&nbsp;','',$value)));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}			
		if (in_array('pl_prod_manu', $advppp_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_manu']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, html_entity_decode(str_replace('&nbsp;','',$value)));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('pl_prod_supplier', $advppp_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_supplier']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, html_entity_decode(str_replace('&nbsp;','',$value)));
		$j = $c;
		$counter += 1;		
		}	
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}	
		} 
		if (in_array('pl_prod_attributes', $advppp_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_attributes']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, html_entity_decode(str_replace('&nbsp;','',$value)));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}			
		if (in_array('pl_prod_currency', $advppp_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_currency']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}			
		if (in_array('pl_prod_price', $advppp_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_price']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		if ($filter_report == 'products_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('pl_prod_quantity', $advppp_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_quantity']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		if ($filter_report == 'products_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('pl_prod_total_excl_vat', $advppp_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_total_excl_vat']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		if ($filter_report == 'products_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('pl_prod_tax', $advppp_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_tax']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		if ($filter_report == 'products_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('pl_prod_total_incl_vat', $advppp_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_total_incl_vat']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		if ($filter_report == 'products_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('pl_prod_sales', $advppp_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_sales']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		if ($filter_report == 'products_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('pl_prod_cost', $advppp_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_cost']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, -str_replace('-','',$value));
		if ($filter_report == 'products_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('pl_prod_profit', $advppp_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_profit']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		if ($filter_report == 'products_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('pl_prod_profit_margin', $advppp_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_profit_margin']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->applyFromArray(array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('%','',$value)/100);
		if ($filter_report == 'products_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('pl_prod_profit_markup', $advppp_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_profit_markup']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->applyFromArray(array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('%','',$value)/100);
		if ($filter_report == 'products_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}	
		
		} else {
			
		$dcounter = $counter;	
		if (in_array('ol_order_order_id', $advppp_settings_ol_columns)) {		
		$counter = $dcounter;		
		$details = explode('<br>', $result['order_prod_ord_id']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('ol_order_date_added', $advppp_settings_ol_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['order_prod_ord_date']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('ol_order_inv_no', $advppp_settings_ol_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['order_prod_inv_no']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}	
		if (in_array('ol_order_customer', $advppp_settings_ol_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['order_prod_name']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}	
		if (in_array('ol_order_email', $advppp_settings_ol_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['order_prod_email']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}	
		if (in_array('ol_order_customer_group', $advppp_settings_ol_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['order_prod_group']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}	
		if (in_array('ol_order_shipping_method', $advppp_settings_ol_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['order_prod_shipping_method']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}	
		if (in_array('ol_order_payment_method', $advppp_settings_ol_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['order_prod_payment_method']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}	
		if (in_array('ol_order_status', $advppp_settings_ol_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['order_prod_status']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}	
		if (in_array('ol_order_store', $advppp_settings_ol_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['order_prod_store']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, html_entity_decode($value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}	
		if (in_array('ol_order_currency', $advppp_settings_ol_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['order_prod_currency']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}			
		if (in_array('ol_prod_price', $advppp_settings_ol_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['order_prod_price']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		if ($filter_report == 'products_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('ol_prod_quantity', $advppp_settings_ol_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['order_prod_quantity']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		if ($filter_report == 'products_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('ol_prod_total_excl_vat', $advppp_settings_ol_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['order_prod_total_excl_vat']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		if ($filter_report == 'products_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('ol_prod_tax', $advppp_settings_ol_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['order_prod_tax']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		if ($filter_report == 'products_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('ol_prod_total_incl_vat', $advppp_settings_ol_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['order_prod_total_incl_vat']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		if ($filter_report == 'products_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('ol_prod_sales', $advppp_settings_ol_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['order_prod_sales']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		if ($filter_report == 'products_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('ol_prod_cost', $advppp_settings_ol_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['order_prod_cost']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, -str_replace('-','',$value));
		if ($filter_report == 'products_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('ol_prod_profit', $advppp_settings_ol_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['order_prod_profit']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		if ($filter_report == 'products_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('ol_prod_profit_margin', $advppp_settings_ol_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['order_prod_profit_margin']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->applyFromArray(array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('%','',$value)/100);
		if ($filter_report == 'products_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('ol_prod_profit_markup', $advppp_settings_ol_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['order_prod_profit_markup']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->applyFromArray(array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('%','',$value)/100);
		if ($filter_report == 'products_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		
		}
		
		if (in_array('cl_customer_cust_id', $advppp_settings_cl_columns)) {		
		$counter = $dcounter;		
		$details = explode('<br>', $result['customer_cust_id']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}	
		if (in_array('cl_billing_name', $advppp_settings_cl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['billing_name']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('cl_billing_company', $advppp_settings_cl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['billing_company']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('cl_billing_address_1', $advppp_settings_cl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['billing_address_1']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('cl_billing_address_2', $advppp_settings_cl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['billing_address_2']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('cl_billing_city', $advppp_settings_cl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['billing_city']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('cl_billing_zone', $advppp_settings_cl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['billing_zone']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('cl_billing_postcode', $advppp_settings_cl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['billing_postcode']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('cl_billing_country', $advppp_settings_cl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['billing_country']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('cl_customer_telephone', $advppp_settings_cl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['customer_telephone']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('cl_shipping_name', $advppp_settings_cl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['shipping_name']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('cl_shipping_company', $advppp_settings_cl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['shipping_company']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('cl_shipping_address_1', $advppp_settings_cl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['shipping_address_1']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('cl_shipping_address_2', $advppp_settings_cl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['shipping_address_2']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('cl_shipping_city', $advppp_settings_cl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['shipping_city']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('cl_shipping_zone', $advppp_settings_cl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['shipping_zone']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('cl_shipping_postcode', $advppp_settings_cl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['shipping_postcode']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('cl_shipping_country', $advppp_settings_cl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['shipping_country']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		
		$counter++;
		$this->mainCounter++;
	}
	
	if (!isset($_GET['cron'])) {
		$filename = "products_profit_report_basic_details_".date($this->config->get('advppp' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A");
		header('Expires: 0');
		header('Cache-control: private');
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=UTF-8; encoding=UTF-8');
		header('Content-Disposition: attachment;filename='.$filename.".xlsx");
		header('Content-Transfer-Encoding: UTF-8');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');	
		$objWriter->save('php://output');	
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
		
		$filename = $file_name."_".date($this->config->get('advppp' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A").".xlsx";
		$file_to_download = $server . $file_save_path . '/' . $file_name."_".date($this->config->get('advppp' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A").".xlsx";
		$file = $file_path . '/' . $file_name."_".date($this->config->get('advppp' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A").".xlsx";		
		
		$objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');	
		$objWriter->save($file);
		
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
	exit();
	}
?>