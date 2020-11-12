<?php

class ControllerFeedUksbGoogleMerchant extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('feed/uksb_google_merchant');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('uksb_google_merchant', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');
        $this->data['text_model'] = $this->language->get('text_model');
        $this->data['text_location'] = $this->language->get('text_location');
        $this->data['text_gtin'] = $this->language->get('text_gtin');
        $this->data['text_mpn'] = $this->language->get('text_mpn');
        $this->data['text_req_mpn'] = $this->language->get('text_req_mpn');
        $this->data['text_req_gtin'] = $this->language->get('text_req_gtin');
        $this->data['text_req_brand'] = $this->language->get('text_req_brand');
        $this->data['text_condition_new'] = $this->language->get('text_condition_new');
        $this->data['text_condition_used'] = $this->language->get('text_condition_used');
        $this->data['text_condition_ref'] = $this->language->get('text_condition_ref');
        $this->data['text_sku'] = $this->language->get('text_sku');
        $this->data['text_upc'] = $this->language->get('text_upc');
        $this->data['text_price_inc_tax'] = $this->language->get('text_price_inc_tax');
        $this->data['text_price_exc_tax'] = $this->language->get('text_price_exc_tax');

        $this->data['tab_general_settings'] = $this->language->get('tab_general_settings');
        $this->data['tab_google_settings'] = $this->language->get('tab_google_settings');
        $this->data['tab_google_feeds'] = $this->language->get('tab_google_feeds');
        $this->data['tab_bing_feeds'] = $this->language->get('tab_bing_feeds');
        $this->data['tab_ciao_feeds'] = $this->language->get('tab_ciao_feeds');
        $this->data['tab_thefind_feeds'] = $this->language->get('tab_thefind_feeds');
        $this->data['tab_pricegrabber_yahoo_feeds'] = $this->language->get('tab_pricegrabber_yahoo_feeds');
        $this->data['tab_nextag_feeds'] = $this->language->get('tab_nextag_feeds');

        $this->data['entry_status'] = $this->language->get('entry_status');
        $this->data['entry_mpn'] = $this->language->get('entry_mpn');
        $this->data['entry_condition'] = $this->language->get('entry_condition');
        $this->data['entry_history_days'] = $this->language->get('entry_history_days');
        $this->data['entry_gtin'] = $this->language->get('entry_gtin');
        $this->data['entry_required_attributes'] = $this->language->get('entry_required_attributes');
        $this->data['entry_characters'] = $this->language->get('entry_characters');
        $this->data['entry_split'] = $this->language->get('entry_split');
        $this->data['entry_fullpath'] = $this->language->get('entry_fullpath');
        $this->data['entry_site'] = $this->language->get('entry_site');
        $this->data['entry_google_category'] = $this->language->get('entry_google_category');
        $this->data['entry_choose_google_category'] = $this->language->get('entry_choose_google_category');
        $this->data['entry_choose_google_category_xml'] = $this->language->get('entry_choose_google_category_xml');
        $this->data['entry_info'] = $this->language->get('entry_info');
        $this->data['entry_data_feed'] = $this->language->get('entry_data_feed');
        $this->data['entry_xml_file'] = $this->language->get('entry_xml_file');

        $this->data['help_brand'] = $this->language->get('help_brand');
        $this->data['help_mpn'] = $this->language->get('help_mpn');
        $this->data['help_condition'] = $this->language->get('help_condition');
        $this->data['help_history_days'] = $this->language->get('help_history_days');
        $this->data['help_gtin'] = $this->language->get('help_gtin');
        $this->data['help_required_attributes'] = $this->language->get('help_required_attributes');
        $this->data['help_characters'] = $this->language->get('help_characters');
        $this->data['help_split'] = $this->language->get('help_split');
        $this->data['help_fullpath'] = $this->language->get('help_fullpath');
        $this->data['help_split_help'] = $this->language->get('help_split_help');
        $this->data['help_site'] = $this->language->get('help_site');
        $this->data['help_google_category'] = $this->language->get('help_google_category');
        $this->data['help_info'] = $this->language->get('help_info');

        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');

        $this->data['tab_general'] = $this->language->get('tab_general');
        $this->data['store_name'] = $this->config->get('config_name');

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->error['duplicate'])) {
            $this->data['error_duplicate'] = $this->error['duplicate'];
        } else {
            $this->data['error_duplicate'] = '';
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_feed'),
            'href' => $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('feed/uksb_google_merchant', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['action'] = $this->url->link('feed/uksb_google_merchant', 'token=' . $this->session->data['token'], 'SSL');

        $this->data['cancel'] = $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->post['uksb_google_merchant_status'])) {
            $this->data['uksb_google_merchant_status'] = $this->request->post['uksb_google_merchant_status'];
        } else {
            $this->data['uksb_google_merchant_status'] = $this->config->get('uksb_google_merchant_status');
        }

        if (isset($this->request->post['uksb_google_merchant_mpn'])) {
            $this->data['uksb_google_merchant_mpn'] = $this->request->post['uksb_google_merchant_mpn'];
        } else {
            $this->data['uksb_google_merchant_mpn'] = $this->config->get('uksb_google_merchant_mpn');
        }

        if (isset($this->request->post['uksb_google_merchant_condition'])) {
            $this->data['uksb_google_merchant_condition'] = $this->request->post['uksb_google_merchant_condition'];
        } else {
            $this->data['uksb_google_merchant_condition'] = $this->config->get('uksb_google_merchant_condition');
        }

        if (isset($this->request->post['uksb_google_merchant_gtin'])) {
            $this->data['uksb_google_merchant_gtin'] = $this->request->post['uksb_google_merchant_gtin'];
        } else {
            $this->data['uksb_google_merchant_gtin'] = $this->config->get('uksb_google_merchant_gtin');
        }

        if (isset($this->request->post['uksb_google_merchant_required_mpn'])) {
            $this->data['uksb_google_merchant_required_mpn'] = $this->request->post['uksb_google_merchant_required_mpn'];
        } else {
            $this->data['uksb_google_merchant_required_mpn'] = $this->config->get('uksb_google_merchant_required_mpn');
        }

        if (isset($this->request->post['uksb_google_merchant_required_gtin'])) {
            $this->data['uksb_google_merchant_required_gtin'] = $this->request->post['uksb_google_merchant_required_gtin'];
        } else {
            $this->data['uksb_google_merchant_required_gtin'] = $this->config->get('uksb_google_merchant_required_gtin');
        }

        if (isset($this->request->post['uksb_google_merchant_required_brand'])) {
            $this->data['uksb_google_merchant_required_brand'] = $this->request->post['uksb_google_merchant_required_brand'];
        } else {
            $this->data['uksb_google_merchant_required_brand'] = $this->config->get('uksb_google_merchant_required_brand');
        }

        if (isset($this->request->post['uksb_google_merchant_characters'])) {
            $this->data['uksb_google_merchant_characters'] = $this->request->post['uksb_google_merchant_characters'];
        } else {
            $this->data['uksb_google_merchant_characters'] = $this->config->get('uksb_google_merchant_characters');
        }

        if (isset($this->request->post['uksb_google_merchant_split'])) {
            $this->data['uksb_google_merchant_split'] = $this->request->post['uksb_google_merchant_split'];
        } else {
            $this->data['uksb_google_merchant_split'] = $this->config->get('uksb_google_merchant_split');
        }

        if (isset($this->request->post['uksb_google_merchant_fullpath'])) {
            $this->data['uksb_google_merchant_fullpath'] = $this->request->post['uksb_google_merchant_fullpath'];
        } else {
            $this->data['uksb_google_merchant_fullpath'] = $this->config->get('uksb_google_merchant_fullpath');
        }

        if (isset($this->request->post['uksb_google_merchant_history_days'])) {
            $this->data['uksb_google_merchant_history_days'] = $this->request->post['uksb_google_merchant_history_days'];
        } else {
            $this->data['uksb_google_merchant_history_days'] = $this->config->get('uksb_google_merchant_history_days');
        }

        if (isset($this->request->post['uksb_google_merchant_google_category_gb'])) {
            $this->data['uksb_google_merchant_google_category_gb'] = $this->request->post['uksb_google_merchant_google_category_gb'];
            $this->data['uksb_google_merchant_tax_gb'] = $this->request->post['uksb_google_merchant_tax_gb'];
            $this->data['uksb_google_merchant_google_category_us'] = $this->request->post['uksb_google_merchant_google_category_us'];
            $this->data['uksb_google_merchant_google_category_au'] = $this->request->post['uksb_google_merchant_google_category_au'];
            $this->data['uksb_google_merchant_tax_au'] = $this->request->post['uksb_google_merchant_tax_au'];
            $this->data['uksb_google_merchant_google_category_fr'] = $this->request->post['uksb_google_merchant_google_category_fr'];
            $this->data['uksb_google_merchant_tax_fr'] = $this->request->post['uksb_google_merchant_tax_fr'];
            $this->data['uksb_google_merchant_google_category_de'] = $this->request->post['uksb_google_merchant_google_category_de'];
            $this->data['uksb_google_merchant_tax_de'] = $this->request->post['uksb_google_merchant_tax_de'];
            $this->data['uksb_google_merchant_google_category_it'] = $this->request->post['uksb_google_merchant_google_category_it'];
            $this->data['uksb_google_merchant_tax_it'] = $this->request->post['uksb_google_merchant_tax_it'];
            $this->data['uksb_google_merchant_google_category_nl'] = $this->request->post['uksb_google_merchant_google_category_nl'];
            $this->data['uksb_google_merchant_tax_nl'] = $this->request->post['uksb_google_merchant_tax_nl'];
            $this->data['uksb_google_merchant_google_category_es'] = $this->request->post['uksb_google_merchant_google_category_es'];
            $this->data['uksb_google_merchant_tax_es'] = $this->request->post['uksb_google_merchant_tax_es'];
        } else {
            $this->data['uksb_google_merchant_google_category_gb'] = $this->config->get('uksb_google_merchant_google_category_gb');
            $this->data['uksb_google_merchant_tax_gb'] = $this->config->get('uksb_google_merchant_tax_gb');
            $this->data['uksb_google_merchant_google_category_us'] = $this->config->get('uksb_google_merchant_google_category_us');
            $this->data['uksb_google_merchant_google_category_au'] = $this->config->get('uksb_google_merchant_google_category_au');
            $this->data['uksb_google_merchant_tax_au'] = $this->config->get('uksb_google_merchant_tax_au');
            $this->data['uksb_google_merchant_google_category_fr'] = $this->config->get('uksb_google_merchant_google_category_fr');
            $this->data['uksb_google_merchant_tax_fr'] = $this->config->get('uksb_google_merchant_tax_fr');
            $this->data['uksb_google_merchant_google_category_de'] = $this->config->get('uksb_google_merchant_google_category_de');
            $this->data['uksb_google_merchant_tax_de'] = $this->config->get('uksb_google_merchant_tax_de');
            $this->data['uksb_google_merchant_google_category_it'] = $this->config->get('uksb_google_merchant_google_category_it');
            $this->data['uksb_google_merchant_tax_it'] = $this->config->get('uksb_google_merchant_tax_it');
            $this->data['uksb_google_merchant_google_category_nl'] = $this->config->get('uksb_google_merchant_google_category_nl');
            $this->data['uksb_google_merchant_tax_nl'] = $this->config->get('uksb_google_merchant_tax_nl');
            $this->data['uksb_google_merchant_google_category_es'] = $this->config->get('uksb_google_merchant_google_category_es');
            $this->data['uksb_google_merchant_tax_es'] = $this->config->get('uksb_google_merchant_tax_es');
        }

        $this->load->model('feed/uksb_google_merchant');

        $store_name = $this->config->get('config_name');
        $this->data['data_feed'] = '';
        $this->data['xml_url'] = '';
        $this->data['data_bingfeed'] = '';
        if ($this->config->get('uksb_google_merchant_split') > 0) {
            $split = $this->config->get('uksb_google_merchant_split');
            $totalproducts = $this->model_feed_uksb_google_merchant->getTotalProductsByStore(0);
            if ($totalproducts > $split) {
                $j = floor($totalproducts / $split);
                $rem = $totalproducts - ($j * $split);
                for ($i = 1; $i <= $j; $i++) {
                    $from = (($i - 1) * $split) + 1;
                    $to = $i * $split;
                    $this->data['xml_url'] .=($i > 1 ? '^' : '') . HTTP_CATALOG . "download/productgooglefeed" . str_replace(' ', '', $store_name) . $from . '-' . $to . ".xml";
                    $xml_file = DIR_DOWNLOAD . "productgooglefeed" . str_replace(' ', '', $store_name) . $from . '-' . $to . ".xml";
                    $this->data['xml_file_time'][] = (file_exists($xml_file)) ? date('Y-m-d',filemtime($xml_file)) : 'NA';
                    $this->data['data_feed'] .= ($i > 1 ? '^' : '') . HTTP_CATALOG . 'index.php?route=feed/uksb_google_merchant&send=' . $from . '-' . $to;
                    $this->data['data_bingfeed'] .= ($i > 1 ? '^' : '') . HTTP_CATALOG . 'index.php?route=feed/uksb_bing_shopping&send=' . $from . '-' . $to;
                }
                if ($rem > 0) {
                    $this->data['xml_url'] .='^' . HTTP_CATALOG . "download/productgooglefeed" . str_replace(' ', '', $store_name) . ($to + 1) . '-' . ($to + $rem) . ".xml";
                    $xml_file = DIR_DOWNLOAD . "productgooglefeed" . str_replace(' ', '', $store_name) . ($to + 1) . '-' . ($to + $rem) . ".xml";
                    $this->data['xml_file_time'][] = (file_exists($xml_file)) ? date('Y-m-d',filemtime($xml_file)) : 'NA';
                    $this->data['data_feed'] .= '^' . HTTP_CATALOG . 'index.php?route=feed/uksb_google_merchant&send=' . ($to + 1) . '-' . ($to + $rem);
                    $this->data['data_bingfeed'] .= '^' . HTTP_CATALOG . 'index.php?route=feed/uksb_bing_shopping&send=' . ($to + 1) . '-' . ($to + $rem);
                }
            } else {
                $this->data['xml_url'] = HTTP_CATALOG . "download/productgooglefeed" . str_replace(' ', '', $store_name) . ".xml";
                $xml_file = DIR_DOWNLOAD . "productgooglefeed" . str_replace(' ', '', $store_name) . ".xml";
                $this->data['xml_file_time'][] = (file_exists($xml_file)) ? date('Y-m-d',filemtime($xml_file)) : 'NA';
                $this->data['data_feed'] = HTTP_CATALOG . 'index.php?route=feed/uksb_google_merchant';
                $this->data['data_bingfeed'] = HTTP_CATALOG . 'index.php?route=feed/uksb_bing_shopping';
            }
        } else {
            $this->data['xml_url'] = HTTP_CATALOG . "download/productgooglefeed" . str_replace(' ', '', $store_name) . ".xml";
            $xml_file = DIR_DOWNLOAD . "productgooglefeed" . str_replace(' ', '', $store_name) . ".xml";
            $this->data['xml_file_time'][] = (file_exists($xml_file)) ? date('Y-m-d',filemtime($xml_file)) : 'NA';
            $this->data['data_feed'] = HTTP_CATALOG . 'index.php?route=feed/uksb_google_merchant';
            $this->data['data_bingfeed'] = HTTP_CATALOG . 'index.php?route=feed/uksb_bing_shopping';
        }
        $this->load->model('setting/store');
        if ($this->model_setting_store->getTotalStores() > 0) {
            $stores = $this->model_setting_store->getStores();
            $stores = array_reverse($stores);

            foreach ($stores as $store) {
                $store_name = $store['name'];
                if ($this->config->get('uksb_google_merchant_split') > 0) {
                    $split = $this->config->get('uksb_google_merchant_split');
                    $totalproducts = $this->model_feed_uksb_google_merchant->getTotalProductsByStore($store['store_id']);
                    if ($totalproducts > $split) {
                        $j = floor($totalproducts / $split);
                        $rem = $totalproducts - ($j * $split);
                        for ($i = 1; $i <= $j; $i++) {
                            $from = (($i - 1) * $split) + 1;
                            $to = $i * $split;
                            $this->data['xml_url'] .= '^' . HTTP_CATALOG . "download/productgooglefeed" . str_replace(' ', '', $store_name) . $from . '-' . $to . ".xml";
                            $xml_file = DIR_DOWNLOAD . "productgooglefeed" . str_replace(' ', '', $store_name) . $from . '-' . $to . ".xml";
                            $this->data['xml_file_time'][] = (file_exists($xml_file)) ? date('Y-m-d',filemtime($xml_file)) : 'NA';
                            $this->data['data_feed'] .= '^' . $store['url'] . 'index.php?route=feed/uksb_google_merchant&send=' . $from . '-' . $to;
                            $this->data['data_bingfeed'] .= '^' . $store['url'] . 'index.php?route=feed/uksb_bing_shopping&send=' . $from . '-' . $to;
                        }
                        if ($rem > 0) {
                            $this->data['xml_url'] .= '^' . HTTP_CATALOG . "download/productgooglefeed" . str_replace(' ', '', $store_name) . ($to + 1) . '-' . ($to + $rem) . ".xml";
                            $xml_file = DIR_DOWNLOAD . "productgooglefeed" . str_replace(' ', '', $store_name) . ($to + 1) . '-' . ($to + $rem) . ".xml";
                            $this->data['xml_file_time'][] = (file_exists($xml_file)) ? date('Y-m-d',filemtime($xml_file)) : 'NA';
                            $this->data['data_feed'] .= '^' . $store['url'] . 'index.php?route=feed/uksb_google_merchant&send=' . ($to + 1) . '-' . ($to + $rem);
                            $this->data['data_bingfeed'] .= '^' . $store['url'] . 'index.php?route=feed/uksb_bing_shopping&send=' . ($to + 1) . '-' . ($to + $rem);
                        }
                    } else {
                        $this->data['xml_url'] .= '^' . HTTP_CATALOG . "download/productgooglefeed" . str_replace(' ', '', $store_name) . ".xml";
                        $xml_file = DIR_DOWNLOAD . "productgooglefeed" . str_replace(' ', '', $store_name) . ".xml";
                        $this->data['xml_file_time'][] = (file_exists($xml_file)) ? date('Y-m-d',filemtime($xml_file)) : 'NA';
                        $this->data['data_feed'] .= '^' . $store['url'] . 'index.php?route=feed/uksb_google_merchant';
                        $this->data['data_bingfeed'] .= '^' . $store['url'] . 'index.php?route=feed/uksb_bing_shopping';
                    }
                } else {
                    $this->data['xml_url'] .= '^' . HTTP_CATALOG . "download/productgooglefeed" . str_replace(' ', '', $store_name) . ".xml";
                    $xml_file = DIR_DOWNLOAD . "productgooglefeed" . str_replace(' ', '', $store_name) . ".xml";
                    $this->data['xml_file_time'][] = (file_exists($xml_file)) ? date('Y-m-d',filemtime($xml_file)) : 'NA';
                    $this->data['data_feed'] .= '^' . $store['url'] . 'index.php?route=feed/uksb_google_merchant';
                    $this->data['data_bingfeed'] .= '^' . $store['url'] . 'index.php?route=feed/uksb_bing_shopping';
                }
            }
        }
        $this->template = 'feed/uksb_google_merchant.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

    private function validate() {
        if (!$this->user->hasPermission('modify', 'feed/uksb_google_merchant')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (isset($this->request->post['uksb_google_merchant_status']) && $this->request->post['uksb_google_merchant_mpn'] == $this->request->post['uksb_google_merchant_gtin']) {
            $this->error['duplicate'] = $this->language->get('error_duplicate');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    public function install() {
        $query = $this->db->query("DESC `" . DB_PREFIX . "product` `upc`");
        if ($query->num_rows) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` CHANGE `upc` `upc` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_bin");
        }

        $query = $this->db->query("DESC `" . DB_PREFIX . "product` `mpn`");
        if (!$query->num_rows) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `mpn` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_bin AFTER `minimum`");
        }

        $query = $this->db->query("DESC `" . DB_PREFIX . "product` `gtin`");
        if (!$query->num_rows) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `gtin` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_bin AFTER `mpn`");
        }

        $query = $this->db->query("DESC `" . DB_PREFIX . "product` `google_category_gb`");
        if (!$query->num_rows) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `google_category_gb` VARCHAR ( 255 ) CHARACTER SET utf8 COLLATE utf8_bin AFTER `gtin`");
        }

        $query = $this->db->query("DESC `" . DB_PREFIX . "product` `google_category_us`");
        if (!$query->num_rows) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `google_category_us` VARCHAR ( 255 ) CHARACTER SET utf8 COLLATE utf8_bin AFTER `google_category_gb`");
        }

        $query = $this->db->query("DESC `" . DB_PREFIX . "product` `google_category_au`");
        if (!$query->num_rows) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `google_category_au` VARCHAR ( 255 ) CHARACTER SET utf8 COLLATE utf8_bin AFTER `google_category_us`");
        }

        $query = $this->db->query("DESC `" . DB_PREFIX . "product` `google_category_fr`");
        if (!$query->num_rows) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `google_category_fr` VARCHAR ( 255 ) CHARACTER SET utf8 COLLATE utf8_bin AFTER `google_category_au`");
        }

        $query = $this->db->query("DESC `" . DB_PREFIX . "product` `google_category_de`");
        if (!$query->num_rows) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `google_category_de` VARCHAR ( 255 ) CHARACTER SET utf8 COLLATE utf8_bin AFTER `google_category_fr`");
        }

        $query = $this->db->query("DESC `" . DB_PREFIX . "product` `google_category_it`");
        if (!$query->num_rows) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `google_category_it` VARCHAR ( 255 ) CHARACTER SET utf8 COLLATE utf8_bin AFTER `google_category_de`");
        }

        $query = $this->db->query("DESC `" . DB_PREFIX . "product` `google_category_nl`");
        if (!$query->num_rows) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `google_category_nl` VARCHAR ( 255 ) CHARACTER SET utf8 COLLATE utf8_bin AFTER `google_category_it`");
        }

        $query = $this->db->query("DESC `" . DB_PREFIX . "product` `google_category_es`");
        if (!$query->num_rows) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `google_category_es` VARCHAR ( 255 ) CHARACTER SET utf8 COLLATE utf8_bin AFTER `google_category_nl`");
        }

        $query = $this->db->query("DESC `" . DB_PREFIX . "product` `google_category`");
        if ($query->num_rows) {
            $this->db->query("UPDATE `" . DB_PREFIX . "product` SET `google_category_gb` = `google_category`, `google_category_us` = `google_category`, `google_category_au` = `google_category`, `google_category_fr` = `google_category`, `google_category_de` = `google_category`, `google_category_it` = `google_category`, `google_category_nl` = `google_category`, `google_category_es` = `google_category`");
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` DROP `google_category`");
        }

        $query = $this->db->query("DESC `" . DB_PREFIX . "product` `gender`");
        if (!$query->num_rows) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `gender` CHAR( 6 ) CHARACTER SET utf8 COLLATE utf8_bin AFTER `google_category_es`");
        }

        $query = $this->db->query("DESC `" . DB_PREFIX . "product` `age_group`");
        if (!$query->num_rows) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `age_group` CHAR( 6 ) CHARACTER SET utf8 COLLATE utf8_bin AFTER `gender`");
        }

        $query = $this->db->query("DESC `" . DB_PREFIX . "product` `colour`");
        if (!$query->num_rows) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `colour` TEXT CHARACTER SET utf8 COLLATE utf8_bin AFTER `age_group`");
        } else {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` CHANGE `colour` `colour` TEXT CHARACTER SET utf8 COLLATE utf8_bin");
        }

        $query = $this->db->query("DESC `" . DB_PREFIX . "product` `size`");
        if (!$query->num_rows) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `size` TEXT CHARACTER SET utf8 COLLATE utf8_bin AFTER `colour`");
        } else {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` CHANGE `size` `size` TEXT CHARACTER SET utf8 COLLATE utf8_bin");
        }

        $query = $this->db->query("DESC `" . DB_PREFIX . "product` `material`");
        if (!$query->num_rows) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `material` TEXT CHARACTER SET utf8 COLLATE utf8_bin AFTER `size`");
        }

        $query = $this->db->query("DESC `" . DB_PREFIX . "product` `pattern`");
        if (!$query->num_rows) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `pattern` TEXT CHARACTER SET utf8 COLLATE utf8_bin AFTER `material`");
        }

        $query = $this->db->query("DESC `" . DB_PREFIX . "product` `gcondition`");
        if (!$query->num_rows) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `gcondition` VARCHAR( 11 ) CHARACTER SET utf8 COLLATE utf8_bin AFTER `pattern`");
        }

        $query = $this->db->query("DESC `" . DB_PREFIX . "product` `brand`");
        if (!$query->num_rows) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `brand` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_bin AFTER `gcondition`");
        }

        $query = $this->db->query("DESC `" . DB_PREFIX . "product` `vmpn`");
        if (!$query->num_rows) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `vmpn` TEXT CHARACTER SET utf8 COLLATE utf8_bin AFTER `brand`");
        }

        $query = $this->db->query("DESC `" . DB_PREFIX . "product` `vgtin`");
        if (!$query->num_rows) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `vgtin` TEXT CHARACTER SET utf8 COLLATE utf8_bin AFTER `vmpn`");
        }

        $query = $this->db->query("DESC `" . DB_PREFIX . "product` `vprices`");
        if (!$query->num_rows) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `vprices` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_bin AFTER `vgtin`");
        }

        $query = $this->db->query("DESC `" . DB_PREFIX . "product` `ongoogle`");
        if (!$query->num_rows) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `ongoogle` TINYINT( 1 ) DEFAULT '1' NOT NULL AFTER `vprices`");
        }

        $query = $this->db->query("DESC `" . DB_PREFIX . "product` `google_adwords_grouping`");
        if (!$query->num_rows) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `google_adwords_grouping` VARCHAR ( 255 ) CHARACTER SET utf8 COLLATE utf8_bin AFTER `vgtin`");
        }

        $query = $this->db->query("DESC `" . DB_PREFIX . "product` `google_adwords_labels`");
        if (!$query->num_rows) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `google_adwords_labels` TEXT CHARACTER SET utf8 COLLATE utf8_bin AFTER `google_adwords_grouping`");
        }

        $query = $this->db->query("DESC `" . DB_PREFIX . "product` `google_adwords_publish`");
        if (!$query->num_rows) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `google_adwords_publish` TINYINT( 1 ) DEFAULT '1' NOT NULL AFTER `google_adwords_labels`");
        }

        $query = $this->db->query("DESC `" . DB_PREFIX . "product` `google_adwords_redirect`");
        if (!$query->num_rows) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `google_adwords_redirect` VARCHAR ( 255 ) CHARACTER SET utf8 COLLATE utf8_bin AFTER `google_adwords_publish`");
        }

        $query = $this->db->query("DESC `" . DB_PREFIX . "product` `google_adwords_queryparam`");
        if (!$query->num_rows) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `google_adwords_queryparam` VARCHAR ( 255 ) CHARACTER SET utf8 COLLATE utf8_bin AFTER `google_adwords_redirect`");
        }

        $query = $this->db->query("DESC `" . DB_PREFIX . "category` `google_category_gb`");
        if (!$query->num_rows) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "category` ADD `google_category_gb` VARCHAR ( 255 ) CHARACTER SET utf8 COLLATE utf8_bin");
        }

        $query = $this->db->query("DESC `" . DB_PREFIX . "category` `google_category_us`");
        if (!$query->num_rows) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "category` ADD `google_category_us` VARCHAR ( 255 ) CHARACTER SET utf8 COLLATE utf8_bin");
        }

        $query = $this->db->query("DESC `" . DB_PREFIX . "category` `google_category_au`");
        if (!$query->num_rows) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "category` ADD `google_category_au` VARCHAR ( 255 ) CHARACTER SET utf8 COLLATE utf8_bin");
        }

        $query = $this->db->query("DESC `" . DB_PREFIX . "category` `google_category_fr`");
        if (!$query->num_rows) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "category` ADD `google_category_fr` VARCHAR ( 255 ) CHARACTER SET utf8 COLLATE utf8_bin");
        }

        $query = $this->db->query("DESC `" . DB_PREFIX . "category` `google_category_de`");
        if (!$query->num_rows) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "category` ADD `google_category_de` VARCHAR ( 255 ) CHARACTER SET utf8 COLLATE utf8_bin");
        }

        $query = $this->db->query("DESC `" . DB_PREFIX . "category` `google_category_it`");
        if (!$query->num_rows) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "category` ADD `google_category_it` VARCHAR ( 255 ) CHARACTER SET utf8 COLLATE utf8_bin");
        }

        $query = $this->db->query("DESC `" . DB_PREFIX . "category` `google_category_nl`");
        if (!$query->num_rows) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "category` ADD `google_category_nl` VARCHAR ( 255 ) CHARACTER SET utf8 COLLATE utf8_bin");
        }

        $query = $this->db->query("DESC `" . DB_PREFIX . "category` `google_category_es`");
        if (!$query->num_rows) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "category` ADD `google_category_es` VARCHAR ( 255 ) CHARACTER SET utf8 COLLATE utf8_bin");
        }

        $this->db->query("INSERT INTO `" . DB_PREFIX . "setting` VALUES (NULL, 0, 'uksb_google_merchant', 'uksb_google_merchant_tax_gb', 1, 0)");

        $this->db->query("INSERT INTO `" . DB_PREFIX . "setting` VALUES (NULL, 0, 'uksb_google_merchant', 'uksb_google_merchant_tax_au', 1, 0)");

        $this->db->query("INSERT INTO `" . DB_PREFIX . "setting` VALUES (NULL, 0, 'uksb_google_merchant', 'uksb_google_merchant_tax_fr', 1, 0)");

        $this->db->query("INSERT INTO `" . DB_PREFIX . "setting` VALUES (NULL, 0, 'uksb_google_merchant', 'uksb_google_merchant_tax_de', 1, 0)");

        $this->db->query("INSERT INTO `" . DB_PREFIX . "setting` VALUES (NULL, 0, 'uksb_google_merchant', 'uksb_google_merchant_tax_it', 1, 0)");

        $this->db->query("INSERT INTO `" . DB_PREFIX . "setting` VALUES (NULL, 0, 'uksb_google_merchant', 'uksb_google_merchant_tax_nl', 1, 0)");

        $this->db->query("INSERT INTO `" . DB_PREFIX . "setting` VALUES (NULL, 0, 'uksb_google_merchant', 'uksb_google_merchant_tax_es', 1, 0)");

        $this->db->query("INSERT INTO `" . DB_PREFIX . "setting` VALUES (NULL, 0, 'uksb_google_merchant', 'uksb_google_merchant_history_days', 60, 0)");
    }

}

?>