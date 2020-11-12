<?php
static $config = NULL;
static $log = NULL;

// Error Handler
function error_handler_for_export($errno, $errstr, $errfile, $errline) {
	global $config;
	global $log;
	
	switch ($errno) {
		case E_NOTICE:
		case E_USER_NOTICE:
			$errors = "Notice";
			break;
		case E_WARNING:
		case E_USER_WARNING:
			$errors = "Warning";
			break;
		case E_ERROR:
		case E_USER_ERROR:
			$errors = "Fatal Error";
			break;
		default:
			$errors = "Unknown";
			break;
	}
		
	if (($errors=='Warning') || ($errors=='Unknown')) {
		return true;
	}

	if ($config->get('config_error_display')) {
		echo '<b>' . $errors . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
	}
	
	if ($config->get('config_error_log')) {
		$log->write('PHP ' . $errors . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
	}

	return true;
}

function fatal_error_shutdown_handler_for_export() {
	$last_error = error_get_last();
	if ($last_error['type'] === E_ERROR) {
		// fatal error
		error_handler_for_export(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
	}
}

class ControllerReportAdvProductsProfit extends Controller { 
	private $error = array();
	
	public function index() {
		$this->load->language('report/adv_products_profit');
		
		$query1 = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE code = 'adv_profit_module'");
		if (empty($query1->num_rows)) {	
			$this->session->data['success'] = $this->language->get('error_installed1');
			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));		
		}

		$query2 = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE code = 'adv_reports_products_profit'");
		if (empty($query2->num_rows)) {	
			$this->session->data['success'] = $this->language->get('error_installed2');
			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));		
		}
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('report/adv_products_profit');

	    $this->document->addScript('view/javascript/bootstrap/js/bootstrap-multiselect.js');
	    $this->document->addStyle('view/javascript/bootstrap/css/bootstrap-multiselect.css');
		$this->document->addScript('view/javascript/bootstrap/js/bootstrap-select.min.js');
		$this->document->addStyle('view/javascript/bootstrap/css/bootstrap-select.css');
		
		if (isset($this->request->get['filter_date_start'])) {
			$this->session->data['filter_date_start'] = $filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$this->session->data['filter_date_start'] = $filter_date_start = '';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$this->session->data['filter_date_end'] = $filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$this->session->data['filter_date_end'] = $filter_date_end = '';
		}

		$data['ranges'] = array();
		
		$data['ranges'][] = array(
			'text'  => $this->language->get('stat_custom'),
			'value' => 'custom',
			'style' => 'color:#666',
		);			
		$data['ranges'][] = array(
			'text'  => $this->language->get('stat_today'),
			'value' => 'today',
			'style' => 'color:#090',
		);
		$data['ranges'][] = array(
			'text'  => $this->language->get('stat_yesterday'),
			'value' => 'yesterday',
			'style' => 'color:#090',
		);
		$data['ranges'][] = array(
			'text'  => $this->language->get('stat_week'),
			'value' => 'week',
			'style' => 'color:#090',
		);
		$data['ranges'][] = array(
			'text'  => $this->language->get('stat_month'),
			'value' => 'month',
			'style' => 'color:#090',
		);					
		$data['ranges'][] = array(
			'text'  => $this->language->get('stat_quarter'),
			'value' => 'quarter',
			'style' => 'color:#090',
		);
		$data['ranges'][] = array(
			'text'  => $this->language->get('stat_year'),
			'value' => 'year',
			'style' => 'color:#090',
		);
		$data['ranges'][] = array(
			'text'  => $this->language->get('stat_current_week'),
			'value' => 'current_week',
			'style' => 'color:#06C',
		);
		$data['ranges'][] = array(
			'text'  => $this->language->get('stat_current_month'),
			'value' => 'current_month',
			'style' => 'color:#06C',
		);	
		$data['ranges'][] = array(
			'text'  => $this->language->get('stat_current_quarter'),
			'value' => 'current_quarter',
			'style' => 'color:#06C',
		);			
		$data['ranges'][] = array(
			'text'  => $this->language->get('stat_current_year'),
			'value' => 'current_year',
			'style' => 'color:#06C',
		);			
		$data['ranges'][] = array(
			'text'  => $this->language->get('stat_last_week'),
			'value' => 'last_week',
			'style' => 'color:#F90',
		);
		$data['ranges'][] = array(
			'text'  => $this->language->get('stat_last_month'),
			'value' => 'last_month',
			'style' => 'color:#F90',
		);	
		$data['ranges'][] = array(
			'text'  => $this->language->get('stat_last_quarter'),
			'value' => 'last_quarter',
			'style' => 'color:#F90',
		);			
		$data['ranges'][] = array(
			'text'  => $this->language->get('stat_last_year'),
			'value' => 'last_year',
			'style' => 'color:#F90',
		);			
		$data['ranges'][] = array(
			'text'  => $this->language->get('stat_all_time'),
			'value' => 'all_time',
			'style' => 'color:#F00',
		);
		
		if (isset($this->request->get['filter_range'])) {
			$this->session->data['filter_range'] = $filter_range = $this->request->get['filter_range'];
		} else {
			$this->session->data['filter_range'] = $filter_range = 'none'; //show no data in Statistical Range by default
		}

		$data['report'] = array();

		$data['report'][] = array(
			'text'		=> $this->language->get('text_all_products'),
			'value'		=> 'all_products_with_without_orders',
			'subtext'	=> $this->language->get('text_with_without_orders'),
			'divider' 	=> '',
		);		
		$data['report'][] = array(
			'text'  	=> $this->language->get('text_products_purchased'),
			'value' 	=> 'products_purchased_without_options',
			'subtext'	=> $this->language->get('text_without_options'),
			'divider' 	=> '',
		);	
		$data['report'][] = array(
			'text'  	=> $this->language->get('text_products_purchased'),
			'value' 	=> 'products_purchased_with_options',
			'subtext'	=> $this->language->get('text_with_options'),
			'divider' 	=> '',
		);		
		$data['report'][] = array(
			'text'  	=> $this->language->get('text_new_products_purchased'),
			'value' 	=> 'new_products_purchased',
			'subtext'	=> $this->language->get('text_new_products'),
			'divider' 	=> '',
		);	
		$data['report'][] = array(
			'text'  	=> $this->language->get('text_old_products_purchased'),
			'value' 	=> 'old_products_purchased',
			'subtext'	=> $this->language->get('text_old_products'),
			'divider' 	=> '',
		);	
		$data['report'][] = array(
			'text'  	=> $this->language->get('text_products'),
			'value' 	=> 'products_without_orders',
			'subtext'	=> $this->language->get('text_without_orders'),
			'divider' 	=> '',
		);
		$data['report'][] = array(
			'text'  	=> $this->language->get('text_product_options'),
			'value' 	=> 'products_options',
			'subtext'	=> $this->language->get('text_products_options'),
			'divider' 	=> '',
		);
		$data['report'][] = array(
			'text'  	=> $this->language->get('text_categories'),
			'value' 	=> 'categories',
			'subtext'	=> '',	
			'divider' 	=> '',
		);	
		$data['report'][] = array(
			'text'  	=> $this->language->get('text_manufacturers'),
			'value' 	=> 'manufacturers',
			'subtext'	=> '',	
			'divider' 	=> '',
		);	
		$data['report'][] = array(
			'text'  	=> $this->language->get('text_suppliers'),
			'value' 	=> 'suppliers',
			'subtext'	=> '',	
			'divider' 	=> '',
		);			
		$data['report'][] = array(
			'text'  	=> '',
			'value' 	=> '',
			'subtext' 	=> '',
			'divider'	=> 'true',
		);		
		$data['report'][] = array(
			'text'  	=> $this->language->get('text_products_abandoned_orders'),
			'value' 	=> 'products_abandoned_orders',
			'subtext'	=> $this->language->get('text_products_abandoned'),	
			'divider' 	=> '',
		);			
		$data['report'][] = array(
			'text'  	=> $this->language->get('text_products_shopping_carts'),
			'value' 	=> 'products_shopping_carts',
			'subtext'	=> $this->language->get('text_shopping_carts'),	
			'divider' 	=> '',
		);
		$data['report'][] = array(
			'text'  	=> $this->language->get('text_products_wishlists'),
			'value' 	=> 'products_wishlists',
			'subtext'	=> $this->language->get('text_customer_wishlists'),	
			'divider' 	=> '',
		);	
		
		if (isset($this->request->get['filter_report'])) {
			$this->session->data['filter_report'] = $filter_report = $this->request->get['filter_report'];
		} else {
			$this->session->data['filter_report'] = $filter_report = 'products_purchased_without_options'; //show Products Purchased withou options in Report By default
		}

		$data['details'] = array();

		$data['details'][] = array(
			'text'  	=> $this->language->get('text_no_details'),
			'value' 	=> 'no_details',
			'subtext' 	=> '',
		);
		$data['details'][] = array(
			'text'  	=> $this->language->get('text_basic_details'),
			'value' 	=> 'basic_details',
			'subtext' 	=> '',
		);
		$data['details'][] = array(
			'text'  	=> $this->language->get('text_all_details'),
			'value' 	=> 'all_details_products',
			'subtext'	=> $this->language->get('text_all_details_products'),	
		);
		$data['details'][] = array(
			'text'  	=> $this->language->get('text_all_details'),
			'value' 	=> 'all_details_orders',
			'subtext'	=> $this->language->get('text_all_details_orders'),	
		);		
		
		if (isset($this->request->get['filter_details'])) {
			$this->session->data['filter_details'] = $filter_details = $this->request->get['filter_details'];
		} else {
			$this->session->data['filter_details'] = $filter_details = 'no_details';
		}	
		
		$data['group'] = array();

		$data['group'][] = array(
			'text'  => $this->language->get('text_no_group'),
			'value' => 'no_group',
		);
		$data['group'][] = array(
			'text'  => $this->language->get('text_year'),
			'value' => 'year',
		);
		$data['group'][] = array(
			'text'  => $this->language->get('text_quarter'),
			'value' => 'quarter',
		);
		$data['group'][] = array(
			'text'  => $this->language->get('text_month'),
			'value' => 'month',
		);
		$data['group'][] = array(
			'text'  => $this->language->get('text_week'),
			'value' => 'week',
		);
		$data['group'][] = array(
			'text'  => $this->language->get('text_day'),
			'value' => 'day',
		);
		$data['group'][] = array(
			'text'  => $this->language->get('text_order'),
			'value' => 'order',
		);
		
		if (isset($this->request->get['filter_group'])) {
			$this->session->data['filter_group'] = $filter_group = $this->request->get['filter_group'];
		} else {
			$this->session->data['filter_group'] = $filter_group = 'no_group';
		}		

		if ($this->config->get('advppp' . $this->user->getId() . '_settings_scw_columns')) {
			$advppp_settings_scw_columns = $this->config->get('advppp' . $this->user->getId() . '_settings_scw_columns');
		} else {
			$advppp_settings_scw_columns = array();
		}
		
		if ($this->config->get('advppp' . $this->user->getId() . '_settings_pnp_columns')) {
			$advppp_settings_pnp_columns = $this->config->get('advppp' . $this->user->getId() . '_settings_pnp_columns');
		} else {
			$advppp_settings_pnp_columns = array();
		}
		
		if ($this->config->get('advppp' . $this->user->getId() . '_settings_cm_columns')) {
			$advppp_settings_cm_columns = $this->config->get('advppp' . $this->user->getId() . '_settings_cm_columns');
		} else {
			$advppp_settings_cm_columns = array();
		}	
		
		if ($this->config->get('advppp' . $this->user->getId() . '_settings_mv_columns')) {
			$advppp_settings_mv_columns = $this->config->get('advppp' . $this->user->getId() . '_settings_mv_columns');
		} else {
			$advppp_settings_mv_columns = array();
		}
		
		$data['sort'] = array();

		$data['sort'][] = array(
			'text'  => $this->language->get('column_date'),
			'value' => 'date',
		);
		if ($filter_report == 'products_shopping_carts' or $filter_report == 'products_wishlists') {
		if (!$advppp_settings_scw_columns or (in_array('scw_id', $advppp_settings_scw_columns))) {	
		$data['sort'][] = array(
			'text'  => $this->language->get('column_id'),
			'value' => 'id',
		);
		}	
		if (in_array('scw_sku', $advppp_settings_scw_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_sku'),
			'value' => 'sku',
		);
		}
		if (!$advppp_settings_scw_columns or (in_array('scw_name', $advppp_settings_scw_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_prod_name'),
			'value' => 'name',
		);
		}
		if (!$advppp_settings_scw_columns or (in_array('scw_model', $advppp_settings_scw_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_model'),
			'value' => 'model',
		);
		}
		if (!$advppp_settings_scw_columns or (in_array('scw_category', $advppp_settings_scw_columns))) {	
		$data['sort'][] = array(
			'text'  => $this->language->get('column_category'),
			'value' => 'category',
		);
		}
		if (!$advppp_settings_scw_columns or (in_array('scw_manufacturer', $advppp_settings_scw_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_manufacturer'),
			'value' => 'manufacturer',
		);
		}
		if (!$advppp_settings_scw_columns or (in_array('scw_supplier', $advppp_settings_scw_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_supplier'),
			'value' => 'supplier',
		);
		}		
		if (in_array('scw_attribute', $advppp_settings_scw_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_attribute'),
			'value' => 'attribute',
		);
		}
		if (!$advppp_settings_scw_columns or (in_array('scw_status', $advppp_settings_scw_columns))) {			
		$data['sort'][] = array(
			'text'  => $this->language->get('column_status'),
			'value' => 'status',
		);
		}
		if (in_array('scw_location', $advppp_settings_scw_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_location'),
			'value' => 'location',
		);
		}
		if (in_array('scw_tax_class', $advppp_settings_scw_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_tax_class'),
			'value' => 'tax_class',
		);
		}
		if (in_array('scw_price', $advppp_settings_scw_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_price'),
			'value' => 'price',
		);
		}
		if (in_array('scw_cost', $advppp_settings_scw_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_cost'),
			'value' => 'cost',
		);
		}
		if (in_array('scw_profit', $advppp_settings_scw_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_profit'),
			'value' => 'profit',
		);
		}
		if (in_array('scw_profit_margin', $advppp_settings_scw_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_profit_margin') . ' [%]',
			'value' => 'profit_margin',
		);
		}
		if (in_array('scw_profit_markup', $advppp_settings_scw_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_profit_markup') . ' [%]',
			'value' => 'profit_markup',
		);
		}		
		if (in_array('scw_viewed', $advppp_settings_scw_columns)) {	
		$data['sort'][] = array(
			'text'  => $this->language->get('column_viewed'),
			'value' => 'viewed',
		);
		}		
		if (!$advppp_settings_scw_columns or (in_array('scw_stock_quantity', $advppp_settings_scw_columns))) {			
		$data['sort'][] = array(
			'text'  => $this->language->get('column_stock_quantity'),
			'value' => 'stock_quantity',
		);
		}
		if ($filter_report == 'products_shopping_carts') {
		if (!$advppp_settings_scw_columns or (in_array('scw_cart_quantity', $advppp_settings_scw_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_cart_quantity'),
			'value' => 'cart_quantity',
		);
		}
		} else if ($filter_report == 'products_wishlists') {
		if (!$advppp_settings_scw_columns or (in_array('scw_wishlist_quantity', $advppp_settings_scw_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_wishlist_quantity'),
			'value' => 'wishlist_quantity',
		);
		}
		}		
		} else if ($filter_report == 'products_without_orders') {
		if (!$advppp_settings_pnp_columns or (in_array('pnp_id', $advppp_settings_pnp_columns))) {	
		$data['sort'][] = array(
			'text'  => $this->language->get('column_id'),
			'value' => 'id',
		);
		}	
		if (in_array('pnp_sku', $advppp_settings_pnp_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_sku'),
			'value' => 'sku',
		);
		}
		if (!$advppp_settings_pnp_columns or (in_array('pnp_name', $advppp_settings_pnp_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_prod_name'),
			'value' => 'name',
		);
		}
		if (!$advppp_settings_pnp_columns or (in_array('pnp_model', $advppp_settings_pnp_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_model'),
			'value' => 'model',
		);
		}
		if (!$advppp_settings_pnp_columns or (in_array('pnp_category', $advppp_settings_pnp_columns))) {	
		$data['sort'][] = array(
			'text'  => $this->language->get('column_category'),
			'value' => 'category',
		);
		}
		if (!$advppp_settings_pnp_columns or (in_array('pnp_manufacturer', $advppp_settings_pnp_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_manufacturer'),
			'value' => 'manufacturer',
		);
		}
		if (!$advppp_settings_pnp_columns or (in_array('pnp_supplier', $advppp_settings_pnp_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_supplier'),
			'value' => 'supplier',
		);
		}		
		if (in_array('pnp_attribute', $advppp_settings_pnp_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_attribute'),
			'value' => 'attribute',
		);
		}
		if (!$advppp_settings_pnp_columns or (in_array('pnp_status', $advppp_settings_pnp_columns))) {			
		$data['sort'][] = array(
			'text'  => $this->language->get('column_status'),
			'value' => 'status',
		);
		}
		if (in_array('pnp_location', $advppp_settings_pnp_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_location'),
			'value' => 'location',
		);
		}
		if (in_array('pnp_tax_class', $advppp_settings_pnp_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_tax_class'),
			'value' => 'tax_class',
		);
		}
		if (in_array('pnp_price', $advppp_settings_pnp_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_price'),
			'value' => 'price',
		);
		}
		if (in_array('pnp_cost', $advppp_settings_pnp_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_cost'),
			'value' => 'cost',
		);
		}
		if (in_array('pnp_profit', $advppp_settings_pnp_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_profit'),
			'value' => 'profit',
		);
		}
		if (in_array('pnp_profit_margin', $advppp_settings_pnp_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_profit_margin') . ' [%]',
			'value' => 'profit_margin',
		);
		}
		if (in_array('pnp_profit_markup', $advppp_settings_pnp_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_profit_markup') . ' [%]',
			'value' => 'profit_markup',
		);
		}		
		if (in_array('pnp_viewed', $advppp_settings_pnp_columns)) {	
		$data['sort'][] = array(
			'text'  => $this->language->get('column_viewed'),
			'value' => 'viewed',
		);
		}		
		if (!$advppp_settings_pnp_columns or (in_array('pnp_stock_quantity', $advppp_settings_pnp_columns))) {			
		$data['sort'][] = array(
			'text'  => $this->language->get('column_stock_quantity'),
			'value' => 'stock_quantity',
		);
		}
		} else if ($filter_report == 'manufacturers' or $filter_report == 'categories' or $filter_report == 'suppliers') {
		if ($filter_report == 'categories') {
		if (!$advppp_settings_cm_columns or (in_array('cm_category', $advppp_settings_cm_columns))) {	
		$data['sort'][] = array(
			'text'  => $this->language->get('column_category'),
			'value' => 'category',
		);
		}
		} else if ($filter_report == 'manufacturers') {
		if (!$advppp_settings_cm_columns or (in_array('cm_manufacturer', $advppp_settings_cm_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_manufacturer'),
			'value' => 'manufacturer',
		);
		}
		} else if ($filter_report == 'suppliers') {
		if (!$advppp_settings_cm_columns or (in_array('cm_supplier', $advppp_settings_cm_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_supplier'),
			'value' => 'supplier',
		);
		}
		}		
		if (!$advppp_settings_cm_columns or (in_array('cm_sold_quantity', $advppp_settings_cm_columns))) {			
		$data['sort'][] = array(
			'text'  => $this->language->get('column_sold_quantity'),
			'value' => 'sold_quantity',
		);
		}
		if (!$advppp_settings_cm_columns or (in_array('cm_total_excl_vat', $advppp_settings_cm_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_prod_total_excl_vat'),
			'value' => 'total_excl_vat',
		);
		}
		if (!$advppp_settings_cm_columns or (in_array('cm_total_tax', $advppp_settings_cm_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_total_tax'),
			'value' => 'total_tax',
		);
		}
		if (!$advppp_settings_cm_columns or (in_array('cm_total_incl_vat', $advppp_settings_cm_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_prod_total_incl_vat'),
			'value' => 'total_incl_vat',
		);
		}
		if (!$advppp_settings_cm_columns or (in_array('cm_app', $advppp_settings_cm_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_app'),
			'value' => 'app',
		);
		}
		if (!$advppp_settings_cm_columns or (in_array('cm_discount', $advppp_settings_cm_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_product_discount'),
			'value' => 'discount',
		);
		}		
		if (!$advppp_settings_cm_columns or (in_array('cm_refunds', $advppp_settings_cm_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_product_refunds'),
			'value' => 'refunds',
		);
		}
		if (in_array('cm_reward_points', $advppp_settings_cm_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_product_reward_points'),
			'value' => 'reward_points',
		);
		}
		if (!$advppp_settings_cm_columns or (in_array('cm_total_sales', $advppp_settings_cm_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_total_sales'),
			'value' => 'total_sales',
		);
		}		
		if (!$advppp_settings_cm_columns or (in_array('cm_total_costs', $advppp_settings_cm_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_total_costs'),
			'value' => 'total_costs',
		);
		}
		if (!$advppp_settings_cm_columns or (in_array('cm_total_profit', $advppp_settings_cm_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_total_profit'),
			'value' => 'total_profit',
		);
		}
		if (!$advppp_settings_cm_columns or (in_array('cm_total_profit_margin', $advppp_settings_cm_columns))) {	
		$data['sort'][] = array(
			'text'  => $this->language->get('column_total_profit_margin'),
			'value' => 'total_profit_margin',
		);
		}	
		if (!$advppp_settings_cm_columns or (in_array('cm_total_profit_markup', $advppp_settings_cm_columns))) {	
		$data['sort'][] = array(
			'text'  => $this->language->get('column_total_profit_markup'),
			'value' => 'total_profit_markup',
		);
		}
		} else {
		if (!$advppp_settings_mv_columns or (in_array('mv_id', $advppp_settings_mv_columns))) {
		$data['sort'][] = array(
			'text'  => $this->language->get('column_id'),
			'value' => 'id',
		);
		}	
		if (in_array('mv_sku', $advppp_settings_mv_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_sku'),
			'value' => 'sku',
		);
		}
		if (!$advppp_settings_mv_columns or (in_array('mv_name', $advppp_settings_mv_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_prod_name'),
			'value' => 'name',
		);
		}
		if (!$advppp_settings_mv_columns or (in_array('mv_model', $advppp_settings_mv_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_model'),
			'value' => 'model',
		);
		}
		if (!$advppp_settings_mv_columns or (in_array('mv_category', $advppp_settings_mv_columns))) {	
		$data['sort'][] = array(
			'text'  => $this->language->get('column_category'),
			'value' => 'category',
		);
		}
		if (!$advppp_settings_mv_columns or (in_array('mv_manufacturer', $advppp_settings_mv_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_manufacturer'),
			'value' => 'manufacturer',
		);
		}
		if (!$advppp_settings_mv_columns or (in_array('mv_supplier', $advppp_settings_mv_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_supplier'),
			'value' => 'supplier',
		);
		}		
		if (in_array('mv_attribute', $advppp_settings_mv_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_attribute'),
			'value' => 'attribute',
		);
		}
		if (!$advppp_settings_mv_columns or (in_array('mv_status', $advppp_settings_mv_columns))) {			
		$data['sort'][] = array(
			'text'  => $this->language->get('column_status'),
			'value' => 'status',
		);
		}
		if (in_array('mv_location', $advppp_settings_mv_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_location'),
			'value' => 'location',
		);
		}
		if (in_array('mv_tax_class', $advppp_settings_mv_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_tax_class'),
			'value' => 'tax_class',
		);
		}
		if (in_array('mv_price', $advppp_settings_mv_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_price'),
			'value' => 'price',
		);
		}
		if (in_array('mv_cost', $advppp_settings_mv_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_cost'),
			'value' => 'cost',
		);
		}
		if (in_array('mv_profit', $advppp_settings_mv_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_profit'),
			'value' => 'profit',
		);
		}
		if (in_array('mv_profit_margin', $advppp_settings_mv_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_profit_margin'),
			'value' => 'profit_margin',
		);
		}
		if (in_array('mv_profit_markup', $advppp_settings_mv_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_profit_markup'),
			'value' => 'profit_markup',
		);
		}		
		if (in_array('mv_viewed', $advppp_settings_mv_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_viewed'),
			'value' => 'viewed',
		);
		}		
		if (!$advppp_settings_mv_columns or (in_array('mv_stock_quantity', $advppp_settings_mv_columns))) {			
		$data['sort'][] = array(
			'text'  => $this->language->get('column_stock_quantity'),
			'value' => 'stock_quantity',
		);
		}
		if (!$advppp_settings_mv_columns or (in_array('mv_sold_quantity', $advppp_settings_mv_columns))) {			
		$data['sort'][] = array(
			'text'  => $this->language->get('column_sold_quantity'),
			'value' => 'sold_quantity',
		);
		}
		if (!$advppp_settings_mv_columns or (in_array('mv_total_excl_vat', $advppp_settings_mv_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_prod_total_excl_vat'),
			'value' => 'total_excl_vat',
		);
		}
		if (!$advppp_settings_mv_columns or (in_array('mv_total_tax', $advppp_settings_mv_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_total_tax'),
			'value' => 'total_tax',
		);
		}
		if (!$advppp_settings_mv_columns or (in_array('mv_total_incl_vat', $advppp_settings_mv_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_prod_total_incl_vat'),
			'value' => 'total_incl_vat',
		);
		}
		if (!$advppp_settings_mv_columns or (in_array('mv_app', $advppp_settings_mv_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_app'),
			'value' => 'app',
		);
		}
		if (!$advppp_settings_mv_columns or (in_array('mv_discount', $advppp_settings_mv_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_product_discount'),
			'value' => 'discount',
		);
		}
		if (!$advppp_settings_mv_columns or (in_array('mv_refunds', $advppp_settings_mv_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_product_refunds'),
			'value' => 'refunds',
		);
		}
		if (in_array('mv_reward_points', $advppp_settings_mv_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_product_reward_points'),
			'value' => 'reward_points',
		);
		}
		if (!$advppp_settings_mv_columns or (in_array('mv_total_sales', $advppp_settings_mv_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_total_sales'),
			'value' => 'total_sales',
		);
		}		
		if (!$advppp_settings_mv_columns or (in_array('mv_total_costs', $advppp_settings_mv_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_total_costs'),
			'value' => 'total_costs',
		);
		}
		if (!$advppp_settings_mv_columns or (in_array('mv_total_profit', $advppp_settings_mv_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_total_profit'),
			'value' => 'total_profit',
		);
		}		
		if (!$advppp_settings_mv_columns or (in_array('mv_total_profit_margin', $advppp_settings_mv_columns))) {	
		$data['sort'][] = array(
			'text'  => $this->language->get('column_total_profit_margin'),
			'value' => 'total_profit_margin',
		);
		}	
		if (!$advppp_settings_mv_columns or (in_array('mv_total_profit_markup', $advppp_settings_mv_columns))) {	
		$data['sort'][] = array(
			'text'  => $this->language->get('column_total_profit_markup'),
			'value' => 'total_profit_markup',
		);
		}	
		}
		
		if (isset($this->request->get['filter_sort'])) {
			$this->session->data['filter_sort'] = $filter_sort = $this->request->get['filter_sort'];
		} else {
			if ($filter_report == 'products_without_orders') {
				$this->session->data['filter_sort'] = $filter_sort = 'id';
			} elseif ($filter_report == 'products_shopping_carts') {
				$this->session->data['filter_sort'] = $filter_sort = 'cart_quantity';		
			} elseif ($filter_report == 'products_wishlists') {
				$this->session->data['filter_sort'] = $filter_sort = 'wishlist_quantity';					
			} else {
				$this->session->data['filter_sort'] = $filter_sort = 'sold_quantity';
			}
		}

		$data['order'] = array();

		$data['order'][] = array(
			'text'  => $this->language->get('text_asc'),
			'value' => 'asc',
		);
		$data['order'][] = array(
			'text'  => $this->language->get('text_desc'),
			'value' => 'desc',
		);
		
		if (isset($this->request->get['filter_order'])) {
			$this->session->data['filter_order'] = $filter_order = $this->request->get['filter_order'];
		} else {
			if ($filter_report == 'products_without_orders') {
				$this->session->data['filter_order'] = $filter_order = 'asc';				
			} else {
				$this->session->data['filter_order'] = $filter_order = 'desc';
			}
		}
		
		$data['limit'] = array();

		$data['limit'][] = array(
			'text'  	=> '10',
			'value' 	=> '10',
			'subtext'	=> '',
		);
		$data['limit'][] = array(
			'text'  	=> '25',
			'value' 	=> '25',
			'subtext'	=> '',
		);
		$data['limit'][] = array(
			'text'  	=> '50',
			'value' 	=> '50',
			'subtext'	=> '',
		);
		$data['limit'][] = array(
			'text'  	=> '100',
			'value' 	=> '100',
			'subtext'	=> '',
		);
		$data['limit'][] = array(
			'text'  	=> '500',
			'value' 	=> '500',
			'subtext'	=> '',
		);
		$data['limit'][] = array(
			'text'  	=> '1000',
			'value' 	=> '1000',
			'subtext'	=> '',
		);			
		$data['limit'][] = array(
			'text'  	=> $this->language->get('text_all'),
			'value' 	=> '99999',
			'subtext'	=> '',
		);
		$data['limit'][] = array(
			'text'  	=> $this->language->get('text_all'),
			'value' 	=> '999999',
			'subtext'	=> $this->language->get('text_for_export'),
		);		
		
		if (isset($this->request->get['filter_limit'])) {
			$this->session->data['filter_limit'] = $filter_limit = $this->request->get['filter_limit'];
		} else {
			$this->session->data['filter_limit'] = $filter_limit = 25;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
 	
		if (isset($this->request->get['filter_status_date_start'])) {
			$this->session->data['filter_status_date_start'] = $filter_status_date_start = $this->request->get['filter_status_date_start'];
		} else {
			$this->session->data['filter_status_date_start'] = $filter_status_date_start = '';
		}

		if (isset($this->request->get['filter_status_date_end'])) {
			$this->session->data['filter_status_date_end'] = $filter_status_date_end = $this->request->get['filter_status_date_end'];
		} else {
			$this->session->data['filter_status_date_end'] = $filter_status_date_end = '';
		}

		$data['order_statuses'] = $this->model_report_adv_products_profit->getOrderStatuses(); 	
		if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = explode(',', $this->request->get['filter_order_status_id']);
			$this->session->data['filter_order_status_id'] = $this->request->get['filter_order_status_id'];
		} else {
			$filter_order_status_id = array();
			$this->session->data['filter_order_status_id'] = '';
		}

		if (isset($this->request->get['filter_order_id_from'])) {
			if (is_numeric(trim($this->request->get['filter_order_id_from']))) {
				$this->session->data['filter_order_id_from'] = $filter_order_id_from = trim($this->request->get['filter_order_id_from']);
			} else {
				$this->session->data['filter_order_id_from'] = $filter_order_id_from = '';
			}
		} else {
			$this->session->data['filter_order_id_from'] = $filter_order_id_from = '';
		}
		
		if (isset($this->request->get['filter_order_id_to'])) {
			if (is_numeric(trim($this->request->get['filter_order_id_to']))) {
				$this->session->data['filter_order_id_to'] = $filter_order_id_to = trim($this->request->get['filter_order_id_to']);
			} else {
				$this->session->data['filter_order_id_to'] = $filter_order_id_to = '';
			}
		} else {
			$this->session->data['filter_order_id_to'] = $filter_order_id_to = '';
		}

		if (isset($this->request->get['filter_prod_price_min'])) {
			if (is_numeric(trim($this->request->get['filter_prod_price_min']))) {
				$this->session->data['filter_prod_price_min'] = $filter_prod_price_min = trim($this->request->get['filter_prod_price_min']);
			} else {
				$this->session->data['filter_prod_price_min'] = $filter_prod_price_min = '';
			}
		} else {
			$this->session->data['filter_prod_price_min'] = $filter_prod_price_min = '';
		}
		
		if (isset($this->request->get['filter_prod_price_max'])) {
			if (is_numeric(trim($this->request->get['filter_prod_price_max']))) {
				$this->session->data['filter_prod_price_max'] = $filter_prod_price_max = trim($this->request->get['filter_prod_price_max']);
			} else {
				$this->session->data['filter_prod_price_max'] = $filter_prod_price_max = '';
			}
		} else {
			$this->session->data['filter_prod_price_max'] = $filter_prod_price_max = '';
		}
		
		$data['stores'] = $this->model_report_adv_products_profit->getOrderStores();
		if (isset($this->request->get['filter_store_id'])) {
			$filter_store_id = explode(',', $this->request->get['filter_store_id']);
			$this->session->data['filter_store_id'] = $this->request->get['filter_store_id'];
		} else {
			$filter_store_id = array();
			$this->session->data['filter_store_id'] = '';
		}

		$data['currencies'] = $this->model_report_adv_products_profit->getOrderCurrencies();
		if (isset($this->request->get['filter_currency'])) {
			$filter_currency = explode(',', $this->request->get['filter_currency']);
			$this->session->data['filter_currency'] = $this->request->get['filter_currency'];
		} else {
			$filter_currency = array();
			$this->session->data['filter_currency'] = '';
		}

		$data['taxes'] = $this->model_report_adv_products_profit->getOrderTaxes();	
		if (isset($this->request->get['filter_taxes'])) {
			$filter_taxes = explode(',', $this->request->get['filter_taxes']);
			$this->session->data['filter_taxes'] = $this->request->get['filter_taxes'];
		} else {
			$filter_taxes = array();
			$this->session->data['filter_taxes'] = '';
		}

		$data['tax_classes'] = $this->model_report_adv_products_profit->getOrderTaxClasses();
		if (isset($this->request->get['filter_tax_classes'])) {
			$filter_tax_classes = explode(',', $this->request->get['filter_tax_classes']);
			$this->session->data['filter_tax_classes'] = $this->request->get['filter_tax_classes'];
		} else {
			$filter_tax_classes = array();
			$this->session->data['filter_tax_classes'] = '';
		}

		$data['geo_zones'] = $this->model_report_adv_products_profit->getOrderGeoZones();
		if (isset($this->request->get['filter_geo_zones'])) {
			$filter_geo_zones = explode(',', $this->request->get['filter_geo_zones']);
			$this->session->data['filter_geo_zones'] = $this->request->get['filter_geo_zones'];
		} else {
			$filter_geo_zones = array();
			$this->session->data['filter_geo_zones'] = '';
		}

		$data['customer_groups'] = $this->model_report_adv_products_profit->getOrderCustomerGroups();	
		if (isset($this->request->get['filter_customer_group_id'])) {
			$filter_customer_group_id = explode(',', $this->request->get['filter_customer_group_id']);
			$this->session->data['filter_customer_group_id'] = $this->request->get['filter_customer_group_id'];
		} else {
			$filter_customer_group_id = array();
			$this->session->data['filter_customer_group_id'] = '';
		}
		
		if (isset($this->request->get['filter_customer_name'])) {
			$this->session->data['filter_customer_name'] = $filter_customer_name = $this->request->get['filter_customer_name'];
		} else {
			$this->session->data['filter_customer_name'] = $filter_customer_name = '';
		}

		if (isset($this->request->get['filter_customer_email'])) {
			$this->session->data['filter_customer_email'] = $filter_customer_email = $this->request->get['filter_customer_email'];
		} else {
			$this->session->data['filter_customer_email'] = $filter_customer_email = '';
		}

		if (isset($this->request->get['filter_customer_telephone'])) {
			$this->session->data['filter_customer_telephone'] = $filter_customer_telephone = $this->request->get['filter_customer_telephone'];
		} else {
			$this->session->data['filter_customer_telephone'] = $filter_customer_telephone = '';
		}

		if (isset($this->request->get['filter_ip'])) {
			$this->session->data['filter_ip'] = $filter_ip = $this->request->get['filter_ip'];
		} else {
			$this->session->data['filter_ip'] = $filter_ip = '';
		}
		
		if (isset($this->request->get['filter_payment_company'])) {
			$this->session->data['filter_payment_company'] = $filter_payment_company = $this->request->get['filter_payment_company'];
		} else {
			$this->session->data['filter_payment_company'] = $filter_payment_company = '';
		}
		
		if (isset($this->request->get['filter_payment_address'])) {
			$this->session->data['filter_payment_address'] = $filter_payment_address = $this->request->get['filter_payment_address'];
		} else {
			$this->session->data['filter_payment_address'] = $filter_payment_address = '';
		}

		if (isset($this->request->get['filter_payment_city'])) {
			$this->session->data['filter_payment_city'] = $filter_payment_city = $this->request->get['filter_payment_city'];
		} else {
			$this->session->data['filter_payment_city'] = $filter_payment_city = '';
		}
		
		if (isset($this->request->get['filter_payment_zone'])) {
			$this->session->data['filter_payment_zone'] = $filter_payment_zone = $this->request->get['filter_payment_zone'];
		} else {
			$this->session->data['filter_payment_zone'] = $filter_payment_zone = '';
		}
		
		if (isset($this->request->get['filter_payment_postcode'])) {
			$this->session->data['filter_payment_postcode'] = $filter_payment_postcode = $this->request->get['filter_payment_postcode'];
		} else {
			$this->session->data['filter_payment_postcode'] = $filter_payment_postcode = '';
		}

		if (isset($this->request->get['filter_payment_country'])) {
			$this->session->data['filter_payment_country'] = $filter_payment_country = $this->request->get['filter_payment_country'];
		} else {
			$this->session->data['filter_payment_country'] = $filter_payment_country = '';
		}

		$data['payment_methods'] = $this->model_report_adv_products_profit->getOrderPaymentMethods();	
		if (isset($this->request->get['filter_payment_method'])) {
			$filter_payment_method = explode(',', $this->request->get['filter_payment_method']);
			$this->session->data['filter_payment_method'] = $this->request->get['filter_payment_method'];
		} else {
			$filter_payment_method = array();
			$this->session->data['filter_payment_method'] = '';
		}
		
		if (isset($this->request->get['filter_shipping_company'])) {
			$this->session->data['filter_shipping_company'] = $filter_shipping_company = $this->request->get['filter_shipping_company'];
		} else {
			$this->session->data['filter_shipping_company'] = $filter_shipping_company = '';
		}
		
		if (isset($this->request->get['filter_shipping_address'])) {
			$this->session->data['filter_shipping_address'] = $filter_shipping_address = $this->request->get['filter_shipping_address'];
		} else {
			$this->session->data['filter_shipping_address'] = $filter_shipping_address = '';
		}

		if (isset($this->request->get['filter_shipping_city'])) {
			$this->session->data['filter_shipping_city'] = $filter_shipping_city = $this->request->get['filter_shipping_city'];
		} else {
			$this->session->data['filter_shipping_city'] = $filter_shipping_city = '';
		}
		
		if (isset($this->request->get['filter_shipping_zone'])) {
			$this->session->data['filter_shipping_zone'] = $filter_shipping_zone = $this->request->get['filter_shipping_zone'];
		} else {
			$this->session->data['filter_shipping_zone'] = $filter_shipping_zone = '';
		}
		
		if (isset($this->request->get['filter_shipping_postcode'])) {
			$this->session->data['filter_shipping_postcode'] = $filter_shipping_postcode = $this->request->get['filter_shipping_postcode'];
		} else {
			$this->session->data['filter_shipping_postcode'] = $filter_shipping_postcode = '';
		}

		if (isset($this->request->get['filter_shipping_country'])) {
			$this->session->data['filter_shipping_country'] = $filter_shipping_country = $this->request->get['filter_shipping_country'];
		} else {
			$this->session->data['filter_shipping_country'] = $filter_shipping_country = '';
		}

		$data['shipping_methods'] = $this->model_report_adv_products_profit->getOrderShippingMethods();	
		if (isset($this->request->get['filter_shipping_method'])) {
			$filter_shipping_method = explode(',', $this->request->get['filter_shipping_method']);
			$this->session->data['filter_shipping_method'] = $this->request->get['filter_shipping_method'];
		} else {
			$filter_shipping_method = array();
			$this->session->data['filter_shipping_method'] = '';
		}

		$data['categories'] = $this->model_report_adv_products_profit->getProductsCategories(0);
		if (isset($this->request->get['filter_category'])) {
			$filter_category = explode(',', $this->request->get['filter_category']);
			$this->session->data['filter_category'] = $this->request->get['filter_category'];
		} else {
			$filter_category = array();
			$this->session->data['filter_category'] = '';
		}
		
		$data['manufacturers'] = $this->model_report_adv_products_profit->getProductsManufacturers(); 
		if (isset($this->request->get['filter_manufacturer'])) {
			$filter_manufacturer = explode(',', $this->request->get['filter_manufacturer']);
			$this->session->data['filter_manufacturer'] = $this->request->get['filter_manufacturer'];
		} else {
			$filter_manufacturer = array();
			$this->session->data['filter_manufacturer'] = '';
		}

		$data['suppliers'] = $this->model_report_adv_products_profit->getProductsSuppliers(); 
		if (isset($this->request->get['filter_supplier'])) {
			$filter_supplier = explode(',', $this->request->get['filter_supplier']);
			$this->session->data['filter_supplier'] = $this->request->get['filter_supplier'];
		} else {
			$filter_supplier = array();
			$this->session->data['filter_supplier'] = '';
		}
		
		if (isset($this->request->get['filter_sku'])) {
			$this->session->data['filter_sku'] = $filter_sku = $this->request->get['filter_sku'];
		} else {
			$this->session->data['filter_sku'] = $filter_sku = '';
		}

		if (isset($this->request->get['filter_product_name'])) {
			$this->session->data['filter_product_name'] = $filter_product_name = $this->request->get['filter_product_name'];
		} else {
			$this->session->data['filter_product_name'] = $filter_product_name = '';
		}
		
		if (isset($this->request->get['filter_model'])) {
			$this->session->data['filter_model'] = $filter_model = $this->request->get['filter_model'];
		} else {
			$this->session->data['filter_model'] = $filter_model = '';
		}

		$data['order_options'] = $this->model_report_adv_products_profit->getProductOptions();
		if (isset($this->request->get['filter_option'])) {
			$filter_option = explode(',', $this->request->get['filter_option']);
			$this->session->data['filter_option'] = $this->request->get['filter_option'];
		} else {
			$filter_option = array();
			$this->session->data['filter_option'] = '';
		}

		$data['attributes'] = $this->model_report_adv_products_profit->getProductAttributes();
		if (isset($this->request->get['filter_attribute'])) {
			$filter_attribute = explode(',', $this->request->get['filter_attribute']);
			$this->session->data['filter_attribute'] = $this->request->get['filter_attribute'];
		} else {
			$filter_attribute = array();
			$this->session->data['filter_attribute'] = '';
		}

		$data['product_statuses'] = $this->model_report_adv_products_profit->getProductStatuses();	
		if (isset($this->request->get['filter_product_status'])) {
			$filter_product_status = explode(',', $this->request->get['filter_product_status']);
			$this->session->data['filter_product_status'] = $this->request->get['filter_product_status'];
		} else {
			$filter_product_status = array();
			$this->session->data['filter_product_status'] = '';
		}
		
		$data['locations'] = $this->model_report_adv_products_profit->getProductLocations();
		if (isset($this->request->get['filter_location'])) {
			$filter_location = explode(',', $this->request->get['filter_location']);
			$this->session->data['filter_location'] = $this->request->get['filter_location'];
		} else {
			$filter_location = array();
			$this->session->data['filter_location'] = '';
		}

		$data['affiliate_names'] = $this->model_report_adv_products_profit->getOrderAffiliates();
		if (isset($this->request->get['filter_affiliate_name'])) {
			$filter_affiliate_name = explode(',', $this->request->get['filter_affiliate_name']);
			$this->session->data['filter_affiliate_name'] = $this->request->get['filter_affiliate_name'];
		} else {
			$filter_affiliate_name = array();
			$this->session->data['filter_affiliate_name'] = '';
		}

		$data['affiliate_emails'] = $this->model_report_adv_products_profit->getOrderAffiliates();
		if (isset($this->request->get['filter_affiliate_email'])) {
			$filter_affiliate_email = explode(',', $this->request->get['filter_affiliate_email']);
			$this->session->data['filter_affiliate_email'] = $this->request->get['filter_affiliate_email'];
		} else {
			$filter_affiliate_email = array();
			$this->session->data['filter_affiliate_email'] = '';
		}

		$data['coupon_names'] = $this->model_report_adv_products_profit->getOrderCouponNames();
		if (isset($this->request->get['filter_coupon_name'])) {
			$filter_coupon_name = explode(',', $this->request->get['filter_coupon_name']);
			$this->session->data['filter_coupon_name'] = $this->request->get['filter_coupon_name'];
		} else {
			$filter_coupon_name = array();
			$this->session->data['filter_coupon_name'] = '';
		}

		if (isset($this->request->get['filter_coupon_code'])) {
			$this->session->data['filter_coupon_code'] = $filter_coupon_code = $this->request->get['filter_coupon_code'];
		} else {
			$this->session->data['filter_coupon_code'] = $filter_coupon_code = '';
		}

		if (isset($this->request->get['filter_voucher_code'])) {
			$this->session->data['filter_voucher_code'] = $filter_voucher_code = $this->request->get['filter_voucher_code'];
		} else {
			$this->session->data['filter_voucher_code'] = $filter_voucher_code = '';
		}

		if (isset($_GET['cron'])) {
			$cron_settings = unserialize($this->config->get('advppp' . $this->user->getId() . 'cron_setting'));
			foreach ($cron_settings as $cron_setting) {
				if (isset($_GET['cron_id']) && ($_GET['cron_id'] == $cron_setting['cron_id'])) {
					$filter_report = $cron_setting['cron_filter_report'];
					$filter_details = $cron_setting['cron_filter_details'];
					$filter_group = $cron_setting['cron_filter_group'];
					$filter_sort = $cron_setting['cron_filter_sort'];
					$filter_order = $cron_setting['cron_filter_order'];
					$filter_limit = $cron_setting['cron_filter_limit'];
					
					$filter_range = $cron_setting['cron_filter_range'];
					$filter_date_start = $cron_setting['cron_date_start'];
					$filter_date_end = $cron_setting['cron_date_end'];
					$filter_status_date_start = $cron_setting['cron_status_date_start'];
					$filter_status_date_end = $cron_setting['cron_status_date_end'];
					$filter_order_status_id = $cron_setting['cron_filter_order_status_id'] != '' ? explode(',', $cron_setting['cron_filter_order_status_id']) : '';
					$filter_order_id_from = $cron_setting['cron_filter_order_id_from'];
					$filter_order_id_to = $cron_setting['cron_filter_order_id_to'];
					$filter_prod_price_min = $cron_setting['cron_filter_prod_price_min'];
					$filter_prod_price_max = $cron_setting['cron_filter_prod_price_max'];	
					$filter_store_id = $cron_setting['cron_filter_store_id'] != '' ? explode(',', $cron_setting['cron_filter_store_id']) : '';
					$filter_currency = $cron_setting['cron_filter_currency'] != '' ? explode(',', $cron_setting['cron_filter_currency']) : '';
					$filter_taxes = $cron_setting['cron_filter_taxes'] != '' ? explode(',', $cron_setting['cron_filter_taxes']) : '';	
					$filter_tax_classes = $cron_setting['cron_filter_tax_classes'] != '' ? explode(',', $cron_setting['cron_filter_tax_classes']) : '';	
					$filter_geo_zones = $cron_setting['cron_filter_geo_zones'] != '' ? explode(',', $cron_setting['cron_filter_geo_zones']) : '';	
					$filter_customer_group_id = $cron_setting['cron_filter_customer_group_id'] != '' ? explode(',', $cron_setting['cron_filter_customer_group_id']) : '';	
					$filter_customer_name = $cron_setting['cron_filter_customer_name'];	
					$filter_customer_email = $cron_setting['cron_filter_customer_email'];	
					$filter_customer_telephone = $cron_setting['cron_filter_customer_telephone'];	
					$filter_ip = $cron_setting['cron_filter_ip'];	
					$filter_payment_company = $cron_setting['cron_filter_payment_company'];
					$filter_payment_address = $cron_setting['cron_filter_payment_address'];
					$filter_payment_city = $cron_setting['cron_filter_payment_city'];
					$filter_payment_zone = $cron_setting['cron_filter_payment_zone'];
					$filter_payment_postcode = $cron_setting['cron_filter_payment_postcode'];
					$filter_payment_country = $cron_setting['cron_filter_payment_country'];
					$filter_payment_method = $cron_setting['cron_filter_payment_method'] != '' ? explode(',', $cron_setting['cron_filter_payment_method']) : '';	
					$filter_shipping_company = $cron_setting['cron_filter_shipping_company'];
					$filter_shipping_address = $cron_setting['cron_filter_shipping_address'];
					$filter_shipping_city = $cron_setting['cron_filter_shipping_city'];	
					$filter_shipping_zone = $cron_setting['cron_filter_shipping_zone'];
					$filter_shipping_postcode = $cron_setting['cron_filter_shipping_postcode'];
					$filter_shipping_country = $cron_setting['cron_filter_shipping_country'];
					$filter_shipping_method = $cron_setting['cron_filter_shipping_method'] != '' ? explode(',', $cron_setting['cron_filter_shipping_method']) : '';	
					$filter_category = $cron_setting['cron_filter_category'] != '' ? explode(',', $cron_setting['cron_filter_category']) : '';	
					$filter_manufacturer = $cron_setting['cron_filter_manufacturer'] != '' ? explode(',', $cron_setting['cron_filter_manufacturer']) : '';	
					$filter_supplier = $cron_setting['cron_filter_supplier'] != '' ? explode(',', $cron_setting['cron_filter_supplier']) : '';	
					$filter_sku = $cron_setting['cron_filter_sku'];		
					$filter_product_name = $cron_setting['cron_filter_product_name'];
					$filter_model = $cron_setting['cron_filter_model'];
					$filter_option = $cron_setting['cron_filter_option'] != '' ? explode(',', $cron_setting['cron_filter_option']) : '';	
					$filter_attribute = $cron_setting['cron_filter_attribute'] != '' ? explode(',', $cron_setting['cron_filter_attribute']) : '';	
					$filter_product_status = $cron_setting['cron_filter_product_status'] != '' ? explode(',', $cron_setting['cron_filter_product_status']) : '';	
					$filter_location = $cron_setting['cron_filter_location'] != '' ? explode(',', $cron_setting['cron_filter_location']) : '';	
					$filter_affiliate_name = $cron_setting['cron_filter_affiliate_name'] != '' ? explode(',', $cron_setting['cron_filter_affiliate_name']) : '';	
					$filter_affiliate_email = $cron_setting['cron_filter_affiliate_email'] != '' ? explode(',', $cron_setting['cron_filter_affiliate_email']) : '';	
					$filter_coupon_name = $cron_setting['cron_filter_coupon_name'] != '' ? explode(',', $cron_setting['cron_filter_coupon_name']) : '';
					$filter_coupon_code = $cron_setting['cron_filter_coupon_code'];
					$filter_voucher_code = $cron_setting['cron_filter_voucher_code'];			
				}
			}
		}
		
		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_range'])) {
			$url .= '&filter_range=' . $this->request->get['filter_range'];
		}

		if (isset($this->request->get['filter_report'])) {
			$url .= '&filter_report=' . $this->request->get['filter_report'];
		}

		if (isset($this->request->get['filter_group'])) {
			$url .= '&filter_group=' . $this->request->get['filter_group'];
		}
		
		if (isset($this->request->get['filter_sort'])) {
			$url .= '&filter_sort=' . $this->request->get['filter_sort'];
		}

		if (isset($this->request->get['filter_order'])) {
			$url .= '&filter_order=' . $this->request->get['filter_order'];
		}
		
		if (isset($this->request->get['filter_details'])) {
			$url .= '&filter_details=' . $this->request->get['filter_details'];
		}
		
		if (isset($this->request->get['filter_limit'])) {
			$url .= '&filter_limit=' . $this->request->get['filter_limit'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['filter_status_date_start'])) {
			$url .= '&filter_status_date_start=' . $this->request->get['filter_status_date_start'];
		}
		
		if (isset($this->request->get['filter_status_date_end'])) {
			$url .= '&filter_status_date_end=' . $this->request->get['filter_status_date_end'];
		}
		
		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
		
		if (isset($this->request->get['filter_order_id_from'])) {
			$url .= '&filter_order_id_from=' . $this->request->get['filter_order_id_from'];
		}
		
		if (isset($this->request->get['filter_order_id_to'])) {
			$url .= '&filter_order_id_to=' . $this->request->get['filter_order_id_to'];
		}

		if (isset($this->request->get['filter_prod_price_min'])) {
			$url .= '&filter_prod_price_min=' . $this->request->get['filter_prod_price_min'];
		}
		
		if (isset($this->request->get['filter_prod_price_max'])) {
			$url .= '&filter_prod_price_max=' . $this->request->get['filter_prod_price_max'];
		}
		
		if (isset($this->request->get['filter_store_id'])) {
			$url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
		}
		
		if (isset($this->request->get['filter_currency'])) {
			$url .= '&filter_currency=' . $this->request->get['filter_currency'];
		}
		
		if (isset($this->request->get['filter_taxes'])) {
			$url .= '&filter_taxes=' . $this->request->get['filter_taxes'];
		}
		
		if (isset($this->request->get['filter_tax_classes'])) {
			$url .= '&filter_tax_classes=' . $this->request->get['filter_tax_classes'];
		}
		
		if (isset($this->request->get['filter_geo_zones'])) {
			$url .= '&filter_geo_zones=' . $this->request->get['filter_geo_zones'];
		}
		
		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}
		
		if (isset($this->request->get['filter_customer_name'])) {
			$url .= '&filter_customer_name=' . urlencode(html_entity_decode($this->request->get['filter_customer_name'], ENT_QUOTES, 'UTF-8'));
		}		

		if (isset($this->request->get['filter_customer_email'])) {
			$url .= '&filter_customer_email=' . urlencode(html_entity_decode($this->request->get['filter_customer_email'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_customer_telephone'])) {
			$url .= '&filter_customer_telephone=' . urlencode(html_entity_decode($this->request->get['filter_customer_telephone'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . urlencode(html_entity_decode($this->request->get['filter_ip'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_payment_company'])) {
			$url .= '&filter_payment_company=' . urlencode(html_entity_decode($this->request->get['filter_payment_company'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_payment_address'])) {
			$url .= '&filter_payment_address=' . urlencode(html_entity_decode($this->request->get['filter_payment_address'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_payment_city'])) {
			$url .= '&filter_payment_city=' . urlencode(html_entity_decode($this->request->get['filter_payment_city'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_payment_zone'])) {
			$url .= '&filter_payment_zone=' . urlencode(html_entity_decode($this->request->get['filter_payment_zone'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_payment_postcode'])) {
			$url .= '&filter_payment_postcode=' . urlencode(html_entity_decode($this->request->get['filter_payment_postcode'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_payment_country'])) {
			$url .= '&filter_payment_country=' . urlencode(html_entity_decode($this->request->get['filter_payment_country'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_payment_method'])) {
			$url .= '&filter_payment_method=' . $this->request->get['filter_payment_method'];
		}	
		
		if (isset($this->request->get['filter_shipping_company'])) {
			$url .= '&filter_shipping_company=' . urlencode(html_entity_decode($this->request->get['filter_shipping_company'], ENT_QUOTES, 'UTF-8'));
		}	

		if (isset($this->request->get['filter_shipping_address'])) {
			$url .= '&filter_shipping_address=' . urlencode(html_entity_decode($this->request->get['filter_shipping_address'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_shipping_city'])) {
			$url .= '&filter_shipping_city=' . urlencode(html_entity_decode($this->request->get['filter_shipping_city'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_shipping_zone'])) {
			$url .= '&filter_shipping_zone=' . urlencode(html_entity_decode($this->request->get['filter_shipping_zone'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_shipping_postcode'])) {
			$url .= '&filter_shipping_postcode=' . urlencode(html_entity_decode($this->request->get['filter_shipping_postcode'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_shipping_country'])) {
			$url .= '&filter_shipping_country=' . urlencode(html_entity_decode($this->request->get['filter_shipping_country'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
		}	
		
		if (isset($this->request->get['filter_category'])) {
			$url .= '&filter_category=' . $this->request->get['filter_category'];
		}	
		
		if (isset($this->request->get['filter_manufacturer'])) {
			$url .= '&filter_manufacturer=' . $this->request->get['filter_manufacturer'];
		}	

		if (isset($this->request->get['filter_supplier'])) {
			$url .= '&filter_supplier=' . $this->request->get['filter_supplier'];
		}
		
		if (isset($this->request->get['filter_sku'])) {
			$url .= '&filter_sku=' . urlencode(html_entity_decode($this->request->get['filter_sku'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_product_name'])) {
			$url .= '&filter_product_name=' . urlencode(html_entity_decode($this->request->get['filter_product_name'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_option'])) {
			$url .= '&filter_option=' . $this->request->get['filter_option'];
		}	
		
		if (isset($this->request->get['filter_attribute'])) {
			$url .= '&filter_attribute=' . $this->request->get['filter_attribute'];
		}			

		if (isset($this->request->get['filter_product_status'])) {
			$url .= '&filter_product_status=' . $this->request->get['filter_product_status'];
		}
		
		if (isset($this->request->get['filter_location'])) {
			$url .= '&filter_location=' . $this->request->get['filter_location'];
		}	
		
		if (isset($this->request->get['filter_affiliate_name'])) {
			$url .= '&filter_affiliate_name=' . $this->request->get['filter_affiliate_name'];
		}	
		
		if (isset($this->request->get['filter_affiliate_email'])) {
			$url .= '&filter_affiliate_email=' . $this->request->get['filter_affiliate_email'];
		}	
		
		if (isset($this->request->get['filter_coupon_name'])) {
			$url .= '&filter_coupon_name=' . $this->request->get['filter_coupon_name'];
		}	
		
		if (isset($this->request->get['filter_coupon_code'])) {
			$url .= '&filter_coupon_code=' . urlencode(html_entity_decode($this->request->get['filter_coupon_code'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_voucher_code'])) {
			$url .= '&filter_voucher_code=' . urlencode(html_entity_decode($this->request->get['filter_voucher_code'], ENT_QUOTES, 'UTF-8'));
		}	
		
   		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('report/adv_products_profit', 'token=' . $this->session->data['token'], 'SSL')
   		);

		if (!file_exists(DIR_APPLICATION . 'model/module/adv_settings.php')) {
			$data['module_page'] = $this->response->redirect($this->url->link('module/adv_reports_products_profit', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = 'module' AND `code` = 'adv_profit_module'");
		if (!$query->rows) {
			$data['settings'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		} else {	
			$data['settings'] = $this->url->link('module/adv_profit_module', 'token=' . $this->session->data['token'], 'SSL');
		}	

		if ($this->config->get('advppp' . $this->user->getId() . '_formula_1')) {
			$data['advppp_formula_1'] = $this->config->get('advppp' . $this->user->getId() . '_formula_1');
		} else {
			$data['advppp_formula_1'] = 0;
		}
		
		if ($this->config->get('advppp' . $this->user->getId() . '_formula_2')) {
			$data['advppp_formula_2'] = $this->config->get('advppp' . $this->user->getId() . '_formula_2');
		} else {
			$data['advppp_formula_2'] = 0;
		}
		
		if ($this->config->get('advppp' . $this->user->getId() . '_formula_3')) {
			$data['advppp_formula_3'] = $this->config->get('advppp' . $this->user->getId() . '_formula_3');
		} else {
			$data['advppp_formula_3'] = 0;
		}

		if ($this->config->get('advppp' . $this->user->getId() . '_formula_4')) {
			$data['advppp_formula_4'] = $this->config->get('advppp' . $this->user->getId() . '_formula_4');
		} else {
			$data['advppp_formula_4'] = 0;
		}
		
		if ($this->config->get('advppp' . $this->user->getId() . '_formula_5')) {
			$data['advppp_formula_5'] = $this->config->get('advppp' . $this->user->getId() . '_formula_5');
		} else {
			$data['advppp_formula_5'] = 0;
		}

		if ($this->config->get('advppp' . $this->user->getId() . '_formula_6')) {
			$data['advppp_formula_6'] = $this->config->get('advppp' . $this->user->getId() . '_formula_6');
		} else {
			$data['advppp_formula_6'] = 0;
		}

		if ($this->config->get('advppp' . $this->user->getId() . '_formula_7')) {
			$data['advppp_formula_7'] = $this->config->get('advppp' . $this->user->getId() . '_formula_7');
		} else {
			$data['advppp_formula_7'] = 0;
		}
		
		if ($this->config->get('advppp' . $this->user->getId() . '_return_action_refund')) {
			$data['advppp_return_action_refund'] = $this->config->get('advppp' . $this->user->getId() . '_return_action_refund');
		} else {
			$data['advppp_return_action_refund'] = '';
		}

		if ($this->config->get('advppp' . $this->user->getId() . '_date_format')) {
			$data['advppp_date_format'] = $this->config->get('advppp' . $this->user->getId() . '_date_format');
		} else {
			$data['advppp_date_format'] = 'DDMMYYYY';
		}

		if ($this->config->get('advppp' . $this->user->getId() . '_hour_format')) {
			$data['advppp_hour_format'] = $this->config->get('advppp' . $this->user->getId() . '_hour_format');
		} else {
			$data['advppp_hour_format'] = '24';
		}
		
		if ($this->config->get('advppp' . $this->user->getId() . '_week_days')) {
			$data['advppp_week_days'] = $this->config->get('advppp' . $this->user->getId() . '_week_days');
		} else {
			$data['advppp_week_days'] = 'mon_sun';
		}

		$selected_load_save_reports = unserialize($this->config->get('advppp' . $this->user->getId() . 'sr_load_save_report'));
		
		if (isset($this->request->post['advppp' . $this->user->getId() . 'sr_load_save_report'])) {
			$data['advppp_load_save_reports'] = $this->request->post['advppp_load_save_report'];
		} elseif (isset($selected_load_save_reports)) {
			$data['advppp_load_save_reports'] = $selected_load_save_reports;
		} else { 	
			$data['advppp_load_save_reports'] = array();
		}

		$selected_cron_settings = unserialize($this->config->get('advppp' . $this->user->getId() . 'cron_setting'));
		
		if (isset($this->request->post['advppp' . $this->user->getId() . 'cron_setting'])) {
			$data['advppp_cron_settings'] = $this->request->post['advppp_cron_setting'];
		} elseif (isset($selected_cron_settings)) {
			$data['advppp_cron_settings'] = $selected_cron_settings;
		} else { 	
			$data['advppp_cron_settings'] = array();
		}
		
		$this->load->model('localisation/return_action');
		$data['return_actions'] = $this->model_localisation_return_action->getReturnActions();
			
		$data['auth'] = FALSE;
		$data['ldata'] = FALSE;
		$data['products'] = array();
		
		$filter_data = array(
			'filter_date_start'	     		=> $filter_date_start, 
			'filter_date_end'	     		=> $filter_date_end,
			'filter_range'           		=> $filter_range,
			'filter_report'           		=> $filter_report,
			'filter_group'           		=> $filter_group,
			'filter_status_date_start'		=> $filter_status_date_start, 
			'filter_status_date_end'		=> $filter_status_date_end, 			
			'filter_order_status_id'		=> $filter_order_status_id,
			'filter_order_id_from'			=> $filter_order_id_from,
			'filter_order_id_to'			=> $filter_order_id_to,	
			'filter_prod_price_min'			=> $filter_prod_price_min,
			'filter_prod_price_max'			=> $filter_prod_price_max,				
			'filter_store_id'				=> $filter_store_id,
			'filter_currency'				=> $filter_currency,
			'filter_taxes'					=> $filter_taxes,
			'filter_tax_classes'			=> $filter_tax_classes,
			'filter_geo_zones'				=> $filter_geo_zones,			
			'filter_customer_group_id'		=> $filter_customer_group_id,
			'filter_customer_name'	 	 	=> $filter_customer_name,			
			'filter_customer_email'			=> $filter_customer_email,
			'filter_customer_telephone'		=> $filter_customer_telephone,
			'filter_ip' 	 				=> $filter_ip,			
			'filter_payment_company'		=> $filter_payment_company,
			'filter_payment_address'		=> $filter_payment_address,
			'filter_payment_city'			=> $filter_payment_city,
			'filter_payment_zone'			=> $filter_payment_zone,			
			'filter_payment_postcode'		=> $filter_payment_postcode,
			'filter_payment_country'		=> $filter_payment_country,
			'filter_payment_method'  		=> $filter_payment_method,
			'filter_shipping_company'		=> $filter_shipping_company,
			'filter_shipping_address'		=> $filter_shipping_address,
			'filter_shipping_city'			=> $filter_shipping_city,
			'filter_shipping_zone'			=> $filter_shipping_zone,			
			'filter_shipping_postcode'		=> $filter_shipping_postcode,
			'filter_shipping_country'		=> $filter_shipping_country,
			'filter_shipping_method'  		=> $filter_shipping_method,
			'filter_category'				=> $filter_category,
			'filter_manufacturer'			=> $filter_manufacturer,
			'filter_supplier'				=> $filter_supplier,
			'filter_sku' 	 				=> $filter_sku,
			'filter_product_name'			=> $filter_product_name,
			'filter_model' 	 				=> $filter_model,
			'filter_option'  				=> $filter_option,
			'filter_attribute' 	 		 	=> $filter_attribute,
			'filter_product_status'   		=> $filter_product_status,			
			'filter_location'  				=> $filter_location,
			'filter_affiliate_name'			=> $filter_affiliate_name,
			'filter_affiliate_email'		=> $filter_affiliate_email,
			'filter_coupon_name'			=> $filter_coupon_name,
			'filter_coupon_code'			=> $filter_coupon_code,
			'filter_voucher_code'			=> $filter_voucher_code,			
			'filter_sort'  					=> $filter_sort,
			'filter_order'  				=> $filter_order,
			'filter_details'  				=> $filter_details,
			'filter_limit'  				=> $filter_limit,			
			'start'                  		=> ($page - 1) * $filter_limit
		);
				
		$total = 0; //for setting report default to load none.
		if ($filter_range != "none"){
		$results = $this->model_report_adv_products_profit->getProductsProfit($filter_data);
		$totals = $filter_limit != '999999' ? $this->model_report_adv_products_profit->getProductsProfitTotal($filter_data) : FALSE;

		if ($filter_limit != '999999') {
		$counter = 0;
		foreach ($totals as $total) {
			$counter += count($total['counter']);
		}
		$total = $counter;
		} else {
		$total = '999999';	
		}		
		
		$this->load->model('tool/image');
			
		foreach ($results as $result) {
			
		if ($filter_details != 'all_details_products' && $filter_details != 'all_details_orders') {

		  if ($filter_details == 'basic_details' && ($filter_report == 'manufacturers' or $filter_report == 'categories' or $filter_report == 'suppliers' or $filter_report == 'products_options')) {
			$details = array(
				'product_ord_id'  					=> $result['product_ord_id'],
				'product_ord_id_link'  				=> $result['product_ord_id_link'],
				'product_ord_date'    				=> $result['product_ord_date'],
				'product_inv_no'     				=> $result['product_inv_no'],
				'product_prod_id'  					=> $result['product_prod_id'],
				'product_prod_id_link'  			=> $result['product_prod_id_link'],
				'product_sku'  						=> $result['product_sku'],
				'product_model'  					=> $result['product_model'],				
				'product_name'  					=> $result['product_name'],
				'product_option'  					=> $result['product_option'],
				'product_attributes'  				=> $result['product_attributes'],				
				'product_manu'  					=> ($filter_report == 'categories' || $filter_report == 'suppliers') ? $result['product_manu'] : '',
				'product_category'  				=> ($filter_report == 'manufacturers' || $filter_report == 'suppliers') ? $result['product_category'] : '',
				'product_supplier'  				=> ($filter_report == 'manufacturers' || $filter_report == 'categories') ? $result['product_supplier'] : '',
				'product_currency'  				=> $result['product_currency'],
				'product_price'  					=> $result['product_price'],
				'product_quantity'  				=> $result['product_quantity'],
				'product_total_excl_vat'  			=> $result['product_total_excl_vat'],				
				'product_tax'  						=> $result['product_tax'],				
				'product_total_incl_vat'  			=> $result['product_total_incl_vat'],
				'product_sales'  					=> $result['product_sales'],				
				'product_cost'   					=> $result['product_cost'],
				'product_profit'   					=> $result['product_profit'],
				'product_profit_margin' 			=> $result['product_profit_margin'],
				'product_profit_markup' 			=> $result['product_profit_markup'],
				'customer_ord_id' 					=> $result['customer_ord_id'],
				'customer_ord_id_link' 				=> $result['customer_ord_id_link'],
				'customer_ord_date' 				=> $result['customer_ord_date'],
				'customer_cust_id' 					=> $result['customer_cust_id'],
				'customer_cust_id_link' 			=> $result['customer_cust_id_link'],
				'billing_name' 						=> $result['billing_name'],
				'billing_company' 					=> $result['billing_company'],
				'billing_address_1' 				=> $result['billing_address_1'],
				'billing_address_2' 				=> $result['billing_address_2'],
				'billing_city' 						=> $result['billing_city'],
				'billing_zone' 						=> $result['billing_zone'],
				'billing_postcode' 					=> $result['billing_postcode'],
				'billing_country' 					=> $result['billing_country'],
				'customer_telephone' 				=> $result['customer_telephone'],
				'shipping_name' 					=> $result['shipping_name'],
				'shipping_company' 					=> $result['shipping_company'],
				'shipping_address_1' 				=> $result['shipping_address_1'],
				'shipping_address_2' 				=> $result['shipping_address_2'],
				'shipping_city' 					=> $result['shipping_city'],
				'shipping_zone' 					=> $result['shipping_zone'],
				'shipping_postcode' 				=> $result['shipping_postcode'],
				'shipping_country' 					=> $result['shipping_country']	
			);
		  } elseif ($filter_details == 'basic_details' && ($filter_report == 'products_purchased_without_options' or $filter_report == 'products_purchased_with_options' or $filter_report == 'new_products_purchased' or $filter_report == 'old_products_purchased' or $filter_report == 'products_abandoned_orders')) {
			$details = array(
				'order_prod_ord_id'     			=> $result['order_prod_ord_id'],
				'order_prod_ord_id_link'     		=> $result['order_prod_ord_id_link'],
				'order_prod_ord_date'    			=> $result['order_prod_ord_date'],
				'order_prod_inv_no'     			=> $result['order_prod_inv_no'],
				'order_prod_name'   				=> $result['order_prod_name'],
				'order_prod_email'   				=> $result['order_prod_email'],
				'order_prod_group'   				=> $result['order_prod_group'],
				'order_prod_shipping_method' 		=> strip_tags($result['order_prod_shipping_method'], '<br>'),
				'order_prod_payment_method'  		=> strip_tags($result['order_prod_payment_method'], '<br>'),
				'order_prod_status'  				=> $result['order_prod_status'],
				'order_prod_store'      			=> $result['order_prod_store'],	
				'order_prod_currency' 				=> $result['order_prod_currency'],
				'order_prod_price' 					=> $result['order_prod_price'],
				'order_prod_quantity' 				=> $result['order_prod_quantity'],
				'order_prod_total_excl_vat'  		=> $result['order_prod_total_excl_vat'],				
				'order_prod_tax'  					=> $result['order_prod_tax'],				
				'order_prod_total_incl_vat'  		=> $result['order_prod_total_incl_vat'],
				'order_prod_sales'  				=> $result['order_prod_sales'],
				'order_prod_cost'  					=> $result['order_prod_cost'],				
				'order_prod_profit'   				=> $result['order_prod_profit'],
				'order_prod_profit_margin' 			=> $result['order_prod_profit_margin'],
				'order_prod_profit_markup' 			=> $result['order_prod_profit_markup'],
				'customer_ord_id' 					=> $result['customer_ord_id'],
				'customer_ord_id_link' 				=> $result['customer_ord_id_link'],
				'customer_ord_date' 				=> $result['customer_ord_date'],
				'customer_cust_id' 					=> $result['customer_cust_id'],
				'customer_cust_id_link' 			=> $result['customer_cust_id_link'],
				'billing_name' 						=> $result['billing_name'],
				'billing_company' 					=> $result['billing_company'],
				'billing_address_1' 				=> $result['billing_address_1'],
				'billing_address_2' 				=> $result['billing_address_2'],
				'billing_city' 						=> $result['billing_city'],
				'billing_zone' 						=> $result['billing_zone'],
				'billing_postcode' 					=> $result['billing_postcode'],
				'billing_country' 					=> $result['billing_country'],
				'customer_telephone' 				=> $result['customer_telephone'],
				'shipping_name' 					=> $result['shipping_name'],
				'shipping_company' 					=> $result['shipping_company'],
				'shipping_address_1' 				=> $result['shipping_address_1'],
				'shipping_address_2' 				=> $result['shipping_address_2'],
				'shipping_city' 					=> $result['shipping_city'],
				'shipping_zone' 					=> $result['shipping_zone'],
				'shipping_postcode' 				=> $result['shipping_postcode'],
				'shipping_country' 					=> $result['shipping_country']	
			);
		  } else {
			$details = array(		
			);			  
		  }
				
			if ($filter_report != 'products_options') {
				
			if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.jpg', 40, 40);
			}
			
			$this->load->model('catalog/product');
			$category = $this->model_catalog_product->getProductCategories($result['product_id']);
			$manufacturer = $this->model_report_adv_products_profit->getProductManufacturers($result['manufacturer_id']);
			$supplier = $this->model_report_adv_products_profit->getProductSuppliers($result['supplier_id']);

			$price = $result['prod_price'];	
			$cost = $result['prod_cost'];	
			$profit_margin = $result['prod_price'] > 0 ? round(100 * (($result['prod_price']-$result['prod_cost'])/$result['prod_price']), 2) . '%' : '0%';
			$profit_markup = $result['prod_cost'] > 0 ? round(100 * (($result['prod_price']-$result['prod_cost'])/$result['prod_cost']), 2) . '%' : '0%';
			
			$product_specials = $this->model_catalog_product->getProductSpecials($result['product_id']);
			
			foreach ($product_specials  as $product_special) {
				if (($product_special['date_start'] == '0000-00-00' || strtotime($product_special['date_start']) < time()) && ($product_special['date_end'] == '0000-00-00' || strtotime($product_special['date_end']) > time())) {
					$price = $product_special['price'];
					$profit_margin = $product_special['price'] > 0 ? round(100 * (($product_special['price']-$result['prod_cost'])/$product_special['price']), 2) . '%' : '0%';
					$profit_markup = $result['prod_cost'] > 0 ? round(100 * (($product_special['price']-$result['prod_cost'])/$result['prod_cost']), 2) . '%' : '0%';					
					break;
				}
			}
			
			if ($filter_report != 'products_without_orders' && $filter_report != 'products_shopping_carts' && $filter_report != 'products_wishlists') {
			
			$total_sales = $result['total_sales'];
			$total_costs = $result['total_costs'];
			$total_profit_margin = ($result['total_sales']) > 0 ? round(100 * (($total_sales-$total_costs) / $total_sales), 2) . '%' : '0%';
			$total_profit_markup = ($result['total_costs']) > 0 ? round(100 * (($total_sales-$total_costs) / $total_costs), 2) . '%' : '0%';

			$sold_quantity_total = 0;
			$total_excl_vat_total = 0;
			$total_tax_total = 0;
			$total_incl_vat_total = 0;
			$discount_total = 0;
			$refunds_total = 0;
			$reward_points_total = 0;
			$total_sales_total = 0;
    		$total_costs_total = 0;
			$total_profit_margin_total = 0;
			$total_profit_markup_total = 0;
	
			foreach ($results as $totals) {				
    			$sold_quantity_total += $totals['sold_quantity'];
				$total_excl_vat_total += $totals['total_excl_vat'];
				$total_tax_total += $totals['total_tax'];
				$total_incl_vat_total += $totals['total_incl_vat'];
				$discount_total += $totals['discount'];
				$refunds_total += $totals['refunds'];
				$reward_points_total += $totals['reward_points'];
				$total_sales_total += $totals['total_sales'];
				$total_costs_total += $totals['total_costs'];
				$total_profit_margin_total = ($total_sales_total) > 0 ? round(100 * (($total_sales_total-$total_costs_total) / $total_sales_total), 2) . '%' : '0%';	
				$total_profit_markup_total = ($total_costs_total) > 0 ? round(100 * (($total_sales_total-$total_costs_total) / $total_costs_total), 2) . '%' : '0%';	
			}

			if ($sold_quantity_total != 0) {
				$sold_percent = round(100 * ($result['sold_quantity'] / $sold_quantity_total), 2) . '%';
				$sold_percent_total = '100%';		
			} else {
				$sold_percent = '0%';
				$sold_percent_total = '0%';
			}			
			}

			} else {
			
			$sold_quantity_total = 0;
			
			foreach ($results as $totals) {				
    			$sold_quantity_total += $totals['sold_quantity'];	
			}

			if ($sold_quantity_total != 0) {
				$sold_percent = round(100 * ($result['sold_quantity'] / $sold_quantity_total), 2) . '%';
				$sold_percent_total = '100%';		
			} else {
				$sold_percent = '0%';
				$sold_percent_total = '0%';
			}	
			
			}
			
		  	if ($filter_report == 'all_products_with_without_orders') {

			$products[] = array(
				'date_added'   						=> date($data['advppp_date_format'] == 'DDMMYYYY' ? 'd/m/Y' : 'm/d/Y', strtotime($result['date_added'])),				
				'product_id' 						=> $result['id'],
				'image'      						=> $image,
				'sku'    							=> $result['sku'],
				'name'     							=> $result['name'],				
				'model'    							=> $result['model'],
				'category'  						=> $category,
				'categories'  						=> $result['categories'],
				'manufacturer'  					=> $manufacturer,
				'manufacturers'  					=> $result['manufacturer'],
				'supplier'  						=> $supplier,
				'suppliers'  						=> $result['supplier'],
				'attribute'  						=> $result['attribute'],			
				'status'     						=> ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'location'     						=> $result['location'],
				'tax_class'     					=> $result['tax_class'],
				'price'      						=> $this->currency->format($price, $this->config->get('config_currency')),
				'price_raw'      					=> $price,
				'cost'      						=> $this->currency->format('-' . $cost, $this->config->get('config_currency')),
				'cost_raw'      					=> $cost,
				'profit'      						=> $this->currency->format(($price-$cost), $this->config->get('config_currency')),
				'profit_raw'      					=> $price-$cost,
				'profit_margin'     				=> $profit_margin,
				'profit_markup'     				=> $profit_markup,
				'viewed'     						=> $result['viewed'],
				'stock_quantity'     				=> $result['stock_quantity'],			
				'sold_quantity' 					=> $result['sold_quantity'] ? $result['sold_quantity'] : 0,
				'sold_percent' 						=> $sold_percent,
				'total_excl_vat'    				=> $this->currency->format($result['total_excl_vat'], $this->config->get('config_currency')),
				'total_excl_vat_raw'      			=> $result['total_excl_vat'],
				'total_tax'    						=> $this->currency->format($result['total_tax'], $this->config->get('config_currency')),
				'total_tax_raw'      				=> $result['total_tax'],
				'total_incl_vat'    				=> $this->currency->format($result['total_incl_vat'], $this->config->get('config_currency')),
				'total_incl_vat_raw'      			=> $result['total_incl_vat'],
				'app'      							=> $this->currency->format(($result['total_excl_vat'] ? $result['total_excl_vat'] / $result['sold_quantity'] : 0), $this->config->get('config_currency')),
				'app_raw'    			  			=> $result['total_excl_vat'] ? $result['total_excl_vat'] / $result['sold_quantity'] : 0,
				'discount'      					=> $this->currency->format($result['discount'], $this->config->get('config_currency')),
				'discount_raw'      				=> $result['discount'],
				'refunds'      						=> $this->currency->format($data['advppp_formula_6'] ? '-' . $result['refunds'] : $result['refunds'], $this->config->get('config_currency')),
				'refunds_raw'      					=> $result['refunds'],
				'reward_points'      				=> $result['reward_points'] ? $result['reward_points'] : 0,
				'total_sales'      					=> $this->currency->format($total_sales, $this->config->get('config_currency')),
				'total_sales_raw'      				=> $total_sales,				
				'total_costs'      					=> $this->currency->format('-' . ($total_costs), $this->config->get('config_currency')),
				'total_costs_raw'      				=> $total_costs,
				'total_profit'      				=> $this->currency->format(($total_sales-$total_costs), $this->config->get('config_currency')),
				'total_profit_raw'      			=> $total_sales-$total_costs,
				'total_profit_margin' 				=> $total_profit_margin,
				'total_profit_markup' 				=> $total_profit_markup,
				'sold_quantity_total' 				=> $sold_quantity_total,
				'sold_percent_total' 				=> $sold_percent_total,
				'total_excl_vat_total' 				=> $this->currency->format($total_excl_vat_total, $this->config->get('config_currency')),
				'total_excl_vat_total_raw'     	 	=> $total_excl_vat_total,
				'total_tax_total' 					=> $this->currency->format($total_tax_total, $this->config->get('config_currency')),
				'total_tax_total_raw'     	 		=> $total_tax_total,
				'total_incl_vat_total' 				=> $this->currency->format($total_incl_vat_total, $this->config->get('config_currency')),
				'total_incl_vat_total_raw'     	 	=> $total_incl_vat_total,
				'app_total' 						=> $this->currency->format(($total_excl_vat_total ? $total_excl_vat_total / $sold_quantity_total : 0), $this->config->get('config_currency')),
				'discount_total' 					=> $this->currency->format($discount_total, $this->config->get('config_currency')),
				'refunds_total' 					=> $this->currency->format($data['advppp_formula_6'] ? '-' . $refunds_total : $refunds_total, $this->config->get('config_currency')),
				'reward_points_total'     			=> $reward_points_total ? $reward_points_total : 0,
				'total_sales_total' 				=> $this->currency->format($total_sales_total, $this->config->get('config_currency')),
				'total_sales_total_raw'     	 	=> $total_sales_total,				
				'total_costs_total' 				=> $this->currency->format('-' . ($total_costs_total), $this->config->get('config_currency')),
				'total_costs_total_raw'      		=> $total_costs_total,
				'total_profit_total'      			=> $this->currency->format(($total_sales_total-$total_costs_total), $this->config->get('config_currency')),
				'total_profit_total_raw'      		=> $total_sales_total-$total_costs_total,
				'total_profit_margin_total' 		=> $total_profit_margin_total,
				'total_profit_markup_total' 		=> $total_profit_markup_total,
				'href' 								=> $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'], 'SSL')
			);
			
		  	} else if ($filter_report == 'products_without_orders') {
		
			$products[] = array(
				'date_added'   						=> date($data['advppp_date_format'] == 'DDMMYYYY' ? 'd/m/Y' : 'm/d/Y', strtotime($result['date_added'])),	
				'product_id'   	  					=> $result['id'],
				'image'      						=> $image,
				'sku'    							=> $result['sku'],
				'name'     							=> $result['name'],	
				'model'    							=> $result['model'],
				'category'  						=> $category,
				'categories'  						=> $result['categories'],
				'manufacturer'  					=> $manufacturer,
				'manufacturers'  					=> $result['manufacturer'],
				'supplier'  						=> $supplier,
				'suppliers'  						=> $result['supplier'],
				'attribute'  						=> $result['attribute'],			
				'status'     						=> ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'location'     						=> $result['location'],
				'tax_class'     					=> $result['tax_class'],
				'price'      						=> $this->currency->format($price, $this->config->get('config_currency')),
				'price_raw'      					=> $price,					
				'cost'      						=> $this->currency->format('-' . $cost, $this->config->get('config_currency')),
				'cost_raw'      					=> $cost,
				'profit'      						=> $this->currency->format(($price-$cost), $this->config->get('config_currency')),
				'profit_raw'      					=> $price-$cost,
				'profit_margin'     				=> $profit_margin,
				'profit_markup'     				=> $profit_markup,
				'viewed'     						=> $result['viewed'],				
				'stock_quantity'     				=> $result['quantity'],
				'href' 								=> $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'], 'SSL')
			);
			
		  	} else if ($filter_report == 'products_shopping_carts' or $filter_report == 'products_wishlists') {
			  
				if ($filter_report == 'products_shopping_carts') {
					$cart_wishlists_query = $this->db->query("SELECT sc.* FROM " . DB_PREFIX . "cart sc, " . DB_PREFIX . "customer c WHERE sc.product_id = '" . (int)$result['id'] . "' AND sc.option = '" . $result['option'] . "' AND sc.customer_id = c.customer_id");
				} else if ($filter_report == 'products_wishlists') {
					$cart_wishlists_query = $this->db->query("SELECT cw.* FROM " . DB_PREFIX . "customer_wishlist cw, " . DB_PREFIX . "customer c WHERE cw.product_id = '" . (int)$result['id'] . "' AND cw.customer_id = c.customer_id");
				}
				
				$customer_data = array();
					foreach ($cart_wishlists_query->rows as $customers) {
						$customer_query = $this->db->query("SELECT c.*, cgd.name AS customer_group, a.company AS company, a.address_1 AS address_1, a.address_2 AS address_2, a.city AS city, a.postcode AS postcode, z.name AS zone, cnt.name AS country FROM " . DB_PREFIX . "customer c, " . DB_PREFIX . "customer_group_description cgd, " . DB_PREFIX . "address a, " . DB_PREFIX . "zone z, " . DB_PREFIX . "country cnt WHERE c.customer_id = '" . (int)$customers['customer_id'] . "' AND c.customer_group_id = cgd.customer_group_id AND c.address_id = a.address_id AND z.zone_id = a.zone_id AND cnt.country_id = a.country_id AND c.status = '1'");
							$customer_data[] = array(
								'date_added'   	  		=> date($data['advppp_date_format'] == 'DDMMYYYY' ? 'd/m/Y' : 'm/d/Y', strtotime($cart_wishlists_query->row['date_added'])),
								'customer_id'         	=> $customer_query->row['customer_id'],
								'customer_name'         => $customer_query->row['firstname'] . ' ' . $customer_query->row['lastname'],
								'email'         		=> $customer_query->row['email'],
								'telephone'         	=> $customer_query->row['telephone'],
								'customer_group'        => $customer_query->row['customer_group'],
								'company'        		=> $customer_query->row['company'],
								'address_1'       		=> $customer_query->row['address_1'],
								'address_2'       		=> $customer_query->row['address_2'],
								'city'        			=> $customer_query->row['city'],
								'zone'        			=> $customer_query->row['zone'],									
								'postcode'        		=> $customer_query->row['postcode'],
								'country'        		=> $customer_query->row['country'],
								'href' 					=> $this->url->link('customer/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $customer_query->row['customer_id'], 'SSL')
							);
					}
				
				if ($filter_report == 'products_shopping_carts') {
				// Options
				$option_price = 0;
				$option_cost = 0;
				$option_data = array();
					foreach (json_decode($result['option']) as $product_option_id => $value) {
						$option_query = $this->db->query("SELECT po.product_option_id, po.option_id, od.name, o.type FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_option_id = '" . (int)$product_option_id . "' AND po.product_id = '" . (int)$result['id'] . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");
						if ($option_query->num_rows) {
							if ($option_query->row['type'] == 'select' || $option_query->row['type'] == 'radio' || $option_query->row['type'] == 'image') {
								$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.cost, pov.cost_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$value . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
							
								if ($option_value_query->num_rows) {
									if ($option_value_query->row['price_prefix'] == '+') {
										$option_price += $option_value_query->row['price'];
									} elseif ($option_value_query->row['price_prefix'] == '-') {
										$option_price -= $option_value_query->row['price'];
									}

									if ($option_value_query->row['cost_prefix'] == '+') {
										$option_cost += $option_value_query->row['cost'];
									} elseif ($option_value_query->row['cost_prefix'] == '-') {
										$option_cost -= $option_value_query->row['cost'];
									}
									
									$option_data[] = array(
										'name'                    => $option_query->row['name'],
										'value'            		  => $option_value_query->row['name'],
										'quantity'                => $option_value_query->row['quantity'],										
										'price'                   => $option_value_query->row['price'],
										'price_prefix'            => $option_value_query->row['price_prefix'],
										'cost'                    => $option_value_query->row['cost'],
										'cost_prefix'             => $option_value_query->row['cost_prefix']										
									);
								}
							} elseif ($option_query->row['type'] == 'checkbox' && is_array($value)) {
								foreach ($value as $product_option_value_id) {
									$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.cost, pov.cost_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
									
									if ($option_value_query->num_rows) {
										if ($option_value_query->row['price_prefix'] == '+') {
											$option_price += $option_value_query->row['price'];
										} elseif ($option_value_query->row['price_prefix'] == '-') {
											$option_price -= $option_value_query->row['price'];
										}

										if ($option_value_query->row['cost_prefix'] == '+') {
											$option_cost += $option_value_query->row['cost'];
										} elseif ($option_value_query->row['cost_prefix'] == '-') {
											$option_cost -= $option_value_query->row['cost'];
										}
									
										$option_data[] = array(
											'name'                    => $option_query->row['name'],
											'value'            		  => $option_value_query->row['name'],
											'quantity'                => $option_value_query->row['quantity'],											
											'price'                   => $option_value_query->row['price'],
											'price_prefix'            => $option_value_query->row['price_prefix'],
											'cost'                    => $option_value_query->row['cost'],
											'cost_prefix'             => $option_value_query->row['cost_prefix']
										);
									}
								}
							} elseif ($option_query->row['type'] == 'text' || $option_query->row['type'] == 'textarea' || $option_query->row['type'] == 'file' || $option_query->row['type'] == 'date' || $option_query->row['type'] == 'datetime' || $option_query->row['type'] == 'time') {
							
								$option_data[] = array(
									'name'                    => $option_query->row['name'],
									'value'            		  => $value,
									'quantity'                => '',
									'price'                   => '',
									'price_prefix'            => '',
									'cost'                    => '',
									'cost_prefix'             => ''									
								);
							}
						}
					}
					
					$profit_margin = ($price+$option_price) > 0 ? round(100 * ((($price+$option_price)-($cost+$option_cost))/($price+$option_price)), 2) . '%' : '0%';
					$profit_markup = ($cost+$option_cost) > 0 ? round(100 * ((($price+$option_price)-($cost+$option_cost))/($cost+$option_cost)), 2) . '%' : '0%';
				}
				
			$products[] = array(
				'date'   	  						=> $result['date'],
				'date_start' 						=> date($data['advppp_date_format'] == 'DDMMYYYY' ? 'd/m/Y' : 'm/d/Y', strtotime($result['date_start'])),
				'date_end'   						=> date($data['advppp_date_format'] == 'DDMMYYYY' ? 'd/m/Y' : 'm/d/Y', strtotime($result['date_end'])),				
				'product_id'   	  					=> $result['id'],
				'image'      						=> $image,
				'sku'    							=> $result['sku'],
				'name'     							=> $result['name'],	
				'option'     						=> $filter_report == 'products_shopping_carts' ? $option_data : '',
				'model'    							=> $result['model'],
				'category'  						=> $category,
				'categories'  						=> $result['categories'],
				'manufacturer'  					=> $manufacturer,
				'manufacturers'  					=> $result['manufacturer'],
				'supplier'  						=> $supplier,
				'suppliers'  						=> $result['supplier'],
				'attribute'  						=> $result['attribute'],			
				'status'     						=> ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'location'     						=> $result['location'],
				'tax_class'     					=> $result['tax_class'],
				'price'      						=> $this->currency->format($filter_report == 'products_shopping_carts' ? ($price+$option_price) : $price, $this->config->get('config_currency')),
				'price_raw'      					=> $filter_report == 'products_shopping_carts' ? ($price+$option_price) : $price,
				'cost'      						=> $this->currency->format($filter_report == 'products_shopping_carts' ? '-' . ($cost+$option_cost) : '-' . $cost, $this->config->get('config_currency')),
				'cost_raw'      					=> $filter_report == 'products_shopping_carts' ? ($cost+$option_cost) : $cost,				
				'profit'      						=> $this->currency->format($filter_report == 'products_shopping_carts' ? (($price+$option_price)-($cost+$option_cost)) : ($price-$cost), $this->config->get('config_currency')),
				'profit_raw'      					=> $filter_report == 'products_shopping_carts' ? (($price+$option_price)-($cost+$option_cost)) : ($price-$cost),	
				'profit_margin'     				=> $profit_margin,
				'profit_markup'     				=> $profit_markup,
				'viewed'     						=> $result['viewed'],				
				'stock_quantity'     				=> $result['stock_quantity'],
				'cart_quantity'     				=> $filter_report == 'products_shopping_carts' ? $result['cart_quantity'] : '',
				'wishlist_quantity'     			=> $filter_report == 'products_wishlists' ? $result['wishlist_quantity'] : '',
				'customer'     						=> $customer_data,				
				'href' 								=> $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'], 'SSL')
			);
			
			} else if ($filter_report == 'products_options') {
			
			$products[] = array(
				'year'		       					=> $result['year'],
				'quarter'		       				=> 'Q' . $result['quarter'],	
				'year_quarter'		       			=> 'Q' . $result['quarter']. ' ' . $result['year'],
				'month'		       					=> $result['month'],
				'year_month'		       			=> substr($result['month'],0,3) . ' ' . $result['year'],			
				'date_start' 						=> date($data['advppp_date_format'] == 'DDMMYYYY' ? 'd/m/Y' : 'm/d/Y', strtotime($result['date_start'])),
				'date_end'   						=> date($data['advppp_date_format'] == 'DDMMYYYY' ? 'd/m/Y' : 'm/d/Y', strtotime($result['date_end'])),
				'order_id'   						=> $result['order_id'],	
				'product_id' 						=> $result['product_id'],						
				'order_product_id'     				=> $result['order_product_id'],	
				'option_name'     					=> $result['option_name'],
				'option_details'     				=> $result['option_details'],
				'sold_quantity' 					=> $result['sold_quantity'],
				'sold_percent' 						=> $sold_percent,			
				'sold_quantity_total' 				=> $sold_quantity_total,
				'sold_percent_total' 				=> $sold_percent_total,
			) + $details;
			
		  	} else {

			if ($filter_report == 'products_purchased_with_options' or $filter_report == 'products_abandoned_orders') {

			// Options
			$option_price = 0;
			$option_cost = 0;
			$option_data = array();
			$options = $this->model_report_adv_products_profit->getOrderOptions($result['order_product_id']);

			foreach ($options as $option) {
				if ($option['type'] != 'file' && $option['type'] != 'image' && $option['type'] != 'date' && $option['type'] != 'datetime' && $option['type'] != 'time') {
					$option_value_query = $this->db->query("SELECT pov.quantity, pov.price, pov.price_prefix, pov.cost, pov.cost_prefix FROM " . DB_PREFIX . "product_option_value pov WHERE pov.product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND pov.product_option_id = '" . (int)$option['product_option_id'] . "'");
							
					if ($option_value_query->num_rows) {
						if ($option_value_query->row['price_prefix'] == '+') {
							$option_price += $option_value_query->row['price'];
						} elseif ($option_value_query->row['price_prefix'] == '-') {
							$option_price -= $option_value_query->row['price'];
						}

						if ($option_value_query->row['cost_prefix'] == '+') {
							$option_cost += $option_value_query->row['cost'];
						} elseif ($option_value_query->row['cost_prefix'] == '-') {
							$option_cost -= $option_value_query->row['cost'];
						}
									
						$option_data[] = array(
							'name'  				=> $option['name'],
							'value' 				=> $option['value'],
							'type'  				=> $option['type'],
							'quantity'              => $option_value_query->row['quantity'],	
							'price'             	=> $option_value_query->row['price'],
							'price_prefix'      	=> $option_value_query->row['price_prefix'],
							'cost'             		=> $option_value_query->row['cost'],
							'cost_prefix'     		=> $option_value_query->row['cost_prefix']										
						);
						
					} else {

						$option_data[] = array(
							'name'  				=> $option['name'],
							'value' 				=> $option['value'],
							'type'  				=> $option['type'],
							'quantity'             	=> '',
							'price'             	=> '',
							'price_prefix'      	=> '',
							'cost'             		=> '',
							'cost_prefix'     		=> ''										
						);
					}
					
					$profit_margin = ($price+$option_price) > 0 ? round(100 * ((($price+$option_price)-($cost+$option_cost))/($price+$option_price)), 2) . '%' : '0%';
					$profit_markup = ($cost+$option_cost) > 0 ? round(100 * ((($price+$option_price)-($cost+$option_cost))/($cost+$option_cost)), 2) . '%' : '0%';					
				}
			}
			
			} else {
			
			$option_data = '';
			
			}
					
			$products[] = array(
				'year'		       					=> $result['year'],
				'quarter'		       				=> 'Q' . $result['quarter'],	
				'year_quarter'		       			=> 'Q' . $result['quarter']. ' ' . $result['year'],
				'month'		       					=> $result['month'],
				'year_month'		       			=> substr($result['month'],0,3) . ' ' . $result['year'],			
				'date_start' 						=> date($data['advppp_date_format'] == 'DDMMYYYY' ? 'd/m/Y' : 'm/d/Y', strtotime($result['date_start'])),
				'date_end'   						=> date($data['advppp_date_format'] == 'DDMMYYYY' ? 'd/m/Y' : 'm/d/Y', strtotime($result['date_end'])),
				'order_id'   						=> $result['order_id'],	
				'product_id' 						=> $result['product_id'],						
				'order_product_id'     				=> $result['order_product_id'],	
				'image'      						=> $image,
				'sku'    							=> $result['sku'],
				'name'     							=> $result['name'],	
				'option'   		   					=> ($filter_report == 'products_purchased_with_options' or $filter_report == 'products_abandoned_orders') ? $option_data : '',				
				'model'    							=> $result['model'],
				'category'  						=> $category,
				'categories'  						=> $result['categories'],
				'manufacturer'  					=> $manufacturer,
				'manufacturers'  					=> $result['manufacturer'],
				'supplier'  						=> $supplier,
				'suppliers'  						=> $result['supplier'],
				'attribute'  						=> $result['attribute'],			
				'status'     						=> ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'location'     						=> $result['location'],
				'tax_class'     					=> $result['tax_class'],
				'price'      						=> $this->currency->format(($filter_report == 'products_purchased_with_options' or $filter_report == 'products_abandoned_orders') ? ($price+$option_price) : $price, $this->config->get('config_currency')),
				'price_raw'      					=> ($filter_report == 'products_purchased_with_options' or $filter_report == 'products_abandoned_orders') ? ($price+$option_price) : $price,
				'cost'      						=> $this->currency->format(($filter_report == 'products_purchased_with_options' or $filter_report == 'products_abandoned_orders') ? '-' . ($cost+$option_cost) : '-' . $cost, $this->config->get('config_currency')),
				'cost_raw'      					=> ($filter_report == 'products_purchased_with_options' or $filter_report == 'products_abandoned_orders') ? ($cost+$option_cost) : $cost,				
				'profit'      						=> $this->currency->format(($filter_report == 'products_purchased_with_options' or $filter_report == 'products_abandoned_orders') ? (($price+$option_price)-($cost+$option_cost)) : ($price-$cost), $this->config->get('config_currency')),
				'profit_raw'      					=> ($filter_report == 'products_purchased_with_options' or $filter_report == 'products_abandoned_orders') ? (($price+$option_price)-($cost+$option_cost)) : ($price-$cost),				
				'profit_margin'     				=> $profit_margin,
				'profit_markup'     				=> $profit_markup,
				'viewed'     						=> $result['viewed'],
				'stock_quantity'     				=> $result['stock_quantity'],	
				'sold_quantity' 					=> $result['sold_quantity'],
				'sold_percent' 						=> $sold_percent,
				'total_excl_vat'    				=> $this->currency->format($result['total_excl_vat'], $this->config->get('config_currency')),
				'total_excl_vat_raw'      			=> $result['total_excl_vat'],
				'total_tax'    						=> $this->currency->format($result['total_tax'], $this->config->get('config_currency')),
				'total_tax_raw'      				=> $result['total_tax'],
				'total_incl_vat'    				=> $this->currency->format($result['total_incl_vat'], $this->config->get('config_currency')),
				'total_incl_vat_raw'      			=> $result['total_incl_vat'],
				'app'      							=> $this->currency->format(($result['total_excl_vat'] ? $result['total_excl_vat'] / $result['sold_quantity'] : 0), $this->config->get('config_currency')),
				'app_raw'    			  			=> $result['total_excl_vat'] ? $result['total_excl_vat'] / $result['sold_quantity'] : 0,
				'discount'      					=> $this->currency->format($result['discount'], $this->config->get('config_currency')),
				'discount_raw'      				=> $result['discount'],
				'refunds'      						=> $this->currency->format($data['advppp_formula_6'] ? '-' . $result['refunds'] : $result['refunds'], $this->config->get('config_currency')),
				'refunds_raw'      					=> $result['refunds'],
				'reward_points'      				=> $result['reward_points'] ? $result['reward_points'] : 0,
				'total_sales'      					=> $this->currency->format($total_sales, $this->config->get('config_currency')),
				'total_sales_raw'      				=> $total_sales,				
				'total_costs'      					=> $this->currency->format('-' . ($total_costs), $this->config->get('config_currency')),
				'total_costs_raw'      				=> $total_costs,
				'total_profit'      				=> $this->currency->format(($total_sales-$total_costs), $this->config->get('config_currency')),
				'total_profit_raw'      			=> $total_sales-$total_costs,
				'total_profit_margin' 				=> $total_profit_margin,
				'total_profit_markup' 				=> $total_profit_markup,
				'gname'    							=> preg_replace('~\(.*?\)~', '', $result['name']),
				'gcategories'  						=> html_entity_decode($result['categories']),
				'gmanufacturer'    					=> html_entity_decode($result['manufacturer']),
				'gsupplier'    						=> html_entity_decode($result['supplier']),
				'gsold'    							=> $result['sold_quantity'],				
				'gsales'      						=> round($total_sales, 2),
				'gcosts'      						=> round($total_costs, 2),
				'gprofit'      						=> round($total_sales-$total_costs, 2),				
				'sold_quantity_total' 				=> $sold_quantity_total,
				'sold_percent_total' 				=> $sold_percent_total,
				'total_excl_vat_total' 				=> $this->currency->format($total_excl_vat_total, $this->config->get('config_currency')),
				'total_excl_vat_total_raw'     	 	=> $total_excl_vat_total,
				'total_tax_total' 					=> $this->currency->format($total_tax_total, $this->config->get('config_currency')),
				'total_tax_total_raw'     	 		=> $total_tax_total,
				'total_incl_vat_total' 				=> $this->currency->format($total_incl_vat_total, $this->config->get('config_currency')),
				'total_incl_vat_total_raw'     	 	=> $total_incl_vat_total,
				'app_total' 						=> $this->currency->format(($total_excl_vat_total ? $total_excl_vat_total / $sold_quantity_total : 0), $this->config->get('config_currency')),
				'discount_total' 					=> $this->currency->format($discount_total, $this->config->get('config_currency')),
				'refunds_total' 					=> $this->currency->format($data['advppp_formula_6'] ? '-' . $refunds_total : $refunds_total, $this->config->get('config_currency')),
				'reward_points_total'     			=> $reward_points_total ? $reward_points_total : 0,
				'total_sales_total' 				=> $this->currency->format($total_sales_total, $this->config->get('config_currency')),
				'total_sales_total_raw'     	 	=> $total_sales_total,				
				'total_costs_total' 				=> $this->currency->format('-' . ($total_costs_total), $this->config->get('config_currency')),
				'total_costs_total_raw'      		=> $total_costs_total,
				'total_profit_total'      			=> $this->currency->format(($total_sales_total-$total_costs_total), $this->config->get('config_currency')),
				'total_profit_total_raw'      		=> $total_sales_total-$total_costs_total,
				'total_profit_margin_total' 		=> $total_profit_margin_total,
				'total_profit_markup_total' 		=> $total_profit_markup_total,
				'href' 								=> $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'], 'SSL'),
			) + $details;
			
		  }
			  
		} else {

			// Options
			$option_data = array();
			$options = $this->model_report_adv_products_profit->getOrderOptions($result['order_product_id']);

			foreach ($options as $option) {
				if ($option['type'] != 'file' && $option['type'] != 'image' && $option['type'] != 'date' && $option['type'] != 'datetime' && $option['type'] != 'time') {
					$option_data[] = array(
						'name'  	=> $option['name'],
						'value' 	=> $option['value'],
						'type'  	=> $option['type']
					);
				}
			}

			// Taxes
			$tax_data = array();
			$taxes = $this->model_report_adv_products_profit->getOrderTaxesDivided($result['order_id']);

			foreach ($taxes as $tax) {
				$tax_data[] = array(
						'title'  	=> $tax['title'],
						'value' 	=> $tax['value']
				);
			}
			
			// Custom Fields
			$this->load->model('customer/custom_field');

			$account_custom_fields = array();

			$filter_data = array(
				'sort'  => 'cf.sort_order',
				'order' => 'ASC',
			);

			$custom_fields = $this->model_customer_custom_field->getCustomFields($filter_data);
			$data['custom_fields'] = $this->model_report_adv_products_profit->getCustomFieldsNames($filter_data);			
			$order_custom_field = json_decode($result['custom_field'], true);

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'account' && isset($order_custom_field[$custom_field['custom_field_id']])) {
					if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio') {
						$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($order_custom_field[$custom_field['custom_field_id']]);

						if ($custom_field_value_info) {
							$account_custom_fields[] = array(
								'name'  => $custom_field['name'],
								'value' => $custom_field_value_info['name']
							);
						}
					}

					if ($custom_field['type'] == 'checkbox' && is_array($order_custom_field[$custom_field['custom_field_id']])) {
						foreach ($order_custom_field[$custom_field['custom_field_id']] as $custom_field_value_id) {
							$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($custom_field_value_id);

							if ($custom_field_value_info) {
								$account_custom_fields[] = array(
									'name'  => $custom_field['name'],
									'value' => $custom_field_value_info['name']
								);
							}
						}
					}

					if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
						$account_custom_fields[] = array(
							'name'  => $custom_field['name'],
							'value' => $order_custom_field[$custom_field['custom_field_id']]
						);
					}
				}
			}
			
			$profit_margin = $result['product_sales'] > 0 ? round(100 * (($result['product_sales']-$result['product_cost'])/$result['product_sales']), 2) . '%' : '0%';
			$profit_markup = $result['product_cost'] > 0 ? round(100 * (($result['product_sales']-$result['product_cost'])/$result['product_cost']), 2) . '%' : '0%';
			$order_profit_prc = ($result['order_sales']) > 0 ? round(100 * (($result['order_sales']-$result['order_costs']) / $result['order_sales']), 2) . '%' : '0%';
			
			$products[] = array(
				'order_id'   					=> $result['order_id'],	
				'order_id_link'     			=> $result['order_id_link'], 
				'date_added'    				=> date($data['advppp_date_format'] == 'DDMMYYYY' ? 'd/m/Y' : 'm/d/Y', strtotime($result['date_added'])),
				'invoice'     					=> $result['invoice_prefix'] . $result['invoice_no'],
				'name'   						=> $result['firstname'] . ' ' . $result['lastname'],
				'email'   						=> $result['email'],
				'cust_group'   					=> $result['cust_group'],
				'product_id'  					=> $result['product_id'],	
				'product_id_link'  				=> $result['product_id_link'],	
				'product_sku'  					=> $result['product_sku'],
				'product_model'  				=> $result['product_model'],				
				'product_name'  				=> $result['product_name'],	
				'product_option'   		   		=> $option_data,				
				'product_attributes'  			=> $result['product_attributes'],
				'product_category'  			=> $result['product_category'],				
				'product_manu'  				=> $result['product_manu'],
				'product_supplier'  			=> $result['product_supplier'],
				'currency_code' 				=> $result['currency_code'],
				'product_price'        			=> $this->currency->format($result['product_price'], $this->config->get('config_currency')),
				'product_price_raw'  			=> $result['product_price'],
				'product_quantity'  			=> $result['product_quantity'],
				'product_total_excl_vat'  		=> $this->currency->format($result['product_total_excl_vat'], $this->config->get('config_currency')),
				'product_total_excl_vat_raw'  	=> $result['product_total_excl_vat'],
				'product_tax'  					=> $this->currency->format($result['product_tax'], $this->config->get('config_currency')),
				'product_tax_raw'  				=> $result['product_tax'],
				'product_total_incl_vat'  		=> $this->currency->format($result['product_total_incl_vat'], $this->config->get('config_currency')),
				'product_total_incl_vat_raw'  	=> $result['product_total_incl_vat'],
				'product_discount'  			=> $this->currency->format($result['product_discount'], $this->config->get('config_currency')),
				'product_discount_raw'  		=> $result['product_discount'],
				'product_qty_refund'  			=> $result['product_qty_refund'],
				'product_refund'  				=> $this->currency->format($data['advppp_formula_6'] ? '-' . $result['product_refund'] : $result['product_refund'], $this->config->get('config_currency')),
				'product_refund_raw'  			=> $result['product_refund'],
				'product_reward_points'      	=> $result['product_reward_points'] ? $result['product_reward_points'] : 0,
				'product_sales'  				=> $this->currency->format($result['product_sales'], $this->config->get('config_currency')),
				'product_sales_raw'  			=> $result['product_sales'],				
				'product_cost'   				=> $this->currency->format('-' . $result['product_cost'], $this->config->get('config_currency')),
				'product_cost_raw'   			=> $result['product_cost'],
				'product_profit'   				=> $this->currency->format(($result['product_sales']-$result['product_cost']), $this->config->get('config_currency')),
				'product_profit_raw'   			=> $result['product_sales']-$result['product_cost'],
				'product_profit_margin' 		=> $profit_margin,
				'product_profit_markup' 		=> $profit_markup,
				'order_sub_total'  				=> $this->currency->format($result['order_sub_total'], $this->config->get('config_currency')),
				'order_sub_total_raw'  			=> $result['order_sub_total'],
				'order_handling'  				=> $this->currency->format($result['order_handling'], $this->config->get('config_currency')),
				'order_handling_raw'  			=> $result['order_handling'],
				'order_low_order_fee'  			=> $this->currency->format($result['order_low_order_fee'], $this->config->get('config_currency')),
				'order_low_order_fee_raw'		=> $result['order_low_order_fee'],
				'order_shipping'  				=> $this->currency->format($result['order_shipping'], $this->config->get('config_currency')),
				'order_shipping_raw'  			=> $result['order_shipping'],
				'order_reward'  				=> $this->currency->format($result['order_reward'], $this->config->get('config_currency')),
				'order_reward_raw'  			=> $result['order_reward'],
				'order_earned_points'      		=> $result['order_earned_points'] ? $result['order_earned_points'] : 0,
				'order_used_points'      		=> $result['order_used_points'] ? $result['order_used_points'] : 0,				
				'order_coupon'  				=> $this->currency->format($result['order_coupon'], $this->config->get('config_currency')),
				'order_coupon_raw'  			=> $result['order_coupon'],
				'order_coupon_name'  			=> $result['order_coupon_name'],
				'order_coupon_code'  			=> $result['order_coupon_code'],
				'order_tax'  					=> $this->currency->format($result['order_tax'], $this->config->get('config_currency')),
				'order_tax_raw'  				=> $result['order_tax'],
				'order_taxes'  					=> $tax_data,
				'order_credit'  				=> $this->currency->format($result['order_credit'], $this->config->get('config_currency')),
				'order_credit_raw'  			=> $result['order_credit'],
				'order_voucher'  				=> $this->currency->format($result['order_voucher'], $this->config->get('config_currency')),
				'order_voucher_raw'  			=> $result['order_voucher'],
				'order_voucher_code'  			=> $result['order_voucher_code'],
				'order_discount'      			=> $this->currency->format($result['order_discount'], $this->config->get('config_currency')),
				'order_discount_raw'      		=> $result['order_discount'],					
				'order_value'  					=> $this->currency->format($result['order_value'], $this->config->get('config_currency')),
				'order_value_raw'  				=> $result['order_value'],
				'order_refund'      			=> $this->currency->format($data['advppp_formula_6'] ? '-' . $result['order_refund'] : $result['order_refund'], $this->config->get('config_currency')),
				'order_refund_raw'      		=> $result['order_refund'],
				'order_sales'   				=> $this->currency->format($result['order_sales'], $this->config->get('config_currency')),
				'order_sales_raw'   			=> $result['order_sales'],
				'order_product_costs'   		=> $this->currency->format('-' . ($result['order_product_costs']-($data['advppp_formula_7'] ? $result['order_product_costs_returns'] : 0)), $this->config->get('config_currency')),
				'order_product_costs_raw'   	=> $result['order_product_costs']-($data['advppp_formula_7'] ? $result['order_product_costs_returns'] : 0),
				'order_commission'   			=> $this->currency->format('-' . ($result['order_commission']), $this->config->get('config_currency')),
				'order_commission_raw'   		=> $result['order_commission'],
				'order_payment_cost'   			=> $this->currency->format('-' . ($result['order_payment_cost']), $this->config->get('config_currency')),
				'order_payment_cost_raw'   		=> $result['order_payment_cost'],
				'order_shipping_cost'   		=> $this->currency->format('-' . ($result['order_shipping_cost']), $this->config->get('config_currency')),
				'order_shipping_cost_raw'   	=> $result['order_shipping_cost'],
				'order_extra_cost'   			=> $this->currency->format('-' . ($result['order_extra_cost']), $this->config->get('config_currency')),
				'order_extra_cost_raw'   		=> $result['order_extra_cost'],
				'order_return_cost'   			=> $this->currency->format('-' . ($result['order_return_cost']), $this->config->get('config_currency')),
				'order_return_cost_raw'   		=> $result['order_return_cost'],
				'order_ship_balance'      		=> $this->currency->format($result['order_shipping']-$result['order_shipping_cost'], $this->config->get('config_currency')),
				'order_ship_balance_raw'      	=> $result['order_shipping']-$result['order_shipping_cost'],				
				'order_costs'   				=> $this->currency->format('-' . ($result['order_costs']), $this->config->get('config_currency')),
				'order_costs_raw'   			=> $result['order_costs'],
				'order_profit'   				=> $this->currency->format(($result['order_sales']-$result['order_costs']), $this->config->get('config_currency')),
				'order_profit_raw'   			=> $result['order_sales']-$result['order_costs'],
				'order_profit_prc' 				=> $order_profit_prc,
				'shipping_method' 				=> preg_replace('~\(.*?\)~', '', $result['shipping_method']),
				'payment_method'  				=> preg_replace('~\(.*?\)~', '', $result['payment_method']),
				'order_status'  				=> $result['order_status'],
				'store_name'      				=> $result['store_name'],	
				'customer_id' 					=> $result['customer_id'],	
				'customer_id_link' 				=> $result['customer_id_link'],	
				'custom_field' 					=> $result['custom_field'],	
				'custom_fields' 				=> $account_custom_fields,
				'payment_firstname' 			=> $result['payment_firstname'],
				'payment_lastname' 				=> $result['payment_lastname'],
				'payment_company' 				=> $result['payment_company'],
				'payment_address_1' 			=> $result['payment_address_1'],
				'payment_address_2' 			=> $result['payment_address_2'],
				'payment_city' 					=> $result['payment_city'],
				'payment_zone' 					=> $result['payment_zone'],
				'payment_zone_id' 				=> $result['payment_zone_id'],
				'payment_zone_code' 			=> $result['payment_zone_code'],
				'payment_postcode' 				=> $result['payment_postcode'],	
				'payment_country' 				=> $result['payment_country'],
				'payment_country_id' 			=> $result['payment_country_id'],
				'payment_country_code' 			=> $result['payment_country_code'],
				'telephone' 					=> $result['telephone'],
				'shipping_firstname' 			=> $result['shipping_firstname'],
				'shipping_lastname' 			=> $result['shipping_lastname'],
				'shipping_company' 				=> $result['shipping_company'],
				'shipping_address_1' 			=> $result['shipping_address_1'],
				'shipping_address_2' 			=> $result['shipping_address_2'],
				'shipping_city' 				=> $result['shipping_city'],
				'shipping_zone' 				=> $result['shipping_zone'],
				'shipping_zone_id' 				=> $result['shipping_zone_id'],
				'shipping_zone_code' 			=> $result['shipping_zone_code'],
				'shipping_postcode' 			=> $result['shipping_postcode'],	
				'shipping_country' 				=> $result['shipping_country'],
				'shipping_country_id' 			=> $result['shipping_country_id'],
				'shipping_country_code' 		=> $result['shipping_country_code'],
				'order_weight' 					=> $result['order_weight'] . $result['weight_class'],
				'order_comment' 				=> $result['comment']
			);
			
			}
			
		} //seems there ends processing
	}//check ends

		$data['adv_ext_name'] = $this->language->get('adv_ext_name');
		$data['adv_ext_short_name'] = 'adv_products_profit';
		$data['adv_ext_version'] = $this->language->get('adv_ext_version');	
		$data['adv_ext_url'] = 'https://www.opencart.com/index.php?route=marketplace/extension/info&extension_id=4170';
		
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');		
		$data['text_products_purchased'] = $this->language->get('text_products_purchased');
		$data['text_product_options'] = $this->language->get('text_product_options');
		$data['text_manufacturers'] = $this->language->get('text_manufacturers');
		$data['text_categories'] = $this->language->get('text_categories');
		$data['text_suppliers'] = $this->language->get('text_suppliers');
		$data['text_no_details'] = $this->language->get('text_no_details');
		$data['text_basic_details'] = $this->language->get('text_basic_details');
		$data['text_all_details'] = $this->language->get('text_all_details');		
		$data['text_no_results'] = $this->language->get('text_no_results');		
		$data['text_all'] = $this->language->get('text_all');
		$data['text_for_export'] = $this->language->get('text_for_export');
		$data['text_select_all'] = $this->language->get('text_select_all');
		$data['text_all_status'] = $this->language->get('text_all_status');		
		$data['text_all_stores'] = $this->language->get('text_all_stores');
		$data['text_all_currencies'] = $this->language->get('text_all_currencies');
		$data['text_all_taxes'] = $this->language->get('text_all_taxes');	
		$data['text_all_tax_classes'] = $this->language->get('text_all_tax_classes');			
		$data['text_all_zones'] = $this->language->get('text_all_zones');			
		$data['text_all_groups'] = $this->language->get('text_all_groups');
		$data['text_all_payment_methods'] = $this->language->get('text_all_payment_methods');	
		$data['text_all_shipping_methods'] = $this->language->get('text_all_shipping_methods');
		$data['text_all_categories'] = $this->language->get('text_all_categories');
		$data['text_all_manufacturers'] = $this->language->get('text_all_manufacturers');
		$data['text_all_suppliers'] = $this->language->get('text_all_suppliers');	
		$data['text_all_options'] = $this->language->get('text_all_options');
		$data['text_all_attributes'] = $this->language->get('text_all_attributes');
		$data['text_all_locations'] = $this->language->get('text_all_locations');	
		$data['text_all_affiliate_names'] = $this->language->get('text_all_affiliate_names');
		$data['text_all_affiliate_emails'] = $this->language->get('text_all_affiliate_emails');
		$data['text_all_coupon_names'] = $this->language->get('text_all_coupon_names');
		$data['text_selected'] = $this->language->get('text_selected');		
		$data['text_detail'] = $this->language->get('text_detail');	
		$data['text_customer_list'] = $this->language->get('text_customer_list');
		$data['text_filter_total'] = $this->language->get('text_filter_total');
		$data['text_load_save_options'] = $this->language->get('text_load_save_options');
		$data['text_load_save'] = $this->language->get('text_load_save');		
		$data['text_export_options'] = $this->language->get('text_export_options');
		$data['text_report_type'] = $this->language->get('text_report_type');
		$data['help_report_type'] = $this->language->get('help_report_type');
		$data['text_export_type'] = $this->language->get('text_export_type');
		$data['text_select_export_type'] = $this->language->get('text_select_export_type');
		$data['text_export_logo_criteria'] = $this->language->get('text_export_logo_criteria');
		$data['text_export_csv_delimiter'] = $this->language->get('text_export_csv_delimiter');	
		$data['text_export_no_details'] = $this->language->get('text_export_no_details');
		$data['text_export_all_details'] = $this->language->get('text_export_all_details');
		$data['text_all_details_products'] = $this->language->get('text_all_details_products');
		$data['text_all_details_orders'] = $this->language->get('text_all_details_orders');
		$data['text_export_basic_details'] = $this->language->get('text_export_basic_details');	
		$data['text_formula_setting1'] = $this->language->get('text_formula_setting1');
		$data['text_formula_setting2'] = $this->language->get('text_formula_setting2');
		$data['text_formula_setting3'] = $this->language->get('text_formula_setting3');	
		$data['text_formula_setting4'] = $this->language->get('text_formula_setting4');	
		$data['text_formula_setting5'] = $this->language->get('text_formula_setting5');
		$data['text_formula_setting6'] = $this->language->get('text_formula_setting6');	
		$data['text_formula_setting7'] = $this->language->get('text_formula_setting7');	
		$data['text_return_action'] = $this->language->get('text_return_action');
		$data['text_profit_calculation'] = $this->language->get('text_profit_calculation');
		$data['text_formula_explanation'] = $this->language->get('text_formula_explanation');
		$data['text_local_settings'] = $this->language->get('text_local_settings');
		$data['text_check_all'] = $this->language->get('text_check_all');
		$data['text_uncheck_all'] = $this->language->get('text_uncheck_all');
		$data['text_filtering_options'] = $this->language->get('text_filtering_options');
		$data['text_column_settings'] = $this->language->get('text_column_settings');
		$data['text_mv_columns'] = $this->language->get('text_mv_columns');		
		$data['text_bd_columns'] = $this->language->get('text_bd_columns');	
		$data['text_all_columns'] = $this->language->get('text_all_columns');		
		$data['text_export_note'] = $this->language->get('text_export_note');
		$data['text_report_settings'] = $this->language->get('text_report_settings');
		$data['text_cron_settings'] = $this->language->get('text_cron_settings');
		$data['text_module_settings'] = $this->language->get('text_module_settings');
		$data['text_format_date'] = $this->language->get('text_format_date');
		$data['text_format_date_eu'] = $this->language->get('text_format_date_eu');
		$data['text_format_date_us'] = $this->language->get('text_format_date_us');
		$data['text_format_hour'] = $this->language->get('text_format_hour');
		$data['text_format_hour_24'] = $this->language->get('text_format_hour_24');
		$data['text_format_hour_12'] = $this->language->get('text_format_hour_12');		
		$data['text_format_week'] = $this->language->get('text_format_week');
		$data['text_format_week_mon_sun'] = $this->language->get('text_format_week_mon_sun');
		$data['text_format_week_sun_sat'] = $this->language->get('text_format_week_sun_sat');
		$data['text_export_notice1'] = $this->language->get('text_export_notice1');
		$data['text_export_notice2'] = $this->language->get('text_export_notice2');		
		$data['text_export_limit'] = $this->language->get('text_export_limit');
		$data['text_cron'] = $this->language->get('text_cron');
		$data['text_cron_1'] = $this->language->get('text_cron_1');
		$data['text_cron_1_text'] = $this->language->get('text_cron_1_text');
		$data['text_cron_2'] = $this->language->get('text_cron_2');
		$data['text_cron_3'] = $this->language->get('text_cron_3');
		$data['text_cron_3_text'] = $this->language->get('text_cron_3_text');
		$data['text_cron_3_option1'] = $this->language->get('text_cron_3_option1');
		$data['text_cron_3_option2'] = $this->language->get('text_cron_3_option2');
		$data['text_save_path'] = $this->language->get('text_save_path');
		$data['help_save_path'] = $this->language->get('help_save_path');
		$data['text_cron_email'] = $this->language->get('text_cron_email');
		$data['help_cron_email'] = $this->language->get('help_cron_email');
		$data['text_cron_file_name'] = $this->language->get('text_cron_file_name');	
		$data['help_cron_file_name'] = $this->language->get('help_cron_file_name');
		$data['text_cron_4'] = $this->language->get('text_cron_4');
		$data['text_cron_user'] = $this->language->get('text_cron_user');
		$data['help_cron_user'] = $this->language->get('help_cron_user');
		$data['text_cron_pass'] = $this->language->get('text_cron_pass');
		$data['help_cron_pass'] = $this->language->get('help_cron_pass');
		$data['text_cron_token'] = $this->language->get('text_cron_token');
		$data['help_cron_token'] = $this->language->get('help_cron_token');
		$data['text_token_generate'] = $this->language->get('text_token_generate');
		$data['text_cron_5'] = $this->language->get('text_cron_5');
		$data['text_cpanel_setting'] = $this->language->get('text_cpanel_setting');
		$data['text_cpanel_setting_note'] = $this->language->get('text_cpanel_setting_note');
		$data['text_cron_command'] = $this->language->get('text_cron_command');
		$data['text_cron_command_empty'] = $this->language->get('text_cron_command_empty');
		$data['text_cron_6'] = $this->language->get('text_cron_6');
		$data['entry_cron_title'] = $this->language->get('entry_cron_title');
		$data['entry_cron_command'] = $this->language->get('entry_cron_command');
		$data['text_save_cron'] = $this->language->get('text_save_cron');		
		$data['text_pagin_page'] = $this->language->get('text_pagin_page');
		$data['text_pagin_of'] = $this->language->get('text_pagin_of');
		$data['text_pagin_results'] = $this->language->get('text_pagin_results');	
		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_report_date'] = $this->language->get('text_report_date');
		$data['text_report_criteria'] = $this->language->get('text_report_criteria');
		$data['text_email'] = $this->language->get('text_email');
		$data['text_telephone'] = $this->language->get('text_telephone');
		$data['text_report_title'] = $this->language->get('text_report_title');	
		$data['text_cron_title'] = $this->language->get('text_cron_title');	

		$data['column_date'] = $this->language->get('column_date');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_date_start'] = $this->language->get('column_date_start');
		$data['column_date_end'] = $this->language->get('column_date_end');
		$data['column_id'] = $this->language->get('column_id');
		$data['column_image'] = $this->language->get('column_image');
		$data['column_sku'] = $this->language->get('column_sku');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_pname'] = $this->language->get('column_pname');
		$data['column_poption'] = $this->language->get('column_poption');
		$data['column_model'] = $this->language->get('column_model');	
		$data['column_category'] = $this->language->get('column_category');		
		$data['column_manufacturer'] = $this->language->get('column_manufacturer');
		$data['column_supplier'] = $this->language->get('column_supplier');
		$data['column_attribute'] = $this->language->get('column_attribute');	
		$data['column_status'] = $this->language->get('column_status');
		$data['column_location'] = $this->language->get('column_location');
		$data['column_tax_class'] = $this->language->get('column_tax_class');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_cost'] = $this->language->get('column_cost');
		$data['column_profit'] = $this->language->get('column_profit');
		$data['help_profit'] = $this->language->get('help_profit');
		$data['column_profit_margin'] = $this->language->get('column_profit_margin');
		$data['help_profit_margin'] = $this->language->get('help_profit_margin');
		$data['column_profit_markup'] = $this->language->get('column_profit_markup');
		$data['help_profit_markup'] = $this->language->get('help_profit_markup');
		$data['column_viewed'] = $this->language->get('column_viewed');
		$data['column_stock_quantity'] = $this->language->get('column_stock_quantity');		
		$data['column_cart_quantity'] = $this->language->get('column_cart_quantity');
		$data['column_wishlist_quantity'] = $this->language->get('column_wishlist_quantity');		
		$data['column_sold_quantity'] = $this->language->get('column_sold_quantity');
		$data['column_sold_percent'] = $this->language->get('column_sold_percent');
		$data['column_total_excl_vat'] = $this->language->get('column_total_excl_vat');			
		$data['column_total_tax'] = $this->language->get('column_total_tax');
		$data['column_total_incl_vat'] = $this->language->get('column_total_incl_vat');	
		$data['column_app'] = $this->language->get('column_app');
		$data['column_product_discount'] = $this->language->get('column_product_discount');
		$data['column_product_refunds'] = $this->language->get('column_product_refunds');
		$data['column_product_reward_points'] = $this->language->get('column_product_reward_points');
		$data['column_total_sales'] = $this->language->get('column_total_sales');
		$data['column_total_costs'] = $this->language->get('column_total_costs');
		$data['column_total_profit'] = $this->language->get('column_total_profit');
		$data['help_total_profit'] = $this->language->get('help_total_profit');
		$data['column_total_profit_margin'] = $this->language->get('column_total_profit_margin');
		$data['help_total_profit_margin'] = $this->language->get('help_total_profit_margin');
		$data['column_total_profit_markup'] = $this->language->get('column_total_profit_markup');
		$data['help_total_profit_markup'] = $this->language->get('help_total_profit_markup');
		$data['column_action'] = $this->language->get('column_action');
		$data['column_sub_total'] = $this->language->get('column_sub_total');
		$data['column_handling'] = $this->language->get('column_handling');	
		$data['column_loworder'] = $this->language->get('column_loworder');
		$data['column_shipping'] = $this->language->get('column_shipping');
		$data['column_reward'] = $this->language->get('column_reward');
		$data['column_earned_reward_points'] = $this->language->get('column_earned_reward_points');
		$data['column_used_reward_points'] = $this->language->get('column_used_reward_points');		
		$data['column_coupon'] = $this->language->get('column_coupon');
		$data['column_coupon_name'] = $this->language->get('column_coupon_name');
		$data['column_coupon_code'] = $this->language->get('column_coupon_code');
		$data['column_taxes'] = $this->language->get('column_taxes');		
		$data['column_credit'] = $this->language->get('column_credit');	
		$data['column_voucher'] = $this->language->get('column_voucher');	
		$data['column_voucher_code'] = $this->language->get('column_voucher_code');	
		$data['column_discount'] = $this->language->get('column_discount');
		$data['column_product_costs'] = $this->language->get('column_product_costs');		
		$data['column_commission'] = $this->language->get('column_commission');	
		$data['column_payment_cost'] = $this->language->get('column_payment_cost');
		$data['column_shipping_cost'] = $this->language->get('column_shipping_cost');
		$data['column_extra_cost'] = $this->language->get('column_extra_cost');
		$data['column_return_cost'] = $this->language->get('column_return_cost');
		$data['column_shipping_balance'] = $this->language->get('column_shipping_balance');	
		$data['column_company'] = $this->language->get('column_company');
		$data['column_address_1'] = $this->language->get('column_address_1');
		$data['column_address_2'] = $this->language->get('column_address_2');
		$data['column_city'] = $this->language->get('column_city');
		$data['column_region_state'] = $this->language->get('column_region_state');			
		$data['column_postcode'] = $this->language->get('column_postcode');
		$data['column_country'] = $this->language->get('column_country');			
		$data['column_order_date_added'] = $this->language->get('column_order_date_added');
		$data['column_order_order_id'] = $this->language->get('column_order_order_id');
		$data['column_order_inv_no'] = $this->language->get('column_order_inv_no');
		$data['column_order_customer'] = $this->language->get('column_order_customer');
		$data['column_order_email'] = $this->language->get('column_order_email');		
		$data['column_order_customer_group'] = $this->language->get('column_order_customer_group');		
		$data['column_order_shipping_method'] = $this->language->get('column_order_shipping_method');
		$data['column_order_payment_method'] = $this->language->get('column_order_payment_method');		
		$data['column_order_status'] = $this->language->get('column_order_status');
		$data['column_order_store'] = $this->language->get('column_order_store');
		$data['column_order_currency'] = $this->language->get('column_order_currency');		
		$data['column_order_quantity'] = $this->language->get('column_order_quantity');	
		$data['column_order_sub_total'] = $this->language->get('column_order_sub_total');	
		$data['column_order_shipping'] = $this->language->get('column_order_shipping');
		$data['column_order_tax'] = $this->language->get('column_order_tax');			
		$data['column_order_value'] = $this->language->get('column_order_value');
		$data['column_order_refund'] = $this->language->get('column_order_refund');
		$data['column_order_sales'] = $this->language->get('column_order_sales');
		$data['column_order_prod_costs'] = $this->language->get('column_order_prod_costs');		
		$data['column_order_commission'] = $this->language->get('column_order_commission');	
		$data['column_order_payment_cost'] = $this->language->get('column_order_payment_cost');
		$data['column_order_shipping_cost'] = $this->language->get('column_order_shipping_cost');
		$data['column_order_extra_cost'] = $this->language->get('column_order_extra_cost');
		$data['column_order_return_cost'] = $this->language->get('column_order_return_cost');
		$data['column_order_costs'] = $this->language->get('column_order_costs');
		$data['column_order_profit'] = $this->language->get('column_order_profit');	
		$data['column_order_margin'] = $this->language->get('column_order_margin');	
		$data['column_prod_order_id'] = $this->language->get('column_prod_order_id');		
		$data['column_prod_date_added'] = $this->language->get('column_prod_date_added');
		$data['column_prod_inv_no'] = $this->language->get('column_prod_inv_no');
		$data['column_prod_id'] = $this->language->get('column_prod_id');
		$data['column_prod_sku'] = $this->language->get('column_prod_sku');		
		$data['column_prod_model'] = $this->language->get('column_prod_model');		
		$data['column_prod_name'] = $this->language->get('column_prod_name');	
		$data['column_prod_option'] = $this->language->get('column_prod_option');	
		$data['column_prod_attributes'] = $this->language->get('column_prod_attributes');			
		$data['column_prod_manu'] = $this->language->get('column_prod_manu');
		$data['column_prod_category'] = $this->language->get('column_prod_category');
		$data['column_prod_supplier'] = $this->language->get('column_prod_supplier');
		$data['column_prod_currency'] = $this->language->get('column_prod_currency');
		$data['column_prod_price'] = $this->language->get('column_prod_price');
		$data['column_prod_quantity'] = $this->language->get('column_prod_quantity');
		$data['column_prod_total_excl_vat'] = $this->language->get('column_prod_total_excl_vat');
		$data['column_prod_tax'] = $this->language->get('column_prod_tax');
		$data['column_prod_total_incl_vat'] = $this->language->get('column_prod_total_incl_vat');
		$data['column_prod_discount'] = $this->language->get('column_prod_discount');
		$data['column_prod_qty_refunded'] = $this->language->get('column_prod_qty_refunded');
		$data['column_prod_refunded'] = $this->language->get('column_prod_refunded');
		$data['column_prod_reward_points'] = $this->language->get('column_prod_reward_points');
		$data['column_prod_sales'] = $this->language->get('column_prod_sales');
		$data['column_prod_cost'] = $this->language->get('column_prod_cost');	
		$data['column_prod_profit'] = $this->language->get('column_prod_profit');
		$data['column_prod_profit_margin'] = $this->language->get('column_prod_profit_margin');
		$data['column_prod_profit_markup'] = $this->language->get('column_prod_profit_markup');
		$data['column_customer_order_id'] = $this->language->get('column_customer_order_id');
		$data['column_customer_date_added'] = $this->language->get('column_customer_date_added');		
		$data['column_customer_cust_id'] = $this->language->get('column_customer_cust_id');
		$data['column_custom_fields'] = $this->language->get('column_custom_fields');
		$data['column_billing_name'] = $this->language->get('column_billing_name');
		$data['column_billing_first_name'] = $this->language->get('column_billing_first_name');
		$data['column_billing_last_name'] = $this->language->get('column_billing_last_name');
		$data['column_billing_company'] = $this->language->get('column_billing_company');
		$data['column_billing_address_1'] = $this->language->get('column_billing_address_1');
		$data['column_billing_address_2'] = $this->language->get('column_billing_address_2');
		$data['column_billing_city'] = $this->language->get('column_billing_city');
		$data['column_billing_zone'] = $this->language->get('column_billing_zone');
		$data['column_billing_zone_id'] = $this->language->get('column_billing_zone_id');
		$data['column_billing_zone_code'] = $this->language->get('column_billing_zone_code');
		$data['column_billing_postcode'] = $this->language->get('column_billing_postcode');		
		$data['column_billing_country'] = $this->language->get('column_billing_country');
		$data['column_billing_country_id'] = $this->language->get('column_billing_country_id');
		$data['column_billing_country_code'] = $this->language->get('column_billing_country_code');
		$data['column_customer_telephone'] = $this->language->get('column_customer_telephone');
		$data['column_shipping_name'] = $this->language->get('column_shipping_name');
		$data['column_shipping_first_name'] = $this->language->get('column_shipping_first_name');
		$data['column_shipping_last_name'] = $this->language->get('column_shipping_last_name');
		$data['column_shipping_company'] = $this->language->get('column_shipping_company');
		$data['column_shipping_address_1'] = $this->language->get('column_shipping_address_1');
		$data['column_shipping_address_2'] = $this->language->get('column_shipping_address_2');
		$data['column_shipping_city'] = $this->language->get('column_shipping_city');
		$data['column_shipping_zone'] = $this->language->get('column_shipping_zone');
		$data['column_shipping_zone_id'] = $this->language->get('column_shipping_zone_id');
		$data['column_shipping_zone_code'] = $this->language->get('column_shipping_zone_code');
		$data['column_shipping_postcode'] = $this->language->get('column_shipping_postcode');		
		$data['column_shipping_country'] = $this->language->get('column_shipping_country');
		$data['column_shipping_country_id'] = $this->language->get('column_shipping_country_id');
		$data['column_shipping_country_code'] = $this->language->get('column_shipping_country_code');
		$data['column_order_weight'] = $this->language->get('column_order_weight');
		$data['column_order_comment'] = $this->language->get('column_order_comment');	

		$data['column_year'] = $this->language->get('column_year');		
		$data['column_quarter'] = $this->language->get('column_quarter');
		$data['column_month'] = $this->language->get('column_month');
		
		$data['column_gsales'] = $this->language->get('column_gsales');
		$data['column_gcosts'] = $this->language->get('column_gcosts');
		$data['column_gprofit'] = $this->language->get('column_gprofit');		

		$data['entry_order_created'] = $this->language->get('entry_order_created');
		$data['entry_added_to_cart'] = $this->language->get('entry_added_to_cart');
		$data['entry_added_to_wishlist'] = $this->language->get('entry_added_to_wishlist');
		$data['entry_order_abandoned'] = $this->language->get('entry_order_abandoned');
		$data['entry_status_changed'] = $this->language->get('entry_status_changed');	
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_range'] = $this->language->get('entry_range');	
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_order_id'] = $this->language->get('entry_order_id');
		$data['entry_order_id_from'] = $this->language->get('entry_order_id_from');
		$data['entry_order_id_to'] = $this->language->get('entry_order_id_to');	
		$data['entry_price_value'] = $this->language->get('entry_price_value');
		$data['entry_price_value_min'] = $this->language->get('entry_price_value_min');
		$data['entry_price_value_max'] = $this->language->get('entry_price_value_max');			
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_currency'] = $this->language->get('entry_currency');	
		$data['entry_tax'] = $this->language->get('entry_tax');
		$data['entry_tax_classes'] = $this->language->get('entry_tax_classes');		
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');		
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_company'] = $this->language->get('entry_company');
		$data['entry_customer_name'] = $this->language->get('entry_customer_name');		
		$data['entry_customer_email'] = $this->language->get('entry_customer_email'); 
		$data['entry_customer_telephone'] = $this->language->get('entry_customer_telephone');
		$data['entry_ip'] = $this->language->get('entry_ip');
		$data['entry_payment_company'] = $this->language->get('entry_payment_company');
		$data['entry_payment_address'] = $this->language->get('entry_payment_address');
		$data['entry_payment_city'] = $this->language->get('entry_payment_city');
		$data['entry_payment_zone'] = $this->language->get('entry_payment_zone');		
		$data['entry_payment_postcode'] = $this->language->get('entry_payment_postcode');
		$data['entry_payment_country'] = $this->language->get('entry_payment_country');		
		$data['entry_payment_method'] = $this->language->get('entry_payment_method');
		$data['entry_shipping_company'] = $this->language->get('entry_shipping_company');
		$data['entry_shipping_address'] = $this->language->get('entry_shipping_address');
		$data['entry_shipping_city'] = $this->language->get('entry_shipping_city');
		$data['entry_shipping_zone'] = $this->language->get('entry_shipping_zone');		
		$data['entry_shipping_postcode'] = $this->language->get('entry_shipping_postcode');
		$data['entry_shipping_country'] = $this->language->get('entry_shipping_country');
		$data['entry_shipping_method'] = $this->language->get('entry_shipping_method');		
		$data['entry_category'] = $this->language->get('entry_category'); 
		$data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
		$data['entry_supplier'] = $this->language->get('entry_supplier');
		$data['entry_sku'] = $this->language->get('entry_sku');
		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_model'] = $this->language->get('entry_model');
		$data['entry_option'] = $this->language->get('entry_option');
		$data['entry_attributes'] = $this->language->get('entry_attributes');
		$data['entry_product_status'] = $this->language->get('entry_product_status');			
		$data['entry_location'] = $this->language->get('entry_location');
		$data['entry_affiliate_name'] = $this->language->get('entry_affiliate_name');
		$data['entry_affiliate_email'] = $this->language->get('entry_affiliate_email');
		$data['entry_coupon_name'] = $this->language->get('entry_coupon_name');
		$data['entry_coupon_code'] = $this->language->get('entry_coupon_code');
		$data['entry_voucher_code'] = $this->language->get('entry_voucher_code');	
		
		$data['entry_report'] = $this->language->get('entry_report');
		$data['entry_group'] = $this->language->get('entry_group');		
		$data['entry_sort_by'] = $this->language->get('entry_sort_by');
		$data['entry_show_details'] = $this->language->get('entry_show_details');	
		$data['entry_limit'] = $this->language->get('entry_limit');
		
		$data['entry_title'] = $this->language->get('entry_title');	
		$data['entry_link'] = $this->language->get('entry_link');			

		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_clear'] = $this->language->get('button_clear');
		$data['button_load_save'] = $this->language->get('button_load_save');
		$data['button_load'] = $this->language->get('button_load');
		$data['button_add_report'] = $this->language->get('button_add_report');		
		$data['button_remove'] = $this->language->get('button_remove');
		$data['button_filters'] = $this->language->get('button_filters');
		$data['button_chart'] = $this->language->get('button_chart');		
		$data['button_export'] = $this->language->get('button_export');
		$data['button_settings'] = $this->language->get('button_settings');
		$data['button_documentation'] = $this->language->get('button_documentation');
		$data['button_close'] = $this->language->get('button_close');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_expand'] = $this->language->get('button_expand');
		$data['button_collapse'] = $this->language->get('button_collapse');		
		
		$data['heading_title'] = $this->language->get('heading_title');
		$data['heading_version'] = $this->language->get('heading_version');	
		
		$data['token'] = $this->session->data['token'];

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_range'])) {
			$url .= '&filter_range=' . $this->request->get['filter_range'];
		}

		if (isset($this->request->get['filter_report'])) {
			$url .= '&filter_report=' . $this->request->get['filter_report'];
		}

		if (isset($this->request->get['filter_group'])) {
			$url .= '&filter_group=' . $this->request->get['filter_group'];
		}
		
		if (isset($this->request->get['filter_sort'])) {
			$url .= '&filter_sort=' . $this->request->get['filter_sort'];
		}

		if (isset($this->request->get['filter_order'])) {
			$url .= '&filter_order=' . $this->request->get['filter_order'];
		}
		
		if (isset($this->request->get['filter_details'])) {
			$url .= '&filter_details=' . $this->request->get['filter_details'];
		}
		
		if (isset($this->request->get['filter_limit'])) {
			$url .= '&filter_limit=' . $this->request->get['filter_limit'];
		}

		if (isset($this->request->get['filter_status_date_start'])) {
			$url .= '&filter_status_date_start=' . $this->request->get['filter_status_date_start'];
		}
		
		if (isset($this->request->get['filter_status_date_end'])) {
			$url .= '&filter_status_date_end=' . $this->request->get['filter_status_date_end'];
		}
		
		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
		
		if (isset($this->request->get['filter_order_id_from'])) {
			$url .= '&filter_order_id_from=' . $this->request->get['filter_order_id_from'];
		}
		
		if (isset($this->request->get['filter_order_id_to'])) {
			$url .= '&filter_order_id_to=' . $this->request->get['filter_order_id_to'];
		}

		if (isset($this->request->get['filter_prod_price_min'])) {
			$url .= '&filter_prod_price_min=' . $this->request->get['filter_prod_price_min'];
		}
		
		if (isset($this->request->get['filter_prod_price_max'])) {
			$url .= '&filter_prod_price_max=' . $this->request->get['filter_prod_price_max'];
		}
		
		if (isset($this->request->get['filter_store_id'])) {
			$url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
		}
		
		if (isset($this->request->get['filter_currency'])) {
			$url .= '&filter_currency=' . $this->request->get['filter_currency'];
		}
		
		if (isset($this->request->get['filter_taxes'])) {
			$url .= '&filter_taxes=' . $this->request->get['filter_taxes'];
		}
		
		if (isset($this->request->get['filter_tax_classes'])) {
			$url .= '&filter_tax_classes=' . $this->request->get['filter_tax_classes'];
		}
		
		if (isset($this->request->get['filter_geo_zones'])) {
			$url .= '&filter_geo_zones=' . $this->request->get['filter_geo_zones'];
		}
		
		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}
		
		if (isset($this->request->get['filter_customer_name'])) {
			$url .= '&filter_customer_name=' . urlencode(html_entity_decode($this->request->get['filter_customer_name'], ENT_QUOTES, 'UTF-8'));
		}		

		if (isset($this->request->get['filter_customer_email'])) {
			$url .= '&filter_customer_email=' . urlencode(html_entity_decode($this->request->get['filter_customer_email'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_customer_telephone'])) {
			$url .= '&filter_customer_telephone=' . urlencode(html_entity_decode($this->request->get['filter_customer_telephone'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . urlencode(html_entity_decode($this->request->get['filter_ip'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_payment_company'])) {
			$url .= '&filter_payment_company=' . urlencode(html_entity_decode($this->request->get['filter_payment_company'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_payment_address'])) {
			$url .= '&filter_payment_address=' . urlencode(html_entity_decode($this->request->get['filter_payment_address'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_payment_city'])) {
			$url .= '&filter_payment_city=' . urlencode(html_entity_decode($this->request->get['filter_payment_city'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_payment_zone'])) {
			$url .= '&filter_payment_zone=' . urlencode(html_entity_decode($this->request->get['filter_payment_zone'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_payment_postcode'])) {
			$url .= '&filter_payment_postcode=' . urlencode(html_entity_decode($this->request->get['filter_payment_postcode'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_payment_country'])) {
			$url .= '&filter_payment_country=' . urlencode(html_entity_decode($this->request->get['filter_payment_country'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_payment_method'])) {
			$url .= '&filter_payment_method=' . $this->request->get['filter_payment_method'];
		}	
		
		if (isset($this->request->get['filter_shipping_company'])) {
			$url .= '&filter_shipping_company=' . urlencode(html_entity_decode($this->request->get['filter_shipping_company'], ENT_QUOTES, 'UTF-8'));
		}	

		if (isset($this->request->get['filter_shipping_address'])) {
			$url .= '&filter_shipping_address=' . urlencode(html_entity_decode($this->request->get['filter_shipping_address'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_shipping_city'])) {
			$url .= '&filter_shipping_city=' . urlencode(html_entity_decode($this->request->get['filter_shipping_city'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_shipping_zone'])) {
			$url .= '&filter_shipping_zone=' . urlencode(html_entity_decode($this->request->get['filter_shipping_zone'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_shipping_postcode'])) {
			$url .= '&filter_shipping_postcode=' . urlencode(html_entity_decode($this->request->get['filter_shipping_postcode'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_shipping_country'])) {
			$url .= '&filter_shipping_country=' . urlencode(html_entity_decode($this->request->get['filter_shipping_country'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
		}	
		
		if (isset($this->request->get['filter_category'])) {
			$url .= '&filter_category=' . $this->request->get['filter_category'];
		}	
		
		if (isset($this->request->get['filter_manufacturer'])) {
			$url .= '&filter_manufacturer=' . $this->request->get['filter_manufacturer'];
		}	

		if (isset($this->request->get['filter_supplier'])) {
			$url .= '&filter_supplier=' . $this->request->get['filter_supplier'];
		}
		
		if (isset($this->request->get['filter_sku'])) {
			$url .= '&filter_sku=' . urlencode(html_entity_decode($this->request->get['filter_sku'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_product_name'])) {
			$url .= '&filter_product_name=' . urlencode(html_entity_decode($this->request->get['filter_product_name'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_option'])) {
			$url .= '&filter_option=' . $this->request->get['filter_option'];
		}	
		
		if (isset($this->request->get['filter_attribute'])) {
			$url .= '&filter_attribute=' . $this->request->get['filter_attribute'];
		}			

		if (isset($this->request->get['filter_product_status'])) {
			$url .= '&filter_product_status=' . $this->request->get['filter_product_status'];
		}
		
		if (isset($this->request->get['filter_location'])) {
			$url .= '&filter_location=' . $this->request->get['filter_location'];
		}	
		
		if (isset($this->request->get['filter_affiliate_name'])) {
			$url .= '&filter_affiliate_name=' . $this->request->get['filter_affiliate_name'];
		}	
		
		if (isset($this->request->get['filter_affiliate_email'])) {
			$url .= '&filter_affiliate_email=' . $this->request->get['filter_affiliate_email'];
		}	
		
		if (isset($this->request->get['filter_coupon_name'])) {
			$url .= '&filter_coupon_name=' . $this->request->get['filter_coupon_name'];
		}	
		
		if (isset($this->request->get['filter_coupon_code'])) {
			$url .= '&filter_coupon_code=' . urlencode(html_entity_decode($this->request->get['filter_coupon_code'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_voucher_code'])) {
			$url .= '&filter_voucher_code=' . urlencode(html_entity_decode($this->request->get['filter_voucher_code'], ENT_QUOTES, 'UTF-8'));
		}

		if (!isset($_GET['cron'])) {
			unset($this->session->data['products_data']);
			$total > 0 ? $this->session->data['products_data'] = $data['products'] = $products : '';
		}

		$data['user'] = $this->user->getId();
		
		$data['report_types'][] = array(
			'name'			=> $this->language->get('text_export_no_details'),
			'type'			=> 'export_no_details',
			'icon'			=> 'fa fa-list-alt'
		);
		$data['report_types'][] = array(
			'name'			=> $this->language->get('text_export_basic_details'),
			'type'			=> 'export_basic_details',
			'icon'			=> 'fa fa-list-alt'
		);
		$data['report_types'][] = array(
			'name'			=> $this->language->get('text_export_all_details')." ".$this->language->get('text_all_details_products'),
			'type' 			=> 'export_all_details_products',
			'icon'			=> 'fa fa-list-alt'
		);
		$data['report_types'][] = array(
			'name'			=> $this->language->get('text_export_all_details')." ".$this->language->get('text_all_details_orders'),
			'type' 			=> 'export_all_details_orders',
			'icon'			=> 'fa fa-list-alt'
		);			

		if (isset($this->session->data['report_type'])) {
			$data['report_type'] = $this->session->data['report_type'];
		} else {
			$data['report_type'] = 'export_no_details';
		}

		$data['export_types'][] = array(
			'name'			=> $this->language->get('text_export_xlsx'),
			'type'			=> 'export_xlsx',
			'icon'			=> 'icon-green fa fa-file-excel-o'
		);
		$data['export_types'][] = array(
			'name'			=> $this->language->get('text_export_xls'),
			'type'			=> 'export_xls',
			'icon'			=> 'icon-green fa fa-file-excel-o'
		);
		$data['export_types'][] = array(
			'name'			=> $this->language->get('text_export_csv'),
			'type'			=> 'export_csv',
			'icon'			=> 'icon-dark-green fa fa-file-text-o'
		);		
		$data['export_types'][] = array(
			'name'			=> $this->language->get('text_export_pdf'),
			'type'			=> 'export_pdf',
			'icon'			=> 'icon-red fa fa-file-pdf-o'
		);
		$data['export_types'][] = array(
			'name'			=> $this->language->get('text_export_html'),
			'type'			=> 'export_html',
			'icon'			=> 'icon-black fa fa-file-text-o'
		);
		
		if (isset($this->session->data['export_type'])) {
			$data['export_type'] = $this->session->data['export_type'];
		} else {
			$data['export_type'] = '';
		}
		
		if (isset($this->session->data['export_logo_criteria'])) {
			$data['export_logo_criteria'] = $this->session->data['export_logo_criteria'];
		} else {
			$data['export_logo_criteria'] = 0;
		}

		$data['export_csv_delimiters'][] = array(
			'name'			=> $this->language->get('text_csv_delimiter_comma'),
			'type'			=> 'comma',
		);
		$data['export_csv_delimiters'][] = array(
			'name'			=> $this->language->get('text_csv_delimiter_semi'),
			'type'			=> 'semi',
		);
		$data['export_csv_delimiters'][] = array(
			'name'			=> $this->language->get('text_csv_delimiter_tab'),
			'type'			=> 'tab',
		);
		
		if (isset($this->session->data['export_csv_delimiter'])) {
			$data['export_csv_delimiter'] = $this->session->data['export_csv_delimiter'];
		} else {
			$data['export_csv_delimiter'] = 'comma';
		}			

		if (isset($_GET['cron'])) {
			$cron_settings = unserialize($this->config->get('advppp' . $this->user->getId() . 'cron_setting'));
			foreach ($cron_settings as $cron_setting) {
				if (isset($_GET['cron_id']) && ($_GET['cron_id'] == $cron_setting['cron_id'])) {
					$data['report_type'] = $cron_setting['cron_report_type'];
					$data['export_type'] = $cron_setting['cron_export_type'];
					$data['export_logo_criteria'] = $cron_setting['cron_export_logo_criteria'];
					$data['export_csv_delimiter'] = $cron_setting['cron_export_csv_delimiter'];
				}
			}
		}
		
		$data['cron_report_type'] = 'export_no_details';
		$data['cron_export_type'] = '';
		$data['cron_export_logo_criteria'] = 0;
		$data['cron_export_csv_delimiter'] = 'comma';
		$data['cron_export_file'] = 'send_to_email';
		$data['cron_file_save_path'] = 'report';
		$data['cron_file_name'] = '';
		$data['cron_email'] = '';
		$data['cron_user_id'] = $this->user->getId();
		$data['root_dir'] = dirname(DIR_APPLICATION) . '/';
		$data['dir_sep'] = '/';
		
		$data['filters'] = array(
			'store'						=> substr($this->language->get('entry_store'),0,-1),			
			'currency'					=> substr($this->language->get('entry_currency'),0,-1),			
			'tax'						=> substr($this->language->get('entry_tax'),0,-1),			
			'tax_class'					=> substr($this->language->get('entry_tax_classes'),0,-1),
			'geo_zone'					=> substr($this->language->get('entry_geo_zone'),0,-1),
			'customer_group'			=> substr($this->language->get('entry_customer_group'),0,-1),
			'customer_name'				=> substr($this->language->get('entry_customer_name'),0,-1),
			'customer_email'			=> substr($this->language->get('entry_customer_email'),0,-1),
			'customer_telephone'		=> substr($this->language->get('entry_customer_telephone'),0,-1),
			'ip'						=> substr($this->language->get('entry_ip'),0,-1),
			'payment_company'			=> substr($this->language->get('entry_payment_company'),0,-1),			
			'payment_address'			=> substr($this->language->get('entry_payment_address'),0,-1),	
			'payment_city'				=> substr($this->language->get('entry_payment_city'),0,-1),	
			'payment_zone'				=> substr($this->language->get('entry_payment_zone'),0,-1),	
			'payment_postcode'			=> substr($this->language->get('entry_payment_postcode'),0,-1),	
			'payment_country'			=> substr($this->language->get('entry_payment_country'),0,-1),
			'payment_method'			=> substr($this->language->get('entry_payment_method'),0,-1),
			'shipping_company'			=> substr($this->language->get('entry_shipping_company'),0,-1),
			'shipping_address'			=> substr($this->language->get('entry_shipping_address'),0,-1),
			'shipping_city'				=> substr($this->language->get('entry_shipping_city'),0,-1),
			'shipping_zone'				=> substr($this->language->get('entry_shipping_zone'),0,-1),
			'shipping_postcode'			=> substr($this->language->get('entry_shipping_postcode'),0,-1),
			'shipping_country'			=> substr($this->language->get('entry_shipping_country'),0,-1),
			'shipping_method'			=> substr($this->language->get('entry_shipping_method'),0,-1),
			'category'					=> substr($this->language->get('entry_category'),0,-1),
			'manufacturer'				=> substr($this->language->get('entry_manufacturer'),0,-1),
			'supplier'					=> substr($this->language->get('entry_supplier'),0,-1),
			'product'					=> substr($this->language->get('entry_product'),0,-1),
			'sku'						=> substr($this->language->get('entry_sku'),0,-1),			
			'model'						=> substr($this->language->get('entry_model'),0,-1),
			'option'					=> substr($this->language->get('entry_option'),0,-1),
			'attribute'					=> substr($this->language->get('entry_attributes'),0,-1),
			'product_status'			=> substr($this->language->get('entry_product_status'),0,-1),
			'location'					=> substr($this->language->get('entry_location'),0,-1),
			'affiliate_name'			=> substr($this->language->get('entry_affiliate_name'),0,-1),
			'affiliate_email'			=> substr($this->language->get('entry_affiliate_email'),0,-1),
			'coupon_name'				=> substr($this->language->get('entry_coupon_name'),0,-1),
			'coupon_code'				=> substr($this->language->get('entry_coupon_code'),0,-1),
			'voucher_code'				=> substr($this->language->get('entry_voucher_code'),0,-1)
		);

		$data['filters_scw_pnp_alp'] = array(
			'store'						=> substr($this->language->get('entry_store'),0,-1),			
			'tax_class'					=> substr($this->language->get('entry_tax_classes'),0,-1),
			'category'					=> substr($this->language->get('entry_category'),0,-1),
			'manufacturer'				=> substr($this->language->get('entry_manufacturer'),0,-1),
			'supplier'					=> substr($this->language->get('entry_supplier'),0,-1),
			'sku'						=> substr($this->language->get('entry_sku'),0,-1),
			'product'					=> substr($this->language->get('entry_product'),0,-1),
			'model'						=> substr($this->language->get('entry_model'),0,-1),
			'attribute'					=> substr($this->language->get('entry_attributes'),0,-1),
			'product_status'			=> substr($this->language->get('entry_product_status'),0,-1),
			'location'					=> substr($this->language->get('entry_location'),0,-1)
		);

		$data['filters_fill'] = array(
			'currency'					=> substr($this->language->get('entry_currency'),0,-1),			
			'tax'						=> substr($this->language->get('entry_tax'),0,-1),			
			'geo_zone'					=> substr($this->language->get('entry_geo_zone'),0,-1),
			'customer_group'			=> substr($this->language->get('entry_customer_group'),0,-1),
			'customer_name'				=> substr($this->language->get('entry_customer_name'),0,-1),
			'customer_email'			=> substr($this->language->get('entry_customer_email'),0,-1),
			'customer_telephone'		=> substr($this->language->get('entry_customer_telephone'),0,-1),
			'ip'						=> substr($this->language->get('entry_ip'),0,-1),
			'payment_company'			=> substr($this->language->get('entry_payment_company'),0,-1),			
			'payment_address'			=> substr($this->language->get('entry_payment_address'),0,-1),	
			'payment_city'				=> substr($this->language->get('entry_payment_city'),0,-1),	
			'payment_zone'				=> substr($this->language->get('entry_payment_zone'),0,-1),	
			'payment_postcode'			=> substr($this->language->get('entry_payment_postcode'),0,-1),	
			'payment_country'			=> substr($this->language->get('entry_payment_country'),0,-1),
			'payment_method'			=> substr($this->language->get('entry_payment_method'),0,-1),
			'shipping_company'			=> substr($this->language->get('entry_shipping_company'),0,-1),
			'shipping_address'			=> substr($this->language->get('entry_shipping_address'),0,-1),
			'shipping_city'				=> substr($this->language->get('entry_shipping_city'),0,-1),
			'shipping_zone'				=> substr($this->language->get('entry_shipping_zone'),0,-1),
			'shipping_postcode'			=> substr($this->language->get('entry_shipping_postcode'),0,-1),
			'shipping_country'			=> substr($this->language->get('entry_shipping_country'),0,-1),
			'shipping_method'			=> substr($this->language->get('entry_shipping_method'),0,-1),									  
			'option'					=> substr($this->language->get('entry_option'),0,-1),									  
			'affiliate_name'			=> substr($this->language->get('entry_affiliate_name'),0,-1),
			'affiliate_email'			=> substr($this->language->get('entry_affiliate_email'),0,-1),
			'coupon_name'				=> substr($this->language->get('entry_coupon_name'),0,-1),
			'coupon_code'				=> substr($this->language->get('entry_coupon_code'),0,-1),
			'voucher_code'				=> substr($this->language->get('entry_voucher_code'),0,-1)
		);
		
		if ($this->config->get('advppp' . $this->user->getId() . '_settings_filters')) {
			$data['advppp_settings_filters'] = $this->config->get('advppp' . $this->user->getId() . '_settings_filters');
		} else {
			$data['advppp_settings_filters'] = array_keys($data['filters']);
		}

		$data['scw_columns'] = array(
			'scw_id'					=> $this->language->get('column_id'),
			'scw_image'					=> $this->language->get('column_image'),
			'scw_sku'					=> $this->language->get('column_sku'),
			'scw_name'					=> $this->language->get('column_name'),
			'scw_model'					=> $this->language->get('column_model'),	
			'scw_category'				=> $this->language->get('column_category'),
			'scw_manufacturer'			=> $this->language->get('column_manufacturer'),
			'scw_supplier'				=> $this->language->get('column_supplier'),
			'scw_attribute'				=> $this->language->get('column_attribute'),
			'scw_status'				=> $this->language->get('column_status'),	
			'scw_location'				=> $this->language->get('column_location'),	
			'scw_tax_class'				=> $this->language->get('column_tax_class'),	
			'scw_price'					=> $this->language->get('column_price'),
			'scw_cost'					=> $this->language->get('column_cost'),	
			'scw_profit'				=> $this->language->get('column_profit'),
			'scw_profit_margin'			=> $this->language->get('column_profit_margin') . ' [%]',
			'scw_profit_markup'			=> $this->language->get('column_profit_markup') . ' [%]',	
			'scw_viewed'				=> $this->language->get('column_viewed'),
			'scw_stock_quantity'		=> $this->language->get('column_stock_quantity'),
			'scw_cart_quantity'			=> $this->language->get('column_cart_quantity'),
			'scw_wishlist_quantity'		=> $this->language->get('column_wishlist_quantity'),
			'scw_customer_id'			=> $this->language->get('column_customer_cust_id'),			
			'scw_date_added'			=> $this->language->get('column_customer_date_added'),
			'scw_customer_name'			=> $this->language->get('column_order_customer'),
			'scw_customer_email'		=> $this->language->get('column_order_email'),
			'scw_customer_telephone' 	=> $this->language->get('column_customer_telephone'),
			'scw_customer_group'		=> $this->language->get('column_order_customer_group'),
			'scw_billing_company'		=> $this->language->get('column_company'),
			'scw_billing_address_1'		=> $this->language->get('column_address_1'),
			'scw_billing_address_2'		=> $this->language->get('column_address_2'),
			'scw_billing_city'			=> $this->language->get('column_city'),
			'scw_billing_zone'			=> $this->language->get('column_region_state'),
			'scw_billing_postcode'		=> $this->language->get('column_postcode'),
			'scw_billing_country'		=> $this->language->get('column_country')
		);
				
		if ($this->config->get('advppp' . $this->user->getId() . '_settings_scw_columns')) {
			$data['advppp_settings_scw_columns'] = $this->config->get('advppp' . $this->user->getId() . '_settings_scw_columns');
		} else {
			$data['advppp_settings_scw_columns'] = array('scw_id','scw_image','scw_name','scw_model','scw_category','scw_manufacturer','scw_status','scw_stock_quantity','scw_cart_quantity','scw_wishlist_quantity','scw_date_added','scw_customer_id','scw_customer_name','scw_customer_email','scw_customer_telephone','scw_customer_group','scw_billing_company','scw_billing_address_1','scw_billing_address_2','scw_billing_city','scw_billing_zone','scw_billing_postcode','scw_billing_country');
		}	

		$data['pnp_columns'] = array(
			'pnp_id'					=> $this->language->get('column_id'),
			'pnp_image'					=> $this->language->get('column_image'),
			'pnp_sku'					=> $this->language->get('column_sku'),
			'pnp_name'					=> $this->language->get('column_name'),
			'pnp_model'					=> $this->language->get('column_model'),	
			'pnp_category'				=> $this->language->get('column_category'),
			'pnp_manufacturer'			=> $this->language->get('column_manufacturer'),
			'pnp_supplier'				=> $this->language->get('column_supplier'),
			'pnp_attribute'				=> $this->language->get('column_attribute'),
			'pnp_status'				=> $this->language->get('column_status'),	
			'pnp_location'				=> $this->language->get('column_location'),	
			'pnp_tax_class'				=> $this->language->get('column_tax_class'),	
			'pnp_price'					=> $this->language->get('column_price'),
			'pnp_cost'					=> $this->language->get('column_cost'),	
			'pnp_profit'				=> $this->language->get('column_profit'),
			'pnp_profit_margin'			=> $this->language->get('column_profit_margin') . ' [%]',
			'pnp_profit_markup'			=> $this->language->get('column_profit_markup') . ' [%]',			
			'pnp_viewed'				=> $this->language->get('column_viewed'),
			'pnp_stock_quantity'		=> $this->language->get('column_stock_quantity')
		);
				
		if ($this->config->get('advppp' . $this->user->getId() . '_settings_pnp_columns')) {
			$data['advppp_settings_pnp_columns'] = $this->config->get('advppp' . $this->user->getId() . '_settings_pnp_columns');
		} else {
			$data['advppp_settings_pnp_columns'] = array('pnp_id','pnp_image','pnp_name','pnp_model','pnp_category','pnp_manufacturer','pnp_status','pnp_stock_quantity');
		}

		$data['cm_columns'] = array(
			'cm_category'				=> $this->language->get('column_category'),
			'cm_manufacturer'			=> $this->language->get('column_manufacturer'),
			'cm_supplier'				=> $this->language->get('column_supplier'),
			'cm_sold_quantity'			=> $this->language->get('column_sold_quantity'),
			'cm_sold_percent'			=> $this->language->get('column_sold_percent'),		
			'cm_total_excl_vat'			=> $this->language->get('column_total_excl_vat'),
			'cm_total_tax'				=> $this->language->get('column_total_tax'),
			'cm_total_incl_vat'			=> $this->language->get('column_total_incl_vat'),
			'cm_app'					=> $this->language->get('column_app'),
			'cm_discount'				=> $this->language->get('column_product_discount'),
			'cm_refunds'				=> $this->language->get('column_product_refunds'),
			'cm_reward_points'			=> $this->language->get('column_product_reward_points'),
			'cm_total_sales'			=> $this->language->get('column_total_sales'),
			'cm_total_costs'			=> $this->language->get('column_total_costs'),
			'cm_total_profit'			=> $this->language->get('column_total_profit'),
			'cm_total_profit_margin'	=> $this->language->get('column_total_profit_margin'),
			'cm_total_profit_markup'	=> $this->language->get('column_total_profit_markup')	
		);
				
		if ($this->config->get('advppp' . $this->user->getId() . '_settings_cm_columns')) {
			$data['advppp_settings_cm_columns'] = $this->config->get('advppp' . $this->user->getId() . '_settings_cm_columns');
		} else {
			$data['advppp_settings_cm_columns'] = array('cm_category','cm_manufacturer','cm_supplier','cm_sold_quantity','cm_sold_percent','cm_cart_quantity','cm_wishlist_quantity','cm_total_excl_vat','cm_total_tax','cm_total_incl_vat','cm_app','cm_refunds','cm_total_sales','cm_total_costs','cm_total_profit','cm_total_profit_margin','cm_total_profit_markup');
		}
		
		$data['mv_columns'] = array(
			'mv_id'						=> $this->language->get('column_id'),
			'mv_image'					=> $this->language->get('column_image'),
			'mv_sku'					=> $this->language->get('column_sku'),
			'mv_name'					=> $this->language->get('column_name'),
			'mv_model'					=> $this->language->get('column_model'),	
			'mv_category'				=> $this->language->get('column_category'),
			'mv_manufacturer'			=> $this->language->get('column_manufacturer'),
			'mv_supplier'				=> $this->language->get('column_supplier'),
			'mv_attribute'				=> $this->language->get('column_attribute'),
			'mv_status'					=> $this->language->get('column_status'),	
			'mv_location'				=> $this->language->get('column_location'),	
			'mv_tax_class'				=> $this->language->get('column_tax_class'),	
			'mv_price'					=> $this->language->get('column_price'),	
			'mv_cost'					=> $this->language->get('column_cost'),	
			'mv_profit'					=> $this->language->get('column_profit'),
			'mv_profit_margin'			=> $this->language->get('column_profit_margin'),
			'mv_profit_markup'			=> $this->language->get('column_profit_markup'),
			'mv_viewed'					=> $this->language->get('column_viewed'),
			'mv_stock_quantity'			=> $this->language->get('column_stock_quantity'),
			'mv_sold_quantity'			=> $this->language->get('column_sold_quantity'),
			'mv_sold_percent'			=> $this->language->get('column_sold_percent'),
			'mv_total_excl_vat'			=> $this->language->get('column_total_excl_vat'),
			'mv_total_tax'				=> $this->language->get('column_total_tax'),
			'mv_total_incl_vat'			=> $this->language->get('column_total_incl_vat'),
			'mv_app'					=> $this->language->get('column_app'),
			'mv_discount'				=> $this->language->get('column_product_discount'),
			'mv_refunds'				=> $this->language->get('column_product_refunds'),
			'mv_reward_points'			=> $this->language->get('column_product_reward_points'),			
			'mv_total_sales'			=> $this->language->get('column_total_sales'),
			'mv_total_costs'			=> $this->language->get('column_total_costs'),
			'mv_total_profit'			=> $this->language->get('column_total_profit'),
			'mv_total_profit_margin'	=> $this->language->get('column_total_profit_margin'),
			'mv_total_profit_markup'	=> $this->language->get('column_total_profit_markup')
		);
				
		if ($this->config->get('advppp' . $this->user->getId() . '_settings_mv_columns')) {
			$data['advppp_settings_mv_columns'] = $this->config->get('advppp' . $this->user->getId() . '_settings_mv_columns');
		} else {
			$data['advppp_settings_mv_columns'] = array('mv_id','mv_image','mv_name','mv_model','mv_category','mv_manufacturer','mv_status','mv_stock_quantity','mv_sold_quantity','mv_sold_percent','mv_total_excl_vat','mv_total_tax','mv_total_incl_vat','mv_app','mv_refunds','mv_total_sales','mv_total_costs','mv_total_profit','mv_total_profit_margin','mv_total_profit_markup');
		}	
		
		$data['ol_columns'] = array(
			'ol_order_order_id'			=> $this->language->get('column_order_order_id'),			
			'ol_order_date_added'		=> $this->language->get('column_order_date_added'),			
			'ol_order_inv_no'			=> $this->language->get('column_order_inv_no'),			
			'ol_order_customer'			=> $this->language->get('column_order_customer'),
			'ol_order_email'			=> $this->language->get('column_order_email'),
			'ol_order_customer_group'	=> $this->language->get('column_order_customer_group'),
			'ol_order_shipping_method'	=> $this->language->get('column_order_shipping_method'),
			'ol_order_payment_method'	=> $this->language->get('column_order_payment_method'),
			'ol_order_status'			=> $this->language->get('column_order_status'),
			'ol_order_store'			=> $this->language->get('column_order_store'),
			'ol_order_currency'			=> $this->language->get('column_order_currency'),
			'ol_prod_price'				=> $this->language->get('column_prod_price'),
			'ol_prod_quantity'			=> $this->language->get('column_prod_quantity'),	
			'ol_prod_total_excl_vat'	=> $this->language->get('column_prod_total_excl_vat'),	
			'ol_prod_tax'				=> $this->language->get('column_prod_tax'),	
			'ol_prod_total_incl_vat'	=> $this->language->get('column_prod_total_incl_vat'),
			'ol_prod_sales'				=> $this->language->get('column_prod_sales'),
			'ol_prod_cost'				=> $this->language->get('column_prod_cost'),
			'ol_prod_profit'			=> $this->language->get('column_prod_profit'),
			'ol_prod_profit_margin'		=> $this->language->get('column_prod_profit_margin'),
			'ol_prod_profit_markup'		=> $this->language->get('column_prod_profit_markup')
		);

		if ($this->config->get('advppp' . $this->user->getId() . '_settings_ol_columns')) {
			$data['advppp_settings_ol_columns'] = $this->config->get('advppp' . $this->user->getId() . '_settings_ol_columns');
		} else {
			$data['advppp_settings_ol_columns'] = array_keys($data['ol_columns']);
		}
		
		$data['pl_columns'] = array(
			'pl_prod_order_id'			=> $this->language->get('column_prod_order_id'),			
			'pl_prod_date_added'		=> $this->language->get('column_prod_date_added'),										
			'pl_prod_inv_no'			=> $this->language->get('column_prod_inv_no'),
			'pl_prod_id'				=> $this->language->get('column_prod_id'),
			'pl_prod_sku'				=> $this->language->get('column_prod_sku'),
			'pl_prod_model'				=> $this->language->get('column_prod_model'),
			'pl_prod_name'				=> $this->language->get('column_prod_name'),
			'pl_prod_option'			=> $this->language->get('column_prod_option'),
			'pl_prod_attributes'		=> $this->language->get('column_prod_attributes'),
			'pl_prod_category'			=> $this->language->get('column_prod_category'),				
			'pl_prod_manu'				=> $this->language->get('column_prod_manu'),
			'pl_prod_supplier'			=> $this->language->get('column_prod_supplier'),	
			'pl_prod_currency'			=> $this->language->get('column_prod_currency'),	
			'pl_prod_price'				=> $this->language->get('column_prod_price'),	
			'pl_prod_quantity'			=> $this->language->get('column_prod_quantity'),	
			'pl_prod_total_excl_vat'	=> $this->language->get('column_prod_total_excl_vat'),	
			'pl_prod_tax'				=> $this->language->get('column_prod_tax'),	
			'pl_prod_total_incl_vat'	=> $this->language->get('column_prod_total_incl_vat'),
			'pl_prod_sales'				=> $this->language->get('column_prod_sales'),
			'pl_prod_cost'				=> $this->language->get('column_prod_cost'),
			'pl_prod_profit'			=> $this->language->get('column_prod_profit'),
			'pl_prod_profit_margin'		=> $this->language->get('column_prod_profit_margin'),
			'pl_prod_profit_markup'		=> $this->language->get('column_prod_profit_markup')
		);

		if ($this->config->get('advppp' . $this->user->getId() . '_settings_pl_columns')) {
			$data['advppp_settings_pl_columns'] = $this->config->get('advppp' . $this->user->getId() . '_settings_pl_columns');
		} else {
			$data['advppp_settings_pl_columns'] = array('pl_prod_order_id','pl_prod_date_added','pl_prod_inv_no','pl_prod_id','pl_prod_sku','pl_prod_model','pl_prod_name','pl_prod_option','pl_prod_currency','pl_prod_price','pl_prod_quantity','pl_prod_total_excl_vat','pl_prod_tax','pl_prod_total_incl_vat','pl_prod_sales','pl_prod_cost','pl_prod_profit','pl_prod_profit_margin','pl_prod_profit_markup');
		}
		
		$data['cl_columns'] = array(
			'cl_customer_order_id'		=> $this->language->get('column_customer_order_id'),			
			'cl_customer_date_added'	=> $this->language->get('column_customer_date_added'),										
			'cl_customer_cust_id'		=> $this->language->get('column_customer_cust_id'),
			'cl_billing_name'			=> strip_tags($this->language->get('column_billing_name')),
			'cl_billing_company'		=> strip_tags($this->language->get('column_billing_company')),
			'cl_billing_address_1'		=> strip_tags($this->language->get('column_billing_address_1')),
			'cl_billing_address_2'		=> strip_tags($this->language->get('column_billing_address_2')),
			'cl_billing_city'			=> strip_tags($this->language->get('column_billing_city')),
			'cl_billing_zone'			=> strip_tags($this->language->get('column_billing_zone')),
			'cl_billing_postcode'		=> strip_tags($this->language->get('column_billing_postcode')),			
			'cl_billing_country'		=> strip_tags($this->language->get('column_billing_country')),
			'cl_customer_telephone'		=> $this->language->get('column_customer_telephone'),
			'cl_shipping_name'			=> strip_tags($this->language->get('column_shipping_name')),	
			'cl_shipping_company'		=> strip_tags($this->language->get('column_shipping_company')),	
			'cl_shipping_address_1'		=> strip_tags($this->language->get('column_shipping_address_1')),	
			'cl_shipping_address_2'		=> strip_tags($this->language->get('column_shipping_address_2')),
			'cl_shipping_city'			=> strip_tags($this->language->get('column_shipping_city')),
			'cl_shipping_zone'			=> strip_tags($this->language->get('column_shipping_zone')),
			'cl_shipping_postcode'		=> strip_tags($this->language->get('column_shipping_postcode')),
			'cl_shipping_country'		=> strip_tags($this->language->get('column_shipping_country'))
		);

		if ($this->config->get('advppp' . $this->user->getId() . '_settings_cl_columns')) {
			$data['advppp_settings_cl_columns'] = $this->config->get('advppp' . $this->user->getId() . '_settings_cl_columns');
		} else {
			$data['advppp_settings_cl_columns'] = array('cl_customer_order_id','cl_customer_date_added','cl_customer_cust_id','cl_billing_name','cl_billing_company','cl_billing_address_1','cl_billing_city','cl_billing_zone','cl_billing_postcode','cl_billing_country','cl_customer_telephone','cl_shipping_name','cl_shipping_company','cl_shipping_address_1','cl_shipping_city','cl_shipping_zone','cl_shipping_postcode','cl_shipping_country');
		}
		
		$data['all_columns'] = array(
			'all_order_inv_no'			=> $this->language->get('column_order_inv_no'),			
			'all_order_customer_name'	=> $this->language->get('column_order_customer'),			
			'all_order_email'			=> $this->language->get('column_order_email'),			
			'all_order_customer_group'	=> $this->language->get('column_order_customer_group'),
			'all_prod_id'				=> $this->language->get('column_prod_id'),
			'all_prod_sku'				=> $this->language->get('column_prod_sku'),
			'all_prod_model'			=> $this->language->get('column_prod_model'),
			'all_prod_name'				=> $this->language->get('column_prod_name'),
			'all_prod_option'			=> $this->language->get('column_prod_option'),
			'all_prod_attributes'		=> $this->language->get('column_prod_attributes'),
			'all_prod_category'			=> $this->language->get('column_prod_category'),			
			'all_prod_manu'				=> $this->language->get('column_prod_manu'),
			'all_prod_supplier'			=> $this->language->get('column_prod_supplier'),
			'all_prod_currency'			=> $this->language->get('column_prod_currency'),
			'all_prod_price'			=> $this->language->get('column_prod_price'),
			'all_prod_quantity'			=> $this->language->get('column_prod_quantity'),
			'all_prod_total_excl_vat'	=> $this->language->get('column_prod_total_excl_vat'),
			'all_prod_tax'				=> $this->language->get('column_prod_tax'),
			'all_prod_total_incl_vat'	=> $this->language->get('column_prod_total_incl_vat'),
			'all_prod_discount'			=> $this->language->get('column_prod_discount'),
			'all_prod_qty_refund'		=> $this->language->get('column_prod_qty_refunded'),
			'all_prod_refund'			=> $this->language->get('column_prod_refunded'),
			'all_prod_reward_points'	=> $this->language->get('column_prod_reward_points'),
			'all_prod_sales'			=> $this->language->get('column_prod_sales'),
			'all_prod_cost'				=> $this->language->get('column_prod_cost'),
			'all_prod_profit'			=> $this->language->get('column_prod_profit'),
			'all_prod_profit_margin'	=> $this->language->get('column_prod_profit_margin'),
			'all_prod_profit_markup'	=> $this->language->get('column_prod_profit_markup'),
			'all_sub_total'				=> $this->language->get('column_sub_total'),
			'all_handling'				=> $this->language->get('column_handling'),
			'all_loworder'				=> $this->language->get('column_loworder'),
			'all_shipping'				=> $this->language->get('column_shipping'),
			'all_reward'				=> $this->language->get('column_reward'),
			'all_reward_points'			=> $this->language->get('column_reward_points'),			
			'all_coupon'				=> $this->language->get('column_coupon'),
			'all_coupon_name'			=> $this->language->get('column_coupon_name'),
			'all_coupon_code'			=> $this->language->get('column_coupon_code'),
			'all_order_tax'				=> $this->language->get('column_order_tax'),
			'all_credit'				=> $this->language->get('column_credit'),
			'all_voucher'				=> $this->language->get('column_voucher'),
			'all_voucher_code'			=> $this->language->get('column_voucher_code'),
			'all_discount'				=> $this->language->get('column_discount'),
			'all_order_value'			=> $this->language->get('column_order_value'),
			'all_refund'				=> $this->language->get('column_order_refund'),
			'all_order_sales'			=> $this->language->get('column_order_sales'),
			'all_order_prod_costs'		=> $this->language->get('column_order_prod_costs'),
			'all_order_commission'		=> $this->language->get('column_order_commission'),
			'all_order_payment_cost'	=> $this->language->get('column_order_payment_cost'),
			'all_order_shipping_cost'	=> $this->language->get('column_order_shipping_cost'),
			'all_order_extra_cost'		=> $this->language->get('column_order_extra_cost'),
			'all_order_return_cost'		=> $this->language->get('column_order_return_cost'),
			'all_shipping_balance'		=> $this->language->get('column_shipping_balance'),
			'all_order_costs'			=> $this->language->get('column_order_costs'),
			'all_order_profit'			=> $this->language->get('column_order_profit'),
			'all_order_profit_prc'		=> $this->language->get('column_order_margin'),
			'all_order_shipping_method'	=> $this->language->get('column_order_shipping_method'),
			'all_order_payment_method'	=> $this->language->get('column_order_payment_method'),
			'all_order_status'			=> $this->language->get('column_order_status'),
			'all_order_store'			=> $this->language->get('column_order_store'),
			'all_customer_cust_id'		=> $this->language->get('column_customer_cust_id'),
			'all_custom_fields'			=> $this->language->get('column_custom_fields'),
			'all_billing_first_name'	=> strip_tags($this->language->get('column_billing_first_name')),
			'all_billing_last_name'		=> strip_tags($this->language->get('column_billing_last_name')),
			'all_billing_company'		=> strip_tags($this->language->get('column_billing_company')),
			'all_billing_address_1'		=> strip_tags($this->language->get('column_billing_address_1')),
			'all_billing_address_2'		=> strip_tags($this->language->get('column_billing_address_2')),
			'all_billing_city'			=> strip_tags($this->language->get('column_billing_city')),
			'all_billing_zone'			=> strip_tags($this->language->get('column_billing_zone')),
			'all_billing_zone_id'		=> strip_tags($this->language->get('column_billing_zone_id')),
			'all_billing_zone_code'		=> strip_tags($this->language->get('column_billing_zone_code')),
			'all_billing_postcode'		=> strip_tags($this->language->get('column_billing_postcode')),			
			'all_billing_country'		=> strip_tags($this->language->get('column_billing_country')),
			'all_billing_country_id'	=> strip_tags($this->language->get('column_billing_country_id')),
			'all_billing_country_code'	=> strip_tags($this->language->get('column_billing_country_code')),
			'all_customer_telephone'	=> $this->language->get('column_customer_telephone'),
			'all_shipping_first_name'	=> strip_tags($this->language->get('column_shipping_first_name')),
			'all_shipping_last_name'	=> strip_tags($this->language->get('column_shipping_last_name')),
			'all_shipping_company'		=> strip_tags($this->language->get('column_shipping_company')),	
			'all_shipping_address_1'	=> strip_tags($this->language->get('column_shipping_address_1')),	
			'all_shipping_address_2'	=> strip_tags($this->language->get('column_shipping_address_2')),
			'all_shipping_city'			=> strip_tags($this->language->get('column_shipping_city')),
			'all_shipping_zone'			=> strip_tags($this->language->get('column_shipping_zone')),
			'all_shipping_zone_id'		=> strip_tags($this->language->get('column_shipping_zone_id')),
			'all_shipping_zone_code'	=> strip_tags($this->language->get('column_shipping_zone_code')),
			'all_shipping_postcode'		=> strip_tags($this->language->get('column_shipping_postcode')),
			'all_shipping_country'		=> strip_tags($this->language->get('column_shipping_country')),
			'all_shipping_country_id'	=> strip_tags($this->language->get('column_shipping_country_id')),
			'all_shipping_country_code'	=> strip_tags($this->language->get('column_shipping_country_code')),
			'all_order_weight'			=> $this->language->get('column_order_weight'),
			'all_order_comment'			=> $this->language->get('column_order_comment')
		);

		if ($this->config->get('advppp' . $this->user->getId() . '_settings_all_columns')) {
			$data['advppp_settings_all_columns'] = $this->config->get('advppp' . $this->user->getId() . '_settings_all_columns');
		} else {
			$data['advppp_settings_all_columns'] = array_keys($data['all_columns']);
		}

		$user = 'advppp' . $this->user->getId();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0' AND `code` = '" . $user . "'");
		$data['initialise'] = '';
		 if (!$query->rows) {
			$data['text_initialise_use'] = $this->language->get('text_initialise_use');			 
			$data['initialise'] = $query;
			$settings_data = array(
				'advppp' . $this->user->getId() . '_settings_filters' 		=> array_keys($data['filters']),
				'advppp' . $this->user->getId() . '_settings_scw_columns' 	=> array('scw_id','scw_image','scw_name','scw_model','scw_category','scw_manufacturer','scw_status','scw_stock_quantity','scw_cart_quantity','scw_wishlist_quantity','scw_date_added','scw_customer_id','scw_customer_name','scw_customer_email','scw_customer_telephone','scw_customer_group','scw_billing_company','scw_billing_address_1','scw_billing_address_2','scw_billing_city','scw_billing_zone','scw_billing_postcode','scw_billing_country'),
				'advppp' . $this->user->getId() . '_settings_pnp_columns' 	=> array('pnp_id','pnp_image','pnp_name','pnp_model','pnp_category','pnp_manufacturer','pnp_status','pnp_stock_quantity'),
				'advppp' . $this->user->getId() . '_settings_cm_columns' 	=> array('cm_category','cm_manufacturer','cm_supplier','cm_sold_quantity','cm_sold_percent','cm_cart_quantity','cm_wishlist_quantity','cm_total_excl_vat','cm_total_tax','cm_total_incl_vat','cm_app','cm_refunds','cm_total_sales','cm_total_costs','cm_total_profit','cm_total_profit_margin','cm_total_profit_markup'),								
				'advppp' . $this->user->getId() . '_settings_mv_columns' 	=> array('mv_id','mv_image','mv_name','mv_model','mv_category','mv_manufacturer','mv_status','mv_stock_quantity','mv_sold_quantity','mv_sold_percent','mv_total_excl_vat','mv_total_tax','mv_total_incl_vat','mv_app','mv_refunds','mv_total_sales','mv_total_costs','mv_total_profit','mv_total_profit_margin','mv_total_profit_markup'),	
				'advppp' . $this->user->getId() . '_settings_ol_columns' 	=> array_keys($data['ol_columns']),
				'advppp' . $this->user->getId() . '_settings_pl_columns' 	=> array('pl_prod_order_id','pl_prod_date_added','pl_prod_inv_no','pl_prod_id','pl_prod_sku','pl_prod_model','pl_prod_name','pl_prod_option','pl_prod_currency','pl_prod_price','pl_prod_quantity','pl_prod_total_excl_vat','pl_prod_tax','pl_prod_total_incl_vat','pl_prod_sales','pl_prod_cost','pl_prod_profit','pl_prod_profit_margin','pl_prod_profit_markup'),
				'advppp' . $this->user->getId() . '_settings_cl_columns' 	=> array('cl_customer_order_id','cl_customer_date_added','cl_customer_cust_id','cl_billing_name','cl_billing_company','cl_billing_address_1','cl_billing_city','cl_billing_zone','cl_billing_postcode','cl_billing_country','cl_customer_telephone','cl_shipping_name','cl_shipping_company','cl_shipping_address_1','cl_shipping_city','cl_shipping_zone','cl_shipping_postcode','cl_shipping_country'),
				'advppp' . $this->user->getId() . '_settings_all_columns' 	=> array_keys($data['all_columns'])
			);
			$this->load->model('setting/setting');
			$this->model_setting_setting->editSetting($user, $settings_data);
		}
		
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $filter_limit;
		$pagination->url = $this->url->link('report/adv_products_profit', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $filter_limit) + 1 : 0, ((($page - 1) * $filter_limit) > ($total - $filter_limit)) ? $total : ((($page - 1) * $filter_limit) + $filter_limit), $total, ceil($total / $filter_limit));

		$data['report_link'] = $this->url->link('report/adv_products_profit', 'token=' . $this->session->data['token'], 'SSL');
		$data['save_report_link'] = 'index.php?route=report/adv_products_profit'. $url;
		
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_range'] = $filter_range;
		$data['filter_report'] = $filter_report;
		$data['filter_group'] = $filter_group;		
		$data['filter_sort'] = $filter_sort;
		$data['filter_order'] = $filter_order;
		$data['filter_details'] = $filter_details;
		$data['filter_limit'] = $filter_limit;		
		$data['filter_status_date_start'] = $filter_status_date_start;
		$data['filter_status_date_end'] = $filter_status_date_end;
		$data['filter_order_status_id'] = $filter_order_status_id;		
		$data['filter_order_id_from'] = $filter_order_id_from;
		$data['filter_order_id_to'] = $filter_order_id_to;
		$data['filter_prod_price_min'] = $filter_prod_price_min;
		$data['filter_prod_price_max'] = $filter_prod_price_max;			
		$data['filter_store_id'] = $filter_store_id;
		$data['filter_currency'] = $filter_currency;
		$data['filter_taxes'] = $filter_taxes;
		$data['filter_tax_classes'] = $filter_tax_classes;		
		$data['filter_geo_zones'] = $filter_geo_zones;
		$data['filter_customer_group_id'] = $filter_customer_group_id;
		$data['filter_customer_name'] = $filter_customer_name; 
		$data['filter_customer_email'] = $filter_customer_email; 		
		$data['filter_customer_telephone'] = $filter_customer_telephone;
		$data['filter_ip'] = $filter_ip;
		$data['filter_payment_company'] = $filter_payment_company; 
		$data['filter_payment_address'] = $filter_payment_address; 
		$data['filter_payment_city'] = $filter_payment_city; 
		$data['filter_payment_postcode'] = $filter_payment_postcode; 
		$data['filter_payment_zone'] = $filter_payment_zone; 
		$data['filter_payment_country'] = $filter_payment_country; 
		$data['filter_payment_method'] = $filter_payment_method; 		
		$data['filter_shipping_company'] = $filter_shipping_company; 
		$data['filter_shipping_address'] = $filter_shipping_address; 
		$data['filter_shipping_city'] = $filter_shipping_city; 
		$data['filter_shipping_postcode'] = $filter_shipping_postcode; 
		$data['filter_shipping_zone'] = $filter_shipping_zone; 
		$data['filter_shipping_country'] = $filter_shipping_country; 
		$data['filter_shipping_method'] = $filter_shipping_method; 
		$data['filter_manufacturer'] = $filter_manufacturer;  
		$data['filter_category'] = $filter_category; 
		$data['filter_supplier'] = $filter_supplier;		
		$data['filter_sku'] = $filter_sku; 
		$data['filter_product_name'] = $filter_product_name; 
		$data['filter_model'] = $filter_model; 
		$data['filter_option'] = $filter_option;
		$data['filter_attribute'] = $filter_attribute;
		$data['filter_product_status'] = $filter_product_status;		
		$data['filter_location'] = $filter_location;
		$data['filter_affiliate_name'] = $filter_affiliate_name; 
		$data['filter_affiliate_email'] = $filter_affiliate_email; 
		$data['filter_coupon_name'] = $filter_coupon_name; 
		$data['filter_coupon_code'] = $filter_coupon_code; 
		$data['filter_voucher_code'] = $filter_voucher_code;
		
		$data['url'] = $this->url->link('report/adv_products_profit', 'token=' . $this->session->data['token'] . $url . '&page='. $page, 'SSL');
				
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		if (!isset($_GET['cron'])) {
			$this->response->setOutput($this->load->view('report/adv_products_profit.tpl', $data));
		} else {	
			$export_data = array(
				'results'		=> $total > 0 ? $data['products'] = $products : ''
			);

			$user_id = $_GET['user_id'];

			if ($this->config->get('advppp' . $user_id . '_settings_scw_columns')) {
				$advppp_settings_scw_columns = $this->config->get('advppp' . $user_id . '_settings_scw_columns');
			} else {
				$advppp_settings_scw_columns = array();
			}
			
			if ($this->config->get('advppp' . $user_id . '_settings_pnp_columns')) {
				$advppp_settings_pnp_columns = $this->config->get('advppp' . $user_id . '_settings_pnp_columns');
			} else {
				$advppp_settings_pnp_columns = array();
			}

			if ($this->config->get('advppp' . $user_id . '_settings_cm_columns')) {
				$advppp_settings_cm_columns = $this->config->get('advppp' . $user_id . '_settings_cm_columns');
			} else {
				$advppp_settings_cm_columns = array();
			}
			
			if ($this->config->get('advppp' . $user_id . '_settings_mv_columns')) {
				$advppp_settings_mv_columns = $this->config->get('advppp' . $user_id . '_settings_mv_columns');
			} else {
				$advppp_settings_mv_columns = array();
			}	

			if ($this->config->get('advppp' . $user_id . '_settings_ol_columns')) {
				$advppp_settings_ol_columns = $this->config->get('advppp' . $user_id . '_settings_ol_columns');
			} else {
				$advppp_settings_ol_columns = array();
			}
		
			if ($this->config->get('advppp' . $user_id . '_settings_pl_columns')) {
				$advppp_settings_pl_columns = $this->config->get('advppp' . $user_id . '_settings_pl_columns');
			} else {
				$advppp_settings_pl_columns = array();
			}
		
			if ($this->config->get('advppp' . $user_id . '_settings_cl_columns')) {
				$advppp_settings_cl_columns = $this->config->get('advppp' . $user_id . '_settings_cl_columns');
			} else {
				$advppp_settings_cl_columns = array();
			}
		
			if ($this->config->get('advppp' . $user_id . '_settings_all_columns')) {
				$advppp_settings_all_columns = $this->config->get('advppp' . $user_id . '_settings_all_columns');
			} else {
				$advppp_settings_all_columns = array();
			}
			
			$cron_settings = unserialize($this->config->get('advppp' . $user_id . 'cron_setting'));
			foreach ($cron_settings as $cron_setting) {
				if (isset($_GET['cron_id']) && ($_GET['cron_id'] == $cron_setting['cron_id'])) {
					$report_type = $cron_setting['cron_report_type'];
					$export_type = $cron_setting['cron_export_type'];
					$export_logo_criteria = $cron_setting['cron_export_logo_criteria'];
					$export_csv_delimiter = $cron_setting['cron_export_csv_delimiter'];
			
					$filter_report = $cron_setting['cron_filter_report'];
					$filter_details = $cron_setting['cron_filter_details'];
					$filter_group = $cron_setting['cron_filter_group'];
					$filter_sort = $cron_setting['cron_filter_sort'];
					$filter_order = $cron_setting['cron_filter_order'];
					$filter_limit = $cron_setting['cron_filter_limit'];
					
					$filter_range = $cron_setting['cron_filter_range'];
					$filter_date_start = $cron_setting['cron_date_start'];
					$filter_date_end = $cron_setting['cron_date_end'];
					$filter_order_status_id = $cron_setting['cron_filter_order_status_id'];
					$filter_status_date_start = $cron_setting['cron_status_date_start'];
					$filter_status_date_end = $cron_setting['cron_status_date_end'];
					$filter_order_id_from = $cron_setting['cron_filter_order_id_from'];
					$filter_order_id_to = $cron_setting['cron_filter_order_id_to'];
					$filter_prod_price_min = $cron_setting['cron_filter_prod_price_min'];
					$filter_prod_price_max = $cron_setting['cron_filter_prod_price_max'];
		
					$filter_store_id = $cron_setting['cron_filter_store_id'];
					$filter_currency = $cron_setting['cron_filter_currency'];
					$filter_taxes = $cron_setting['cron_filter_taxes'];
					$filter_tax_classes = $cron_setting['cron_filter_tax_classes'];
					$filter_geo_zones = $cron_setting['cron_filter_geo_zones'];
					$filter_customer_group_id = $cron_setting['cron_filter_customer_group_id'];
					$filter_customer_name = $cron_setting['cron_filter_customer_name'];
					$filter_customer_email = $cron_setting['cron_filter_customer_email'];
					$filter_customer_telephone = $cron_setting['cron_filter_customer_telephone'];
					$filter_ip = $cron_setting['cron_filter_ip'];
					$filter_payment_company = $cron_setting['cron_filter_payment_company'];
					$filter_payment_address = $cron_setting['cron_filter_payment_address'];
					$filter_payment_city = $cron_setting['cron_filter_payment_city'];
					$filter_payment_zone = $cron_setting['cron_filter_payment_zone'];
					$filter_payment_postcode = $cron_setting['cron_filter_payment_postcode'];
					$filter_payment_country = $cron_setting['cron_filter_payment_country'];
					$filter_payment_method = $cron_setting['cron_filter_payment_method'];
					$filter_shipping_company = $cron_setting['cron_filter_shipping_company'];
					$filter_shipping_address = $cron_setting['cron_filter_shipping_address'];
					$filter_shipping_city = $cron_setting['cron_filter_shipping_city'];
					$filter_shipping_zone = $cron_setting['cron_filter_shipping_zone'];
					$filter_shipping_postcode = $cron_setting['cron_filter_shipping_postcode'];
					$filter_shipping_country = $cron_setting['cron_filter_shipping_country'];
					$filter_shipping_method = $cron_setting['cron_filter_shipping_method'];
					$filter_category = $cron_setting['cron_filter_category'];
					$filter_manufacturer = $cron_setting['cron_filter_manufacturer'];
					$filter_supplier = $cron_setting['cron_filter_supplier'];
					$filter_sku = $cron_setting['cron_filter_sku'];
					$filter_product_name = $cron_setting['cron_filter_product_name'];
					$filter_model = $cron_setting['cron_filter_model'];
					$filter_option = $cron_setting['cron_filter_option'];
					$filter_attribute = $cron_setting['cron_filter_attribute'];
					$filter_product_status = $cron_setting['cron_filter_product_status'];
					$filter_location = $cron_setting['cron_filter_location'];
					$filter_affiliate_name = $cron_setting['cron_filter_affiliate_name'];
					$filter_affiliate_email = $cron_setting['cron_filter_affiliate_email'];
					$filter_coupon_name = $cron_setting['cron_filter_coupon_name'];
					$filter_coupon_code = $cron_setting['cron_filter_coupon_code'];
					$filter_voucher_code = $cron_setting['cron_filter_voucher_code'];	
					
					$file_save_path = $cron_setting['cron_file_save_path'];
					$file_name = $cron_setting['cron_file_name'];
					$export_file = $cron_setting['cron_export_file'];
					$email = $cron_setting['cron_email'];
				}
			}
			
			if ($export_type == 'export_xlsx') {
				$logo = str_replace('\\', '/', DIR_IMAGE . $this->config->get('config_logo'));
			} else {
				$this->load->model('tool/image');
				$logo = $this->model_tool_image->resize($this->config->get('config_logo'), 268, 50);
			}
		
			if ($report_type == 'export_no_details' && $export_type == 'export_xls') {
				$cwd = getcwd();			
				chdir(DIR_SYSTEM . 'library/pear');
				require_once('Spreadsheet/Excel/Writer.php');
				chdir($cwd);			
				include(DIR_APPLICATION . 'controller/report/adv_reports/ppp_export_xls.inc.php');
				exit();
			} elseif ($report_type == 'export_no_details' && $export_type == 'export_xlsx') {
				require_once(DIR_SYSTEM . 'library/PHPExcel/Classes/PHPExcel.php');
				require_once(DIR_SYSTEM . 'library/PHPExcel/Classes/PHPExcel/IOFactory.php');			
				include(DIR_APPLICATION . 'controller/report/adv_reports/ppp_export_xlsx.inc.php');
				exit();			
			} elseif ($report_type == 'export_no_details' && $export_type == 'export_csv') {
				include(DIR_APPLICATION . 'controller/report/adv_reports/ppp_export_csv.inc.php');
				exit();
			} elseif ($report_type == 'export_no_details' && $export_type == 'export_pdf') {
				require_once(DIR_SYSTEM . 'library/dompdf/dompdf_config.inc.php');
				include(DIR_APPLICATION . 'controller/report/adv_reports/ppp_export_pdf.inc.php');
				exit();
			} elseif ($report_type == 'export_no_details' && $export_type == 'export_html') {
				include(DIR_APPLICATION . 'controller/report/adv_reports/ppp_export_html.inc.php');
				exit();	
			} elseif ($report_type == 'export_basic_details' && $export_type == 'export_xls') {
				$cwd = getcwd();			
				chdir(DIR_SYSTEM . 'library/pear');
				require_once('Spreadsheet/Excel/Writer.php');
				chdir($cwd);			
				include(DIR_APPLICATION . 'controller/report/adv_reports/ppp_export_xls_basic_details.inc.php');
				exit();
			} elseif ($report_type == 'export_basic_details' && $export_type == 'export_xlsx') {
				require_once(DIR_SYSTEM . 'library/PHPExcel/Classes/PHPExcel.php');
				require_once(DIR_SYSTEM . 'library/PHPExcel/Classes/PHPExcel/IOFactory.php');			
				include(DIR_APPLICATION . 'controller/report/adv_reports/ppp_export_xlsx_basic_details.inc.php');
				exit();	
			} elseif ($report_type == 'export_basic_details' && $export_type == 'export_csv') {
				include(DIR_APPLICATION . 'controller/report/adv_reports/ppp_export_csv_basic_details.inc.php');
				exit();				
			} elseif ($report_type == 'export_basic_details' && $export_type == 'export_pdf') {
				require_once(DIR_SYSTEM . 'library/dompdf/dompdf_config.inc.php');
				include(DIR_APPLICATION . 'controller/report/adv_reports/ppp_export_pdf_basic_details.inc.php');	
				exit();
			} elseif ($report_type == 'export_basic_details' && $export_type == 'export_html') {
				include(DIR_APPLICATION . 'controller/report/adv_reports/ppp_export_html_basic_details.inc.php');
				exit();	
			} elseif (($report_type == 'export_all_details_products' or $report_type == 'export_all_details_orders') && $export_type == 'export_xls') {
				$cwd = getcwd();			
				chdir(DIR_SYSTEM . 'library/pear');
				require_once('Spreadsheet/Excel/Writer.php');
				chdir($cwd);			
				include(DIR_APPLICATION . 'controller/report/adv_reports/ppp_export_xls_all_details.inc.php');
				exit();
			} elseif (($report_type == 'export_all_details_products' or $report_type == 'export_all_details_orders') && $export_type == 'export_xlsx') {
				require_once(DIR_SYSTEM . 'library/PHPExcel/Classes/PHPExcel.php');
				require_once(DIR_SYSTEM . 'library/PHPExcel/Classes/PHPExcel/IOFactory.php');			
				include(DIR_APPLICATION . 'controller/report/adv_reports/ppp_export_xlsx_all_details.inc.php');
				exit();	
			} elseif (($report_type == 'export_all_details_products' or $report_type == 'export_all_details_orders') && $export_type == 'export_csv') {
				include(DIR_APPLICATION . 'controller/report/adv_reports/ppp_export_csv_all_details.inc.php');
				exit();			
			} else {
				exit();
			}		
		}
	}

	public function customer_autocomplete() {
		$json = array();

		$data['token'] = $this->session->data['token'];
		
		if (isset($this->request->get['filter_customer_name']) or isset($this->request->get['filter_customer_email']) or isset($this->request->get['filter_customer_telephone']) or isset($this->request->get['filter_ip']) or isset($this->request->get['filter_payment_company']) or isset($this->request->get['filter_payment_address']) or isset($this->request->get['filter_payment_city']) or isset($this->request->get['filter_payment_zone']) or isset($this->request->get['filter_payment_postcode']) or isset($this->request->get['filter_payment_country']) or isset($this->request->get['filter_shipping_company']) or isset($this->request->get['filter_shipping_address']) or isset($this->request->get['filter_shipping_city']) or isset($this->request->get['filter_shipping_zone']) or isset($this->request->get['filter_shipping_postcode']) or isset($this->request->get['filter_shipping_country'])) {
			
		$this->load->model('report/adv_products_profit');
		
		if (isset($this->request->get['filter_customer_name'])) {
			$filter_customer_name = $this->request->get['filter_customer_name'];
		} else {
			$filter_customer_name = '';
		}

		if (isset($this->request->get['filter_customer_email'])) {
			$filter_customer_email = $this->request->get['filter_customer_email'];
		} else {
			$filter_customer_email = '';
		}	

		if (isset($this->request->get['filter_customer_telephone'])) {
			$filter_customer_telephone = $this->request->get['filter_customer_telephone'];
		} else {
			$filter_customer_telephone = '';
		}

		if (isset($this->request->get['filter_ip'])) {
			$filter_ip = $this->request->get['filter_ip'];
		} else {
			$filter_ip = '';
		}
		
		if (isset($this->request->get['filter_payment_company'])) {
			$filter_payment_company = $this->request->get['filter_payment_company'];
		} else {
			$filter_payment_company = '';
		}
		
		if (isset($this->request->get['filter_payment_address'])) {
			$filter_payment_address = $this->request->get['filter_payment_address'];
		} else {
			$filter_payment_address = '';
		}

		if (isset($this->request->get['filter_payment_city'])) {
			$filter_payment_city = $this->request->get['filter_payment_city'];
		} else {
			$filter_payment_city = '';
		}
		
		if (isset($this->request->get['filter_payment_zone'])) {
			$filter_payment_zone = $this->request->get['filter_payment_zone'];
		} else {
			$filter_payment_zone = '';
		}
		
		if (isset($this->request->get['filter_payment_postcode'])) {
			$filter_payment_postcode = $this->request->get['filter_payment_postcode'];
		} else {
			$filter_payment_postcode = '';
		}

		if (isset($this->request->get['filter_payment_country'])) {
			$filter_payment_country = $this->request->get['filter_payment_country'];
		} else {
			$filter_payment_country = '';
		}
		
		if (isset($this->request->get['filter_shipping_company'])) {
			$filter_shipping_company = $this->request->get['filter_shipping_company'];
		} else {
			$filter_shipping_company = '';
		}
		
		if (isset($this->request->get['filter_shipping_address'])) {
			$filter_shipping_address = $this->request->get['filter_shipping_address'];
		} else {
			$filter_shipping_address = '';
		}

		if (isset($this->request->get['filter_shipping_city'])) {
			$filter_shipping_city = $this->request->get['filter_shipping_city'];
		} else {
			$filter_shipping_city = '';
		}
		
		if (isset($this->request->get['filter_shipping_zone'])) {
			$filter_shipping_zone = $this->request->get['filter_shipping_zone'];
		} else {
			$filter_shipping_zone = '';
		}
		
		if (isset($this->request->get['filter_shipping_postcode'])) {
			$filter_shipping_postcode = $this->request->get['filter_shipping_postcode'];
		} else {
			$filter_shipping_postcode = '';
		}

		if (isset($this->request->get['filter_shipping_country'])) {
			$filter_shipping_country = $this->request->get['filter_shipping_country'];
		} else {
			$filter_shipping_country = '';
		}

		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = 10;
		}
		
		$filter_data = array(		
			'filter_customer_name' 	 		=> $filter_customer_name,
			'filter_customer_email' 	 	=> $filter_customer_email,			
			'filter_customer_telephone' 	=> $filter_customer_telephone,
			'filter_ip' 					=> $filter_ip,			
			'filter_payment_company' 		=> $filter_payment_company,
			'filter_payment_address' 		=> $filter_payment_address,
			'filter_payment_city' 			=> $filter_payment_city,
			'filter_payment_zone' 			=> $filter_payment_zone,			
			'filter_payment_postcode' 		=> $filter_payment_postcode,
			'filter_payment_country' 		=> $filter_payment_country,			
			'filter_shipping_company' 		=> $filter_shipping_company,
			'filter_shipping_address' 		=> $filter_shipping_address,
			'filter_shipping_city' 			=> $filter_shipping_city,
			'filter_shipping_zone' 			=> $filter_shipping_zone,			
			'filter_shipping_postcode' 		=> $filter_shipping_postcode,
			'filter_shipping_country' 		=> $filter_shipping_country,
			'start'        					=> 0,
			'limit'        					=> $limit
		);
						
		$results = $this->model_report_adv_products_profit->getCustomerAutocomplete($filter_data);
			
			foreach ($results as $result) {
				$json[] = array(
					'customer_id'     		=> $result['customer_id'],				
					'cust_name'     		=> html_entity_decode($result['cust_name'], ENT_QUOTES, 'UTF-8'),
					'cust_email'     		=> $result['cust_email'],
					'cust_telephone'     	=> $result['cust_telephone'],	
					'cust_ip'     			=> $result['cust_ip'],
					'payment_company'     	=> html_entity_decode($result['payment_company'], ENT_QUOTES, 'UTF-8'),	
					'payment_address'     	=> html_entity_decode($result['payment_address'], ENT_QUOTES, 'UTF-8'),	
					'payment_city'     		=> html_entity_decode($result['payment_city'], ENT_QUOTES, 'UTF-8'),	
					'payment_zone'     		=> html_entity_decode($result['payment_zone'], ENT_QUOTES, 'UTF-8'),						
					'payment_postcode'     	=> $result['payment_postcode'],
					'payment_country'     	=> html_entity_decode($result['payment_country'], ENT_QUOTES, 'UTF-8'),					
					'shipping_company'     	=> html_entity_decode($result['shipping_company'], ENT_QUOTES, 'UTF-8'),	
					'shipping_address'     	=> html_entity_decode($result['shipping_address'], ENT_QUOTES, 'UTF-8'),
					'shipping_city'     	=> html_entity_decode($result['shipping_city'], ENT_QUOTES, 'UTF-8'),
					'shipping_zone'     	=> html_entity_decode($result['shipping_zone'], ENT_QUOTES, 'UTF-8'),					
					'shipping_postcode'     => $result['shipping_postcode'],
					'shipping_country'     	=> html_entity_decode($result['shipping_country'], ENT_QUOTES, 'UTF-8')				
				);
			}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}	
	
	public function product_autocomplete() {
		$json = array();

		$data['token'] = $this->session->data['token'];
		
		if (isset($this->request->get['filter_sku']) or isset($this->request->get['filter_product_name']) or isset($this->request->get['filter_model'])) {
		
		$this->load->model('report/adv_products_profit');
					
		if (isset($this->request->get['filter_sku'])) {
			$filter_sku = $this->request->get['filter_sku'];
		} else {
			$filter_sku = '';
		}

		if (isset($this->request->get['filter_product_name'])) {
			$filter_product_name = $this->request->get['filter_product_name'];
		} else {
			$filter_product_name = '';
		}
		
		if (isset($this->request->get['filter_model'])) {
			$filter_model = $this->request->get['filter_model'];
		} else {
			$filter_model = '';
		}

		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = 10;
		}
		
		$filter_data = array(				
			'filter_sku' 	 				=> $filter_sku,
			'filter_product_name' 	 		=> $filter_product_name,
			'filter_model' 	 				=> $filter_model,
			'start'        					=> 0,
			'limit'        					=> $limit	
		);
						
		$results = $this->model_report_adv_products_profit->getProductAutocomplete($filter_data);
			
			foreach ($results as $result) {
				$json[] = array(
					'product_id'     		=> $result['product_id'],
					'prod_sku'     			=> html_entity_decode($result['prod_sku'], ENT_QUOTES, 'UTF-8'),					
					'prod_name'     		=> html_entity_decode($result['prod_name'], ENT_QUOTES, 'UTF-8'),
					'prod_model'     		=> html_entity_decode($result['prod_model'], ENT_QUOTES, 'UTF-8')				
				);
			}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function coupon_autocomplete() {
		$json = array();

		$data['token'] = $this->session->data['token'];
		
		if (isset($this->request->get['filter_coupon_code'])) {
			
		$this->load->model('report/adv_products_profit');

		if (isset($this->request->get['filter_coupon_code'])) {
			$filter_coupon_code = $this->request->get['filter_coupon_code'];
		} else {
			$filter_coupon_code = '';
		}

		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = 10;
		}
		
		$filter_data = array(		
			'filter_coupon_code' 	 		=> $filter_coupon_code,
			'start'        					=> 0,
			'limit'        					=> $limit			
		);
						
		$results = $this->model_report_adv_products_profit->getCouponAutocomplete($filter_data);
			
			foreach ($results as $result) {
				$json[] = array(
					'coupon_id'     		=> $result['coupon_id'],
					'coupon_code'     		=> html_entity_decode($result['coupon_code'], ENT_QUOTES, 'UTF-8')
				);
			}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function voucher_autocomplete() {
		$json = array();

		$data['token'] = $this->session->data['token'];
		
		if (isset($this->request->get['filter_voucher_code'])) {
			
		$this->load->model('report/adv_products_profit');

		if (isset($this->request->get['filter_voucher_code'])) {
			$filter_voucher_code = $this->request->get['filter_voucher_code'];
		} else {
			$filter_voucher_code = '';
		}

		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = 10;
		}
		
		$filter_data = array(		
			'filter_voucher_code' 	 		=> $filter_voucher_code,
			'start'        					=> 0,
			'limit'        					=> $limit
		);
						
		$results = $this->model_report_adv_products_profit->getVoucherAutocomplete($filter_data);
			
			foreach ($results as $result) {
				$json[] = array(
					'voucher_id'     		=> $result['voucher_id'],
					'voucher_code'     		=> html_entity_decode($result['voucher_code'], ENT_QUOTES, 'UTF-8')
				);
			}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function settings($filter_data = array()) {
		$json = array();
		
		$this->load->model('setting/setting');
		$this->load->language('report/adv_products_profit');
		
		if (!$json) {
			if (!$this->user->hasPermission('modify', 'report/adv_products_profit')) {
				$json['error'] = $this->language->get('error_permission');
			} else {			
				$user = 'advppp' . $this->user->getId();
				$this->model_setting_setting->editSetting($user, $this->request->post);
				$json['success'] = $this->language->get('text_success_settings');
			}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function load_save_report($filter_data = array()) {
		$json = array();
		
		$this->load->model('setting/setting');
		$this->load->language('report/adv_products_profit');
		
		if (!$json) {
			if (!$this->user->hasPermission('modify', 'report/adv_products_profit')) {
				$json['error'] = $this->language->get('error_permission');
			} else {			
				if (isset($this->request->post['advppp_load_save_report'])) {
					$this->request->post['advppp' . $this->user->getId() . 'sr_load_save_report'] = serialize($this->request->post['advppp_load_save_report']);
				}			
				$this->model_setting_setting->editSetting('advppp' . $this->user->getId() . 'sr', $this->request->post);
				$json['success'] = $this->language->get('text_success_save_report');
			}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function cron($filter_data = array()) {
		$json = array();
		
		$this->load->model('setting/setting');
		$this->load->language('report/adv_products_profit');
		
		if (!$json) {
			if (!$this->user->hasPermission('modify', 'report/adv_products_profit')) {
				$json['error'] = $this->language->get('error_permission');
			} else {	
				if ($this->request->post['cron_export_type'] == '') {
					$json['error'] = $this->language->get('error_export_type');
				} else if ((utf8_strlen($this->request->post['cron_email']) > 96) || !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['cron_email'])) {
					$json['error'] = $this->language->get('error_export_email');					
				} else if ($this->request->post['cron_file_name'] == '') {
					$json['error'] = $this->language->get('error_file_name');
				} else if ($this->request->post['cron_user'] == '') {
					$json['error'] = $this->language->get('error_admin_username');
				} else if ($this->request->post['cron_pass'] == '') {
					$json['error'] = $this->language->get('error_admin_password');
				} else if ($this->request->post['cron_token'] == '') {
					$json['error'] = $this->language->get('error_generate_token');
				} else {
					if (isset($this->request->post['advppp_cron_setting'])) {
						$this->request->post['advppp' . $this->user->getId() . 'cron_setting'] = serialize($this->request->post['advppp_cron_setting']);
					}			
					$this->model_setting_setting->editSetting('advppp' . $this->user->getId() . 'cron', $this->request->post);
					$json['success'] = $this->language->get('text_success_save_cron');
				}			
			}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function export($filter_data = array()) {
		$this->load->language('report/adv_products_profit');

		$export_data = array(
			'results'		=> $this->session->data['products_data']
		);
		
		$user_id = $this->user->getId();

		if ($this->config->get('advppp' . $user_id . '_settings_scw_columns')) {
			$advppp_settings_scw_columns = $this->config->get('advppp' . $user_id . '_settings_scw_columns');
		} else {
			$advppp_settings_scw_columns = array();
		}
			
		if ($this->config->get('advppp' . $user_id . '_settings_pnp_columns')) {
			$advppp_settings_pnp_columns = $this->config->get('advppp' . $user_id . '_settings_pnp_columns');
		} else {
			$advppp_settings_pnp_columns = array();
		}

		if ($this->config->get('advppp' . $user_id . '_settings_cm_columns')) {
			$advppp_settings_cm_columns = $this->config->get('advppp' . $user_id . '_settings_cm_columns');
		} else {
			$advppp_settings_cm_columns = array();
		}
		
		if ($this->config->get('advppp' . $user_id . '_settings_mv_columns')) {
			$advppp_settings_mv_columns = $this->config->get('advppp' . $user_id . '_settings_mv_columns');
		} else {
			$advppp_settings_mv_columns = array();
		}	

		if ($this->config->get('advppp' . $user_id . '_settings_ol_columns')) {
			$advppp_settings_ol_columns = $this->config->get('advppp' . $user_id . '_settings_ol_columns');
		} else {
			$advppp_settings_ol_columns = array();
		}
		
		if ($this->config->get('advppp' . $user_id . '_settings_pl_columns')) {
			$advppp_settings_pl_columns = $this->config->get('advppp' . $user_id . '_settings_pl_columns');
		} else {
			$advppp_settings_pl_columns = array();
		}
		
		if ($this->config->get('advppp' . $user_id . '_settings_cl_columns')) {
			$advppp_settings_cl_columns = $this->config->get('advppp' . $user_id . '_settings_cl_columns');
		} else {
			$advppp_settings_cl_columns = array();
		}
		
		if ($this->config->get('advppp' . $user_id . '_settings_all_columns')) {
			$advppp_settings_all_columns = $this->config->get('advppp' . $user_id . '_settings_all_columns');
		} else {
			$advppp_settings_all_columns = array();
		}
		
		$this->session->data['report_type'] = $report_type = $this->request->get['report_type'];		
		$this->session->data['export_type'] = $export_type = $this->request->get['export_type'];
		$this->session->data['export_logo_criteria'] = $export_logo_criteria = $this->request->get['export_logo_criteria'];
		$this->session->data['export_csv_delimiter'] = $export_csv_delimiter = $this->request->get['export_csv_delimiter'];
			
		$filter_report = $this->session->data['filter_report'];
		$filter_details = $this->session->data['filter_details'];
		$filter_group = $this->session->data['filter_group'];
		$filter_sort = $this->session->data['filter_sort'];
		$filter_order = $this->session->data['filter_order'];
		$filter_limit = $this->session->data['filter_limit'];
		
		$filter_range = $this->session->data['filter_range'];
		$filter_date_start = $this->session->data['filter_date_start'];
		$filter_date_end = $this->session->data['filter_date_end'];
		$filter_order_status_id = $this->session->data['filter_order_status_id'];
		$filter_status_date_start = $this->session->data['filter_status_date_start'];
		$filter_status_date_end = $this->session->data['filter_status_date_end'];
		$filter_order_id_from = $this->session->data['filter_order_id_from'];
		$filter_order_id_to = $this->session->data['filter_order_id_to'];
		$filter_prod_price_min = $this->session->data['filter_prod_price_min'];
		$filter_prod_price_max = $this->session->data['filter_prod_price_max'];			
		
		$filter_store_id = $this->session->data['filter_store_id'];
		$filter_currency = $this->session->data['filter_currency'];
		$filter_taxes = $this->session->data['filter_taxes'];
		$filter_tax_classes = $this->session->data['filter_tax_classes'];
		$filter_geo_zones = $this->session->data['filter_geo_zones'];
		$filter_customer_group_id = $this->session->data['filter_customer_group_id'];
		$filter_customer_name = $this->session->data['filter_customer_name'];
		$filter_customer_email = $this->session->data['filter_customer_email'];
		$filter_customer_telephone = $this->session->data['filter_customer_telephone'];
		$filter_ip = $this->session->data['filter_ip'];
		$filter_payment_company = $this->session->data['filter_payment_company'];
		$filter_payment_address = $this->session->data['filter_payment_address'];
		$filter_payment_city = $this->session->data['filter_payment_city'];
		$filter_payment_zone = $this->session->data['filter_payment_zone'];
		$filter_payment_postcode = $this->session->data['filter_payment_postcode'];
		$filter_payment_country = $this->session->data['filter_payment_country'];
		$filter_payment_method = $this->session->data['filter_payment_method'];
		$filter_shipping_company = $this->session->data['filter_shipping_company'];
		$filter_shipping_address = $this->session->data['filter_shipping_address'];
		$filter_shipping_city = $this->session->data['filter_shipping_city'];
		$filter_shipping_zone = $this->session->data['filter_shipping_zone'];
		$filter_shipping_postcode = $this->session->data['filter_shipping_postcode'];
		$filter_shipping_country = $this->session->data['filter_shipping_country'];
		$filter_shipping_method = $this->session->data['filter_shipping_method'];
		$filter_category = $this->session->data['filter_category'];
		$filter_manufacturer = $this->session->data['filter_manufacturer'];
		$filter_supplier = $this->session->data['filter_supplier'];
		$filter_sku = $this->session->data['filter_sku'];
		$filter_product_name = $this->session->data['filter_product_name'];
		$filter_model = $this->session->data['filter_model'];
		$filter_option = $this->session->data['filter_option'];
		$filter_attribute = $this->session->data['filter_attribute'];
		$filter_product_status = $this->session->data['filter_product_status'];		
		$filter_location = $this->session->data['filter_location'];
		$filter_affiliate_name = $this->session->data['filter_affiliate_name'];
		$filter_affiliate_email = $this->session->data['filter_affiliate_email'];
		$filter_coupon_name = $this->session->data['filter_coupon_name'];
		$filter_coupon_code = $this->session->data['filter_coupon_code'];
		$filter_voucher_code = $this->session->data['filter_voucher_code'];
		
		if ($export_type == 'export_xlsx') {
			$logo = str_replace('\\', '/', DIR_IMAGE . $this->config->get('config_logo'));
		} else {
			$this->load->model('tool/image');
			$logo = $this->model_tool_image->resize($this->config->get('config_logo'), 268, 50);
		}
		
		unset($this->session->data['error_export_type']);
		
		if ($report_type == 'export_no_details' && $export_type == 'export_xls') {
			$cwd = getcwd();			
			chdir(DIR_SYSTEM . 'library/pear');
			require_once('Spreadsheet/Excel/Writer.php');
			chdir($cwd);			
			include(DIR_APPLICATION . 'controller/report/adv_reports/ppp_export_xls.inc.php');
			exit();
		} elseif ($report_type == 'export_no_details' && $export_type == 'export_xlsx') {
			require_once(DIR_SYSTEM . 'library/PHPExcel/Classes/PHPExcel.php');
			require_once(DIR_SYSTEM . 'library/PHPExcel/Classes/PHPExcel/IOFactory.php');			
			include(DIR_APPLICATION . 'controller/report/adv_reports/ppp_export_xlsx.inc.php');
			exit();			
		} elseif ($report_type == 'export_no_details' && $export_type == 'export_csv') {
			include(DIR_APPLICATION . 'controller/report/adv_reports/ppp_export_csv.inc.php');
			exit();
		} elseif ($report_type == 'export_no_details' && $export_type == 'export_pdf') {
			require_once(DIR_SYSTEM . 'library/dompdf/dompdf_config.inc.php');
			include(DIR_APPLICATION . 'controller/report/adv_reports/ppp_export_pdf.inc.php');
			exit();
		} elseif ($report_type == 'export_no_details' && $export_type == 'export_html') {
			include(DIR_APPLICATION . 'controller/report/adv_reports/ppp_export_html.inc.php');
			exit();	
		} elseif ($report_type == 'export_basic_details' && $export_type == 'export_xls') {
			$cwd = getcwd();			
			chdir(DIR_SYSTEM . 'library/pear');
			require_once('Spreadsheet/Excel/Writer.php');
			chdir($cwd);			
			include(DIR_APPLICATION . 'controller/report/adv_reports/ppp_export_xls_basic_details.inc.php');
			exit();
		} elseif ($report_type == 'export_basic_details' && $export_type == 'export_xlsx') {
			require_once(DIR_SYSTEM . 'library/PHPExcel/Classes/PHPExcel.php');
			require_once(DIR_SYSTEM . 'library/PHPExcel/Classes/PHPExcel/IOFactory.php');			
			include(DIR_APPLICATION . 'controller/report/adv_reports/ppp_export_xlsx_basic_details.inc.php');
			exit();	
		} elseif ($report_type == 'export_basic_details' && $export_type == 'export_csv') {
			include(DIR_APPLICATION . 'controller/report/adv_reports/ppp_export_csv_basic_details.inc.php');
			exit();				
		} elseif ($report_type == 'export_basic_details' && $export_type == 'export_pdf') {
			require_once(DIR_SYSTEM . 'library/dompdf/dompdf_config.inc.php');
			include(DIR_APPLICATION . 'controller/report/adv_reports/ppp_export_pdf_basic_details.inc.php');	
			exit();
		} elseif ($report_type == 'export_basic_details' && $export_type == 'export_html') {
			include(DIR_APPLICATION . 'controller/report/adv_reports/ppp_export_html_basic_details.inc.php');
			exit();	
		} elseif (($report_type == 'export_all_details_products' or $report_type == 'export_all_details_orders') && $export_type == 'export_xls') {
			$cwd = getcwd();			
			chdir(DIR_SYSTEM . 'library/pear');
			require_once('Spreadsheet/Excel/Writer.php');
			chdir($cwd);			
			include(DIR_APPLICATION . 'controller/report/adv_reports/ppp_export_xls_all_details.inc.php');
			exit();
		} elseif (($report_type == 'export_all_details_products' or $report_type == 'export_all_details_orders') && $export_type == 'export_xlsx') {
			require_once(DIR_SYSTEM . 'library/PHPExcel/Classes/PHPExcel.php');
			require_once(DIR_SYSTEM . 'library/PHPExcel/Classes/PHPExcel/IOFactory.php');			
			include(DIR_APPLICATION . 'controller/report/adv_reports/ppp_export_xlsx_all_details.inc.php');
			exit();	
		} elseif (($report_type == 'export_all_details_products' or $report_type == 'export_all_details_orders') && $export_type == 'export_csv') {
			include(DIR_APPLICATION . 'controller/report/adv_reports/ppp_export_csv_all_details.inc.php');
			exit();			
		} else {
			exit();
		}	
	}
	
	public function export_validate () {
		$json = array();
		
		$this->load->language('report/adv_products_profit');
				
		if (!empty($this->session->data['products_data'])) {
			if ($this->request->post['export_type'] == '') {
				$json['error'] = $this->language->get('error_export_type');
			}
		} else {
			$json['error'] = $this->language->get('error_no_data');
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	protected function clearSpreadsheetCache() {
		$files = glob(DIR_CACHE . 'Spreadsheet_Excel_Writer' . '*');
		
		if ($files) {
			foreach ($files as $file) {
				if (file_exists($file)) {
					@unlink($file);
					clearstatcache();
				}
			}
		}
	}
}