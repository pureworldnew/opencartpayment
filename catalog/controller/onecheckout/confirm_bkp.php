<?php

class ControllerOneCheckoutConfirm extends Controller {

    public function lengthId($val, $from, $to) {
        $this->load->model("catalog/product");
        $data = $this->model_catalog_product->getLengthClasses($from, $to);
        foreach ($data as $newdata) {
            if ($newdata['name'] == $from) {
                $from_id = $newdata['value'];
            }
            if ($newdata['name'] == $to) {
                $to_id = $newdata['value'];
            }
        }
        $result = $this->length->convert($val, $from_id, $to_id);
        return $result;
    }

    public function weightId($val, $from, $to) {
        $this->load->model("catalog/product");
        $data = $this->model_catalog_product->getWeightClasses($from, $to);
        foreach ($data as $newdata) {
            if ($newdata['name'] == $from) {
                $from_id = $newdata['value'];
            }
            if ($newdata['name'] == $to) {
                $to_id = $newdata['value'];
            }
        }
        $result = $this->weight->convert($val, $from_id, $to_id);
        return $result;
    }
 
    public function index() {

        $json = array();
        $this->load->model('onecheckout/checkout');
        $this->language->load('onecheckout/checkout');
        if ((!$this->cart->hasProducts() && (!isset($this->session->data['vouchers']) || !$this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $json['redirect'] = $this->url->link('checkout/cart');
        }

        if (!$json) {

            $total_data = array();

            $total = 0;

            $taxes = $this->cart->getTaxes();

            $sort_order = array();

            $results = $this->model_onecheckout_checkout->getExtensions('total');


            foreach ($results as $key => $value) {

                $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
            }

            array_multisort($sort_order, SORT_ASC, $results);

            foreach ($results as $result) {
                if ($this->config->get($result['code'] . '_status')) {
                    $this->load->model('total/' . $result['code']);
                    $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
                }
            }

            $sort_order = array();

            foreach ($total_data as $key => $value) {
                $sort_order[$key] = $value['sort_order'];
            }

            array_multisort($sort_order, SORT_ASC, $total_data);

            $this->data['column_name'] = $this->language->get('column_name');

            $this->data['column_model'] = $this->language->get('column_model');

            $this->data['column_quantity'] = $this->language->get('column_quantity');

            $this->data['column_price'] = $this->language->get('column_price');

            $this->data['column_total'] = $this->language->get('column_total');

            $this->data['button_confirm'] = $this->language->get('button_confirmorder');

            $this->data['products'] = array();

            foreach ($this->cart->getProducts() as $product) {
                
                $option_data = array();
                foreach ($product['option'] as $option) {

                    if ($option['type'] != 'file') {
                        if (function_exists('utf8_strlen') && function_exists('utf8_substr')) {

                            //Skip these attributes
//                            if ($option['name'] == 'Units' ||
//                                    $option['name'] == "Select Overall length" ||
//                                    $option['name'] == "Select width" ||
//                                    $option['name'] == "Weight" ||
//                                    $option['name'] == "Select weight"
//                            ) {
//                                continue;
//                            }

                            $option_data[] = array(
                                'name' => $option['name'],
                                'value' => (utf8_strlen($option['option_value']) > 20 ? utf8_substr($option['option_value'], 0, 20) . '..' : $option['option_value'])
                            );
                        } else {

                            $option_data[] = array(
                                'name' => $option['name'],
                                'value' => (strlen($option['option_value']) > 20 ? substr($option['option_value'], 0, 20) . '..' : $option['option_value'])
                            );
                        }
                    } else {
                        $this->load->library('encryption');

                        $encryption = new Encryption($this->config->get('config_encryption'));

                        if (function_exists('utf8_strlen') && function_exists('utf8_strrpos') && function_exists('utf8_substr')) {
                            $file = utf8_substr($encryption->decrypt($option['option_value']), 0, utf8_strrpos($encryption->decrypt($option['option_value']), '.'));
                            $option_data[] = array(
                                'name' => $option['name'],
                                'value' => (utf8_strlen($file) > 20 ? utf8_substr($file, 0, 20) . '..' : $file)
                            );
                        } else {
                            $file = substr($encryption->decrypt($option['option_value']), 0, strrpos($encryption->decrypt($option['option_value']), '.'));
                            $option_data[] = array(
                                'name' => $option['name'],
                                'value' => (strlen($file) > 20 ? substr($file, 0, 20) . '..' : $file)
                            );
                        }
                    }
                }
                
                //Convert attributes units
//                $to = "";
//                $to_weight = "";
//                //Units to be converted to
//                foreach ($product['option'] as $option) {
//                    if ($option['name'] == 'Units') {
//                        $to = $option['option_value'];
//                        $option_data[] = array(
//                            'name' => $option['name'],
//                            'value' => $option['option_value']
//                        );
//                        continue;
//                    }
//                    //units to be converted from
//                    if ($option['name'] == "Select Overall length") {
//                        $a = $option['option_value'];
//                        $val = (int) $a;
//                        $b = explode($val, $a);
//                        $from = $b[1];
//                        $value_length = $this->lengthId($val, $from, $to);
//                        $option_data[] = array(
//                            'name' => $option['name'],
//                            'value' => round($value_length, 2) . $to
//                        );
//                    }
//                    if ($option['name'] == "Select width") {
//                        $a = $option['option_value'];
//                        $val = (double) $a;
//                        $b = explode($val, $a);
//                        $from = $b[1];
//                        $value_length = $this->lengthId($val, $from, $to);
//                        $option_data[] = array(
//                            'name' => $option['name'],
//                            'value' => round($value_length, 2) . $to
//                        );
//                    }
//
//                    //Weight class convertor
//                    if ($option['name'] == 'Weight') {
//                        $to_weight = $option['option_value'];
//                        $option_data[] = array(
//                            'name' => $option['name'],
//                            'value' => $option['option_value']
//                        );
//                        continue;
//                    }
//                    //units to be converted from
//                    if ($option['name'] == "Select weight") {
//                        $a = $option['option_value'];
//                        $val = (double) $a;
//                        $b = explode($val, $a);
//                        $from = $b[1];
//                        $value_length = $this->weightId($val, $from, $to_weight);
//                        $option_data[] = array(
//                            'name' => $option['name'],
//                            'value' => round($value_length, 2) . $to_weight
//                        );
//                    }
//                }

                
                
                $version_int = $this->model_onecheckout_checkout->versiontoint();
                //version
                if ($version_int < 1513 && $version_int >= 1500) {
                    $version_tax = $this->tax->getRate($product['tax_class_id']);
                } elseif ($version_int >= 1513) {
                    $version_tax = $this->tax->getTax($product['price'], $product['tax_class_id']);
                }
                $this->data['products'][] = array(
                    'product_id' => $product['product_id'],
                    'name' => $product['name'],
                    'model' => $product['model'],
                    'option' => $option_data,
                    'quantity' => $product['quantity'],
                    'subtract' => $product['subtract'],
                    'tax' => $version_tax,
                    'price' => ($version_int < 1540) ? $this->currency->format($product['price']) : $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))),
                    'total' => ($version_int < 1540) ? $this->currency->format($product['total']) : $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']),
                    'href' => $this->url->link('product/product', 'product_id=' . $product['product_id'])
                );
            }



            // Gift Voucher

            $this->data['vouchers'] = array();

            if (isset($this->session->data['vouchers']) && $this->session->data['vouchers']) {

                foreach ($this->session->data['vouchers'] as $key => $voucher) {

                    $this->data['vouchers'][] = array(
                        'description' => $voucher['description'],
                        'amount' => $this->currency->format($voucher['amount'])
                    );
                }
            }

            $this->data['totals'] = $total_data;

            if (isset($this->session->data['success'])) {
                $this->data['success'] = $this->session->data['success'];
                unset($this->session->data['success']);
            } else {
                $this->data['success'] = '';
            }
            //cart module
            $this->data['cartmodule'] = $this->getChild('onecheckout/cartmodule');

            if ($this->config->get('config_checkout_id')) {
                $information_info = $this->model_onecheckout_checkout->getInformation($this->config->get('config_checkout_id'));

                if ($information_info) {

                    $this->data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/info', 'information_id=' . $this->config->get('config_checkout_id'), 'SSL'), $information_info['title'], $information_info['title']);
                } else {

                    $this->data['text_agree'] = '';
                }
            } else {

                $this->data['text_agree'] = '';
            }



            if (isset($this->session->data['agree'])) {

                $this->data['agree'] = $this->session->data['agree'];
            } else {

                $this->data['agree'] = '';
            }


            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/onecheckout/confirm.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/onecheckout/confirm.tpl';
            } else {
                $this->template = 'default/template/onecheckout/confirm.tpl';
            }

            $json['total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total));

            $json['output'] = $this->render();
        }

        $this->response->setOutput($this->model_onecheckout_checkout->jsonencode($json));
    }

    public function createorder() {

        $json = array();

        if ((!$this->cart->hasProducts() && (!isset($this->session->data['vouchers']) || !$this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {

            $json['redirect'] = $this->url->link('checkout/cart');
        }

        $this->load->model('onecheckout/checkout');
        $version_int = $this->model_onecheckout_checkout->versiontoint();

        if ($this->customer->isLogged()) {

            $payment_address = $this->model_onecheckout_checkout->getAddress($this->session->data['payment_address_id']);
        } elseif (isset($this->session->data['guest']['payment'])) {

            $payment_address = $this->session->data['guest']['payment'];
        }

        if (!isset($payment_address)) {

            $json['redirect'] = $this->url->link('onecheckout/checkout', '', 'SSL');
        }

        if (!isset($this->session->data['payment_method'])) {

            $json['redirect'] = $this->url->link('onecheckout/checkout', '', 'SSL');
        }

        if ($this->cart->hasShipping()) {


            if ($this->customer->isLogged()) {

                $shipping_address = $this->model_onecheckout_checkout->getAddress($this->session->data['shipping_address_id']);
            } elseif (isset($this->session->data['guest']['shipping'])) {

                $shipping_address = $this->session->data['guest']['shipping'];
            }



            if (!isset($shipping_address)) {

                $json['redirect'] = $this->url->link('onecheckout/checkout', '', 'SSL');
            }



            if (!isset($this->session->data['shipping_method'])) {

                $json['redirect'] = $this->url->link('onecheckout/checkout', '', 'SSL');
            }
        } else {

            unset($this->session->data['guest']['shipping']);

            unset($this->session->data['shipping_address_id']);

            unset($this->session->data['shipping_method']);

            unset($this->session->data['shipping_methods']);
        }

        if (!$json) {

            $total_data = array();

            $total = 0;

            $taxes = $this->cart->getTaxes();

            $sort_order = array();


            $results = $this->model_onecheckout_checkout->getExtensions('total');

            foreach ($results as $key => $value) {
                $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
            }

            array_multisort($sort_order, SORT_ASC, $results);


            foreach ($results as $result) {

                if ($this->config->get($result['code'] . '_status')) {

                    $this->load->model('total/' . $result['code']);


                    $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
                }
            }

            $sort_order = array();

            foreach ($total_data as $key => $value) {

                $sort_order[$key] = $value['sort_order'];
            }

            array_multisort($sort_order, SORT_ASC, $total_data);

            $data = array();

            $data['invoice_prefix'] = $this->config->get('config_invoice_prefix');

            $data['store_id'] = $this->config->get('config_store_id');

            $data['store_name'] = $this->config->get('config_name');

            if ($data['store_id']) {

                $data['store_url'] = $this->config->get('config_url');
            } else {

                $data['store_url'] = HTTP_SERVER;
            }

            if ($this->customer->isLogged()) {

                $data['customer_id'] = $this->customer->getId();

                $data['customer_group_id'] = $this->customer->getCustomerGroupId();

                $data['firstname'] = $this->customer->getFirstName();

                $data['lastname'] = $this->customer->getLastName();

                $data['email'] = $this->customer->getEmail();

                $data['telephone'] = $this->customer->getTelephone();

                $data['fax'] = $this->customer->getFax();


                $payment_address = $this->model_onecheckout_checkout->getAddress($this->session->data['payment_address_id']);

                if ($version_int >= 1530) {
                    $payment_address = array();
                    $this->load->model('account/address');
                    $payment_address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);
                }
            } elseif (isset($this->session->data['guest'])) {

                $data['customer_id'] = 0;

                $data['customer_group_id'] = isset($this->session->data['guest']['customer_group_id']) ? $this->session->data['guest']['customer_group_id'] : $this->config->get('config_customer_group_id');

                $data['firstname'] = $this->session->data['guest']['firstname'];

                $data['lastname'] = $this->session->data['guest']['lastname'];

                $data['email'] = $this->session->data['guest']['email'];

                $data['telephone'] = $this->session->data['guest']['telephone'];

                $data['fax'] = $this->session->data['guest']['fax'];

                $payment_address = $this->session->data['guest']['payment'];
            }


            $data['payment_firstname'] = $payment_address['firstname'];

            $data['payment_lastname'] = $payment_address['lastname'];

            $data['payment_company'] = $payment_address['company'];

            $data['payment_company_id'] = isset($payment_address['company_id']) ? $payment_address['company_id'] : '';
            $data['payment_tax_id'] = isset($payment_address['tax_id']) ? $payment_address['tax_id'] : '';

            $data['payment_address_1'] = $payment_address['address_1'];

            $data['payment_address_2'] = $payment_address['address_2'];

            $data['payment_city'] = $payment_address['city'];

            $data['payment_postcode'] = $payment_address['postcode'];

            $data['payment_zone'] = $payment_address['zone'];

            $data['payment_zone_id'] = $payment_address['zone_id'];

            $data['payment_country'] = $payment_address['country'];

            $data['payment_country_id'] = $payment_address['country_id'];

            $data['payment_address_format'] = $payment_address['address_format'];


            if (isset($this->session->data['payment_method']['title'])) {

                $data['payment_method'] = $this->session->data['payment_method']['title'];
            } else {

                $data['payment_method'] = '';
            }

            if (isset($this->session->data['payment_method']['code'])) {
                $data['payment_code'] = $this->session->data['payment_method']['code'];
            } else {
                $data['payment_code'] = '';
            }


            if ($this->cart->hasShipping()) {

                if ($this->customer->isLogged()) {


                    $shipping_address = $this->model_onecheckout_checkout->getAddress($this->session->data['shipping_address_id']);
                } elseif (isset($this->session->data['guest']['shipping'])) {

                    $shipping_address = $this->session->data['guest']['shipping'];
                }

                $data['shipping_firstname'] = $shipping_address['firstname'];

                $data['shipping_lastname'] = $shipping_address['lastname'];

                $data['shipping_company'] = $shipping_address['company'];

                $data['shipping_address_1'] = $shipping_address['address_1'];

                $data['shipping_address_2'] = $shipping_address['address_2'];

                $data['shipping_city'] = $shipping_address['city'];

                $data['shipping_postcode'] = $shipping_address['postcode'];

                $data['shipping_zone'] = $shipping_address['zone'];

                $data['shipping_zone_id'] = $shipping_address['zone_id'];

                $data['shipping_country'] = $shipping_address['country'];

                $data['shipping_country_id'] = $shipping_address['country_id'];

                $data['shipping_address_format'] = $shipping_address['address_format'];



                if (isset($this->session->data['shipping_method']['title'])) {

                    $data['shipping_method'] = $this->session->data['shipping_method']['title'];
                } else {

                    $data['shipping_method'] = '';
                }

                if (isset($this->session->data['shipping_method']['code'])) {
                    $data['shipping_code'] = $this->session->data['shipping_method']['code'];
                } else {
                    $data['shipping_code'] = '';
                }
            } else {

                $data['shipping_firstname'] = '';

                $data['shipping_lastname'] = '';

                $data['shipping_company'] = '';

                $data['shipping_address_1'] = '';

                $data['shipping_address_2'] = '';

                $data['shipping_city'] = '';

                $data['shipping_postcode'] = '';

                $data['shipping_zone'] = '';

                $data['shipping_zone_id'] = '';

                $data['shipping_country'] = '';

                $data['shipping_country_id'] = '';

                $data['shipping_address_format'] = '';

                $data['shipping_method'] = '';

                $data['shipping_code'] = '';
            }


            $this->language->load('onecheckout/checkout');

            $this->load->library('encryption');


            //version
            if ($version_int < 1513 && $version_int >= 1500) {
                if ($this->cart->hasShipping()) {
                    $this->tax->setZone($shipping_address['country_id'], $shipping_address['zone_id']);
                } else {
                    $this->tax->setZone($payment_address['country_id'], $payment_address['zone_id']);
                }
            }

            $product_data = array();

            foreach ($this->cart->getProducts() as $product) {

                $option_data = array();


                foreach ($product['option'] as $option) {

                    if ($option['type'] != 'file') {

                        $option_data[] = array(
                            'product_option_id' => $option['product_option_id'],
                            'product_option_value_id' => $option['product_option_value_id'],
                            'product_option_id' => $option['product_option_id'],
                            'product_option_value_id' => $option['product_option_value_id'],
                            'option_id' => $option['option_id'],
                            'option_value_id' => $option['option_value_id'],
                            'name' => $option['name'],
                            'value' => $option['option_value'],
                            'type' => $option['type']
                        );
                    } else {

                        $encryption = new Encryption($this->config->get('config_encryption'));

                        $option_data[] = array(
                            'product_option_id' => $option['product_option_id'],
                            'product_option_value_id' => $option['product_option_value_id'],
                            'product_option_id' => $option['product_option_id'],
                            'product_option_value_id' => $option['product_option_value_id'],
                            'option_id' => $option['option_id'],
                            'option_value_id' => $option['option_value_id'],
                            'name' => $option['name'],
                            'value' => $encryption->decrypt($option['option_value']),
                            'type' => $option['type']
                        );
                    }
                }

                $version_int = $this->model_onecheckout_checkout->versiontoint();
                //version
                if ($version_int < 1513 && $version_int >= 1500) {
                    $version_tax = $this->tax->getRate($product['tax_class_id']);
                } elseif ($version_int >= 1513) {
                    $version_tax = $this->tax->getTax($product['price'], $product['tax_class_id']);
                }

                $product_data[] = array(
                    'product_id' => $product['product_id'],
                    'name' => $product['name'],
                    'model' => $product['model'],
                    'option' => $option_data,
                    'download' => $product['download'],
                    'quantity' => $product['quantity'],
                    'subtract' => $product['subtract'],
                    'price' => $product['price'],
                    'total' => $product['total'],
                    'tax' => $version_tax,
                    'reward' => isset($product['reward']) ? $product['reward'] : 0
                );
            }


            // Gift Voucher
            if ($version_int < 1520 && $version_int >= 1500) {

                if (isset($this->session->data['vouchers']) && $this->session->data['vouchers']) {
                    foreach ($this->session->data['vouchers'] as $voucher) {
                        $product_data[] = array(
                            'product_id' => 0,
                            'name' => $voucher['description'],
                            'model' => '',
                            'option' => array(),
                            'download' => array(),
                            'quantity' => 1,
                            'subtract' => false,
                            'price' => $voucher['amount'],
                            'total' => $voucher['amount'],
                            'tax' => 0
                        );
                    }
                }
                $data['reward'] = $this->cart->getTotalRewardPoints();
            } elseif ($version_int >= 1520) {

                $voucher_data = array();

                if (!empty($this->session->data['vouchers'])) {
                    foreach ($this->session->data['vouchers'] as $voucher) {
                        $voucher_data[] = array(
                            'description' => $voucher['description'],
                            'code' => substr(md5(mt_rand()), 0, 10), //substr(md5(rand()), 0, 7),
                            'to_name' => $voucher['to_name'],
                            'to_email' => $voucher['to_email'],
                            'from_name' => $voucher['from_name'],
                            'from_email' => $voucher['from_email'],
                            'voucher_theme_id' => $voucher['voucher_theme_id'],
                            'message' => $voucher['message'],
                            'amount' => $voucher['amount']
                        );
                    }
                }
                $data['vouchers'] = $voucher_data;
            }

            $data['products'] = $product_data;

            $data['totals'] = $total_data;

            $data['comment'] = $this->session->data['comment'];

            $data['total'] = $total;

            if (isset($this->request->cookie['tracking'])) {

                $affiliate_info = $this->model_onecheckout_checkout->getAffiliateByCode($this->request->cookie['tracking']);

                $subtotal = $this->cart->getSubTotal();

                if ($affiliate_info) {

                    $data['affiliate_id'] = $affiliate_info['affiliate_id'];

                    $data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
                } else {

                    $data['affiliate_id'] = 0;

                    $data['commission'] = 0;
                }
            } else {

                $data['affiliate_id'] = 0;

                $data['commission'] = 0;
            }

            $data['language_id'] = $this->config->get('config_language_id');

            $data['currency_id'] = $this->currency->getId();

            $data['currency_code'] = $this->currency->getCode();

            $data['currency_value'] = $this->currency->getValue($this->currency->getCode());

            $data['ip'] = $this->request->server['REMOTE_ADDR'];

            if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
                $data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
            } elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
                $data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
            } else {
                $data['forwarded_ip'] = '';
            }

            if (isset($this->request->server['HTTP_USER_AGENT'])) {
                $data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
            } else {
                $data['user_agent'] = '';
            }

            if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
                $data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
            } else {
                $data['accept_language'] = '';
            }

            if ($version_int < 1520 && $version_int >= 1500) {

                $this->session->data['order_id'] = $this->model_onecheckout_checkout->create($data);
                // Gift Voucher
                if (isset($this->session->data['vouchers']) && is_array($this->session->data['vouchers'])) {
                    foreach ($this->session->data['vouchers'] as $voucher) {
                        $this->model_onecheckout_checkout->addVoucher($this->session->data['order_id'], $voucher);
                    }
                }
            } elseif ($version_int >= 1520) {

                $this->load->model('checkout/order');

                if (isset($this->session->data['resale_id_number'])) {
                    $data['resale_text'] = $this->session->data['resale_id_number'];
                }


                $this->session->data['order_id'] = $this->model_checkout_order->addOrder($data);
            }

            $this->data['payment'] = $this->getChild('payment/' . $this->session->data['payment_method']['code']);
            $this->data['button_back'] = $this->language->get('button_back');


            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/onecheckout/confirmorder.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/onecheckout/confirmorder.tpl';
            } else {
                $this->template = 'default/template/onecheckout/confirmorder.tpl';
            }

            $json['output'] = $this->render();
        }

        $this->response->setOutput($this->model_onecheckout_checkout->jsonencode($json));
    }

    public function checklog() {

        $json = array();

        $this->language->load('onecheckout/checkout');
        $this->load->model('onecheckout/checkout');

        if ($this->customer->isLogged()) {

            $json['islogged'] = true;
        } else {

            $json['islogged'] = false;
        }

        if ($this->config->get('config_checkout_id')) {

            $information_info = $this->model_onecheckout_checkout->getInformation($this->config->get('config_checkout_id'));

            if ($information_info && !isset($this->request->post['agree'])) {

                $json['error']['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
            }
        }
        if (isset($this->request->post['agree']))
            $this->session->data['agree'] = $this->request->post['agree'];
        $this->response->setOutput($this->model_onecheckout_checkout->jsonencode($json));
    }

}

?>