<?php
class ControllerSaleAccount extends Controller {

    private $error = array();

    public function index() {
        $this->language->load('sale/account');
        $this->load->model('sale/account');

        $data['token'] = $this->session->data['token'];

        $this->document->setTitle($this->language->get('heading_title'));
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_download'] = $this->language->get('button_download');
        $data['entry_date_from'] = $this->language->get('entry_date_from');
        $data['entry_date_to'] = $this->language->get('entry_date_to');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

            $this->session->data['success'] = $this->language->get('text_success');
            $url = '';
        }

        if (isset($this->session->data['warning'])) {
            $data['warning'] = $this->session->data['warning'];

            unset($this->session->data['warning']);
        } else {
            $data['warning'] = '';
        }

        if (!isset($this->session->data['date_from'])) {
            $data['date_from'] = date("Y-m-d", strtotime("-8 days"));
        } else {
            $data['date_from'] = $this->session->data['date_from'];
        }

        if (!isset($this->session->data['date_to'])) {
            $data['date_to'] = date("Y-m-d", strtotime("-1 days"));
        } else {
            $data['date_to'] = $this->session->data['date_to'];
        }

        $data['breadcrumbs'] = array();

        //$data['filter_name'] = $filter_name;

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('sale/account', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['export_link'] = $this->url->link('sale/account/export', 'token=' . $this->session->data['token'], 'SSL');
        $data['download'] = $this->url->link('sale/account/export', 'token=' . $this->session->data['token'], 'SSL');

        $data['heading_title'] = $this->language->get('heading_title');
        $data['button_export'] = $this->language->get('button_export');
        $data['column_wishlist_id'] = $this->language->get('column_wishlist_id');
        $data['column_customer_name'] = $this->language->get('column_customer_name');
        $data['column_customer_email'] = $this->language->get('column_customer_email');
        $data['column_customer_phone'] = $this->language->get('column_customer_phone');
        $data['column_product_id'] = $this->language->get('column_product_id');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_notes'] = $this->language->get('column_notes');
        $data['column_action'] = $this->language->get('column_action');
        $data['column_product_name'] = $this->language->get('column_product_name');
        $data['column_product_quantity'] = $this->language->get('column_product_quantity');
        $data['text_add_note'] = $this->language->get('text_add_note');

        if (isset($this->request->get['filter_total'])) {
            $filter_total = $this->request->get['filter_total'];
        } else {
            $filter_total = null;
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total=' . $this->request->get['filter_total'];
        }

        $total_data = $this->model_sale_account->getTotalFeild();

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
		
		$data['page'] = 'step1';

//        $pagination = new Pagination();
//        $pagination->total = $total_data;
//        $pagination->page = $page;
//        $pagination->limit = $this->config->get('config_admin_limit');
//        $pagination->text = $this->language->get('text_pagination');
//        $pagination->url = $this->url->link('sale/wishlist', 'token=' . $this->session->data['token'] . '&page={page}', 'SSL');
//
//        $data['pagination'] = $pagination->render();
		
		$this->load->model('sale/account');
        $data['export_button'] = $this->model_sale_account->checkrecords();
		if($data['export_button'] == 1){
			$data['export_dates'] =  $this->model_sale_account->firstlastrecords();
			
		}
        $file = DIR_LOGS . $this->config->get('config_error_filename');

        if (file_exists($file)) {
            $data['log'] = file_get_contents($file, FILE_USE_INCLUDE_PATH, null);
        } else {
            $data['log'] = '';
        }
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
	
		$this->response->setOutput($this->load->view('sale/account.tpl', $data));
		
      /*  $this->template = 'sale/account.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());*/
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'sale/wishlist')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 
     */
   
   public function calculateDays() {
		
		$this->load->model('sale/account');
        $this->model_sale_account->trancatedb();
		$json['date_form'] = $this->request->post['date_from']." 00:00:00";
		$json['date_to'] =$this->request->post['date_to']." 23:59:59";
	       
		$date1 = new DateTime($this->request->post['date_from']);
		$date2 = new DateTime($this->request->post['date_to']);
		
		$json['days'] = $date2->diff($date1)->format("%a"); 
		
		$json['weeks'] = ceil($json['days']/4);//$date2->diff($date1)->format("%a");
		$json['progress'] = 100/$json['weeks'];
		$this->response->setOutput(json_encode($json));   
		   
   }
    
	public function init() {
		
		
        $numberofdaysfrom =  $this->request->post['weeknumber']*4;
		
		$numberofdaysto =  $numberofdaysfrom + 4;
		$date_from =  $this->request->post['date_from']." 00:00:00";
		$date_to_post = $this->request->post['date_to']." 23:59:59";
		$date_to =  date('Y-m-d', strtotime($date_from. ' + '.$numberofdaysto.' days'))." 23:59:59";
		if($numberofdaysfrom >0){ 
			$date_from =  date('Y-m-d', strtotime($date_from. ' + '.($numberofdaysfrom + 1).' days'))." 00:00:00";
		}
		
		
		if($date_to >$date_to_post){
			$date_to = $date_to_post;	
		}
		
		
//      echo "date from $date_from and date to is $date_to";die;
       
	   $this->load->model('sale/account');
       $this->model_sale_account->insertDB($date_from, $date_to);
       $data['progress'] = 'inprogress';
	   $this->response->setOutput(json_encode($data)); 
	   //echo $str;
    }
    /*public function export() {
		
	    $date_to =  $this->request->post['date_to']." 23:59:59";
        $date_from =  $this->request->post['date_from']." 00:00:00";
//        echo "date from $date_from and date to is $date_to";die;
        $this->load->model('sale/account');
        $list_data = $this->model_sale_account->exportExcel($date_from, $date_to);
        $final_data = array();
        foreach($list_data as $order){
            $taxes_data = $this->model_sale_account->getTotal($order['order_id'],'tax');
            $shipping_data = $this->model_sale_account->getTotal($order['order_id'],'shipping');
            $total_product_cost = $this->model_sale_account->getTotal($order['order_id'],'sub_total');
			$total_voucher_amount = $this->model_sale_account->getTotal($order['order_id'],'voucher');
            $product_ids = $this->model_sale_account->getTotalProducts($order['order_id']);
            $order_status = $this->model_sale_account->getOrderStatus($order['order_id']);
            $final_price = 0;
//            $product_quantity = $this->
            foreach($product_ids as $product_id){
                 $final_price+= $this->model_sale_account->geProductCalculatedPriceWithouDiscount($product_id, $order['order_id']);
            }
            $final_data[$order['order_id']] = $order;
            $final_data[$order['order_id']]['total_product_cost'] = ($total_product_cost) ? $total_product_cost['value'] : 0;
            $final_data[$order['order_id']]['total_voucher_amount'] = ($total_voucher_amount) ? $total_voucher_amount['value'] : 0;
			$final_data[$order['order_id']]['date'] = date("Y-m-d", strtotime($order['date']));
            $final_data[$order['order_id']]['total_tax'] = ($taxes_data) ? $taxes_data['value'] : 0;
            $final_data[$order['order_id']]['total_shipping'] = ($shipping_data) ? $shipping_data['value'] : 0;
            $final_data[$order['order_id']]['total_paid'] = $final_price;
            $final_data[$order['order_id']]['order_status'] = $order_status;
        }
		
        $this->processData($final_data);
    }*/
	
	public function export() {
		
        $this->load->model('sale/account');
        $list_data = $this->model_sale_account->exportExcel(0,0);
        $final_data = array();
        foreach($list_data as $order){
			 $final_data[$order['order_id']] = $order;
        }
		
        $this->processData($final_data);
    }
	
	
    private function processData($results) {
        $file = date('m-d-Y') . '_' . uniqid() . rand(0, 4500);
        $file = $file . '.xls';
        $headers =  array(
                    "Order ID", 
                    "Date", 
                    "Customer Name",
                    "Currency",
                    "Customer email",
                    "Customer Phone",	
                    "Delivery Address",
                    "Delivery City",
                    "Delivery State",
					"Delivery Country",
                    "Payment Address",
                    "Payment City",
                    "Payment State",
					"Payment Country",
                    "Customer Resale",
					"customer group",
                    "Total Paid",
                    "Total Paid for Products (Tax Excl)",
					"Voucher Amount",
                    "Total Tax",
                    "Total Shipping",
                    "Total Product Cost",
                    "Order Status",
                    "Payment Method",
                    "Store Name",
                    "Item Count",
                    "Refund Amount",
                    "Return Cost");
        $fp = fopen(DIR_DOWNLOAD. $file, 'w'); 
        fputcsv($fp, $headers, ";", '"');

        foreach ($results as $fields) {
            fputcsv($fp, $fields, ";", '"');
        }

        fclose($fp);

        $file = DIR_DOWNLOAD . $file; //path to the file on disk
        if (file_exists($file)) {

            //set appropriate headers
            header('Content-Description: File Transfer');
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename=' . basename($file));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            // ob_clean();
            flush();

            //read the file from disk and output the content.
            readfile($file);
            exit;
        }
    }

}

?>