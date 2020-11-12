<?php
class ControllerMarketingGiveaway extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('marketing/giveaway');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_giveaway'] = $this->language->get('text_giveaway');
		$data['text_customer_all'] = $this->language->get('text_customer_all');
		$data['text_customer'] = $this->language->get('text_customer');
		$data['text_customer_group'] = $this->language->get('text_customer_group');
		$data['text_affiliate_all'] = $this->language->get('text_affiliate_all');
		$data['text_affiliate'] = $this->language->get('text_affiliate');
		$data['text_product'] = $this->language->get('text_product');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_minimum'] = $this->language->get('entry_minimum');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_product'] = $this->language->get('entry_product');
		
		$data['button_send'] = $this->language->get('button_send');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['token'] = $this->session->data['token'];

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('marketing/giveaway', 'token=' . $this->session->data['token'], 'SSL')
		);

		$this->load->model('setting/setting');
		if ( $this->request->server['REQUEST_METHOD'] == 'POST' ) {

			$this->model_setting_setting->editSetting('giveaway', $this->request->post);
		}

		$giveaway_info = $this->model_setting_setting->getSetting('giveaway');
		$data['giveaway_status']  = $giveaway_info['giveaway_status'];
		$data['giveaway_minimum_order']  = $giveaway_info['giveaway_minimum_order'];
		$data['giveaway_height']  = $giveaway_info['giveaway_height'];
		$data['giveaway_width']  = $giveaway_info['giveaway_width'];
		$data['giveaway_product']  = $giveaway_info['giveaway_product'];
		$data['giveaway_products'] = array();
		if( !empty($data['giveaway_product']) )
		{
			foreach($data['giveaway_product'] as $giveaway_product)
			{
				$name = $this->getProductName($giveaway_product);
				$data['giveaway_products'][] = array("product_id" => $giveaway_product, "name" => $name);
			}
		}
		$data['action'] = $this->url->link('marketing/giveaway', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('marketing/giveaway', 'token=' . $this->session->data['token'], 'SSL');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('marketing/giveaway.tpl', $data));
	}


	public function getProductName($product_id)
	{
		$query = $this->db->query("SELECT name FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "' AND language_id = 1");
		return $query->row ? $query->row['name'] : '';
	}

	
}