<?php

class ModelModuleEmailTemplate extends Model {

	public function __construct($registry) {
		parent::__construct($registry);

		$file = DIR_SYSTEM . 'library/emailtemplate/email_template.php';

		if (file_exists($file)) {
			include_once($file);
		} else {
			trigger_error('Error: Could not load library ' . $file . '!');
			exit();
		}
	}

	/**
	 * Get Email Template Config
	 *
	 * @param int||array $identifier
	 * @param bool $outputFormatting
	 * @return array
	 */
	public function getConfig($data = false, $outputFormatting = false, $keyCleanUp = false) {
		$cond = array();

		if (is_array($data)) {
			if (isset($data['store_id'])) {
				$cond[] = "`store_id` = '". (int)$data['store_id'] ."'";
			}
			if (isset($data['language_id'])) {
				$cond[] = "(`language_id` = '". (int)$data['language_id']."' OR `language_id` = 0)";
			}
		} elseif (is_numeric($data)) {
			$cond[] = "`emailtemplate_config_id` = '" . (int)$data . "'";
		}

		$query = "SELECT * FROM " . DB_PREFIX . "emailtemplate_config";
		if (!empty($cond)) {
			$query .= " WHERE " . implode(" AND ", $cond);
		}
		$query .= " ORDER BY `language_id` DESC LIMIT 1";

		$result = $this->db->query($query);
		if (empty($result->row)) return false;
		$row = $result->row;

		$cols = EmailTemplateConfigDAO::describe();
		foreach($cols as $col => $type) {
			if (isset($row[$col]) && $type == EmailTemplateDAO::SERIALIZE) {
				if ($row[$col]) {
					$row[$col] = unserialize(base64_decode($row[$col]));
				} else {
					$row[$col] = array();
				}
			}
		}

		if ($outputFormatting) {
			$row = $this->formatConfig($row, $keyCleanUp);
		}

		return $row;
	}

	/**
	 * Return array of configs
	 *
	 * @param array - $data
	 */
	public function formatConfig($data = array(), $keyCleanUp = false) {
		$this->load->model('tool/image');

		if ($data['emailtemplate_config_modified']) {
			$modified = strtotime($data['emailtemplate_config_modified']);
			if (date('Ymd') == date('Ymd', $modified)) {
				$data['emailtemplate_config_modified'] = date($this->language->get('time_format'), $modified);
			} else {
				$data['emailtemplate_config_modified'] = date($this->language->get('date_format_short'), $modified);
			}
		}

		$data['emailtemplate_config_head_text'] = html_entity_decode($data['emailtemplate_config_head_text'], ENT_QUOTES, 'UTF-8');
		$data['emailtemplate_config_view_browser_text'] = html_entity_decode($data['emailtemplate_config_view_browser_text'], ENT_QUOTES, 'UTF-8');
		$data['emailtemplate_config_page_footer_text'] = html_entity_decode($data['emailtemplate_config_page_footer_text'], ENT_QUOTES, 'UTF-8');
		$data['emailtemplate_config_footer_text'] = html_entity_decode($data['emailtemplate_config_footer_text'], ENT_QUOTES, 'UTF-8');

		if ($data['emailtemplate_config_logo'] && file_exists(DIR_IMAGE . $data['emailtemplate_config_logo'])) {
			if ($data['emailtemplate_config_logo_width'] > 0 && $data['emailtemplate_config_logo_height'] > 0) {
				$data['emailtemplate_config_logo_thumb'] = $this->model_tool_image->resize($data['emailtemplate_config_logo'], $data['emailtemplate_config_logo_width'], $data['emailtemplate_config_logo_height']);
			} else {
				$data['emailtemplate_config_logo_thumb'] = $this->model_tool_image->resize($data['emailtemplate_config_logo'], 100, 100);
			}
		} else {
			$data['emailtemplate_config_logo_thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		if ($data['emailtemplate_config_header_bg_image'] && file_exists(DIR_IMAGE . $data['emailtemplate_config_header_bg_image'])) {
			$data['emailtemplate_config_header_bg_image_thumb'] = $this->model_tool_image->resize($data['emailtemplate_config_header_bg_image'], 100, 100);
		} else {
			$data['emailtemplate_config_header_bg_image_thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		if ($data['emailtemplate_config_body_bg_image'] && file_exists(DIR_IMAGE . $data['emailtemplate_config_body_bg_image'])) {
			$data['emailtemplate_config_body_bg_image_thumb'] = $this->model_tool_image->resize($data['emailtemplate_config_body_bg_image'], 100, 100);
		} else {
			$data['emailtemplate_config_body_bg_image_thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		foreach(array('left', 'right') as $col) {
			if (isset($data['emailtemplate_config_shadow_top'][$col.'_img']) && $data['emailtemplate_config_shadow_top'][$col.'_img'] && file_exists(DIR_IMAGE . $data['emailtemplate_config_shadow_top'][$col.'_img'])) {
				$data['emailtemplate_config_shadow_top'][$col.'_thumb'] = $this->model_tool_image->resize($data['emailtemplate_config_shadow_top'][$col.'_img'], 17, 17);
			} else {
				$data['emailtemplate_config_shadow_top'][$col.'_thumb'] = $this->model_tool_image->resize('no_image.png', 17, 17);
			}

			if (isset($data['emailtemplate_config_shadow_bottom'][$col.'_img']) && $data['emailtemplate_config_shadow_bottom'][$col.'_img'] && file_exists(DIR_IMAGE . $data['emailtemplate_config_shadow_bottom'][$col.'_img'])) {
				$data['emailtemplate_config_shadow_bottom'][$col.'_thumb'] = $this->model_tool_image->resize($data['emailtemplate_config_shadow_bottom'][$col.'_img'], 17, 17);
			} else {
				$data['emailtemplate_config_shadow_bottom'][$col.'_thumb'] = $this->model_tool_image->resize('no_image.png', 17, 17);
			}
		}

		if ($keyCleanUp) {
			foreach($data as $col => $val) {
				$key = (strpos($col, 'emailtemplate_config_') === 0 && substr($col, -3) != '_id') ? substr($col, 21) : $col;
				if (!isset($data[$key])) {
					unset($data[$col]);
					$data[$key] = $val;
				}
			}
		}

		return $data;
	}

	/**
	 * Return array of configs
	 * @param array - $data
	 */
	public function getConfigs($data = array(), $outputFormatting = false, $keyCleanUp = false) {
		$cond = array();

		if (isset($data['language_id'])) {
			$cond[] = "AND ec.`language_id` = '".(int)$data['language_id']."'";
		} elseif (isset($data['_language_id'])) {
			$cond[] = "OR ec.`language_id` = '".(int)$data['_language_id']."'";
		}

		if (isset($data['store_id'])) {
			$cond[] = "AND ec.`store_id` = '".(int)$data['store_id']."'";
		} elseif (isset($data['_store_id'])) {
			$cond[] = "OR ec.`store_id` = '".(int)$data['_store_id']."'";
		}

		if (isset($data['customer_group_id'])) {
			$cond[] = "AND ec.`customer_group_id` = '".(int)$data['customer_group_id']."'";
		} elseif (isset($data['_customer_group_id'])) {
			$cond[] = "OR ec.`customer_group_id` = '".(int)$data['_customer_group_id']."'";
		}

		if (isset($data['emailtemplate_config_id'])) {
			if (is_array($data['emailtemplate_config_id'])) {
				$ids = array();
				foreach($data['emailtemplate_config_id'] as $id) { $ids[] = (int)$id; }
				$cond[] = "AND ec.`emailtemplate_config_id` IN('".implode("', '", $ids)."')";
			} else {
				$cond[] = "AND ec.`emailtemplate_config_id` = '".(int)$data['emailtemplate_config_id']."'";
			}
		}

		if (isset($data['not_emailtemplate_config_id'])) {
			if (is_array($data['not_emailtemplate_config_id'])) {
				$ids = array();
				foreach($data['not_emailtemplate_config_id'] as $id) { $ids[] = (int)$id; }
				$cond[] = "AND ec.`emailtemplate_config_id` NOT IN('".implode("', '", $ids)."')";
			} else {
				$cond[] = "AND ec.`emailtemplate_config_id` != '".(int)$data['not_emailtemplate_config_id']."'";
			}
		}

		$query = "SELECT ec.* FROM " . DB_PREFIX . "emailtemplate_config ec";
		if (!empty($cond)) {
			$query .= ' WHERE ' . ltrim(implode(' ', $cond), 'AND');
		}

		$sort_data = array(
			'ec.emailtemplate_config_id',
			'ec.emailtemplate_config_name',
			'ec.emailtemplate_config_modified',
			'ec.store_id',
			'ec.language_id',
			'ec.customer_group_id'
		);
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$query .= " ORDER BY `" . $data['sort'] . "`";
		} else {
			$query .= " ORDER BY ec.`emailtemplate_config_name`";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$query .= " DESC";
		} else {
			$query .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if (!isset($data['start']) || $data['start'] < 0) {
				$data['start'] = 0;
			}
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}
			$query .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$result = $this->db->query($query);
		if (empty($result->rows)) return array();
		$rows = $result->rows;

		$cols = EmailTemplateConfigDAO::describe();

		foreach($rows as $key => &$row) {
			foreach($cols as $col => $type) {

				if (isset($row[$col]) && $type == EmailTemplateDAO::SERIALIZE) {
					if ($row[$col]) {
						$row[$col] = unserialize(base64_decode($row[$col]));
					} else {
						$row[$col] = array();
					}
				}

				if ($keyCleanUp) {
					$key = (strpos($col, 'emailtemplate_config_') === 0 && substr($col, -3) != '_id') ? substr($col, 21) : $col;
					if (!isset($row[$key]) && isset($row[$col])) {
						$row[$key] = $row[$col];
						unset($row[$col]);
					}
				}

			}
		}

		return $rows;
	}

	/**
	 * Add new Email Template Config by cloning an existing one.
	 *
	 * @return new row identifier
	 */
    public function cloneConfig($id, $data = array()) {
        $id = (int)$id;
        $inserts = array();
        $cols = EmailTemplateConfigDAO::describe("emailtemplate_config_id", "store_id", "language_id", "customer_group_id");

        if (isset($data['store_id'])) {
        	$store_id = (int)$data['store_id'];
        } else {
        	$store_id = -1;
        }

        if (isset($data['language_id'])) {
        	$language_id = (int)$data['language_id'];
        } else {
        	$language_id = 0;
        }

        if (isset($data['customer_group_id'])) {
        	$customer_group_id = (int)$data['customer_group_id'];
        } else {
        	$customer_group_id = 0;
        }

        $colsInsert = '';
        foreach($cols as $col => $type) {
	        if (!array_key_exists($col, $data)) {
	        	$value = "`{$col}`";
	        } else {
		        switch ($type) {
		        	case EmailTemplateAbstract::INT:
		        		if (strtoupper($data[$col]) == 'NULL') {
							$value = 'NULL';
						} else {
							$value = (int)$data[$col];
						}
		        		break;
		        	case EmailTemplateAbstract::FLOAT:
		        		$value = floatval($data[$col]);
		        		break;
		        	case EmailTemplateAbstract::DATE_NOW:
		        		$value = 'NOW()';
		        		break;
		        	case EmailTemplateAbstract::SERIALIZE:
		        		$value = base64_encode(serialize($data[$col]));
		        		break;
		        	default:
		        		$value = $this->db->escape($data[$col]);
		        }
		        $value = "'{$value}'";
	        }

	        $colsInsert .= "{$value}, ";
        }

        $stmnt = "INSERT INTO " . DB_PREFIX . "emailtemplate_config (".implode(array_keys($cols),', ').", store_id, language_id, customer_group_id)
                  SELECT ".$colsInsert." '{$store_id}', '{$language_id}', '{$customer_group_id}' FROM " . DB_PREFIX . "emailtemplate_config WHERE emailtemplate_config_id = '". (int)$id . "'";
        $this->db->query($stmnt);

        $emailtemplate_config_id = $this->db->getLastId();

        $stmnt = "UPDATE " . DB_PREFIX . "emailtemplate_config SET emailtemplate_config_name = CONCAT(emailtemplate_config_name, ' - {$emailtemplate_config_id}') WHERE emailtemplate_config_id = '{$emailtemplate_config_id}'";
        $this->db->query($stmnt);

        $this->_delete_cache();

        return $emailtemplate_config_id;
    }

	/**
	 * Edit existing config
	 *
	 * @param int - emailtemplate.emailtemplate_id
	 * @param array - column => value
	 * @return int affected row count
	 */
	 public function updateConfig($id, array $data) {
		 if (empty($data) && !is_numeric($id)) return false;

		 $cols = EmailTemplateConfigDAO::describe();
		 $updates = $this->_build_query($cols, $data);
		 if (!$updates) return false;

		 $sql = "UPDATE " . DB_PREFIX . "emailtemplate_config SET ".implode($updates,", ") . " WHERE emailtemplate_config_id = '". (int)$id . "'";
		 $this->db->query($sql);

		 $this->_delete_cache();

		 $affected = $this->db->countAffected();
		 return ($affected > 0) ? $affected : false;
 	}

 	/**
 	* Delete config row
 	*
 	* @param mixed array||int - emailtemplate.id
 	 * @return int - row count effected
 	*/
 	public function deleteConfig($data) {
	 	$ids = array();

 		if (is_array($data)) {
	 		foreach($data as $item) {
	 			$ids[] = (int)$item;
	 		}
 		} else {
	 		$ids[] = (int)$data;
	 	}

	 	if (($key = array_search(1, $ids)) !== false) {
	 		unset($ids[$key]);
	 	}

	 	$queries = array();
	 	$queries[] = "DELETE FROM " . DB_PREFIX . "emailtemplate_config WHERE emailtemplate_config_id IN('".implode("', '", $ids)."')";
	 	$queries[] = "UPDATE " . DB_PREFIX . "emailtemplate SET emailtemplate_config_id = '' WHERE emailtemplate_config_id IN('".implode("', '", $ids)."')";

	 	$affected = 0;
	 	foreach($queries as $query) {
	 		$this->db->query($query);
	 		$affected += $this->db->countAffected();
	 	}

 		$this->_delete_cache();

 		if ($affected > 0) {
 			return $affected;
 		}
 		return false;
 	}

	/**
	 * Get Template
	 * @param int $ident
	 * @param int $language_id
	 * @param int $keyCleanUp
	 * @return array
	 */
	public function getTemplate($ident, $language_id = null, $keyCleanUp = false) {
		$return = array();

		if (is_numeric($ident)) {
			$cond = "`emailtemplate_id` = '" . (int)$ident . "'";
		} else {
			$cond = "`emailtemplate_key` = '" . $this->db->escape($ident) . "' AND `emailtemplate_default` = 1";
		}

		$query = "SELECT * FROM " . DB_PREFIX . "emailtemplate WHERE " . $cond . " LIMIT 1";
		$result = $this->_fetch($query);

		if ($result->row) {
			$return = $result->row;

			$cols = EmailTemplateDAO::describe();

			foreach($cols as $col => $type) {
				if (!isset($return[$col])) continue;

				if ($type == EmailTemplateDAO::SERIALIZE && $return[$col]) {
					$return[$col] = unserialize(base64_decode($return[$col]));
				}

				if ($keyCleanUp) {
					$key = (strpos($col, 'emailtemplate_') === 0 && substr($col, -3) != '_id') ? substr($col, 14) : $col;
					if (!isset($return[$key])) {
						$return[$key] = $return[$col];
						unset($return[$col]);
					}
				}
			}

			if ($language_id) {
				$result = $this->getTemplateDescription(array('emailtemplate_id' => $return['emailtemplate_id'], 'language_id' => $language_id), 1);

				if ($result) {
					$cols = EmailTemplateDescriptionDAO::describe();
					foreach($cols as $col => $type) {
						$key = $col;
						if ($keyCleanUp) {
							$key = (strpos($col, 'emailtemplate_description_') === 0 && substr($col, -3) != '_id') ? substr($col, 24) : $col;
						}

						if (!isset($return[$key])) {
							$return[$key] = $result[$col];
							unset($result[$col]);
						}
					}
				}
			}
		}

		return $return;
	}

	/**
	 * Get Template
	 * @param int $id
	 * @return array
	 */
	public function getTemplateDescription($data = array(), $limit = null) {
		$cond = array();
		$query = "SELECT * FROM " . DB_PREFIX . "emailtemplate_description";

		if (isset($data['emailtemplate_id'])) {
			$cond[] = "`emailtemplate_id` = '".(int)$data['emailtemplate_id']."'";
		} else {
			return array();
		}

		if (isset($data['language_id'])) {
			$cond[] = "`language_id` = '".(int)$data['language_id']."'";
		}

		if (!empty($cond)) {
			$query .= ' WHERE ' . implode(' AND ', $cond);
		}
		if (is_numeric($limit)) {
			$query .= ' LIMIT ' . (int)$limit;
		}

		$result = $this->db->query($query);

		return ($limit == 1) ? $result->row : $result->rows;
	}

	/**
	 * Return array of templates
	 * @param array - $data
	 */
	public function getTemplates($data = array(), $keyCleanUp = false) {
		$cond = array();

		if (isset($data['store_id'])) {
			if (is_numeric($data['store_id'])) {
				$cond[] = "e.`store_id` = '".(int)$data['store_id']."'";
			} else {
				$cond[] = "e.`store_id` IS NULL";
			}
		}

		if (isset($data['customer_group_id']) && $data['customer_group_id'] != 0) {
			$cond[] = "e.`customer_group_id` = '".(int)$data['customer_group_id']."'";
		}

		if (isset($data['emailtemplate_key']) && $data['emailtemplate_key'] != "") {
			$cond[] = "e.`emailtemplate_key` = '".$this->db->escape($data['emailtemplate_key'])."'";
		}

		if (isset($data['emailtemplate_type'])) {
			$cond[] = "e.`emailtemplate_type` = '".$this->db->escape($data['emailtemplate_type'])."'";
		}

		if (isset($data['emailtemplate_status']) && $data['emailtemplate_status'] != "") {
			$cond[] = "e.`emailtemplate_status` = '".$this->db->escape($data['emailtemplate_status'])."'";
		}

		if (isset($data['emailtemplate_default'])) {
			$cond[] = "e.`emailtemplate_default` = '" . (int)$data['emailtemplate_default'] . "'";
		}

		if (isset($data['emailtemplate_id'])) {
			if (is_array($data['emailtemplate_id'])) {
				$ids = array();
				foreach($data['emailtemplate_id'] as $id) { $ids[] = (int)$id; }
				$cond[] = "e.`emailtemplate_id` IN('".implode("', '", $ids)."')";
			} else {
				$cond[] = "e.`emailtemplate_id` = '".(int)$data['emailtemplate_id']."'";
			}
		}

		if (isset($data['not_emailtemplate_id'])) {
			if (is_array($data['not_emailtemplate_id'])) {
				$ids = array();
				foreach($data['not_emailtemplate_id'] as $id) { $ids[] = (int)$id; }
				$cond[] = "e.`emailtemplate_id` NOT IN('".implode("', '", $ids)."')";
			} else {
				$cond[] = "e.`emailtemplate_id` != '".(int)$data['not_emailtemplate_id']."'";
			}
		}

		$query = "SELECT e.* FROM " . DB_PREFIX . "emailtemplate e";

		if (!empty($cond)) {
			$query .= ' WHERE ' . implode(' AND ', $cond);
		}

		$sort_data = array(
			'label' => 'e.`emailtemplate_label`',
			'key' => 'e.`emailtemplate_key`',
			'template' => 'e.`emailtemplate_template`',
			'modified' => 'e.`emailtemplate_modified`',
			'default' => 'e.`emailtemplate_default`',
			'shortcodes' => 'e.`emailtemplate_shortcodes`',
			'status' => 'e.`emailtemplate_status`',
			'id' => 'e.`emailtemplate_id`',
			'config' => 'e.`emailtemplate_config_id`',
			'store' => 'e.`store_id`',
			'customer' => 'e.`customer_group_id`',
			'language' => 'ed.`language_id`'
		);
		if (isset($data['sort']) && in_array($data['sort'], array_keys($sort_data))) {
			$query .= " ORDER BY " . $sort_data[$data['sort']];
		} else {
			$query .= " ORDER BY e.`emailtemplate_key`";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$query .= " DESC";
		} else {
			$query .= " ASC";
		}

		if (isset($data['sort'])) {
			$query .= ", `e`.`emailtemplate_key` ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if (!isset($data['start']) || $data['start'] < 0) {
				$data['start'] = 0;
			}
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}
			$query .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$result = $this->db->query($query);
		if (empty($result->rows)) return array();

		$rows = $result->rows;

		$cols = EmailTemplateDAO::describe();

		foreach($rows as $key => &$row) {
			foreach($row as $col => $val) {
				if (isset($cols[$col]) && $cols[$col] == EmailTemplateDAO::SERIALIZE) {
					if ($val) {
						$row[$col] = unserialize(base64_decode($val));
					}
				}

				if ($keyCleanUp) {
					$key = (strpos($col, 'emailtemplate_') === 0 && substr($col, -3) != '_id') ? substr($col, 14) : $col;

					if (!array_key_exists($key, $row)) {
						$row[$key] = $val;
						unset($row[$col]);
					}
				}
			}
		}

		return $rows;
	}

	/**
	 * Get Template Log
	 * @param int $id
	 * @return array
	 */
	public function getTemplateLog($id, $keyCleanUp = false) {
		$return = array();
		if (!$id) return $return;

		$query = "SELECT * FROM " . DB_PREFIX . "emailtemplate_logs WHERE `emailtemplate_log_id` = '". (int)$id . "'";
		$result = $this->db->query($query);
		$return = ($result->row) ? $result->row : array();

		if (!empty($return) && $keyCleanUp) {
			$cols = EmailTemplateLogsDAO::describe();
			foreach($cols as $col => $type) {
				$key = (strpos($col, 'emailtemplate_log_') === 0 && substr($col, -3) != '_id') ? substr($col, 18) : $col;
				if (!isset($return[$key])) {
					$return[$key] = $return[$col];
					unset($return[$col]);
				}
			}
		}

		return $return;
	}

	/**
	 * Return array of logs
	 * @param array - $data
	 */
	public function getTemplateLogs($data = array(), $outputFormatting = false, $keyCleanUp = false) {
		$cond = array();
		$query = "SELECT el.* FROM `" . DB_PREFIX . "emailtemplate_logs` el";

		if (isset($data['store_id']) && is_numeric($data['store_id'])) {
			if (is_numeric($data['store_id'])) {
				$cond[] = "el.`store_id` = '".(int)$data['store_id']."'";
			}
		}

		if (isset($data['language_id']) && $data['language_id'] != 0) {
			$cond[] = "el.`language_id` = '".(int)$data['language_id']."'";
		}

		if (isset($data['customer_id']) && $data['customer_id'] != 0) {
			$cond[] = "(el.`customer_id` = '".(int)$data['customer_id']."' OR emailtemplate_log_to = (SELECT email FROM " . DB_PREFIX . "customer WHERE customer_id = '".(int)$data['customer_id']."' LIMIT 1))";
		}

		if (isset($data['emailtemplate_id']) && $data['emailtemplate_id'] != 0) {
			if (is_array($data['emailtemplate_id'])) {
				$ids = array();
				foreach($data['emailtemplate_id'] as $id) { $ids[] = (int)$id; }
				$cond[] = "el.`emailtemplate_id` IN('".implode("', '", $ids)."')";
			} else {
				$cond[] = "el.`emailtemplate_id` = '".(int)$data['emailtemplate_id']."'";
			}
		}

		if (!empty($cond)) {
			$query .= ' WHERE ' . implode(' AND ', $cond);
		}

		$sort_data = array(
			'id' => 'el.`emailtemplate_log_id`',
			'template' => 'el.`emailtemplate_id`',
			'store_id' => 'el.`store_id`',
			'date' => 'el.`emailtemplate_log_sent`',
			'type' => 'el.`emailtemplate_log_type`',
			'to' => 'el.`emailtemplate_log_to`',
			'from' => 'el.`emailtemplate_log_from`',
			'sender' => 'el.`emailtemplate_log_sender`',
			'subject' => 'el.`emailtemplate_log_subject`'
		);
		if (isset($data['sort']) && in_array($data['sort'], array_keys($sort_data))) {
			$query .= " ORDER BY " . $sort_data[$data['sort']];
		} else {
			$query .= " ORDER BY el.`emailtemplate_log_sent`";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$query .= " DESC";
		} else {
			$query .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if (!isset($data['start']) || $data['start'] < 0) {
				$data['start'] = 0;
			}
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}
			$query .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$result = $this->db->query($query);

		if (empty($result->rows)) return array();

		foreach($result->rows as $i => $row) {
			foreach($row as $col => $val) {
				if ($keyCleanUp) {
					$key = (strpos($col, 'emailtemplate_log_') === 0 && substr($col, -3) != '_id') ? substr($col, 18) : $col;
					if (!isset($result->rows[$i][$key])) {
						unset($result->rows[$i][$col]);
						$result->rows[$i][$key] = $val;
					}
				}
			}
		}

		return $result->rows;
	}

	/**
	 * Return top logs
	 * @param array - $data
	 */
	public function getTotalTemplateLogs($data = array()) {
		$cond = array();

		if (isset($data['store_id']) && is_numeric($data['store_id'])) {
			if (is_numeric($data['store_id'])) {
				$cond[] = "el.`store_id` = '".(int)$data['store_id']."'";
			}
		}

		if (isset($data['language_id']) && $data['language_id'] != 0) {
			$cond[] = "el.`language_id` = '".(int)$data['language_id']."'";
		}

		if (isset($data['customer_id']) && $data['customer_id'] != 0) {
			$cond[] = "el.`customer_id` = '".(int)$data['customer_id']."'";
		}

		if (isset($data['emailtemplate_id']) && $data['emailtemplate_id'] != 0) {
			if (is_array($data['emailtemplate_id'])) {
				$ids = array();
				foreach($data['emailtemplate_id'] as $id) { $ids[] = (int)$id; }
				$cond[] = "el.`emailtemplate_id` IN('".implode("', '", $ids)."')";
			} else {
				$cond[] = "el.`emailtemplate_id` = '".(int)$data['emailtemplate_id']."'";
			}
		}

		$query = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "emailtemplate_logs` el";
		if (!empty($cond)) {
			$query .= ' WHERE ' . implode(' AND ', $cond);
		}

		$result = $this->db->query($query);
		if (empty($result->row)) return array();

		return $result->row['total'];
	}

	/**
	 * Return last insert id for logs.
	 * @return int
	 */
	public function getLastTemplateLogId() {
		$query = "SELECT MAX(emailtemplate_log_id) as emailtemplate_log_id FROM `" . DB_PREFIX . "emailtemplate_logs`";
		$result = $this->db->query($query);

		return $result->row['emailtemplate_log_id'];
	}


	/**
	 * Return total template(s)
	 * @return int - total rows
	 */
	public function getTotalTemplates($data) {
		$cond = array();

		if (isset($data['store_id'])) {
			if (is_numeric($data['store_id'])) {
				$cond[] = "e.`store_id` = '".(int)$data['store_id']."'";
			} else {
				$cond[] = "e.`store_id` IS NULL";
			}
		}

		if (isset($data['customer_group_id']) && $data['customer_group_id'] != 0) {
			$cond[] = "e.`customer_group_id` = '".(int)$data['customer_group_id']."'";
		}

		if (isset($data['emailtemplate_key']) && $data['emailtemplate_key'] != "") {
			$cond[] = "e.`emailtemplate_key` = '".$this->db->escape($data['emailtemplate_key'])."'";
		}

		if (isset($data['emailtemplate_type'])) {
			$cond[] = "e.`emailtemplate_type` = '".$this->db->escape($data['emailtemplate_type'])."'";
		}

		if (isset($data['emailtemplate_status']) && $data['emailtemplate_status'] != "") {
			$cond[] = "e.`emailtemplate_status` = '".$this->db->escape($data['emailtemplate_status'])."'";
		}

		if (isset($data['emailtemplate_default'])) {
			$cond[] = "e.`emailtemplate_default` = '" . (int)$data['emailtemplate_default'] . "'";
		}

		if (isset($data['emailtemplate_id'])) {
			if (is_array($data['emailtemplate_id'])) {
				$ids = array();
				foreach($data['emailtemplate_id'] as $id) { $ids[] = (int)$id; }
				$cond[] = "e.`emailtemplate_id` IN('".implode("', '", $ids)."')";
			} else {
				$cond[] = "e.`emailtemplate_id` = '".(int)$data['emailtemplate_id']."'";
			}
		}

		if (isset($data['not_emailtemplate_id'])) {
			if (is_array($data['not_emailtemplate_id'])) {
				$ids = array();
				foreach($data['not_emailtemplate_id'] as $id) { $ids[] = (int)$id; }
				$cond[] = "e.`emailtemplate_id` NOT IN('".implode("', '", $ids)."')";
			} else {
				$cond[] = "e.`emailtemplate_id` != '".(int)$data['not_emailtemplate_id']."'";
			}
		}

		if (isset($data['emailtemplate_id'])) {
			if (is_array($data['emailtemplate_id'])) {
				$ids = array();
				foreach($data['emailtemplate_id'] as $id) { $ids[] = (int)$id; }
				$cond[] = "e.`emailtemplate_id` IN('".implode("', '", $ids)."')";
			} else {
				$cond[] = "e.`emailtemplate_id` = '".(int)$data['emailtemplate_id']."'";
			}
		}

		$query = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "emailtemplate e";
		if (!empty($cond)) {
			$query .= ' WHERE ' . implode(' AND ', $cond);
		}

		$result = $this->db->query($query);
		return $result->row['total'];
	}

	/**
	 * Add new template row
	 *
	 * @return new row identifier
	 */
	public function insertTemplate(array $data) {
		if (empty($data)) return false;

		$this->load->model('localisation/language');

		$cols = EmailTemplateDAO::describe('emailtemplate_id');

        $inserts = $this->_build_query($cols, $data);
        if (empty($inserts)) return false;

        $this->db->query("INSERT INTO " . DB_PREFIX . "emailtemplate SET ".implode($inserts,", "));

		$new_id = $this->db->getLastId();

		$languages = $this->model_localisation_language->getLanguages();

		$cols = EmailTemplateDescriptionDAO::describe('emailtemplate_description_id', 'emailtemplate_id', 'language_id');
		$descriptions = array();

		foreach($languages as $language) {
			$langId = $language['language_id'];

			if (!isset($descriptions[$langId])) {
				$descriptions[$langId] = array();
			}

			foreach($cols as $col => $type) {
				if (isset($data[$col][$langId])) {
					$descriptions[$langId][$col] = $data[$col][$langId];
				} elseif (isset($defaultTemplateDescriptions[$langId][$col])) {
					$descriptions[$langId][$col] = $defaultTemplateDescriptions[$langId][$col];
				} else {
					$descriptions[$langId][$col] = '';
				}
			}
		}

		foreach($descriptions as $langId => $data) {
			$data['language_id'] = (int)$langId;
			$data['emailtemplate_id'] = $new_id;

			$this->insertTemplateDescription($data);
		}

		if (!empty($data['default_emailtemplate_id'])) {
			$data = $this->getTemplateShortcodes($default_emailtemplate_id);
			$shortcodes = array();
			foreach($data as $row) {
				$shortcodes[$row['emailtemplate_shortcode_code']] = $row['emailtemplate_shortcode_example'];
			}
			$this->insertTemplateShortCodes($new_id, $shortcodes);
		}

        $this->_delete_cache();

        return $new_id;
	}

	/**
	 * Add new template description row
	 *
	 * @return new row identifier
	 */
	public function insertTemplateDescription(array $data) {
		if (empty($data)) return false;

		$cols = EmailTemplateDescriptionDAO::describe('emailtemplate_description_id');

		$inserts = $this->_build_query($cols, $data);
		if (empty($inserts)) return false;

		$sql = "INSERT INTO " . DB_PREFIX . "emailtemplate_description SET ".implode($inserts,", ");
		$this->db->query($sql);

		$new_id = $this->db->getLastId();

        $this->_delete_cache();

        return $new_id;
	}

	/**
	 * Insert Template Short Codes
	 */
	public function insertTemplateShortCodes($id, $data = array()) {
		$cols = EmailTemplateShortCodesDAO::describe();
		$return = 0;

		$this->db->query("DELETE FROM " . DB_PREFIX . "emailtemplate_shortcode WHERE `emailtemplate_id` = '". (int)$id . "'");

		if ($data) {
			foreach($data as $code => $example) {
				if (in_array($code,  array('config', 'emailtemplate', 'showcase_selection', 'title', 'server')))
					continue;

				if (is_array($example)) {
					foreach($example as $example2 => $val) {
						if (is_string($val) || is_int($val)) {
							$data = array(
								'emailtemplate_id' => $id,
								'emailtemplate_shortcode_type' => 'auto',
								'emailtemplate_shortcode_code' => ($code . '.' . $example2),
								'emailtemplate_shortcode_example' => $val
							);

							$inserts = $this->_build_query($cols, $data);
							if (empty($inserts)) return false;

							$this->db->query("INSERT INTO " . DB_PREFIX . "emailtemplate_shortcode SET ".implode($inserts,", "));
							$return++;
						}
					}
				} else {
					$data = array(
						'emailtemplate_id' => $id,
						'emailtemplate_shortcode_type' => 'auto',
						'emailtemplate_shortcode_code' => $code,
						'emailtemplate_shortcode_example' => $example
					);

					$inserts = $this->_build_query($cols, $data);
					if (empty($inserts)) return false;

					$this->db->query("INSERT INTO " . DB_PREFIX . "emailtemplate_shortcode SET ".implode($inserts,", "));
					$return++;
				}
			}
		} else {
			$result = $this->db->query("SELECT emailtemplate_key FROM " . DB_PREFIX . "emailtemplate WHERE `emailtemplate_id` = '". (int)$id . "' LIMIT 1");

			if ($result->row) {
				$file = DIR_APPLICATION . 'model/module/emailtemplate/install/shortcode/' . $result->row['emailtemplate_key'] . '.sql';

				if (file_exists($file)) {
					$stmnts = $this->_parse_sql($file);

					foreach($stmnts as $stmnt) {
						$stmnt = str_replace('{_ID}', (int)$id, $stmnt);

						$this->db->query($stmnt);

						$return++;
					}
				}

				// Store Data
				$config_keys = array('title', 'name', 'url', 'owner', 'address', 'email', 'telephone', 'fax', 'country_id', 'zone_id', 'tax', 'tax_default', 'customer_price');

				$query = "INSERT INTO `" . DB_PREFIX . "emailtemplate_shortcode` (`emailtemplate_shortcode_code`, `emailtemplate_shortcode_type`, `emailtemplate_shortcode_example`, `emailtemplate_id`) VALUES ";

				foreach($config_keys as $i => $key) {
					$value = $this->config->get('config_'.$key);

					if($key == 'url' && !$value) {
						$value = HTTP_CATALOG;
					}

					$query .= ($i == 0 ? '' : ', ') . "('". $this->db->escape('store_'.$key) . "', 'auto', '". $this->db->escape($value) . "', " . (int)$id . ")";
				}

				$this->db->query($query);
			}
		}

		$this->db->query("UPDATE " . DB_PREFIX . "emailtemplate SET `emailtemplate_shortcodes` = '1' WHERE `emailtemplate_id` = '". (int)$id . "'");

		$this->_delete_cache();

		return $return;
	}

	/**
	 * Edit existing template row
	 *
	 * @param int - emailtemplate.id
	 * @param array - column => value
	 * @return returns true if row was updated with new data
	 */
	public function updateTemplate($id, array $data) {
		$queries = array();
		$affected = 0;

		$cols = EmailTemplateDAO::describe('emailtemplate_id');

		$updates = $this->_build_query($cols, $data);

		if ($updates) {
			$sql = "UPDATE " . DB_PREFIX . "emailtemplate SET ".implode($updates,", ") . " WHERE `emailtemplate_id` = '". (int)$id . "'";
			$this->db->query($sql);

			$affected += $this->db->countAffected();
		}

		$cols = EmailTemplateDescriptionDAO::describe('emailtemplate_description', 'emailtemplate_id', 'language_id');
		$descriptions = array();

		foreach($cols as $col => $type) {
			if (isset($data[$col]) && is_array($data[$col])) {
				foreach($data[$col] as $langId => $val) {
					if (!isset($descriptions[$langId])) {
						$descriptions[$langId] = array();
					}
					$descriptions[$langId][$col] = $val;
				}
			}
		}

		foreach($descriptions as $langId => $data) {
			$langId = (int)$langId;
			$updates = $this->_build_query($cols, $data);
			if (empty($updates)) continue;

			$result = $this->db->query("SELECT count(`emailtemplate_id`) AS total FROM " . DB_PREFIX . "emailtemplate_description WHERE `emailtemplate_id` = '". (int)$id . "' AND `language_id` = '{$langId}'");
			if ($result->row['total'] == 0) {
				$query = "INSERT INTO " . DB_PREFIX . "emailtemplate_description SET `emailtemplate_id` = '". (int)$id . "', `language_id` = '{$langId}', ".implode($updates,", ");
			} else {
				$query = "UPDATE " . DB_PREFIX . "emailtemplate_description SET ".implode($updates,", ") . " WHERE `emailtemplate_id` = '". (int)$id . "' AND `language_id` = '{$langId}'";
			}
			$this->db->query($query);

			if ($affected == 0 && $this->db->countAffected()) {
				$this->db->query("UPDATE " . DB_PREFIX . "emailtemplate SET `emailtemplate_modified` = NOW() WHERE `emailtemplate_id` = '". (int)$id . "'");
			}
			$affected += $this->db->countAffected();
		}

		$this->_delete_cache();

		return ($affected > 0) ? $affected : false;
	}

	public function updateTemplatesStatus(array $selected, $status = false) {
		$affected = 0;

		if ($selected) {
			if ($status) {
				$status = 1;
			} else {
				$status = 0;
			}

			foreach($selected as $id){
				$sql = "UPDATE " . DB_PREFIX . "emailtemplate SET emailtemplate_status = '{$status}' WHERE `emailtemplate_id` = '". (int)$id . "'";

				$this->db->query($sql);

				$affected += $this->db->countAffected();
			}
		}

		$this->_delete_cache();

		return ($affected > 0) ? $affected : false;
	}

	/**
	 * Delete template row
	 *
	 * @param mixed array||int - emailtemplate.id
	 * @return int - row count effected
	 */
	public function deleteTemplate($data) {
		$ids = array();
		if (is_array($data)) {
			foreach($data as $var) {
				$ids[] = (int)$var;
			}
		} else {
			$ids[] = (int)$data;
		}

		if (($key = array_search(1, $ids)) !== false) {
			unset($ids[$key]);
		}

		foreach($ids as $id) {
			$sql = "SELECT emailtemplate_id FROM " . DB_PREFIX . "emailtemplate WHERE emailtemplate_key = (SELECT emailtemplate_key FROM " . DB_PREFIX . "emailtemplate WHERE emailtemplate_id = '". (int)$id . "' AND emailtemplate_default = 1) AND emailtemplate_id != '". (int)$id . "'";
			$result = $this->db->query($sql);
			foreach($result->rows as $row) {
				$ids[] = $row['emailtemplate_id'];
			}
		}

		$queries = array();
		$queries[] = "DELETE FROM `" . DB_PREFIX . "emailtemplate_description` WHERE `emailtemplate_id` IN('".implode("', '", $ids)."')";
		$queries[] = "DELETE FROM `" . DB_PREFIX . "emailtemplate_shortcode` WHERE `emailtemplate_id` IN('".implode("', '", $ids)."')";
		$queries[] = "DELETE FROM " . DB_PREFIX . "emailtemplate WHERE `emailtemplate_id` IN('".implode("', '", $ids)."')";

		foreach($queries as $query) {
			$this->db->query($query);
		}

		$affected = $this->db->countAffected();

		$this->_delete_cache();

		return $affected;
	}

	/**
	 * Delete template row
	 *
	 * @param mixed array||int - emailtemplate.id
	 * @return int - row count effected
	 */
	public function deleteLogs($data) {
		if (empty($data)) return false;

		$ids = array();
		if (is_array($data)) {
			foreach($data as $var) {
				$ids[] = (int)$var;
			}
		} else {
			$ids[] = (int)$data;
		}

		$query = "DELETE FROM `" . DB_PREFIX . "emailtemplate_logs` WHERE `emailtemplate_log_id` IN('".implode("', '", $ids)."')";

		$this->db->query($query);

		$affected = $this->db->countAffected();

		$this->_delete_cache();

		return $affected;
	}

	/**
	 * Delete template row
	 *
	 * @param mixed array
	 * @return int - row count effected
	 */
	public function deleteTemplateDescription($data) {
		$cond = array();
		if (isset($data['language_id'])) {
			$cond[] = "`language_id` = '" . (int)$data['language_id'] . "'";
		}

		$query = "DELETE FROM `" . DB_PREFIX . "emailtemplate_description` WHERE ".implode("', '", $cond);
		$this->db->query($query);

		$this->_delete_cache();

		$affected = $this->db->countAffected();
		return ($affected > 0) ? $affected : false;
	}

	/**
	 * Get template enum types
	 */
	public function getTemplateKeys() {
		$return = array();
		$query = "SELECT `emailtemplate_key`, count(`emailtemplate_id`) AS `total`
					FROM " . DB_PREFIX . "emailtemplate
				   WHERE `emailtemplate_default` = 1 AND `emailtemplate_key` != ''
				GROUP BY `emailtemplate_key`
				ORDER BY `emailtemplate_key` ASC";
		$result = $this->_fetch($query);

		foreach($result->rows as $row) {
			$return[] = array(
				'value' => $row['emailtemplate_key'],
				'label' => $row['emailtemplate_key'] . ($row['total'] > 1 ? (' ('.$row['total'].')') : '')
			);
		}

		return $return;
	}

	/**
	 * Get template shortcodes
	 */
	public function getTemplateShortcodes($data, $keyCleanUp = false) {
		$cond = array();

		if (is_array($data)) {
			if (isset($data['emailtemplate_shortcode_id']) && $data['emailtemplate_shortcode_id'] != 0) {
				$cond[] = "es.`emailtemplate_shortcode_id` = '".(int)$data['emailtemplate_shortcode_id']."'";
			}

			if (isset($data['emailtemplate_id']) && $data['emailtemplate_id'] != 0) {
				$cond[] = "es.`emailtemplate_id` = '".(int)$data['emailtemplate_id']."'";
			}

			if (isset($data['emailtemplate_shortcode_type'])) {
				$cond[] = "es.`emailtemplate_shortcode_type` = '".$this->db->escape($data['emailtemplate_shortcode_type'])."'";
			}

			if (isset($data['emailtemplate_key'])) {
				$result = $this->_fetch("SELECT emailtemplate_id FROM " . DB_PREFIX . "emailtemplate WHERE emailtemplate_key = '".$this->db->escape($data['emailtemplate_key'])."' AND emailtemplate_default = 1 LIMIT 1");
				$cond[] = "es.`emailtemplate_id` = '".(int)$result->row['emailtemplate_id']."'";
			}
		} else {
			$cond[] = "es.`emailtemplate_id` = '".(int)$data."'";
		}

		$query = "SELECT es.* FROM `" . DB_PREFIX . "emailtemplate_shortcode` es";
		if (!empty($cond)) {
			$query .= ' WHERE ' . implode(' AND ', $cond);
		}

		$sort_data = array(
			'id' => 'es.`emailtemplate_shortcode_id`',
			'emailtemplate_id' => 'es.`emailtemplate_id`',
			'code' => 'es.`emailtemplate_shortcode_code`',
			'example' => 'es.`emailtemplate_shortcode_example`',
			'type' => 'es.`emailtemplate_shortcode_type`'
		);
		if (isset($data['sort']) && in_array($data['sort'], array_keys($sort_data))) {
			$query .= " ORDER BY " . $sort_data[$data['sort']];
		} else {
			$query .= " ORDER BY es.`emailtemplate_shortcode_code`";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$query .= " DESC";
		} else {
			$query .= " ASC";
		}

		if (is_array($data) && (isset($data['start']) || isset($data['limit']))) {
			if (!isset($data['start']) || $data['start'] < 0) {
				$data['start'] = 0;
			}
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}
			$query .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$result = $this->db->query($query);
		$cols = EmailTemplateShortCodesDAO::describe();

		foreach($result->rows as $key => &$row) {
			if ($row['emailtemplate_shortcode_type'] == 'auto_serialize' && $row['emailtemplate_shortcode_example']) {
				$row['emailtemplate_shortcode_example'] = unserialize(base64_decode($row['emailtemplate_shortcode_example']));
			}

			if ($keyCleanUp) {
				foreach($cols as $col => $type) {
					$key = (strpos($col, 'emailtemplate_shortcode_') === 0 && substr($col, -3) != '_id') ? substr($col, 24) : $col;
					if (!isset($row[$key]) && isset($row[$col])) {
						$row[$key] = $row[$col];
						unset($row[$col]);
					}
				}
			}
		}

		return $result->rows;
	}

	/**
	 * Get template shortcodes
	 * @param array - $data
	 */
	public function getTotalTemplateShortcodes($data = array()) {
		$cond = array();

		if (is_array($data)) {
			if (isset($data['emailtemplate_shortcode_id']) && $data['emailtemplate_shortcode_id'] != 0) {
				$cond[] = "es.`emailtemplate_shortcode_id` = '".(int)$data['emailtemplate_shortcode_id']."'";
			}

			if (isset($data['emailtemplate_id']) && $data['emailtemplate_id'] != 0) {
				$cond[] = "es.`emailtemplate_id` = '".(int)$data['emailtemplate_id']."'";
			}

			if (isset($data['emailtemplate_shortcode_type'])) {
				$cond[] = "es.`emailtemplate_shortcode_type` = '".$this->db->escape($data['emailtemplate_shortcode_type'])."'";
			}

			if (isset($data['emailtemplate_key'])) {
				$result = $this->_fetch("SELECT emailtemplate_id FROM " . DB_PREFIX . "emailtemplate WHERE emailtemplate_key = '".$this->db->escape($data['emailtemplate_key'])."' AND emailtemplate_default = 1 LIMIT 1");
				$cond[] = "es.`emailtemplate_id` = '".(int)$result->row['emailtemplate_id']."'";
			}
		} else {
			$cond[] = "es.`emailtemplate_id` = '".(int)$data."'";
		}

		$query = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "emailtemplate_shortcode` es";
		if (!empty($cond)) {
			$query .= ' WHERE ' . implode(' AND ', $cond);
		}

		$result = $this->db->query($query);
		if (empty($result->row)) return array();

		return $result->row['total'];
	}

	/**
	 * Edit shortcode
	 *
	 * @param int - emailtemplate_shortcode.emailtemplate_shortcode_id
	 * @param array - column => value
	 * @return int affected row count
	 */
	public function updateTemplateShortcode($id, array $data) {
		if (empty($data) && !is_numeric($id)) return false;
		$cols = EmailTemplateShortCodesDAO::describe();

		$updates = $this->_build_query($cols, $data);
		if (!$updates) return false;

		$sql = "UPDATE " . DB_PREFIX . "emailtemplate_shortcode SET ".implode($updates,", ") . " WHERE emailtemplate_shortcode_id = '". (int)$id . "'";
		$this->db->query($sql);

		$this->_delete_cache();

		$affected = $this->db->countAffected();
		return ($affected > 0) ? $affected : false;
	}

	/**
	 * Delete template shortcode(s)
	 * Detech if template is custom and deletes shortcodes for custom templates
	 * @todo admin on load custom template populate from default template if empty
	 *
	 * @param int template_id
	 * @param array selected emailtemplate_shortcode_id - if empty deletes all
	 * @return int - row count effected
	 */
	public function deleteTemplateShortcodes($id, $data = array()) {
		$cond = array();
		$cond[] = "`emailtemplate_id` = '". (int)$id . "'";

		if (isset($data['emailtemplate_shortcode_id'])) {
			if (is_array($data['emailtemplate_shortcode_id'])) {
				$ids = array();
				foreach($data['emailtemplate_shortcode_id'] as $emailtemplate_shortcode_id) {
					$ids[] = (int)$emailtemplate_shortcode_id;
				}
				$cond[] = "`emailtemplate_shortcode_id` IN(". implode(', ', $ids) .")";
			} else {
				$cond[] = "`emailtemplate_shortcode_id` = '". (int)$data['emailtemplate_shortcode_id'] . "'";
			}
		}

		if ($cond) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "emailtemplate_shortcode` WHERE ".implode(" AND ", $cond));
			$affected = $this->db->countAffected();

			if ($affected > 0) {
				$result = $this->db->query("SELECT 1 FROM `" . DB_PREFIX . "emailtemplate_shortcode` WHERE emailtemplate_id = '". (int)$id . "' LIMIT 1");
				if ($result->num_rows == 0) {
					$this->db->query("UPDATE " . DB_PREFIX . "emailtemplate SET `emailtemplate_shortcodes` = 0 WHERE `emailtemplate_id` = ".$id);
				}

				$this->_delete_cache();
			}

			return $affected;
		}
	}

	/**
	 * Get complete order email
	 *
	 * @param int $order_id
	 * @param array $data
	 * @param array $overwrite
	 */
	public function getCompleteOrderEmail($order_id, $data = array(), $overwrite = array()) {
		$order_id = (int)$order_id;
		$order_status_id = $this->config->get('config_order_status_id');

		$this->load->model('sale/order');
		$this->load->model('tool/image');

		$order_info = $this->model_sale_order->getOrder($order_id);

		if (isset($data['language_id'])) {
			$language_id = $data['language_id'];
		} elseif (isset($order->row['language_id'])) {
			$language_id = $order->row['language_id'];
		}
		if (empty($language_id)) {
		    $language_id = $this->config->get('config_language_id');
		}

		if (isset($data['store_id'])) {
			$store_id = $data['store_id'];
		} elseif (isset($order->row['store_id'])) {
			$store_id = $order->row['store_id'];
		} else {
			$store_id = 0;
		}

		# Demo email template
		$template = new EmailTemplate($this->request, $this->registry);

		// Load Customer Group - check file exists for old versions of opencart
		if (isset($order_info['customer_group_id']) && $order_info['customer_group_id']) {
			$this->load->model('sale/customer_group');
			$customer_group_info = $this->model_sale_customer_group->getCustomerGroup($order_info['customer_group_id']);
		}

		// Load affiliate data into email
		if (isset($order_info['affiliate_id']) && $order_info['affiliate_id']) {
			$this->load->model('marketing/affiliate');
			$affiliate_info = $this->model_marketing_affiliate->getAffiliate($order_info['affiliate_id']);
		}

		// Order Products
		$order_product_query = $this->db->query("SELECT op.*, p.image, p.sku FROM " . DB_PREFIX . "order_product op LEFT JOIN " . DB_PREFIX . "product p ON (p.product_id = op.product_id) WHERE order_id = '" . (int)$order_id . "'");

		// Check for any downloadable products
		$download_status = false;

		foreach ($order_product_query->rows as $order_product) {
			// Check if there are any linked downloads
			$product_download_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "product_to_download` WHERE product_id = '" . (int)$order_product['product_id'] . "'");

			if ($product_download_query->row['total']) {
				$download_status = true;
			}
		}

		// Gift Voucher
		$chk = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "order_voucher'");
		if ($chk->num_rows) {
			$order_voucher_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'");
		} else {
			$order_voucher_query = false;
		}

		// Order Totals
		$order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order ASC");

		// Order Status
		$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_info['language_id'] . "'");
		if ($order_status_query->num_rows) {
			$order_status = $order_status_query->row['name'];
		} else {
			$order_status = '';
		}

		// Send out order confirmation mail
		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage($language_id);

		$language = new Language($language_info['directory']);
		$language->setPath(DIR_CATALOG.'language/');
		$language->load('default'); // @todo remove left in for backwards compatbility with 2.0.2.0
		$language->load($language_info['directory']);
		$langData = $language->load('mail/order');

		$template->addData($langData);

		$template->data['affiliate'] = (isset($affiliate_info)) ? $affiliate_info : '';
		$template->data['customer_group'] = (isset($customer_group_info['name'])) ? $customer_group_info['name'] : '';
		$template->data['new_order_status'] = $order_status;
		$template->data['subject'] = sprintf($language->get('text_new_subject'), $order_info['store_name'], $order_id);

		$template->data['text_greeting'] = sprintf($language->get('text_new_greeting'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'));
		$template->data['text_link'] = $language->get('text_new_link');
		$template->data['text_download'] = $language->get('text_new_download');
		$template->data['text_order_detail'] = $language->get('text_new_order_detail');
		$template->data['text_order_status'] = $language->get('text_new_order_status');
		$template->data['text_instruction'] = $language->get('text_new_instruction');
		$template->data['text_order_id'] = $language->get('text_new_order_id');
		$template->data['text_date_added'] = $language->get('text_new_date_added');
		$template->data['text_payment_method'] = $language->get('text_new_payment_method');
		$template->data['text_shipping_method'] = $language->get('text_new_shipping_method');
		$template->data['text_email'] = $language->get('text_new_email');
		$template->data['text_telephone'] = $language->get('text_new_telephone');
		$template->data['text_ip'] = $language->get('text_new_ip');
		$template->data['text_payment_address'] = $language->get('text_new_payment_address');
		$template->data['text_shipping_address'] = $language->get('text_new_shipping_address');
		$template->data['text_product'] = $language->get('text_new_product');
		$template->data['text_model'] = $language->get('text_new_model');
		$template->data['text_quantity'] = $language->get('text_new_quantity');
		$template->data['text_price'] = $language->get('text_new_price');
		$template->data['text_total'] = $language->get('text_new_total');
		$template->data['text_footer'] = $language->get('text_new_footer');
		$template->data['text_powered'] = $language->get('text_new_powered');

		$template->data['store_name'] = $order_info['store_name'];
		$template->data['store_url'] = $order_info['store_url'];
		$template->data['customer_id'] = $order_info['customer_id'];

		$template->data['link'] = $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id;
		$template->data['link_tracking'] = $template->getTracking($template->data['link']);

		if ($download_status) {
			$template->data['download'] = $order_info['store_url'] . 'index.php?route=account/download';
			$template->data['download_tracking'] = $template->getTracking($template->data['download']);
		} else {
			$template->data['download'] = '';
		}

		$template->data['order_id'] = $order_id;
		if ($language->get('date_format_short') && $language->get('date_format_short') != 'date_format_short') {
			$template->data['date_added'] = date($language->get('date_format_short'), strtotime($order_info['date_added']));
		} else {
			$template->data['date_added'] = $order_info['date_added'];
		}
		$template->data['payment_method'] = $order_info['payment_method'];
		$template->data['shipping_method'] = $order_info['shipping_method'];
		$template->data['email'] = $order_info['email'];
		$template->data['telephone'] = $order_info['telephone'];
		$template->data['ip'] = $order_info['ip'];

		$template->data['comment'] = ($order_info['comment']) ? str_replace(array("\r\n", "\r", "\n"), "<br />", $order_info['comment']) : '';
		$template->data['instruction'] = '';

		$template->data['shipping_address'] = $this->_formatAddress($order_info, 'shipping', $order_info['shipping_address_format']);
		$template->data['payment_address'] = $this->_formatAddress($order_info, 'payment', $order_info['payment_address_format']);

		// Products
		$template->data['products'] = array();
		foreach ($order_product_query->rows as $product) {
			$option_data = array();
			$order_option_query = $this->db->query("SELECT oo.*, pov.* FROM " . DB_PREFIX . "order_option oo LEFT JOIN " . DB_PREFIX . "product_option_value pov ON (pov.product_option_value_id = oo.product_option_value_id) WHERE oo.order_id = '" . (int)$order_id . "' AND oo.order_product_id = '" . (int)$product['order_product_id'] . "'");

			foreach ($order_option_query->rows as $option) {
				if ($option['type'] != 'file') {
					$value = $option['value'];
				} else {
					$value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
				}

				$price = false;
				if ((float)$option['price']) {
					$price = $this->currency->format($option['price'], $this->config->get('config_currency'));
				}

				$option_data[] = array(
					'name'  => $option['name'],
					'price'  => $price,
					'price_value' => ((float)$option['price']) ? $option['price'] : 0,
					'price_prefix'  => $option['price_prefix'],
					'value' => (utf8_strlen($value) > 120 ? utf8_substr($value, 0, 120) . '..' : $value)
				);
			}

			if (!empty($product['image'])) {
				$this->model_tool_image->setUrl($order_info['store_url']);

				$product['image'] = $this->model_tool_image->resize($product['image'], 50, 50);
			}

			$url = $template->data['store_url'] . '?route=product/product&product_id='.$product['product_id'];

			$template->data['products'][] = array(
				'url'     		=> $url,
				'url_tracking' 	=> $template->getTracking($url),
				'image'     	=> $product['image'],
				'product_id'	=> $product['order_product_id'],
				'sku'			=> $product['sku'],
				'name'     => $product['name'],
				'model'    => $product['model'],
				'option'   => $option_data,
				'quantity' => $product['quantity'],
				'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
				'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
			);
		}

		// Vouchers
		$template->data['vouchers'] = array();
		if ($order_voucher_query) {
			foreach ($order_voucher_query->rows as $voucher) {
				$template->data['vouchers'][] = array(
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
				);
			}
		}

		// Order Totals
		$template->data['totals'] = array();

		foreach ($order_total_query->rows as $total) {
			$template->data['totals'][] = array(
				'title' => $total['title'],
				'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
			);
		}

		$emailData = array(
			'key' => 'order.customer',
			'language_id' => $language_id,
			'store_id' => $store_id
		);

		if (isset($data['emailtemplate_config_id'])) {
			$emailData['emailtemplate_config_id'] = $data['emailtemplate_config_id'];
		}

		$template->load($emailData);

		// Overwrite config data
		if ($overwrite) {
			foreach($overwrite as $key => $val) {
				if (strpos($key, 'emailtemplate_config_') === 0 && substr($key, -3) != '_id') {
					$key = substr($key, 21); #21=strlen('emailtemplate_config_')
				}

				if (isset($template->data['config'][$key])) {
					$template->data['config'][$key] = $val;
				}
			}
		}

		$template->set('insert_shortcodes', false);
		$template->set('emailtemplate_log_id', false);

		$template->build();

		$template->sent();

		return $template;
	}

	/**
	 * Load show for admin emails
	 */
	public function getShowcase($data = array()) {
		$return = array();

		$this->load->model('catalog/product');
		$this->load->model('sale/order');
		$this->load->model('sale/products');
		$this->load->model('tool/image');

		$registry = clone $this->registry;

		$registry->set('customer', new Customer($registry));

		$oConfig = $registry->get('config');

		$oConfig->set('config_customer_group_id', $data['customer_group_id']);

		$registry->set('config', $oConfig);

		$file = DIR_SYSTEM . 'library/tax.php';

		if (file_exists($file)) {
			include_once($file);
		} else {
			trigger_error('Error: Could not load library ' . $file . '!');
			exit();
		}

		$oTax = new Tax($registry);

		if (isset($data['store_tax_default'])) {
			if ($data['store_tax_default'] == 'shipping') {
				$oTax->setShippingAddress($data['store_country_id'], $data['store_zone_id']);
			} elseif ($data['store_tax_default'] == 'payment') {
				$oTax->setPaymentAddress($data['store_country_id'], $data['store_zone_id']);
			}
		} else {
			if (method_exists($oTax, 'setZone')) {
				$oTax->setZone($data['store_country_id'], $data['store_zone_id']); # oc:151
			}
		}

		if (method_exists($oTax, 'setStoreAddress')) {
			$oTax->setStoreAddress($data['store_country_id'], $data['store_zone_id']);
		}

		$products = array();
		$order_products = array();

		if ($data['config']['showcase_related'] && isset($data['order_id'])) {
			$result = $this->model_sale_order->getOrderProducts($data['order_id']);
			if ($result) {
				foreach($result as $row) {
					$order_products[$row['product_id']] = $row;
				}
				foreach($result as $row) {
					$result2 = $this->model_sale_product->getProductRelated($row['product_id'], $data['language_id'], $data['store_id'], $data['customer_group_id']);
					if ($result2) {
						foreach($result2 as $row2) {
							if (!isset($products[$row2['product_id']]) && !isset($order_products[$row2['product_id']])) {
								$products[$row2['product_id']] = $row2;
							}
						}
					}
				}
			}
		}

		if (count($products) < $data['config']['showcase_limit']) {
			$limit = count($order_products) + $data['config']['showcase_limit'];
			$result = false;
			switch($data['config']['showcase']) {
				case 'bestsellers':
					$result = $this->model_sale_products->getBestSellerProducts($limit, $data['language_id'], $data['store_id'], $data['customer_group_id']);
				break;

				case 'latest':
					$result = $this->model_sale_products->getLatestProducts($limit, $data['language_id'], $data['store_id'], $data['customer_group_id']);
				break;

				case 'specials':
					$result = $this->model_sale_products->getProductSpecials(array('start' => 0, 'limit' => $limit), $data['language_id'], $data['store_id'], $data['customer_group_id']);
				break;

				case 'popular':
					$result = $this->model_sale_products->getPopularProducts($limit, $data['language_id'], $data['store_id'], $data['customer_group_id']);
				break;

				case 'products':
					if ($data['config']['showcase_selection']) {
						$result = array();
						$selection = explode(',', $data['config']['showcase_selection']);
						foreach($selection as $product_id) {
							if ($product_id && !isset($products[$product_id])) {
								$row = $this->model_sale_products->getProduct($product_id, $data['language_id'], $data['store_id'], $data['customer_group_id']);
								if ($row) {
									$result[] = $row;
								}
							}
						}
					}
				break;
			}

			if(!empty($result)){
				foreach($result as $row) {
					if (count($products) >= $data['config']['showcase_limit']) {
						break;
					}
					if (!isset($products[$row['product_id']]) && !isset($order_products[$row['product_id']])) {
						$products[$row['product_id']] = $row;
					}
				}
			}
		}

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->model_tool_image->setUrl($data['store_ssl']);
		} else {
			$this->model_tool_image->setUrl($data['store_url']);
		}

		if (!empty($products)) {
			foreach($products as $row) {
				if (!isset($row['product_id'])) continue;

				if ($row['image']) {
					$row['image'] = $this->model_tool_image->resize($row['image'], 100, 100);
				}

				if (!$data['store_customer_price']) {
					$price = $this->currency->format($oTax->calculate($row['price'], $row['tax_class_id'], $data['store_tax']));
				} else {
					$price = false;
				}

				$product_specials = $this->model_catalog_product->getProductSpecials($row['product_id']);

				$special = false;
				foreach ($product_specials  as $product_special) {
					if (($product_special['date_start'] == '0000-00-00' || $product_special['date_start'] < date('Y-m-d')) && ($product_special['date_end'] == '0000-00-00' || $product_special['date_end'] > date('Y-m-d'))) {
						$special = $this->currency->format($oTax->calculate($product_special['price'], $row['tax_class_id'], $data['store_tax']));
						break;
					}
				}

				$item = array(
					'product_id' => $row['product_id'],
					'image' => $row['image'],
					'name' => $row['name'],
					'rating' => round($row['rating']),
					'reviews' => $row['reviews'] ? $row['reviews'] : 0,
					'name_short' => EmailTemplate::truncate_str($row['name'], 28, ''),
					'description' => EmailTemplate::truncate_str(strip_tags(html_entity_decode($row['description'], ENT_QUOTES, 'UTF-8')), 100),
					'price' => $price,
					'special' => $special,
					'url' => HTTP_CATALOG . 'index.php?route=product/product&amp;product_id=' . $row['product_id'],
				);

				if ($item['name_short'] != $item['name']) {
					$item['preview'] = $item['name'] . ' - ' . $item['description'];
				} else {
					$item['preview'] = $item['description'];
				}

				$return[] = $item;
			}
		}

		return $return;
	}

	public function installTemplates($request = array()) {
		$stores = $this->getStores();

		foreach($stores as $store) {
			$data = array(
				'emailtemplate_config_tracking_campaign_name' => $store["store_name"],
				'emailtemplate_config_name' => $store["store_name"],
				'emailtemplate_config_version' => EmailTemplate::$version,
				'store_id' => $store["store_id"]
			);

			$this->model_setting_setting->deleteSetting('emailtemplate', $store["store_id"]); // Clean up any old code

			if (!empty($store['config_logo']) && file_exists(DIR_IMAGE.$store['config_logo'])) {
				$data['emailtemplate_config_logo'] = $store['config_logo'];

				list($data['emailtemplate_config_logo_width'], $data['emailtemplate_config_logo_height']) = getimagesize(DIR_IMAGE.$store['config_logo']);
			}

			if ($store["store_id"] == 0) {
				$this->updateConfig(1, $data);
			} else {
				$this->cloneConfig(1, $data);
			}
		}

		foreach(EmailTemplate::$original_templates as $key) {
			if (isset($request['original_templates'][$key])) {
				$this->installTemplate($key);
			}
		}

		$this->updateModification();

		// Events
		if (file_exists(DIR_APPLICATION . 'model/extension/event.php')) {
			$this->load->model('extension/event');

			if ($this->model_extension_event) {
				$this->model_extension_event->addEvent('emailtemplate', 'post.return.add', 'module/emailtemplate/send_return_mail');
			}
		}

		$this->_delete_cache();

		return true;
	}

	public function install(){
		// Increase `modification` length
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "modification` CHANGE `xml` `xml` MEDIUMTEXT NOT NULL");

		// Add language_id to `customers`
		$chk = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "customer` LIKE 'language_id'");
		if (!$chk->num_rows) {
			$result = $this->db->query("ALTER TABLE `" . DB_PREFIX . "customer` ADD `language_id` int(11) NOT NULL DEFAULT '0' AFTER `store_id`");
		}

		// Add weight to `orders`
		$chk = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "order` LIKE 'weight'");
		if (!$chk->num_rows) {
			$result = $this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD `weight` decimal(15,8) NOT NULL DEFAULT '0.00000000' AFTER `invoice_prefix`");
		}

		$file = DIR_APPLICATION . 'model/module/emailtemplate/install/install.sql';

		if (!file_exists($file)) {
			$this->session->data['error'] = sprintf($this->language->get('error_install_sql'), $file);

			return false;
		}

		$stmnts = $this->_parse_sql($file);

		foreach($stmnts as $stmnt) {
			$this->db->query($stmnt);
		}

		$this->db->query("UPDATE `" . DB_PREFIX . "emailtemplate_description` SET language_id  = '".(int)$this->config->get('config_language_id')."' WHERE emailtemplate_id = 1");
	}

	/**
	 * Insert Template from SQL file
	 *
	 * @param string $key
	 * @return int template_id
	 */
	public function installTemplate($key) {
		$emailtemplate_id = false;

		// Load Config
		$config = $this->getConfig(1);

		// Template
		$file = DIR_APPLICATION . 'model/module/emailtemplate/install/template/' . $key . '.sql';

		if (!file_exists($file)) return false;

		foreach($this->_parse_sql($file) as $i => $stmnt) {
			if ($emailtemplate_id) {
				$stmnt = str_replace('{_ID}', (int)$emailtemplate_id, $stmnt);
			}

			$this->db->query($stmnt);

			if ($i == 0) {
				$emailtemplate_id = $this->db->getLastId();
			}
		}

		$emailtemplate = $this->getTemplate($emailtemplate_id);

		if(!$emailtemplate) return false;

		// Template Descriptions
		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		$emailtemplates_description = $this->getTemplateDescription(array('emailtemplate_id' => $emailtemplate_id), 1);

		foreach ($languages as $language) {
			$data = $emailtemplates_description;

			if (EmailTemplate::$replace_language_vars) {
				$oLanguage = new Language($language['directory']);

				if (substr($emailtemplate['emailtemplate_key'], 0, 6) != 'admin.' && defined('DIR_CATALOG')) {
					$oLanguage->setPath(DIR_CATALOG.'language/');
				}

				$oLanguage->load('default'); // @todo remove left in for backwards compatbility with 2.0.2.0
				$oLanguage->load($language['directory']);

				$langData = array();

				foreach(explode(',', $emailtemplate['emailtemplate_language_files']) as $language_file) {
					if ($language_file) {
						$_langData = $oLanguage->load(trim($language_file));
						if ($_langData) {
							$langData = array_merge($langData, $_langData);
						}
					}
				}

				$find = array();
				$replace = array();

				foreach($langData as $i => $val) {
					if ((is_string($val) && (strpos($val, '%s') === false) || is_int($val))) {
						$find[$i] = '{$'.$i.'}';
						$replace[$i] = $val;
					}
				}

				foreach($data as $col => $val) {
					if ($val && is_string($val)) {
						$data[$col] = str_replace($find, $replace, $val);
					}
				}
			}

			$data['language_id'] = $language['language_id'];

			$data['emailtemplate_id'] = $emailtemplate_id;

			$this->insertTemplateDescription($data);

			if (!EmailTemplate::$replace_language_vars) {
				break;
			}
		}

		$this->deleteTemplateDescription(array('language_id' => 0, 'emailtemplate_id' => $emailtemplate_id));

		$file = DIR_APPLICATION . 'model/module/emailtemplate/install/shortcode/' . $key . '.sql';

		if (file_exists($file)) {
			foreach($this->_parse_sql($file) as $stmnt) {
				$stmnt = str_replace('{_ID}', (int)$emailtemplate_id, $stmnt);

				$this->db->query($stmnt);
			}

			$this->db->query("UPDATE " . DB_PREFIX . "emailtemplate SET emailtemplate_shortcodes = 1 WHERE emailtemplate_id = '".(int)$emailtemplate_id."'");
		}

		// Store Data
		$config_keys = array('title', 'name', 'url', 'owner', 'address', 'email', 'telephone', 'fax', 'country_id', 'zone_id', 'tax', 'tax_default', 'customer_price');

		$query = "INSERT INTO `" . DB_PREFIX . "emailtemplate_shortcode` (`emailtemplate_shortcode_code`, `emailtemplate_shortcode_type`, `emailtemplate_shortcode_example`, `emailtemplate_id`) VALUES ";

		foreach($config_keys as $i => $key) {
			$value = $this->config->get('config_'.$key);

			if($key == 'url' && !$value) {
				$value = HTTP_CATALOG;
			}

			$query .= ($i == 0 ? '' : ', ') . "('". $this->db->escape('store_'.$key) . "', 'auto', '". $this->db->escape($value) . "', " . (int)$emailtemplate_id . ")";
		}

		$this->db->query($query);

		return $emailtemplate_id;
	}

	/**
	 * Apply upgrade queries
	 */
	public function upgrade() {
		$current_ver = $this->checkVersion();

		if (!$current_ver) return false;

		$dir = DIR_APPLICATION.'model/module/emailtemplate/upgrade/';

		$upgrades = glob($dir.'*.sql');
		natsort($upgrades);

		foreach($upgrades as $i => $file) {
			$ver = substr(substr($file, 0, -4), strlen($dir));

			if (version_compare($current_ver, $ver) >= 0 || version_compare(2.5, $ver) >= 0){
				continue;
			}

			$stmnts = $this->_parse_sql($file);
			foreach($stmnts as $stmnt) {
				$this->db->query($stmnt);
			}
		}

		$this->db->query("IF EXISTS( SELECT NULL FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '" . DB_PREFIX . "modification' AND table_schema = '" . DB_DATABASE . "' AND column_name = 'xml')  THEN
					ALTER TABLE `" . DB_PREFIX . "modification` CHANGE `xml` `xml` MEDIUMTEXT NOT NULL;
				ELSE
					ALTER TABLE `" . DB_PREFIX . "modification` CHANGE `code` `code` MEDIUMTEXT NOT NULL;
				END IF;");

		$this->db->query("UPDATE " . DB_PREFIX . "emailtemplate_config SET emailtemplate_config_version = '".EmailTemplate::$version."'");

		$old_files = array(
			DIR_APPLICATION . 'language/*/mail/order_.php',
			DIR_APPLICATION . 'model/module/emailtemplate/vqmod/',
			DIR_APPLICATION . 'model/module/emailtemplate/install-shortcodes.sql',
			DIR_APPLICATION . 'model/module/emailtemplate/install/modification/order.update_admin.xml',
			DIR_APPLICATION . 'view/image/emailtemplate-sprite.png',
			DIR_APPLICATION . 'view/image/mediaIcons.png',
			DIR_APPLICATION . 'view/image/vQmod_Icon.png',
			DIR_TEMPLATE . 'mail/order_update.tpl',
			DIR_TEMPLATE . 'mail/return_history.tpl',
			DIR_CATALOG . 'view/theme/*/template/mail/return_admin.tpl'
		);

		foreach ($old_files as $file) {
			if (file_exists($file) && is_writeable($file)) {
				unlink($file);
			} else {
				$files = glob($file);
				if ($files) {
					foreach ($files as $file){
						if (file_exists($file) && is_writeable($file)) {
							unlink($file);
						}
					}
				}
			}
		}

		$this->updateModification('core');

		$this->updateModification('lang');

		$this->updateModification();

		$this->_delete_cache();

		return true;
	}

	/**
	 * Method handles removing table
	 */
	public function uninstall() {
		$queries = array();
		$queries[] = "DROP TABLE IF EXISTS " . DB_PREFIX . "emailtemplate";
		$queries[] = "DROP TABLE IF EXISTS " . DB_PREFIX . "emailtemplate_config";
		$queries[] = "DROP TABLE IF EXISTS `" . DB_PREFIX . "emailtemplate_description`";
		$queries[] = "DROP TABLE IF EXISTS `" . DB_PREFIX . "emailtemplate_logs`";
		$queries[] = "DROP TABLE IF EXISTS `" . DB_PREFIX . "emailtemplate_shortcode`";

		foreach($queries as $query) {
			$this->db->query($query);
		}

		$this->load->model('extension/modification');

		// Modification Templates
		$modification_info = $this->getModificationByCode('emailtemplates');

		if ($modification_info) {
			$this->model_extension_modification->deleteModification($modification_info['modification_id']);
		}

		// Modification Core
		$modification_info = $this->getModificationByCode('emailtemplates_core');

		if ($modification_info) {
			$this->model_extension_modification->deleteModification($modification_info['modification_id']);
		}

		// Modification Language
		$dir = DIR_APPLICATION . 'model/module/emailtemplate/install/language';

		$files = glob($dir . '*.xml');

		if ($files) {
			foreach ($files as $file) {
				$filename = substr(basename($file), 0, -4);

				$modification_info = $this->getModificationByCode('emailtemplates_language_' . $filename);

				if ($modification_info) {
					$this->model_extension_modification->deleteModification($modification_info['modification_id']);
				}
			}
		}

		// Events
		if (file_exists(DIR_APPLICATION . 'model/extension/event.php')) {
			$this->load->model('extension/event');

			if ($this->model_extension_event) {
				$this->model_extension_event->deleteEvent('emailtemplate');
			}
		}

		$this->_delete_cache();

		return true;
	}

	/**
	 * Check version of files with databse
	 *
	 * @return version upgrading from
	 */
	public function checkVersion() {
		$result = $this->db->query("SELECT `emailtemplate_config_version` FROM " . DB_PREFIX . "emailtemplate_config WHERE `emailtemplate_config_id` = 1 LIMIT 1");

		if (version_compare(EmailTemplate::$version, $result->row['emailtemplate_config_version']) > 0) {
			return $result->row['emailtemplate_config_version'];
		}

		return false;
	}

	/**
	 * Insert Log
	 */
	public function insertLog($data) {
		$cols = EmailTemplateLogsDAO::describe();
		$logData = array();

		foreach($cols as $col => $type) {
			// Exact match
			if(!empty($data[$col])){
				$logData[$col] = $data[$col];
			} else {
				// Short key match
				$key = (strpos($col, 'emailtemplate_log_') === 0) ? substr($col, 18) : $col;

				if(!empty($data[$key])){
					$logData[$col] = $data[$key];
				}
			}
		}

		$logData['emailtemplate_log_sent'] = '';

		if (!isset($logData['emailtemplate_log_type'])) {
			$logData['emailtemplate_log_type'] = 'SYSTEM';
		}

		$inserts = $this->_build_query($cols, $logData);
		if (empty($inserts)) return false;

		$query = "INSERT IGNORE INTO " . DB_PREFIX . "emailtemplate_logs SET ".implode($inserts,", ");

		$this->db->query($query);

		return $this->db->getLastId();
	}

	/**
	 * Get template files
	 *
	 * @return array
	 */
	public function getTemplateFiles($theme) {
		$return = array(
			'catalog' => array(),
			'catalog_default' => array(),
			'admin' => array(),
			'dirs' => array()
		);

		$base = substr(DIR_SYSTEM, 0, -7);
		$dir = 'catalog/view/theme/' . $theme . '/template/mail/';
		$return['dirs']['catalog'] = $dir;
		$files = glob($base.$dir.'*.tpl');
		if ($files) {
			foreach($files as $file) {
				$filename = basename($file);
				if ($filename[0] == '_') continue;
				$return['catalog'][] = $filename;
			}
		}

		if ($theme != "default") {
			$dir = 'catalog/view/theme/default/template/mail/';
			$return['dirs']['catalog_default'] = $dir;
			$files = glob($base.$dir.'*.tpl');
			if ($files) {
				foreach($files as $file) {
					$filename = basename($file);
					if ($filename[0] == '_') continue;
					$return['catalog_default'][] = $filename;
				}
			}
		}

		$dir = str_replace($base, '', DIR_TEMPLATE) .'mail/';
		$return['dirs']['admin'] = $dir;
		$files = glob($base.$dir.'*.tpl');
		if ($files) {
			foreach($files as $file) {
				$filename = basename($file);
				if ($filename[0] == '_') continue;
				$return['admin'][] = $filename;
			}
		}

		return $return;
	}

	/**
	 * Get stores include default store OR return selected store if store_id
	 *
	 * @return array
	 */
	private $stores = null;
	public function getStores() {
		if (is_null($this->stores)) {
			$this->load->model('setting/store');
			$this->load->model('setting/setting');
			$this->load->model('localisation/language');

			$this->stores = array();
			$stores = array();
			$stores[0] = array(
				'store_id' 	 => 0,
				'name' => $this->config->get('config_name')
			);
			$stores = array_merge($stores, $this->model_setting_store->getStores());

			foreach ($stores as $result) {
				$storeId = $result['store_id'];
				$result = array_merge($result, $this->model_setting_setting->getSetting("config", $storeId));
				$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "language WHERE code = '" . $this->db->escape($result['config_language']) . "' LIMIT 1");
				$language = $query->row;

				$this->stores[$storeId] = $result;

				$this->stores[$storeId]['store_url'] = (isset($result['config_url'])) ? $result['config_url'] : (defined('HTTP_CATALOG') ? HTTP_CATALOG : HTTP_SERVER);
				$this->stores[$storeId]['store_ssl'] = (isset($result['config_ssl'])) ? $result['config_ssl'] : (defined('HTTPS_CATALOG') ? HTTPS_CATALOG : (defined('HTTPS_SERVER') ? HTTPS_SERVER : HTTP_SERVER));

				$this->stores[$storeId]['store_name'] = $this->_stripHtml($result['name']);
				$this->stores[$storeId]['store_name_short'] = $this->_truncate($result['name']);
				if (isset($result['config_title'])) {
					$this->stores[$storeId]['store_title'] = $this->_stripHtml($result['config_title']);
				} else {
					$this->stores[$storeId]['store_title'] = $this->stores[$storeId]['store_name'];
				}

				$this->stores[$storeId]['language_id'] = $language['language_id'];
				$this->stores[$storeId]['language_name'] = $language['name'];
			}
		}

		return $this->stores;
	}

	public function getUrl($route, $key, $value) {
		$url = "index.php?route={$route}&{$key}={$value}";

		if ($this->config->get('config_seo_url')) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "'");
			if (!empty($query->row['keyword'])) {
				$url = $query->row['keyword'];
			}
		}

		return $url;
	}

	/**
	 * Missing from opencart 2.0.0.0
	 *
	 * @param unknown_type $code
	 */
	public function getModificationByCode($code) {
		switch($code) {
			case 'emailtemplate_core':
			case 'emailtemplates_core':
				$name = 'Email Templates Core';
			break;
			default:
				$name = 'Email Templates Language';
		}

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "modification WHERE (code = '" . $this->db->escape($code) . "' or name LIKE '" . $this->db->escape($name) . "')");

		return $query->row;
	}

	/**
	 * Update modification
	 */
	public function updateModification($name = '') {
		$this->load->model('extension/modification');

		switch(strtolower($name)){
			case 'core':
				$file = DIR_APPLICATION . 'model/module/emailtemplate/install/install.xml';

				if (file_exists($file)) {
					$modification_data = array(
						'name' => "Email Templates Core",
						'code' => "emailtemplates_core",
						'author' => "opencart-templates",
						'version' => EmailTemplate::$version,
						'link' => "http://www.opencart-templates.co.uk/advanced_professional_email_template",
						'xml' => file_get_contents($file),
						'status' => 1
					);
				}

				$modification_info = $this->getModificationByCode("emailtemplates_core");

				if ($modification_info) {
					$this->model_extension_modification->deleteModification($modification_info['modification_id']);
				}

				if(!empty($modification_data)){
					$this->model_extension_modification->addModification($modification_data);
				}

			break;

			case 'lang':
				$dir = DIR_APPLICATION . 'model/module/emailtemplate/install/language/';

				$files = glob($dir . '*.xml');

				if ($files) {
					foreach ($files as $file) {

						$filename = substr(basename($file), 0, -4);

						$modification_data = array(
							'name' => "Email Templates Language - " . ucwords($filename),
							'code' => "emailtemplates_language_" . $filename,
							'author' => "opencart-templates",
							'version' => EmailTemplate::$version,
							'link' => "http://www.opencart-templates.co.uk/advanced_professional_email_template",
							'xml' => file_get_contents($file),
							'status' => 1
						);

						$modification_info = $this->getModificationByCode('emailtemplates_language_' . $filename);

						if ($modification_info) {
							$this->model_extension_modification->deleteModification($modification_info['modification_id']);
						}

						$this->model_extension_modification->addModification($modification_data);
					}
				}

			break;

			default:
				$query = $this->db->query("SELECT emailtemplate_key FROM ".DB_PREFIX."emailtemplate WHERE `emailtemplate_default` = 1 AND `emailtemplate_status` = 1 AND `emailtemplate_key` IN('". implode("', '", EmailTemplate::$original_templates) . "')");

				if($query->rows){
					$modification_data = array(
						'name' => "Email Templates",
						'code' => "emailtemplates",
						'author' => "opencart-templates",
						'version' => EmailTemplate::$version,
						'link' => "http://www.opencart-templates.co.uk/advanced_professional_email_template",
						'status' => 1
					);

					$modification_data['xml'] = "<modification>
	<name>". $modification_data['name'] . "</name>
	<code>emailtemplates</code>
	<author>". $modification_data['author'] . "</author>
	<version>". $modification_data['version'] . "</version>
	<link>". $modification_data['link'] . "</link>";

					foreach($query->rows as $row) {
						$file = DIR_APPLICATION . 'model/module/emailtemplate/install/modification/'. $row['emailtemplate_key'] . '.xml';
						if (file_exists($file)) {
							$modification_data['xml'] .= "
	".file_get_contents($file);
						}
					}

					$modification_data['xml'] .= "
</modification>";
				}

				$modification_info = $this->getModificationByCode("emailtemplates");

				if ($modification_info) {
					$this->model_extension_modification->deleteModification($modification_info['modification_id']);
				}

				if(!empty($modification_data)){
					$this->model_extension_modification->addModification($modification_data);
				}
			break;
		}
	}

	private function _formatAddress($address, $address_prefix = '', $format = null) {
		$find = array();
		$replace = array();
		if ($address_prefix != "") {
			$address_prefix = trim($address_prefix, '_') . '_';
		}
		if (is_null($format) || $format == '') {
			$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}
		$vars = array(
				'firstname',
				'lastname',
				'company',
				'address_1',
				'address_2',
				'city',
				'postcode',
				'zone',
				'zone_code',
				'country'
		);
		foreach($vars as $var) {
			$find[$var] = '{'.$var.'}';
			if ($address_prefix && isset($address[$address_prefix.$var])) {
				$replace[$var] =  $address[$address_prefix.$var];
			} elseif (isset($address[$var])) {
				$replace[$var] =  $address[$var];
			} else {
				$replace[$var] =  '';
			}
		}
		return str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
	}

	/**
	 * Fetch query with caching
	 */
	private function _fetch($query) {
		$queryName = 'emailtemplate_sql_'.md5($query);

		$result = $this->cache->get($queryName);

		if ($result) {
			$result = (object)$result; // Hack convert back to object if using file cache
		} else {
			$result = $this->db->query($query);

			$this->cache->set($queryName, $result);
		}
		return $result;
	}

	/**
	 * Delete all cache files that begin with emailtemplate_
	 */
	private function _delete_cache($key = 'emailtemplate_sql_') {
		$files = glob(DIR_CACHE . 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '*');
		if ($files) {
    		foreach ($files as $file) {
      			if (is_file($file) && is_writable($file)) {
					unlink($file);
				}
    		}
		}
	}

	/**
	 * Method builds mysql for INSERT/UPDATE
	 *
	 * @param array $cols
	 * @param array $data
	 * @return array
	 */
	private function _build_query($cols, $data, $withoutCols = false) {
		if (empty($data)) return $data;
		$return = array();

		foreach ($cols as $col => $type) {
			if (!array_key_exists($col, $data)) continue;

			switch ($type) {
				case EmailTemplateAbstract::INT:
					if (strtoupper($data[$col]) == 'NULL') {
						$value = 'NULL';
					} else {
						$value = (int)$data[$col];
					}
					break;
				case EmailTemplateAbstract::FLOAT:
					$value = floatval($data[$col]);
					break;
				case EmailTemplateAbstract::DATE_NOW:
					$value = 'NOW()';
					break;
				case EmailTemplateAbstract::SERIALIZE:
					$value = "'".base64_encode(serialize($data[$col]))."'";
					break;
				default:
					$value = "'".$this->db->escape($data[$col])."'";
			}

			if ($withoutCols) {
				$return[] = "'{$value}'";
			} else {
				$return[] = " `{$col}` = {$value}";
			}
		}

		return empty($return) ? false : $return;
	}

	/**
	 * Parse SQL file and split into single sql statements.
	 *
	 * @param string $sql - file path
	 * @return array
	 */
	private function _parse_sql($file) {
		$sql = @fread(@fopen($file, 'r'), @filesize($file)) or die('problem reading sql:'.$file);
		$sql = str_replace(" `oc_", " `" . DB_PREFIX, $sql);

		$lines = explode("\n", $sql);
		$linecount = count($lines);
		$sql = "";
		for ($i = 0; $i < $linecount; $i++) {
			if (($i != ($linecount - 1)) || (strlen($lines[$i]) > 0)) {
				if (isset($lines[$i][0]) && $lines[$i][0] != "#") {
					$sql .= $lines[$i] . "\n";
				} else {
					$sql .= "\n";
				}
				$lines[$i] = "";
			}
		}

		$tokens = explode(';', $sql);
		$sql = "";

		$queries = array();
		$matches = array();

		$token_count = count($tokens);
		for ($i = 0; $i < $token_count; $i++) {

			if (($i != ($token_count - 1)) || (strlen($tokens[$i] > 0))) {
				$total_quotes = preg_match_all("/'/", $tokens[$i], $matches);
				$escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$i], $matches);
				$unescaped_quotes = $total_quotes - $escaped_quotes;

				if (($unescaped_quotes % 2) == 0) {
					$queries[] = trim($tokens[$i]);
					$tokens[$i] = "";
				} else {
					$temp = $tokens[$i] . ';';
					$tokens[$i] = "";
					$complete_stmt = false;

					for ($j = $i + 1; (!$complete_stmt && ($j < $token_count)); $j++) {
						$total_quotes = preg_match_all("/'/", $tokens[$j], $matches);
						$escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$j], $matches);
						$unescaped_quotes = $total_quotes - $escaped_quotes;

						if (($unescaped_quotes % 2) == 1) {
							$queries[] = trim($temp . $tokens[$j]);
							$tokens[$j] = "";
							$temp = "";
							$complete_stmt = true;
							$i = $j;
						} else {
							$temp .= $tokens[$j] . ';';
							$tokens[$j] = "";
						}

					}
				}
			}
		}

		return $queries;
	}

	/**
	 * Truncate Text
	 *
	 * @param string $text
	 * @param int $limit
	 * @param string $ellipsis
	 * @return string
	 */
	private function _truncate($text, $limit = 20, $ellipsis = '...') {
		if (is_string($text) && strlen($text) > $limit) {
			$text = trim(substr(strip_tags(html_entity_decode($text, ENT_QUOTES, 'UTF-8')), 0, $limit)) . $ellipsis;
		}
		return $text;
	}

	/**
	 * Strip HTML with entity decode.
	 *
	 * @param string $text
	 * @return string
	 */
	private function _stripHtml($text) {
		if (is_string($text)) {
			return strip_tags(html_entity_decode($text, ENT_QUOTES, 'UTF-8'));
		}
	}
}


/**
 * Data Access Object - Abstract
 */
abstract class EmailTemplateAbstract
{
	/**
	 * Data Types
	 */
	const INT = "INT";
	const TEXT = "TEXT";
	const SERIALIZE = "SERIALIZE";
	const FLOAT = "FLOAT";
	const DATE_NOW = "DATE_NOW";

	/**
	 * Filter from array, by unsetting element(s)
	 * @param string/array $filter - match array key
	 * @param array to be filtered
	 * @return array
	 */
	protected static function filterArray($filter, $array) {
		if ($filter === null) return $array;

		if (is_array($filter)) {
			foreach($filter as $f) {
				unset($array[$f]);
			}
		} else {
			unset($array[$filter]);
		}

		return $array;
	}

}

/**
 * Email Templates `emailtemplate`
 */
class EmailTemplateDAO extends EmailTemplateAbstract
{
	/**
	 * Columns & Data Types.
	 * @see EmailTemplateDAOAbstract::describe()
	 */
	public static function describe() {
		$filter = func_get_args();
		$cols = array(
				'emailtemplate_id' => EmailTemplateAbstract::INT,
				'emailtemplate_key' => EmailTemplateAbstract::TEXT,
				'emailtemplate_label' => EmailTemplateAbstract::TEXT,
				'emailtemplate_type' => EmailTemplateAbstract::TEXT,
				'emailtemplate_template' => EmailTemplateAbstract::TEXT,
				'emailtemplate_modified' => EmailTemplateAbstract::DATE_NOW,
				'emailtemplate_log' => EmailTemplateAbstract::INT,
				'emailtemplate_view_browser' => EmailTemplateAbstract::INT,
				'emailtemplate_mail_attachment' => EmailTemplateAbstract::TEXT,
				'emailtemplate_mail_to' => EmailTemplateAbstract::TEXT,
				'emailtemplate_mail_cc' => EmailTemplateAbstract::TEXT,
				'emailtemplate_mail_bcc' => EmailTemplateAbstract::TEXT,
				'emailtemplate_mail_from' => EmailTemplateAbstract::TEXT,
				'emailtemplate_mail_html' => EmailTemplateAbstract::INT,
				'emailtemplate_mail_plain_text' => EmailTemplateAbstract::INT,
				'emailtemplate_mail_sender' => EmailTemplateAbstract::TEXT,
				'emailtemplate_mail_replyto' => EmailTemplateAbstract::TEXT,
				'emailtemplate_mail_replyto_name' => EmailTemplateAbstract::TEXT,
				'emailtemplate_attach_invoice' => EmailTemplateAbstract::INT,
				'emailtemplate_language_files' => EmailTemplateAbstract::TEXT,
				'emailtemplate_wrapper_tpl' => EmailTemplateAbstract::TEXT,
				'emailtemplate_tracking_campaign_source' => EmailTemplateAbstract::TEXT,
				'emailtemplate_default' => EmailTemplateAbstract::INT,
				'emailtemplate_status' => EmailTemplateAbstract::INT,
				'emailtemplate_shortcodes' => EmailTemplateAbstract::INT,
				'emailtemplate_showcase' => EmailTemplateAbstract::INT,
				'emailtemplate_condition' => EmailTemplateAbstract::SERIALIZE,
				'emailtemplate_config_id' => EmailTemplateAbstract::INT,
				'store_id' => EmailTemplateAbstract::INT,
				'customer_group_id' => EmailTemplateAbstract::INT,
				'order_status_id' => EmailTemplateAbstract::INT,
				'event_id' => EmailTemplateAbstract::INT
		);

		return (!$filter)? $cols : self::filterArray($filter, $cols);
	}
}

/**
 * Email Templates `emailtemplate_description`
 */
class EmailTemplateDescriptionDAO extends EmailTemplateAbstract
{
	public static $content_count = 3;

	/**
	 * Columns & Data Types.
	 * @see EmailTemplateDAOAbstract::describe()
	 */
	public static function describe() {
		$filter = func_get_args();
		$cols = array(
				'emailtemplate_id' => EmailTemplateAbstract::INT,
				'language_id' => EmailTemplateAbstract::INT,
				'emailtemplate_description_subject' => EmailTemplateAbstract::TEXT,
				'emailtemplate_description_preview' => EmailTemplateAbstract::TEXT,
				'emailtemplate_description_content1' => EmailTemplateAbstract::TEXT,
				'emailtemplate_description_content2' => EmailTemplateAbstract::TEXT,
				'emailtemplate_description_content3' => EmailTemplateAbstract::TEXT,
				'emailtemplate_description_comment' => EmailTemplateAbstract::TEXT,
				'emailtemplate_description_unsubscribe_text' => EmailTemplateAbstract::TEXT
		);

		return (!$filter)? $cols : self::filterArray($filter, $cols);
	}
}

/**
 * Email Templates `emailtemplate_shortcode`
 */
class EmailTemplateShortCodesDAO extends EmailTemplateAbstract
{
	/**
	 * Columns & Data Types.
	 * @see EmailTemplateDAOAbstract::describe()
	 */
	public static function describe() {
		$filter = func_get_args();
		$cols = array(
				'emailtemplate_shortcode_id' => EmailTemplateAbstract::INT,
				'emailtemplate_shortcode_code' => EmailTemplateAbstract::TEXT,
				'emailtemplate_shortcode_type' => EmailTemplateAbstract::TEXT,
				'emailtemplate_shortcode_example' => EmailTemplateAbstract::TEXT,
				'emailtemplate_id' => EmailTemplateAbstract::INT
		);

		return (!$filter)? $cols : self::filterArray($filter, $cols);
	}
}

/**
 * Email Templates `emailtemplate_logs`
 */
class EmailTemplateLogsDAO extends EmailTemplateAbstract
{
	/**
	 * Columns & Data Types.
	 * @see EmailTemplateDAOAbstract::describe()
	 */
	public static function describe() {
		$filter = func_get_args();
		$cols = array(
				'emailtemplate_log_id' => EmailTemplateAbstract::INT,
				'emailtemplate_log_sent' => EmailTemplateAbstract::DATE_NOW,
				'emailtemplate_log_read' => EmailTemplateAbstract::TEXT,
				'emailtemplate_log_read_last' => EmailTemplateAbstract::TEXT,
				'emailtemplate_log_type' => EmailTemplateAbstract::TEXT,
				'emailtemplate_log_to' => EmailTemplateAbstract::TEXT,
				'emailtemplate_log_from' => EmailTemplateAbstract::TEXT,
				'emailtemplate_log_sender' => EmailTemplateAbstract::TEXT,
				'emailtemplate_log_text' => EmailTemplateAbstract::TEXT,
				'emailtemplate_log_content' => EmailTemplateAbstract::TEXT,
				'emailtemplate_log_subject' => EmailTemplateAbstract::TEXT,
				'emailtemplate_log_enc' => EmailTemplateAbstract::TEXT,
				'emailtemplate_id' => EmailTemplateAbstract::INT,
				'order_id' => EmailTemplateAbstract::INT,
				'customer_id' => EmailTemplateAbstract::INT,
				'store_id' => EmailTemplateAbstract::INT
		);

		return (!$filter)? $cols : self::filterArray($filter, $cols);
	}
}

/**
 * Config `emailtemplate_config`
 */
class EmailTemplateConfigDAO extends EmailTemplateAbstract
{
	/**
	 * Columns & Data Types.
	 * @see EmailTemplateAbstract::describe()
	 */
	public static function describe() {
		$filter = func_get_args();
		$cols = array(
				'emailtemplate_config_id' => EmailTemplateAbstract::INT,
				'emailtemplate_config_name' => EmailTemplateAbstract::TEXT,
				'emailtemplate_config_email_width' => EmailTemplateAbstract::INT,
				'emailtemplate_config_email_responsive' => EmailTemplateAbstract::INT,
				'emailtemplate_config_wrapper_tpl' => EmailTemplateAbstract::TEXT,
				'emailtemplate_config_page_bg_color' => EmailTemplateAbstract::TEXT,
				'emailtemplate_config_body_bg_color' => EmailTemplateAbstract::TEXT,
				'emailtemplate_config_body_bg_image' => EmailTemplateAbstract::TEXT,
				'emailtemplate_config_body_bg_image_repeat' => EmailTemplateAbstract::TEXT,
				'emailtemplate_config_body_bg_image_position' => EmailTemplateAbstract::TEXT,
				'emailtemplate_config_body_font_color' => EmailTemplateAbstract::TEXT,
				'emailtemplate_config_body_link_color' => EmailTemplateAbstract::TEXT,
				'emailtemplate_config_body_heading_color' => EmailTemplateAbstract::TEXT,
				'emailtemplate_config_body_section_bg_color' => EmailTemplateAbstract::TEXT,
				'emailtemplate_config_page_footer_text' => EmailTemplateAbstract::TEXT,
				'emailtemplate_config_footer_text' => EmailTemplateAbstract::TEXT,
				'emailtemplate_config_footer_align' => EmailTemplateAbstract::TEXT,
				'emailtemplate_config_footer_valign' => EmailTemplateAbstract::TEXT,
				'emailtemplate_config_footer_font_color' => EmailTemplateAbstract::TEXT,
				'emailtemplate_config_footer_height' => EmailTemplateAbstract::INT,
				'emailtemplate_config_footer_section_bg_color' => EmailTemplateAbstract::TEXT,
				'emailtemplate_config_header_bg_color' => EmailTemplateAbstract::TEXT,
				'emailtemplate_config_header_bg_image' => EmailTemplateAbstract::TEXT,
				'emailtemplate_config_header_height' => EmailTemplateAbstract::INT,
				'emailtemplate_config_header_border_color' => EmailTemplateAbstract::TEXT,
				'emailtemplate_config_header_section_bg_color' => EmailTemplateAbstract::TEXT,
				'emailtemplate_config_head_text' => EmailTemplateAbstract::TEXT,
				'emailtemplate_config_head_section_bg_color' => EmailTemplateAbstract::TEXT,
				'emailtemplate_config_view_browser_text' => EmailTemplateAbstract::TEXT,
				'emailtemplate_config_view_browser_theme' => EmailTemplateAbstract::INT,
				'emailtemplate_config_logo' => EmailTemplateAbstract::TEXT,
				'emailtemplate_config_logo_align' => EmailTemplateAbstract::TEXT,
				'emailtemplate_config_logo_font_color' => EmailTemplateAbstract::TEXT,
				'emailtemplate_config_logo_font_size' => EmailTemplateAbstract::INT,
				'emailtemplate_config_logo_height' => EmailTemplateAbstract::INT,
				'emailtemplate_config_logo_valign' => EmailTemplateAbstract::TEXT,
				'emailtemplate_config_logo_width' => EmailTemplateAbstract::INT,
				'emailtemplate_config_shadow_top' => EmailTemplateAbstract::SERIALIZE,
				'emailtemplate_config_shadow_left' => EmailTemplateAbstract::SERIALIZE,
				'emailtemplate_config_shadow_right' => EmailTemplateAbstract::SERIALIZE,
				'emailtemplate_config_shadow_bottom' => EmailTemplateAbstract::SERIALIZE,
				'emailtemplate_config_showcase' => EmailTemplateAbstract::TEXT,
				'emailtemplate_config_showcase_limit' => EmailTemplateAbstract::INT,
				'emailtemplate_config_showcase_selection' => EmailTemplateAbstract::TEXT,
				'emailtemplate_config_showcase_title' => EmailTemplateAbstract::TEXT,
				'emailtemplate_config_showcase_related' => EmailTemplateAbstract::INT,
				'emailtemplate_config_showcase_section_bg_color' => EmailTemplateAbstract::TEXT,
				'emailtemplate_config_text_align' => EmailTemplateAbstract::TEXT,
				'emailtemplate_config_page_align' => EmailTemplateAbstract::TEXT,
				'emailtemplate_config_tracking' => EmailTemplateAbstract::INT,
				'emailtemplate_config_tracking_campaign_name' => EmailTemplateAbstract::TEXT,
				'emailtemplate_config_tracking_campaign_term' => EmailTemplateAbstract::TEXT,
				'emailtemplate_config_theme' => EmailTemplateAbstract::TEXT,
				'emailtemplate_config_table_quantity' => EmailTemplateAbstract::INT,
				'emailtemplate_config_style' => EmailTemplateAbstract::TEXT,
				'emailtemplate_config_log' => EmailTemplateAbstract::INT,
				'emailtemplate_config_log_read' => EmailTemplateAbstract::INT,
				'emailtemplate_config_status' => EmailTemplateAbstract::INT,
				'emailtemplate_config_version' => EmailTemplateAbstract::TEXT,
				'customer_group_id' => EmailTemplateAbstract::INT,
				'language_id' => EmailTemplateAbstract::INT,
				'store_id' => EmailTemplateAbstract::INT
		);

		return (!$filter)? $cols : self::filterArray($filter, $cols);
	}
}
