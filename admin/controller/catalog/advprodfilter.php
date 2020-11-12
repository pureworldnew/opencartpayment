<?php 
class ControllerCatalogadvprodfilter extends Controller { 
	private $error = array();
	
	public function index() {

		if(version_compare(VERSION, '1.5.4.1', '>')) {
		$this->language->load('catalog/advprodfilter');
		} else {
    		$this->load->language('catalog/advprodfilter');
		}
		
		$this->document->addStyle('view/template/catalog/advprodfilter.css');
		
		$data['heading_title'] = $this->language->get('heading_title');
		
		// Filters
		$data['apftxt_p_filters_h'] = $this->language->get('apftxt_p_filters_h');
		$data['apftxt_name'] = $this->language->get('apftxt_name');
		$data['apftxt_name_help'] = $this->language->get('apftxt_name_help');
		$data['apftxt_model'] = $this->language->get('apftxt_model');
		$data['apftxt_model_help'] = $this->language->get('apftxt_model_help');
		$data['apftxt_tag'] = $this->language->get('apftxt_tag');
		$data['apftxt_tag_help'] = $this->language->get('apftxt_tag_help');
		$data['apftxt_categories'] = $this->language->get('apftxt_categories');
		$data['apftxt_manufacturers'] = $this->language->get('apftxt_manufacturers');
		$data['apftxt_price'] = $this->language->get('apftxt_price');
		$data['apftxt_price_help'] = $this->language->get('apftxt_price_help');
		$data['apftxt_discount'] = $this->language->get('apftxt_discount');
		$data['apftxt_customer_group'] = $this->language->get('apftxt_customer_group');
		$data['apftxt_special'] = $this->language->get('apftxt_special');
		$data['apftxt_tax_class'] = $this->language->get('apftxt_tax_class');
		$data['apftxt_quantity'] = $this->language->get('apftxt_quantity');
		$data['apftxt_minimum_quantity'] = $this->language->get('apftxt_minimum_quantity');
		$data['apftxt_subtract_stock'] = $this->language->get('apftxt_subtract_stock');
		$data['apftxt_out_of_stock_status'] = $this->language->get('apftxt_out_of_stock_status');
		$data['apftxt_requires_shipping'] = $this->language->get('apftxt_requires_shipping');
		$data['apftxt_date_available'] = $this->language->get('apftxt_date_available');
		$data['apftxt_date_added'] = $this->language->get('apftxt_date_added');
		$data['apftxt_date_modified'] = $this->language->get('apftxt_date_modified');
		$data['apftxt_status'] = $this->language->get('apftxt_status');
		$data['apftxt_store'] = $this->language->get('apftxt_store');
		$data['apftxt_with_attribute'] = $this->language->get('apftxt_with_attribute');
		$data['apftxt_with_attribute_value'] = $this->language->get('apftxt_with_attribute_value');
		$data['apftxt_with_attribute_value_help'] = $this->language->get('apftxt_with_attribute_value_help');
		$data['apftxt_with_this_option'] = $this->language->get('apftxt_with_this_option');
		$data['apftxt_with_this_option_value'] = $this->language->get('apftxt_with_this_option_value');
		
		$data['apftxt_yes'] = $this->language->get('apftxt_yes');
		$data['apftxt_no'] = $this->language->get('apftxt_no');
		$data['apftxt_enabled'] = $this->language->get('apftxt_enabled');
		$data['apftxt_disabled'] = $this->language->get('apftxt_disabled');
		$data['apftxt_select_all'] = $this->language->get('apftxt_select_all');
		$data['apftxt_unselect_all'] = $this->language->get('apftxt_unselect_all');
		$data['apftxt_none'] = $this->language->get('apftxt_none');
		$data['apftxt_none_cat'] = $this->language->get('apftxt_none_cat');
		$data['apftxt_none_fil'] = $this->language->get('apftxt_none_fil');
		$data['apftxt_all'] = $this->language->get('apftxt_all');
		$data['apftxt_default'] = $this->language->get('apftxt_default');
		$data['apftxt_unselect_all_to_ignore'] = $this->language->get('apftxt_unselect_all_to_ignore');
		$data['apftxt_ignore_this'] = $this->language->get('apftxt_ignore_this');
		$data['apftxt_leave_empty_to_ignore'] = $this->language->get('apftxt_leave_empty_to_ignore');
		$data['apftxt_greater_than_or_equal'] = $this->language->get('apftxt_greater_than_or_equal');
		$data['apftxt_less_than_or_equal'] = $this->language->get('apftxt_less_than_or_equal');
		
		$data['apftxt_show_max_prod_per_pag1'] = $this->language->get('apftxt_show_max_prod_per_pag1');
		$data['apftxt_show_max_prod_per_pag2'] = $this->language->get('apftxt_show_max_prod_per_pag2');
		
		$data['apftxt_button_filter_products'] = $this->language->get('apftxt_button_filter_products');
		$data['apftxt_button_reset_filters'] = $this->language->get('apftxt_button_reset_filters');
		
		// Products results
		$data['apftxt_results_products'] = $this->language->get('apftxt_results_products');
		$data['apftxt_results_insert'] = $this->language->get('apftxt_results_insert');
		$data['apftxt_results_copy'] = $this->language->get('apftxt_results_copy');
		$data['apftxt_results_delete'] = $this->language->get('apftxt_results_delete');
		$data['apftxt_results_product_name'] = $this->language->get('apftxt_results_product_name');
		$data['apftxt_results_image'] = $this->language->get('apftxt_results_image');
		$data['apftxt_results_model'] = $this->language->get('apftxt_results_model');
		$data['apftxt_results_base_price'] = $this->language->get('apftxt_results_base_price');
		$data['apftxt_results_quantity'] = $this->language->get('apftxt_results_quantity');
		$data['apftxt_results_status'] = $this->language->get('apftxt_results_status');
		$data['apftxt_results_product_id'] = $this->language->get('apftxt_results_product_id');
		$data['apftxt_results_date_added'] = $this->language->get('apftxt_results_date_added');
		$data['apftxt_results_date_modified'] = $this->language->get('apftxt_results_date_modified');
		$data['apftxt_results_viewed'] = $this->language->get('apftxt_results_viewed');
		$data['apftxt_results_action'] = $this->language->get('apftxt_results_action');
		$data['apftxt_results_edit'] = $this->language->get('apftxt_results_edit');
		$data['apftxt_results_no_results'] = $this->language->get('apftxt_results_no_results');

		
		$this->document->setTitle($data['heading_title']);
		
		$this->load->model('catalog/category');
		
		$this->load->model('catalog/manufacturer');
		
		$this->load->model('localisation/tax_class');
		
		$this->load->model('localisation/stock_status');
		
		$this->load->model('localisation/language');
		
		$this->load->model('catalog/attribute');
		
		$this->load->model('setting/store');
		
		$this->load->model('sale/customer_group');
		



		unset($post_var);
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
		$this->session->data['advpf_post']=$_POST;
		}
		
		if (isset($this->request->post['reset_filters']) OR isset($this->request->get['resetadvpfil'])) {
		unset($this->session->data['advpf_post']);
		}
		
		if (isset($this->session->data['advpf_post'])) {
		$post_var=array();
		$post_var=$this->session->data['advpf_post'];
		}

		
		$data['insert'] = $this->url->link('catalog/product/insert', 'token=' . $this->session->data['token'] . '&advprodfilter=1', 'SSL');
		
		
		
		if(version_compare(VERSION, '1.5.4.1', '>')) {
		$data['apftxt_p_filters'] = $this->language->get('apftxt_p_filters');
		$data['apftxt_p_filters_none'] = $this->language->get('apftxt_p_filters_none');
		
		$sql = "SELECT f.filter_id AS `filter_id`, fd.name AS `name`, fgd.name AS `group` FROM " . DB_PREFIX . "filter f 
		LEFT JOIN " . DB_PREFIX . "filter_description fd ON (f.filter_id = fd.filter_id) 
		LEFT JOIN " . DB_PREFIX . "filter_group_description fgd ON (f.filter_group_id = fgd.filter_group_id) 
		LEFT JOIN " . DB_PREFIX . "filter_group fg ON (f.filter_group_id = fg.filter_group_id) 
		WHERE fd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
		AND fgd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		$sql .= " ORDER BY fg.sort_order, fgd.name, f.sort_order, fd.name";
		$query_pf = $this->db->query($sql);
		$data['p_filters'] = $query_pf->rows;

		if (isset($post_var['filters_ids'])) {
			$data['filters_ids'] = $post_var['filters_ids'];
		} else {
			$data['filters_ids'] = array();
		}
		}
		
		
		$data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		
		$data['categories'] = $this->model_catalog_category->getCategories(0);
		if (isset($post_var['product_category'])) {
			$data['product_category'] = $post_var['product_category'];
		} else {
			$data['product_category'] = array();
		}
		
		$data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();
		if (isset($post_var['manufacturer_ids'])) {
      			$data['manufacturer_ids'] = $post_var['manufacturer_ids'];
		} else {
      			$data['manufacturer_ids'] = array();
    		}
		
		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
		
		$data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();
		
		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		$data['all_attributes'] = $this->model_catalog_attribute->getAttributes();
		
		$data['stores'] = $this->model_setting_store->getStores();
		
		// all options names + id-s for filter
		$query_all_options = $this->db->query("SELECT od.option_id, od.name FROM " . DB_PREFIX . "option_description od 
		WHERE od.language_id = '" . (int)$this->config->get('config_language_id') . "'
		ORDER BY od.name");
		$data['all_options'] = $query_all_options->rows;
		
		// all options values + id-s for filter
		$query_all_optval = $this->db->query("SELECT ovd.option_value_id, ovd.name AS ov_name, od.name AS o_name 
		FROM " . DB_PREFIX . "option_value_description ovd 
		LEFT JOIN " . DB_PREFIX . "option_description od ON (ovd.option_id = od.option_id) 
		WHERE ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY ovd.option_value_id ORDER BY od.name, ovd.name");
		$data['all_optval'] = $query_all_optval->rows;
		
		////
		
		if (isset($post_var['price_mmarese'])) {
      			$data['price_mmarese'] = $post_var['price_mmarese'];
		} else {
      			$data['price_mmarese'] = '';
    		}

		if (isset($post_var['price_mmicse'])) {
      			$data['price_mmicse'] = $post_var['price_mmicse'];
		} else {
      			$data['price_mmicse'] = '';
    		}

		if (isset($post_var['d_cust_group_filter'])) {
      			$data['d_cust_group_filter'] = $post_var['d_cust_group_filter'];
		} else {
      			$data['d_cust_group_filter'] = 'any';
    		}
		
		if (isset($post_var['s_cust_group_filter'])) {
      			$data['s_cust_group_filter'] = $post_var['s_cust_group_filter'];
		} else {
      			$data['s_cust_group_filter'] = 'any';
    		}

		if (isset($post_var['d_price_mmarese'])) {
      			$data['d_price_mmarese'] = $post_var['d_price_mmarese'];
		} else {
      			$data['d_price_mmarese'] = '';
    		}

		if (isset($post_var['d_price_mmicse'])) {
      			$data['d_price_mmicse'] = $post_var['d_price_mmicse'];
		} else {
      			$data['d_price_mmicse'] = '';
    		}

		if (isset($post_var['s_price_mmarese'])) {
      			$data['s_price_mmarese'] = $post_var['s_price_mmarese'];
		} else {
      			$data['s_price_mmarese'] = '';
    		}

		if (isset($post_var['s_price_mmicse'])) {
      			$data['s_price_mmicse'] = $post_var['s_price_mmicse'];
		} else {
      			$data['s_price_mmicse'] = '';
    		}

		if (isset($post_var['tax_class_filter'])) {
      			$data['tax_class_filter'] = $post_var['tax_class_filter'];
		} else {
      			$data['tax_class_filter'] = 'any';
    		}

		if (isset($post_var['stock_mmarese'])) {
      			$data['stock_mmarese'] = $post_var['stock_mmarese'];
		} else {
      			$data['stock_mmarese'] = '';
    		}

		if (isset($post_var['stock_mmicse'])) {
      			$data['stock_mmicse'] = $post_var['stock_mmicse'];
		} else {
      			$data['stock_mmicse'] = '';
    		}

		if (isset($post_var['min_q_mmarese'])) {
      			$data['min_q_mmarese'] = $post_var['min_q_mmarese'];
		} else {
      			$data['min_q_mmarese'] = '';
    		}

		if (isset($post_var['min_q_mmicse'])) {
      			$data['min_q_mmicse'] = $post_var['min_q_mmicse'];
		} else {
      			$data['min_q_mmicse'] = '';
    		}

		if (isset($post_var['subtract_filter'])) {
      			$data['subtract_filter'] = $post_var['subtract_filter'];
		} else {
      			$data['subtract_filter'] = 'any';
    		}

		if (isset($post_var['stock_status_filter'])) {
      			$data['stock_status_filter'] = $post_var['stock_status_filter'];
		} else {
      			$data['stock_status_filter'] = 'any';
    		}

		if (isset($post_var['shipping_filter'])) {
      			$data['shipping_filter'] = $post_var['shipping_filter'];
		} else {
      			$data['shipping_filter'] = 'any';
    		}

		if (isset($post_var['date_mmarese'])) {
      			$data['date_mmarese'] = $post_var['date_mmarese'];
		} else {
      			$data['date_mmarese'] = '';
    		}

		if (isset($post_var['date_mmicse'])) {
      			$data['date_mmicse'] = $post_var['date_mmicse'];
		} else {
      			$data['date_mmicse'] = '';
    		}

		if (isset($post_var['date_added_mmarese'])) {
      			$data['date_added_mmarese'] = $post_var['date_added_mmarese'];
		} else {
      			$data['date_added_mmarese'] = '';
    		}

		if (isset($post_var['date_added_mmicse'])) {
      			$data['date_added_mmicse'] = $post_var['date_added_mmicse'];
		} else {
      			$data['date_added_mmicse'] = '';
    		}
    		
    		if (isset($post_var['date_modified_mmarese'])) {
      			$data['date_modified_mmarese'] = $post_var['date_modified_mmarese'];
		} else {
      			$data['date_modified_mmarese'] = '';
    		}

		if (isset($post_var['date_modified_mmicse'])) {
      			$data['date_modified_mmicse'] = $post_var['date_modified_mmicse'];
		} else {
      			$data['date_modified_mmicse'] = '';
    		}

		if (isset($post_var['prod_status'])) {
      			$data['prod_status'] = $post_var['prod_status'];
		} else {
      			$data['prod_status'] = 'any';
    		}

    		if (isset($post_var['store_filter'])) {
      			$data['store_filter'] = $post_var['store_filter'];
		} else {
      			$data['store_filter'] = 'any';
    		}

		if (isset($post_var['filter_attr'])) {
      			$data['filter_attr'] = $post_var['filter_attr'];
		} else {
      			$data['filter_attr'] = 'any';
    		}
    		
    		if (isset($post_var['filter_opti'])) {
      			$data['filter_opti'] = $post_var['filter_opti'];
		} else {
      			$data['filter_opti'] = 'any';
    		}
    		
    		if (isset($post_var['filter_attr_val'])) {
      			$data['filter_attr_val'] = $post_var['filter_attr_val'];
		} else {
      			$data['filter_attr_val'] = '';
    		}
    		
    		if (isset($post_var['filter_opti_val'])) {
      			$data['filter_opti_val'] = $post_var['filter_opti_val'];
		} else {
      			$data['filter_opti_val'] = 'any';
    		}
    		
    		if (isset($post_var['filter_name'])) {
      			$data['filter_name'] = $post_var['filter_name'];
		} else {
      			$data['filter_name'] = '';
    		}
    		
    		if (isset($post_var['filter_namex'])) {
      			$data['filter_namex'] = $post_var['filter_namex'];
		} else {
      			$data['filter_namex'] = '';
    		}

    		if (isset($post_var['filter_modelx'])) {
      			$data['filter_modelx'] = $post_var['filter_modelx'];
		} else {
      			$data['filter_modelx'] = '';
    		}
    		
     		if (isset($post_var['product_id_to_attr'])) {
      			$data['product_id_to_attr'] = $post_var['product_id_to_attr'];
		} else {
      			$data['product_id_to_attr'] = '';
    		}   		
    		
    		if (isset($post_var['filter_model'])) {
      			$data['filter_model'] = $post_var['filter_model'];
		} else {
      			$data['filter_model'] = '';
    		}
    		
    		if (isset($post_var['filter_tag'])) {
      			$data['filter_tag'] = $post_var['filter_tag'];
		} else {
      			$data['filter_tag'] = '';
    		}
    		
    		if (isset($post_var['max_prod_per_pag'])) {
      			$data['max_prod_per_pag'] = $post_var['max_prod_per_pag'];
		} else {
      			$data['max_prod_per_pag'] = $this->config->get('config_admin_limit');
    		}
    		
    		////
    		
    		$data['arr_lista_prod'] = array();




if (isset($this->request->post['delete'])) { /// delete products button

$this->load->model('catalog/product');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $product_id) {
				$this->model_catalog_product->deleteProduct($product_id);
	  		}
			
			$url = '';
					
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
		
		$this->session->data['success'] = $this->language->get('apftxt_succes_products_deleted1').(isset($this->request->post['selected']) ? count($this->request->post['selected']) : 0).$this->language->get('apftxt_succes_products_deleted2');

		}

} /// end delete button


if (isset($this->request->post['copy'])) { /// copy products button

$this->load->model('catalog/product');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $product_id) {
				$this->model_catalog_product->copyProduct($product_id);
	  		}
			
			$url = '';
					
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
		$this->session->data['success'] = $this->language->get('apftxt_succes_products_copied1').(isset($this->request->post['selected']) ? count($this->request->post['selected']) : 0).$this->language->get('apftxt_succes_products_copied2');

		}

} /// end copy button



//if ($this->request->server['REQUEST_METHOD'] == 'POST') { /// post

$prfx="";
$plus_join="";
$plus_where="";

if (isset($post_var)) { /// data filters

if (isset($post_var['product_category'])) { // categories
$plus_join=" LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";
if ($plus_where=="") { $prfx=" WHERE "; } else { $prfx=" AND "; }
	if (in_array(0,$post_var['product_category'])) {
	$plus_join.=" LEFT JOIN " . DB_PREFIX . "product_to_category p2c0x ON (p.product_id = p2c0x.product_id)";
	$plus_where=$prfx."(p2c.category_id IN ('" .implode("', '", $post_var['product_category']). "') OR p2c0x.category_id IS NULL)";
	} else {
	$plus_where=$prfx."p2c.category_id IN ('" .implode("', '", $post_var['product_category']). "')";
	}
}

if (isset($post_var['manufacturer_ids'])) { // manufacturers
if ($plus_where=="") { $prfx=" WHERE "; } else { $prfx=" AND "; }
$plus_where.=$prfx."p.manufacturer_id IN ('" .implode("', '", $post_var['manufacturer_ids']). "')";
}

if (isset($post_var['filters_ids'])) { // filters
$plus_join.=" LEFT JOIN " . DB_PREFIX . "product_filter prfil ON (p.product_id = prfil.product_id)";
if ($plus_where=="") { $prfx=" WHERE "; } else { $prfx=" AND "; }
	if (in_array(0,$post_var['filters_ids'])) {
	$plus_join.=" LEFT JOIN " . DB_PREFIX . "product_filter pf0x ON (p.product_id = pf0x.product_id)";
	$plus_where.=$prfx."(prfil.filter_id IN ('" .implode("', '", $post_var['filters_ids']). "') OR pf0x.filter_id IS NULL)";
	} else {
	$plus_where.=$prfx."prfil.filter_id IN ('" .implode("', '", $post_var['filters_ids']). "')";
	}
}

if ($post_var['price_mmarese']!="") { // price greater than or equal to
if ($plus_where=="") { $prfx=" WHERE "; } else { $prfx=" AND "; }
$plus_where.=$prfx."p.price >= '" . (float)$post_var['price_mmarese'] . "'";
}

if ($post_var['price_mmicse']!="") { // price less than or equal to
if ($plus_where=="") { $prfx=" WHERE "; } else { $prfx=" AND "; }
$plus_where.=$prfx."p.price <= '" . (float)$post_var['price_mmicse'] . "'";
}

// discount price
if ($post_var['d_price_mmarese']!="" OR $post_var['d_price_mmicse']!="" OR $post_var['d_cust_group_filter']!="any") {
$plus_join.=" LEFT JOIN " . DB_PREFIX . "product_discount pdisc ON (p.product_id = pdisc.product_id)";
}
if ($post_var['d_cust_group_filter']!="any") { // cusomer group
if ($plus_where=="") { $prfx=" WHERE "; } else { $prfx=" AND "; }
$plus_where.=$prfx."pdisc.customer_group_id = '" . (int)$post_var['d_cust_group_filter'] . "'";
}
if ($post_var['d_price_mmarese']!="") { // greater than or equal to
if ($plus_where=="") { $prfx=" WHERE "; } else { $prfx=" AND "; }
$plus_where.=$prfx."pdisc.price >= '" . (float)$post_var['d_price_mmarese'] . "'";
}
if ($post_var['d_price_mmicse']!="") { // less than or equal to
if ($plus_where=="") { $prfx=" WHERE "; } else { $prfx=" AND "; }
$plus_where.=$prfx."pdisc.price <= '" . (float)$post_var['d_price_mmicse'] . "'";
}
//

// special price
if ($post_var['s_price_mmarese']!="" OR $post_var['s_price_mmicse']!="" OR $post_var['s_cust_group_filter']!="any") {
$plus_join.=" LEFT JOIN " . DB_PREFIX . "product_special pspec ON (p.product_id = pspec.product_id)";
}
if ($post_var['s_cust_group_filter']!="any") { // cusomer group
if ($plus_where=="") { $prfx=" WHERE "; } else { $prfx=" AND "; }
$plus_where.=$prfx."pspec.customer_group_id = '" . (int)$post_var['s_cust_group_filter'] . "'";
}
if ($post_var['s_price_mmarese']!="") { // greater than or equal to
if ($plus_where=="") { $prfx=" WHERE "; } else { $prfx=" AND "; }
$plus_where.=$prfx."pspec.price >= '" . (float)$post_var['s_price_mmarese'] . "'";
}
if ($post_var['s_price_mmicse']!="") { // less than or equal to
if ($plus_where=="") { $prfx=" WHERE "; } else { $prfx=" AND "; }
$plus_where.=$prfx."pspec.price <= '" . (float)$post_var['s_price_mmicse'] . "'";
}
//

if ($post_var['tax_class_filter']!="any") { // tax class
if ($plus_where=="") { $prfx=" WHERE "; } else { $prfx=" AND "; }
$plus_where.=$prfx."p.tax_class_id = '" . (int)$post_var['tax_class_filter'] . "'";
}

if ($post_var['stock_mmarese']!="") { // stock greater than or equal to
if ($plus_where=="") { $prfx=" WHERE "; } else { $prfx=" AND "; }
$plus_where.=$prfx."p.quantity >= '" . (int)$post_var['stock_mmarese'] . "'";
}

if ($post_var['stock_mmicse']!="") { // stock less than or equal to
if ($plus_where=="") { $prfx=" WHERE "; } else { $prfx=" AND "; }
$plus_where.=$prfx."p.quantity <= '" . (int)$post_var['stock_mmicse'] . "'";
}

if ($post_var['min_q_mmarese']!="") { // Minimum Quantity greater than or equal to
if ($plus_where=="") { $prfx=" WHERE "; } else { $prfx=" AND "; }
$plus_where.=$prfx."p.minimum >= '" . (int)$post_var['min_q_mmarese'] . "'";
}

if ($post_var['min_q_mmicse']!="") { // Minimum Quantity less than or equal to
if ($plus_where=="") { $prfx=" WHERE "; } else { $prfx=" AND "; }
$plus_where.=$prfx."p.minimum <= '" . (int)$post_var['min_q_mmicse'] . "'";
}

if ($post_var['stock_status_filter']!="any") { // Subtract Stock
if ($plus_where=="") { $prfx=" WHERE "; } else { $prfx=" AND "; }
$plus_where.=$prfx."p.stock_status_id = '" . (int)$post_var['stock_status_filter'] . "'";
}

if ($post_var['subtract_filter']!="any") { // Out Of Stock Status
if ($plus_where=="") { $prfx=" WHERE "; } else { $prfx=" AND "; }
$plus_where.=$prfx."p.subtract = '" . (int)$post_var['subtract_filter'] . "'";
}

if ($post_var['shipping_filter']!="any") { // Requires Shipping
if ($plus_where=="") { $prfx=" WHERE "; } else { $prfx=" AND "; }
$plus_where.=$prfx."p.shipping = '" . (int)$post_var['shipping_filter'] . "'";
}

if ($post_var['date_mmarese']!="") { // Date Available greater than or equal to
if ($plus_where=="") { $prfx=" WHERE "; } else { $prfx=" AND "; }
$plus_where.=$prfx."p.date_available >= '" . $this->db->escape($post_var['date_mmarese']) . "'";
}

if ($post_var['date_mmicse']!="") { // Date Available less than or equal to
if ($plus_where=="") { $prfx=" WHERE "; } else { $prfx=" AND "; }
$plus_where.=$prfx."p.date_available <= '" . $this->db->escape($post_var['date_mmicse']) . "'";
}

if ($post_var['date_added_mmarese']!="") { // Date added greater than or equal to
if ($plus_where=="") { $prfx=" WHERE "; } else { $prfx=" AND "; }
$plus_where.=$prfx."p.date_added >= '" . $this->db->escape($post_var['date_added_mmarese']) . "'";
}

if ($post_var['date_added_mmicse']!="") { // Date added less than or equal to
if ($plus_where=="") { $prfx=" WHERE "; } else { $prfx=" AND "; }
$plus_where.=$prfx."p.date_added <= '" . $this->db->escape($post_var['date_added_mmicse']) . "'";
}

if ($post_var['date_modified_mmarese']!="") { // Date modified greater than or equal to
if ($plus_where=="") { $prfx=" WHERE "; } else { $prfx=" AND "; }
$plus_where.=$prfx."p.date_modified >= '" . $this->db->escape($post_var['date_modified_mmarese']) . "'";
}

if ($post_var['date_modified_mmicse']!="") { // Date modified less than or equal to
if ($plus_where=="") { $prfx=" WHERE "; } else { $prfx=" AND "; }
$plus_where.=$prfx."p.date_modified <= '" . $this->db->escape($post_var['date_modified_mmicse']) . "'";
}

if ($post_var['prod_status']!="any") { // status
if ($plus_where=="") { $prfx=" WHERE "; } else { $prfx=" AND "; }
$plus_where.=$prfx."p.status = '" . (int)$post_var['prod_status'] . "'";
}

if ($post_var['store_filter']!="any") { // store
$plus_join.=" LEFT JOIN " . DB_PREFIX . "product_to_store pts ON (p.product_id = pts.product_id)";
if ($plus_where=="") { $prfx=" WHERE "; } else { $prfx=" AND "; }
$plus_where.=$prfx."pts.store_id = '" . (int)$post_var['store_filter'] . "'";
}

if ($post_var['filter_attr']!="any") { // attribute
$plus_join.=" LEFT JOIN " . DB_PREFIX . "product_attribute pattr ON (p.product_id = pattr.product_id)";
if ($plus_where=="") { $prfx=" WHERE "; } else { $prfx=" AND "; }
$plus_where.=$prfx."pattr.attribute_id = '" . (int)$post_var['filter_attr'] . "'";
}

if ($post_var['filter_opti']!="any") { // option
$plus_join.=" LEFT JOIN " . DB_PREFIX . "product_option po ON (p.product_id = po.product_id)";
if ($plus_where=="") { $prfx=" WHERE "; } else { $prfx=" AND "; }
$plus_where.=$prfx."po.option_id = '" . (int)$post_var['filter_opti'] . "'";
}

if ($post_var['filter_attr_val']!="") { // attribute value (text)
if ($post_var['filter_attr']=="any") { $plus_join.=" LEFT JOIN " . DB_PREFIX . "product_attribute pattr ON (p.product_id = pattr.product_id)"; }
if ($plus_where=="") { $prfx=" WHERE "; } else { $prfx=" AND "; }
$plus_where.=$prfx."pattr.text LIKE '%" . $this->db->escape($post_var['filter_attr_val']) . "%'";
}

if ($post_var['filter_opti_val']!="any") { // option value
$plus_join.=" LEFT JOIN " . DB_PREFIX . "product_option_value pov ON (p.product_id = pov.product_id)";
if ($plus_where=="") { $prfx=" WHERE "; } else { $prfx=" AND "; }
$plus_where.=$prfx."pov.option_value_id = '" . (int)$post_var['filter_opti_val'] . "'";
}

if ($post_var['filter_name']!="") { // part of name
if ($plus_where=="") { $prfx=" WHERE "; } else { $prfx=" AND "; }
if(version_compare(VERSION, '1.5.4.1', '>')) {
	$plus_where.=$prfx."pd.name LIKE '%" . $this->db->escape($post_var['filter_name']) . "%'";
	} elseif (version_compare(VERSION, '1.5.1.2', '>')) {
	$plus_where.=$prfx."LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($post_var['filter_name'])) . "%'";
	} else {
	$plus_where.=$prfx."LCASE(pd.name) LIKE '%" . $this->db->escape(strtolower($post_var['filter_name'])) . "%'";
	}
}

if ($post_var['filter_model']!="") { // part of model
if ($plus_where=="") { $prfx=" WHERE "; } else { $prfx=" AND "; }
if(version_compare(VERSION, '1.5.4.1', '>')) {
	$plus_where.=$prfx."p.model LIKE '%" . $this->db->escape($post_var['filter_model']) . "%'";
	} elseif (version_compare(VERSION, '1.5.1.2', '>')) {
	$plus_where.=$prfx."LCASE(p.model) LIKE '%" . $this->db->escape(utf8_strtolower($post_var['filter_model'])) . "%'";
	} else {
	$plus_where.=$prfx."LCASE(p.model) LIKE '%" . $this->db->escape(strtolower($post_var['filter_model'])) . "%'";
	}
}

if ($post_var['filter_tag']!="") { // tag
if ($plus_where=="") { $prfx=" WHERE "; } else { $prfx=" AND "; }
if(version_compare(VERSION, '1.5.3.1', '>')) {
	$plus_where.=$prfx."LCASE(pd.tag) LIKE '%" . $this->db->escape(utf8_strtolower($post_var['filter_tag'])) . "%'";
	} else {
	$plus_join.=" LEFT JOIN " . DB_PREFIX . "product_tag ptag ON (p.product_id = ptag.product_id)";	
	$plus_where.=$prfx."LCASE(ptag.tag) LIKE '%" . $this->db->escape(utf8_strtolower($post_var['filter_tag'])) . "%'";
	}
}

} /// end data filters


if ($plus_where=="") { $prfx=" WHERE "; } else { $prfx=" AND "; }
$plus_where.=$prfx."pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

$final_query="SELECT p.product_id, p.model, p.image, p.price, p.quantity, p.status, p.date_added, p.date_modified, p.viewed, pd.name 
FROM " . DB_PREFIX . "product p 
LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
".$plus_join.$plus_where." GROUP BY p.product_id";

/*
$query_total = $this->db->query($final_query);
$product_total=count($query_total->rows);
*/

$total_prod_query="SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p 
LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
".$plus_join.$plus_where;
$query_total_tmp = $this->db->query($total_prod_query);
$product_total= $query_total_tmp->row['total'];


if (isset($post_var['max_prod_per_pag'])) {
	if ($post_var['max_prod_per_pag']!='' AND (int)$post_var['max_prod_per_pag']>0) {
		$max_prod_per_pag=(int)$post_var['max_prod_per_pag'];
	} else {
		$max_prod_per_pag=$this->config->get('config_admin_limit');
	}
} else {
	$max_prod_per_pag=$this->config->get('config_admin_limit');
}


		//////
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pd.name';
		}
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$url = '';
								
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
					
		$data['sort_name'] = $this->url->link('catalog/advprodfilter', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL');
		$data['sort_model'] = $this->url->link('catalog/advprodfilter', 'token=' . $this->session->data['token'] . '&sort=p.model' . $url, 'SSL');
		$data['sort_price'] = $this->url->link('catalog/advprodfilter', 'token=' . $this->session->data['token'] . '&sort=p.price' . $url, 'SSL');
		$data['sort_quantity'] = $this->url->link('catalog/advprodfilter', 'token=' . $this->session->data['token'] . '&sort=p.quantity' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('catalog/advprodfilter', 'token=' . $this->session->data['token'] . '&sort=p.status' . $url, 'SSL');
		$data['sort_product_id'] = $this->url->link('catalog/advprodfilter', 'token=' . $this->session->data['token'] . '&sort=p.product_id' . $url, 'SSL');
		$data['sort_date_added'] = $this->url->link('catalog/advprodfilter', 'token=' . $this->session->data['token'] . '&sort=p.date_added' . $url, 'SSL');
		$data['sort_date_modified'] = $this->url->link('catalog/advprodfilter', 'token=' . $this->session->data['token'] . '&sort=p.date_modified' . $url, 'SSL');
		$data['sort_viewed'] = $this->url->link('catalog/advprodfilter', 'token=' . $this->session->data['token'] . '&sort=p.viewed' . $url, 'SSL');
		$data['sort_order'] = $this->url->link('catalog/advprodfilter', 'token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}									
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
				
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $max_prod_per_pag;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/advprodfilter', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
		
		$data['sort'] = $sort;
		$data['order'] = $order;

		$data = array(
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $max_prod_per_pag,
			'limit'           => $max_prod_per_pag
		);

			$sort_data = array(
				'pd.name',
				'p.model',
				'p.price',
				'p.quantity',
				'p.status',
				'p.product_id',
				'p.date_added',
				'p.date_modified',
				'p.viewed',
				'p.sort_order'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$final_query .= " ORDER BY " . $data['sort'];	
			} else {
				$final_query .= " ORDER BY pd.name";	
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$final_query .= " DESC";
			} else {
				$final_query .= " ASC";
			}
		
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
			
				$final_query .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}	
		//////


$query = $this->db->query($final_query);

$arr_lista_prod_tmp = $query->rows;

// thumbs
if (count($arr_lista_prod_tmp)>0) {
	$this->load->model('tool/image');
	for ($i=0;$i<count($arr_lista_prod_tmp);$i++) {
		if ($arr_lista_prod_tmp[$i]['image'] && file_exists(DIR_IMAGE . $arr_lista_prod_tmp[$i]['image'])) {
			$arr_lista_prod_tmp[$i]['image'] = $this->model_tool_image->resize($arr_lista_prod_tmp[$i]['image'], 40, 40);
		} else {
			$arr_lista_prod_tmp[$i]['image'] = $this->model_tool_image->resize('no_image.jpg', 40, 40);
		}
	}
}


$data['arr_lista_prod'] = $arr_lista_prod_tmp;


//} /// end post


		$data['token'] = $this->session->data['token']; ////
		
		if (isset($this->session->data['error'])) {
    			$data['error_warning'] = $this->session->data['error'];
    
			unset($this->session->data['error']);
 		} elseif (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
		'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),     		
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $data['heading_title'],
		'href'      => $this->url->link('catalog/advprodfilter', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		
		
			$url = '';
					
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
		
		$data['action'] = $this->url->link('catalog/advprodfilter', 'token=' . $this->session->data['token'] . $url, 'SSL');

		/*$this->template = 'catalog/advprodfilter.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());*/
		
		$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
	
			$this->response->setOutput($this->load->view('catalog/advprodfilter.tpl', $data));
	}


  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'catalog/product')) {
      		$this->error['warning'] = $this->language->get('apftxt_error_permission');
    	}
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}
  	
  	private function validateCopy() {
    	if (!$this->user->hasPermission('modify', 'catalog/product')) {
      		$this->error['warning'] = $this->language->get('apftxt_error_permission');
    	}
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

}
?>
