<?php

class ModelCatalogMarketPrice extends Model {

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
}
