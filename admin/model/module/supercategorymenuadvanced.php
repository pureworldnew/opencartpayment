<?php 
class ModelModuleSuperCategoryMenuAdvanced extends Model
{
    public function getCategoryAttributes($category_id, $store_id)
    {
        $cache                   = md5($category_id . $store_id);
        $category_attribute_data = $this->getCacheSMBD('admin_categories_' . $category_id . '_attributes_store(' . $store_id . ').' . (int) $this->config->get('config_language_id') . '.' . (int) $this->config->get('config_store_id') . $cache, 'admin', $store_id);
        if (!$category_attribute_data) {
            $sql = "SELECT pa.attribute_id, ad.name FROM " . DB_PREFIX . "product_attribute pa 		
			LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id)
			LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) 
			LEFT JOIN " . DB_PREFIX . "product p ON (p.product_id = pa.product_id) 
			LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
			LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";
            if ((int) $category_id == 0 || $category_id == "all") {
                
                
                $sql .= " WHERE p2s.store_id = '" . (int) $store_id . "' 
			AND ad.language_id = '" . (int) $this->config->get('config_language_id') . " '
			GROUP BY pa.attribute_id ORDER BY a.sort_order";
            } else {
                $sql .= " WHERE p2c.category_id = '" . (int) $category_id . "' 
			AND p2s.store_id = '" . (int) $store_id . "' 
			AND ad.language_id = '" . (int) $this->config->get('config_language_id') . " ' 
			GROUP BY pa.attribute_id ORDER BY a.sort_order";
            }
            $category_attribute_data  = array();
            $category_attribute_query = $this->db->query($sql);
            $category_attribute_data  = $category_attribute_query->rows;
            $this->setCacheSMBD('admin_categories_' . $category_id . '_attributes_store(' . $store_id . ').' . (int) $this->config->get('config_language_id') . '.' . (int) $this->config->get('config_store_id') . $cache, $category_attribute_data, 'admin', $store_id);
        }
        return $category_attribute_data;
    } 
    public function getStoreName($store_id)
    {
        $query = $this->db->query("SELECT name FROM " . DB_PREFIX . "store WHERE store_id = '" . (int) $store_id . "'");
        return $query->row['name'];
    }
    public function ExtensionInstalled()
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `code` = 'supercategorymenuadvanced'");
        if ($query->rows) {
            return true;
            
        } else {
            return false;
        }
        
    }
    public function getAttributeValues($attribute_id, $category_id, $store_id)
    {
        $attribute_data = array();
        $attribute_data = array();
        $cache          = md5($attribute_id . $category_id . $store_id);
        $attribute_data = $this->getCacheSMBD('admin_categories_' . $category_id . '_attributesvalues_store(' . $store_id . ').' . (int) $this->config->get('config_language_id') . '.' . (int) $this->config->get('config_store_id') . $cache, 'admin', $store_id);
        if (!$attribute_data) {
            $sql = "SELECT distinct pa.text,pa.attribute_id, ad.name,  pa.language_id FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) 
			LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (pa.product_id = p2c.product_id) 
			LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (pa.product_id = p2s.product_id) WHERE pa.attribute_id = '" . (int) $attribute_id . "' AND p2s.store_id = '" . (int) $store_id . "'";
            if ((int) $category_id != 0 && $category_id != "all") {
                $sql .= " AND p2c.category_id = '" . (int) $category_id . "' ";
            }
            $sql .= " LIMIT 20";
            $query = $this->db->query($sql);
            foreach ($query->rows as $result) {
                $attribute_data[$result['language_id']][] = $result['text'];
            }
            $this->setCacheSMBD('admin_categories_' . $category_id . '_attributesvalues_store(' . $store_id . ').' . (int) $this->config->get('config_language_id') . '.' . (int) $this->config->get('config_store_id') . $cache, $attribute_data, 'admin', $store_id);
        }
        return $attribute_data;
    }
    public function getCategoryOptions($category_id, $store_id)
    {
        $cache                 = md5($category_id . $store_id);
        $category_options_data = $this->getCacheSMBD('admin_categories_' . $category_id . '_options_store(' . $store_id . ').' . (int) $this->config->get('config_language_id') . '.' . (int) $this->config->get('config_store_id') . $cache, 'admin', $store_id);
        if (!$category_options_data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON ( po.product_id = p2c.product_id ) LEFT JOIN " . DB_PREFIX . "option_description od ON ( od.option_id = po.option_id ) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (po.product_id = p2s.product_id) LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id)";
            if ((int) $category_id == 0 || $category_id == "all") {
                $sql .= " WHERE p2s.store_id = '" . (int) $store_id . "' AND  od.language_id = '" . (int) $this->config->get('config_language_id') . " ' GROUP BY od.option_id ORDER BY o.sort_order";
            } else {
                $sql .= " WHERE p2s.store_id = '" . (int) $store_id . "' AND  p2c.category_id = '" . (int) $category_id . "' AND od.language_id = '" . (int) $this->config->get('config_language_id') . " ' GROUP BY od.option_id ORDER BY o.sort_order";
            }
            $sql .= " LIMIT 20";
            
            
            $category_options_data  = array();
            $category_options_query = $this->db->query($sql);
            $category_options_data  = $category_options_query->rows;
            $this->setCacheSMBD('admin_categories_' . $category_id . '_options_store(' . $store_id . ').' . (int) $this->config->get('config_language_id') . '.' . (int) $this->config->get('config_store_id') . $cache, $category_options_data, 'admin', $store_id);
        }
        return $category_options_data;
    }
    public function getOptionsValues($option_id, $category_id, $store_id)
    {
        
        
        $options_data = array();
        $cache        = md5($option_id . $category_id . $store_id);
        $options_data = $this->getCacheSMBD('admin_categories_' . $category_id . '_optionsvalues_store(' . $store_id . ').' . (int) $this->config->get('config_language_id') . '.' . (int) $this->config->get('config_store_id') . $cache, 'admin', $store_id);
        if (!$options_data) {
            $sql = "SELECT distinct pov.option_value_id, ovd.name, ovd.language_id FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN  " . DB_PREFIX . "option_value_description ovd ON (pov.option_value_id=ovd.option_value_id) LEFT JOIN  " . DB_PREFIX . "product_to_category p2c ON (pov.product_id=p2c.product_id) 
		LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (pov.product_id = p2s.product_id) 
		WHERE pov.option_id = '" . (int) $option_id . "' 
		AND p2s.store_id = '" . (int) $store_id . "'";
            if ((int) $category_id != 0 && $category_id != "all") {
                $sql .= "AND p2c.category_id = '" . (int) $category_id . "' ";
            }
            $sql .= " LIMIT 20";
            
            
            $query = $this->db->query($sql);
            foreach ($query->rows as $result) {
                $options_data[$result['language_id']][] = $result['name'];
            }
            $this->setCacheSMBD('admin_categories_' . $category_id . '_optionsvalues_store(' . $store_id . ').' . (int) $this->config->get('config_language_id') . '.' . (int) $this->config->get('config_store_id') . $cache, $options_data, 'admin', $store_id);
        }
        return $options_data;
    }
    public function getProductInfoValues($what, $category_id, $store_id)
    {
        $product_info_data = array();
        $cache             = md5($what . $category_id . $store_id);
        $product_info_data = $this->getCacheSMBD('admin_categories_' . $category_id . '_productinfovalues_store(' . $store_id . ').' . (int) $this->config->get('config_language_id') . '.' . (int) $this->config->get('config_store_id') . $cache, 'admin', $store_id);
        if (!$product_info_data) {
            $sql = "SELECT DISTINCT p." . $what . " FROM " . DB_PREFIX . "product p LEFT JOIN  " . DB_PREFIX . "product_to_category p2c ON (p.product_id=p2c.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p2s.store_id = '" . (int) $store_id . "'";
            if ((int) $category_id != 0 && $category_id != "all") {
                $sql .= "AND p2c.category_id = '" . (int) $category_id . "' ";
            }
            $sql .= " LIMIT 20";
            $query = $this->db->query($sql);
            foreach ($query->rows as $result) {
                $product_info_data[] = $result[$what];
            }
            $this->setCacheSMBD('admin_categories_' . $category_id . '_productinfovalues_store(' . $store_id . ').' . (int) $this->config->get('config_language_id') . '.' . (int) $this->config->get('config_store_id') . $cache, $product_info_data, 'admin', $store_id);
        }
        return $product_info_data;
    }
    public function GetCacheRecords($category_id, $order, $store_id = "all")
    {
        if ($store_id == "all") {
            $sql  = "SELECT * FROM " . DB_PREFIX . "cache_supercategory cs WHERE cs.cat = '" . $category_id . "' ORDER BY " . $order . " DESC";
            $sql2 = "SELECT * FROM " . DB_PREFIX . "cache_supercategory_menu cs WHERE cs.cat = '" . $category_id . "' ORDER BY " . $order . " DESC";
        } else {
            $sql  = "SELECT * FROM " . DB_PREFIX . "cache_supercategory cs WHERE store_id='" . (int) $store_id . "' AND cs.cat = '" . $category_id . "' ORDER BY " . $order . " DESC";
            $sql2 = "SELECT * FROM " . DB_PREFIX . "cache_supercategory_menu cs WHERE store_id='" . (int) $store_id . "' AND cs.cat = '" . $category_id . "' ORDER BY " . $order . " DESC";
        }
        $cache_records_data   = $cache_records_1 = $cache_records_2 = array();
        $cache_records_query  = $this->db->query($sql);
        $cache_records_query2 = $this->db->query($sql2);
        $cache_records_1      = $cache_records_query->rows;
        $cache_records_2      = $cache_records_query2->rows;
        return $cache_records_data = array_merge($cache_records_2, $cache_records_1);
    }
    public function GetCacheRecordsM($id, $order, $store_id = "all")
    {
        if ($store_id == "all") {
            $sql  = "SELECT * FROM " . DB_PREFIX . "cache_supercategory cs WHERE cs.man = '" . $id . "' ORDER BY " . $order . " DESC";
            $sql2 = "SELECT * FROM " . DB_PREFIX . "cache_supercategory_menu cs WHERE cs.man = '" . $id . "' ORDER BY " . $order . " DESC";
        } else {
            $sql  = "SELECT * FROM " . DB_PREFIX . "cache_supercategory cs WHERE store_id='" . (int) $store_id . "' AND cs.man = '" . $id . "' ORDER BY " . $order . " DESC";
            $sql2 = "SELECT * FROM " . DB_PREFIX . "cache_supercategory_menu cs WHERE store_id='" . (int) $store_id . "' AND cs.man = '" . $id . "' ORDER BY " . $order . " DESC";
        }
        $cache_records_data   = $cache_records_1 = $cache_records_2 = array();
        $cache_records_query  = $this->db->query($sql);
        $cache_records_query2 = $this->db->query($sql2);
        $cache_records_1      = $cache_records_query->rows;
        $cache_records_2      = $cache_records_query2->rows;
        return $cache_records_data = array_merge($cache_records_1, $cache_records_2);
    }
    public function DeleteCacheRecord($cache_id, $store_id = "all")
    {
        if ($store_id == "all") {
            $this->db->query("DELETE FROM " . DB_PREFIX . "cache_supercategory WHERE cache_id = '" . (int) $cache_id . "'");
            $this->db->query("DELETE FROM " . DB_PREFIX . "cache_supercategory_menu WHERE cache_id = '" . (int) $cache_id . "'");
        } else {
            $this->db->query("DELETE FROM " . DB_PREFIX . "cache_supercategory WHERE store_id='" . (int) $store_id . "' AND cache_id = '" . (int) $cache_id . "'");
            $this->db->query("DELETE FROM " . DB_PREFIX . "cache_supercategory_menu WHERE store_id='" . (int) $store_id . "' AND cache_id = '" . (int) $cache_id . "'");
        }
    }
    public function DeleteCacheValues($category, $store_id)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "cache_supercategory WHERE name LIKE  'admin_categories_" . $category . "_attributes._store(" . $store_id . ").%'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "cache_supercategory WHERE name LIKE  'admin_categories_" . $category . "_options._store(" . $store_id . ").%'");
    }
    public function DeleteCacheRecordbyCategory($category_id)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "cache_supercategory WHERE cat = '" . $this->db->escape($category_id) . "'");
    }
    public function getCategory($category_id)
    {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.category_id = '" . (int) $category_id . "' AND cd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int) $this->config->get('config_store_id') . "' AND c.status = '1'");
        return $query->row;
    }
    public function getCategoriesParent_id($parent_id = 0, $store_id = 0, $data = '')
    {
        $category_data = array();
        $sql           = "SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) 
WHERE c2s.store_id='" . (int) $store_id . "' AND c.parent_id = '" . (int) $parent_id . "' AND cd.language_id = '" . (int) $this->config->get('config_language_id') . "' 
ORDER BY c.sort_order, cd.name ASC ";
        if (!empty($data)) {
            if (isset($data['start']) || isset($data['limit'])) {
                if ($data['start'] < 0) {
                    $data['start'] = 0;
                }
                if ($data['limit'] < 1) {
                    $data['limit'] = 20;
                }
                $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
            }
        }
        $query = $this->db->query($sql);
        foreach ($query->rows as $result) {
            $category_data[] = array(
                'category_id' => $result['category_id'],
                'name' => $this->getPath($result['category_id'], $this->config->get('config_language_id')),
                'status' => $result['status'],
                'sort_order' => $result['sort_order'],
                'parent_id' => $result['parent_id'],
                'store_id' => $store_id
            );
        }
        return $category_data;
    }
    public function getMySetting($key, $store_id = 0)
    {
        $data  = array();
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int) $store_id . "' AND `key` = '" . $this->db->escape($key) . "'");
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
    public function getallCategories($store_id)
    {
        $category_store_data   = array();
        $category_store_data[] = 0;
        $query                 = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_to_store WHERE store_id = '" . (int) $store_id . "'");
        foreach ($query->rows as $result) {
            $category_store_data[] = $result['category_id'];
        }
        return $category_store_data;
    }
    public function getallManufacturers($store_id)
    {
        $manufacturer_store_data   = array();
        $manufacturer_store_data[] = 0;
        $query                     = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer_to_store WHERE store_id = '" . (int) $store_id . "'");
        foreach ($query->rows as $result) {
            $manufacturer_store_data[] = $result['manufacturer_id'];
        }
        return $manufacturer_store_data;
    }
    public function getCategories($parent_id = 0, $store_id = 0)
    {
        $category_data = $this->getCacheSMBD('admin_categories_store(' . $store_id . ')' . $parent_id . '_.' . (int) $this->config->get('config_language_id') . '.' . (int) $parent_id, 'admin', $store_id);
        if (!$category_data) {
            $category_data = array();
            $query         = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE c2s.store_id='" . (int) $store_id . "' AND c.parent_id = '" . (int) $parent_id . "' AND cd.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY c.sort_order, cd.name ASC");
            foreach ($query->rows as $result) {
                $category_data[] = array(
                    'category_id' => $result['category_id'],
                    'name' => $this->getPath($result['category_id'], $this->config->get('config_language_id')),
                    'parent_id' => $result['parent_id']
                );
                //$category_data = array_merge($category_data, $this->getCategories($result['category_id'])); 
                $category_data   = array_merge($category_data, $this->getCategories($result['category_id'], $result['store_id']));
            }
            $this->setCacheSMBD('admin_categories_store(' . $store_id . ')' . $parent_id . '_.' . (int) $this->config->get('config_language_id') . '.' . (int) $parent_id, $category_data, 'admin', $store_id);
        }
        return $category_data;
    }
    public function getManufacturerName($manufacturer_id)
    {
        $query = $this->db->query("SELECT name FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = '" . (int) $manufacturer_id . "'");
        return $query->row['name'];
    }
    public function getPath($category_id = 0)
    {
        $query = $this->db->query("SELECT name, parent_id FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE c.category_id = '" . (int) $category_id . "' AND cd.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY c.sort_order, cd.name ASC");
        if ($category_id == 0) {
            return $this->language->get('category_home');
        } else {
            if ($query->row['parent_id']) {
                return $this->getPath($query->row['parent_id'], $this->config->get('config_language_id')) . "&nbsp;&gt;&nbsp;" . $query->row['name'];
            } else {
                return $query->row['name'];
            }
        }
    }
    public function getCategoryParent_id($category_id = 0)
    {
        $query = $this->db->query("SELECT parent_id FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE c.category_id = '" . (int) $category_id . "' AND cd.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY c.sort_order, cd.name ASC");
        if ($category_id == 0) {
            return false;
        } else {
            if ($query->row['parent_id']) {
                return false;
            } else {
                return true;
            }
        }
    }
    public function editAdminSettings($group, $dnd, $value, $store_id = 0)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int) $store_id . "' AND `code` = '" . $this->db->escape($group) . "'	AND  `key` like 'SETTINGS%'");
        //foreach ($data as $key => $value) {
        if (!is_array($value)) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int) $store_id . "', `code` = '" . $this->db->escape($group) . "', `key` = '" . $this->db->escape($dnd) . "', `value` = '" . $this->db->escape($value) . "'");
        } else {
           
		   if(version_compare(VERSION,'2.1.0','>')) {
			  $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int) $store_id . "', `code` = '" . $this->db->escape($group) . "', `key` = '" . $this->db->escape($dnd) . "', `value` = '" . $this->db->escape(json_encode($value)) . "', serialized = '1'");

		  }else{
			 $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int) $store_id . "', `code` = '" . $this->db->escape($group) . "', `key` = '" . $this->db->escape($dnd) . "', `value` = '" . $this->db->escape(serialize($value)) . "', serialized = '1'");
          }
        }
        //	}    
    }
    public function editOneSetting($group, $dnd, $data, $store_id = 0)
    {
        if (!is_array($data)) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int) $store_id . "' AND `code` = '" . $this->db->escape($group) . "' AND `key` = '" . $this->db->escape($dnd) . " '");
            $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int) $store_id . "', `code` = '" . $this->db->escape($group) . "', `key` = '" . $this->db->escape($dnd) . "', `value` = '" . $this->db->escape($data) . "'");
        } else {
            foreach ($data as $key => $value) {
                $this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int) $store_id . "' AND `code` = '" . $this->db->escape($group) . "' AND `key` = '" . $this->db->escape($key) . " '");
                if (!is_array($value)) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int) $store_id . "', `code` = '" . $this->db->escape($group) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
                } else {
					if(version_compare(VERSION,'2.1.0','>')) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int) $store_id . "', `code` = '" . $this->db->escape($group) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(json_encode($value)) . "', serialized = '1'");
					}else{
                    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int) $store_id . "', `code` = '" . $this->db->escape($group) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(serialize($value)) . "', serialized = '1'");
                }
				}
            }
        }
        $this->db->query("DELETE FROM " . DB_PREFIX . "setting 	WHERE store_id = '" . (int) $store_id . "' AND `code` = '" . $this->db->escape($group) . "' AND  `key` IN ('SETTINGS','dnd','select','price','supercategorymenuadvanced_seo_man','supercategorymenuadvanced_seo_cat','token','route','category_id','manufacturer_id','store_id')");
        unset($data);
    }
    public function getCacheSMBD($key, $cat, $store_id)
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cache_supercategory cs WHERE cs.cat = '" . $cat . "' AND cs.name = '" . $key . "' AND cs.store_id = '" . $store_id . "' LIMIT 1");
        if ($query->num_rows) {
            $this->db->query("UPDATE " . DB_PREFIX . "cache_supercategory cs SET cs.cached=cs.cached+1 WHERE cs.cache_id = '" . $query->row['cache_id'] . "'");
					if(version_compare(VERSION,'2.1.0','>')) {
					 return json_decode($query->row['data'], true);
					}else{
            return unserialize($query->row['data']);
					}
        }
    }
    public function getSeoWord($string)
    {
        $query = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "url_alias u WHERE u.query = '" . $string . "'");
        if ($query->num_rows) {
            return $query->row['keyword'];
        } else {
            return false;
        }
    }
    public function editSeoKeyword($keyword, $where)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = '" . $where . "'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET `url_alias_id` = '', `query` = '" . $where . "', `keyword` = '" . $this->db->escape($keyword) . "'");
    }
    public function setCacheSMBD($key, $value, $cat, $store_id = 0)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "cache_supercategory WHERE name LIKE  '" . $key . "%'");
        $settings_module = $this->model_module_supercategorymenuadvanced->getMySetting('SETTINGS_' . $store_id, $store_id);
        $menu_mode       = isset($settings_module['general_data']['menu_mode']) ? $settings_module['general_data']['menu_mode'] : "developing";
        if ($menu_mode == "Production") {
            $this->db->query("INSERT INTO " . DB_PREFIX . "cache_supercategory 
			SET 
			`cache_id` = '', 
			`cat` = '" . $this->db->escape($cat) . "', 
			`store_id` = $store_id, 
			`name` = '" . $this->db->escape($key) . "', 
			`data` = '" . $this->db->escape(serialize($value)) . "'");
        }
    }
    public function getCacheCount($store_id)
    {
        $sql   = "SELECT COUNT(DISTINCT cache_id) AS total FROM " . DB_PREFIX . "cache_supercategory WHERE store_id = '" . (int) $store_id . "'";
        $query = $this->db->query($sql);
        return $query->row['total'];
    }
    public function createTable($tblname)
    {
        $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $tblname . "` (`cache_id` int(11) NOT NULL AUTO_INCREMENT, `store_id` int(11) NOT NULL, `man` varchar(10) COLLATE utf8_bin NOT NULL,`cat` varchar(10) COLLATE utf8_bin NOT NULL,`name` varchar(200) COLLATE utf8_bin DEFAULT NULL,`data` longtext  COLLATE utf8_bin NOT NULL,`cached` int(11) NOT NULL DEFAULT '1', `string` text COLLATE utf8_bin NOT NULL,`date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`cache_id`),	UNIQUE KEY `name` (`name`,`cat`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1";
        $this->db->query($sql);
    }
    public function createPriceRange($prices = array()) {
		$this->load->model('setting/setting');
		$range = array();
		sort($prices);
		$l = count($prices);
		for ($i = 0 ; $i < $l ; $i++){
			if (!isset($prices[$i])) { //last 
				break;
			}

			$min = isset($prices[$i-1])?$prices[$i-1]+1:$prices[$i]+1;
			$max = isset($prices[$i])?$prices[$i]:$prices[$i-1];
			if ($i == 0) { //first element
				$min = 0;
				$max = $prices[$i];
			}
			$range[] = array('min'=>$min,'max'=>$max);
		}
		//$range[] = array('min'=>max($prices)+1,'max'=>99999);
		//echo '<pre>';print_r($range);echo'</pre>';
		//die();
		$this->model_setting_setting->editSetting("smartprice",array('smartprice'=>$range));
		return $range;
	}
}
?>
