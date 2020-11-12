<?php
    $manufacturer = $this->GMTMaster->get_product_manufacturer($product_info['product_id']);
    $category_name = $this->GMTMaster->get_product_category_name($product_info['product_id']);

    $json['atw_status'] = true;
    $json['atw_id'] = $product_info['product_id'];
    $json['atw_name'] = $product_info['name'];
    $json['atw_brand'] = $manufacturer;
    $json['atw_category'] = $category_name;
    $json['atw_price'] = $this->GMTMaster->get_product_price($product_info);
?>