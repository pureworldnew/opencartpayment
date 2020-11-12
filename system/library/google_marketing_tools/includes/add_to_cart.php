<?php
  $manufacturer = $this->GMTMaster->get_product_manufacturer($product_info['product_id']);
  $category_name = $this->GMTMaster->get_product_category_name($product_info['product_id']);
  $price = $this->GMTMaster->format_price($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), true);

  $json['atc_status'] = true;
  $json['atc_id'] = $product_info['product_id'];
  $json['atc_name'] = str_replace('&amp;','&', $product_info['name']);
  $json['atc_brand'] = $manufacturer;
  $json['atc_category'] = $category_name;
  $json['atc_quantity'] = $quantity;
  $json['atc_price'] = $price;
  $json['atc_variant'] = '';

  //Variants
    if(!empty($option))
      $json['atc_variant'] = $this->GMTMaster->get_product_variant($product_info['product_id'], $option);
  //END
?>