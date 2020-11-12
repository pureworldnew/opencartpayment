<?php
class ModelExtensionFeedGoogleBasePro extends Model {
  public function getProducts($categories_allowed = array(), $only_these_products = false) {
      $spe_cg = $this->special_customer_group_id ? $this->special_customer_group_id : 1;

    $sql = "SELECT DISTINCT
      p.product_id as product_id,
      p.quantity as quantity,
      p.price as price,
      pd.name as name,
      pd.description as description,
      pd.meta_description as meta_description,
      pd.meta_keyword as meta_keyword,
      ma.name as manufacturer,
      p.image as image,
      p.model as model,";

    if(version_compare(VERSION, '1.5.3.1', '>'))
      $sql .= "p.ean as ean,
      p.mpn as mpn,";

    $sql .= "p.tax_class_id as tax_class_id,
      ps.price as special,
      p.upc as upc,
      p.weight as weight,
      p.weight_class_id as weight_class_id,
      p.sku as sku,
      p.jan as jan,
      p.isbn as isbn

      FROM " . DB_PREFIX . "product p ";

    if(!empty($categories_allowed))
    {
      $cat_string = '';
      foreach ($categories_allowed as $key => $value) {
        $cat_string .= $value.',';
      }
      $cat_string = trim($cat_string, ',');

      $sql .= " INNER JOIN " . DB_PREFIX . "product_to_category ptc ON (ptc.product_id = p.product_id AND ptc.category_id IN(".$cat_string." ))";
    }

    $sql .= "INNER JOIN " . DB_PREFIX . "product_to_store pte ON (pte.product_id = p.product_id AND store_id = ".(int)$this->config->get('config_store_id').")
      LEFT JOIN " . DB_PREFIX . "product_description pd ON (pd.product_id = p.product_id AND language_id = ".(int)$this->language_id.")
      LEFT JOIN " . DB_PREFIX . "manufacturer ma ON (p.manufacturer_id = ma.manufacturer_id)
      LEFT JOIN (SELECT * FROM `" . DB_PREFIX . "product_special` pss WHERE ((pss.date_start = '0000-00-00' OR pss.date_start < NOW()) AND (pss.date_end = '0000-00-00' OR pss.date_end > NOW())) AND pss.customer_group_id = ".$spe_cg." ORDER BY pss.priority ASC) ps ON (ps.product_id = p.product_id)
      WHERE p.status = 1";

    if($only_these_products)
        $sql .= ' AND p.product_id IN('.implode($only_these_products, ",").')';

    $sql .= " GROUP BY p.product_id";

    $query = $this->db->query($sql);

    /*To personal developer OPENSTOCK*/
    return $query->rows;
  }

  public function getProductsFromView($viewName, $only_these_products = false) {
    $sql = "SELECT * FROM ".$viewName;

    if($only_these_products)
        $sql .= 'WHERE product_id IN('.implode($only_these_products, ",").')';

    $query = $this->db->query($sql);
    return $query->rows;
  }

  function getCategories()
  {
    $sql = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS name, c1.parent_id, c1.sort_order FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category c1 ON (cp.category_id = c1.category_id) LEFT JOIN " . DB_PREFIX . "category c2 ON (cp.path_id = c2.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id) WHERE cd1.language_id = '" . (int)$this->language_id . "' AND cd2.language_id = '" . (int)$this->language_id . "'";
    $sql .= " GROUP BY cp.category_id ORDER BY sort_order ASC";
    $query = $this->db->query($sql);
    return $query->rows;
  }
  function getCategories_oc_old($parent_id = 0) {   
    $category_data = array();
  
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE cd.language_id = '" . (int)$this->language_id . "' ORDER BY c.sort_order, cd.name ASC");

    foreach ($query->rows as $result) {
      $category_data[] = array(
        'category_id' => $result['category_id'],
        'name'        => $result['name'],
        'status'      => $result['status'],
        'sort_order'  => $result['sort_order']
      );
    }
    return $category_data;
  }
}
?>