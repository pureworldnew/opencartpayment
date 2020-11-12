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
	 * Get Template
	 * @param int $id
	 * @return array
	 */
	public function getTemplate($id, $language_id = null, $keyCleanUp = false) {
		$id = (int)$id;

		$query = "SELECT * FROM " . DB_PREFIX . "emailtemplate WHERE `emailtemplate_id` = '{$id}'";
		$result = $this->_fetch($query);
		$return = ($result->row) ? $result->row : array();

		if ($keyCleanUp) {
			$cols = EmailTemplateDAO::describe();

			foreach($cols as $col => $type) {
				$key = (strpos($col, 'emailtemplate_') === 0 && substr($col, -3) != '_id') ? substr($col, 14) : $col;

				if (!isset($return[$key])) {
					$return[$key] = $return[$col];
					unset($return[$col]);
				}
			}
		}

		if ($language_id) {
			$result = $this->getTemplateDescription(array('emailtemplate_id' => $id, 'language_id' => $language_id), 1);

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

		return $return;
	}

	/**
	 * Get Templates
	 *
	 * @return array
	 */
	public function getTemplates($data = array()) {
		$cond = array();

		if (isset($data['store_id'])) {
			if (is_numeric($data['store_id'])) {
				$cond[] = "e.`store_id` = '".(int)$data['store_id']."'";
			} else {
				$cond[] = "e.`store_id` IS NULL";
			}
		}

		if (isset($data['language_id']) && $data['language_id'] != 0) {
			$cond[] = "ed.`language_id` = '".(int)$data['language_id']."'";
		}

		if (isset($data['customer_group_id']) && $data['customer_group_id'] != 0) {
			$cond[] = "e.`customer_group_id` = '".(int)$data['customer_group_id']."'";
		}

		if (isset($data['emailtemplate_key']) && $data['emailtemplate_key'] != "") {
			$cond[] = "e.`emailtemplate_key` = '".$this->db->escape($data['emailtemplate_key'])."'";
		}

		if (isset($data['emailtemplate_status']) && $data['emailtemplate_status'] != "") {
			$cond[] = "e.`emailtemplate_status` = '".$this->db->escape($data['emailtemplate_status'])."'";
		} else {
			$cond[] = "e.`emailtemplate_status` = 1";
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

		if (isset($data['language_id']) && $data['language_id'] != 0) {
			$query = "SELECT e.*, ed.* FROM " . DB_PREFIX . "emailtemplate e LEFT JOIN `" . DB_PREFIX . "emailtemplate_description` ed ON(ed.emailtemplate_id = e.emailtemplate_id)";
		} else {
			$query = "SELECT e.* FROM " . DB_PREFIX . "emailtemplate e";
		}

		if (!empty($cond)) {
			$query .= ' WHERE ' . implode(' AND ', $cond);
		}

		$sort_data = array(
			'label' => 'e.`emailtemplate_label`',
			'key' => 'e.`emailtemplate_key`',
			'template' => 'e.`emailtemplate_template`',
			'modified' => 'e.`emailtemplate_modified`',
			'shortcodes' => 'e.`emailtemplate_shortcodes`',
			'status' => 'e.`emailtemplate_status`',
			'id' => 'e.`emailtemplate_id`',
			'store' => 'e.`store_id`',
			'customer' => 'e.`customer_group_id`',
			'language' => 'ed.`language_id`'
		);
		if (isset($data['sort']) && in_array($data['sort'], array_keys($sort_data))) {
			$query .= " ORDER BY " . $sort_data[$data['sort']];
		} else {
			$query .= " ORDER BY e.`emailtemplate_label`";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$query .= " DESC";
		} else {
			$query .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}
			$query .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$result = $this->_fetch($query);

		return $result->rows;
	}

	/**
	 * Get Template
	 * @param array $data
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

		$result = $this->_fetch($query);

		return ($limit == 1) ? $result->row : $result->rows;
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

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}
			$query .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$result = $this->_fetch($query);
		$cols = EmailTemplateShortCodesDAO::describe();

		foreach($result->rows as $key => &$row) {
			foreach($cols as $col => $type) {

				if (isset($row[$col]) && $type == EmailTemplateDAO::SERIALIZE) {
					if ($row[$col]) {
						$row[$col] = unserialize(base64_decode($row[$col]));
					} else {
						$row[$col] = array();
					}
				}

				if ($keyCleanUp) {
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
	 * Load showcase for store emails
	 * NOTE: duplicate method admin/model/module/emailtemplate.php
	 */
	public function getShowcase($data = array()) {
		$return = array();

		$this->load->model('catalog/product');
		$this->load->model('account/order');
		$this->load->model('tool/image');

		$products = array();
		$order_products = array();

		if ($data['config']['showcase_related'] && isset($data['order_id'])) {
			$result = $this->model_account_order->getOrderProducts($data['order_id']);
			if ($result) {
				foreach($result as $row) {
					$order_products[$row['product_id']] = $row;
				}
				foreach($result as $row) {
					$result2 = $this->model_catalog_product->getProductRelated($row['product_id']);
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
			$result = false;
			switch($data['config']['showcase']) {
				case 'bestsellers':
					$result = $this->model_catalog_product->getBestSellerProducts($data['config']['showcase_limit']);
				break;

				case 'latest':
					$result = $this->model_catalog_product->getLatestProducts($data['config']['showcase_limit']);
				break;

				case 'specials':
					$result = $this->model_catalog_product->getProductSpecials(array('start' => 0, 'limit' => $data['config']['showcase_limit']));
				break;

				case 'popular':
					$result = $this->model_catalog_product->getPopularProducts($data['config']['showcase_limit']);
				break;

				case 'products':
					if ($data['config']['showcase_selection']) {
						$result = array();
						$selection = explode(',', $data['config']['showcase_selection']);
						foreach($selection as $product_id) {
							if ($product_id && !isset($products[$product_id])) {
								$row = $this->model_catalog_product->getProduct($product_id);
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

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($row['price'], $row['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}

				if ((float)$row['special']) {
					$special = $this->currency->format($this->tax->calculate($row['special'], $row['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}

				$item = array(
					'product_id' => $row['product_id'],
					'image' => $row['image'],
					'name' => $row['name'],
					'rating' => round($row['rating']),
					'reviews' => $row['reviews'] ? $row['reviews'] : 0,
					'name_short' => EmailTemplate::truncate_str($row['name'], 28, ''),
					'description' => EmailTemplate::truncate_str($row['description'], 100),
					'price' => $price,
					'special' => $special,
					'url' => $this->url->link('product/product', 'product_id=' . $row['product_id'])
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

	/**
	 * Get Email Template Config
	 *
	 * @param int||array $identifier
	 * @param bool $outputFormatting
	 * @param bool $keyCleanUp
	 * @return array
	 */
	public function getConfig($data, $outputFormatting = false, $keyCleanUp = false) {
		$cond = array();

		if (is_array($data)) {
			if (isset($data['store_id'])) {
				$cond[] = "`store_id` = '".(int)$data['store_id']."'";
			}
			if (isset($data['language_id'])) {
				$cond[] = "(`language_id` = '".(int)$data['language_id']."' OR `language_id` = 0)";
			}
		} elseif (is_numeric($data)) {
			$cond[] = "`emailtemplate_config_id` = '" . (int)$data . "'";
		} else {
			return false;
		}

		$query = "SELECT * FROM " . DB_PREFIX . "emailtemplate_config";
		if (!empty($cond)) {
			$query .= " WHERE " . implode(" AND ", $cond);
		}
		$query .= " ORDER BY `language_id` DESC LIMIT 1";

		$result = $this->_fetch($query);
		if (empty($result->row)) return false;
		$row = $result->row;

		$cols = EmailTemplateConfigDAO::describe();
		foreach($cols as $col => $type) {
			if (isset($row[$col]) && $type == EmailTemplateDAO::SERIALIZE) {
				if ($row[$col]) {
					$row[$col] = unserialize(base64_decode($row[$col]));
				}
			}
		}

		if ($outputFormatting) {
			$row = $this->formatConfig($row);
		}

		if ($outputFormatting) {
			foreach($row as $col => $val) {
				$key = (strpos($col, 'emailtemplate_config_') === 0 && substr($col, -3) != '_id') ? substr($col, 21) : $col;
				if (!isset($row[$key])) {
					unset($row[$col]);
					$row[$key] = $val;
				}
			}
		}

		return $row;
	}

	/**
	 * Return array of configs
	 * @param array - $data
	 */
	public function getConfigs($data = array(), $outputFormatting = false) {
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

		if (isset($data['status']) && $data['status'] != "") {
			$cond[] = "AND ec.`emailtemplate_config_status` = '".$this->db->escape($data['status'])."'";
		} else {
			$cond[] = "AND ec.`emailtemplate_config_status` = 1";
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
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}
			$query .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$result = $this->_fetch($query);
		if (empty($result->rows)) return array();
		$rows = $result->rows;

		$cols = EmailTemplateConfigDAO::describe();
		foreach($rows as $key => $row) {
			foreach($cols as $col => $type) {
				if (isset($row[$col]) && $type == EmailTemplateDAO::SERIALIZE) {
					if ($row[$col]) {
						$row[$col] = unserialize(base64_decode($row[$col]));
					}
				}
			}
			$rows[$key] = $row;
		}

		return $rows;
	}


	/**
	 * Return array of configs
	 *
	 * @param array - $data
	 */
	public function formatConfig($data = array()) {
		$this->load->model('tool/image');

		$data['emailtemplate_config_head_text'] = html_entity_decode($data['emailtemplate_config_head_text'], ENT_QUOTES, 'UTF-8');
		$data['emailtemplate_config_view_browser_text'] = html_entity_decode($data['emailtemplate_config_view_browser_text'], ENT_QUOTES, 'UTF-8');
		$data['emailtemplate_config_page_footer_text'] = html_entity_decode($data['emailtemplate_config_page_footer_text'], ENT_QUOTES, 'UTF-8');
		$data['emailtemplate_config_footer_text'] = html_entity_decode($data['emailtemplate_config_footer_text'], ENT_QUOTES, 'UTF-8');

		if(defined("HTTP_IMAGE")) {
			$url =  HTTP_IMAGE;
		} elseif ($this->config->get('config_url')) {
			$url = $this->config->get('config_url') . 'image/';
		} else {
			$url = HTTP_SERVER . 'image/';
		}

		foreach(array('left', 'right') as $col) {
			if (isset($data['emailtemplate_config_shadow_top'][$col.'_img']) && file_exists(DIR_IMAGE . $data['emailtemplate_config_shadow_top'][$col.'_img'])) {
				$data['emailtemplate_config_shadow_top'][$col.'_thumb'] = $url . $data['emailtemplate_config_shadow_top'][$col.'_img'];
			}

			if (isset($data['emailtemplate_config_shadow_bottom'][$col.'_img']) && file_exists(DIR_IMAGE . $data['emailtemplate_config_shadow_bottom'][$col.'_img'])) {
				$data['emailtemplate_config_shadow_bottom'][$col.'_thumb'] = $url . $data['emailtemplate_config_shadow_bottom'][$col.'_img'];
			}
		}

		return $data;
	}


	/**
	 * Insert Template Short Codes
	 */
	public function insertTemplateShortCodes($id, $data) {
		$id = (int)$id;
		$cols = EmailTemplateShortCodesDAO::describe();
		$return = 0;

		$this->db->query("DELETE FROM " . DB_PREFIX . "emailtemplate_shortcode WHERE `emailtemplate_id` = '{$id}'");

		foreach($data as $code => $example) {
			if (in_array($code,  array('config', 'emailtemplate', 'showcase_selection'))) continue;

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

		$this->db->query("UPDATE " . DB_PREFIX . "emailtemplate SET `emailtemplate_shortcodes` = '1' WHERE `emailtemplate_id` = '{$id}'");

		$this->_delete_cache();

		return $return;
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
	 * Get Template Log
	 * @return array
	 */
	public function getTemplateLog($data, $keyCleanUp = false) {
		$cond = array();

		if (is_array($data)) {
			if (isset($data['emailtemplate_log_id'])) {
				$cond[] = "`emailtemplate_log_id` = '".(int)$data['emailtemplate_log_id']."'";
			}
			if (isset($data['emailtemplate_log_enc'])) {
				$cond[] = "`emailtemplate_log_enc` = '".$this->db->escape($data['emailtemplate_log_enc'])."'";
			}
		} elseif (is_numeric($data)) {
			$cond[] = "`emailtemplate_log_id` = '" . (int)$data . "'";
		} else {
			return false;
		}

		$query = "SELECT * FROM `" . DB_PREFIX . "emailtemplate_logs`";
		if (!empty($cond)) {
			$query .= " WHERE " . implode(" AND ", $cond);
		}
		$query .= " LIMIT 1";

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
	 * Return last insert id for logs.
	 * @return int
	 */
	public function getLastTemplateLogId() {
		$query = "SELECT MAX(emailtemplate_log_id) as emailtemplate_log_id FROM `" . DB_PREFIX . "emailtemplate_logs`";
		$result = $this->db->query($query);

		return $result->row['emailtemplate_log_id'];
	}

	/**
	 * Record Email as read, if not already read.
	 * Else update last read
	 *
	 * @param unknown $id
	 * @param unknown $enc
	 * @return boolean
	 */
	public function readTemplateLog($id, $enc) {
		$id = (int)$id;
		$enc = $this->db->escape($enc);

		// Update recent read
		$sql = "UPDATE " . DB_PREFIX . "emailtemplate_logs SET emailtemplate_log_read_last = NOW() WHERE emailtemplate_log_id = '{$id}' AND emailtemplate_log_enc = '{$enc}'";
		$this->db->query($sql);

		// Should always affected log if everything is correct
		if ($this->db->countAffected() > 0) {

			// Update first read only if its empty, we already know enc is correct
			$sql = "UPDATE " . DB_PREFIX . "emailtemplate_logs SET emailtemplate_log_read = NOW() WHERE emailtemplate_log_id = '{$id}' AND emailtemplate_log_read IS NULL";
			$this->db->query($sql);

			return true;
		}

		return false;
	}

	/**
	 * Fetch query with caching
	 *
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
	 * Delete all cache files that begin with emailtemplate_
	 *
	 */
	private function _delete_cache($key = 'emailtemplate_sql_') {
		$files = glob(DIR_CACHE . 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '*');
		if ($files) {
			foreach ($files as $file) {
				if (file_exists($file) && is_writable($file)) {
					unlink($file);
				}
			}
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