<?php
class ControllerSaleOrderlabels extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');
		$this->load->model('setting/setting');

		$this->document->addStyle('view/stylesheet/product_labels/stylesheet.css');
		$this->document->addScript('view/javascript/product_labels/pdfobject.js');
		$this->document->addScript('view/javascript/product_labels/product_labels.min.js');
		$data['product_labels_tab'] = $this->load->controller('module/product_labels/tab');
		$data['settings'] = $this->model_setting_setting->getSetting('product_labels');
		$data['download'] = $data['settings']['product_labels_download'];
		if (isset($_GET['product_id']))
			$data['product_id'] = $this->request->get['product_id'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('sale/orderlabels.tpl', $data));
	}

}