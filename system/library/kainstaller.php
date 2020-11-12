<?php
/*
	Project: Ka Extensions
	Author : karapuz <support@ka-station.com>

	Version: 3 ($Revision: 36 $)
*/

require_once(modification(DIR_APPLICATION . 'controller/extension/modification.php'));
class KaModificationController extends ControllerExtensionModification {
	protected function getList() {
		return true;
	}
	
	public function refresh() {
		$GLOBALS['ka_silent_return'] = true;
		$result = parent::refresh();
		$GLOBALS['ka_silent_return'] = false;
		return $result;
	}
}


class KaInstaller extends KaController {

	// contstants
	//
	protected $extension_version = '0.0.0';
	protected $min_store_version = '2.0.0.0';
	protected $max_store_version = '2.0.0.9';
	
	protected $tables;
	protected $xml_file   = '';

	// temporary variables
	//
	protected $db_patched = false;

	/*
		Compatible db may be fully patched or not patched at all. Partial changes are
		treated as a corrupted db.

		Returns
			true  - db is compatible
			false - db is not compatible

	*/
	protected function checkDBCompatibility(&$messages) {

		$this->db_patched = false;
		if (empty($this->tables)) {
			return true;
		}

		foreach ($this->tables as $tk => $tv) {

			$tbl = DB_PREFIX . $tk;
			$res = $this->kadb->safeQuery("SHOW TABLES LIKE '$tbl'");

			if (!empty($res->rows)) {
				$this->tables[$tk]['exists'] = true;
			} else {
				continue;
			}

			$fields = $this->kadb->safeQuery("DESCRIBE `$tbl`");
			if (empty($fields->rows)) {
				$messages .= "Table '$tbl' exists in the database but it is empty. Please remove this table and try to install the extension again.";
				return false;
			}

			// check fields 

			$db_fields = array();
			foreach ($fields->rows as $v) {
				$db_fields[$v['Field']] = array(
					'type'  => $v['Type']
				);
			}

			foreach ($tv['fields'] as $fk => $field) {
			
				if (empty($db_fields[$fk])) {
					continue;
				}

				// if the field is found we validate its type

				$db_field = $db_fields[$fk];

				if ($field['type'] != $db_field['type']) {
					$messages .= "Field type '$db_field[type]' for '$fk' in the table '$tbl' does not match the required field type '$field[type]'.";
					return false;
				} else {
					$this->tables[$tk]['fields'][$fk]['exists'] = true;
					$this->db_patched = true;
				}
			}

			// check indexes
			/*
				We do not compare index fields yet, just ensure that the index with the appropriate
				name exists for the table.
			*/
			if (!empty($tv['indexes'])) {

				$rec = $this->kadb->safeQuery("SHOW INDEXES FROM `$tbl`");
				$db_indexes = array();
				foreach ($rec->rows as $v) {
					$db_indexes[$v['Key_name']]['columns'][] = $v['Column_name'];
				}

				foreach ($tv['indexes'] as $ik => $index) {
					if (!empty($db_indexes[$ik])) {
						$this->tables[$tk]['indexes'][$ik]['exists'] = true;
					}
				}
			}
		}

		return true;
	}

	protected function patchDB(&$messages) {

		// create db
		if (empty($this->tables)) {
			return true;
		}

		foreach ($this->tables as $tk => $tv) {
			if (empty($tv['exists'])) {
				$this->kadb->safeQuery($tv['query']);
				continue;
			}

			if (!empty($tv['fields'])) {
				foreach ($tv['fields'] as $fk => $fv) {
					if (empty($fv['exists'])) {
 						if (empty($fv['query'])) {
 							$messages .= "Installation error. Cannot create '$tk.$fk' field. Try to create it manually and run the installation again.";
 							return false;
 						}
						$this->kadb->safeQuery($fv['query']);
					}
				}
			}

			if (!empty($tv['indexes'])) {
				foreach ($tv['indexes'] as $ik => $iv) {
					if (empty($iv['exists']) && !empty($iv['query'])) {
						$this->kadb->safeQuery($iv['query']);
					}
				}
			}
		}
		
		return true;
	}

	
	protected function applyXml() {

		$modification_controller = new KaModificationController($this->registry);
		$result = $modification_controller->refresh();
		
		return $result;
	}

		
	protected function checkCompatibility(&$messages) {

		// check store version 
		if (version_compare(VERSION, $this->min_store_version, '<')
			|| version_compare(VERSION, $this->max_store_version, '>'))
		{
			$messages .= "compatibility of this extension with your store version (" . VERSION . ") was not checked.
				Please contact ka-station team for update.";
			return false;
		}
		
		//check database
		//
		if (!$this->checkDBCompatibility($messages)) {
			return false;
		}
    
		return true;
	}
	
	
	public function install() {

		if (!$this->checkCompatibility($messages)) {
			$this->addTopMessage($messages, 'E');
			return false;
		}
		
		if (!$this->patchDB($messages)) {
			$this->addTopMessage($messages, 'E');
			return false;
		}
		
		if (!$this->applyXml()) {
			$messages .= "Possible errors in applying xml file. Check the log files and 
				contact ka-support at <b>support@ka-station.com</b> for assistance.
			";
		
			$this->addTopMessage($messages, 'E');
			return false;
		}
		
		return true;
	}
	
	public function uninstall() {
		$this->addTopMessage("The extension has been uninstalled successfully");	
		return true;
	}
	
	
	public function getTitle() {
		$str = str_replace('{{version}}', $this->extension_version, $this->language->get('heading_title_ver'));
		return $str;
	}
	
}

?>