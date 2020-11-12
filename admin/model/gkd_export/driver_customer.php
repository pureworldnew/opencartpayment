<?php
class ModelGkdExportDriverCustomer extends Model {
  
  public function getItems($data = array(), $count = false) {
    $select = ($count) ? 'COUNT(DISTINCT c.customer_id) AS total' : "*, CONCAT(c.firstname, ' ', c.lastname) AS name, cgd.name AS customer_group";
    
    // hide sensible info for demo user
    if (($this->user->getUserName() == 'demo' || !$this->user->hasPermission('modify', 'module/universal_import')) && !$count) {
      $select .= ", '******' AS password, '*******@mail.com' AS email, '' AS salt, '' AS token, '' AS code, '' AS ip";
    }
    
    $sql = "SELECT ".$select." FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id)";
    
    // Where
    $sql .= " WHERE cgd.language_id = '" . (int)$data['filter_language'] . "'";
    
    if (!empty($data['filter_status'])) {
      $sql .= " AND c.status = '" . (int)$data['filter_status'] . "'";
    }
    
    if (!empty($data['filter_approved'])) {
      $sql .= " AND c.approved = '" . (int)$data['filter_status'] . "'";
    }
    
    if (!empty($data['filter_newsletter'])) {
      $sql .= " AND c.newsletter = '" . (int)$data['filter_newsletter'] . "'";
    }
    
		if (!empty($data['filter_name'])) {
			$sql .= " AND m.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

    // return count
    if ($count) {
      return $this->db->query($sql)->row['total'];
    }
    
    $sql .= " ORDER BY c.customer_id";
    
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql)->rows;
    
    foreach($query as $key => $cust) {
      $addresses = $this->getAddresses($cust['customer_id']);
      foreach($addresses as $address) {
        // $query[$key] = array_merge($query[$key], $address);
        $query[$key] = $query[$key] + $address;
      }
    }
    
		return $query;
	}
  
  public function getAddress($address_id, $addressCount) {
		$address_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)$address_id . "'");

		if ($address_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$address_query->row['country_id'] . "'");

			if ($country_query->num_rows) {
				$country = $country_query->row['name'];
				$iso_code_2 = $country_query->row['iso_code_2'];
				$iso_code_3 = $country_query->row['iso_code_3'];
				$address_format = $country_query->row['address_format'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$address_query->row['zone_id'] . "'");

			if ($zone_query->num_rows) {
				$zone = $zone_query->row['name'];
				$zone_code = $zone_query->row['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}
      
      $custom_fields = '';
      
      if (!empty($address_query->row['custom_field'])) {
        $cust_fields = json_decode($address_query->row['custom_field'], true);
        
        foreach ($cust_fields as $key => $val) {
          $custom_fields .= $custom_fields ? '|' : '';
          $custom_fields .= $key.':'.$val;
        }
      }
      
      $addressCount = $addressCount ? $addressCount : '';
      
			return array(
				// 'address_id'     => $address_query->row['address_id'],
				// 'customer_id'    => $address_query->row['customer_id'],
				'address'.$addressCount.'_firstname'      => $address_query->row['firstname'],
				'address'.$addressCount.'_lastname'       => $address_query->row['lastname'],
				'address'.$addressCount.'_company'        => $address_query->row['company'],
				'address'.$addressCount.'_line_1'        => $address_query->row['address'.$addressCount.'_1'],
				'address'.$addressCount.'_line_2'       => $address_query->row['address'.$addressCount.'_2'],
				'address'.$addressCount.'_postcode'       => $address_query->row['postcode'],
				'address'.$addressCount.'_city'           => $address_query->row['city'],
				// 'zone_id'        => $address_query->row['zone_id'],
				'address'.$addressCount.'_zone'           => $zone,
				'address'.$addressCount.'_zone_code'      => $zone_code,
				// 'country_id'     => $address_query->row['country_id'],
				'address'.$addressCount.'_country'        => $country,
				'address'.$addressCount.'_iso_code_2'     => $iso_code_2,
				'address'.$addressCount.'_iso_code_3'     => $iso_code_3,
				// 'address'.$addressCount.'_format' => $address_format,
				'address'.$addressCount.'_custom_fields'   => $custom_fields
			);
		}
	}

	public function getAddresses($customer_id) {
		$address_data = array();

		$query = $this->db->query("SELECT address_id FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "'");

		foreach ($query->rows as $i => $result) {
			$address_info = $this->getAddress($result['address_id'], $i);

			if ($address_info) {
				$address_data[$result['address_id']] = $address_info;
			}
		}

		return $address_data;
	}
  
  public function getTotalItems($data = array()) {
    return $this->getItems($data, true);
  }
}