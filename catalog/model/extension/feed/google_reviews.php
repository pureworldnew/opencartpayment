<?php
class ModelExtensionFeedGoogleReviews extends Model {
    function getReviews($language_id, $only_these_products = false) {
        $query = 'SELECT
            ocr.`review_id`,
            ocr.`customer_id`,
            ocr.`date_added`,
            ocr.`author`,
            ocr.`rating`,
            ocr.`text`,
            ocp.`product_id`,
            ocp.`sku`,
            ocp.`mpn`,
            ocp.`model`,
            ocm.`name` as manufacturer,
            ocpd.`name` as product_name
        FROM '.DB_PREFIX.'review ocr
        LEFT JOIN '.DB_PREFIX.'product ocp ON(ocp.product_id = ocr.product_id)
        LEFT JOIN '.DB_PREFIX.'product_description ocpd ON(ocp.product_id = ocpd.product_id AND ocpd.`language_id` = '.$language_id.')
        LEFT JOIN '.DB_PREFIX.'manufacturer ocm ON(ocm.manufacturer_id = ocp.manufacturer_id)
        
        WHERE ocr.status = 1';

        if($only_these_products)
            $query .= ' AND ocr.product_id IN('.implode($only_these_products, ",").')';


        $reviews = $this->db->query($query);

        return $reviews->rows;
    }
}
?>