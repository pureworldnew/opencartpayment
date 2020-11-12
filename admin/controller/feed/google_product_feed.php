<?php
class ControllerFeedGoogleProductFeed extends Controller {
	private $error = array(); 

	//////////////////////////////////////////////////////////////////////////////
	//                                                                          //
	//      Set up the Default Values                                           //
	//                                                                          //
	//////////////////////////////////////////////////////////////////////////////

	public function index() {
		$this->load->language('feed/google_product_feed');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
		$this->load->model('feed/google_product_feed');

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		if (isset($this->session->data['error_warning'])) {
			$this->data['error_warning'] = $this->session->data['error_warning'];		
			unset($this->session->data['error_warning']);
		} else {
			$this->data['error_warning'] = '';
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
		
			$this->load->model('setting/setting');
			$this->model_setting_setting->editSetting('google_product_feed', $this->request->post);

			if ($this->request->post['enable_all_products'] == "1") {
					$this->model_feed_google_product_feed->enableAllProducts();
					$this->data['success'] = 'All products have been enabled for Google Shopping';
			} 
			
			if ($this->request->post['copy_gtin'] == "0" && $this->request->post['enable_all_products'] == "0") {
				$this->session->data['success'] = $this->language->get('text_success');
				$this->response->redirect(HTTP_SERVER . 'index.php?route=extension/feed&token=' . $this->session->data['token']);
			} elseif ($this->request->post['copy_gtin'] == "1") {
					$this->model_feed_google_product_feed->copyGTIN($this->request->post['google_product_feed_copy_gtin']);
					$this->data['success'] = 'GTIN Data has been copied successfully';
			} elseif ($this->request->post['copy_gtin'] == "3") {
					$this->model_feed_google_product_feed->copyManufacturer();
					$this->data['success'] = 'Manufacturer Data has been copied successfully';
			} elseif ($this->request->post['copy_gtin'] == "4") {
					$this->model_feed_google_product_feed->copyProductCategory();
					$this->data['success'] = 'Default Google Product Category Data has been copied successfully';
			} elseif ($this->request->post['copy_gtin'] == "5") {
					$this->model_feed_google_product_feed->copyCondition();
					$this->data['success'] = 'Condition Data has been copied successfully';
			} elseif ($this->request->post['copy_gtin'] == "6") {
					$this->model_feed_google_product_feed->copyOosStatus();
					$this->data['success'] = 'Out of Stock Status has been copied successfully';
			} elseif ($this->request->post['copy_gtin'] == "7") {
					$this->model_feed_google_product_feed->copyIdentifierExists();
					$this->data['success'] = 'Identifier Exists Data has been copied successfully';
			} elseif ($this->request->post['copy_gtin'] == "2") {
					$this->model_feed_google_product_feed->copyMPN($this->request->post['google_product_feed_copy_mpn']);
					$this->data['success'] = 'MPN Data has been copied successfully';
			}
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_true'] = $this->language->get('text_true');
		$this->data['text_false'] = $this->language->get('text_false');

		$this->data['alert_enable'] = $this->language->get('alert_enable');
		$this->data['alert_copy_manufacturer'] = $this->language->get('alert_copy_manufacturer');
		$this->data['alert_google_product_category'] = $this->language->get('alert_google_product_category');
		$this->data['alert_gtin'] = $this->language->get('alert_gtin');

		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_enable_all_products'] = $this->language->get('entry_enable_all_products');
		$this->data['entry_data_feed'] = $this->language->get('entry_data_feed');
        $this->data['entry_currency'] = $this->language->get('entry_currency');
        $this->data['entry_language'] = $this->language->get('entry_language');
        $this->data['entry_copy_gtin'] = $this->language->get('entry_copy_gtin');
        $this->data['entry_copy_mpn'] = $this->language->get('entry_copy_mpn');
		$this->data['entry_default_google_product_category'] = $this->language->get('entry_default_google_product_category');
		$this->data['entry_copy_manufacturer'] = $this->language->get('entry_copy_manufacturer');
		$this->data['entry_condition'] = $this->language->get('entry_condition');
		$this->data['entry_oos_status'] = $this->language->get('entry_oos_status');
		$this->data['entry_identifier_exists'] = $this->language->get('entry_identifier_exists');
		$this->data['entry_information'] = $this->language->get('entry_information');
		$this->data['entry_troubleshooting'] = $this->language->get('entry_troubleshooting');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_bulk_update'] = $this->language->get('button_bulk_update');
		$this->data['button_view_feed'] = $this->language->get('button_view_feed');
		$this->data['button_enable_all_products'] = $this->language->get('button_enable_all_products');
		$this->data['button_copy_gtin'] = $this->language->get('button_copy_gtin');
		$this->data['button_install_taxonomy'] = $this->language->get('button_install_taxonomy');

		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['token'] = $this->session->data['token'];

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_feed'),
			'href'      => $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('feed/google_product_feed', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['action'] = HTTP_SERVER . 'index.php?route=feed/google_product_feed&token=' . $this->session->data['token'];

		$this->data['cancel'] = HTTP_SERVER . 'index.php?route=extension/feed&token=' . $this->session->data['token'];

		$this->data['bulk_update'] = $this->url->link('feed/google_product_feed/bulk', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['view_feed'] = HTTP_CATALOG . 'index.php?route=feed/google_product_feed';

		if (isset($this->request->post['google_product_feed_status'])) {
			$this->data['google_product_feed_status'] = $this->request->post['google_product_feed_status'];
		} else {
			$this->data['google_product_feed_status'] = $this->config->get('google_product_feed_status');
		}

		$this->data['data_feed'] = HTTP_CATALOG . 'index.php?route=feed/google_product_feed';

        $this->data['currencies'] = array('GBP', 'USD', 'EUR', 'AUD', 'JPY', 'CNY', 'CHF', 'CZK', 'BRL', 'CAD', 'INR');

        if (isset($this->request->post['google_product_feed_currency'])) {
            $this->data['google_product_feed_currency'] = $this->request->post['google_product_feed_currency'];
        } else {
            $this->data['google_product_feed_currency'] = $this->config->get('google_product_feed_currency');
        }

        $this->data['languages'] = array(
			array('value'=>'cs-CZ', 'selection' => '&#268;esky'),
			array('value'=>'de-DE', 'selection' => 'Deutsch'),
			array('value'=>'en-US', 'selection' => 'English (US)&lrm;'),
			array('value'=>'en-AU', 'selection' => 'English (Australia)&lrm;'),
			array('value'=>'en-GB', 'selection' => 'English (GB)&lrm;'),
			array('value'=>'es-ES', 'selection' => 'Espa&#241;ol'),
			array('value'=>'fr-FR', 'selection' => 'Fran&#231;ais'),
			array('value'=>'it-IT', 'selection' => 'Italiano'),
			array('value'=>'nl-NL', 'selection' => 'Nederlands'),
			array('value'=>'pt-BR', 'selection' => 'Portugu&#234;s (Brasil)&lrm;'),
			array('value'=>'ch-US', 'selection' => 'Chinese'),
			array('value'=>'ja-JP', 'selection' => 'Japanese')	
		);
		
        if (isset($this->request->post['google_product_feed_lang'])) {
            $this->data['google_product_feed_lang'] = $this->request->post['google_product_feed_lang'];
        } else {
            $this->data['google_product_feed_lang'] = $this->config->get('google_product_feed_lang');
        }

        $this->data['original_gtin'] = array('Select Field to Copy From',  'model', 'sku', 'mpn');
        $this->data['original_mpn'] = array('Select Field to Copy From', 'model', 'sku');


        if (isset($this->request->post['google_product_feed_copy_gtin'])) {
            $this->data['google_product_feed_copy_gtin'] = $this->request->post['google_product_feed_copy_gtin'];
        } else {
            $this->data['google_product_feed_copy_gtin'] = $this->config->get('google_product_feed_copy_gtin');
        }

        if (isset($this->request->post['google_product_feed_copy_mpn'])) {
            $this->data['google_product_feed_copy_mpn'] = $this->request->post['google_product_feed_copy_mpn'];
        } else {
            $this->data['google_product_feed_copy_mpn'] = $this->config->get('google_product_feed_copy_mpn');
        }

		if (isset($this->request->post['default_google_product_category'])) {
            $this->data['default_google_product_category'] = $this->request->post['default_google_product_category'];
        } else {
            $this->data['default_google_product_category'] = $this->config->get('default_google_product_category');
        }
		
		if (isset($this->request->post['google_product_feed_copy_manufacturer'])) {
            $this->data['google_product_feed_copy_manufacturer'] = $this->request->post['google_product_feed_copy_manufacturer'];
        } else {
            $this->data['google_product_feed_copy_manufacturer'] = $this->config->get('google_product_feed_copy_manufacturer');
        }
		
		if (isset($this->request->post['google_product_feed_shipping'])) {
            $this->data['google_product_feed_shipping'] = $this->request->post['google_product_feed_shipping'];
        } else {
            $this->data['google_product_feed_shipping'] = $this->config->get('google_product_feed_shipping');
        }



		if (isset($this->request->post['latest_taxonomy'])) {
            $this->data['latest_taxonomy'] = $this->request->post['latest_taxonomy'];
        } else {
            $this->data['latest_taxonomy'] = $this->config->get('latest_taxonomy');
        }

		$this->data['available_conditions'] = array('New', 'Refurbished', 'Used');
		$this->data['yes_no'] = array('Yes', 'No');


        if (isset($this->request->post['condition'])) {
            $this->data['condition'] = $this->request->post['condition'];
        } else {
            $this->data['condition'] = $this->config->get('condition');
        }
		
		$this->data['available_oos_statuses'] = array('Available for Order', 'Out of Stock', 'Preorder');

        if (isset($this->request->post['oos_status'])) {
            $this->data['oos_status'] = $this->request->post['oos_status'];
        } else {
            $this->data['oos_status'] = $this->config->get('oos_status');
        }

		if (isset($this->request->post['identifier_exists'])) {
            $this->data['identifier_exists'] = $this->request->post['identifier_exists'];
        } else {
            $this->data['identifier_exists'] = $this->config->get('identifier_exists');
        }

		if(!$this->config->get('google_product_feed_status')) {
			$this->data['gpf_defaults_saved'] ='0';
		} else {
			$this->data['gpf_defaults_saved'] ='1';
		}

		$this->template = 'feed/google_product_feed.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	//////////////////////////////////////////////////////////////////////////////
	//                                                                          //
	//      Create Product List for Bulk Updating                               //
	//                                                                          //
	//////////////////////////////////////////////////////////////////////////////

	public function bulk() {
		$this->load->language('feed/google_product_feed');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
		$this->load->model('feed/google_product_feed');
		$this->load->model('catalog/product');
		$this->load->model('catalog/manufacturer');
		$this->load->model('catalog/category');

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_model'])) {
			$filter_model = $this->request->get['filter_model'];
		} else {
			$filter_model = null;
		}
		
		if (isset($this->request->get['filter_manufacturer'])) {
			$filter_manufacturer = $this->request->get['filter_manufacturer'];
		} else {
			$filter_manufacturer = null;
		}

		if (isset($this->request->get['filter_category_id'])) {
			$filter_category_id = $this->request->get['filter_category_id'];
		} else {
			$filter_category_id = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pd.name';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
						
		$url = '';
						
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_manufacturer'])) {
			$url .= '&filter_manufacturer=' . $this->request->get['filter_manufacturer'];
		}
		
		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}		

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
						
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('feed/google_product_feed', 'token=' . $this->session->data['token'] . $url, 'SSL'),       		
      		'separator' => ' :: '
   		);
		
		$this->data['update'] = $this->url->link('feed/google_product_feed/bulk_update', 'token=' . $this->session->data['token'], 'SSL');
    	
		$this->data['products'] = array();

		$data = array(
			'filter_name'	  => $filter_name, 
			'filter_model'	  => $filter_model,
			'filter_manufacturer'	  => $filter_manufacturer,
			'filter_category_id' => $filter_category_id,
			'filter_status'   => $filter_status,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);
		
		$this->load->model('tool/image');
		
		$this->data['categories'] = $this->model_catalog_category->getCategories();
		$product_total = $this->model_catalog_product->getTotalProducts($data);
		$results = $this->model_feed_google_product_feed->getProducts($data);
				    	
		foreach ($results as $result) {
			
			if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.jpg', 40, 40);
			}
	
	
      		$this->data['products'][] = array(
				'product_id' => $result['product_id'],
				'name'       => $result['name'],
				'model'      => $result['model'],
				'manufacturer'      => $result['manufacturer'],
				'image'      => $image,
				'quantity'   => $result['quantity'],
				'status'     => ($result['gpf_status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'selected'   => isset($this->request->post['selected']) && in_array($result['product_id'], $this->request->post['selected']),
			);
    	}
		
		$this->data['heading_title'] = $this->language->get('heading_title_bulk');		
				
		$this->data['text_enabled'] = $this->language->get('text_enabled');		
		$this->data['text_disabled'] = $this->language->get('text_disabled');		
		$this->data['text_no_results'] = $this->language->get('text_no_results');		
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');		
			
		$this->data['column_image'] = $this->language->get('column_image');		
		$this->data['column_name'] = $this->language->get('column_name');		
		$this->data['column_model'] = $this->language->get('column_model');		
		$this->data['column_manufacturer'] = $this->language->get('column_manufacturer');		
		$this->data['column_quantity'] = $this->language->get('column_quantity');		
		$this->data['column_status'] = $this->language->get('column_status');		
		$this->data['column_category'] = $this->language->get('column_category');		
				
		$this->data['button_copy'] = $this->language->get('button_copy');		
		$this->data['button_insert'] = $this->language->get('button_insert');		
		$this->data['button_update'] = $this->language->get('button_update');		
		$this->data['button_filter'] = $this->language->get('button_filter');
		 
 		$this->data['token'] = $this->session->data['token'];
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['warning'])) {
			$this->data['error_warning'] = $this->session->data['warning'];		
			unset($this->session->data['warning']);
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_manufacturer'])) {
			$url .= '&filter_manufacturer=' . $this->request->get['filter_manufacturer'];
		}
		
		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}
		
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
								
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
					
		$this->data['sort_name'] = $this->url->link('feed/google_product_feed/bulk', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL');
		$this->data['sort_model'] = $this->url->link('feed/google_product_feed/bulk', 'token=' . $this->session->data['token'] . '&sort=p.model' . $url, 'SSL');
		$this->data['sort_manufacturer'] = $this->url->link('feed/google_product_feed/bulk', 'token=' . $this->session->data['token'] . '&sort=manufacturer' . $url, 'SSL');
		$this->data['sort_quantity'] = $this->url->link('feed/google_product_feed/bulk', 'token=' . $this->session->data['token'] . '&sort=p.quantity' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('feed/google_product_feed/bulk', 'token=' . $this->session->data['token'] . '&sort=p.gpf_status' . $url, 'SSL');
		$this->data['sort_order'] = $this->url->link('feed/google_product_feed/bulk', 'token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url, 'SSL');
		
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_manufacturer'])) {
			$url .= '&filter_manufacturer=' . $this->request->get['filter_manufacturer'];
		}
		
		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
				
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('feed/google_product_feed/bulk', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
	
		$this->data['filter_name'] = $filter_name;
		$this->data['filter_model'] = $filter_model;
		$this->data['filter_manufacturer'] = $filter_manufacturer;
		$this->data['filter_category_id'] = $filter_category_id;
		$this->data['filter_status'] = $filter_status;
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'feed/google_product_feed_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
  	}


	//////////////////////////////////////////////////////////////////////////////
	//                                                                          //
	//      Got the List, Let's Update the Fields                               //
	//                                                                          //
	//////////////////////////////////////////////////////////////////////////////

	public function bulk_update() {
	
		$this->load->language('feed/google_product_feed');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
		$this->load->model('feed/google_product_feed');
		$this->load->model('catalog/product');
		$this->load->model('catalog/manufacturer');
		$this->load->model('catalog/category');
	
		$this->data['products'] = array();
		
		if (isset($this->request->post['selected'])) {
		
			$ctr = -1;
			$selected_products = '';
			foreach ($this->request->post['selected'] as $product_id) {
				$ctr++;
				$product_data = $this->model_feed_google_product_feed->getProductData($product_id);
				if($selected_products) {
					$selected_products = $selected_products . ',' . $product_id;
				} else {
					$selected_products = $product_id;				
				}
				$this->data['products'][] = Array(
					'product_id' => $product_id,
					'name'       => $product_data['name'],
					'model'      => $product_data['model'],
					'id'         => $ctr
				);
			}
		$this->data['no_of_products'] = $ctr;
		$this->data['selected_products'] = $selected_products;
		} else {
			$this->session->data['warning'] = $this->language->get('error_none_selected');
			$this->response->redirect($this->url->link('feed/google_product_feed/bulk', 'token=' . $this->session->data['token'], 'SSL'));	
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('feed/google_product_feed', 'token=' . $this->session->data['token'], 'SSL'),       		
      		'separator' => ' :: '
   		);
		
		$this->data['update'] = $this->url->link('feed/google_product_feed/bulk_update_db', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('feed/google_product_feed', 'token=' . $this->session->data['token'], 'SSL');	

		$this->data['heading_title'] = $this->language->get('heading_title_bulk_update');		
				
		$this->data['text_enabled'] = $this->language->get('text_enabled');		
		$this->data['text_disabled'] = $this->language->get('text_disabled');		
		$this->data['text_yes'] = $this->language->get('text_yes');		
		$this->data['text_no'] = $this->language->get('text_no');		
		$this->data['text_true'] = $this->language->get('text_true');
		$this->data['text_false'] = $this->language->get('text_false');
		$this->data['text_no_results'] = $this->language->get('text_no_results');		
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');		
		$this->data['text_clear_keyword'] = $this->language->get('text_clear_keyword');
		$this->data['text_clear'] = $this->language->get('text_clear');
		$this->data['text_ignore'] = $this->language->get('text_ignore');
		$this->data['text_product'] = $this->language->get('text_product');
		$this->data['text_products'] = $this->language->get('text_products');
		$this->data['text_instructions_1'] = $this->language->get('text_instructions_1');		
		$this->data['text_instructions_2'] = $this->language->get('text_instructions_2');		
		$this->data['text_general'] = $this->language->get('text_general');		
			
		$this->data['column_image'] = $this->language->get('column_image');		
		$this->data['column_name'] = $this->language->get('column_name');		
		$this->data['column_model'] = $this->language->get('column_model');		
		$this->data['column_manufacturer'] = $this->language->get('column_manufacturer');		
		$this->data['column_quantity'] = $this->language->get('column_quantity');		
		$this->data['column_status'] = $this->language->get('column_status');		
		$this->data['column_category'] = $this->language->get('column_category');		
				
		$this->data['button_copy'] = $this->language->get('button_copy');		
		$this->data['button_insert'] = $this->language->get('button_insert');		
		$this->data['button_update'] = $this->language->get('button_update');		
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_remove'] = $this->language->get('button_remove');
		 
 		$this->data['token'] = $this->session->data['token'];
		$this->data['error_warning'] = '';
		
		$this->data['entry_google_product_category'] = $this->language->get('entry_google_product_category');
		$this->data['entry_brand'] = $this->language->get('entry_brand');
		$this->data['entry_gtin'] = $this->language->get('entry_gtin');
		$this->data['entry_mpn'] = $this->language->get('entry_mpn');
		$this->data['entry_list'] = $this->language->get('entry_list');
		$this->data['entry_gender'] = $this->language->get('entry_gender');
		$this->data['entry_agegroup'] = $this->language->get('entry_agegroup');
		$this->data['entry_colour'] = $this->language->get('entry_colour');
		$this->data['entry_size'] = $this->language->get('entry_size');
		$this->data['entry_condition'] = $this->language->get('entry_condition');
		$this->data['entry_oos_status'] = $this->language->get('entry_oos_status');
		$this->data['entry_identifier_exists'] = $this->language->get('entry_identifier_exists');
		$this->data['entry_variant'] = $this->language->get('entry_variant');
		$this->data['entry_information'] = $this->language->get('entry_information');
		$this->data['entry_products'] = $this->language->get('entry_products');
		$this->data['entry_clear_variants'] = $this->language->get('entry_clear_variants');
		$this->data['entry_adwords'] = $this->language->get('entry_adwords');
		$this->data['entry_adwords_grouping'] = $this->language->get('entry_adwords_grouping');
		$this->data['entry_adwords_labels'] = $this->language->get('entry_adwords_labels');
		$this->data['entry_adwords_redirect'] = $this->language->get('entry_adwords_redirect');
		$this->data['button_add_variant'] = $this->language->get('button_add_variant');
        $this->data['google_product_feed_copy_manufacturer'] = $this->config->get('google_product_feed_copy_manufacturer');

		$this->load->model('setting/setting');

        $this->data['google_product_feed_currency'] = $this->config->get('google_product_feed_currency');
		$this->data['google_product_feed_status'] = $this->config->get('google_product_feed_status');
		$this->data['google_product_feed_lang'] = $this->config->get('google_product_feed_lang');

		if (isset($this->request->post['google_product_category'])) {
      		$this->data['google_product_category'] = $this->request->post['google_product_category'];
    	} else if (!empty($product_info)) {
			if($product_info['google_product_category'] !="") {
				$this->data['google_product_category'] = $product_info['google_product_category'];
			} else {
				$this->data['google_product_category'] = $this->config->get('default_google_product_category');
			}
		} else {
      		$this->data['google_product_category'] = $this->config->get('default_google_product_category');
    	}

		if (isset($this->request->post['gpf_status'])) {
      		$this->data['gpf_status'] = $this->request->post['gpf_status'];
    	} else if (!empty($product_info)) {
			$this->data['gpf_status'] = $product_info['gpf_status'];
		} else {
      		$this->data['gpf_status'] = '0';
    	}

		if (isset($this->request->post['brand'])) {
      		$this->data['brand'] = $this->request->post['brand'];
    	} else if (!empty($product_info)) {
			$this->data['brand'] = $product_info['brand'];
		} else {
      		$this->data['brand'] = '';
    	}

		if (isset($this->request->post['gtin'])) {
      		$this->data['gtin'] = $this->request->post['gtin'];
    	} else if (!empty($product_info)) {
			$this->data['gtin'] = $product_info['gtin'];
		} else {
      		$this->data['gtin'] = '';
    	}

		if (isset($this->request->post['mpn'])) {
      		$this->data['mpn'] = $this->request->post['mpn'];
    	} else if (!empty($product_info)) {
			$this->data['mpn'] = $product_info['mpn'];
		} else {
      		$this->data['mpn'] = '';
    	}

		$this->data['available_genders'] = array('Do Not Change', 'Not Applicable', 'Male', 'Female', 'Unisex');
		if (isset($this->request->post['gender'])) {
      		$this->data['gender'] = $this->request->post['gender'];
    	} else if (!empty($product_info)) {
			$this->data['gender'] = $product_info['gender'];
		} else {
      		$this->data['gender'] = 'Do Not Change';
    	}


		$this->data['available_agegroup'] = array('Do Not Change', 'Not Applicable', 'Kids', 'Adult');
		if (isset($this->request->post['agegroup'])) {
      		$this->data['agegroup'] = $this->request->post['agegroup'];
    	} else if (!empty($product_info)) {
			$this->data['agegroup'] = $product_info['agegroup'];
		} else {
      		$this->data['agegroup'] = 'Do Not Change';
    	}


		if (isset($this->request->post['colour'])) {
      		$this->data['colour'] = $this->request->post['colour'];
    	} else if (!empty($product_info)) {
			$this->data['colour'] = $product_info['colour'];
		} else {
      		$this->data['colour'] = '';
    	}

		if (isset($this->request->post['size'])) {
      		$this->data['size'] = $this->request->post['size'];
    	} else if (!empty($product_info)) {
			$this->data['size'] = $product_info['size'];
		} else {
      		$this->data['size'] = '';
    	}

		$this->data['available_conditions'] = array('New', 'Refurbished', 'Used');
    	if (isset($this->request->post['condition'])) {
      		$this->data['condition'] = $this->request->post['condition'];
    	} else if (!empty($product_info['condition'])) {
			$this->data['condition'] = $product_info['condition'];
		} else {
      		$this->data['condition'] = $this->config->get('condition');
    	}

		
		$this->data['available_oos_statuses'] = array('Available for Order', 'Out of Stock', 'Preorder');
    	if (isset($this->request->post['oos_status'])) {
      		$this->data['oos_status'] = $this->request->post['oos_status'];
    	} else if (!empty($product_info['oos_status'])) {
			$this->data['oos_status'] = $product_info['oos_status'];
		} else {
      		$this->data['oos_status'] = $this->config->get('oos_status');
    	}
		
		
		if (isset($this->request->post['adwords_grouping'])) {
      		$this->data['adwords_grouping'] = $this->request->post['adwords_grouping'];
    	} else if (!empty($product_info)) {
			$this->data['adwords_grouping'] = $product_info['adwords_grouping'];
		} else {
      		$this->data['adwords_grouping'] = '';
    	}

		if (isset($this->request->post['adwords_labels'])) {
      		$this->data['adwords_labels'] = $this->request->post['adwords_labels'];
    	} else if (!empty($product_info)) {
			$this->data['adwords_labels'] = $product_info['adwords_labels'];
		} else {
      		$this->data['adwords_labels'] = '';
    	}

		if (isset($this->request->post['product_variants'])) {
      		$product_variants = $this->request->post['product_variants'];
    	} elseif (isset($this->request->get['product_id'])) {
			$product_variants = $this->model_catalog_product->getProductVariants($this->request->get['product_id']);
		} else {
			$product_variants = array();
		}

		$this->data['product_variants'] = array();
		
		foreach ($product_variants as $product_variant) {
			if ($product_variant['image'] && file_exists(DIR_IMAGE . $product_variant['image'])) {
				$image = $product_variant['image'];
			} else {
				$image = 'no_image.jpg';
			}
			
			$this->data['product_variants'][] = array(
				'image'      => $image,
				'thumb'      => $this->model_tool_image->resize($image, 100, 100),
				'size' 		 => $product_variant['size'],
				'colour' 	 => $product_variant['colour'],
				'material' 	 => $product_variant['material'],
				'pattern' 	 => $product_variant['pattern']
			);
		}

		
		$this->template = 'feed/google_product_feed_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
		
	}
	
	//////////////////////////////////////////////////////////////////////////////
	//                                                                          //
	//      Updated the Fields, Let's Update the Database                       //
	//                                                                          //
	//////////////////////////////////////////////////////////////////////////////

	public function bulk_update_db() {

		$this->load->language('feed/google_product_feed');

		$this->load->model('setting/setting');
		$this->load->model('feed/google_product_feed');
		
		if (isset($this->request->post['selected_products']) && $this->validate()) {

				if (isset($this->request->post['product_variants'])) {
					$product_variants = $this->request->post['product_variants'];
				} else {
					$product_variants = false;
				}
			
				$selected_products = explode(',', $this->request->post['selected_products']);
				
				$data = array(
				'selected_products' 		=> $selected_products,
				'gpf_status' 				=> $this->request->post['gpf_status'],
				'google_product_category' 	=> $this->request->post['google_product_category'],
				'brand' 					=> $this->request->post['brand'],
				'mpn' 						=> $this->request->post['mpn'],
				'gender' 					=> $this->request->post['gender'],
				'agegroup' 					=> $this->request->post['agegroup'],
				'colour' 					=> $this->request->post['colour'],
				'size' 						=> $this->request->post['size'],
				'condition' 				=> $this->request->post['condition'],
				'oos_status' 				=> $this->request->post['oos_status'],
				'identifier_exists' 		=> $this->request->post['identifier_exists'],
				'adwords_grouping' 			=> $this->request->post['adwords_grouping'],
				'adwords_labels' 			=> $this->request->post['adwords_labels'],
				'adwords_redirect' 			=> $this->request->post['adwords_redirect'],
				'product_variants'			=> $product_variants,
				'clear_variants'			=> $this->request->post['clear_variants'],
				'text_clear_keyword'		=> $this->language->get('text_clear_keyword')
				);

				
				$result = $this->model_feed_google_product_feed->bulk_update_db($data);
				
			$this->session->data['success'] = $this->language->get('text_success_bulk_update');
			$this->response->redirect($this->url->link('feed/google_product_feed', 'token=' . $this->session->data['token'], 'SSL'));	
		
		} else {
			$this->session->data['error_warning'] = $this->language->get('error_permission');
			$this->response->redirect($this->url->link('feed/google_product_feed', 'token=' . $this->session->data['token'], 'SSL'));
		}
	}
	

	private function validate() {
		if (!$this->user->hasPermission('modify', 'feed/google_product_feed')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>