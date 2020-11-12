<?php
class ControllerModulesupermenu extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->language->load('module/supermenu');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		$this->load->model('catalog/category');
		$this->load->model('catalog/information');
		$this->load->model('localisation/language');
		$this->load->model('tool/image');
        $this->load->model('setting/store');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('supermenu', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->cache->delete('supermenu');
						
			$this->response->redirect($this->url->link('module/supermenu', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['stores'] = $this->model_setting_store->getStores();
		$data['text_stores'] = $this->language->get('text_stores');
		$data['text_fbrands'] = $this->language->get('text_fbrands');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_content_top'] = $this->language->get('text_content_top');
		$data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$data['text_column_left'] = $this->language->get('text_column_left');
		$data['text_column_right'] = $this->language->get('text_column_right');
		$data['text_image'] = $this->language->get('text_image');
		$data['text_expando'] = $this->language->get('text_expando');
		$data['text_sorder'] = $this->language->get('text_sorder');
		$data['text_tlcolor'] = $this->language->get('text_tlcolor');
		$data['text_tlstyle'] = $this->language->get('text_tlstyle');
		$data['text_justadd'] = $this->language->get('text_justadd');
		$data['text_alldrop'] = $this->language->get('text_alldrop');
		$data['text_overdrop'] = $this->language->get('text_overdrop');
		$data['text_supermenuisresponsive'] = $this->language->get('text_supermenuisresponsive');
		$data['text_or'] = $this->language->get('text_or');
		$data['text_no'] = $this->language->get('text_no');
		$data['tab_items'] = $this->language->get('tab_items');
		$data['tab_settings'] = $this->language->get('tab_settings');
		$data['tab_html'] = $this->language->get('tab_html');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_browse'] = $this->language->get('text_browse');
		$data['text_clear'] = $this->language->get('text_clear');
		$data['text_image_manager'] = $this->language->get('text_image_manager');
		$data['text_unselect_all'] = $this->language->get('text_unselect_all');
		$data['text_select_all'] = $this->language->get('text_select_all');
		$data['entry_layout'] = $this->language->get('entry_layout');
		$data['entry_add'] = $this->language->get('entry_add');
		$data['text_slist'] = $this->language->get('text_slist');
		$data['text_sgrid'] = $this->language->get('text_sgrid');
		$data['text_sview'] = $this->language->get('text_sview');
		$data['text_dwidth'] = $this->language->get('text_dwidth');
		$data['text_iwidth'] = $this->language->get('text_iwidth');
		$data['text_chtml'] = $this->language->get('text_chtml');
		$data['text_cchtml'] = $this->language->get('text_cchtml');
		$data['text_whatproducts'] = $this->language->get('text_whatproducts');
		$data['text_productlatest'] = $this->language->get('text_productlatest');
		$data['text_productspecial'] = $this->language->get('text_productspecial');
		$data['text_productfeatured'] = $this->language->get('text_productfeatured');
		$data['text_productbestseller'] = $this->language->get('text_productbestseller');
		$data['text_productlimit'] = $this->language->get('text_productlimit');
		$data['entry_type'] = $this->language->get('entry_type');
		$data['entry_category'] = $this->language->get('entry_category');
		$data['entry_custom'] = $this->language->get('entry_custom');
		$data['entry_information'] = $this->language->get('entry_information');
		$data['entry_advanced'] = $this->language->get('entry_advanced');
		$data['custom_name'] = $this->language->get('custom_name');
		$data['custom_url'] = $this->language->get('custom_url');
		$data['type_cat'] = $this->language->get('type_cat');
		$data['type_mand'] = $this->language->get('type_mand');
		$data['type_infol'] = $this->language->get('type_infol');
		$data['type_products'] = $this->language->get('type_products');
		$data['type_catprods'] = $this->language->get('type_catprods');
		$data['type_infod'] = $this->language->get('type_infod');
		$data['entry_iset'] = $this->language->get('entry_iset');
		$data['type_custom'] = $this->language->get('type_custom');
		$data['type_more'] = $this->language->get('type_more');
		$data['type_more2'] = $this->language->get('type_more2');
		$data['type_login'] = $this->language->get('type_login');
		$data['entry_position'] = $this->language->get('entry_position');
		$data['entry_count'] = $this->language->get('entry_count');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_add_module'] = $this->language->get('button_add_module');
		$data['button_add_item'] = $this->language->get('button_add_item');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['more_name'] = $this->language->get('more_name');
		$data['more2_name'] = $this->language->get('more2_name');
		$data['more_status'] = $this->language->get('more_status');
		$data['entry_image_size'] = $this->language->get('entry_image_size');
		$data['entry_show_description'] = $this->language->get('entry_show_description');
		$data['text_general'] = $this->language->get('text_general');
		$data['text_more_dropdown'] = $this->language->get('text_more_dropdown');
		$data['text_more2_dropdown'] = $this->language->get('text_more2_dropdown');
		$data['text_languagerelated'] = $this->language->get('text_languagerelated');
		$data['text_customization'] = $this->language->get('text_customization');
		$data['text_settings_isresponsive'] = $this->language->get('text_settings_isresponsive');
		$data['text_settings_dropdowntitle'] = $this->language->get('text_settings_dropdowntitle');
		$data['text_settings_topitemlink'] = $this->language->get('text_settings_topitemlink');
		$data['text_settings_flyoutwidth'] = $this->language->get('text_settings_flyoutwidth');
		$data['text_settings_bspacewidth'] = $this->language->get('text_settings_bspacewidth');
		$data['text_settings_mobilemenuname'] = $this->language->get('text_settings_mobilemenuname');
		$data['text_settings_infodname'] = $this->language->get('text_settings_infodname');
		$data['text_settings_brandsdname'] = $this->language->get('text_settings_brandsdname');
		$data['text_settings_latestpname'] = $this->language->get('text_settings_latestpname');
		$data['text_settings_specialpname'] = $this->language->get('text_settings_specialpname');
		$data['text_settings_featuredpname'] = $this->language->get('text_settings_featuredpname');
		$data['text_settings_bestpname'] = $this->language->get('text_settings_bestpname');
		$data['text_subcatdisplay'] = $this->language->get('text_subcatdisplay');
		$data['text_subcatdisplay_all'] = $this->language->get('text_subcatdisplay_all');
		$data['text_subcatdisplay_level1'] = $this->language->get('text_subcatdisplay_level1');
		$data['text_subcatdisplay_none'] = $this->language->get('text_subcatdisplay_none');
		$data['text_3dlevellimit'] = $this->language->get('text_3dlevellimit');
		$data['text_settings_viewallname'] = $this->language->get('text_settings_viewallname');
		$data['text_settings_viewmorename'] = $this->language->get('text_settings_viewmorename');
		$data['text_settings_dropeffect'] = $this->language->get('text_settings_dropeffect');
		$data['text_settings_hoverintent'] = $this->language->get('text_settings_hoverintent');
		$data['text_settings_tophomelink'] = $this->language->get('text_settings_tophomelink');
		$data['text_settings_menuskin'] = $this->language->get('text_settings_menuskin');
		$data['text_dflist'] = $this->language->get('text_dflist');
		$data['text_dfgrid'] = $this->language->get('text_dfgrid');
		$data['text_settings_supercache'] = $this->language->get('text_settings_supercache');
		$data['text_settings_fproduct'] = $this->language->get('text_settings_fproduct');
		
		
		$data['token'] = $this->session->data['token'];
		
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
			'href'      => $this->url->link('module/supermenu', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$data['action'] = $this->url->link('module/supermenu', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['no_image'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		$data['modules'] = array();
		$data['items'] = array();
		$data['categories'] = array();
		$data['informations'] = array();
		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		$filter_data = array(
			'sort'  => 'name',
			'order' => 'ASC'
		);
		$categ = $this->model_catalog_category->getCategories($filter_data);

		foreach ($categ as $cate) {
							
			$data['categories'][] = array(
				'category_id' => $cate['category_id'],
				'name'        => $cate['name']
			);
		}
		
		$infos = $this->model_catalog_information->getInformations();
		
		foreach ($infos as $info) {
							
			$data['informations'][] = array(
				'information_id' => $info['information_id'],
				'name'           => $info['title']
			);
		}
			
		if (isset($this->request->post['supermenu_item'])) {
			$data['items'] = $this->request->post['supermenu_item'];
		} elseif ($this->config->get('supermenu_item')) { 
			$data['items'] = $this->config->get('supermenu_item');
		}
		if (isset($this->request->post['supermenu_settings'])) {
			$data['settings'] = $this->request->post['supermenu_settings'];
		} elseif ($this->config->get('supermenu_settings')) { 
			$data['settings'] = $this->config->get('supermenu_settings');
		}
		if (isset($this->request->post['supermenu_settings_status'])) {
			$data['supermenu_settings_status'] = $this->request->post['supermenu_settings_status'];
		} elseif ($this->config->get('supermenu_settings_status')) { 
			$data['supermenu_settings_status'] = $this->config->get('supermenu_settings_status');
		} else {
		    $data['supermenu_settings_status'] = '';
		}
		if (isset($this->request->post['supermenu_supermenuisresponsive'])) {
			$data['supermenu_supermenuisresponsive'] = $this->request->post['supermenu_supermenuisresponsive'];
		} elseif ($this->config->get('supermenu_supermenuisresponsive')) { 
			$data['supermenu_supermenuisresponsive'] = $this->config->get('supermenu_supermenuisresponsive');
		} else {
		    $data['supermenu_supermenuisresponsive'] = 0;
		}
		if (isset($this->request->post['supermenu_more'])) {
			$data['supermenu_more'] = $this->request->post['supermenu_more'];
		} elseif ($this->config->get('supermenu_more')) {
			$data['supermenu_more'] = $this->config->get('supermenu_more');
		} else {
			$data['supermenu_more'] = array();
		}
		if (isset($this->request->post['supermenu_more2'])) {
			$data['supermenu_more2'] = $this->request->post['supermenu_more2'];
		} elseif ($this->config->get('supermenu_more2')) {
			$data['supermenu_more2'] = $this->config->get('supermenu_more2');
		} else {
			$data['supermenu_more2'] = array();
		}
		
		if (isset($this->request->post['supermenu_more_view'])) {
			$data['supermenu_more_view'] = $this->request->post['supermenu_more_view'];
		} elseif ($this->config->get('supermenu_more_view')) {
			$data['supermenu_more_view'] = $this->config->get('supermenu_more_view');
		} else {
			$data['supermenu_more_view'] = '';
		}
		
		if (isset($this->request->post['supermenu_more_title'])) {
			$data['supermenu_more_title'] = $this->request->post['supermenu_more_title'];
		} else {
			$data['supermenu_more_title'] = $this->config->get('supermenu_more_title');
		}
		if (isset($this->request->post['supermenu_more2_title'])) {
			$data['supermenu_more2_title'] = $this->request->post['supermenu_more2_title'];
		} else {
			$data['supermenu_more2_title'] = $this->config->get('supermenu_more2_title');
		}
		
		if (isset($this->request->post['supermenu_more_status'])) {
			$data['supermenu_more_status'] = $this->request->post['supermenu_more_status'];
		} elseif ($this->config->get('supermenu_more_status')) {
			$data['supermenu_more_status'] = $this->config->get('supermenu_more_status');
		} else {
		    $data['supermenu_more_status'] = '';
		}
		if (isset($this->request->post['supermenu_image_width'])) {
			$data['supermenu_image_width'] = $this->request->post['supermenu_image_width'];
		} elseif ($this->config->get('supermenu_image_width')) {
			$data['supermenu_image_width'] = $this->config->get('supermenu_image_width');
		} else {
			$data['supermenu_image_width'] = 120;
		}
		if (isset($this->request->post['supermenu_image_height'])) {
			$data['supermenu_image_height'] = $this->request->post['supermenu_image_height'];
		} elseif ($this->config->get('supermenu_image_height')) {
			$data['supermenu_image_height'] = $this->config->get('supermenu_image_height');
		} else {
			$data['supermenu_image_height'] = 120;
		}
		if (isset($this->request->post['supermenu_show_description'])) {
			$data['supermenu_show_description'] = $this->request->post['supermenu_show_description'];
		} elseif ($this->config->get('supermenu_show_description')) {
			$data['supermenu_show_description'] = $this->config->get('supermenu_show_description');
		} else {
			$data['supermenu_show_description'] = 'no';
		}
		if (isset($this->request->post['supermenu_dropdowntitle'])) {
			$data['supermenu_dropdowntitle'] = $this->request->post['supermenu_dropdowntitle'];
		} elseif ($this->config->get('supermenu_dropdowntitle')) { 
			$data['supermenu_dropdowntitle'] = $this->config->get('supermenu_dropdowntitle');
		} else {
		    $data['supermenu_dropdowntitle'] = 0;
		}
		if (isset($this->request->post['supermenu_topitemlink'])) {
			$data['supermenu_topitemlink'] = $this->request->post['supermenu_topitemlink'];
		} elseif ($this->config->get('supermenu_topitemlink')) { 
			$data['supermenu_topitemlink'] = $this->config->get('supermenu_topitemlink');
		} else {
		    $data['supermenu_topitemlink'] = 'bottom';
		}
		if (isset($this->request->post['supermenu_skin'])) {
			$data['supermenu_skin'] = $this->request->post['supermenu_skin'];
		} elseif ($this->config->get('supermenu_skin')) { 
			$data['supermenu_skin'] = $this->config->get('supermenu_skin');
		} else {
		    $data['supermenu_skin'] = 'default';
		}
		if (isset($this->request->post['supermenu_flyout_width'])) {
			$data['supermenu_flyout_width'] = $this->request->post['supermenu_flyout_width'];
		} elseif ($this->config->get('supermenu_flyout_width')) {
			$data['supermenu_flyout_width'] = $this->config->get('supermenu_flyout_width');
		} else {
			$data['supermenu_flyout_width'] = '';
		}
		if (isset($this->request->post['supermenu_mobilemenuname'])) {
			$data['supermenu_mobilemenuname'] = $this->request->post['supermenu_mobilemenuname'];
		} elseif ($this->config->get('supermenu_mobilemenuname')) {
			$data['supermenu_mobilemenuname'] = $this->config->get('supermenu_mobilemenuname');
		} else {
		    $data['supermenu_mobilemenuname'] = array();
		}
		if (isset($this->request->post['supermenu_infodname'])) {
			$data['supermenu_infodname'] = $this->request->post['supermenu_infodname'];
		} elseif ($this->config->get('supermenu_infodname')) {
			$data['supermenu_infodname'] = $this->config->get('supermenu_infodname');
		} else {
		    $data['supermenu_infodname'] = array();
		}
		if (isset($this->request->post['supermenu_brandsdname'])) {
			$data['supermenu_brandsdname'] = $this->request->post['supermenu_brandsdname'];
		} elseif ($this->config->get('supermenu_brandsdname')) {
			$data['supermenu_brandsdname'] = $this->config->get('supermenu_brandsdname');
		} else {
		    $data['supermenu_brandsdname'] = array();
		}
		if (isset($this->request->post['supermenu_latestpname'])) {
			$data['supermenu_latestpname'] = $this->request->post['supermenu_latestpname'];
		} elseif ($this->config->get('supermenu_latestpname')) {
			$data['supermenu_latestpname'] = $this->config->get('supermenu_latestpname');
		} else {
		    $data['supermenu_latestpname'] = array();
		}
		if (isset($this->request->post['supermenu_specialpname'])) {
			$data['supermenu_specialpname'] = $this->request->post['supermenu_specialpname'];
		} elseif ($this->config->get('supermenu_specialpname')) {
			$data['supermenu_specialpname'] = $this->config->get('supermenu_specialpname');
		} else {
		    $data['supermenu_specialpname'] = array();
		}
		if (isset($this->request->post['supermenu_featuredpname'])) {
			$data['supermenu_featuredpname'] = $this->request->post['supermenu_featuredpname'];
		} elseif ($this->config->get('supermenu_featuredpname')) {
			$data['supermenu_featuredpname'] = $this->config->get('supermenu_featuredpname');
		} else {
		    $data['supermenu_featuredpname'] = array();
		}
		if (isset($this->request->post['supermenu_bestpname'])) {
			$data['supermenu_bestpname'] = $this->request->post['supermenu_bestpname'];
		} elseif ($this->config->get('supermenu_bestpname')) {
			$data['supermenu_bestpname'] = $this->config->get('supermenu_bestpname');
		} else {
		    $data['supermenu_bestpname'] = array();
		}
		if (isset($this->request->post['supermenu_3dlevellimit'])) {
			$data['supermenu_3dlevellimit'] = $this->request->post['supermenu_3dlevellimit'];
		} elseif ($this->config->get('supermenu_3dlevellimit')) {
			$data['supermenu_3dlevellimit'] = $this->config->get('supermenu_3dlevellimit');
		} else {
			$data['supermenu_3dlevellimit'] = '';
		}
		if (isset($this->request->post['supermenu_viewallname'])) {
			$data['supermenu_viewallname'] = $this->request->post['supermenu_viewallname'];
		} elseif ($this->config->get('supermenu_viewallname')) {
			$data['supermenu_viewallname'] = $this->config->get('supermenu_viewallname');
		} else {
		    $data['supermenu_viewallname'] = array();
		}
		if (isset($this->request->post['supermenu_viewmorename'])) {
			$data['supermenu_viewmorename'] = $this->request->post['supermenu_viewmorename'];
		} elseif ($this->config->get('supermenu_viewmorename')) {
			$data['supermenu_viewmorename'] = $this->config->get('supermenu_viewmorename');
		} else {
		    $data['supermenu_viewmorename'] = array();
		}
		if (isset($this->request->post['supermenu_dropdowneffect'])) {
			$data['supermenu_dropdowneffect'] = $this->request->post['supermenu_dropdowneffect'];
		} elseif ($this->config->get('supermenu_dropdowneffect')) {
			$data['supermenu_dropdowneffect'] = $this->config->get('supermenu_dropdowneffect');
		} else {
		    $data['supermenu_dropdowneffect'] = 'drop';
		}
		if (isset($this->request->post['supermenu_usehoverintent'])) {
			$data['supermenu_usehoverintent'] = $this->request->post['supermenu_usehoverintent'];
		} elseif ($this->config->get('supermenu_usehoverintent')) {
			$data['supermenu_usehoverintent'] = $this->config->get('supermenu_usehoverintent');
		} else {
			$data['supermenu_usehoverintent'] = '';
		}
		if (isset($this->request->post['supermenu_tophomelink'])) {
			$data['supermenu_tophomelink'] = $this->request->post['supermenu_tophomelink'];
		} elseif ($this->config->get('supermenu_tophomelink')) { 
			$data['supermenu_tophomelink'] = $this->config->get('supermenu_tophomelink');
		} else {
		    $data['supermenu_tophomelink'] = 'none';
		}
		if (isset($this->request->post['supermenu_htmlarea1'])) {
			$data['supermenu_htmlarea1'] = $this->request->post['supermenu_htmlarea1'];
		} elseif ($this->config->get('supermenu_htmlarea1')) {
			$data['supermenu_htmlarea1'] = $this->config->get('supermenu_htmlarea1');
		} else {
		    $data['supermenu_htmlarea1'] = array();
		}
		if (isset($this->request->post['supermenu_htmlarea2'])) {
			$data['supermenu_htmlarea2'] = $this->request->post['supermenu_htmlarea2'];
		} elseif ($this->config->get('supermenu_htmlarea2')) {
			$data['supermenu_htmlarea2'] = $this->config->get('supermenu_htmlarea2');
		} else {
		    $data['supermenu_htmlarea2'] = array();
		}
		if (isset($this->request->post['supermenu_htmlarea3'])) {
			$data['supermenu_htmlarea3'] = $this->request->post['supermenu_htmlarea3'];
		} elseif ($this->config->get('supermenu_htmlarea3')) {
			$data['supermenu_htmlarea3'] = $this->config->get('supermenu_htmlarea3');
		} else {
		    $data['supermenu_htmlarea3'] = array();
		}
		if (isset($this->request->post['supermenu_htmlarea4'])) {
			$data['supermenu_htmlarea4'] = $this->request->post['supermenu_htmlarea4'];
		} elseif ($this->config->get('supermenu_htmlarea4')) {
			$data['supermenu_htmlarea4'] = $this->config->get('supermenu_htmlarea4');
		} else {
		    $data['supermenu_htmlarea4'] = array();
		}
		if (isset($this->request->post['supermenu_htmlarea5'])) {
			$data['supermenu_htmlarea5'] = $this->request->post['supermenu_htmlarea5'];
		} elseif ($this->config->get('supermenu_htmlarea5')) {
			$data['supermenu_htmlarea5'] = $this->config->get('supermenu_htmlarea5');
		} else {
		    $data['supermenu_htmlarea5'] = array();
		}
		if (isset($this->request->post['supermenu_htmlarea6'])) {
			$data['supermenu_htmlarea6'] = $this->request->post['supermenu_htmlarea6'];
		} elseif ($this->config->get('supermenu_htmlarea6')) {
			$data['supermenu_htmlarea6'] = $this->config->get('supermenu_htmlarea6');
		} else {
		    $data['supermenu_htmlarea6'] = array();
		}
		if (isset($this->request->post['supermenu_htmlarea7'])) {
			$data['supermenu_htmlarea7'] = $this->request->post['supermenu_htmlarea7'];
		} elseif ($this->config->get('supermenu_htmlarea7')) {
			$data['supermenu_htmlarea7'] = $this->config->get('supermenu_htmlarea7');
		} else {
		    $data['supermenu_htmlarea7'] = array();
		}
		if (isset($this->request->post['supermenu_htmlarea8'])) {
			$data['supermenu_htmlarea8'] = $this->request->post['supermenu_htmlarea8'];
		} elseif ($this->config->get('supermenu_htmlarea8')) {
			$data['supermenu_htmlarea8'] = $this->config->get('supermenu_htmlarea8');
		} else {
		    $data['supermenu_htmlarea8'] = array();
		}
		if (isset($this->request->post['supermenu_htmlarea9'])) {
			$data['supermenu_htmlarea9'] = $this->request->post['supermenu_htmlarea9'];
		} elseif ($this->config->get('supermenu_htmlarea8')) {
			$data['supermenu_htmlarea9'] = $this->config->get('supermenu_htmlarea9');
		} else {
		    $data['supermenu_htmlarea9'] = array();
		}
		if (isset($this->request->post['supermenu_htmlarea10'])) {
			$data['supermenu_htmlarea10'] = $this->request->post['supermenu_htmlarea10'];
		} elseif ($this->config->get('supermenu_htmlarea10')) {
			$data['supermenu_htmlarea10'] = $this->config->get('supermenu_htmlarea10');
		} else {
		    $data['supermenu_htmlarea10'] = array();
		}
		if (isset($this->request->post['supermenu_bannerspace_width'])) {
			$data['supermenu_bannerspace_width'] = $this->request->post['supermenu_bannerspace_width'];
		} elseif ($this->config->get('supermenu_bannerspace_width')) {
			$data['supermenu_bannerspace_width'] = $this->config->get('supermenu_bannerspace_width');
		} else {
			$data['supermenu_bannerspace_width'] = '';
		}
		if (isset($this->request->post['supermenu_cache'])) {
			$data['supermenu_cache'] = $this->request->post['supermenu_cache'];
		} elseif ($this->config->get('supermenu_cache')) { 
			$data['supermenu_cache'] = $this->config->get('supermenu_cache');
		} else {
		    $data['supermenu_cache'] = 0;
		}
		
		$this->load->model('catalog/product');
		
		$data['products'] = array();
		
		if (isset($this->request->post['supermenu_fproduct'])) {
			$products = $this->request->post['supermenu_fproduct'];
		} elseif ($this->config->get('supermenu_fproduct')) {
			$products = $this->config->get('supermenu_fproduct');
		} else {
			$products = array();
		}	
		
		foreach ($products as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);

			if ($product_info) {
				$data['products'][] = array(
					'product_id' => $product_info['product_id'],
					'name'       => $product_info['name']
				);
			}
		}
				
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/supermenu.tpl', $data));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/supermenu')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}