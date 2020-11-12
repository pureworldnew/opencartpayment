<?php
class ControllerCheckoutSuccess extends Controller {
	public function index() {

			$data['tmdaccount_customcss'] = $this->config->get('tmdaccount_custom_css');
			$data['tmdaccount_status'] = $this->config->get('tmdaccount_status');
		$this->load->language('checkout/success');
		$this->document->addStyle('catalog/view/theme/gempack/stylesheet/giveaway.css'); 

				// OrderSuccessPage Code
				if (!empty($this->session->data['order_id'])) {
					$this->session->data['osp_order_id'] = $this->session->data['order_id'];
                    $osp_order_products = $this->cart->getProducts();
				}
				$data['osp_order_id'] = !empty($this->session->data['osp_order_id']) ? $this->session->data['osp_order_id'] : 0;
				// OrderSuccessPage Code
			
		if (isset($this->session->data['order_id'])) {

			// Abandoned Cart xml starts
			$this->abandonedcart->addAbandonedCartHistory($this->session->data['order_id']);
			// Abandoned Cart xml ends
			
			$this->cart->clear();

			// Add to activity log
			$this->load->model('account/activity');

			if ($this->customer->isLogged()) {
				$activity_data = array(
					'customer_id' => $this->customer->getId(),
					'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
					'order_id'    => $this->session->data['order_id']
				);

				$this->model_account_activity->addActivity('order_account', $activity_data);
				if ( isset($this->session->data['eligible_to_combine']) )
				{
					$this->model_account_activity->addCombineShipping( $this->customer->getId(), $this->session->data['eligible_to_combine'], $this->config->get('time_frame'), $this->config->get('selected_order_statuses') );

					unset($this->session->data['eligible_to_combine']);
				}
				$this->model_account_activity->updateSoldQuantity($this->session->data['order_id']);
				$this->model_account_activity->addAuthorizationAmount($this->session->data['order_id']);
				$this->model_account_activity->subtractStock($this->session->data['order_id']);
			} else {
				$activity_data = array(
					'name'     => $this->session->data['guest']['firstname'] . ' ' . $this->session->data['guest']['lastname'],
					'order_id' => $this->session->data['order_id']
				);

				$this->model_account_activity->addActivity('order_guest', $activity_data);
				$this->model_account_activity->updateSoldQuantity($this->session->data['order_id']);
				$this->model_account_activity->addAuthorizationAmount($this->session->data['order_id']);
				$this->model_account_activity->subtractStock($this->session->data['order_id']);
			}

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['guest']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);
			unset($this->session->data['totals']);
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_basket'),
			'href' => $this->url->link('checkout/cart')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_checkout'),
			'href' => $this->url->link('checkout/checkout', '', 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_success'),
			'href' => $this->url->link('checkout/success')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		if ($this->customer->isLogged()) {
			$data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/account', '', 'SSL'), $this->url->link('account/order', '', 'SSL'), $this->url->link('account/download', '', 'SSL'), $this->url->link('information/contact'));
		} else {
			$data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
		}

		$data['button_continue'] = $this->language->get('button_continue');

		$data['continue'] = $this->url->link('common/home');

		$data['giveaway_action'] = $this->url->link('checkout/success/addgiveaway'); 

		$this->load->model('setting/setting');
		$giveaway_info = $this->model_setting_setting->getSetting('giveaway'); 
		$data['giveaway_status'] = $giveaway_info['giveaway_status'];
		if( $data['giveaway_status'] == 1 )
		{
			$data['giveaway_minimum_order'] = $giveaway_info['giveaway_minimum_order'];
			$giveaway_products_id_array = $giveaway_info['giveaway_product'];
			$giveaway_products_id = implode(",",$giveaway_products_id_array);
			$this->load->model('catalog/product');
			$this->load->model('tool/image');
			$data['giveaway_already_added'] = $this->checkGiveawayAdded($data['osp_order_id']);
			$giveaway_products_data = $this->model_catalog_product->getGiveawayProducts($giveaway_products_id);
			$data['giveaway_products'] = array();
			foreach($giveaway_products_data as $giveaway_product_data)
			{
				if ($giveaway_product_data['image']) {
					$giveaway_image = $this->model_tool_image->resize($giveaway_product_data['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
				} else {
					$giveaway_image = '';
				}
				
				$data['giveaway_products'][] = array(
					'product_id' 	=> $giveaway_product_data['product_id'],
					'name' 			=> $giveaway_product_data['name'],
					'image' 		=> $giveaway_image
				);
			}


		}
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');


				// OrderSuccessPage Code
				$OSP = $this->config->get('ordersuccesspage');
				$current_language = $this->config->get('config_language_id');

				if (isset($OSP['Enabled']) && $OSP['Enabled'] == 'yes' && $data['osp_order_id']!='0') {
					$this->load->model('checkout/order');
					$this->load->model('module/ordersuccesspage');
					$this->load->language('account/order');
					$this->load->language('checkout/order');

					$data['current_language'] = $this->config->get('config_language_id');
					
                    if (isset($OSP['PageTitle'][$current_language])) {
                        $this->document->setTitle($OSP['PageTitle'][$current_language]);
                        $data['heading_title'] = $OSP['PageTitle'][$current_language];
                        $data['header'] = $this->load->controller('common/header');
                    }	
					
                    $language_array = array('text_order_detail', 'text_invoice_no', 'text_order_id', 'text_date_added', 'text_shipping_method', 'text_shipping_address', 'text_payment_method', 'text_payment_address', 'text_payment_address', 'text_history', 'text_comment', 'column_name', 'column_model', 'column_quantity', 'column_price', 'column_total', 'column_action', 'column_date_added', 'column_status', 'column_comment', 'text_tax', 'button_cart', 'button_wishlist', 'button_compare');

					foreach ($language_array as $lang) {
                        $data[$lang] = $this->language->get($lang);
                    }

					$data['OSP_orderdata']		= $this->model_checkout_order->getOrder($data['osp_order_id']);
                    $data['OSP_ordertotal']		= $this->model_checkout_order->getOrderTotal($data['osp_order_id']);
                    $data['OSP_ordertax']		= $this->model_checkout_order->getOrderTax($data['osp_order_id']);
                    $data['OSP_ordershipping']	= $this->model_checkout_order->getOrderShipping($data['osp_order_id']);
					$data['OSP'] 				= $this->model_module_ordersuccesspage->getMessage($OSP, $data['OSP_orderdata'], $current_language);
					$data['promoted_products'] 	= $this->model_module_ordersuccesspage->getPromotedProducts($OSP);
					$data['OSP_orderinfo']		= $this->model_module_ordersuccesspage->getOrderData($data['OSP_orderdata'], $osp_order_products);
					$data['OSP_settings']		= $this->config->get('ordersuccesspage');
					
					if (!empty($data['OSP']['CustomJS'])) {
						$original = array('{order_id}', '{order_total}');
						$replace = array($data['osp_order_id'], $data['OSP_orderdata']['total']);
						$data['OSP']['CustomJS'] = str_replace($original, $replace, $data['OSP']['CustomJS']);
					}
					
					if($this->config->get('config_template') == 'journal2') {
						if (file_exists(DIR_TEMPLATE . 'default/template/common/ordersuccesspage_journal.tpl')) {
							$this->response->setOutput($this->load->view('default/template/common/ordersuccesspage_journal.tpl', $data));
							return;
						}
					} else {
						if (file_exists(DIR_TEMPLATE . 'default/template/common/ordersuccesspage.tpl')) {
							$this->response->setOutput($this->load->view('default/template/common/ordersuccesspage.tpl', $data));
							return;
						}
					}

				}
				// OrderSuccessPage Code
			
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/success.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/common/success.tpl', $data));
		}
	}

	public function addgiveaway()
	{
		$giveaway_order_id = $this->request->post['giveaway_order_id'];
		$giveaway_product_id = $this->request->post['giveaway_product_id'];
		$this->load->model('checkout/order'); 
		$is_valid_gift_product = $this->model_checkout_order->isValidGiftProduct($giveaway_product_id); 

		if( $is_valid_gift_product )
		{
			$this->model_checkout_order->addGiftProductToOrder($giveaway_order_id, $giveaway_product_id); 
		}

		$this->redirect($this->url->link('checkout/success'));

	}

	public function checkGiveawayAdded($order_id)
	{
		$query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "order_product WHERE is_gift_product = 1 AND order_id = '" . (int)$order_id . "'");
		return $query->row ? true : false;
	}
}