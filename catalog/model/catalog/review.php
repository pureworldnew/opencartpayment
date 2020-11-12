<?php
class ModelCatalogReview extends Model {
	public function addReview($product_id, $data) {
		$this->event->trigger('pre.review.add', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "review SET author = '" . $this->db->escape($data['name']) . "', customer_id = '" . (int)$this->customer->getId() . "', product_id = '" . (int)$product_id . "', text = '" . $this->db->escape($data['text']) . "', rating = '" . (int)$data['rating'] . "', date_added = NOW()");

		$review_id = $this->db->getLastId();

		if ($this->config->get('config_review_mail')) {
			$this->load->language('mail/review');
			$this->load->model('catalog/product');
			
			$product_info = $this->model_catalog_product->getProduct($product_id);

			$subject = sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));

			$message  = $this->language->get('text_waiting') . "\n";
			$message .= sprintf($this->language->get('text_product'), html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8')) . "\n";
			$message .= sprintf($this->language->get('text_reviewer'), html_entity_decode($data['name'], ENT_QUOTES, 'UTF-8')) . "\n";
			$message .= sprintf($this->language->get('text_rating'), $data['rating']) . "\n";
			$message .= $this->language->get('text_review') . "\n";
			$message .= html_entity_decode($data['text'], ENT_QUOTES, 'UTF-8') . "\n\n";

			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($this->config->get('config_email'));
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject($subject);
			$mail->setText($message);
			$mail->send();

			// Send to additional alert emails
			$emails = explode(',', $this->config->get('config_mail_alert'));

			foreach ($emails as $email) {
				if ($email && preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $email)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
		}

		$this->event->trigger('post.review.add', $review_id);
	}

	public function getReviewsByProductId($product_id, $start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 20;
		}

		$query = $this->db->query("SELECT r.review_id, r.author, r.rating, r.text, p.product_id, pd.name, p.sku, p.price, p.image, r.date_added FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY r.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalReviewsByProductId($product_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row['total'];
	}

	public function getTotalGroupProductReviewsByProductId($product_id)
	{
		$review = 0;
		$sql   = "SELECT groupindicator_id FROM product_concat_temp_table WHERE product_id='".$product_id."'";
		$query =  $this->db->query($sql);
		$groupindicator_id = $query->row ? $query->row['groupindicator_id'] : 0;
		$sql   = "SELECT product_id FROM product_concat_temp_table WHERE groupindicator_id='".$groupindicator_id."'";
		$query =  $this->db->query($sql);
		$pid = "";
		if($query->num_rows)
		{
			foreach( $query->rows as $row )
			{
				$pid .= $row['product_id'] . ",";
			}

			$pid = rtrim($pid, ",");

			$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review WHERE product_id IN (" . $pid . ") AND status = '1'";
			$query =  $this->db->query($sql);
			return $query->row ? $query->row['total'] : 0;
		}

		return $review; 
	}

	public function getGroupProductReviewsByProductId($product_id, $start = 0, $limit = 20) {
		$reviews = array();
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 20;
		}

		$sql   = "SELECT groupindicator_id FROM product_concat_temp_table WHERE product_id='".$product_id."'";
		$query =  $this->db->query($sql);
		$groupindicator_id = $query->row ? $query->row['groupindicator_id'] : 0;
		$sql   = "SELECT product_id FROM product_concat_temp_table WHERE groupindicator_id='".$groupindicator_id."'";
		$query =  $this->db->query($sql);
		$pid = "";

		if($query->num_rows)
		{
			foreach( $query->rows as $row )
			{
				$pid .= $row['product_id'] . ",";
			}

			$pid = rtrim($pid, ",");

			$query = $this->db->query("SELECT r.review_id, r.author, r.rating, r.text, p.product_id, pd.name, p.sku, p.price, p.image, r.date_added FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id IN (" . $pid . ") AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY r.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

			return $query->rows;
			
		}

		return $reviews;
	}

	public function getProductOptions($product_id)
	{
		$options = array();
		$query = $this->db->query("SELECT * FROM product_concat_temp_table WHERE product_id = '" . (int)$product_id . "'");
		if( $query->row )
		{
			if( !empty($query->row['groupbyname']) )
			{
				$key = $query->row['groupbyname'];
				$value = $query->row['groupbyvalue'];
				$options[$key] = $value;
			}

			for($i=1; $i<=11; $i++)
			{
				if(!empty($query->row['optionname'.$i]))
				{
					$key = $query->row['optionname'.$i];
					$value = $query->row['optionvalue'.$i];
					$options[$key] = $value;
				}
			}
		}

		return $options; 
	}
}