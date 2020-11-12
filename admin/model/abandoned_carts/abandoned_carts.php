<?php
class ModelAbandonedCartsAbandonedCarts extends Model {
	public function createTables() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "abandonedcart` (`abandonedcart_id` int(11) NOT NULL AUTO_INCREMENT, `cart_id` int(11) NOT NULL, `customer_id` int(11) NOT NULL, `firstname` varchar(255) NOT NULL, `lastname` varchar(255) NOT NULL, `email` varchar(96) NOT NULL, `telephone` varchar(32) NOT NULL, `session_id` varchar(32) NOT NULL, `product_id` int(11) NOT NULL, `recurring_id` int(11) NOT NULL, `option` text NOT NULL, `quantity` int(5) NOT NULL, `email_notify` tinyint(4) NOT NULL, `store_id` int(11) NOT NULL, `ip` varchar(40) NOT NULL, `date_added` datetime NOT NULL, PRIMARY KEY (`abandonedcart_id`), KEY `cart_id` (`customer_id`,`session_id`,`product_id`,`recurring_id`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=38");
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "abandonedcart_history` (`abandonedcart_history_id` int(11) NOT NULL AUTO_INCREMENT, `cart_id` int(11) NOT NULL, `customer_id` int(11) NOT NULL, `firstname` varchar(255) NOT NULL, `lastname` varchar(255) NOT NULL, `email` varchar(96) NOT NULL, `telephone` varchar(32) NOT NULL, `session_id` varchar(32) NOT NULL, `product_id` int(11) NOT NULL, `recurring_id` int(11) NOT NULL, `option` text NOT NULL, `quantity` int(5) NOT NULL, `email_notify` tinyint(4) NOT NULL, `email_notify_order` tinyint(4) NOT NULL,`order_id` int(11) NOT NULL,`store_id` int(11) NOT NULL,`ip` varchar(40) NOT NULL,`date_added` datetime NOT NULL, `date_modified` datetime NOT NULL, PRIMARY KEY (`abandonedcart_history_id`), KEY `cart_id` (`customer_id`,`session_id`,`product_id`,`recurring_id`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5");
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "abandoned_template` (`abandoned_template_id` int(11) NOT NULL AUTO_INCREMENT,`status` tinyint(4) NOT NULL,PRIMARY KEY (`abandoned_template_id`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ");
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "abandoned_template_description` (`abandoned_template_id` int(11) NOT NULL, `language_id` int(11) NOT NULL, `title` varchar(255) NOT NULL, `subject` varchar(255) NOT NULL, `message` longtext NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
	}
	
	public function addAbandonedTemplate($data) {
		$this->event->trigger('pre.admin.template.add', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "abandoned_template SET status = '" . (int)$data['status'] . "'");

		$abandoned_template_id = $this->db->getLastId();

		foreach ($data['abandoned_template_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "abandoned_template_description SET abandoned_template_id = '" . (int)$abandoned_template_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', subject = '" . $this->db->escape($value['subject']) . "', message = '" . $this->db->escape($value['message']) . "'");
		}
		
		$this->event->trigger('post.admin.template.add', $abandoned_template_id);
	}
	
	public function editAbandonedTemplate($abandoned_template_id, $data) {
		$this->event->trigger('pre.admin.template.edit', $data);

		
		$this->db->query("UPDATE " . DB_PREFIX . "abandoned_template SET status = '" . (int)$data['status'] . "' WHERE abandoned_template_id = '". (int)$abandoned_template_id ."'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "abandoned_template_description WHERE abandoned_template_id = '" . (int)$abandoned_template_id . "'");
		
		foreach ($data['abandoned_template_description'] as $language_id => $value) {
			
			$this->db->query("INSERT INTO " . DB_PREFIX . "abandoned_template_description SET abandoned_template_id = '" . (int)$abandoned_template_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', subject = '" . $this->db->escape($value['subject']) . "', message = '" . $this->db->escape($value['message']) . "'");
		}
		
		$this->event->trigger('post.admin.template.edit', $abandoned_template_id);

	}
	
	
	public function deleteAbandonedTemplate($abandoned_template_id) {
		$this->event->trigger('pre.admin.information.delete', $abandoned_template_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "abandoned_template WHERE abandoned_template_id = '" . (int)$abandoned_template_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "abandoned_template_description WHERE abandoned_template_id = '" . (int)$abandoned_template_id . "'");

		$this->event->trigger('post.admin.information.delete', $abandoned_template_id);
	}

	public function getAbandonedTemplate($abandoned_template_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "abandoned_template WHERE abandoned_template_id = '" . (int)$abandoned_template_id . "'");

		return $query->row;
	}
	
	public function getAbandonedTemplateData($abandoned_template_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "abandoned_template at LEFT JOIN " . DB_PREFIX . "abandoned_template_description atd on(at.abandoned_template_id=atd.abandoned_template_id) WHERE at.abandoned_template_id = '" . (int)$abandoned_template_id . "' AND atd.language_id = '". (int)$this->config->get('config_language_id') ."'");

		return $query->row;
	}
	
	public function getAbandonedTemplateDescriptions($abandoned_template_id) {
		$abandoned_template_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "abandoned_template_description WHERE abandoned_template_id = '" . (int)$abandoned_template_id . "'");

		foreach ($query->rows as $result) {
			$abandoned_template_description_data[$result['language_id']] = array(
				'title'            => $result['title'],
				'subject'      => $result['subject'],
				'message'      => $result['message']
			);
		}

		return $abandoned_template_description_data;
	}

	
	public function getAbandonedTemplates($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "abandoned_template at LEFT JOIN " . DB_PREFIX . "abandoned_template_description atd ON (at.abandoned_template_id = atd.abandoned_template_id) WHERE atd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$sort_data = array(
			'atd.title',
			'i.status'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY atd.title";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getTotalAbandonedTemplates() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "abandoned_template");

		return $query->row['total'];
	}
	
	public function getAbandonedCartsPeoples($data = array()) {
		$sql = "SELECT *, CONCAT(firstname, ' ', lastname) AS name FROM " . DB_PREFIX . "abandonedcart";	
		
		
		$sql .= " WHERE email !=''";
		
		if(!empty($data['filter_name'])) {
			$sql .= " AND CONCAT(firstname, ' ', lastname)  LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}
		
		if(!empty($data['filter_email'])) {
			$sql .= " AND email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}
		
		if (isset($data['filter_store_id']) && !is_null($data['filter_store_id'])) {
			$sql .= " AND store_id = '" . $this->db->escape($data['filter_store_id']) . "'";
		}
		
		if (isset($data['filter_email_notify']) && !is_null($data['filter_email_notify'])) {
			$sql .= " AND email_notify = '" . $this->db->escape($data['filter_email_notify']) . "'";
		}
		
		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		if (!empty($data['filter_account'])) {
			$sql .= " AND customer_id != '0'";
		}elseif (isset($data['filter_account']) && !is_null($data['filter_account'])) {
			$sql .= " AND customer_id = '0'";
		}
		
		$sql .= " GROUP BY email, store_id";
		
		$sql .= " ORDER BY date_added DESC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getAbandonedCartsHistoriesPeoples($data = array()) {
		$sql = "SELECT *, CONCAT(firstname, ' ', lastname) AS name FROM " . DB_PREFIX . "abandonedcart_history";	
		
		
		$sql .= " WHERE email !=''";
		
		if(!empty($data['filter_name'])) {
			$sql .= " AND CONCAT(firstname, ' ', lastname)  LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}
		
		if(!empty($data['filter_email'])) {
			$sql .= " AND email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}
		
		if (isset($data['filter_store_id']) && !is_null($data['filter_store_id'])) {
			$sql .= " AND store_id = '" . $this->db->escape($data['filter_store_id']) . "'";
		}
		
		if (isset($data['filter_email_notify']) && !is_null($data['filter_email_notify'])) {
			$sql .= " AND email_notify = '" . $this->db->escape($data['filter_email_notify']) . "'";
		}
		
		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		if (!empty($data['filter_account'])) {
			$sql .= " AND customer_id != '0'";
		}elseif (isset($data['filter_account']) && !is_null($data['filter_account'])) {
			$sql .= " AND customer_id = '0'";
		}
		
		$sql .= " GROUP BY email, store_id";
		
		$sql .= " ORDER BY date_added DESC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getTotalAbandonedCartsPeoples($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "abandonedcart";	
		
		
		$sql .= " WHERE email !=''";

		if(!empty($data['filter_name'])) {
			$sql .= " AND CONCAT(firstname, ' ', lastname)  LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}
		
		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		if(!empty($data['filter_email'])) {
			$sql .= " AND email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}		
		
		if (isset($data['filter_store_id']) && !is_null($data['filter_store_id'])) {
			$sql .= " AND store_id = '" . $this->db->escape($data['filter_store_id']) . "'";
		}
		
		if (isset($data['filter_email_notify']) && !is_null($data['filter_email_notify'])) {
			$sql .= " AND email_notify = '" . $this->db->escape($data['filter_email_notify']) . "'";
		}
		
		if (!empty($data['filter_account'])) {
			$sql .= " AND customer_id != '0'";
		}elseif (isset($data['filter_account']) && !is_null($data['filter_account'])) {
			$sql .= " AND customer_id = '0'";
		}
		
		$sql .= " GROUP BY email, store_id";
		
		$query = $this->db->query($sql);

		return $query->num_rows;
	}
	
	public function getTotalAbandonedCartsHistoriesPeoples($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "abandonedcart_history";	
		
		$sql .= " WHERE email !=''";

		if(!empty($data['filter_name'])) {
			$sql .= " AND CONCAT(firstname, ' ', lastname)  LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}
		
		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		if(!empty($data['filter_email'])) {
			$sql .= " AND email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}		
		
		if (isset($data['filter_store_id']) && !is_null($data['filter_store_id'])) {
			$sql .= " AND store_id = '" . $this->db->escape($data['filter_store_id']) . "'";
		}
		
		if (isset($data['filter_email_notify']) && !is_null($data['filter_email_notify'])) {
			$sql .= " AND email_notify = '" . $this->db->escape($data['filter_email_notify']) . "'";
		}
		
		if (!empty($data['filter_account'])) {
			$sql .= " AND customer_id != '0'";
		}elseif (isset($data['filter_account']) && !is_null($data['filter_account'])) {
			$sql .= " AND customer_id = '0'";
		}
		
		$sql .= " GROUP BY email, store_id";
		
		$query = $this->db->query($sql);

		return $query->num_rows;
	}
	
	public function getAbandonedCartsProducts($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "abandonedcart";

		$sql .= " WHERE email !='' AND LCASE(email) = '" . $this->db->escape(utf8_strtolower($data['filter_email'])) . "'";
		
		if (isset($data['filter_email_notify']) && !is_null($data['filter_email_notify'])) {
			$sql .= " AND email_notify = '" . $this->db->escape($data['filter_email_notify']) . "'";
		}
		
		if (isset($data['filter_store_id']) && !is_null($data['filter_store_id'])) {
			$sql .= " AND store_id = '" . $this->db->escape($data['filter_store_id']) . "'";
		}
		
		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		if (!empty($data['filter_account'])) {
			$sql .= " AND customer_id != '0'";
		}elseif (isset($data['filter_account']) && !is_null($data['filter_account'])) {
			$sql .= " AND customer_id = '0'";
		}
		
		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getAbandonedCartsHistoriesProducts($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "abandonedcart_history";

		$sql .= " WHERE email !='' AND LCASE(email) = '" . $this->db->escape(utf8_strtolower($data['filter_email'])) . "'";
		
		if (isset($data['filter_email_notify']) && !is_null($data['filter_email_notify'])) {
			$sql .= " AND email_notify = '" . $this->db->escape($data['filter_email_notify']) . "'";
		}
		
		if (isset($data['filter_store_id']) && !is_null($data['filter_store_id'])) {
			$sql .= " AND store_id = '" . $this->db->escape($data['filter_store_id']) . "'";
		}
		
		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		if (!empty($data['filter_account'])) {
			$sql .= " AND customer_id != '0'";
		}elseif (isset($data['filter_account']) && !is_null($data['filter_account'])) {
			$sql .= " AND customer_id = '0'";
		}
		
		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getTotalAbandonedCartProducts() {
		$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "abandonedcart";
		
		$sql .= " WHERE email !=''";
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
	
	public function getTotalCouponsHistories() {
		$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "coupon_history";
				
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
	
	public function getAbandonedCartByEmail($email) {
		$sql = "SELECT * FROM " . DB_PREFIX . "abandonedcart WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'";
		
		$query = $this->db->query($sql);
		
		return $query->row;
	}
	
	public function getAbandonedCartByEmailStore($email, $store_id) {
		$sql = "SELECT * FROM " . DB_PREFIX . "abandonedcart WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "' AND store_id = '". (int)$store_id ."'";
		
		$query = $this->db->query($sql);
		
		return $query->row;
	}
	
	public function getAbandonedCartByEmailStoreRows($email, $store_id) {
		$sql = "SELECT * FROM " . DB_PREFIX . "abandonedcart WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "' AND store_id = '". (int)$store_id ."'";
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getAbandonedCartHistoryByEmailStoreRows($email, $store_id) {
		$sql = "SELECT * FROM " . DB_PREFIX . "abandonedcart_history WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "' AND store_id = '". (int)$store_id ."'";
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function deleteAbandonedCartProdcut($cart_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "abandonedcart WHERE cart_id = '" . $this->db->escape($cart_id) . "'");
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "cart WHERE cart_id = '" . $this->db->escape($cart_id) . "'");
		
	}
	
	public function deleteAbandonedCartHistoryProdcut($cart_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "abandonedcart_history WHERE cart_id = '" . $this->db->escape($cart_id) . "'");
	}

	public function SendEmailTemplate($abandoned_template_id, $data) {
		$this->load->language('abandoned_carts/abandoned_carts');
		
		$this->load->model('catalog/product');
		
		$this->load->model('tool/image');
		
		$this->load->model('tool/upload');
		
		$datas = array();
		if($abandoned_template_id && !empty($data['selected_data'])) {
			$temlpate_info = $this->getAbandonedTemplateData($abandoned_template_id);
			if(!empty($temlpate_info['status'])) {
				foreach($data['selected_data'] as $result_value) {
					if(!empty($result_value['email']) && isset($result_value['store_id'])) {
						$customer_info = $this->getAbandonedCartByEmailStore($result_value['email'], $result_value['store_id']);
						if($customer_info) {
							$this->db->query("UPDATE ". DB_PREFIX ."abandonedcart SET email_notify = '1' WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($result_value['email'])) . "' AND store_id ='". (int)$result_value['store_id'] ."'");
							
							$datas['title'] = $this->language->get('heading_title');
							$datas['text_image'] = $this->language->get('text_image');
							$datas['text_product'] = $this->language->get('text_product');
							$datas['text_model'] = $this->language->get('text_model');
							$datas['text_quantity'] = $this->language->get('text_quantity');
							$datas['text_date_added'] = $this->language->get('text_date_added');
							
							$filter_data = array(
								'filter_name'	  							=> $data['filter_name'],
								'filter_email'	  						=> $result_value['email'],
								'filter_store_id'	  					=> $result_value['store_id'],
								'filter_email_notify'	  			=> $data['filter_email_notify'],
								'filter_account' 							=> $data['filter_account'],
								'filter_date_added' 					=> $data['filter_date_added'],
							);
							
							$products = $this->getAbandonedCartsProducts($filter_data);
							
							$datas['products'] = array();
							
							foreach($products as $product) {
								$product_info = $this->model_catalog_product->getProduct($product['product_id']);
								if($product_info) {
									if (is_file(DIR_IMAGE . $product_info['image'])) {
										$image = $this->model_tool_image->resize($product_info['image'], 40, 40);
									} else {
										$image = $this->model_tool_image->resize('no_image.png', 40, 40);
									}
									
									/*** Option ss ***/
									$option_data = array();
									foreach (json_decode($product['option']) as $product_option_id => $value) {
										$option_datas = $this->getCartProductOptions($product['product_id'], $product_option_id, $value);
										
										foreach ($option_datas as $option_value) {
											if ($option_value['type'] != 'file') {
												$value = $option_value['value'];
											} else {
												$upload_info = $this->model_tool_upload->getUploadByCode($option_value['value']);

												if ($upload_info) {
													$value = $upload_info['name'];
												} else {
													$value = '';
												}
											}

											$option_data[] = array(
												'name'  => $option_value['name'],
												'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
											);
										}
									}
									
									/*** Option ee ***/
									
									$datas['products'][] = array(
										'product_id'	=> $product_info['product_id'],
										'name'				=> $product_info['name'],
										'model'				=> $product_info['model'],
										'quantity'		=> $product['quantity'],
										'date_added'	=> date($this->language->get('date_format_short'), strtotime($product['date_added'])),
										'option'			=> $option_data,
										'image'				=> $image,
									);
								}								
							}
							
							// print_r($option_data); die();
							
							$cart_html = $this->load->view('abandoned_carts/cart_html.tpl', $datas);
							
							$find = array(
								'{store}',
								'{logo}',
								'{customer_id}',
								'{firstname}',
								'{lastname}',
								'{email}',
								'{telephone}',
								'{cart}',
								'{date_added}',							
							);

							$replace = array(
								'store' 		 => html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'),
								'logo' 			 => '<img src="'. HTTP_CATALOG .'image/'. $this->config->get('config_logo') .'" title="'. $this->config->get('config_name') .'" alt="'. $this->config->get('config_name') .'" />',
								'customer_id'=> $customer_info['customer_id'],
								'firstname'  => $customer_info['firstname'],
								'lastname' 	 => $customer_info['lastname'],
								'email'			 => $customer_info['email'],
								'telephone'  => $customer_info['telephone'],
								'cart' 			 => $cart_html,
								'date_added' => date($this->language->get('date_format_short'), strtotime($customer_info['date_added'])),
							);

							$subject = str_replace(array("\r\n", "\r", "\n"), '', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '', trim(str_replace($find, $replace, $temlpate_info['subject']))));
							
							$message = str_replace(array("\r\n", "\r", "\n"), '', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '', trim(str_replace($find, $replace, $temlpate_info['message']))));

							$mail = new Mail();
							$mail->protocol = $this->config->get('config_mail_protocol');
							$mail->parameter = $this->config->get('config_mail_parameter');
							$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
							$mail->smtp_username = $this->config->get('config_mail_smtp_username');
							$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
							$mail->smtp_port = $this->config->get('config_mail_smtp_port');
							$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
							$mail->setTo($result_value['email']);
							$mail->setFrom($this->config->get('config_email'));
							$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
							$mail->setSubject($subject);
							$mail->setHtml(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
							$mail->send();
						}
					}
				}
			}
		}
	}
	
	public function getCartProductOptions($product_id, $product_option_id, $value) {
		$option_data = array();
		
		$option_query = $this->db->query("SELECT po.product_option_id, po.option_id, od.name, o.type FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_option_id = '" . (int)$product_option_id . "' AND po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		if ($option_query->num_rows) {
			if ($option_query->row['type'] == 'select' || $option_query->row['type'] == 'radio' || $option_query->row['type'] == 'image') {
				$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$value . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

				if ($option_value_query->num_rows) {
					$option_data[] = array(
						'product_option_id'       => $product_option_id,
						'product_option_value_id' => $value,
						'option_id'               => $option_query->row['option_id'],
						'option_value_id'         => $option_value_query->row['option_value_id'],
						'name'                    => $option_query->row['name'],
						'value'                   => $option_value_query->row['name'],
						'type'                    => $option_query->row['type'],
					);
				}
			} elseif ($option_query->row['type'] == 'checkbox' && is_array($value)) {
				foreach ($value as $product_option_value_id) {
					$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

					if ($option_value_query->num_rows) {
						$option_data[] = array(
							'product_option_id'       => $product_option_id,
							'product_option_value_id' => $product_option_value_id,
							'option_id'               => $option_query->row['option_id'],
							'option_value_id'         => $option_value_query->row['option_value_id'],
							'name'                    => $option_query->row['name'],
							'value'                   => $option_value_query->row['name'],
							'type'                    => $option_query->row['type'],
						);
					}
				}
			} elseif ($option_query->row['type'] == 'text' || $option_query->row['type'] == 'textarea' || $option_query->row['type'] == 'file' || $option_query->row['type'] == 'date' || $option_query->row['type'] == 'datetime' || $option_query->row['type'] == 'time') {
				$option_data[] = array(
					'product_option_id'       => $product_option_id,
					'product_option_value_id' => '',
					'option_id'               => $option_query->row['option_id'],
					'option_value_id'         => '',
					'name'                    => $option_query->row['name'],
					'value'                   => $value,
					'type'                    => $option_query->row['type'],
				);
			}
		}
		
		return $option_data;
	}
	
}