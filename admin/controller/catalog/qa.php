<?php

require_once(DIR_CATALOG . 'controller/common/seo_url.php');

/*
Validate an email address.
Provide email address (raw input)
Returns true if the email address has the email
address format and the domain exists.
*/
function valid_email($email) {
   $isValid = true;
   $atIndex = strrpos($email, "@");
   if (is_bool($atIndex) && !$atIndex) {
      $isValid = false;
   } else {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64) {
         // local part length exceeded
         $isValid = false;
      } else if ($domainLen < 1 || $domainLen > 255) {
         // domain part length exceeded
         $isValid = false;
      } else if ($local[0] == '.' || $local[$localLen-1] == '.') {
         // local part starts or ends with '.'
         $isValid = false;
      } else if (preg_match('/\\.\\./', $local)) {
         // local part has two consecutive dots
         $isValid = false;
      } else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
         // character not valid in domain part
         $isValid = false;
      } else if (preg_match('/\\.\\./', $domain)) {
         // domain part has two consecutive dots
         $isValid = false;
      } else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                 str_replace("\\\\","",$local)))
      {
         // character not valid in local part unless
         // local part is quoted
         if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local))) {
            $isValid = false;
         }
      }
      if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))) {
         // domain not found in DNS
         $isValid = false;
      }
   }
   return $isValid;
}

class ControllerCatalogQA extends Controller {
    private $error = array();

    public function index() {
        $this->language->load('catalog/qa');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/qa');

        $this->getList();
    }

    public function insert() {
        $this->language->load('catalog/qa');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/qa');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_catalog_qa->addQA($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            $this->response->redirect($this->url->link('catalog/qa', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function update() {
        $this->language->load('catalog/qa');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/qa');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_catalog_qa->editQA($this->request->get['qa_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            if (isset($this->request->post['notify']) && (int)$this->request->post['notify'] && (int)$this->request->post['status'] && $this->request->post['question'] != "" && $this->request->post['answer'] != "" && $this->request->post['q_author_email'] != "") {
                $l_query = $this->db->query("SELECT language_id, code, directory FROM " . DB_PREFIX . "language WHERE code = '" . $this->request->post['lang_code'] . "'");
                $language = new Language($l_query->row['directory']);
                $language->load($l_query->row['directory']);
                $language->load('mail/question_reply');
                
                // Get product info
                $p_query = $this->db->query("SELECT p.model AS model, pd.name AS name FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$this->request->post['product_id'] . "' AND pd.language_id = '" . $l_query->row['language_id'] . "'");

                $subject = sprintf($language->get('text_subject'), $this->config->get('config_name'));
                
                $url = new Url(HTTP_CATALOG, $this->config->get('config_use_ssl') ? HTTP_CATALOG : HTTP_CATALOG);
                $product_link = $url->link('product/product', 'product_id=' . $this->request->post['product_id']);
                if ($this->config->get('config_seo_url')) { 
                    $seo_url = new ControllerCommonSeoUrl($this->registry);
                    //$product_link = $seo_url->rewrite($product_link);
                }
                // HTML Mail
                $email_data = array();

                $email_data['title'] = sprintf($language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));

                $email_data['text_answered'] = sprintf($language->get('text_answered'), $product_link, $p_query->row['name']);
                $email_data['text_view'] = sprintf($language->get('text_view'), $product_link, $product_link);
                $email_data['text_question_detail'] = $language->get('text_question_detail');
                $email_data['text_greeting'] = $language->get('text_greeting');
                $email_data['text_thanks'] = $language->get('text_thanks');
                $email_data['text_answer'] = $language->get('text_answer');
                $email_data['text_asked'] = sprintf($language->get('text_asked'), date($language->get('date_format_short'), strtotime($this->request->post['date_asked'])));
                $email_data['text_powered_by'] = $language->get('text_powered_by');
                $email_data['text_closing'] = $language->get('text_closing');
                $email_data['text_sender'] = sprintf($language->get('text_sender'), $this->config->get('config_name'));

                $email_data['store_name'] = $this->config->get('config_name');
                $email_data['store_url'] = HTTP_CATALOG;
                $email_data['logo'] = HTTP_CATALOG . 'image/' . $this->config->get('config_logo');
                $email_data['question'] = str_replace(array("\r\n", "\r", "\n"), '<br />', strip_tags($this->request->post['question']));
                $email_data['answer'] = str_replace(array("\r\n", "\r", "\n"), '<br />', html_entity_decode($this->request->post['answer']));
                
                $html = $this->load->view('mail/question_reply.tpl', $email_data);

                // Text Mail
                $text  = sprintf(strip_tags($language->get('text_answered')), $p_query->row['name']) . "\n";
                $text .= sprintf(strip_tags($language->get('text_view')), $product_link) . "\n\n";
                $text .= sprintf($language->get('text_asked'), date($language->get('date_format_short'), strtotime($this->request->post['date_asked']))) . "\n";
                $text .= $this->request->post['question'] . "\n\n";
                $text .= $language->get('text_answer') . "\n" . strip_tags($this->request->post['answer']) . "\n\n";
                $text .= $language->get('text_closing') . "\n";
                $text .= sprintf($language->get('text_sender'), $this->config->get('config_name')) . "\n";

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
                //$mail->addAttachment(DIR_IMAGE . $this->config->get('config_logo'));

                $mail->setTo($this->request->post['q_author_email']);
                $mail->send();
            }

            $url = '';

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            $this->response->redirect($this->url->link('catalog/qa', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function delete() {
        $this->language->load('catalog/qa');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/qa');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $qa_id) {
                $this->model_catalog_qa->deleteQA($qa_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            $this->response->redirect($this->url->link('catalog/qa', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    private function getList() {
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'qa.date_asked';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }

        $url = '';

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('catalog/qa', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['insert'] = $this->url->link('catalog/qa/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['delete'] = $this->url->link('catalog/qa/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['qas'] = array();

        $filter_data = array(
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $qa_total = $this->model_catalog_qa->getTotalQuestions();

        $results = $this->model_catalog_qa->getQAs($filter_data);

        foreach ($results as $result) {
            $action = array();

            $action[] = array(
                'text' => $this->language->get('text_edit'),
                'href' => $this->url->link('catalog/qa/update', 'token=' . $this->session->data['token'] . '&qa_id=' . $result['qa_id'] . $url, 'SSL')
            );

            if ($result['customer_id'] != '0') {
                $this->load->model('sale/customer');
                $customer = $this->model_sale_customer->getCustomer($result['customer_id']);
                $c_link = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&filter_name=' . $customer['firstname'] . ' ' . $customer['lastname'], 'SSL');
            } else {
                $c_link = '';
            }

            $data['qas'][] = array(
                'qa_id'             => $result['qa_id'],
                'name'              => $result['name'],
                'q_author'          => $result['q_author'],
                'customer_link'     => $c_link,
                'q_author_email'    => $result['q_author_email'],
                'a_author'          => $result['a_author'],
                'language'          => $result['language'],
                'status'            => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'date_asked'        => date($this->language->get('date_format_short'), strtotime($result['date_asked'])),
                'answered'          => ($result['answer'] != '') ? $this->language->get('text_yes') : $this->language->get('text_no'),
                'date_answered'     => ($result['date_answered'] != "0000-00-00 00:00:00") ? date($this->language->get('date_format_short'), strtotime($result['date_answered'])) : "",
                'selected'          => isset($this->request->post['selected']) && in_array($result['qa_id'], $this->request->post['selected']),
                'action'            => $action
            );
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_no_results'] = $this->language->get('text_no_results');

        $data['column_product'] = $this->language->get('column_product');
        $data['column_question_author'] = $this->language->get('column_question_author');
        $data['column_q_author_email'] = $this->language->get('column_q_author_email');
        $data['column_answer_author'] = $this->language->get('column_answer_author');
        $data['column_date_asked'] = $this->language->get('column_date_asked');
        $data['column_answered'] = $this->language->get('column_answered');
        $data['column_date_answered'] = $this->language->get('column_date_answered');
        $data['column_language'] = $this->language->get('column_language');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_action'] = $this->language->get('column_action');

        $data['button_insert'] = $this->language->get('button_insert');
        $data['button_delete'] = $this->language->get('button_delete');

         if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $url = '';

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_product'] = $this->url->link('catalog/qa', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL');
        $data['sort_question_author'] = $this->url->link('catalog/qa', 'token=' . $this->session->data['token'] . '&sort=qa.q_author' . $url, 'SSL');
        $data['sort_q_author_email'] = $this->url->link('catalog/qa', 'token=' . $this->session->data['token'] . '&sort=qa.q_author_email' . $url, 'SSL');
        $data['sort_answer_author'] = $this->url->link('catalog/qa', 'token=' . $this->session->data['token'] . '&sort=qa.a_author' . $url, 'SSL');
        $data['sort_language'] = $this->url->link('catalog/qa', 'token=' . $this->session->data['token'] . '&sort=l.name' . $url, 'SSL');
        $data['sort_status'] = $this->url->link('catalog/qa', 'token=' . $this->session->data['token'] . '&sort=qa.status' . $url, 'SSL');
        $data['sort_date_asked'] = $this->url->link('catalog/qa', 'token=' . $this->session->data['token'] . '&sort=qa.date_asked' . $url, 'SSL');
        $data['sort_answered'] = $this->url->link('catalog/qa', 'token=' . $this->session->data['token'] . '&sort=qa.answered' . $url, 'SSL');
        $data['sort_date_answered'] = $this->url->link('catalog/qa', 'token=' . $this->session->data['token'] . '&sort=qa.date_answered' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $qa_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('catalog/qa', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['sort'] = $sort;
        $data['order'] = $order;


			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
	
			$this->response->setOutput($this->load->view('catalog/qa_list.tpl', $data));
    }

    private function getForm() {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_none'] = $this->language->get('text_none');
        $data['text_select'] = $this->language->get('text_select');
        $data['text_customer'] = $this->language->get('text_customer');

        $data['entry_product'] = $this->language->get('entry_product');
        $data['entry_question_author'] = $this->language->get('entry_question_author');
        $data['entry_q_author_email'] = $this->language->get('entry_q_author_email');
        $data['entry_answer_author'] = $this->language->get('entry_answer_author');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_question'] = $this->language->get('entry_question');
        $data['entry_language'] = $this->language->get('entry_language');
        $data['entry_answer'] = $this->language->get('entry_answer');
        $data['entry_notify'] = $this->language->get('entry_notify');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['product'])) {
            $data['error_product'] = $this->error['product'];
        } else {
            $data['error_product'] = '';
        }

        if (isset($this->error['question_author'])) {
            $data['error_question_author'] = $this->error['question_author'];
        } else {
            $data['error_question_author'] = '';
        }

        if (isset($this->error['q_author_email'])) {
            $data['error_q_author_email'] = $this->error['q_author_email'];
        } else {
            $data['error_q_author_email'] = '';
        }

        if (isset($this->error['answer_author'])) {
            $data['error_answer_author'] = $this->error['answer_author'];
        } else {
            $data['error_answer_author'] = '';
        }

        if (isset($this->error['question'])) {
            $data['error_question'] = $this->error['question'];
        } else {
            $data['error_question'] = '';
        }

        if (isset($this->error['answer'])) {
            $data['error_answer'] = $this->error['answer'];
        } else {
            $data['error_answer'] = '';
        }

        $url = '';

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('catalog/qa', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        if (!isset($this->request->get['qa_id'])) {
            $data['action'] = $this->url->link('catalog/qa/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
        } else {
            $data['action'] = $this->url->link('catalog/qa/update', 'token=' . $this->session->data['token'] . '&qa_id=' . $this->request->get['qa_id'] . $url, 'SSL');
        }

        $data['cancel'] = $this->url->link('catalog/qa', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['token'] = $this->session->data['token'];

        if (isset($this->request->get['qa_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $qa_info = $this->model_catalog_qa->getQA($this->request->get['qa_id']);
        }

        $this->load->model('catalog/category');

        $data['categories'] = $this->model_catalog_category->getCategories(0);

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        $this->load->model('catalog/product');

        if (isset($this->request->post['product_id'])) {
            $data['product_id'] = $this->request->post['product_id'];

            $product_info = $this->model_catalog_product->getProduct($this->request->post['product_id']);

            if ($product_info) {
                $data['product'] = $product_info['name'];
            } else {
                $data['product'] = '';
            }
        } elseif (isset($qa_info)) {
            $data['product_id'] = $qa_info['product_id'];

            $product_info = $this->model_catalog_product->getProduct($qa_info['product_id']);

            if ($product_info) {
                $data['product'] = $product_info['name'];
            } else {
                $data['product'] = '';
            }
        } else {
            $data['product_id'] = '';
        }

        if (isset($qa_info) && $qa_info['customer_id'] != '0') {
            $this->load->model('sale/customer');
            $customer = $this->model_sale_customer->getCustomer($qa_info['customer_id']);
            $data['customer'] = $customer['firstname'] . ' ' . $customer['lastname'];
            $data['customer_link'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&filter_name=' . $data['customer'], 'SSL');
        } else {
            $data['customer'] = '';
        }

        if (isset($this->request->post['q_author'])) {
            $data['q_author'] = $this->request->post['q_author'];
        } elseif (isset($qa_info)) {
            $data['q_author'] = $qa_info['q_author'];
        } else {
            $data['q_author'] = '';
        }

        if (isset($this->request->post['q_author_email'])) {
            $data['q_author_email'] = $this->request->post['q_author_email'];
        } elseif (isset($qa_info)) {
            $data['q_author_email'] = $qa_info['q_author_email'];
        } else {
            $data['q_author_email'] = '';
        }

        if (isset($this->request->post['a_author'])) {
            $data['a_author'] = $this->request->post['a_author'];
        } elseif (isset($qa_info)) {
            $data['a_author'] = $qa_info['a_author'];
        } else {
            $data['a_author'] = '';
        }

        if (isset($this->request->post['question'])) {
            $data['question'] = $this->request->post['question'];
        } elseif (isset($qa_info)) {
            $data['question'] = $qa_info['question'];
        } else {
            $data['question'] = '';
        }

        if (isset($this->request->post['answer'])) {
            $data['answer'] = $this->request->post['answer'];
        } elseif (isset($qa_info)) {
            $data['answer'] = $qa_info['answer'];
        } else {
            $data['answer'] = '';
        }

        if (isset($this->request->post['notify'])) {
            $data['notify'] = $this->request->post['notify'];
        } else {
            $data['notify'] = $this->config->get("qa_question_reply_notification");
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (isset($qa_info)) {
            $data['status'] = $qa_info['status'];
        } else {
            $data['status'] = '';
        }

        if (isset($this->request->post['language'])) {
            $data['lang_code'] = $this->request->post['language'];
        } elseif (isset($qa_info)) {
            $data['lang_code'] = $qa_info['lang_code'];
        } else {
            $data['lang_code'] = $this->config->get('config_language');
        }

        if (isset($this->request->post['date_asked'])) {
            $data['date_asked'] = $this->request->post['date_asked'];
        } elseif (isset($qa_info)) {
            $data['date_asked'] = $qa_info['date_asked'];
        } else {
            $data['date_asked'] = '';
        }
		
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
	
			$this->response->setOutput($this->load->view('catalog/qa_form.tpl', $data));
			
    }

    public function category() {
        $this->load->model('catalog/product');

        if (isset($this->request->get['category_id'])) {
            $category_id = $this->request->get['category_id'];
        } else {
            $category_id = 0;
        }

        $product_data = array();

        $results = $this->model_catalog_product->getProductsByCategoryId($category_id);

        if ($results) {
            foreach ($results as $result) {
                $product_data[] = array(
                    'product_id' => $result['product_id'],
                    'name'       => $result['name']
                );
            }
        } else {
            $product_data[] = array(
                'product_id' => 0,
                'name'       => $this->language->get('text_none')
            );
        }

        $this->response->setOutput(json_encode($product_data));
    }

    private function validateForm() {
        if (!$this->user->hasPermission('modify', 'catalog/qa')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['product_id']) {
            $this->error['product'] = $this->language->get('error_product');
        }

        if ((strlen(utf8_decode($this->request->post['q_author'])) < 3) || (strlen(utf8_decode($this->request->post['q_author'])) > 64)) {
            $this->error['question_author'] = $this->language->get('error_question_author');
        }

        if (strlen(utf8_decode($this->request->post['q_author_email'])) > 0 && !valid_email(utf8_decode($this->request->post['q_author_email']))) {
            $this->error['q_author_email'] = $this->language->get('error_email');
        }

        if (strlen(utf8_decode($this->request->post['a_author'])) > 64) {
            $this->error['answer_author'] = $this->language->get('error_answer_author');
        }

        if (strlen(utf8_decode($this->request->post['question'])) < 1) {
            $this->error['question'] = $this->language->get('error_question');
        }

        if (!$this->error) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function validateDelete() {
        if (!$this->user->hasPermission('modify', 'catalog/qa')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
}
?>