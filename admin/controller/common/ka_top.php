<?php
/*
	Project: Ka Extensions
	Author : karapuz <support@ka-station.com>

	Version: 2.0 ($Revision$)
*/

class ControllerCommonKaTop extends Controller {

	/*
		$data - exact copy of the $this->data array assigned to the parent controller;
	*/
	public function index($data) {
		$this->data = $data;
		$this->template = 'common/ka_top.tpl';
    	$this->render();
  	}
}
?>