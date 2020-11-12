<?php
class ControllerAbandonedCartsScript extends Controller {
	public function index() {
		$this->load->language('abandoned_carts/script');
		
		$this->load->model('abandoned_carts/script');
		
		$this->load->model('catalog/product');
		
		$this->load->model('tool/image');
		
		$this->load->model('tool/upload');
		
		$this->document->setTitle($this->language->get('heading_title'));

		if($this->config->get('abandoned_carts_abandoned_template_id') && $this->config->get('abandoned_carts_crownjob_status')) {
			$temlpate_info = $this->model_abandoned_carts_script->getAbandonedTemplateData($this->config->get('abandoned_carts_abandoned_template_id'));
			if(!empty($temlpate_info['status'])) {
				$filter_data = array(
					'filter_email_notify'		=> '0',
				);
				
				$peoples = $this->model_abandoned_carts_script->getAbandonedCartsPeoples($filter_data);
				if($peoples) {
					$i = 0; 
					foreach($peoples as $people) {
						$filter_data = array(
							'filter_email'	  						=> $people['email'],
							'filter_store_id'	  					=> $people['store_id'],
						);
						
						$products = $this->model_abandoned_carts_script->getAbandonedCartsProducts($filter_data);
						
						$this->model_abandoned_carts_script->UpdateCartNotify($people['email'], $people['store_id']);
						
						$datas['title'] = $this->language->get('heading_title');
						$datas['text_image'] = $this->language->get('text_image');
						$datas['text_product'] = $this->language->get('text_product');
						$datas['text_model'] = $this->language->get('text_model');
						$datas['text_quantity'] = $this->language->get('text_quantity');
						$datas['text_date_added'] = $this->language->get('text_date_added');
						
						$filter_data = array(
							'filter_email'	  						=> $people['email'],
							'filter_store_id'	  					=> $people['store_id'],
						);
						
						$products = $this->model_abandoned_carts_script->getAbandonedCartsProducts($filter_data);
						
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
										$option_datas = $this->model_abandoned_carts_script->getCartProductOptions($product['product_id'], $product_option_id, $value);
										
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
						
						if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/abandoned_carts/cart_html.tpl')) {
							$cart_html = $this->load->view($this->config->get('config_template') . '/template/abandoned_carts/cart_html.tpl', $datas);
						} else {
							$cart_html = $this->load->view('default/template/abandoned_carts/cart_html.tpl', $datas);
						}		
						
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
							'logo' 			 => '<img src="'. HTTP_SERVER .'image/'. $this->config->get('config_logo') .'" title="'. $this->config->get('config_name') .'" alt="'. $this->config->get('config_name') .'" />',
							'customer_id'=> $people['customer_id'],
							'firstname'  => $people['firstname'],
							'lastname' 	 => $people['lastname'],
							'email'			 => $people['email'],
							'telephone'  => $people['telephone'],
							'cart' 			 => $cart_html,
							'date_added' => date($this->language->get('date_format_short'), strtotime($people['date_added'])),
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
						$mail->setTo($people['email']);
						$mail->setFrom($this->config->get('config_email'));
						$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
						$mail->setSubject($subject);
						$mail->setHtml(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
						$mail->send();
						
						$i++; 
					}
					
					echo $i.' Email Send';
				}else{
					echo 'No Cart Found!';
				}
			}else{
				echo 'Template Disabled';
			}
		}else{
			echo 'Template or status disabled';
		}
	}
}
