<?php 
class ControllerProductaSearch extends Controller { 	
	public function index() { 
	
		 
		$this->language->load('product/asearch');
		
		$this->load->model('module/supercategorymenuadvanced');
		$this->load->model('catalog/asearch');
		$this->language->load('module/supercategorymenuadvanced');
		$this->load->model('tool/image'); 
		
			
		$data['button_continue'] = $this->language->get('button_continue');
		$data['continue'] = $this->url->link('common/home');
		$data['thumb'] = '';
		$data['description'] = '';		
		$txt_reset_filter= $this->language->get('txt_reset_filter');
		
		
		$data['store_id'] =$STORE= $this->config->get('config_store_id');
	    
		//INIT FILTER VARS
		$has_filter=$filter=$filtros_seleccionados=$filter_att=$filter_opt='';
		$filter_manufacturer_id=$filter_category_id='';
		$filter_option_id=$filter_options_by_name=$filter_options_by_ids='';
		$filter_attribute_string=$filter_attribute_id=$filter_attributes_by_name='';
		$filter_ids=$filter_by_name='';
		$filter_price_range='';
		$filter_special=$filter_model='';
		$filter_min_price=$filter_max_price='';
		$filter_stock_id=$filter_stock=$filter_clearance=$filter_arrivals='';
		$filter_manufacturers_by_id='';	$filter_manufacturers_by_id_string='';	'';
		$filter_productinfo_id='';
		$filter_width=$filter_height=$filter_length=$filter_weight='';
		$filter_ean=$filter_isbn=$filter_mpn=$filter_jan=$filter_sku=$filter_upc=$filter_location='';
		$filter_rating='';
		
		$idx=$tip_id=1;
				
		$url_where2go_brands=$filter_search=$filter_tag=$filter_description=$filter_subcategory=$urlfilter='';
				
		//this will contain all filters we need
		$super_url = $url_pr = $url_search = $url_limits = '';
		
		$url_top_filters='';
		
			$title_name='';
			$filter = false;
        //init filters
        $filter_manufacturers_by_id = '';
        $filter_manufacturers_by_id_string = '';
        $filter_attributes_by_name = '';
        $filter_attribute_id = '';
        $filter_options_by_name = '';
        $filter_option_id = '';
        $filter_attribute_string = '';
        $filter_min_price = '';
        $filter_max_price = '';
        $filter_stock_id = '';
        $filter_by_name = '';
        $filter_ids = '';
        $filter_stock = '';
        $filter_special = '';
        $filter_clearance = '';
        $filter_arrivals = '';
        $filter_width = '';
        $filter_height = '';
        $filter_length = '';
        $filter_model = '';
        $filter_sku = '';
        $filter_upc = '';
        $filter_location = '';
        $filter_productinfo_id = '';
        $filter_weight = '';
        $filter_options_by_ids = '';
        $filter_ean = '';
        $filter_isbn = '';
        $filter_mpn = '';
        $filter_jan = '';
        $filter_rating = '';

        $filter_in_manufacturer_id = false;
        $filter_in_category_id = 0;
        $filtros_seleccionados = $url_filter = '';
		//Load filter settings.
		$settings_module=  $this->model_module_supercategorymenuadvanced->getMySetting('SETTINGS_'.$STORE,$STORE);

		//Load filter settings.
		$data['see_more_trigger']=isset($settings_module['general_data']['see_more_trigger'])? $settings_module['general_data']['see_more_trigger'] : false;
		$data['option_tip']=isset($settings_module['general_data']['option_tip'])? $settings_module['general_data']['option_tip'] : false;	
		$count_products=$data['count_products']= isset($settings_module['general_data']['countp'])? $settings_module['general_data']['countp'] : false;	
		$nofollow=$data['nofollow']= isset($settings_module['general_data']['nofollow'])? 'rel="nofollow"' : '';	
		$track_google=$data['track_google']= isset($settings_module['general_data']['track_google'])? $settings_module['general_data']['track_google'] : false;
		$menu_mode 			=isset($settings_module['general_data']['menu_mode'])? $settings_module['general_data']['menu_mode'] : "developing";
		$image_option_width	= isset($settings_module['general_data']['image_option_width'])? $settings_module['general_data']['image_option_width'] : 20;
		$image_option_height= isset($settings_module['general_data']['image_option_height'])? $settings_module['general_data']['image_option_height'] : 20;
		$asearch_filters 	= isset($settings_module['general_data']['asearch_filters'])? $settings_module['general_data']['asearch_filters'] : false;
		$menu_filters 		= isset($settings_module['general_data']['menu_filters'])? $settings_module['general_data']['menu_filters'] : false;
		$ocscroll			= isset($settings_module['general_data']['ocscroll'])? $settings_module['general_data']['ocscroll'] : false;
		$totales			= isset($settings_module['general_data']['totales'])? $settings_module['general_data']['totales'] : false;
		$link_product		= isset($settings_module['general_data']['link_to_product'])? $settings_module['general_data']['link_to_product'] : false;
		$option_stock		= isset($settings_module['general_data']['option_stock'])? $settings_module['general_data']['option_stock'] : false;
		
		$modecatalog 		= isset($settings_module['general_data']['modecatalog'])? $settings_module['general_data']['modecatalog'] : false;
		
		
		
		
		$data_settings=array(
			'nofollow'			=> $nofollow,
			'track_google' 		=> $track_google,
			'count_products'	=> $count_products,
			'option_stock' 		=> $option_stock,
			'clearance'			=> isset($settings_module['stock']['clearance_value']) ? $settings_module['stock']['clearance_value'] : '',
			'days'				=> isset($settings_module['stock']['number_day']) ? $settings_module['stock']['number_day'] : 7,
			'reviews' 			=> $settings_module['reviews']['tipo']
			
		);
		
		
		$data['option_images']=$option_images= isset($settings_module['general_data']['option_images'])? $settings_module['general_data']['option_images'] : false;
		
		
		if ($option_images) {
			
			$this->load->model('module/optionsimagesaddon');
			$opt_settings=$this->config->get('oc_option_image_addon_settings');
		
			$data['mouse_event']=$opt_settings['mouse_event'];
			$data['option_tip_image']=false;
			
			if ($opt_settings['option_tip']){
				$data['option_tip_image']=true;
			}
		}
			
		$data['is_categories']=$asearch_filters ;
		
				
		//Load AJAX settings.
		if (isset($settings_module['ajax']['enable'])){
			$is_ajax=$data['is_ajax']=$settings_module['ajax']['enable'];
		}else{
			$is_ajax=$data['is_ajax']=0;
		}
		
	   
	   if (isset($this->request->get['search'])) {
			$search = $this->request->get['search'];
			$url_search .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
		} else {
			$search = '';
		}

		if (isset($this->request->get['tag'])) {
			$tag = $this->request->get['tag'];
			$url_search .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
		} elseif (isset($this->request->get['search'])) {
			$tag = $this->request->get['search'];
			$url_search .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
		} else {
			$tag = '';
		}

		if (isset($this->request->get['description'])) {
			$description = $this->request->get['description'];
			$url_search .= '&description=' . $this->request->get['description'];
		} else {
			$description = '';
		}

		if (isset($this->request->get['category_id'])) {
			$category_id = $this->request->get['category_id'];
			$url_search .= '&category_id=' . $this->request->get['category_id'];
		} else {
			$category_id = 0;
		}

		if (isset($this->request->get['sub_category'])) {
			$sub_category = $this->request->get['sub_category'];
			$url_search .= '&sub_category=' . $this->request->get['sub_category'];
		} else {
			$sub_category = '';
		}
		
		
		//limits
		$url_limits='';
			
		if (isset($this->request->get['sort'])) {
			$url_limits.= '&sort=' . $this->request->get['sort'];
			$sort=$this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
		}	
		if (isset($this->request->get['order'])) {
			$url_limits.= '&order=' . $this->request->get['order'];
			$order=$this->request->get['order'];
		} else {
				$order = 'ASC';
			}
		if (isset($this->request->get['limit'])) {
			$url_limits.= '&limit=' . $this->request->get['limit'];
			$limit=$this->request->get['limit'];
		} else {
			$limit = $this->config->get('config_product_limit');
		}
		if (isset($this->request->get['page'])) {
			$url_limits .= '&page=' . $this->request->get['page'];
			$page=$this->request->get['page'];
			
		}else{
			$page=1;	
			
		}
		
		$filter_coin=$this->session->data['currency'];
		
		
		if (isset($this->request->get['pr'])) {
			$url_pr.= '&pr=' . $this->request->get['pr'];
	        $filter_price_range=$this->request->get['pr'];
		
		}
		if (!isset($this->request->get['path']) or empty($this->request->get['path'])) {
			
			if (isset($this->request->get['filter_category_id'])) {
			
				$this->request->get['path']=$this->request->get['filter_category_id'];
			}else{
				$this->request->get['path']=0;
			}
		}else{
		
			$this->request->get['path']=$this->request->get['path'];
			
			
		}
	
	
		//category
		if(isset($this->request->get['path'])) {
		      $url_top_filters.= '&path=' . $this->request->get['path'];
		      $pt=explode('_', (string)$this->request->get['path']);
		      $filter_category_id=(int)array_pop($pt);
		 }
		//Manufacturer
		if(isset($this->request->get['manufacturer_id'])) {
			 $url_top_filters.= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			 $filter_manufacturer_id=$this->request->get['manufacturer_id'];
		 }
		
		
		if (empty($this->request->get['route']) || $this->request->get['route']=="common/home" || $this->request->get['route']=="product/special"){
	    	$data['breadcrumbs']=$this->Category_breadcrumbs(0,$url_limits);
			 $what="C";
	 	 }
		 if ($this->request->get['route']=="product/category"){
			$data['breadcrumbs']=$this->Category_breadcrumbs($filter_category_id,$url_limits);
		$what="C";
	 	}
	 	if ($this->request->get['route']=="product/manufacturer"){
	  	 $data['breadcrumbs']=$this->Manufacturer_breadcrumbs(0,$url_limits);
	  	 $what="M";
		}
		if ($this->request->get['route']=="product/manufacturer/info"){
		$data['breadcrumbs']=$this->Manufacturer_breadcrumbs($filter_manufacturer_id,$url_limits);
		$what="M";
   		 }
   	   	if(isset($this->request->get['filtering'])) {
		 $what=$this->request->get['filtering'];
		 $url_top_filters.= '&filtering=' . $this->request->get['filtering'];
		}
		
	    if (!$title_name){
    	$data['heading_title'] = sprintf($this->language->get('heading_title'),$this->language->get('this_store'));
		}else{
		$data['heading_title'] = sprintf($this->language->get('heading_title'),$title_name);
		}	
		
		
		
		if ($this->request->get['route']=="product/asearch"){
			
			if (!isset($what)){
				
				$what="C";	
			
				$data['breadcrumbs']=$this->Category_breadcrumbs(0,$url_limits);
			
			//echo "estamos en product/asearch";
			}elseif ($what=="M"){
					$data['breadcrumbs']=$this->Manufacturer_breadcrumbs($filter_manufacturer_id,$url_limits);
					$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($filter_manufacturer_id);
						if ($manufacturer_info) {
							$title_name=$manufacturer_info['name'];
						}
				$data['heading_title'] = sprintf($this->language->get('heading_title'),$title_name);
				$what="M";
			}else{
					$data['breadcrumbs']=$this->Category_breadcrumbs($filter_category_id,$url_limits);
					$category_info = $this->model_catalog_category->getCategory($filter_category_id);
						if ($category_info) {
						$title_name=$category_info['name'];
						}
					$data['heading_title'] = sprintf($this->language->get('heading_title'),$title_name);
					$what="C";
			}
		 }
		//filter_rating
		if(isset($this->request->get['filter_rating'])) {
			 $url_top_filters.= '&filter_rating=' . $this->request->get['filter_rating'];
			 $filter_rating=$this->request->get['filter_rating'];
		 }
		//filter_clearance
		if(isset($this->request->get['filter_clearance'])) {
			 $url_top_filters.= '&filter_clearance=' . $this->request->get['filter_clearance'];
			 $filter_clearance=$this->request->get['filter_clearance'];
		 }
		//filter_stock
		if(isset($this->request->get['filter_stock'])) {
			 $url_top_filters.= '&filter_stock=' . $this->request->get['filter_stock'];
			 $filter_stock=$this->request->get['filter_stock'];
		 }
		
		//filter_arrivals
		if(isset($this->request->get['filter_arrivals'])) {
			 $url_top_filters.= '&filter_arrivals=' . $this->request->get['filter_arrivals'];
			 $filter_arrivals=$this->request->get['filter_arrivals'];
		 }
		
		//filter_special
		if(isset($this->request->get['filter_special'])) {
			 $url_top_filters.= '&filter_special=' . $this->request->get['filter_special'];
			 $filter_special=$this->request->get['filter_special'];
		 }
		 
		 
		if($this->request->get['route']=="product/special") {
			 $url_top_filters.= '&filter_special=yes';
			 $filter_special="yes";
		 } 
		 
		 
		
		//filter_width 
		 if(isset($this->request->get['filter_width'])) {
			 $url_top_filters.= '&filter_width=' . $this->request->get['filter_width'];
			 $filter_width=$this->request->get['filter_width'];
			$filter_productinfo_id.="1,";
		
		 }
		 if(isset($this->request->get['filter_height'])) {
			 $url_top_filters.= '&filter_height=' . $this->request->get['filter_height'];
			 $filter_height=$this->request->get['filter_height'];
			$filter_productinfo_id.="2,";
		
		 }
		 
		 if(isset($this->request->get['filter_length'])) {
			 $url_top_filters.= '&filter_length=' . $this->request->get['filter_length'];
			 $filter_length=$this->request->get['filter_length'];
			$filter_productinfo_id.="3,";
		
		 }
		 
		 if(isset($this->request->get['filter_model'])) {
			 $url_top_filters.= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			 $filter_model=$this->request->get['filter_model'];
			$filter_productinfo_id.="4,";
		
		 }
		if(isset($this->request->get['filter_sku'])) {
			 $url_top_filters.= '&filter_sku=' . $this->request->get['filter_sku'];
			 $filter_sku=$this->request->get['filter_sku'];
			 $filter_productinfo_id.="5,";
		
		 }
		if(isset($this->request->get['filter_upc'])) {
			 $url_top_filters.= '&filter_upc=' . $this->request->get['filter_upc'];
			 $filter_upc=$this->request->get['filter_upc'];
			$filter_productinfo_id.="6,";
		
		 }
		 if(isset($this->request->get['filter_location'])) {
			 $url_top_filters.= '&filter_location=' . urlencode(html_entity_decode($this->request->get['filter_location'], ENT_QUOTES, 'UTF-8'));
			 $filter_location=$this->request->get['filter_location'];
			$filter_productinfo_id.="7,";
		
		 }
		 
		 if(isset($this->request->get['filter_weight'])) {
			 $url_top_filters.= '&filter_weight=' . $this->request->get['filter_weight'];
			 $filter_weight=$this->request->get['filter_weight'];
			$filter_productinfo_id.="8,";
		
		 }
		 
		 if(isset($this->request->get['filter_ean'])) {
			 $url_top_filters.= '&filter_ean=' . $this->request->get['filter_ean'];
			 $filter_ean=$this->request->get['filter_ean'];
			$filter_productinfo_id.="9,";
		
		 }
		 if(isset($this->request->get['filter_isbn'])) {
			 $url_top_filters.= '&filter_isbn=' . $this->request->get['filter_isbn'];
			 $filter_isbn=$this->request->get['filter_isbn'];
			$filter_productinfo_id.="10,";
		
		 }
		 
		  if(isset($this->request->get['filter_mpn'])) {
			 $url_top_filters.= '&filter_mpn=' . $this->request->get['filter_mpn'];
			 $filter_mpn=$this->request->get['filter_mpn'];
			$filter_productinfo_id.="11,";
		
		 }
		 
		if(isset($this->request->get['filter_jan'])) {
			 $url_top_filters.= '&filter_jan=' . $this->request->get['filter_jan'];
			 $filter_jan=$this->request->get['filter_jan'];
			$filter_productinfo_id.="12,";
		
		 }
			//  urlencode(html_entity_decode(, ENT_QUOTES, 'UTF-8'));
		
		//filter_att[3]=Clockspeed-100mhz-n
		if(isset($this->request->get['filter_att'])) {
			
			$filter_att=true;			
			
			foreach ($this->request->get['filter_att'] as $key=>$value){
			
				$url_top_filters.= '&filter_att['.$key.']=' . urlencode(html_entity_decode($value, ENT_QUOTES, 'UTF-8'));
				}
			 
		 }
		
	
		
		//filter_att[3]=Clockspeed-100mhz-n
		if(isset($this->request->get['filter_opt'])) {
			
			$filter_opt=true;			
			
			foreach ($this->request->get['filter_opt'] as $key=>$value){
			
				$url_top_filters.= '&filter_opt['.$key.']='. urlencode(html_entity_decode($value, ENT_QUOTES, 'UTF-8'));
				}
		 }
		
	  if ($what=="M"){
			$values_in_filter= $this->config->get('VALORESM_'.$filter_manufacturer_id, (int)$this->config->get('config_store_id') );
			$url_where2go_brands='manufacturer_id='.$filter_manufacturer_id;
		    $url_where2go_m=(isset($this->request->get['path'])) ? 'path='.$this->request->get['path'] : "path=0";
		
		}elseif($what=="C"){
			$values_in_filter= $this->config->get('VALORES_'.$filter_category_id, (int)$this->config->get('config_store_id') );
		    $url_where2go_m=(isset($this->request->get['path'])) ? 'path='.$this->request->get['path'] : "path=0";	
		}

		
	 $url_where2go=$url_top_filters;
				
			
	
			 
		$pricerange_text 				= $this->language->get('pricerange_text');
		$manufacturer_text				= $this->language->get('manufacturer_text');
		$stock_text						= $this->language->get('stock_text');
		$no_data_text 					= $this->language->get('no_data_text');	
		$category_text 					= $this->language->get('category_text');
		$rating_text 					= $this->language->get('rating_text');
		$txt_reset_filter				= $this->language->get('txt_reset_filter');
		$data['text_empty'] = $this->language->get('text_empty');
		$data['text_category'] = $this->language->get('text_category');
		$data['text_sub_category'] = $this->language->get('text_sub_category');
		$data['text_quantity'] = $this->language->get('text_quantity');
		$data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$data['text_model'] = $this->language->get('text_model');
		$data['text_price'] = $this->language->get('text_price');
		$data['text_tax'] = $this->language->get('text_tax');
		$data['text_points'] = $this->language->get('text_points');
		$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
		$data['text_display'] = $this->language->get('text_display');
		$data['text_list'] = $this->language->get('text_list');
		$data['text_grid'] = $this->language->get('text_grid');		
		$data['text_sort'] = $this->language->get('text_sort');
		$data['text_limit'] = $this->language->get('text_limit');
		$data['text_refine'] = $this->language->get('text_refine');
    	
		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');
		$data['compare'] = $this->url->link('product/compare');
		$data['remove_filter_text'] = $this->language->get('remove_filter_text');
		$data['entry_selected'] = $this->language->get('entry_selected');
	    $data['button_list'] = $this->language->get('button_list');
		$data['button_grid'] = $this->language->get('button_grid');


		$data['reset_all_filter']="<a class=\"filter_del_link link_filter_del smenu {dnd:'".$this->url->link('product/asearch')."', ajaxurl:'', gapush:'no'}\" href=\"javascript:void(0)\" nofollow><img src=\"image/advancedmenu/spacer.gif\" alt=\"".$txt_reset_filter."\" title=\"".$txt_reset_filter."\" class=\"filter_del_nav_img\" /></a>";	
	
		$data['isset_subcategories']=false;
		$no_data_text=$data['no_data_text'] 		= $this->language->get('no_data_text');
		
		
		
		/* FIRST PART */
		//////////////////////////////////////////////////////////////////
		
		
		
		
		if (isset($filter_manufacturer_id) && !empty($filter_manufacturer_id)) {// manufacturer

			$url_where2go_clean=str_replace("&manufacturer_id=".$filter_manufacturer_id,"",$url_where2go);
			$filter_url=$this->url->link('product/asearch', $url_where2go_clean).$url_pr.$url_limits.$url_search;
			$ajax_url=$url_where2go_clean.$url_pr.$url_limits.$url_search;
			
			$value=$this->model_module_supercategorymenuadvanced->getManufacturerName($filter_manufacturer_id);
			
			$filtros_seleccionados['_M_']=array(
						'href'		   => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
						'ajax_url'	   => $ajax_url,
						'name'		   => html_entity_decode($value),
						'dnd'		   => html_entity_decode($manufacturer_text),					
				);	
			}
		
				
		if (isset($filter_rating) && !empty($filter_rating)) {// rating
			
			$url_where2go_clean=str_replace("&filter_rating=".$filter_rating,"",$url_where2go);
			$filter_url=$this->url->link('product/asearch', $url_where2go_clean).$url_pr.$url_limits.$url_search;
			$ajax_url=$url_where2go_clean.$url_pr.$url_limits.$url_search;
			
			$filtros_seleccionados['RA_A']=array(
						'href'		   => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
						'ajax_url'	   => $ajax_url,
						'name'		   => html_entity_decode($filter_rating),
						'dnd'		   => html_entity_decode($this->language->get('rating_text')),
			);	
			
	
        }
		
			
		if (isset($filter_stock) && !empty($filter_stock)) {// 

			$url_where2go_clean=str_replace("&filter_stock=".$filter_stock,"",$url_where2go);
			$filter_url=$this->url->link('product/asearch', $url_where2go_clean).$url_pr.$url_limits.$url_search;
			$ajax_url=$url_where2go_clean.$url_pr.$url_limits.$url_search;
				
			$filtros_seleccionados['SS_A']=array(
						'href'		   => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
						'ajax_url'	   => $ajax_url,
						'name'		   => html_entity_decode($this->language->get('in_stock_text')),
						'dnd'		   => html_entity_decode($this->language->get('stock_text')),
			);	
        }
		
		if (isset($filter_special) && !empty($filter_special)) {// 

			$url_where2go_clean=str_replace("&filter_special=".$filter_special,"",$url_where2go);
			$filter_url=$this->url->link('product/asearch', $url_where2go_clean).$url_pr.$url_limits.$url_search;
			$ajax_url=$url_where2go_clean.$url_pr.$url_limits.$url_search;
				
			$filtros_seleccionados['SP_A']=array(
						'href'		   => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
						'ajax_url'	   => $ajax_url,
						'name'		   => html_entity_decode($this->language->get('special_prices_text')),
						'dnd'		   => html_entity_decode($this->language->get('stock_text')),
			);	
        }

		
		
		if (isset($filter_arrivals) && !empty($filter_arrivals)) {// 

			$url_where2go_clean=str_replace("&filter_arrivals=".$filter_arrivals,"",$url_where2go);
			$filter_url=$this->url->link('product/asearch', $url_where2go_clean).$url_pr.$url_limits.$url_search;
			$ajax_url=$url_where2go_clean.$url_pr.$url_limits.$url_search;
			
			$filtros_seleccionados['SN_A']=array(
						'href'		   => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
						'ajax_url'	   => $ajax_url,
						'name'		   => html_entity_decode($this->language->get('new_products_text')),
						'dnd'		   => html_entity_decode($this->language->get('stock_text')),
			);	

		}

		
		
		
		if (isset($filter_clearance) && !empty($filter_clearance)) {// rating

			$url_where2go_clean=str_replace("&filter_clearance=".$filter_clearance,"",$url_where2go);
			$filter_url=$this->url->link('product/asearch', $url_where2go_clean).$url_pr.$url_limits.$url_search;
			$ajax_url=$url_where2go_clean.$url_pr.$url_limits.$url_search;
		
			$filtros_seleccionados['SC_A']=array(
						'href'		   => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
						'ajax_url'	   => $ajax_url,
						'name'		   => html_entity_decode($this->language->get('clearance_text')),
						'dnd'		   => html_entity_decode($this->language->get('stock_text')),
			);	

		}

       if ($search){// show search box

			$filter_url=$this->url->link('product/asearch', $url_where2go).$url_pr.$url_limits;
			$ajax_url=$url_where2go.$url_pr.$url_limits.$url_search;
			         
			$filtros_seleccionados[utf8_strtoupper('search_1')]=array( 

						'href'		   => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
						'ajax_url'	   => $ajax_url,
						'name'		   => html_entity_decode($search),
						'dnd'		   => html_entity_decode($this->language->get('search_filter_text')),
			);	

		}
			
			
		//PRICE RANGE
		if (isset($filter_price_range) and !empty($filter_price_range)) {
			
				$filter_url=$this->url->link('product/asearch', $url_where2go).$url_limits.$url_search;
				$ajax_url=$url_where2go.$url_limits.$url_search;

			    $SymbolLeft=$this->currency->getSymbolLeft();
				$SymbolRight=$this->currency->getSymbolRight();
				
				
			
				list($filter_min_price,$filter_max_price)=explode(";",$filter_price_range);
				
				//$SymbolLeft=$this->currency->getSymbolLeft();
				//$SymbolRight=$this->currency->getSymbolRight();
					
				//$txt_price_rage_selected=$SymbolLeft.$filter_min_price.$SymbolRight." - ".$SymbolLeft.$filter_max_price.$SymbolRight;
				
				//remove currency from price
				$filter_min_price=floor($this->model_module_supercategorymenuadvanced->UnformatMoney($filter_min_price,$filter_coin)); 
				$filter_max_price=ceil($this->model_module_supercategorymenuadvanced->UnformatMoney($filter_max_price,$filter_coin));
				
				 if ($this->config->get('config_tax') && $settings_module['pricerange']['setvat']) {
					    $tax_value= $this->tax->calculate(1, $settings_module['pricerange']['tax_class_id'], $this->config->get('config_tax'));
						$filter_min_price=floor( $filter_min_price/$tax_value ); 
						$filter_max_price=ceil( $filter_max_price/$tax_value );
			     }
			
				$txt_price_rage_selected=$SymbolLeft.$filter_min_price.$SymbolRight." - ".$SymbolLeft.$filter_max_price.$SymbolRight;
			
		
			
				$filtros_seleccionados[utf8_strtoupper("PR_PRICERANGE_1")]=array(
							'href'		   => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
							'ajax_url'	   => $ajax_url,	
							'dnd'		   => $this->language->get('pricerange_text'),
							'name'		   => $txt_price_rage_selected,
						
				);
						
			}	
			
		
		if ($filter_att) {// we have attributes on filter

			foreach ($this->request->get['filter_att'] as $key=>$value){
			
				//$url_top_filters.= '&filter_att['.$key.']=' . urlencode($value);
				$att_info=$values_in_filter['attributes'][$key];
				$att_name= $att_info['name'];
				$att_id=$att_info['attribute_id'];
				$att_separator= $att_info['separator'];
				$att_view= $att_info['view'];
					
				if ($att_separator!="no"){
					$ss="p";
				}else{
					$ss=$this->model_module_supercategorymenuadvanced->GetView($att_view);
				}
						
				$filter_attribute_id.=$key.",";
				$filter_by_name.=$this->model_module_supercategorymenuadvanced->CleanName($value).$ss."ATTNNATT@@@";	
				//$filter_attributes_by_name.=$value.$ss."ATTNNATT";		
				$filter_ids.=$key.",";			
				$filter_options_by_ids.="0,";				 
				
				$url_where2go_clean=$this->model_module_supercategorymenuadvanced->RemoveFilterOPtAtt($url_where2go,'filter_att',$key);
				
				$filter_url=$this->url->link('product/asearch', $url_where2go_clean).$url_pr.$url_limits.$url_search;
				
				$ajax_url=$url_where2go_clean.$url_pr.$url_limits.$url_search;
					
				$filtros_seleccionados[utf8_strtoupper("A_".$att_id)]=array(
							'href'		   => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
							'ajax_url'	   => $ajax_url,
							'name'		   => html_entity_decode($value),
							'dnd'		   => html_entity_decode($att_name),	
							
					);	
			
			 }
	
       	 }
		
			if ($filter_opt) {// we have option filter
		

			foreach ($this->request->get['filter_opt'] as $key=>$value){
			
				//$url_top_filters.= '&filter_opt['.$key.']=' . $value;
				$opt_info=$values_in_filter['options'][$this->model_module_supercategorymenuadvanced->GetOptionid($key)];
				
				 	$opt_name= $opt_info['name'];
					$opt_view= $opt_info['view'];
			       	$opt_id=$opt_info['option_id'];
					
					$url_where2go_clean=$this->model_module_supercategorymenuadvanced->RemoveFilterOPtAtt($url_where2go,'filter_opt',$key);
			
			 		$filter_url=$this->url->link('product/asearch', $url_where2go_clean).$url_pr.$url_limits.$url_search;
			 
			 		$ajax_url=$url_where2go_clean.$url_pr.$url_limits.$url_search;
							
					$filter_option_id.=$this->model_module_supercategorymenuadvanced->GetOptionid($key).",";	
					$filter_by_name.=$this->model_module_supercategorymenuadvanced->CleanName($value)."OPTTOP@@@";
					$filter_ids.=$key.",";
					$filter_options_by_ids.=$key.",";
			
				 
				$filtros_seleccionados[utf8_strtoupper("O_".$opt_id)]=array(
						'href'		   => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
						'ajax_url'	   => $ajax_url,
						'name'		   => html_entity_decode($value),
						'dnd'		   => html_entity_decode($opt_name),
				);	
			 }
	        }	
		
		

		
		
		if (isset($filter_width) && !empty($filter_width)) {

			$filtros_seleccionados['filter_width']=$this->model_module_supercategorymenuadvanced->GetProductInfosAsearchSelected($values_in_filter,'width',1,$filter_width,'w',$url_where2go,$url_pr,$url_limits,$url_search);
		 }
		 
		  
		 
         if (isset($filter_height) && !empty($filter_height)) {

			$filtros_seleccionados['filter_height']=$this->model_module_supercategorymenuadvanced->GetProductInfosAsearchSelected($values_in_filter,'height',2,$filter_height,'h',$url_where2go,$url_pr,$url_limits,$url_search);
		 }			
	
		 	
		 if (isset($filter_length) && !empty($filter_length)) {

			$filtros_seleccionados['filter_length']=$this->model_module_supercategorymenuadvanced->GetProductInfosAsearchSelected($values_in_filter,'length',3,$filter_length,'l',$url_where2go,$url_pr,$url_limits,$url_search);
		 }		
	       
		 if (isset($filter_model) && !empty($filter_model)) {

			$filtros_seleccionados['filter_model']=$this->model_module_supercategorymenuadvanced->GetProductInfosAsearchSelected($values_in_filter,'model',4,$filter_model,'mo',$url_where2go,$url_pr,$url_limits,$url_search);
		 }	 
		   
		 if (isset($filter_sku) && !empty($filter_sku)) {

			$filtros_seleccionados['filter_sku']=$this->model_module_supercategorymenuadvanced->GetProductInfosAsearchSelected($values_in_filter,'sku',5,$filter_sku,'sk',$url_where2go,$url_pr,$url_limits,$url_search);
		 }		   
		   
		 if (isset($filter_upc) && !empty($filter_upc)) {

			$filtros_seleccionados['filter_upc']=$this->model_module_supercategorymenuadvanced->GetProductInfosAsearchSelected($values_in_filter,'upc',6,$filter_upc,'up',$url_where2go,$url_pr,$url_limits,$url_search);
		 }		   
		 if (isset($filter_location) && !empty($filter_location)) {

			$filtros_seleccionados['filter_location']=$this->model_module_supercategorymenuadvanced->GetProductInfosAsearchSelected($values_in_filter,'location',7,$filter_location,'lo',$url_where2go,$url_pr,$url_limits,$url_search);
		 }		
      
	     if (isset($filter_weight) && !empty($filter_weight)) {

			$filtros_seleccionados['filter_weight']=$this->model_module_supercategorymenuadvanced->GetProductInfosAsearchSelected($values_in_filter,'weight',8,$filter_weight,'wg',$url_where2go,$url_pr,$url_limits,$url_search);
		 }		   
		 if (isset($filter_ean) && !empty($filter_ean)) {

			$filtros_seleccionados['filter_ean']=$this->model_module_supercategorymenuadvanced->GetProductInfosAsearchSelected($values_in_filter,'ean',9,$filter_ean,'e',$url_where2go,$url_pr,$url_limits,$url_search);
		 }		
      
	     if (isset($filter_isbn) && !empty($filter_isbn)) {

			$filtros_seleccionados['filter_isbn']=$this->model_module_supercategorymenuadvanced->GetProductInfosAsearchSelected($values_in_filter,'isbn',10,$filter_isbn,'i',$url_where2go,$url_pr,$url_limits,$url_search);
     	 }	 
	
		 if (isset($filter_mpn) && !empty($filter_mpn)) {

			$filtros_seleccionados['filter_mpn']=$this->model_module_supercategorymenuadvanced->GetProductInfosAsearchSelected($values_in_filter,'mpm',11,$filter_mpn,'p',$url_where2go,$url_pr,$url_limits,$url_search);
		 }		
      
	     if (isset($filter_jan) && !empty($filter_jan)) {

			$filtros_seleccionados['filter_jan']=$this->model_module_supercategorymenuadvanced->GetProductInfosAsearchSelected($values_in_filter,'jan',12,$filter_jan,'j',$url_where2go,$url_pr,$url_limits,$url_search);
     	 }	 
			
		
		//echo "<pre>";
		//print_r($filtros_seleccionados);
		//echo "</pre>";
		
		 // REMOVE FILTERS CHECKED ON MENU
		$data['values_selected']= (!$asearch_filters) ? '' : $filtros_seleccionados;
		
		if (isset($this->request->get['keyword'])) {
			$this->document->setTitle($data['heading_title'] .  ' - ' . $this->request->get['keyword']);
		} else {
		    $this->document->setTitle($data['heading_title']);
		}
		
		
		$data_filter = array(
				'filter_manufacturers_by_id'=> $filter_manufacturer_id,
				'filter_category_id'    	=> $filter_category_id,  
				'filter_min_price'  		=> $filter_min_price,
				'filter_max_price'  	 	=> $filter_max_price, 
				'filter_stock_id'    	    => $filter_stock_id, 
				'filter_by_name' 			=> substr($filter_by_name,0,-3),
				'filter_ids'				=> substr($filter_ids,0,-1),
				'filter_name'         		=> $search, 
				'filter_tag'          		=> $tag, 
				'filter_description'  		=> $description,
				'filter_sub_category' 		=> $sub_category, 
				'filter_stock'				=> $filter_stock,
				'filter_special'			=> $filter_special, 
				'filter_clearance'			=> $filter_clearance,
				'filter_arrivals'			=> $filter_arrivals,
				'filter_width' 				=> $filter_width,
				'filter_height'				=> $filter_height,
				'filter_length' 			=> $filter_length,
				'filter_model' 				=> $filter_model,
				'filter_sku' 				=> $filter_sku,
				'filter_upc' 				=> $filter_upc,
				'filter_location'			=> $filter_location,
				'filter_option_id' 			=> $filter_option_id,
				'filter_attribute_id'		=> $filter_attribute_id,				
				'filter_productinfo_id'		=> $filter_productinfo_id,
				'filter_options_by_ids'		=> $filter_options_by_ids,
				'filter_weight'				=> $filter_weight,
				'filter_ean'				=> $filter_ean,				
				'filter_isbn'				=> $filter_isbn,
				'filter_mpn'				=> $filter_mpn,
				'filter_jan'				=> $filter_jan,				
			    'filter_rating'				=> $filter_rating,	
				
			);
	
		//List of products filtered
		$productos_filtrados= $this->model_module_supercategorymenuadvanced->getProductsFiltered($data_filter,$data_settings,$what);
		
		//echo "total productos from aserch".count($productos_filtrados);
		$product_total = count($productos_filtrados);
		
		$data['totales']= $totales ? count($productos_filtrados) : '';			
	
		$data['categories'] = '';


		   if (!empty($productos_filtrados)){
		
		
				if ($data['is_categories']){ // NO CATEGORY NAV IN 
						
					$data['categories'] = array();
					
					$results = $this->model_module_supercategorymenuadvanced->getCategoriesFiltered($productos_filtrados,$data_filter);
					
					foreach ($results as $result) {
						
						if ($result['image']) {
		
							$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
						} else {
							$image = '';
						}
																	
						$data['categories'][] = array(
							'name'  => $result['name'],
							'href'  => $this->url->link('product/asearch', 'path=' . $this->request->get['path'] . '_' . $result['category_id']).$url_pr .$url_search,
							'ajax_url'  => 'path=' . $this->request->get['path'] . '_' . $result['category_id'] .$url_pr.$url_search,
							'total'	=> $result['total'],
							'image' =>$image,
							'thumb' =>$image,
						);
					}
			}
				
		
		   $pagination_filters = array(
			'products'					=> implode(",",$productos_filtrados),
			'sort'                		=> $sort,
			'order'               		=> $order,
			'start'               		=> ($page - 1) * $limit,
			'limit'               		=> $limit,
		   );


		    $results= $this->model_catalog_asearch->getProducts($pagination_filters);
			
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
				} else {
					$image = false;
				}
				
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}
				
				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}	
				
				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
				} else {
					$tax = false;
				}				
				
				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}
			
					/*if ($link_product){
								
						$href=$this->url->link('product/product', $url_where2go . '&product_id=' . $result['product_id']);
						
					}else{
					*/	
						$href=$this->url->link('product/product','product_id=' . $result['product_id']);
						
				//	}	
		
		
				$options_images_data=false;
				if ($option_images) {
					$options_images_data=$this->model_module_optionsimagesaddon->getProductOptions($result['product_id'],$opt_settings['image_option_optionid']);
				
					if ($filter_opt){
				
				    //first comprobamos que la option este seleccionada en image_option_option_id
					$we=0;
					
					$explode_data2= explode(",",$filter_option_id);
					$explode_data3= explode(",",$filter_options_by_ids);
								
					foreach ($explode_data2 as $opt_id){
						
						if (in_array($opt_id, $opt_settings['image_option_optionid'])) {
							
							$image=$this->model_catalog_asearch->GetImageForOption($opt_id,$explode_data3[$we],$result['product_id']);
								
						}
					$we++;						
						
					}
					}
			
				}
				
				$productAttributes=array();
				$product_attributes=$this->db->query("SELECT * FROM ".DB_PREFIX."product_attribute WHERE product_id='".$result['product_id']."' ");

				if($product_attributes->num_rows){
					foreach ($product_attributes->rows as $attribut) {
						$productAttributes[]=array(
							'text'=>$this->getAttributeNameById($attribut['attribute_id']),
							'value'=>$attribut['text']
						);
					}
				}

			
				$data['products'][] = array(
					'product_id'  => $result['product_id'],
					'model'  => $result['model'],
					'thumb'       => $image,
					'optionimages'=> $options_images_data,
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
					'price'       => ($modecatalog) ? false : $price,
					'special'     => $special,
					'tax'         => $tax,
					'product_attributes'=>$productAttributes,
					 'unit' => " per " . strtolower($result['unit_singular']),
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $result['rating'],
					'reviews'     => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
					'href'        => $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'])
				);
			}
					
		   
		   
		   $data['sorts'] = array();
			
			$url = '';
			$url .= $url_search.$url_pr;
			
			/* $data['sorts'][] = array(
				'text'  => $this->language->get('text_default'),
				'value' => 'p.sort_order-ASC',
				'href'  => $this->url->link('product/asearch', $url_where2go . '&sort=p.sort_order&order=ASC' . $url)
			); */

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href'  => $this->url->link('product/asearch', $url_where2go . '&sort=pd.name&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href'  => $this->url->link('product/asearch', $url_where2go . '&sort=pd.name&order=DESC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_asc'),
				'value' => 'p.price-ASC',
				'href'  => $this->url->link('product/asearch', $url_where2go . '&sort=p.price&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_desc'),
				'value' => 'p.price-DESC',
				'href'  => $this->url->link('product/asearch', $url_where2go . '&sort=p.price&order=DESC' . $url)
			);

			if ($this->config->get('config_review_status')) {
				/* $data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => $this->url->link('product/asearch', $url_where2go . '&sort=rating&order=DESC' . $url)
				);

				$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_asc'),
					'value' => 'rating-ASC',
					'href'  => $this->url->link('product/asearch', $url_where2go . '&sort=rating&order=ASC' . $url)
				); */
			}

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_asc'),
				'value' => 'p.model-ASC',
				'href'  => $this->url->link('product/asearch', $url_where2go . '&sort=p.model&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_desc'),
				'value' => 'p.model-DESC',
				'href'  => $this->url->link('product/asearch', $url_where2go . '&sort=p.model&order=DESC' . $url)
			);
			
	
		
        	if (isset($this->request->get['sort'])) {
				$url .= '&amp;sort=' . $this->request->get['sort'];
			}	
	
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
				
			$data['limits'] = array();
	
			$limits = array_unique(array($this->config->get('config_catalog_limit'), 25, 50, 75, 100));
			
			sort($limits);
	
			foreach($limits as $value) {
				$data['limits'][] = array(
					'text'  => $value,
					'value' => $value,
					'href'  => $this->url->link('product/asearch', $url_where2go . $url . '&limit=' . $value)
					
				);
			}
				
							
			$url = '';
			$url .= $url_search.$url_pr;
			

										
			if (isset($this->request->get['sort'])) {
				$url .= '&amp;sort=' . $this->request->get['sort'];
			}	
	
			if (isset($this->request->get['order'])) {
				$url .= '&amp;order=' . $this->request->get['order'];
			}
			
			if (isset($this->request->get['limit'])) {
				$url .= '&amp;limit=' . $this->request->get['limit'];
			}
		
			
			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('product/asearch',$url_where2go.'&page={page}') . $url;
			$data['pagination'] = $pagination->render();
			
            $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

			$valid_sorts = array('pd.name-ASC','pd.name-DESC','p.price-ASC','p.price-DESC','p.model-ASC','p.model-DESC');
			foreach($data['sorts'] as $k => $sort)
			{
				if( !in_array( $sort['value'], $valid_sorts ) )
				{
					unset($data['sorts'][$k]);
				}
			}
			//Load filter settings.
			$data['ocscroll']='';
			$settings_module=$this->config->get('supercategorymenuadvanced_settings');
			if($settings_module['ocscroll']){
				$this->load->model('module/ocscroll');
				$data['ocscroll']=$this->model_module_ocscroll->setocScroll();
			}
		   
		    $data['sort'] = $sort;
			$data['order'] = $order;
			$data['limit'] = $limit;

			$data['continue'] = $this->url->link('common/home');
	   } //if (!empty($productos_filtrados)){
		
		
		if($data['is_ajax'] && isset($this->request->get['a'])){
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			//$data['footer'] = $this->load->controller('common/footer');
			//$data['header'] = $this->load->controller('common/header');	
			
			if ($data['column_left'] && $data['column_right']) { 
    			$data['class'] = 'col-sm-6'; 
    		} elseif ($data['column_left'] || $data['column_right']) { 
    			 $data['class'] = 'col-sm-9';
    		} else {	
    			$data['class'] = 'col-sm-12';	
   			} 	
			
			$what_template="asearch_a.tpl";
				
		}else{
			
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
			$what_template="asearch.tpl";
		}

		//unset($data["categories"]);
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/'.$what_template)) {
			
			return $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/'.$what_template, $data));
		} else {
			
			return $this->response->setOutput($this->load->view('default/template/product/'.$what_template, $data));
			
		}
		
		
		
		
	}
	
	public function getAttributeNameById($attribute_id){
			$attr_name=$this->db->query("SELECT name FROM ".DB_PREFIX."attribute_description WHERE attribute_id='".$attribute_id."'  ");

			return $attr_name->row['name'];

	}

function Manufacturer_breadcrumbs($id,$url_limits){
		
		$this->load->model('catalog/manufacturer');
			//set right filter
			
			$url_seo="manufacturer_id=".$id;
					   
		   //then check if we are in manufacturer page and we have select a category
		    if (isset($this->request->get['path'])){
	        $path = '';
			$parts = explode('_', (string)$this->request->get['path']);
			$filter_in_category_id = array_pop($parts);
	        }
	   
	   
	   		//creating the right breadcrumbs
	   		$breadcrumbs = array();

	   		$breadcrumbs[] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        	'separator' => false
      			);
		
		
		
			$breadcrumbs[] = array(
				'text'      => $this->language->get('text_brand'),
				'href'      => $this->url->link('product/manufacturer'),
				'separator' => $this->language->get('text_separator')
			);
	   
	   
	 
	   	$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($id);
	
		if ($manufacturer_info) {
	   		
			$breadcrumbs[] = array(
       			'text'      => $manufacturer_info['name'],
				'href'      => $this->url->link('product/asearch', $url_seo . $url_limits),
      			'separator' => $this->language->get('text_separator')

   			);
	   
	   
		}
	   
	   
		return $breadcrumbs;
	}
	function Category_breadcrumbs($id,$url_limits){
	   
		$this->load->model('catalog/category');
		$url_seo="path=".$id;
	   	
		$breadcrumbs = array();
   		$breadcrumbs[] = array( 
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
      		'separator' => false
   		);
		
   		$breadcrumbs[] = array( 
       		'text'      => $this->getSeoWord('category_id=0'),
			'href'      => $this->url->link('product/asearch'),
      		'separator' => false
   		);
		
		$path = '';
		$parts = explode('_', (string)$this->request->get['path']);
		foreach ($parts as $path_id) {
			  if (!$path) {
				$path = $path_id;
			  } else {
				$path .= '_' . $path_id;
			  }
			 
			$category_info = $this->model_catalog_category->getCategory($path_id);
					
				if ($category_info) {
					$title_name=$category_info['name'];
					$breadcrumbs[] = array(
						'text'      => $category_info['name'],
						'href'      => $this->url->link('product/category', 'path=' . $path_id. $url_limits),
						'separator' => $this->language->get('text_separator')
					);
				}
				
			}		
		
		
	
		return $breadcrumbs;
	}
	
	 public function getSeoWord($string) { $query = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "url_alias u WHERE u.query = '". $string ."'"); 
 if ($query->num_rows) { return $query->row['keyword']; }else{ return false; } } 
	
	
	
	
	
	
	
	}
?>	