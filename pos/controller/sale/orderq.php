<?php

class ControllerSaleOrderq extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('sale/orderq');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/orderq');

        $this->getList();
    }

    public function add() {
        $this->load->language('sale/orderq');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/orderq');

        $this->getForm();
    }

    public function edit() {
        $this->load->language('sale/orderq');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/orderq');

        $this->getForm();
    }

    public function editincoming() {
        $this->load->language('sale/orderq');

        $this->document->setTitle("Edit Incoming Order");

        $this->load->model('sale/orderq');

        $this->getFormForIncoming();
    }

    public function addincoming() {
        $this->load->language('sale/orderq');

        $this->document->setTitle("Add Incoming Order");

        $this->load->model('sale/orderq');

        $this->getFormForIncoming();
    }

    protected function getList() {
        $this->document->addStyle('view/stylesheet/orderquote.css');
        if (isset($this->request->get['filter_order_id'])) {
            $filter_order_id = $this->request->get['filter_order_id'];
        } else {
            $filter_order_id = null;
        }

        if (isset($this->request->get['filter_customer'])) {
            $filter_customer = $this->request->get['filter_customer'];
        } else {
            $filter_customer = null;
        }

        if (isset($this->request->get['filter_order_status'])) {
            $filter_order_status = $this->request->get['filter_order_status'];
        } else {
            $filter_order_status = null;
        }

        if (isset($this->request->get['filter_total'])) {
            $filter_total = $this->request->get['filter_total'];
        } else {
            $filter_total = null;
        }

        $this->load->model('tool/orderq');
        $this->model_tool_orderq->createTable();

        if (isset($this->request->get['filter_date_added'])) {
            $filter_date_added = $this->request->get['filter_date_added'];
        } else {
            $filter_date_added = null;
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $filter_date_modified = $this->request->get['filter_date_modified'];
        } else {
            $filter_date_modified = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'o.order_id';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total=' . $this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('sale/orderq', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

        $data['invoice'] = $this->url->link('sale/order/invoice', 'token=' . $this->session->data['token'], 'SSL');
        $data['shipping'] = $this->url->link('sale/order/shipping', 'token=' . $this->session->data['token'], 'SSL');
        $data['add'] = $this->url->link('sale/orderq/add', 'token=' . $this->session->data['token'], 'SSL');
        $this->session->data['wearesetting'] = 0;

        $data['orders'] = array();

        $filter_data = array(
            'filter_order_id' => $filter_order_id,
            'filter_customer' => $filter_customer,
            'filter_order_status' => $filter_order_status,
            'filter_total' => $filter_total,
            'filter_date_added' => $filter_date_added,
            'filter_date_modified' => $filter_date_modified,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $order_total = $this->model_sale_orderq->getTotalOrders($filter_data);

        $results = $this->model_sale_orderq->getOrders($filter_data);

        foreach ($results as $result) {
            $data['orders'][] = array(
                'order_id' => $result['order_id'],
                'customer' => $result['customer'],
                'status' => $result['status'],
                'total' => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
                'shipping_code' => $result['shipping_code'],
                'edit' => $this->url->link('sale/orderq/edit', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
            );
        }

        $data['heading_title'] = $this->language->get('heading_title1');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_missing'] = $this->language->get('text_missing');
        $data['text_loading'] = $this->language->get('text_loading');
        $data['text_version'] = $this->language->get('text_version');

        $data['column_order_id'] = $this->language->get('column_order_id');
        $data['column_customer'] = $this->language->get('column_customer');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_total'] = $this->language->get('column_total');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_date_modified'] = $this->language->get('column_date_modified');
        $data['column_action'] = $this->language->get('column_action');

        $data['entry_return_id'] = $this->language->get('entry_return_id');
        $data['entry_order_id'] = $this->language->get('entry_order_id');
        $data['entry_customer'] = $this->language->get('entry_customer');
        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_total'] = $this->language->get('entry_total');
        $data['entry_date_added'] = $this->language->get('entry_date_added');
        $data['entry_date_modified'] = $this->language->get('entry_date_modified');

        $data['button_invoice_print'] = $this->language->get('button_invoice_print');
        $data['button_shipping_print'] = $this->language->get('button_shipping_print');
        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_view'] = $this->language->get('button_view');
        $data['button_ip_add'] = $this->language->get('button_ip_add');

        $data['token'] = $this->session->data['token'];

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array) $this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        $url = '';

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total=' . $this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
        }

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_order'] = $this->url->link('sale/orderq', 'token=' . $this->session->data['token'] . '&sort=o.order_id' . $url, 'SSL');
        $data['sort_customer'] = $this->url->link('sale/orderq', 'token=' . $this->session->data['token'] . '&sort=customer' . $url, 'SSL');
        $data['sort_status'] = $this->url->link('sale/orderq', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
        $data['sort_total'] = $this->url->link('sale/orderq', 'token=' . $this->session->data['token'] . '&sort=o.total' . $url, 'SSL');
        $data['sort_date_added'] = $this->url->link('sale/orderq', 'token=' . $this->session->data['token'] . '&sort=o.date_added' . $url, 'SSL');
        $data['sort_date_modified'] = $this->url->link('sale/orderq', 'token=' . $this->session->data['token'] . '&sort=o.date_modified' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total=' . $this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $order_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('sale/orderq', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

        $data['filter_order_id'] = $filter_order_id;
        $data['filter_customer'] = $filter_customer;
        $data['filter_order_status'] = $filter_order_status;
        $data['filter_total'] = $filter_total;
        $data['filter_date_added'] = $filter_date_added;
        $data['filter_date_modified'] = $filter_date_modified;

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['store'] = HTTPS_CATALOG;

        // API login
        $this->load->model('user/api');

        $api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

        if ($api_info) {
            $data['api_id'] = $api_info['api_id'];
            $data['api_key'] = $api_info['key'];
            $data['api_ip'] = $this->request->server['REMOTE_ADDR'];
        } else {
            $data['api_id'] = '';
            $data['api_key'] = '';
            $data['api_ip'] = '';
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('sale/orderq_list.tpl', $data));
    }

    public function getForm() {
        if (isset($this->request->get['order_id'])) {
            $this->model_sale_orderq->addOrderShippingIfNotExists($this->request->get['order_id']);
        }
        unset($this->session->data['order_id']);
        unset($this->session->data['refreshproducts']);
        $this->document->addStyle('view/stylesheet/orderquote.css');

        $this->load->model('customer/customer');

        $data['heading_title'] = $this->language->get('heading_title1');

        $data['text_form'] = !isset($this->request->get['order_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_edit_address'] = $this->language->get('text_edit_address');
        $data['text_default'] = $this->language->get('text_default');
        $data['text_select'] = $this->language->get('text_select');
        $data['text_version'] = $this->language->get('text_version');
        $data['text_none'] = $this->language->get('text_none');
        $data['text_guestcustomer'] = $this->language->get('text_guestcustomer');
        $data['text_loading'] = $this->language->get('text_loading');
        $data['text_ip_add'] = sprintf($this->language->get('text_ip_add'), $this->request->server['REMOTE_ADDR']);
        $data['text_product'] = $this->language->get('text_product');
        $data['text_voucher'] = $this->language->get('text_voucher');
        $data['text_order_detail'] = $this->language->get('text_order_detail');
        $data['text_more_detail'] = $this->language->get('text_more_detail');
        $data['text_order_address'] = $this->language->get('text_order_address');
        $data['text_accountdetails'] = $this->language->get('text_accountdetails');
        $data['text_paymentdetails'] = $this->language->get('text_paymentdetails');
        $data['text_shippingdetails'] = $this->language->get('text_shippingdetails');
        $data['text_updatedetails'] = $this->language->get('text_updatedetails');
        $data['text_tosave'] = $this->language->get('text_tosave');
        $data['text_updateded'] = $this->language->get('text_updateded');
        $data['text_details'] = $this->language->get('text_details');
        $data['text_personaldetails_success'] = $this->language->get('text_personaldetails_success');
        $data['text_recalculatetotal'] = $this->language->get('text_recalculatetotal');
        $data['text_savedpdetails'] = $this->language->get('text_savedpdetails');

        $data['text_previous_orders'] = $this->language->get('text_previous_orders');
        $data['text_customtotal'] = $this->language->get('text_customtotal');
        $data['text_customfee'] = $this->language->get('text_customfee');
        $data['text_customtaxes'] = $this->language->get('text_customtaxes');
        $data['text_customamount'] = $this->language->get('text_customamount');
        $data['text_customshipping'] = $this->language->get('text_customshipping');
        $data['text_custompayment'] = $this->language->get('text_custompayment');

        $this->load->model('tool/orderq');
        $this->model_tool_orderq->createTable();

        $data['text_invoice'] = $this->language->get('text_invoice');
        $data['text_order_id'] = $this->language->get('text_order_id');
        $data['text_invoice_no'] = $this->language->get('text_invoice_no');
        $data['text_invoice_date'] = $this->language->get('text_invoice_date');
        $data['text_date_added'] = $this->language->get('text_date_added');
        $data['text_telephone'] = $this->language->get('text_telephone');
        $data['text_fax'] = $this->language->get('text_fax');
        $data['text_email'] = $this->language->get('text_email');
        $data['text_website'] = $this->language->get('text_website');
        $data['text_payment_address'] = $this->language->get('text_payment_address');
        $data['text_shipping_address'] = $this->language->get('text_shipping_address');
        $data['text_payment_method'] = $this->language->get('text_payment_method');
        $data['text_shipping_method'] = $this->language->get('text_shipping_method');
        $data['text_notify'] = $this->language->get('text_notify');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_comment'] = $this->language->get('text_comment');

        $data['entry_store'] = $this->language->get('entry_store');
        $data['entry_customer'] = $this->language->get('entry_customer');
        $data['entry_addnewcustomer'] = $this->language->get('entry_addnewcustomer');
        $data['entry_updatecustomer'] = $this->language->get('entry_updatecustomer');
        $data['entry_latestorders'] = $this->language->get('entry_latestorders');
        $data['entry_refreshcustomer'] = $this->language->get('entry_refreshcustomer');
        $data['entry_customer_group'] = $this->language->get('entry_customer_group');
        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_fax'] = $this->language->get('entry_fax');
        $data['entry_comment'] = $this->language->get('entry_comment');
        $data['entry_affiliate'] = $this->language->get('entry_affiliate');
        $data['entry_address'] = $this->language->get('entry_address');
        $data['entry_newprice'] = $this->language->get('entry_newprice');
        $data['entry_payment_address'] = $this->language->get('entry_payment_address');
        $data['entry_shipping_address'] = $this->language->get('entry_shipping_address');
        $data['text_payment_address'] = $this->language->get('text_payment_address');
        $data['text_shipping_address'] = $this->language->get('text_shipping_address');
        $data['text_address_detail'] = $this->language->get('text_address_detail');
        $data['entry_addproducts'] = $this->language->get('entry_addproducts');
        $data['entry_addvoucher'] = $this->language->get('entry_addvoucher');
        $data['entry_add_address'] = $this->language->get('entry_add_address');
        $data['noaddress_1'] = $this->language->get('noaddress_1');
        $data['noaddress_2'] = $this->language->get('noaddress_2');
        $data['entry_company'] = $this->language->get('entry_company');
        $data['entry_address_1'] = $this->language->get('entry_address_1');
        $data['entry_address_2'] = $this->language->get('entry_address_2');
        $data['entry_city'] = $this->language->get('entry_city');
        $data['entry_postcode'] = $this->language->get('entry_postcode');
        $data['entry_zone'] = $this->language->get('entry_zone');
        $data['entry_zone_code'] = $this->language->get('entry_zone_code');
        $data['entry_country'] = $this->language->get('entry_country');
        $data['entry_product'] = $this->language->get('entry_product');
        $data['entry_filterby'] = $this->language->get('entry_filterby');
        $data['entry_option'] = $this->language->get('entry_option');
        $data['entry_quantity'] = $this->language->get('entry_quantity');
        $data['entry_to_name'] = $this->language->get('entry_to_name');
        $data['entry_to_email'] = $this->language->get('entry_to_email');
        $data['entry_from_name'] = $this->language->get('entry_from_name');
        $data['entry_from_email'] = $this->language->get('entry_from_email');
        $data['entry_theme'] = $this->language->get('entry_theme');
        $data['entry_message'] = $this->language->get('entry_message');
        $data['entry_amount'] = $this->language->get('entry_amount');
        $data['entry_currency'] = $this->language->get('entry_currency');
        $data['entry_shipping_method'] = $this->language->get('entry_shipping_method');
        $data['entry_payment_method'] = $this->language->get('entry_payment_method');
        $data['entry_coupon'] = $this->language->get('entry_coupon');
        $data['entry_voucher'] = $this->language->get('entry_voucher');
        $data['entry_reward'] = $this->language->get('entry_reward');
        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_additionalemail'] = $this->language->get('entry_additionalemail');

        $data['help_additionalemail'] = $this->language->get('help_additionalemail');
        $data['help_customer'] = $this->language->get('help_customer');

        $data['column_image'] = $this->language->get('column_image');
        $data['column_product'] = $this->language->get('column_product');
        $data['column_model'] = $this->language->get('column_model');
        $data['column_quantity'] = $this->language->get('column_quantity');
        $data['column_price'] = $this->language->get('column_price');
        $data['column_newprice'] = $this->language->get('column_newprice');
        $data['column_total'] = $this->language->get('column_total');
        $data['column_action'] = $this->language->get('column_action');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_continue'] = $this->language->get('button_continue');
        $data['button_back'] = $this->language->get('button_back');
        $data['button_refresh'] = $this->language->get('button_refresh');
        $data['button_product_add'] = $this->language->get('button_product_add');
        $data['button_voucher_add'] = $this->language->get('button_voucher_add');
        $data['button_apply'] = $this->language->get('button_apply');
        $data['button_upload'] = $this->language->get('button_upload');
        $data['button_remove'] = $this->language->get('button_remove');
        $data['button_ip_add'] = $this->language->get('button_ip_add');
        $data['button_removecustompayment'] = $this->language->get('button_removecustompayment');
        $data['button_removecustomshipping'] = $this->language->get('button_removecustomshipping');

        $data['tab_order'] = $this->language->get('tab_order');
        $data['tab_customer'] = $this->language->get('tab_customer');
        $data['tab_payment'] = $this->language->get('tab_payment');
        $data['tab_shipping'] = $this->language->get('tab_shipping');
        $data['tab_product'] = $this->language->get('tab_product');
        $data['tab_voucher'] = $this->language->get('tab_voucher');
        $data['tab_total'] = $this->language->get('tab_total');

        $data['token'] = $this->session->data['token'];

        /**
         *
         * @Product Label
         *
         */
        $this->load->language('module/product_labels');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->document->addScript('view/javascript/product_labels/pdfobject.js');
        $this->document->addScript('view/javascript/product_labels/jquery.colorPicker.js');
        $this->document->addScript('view/javascript/product_labels/product_labels.min.js');
        $this->document->addStyle('view/stylesheet/product_labels/stylesheet.css');
        $store_id = 0;

        $this->load->model('module/product_labels');
        $this->load->model('setting/setting');

        $data['settings'] = $this->model_setting_setting->getSetting('product_labels');
        $data['labels'] = $this->model_module_product_labels->getLabels();
        $data['label_templates'] = $this->model_module_product_labels->getLabelTemplates();

        $data['text_template_settings'] = $this->language->get('text_template_settings');
        $data['text_select_template'] = $this->language->get('text_select_template');
        $data['text_preview'] = $this->language->get('text_preview');
        $data['text_page_size'] = $this->language->get('text_page_size');
        $data['text_label_width'] = $this->language->get('text_label_width');
        $data['text_label_height'] = $this->language->get('text_label_height');
        $data['text_labels_hor'] = $this->language->get('text_labels_hor');
        $data['text_labels_ver'] = $this->language->get('text_labels_ver');
        $data['text_spacer_hor'] = $this->language->get('text_spacer_hor');
        $data['text_spacer_ver'] = $this->language->get('text_spacer_ver');
        $data['text_margin_top'] = $this->language->get('text_margin_top');
        $data['text_margin_left'] = $this->language->get('text_margin_left');
        $data['text_rounded'] = $this->language->get('text_rounded');

        $data['text_select_label'] = $this->language->get('text_select_label');
        $data['text_text'] = $this->language->get('text_text');
        $data['text_list'] = $this->language->get('text_list');
        $data['text_img'] = $this->language->get('text_img');
        $data['text_barcode'] = $this->language->get('text_barcode');
        $data['text_addnew'] = $this->language->get('text_addnew');
        $data['text_add'] = $this->language->get('text_add');
        $data['text_font_f'] = $this->language->get('text_font_f');
        $data['text_font_s'] = $this->language->get('text_font_s');
        $data['text_font_a'] = $this->language->get('text_font_a');
        $data['text_xpos'] = $this->language->get('text_xpos');
        $data['text_ypos'] = $this->language->get('text_ypos');
        $data['text_width'] = $this->language->get('text_width');
        $data['text_height'] = $this->language->get('text_height');
        $data['text_color'] = $this->language->get('text_color');
        $data['text_fill'] = $this->language->get('text_fill');
        $data['text_rect'] = $this->language->get('text_rect');
        $data['text_option_delete'] = $this->language->get('text_option_delete');
        $data['text_option_new_template'] = $this->language->get('text_option_new_template');
        $data['text_option_new_label'] = $this->language->get('text_option_new_label');

        $data['text_placeholder_text'] = $this->language->get('text_placeholder_text');
        $data['text_placeholder_img'] = $this->language->get('text_placeholder_text');
        $data['text_placeholder_font_f'] = $this->language->get('text_placeholder_font_f');
        $data['text_placeholder_font_s'] = $this->language->get('text_placeholder_font_s');
        $data['text_placeholder_font_a'] = $this->language->get('text_placeholder_font_a');
        $data['text_placeholder_xpos'] = $this->language->get('text_placeholder_xpos');
        $data['text_placeholder_ypos'] = $this->language->get('text_placeholder_ypos');
        $data['text_placeholder_width'] = $this->language->get('text_placeholder_width');
        $data['text_placeholder_height'] = $this->language->get('text_placeholder_height');
        $data['text_placeholder_color'] = $this->language->get('text_placeholder_color');
        $data['text_placeholder_fill'] = $this->language->get('text_placeholder_fill');

        $data['text_tip_font_f'] = $this->language->get('text_tip_font_f');
        $data['text_tip_font_s'] = $this->language->get('text_tip_font_s');
        $data['text_tip_font_a'] = $this->language->get('text_tip_font_a');
        $data['text_tip_text'] = $this->language->get('text_tip_text');
        $data['text_tip_img'] = $this->language->get('text_tip_img');
        $data['text_tip_xpos'] = $this->language->get('text_tip_xpos');
        $data['text_tip_ypos'] = $this->language->get('text_tip_ypos');
        $data['text_tip_width'] = $this->language->get('text_tip_width');
        $data['text_tip_height'] = $this->language->get('text_tip_height');
        $data['text_tip_color'] = $this->language->get('text_tip_color');
        $data['text_tip_fill'] = $this->language->get('text_tip_fill');

        $data['text_portrait'] = $this->language->get('text_portrait');
        $data['text_landscape'] = $this->language->get('text_landscape');

        $data['error_saveas_template'] = $this->language->get('error_saveas_template');
        $data['error_saveas_label'] = $this->language->get('error_saveas_label');
        $data['error_delete_template'] = $this->language->get('error_delete_template');
        $data['error_delete_label'] = $this->language->get('error_delete_label');
        $data['error_hi_fields'] = $this->language->get('error_hi_fields');
        $data['error_pdf'] = $this->language->get('error_pdf');
        $data['error_nopdf'] = $this->language->get('error_nopdf');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_remove'] = $this->language->get('button_remove');
        $data['button_add'] = $this->language->get('button_add');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_saveas'] = $this->language->get('button_saveas');
        $data['button_preview'] = $this->language->get('button_preview');
        $data['button_printpreview'] = $this->language->get('button_printpreview');

        $data['row'] = 0;
        $data['type'] = "text";
        $data['i'] = 1;

        $data['update_needed'] = $this->language->get('text_update_needed');
        $data['new_version'] = $this->language->get('text_new_version');
        $data['please_update'] = $this->language->get('text_please_update');
        $data['this_version'] = "1.0";

        /**
         *
         * @Product Label
         *
         */
        if (isset($this->session->data['error_upload']) && !empty($this->session->data['error_upload'])) {
            $data['error_upload'] = $this->session->data['error_upload'];
        }
        unset($this->session->data['error_upload']);

        $url = '';

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total=' . $this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

        $data['cancel'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['newcustomer'] = $this->url->link('customer/customer/add', 'token=' . $this->session->data['token'] . $url, 'SSL');

        if (isset($this->request->get['order_id'])) {
            $order_info = $this->model_sale_orderq->getOrder($this->request->get['order_id']);
        }

        if (!empty($order_info)) {
            $data['order_id'] = $this->request->get['order_id'];
            $data['store_id'] = $order_info['store_id'];
            $data['is_pos'] = $order_info['is_pos'];
            $data['order_type'] = $order_info['order_type'];

            $data['customer'] = $order_info['customer'];
            $data['customer_id'] = $order_info['customer_id'];
            if ($data['customer_id']) {
                $data['customeredit'] = $this->url->link('customer/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $order_info['customer_id'], 'SSL');
            } else {
                $data['customeredit'] = "";
            }
            $data['customer_group_id'] = $order_info['customer_group_id'];
            $data['firstname'] = $order_info['firstname'];
            $data['lastname'] = $order_info['lastname'];
            $data['email'] = $order_info['email'];
            $data['telephone'] = $order_info['telephone'];
            $data['fax'] = $order_info['fax'];
            $data['account_custom_field'] = $order_info['custom_field'];

            $this->load->model('customer/customer');
            if ($order_info['customer_id'] > 0) {
                $data['addresses'] = $this->model_customer_customer->getAddresses($order_info['customer_id']);
            } else {
                $data['addresses'] = $this->model_customer_customer->addGuestAddresses($order_info);
            }
            $data['payment_firstname'] = $order_info['payment_firstname'];
            $data['payment_lastname'] = $order_info['payment_lastname'];
            $data['payment_company'] = $order_info['payment_company'];
            $data['payment_address_1'] = $order_info['payment_address_1'];
            $data['payment_address_2'] = $order_info['payment_address_2'];
            $data['payment_city'] = $order_info['payment_city'];
            $data['payment_postcode'] = $order_info['payment_postcode'];
            $data['payment_country_id'] = $order_info['payment_country_id'];
            $data['payment_country'] = $order_info['payment_country'];
            $data['payment_zone_id'] = $order_info['payment_zone_id'];
            $data['payment_custom_field'] = $order_info['payment_custom_field'];
            $data['payment_method'] = $order_info['payment_method'];
            $data['payment_code'] = $order_info['payment_code'];

            $data['shipping_firstname'] = $order_info['shipping_firstname'];
            $data['shipping_lastname'] = $order_info['shipping_lastname'];
            $data['shipping_company'] = $order_info['shipping_company'];
            $data['shipping_address_1'] = $order_info['shipping_address_1'];
            $data['shipping_address_2'] = $order_info['shipping_address_2'];
            $data['shipping_city'] = $order_info['shipping_city'];
            $data['shipping_postcode'] = $order_info['shipping_postcode'];
            $data['shipping_country_id'] = $order_info['shipping_country_id'];
            $data['shipping_country'] = $order_info['shipping_country'];
            $data['shipping_zone_id'] = $order_info['shipping_zone_id'];
            $data['shipping_custom_field'] = $order_info['shipping_custom_field'];
            $data['shipping_method'] = $order_info['shipping_method'];
            $data['shipping_code'] = $order_info['shipping_code'];

            // Custom Fields
            $this->load->model('customer/custom_field');
            $custom_fields = $this->model_customer_custom_field->getCustomFields();

            $data['shipping_custom_fields'] = array();

            foreach ($custom_fields as $custom_field) {
                if ($custom_field['location'] == 'address' && isset($order_info['shipping_custom_field'][$custom_field['custom_field_id']])) {
                    if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio') {
                        $custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($order_info['shipping_custom_field'][$custom_field['custom_field_id']]);

                        if ($custom_field_value_info) {
                            $data['shipping_custom_fields'][] = array(
                                'name' => $custom_field['name'],
                                'value' => $custom_field_value_info['name'],
                                'sort_order' => $custom_field['sort_order']
                            );
                        }
                    }

                    if ($custom_field['type'] == 'checkbox' && is_array($order_info['shipping_custom_field'][$custom_field['custom_field_id']])) {
                        foreach ($order_info['shipping_custom_field'][$custom_field['custom_field_id']] as $custom_field_value_id) {
                            $custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($custom_field_value_id);

                            if ($custom_field_value_info) {
                                $data['shipping_custom_fields'][] = array(
                                    'name' => $custom_field['name'],
                                    'value' => $custom_field_value_info['name'],
                                    'sort_order' => $custom_field['sort_order']
                                );
                            }
                        }
                    }

                    if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
                        $data['shipping_custom_fields'][] = array(
                            'name' => $custom_field['name'],
                            'value' => $order_info['shipping_custom_field'][$custom_field['custom_field_id']],
                            'sort_order' => $custom_field['sort_order']
                        );
                    }

                    if ($custom_field['type'] == 'file') {
                        $upload_info = $this->model_tool_upload->getUploadByCode($order_info['shipping_custom_field'][$custom_field['custom_field_id']]);

                        if ($upload_info) {
                            $data['shipping_custom_fields'][] = array(
                                'name' => $custom_field['name'],
                                'value' => $upload_info['name'],
                                'sort_order' => $custom_field['sort_order']
                            );
                        }
                    }
                }
            }


            //payment custom fields
            // Custom fields
            $data['payment_custom_fields'] = array();

            foreach ($custom_fields as $custom_field) {
                if ($custom_field['location'] == 'address' && isset($order_info['payment_custom_field'][$custom_field['custom_field_id']])) {
                    if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio') {
                        $custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($order_info['payment_custom_field'][$custom_field['custom_field_id']]);

                        if ($custom_field_value_info) {
                            $data['payment_custom_fields'][] = array(
                                'name' => $custom_field['name'],
                                'value' => $custom_field_value_info['name'],
                                'sort_order' => $custom_field['sort_order']
                            );
                        }
                    }

                    if ($custom_field['type'] == 'checkbox' && is_array($order_info['payment_custom_field'][$custom_field['custom_field_id']])) {
                        foreach ($order_info['payment_custom_field'][$custom_field['custom_field_id']] as $custom_field_value_id) {
                            $custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($custom_field_value_id);

                            if ($custom_field_value_info) {
                                $data['payment_custom_fields'][] = array(
                                    'name' => $custom_field['name'],
                                    'value' => $custom_field_value_info['name'],
                                    'sort_order' => $custom_field['sort_order']
                                );
                            }
                        }
                    }

                    if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
                        $data['payment_custom_fields'][] = array(
                            'name' => $custom_field['name'],
                            'value' => $order_info['payment_custom_field'][$custom_field['custom_field_id']],
                            'sort_order' => $custom_field['sort_order']
                        );
                    }

                    if ($custom_field['type'] == 'file') {
                        $upload_info = $this->model_tool_upload->getUploadByCode($order_info['payment_custom_field'][$custom_field['custom_field_id']]);

                        if ($upload_info) {
                            $data['payment_custom_fields'][] = array(
                                'name' => $custom_field['name'],
                                'value' => $upload_info['name'],
                                'sort_order' => $custom_field['sort_order']
                            );
                        }
                    }
                }
            }

            // Products
            $data['order_products'] = array();

            $products = $this->model_sale_orderq->getOrderProducts($this->request->get['order_id']);

            foreach ($products as $product) {
                $data['order_products'][] = array(
                    'product_id' => $product['product_id'],
                    'name' => $product['name'],
                    'model' => $product['model'],
                    'unit_conversion_values' => $product['unit_conversion_values'],
                    'convert_price' => !empty($product['unit_conversion_values']) ? $this->model_sale_orderq->getConvertedPrice($product['unit_conversion_values']) : 1,
                    'image' => "",
                    'option' => $this->model_sale_orderq->getOrderOptions($this->request->get['order_id'], $product['order_product_id']),
                    'quantity_supplied' => $product['quantity_supplied'],
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                    'total' => $product['total'],
                    'reward' => $product['reward']
                );
            }

            // Vouchers
            $data['order_vouchers'] = $this->model_sale_orderq->getOrderVouchers($this->request->get['order_id']);

            $data['coupon'] = '';
            $data['voucher'] = '';
            $data['reward'] = '';

            $data['order_totals'] = array();

            $order_totals = $this->model_sale_orderq->getOrderTotals($this->request->get['order_id']);

            foreach ($order_totals as $order_total) {
                // If coupon, voucher or reward points
                $start = strpos($order_total['title'], '(') + 1;
                $end = strrpos($order_total['title'], ')');

                if ($start && $end) {
                    $data[$order_total['code']] = substr($order_total['title'], $start, $end - $start);
                }
            }

            $data['order_status_id'] = $order_info['order_status_id'];
            $data['comment'] = $order_info['comment'];
            $data['affiliate_id'] = $order_info['affiliate_id'];
            $data['affiliate'] = $order_info['affiliate_firstname'] . ' ' . $order_info['affiliate_lastname'];
            $data['currency_code'] = $order_info['currency_code'];
        } else {
            $data['order_id'] = 0;
            $data['order_type'] = 0;
            $data['store_id'] = '';
            $data['customer'] = '';
            $data['customer_id'] = '';
            $data['customeredit'] = "";
            $data['customer_group_id'] = $this->config->get('config_customer_group_id');
            $data['firstname'] = '';
            $data['lastname'] = '';
            $data['email'] = '';
            $data['telephone'] = '';
            $data['fax'] = '';
            $data['customer_custom_field'] = array();

            $data['addresses'] = array();

            $data['payment_firstname'] = '';
            $data['payment_lastname'] = '';
            $data['payment_company'] = '';
            $data['payment_address_1'] = '';
            $data['payment_address_2'] = '';
            $data['payment_city'] = '';
            $data['payment_postcode'] = '';
            $data['payment_country_id'] = '';
            $data['payment_zone_id'] = '';
            $data['payment_custom_field'] = array();
            $data['payment_method'] = '';
            $data['payment_code'] = '';

            $data['shipping_firstname'] = '';
            $data['shipping_lastname'] = '';
            $data['shipping_company'] = '';
            $data['shipping_address_1'] = '';
            $data['shipping_address_2'] = '';
            $data['shipping_city'] = '';
            $data['shipping_postcode'] = '';
            $data['shipping_country_id'] = '';
            $data['shipping_zone_id'] = '';
            $data['shipping_custom_field'] = array();
            $data['shipping_method'] = '';
            $data['shipping_code'] = '';

            $data['order_products'] = array();
            $data['order_vouchers'] = array();
            $data['order_totals'] = array();

            $data['order_status_id'] = $this->config->get('config_order_status_id');
            $data['comment'] = '';
            $data['affiliate_id'] = '';
            $data['affiliate'] = '';
            $data['currency_code'] = $this->config->get('config_currency');

            $data['coupon'] = '';
            $data['voucher'] = '';
            $data['reward'] = '';
        }

        // Stores
        $this->load->model('setting/store');

        $data['stores'] = array();

        $data['stores'][] = array(
            'store_id' => 0,
            'name' => $this->language->get('text_default'),
            'href' => HTTPS_CATALOG
        );

        $results = $this->model_setting_store->getStores();

        foreach ($results as $result) {
            $data['stores'][] = array(
                'store_id' => $result['store_id'],
                'name' => $result['name'],
                'href' => $result['url']
            );
        }

        $this->load->model('localisation/tax_class');
        $data['customttotal_tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

        // Customer Groups
        $this->load->model('customer/customer_group');

        $data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

        // Custom Fields
        $this->load->model('customer/custom_field');

        $data['custom_fields'] = array();

        $filter_data = array(
            'sort' => 'cf.sort_order',
            'order' => 'ASC'
        );

        $custom_fields = $this->model_customer_custom_field->getCustomFields($filter_data);

        foreach ($custom_fields as $custom_field) {
            $data['custom_fields'][] = array(
                'custom_field_id' => $custom_field['custom_field_id'],
                'custom_field_value' => $this->model_customer_custom_field->getCustomFieldValues($custom_field['custom_field_id']),
                'name' => $custom_field['name'],
                'value' => $custom_field['value'],
                'type' => $custom_field['type'],
                'location' => $custom_field['location'],
                'sort_order' => $custom_field['sort_order']
            );
        }

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $this->load->model('localisation/country');

        $data['countries'] = $this->model_localisation_country->getCountries();

        $this->load->model('localisation/currency');

        $data['currencies'] = $this->model_localisation_currency->getCurrencies();

        $data['customtotalstatus'] = $this->config->get('customtotal_status');
        $data['customtotal_names'] = $this->config->get('customtotal_names');
        if ($this->config->get('customtotal_status') && $data['customtotal_names']) {
            $lid = $this->config->get('config_language_id');
            foreach ($data['customtotal_names'] as $key => $value) {
                $data['customtotal'][$key]['name'] = isset($value[$lid]) ? $value[$lid]['name'] : "";
                $data['customtotal'][$key]['amount'] = $value['amount'];
            }
        } else {
            $data['customtotal'] = array();
        }

        $data['payment_method_name'] = "";
        $data['payment_method_cost'] = "";
        $data['shipping_method_name'] = "";
        $data['shipping_method_cost'] = "";

        if (!empty($order_info)) {
            if (isset($order_info['payment_code']) && $order_info['payment_code'] == "custom_payment_method") {
                $data['payment_method_name'] = $order_info['payment_method'];
            }
            if (isset($order_info['shipping_code']) && $order_info['shipping_code'] == "custom_shipping_method") {
                $data['shipping_method_name'] = $order_info['shipping_method'];
            }
        }

        $data['voucher_min'] = $this->config->get('config_voucher_min');

        $this->load->model('sale/voucher_theme');

        $data['voucher_themes'] = $this->model_sale_voucher_theme->getVoucherThemes();

        // API login
        $this->load->model('user/api');

        $api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

        if ($api_info) {
            $data['api_id'] = $api_info['api_id'];
            $data['api_key'] = $api_info['key'];
            $data['api_ip'] = $this->request->server['REMOTE_ADDR'];
        } else {
            $data['api_id'] = '';
            $data['api_key'] = '';
            $data['api_ip'] = '';
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('sale/orderq_form.tpl', $data));
    }

    public function autocomplete_manufacturer() {
        $json = array();

        if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_email'])) {
            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            if (isset($this->request->get['filter_email'])) {
                $filter_email = $this->request->get['filter_email'];
            } else {
                $filter_email = '';
            }

            $this->load->model('catalog/manufacturer');

            $filter_data = array(
                'filter_name' => $filter_name,
                'filter_email' => $filter_email,
                'start' => 0,
                'limit' => 5
            );

            //$results = $this->model_catalog_manufacturer->getManufacturers($filter_data);

            $sql_query = "SELECT DISTINCT m.manufacturer_id, m.name FROM " . DB_PREFIX . "order_product op LEFT JOIN " . DB_PREFIX . "product p ON(op.product_id=p.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON(p.manufacturer_id=m.manufacturer_id) WHERE op.order_id='" . $this->request->get['order_id'] . "' ";
            if (!empty($filter_data['filter_name'])) {
                $sql_query .= " AND m.name like '%" . $filter_data['filter_name'] . "%'";
            }

            $query_result = $this->db->query($sql_query);
            $results = $query_result->rows;

            foreach ($results as $result) {
                $json[] = array(
                    'manufacturer_id' => $result['manufacturer_id'],
                    'name' => isset($result['name']) ? $result['name'] : "",
                    'email' => isset($result['email']) ? $result['email'] : "",
                    'phone' => isset($result['phone']) ? $result['phone'] : ""
                );
            }
        }

        $sort_order = array();

        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['name'];
        }

        array_multisort($sort_order, SORT_ASC, $json);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getOrderedProductsManufacturers() {
        $query = $this->db->query("SELECT DISTINCT m.manufacturer_id, m.name FROM " . DB_PREFIX . "order_product op LEFT JOIN " . DB_PREFIX . "product p ON(op.product_id=p.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON(p.manufacturer_id=m.manufacturer_id) WHERE op.order_id='" . $this->request->get['order_id'] . "'");
        if ($query->num_rows) {
            return $query->rows;
        } else {
            return array();
        }
    }

    public function getFormForIncoming() {
        unset($this->session->data['order_id']);
        unset($this->session->data['refreshproducts']);
        $this->document->addStyle('view/stylesheet/orderquote.css');

        $this->load->model('customer/customer');

        $data['heading_title'] = $this->language->get('heading_title1');

        $data['text_form'] = !isset($this->request->get['order_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_edit_address'] = $this->language->get('text_edit_address');
        $data['text_default'] = $this->language->get('text_default');
        $data['text_select'] = $this->language->get('text_select');
        $data['text_version'] = $this->language->get('text_version');
        $data['text_none'] = $this->language->get('text_none');
        $data['text_guestcustomer'] = $this->language->get('text_guestcustomer');
        $data['text_loading'] = $this->language->get('text_loading');
        $data['text_ip_add'] = sprintf($this->language->get('text_ip_add'), $this->request->server['REMOTE_ADDR']);
        $data['text_product'] = $this->language->get('text_product');
        $data['text_voucher'] = $this->language->get('text_voucher');
        $data['text_order_detail'] = $this->language->get('text_order_detail');
        $data['text_more_detail'] = $this->language->get('text_more_detail');
        $data['text_order_address'] = $this->language->get('text_order_address');
        $data['text_accountdetails'] = $this->language->get('text_accountdetails');
        $data['text_paymentdetails'] = $this->language->get('text_paymentdetails');
        $data['text_shippingdetails'] = $this->language->get('text_shippingdetails');
        $data['text_updatedetails'] = $this->language->get('text_updatedetails');
        $data['text_tosave'] = $this->language->get('text_tosave');
        $data['text_updateded'] = $this->language->get('text_updateded');
        $data['text_details'] = $this->language->get('text_details');
        $data['text_personaldetails_success'] = $this->language->get('text_personaldetails_success');
        $data['text_recalculatetotal'] = $this->language->get('text_recalculatetotal');
        $data['text_savedpdetails'] = $this->language->get('text_savedpdetails');

        $data['text_previous_orders'] = $this->language->get('text_previous_orders');
        $data['text_customtotal'] = $this->language->get('text_customtotal');
        $data['text_customfee'] = $this->language->get('text_customfee');
        $data['text_customtaxes'] = $this->language->get('text_customtaxes');
        $data['text_customamount'] = $this->language->get('text_customamount');
        $data['text_customshipping'] = $this->language->get('text_customshipping');
        $data['text_custompayment'] = $this->language->get('text_custompayment');

        $this->load->model('tool/orderq');
        $this->model_tool_orderq->createTable();

        $data['text_invoice'] = $this->language->get('text_invoice');
        $data['text_order_id'] = $this->language->get('text_order_id');
        $data['text_invoice_no'] = $this->language->get('text_invoice_no');
        $data['text_invoice_date'] = $this->language->get('text_invoice_date');
        $data['text_date_added'] = $this->language->get('text_date_added');
        $data['text_telephone'] = $this->language->get('text_telephone');
        $data['text_fax'] = $this->language->get('text_fax');
        $data['text_email'] = $this->language->get('text_email');
        $data['text_website'] = $this->language->get('text_website');
        $data['text_payment_address'] = $this->language->get('text_payment_address');
        $data['text_shipping_address'] = $this->language->get('text_shipping_address');
        $data['text_payment_method'] = $this->language->get('text_payment_method');
        $data['text_shipping_method'] = $this->language->get('text_shipping_method');
        $data['text_notify'] = $this->language->get('text_notify');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_comment'] = $this->language->get('text_comment');

        $data['entry_store'] = $this->language->get('entry_store');
        $data['entry_customer'] = $this->language->get('entry_customer');
        $data['entry_addnewcustomer'] = $this->language->get('entry_addnewcustomer');
        $data['entry_updatecustomer'] = $this->language->get('entry_updatecustomer');
        $data['entry_latestorders'] = $this->language->get('entry_latestorders');
        $data['entry_refreshcustomer'] = $this->language->get('entry_refreshcustomer');
        $data['entry_customer_group'] = $this->language->get('entry_customer_group');
        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_fax'] = $this->language->get('entry_fax');
        $data['entry_comment'] = $this->language->get('entry_comment');
        $data['entry_affiliate'] = $this->language->get('entry_affiliate');
        $data['entry_address'] = $this->language->get('entry_address');
        $data['entry_newprice'] = $this->language->get('entry_newprice');
        $data['entry_payment_address'] = $this->language->get('entry_payment_address');
        $data['entry_shipping_address'] = $this->language->get('entry_shipping_address');
        $data['text_payment_address'] = $this->language->get('text_payment_address');
        $data['text_shipping_address'] = $this->language->get('text_shipping_address');
        $data['text_address_detail'] = $this->language->get('text_address_detail');
        $data['entry_addproducts'] = $this->language->get('entry_addproducts');
        $data['entry_addvoucher'] = $this->language->get('entry_addvoucher');
        $data['entry_add_address'] = $this->language->get('entry_add_address');
        $data['noaddress_1'] = $this->language->get('noaddress_1');
        $data['noaddress_2'] = $this->language->get('noaddress_2');
        $data['entry_company'] = $this->language->get('entry_company');
        $data['entry_address_1'] = $this->language->get('entry_address_1');
        $data['entry_address_2'] = $this->language->get('entry_address_2');
        $data['entry_city'] = $this->language->get('entry_city');
        $data['entry_postcode'] = $this->language->get('entry_postcode');
        $data['entry_zone'] = $this->language->get('entry_zone');
        $data['entry_zone_code'] = $this->language->get('entry_zone_code');
        $data['entry_country'] = $this->language->get('entry_country');
        $data['entry_product'] = $this->language->get('entry_product');
        $data['entry_filterby'] = $this->language->get('entry_filterby');
        $data['entry_option'] = $this->language->get('entry_option');
        $data['entry_quantity'] = $this->language->get('entry_quantity');
        $data['entry_to_name'] = $this->language->get('entry_to_name');
        $data['entry_to_email'] = $this->language->get('entry_to_email');
        $data['entry_from_name'] = $this->language->get('entry_from_name');
        $data['entry_from_email'] = $this->language->get('entry_from_email');
        $data['entry_theme'] = $this->language->get('entry_theme');
        $data['entry_message'] = $this->language->get('entry_message');
        $data['entry_amount'] = $this->language->get('entry_amount');
        $data['entry_currency'] = $this->language->get('entry_currency');
        $data['entry_shipping_method'] = $this->language->get('entry_shipping_method');
        $data['entry_payment_method'] = $this->language->get('entry_payment_method');
        $data['entry_coupon'] = $this->language->get('entry_coupon');
        $data['entry_voucher'] = $this->language->get('entry_voucher');
        $data['entry_reward'] = $this->language->get('entry_reward');
        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_additionalemail'] = $this->language->get('entry_additionalemail');

        $data['help_additionalemail'] = $this->language->get('help_additionalemail');
        $data['help_customer'] = $this->language->get('help_customer');

        $data['column_image'] = $this->language->get('column_image');
        $data['column_product'] = $this->language->get('column_product');
        $data['column_model'] = $this->language->get('column_model');
        $data['column_quantity'] = $this->language->get('column_quantity');
        $data['column_price'] = $this->language->get('column_price');
        $data['column_newprice'] = $this->language->get('column_newprice');
        $data['column_total'] = $this->language->get('column_total');
        $data['column_action'] = $this->language->get('column_action');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_continue'] = $this->language->get('button_continue');
        $data['button_back'] = $this->language->get('button_back');
        $data['button_refresh'] = $this->language->get('button_refresh');
        $data['button_product_add'] = $this->language->get('button_product_add');
        $data['button_voucher_add'] = $this->language->get('button_voucher_add');
        $data['button_apply'] = $this->language->get('button_apply');
        $data['button_upload'] = $this->language->get('button_upload');
        $data['button_remove'] = $this->language->get('button_remove');
        $data['button_ip_add'] = $this->language->get('button_ip_add');
        $data['button_removecustompayment'] = $this->language->get('button_removecustompayment');
        $data['button_removecustomshipping'] = $this->language->get('button_removecustomshipping');

        $data['tab_order'] = $this->language->get('tab_order');
        $data['tab_customer'] = $this->language->get('tab_customer');
        $data['tab_payment'] = $this->language->get('tab_payment');
        $data['tab_shipping'] = $this->language->get('tab_shipping');
        $data['tab_product'] = $this->language->get('tab_product');
        $data['tab_voucher'] = $this->language->get('tab_voucher');
        $data['tab_total'] = $this->language->get('tab_total');

        $data['token'] = $this->session->data['token'];

        if (isset($this->request->get['order_id'])) {
            $manufacturer_info = $this->getOrderedProductsManufacturers($this->request->get['order_id']);
        }
        if (!empty($manufacturer_info)) {
            $data['manufacturers_ordered'] = $manufacturer_info;
        }
        if (isset($this->session->data['error_upload']) && !empty($this->session->data['error_upload'])) {
            $data['error_upload'] = $this->session->data['error_upload'];
        }
        unset($this->session->data['error_upload']);

        $url = '';

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total=' . $this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

        $data['cancel'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['newcustomer'] = $this->url->link('customer/customer/add', 'token=' . $this->session->data['token'] . $url, 'SSL');

        if (isset($this->request->get['order_id'])) {
            $order_info = $this->model_sale_orderq->getOrder($this->request->get['order_id']);
        }

        if (!empty($order_info)) {
            $data['order_id'] = $this->request->get['order_id'];
            $data['store_id'] = $order_info['store_id'];

            $data['customer'] = $order_info['customer'];
            $data['customer_id'] = $order_info['customer_id'];
            if ($data['customer_id']) {
                $data['customeredit'] = $this->url->link('customer/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $order_info['customer_id'], 'SSL');
            } else {
                $data['customeredit'] = "";
            }
            $data['customer_group_id'] = $order_info['customer_group_id'];
            $data['firstname'] = $order_info['firstname'];
            $data['lastname'] = $order_info['lastname'];
            $data['email'] = $order_info['email'];
            $data['telephone'] = $order_info['telephone'];
            $data['fax'] = $order_info['fax'];
            $data['account_custom_field'] = $order_info['custom_field'];

            $this->load->model('customer/customer');
            if ($order_info['customer_id'] > 0) {
                $data['addresses'] = $this->model_customer_customer->getAddresses($order_info['customer_id']);
            } else {
                $data['addresses'] = $this->model_customer_customer->addGuestAddresses($order_info);
            }
            $data['payment_firstname'] = $order_info['payment_firstname'];
            $data['payment_lastname'] = $order_info['payment_lastname'];
            $data['payment_company'] = $order_info['payment_company'];
            $data['payment_address_1'] = $order_info['payment_address_1'];
            $data['payment_address_2'] = $order_info['payment_address_2'];
            $data['payment_city'] = $order_info['payment_city'];
            $data['payment_postcode'] = $order_info['payment_postcode'];
            $data['payment_country_id'] = $order_info['payment_country_id'];
            $data['payment_country'] = $order_info['payment_country'];
            $data['payment_zone_id'] = $order_info['payment_zone_id'];
            $data['payment_custom_field'] = $order_info['payment_custom_field'];
            $data['payment_method'] = $order_info['payment_method'];
            $data['payment_code'] = $order_info['payment_code'];

            $data['shipping_firstname'] = $order_info['shipping_firstname'];
            $data['shipping_lastname'] = $order_info['shipping_lastname'];
            $data['shipping_company'] = $order_info['shipping_company'];
            $data['shipping_address_1'] = $order_info['shipping_address_1'];
            $data['shipping_address_2'] = $order_info['shipping_address_2'];
            $data['shipping_city'] = $order_info['shipping_city'];
            $data['shipping_postcode'] = $order_info['shipping_postcode'];
            $data['shipping_country_id'] = $order_info['shipping_country_id'];
            $data['shipping_country'] = $order_info['shipping_country'];
            $data['shipping_zone_id'] = $order_info['shipping_zone_id'];
            $data['shipping_custom_field'] = $order_info['shipping_custom_field'];
            $data['shipping_method'] = $order_info['shipping_method'];
            $data['shipping_code'] = $order_info['shipping_code'];

            // Custom Fields
            $this->load->model('customer/custom_field');
            $custom_fields = $this->model_customer_custom_field->getCustomFields();

            $data['shipping_custom_fields'] = array();

            foreach ($custom_fields as $custom_field) {
                if ($custom_field['location'] == 'address' && isset($order_info['shipping_custom_field'][$custom_field['custom_field_id']])) {
                    if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio') {
                        $custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($order_info['shipping_custom_field'][$custom_field['custom_field_id']]);

                        if ($custom_field_value_info) {
                            $data['shipping_custom_fields'][] = array(
                                'name' => $custom_field['name'],
                                'value' => $custom_field_value_info['name'],
                                'sort_order' => $custom_field['sort_order']
                            );
                        }
                    }

                    if ($custom_field['type'] == 'checkbox' && is_array($order_info['shipping_custom_field'][$custom_field['custom_field_id']])) {
                        foreach ($order_info['shipping_custom_field'][$custom_field['custom_field_id']] as $custom_field_value_id) {
                            $custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($custom_field_value_id);

                            if ($custom_field_value_info) {
                                $data['shipping_custom_fields'][] = array(
                                    'name' => $custom_field['name'],
                                    'value' => $custom_field_value_info['name'],
                                    'sort_order' => $custom_field['sort_order']
                                );
                            }
                        }
                    }

                    if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
                        $data['shipping_custom_fields'][] = array(
                            'name' => $custom_field['name'],
                            'value' => $order_info['shipping_custom_field'][$custom_field['custom_field_id']],
                            'sort_order' => $custom_field['sort_order']
                        );
                    }

                    if ($custom_field['type'] == 'file') {
                        $upload_info = $this->model_tool_upload->getUploadByCode($order_info['shipping_custom_field'][$custom_field['custom_field_id']]);

                        if ($upload_info) {
                            $data['shipping_custom_fields'][] = array(
                                'name' => $custom_field['name'],
                                'value' => $upload_info['name'],
                                'sort_order' => $custom_field['sort_order']
                            );
                        }
                    }
                }
            }



            //payment custom fields
            // Custom fields
            $data['payment_custom_fields'] = array();

            foreach ($custom_fields as $custom_field) {
                if ($custom_field['location'] == 'address' && isset($order_info['payment_custom_field'][$custom_field['custom_field_id']])) {
                    if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio') {
                        $custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($order_info['payment_custom_field'][$custom_field['custom_field_id']]);

                        if ($custom_field_value_info) {
                            $data['payment_custom_fields'][] = array(
                                'name' => $custom_field['name'],
                                'value' => $custom_field_value_info['name'],
                                'sort_order' => $custom_field['sort_order']
                            );
                        }
                    }

                    if ($custom_field['type'] == 'checkbox' && is_array($order_info['payment_custom_field'][$custom_field['custom_field_id']])) {
                        foreach ($order_info['payment_custom_field'][$custom_field['custom_field_id']] as $custom_field_value_id) {
                            $custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($custom_field_value_id);

                            if ($custom_field_value_info) {
                                $data['payment_custom_fields'][] = array(
                                    'name' => $custom_field['name'],
                                    'value' => $custom_field_value_info['name'],
                                    'sort_order' => $custom_field['sort_order']
                                );
                            }
                        }
                    }

                    if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
                        $data['payment_custom_fields'][] = array(
                            'name' => $custom_field['name'],
                            'value' => $order_info['payment_custom_field'][$custom_field['custom_field_id']],
                            'sort_order' => $custom_field['sort_order']
                        );
                    }

                    if ($custom_field['type'] == 'file') {
                        $upload_info = $this->model_tool_upload->getUploadByCode($order_info['payment_custom_field'][$custom_field['custom_field_id']]);

                        if ($upload_info) {
                            $data['payment_custom_fields'][] = array(
                                'name' => $custom_field['name'],
                                'value' => $upload_info['name'],
                                'sort_order' => $custom_field['sort_order']
                            );
                        }
                    }
                }
            }

            // Products
            $data['order_products'] = array();

            $products = $this->model_sale_orderq->getOrderProducts($this->request->get['order_id']);

            $data['order_has_location_quantity'] = $this->model_sale_orderq->checkOrderHasLocationQuantity($products);

            foreach ($products as $product) {
                $data['order_products'][] = array(
                    'product_id' => $product['product_id'],
                    'name' => $product['name'],
                    'model' => $product['model'],
                    'unit_conversion_values' => $product['unit_conversion_values'],
                    'image' => "",
                    'option' => $this->model_sale_orderq->getOrderOptions($this->request->get['order_id'], $product['order_product_id']),
                    'quantity_supplied' => $product['quantity_supplied'],
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                    'total' => $product['total'],
                    'reward' => $product['reward']
                );
            }

            // Vouchers
            $data['order_vouchers'] = $this->model_sale_orderq->getOrderVouchers($this->request->get['order_id']);

            $data['coupon'] = '';
            $data['voucher'] = '';
            $data['reward'] = '';

            $data['order_totals'] = array();

            $order_totals = $this->model_sale_orderq->getOrderTotals($this->request->get['order_id']);

            foreach ($order_totals as $order_total) {
                // If coupon, voucher or reward points
                $start = strpos($order_total['title'], '(') + 1;
                $end = strrpos($order_total['title'], ')');

                if ($start && $end) {
                    $data[$order_total['code']] = substr($order_total['title'], $start, $end - $start);
                }
            }

            $data['order_status_id'] = $order_info['order_status_id'];
            $data['comment'] = $order_info['comment'];
            $data['affiliate_id'] = $order_info['affiliate_id'];
            $data['affiliate'] = $order_info['affiliate_firstname'] . ' ' . $order_info['affiliate_lastname'];
            $data['currency_code'] = $order_info['currency_code'];
        } else {
            $customer_info = $this->model_customer_customer->getCustomerByEmail('bellausa@yahoo.com');
            $data['order_id'] = 0;
            $data['store_id'] = $customer_info['store_id'];
            $data['customer'] = $customer_info['firstname'] . ' ' . $customer_info['lastname'];
            $data['customer_id'] = $customer_info['customer_id'];
            $data['customeredit'] = "";
            $data['customer_group_id'] = $customer_info['customer_group_id'];
            $data['firstname'] = $customer_info['firstname'];
            $data['lastname'] = $customer_info['lastname'];
            $data['email'] = $customer_info['email'];
            $data['telephone'] = $customer_info['telephone'];
            $data['fax'] = $customer_info['fax'];
            $data['customer_custom_field'] = array();
            $data['order_has_location_quantity'] = false;

            $data['addresses'] = $this->model_customer_customer->getAddresses($customer_info['customer_id']);
            $key = key($data['addresses']);
            $data['payment_firstname'] = $data['addresses'][$key]['firstname'];
            $data['payment_lastname'] = $data['addresses'][$key]['lastname'];
            $data['payment_company'] = '';
            $data['payment_address_1'] = $data['addresses'][$key]['address_1'];
            $data['payment_address_2'] = $data['addresses'][$key]['address_2'];
            $data['payment_city'] = $data['addresses'][$key]['city'];
            $data['payment_postcode'] = $data['addresses'][$key]['postcode'];
            $data['payment_country_id'] = $data['addresses'][$key]['country_id'];
            $data['payment_zone_id'] = $data['addresses'][$key]['zone_id'];
            $data['payment_custom_field'] = array();
            $data['payment_method'] = 'Bill Me Later';
            $data['payment_code'] = 'cheque';

            $data['shipping_firstname'] = $data['addresses'][$key]['firstname'];
            $data['shipping_lastname'] = $data['addresses'][$key]['lastname'];
            $data['shipping_company'] = '';
            $data['shipping_address_1'] = $data['addresses'][$key]['address_1'];
            $data['shipping_address_2'] = $data['addresses'][$key]['address_2'];
            $data['shipping_city'] = $data['addresses'][$key]['city'];
            $data['shipping_postcode'] = $data['addresses'][$key]['postcode'];
            $data['shipping_country_id'] = $data['addresses'][$key]['country_id'];
            $data['shipping_zone_id'] = $data['addresses'][$key]['zone_id'];
            $data['shipping_custom_field'] = array();
            $data['shipping_method'] = 'In-Store Pick up';
            $data['shipping_code'] = 'xshippingpro.xshippingpro2';

            $data['order_products'] = array();
            $data['order_vouchers'] = array();
            $data['order_totals'] = array();

            $data['order_status_id'] = 1;
            $data['comment'] = '';
            $data['affiliate_id'] = '';
            $data['affiliate'] = '';
            $data['currency_code'] = $this->config->get('config_currency');

            $data['coupon'] = '';
            $data['voucher'] = '';
            $data['reward'] = '';
        }

        // Stores
        $this->load->model('setting/store');

        $data['stores'] = array();

        $data['stores'][] = array(
            'store_id' => 0,
            'name' => $this->language->get('text_default'),
            'href' => HTTPS_CATALOG
        );

        $results = $this->model_setting_store->getStores();

        foreach ($results as $result) {
            $data['stores'][] = array(
                'store_id' => $result['store_id'],
                'name' => $result['name'],
                'href' => $result['url']
            );
        }

        $this->load->model('localisation/tax_class');
        $data['customttotal_tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

        // Customer Groups
        $this->load->model('customer/customer_group');

        $data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

        // Custom Fields
        $this->load->model('customer/custom_field');

        $data['custom_fields'] = array();

        $filter_data = array(
            'sort' => 'cf.sort_order',
            'order' => 'ASC'
        );

        $custom_fields = $this->model_customer_custom_field->getCustomFields($filter_data);

        foreach ($custom_fields as $custom_field) {
            $data['custom_fields'][] = array(
                'custom_field_id' => $custom_field['custom_field_id'],
                'custom_field_value' => $this->model_customer_custom_field->getCustomFieldValues($custom_field['custom_field_id']),
                'name' => $custom_field['name'],
                'value' => $custom_field['value'],
                'type' => $custom_field['type'],
                'location' => $custom_field['location'],
                'sort_order' => $custom_field['sort_order']
            );
        }

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $this->load->model('localisation/country');

        $data['countries'] = $this->model_localisation_country->getCountries();

        $this->load->model('localisation/currency');

        $data['currencies'] = $this->model_localisation_currency->getCurrencies();

        $data['customtotalstatus'] = $this->config->get('customtotal_status');
        $data['customtotal_names'] = $this->config->get('customtotal_names');
        if ($this->config->get('customtotal_status') && $data['customtotal_names']) {
            $lid = $this->config->get('config_language_id');
            foreach ($data['customtotal_names'] as $key => $value) {
                $data['customtotal'][$key]['name'] = isset($value[$lid]) ? $value[$lid]['name'] : "";
                $data['customtotal'][$key]['amount'] = $value['amount'];
            }
        } else {
            $data['customtotal'] = array();
        }

        $data['payment_method_name'] = "";
        $data['payment_method_cost'] = "";
        $data['shipping_method_name'] = "";
        $data['shipping_method_cost'] = "";

        if (!empty($order_info)) {
            if (isset($order_info['payment_code']) && $order_info['payment_code'] == "custom_payment_method") {
                $data['payment_method_name'] = $order_info['payment_method'];
            }
            if (isset($order_info['shipping_code']) && $order_info['shipping_code'] == "custom_shipping_method") {
                $data['shipping_method_name'] = $order_info['shipping_method'];
            }
        }

        $data['voucher_min'] = $this->config->get('config_voucher_min');

        $this->load->model('sale/voucher_theme');

        $data['voucher_themes'] = $this->model_sale_voucher_theme->getVoucherThemes();

        // API login
        $this->load->model('user/api');

        $api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

        if ($api_info) {
            $data['api_id'] = $api_info['api_id'];
            $data['api_key'] = $api_info['key'];
            $data['api_ip'] = $this->request->server['REMOTE_ADDR'];
        } else {
            $data['api_id'] = '';
            $data['api_key'] = '';
            $data['api_ip'] = '';
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('sale/incoming_orderq_form.tpl', $data));
    }

    public function create_backorder() {
        $json = array();
        $product_id = $this->request->get['product_id'];
        $customer_id = $this->request->get['customer_id'];
        $order_id = $this->request->get['order_id'];
        $quantity = $this->request->get['quantity'];
        $address_id = 0;
        if (isset($this->request->get['address_id'])) {
            $address_id = $this->request->get['address_id'];
        }
        if ($product_id > 0) {
            $this->load->model("sale/orderq");
            $json['msg'] = $this->model_sale_orderq->createBackOrder(array(), $product_id, $customer_id, $order_id, $quantity, $address_id);
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function create_incoming_order() {
        $json = array();
        $product_id = $this->request->get['product_id'];
        $order_id = $this->request->get['order_id'];
        $quantity = $this->request->get['quantity'];
        if ($product_id > 0) {
            $this->load->model("sale/orderq");
            $manufacturer_id = $this->model_sale_orderq->getProductManufacturerID($product_id);
            $new_incoming_order_id = $this->model_sale_orderq->createIncomingOrderRow(array(), $manufacturer_id);
            if ($new_incoming_order_id == 0) {
                $json['msg'] = "Nothing is added because default customer is not found.";
            } else {
                $this->model_sale_orderq->addFirstProductToIncomingOrder($new_incoming_order_id, $product_id, $order_id, $quantity);
                $product_info = $this->model_sale_orderq->getProductData($product_id);
                $json['msg'] = "New Incoming Order Created! " . $quantity . " X " . $product_info['model'] . " added to Incoming Order " . $new_incoming_order_id;
            }
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function add_to_pending_incoming_order() {
        $json = array();
        $product_id = $this->request->get['product_id'];
        $order_id = $this->request->get['order_id'];
        $quantity = $this->request->get['quantity'];
        $this->load->model("sale/orderq");
        $product_info = $this->model_sale_orderq->getProductData($product_id);
        $manufacturer_id = $this->model_sale_orderq->getProductManufacturerID($product_id);
        if ($product_id > 0) {
            $pending_incoming_order_id = $this->model_sale_orderq->pendingIncomingOrder($manufacturer_id);
            if ($pending_incoming_order_id > 0) {
                $this->model_sale_orderq->addProductToIncomingOrder($pending_incoming_order_id, $product_id, $order_id, $quantity);
                $json['msg'] = $quantity . " X " . $product_info['model'] . " added to Incoming Order " . $pending_incoming_order_id;
            } else {
                $new_incoming_order_id = $this->model_sale_orderq->createIncomingOrderRow(array(), $manufacturer_id);
                $this->model_sale_orderq->addFirstProductToIncomingOrder($new_incoming_order_id, $product_id, $order_id, $quantity);
                $json['msg'] = "Pending Incoming Order not found. New Incoming Order Created! " . $quantity . " X " . $product_info['model'] . " added to Incoming Order " . $new_incoming_order_id;
            }
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getpdetails() {
        $html = "";
        $this->load->model("customer/customer");
        $this->load->model("sale/order");
        if (isset($this->request->get['cid'])) {
            $this->load->language('customer/customer');
            $data['entry_customer_group'] = $this->language->get('entry_customer_group');
            $data['entry_firstname'] = $this->language->get('entry_firstname');
            $data['entry_lastname'] = $this->language->get('entry_lastname');
            $data['entry_email'] = $this->language->get('entry_email');
            $data['entry_telephone'] = $this->language->get('entry_telephone');
            $data['text_select'] = $this->language->get('text_select');

            if ($this->request->get['cid'] > 0) {
                $customer_info = $this->model_customer_customer->getCustomer($this->request->get['cid']);
            } else {
                $customer_info = $this->model_sale_order->getOrderguest($this->request->get['order_id']);
            }
            $this->load->model('customer/customer_group');

            $data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

            if (!empty($customer_info)) {
                $data['customer_group_id'] = $customer_info['customer_group_id'];
            } else {
                $data['customer_group_id'] = $this->config->get('config_customer_group_id');
            }
            if (!empty($customer_info)) {
                $data['firstname'] = $customer_info['firstname'];
            } else {
                $data['firstname'] = '';
            }
            if (!empty($customer_info)) {
                $data['lastname'] = $customer_info['lastname'];
            } else {
                $data['lastname'] = '';
            }
            if (!empty($customer_info)) {
                $data['email'] = $customer_info['email'];
            } else {
                $data['email'] = '';
            }
            if (!empty($customer_info)) {
                $data['telephone'] = $customer_info['telephone'];
            } else {
                $data['telephone'] = '';
            }
            // Custom Fields
            $this->load->model('customer/custom_field');

            $data['custom_fields'] = array();

            $filter_data = array(
                'sort' => 'cf.sort_order',
                'order' => 'ASC'
            );

            $custom_fields = $this->model_customer_custom_field->getCustomFields($filter_data);

            foreach ($custom_fields as $custom_field) {
                $data['custom_fields'][] = array(
                    'custom_field_id' => $custom_field['custom_field_id'],
                    'custom_field_value' => $this->model_customer_custom_field->getCustomFieldValues($custom_field['custom_field_id']),
                    'name' => $custom_field['name'],
                    'value' => $custom_field['value'],
                    'type' => $custom_field['type'],
                    'location' => $custom_field['location'],
                    'sort_order' => $custom_field['sort_order']
                );
            }

            if (!empty($customer_info) && isset($customer_info['custom_field'])) {
                $data['account_custom_field'] = json_decode($customer_info['custom_field'], true);
            } else {
                $data['account_custom_field'] = array();
            }
            $html = $this->load->view('sale/orderq_pd.tpl', $data);
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($html));
    }

    public function getadetails() {
        $html = "";
        $this->load->model("customer/customer");
        $this->load->model("customer/custom_field");
        $this->load->model("sale/orderq");
        if (isset($this->request->get['aid']) && $this->request->get['aid']) {
            $this->load->language('customer/customer');
            $data['entry_firstname'] = $this->language->get('entry_firstname');
            $data['entry_lastname'] = $this->language->get('entry_lastname');
            $data['entry_company'] = $this->language->get('entry_company');
            $data['entry_address_1'] = $this->language->get('entry_address_1');
            $data['entry_address_2'] = $this->language->get('entry_address_2');
            $data['entry_city'] = $this->language->get('entry_city');
            $data['entry_postcode'] = $this->language->get('entry_postcode');
            $data['entry_zone'] = $this->language->get('entry_zone');
            $data['entry_country'] = $this->language->get('entry_country');
            $data['text_select'] = $this->language->get('text_select');

            $address_info = $this->model_customer_customer->getAddress($this->request->get['aid']);
            $data['address_id'] = $this->request->get['aid'];
            $data['customer_group_id'] = '';
            if (!empty($address_info)) {
                $data['customer_id'] = $address_info['customer_id'];
                $customer_info = $this->model_customer_customer->getCustomer($data['customer_id']);
                if (!empty($customer_info)) {
                    $data['customer_group_id'] = $customer_info['customer_group_id'];
                }
            } else {
                $data['customer_id'] = '';
            }
            if (!empty($address_info)) {
                $data['firstname'] = $address_info['firstname'];
            } else {
                $data['firstname'] = '';
            }
            if (!empty($address_info)) {
                $data['lastname'] = $address_info['lastname'];
            } else {
                $data['lastname'] = '';
            }
            if (!empty($address_info)) {
                $data['company'] = $address_info['company'];
            } else {
                $data['company'] = '';
            }
            if (!empty($address_info)) {
                $data['address_1'] = $address_info['address_1'];
            } else {
                $data['address_1'] = '';
            }
            if (!empty($address_info)) {
                $data['address_2'] = $address_info['address_2'];
            } else {
                $data['address_2'] = '';
            }
            if (!empty($address_info)) {
                $data['city'] = $address_info['city'];
            } else {
                $data['city'] = '';
            }
            if (!empty($address_info)) {
                $data['postcode'] = $address_info['postcode'];
            } else {
                $data['postcode'] = '';
            }

            $this->load->model('localisation/country');
            $data['countries'] = $this->model_localisation_country->getCountries();


            if (!empty($address_info)) {
                $data['country_id'] = $address_info['country_id'];
            } else {
                $data['country_id'] = '';
            }

            $this->load->model('localisation/zone');
            $data['zones'] = $this->model_localisation_zone->getZonesByCountryId($data['country_id']);

            if (!empty($address_info)) {
                $data['zone_id'] = $address_info['zone_id'];
            } else {
                $data['zone_id'] = '';
            }
            $data['custom_fields'] = array();
            $custom_fields = $this->model_sale_orderq->getCustomFields($data['customer_group_id']);

            foreach ($custom_fields as $custom_field) {
                $data['custom_fields'][] = array(
                    'custom_field_id' => $custom_field['custom_field_id'],
                    'custom_field_value' => $this->model_customer_custom_field->getCustomFieldValues($custom_field['custom_field_id']),
                    'name' => $custom_field['name'],
                    'value' => $custom_field['value'],
                    'type' => $custom_field['type'],
                    'location' => $custom_field['location'],
                    'sort_order' => $custom_field['sort_order']
                );
            }

            if (!empty($address_info)) {
                $data['address_custom_field'] = $address_info['custom_field'];
            } else {
                $data['address_custom_field'] = array();
            }

            $html = $this->load->view('sale/orderq_ad.tpl', $data);
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($html));
    }

    public function savepdetails() {
        $this->load->model("customer/customer");
        $this->load->model("sale/orderq");
        $this->load->language("customer/customer");
        $this->load->language("sale/orderq");

        $json = array();
        $json['error'] = "";

        if (!$this->user->hasPermission('modify', 'customer/customer') && !$this->user->hasPermission('modify', 'sale/order')) {
            $json['error'] = $this->language->get('error_permission');
        } else {

            if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
                $json['error'] = $this->language->get('error_firstname') . "<br>";
            }

            if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
                $json['error'] .= $this->language->get('error_lastname') . "<br>";
            }

            if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
                $json['error'] .= $this->language->get('error_email') . "<br>";
            }

            $customer_info = $this->model_customer_customer->getCustomerByEmail($this->request->post['email']);

            if (!isset($this->request->post['customer_id'])) {
                if ($customer_info) {
                    $json['error'] .= $this->language->get('error_exists') . "<br>";
                }
            } else {
                if ($customer_info && ($this->request->post['customer_id'] != $customer_info['customer_id'])) {
                    $json['error'] .= $this->language->get('error_exists') . "<br>";
                }
            }

            if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
                $json['error'] .= $this->language->get('error_telephone') . "</br>";
            }

            // Custom field validation
            $this->load->model('customer/custom_field');

            $custom_fields = $this->model_customer_custom_field->getCustomFields(array('filter_customer_group_id' => $this->request->post['customer_group_id']));

            foreach ($custom_fields as $custom_field) {
                if (($custom_field['location'] == 'account') && $custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['custom_field_id']])) {
                    $json['error'] .= sprintf($this->language->get('error_custom_field'), $custom_field['name']) . "</br>";
                } elseif (($custom_field['location'] == 'account') && ($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !filter_var($this->request->post['custom_field'][$custom_field['custom_field_id']], FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $custom_field['validation'])))) {
                    $json['error'] .= sprintf($this->language->get('error_custom_field'), $custom_field['name']) . "</br>";
                }
            }

            if ($json['error'] == "") {
                $this->model_sale_orderq->savepdetails($this->request->post['customer_id'], $this->request->post);
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function latestorders() {
        $this->load->language('sale/order');
        $json = array();
        $data['title'] = $this->language->get('text_invoice');

        if ($this->request->server['HTTPS']) {
            $data['base'] = HTTPS_SERVER;
        } else {
            $data['base'] = HTTP_SERVER;
        }

        $data['direction'] = $this->language->get('direction');
        $data['lang'] = $this->language->get('code');

        $data['text_invoice'] = $this->language->get('text_invoice');
        $data['text_order_detail'] = $this->language->get('text_order_detail');
        $data['text_order_id'] = $this->language->get('text_order_id');
        $data['text_invoice_no'] = $this->language->get('text_invoice_no');
        $data['text_invoice_date'] = $this->language->get('text_invoice_date');
        $data['text_date_added'] = $this->language->get('text_date_added');
        $data['text_telephone'] = $this->language->get('text_telephone');
        $data['text_fax'] = $this->language->get('text_fax');
        $data['text_email'] = $this->language->get('text_email');
        $data['text_website'] = $this->language->get('text_website');
        $data['text_payment_address'] = $this->language->get('text_payment_address');
        $data['text_shipping_address'] = $this->language->get('text_shipping_address');
        $data['text_payment_method'] = $this->language->get('text_payment_method');
        $data['text_shipping_method'] = $this->language->get('text_shipping_method');
        $data['text_comment'] = $this->language->get('text_comment');

        $data['column_product'] = $this->language->get('column_product');
        $data['column_model'] = $this->language->get('column_model');
        $data['column_quantity'] = $this->language->get('column_quantity');
        $data['column_price'] = $this->language->get('column_price');
        $data['column_total'] = $this->language->get('column_total');

        $this->load->model('sale/order');
        $this->load->model('sale/orderq');
        $this->load->model('setting/setting');

        $json['orders'] = array();

        $orders = array();

        $temporders = $this->model_sale_orderq->getLatestOrders($this->request->post['customer_id']);

        foreach ($temporders as $key => $value) {
            $orders[] = $value['order_id'];
        }

        foreach ($orders as $order_id) {
            $order_info = $this->model_sale_order->getOrder($order_id);

            if ($order_info) {
                $store_info = $this->model_setting_setting->getSetting('config', $order_info['store_id']);

                if ($store_info) {
                    $store_address = $store_info['config_address'];
                    $store_email = $store_info['config_email'];
                    $store_telephone = $store_info['config_telephone'];
                    $store_fax = $store_info['config_fax'];
                } else {
                    $store_address = $this->config->get('config_address');
                    $store_email = $this->config->get('config_email');
                    $store_telephone = $this->config->get('config_telephone');
                    $store_fax = $this->config->get('config_fax');
                }

                if ($order_info['invoice_no']) {
                    $invoice_no = $order_info['invoice_prefix'] . $order_info['invoice_no'];
                } else {
                    $invoice_no = '';
                }

                if ($order_info['payment_address_format']) {
                    $format = $order_info['payment_address_format'];
                } else {
                    $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
                }

                $find = array(
                    '{firstname}',
                    '{lastname}',
                    '{company}',
                    '{address_1}',
                    '{address_2}',
                    '{city}',
                    '{postcode}',
                    '{zone}',
                    '{zone_code}',
                    '{country}'
                );

                $replace = array(
                    'firstname' => $order_info['payment_firstname'],
                    'lastname' => $order_info['payment_lastname'],
                    'company' => $order_info['payment_company'],
                    'address_1' => $order_info['payment_address_1'],
                    'address_2' => $order_info['payment_address_2'],
                    'city' => $order_info['payment_city'],
                    'postcode' => $order_info['payment_postcode'],
                    'zone' => $order_info['payment_zone'],
                    'zone_code' => $order_info['payment_zone_code'],
                    'country' => $order_info['payment_country']
                );

                $payment_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

                if ($order_info['shipping_address_format']) {
                    $format = $order_info['shipping_address_format'];
                } else {
                    $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
                }

                $find = array(
                    '{firstname}',
                    '{lastname}',
                    '{company}',
                    '{address_1}',
                    '{address_2}',
                    '{city}',
                    '{postcode}',
                    '{zone}',
                    '{zone_code}',
                    '{country}'
                );

                $replace = array(
                    'firstname' => $order_info['shipping_firstname'],
                    'lastname' => $order_info['shipping_lastname'],
                    'company' => $order_info['shipping_company'],
                    'address_1' => $order_info['shipping_address_1'],
                    'address_2' => $order_info['shipping_address_2'],
                    'city' => $order_info['shipping_city'],
                    'postcode' => $order_info['shipping_postcode'],
                    'zone' => $order_info['shipping_zone'],
                    'zone_code' => $order_info['shipping_zone_code'],
                    'country' => $order_info['shipping_country']
                );

                $shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

                $this->load->model('tool/upload');

                $product_data = array();

                $products = $this->model_sale_order->getOrderProducts($order_id);

                foreach ($products as $product) {
                    $option_data = array();

                    $options = $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);

                    foreach ($options as $option) {
                        if ($option['type'] != 'file') {
                            $value = $option['value'];
                        } else {
                            $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

                            if ($upload_info) {
                                $value = $upload_info['name'];
                            } else {
                                $value = '';
                            }
                        }

                        $option_data[] = array(
                            'name' => $option['name'],
                            'value' => $value
                        );
                    }

                    $product_data[] = array(
                        'name' => $product['name'],
                        'model' => $product['model'],
                        'option' => $option_data,
                        'quantity' => $product['quantity'],
                        'price' => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                        'total' => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
                    );
                }

                $voucher_data = array();

                $vouchers = $this->model_sale_order->getOrderVouchers($order_id);

                foreach ($vouchers as $voucher) {
                    $voucher_data[] = array(
                        'description' => $voucher['description'],
                        'amount' => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
                    );
                }

                $total_data = array();

                $totals = $this->model_sale_order->getOrderTotals($order_id);

                foreach ($totals as $total) {
                    $total_data[] = array(
                        'title' => $total['title'],
                        'text' => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value'])
                    );
                }

                $json['orders'][] = array(
                    'order_id' => $order_id,
                    'invoice_no' => $invoice_no,
                    'date_added' => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
                    'store_name' => $order_info['store_name'],
                    'store_url' => rtrim(str_replace('http:', 'https:', $order_info['store_url']), '/'),
                    'store_address' => nl2br($store_address),
                    'store_email' => $store_email,
                    'store_telephone' => $store_telephone,
                    'store_fax' => $store_fax,
                    'email' => $order_info['email'],
                    'telephone' => $order_info['telephone'],
                    'shipping_address' => $shipping_address,
                    'shipping_method' => $order_info['shipping_method'],
                    'payment_address' => $payment_address,
                    'payment_method' => $order_info['payment_method'],
                    'product' => $product_data,
                    'voucher' => $voucher_data,
                    'total' => $total_data,
                    'comment' => nl2br($order_info['comment'])
                );
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function saveaddress() {
        $this->load->language('sale/orderq');
        $this->load->model("sale/orderq");
        $json = array();
        if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
            $json['error']['firstname'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
            $json['error']['lastname'] = $this->language->get('error_lastname');
        }

        if ((utf8_strlen(trim($this->request->post['address_1'])) < 3) || (utf8_strlen(trim($this->request->post['address_1'])) > 128)) {
            $json['error']['address_1'] = $this->language->get('error_address_1');
        }

        if ((utf8_strlen(trim($this->request->post['city'])) < 2) || (utf8_strlen(trim($this->request->post['city'])) > 128)) {
            $json['error']['city'] = $this->language->get('error_city');
        }

        $this->load->model('localisation/country');

        $country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

        if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($this->request->post['postcode'])) < 2 || utf8_strlen(trim($this->request->post['postcode'])) > 10)) {
            $json['error']['postcode'] = $this->language->get('error_postcode');
        }

        if ($this->request->post['country_id'] == '') {
            $json['error']['country'] = $this->language->get('error_country');
        }

        if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '' || !is_numeric($this->request->post['zone_id'])) {
            $json['error']['zone'] = $this->language->get('error_zone');
        }

        // Custom field validation
        $custom_fields = $this->model_sale_orderq->getCustomFields($this->request->post['customer_group_id']);

        foreach ($custom_fields as $custom_field) {
            if (($custom_field['location'] == 'address') && $custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['custom_field_id']])) {
                $json['error']['custom_field' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
            } elseif (($custom_field['location'] == 'address') && ($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !filter_var($this->request->post['custom_field'][$custom_field['custom_field_id']], FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $custom_field['validation'])))) {
                $json['error']['custom_field' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
            }
        }

        if (!$json) {
            $this->model_sale_orderq->editAddress($this->request->post['address_id'], $this->request->post['customer_id'], $this->request->post);
            $json['success'] = "Address details saved";
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
