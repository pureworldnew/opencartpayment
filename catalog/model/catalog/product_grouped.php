<?php

/*
  #file: catalog/model/catalog/product_grouped.php
  #powered by fabiom7 - www.fabiom7.com - fabiome77@hotmail.it - copyright fabiom7 2012 - 2013 - 2014
 */

class ModelCatalogProductGrouped extends Model {

    //max grouping options
    public $max_options = 11;

    public function getProductGroupedType($product_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_grouped_type WHERE product_id = '" . (int) $product_id . "' LIMIT 1");

        if ($query->num_rows) {
            return array(
                'pg_type' => $query->row['pg_type'],
                'pg_groupby' => $query->row['groupby'],
                'pg_layout' => $query->row['pg_layout']
            );
        } else {
            return false;
        }
    }

    public function getProductRelatedIsGrouped($product_id) {
        $query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product_grouped WHERE product_id = '" . $product_id . "' LIMIT 1");

        return $query->num_rows;
    }
	
	public function getProductGroupedByName($product_id)
	{
		$query = $this->db->query("SELECT group_indicator FROM " . DB_PREFIX . "product_grouped_indicator WHERE group_product_id = '" . $product_id . "'");
		$groupindicator_id = $query->row ? $query->row['group_indicator'] : 0;
		if($groupindicator_id)
		{
			$query = $this->db->query("SELECT groupbyname FROM product_concat_temp_table WHERE groupindicator_id = '" . $groupindicator_id . "' LIMIT 1");
			return $query->row ? $query->row['groupbyname'] : "";
		}
		return "";
	}

    public function getGrouped($product_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_grouped WHERE product_id = '" . $product_id . "' ORDER BY grouped_sort_order");
        $products = $query->rows;
        $valid_products = array();
        if ( $products )
        {
            foreach ( $products as $product )
            {
                $enabled = $this->getProductStatusFromDb($product['grouped_id']);
                if ( $enabled )
                {
                    $valid_products[] = $product;
                }
            }
        }


        return $valid_products;
    }

    public function getProductStatusFromDb($product_id)
    {
        $query = $this->db->query("SELECT status FROM " . DB_PREFIX . "product WHERE product_id = '" . $product_id . "' LIMIT 1");

        return $query->row ? $query->row['status'] : false;
    }

    public function getGroupIndicator($group_product_id) {
        $query = $this->db->query("SELECT group_indicator FROM " . DB_PREFIX . "product_grouped_indicator WHERE group_product_id = '" . $group_product_id . "'");
        if ($query->num_rows > 0) {
            return $query->row['group_indicator'];
        } else {
            return false;
        }
    }

    public function getSelectedOptionProductId($data) {
        $options = $data['option_text'];
        $num_options = $data['num_options'];
        $sql = "SELECT sku FROM product_concat_temp_table WHERE  groupbyvalue='" . $this->db->escape(trim($data['group_selector'])) . "' AND groupindicator='" . $this->db->escape($data['group_indicator']) . "'";
        $selected_option_values = array();

        foreach ($options as $key => $option_text) {
            $option_num = $key + 1;
            $opt_data = explode('~', $option_text, 2);
            $optname = $opt_data[0];
            $opt_val = $opt_data[1];
            $num_opt = $data['num_options'];
            for ($x = 1; $x <= $num_opt; $x++) {
                $sql_query = "SELECT optionname" . $x . " FROM product_concat_temp_table WHERE groupbyvalue='" . $this->db->escape(trim($data['group_selector'])) . "' AND groupindicator='" . $this->db->escape($data['group_indicator']) . "' AND optionname" . $x . "='" . $this->db->escape($optname) . "'";
                $query_sql = $this->db->query($sql_query);
                if ($query_sql->num_rows) {
                    $sql .= " AND optionvalue" . $x . "='" . $this->db->escape($opt_val) . "'";
                }
            }
        }
        $query = $this->db->query($sql);
        if ($query->num_rows) {
            return $this->getproductIdBySku($query->row['sku']);
        } else {

            return FALSE;
        }
    }

    public function getOptionsNames($where) {

        $sql = "SELECT * FROM product_concat_temp_table WHERE groupindicator_id='" . $where['groupindicator'] . "' and product_id='" . $where['product_id'] . "'";
        $op = $this->db->query($sql);
        $da = array(
            'all' => $op->row,
            'columns' => array()
        );
		
        foreach ($op->row as $key => $value) {
            if (preg_match('/(optionname)/', $key)) {
                if ($value !== '') {
                    $da['columns'][$key] = $value;
                }
            }
        }
		
		//print_r($da);
        return $da;
    }

    public function getCombination($where, $field = 'product_id') {
        $sql = "SELECT $field FROM product_concat_temp_table";
        if (!empty($where)) {
            $sql.=" where ";
            foreach ($where as $key => $wh) {
                $sql.=$key . "='" . $wh . "' and ";
            }
            $sql = trim($sql, "and ");
            //echo $sql;
			$query = $this->db->query($sql);
            return $query;
        } else {
            return false;
        }
    }

    public function getproductIdBySku($sku) {
        $sel_query = $this->db->query("SELECT product_id from " . DB_PREFIX . "product WHERE status = 1 AND sku='" . $sku . "'");
        if ($sel_query->num_rows) {
            return $sel_query->row['product_id'];
        } else {
            $sel_query_upc = $this->db->query("SELECT product_id from " . DB_PREFIX . "product WHERE status = 1 AND upc='" . $sku . "'");
            if ($sel_query_upc->num_rows) {
                return $sel_query_upc->row['product_id'];
            } else {
                $sel_query_model = $this->db->query("SELECT product_id from " . DB_PREFIX . "product WHERE status = 1 AND model='" . $sku . "'");
                if ($sel_query_model->num_rows) {
                    return $sel_query_model->row['product_id'];
                } else {
                    return FALSE;
                }
            }
        }
    }

    public function getDisabledProducts($groupindicator_id)
    {
        $product_ids = array();
        $query = $this->db->query('SELECT product_id FROM product_concat_temp_table WHERE groupindicator_id ="' . $groupindicator_id . '"');

        if ($query->num_rows > 0) {
            foreach ($query->rows as $pi) {
                $product_ids[] = "'" . $pi['product_id'] . "'";
            }
        }

        if (!empty($product_ids)) {
            $q = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE product_id IN (" . implode(',', $product_ids) . ") AND status = 0");
            $pids = array();
            if ($q->num_rows > 0) {
                foreach ($q->rows as $product) {
                    $pids[] = $product['product_id'];
                }

                return $pids;
            }
        }
        return $product_ids;

    }

    public function getProductStatus($gi) {
        $skus = array();
        $d = $this->db->query('SELECT sku FROM product_concat_temp_table where groupindicator="' . $gi . '"');
        if ($d->num_rows > 0) {
            foreach ($d->rows as $sku) {
                $skus[] = "'" . $sku['sku'] . "'";
            }
        }

        if (!empty($skus)) {
            $s = $this->db->query("SELECT sku from " . DB_PREFIX . "product WHERE sku IN (" . implode(',', $skus) . ") and status=0");
            $skus = array();
            if ($s->num_rows > 0) {
                foreach ($s->rows as $sku) {
                    $skus[] = $sku['sku'];
                }
            }
        }
        return $skus;
    }

    public function getCombinationSku($group_indicator) {
        $sql = $this->db->query("SELECT * from product_concat_temp_table WHERE groupindicator = '" . $group_indicator . "' LIMIT 1");
        if ($sql->num_rows > 0) {
            return $sql->row['sku'];
        } else {
            return false;
        }
    }

    public function getSkuOfCombination($selOpt, $group_indicator) {
        
    }

    public function getCombinationProductId($sku) {
        $sql = $this->db->query("SELECT product_id from " . DB_PREFIX . "product WHERE sku = '" . $sku . "'");
        if ($sql->num_rows > 0) {
            return $sql->row['product_id'];
        } else {
            return false;
        }
    }

    public function getProductOptionIds($product_id, $selected_options) {
        $option_values_ids = array();
        foreach ($selected_options as $selected_option) {
            $sp = explode('~', $selected_option);
            $option_name = $sp[0];
            $option_value_name = $sp[1];

            $sql = $this->db->query("SELECT option_id from " . DB_PREFIX . "option_description WHERE name = '" . trim($option_name) . "' AND language_id = 1");
            if ($sql->num_rows > 0) {
                $option_id = $sql->row['option_id'];
            } else {
                $option_id = 0;
            }
            $sql1 = $this->db->query("SELECT option_value_id from " . DB_PREFIX . "option_value_description WHERE name = '" . trim($option_value_name) . "' AND language_id = 1 AND option_id = '" . $option_id . "'");
            if ($sql1->num_rows > 0) {
                $option_value_id = $sql1->row['option_value_id'];
            } else {
                $option_value_id = 0;
            }

            $sql2 = $this->db->query("SELECT product_option_id from " . DB_PREFIX . "product_option WHERE product_id = '" . $product_id . "' AND option_id = '" . $option_id . "'");
            if ($sql2->num_rows > 0) {
                $product_option_id = $sql2->row['product_option_id'];
            } else {
                $product_option_id = 0;
            }

            $sql3 = $this->db->query("SELECT product_option_value_id from " . DB_PREFIX . "product_option_value WHERE product_id = '" . $product_id . "' AND product_option_id = '" . $product_option_id . "' AND option_id = '" . $option_id . "' AND option_value_id = '" . $option_value_id . "'");
            if ($sql3->num_rows > 0) {
                $product_option_value_id = $sql3->row['product_option_value_id'];
            } else {
                $product_option_value_id = 0;
            }
			
			
			
			$option_name = str_replace('?','',$option_name);
			$option_name = str_replace(' ','',$option_name);
            $option_values_ids[] = array(
                $option_name => array(
                    'product_option_id' => $product_option_id,
                    'option_value' => array(
                        'name' => $option_value_name,
                        'product_option_value_id' => $product_option_value_id
                    )
                )
            );
        }
        return $option_values_ids;
    }

    /**
     * This function handles :
     * 1) get product from group concat table
     * @author : Manish Gautam
     */
    public function getGroupedData($product_id, $groupindicator_id) {
		//echo "SELECT * FROM product_concat_temp_table WHERE product_id=" . $product_id . " AND groupindicator_id='" . $groupindicator_id . "'";
        $product_data_select_query = $this->db->query("SELECT * FROM product_concat_temp_table WHERE product_id=" . (int)$product_id . " AND groupindicator_id='" . (int)$groupindicator_id . "'");
        if ($product_data_select_query->num_rows > 0) {
            $product_data = $product_data_select_query->row;

            $required_data = array();

            $required_data['groupbyname'] = $product_data['groupbyname'];
            $required_data['groupbyvalue'] = $product_data['groupbyvalue'];

            $option_count = 0;

            for ($i = 0; $i <= $this->max_options; $i++) {
                if (!empty($product_data['optionname' . $i])) {
                    $required_data['optionname' . $i] = trim($product_data['optionname' . $i]);
                    $required_data['optionvalue' . $i] = trim($product_data['optionvalue' . $i]);
                    $option_count++;
                }
            }

            $required_data['option_count'] = $option_count;
			//print_r($required_data);
            return $required_data;
        } else {
            return FALSE;
        }
    }


    public function getNewGroupingSystemStatus($product_id) {
        $product_data_select_query = $this->db->query("SELECT new_grouping_system FROM product_concat_temp_table WHERE product_id=" . (int)$product_id . "");
        if ($product_data_select_query->num_rows > 0) {
            $product_data = $product_data_select_query->row;
            return $product_data['new_grouping_system'];
        } else {
            return 0;
        }
    }

}

?>
