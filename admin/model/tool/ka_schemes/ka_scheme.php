<?php
/*
	Project: CSV Product Export
	Author : karapuz <support@ka-station.com>

	Version: 3.2 ($Revision$)
*/

class KaScheme extends Model {
	protected $last_error = false;

	public function getLastError() {
		return $this->last_error;
	}
}