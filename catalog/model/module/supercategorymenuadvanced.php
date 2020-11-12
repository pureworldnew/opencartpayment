<?php
class ModelModuleSuperCategoryMenuAdvanced extends Model {
public function Ampersand($str){
	$str_to_clean=$str;
	$search  = array('"','&amp;quot;','<','&amp;lt;','>','&amp;gt','&amp;amp;','%3A');
	$replace = array('&quot;','&quot;','&lt;','&lt; ','&gt','&gt','&amp;',':');
		return str_replace($search, $replace,$str_to_clean);
	}
public function CleanName($str){
	$str_to_clean=$str;
	$search  = array('%25','%2B','%26quot%3B','&quot;','%26amp%3B','&amp;');
	$replace = array('%','+','"','"',"&","&amp;");
		return str_replace($search, $replace,$str_to_clean);
	}
	
public function CleanSlider($str){
	$str_to_clean=$str;
	$search  = array('%3A','==');
	$replace = array(':','=');
		return str_replace($search, $replace,$str_to_clean);
	}	
public function RemoveFilter($query,$filter)	{
	
	parse_str($query, $output);
	unset($output[$filter]);
	return "&".http_build_query($output);
}
public function RemoveFilterOPtAtt($query,$filter,$key)	{
	parse_str($query, $output);
	unset($output[$filter][$key]);
	return "&".http_build_query($output);
}
public function getMySetting($key, $store_id = 0) {
		$data = array(); 
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `key` = '" . $this->db->escape($key) . "'");
		foreach ($query->rows as $result) {
			if (!$result['serialized']) {
				$data = $result['value'];
			} else {
				if(version_compare(VERSION,'2.1.0','>')) {
					$data = json_decode($result['value'],true);
				}else{
				$data = unserialize($result['value']);
				}
			}
		}
		return $data;
	}
	public function SeeMore($what,$url_where2go,$id,$dnd,$tipo,$namefinal) {
		$see_more_url="index.php?route=module/supercategorymenuadvancedseemore/seemore&amp;".$url_where2go.
		"&amp;what=".$what.
		"&amp;id=".$id.
		"&amp;dnd=seemore
		&amp;tipo=".urlencode($tipo)."&amp;name=".urlencode($namefinal);
		return $see_more_url;
	} 
	public function GetOptionid($key) {
		$query = $this->db->query("SELECT option_id FROM " . DB_PREFIX . "option_value WHERE option_value_id = '" . (int)$key . "'");
		return $query->row['option_id'];
	}
public  function GetProductInfosAsearchSelected($values_in_filter,$what_filter,$product_info_id,$filter,$wxy,$url_where2go,$url_pr,$url_limits,$url_search){
			$product_infos_selected = array();
			$this->language->load('module/supercategorymenuadvanced');
			
			
			
			
			$pro_info=$values_in_filter['productinfo'][$product_info_id];

			$pro_view= $pro_info['view'];
			
			//$url_where2go_clean=str_replace("&."=".$filter,"",);
			
			$url_where2go_clean=$this->RemoveFilter($this->CleanSlider($url_where2go),"filter_".$what_filter);
			
			
			
			$filter_url=$this->url->link('product/asearch', $url_where2go_clean.$url_pr).$url_limits.$url_search;
			$ajax_url=$url_where2go_clean.$url_pr.$url_limits.$url_search;
			$value=html_entity_decode($filter)=="NDDDDDN" ? html_entity_decode($no_data_text): html_entity_decode($filter);
			
			
			
			
			if($pro_view=="slider"){
				
				$valueslider=explode(":", $filter);
				
				if ($wxy=="w" || $wxy=="l" || $wxy=="h"){
				 $val=$this->length->format($valueslider[0] , $this->config->get('config_length_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'))." - " . $this->length->format($valueslider[1] , $this->config->get('config_length_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
				 }elseif($wxy=="wg" ) { 
				 $val=$this->weight->format($valueslider[0] , $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'))." - " . $this->weight->format($valueslider[1] , $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
				 }else{
				$val=$valueslider[0]." - " .$valueslider[1];
				}	
				
				
			}else{
				
				if ($wxy=="w" || $wxy=="l" || $wxy=="h"){
				   $val=$this->length->format($value , $this->config->get('config_length_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
				}elseif($wxy=="wg" ) { 
				   $val=$this->weight->format($value , $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
				}else{
					$val=$value;
				}	
				
			}
			
			
			$product_infos_selected=array(
						'href'		   => $this->CleanSlider($filter_url),
						'ajax_url'	   => $this->CleanSlider($ajax_url),
						'name'		   => html_entity_decode($val),
						'dnd'		   => html_entity_decode($this->language->get("entry_".$wxy)),					
				);	
return $product_infos_selected;
}

	

public  function GetProductInfosSelected($values_in_filter,$what,$url_where2go,$filter,$product_info_id,$what_filter,$data_settings,$no_data_text,$url_pr,$url_limits,$url_search){
			
	
			$this->language->load('module/supercategorymenuadvanced');

		    $see_more_text 					= $this->language->get('see_more_text');

		    $remove_filter_text 			= $this->language->get('remove_filter_text'); 

			//$url_where2go_clean=str_replace("&filter_".$what_filter."=".$filter,"",$this->CleanSlider($url_where2go));
			$url_where2go_clean=$this->RemoveFilter($this->CleanSlider($url_where2go),"filter_".$what_filter);
			
			$pro_info=$values_in_filter['productinfo'][$product_info_id];

			$pro_short_name= $pro_info['short_name'];

			$pro_view= $pro_info['view'];

			$pro_name=$this->language->get('entry_'.$pro_short_name);  
            
			$filter_url=$this->url->link('product/asearch', $url_where2go_clean.$url_pr).$url_limits.$url_search;
			$ajax_url=$url_where2go_clean.$url_pr.$url_limits.$url_search;
			$value=html_entity_decode($filter)=="NDDDDDN" ? html_entity_decode($no_data_text): html_entity_decode($filter);
		   
		   $see_more_url=($pro_view=="slider") ? '':"index.php?route=module/supercategorymenuadvancedseemore/seemore&amp;dnd=seemore&amp;name=".urlencode($value)."&amp;tipo=".$pro_short_name."&amp;id=".$product_info_id.$url_where2go_clean.$url_pr.$url_limits.$url_search;

		   $data_filtering=array(
						'href' 				=> $this->CleanSlider($filter_url),
						'ajax_url'			=> $this->CleanSlider($ajax_url),
						'see_more_text' 	=> $see_more_text,
						'remove_filter_text'=> $remove_filter_text,
						'name'				=> html_entity_decode($value),
						'see_more_url'		=> $see_more_url,
					);	
			//remove filter_name from string.
            $html='';
		
		    $html.=($pro_view=="slider") ? '': $this->GetHtmlSelected($data_filtering,$data_settings,$pro_short_name);
			$seleccionados=array(
						'id'		   => $product_info_id,
						'Tipo' 		   => $pro_short_name,
						'name'		   => html_entity_decode($value),
						'href'		   => $this->CleanSlider($filter_url),
						'ajax_url'	   => $this->CleanSlider($ajax_url),
						'see_more'	   => $url_where2go,
						'dnd'		   => html_entity_decode($pro_name),
						'html'		   => $html,
						'tip_div'	   => '',
				);	
		return $seleccionados;
        }
 public function GetView($str){
		 if ($str=="slider") {
			return "s";
		 }elseif ($str=="image"){
			return "i";						
		 }elseif($str=="list"){
			return "n";
		 }elseif($str=="sele"){
			return "n";
		 }elseif($str=="sept"){
			return "p";
		 }
	  }
	 public function GetProductInfo($int){
		$results=array(
		1=>"filter_width",
		2=>"filter_height",
		3=>"filter_length",
		4=>"filter_model",
		5=>"filter_sku",
		6=>"filter_upc",
		7=>"filter_location",
		8=>"filter_weight",
		9=>"filter_ean",
		10=>"filter_isbn",
		11=>"filter_mpn",
		12=>"filter_jan",);
		return $results[$int];
      }	
	  public function getoptionImage($key,$width, $height) {
		$query = $this->db->query("SELECT image FROM " . DB_PREFIX . "option_value WHERE option_value_id = '" . (int)$key . "'");
		$this->load->model('tool/image');
		if ($query->row['image']) {
					if ($width && $height ){
					   $image = $this->model_tool_image->resize($query->row['image'], $width,$height);
					}else{
						if (!file_exists(DIR_IMAGE . $query->row['image']) || !is_file(DIR_IMAGE . $query->row['image'])) {
							$image= '';
						} else{
							$info = getimagesize(DIR_IMAGE . $query->row['image']);
							$image = $this->model_tool_image->resize($query->row['image'],$info[0],$info[1]);
						}
					}
				} else {
					$image= '';
				}
		return $image;
	}	
	public function getAtributesFiltered($products = array(), $data= array(), $attributes =array(),$what=''){
		$cache = md5(http_build_query($data));
	    $string=http_build_query($data);
	    // commit
		$attributes_data = $this->getCacheSMBD(
		'attribute_filters_store_'.$what.'_('. 
		(int)$this->config->get('config_store_id') .').'. 
		(int)$data['filter_category_id'] . '.' . 
		(int)substr($data['filter_manufacturers_by_id'], 0, -1) . '.' . 
		(int)$this->config->get('config_language_id') . '.' . 
		$cache,
		(int)$data['filter_category_id'],
		(int)substr($data['filter_manufacturers_by_id'], 0, -1),
		$what);
		if (!$attributes_data) {	
		    $attributes_data = array();
			$sql = "SELECT pa.text, p.product_id, pa.attribute_id as id
				FROM " . DB_PREFIX . "product_attribute pa
				LEFT JOIN " . DB_PREFIX . "product p ON (pa.product_id=p.product_id) 
				WHERE
				p.product_id IN (".implode(', ',array_values($products)).")
				AND
				pa.attribute_id IN (".implode(', ',array_values($attributes)).")
				AND
				pa.language_id=". (int)$this->config->get('config_language_id');
			$query =  $this->db->query($sql);
			$value_total=1;
			foreach ($query->rows as $key=> $value){
				if (isset($attributes_data[$value['id']][$value['text']]['total'])){
					$value_total = $attributes_data[$value['id']][$value['text']]['total'] + 1;
				}else{
					$value_total=1;
				}
		       $attributes_data[$value['id']][$value['text']] = array(
					'total' => $value_total,
					'text'	=> $value['text'],
					'attribute_id' => $value['id']
				);
			}
		$this->setCacheSMBD(
		'attribute_filters_store_'.$what.'_('. 
		(int)$this->config->get('config_store_id') .').'. 
		(int)$data['filter_category_id'] . '.'  .
		(int)substr($data['filter_manufacturers_by_id'], 0, -1) . '.' . 
		(int)$this->config->get('config_language_id') . '.' . 
		$cache, 
		$attributes_data,
		(int)$data['filter_category_id'],
		(int)substr($data['filter_manufacturers_by_id'], 0, -1),
		$what,
		$string);	
		} 	
		return $attributes_data;
	}
public function getProductInfosFiltered($products = array(), $data= array(), $where, $id,$what=''){
		$cache = md5(http_build_query($data));
	    $string=http_build_query($data);
		$product_info_data =  $this->getCacheSMBD(
		$where.'_filters_store_'.$what.'_('. 
		(int)$this->config->get('config_store_id') .').'. 
		(int)$data['filter_category_id'] . '.' . 
		(int)substr($data['filter_manufacturers_by_id'], 0, -1) . '.' . 
		(int)$this->config->get('config_language_id') . '.' .  
		$cache,
		(int)$data['filter_category_id'],
		(int)substr($data['filter_manufacturers_by_id'], 0, -1),
		$what);
		if (!$product_info_data) {	
			$product_info_data = array();
			$sql = "SELECT ".$where.", COUNT( * ) AS total
			FROM " . DB_PREFIX . "product p			
			WHERE
			p.product_id IN (".implode(', ',array_values($products)).")			
			GROUP BY ".$where."";
				$query =  $this->db->query($sql);
				$value_total=1;
				foreach ($query->rows as $key=> $value){
				  $product_info_data[$id][$value[$where]] = array(
						'total' => $value['total'],
						'text'	=> $value[$where],
						'product_info_id' => $id
				  );
			     }
		$this->setCacheSMBD(
		$where.'filters_store_'.$what.'_('. 
		(int)$this->config->get('config_store_id') .').'. 
		(int)$data['filter_category_id'] . '.'  .
		(int)substr($data['filter_manufacturers_by_id'], 0, -1) . '.' . 
		(int)$this->config->get('config_language_id') . '.' . 
		$cache, 
		$product_info_data,
		(int)$data['filter_category_id'],
		(int)substr($data['filter_manufacturers_by_id'], 0, -1),
		$what,
		$string);	
		} 		
		return $product_info_data;
	}
public function getOptionsFiltered($products = array(), $data= array(), $options= array(),$what=''){
		
		
	    $set_module=  $this->model_module_supercategorymenuadvanced->getMySetting('SETTINGS_'.$this->config->get('config_store_id'),$this->config->get('config_store_id'));

        $option_stock		= isset($set_module['general_data']['option_stock'])? $set_module['general_data']['option_stock'] : false;
		
		$cache = md5(http_build_query($data));
	    $string=http_build_query($data);
		$options_data =  $this->getCacheSMBD(
		'options_filters_store_'.$what.'_('. 
		(int)$this->config->get('config_store_id') .').'. 
		(int)$data['filter_category_id'] . '.' . 
		(int)substr($data['filter_manufacturers_by_id'], 0, -1) . '.' . 
		(int)$this->config->get('config_language_id')  . '.' .  
		$cache,
		(int)$data['filter_category_id'],
		(int)substr($data['filter_manufacturers_by_id'], 0, -1),
		$what);
		if (!$options_data) {
		$options_data = array();	
			$sql="SELECT ovd.name as name, pov.option_id as id,p.product_id, op.image, ovd.option_value_id,op.sort_order
			FROM " . DB_PREFIX . "product_option_value pov 
			LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (pov.option_value_id=ovd.option_value_id) 
			LEFT JOIN " . DB_PREFIX . "product p ON (pov.product_id=p.product_id) 
			LEFT JOIN " . DB_PREFIX . "option_value op ON (pov.option_value_id=op.option_value_id) 
			
			WHERE
			p.product_id IN (".implode(', ',array_values($products)).") 
			AND 
			pov.quantity > 0 
			AND
			pov.option_id IN (".implode(', ',array_values($options)).")
			AND
			ovd.language_id = " . (int)$this->config->get('config_language_id') ." 
			ORDER BY op.sort_order ASC";
			
			/*if ($option_stock){
				$sql.=" AND pov.quantity > 0  ";
			}
			
			$sql.=" ORDER BY op.sort_order ASC";
			*/
			
			$query =  $this->db->query($sql);
			
			$value_total=1;
			foreach ($query->rows as $key=> $value){
				if (isset($options_data[$value['id']][$value['name']]['total'])){
					$value_total = $options_data[$value['id']][$value['name']]['total'] + 1;
				}else{
					$value_total=1;
				}
				$options_data[$value['id']][$value['name']] = array(
					'total' => $value_total,
					'text'	=> $value['name'],
					'option_id' => $value['id'],
					'image_thumb' =>$value['image'],
					'option_value_id'=>$value['option_value_id'],
					'order'=>$value['sort_order']
				);
			}
		$this->setCacheSMBD(
		'options_filters_store_'.$what.'_('. 
		(int)$this->config->get('config_store_id') .').'. 
		(int)$data['filter_category_id'] . '.'  .
		(int)substr($data['filter_manufacturers_by_id'], 0, -1) . '.' . 
		(int)$this->config->get('config_language_id') . '.' . 
		$cache, 
		$options_data,
		(int)$data['filter_category_id'],
		(int)substr($data['filter_manufacturers_by_id'], 0, -1),
		$what,
		$string);	
		} 	
			return $options_data;
	}
public function getReviewsFiltered($products = array(), $data= array(),$tipo,$what=''){
		$cache = md5(http_build_query($data));
	    $string=http_build_query($data);
		$reviews_data =  $this->getCacheSMBD(
		'reviews_by_products_store_'.$what.'_('. 
		(int)$this->config->get('config_store_id') .').'. 
		(int)$data['filter_category_id'] . '.' . 
		(int)substr($data['filter_manufacturers_by_id'], 0, -1) . '.' . 
		(int)$this->config->get('config_language_id') . '.' .  
		$cache,
		(int)$data['filter_category_id'],
		(int)substr($data['filter_manufacturers_by_id'], 0, -1),
		$what);
		if (!$reviews_data) {	
			$reviews_data = array();
			if ($tipo=="avg"){
			//echo $sql = "SELECT product_id,CONVERT(AVG(rating),signed) AS total FROM " . DB_PREFIX . "review r WHERE r.product_id IN (".implode(', ',array_values($products)).") AND r.status = '1' GROUP BY r.product_id order by total" ;
			
			$sql = "SELECT product_id,AVG(rating) AS total FROM " . DB_PREFIX . "review r WHERE r.product_id IN (".implode(', ',array_values($products)).") AND r.status = '1' GROUP BY r.product_id order by total" ;
			
			$query =  $this->db->query($sql);
			$value_total=0;
				foreach ($query->rows as $key=> $value){
					if(!empty($reviews_data)){
						$value_total = 1;
						if(array_key_exists((int)round($value['total']),$reviews_data)){
						$value_total = $reviews_data[round($value['total'])]['total'] + 1;
						}
					}else{
						$value_total = 1;
					}
						$reviews_data[round($value['total'])] = array(
							'total' => $value_total,
							'rating'	=> round($value['total']),
						);
				}
			}elseif ($tipo=="num"){
			$sql = "SELECT rating,Count(rating) as total FROM " . DB_PREFIX . "review r WHERE r.product_id IN (".implode(', ',array_values($products)).") AND r.status = '1' GROUP BY r.rating" ;	
			$query =  $this->db->query($sql);
			$value_total=0;
				foreach ($query->rows as $key=> $value){
					$reviews_data[round($value['rating'])] = array(
							'total' => (int)$value['total'],
							'rating'	=> round($value['rating']),
						);
				}
			}
		$this->setCacheSMBD(
		'reviews_by_products_store_'.$what.'_('. 
		(int)$this->config->get('config_store_id') .').'. 
		(int)$data['filter_category_id'] . '.'  .
		(int)substr($data['filter_manufacturers_by_id'], 0, -1) . '.' . 
		(int)$this->config->get('config_language_id') . '.' . 
		$cache, 
		$reviews_data,
		(int)$data['filter_category_id'],
		(int)substr($data['filter_manufacturers_by_id'], 0, -1),
		$what,
		$string);	
		} 	
			return $reviews_data;
	}
	public function getManufacturesFiltered($products = array(), $data= array(),$what=''){
		$cache = md5(http_build_query($data));
	    $string=http_build_query($data);
		$manufacturer_data =  $this->getCacheSMBD(
		'manufactures_by_products_store_'.$what.'_('. 
		(int)$this->config->get('config_store_id') .').'. 
		(int)$data['filter_category_id'] . '.' . 
		(int)substr($data['filter_manufacturers_by_id'], 0, -1) . '.' . 
		(int)$this->config->get('config_language_id')  . '.' .  
		$cache,
		(int)$data['filter_category_id'],
		(int)substr($data['filter_manufacturers_by_id'], 0, -1),
		$what);
		if (!$manufacturer_data) {	
			$manufacturer_data = array();
			$sql = "SELECT DISTINCT m.name, p.product_id, m.manufacturer_id,m.image,m.sort_order
				FROM " . DB_PREFIX . "manufacturer m 
				LEFT JOIN " . DB_PREFIX . "product p ON (m.manufacturer_id=p.manufacturer_id) 
				WHERE
				p.product_id IN (".implode(', ',array_values($products)).") 
				ORDER BY m.sort_order ASC";
				$query =  $this->db->query($sql);
				$value_total=0;
				foreach ($query->rows as $key=> $value){
					if(!empty($manufacturer_data)){
						$value_total = 1;
						if(array_key_exists($value['name'],$manufacturer_data)){
						$value_total = $manufacturer_data[$value['name']]['total'] + 1;
						}
					}else{
						$value_total = 1;
					}
						$manufacturer_data[$value['name']] = array(
							'total' => $value_total,
							'name'	=> $value['name'],
							'manufacturer_id' => $value['manufacturer_id'],
							'image'	=>$value['image'],
							'order' => $value['sort_order']
						);
				}
		$this->setCacheSMBD(
		'manufactures_by_products_store_'.$what.'_('. 
		(int)$this->config->get('config_store_id') .').'. 
		(int)$data['filter_category_id'] . '.'  .
		(int)substr($data['filter_manufacturers_by_id'], 0, -1) . '.' . 
		(int)$this->config->get('config_language_id') . '.' . 
		$cache, 
		$manufacturer_data,
		(int)$data['filter_category_id'],
		(int)substr($data['filter_manufacturers_by_id'], 0, -1),
		$what,
		$string);	
		} 	
			return $manufacturer_data;
	}
	public function getStocksFiltered($products = array(), $data= array(), $recalcular=false,$what=''){
		$cache = md5(http_build_query($data));
	    $string=http_build_query($data);
		$stockstatus_data =  $this->getCacheSMBD(
		'stocks_by_products_store_'.$what.'_('. 
		(int)$this->config->get('config_store_id') .').'. 
		(int)$data['filter_category_id'] . '.' . 
		(int)substr($data['filter_manufacturers_by_id'], 0, -1) . '.' . 
		(int)$this->config->get('config_language_id') . '.' .  
		$cache,
		(int)$data['filter_category_id'],
		(int)substr($data['filter_manufacturers_by_id'], 0, -1),
		$what);
		if (!$stockstatus_data) {	
		$stockstatus_data = array();
		$sql = "SELECT DISTINCT ss.name, p.product_id, ss.stock_status_id, quantity
			FROM " . DB_PREFIX . "stock_status ss 
			LEFT JOIN " . DB_PREFIX . "product p ON (ss.stock_status_id=p.stock_status_id) 
			WHERE
			p.product_id IN (".implode(', ',array_values($products)).")";
		   // $this->language->load('module/supercategorymenuadvanced');
			$query =  $this->db->query($sql);
			$value_total=0;
			foreach ($query->rows as $key=> $value){
				if($recalcular){
					($value['quantity'] > 0) ? $name=$this->language->get('in_stock_values') : $name=$value['name'];
				}else{
					$name=$value['name'];
				}
				if(!empty($stockstatus_data)){
					$value_total = 1;
					if(array_key_exists($name,$stockstatus_data)){
					$value_total = $stockstatus_data[$name]['total'] + 1;
					}
				}else{
					$value_total = 1;
				}
					$stockstatus_data[$name] = array(
						'total' => $value_total,
						'name'	=> $name,
						'stock_status_id' => $value['stock_status_id']
					);
			}
		$this->setCacheSMBD(
		'stocks_by_products_store_'.$what.'_('. 
		(int)$this->config->get('config_store_id') .').'. 
		(int)$data['filter_category_id'] . '.'  .
		(int)substr($data['filter_manufacturers_by_id'], 0, -1) . '.' . 
		(int)$this->config->get('config_language_id') . '.' . 
		$cache, 
		$stockstatus_data,
		(int)$data['filter_category_id'],
		(int)substr($data['filter_manufacturers_by_id'], 0, -1),
		$what,
		$string);	
		} 	
			return $stockstatus_data;
	}
	public function getCategoriesFiltered($products = array(), $data= array(),$what=''){
	   	$cache = md5(http_build_query($data));
	    $string=http_build_query($data);
		$subcategory_data =  $this->getCacheSMBD(
		'categories_filtered_by_products_store_'.$what.'_('. 
		(int)$this->config->get('config_store_id') .').'. 
		(int)$data['filter_category_id'] . '.' . 
		(int)substr($data['filter_manufacturers_by_id'], 0, -1) . '.' . 
		(int)$this->config->get('config_language_id') . '.' . 
		$cache,
		(int)$data['filter_category_id'],
		(int)substr($data['filter_manufacturers_by_id'], 0, -1),
		$what);
		if (!$subcategory_data) {
			$subcategory_data = array();
 			$sql= "SELECT * FROM " . DB_PREFIX . "category c 
			LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (c.category_id =p2c.category_id ) 
			LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id)
			LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id =c2s.category_id ) 
			WHERE 
			c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
			AND
			c.parent_id = '" . (int)$data['filter_category_id'] . "' 
			AND (c.metal_price IS NULL OR c.metal_price = 0)
			AND
			p2c.product_id IN (".implode(', ',array_values($products)).")
			and cd.language_id=". (int)$this->config->get('config_language_id') . "
			ORDER BY 
			c.sort_order, LCASE(cd.name)";
			$query =  $this->db->query($sql);
		$value_total=0;
			$subcategory_data = array();
            foreach ($query->rows as $key=> $value){
				if(!empty($subcategory_data)){
					$value_total = 1;
					if(array_key_exists($value['name'],$subcategory_data)){
					$value_total = $subcategory_data[$value['name']]['total'] + 1;
					}
				}else{
					$value_total = 1;
				}
					$subcategory_data[$value['name']] = array(
						'total' => $value_total,
						'name'	=> $value['name'],
						'category_id' => $value['category_id'],
						'order' => $value['sort_order'],
						'image' =>$value['image'],
					);
			}
		$this->setCacheSMBD(
		'categories_filtered_by_products_store_'.$what.'_('. 
		(int)$this->config->get('config_store_id') .').'. 
		(int)$data['filter_category_id'] . '.'  .
		(int)substr($data['filter_manufacturers_by_id'], 0, -1) . '.' . 
		(int)$this->config->get('config_language_id') . '.' . 
		$cache, 
		$subcategory_data,
		(int)$data['filter_category_id'],
		(int)substr($data['filter_manufacturers_by_id'], 0, -1),
		$what,
		$string);
		} 	
			return $subcategory_data;
	}
	public function getAttributeName($attribute_id) {
		$query = $this->db->query("SELECT name FROM " . DB_PREFIX . "attribute_description WHERE attribute_id = '" . (int)$attribute_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");
	return $query->row['name'];
	}
	public function getoptionName($option_id) {
		$query = $this->db->query("SELECT name FROM " . DB_PREFIX . "option_description WHERE option_id = '" . (int)$option_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");
	return $query->row['name'];
	}
	
	public function isNotHiddenCategory($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category WHERE category_id = '" . (int)$category_id . "' AND 	(metal_price IS NULL OR metal_price = 0)");
		return $query->row ? true : false;
	}
	
	public function getCategoryName($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int)$category_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");
		return $query->row['name'];
	}
	public function getManufacturerName($manufacturer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = '" . (int)$manufacturer_id. "'" );
		return $query->row['name'];
	}
	public function getProductsFiltered($data = array(),$data2=array(),$what=''){
		//die("THERE YOU GO");
		//die($what);
		//$data['filter_category_id'] = 3;
		
		if ($data['filter_category_id'] == 1){
			//to run on search page , bypass all other checks for category
			$data['filter_tag'] = $data['filter_name'];
		}
		//die(print_r($data));
		//echo "<pre>";print_r($data);echo"</pre>";
		//die();
		$cache = md5(http_build_query($data));
		$string = http_build_query($data);	
		$with_stock=$data2['option_stock'];
		//die(print_r($product_data));
	    $product_data =  $this->getCacheSMBD(
		'product_filters_store_'.$what.'_('. 
		(int)$this->config->get('config_store_id') .').'. 
		(int)$data['filter_category_id'] . '.' . 
		(int)substr($data['filter_manufacturers_by_id'], 0, -1) . '.' . 
		(int)$this->config->get('config_language_id') . '.' . 
		$cache,
		(int)$data['filter_category_id'],
		(int)substr($data['filter_manufacturers_by_id'], 0, -1),
		$what);
		
		//die(print_r($product_data));
	    
         if (!$product_data) {
         	//die("HHHHHHHHHH1");

         	if (!empty($data['filter_special'])){
         		//die("S:S:S:S");
				$product_id_NO_seleccionados=$this->GetProductsFilteredSpecial($data,$data2);
			}else{
				//die("GGGGGGGGG");
				//print_r($data2);
				//die();
				$Products_with_price_filtered=$this->GetProductsFilteredPrice($data,$data2);
				//print_r($Products_with_price_filtered);die();
				$Products_with_special_price_filtered=$this->GetProductsFilteredSpecial($data,$data2);
				//print_r($Products_with_special_price_filtered);
     			$product_id_NO_seleccionados=array_merge((array)$Products_with_price_filtered,(array)$Products_with_special_price_filtered);
     			//die(print_r($product_id_NO_seleccionados));
     			//die();
			}
			//die("HHHHHHHHHH2");

		if (!empty($data['filter_by_name'])) {
				//echo $data['filter_by_name'];
				//die($data['filter_by_name']);

				$explode_data=array();
				$explode_data= explode("@@@",$data['filter_by_name']);
				$explode_data2=array();
				$explode_data2= explode(",",$data['filter_ids']);
				$explode_data3=array(); //have the option_value_id
				$explode_data3= explode(",",$data['filter_options_by_ids']);
					//pov.option_id = '" . (int)$explode_data2[$i] . "'
				$i=0;
				$products_id_seleccionados=array();
				foreach ($explode_data as  $explode){
					//die("HHHHHHHHHH23");
				 $products_id_query=array();
					$pos = strpos($explode,'OPTTOP');
					
					if ($pos !== false) {// is an option
						//die("123");
						$sql="SELECT pov.product_id 
						FROM " . DB_PREFIX . "product_option_value pov 
						LEFT JOIN  " . DB_PREFIX . "option_value_description ovd ON (pov.option_value_id=ovd.option_value_id) 
						LEFT JOIN " . DB_PREFIX . "product p ON (pov.product_id = p.product_id)  
						LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
						WHERE
						ovd.option_value_id= '" . (int)$explode_data3[$i] . "'
						AND	ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
						AND p.status = '1' 
						AND p.date_available <= NOW() 
						AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
						if ($with_stock){
						$sql.=" AND pov.quantity > 0  ";
						}
					}
					$pos = strpos($explode,'ATTNNATT');
					if ($pos !== false) {// is an attribute
						//die("#$%");
						$sql= "SELECT p.product_id
							FROM " . DB_PREFIX . "product_attribute pa 
							LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) 
							LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) 
							LEFT JOIN " . DB_PREFIX . "product p ON (pa.product_id = p.product_id)  
							LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
							WHERE
							pa.attribute_id = '" . (int)$explode_data2[$i] . "'
							AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' 
							AND p.status = '1' 
							AND p.date_available <= NOW() 
							AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
							//FIX for empty attribute values.
							//die($sql);
							
							if ($explode=="NDDDDDNnATTNNATT"){
								//die("F");
								$sql.=" AND LCASE(pa.text)='' GROUP BY product_id";
							}elseif (strpos($explode, "sATTNNATT")){//is slider
								//die("K");
								utf8_strtolower(substr($this->Ampersand($explode),0,-9));
								$values=explode(":",utf8_strtolower(substr($this->Ampersand($explode),0,-9)));
								$sql .= " AND CONVERT(pa.text, signed) BETWEEN '".$this->db->escape($values[0])."' AND '".$this->db->escape($values[1])."'";
							}elseif (strpos($explode, "pATTNNATT")){ // is splitted value
								//die(print_r($explode));
								//RLIKE '[[:<:]]XL[[:>:]]'
								//\b
								if(strpos($explode,"|") !==false){
									//die("HELL");
									$fresh_var =explode("|",$this->db->escape(substr($this->Ampersand($explode),0,-9))); 
									$sql.=" AND LCASE(pa.text) REGEXP '[[:<:]]" . $this->db->escape(utf8_strtolower(substr($this->Ampersand($explode),0,-9))) . "[[:>:]]'";
									//$sql.=" OR LCASE(pa.text) REGEXP '[[:<:]]" . $this->db->escape(utf8_strtolower(substr($this->Ampersand($explode),10,-9))) . "[[:>:]]'";
									$sql.=" OR pa.text IN ('" .implode("','",$fresh_var)."')";

								}else{
									//die("HEAVEN");
									$sql.=" AND LCASE(pa.text) REGEXP '[[:<:]]" . $this->db->escape(utf8_strtolower(substr($this->Ampersand($explode),0,-9))) . "[[:>:]]'";
									$sql.=" OR LCASE(pa.text) = '" . $this->db->escape(utf8_strtolower(substr(str_replace("&amp;","&",$explode),0,-9))) . "'";
	
								} //waqar
								//die($sql);

								//$sql.=" AND LCASE(p.alpha) REGEXP '^" . $this->db->escape(utf8_strtolower(substr(str_replace("&amp;","&",$explode),0,-9))) . "$'";	
							}else{ // is list or select value
								if(strpos($explode,"|") !==false){
									//die("SKDLJF:KSD");
									$fresh_var =explode("|",$this->db->escape(substr($this->Ampersand($explode),0,-9))); 
									$sql.=" AND pa.text IN ('" .implode("','",$fresh_var)."')";	
								}else{
									//die("IIE");
									$sql.=" AND LCASE(pa.text) = '" . $this->db->escape(utf8_strtolower(substr($this->Ampersand($explode),0,-9)))."'";	
								} //waqar	
							

							}
							//echo $sql;
							//die($sql);
					}
						
					  //echo $sql;die('super catregory menu advance');
						$query2 =  $this->db->query($sql);
						foreach ($query2->rows as $key=> $value) {
							//die("ONE");
							$products_id_query[$key] = $value['product_id'];
					    }
					    //die(print_r($products_id_query));
						if(empty($products_id_seleccionados)){
							//die("ONEONEONE");
						    $products_id_seleccionados =$products_id_query;
						    //die(print_r($products_id_seleccionados));
						}else{
							//die("TWOTWOTWO");
							$products_id_seleccionados =array_intersect((array)$products_id_seleccionados,$products_id_query);
							//here is the part to make the menu multiselectable or not
							/***************************************************************************************************
							//$products_id_seleccionados =array_merge((array)$products_id_seleccionados,$products_id_query);
							****************************************************************************/
							//$products_id_seleccionados =array_merge((array)$products_id_seleccionados,$products_id_query);
						}
					$i++;
					//die("$$");
					}
					//die(print_r($products_id_seleccionados));
					//print_r($products_id_query); //returns records from data only.
					//die();
					//$result = $products_id_seleccionados;
					
			 		$result = array_intersect($product_id_NO_seleccionados, $products_id_seleccionados);
			 		//die(print_r($result));
					 foreach ($result as $key=> $value) {
					    $product_data[$key] = $value;
					}
			}else{
				$product_data = array();
				foreach ($product_id_NO_seleccionados as $result) {
					$product_data[$result] = $result;
				}
		}
		$this->setCacheSMBD(
		'product_filters_store_'.$what.'_('. 
		(int)$this->config->get('config_store_id') .').'. 
		(int)$data['filter_category_id'] . '.'  .
		(int)substr($data['filter_manufacturers_by_id'], 0, -1) . '.' . 
		(int)$this->config->get('config_language_id') . '.' . 
		$cache, 
		$product_data,
		(int)$data['filter_category_id'],
		(int)substr($data['filter_manufacturers_by_id'], 0, -1),
		$what,
		$string);
		} 
		
		//die(print_r($product_data));
		return $product_data;
	}
	private function GetProductsFilteredPrice($data = array(), $data2 = array()){
		$clearance_id=$data2['clearance'];
		$days=$data2['days'];
		$reviewtipo=$data2['reviews'];
		//die("YES");
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}
			$sql = "SELECT p.product_id,(SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, p.price AS price FROM " . DB_PREFIX . "product p 
				LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
				LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)
				LEFT JOIN " . DB_PREFIX . "product_special ps ON (p.product_id = ps.product_id)"; 
				if (!empty($data['filter_category_id'])) {
					$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";			
				}
			    //$data['filter_rating']=3;	
				if (!empty($data['filter_rating'])) {
					$sql .= " LEFT JOIN " . DB_PREFIX . "review r ON (p.product_id = r.product_id)";			
				}
			$sql .= " 
			WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
			AND p.status = '1' 
			AND p.date_available <= NOW() 
			AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'"; 
		   /* if (!empty($data['filter_category_id']) and $data['filter_category_id']!=0) {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}*/
			if (!empty($data['filter_category_id']) and $data['filter_category_id']!=0) {
 
                            $implode_data = array();
                                                                             
                            $implode_data[] = "p2c.category_id = '" . (int)$data['filter_category_id'] . "'";


                            $categories = $this->getCategoriesByParentId($data['filter_category_id']);

                            foreach ($categories as $category_id) {
                                            $implode_data[] = "p2c.category_id = '" . (int)$category_id . "'";
                            }

                            if ($data['filter_category_id']!=1)
                            	$sql .= " AND (" . implode(' OR ', $implode_data) . ")";
                            else
                            	$sql .= "";	
 
 
                        }
			if (!empty($data['filter_manufacturers_by_id']) and $data['filter_manufacturers_by_id']!=0) {
				$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturers_by_id'] . "'";
			}
			if (!empty($data['filter_stock_id'])) {
				$sql .= " AND p.stock_status_id = '" . (int)$data['filter_stock_id'] . "'";
			}
			if (isset($data['filter_min_price']) && !empty($data['filter_max_price'])) {
				//die("OF COURSE");
				$sql .= " AND p.price BETWEEN ".$data['filter_min_price']." AND ".$data['filter_max_price'];
			}
			if (!empty($data['filter_stock'])) {
				$sql .= " AND p.quantity > 0";
			}
			if (!empty($data['filter_clearance'])) {
				$sql .= " AND  p.stock_status_id='".$clearance_id."'";
			}
			if (!empty($data['filter_arrivals'])) {
				$sql .= " AND p.date_added >DATE_SUB(CURDATE(), INTERVAL ".$days." DAY)";
			}
			//$data['filter_rating']=3;
			if (!empty($data['filter_rating'])) {
				if($reviewtipo=="avg"){
					if (strpos($data['filter_rating'], ":")){ //is slider
					    $values=explode(":", $data['filter_rating']);
					    $sql .= " AND (AVG(r.rating) BETWEEN ".$values[0]." AND ".$values[1]." AND r.status='1') ";
			       	}else{
						$sql .= " AND (SELECT CONVERT(AVG(rating) ,signed) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id)='".$data['filter_rating']."'" ;
					}
				}else{
					$sql .= " AND r.rating='".$data['filter_rating']."'" ;
				}
			}
			if (!empty($data['filter_width'])) {
				if (strpos($data['filter_width'], ":")){ //is slider
			 		$values=explode(":", $data['filter_width']);
					$sql .= " AND p.width BETWEEN ".$values[0]." AND ".$values[1];
				}else{
					if ($data['filter_width']=="NDDDDDN"){
						$sql .= " AND p.width=''" ;
					}else{
						$sql .= " AND p.width='".$data['filter_width']."'" ;
					}
				}
			}
			if (!empty($data['filter_height'])) {
				if (strpos($data['filter_height'], ":")){ //is slider
					$values=explode(":", $data['filter_height']);
					$sql .= " AND p.height BETWEEN ".$values[0]." AND ".$values[1];
				}else{
					if ($data['filter_height']=="NDDDDDN"){
						$sql .= " AND p.height=''" ;
					}else{
						$sql .= " AND p.height='".$data['filter_height']."'" ;
					}
				}
			}
			if (!empty($data['filter_length'])) {
				if (strpos($data['filter_length'], ":")){ //is slider
					$values=explode(":", $data['filter_length']);
					$sql .= " AND p.length BETWEEN ".$values[0]." AND ".$values[1];
				}else{
					if ($data['filter_length']=="NDDDDDN"){
						$sql .= " AND p.length=''" ;
					}else{
						$sql .= " AND p.length='".$data['filter_length']."'" ;
					}
				}
			}
			if (!empty($data['filter_model'])) {
				if (strpos($data['filter_model'], ":")){ //is slider
					$values=explode(":", $data['filter_model']);
					$sql .= " AND p.model BETWEEN ".$values[0]." AND ".$values[1];
				}else{
					if ($data['filter_model']=="NDDDDDN"){
						$sql .= " AND p.model=''" ;
					}else{
						$sql .= " AND p.model='".$data['filter_model']."'" ;
					}
				}
			}
			if (!empty($data['filter_sku'])) {
				if (strpos($data['filter_sku'], ":")){ //is slider
					$values=explode(":", $data['filter_sku']);
					$sql .= " AND p.sku BETWEEN ".$values[0]." AND ".$values[1];
				}else{
					if ($data['filter_sku']=="NDDDDDN"){
						$sql .= " AND p.sku=''" ;
					}else{
						$sql .= " AND p.sku='".$data['filter_sku']."'" ;
					}
				}
			}
			if (!empty($data['filter_upc'])) {
				if (strpos($data['filter_upc'], ":")){ //is slider
					$values=explode(":", $data['filter_upc']);
					$sql .= " AND p.upc BETWEEN ".$values[0]." AND ".$values[1];
				}else{
					if ($data['filter_upc']=="NDDDDDN"){
						$sql .= " AND p.upc=''" ;
					}else{
						$sql .= " AND p.upc='".$data['filter_upc']."'" ;
					}
				}
			}			
			if (!empty($data['filter_location'])) {
				if (strpos($data['filter_location'], ":")){ //is slider
					$values=explode(":", $data['filter_location']);
					$sql .= " AND p.location BETWEEN ".$values[0]." AND ".$values[1];
				}else{
					if ($data['filter_location']=="NDDDDDN"){
						$sql .= " AND p.location=''" ;
					}else{
						$sql .= " AND p.location='".$data['filter_location']."'" ;
					}
				}
			}				
			if (!empty($data['filter_weight'])) {
				if (strpos($data['filter_weight'], ":")){ //is slider
					$values=explode(":", $data['filter_weight']);
					$sql .= " AND p.weight BETWEEN ".$values[0]." AND ".$values[1];
				}else{
					if ($data['filter_weight']=="NDDDDDN"){
						$sql .= " AND p.weight=''" ;
					}else{
						$sql .= " AND p.weight='".$data['filter_weight']."'" ;
					}
				}
			}				
			if (!empty($data['filter_ean'])) {
				if (strpos($data['filter_ean'], ":")){ //is slider
					$values=explode(":", $data['filter_ean']);
					$sql .= " AND p.ean BETWEEN ".$values[0]." AND ".$values[1];
				}else{
					if ($data['filter_ean']=="NDDDDDN"){
						$sql .= " AND p.ean=''" ;
					}else{
						$sql .= " AND p.ean='".$data['filter_ean']."'" ;
					}
				}
			}				
			if (!empty($data['filter_isbn'])) {
				if (strpos($data['filter_isbn'], ":")){ //is slider
					$values=explode(":", $data['filter_isbn']);
					$sql .= " AND p.isbn BETWEEN ".$values[0]." AND ".$values[1];
				}else{
					if ($data['filter_isbn']=="NDDDDDN"){
						$sql .= " AND p.isbn=''" ;
					}else{
						$sql .= " AND p.isbn='".$data['filter_isbn']."'" ;
					}
				}
			}				
			if (!empty($data['filter_mpn'])) {
				if (strpos($data['filter_mpn'], ":")){ //is slider
					$values=explode(":", $data['filter_mpn']);
					$sql .= " AND p.mpn BETWEEN ".$values[0]." AND ".$values[1];
				}else{
					if ($data['filter_mpn']=="NDDDDDN"){
						$sql .= " AND p.mpn=''" ;
					}else{
						$sql .= " AND p.mpn='".$data['filter_mpn']."'" ;
					}
				}
			}				
			if (!empty($data['filter_jan'])) {
				if (strpos($data['filter_jan'], ":")){ //is slider
					$values=explode(":", $data['filter_jan']);
					$sql .= " AND p.jan BETWEEN ".$values[0]." AND ".$values[1];
				}else{
					if ($data['filter_jan']=="NDDDDDN"){
						$sql .= " AND p.jan=''" ;
					}else{
						$sql .= " AND p.jan='".$data['filter_jan']."'" ;
					}
				}
			}
					if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";
			if (!empty($data['filter_name'])) {
				$implode = array();
				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));
				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}
				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}
				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}
			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}
			if (!empty($data['filter_tag'])) {
				$sql .= "pd.tag LIKE '%" . $this->db->escape($data['filter_tag']) . "%'";
			}
			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}
			$sql .= ")";
		}
			$sql .= " GROUP BY p.product_id";
			(isset($data['filter_min_price']) && !empty($data['filter_min_price'])) ? $min_price=$data['filter_min_price'] : $min_price="no";
			!empty($data['filter_max_price'])? $max_price=$data['filter_max_price'] : $max_price="no";
	 		$product_data = array();
	 		//die($sql);
	 		$query = $this->db->query($sql);
			foreach ($query->rows as $key=> $value) {
				if ($min_price=="no"){
					$product_data["p".$value['product_id']] = $value['product_id'];
				}else{
					!empty($value['special']) ? $special_price=$value['special'] : $special_price=$min_price;
					if (($value['price'] <= $max_price || $value['price'] >= $max_price ) && ($special_price >= $min_price )){
						 $product_data["p".$value['product_id']] = $value['product_id'];
					}
				}
			}
	return	$product_data;
	}
	public  function GetProductsFilteredSpecial($data = array(), $data2 = array()){
		$clearance_id=$data2['clearance'];
		$days=$data2['days'];
		$reviewtipo=$data2['reviews'];
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	
		$sql = "SELECT p.product_id,ps.price AS special, p.price AS price FROM " . DB_PREFIX . "product p 
			LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
			LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)
			LEFT JOIN " . DB_PREFIX . "product_special ps ON (p.product_id = ps.product_id)"; 
			if (!empty($data['filter_category_id'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";			
			}
			
			//rating filter
			if (!empty($data['filter_rating'])) {
					$sql .= " LEFT JOIN " . DB_PREFIX . "review r ON (p.product_id = r.product_id)";			
				}
			$sql .= " WHERE ps.customer_group_id = '" . (int)$customer_group_id . "' 
			AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
			AND p.status = '1' AND p.date_available <= NOW() 
			AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
			AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))"; 
	        /*if (!empty($data['filter_category_id']) and $data['filter_category_id']!=0) {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}*/
			if (!empty($data['filter_category_id']) and $data['filter_category_id']!=0) {
 
                            $implode_data = array();
                                                                             
                            $implode_data[] = "p2c.category_id = '" . (int)$data['filter_category_id'] . "'";


                            $categories = $this->getCategoriesByParentId($data['filter_category_id']);

                            foreach ($categories as $category_id) {
                                            $implode_data[] = "p2c.category_id = '" . (int)$category_id . "'";
                            }

                            if ($data['filter_category_id']!=1)
                            	$sql .= " AND (" . implode(' OR ', $implode_data) . ")";
                            else
                            	$sql .= "";
 
 
                        }
			if (!empty($data['filter_manufacturers_by_id']) and $data['filter_manufacturers_by_id']!=0) {
				$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturers_by_id'] . "'";
			}
			if (!empty($data['filter_stock_id'])) {
				$sql .= " AND p.stock_status_id = '" . (int)$data['filter_stock_id'] . "'";
			}
			if (isset($data['filter_min_price']) && !empty($data['filter_max_price'])) {
				$sql .= "AND (ps.price BETWEEN ".$data['filter_min_price']." AND ".$data['filter_max_price'].") ";
			}
			if (!empty($data['filter_stock'])) {
				$sql .= " AND p.quantity > 0";
			}
			if (!empty($data['filter_clearance'])) {
				$sql .= " AND  p.stock_status_id='".$clearance_id."'";
			}
			if (!empty($data['filter_arrivals'])) {
				$sql .= " AND p.date_added >DATE_SUB(CURDATE(), INTERVAL ".$days." DAY)";
			}
			if (!empty($data['filter_rating'])) {
				if($reviewtipo=="avg"){
					if (strpos($data['filter_rating'], ":")){ //is slider
					    $values=explode(":", $data['filter_rating']);
					    $sql .= " AND (AVG(r.rating) BETWEEN ".$values[0]." AND ".$values[1]." AND r.status='1') ";
			       	}else{
						$sql .= " AND (SELECT CONVERT(AVG(rating) ,signed) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id)='".$data['filter_rating']."'" ;
					}
				}else{
					$sql .= " AND r.rating='".$data['filter_rating']."'" ;
				}
			}
			if (!empty($data['filter_width'])) {
				if (strpos($data['filter_width'], ":")){ //is slider
			 		$values=explode(":", $data['filter_width']);
					$sql .= " AND p.width BETWEEN ".$values[0]." AND ".$values[1];
				}else{
					if ($data['filter_width']=="NDDDDDN"){
						$sql .= " AND p.width=''" ;
					}else{
						$sql .= " AND p.width='".$data['filter_width']."'" ;
					}
				}
			}
			if (!empty($data['filter_height'])) {
				if (strpos($data['filter_height'], ":")){ //is slider
					$values=explode(":", $data['filter_height']);
					$sql .= " AND p.height BETWEEN ".$values[0]." AND ".$values[1];
				}else{
					if ($data['filter_height']=="NDDDDDN"){
						$sql .= " AND p.height=''" ;
					}else{
						$sql .= " AND p.height='".$data['filter_height']."'" ;
					}
				}
			}
			if (!empty($data['filter_length'])) {
				if (strpos($data['filter_length'], ":")){ //is slider
					$values=explode(":", $data['filter_length']);
					$sql .= " AND p.length BETWEEN ".$values[0]." AND ".$values[1];
				}else{
					if ($data['filter_length']=="NDDDDDN"){
						$sql .= " AND p.length=''" ;
					}else{
						$sql .= " AND p.length='".$data['filter_length']."'" ;
					}
				}
			}
			if (!empty($data['filter_model'])) {
				if (strpos($data['filter_model'], ":")){ //is slider
					$values=explode(":", $data['filter_model']);
					$sql .= " AND p.model BETWEEN ".$values[0]." AND ".$values[1];
				}else{
					if ($data['filter_model']=="NDDDDDN"){
						$sql .= " AND p.model=''" ;
					}else{
						$sql .= " AND p.model='".$data['filter_model']."'" ;
					}
				}
			}
			if (!empty($data['filter_sku'])) {
				if (strpos($data['filter_sku'], ":")){ //is slider
					$values=explode(":", $data['filter_sku']);
					$sql .= " AND p.sku BETWEEN ".$values[0]." AND ".$values[1];
				}else{
					if ($data['filter_sku']=="NDDDDDN"){
						$sql .= " AND p.sku=''" ;
					}else{
						$sql .= " AND p.sku='".$data['filter_sku']."'" ;
					}
				}
			}
			if (!empty($data['filter_upc'])) {
				if (strpos($data['filter_upc'], ":")){ //is slider
					$values=explode(":", $data['filter_upc']);
					$sql .= " AND p.upc BETWEEN ".$values[0]." AND ".$values[1];
				}else{
					if ($data['filter_upc']=="NDDDDDN"){
						$sql .= " AND p.upc=''" ;
					}else{
						$sql .= " AND p.upc='".$data['filter_upc']."'" ;
					}
				}
			}			
			if (!empty($data['filter_location'])) {
				if (strpos($data['filter_location'], ":")){ //is slider
					$values=explode(":", $data['filter_location']);
					$sql .= " AND p.location BETWEEN ".$values[0]." AND ".$values[1];
				}else{
					if ($data['filter_location']=="NDDDDDN"){
						$sql .= " AND p.location=''" ;
					}else{
						$sql .= " AND p.location='".$data['filter_location']."'" ;
					}
				}
			}				
			if (!empty($data['filter_weight'])) {
				if (strpos($data['filter_weight'], ":")){ //is slider
					$values=explode(":", $data['filter_weight']);
					$sql .= " AND p.weight BETWEEN ".$values[0]." AND ".$values[1];
				}else{
					if ($data['filter_weight']=="NDDDDDN"){
						$sql .= " AND p.weight=''" ;
					}else{
						$sql .= " AND p.weight='".$data['filter_weight']."'" ;
					}
				}
			}				
			if (!empty($data['filter_ean'])) {
				if (strpos($data['filter_ean'], ":")){ //is slider
					$values=explode(":", $data['filter_ean']);
					$sql .= " AND p.ean BETWEEN ".$values[0]." AND ".$values[1];
				}else{
					if ($data['filter_ean']=="NDDDDDN"){
						$sql .= " AND p.ean=''" ;
					}else{
						$sql .= " AND p.ean='".$data['filter_ean']."'" ;
					}
				}
			}				
			if (!empty($data['filter_isbn'])) {
				if (strpos($data['filter_isbn'], ":")){ //is slider
					$values=explode(":", $data['filter_isbn']);
					$sql .= " AND p.isbn BETWEEN ".$values[0]." AND ".$values[1];
				}else{
					if ($data['filter_isbn']=="NDDDDDN"){
						$sql .= " AND p.isbn=''" ;
					}else{
						$sql .= " AND p.isbn='".$data['filter_isbn']."'" ;
					}
				}
			}				
			if (!empty($data['filter_mpn'])) {
				if (strpos($data['filter_mpn'], ":")){ //is slider
					$values=explode(":", $data['filter_mpn']);
					$sql .= " AND p.mpn BETWEEN ".$values[0]." AND ".$values[1];
				}else{
					if ($data['filter_mpn']=="NDDDDDN"){
						$sql .= " AND p.mpn=''" ;
					}else{
						$sql .= " AND p.mpn='".$data['filter_mpn']."'" ;
					}
				}
			}				
			if (!empty($data['filter_jan'])) {
				if (strpos($data['filter_jan'], ":")){ //is slider
					$values=explode(":", $data['filter_jan']);
					$sql .= " AND p.jan BETWEEN ".$values[0]." AND ".$values[1];
				}else{
					if ($data['filter_jan']=="NDDDDDN"){
						$sql .= " AND p.jan=''" ;
					}else{
						$sql .= " AND p.jan='".$data['filter_jan']."'" ;
					}
				}
			}
					if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";
			if (!empty($data['filter_name'])) {
				$implode = array();
				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));
				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}
				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}
				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}
			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}
			if (!empty($data['filter_tag'])) {
				$sql .= "pd.tag LIKE '%" . $this->db->escape($data['filter_tag']) . "%'";
			}
			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}
			$sql .= ")";
		}
			$sql .= " GROUP BY p.product_id";
			(isset($data['filter_min_price']) && !empty($data['filter_min_price'])) ? $min_price=$data['filter_min_price'] : $min_price="no";
			!empty($data['filter_max_price'])? $max_price=$data['filter_max_price'] : $max_price="no";
	 		$product_data = array();
	 		$query = $this->db->query($sql);
			foreach ($query->rows as $key=> $value) {
				if ($min_price=="no"){
					 $product_data["p".$value['product_id']] = $value['product_id'];
				}else{
					if ($value['special'] <= $min_price || $value['special'] >= $min_price ){
						$product_data["p".$value['product_id']] = $value['product_id'];
					}
				}
			}
	return	$product_data;
	}
	/*
	public function CleanName($str){
      return html_entity_decode($str);
    }*/
	public function SeoFix($str){
		$str_seo_fix=$str;
		$search  = array("%20","%23","%24","%25","%26","%40","%60","%2F","%3A","%3B","%3C","%3D","%3E","%3F","%5B","%5C","%5D","%5E","%7B","%7C","%7D","%7E","%22","%27","%2B","%2C");
		$replace = array("%2520","%2523","%2524","%2525","%2526","%2540","%2560","%252F","%253A","%253B","%253C","%253D","%253E","%253F","%255B","%255C","%255D","%255E","%257B","%257C","%257D","%257E","%2522","%2527","%252B","%252C");
		if ($this->config->get('config_seo_url')){
			return str_replace($search, $replace,$str_seo_fix);
		}else{
			return $str_seo_fix;
		}
	}
	public function formatMoney($number) {
	    return ($number*$this->currency->getValue());
  	}
	public function UnformatMoney($number,$currency='') {
	    return ($number/$this->currency->getValue($currency));
  	}
    public function formatCurrency($value = '', $format = true) {
		$decimal_place = $this->currency->getDecimalPlace();
		if ($format) {
			$decimal_point = $this->language->get('decimal_point');
		} else {
			$decimal_point = '.';
		}
		if ($format) {
			$thousand_point = $this->language->get('thousand_point');
		} else {
			$thousand_point = '';
		}
    	$string = number_format(round($value, (int)$decimal_place), (int)$decimal_place, $decimal_point, $thousand_point);
    	return $string;
  	}
     public function getRanges($intMin,$intMax,$intRanges=5,$prices=array(),$productos = array(),$currency,$is_tax,$tax_id,$cat_id,$what='') {
	$intRange =$price_diff=$intMax-$intMin;
	$intIncrement = ceil(abs($intRange/$intRanges));
	$intRanges;
	$arrRanges = array(); $arrPrices=array();
	/*if ($intIncrement < 10){ //the minimun between min and max must be 10
		$arrRanges[]=$intMax;
		   $f_min_price=$this->UnformatMoney($intMin,$currency);
		   $f_max_price=$this->UnformatMoney($intMax,$currency);
		    if ( $this->config->get('config_tax')&& $is_tax) {
				    $tax_value= $this->tax->calculate(1, $tax_id, $this->config->get('config_tax'));
					$f_min_price=floor( $f_min_price/$tax_value ); 
					$f_max_price=ceil( $f_max_price/$tax_value );
			}
		$arrayPrices[]=array(
				'prices'	=> "%s".$intMin."%s",
				'intMax' 	=> $intMax,
				'intMin' 	=> $intMin,
				'total' 	=> (int)$this->getTotalPrices($f_min_price,$f_max_price,$productos),
			);
	}else{ */
		for($i=0;$i<$intRanges;$i++) {
			$arrRanges[] = $i==0 || $i==($intRanges-1)?$i==0?$intMin:$intMax:$intMin+($i*$intIncrement);
		}
	    $j=0;
        foreach($arrRanges as $key=>$value) { 
		if ($j == count($arrRanges)-1){
             break;
        }else{
		   $intMin = $key == 0?$value:$arrRanges[($key)];
		   $intMax = $arrRanges[($key+1)];
           $f_min_price=$this->UnformatMoney($intMin,$currency);
		   $f_max_price=$this->UnformatMoney($intMax,$currency);
		      if ( $this->config->get('config_tax')&& $is_tax) {
				    $tax_value= $this->tax->calculate(1, $tax_id, $this->config->get('config_tax'));
					$f_min_price=($j>0)?ceil( $f_min_price/$tax_value )+0.1:floor( $f_min_price/$tax_value );
				    $f_max_price= ceil($f_max_price/$tax_value) ;
			  }else{
				  $f_min_price= ($j>0) ? $f_min_price+0.1:$f_min_price; 
				  $f_max_price= $f_max_price;
			  }
		  	$arrayPrices[]=array(
				'prices'	=> "%s ".$this->formatCurrency($intMin)." %s - %s ".$this->formatCurrency($intMax)." %s",
				'intMax' 	=> $intMax,
				'intMin' 	=> $intMin,
				'total' 	=> (int)$this->getTotalPrices($f_min_price,$f_max_price,$productos,$cat_id,$what),
			);
		  $j++;
		}
	//	}'prices'	=> "%s ".$intMin." %s - %s ".$intMax." %s",
	}
    return $arrayPrices;
}
public function getSmartRanges($intMin,$intMax,$intRanges=5,$prices=array(),$productos = array(),$currency,$is_tax,$tax_id,$cat_id,$what='',$ranges) {
    $j = 0;
    foreach ($ranges as $range) {
			$intMin = $range['min'];
			$intMax = $range['max'];
			$f_min_price=$this->UnformatMoney($intMin,$currency);
			$f_max_price=$this->UnformatMoney($intMax,$currency);
			  if ( $this->config->get('config_tax')&& $is_tax) {
					$tax_value= $this->tax->calculate(1, $tax_id, $this->config->get('config_tax'));
					$f_min_price=($j>0)?ceil( $f_min_price/$tax_value )+0.1:floor( $f_min_price/$tax_value );
					$f_max_price= ceil($f_max_price/$tax_value) ;
			  }else{
				  $f_min_price= ($j>0) ? $f_min_price+0.1:$f_min_price; 
				  $f_max_price= $f_max_price;
			  }
			$arrayPrices[]=array(
			'prices'	=> "%s ".$this->formatCurrency($range['min'])." %s - %s ".$this->formatCurrency($range['max'])." %s",
			'intMax' 	=> $range['max'],
			'intMin' 	=> $range['min'],
			'total' 	=> (int)$this->getTotalPrices($f_min_price,$f_max_price,$productos,$cat_id,$what),
		);
		$j++;
	}
	
    return $arrayPrices;
}
	public function getTotalPrices($intMin,$intMax,$productos,$cat_id,$what=''){
	if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	
			$cache = md5($intMin.$intMax.http_build_query($productos).$cat_id.$what);
	    	$string=http_build_query($productos);
			$products_count =  $this->getCacheSMBD(
			'price_ranges_store_'.$what.'_('. 
			(int)$this->config->get('config_store_id') .').'. 
			(int)$cat_id . '.' . 
			(int)$cat_id . '.' . 
			(int)$this->config->get('config_language_id') . '.' .  
			(int)$customer_group_id  . '.' . 
			$cache,
			(int)$cat_id,
			(int)$cat_id,
			$what);
			if (!$products_count){
			$products_count=array();
			$sql= "SELECT ps.product_id FROM  " . DB_PREFIX . "product_special ps 
			LEFT JOIN " . DB_PREFIX . "product p ON (p.product_id = ps.product_id)
			LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)
			AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
			AND p.status = '1' AND p.date_available <= NOW() 
			WHERE
			ps.customer_group_id = '" . (int)$customer_group_id . "'
			AND
			ps.product_id IN (".implode(', ',array_values($productos)).") 
			AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))		
			AND ps.price BETWEEN ".$intMin." AND ".$intMax." ";
			$query = $this->db->query($sql);
			foreach ($query->rows as $key=> $value){
				$products_count[$value['product_id'].'id']=$value['product_id'];
			}
			$sql = "SELECT p.product_id,(SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND
			ps.product_id IN (".implode(', ',array_values($productos)).") AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, p.price AS price FROM " . DB_PREFIX . "product p 
				LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
				LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)
				LEFT JOIN " . DB_PREFIX . "product_special ps ON (p.product_id = ps.product_id)									
			WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
			AND p.status = '1' 
			AND p.date_available <= NOW() 
			AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
			AND
			p.product_id IN (".implode(', ',array_values($productos)).") 
			AND p.price BETWEEN ".$intMin." AND ".$intMax."";
			$query = $this->db->query($sql);
			foreach ($query->rows as $key=> $value){
				if(isset($value['special'])){
					if ($value['special'] >= $intMin){
						$products_count[$value['product_id'].'id']=$value['product_id'];
					}
				}else{
				$products_count[$value['product_id'].'id']=$value['product_id'];
				}
			}
			$this->setCacheSMBD(
			'price_ranges_store_'.$what.'_('. 
			(int)$this->config->get('config_store_id') .').'. 
			(int)$cat_id . '.' . 
			(int)$cat_id . '.' . 
			(int)$this->config->get('config_language_id') . '.' .  
			(int)$customer_group_id  . '.' .  
			$cache,
			$products_count,
			(int)$cat_id,
			(int)$cat_id,
			$what,
			$string);
	        }
		return count($products_count);
	}
	public function getProductsPriceandSpecial($products = array(), $data= array(),$what=''){
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	
	    $cache = md5(http_build_query($data));
	    $string=http_build_query($data);
		$prices_data =  $this->getCacheSMBD(
		'Prices_filtered_by_products_store_'.$what.'_('. 
		(int)$this->config->get('config_store_id') .')_customer('.
		(int)$customer_group_id.').'.
		(int)$data['filter_category_id'] . '.' . 
		(int)substr($data['filter_manufacturers_by_id'], 0, -1) . '.' . 
		(int)$this->config->get('config_language_id')  . '.' . 
		$cache,
		(int)$data['filter_category_id'],
		(int)substr($data['filter_manufacturers_by_id'], 0, -1),
		$what);
		if (!$prices_data) {
			$prices= array();
			$special_prices= array();
			$sql= "SELECT p.price, p.product_id FROM  " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
			WHERE p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND p.product_id IN (".implode(', ',array_values($products)).") ORDER BY p.price DESC";
			$query = $this->db->query($sql);
			foreach ($query->rows as $key=> $value){
				$prices[$value['product_id'].'id']=$value['price'];
			}
			$sql= "SELECT ps.price, ps.product_id FROM  " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (ps.product_id = p2s.product_id) WHERE  ps.customer_group_id = '" . (int)$customer_group_id . "' AND ps.product_id IN (".implode(', ',array_values($products)).") AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))		
			ORDER BY ps.price DESC";
			$query = $this->db->query($sql);
			if ($query->rows){
				foreach ($query->rows as $key=> $value){
					$special_prices[$value['product_id'].'id']=$value['price'];
				}
			}
			$new_array=array_merge($prices,$special_prices);
			$prices_data=array();
			$prices_data = array(
				'PriceMax' => max($new_array),
				'PriceMin'	=> min($new_array)
			);
		 $this->setCacheSMBD(
		'Prices_filtered_by_products_store_'.$what.'_('. 
		(int)$this->config->get('config_store_id') .')_customer('.
		(int)$customer_group_id.').'.
		(int)$data['filter_category_id'] . '.' . 
		(int)substr($data['filter_manufacturers_by_id'], 0, -1) . '.' . 
		(int)$this->config->get('config_language_id') . '.' .  
		$cache,
		$prices_data,
		(int)$data['filter_category_id'],
		(int)substr($data['filter_manufacturers_by_id'], 0, -1),
		$what,
		$string);	
	}
			return $prices_data;
	}
	public function calculateTAX($price,$taxclass_id){
    	$tax_rates= $this->tax->getRates(100,$taxclass_id);
		$amount=0;
		foreach ($tax_rates as $tax_rate) {
			if ($tax_rate['type'] == 'F') {
				$amount += $tax_rate['amount'];
			//$price=$price-$tax_rate['amount'];echo "<br>";
			} elseif ($tax_rate['type'] == 'P') {
				$amount += ($price / 100 * $tax_rate['amount']);
			}
		}
	return	$price-$amount;
	}
	public function getStocksSpecial($products = array(), $data= array(),$what=''){
		$cache = md5(http_build_query($data));
	    $string=http_build_query($data);
		if ($this->customer->isLogged()) {
				$customer_group_id = $this->customer->getGroupId();
			} else {
				$customer_group_id = $this->config->get('config_customer_group_id');
			}	
		$special_data = $this->getCacheSMBD(
		'specialprice_filters_store_'.$what.'_('. 
		(int)$this->config->get('config_store_id') .').'. 
		(int)$data['filter_category_id'] . '.' . 
		(int)substr($data['filter_manufacturers_by_id'], 0, -1) . '.' . 
		(int)$this->config->get('config_language_id') . '.' . 
		(int)$this->config->get('customer_group_id') . '.' . 
		$cache,
		(int)$data['filter_category_id'],
		(int)substr($data['filter_manufacturers_by_id'], 0, -1),
		$what);
		if (!$special_data) {	
			$special_data = array();
			$sql = "SELECT COUNT(DISTINCT ps.product_id) AS total FROM " . DB_PREFIX . "product_special ps 
			LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) 
			LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
			WHERE
			p.product_id IN (".implode(', ',array_values($products)).") AND
			p.status = '1' AND 
			p.date_available <= NOW() AND 
			p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND 
			ps.customer_group_id = '" . (int)$customer_group_id . "' 
			AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))";
			$query = $this->db->query($sql);
			if (isset($query->row['total'])) {
				$special_data= $query->row['total'];
			} else {
				$special_data= "no_special";	
			}
			$this->setCacheSMBD(
			'specialprice_filters_store_'.$what.'_('. 
			(int)$this->config->get('config_store_id') .').'. 
			(int)$data['filter_category_id'] . '.'  .
			(int)substr($data['filter_manufacturers_by_id'], 0, -1) . '.' . 
			(int)$this->config->get('config_language_id') . '.' . 
			(int)$this->config->get('customer_group_id') . '.' . 
			$cache, 
			$special_data,
			(int)$data['filter_category_id'],
			(int)substr($data['filter_manufacturers_by_id'], 0, -1),
			$what,
			$string);
		}
		return $special_data;
	}
	public function getStocksClearance($products = array(), $data= array(), $clearance_id,$what=''){
		$cache = md5(http_build_query($data));
	    $string=http_build_query($data);
		$clearance_data = $this->getCacheSMBD(
		'clearance_store_'.$what.'_('. 
		(int)$this->config->get('config_store_id') .').'. 
		(int)$data['filter_category_id'] . '.' . 
		(int)substr($data['filter_manufacturers_by_id'], 0, -1) . '.' . 
		(int)$this->config->get('config_language_id') . '.' . 
		$cache,
		(int)$data['filter_category_id'],
		(int)substr($data['filter_manufacturers_by_id'], 0, -1),
		$what);
		if (!$clearance_data) {	
		$clearance_data = array();
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p 
			WHERE
			p.product_id IN (".implode(', ',array_values($products)).")
			AND p.stock_status_id='".$clearance_id."'";
			$query =  $this->db->query($sql);
			if (isset($query->row['total'])) {
				$clearance_data= $query->row['total'];
			} else {
				$clearance_data= "no_clearance";	
			}
		$this->setCacheSMBD(
			'stocks_by_products_store_'.$what.'_('. 
			(int)$this->config->get('config_store_id') .').'. 
			(int)$data['filter_category_id'] . '.'  .
			(int)substr($data['filter_manufacturers_by_id'], 0, -1) . '.' . 
			(int)$this->config->get('config_language_id') . '.' . 
			$cache, 
			$clearance_data,
			(int)$data['filter_category_id'],
			(int)substr($data['filter_manufacturers_by_id'], 0, -1),
			$what,
			$string);
		} 	
			return $clearance_data;
	}
	public function getStocksInStock($products = array(), $data= array(),$what=''){
		$cache = md5(http_build_query($data));
	    $string=http_build_query($data);
		$stockstatus_data = $this->getCacheSMBD(
		'instock_products_store_'.$what.'_('. 
		(int)$this->config->get('config_store_id') .').'. 
		(int)$data['filter_category_id'] . '.' . 
		(int)substr($data['filter_manufacturers_by_id'], 0, -1) . '.' . 
		(int)$this->config->get('config_language_id') . '.' .  
		$cache,
		(int)$data['filter_category_id'],
		(int)substr($data['filter_manufacturers_by_id'], 0, -1),
		$what);
		if (!$stockstatus_data) {	
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p 
			WHERE
			p.product_id IN (".implode(', ',array_values($products)).")
			AND p.quantity >0";
		 $query =  $this->db->query($sql);
			if (isset($query->row['total'])) {
				$stockstatus_data= $query->row['total'];
			} else {
				$stockstatus_data= "no_stock";	
			}
		$this->setCacheSMBD(
			'instock_products_store_'.$what.'_('. 
			(int)$this->config->get('config_store_id') .').'. 
			(int)$data['filter_category_id'] . '.'  .
			(int)substr($data['filter_manufacturers_by_id'], 0, -1) . '.' . 
			(int)$this->config->get('config_language_id') . '.' . 
			$cache, 
			$stockstatus_data,
			(int)$data['filter_category_id'],
			(int)substr($data['filter_manufacturers_by_id'], 0, -1),
			$what,
			$string);
		} 	
			return $stockstatus_data;
	}
	public function getStocksArrivals($products = array(), $data= array(),$days=7,$what=''){
		$cache = md5(http_build_query($data));
	    $string=http_build_query($data); 
		$new_arribals_data = $this->getCacheSMBD(
		'new_products_store_'.$what.'_('. 
		(int)$this->config->get('config_store_id') .').'. 
		(int)$data['filter_category_id'] . '.' . 
		(int)substr($data['filter_manufacturers_by_id'], 0, -1) . '.' . 
		(int)$this->config->get('config_language_id') . '.' . 
		$cache,
		(int)$data['filter_category_id'],
		(int)substr($data['filter_manufacturers_by_id'], 0, -1),
		$what);
		if (!$new_arribals_data) {	
		$new_arribals_data=array();
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p 
			WHERE
			p.product_id IN (".implode(', ',array_values($products)).")
			AND p.date_added >DATE_SUB(CURDATE(), INTERVAL ".$days." DAY)";
		 $query =  $this->db->query($sql);
			if (isset($query->row['total'])) {
				$new_arribals_data= $query->row['total'];
			} else {
				$new_arribals_data= "no_new";	
			}
		$this->setCacheSMBD(
			'new_products_store_'.$what.'_('. 
			(int)$this->config->get('config_store_id') .').'. 
			(int)$data['filter_category_id'] . '.'  .
			(int)substr($data['filter_manufacturers_by_id'], 0, -1) . '.' . 
			(int)$this->config->get('config_language_id') . '.' . 
			$cache, 
			$new_arribals_data,
			(int)$data['filter_category_id'],
			(int)substr($data['filter_manufacturers_by_id'], 0, -1),
			$what,
			$string);
		} 	
		return $new_arribals_data;
	}
	public function OrderArray($values,$order){
		$sort_order=array();
		if (empty($values)){
			return $values;	
		}else{
			if ($order=="OCASC"){
				 foreach ($values as $key => $value) {
					 $sort_order[] = $value['order'];
				 }
				 array_multisort($sort_order,SORT_ASC,$values);   
				 return $values;
			//order opencart default reverse
			}elseif ($order=="OCDESC"){
				 foreach ($values as $key => $value) {
               		 $sort_order[] = $value['order'];
               	 }
                 array_multisort($sort_order,SORT_DESC,$values);  
				 return $values;   
			//order total asc
			}elseif ($order=="OTASC"){
				 foreach ($values as $key => $value) {
            		 $sort_order[] = $value['total'];
            	 }
                 array_multisort($sort_order,SORT_ASC,$values);  
				 return $values;   
		    //order total desc
			}elseif ($order=="OTDESC"){
				foreach ($values as $key => $value) {
                	 $sort_order[] = $value['total'];
               	}
                array_multisort($sort_order,SORT_DESC,$values);  
				 return $values;   
			//order computer order asc
			}elseif ($order=="OCOASC"){
				 foreach ($values as $key => $value) {
            		 $sort_order[] = $value['name'];
            	 }
                 array_multisort($sort_order,SORT_ASC,$values);  
				 return $values;   
		    //order computer order desc
			}elseif ($order=="OCODESC"){
				foreach ($values as $key => $value) {
                	 $sort_order[] = $value['name'];
               	}
                array_multisort($sort_order,SORT_DESC,$values);  
			    return $values;   
			//order human order desc
			}elseif ($order=="OHDESC"){
				foreach ($values as $key => $value) {
					$sort_order["str".$value['name']] = $value['name'];
				}
			    natsort($sort_order);
				$values= array_reverse(array_merge($sort_order,$values));  
				return $values;
			//order human order asc	   
			}elseif ($order=="OHASC"){	
				foreach ($values as $key => $value) {
					$sort_order["str".$value['name']] = $value['name'];
				}
			    natsort($sort_order);
				$values= array_merge($sort_order,$values);   
				return $values; 
			}
		}  
	}
	public function CacheMenu($output_html, $data= array(),$what,$filter_coin){
		
		$cache = md5(http_build_query($data));
	    $string=http_build_query($data);
		$html_data = $this->getCacheSMBD('Main_menu_'.$what.'.'.$filter_coin.'_('.(int)$this->config->get('config_store_id') .').'.(int)$data['filter_category_id'] . '.' .(int)substr($data['filter_manufacturers_by_id'], 0, -1) . '.' .(int)$this->config->get('config_language_id'). '.' .$cache,(int)$data['filter_category_id'],(int)substr($data['filter_manufacturers_by_id'], 0, -1),$what,"cache_supercategory_menu");
		if (!$html_data) {	
		$this->setCacheSMBD('Main_menu_'.$what.'.'.$filter_coin.'_('.(int)$this->config->get('config_store_id') .').'.(int)$data['filter_category_id'] . '.'  .			(int)substr($data['filter_manufacturers_by_id'], 0, -1) . '.' .(int)$this->config->get('config_language_id') . '.' .$cache,$output_html,(int)$data['filter_category_id'],(int)substr($data['filter_manufacturers_by_id'], 0, -1),$what,$string,"cache_supercategory_menu");
		} 	
		return $html_data;
	}
	public function isCachedMenu($data= array(),$what,$filter_coin){
		$cache = md5(http_build_query($data));
	    $string=http_build_query($data);
		$html_data = $this->getCacheSMBD('Main_menu_'.$what.'.'.$filter_coin.'_('.(int)$this->config->get('config_store_id') .').'. (int)$data['filter_category_id'] . '.' .(int)substr($data['filter_manufacturers_by_id'], 0, -1) . '.' . (int)$this->config->get('config_language_id'). '.' . $cache,(int)$data['filter_category_id'],(int)substr($data['filter_manufacturers_by_id'], 0, -1),$what,"cache_supercategory_menu");
    	if (!$html_data) {	
		   return false;
	    }else{	
	       return $html_data;
		}
	}
	public function getPriceSliderHtml($idtxt,$scale,$min,$max,$symbol_left, $symbol_right,$name,$is_ajax,$ajax_url,$url,$currency=0){
		$out_put_html='';
		$out_put_html.="<div class=\"slider\">";
		if (($min==$max) || ($max-$min<=1)){ 
		$out_put_html.="&nbsp;<span style=\"display: inline-block; width: 150px; padding-right:10px;\">".$symbol_left."&nbsp;". round($max,2) ."&nbsp;".$symbol_right."</span>";
  		}else{
		$out_put_html.="<input id=\"".$idtxt."\" type=\"slider\" name=\"".$name."\" value=\"".$min.";".$max."\" /></span>";
		$out_put_html.="<script type=\"text/javascript\">";
		$out_put_html.="$(function() {";
		$out_put_html.="jQuery(\"#".$idtxt."\").slider({";
		$out_put_html.="	from: ".$min.",";
  		$out_put_html.="	to: ".$max.",";
    	$out_put_html.="	step: 1,";
  		$out_put_html.="	scale: ".$scale.",";
  		$out_put_html.="	limits: false,";
		$out_put_html.="	round: 2,";
 		$out_put_html.="	format: { format: '".$symbol_left."##".$symbol_right."' },";
		$out_put_html.="	callback: function( value ){";
		//$out_put_html.="	// console.dir( this );";
		$out_put_html.="	var nvalues=value.split(\";\");";
		//$out_put_html.="	";
		//$out_put_html.="	//clearTimeout(timers);";
	   //	if ($is_ajax){  
			$out_put_html.="url='".htmlspecialchars_decode($ajax_url)."&".$name."='+nvalues[ 0 ]+';'+nvalues[ 1 ];";
	   //	}else{
			$out_put_html.="url2='".htmlspecialchars_decode($url)."&".$name."='+nvalues[ 0 ]+';'+nvalues[ 1 ];";
       //	}
		$out_put_html.="delayShowData(url,".$is_ajax.",url2);";
		$out_put_html.="	}";
		$out_put_html.="})";
		$out_put_html.="});";
		$out_put_html.="</script>";
		}
		$out_put_html.="</div>";  
		return $out_put_html;
	}
		public function getPriceSliderHtmlSelected($idtxt,$scale,$min,$max,$symbol_left, $symbol_right,$name,$is_ajax,$ajax_url,$url,$currency=0,$ajax_url_del,$url_del){
		$out_put_html='';
		$out_put_html.="<div class=\"slider\">";
		$out_put_html.="<a class=\"link_filter_del smenu {dnd:'".$url_del."',ajaxurl:'".$ajax_url_del."', gapush:'no'}\" href=\"javascript:void(0)\" rel=\"nofollow\"> <img src=\"image/advancedmenu/spacer.gif\" alt=\"remove filter\" class=\"filter_del\" /></a><br>";
		if (($min==$max) || ($max-$min<=1)){ 
		$out_put_html.="&nbsp;<span style=\"display: inline-block; width: 150px; padding-right:10px;\">".$symbol_left."&nbsp;". round($max,2) ."&nbsp;".$symbol_right."</span>";
  		}else{
		$out_put_html.="<input id=\"".$idtxt."\" type=\"slider\" name=\"".$name."\" value=\"".$min.";".$max."\" /></span>";
		$out_put_html.="<script type=\"text/javascript\">";
		$out_put_html.="$(function() {";
		$out_put_html.="jQuery(\"#".$idtxt."\").slider({";
		$out_put_html.="	from: ".$min.",";
  		$out_put_html.="	to: ".$max.",";
    	$out_put_html.="	step: 1,";
  		$out_put_html.="	scale: ".$scale.",";
  		$out_put_html.="	limits: false,";
		$out_put_html.="	round: 2,";
 		$out_put_html.="	format: { format: '".$symbol_left."##".$symbol_right."' },";
		$out_put_html.="	callback: function( value ){";
		//$out_put_html.="	// console.dir( this );";
		$out_put_html.="	var nvalues=value.split(\";\");";
		//$out_put_html.="	";
		//$out_put_html.="	//clearTimeout(timers);";
	   	 //	if ($is_ajax){  
			$out_put_html.="url='".htmlspecialchars_decode($ajax_url)."&".$name."='+nvalues[ 0 ]+';'+nvalues[ 1 ];";
	   //	}else{
			$out_put_html.="url2='".htmlspecialchars_decode($url)."&".$name."='+nvalues[ 0 ]+';'+nvalues[ 1 ];";
       //	}
		$out_put_html.="delayShowData(url,".$is_ajax.",url2);";
		$out_put_html.="	}";
		$out_put_html.="});";
		$out_put_html.="});";
		$out_put_html.="</script>";
		}
		$out_put_html.="</div>";  
		return $out_put_html;
	}				
	public function CleanSliderName($str){
		$search  = array(' ','(',')','.','/');
		$replace = array("","","","","");
		return str_replace($search, $replace,$str);
	}		
	public function getSliderHtml($idtxt,$scale,$min,$max,$dimension,$name,$is_ajax,$ajax_url,$url){
		$out_put_html='';
		$out_put_html.="<div class=\"slider\">";	
		$out_put_html.="<input id=\"".$this->CleanSliderName($idtxt)."\" type=\"slider\" name=\"".$name."\" value=\"".$min.";".$max."\" /></span>";
		$out_put_html.="<script type=\"text/javascript\">";
		$out_put_html.="$(function() {";
		$out_put_html.="jQuery(\"#".$this->CleanSliderName($idtxt)."\").slider({";
		$out_put_html.="	from: ".$min.",";
  		$out_put_html.="	to: ". $max.",";
    	$out_put_html.="	step: 1,";
  		$out_put_html.="	scale: ".$scale.",";
  		$out_put_html.="	limits: false,";
		$out_put_html.="	round: 2,";
 		$out_put_html.="	dimension: '".$dimension."',";
		$out_put_html.="	format: { format: '##' },";
		$out_put_html.="	callback: function( value ){";
		//$out_put_html.="	// console.dir( this );";
		$out_put_html.="	var nvalues=value.split(\";\");";
		   //if ($is_ajax){ 		
				$out_put_html.="url='".htmlspecialchars_decode($ajax_url)."='+nvalues[ 0 ]+':'+nvalues[ 1 ];";
		  // }else{
				$out_put_html.="url2='". htmlspecialchars_decode($url)."='+nvalues[ 0 ]+':'+nvalues[ 1 ];";
		  // }
		$out_put_html.="delayShowData(url,".$is_ajax.",url2);";
		$out_put_html.="	}";
		$out_put_html.="});";
		$out_put_html.="});";
		$out_put_html.="</script>";
    	$out_put_html.="</div>";
		return $out_put_html;
	}
	public function getSliderHtmlSelected($idtxt,$scale,$min,$max,$dimension,$name,$is_ajax,$ajax_url_del,$url_del,$ajax_url_slider,$filter_url_slider){
		$out_put_html='';
		$out_put_html.="<div class=\"slider\">";	
		$out_put_html.="<a class=\"link_filter_del smenu {dnd:'".$url_del."',ajaxurl:'".$ajax_url_del."', gapush:'no'}\" href=\"javascript:void(0)\" rel=\"nofollow\"> <img src=\"image/advancedmenu/spacer.gif\" alt=\"remove filter\" class=\"filter_del\" /></a><br>";
		$out_put_html.="<input id=\"".$this->CleanSliderName($idtxt)."\" type=\"slider\" name=\"".$name."\" value=\"".$min.";".$max."\" /></span>";
		$out_put_html.="<script type=\"text/javascript\">";
		$out_put_html.="$(function() {";
		$out_put_html.="jQuery(\"#".$this->CleanSliderName($idtxt)."\").slider({";
		$out_put_html.="	from: ".$min.",";
  		$out_put_html.="	to: ". $max.",";
    	$out_put_html.="	step: 1,";
  		$out_put_html.="	scale: ".$scale.",";
  		$out_put_html.="	limits: false,";
		$out_put_html.="	round: 2,";
 		$out_put_html.="	dimension: '".$dimension."',";
		$out_put_html.="	format: { format: '##' },";
		$out_put_html.="	callback: function( value ){";
		//$out_put_html.="	// console.dir( this );";
		$out_put_html.="	var nvalues=value.split(\";\");";
		  // if ($is_ajax){ 		
				$out_put_html.="url='".htmlspecialchars_decode($ajax_url_slider)."='+nvalues[ 0 ]+':'+nvalues[ 1 ];";
		  // }else{
				$out_put_html.="url2='". htmlspecialchars_decode($filter_url_slider)."='+nvalues[ 0 ]+':'+nvalues[ 1 ];";
		  // }
		$out_put_html.="delayShowData(url,".$is_ajax.",url2);";
		$out_put_html.="	}";
		$out_put_html.="});";
		$out_put_html.="});";
		$out_put_html.="</script>";
    	$out_put_html.="</div>";
		return $out_put_html;
	}
	public function getSliderHtmlSelectedwithUnits($idtxt,$scale,$min,$max,$dimension,$name,$is_ajax,$ajax_url_del,$url_del,$ajax_url_slider,$filter_url_slider){
		$out_put_html='';
		$out_put_html.="<div class=\"slider\">";	
		$out_put_html.="<a class=\"link_filter_del smenu {dnd:'".$url_del."',ajaxurl:'".$ajax_url_del."', gapush:'no'}\" href=\"javascript:void(0)\" rel=\"nofollow\"> <img src=\"image/advancedmenu/spacer.gif\" alt=\"remove filter\" class=\"filter_del\" /></a><br>";
		$out_put_html.="<input id=\"".$this->CleanSliderName($idtxt)."\" type=\"slider\" name=\"".$name."\" value=\"".$min.";".$max."\" /></span>";
		$out_put_html.="<script type=\"text/javascript\">";
		$out_put_html.="$(function() {";
		$out_put_html.="jQuery(\"#".$this->CleanSliderName($idtxt)."\").slider({";
		$out_put_html.="	from: ".$min.",";
  		$out_put_html.="	to: ". $max.",";
    	$out_put_html.="	step: 1,";
  		$out_put_html.="	scale: ".$scale.",";
  		$out_put_html.="	limits: false,";
		$out_put_html.="	round: 2,";
 		$out_put_html.="	dimension: '".$dimension."',";
		$out_put_html.="	format: { format: '##' },";
		$out_put_html.="	callback: function( value ){";
		//$out_put_html.="	// console.dir( this );";
		$out_put_html.="	var nvalues=value.split(\";\");";
		// if ($is_ajax){ 		
				$out_put_html.="url='".htmlspecialchars_decode($ajax_url_slider)."='+nvalues[0]+'".urlencode($dimension).":'+nvalues[1]+'".urlencode($dimension)."';";
		 //}else{
				$out_put_html.="url2='".htmlspecialchars_decode($filter_url_slider)."='+nvalues[0]+'".urlencode($dimension).":'+nvalues[1]+'".urlencode($dimension)."';";
		// }
		$out_put_html.="delayShowData(url,".$is_ajax.",url2);";
		$out_put_html.="	}";
		$out_put_html.="});";
		$out_put_html.="});";
		$out_put_html.="</script>";
    	$out_put_html.="</div>";
		return $out_put_html;
	}
	public function getSliderHtmlwithUnits($idtxt,$scale,$min,$max,$dimension,$name,$is_ajax,$ajax_url,$url){
		$out_put_html='';
		$out_put_html.="<div class=\"slider\">";	
		$out_put_html.="<input id=\"".$this->CleanSliderName($idtxt)."\" type=\"slider\" name=\"".$name."\" value=\"".$min.";".$max."\" /></span>";
		$out_put_html.="<script type=\"text/javascript\">";
		$out_put_html.="$(function() {";
		$out_put_html.="jQuery(\"#".$this->CleanSliderName($idtxt)."\").slider({";
		$out_put_html.="	from: ".$min.",";
  		$out_put_html.="	to: ". $max.",";
    	$out_put_html.="	step: 1,";
  		$out_put_html.="	scale: ".$scale.",";
  		$out_put_html.="	limits: false,";
		$out_put_html.="	round: 2,";
 		$out_put_html.="	dimension: '".$dimension."',";
		$out_put_html.="	format: { format: '##' },";
		$out_put_html.="	callback: function( value ){";
		//$out_put_html.="	// console.dir( this );";
		$out_put_html.="	var nvalues=value.split(\";\");";
		  // if ($is_ajax){ 		
				$out_put_html.="url='".htmlspecialchars_decode($ajax_url)."='+nvalues[ 0 ]+'".urlencode($dimension).":'+nvalues[ 1 ]+'".urlencode($dimension)."';";
		  // }else{
				$out_put_html.="url2='". htmlspecialchars_decode($url)."='+nvalues[ 0 ]+'".urlencode($dimension).":'+nvalues[ 1 ]+'".urlencode($dimension)."';";
		 //  }
		$out_put_html.="delayShowData(url,".$is_ajax.",url2);";
		$out_put_html.="	}";
		$out_put_html.="});";
		$out_put_html.="});";
		$out_put_html.="</script>";
    	$out_put_html.="</div>";
		return $out_put_html;
	}
	public function getOneHtml($name,$total,$data_settings, $product_info=0){
			if ($product_info=="1" || $product_info=="2" || $product_info=="3"){
               $val=$this->length->format($name, $this->config->get('config_length_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
            }elseif($product_info=="8" ) { 
               $val=$this->weight->format($name, $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
            }else{
				$val=$name;
			}
		$out_put_html='';
		if($data_settings['count_products']) {
        	 $out_put_html.="<span class=\"seleccionado\"><em>&nbsp;</em>".$val." (".$total.")</span>";
        }else{ 
         	$out_put_html.="<span class=\"seleccionado\"><em>&nbsp;</em>".$val."</span>";
        }
		return $out_put_html;
	}
	public function getImageHtml($data,$data_settings,$name){
		$out_put_html='';
		$out_put_html.="<div style=\"margin-left: 6px;\" class=\"color_matrix\">";
		$out_put_html.="<ul>";
		  foreach ($data as $value){ 
	          ($data_settings['track_google']) ? $gap=trim($name)."@@@@@@".trim($value['name']) : $gap="no";    
			   $out_put_html.="<li><a class=\"smenu {dnd:'".$value['href']."', ajaxurl:'". $value['ajax_url']."', gapush:'". addslashes($gap)."'}\" href=\"javascript:void(0)\" ". $data_settings['nofollow']."><img class=\"picker\" src=\"".$value['image_thumb']."\" original-title=\"".utf8_strtoupper($value['name'])."\" alt=\"".utf8_strtoupper($value['name'])."\"/></a></li>";
		   } 
		 $out_put_html.="</ul></div>";
		return $out_put_html;
	}
	public function GetHtmlSelected($data_filtering,$data_settings,$product_info=0){
		if ($product_info=="w" || $product_info=="l" || $product_info=="h"){
               $val=$this->length->format($data_filtering['name'], $this->config->get('config_length_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
            }elseif($product_info=="wg" ) { 
               $val=$this->weight->format($data_filtering['name'], $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
            }else{
				$val=$data_filtering['name'];
			}
		$html="<ul>";
		$html.="<li class=\"active\"><em>&nbsp;</em> <a class=\"link_filter_del smenu {dnd:'".$data_filtering['href']."', ajaxurl:'".$data_filtering['ajax_url']."', gapush:'no'}\" href=\"javascript:void(0)\"  ".$data_settings['nofollow']."><img src=\"image/advancedmenu/spacer.gif\" alt=\"".$data_filtering['remove_filter_text']."\" class=\"filter_del\" /></a> <span>".$val." </span></li>";
		if ($data_filtering['see_more_url']){ 
        $html.="<li class=\"more_filters\"><a href=\"javascript:void(0)\" class=\"all_filters light small {dnd:'".$data_filtering['see_more_url']."'}\" rel=\"nofollow\">".$data_filtering['see_more_text']."</a></li>";             		    
		} 
        $html.="</ul>";
		return $html;
	}
	public function GetHtmlImageSelected($data_filtering,$data_settings){
	   //$count=$data_settings['count_products'] ? "&nbsp;<span class=\"product-count\">(". $total.")</span>" : "";
       $html="<ul>";
	   $html.="<li class=\"active\"><img class=\"picker\" align=\"absmiddle\" src=\"".$data_filtering['image']."\" title=\"".utf8_strtoupper($data_filtering['name'])."\" original-title=\"".utf8_strtoupper($data_filtering['name'])."\" alt=\"".utf8_strtoupper(str_replace("&amp;","&",$data_filtering['name']))."\"/> <a class=\"link_filter_del smenu {dnd:'".$data_filtering['href']."', ajaxurl:'".$data_filtering['ajax_url']."', gapush:'no'}\" href=\"javascript:void(0)\" ".$data_settings['nofollow']."\> <img src=\"image/advancedmenu/spacer.gif\" alt=\"".$data_filtering['remove_filter_text']."\" class=\"filter_del\" /></a> <span>".$data_filtering['name']."</span></li>";
	  if ($data_filtering['see_more_url']){ 
          $html.="<li class=\"more_filters\"><a href=\"javascript:void(0)\" class=\"all_filters light small {dnd:'".$data_filtering['see_more_url']."'}\" rel=\"nofollow\">".$data_filtering['see_more_text']."</a></li>";
       } 
       $html.="</ul>";
	   return $html;
	}
	public function getImageHtmlReview($data,$data_settings,$name, $extra_text){
		$out_put_html='';
		$out_put_html.="<div style=\"margin-left: 6px;\" class=\"color_matrix2\">";
		$out_put_html.="<ul>";
    	  foreach ($data as $value){ 
			($data_settings['track_google']) ? $gap=trim($name)."@@@@@@".trim($value['name']) : $gap="no";    
			($data_settings['count_products']) ? $count=" <span class=\"product-count\">(". $value['total'] .")</span>" : $count="";
		    $out_put_html.="<li original-title=\"". sprintf($extra_text,$value['name'])."\" alt=\"". sprintf($extra_text,$value['name'])."\"><a class=\"smenu rating {dnd:'".$value['href']."', ajaxurl:'". $value['ajax_url']."', gapush:'". addslashes($gap)."'}\" href=\"javascript:void(0)\" ". $data_settings['nofollow'].">";
			for ($i = 1; $i <= 5; $i++) { 
                 if ($value['name'] < $i) {
                  $out_put_html.=" <span class=\"fa fa-stack\"><i class=\"fa fa-star-o fa-stack-2x\"></i></span>";
                } else { 
                  $out_put_html.=" <span class=\"fa fa-stack\"><i class=\"fa fa-star fa-stack-2x\"></i><i class=\"fa fa-star-o fa-stack-2x\"></i></span>";
				} 
           } 
	    //<img align=\"absmiddle\" class=\"picker\" src=\"catalog/view/theme/default/image/stars-". $value['name'].".png\"  original-title=\"". sprintf($extra_text,$value['name'])."\" alt=\"". sprintf($extra_text,$value['name'])."\"/>
		$out_put_html.="</a> ".$count."</li>";
	    } 
		$out_put_html.="</ul></div>";
		return $out_put_html;
	}
	public function getOneHtmlReview($name,$total,$data_settings,$extra_text){
		$count=$data_settings['count_products'] ? "&nbsp;<span class=\"product-count\">(". $total.")</span>" : "";
		$out_put_html='';
		//$out_put_html.="<img class=\"picker\" align=\"absmiddle\" src=\"catalog/view/theme/default/image/stars-". $name.".png\" original-title=\"". sprintf($extra_text,$name)."\" alt=\"". sprintf($extra_text,$name)."\"/></a>".$count;
        $out_put_html.="<div style=\"margin-left: 6px;\" class=\"color_matrix2\">";
		$out_put_html.="<ul>"; 
        $out_put_html.="<li class=\"rating\" original-title=\"". sprintf($extra_text,$name)."\" alt=\"". sprintf($extra_text,$name)."\">";
		    for ($i = 1; $i <= 5; $i++) { 
                if ($name < $i) {
                  $out_put_html.=" <span class=\"fa fa-stack\"><i class=\"fa fa-star-o fa-stack-2x\"></i></span>";
                } else { 
                  $out_put_html.=" <span class=\"fa fa-stack\"><i class=\"fa fa-star fa-stack-2x\"></i><i class=\"fa fa-star-o fa-stack-2x\"></i></span>";
				} 
            } 
		   $out_put_html.= $count."</li>";
		   $out_put_html.="</ul></div>";
		 return $out_put_html;
	}
	public function GetHtmlImageSelectedReviews($data_filtering,$data_settings,$extra_text){
	   $html="<ul>";
	   $html.="<li class=\"active rating\" original-title=\"". sprintf($extra_text,$data_filtering['filter_rating'])."\" alt=\"". sprintf($extra_text,$data_filtering['filter_rating'])."\">";
			for ($i = 1; $i <= 5; $i++) { 
               if ($data_filtering['filter_rating'] < $i) {
                  $html.=" <span class=\"fa fa-stack\"><i class=\"fa fa-star-o fa-stack-2x\"></i></span>";
                } else { 
                  $html.=" <span class=\"fa fa-stack\"><i class=\"fa fa-star fa-stack-2x\"></i><i class=\"fa fa-star-o fa-stack-2x\"></i></span>";
               } 
            } 
	   $html.="  <a class=\"link_filter_del smenu {dnd:'".$data_filtering['href']."', ajaxurl:'".$data_filtering['ajax_url']."', gapush:'no'}\" href=\"javascript:void(0)\" ".$data_settings['nofollow']."\> <img src=\"image/advancedmenu/spacer.gif\" alt=\"".$data_filtering['remove_filter_text']."\" class=\"filter_del\" /></a></li>";
	   if ($data_filtering['see_more_url']){ 
          $html.="<li class=\"more_filters\"><a href=\"javascript:void(0)\" class=\"all_filters light small {dnd:'".$data_filtering['see_more_url']."'}\" rel=\"nofollow\">".$data_filtering['see_more_text']."</a></li>";
       } 
       $html.="</ul>";
	   return $html;
	}
	public function getSelectHtml($data,$data_settings,$name,$product_info=0){
		$this->language->load('module/supercategorymenuadvanced');
		$out_put_html='';
		$out_put_html.="<select name=\"select\" class=\"smenu form-control\">";
        $out_put_html.="<option value=\"0\" selected=\"selected\">- ".$this->language->get('txt_select_on_select')."&nbsp;".$name." -</option>";
		foreach ($data as $value){ 
			if ($product_info=="1" || $product_info=="2" || $product_info=="3"){
				$val=$this->length->format($value['name'], $this->config->get('config_length_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
			}elseif($product_info=="8" ) { 
				$val=$this->weight->format($value['name'], $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
			}else{
				$val=$value['name'];
			}
         ($data_settings['count_products'])? $count="&nbsp;(". $value['total'] .")" : $count="";
   		 ($data_settings['track_google']) ? $gap=trim($name)."@@@@@@".trim($value['name']) : $gap="no";   
		 $out_put_html.="<option class=\"smenu {dnd:'".$value['href']."', ajaxurl:'". $value['ajax_url']."', gapush:'". addslashes($gap)."'}\">".$val.$count."</option>";
		 } 
         $out_put_html.=" </select>";
		 return $out_put_html;
	}
    public function getListHtml($data,$data2,$data_settings,$name,$i,$searchinput,$product_info=0){
		$this->language->load('module/supercategorymenuadvanced');
		$out_put_html='';
		if (!empty($data2)){ //array_slice
			$out_put_html.="<ul id=\"results_container_".$i."\">";
			foreach ($data2 as $value){
			($data_settings['count_products'])? $count=" <span class=\"product-count\">(". $value['total'] .")</span>" : $count="";
			($data_settings['track_google']) ? $gap=trim($name)."@@@@@@".trim($value['name']) : $gap="no";		
			if ($product_info=="1" || $product_info=="2" || $product_info=="3"){
               $val=$this->length->format($value['name'], $this->config->get('config_length_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
            }elseif($product_info=="8" ) { 
               $val=$this->weight->format($value['name'], $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
            }else{
				$val=$value['name'];
			}
			$out_put_html.="<li><em>&nbsp;</em><a class=\"smenu {dnd: '".$value['href']."', ajaxurl:'".$value['ajax_url']."', gapush:'".addslashes($gap)."'}\" href=\"javascript:void(0)\" ". $data_settings['nofollow'].">".$val."</a>". $count."</li>";
           	}
            $out_put_html.="<li class=\"more_filters1\"><a href=\"javascript:void(0)\" class=\"light small\" rel=\"nofollow\">".$this->language->get('see_more_text')."</a></li>";
            $out_put_html.="</ul>";
		    $out_put_html.="<dd style=\"display: none;\" class=\"page_preload\">";
        	$out_put_html.="<div id=\"search_container_".$i."\" >";
           
            $out_put_html.="<ul>";
			
			 if ($searchinput=="yes"){ 
            $out_put_html.="<input name=\"search\" type=\"text\"  id=\"search".$i."\" onclick=\"this.value = '';\" class=\"search-box-bg form-control\" onkeyup=\"refineResults(event,this,'search_container_".$i."','#search_container_".$i."')\" value=\"".$this->language->get('search_in')."\"  />";
            } 
			
			
			foreach ($data as $value){ 
			if ($product_info=="1" || $product_info=="2" || $product_info=="3"){
               $val=$this->length->format($value['name'], $this->config->get('config_length_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
            }elseif($product_info=="8" ) { 
               $val=$this->weight->format($value['name'], $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
            }else{
				$val=$value['name'];
			}
			($data_settings['count_products'])? $count=" <span class=\"product-count\">(". $value['total'] .")</span>" : $count="";
			($data_settings['track_google']) ? $gap=trim($name)."@@@@@@".trim($value['name']) : $gap="no";		
			$out_put_html.="<li><em>&nbsp;</em><a class=\"smenu {dnd: '".$value['href']."', ajaxurl:'".$value['ajax_url']."', gapush:'".addslashes($gap)."'}\" href=\"javascript:void(0)\" ".$data_settings['nofollow'].">".$val."</a>". $count."</li>";
			}
        	$out_put_html.="</ul>";
        	$out_put_html.="  </div>";
        	$out_put_html.="</dd>";
		}else{
			$out_put_html.="<ul>";
			foreach ($data as $value){ 
			($data_settings['count_products'])? $count=" <span class=\"product-count\">(". $value['total'] .")</span>" : $count="";
			($data_settings['track_google']) ? $gap=trim($name)."@@@@@@".trim($value['name']) : $gap="no";	
			if ($product_info=="1" || $product_info=="2" || $product_info=="3"){
               $val=$this->length->format($value['name'], $this->config->get('config_length_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
            }elseif($product_info=="8" ) { 
               $val=$this->weight->format($value['name'], $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
            }else{
				$val=$value['name'];
			}
			 $out_put_html.="<li><em>&nbsp;</em><a class=\"smenu {dnd: '".$value['href']."', ajaxurl:'".$value['ajax_url']."', gapush:'".addslashes($gap)."'}\" href=\"javascript:void(0)\" ". $data_settings['nofollow'].">".$val."</a>". $count."</li>";
			}
			$out_put_html.="</ul>";
		}
		return $out_put_html;
	}
public function resize($filename, $width, $height) {
		if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename)) {
			return;
		} 
		$info = pathinfo($filename);
		$extension = $info['extension'];
		$old_image = $filename;
		$new_image = 'cache/' . substr($filename, 0, strrpos($filename, '.')) . '-' . $width . 'x' . $height . '.' . $extension;
		if (!file_exists(DIR_IMAGE . $new_image) || (filemtime(DIR_IMAGE . $old_image) > filemtime(DIR_IMAGE . $new_image))) {
			$path = '';
			$directories = explode('/', dirname(str_replace('../', '', $new_image)));
			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;
				if (!file_exists(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				}		
			}
			$image = new Image(DIR_IMAGE . $old_image);
			$image->resize($width, $height);
			$image->save(DIR_IMAGE . $new_image);
		}
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			return HTTPS_IMAGE . $new_image;
		} else {
			return HTTP_IMAGE . $new_image;
		}	
	}
	/*
		$html_data = $this->getCacheSMBD(
		'Main_menu_'.$what.'.'.$filter_coin.'_('.(int)$this->config->get('config_store_id') .').'.(int)$data['filter_category_id'] . '.' .(int)substr($data['filter_manufacturers_by_id'], 0, -1) . '.' .(int)$this->config->get('config_language_id'). '.' .$cache,
		(int)$data['filter_category_id'],
		(int)substr($data['filter_manufacturers_by_id'], 0, -1),
		$what,
		"cache_supercategory_menu");
	*/
	public function getCacheSMBD($key,$cat,$man,$what="C",$table="cache_supercategory") {
		// $key
		/*echo "\nKey = " . $key;
		echo "<br>CAT = " . $cat;
		echo "<br>MAN = " . $man;
		echo "<br>WHAT = " . $what;
		echo "<br>Table = " . $table;*/
		//die();
		if ($what=="C"){ 
			$query = $this->db->query("SELECT data,cache_id FROM " . DB_PREFIX . $table." cs WHERE cs.cat = '" . $cat . "' AND cs.name = '" . $key. "' LIMIT 1");
		}else{
			$query = $this->db->query("SELECT data,cache_id FROM " . DB_PREFIX . $table." cs WHERE cs.man = '" . $man . "' AND cs.name = '" . $key. "' LIMIT 1");
		}
		if ($query->num_rows) {
			$this->db->query("UPDATE " . DB_PREFIX . $table." cs SET cs.cached=cs.cached+1 WHERE cs.cache_id = '" . $query->row['cache_id'] . "'");
			//print_r (unserialize($query->row['data']));
			//die();
			return unserialize($query->row['data']);
		}
	}
  	public function setCacheSMBD($key,$value,$cat,$man,$what="C",$string,$table="cache_supercategory") {

		$this->db->query("DELETE FROM " . DB_PREFIX . $table." WHERE name LIKE  '" . $key . "%'");
		$STORE= $this->config->get('config_store_id');
	    $settings_module=  $this->model_module_supercategorymenuadvanced->getMySetting('SETTINGS_'.$STORE,$STORE);
        $menu_mode 			=isset($settings_module['general_data']['menu_mode'])? $settings_module['general_data']['menu_mode'] : "developing";
		if($menu_mode=="Production"){
			$this->db->query("INSERT INTO " . DB_PREFIX . $table." 
			SET 
			`cache_id` = '', 
			`cat` = '" . $this->db->escape($cat) . "', 
			`man` = '" . $this->db->escape($man) . "', 
			`store_id` = '" . (int)$this->config->get('config_store_id'). "', 
			`name` = '" . $this->db->escape($key) . "', 
			`string` = '" . $this->db->escape($string) . "', 
			`data` = '" . $this->db->escape(serialize($value)) . "'");
		}
	}
	
	public function getCategoriesByParentId($category_id) {
        $category_data = array();

        $category_query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "category WHERE parent_id = '" . (int)$category_id . "'");

        foreach ($category_query->rows as $category) {
                        $category_data[] = $category['category_id'];

                        $children = $this->getCategoriesByParentId($category['category_id']);

                        if ($children) {
                                        $category_data = array_merge($children, $category_data);
                        }                                            
        }

        return $category_data;
    }
}
?>
