<?php
class ControllerPaymentPaystand extends Controller {

    protected $error;
    protected $errno;
    protected $raw_response;
    protected $http_response_code;
    protected $logger;

    public function __construct($registry) {
        parent::__construct($registry);

        $this->logger = new Log('paystand.log');
    }

	public function index() {
		$this->load->language('payment/paystand');

		$data['text_payment'] = $this->language->get('text_payment');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['button_confirm'] = $this->language->get('button_confirm');

		
		$data['address'] = nl2br($this->config->get('config_address'));

        $data['continue'] = $this->url->link('checkout/success');
        
        $this->load->model('checkout/order');
        $this->load->model('localisation/country');

        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        $country_info = $this->model_localisation_country->getCountry($order_info['payment_country_id']);

        $payment_address  = $order_info['payment_address_1'];
        if( !empty($order_info['payment_address_2']) )
        {
            $payment_address  .= " " . $order_info['payment_address_2'];
        }
        $data['payer_data'] = array(
            "name"      => $order_info['firstname'] . " " . $order_info['lastname'],
            "email"     => $order_info['email'],
            "address"   => $payment_address,
            "city"      => $order_info['payment_city'],
            "state"     => $order_info['payment_zone_code'],
            "country"   => $order_info['payment_iso_code_3'],
            "postcode"  => $order_info['payment_postcode']
        );

        $billing_address = array(
			"address1" => $order_info['payment_address_1'],
			"address2" => $order_info['payment_address_2'],
			"postalCode" => $order_info['payment_postcode'],
			"city" => $order_info['payment_city'],
			"state" => $order_info['payment_zone'],
			"countryCode" => $country_info['iso_code_2'],
        );

        $data['order'] = array(
			"amount" => $order_info['total'],
			"currencyCode" => $order_info['currency_code'],
			"name" => $order_info['firstname'] . ' ' . $order_info['lastname'],
			"orderDescription" => $order_info['store_name'] . ' - ' . date('Y-m-d H:i:s'),
			"customerOrderCode" => $order_info['order_id'],
			"email" => $order_info['email'],
			"billingAddress" => json_encode($billing_address)
        );
        
        $data['publishable_key'] = $this->config->get('paystand_publishable_key');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/paystand.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/paystand.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/paystand.tpl', $data);
		}
	}

	public function confirm() {
		if ($this->session->data['payment_method']['code'] == 'paystand') {
			$this->load->language('payment/paystand');

			$this->load->model('checkout/order');

			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], 1); // 1 is order status id : pending
		}
    }
    
    public function webhook() { 
        $this->logger->write('paystand webhook endpoint was hit');

        $body = @file_get_contents('php://input');
        $json = json_decode($body);
        $this->logger->write(">>>>> body=".print_r($body, true));

        if (isset($json->resource->meta->source) && ($json->resource->meta->source == "opencart")) {
            $order_id = $json->resource->meta->order_id;

           $this->logger->write('opencart webhook identified with order id = '.$order_id);

           $this->load->model('checkout/order');

           $order = $this->model_checkout_order->getOrder($order_id);
        

            if (!empty($order)) {
               
               $order_status = $order['order_status'];
               $this->logger->write('current order status = '.$order_status);

               $base_url = 'https://api.paystand.com/v3';

               $url = $base_url . "/events/" . $json->id . "/verify";
               $publishable_key = $this->config->get('paystand_publishable_key');
               $auth_header = ["x-publishable-key: ".$publishable_key];

               $curl = $this->buildCurl("POST", $url, json_encode($json), $auth_header);
               $response = $this->runCurl($curl);
               
               $this->logger->write("http_response_code is ".$this->http_response_code);

                if (false !== $response && $this->http_response_code == 200) {
                    if ($json->resource->object = "payment") {
                        switch ($json->resource->status) {
                            case 'posted':
                                $status = 'Pending';
                                $order_status_id = 1;
                                break;
                            case 'paid':
                                $status = 'Processing';
                                $order_status_id = 2;
                                break;
                            case 'failed':
                                $status = 'Failed';
                                $order_status_id = 10;
                                break;
                            case 'canceled':
                                $status = 'Canceled';
                                $order_status_id = 7;
                                break;
                        }
                    }

                  // Update order status
                  $this->db->query("UPDATE " . DB_PREFIX . "order SET order_status_id = '" . (int)$order_status_id . "' WHERE order_id = '" . (int)$order_id . "'");
                   
                  $this->logger->write('new order status = '.$status);
                } else {
                  $this->logger->write('event verify failed');
                }
            }
        }
    }

    private function buildCurl($verb = "POST", $url, $body = "", $extheaders = null)
    {
        $headers = [
        "Content-Type: application/json",
        "Accept: application/json"
        ];

        if (null != $extheaders) {
            $headers = array_merge($headers, $extheaders);
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:32.0) Gecko/20100101 Firefox/32.0");
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $verb);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);

        return $curl;
    }

    private function runCurl($curl)
    {
        $raw_response = curl_exec($curl);
        $response = json_decode($raw_response);
        $this->error = curl_error($curl);
        $this->errno = curl_errno($curl);
        $this->raw_response = $raw_response;
        $this->http_response_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        return $response;
    }
}