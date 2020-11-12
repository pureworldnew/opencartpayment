<?php 
/* 
  #file: admin/controller/catalog/product_configurable.php
  #powered by fabiom7 - www.fabiom7.com - fabiome77@hotmail.it - copyright fabiom7 2012 - 2013 - 2014
  #switched: v1.5.4.1 - v1.5.5.1 - v1.5.6
*/

class ControllerCatalogProductConfigurable extends Controller { 
	private $error = array();
	
	public function insert() {
		$this->language->load('catalog/product');
		
		$this->language->load('catalog/product_configurable');
		$this->load->model('catalog/product_configurable');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/product');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$product_id = $this->model_catalog_product_configurable->addProduct($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			if (isset($this->request->get['filter_type'])) {
				$url .= '&filter_type=' . $this->request->get['filter_type'];
			}
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
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
			
			if (isset($this->request->post['save_continue'])) {
				$this->response->redirect($this->url->link('catalog/product_configurable/update', 'token='.$this->session->data['token'].'&product_id='.$product_id.$url, 'SSL'));
			} else {
				$this->response->redirect($this->url->link('catalog/product_list_gp', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}
		
		$this->getForm();
	}

	public function update() {
		$this->language->load('catalog/product');
		
		$this->language->load('catalog/product_configurable');
		$this->load->model('catalog/product_configurable');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/product');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$product_id = $this->request->get['product_id'];
			
			$this->model_catalog_product_configurable->editProduct($product_id, $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			if (isset($this->request->get['filter_type'])) {
				$url .= '&filter_type=' . $this->request->get['filter_type'];
			}
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
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
			
			if (isset($this->request->post['save_continue'])) {
				$this->response->redirect($this->url->link('catalog/product_configurable/update', 'token='.$this->session->data['token'].'&product_id='.$product_id.$url, 'SSL'));
			} else {
				$this->response->redirect($this->url->link('catalog/product_list_gp', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}

		$this->getForm();
	}
	
	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_none'] = $this->language->get('text_none');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_select_all'] = $this->language->get('text_select_all');
		$data['text_unselect_all'] = $this->language->get('text_unselect_all');
		$data['text_image_manager'] = $this->language->get('text_image_manager');
		$data['text_browse'] = $this->language->get('text_browse');
		$data['text_clear'] = $this->language->get('text_clear');
		$data['text_enabled'] = $this->language->get('text_enabled');
    	$data['text_disabled'] = $this->language->get('text_disabled');
		
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_tag_title'] = $this->language->get('entry_tag_title');
		$data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_tag'] = $this->language->get('entry_tag');
		
		$data['entry_model'] = $this->language->get('entry_model');
		$data['entry_sku'] = $this->language->get('entry_sku');
		$data['entry_keyword'] = $this->language->get('entry_keyword');
		$data['entry_weight_class'] = $this->language->get('entry_weight_class');
    	$data['entry_weight'] = $this->language->get('entry_weight');
		$data['entry_dimension'] = $this->language->get('entry_dimension');
		$data['entry_length'] = $this->language->get('entry_length');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_date_available'] = $this->language->get('entry_date_available');
		$data['entry_quantity'] = $this->language->get('entry_quantity');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_download'] = $this->language->get('entry_download');
    	$data['entry_category'] = $this->language->get('entry_category');
		$data['entry_filter'] = $this->language->get('entry_filter');
		$data['entry_related'] = $this->language->get('entry_related');
		$data['entry_attribute'] = $this->language->get('entry_attribute');
		$data['entry_text'] = $this->language->get('entry_text');
		
		$data['tab_general'] = $this->language->get('tab_general');
    	$data['tab_data'] = $this->language->get('tab_data');
		$data['tab_attribute'] = $this->language->get('tab_attribute');
		$data['tab_links'] = $this->language->get('tab_links');
		$data['tab_image'] = $this->language->get('tab_image');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_add_attribute'] = $this->language->get('button_add_attribute');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['button_add_image'] = $this->language->get('button_add_image');
		
		$data['column_image'] = $this->language->get('column_image');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_price'] = $this->language->get('column_price');
		$data['entry_subtract'] = $this->language->get('entry_subtract');
		
	////////
		$data['column_config_option_type'] = $this->language->get('column_config_option_type');
		$data['column_config_option_required'] = $this->language->get('column_config_option_required');
		$data['column_config_option_quantity'] = $this->language->get('column_config_option_quantity');
		$data['column_config_option_hide_qty'] = $this->language->get('column_config_option_hide_qty');
		$data['column_config_option_label'] = $this->language->get('column_config_option_label');
		
		$data['text_auto_identifier_system'] = $this->language->get('text_auto_identifier_system');
		$data['text_yes_add_no_thanks'] = $this->language->get('text_yes_add_no_thanks');
		$data['text_amount'] = $this->language->get('text_amount');//OC product
		$data['text_percent'] = $this->language->get('text_percent');//OC product
		$data['text_help_config_option'] = $this->language->get('text_help_config_option');
		$data['text_help_reward_point'] = $this->language->get('text_help_reward_point');
		
		$data['entry_price'] = $this->language->get('entry_price');
		$data['entry_price_from'] = $this->language->get('entry_price_from');
		$data['entry_price_to'] = $this->language->get('entry_price_to');
		$data['entry_price_fixed'] = $this->language->get('entry_price_fixed');
		$data['entry_tax_class'] = $this->language->get('entry_tax_class');
		
		$data['tab_grouped'] = $this->language->get('tab_grouped');
		$data['tab_system_identifier'] = $this->language->get('tab_system_identifier');
		
		$data['button_save_continue'] = $this->language->get('button_save_continue');
		$data['button_add_config_option'] = $this->language->get('button_add_config_option');
		
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
		
 		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
		}
		
		if (isset($this->error['option_type'])) {
			$data['error_option_type'] = $this->error['option_type'];
		} else {
			$data['error_option_type'] = '';
		}
		
		$url = '';
		if (isset($this->request->get['filter_type'])) {
			$url .= '&filter_type=' . $this->request->get['filter_type'];
		}
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
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
		
  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/product_list_gp', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		if (!isset($this->request->get['product_id'])) {
			$data['action'] = $this->url->link('catalog/product_configurable/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('catalog/product_configurable/update', 'token=' . $this->session->data['token'] . '&product_id=' . $this->request->get['product_id'] . $url, 'SSL');
		}
		
		$data['cancel'] = $this->url->link('catalog/product_list_gp', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['product_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);
    	}
		
		$data['token'] = $this->session->data['token'];
		
		$this->load->model('localisation/language');
		
		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['product_description'])) {
			$data['product_description'] = $this->request->post['product_description'];
		} elseif (isset($this->request->get['product_id'])) {
			$data['product_description'] = $this->model_catalog_product->getProductDescriptions($this->request->get['product_id']);
		} else {
			$data['product_description'] = array();
		}
		
		if (!empty($product_info)) {
			$data['model'] = $product_info['model'];
		} else {
      		$data['model'] = 'grouped';
    	}
		
		if (isset($this->request->post['sku'])) {
      		$data['sku'] = $this->request->post['sku'];
    	} elseif (!empty($product_info)) {
			$data['sku'] = $product_info['sku'];
		} else {
      		$data['sku'] = '';
    	}
		
		$this->load->model('setting/store');
		
		$data['stores'] = $this->model_setting_store->getStores();
		
		if (isset($this->request->post['product_store'])) {
			$data['product_store'] = $this->request->post['product_store'];
		} elseif (isset($this->request->get['product_id'])) {
			$data['product_store'] = $this->model_catalog_product->getProductStores($this->request->get['product_id']);
		} else {
			$data['product_store'] = array(0);
		}
		
		if (isset($this->request->post['keyword'])) {
			$data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($product_info)) {
			$data['keyword'] = $product_info['keyword'];
		} else {
			$data['keyword'] = '';
		}
		
		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($product_info)) {
			$data['image'] = $product_info['image'];
		} else {
			$data['image'] = '';
		}
		
		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && file_exists(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($product_info) && $product_info['image'] && file_exists(DIR_IMAGE . $product_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($product_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		
		if (isset($this->request->post['price'])) {
      		$data['price'] = $this->request->post['price'];
    	} elseif (!empty($product_info)) {
			$data['price'] = $product_info['price'];
		} else {
      		$data['price'] = '';
    	}
		
		$this->load->model('localisation/tax_class');
		
		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
    	
		if (isset($this->request->post['tax_class_id'])) {
      		$data['tax_class_id'] = $this->request->post['tax_class_id'];
    	} elseif (!empty($product_info)) {
			$data['tax_class_id'] = $product_info['tax_class_id'];
		} else {
      		$data['tax_class_id'] = 0;
    	}
		
		if (isset($this->request->post['date_available'])) {
       		$data['date_available'] = $this->request->post['date_available'];
		} elseif (!empty($product_info)) {
			$data['date_available'] = date('Y-m-d', strtotime($product_info['date_available']));
		} else {
			$data['date_available'] = date('Y-m-d', time() - 86400);
		}
		
		if (isset($this->request->post['quantity'])) {
      		$data['quantity'] = $this->request->post['quantity'];
    	} elseif (!empty($product_info)) {
      		$data['quantity'] = $product_info['quantity'];
    	} else {
			$data['quantity'] = 1;
		}
		
		if (isset($this->request->post['sort_order'])) {
      		$data['sort_order'] = $this->request->post['sort_order'];
    	} elseif (!empty($product_info)) {
      		$data['sort_order'] = $product_info['sort_order'];
    	} else {
			$data['sort_order'] = 1;
		}
		
		$this->load->model('localisation/stock_status');
		
		$data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();
		
		if (isset($this->request->post['status'])) {
      		$data['status'] = $this->request->post['status'];
    	} elseif (!empty($product_info)) {
			$data['status'] = $product_info['status'];
		} else {
      		$data['status'] = 1;
    	}
		
		if (isset($this->request->post['weight'])) {
      		$data['weight'] = $this->request->post['weight'];
		} elseif (!empty($product_info)) {
			$data['weight'] = $product_info['weight'];
    	} else {
      		$data['weight'] = '';
    	} 
		
		$this->load->model('localisation/weight_class');
		
		$data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();
    	
		if (isset($this->request->post['weight_class_id'])) {
      		$data['weight_class_id'] = $this->request->post['weight_class_id'];
    	} elseif (!empty($product_info)) {
      		$data['weight_class_id'] = $product_info['weight_class_id'];
		} else {
      		$data['weight_class_id'] = $this->config->get('config_weight_class_id');
    	}
		
		if (isset($this->request->post['length'])) {
      		$data['length'] = $this->request->post['length'];
    	} elseif (!empty($product_info)) {
			$data['length'] = $product_info['length'];
		} else {
      		$data['length'] = '';
    	}
		
		if (isset($this->request->post['width'])) {
      		$data['width'] = $this->request->post['width'];
		} elseif (!empty($product_info)) {	
			$data['width'] = $product_info['width'];
    	} else {
      		$data['width'] = '';
    	}
		
		if (isset($this->request->post['height'])) {
      		$data['height'] = $this->request->post['height'];
		} elseif (!empty($product_info)) {
			$data['height'] = $product_info['height'];
    	} else {
      		$data['height'] = '';
    	}
		
		$this->load->model('localisation/length_class');
		
		$data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();
    	
		if (isset($this->request->post['length_class_id'])) {
      		$data['length_class_id'] = $this->request->post['length_class_id'];
    	} elseif (!empty($product_info)) {
      		$data['length_class_id'] = $product_info['length_class_id'];
    	} else {
      		$data['length_class_id'] = $this->config->get('config_length_class_id');
		}
		
	if (VERSION > '1.5.4.1') {
		// Manufacturers
		$this->load->model('catalog/manufacturer');
		
    	if (isset($this->request->post['manufacturer_id'])) {
      		$data['manufacturer_id'] = $this->request->post['manufacturer_id'];
		} elseif (!empty($product_info)) {
			$data['manufacturer_id'] = $product_info['manufacturer_id'];
		} else {
      		$data['manufacturer_id'] = 0;
    	}
		
    	if (isset($this->request->post['manufacturer'])) {
      		$data['manufacturer'] = $this->request->post['manufacturer'];
		} elseif (!empty($product_info)) {
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);
			
			if ($manufacturer_info) {		
				$data['manufacturer'] = $manufacturer_info['name'];
			} else {
				$data['manufacturer'] = '';
			}	
		} else {
      		$data['manufacturer'] = '';
    	}
		
		// Categories
		$this->load->model('catalog/category');
		
		if (isset($this->request->post['product_category'])) {
			$categories = $this->request->post['product_category'];
		} elseif (isset($this->request->get['product_id'])) {		
			$categories = $this->model_catalog_product->getProductCategories($this->request->get['product_id']);
		} else {
			$categories = array();
		}
		
		$data['product_categories'] = array();
		
		foreach ($categories as $category_id) {
			$category_info = $this->model_catalog_category->getCategory($category_id);
			
			if ($category_info) {
				$data['product_categories'][] = array(
					'category_id' => $category_info['category_id'],
					'name'        => ($category_info['path'] ? $category_info['path'] . ' &gt; ' : '') . $category_info['name']
				);
			}
		}

		// Filters
		$this->load->model('catalog/filter');
		
		if (isset($this->request->post['product_filter'])) {
			$filters = $this->request->post['product_filter'];
		} elseif (isset($this->request->get['product_id'])) {
			$filters = $this->model_catalog_product->getProductFilters($this->request->get['product_id']);
		} else {
			$filters = array();
		}
		
		$data['product_filters'] = array();
		
		foreach ($filters as $filter_id) {
			$filter_info = $this->model_catalog_filter->getFilter($filter_id);
			
			if ($filter_info) {
				$data['product_filters'][] = array(
					'filter_id' => $filter_info['filter_id'],
					'name'      => $filter_info['group'] . ' &gt; ' . $filter_info['name']
				);
			}
		}	
		
		// Attributes
		$this->load->model('catalog/attribute');
		
		if (isset($this->request->post['product_attribute'])) {
			$product_attributes = $this->request->post['product_attribute'];
		} elseif (isset($this->request->get['product_id'])) {
			$product_attributes = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);
		} else {
			$product_attributes = array();
		}
		
		$data['product_attributes'] = array();
		
		foreach ($product_attributes as $product_attribute) {
			$attribute_info = $this->model_catalog_attribute->getAttribute($product_attribute['attribute_id']);
			
			if ($attribute_info) {
				$data['product_attributes'][] = array(
					'attribute_id'                  => $product_attribute['attribute_id'],
					'name'                          => $attribute_info['name'],
					'product_attribute_description' => $product_attribute['product_attribute_description']
				);
			}
		}
		
		// Downloads
		$this->load->model('catalog/download');
		
		if (isset($this->request->post['product_download'])) {
			$product_downloads = $this->request->post['product_download'];
		} elseif (isset($this->request->get['product_id'])) {
			$product_downloads = $this->model_catalog_product->getProductDownloads($this->request->get['product_id']);
		} else {
			$product_downloads = array();
		}
			
		$data['product_downloads'] = array();
		
		foreach ($product_downloads as $download_id) {
			$download_info = $this->model_catalog_download->getDownload($download_id);
			
			if ($download_info) {
				$data['product_downloads'][] = array(
					'download_id' => $download_info['download_id'],
					'name'        => $download_info['name']
				);
			}
		}
		
	} else {
		// Manufacturers
		$this->load->model('catalog/manufacturer');
		
    	$data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();

    	if (isset($this->request->post['manufacturer_id'])) {
      		$data['manufacturer_id'] = $this->request->post['manufacturer_id'];
		} elseif (!empty($product_info)) {
			$data['manufacturer_id'] = $product_info['manufacturer_id'];
		} else {
      		$data['manufacturer_id'] = 0;
    	}
		
		// Categories
		$this->load->model('catalog/category');
		
		$data['categories'] = $this->model_catalog_category->getCategories(0);
		
		if (isset($this->request->post['product_category'])) {
			$data['product_category'] = $this->request->post['product_category'];
		} elseif (isset($this->request->get['product_id'])) {
			$data['product_category'] = $this->model_catalog_product->getProductCategories($this->request->get['product_id']);
		} else {
			$data['product_category'] = array();
		}
		
		// Attributes
		if (isset($this->request->post['product_attribute'])) {
			$data['product_attributes'] = $this->request->post['product_attribute'];
		} elseif (isset($this->request->get['product_id'])) {
			$data['product_attributes'] = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);
		} else {
			$data['product_attributes'] = array();
		}
		
		// Downloads
		$this->load->model('catalog/download');
		
		$data['downloads'] = $this->model_catalog_download->getDownloads();
		
		if (isset($this->request->post['product_download'])) {
			$data['product_download'] = $this->request->post['product_download'];
		} elseif (isset($this->request->get['product_id'])) {
			$data['product_download'] = $this->model_catalog_product->getProductDownloads($this->request->get['product_id']);
		} else {
			$data['product_download'] = array();
		}
		
	} 
	// END VERSION
		
		
		// Options
		
		// Images
		if (isset($this->request->post['product_image'])) {
			$product_images = $this->request->post['product_image'];
		} elseif (isset($this->request->get['product_id'])) {
			$product_images = $this->model_catalog_product->getProductImages($this->request->get['product_id']);
		} else {
			$product_images = array();
		}
		
		$data['product_images'] = array();
		
		foreach ($product_images as $product_image) {
			if ($product_image['image'] && file_exists(DIR_IMAGE . $product_image['image'])) {
				$image = $product_image['image'];
			} else {
				$image = 'no_image.jpg';
			}
			
			$data['product_images'][] = array(
				'image'      => $image,
				'thumb'      => $this->model_tool_image->resize($image, 100, 100),
				'sort_order' => $product_image['sort_order']
			);
		}
		
		$data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		
		if (isset($this->request->post['product_related'])) {
			$products = $this->request->post['product_related'];
		} elseif (isset($this->request->get['product_id'])) {		
			$products = $this->model_catalog_product->getProductRelated($this->request->get['product_id']);
		} else {
			$products = array();
		}
	
		$data['product_related'] = array();
		
		foreach ($products as $product_id) {
			$related_info = $this->model_catalog_product->getProduct($product_id);
			
			if ($related_info) {
				$data['product_related'][] = array(
					'product_id' => $related_info['product_id'],
					'name'       => $related_info['name']
				);
			}
		}
		
		$data['entry_points'] = $this->language->get('entry_points');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_reward'] = $this->language->get('entry_reward');
		$data['tab_reward'] = $this->language->get('tab_reward');
		
		$this->load->model('sale/customer_group');
		$data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		
		if (isset($this->request->post['points'])) {
      		$data['points'] = $this->request->post['points'];
    	} elseif (!empty($product_info)) {
			$data['points'] = $product_info['points'];
		} else {
      		$data['points'] = '';
    	}
		if (isset($this->request->post['product_reward'])) {
			$data['product_reward'] = $this->request->post['product_reward'];
		} elseif (isset($this->request->get['product_id'])) {
			$data['product_reward'] = $this->model_catalog_product->getProductRewards($this->request->get['product_id']);
		} else {
			$data['product_reward'] = array();
		}
		
	// Product Grouped
		$data['pg_available_layouts'] = array();
		$template_files = glob(DIR_CATALOG . 'view/theme/default/template/product/product_configurable_*.tpl');
		foreach ($template_files as $template_file) {
			$template_name = basename($template_file, '.tpl');
				$part = explode('_', $template_name);
				$data['pg_available_layouts'][] = array(
					'pg_layout' => $part[2],
					'pg_label'  => $template_name . '.tpl'
				);
		}
		$data['entry_pg_layout'] = $this->language->get('entry_pg_layout');
		if (isset($this->request->post['pg_layout'])) {
			$data['pg_layout'] = $this->request->post['pg_layout'];
		} elseif (isset($this->request->get['product_id'])) {
			$data['pg_layout'] = $this->model_catalog_product_configurable->getProductGroupedTypeLayout($this->request->get['product_id']);
		} else {
			$data['pg_layout'] = 'default';
		}
		
		// Grouped Discount
		$data['entry_group_discount'] = $this->language->get('entry_group_discount');
		if (isset($this->request->get['product_id'])) {
			$group_discount = $this->model_catalog_product_configurable->getProductGroupedDiscount($this->request->get['product_id']);
		}
		if (isset($this->request->post['group_discount'])) {
			$data['group_discount'] = $this->request->post['group_discount'];
		} elseif (isset($this->request->get['product_id'])) {
			$data['group_discount'] = $group_discount['discount'];
		} else {
			$data['group_discount'] = '';
		}
		if (isset($this->request->post['group_discount_type'])) {
			$data['group_discount_type'] = $this->request->post['group_discount_type'];
		} elseif (isset($this->request->get['product_id'])) {
			$data['group_discount_type'] = $group_discount['type'];
		} else {
			$data['group_discount_type'] = '';
		}
		
		// S Product Grouped configurable
		if (isset($this->request->post['price_from'])) {
      		$data['price_from'] = $this->request->post['price_from'];
    	} elseif (!empty($product_info)) {
			$data['price_from'] = $product_info['pgprice_from'];
		} else {
      		$data['price_from'] = '';
    	}
		if (isset($this->request->post['price_to'])) {
      		$data['price_to'] = $this->request->post['price_to'];
    	} elseif (!empty($product_info)) {
			$data['price_to'] = $product_info['pgprice_to'];
		} else {
      		$data['price_to'] = '';
    	}
		if (isset($this->request->post['price_fixed'])) {
      		$data['price_fixed'] = $this->request->post['price_fixed'];
    	} elseif (!empty($product_info)) {
			$data['price_fixed'] = $product_info['price'];
		} else {
      		$data['price_fixed'] = '';
    	}
		
	// S nuovo
		$data['column_config_option_sort_order'] = $this->language->get('column_config_option_sort_order');
		$data['column_child_image'] = $this->language->get('column_child_image');
		$data['column_child_name'] = $this->language->get('column_child_name');
		$data['column_child_model'] = $this->language->get('column_child_model');
		$data['column_child_price'] = $this->language->get('column_child_price');
		$data['column_child_info'] = $this->language->get('column_child_info');
		$data['column_child_visibility'] = $this->language->get('column_child_visibility');
		$data['column_child_nocart'] = $this->language->get('column_child_nocart');
		$data['text_visible'] = $this->language->get('text_visible');
		$data['text_invisible_searchable'] = $this->language->get('text_invisible_searchable');
		$data['text_invisible'] = $this->language->get('text_invisible');
		$text_enabled = '<span style="color:green;">' . $this->language->get('text_enabled') . '</span>';
		$text_disabled = '<span style="color:red;">' . $this->language->get('text_disabled') . '</span>';

		// Grouped Products childs list
		if (isset($this->request->get['product_id'])) {
			$results = $this->model_catalog_product_configurable->getProductGroupedConfigurable($this->request->get['product_id']);
		} else {
			$results = array();
		}

		$data['option_configs'] = array();
		foreach ($results as $result) {
			$childs_data = array();
			$childs_id = explode(',', $result['child_id']);
			foreach ($childs_id as $child_id) if(!empty($child_id)) {
				$child_info = $this->model_catalog_product->getProduct($child_id);

				if ($child_info) {
					if ($child_info['image'] && file_exists(DIR_IMAGE . $child_info['image'])) {
						$child_info_image = $this->model_tool_image->resize($child_info['image'], 40, 40);
					} else {
						$child_info_image = $this->model_tool_image->resize('no_image.jpg', 40, 40);
					}

					$child_info_special = false;
					$child_specials = $this->model_catalog_product->getProductSpecials($child_info['product_id']);
					foreach ($child_specials as $child_special) {
						if (($child_special['date_start'] == '0000-00-00' || $child_special['date_start'] < date('Y-m-d')) && ($child_special['date_end'] == '0000-00-00' || $child_special['date_end'] > date('Y-m-d'))) {
							$child_info_special = $child_special['price'];
							break;
						}
					}

					if ($this->model_catalog_product->getProductOptions($child_info['product_id'])) {
						$child_info_have_options = $this->language->get('text_product_with_options');
					} else {
						$child_info_have_options = '';
					}

					if ($result['child_to_hide']) {
						$childs_to_hide = explode(';', $result['child_to_hide']);
						foreach ($childs_to_hide as $element) if(!empty($element)) {
							$element = explode(':', $element);
							$child_to_hide[$element[0]] = $element[1];
						}
					}

					$childs_data[] = array(
						'child_id'     => $child_info['product_id'],
						'image'        => $child_info_image,
						'name'         => $child_info['name'] . $child_info_have_options,
						'href'         => $this->url->link('catalog/product/update', 'token='. $this->session->data['token'] .'&product_id='. $child_info['product_id'], 'SSL'),
						'model'        => $child_info['model'],
						'price'        => $child_info['price'],
						'special'      => $child_info_special,
						'quantity'     => $child_info['quantity'],
						'subtract'     => $child_info['subtract'],
						'status'       => $child_info['status'] ? $text_enabled : $text_disabled,
						'pgvisibility' => $child_info['pgvisibility'],
						'grouped_stock_status_id' =>$this->model_catalog_product_configurable->getProductGroupedStockStatusId($this->request->get['product_id'],$child_id),
						'child_to_hide' => isset($child_to_hide[$child_id]) ? $child_to_hide[$child_id] : ''
					);
				}
			}
			
			$option_name_data[$result['language_id']] = array('option_name' => $result['option_name']);
			$data['option_configs'][$result['option_type']] = array(
				'option_type'       => $result['option_type'],
				'option_required'   => $result['option_required'],
				'option_sort_order' => $result['option_sort_order'],
				'option_min_qty'    => $result['option_min_qty'],
				'option_hide_qty'   => $result['option_hide_qty'],
				'option_name'       => $option_name_data,
				'childs'            => $childs_data
			);
		}
		// E nuovo
		
		$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
	
			$this->response->setOutput($this->load->view('catalog/product_form_configurable.tpl', $data));
			
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/product_configurable')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['product_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 2) || (utf8_strlen($value['name']) > 255)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}
		
		if (!isset($this->request->post['gpo'])) {
			$this->error['option_type'] = $this->language->get('error_option_type');
		} else {
			foreach ($this->request->post['gpo'] as $key => $value) {
				if (empty($value['option_type'])) {
					$this->error['option_type'] = $this->language->get('error_option_type');
				}
			}
		}
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
					
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>