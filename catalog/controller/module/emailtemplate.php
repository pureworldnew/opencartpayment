<?php
class ControllerModuleEmailtemplate extends Controller {

	public $trigger_key = '';

    public function index() {
    	return $this->view();
    }

    public function view() {
    	if(empty($this->request->get['id']) || empty($this->request->get['enc'])){
			exit;
    	}

   		$this->load->model('module/emailtemplate');

   		$log = $this->model_module_emailtemplate->getTemplateLog(array(
   			'emailtemplate_log_id' => $this->request->get['id'],
   			'emailtemplate_log_enc' => $this->request->get['enc']
   		), true);

   		if($log){
   			$template = $this->model_module_emailtemplate->getTemplate($log['emailtemplate_id'], true, true);

   			if ($template) {

   				if ($template['emailtemplate_config_id']) {
   					$config = $this->model_module_emailtemplate->getConfig($template['emailtemplate_config_id']);
   				} else {
   					$config_load = array('store_id' => $this->config->get('config_store_id'), 'language_id' => $this->config->get('config_language_id'));

   					$configs = $this->model_module_emailtemplate->getConfigs($config_load);

   					if (!$configs) {
   						$configs = $this->model_module_emailtemplate->getConfigs();
   					}

   					if ($configs) {
   						if (count($configs) == 1) {
   							$config = $configs[0];
   						} elseif (count($configs) > 1) {
   							foreach ($configs as &$config) {
   								$config['power'] = 0;
   								foreach ($config_load as $_key => $_value) {
   									$config['power'] = $config['power'] << 1;
   									if (!empty($config[$_key]) && $config[$_key] == $_value) {
   										$config['power'] |= 1;
   									}
   								}
   							}
    						unset($config);
   							$config = $configs[0];

   							foreach ($configs as $_config) {
   								if ($_config['power'] > $config['power']) {
   									$config = $_config;
   								}
   							}
   						}
   					}
   				}

				if (empty($config)) {
					$config = $this->model_module_emailtemplate->getConfig(1);
				}

	    		if($config && $config['emailtemplate_config_view_browser_theme']){
	    			$this->load->language('information/information');

	    			$data['breadcrumbs'] = array();

	    			$data['breadcrumbs'][] = array(
    					'text'      => $this->language->get('text_home'),
    					'href'      => $this->url->link('common/home'),
    					'separator' => false
	    			);

	    			$this->document->setTitle($log['subject']);

	    			$data['heading_title'] = $log['subject'];

	    			$data['button_continue'] = $this->language->get('button_continue');

	    			$data['description'] = html_entity_decode($log['content'], ENT_QUOTES, 'UTF-8');

	    			$data['continue'] = $this->url->link('common/home');

		    		$data['column_left'] = $this->load->controller('common/column_left');
					$data['column_right'] = $this->load->controller('common/column_right');
					$data['content_top'] = $this->load->controller('common/content_top');
					$data['content_bottom'] = $this->load->controller('common/content_bottom');
					$data['footer'] = $this->load->controller('common/footer');
					$data['header'] = $this->load->controller('common/header');

					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/information.tpl')) {
						$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/information/information.tpl', $data));
					} else {
						$this->response->setOutput($this->load->view('default/template/information/information.tpl', $data));
					}
	    		} else {
	    			$template = new EmailTemplate($this->request, $this->registry);

	    			$template->load(1);

	    			$content = html_entity_decode($log['content'], ENT_QUOTES, 'UTF-8');

	    			$template->fetch(null, $content);

	    			$html = $template->getHtml();

	    			die($html);
	    		}
	    	}
    	}
    }

    public function record() {
    	if(!empty($this->request->get['id']) && !empty($this->request->get['enc'])){
    		$this->load->model('module/emailtemplate');

    		$this->model_module_emailtemplate->readTemplateLog($this->request->get['id'], $this->request->get['enc']);

    		if(defined('DIR_CATALOG')){
    			$graphic_http =  DIR_CATALOG . '/view/theme/default/image/blank.gif';
    		} else {
    			$graphic_http =  DIR_APPLICATION . '/view/theme/default/image/blank.gif';
    		}

    		$filesize = filesize(DIR_TEMPLATE . 'default/image/blank.gif');

    		header('Content-Type: image/gif');
    		header('Pragma: public');
    		header('Expires: 0');
    		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    		header('Cache-Control: private',false);
    		header('Content-Disposition: attachment; filename="blank.gif"');
    		header('Content-Transfer-Encoding: binary');
    		header('Content-Length: '.$filesize);
    		readfile($graphic_http);

    		exit;
    	}
    }

    /**
     * Add Return Mail
     */
    public function send_return_mail($return_id) {
    	$this->load->model('account/return');

    	$return_info = $this->model_account_return->getReturn($return_id);
    	if (!$return_info) return false;

    	$this->load->language('mail/return');

    	$file = DIR_SYSTEM . 'library/emailtemplate/email_template.php';

		if (file_exists($file)) {
			include_once($file);
		} else {
			trigger_error('Error: Could not load library ' . $file . '!');
			exit();
		}

    	$template = new EmailTemplate($this->request, $this->registry);

    	$this->load->model('checkout/order');

    	$order_info = $this->model_checkout_order->getOrder($return_info['order_id']);
    	if ($order_info && $order_info['email'] == $return_info['email']) {
    		$template->addData($order_info, 'order');

    		$template->data['order_date'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));
    	}

    	$template->addData($return_info);

    	$template->data['opened'] = $return_info['opened'] ? $this->language->get('text_yes') : $this->language->get('text_no');

    	$template->data['return_date'] = date($this->language->get('date_format_short'), strtotime($return_info['date_ordered']));

    	$template->data['comment'] = $return_info['comment'] ? nl2br($return_info['comment']) : '';

    	$template->data['text_greeting'] = sprintf($this->language->get('text_greeting'), $this->config->get('config_name'));

    	$template->data['return_link'] = (defined('HTTP_ADMIN') ? HTTP_ADMIN : HTTPS_SERVER.'admin/') . 'index.php?route=sale/return/info&return_id=' . $return_id;

    	$template->load('order.return');

    	$template->send();

    	return true;
    }
}