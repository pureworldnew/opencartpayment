<?php
class ControllerShippingXshippingpro extends Controller {
	private $error = array(); 
	
	public function index() {   
	    
		@ini_set( "max_input_vars", 10000);
		$this->load->language('shipping/xshippingpro');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
		   
		   if(isset($this->request->post['action']) && $this->request->post['action']=='import') {
		       $this->import();
		       $this->response->redirect($this->url->link('shipping/xshippingpro', 'token=' . $this->session->data['token'], 'SSL'));
		   }
		  
			
			$save=array();
			$save['xshippingpro_status']=$this->request->post['xshippingpro_status'];
			$save['xshippingpro_group']=$this->request->post['xshippingpro_group'];
			$save['xshippingpro_group_limit']=$this->request->post['xshippingpro_group_limit'];
			$save['xshippingpro_heading']=$this->request->post['xshippingpro_heading'];
			$save['xshippingpro_sort_order']=$this->request->post['xshippingpro_sort_order'];
			$save['xshippingpro_desc_mail']=isset($this->request->post['xshippingpro_desc_mail'])?1:0;
			$save['xshippingpro_debug']=$this->request->post['xshippingpro_debug'];
			$save['xshippingpro_sorting']=$this->request->post['xshippingpro_sorting'];
			$save['xshippingpro_sub_group']=$this->request->post['xshippingpro_sub_group'];
			$save['xshippingpro_sub_group_limit']=$this->request->post['xshippingpro_sub_group_limit'];
			$save['xshippingpro_sub_group_name']=$this->request->post['xshippingpro_sub_group_name'];
			
			if(isset($this->request->post['xshippingpro']))
			$save['xshippingpro']=base64_encode(serialize($this->request->post['xshippingpro']));
			$this->model_setting_setting->editSetting('xshippingpro', $save);		
			$this->session->data['success'] = $this->language->get('text_success');	
			$this->response->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
		}
			
				
		$data['heading_title'] = $this->language->get('heading_title');

        $data['tab_rate'] = $this->language->get('tab_rate');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_order_total'] = $this->language->get('entry_order_total');
		$data['entry_order_weight'] = $this->language->get('entry_order_weight');
		$data['entry_quantity'] = $this->language->get('entry_quantity');
		$data['entry_to'] = $this->language->get('entry_to');
		$data['entry_order_hints'] = $this->language->get('entry_order_hints');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['ignore_modifier'] = $this->language->get('ignore_modifier');
		$data['tip_weight'] = $this->language->get('tip_weight');
		$data['tip_total'] = $this->language->get('tip_total');
		$data['tip_quantity'] = $this->language->get('tip_quantity');
		
		$data['entry_cost'] = $this->language->get('entry_cost');
		$data['entry_tax'] = $this->language->get('entry_tax');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_text_color'] = $this->language->get('entry_text_color');
		$data['entry_background_color'] = $this->language->get('entry_background_color');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['text_all'] = $this->language->get('text_all');
		$data['text_category'] = $this->language->get('text_category');
		$data['text_category_any'] = $this->language->get('text_category_any');
		$data['text_category_all'] = $this->language->get('text_category_all');
		$data['text_category_least'] = $this->language->get('text_category_least');
		$data['text_category_least_with_other'] = $this->language->get('text_category_least_with_other');       $data['text_category_except_other'] = $this->language->get('text_category_except_other');
		
		$data['text_grand_total'] = $this->language->get('text_grand_total');
		$data['text_category_except'] = $this->language->get('text_category_except');
		$data['text_category_exact'] = $this->language->get('text_category_exact');
		$data['entry_category'] = $this->language->get('entry_category');
		$data['entry_weight_include'] = $this->language->get('entry_weight_include');
		$data['entry_desc'] = $this->language->get('entry_desc');
		$data['text_select_all'] = $this->language->get('text_select_all');
		$data['text_unselect_all'] = $this->language->get('text_unselect_all');
		$data['text_any'] = $this->language->get('text_any');
        $data['module_status'] = $this->language->get('module_status');
        $data['text_heading'] = $this->language->get('text_heading');
        $data['entry_store'] = $this->language->get('entry_store');
        $data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
        $data['text_product'] = $this->language->get('text_product');
	    $data['text_product_any'] = $this->language->get('text_product_any');
	    $data['text_product_all'] = $this->language->get('text_product_all');
	    $data['text_product_least'] = $this->language->get('text_product_least');
	    $data['text_product_least_with_other'] = $this->language->get('text_product_least_with_other');
	    $data['text_product_exact'] = $this->language->get('text_product_exact');
	    $data['text_product_except'] = $this->language->get('text_product_except');
	    $data['text_product_except_other'] = $this->language->get('text_product_except_other');
	    $data['entry_product'] = $this->language->get('entry_product');
	    $data['text_debug'] = $this->language->get('text_debug');
	    
	   $data['text_description'] = $this->language->get('text_description');
	   $data['text_desc_estimate_popup'] = $this->language->get('text_desc_estimate_popup');
	   $data['text_desc_delivery_method'] = $this->language->get('text_desc_delivery_method');
	   $data['text_desc_confirmation'] = $this->language->get('text_desc_confirmation');
	   $data['text_desc_site_order_detail'] = $this->language->get('text_desc_site_order_detail');
	   $data['text_desc_admin_order_detail'] = $this->language->get('text_desc_admin_order_detail');
	   $data['text_desc_order_email'] = $this->language->get('text_desc_order_email');
	   $data['text_desc_order_invoice'] = $this->language->get('text_desc_order_invoice');
		
	   $data['text_manufacturer_rule'] = $this->language->get('text_manufacturer_rule');
	   $data['text_manufacturer_any'] = $this->language->get('text_manufacturer_any');
	   $data['text_manufacturer_all'] = $this->language->get('text_manufacturer_all');
	   $data['text_manufacturer_least'] = $this->language->get('text_manufacturer_least');
	   $data['text_manufacturer_least_with_other'] = $this->language->get('text_manufacturer_least_with_other');
	   $data['text_manufacturer_exact'] = $this->language->get('text_manufacturer_exact');
	   $data['text_manufacturer_except'] = $this->language->get('text_manufacturer_except');
	   $data['text_manufacturer_except_other'] = $this->language->get('text_manufacturer_except_other');
	   $data['tip_manufacturer_rule'] = $this->language->get('tip_manufacturer_rule');
		
	   $data['text_rate_total_method'] = $this->language->get('text_rate_total_method');
	   $data['text_rate_sub_total_method'] = $this->language->get('text_rate_sub_total_method');
	   $data['text_rate_quantity_method'] = $this->language->get('text_rate_quantity_method');
	   $data['text_rate_weight_method'] = $this->language->get('text_rate_weight_method');
	   $data['text_rate_volume_method'] = $this->language->get('text_rate_volume_method');
		
	   $data['button_save'] = $this->language->get('button_save');
	   $data['button_cancel'] = $this->language->get('button_cancel');
       $data['button_save_continue'] = $this->language->get('button_save_continue');
       $data['tab_general'] = $this->language->get('tab_general');
	   $data['text_method_remove'] = $this->language->get('text_method_remove');
	   $data['text_method_copy'] = $this->language->get('text_method_copy');
                
		$data['text_group_shipping_mode'] = $this->language->get('text_group_shipping_mode');
		$data['text_no_grouping'] = $this->language->get('text_no_grouping');
		$data['text_lowest'] = $this->language->get('text_lowest');
		$data['text_highest'] = $this->language->get('text_highest');
		$data['text_average'] = $this->language->get('text_average');
		$data['text_sum'] = $this->language->get('text_sum');
		$data['text_and'] = $this->language->get('text_and');
		$data['text_add_new_method'] = $this->language->get('text_add_new_method');
		$data['text_remove'] = $this->language->get('text_remove');
		$data['text_general'] = $this->language->get('text_general');
		$data['text_criteria_setting'] = $this->language->get('text_criteria_setting');
		$data['text_category_product'] = $this->language->get('text_category_product');
		$data['text_price_setting'] = $this->language->get('text_price_setting');
		$data['text_others'] = $this->language->get('text_others');
		$data['text_zip_postal'] = $this->language->get('text_zip_postal');
		$data['text_enter_zip'] = $this->language->get('text_enter_zip');
		$data['text_zip_rule'] = $this->language->get('text_zip_rule');
		$data['text_zip_rule_inclusive'] = $this->language->get('text_zip_rule_inclusive');
		$data['text_zip_rule_exclusive'] = $this->language->get('text_zip_rule_exclusive');
		$data['text_coupon'] = $this->language->get('text_coupon');
		$data['text_enter_coupon'] = $this->language->get('text_enter_coupon');
		$data['text_coupon_rule'] = $this->language->get('text_coupon_rule');
		$data['text_coupon_rule_inclusive'] = $this->language->get('text_coupon_rule_inclusive');
		$data['text_coupon_rule_exclusive'] = $this->language->get('text_coupon_rule_exclusive');
		$data['text_rate_type'] = $this->language->get('text_rate_type');
		$data['text_rate_flat'] = $this->language->get('text_rate_flat');
		$data['text_rate_quantity'] = $this->language->get('text_rate_quantity');
		$data['text_rate_weight'] = $this->language->get('text_rate_weight');
		$data['text_rate_volume'] = $this->language->get('text_rate_volume'); 
		$data['text_rate_total_coupon'] = $this->language->get('text_rate_total_coupon');
		$data['text_rate_total'] = $this->language->get('text_rate_total');
		$data['text_rate_sub_total'] = $this->language->get('text_rate_sub_total');
		$data['text_unit_range'] = $this->language->get('text_unit_range');
		$data['text_delete_all'] = $this->language->get('text_delete_all');
		$data['text_csv_import'] = $this->language->get('text_csv_import');
		$data['text_start'] = $this->language->get('text_start');
		$data['text_end'] = $this->language->get('text_end');
		$data['text_cost'] = $this->language->get('text_cost');
		$data['text_qnty_block'] = $this->language->get('text_qnty_block');
		$data['text_add_new'] = $this->language->get('text_add_new');
		$data['text_final_cost'] = $this->language->get('text_final_cost');
		$data['text_final_single'] = $this->language->get('text_final_single');
		$data['text_final_cumulative'] = $this->language->get('text_final_cumulative');
		$data['text_percentage_related'] = $this->language->get('text_percentage_related');
		$data['text_percent_sub_total'] = $this->language->get('text_percent_sub_total');
		$data['text_percent_total'] = $this->language->get('text_percent_total');
		$data['text_price_adjustment'] = $this->language->get('text_price_adjustment');
		$data['text_price_min'] = $this->language->get('text_price_min');
		$data['text_price_max'] = $this->language->get('text_price_max');
		$data['text_price_add'] = $this->language->get('text_price_add');
		$data['text_days_week'] = $this->language->get('text_days_week');
		$data['text_time_period'] = $this->language->get('text_time_period');
		$data['text_sunday'] = $this->language->get('text_sunday');
		$data['text_monday'] = $this->language->get('text_monday');
		$data['text_tuesday'] = $this->language->get('text_tuesday');
		$data['text_wednesday'] = $this->language->get('text_wednesday');
		$data['text_thursday'] = $this->language->get('text_thursday');
		$data['text_friday'] = $this->language->get('text_friday');
		$data['text_saturday'] = $this->language->get('text_saturday');
		$data['text_country'] = $this->language->get('text_country');
		
		$data['tip_weight_include'] = $this->language->get('tip_weight_include');
		$data['tip_sorting_own'] = $this->language->get('tip_sorting_own');
		$data['tip_text_color'] = $this->language->get('tip_text_color');
		$data['tip_background_color'] = $this->language->get('tip_background_color');
		$data['tip_status_own'] = $this->language->get('tip_status_own');
		$data['tip_store'] = $this->language->get('tip_store');
		$data['tip_geo'] = $this->language->get('tip_geo');
		$data['tip_manufacturer'] = $this->language->get('tip_manufacturer');
		$data['tip_customer_group'] = $this->language->get('tip_customer_group');
		$data['tip_zip'] = $this->language->get('tip_zip');
		$data['tip_coupon'] = $this->language->get('tip_coupon');
		$data['tip_category'] = $this->language->get('tip_category');
		$data['tip_product'] = $this->language->get('tip_product');
		$data['tip_rate_type'] = $this->language->get('tip_rate_type');
		$data['tip_cost'] = $this->language->get('tip_cost');
		$data['tip_unit_start'] = $this->language->get('tip_unit_start');
		$data['tip_unit_end'] = $this->language->get('tip_unit_end');
		$data['tip_unit_price'] = $this->language->get('tip_unit_price');
		$data['tip_unit_ppu'] = $this->language->get('tip_unit_ppu');
		$data['tip_single_commulative'] = $this->language->get('tip_single_commulative');
		$data['tip_percentage'] = $this->language->get('tip_percentage');
		$data['tip_price_adjust'] = $this->language->get('tip_price_adjust');
		$data['tip_day'] = $this->language->get('tip_day');
		$data['tip_time'] = $this->language->get('tip_time');
		$data['tip_heading'] = $this->language->get('tip_heading');
		$data['tip_status_global'] = $this->language->get('tip_status_global');
		$data['tip_sorting_global'] = $this->language->get('tip_sorting_global');
		$data['tip_grouping'] = $this->language->get('tip_grouping');
		$data['tip_debug'] = $this->language->get('tip_debug');
		$data['tip_desc'] = $this->language->get('tip_desc');
		$data['tip_import'] = $this->language->get('tip_import');
		$data['tip_postal_code'] = $this->language->get('tip_postal_code');
		$data['tip_multi_category'] = $this->language->get('tip_multi_category');
		$data['text_multi_category'] = $this->language->get('text_multi_category');
		$data['entry_all'] = $this->language->get('entry_all');
		$data['entry_any'] = $this->language->get('entry_any');
		$data['tip_group_limit'] = $this->language->get('tip_group_limit');
		$data['text_group_limit'] = $this->language->get('text_group_limit');
		$data['no_unit_row'] = $this->language->get('no_unit_row');
		
		 $data['text_partial'] = $this->language->get('text_partial');
		 $data['tip_partial'] = $this->language->get('tip_partial');
		 $data['text_yes'] = $this->language->get('text_yes');
		 $data['text_no'] = $this->language->get('text_no');
		 $data['text_additional'] = $this->language->get('text_additional');
		 $data['tip_additional'] = $this->language->get('tip_additional');
         
		$data['text_dimensional_weight'] = $this->language->get('text_dimensional_weight');
		$data['text_dimensional_factor'] = $this->language->get('text_dimensional_factor');
		$data['text_dimensional_overrule'] = $this->language->get('text_dimensional_overrule');
		$data['text_dimensional_weight_method'] = $this->language->get('text_dimensional_weight_method'); 
		$data['text_logo'] = $this->language->get('text_logo');
		$data['tip_text_logo'] = $this->language->get('tip_text_logo');    
		
		 $data['text_sort_manual'] = $this->language->get('text_sort_manual'); 
		 $data['text_sort_type'] = $this->language->get('text_sort_type'); 
		 $data['text_sort_price_asc'] = $this->language->get('text_sort_price_asc'); 
		 $data['text_sort_price_desc'] = $this->language->get('text_sort_price_desc'); 
		 $data['tip_text_sort_type'] = $this->language->get('tip_text_sort_type'); 
		 $data['tab_general_global'] = $this->language->get('tab_general_global');  
		 $data['tab_general_general'] = $this->language->get('tab_general_general'); 
		 
		 $data['text_export'] = $this->language->get('text_export');   
		 $data['tip_export'] = $this->language->get('tip_export');   
		 $data['text_import'] = $this->language->get('text_import');   
		 $data['tip_import'] = $this->language->get('tip_import');  
		 $data['tab_import_export'] = $this->language->get('tab_import_export');    
		 $data['error_import'] = $this->language->get('error_import'); 
		 $data['text_mask_price'] = $this->language->get('text_mask_price');     
		 
		  $data['text_percent_shipping'] = $this->language->get('text_percent_shipping'); 
		 $data['text_percent_sub_total_shipping'] = $this->language->get('text_percent_sub_total_shipping'); 
		 $data['text_percent_total_shipping'] = $this->language->get('text_percent_total_shipping'); 
		 $data['tip_group_name'] = $this->language->get('tip_group_name');
		 $data['entry_group_name'] = $this->language->get('entry_group_name'); 
		 
		 $data['text_equation'] = $this->language->get('text_equation'); 
		 $data['tip_equation'] = $this->language->get('tip_equation'); 
		 $data['text_equation_help'] = $this->language->get('text_equation_help');          
		 	
 	    if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
	
	    if (isset($this->session->data['warning'])) {
			$data['error_warning'] = $this->session->data['warning'];
			unset($this->session->data['warning']);
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
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL')
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_shipping'),
			'href'      => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL')
   		);
		
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('shipping/xshippingpro', 'token=' . $this->session->data['token'], 'SSL')
   		);
		
		$data['action'] = $this->url->link('shipping/xshippingpro', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('extension/xshippingpro', 'token=' . $this->session->data['token'], 'SSL');
		$data['export'] = $this->url->link('shipping/xshippingpro/export', 'token=' . $this->session->data['token'], 'SSL');
		
		$xshippingpro=$this->config->get('xshippingpro');
		if ($xshippingpro) {
				
			 $xshippingpro=unserialize(base64_decode($xshippingpro)); 
		}
		
		if(!is_array($xshippingpro))$xshippingpro=array();
		$data['xshippingpro'] = $xshippingpro;
		
		$data['token']=$this->session->data['token'];
		
		
		 
		 if (isset($this->request->post['xshippingpro_status'])) {
			   $data['xshippingpro_status'] = $this->request->post['xshippingpro_status'];
         } else {
              $data['xshippingpro_status'] = $this->config->get('xshippingpro_status');
        }
		
		if (isset($this->request->post['xshippingpro_sort_order'])) {
			$data['xshippingpro_sort_order'] = $this->request->post['xshippingpro_sort_order'];
        } else {
             $data['xshippingpro_sort_order'] = $this->config->get('xshippingpro_sort_order');
        }
         
        if (isset($this->request->post['xshippingpro_group'])) {
			$data['xshippingpro_group'] = $this->request->post['xshippingpro_group'];
         } else {
            $data['xshippingpro_group'] = $this->config->get('xshippingpro_group');
         }
		 
		 if (isset($this->request->post['xshippingpro_group_limit'])) {
			$data['xshippingpro_group_limit'] = $this->request->post['xshippingpro_group_limit'];
         } else {
            $data['xshippingpro_group_limit'] = $this->config->get('xshippingpro_group_limit');
         }
         
         if (isset($this->request->post['xshippingpro_sorting'])) {
			$data['xshippingpro_sorting'] = $this->request->post['xshippingpro_sorting'];
          } else {
            $data['xshippingpro_sorting'] = $this->config->get('xshippingpro_sorting');
         }
		         
        if (isset($this->request->post['xshippingpro_heading'])) {
			$data['xshippingpro_heading'] = $this->request->post['xshippingpro_heading'];
         } else {
            $data['xshippingpro_heading'] = $this->config->get('xshippingpro_heading');
         }
         
		 
		 if (isset($this->request->post['xshippingpro_desc_mail'])) {
			$data['xshippingpro_desc_mail'] = isset($this->request->post['xshippingpro_desc_mail'])?1:0;
         } else {
            $data['xshippingpro_desc_mail'] = $this->config->get('xshippingpro_desc_mail');
         } 
		    
        
        if (isset($this->request->post['xshippingpro_debug'])) {
			$data['xshippingpro_debug'] = $this->request->post['xshippingpro_debug'];
         } else {
            $data['xshippingpro_debug'] = $this->config->get('xshippingpro_debug');
         }
         
        if (isset($this->request->post['xshippingpro_sub_group'])) {
			$data['xshippingpro_sub_group'] = $this->request->post['xshippingpro_sub_group'];
          } else {
            $data['xshippingpro_sub_group'] = $this->config->get('xshippingpro_sub_group');
         }
         
         if (isset($this->request->post['xshippingpro_sub_group_limit'])) {
			$data['xshippingpro_sub_group_limit'] = $this->request->post['xshippingpro_sub_group_limit'];
          } else {
            $data['xshippingpro_sub_group_limit'] = $this->config->get('xshippingpro_sub_group_limit');
         }
         
         if (isset($this->request->post['xshippingpro_sub_group_name'])) {
			$data['xshippingpro_sub_group_name'] = $this->request->post['xshippingpro_sub_group_name'];
          } else {
            $data['xshippingpro_sub_group_name'] = $this->config->get('xshippingpro_sub_group_name');
         }
                
                
		$this->load->model('localisation/tax_class');
		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
		
		$this->load->model('localisation/geo_zone');
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
                
       $this->load->model('setting/store');
		$data['stores'] = $this->model_setting_store->getStores();
       $data['stores']=  array_merge(array(array('store_id'=>0,'name'=>$this->language->get('store_default'))),$data['stores']);
                
         
		 
		  if(intval(str_replace('.','',VERSION)) >=  2101) {
           $this->load->model('customer/customer_group');
		   $data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();
		 } else {
		   $this->load->model('sale/customer_group');
		   $data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		 }
                
        $this->load->model('catalog/manufacturer');
		 $data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();
                
		 $this->load->model('localisation/language');
		 $data['languages'] = $this->model_localisation_language->getLanguages();
		 $data['language_id']=$this->config->get('config_language_id');
		 
		 $this->load->model('localisation/country');
		 $data['countries'] = $this->model_localisation_country->getCountries();
                
        $data['group_options']=array('no_group'=>$this->language->get('text_no_grouping'),'lowest'=>$this->language->get('text_lowest'),'highest'=>$this->language->get('text_highest'),'average'=>$this->language->get('text_average'),'sum'=>$this->language->get('text_sum'),'and'=>$this->language->get('text_and'));
        $data['sort_options']=array('1'=>$this->language->get('text_sort_manual'),'2'=>$this->language->get('text_sort_price_asc'),'3'=>$this->language->get('text_sort_price_desc'));       
               
        $data['text_group_none']=$this->language->get('text_group_none');
        $data['entry_group']=$this->language->get('entry_group'); 
        $data['entry_group_tip']=$this->language->get('entry_group_tip');  
        $data['text_group_name']=$this->language->get('text_group_name'); 
        $data['text_group_type']=$this->language->get('text_group_type');   
        $data['text_method_group']=$this->language->get('text_method_group'); 
        $data['tip_method_group']=$this->language->get('tip_method_group');
        
        $data['xshippingpro_sub_groups_count']=10;
               
		 $data['form_data']=$this->getFormData($data);
	
		 $data['header'] = $this->load->controller('common/header');
		 $data['column_left'] = $this->load->controller('common/column_left');
		 $data['footer'] = $this->load->controller('common/footer');
		
		 $this->response->setOutput($this->load->view('shipping/xshippingpro.tpl', $data));
	}
      
      public function quick_save(){
         
        $this->load->model('setting/setting');
         $json=array();
         
         if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			 $save=array();
			
			 $save['xshippingpro_status']=$this->request->post['xshippingpro_status'];
			 $save['xshippingpro_group']=$this->request->post['xshippingpro_group'];
			 $save['xshippingpro_group_limit']=$this->request->post['xshippingpro_group_limit'];
			 $save['xshippingpro_heading']=$this->request->post['xshippingpro_heading'];
			 $save['xshippingpro_sort_order']=$this->request->post['xshippingpro_sort_order']; 
			 $save['xshippingpro_desc_mail']=isset($this->request->post['xshippingpro_desc_mail'])?1:0;
			 $save['xshippingpro_debug']=$this->request->post['xshippingpro_debug'];
			 $save['xshippingpro_sorting']=$this->request->post['xshippingpro_sorting'];
			 $save['xshippingpro_sub_group']=$this->request->post['xshippingpro_sub_group'];
			 $save['xshippingpro_sub_group_limit']=$this->request->post['xshippingpro_sub_group_limit'];
			 $save['xshippingpro_sub_group_name']=$this->request->post['xshippingpro_sub_group_name'];
			 
			 if(isset($this->request->post['xshippingpro']))
			 $save['xshippingpro']=base64_encode(serialize($this->request->post['xshippingpro']));
			 $this->model_setting_setting->editSetting('xshippingpro', $save);
			 $json['success']=1;	
			 	
		  } else{
			  
		     $json['error']=$this->language->get('error_permission');
		  }
		  
		  $this->response->addHeader('Content-Type: application/json');
		  $this->response->setOutput(json_encode($json)); 
         
      } 
      
   public function export(){
     
        $export = array();
        
        if(isset($this->request->get['no'])) {
          $this->exportMethod($this->request->get['no']);
        }
        
        $export['xshippingpro']=$this->config->get('xshippingpro');
		 
		 if (isset($this->request->post['xshippingpro_status'])) {
			   $export['xshippingpro_status'] = $this->request->post['xshippingpro_status'];
         } else {
              $export['xshippingpro_status'] = $this->config->get('xshippingpro_status');
        }
		
		if (isset($this->request->post['xshippingpro_sort_order'])) {
			$export['xshippingpro_sort_order'] = $this->request->post['xshippingpro_sort_order'];
        } else {
             $export['xshippingpro_sort_order'] = $this->config->get('xshippingpro_sort_order');
        }
         
        if (isset($this->request->post['xshippingpro_group'])) {
			$export['xshippingpro_group'] = $this->request->post['xshippingpro_group'];
         } else {
            $export['xshippingpro_group'] = $this->config->get('xshippingpro_group');
         }
		 
		 if (isset($this->request->post['xshippingpro_group_limit'])) {
			$export['xshippingpro_group_limit'] = $this->request->post['xshippingpro_group_limit'];
         } else {
            $export['xshippingpro_group_limit'] = $this->config->get('xshippingpro_group_limit');
         }
         
         if (isset($this->request->post['xshippingpro_sorting'])) {
			$export['xshippingpro_sorting'] = $this->request->post['xshippingpro_sorting'];
          } else {
            $export['xshippingpro_sorting'] = $this->config->get('xshippingpro_sorting');
         }
		         
        if (isset($this->request->post['xshippingpro_heading'])) {
			$export['xshippingpro_heading'] = $this->request->post['xshippingpro_heading'];
         } else {
            $export['xshippingpro_heading'] = $this->config->get('xshippingpro_heading');
         }
         
		 
		 if (isset($this->request->post['xshippingpro_desc_mail'])) {
			$export['xshippingpro_desc_mail'] = isset($this->request->post['xshippingpro_desc_mail'])?1:0;
         } else {
            $export['xshippingpro_desc_mail'] = $this->config->get('xshippingpro_desc_mail');
         } 
		    
        
        if (isset($this->request->post['xshippingpro_debug'])) {
			$export['xshippingpro_debug'] = $this->request->post['xshippingpro_debug'];
         } else {
            $export['xshippingpro_debug'] = $this->config->get('xshippingpro_debug');
         }
         
        if (isset($this->request->post['xshippingpro_sub_group'])) {
			$export['xshippingpro_sub_group'] = $this->request->post['xshippingpro_sub_group'];
          } else {
            $export['xshippingpro_sub_group'] = $this->config->get('xshippingpro_sub_group');
         }
         
         if (isset($this->request->post['xshippingpro_sub_group_limit'])) {
			$export['xshippingpro_sub_group_limit'] = $this->request->post['xshippingpro_sub_group_limit'];
          } else {
            $export['xshippingpro_sub_group_limit'] = $this->config->get('xshippingpro_sub_group_limit');
         }
       $out = base64_encode(serialize($export));  
       header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
       header("Content-Length: " . strlen($out));
       header("Content-type: text/txt");
       header("Content-Disposition: attachment; filename=xshippingpro.txt");
       echo $out;
       exit;
   } 
   
   public function exportMethod($no) {
	   
	    $xshippingpro=$this->config->get('xshippingpro');
		if ($xshippingpro) {
				
			 $xshippingpro=unserialize(base64_decode($xshippingpro)); 
		}
		
		if(!is_array($xshippingpro))$xshippingpro=array();
	   
	    $csv_terminated = "\n";
		$csv_separator = ",";
		$csv_enclosed = '"';
		$csv_escaped = "\\";
		$out="";
		
		$heading = array('Start','End','Cost','Per Unit Block','Allow Partial');
		foreach($heading as $head)
		{		
			$out .= $csv_enclosed . str_replace($csv_enclosed, $csv_escaped . $csv_enclosed,			
			stripslashes($head)) . $csv_enclosed;			
			$out .= $csv_separator;
		
		}
		
		$out= rtrim($out,$csv_separator);		
		$out .= $csv_terminated;
	   
	   $language_id = $this->config->get('config_language_id');
	   $method_name = 'Untitled';
	   
	   if(!isset($xshippingpro['name']))$xshippingpro['name']=array();
	   if(!is_array($xshippingpro['name']))$xshippingpro['name']=array();
	   foreach($xshippingpro['name'] as $no_of_tab=>$names){
	       
	       if($no!=$no_of_tab) continue;
	       
	       $method_name = isset($names[$language_id])? $names[$language_id] : 'Untitled';;
	        
	       if(!is_array($xshippingpro['rate_start'][$no_of_tab]))$xshippingpro['rate_start'][$no_of_tab]=array();
		   if(!is_array($xshippingpro['rate_end'][$no_of_tab]))$xshippingpro['rate_end'][$no_of_tab]=array();
		   if(!is_array($xshippingpro['rate_total'][$no_of_tab]))$xshippingpro['rate_total'][$no_of_tab]=array();
		   if(!is_array($xshippingpro['rate_block'][$no_of_tab]))$xshippingpro['rate_block'][$no_of_tab]=array();
		   
		   foreach ($xshippingpro['rate_start'][$no_of_tab] as $inc=>$rate_start) { 
					 
					  if(!isset($xshippingpro['rate_partial'][$no_of_tab][$inc]))$xshippingpro['rate_partial'][$no_of_tab][$inc]='0'; 
					 
					 $out .= $csv_enclosed . str_replace($csv_enclosed, $csv_escaped . $csv_enclosed,			
			           stripslashes($rate_start)) . $csv_enclosed;			
			         $out .= $csv_separator;
			         
			         $out .= $csv_enclosed . str_replace($csv_enclosed, $csv_escaped . $csv_enclosed,			
			           stripslashes($xshippingpro['rate_end'][$no_of_tab][$inc])) . $csv_enclosed;			
			         $out .= $csv_separator;
			         
			         $out .= $csv_enclosed . str_replace($csv_enclosed, $csv_escaped . $csv_enclosed,			
			           stripslashes($xshippingpro['rate_total'][$no_of_tab][$inc])) . $csv_enclosed;			
			         $out .= $csv_separator;
			         
			         $out .= $csv_enclosed . str_replace($csv_enclosed, $csv_escaped . $csv_enclosed,			
			           stripslashes($xshippingpro['rate_block'][$no_of_tab][$inc])) . $csv_enclosed;			
			         $out .= $csv_separator;
			         
			         $out .= $csv_enclosed . str_replace($csv_enclosed, $csv_escaped . $csv_enclosed,			
			           stripslashes($xshippingpro['rate_partial'][$no_of_tab][$inc])) . $csv_enclosed;			
			         
			         $out .= $csv_terminated;
            }
            break;
	   
	   }
	   
	   $filename = str_replace(array('#',' ',"'",'"','!','@','#','$','%','^','&','*','(',')','~','`'),'_',$method_name).'.csv'; 
	   
	   header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
       header("Content-Length: " . strlen($out));
       // Output to browser with appropriate mime type, you choose ;)
       header("Content-type: text/x-csv");
       //header("Content-type: text/csv");
       //header("Content-type: application/csv");
       header("Content-Disposition: attachment; filename=$filename");
       echo $out;
       exit;
	
	}
   
   public function import(){
         
         $this->load->model('setting/setting');
         $success = false;
     
         if ($this->request->server['REQUEST_METHOD'] == 'POST' && is_uploaded_file($this->request->files['file_import']['tmp_name']) && file_exists($this->request->files['file_import']['tmp_name'])) {
			
			 $import_data = file_get_contents($this->request->files['file_import']['tmp_name']);
			 if($import_data) {
			 
			   $import_data=@unserialize(@base64_decode($import_data));

			   if(is_array($import_data) && $import_data['xshippingpro_status']) {
			
			 		$save=array();
			 		$save['xshippingpro_status']=$import_data['xshippingpro_status'];
			 		$save['xshippingpro_group']=$import_data['xshippingpro_group'];
					$save['xshippingpro_group_limit']=$import_data['xshippingpro_group_limit'];
			 		$save['xshippingpro_heading']=$import_data['xshippingpro_heading'];
			 		$save['xshippingpro_sort_order']=$import_data['xshippingpro_sort_order']; 
			 		$save['xshippingpro_desc_mail']=isset($import_data['xshippingpro_desc_mail'])?1:0;
					$save['xshippingpro_debug']=$import_data['xshippingpro_debug'];
			 		$save['xshippingpro_sorting']=$import_data['xshippingpro_sorting'];
			 		$save['xshippingpro_sub_group']=$import_data['xshippingpro_sub_group'];
			 		$save['xshippingpro_sub_group_limit']=$import_data['xshippingpro_sub_group_limit'];
			 
					$save['xshippingpro']=$import_data['xshippingpro'];
			 		$this->model_setting_setting->editSetting('xshippingpro', $save);
			 		$success = true;
			 		
			     }
			   }		
			 	
		       } 
		  
		  if($success) {
		    $this->session->data['success'] = $this->language->get('text_success');
		  } else {
		     $this->session->data['warning'] = $this->language->get('error_import');
		  }
		
      }    
            
   public function csv_upload(){
          
		   ini_set('auto_detect_line_endings', true);
          $this->load->language('shipping/xshippingpro');
          
          $json = array();
          if (!empty($this->request->files['file']['name'])) {
               $filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8')));
               
               $allowed=  array('csv');
               if (!in_array(substr(strrchr($filename, '.'), 1), $allowed)) {
		  $json['error'] = $this->language->get('error_filetype');
	       }
               if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
		 $json['error'] = $this->language->get('error_partial');
	       }
          }
          else{
             $json['error']=$this->language->get('error_upload');  
          }
          
          if (!$json && is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])) {
              
              $isFound=false;
              $json['data']=array();
              if (($handle = fopen($this->request->files['file']['tmp_name'], "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                       $start=$data[0];  
                       $end=$data[1]; 
                       $cost=$data[2]; 
                       $pg=isset($data[3])?$data[3]:0; 
					   $pa=isset($data[4])?$data[4]:0; 
                       if(is_numeric($start) && is_numeric($end) && is_numeric($cost)){
                          $json['data'][]=array('start'=>(float)$start,'end'=>(float)$end,'cost'=>(float)$cost,'pg'=>(float)$pg,'pa'=>(int)$pa); 
                          $isFound=true;
                       }
                    }
                    fclose($handle);
                }
             if(!$isFound)$json['error']=$this->language->get('error_no_data');     
          }
          
		   $this->response->addHeader('Content-Type: application/json');
          $this->response->setOutput(json_encode($json)); 
          
        }

   private function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/xshippingpro')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
	
	public function copyMehthod()
	{
	  $tabId=$this->requrest->get['tabId'];
	}

	private function getFormData($data)
	{
	   $this->load->model('catalog/category');
	   $this->load->model('catalog/product');
	   
	   $return=''; 
	   if(!isset($data['xshippingpro']['name']))$data['xshippingpro']['name']=array();
	   if(!is_array($data['xshippingpro']['name']))$data['xshippingpro']['name']=array();
	   foreach($data['xshippingpro']['name'] as $no_of_tab=>$names){
	   	 
		 if(!isset($data['xshippingpro']['customer_group'][$no_of_tab]))$data['xshippingpro']['customer_group'][$no_of_tab]=array();
		 if(!isset($data['xshippingpro']['geo_zone_id'][$no_of_tab]))$data['xshippingpro']['geo_zone_id'][$no_of_tab]=array();
		 if(!isset($data['xshippingpro']['product_category'][$no_of_tab]))$data['xshippingpro']['product_category'][$no_of_tab]=array();
		 if(!isset($data['xshippingpro']['product_product'][$no_of_tab]))$data['xshippingpro']['product_product'][$no_of_tab]=array();
		 if(!isset($data['xshippingpro']['store'][$no_of_tab]))$data['xshippingpro']['store'][$no_of_tab]=array();
		 if(!isset($data['xshippingpro']['manufacturer'][$no_of_tab]))$data['xshippingpro']['manufacturer'][$no_of_tab]=array();
		 if(!isset($data['xshippingpro']['days'][$no_of_tab]))$data['xshippingpro']['days'][$no_of_tab]=array();
		 if(!isset($data['xshippingpro']['rate_start'][$no_of_tab]))$data['xshippingpro']['rate_start'][$no_of_tab]=array();
		 if(!isset($data['xshippingpro']['rate_end'][$no_of_tab]))$data['xshippingpro']['rate_end'][$no_of_tab]=array();
		 if(!isset($data['xshippingpro']['rate_total'][$no_of_tab]))$data['xshippingpro']['rate_total'][$no_of_tab]=array();
		 if(!isset($data['xshippingpro']['rate_block'][$no_of_tab]))$data['xshippingpro']['rate_block'][$no_of_tab]=array();
		 if(!isset($data['xshippingpro']['country'][$no_of_tab]))$data['xshippingpro']['country'][$no_of_tab]=array();
		 
		 if(!is_array($data['xshippingpro']['customer_group'][$no_of_tab]))$data['xshippingpro']['customer_group'][$no_of_tab]=array();
		 if(!is_array($data['xshippingpro']['geo_zone_id'][$no_of_tab]))$data['xshippingpro']['geo_zone_id'][$no_of_tab]=array();
		 if(!is_array($data['xshippingpro']['product_category'][$no_of_tab]))$data['xshippingpro']['product_category'][$no_of_tab]=array();
		 if(!is_array($data['xshippingpro']['product_product'][$no_of_tab]))$data['xshippingpro']['product_product'][$no_of_tab]=array();
		 if(!is_array($data['xshippingpro']['store'][$no_of_tab]))$data['xshippingpro']['store'][$no_of_tab]=array();
		 if(!is_array($data['xshippingpro']['manufacturer'][$no_of_tab]))$data['xshippingpro']['manufacturer'][$no_of_tab]=array();
		 if(!is_array($data['xshippingpro']['days'][$no_of_tab]))$data['xshippingpro']['days'][$no_of_tab]=array();
		 if(!is_array($data['xshippingpro']['rate_start'][$no_of_tab]))$data['xshippingpro']['rate_start'][$no_of_tab]=array();
		 if(!is_array($data['xshippingpro']['rate_end'][$no_of_tab]))$data['xshippingpro']['rate_end'][$no_of_tab]=array();
		 if(!is_array($data['xshippingpro']['rate_total'][$no_of_tab]))$data['xshippingpro']['rate_total'][$no_of_tab]=array();
		 if(!is_array($data['xshippingpro']['rate_block'][$no_of_tab]))$data['xshippingpro']['rate_block'][$no_of_tab]=array();
		 if(!is_array($data['xshippingpro']['country'][$no_of_tab]))$data['xshippingpro']['country'][$no_of_tab]=array();
		 
		 if(!isset($data['xshippingpro']['inc_weight'][$no_of_tab]))$data['xshippingpro']['inc_weight'][$no_of_tab]='';
		 
		 if(!isset($data['xshippingpro']['dimensional_overfule'][$no_of_tab]))$data['xshippingpro']['dimensional_overfule'][$no_of_tab]='';
		 if(!isset($data['xshippingpro']['dimensional_factor'][$no_of_tab]) || !$data['xshippingpro']['dimensional_factor'][$no_of_tab])$data['xshippingpro']['dimensional_factor'][$no_of_tab]=5000;
		 
		 if(!is_array($names))$names=array();
		 
		 if(!isset($data['xshippingpro']['desc'][$no_of_tab]))$data['xshippingpro']['desc'][$no_of_tab]=array();
		 if(!is_array($data['xshippingpro']['desc'][$no_of_tab]))$data['xshippingpro']['desc'][$no_of_tab]=array();
		 
		  if(!isset($data['xshippingpro']['customer_group_all'][$no_of_tab]))$data['xshippingpro']['customer_group_all'][$no_of_tab]='';
		  if(!isset($data['xshippingpro']['geo_zone_all'][$no_of_tab]))$data['xshippingpro']['geo_zone_all'][$no_of_tab]='';
		  if(!isset($data['xshippingpro']['country_all'][$no_of_tab]))$data['xshippingpro']['country_all'][$no_of_tab]='';
		  if(!isset($data['xshippingpro']['store_all'][$no_of_tab]))$data['xshippingpro']['store_all'][$no_of_tab]='';
		  if(!isset($data['xshippingpro']['manufacturer_all'][$no_of_tab]))$data['xshippingpro']['manufacturer_all'][$no_of_tab]='';
		  if(!isset($data['xshippingpro']['postal_all'][$no_of_tab]))$data['xshippingpro']['postal_all'][$no_of_tab]='';
		  if(!isset($data['xshippingpro']['coupon_all'][$no_of_tab]))$data['xshippingpro']['coupon_all'][$no_of_tab]='';
		  if(!isset($data['xshippingpro']['postal'][$no_of_tab]))$data['xshippingpro']['postal'][$no_of_tab]='';
		  if(!isset($data['xshippingpro']['coupon'][$no_of_tab]))$data['xshippingpro']['coupon'][$no_of_tab]='';
		  if(!isset($data['xshippingpro']['postal_rule'][$no_of_tab]))$data['xshippingpro']['postal_rule'][$no_of_tab]='inclusive';
		  if(!isset($data['xshippingpro']['coupon_rule'][$no_of_tab]))$data['xshippingpro']['coupon_rule'][$no_of_tab]='inclusive';
		  if(!isset($data['xshippingpro']['time_start'][$no_of_tab]))$data['xshippingpro']['time_start'][$no_of_tab]='';
		  if(!isset($data['xshippingpro']['time_end'][$no_of_tab]))$data['xshippingpro']['time_end'][$no_of_tab]='';
		  if(!isset($data['xshippingpro']['rate_final'][$no_of_tab]))$data['xshippingpro']['rate_final'][$no_of_tab]='single';
		  if(!isset($data['xshippingpro']['rate_percent'][$no_of_tab]))$data['xshippingpro']['rate_percent'][$no_of_tab]='sub';
		  if(!isset($data['xshippingpro']['rate_min'][$no_of_tab]))$data['xshippingpro']['rate_min'][$no_of_tab]='';
		  if(!isset($data['xshippingpro']['rate_max'][$no_of_tab]))$data['xshippingpro']['rate_max'][$no_of_tab]='';
		  if(!isset($data['xshippingpro']['rate_add'][$no_of_tab]))$data['xshippingpro']['rate_add'][$no_of_tab]='';
		  
		  if(!isset($data['xshippingpro']['manufacturer_rule'][$no_of_tab]))$data['xshippingpro']['manufacturer_rule'][$no_of_tab]='2';
		  
		  if(!isset($data['xshippingpro']['multi_category'][$no_of_tab]))$data['xshippingpro']['multi_category'][$no_of_tab]='all';   
		  if(!isset($data['xshippingpro']['additional'][$no_of_tab]))$data['xshippingpro']['additional'][$no_of_tab]=0;
		  if(!isset($data['xshippingpro']['modifier_ignore'][$no_of_tab]))$data['xshippingpro']['modifier_ignore'][$no_of_tab]='';
		  if(!isset($data['xshippingpro']['logo'][$no_of_tab]))$data['xshippingpro']['logo'][$no_of_tab]='';
		  if(!isset($data['xshippingpro']['group'][$no_of_tab]))$data['xshippingpro']['group'][$no_of_tab]=0;
		  
		  if(!isset($data['xshippingpro']['order_total_start'][$no_of_tab]))$data['xshippingpro']['order_total_start'][$no_of_tab]=0;
		  if(!isset($data['xshippingpro']['order_total_end'][$no_of_tab]))$data['xshippingpro']['order_total_end'][$no_of_tab]=0;
		  if(!isset($data['xshippingpro']['weight_start'][$no_of_tab]))$data['xshippingpro']['weight_start'][$no_of_tab]=0;
		  if(!isset($data['xshippingpro']['weight_end'][$no_of_tab]))$data['xshippingpro']['weight_end'][$no_of_tab]=0;
		  if(!isset($data['xshippingpro']['quantity_start'][$no_of_tab]))$data['xshippingpro']['quantity_start'][$no_of_tab]=0;
		  if(!isset($data['xshippingpro']['quantity_end'][$no_of_tab]))$data['xshippingpro']['quantity_end'][$no_of_tab]=0;
		  if(!isset($data['xshippingpro']['mask'][$no_of_tab]))$data['xshippingpro']['mask'][$no_of_tab]='';
		  if(!isset($data['xshippingpro']['equation'][$no_of_tab]))$data['xshippingpro']['equation'][$no_of_tab]='';
		  if(!isset($data['xshippingpro']['text_color'][$no_of_tab]))$data['xshippingpro']['text_color'][$no_of_tab]='#000000';
		  if(!isset($data['xshippingpro']['background_color'][$no_of_tab]))$data['xshippingpro']['background_color'][$no_of_tab]='#ffffff';
		 
		  $return.='<div id="shipping-'.$no_of_tab.'" class="tab-pane shipping">'
          .'<div class="action-btn">'
		     .'<button class="btn btn-warning btn-copy" data-toggle="tooltip" type="button" data-original-title="'.$data['text_method_copy'].'"><i class="fa fa-copy"></i></button>'
			 .'<button class="btn btn-danger btn-delete" data-toggle="tooltip" type="button" data-original-title="'.$data['text_method_remove'].'"><i class="fa fa-trash-o"></i></button>'
		   .'</div>'
          .'<ul class="nav nav-tabs" id="language'.$no_of_tab.'">';
          
		  $inc=0; 
		   foreach ($data['languages'] as $language) { 
		       $active_cls=($inc==0)?'class="active"':''; 
			   $inc++;
              $return.='<li '.$active_cls.' ><a href="#language'.$language['language_id'].''.$no_of_tab.'" data-toggle="tab"><img src="view/image/flags/'.$language['image'].'" title="'.$language['name'].'" /> '.$language['name'].'</a></li>';
             } 
          $return.='</ul>'
		   .'<div class="tab-content">';
          
		   $inc=0;
		   foreach ($data['languages'] as $language) { 
		       $active_cls=($inc==0)?' active':''; 
			    $lang_cls=($inc==0)?'':'-lang'; $inc++; 
				if(!isset($names[$language['language_id']]) || !$names[$language['language_id']])$names[$language['language_id']]='Untitled Method '.$no_of_tab;     
				if(!isset($data['xshippingpro']['desc'][$no_of_tab][$language['language_id']]) || !$data['xshippingpro']['desc'][$no_of_tab][$language['language_id']])$data['xshippingpro']['desc'][$no_of_tab][$language['language_id']]='';
				
               $return.='<div class="tab-pane'.$active_cls.'" id="language'.$language['language_id'].''.$no_of_tab.'">'
		    .'<div class="form-group required">'
				.'<label class="col-sm-2 control-label" for="lang-name-'.$no_of_tab.''.$language['language_id'].'">'.$data['entry_name'].'</label>'
				.'<div class="col-sm-10">'
				 .'<input type="text" name="xshippingpro[name]['.$no_of_tab.']['.$language['language_id'].']" value="'.$names[$language['language_id']].'" placeholder="'.$data['entry_name'].'" id="lang-name-'.$no_of_tab.''.$language['language_id'].'" class="form-control method-name'.$lang_cls.'" />'
				 .'</div>'
			  .'</div>'
			  .'<div class="form-group">'
				 .'<label class="col-sm-2 control-label" for="lang-desc-'.$no_of_tab.''.$language['language_id'].'"><span data-toggle="tooltip" title="'.$data['tip_desc'].'">'.$data['entry_desc'].' </span></label>'
				 .'<div class="col-sm-10">'
				  .'<input type="text" name="xshippingpro[desc]['.$no_of_tab.']['.$language['language_id'].']" value="'.$data['xshippingpro']['desc'][$no_of_tab][$language['language_id']].'" placeholder="'.$data['entry_desc'].'" id="lang-desc-'.$no_of_tab.''.$language['language_id'].'" class="form-control" />'
				  .'</div>'
			   .'</div>'
	         .'</div>';
	        } 
	    $return.='</div>'
          .'<ul class="nav nav-tabs" id="method-tab-'.$no_of_tab.'">'
             .'<li class="active"><a href="#common_'.$no_of_tab.'" data-toggle="tab">'.$data['text_general'].'</a></li>'
             .'<li><a href="#criteria_'.$no_of_tab.'" data-toggle="tab">'.$data['text_criteria_setting'].'</a></li>'
             .'<li><a href="#catprod_'.$no_of_tab.'" data-toggle="tab">'.$data['text_category_product'].'</a></li>'
             .'<li><a href="#price_'.$no_of_tab.'" data-toggle="tab">'.$data['text_price_setting'].'</a></li>'
             .'<li><a href="#other_'.$no_of_tab.'" data-toggle="tab">'.$data['text_others'].'</a></li>'
           .'</ul>' 
		   .'<div class="tab-content">'
           .'<div class="tab-pane active" id="common_'.$no_of_tab.'">'
		        .'<div class="form-group">'
                   .'<label class="col-sm-2 control-label" for="input-weight'.$no_of_tab.'"><span data-toggle="tooltip" title="'.$data['tip_weight_include'].'">'.$data['entry_weight_include'].'</span></label>'
                   .'<div class="col-sm-10"><input '.(($data['xshippingpro']['inc_weight'][$no_of_tab]=='1')?'checked="checked"':'').' type="checkbox" name="xshippingpro[inc_weight]['.$no_of_tab.']" value="1" id="input-weight'.$no_of_tab.'" /></div>'
                .'</div>'
                .'<div class="form-group">'
                  .'<label class="col-sm-2 control-label" for="input-tax-class'.$no_of_tab.'">'.$data['entry_tax'].'</label>'
                  .'<div class="col-sm-10"><select id="input-tax-class'.$no_of_tab.'" name="xshippingpro[tax_class_id]['.$no_of_tab.']" class="form-control" >'
                  .'<option value="0">'.$data['text_none'].'</option>';
				  
                 foreach ($data['tax_classes'] as $tax_class) { 
                     $return.='<option '.(($data['xshippingpro']['tax_class_id'][$no_of_tab]==$tax_class['tax_class_id'])?'selected':'').' value="'.$tax_class['tax_class_id'].'">'.$tax_class['title'].'</option>';
                   } 
                  $return.='</select></div>'
                .'</div>'
                .'<div class="form-group">'
                  .'<label class="col-sm-2 control-label" for="input-logo'.$no_of_tab.'"><span data-toggle="tooltip" title="'.$data['tip_text_logo'].'">'.$data['text_logo'].' </span></label>'
                  .'<div class="col-sm-10"><input type="text" name="xshippingpro[logo]['.$no_of_tab.']" value="'.$data['xshippingpro']['logo'][$no_of_tab].'" class="form-control" id="input-logo'.$no_of_tab.'" /></div>'
                .'</div>'
                .'<div class="form-group">'
                  .'<label class="col-sm-2 control-label" for="input-sortorder'.$no_of_tab.'"><span data-toggle="tooltip" title="'.$data['tip_sorting_own'].'">'.$data['entry_sort_order'].' </span></label>'
                  .'<div class="col-sm-10"><input type="text" name="xshippingpro[sort_order]['.$no_of_tab.']" value="'.$data['xshippingpro']['sort_order'][$no_of_tab].'" class="form-control" id="input-sortorder'.$no_of_tab.'" /></div>'
                .'</div>'
                .'<div class="form-group">'
                  .'<label class="col-sm-2 control-label" for="input-status'.$no_of_tab.'"><span data-toggle="tooltip" title="'.$data['tip_status_own'].'">'.$data['entry_status'].'</span></label>'
                  .'<div class="col-sm-10"><select class="form-control" id="input-status'.$no_of_tab.'" name="xshippingpro[status]['.$no_of_tab.']">'
                   .'<option value="1" '.(($data['xshippingpro']['status'][$no_of_tab]==1 || $data['xshippingpro']['status'][$no_of_tab]=='')?'selected':'').'>'.$data['text_enabled'].'</option>'
					 .'<option value="0" '.(($data['xshippingpro']['status'][$no_of_tab]==0)?'selected':'').'>'.$data['text_disabled'].'</option>'
                  .'</select></div>'
                .'</div>'
                .'<div class="form-group">'
                  .'<label class="col-sm-2 control-label" for="input-group'.$no_of_tab.'"><span data-toggle="tooltip" title="'.$data['entry_group_tip'].'">'.$data['entry_group'].'</span></label>'
                  .'<div class="col-sm-10"><select class="form-control" id="input-group'.$no_of_tab.'" name="xshippingpro[group]['.$no_of_tab.']">'
                   .'<option value="0">'.$data['text_group_none'].'</option>';
					
					for($sg=1; $sg<=$data['xshippingpro_sub_groups_count'];$sg++) { 
						$return.='<option '.(($data['xshippingpro']['group'][$no_of_tab]==$sg)?'selected':'').' value="'.$sg.'">Group'.$sg.'</option>';
					   } 
                   $return.='</select></div>'
				.'</div>'
				.'<div class="form-group">'
                  .'<label class="col-sm-2 control-label" for="input-textcolor'.$no_of_tab.'"><span data-toggle="tooltip" title="'.$data['tip_text_color'].'">'.$data['entry_text_color'].' </span></label>'
                  .'<div class="col-sm-10"><input type="color" name="xshippingpro[text_color]['.$no_of_tab.']" value="'.$data['xshippingpro']['text_color'][$no_of_tab].'" class="form-control" id="input-textcolor'.$no_of_tab.'" /></div>'
				.'</div>'
				.'<div class="form-group">'
                  .'<label class="col-sm-2 control-label" for="input-backgroundcolor'.$no_of_tab.'"><span data-toggle="tooltip" title="'.$data['tip_background_color'].'">'.$data['entry_background_color'].' </span></label>'
                  .'<div class="col-sm-10"><input type="color" name="xshippingpro[background_color]['.$no_of_tab.']" value="'.$data['xshippingpro']['background_color'][$no_of_tab].'" class="form-control" id="input-backgroundcolor'.$no_of_tab.'" /></div>'
                .'</div>'
            .'</div>'
            .'<div class="tab-pane" id="criteria_'.$no_of_tab.'">'
               .'<div class="form-group">'
                .'<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="'.$data['tip_store'].'">'.$data['entry_store'].'</span></label>' 
                 .'<div class="col-sm-10">'
		            .'<label class="any-class"><input '.(($data['xshippingpro']['store_all'][$no_of_tab]=='1')?'checked="checked"':'').' type="checkbox" name="xshippingpro[store_all]['.$no_of_tab.']" class="choose-any" value="1" />&nbsp;'.$data['text_any'].'</label>'
		            .'<div class="well well-sm" style="height: 70px; overflow: auto;'.(($data['xshippingpro']['store_all'][$no_of_tab]!='1')?'display:block':'').'">'
		             .'<div class="checkbox xshipping-checkbox">';
                   
                    foreach ($data['stores'] as $store) {
		               $return.='<label>'
                       .'<input '.((in_array($store['store_id'],$data['xshippingpro']['store'][$no_of_tab]))?'checked':'').' type="checkbox" name="xshippingpro[store]['.$no_of_tab.'][]" value="'.$store['store_id'].'" />'.$store['name'].''
		                .'</label>';
                 } 
                $return.='</div>'
				   .'</div>'
	            .'</div>'
               .'</div>'
			   
			   .'<div class="form-group">'
                .'<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="'.$data['tip_geo'].'">'.$data['entry_geo_zone'].'</span></label>' 
                 .'<div class="col-sm-10">'
		            .'<label class="any-class"><input '.(($data['xshippingpro']['geo_zone_all'][$no_of_tab]=='1')?'checked="checked"':'').' type="checkbox" name="xshippingpro[geo_zone_all]['.$no_of_tab.']" class="choose-any" value="1" />&nbsp;'.$data['text_any'].'</label>'
		            .'<div class="well well-sm" style="height: 100px; overflow: auto;'.(($data['xshippingpro']['geo_zone_all'][$no_of_tab]!='1')?'display:block':'').'">'
		             .'<div class="checkbox xshipping-checkbox">';
                    
                    foreach ($data['geo_zones'] as $geo_zone) {
                    
		             $return.='<label>'
                       .'<input '.((in_array($geo_zone['geo_zone_id'],$data['xshippingpro']['geo_zone_id'][$no_of_tab]))?'checked':'').' type="checkbox" name="xshippingpro[geo_zone_id]['.$no_of_tab.'][]" value="'.$geo_zone['geo_zone_id'].'" />'.$geo_zone['name'].''
		             .'</label>';
                      } 
                  $return.='</div>'
				   .'</div>'
	            .'</div>'
               .'</div>'
               
               .'<div class="form-group">'
                .'<label class="col-sm-2 control-label">'.$data['text_country'].'</label>' 
                 .'<div class="col-sm-10">'
		            .'<label class="any-class"><input '.(($data['xshippingpro']['country_all'][$no_of_tab]=='1')?'checked="checked"':'').' type="checkbox" name="xshippingpro[country_all]['.$no_of_tab.']" class="choose-any" value="1" />&nbsp;'.$data['text_any'].'</label>'
		            .'<div class="well well-sm" style="height: 115px; overflow: auto; padding: 0;border-radius: 0;box-shadow: none;background: none;border: none;'.(($data['xshippingpro']['country_all'][$no_of_tab]!='1')?'display:block':'').'">'
		              .'<select class="form-control" multiple="true" size="5" name="xshippingpro[country]['.$no_of_tab.'][]">';
		                 foreach ($data['countries'] as $country) {
                  		   $return.='<option value="'.$country['country_id'].'" '.((in_array($country['country_id'],$data['xshippingpro']['country'][$no_of_tab]))?'selected':'').'>'.$country['name'].'</option>';
                  		 }
                  		 
                 $return.='</select>'
				   .'</div>'
	            .'</div>'
	            .'</div>'
			   
			    .'<div class="form-group">'
                .'<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="'.$data['tip_customer_group'].'">'.$data['entry_customer_group'].'</span></label>' 
                 .'<div class="col-sm-10">'
		            .'<label class="any-class"><input '.(($data['xshippingpro']['customer_group_all'][$no_of_tab]=='1')?'checked="checked"':'').' type="checkbox" name="xshippingpro[customer_group_all]['.$no_of_tab.']" class="choose-any" value="1" />&nbsp;'.$data['text_any'].'</label>'
		            .'<div class="well well-sm" style="height: 70px; overflow: auto;'.(($data['xshippingpro']['customer_group_all'][$no_of_tab]!='1')?'display:block':'').'">'
		             .'<div class="checkbox xshipping-checkbox">';
                    
                     foreach ($data['customer_groups'] as $customer_group) {
                   
		              $return.='<label>'
                       .'<input '.((in_array($customer_group['customer_group_id'],$data['xshippingpro']['customer_group'][$no_of_tab]))?'checked':'').' type="checkbox" name="xshippingpro[customer_group]['.$no_of_tab.'][]" value="'.$customer_group['customer_group_id'].'" />'.$customer_group['name'].''
		             .'</label>';
                  } 
                $return.='</div>'
				   .'</div>'
	            .'</div>'
               .'</div>'
			   
			   .'<div class="form-group">'
                .'<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="'.$data['tip_manufacturer'].'">'.$data['entry_manufacturer'].'</span></label>' 
                 .'<div class="col-sm-10">'
		            .'<label class="any-class"><input '.(($data['xshippingpro']['manufacturer_all'][$no_of_tab]=='1')?'checked="checked"':'').' type="checkbox" name="xshippingpro[manufacturer_all]['.$no_of_tab.']" class="choose-any-with" rel="manufacturer-option" value="1" />&nbsp;'.$data['text_any'].'</label>'
		            .'<div class="well well-sm" style="height: 100px; overflow: auto;'.(($data['xshippingpro']['manufacturer_all'][$no_of_tab]!='1')?'display:block':'').'">'
		             .'<div class="checkbox xshipping-checkbox">';
                  
                    foreach ($data['manufacturers'] as $manufacturer) {
                     
		             $return.='<label>'
                       .'<input '.((in_array($manufacturer['manufacturer_id'],$data['xshippingpro']['manufacturer'][$no_of_tab]))?'checked':'').' type="checkbox" name="xshippingpro[manufacturer]['.$no_of_tab.'][]" value="'.$manufacturer['manufacturer_id'].'" />'.$manufacturer['name'].''
		             .'</label>';
                    } 
                  $return.='</div>'
				   .'</div>'
	            .'</div>'
               .'</div>'
                .'<div class="form-group manufacturer-option" '.(($data['xshippingpro']['manufacturer_all'][$no_of_tab]!='1')?'style="display:block"':'').'>'
                .'<label class="col-sm-2 control-label" for="input-make-rule'.$no_of_tab.'"><span data-toggle="tooltip" title="'.$data['tip_manufacturer_rule'].'">'.$data['text_manufacturer_rule'].'</span></label>'
                .'<div class="col-sm-10"><select class="form-control" id="input-make-rule'.$no_of_tab.'" name="xshippingpro[manufacturer_rule]['.$no_of_tab.']">'
                   .'<option value="2" '.(($data['xshippingpro']['manufacturer_rule'][$no_of_tab]==2)?'selected':'').'>'.$data['text_manufacturer_all'].'</option>'
		           .'<option value="3" '.(($data['xshippingpro']['manufacturer_rule'][$no_of_tab]==3)?'selected':'').'>'.$data['text_manufacturer_least_with_other'].'</option>'
		           .'<option value="6" '.(($data['xshippingpro']['manufacturer_rule'][$no_of_tab]==6)?'selected':'').'>'.$data['text_manufacturer_least'].'</option>'
		           .'<option value="4" '.(($data['xshippingpro']['manufacturer_rule'][$no_of_tab]==4)?'selected':'').'>'.$data['text_manufacturer_exact'].'</option>'
		           .'<option value="5" '.(($data['xshippingpro']['manufacturer_rule'][$no_of_tab]==5)?'selected':'').'>'.$data['text_manufacturer_except'].'</option>'
				   .'<option value="7" '.(($data['xshippingpro']['manufacturer_rule'][$no_of_tab]==7)?'selected':'').'>'.$data['text_manufacturer_except_other'].'</option>'
                  .'</select></div>'
               .'</div>'
			   
			   .'<div class="form-group">'
                .'<label class="col-sm-2 control-label" for="input-postal'.$no_of_tab.'"><span data-toggle="tooltip" title="'.$data['tip_zip'].'">'.$data['text_zip_postal'].'</span></label>' 
                 .'<div class="col-sm-10">'
		            .'<label class="any-class"><input '.(($data['xshippingpro']['postal_all'][$no_of_tab]=='1')?'checked="checked"':'').' type="checkbox" name="xshippingpro[postal_all]['.$no_of_tab.']" class="choose-any-with" rel="postal-option" value="1" id="input-postal'.$no_of_tab.'" />'.$data['text_any'].'</label>'
	            .'</div>'
               .'</div>'
               .'<div class="form-group postal-option" '.(($data['xshippingpro']['postal_all'][$no_of_tab]!='1')?'style="display:block"':'').'>'
                .'<label class="col-sm-2 control-label" for="input-zip'.$no_of_tab.'"><span data-toggle="tooltip" title="'.$data['tip_postal_code'].'">'.$data['text_enter_zip'].'</span></label>'
                .'<div class="col-sm-10"><textarea class="form-control" id="input-zip'.$no_of_tab.'" name="xshippingpro[postal]['.$no_of_tab.']" rows="8" cols="70" />'.$data['xshippingpro']['postal'][$no_of_tab].'</textarea></div>'
               .'</div>'
               .'<div class="form-group postal-option" '.(($data['xshippingpro']['postal_all'][$no_of_tab]!='1')?'style="display:block"':'').'>'
                .'<label class="col-sm-2 control-label" for="input-zip-rule'.$no_of_tab.'">'.$data['text_zip_rule'].'</label>'
                .'<div class="col-sm-10"><select class="form-control" id="input-zip-rule'.$no_of_tab.'" name="xshippingpro[postal_rule]['.$no_of_tab.']">'
                    .'<option value="inclusive" '.(($data['xshippingpro']['postal_rule'][$no_of_tab]=='inclusive')?'selected':'').'>'.$data['text_zip_rule_inclusive'].'</option>'
                    .'<option value="exclusive" '.(($data['xshippingpro']['postal_rule'][$no_of_tab]=='exclusive')?'selected':'').'>'.$data['text_zip_rule_exclusive'].'</option>'
                  .'</select></div>'
               .'</div>'  
			   
			    .'<div class="form-group">'
                .'<label class="col-sm-2 control-label" for="input-coupon'.$no_of_tab.'"><span data-toggle="tooltip" title="'.$data['tip_coupon'].'">'.$data['text_coupon'].'</span></label>' 
                 .'<div class="col-sm-10">'
		            .'<label class="any-class"><input '.(($data['xshippingpro']['coupon_all'][$no_of_tab]=='1')?'checked="checked"':'').' type="checkbox" name="xshippingpro[coupon_all]['.$no_of_tab.']" class="choose-any-with" rel="coupon-option" value="1" id="input-coupon'.$no_of_tab.'" />'.$data['text_any'].'</label>'
	            .'</div>'
               .'</div>'
               .'<div class="form-group coupon-option" '.(($data['xshippingpro']['coupon_all'][$no_of_tab]!='1')?'style="display:blocked"':'').'>'
                .'<label class="col-sm-2 control-label" for="input-coupon-here'.$no_of_tab.'">'.$data['text_enter_coupon'].'</label>'
                .'<div class="col-sm-10"><textarea class="form-control" id="input-coupon-here'.$no_of_tab.'" name="xshippingpro[coupon]['.$no_of_tab.']" rows="8" cols="70" />'.$data['xshippingpro']['coupon'][$no_of_tab].'</textarea></div>'
               .'</div>'
               .'<div class="form-group coupon-option" '.(($data['xshippingpro']['coupon_all'][$no_of_tab]!='1')?'style="display:blocked"':'').'>'
                .'<label class="col-sm-2 control-label" for="input-coupon-rule'.$no_of_tab.'">'.$data['text_coupon_rule'].'</label>'
                .'<div class="col-sm-10"><select class="form-control" id="input-coupon-rule'.$no_of_tab.'" name="xshippingpro[coupon_rule]['.$no_of_tab.']">'
                    .'<option value="inclusive" '.(($data['xshippingpro']['coupon_rule'][$no_of_tab]=='inclusive')?'selected':'').'>'.$data['text_coupon_rule_inclusive'].'</option>'
                    .'<option value="exclusive" '.(($data['xshippingpro']['coupon_rule'][$no_of_tab]=='exclusive')?'selected':'').'>'.$data['text_coupon_rule_exclusive'].'</option>'
                  .'</select></div>'
               .'</div>'
         .'</div>' 
         .'<div class="tab-pane" id="catprod_'.$no_of_tab.'">'
	        .'<div class="form-group">'
              .'<label class="col-sm-2 control-label" for="input-cat-rule'.$no_of_tab.'"><span data-toggle="tooltip" title="'.$data['tip_category'].'">'.$data['text_category'].'</span></label>'
              .'<div class="col-sm-10"><select id="input-cat-rule'.$no_of_tab.'" class="form-control selection" rel="category" name="xshippingpro[category]['.$no_of_tab.']">'
                  .'<option value="1" '.(($data['xshippingpro']['category'][$no_of_tab]==1)?'selected':'').'>'.$data['text_category_any'].'</option>'
                  .'<option value="2" '.(($data['xshippingpro']['category'][$no_of_tab]==2)?'selected':'').'>'.$data['text_category_all'].'</option>'
		          .'<option value="3" '.(($data['xshippingpro']['category'][$no_of_tab]==3)?'selected':'').'>'.$data['text_category_least_with_other'].'</option>'
		          .'<option value="6" '.(($data['xshippingpro']['category'][$no_of_tab]==6)?'selected':'').'>'.$data['text_category_least'].'</option>'
		          .'<option value="4" '.(($data['xshippingpro']['category'][$no_of_tab]==4)?'selected':'').'>'.$data['text_category_exact'].'</option>'
		          .'<option value="5" '.(($data['xshippingpro']['category'][$no_of_tab]==5)?'selected':'').'>'.$data['text_category_except'].'</option>'
				   .'<option value="7" '.(($data['xshippingpro']['category'][$no_of_tab]==7)?'selected':'').'>'.$data['text_category_except_other'].'</option>'
                .'</select></div>'
            .'</div>'
			 .'<div class="form-group category" '.(($data['xshippingpro']['category'][$no_of_tab]!=1)?'style="display:block"':'').'>'
              .'<label class="col-sm-2 control-label" for="input-mul-cat-rule'.$no_of_tab.'"><span data-toggle="tooltip" title="'.$data['tip_multi_category'].'">'.$data['text_multi_category'].'</span></label>'
              .'<div class="col-sm-10"><select id="input-mul-cat-rule'.$no_of_tab.'" class="form-control" name="xshippingpro[multi_category]['.$no_of_tab.']">'
                  .'<option value="all" '.(($data['xshippingpro']['multi_category'][$no_of_tab]=='all')?'selected':'').'>'.$data['entry_all'].'</option>'
                  .'<option value="any" '.(($data['xshippingpro']['multi_category'][$no_of_tab]=='any')?'selected':'').'>'.$data['entry_any'].'</option>'
                .'</select></div>'
            .'</div>'
	        .'<div class="form-group category" '.(($data['xshippingpro']['category'][$no_of_tab]!=1)?'style="display:block"':'').'>'
              .'<label class="col-sm-2 control-label" for="input-category'.$no_of_tab.'">'.$data['entry_category'].'</label>'
              .'<div class="col-sm-10"><input type="text" name="category" value="" placeholder="'.$data['entry_category'].'" id="input-category'.$no_of_tab.'" class="form-control" />'
			     .'<div class="well well-sm product-category" style="height: 150px; overflow: auto;">';
				 foreach ($data['xshippingpro']['product_category'][$no_of_tab] as $category_id) {
						   $category_info = $this->model_catalog_category->getCategory($category_id);
						   if(!$category_info) {
						     $category_info['path'] = '';
						     $category_info['name'] = '';
						   }
						   $return.='<div class="product-category'.$category_id. '"><i class="fa fa-minus-circle"></i> '.$category_info['path'].'&nbsp;&nbsp;&gt;&nbsp;&nbsp;'.$category_info['name'].'<input type="hidden" class="category" name="xshippingpro[product_category]['.$no_of_tab.'][]" value="'.$category_id.'" /></div>';
						}
	    $return.='</div>'
			   .'</div>'
            .'</div>'
            .'<div class="form-group">'
              .'<label class="col-sm-2 control-label" for="input-product_rule'.$no_of_tab.'"><span data-toggle="tooltip" title="'.$data['tip_product'].'">'.$data['text_product'].'</span></label>'
              .'<div class="col-sm-10"><select id="input-product_rule'.$no_of_tab.'" class="form-control selection" rel="product" name="xshippingpro[product]['.$no_of_tab.']">'
                  .'<option value="1" '.(($data['xshippingpro']['product'][$no_of_tab]==1)?'selected':'').'>'.$data['text_product_any'].'</option>'
                  .'<option value="2" '.(($data['xshippingpro']['product'][$no_of_tab]==2)?'selected':'').'>'.$data['text_product_all'].'</option>'
	              .'<option value="3" '.(($data['xshippingpro']['product'][$no_of_tab]==3)?'selected':'').'>'.$data['text_product_least_with_other'].'</option>'
	              .'<option value="6" '.(($data['xshippingpro']['product'][$no_of_tab]==6)?'selected':'').'>'.$data['text_product_least'].'</option>'
	              .'<option value="4" '.(($data['xshippingpro']['product'][$no_of_tab]==4)?'selected':'').'>'.$data['text_product_exact'].'</option>'
	              .'<option value="5" '.(($data['xshippingpro']['product'][$no_of_tab]==5)?'selected':'').'>'.$data['text_product_except'].'</option>'
				   .'<option value="7" '.(($data['xshippingpro']['product'][$no_of_tab]==7)?'selected':'').'>'.$data['text_product_except_other'].'</option>'
                .'</select></div>'
             .'</div>'
			  .'<div class="form-group product" ' .(($data['xshippingpro']['product'][$no_of_tab]!=1)?'style="display:block"':'').'>'
              .'<label class="col-sm-2 control-label" for="input-product'.$no_of_tab.'">'.$data['entry_product'].'</label>'
              .'<div class="col-sm-10"><input type="text" name="product" value="" placeholder="'.$data['entry_product'].'" id="input-product'.$no_of_tab.'" class="form-control" />'
			     .'<div class="well well-sm product-product" style="height: 150px; overflow: auto;">';
				   foreach ($data['xshippingpro']['product_product'][$no_of_tab] as $product_id) {
						   $product_info = $this->model_catalog_product->getProduct($product_id);
						   $return.='<div class="product-product'.$product_id. '"><i class="fa fa-minus-circle"></i> '.(isset($product_info['name'])?$product_info['name']:'').'<input type="hidden" name="xshippingpro[product_product]['.$no_of_tab.'][]" value="'.$product_id.'" /></div>';
						  
						}
				   $return.='</div>'
			   .'</div>'
            .'</div>'
          .'</div>'
         .'<div class="tab-pane" id="price_'.$no_of_tab.'">'
            .'<div class="form-group">'
              .'<label class="col-sm-2 control-label" for="input-rate'.$no_of_tab.'"><span data-toggle="tooltip" title="'.$data['tip_rate_type'].'">'.$data['text_rate_type'].'</span></label>'
              .'<div class="col-sm-10"><select id="input-rate'.$no_of_tab.'" class="rate-selection form-control" name="xshippingpro[rate_type]['.$no_of_tab.']">'
                  .'<option value="flat" '.(($data['xshippingpro']['rate_type'][$no_of_tab]=='flat')?'selected':'').'>'.$data['text_rate_flat'].'</option>'
                  .'<option value="quantity" '.(($data['xshippingpro']['rate_type'][$no_of_tab]=='quantity')?'selected':'').'>'.$data['text_rate_quantity'].'</option>'
                  .'<option value="weight" '.(($data['xshippingpro']['rate_type'][$no_of_tab]=='weight')?'selected':'').'>'.$data['text_rate_weight'].'</option>'
				   .'<option value="dimensional" '.(($data['xshippingpro']['rate_type'][$no_of_tab]=='dimensional')?'selected':'').'>'.$data['text_dimensional_weight'].'</option>'
                  .'<option value="volume" '.(($data['xshippingpro']['rate_type'][$no_of_tab]=='volume')?'selected':'').'>'.$data['text_rate_volume'].'</option>'
		          .'<option value="total" '.(($data['xshippingpro']['rate_type'][$no_of_tab]=='total')?'selected':'').'>'.$data['text_rate_total'].'</option>'
		          .'<option value="total_coupon" '.(($data['xshippingpro']['rate_type'][$no_of_tab]=='total_coupon')?'selected':'').'>'.$data['text_rate_total_coupon'].'</option>'
                  .'<option value="sub" '.(($data['xshippingpro']['rate_type'][$no_of_tab]=='sub')?'selected':'').'>'.$data['text_rate_sub_total'].'</option>'
                  .'<option value="grand" '.(($data['xshippingpro']['rate_type'][$no_of_tab]=='grand')?'selected':'').'>'.$data['text_grand_total'].'</option>'
                  .'<option value="total_method" '.(($data['xshippingpro']['rate_type'][$no_of_tab]=='total_method')?'selected':'').'>'.$data['text_rate_total_method'].'</option>'
                  .'<option value="sub_method" '.(($data['xshippingpro']['rate_type'][$no_of_tab]=='sub_method')?'selected':'').'>'.$data['text_rate_sub_total_method'].'</option>'
                  .'<option value="quantity_method" '.(($data['xshippingpro']['rate_type'][$no_of_tab]=='quantity_method')?'selected':'').'>'.$data['text_rate_quantity_method'].'</option>'
                  .'<option value="weight_method" '.(($data['xshippingpro']['rate_type'][$no_of_tab]=='weight_method')?'selected':'').'>'.$data['text_rate_weight_method'].'</option>'
				   .'<option value="dimensional_method" '.(($data['xshippingpro']['rate_type'][$no_of_tab]=='dimensional_method')?'selected':'').'>'.$data['text_dimensional_weight_method'].'</option>'
                  .'<option value="volume_method" '.(($data['xshippingpro']['rate_type'][$no_of_tab]=='volume_method')?'selected':'').'>'.$data['text_rate_volume_method'].'</option>'
                .'</select></div>'
            .'</div>'
			.'<div class="form-group dimensional-option" '.(($data['xshippingpro']['rate_type'][$no_of_tab]=='dimensional' || $data['xshippingpro']['rate_type'][$no_of_tab]=='dimensional_method' || $data['xshippingpro']['rate_type'][$no_of_tab]=='volume' || $data['xshippingpro']['rate_type'][$no_of_tab]=='volume_method')?'style="display:block"':'style="display:none"').'>'
             .'<label class="col-sm-3 control-label" for="input-dimension_factor'.$no_of_tab.'">'.$data['text_dimensional_factor'].'</label>'
             .'<div class="col-sm-9"><input id="input-dimension_factor'.$no_of_tab.'" type="text" name="xshippingpro[dimensional_factor]['.$no_of_tab.']" value="'.$data['xshippingpro']['dimensional_factor'][$no_of_tab].'" class="form-control" /></div>'
           .'</div>'
			.'<div class="form-group dimensional-option" '.(($data['xshippingpro']['rate_type'][$no_of_tab]=='dimensional' || $data['xshippingpro']['rate_type'][$no_of_tab]=='dimensional_method' || $data['xshippingpro']['rate_type'][$no_of_tab]=='volume' || $data['xshippingpro']['rate_type'][$no_of_tab]=='volume_method')?'style="display:block"':'style="display:none"').'>'
                .'<label class="col-sm-4 control-label" for="input-dimension_overrule'.$no_of_tab.'">'.$data['text_dimensional_overrule'].'</label>'
                .'<div class="col-sm-8"><input '.(($data['xshippingpro']['dimensional_overfule'][$no_of_tab]=='1')?'checked="checked"':'').' id="input-dimension_overrule'.$no_of_tab.'" type="checkbox" name="xshippingpro[dimensional_overfule]['.$no_of_tab.']" value="1" /></div>'
            .'</div>'
           .'<div class="form-group single-option" '.(($data['xshippingpro']['rate_type'][$no_of_tab]=='flat')?'style="display:block"':'style="display:none"').'>'
             .'<label class="col-sm-2 control-label" for="input-cost'.$no_of_tab.'"><span data-toggle="tooltip" title="'.$data['tip_cost'].'">'.$data['entry_cost'].'</span></label>'
             .'<div class="col-sm-10"><input id="input-cost'.$no_of_tab.'" class="form-control" type="text" name="xshippingpro[cost]['.$no_of_tab.']" value="'.$data['xshippingpro']['cost'][$no_of_tab].'" /></div>'
           .'</div>'
           .'<div class="form-group range-option" '.(($data['xshippingpro']['rate_type'][$no_of_tab]!='flat')?'style="display:block"':'').'>'
             .'<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="'.$data['tip_import'].'">'.$data['text_unit_range'].'</span></label>'
             .'<div class="col-sm-10">'
			  .'<div class="tbl-wrapper">'
			    .'<div class="import-btn-wrapper">'
                .'<a href="'.$data['export'].'&no='.$no_of_tab.'" class="btn btn-info export-btn rate-btn">'.$data['text_export'].'</a>&nbsp;<a class="btn btn-danger delete-all rate-btn">'.$data['text_delete_all'].'</a>&nbsp;<a  class="btn btn-primary import-btn rate-btn">'.$data['text_csv_import'].'</a>'
			    .'</div>'
			    .'<div class="table-responsive">'
               .'<table class="table table-striped table-bordered table-hover">'
                  .'<thead>'
                   .'<tr>'
                    .'<td class="text-left"><label class="control-label"><span data-toggle="tooltip" title="'.$data['tip_unit_start'].'">'.$data['text_start'].'</span></label></td>'
					   .'<td class="text-left"><label class="control-label"><span data-toggle="tooltip" title="'.$data['tip_unit_end'].'">'.$data['text_end'].'</span></label></td>'
					   .'<td class="text-left"><label class="control-label"><span data-toggle="tooltip" title="'.$data['tip_unit_price'].'">'.$data['text_cost'].'</span></label></td>'
					   .'<td class="text-left"><label class="control-label"><span data-toggle="tooltip" title="'.$data['tip_unit_ppu'].'">'.$data['text_qnty_block'].'</span></label></td>'
					   .'<td class="text-left"><label class="control-label"><span data-toggle="tooltip" title="'.$data['tip_partial'].'">'.$data['text_partial'].'</span></label></td>'
					   .'<td class="left"></td>'
                   .'</tr>'
                  .'</thead>'
                  .'<tbody>';
				    
					$is_row_found=false;
					foreach ($data['xshippingpro']['rate_start'][$no_of_tab] as $inc=>$rate_start) { 
					  if(!isset($data['xshippingpro']['rate_partial'][$no_of_tab][$inc]))$data['xshippingpro']['rate_partial'][$no_of_tab][$inc]='0'; 
					  $is_row_found=true; 
                    $return.='<tr>' 
                   .'<td class="text-left"><input size="15" type="text" class="form-control" name="xshippingpro[rate_start]['.$no_of_tab.'][]" value="'.$rate_start.'" /></td>'
                   .'<td class="text-left"><input size="15" type="text" class="form-control" name="xshippingpro[rate_end]['.$no_of_tab.'][]" value="'.$data['xshippingpro']['rate_end'][$no_of_tab][$inc].'" /></td>'
                   .'<td class="text-left"><input size="15" type="text" class="form-control" name="xshippingpro[rate_total]['.$no_of_tab.'][]" value="'.$data['xshippingpro']['rate_total'][$no_of_tab][$inc].'" /></td>'
                   .'<td class="text-left"><input size="6" type="text" class="form-control" name="xshippingpro[rate_block]['.$no_of_tab.'][]" value="'.$data['xshippingpro']['rate_block'][$no_of_tab][$inc].'" /></td>'
				    .'<td class="text-left"><select name="xshippingpro[rate_partial]['.$no_of_tab.'][]"><option '.(($data['xshippingpro']['rate_partial'][$no_of_tab][$inc]=='1')?'selected':'').' value="1">'.$data['text_yes'].'</option><option '.(($data['xshippingpro']['rate_partial'][$no_of_tab][$inc]=='0')?'selected':'').' value="0">'.$data['text_no'].'</option></select></td>'
                   .'<td class="text-right"><a class="btn btn-danger remove-row">'.$data['text_remove'].'</a></td>'
                   .'</tr>';
                  }
					if(!$is_row_found)$return.='<tr class="no-row"><td colspan="6">'.$data['no_unit_row'].'</td></tr>';
                 
				    $return.='</tbody>'
                  .'<tfoot>'
                    .'<tr>'
                     .'<td colspan="5">&nbsp;</td>'
                     .'<td class="right">&nbsp;<a class="btn btn-primary add-row"><i class="fa fa-plus-circle"></i>'.$data['text_add_new'].'</span></label>'
                    .'</tr>'
                   .'</tfoot>'     
                 .'</table>'
                .'</div>'
				.'</div>'
				.'</div>'
            .'</div>'
			 .'<div class="form-group range-option" '.(($data['xshippingpro']['rate_type'][$no_of_tab]!='flat')?'style="display:block"':'style="display:none"').'>'
             .'<label class="col-sm-2 control-label" for="input-additional'.$no_of_tab.'"><span data-toggle="tooltip" title="'.$data['tip_additional'].'">'.$data['text_additional'].'</span></label>'
             .'<div class="col-sm-10"><input id="input-additional'.$no_of_tab.'" class="form-control" type="text" name="xshippingpro[additional]['.$no_of_tab.']" value="'.$data['xshippingpro']['additional'][$no_of_tab].'" /></div>'
           .'</div>'
            .'<div class="form-group range-option" '.(($data['xshippingpro']['rate_type'][$no_of_tab]!='flat')?'style="display:block"':'').'>'
              .'<label class="col-sm-2 control-label" for="input-rate-final'.$no_of_tab.'"><span data-toggle="tooltip" title="'.$data['tip_single_commulative'].'">'.$data['text_final_cost'].'</span></label>'
              .'<div class="col-sm-10"><select id="input-rate-final'.$no_of_tab.'" class="form-control" name="xshippingpro[rate_final]['.$no_of_tab.']">'
                  .'<option '.(($data['xshippingpro']['rate_final'][$no_of_tab]=='single')?'selected':'').' value="single">'.$data['text_final_single'].'</option>'
                  .'<option '.(($data['xshippingpro']['rate_final'][$no_of_tab]=='cumulative')?'selected':'').' value="cumulative">'.$data['text_final_cumulative'].'</option>'
                .'</select></div>'
            .'</div>'
            .'<div class="form-group">'
              .'<label class="col-sm-2 control-label" for="input-rate-percent'.$no_of_tab.'"><span data-toggle="tooltip" title="'.$data['tip_percentage'].'">'.$data['text_percentage_related'].'</span></label>'
              .'<div class="col-sm-10"><select class="form-control" id="input-rate-percent'.$no_of_tab.'" name="xshippingpro[rate_percent]['.$no_of_tab.']">'
                  .'<option '.(($data['xshippingpro']['rate_percent'][$no_of_tab]=='sub')?'selected':'').' value="sub">'.$data['text_percent_sub_total'].'</option>'
                  .'<option '.(($data['xshippingpro']['rate_percent'][$no_of_tab]=='total')?'selected':'').' value="total">'.$data['text_percent_total'].'</option>'
                  .'<option '.(($data['xshippingpro']['rate_percent'][$no_of_tab]=='shipping')?'selected':'').' value="shipping">'.$data['text_percent_shipping'].'</option>'
                  .'<option '.(($data['xshippingpro']['rate_percent'][$no_of_tab]=='sub_shipping')?'selected':'').' value="sub_shipping">'.$data['text_percent_sub_total_shipping'].'</option>'
                  .'<option '.(($data['xshippingpro']['rate_percent'][$no_of_tab]=='total_shipping')?'selected':'').' value="total_shipping">'.$data['text_percent_total_shipping'].'</option>'
                .'</select></div>'
            .'</div>'
             .'<div class="form-group single-option" '.(($data['xshippingpro']['rate_type'][$no_of_tab]=='flat')?'style="display:block"':'style="display:none"').'>'
             .'<label class="col-sm-2 control-label" for="input-mask'.$no_of_tab.'">'.$data['text_mask_price'].'</label>'
             .'<div class="col-sm-10"><input id="input-mask'.$no_of_tab.'" class="form-control" type="text" name="xshippingpro[mask]['.$no_of_tab.']" value="'.$data['xshippingpro']['mask'][$no_of_tab].'" /></div>'
           .'</div>'
            .'<div class="form-group range-option" '.(($data['xshippingpro']['rate_type'][$no_of_tab]!='flat')?'style="display:block"':'').'>'
                .'<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="'.$data['tip_price_adjust'].'">'.$data['text_price_adjustment'].'</span></label>'
                .'<div class="col-sm-10">'
				    .'<div class="row">'
					   .'<div class="col-sm-4">'
					    .' <input class="form-control" type="text" name="xshippingpro[rate_min]['.$no_of_tab.']" placeholder="'.$data['text_price_min'].'" value="'.$data['xshippingpro']['rate_min'][$no_of_tab].'" />'
					    .'</div>'
						.'<div class="col-sm-4">'
						   .'<input class="form-control" type="text" name="xshippingpro[rate_max]['.$no_of_tab.']" placeholder="'.$data['text_price_max'].'" value="'.$data['xshippingpro']['rate_max'][$no_of_tab].'" />'
						 .'</div>'  
						 .'<div class="col-sm-4">'
						   .'<input class="form-control" type="text" name="xshippingpro[rate_add]['.$no_of_tab.']" placeholder="'.$data['text_price_add'].'" value="'.$data['xshippingpro']['rate_add'][$no_of_tab].'" />'
					    .'</div>'	   
                   .'</div>'
                   .'<div class="row"><div class="col-sm-12"><input '.(($data['xshippingpro']['modifier_ignore'][$no_of_tab])?'checked':'').' type="checkbox" value="1" name="xshippingpro[modifier_ignore]['.$no_of_tab.']" />'.$data['ignore_modifier'].'</div></div>'
                .'</div>'
            .'</div>'
            .'<div class="form-group range-option" '.(($data['xshippingpro']['rate_type'][$no_of_tab]!='flat')?'style="display:block"':'').'>'
             .'<label class="col-sm-2 control-label" for="input-equation'.$no_of_tab.'"><span data-toggle="tooltip" title="'.$data['tip_equation'].'">'.$data['text_equation'].'</span></label>'
             .'<div class="col-sm-10"><textarea class="form-control" id="lang-equation'.$no_of_tab.'" name="xshippingpro[equation]['.$no_of_tab.']" rows="8" cols="70" />'.$data['xshippingpro']['equation'][$no_of_tab].'</textarea>'.$data['text_equation_help'].'</div>'
           .'</div>'
		    .'</div>'
           .'<div class="tab-pane" id="other_'.$no_of_tab.'">'
             .'<div class="form-group">'
              .'<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="'.$data['tip_day'].'">'.$data['text_days_week'].'</span></label>'
              .'<div class="col-sm-10">'
			      .'<div class="well well-sm well-days" style="height: 60px; overflow: auto;">'
				    .'<div class="checkbox xshipping-checkbox">' 
				      .'<label><input name="xshippingpro[days]['.$no_of_tab.'][]" '.((in_array(0,$data['xshippingpro']['days'][$no_of_tab]))?'checked':'').' type="checkbox" value="0" />&nbsp; '.$data['text_sunday'].'</label>'
                    .'<label><input name="xshippingpro[days]['.$no_of_tab.'][]" '.((in_array(1,$data['xshippingpro']['days'][$no_of_tab]))?'checked':'').' type="checkbox" value="1" />&nbsp; '.$data['text_monday'].'</label>'
                    .'<label><input name="xshippingpro[days]['.$no_of_tab.'][]" '.((in_array(2,$data['xshippingpro']['days'][$no_of_tab]))?'checked':'').' type="checkbox" value="2" />&nbsp; '.$data['text_tuesday'].'</label>'
                    .'<label><input name="xshippingpro[days]['.$no_of_tab.'][]" '.((in_array(3,$data['xshippingpro']['days'][$no_of_tab]))?'checked':'').' type="checkbox" value="3" />&nbsp; '.$data['text_wednesday'].'</label>'
                    .'<label><input name="xshippingpro[days]['.$no_of_tab.'][]" '.((in_array(4,$data['xshippingpro']['days'][$no_of_tab]))?'checked':'').' type="checkbox" value="4" />&nbsp; '.$data['text_thursday'].'</label>'
                    .'<label><input name="xshippingpro[days]['.$no_of_tab.'][]" '.((in_array(5,$data['xshippingpro']['days'][$no_of_tab]))?'checked':'').' type="checkbox" value="5" />&nbsp; '.$data['text_friday'].'</label>'
                    .'<label><input name="xshippingpro[days]['.$no_of_tab.'][]" '.((in_array(6,$data['xshippingpro']['days'][$no_of_tab]))?'checked':'').' type="checkbox" value="6" />&nbsp; '.$data['text_saturday'].'</label>'
					 .'</div>'
					.'</div>' 
                .'</div>'
             .'</div>'
            .'<div class="form-group">'
                .'<label class="col-sm-2 control-label" for="input-time-start'.$no_of_tab.'"><span data-toggle="tooltip" title="'.$data['tip_time'].'">'.$data['text_time_period'].'</span></label>'
                .'<div class="col-sm-10">'
				    .'<div class="row">'
					    .'<div class="col-sm-4">'
						   .'<select id="input-time-start'.$no_of_tab.'" class="form-control" name="xshippingpro[time_start]['.$no_of_tab.']">'
						  .'<option value="">'.$data['text_any'].'</option>';
						 for($i = 1; $i <= 24; $i++) { 
						  $return.='<option '.(($data['xshippingpro']['time_start'][$no_of_tab]==$i)?'selected':'').' value="'.$i.'">'.date("h:i A", strtotime("$i:00")).'</option>';
						   } 
						$return.='</select>'
				       .'</div>'
				       .'<div class="col-sm-4">'
						  .'<select class="form-control" name="xshippingpro[time_end]['.$no_of_tab.']">'
						  .'<option value="">'.$data['text_any'].'</option>';
						   for($i = 1; $i <= 24; $i++) { 
						  $return.='<option '.(($data['xshippingpro']['time_end'][$no_of_tab]==$i)?'selected':'').' value="'.$i.'">'.date("h:i A", strtotime("$i:00")).'</option>';
						   }
						 $return.='</select>'
				        .'</div>'
				     .'</div>'
                .'</div>'
               .'</div>'
               .'<div class="form-group">'
             .'<label class="col-sm-2 control-label" for="input-total'.$no_of_tab.'"><span data-toggle="tooltip" title="'.$data['tip_total'].'">'.$data['entry_order_total'].'</span></label>'
             .'<div class="col-sm-10">'
                  .'<div class="row-fluid">'
                     .'<div class="col-sm-5">'
                       .'<input size="15" class="form-control" type="text" name="xshippingpro[order_total_start]['.$no_of_tab.']" value="'.$data['xshippingpro']['order_total_start'][$no_of_tab].'" />'
                     .'</div>'
                    .'<div class="col-sm-1">'.$data['entry_to'].'</div>'
                    .'<div class="col-sm-5">'
                       .'<input class="form-control" size="15" type="text" name="xshippingpro[order_total_end]['.$no_of_tab.']" value="'.$data['xshippingpro']['order_total_end'][$no_of_tab].'" />'
                    .'</div>'
                  .'</div>'
              .'</div>'
           .'</div>'
		   .'<div class="form-group">'
            .'<label class="col-sm-2 control-label" for="input-total'.$no_of_tab.'"><span data-toggle="tooltip" title="'.$data['tip_weight'].'">'.$data['entry_order_weight'].'</span></label>'
             .'<div class="col-sm-10">'
                  .'<div class="row-fluid">'
                     .'<div class="col-sm-5">'
                       .'<input size="15" class="form-control" type="text" name="xshippingpro[weight_start]['.$no_of_tab.']" value="'.$data['xshippingpro']['weight_start'][$no_of_tab].'" />'
                     .'</div>'
                    .'<div class="col-sm-1">'.$data['entry_to'].'</div>'
                    .'<div class="col-sm-5">'
                       .'<input class="form-control" size="15" type="text" name="xshippingpro[weight_end]['.$no_of_tab.']" value="'.$data['xshippingpro']['weight_end'][$no_of_tab].'" />'
                    .'</div>'
                  .'</div>'
              .'</div>'
           .'</div>'
		   .'<div class="form-group">'
           .'<label class="col-sm-2 control-label" for="input-total'.$no_of_tab.'"><span data-toggle="tooltip" title="'.$data['tip_quantity'].'">'.$data['entry_quantity'].'</span></label>'
             .'<div class="col-sm-10">'
                  .'<div class="row-fluid">'
                     .'<div class="col-sm-5">'
                       .'<input size="15" class="form-control" type="text" name="xshippingpro[quantity_start]['.$no_of_tab.']" value="'.$data['xshippingpro']['quantity_start'][$no_of_tab].'" />'
                     .'</div>'
                     .'<div class="col-sm-1">'.$data['entry_to'].'</div>'
                     .'<div class="col-sm-5">'
                       .'<input class="form-control" size="15" type="text" name="xshippingpro[quantity_end]['.$no_of_tab.']" value="'.$data['xshippingpro']['quantity_end'][$no_of_tab].'" />'
                    .'</div>'
                  .'</div>'
              .'</div>'
           .'</div>'
           .'</div>'
		   .'</div>' 
        .'</div>';
          
		}
		
		return $return;		
	}
}
?>