<?php
class ModelCatalogUnitConversion extends Model {
    public function addUnit($data){
        $sql_query = "INSERT INTO `" . DB_PREFIX . "unit_conversion` SET language_id = 1, name = '" . $this->db->escape($data['unit_name']) . "', sort_order = '" . (int)$data['sort_order'] . "'";
        $this->db->query($sql_query);
        
        $unit_id = $this->db->getLastId();
        
        if (isset($data['unit_value'])) {
            foreach ($data['unit_value'] as $unit_value) {
//                $query = "INSERT INTO " . DB_PREFIX . "unit_conversion_value SET unit_id  = '" . (int)$unit_id . "', language_id = 1 , name = '" . $this->db->escape(html_entity_decode($unit_value['name'], ENT_QUOTES, 'UTF-8')) . "' ,convert_price = '". $unit_value['convert_price']."', sort_order = '" . (int)$unit_value['sort_order'] . "'";
                $query = "INSERT INTO " . DB_PREFIX . "unit_conversion_value SET unit_id  = '" . (int)$unit_id . "', language_id = 1 , name = '" . $this->db->escape(html_entity_decode($unit_value['name'], ENT_QUOTES, 'UTF-8')) ."', sort_order = '" . (int)$unit_value['sort_order'] . "'";
                $this->db->query($query);
            }
        }
    }
    
//    public function getUnitValues($unit_id) {
//        $unit_value_data = array();
//
//        $unit_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "unit_conversion WHERE unit_id = '" . (int)$unit_id . "' ORDER BY sort_order ASC");
//
//        foreach ($unit_value_query->rows as $unit_value) {
//                $unit_value_data[] = array(
//                        'unit_id'         => $unit_value['unit_id'],
//                        'name'            => $unit_value['name'],
//                        'sort_order'      => $unit_value['sort_order']
//                );
//        }
//
//        return $unit_value_data;
//    }
    
    public function getOptionValueDescriptions($unit_id) {
        $unit_value_data = array();

        $unit_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "unit_conversion_value WHERE unit_id = '" . (int)$unit_id . "'");

        foreach ($unit_value_query->rows as $unit_value) {
                $unit_value_data[] = array(
                        'unit_value_id'          => $unit_value['unit_value_id'],
                        'unit_value_name'        => $unit_value['name'],
                        //'convert_price'          => $unit_value['convert_price'],
                        'sort_order'              => $unit_value['sort_order']
                );
        }

        return $unit_value_data;
    }
    
    public function getUnits($data = array()) {
        $sql = "SELECT * FROM `" . DB_PREFIX . "unit_conversion` u WHERE language_id = 1";

        if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
                $sql .= " AND u.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }

        $sort_data = array(
                'u.name',
                'u.sort_order'
        );	

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
                $sql .= " ORDER BY " . $data['sort'];	
        } else {
                $sql .= " ORDER BY u.name";	
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
    
    public function editUnit($unit_id, $data) {
            $this->db->query("UPDATE `" . DB_PREFIX . "unit_conversion` SET name = '" . $this->db->escape($data['unit_name']) . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE unit_id = '" . (int)$unit_id . "'");

            //$sql_unit_value = "DELETE FROM `" . DB_PREFIX . "unit_conversion_value` WHERE unit_id = '" . (int)$unit_id . "'";
            //$this->db->query($sql_unit_value);
            
            if (isset($data['unit_value'])) {
                    foreach ($data['unit_value'] as $unit_value) {
                        //$query = "INSERT INTO " . DB_PREFIX . "unit_conversion_value SET unit_id  = '" . (int)$unit_id . "', language_id = 1 , name = '" . $this->db->escape(html_entity_decode($unit_value['name'], ENT_QUOTES, 'UTF-8')) . "' ,convert_price = '". $unit_value['convert_price']."', sort_order = '" . (int)$unit_value['sort_order'] . "'";
                        $query = "UPDATE " . DB_PREFIX . "unit_conversion_value 
                                    SET 
                                    unit_id  = '" . (int)$unit_id . "', 
                                    language_id = 1 , 
                                    name = '" . $this->db->escape(html_entity_decode($unit_value['name'], ENT_QUOTES, 'UTF-8')) ."', 
                                    sort_order = '" . (int)$unit_value['sort_order'] . "'
                                    WHERE unit_value_id = '" . $unit_value['unit_value_id']. "'";
                        $this->db->query($query);
                    }
            }
    }
    
    public function getUnitValueDescriptions($unit_id) {
        $unit_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "unit_conversion_value WHERE unit_id = '" . (int)$unit_id . "'");
        

        foreach ($query->rows as $result) {
                $unit_data[] = array(
                                'name' => $result['name'], 
                                //'convert_price' => $result['convert_price'], 
                                'sort_order' => $result['sort_order'], 
                                'unit_value_id' => $result['unit_value_id']);
        }
        return $unit_data;
    }
    
    public function getUnitDataDescriptions($unit_id) {
        $unit_data = array();
        $sql = "SELECT * FROM " . DB_PREFIX . "unit_conversion WHERE unit_id = '" . (int)$unit_id . "'";
        $query = $this->db->query($sql);
        $unit_data[] = array(
                        'unit_name' => $query->row['name'],
                        'unit_sortorder' => $query->row['sort_order']);
        return $unit_data;
    }
    
    public function deleteUnit($unit_id) {
        $sql_unit = "DELETE FROM `" . DB_PREFIX . "unit_conversion` WHERE unit_id = '" . (int)$unit_id . "'";
        $this->db->query($sql_unit);
        $sql_unit_value = "DELETE FROM `" . DB_PREFIX . "unit_conversion_value` WHERE unit_id = '" . (int)$unit_id . "'";
        $this->db->query($sql_unit_value);
    }
    
    public function getTotalUnits() {
    $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "unit_conversion`"); 

            return $query->row['total'];
    }
    public function getDefaultUnitDetails($product_id){
		  $query = "SELECT ucv.name,ucp.convert_price,ucp.sort_order,ucp.unit_conversion_product_id
				  FROM ". DB_PREFIX ."unit_conversion_product ucp 
				  LEFT JOIN ". DB_PREFIX ."unit_conversion_value ucv on ucp.unit_value_id=ucv.unit_value_id 
				  WHERE ucp.convert_price = 1 AND product_id = '$product_id' order by sort_order LIMIT 0,1";
		  $query2 = $this->db->query($query);
		  return $query2->row;
    }
    public function getOrderUnits($unit_conversion_values) {
            if($unit_conversion_values != 0){
                    $sql_unit_sql = "SELECT unit_id, unit_value_id,convert_price FROM " . DB_PREFIX . "unit_conversion_product WHERE unit_conversion_product_id = $unit_conversion_values";
                    $res_unit_sql = $this->db->query($sql_unit_sql);
                    
                    $unit_id = $res_unit_sql->row['unit_id'];
                    $unit_value_id = $res_unit_sql->row['unit_value_id'];
					$convert_price = $res_unit_sql->row['convert_price'];
                    
                    $sql_unit_name = "SELECT name FROM " . DB_PREFIX . "unit_conversion WHERE unit_id = $unit_id";
                    $res_unit_name = $this->db->query($sql_unit_name);
                    $unit_name = $res_unit_name->row['name'];
                    
                    $sql_unit_value_name = "SELECT name FROM " . DB_PREFIX . "unit_conversion_value WHERE unit_value_id = $unit_value_id";
                    $res_unit_value_name = $this->db->query($sql_unit_value_name);
                    $unit_value_name = $res_unit_value_name->row['name'];
                    
                    $unit_data = array(
                                        'unit_conversion_values' => $unit_conversion_values,
                                        'unit_name' => $unit_name,
                                        'unit_value_name' => $unit_value_name,
										'convert_price' => $convert_price
                                        );
                    return $unit_data;
                } else {
                    return 0;
                }
                
    }
    
    public function getProductUnits($product_id) {
            $sql_unit_sql = "SELECT unit_id, unit_value_id, convert_price, unit_conversion_product_id FROM " . DB_PREFIX . "unit_conversion_product WHERE product_id = '" . (int)$product_id . "'";
            $res_unit_sql = $this->db->query($sql_unit_sql);

            $unit_data = array();
            $unit_datas = array();
            if($res_unit_sql->num_rows > 0){
                foreach ($res_unit_sql->rows as $data){
                    $unit_id = $data['unit_id'];
                    $unit_value_id = $data['unit_value_id'];
                    $convert_price = $data['convert_price'];
                    $unit_conversion_values = $data['unit_conversion_product_id'];

                    $sql_unit_name = "SELECT name FROM " . DB_PREFIX . "unit_conversion WHERE unit_id = $unit_id";
                    $res_unit_name = $this->db->query($sql_unit_name);
                    $unit_name = $res_unit_name->row['name'];

                    $sql_unit_value_name = "SELECT name FROM " . DB_PREFIX . "unit_conversion_value WHERE unit_value_id = $unit_value_id";
                    $res_unit_value_name = $this->db->query($sql_unit_value_name);
                    $unit_value_name = $res_unit_value_name->row['name'];

                    $unit_datas[] = array(
                                    'unit_conversion_values' => $unit_conversion_values,
                                    
                                    'unit_value_name' => $unit_value_name,
                                    'convert_price' => $convert_price
                            );
                    $unit_data = array(
                        'unit_name' => $unit_name,
                        'unit_value' => $unit_datas
                    );
                }
                return array($unit_data);
            } else {
                return FALSE;
            }
        }
}
?>