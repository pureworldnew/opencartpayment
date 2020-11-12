<?php 
/* 
  #file: admin/controller/catalog/product_grouped.php
  #powered by fabiom7 - www.fabiom7.com - fabiome77@hotmail.it - copyright fabiom7 2012 - 2013 - 2014
  #switched: v1.5.4.1 - v1.5.5.1 - v1.5.6
*/

class ControllerCatalogProductGrouped extends Controller { 
	private $error = array();
	
	public function insert() {
		$this->load->language('catalog/product');
		
		$this->load->language('catalog/product_grouped');
		$this->load->model('catalog/product_grouped');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/product');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$product_id = $this->model_catalog_product_grouped->addProduct_frmcatalog($this->request->post);

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
				$this->response->redirect($this->url->link('catalog/product_grouped/update', 'token=' . $this->session->data['token'] . '&product_id=' . $product_id . $url, 'SSL'));
			} else {
				$this->response->redirect($this->url->link('catalog/product_list_gp', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}
		
		$this->getForm();
	}

	public function update() {
		$this->load->language('catalog/product');
		
		$this->load->language('catalog/product_grouped');
		$this->load->model('catalog/product_grouped');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/product');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$id = $this->request->get['id'];
			
			$this->model_catalog_product_grouped->editProduct_frmcatalog($this->request->post);
			
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
				$this->response->redirect($this->url->link('catalog/product_grouped/update', 'token=' . $this->session->data['token'] . '&id=' . $id . $url, 'SSL'));
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
		
		////
		$data['tab_grouped'] = $this->language->get('tab_grouped');
		$data['tab_system_identifier'] = $this->language->get('tab_system_identifier');
		
		$data['column_maximum'] = $this->language->get('column_maximum');
		$data['column_info'] = $this->language->get('column_info');
		$data['column_visibility'] = $this->language->get('column_visibility');
		$data['column_product_sort_order'] = $this->language->get('column_product_sort_order');
		$data['column_product_nocart'] = $this->language->get('column_product_nocart');
		
		$data['text_visible'] = $this->language->get('text_visible');
		$data['text_invisible_searchable'] = $this->language->get('text_invisible_searchable');
		$data['text_invisible'] = $this->language->get('text_invisible');
		$data['text_auto_identifier_system'] = $this->language->get('text_auto_identifier_system');
		
		$data['button_save_continue'] = $this->language->get('button_save_continue');
		
		$data['entry_price'] = $this->language->get('entry_price');
		$data['entry_tax_class'] = $this->language->get('entry_tax_class');
		
 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error)) {
			$data['errors'] = $this->error;
		} else {
			$data['errors'] = '';
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
		
		if (!isset($this->request->get['id'])) {
			$data['action'] = $this->url->link('catalog/product_grouped/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('catalog/product_grouped/update', 'token=' . $this->session->data['token'] . '&id=' . $this->request->get['id'] . $url, 'SSL');
		}
		
		$data['cancel'] = $this->url->link('catalog/product_list_gp', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['product_info'] = array();
		if (isset($this->request->get['id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$data['product_info'] = $this->model_catalog_product->getGroupProduct($this->request->get['id']);
    	}
		
		
		$data['token'] = $this->session->data['token'];
		
		if (isset($this->request->get['id'])) {
      		$data['id'] = $this->request->get['id'];
    	}else{
			$data['id'] = '';//$this->request->get['id'];
		}
		
		
		if (isset($this->request->post['sort_order'])) {
      		$data['sort_order'] = $this->request->post['sort_order'];
    	} elseif (!empty($product_info)) {
      		$data['sort_order'] = $product_info['sort_order'];
    	} else {
			$data['sort_order'] = 1;
		}
		
		
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
	
		$this->response->setOutput($this->load->view('catalog/product_form_grouped.tpl', $data));
		
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/product_grouped')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 255)) {
				$this->error['name'] = "Product Name must be between 3 and 255 characters!";
			}
			
		if ((utf8_strlen($this->request->post['sku']) < 1) || (utf8_strlen($this->request->post['sku']) > 64)) {
			$this->error['sku'] = $this->language->get('error_model');
		}
		
		if ( empty($this->request->post['product_id']) || !filter_var($this->request->post['product_id'], FILTER_VALIDATE_INT)) {
			$this->error['product_id'] = "Product ID must not be empty and it must be integer.";
		}
		
		if ($this->error && !isset($this->error['warning'])) {
			//$this->error['warning'] = $this->language->get('error_warning');
			$this->error = array('warning' => $this->language->get('error_warning')) + $this->error;
		}
		
		//echo "<pre>"; print_r($this->error); echo "</pre>"; exit;
		
		return !$this->error;
					
		/*if (!$this->error) {
			return true;
		} else {
			return false;
		}*/
	}
}
?>