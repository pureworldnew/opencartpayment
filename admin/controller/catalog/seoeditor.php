<?php 

/*******************************************************************************
*                                 Opencart SEO Pack                            *
*                             © Copyright Ovidiu Fechete                       *
*                              email: ovife21@gmail.com                        *
*                Below source-code or any part of the source-code              *
*                          cannot be resold or distributed.                    *
*******************************************************************************/


class ControllerCatalogSeoEditor extends Controller {
	private $error = array(); 
     
  	public function index() {
		$this->load->language('catalog/seoeditor');
                if (!isset($this->session->data['language_id'])) {
			$this->session->data['language_id'] = $this->config->get('config_language_id');
		}
    	
		$this->document->setTitle($this->language->get('heading_title')); 
		
		$this->load->model('catalog/seoeditor');
		
		$this->getList();
  	}
  
  		
  	private function getList() {				
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_type'])) {
			$filter_type = $this->request->get['filter_type'];
		} else {
			$filter_type = null;
		}
		
		if (isset($this->request->get['filter_keyword'])) {
			$filter_keyword = $this->request->get['filter_keyword'];
		} else {
			$filter_keyword = null;
		}

		if (isset($this->request->get['filter_meta_description'])) {
			$filter_meta_description = $this->request->get['filter_meta_description'];
		} else {
			$filter_meta_description = null;
		}

		if (isset($this->request->get['filter_meta_keyword'])) {
			$filter_meta_keyword = $this->request->get['filter_meta_keyword'];
		} else {
			$filter_meta_keyword = null;
		}
		
		if (isset($this->request->get['filter_tags'])) {
			$filter_tags = $this->request->get['filter_tags'];
		} else {
			$filter_tags = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'type desc, name';
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
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_type'])) {
			$url .= '&filter_type=' . $this->request->get['filter_type'];
		}
		
		if (isset($this->request->get['filter_meta_description'])) {
			$url .= '&filter_meta_description=' . $this->request->get['filter_meta_description'];
		}
		
		if (isset($this->request->get['filter_meta_keyword'])) {
			$url .= '&filter_meta_keyword=' . $this->request->get['filter_meta_keyword'];
		}		

		if (isset($this->request->get['filter_tags'])) {
			$url .= '&filter_tags=' . $this->request->get['filter_tags'];
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
			'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/seoeditor', 'token=' . $this->session->data['token'] . $url, 'SSL'),       		
      		'separator' => ' :: '
   		);
		
		
		$data['products'] = array();

		$fdata = array(
			'filter_type'	  => $filter_type, 
			'filter_name'	  => $filter_name, 
			'filter_keyword'  => $filter_keyword,
			'filter_meta_description'	  => $filter_meta_description,
			'filter_meta_keyword' => $filter_meta_keyword,
			'filter_tags'   => $filter_tags,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		);
		
		
		$product_total = $this->model_catalog_seoeditor->getTotalSEOs($fdata);
			
		$results = $this->model_catalog_seoeditor->getSEOs($fdata);
						    	
		foreach ($results as $result) {
			$action = array();
			
			
			
	
      		$data['products'][] = array(

				'id' => $result['id'],
				'name'       => $result['name'],
				'type'      => $result['type'],
				'keyword'      => $result['keyword'],
				'meta_keyword'      => $result['meta_keyword'],
				'meta_description'      => $result['meta_description'],
				'tags'      => $result['tags']
				
			);
    	}
		
                $this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		$data['heading_title'] = $this->language->get('heading_title');		
				
		$data['text_enabled'] = $this->language->get('text_enabled');		
		$data['text_disabled'] = $this->language->get('text_disabled');		
		$data['text_no_results'] = $this->language->get('text_no_results');		
		$data['text_image_manager'] = $this->language->get('text_image_manager');		
			
		$data['column_type'] = $this->language->get('column_type');		
		$data['column_name'] = $this->language->get('column_name');		
		$data['column_keyword'] = $this->language->get('column_keyword');		
		$data['column_meta_keyword'] = $this->language->get('column_meta_keyword');		
		$data['column_meta_description'] = $this->language->get('column_meta_description');		
		$data['column_tags'] = $this->language->get('column_tags');		
				
		$data['button_copy'] = $this->language->get('button_copy');		
		$data['button_insert'] = $this->language->get('button_insert');		
		$data['button_delete'] = $this->language->get('button_delete');		
		$data['button_filter'] = $this->language->get('button_filter');
		 
                $data['action'] = $this->url->link('catalog/seoeditor/changeLanguage&token=', 'token=' . $this->session->data['token'].'&lang=', 'SSL');
		
		if (!isset($this->session->data['language_id'])) {
			$this->session->data['language_id'] = $this->config->get('config_language_id');
		}
		
		foreach ($data['languages'] as $language) {
			if ($language['language_id'] == $this->session->data['language_id']) 
			{
				$data['selected_language'] = $language['name']; 
				$data['selected_language_id'] = $language['language_id'];
			}
		}
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

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_keyword'])) {
			$url .= '&filter_keyword=' . $this->request->get['filter_keyword'];
		}
		
		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . $this->request->get['filter_model'];
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
								
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
					
		$data['sort_name'] = $this->url->link('catalog/seoeditor', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
		$data['sort_type'] = $this->url->link('catalog/seoeditor', 'token=' . $this->session->data['token'] . '&sort=type' . $url, 'SSL');
		$data['sort_keyword'] = $this->url->link('catalog/seoeditor', 'token=' . $this->session->data['token'] . '&sort=keyword' . $url, 'SSL');
		$data['sort_meta_keyword'] = $this->url->link('catalog/seoeditor', 'token=' . $this->session->data['token'] . '&sort=meta_keyword' . $url, 'SSL');
		$data['sort_meta_description'] = $this->url->link('catalog/seoeditor', 'token=' . $this->session->data['token'] . '&sort=meta_description' . $url, 'SSL');
		$data['sort_tags'] = $this->url->link('catalog/seoeditor', 'token=' . $this->session->data['token'] . '&sort=tags' . $url, 'SSL');
		
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_type'])) {
			$url .= '&filter_type=' . $this->request->get['filter_type'];
		}
		
		if (isset($this->request->get['filter_keyword'])) {
			$url .= '&filter_keyword=' . $this->request->get['filter_keyword'];
		}
		
		if (isset($this->request->get['filter_meta_keyword'])) {
			$url .= '&filter_meta_keyword=' . $this->request->get['filter_meta_keyword'];
		}
		
		if (isset($this->request->get['filter_meta_description'])) {
			$url .= '&filter_meta_description=' . $this->request->get['filter_meta_description'];
		}
		
		if (isset($this->request->get['filter_tags'])) {
			$url .= '&filter_tags=' . $this->request->get['filter_tags'];
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
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/seoeditor', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$data['pagination'] = $pagination->render();
	
		$data['filter_name'] = $filter_name;
		$data['filter_type'] = $filter_type;
		$data['filter_keyword'] = $filter_keyword;
		$data['filter_meta_keyword'] = $filter_meta_keyword;
		$data['filter_meta_description'] = $filter_meta_description;
		$data['filter_tags'] = $filter_tags;
		
		$data['sort'] = $sort;
		$data['order'] = $order;

				
				$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/seoeditor.tpl', $data));
  	}

		
	public function autocomplete() {
		$json = array();
		
		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_keyword']) || isset($this->request->get['filter_meta_keyword']) || isset($this->request->get['filter_meta_description']) || isset($this->request->get['filter_tags']) || isset($this->request->get['filter_category_id'])) {
			$this->load->model('catalog/seoeditor');
			
			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}
			
			if (isset($this->request->get['filter_keyword'])) {
				$filter_keyword = $this->request->get['filter_keyword'];
			} else {
				$filter_keyword = '';
			}
			
			if (isset($this->request->get['filter_meta_keyword'])) {
				$filter_meta_keyword = $this->request->get['filter_meta_keyword'];
			} else {
				$filter_meta_keyword = '';
			}
			
			if (isset($this->request->get['filter_meta_description'])) {
				$filter_meta_description = $this->request->get['filter_meta_description'];
			} else {
				$filter_meta_description = '';
			}
			
			if (isset($this->request->get['filter_tags'])) {
				$filter_tags = $this->request->get['filter_tags'];
			} else {
				$filter_tags = '';
			}
			
			
									
			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];	
			} else {
				$limit = 20;	
			}			
						
			$fdata = array(
				'filter_name'         => $filter_name,
				'filter_keyword'      => $filter_keyword,
				'filter_meta_keyword'      => $filter_meta_keyword,
				'filter_meta_description'      => $filter_meta_description,
				'filter_tags'      => $filter_tags,
				
				'start'               => 0,
				'limit'               => $limit
			);
			
			$results = $this->model_catalog_seoeditor->getSEOs($fdata);
			
			foreach ($results as $result) {
				
				
				$json[] = array(
					'id' => $result['id'],
					'name'       => html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'),	
					'keyword'      => html_entity_decode($result['keyword'], ENT_QUOTES, 'UTF-8'),
					'meta_keyword'      => html_entity_decode($result['meta_keyword'], ENT_QUOTES, 'UTF-8'),
					'meta_description'      => html_entity_decode($result['meta_description'], ENT_QUOTES, 'UTF-8'),
					'tags'      => html_entity_decode($result['tags'], ENT_QUOTES, 'UTF-8')
					
				);	
			}
		}

		$this->response->setOutput(json_encode($json));
	}
    
	public function changeLanguage()
    {
        $this->session->data['language_id'] = $this->request->get['lang']; 
		$this->session->data['token'] = $this->request->get['token'];        
        $this->response->redirect($this->url->link('catalog/seoeditor', 'token=' . $this->session->data['token'], 'SSL'));
    }
	
	
	
}
?>