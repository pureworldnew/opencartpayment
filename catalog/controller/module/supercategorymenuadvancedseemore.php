<?php  
class ControllerModuleSuperCategoryMenuAdvancedSeeMore extends Controller {
	
	public function SeeMore() { 

		 $this->load->model('module/supercategorymenuadvanced');
	     $data['store_id'] =$STORE= $this->config->get('config_store_id');
	     $settings_module=  $this->model_module_supercategorymenuadvanced->getMySetting('SETTINGS_'.$STORE,$STORE);
		
		
		$this->language->load('module/supercategorymenuadvanced');
		
		
		$data['see_more_text'] 		= $this->language->get('see_more_text');
		$data['remove_filter_text'] 	= $this->language->get('remove_filter_text');
		$data['pricerange_text'] 		= $this->language->get('pricerange_text');
		$data['no_data_text'] 		= $this->language->get('no_data_text');
		$data['manufacturer_text'] 	= $this->language->get('manufacturer_text');
		$data['category_text'] 		= $this->language->get('category_text');
	    $data['search_in'] 			= $this->language->get('search_in');
	    $rating_text 					= $this->language->get('rating_text');
		
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
		//serach engine

		$url_where2go_brands=$filter_search=$filter_tag=$filter_description=$filter_subcategory=$urlfilter='';
		
		
		//this will contain all filters we need
		$super_url = $url_pr = $url_search = $url_limits = '';
		
		$url_top_filters='';
		
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
			
		if(isset($this->request->get['filtering'])) {
			 $what=$this->request->get['filtering'];
			 $url_top_filters.= '&filtering=' . $this->request->get['filtering'];
			 
		 }
		 
		if ($what=="M"){
			$values_in_filter= $this->config->get('VALORESM_'.$filter_manufacturer_id, (int)$this->config->get('config_store_id') );
			//$url_where2go_brands='manufacturer_id='.$id;
		}elseif($what=="C"){
			$values_in_filter= $this->config->get('VALORES_'.$filter_category_id, (int)$this->config->get('config_store_id') );
		}
		
		
		//filter_att[3]=Clockspeed-100mhz-n
		if(isset($this->request->get['filter_att'])) {
			
			$filter_att=true;			
			
			foreach ($this->request->get['filter_att'] as $key=>$value){
			
				$url_top_filters.= '&filter_att['.$key.']=' . urlencode(html_entity_decode($value, ENT_QUOTES, 'UTF-8'));
				$att_info=$values_in_filter['attributes'][$key];
			
			       	$att_name= $att_info['name'];
					$att_view= $att_info['view'];
					$att_separator= $att_info['separator'];
					
			if ($att_separator!="no"){
				$ss="p";
			}else{
				$ss=$this->model_module_supercategorymenuadvanced->GetView($att_view);
			}
						
				$filter_attribute_id.=$key.",";
				$filter_by_name.=$this->model_module_supercategorymenuadvanced->CleanName($value).$ss."ATTNNATT@@@";	
				$filter_attributes_by_name.=$value.$ss."ATTNNATT";		
				$filter_ids.=$key.",";			
				$filter_options_by_ids.="0,";		
			}
			 
		 }
		
		
		//filter_att[3]=Clockspeed-100mhz-n
		if(isset($this->request->get['filter_opt'])) {
			
			$filter_opt=true;			
			
			foreach ($this->request->get['filter_opt'] as $key=>$value){
			
				$url_top_filters.= '&filter_opt['.$key.']='. urlencode(html_entity_decode($value, ENT_QUOTES, 'UTF-8'));
				$opt_info=$values_in_filter['options'][$this->model_module_supercategorymenuadvanced->GetOptionid($key)];
			
			       	$opt_name= $opt_info['name'];
					$opt_view= $opt_info['view'];
										
				
				//$filter_options_by_name.=$name."OPTTOP";		
						$filter_option_id.=$this->model_module_supercategorymenuadvanced->GetOptionid($key).",";	
						$filter_by_name.=$this->model_module_supercategorymenuadvanced->CleanName($value)."OPTTOP@@@";
						$filter_ids.=$key.",";
						$filter_options_by_ids.=$key.",";
						
			}
			 
		 }
		
		 
	    //	echo "<h1>url_wher2go with all filter: <br>";
		$url_where2go=$url_top_filters;
		//echo "</h1><br>";
		
		// $url_where2go=$url_top_filters;
		
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
		$option_stock		= isset($settings_module['general_data']['option_stock'])? $settings_module['general_data']['option_stock'] : false;
		$nodata				= isset($settings_module['general_data']['nodata'])? $settings_module['general_data']['nodata'] : true;		
		$data_settings=array(
			'nofollow'			=> $nofollow,
			'track_google' 		=> $track_google,
			'count_products'	=> $count_products,
			'option_stock' 		=> $option_stock,
			'clearance'			=> isset($settings_module['stock']['clearance_value']) ? $settings_module['stock']['clearance_value'] : '',
			'days'				=> isset($settings_module['stock']['number_day']) ? $settings_module['stock']['number_day'] : 7,
			'reviews' 			=> $settings_module['reviews']['tipo']
			
		);
		
		//Load AJAX settings.
		if (isset($settings_module['ajax']['enable'])){
			$is_ajax=$data['is_ajax']=$settings_module['ajax']['enable'];
		}else{
			$is_ajax=$data['is_ajax']=0;
		}
		//check if loader is selected
		if (isset($settings_module['ajax']['loader'])){
			$data['loader']=$settings_module['ajax']['loader'];
		   if (!empty($settings_module['ajax']['loader_image']) && ($settings_module['ajax']['loader_image']) ){
			   $data['loader_image']=HTTP_SERVER.'image/supermenu/loaders/'.$settings_module['ajax']['loader_image'];
		   }else{
		   	   $data['loader_image']=HTTP_SERVER.'image/supermenu/loaders/103.png';
		   }
		}else{
			$data['loader']=false;
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
		
			
		if (isset($this->request->get['sort'])) {
			$url_limits.= '&sort=' . $this->request->get['sort'];
		}	
		if (isset($this->request->get['order'])) {
			$url_limits.= '&order=' . $this->request->get['order'];
		}
		if (isset($this->request->get['limit'])) {
			$url_limits.= '&limit=' . $this->request->get['limit'];
		}
		if (isset($this->request->get['page'])) {
			$url_limits.= '&page=' . $this->request->get['page'];
		}
		
		
		if (isset($this->request->get['pr'])) {
			$url_pr.= '&pr=' . $this->request->get['pr'];
	        $filter_price_range=$this->request->get['pr'];
		
		}
		
			
		$filter_coin=$this->session->data['currency'];
			
		
		//PRICE RANGE
			if (isset($filter_price_range) and !empty($filter_price_range)) {
				
				list($filter_min_price,$filter_max_price)=explode(";",$filter_price_range);
											
				//remove currency from price
				$filter_min_price=floor($this->model_module_supercategorymenuadvanced->UnformatMoney($filter_min_price,$filter_coin)); 
				$filter_max_price=ceil($this->model_module_supercategorymenuadvanced->UnformatMoney($filter_max_price,$filter_coin));
				//remove tax from price
				 if ($this->config->get('config_tax') && $settings_module['pricerange']['setvat']) {
					    $tax_value= $this->tax->calculate(1, $settings_module['pricerange']['tax_class_id'], $this->config->get('config_tax'));
						$filter_min_price=floor( $filter_min_price/$tax_value ); 
						$filter_max_price=ceil( $filter_max_price/$tax_value );
				 }
				 
			} //END PRICE RANGE 
			
	
	
		//array with filters to search in database.
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
				'filter_see_more'			=> $this->request->get['tipo'].$this->request->get['id'].str_replace("&amp;","&",$this->request->get['name']).$filter_manufacturer_id.$this->request->get['filtering']
			);


		$productos_filtrados= $this->model_module_supercategorymenuadvanced->getProductsFiltered($data_filter,$data_settings,$what);

		$total_productos=count($productos_filtrados);
		
		$que_buscar=$this->request->get['tipo'];
	    $nombre_seleccionado=str_replace("&amp;","&",$this->request->get['name']);  
		//$dnd=$this->request->get['dnd'];
		$id=$this->request->get['id'];
		$product_infos_values=array("w","h","l","sk","up","lo","mo","wg","e","i","p","j");
		
		if($que_buscar=="m"){
         
		 	//check admin configuration
			if ($settings_module['manufacturer']['enable']){
				
				$manufactures = array();
				$results = $this->model_module_supercategorymenuadvanced->getManufacturesFiltered($productos_filtrados,$data_filter);
					
 				if(!empty($results)){
					
					foreach ($results as $result) {
						
						$string_filtering="&manufacturer_id=".$result['manufacturer_id'];
						$filter_url=$this->url->link('product/asearch', $string_filtering.$url_where2go).$url_pr.$url_limits.$url_search;
						$ajax_url=$url_where2go.$url_pr.$url_limits.$url_search;

						$is_selected=0;
											
					   // $url_where2go_clean=str_replace("&manufacturer_id=".$filter_manufacturer_id,"",$url_where2go);
					
					   if ($result['manufacturer_id']==$id){//is selected
								$filter_url=$this->url->link('product/asearch', $url_where2go).$url_pr.$url_limits.$url_search;										
								$ajax_url=$url_where2go.$url_pr.$url_limits.$url_search;
								$is_selected=1;
							
						}else{ //no es seleccionado
						
							$filter_url=$this->url->link('product/asearch', $url_where2go.$string_filtering).$url_pr.$url_limits.$url_search;
							$ajax_url=$url_where2go.$url_pr.$url_limits.$url_search.$string_filtering;
						
						}
						
						
						$manufactures_final["str".$result['name']] = array(
							'manufacturer_id'=> $result['manufacturer_id'],
							'seleccionado' => ($is_selected) ? "is_seleccionado" : "no_seleccionado",
							'name'    	     => $result['name'],
							'total'		     => $result['total'],
							'href'    	     => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
							'ajax_url'	     => $ajax_url,
							'tipo'			 => "MANUFACTURER",
							'order'			 => $result['order'],
							
						);
						
					}
		
				$manufactures_ord = array_values($this->model_module_supercategorymenuadvanced->OrderArray($manufactures_final,$settings_module['manufacturer']['order']));
				
				$manufactures[] = array(
					'name'    	     => $data['manufacturer_text'] ,
					'total'		     => count($manufactures_final),
					'jurjur'    	 => $manufactures_ord,
					'list_number' 	 => $settings_module['manufacturer']['list_number'],
				    'order'			 => $settings_module['manufacturer']['order'],
				    'view'			 => $settings_module['manufacturer']['view'],
					'initval'	     => isset($settings_module['manufacturer']['initval']) ? $settings_module['manufacturer']['initval']  : "opened",
					'searchinput'    => isset($settings_module['manufacturer']['searchinput']) ? $settings_module['manufacturer']['searchinput'] : "no",
					'tipo'			 => "MANUFACTURER",	
				);
						
				$data['values_no_selected'][]=$manufactures;
						
				}// end !empty results
				
	
			}// end if ($settings_module['manufacturer']['enable']){
		
		
		
		}elseif($que_buscar=="ra"){
         
		 	//check admin configuration
			if($settings_module['reviews']['enable']){
				
				$data['re_extra_text']=$this->language->get('rating_text_'.$settings_module['reviews']['tipo']);
			
				$reviews_final=array();	
			    $results = $this->model_module_supercategorymenuadvanced->getReviewsFiltered($productos_filtrados,$data_filter,$settings_module['reviews']['tipo']);
					
				
				if(!empty($results)){
					
					foreach ($results as $result) {
						
						$string_filtering="&filter_rating=".$result['rating'];

						$review_name=$result['rating'];
						

						$is_selected=0;
											
					   // $url_where2go_clean=str_replace("&manufacturer_id=".$filter_manufacturer_id,"",$url_where2go);
					
					   if ($review_name==$nombre_seleccionado){//is selected
								$filter_url=$this->url->link('product/asearch', $url_where2go).$url_pr.$url_limits.$url_search;										
								$ajax_url=$url_where2go.$url_pr.$url_limits.$url_search;
								$is_selected=1;
							
						}else{ //no es seleccionado
						
							$filter_url=$this->url->link('product/asearch', $url_where2go.$string_filtering).$url_pr.$url_limits.$url_search;
							$ajax_url=$url_where2go.$url_pr.$url_limits.$url_search.$string_filtering;
							
						
						}
						
						$reviews_final["str".$result['rating']] = array(
							'reviews_id'=> $result['rating'],
							'seleccionado' => ($is_selected) ? "is_seleccionado" : "no_seleccionado",
							'name'    	     => $result['rating'],
							'total'		     => $result['total'],
							'href'    	     => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
							'ajax_url'	     => $ajax_url,
							'tipo'			 => "REVIEWS",
							
							
						);
						
					}
		
				$reviews[] = array(
					'name'    	     => $rating_text,
					'total'		     => count($reviews_final),
					'jurjur'    	 => $reviews_final,
					'list_number' 	 => 10000000000000000000000,
				    'order'			 => '',
				    'view'			 => 'rimage',
					'initval'	     => isset($settings_module['reviews']['initval']) ? $settings_module['reviews']['initval']  : "opened",
					'searchinput'    => "no",
						'tipo'			 => "REVIEWS",
				);
						
				$data['values_no_selected'][]=$reviews;
						
				
				}// end !empty results
				
	
			}// end if($settings_module['reviews']['enable']){ 
		
		
		 }elseif ($que_buscar=="a"){
		
				$attribute_we_want_info=array($id=>$id);	
		
				//FILTER ATRIBUTES.
				//Get attributes filtered
				$results = $this->model_module_supercategorymenuadvanced->getAtributesFiltered($productos_filtrados,$data_filter,$attribute_we_want_info);
				
				$att_info=$values_in_filter['attributes'][$id];
				
				$attribute_name= $att_info['name'];	
				$attribute_id=$att_info['attribute_id'];
				$attribute_number=$att_info['number'];
				$attribute_sort_order=$att_info['sort_order'];
				$attribute_orderval=$att_info['orderval'];
				$attribute_separator=$att_info['separator'];
				$attribute_view=$att_info['view'];
				$attribute_initval=isset($att_info['initval']) ? $att_info['initval']  : "opened";
				$attribute_searchinput=isset($att_info['searchinput']) ? $att_info['searchinput']  : "no";
			
				$attribute_values = $results[$id];
					
				if ($attribute_separator!="no"){
							$new_array_values= array();
								if($attribute_values){
								foreach ($attribute_values as $attribute_value){
										
									$attributes = explode($attribute_separator, $attribute_value['text']);	
										$total=0;			
										foreach ($attributes as $attribute) {
											
											if (array_key_exists(trim($attribute), $new_array_values)) {
												$new_array_values[trim($attribute)]=array(
													'text'=>trim($attribute),
													'total'=>$attribute_value['total'] + $new_array_values[trim($attribute)]['total'],
													'separator'=>'YES'
												);
											}else{
												$new_array_values[trim($attribute)]=array(
													'text'=>trim($attribute),
													'total'=>$attribute_value['total'],
													'separator'=>'YES'
												);
											}
										}
									
								}
								$attribute_values=$new_array_values;
								}
						}
			
			
			 $atributos_final = array();
			 	$explode_var =array();
				if(strpos($nombre_seleccionado,"|") !==false){
					foreach (explode("|",$nombre_seleccionado) as $key => $value) {
						$explode_var[$value] = $value;
					}
				}
					

				if($attribute_values){ 
					foreach ($attribute_values as $attribute_value){
													
						//check if attribute value have no data:
					   $attribute_value_name= ($attribute_value['text']=="") ? "NDDDDDN" : $attribute_value['text'];
					   
					 	if (!$nodata && $attribute_value['text']==""){
							continue;
						}

					    $is_selected=0;
					   
					   if(!empty($explode_var)){
					   		$matc_name = $this->model_module_supercategorymenuadvanced->Ampersand($explode_var[$attribute_value_name]);
					   }else{
					   		$matc_name = $this->model_module_supercategorymenuadvanced->Ampersand($nombre_seleccionado);
					   }
					   
					   if ($attribute_value_name== $matc_name){
								$filter_url=$this->url->link('product/asearch', $url_where2go).$url_pr.$url_limits.$url_search;										
								$ajax_url=$url_where2go.$url_pr.$url_limits.$url_search;
								$is_selected=1;
							
						}else{ //no es seleccionado
						
							$string_filtering="&filter_att[".$attribute_id."]=".urlencode($attribute_value_name);
						
							$filter_url=$this->url->link('product/asearch', $url_where2go.$string_filtering).$url_pr.$url_limits.$url_search;
							$ajax_url=$url_where2go.$url_pr.$url_limits.$url_search.$string_filtering;
							
						
						}
							
						$namer=$attribute_value['text']=="" ? $attribute_value_name=$this->data['no_data_text'] : $attribute_value_name=$attribute_value['text'];
						     $atributos_final['str'.(string)$namer] = array(
								'name'    	   => $namer,
								'attribute_id'		   => $attribute_id,	
								'href'         => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
								'total'		   => $attribute_value['total'],
								'ajax_url'	   => $ajax_url,
								'seleccionado' => ($is_selected) ? "is_seleccionado" : "no_seleccionado",
							);
					}
			
				}//
			
			
			
			$attributes_ord = array_values($this->model_module_supercategorymenuadvanced->OrderArray($atributos_final,$attribute_orderval));
			
			   if(!empty($atributos_final)){
					$atributos[$attribute_id] = array(
						'attribute_id' => $attribute_id,
						'name'    	   => html_entity_decode($attribute_name),
						'total'		   => count($atributos_final), 
						'ajax_url'	   => $ajax_url,
						'tipo'         => 'ATTRIBUTE',
						'list_number'  => $attribute_number,
				       	'order'	       => $attribute_orderval,
					    'sort_order'   => $attribute_sort_order,
						'jurjur'	   => $attributes_ord,
						'view'		   => $attribute_view,
						'initval'	   => $attribute_initval,
						'searchinput'  => $attribute_searchinput,				
						);
				}
			
			
			
			
			if(!empty($atributos)){
				
				$data['values_no_selected'][]=$atributos;
			}else{
				
					$filter_url=$this->url->link('product/asearch', $url_where2go).$url_pr.$url_limits.$url_search;										
								$ajax_url=$url_where2go.$url_pr.$url_limits.$url_search;
						
							
				$atributos_final['str'] = array(
					'name'    	   => $nombre_seleccionado,
					'href'         => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
					'total'		   => "-",
					'ajax_url'	   => $ajax_url,
					'seleccionado' => "is_seleccionado",
				);
					
				$atributos[$id] = array(
						'attribute_id' => $id,
						'name'    	   => html_entity_decode($nombre_seleccionado),
						'total'		   => 1, 
						'ajax_url'	   => $ajax_url,
						'tipo'         => $que_buscar,
     					'jurjur'	   => $atributos_final,
						'view'		   => "list",
						'initval'	   => "opened",
						'searchinput'  => "no",	
						);
					
				$data['values_no_selected'][]=$atributos;
				
			}	
			
			
			}elseif (in_array($que_buscar,$product_infos_values)){
		
		
				$product_info_we_want_info=array($id=>$id);	
				
				
		
			    $pro_info=$values_in_filter['productinfo'][$id];
							
			    $product_info_name=utf8_strtolower($pro_info['name']);	
				$product_info_id=$pro_info['productinfo_id'];
				$product_info_number=$pro_info['number'];
				$product_info_sort_order=$pro_info['sort_order'];
                $product_info_orderval=$pro_info['orderval'];
                //$product_info_separator=$pro_info['separator'];
				$product_info_view=$pro_info['view'];
				$product_info_initval=$pro_info['initval'];
				$product_info_searchinput=$pro_info['searchinput'];
			
			$results = $this->model_module_supercategorymenuadvanced->getProductInfosFiltered($productos_filtrados,$data_filter,$product_info_name,$id);
			    
				
				
				//GET ALL PRODUCT INFO VALUES FILTERED
			    $product_info_values = $results[$product_info_id];
			
			    $productos_info_final = array();

				if($product_info_values){ 
					foreach ($product_info_values as $product_info_value){
			
					  $product_info_value_name= ($product_info_value['text']=="") ? "NDDDDDN" : $product_info_value['text'];
						
						if (!$nodata && $product_info_value['text']==""){
							continue;
						}	
						
						$is_selected=0;
				    	if ($product_info_value_name==$this->model_module_supercategorymenuadvanced->Ampersand($nombre_seleccionado)){
						//if ($option_id==$id){	
						$is_selected=1;
							//reset filter 
							$filter_url=$this->url->link('product/asearch', $url_where2go).$url_pr.$url_limits.$url_search;										
							$ajax_url=$url_where2go.$url_pr.$url_limits.$url_search;
						}else{ //no es seleccionado
							$string_filtering="&filter_".$product_info_name."=".urlencode($product_info_value_name);
							$filter_url=$this->url->link('product/asearch', $url_where2go.$string_filtering).$url_pr.$url_limits.$url_search;
							$ajax_url=$url_where2go.$url_pr.$url_limits.$url_search.$string_filtering;
						}
					
			
				$namer=$product_info_value['text']=="" ? $product_info_value_name=$this->data['no_data_text'] : $product_info_value_name=$product_info_value['text'];						    
														
				if ($que_buscar=="w" || $que_buscar=="h" || $que_buscar=="l"){
			  
				   $val=$this->length->format($namer, $this->config->get('config_length_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
			  
				}elseif($que_buscar=="wg" ) { 
			  
				   $val=$this->weight->format($namer, $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
			  
				}else{
					
					$val=$namer;
					
				}
				
			
			     $productos_info_final['str'.(string)$namer] = array(
								'name'    	   => $namer,
								'href'         => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
								'total'		   => $product_info_value['total'],
								'ajax_url'	   => $ajax_url,
								'seleccionado' => ($is_selected) ? "is_seleccionado" : "no_seleccionado",
								'val_formatted' =>$val
							);
					
			
				}//if($product_info_values){ 
			
			}
			$product_infos_ord = array_values($this->model_module_supercategorymenuadvanced->OrderArray($productos_info_final,$product_info_orderval));
			   if(!empty($productos_info_final)){
					$productos_info[$product_info_id] = array(
						'product_info_id' => $product_info_id,
						'name'    	   => html_entity_decode($product_info_name),
						'total'		   => count($productos_info_final), 
						'ajax_url'	   => $ajax_url,
						'tipo'         => 'product_info',
						'list_number'  => $product_info_number,
				       	'order'	       => $product_info_orderval,
					    'sort_order'   => $product_info_sort_order,
						'jurjur'	   => $product_infos_ord,
						'view'		   => $product_info_view,
						'initval'	   => $product_info_initval,
						'searchinput'  => $product_info_searchinput,				
						);
				}
			
			
			if(!empty($productos_info)){
				
				$data['values_no_selected'][]=$productos_info;
			}else{
				
				$filter_url=$this->url->link('product/asearch', $url_where2go).$url_pr.$url_limits.$url_search;										
				$ajax_url=$url_where2go.$url_pr.$url_limits.$url_search;
							
				$productos_info_final['str'] = array(
					'name'    	   => $nombre_seleccionado,
					'href'         => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
					'total'		   => "-",
					'ajax_url'	   => $ajax_url,
					'seleccionado' => "is_seleccionado",
				);
					
				$productos_info[$id] = array(
						'product_info_id' => $id,
						'name'    	   => html_entity_decode($nombre_seleccionado),
						'total'		   => 1, 
						'ajax_url'	   => $ajax_url,
						'tipo'         => $que_buscar,
     					'jurjur'	   => $productos_info_final,
						'view'		   => "list",
						'initval'	   => "opened",
						'searchinput'  => "no",	
						);
					
				$data['values_no_selected'][]=$productos_info;
				
			}	
			
			
			}elseif ($que_buscar=="o"){
			
			$options_we_want_info=array($id=>$id);	
			
			$id2=$this->request->get['id2'];	
			
			//FILTER OPTIONS.
			//Get options filtered
			$results = $this->model_module_supercategorymenuadvanced->getOptionsFiltered($productos_filtrados,$data_filter,$options_we_want_info);
			
				$opt_info=$values_in_filter['options'][$id];
		
				$option_name=$opt_info['name'];		
				$option_id=$opt_info['option_id'];
			    $option_number=$opt_info['number'];
				$option_sort_order=$opt_info['sort_order'];
                $option_orderval=$opt_info['orderval'];
               // $option_separator=$opt_info['separator'];
				$option_view=$opt_info['view'];
				$option_initval= isset($opt_info['initval']) ? $opt_info['initval']  : "opened";
				$option_searchinput=isset($opt_info['searchinput']) ? $opt_info['searchinput']  : "no";
			
			
				
				//GET ALL OPTIONS VALUES FILTERED
			    $options_values = $results[$option_id];	
				
				$options_final = array();
			
			if($options_values){ 
					foreach ($options_values as $option_value){
					
						$option_value_id=$option_value['option_value_id'];		
						//check if option value have no data:
						$option_value['text']=="" ? $option_value_name="NDDDDDN" : $option_value_name=$option_value['text'];						
						
						if (!$nodata && $option_value['text']==""){
							continue;
						}	
						
						$is_selected=0;	
						//if ($option_value_name==$nombre_seleccionado){
						if ($option_value_id==$id2){	
							
							
							$is_selected=1;
							$filter_url=$this->url->link('product/asearch', $url_where2go).$url_pr.$url_limits.$url_search;										
							$ajax_url=$url_where2go.$url_pr.$url_limits.$url_search;
							
						}else{ //no es seleccionado
						
							$string_filtering="&filter_opt[".$option_value_id."]=".urlencode($option_value_name);
						
							$filter_url=$this->url->link('product/asearch', $url_where2go.$string_filtering).$url_pr.$url_limits.$url_search;
							$ajax_url=$url_where2go.$url_pr.$url_limits.$url_search.$string_filtering;
															
						}
						
						
						$namer=$option_value['text']=="" ? $no_data_text : $option_value['text'];
						
						$options_final['str'.(string)$namer] = array(
								'name'    	   => $namer,
								'seleccionado' => ($is_selected) ? "is_seleccionado" : "no_seleccionado",
								'href'         => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
								'total'		   => $option_value['total'],
								'ajax_url'	   => $ajax_url,
								'image_thumb'  => $this->model_module_supercategorymenuadvanced->getoptionImage($option_value['option_value_id'],$image_option_width,$image_option_height),
								'order'	       => $option_value['order'],
							);
					}
			
			
			
				}//
			
			
			$sort_order = array();
	  				
					 
					if ($option_value['image_thumb']){
						 foreach ($options_final as $key => $value) {
							 $sort_order[] = $value['seleccionado'];
						 }
						array_multisort($sort_order,SORT_ASC,$options_final); 
						
						$options_ord =array_values($options_final);
				
					}else{
				
						$options_ord = array_values($this->model_module_supercategorymenuadvanced->OrderArray($options_final,$option_orderval));
			
					}
					
					if(!empty($options_final)){
						$opciones[$option_id] = array(
							'option_id' => $option_id,
							'name'    	   => html_entity_decode($option_name),
							'total'		   => count($options_final), 
							'ajax_url'	   => $ajax_url,
							'tipo'         => 'OPTION',
							'list_number'  => $option_number,
							'order'	       => $option_orderval,
							'sort_order'   => $option_sort_order,
							'jurjur'	   => $options_ord,
							'view'		   => $option_view,
							'initval'	   => $option_initval,
							'searchinput'  => $option_searchinput,
							
												
							);
					}
			
			
			
			
			
			if(!empty($opciones)){
				
				$data['values_no_selected'][]=$opciones;
		}else{
				
				$filter_url=$this->url->link('product/asearch', $url_where2go).$url_pr.$url_limits.$url_search;										
				$ajax_url=$url_where2go.$url_pr.$url_limits.$url_search;
						
							
				$options_final['str'] = array(
					'name'    	   => $nombre_seleccionado,
					'href'         => $this->model_module_supercategorymenuadvanced->CleanSlider($filter_url),
					'total'		   => "-",
					'ajax_url'	   => $ajax_url,
					'seleccionado' => "is_seleccionado",
				);
					
				$opciones[$id] = array(
						'option_id' => $id,
						'name'    	   => html_entity_decode($nombre_seleccionado),
						'total'		   => 1, 
						'ajax_url'	   => $ajax_url,
						'tipo'         => $que_buscar,
						'jurjur'	   => $options_final,
						'view'		   => "list",
						'initval'	   => "opened",
						'searchinput'  => "no",	
						);
					
				$data['values_no_selected'][]=$opciones;
				
			}	
			
			
			}//quebuscar
		
		
	    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/advancedmenu/supercategorymenuadvanced_seemore.tpl')) {
			
			return	 $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/module/advancedmenu/supercategorymenuadvanced_seemore.tpl', $data));

		} else {
			
			return $this->response->setOutput($this->load->view('default/template/module/advancedmenu/supercategorymenuadvanced_seemore.tpl', $data));
			
		}
				
	}

}
?>
