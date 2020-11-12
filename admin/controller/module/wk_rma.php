<?php
/**
 * Webkul Software.
 * @category  Webkul
 * @author    Webkul
 * @copyright Copyright (c) 2010-2016 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
class ControllerModulewkrma extends Controller {

	use RmaControllerTrait;

	private $error = array();

	public function install() {
		$this->load->model('module/wk_rma');
		$this->model_module_wk_rma->install();
	}

	public function index() {

		$data = $this->load->language('module/wk_rma');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
		$this->load->model('tool/image');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate('module/wk_rma') && $this->validateForm()) {
			$this->model_setting_setting->editSetting('wk_rma', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect(version_compare(VERSION,'2.3','<') ? $this->urlChange('extension/module', 'token=' . $this->session->data['token'], 'SSL') : $this->urlChange('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', 'SSL'));
		}

		$data['manage_rma'] = $this->urlChange('catalog/wk_rma_admin', 'token=' . $this->session->data['token'], 'SSL');
		$data['status_rma'] = $this->urlChange('catalog/wk_rma_status', 'token=' . $this->session->data['token'], 'SSL');
		$data['reason_rma'] = $this->urlChange('catalog/wk_rma_reason', 'token=' . $this->session->data['token'], 'SSL');

		//CONFIG
		$config_data = array(
			'wk_rma_status',
			'wk_rma_address',
			'wk_rma_system_information',
			'wk_rma_system_time',
			'wk_rma_system_time_admin',
			'wk_rma_system_orders',
			'wk_rma_system_image',
			'wk_rma_system_size',
			'wk_rma_system_file',
			'wk_rma_voucher_theme',
		);

		foreach ($config_data as $conf) {
			if (isset($this->request->post[$conf])) {
				$data[$conf] = $this->request->post[$conf];
			} else {
				$data[$conf] = $this->config->get($conf);
			}
		}

		$this->load->model('catalog/information');
		$data['information'] = $this->model_catalog_information->getInformations(array());

		$this->load->model('localisation/order_status');
		$data['order_status'] = $this->model_localisation_order_status->getOrderStatuses(array());

		$data['token'] = $this->session->data['token'];
		$data['error_warning'] = '';
		if (isset($this->error['warning'])) {
			unset($this->error['warning']);
			$data['error'] = $this->error;
		} else {
			$data['error'] = array();
		}

		if (isset($this->session->data['warning'])) {
			$data['error_warning'] = $this->session->data['warning'];
			unset($this->session->data['warning']);
		}

		if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];
			unset($this->session->data['error_warning']);
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['breadcrumbs'] = array();

 		$data['breadcrumbs'][] = array(
     		'text'      => $this->language->get('text_home'),
		    'href'      => $this->urlChange('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
    		'separator' => false
 		);

 		$data['breadcrumbs'][] = array(
     		'text'      => $this->language->get('text_module'),
		    'href'      => version_compare(VERSION,'2.3','<') ? $this->urlChange('extension/module', 'token=' . $this->session->data['token'], 'SSL') : $this->urlChange('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', 'SSL'),
    		'separator' => ''
 		);

 		$data['breadcrumbs'][] = array(
     		'text'      => $this->language->get('heading_title'),
		    'href'      => $this->urlChange('module/wk_rma', 'token=' . $this->session->data['token'], 'SSL'),
    		'separator' => ''
 		);

		$data['action'] = $this->urlChange('module/wk_rma', 'token=' . $this->session->data['token'], 'SSL');
		$data['refresh'] = $this->urlChange('module/wk_rma/refresh', 'token=' . $this->session->data['token'], 'SSL');
		$data['uninstall'] = $this->urlChange('module/wk_rma/deletetable', 'token=' . $this->session->data['token'], 'SSL');

		if(version_compare(VERSION,'2.3','<'))
			$data['cancel'] = $this->urlChange('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		else
			$data['cancel'] = $this->urlChange('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', 'SSL');

		$this->load->model('sale/voucher_theme');
		$data['voucher_themes'] = $this->model_sale_voucher_theme->getVoucherThemes();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/wk_rma.tpl', $data));

	}

	private function validateForm() {
		$config_data = array(
			'address',
			'system_orders',
			'system_size',
		);

		foreach ($config_data as $config) {
			if (!isset($this->request->post['wk_rma_' . $config]) || !$this->request->post['wk_rma_' . $config]) {
				$this->error['warning'] = $this->language->get('error_' . $config);
				$this->error['error_' . $config] = $this->language->get('error_' . $config);
			}
		}

		$config_data = array(
			'system_size',
		);

		foreach ($config_data as $config) {
			if (!isset($this->request->post['wk_rma_' . $config]) || !filter_var($this->request->post['wk_rma_' . $config], FILTER_VALIDATE_INT) && (int)$this->request->post['wk_rma_' . $config] > 0) {
				$this->error['warning'] = $this->language->get('error_' . $config);
				$this->error['error_' . $config] = $this->language->get('error_size');
			}
		}

		if (isset($this->request->post['wk_rma_system_time']) &&  (int)$this->request->post['wk_rma_system_time'] < 0) {
			$this->error['warning'] = $this->language->get('error_system_time');
			$this->error['error_system_time'] = $this->language->get('error_system_time');
		}

		return !$this->error;

	}

	public function refresh() {
		if (!$this->validate('module/wk_rma')) {
			$this->load->language('module/wk_rma');
			$this->session->data['error_warning'] = $this->language->get('error_permission');
			$this->response->redirect($this->urlChange('module/wk_rma','&token=' . $this->session->data['token']));
		}
		$this->load->model('module/wk_rma');
		$this->model_module_wk_rma->uninstall();
		$this->model_module_wk_rma->install();
		$this->load->language('module/wk_rma');
		$this->session->data['success'] = $this->language->get('text_refresh');
		$this->response->redirect($this->urlChange('module/wk_rma','&token=' . $this->session->data['token'], true));
	}

	public function deletetable() {
		if (!$this->validate('module/wk_rma')) {
			$this->load->language('module/wk_rma');
			$this->session->data['error_warning'] = $this->language->get('error_permission');
			$this->response->redirect($this->urlChange('module/wk_rma','&token=' . $this->session->data['token']));
		}
		$this->load->model('module/wk_rma');
		$this->model_module_wk_rma->uninstall();
		// $this->model_module_wk_rma->removeOCMOD();
		$this->load->language('module/wk_rma');
		$this->session->data['success'] = $this->language->get('text_delete_table');
		// $this->load->controller('extension/modification/refresh');
		$this->response->redirect($this->urlChange('module/wk_rma','&token=' . $this->session->data['token'], true));
	}


}
