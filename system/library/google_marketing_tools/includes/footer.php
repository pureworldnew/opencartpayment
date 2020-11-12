<?php
    if($this->GMTMaster && $this->GMTMaster->gmt_is_enabled()) {
        $data_gmt = $this->GMTMaster->get_data();

        if(array_key_exists('order_id', $data_gmt) && $this->GMTMaster->get_current_view() == 'purchase') {
            $order_id = $data_gmt['order_id'];
            $valid_order_status = $this->GMTMaster->validate_order_status($order_id);

            if($valid_order_status) {
                $order_info = $this->GMTMaster->getOrder($order_id);
                $order_info['totals'] = $this->GMTMaster->getOrderTotals($order_id);
                $order_info['products'] = $this->GMTMaster->getOrderProducts($order_id);
                $this->GMTMaster->set_script('end_body', $this->GoogleReviews->get_reviews_badge_code());
                $this->GMTMaster->set_script('end_body', $this->GoogleReviews->get_google_reviews_code($order_info));
            }
        }

        $data_to_view['end_body'] = $this->GMTMaster->get_scripts('end_body');

        foreach ($data_to_view as $data_name => $value) {
            if(version_compare(VERSION, '2.0.0.0', '>='))
                $data[$data_name] = $value;
            else
                $this->data[$data_name] = $value;
        }

    }
?>