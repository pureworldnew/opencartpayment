<?php

class ModelShippingXshippingpro extends Model {
	
	public function getQuote($address) {
	
		$this->load->language('shipping/xshippingpro');
		$this->load->model('catalog/product');

		$language_id=$this->config->get('config_language_id');
		$store_id=(isset($_POST['store_id']))?$_POST['store_id']:$this->config->get('config_store_id');
	
		$method_data = array();
	    $quote_data = array();
		$sort_data = array(); 
	    
	    $xshippingpro_heading=$this->config->get('xshippingpro_heading');
	    $xshippingpro_group=$this->config->get('xshippingpro_group');
		$xshippingpro_group_limit=$this->config->get('xshippingpro_group_limit');
		$xshippingpro_sub_group=$this->config->get('xshippingpro_sub_group');
		$xshippingpro_sub_group_limit=$this->config->get('xshippingpro_sub_group_limit');
		$xshippingpro_sub_group_name=$this->config->get('xshippingpro_sub_group_name');
	    $xshippingpro_debug=$this->config->get('xshippingpro_debug');
	    
	    $xshippingpro_group=($xshippingpro_group)?$xshippingpro_group:'no_group';
		$xshippingpro_group_limit=($xshippingpro_group_limit)?(int)$xshippingpro_group_limit:1;
		
		$xshippingpro_sub_group=($xshippingpro_sub_group)?$xshippingpro_sub_group:array();
		$xshippingpro_sub_group_limit=($xshippingpro_sub_group_limit)?$xshippingpro_sub_group_limit:array();
		$xshippingpro_sub_group_name=($xshippingpro_sub_group_name)?$xshippingpro_sub_group_name:array();
		
		$xshippingpro_sorting=$this->config->get('xshippingpro_sorting');
		$xshippingpro_sorting = ($xshippingpro_sorting)?(int)$xshippingpro_sorting:1;
	    
		$xshippingpro=$this->config->get('xshippingpro');
		if($xshippingpro){
		   $xshippingpro=unserialize(base64_decode($xshippingpro));  
		}
		
		$currency_code=$this->config->get('config_currency');
        $order_info='';
        if(isset($this->session->data['order_id'])){
            $this->load->model('checkout/order');
            $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        }
       if($order_info){
            $currency_code=$order_info['currency_code'];  
        }
		
		$cart_products=$this->cart->getProducts();
		$cart_weight=$this->cart->getWeight(); 
		$cart_quantity=$this->cart->countProducts();
		$cart_subtotal=$this->cart->getSubTotal();
		$cart_total=$this->cart->getTotal();
		$coupon_value=0;
		$grand_total = 0;
	
	    $xtotal_data = array();
		$xtotal = $cart_total;
		$xtaxes = $this->cart->getTaxes();
		
		$coupon = '';
		
		if (isset($_SESSION['default']['coupon']) && $_SESSION['default']['coupon']) {
		  $coupon = $_SESSION['default']['coupon'];
		}
		
		if (isset($_SESSION['coupon']) && $_SESSION['coupon']) {
		  $coupon = $_SESSION['coupon'];
		}
		
		if ($coupon) {
		    
			if ($this->config->get('coupon_status')) {
				$this->load->model('total/coupon');
                $this->{'model_total_coupon'}->getTotal($xtotal_data, $xtotal, $xtaxes);
			}
	       if(isset($xtotal_data[0]['code']) && $xtotal_data[0]['code']=='coupon'){
	         $coupon_value=$xtotal_data[0]['value'];
	       } 
		}
		
		$cart_total_without_coupon=$cart_total+$coupon_value;
		
		$cart_categories=array();
		$cart_product_ids=array();
		$cart_manufacturers=array();
		$cart_volume=0;
		$multi_category=false;
		foreach($cart_products as $inc=>$product){
			$product_categories=$this->model_catalog_product->getCategories($product['product_id']);
			$cart_product_ids[]=$product['product_id']; 
			
			$cart_products[$inc]['categories']=array();
			if($product_categories){
				 if(count($product_categories)>1)$multi_category=true;
				 foreach($product_categories as $category){
					$cart_categories[]=$category['category_id'];  
					$cart_products[$inc]['categories'][]=$category['category_id']; //store for future use 
				 } 
			 }
			
			$product_volume=(($product['width']*$product['height']*$product['length'])*$product['quantity']);		
			$cart_volume+=$product_volume; 
			$cart_products[$inc]['volume']=$product_volume; //store for future use
			$cart_products[$inc]['dimensional']=0; // just initialize for now. Will calc later for method wise
			
			
			
			$product_info=$this->model_catalog_product->getProduct($product['product_id']);
			if($product_info){
				$cart_manufacturers[]=$product_info['manufacturer_id'];
				$cart_products[$inc]['manufacturer_id']=$product_info['manufacturer_id']; //store for future use
			}
			 
		 } 
		$cart_categories=array_unique($cart_categories);
		$cart_product_ids=array_unique($cart_product_ids);
		$cart_manufacturers=array_unique($cart_manufacturers);
	    $operators= array('+','-','/','*');
		
		$debugging=array();
		$shipping_group_methods=array();
		
		if(!isset($xshippingpro['name']))$xshippingpro['name']=array();
		if(!is_array($xshippingpro['name']))$xshippingpro['name']=array();
         
        $isGrandFound = false; 
        $isSubGroupFound = false;
                
        foreach($xshippingpro['name'] as $no_of_tab=>$names){
		    
			$debugging_message=array();
		
			if(!isset($xshippingpro['customer_group'][$no_of_tab]))$xshippingpro['customer_group'][$no_of_tab]=array();
		    if(!isset($xshippingpro['geo_zone_id'][$no_of_tab]))$xshippingpro['geo_zone_id'][$no_of_tab]=array();
		 	if(!isset($xshippingpro['product_category'][$no_of_tab]))$xshippingpro['product_category'][$no_of_tab]=array();
		 	if(!isset($xshippingpro['product_product'][$no_of_tab]))$xshippingpro['product_product'][$no_of_tab]=array();
			if(!isset($xshippingpro['store'][$no_of_tab]))$xshippingpro['store'][$no_of_tab]=array();
		 	if(!isset($xshippingpro['manufacturer'][$no_of_tab]))$xshippingpro['manufacturer'][$no_of_tab]=array();
		 	if(!isset($xshippingpro['days'][$no_of_tab]))$xshippingpro['days'][$no_of_tab]=array();
		 	if(!isset($xshippingpro['rate_start'][$no_of_tab]))$xshippingpro['rate_start'][$no_of_tab]=array();
		 	if(!isset($xshippingpro['rate_end'][$no_of_tab]))$xshippingpro['rate_end'][$no_of_tab]=array();
		 	if(!isset($xshippingpro['rate_total'][$no_of_tab]))$xshippingpro['rate_total'][$no_of_tab]=array();
		 	if(!isset($xshippingpro['rate_block'][$no_of_tab]))$xshippingpro['rate_block'][$no_of_tab]=array();
		 	if(!isset($xshippingpro['rate_partial'][$no_of_tab]))$xshippingpro['rate_partial'][$no_of_tab]=array();
		 	if(!isset($xshippingpro['country'][$no_of_tab]))$xshippingpro['country'][$no_of_tab]=array();
		 
		 	if(!is_array($xshippingpro['customer_group'][$no_of_tab]))$xshippingpro['customer_group'][$no_of_tab]=array();
		 	if(!is_array($xshippingpro['geo_zone_id'][$no_of_tab]))$xshippingpro['geo_zone_id'][$no_of_tab]=array();
		 	if(!is_array($xshippingpro['product_category'][$no_of_tab]))$xshippingpro['product_category'][$no_of_tab]=array();
		 	if(!is_array($xshippingpro['product_product'][$no_of_tab]))$xshippingpro['product_product'][$no_of_tab]=array();
		 	if(!is_array($xshippingpro['store'][$no_of_tab]))$xshippingpro['store'][$no_of_tab]=array();
		 	if(!is_array($xshippingpro['manufacturer'][$no_of_tab]))$xshippingpro['manufacturer'][$no_of_tab]=array();
		 	if(!is_array($xshippingpro['days'][$no_of_tab]))$xshippingpro['days'][$no_of_tab]=array();
		 	if(!is_array($xshippingpro['rate_start'][$no_of_tab]))$xshippingpro['rate_start'][$no_of_tab]=array();
		 	if(!is_array($xshippingpro['rate_end'][$no_of_tab]))$xshippingpro['rate_end'][$no_of_tab]=array();
		 	if(!is_array($xshippingpro['rate_total'][$no_of_tab]))$xshippingpro['rate_total'][$no_of_tab]=array();
		 	if(!is_array($xshippingpro['rate_block'][$no_of_tab]))$xshippingpro['rate_block'][$no_of_tab]=array();
		 	if(!is_array($xshippingpro['rate_partial'][$no_of_tab]))$xshippingpro['rate_partial'][$no_of_tab]=array();
		 	if(!is_array($xshippingpro['country'][$no_of_tab]))$xshippingpro['country'][$no_of_tab]=array();
		 
		 	if(!isset($xshippingpro['inc_weight'][$no_of_tab]))$xshippingpro['inc_weight'][$no_of_tab]='';
			if(!isset($xshippingpro['dimensional_overfule'][$no_of_tab]))$xshippingpro['dimensional_overfule'][$no_of_tab]='';
			
			
			if(!isset($xshippingpro['dimensional_factor'][$no_of_tab]) || !$xshippingpro['dimensional_factor'][$no_of_tab])$xshippingpro['dimensional_factor'][$no_of_tab]= ($xshippingpro['rate_type'][$no_of_tab]=='volume' || $xshippingpro['rate_type'][$no_of_tab]=='volume_method')?1:6000;
			
		 	if(!is_array($names))$names=array();
		 
		 	if(!isset($xshippingpro['desc'][$no_of_tab]))$xshippingpro['desc'][$no_of_tab]=array();
		 	if(!is_array($xshippingpro['desc'][$no_of_tab]))$xshippingpro['desc'][$no_of_tab]=array();
		 
		  	if(!isset($xshippingpro['customer_group_all'][$no_of_tab]))$xshippingpro['customer_group_all'][$no_of_tab]='';
		  	if(!isset($xshippingpro['geo_zone_all'][$no_of_tab]))$xshippingpro['geo_zone_all'][$no_of_tab]='';
		  	if(!isset($xshippingpro['store_all'][$no_of_tab]))$xshippingpro['store_all'][$no_of_tab]='';
		  	if(!isset($xshippingpro['manufacturer_all'][$no_of_tab]))$xshippingpro['manufacturer_all'][$no_of_tab]='';
		 	if(!isset($xshippingpro['postal_all'][$no_of_tab]))$xshippingpro['postal_all'][$no_of_tab]='';
		  	if(!isset($xshippingpro['coupon_all'][$no_of_tab]))$xshippingpro['coupon_all'][$no_of_tab]='';
		  	if(!isset($xshippingpro['postal'][$no_of_tab]))$xshippingpro['postal'][$no_of_tab]='';
		  	if(!isset($xshippingpro['coupon'][$no_of_tab]))$xshippingpro['coupon'][$no_of_tab]='';
		  	if(!isset($xshippingpro['postal_rule'][$no_of_tab]))$xshippingpro['postal_rule'][$no_of_tab]='inclusive';
		  	if(!isset($xshippingpro['coupon_rule'][$no_of_tab]))$xshippingpro['coupon_rule'][$no_of_tab]='inclusive';
		  	if(!isset($xshippingpro['time_start'][$no_of_tab]))$xshippingpro['time_start'][$no_of_tab]='';
		  	if(!isset($xshippingpro['time_end'][$no_of_tab]))$xshippingpro['time_end'][$no_of_tab]='';
		  	if(!isset($xshippingpro['rate_final'][$no_of_tab]))$xshippingpro['rate_final'][$no_of_tab]='single';
		  	if(!isset($xshippingpro['rate_percent'][$no_of_tab]))$xshippingpro['rate_percent'][$no_of_tab]='sub';
		  	if(!isset($xshippingpro['rate_min'][$no_of_tab]))$xshippingpro['rate_min'][$no_of_tab]=0;
		  	if(!isset($xshippingpro['rate_max'][$no_of_tab]))$xshippingpro['rate_max'][$no_of_tab]=0;
		  	if(!isset($xshippingpro['rate_add'][$no_of_tab]))$xshippingpro['rate_add'][$no_of_tab]=0;
		  	if(!isset($xshippingpro['modifier_ignore'][$no_of_tab]))$xshippingpro['modifier_ignore'][$no_of_tab]='';
		  	if(!isset($xshippingpro['country_all'][$no_of_tab]))$xshippingpro['country_all'][$no_of_tab]='';
			
			if(!isset($xshippingpro['manufacturer_rule'][$no_of_tab]))$xshippingpro['manufacturer_rule'][$no_of_tab]='2';
			if(!isset($xshippingpro['multi_category'][$no_of_tab]))$xshippingpro['multi_category'][$no_of_tab]='all';  
			if(!isset($xshippingpro['additional'][$no_of_tab]))$xshippingpro['additional'][$no_of_tab]=0; 
			if(!isset($xshippingpro['logo'][$no_of_tab]))$xshippingpro['logo'][$no_of_tab]='';
			if(!isset($xshippingpro['group'][$no_of_tab]))$xshippingpro['group'][$no_of_tab]=0;
			
			if(!isset($xshippingpro['order_total_start'][$no_of_tab]))$xshippingpro['order_total_start'][$no_of_tab]=0;
		  	if(!isset($xshippingpro['order_total_end'][$no_of_tab]))$xshippingpro['order_total_end'][$no_of_tab]=0;
		 	if(!isset($xshippingpro['weight_start'][$no_of_tab]))$xshippingpro['weight_start'][$no_of_tab]=0;
		  	if(!isset($xshippingpro['weight_end'][$no_of_tab]))$xshippingpro['weight_end'][$no_of_tab]=0;
		  	if(!isset($xshippingpro['quantity_start'][$no_of_tab]))$xshippingpro['quantity_start'][$no_of_tab]=0;
		  	if(!isset($xshippingpro['quantity_end'][$no_of_tab]))$xshippingpro['quantity_end'][$no_of_tab]=0;
		  	if(!isset($xshippingpro['mask'][$no_of_tab]))$xshippingpro['mask'][$no_of_tab]='';
		  	if(!isset($xshippingpro['equation'][$no_of_tab]))$xshippingpro['equation'][$no_of_tab]='';
			
			$shipping_group_methods[intval($xshippingpro['group'][$no_of_tab])][]=$no_of_tab;
			
			if($xshippingpro['rate_type'][$no_of_tab]=='grand' && !$isGrandFound) {
			   /* Finding grand-total */
				$this->load->model('extension/extension');
				$total_mods = $this->model_extension_extension->getExtensions('total');
				$xtotal_data = array();
				$xtotal = 0;
		
				$sort_order = array();
				foreach ($total_mods as $key => $value) {
					$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
				}

				array_multisort($sort_order, SORT_ASC, $total_mods);
		
				foreach ($total_mods as $total_mod) {
		         
		        	if($total_mod['code']=='shipping') continue;
		           
					if ($this->config->get($total_mod['code'] . '_status')) {
						$this->load->model('total/' . $total_mod['code']);

						$this->{'model_total_' . $total_mod['code']}->getTotal($xtotal_data, $xtotal, $xtaxes);
					
						if($total_mod['code']=='total') {
					  		$grand_total = $xtotal;
					  		break;
						}
					}
			    }
	     
	           if(!$grand_total) $grand_total = $cart_total;		
	            /* end of grand-total */	
	           $isGrandFound =true;
	        }
				
				$status = true;
				$block_found=false;
				
				if($xshippingpro['geo_zone_id'][$no_of_tab] && $xshippingpro['geo_zone_all'][$no_of_tab]!=1){
				   $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id in (" . implode(',',$xshippingpro['geo_zone_id'][$no_of_tab]) . ") AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')"); 
				}
                
				if($xshippingpro['geo_zone_all'][$no_of_tab]!=1){
				  if ($xshippingpro['geo_zone_id'][$no_of_tab] && $query->num_rows==0) {
					$status = false;
					$debugging_message[]='GEO Zone';
				  } 
				}
				
				if($xshippingpro['country_all'][$no_of_tab]!=1){
				  if (!in_array((int)$address['country_id'], $xshippingpro['country'][$no_of_tab])) {
					 $status = false;
					 $debugging_message[]='Country';
				  } 
				}
				
				if (!$xshippingpro['status'][$no_of_tab]) {
				  $status = false;
				  $debugging_message[]='Status';
				}
				
				/*store checking*/
				if($xshippingpro['store_all'][$no_of_tab]!=1){
				  if(!in_array((int)$store_id,$xshippingpro['store'][$no_of_tab])){
				  $status = false;
				  $debugging_message[]='Store';
				  }
				}
				
				$method_categories=array();
				// if multi-cateogry rule is any, then recalculate method categories
				if($multi_category && $xshippingpro['multi_category'][$no_of_tab]=='any'){
					  foreach($cart_products as $product){
						  if(array_intersect($xshippingpro['product_category'][$no_of_tab],$product['categories']))         {
							 $method_categories=array_merge($method_categories, $product['categories']); 
						  }
					  }
					  $method_categories=array_unique($method_categories);
					  $xshippingpro['product_category'][$no_of_tab]=$method_categories;
				 }
				
				$resultant_category=array_intersect($xshippingpro['product_category'][$no_of_tab],$cart_categories);
				$resultant_products=array_intersect($xshippingpro['product_product'][$no_of_tab],$cart_product_ids);
				$resultant_manufacturers=array_intersect($xshippingpro['manufacturer'][$no_of_tab],$cart_manufacturers);
				
				// print_r($xshippingpro['product_category'][$no_of_tab]);
				// print_r($resultant_category);
				
				/*Manufacturer checking*/
				$applicable_manufacturer=$cart_manufacturers;
				if($xshippingpro['manufacturer_all'][$no_of_tab]!=1){
				   
				   if ($xshippingpro['manufacturer_rule'][$no_of_tab]==2){
				     if(count($resultant_manufacturers)!=count($xshippingpro['manufacturer'][$no_of_tab])){
				       $status = false; 
				       $debugging_message[]='Manufacturer';
				    }
				    $applicable_manufacturer=$xshippingpro['manufacturer'][$no_of_tab];
				  }
				  
				 if ($xshippingpro['manufacturer_rule'][$no_of_tab]==3){
				   if(!$resultant_manufacturers){
				     $status = false; 
				     $debugging_message[]='Manufacturer';
				    }
				    $applicable_manufacturer=$xshippingpro['manufacturer'][$no_of_tab];
				  }
				
				if ($xshippingpro['manufacturer_rule'][$no_of_tab]==4){
				
				  if(count($resultant_manufacturers)!=count($xshippingpro['manufacturer'][$no_of_tab]) || count($resultant_manufacturers)!=count($cart_manufacturers)){
				    $status = false; 
				    $debugging_message[]='Manufacturer';
				  }
				  $applicable_manufacturer=$xshippingpro['manufacturer'][$no_of_tab];
				}
				
				if ($xshippingpro['manufacturer_rule'][$no_of_tab]==5){
				   if($resultant_manufacturers){
				     $status = false; 
				     $debugging_message[]='Manufacturer';
				   }
				  $applicable_manufacturer= array_diff($cart_manufacturers, $xshippingpro['manufacturer'][$no_of_tab]); 
				 }
				
				if ($xshippingpro['manufacturer_rule'][$no_of_tab]==6){
				
				  if(!$resultant_manufacturers || count($resultant_manufacturers)!=count($cart_manufacturers)){
				    $status = false; 
				    $debugging_message[]='Manufacturer';
				   }
				   $applicable_manufacturer=$xshippingpro['manufacturer'][$no_of_tab];
				 }
				 
				 if ($xshippingpro['manufacturer_rule'][$no_of_tab]==7){
				
				  if($resultant_manufacturers && count($resultant_manufacturers)==count($cart_manufacturers)){
				    $status = false; 
				    $debugging_message[]='Manufacturer';
				  }
				  
				  $applicable_manufacturer= array_diff($cart_manufacturers, $xshippingpro['manufacturer'][$no_of_tab]);
				}
				   
			 }
			 /* End manufacturer checking*/
				
				/*Customer group checking*/
				if(isset($_POST['customer_group_id']) && $_POST['customer_group_id']){
				     $customer_group_id=$_POST['customer_group_id'];
				}
				elseif ($this->customer->isLogged()) {
					 $customer_group_id = $this->customer->getGroupId();
				 } else {
					 $customer_group_id = $this->config->get('config_customer_group_id');
				 }
				 
				 
				if (!in_array($customer_group_id,$xshippingpro['customer_group'][$no_of_tab]) && $xshippingpro['customer_group_all'][$no_of_tab]!=1) {
				   $status = false; 
				   $debugging_message[]='Customer Group';
				}
				
				/*postal checking*/
				if($xshippingpro['postal_all'][$no_of_tab]!=1){
				  $postal=$xshippingpro['postal'][$no_of_tab]; 
				  $postal_rule=$xshippingpro['postal_rule'][$no_of_tab];
				  $postal_rule=($postal_rule=='inclusive')?true:false;
				  $postal_found=false;
				  if($postal && isset($address['postcode'])){
				    $deliver_postal = str_replace('-','',$address['postcode']); 
				    $postal=explode(',',trim($postal));
					 foreach($postal as $postal_code){
						 $postal_code=trim($postal_code);
						
						  /* In case of range postal code - only numeric */
						 if(strpos($postal_code,'-')!==false && substr_count($postal_code,'-')==1 ) {
						    list($start_postal,$end_postal)=	explode('-',$postal_code); 
							
							$start_postal=(int)$start_postal;
							$end_postal=(int)$end_postal;
							
							if( $deliver_postal >= $start_postal &&  $deliver_postal <= $end_postal) {
							    $postal_found=true;
							}
					     }
						 /* End of range checking*/
						
						  /* In case of range postal code wiht prefix*/
						 elseif(strpos($postal_code,'-')!==false && substr_count($postal_code,'-')==2){
						    list($prefix,$start_postal,$end_postal)=	explode('-',$postal_code); 
							$start_postal=(int)$start_postal;
							$end_postal=(int)$end_postal;
							
							if($start_postal<=$end_postal){
						       for($i=$start_postal;$i<=$end_postal;$i++){
								   
								    if(preg_match('/^'.str_replace(array('\*','\?'),array('(.*?)','[a-zA-Z0-9]'),preg_quote($prefix.$i)).'$/i',trim($deliver_postal))){
							         $postal_found=true; 
									  break; 
						           }
								   
								}
							}
					     }
						 /* End of range checking*/
						   /* In case of range postal code wiht prefix and sufiix*/
						 elseif(strpos($postal_code,'-')!==false && substr_count($postal_code,'-')==3){
						    list($prefix,$start_postal,$end_postal,$sufiix)=	explode('-',$postal_code); 
							$start_postal=(int)$start_postal;
							$end_postal=(int)$end_postal;
							
							if($start_postal<=$end_postal){
						       for($i=$start_postal;$i<=$end_postal;$i++){
								   
								   if(preg_match('/^'.str_replace(array('\*','\?'),array('(.*?)','[a-zA-Z0-9]'),preg_quote($prefix.$i.$sufiix)).'$/i',trim($deliver_postal))){
							         $postal_found=true;  
									 break;
						           }
								}
							}
					     }
						 /* End of range checking*/
						 
						 /* In case of wildcards use code*/
						 elseif(strpos($postal_code,'*')!==false || strpos($postal_code,'?')!==false){
							
				         if(preg_match('/^'.str_replace(array('\*','\?'),array('(.*?)','[a-zA-Z0-9]'),preg_quote($postal_code)).'$/i',trim($deliver_postal))){
							 $postal_found=true;  
						  }
						
                        
					     }
						 /* End of wildcards checking*/
						 else{
							  
							  if(trim(strtolower($deliver_postal))==strtolower($postal_code)){
									$postal_found=true; 
								} 
					     }
					 }
				    
			        if((boolean)$postal_found!==$postal_rule){
				        $status = false;
				        $debugging_message[]='Zip/Postal -'.$address['postcode'];
				    } 
				  }	  
				}
				
				/*coupon checking*/
				if($xshippingpro['coupon_all'][$no_of_tab]!=1) {
				  $coupon=$xshippingpro['coupon'][$no_of_tab]; 
				  $coupon_rule=$xshippingpro['coupon_rule'][$no_of_tab];
				
				  if($coupon){
				    $coupon=explode(',',trim($coupon));
				    $coupon_rule=($coupon_rule=='inclusive')?false:true;
		
				    if($coupon_rule===false && !isset($this->session->data['coupon'])) {
				      $status = false;
				      $debugging_message[]='Coupon';
				    }
				    
			        if(isset($this->session->data['coupon']) && in_array(trim($this->session->data['coupon']),$coupon)===$coupon_rule){
				        $status = false;
				        $debugging_message[]='Coupon';
				    } 
				  }	  
				}
				
				
				/*category checking*/
				$applicable_category=$cart_categories;
				if ($xshippingpro['category'][$no_of_tab]==2){
				  if(count($resultant_category)!=count($xshippingpro['product_category'][$no_of_tab])){
				    $status = false; 
				    $debugging_message[]='Category';
				  }
			      $applicable_category=$xshippingpro['product_category'][$no_of_tab];
				}
				if ($xshippingpro['category'][$no_of_tab]==3){
				   if(!$resultant_category){
				     $status = false; 
				     $debugging_message[]='Category';
				   }
				   $applicable_category=$xshippingpro['product_category'][$no_of_tab];
				}
				
				if ($xshippingpro['category'][$no_of_tab]==4){
				
				  if(count($resultant_category)!=count($xshippingpro['product_category'][$no_of_tab]) || count($resultant_category)!=count($cart_categories)){
				    $status = false; 
				    $debugging_message[]='Category';
				  }
				  $applicable_category=$xshippingpro['product_category'][$no_of_tab];
				}
				
				if ($xshippingpro['category'][$no_of_tab]==5){
				   if($resultant_category){
				     $status = false; 
				     $debugging_message[]='Category';
				   }
				  $applicable_category= array_diff($cart_categories, $xshippingpro['product_category'][$no_of_tab]); 
				}
				
				if ($xshippingpro['category'][$no_of_tab]==6){
				
				  if(!$resultant_category || count($resultant_category)!=count($cart_categories)){
				    $status = false; 
				    $debugging_message[]='Category';
				  }
				  $applicable_category=$xshippingpro['product_category'][$no_of_tab];
				}
				
				if ($xshippingpro['category'][$no_of_tab]==7){
				
				  if($resultant_category && count($resultant_category)==count($cart_categories)){
				    $status = false; 
				    $debugging_message[]='Category';
				  }
				  $applicable_category= array_diff($cart_categories, $xshippingpro['product_category'][$no_of_tab]);
				}
				
				/* End of category */
				
				/*product checking*/
				$applicable_product=$cart_product_ids;
				if ($xshippingpro['product'][$no_of_tab]==2){
				  if(count($resultant_products)!=count($xshippingpro['product_product'][$no_of_tab])){
				    $status = false; 
				    $debugging_message[]='Product';
				  }
				  $applicable_product=$xshippingpro['product_product'][$no_of_tab];
				}
				if ($xshippingpro['product'][$no_of_tab]==3){
				   if(!$resultant_products){
				     $status = false; 
				     $debugging_message[]='Product';
				   }
				  $applicable_product=$xshippingpro['product_product'][$no_of_tab]; 
				}
				if ($xshippingpro['product'][$no_of_tab]==4){
				  if(count($resultant_products)!=count($xshippingpro['product_product'][$no_of_tab]) || count($resultant_products)!=count($cart_product_ids)){
				    $status = false;
				    $debugging_message[]='Product'; 
				  }
				  $applicable_product=$xshippingpro['product_product'][$no_of_tab]; 
				}
				
				if ($xshippingpro['product'][$no_of_tab]==5){
				   if($resultant_products){
				     $status = false; 
				     $debugging_message[]='Product';
				   }
				  $applicable_product= array_diff($cart_product_ids, $xshippingpro['product_product'][$no_of_tab]); 
				}
				
				if ($xshippingpro['product'][$no_of_tab]==6){
				   if(!$resultant_products || count($resultant_products)!=count($cart_product_ids)){
				     $status = false; 
				     $debugging_message[]='Product';
				   }
				  $applicable_product=$xshippingpro['product_product'][$no_of_tab]; 
				}
				
				if ($xshippingpro['product'][$no_of_tab]==7){
				
				  if($resultant_products && count($resultant_products)==count($cart_product_ids)){
				    $status = false; 
				    $debugging_message[]='Product';
				  }
				  $applicable_product= array_diff($cart_product_ids, $xshippingpro['product_product'][$no_of_tab]);
				}
				
				/* End of product */
				
				/*Days of week checking*/
				  $day=date('w');
				  if(!in_array($day,$xshippingpro['days'][$no_of_tab])){
				    $status = false; 
				    $debugging_message[]='Day Option';
				  }
				/* Day checking*/
				
				/*time checking*/
				
				  $time=date('G')+1; /* 'G' return 0-23 so plus 1 */
				  if($xshippingpro['time_start'][$no_of_tab] && $xshippingpro['time_end'][$no_of_tab]){
				    if($time < $xshippingpro['time_start'][$no_of_tab] || $time> $xshippingpro['time_end'][$no_of_tab]) {
				      $status = false; 
				      $debugging_message[]='Time Setting';
				     }  
				  }
				  
				/*Day checking*/
				
				$cart_dimensional_weight=0;
				
				/* Calculate dimension weight*/
				if ($xshippingpro['rate_type'][$no_of_tab]=='dimensional' || $xshippingpro['rate_type'][$no_of_tab]=='dimensional_method'){
				   foreach($cart_products as $inc=>$product){
				      
					   $product_dimensional_weight=($product['volume']/$xshippingpro['dimensional_factor'][$no_of_tab])*$product['weight'];  
					   
		 
					   if($xshippingpro['dimensional_overfule'][$no_of_tab] && $product_dimensional_weight<$product['weight']){
					     $product_dimensional_weight= $product['weight'];
					   }
					 
					 $cart_products[$inc]['dimensional']=$product_dimensional_weight;
					 $cart_dimensional_weight+=$product_dimensional_weight;
				   }
				}
				/* End of dimension weight*/
				
				/* Calculate volumetric weight*/
			    $volumetric_weight = 0;
				if ($xshippingpro['rate_type'][$no_of_tab]=='volume' || $xshippingpro['rate_type'][$no_of_tab]=='volume_method'){
				
				   foreach($cart_products as $inc=>$product){
				      
					   $product_volumetric_weight=($product['volume']*$xshippingpro['dimensional_factor'][$no_of_tab]);  
					   
		 
					   if($xshippingpro['dimensional_overfule'][$no_of_tab] && $product_volumetric_weight<$product['weight']){
					     $product_volumetric_weight= $product['weight'];
					   }
					 
					 $cart_products[$inc]['volumetric']=$product_volumetric_weight;
					 $volumetric_weight+=$product_volumetric_weight;
				   }
				}
				/* End of volumetric weight*/
			    
				
				
				/* Calculate method wise data if needed*/
				if ($xshippingpro['rate_type'][$no_of_tab]=='total_method' || $xshippingpro['rate_type'][$no_of_tab]=='quantity_method' || $xshippingpro['rate_type'][$no_of_tab]=='sub_method' || $xshippingpro['rate_type'][$no_of_tab]=='weight_method' || $xshippingpro['rate_type'][$no_of_tab]=='volume_method' || $xshippingpro['rate_type'][$no_of_tab]=='dimensional_method'){
			    	$method_quantity=0;	
			    	$method_weight=0;
			    	$method_total=0;
			    	$method_sub=0;
			    	$method_volume=0;
					$method_dimensional_weight=0;
					$method_volumetric_weight = 0;
					
			    	foreach($cart_products as $product){
			      
			      		if (($xshippingpro['manufacturer_rule'][$no_of_tab]==2 || $xshippingpro['manufacturer_rule'][$no_of_tab]==3 || $xshippingpro['manufacturer_rule'][$no_of_tab]==4 || $xshippingpro['manufacturer_rule'][$no_of_tab]==5 || $xshippingpro['manufacturer_rule'][$no_of_tab]==6 || $xshippingpro['manufacturer_rule'][$no_of_tab]==7) && !in_array($product['manufacturer_id'],$applicable_manufacturer)){   
			       	      continue;
			            } 
			           
			           if (($xshippingpro['category'][$no_of_tab]==2 || $xshippingpro['category'][$no_of_tab]==3 || $xshippingpro['category'][$no_of_tab]==4 || $xshippingpro['category'][$no_of_tab]==5 || $xshippingpro['category'][$no_of_tab]==6 || $xshippingpro['category'][$no_of_tab]==7) && !array_intersect($product['categories'],$applicable_category)){   
			             continue;
			           }
			           
			           if (($xshippingpro['product'][$no_of_tab]==2 || $xshippingpro['product'][$no_of_tab]==3 || $xshippingpro['product'][$no_of_tab]==4 || $xshippingpro['product'][$no_of_tab]==5 || $xshippingpro['product'][$no_of_tab]==6 || $xshippingpro['product'][$no_of_tab]==7) && !in_array($product['product_id'],$applicable_product)){   
			            continue;
			           }
			      
			          $method_quantity+= $product['quantity'];
					  
					   if($product['weight_class_id']) {
			      	     $method_weight+= $this->weight->convert($product['weight'], $product['weight_class_id'], $this->config->get('config_weight_class_id'));
					   }
					  
					  if($product['tax_class_id']) {
			      	     $method_total+=  $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'];
						}
			      	   $method_sub+= $product['total']; 
			      	   $method_volume+= $product['volume'];  
					   $method_dimensional_weight+= isset($product['dimensional'])?$product['dimensional']:0;
					   $method_volumetric_weight+= isset($product['volumetric'])?$product['volumetric']:0; 
			       }
			    }
			    	
				
				/*rate calculation*/
				$cost=0;
				$percent_to_be_considered=($xshippingpro['rate_percent'][$no_of_tab]=='sub')?$cart_subtotal:$cart_total;
				
				if ($xshippingpro['rate_type'][$no_of_tab]=='flat'){
				   if(substr(trim($xshippingpro['cost'][$no_of_tab]), -1)=='%'){
				     $percent=rtrim(trim($xshippingpro['cost'][$no_of_tab]),'%'); 
				     $cost=(float)(($percent*$percent_to_be_considered)/100);
				   }else{
				     $cost=(float)$xshippingpro['cost'][$no_of_tab];  
				   }  
				}else{
				
				    $target_value=0;
				    if ($xshippingpro['rate_type'][$no_of_tab]=='quantity'){
				       $target_value=$cart_quantity;
				       
				    }
				    
				    if($xshippingpro['rate_type'][$no_of_tab]=='quantity_method'){
				       $target_value=$method_quantity;  
				    }
				 
				    if ($xshippingpro['rate_type'][$no_of_tab]=='weight'){
				       $target_value=$cart_weight;  
				    }
					
				    if($xshippingpro['rate_type'][$no_of_tab]=='weight_method'){
				       $target_value=$method_weight;  
				    }
					
					if ($xshippingpro['rate_type'][$no_of_tab]=='dimensional'){
				       $target_value=$cart_dimensional_weight;
				    }
					if ($xshippingpro['rate_type'][$no_of_tab]=='dimensional_method'){
				        $target_value=$method_dimensional_weight;
				    }
				
				    if ($xshippingpro['rate_type'][$no_of_tab]=='volume'){
				       $target_value=$volumetric_weight;  
				    }
				    
				    if($xshippingpro['rate_type'][$no_of_tab]=='volume_method'){
				       $target_value=$method_volumetric_weight;  
				    }
				
				   if ($xshippingpro['rate_type'][$no_of_tab]=='total' || $xshippingpro['rate_type'][$no_of_tab]=='total_method'){
				     
				      $target_value=$cart_total;  
				      
				     if($xshippingpro['rate_type'][$no_of_tab]=='total_method'){
				       $target_value=$method_total;  
				      }
				   }
				   
				   if ($xshippingpro['rate_type'][$no_of_tab]=='total_coupon'){
				   
				      $target_value=$cart_total_without_coupon;  
				   }
				
				   if ($xshippingpro['rate_type'][$no_of_tab]=='sub' || $xshippingpro['rate_type'][$no_of_tab]=='sub_method'){
				   
				     $target_value=$cart_subtotal;  
				       
				     if($xshippingpro['rate_type'][$no_of_tab]=='sub_method'){
				       $target_value=$method_sub;  
				      }
				   }
				   
				   if ($xshippingpro['rate_type'][$no_of_tab]=='grand') {
				    
				     foreach($xshippingpro['rate_start'][$no_of_tab] as $index=>$single){
				        $xshippingpro['rate_start'][$no_of_tab][$index]=$this->currency->format((float)$single, $currency_code, false, false); 
				     }
				     
				     foreach($xshippingpro['rate_end'][$no_of_tab] as $index=>$single){
				        $xshippingpro['rate_end'][$no_of_tab][$index]=$this->currency->format((float)$single, $currency_code, false, false); 
				     }
				   
				     $target_value=$grand_total;  
				   }
				  
				  
				  
				
				  if($xshippingpro['rate_final'][$no_of_tab]=='single'){
				      if(!$this->getSinglePrice($xshippingpro['rate_start'][$no_of_tab],$xshippingpro['rate_end'][$no_of_tab],$xshippingpro['rate_total'][$no_of_tab],$xshippingpro['rate_block'][$no_of_tab],$xshippingpro['rate_partial'][$no_of_tab],$xshippingpro['additional'][$no_of_tab],$target_value,$percent_to_be_considered,$cost,$block_found)){
				        $status = false; 
				        $debugging_message[]='Price Single ('.$xshippingpro['rate_type'][$no_of_tab].'='.$target_value.')';
				      }
			      }
			      if($xshippingpro['rate_final'][$no_of_tab]=='cumulative'){
				     
				     if(!$this->getCumulativePrice($xshippingpro['rate_start'][$no_of_tab],$xshippingpro['rate_end'][$no_of_tab],$xshippingpro['rate_total'][$no_of_tab],$xshippingpro['rate_block'][$no_of_tab],$xshippingpro['rate_partial'][$no_of_tab],$xshippingpro['additional'][$no_of_tab],$target_value,$percent_to_be_considered,$cost,$block_found)){
				        $status = false; 
				        $debugging_message[]='Price Cumulative ('.$xshippingpro['rate_type'][$no_of_tab].'='.$target_value.')';
				      }
				   }
				   
				     
				  $modifier_allowed=true;
				  if($xshippingpro['modifier_ignore'][$no_of_tab] && !$block_found) $modifier_allowed=false;
				  
				  if($xshippingpro['rate_min'][$no_of_tab] && $xshippingpro['rate_min'][$no_of_tab]>$cost && $modifier_allowed) {
			        $cost=(float)$xshippingpro['rate_min'][$no_of_tab];
			      }
			    
			      if($xshippingpro['rate_max'][$no_of_tab] && $xshippingpro['rate_max'][$no_of_tab]<$cost && $modifier_allowed) {
			        $cost=(float)$xshippingpro['rate_max'][$no_of_tab];
			      }
			      
			      $eq_shipping = $cost;
			      $eq_modifier = 0;
			      
			      /* find modifier*/
			      if($xshippingpro['rate_percent'][$no_of_tab]=='sub') {
                      $percent_to_be_considered = $cart_subtotal; 
			      }
			      if($xshippingpro['rate_percent'][$no_of_tab]=='total') {
                      $percent_to_be_considered = $cart_total;
			      }
			      if($xshippingpro['rate_percent'][$no_of_tab]=='shipping') {
                      $percent_to_be_considered = $cost;
			      }
			      if($xshippingpro['rate_percent'][$no_of_tab]=='sub_shipping') {
                      $percent_to_be_considered = $cart_subtotal + $cost;
			      }
			      if($xshippingpro['rate_percent'][$no_of_tab]=='total_shipping') {
                      $percent_to_be_considered = $cart_total + $cost;
			      }
			      
			      $modifier = substr(trim($xshippingpro['rate_add'][$no_of_tab]),0,1);
			      $modifier = in_array($modifier,$operators)?$modifier:'+';
			      $xshippingpro['rate_add'][$no_of_tab]=str_replace($operators,'',$xshippingpro['rate_add'][$no_of_tab]);
			      $modification=0;
			      if(substr(trim($xshippingpro['rate_add'][$no_of_tab]), -1)=='%'){
					  $add_percent=rtrim(trim($xshippingpro['rate_add'][$no_of_tab]),'%'); 
					  $modification=(float)(($add_percent*$percent_to_be_considered)/100);	 
				  } else {
					  $modification=(float)$xshippingpro['rate_add'][$no_of_tab];	
				   }
				  
				  if($modification && $modifier_allowed) {
				     if($modifier=='+') $cost +=$modification; 
				     if($modifier=='-') $cost -=$modification; 
				     if($modifier=='*') $cost *=$modification; 
				     if($modifier=='/') $cost /=$modification; 
				     $eq_modifier = $cost;
				  }
				  
				  /*Equation*/
			      if($xshippingpro['equation'][$no_of_tab]) {
			   
			   		$equation = $xshippingpro['equation'][$no_of_tab]; 
			   		$placholder = array('{cartTotal}','{cartQnty}','{cartWeight}', '{shipping}', '{modifier}');
			   		
			   		$eq_total = ($xshippingpro['rate_type'][$no_of_tab]=='total_method') ? $method_total : $cart_total;
			   		$eq_qnty = ($xshippingpro['rate_type'][$no_of_tab]=='quantity_method') ? $method_quantity : $cart_quantity;
			   		$eq_weight = ($xshippingpro['rate_type'][$no_of_tab]=='weight_method') ? $method_weight : $cart_weight;
			   		
			   		$replacer = array($eq_total,$eq_qnty,$eq_weight,$eq_shipping,$eq_modifier);
			   		$equation = str_replace($placholder, $replacer, $equation);
			   		
			   		$cost =(float)$this->calculate_string($equation);
			   		
			      }
				   
			   }
			    
			     /* additional Ranges checking*/
				if((float)$xshippingpro['order_total_end'][$no_of_tab]>0){
				    
					 if ($cart_total < (float)$xshippingpro['order_total_start'][$no_of_tab] || $cart_total> (float)$xshippingpro['order_total_end'][$no_of_tab]) {
					   $status = false;
					   $debugging_message[]='Additional Order Total Ranges';
					 } 
				}
				
				if((float)$xshippingpro['weight_end'][$no_of_tab]>0){
				    
					 if ($cart_weight < (float)$xshippingpro['weight_start'][$no_of_tab] || $cart_weight > (float)$xshippingpro['weight_end'][$no_of_tab]) {
					   $status = false;
					   $debugging_message[]='Additional Weight Ranges';
					 }
				}
				
				if((int)$xshippingpro['quantity_end'][$no_of_tab]>0){
				    
					 if ($cart_quantity < (int)$xshippingpro['quantity_start'][$no_of_tab] || $cart_quantity > (int)$xshippingpro['quantity_end'][$no_of_tab]) {
					   $status = false;
					   $debugging_message[]='Additional Quantity Ranges';
					 }
				}
			    
			    /* End of ranges of checking*/ 		
			    
			    
				
				/*Ended rate cal*/

				if(!isset($names[$language_id]) || !$names[$language_id]){
				  $status = false;
				  $debugging_message[]='Name';
				}
				
				
				if(!$status){
				 $debugging[]=array('name'=>$names[$language_id],'filter'=>$debugging_message,'index'=>$no_of_tab);
				}
				
				
				
			    if($xshippingpro['inc_weight'][$no_of_tab]==1 && $cart_weight>0){
				  $names[$language_id].=' ('.$this->weight->format($cart_weight, $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point')).')';
				}
	
				$method_desc=($xshippingpro['desc'][$no_of_tab][$language_id])?'<span style="color: #999999;font-size: 11px;display:block" class="x-shipping-desc">'.$xshippingpro['desc'][$no_of_tab][$language_id].'</span>':'';
				
				if(intval($xshippingpro['group'][$no_of_tab])) {
				  $isSubGroupFound = true;
				}
				
				if ($status) {
				
					$quote_data['xshippingpro'.$no_of_tab] = array(
						'code'         => 'xshippingpro'.'.xshippingpro'.$no_of_tab,
						'title'        => $names[$language_id],
						'desc'         => $method_desc,
						'logo'         => $xshippingpro['logo'][$no_of_tab],
						'cost'         => $cost,
						'group'         => intval($xshippingpro['group'][$no_of_tab]),
						'sort_order'         => intval($xshippingpro['sort_order'][$no_of_tab]),
						'tax_class_id' => $xshippingpro['tax_class_id'][$no_of_tab],
						'text'         => ($xshippingpro['mask'][$no_of_tab])? $xshippingpro['mask'][$no_of_tab]: $this->currency->format($this->tax->calculate($cost, $xshippingpro['tax_class_id'][$no_of_tab], $this->config->get('config_tax')))
					);
				}
			}
			
			
			/*Finding sub grouping*/
	
		   if($isSubGroupFound) {	
		     
			  $grouping_methods=array();
			  foreach($quote_data as $xkey=>$single) {
			     $single['xkey']=$xkey;
			     $grouping_methods[$single['group']][]=$single;    
			  }
			  
			 $new_quote_data=array();
			 
			 foreach($grouping_methods as $sub_group_id=>$grouping_method) {
			 
			     if($sub_group_id && $xshippingpro_sub_group[$sub_group_id] =='and' && count($grouping_method)!=count($shipping_group_methods[$sub_group_id])) {
			        continue;
			      }
			      
			      if(count($grouping_method)==1 || empty($sub_group_id)) {
			        
			        $append_methods = array();
			        foreach($grouping_method as $single) {
			          $append_methods[$single['xkey']]= $single;  
			        }
			      
			        $new_quote_data = array_merge($new_quote_data,$append_methods);
			        continue;
			      }
			      
			      $sub_group_type = $xshippingpro_sub_group[$sub_group_id];
			      $sub_group_limit = isset($xshippingpro_sub_group_limit[$sub_group_id])?$xshippingpro_sub_group_limit[$sub_group_id]:1;
			      $sub_group_name = isset($xshippingpro_sub_group_name[$sub_group_id])?$xshippingpro_sub_group_name[$sub_group_id]:'';
			      
			      if(isset($grouping_method)) {
			         $new_quote_data = array_merge($new_quote_data,$this->findGroup($grouping_method, $sub_group_type, $sub_group_limit, $sub_group_name));
			      }
			      
			 }
			 
			 $quote_data= $new_quote_data;  
			 
		   }
		   
		   /* find top grouping*/
		   if($xshippingpro_group!='no_group') {
		      
		      $grouping_methods=array();
			  foreach($quote_data as $xkey=>$single){
			     $single['xkey']=$xkey;
			     $grouping_methods[$single['sort_order']][]=$single;    
			  }
			 
			  $new_quote_data=array();
		      foreach($grouping_methods as $group_id=>$grouping_method) {
		         
		         if(count($grouping_method)==1) {
			        
			        $append_methods = array();
			        foreach($grouping_method as $single) {
			          $append_methods[$single['xkey']]= $single;  
			        }
			      
			        $new_quote_data = array_merge($new_quote_data,$append_methods);
			        continue;
			      }
		         
		         if(isset($grouping_method)) {
			         $new_quote_data = array_merge($new_quote_data,$this->findGroup($grouping_method, $xshippingpro_group, $xshippingpro_group_limit));
			      }   
		      }
		      
		       $quote_data= $new_quote_data;   
		   }
			
		
		    /*Sorting final method*/
		    $sort_order = array();
		    $price_order = array();
			foreach ($quote_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
				$price_order[$key] = $value['cost'];
			}
			
		    if( $xshippingpro_sorting == 2) {
			   array_multisort($price_order, SORT_ASC, $quote_data);
			}
			elseif( $xshippingpro_sorting == 3) {
			   array_multisort($price_order, SORT_DESC, $quote_data);
			}
			else {
			   array_multisort($sort_order, SORT_ASC, $quote_data);
			}
			
			
			$xshippingpro_heading=isset($xshippingpro_heading[$language_id])?$xshippingpro_heading[$language_id]:'';
			
			$method_data = array(
				'code'       => 'xshippingpro',
				'title'      => ($xshippingpro_heading)?html_entity_decode($xshippingpro_heading):$this->language->get('text_title'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('xshippingpro_sort_order'),
				'error'      => ''
			);	
	   
	    if($xshippingpro_debug && $debugging){ 
	      echo '<div style="border: 1px solid #FF0000; margin: 20px 5px;padding: 10px;">';
	      foreach($debugging as $debug){
	        echo '<b>'.$debug['name'].'-'.$debug['index'].'</b> : ('.implode(',',$debug['filter']).') <br />';
	      }
	      echo '</div>';
	    }
	
		
        if(!$quote_data) return array();
		return $method_data;
 }
 
 
 private function findGroup($group_method, $group_type, $group_limit, $group_name='') {
      
      $language_id=$this->config->get('config_language_id');
      $currency_code=$this->config->get('config_currency');
      $return = array();
      $replacer = array();
      $replacer_price = array();
      if($group_type=='lowest') {
    
			$lowest=array();
			$lowest_sort=array();
			
			foreach($group_method as $group_id=>$method){
			
				 $lowest_sort[$group_id]=$method['cost'];
				 $lowest[$group_id]=$method;
				 array_push($replacer, $method['title']);
				 array_push($replacer_price, $this->currency->format((float)$method['cost'], $currency_code, false, true));
			}
			 
			 array_multisort($lowest_sort, SORT_ASC, $lowest);
			 
			 for($i=0;$i<$group_limit;$i++) {
				 if(isset($lowest[$i]) && is_array($lowest[$i]) && $lowest[$i]){	
			        $return[$lowest[$i]['xkey']]= $lowest[$i]; 
				 }
			 }
			     
		}
			 
	   
	    if($group_type=='highest') {
			     
			  
			  $highest=array();
			  $highest_sort=array();
			  
			  foreach($group_method as $group_id=>$method){
				  $highest_sort[$group_id]=$method['cost'];
				  $highest[$group_id]=$method;
				  array_push($replacer, $method['title']);
				  array_push($replacer_price, $this->currency->format((float)$method['cost'], $currency_code, false, true));
			  }
					
			  array_multisort($highest_sort, SORT_DESC, $highest);
			  
			  for($i=0;$i<$group_limit;$i++){
				    
				    if(isset($highest[$i]) && is_array($highest[$i]) && $highest[$i]){	
			             $return[$highest[$i]['xkey']]= $highest[$i]; 
					  }
				 } 
		} 
			 
	   if($group_type=='average') {
			
			    $sum=0;
			    foreach($group_method as $group_id=>$method){
			       $sum+=$method['cost'];
			       array_push($replacer, $method['title']);
			       array_push($replacer_price, $this->currency->format((float)$method['cost'], $currency_code, false, true));
			    }
			        
			   if(count($group_method)>1){
			         $group_method[0]['cost']=$sum/count($group_method); 
			         $group_method[0]['text']=$this->currency->format($this->tax->calculate($group_method[0]['cost'], $group_method[0]['tax_class_id'], $this->config->get('config_tax')));
			    }
			   
			   $return[$group_method[0]['xkey']]= $group_method[0]; 		     
	  } 
			 
	
	 if($group_type=='sum') {
			    
			 $sum=0;
			 foreach($group_method as $group_id=>$method){
			          $sum+=$method['cost'];
			          array_push($replacer, $method['title']);
			          array_push($replacer_price, $this->currency->format((float)$method['cost'], $currency_code, false, true));
			 }
			 $group_method[0]['cost']=$sum;
			 $group_method[0]['text']=$this->currency->format($this->tax->calculate($group_method[0]['cost'], $group_method[0]['tax_class_id'], $this->config->get('config_tax')));
			 $return[$group_method[0]['xkey']]= $group_method[0];  
	 } 
			 
			 
	 if($group_type=='and') {
			    
			       /* If AND success, show lowest price in case price is not equal*/
			       $lowest=99999999;
			       $target=0;
			       foreach($group_method as $group_id=>$method){
			           if($method['cost']<$lowest){
			             $target=$group_id; 
			             $lowest=$method['cost'];
			             array_push($replacer, $method['title']);
			             array_push($replacer_price, $this->currency->format((float)$method['cost'], $currency_code, false, true));
			           }
			        }
			       $return[$group_method[$target]['xkey']]= $group_method[$target]; 
	  }
	  
	  $keywords = array('#1','#2','#3','#4','#5'); 
	  $group_name = str_replace($keywords,$replacer, $group_name);
	  
	  $keywords = array('@1','@2','@3','@4','@5'); 
	  $group_name = str_replace($keywords,$replacer_price, $group_name);
	  
	  if(count($return)==1 && $group_name) {
	    foreach($return as $key => $method) {
	       $return[$key]['title'] = $group_name;
	    }
	  }
	  
	  return $return;
 
} 

private function getSinglePrice($start_range,$end_range,$price_range,$block_range,$partial,$additional,$target_value,$percent_to_be_considered,&$cost,&$block_found) {
	    
	   $status = false;
	   foreach($start_range as $index=>$start){
	     $start=(float)$start;
	     $end=(float)$end_range[$index];
	     
		 if(substr(trim($price_range[$index]), -1)=='%') {
		     $percent=rtrim(trim($price_range[$index]),'%'); 
			  $cost=(float)(($percent*$percent_to_be_considered)/100);
		  } else {
			  $cost=(float)$price_range[$index];  
		  } 
		   
	     if ($start <= $target_value && $target_value<= $end) {
			  $status = true; 
			  $end=$target_value;
		  }
		  
		  $block=((float)$block_range[$index])?(float)$block_range[$index]:0; 
		  $partialAllow= (isset($partial[$index]) && $partial[$index])?(int)$partial[$index]:0;
		  
		  if($block>0)
		  {  
		     	/*round to complete block for iteration purpose*/
		       if($block < 1 && fmod($end,$block) != 0) {
		        $end = ($end - fmod($end,$block)) + $block;
		       }
		       else if($block >= 1 && ($end % $block) != 0) {
		        $end = ($end - ($end % $block)) + $block; 
		       }
		 	   
		 	  
			  $no_of_blocks =0;
			  
			  if($start == 0) {
			     
			     while( $start < $end ) {
			     
			     	if($partialAllow) {
			       		$no_of_blocks =  ($end-$start) >= $block ? ($no_of_blocks+1) : ($no_of_blocks+($end-$start)/$block);
			     	} 
			     	else {
			       		$no_of_blocks++;
			     	}
			       $start += $block;
			     }
			  
			  } 
			  
			  else {
			 
			    while( $start <= $end ) {
			     
			        if($partialAllow) {
			          $no_of_blocks =  ($end-$start) >= $block ? ($no_of_blocks+1) : ($no_of_blocks+($end-$start)/$block);
			        } 
			        else {
			          $no_of_blocks++;
			        }
			       $start += $block;
			    }
			 }   
			
			  $cost=($no_of_blocks*$cost);	  
			 
		   }
			  
			if($status) break; 
	   }
	   
	   //if not found and additional price was set
	   if(substr(trim($additional), -1)=='%') {
		  $percent=rtrim(trim($additional),'%'); 
		  $additional=(float)(($percent*$percent_to_be_considered)/100);
		} else {
		  $additional=(float)$additional;  
		}
	   if(!$status && $additional) {
		   
		   if(!$block) $block = 1;
		    while( $end < $target_value ) {
		      $cost += $additional;
		      $end += $block;
		    }
	
		    $status=true;	  
		}
	   
	   return $status;
	}
	
  private function getCumulativePrice($start_range,$end_range,$price_range,$block_range,$partial,$additional,$target_value,$percent_to_be_considered,&$cost,&$block_found) {
	   $status = false;
	    
	   foreach($start_range as $index=>$start){
	     $start=(float)$start;
	     $end=(float)$end_range[$index];
	     
		 if(substr(trim($price_range[$index]), -1)=='%'){
			 $percent=rtrim(trim($price_range[$index]),'%'); 
			 $block_price=(float)(($percent*$percent_to_be_considered)/100);	 
			}else{
			 $block_price=(float)$price_range[$index];  
		  }
		 
	     $block=((float)$block_range[$index])?(float)$block_range[$index]:0;
		 $partialAllow= (isset($partial[$index]) && $partial[$index])?(int)$partial[$index]:0;
		  
		 
	     if ($start <= $target_value && $target_value<= $end) {
			 $status = true;
			 $end=$target_value; 
		  }
		  
		  if($block==0){
			 $cost+=(float)$block_price;
		  }
		  else
		  {
		  
		  	/*round to complete block for iteration purpose*/
		    if($block < 1 && fmod($end,$block) != 0) {
		        $end = ($end - fmod($end,$block)) + $block;
		     }
		    else if($block >= 1 && ($end % $block) != 0) {
		       $end = ($end - ($end % $block)) + $block; 
		     }
		 	 
		 	  
		 	  $no_of_blocks =0;
		 	  
		 	  if($start == 0) {
		 	     
		 	      while( $start < $end ) {
			     
			     	if($partialAllow) {
			       		$no_of_blocks =  ($end-$start) >= $block ? ($no_of_blocks+1) : ($no_of_blocks+($end-$start)/$block);
			     	 } else {
			       		$no_of_blocks++;
			     	 }
			        $start += $block;
			  	  }
		 	    
		 	  }
			
			  else {
			  
			 	 while( $start <= $end ) {
			     
			     	if($partialAllow) {
			       		$no_of_blocks =  ($end-$start) >= $block ? ($no_of_blocks+1) : ($no_of_blocks+($end-$start)/$block);
			     	 } 
			     	 else {
			       		$no_of_blocks++;
			     	 }
			        $start += $block;
			  	  }
			  }
			    
			  $cost+=($no_of_blocks*(float)$block_price);
			  $block_found=true;
		  }
		  
		  if($status) break;
	    }
		
		 //if not found and additional price was set
	   if(substr(trim($additional), -1)=='%') {
		  $percent=rtrim(trim($additional),'%'); 
		  $additional=(float)(($percent*$percent_to_be_considered)/100);
		} else {
		  $additional=(float)$additional;  
		}
		
	   if(!$status && $additional) {
		   
		    if(!$block) $block = 1;
		    while( $end < $target_value ) {
		      $cost += $additional;
		      $end += $block;
		    }
	
		  $status=true;	 
		}
	   
	   return $status;
	}
	
 private function calculate_string( $mathString )    {
    $mathString = trim($mathString);     // trim white spaces
    $mathString = preg_replace ('[^0-9\+-\*\/\(\) ]', '', $mathString);    // remove any non-numbers chars; exception for math operators
 
    $compute = create_function("", "return (" . $mathString . ");" );
    return 0 + $compute();
  }
  
}	
?>