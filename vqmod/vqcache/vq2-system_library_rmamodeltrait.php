<?php
/**
 * Webkul Software.
 * @category  Webkul
 * @author    Webkul
 * @copyright Copyright (c) 2010-2016 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

trait RmaModelTrait {

  public function getrmaorders($data=array(), $customer_id = 0, $is_admin = false){
    $customer_id = (int)$customer_id;
		$query = '';

		if(!$customer_id && isset($data['email']))
			$query .= "AND email = '".$this->db->escape($data['email'])."'";

		if($is_admin)
		{
			if((int)$this->config->get('wk_rma_system_time_admin'))
			$query .= " AND o.date_added >= DATE_SUB(CURDATE(), INTERVAL '".(int)$this->config->get('wk_rma_system_time_admin')."' DAY) ";
		} else {
			if((int)$this->config->get('wk_rma_system_time'))
			$query .= " AND o.date_added >= DATE_SUB(CURDATE(), INTERVAL '".(int)$this->config->get('wk_rma_system_time')."' DAY) ";
		}	
		if ($this->config->get('wk_rma_system_orders')) {
			$sql = "SELECT o.order_id,o.total,SUM(op.quantity) as quantity FROM `" . DB_PREFIX . "order` o LEFT JOIN `".DB_PREFIX."order_product` op ON (o.order_id = op.order_id) WHERE o.customer_id = '".$customer_id."' $query AND o.order_status_id IN (".implode(',',$this->config->get('wk_rma_system_orders')).")";
		} else {
			return array();
		}

    $sql .= " GROUP BY o.order_id ORDER BY o.order_id DESC";

		$results = $this->db->query($sql)->rows;

    $order = array(
      '0' => 0
    );

    foreach ($results as $result) {
      $order[] = $result['order_id'];
    }

    $rma_array = $this->db->query("SELECT SUM(wrp.quantity) as quantity,order_id FROM `" . DB_PREFIX . "wk_rma_order` wro LEFT JOIN `" . DB_PREFIX . "wk_rma_product` wrp ON (wrp.rma_id = wro.id) WHERE admin_return <> 1 AND wro.order_id IN (" . implode(',',$order) . ") GROUP BY wro.order_id")->rows;

    foreach ($rma_array as $value) {
      foreach ($results as $key => $result) {
        if (!$result['quantity']) {
          unset($results[$key]);
          continue;
        }
        if ($result['order_id'] == $value['order_id']) {
          $results[$key]['quantity'] = $result['quantity'] - $value['quantity'];
          if ($results[$key]['quantity'] < 1) {
              unset($results[$key]);
          }
        }
      }
    }
		return $results;
	}

  public function defaultRmaStatus(){
    $result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "wk_rma_status` WHERE `default` = 'admin'")->row;
    if ($result) {
      return $result['status_id'];
    }
    return false;
  }

  public function solveRmaStatus(){
    $result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "wk_rma_status` WHERE `default` = 'solve'")->row;
    if ($result) {
      return $result['status_id'];
    }
    return false;
  }

  public function cancelRmaStatus(){
    $result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "wk_rma_status` WHERE `default` = 'cancel'")->row;
    if ($result) {
      return $result['status_id'];
    }
    return false;
  }

  public function insertOrderRma($data,$img_folder,$customer_id = 0,$return_pos = 0,$admin_rma = false){

		$getDefaultStatus = $this->getDefaultStatus();

		if ( !isset($data['autono']) )
		{
			$data['autono'] = "";
		}

		
		
		$this->db->query("INSERT INTO `" . DB_PREFIX . "wk_rma_order` SET `order_id` = '".(int)$data['order']."', `images` = '".$this->db->escape($img_folder)."',`add_info` = '".$this->db->escape(nl2br($data['info']))."', `admin_status` = '".($getDefaultStatus ? $getDefaultStatus['status_id'] : 0 )."',`rma_auth_no` = '".$this->db->escape($data['autono'])."',`return_pos` = '".$return_pos."', `date`=NOW()");

		$rma_id = $this->db->getLastId();

		if(isset($this->session->data['rma_login']))
			$email = $this->session->data['rma_login'];
    else {
      $email = $this->db->query("SELECT email FROM `" . DB_PREFIX . "customer` WHERE `customer_id` = '" . (int)$customer_id . "'")->row['email'];
    }

		$this->db->query("INSERT INTO `" . DB_PREFIX . "wk_rma_customer` SET `rma_id` = '".$rma_id."', `customer_id` = '".(int)$customer_id."', `email` = '".$this->db->escape($email)."'");

		
           $prd_rtn = 0.00;
           $prd_hash= array();
           
           //get hash to overcome this situation
           
           
           
		  foreach($data['selected'] as $key => $product) {
          $hash = $rma_id."-".(int)$product."-".(int)$data['quantity'][$key]."-".(int)$data['reason'][$key]."-".(int)$data['product'][$key];
          $hash = md5($hash);
          if (in_array($hash,$prd_hash)){
            continue;
          }else{
            $this->db->query("INSERT INTO `" . DB_PREFIX . "wk_rma_product` SET `rma_id` = '".(int)$rma_id."',`product_id` = '".(int)$product."',`quantity` = '".(int)$data['quantity'][$key]."',`reason` = '".(int)$data['reason'][$key]."', `order_product_id` = '".(int)$data['product'][$key]."'");
         // Get Return Product Unit Price
            $unt_prc = $this->db->query("SELECT price,tax FROM `" . DB_PREFIX . "order_product` WHERE `order_product_id` = '" . (int)$data['product'][$key] . "'")->row;
            $prd_rtn += ($unt_prc['price'] + $unt_prc['tax']) * $data['quantity'][$key];
            array_push($prd_hash,$hash);
          }
		  }
          // Calculate Return Total Amount and INSERT in oc_mv_return_total
          $this->db->query("INSERT INTO `" . DB_PREFIX . "mv_return_total` SET `order_id` = '".(int)$data['order']."', `rma_id` = '".(int)$rma_id."', `total` = '".$prd_rtn."'");
           




    $data['rma_id'] = $rma_id;
    $data['customer_id'] = $customer_id;
		$data['customer_message'] = $data['info'] ;
		if(!$admin_rma)
		{
    	$this->mail($data,'generate_admin');
			$this->mail($data,'generate_customer');
		}

	}



  public function getDefaultStatus(){
    $result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "wk_rma_status` WHERE `default`='admin'");
    return $result->row;
  }

  public function getOrderStatus($order = 0) {
    $result = $this->db->query("SELECT order_status_id FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order . "'")->row;

    if ($result) {
        return $result['order_status_id'];
    }
    return false;
  }

  public function orderprodetails($order, $customer_id = 0){
    if ($this->config->get('wk_rma_system_orders')) {
      $results = $this->db->query("SELECT op.name,op.product_id,op.model,op.quantity,op.quantity_supplied,op.order_product_id,(SELECT SUM(quantity) FROM `" . DB_PREFIX . "wk_rma_product` wrp LEFT JOIN `" . DB_PREFIX . "wk_rma_order` wro ON (wrp.rma_id = wro.id) WHERE order_product_id = op.order_product_id AND wro.cancel_rma <>1 AND admin_return <> 1 GROUP BY order_product_id) as rma_quantity_sum FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (o.order_id = op.order_id) WHERE o.order_id='".$this->db->escape($order)."' AND o.customer_id='" . (int)$customer_id . "' AND o.order_status_id IN (" . implode(',',$this->config->get('wk_rma_system_orders')) . ")")->rows;

      foreach ($results as $key => $result) {
        $results[$key]['quantity'] = $result['quantity'] - $result['rma_quantity_sum'];
        if ($results[$key]['quantity'] < 1) {
          unset($results[$key]);
        }
      }
      return $results;
		}
		return array();
  }

  /**
   * [getMailData description]
   * @param  [int] $id [mail id]
   * @return [array] [mail data]
   */
  public function getMailData($id = 0){
    return $this->db->query("SELECT * FROM `" . DB_PREFIX . "webkul_mail` WHERE id='".(int)$id."'")->row;
  }

  public function getCustomer($rma = 0){
		$customer = $this->db->query("SELECT o.* FROM `" . DB_PREFIX . "order` o LEFT JOIN ".DB_PREFIX."wk_rma_order wro ON (wro.order_id = o.order_id) WHERE wro.id = '".(int)$rma."'")->row;
		return $customer;
  }

  public function mail($data, $mail_type = '') {

    $value_index = array();

		$this->load->language('catalog/rma_mail');

		$mail_message = '';

		switch($mail_type){

      case 'message_to_customer' :

				$mail_subject = $this->language->get('message_to_customer_subject');
				$mail_message = nl2br(sprintf($this->language->get('message_to_customer_message'),ucfirst($data['status'])));

				$customer_info = $this->getCustomer($data['rma_id']);
				$mail_to = $customer_info['email'];
				$mail_from = $this->config->get('config_email');

				$value_index = array(
									'customer_name' => $customer_info['firstname'],
									'customer_message' => nl2br($data['message']),
									'rma_id' => $data['rma_id'],
									);
				break;

			//admin changed status - message to customer
			case 'label_to_customer' :

				$mail_subject = $this->language->get('label_to_customer_subject');
				$mail_message = nl2br($this->language->get('label_to_customer_message'));

				$customer_info = $this->getCustomer($data['rma_id']);
				$mail_to = $customer_info['email'];
				$mail_from = $this->config->get('config_email');

				$value_index = array(
									'customer_name' => $customer_info['firstname'],
									'rma_id' => $data['rma_id'],
									'link' => $data['link']
									);

				if(file_exists(DIR_IMAGE.$data['label'])){
					$mail_attachment = DIR_IMAGE.$data['label'];
				}

				break;

			//customer added RMA
			case 'generate_customer' ://to customer

				$mail_subject = $this->language->get('generate_customer_subject');
				$mail_message = nl2br($this->language->get('generate_customer_message'));

				$customer_info = $this->getCustomer($data['rma_id']);
				$mail_to = $customer_info['email'];
				$mail_from = $this->config->get('config_email');

				$value_index = array(
									'customer_name' => $customer_info['firstname'],
									'order_id' => $data['order'],
									'rma_id' => $data['rma_id'],
									'link' => defined('HTTP_CATALOG') ? HTTP_CATALOG . 'index.php?route=account/rma/viewrma&vid=' . $data['rma_id'] : $this->urlChange('account/rma/viewrma&vid='.$data['rma_id'],'','SSL')
									);
				break;

			case 'generate_admin' ://to admin

				$mail_subject = $this->language->get('generate_admin_subject');
				$mail_message = nl2br($this->language->get('generate_admin_message'));

				$customer_info = $this->getCustomer($data['rma_id']);
				$mail_to = $this->config->get('config_email');
				$mail_from = $customer_info['email'];

				$value_index = array(
									'customer_name' => $customer_info['firstname'].' '.$customer_info['lastname'],
									'rma_id' => $data['rma_id'],
									'order_id' => $data['order'],
									);
				break;

			//customer send message to admin
			case 'message_to_admin' :

				$mail_subject = $this->language->get('message_to_admin_subject');
				$mail_message = nl2br($this->language->get('message_to_admin_message'));

				$customer_info = $this->getCustomer($data['rma_id']);
				$mail_to = $this->config->get('config_email');
				$mail_from = $customer_info['email'];

				$value_index = array(
									'customer_name' => $customer_info['firstname'].' '.$customer_info['lastname'],
									'customer_message' => nl2br($data['message']),
									'rma_id' => $data['rma_id'],
									);
				break;

			//customer changed status - message to admin
			case 'status_to_admin' :

				$mail_subject = $this->language->get('status_to_admin_subject');
				$mail_message = nl2br(sprintf($this->language->get('status_to_admin_message'),ucfirst($data['status'])));

				$customer_info = $this->getCustomer($data['rma_id']);
				$mail_to = $this->config->get('config_email');
				$mail_from = $customer_info['email'];

				$value_index = array(
									'customer_name' => $customer_info['firstname'].' '.$customer_info['lastname'],
									'rma_id' => $data['rma_id'],
									);
				break;

			//customer changed status - message to customer
			case 'status_to_customer' :

				$mail_subject = $this->language->get('status_to_customer_subject');
				$mail_message = nl2br(sprintf($this->language->get('status_to_customer_message'),ucfirst($data['status'])));

				$customer_info = $this->getCustomer($data['rma_id']);
				$mail_to = $customer_info['email'];
				$mail_from = $this->config->get('config_email');

				$value_index = array(
									'customer_name' => $customer_info['firstname'],
									'rma_id' => $data['rma_id'],
									);
				break;

			default :
				return;
		}


		if($mail_message){

			$data['store_name'] = $this->config->get('config_name');
			$data['store_url'] = HTTP_CATALOG;
			$data['logo'] = HTTP_CATALOG.'image/' . $this->config->get('config_logo');

			$find = array(
				'{order_id}',
				'{rma_id}',
				'{product_name}',
				'{customer_name}',
				'{customer_message}',
				'{link}',
				'{config_logo}',
				'{config_icon}',
				'{config_currency}',
				'{config_image}',
				'{config_name}',
				'{config_owner}',
				'{config_address}',
				'{config_geocode}',
				'{config_email}',
				'{config_telephone}',
				);

			$replace = array(
				'order_id' => '',
				'rma_id' => '',
				'product_name' => '',
				'customer_name' => '',
				'customer_message' => '',
				'link' => '',
				'config_logo' => '<a href="'.HTTP_CATALOG.'" title="'.$data['store_name'].'"><img src="'.HTTP_CATALOG.'image/' . $this->config->get('config_logo').'" alt="'.$data['store_name'].'" style="max-width:200px;"/></a>',
				'config_icon' => '<img src="'.HTTP_CATALOG.'image/' . $this->config->get('config_icon').'" style="max-width:200px;">',
				'config_currency' => $this->config->get('config_currency'),
				'config_image' => '<img src="'.HTTP_CATALOG.'image/' . $this->config->get('config_image').'" style="max-width:200px;">',
				'config_name' => $this->config->get('config_name'),
				'config_owner' => $this->config->get('config_owner'),
				'config_address' => $this->config->get('config_address'),
				'config_geocode' => $this->config->get('config_geocode'),
				'config_email' => $this->config->get('config_email'),
				'config_telephone' => $this->config->get('config_telephone'),
			);

			$replace = array_merge($replace,$value_index);

			$mail_message = trim(str_replace($find, $replace, $mail_message));

			$data['subject'] = $mail_subject;
			$data['message'] = $mail_message;

      if (version_compare(VERSION, '2.2', '>=')) {
  			$html = $this->load->view('catalog/rma_mail', $data);
  		} else {
        if (isset($this->request->get['token']) && $this->session->data['token'] == $this->request->get['token']) {
          $html = $this->load->view('catalog/rma_mail.tpl', $data);
        } else {
          if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/catalog/rma_mail.tpl')) {
            $html = $this->load->view($this->config->get('config_template') . '/template/catalog/rma_mail.tpl', $data);
          } else {
            $html = $this->load->view('default/template/catalog/rma_mail.tpl', $data);
          }
        }
  		}

			if (preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $mail_to) AND preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $mail_from) ) {
				if(version_compare(VERSION, '2.0.1.1', '<=')) {
					$mail = new Mail($this->config->get('config_mail'));
					$mail->setTo($mail_to);
					$mail->setFrom($mail_from);
					$mail->setSender($data['store_name']);
					$mail->setSubject($data['subject']);
					$mail->setHtml($html);
					$mail->setText(strip_tags($html));
					$status = $mail->send();
				} else {
					$mail = new Mail();
					$mail->protocol = $this->config->get('config_mail_protocol');
					$mail->parameter = $this->config->get('config_mail_parameter');
					$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
					$mail->smtp_username = $this->config->get('config_mail_smtp_username');
					$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
					$mail->smtp_port = $this->config->get('config_mail_smtp_port');
					$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
					$mail->setTo($mail_to);
					$mail->setFrom($mail_from);
					$mail->setSender($data['store_name']);
					$mail->setSubject($data['subject']);
					$mail->setHtml($html);
					$mail->setText(strip_tags($html));
					$mail->send();
				}
			}

		}
	}
  public function urlChange($route,$get = '',$extra = '') {
    if (version_compare(VERSION, '2.2', '>')) {
      return $this->url->link($route, $get, 'SSL');
    } else {
      return $this->url->link($route, $get, true);
    }

  }

  
           public function viewProducts($id){
    $sql = "SELECT pd.name,wrp.quantity,wrp.order_product_id,wrr.reason,p.model,op.product_id,op.quantity `quantity_total`,op.quantity_supplied `quantity_shipped`,op.price,op.order_id, IFNULL(rf.refund_amount,0) `refund_amount` FROM " . DB_PREFIX . "product_description pd LEFT JOIN ".DB_PREFIX."wk_rma_product wrp ON (wrp.product_id = pd.product_id) LEFT JOIN ".DB_PREFIX."wk_rma_reason wrr ON (wrp.reason = wrr.reason_id) LEFT JOIN ".DB_PREFIX."product p ON (p.product_id = pd.product_id) LEFT JOIN ".DB_PREFIX."order_product op ON (op.order_product_id = wrp.order_product_id) LEFT JOIN " .DB_PREFIX."return_refund rf ON (op.product_id = rf.product_id) WHERE wrp.rma_id = '".(int)$id."' AND pd.language_id = '".$this->config->get('config_language_id')."' AND wrr.language_id = '".$this->config->get('config_language_id')."'";

    //echo $sql;

    
           

    $result = $this->db->query($sql);

    return $result->rows;
  }

}
