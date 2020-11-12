<?php
class ControllerCatalogUnitConversion extends Controller {
	private $error = array();  
 
        public function __construct($registry){
            $this->registry = $registry;
            
            $this->language->load('catalog/unit_conversion');
            $this->document->setTitle($this->language->get('heading_title'));
            $data['heading_title'] = $this->language->get('heading_title');

            $this->load->model('catalog/unit_conversion');
        }
        
        public function index() {
            $this->getList();
	}
        
        protected function getList() {
            if (isset($this->request->get['sort'])) {
                    $sort = $this->request->get['sort'];
            } else {
                    $sort = 'u.name';
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

            if (isset($this->request->get['sort'])) {
                    $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                    $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                    $url .= '&page=' . $this->request->get['page'];
            }
            
            $url = '';
            $data['breadcrumbs'] = array();

            $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
            );

            $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('catalog/unit_conversion', 'token=' . $this->session->data['token'] . $url, 'SSL'),
            'separator' => ' :: '
            );
            
            $data['insert'] = $this->url->link('catalog/unit_conversion/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
            $data['delete'] = $this->url->link('catalog/unit_conversion/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
            
            
            
            $data['button_insert'] = $this->language->get('button_insert');
            $data['button_delete'] = $this->language->get('button_delete');
            $data['text_no_results'] = $this->language->get('text_no_results');
            $data['column_name'] = $this->language->get('column_name');
            $data['column_sort_order'] = $this->language->get('column_sort_order');
            $data['column_action'] = $this->language->get('column_action');
            $data['heading_title'] = $this->language->get('heading_title');
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

            $data['units'] = array();
            
            $data_filter = array(
                    'sort'  => $sort,
                    'order' => $order,
                    'start' => ($page - 1) * $this->config->get('config_admin_limit'),
                    'limit' => $this->config->get('config_admin_limit')
            );
            
            $unit_total = $this->model_catalog_unit_conversion->getTotalUnits();
            $results = $this->model_catalog_unit_conversion->getUnits($data_filter);
            
            foreach ($results as $result) {
                $action = array();

                $action[] = array(
                        'text' => $this->language->get('text_edit'),
                        'href' => $this->url->link('catalog/unit_conversion/update', 'token=' . $this->session->data['token'] . '&unit_id=' . $result['unit_id'] . $url, 'SSL')
                );

                $data['units'][] = array(
                        'unit_id'  => $result['unit_id'],
                        'name'       => $result['name'],
                        'sort_order' => $result['sort_order'],
                        'selected'   => isset($this->request->post['selected']) && in_array($result['unit_id'], $this->request->post['selected']),
                        'action'     => $action
                );
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

            $data['sort_name'] = $this->url->link('catalog/unit_conversion', 'token=' . $this->session->data['token'] . '&sort=od.name' . $url, 'SSL');
            $data['sort_sort_order'] = $this->url->link('catalog/unit_conversion', 'token=' . $this->session->data['token'] . '&sort=o.sort_order' . $url, 'SSL');

            $url = '';

            if (isset($this->request->get['sort'])) {
                    $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                    $url .= '&order=' . $this->request->get['order'];
            }
            
            $pagination = new Pagination();
            $pagination->total = $unit_total;
            $pagination->page = $page;
            $pagination->limit = $this->config->get('config_admin_limit');
            $pagination->text = $this->language->get('text_pagination');
            $pagination->url = $this->url->link('catalog/unit_conversion', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

            $data['pagination'] = $pagination->render();
            
            $data['sort'] = $sort;
            $data['order'] = $order;
            
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
	
			$this->response->setOutput($this->load->view('catalog/unit_conversion_list.tpl', $data));
	}
        
        public function insert() {
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
               $this->model_catalog_unit_conversion->addUnit($this->request->post);
                        
				$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->response->redirect($this->url->link('catalog/unit_conversion', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}
        
        protected function getForm() {
            
            $data['button_save'] = $this->language->get('button_save');
            $data['button_cancel'] = $this->language->get('button_cancel');
            $data['button_add_option_value'] = $this->language->get('button_add_option_value');
            $data['button_remove'] = $this->language->get('button_remove');
			$data['heading_title'] = $this->language->get('heading_title');
            if (isset($this->error['warning'])) {
                    $data['error_warning'] = $this->error['warning'];
            } else {
                    $data['error_warning'] = '';
            }

            $url = '';

            $data['entry_name'] = $this->language->get('entry_name');
            $data['entry_sort_order'] = $this->language->get('entry_sort_order');
            $data['entry_unit_value'] = $this->language->get('entry_unit_value');
            //$data['entry_convert_price'] = $this->language->get('entry_convert_price');
            $data['add_unit_value'] = $this->language->get('add_unit_value');
            
            $data['breadcrumbs'] = array();

            $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
            );

            $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('catalog/unit_conversion', 'token=' . $this->session->data['token'] . $url, 'SSL'),
            'separator' => ' :: '
            );

            if (!isset($this->request->get['unit_id'])) {
                    $data['action'] = $this->url->link('catalog/unit_conversion/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
            } else { 
                    $data['action'] = $this->url->link('catalog/unit_conversion/update', 'token=' . $this->session->data['token'] . '&unit_id=' . $this->request->get['unit_id'] . $url, 'SSL');
            }

            $data['cancel'] = $this->url->link('catalog/unit_conversion', 'token=' . $this->session->data['token'] . $url, 'SSL');

            if (isset($this->request->post['unit_value'])) {
                    $unit_values = $this->request->post['unit_value'];
            } elseif (isset($this->request->get['unit_id'])) {
                    $unit_values = $this->model_catalog_unit_conversion->getUnitValueDescriptions($this->request->get['unit_id']);
                    $unit_data = $this->model_catalog_unit_conversion->getUnitDataDescriptions($this->request->get['unit_id']);
            } else {
                    $unit_values = array();
            }

            $data['unit_values'] = array();
            foreach ($unit_values as $unit_value) {
                $data['unit_values'][] = array(
                    'unit_value_id'          => $unit_value['unit_value_id'],
                    'unit_value_name'        => $unit_value['name'],
                    //'convert_price'          => $unit_value['convert_price'],
                    'sort_order'             => $unit_value['sort_order'],
                );
            }
            
            if(isset($unit_data)){
                $data['unit_name'] = $unit_data[0]['unit_name'];
                $data['unit_sortorder'] = $unit_data[0]['unit_sortorder'];
            }
            
            
            $data['token'] = $this->session->data['token'];

            $this->load->model('localisation/language');
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
	
			$this->response->setOutput($this->load->view('catalog/unit_conversion_form.tpl', $data));
            
        }
        
        public function delete() {
            if (isset($this->request->post['selected']) && $this->validateDelete()) {
                    foreach ($this->request->post['selected'] as $unit_id) {
                            $this->model_catalog_unit_conversion->deleteUnit($unit_id);
                    }

                    $this->session->data['success'] = $this->language->get('text_success');

                    $url = '';

                    if (isset($this->request->get['sort'])) {
                            $url .= '&sort=' . $this->request->get['sort'];
                    }

                    if (isset($this->request->get['order'])) {
                            $url .= '&order=' . $this->request->get['order'];
                    }

                    if (isset($this->request->get['page'])) {
                            $url .= '&page=' . $this->request->get['page'];
                    }

                   $this->response->redirect($this->url->link('catalog/unit_conversion', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            $this->getList();
	}
        
        public function update() {
            if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
                    $this->model_catalog_unit_conversion->editUnit($this->request->get['unit_id'], $this->request->post);

                    $this->session->data['success'] = $this->language->get('text_success');

                    $url = '';

                    if (isset($this->request->get['sort'])) {
                            $url .= '&sort=' . $this->request->get['sort'];
                    }

                    if (isset($this->request->get['order'])) {
                            $url .= '&order=' . $this->request->get['order'];
                    }

                    if (isset($this->request->get['page'])) {
                            $url .= '&page=' . $this->request->get['page'];
                    }

                    $this->response->redirect($this->url->link('catalog/unit_conversion', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            $this->getForm();
	}
        
        protected function validateDelete() {
            if (!$this->user->hasPermission('modify', 'catalog/unit_conversion')) {
                    $this->error['warning'] = $this->language->get('error_permission');
            }

//            $this->load->model('catalog/product');
//
//            foreach ($this->request->post['selected'] as $option_id) {
//                    $product_total = $this->model_catalog_product->getTotalProductsByOptionId($option_id);
//
//                    if ($product_total) {
//                            $this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);
//                    }
//            }

            if (!$this->error) {
                    return true;
            } else {
                    return false;
            }
	}
        
        protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/unit_conversion')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
        
        public function autocomplete() {
            $json = array();

            if (isset($this->request->get['filter_name'])) {
                    $this->load->model('tool/image');

                    $data = array(
                            'filter_name' => $this->request->get['filter_name'],
                            'start'       => 0,
                            'limit'       => 20
                    );

                    $units = $this->model_catalog_unit_conversion->getUnits($data);

                    foreach ($units as $unit) {
                        $unit_value_data = array();

                        $unit_values = $this->model_catalog_unit_conversion->getUnitValueDescriptions($unit['unit_id']);

                        foreach ($unit_values as $unit_value) {
                            $unit_value_data[] = array(
                                    'unit_value_id'   => $unit_value['unit_value_id'],
                                    'name'            => html_entity_decode($unit_value['name'], ENT_QUOTES, 'UTF-8'),
                            );
                        }

                        $sort_order = array();

                        foreach ($unit_value_data as $key => $value) {
                                $sort_order[$key] = $value['name'];
                        }

                        array_multisort($sort_order, SORT_ASC, $unit_value_data);					

                       

                        $json[] = array(
                            'unit_id'    => $unit['unit_id'],
                            'name'         => strip_tags(html_entity_decode($unit['name'], ENT_QUOTES, 'UTF-8')),
                            'unit_value' => $unit_value_data
                        );
                    }
            }

            $sort_order = array();

            foreach ($json as $key => $value) {
                    $sort_order[$key] = $value['name'];
            }

            array_multisort($sort_order, SORT_ASC, $json);

            $this->response->setOutput(json_encode($json));
	}
}
?>