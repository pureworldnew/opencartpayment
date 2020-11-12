<?php

class ControllerOneCheckoutCartModule extends Controller {

    public function index() {
        $this->language->load('onecheckout/checkout');

        //coupon
        $this->data['coupon_status'] = $this->config->get('coupon_status');
        if ($this->config->get('coupon_status')) {
            $this->data['coupon_heading_title'] = $this->language->get('heading_title_coupon');
            $this->data['entry_coupon'] = $this->language->get('entry_coupon');
            $this->data['button_coupon'] = $this->language->get('button_coupon');

            if (isset($this->session->data['coupon'])) {
                $this->data['coupon'] = $this->session->data['coupon'];
            } else {
                $this->data['coupon'] = '';
            }
        }
        //reward
        if ($this->config->get('reward_status')) {
            $points = $this->customer->getRewardPoints();
            $points_total = 0;
            foreach ($this->cart->getProducts() as $product) {
                if ($product['points']) {
                    $points_total += $product['points'];
                }
            }

            if ($points && $points_total && $this->config->get('reward_status')) {
                $this->data['reward_status'] = true;
                $this->data['reward_heading_title'] = sprintf($this->language->get('heading_title_reward'), $points);
                $this->data['entry_reward'] = sprintf($this->language->get('entry_reward'), $points_total);
                $this->data['button_reward'] = $this->language->get('button_reward');

                if (isset($this->session->data['reward'])) {
                    $this->data['reward'] = $this->session->data['reward'];
                } else {
                    $this->data['reward'] = '';
                }
            } else {
                $this->data['reward_status'] = false;
            }
        } else {
            $this->data['reward_status'] = false;
        }
        //voucher
        $this->data['voucher_status'] = $this->config->get('voucher_status');
        if ($this->config->get('voucher_status')) {
            $this->data['voucher_heading_title'] = $this->language->get('heading_title_voucher');
            $this->data['entry_voucher'] = $this->language->get('entry_voucher');
            $this->data['button_voucher'] = $this->language->get('button_voucher');

            if (isset($this->session->data['voucher'])) {
                $this->data['voucher'] = $this->session->data['voucher'];
            } else {
                $this->data['voucher'] = '';
            }
        }
        if ($this->session->data['shipping_zone_id'] == 3624) {
            $this->data['resale_status'] = 1;
        } else {
            $this->data['resale_status'] = 0;
        }

        if ($this->config->get('voucher_status')) {
            $this->data['voucher_heading_title'] = $this->language->get('heading_title_voucher');
            $this->data['entry_voucher'] = $this->language->get('entry_voucher');
            $this->data['button_voucher'] = $this->language->get('button_voucher');

            if (isset($this->session->data['resale_id_number'])) {
                $this->data['resale_id_number'] = $this->session->data['resale_id_number'];
            } else {
                $this->data['resale_id_number'] = '';
            }
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/onecheckout/cartmodule.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/onecheckout/cartmodule.tpl';
        } else {
            $this->template = 'default/template/onecheckout/cartmodule.tpl';
        }

        $this->render();
    }

    public function validateCoupon() {
        $this->load->model('onecheckout/checkout');
        $this->language->load('onecheckout/checkout');
        $json = array();
        if (!$this->cart->hasProducts()) {
            $json['redirect'] = $this->url->link('checkout/cart');
        }

        if (isset($this->request->post['coupon'])) {
            $this->load->model('checkout/coupon');
            $coupon_info = $this->model_checkout_coupon->getCoupon($this->request->post['coupon']);

            if ($coupon_info) {
                $this->session->data['coupon'] = $this->request->post['coupon'];
                $this->session->data['success'] = $this->language->get('text_success_coupon');
                $json['redirect'] = $this->url->link('checkout/cart', '', 'SSL');
            } else {
                $json['error'] = $this->language->get('error_coupon');
            }
        }

        $this->response->setOutput($this->model_onecheckout_checkout->jsonencode($json));
    }

    public function validateVoucher() {
        $this->load->model('onecheckout/checkout');
        $this->language->load('onecheckout/checkout');

        $json = array();

        if (!$this->cart->hasProducts()) {
            $json['redirect'] = $this->url->link('checkout/cart');
        }

        if (isset($this->request->post['voucher'])) {
            $this->load->model('checkout/voucher');

            $voucher_info = $this->model_checkout_voucher->getVoucher($this->request->post['voucher']);

            if ($voucher_info) {
                $this->session->data['voucher'] = $this->request->post['voucher'];

                $this->session->data['success'] = $this->language->get('text_success_voucher');

                $json['redirect'] = $this->url->link('checkout/cart', '', 'SSL');
            } else {
                $json['error'] = $this->language->get('error_voucher');
            }
        }
        echo json_encode($json);
        die;
        $this->response->setOutput($this->model_onecheckout_checkout->jsonencode($json));
    }

    public function validateResale() {
        $this->load->model('onecheckout/checkout');
        $this->language->load('onecheckout/checkout');
        $json = array();
		$flag = 0;
        if (!$this->cart->hasProducts()) {
            $json['redirect'] = $this->url->link('checkout/cart');
        }
        if (isset($this->request->post['resale_id_number'])) {
            if ($this->request->post['resale_id_number'] == "") {
                $json['error'] = $this->language->get('error_resale_number');
				$json['error'] = $this->language->get('error_resale_number');
            } else {
				if ((utf8_strlen($this->request->post['resale_id_number']) > 14)) {
					$json['error'] = $this->language->get('error_resale_number');
					$json['error_html'] = str_replace("\n","<br>",$this->language->get('error_resale_number'));
				}else{
					if(preg_match('/^[A-Z]{4}-[0-9]{9}$/i', $this->request->post['resale_id_number'])){
						$flag = 1;
					}else if(preg_match('/^[A-Z]{4}[0-9]{9}$/i', $this->request->post['resale_id_number'])){
						$flag = 1;
					}else if(preg_match('/^[0-9]{9}$/', $this->request->post['resale_id_number'])){
						$flag = 1;
					}else{
						$json['error'] = $this->language->get('error_resale_number');
						$json['error_html'] = str_replace("\n","<br>",$this->language->get('error_resale_number'));
					}
						
				}
				if($flag == 1){
					$json['success'] = "Success: Your Resale id Number has been applied! ";
                	$this->session->data['resale_id_number'] = $this->request->post['resale_id_number'];
                	$this->session->data['success'] = "Success: Your Resale id Number has been applied! ";
                	if($this->customer->isLogged()){
						$this->customer->setResaleNumber($this->request->post['resale_id_number']);
					}
				}
            }
        }
        $this->response->setOutput($this->model_onecheckout_checkout->jsonencode($json));
    }

    public function validateReward() {
        $this->load->model('onecheckout/checkout');
        $this->language->load('onecheckout/checkout');

        $json = array();

        if (isset($this->request->post['reward'])) {
            if (!$this->request->post['reward']) {
                $json['error'] = $this->language->get('error_empty_reward');
            }

            $points = $this->customer->getRewardPoints();

            if ($this->request->post['reward'] > $points) {
                $json['error'] = sprintf($this->language->get('error_points_reward'), $this->request->post['reward']);
            }

            $points_total = 0;

            foreach ($this->cart->getProducts() as $product) {
                if ($product['points']) {
                    $points_total += $product['points'];
                }
            }

            if ($this->request->post['reward'] > $points_total) {
                $json['error'] = sprintf($this->language->get('error_maximum_reward'), $points_total);
            }

            if (!isset($json['error'])) {
                $this->session->data['reward'] = abs($this->request->post['reward']);
                ;

                $this->session->data['success'] = $this->language->get('text_success_reward');

                $json['redirect'] = $this->url->link('checkout/cart', '', 'SSL');
            }
        }

        $this->response->setOutput($this->model_onecheckout_checkout->jsonencode($json));
    }

}

?>