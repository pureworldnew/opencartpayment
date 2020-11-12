<?php

class ModelPosSeoUrl extends Model {

    
    public function checkIfGrouped($product_id) {
        //check if product exists in some grouped
        $exist_check_query = $this->db->query("SELECT groupindicator_id FROM product_concat_temp_table WHERE product_id=" . $product_id . "");
        if ($exist_check_query->num_rows > 0) {
            //check if group indicator is valid i.e. it has been grouped
            $valid_check_query = $this->db->query("SELECT group_product_id FROM " . DB_PREFIX . "product_grouped_indicator WHERE group_indicator=" . $exist_check_query->row['groupindicator_id'] . "");
            if ($valid_check_query->num_rows > 0) {
                return $valid_check_query->row['group_product_id'];
            }
        }
        return 0;
    }

    
    public function isProductGrouped($product_id) {
        $query = $this->db->query("SELECT model FROM " . DB_PREFIX . "product WHERE product_id=" . $product_id . "");
        if ($query->row['model'] == 'grouped') {
            return $this->getGroupProductId($product_id);
        } else {
            //$pro_id = $this->getproductIdBySku($query->row['sku'], $query->row['upc'], $query->row['model']);
            $pro_id = $this->getproductIdByProductId($product_id);
            if ($pro_id) {
                return $pro_id;
            } else {
                return FALSE;
            }
        }
    }

    public function getGroupProductId($product_id) {
        $query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product_grouped WHERE grouped_id=" . $product_id . "");
        if ($query->num_rows) {
            return $query->row['product_id'];
        } else {
            return FALSE;
        }
    }

    public function getproductIdBySku($sku, $upc, $model) {
        $sel_query = $this->db->query("SELECT groupindicator_id from product_concat_temp_table WHERE sku = '" . $sku . "' OR sku = '" . $upc . "' OR sku = '" . $model . "'");
        if ($sel_query->num_rows) {
            return $this->getGroupProductIds($sel_query->row['groupindicator_id']);
        } else {
            return FALSE;
        }
    }

    public function getproductIdByProductId($product_id) {
        $sel_query = $this->db->query("SELECT groupindicator_id from product_concat_temp_table WHERE product_id = '" . $product_id . "'");
        if ($sel_query->num_rows) {
            return $this->getGroupProductIds($sel_query->row['groupindicator_id']);
        } else {
            return FALSE;
        }
    }

    public function getGroupProductIds($groupindicator_id) {
        $query = $this->db->query("SELECT group_product_id FROM " . DB_PREFIX . "product_grouped_indicator WHERE group_indicator='" . $groupindicator_id . "'");
        if ($query->num_rows) {
            return $query->row['group_product_id'];
        } else {
            return FALSE;
        }
    }

    public function seoUrlkeyword($query) {
        $qu = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query ='$query'");
        return $qu->row['keyword'];
    }

    public function productOrCaregory($check_var, $link_rewrite) {
        $return_array = array();
        $qu = $this->db->query("SELECT link_rewrite FROM ps_category_lang WHERE id_category ='$check_var' AND link_rewrite = '$link_rewrite' limit 1");
        if ($qu->num_rows > 0 && !empty($qu->row['link_rewrite'])) {
            $return_array = array(
                'link' => 'category_id=' . $check_var,
                'c-or-p' => 1
            );
            return $return_array;
        }
        $qu1 = $this->db->query("SELECT link_rewrite FROM ps_product_lang WHERE id_product ='$check_var' AND link_rewrite = '$link_rewrite' limit 1");
        if ($qu1->num_rows > 0 && !empty($qu1->row['link_rewrite'])) {
            $return_array = array(
                'link' => 'product_id=' . $check_var,
                'c-or-p' => 2
            );
            return $return_array;
        }
    }

    public function getDbRedirectLink($route_url) {
        $query = $this->db->query("SELECT redirect_links FROM redirect_link WHERE address = '$route_url'");
        if ($query->num_rows > 0 && $query->row['redirect_links'] != '-') {
            return $query->row['redirect_links'];
        }
    }

    public function productSku($product_id) {
        $query = $this->db->query("SELECT sku FROM " . DB_PREFIX . "product WHERE product_id = '$product_id'");
        if ($query->num_rows > 0) {
            return $query->row['sku'];
        }
    }

}

?>