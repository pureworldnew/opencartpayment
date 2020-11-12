<?php
class ControllerPaymentNMCGateway extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/nmc_gateway');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('nmc_gateway', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_test'] = $this->language->get('text_test');
		$data['text_live'] = $this->language->get('text_live');
		$data['text_authorization'] = $this->language->get('text_authorization');
		//$data['text_capture'] = $this->language->get('text_capture');
		$data['text_sale'] = $this->language->get('text_sale');

		$data['entry_login'] = $this->language->get('entry_login');
		$data['entry_key'] = $this->language->get('entry_key');
		//$data['entry_hash'] = $this->language->get('entry_hash');
		$data['entry_server'] = $this->language->get('entry_server');
		//$data['entry_mode'] = $this->language->get('entry_mode');
		$data['entry_method'] = $this->language->get('entry_method');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		//$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_title_usa'] = $this->language->get('entry_title_usa');
		$data['entry_title_nonusa'] = $this->language->get('entry_title_nonusa');

		$data['help_total'] = $this->language->get('help_total');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['login'])) {
			$data['error_login'] = $this->error['login'];
		} else {
			$data['error_login'] = '';
		}

		if (isset($this->error['key'])) {
			$data['error_key'] = $this->error['key'];
		} else {
			$data['error_key'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_payment'),
			'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('payment/nmc_gateway', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('payment/nmc_gateway', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['nmc_gateway_login'])) {
			$data['nmc_gateway_login'] = $this->request->post['nmc_gateway_login'];
		} else {
			$data['nmc_gateway_login'] = $this->config->get('nmc_gateway_login');
		}

		if (isset($this->request->post['nmc_gateway_key'])) {
			$data['nmc_gateway_key'] = $this->request->post['nmc_gateway_key'];
		} else {
			$data['nmc_gateway_key'] = $this->config->get('nmc_gateway_key');
		}

		if (isset($this->request->post['nmc_gateway_hash'])) {
			$data['nmc_gateway_hash'] = $this->request->post['nmc_gateway_hash'];
		} else {
			$data['nmc_gateway_hash'] = $this->config->get('nmc_gateway_hash');
		}

		if (isset($this->request->post['nmc_gateway_server'])) {
			$data['nmc_gateway_server'] = $this->request->post['nmc_gateway_server'];
		} else {
			$data['nmc_gateway_server'] = $this->config->get('nmc_gateway_server');
		}

		if (isset($this->request->post['nmc_gateway_mode'])) {
			$data['nmc_gateway_mode'] = $this->request->post['nmc_gateway_mode'];
		} else {
			$data['nmc_gateway_mode'] = $this->config->get('nmc_gateway_mode');
		}

		if (isset($this->request->post['nmc_gateway_method'])) {
			$data['nmc_gateway_method'] = $this->request->post['nmc_gateway_method'];
		} else {
			$data['nmc_gateway_method'] = $this->config->get('nmc_gateway_method');
		}

		if (isset($this->request->post['nmc_gateway_total'])) {
			$data['nmc_gateway_total'] = $this->request->post['nmc_gateway_total'];
		} else {
			$data['nmc_gateway_total'] = $this->config->get('nmc_gateway_total');
		}

		if (isset($this->request->post['nmc_gateway_order_status_id'])) {
			$data['nmc_gateway_order_status_id'] = $this->request->post['nmc_gateway_order_status_id'];
		} else {
			$data['nmc_gateway_order_status_id'] = $this->config->get('nmc_gateway_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['nmc_gateway_geo_zone_id'])) {
			$data['nmc_gateway_geo_zone_id'] = $this->request->post['nmc_gateway_geo_zone_id'];
		} else {
			$data['nmc_gateway_geo_zone_id'] = $this->config->get('nmc_gateway_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['nmc_gateway_status'])) {
			$data['nmc_gateway_status'] = $this->request->post['nmc_gateway_status'];
		} else {
			$data['nmc_gateway_status'] = $this->config->get('nmc_gateway_status');
		}

		if (isset($this->request->post['nmc_gateway_sort_order'])) {
			$data['nmc_gateway_sort_order'] = $this->request->post['nmc_gateway_sort_order'];
		} else {
			$data['nmc_gateway_sort_order'] = $this->config->get('nmc_gateway_sort_order');
		}

		if (isset($this->request->post['nmc_gateway_title_usa'])) {
			$data['nmc_gateway_title_usa'] = $this->request->post['nmc_gateway_title_usa'];
		} else {
			$data['nmc_gateway_title_usa'] = $this->config->get('nmc_gateway_title_usa');
		}

		if (isset($this->request->post['nmc_gateway_title_nonusa'])) {
			$data['nmc_gateway_title_nonusa'] = $this->request->post['nmc_gateway_title_nonusa'];
		} else {
			$data['nmc_gateway_title_nonusa'] = $this->config->get('nmc_gateway_title_nonusa');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/nmc_gateway.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/nmc_gateway')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['nmc_gateway_login']) {
			$this->error['login'] = $this->language->get('error_login');
		}

		if (!$this->request->post['nmc_gateway_key']) {
			$this->error['key'] = $this->language->get('error_key');
		}

		return !$this->error;
	}
}