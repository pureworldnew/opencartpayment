<?php
class ModelModuleSuperManufacturerMenuAdvanced extends Model { 



public function getManufacturers($store_id=0,$data) {
		
		$sql = "SELECT * FROM " . DB_PREFIX . "manufacturer m 
        LEFT JOIN " . DB_PREFIX . "manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id) 
        WHERE m2s.store_id = '".(int)$store_id."'";
	
	
		$sort_data = array(
			'name',
			'sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

	
		$query = $this->db->query($sql);

		return $query->rows;
		
		
		
		
	}



public function getTotalManufacturers($store_id=0) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "manufacturer m 
        LEFT JOIN " . DB_PREFIX . "manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id) 
        WHERE m2s.store_id = '".(int)$store_id."'");

		return $query->row['total'];
	}





public function getManufacturerAttributes($manufacturer_id, $store_id) { 

$cache = md5($manufacturer_id.$store_id); 

$manufacturer_attribute_data = $this->model_module_supercategorymenuadvanced->getCacheSMBD('admin_manufacturers_store('.$store_id.')'.$manufacturer_id.'_attributes.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . $cache,'admin',$store_id); if (!$manufacturer_attribute_data) { $sql="SELECT pa.attribute_id, ad.name FROM " . DB_PREFIX . "product_attribute pa 		LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) LEFT JOIN " . DB_PREFIX . "product p ON (p.product_id = pa.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)";
  
  
  
  if ((int)$manufacturer_id==0 || $manufacturer_id=="all"){ 
 

 
 $sql.="WHERE p2s.store_id = '" . (int)$store_id . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . " ' GROUP BY pa.attribute_id ORDER BY a.sort_order"; 
 }else{ 
 $sql.="WHERE p.manufacturer_id= '" . (int)$manufacturer_id . "' AND p2s.store_id = '" . (int)$store_id . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . " ' GROUP BY pa.attribute_id ORDER BY a.sort_order"; 

	





} $manufacturer_attribute_data = array(); $manufacturer_attribute_query = $this->db->query($sql); $manufacturer_attribute_data =$manufacturer_attribute_query->rows; $this->model_module_supercategorymenuadvanced->setCacheSMBD('admin_manufacturers_store('.$store_id.')'.$manufacturer_id.'_attributes.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . $cache, $manufacturer_attribute_data,'admin',$store_id); } return $manufacturer_attribute_data; } 

public function getAttributeValuesManufacturers($attribute_id,$manufacturer_id,$store_id) { 
$attribute_data = array(); 
$cache = md5($attribute_id.$manufacturer_id.$store_id); 
		$attribute_data = $this->model_module_supercategorymenuadvanced->getCacheSMBD('admin_manufacturer_'.$manufacturer_id.'_attributesvalues_store('.$store_id.').' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . $cache,'admin',$store_id); 
		if (!$attribute_data) { 
			$sql="SELECT distinct pa.text,pa.attribute_id, pa.language_id FROM " . DB_PREFIX . "product_attribute pa 
			LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) 
			LEFT JOIN " . DB_PREFIX . "product p ON (pa.product_id = p.product_id) 
			LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (pa.product_id = p2s.product_id) 
			WHERE pa.language_id = '" . (int)$this->config->get('config_language_id') . "' 
			AND pa.attribute_id = '" . (int)$attribute_id . "' 
			AND	p2s.store_id = '" . (int)$store_id . "'"; 

				if ((int)$manufacturer_id!=0 && $manufacturer_id!="all"){ 				
					$sql.=" AND p.manufacturer_id = '" . (int)$manufacturer_id . "' "; 
				} 
				$sql.=" LIMIT 20"; 	

			$query = $this->db->query($sql); foreach ($query->rows as $result) { $attribute_data[$result['language_id']][] = $result['text']; } 
			
		$this->model_module_supercategorymenuadvanced->setCacheSMBD('admin_manufacturer_'.$manufacturer_id.'_attributesvalues_store('.$store_id.').' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . $cache, $attribute_data,'admin',$store_id); 
		} 	
		return $attribute_data;
}






















 public function getManufacturerOptions($manufacturer_id,$store_id) { $cache = md5($manufacturer_id.$store_id); $manufacturer_options_data = $this->model_module_supercategorymenuadvanced->getCacheSMBD('admin_manufacturers_'.$manufacturer_id.'_options_store('.$store_id.')' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . $cache,'admin',$store_id); if (!$manufacturer_options_data) { 
 
 $sql="SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN " . DB_PREFIX . "product p ON ( po.product_id = p.product_id ) LEFT JOIN " . DB_PREFIX . "option_description od ON ( od.option_id = po.option_id ) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (po.product_id = p2s.product_id) LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id)"; 
   if ((int)$manufacturer_id==0 || $manufacturer_id=="all"){ 
 $sql.=" WHERE p2s.store_id = '" . (int)$store_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . " ' GROUP BY od.option_id ORDER BY o.sort_order"; 
 }else{ 
 $sql.=" WHERE p.manufacturer_id = '" . (int)$manufacturer_id . "' AND p2s.store_id = '" . (int)$store_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . " ' GROUP BY od.option_id ORDER BY o.sort_order"; } 
 $manufacturer_options_data = array(); $manufacturer_options_query = $this->db->query($sql); $manufacturer_options_data =$manufacturer_options_query->rows; $this->model_module_supercategorymenuadvanced->setCacheSMBD('admin_manufacturers_'.$manufacturer_id.'_options_store('.$store_id.')' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . $cache, $manufacturer_options_data,'admin',$store_id); } return $manufacturer_options_data; } 
 
 public function getOptionsValuesManufacturers($option_id,$manufacturer_id,$store_id) { 
 
 $options_data = array(); 
		$cache = md5($option_id.$manufacturer_id.$store_id); 
		$options_data = $this->model_module_supercategorymenuadvanced->setCacheSMBD('admin_manufacturer_'.$manufacturer_id.'_optionsvalues_store('.$store_id.').' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . $cache,'admin',$store_id); 	
		if (!$options_data) { 
 	 
		 $sql="SELECT distinct pov.option_value_id, ovd.name, ovd.language_id FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN  " . DB_PREFIX . "option_value_description ovd ON (pov.option_value_id=ovd.option_value_id) LEFT JOIN  " . DB_PREFIX . "product p ON (pov.product_id=p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (pov.product_id = p2s.product_id) WHERE pov.option_id = '" . (int)$option_id. "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2s.store_id = '" . (int)$store_id . "'";
		 
			if ((int)$manufacturer_id!=0 && $manufacturer_id!="all"){ 				
				$sql.=" AND p.manufacturer_id = '" . (int)$manufacturer_id . "' "; 
			} 
			$sql.=" LIMIT 20"; 	
		 
		$query = $this->db->query($sql); 
			foreach ($query->rows as $result) { 
			$options_data[$result['language_id']][] = $result['name']; 
			}
		$this->model_module_supercategorymenuadvanced->setCacheSMBD('admin_manufacturer_'.$manufacturer_id.'_optionsvalues_store('.$store_id.').' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . $cache, $options_data,'admin',$store_id); 
		} 	
		return $options_data; 
		} 
 
 
 
 public function getProductInfoValues($what,$manufacturer_id,$store_id) { 
 
		$product_info_data = array(); 
		$cache = md5($what.$manufacturer_id.$store_id); 
		$product_info_data = $this->model_module_supercategorymenuadvanced->setCacheSMBD('admin_manufacturer_'.$manufacturer_id.'_productinfovalues_store('.$store_id.').' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . $cache,'admin',$store_id); 
			if (!$product_info_data) { 
 
			$sql="SELECT DISTINCT p.".$what." FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p2s.store_id = '" . (int)$store_id . "'"; 
 
			 if ((int)$manufacturer_id!=0 && $manufacturer_id!="all"){ 				
					$sql.=" AND p.manufacturer_id = '" . (int)$manufacturer_id . "' "; 
				} 
			$sql.=" LIMIT 20"; 	
			$query = $this->db->query($sql); foreach ($query->rows as $result) { $product_info_data[] = $result[$what]; } 
	
	 $this->model_module_supercategorymenuadvanced->setCacheSMBD('admin_manufacturer_'.$manufacturer_id.'_productinfovalues_store('.$store_id.').' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . $cache, $product_info_data,'admin',$store_id); 
		} 	
			 return $product_info_data;
			 } 
	
	}

 

	?>