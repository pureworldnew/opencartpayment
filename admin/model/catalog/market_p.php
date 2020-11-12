<?php

class ModelCatalogMarketP extends Model {

    public function updatePrice($data) {
        foreach ($data as $cat_name => $market_price) {
            $this->_updateMarketPrice($cat_name, $market_price);
        }
    }
    private function _updateMarketPrice($cat_name, $market_price) {
        if($market_price !="") {
            $update_query = "UPDATE ".DB_PREFIX."category c LEFT JOIN ".DB_PREFIX.""
                    . "category_description cd ON c.category_id = cd.category_id"
                    . " SET c.metal_price= '".$market_price."' WHERE cd.name LIKE '".$cat_name."%'";
            $this->db->query($update_query);
        }
    }
	
	public function updateDiscountrates() {
       
           $query="UPDATE ".DB_PREFIX."product_discount pd LEFT JOIN "
		                . "".DB_PREFIX."product p ON pd.product_id=p.product_id SET "
		                . "pd.price=p.price - (p.price * (pd.discount_percent/100)) where 1;";
		   $this->db->query($query);
       
    }

    public function updateProductsMultiplierValues($multiplier_data)
    {
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "10000M");
        ini_set('default_socket_timeout', 600);
        $products = $this->getMultiplierProducts();
        if( !empty($products) )
        {
            foreach($products as $product)
            {
                $product_id         = $product['product_id'];
                $multiplier_name    = $product['multiplier_name'];
                if( isset($multiplier_data[$multiplier_name]) )
                {
                    $multiplier_value   = $multiplier_data[$multiplier_name];
                    $this->updateProductMultiplierValue($product_id, $multiplier_value);
                }
            }
        }
    }

    public function getMultiplierProducts()
    {
        $query = $this->db->query("SELECT product_id, multiplier_name FROM " . DB_PREFIX . "product WHERE multiplier_name <> ''");
        return $query->rows;
    }

    public function updateProductMultiplierValue($product_id, $multiplier_value)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "product SET multiplier_value = '" . $this->db->escape($multiplier_value) . "' WHERE product_id = '" . (int)$product_id . "'");
    }
}
