<?php

class KaFormat {

	protected $registry;
	protected $db;
	protected $config;

	protected $model_localisation_language;
	
	protected $language;     // language object
	protected $langauge_id;

	public function __construct($registry = null, $language_id = null) {

		if (!empty($registry)) {	
			$this->registry = $registry;
		} else {
			$this->registry = Loader::getRegistry();
		}
		
		$this->db       = $this->registry->get('db');
		$this->config   = $this->registry->get('config');

		$this->registry->get('load')->model('localisation/language');
		$this->model_localisation_language = $this->registry->get('model_localisation_language');

		$language = false;
		if (empty($language_id)) {
		
			$language_code = $this->config->get('config_language');
			
			$languages = $this->model_localisation_language->getLanguages();
			if (!empty($languages)) {
				foreach ($languages as $lng) {
					if ($lng['code'] == $language_code) {
						$language = $lng;
						break;
					}
				}
			}

		} else {
			$language = $this->model_localisation_language->getLanguage($language_id);
		}

		if (empty($language)) {
			trigger_error("KaFormat::__construct: language not found", E_USER_ERROR);
			return false;
		}
		
		$this->language_id = $language['language_id'];
		$this->language = new Language($language['directory']);
		$this->language->load('ka_format');

		return true;
	}
	
	/*
		PARAMETERS:
			$str   - string
			$chars - a character or array of characters
	*/
	public function strip($str, $chars) {
		$str = trim($str);

		if (empty($chars)) {
			return $str;
		}

		if (!is_array($chars)) {
			$chars = array($chars);
		}

		$pat = array();
		$rep = array();
		foreach($chars as $char) {
			$pat[] = "/(" . preg_quote($char, '/') . ")*$/";
			$rep[] = '';
			$pat[] = "/^(" . preg_quote($char, '/') . ")*/";
			$rep[] = '';
		}

		$res = preg_replace($pat, $rep, $str);
		
		return $res;
	}

	/*
		supports values like '.99', '0,99', '$1,200.00', '123,456.78' => '123456.78'
	*/
	public function parsePrice($price) {
	
		// remove all useless characters from the price
		//
		$price = preg_replace("([^\d\-\.`,])", "", $price);
		
		if (!preg_match("/([\d\-,\.` ]*)([\.,])(\d*)$/U", $price, $matches)) {
			return $price;
		}

		$matches[1] = preg_replace("/[^\d\-]/", "", $matches[1]);
		$res = doubleval($matches[1] . '.' . $matches[3]);
		
		return $res;
	}

	
	public function formatPrice($price, $new = false) {
		
		if (!$new) {
			return $this->parsePrice($price);
		}
		
		$price = (float) $price;
		
		return $price;
	}

		
	/*
		this function should parse the date and try to return formated as YYYY-MM-DD.
	*/
	public function formatDate(&$date) {
	
		$date = trim($date);
		
		// yyyy-mm-dd
		if (preg_match("/^\d{4}-\d{1,2}-\d{1,2}$/", $date, $matches)) {
			return true;

		// mm/dd/yyyy
		} elseif (preg_match("/^(\d{1,2})\/(\d{1,2})\/(\d{2,4})$/", $date, $matches)) {
			if ($matches[3] < 100) {
				$matches[3] += 2000;
			}
			$date = sprintf("%04d-%02d-%02d", $matches[3], $matches[1], $matches[2]);			
			return true;
			
		// dd.mm.yyyy
		} elseif (preg_match("/^(\d{1,2})\.(\d{1,2})\.(\d{2,4})$/", $date, $matches)) {
			if ($matches[3] < 100) {
				$matches[3] += 2000;
			}
			$date = sprintf("%04d-%02d-%02d", $matches[3], $matches[2], $matches[1]);
			return true;
		}
		
		return false;
	}
	
	/*
		$diff - number of seconds since specific moment
	
	*/
	public function formatPassedTime($diff) {

 		$periods = array( //suffixes
	    	'd' => array(86400, $this->language->get('text_days')),
	   		'h' => array(3600, $this->language->get('text_hours')),
      		'm' => array(60, $this->language->get('text_minutes')),
			's' => array(1, $this->language->get('text_seconds'))
  		);

		$ret = '';
		foreach ($periods as $k => $v) {
			$num = floor($diff / $v[0]);
				if ($num || !empty($ret) || $k == 's') {
					$ret .= $num . ' ' . $v[1] . ' ';
				}
				$diff -= $v[0] * $num;
		}

	    return $ret;
	}

	
  	/*
  		function converts values like 10M to bytes
	*/
	public function convertToByte($file_size) {
		$val = trim($file_size);
		switch (strtolower(substr($val, -1))) {
			case 'g':
				$val *= 1024;
			case 'm':
				$val *= 1024;
			case 'k':
				$val *= 1024;
		}
		return $val;
	}


	/*
		Function converts value to human readable format like 10.1 Mb 
	*/
	public function convertToMegabyte($val) {
	
		if (!is_numeric($val)) {
			$val = $this->convertToByte($val);
		}

		if ($val >= 1073741824) {
			$val = round($val/1073741824, 1) . " Gb";

		} elseif ($val >= 1048576) {
			$val = round($val/1048576, 1) . " Mb";

		} elseif ($val >= 1024) {
			$val = round($val/1024, 1) . " Kb";
		} else {
			$val = $val . " bytes";
		}

		return $val;
	}
	
	
	/*
		PARAMETERS:
			weight - value like this 0.0234g

		RETURNS:
			array (
				value           -> 0.0234
				weight_class_id -> 4
			)

		NOTES:
			function does NOT create a new weight class
	*/	
	public function parseWeight($weight) {

		$pair = array(
			'value'           => 0,
			'weight_class_id' => $this->config->get("config_weight_class_id"),
		);
	
		$matches = array();
		if (preg_match("/([\d\.\,]*)([\D]*)$/", $weight, $matches)) {
			$pair['value'] = $matches[1];
		
			$qry = $this->db->query("SELECT * FROM " . DB_PREFIX . "weight_class_description
				WHERE unit = '" . $this->db->escape($matches[2]) . "'"
			);
	
			if (!empty($qry->row)) {
				$pair['weight_class_id'] = $qry->row['weight_class_id'];
			}
		}
		
		return $pair;
	}

	
	public function parseLength($length) {
	
		$pair = array(
			'value'           => 0,
			'length_class_id' => $this->config->get("config_length_class_id"),
		);
	
		$matches = array();
		if (preg_match("/(.*)([\D]*)$/U", $length, $matches)) {
			$pair['value'] = $matches[1];
		
			$qry = $this->db->query("SELECT * FROM " . DB_PREFIX . "length_class_description
				WHERE unit = '" . $this->db->escape($matches[2]) . "'"
			);

			if (!empty($qry->row)) {
				$pair['length_class_id'] = $qry->row['length_class_id'];
			}
		}

		return $pair;
	}
	
	
	public function formatWeight($val, $class_id, $decimal_point = '.', $thousand_point = '') {
		
		$val = doubleval($val);
		$unit = $this->getWeightUnit($class_id);
		
		$val = number_format($val, 2, $decimal_point, $thousand_point) . $unit;

		return $val;
	}
		
	public function getWeightUnit($class_id) {
		static $weights;
				
		if (empty($weights[$class_id])) {
			$qry = $this->db->query("SELECT * FROM " . DB_PREFIX . "weight_class_description
				WHERE weight_class_id = '$class_id' 
					AND language_id = '" . $this->language_id. "'"
			);
						
			if (!empty($qry->row)) {
				$weights[$class_id] = $qry->row;
			} else {
				$weights[$class_id] = array(
					'unit' => ''
				);
			}
		}

		return	$weights[$class_id]['unit'];
	}
	
	public function formatLength($val, $class_id, $decimal_point = '.', $thousand_point = '') {

		$val = doubleval($val);
		$unit = $this->getLengthUnit($class_id);
		
		$val = number_format($val, 2, $decimal_point, $thousand_point) . $unit;

		return $val;
	}
	
	public function getLengthUnit($class_id) {
		static $lengths;
	
		if (empty($lengths[$class_id])) {
			$qry = $this->db->query("SELECT * FROM " . DB_PREFIX . "length_class_description
				WHERE length_class_id = '$class_id'
				AND language_id = '" . $this->language_id. "'"
			);

			if (!empty($qry->row)) {
				$lengths[$class_id] = $qry->row;
			} else {
				$lengths[$class_id] = array('unit' => '');
			}
		}
		
		return $lengths[$class_id]['unit'];
	}
}

?>