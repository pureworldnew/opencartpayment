<?php
    if($this->GMTMaster && $this->GMTMaster->gmt_is_enabled()) {
        $data_gmt = $this->GMTMaster->get_data();

        //<editor-fold desc="Pruchase">
            if(array_key_exists('order_id', $data_gmt) && $this->GMTMaster->get_current_view() == 'purchase') {
                $order_id = $data_gmt['order_id'];
                $valid_order_status = $this->GMTMaster->validate_order_status($order_id);
                $order_info = $this->GMTMaster->getOrder($order_id);

                if ($this->config->get("google_ac_status_" . $this->config->get('config_store_id')))
                    $this->AbandonedCart->unsubscribe($order_info);

                if($valid_order_status) {
                    $order_info['totals'] = $this->GMTMaster->getOrderTotals($order_id);
                    $order_info['products'] = $this->GMTMaster->getOrderProducts($order_id);
                    $data_gmt['order_info'] = $order_info;
                    $this->GMTMaster->set_script('begin_head', $this->DataLayer->set_data_layer_order_success($order_info));
                    $this->GMTMaster->set_script('begin_head', $this->GoogleEnhancedEcommerce->get_measuring_purchase_code($order_info));
                }
            }
        //</editor-fold>

        $this->GMTMaster->set_script('begin_head', $this->FacebookPixel->get_data_view_content_code($data_gmt));
        $this->GMTMaster->set_script('begin_head', $this->GoogleDynamicRemarketing->get_gdr_event());

        $mailchimp_events = $this->AbandonedCart->get_events_code();

        if(!empty($mailchimp_events))
            $this->GMTMaster->set_script('begin_body', $mailchimp_events);

        if($this->Gdpr->is_enabled())
            $this->GMTMaster->set_script('begin_body', $this->Gdpr->get_code_head());

        $this->GMTMaster->set_script('begin_head', $this->GoogleTagManager->get_code_head(), true);

        if($this->Gdpr->check_cookie('gmt_cookie_statistics'))
            $this->GMTMaster->set_script('begin_head', $this->DataLayer->get_gdpr_code('statistics'), true);

        if($this->Gdpr->check_cookie('gmt_cookie_marketing'))
            $this->GMTMaster->set_script('begin_head', $this->DataLayer->get_gdpr_code('marketing'), true);


        $this->GMTMaster->set_script('begin_head', $this->Criteo->get_criteo_params($data_gmt), true);
        $this->GMTMaster->set_script('begin_head', $this->GoogleDynamicRemarketing->get_gdr_params($data_gmt), true);

        $this->GMTMaster->set_script('begin_head', $this->DataLayer->get_data_layer_initialization_code(), true);

        $this->GMTMaster->set_script('begin_body', $this->GoogleTagManager->get_code_body(), true);


        $data_to_view['head_gmt'] = $this->GMTMaster->get_scripts('begin_head');
        $data_to_view['body_gmt'] = $this->GMTMaster->get_scripts('begin_body');

        foreach ($data_to_view as $data_name => $value) {
            if(version_compare(VERSION, '2.0.0.0', '>='))
                $data[$data_name] = $value;
            else
                $this->data[$data_name] = $value;
        }
        $this->document->addScript('catalog/view/javascript/devmanextensions_gmt/data_layer_events.js?v='.$this->GMTMaster->get_gmt_version());

        if($this->Gdpr->is_enabled()) {
            $this->document->addScript('catalog/view/javascript/devmanextensions_gmt/gdpr.js?v='.$this->GMTMaster->get_gmt_version());
            $this->document->addStyle('catalog/view/theme/devmanextensions_gmt/gdpr.css?v='.$this->GMTMaster->get_gmt_version());
        }
    }
?>