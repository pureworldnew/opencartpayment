<?php
class ControllerTotalcustomtotal extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('total/customtotal');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			$this->model_setting_setting->editSetting('customtotal', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_allowfront'] = $this->language->get('entry_allowfront');
		$data['entry_customtotaltext'] = $this->language->get('entry_customtotaltext');
		$data['entry_customtotalamount'] = $this->language->get('entry_customtotalamount');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_remove'] = $this->language->get('button_remove');
		

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_total'),
			'href' => $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('total/customtotal', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('total/customtotal', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['customtotal_status'])) {
			$data['customtotal_status'] = $this->request->post['customtotal_status'];
		} else {
			$data['customtotal_status'] = $this->config->get('customtotal_status');
		}

		if (isset($this->request->post['customtotal_allowfront'])) {
			$data['customtotal_allowfront'] = $this->request->post['customtotal_allowfront'];
		} else {
			$data['customtotal_allowfront'] = $this->config->get('customtotal_allowfront');
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['customtotal_sort_order'])) {
			$data['customtotal_sort_order'] = $this->request->post['customtotal_sort_order'];
		} else {
			$data['customtotal_sort_order'] = $this->config->get('customtotal_sort_order');
		}

		if (isset($this->request->post['customtotal_names'])) {
			$data['customtotal_names'] = $this->request->post['customtotal_names'];
		} else {
			$data['customtotal_names'] = $this->config->get('customtotal_names');
		}

		if(!is_array($data['customtotal_names'])) {
			$data['customtotal_names'] = array();
		}


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('total/customtotal.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'total/customtotal')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function saveSession() {
		
		$json['success'] = "Successfully saved custom totals";
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}