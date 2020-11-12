<?php
class ControllerModuleSupercategoryMenuAdvanced extends Controller {
	private $error = array(); 
	private $all_links = false;	
	 
	public function index() {   
		$this->load->language('catalog/category');
        $this->load->language('module/supercategorymenuadvanced');
		//see if table for cache is on database
		$this->load->model('module/supercategorymenuadvanced');
	    $this->model_module_supercategorymenuadvanced->createTable('cache_supercategory');
		$this->model_module_supercategorymenuadvanced->createTable('cache_supercategory_menu');
    	$this->document->setTitle($this->language->get('heading_title'));
		
		//inset javascript	
		
		$this->document->addScript('view/javascript/jquery/fancybox2/source/jquery.fancybox.pack.js');
		$this->document->addStyle('view/javascript/jquery/fancybox2/source/jquery.fancybox.css');
		 
		//if (!$this->_is_vqmod_installed() ){
		//	$this->error['warning'] = $this->language->get('error_vqmod');
 		//}
		$data['token'] = $this->session->data['token'];	
		
			echo "<pre>";
	//	echo print_r($this->request->post);
		echo "</pre>";
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {		
		
	
			
			//save the same settings for all stores.
		//	$this->load->model('setting/store');
				
		//	$this->model_module_supercategorymenuadvanced->editOneSetting('supercategorymenuadvanced', 'supercategorymenuadvanced_module',$this->request->post);	
			//$this->model_module_supercategorymenuadvanced->editAdminSettings('supercategorymenuadvanced', $this->request->post);
			
		//	$this->session->data['success'] = $this->language->get('text_success');
		//	$this->redirect($this->url->link('module/supercategorymenuadvanced', 'token=' . $this->session->data['token'], 'SSL'));
		
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		$this->getList();
   		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	
	private $BASE_URL = "http://support.ocmodules.com/register/?";
	private $GETACCOUNTDETAILS_URL = "http://support.ocmodules.com/getAccountDetails/";
	private $GETVERSION_URL = "http://support.ocmodules.com/getCurrentVersion/";
	private $EXTENSION=16;
	private $VERSIONINSTALLED="1.0";
		
	
	
	
	
	public function settings() {   
		
		$this->document->addScript('view/javascript/jquery/fancybox2/source/jquery.fancybox.pack.js');
		$this->document->addStyle('view/javascript/jquery/fancybox2/source/jquery.fancybox.css');
		$this->document->addScript(HTTP_CATALOG . 'catalog/view/javascript/jquery/supermenu/supermenu_base.js');
		$this->document->addStyle('view/javascript/bootstrap-vertical-tabs-1.1.0/bootstrap.vertical-tabs.min.css');
	
		
		$data['token'] =$this->session->data['token'];
		
		$data['current_version']=$this->VERSIONINSTALLED;
		$this->load->language('module/supercategorymenuadvanced');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('module/supercategorymenuadvanced');
       

	
			

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {		
			
		
		
			//save the same settings for all stores.
			$this->load->model('setting/store');
			$this->model_module_supercategorymenuadvanced->editSeoKeyword($this->request->post['supercategorymenuadvanced_seo_cat'],'category_id=0');
			$this->model_module_supercategorymenuadvanced->editSeoKeyword($this->request->post['supercategorymenuadvanced_seo_man'],'manufacturer_id=0');	
			foreach ($this->request->post['SETTINGS'] as $key=>$value){
			$this->model_module_supercategorymenuadvanced->editAdminSettings('supercategorymenuadvanced','SETTINGS_'.$key, $this->request->post['SETTINGS'][$key], $key);
			}
				
			$this->model_module_supercategorymenuadvanced->editOneSetting('supercategorymenuadvanced', 'supercategorymenuadvanced_status',$this->request->post);	
			//$this->model_module_supercategorymenuadvanced->editAdminSettings('supercategorymenuadvanced', 'supercategorymenuadvanced_code',$this->request->post['supercategorymenuadvanced_code']);	
			//$this->session->data['success'] = $this->language->get('text_success');
			//$this->response->redirect($this->url->link('module/supercategorymenuadvanced/settings', 'token=' . $this->session->data['token'], 'SSL'));
		
		}
		
	
		
		
		
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/supercategorymenuadvanced', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title2'),
			'href'      => $this->url->link('module/supercategorymenuadvanced/settings', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		
		
		if (isset($this->request->post['supercategorymenuadvanced_status'])) {
			$data['supercategorymenuadvanced_status'] = $this->request->post['supercategorymenuadvanced_status'];
		} else {
			$data['supercategorymenuadvanced_status'] = $this->config->get('supercategorymenuadvanced_status');
		}
		
		
		
		
		
		
		
		$data['rg']=false;
        if (isset($this->request->post['supercategorymenuadvanced_seo_cat'])){
			$data['settings_seo_cat']=$this->request->post['supercategorymenuadvanced_seo_cat'];
		}elseif ($this->model_module_supercategorymenuadvanced->getSeoWord('category_id=0')) { 
			$data['settings_seo_cat'] = $this->model_module_supercategorymenuadvanced->getSeoWord('category_id=0');
		}else{
			$data['settings_seo_cat']="Store";
		}
	    if (isset($this->request->post['supercategorymenuadvanced_seo_man'])){
			$data['settings_seo_man']=$this->request->post['supercategorymenuadvanced_seo_man'];
		}elseif ($this->model_module_supercategorymenuadvanced->getSeoWord('manufacturer_id=0')) { 
			$data['settings_seo_man'] = $this->model_module_supercategorymenuadvanced->getSeoWord('manufacturer_id=0');
		}else{
			$data['settings_seo_man']="Brand";
		}
		
		if (isset($this->request->post['supercategorymenuadvanced_code'])){
			$data['settings_code']=$this->request->post['supercategorymenuadvanced_code'];
		}elseif ($this->config->get('supercategorymenuadvanced_code')) { 
			$data['settings_code'] = $this->config->get('supercategorymenuadvanced_code');
		}else{
			$data['settings_code']="u";
		}
				
		if ($data['settings_code']=="u"){
			$data['supercategorymenuadvanced_accountDetails'] = "Extension not registered, please register to get support. <a class=\"register\" href=\"".$this->BASE_URL."&extension=".urlencode($this->EXTENSION)."&domain=".urlencode($_SERVER['SERVER_NAME'])."\"> Register</a>";
			
			$error['warning'] = "Extension not registered, please register to get support.";
		}else{
		
		    $accountDetails = $this->get_accountDetails($this->config->get('supercategorymenuadvanced_code'));
			if(!isset($accountDetails) || ($accountDetails->error)){
				$this->all_links = false;
			}else{
				$this->all_links = true;
			}
			if ($this->all_links) {
				$data['supercategorymenuadvanced_accountDetails'] = "Thanks for register this extension, {$accountDetails->extension} for {$accountDetails->domain} , now you have full support on support.ocmodules.com";			
			    $data['rg']=true;
			if(!$accountDetails->approved){
				$this->error['warning'] = "Extension registered, waiting to be approved.";
			}
			
			}else{ //el codigo es incorrecto
				$data['supercategorymenuadvanced_accountDetails'] = "Extension not registered, please register to get support. <a class=\"register\" href=\"".$this->BASE_URL."&extension=".urlencode($this->EXTENSION)."&domain=".urlencode($_SERVER['SERVER_NAME'])."\"> Register</a>";
				$this->error['warning'] = "Extension not registered, please register to get support.";
			}
		} 
		
			
		
		$data['action'] = $this->url->link('module/supercategorymenuadvanced/settings', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('module/supercategorymenuadvanced', 'token=' . $this->session->data['token'], 'SSL');
		$data['settings_btn'] = $this->url->link('module/supercategorymenuadvanced/settings', 'token=' . $this->session->data['token'], 'SSL');
		
	    $data['button_save'] 		= $this->language->get('button_save');
		$data['button_exit']		= $this->language->get('button_exit');
		$data['heading_title']					= $this->language->get('heading_title2');
		$data['text_form_settings']					= $this->language->get('text_form_settings');
		$data['tab_manufacturer']					= $this->language->get('tab_manufacturer');
		$data['entry_manufacturer'] 				= $this->language->get('entry_manufacturer');
		$data['entry_manufacturer_explanation'] 	= $this->language->get('entry_manufacturer_explanation');
		$data['entry_list_number'] 					= $this->language->get('entry_list_number');
		$data['entry_list_number_explanation']		= $this->language->get('entry_list_number_explanation');
		$data['entry_order_explanation']			= $this->language->get('entry_order_explanation');
		$data['entry_view_explanation']				= $this->language->get('entry_view_explanation');
		$data['entry_search_explanation']			= $this->language->get('entry_search_explanation');
		$data['entry_open_explanation']				= $this->language->get('entry_open_explanation');
		$data['tab_categories']						= $this->language->get('tab_categories');
		$data['tab_pricerange']						= $this->language->get('tab_pricerange');
		$data['tab_reviews'] 						= $this->language->get('tab_reviews');
		$data['tab_layouts']						= $this->language->get('tab_layouts');
		$data['tab_stock']							= $this->language->get('tab_stock');
		$data['tab_styles']							= $this->language->get('tab_styles');
		$data['tab_templates']						= $this->language->get('tab_templates');
		$data['tab_filters_order']					= $this->language->get('tab_filters_order');
		$data['entry_order']						= $this->language->get('entry_order');
		$data['entry_view']							= $this->language->get('entry_view');
		$data['entry_search']						= $this->language->get('entry_search');
		$data['text_yes']							= $this->language->get('text_yes');
		$data['text_no']							= $this->language->get('text_no');
		$data['entry_open']							= $this->language->get('entry_open');
		$data['text_open'] 							= $this->language->get('text_open');
		$data['text_close'] 						= $this->language->get('text_close');
		$data['entry_list'] 						= $this->language->get('entry_list');
		$data['entry_select'] 						= $this->language->get('entry_select');
		$data['ASC'] 								= $this->language->get('ASC');
		$data['DESC'] 								= $this->language->get('DESC');
		$data['entry_category_explanation']			= $this->language->get('entry_category_explanation');
		$data['entry_category ']					= $this->language->get('entry_category ');
		$data['entry_category_asearch']				= $this->language->get('entry_category_asearch');
		$data['entry_reset_category']				= $this->language->get('entry_reset_category');
		$data['entry_style']						= $this->language->get('entry_style');
		$data['entry_category']						= $this->language->get('entry_category');
		$data['text_count'] 						= $this->language->get('text_count');
		$data['text_human'] 						= $this->language->get('text_human');
		$data['entry_asearch_explanation']			= $this->language->get('entry_asearch_explanation');
		$data['entry_style_explanation']			= $this->language->get('entry_style_explanation');
		$data['opencart']							= $this->language->get('opencart');
		$data['entry_reset_category_explanation']	= $this->language->get('entry_reset_category_explanation');
		$data['entry_pricerange']					= $this->language->get('entry_pricerange');
		$data['entry_pricerange_explanation']		= $this->language->get('entry_pricerange_explanation');
		$data['entry_slider']						= $this->language->get('entry_slider');
		$data['entry_set_vat']						= $this->language->get('entry_set_vat');
		$data['entry_set_vat_explanation']			= $this->language->get('entry_set_vat_explanation');
		$data['default_vat_price_range']			= $this->language->get('default_vat_price_range');
		$data['entry_reviews']						= $this->language->get('entry_reviews');
		$data['entry_reviews_tipo']					= $this->language->get('entry_reviews_tipo');
		$data['entry_reviews_tipo_explanation']		= $this->language->get('entry_reviews_tipo_explanation');
		$data['entry_reviews_num']					= $this->language->get('entry_reviews_num');
		$data['entry_reviews_avg']					= $this->language->get('entry_reviews_avg');
		$data['entry_stock']						= $this->language->get('entry_stock');
		$data['entry_stock_explanation']			= $this->language->get('entry_stock_explanation');
		$data['entry_special']						= $this->language->get('entry_special');
		$data['entry_special_explanation']			= $this->language->get('entry_special_explanation');
		$data['entry_arrivals']						= $this->language->get('entry_arrivals');
		$data['entry_number_day']					= $this->language->get('entry_number_day');
		$data['entry_arrivals_explanation']			= $this->language->get('entry_arrivals_explanation');
		$data['entry_clearance']					= $this->language->get('entry_clearance');
		$data['entry_select_clearance']				= $this->language->get('entry_select_clearance');
		$data['entry_clearance_explanation']		= $this->language->get('entry_clearance_explanation');
		$data['entry_template']						= $this->language->get('entry_template');
		$data['entry_truncate']						= $this->language->get('entry_truncate');
		$data['entry_seo_keyword_manufacturer']		= $this->language->get('entry_seo_keyword_manufacturer');
		$data['entry_mode']							= $this->language->get('entry_mode');
		$data['entry_seo_keyword_category']			= $this->language->get('entry_seo_keyword_category');
		$data['entry_option_tip']					= $this->language->get('entry_option_tip');
		$data['entry_asearch_filters']				= $this->language->get('entry_asearch_filters');
		$data['entry_trigger_see_more']				= $this->language->get('entry_trigger_see_more');
		$data['entry_menu_filters']					= $this->language->get('entry_menu_filters');
		$data['entry_count']						= $this->language->get('entry_count');
		$data['entry_mode_explanation']				= $this->language->get('entry_mode_explanation');
		$data['text_production']					= $this->language->get('text_production');
		$data['text_developing']					= $this->language->get('text_developing');
		$data['settings_mode']						= $this->language->get('settings_mode');
		$data['text_records']						= $this->language->get('text_records');
		$data['entry_truncate_explanation']			= $this->language->get('entry_truncate_explanation');
		$data['button_truncate']					= $this->language->get('button_truncate');
		$data['entry_seo_keyword_explanation']		= $this->language->get('entry_seo_keyword_explanation');
		$data['entry_seo_keyword_explanation2']		= $this->language->get('entry_seo_keyword_explanation2');
		$data['entry_image_option_menu']			= $this->language->get('entry_image_option_menu');
		$data['entry_nofollow']						= $this->language->get('entry_nofollow');
		$data['entry_track_google']					= $this->language->get('entry_track_google');
		$data['entry_ocscroll']						= $this->language->get('entry_ocscroll');
		$data['tab_settings']						= $this->language->get('tab_settings');
		$data['tab_ajax']							= $this->language->get('tab_ajax');
		$data['tab_admincache']						= $this->language->get('tab_admincache');
		$data['tab_register']						= $this->language->get('tab_register');
		$data['tab_contact']						= $this->language->get('tab_contact');
		$data['entry_image_option_menu_explanation']= $this->language->get('entry_image_option_menu_explanation');
		$data['entry_option_tip_explanation']		= $this->language->get('entry_option_tip_explanation');
		$data['entry_asearch_filters_explanation']	= $this->language->get('entry_asearch_filters_explanation');
		$data['entry_see_more_trigger_explanation']	= $this->language->get('entry_see_more_trigger_explanation');
		$data['entry_menu_filters_explanation']		= $this->language->get('entry_menu_filters_explanation');
		$data['entry_count_explanation']			= $this->language->get('entry_count_explanation');
		$data['entry_nofollow_explanation']			= $this->language->get('entry_nofollow_explanation');
		$data['entry_track_google_explanation']		= $this->language->get('entry_track_google_explanation');
		$data['entry_ocscroll_explanation']			= $this->language->get('entry_ocscroll_explanation');
		$data['entry_ajax']							= $this->language->get('entry_ajax');
		$data['entry_ajax_explanation']				= $this->language->get('entry_ajax_explanation');
		$data['entry_ajax_image']					= $this->language->get('entry_ajax_image');
		$data['entry_ajax_loader']					= $this->language->get('entry_ajax_loader');
		$data['loader_explanation']					= $this->language->get('loader_explanation');
		$data['register_status']					= $this->language->get('register_status');
		$data['entry_status']						= $this->language->get('entry_status');		
		$data['text_enabled']						= $this->language->get('text_enabled');
		$data['text_disabled']						= $this->language->get('text_disabled');
		$data['']		= $this->language->get('');
		$data['']		= $this->language->get('');
		$data['']		= $this->language->get('');
		$data['']		= $this->language->get('');
		$data['']		= $this->language->get('');
		$data['']		= $this->language->get('');		
		
		$data['']		= $this->language->get('');
		$data['']		= $this->language->get('');
		$data['']		= $this->language->get('');
		$data['']		= $this->language->get('');
		$data['']		= $this->language->get('');
		$data['']		= $this->language->get('');
		$data['']		= $this->language->get('');
		$data['']		= $this->language->get('');		
		
		
		
				
		
				
		//int with default store
		$this->load->model('setting/store');
		
		
		
		$this->load->model('extension/extension');
		
		
		
		$data['stores']=array(array('name' => "Main Store",'store_id' => 0));
		$store_id=0;
		
		$results = $this->model_setting_store->getStores();
			foreach ($results as $result) {
			
			$store_id.=",".$result['store_id'];
			
			$data['stores'][$result['store_id']] = array(
					'store_id' => $result['store_id'],
					'name' => $result['name'],
					
				);
			}
				
		

		$stores=explode(",",$store_id);
		
		


        /*************************************
		  SETTINGS
		**************************************/
			
		
		foreach ($stores as $store){
		 
		  
		 $settings_values = $this->model_module_supercategorymenuadvanced->getMySetting('SETTINGS_'.$store,$store);
		
		 
		 $all_settings=$manufacturer_data=$pricerange_data=$review_data=$category_data=$stock_data =$template_menu_data =$style_data =array();
		
		
		$general_data = array(
		
			'num_registros'=>$this->model_module_supercategorymenuadvanced->getCacheCount($store),
			'menu_mode'	=> isset($settings_values['general_data']['menu_mode'])? $settings_values['general_data']['menu_mode'] : "production",
			'track_google'=> isset($settings_values['general_data']['track_google'])? $settings_values['general_data']['track_google'] : 0,
			'image_option_width'=> isset($settings_values['general_data']['image_option_width'])? $settings_values['general_data']['image_option_width'] : 20,
			'image_option_height'=> isset($settings_values['general_data']['image_option_height'])? $settings_values['general_data']['image_option_height'] : 20,
			'option_tip'=> isset($settings_values['general_data']['option_tip'])? $settings_values['general_data']['option_tip'] : 0,
			'nofollow'=> isset($settings_values['general_data']['nofollow'])? $settings_values['general_data']['nofollow'] : 0,
			'see_more_trigger'=> isset($settings_values['general_data']['see_more_trigger'])? $settings_values['general_data']['see_more_trigger'] : 0,
			'asearch_filters'=> isset($settings_values['general_data']['asearch_filters'])? $settings_values['general_data']['asearch_filters'] : 0,
		    'menu_filters'=> isset($settings_values['general_data']['menu_filters'])? $settings_values['general_data']['menu_filters'] : 0,
		    'ocscroll'=> isset($settings_values['general_data']['ocscroll'])? $settings_values['general_data']['ocscroll'] : 0,
		    'countp'=> isset($settings_values['general_data']['countp'])? $settings_values['general_data']['countp'] : 0,
		);
		
		
		$ajax_data = array(
		
			
			'enable'  		=> isset($settings_values['ajax']['enable'])? $settings_values['ajax']['enable'] : 0,
			'loader'  		=> isset($settings_values['ajax']['loader'])? $settings_values['ajax']['loader'] : 0,
			'loader_image'  => isset($settings_values['ajax']['loader_image'])? $settings_values['ajax']['loader_image'] : '103.png',
			//'speedmenu'  	=> isset($settings_values['ajax']['loader'])? $settings_values['ajax']['speedmenu'] : 2000,
			//speedresults'  => isset($settings_values['ajax']['speedresults'])? $settings_values['ajax']['speedresults'] : 2000,
			
		
		);
	
		
		$version_data = array();
		
		$extensionDetails = $this->get_currentVersion();
		
		if(isset($extensionDetails) && (!$extensionDetails->error)){
		    $data['version']['name']=$extensionDetails->extension_name;
			$data['version']['extension_opencart_url']=$extensionDetails->extension_opencart_url;
			$data['version']['current_version']=$extensionDetails->extension_current_version;
			$data['version']['whats_new']=$extensionDetails->extension_last_update;
		
			foreach($extensionDetails->other_modules as $other_modules){
				$data['version']['modules'][]=array(
						'name'		=>$other_modules->extension_name,
						'version'	=>$other_modules->extension_current_version,
						'extension_opencart_url' 		=>$other_modules->extension_opencart_url,
						'resume' 	=>$other_modules->extension_description,
						'video' 	=>$other_modules->extension_video,
						);
				 }
			
		}
		
			
		
		 $manufacturer_data = array(
						'enable'  		=> isset($settings_values['manufacturer']['enable'])? $settings_values['manufacturer']['enable'] : 0,
						'list_number'	=> isset($settings_values['manufacturer']['list_number'])? $settings_values['manufacturer']['list_number'] : 10,
						'order'			=> isset($settings_values['manufacturer']['order'])? $settings_values['manufacturer']['order'] : "OCASC",	
						'view'			=> isset($settings_values['manufacturer']['view'])? $settings_values['manufacturer']['view'] : "list",
						'searchinput'	=> isset($settings_values['manufacturer']['searchinput'])? $settings_values['manufacturer']['searchinput'] : "no",
						'initval'		=> isset($settings_values['manufacturer']['initval'])? $settings_values['manufacturer']['initval'] :"opened",
						'super_order'	=> isset($settings_values['manufacturer']['super_order'])? $settings_values['manufacturer']['super_order'] : 2,
                      );	
		
		 $category_data = array(
						'enable'  		=> isset($settings_values['categories']['enable'])? $settings_values['categories']['enable'] : 0,
						'asearch'		=> isset($settings_values['categories']['asearch'])? $settings_values['categories']['asearch'] : 0,						
						'reset'			=> isset($settings_values['categories']['reset'])? $settings_values['categories']['reset'] : 0,
						'list_number'	=> isset($settings_values['categories']['list_number'])? $settings_values['categories']['list_number'] : 10,
						'view'			=> isset($settings_values['categories']['view'])? $settings_values['categories']['view'] : "list",
						'searchinput'	=> isset($settings_values['categories']['searchinput'])? $settings_values['categories']['searchinput'] : "no",	
						'initval'		=> isset($settings_values['categories']['initval'])? $settings_values['categories']['initval'] :"opened",
						'super_order'	=> isset($settings_values['categories']['super_order'])? $settings_values['categories']['super_order'] : 1,
						'style'			=> isset($settings_values['categories']['style'])? $settings_values['categories']['style'] : "imagen1",
						'order'			=> isset($settings_values['categories']['order'])? $settings_values['categories']['order'] : "OCASC",
                      );	
		
		 $pricerange_data = array(
						'enable'  		=> isset($settings_values['pricerange']['enable'])? $settings_values['pricerange']['enable'] : 0,
						'view'			=> isset($settings_values['pricerange']['view'])? $settings_values['pricerange']['view'] : "slider",
						'super_order'	=> isset($settings_values['pricerange']['super_order'])? $settings_values['pricerange']['super_order'] : 9,
						'setvat'		=> isset($settings_values['pricerange']['setvat'])? $settings_values['pricerange']['setvat'] : 0,						
						'tax_class_id'	=> isset($settings_values['pricerange']['tax_class_id'])? $settings_values['pricerange']['tax_class_id'] : "",	
						'initval'		=> isset($settings_values['pricerange']['initval'])? $settings_values['pricerange']['initval'] :"opened",
				      );	  
	
		 $reviews_data = array(
						'enable'  		=> isset($settings_values['reviews']['enable'])? $settings_values['reviews']['enable'] : 0,
						'view'			=> isset($settings_values['reviews']['tipo'])? $settings_values['reviews']['tipo'] : "avg",
						'super_order'	=> isset($settings_values['reviews']['super_order'])? $settings_values['reviews']['super_order'] : 7,
						'initval'		=> isset($settings_values['reviews']['initval'])? $settings_values['reviews']['initval'] :"opened",
				      );	  
		   
		   
		   
		$stock_data = array(
						'enable'  		=> isset($settings_values['stock']['enable'])? $settings_values['stock']['enable'] : 0,
						'super_order'	=> isset($settings_values['stock']['super_order'])? $settings_values['stock']['super_order'] : 4,
						'view'			=> isset($settings_values['stock']['view'])? $settings_values['stock']['view'] : "list",
						'recalcular'		=> isset($settings_values['stock']['recalcular'])? $settings_values['stock']['recalcular'] : 0,
						'special'		=> isset($settings_values['stock']['special'])? $settings_values['stock']['special'] : 0,							
						'arrivals'		=> isset($settings_values['stock']['arrivals'])? $settings_values['stock']['arrivals'] : 0,	
						'number_day'	=> isset($settings_values['stock']['number_day'])? $settings_values['stock']['number_day'] : 7,	
						'clearance'		=> isset($settings_values['stock']['clearance'])? $settings_values['stock']['clearance'] : 0,	
						'initval'		=> isset($settings_values['stock']['initval'])? $settings_values['stock']['initval'] :"opened",
						'clearance_value'=> isset($settings_values['stock']['clearance_value'])? $settings_values['stock']['clearance_value'] :$this->language->get('entry_select')
				      );	  
			   
		   
		 $style_data = array(
						'style'  		=> isset($settings_values['styles']['css'])? $settings_values['styles']['css'] : "default",
						'skin_slider'	=> isset($settings_values['styles']['skin_slider'])? $settings_values['styles']['skin_slider'] : "jslider.yellow.classic",
						'template_menu'	=> isset($settings_values['styles']['template_menu'])? $settings_values['styles']['template_menu'] : "supercategorymenu.tpl",
				      
					  
					  
					  );	  
		 
		   
		$filter_data = array(
						'super_order'  		=> isset($settings_values['filter']['super_order'])? $settings_values['filter']['super_order'] : 0,
			);
			
		
		
		$data['seetings']['str'.$store]= array('store_id'=> $store,'manufacturer'=> $manufacturer_data,'pricerange'=> $pricerange_data,'reviews'=> $reviews_data,'stock'=> $stock_data,'styles'=> $style_data,'categories'=> $category_data,'filter'=> $filter_data,'general_data'=> $general_data,
		'ajax'		=> $ajax_data,
		'version_data'	=> $version_data,
		
		
		
		);	
		
		   
		   
		}

		
		
		$data['ajax_loaders'] = array();
		$files = glob(DIR_IMAGE . 'supermenu/loaders/*');
		foreach ($files as $file) {
			$data['ajax_loaders'][] = basename($file);
		}	
		
		//checking if google code is set.
		$data['google_code']=$this->config->get('config_google_analytics');

		$this->load->model('localisation/tax_class');
		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
		
		$this->load->model('localisation/stock_status');
		$data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();
		
		$this->load->model('localisation/language');
		$data['languages'] 	= $this->model_localisation_language->getLanguages();
		
		$this->load->model('design/layout');
		$data['layouts'] 		= $this->model_design_layout->getLayouts();
		
		
		$directories = glob( DIR_CATALOG .'/view/theme/'. $this->config->get('config_template') . '/template/module/supercategorymenu/templates/*V3.tpl');
		
		$data['url_templates']=HTTP_CATALOG .'catalog/view/theme/'. $this->config->get('config_template') . '/template/module/supercategorymenu/templates/';
		
		$data['templates'] = array();
     
		foreach ($directories as $directory) {
			$data['templates'][] = basename($directory);
		}		
		
		
		$data['styles'] = array();

		$directories = glob(DIR_CATALOG . 'view/javascript/jquery/supermenu/templates/*', GLOB_ONLYDIR);

		foreach ($directories as $directory) {
			$data['styles'][] = basename($directory);
		}		
		
			
		if (isset($settings_values['styles']['skin_slider'])){
			$this->document->addStyle(HTTP_CATALOG . 'catalog/view/javascript/jquery/supermenu/slider/skins/'.$settings_values['styles']['skin_slider'].'.css');
		}else{
			
			$this->document->addStyle(HTTP_CATALOG . 'catalog/view/javascript/jquery/supermenu/slider/skins/jslider.yellow.classic.css');
			
		}
		$data['skin_slider'] = array();
		$files = glob(DIR_CATALOG . 'view/javascript/jquery/supermenu/slider/skins/*.css');
		foreach ($files as $file) {
			$data['skin_sliders'][] = basename($file,".css");
		}	
		
		
        if(in_array("ocscroll",$this->model_extension_extension->getInstalled('module'))){
			$data['ocscroll']=true;
		}else{
		   $data['ocscroll']=false;
		}
		
		
		


		

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');


		$this->response->setOutput($this->load->view('module/supercategorymenu/supercategorymenuadvanced_settings.tpl', $data));
		
	 }
	
	
	
     private function getList() {
   		
		$this->document->addScript('view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.pack.js');
		$this->document->addStyle('view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.css');
	    $this->document->addScript(HTTP_CATALOG . 'catalog/view/javascript/jquery/supermenu/supermenu_base.js');
		
		$this->load->language('module/supercategorymenuadvanced');
		
		$data['breadcrumbs'] = array();
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/supercategorymenuadvanced', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$data['action'] 		= $this->url->link('module/supercategorymenuadvanced', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] 		= $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		$data['settings_btn'] = $this->url->link('module/supercategorymenuadvanced/settings', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['button_save'] 		= $this->language->get('button_save');
		$data['button_exit']		= $this->language->get('button_exit');
		$data['button_settings']	= $this->language->get('button_settings');
	
		
					
	    
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$filter_data = array(
			'start' => ($page - 1) * 2,
			'limit' => $this->config->get('config_limit_admin')
		);
		
		//int with default store
		$this->load->model('setting/store');
		$data['stores']=array(array('name' => "Main Store",'store_id' => 0));
		$store_id=0;
		
		$results = $this->model_setting_store->getStores();
			foreach ($results as $result) {
			
			$store_id.=",".$result['store_id'];
			
			$data['stores'][$result['store_id']] = array(
					'store_id' => $result['store_id'],
					'name' => $result['name'],
					
				);
			}
				
		

		$stores=explode(",",$store_id);
		
		
		
		
		$this->load->model('module/supermanufacturermenuadvanced');
		
		
		//DATA FOR MANUFACTURERS
		$data['manufacturers'] = array();
		
		foreach ($stores as $store){
			
		    $man_total = count($this->model_module_supermanufacturermenuadvanced->getManufacturers($store));
		    $results = $this->model_module_supermanufacturermenuadvanced->getManufacturers($store,$filter_data);
		    $url = '';
			$url .= '&store=' . $store;
			$pagination = new Pagination();
		    $pagination->total = $man_total;
		    $pagination->page = $page;
		    $pagination->limit = $this->config->get('config_limit_admin');
		    $pagination->url = $this->url->link('module/supercategorymenuadvanced/paginationman', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
     	    $data['pagination_man'.$store] = $pagination->render();
		    $data['results_man'.$store] = sprintf($this->language->get('text_pagination'), ($man_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($man_total - $this->config->get('config_limit_admin'))) ? $man_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $man_total, ceil($man_total / $this->config->get('config_limit_admin')));
			
			// Data for home manufacturers
			$submanufacturer[] = array(
				'manufacturer_id' => 0,
				'name'        => $this->language->get('category_home'),
				'href' => $this->url->link('module/supermanufacturermenuadvanced/GetAllValuesManufacturer', 'token=' . $this->session->data['token'] . '&manufacturer_id=0&store_id='.$store, 'SSL')
			);	
		
		
		$data['manufacturers']['str'.$store][] = array(
				'manufacturer_id' => 0,
				'name'        => $this->language->get('category_home'),
				'submanufacturer' =>$submanufacturer
		   );
		
	
		
		$submanufacturer = array();
		
        foreach ($results as $result) {
			
			//first subcategory for main category
			$submanufacturer[] = array(
				'manufacturer_id' => $result['manufacturer_id'],
				'name'        	  => $result['name'],
				'href'			  => $this->url->link('module/supermanufacturermenuadvanced/GetAllValuesManufacturer', 'token=' . $this->session->data['token'] . '&manufacturer_id=' . $result['manufacturer_id'].'&store_id='.$store, 'SSL')
			);	
				//search all sub subsub subsubsub etc.. from main category
				
			$data['manufacturers']['str'.$store][] = array(
					'manufacturer_id' 	=> $result['manufacturer_id'],
					'name'       		=> $result['name'],
					'submanufacturer' 	=> $submanufacturer
				);
			
			$submanufacturer = array();
			
		}
		}
	
		foreach ($stores as $store){
			
			$data['categories']['str'.$store] = array();
			$this->load->model('module/supercategorymenuadvanced');
			
			
			$subcategories[] = array(
				'category_id' => 0,
				'name'        => $this->language->get('category_home'),
				'parent_id'   => 0,
				'href' => $this->url->link('module/supercategorymenuadvanced/GetAllValues', 'token=' . $this->session->data['token'] . '&category_id=0&store_id='.$store, 'SSL')
			);	
		
		// Data for home category
			$data['categories']['str'.$store][] = array(
				'category_id' => 0,
				'name'        => $this->language->get('category_home')."<br><small>".$this->language->get('category_home_help')."</small>",
				'subcategories' =>$subcategories
		   );
			
			
			
			//$results = $this->model_module_supercategorymenuadvanced->getCategoriesParent_id(0, $store);
			
			// $this->config->get('config_limit_admin')
			

		    $category_total = count($this->model_module_supercategorymenuadvanced->getCategoriesParent_id(0, $store));

		    $results = $this->model_module_supercategorymenuadvanced->getCategoriesParent_id(0, $store,$filter_data);

					
			$url = '';
			
			$url .= '&store=' . $store;
			
		
				
			$pagination = new Pagination();
		    $pagination->total = $category_total;
		    $pagination->page = $page;
		    $pagination->limit = $this->config->get('config_limit_admin');
		    $pagination->url = $this->url->link('module/supercategorymenuadvanced/paginationcat', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');


		    $data['pagination_cat'.$store] = $pagination->render();

		    $data['results_cat'.$store] = sprintf($this->language->get('text_pagination'), ($category_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($category_total - $this->config->get('config_limit_admin'))) ? $category_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $category_total, ceil($category_total / $this->config->get('config_limit_admin')));
			
			
			
			$subcategories = array();
			
			foreach ($results as $result) {
			
			//first subcategory for main category
			$subcategories[] = array(
				'category_id' => $result['category_id'],
				'name'        => $result['name'],
				'parent_id'   => $result['parent_id'],
				'href' => $this->url->link('module/supercategorymenuadvanced/GetAllValues', 'token=' . $this->session->data['token'] . '&category_id=' . $result['category_id'] .'&store_id='.$store, 'SSL')
			);	
				//search all sub subsub subsubsub etc.. from main category
				$list_of_subcat = array();
				$list_of_subcats=$this->model_module_supercategorymenuadvanced->getCategories($result['category_id'], $store);
				 foreach ($list_of_subcats as $list_of_subcat) {
				
					$subcategories[] = array(
						'category_id' => $list_of_subcat['category_id'],
						'name'        => $list_of_subcat['name'],
						'parent_id'   => $list_of_subcat['parent_id'],
						'href' => $this->url->link('module/supercategorymenuadvanced/GetAllValues', 'token=' . $this->session->data['token'] . '&category_id=' . $list_of_subcat['category_id'] .'&store_id='.$store, 'SSL')
					);	
				}
				
			if ($result['parent_id']==0){// es una principal		
				$data['categories']['str'.$store][] = array(
					'category_id' 	=> $result['category_id'],
					'name'       	=> $result['name'],
					'subcategories' => $subcategories
				);
			
			$subcategories = array();
			}
			
		}
						
		}//end stores
		

		$data['heading_title'] 	 = $this->language->get('heading_title').' Mode - '.$this->config->get('supercategorymenuadvanced_mode');
		$data['text_no_results']   = $this->language->get('text_no_results');
		$data['column_name'] 		 = $this->language->get('column_name');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] 	 = $this->language->get('column_action');
		$data['button_insert'] 	 = $this->language->get('button_insert');
		$data['button_delete'] 	 = $this->language->get('button_delete');
		
		$data['entry_select_att'] 		= $this->language->get('entry_select_att');
		$data['entry_build_cache_att'] 	= $this->language->get('entry_build_cache_att');
		$data['entry_delete_cache_att'] 	= $this->language->get('entry_delete_cache_att');
		$data['entry_separator'] 						= $this->language->get('entry_separator');
		$data['text_edit']		= $this->language->get('text_edit');
		$data['token'] =$this->session->data['token'];
		
 
 		if (isset($this->error['warning'])) {
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
		
		//$this->template = 'module/supercategorymenu/supercategorymenuadvanced.tpl';
		//$this->children = array('common/header','common/footer');				
		//$this->response->setOutput($this->render());
	
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');


		$this->response->setOutput($this->load->view('module/supercategorymenu/supercategorymenuadvanced.tpl', $data));
		
		
	}
   
   
    
   
   
   
   public function paginationcat (){
	   
	   
	  	$this->load->language('module/supercategorymenuadvanced');
				
		$data['button_save'] 		= $this->language->get('button_save');
		$data['button_exit']		= $this->language->get('button_exit');
		$data['button_settings']	= $this->language->get('button_settings');
	
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['store'])) {
			$store=$data['store'] = $this->request->get['store'];
		} else {
			$store=$data['store']= 0;
		}
		
		
		$data['categories']= array();
	
		$this->load->model('module/supercategorymenuadvanced');
			
			// 
			$filter_data = array('start' => ($page - 1) * 2,'limit' => $this->config->get('config_limit_admin'));

		    $category_total = count($this->model_module_supercategorymenuadvanced->getCategoriesParent_id(0, $store));

		    $results = $this->model_module_supercategorymenuadvanced->getCategoriesParent_id(0, $store,$filter_data);

					
			$url = '';
			$url .= '&store=' . $store;
			
		
				
			$pagination = new Pagination();
		    $pagination->total = $category_total;
		    $pagination->page = $page;
		    $pagination->limit = $this->config->get('config_limit_admin');
		    $pagination->url = $this->url->link('module/supercategorymenuadvanced/paginationcat', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');


		    $data['pagination_cat'.$store] = $pagination->render();

		    $data['results_cat'.$store] = sprintf($this->language->get('text_pagination'), ($category_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($category_total - $this->config->get('config_limit_admin'))) ? $category_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $category_total, ceil($category_total / $this->config->get('config_limit_admin')));
			
			
			
			$subcategories = array();
			
			foreach ($results as $result) {
			
			//first subcategory for main category
			$subcategories[] = array(
				'category_id' => $result['category_id'],
				'name'        => $result['name'],
				'parent_id'   => $result['parent_id'],
				'href' => $this->url->link('module/supercategorymenuadvanced/GetAllValues', 'token=' . $this->session->data['token'] . '&category_id=' . $result['category_id'] .'&store_id='.$store, 'SSL')
			);	
				//search all sub subsub subsubsub etc.. from main category
				$list_of_subcat = array();
				$list_of_subcats=$this->model_module_supercategorymenuadvanced->getCategories($result['category_id'], $store);
				 foreach ($list_of_subcats as $list_of_subcat) {
				
					$subcategories[] = array(
						'category_id' => $list_of_subcat['category_id'],
						'name'        => $list_of_subcat['name'],
						'parent_id'   => $list_of_subcat['parent_id'],
						'href' => $this->url->link('module/supercategorymenuadvanced/GetAllValues', 'token=' . $this->session->data['token'] . '&category_id=' . $list_of_subcat['category_id'] .'&store_id='.$store, 'SSL')
					);	
				}
				
			if ($result['parent_id']==0){// es una principal		
				$data['categories']['str'.$store][] = array(
					'category_id' 	=> $result['category_id'],
					'name'       	=> $result['name'],
					'subcategories' => $subcategories
				);
			
			$subcategories = array();
			}
			
		}
						
		

		$data['text_no_results']   = $this->language->get('text_no_results');
		$data['column_name'] 		 = $this->language->get('column_name');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] 	 = $this->language->get('column_action');
		$data['button_insert'] 	 = $this->language->get('button_insert');
		$data['button_delete'] 	 = $this->language->get('button_delete');
		
		$data['entry_select_att'] 		= $this->language->get('entry_select_att');
		$data['entry_build_cache_att'] 	= $this->language->get('entry_build_cache_att');
		$data['entry_delete_cache_att'] 	= $this->language->get('entry_delete_cache_att');
		$data['entry_separator'] 						= $this->language->get('entry_separator');
		$data['text_edit']		= $this->language->get('text_edit');
		$data['token'] =$this->session->data['token'];
		
 
 		if (isset($this->error['warning'])) {
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
		
		


		$this->response->setOutput($this->load->view('module/supercategorymenu/supercategorymenuadvanced_cat.tpl', $data));
	   
  }
   
   
   
   
   
   
   
   
   
   
   
   
   
   
    private function do_post_request($url, $_data, $optional_headers = null)
	{
		$data = array();
		while(list($n,$v) = each($_data)){
			$data[] = urlencode($n)."=".urlencode($v);
		}
		$data = implode('&', $data);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_REFERER,$_SERVER['SERVER_NAME']);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_HEADER, false); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
		curl_close($ch);
	  	return $response;
	}


	public function _is_vqmod_installed() {
		if(class_exists('VQMod')) {
        	 return true;
    	}else{
			return false;
		}
	}

		
	public function SetAllValues() {   
		
		$this->load->model('module/supercategorymenuadvanced');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {	
		$this->model_module_supercategorymenuadvanced->editOneSetting('supercategorymenuadvanced', $this->request->post['dnd'],$this->request->post,$this->request->post['store_id']);
		
		$this->model_module_supercategorymenuadvanced->DeleteCacheValues($this->request->post['category_id'],$this->request->post['store_id']);
		
		}
	}
	
	
	public function SetAllValuesCategories() {   
		
		$this->load->model('module/supercategorymenuadvanced');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {		
		
		$this->model_module_supercategorymenuadvanced->editOneSetting('supercategorymenuadvanced', $this->request->post['dnd'],$this->request->post,$this->request->post['store_id']);
		
		$this->model_module_supercategorymenuadvanced->DeleteCacheValues($this->request->post['category_id'],$this->request->post['store_id']);
		$subcategories=$this->model_module_supercategorymenuadvanced->getCategoriesParent_id($this->request->post['category_id'],$this->request->post['store_id']);
		
		if ($subcategories){
		
		 foreach ($subcategories as $subcategory) {
			
				//first subcategory for main category
				$subcategories2[$subcategory['category_id']] = $subcategory['category_id'];	
				//search all sub subsub subsubsub etc.. from main category
				$list_of_subcat = array();
				$list_of_subcats=$this->model_module_supercategorymenuadvanced->getCategories($subcategory['category_id'],$this->request->post['store_id']);
				 foreach ($list_of_subcats as $list_of_subcat) {
					$subcategories2[$list_of_subcat['category_id']] = $list_of_subcat['category_id'];	
				}
		 
		}
		
			foreach ($subcategories2 as $subcategory2){
				$valores=array();
			
				$valores = array("VALORES_".$subcategory2 => $this->request->post['VALORES_'.$this->request->post['category_id'].''],);
	
				$this->model_module_supercategorymenuadvanced->editOneSetting('supercategorymenuadvanced', "VALORES_".$subcategory2,$valores,$this->request->post['store_id']);
				$this->model_module_supercategorymenuadvanced->DeleteCacheValues($subcategory2,$this->request->post['store_id']);		
			}
		
		}
		}
	}



	
	
	public function GetAllValues(){
		
		if (isset($this->request->get['category_id'])) {
			$category_id = $this->request->get['category_id'];
		} else {
			$category_id = 'error';
		}
		
		if (isset($this->request->get['store_id'])) {
			$store_id = $this->request->get['store_id'];
		} else {
			$store_id = 'error';
		}
		
		$this->load->model('localisation/language');
		$data['languages']=$languages= $this->model_localisation_language->getLanguages();

		$data['action_save_attribute'] = $this->url->link('module/supercategorymenuadvanced/SetAllValues', 'token=' . $this->session->data['token'], 'SSL');

		$this->load->language('module/supercategorymenuadvanced');
     	$data['entry_all_values']				= $this->language->get('entry_all_values');
		$data['entry_value'] 					= $this->language->get('entry_value');
		$data['entry_separator'] 				= $this->language->get('entry_separator');
		$data['entry_examples'] 				= $this->language->get('entry_examples');
		$data['entry_separator_explanation'] 	= $this->language->get('entry_separator_explanation');
		$data['entry_order'] 					= $this->language->get('entry_order');
		$data['entry_values_explanation'] 	= $this->language->get('entry_values_explanation');
		$data['text_none'] 					= $this->language->get('text_none');
		$data['button_save'] 					= $this->language->get('button_save');
		$data['button_close'] 				= $this->language->get('button_close');
		$data['text_count'] 					= $this->language->get('text_count');
		$data['text_human'] 					= $this->language->get('text_human');
		$data['text_computer'] 				= $this->language->get('text_computer');
		$data['entry_sort_order'] 			= $this->language->get('entry_sort_order');		
		$data['entry_num_products'] 			= $this->language->get('entry_num_products');	
		$data['entry_list'] 					= $this->language->get('entry_list');	
		$data['entry_select'] 				= $this->language->get('entry_select');
		$data['entry_view'] 					= $this->language->get('entry_view2');	
		$data['entry_image'] 					= $this->language->get('entry_image');	
		$data['text_what_is'] 				= $this->language->get('text_what_is');		
		$data['entry_open'] 					= $this->language->get('entry_open');
		$data['entry_search'] 				= $this->language->get('entry_search');
		$data['text_yes'] 					= $this->language->get('text_yes');
		$data['text_no'] 						= $this->language->get('text_no');
		$data['text_open'] 					= $this->language->get('text_open');
		$data['text_close'] 					= $this->language->get('text_close');
		$data['text_info'] 					= $this->language->get('text_info');	
		$data['button_save_all'] 				= $this->language->get('button_save_all');
		$data['ASC'] 							= $this->language->get('ASC');
		$data['DESC'] 						= $this->language->get('DESC');  
		$data['novaluestxt'] 					= $this->language->get('novaluestxt');  
		$data['entry_slider']				    = $this->language->get('entry_slider');
		$data['opencart'] 					= $this->language->get('opencart');
		$data['entry_unit'] 					= $this->language->get('entry_unit');
		$data['no_attributes_c'] 				= $this->language->get('no_attributes_c');
		$data['no_options_c'] 				= $this->language->get('no_options_c');
		$data['no_attributes_m'] 				= $this->language->get('no_attributes_m');
		$data['no_options_m'] 				= $this->language->get('no_options_m');
		$data['entry_attributes']				= $this->language->get('entry_attributes');
		$data['entry_options']				= $this->language->get('entry_options');
		$data['entry_product_info']			= $this->language->get('entry_product_info');
        $data['entry_remove_tabs']			= $this->language->get('entry_remove_tabs');
		
		$this->load->model('module/supercategorymenuadvanced'); 	
		
		
		if ((int)$category_id==0){
			
			$data['success'] =sprintf($this->language->get('success_set'), $this->language->get('category_home'));
		
		}else{
		
			$category_path = $this->model_module_supercategorymenuadvanced->getPath((int)$category_id);
			$data['success'] =sprintf($this->language->get('success_set'), $category_path);
    	
		}
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		$data['category'] = array();
					
		//$category_attributes=array();			
					
		$category_values = $this->model_module_supercategorymenuadvanced->getMySetting('VALORES_'.$category_id,$store_id);
 		
		/***************************************************************************************
		    PRODUCT INFO
		
		****************************************************************************************/
	
		$results=array(
		array('productinfo_id'=>1,'name'=>"Width",'short_name'=>"w" ), 
		array('productinfo_id'=>2,'name'=>"Height",'short_name'=>"h" ),
		array('productinfo_id'=>3,'name'=>"Length",'short_name'=>"l" ),
		array('productinfo_id'=>4,'name'=>"Model",'short_name'=>"mo" ),
		array('productinfo_id'=>5,'name'=>"sku",'short_name'=>"sk" ),
		array('productinfo_id'=>6,'name'=>"UPC",'short_name'=>"up" ),
		array('productinfo_id'=>7,'name'=>"Location",'short_name'=>"lo" ),
		array('productinfo_id'=>8,'name'=>"Weight",'short_name'=>"wg" ),
		array('productinfo_id'=>9,'name'=>"EAN",'short_name'=>"e" ), 
		array('productinfo_id'=>10,'name'=>"ISBN",'short_name'=>"i" ),	
		array('productinfo_id'=>11,'name'=>"MPN",'short_name'=>"p" ),	
		array('productinfo_id'=>12,'name'=>"Jan",'short_name'=>"j" ));
	   
	    $productinfo_data = array();
		
		foreach ($results as $result) {
				if (is_array($category_values) && isset($category_values['productinfo'][$result['productinfo_id']]['productinfo_id'])){
						$productinfo_checked=true;
						$productinfo_seperator=isset($category_values['productinfo'][$result['productinfo_id']]['separator'])? $category_values['productinfo'][$result['productinfo_id']]['separator'] : "no";	
						$productinfo_order=isset($category_values['productinfo'][$result['productinfo_id']]['sort_order'])? $category_values['productinfo'][$result['productinfo_id']]['sort_order'] : 0;	
						$productinfo_orderval=isset($category_values['productinfo'][$result['productinfo_id']]['orderval'])? $category_values['productinfo'][$result['productinfo_id']]['orderval'] : "OHASC";	
						$productinfo_number=isset($category_values['productinfo'][$result['productinfo_id']]['number'])? $category_values['productinfo'][$result['productinfo_id']]['number'] : 8;	
						$productinfo_view=isset($category_values['productinfo'][$result['productinfo_id']]['view'])? $category_values['productinfo'][$result['productinfo_id']]['view'] :"slider" ;	
						$productinfo_info=isset($category_values['productinfo'][$result['productinfo_id']]['info'])? $category_values['productinfo'][$result['productinfo_id']]['info'] :"no" ;
				 		$productinfo_initval=isset($category_values['productinfo'][$result['productinfo_id']]['initval'])? $category_values['productinfo'][$result['productinfo_id']]['initval'] :"opened" ;
						$productinfo_searchinput=isset($category_values['productinfo'][$result['productinfo_id']]['searchinput'])? $category_values['productinfo'][$result['productinfo_id']]['searchinput'] :"no" ;
						$productinfo_unit=isset($category_values['productinfo'][$result['productinfo_id']]['unit'])? $category_values['productinfo'][$result['productinfo_id']]['unit'] :"" ;
						$productinfo_text_info= array(); 
						foreach ($languages as $language){
							$productinfo_text_info[$language['language_id']]=isset($category_values['productinfo'][$result['productinfo_id']]['text_info'][$language['language_id']])? $category_values['productinfo'][$result['productinfo_id']]['text_info'][$language['language_id']] :"" ;
						}
								
				}else{
						$productinfo_checked=false;
						$productinfo_seperator="no";
						$productinfo_order=0;
						$productinfo_orderval="OHASC"; 
						$productinfo_number="8";
						$productinfo_view="list";
						$productinfo_initval="opened";
						$productinfo_searchinput="no";
						$productinfo_info="no" ;
						$productinfo_unit="" ;
						$productinfo_text_info=0;
						
				}
				
				//Get productinfo for examples
				$productinfo_values=array(); 
				$productinfo_values = $this->model_module_supercategorymenuadvanced->getProductInfoValues($result['name'],$category_id,$store_id);	

				  $productinfo_data[] = array(
						'productinfo_id'  	=> $result['productinfo_id'],
						'name'          	=> $result['name'],
						'short_name'		=> $result['short_name'],
						'checked'			=> $productinfo_checked,
						'separator' 		=> $productinfo_seperator,
						'values'			=> $productinfo_values,
						'sort_order'  		=> $productinfo_order,
						'number'			=> $productinfo_number,
						'orderval'			=> $productinfo_orderval,
						'view'				=> $productinfo_view,
						'what'				=> 'productinfo',
						'initval'			=> $productinfo_initval,
						'searchinput'		=> $productinfo_searchinput,
						'info'				=> $productinfo_info,
						'unit'				=> $productinfo_unit,
						'text_info'			=> $productinfo_text_info
						
					);	
		
			
				//reorder fields by order
				
		
			}
			
		
		
		
		//get options from the category
		$results = $this->model_module_supercategorymenuadvanced->getCategoryOptions($category_id,$store_id);	
		
		$options_data= array();
		
			foreach ($results as $result) {
				if (is_array($category_values) && isset($category_values['options'][$result['option_id']]['option_id'])){
						$option_checked=true;
						$option_seperator=isset($category_values['options'][$result['option_id']]['separator'])? $category_values['options'][$result['option_id']]['separator'] : "no";					
						$option_order=isset($category_values['options'][$result['option_id']]['sort_order'])? $category_values['options'][$result['option_id']]['sort_order'] : 0;	
						$option_orderval=isset($category_values['options'][$result['option_id']]['orderval'])? $category_values['options'][$result['option_id']]['orderval'] : "OHASC";	
						$option_number=isset($category_values['options'][$result['option_id']]['number'])? $category_values['options'][$result['option_id']]['number'] : 8;	
						$option_view=isset($category_values['options'][$result['option_id']]['view'])? $category_values['options'][$result['option_id']]['view'] :"list" ;	
						$option_initval=isset($category_values['options'][$result['option_id']]['initval'])? $category_values['options'][$result['option_id']]['initval'] :"opened" ;
						$option_searchinput=isset($category_values['options'][$result['option_id']]['searchinput'])? $category_values['options'][$result['option_id']]['searchinput'] :"no" ;
						$option_info=isset($category_values['options'][$result['option_id']]['info'])? $category_values['options'][$result['option_id']]['info'] :"no" ;
						$option_unit=isset($category_values['options'][$result['option_id']]['unit'])? $category_values['options'][$result['option_id']]['unit'] :"" ;			
						$option_text_info=array();
						foreach ($languages as $language){
						$option_text_info[$language['language_id']]=isset($category_values['options'][$result['option_id']]['text_info'][$language['language_id']])? $category_values['options'][$result['option_id']]['text_info'][$language['language_id']] :"" ;
						}
				
				}else{
						$option_checked=false;
						$option_seperator="no";
						$option_order=0;
						$option_orderval="OHASC";
						$option_number="8";
						$option_view="list";
						$option_initval="opened";
						$option_searchinput="no";
						$option_info="no" ;
						$option_unit="" ;
						$option_text_info=0;
				}
				
				//Get attributes for examples
				$options_values=array();
				$options_values = $this->model_module_supercategorymenuadvanced->getOptionsValues($result['option_id'],$category_id,$store_id);	
	
		           $options_data[] = array(
						'option_id'  		=> $result['option_id'],
						'name'          	=> $result['name'],
						'short_name'		=> "o",
						'checked'			=> $option_checked,
						'separator' 		=> $option_seperator,
						'values'			=> $options_values,
						'sort_order'  		=> $option_order,
						'number'			=> $option_number,
						'orderval'			=> $option_orderval,
						'view'				=> $option_view,
						'initval'			=> $option_initval,
						'searchinput'		=> $option_searchinput,
						'info'				=> $option_info,
						'what'				=> 'options',
						'unit'				=> $option_unit,
						'text_info'			=> $option_text_info
                      );	
		
										
			}

		
		$results = $this->model_module_supercategorymenuadvanced->getCategoryAttributes($category_id,$store_id);	
       
	    $attribute_data = array();

			foreach ($results as $result) {
				if (is_array($category_values) && isset($category_values['attributes'][$result['attribute_id']]['attribute_id'])){
						$attribute_checked=true;
						$attribute_seperator=isset($category_values['attributes'][$result['attribute_id']]['separator'])? $category_values['attributes'][$result['attribute_id']]['separator'] : "no";	
						$attribute_order=isset($category_values['attributes'][$result['attribute_id']]['sort_order'])? $category_values['attributes'][$result['attribute_id']]['sort_order'] : 0;	
						$attribute_orderval=isset($category_values['attributes'][$result['attribute_id']]['orderval'])? $category_values['attributes'][$result['attribute_id']]['orderval'] : "OHASC";	
						$attribute_number=isset($category_values['attributes'][$result['attribute_id']]['number'])? $category_values['attributes'][$result['attribute_id']]['number'] : 8;	
						$attribute_view=isset($category_values['attributes'][$result['attribute_id']]['view'])? $category_values['attributes'][$result['attribute_id']]['view'] :"list" ;	
						$attribute_initval=isset($category_values['attributes'][$result['attribute_id']]['initval'])? $category_values['attributes'][$result['attribute_id']]['initval'] :"opened" ;
						$attribute_searchinput=isset($category_values['attributes'][$result['attribute_id']]['searchinput'])? $category_values['attributes'][$result['attribute_id']]['searchinput'] :"no" ;
						$attribute_info=isset($category_values['attributes'][$result['attribute_id']]['info'])? $category_values['attributes'][$result['attribute_id']]['info'] :"no" ;
						$attribute_unit=isset($category_values['attributes'][$result['attribute_id']]['unit'])? $category_values['attributes'][$result['attribute_id']]['unit'] :"";
						
						$attribute_text_info=array();
						foreach ($languages as $language){
						$attribute_text_info[$language['language_id']]=isset($category_values['attributes'][$result['attribute_id']]['text_info'][$language['language_id']])? $category_values['attributes'][$result['attribute_id']]['text_info'][$language['language_id']] :"" ;
						}
				}else{
						$attribute_checked=false;
						$attribute_seperator="no";
						$attribute_order=0;
						$attribute_orderval="OHASC";
						$attribute_number="8";
						$attribute_view="list";
						$attribute_initval="opened";
						$attribute_searchinput="no";
						$attribute_info="no" ; 
						$attribute_unit=""; 
						$attribute_text_info=0;
				}
				
				//Get attributes for examples
				$attribute_values=array();
				$attribute_values = $this->model_module_supercategorymenuadvanced->getAttributeValues($result['attribute_id'],$category_id,$store_id);	
	
		           $attribute_data[] = array(
						'attribute_id'  	=> $result['attribute_id'],
						'name'          	=> $result['name'],
						'short_name'		=> "a",
						'checked'			=> $attribute_checked,
						'separator' 		=> $attribute_seperator,
						'values'			=> $attribute_values,
						'sort_order'  		=> $attribute_order,
						'number'			=> $attribute_number,
						'orderval'			=> $attribute_orderval,
						'view'				=> $attribute_view,
						'initval'			=> $attribute_initval,
						'searchinput'		=> $attribute_searchinput,
						'info'				=> $attribute_info,
						'what'				=> 'attributes',
						'unit'				=> $attribute_unit,
						'text_info'			=> $attribute_text_info
					);	
		
				//reorder attributes by order
				
		
			}
			
			//merge attributes and options.
			
			$all_values=array();
			
			$all_values=array_merge($options_data,$attribute_data,$productinfo_data);
			
			$sort_order=array();
			foreach ($all_values as $key => $value) {
               $sort_order[] = $value['sort_order'];
		  	}
              
		  	array_multisort($sort_order, SORT_ASC,$all_values);
			
			$sort_order=array();
			foreach ($attribute_data as $key => $value) {
               $sort_order[] = $value['sort_order'];
		  	}
              
		  	array_multisort($sort_order, SORT_ASC,$attribute_data);
			
			$sort_order=array();
			foreach ($options_data as $key => $value) {
               $sort_order[] = $value['sort_order'];
		  	}
              
		  	array_multisort($sort_order, SORT_ASC,$options_data);
			
			$sort_order=array();
			foreach ($productinfo_data as $key => $value) {
               $sort_order[] = $value['sort_order'];
		  	}
              
		  	array_multisort($sort_order, SORT_ASC,$productinfo_data);
					
			
			$data['category'] = array(
				'category_id' 	=> $category_id,
				'all_values'  	=> $all_values,
				'attributes'	=> $attribute_data,
				'options'		=> $options_data,
				'productinfo'	=> $productinfo_data,
				'parent_id'		=> $this->model_module_supercategorymenuadvanced->getCategoryParent_id($category_id),
				'store'			=> $store_id
				);
	
	
           $data['token'] = $this->session->data['token'];	
		   
		   
		   
		
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		 
		
		//$this->template = 'module/supercategorymenu/supercategorymenuadvanced_values.tpl';
		//$this->response->setOutput($this->render());
		
		$this->response->setOutput($this->load->view('module/supercategorymenu/supercategorymenuadvanced_values.tpl', $data));
		
		
}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/supercategorymenuadvanced')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
	

	public function style() {
		$style = basename($this->request->get['style']);
		if (file_exists(DIR_CATALOG . 'view/javascript/jquery/supermenu/templates/'. $style . '/'. $style . '.png')) {
			$image =  HTTP_CATALOG. 'catalog/view/javascript/jquery/supermenu/templates/'. $style . '/'. $style . '.png'; 
		} else {
			$image = DIR_CATALOG . 'image/no_image.jpg';
		}
		
		$this->response->setOutput('<img src="' . $image . '" alt="" title="" style="border: 1px solid #EEEEEE;" />');
	}
	
	public function SliderStyle() {
		$style = basename($this->request->get['style']);
		//$this->document->addStyle(HTTP_CATALOG . 'catalog/view/javascript/jquery/supermenu/slider/skins/'.$style.'.css');
		$html='<link rel="stylesheet" type="text/css" href="'.HTTP_CATALOG .'catalog/view/javascript/jquery/supermenu/slider/skins/'.$style.'.css" media="screen" />';
		$html.=' <div style="width: 500px; position: relative; top: 32px;" class="slider_content">';
        $html.='      <input id="Slider6" type="slider" name="price" value="30000.5;60000" />';
		$html.='	  <script type="text/javascript" charset="utf-8">';
        $html.='jQuery("#Slider6").slider({ from: 1000, to: 100000, step: 500, smooth: true, round: 0, dimension: "&nbsp;$" });';
     	$html.='</script><div>';
		$this->response->setOutput($html);
	}
	
	
	
	public function DeleteCacheDB() {
		$json = array();
		
		if($this->db->query("TRUNCATE " . DB_PREFIX . "cache_supercategory") && $this->db->query("TRUNCATE " . DB_PREFIX . "cache_supercategory_menu")){
			$json['success']="Database truncated";
			$json['registros']=0;
		}else{
			$json['error']="Error, we could not truncate tables, try again!";
		}
		
		
		$this->response->setOutput(json_encode($json));
		}



	//ADD ON 1
	
	public function GetAllValuesManufacturer(){
		
		if (isset($this->request->get['manufacturer_id'])) {
			$manufacturer_id = $this->request->get['manufacturer_id'];
		} else {
			$manufacturer_id = 'error';
		}

		if (isset($this->request->get['store_id'])) {
			$store_id = $this->request->get['store_id'];
		} else {
			$store_id = 'error';
		}

		$this->load->model('localisation/language');
		$data['languages']=$languages= $this->model_localisation_language->getLanguages();
		$this->load->language('module/supercategorymenuadvanced');

$data['token'] = $this->session->data['token'];	
		$data['action_save_manu'] = $this->url->link('supermenu/supermanufacturermenuadvanced/SetAllValuesManufacturer', 'token=' . $this->session->data['token'], 'SSL');

	   	$data['entry_all_values']				= $this->language->get('entry_all_values');
		$data['entry_value'] 					= $this->language->get('entry_value');
		$data['entry_separator'] 				= $this->language->get('entry_separator');
		$data['entry_examples'] 				= $this->language->get('entry_examples');
		$data['entry_separator_explanation'] 	= $this->language->get('entry_separator_explanation');
		$data['entry_order'] 					= $this->language->get('entry_order');
		$data['entry_values_explanation'] 	= $this->language->get('entry_values_explanation');
		$data['text_none'] 					= $this->language->get('text_none');
		$data['button_save'] 					= $this->language->get('button_save');
		$data['button_close'] 				= $this->language->get('button_close');
		$data['text_count'] 					= $this->language->get('text_count');
		$data['text_human'] 					= $this->language->get('text_human');
		$data['text_computer'] 				= $this->language->get('text_computer');
		$data['entry_sort_order'] 			= $this->language->get('entry_sort_order');		
		$data['entry_num_products'] 			= $this->language->get('entry_num_products');	
		$data['entry_list'] 					= $this->language->get('entry_list');	
		$data['entry_select'] 				= $this->language->get('entry_select');
		$data['entry_view'] 					= $this->language->get('entry_view2');	
		$data['entry_image'] 					= $this->language->get('entry_image');	
		$data['text_what_is'] 				= $this->language->get('text_what_is');		
		$data['entry_open'] 					= $this->language->get('entry_open');
		$data['entry_search'] 				= $this->language->get('entry_search');
		$data['text_yes'] 					= $this->language->get('text_yes');
		$data['text_no'] 						= $this->language->get('text_no');
		$data['text_open'] 					= $this->language->get('text_open');
		$data['text_close'] 					= $this->language->get('text_close');
		$data['text_info'] 					= $this->language->get('text_info');	
		$data['button_save_all'] 				= $this->language->get('button_save_all');	
		$data['ASC'] 							= $this->language->get('ASC');
		$data['DESC'] 						= $this->language->get('DESC');  
		$data['novaluestxt'] 					= $this->language->get('novaluestxt');  
		$data['tab_atributtes']				= $this->language->get('tab_atributtes');
		$data['tab_productinfo']				= $this->language->get('tab_productinfo');
		$data['entry_slider']				    = $this->language->get('entry_slider');
		$data['opencart'] 					= $this->language->get('opencart');
		$data['entry_unit'] 					= $this->language->get('entry_unit');
		$data['no_attributes_c'] 				= $this->language->get('no_attributes_c');
		$data['no_options_c'] 				= $this->language->get('no_options_c');
		$data['no_attributes_m'] 				= $this->language->get('no_attributes_m');
		$data['no_options_m'] 				= $this->language->get('no_options_m');
		$data['entry_attributes']				= $this->language->get('entry_attributes');
		$data['entry_options'] 				= $this->language->get('entry_options');
		$data['entry_product_info'] 			= $this->language->get('entry_product_info');
        $data['entry_remove_tabs']			= $this->language->get('entry_remove_tabs');

		$this->load->model('module/supercategorymenuadvanced'); 	
		$this->load->model('module/supermanufacturermenuadvanced'); 
		
		if ((int)$manufacturer_id==0){
			$data['success'] =sprintf($this->language->get('success_set'),  "manufacturer",$this->language->get('category_home'));
		}else{
			//$category_path = $this->model_module_supercategorymenuadvanced->getPath((int)$category_id);
			$data['success'] =sprintf($this->language->get('success_set'), "manufacturer","kjjkkkjl");
    	}
		
		if (isset($this->error['warning'])) { 
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
 		$data['manufacturers'] = array();
					
		//$category_attributes=array();			
					
		$manufacturer_values = $this->config->get('VALORESM_'.$manufacturer_id,$store_id);

		/*****************************************************************
				PRODUCT INFO MANUFACTURER
		******************************************************************/

		$results=array(
		array('productinfo_id'=>1,'name'=>"Width",'short_name'=>"w" ), 
		array('productinfo_id'=>2,'name'=>"Height",'short_name'=>"h" ),
		array('productinfo_id'=>3,'name'=>"Length",'short_name'=>"l" ),
		array('productinfo_id'=>4,'name'=>"Model",'short_name'=>"mo" ),
		array('productinfo_id'=>5,'name'=>"sku",'short_name'=>"sk" ),
		array('productinfo_id'=>6,'name'=>"UPC",'short_name'=>"up" ),
		array('productinfo_id'=>7,'name'=>"Location",'short_name'=>"lo" ),
		array('productinfo_id'=>8,'name'=>"Weight",'short_name'=>"wg" ),
		);
		
	   if(version_compare(VERSION,'1.5.4','>=')) {
		
		array_push($results,array('productinfo_id'=>9,'name'=>"EAN",'short_name'=>"e" ), array('productinfo_id'=>10,'name'=>"ISBN",'short_name'=>"i" ),	array('productinfo_id'=>11,'name'=>"MPN",'short_name'=>"p" ),	array('productinfo_id'=>12,'name'=>"Jan",'short_name'=>"j" ));
	   
	   
	   }
		
	   $productinfo_data = array();
		
		foreach ($results as $result) {
				if (is_array($manufacturer_values) && isset($manufacturer_values['productinfo'][$result['productinfo_id']]['productinfo_id'])){
						$productinfo_checked=true;
						$productinfo_seperator=isset($manufacturer_values['productinfo'][$result['productinfo_id']]['separator'])? $manufacturer_values['productinfo'][$result['productinfo_id']]['separator'] : "no";	
						$productinfo_order=isset($manufacturer_values['productinfo'][$result['productinfo_id']]['sort_order'])? $manufacturer_values['productinfo'][$result['productinfo_id']]['sort_order'] : 0;	
						$productinfo_orderval=isset($manufacturer_values['productinfo'][$result['productinfo_id']]['orderval'])? $manufacturer_values['productinfo'][$result['productinfo_id']]['orderval'] : "OHASC";	
						$productinfo_number=isset($manufacturer_values['productinfo'][$result['productinfo_id']]['number'])? $manufacturer_values['productinfo'][$result['productinfo_id']]['number'] : 8;	
						$productinfo_view=isset($manufacturer_values['productinfo'][$result['productinfo_id']]['view'])? $manufacturer_values['productinfo'][$result['productinfo_id']]['view'] :"slider" ;	
						$productinfo_info=isset($manufacturer_values['productinfo'][$result['productinfo_id']]['info'])? $manufacturer_values['productinfo'][$result['productinfo_id']]['info'] :"no" ;
				 		$productinfo_initval=isset($manufacturer_values['productinfo'][$result['productinfo_id']]['initval'])? $manufacturer_values['productinfo'][$result['productinfo_id']]['initval'] :"opened" ;
						$productinfo_searchinput=isset($manufacturer_values['productinfo'][$result['productinfo_id']]['searchinput'])? $manufacturer_values['productinfo'][$result['productinfo_id']]['searchinput'] :"no" ;
						$productinfo_unit=isset($manufacturer_values['productinfo'][$result['productinfo_id']]['unit'])? $manufacturer_values['productinfo'][$result['productinfo_id']]['unit'] :"" ;
						$productinfo_text_info= array(); 
						foreach ($languages as $language){
							$productinfo_text_info[$language['language_id']]=isset($manufacturer_values['productinfo'][$result['productinfo_id']]['text_info'][$language['language_id']])? $manufacturer_values['productinfo'][$result['productinfo_id']]['text_info'][$language['language_id']] :"" ;
						}
								
				}else{
						$productinfo_checked=false;
						$productinfo_seperator="no";
						$productinfo_order=0;
						$productinfo_orderval="OHASC"; 
						$productinfo_number="8";
						$productinfo_view="list";
						$productinfo_initval="opened";
						$productinfo_searchinput="no";
						$productinfo_info="no" ;
						$productinfo_unit="" ;
						$productinfo_text_info=0;
						
				}
				
				//Get productinfo for examples
				$productinfo_values=array(); 
				$productinfo_values = $this->model_module_supermanufacturermenuadvanced->getProductInfoValues($result['name'],$manufacturer_id,$store_id);	

				  $productinfo_data[] = array(
						'productinfo_id'  	=> $result['productinfo_id'],
						'name'          	=> $result['name'],
						'short_name'		=> $result['short_name'],
						'checked'			=> $productinfo_checked,
						'separator' 		=> $productinfo_seperator,
						'values'			=> $productinfo_values,
						'sort_order'  		=> $productinfo_order,
						'number'			=> $productinfo_number,
						'orderval'			=> $productinfo_orderval,
						'view'				=> $productinfo_view,
						'what'				=> 'productinfo',
						'initval'			=> $productinfo_initval,
						'searchinput'		=> $productinfo_searchinput,
						'info'				=> $productinfo_info,
						'unit'				=> $productinfo_unit,
						'text_info'			=> $productinfo_text_info
						
					);	
		
			
				//reorder fields by order

				
		
			}
		
		
		/*****************************************************************
				OPTIONS MANUFACTURER
		******************************************************************/

		//get options from the manufacturer
		$results = $this->model_module_supermanufacturermenuadvanced->getManufacturerOptions($manufacturer_id,$store_id);	
		
		$options_data= array();
		
			foreach ($results as $result) {
				if (is_array($manufacturer_values) && isset($manufacturer_values['options'][$result['option_id']]['option_id'])){
						$option_checked=true;
						$option_seperator=isset($manufacturer_values['options'][$result['option_id']]['separator'])? $manufacturer_values['options'][$result['option_id']]['separator'] : "no";					
						$option_order=isset($manufacturer_values['options'][$result['option_id']]['sort_order'])? $manufacturer_values['options'][$result['option_id']]['sort_order'] : 0;	
						$option_orderval=isset($manufacturer_values['options'][$result['option_id']]['orderval'])? $manufacturer_values['options'][$result['option_id']]['orderval'] : "OHASC";	
						$option_number=isset($manufacturer_values['options'][$result['option_id']]['number'])? $manufacturer_values['options'][$result['option_id']]['number'] : 8;	
						$option_view=isset($manufacturer_values['options'][$result['option_id']]['view'])? $manufacturer_values['options'][$result['option_id']]['view'] :"list" ;	
						$option_initval=isset($manufacturer_values['options'][$result['option_id']]['initval'])? $manufacturer_values['options'][$result['option_id']]['initval'] :"opened" ;
						$option_searchinput=isset($manufacturer_values['options'][$result['option_id']]['searchinput'])? $manufacturer_values['options'][$result['option_id']]['searchinput'] :"no" ;
						$option_info=isset($manufacturer_values['options'][$result['option_id']]['info'])? $manufacturer_values['options'][$result['option_id']]['info'] :"no" ;
						$option_unit=isset($manufacturer_values['options'][$result['option_id']]['unit'])? $manufacturer_values['options'][$result['option_id']]['unit'] :"" ;			
						$option_text_info=array();
						foreach ($languages as $language){
						$option_text_info[$language['language_id']]=isset($manufacturer_values['options'][$result['option_id']]['text_info'][$language['language_id']])? $manufacturer_values['options'][$result['option_id']]['text_info'][$language['language_id']] :"" ;
						}
				
				}else{
						$option_checked=false;
						$option_seperator="no";
						$option_order=0;
						$option_orderval="OHASC";
						$option_number="8";
						$option_view="list";
						$option_initval="opened";
						$option_searchinput="no";
						$option_info="no" ;
						$option_unit="" ;
						$option_text_info=0;
				}
				
				//Get options values for examples
				$options_values=array();
				$options_values = $this->model_module_supermanufacturermenuadvanced->getOptionsValuesManufacturers($result['option_id'],$manufacturer_id,$store_id);		
	
		  		$options_data[] = array(
						'option_id'  		=> $result['option_id'],
						'name'          	=> $result['name'],
						'short_name'		=> "o",
						'checked'			=> $option_checked,
						'separator' 		=> $option_seperator,
						'values'			=> $options_values,
						'sort_order'  		=> $option_order,
						'number'			=> $option_number,
						'orderval'			=> $option_orderval,
						'view'				=> $option_view,
						'initval'			=> $option_initval,
						'searchinput'		=> $option_searchinput,
						'info'				=> $option_info,
						'what'				=> 'options',
						'unit'				=> $option_unit,
						'text_info'			=> $option_text_info
                      );	
		
									
			}

		/*****************************************************************
				ATTRIBUTES MANUFACTURER
		******************************************************************/
		
		$results = $this->model_module_supermanufacturermenuadvanced->getManufacturerAttributes($manufacturer_id,$store_id);		
       
	    $attribute_data = array();

			foreach ($results as $result) {
				if (is_array($manufacturer_values) && isset($manufacturer_values['attributes'][$result['attribute_id']]['attribute_id'])){
						$attribute_checked=true;
						$attribute_seperator=isset($manufacturer_values['attributes'][$result['attribute_id']]['separator'])? $manufacturer_values['attributes'][$result['attribute_id']]['separator'] : "no";	
						$attribute_order=isset($manufacturer_values['attributes'][$result['attribute_id']]['sort_order'])? $manufacturer_values['attributes'][$result['attribute_id']]['sort_order'] : 0;	
						$attribute_orderval=isset($manufacturer_values['attributes'][$result['attribute_id']]['orderval'])? $manufacturer_values['attributes'][$result['attribute_id']]['orderval'] : "OHASC";	
						$attribute_number=isset($manufacturer_values['attributes'][$result['attribute_id']]['number'])? $manufacturer_values['attributes'][$result['attribute_id']]['number'] : 8;	
						$attribute_view=isset($manufacturer_values['attributes'][$result['attribute_id']]['view'])? $manufacturer_values['attributes'][$result['attribute_id']]['view'] :"list" ;	
						$attribute_initval=isset($manufacturer_values['attributes'][$result['attribute_id']]['initval'])? $manufacturer_values['attributes'][$result['attribute_id']]['initval'] :"opened" ;
						$attribute_searchinput=isset($manufacturer_values['attributes'][$result['attribute_id']]['searchinput'])? $manufacturer_values['attributes'][$result['attribute_id']]['searchinput'] :"no" ;
						$attribute_info=isset($manufacturer_values['attributes'][$result['attribute_id']]['info'])? $manufacturer_values['attributes'][$result['attribute_id']]['info'] :"no" ;
						$attribute_unit=isset($manufacturer_values['attributes'][$result['attribute_id']]['unit'])? $manufacturer_values['attributes'][$result['attribute_id']]['unit'] :"";
						
						$attribute_text_info=array();
						foreach ($languages as $language){
						$attribute_text_info[$language['language_id']]=isset($manufacturer_values['attributes'][$result['attribute_id']]['text_info'][$language['language_id']])? $manufacturer_values['attributes'][$result['attribute_id']]['text_info'][$language['language_id']] :"" ;
						}
						
						
						
				
				}else{
						$attribute_checked=false;
						$attribute_seperator="no";
						$attribute_order=0;
						$attribute_orderval="OHASC";
						$attribute_number="8";
						$attribute_view="list";
						$attribute_initval="opened";
						$attribute_searchinput="no";
						$attribute_info="no" ; 
						$attribute_unit=""; 
						$attribute_text_info=0;
				}
				
				//Get attributes for examples
				$attribute_values=array();
				$attribute_values = $this->model_module_supermanufacturermenuadvanced->getAttributeValuesManufacturers($result['attribute_id'],$manufacturer_id,$store_id);	
	
		           $attribute_data[] = array(
						'attribute_id'  	=> $result['attribute_id'],
						'name'          	=> $result['name'],
						'short_name'		=> "a",
						'checked'			=> $attribute_checked,
						'separator' 		=> $attribute_seperator,
						'values'			=> $attribute_values,
						'sort_order'  		=> $attribute_order,
						'number'			=> $attribute_number,
						'orderval'			=> $attribute_orderval,
						'view'				=> $attribute_view,
						'initval'			=> $attribute_initval,
						'searchinput'		=> $attribute_searchinput,
						'info'				=> $attribute_info,
						'what'				=> 'attributes',
						'unit'				=> $attribute_unit,
						'text_info'			=> $attribute_text_info
					
					);	
		
				//reorder attributes by order
				
		
			}
			
			//merge attributes and options and product info.
			
			$all_values=array();
			
			$all_values=array_merge($options_data,$attribute_data,$productinfo_data);
			
			$sort_order=array();
			foreach ($all_values as $key => $value) {
               $sort_order[] = $value['sort_order'];
		  	}
              
		  	array_multisort($sort_order, SORT_ASC,$all_values);
			
			$sort_order=array();
			foreach ($attribute_data as $key => $value) {
               $sort_order[] = $value['sort_order'];
		  	}
              
		  	array_multisort($sort_order, SORT_ASC,$attribute_data);
			
			$sort_order=array();
			foreach ($options_data as $key => $value) {
               $sort_order[] = $value['sort_order'];
		  	}
              
		  	array_multisort($sort_order, SORT_ASC,$options_data);
			
			$sort_order=array();
			foreach ($productinfo_data as $key => $value) {
               $sort_order[] = $value['sort_order'];
		  	}
              
		  	array_multisort($sort_order, SORT_ASC,$productinfo_data);
			
			
			$data['manufacturers'] = array(
				'manufacturer_id' 	=> $manufacturer_id,
				'all_values'  		=> $all_values,
				'attributes'		=> $attribute_data,
				'options'			=> $options_data,
				'productinfo'		=> $productinfo_data,
				'store'				=> $store_id

				);
	
	
	
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		
			
   $data['token'] = $this->session->data['token'];	
		 
		
		//$this->template = 'module/supercategorymenu/supercategorymenuadvanced_values.tpl';
		//$this->response->setOutput($this->render());
		
		$this->response->setOutput($this->load->view('module/supercategorymenu/supercategorymenuadvanced_values_M.tpl', $data));
		
		
		
		
		
		
		
}


public function SetAllValuesManufacturer() {   
		
		$this->load->model('module/supercategorymenuadvanced');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {	
		$this->model_module_supercategorymenuadvanced->editOneSetting('supercategorymenuadvanced', $this->request->post['dnd'],$this->request->post,$this->request->post['store_id']);
		
	    $this->model_module_supercategorymenuadvanced->DeleteCacheValues($this->request->post['manufacturer_id'],$this->request->post['store_id']);
		
		}
	}

private function get_accountDetails($salt) {
		$detailurl = $this->GETACCOUNTDETAILS_URL;
		return json_decode($this->do_post_request($detailurl, array("salt" => $salt,"server"=>$_SERVER['SERVER_NAME'], "extension"=>$this->EXTENSION)));
	}

private function get_currentVersion() {
		$detailurl = $this->GETVERSION_URL;
		return json_decode($this->do_post_request($detailurl, array("version_installed" => $this->VERSIONINSTALLED,"extension"=>$this->EXTENSION)));
	}


	public function DeleteCacheSettings() {   
		$this->load->model('module/supercategorymenuadvanced');
		//Init var
		$category_id="Error";
		$are_you_sure=0;
		$data['error_warning']=0;
		$data['successdel']=0;
		$data['button_delete'] 			= $this->language->get('button_delete');
		$data['button_cancel'] 			= $this->language->get('button_cancel');
			
		
		if (isset($this->request->get['Are_you_sure'])) {
			$are_you_sure = $this->request->get['Are_you_sure'];
		} else {
			$are_you_sure = 0;
		}

			$this->load->language('module/supercategorymenuadvanced');
			
			if ($are_you_sure){
				
				//Delete cache	
				foreach ($this->request->get['selected_del'] as $cache_id) {
					$this->model_module_supercategorymenuadvanced->DeleteCacheRecord($cache_id);
				}
			
			$data['successdel']=$this->language->get('success_del_cache');
			$data['text_error_no_cache']= false;
			$data['cache_records']=$this->model_module_supercategorymenuadvanced->GetCacheRecords('admin','cs.name');
			
			
				if (empty($data['cache_records'])){
					$data['text_error_no_cache'] 		= $this->language->get('text_error_no_cache');
				}
			
			}
			
			//$this->template = 'module/supercategorymenu/supercategorymenuadvanced_delete_cache_admin.tpl';
			//$this->response->setOutput($this->render());
			$this->response->setOutput($this->load->view('module/supercategorymenu/supercategorymenuadvanced_delete_cache_admin.tpl', $data));
		
}

	
 public function DeleteCache() {   
		$this->load->model('module/supercategorymenuadvanced');
		//Init var
		$category_id="Error";
		$are_you_sure=0;
		$data['error_warning'] =0;
		$data['success']		 =0;
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['successdel']=0;
		if (isset($this->request->get['category_id'])) {
			$category_id = $this->request->get['category_id'];
		} else {
			$category_id = "Error";
		}
		
		if (isset($this->request->get['store_id'])) {
			$store_id = $this->request->get['store_id'];
		} else {
			$store_id = "Error";
		}
		
		if (isset($this->request->get['Are_you_sure'])) {
			$are_you_sure = $this->request->get['Are_you_sure'];
		} else {
			$are_you_sure = 0;
		}
	    if ($category_id=="Error" || $store_id =="Error"){
			$data['error_warning']=$this->language->get('text_error_category');
		}else{
			$this->load->language('module/supercategorymenuadvanced');
			
			if ($are_you_sure){
				
			//Delete cache	
			foreach ($this->request->post['selected'] as $cache_id) {
				$this->model_module_supercategorymenuadvanced->DeleteCacheRecord($cache_id,$store_id);
	  		}
			
			$data['successdel']=$this->language->get('success_del_cache');
			$data['text_error_no_cache']= false;
			$data['cache_records']=$this->model_module_supercategorymenuadvanced->GetCacheRecords($category_id,'cs.cached',$store_id);
			$data['entry_cache_del_list']    	= sprintf($this->language->get('entry_cache_del_list'),$this->model_module_supercategorymenuadvanced->getPath($category_id));
			$data['text_cache_del_remenber'] 	= $this->language->get('text_cache_del_remenber');	
		
			if (empty($data['cache_records'])){
				
				$data['text_error_no_cache'] 		= $this->language->get('text_error_no_cache');
		    }
			 
		  	
		    }else{//show cache  
				
				
				$data['text_cache_del_remenber'] 	= $this->language->get('text_cache_del_remenber');
				$data['text_error_no_cache'] 		= false;
				$data['entry_cache_del_list']    	= sprintf($this->language->get('entry_cache_del_list'),$this->model_module_supercategorymenuadvanced->getPath($category_id));
	
				
				$data['cache_records']=$this->model_module_supercategorymenuadvanced->GetCacheRecords($category_id,'cs.cached',$store_id);
							
				if (empty($data['cache_records'])){
					$data['text_error_no_cache']=$this->language->get('text_error_no_cache');
				}
				
			}
			
		$data['action_del_cache'] = $this->url->link('module/supercategorymenuadvanced/DeleteCache', 'token=' . $this->session->data['token'] .'&category_id='.$category_id.'&Are_you_sure=1&store_id='.$store_id, 'SSL');
		
		
		}
		
	   $this->response->setOutput($this->load->view('module/supercategorymenu/supercategorymenuadvanced_delete_cache.tpl', $data));
			
 }
 
 public function DeleteCacheManufacturer() {   
		$this->load->model('module/supercategorymenuadvanced');
		//Init var
		$manufacturer_id="Error";
		$are_you_sure=0;
		$data['error_warning'] =0;
		$data['success']		 =0;
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['successdel']=0;
		if (isset($this->request->get['manufacturer_id'])) {
			$manufacturer_id = $this->request->get['manufacturer_id'];
		} else {
			$manufacturer_id = "Error";
		}
		
		if (isset($this->request->get['store_id'])) {
			$store_id = $this->request->get['store_id'];
		} else {
			$store_id = "Error";
		}
		
		if (isset($this->request->get['Are_you_sure'])) {
			$are_you_sure = $this->request->get['Are_you_sure'];
		} else {
			$are_you_sure = 0;
		}
	    if ($manufacturer_id=="Error" || $store_id =="Error"){
			$data['error_warning']=$this->language->get('text_error_category');
		}else{
			$this->load->language('module/supercategorymenuadvanced');
			
			if ($are_you_sure){
				
			//Delete cache	
			foreach ($this->request->post['selected'] as $cache_id) {
				$this->model_module_supercategorymenuadvanced->DeleteCacheRecord($cache_id,$store_id);
	  		}
			
			$data['successdel']=$this->language->get('success_del_cache'); 
			$data['text_error_no_cache']= false;
			$data['cache_records']=$this->model_module_supercategorymenuadvanced->GetCacheRecordsM($manufacturer_id,'cs.cached',$store_id);
			$data['entry_cache_del_list']    	= sprintf($this->language->get('entry_cache_del_list'),$this->model_module_supercategorymenuadvanced->getManufacturerName($manufacturer_id));
			$data['text_cache_del_remenber'] 	= $this->language->get('text_cache_del_remenber');	
		
			if (empty($data['cache_records'])){
				
				$data['text_error_no_cache'] 		= $this->language->get('text_error_no_cache');
		    }
			 
		  	
		    }else{//show cache  
				
				
				$data['text_cache_del_remenber'] 	= $this->language->get('text_cache_del_remenber');
				$data['text_error_no_cache'] 		= false;
				$data['entry_cache_del_list']    	= sprintf($this->language->get('entry_cache_del_list'),$this->model_module_supercategorymenuadvanced->getManufacturerName($manufacturer_id));
	
				
				$data['cache_records']=$this->model_module_supercategorymenuadvanced->GetCacheRecordsM($manufacturer_id,'cs.cached',$store_id);
							
				if (empty($data['cache_records'])){
					$data['text_error_no_cache']=$this->language->get('text_error_no_cache');
				}
				
			} 
			
		$data['action_del_cache'] = $this->url->link('module/supercategorymenuadvanced/DeleteCacheManufacturer', 'token=' . $this->session->data['token'] .'&manufacturer_id='.$manufacturer_id.'&Are_you_sure=1&store_id='.$store_id, 'SSL');
		
		
		}
		$this->response->setOutput($this->load->view('module/supercategorymenu/supercategorymenuadvanced_delete_cache.tpl', $data));
		
		
 }

}
?>