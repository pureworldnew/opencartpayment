<?php

class ControllerFeedCategoryDiscount extends Controller {

    private $error = array();

    public function index() {
         $this->language->load('feed/category_discount');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (empty($params)) {
		
			$params = array(
				
				'products_from_categories'    => 'all',
				'products_from_manufacturers' => 'all',
				'category_ids'        => array(),
				'manufacturer_ids'    => array()	
			);
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

            $params['products_from_categories']    = $this->request->post['products_from_categories'];			
            $params['products_from_manufacturers'] = $this->request->post['products_from_manufacturers'];
            
            if ($params['products_from_categories'] == 'selected' || $params['products_from_categories'] == 'selected_sub') {
				if (empty($this->request->post['category_ids'])) {
					$msg = "Please specify what products you want to export.";
				} else {
					$params['category_ids'] = $this->request->post['category_ids'];
				}
			}

			if ($params['products_from_manufacturers'] == 'selected') {
				if (empty($this->request->post['manufacturer_ids'])) {
					$msg = "Please specify what products you want to export.";
				} else {
					$params['manufacturer_ids'] = $this->request->post['manufacturer_ids'];
				}
			}
            
            $this->model_setting_setting->editSetting('category_discount', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');
            
            $this->response->redirect($this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $data['params']    =  $params;
        $this->load->model('feed/category_discount');
        $data['category_list'] = $this->model_feed_category_discount->selectCategory();
        $data['cust_group_list'] = $this->model_feed_category_discount->selectCustomeGroup();
        $data['manufacture_list'] = $this->model_feed_category_discount->selectManufacture();

        $this->load->model('catalog/category');		
        $data['categories'] = $this->model_catalog_category->getCategoriesExport(0);
        
		$this->load->model('catalog/manufacturer');		
		$data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();

        $data['heading_title'] = $this->language->get('heading_title');
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_or'] = $this->language->get('text_or');
        $data['text_select_cate'] = $this->language->get('text_select_cate');
        $data['text_select_cust'] = $this->language->get('text_select_cust');
        $data['text_select_manu'] = $this->language->get('text_select_manu');
        $data['button_export'] = $this->language->get('button_export');


        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_feed'),
            'href' => $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('feed/category_discount', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['action'] = $this->url->link('feed/category_discount', 'token=' . $this->session->data['token'], 'SSL');
        $data['action_export'] = $this->url->link('feed/category_discount/updateDisocunts', 'token=' . $this->session->data['token'], 'SSL');
        $data['cancel'] = $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL');

        $data['export_link'] = $this->url->link('feed/category_discount/export', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->post['category_discount_status'])) {
            $data['category_discount_status'] = $this->request->post['category_discount_status'];
        } else {
            $data['category_discount_status'] = $this->config->get('category_discount_status');
        }

        $data['data_feed'] = HTTP_CATALOG . 'index.php?route=feed/category_discount';

        if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
        }
        
        $data['discount_update'] = $this->url->link('catalog/marketprice/updateDiscountRates','token=' . $this->session->data['token'], 'SSL');
        $data['button_discount_update'] = 'Discount rates update';

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('feed/category_discount_new.tpl', $data));
        
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'feed/category_discount')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    public function updateDisocunts() {
        $this->load->model('feed/category_discount');
        $this->model_feed_category_discount->updateCategoriesDiscounts($_POST);
        $this->session->data['success'] = "Discount updated successfully.";
        $this->response->redirect($this->url->link('feed/category_discount', 'token=' . $this->session->data['token'], 'SSL'));
    }

    public function updateDisocuntsBackup() {
        $this->load->model('feed/category_discount');
        $category_id = $_POST['select_category'];
        $cust_group = $_POST['select_cust_group'];
        $status = 0;
        if( !empty( $_POST['product_discount'] ) )
        {
             $status = 1;
             foreach ($_POST['product_discount'] as $post){
                 if( empty($post['quantity']) || empty($post['price']) )
                 {
                     $status = 0;
                     break;
                 } 
             }
         }
        if($status === 1){
             if($category_id > 1 && $cust_group >= 1){
                  $this->model_feed_category_discount->updateCatDiscounts($category_id,$_POST['product_discount'],$cust_group);
             }else{
                 echo "Please select Category and customer group.";
             }
        } else {
            echo "Please enter Discount and Quantity.";
        }
     }
}

?>