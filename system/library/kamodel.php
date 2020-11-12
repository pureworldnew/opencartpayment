<?php
/*
	Project: Ka Extensions
	Author : karapuz <support@ka-station.com>

	Version: 3 ($Revision: 27 $)
*/

abstract class KaModel extends Model {

	protected $params;
	protected $lastError;

	function __construct($registry) {
		parent::__construct($registry);

		$class = get_class($this);
		if (!isset($this->session->data["ka_params_$class"])) {
			$this->session->data["ka_params_$class"] = array();
		}
		$this->params = &$this->session->data["ka_params_$class"];
		$this->kadb = new KaDb($this->db);
				
		$this->onLoad();
	}

	
	protected function loadLanguage($language) {
		$ret = $this->load->language($language);
		
		return $ret;
	}
	
	
	public function getLastError() {
		return $this->lastError;
	}
	
	protected function onLoad() {
		return true;
	}
	
}
?>