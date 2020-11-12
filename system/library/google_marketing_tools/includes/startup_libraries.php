<?php
    if(!defined('HTTP_CATALOG')) {
        if (version_compare(VERSION, '2.1.0.3', '>')) {
            $this->registry->set('GMTMaster', new google_marketing_tools\master($this->registry));
            $this->registry->set('GoogleTagManager', new google_marketing_tools\google_tag_manager($this->registry));
            $this->registry->set('DataLayer', new google_marketing_tools\data_layer($this->registry));
            $this->registry->set('FacebookPixel', new google_marketing_tools\facebook_pixel($this->registry));
            $this->registry->set('GoogleDynamicRemarketing', new google_marketing_tools\google_dynamic_remarketing($this->registry));
            $this->registry->set('GoogleReviews', new google_marketing_tools\google_reviews($this->registry));
            $this->registry->set('GoogleEnhancedEcommerce', new google_marketing_tools\google_enhanced_ecommerce($this->registry));
            $this->registry->set('Criteo', new google_marketing_tools\criteo($this->registry));
            $this->registry->set('Gdpr', new google_marketing_tools\gdpr($this->registry));
            $this->registry->set('AbandonedCart', new google_marketing_tools\abandoned_cart($this->registry));
            if ($this->config->get("google_ac_status_" . $this->config->get('config_store_id')))
                $this->AbandonedCart->restore_cart_products();
        } elseif (version_compare(VERSION, '2', '>=')) {
            $registry->set('GMTMaster', new google_marketing_tools\master($registry));
            $registry->set('GoogleTagManager', new google_marketing_tools\google_tag_manager($registry));
            $registry->set('DataLayer', new google_marketing_tools\data_layer($registry));
            $registry->set('FacebookPixel', new google_marketing_tools\facebook_pixel($registry));
            $registry->set('GoogleDynamicRemarketing', new google_marketing_tools\google_dynamic_remarketing($registry));
            $registry->set('GoogleReviews', new google_marketing_tools\google_reviews($registry));
            $registry->set('GoogleEnhancedEcommerce', new google_marketing_tools\google_enhanced_ecommerce($registry));
            $registry->set('Criteo', new google_marketing_tools\criteo($registry));
            $registry->set('Gdpr', new google_marketing_tools\gdpr($registry));
            $registry->set('AbandonedCart', new google_marketing_tools\abandoned_cart($registry));
            if ($config->get("google_ac_status_" . $config->get('config_store_id'))) {
                $AbandonedCart = new google_marketing_tools\abandoned_cart($registry);
                $AbandonedCart->restore_cart_products();
            }
        } else {
            include_once(DIR_SYSTEM . 'library/google_marketing_tools/master.php');
            $registry->set('GMTMaster', new google_marketing_tools\master($registry));

            include_once(DIR_SYSTEM . 'library/google_marketing_tools/google_tag_manager.php');
            $registry->set('GoogleTagManager', new google_marketing_tools\google_tag_manager($registry));

            include_once(DIR_SYSTEM . 'library/google_marketing_tools/data_layer.php');
            $registry->set('DataLayer', new google_marketing_tools\data_layer($registry));

            include_once(DIR_SYSTEM . 'library/google_marketing_tools/facebook_pixel.php');
            $registry->set('FacebookPixel', new google_marketing_tools\facebook_pixel($registry));

            include_once(DIR_SYSTEM . 'library/google_marketing_tools/google_dynamic_remarketing.php');
            $registry->set('GoogleDynamicRemarketing', new google_marketing_tools\google_dynamic_remarketing($registry));

            include_once(DIR_SYSTEM . 'library/google_marketing_tools/google_reviews.php');
            $registry->set('GoogleReviews', new google_marketing_tools\google_reviews($registry));

            include_once(DIR_SYSTEM . 'library/google_marketing_tools/google_enhanced_ecommerce.php');
            $registry->set('GoogleEnhancedEcommerce', new google_marketing_tools\google_enhanced_ecommerce($registry));

            include_once(DIR_SYSTEM . 'library/google_marketing_tools/criteo.php');
            $registry->set('Criteo', new google_marketing_tools\criteo($registry));

            include_once(DIR_SYSTEM . 'library/google_marketing_tools/gdpr.php');
            $registry->set('Gdpr', new google_marketing_tools\gdpr($registry));

            include_once(DIR_SYSTEM . 'library/google_marketing_tools/abandoned_cart.php');
            $registry->set('AbandonedCart', new google_marketing_tools\abandoned_cart($registry));
            if($config->get("google_ac_status_".$config->get('config_store_id'))) {
                $AbandonedCart = $registry->get('AbandonedCart');
                $AbandonedCart->restore_cart_products();
            }
        }
    }
?>