<?php
class ModelCatalogQA extends Model {
    public function addQA($product_id, $data) { 
        $this->db->query("INSERT INTO " . DB_PREFIX . "qa SET q_author = '" . $this->db->escape($data['name']) . "', q_author_email = '" . $this->db->escape($data['email']) . "', customer_id = '" . (int)$this->customer->getId() . "', product_id = '" . (int)$product_id . "', question = '" . $this->db->escape(strip_tags($data['question'])) . "', status = '" . (int)$this->config->get('qa_new_question_state') . "', date_asked = NOW(), lang_code = '" . $this->session->data['language'] . "'");

        if ( true ) {
            $l_query = $this->db->query("SELECT language_id, code, directory FROM " . DB_PREFIX . "language WHERE code = '" . $this->config->get('config_admin_language') . "'");
            $language = new Language($l_query->row['directory']);
            $language->load($l_query->row['directory']);
            $language->load('mail/new_question');

            // Get product info
            $p_query = $this->db->query("SELECT p.model AS model, pd.name AS name FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . $l_query->row['language_id'] . "'");

            // Get customer info
            if ($this->customer->isLogged()) {
                $customer_name = $this->customer->getFirstName() . " " . $this->customer->getLastName();
            } else {
                $customer_name = "";
            }

            $subject = sprintf($language->get('text_subject'), $this->config->get('config_name'));

            // HTML Mail
            $email_data = array();

            $email_data['title'] = sprintf($language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));

            $email_data['text_new_question'] = $language->get('text_new_question');
            $email_data['text_question_detail'] = $language->get('text_question_detail');
            $email_data['text_question'] = $language->get('text_question');
            $email_data['text_date_asked'] = $language->get('text_date_asked');
            $email_data['text_related_product'] = $language->get('text_related_product');
            $email_data['text_question_author'] = $language->get('text_question_author');
            $email_data['text_q_author_email'] = $language->get('text_q_author_email');
            $email_data['text_customer_name'] = $language->get('text_customer_name');
            $email_data['text_ip'] = $language->get('text_ip');
            $email_data['text_email'] = $language->get('text_email');
            $email_data['text_powered_by'] = $language->get('text_powered_by');

            $email_data['store_name'] = $this->config->get('config_name');
            $email_data['store_url'] = $this->config->get('config_url');
            $email_data['logo'] = $this->config->get('config_url') . 'image/' . $this->config->get('config_logo');
            $email_data['question'] = str_replace(array("\r\n", "\r", "\n"), '<br />', strip_tags($data['question']));
            $email_data['date_asked'] = date($language->get('date_format_short'));
            $email_data['product_name'] = $p_query->row['name'];
            $email_data['product_model'] = $p_query->row['model'];
            $email_data['product_link'] = $this->url->link('product/product', 'product_id=' . $product_id);
            $email_data['question_author'] = strip_tags($data['name']);
            $email_data['question_author_email'] = strip_tags($data['email']);
            $email_data['customer_name'] = !empty($customer_name) ? " (" . $customer_name . ")" : "";
            $email_data['ip_address'] = $this->request->server['REMOTE_ADDR'];

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/new_question.tpl')) {
                $html = $this->load->view($this->config->get('config_template') . '/template/mail/new_question.tpl', $email_data);
            } else {
               $html = $this->load->view('default/template/mail/new_question.tpl', $email_data);
            }

            // Text Mail
            $text  = $language->get('text_new_question') . "\n\n";
            $text .= $language->get('text_question') . ' ' . $data['question'] . "\n\n";
            $text .= $language->get('text_date_asked') . ' ' . date($language->get('date_format_short')) . "\n";
            $text .= $language->get('text_related_product') . ' ' . $p_query->row['name'] . " (" . $p_query->row['model'] . ")\n";
            $text .= $language->get('question_author') . ' ' . $data['name'] . (($customer_name) ? " ($customer_name)" : "") . "\n";
            $text .= $language->get('text_ip') . ' ' . $this->request->server['REMOTE_ADDR'] . "\n";

            $mail = new Mail();
            $mail->protocol = $this->config->get('config_mail_protocol');
            $mail->parameter = $this->config->get('config_mail_parameter');
            $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
            $mail->smtp_username = $this->config->get('config_mail_smtp_username');
            $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
            $mail->smtp_port = $this->config->get('config_mail_smtp_port');
            $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
            $mail->setFrom($this->config->get('config_email'));
            $mail->setSender($this->config->get('config_name'));  
            $mail->setSubject($subject);
            $mail->setHtml($html);
            $mail->setText(html_entity_decode($text, ENT_QUOTES, 'UTF-8'));

            if ( $data['email_copy'] == 'true' )
            {
                $mail->setTo($data['email']);
                $mail->send();
            }

            $emails = explode(',', $this->config->get('config_email'));
            foreach ($emails as $email) {
                $mail->setTo($email);
                $mail->send();
            }
        }
    }

    public function getQAsByProductId($product_id, $start = 0, $limit = 20) {
        $sql = "SELECT qa.*, p.product_id, pd.name, p.sku, p.price, p.image FROM " . DB_PREFIX . "qa qa LEFT JOIN " . DB_PREFIX . "product p ON (qa.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND p.date_available <= NOW() AND pd.language_id = 1 AND p.status = '1' AND qa.status = '1'";

        if ((int)$this->config->get('qa_display_all_languages') != 1) {
            $sql .= " AND qa.lang_code = '" . $this->db->escape($this->config->get('config_language')) . "'";
        }

        $sql .= " ORDER BY qa.date_asked DESC";

        if ($limit > 0) {
            $sql .= " LIMIT " . (int)$start . "," . (int)$limit;
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }


    public function getGroupQAsByProductId($product_id, $start = 0, $limit = 20) {
        $questions = array();
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

            $sql = "SELECT qa.*, p.product_id, pd.name, p.sku, p.price, p.image FROM " . DB_PREFIX . "qa qa LEFT JOIN " . DB_PREFIX . "product p ON (qa.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id IN (" . $pid . ") AND p.date_available <= NOW() AND pd.language_id = 1 AND p.status = '1' AND qa.status = '1'";

            if ((int)$this->config->get('qa_display_all_languages') != 1) {
                $sql .= " AND qa.lang_code = '" . $this->db->escape($this->config->get('config_language')) . "'";
            }

            $sql .= " ORDER BY qa.date_asked DESC";

            if ($limit > 0) {
                $sql .= " LIMIT " . (int)$start . "," . (int)$limit;
            }

            $query = $this->db->query($sql);

            return $query->rows;
        }

        return $questions;
        
    }

    public function getTotalQAsByProductId($product_id) {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "qa qa LEFT JOIN " . DB_PREFIX . "product p ON (qa.product_id = p.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND p.date_available <= NOW() AND p.status = '1' AND qa.status = '1'";

        if ((int)$this->config->get('qa_display_all_languages') != 1) {
            $sql .= " AND qa.lang_code = '" . $this->db->escape($this->config->get('config_language')) . "'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }


    public function getTotalGroupQAsByProductId($product_id) {
        $questions = 0;
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
            
            $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "qa qa LEFT JOIN " . DB_PREFIX . "product p ON (qa.product_id = p.product_id) WHERE p.product_id IN (" . $pid . ") AND p.date_available <= NOW() AND p.status = '1' AND qa.status = '1'";

            if ((int)$this->config->get('qa_display_all_languages') != 1) {
                $sql .= " AND qa.lang_code = '" . $this->db->escape($this->config->get('config_language')) . "'";
            }

            $query = $this->db->query($sql);

            return $query->row['total'];	
		}

		return $questions;  
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
?>