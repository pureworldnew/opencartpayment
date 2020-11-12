<?php
class ControllerModuleSuperCategoryMenuAdvanced extends Controller {
    public function index($setting) {
        $this->load->model('module/supercategorymenuadvanced');
        $data['store_id'] = $STORE = $this->config->get('config_store_id');
        $settings_module  = $this->model_module_supercategorymenuadvanced->getMySetting('SETTINGS_' . $STORE, $STORE);

        if (!isset($this->request->get['a'])) {
            $DIR_MY_STYLES = 'catalog/view/javascript/advancedmenu/templates/';
            $this->document->addScript('catalog/view/javascript/advancedmenu/advancedmenu.js');
            if (isset($settings_module['styles']['css'])) {
                $template_styles = $settings_module['styles']['css'];
            } //isset($settings_module['styles']['css'])
            else {
                $template_styles = 'default';
            }
            if (file_exists($DIR_MY_STYLES . $template_styles . '/advancedmenu.css')) {
                $this->document->addStyle($DIR_MY_STYLES . $template_styles . '/advancedmenu.css?v='.rand());
            } //file_exists($DIR_MY_STYLES . $template_styles . '/advancedmenu.css')
            else {
                $this->document->addStyle($DIR_MY_STYLES . 'default/advancedmenu.css?v='.rand());
            }
            if (($settings_module['general_data']['rtl_lang'])) {
                $this->document->addStyle('catalog/view/javascript/advancedmenu/advancedmenu_rtl.css?v='.rand());
            } //($settings_module['general_data']['rtl_lang'])
            if (isset($settings_module['styles']['skin_slider'])) {
                $this->document->addStyle('catalog/view/javascript/advancedmenu/slider/skins/' . $settings_module['styles']['skin_slider'] . '.css?v='.rand());
            } //isset($settings_module['styles']['skin_slider'])
            else {
                $this->document->addStyle('catalog/view/javascript/advancedmenu/slider/skins/jslider.yellow.classic.css?v='.rand());
            }
        } //!isset($this->request->get['a'])


        if (isset($this->request->get['filtering'])) {
            $from = $this->request->get['filtering'];
        } //isset($this->request->get['filtering'])
        else {
            $from = '';
        }

        $category_id = $manufacturer_id = '';
        if (isset($this->request->get['path'])) {
            $pt          = explode('_', (string) $this->request->get['path']);
            $category_id = (int) array_pop($pt);
        } //isset($this->request->get['path'])
        else {
            $category_id = 0;
        }
        if (isset($this->request->get['manufacturer_id'])) {
            $manufacturer_id = $this->request->get['manufacturer_id'];
        } //isset($this->request->get['manufacturer_id'])
		
		
        if (isset($this->request->get['route'])) {
            if ($this->request->get['route'] == "product/asearch" || $this->request->get['route'] == "module/supercategorymenuadvanced" || $this->request->get['route'] == "product/product") {
                if ($from == "M") {
                    return $this->CreateMenu("M", $manufacturer_id, $settings_module);
                } //$from == "M"
                else {
                    return $this->CreateMenu("C", $category_id, $settings_module);
                }
            } 
			
			//$this->request->get['route'] == "product/asearch" || $this->request->get['route'] == "module/supercategorymenuadvanced" || $this->request->get['route'] == "product/product"
            if (empty($this->request->get['route']) || $this->request->get['route'] == "common/home" || $this->request->get['route'] == "product/special" || $this->request->get['route'] == "product/search" || $this->request->get['route'] == "product/isearch") {
                return $this->CreateMenu("C", 0, $settings_module);
            } //empty($this->request->get['route']) || $this->request->get['route'] == "common/home" || $this->request->get['route'] == "product/special" || $this->request->get['route'] == "product/search"
            
			if ($this->request->get['route'] == "product/category") {
                return $this->CreateMenu("C", $category_id, $settings_module);
            } //$this->request->get['route'] == "product/category"
            
			if ($this->request->get['route'] == "product/manufacturer") {
                return $this->CreateMenu("M", 0, $settings_module);
            } //$this->request->get['route'] == "product/manufacturer"
            
			if ($this->request->get['route'] == "product/manufacturer/info") {
                return $this->CreateMenu("M", $manufacturer_id, $settings_module);
            } //$this->request->get['route'] == "product/manufacturer/info"
        } //isset($this->request->get['route'])
        else {
            return $this->CreateMenu("C", 0, $settings_module);
        }
    }
	
	
    public function CreateMenu($what, $id, $settings_module) {
		
        $this->load->model('module/supercategorymenuadvanced');
        $has_filter                        = $filter = $filtros_seleccionados = $filter_att = $filter_opt = '';
        $filter_manufacturer_id            = $filter_category_id = '';
        $filter_option_id                  = $filter_options_by_name = $filter_options_by_ids = '';
        $filter_attribute_string           = $filter_attribute_id = $filter_attributes_by_name = '';
        $filter_ids                        = $filter_by_name = '';
        $filter_price_range                = '';
        $filter_special                    = $filter_model = '';
        $filter_min_price                  = $filter_max_price = '';
        $filter_stock_id                   = $filter_stock = $filter_clearance = $filter_arrivals = '';
        $filter_manufacturers_by_id        = '';
        $filter_manufacturers_by_id_string = '';
        $filter_productinfo_id = '';
        $filter_width          = $filter_height = $filter_length = $filter_weight = '';
        $filter_ean            = $filter_isbn = $filter_mpn = $filter_jan = $filter_sku = $filter_upc = $filter_location = '';
        $filter_rating         = '';
        $idx                   = $tip_id = 1;
        $url_where2go_brands   = $filter_search = $filter_tag = $filter_description = $filter_subcategory = $urlfilter = '';
        $super_url             = $url_pr = $url_search = $url_limits = '';
        $url_top_filters       = '';
        if ($what == "M") {
            $values_in_filter    = $this->config->get('VALORESM_' . $id, (int) $this->config->get('config_store_id'));
            $url_where2go_brands = 'manufacturer_id=' . $id;
        } //$what == "M"
        elseif ($what == "C") {
             $values_in_filter = $this->config->get('VALORES_' . $id, (int) $this->config->get('config_store_id'));
            if ($id == 0) {
                $filter_category_id = $id;
                $url_top_filters .= '&path=' . $id;
            } //$id == 0
        } //$what == "C"
		
        if (isset($this->request->get['path'])) {
            $url_top_filters .= '&path=' . $this->request->get['path'];
            $pt                 = explode('_', (string) $this->request->get['path']);
            $filter_category_id = (int) array_pop($pt);
        } //isset($this->request->get['path'])
        if (isset($this->request->get['manufacturer_id'])) {
            $url_top_filters .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
            $filter_manufacturer_id = $this->request->get['manufacturer_id'];
        } //isset($this->request->get['manufacturer_id'])
        if (isset($this->request->get['filter_rating'])) {
            $url_top_filters .= '&filter_rating=' . $this->request->get['filter_rating'];
            $filter_rating = $this->request->get['filter_rating'];
        } //isset($this->request->get['filter_rating'])
        if (isset($this->request->get['filter_clearance'])) {
            $url_top_filters .= '&filter_clearance=' . $this->request->get['filter_clearance'];
            $filter_clearance = $this->request->get['filter_clearance'];
        } //isset($this->request->get['filter_clearance'])
        if (isset($this->request->get['filter_stock'])) {
            $url_top_filters .= '&filter_stock=' . $this->request->get['filter_stock'];
            $filter_stock = $this->request->get['filter_stock'];
        } //isset($this->request->get['filter_stock'])
        if (isset($this->request->get['filter_arrivals'])) {
            $url_top_filters .= '&filter_arrivals=' . $this->request->get['filter_arrivals'];
            $filter_arrivals = $this->request->get['filter_arrivals'];
        } //isset($this->request->get['filter_arrivals'])
        if (isset($this->request->get['filter_special'])) {
            $url_top_filters .= '&filter_special=' . $this->request->get['filter_special'];
            $filter_special = $this->request->get['filter_special'];
        } //isset($this->request->get['filter_special'])
        if (isset($this->request->get['route']) && $this->request->get['route'] == "product/special") {
            $url_top_filters .= '&filter_special=yes';
            $filter_special = "yes";
        } //isset($this->request->get['route']) && $this->request->get['route'] == "product/special"
        if (isset($this->request->get['filter_width'])) {
            $url_top_filters .= '&filter_width=' . $this->request->get['filter_width'];
            $filter_width = $this->request->get['filter_width'];
            $filter_productinfo_id .= "1,";
        } //isset($this->request->get['filter_width'])
        if (isset($this->request->get['filter_height'])) {
            $url_top_filters .= '&filter_height=' . $this->request->get['filter_height'];
            $filter_height = $this->request->get['filter_height'];
            $filter_productinfo_id .= "2,";
        } //isset($this->request->get['filter_height'])
        if (isset($this->request->get['filter_length'])) {
            $url_top_filters .= '&filter_length=' . $this->request->get['filter_length'];
            $filter_length = $this->request->get['filter_length'];
            $filter_productinfo_id .= "3,";
        } //isset($this->request->get['filter_length'])
        if (isset($this->request->get['filter_model'])) {
            $url_top_filters .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
            $filter_model = $this->request->get['filter_model'];
            $filter_productinfo_id .= "4,";
        } //isset($this->request->get['filter_model'])
        if (isset($this->request->get['filter_sku'])) {
            $url_top_filters .= '&filter_sku=' . $this->request->get['filter_sku'];
            $filter_sku = $this->request->get['filter_sku'];
            $filter_productinfo_id .= "5,";
        } //isset($this->request->get['filter_sku'])
        if (isset($this->request->get['filter_upc'])) {
            $url_top_filters .= '&filter_upc=' . $this->request->get['filter_upc'];
            $filter_upc = $this->request->get['filter_upc'];
            $filter_productinfo_id .= "6,";
        } //isset($this->request->get['filter_upc'])
        if (isset($this->request->get['filter_location'])) {
            $url_top_filters .= '&filter_location=' . urlencode(html_entity_decode($this->request->get['filter_location'], ENT_QUOTES, 'UTF-8'));
            $filter_location = $this->request->get['filter_location'];
            $filter_productinfo_id .= "7,";
        } //isset($this->request->get['filter_location'])
        if (isset($this->request->get['filter_weight'])) {
            $url_top_filters .= '&filter_weight=' . $this->request->get['filter_weight'];
            $filter_weight = $this->request->get['filter_weight'];
            $filter_productinfo_id .= "8,";
        } //isset($this->request->get['filter_weight'])
        if (isset($this->request->get['filter_ean'])) {
            $url_top_filters .= '&filter_ean=' . $this->request->get['filter_ean'];
            $filter_ean = $this->request->get['filter_ean'];
            $filter_productinfo_id .= "9,";
        } //isset($this->request->get['filter_ean'])
        if (isset($this->request->get['filter_isbn'])) {
            $url_top_filters .= '&filter_isbn=' . $this->request->get['filter_isbn'];
            $filter_isbn = $this->request->get['filter_isbn'];
            $filter_productinfo_id .= "10,";
        } //isset($this->request->get['filter_isbn'])
        if (isset($this->request->get['filter_mpn'])) {
            $url_top_filters .= '&filter_mpn=' . $this->request->get['filter_mpn'];
            $filter_mpn = $this->request->get['filter_mpn'];
            $filter_productinfo_id .= "11,";
        } //isset($this->request->get['filter_mpn'])
        if (isset($this->request->get['filter_jan'])) {
            $url_top_filters .= '&filter_jan=' . $this->request->get['filter_jan'];
            $filter_jan = $this->request->get['filter_jan'];
            $filter_productinfo_id .= "12,";
        } //isset($this->request->get['filter_jan'])
		
		
        if (isset($this->request->get['filter_att'])) {
            $filter_att = true;
            foreach ($this->request->get['filter_att'] as $key => $value) {
                $url_top_filters .= '&filter_att[' . $key . ']=' . urlencode(html_entity_decode($value, ENT_QUOTES, 'UTF-8'));
                $att_info      = $values_in_filter['attributes'][$key];
                $att_name      = $att_info['name'];
                $att_view      = $att_info['view'];
                $att_separator = $att_info['separator'];
                if ($att_separator != "no") {
                    $ss = "p";
                } //$att_separator != "no"
                else {
                    $ss = $this->model_module_supercategorymenuadvanced->GetView($att_view);
                }
                $filter_attribute_id .= $key . ",";
                $filter_by_name .= $this->model_module_supercategorymenuadvanced->CleanName($value) . $ss . "ATTNNATT@@@";
                $filter_ids .= $key . ",";
                $filter_options_by_ids .= "0,";
            } //$this->request->get['filter_att'] as $key => $value
        } //isset($this->request->get['filter_att']) 
        if (isset($this->request->get['filter_opt'])) {
            $filter_opt = true;
            foreach ($this->request->get['filter_opt'] as $key => $value) {
                $url_top_filters .= '&filter_opt[' . $key . ']=' . urlencode(html_entity_decode($value, ENT_QUOTES, 'UTF-8'));
                $opt_info = $values_in_filter['options'][$this->model_module_supercategorymenuadvanced->GetOptionid($key)];
                $opt_name = $opt_info['name'];
                $opt_view = $opt_info['view'];
                $filter_option_id .= $this->model_module_supercategorymenuadvanced->GetOptionid($key) . ",";
                $filter_by_name .= $this->model_module_supercategorymenuadvanced->CleanName($value) . "OPTTOP@@@";
                $filter_ids .= $key . ",";
                $filter_options_by_ids .= $key . ",";
            } //$this->request->get['filter_opt'] as $key => $value
        } //isset($this->request->get['filter_opt'])
		
		
		
        if (isset($this->request->get['filtering'])) {
            $from             = $this->request->get['filtering'];
            $url_top_filters2 = '&filtering=' . $this->request->get['filtering'];
        } //isset($this->request->get['filtering'])
        else {
            if ($what == "M") {
                $url_top_filters2 = '&filtering=M';
            } //$what == "M"
            elseif ($what == "C") {
                $url_top_filters2 = '&filtering=C';
            } //$what == "C"
        }
		
		
		
        $url_where2go             = $url_top_filters2 . $url_top_filters;
        $data['see_more_trigger'] = isset($settings_module['general_data']['see_more_trigger']) ? $settings_module['general_data']['see_more_trigger'] : false;
        $data['option_tip']       = isset($settings_module['general_data']['option_tip']) ? $settings_module['general_data']['option_tip'] : false;
        $count_products           = $data['count_products'] = isset($settings_module['general_data']['countp']) ? $settings_module['general_data']['countp'] : false;
        $nofollow                 = $data['nofollow'] = isset($settings_module['general_data']['nofollow']) ? 'rel="nofollow"' : '';
        $track_google             = $data['track_google'] = isset($settings_module['general_data']['track_google']) ? $settings_module['general_data']['track_google'] : false;
        $menu_mode                = isset($settings_module['general_data']['menu_mode']) ? $settings_module['general_data']['menu_mode'] : "developing";
        $image_option_width       = isset($settings_module['general_data']['image_option_width']) ? $settings_module['general_data']['image_option_width'] : 20;
        $image_option_height      = isset($settings_module['general_data']['image_option_height']) ? $settings_module['general_data']['image_option_height'] : 20;
        $asearch_filters          = isset($settings_module['general_data']['asearch_filters']) ? $settings_module['general_data']['asearch_filters'] : false;
        $menu_filters             = isset($settings_module['general_data']['menu_filters']) ? $settings_module['general_data']['menu_filters'] : false;
        $ocscroll                 = isset($settings_module['general_data']['ocscroll']) ? $settings_module['general_data']['ocscroll'] : false;
        $menu_scroll              = isset($settings_module['general_data']['enable_scroll']) ? $settings_module['general_data']['enable_scroll'] : false;
        $option_stock             = isset($settings_module['general_data']['option_stock']) ? $settings_module['general_data']['option_stock'] : false;
        $enable_scroll_layout     = isset($settings_module['general_data']['enable_scroll_layout']) ? $settings_module['general_data']['enable_scroll_layout'] : "column_left";
        $modecatalog              = isset($settings_module['general_data']['modecatalog']) ? $settings_module['general_data']['modecatalog'] : false;
        $nodata                   = isset($settings_module['general_data']['nodata']) ? $settings_module['general_data']['nodata'] : true;
        $data['isresponsive']     = isset($settings_module['responsive']['enable']) ? $settings_module['responsive']['enable'] : 0;
        $data['menu_name']        = isset($settings_module['responsive']['name']) ? $settings_module['responsive']['name'] : "FILTER MENU";
        $data['appendto']         = isset($settings_module['responsive']['appendto']) ? $settings_module['responsive']['appendto'] : "#menu";
        $data['scolumn_left']     = $data['scolumn_right'] = false;
        if ($menu_scroll) {
            $data['s' . $enable_scroll_layout] = 's' . $enable_scroll_layout;
        } //$menu_scroll
        $data_settings = array(
            'nofollow' => $nofollow,
            'track_google' => $track_google,
            'count_products' => $count_products,
            'option_stock' => $option_stock,
            'clearance' => isset($settings_module['stock']['clearance_value']) ? $settings_module['stock']['clearance_value'] : '',
            'days' => isset($settings_module['stock']['number_day']) ? $settings_module['stock']['number_day'] : 7,
            'reviews' => $settings_module['reviews']['tipo']
        );
        if (isset($settings_module['ajax']['enable'])) {
            $is_ajax = $data['is_ajax'] = $settings_module['ajax']['enable'];
        } //isset($settings_module['ajax']['enable'])
        else {
            $is_ajax = $data['is_ajax'] = 0;
        }
        if (isset($settings_module['ajax']['loader'])) {
            $data['loader'] = $settings_module['ajax']['loader'];
            if (isset($settings_module['ajax']['loader_image'])) {
                $data['loader_image'] = HTTP_SERVER . 'image/advancedmenu/loaders/' . $settings_module['ajax']['loader_image'];
            } //$settings_module['ajax']['loader_image']
            else {
                $data['loader_image'] = HTTP_SERVER . 'image/advancedmenu/loaders/103.png';
            }
        } //isset($settings_module['ajax']['loader'])
        else {
            $data['loader'] = false;
        }
        if (isset($settings_module['ajax']['scrollto'])) {
            $data['scrollto'] = $settings_module['ajax']['scrollto'];
        } //isset($settings_module['ajax']['scrollto'])
        else {
            $data['scrollto'] = 0;
        }
        if (isset($this->request->get['search'])) {
            $search = $this->request->get['search'];
            $url_search .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
        } //isset($this->request->get['search'])
        else {
            $search = '';
        }
        if (isset($this->request->get['tag'])) {
            $tag = $this->request->get['tag'];
            $url_search .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
        } //isset($this->request->get['tag'])
        elseif (isset($this->request->get['search'])) {
            $tag = $this->request->get['search'];
            $url_search .= '&tag=' . urlencode(html_entity_decode($tag, ENT_QUOTES, 'UTF-8'));
        } //isset($this->request->get['search'])
        else {
            $tag = '';
        }
        if (isset($this->request->get['description'])) {
            $description = $this->request->get['description'];
            $url_search .= '&description=' . $this->request->get['description'];
        } //isset($this->request->get['description'])
        else {
            $description = '';
        }
        if (isset($this->request->get['category_id'])) {
            $category_id = $this->request->get['category_id'];
            $url_search .= '&category_id=' . $this->request->get['category_id'];
        } //isset($this->request->get['category_id'])
        else {
            $category_id = 0;
        }
        if (isset($this->request->get['sub_category'])) {
            $sub_category = $this->request->get['sub_category'];
            $url_search .= '&sub_category=' . $this->request->get['sub_category'];
        } //isset($this->request->get['sub_category'])
        else {
            $sub_category = '';
        }
        if (isset($this->request->get['sort'])) {
            $url_limits .= '&sort=' . $this->request->get['sort'];
        } //isset($this->request->get['sort'])
        if (isset($this->request->get['order'])) {
            $url_limits .= '&order=' . $this->request->get['order'];
        } //isset($this->request->get['order'])
        if (isset($this->request->get['limit'])) {
            $url_limits .= '&limit=' . $this->request->get['limit'];
        } //isset($this->request->get['limit'])
        if (isset($this->request->get['page'])) {
        } //isset($this->request->get['page'])
        if (isset($this->request->get['pr'])) {
            $url_pr .= '&pr=' . $this->request->get['pr'];
            $filter_price_range = $this->request->get['pr'];
        } //isset($this->request->get['pr'])
        if (isset($this->request->get['PRICERANGE'])) {
            $url_pr .= '&pr=' . $this->request->get['PRICERANGE'];
            $filter_price_range = $this->request->get['PRICERANGE'];
        }
        $filter_coin = $this->session->data['currency'];
        $this->language->load('module/supercategorymenuadvanced');
        $data['heading_title']       = $this->language->get('heading_title');
        $see_more_text               = $this->language->get('see_more_text');
        $remove_filter_text          = $this->language->get('remove_filter_text');
        $pricerange_text             = $this->language->get('pricerange_text');
        $manufacturer_text           = $this->language->get('manufacturer_text');
        $stock_text                  = $this->language->get('stock_text');
        $no_data_text                = $this->language->get('no_data_text');
        $category_text               = $this->language->get('category_text');
        $rating_text                 = $this->language->get('rating_text');
        $txt_reset_filter            = $this->language->get('txt_reset_filter');
        $data['entry_selected']      = $this->language->get('entry_selected');
        $data['entry_select_filter'] = $this->language->get('entry_select_filter');
        $data['txt_your_selections'] = $this->language->get('txt_your_selections');
        $data['reset_all_filter']    = "<a class=\"link_filter_del smenu {dnd:'" . $this->url->link('product/asearch', $url_where2go) . "&amp;filter=', ajaxurl:'" . $super_url . "&amp;filter=', gapush:'no'}\" href=\"javascript:void(0)\" nofollow><img src=\"image/advancedmenu/spacer.gif\" alt=\"" . $txt_reset_filter . "\" class=\"filter_del\" /></a>";
        $data['values_selected']     = array();
        $data['values_no_selected']  = array();
		//echo '<pre>';
		
        if (isset($filter_manufacturer_id) && !empty($filter_manufacturer_id)) {
			//echo 'here';
            $url_where2go_clean = str_replace("&manufacturer_id=" . $filter_manufacturer_id, "", $url_where2go);
            $filter_url         = $this->url->link('product/asearch', $url_where2go_clean) . $url_pr . $url_limits . $url_search;
            $ajax_url           = $url_where2go_clean . $url_pr . $url_limits . $url_search;
            $namefinal          = $this->model_module_supercategorymenuadvanced->getManufacturerName($filter_manufacturer_id);
            $see_more_url       = "index.php?route=module/supercategorymenuadvancedseemore/seemore&amp;dnd=seemore&amp;name=" . urlencode($namefinal) . $url_where2go_clean . $url_pr . $url_limits . $url_search . "&amp;tipo=m&amp;id=" . $filter_manufacturer_id;
            $data_filtering     = array(
                'href' => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
                'ajax_url' => $ajax_url,
                'see_more_text' => $see_more_text,
                'remove_filter_text' => $remove_filter_text,
                'name' => html_entity_decode($namefinal),
                'see_more_url' => $see_more_url
            );
            $html               = '';
            $html .= $this->model_module_supercategorymenuadvanced->GetHtmlSelected($data_filtering, $data_settings, 'm');
            $filtros_seleccionados['M_' . $filter_manufacturer_id] = array(
                'id' => $filter_manufacturer_id,
                'Tipo' => 'm',
                'name' => html_entity_decode($namefinal),
                'href' => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
                'ajax_url' => $ajax_url,
                'url_filter' => $urlfilter,
                'see_more' => $see_more_url,
                'dnd' => html_entity_decode($manufacturer_text),
                'html' => $html,
                'tip_code' => ''
            );
        } //isset($filter_manufacturer_id) && !empty($filter_manufacturer_id)
		
		
		
        if (isset($filter_width) && !empty($filter_width)) {
            $filtros_seleccionados['W_1'] = $this->model_module_supercategorymenuadvanced->GetProductInfosSelected($values_in_filter, $what, $url_where2go, $filter_width, 1, 'width', $data_settings, $no_data_text, $url_pr, $url_limits, $url_search);
        } //isset($filter_width) && !empty($filter_width)
        if (isset($filter_height) && !empty($filter_height)) {
            $filtros_seleccionados['H_2'] = $this->model_module_supercategorymenuadvanced->GetProductInfosSelected($values_in_filter, $what, $url_where2go, $filter_height, 2, 'height', $data_settings, $no_data_text, $url_pr, $url_limits, $url_search);
        } //isset($filter_height) && !empty($filter_height)
        if (isset($filter_length) && !empty($filter_length)) {
            $filtros_seleccionados['L_3'] = $this->model_module_supercategorymenuadvanced->GetProductInfosSelected($values_in_filter, $what, $url_where2go, $filter_length, 3, 'length', $data_settings, $no_data_text, $url_pr, $url_limits, $url_search);
        } //isset($filter_length) && !empty($filter_length)
        if (isset($filter_model) && !empty($filter_model)) {
            $filtros_seleccionados['MO_4'] = $this->model_module_supercategorymenuadvanced->GetProductInfosSelected($values_in_filter, $what, $url_where2go, $filter_model, 4, 'model', $data_settings, $no_data_text, $url_pr, $url_limits, $url_search);
        } //isset($filter_model) && !empty($filter_model)
        if (isset($filter_sku) && !empty($filter_sku)) {
            $filtros_seleccionados['SK_5'] = $this->model_module_supercategorymenuadvanced->GetProductInfosSelected($values_in_filter, $what, $url_where2go, $filter_sku, 5, 'sku', $data_settings, $no_data_text, $url_pr, $url_limits, $url_search);
        } //isset($filter_sku) && !empty($filter_sku)
        if (isset($filter_upc) && !empty($filter_upc)) {
            $filtros_seleccionados['UP_6'] = $this->model_module_supercategorymenuadvanced->GetProductInfosSelected($values_in_filter, $what, $url_where2go, $filter_upc, 6, 'upc', $data_settings, $no_data_text, $url_pr, $url_limits, $url_search);
        } //isset($filter_upc) && !empty($filter_upc)
        if (isset($filter_location) && !empty($filter_location)) {
            $filtros_seleccionados['LO_7'] = $this->model_module_supercategorymenuadvanced->GetProductInfosSelected($values_in_filter, $what, $url_where2go, $filter_location, 7, 'location', $data_settings, $no_data_text, $url_pr, $url_limits, $url_search);
        } //isset($filter_location) && !empty($filter_location)
        if (isset($filter_weight) && !empty($filter_weight)) {
            $filtros_seleccionados['WG_8'] = $this->model_module_supercategorymenuadvanced->GetProductInfosSelected($values_in_filter, $what, $url_where2go, $filter_weight, 8, 'weight', $data_settings, $no_data_text, $url_pr, $url_limits, $url_search);
        } //isset($filter_weight) && !empty($filter_weight)
        if (isset($filter_ean) && !empty($filter_ean)) {
            $filtros_seleccionados['E_9'] = $this->model_module_supercategorymenuadvanced->GetProductInfosSelected($values_in_filter, $what, $url_where2go, $filter_ean, 9, 'ean', $data_settings, $no_data_text, $url_pr, $url_limits, $url_search);
        } //isset($filter_ean) && !empty($filter_ean)
        if (isset($filter_isbn) && !empty($filter_isbn)) {
            $filtros_seleccionados['I_10'] = $this->model_module_supercategorymenuadvanced->GetProductInfosSelected($values_in_filter, $what, $url_where2go, $filter_isbn, 10, 'isbn', $data_settings, $no_data_text, $url_pr, $url_limits, $url_search);
        } //isset($filter_isbn) && !empty($filter_isbn)
        if (isset($filter_mpn) && !empty($filter_mpn)) {
            $filtros_seleccionados['P_11'] = $this->model_module_supercategorymenuadvanced->GetProductInfosSelected($values_in_filter, $what, $url_where2go, $filter_mpn, 11, 'mpn', $data_settings, $no_data_text, $url_pr, $url_limits, $url_search);
        } //isset($filter_mpn) && !empty($filter_mpn)
        if (isset($filter_jan) && !empty($filter_jan)) {
            $filtros_seleccionados['J_12'] = $this->model_module_supercategorymenuadvanced->GetProductInfosSelected($values_in_filter, $what, $url_where2go, $filter_jan, 12, 'jan', $data_settings, $no_data_text, $url_pr, $url_limits, $url_search);
        } //isset($filter_jan) && !empty($filter_jan)
		
		
        if ($filter_att) {
			//echo 'attributes';
            foreach ($this->request->get['filter_att'] as $key => $value) {
                $att_info           = $values_in_filter['attributes'][$key];
                $att_name           = $att_info['name'];
                $att_view           = $att_info['view'];
                $att_separator      = $att_info['separator'];
                $att_id             = $att_info['attribute_id'];
                $url_where2go_clean = $this->model_module_supercategorymenuadvanced->RemoveFilterOPtAtt($url_where2go, 'filter_att', $key);
                $filter_url         = $this->url->link('product/asearch', $url_where2go_clean) . $url_pr . $url_limits . $url_search;
                $ajax_url           = $url_where2go_clean . $url_pr . $url_limits . $url_search;
                $see_more_url       = "index.php?route=module/supercategorymenuadvancedseemore/seemore&amp;dnd=seemore&amp;name=" . urlencode($value) . "&amp;tipo=a&amp;id=" . $att_id . $url_where2go_clean . $url_pr . $url_limits . $url_search;
                $data_filtering     = array(
                    'href' => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
                    'ajax_url' => $ajax_url,
                    'see_more_text' => $see_more_text,
                    'remove_filter_text' => $remove_filter_text,
                    'name' => html_entity_decode($value),
                    'see_more_url' => $see_more_url
                );
                $html               = '';
                $html .= ($att_view == "slider") ? '' : $this->model_module_supercategorymenuadvanced->GetHtmlSelected($data_filtering, $data_settings, 'a');
                $filtros_seleccionados["A_" . $att_id] = array(
                    'id' => $att_id,
                    'Tipo' => 'a',
                    'name' => html_entity_decode($value),
                    'href' => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
                    'ajax_url' => $ajax_url,
                    'url_filter' => $urlfilter,
                    'see_more' => $see_more_url,
                    'dnd' => html_entity_decode($this->model_module_supercategorymenuadvanced->getAttributeName($att_id)),
                    'html' => $html,
                    'tip_code' => ''
                );
            } //$this->request->get['filter_att'] as $key => $value
        } //$filter_att

		
        if ($filter_opt) {
			//echo 'options';
            foreach ($this->request->get['filter_opt'] as $key => $value) {
                $opt_info           = $values_in_filter['options'][$this->model_module_supercategorymenuadvanced->GetOptionid($key)];
                $opt_name           = $opt_info['name'];
                $opt_view           = $opt_info['view'];
                $opt_id             = $opt_info['option_id'];
                $url_where2go_clean = $this->model_module_supercategorymenuadvanced->RemoveFilterOPtAtt($url_where2go, 'filter_opt', $key);
                $filter_url         = $this->url->link('product/asearch', $url_where2go_clean) . $url_pr . $url_limits . $url_search;
                $ajax_url           = $url_where2go_clean . $url_pr . $url_limits . $url_search;
                $see_more_url       = "index.php?route=module/supercategorymenuadvancedseemore/seemore&amp;dnd=seemore&amp;name=" . urlencode($opt_name) . "&amp;id2=" . $key . "&amp;tipo=o&amp;id=" . $opt_id . $url_where2go_clean . $url_pr . $url_limits . $url_search;
                $image              = ($opt_view == "image") ? $this->model_module_supercategorymenuadvanced->getoptionImage($key, $image_option_width, $image_option_height) : '';
                $data_filtering     = array(
                    'href' => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
                    'ajax_url' => $ajax_url,
                    'see_more_text' => $see_more_text,
                    'remove_filter_text' => $remove_filter_text,
                    'name' => html_entity_decode($value),
                    'see_more_url' => $see_more_url,
                    'image' => $image
                );
                $html               = '';
                $html .= ($opt_view == "image") ? $this->model_module_supercategorymenuadvanced->GetHtmlImageSelected($data_filtering, $data_settings) : $this->model_module_supercategorymenuadvanced->GetHtmlSelected($data_filtering, $data_settings, 'o');
                $filtros_seleccionados["O_" . $opt_id] = array(
                    'id' => $opt_id,
                    'Tipo' => 'o',
                    'name' => html_entity_decode($value),
                    'href' => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
                    'ajax_url' => $ajax_url,
                    'url_filter' => $urlfilter,
                    'see_more' => $see_more_url,
                    'dnd' => html_entity_decode($this->model_module_supercategorymenuadvanced->getOptionName($opt_id)),
                    'html' => $html,
                    'tip_code' => ''
                );
            } //$this->request->get['filter_opt'] as $key => $value
        } //$filter_opt
		
		
		
        if (isset($filter_rating) && !empty($filter_rating)) {
            $url_where2go_clean = str_replace("&filter_rating=" . $filter_rating, "", $url_where2go);
            $filter_url         = $this->url->link('product/asearch', $url_where2go_clean) . $url_pr . $url_limits . $url_search;
            $ajax_url           = $url_where2go_clean . $url_pr . $url_limits . $url_search;
            $namefinal          = $this->language->get('rating_text');
            $see_more_url       = "index.php?route=module/supercategorymenuadvancedseemore/seemore&amp;dnd=seemore&amp;name=" . urlencode($filter_rating) . "&amp;tipo=ra&amp;id=" . $filter_rating . $url_where2go_clean . $url_pr . $url_limits . $url_search;
            $data_filtering     = array(
                'href' => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
                'ajax_url' => $ajax_url,
                'see_more_text' => $see_more_text,
                'remove_filter_text' => $remove_filter_text,
                'name' => html_entity_decode($namefinal),
                'see_more_url' => $see_more_url,
                'filter_rating' => $filter_rating,
                'rating_extra_txt' => $this->language->get('rating_text_' . $settings_module['reviews']['tipo'])
            );
            $html               = '';
            $html .= $this->model_module_supercategorymenuadvanced->GetHtmlImageSelectedReviews($data_filtering, $data_settings, $this->language->get('rating_text_' . $settings_module['reviews']['tipo']));
            $filtros_seleccionados['RA_A'] = array(
                'id' => $filter_rating,
                'Tipo' => 'r',
                'name' => html_entity_decode($namefinal),
                'href' => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
                'ajax_url' => $ajax_url,
                'url_filter' => $urlfilter,
                'see_more' => $see_more_url,
                'dnd' => html_entity_decode($namefinal),
                'html' => $html,
                'tip_code' => ''
            );
        } //isset($filter_rating) && !empty($filter_rating)
		
		
        if (isset($filter_stock) && !empty($filter_stock)) {
            $url_where2go_clean = str_replace("&filter_stock=" . $filter_stock, "", $url_where2go);
            $filter_url         = $this->url->link('product/asearch', $url_where2go_clean) . $url_pr . $url_limits;
            $ajax_url           = $url_where2go_clean . $url_pr . $url_limits . $url_search;
            $namefinal          = $this->language->get('in_stock_text');
            $see_more_url       = false;
            $data_filtering     = array(
                'href' => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
                'ajax_url' => $ajax_url,
                'see_more_text' => $see_more_text,
                'remove_filter_text' => $remove_filter_text,
                'name' => html_entity_decode($namefinal),
                'see_more_url' => $see_more_url
            );
            $html               = '';
            $html .= $this->model_module_supercategorymenuadvanced->GetHtmlSelected($data_filtering, $data_settings, 'ss');
            $filtros_seleccionados['SS_A'] = array(
                'id' => $filter_stock,
                'Tipo' => 'ss',
                'name' => html_entity_decode($namefinal),
                'href' => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
                'ajax_url' => $ajax_url,
                'url_filter' => $urlfilter,
                'see_more' => $see_more_url,
                'dnd' => html_entity_decode($this->language->get('stock_text')),
                'html' => $html,
                'tip_code' => ''
            );
        } //isset($filter_stock) && !empty($filter_stock)
		
		
        if (isset($filter_special) && !empty($filter_special)) {
            $url_where2go_clean = str_replace("&filter_special=" . $filter_special, "", $url_where2go);
            $filter_url         = $this->url->link('product/asearch', $url_where2go_clean) . $url_pr . $url_limits . $url_search;
            $ajax_url           = $url_where2go_clean . $url_pr . $url_limits . $url_search;
            $namefinal          = $this->language->get('special_prices_text');
            $see_more_url       = false;
            $data_filtering     = array(
                'href' => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
                'ajax_url' => $ajax_url,
                'see_more_text' => $see_more_text,
                'remove_filter_text' => $remove_filter_text,
                'name' => html_entity_decode($namefinal),
                'see_more_url' => $see_more_url
            );
            $html               = '';
            $html .= $this->model_module_supercategorymenuadvanced->GetHtmlSelected($data_filtering, $data_settings, 'sp');
            $filtros_seleccionados['SP_A'] = array(
                'id' => $filter_special,
                'Tipo' => 'sp',
                'name' => html_entity_decode($namefinal),
                'href' => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
                'ajax_url' => $ajax_url,
                'url_filter' => $urlfilter,
                'see_more' => $see_more_url,
                'dnd' => html_entity_decode($this->language->get('stock_text')),
                'html' => $html,
                'tip_code' => ''
            );
        } //isset($filter_special) && !empty($filter_special)
		
		
        if (isset($filter_arrivals) && !empty($filter_arrivals)) {
            $url_where2go_clean = str_replace("&filter_arrivals=" . $filter_arrivals, "", $url_where2go);
            $filter_url         = $this->url->link('product/asearch', $url_where2go_clean) . $url_pr . $url_limits . $url_search;
            $ajax_url           = $url_where2go_clean . $url_pr . $url_limits . $url_search;
            $namefinal          = $this->language->get('new_products_text');
            $see_more_url       = false;
            $data_filtering     = array(
                'href' => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
                'ajax_url' => $ajax_url,
                'see_more_text' => $see_more_text,
                'remove_filter_text' => $remove_filter_text,
                'name' => html_entity_decode($namefinal),
                'see_more_url' => $see_more_url
            );
            $html               = '';
            $html .= $this->model_module_supercategorymenuadvanced->GetHtmlSelected($data_filtering, $data_settings, 'sn');
            $filtros_seleccionados['SN_A'] = array(
                'id' => $filter_arrivals,
                'Tipo' => 'sn',
                'name' => html_entity_decode($namefinal),
                'href' => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
                'ajax_url' => $ajax_url,
                'url_filter' => $urlfilter,
                'see_more' => $see_more_url,
                'dnd' => html_entity_decode($this->language->get('stock_text')),
                'html' => $html,
                'tip_code' => ''
            );
        } //isset($filter_arrivals) && !empty($filter_arrivals)
		
		
        if (isset($filter_clearance) && !empty($filter_clearance)) {
            $url_where2go_clean = str_replace("&filter_clearance=" . $filter_clearance, "", $url_where2go);
            $filter_url         = $this->url->link('product/asearch', $url_where2go_clean) . $url_pr . $url_limits . $url_search;
            $ajax_url           = $url_where2go_clean . $url_pr . $url_limits . $url_search;
            $namefinal          = $this->language->get('clearance_text');
            $see_more_url       = false;
            $data_filtering     = array(
                'href' => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
                'ajax_url' => $ajax_url,
                'see_more_text' => $see_more_text,
                'remove_filter_text' => $remove_filter_text,
                'name' => html_entity_decode($namefinal),
                'see_more_url' => $see_more_url
            );
            $html               = '';
            $html .= $this->model_module_supercategorymenuadvanced->GetHtmlSelected($data_filtering, $data_settings, 'sc');
            $filtros_seleccionados['SC_A'] = array(
                'id' => $filter_clearance,
                'Tipo' => 'sc',
                'name' => html_entity_decode($namefinal),
                'href' => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
                'ajax_url' => $ajax_url,
                'url_filter' => $urlfilter,
                'see_more' => $see_more_url,
                'dnd' => html_entity_decode($this->language->get('stock_text')),
                'html' => $html,
                'tip_code' => ''
            );
        } //isset($filter_clearance) && !empty($filter_clearance)
		
		
        if ($search) {
            $filter_url = $this->url->link('product/asearch', $url_where2go) . $url_pr . $url_limits;
            //$filter_url = $this->url->link('product/asearch').$url_where2go . $url_pr . $url_limits;
            $ajax_url   = $url_where2go . $url_pr . $url_limits . $url_search;
            $html       = '';
            $html .= "<ul>";
            $html .= "<li class=\"active\"><em>&nbsp;</em> <a class=\"link_filter_del smenu {dnd:'" . $filter_url . "', ajaxurl:'" . $ajax_url . "', gapush:'no'}\" href=\"javascript:void(0)\"  " . $nofollow . "><img src=\"image/advancedmenu/spacer.gif\" alt=\"" . $remove_filter_text . "\" class=\"filter_del\" /></a> <span>" . $search . " </span></li>";
            $html .= "</ul>";
            $filtros_seleccionados['search_1'] = array(
                'id' => "SEARCH",
                'Tipo' => "Search",
                'name' => html_entity_decode($search),
                'href' => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
                'ajax_url' => $ajax_url,
                'see_more' => false,
                'dnd' => $this->language->get('search_filter_text'),
                'image' => '',
                'tip_code' => '',
                'html' => $html
            );
        } //$search
		
		
        if (isset($filter_price_range) and !empty($filter_price_range)) {
            $filter_url = $this->url->link('product/asearch', $url_where2go) . $url_limits . $url_search;
            $ajax_url   = $url_where2go . $url_limits . $url_search;
            list($filter_min_price, $filter_max_price) = explode(";", $filter_price_range);
            $filter_min_price = floor($this->model_module_supercategorymenuadvanced->UnformatMoney($filter_min_price, $filter_coin));
            $filter_max_price = ceil($this->model_module_supercategorymenuadvanced->UnformatMoney($filter_max_price, $filter_coin));
            if ($this->config->get('config_tax') && $settings_module['pricerange']['setvat']) {
                $tax_value        = $this->tax->calculate(1, $settings_module['pricerange']['tax_class_id'], $this->config->get('config_tax'));
                $filter_min_price = floor($filter_min_price / $tax_value);
                $filter_max_price = ceil($filter_max_price / $tax_value);
            } //$this->config->get('config_tax') && $settings_module['pricerange']['setvat']
            $filtros_seleccionados[utf8_strtoupper("PR_PRICERANGE_1")] = array(
                'Tipo' => "pr",
                'href' => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
                'ajax_url' => $ajax_url,
                'dnd' => $this->language->get('pricerange_text'),
                'name' => $this->language->get('pricerange_text'),
                'tip_code' => '',
                'html' => "do_it_later",
                'initval' => 'opened'
            );
        } //isset($filter_price_range) and !empty($filter_price_range)
		
        $data['values_selected'] = $filtros_seleccionados;
        $data_filter             = array(
            'filter_manufacturers_by_id' => $filter_manufacturer_id,
            'filter_category_id' => $filter_category_id,
            'filter_min_price' => $filter_min_price,
            'filter_max_price' => $filter_max_price,
            'filter_stock_id' => $filter_stock_id,
            'filter_by_name' => substr($filter_by_name, 0, -3),
            'filter_ids' => substr($filter_ids, 0, -1),
            'filter_name' => $search,
            'filter_tag' => $tag,
            'filter_description' => $description,
            'filter_sub_category' => $sub_category,
            'filter_stock' => $filter_stock,
            'filter_special' => $filter_special,
            'filter_clearance' => $filter_clearance,
            'filter_arrivals' => $filter_arrivals,
            'filter_width' => $filter_width,
            'filter_height' => $filter_height,
            'filter_length' => $filter_length,
            'filter_model' => $filter_model,
            'filter_sku' => $filter_sku,
            'filter_upc' => $filter_upc,
            'filter_location' => $filter_location,
            'filter_option_id' => $filter_option_id,
            'filter_attribute_id' => $filter_attribute_id,
            'filter_productinfo_id' => $filter_productinfo_id,
            'filter_options_by_ids' => $filter_options_by_ids,
            'filter_weight' => $filter_weight,
            'filter_ean' => $filter_ean,
            'filter_isbn' => $filter_isbn,
            'filter_mpn' => $filter_mpn,
            'filter_jan' => $filter_jan,
            'filter_rating' => $filter_rating
        );
		

		//echo '----------- nothing Run ------------ ';
		//echo '<br>';
		
		
        if ($this->model_module_supercategorymenuadvanced->isCachedMenu($data_filter, $what, $this->session->data['currency'])) {
			//echo 'Cache manu';
			
			$data['html'] = $this->model_module_supercategorymenuadvanced->isCachedMenu($data_filter, $what, $this->session->data['currency']);
            
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/advancedmenu/supercategorymenuadvanced_cache.tpl')) {
                if (!isset($this->request->get['a'])) {
                    return $this->load->view($this->config->get('config_template') . '/template/module/advancedmenu/supercategorymenuadvanced_cache.tpl', $data);
                } //!isset($this->request->get['a'])
                else {
                    return $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/module/advancedmenu/supercategorymenuadvanced_cache.tpl', $data));
                }
            } //file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/advancedmenu/supercategorymenuadvanced_cache.tpl')
            else {
                if (!isset($this->request->get['a'])) {
                    return $this->load->view('default/template/module/advancedmenu/supercategorymenuadvanced_cache.tpl', $data);
                } //!isset($this->request->get['a'])
                else {
                    return $this->response->setOutput($this->load->view('default/template/module/advancedmenu/supercategorymenuadvanced_cache.tpl', $data));
                }
            }
        } //$this->model_module_supercategorymenuadvanced->isCachedMenu($data_filter, $what, $this->session->data['currency'])
        else {

            $productos_filtrados = $this->model_module_supercategorymenuadvanced->getProductsFiltered($data_filter, $data_settings, $what);
            
            $total_productos     = count($productos_filtrados);
            if (!empty($productos_filtrados)) {
                $data['menu']           = true;
                $data['sub_categories'] = array();
                $category_selected      = false;
               

			   if ($settings_module['categories']['enable']) {
	

				(isset($settings_module['categories']['style'])) ? $category_style = "_" . $settings_module['categories']['style'] : $category_style = '';
                    $subsubcategories           = array();
                    $subsubcategories_ord       = array();
                    $subsubcategories_slice_ord = array();
                    $results                    = $this->model_module_supercategorymenuadvanced->getCategoriesFiltered($productos_filtrados, $data_filter, $what);
              
					
					if (!empty($this->request->get['path'])) {
                        $previo_path        = $this->request->get['path'] . '_';
                        $url_where2go_clean = str_replace("&path=" . $this->request->get['path'], "", $url_where2go);
                    } //!empty($this->request->get['path'])
                    else {
                        $previo_path        = '';
                        $url_where2go_clean = str_replace("&path=" . $filter_category_id, "", $url_where2go);
                    }
                    if (!empty($results)) {
                        foreach ($results as $result) {
                            if ($settings_module['categories']['reset']) {
                                $url      = $this->url->link('product/asearch', 'path=' . $previo_path . $result['category_id'] . '&filtering=' . $what) . $url_pr . $url_limits . $url_search;
                                $url_ajax = 'path=' . $previo_path . $result['category_id'] . $url_pr . $url_limits . $url_search . '&filtering=' . $what;
                            } //$settings_module['categories']['reset']
                            else {
                                if ($what == "M") {
                                    $url      = $this->url->link('product/asearch', 'path=' . $previo_path . $result['category_id'] . '&' . $url_where2go_brands . $url_where2go_clean) . $url_pr . $url_limits . $url_search;
                                    $url_ajax = $url_where2go_brands . $url_where2go . '&amp;path=' . $previo_path . $result['category_id'] . $url_pr . $url_limits . $url_search;
                                } //$what == "M"
                                else {
                                    $url      = $this->url->link('product/asearch', 'path=' . $previo_path . $result['category_id'] . $url_where2go_clean) . $url_pr . $url_limits . $url_search;
                                    $url_ajax = 'path=' . $previo_path . $result['category_id'] . $url_where2go_clean . $url_pr . $url_limits . $url_search;
                                }
                            }
                            $subsubcategories["str" . $result['name']] = array(
                                'category_id' => $result['category_id'],
                                'name' => $result['name'],
                                'href' => $this->model_module_supercategorymenuadvanced->CleanSlider($url),
                                'ajax_url' => $url_ajax,
                                'total' => $result['total'],
                                'order' => $result['order'],
                                'tip_code' => ''
                            );
                        } //$results as $result
                        $subsubcategories_slice = array();
                        if (count($subsubcategories) > $settings_module['categories']['list_number']) {
                            $sort_order              = array();
                            $subsubcategories_2slice = $subsubcategories;
                            $subsubcategories_slice  = array();
                            foreach ($subsubcategories_2slice as $key => $value) {
                                $sort_order[$key] = $value['total'];
                            } //$subsubcategories_2slice as $key => $value
                            array_multisort($sort_order, SORT_DESC, $subsubcategories_2slice);
                            $subsubcategories_slice = array_slice($subsubcategories_2slice, 0, $settings_module['categories']['list_number']);
                        } //count($subsubcategories) > $settings_module['categories']['list_number']
                        $subsubcategories_ord       = array_values($this->model_module_supercategorymenuadvanced->OrderArray($subsubcategories, $settings_module['categories']['order']));
                        $subsubcategories_slice_ord = array_values($this->model_module_supercategorymenuadvanced->OrderArray($subsubcategories_slice, $settings_module['categories']['order']));
                        $subcategories[]            = array(
                            'name' => $category_text,
                            'total' => count($subsubcategories),
                            'jurjur' => $subsubcategories_ord,
                            'slice' => $subsubcategories_slice_ord,
                            'Tipo' => "CATEGORY",
                            'list_number' => $settings_module['categories']['list_number'],
                            'order' => $settings_module['categories']['order'],
                            'sort_order' => $settings_module['categories']['super_order'],
                            'view' => $settings_module['categories']['view'],
                            'initval' => $settings_module['categories']['initval'],
                            'tip_code' => ''
                        );
                    } //!empty($results)
                    $html = '';
                    if (!empty($this->request->get['path']) and $this->request->get['path'] != "") {
                        $paths          = explode("_", urldecode($this->request->get['path']));
                        $categories_nav = array();
                        $w              = 0;
                        foreach ($paths as $path) {
                            $arr = array_slice($paths, 0, $w);
                            $w == 0 ? $path_links = 0 : $path_links = implode("_", $arr);
                            if ($path != 0 && $this->model_module_supercategorymenuadvanced->isNotHiddenCategory($path)) {
                                if ($settings_module['categories']['reset']) {
                                    $url      = $this->url->link('product/asearch', 'path=' . $path_links . '&filtering=' . $what);
                                    $url_ajax = 'path=' . $path_links . '&filtering=' . $what;
                                } //$settings_module['categories']['reset']
                                else {
                                    if ($what == "M") {
                                        $url      = $this->url->link('product/asearch', 'path=' . $path_links . $url_where2go_clean . '&' . $url_where2go_brands) . $url_pr . $url_limits . $url_search;
                                        $url_ajax = 'path=' . $path_links . '&amp;' . $url_where2go_brands . $url_where2go_clean . $url_pr . $url_limits . $url_search;
                                    } //$what == "M"
                                    else {
                                        $url      = $this->url->link('product/asearch', 'path=' . $path_links . $url_where2go_clean) . $url_pr . $url_limits . $url_search;
                                        $url_ajax = 'path=' . $path_links . $url_where2go_clean . $url_pr . $url_limits . $url_search;
                                    }
                                }
                                $categories_nav[] = array(
                                    'href' => $this->model_module_supercategorymenuadvanced->CleanSlider($url),
                                    'name' => $this->model_module_supercategorymenuadvanced->getCategoryName($path),
                                    'ajax_url' => $url_ajax
                                );
                                $w++;
                            } //$path != 0
                        } //$paths as $path
                        $html = '';
                        if ($categories_nav && !empty($categories_nav)) {
                            $html .= "<ul>";
                            foreach ($categories_nav as $values_selected_category) {
                                $html .= "<li class=\"active" . $category_style . "\" ><em>&nbsp;</em><a class=\"link_filter_del smenu {dnd:'" . $values_selected_category['href'] . "',ajaxurl:'" . $values_selected_category['ajax_url'] . "',gapush:'no'}\" href=\"javascript:void(0)\" " . $nofollow . " ><img src=\"image/advancedmenu/spacer.gif\" alt=\"" . $remove_filter_text . "\" class=\"filter_del\" /></a><span>" . $values_selected_category['name'] . " </span></li>";
                            } //$categories_nav as $values_selected_category
                            $html .= "</ul>";
                        } //$categories_nav && !empty($categories_nav)
                        $category_selected = true;
                    } //!empty($this->request->get['path']) and $this->request->get['path'] != ""
                    $view        = $settings_module['categories']['view'];
                    $searchinput = $settings_module['categories']['searchinput'];
                    $html2       = '<dd class="page_preload">';
                    $html2       = '';
                    if (count($subsubcategories) == 1 && $subcategories[0]['total'] == $total_productos) {
                        $html2 .= $this->model_module_supercategorymenuadvanced->getOneHtml($subsubcategories_ord[0]['name'], $subsubcategories_ord[0]['total'], $data_settings);
                    } //count($subsubcategories) == 1 && $subcategories[0]['total'] == $total_productos
                    elseif ($view == "sele") {
                        $html2 .= $this->model_module_supercategorymenuadvanced->getSelectHtml($subsubcategories_ord, $data_settings, $category_text);
                    } //$view == "sele"
                        elseif ($view == "list") {
                        $html2 .= $this->model_module_supercategorymenuadvanced->getListHtml($subsubcategories_ord, $subsubcategories_slice_ord, $data_settings, $category_text, $idx, $searchinput);
                        $idx++;
                    } //$view == "list"
                   
					$data['all_values'][$settings_module['categories']['super_order']][] = array(
                        'html' => $html . $html2,
                        'name' => $category_text,
                        'initval' => $settings_module['categories']['initval'],
                        'dnd' => $category_text,
                        'tip_code' => ''
                    );
					if (!empty($subcategories)) {
                            $data['subcategories_not_selected']["CATEGORY"] = array(
                                'html' => $html2,
                                'name' => $category_text,
                                'dnd' => $category_text,
                                'tip_code' => ''
                            );
                        } //!empty($subcategories)
						
						
                    if ($category_selected) {
						
                        $data['values_selected']["CATEGORY"] = array(
                            'html' => $html . '</dd><dd class="page_preload">' . $html2,
                            'name' => $category_text,
                            'initval' => $settings_module['categories']['initval'],
                            'dnd' => $category_text,
                            'tip_code' => ''
                        );
                    } //$category_selected
                    elseif($filter_category_id==0){
						
							$data['values_selected']["CATEGORY"] = array(
                                'html' => $html2,
                                'name' => $category_text,
                                'dnd' => $category_text,
                                'tip_code' => ''
                            );
               
					}	
					else{
                        if (!empty($subcategories)) {

							$data['values_no_selected'][$settings_module['categories']['super_order']][] = array(
                                'html' => $html2,
                                'name' => $category_text,
                                'dnd' => $category_text,
                                'tip_code' => ''
                            );
                        } //!empty($subcategories)
                    }
					
				
                } //$settings_module['categories']['enable']
			
				
                
				
                if (($this->config->get('config_customer_price') && $this->customer->isLogged() && $settings_module['pricerange']['enable'] && !$modecatalog) || !$this->config->get('config_customer_price') && $settings_module['pricerange']['enable'] && !$modecatalog) {
                    $price_range_init         = $settings_module['pricerange']['initval'];
                    $SymbolLeft               = $this->currency->getSymbolLeft();
                    $SymbolRight              = $this->currency->getSymbolRight();
                    $txt_price_rage_selected2 = '';
                    if (isset($this->request->get['pr'])) {
                        list($min_price, $max_price) = explode(";", $this->request->get['pr']);
                        $txt_price_rage_selected2 = $SymbolLeft . " " . $this->model_module_supercategorymenuadvanced->formatCurrency($min_price) . " " . $SymbolRight . " - " . $SymbolLeft . " " . $this->model_module_supercategorymenuadvanced->formatCurrency($max_price) . " " . $SymbolRight;
                    } //isset($this->request->get['pr'])

                    if (isset($this->request->get['PRICERANGE'])) {
                        list($min_price, $max_price) = explode(";", $this->request->get['PRICERANGE']);
                        $txt_price_rage_selected2 = $SymbolLeft . " " . $this->model_module_supercategorymenuadvanced->formatCurrency($min_price) . " " . $SymbolRight . " - " . $SymbolLeft . " " . $this->model_module_supercategorymenuadvanced->formatCurrency($max_price) . " " . $SymbolRight;
                    } //isset($this->request->get['pr'])
                    $prices_min_max = $this->model_module_supercategorymenuadvanced->getProductsPriceandSpecial($productos_filtrados, $data_filter, $what);
                    $max_price      = $prices_min_max['PriceMax'];
                    $min_price      = $prices_min_max['PriceMin'];
                    if ($this->config->get('config_tax') && $settings_module['pricerange']['setvat']) {
                        $max_price = $this->tax->calculate($max_price, $settings_module['pricerange']['tax_class_id'], $this->config->get('config_tax'));
                        $min_price = $this->tax->calculate($min_price, $settings_module['pricerange']['tax_class_id'], $this->config->get('config_tax'));
                    } //$this->config->get('config_tax') && $settings_module['pricerange']['setvat']
                    $max_price               = ceil($this->model_module_supercategorymenuadvanced->formatMoney($max_price));
                    $min_price               = floor($this->model_module_supercategorymenuadvanced->formatMoney($min_price));
                    $currency                = $filter_coin;
                    $txt_price_rage_selected = $SymbolLeft . " " . $this->model_module_supercategorymenuadvanced->formatCurrency($min_price) . " " . $SymbolRight . " - " . $SymbolLeft . " " . $this->model_module_supercategorymenuadvanced->formatCurrency($max_price) . " " . $SymbolRight;
                    $view                    = $settings_module['pricerange']['view'];
                    $data['intivalprice']    = $settings_module['pricerange']['initval'];
                    if (($view == "select") || ($view == "list")) {
                        $number_ranges       = 5;
                        $array_prices_values = $this->model_module_supercategorymenuadvanced->getRanges($min_price, $max_price, $number_ranges, $prices_min_max, $productos_filtrados, $currency, $settings_module['pricerange']['setvat'], $settings_module['pricerange']['tax_class_id'], $id, $what);
                    } //($view == "select") || ($view == "list")
                    if ($view == "smart") {
                        $this->load->model('setting/setting');
                        $r = $this->model_setting_setting->getSetting('smartprice');
                        if (!empty($r['smartprice'])) {
                            $ranges = $r['smartprice'];
                            $number_ranges       = 6;
                            $array_prices_values = $this->model_module_supercategorymenuadvanced->getSmartRanges($min_price, $max_price, $number_ranges, $prices_min_max, $productos_filtrados, $currency, $settings_module['pricerange']['setvat'], $settings_module['pricerange']['tax_class_id'], $id, $what,$ranges);
                        }else{
                            $number_ranges       = 6;
                            $array_prices_values = $this->model_module_supercategorymenuadvanced->getRanges($min_price, $max_price, $number_ranges, $prices_min_max, $productos_filtrados, $currency, $settings_module['pricerange']['setvat'], $settings_module['pricerange']['tax_class_id'], $id, $what);
                        }
                    }
                    $PriceUrl     = $this->url->link('product/asearch', $url_where2go) . $url_search . $url_limits;
                    $PriceAjaxUrl = $url_where2go . $url_search . $url_limits;
                    $html         = '';
                    if (($max_price == $min_price) || ($max_price - $min_price <= 1)) {
                        if ($filter_price_range) {
                            $html .= "<ul>";
                            $html .= "<li  class=\"active\"><em>&nbsp;</em> <a class=\"link_filter_del smenu {dnd:'" . $PriceUrl . "', ajaxurl:'" . $PriceAjaxUrl . "', gapush:'no'}\" href=\"javascript:void(0)\"  " . $nofollow . "><img src=\"image/advancedmenu/spacer.gif\" alt=\"" . $remove_filter_text . "\" class=\"filter_del\" /></a> <span>" . $txt_price_rage_selected . "</span></li>";
                            $html .= "</ul>";
                            $data['values_selected'][utf8_strtoupper("PR_PRICERANGE_1")]['html'] = $html;
                        } //$filter_price_range
                        else {
                            $html .= $this->model_module_supercategorymenuadvanced->getOneHtml($txt_price_rage_selected, $total_productos, $data_settings);
                        }
                    } //($max_price == $min_price) || ($max_price - $min_price <= 1)
                    elseif ($view == "select") {
                        $html .= "<ul>";
                        if (count($array_prices_values) > 1) {
                            $html .= "<select class=\"smenu\" style=\"width: 160px; margin-left:5px;\">";
                            $html .= "<option value=\"0\" selected=\"selected\">- Select " . $pricerange_text . " -</option>";
                            foreach ($array_prices_values as $array_price_value) {
                                ($count_products) ? $count = "&nbsp;(" . $array_price_value['total'] . ")" : $count = "";
                                if ($array_price_value['total'] > 0) {
                                    $html .= "<option class=\"smenu {dnd:'" . $PriceUrl . "&amp;C=" . $filter_coin . "&amp;pr=" . $array_price_value['intMin'] . ";" . $array_price_value['intMax'] . "', ajaxurl:'" . $PriceAjaxUrl . "&amp;C=" . $filter_coin . "&amp;PRICERANGE=" . $array_price_value['intMin'] . ";" . $array_price_value['intMax'] . "', gapush:'no'}\">" . sprintf($array_price_value['prices'], $SymbolLeft, $SymbolRight, $SymbolLeft, $SymbolRight) . " " . $count . "</option>";
                                } //$array_price_value['total'] > 0
                            } //$array_prices_values as $array_price_value
                            $html .= "</select>";
                            if ($filter_price_range) {
                                $data['values_selected'][utf8_strtoupper("PR_PRICERANGE_1")]['html'] = $html;
                            } //$filter_price_range
                        } //count($array_prices_values) > 1
                        else {
                            ($count_products) ? $count = " <span class=\"product-count\">(" . $array_prices_values[0]['total'] . ")</span>" : $count = "";
                            $html .= "<span class=\"seleccionado\"> <em>&nbsp;</em>" . sprintf($array_prices_values[0]['prices'], $SymbolLeft, $SymbolRight) . "&nbsp;" . $count . "</span>";
                        }
                        $html .= "</ul>";
                        if ($filter_price_range) {
                            $data['values_selected'][utf8_strtoupper("PR_PRICERANGE_1")]['html'] = $html;
                        } //$filter_price_range
                    } //$view == "select"
                        elseif ($view == "list") {
                        if ($filter_price_range) {
                            $html .= "<ul>";
                            $html .= "<li   class=\"active\"><em>&nbsp;</em> <a class=\"link_filter_del smenu {dnd:'" . $PriceUrl . "', ajaxurl:'" . $PriceAjaxUrl . "', gapush:'no'}\" href=\"javascript:void(0)\"  " . $nofollow . "><img src=\"image/advancedmenu/spacer.gif\" alt=\"" . $remove_filter_text . "\" class=\"filter_del\" /></a> <span>" . $txt_price_rage_selected2 . "</span></li>";
                            $html .= "</ul>";
                            $html .= "</dd>";
                            $html .= "<dd>";
                        } //$filter_price_range
                        $html .= "<ul>";
                        if (count($array_prices_values) > 1) {
                            foreach ($array_prices_values as $array_price_value) {
                                ($count_products) ? $count = " <span class=\"product-count\">(" . $array_price_value['total'] . ")</span>" : $count = "";
                                if ($array_price_value['total'] > 0) {
                                    $html .= "<li> <em>&nbsp;</em><a   class=\"smenu {dnd: '" . $PriceUrl . "&amp;C=" . $filter_coin . "&amp;pr=" . $array_price_value['intMin'] . ";" . $array_price_value['intMax'] . "', ajaxurl:'" . $PriceAjaxUrl . "&amp;C=" . $filter_coin . "&amp;PRICERANGE=" . $array_price_value['intMin'] . ";" . $array_price_value['intMax'] . "', gapush:'no'}\" href=\"javascript:void(0)\" " . $nofollow . ">" . sprintf($array_price_value['prices'], $SymbolLeft, $SymbolRight, $SymbolLeft, $SymbolRight) . " </a>" . $count . "</li>";
                                } //$array_price_value['total'] > 0
                            } //$array_prices_values as $array_price_value
                        } //count($array_prices_values) > 1
                        else {
                            ($count_products) ? $count = " <span class=\"product-count\">(" . $array_prices_values[0]['total'] . ")</span>" : $count = "";
                            $html .= "<span class=\"seleccionado\"> <em>&nbsp;</em>" . sprintf($array_prices_values[0]['prices'], $SymbolLeft, $SymbolRight) . "&nbsp;" . $count . "</span>";
                        }
                        $html .= "</ul>";
                        if ($filter_price_range) {
                            $data['values_selected'][utf8_strtoupper("PR_PRICERANGE_1")]['html'] = $html;
                        } //$filter_price_range
                    } //$view == "list"
                        elseif ($view == "slider") {
                        $price_diff  = $max_price - $min_price;
                        $half_value  = round(($min_price + $max_price) / 2, 0);
                        $half_value1 = round(($min_price + $half_value) / 2, 0);
                        $half_value2 = round(($max_price + $half_value) / 2, 0);
                        $scale_price = "[{$min_price}, '|', {$half_value}, '|', {$max_price}]";
                        if ($filter_price_range) {
                            $ajax_url_del                                       = $data['values_selected']['PR_PRICERANGE_1']['ajax_url'];
                            $url_del                                            = $data['values_selected']['PR_PRICERANGE_1']['href'];
                            $html                                               = $this->model_module_supercategorymenuadvanced->getPriceSliderHtmlSelected("slider_price_range", $scale_price, $min_price, $max_price, $SymbolLeft, $SymbolRight, "pr", $is_ajax, $PriceAjaxUrl, $PriceUrl, $currency, $ajax_url_del, $url_del);
                            $data['values_selected']['PR_PRICERANGE_1']['html'] = $html;
                        } //$filter_price_range
                        else {
                            $html = $this->model_module_supercategorymenuadvanced->getPriceSliderHtml("slider_price_range", $scale_price, $min_price, $max_price, $SymbolLeft, $SymbolRight, "pr", $is_ajax, $PriceAjaxUrl, $PriceUrl, $currency);
                        }
                    } //$view == "slider"
                        elseif ($view == "smart") {
                            if ($filter_price_range) {
                            $html .= "<ul>";
                            $html .= "<li   class=\"active\"><em>&nbsp;</em> <a class=\"link_filter_del smenu {dnd:'" . $PriceUrl . "', ajaxurl:'" . $PriceAjaxUrl . "', gapush:'no'}\" href=\"javascript:void(0)\"  " . $nofollow . "><img src=\"image/advancedmenu/spacer.gif\" alt=\"" . $remove_filter_text . "\" class=\"filter_del\" /></a> <span>" . $txt_price_rage_selected2 . "</span></li>";
                            $html .= "</ul>";
                            $html .= "</dd>";
                            $html .= "<dd>";
                        } //$filter_price_range
                        $html .= "<ul>";
                        if (count($array_prices_values) > 1) {
                            foreach ($array_prices_values as $array_price_value) {
                                ($count_products) ? $count = " <span class=\"product-count\">(" . $array_price_value['total'] . ")</span>" : $count = "";
                                if ($array_price_value['total'] > 0) {
                                    $html .= "<li> <em>&nbsp;</em><a   class=\"smenu {dnd: '" . $PriceUrl . "&amp;C=" . $filter_coin . "&amp;pr=" . $array_price_value['intMin'] . ";" . $array_price_value['intMax'] . "', ajaxurl:'" . $PriceAjaxUrl . "&amp;C=" . $filter_coin . "&amp;PRICERANGE=" . $array_price_value['intMin'] . ";" . $array_price_value['intMax'] . "', gapush:'no'}\" href=\"javascript:void(0)\" " . $nofollow . ">" . sprintf($array_price_value['prices'], $SymbolLeft, $SymbolRight, $SymbolLeft, $SymbolRight) . " </a>" . $count . "</li>";
                                } //$array_price_value['total'] > 0
                            } //$array_prices_values as $array_price_value
                        } //count($array_prices_values) > 1
                        else {
                            ($count_products) ? $count = " <span class=\"product-count\">(" . $array_prices_values[0]['total'] . ")</span>" : $count = "";
                            $html .= "<span class=\"seleccionado\"> <em>&nbsp;</em>" . sprintf($array_prices_values[0]['prices'], $SymbolLeft, $SymbolRight) . "&nbsp;" . $count . "</span>";
                        }
                        $html .= "</ul>";
                        if ($filter_price_range) {
                            $data['values_selected'][utf8_strtoupper("PR_PRICERANGE_1")]['html'] = $html;
                        } //$filter_price_range
                    } //$view == "smart"
                    $is_selected = 0;
                    if ($filter_price_range) {
                        $is_selected = 1;
                    } //$filter_price_range
                    $pricerange["PRICERANGE"] = array(
                        'name' => $pricerange_text,
                        'total' => 10000,
                        'html' => $html,
                        'jurjur' => '',
                        'slice' => '',
                        'list_number' => 10000,
                        'order' => '',
                        'view' => $settings_module['pricerange']['view'],
                        'initval' => $settings_module['pricerange']['initval'],
                        'searchinput' => '',
                        'dnd' => $pricerange_text,
                        'tip_code' => '',
                        'is_selected' => $is_selected
                    );
                    $data['price_range']      = $pricerange;
                    if ($filter_price_range) {
                        $data['all_values'][$settings_module['pricerange']['super_order']]['PRICERANGE'] = $data['values_selected']['PR_PRICERANGE_1'];
                    } //$filter_price_range
                    else {
                        $data['all_values'][$settings_module['pricerange']['super_order']]         = $pricerange;
                        $data['values_no_selected'][$settings_module['pricerange']['super_order']] = $pricerange;
                    }
                } //($this->config->get('config_customer_price') && $this->customer->isLogged() && $settings_module['pricerange']['enable'] && !$modecatalog) || !$this->config->get('config_customer_price') && $settings_module['pricerange']['enable'] && !$modecatalog
                //echo "<pre>";var_dump($pricerange);echo"</pre>";die();
                if ($settings_module['manufacturer']['enable']) {
                    $array_man_selected = explode(",", $filter_manufacturers_by_id);
                    $manufactures       = array();
                    $results            = $this->model_module_supercategorymenuadvanced->getManufacturesFiltered($productos_filtrados, $data_filter, $what);
                    $url_where2go_m     = ($url_where2go == "manufacturer_id=0") ? '' : $url_where2go;
                    if (!empty($results)) {
                        foreach ($results as $result) {
                            $string_filtering = "manufacturer_id=" . $result['manufacturer_id'];
                            $filter_url       = $this->url->link('product/asearch', $string_filtering . $url_where2go_m) . $url_pr . $url_limits . $url_search;
                            $ajax_url         = $string_filtering . $url_where2go_m . $url_pr . $url_limits . $url_search;
                            $is_selected      = 0;
                            if ($result['manufacturer_id'] == $filter_manufacturer_id) {
                                $is_selected = 1;
                            } //$result['manufacturer_id'] == $filter_manufacturer_id
                            $manufactures_final["str" . $result['name']] = array(
                                'manufacturer_id' => $result['manufacturer_id'],
                                'name' => $result['name'],
                                'total' => $result['total'],
                                'href' => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
                                'ajax_url' => $ajax_url,
                                'tipo' => "MANUFACTURER",
                                'order' => $result['order'],
                                'is_selected' => $is_selected
                            );
                        } //$results as $result
                        $manufactures_slice = array();
                        if (count($manufactures_final) > $settings_module['manufacturer']['list_number']) {
                            $sort_order          = array();
                            $manufactures_2slice = $manufactures_final;
                            foreach ($manufactures_2slice as $key => $value) {
                                $sort_order[$key] = $value['total'];
                            } //$manufactures_2slice as $key => $value
                            array_multisort($sort_order, SORT_DESC, $manufactures_2slice);
                            $manufactures_slice = array_slice($manufactures_2slice, 0, $settings_module['manufacturer']['list_number']);
                        } //count($manufactures_final) > $settings_module['manufacturer']['list_number']
                        $manufactures_ord       = array_values($this->model_module_supercategorymenuadvanced->OrderArray($manufactures_final, $settings_module['manufacturer']['order']));
                        $manufactures_slice_ord = array_values($this->model_module_supercategorymenuadvanced->OrderArray($manufactures_slice, $settings_module['manufacturer']['order']));
                        $total                  = count($manufactures_ord);
                        $list_number            = $settings_module['manufacturer']['list_number'];
                        $view                   = $settings_module['manufacturer']['view'];
                        $searchinput            = $settings_module['manufacturer']['searchinput'];
                        $is_selected            = 0;
                        if ($result['manufacturer_id'] == $filter_manufacturer_id) {
                            $html        = $filtros_seleccionados['M_' . $result['manufacturer_id']]['html'];
                            $is_selected = 1;
                        } //$result['manufacturer_id'] == $filter_manufacturer_id
                        else {
                            if ($total == 1 && $manufactures_ord[0]['total'] == $total_productos) {
                                $html = $this->model_module_supercategorymenuadvanced->getOneHtml($manufactures_ord[0]['name'], $manufactures_ord[0]['total'], $data_settings);
                            } //$total == 1 && $manufactures_ord[0]['total'] == $total_productos
                            elseif ($view == "sele") {
                                $html = $this->model_module_supercategorymenuadvanced->getSelectHtml($manufactures_ord, $data_settings, $manufacturer_text);
                            } //$view == "sele"
                                elseif ($view == "image") {
                                $html = $this->model_module_supercategorymenuadvanced->getImageHtml($data_settings);
                            } //$view == "image"
                                elseif ($view == "list") {
                                $html = $this->model_module_supercategorymenuadvanced->getListHtml($manufactures_ord, $manufactures_slice_ord, $data_settings, $manufacturer_text, $idx, $searchinput);
                                $idx++;
                            } //$view == "list"
                        }
                        $manufactures[]        = array(
                            'name' => $manufacturer_text,
                            'total' => count($manufactures_final),
                            'html' => $html,
                            'jurjur' => $manufactures_ord,
                            'slice' => $manufactures_slice_ord,
                            'list_number' => $settings_module['manufacturer']['list_number'],
                            'order' => $settings_module['manufacturer']['order'],
                            'view' => $settings_module['manufacturer']['view'],
                            'initval' => $settings_module['manufacturer']['initval'],
                            'searchinput' => $settings_module['manufacturer']['searchinput'],
                            'is_selected' => $is_selected,
                            'tip_code' => ''
                        );
                        $data['manufacturers'] = $manufactures;
                        if (!$data_filter['filter_manufacturers_by_id']) {
                            $data['values_no_selected'][$settings_module['manufacturer']['super_order']] = $manufactures;
                        } //!$data_filter['filter_manufacturers_by_id']
                        $data['all_values'][$settings_module['manufacturer']['super_order']] = $manufactures;
                    } //!empty($results)
                } //$settings_module['manufacturer']['enable']
				
                $reviews_final = array();
                $html          = '';
                if ($settings_module['reviews']['enable']) {
                    $results = $this->model_module_supercategorymenuadvanced->getReviewsFiltered($productos_filtrados, $data_filter, $settings_module['reviews']['tipo'], $what);
                    if (!empty($results)) {
                        foreach ($results as $result) {
                            $string_filtering = "filter_rating=" . $result['rating'];
                            $filter_url       = $this->url->link('product/asearch', $string_filtering . $url_where2go) . $url_pr . $url_limits . $url_search;
                            $ajax_url         = $string_filtering . $url_where2go . $url_pr . $url_limits . $url_search;
                            $is_selected      = 0;
                            if ($result['rating'] == $filter_rating) {
                                $is_selected = 1;
                            } //$result['rating'] == $filter_rating
                            $reviews_final["str" . $result['rating']] = array(
                                'reviews_id' => $result['rating'],
                                'name' => $result['rating'],
                                'total' => $result['total'],
                                'href' => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
                                'ajax_url' => $ajax_url,
                                'tipo' => "review",
                                'order' => $result['rating'],
                                'is_selected' => $is_selected
                            );
                        } //$results as $result
                        $total         = count($reviews_final);
                        $list_number   = 1000000000;
                        $view          = 'image';
                        $searchinput   = '';
                        $is_selected   = 0;
                        $reviews_final = array_values($reviews_final);
                        if ($result['rating'] == $filter_rating) {
                            $html        = $filtros_seleccionados['RA_A']['html'];
                            $is_selected = 1;
                        } //$result['rating'] == $filter_rating
                        else {
                            if ($total == 1 && $reviews_final[0]['total'] == $total_productos) {
                                $html = $this->model_module_supercategorymenuadvanced->getOneHtmlReview($reviews_final[0]['name'], $reviews_final[0]['total'], $data_settings, $this->language->get('rating_text_' . $settings_module['reviews']['tipo']));
                            } //$total == 1 && $reviews_final[0]['total'] == $total_productos
                            elseif ($view == "sele") {
                            } //$view == "sele"
                                elseif ($view == "image") {
                                $html = $this->model_module_supercategorymenuadvanced->getImageHtmlReview($reviews_final, $data_settings, $rating_text, $this->language->get('rating_text_' . $settings_module['reviews']['tipo']));
                            } //$view == "image"
                                elseif ($view == "list") {
                                $idx++;
                            } //$view == "list"
                        }
                        $reviews[]       = array(
                            'name' => $rating_text,
                            'total' => count($reviews_final),
                            'html' => $html,
                            'jurjur' => $reviews_final,
                            'slice' => '',
                            'list_number' => 10000000000000000000000,
                            'order' => '',
                            'view' => $view,
                            'initval' => $settings_module['reviews']['initval'],
                            'searchinput' => '',
                            'is_selected' => $is_selected,
                            'tip_code' => ''
                        );
                        $data['reviews'] = $reviews;
                        if (!$data_filter['filter_rating']) {
                            $data['values_no_selected'][$settings_module['reviews']['super_order']] = $reviews;
                        } //!$data_filter['filter_rating']
                        $data['all_values'][$settings_module['reviews']['super_order']] = $reviews;
                    } //!empty($results)
                } //$settings_module['reviews']['enable']
                $stockstatuses_final = array();
                $html                = '';
                if ($settings_module['stock']['enable']) {
                    $results          = $this->model_module_supercategorymenuadvanced->getStocksInStock($productos_filtrados, $data_filter, $what);
                    $string_filtering = "&filter_stock=yes";
                    $filter_url       = $this->url->link('product/asearch', $url_where2go . $string_filtering) . $url_pr . $url_limits . $url_search;
                    $ajax_url         = $string_filtering . $url_where2go . $url_pr . $url_limits . $url_search;
                    $is_selected      = 0;
                    if ($filter_stock) {
                        $is_selected = 1;
                        $html .= $filtros_seleccionados['SS_A']['html'];
                    } //$filter_stock
                    if ($results != "no_stock" && $results > 0 && !$is_selected) {
                        $stockstatuses_final["no_stock"] = array(
                            'stock_id' => "stock",
                            'name' => $this->language->get('in_stock_text'),
                            'total' => $results,
                            'href' => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
                            'ajax_url' => $ajax_url,
                            'tipo' => "STOCKS",
                            'is_selected' => $is_selected
                        );
                    } //$results != "no_stock" && $results > 0 && !$is_selected
                } //$settings_module['stock']['enable']
                if ($settings_module['stock']['special']) {
                    $results          = $this->model_module_supercategorymenuadvanced->getStocksSpecial($productos_filtrados, $data_filter, $what);
                    $string_filtering = "&filter_special=yes";
                    $filter_url       = $this->url->link('product/asearch', $url_where2go . $string_filtering) . $url_pr . $url_limits . $url_search;
                    $ajax_url         = $string_filtering . $url_where2go . $url_pr . $url_limits . $url_search;
                    $is_selected      = 0;
                    if ($filter_special) {
                        $is_selected = 1;
                        $html .= $filtros_seleccionados['SP_A']['html'];
                    } //$filter_special
                    if ($results != "no_special" && $results > 0 && !$is_selected) {
                        $stockstatuses_final["strspecial"] = array(
                            'stock_id' => "special",
                            'name' => $this->language->get('special_prices_text'),
                            'total' => $results,
                            'href' => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
                            'ajax_url' => $ajax_url,
                            'tipo' => "STOCKSPECIAL",
                            'is_selected' => $is_selected
                        );
                    } //$results != "no_special" && $results > 0 && !$is_selected
                } //$settings_module['stock']['special']
                if ($settings_module['stock']['clearance']) {
                    $results          = $this->model_module_supercategorymenuadvanced->getStocksClearance($productos_filtrados, $data_filter, $settings_module['stock']['clearance_value'], $what);
                    $string_filtering = "filter_clearance=yes";
                    $filter_url       = $this->url->link('product/asearch', $string_filtering . $url_where2go) . $url_pr . $url_limits . $url_search;
                    $ajax_url         = $string_filtering . $url_where2go . $url_pr . $url_limits . $url_search;
                    $is_selected      = 0;
                    if ($filter_clearance) {
                        $is_selected = 1;
                        $html .= $filtros_seleccionados['SC_A']['html'];
                    } //$filter_clearance
                    if ($results != "no_clearance" && $results > 0 && !$is_selected) {
                        $stockstatuses_final["clearance"] = array(
                            'stock_id' => "clearance",
                            'name' => $this->language->get('clearance_text'),
                            'total' => $results,
                            'href' => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
                            'ajax_url' => $ajax_url,
                            'tipo' => "STOCKCLEARANCE",
                            'is_selected' => $is_selected
                        );
                    } //$results != "no_clearance" && $results > 0 && !$is_selected
                } //$settings_module['stock']['clearance']
                if ($settings_module['stock']['arrivals']) {
                    $results          = $this->model_module_supercategorymenuadvanced->getStocksArrivals($productos_filtrados, $data_filter, $settings_module['stock']['number_day'], $what);
                    $string_filtering = "&filter_arrivals=yes";
                    $filter_url       = $this->url->link('product/asearch', $string_filtering . $url_where2go) . $url_pr . $url_limits . $url_search;
                    $ajax_url         = $string_filtering . $url_where2go . $url_pr . $url_limits . $url_search;
                    $is_selected      = 0;
                    if ($filter_arrivals) {
                        $is_selected = 1;
                        $html .= $filtros_seleccionados['SN_A']['html'];
                    } //$filter_arrivals
                    if ($results != "no_new" && $results > 0 && !$is_selected) {
                        $stockstatuses_final["new"] = array(
                            'stock_id' => "new",
                            'name' => $this->language->get('new_products_text'),
                            'total' => $results,
                            'href' => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
                            'ajax_url' => $ajax_url,
                            'tipo' => "STOCKNEW",
                            'is_selected' => $is_selected
                        );
                    } //$results != "no_new" && $results > 0 && !$is_selected
                } //$settings_module['stock']['arrivals']
                if (!empty($stockstatuses_final)) {
                    $stockstatuses_ord = array_values($this->model_module_supercategorymenuadvanced->OrderArray($stockstatuses_final, "OTASC"));
                    $view              = $settings_module['stock']['view'];
                    $total             = count($stockstatuses_final);
                    if ($total == 1 && $stockstatuses_ord[0]['total'] == $total_productos) {
                        $html2 = $this->model_module_supercategorymenuadvanced->getOneHtml($stockstatuses_ord[0]['name'], $stockstatuses_ord[0]['total'], $data_settings);
                    } //$total == 1 && $stockstatuses_ord[0]['total'] == $total_productos
                    elseif ($view == "sele") {
                        $html2 = $this->model_module_supercategorymenuadvanced->getSelectHtml($stockstatuses_ord, $data_settings, $stock_text);
                    } //$view == "sele"
                        elseif ($view == "list") {
                        $html2 = $this->model_module_supercategorymenuadvanced->getListHtml($stockstatuses_ord, 0, $data_settings, $stock_text, $idx, "no");
                        $idx++;
                    } //$view == "list"
                    $stockstatuses_all[]                                                  = array(
                        'name' => $stock_text,
                        'total' => count($stockstatuses_final),
                        'html' => $html . $html2,
                        'jurjur' => $stockstatuses_ord,
                        'slice' => 0,
                        'list_number' => 100,
                        'order' => "OTASC",
                        'view' => $settings_module['stock']['view'],
                        'initval' => $settings_module['stock']['initval'],
                        'searchinput' => "no",
                        'tip_code' => '',
                        'is_selected' => $is_selected
                    );
                    $stockstatuses[]                                                      = array(
                        'name' => $stock_text,
                        'total' => count($stockstatuses_final),
                        'html' => $html2,
                        'jurjur' => $stockstatuses_ord,
                        'slice' => 0,
                        'list_number' => 100,
                        'order' => "OTASC",
                        'view' => $settings_module['stock']['view'],
                        'initval' => $settings_module['stock']['initval'],
                        'searchinput' => "no",
                        'tip_code' => '',
                        'is_selected' => $is_selected
                    );
                    $data['stock_statuses']                                               = $stockstatuses;
                    $data['values_no_selected'][$settings_module['stock']['super_order']] = $stockstatuses;
                    $data['all_values'][$settings_module['stock']['super_order']]         = $stockstatuses_all;
                } //!empty($stockstatuses_final)
                $opciones                 = $atributos = $productoinfos = array();
                $option_no_selected       = array();
                $attribute_no_selected    = array();
                $productinfo_no_selected  = array();
                $productinfos_in_category = isset($values_in_filter['productinfo']) ? $values_in_filter['productinfo'] : "no infos";
                if (is_array($productinfos_in_category)) {
                    foreach ($productinfos_in_category as $productinfo_in_category) {
                        $productinfo_ids[$productinfo_in_category['productinfo_id']] = $productinfo_in_category['productinfo_id'];
                    } //$productinfos_in_category as $productinfo_in_category
                    foreach ($productinfos_in_category as $key => $value) {
                        $product_in_product_info_filtered[$key] = $this->model_module_supercategorymenuadvanced->getProductInfosFiltered($productos_filtrados, $data_filter, $value['name'], $key, $what);
                    } //$productinfos_in_category as $key => $value
                    if ($product_in_product_info_filtered) {
                        $productinfos_in_filter_selected = array();
                        if ($data_filter['filter_productinfo_id']) {
                            $productinfos_filtrados = explode(",", substr($data_filter['filter_productinfo_id'], 0, -1));
                            foreach ($productinfos_filtrados as $productinfos_filtrado) {
                                $productinfos_in_filter_selected[$productinfos_filtrado] = $productinfos_filtrado;
                            } //$productinfos_filtrados as $productinfos_filtrado
                        } //$data_filter['filter_productinfo_id']
                        $results = array_intersect_key($productinfos_in_category, $product_in_product_info_filtered);
                        foreach ($results as $result) {
                            $productinfo_supername   = utf8_strtolower($result['name']);
                            $productinfo_name        = $this->language->get('entry_' . $result['short_name']);
                            $productinfo_id          = $result['productinfo_id'];
                            $productinfo_number      = $result['number'];
                            $productinfo_sort_order  = $result['sort_order'];
                            $productinfo_orderval    = $result['orderval'];
                            $productinfo_view        = $result['view'];
                            $productinfo_initval     = $result['initval'];
                            $productinfo_searchinput = $result['searchinput'];
                            $productinfo_unit        = $result['unit'];
                            $productinfo_short_name  = $result['short_name'];
                            $productinfo_tip         = "";
                            $productinfo_info        = $result['info'];
                            if ($productinfo_info == "yes") {
                                $productinfo_tip_code = '<div class="text-info-filter"><a href="#" rel="popover" data-popover-content="#myPopover' . $tip_id . '"><img class="extra_info" src="image/advancedmenu/spacer.gif" alt=""></a></div>
						<div id="myPopover' . $tip_id . '" class="hide">' . html_entity_decode($result['text_info'][(int) $this->config->get('config_language_id')]) . '</div>';
                                $tip_id++;
                            } //$productinfo_info == "yes"
                            else {
                                $productinfo_tip_code = false;
                            }
                            $productinfo_values       = $product_in_product_info_filtered[$productinfo_id];
                            $productinfos_all_values  = array();
                            $product_info_only_values = array();
                            $productinfo_selected     = array();
                            $productinfo_no_selected  = array();
                            if ($productinfo_values) {
                                foreach ($productinfo_values[$productinfo_id] as $productinfo_value) {
                                    $productinfo_value_name = ($productinfo_value['text'] == "") ? "NDDDDDN" : $productinfo_value['text'];
                                    if (!$nodata && $productinfo_value['text'] == "") {
                                        continue;
                                    } //!$nodata && $productinfo_value['text'] == ""
                                    $string_filtering           = "&filter_{$productinfo_supername}=" . urlencode($productinfo_value_name);
                                    $filter_url                 = $this->url->link('product/asearch', $url_where2go . $string_filtering) . $url_pr . $url_limits . $url_search;
                                    $ajax_url                   = $url_where2go . $url_pr . $url_limits . $url_search . $string_filtering;
                                    $namer                      = $productinfo_value['text'] == "" ? $no_data_text : $productinfo_value['text'];
                                    $product_info_only_values[] = (int) str_replace($productinfo_unit, "", $namer);
                                    if (array_key_exists($productinfo_id, $productinfos_in_filter_selected)) {
                                        $is_selected = 1;
                                    } //array_key_exists($productinfo_id, $productinfos_in_filter_selected)
                                    else {
                                        $is_selected = 0;
                                    }
                                    $productinfos_all_values['str' . (string) $namer] = array(
                                        'name' => $namer,
                                        'href' => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
                                        'ajax_url' => $ajax_url,
                                        'total' => $productinfo_value['total'],
                                        'tipo' => 'productinfo',
                                        'order' => 1,
                                        'selected' => $is_selected
                                    );
                                } //$productinfo_values[$productinfo_id] as $productinfo_value
                                $productinfos_slice = array();
                                if (count($productinfos_all_values) > $productinfo_number) {
                                    $sort_order         = array();
                                    $productinfo_2slice = $productinfos_all_values;
                                    foreach ($productinfo_2slice as $key => $value) {
                                        $sort_order[$key] = $value['total'];
                                    } //$productinfo_2slice as $key => $value
                                    array_multisort($sort_order, SORT_DESC, $productinfo_2slice);
                                    $productinfos_slice = array_slice($productinfo_2slice, 0, $productinfo_number);
                                } //count($productinfos_all_values) > $productinfo_number
                                $productinfos_ord       = array_values($this->model_module_supercategorymenuadvanced->OrderArray($productinfos_all_values, $productinfo_orderval));
                                $productinfos_slice_ord = array_values($this->model_module_supercategorymenuadvanced->OrderArray($productinfos_slice, $productinfo_orderval));
                                $total                  = count($productinfos_ord);
                                $list_number            = $productinfo_number;
                                $view                   = $productinfo_view;
                                $searchinput            = $productinfo_searchinput;
                                if ($is_selected) {
                                    $data['values_selected'][utf8_strtoupper($productinfo_short_name . "_" . $productinfo_id)]['tip_code'] = $productinfo_tip_code;
                                } //$is_selected
                                if ($total == 1 && $productinfos_ord[0]['total'] == $total_productos && $view != "slider") {
                                    $html = ($is_selected) ? $filtros_seleccionados[utf8_strtoupper($productinfo_short_name . "_" . $productinfo_id)]['html'] : $this->model_module_supercategorymenuadvanced->getOneHtml($productinfos_ord[0]['name'], $productinfos_ord[0]['total'], $data_settings, $productinfo_id);
                                } //$total == 1 && $productinfos_ord[0]['total'] == $total_productos && $view != "slider"
                                elseif ($view == "sele") {
                                    $html = ($is_selected) ? $filtros_seleccionados[utf8_strtoupper($productinfo_short_name . "_" . $productinfo_id)]['html'] : $this->model_module_supercategorymenuadvanced->getSelectHtml($productinfos_ord, $data_settings, $productinfo_name, $productinfo_id);
                                } //$view == "sele"
                                    elseif ($view == "image") {
                                    $html = ($is_selected) ? $filtros_seleccionados[utf8_strtoupper($productinfo_short_name . "_" . $productinfo_id)]['html'] : $this->model_module_supercategorymenuadvanced->getImageHtml($productinfos_ord, $data_settings, $productinfo_name);
                                } //$view == "image"
                                    elseif ($view == "list") {
                                    $html = ($is_selected) ? $filtros_seleccionados[utf8_strtoupper($productinfo_short_name . "_" . $productinfo_id)]['html'] : $this->model_module_supercategorymenuadvanced->getListHtml($productinfos_ord, $productinfos_slice_ord, $data_settings, $productinfo_name, $idx, $searchinput, $productinfo_id);
                                    $idx++;
                                } //$view == "list"
                                    elseif ($view == "slider") {
                                    $productinfo_value_name = ($productinfo_value['text'] == "") ? "NDDDDDN" : $productinfo_value['text'];
                                    if (!$nodata && $productinfo_value['text'] == "") {
                                        continue;
                                    } //!$nodata && $productinfo_value['text'] == ""
                                    $valuetoreplace     = "&filter_" . strtolower($productinfo_name) . "=" . ${$this->model_module_supercategorymenuadvanced->GetProductInfo($productinfo_id)};
                                    $url_where2go_clean = str_replace($valuetoreplace, "", $this->model_module_supercategorymenuadvanced->CleanSlider($url_where2go));
                                    $string_filtering   = "&filter_{$productinfo_supername}";
                                    $filter_url_slider  = $this->url->link('product/asearch', $url_where2go_clean) . $url_pr . $url_limits . $url_search . $string_filtering;
                                    $ajax_url_slider    = $url_where2go_clean . $url_pr . $url_limits . $url_search . $string_filtering;
                                    $max_value          = max($product_info_only_values);
                                    $min_value          = min($product_info_only_values);
                                    $half_value         = round(($min_value + $max_value) / 2, 0);
                                    $scale              = "[{$min_value}, '|', {$half_value}, '|', {$max_value}]";
                                    if ($is_selected) {
                                        $url_del                 = $filtros_seleccionados[utf8_strtoupper($productinfo_short_name . "_" . $productinfo_id)]['href'];
                                        $ajaxurl_del             = $filtros_seleccionados[utf8_strtoupper($productinfo_short_name . "_" . $productinfo_id)]['ajax_url'];
                                        $txt_price_rage_selected = $min_value . " " . $productinfo_unit . " - " . $max_value . " " . $productinfo_unit;
                                        if (($max_value == $min_value) || ($max_value - $min_value <= 1)) {
                                            $html = "<ul>";
                                            $html .= "<li  class=\"active\"><em>&nbsp;</em> <a class=\"link_filter_del smenu {dnd:'" . $url_del . "', ajaxurl:'" . $ajaxurl_del . "', gapush:'no'}\" href=\"javascript:void(0)\"  rel=\"nofollow\"><img src=\"image/advancedmenu/spacer.gif\" alt=\"" . $remove_filter_text . "\" class=\"filter_del\" /></a> <span>" . $txt_price_rage_selected . "</span></li>";
                                            $html .= "</ul>";
                                        } //($max_value == $min_value) || ($max_value - $min_value <= 1)
                                        else {
                                            $html = $this->model_module_supercategorymenuadvanced->getSliderHtmlSelected("slider_" . $productinfo_name, $scale, $min_value, $max_value, $productinfo_unit, $productinfo_name, $is_ajax, $ajaxurl_del, $url_del, $ajax_url_slider, $filter_url_slider);
                                        }
                                        $data['values_selected'][utf8_strtoupper($productinfo_short_name . "_" . $productinfo_id)]['html'] = $html;
                                    } //$is_selected
                                    else {
                                        if (($max_value == $min_value) || ($max_value - $min_value <= 1)) {
                                            $html = $this->model_module_supercategorymenuadvanced->getOneHtml($txt_price_rage_selected, $total_productos, $data_settings);
                                        } //($max_value == $min_value) || ($max_value - $min_value <= 1)
                                        else {
                                            $html = $this->model_module_supercategorymenuadvanced->getSliderHtml("slider_" . $productinfo_name, $scale, $min_value, $max_value, $productinfo_unit, $productinfo_name, $is_ajax, $ajax_url_slider, $filter_url_slider);
                                        }
                                    }
                                } //$view == "slider"
                                if (!empty($productinfos_all_values)) {
                                    $productoinfos[$productinfo_id] = array(
                                        'productinfo_id' => $productinfo_id,
                                        'name' => html_entity_decode($productinfo_name),
                                        'total' => count($productinfos_all_values),
                                        'html' => $html,
                                        'jurjur' => $productinfos_ord,
                                        'slice' => $productinfos_slice_ord,
                                        'tipo' => 'productinfo',
                                        'list_number' => $productinfo_number,
                                        'order' => $productinfo_orderval,
                                        'sort_order' => $productinfo_sort_order,
                                        'initval' => $productinfo_initval,
                                        'searchinput' => $productinfo_searchinput,
                                        'jurjur' => $productinfos_all_values,
                                        'view' => $productinfo_view,
                                        'is_selected' => $is_selected,
                                        'tip_code' => $productinfo_tip_code
                                    );
                                } //!empty($productinfos_all_values)
                            } //$productinfo_values
                        } //$results as $result
                        if (!empty($productoinfos)) {
                            foreach ($productoinfos as $key => $value) {
                                if ($value['is_selected']) {
                                    $productinfo_selected[$key] = $productoinfos[$key];
                                } //$value['is_selected']
                                else {
                                    $productinfo_no_selected[$key] = $productoinfos[$key];
                                }
                            } //$productoinfos as $key => $value
                            $data['all_product_infos']         = $productoinfos;
                            $data['no_selected_product_infos'] = $productinfo_no_selected;
                            $data['selected_product_infos']    = $productinfo_selected;
                        } //!empty($productoinfos)
                    } //$product_in_product_info_filtered
                } //is_array($productinfos_in_category)
                $options_in_category = isset($values_in_filter['options']) ? $values_in_filter['options'] : "no options";
                //print_r($options_in_category);
				
				if (is_array($options_in_category)) {
					//echo 'here';
					
                    foreach ($options_in_category as $option_in_category) {
                        $option_ids[$option_in_category['option_id']] = $option_in_category['option_id'];
                    } //$options_in_category as $option_in_category
                    $options_in_category_filtered = $this->model_module_supercategorymenuadvanced->getOptionsFiltered($productos_filtrados, $data_filter, $option_ids, $what);
                    if ($options_in_category_filtered) {
                        $options_in_filter_selected = array();
                        if ($data_filter['filter_option_id']) {
                            $options_filtrados = explode(",", substr($data_filter['filter_option_id'], 0, -1));
                            foreach ($options_filtrados as $options_filtrado) {
                                $options_in_filter_selected[$options_filtrado] = $options_filtrado;
                            } //$options_filtrados as $options_filtrado
                        } //$data_filter['filter_option_id']
                        $results = array_intersect_key($options_in_category, $options_in_category_filtered);
                        foreach ($results as $result) {
                            $option_name        = $this->model_module_supercategorymenuadvanced->getOptionName($result['option_id']);
                            $option_id          = $result['option_id'];
                            $option_number      = $result['number'];
                            $option_sort_order  = $result['sort_order'];
                            $option_orderval    = $result['orderval'];
                            $option_view        = $result['view'];
                            $option_initval     = $result['initval'];
                            $option_searchinput = $result['searchinput'];
                            $option_unit        = $result['unit'];
                            $option_short_name  = $result['short_name'];
                            $option_info        = $result['info'];
                            if ($option_info == "yes") {
                                $option_tip_code = '<div class="text-info-filter"><a href="#" rel="popover" data-popover-content="#myPopover' . $tip_id . '"><img class="extra_info" src="image/advancedmenu/spacer.gif" alt=""></a></div>
						<div id="myPopover' . $tip_id . '" class="hide">' . html_entity_decode($result['text_info'][(int) $this->config->get('config_language_id')]) . '</div>';
                                $tip_id++;
                            } //$option_info == "yes"
                            else {
                                $option_tip_code = false;
                            }
                            $option_values      = $options_in_category_filtered[$option_id];
                            $options_all_values = array();
                            $option_only_values = array();
                            $option_selected    = array();
                            $option_no_selected = array();
                            if ($option_values) {
                                foreach ($option_values as $option_value) {
                                    $option_value_name = ($option_value['text'] == "") ? "NDDDDDN" : $option_value['text'];
                                    if (!$nodata && $option_value['text'] == "") {
                                        continue;
                                    } //!$nodata && $option_value['text'] == ""
                                    $string_filtering     = "&filter_opt[" . $option_value['option_value_id'] . "]=" . urlencode($option_value_name);
                                    $filter_url           = $this->url->link('product/asearch', $url_where2go . $string_filtering) . $url_pr . $url_limits . $url_search;
                                    $ajax_url             = $url_where2go . $url_pr . $url_limits . $url_search . $string_filtering;
                                    $namer                = $option_value['text'] == "" ? $no_data_text : $option_value['text'];
                                    $option_only_values[] = (int) str_replace($option_unit, "", $namer);
                                    if (array_key_exists($option_id, $options_in_filter_selected)) {
                                        $is_selected = 1;
                                    } //array_key_exists($option_id, $options_in_filter_selected)
                                    else {
                                        $is_selected = 0;
                                    }
                                    $options_all_values['str' . (string) $namer] = array(
                                        'name' => $namer,
                                        'href' => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
                                        'ajax_url' => $ajax_url,
                                        'total' => $option_value['total'],
                                        'image_thumb' => $this->model_module_supercategorymenuadvanced->getoptionImage($option_value['option_value_id'], $image_option_width, $image_option_height),
                                        'option_value_id' => $option_value['option_value_id'],
                                        'tipo' => 'OPTION',
                                        'order' => $option_value['order'],
                                        'selected' => $is_selected
                                    );
                                } //$option_values as $option_value
                                $options_slice = array();
                                if (count($options_all_values) > $option_number) {
                                    $sort_order    = array();
                                    $option_2slice = $options_all_values;
                                    foreach ($option_2slice as $key => $value) {
                                        $sort_order[$key] = $value['total'];
                                    } //$option_2slice as $key => $value
                                    array_multisort($sort_order, SORT_DESC, $option_2slice);
                                    $options_slice = array_slice($option_2slice, 0, $option_number);
                                } //count($options_all_values) > $option_number
                                $options_ord       = array_values($this->model_module_supercategorymenuadvanced->OrderArray($options_all_values, $option_orderval));
                                $options_slice_ord = array_values($this->model_module_supercategorymenuadvanced->OrderArray($options_slice, $option_orderval));
                                $total             = count($options_ord);
                                $list_number       = $option_number;
                                $view              = $option_view;
                                $searchinput       = $option_searchinput;
                                if ($is_selected) {
                                    $data['values_selected']['O_' . $option_id]['tip_code'] = $option_tip_code;
                                } //$is_selected
                                if ($total == 1 && $options_ord[0]['total'] == $total_productos && $view != "slider") {
                                    $html = ($is_selected) ? $filtros_seleccionados['O_' . $option_id]['html'] : $this->model_module_supercategorymenuadvanced->getOneHtml($options_ord[0]['name'], $options_ord[0]['total'], $data_settings);
                                } //$total == 1 && $options_ord[0]['total'] == $total_productos && $view != "slider"
                                elseif ($view == "sele") {
                                    $html = ($is_selected) ? $filtros_seleccionados['O_' . $option_id]['html'] : $this->model_module_supercategorymenuadvanced->getSelectHtml($options_ord, $data_settings, $option_name);
                                } //$view == "sele"
                                    elseif ($view == "image") {
                                    $html = ($is_selected) ? $filtros_seleccionados['O_' . $option_id]['html'] : $this->model_module_supercategorymenuadvanced->getImageHtml($options_ord, $data_settings, $option_name);
                                } //$view == "image"
                                    elseif ($view == "list") {
                                    $html = ($is_selected) ? $filtros_seleccionados['O_' . $option_id]['html'] : $this->model_module_supercategorymenuadvanced->getListHtml($options_ord, $options_slice_ord, $data_settings, $option_name, $idx, $searchinput);
                                    $idx++;
                                } //$view == "list"
                                if (!empty($options_all_values)) {
                                    $opciones[$option_id] = array(
                                        'option_id' => $option_id,
                                        'name' => html_entity_decode($option_name),
                                        'total' => count($options_all_values),
                                        'html' => $html,
                                        'jurjur' => $options_ord,
                                        'slice' => $options_slice_ord,
                                        'tipo' => 'option',
                                        'list_number' => $option_number,
                                        'order' => $option_orderval,
                                        'sort_order' => $option_sort_order,
                                        'initval' => $option_initval,
                                        'searchinput' => $option_searchinput,
                                        'jurjur' => $options_all_values,
                                        'view' => $option_view,
                                        'is_selected' => $is_selected,
                                        'tip_code' => $option_tip_code
                                    );
                                } //!empty($options_all_values)
                            } //$option_values
                        } //$results as $result
                        if (!empty($opciones)) {
                            foreach ($opciones as $key => $value) {
                                if ($value['is_selected']) {
                                    $option_selected[$key] = $opciones[$key];
                                } //$value['is_selected']
                                else {
                                    $option_no_selected[$key] = $opciones[$key];
                                }
                            } //$opciones as $key => $value
                            $data['all_options']         = $opciones;
                            $data['no_selected_options'] = $option_no_selected;
                            $data['selected_options']    = $option_selected;
                        } //!empty($opciones)
                    } //$options_in_category_filtered
                
				
				} //is_array($options_in_category)
				
				
				
                $attributes_in_category = isset($values_in_filter['attributes']) ? $values_in_filter['attributes'] : "no attributes";
                if (is_array($attributes_in_category)) {
                    foreach ($attributes_in_category as $attribute_in_category) {
                        $attribute_ids[$attribute_in_category['attribute_id']] = $attribute_in_category['attribute_id'];
                    } //$attributes_in_category as $attribute_in_category
                    $attributes_in_category_filtered = $this->model_module_supercategorymenuadvanced->getAtributesFiltered($productos_filtrados, $data_filter, $attribute_ids, $what);
                    if ($attributes_in_category_filtered) {
                        $attributes_in_filter_selected = array();
                        if ($data_filter['filter_attribute_id']) {
                            $attributes_in_filter_selected = array();
                            $attributes_filtrados          = explode(",", substr($data_filter['filter_attribute_id'], 0, -1));
                            foreach ($attributes_filtrados as $attributes_filtrado) {
                                $attributes_in_filter_selected[$attributes_filtrado] = $attributes_filtrado;
                            } //$attributes_filtrados as $attributes_filtrado
                        } //$data_filter['filter_attribute_id']
                        $results = array_intersect_key($attributes_in_category, $attributes_in_category_filtered);
                        foreach ($results as $result) {
                            $attribute_name        = $this->model_module_supercategorymenuadvanced->getAttributeName($result['attribute_id']);
                            $attribute_id          = $result['attribute_id'];
                            $attribute_number      = $result['number'];
                            $attribute_sort_order  = $result['sort_order'];
                            $attribute_orderval    = $result['orderval'];
                            $attribute_separator   = $result['separator'];
                            $attribute_view        = $result['view'];
                            $attribute_initval     = $result['initval'];
                            $attribute_searchinput = $result['searchinput'];
                            $attribute_unit        = $result['unit'];
                            $attribute_short_name  = $result['short_name'];
                            $attribute_info        = $result['info'];
                            if ($attribute_info == "yes") {
                                $attribute_tip_code = '<div class="text-info-filter"><a href="#" rel="popover" data-popover-content="#myPopover' . $tip_id . '"><img class="extra_info" src="image/advancedmenu/spacer.gif" alt=""></a></div>
					<div id="myPopover' . $tip_id . '" class="hide">' . html_entity_decode($result['text_info'][(int) $this->config->get('config_language_id')]) . '</div>';
                                $tip_id++;
                            } //$attribute_info == "yes"
                            else {
                                $attribute_tip_code = false;
                            }
                            $attribute_values = $attributes_in_category_filtered[$attribute_id];
                            if ($attribute_separator != "no") {
                                $new_array_values = array();
                                if ($attribute_values) {
                                    foreach ($attribute_values as $attribute_value) {
                                        $attributes = explode($attribute_separator, $attribute_value['text']);
                                        $total      = 0;
                                        foreach ($attributes as $attribute) {
                                            if (array_key_exists(trim($attribute), $new_array_values)) {
                                                $new_array_values[trim($attribute)] = array(
                                                    'text' => trim($attribute),
                                                    'total' => $attribute_value['total'] + $new_array_values[trim($attribute)]['total'],
                                                    'separator' => 'YES'
                                                );
                                            } //array_key_exists(trim($attribute), $new_array_values)
                                            else {
                                                $new_array_values[trim($attribute)] = array(
                                                    'text' => trim($attribute),
                                                    'total' => $attribute_value['total'],
                                                    'separator' => 'YES'
                                                );
                                            }
                                        } //$attributes as $attribute
                                    } //$attribute_values as $attribute_value
                                    $attribute_values = $new_array_values;
                                } //$attribute_values
                            } //$attribute_separator != "no"
                            $attributes_all_values = array();
                            $attribute_only_values = array();
                            $attribute_selected    = array();
                            $attribute_no_selected = array();
                            if ($attribute_values) {
                                foreach ($attribute_values as $attribute_value) {
                                    $attribute_value_name = ($attribute_value['text'] == "") ? "NDDDDDN" : $attribute_value['text'];
                                    if (!$nodata && $attribute_value['text'] == "") {
                                        continue;
                                    } //!$nodata && $attribute_value['text'] == ""
                                    $namer                   = $attribute_value['text'] == "" ? $no_data_text : $attribute_value['text'];
                                    $attribute_only_values[] = (int) str_replace($attribute_unit, "", $namer);
                                    if (array_key_exists($attribute_id, $attributes_in_filter_selected)) {
                                        $is_selected = 1;
                                        $filter_url  = $this->url->link('product/asearch', $url_where2go) . $url_pr . $url_limits . $url_search;
                                        $ajax_url    = $url_where2go . $url_pr . $url_limits . $url_search;
                                    } //array_key_exists($attribute_id, $attributes_in_filter_selected)
                                    else {
                                        $is_selected      = 0;
                                        $string_filtering = "&filter_att[" . $attribute_id . "]=" . urlencode($attribute_value_name);
                                        $filter_url       = $this->url->link('product/asearch', $url_where2go . $string_filtering) . $url_pr . $url_limits . $url_search;
                                        $ajax_url         = $url_where2go . $url_pr . $url_limits . $url_search . $string_filtering;
                                    }
                                    $attributes_all_values['str' . (string) $namer] = array(
                                        'name' => $namer,
                                        'href' => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
                                        'ajax_url' => $ajax_url,
                                        'total' => $attribute_value['total'],
                                        'tipo' => 'ATTRIBUTE',
                                        'selected' => $is_selected
                                    );
                                } //$attribute_values as $attribute_value
                                $attributes_slice = array();
                                if (count($attributes_all_values) > $attribute_number) {
                                    $sort_order       = array();
                                    $attribute_2slice = $attributes_all_values;
                                    foreach ($attribute_2slice as $key => $value) {
                                        $sort_order[$key] = $value['total'];
                                    } //$attribute_2slice as $key => $value
                                    array_multisort($sort_order, SORT_DESC, $attribute_2slice);
                                    $attributes_slice = array_slice($attribute_2slice, 0, $attribute_number);
                                } //count($attributes_all_values) > $attribute_number
                                $attributes_ord       = array_values($this->model_module_supercategorymenuadvanced->OrderArray($attributes_all_values, $attribute_orderval));
                                $attributes_slice_ord = array_values($this->model_module_supercategorymenuadvanced->OrderArray($attributes_slice, $attribute_orderval));
                                $total                = count($attributes_ord);
                                $list_number          = $attribute_number;
                                $view                 = $attribute_view;
                                $searchinput          = $attribute_searchinput;
                                if ($is_selected) {
                                    $data['values_selected']['A_' . $attribute_id]['tip_code'] = $attribute_tip_code;
                                } //$is_selected
                                if ($total == 1 && $attributes_ord[0]['total'] == $total_productos && $view != "slider") {
                                    $html = ($is_selected) ? $filtros_seleccionados['A_' . $attribute_id]['html'] : $this->model_module_supercategorymenuadvanced->getOneHtml($attributes_ord[0]['name'], $attributes_ord[0]['total'], $data_settings);
                                } //$total == 1 && $attributes_ord[0]['total'] == $total_productos && $view != "slider"
                                elseif ($view == "sele") {
                                    $html = ($is_selected) ? $filtros_seleccionados['A_' . $attribute_id]['html'] : $this->model_module_supercategorymenuadvanced->getSelectHtml($attributes_ord, $data_settings, $attribute_name);
                                } //$view == "sele"
                                    elseif ($view == "image") {
                                    $html = ($is_selected) ? $filtros_seleccionados['A_' . $attribute_id]['html'] : $this->model_module_supercategorymenuadvanced->getImageHtml($attributes_ord, $data_settings, $attribute_name);
                                } //$view == "image"
                                    elseif ($view == "list") {
                                    $html = ($is_selected) ? $filtros_seleccionados['A_' . $attribute_id]['html'] : $this->model_module_supercategorymenuadvanced->getListHtml($attributes_ord, $attributes_slice_ord, $data_settings, $attribute_name, $idx, $searchinput);
                                    $idx++;
                                } //$view == "list"
                                    elseif ($view == "slider") {
                                    $attribute_value_name = ($attribute_value['text'] == "") ? "NDDDDDN" : $attribute_value['text'];
                                    if (!$nodata && $attribute_value['text'] == "") {
                                        continue;
                                    } //!$nodata && $attribute_value['text'] == ""
                                    $string_filtering  = "&filter_att[" . $attribute_id . "]=" . urlencode($attribute_value_name);
                                    $string_filtering  = "&filter_att[" . $attribute_id . "]";
                                    $filter_url_slider = $this->url->link('product/asearch', $url_where2go) . $string_filtering . $url_pr . $url_limits . $url_search;
                                    $ajax_url_slider   = $url_where2go . $url_pr . $url_limits . $url_search . $string_filtering;
                                    $max_value         = max($attribute_only_values);
                                    $min_value         = min($attribute_only_values);
                                    $half_value        = round(($min_value + $max_value) / 2, 0);
                                    $scale             = "[{$min_value}, '|', {$half_value}, '|', {$max_value}]";
                                    if ($is_selected) {
                                        $url_del     = $filtros_seleccionados['A_' . $attribute_id]['href'];
                                        $ajaxurl_del = $filtros_seleccionados['A_' . $attribute_id]['ajax_url'];
                                        if (($max_value == $min_value) || ($max_value - $min_value <= 1)) {
                                            $txt_price_rage_selected = $min_value . " " . $attribute_unit . " - " . $max_value . " " . $attribute_unit;
                                            $html                    = "<ul>";
                                            $html .= "<li  class=\"active\"><em>&nbsp;</em> <a class=\"link_filter_del smenu {dnd:'" . $url_del . "', ajaxurl:'" . $ajaxurl_del . "', gapush:'no'}\" href=\"javascript:void(0)\"  rel=\"nofollow\"><img src=\"image/advancedmenu/spacer.gif\" alt=\"" . $remove_filter_text . "\" class=\"filter_del\" /></a> <span>" . $txt_price_rage_selected . "</span></li>";
                                            $html .= "</ul>";
                                        } //($max_value == $min_value) || ($max_value - $min_value <= 1)
                                        else {
                                            $html = $this->model_module_supercategorymenuadvanced->getSliderHtmlSelected("slider_" . $attribute_name, $scale, $min_value, $max_value, $attribute_unit, $attribute_name, $is_ajax, $ajaxurl_del, $url_del, $ajax_url_slider, $filter_url_slider);
                                        }
                                        $data['values_selected']['A_' . $attribute_id]['html'] = $html;
                                    } //$is_selected
                                    else {
                                        if (($max_value == $min_value) || ($max_value - $min_value <= 1)) {
                                            $txt_range_selected = $min_value . " " . $attribute_unit . " - " . $max_value . " " . $attribute_unit;
                                            $html               = $this->model_module_supercategorymenuadvanced->getOneHtml($txt_range_selected, $total_productos, $data_settings);
                                        } //($max_value == $min_value) || ($max_value - $min_value <= 1)
                                        else {
                                            $html = $this->model_module_supercategorymenuadvanced->getSliderHtml("slider_" . $attribute_name, $scale, $min_value, $max_value, $attribute_unit, $attribute_name, $is_ajax, $ajax_url_slider, $filter_url_slider);
                                        }
                                    }
                                } //$view == "slider"
                                if (!empty($attributes_all_values)) {
                                    $atributos[$attribute_id] = array(
                                        'attribute_id' => $attribute_id,
                                        'name' => html_entity_decode($attribute_name),
                                        'total' => count($attributes_all_values),
                                        'html' => $html,
                                        'jurjur' => $attributes_ord,
                                        'slice' => $attributes_slice_ord,
                                        'tipo' => 'attribute',
                                        'list_number' => $attribute_number,
                                        'order' => $attribute_orderval,
                                        'sort_order' => $attribute_sort_order,
                                        'initval' => $attribute_initval,
                                        'searchinput' => $attribute_searchinput,
                                        'jurjur' => $attributes_all_values,
                                        'view' => $attribute_view,
                                        'is_selected' => $is_selected,
                                        'tip_code' => $attribute_tip_code
                                    );
                                } //!empty($attributes_all_values)
                            } //$attribute_values
                        } //$results as $result
                        if (!empty($atributos)) {
                            foreach ($atributos as $key => $value) {
                                if ($value['is_selected']) {
                                    $attribute_selected[$key] = $atributos[$key];
                                } //$value['is_selected']
                                else {
                                    $attribute_no_selected[$key] = $atributos[$key];
                                }
                            } //$atributos as $key => $value
                            $data['all_attributes']         = $atributos;
                            $data['no_selected_attributes'] = $attribute_no_selected;
                            $data['selected_attributes']    = $attribute_selected;
                        } //!empty($atributos)
                    } //$attributes_in_category_filtered
                } //is_array($attributes_in_category)
                $all_values = array_merge($opciones, $atributos, $productoinfos);
                $sort_order = array();
                foreach ($all_values as $key => $value) {
                    $sort_order[] = $value['sort_order'];
                } //$all_values as $key => $value
                array_multisort($sort_order, SORT_ASC, $all_values);
                $data['all_values'][$settings_module['filter']['super_order']] = $all_values;
                $all_values                                                    = array_merge($option_no_selected, $attribute_no_selected, $productinfo_no_selected);
                $sort_order                                                    = array();
                foreach ($all_values as $key => $value) {
                    $sort_order[] = $value['sort_order'];
                } //$all_values as $key => $value
                array_multisort($sort_order, SORT_ASC, $all_values);
                $data['values_no_selected'][$settings_module['filter']['super_order']] = $all_values;
                if (!$menu_filters) {
                    $data['values_selected'] = '';
                } //!$menu_filters
            } //!empty($productos_filtrados)
            else {
                $data['menu'] = false;
            }
            if (isset($settings_module['styles']['template_menu'])) {
                $template_menu = $settings_module['styles']['template_menu'];
            } //isset($settings_module['styles']['template_menu'])
            else {
                $template_menu = 'scma_standar_V4.tpl';
            }
            

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/advancedmenu/templates/' . $template_menu)) {
                $this->model_module_supercategorymenuadvanced->CacheMenu($this->load->view($this->config->get('config_template') . '/template/module/advancedmenu/templates/' . $template_menu, $data), $data_filter, $what, $this->session->data['currency']);
                if (!isset($this->request->get['a'])) {
                    return $this->load->view($this->config->get('config_template') . '/template/module/advancedmenu/templates/' . $template_menu, $data);
                } //!isset($this->request->get['a'])
                else {
                    return $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/module/advancedmenu/templates/' . $template_menu, $data));
                }
            } //file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/advancedmenu/templates/' . $template_menu)
            else {
                $this->model_module_supercategorymenuadvanced->CacheMenu($this->response->setOutput($this->load->view('default/template/module/advancedmenu/templates/' . $template_menu, $data)), $data_filter, $what, $this->session->data['currency']);
                if (!isset($this->request->get['a'])) {
                    return $this->load->view('default/template/module/advancedmenu/templates/' . $template_menu, $data);
                } //!isset($this->request->get['a'])
                else {
                    return $this->response->setOutput($this->load->view('default/template/module/advancedmenu/templates/' . $template_menu, $data));
                }
            }
        }
    }
}
?>