<?php
/*
	Project: Ka Extensions
	Author : karapuz <support@ka-station.com>

	Version: 3 ($Revision: 35 $)
*/

abstract class KaController extends Controller {

	protected $params;
	protected $data = array();
	protected $kadb = null;

	function __construct($registry) {
		parent::__construct($registry);

		$class = get_class($this);
		if (!isset($this->session->data["ka_params_$class"])) {
			$this->session->data["ka_params_$class"] = array();
		}
		$this->params = &$this->session->data["ka_params_$class"];
		
		$this->kadb = new KaDB($this->db);

		if (defined('DIR_CATALOG')) {		
			$this->document->addStyle('view/stylesheet/ka_extensions.css');
		}
		$this->onLoad();
	}

	
	protected function addTopMessage($msg, $type = 'I') {
		$this->session->data['ka_top_messages'][] = array(
			'type'    => $type,
			'content' => $msg
		);
	}

	
	protected function getTopMessages($clear = true) {
		
		if (isset($this->session->data['ka_top_messages'])) {
			$top = $this->session->data['ka_top_messages'];
		} else {
			$top = null;
		}

		if ($clear) {
			$this->session->data['ka_top_messages'] = null;
		}
		return $top;
	}

	
	protected function findTemplate($template) {
	
		if (!defined('HTTP_CATALOG')) {
			$template = '/template/' . $template;
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . $template)) {
				$template = $this->config->get('config_template') . $template;
			} else {
				$template = 'default' . $template;
			}
		}
		
		return $template;
	}

	
	protected function render() {

		$this->data['top_messages'] = $this->getTopMessages();
		$this->data['ka_top'] = $this->load->view($this->findTemplate('common/ka_top.tpl'), $this->data);
		$this->data['ka_breadcrumbs'] = $this->load->view($this->findTemplate('common/ka_breadcrumbs.tpl'), $this->data);

		
		if ($this->children) {
			foreach ($this->children as $child) {
				$this->data[basename($child)] = $this->load->controller($child);
			}
		}
		
		return $this->load->view($this->template, $this->data);
	}
	
	
	protected function setOutput($param = null) {
		if (!is_null($param)) {
			$this->response->setOutput($param);
		} else {
			$this->response->setOutput($this->render());
		}
	}
	
	
	protected function loadLanguage($language) {	
		$ret = $this->load->language($language);
		
		return $ret;
	}
	
	
	protected function onLoad() {
		return true;
	}
	
	protected function redirect($url, $status = 302) {
		return $this->response->redirect($url, $status);
	}
}
?>