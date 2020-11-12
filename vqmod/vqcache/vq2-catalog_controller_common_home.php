<?php
class ControllerCommonHome extends Controller {
	public function index() {
// Create new db if needed for paypal admin tools
			$sql = "
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "paypal_admin_tools` (
			`order_id` int(11) NOT NULL default '0',
			`transaction_id` varchar(32) NOT NULL,
			`amount` DECIMAL(15,2) NOT NULL default '0.00',
			`currency` varchar(3) NOT NULL default 'USD',
			`authorization_id` varchar(32) NOT NULL default '0',
			`parent_transaction_id` varchar(32) NOT NULL,
			PRIMARY KEY  (`order_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8";
			$this->db->query($sql);
			//
		$this->document->setTitle($this->config->get('config_meta_title'));

				$canonicals = $this->config->get('canonicals');
				if (isset($canonicals['canonicals_home'])) {
					$this->document->addLink($this->config->get('config_url'), 'canonical');
					}
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));

		if (isset($this->request->get['route'])) {
			$this->document->addLink(HTTP_SERVER, 'canonical');
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/home.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/home.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/common/home.tpl', $data));
		}
	}
}