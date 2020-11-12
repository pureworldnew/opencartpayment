<?php 
class ControllerToolGoogleProductFeedCsv extends Controller { 
	
	public function index() {		
		$this->load->language('tool/google_product_feed_csv');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['button_download'] = $this->language->get('button_download');
		$this->data['entry_date_from'] = $this->language->get('entry_date_from');
		$this->data['entry_date_to'] = $this->language->get('entry_date_to');

		if (isset($this->session->data['warning'])) {
			$this->data['warning'] = $this->session->data['warning'];
		
			unset($this->session->data['warning']);
		} else {
			$this->data['warning'] = '';
		}
		
		if (!isset($this->session->data['date_from'])) {
			$this->data['date_from'] = date("Y-m-d", strtotime("-8 days"));
		}
		else {
			$this->data['date_from'] = $this->session->data['date_from'];
		}
		
		if (!isset($this->session->data['date_to'])) {
			$this->data['date_to'] = date("Y-m-d", strtotime("-1 days"));
		}
		else {
			$this->data['date_to'] = $this->session->data['date_to'];
		}
		
 		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),       		
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('tool/error_log', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['download'] = $this->url->link('tool/google_product_feed_csv/download', 'token=' . $this->session->data['token'], 'SSL');
		
		$file = DIR_LOGS . $this->config->get('config_error_filename');
		
		if (file_exists($file)) {
			$this->data['log'] = file_get_contents($file, FILE_USE_INCLUDE_PATH, null);
		} else {
			$this->data['log'] = '';
		}

		$this->template = 'tool/google_product_feed_csv.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	public function download() {
		
		$this->load->language('tool/google_product_feed_csv');
		$this->load->model('tool/google_product_feed_csv');
		
    	if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$visitor_data = $this->model_tool_google_product_feed_csv->getVisitorData($this->request->post);
			
			if($visitor_data) {
				// create the download
				$csv = 'IP Address, User Agent, Date, Time, Referrer, Product ID, Product Name, Source, Grouping' . "\n";
				foreach ($visitor_data as $row) {
					$product = $this->model_tool_google_product_feed_csv->getProduct($row['product_id']);
					$csv .= $row['visitor_ip'] . ',"' . $row['visitor_user_agent'] . '",' . $row['visitor_date'] . ',' . $row['visitor_hour'] . ':' . $row['visitor_minute'] . ',' . $row['visitor_referrer'] . ',' . $row['product_id'] . ',' . $product['name'].  ',' . $row['source'] . ',' . $row['grouping'] . "\n";
				}
				
				header("Content-Type: text/csv;"); 
				header("Cache-Control: public");
				header("Content-Description: File Transfer");
				header("Content-Transfer-Encoding: binary\n");
				header('Content-Disposition: attachment; filename="visitors.csv"');
				
				echo $csv;
				exit;
			} else {
				// write 'no data' warning
				$this->session->data['warning'] = $this->language->get('text_warning_no_data');
				$this->session->data['date_from'] = $this->request->post['date_from'];
				$this->session->data['date_to'] = $this->request->post['date_to'];
				
			}
		}
		
		
		$this->response->redirect($this->url->link('tool/google_product_feed_csv', 'token=' . $this->session->data['token'], 'SSL'));		
		
	}
}
?>