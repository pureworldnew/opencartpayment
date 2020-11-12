<?php 
/* 
  #file: admin/controller/catalog/product_list_gp.php
  #switched: v1.5.4.1 - v1.5.5.1 - v1.5.6
*/

class ControllerCatalogProductListGp extends Controller {
	private $error = array();
	
	public function getGpVersion(){
		$data['modid'] = 'Grouped Product v5.1 - Ultimate';
	}
	
	public function index() {
		if (file_exists('model/catalog/product_grouped_dbt.php') || file_exists('model/catalog/product_grouped_dbt_configurable.php')) {
			$this->response->redirect($this->url->link('extension/product_grouped', 'token=' . $this->session->data['token'] . '&_ms=1', 'SSL'));
		} else {
			$this->load->language('catalog/product');
			$this->load->language('catalog/product_list_gp');
			$this->document->setTitle($this->language->get('heading_title'));
			$this->load->model('catalog/product_list_gp');
			$this->getList();
		}
	}
	
	public function delete() { 
		$this->load->language('catalog/product');
		$this->load->language('catalog/product_list_gp');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/product_list_gp');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $product_id) {
				$this->model_catalog_product_list_gp->deleteProductFromProductConcatTemp($product_id);
			}

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
			
			$this->response->redirect($this->url->link('catalog/product_list_gp', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}
	
	public function copy() {
    	$this->language->load('catalog/product');
    	$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/product_list_gp');
		
		if (isset($this->request->post['selected']) && $this->validateCopy()) {
			foreach ($this->request->post['selected'] as $product_id) {
				$this->model_catalog_product_list_gp->copyProduct($product_id);
	  		}

			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}
			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
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
			
			$this->response->redirect($this->url->link('catalog/product_list_gp', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

    	$this->getList();
  	}
	
	protected function getList() {
		$this->getGpVersion();
		
		if (isset($this->request->get['filter_model'])) {
			$filter_model = $this->request->get['filter_model'];
		} else {
			$filter_model = null;
		}
		
		if (isset($this->request->get['filter_type'])) {
			$filter_type = $this->request->get['filter_type'];
		} else {
			$filter_type = null;
		}
		
		
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}
		
		if (isset($this->request->get['filter_price'])) {
			$filter_price = $this->request->get['filter_price'];
		} else {
			$filter_price = null;
		}
		
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
		
		if (isset($this->request->get['filter_indicator'])) {
			$filter_indicator = $this->request->get['filter_indicator'];
		} else {
			$filter_indicator = null;
		}
		
			if (isset($this->request->get['filter_indicator_id'])) {
			$filter_indicator_id = $this->request->get['filter_indicator_id'];
		} else {
			$filter_indicator_id = null;
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
		if (isset($this->request->get['filter_indicator_id'])) {
			$url .= '&filter_indicator_id=' . $this->request->get['filter_indicator_id'];
		}
		
		if (isset($this->request->get['filter_indicator'])) {
			$url .= '&filter_indicator=' . $this->request->get['filter_indicator'];
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
		
		
		$data['gplist'] = $this->url->link('catalog/product_list_gp', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$gpfiles = glob(DIR_APPLICATION . 'controller/catalog/product_*.php');
		foreach ($gpfiles as $gpfile) {
			$thefile = basename($gpfile, '.php');
			if ($thefile != 'product_list_gp') {
				$data['inserts'][] = array(
					'href'  => $this->url->link('catalog/' . $thefile . '/insert', 'token=' . $this->session->data['token'], 'SSL'),
					'label' => ucwords(str_replace('product_', '', $thefile))
				);
			}
		}
		$data['copy'] = $this->url->link('catalog/product_list_gp/copy', 'token=' . $this->session->data['token'] . $url, 'SSL');	
		$data['delete'] = $this->url->link('catalog/product_list_gp/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['insert'] = $this->url->link('catalog/product_grouped/insert', 'token=' . $this->session->data['token'], 'SSL');
		
		
		
		$data['products'] = array();
		$text_enabled = '<span style="color:green;">' . $this->language->get('text_enabled') . '</span>';
		$text_disabled = '<span style="color:red;">' . $this->language->get('text_disabled') . '</span>';
		
		$data_filter = array( 
			'filter_sku'     => $filter_model,
			'filter_indicator'     => $filter_indicator,
			'filter_indicator_id'     => $filter_indicator_id,
			'filter_name'	  => $filter_name, 
			'filter_status'   => $filter_status,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		);
		
		$this->load->model('tool/image');
		
		$product_total = $this->model_catalog_product_list_gp->getTotalProducts($data_filter);
		
		$results = $this->model_catalog_product_list_gp->getProducts($data_filter);

		foreach ($results as $result) {  
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/product_grouped/update', 'token=' . $this->session->data['token'] . '&id=' . $result['id'] . $url, 'SSL')
			);
			
			if ($result['image'] && is_file(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 40, 40);
			}
			
			$special = false;
			
			$product_specials = $this->model_catalog_product_list_gp->getProductSpecials($result['product_id']);
			
			foreach ($product_specials  as $product_special) {
				if (($product_special['date_start'] == '0000-00-00' || $product_special['date_start'] < date('Y-m-d')) && ($product_special['date_end'] == '0000-00-00' || $product_special['date_end'] > date('Y-m-d'))) {
					$special = $product_special['price'];
			
					break;
				}					
			}
			
			$data['products'][] = array(
				'id'			=> $result['id'],
				'product_id'    => $result['product_id'],
				'image'         => $image,
				'name'          => $result['name'],
				'sku'          => $result['sku'],
				'groupindicator'         => $result['groupindicator'],
				'groupindicator_id'         => $result['groupindicator_id'],
				'price'         => $result['price'],
				'status'        => $result['status'] ? $text_enabled : $text_disabled,
				'selected'      => isset($this->request->post['selected']) && in_array($result['product_id'], $this->request->post['selected']),
				'action'        => $action,
				'special'       => $special,
				'price_from'    => $result['pgprice_from'],
				'price_to'      => $result['pgprice_to'],
				'total_grouped' => $this->model_catalog_product_list_gp->getTotalGroupedByProductId($result['product_id']),
				'pg_type'       => $result['pg_type'],
				'pg_layout'     => $result['pg_layout']
			);
		}
		
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['column_image'] = $this->language->get('column_image');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_product_type'] = $this->language->get('column_product_type');
		$data['column_product_total_grouped'] = $this->language->get('column_product_total_grouped');
		$data['column_action'] = $this->language->get('column_action');
		
		$data['text_price_start'] = $this->language->get('text_price_start');
		$data['text_price_from'] = $this->language->get('text_price_from');
		$data['text_price_to'] = $this->language->get('text_price_to');
		$data['text_price_fixed'] = $this->language->get('text_price_fixed');
		$data['text_no_results'] = $this->language->get('text_no_results');
		
		$data['button_setting'] = $this->language->get('button_setting');
		$data['button_setting_href'] = $this->url->link('extension/product_grouped', 'token=' . $this->session->data['token'], 'SSL');
		$data['button_copy'] = $this->language->get('button_copy');
		$data['button_insert'] = $this->language->get('button_insert');
		$data['button_delete'] = $this->language->get('button_delete');
 		
		$data['text_enabled'] = $this->language->get('text_enabled');		
		$data['text_disabled'] = $this->language->get('text_disabled');	
		
		$data['token'] = $this->session->data['token'];
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
		
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/product_list_gp', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$data['pagination'] = $pagination->render();
		
		$data['filter_type'] = $filter_type;
		$data['filter_model'] = $filter_model;
		
		$data['filter_name'] = $filter_name;
		$data['filter_price'] = $filter_price;
		$data['filter_status'] = $filter_status;
		
		$data['filter_indicator'] = $filter_indicator;
		$data['filter_indicator_id'] = $filter_indicator_id;
		
		$data['sort'] = $sort;
		$data['order'] = $order;
		
		
		$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
	
			$this->response->setOutput($this->load->view('catalog/product_list_gp.tpl', $data));
		
		
	}
	
	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/product_list_gp')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
 
		if (!$this->error) {
			return true; 
		} else {
			return false;
		}
	}
	
	protected function validateCopy() {
    	if (!$this->user->hasPermission('modify', 'catalog/product_list_gp')) {
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