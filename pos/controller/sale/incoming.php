<?php
class ControllerSaleIncoming extends Controller {
	private $error = array();
	public function index() {
		$this->load->language('sale/incoming');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('sale/incoming');
		$this->getList();
	}
	public function add() {
		$this->load->language('sale/incoming');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('sale/incoming');
		$this->getForm();
	}
	public function edit() {
		$this->load->language('sale/incoming');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('sale/incoming');
		$this->getForm();
	}
	public function getAjaxAdditionalCost(){
		$json=array();
		if(isset($this->request->get['order_id'])){
		$additional_cost=$this->db->query("SELECT currency_code,currency_value,additional_cost FROM  `".DB_PREFIX."order`  WHERE order_id='".$this->request->get['order_id']."'");
        $json['additional_cost_text']=$this->currency->format($additional_cost->row['additional_cost'],$additional_cost->row['currency_code'], $additional_cost->row['currency_value']);
        $json['additional_cost_value']=$additional_cost->row['additional_cost'];
        if($additional_cost->row['currency_code']=='USD'){
        	$json['currency_code']="$";
        }elseif($additional_cost->row['currency_code']=='EUR'){
        	$json['currency_code']="€";
        }else{
        	$json['currency_code']="£";
        }
        
		echo json_encode($json);			
		}
	}
	public function getOrderImages(){
		$json=array();
		

		if(isset($this->request->get['order_id'])){
			$query=$this->db->query("SELECT * FROM ".DB_PREFIX."order_images WHERE order_id='".$this->request->get['order_id']."' ");
			if($query->num_rows){
				foreach ($query->rows as $key => $value) {
					$json['dataRows'][]=array(
						'image'=> HTTPS_CATALOG."image/".$value['image_path'],
					);
				}
             
			}
		}
		echo  json_encode($json);
	}

	public function editincoming_row(){
		if(isset($this->request->get['product_id'])){
			$this->db->query("DELETE FROM ".DB_PREFIX."order_product WHERE product_id='".$this->request->get['product_id']."' AND order_id='".$this->request->get['order_id']."'");
             $this->db->query("UPDATE ".DB_PREFIX."product SET incoming_request='0' WHERE product_id='".$this->request->get['product_id']."' ");

             $price_query=$this->db->query("SELECT price FROM ".DB_PREFIX."product WHERE product_id='".$this->request->get['product_id']."' ");

              $p_price=$price_query->row['price'];



             $this->db->query("UPDATE ". DB_PREFIX ."order_total SET text=(value-".$p_price."),value= (value-".$p_price.") WHERE order_id='".$this->request->get['order_id']."' AND code='total'");

             $this->db->query("UPDATE ". DB_PREFIX ."order_total SET text=(value-".$p_price."),value=(value-".$p_price.") WHERE order_id='".$this->request->get['order_id']."' AND code='sub_total'");


             $this->db->query("UPDATE `".DB_PREFIX."order` SET total=(total-".$p_price.") WHERE order_id='".$this->request->get['order_id']."' ");
		}
	}
	public function uploadOrderImages(){
		if (isset($this->request->post['submit'])) {
			if(!empty($this->request->post['order_id']))
			{
				$order_id=$this->request->post['order_id'];
			} else
			{
				if(!empty($this->request->get['order_id']))
				{
					$order_id=$this->request->get['order_id'];
				} 
			}
			$j = 0;     // Variable for indexing uploaded image.
			
			$error_upload=array();
			for ($i = 0; $i < count($_FILES['file']['name']); $i++) 
			{ 
				if( $_FILES['file']['size'][$i] > 10485760 ) { 
					$error_upload[]= basename($_FILES['file']['name'][$i])." file size exceeds 10 Mb";
					continue;
				}
				$target_path = DIR_IMAGE;
				$validextensions = array("jpeg", "jpg", "png","pdf","zip");      // Extensions which are allowed.
				$ext = explode('.', basename($_FILES['file']['name'][$i]));   // Explode file name from dot(.)
				
				$file_extension = end($ext); // Store extensions in the variable.
				$file_extension = strtolower($file_extension);
				if( !in_array( $file_extension, $validextensions ) ) { 
					$error_upload[]= basename($_FILES['file']['name'][$i])." file has not valid image extension";
					continue;
				}
				$hashed_images= "orders/".$order_id."_".md5(uniqid()) . "." . $ext[count($ext) - 1];
				$target_path = $target_path . $hashed_images;
	
				$j = $j + 1;     
				
				if (move_uploaded_file($_FILES['file']['tmp_name'][$i], $target_path)) {
					$this->db->query("INSERT INTO ".DB_PREFIX."order_images SET order_id='".$order_id."',image_path='".$this->db->escape($hashed_images)."'");
					$this->session->data['success'] = "Image uploaded successfully.";	 
				} else{
				   $error_upload[]= basename($_FILES['file']['name'][$i])." file could not upload.";
				   }   			
			}
			$this->session->data['error_upload']=$error_upload;
			if(!empty($this->request->post['order_id']))
			{
				//$this->response->redirect($this->url->link('sale/incoming/edit&order_id='.$order_id, 'token=' . $this->session->data['token'], 'SSL'));
			}
			
			$this->response->redirect($this->url->link('sale/incoming', 'token=' . $this->session->data['token'], 'SSL'));
		}

	}
	public function getALlManufacturerList(){
		$query=$this->db->query("SELECT * FROM ".DB_PREFIX."manufacturer WHERE 1");
		if($query->num_rows){
			return $query->rows;
		}else{
			return array();
		}
	}
	protected function getList() {

		$data['permissions'] = $this->user->getPermissions((int)$this->session->data['user_id']);

		if(isset($data['permissions']['incoming_orders'][0]) && !empty($data['permissions']['incoming_orders'][0]) && $data['permissions']['incoming_orders'][0] == 'no_access'){
			echo "<br />";	
			echo '<p style="color:red;font-weight:600">You Don\'t Have Permission To View This Page!</p>';
			return;
		}

		$this->load->model('user/user');
		$user_info = $this->model_user_user->getUser($this->user->getId());
		if ($user_info) {
			$data['logedin_user'] = $user_info['firstname'] . " " . $user_info['lastname'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}
		if (isset($this->request->get['filter_model'])) {
			$filter_model = $this->request->get['filter_model'];
		} else {
			$filter_model = null;
		}
		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = null;
		}
		if (isset($this->request->get['filter_manufacturer'])) {
			$filter_manufacturer = $this->request->get['filter_manufacturer'];
		} else {
			$filter_manufacturer = null;
		}
		
		if (isset($this->request->get['filter_pos'])) {
			$filter_pos = $this->request->get['filter_pos'];
		} else {
			$filter_pos = 0;
		}
		if (isset($this->request->get['filter_order_status'])) {
			$filter_order_status = $this->request->get['filter_order_status'];
		} else {
			$filter_order_status = null;
		}
		if (isset($this->request->get['filter_total'])) {
			$filter_total = $this->request->get['filter_total'];
		} else {
			$filter_total = null;
		}
		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}
		if (isset($this->request->get['filter_date_modified'])) {
			$filter_date_modified = $this->request->get['filter_date_modified'];
		} else {
			$filter_date_modified = null;
		}
		if (isset($this->request->get['filter_payment_method'])) {
			$filter_payment_method = $this->request->get['filter_payment_method'];
		} else {
			$filter_payment_method = null;
		} 
		if (isset($this->request->get['filter_sales_person'])) {
			$filter_sales_person = $this->request->get['filter_sales_person'];
		} else {
			$filter_sales_person = null;
		}
		if (isset($this->request->get['filter_record_limit'])) {
			$filter_record_limit = $this->request->get['filter_record_limit'];
		} else {
			$filter_record_limit = '20';
		}
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'o.order_id';
		}
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		$url = '';
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . $this->request->get['filter_model'];
		}

		if (isset($this->request->get['filter_manufacturer'])) {
			$url .= '&filter_manufacturer=' . $this->request->get['filter_manufacturer'];
		}
		
		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_pos'])) {
			$url .= '&filter_pos=' . $this->request->get['filter_pos'];
		}
		if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}
		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}
		if (isset($this->request->get['filter_payment_method'])) {
			$url .= '&filter_payment_method=' . $this->request->get['filter_payment_method'];
		}
		if (isset($this->request->get['filter_sales_person'])) {
			$url .= '&filter_sales_person=' . $this->request->get['filter_sales_person'];
		}
		if (isset($this->request->get['filter_record_limit'])) {
			$url .= '&filter_record_limit=' . $this->request->get['filter_record_limit'];
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
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/incoming', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		$data['payment_methods'] = $this->getEnabledPaymentMethods();
		$data['sales_persons'] = $this->model_sale_incoming->getSalesPersons($this->config->get('config_users_group'));
		$data['invoice'] = $this->url->link('sale/incoming/invoice', 'token=' . $this->session->data['token'], 'SSL');
		$data['shipping'] = $this->url->link('sale/incoming/shipping', 'token=' . $this->session->data['token'], 'SSL');
		$data['add'] = $this->url->link('sale/orderq/addincoming', 'token=' . $this->session->data['token'], 'SSL');
		$data['orders'] = array();
		$filter_data = array(
			'filter_order_id'      => $filter_order_id,
			'filter_model'      => $filter_model,
			'filter_manufacturer'      => $filter_manufacturer,
			'filter_customer'	   => $filter_customer,
			'filter_pos'  		   => $filter_pos,
			'filter_order_status'  => $filter_order_status,
			'filter_total'         => $filter_total,
			'filter_date_added'    => $filter_date_added,
			'filter_date_modified' => $filter_date_modified,
			'filter_payment_method' => $filter_payment_method,
			'filter_sales_person'   => $filter_sales_person,
			'sort'                 => $sort,
			'order'                => $order,
			'start'                => ($page - 1) * $filter_record_limit,
			'limit'                => $filter_record_limit
		);
		$order_total = $this->model_sale_incoming->getTotalOrders($filter_data);
		$results = $this->model_sale_incoming->getOrders($filter_data);
		$this->load->model('localisation/order_status');
		$this->load->model('sale/order');
		foreach ($results as $result) {
			$histories = array();
			$db_histories = $this->model_sale_order->getOrderHistories2($result['order_id'], 3);
			
			foreach ($db_histories as $history) {
				$histories[] = array(
					'notify'     => $history['notify'] ? "Yes" : "No",
					'status'     => $history['status'],
					'comment'    => nl2br($history['comment']),
					'date_added' => date($this->language->get('datetime_format'), strtotime($history['date_added']))
				);
			}
			if ($result['payment_address_format']) {
				$format = $result['payment_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}

			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{country}'
			);

			$replace = array(
				'firstname' => $result['payment_firstname'],
				'lastname'  => $result['payment_lastname'],
				'company'   => $result['payment_company'],
				'address_1' => $result['payment_address_1'],
				'address_2' => $result['payment_address_2'],
				'city'      => $result['payment_city'],
				'postcode'  => $result['payment_postcode'],
				'zone'      => $result['payment_zone'],
				'country'   => $result['payment_country']
			);

			$payment_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
		
		
		$products = $this->model_sale_order->getOrderProducts($result['order_id']);
		$order_products = array();
		foreach ($products as $product) {
			$option_data = array();

			$options = $this->model_sale_order->getOrderOptions($result['order_id'], $product['order_product_id']);

			foreach ($options as $option) {
				if ($option['type'] != 'file') {
					$option_data[] = array(
						'name'  => $option['name'],
						'value' => $option['value'],
						'type'  => $option['type']
					);
				}
			}
			
			$this->load->model('tool/image');
			$img = $this->model_sale_order->getProductImg($product['product_id']);
			
			if (!empty($img['image']) && is_file(DIR_IMAGE . $img['image'])) {
				$thumb = $this->model_tool_image->resize($img['image'], 120, 120);
				$image = $this->model_tool_image->resize($img['image'], 800, 800);
			} else {
				$thumb = $this->model_tool_image->resize('no_image.png', 120, 120);
				$image = $this->model_tool_image->resize('no_image.png', 800, 800);
			}
			
			if($product['quantity_supplied']>0){
			   $product['quantity_supplied']=$product['quantity_supplied'];
			   $totals_order=$this->currency->format(($product['price'] * $product['quantity_supplied']) + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $result['currency_code'], $result['currency_value']);
			 }else if($product['quantity_supplied']==NULL){
				 $product['quantity_supplied']="";
				 $totals_order=$this->currency->format(($product['price'] * $product['quantity']) + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $result['currency_code'], $result['currency_value']);
			 }else if($product['quantity_supplied']==0){
				 $product['quantity_supplied']=0;
			 $totals_order=$this->currency->format(($product['price'] * $product['quantity_supplied']) + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $result['currency_code'], $result['currency_value']);
			 }

			$this->load->model('catalog/unit_conversion');
			$units   = $this->model_catalog_unit_conversion->getOrderUnits($product['unit_conversion_values']);

			$order_products[] = array(
				'order_product_id' => $product['order_product_id'],
				'product_id'       => $product['product_id'],
				'thumb' 		   => $thumb,
				'image'			   => $image,
				'name'    	 	   => $product['name'],
				'model'    		   => $product['model'],
				'option'   		   => $option_data,
				'unit'   		   => ($units == 0 ? '' : $units),
				'quantity'		   => $product['quantity'],
				'quantity_supplied'		   => $product['quantity_supplied'],
				'price'    		   => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $result['currency_code'], $result['currency_value']),
				'total'    		   => $totals_order,
				'href'     		   => $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], 'SSL'),
				'unitdatanames' => $this->model_sale_order->getunitdataname($product['product_id']),
				'getDefaultUnitDetails' => $this->model_sale_order->getDefaultUnitDetails($product['product_id'])
			);
		}
			$data['orders'][] = array(
				'email'			 => $result['email'],
				'telephone'      => $result['telephone'],
				'fax'            => $result['fax'],
				'address'	     => $payment_address,
				'payment_firstname'       => $result['payment_firstname'],
				'payment_lastname'        => $result['payment_lastname'],
				'payment_company'         => $result['payment_company'],
				'payment_address_1'       => $result['payment_address_1'],
				'payment_address_2'       => $result['payment_address_2'],
				'payment_postcode'        => $result['payment_postcode'],
				'payment_city'            => $result['payment_city'],
				'payment_zone_id'         => $result['payment_zone_id'],
				'payment_zone'            => $result['payment_zone'],
				'payment_country_id'      => $result['payment_country_id'],
				'payment_country'         => $result['payment_country'],
				'payment_address_format'  => $result['payment_address_format'],
				'payment_custom_field'    => json_decode($result['payment_custom_field']),
				'payment_method'          => $result['payment_method'],
				'payment_code'            => $result['payment_code'],
				'shipping_firstname'      => $result['shipping_firstname'],
				'shipping_lastname'       => $result['shipping_lastname'],
				'shipping_company'        => $result['shipping_company'],
				'shipping_address_1'      => $result['shipping_address_1'],
				'shipping_address_2'      => $result['shipping_address_2'],
				'shipping_postcode'       => $result['shipping_postcode'],
				'shipping_city'           => $result['shipping_city'],
				'shipping_zone_id'        => $result['shipping_zone_id'],
				'shipping_zone'           => $result['shipping_zone'],
				'shipping_country_id'     => $result['shipping_country_id'],
				'shipping_country'        => $result['shipping_country'],
				'shipping_address_format' => $result['shipping_address_format'],
				'shipping_custom_field'   => json_decode($result['shipping_custom_field']),
				'shipping_method'         => $result['shipping_method'],
				'shipping_code'           => $result['shipping_code'],
				'comment'                 => $result['comment'],
				'products'				  => $order_products,
				'order_id'      		=> $result['order_id'],
				'customer'      		=> $result['customer'],
				'sales_person' 			=> $this->model_sale_incoming->getSalesPerson($result['order_id']),
				'histories'		 		=> $histories,
				'order_has_image' 		=> $this->model_sale_incoming->orderHasImage($result['order_id']),
				'status'        		=> $result['status'],
				'no_of_items'          => $this->getOrderProductsCount($result['order_id']),
				'pending_backorders'   => $this->getOrderProductsPendingBackOrders($result['order_id']),
				'store'                =>  isset($result['is_pos'])?$result['is_pos']:0,
				'manufacturers'        =>  $this->getOrderedProductsManufacturers($result['order_id']),
				'order_status_color'    => $this->model_localisation_order_status->getOrderStatuscolor($result['order_status_id']),
				'total'         		=> $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'date_added'    		=> date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_modified' 		=> date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'shipping_code' 		=> $result['shipping_code'],
				'view'          		=> $this->url->link('sale/incoming/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
				'copy'          		=> $this->url->link('sale/incoming/copy', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
				'pdfinv_packing'       	=> $this->url->link('sale/order/pdf_packingslip', 'token=' . $this->session->data['token'] . '&type=incoming&order_id=' . $result['order_id'] . $url, 'SSL'),
				'link_pdfinv_invoice'   => $this->url->link('sale/order/pdf_invoice', 'token=' . $this->session->data['token'] . '&type=incoming&order_id=' . $result['order_id'] . $url, 'SSL'),
				'link_pdf_order_request'   => $this->url->link('sale/order/pdf_order_request', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
				'edit'          		=> $this->url->link('sale/orderq/editincoming', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
			);
		}
		$data['heading_title'] = "Incoming Vendor Orders";
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_missing'] = $this->language->get('text_missing');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['column_payment_method'] = $this->language->get('column_payment_method');
		$data['column_shipping_method'] = $this->language->get('column_shipping_method');
		$data['column_order_id'] = $this->language->get('column_order_id');
		$data['column_customer'] = $this->language->get('column_customer');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_date_modified'] = $this->language->get('column_date_modified');
		$data['column_action'] = $this->language->get('column_action');
		$data['entry_return_id'] = $this->language->get('entry_return_id');
		$data['entry_order_id'] = $this->language->get('entry_order_id');
		$data['entry_model'] = $this->language->get('entry_model');
		$data['entry_customer'] = $this->language->get('entry_customer');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_date_added'] = $this->language->get('entry_date_added');
		$data['entry_date_modified'] = $this->language->get('entry_date_modified');
		$data['entry_payment_method'] = $this->language->get('entry_payment_method');
		$data['entry_sales_person'] = $this->language->get('entry_sales_person');
		$data['button_invoice_print'] = $this->language->get('button_invoice_print');
		$data['button_shipping_print'] = $this->language->get('button_shipping_print');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_copy'] = $this->language->get('button_copy');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_view'] = $this->language->get('button_view');
		$data['button_ip_add'] = $this->language->get('button_ip_add');
		$data['token'] = $this->session->data['token'];

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];

			unset($this->session->data['error_warning']);
		} else {
			$data['error_warning'] = '';
		}
		
		if(isset($this->session->data['error_upload'])&& !empty($this->session->data['error_upload'])){
			$data['error_upload']= $this->session->data['error_upload'];
		   }
		unset($this->session->data['error_upload']);
		
		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}
		$url = '';
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . $this->request->get['filter_model'];
		}

       if (isset($this->request->get['filter_manufacturer'])) {
			$url .= '&filter_manufacturer=' . $this->request->get['filter_manufacturer'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_pos'])) {
			$url .= '&filter_pos=' . $this->request->get['filter_pos'];
		}
		if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}
		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}
		if (isset($this->request->get['filter_payment_method'])) {
			$url .= '&filter_payment_method=' . $this->request->get['filter_payment_method'];
		}
		if (isset($this->request->get['filter_sales_person'])) {
			$url .= '&filter_sales_person=' . $this->request->get['filter_sales_person'];
		}
		if (isset($this->request->get['filter_record_limit'])) {
			$url .= '&filter_record_limit=' . $this->request->get['filter_record_limit'];
		}
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$data['sort_order'] = $this->url->link('sale/incoming', 'token=' . $this->session->data['token'] . '&sort=o.order_id' . $url, 'SSL');
		$data['sort_customer'] = $this->url->link('sale/incoming', 'token=' . $this->session->data['token'] . '&sort=customer' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('sale/incoming', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
		$data['sort_total'] = $this->url->link('sale/incoming', 'token=' . $this->session->data['token'] . '&sort=o.total' . $url, 'SSL');
		$data['sort_date_added'] = $this->url->link('sale/incoming', 'token=' . $this->session->data['token'] . '&sort=o.date_added' . $url, 'SSL');
		$data['sort_date_modified'] = $this->url->link('sale/incoming', 'token=' . $this->session->data['token'] . '&sort=o.date_modified' . $url, 'SSL');
		$data['sort_payment_method'] = $this->url->link('sale/incoming', 'token=' . $this->session->data['token'] . '&sort=o.payment_method' . $url, 'SSL');
		$data['sort_shipping_method'] = $this->url->link('sale/incoming', 'token=' . $this->session->data['token'] . '&sort=o.shipping_method' . $url, 'SSL');
		$url = '';
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . $this->request->get['filter_model'];
		}
		if (isset($this->request->get['filter_manufacturer'])) {
			$url .= '&filter_manufacturer=' . $this->request->get['filter_manufacturer'];
		}
		
		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_pos'])) {
			$url .= '&filter_pos=' . $this->request->get['filter_pos'];
		}
		if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}
		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}
		if (isset($this->request->get['filter_payment_method'])) {
			$url .= '&filter_payment_method=' . $this->request->get['filter_payment_method'];
		}
		if (isset($this->request->get['filter_sales_person'])) {
			$url .= '&filter_sales_person=' . $this->request->get['filter_sales_person'];
		}
		if (isset($this->request->get['filter_record_limit'])) {
			$url .= '&filter_record_limit=' . $this->request->get['filter_record_limit'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		$data['manufacturer_list']=$this->getALlManufacturerList();
		$this->load->model('catalog/manufacturer');
		$data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();	
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $filter_record_limit;
		$pagination->url = $this->url->link('sale/incoming', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $filter_record_limit) + 1 : 0, ((($page - 1) * $filter_record_limit) > ($order_total - $filter_record_limit)) ? $order_total : ((($page - 1) * $filter_record_limit) + $filter_record_limit), $order_total, ceil($order_total / $filter_record_limit));
		$data['filter_order_id'] = $filter_order_id;
		$data['filter_model'] = $filter_model;
		$data['filter_manufacturer'] = $filter_manufacturer;
		$data['filter_customer'] = $filter_customer;
		$data['filter_pos'] = $filter_pos;
		$data['filter_order_status'] = $filter_order_status;
		$data['filter_total'] = $filter_total;
		$data['filter_date_added'] = $filter_date_added;
		$data['filter_date_modified'] = $filter_date_modified;
		$data['filter_payment_method'] = $filter_payment_method;
		$data['filter_sales_person'] = $filter_sales_person;
		$data['filter_record_limit'] = $filter_record_limit; 
		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['store'] = HTTPS_CATALOG;
		// API login
		$this->load->model('user/api');
		$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));
		if ($api_info) {
			$data['api_id'] = $api_info['api_id'];
			$data['api_key'] = $api_info['key'];
			$data['api_ip'] = $this->request->server['REMOTE_ADDR'];
		} else {
			$data['api_id'] = '';
			$data['api_key'] = '';
			$data['api_ip'] = '';
		}
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('sale/incoming_list.tpl', $data));
	}

	public function getOrderedProductsManufacturers($order_id){

		$query=$this->db->query("SELECT DISTINCT m.name from ".DB_PREFIX."manufacturer m LEFT JOIN ".DB_PREFIX."product p ON(p.manufacturer_id=m.manufacturer_id) LEFT JOIN ".DB_PREFIX."order_product op ON(op.product_id=p.product_id) WHERE op.order_id='".$order_id."' GROUP BY m.name");

		if($query->num_rows){
			return $query->rows;
		}else{
			return array();
		}
         
		}
		
	
	public function getOrderProductsCount($order_id){
		$query= $this->db->query("SELECT count(product_id) as total_items FROM ".DB_PREFIX."order_product Where order_id='".$order_id."' ");
		if($query->num_rows){
          return $query->row['total_items'];
		}else{
			return 0;
		}
	}

	public function getOrderProductsPendingBackOrders($order_id){
		$products = array();
		$backorder_statuses = !empty($this->config->get('selected_backorder_statuses')) ? $this->config->get('selected_backorder_statuses') : 1;
		$query= $this->db->query("SELECT product_id FROM ".DB_PREFIX."order_product Where order_id='".$order_id."'");
		if($query->rows){
			foreach($query->rows as $row)
			{
				$products[] = $row['product_id'];
			}
			$products = implode(",",$products);
			$query = $this->db->query("SELECT count(o.order_id) as total FROM ".DB_PREFIX."order o INNER JOIN ".DB_PREFIX."order_product op ON
			o.order_id = op.order_id Where op.product_id IN (".$products.") AND o.order_type = 3 AND o.order_status_id IN (".$backorder_statuses.")");
			return $query->row ? $query->row['total'] : 0;
		}
		return 0;
	}

	public function getForm() {
		$this->load->model('customer/customer');
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_form'] = !isset($this->request->get['order_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_ip_add'] = sprintf($this->language->get('text_ip_add'), $this->request->server['REMOTE_ADDR']);
		$data['text_product'] = $this->language->get('text_product');
		$data['text_voucher'] = $this->language->get('text_voucher');
		$data['text_order_detail'] = $this->language->get('text_order_detail');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_customer'] = $this->language->get('entry_customer');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_firstname'] = $this->language->get('entry_firstname');
		$data['entry_lastname'] = $this->language->get('entry_lastname');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_telephone'] = $this->language->get('entry_telephone');
		$data['entry_fax'] = $this->language->get('entry_fax');
		$data['entry_comment'] = $this->language->get('entry_comment');
		$data['entry_affiliate'] = $this->language->get('entry_affiliate');
		$data['entry_address'] = $this->language->get('entry_address');
		$data['entry_company'] = $this->language->get('entry_company');
		$data['entry_address_1'] = $this->language->get('entry_address_1');
		$data['entry_address_2'] = $this->language->get('entry_address_2');
		$data['entry_city'] = $this->language->get('entry_city');
		$data['entry_postcode'] = $this->language->get('entry_postcode');
		$data['entry_zone'] = $this->language->get('entry_zone');
		$data['entry_zone_code'] = $this->language->get('entry_zone_code');
		$data['entry_country'] = $this->language->get('entry_country');
		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_option'] = $this->language->get('entry_option');
		$data['entry_quantity'] = $this->language->get('entry_quantity');
		$data['entry_to_name'] = $this->language->get('entry_to_name');
		$data['entry_to_email'] = $this->language->get('entry_to_email');
		$data['entry_from_name'] = $this->language->get('entry_from_name');
		$data['entry_from_email'] = $this->language->get('entry_from_email');
		$data['entry_theme'] = $this->language->get('entry_theme');
		$data['entry_message'] = $this->language->get('entry_message');
		$data['entry_amount'] = $this->language->get('entry_amount');
		$data['entry_currency'] = $this->language->get('entry_currency');
		$data['entry_shipping_method'] = $this->language->get('entry_shipping_method');
		$data['entry_payment_method'] = $this->language->get('entry_payment_method');
		$data['entry_coupon'] = $this->language->get('entry_coupon');
		$data['entry_voucher'] = $this->language->get('entry_voucher');
		$data['entry_reward'] = $this->language->get('entry_reward');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['column_product'] = $this->language->get('column_product');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_shipped_quantity'] = $this->language->get('column_shipped_quantity');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_action'] = $this->language->get('column_action');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_back'] = $this->language->get('button_back');
		$data['button_refresh'] = $this->language->get('button_refresh');
		$data['button_product_add'] = $this->language->get('button_product_add');
		$data['button_voucher_add'] = $this->language->get('button_voucher_add');
		$data['button_apply'] = $this->language->get('button_apply');
		$data['button_upload'] = $this->language->get('button_upload');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['button_ip_add'] = $this->language->get('button_ip_add');
		$data['tab_order'] = $this->language->get('tab_order');
		$data['tab_customer'] = $this->language->get('tab_customer');
		$data['tab_payment'] = $this->language->get('tab_payment');
		$data['tab_shipping'] = $this->language->get('tab_shipping');
		$data['tab_product'] = $this->language->get('tab_product');
		$data['tab_voucher'] = $this->language->get('tab_voucher');
		$data['tab_total'] = $this->language->get('tab_total');
		$data['token'] = $this->session->data['token'];
		$url = '';
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}
		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
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
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/incoming', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		$data['cancel'] = $this->url->link('sale/incoming', 'token=' . $this->session->data['token'] . $url, 'SSL');
		if (isset($this->request->get['order_id'])) {
			$order_info = $this->model_sale_incoming->getOrder($this->request->get['order_id']);
		}
		if (!empty($order_info)) {
			$data['order_id'] = $this->request->get['order_id'];
			$data['store_id'] = $order_info['store_id'];
			$data['customer'] = $order_info['customer'];
			$data['customer_id'] = $order_info['customer_id'];
			$data['customer_group_id'] = $order_info['customer_group_id'];
			$data['firstname'] = $order_info['firstname'];
			$data['lastname'] = $order_info['lastname'];
			$data['email'] = $order_info['email'];
			$data['telephone'] = $order_info['telephone'];
			$data['fax'] = $order_info['fax'];
			$data['account_custom_field'] = $order_info['custom_field'];
			$this->load->model('customer/customer');
			$data['addresses'] = $this->model_customer_customer->getAddresses($order_info['customer_id']);
			$data['payment_firstname'] = $order_info['payment_firstname'];
			$data['payment_lastname'] = $order_info['payment_lastname'];
			$data['payment_company'] = $order_info['payment_company'];
			$data['payment_address_1'] = $order_info['payment_address_1'];
			$data['payment_address_2'] = $order_info['payment_address_2'];
			$data['payment_city'] = $order_info['payment_city'];
			$data['payment_postcode'] = $order_info['payment_postcode'];
			$data['payment_country_id'] = $order_info['payment_country_id'];
			$data['payment_zone_id'] = $order_info['payment_zone_id'];
			$data['payment_custom_field'] = $order_info['payment_custom_field'];
			$data['payment_method'] = $order_info['payment_method'];
			$data['payment_code'] = $order_info['payment_code'];
			$data['shipping_firstname'] = $order_info['shipping_firstname'];
			$data['shipping_lastname'] = $order_info['shipping_lastname'];
			$data['shipping_company'] = $order_info['shipping_company'];
			$data['shipping_address_1'] = $order_info['shipping_address_1'];
			$data['shipping_address_2'] = $order_info['shipping_address_2'];
			$data['shipping_city'] = $order_info['shipping_city'];
			$data['shipping_postcode'] = $order_info['shipping_postcode'];
			$data['shipping_country_id'] = $order_info['shipping_country_id'];
			$data['shipping_zone_id'] = $order_info['shipping_zone_id'];
			$data['shipping_custom_field'] = $order_info['shipping_custom_field'];
			$data['shipping_method'] = $order_info['shipping_method'];
			$data['shipping_code'] = $order_info['shipping_code'];
			// Products
			$data['order_products'] = array();
			$products = $this->model_sale_incoming->getOrderProducts($this->request->get['order_id']);
			foreach ($products as $product) {
				$data['order_products'][] = array(
					'product_id' => $product['product_id'],
					'name'       => $product['name'],
					'model'      => $product['model'],
					'unit_conversion_values'      => $product['unit_conversion_values'],
					'option'     => $this->model_sale_incoming->getOrderOptions($this->request->get['order_id'], $product['order_product_id']),
					'quantity'   => $product['quantity'],
					'price'      => $product['price'],
					'total'      => $product['total'],
					'reward'     => $product['reward']
				);
			}
			// Vouchers
			$data['order_vouchers'] = $this->model_sale_incoming->getOrderVouchers($this->request->get['order_id']);
			$data['coupon'] = '';
			$data['voucher'] = '';
			$data['reward'] = '';
			$data['order_totals'] = array();
			$order_totals = $this->model_sale_incoming->getOrderTotals($this->request->get['order_id']);
			foreach ($order_totals as $order_total) {
				// If coupon, voucher or reward points
				$start = strpos($order_total['title'], '(') + 1;
				$end = strrpos($order_total['title'], ')');
				if ($start && $end) {
					$data[$order_total['code']] = substr($order_total['title'], $start, $end - $start);
				}
			}
			$data['order_status_id'] = $order_info['order_status_id'];
			$data['comment'] = $order_info['comment'];
			$data['affiliate_id'] = $order_info['affiliate_id'];
			$data['affiliate'] = $order_info['affiliate_firstname'] . ' ' . $order_info['affiliate_lastname'];
			$data['currency_code'] = $order_info['currency_code'];
		} else {
			$data['order_id'] = 0;
			$data['store_id'] = '';
			$data['customer'] = '';
			$data['customer_id'] = '';
			$data['customer_group_id'] = $this->config->get('config_customer_group_id');
			$data['firstname'] = '';
			$data['lastname'] = '';
			$data['email'] = '';
			$data['telephone'] = '';
			$data['fax'] = '';
			$data['customer_custom_field'] = array();
			$data['addresses'] = array();
			$data['payment_firstname'] = '';
			$data['payment_lastname'] = '';
			$data['payment_company'] = '';
			$data['payment_address_1'] = '';
			$data['payment_address_2'] = '';
			$data['payment_city'] = '';
			$data['payment_postcode'] = '';
			$data['payment_country_id'] = '';
			$data['payment_zone_id'] = '';
			$data['payment_custom_field'] = array();
			$data['payment_method'] = '';
			$data['payment_code'] = '';
			$data['shipping_firstname'] = '';
			$data['shipping_lastname'] = '';
			$data['shipping_company'] = '';
			$data['shipping_address_1'] = '';
			$data['shipping_address_2'] = '';
			$data['shipping_city'] = '';
			$data['shipping_postcode'] = '';
			$data['shipping_country_id'] = '';
			$data['shipping_zone_id'] = '';
			$data['shipping_custom_field'] = array();
			$data['shipping_method'] = '';
			$data['shipping_code'] = '';
			$data['order_products'] = array();
			$data['order_vouchers'] = array();
			$data['order_totals'] = array();
			$data['order_status_id'] = $this->config->get('config_order_status_id');
			$data['comment'] = '';
			$data['affiliate_id'] = '';
			$data['affiliate'] = '';
			$data['currency_code'] = $this->config->get('config_currency');
			$data['coupon'] = '';
			$data['voucher'] = '';
			$data['reward'] = '';
		}
		// Stores
		$this->load->model('setting/store');
		$data['stores'] = array();
		$data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->language->get('text_default'),
			'href'     => HTTP_CATALOG
		);
		$results = $this->model_setting_store->getStores();
		foreach ($results as $result) {
			$data['stores'][] = array(
				'store_id' => $result['store_id'],
				'name'     => $result['name'],
				'href'     => $result['url']
			);
		}
		// Customer Groups
		$this->load->model('customer/customer_group');
		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();
		// Custom Fields
		$this->load->model('customer/custom_field');
		$data['custom_fields'] = array();
		$filter_data = array(
			'sort'  => 'cf.sort_order',
			'order' => 'ASC'
		);
		$custom_fields = $this->model_customer_custom_field->getCustomFields($filter_data);
		foreach ($custom_fields as $custom_field) {
			$data['custom_fields'][] = array(
				'custom_field_id'    => $custom_field['custom_field_id'],
				'custom_field_value' => $this->model_customer_custom_field->getCustomFieldValues($custom_field['custom_field_id']),
				'name'               => $custom_field['name'],
				'value'              => $custom_field['value'],
				'type'               => $custom_field['type'],
				'location'           => $custom_field['location'],
				'sort_order'         => $custom_field['sort_order']
			);
		}
		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		$this->load->model('localisation/country');
		$data['countries'] = $this->model_localisation_country->getCountries();
		$this->load->model('localisation/currency');
		$data['currencies'] = $this->model_localisation_currency->getCurrencies();
		$data['voucher_min'] = $this->config->get('config_voucher_min');
		$this->load->model('sale/voucher_theme');
		$data['voucher_themes'] = $this->model_sale_voucher_theme->getVoucherThemes();
		// API login
		$this->load->model('user/api');
		$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));
		if ($api_info) {
			$data['api_id'] = $api_info['api_id'];
			$data['api_key'] = $api_info['key'];
			$data['api_ip'] = $this->request->server['REMOTE_ADDR'];
		} else {
			$data['api_id'] = '';
			$data['api_key'] = '';
			$data['api_ip'] = '';
		}
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('sale/order_form.tpl', $data));
	}
	public function copy()
	{
		$this->load->model('sale/incoming');
		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}
		$new_order_id = $this->model_sale_incoming->copyOrder($order_id);
		if($new_order_id)
		{
			$this->session->data['success'] = "Order Copied Successfully."; 
			//$this->response->redirect($this->url->link('sale/incoming', 'token=' . $this->session->data['token'], 'SSL'));

		} else {
			$this->session->data['error_warning'] = "Error occurs while copying order.";
			//$this->response->redirect($this->url->link('sale/incoming', 'token=' . $this->session->data['token'], 'SSL'));
		}
	}
	public function info() {
		$this->load->model('user/user');
		$user_info = $this->model_user_user->getUser($this->user->getId());
		if ($user_info) {
			$data['logedin_user'] = $user_info['firstname'] . " " . $user_info['lastname'];
		}
		$this->load->model('sale/incoming');
		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}
		$order_info = $this->model_sale_incoming->getOrder($order_id);
		if ($order_info) {
			$this->load->language('sale/incoming');
			$this->document->setTitle($this->language->get('heading_title'));
			$data['heading_title'] = $this->language->get('heading_title');
			$data['text_ip_add'] = sprintf($this->language->get('text_ip_add'), $this->request->server['REMOTE_ADDR']);
			$data['text_order_detail'] = $this->language->get('text_order_detail');
			$data['text_customer_detail'] = $this->language->get('text_customer_detail');
			$data['text_option'] = $this->language->get('text_option');
			$data['text_store'] = $this->language->get('text_store');
			$data['text_date_added'] = $this->language->get('text_date_added');
			$data['text_payment_method'] = $this->language->get('text_payment_method');
			$data['text_shipping_method'] = $this->language->get('text_shipping_method');
			$data['text_customer'] = $this->language->get('text_customer');
			$data['text_customer_group'] = $this->language->get('text_customer_group');
			$data['text_email'] = $this->language->get('text_email');
			$data['text_telephone'] = $this->language->get('text_telephone');
			$data['text_invoice'] = $this->language->get('text_invoice');
			$data['text_reward'] = $this->language->get('text_reward');
			$data['text_affiliate'] = $this->language->get('text_affiliate');
			$data['text_order'] = sprintf($this->language->get('text_order'), $this->request->get['order_id']);
			$data['text_payment_address'] = $this->language->get('text_payment_address');
			$data['text_shipping_address'] = $this->language->get('text_shipping_address');
			$data['text_comment'] = $this->language->get('text_comment');
			$data['text_account_custom_field'] = $this->language->get('text_account_custom_field');
			$data['text_payment_custom_field'] = $this->language->get('text_payment_custom_field');
			$data['text_shipping_custom_field'] = $this->language->get('text_shipping_custom_field');
			$data['text_browser'] = $this->language->get('text_browser');
			$data['text_ip'] = $this->language->get('text_ip');
			$data['text_forwarded_ip'] = $this->language->get('text_forwarded_ip');
			$data['text_user_agent'] = $this->language->get('text_user_agent');
			$data['text_accept_language'] = $this->language->get('text_accept_language');
			$data['text_history'] = $this->language->get('text_history');
			$data['text_history_add'] = $this->language->get('text_history_add');
			$data['text_loading'] = $this->language->get('text_loading');
			$data['column_product'] = $this->language->get('column_product');
			$data['column_model'] = $this->language->get('column_model');
			$data['column_quantity'] = $this->language->get('column_quantity');
			$data['column_price'] = $this->language->get('column_price');
			$data['column_total'] = $this->language->get('column_total');
			$data['column_shipped_quantity'] = $this->language->get('column_shipped_quantity');
			$data['entry_order_status'] = $this->language->get('entry_order_status');
			$data['entry_notify'] = $this->language->get('entry_notify');
			$data['entry_override'] = $this->language->get('entry_override');
			$data['entry_comment'] = $this->language->get('entry_comment');
			$data['entry_sales_person'] = $this->language->get('entry_sales_person');
			$data['help_override'] = $this->language->get('help_override');
			$data['button_invoice_print'] = $this->language->get('button_invoice_print');
			$data['button_shipping_print'] = $this->language->get('button_shipping_print');
			$data['button_edit'] = $this->language->get('button_edit');
			$data['button_cancel'] = $this->language->get('button_cancel');
			$data['button_generate'] = $this->language->get('button_generate');
			$data['button_reward_add'] = $this->language->get('button_reward_add');
			$data['button_reward_remove'] = $this->language->get('button_reward_remove');
			$data['button_commission_add'] = $this->language->get('button_commission_add');
			$data['button_commission_remove'] = $this->language->get('button_commission_remove');
			$data['button_history_add'] = $this->language->get('button_history_add');
			$data['button_ip_add'] = $this->language->get('button_ip_add');
			$data['btn_sales_person'] = $this->language->get('btn_sales_person');
			$data['tab_history'] = $this->language->get('tab_history');
			$data['tab_additional'] = $this->language->get('tab_additional');
			$data['tab_sales_person'] = $this->language->get('tab_sales_person');
			$data['token'] = $this->session->data['token'];
			$url = '';
			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_order_status'])) {
				$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
			}
			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}
			if (isset($this->request->get['filter_date_modified'])) {
				$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
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
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
			);
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('sale/incoming', 'token=' . $this->session->data['token'] . $url, 'SSL')
			);
			$data['shipping'] = $this->url->link('sale/incoming/shipping', 'token=' . $this->session->data['token'] . '&order_id=' . (int)$this->request->get['order_id'], 'SSL');
			$data['invoice'] = $this->url->link('sale/incoming/invoice', 'token=' . $this->session->data['token'] . '&order_id=' . (int)$this->request->get['order_id'], 'SSL');
			$data['edit'] = $this->url->link('sale/incomingq/edit', 'token=' . $this->session->data['token'] . '&order_id=' . (int)$this->request->get['order_id'], 'SSL');
			$data['cancel'] = $this->url->link('sale/incoming', 'token=' . $this->session->data['token'] . $url, 'SSL');
			$data['order_id'] = $this->request->get['order_id'];
			$data['store_name'] = $order_info['store_name'];
			$data['store_url'] = $order_info['store_url'];
			//exit;
			$data['sales_persons'] = $this->model_sale_incoming->getSalesPersons($this->config->get('config_users_group'));
			$data['sales_person'] = $this->model_sale_incoming->getSalesPerson($data['order_id']);
			if ($order_info['invoice_no']) {
				$data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
			} else {
				$data['invoice_no'] = '';
			}
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));
			$data['firstname'] = $order_info['firstname'];
			$data['lastname'] = $order_info['lastname'];
			if ($order_info['customer_id']) {
				$data['customer'] = $this->url->link('customer/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $order_info['customer_id'], 'SSL');
			} else {
				$data['customer'] = '';
			}
			$this->load->model('customer/customer_group');
			$customer_group_info = $this->model_customer_customer_group->getCustomerGroup($order_info['customer_group_id']);
			if ($customer_group_info) {
				$data['customer_group'] = $customer_group_info['name'];
			} else {
				$data['customer_group'] = '';
			}
			$data['email'] = $order_info['email'];
			$data['telephone'] = $order_info['telephone'];
			$data['shipping_method'] = $order_info['shipping_method'];
			$data['payment_method'] = $order_info['payment_method'];
			// Payment Address
			if ($order_info['payment_address_format']) {
				$format = $order_info['payment_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}
			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
			);
			$replace = array(
				'firstname' => $order_info['payment_firstname'],
				'lastname'  => $order_info['payment_lastname'],
				'company'   => $order_info['payment_company'],
				'address_1' => $order_info['payment_address_1'],
				'address_2' => $order_info['payment_address_2'],
				'city'      => $order_info['payment_city'],
				'postcode'  => $order_info['payment_postcode'],
				'zone'      => $order_info['payment_zone'],
				'zone_code' => $order_info['payment_zone_code'],
				'country'   => $order_info['payment_country']
			);
			$data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
			// Shipping Address
			if ($order_info['shipping_address_format']) {
				$format = $order_info['shipping_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}
			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
			);
			$replace = array(
				'firstname' => $order_info['shipping_firstname'],
				'lastname'  => $order_info['shipping_lastname'],
				'company'   => $order_info['shipping_company'],
				'address_1' => $order_info['shipping_address_1'],
				'address_2' => $order_info['shipping_address_2'],
				'city'      => $order_info['shipping_city'],
				'postcode'  => $order_info['shipping_postcode'],
				'zone'      => $order_info['shipping_zone'],
				'zone_code' => $order_info['shipping_zone_code'],
				'country'   => $order_info['shipping_country']
			);
			$data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
			// Uploaded files
			$this->load->model('tool/upload');
			$data['products'] = array();
			$products = $this->model_sale_incoming->getOrderProducts($this->request->get['order_id']);
			foreach ($products as $product) {
				$option_data = array();
				$options = $this->model_sale_incoming->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);
				foreach ($options as $option) {
					if ($option['type'] != 'file') {
						$option_data[] = array(
							'name'  => $option['name'],
							'value' => $option['value'],
							'type'  => $option['type']
						);
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);
						if ($upload_info) {
							$option_data[] = array(
								'name'  => $option['name'],
								'value' => $upload_info['name'],
								'type'  => $option['type'],
								'href'  => $this->url->link('tool/upload/download', 'token=' . $this->session->data['token'] . '&code=' . $upload_info['code'], 'SSL')
							);
						}
					}
				}
				$this->load->model('catalog/unit_conversion');
                $units   = $this->model_catalog_unit_conversion->getOrderUnits($product['unit_conversion_values']);
				$data['products'][] = array(
					'order_product_id' => $product['order_product_id'],
					'product_id'       => $product['product_id'],
					'name'    	 	   => $product['name'],
					'model'    		   => $product['model'],
					'option'   		   => $option_data,
    				'unit'   		   => ($units == 0 ? '' : $units),
                    'quantity' => $product['quantity'],
                    'quantity_supplied' => $product['quantity_supplied'],
					'quantity'		   => $product['quantity'],
					//'price'    		   => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
					//'total'    		   => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
					'price' => $this->currency->format($product['price'], $order_info['currency_code'], $order_info['currency_value']),
                    'total' => $this->currency->format($product['price'] * $product['quantity_supplied'], $order_info['currency_code'], $order_info['currency_value']),
					'href'     		   => $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], 'SSL'),
					'unitdatanames' => $this->model_sale_incoming->getunitdataname($product['product_id']),
					'getDefaultUnitDetails' => $this->model_sale_incoming->getDefaultUnitDetails($product['product_id'])
				);
			}
			$data['vouchers'] = array();
			$vouchers = $this->model_sale_incoming->getOrderVouchers($this->request->get['order_id']);
			foreach ($vouchers as $voucher) {
				$data['vouchers'][] = array(
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
					'href'        => $this->url->link('sale/voucher/edit', 'token=' . $this->session->data['token'] . '&voucher_id=' . $voucher['voucher_id'], 'SSL')
				);
			}
			$data['totals'] = array();
			$totals = $this->model_sale_incoming->getOrderTotals($this->request->get['order_id']);
			foreach ($totals as $total) {
				$data['totals'][] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
				);
			}
			$data['comment'] = nl2br($order_info['comment']);
			$this->load->model('customer/customer');
			$data['reward'] = $order_info['reward'];
			$data['reward_total'] = $this->model_customer_customer->getTotalCustomerRewardsByOrderId($this->request->get['order_id']);
			$data['affiliate_firstname'] = $order_info['affiliate_firstname'];
			$data['affiliate_lastname'] = $order_info['affiliate_lastname'];
			if ($order_info['affiliate_id']) {
				$data['affiliate'] = $this->url->link('marketing/affiliate/edit', 'token=' . $this->session->data['token'] . '&affiliate_id=' . $order_info['affiliate_id'], 'SSL');
			} else {
				$data['affiliate'] = '';
			}
			$data['commission'] = $this->currency->format($order_info['commission'], $order_info['currency_code'], $order_info['currency_value']);
			$this->load->model('marketing/affiliate');
			$data['commission_total'] = $this->model_marketing_affiliate->getTotalTransactionsByOrderId($this->request->get['order_id']);
			$this->load->model('localisation/order_status');
			$order_status_info = $this->model_localisation_order_status->getOrderStatus($order_info['order_status_id']);
			if ($order_status_info) {
				$data['order_status'] = $order_status_info['name'];
			} else {
				$data['order_status'] = '';
			}
			$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
			$data['order_status_id'] = $order_info['order_status_id'];
			$data['account_custom_field'] = $order_info['custom_field'];
			// Custom Fields
			$this->load->model('customer/custom_field');
			$data['account_custom_fields'] = array();
			$filter_data = array(
				'sort'  => 'cf.sort_order',
				'order' => 'ASC',
			);
			$custom_fields = $this->model_customer_custom_field->getCustomFields($filter_data);
			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'account' && isset($order_info['custom_field'][$custom_field['custom_field_id']])) {
					if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio') {
						$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($order_info['custom_field'][$custom_field['custom_field_id']]);
						if ($custom_field_value_info) {
							$data['account_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $custom_field_value_info['name']
							);
						}
					}
					if ($custom_field['type'] == 'checkbox' && is_array($order_info['custom_field'][$custom_field['custom_field_id']])) {
						foreach ($order_info['custom_field'][$custom_field['custom_field_id']] as $custom_field_value_id) {
							$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($custom_field_value_id);
							if ($custom_field_value_info) {
								$data['account_custom_fields'][] = array(
									'name'  => $custom_field['name'],
									'value' => $custom_field_value_info['name']
								);
							}
						}
					}
					if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
						$data['account_custom_fields'][] = array(
							'name'  => $custom_field['name'],
							'value' => $order_info['custom_field'][$custom_field['custom_field_id']]
						);
					}
					if ($custom_field['type'] == 'file') {
						$upload_info = $this->model_tool_upload->getUploadByCode($order_info['custom_field'][$custom_field['custom_field_id']]);
						if ($upload_info) {
							$data['account_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $upload_info['name']
							);
						}
					}
				}
			}
			// Custom fields
			$data['payment_custom_fields'] = array();
			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'address' && isset($order_info['payment_custom_field'][$custom_field['custom_field_id']])) {
					if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio') {
						$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($order_info['payment_custom_field'][$custom_field['custom_field_id']]);
						if ($custom_field_value_info) {
							$data['payment_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $custom_field_value_info['name'],
								'sort_order' => $custom_field['sort_order']
							);
						}
					}
					if ($custom_field['type'] == 'checkbox' && is_array($order_info['payment_custom_field'][$custom_field['custom_field_id']])) {
						foreach ($order_info['payment_custom_field'][$custom_field['custom_field_id']] as $custom_field_value_id) {
							$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($custom_field_value_id);
							if ($custom_field_value_info) {
								$data['payment_custom_fields'][] = array(
									'name'  => $custom_field['name'],
									'value' => $custom_field_value_info['name'],
									'sort_order' => $custom_field['sort_order']
								);
							}
						}
					}
					if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
						$data['payment_custom_fields'][] = array(
							'name'  => $custom_field['name'],
							'value' => $order_info['payment_custom_field'][$custom_field['custom_field_id']],
							'sort_order' => $custom_field['sort_order']
						);
					}
					if ($custom_field['type'] == 'file') {
						$upload_info = $this->model_tool_upload->getUploadByCode($order_info['payment_custom_field'][$custom_field['custom_field_id']]);
						if ($upload_info) {
							$data['payment_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $upload_info['name'],
								'sort_order' => $custom_field['sort_order']
							);
						}
					}
				}
			}
			// Shipping
			$data['shipping_custom_fields'] = array();
			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'address' && isset($order_info['shipping_custom_field'][$custom_field['custom_field_id']])) {
					if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio') {
						$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($order_info['shipping_custom_field'][$custom_field['custom_field_id']]);
						if ($custom_field_value_info) {
							$data['shipping_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $custom_field_value_info['name'],
								'sort_order' => $custom_field['sort_order']
							);
						}
					}
					if ($custom_field['type'] == 'checkbox' && is_array($order_info['shipping_custom_field'][$custom_field['custom_field_id']])) {
						foreach ($order_info['shipping_custom_field'][$custom_field['custom_field_id']] as $custom_field_value_id) {
							$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($custom_field_value_id);
							if ($custom_field_value_info) {
								$data['shipping_custom_fields'][] = array(
									'name'  => $custom_field['name'],
									'value' => $custom_field_value_info['name'],
									'sort_order' => $custom_field['sort_order']
								);
							}
						}
					}
					if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
						$data['shipping_custom_fields'][] = array(
							'name'  => $custom_field['name'],
							'value' => $order_info['shipping_custom_field'][$custom_field['custom_field_id']],
							'sort_order' => $custom_field['sort_order']
						);
					}
					if ($custom_field['type'] == 'file') {
						$upload_info = $this->model_tool_upload->getUploadByCode($order_info['shipping_custom_field'][$custom_field['custom_field_id']]);
						if ($upload_info) {
							$data['shipping_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $upload_info['name'],
								'sort_order' => $custom_field['sort_order']
							);
						}
					}
				}
			}
			$data['ip'] = $order_info['ip'];
			$data['forwarded_ip'] = $order_info['forwarded_ip'];
			$data['user_agent'] = $order_info['user_agent'];
			$data['accept_language'] = $order_info['accept_language'];
			// Additional Tabs
			$data['tabs'] = array();
			$this->load->model('extension/extension');
			$content = $this->load->controller('payment/' . $order_info['payment_code'] . '/order');
			if ($content) {
				$this->load->language('payment/' . $order_info['payment_code']);
				$data['tabs'][] = array(
					'code'    => $order_info['payment_code'],
					'title'   => $this->language->get('heading_title'),
					'content' => $content
				);
			}
			$extensions = $this->model_extension_extension->getInstalled('fraud');
			foreach ($extensions as $extension) {
				if ($this->config->get($extension . '_status')) {
					$this->load->language('fraud/' . $extension);
					$content = $this->load->controller('fraud/' . $extension . '/order');
					if ($content) {
						$data['tabs'][] = array(
							'code'    => $extension,
							'title'   => $this->language->get('heading_title'),
							'content' => $content
						);
					}
				}
			}
			// API login
			$this->load->model('user/api');
			$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));
			if ($api_info) {
				$data['api_id'] = $api_info['api_id'];
				$data['api_key'] = $api_info['key'];
				$data['api_ip'] = $this->request->server['REMOTE_ADDR'];
			} else {
				$data['api_id'] = '';
				$data['api_key'] = '';
				$data['api_ip'] = '';
			}
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			$this->response->setOutput($this->load->view('sale/incoming_info.tpl', $data));
		} else {
			$this->load->language('error/not_found');
			$this->document->setTitle($this->language->get('heading_title'));
			$data['heading_title'] = $this->language->get('heading_title');
			$data['text_not_found'] = $this->language->get('text_not_found');
			$data['breadcrumbs'] = array();
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
			);
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('error/not_found', 'token=' . $this->session->data['token'], 'SSL')
			);
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			$this->response->setOutput($this->load->view('error/not_found.tpl', $data));
		}
	}
	public function createInvoiceNo() {
		$this->load->language('sale/incoming');
		$json = array();
		if (!$this->user->hasPermission('modify', 'sale/incoming')) {
			$json['error'] = $this->language->get('error_permission');
		} elseif (isset($this->request->get['order_id'])) {
			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
			} else {
				$order_id = 0;
			}
			$this->load->model('sale/incoming');
			$invoice_no = $this->model_sale_incoming->createInvoiceNo($order_id);
			if ($invoice_no) {
				$json['invoice_no'] = $invoice_no;
			} else {
				$json['error'] = $this->language->get('error_action');
			}
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	public function addReward() {
		$this->load->language('sale/incoming');
		$json = array();
		if (!$this->user->hasPermission('modify', 'sale/incoming')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
			} else {
				$order_id = 0;
			}
			$this->load->model('sale/incoming');
			$order_info = $this->model_sale_incoming->getOrder($order_id);
			if ($order_info && $order_info['customer_id'] && ($order_info['reward'] > 0)) {
				$this->load->model('customer/customer');
				$reward_total = $this->model_customer_customer->getTotalCustomerRewardsByOrderId($order_id);
				if (!$reward_total) {
					$this->model_customer_customer->addReward($order_info['customer_id'], $this->language->get('text_order_id') . ' #' . $order_id, $order_info['reward'], $order_id);
				}
			}
			$json['success'] = $this->language->get('text_reward_added');
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	public function removeReward() {
		$this->load->language('sale/incoming');
		$json = array();
		if (!$this->user->hasPermission('modify', 'sale/incoming')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
			} else {
				$order_id = 0;
			}
			$this->load->model('sale/incoming');
			$order_info = $this->model_sale_incoming->getOrder($order_id);
			if ($order_info) {
				$this->load->model('customer/customer');
				$this->model_customer_customer->deleteReward($order_id);
			}
			$json['success'] = $this->language->get('text_reward_removed');
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	public function addCommission() {
		$this->load->language('sale/incoming');
		$json = array();
		if (!$this->user->hasPermission('modify', 'sale/incoming')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
			} else {
				$order_id = 0;
			}
			$this->load->model('sale/incoming');
			$order_info = $this->model_sale_incoming->getOrder($order_id);
			if ($order_info) {
				$this->load->model('marketing/affiliate');
				$affiliate_total = $this->model_marketing_affiliate->getTotalTransactionsByOrderId($order_id);
				if (!$affiliate_total) {
					$this->model_marketing_affiliate->addTransaction($order_info['affiliate_id'], $this->language->get('text_order_id') . ' #' . $order_id, $order_info['commission'], $order_id);
				}
			}
			$json['success'] = $this->language->get('text_commission_added');
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	public function removeCommission() {
		$this->load->language('sale/incoming');
		$json = array();
		if (!$this->user->hasPermission('modify', 'sale/incoming')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
			} else {
				$order_id = 0;
			}
			$this->load->model('sale/incoming');
			$order_info = $this->model_sale_incoming->getOrder($order_id);
			if ($order_info) {
				$this->load->model('marketing/affiliate');
				$this->model_marketing_affiliate->deleteTransaction($order_id);
			}
			$json['success'] = $this->language->get('text_commission_removed');
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	public function history() {
		$this->load->language('sale/incoming');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_notify'] = $this->language->get('column_notify');
		$data['column_comment'] = $this->language->get('column_comment');
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		$data['histories'] = array();
		$this->load->model('sale/incoming');
		$results = $this->model_sale_incoming->getOrderHistories($this->request->get['order_id'], ($page - 1) * 10, 10);
		foreach ($results as $result) {
			$data['histories'][] = array(
				'notify'     => $result['notify'] ? $this->language->get('text_yes') : $this->language->get('text_no'),
				'status'     => $result['status'],
				'comment'    => nl2br($result['comment']),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}
		$history_total = $this->model_sale_incoming->getTotalOrderHistories($this->request->get['order_id']);
		$pagination = new Pagination();
		$pagination->total = $history_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('sale/incoming/history', 'token=' . $this->session->data['token'] . '&order_id=' . $this->request->get['order_id'] . '&page={page}', 'SSL');
		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($history_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($history_total - 10)) ? $history_total : ((($page - 1) * 10) + 10), $history_total, ceil($history_total / 10));
		$this->response->setOutput($this->load->view('sale/order_history.tpl', $data));
	}
	public function invoice() {
		$this->load->language('sale/incoming');
		$data['title'] = $this->language->get('text_invoice');
		if ($this->request->server['HTTPS']) {
			$data['base'] = HTTPS_SERVER;
		} else {
			$data['base'] = HTTP_SERVER;
		}
		$data['direction'] = $this->language->get('direction');
		$data['lang'] = $this->language->get('code');
		$data['text_invoice'] = $this->language->get('text_invoice');
		$data['text_order_detail'] = $this->language->get('text_order_detail');
		$data['text_order_id'] = $this->language->get('text_order_id');
		$data['text_invoice_no'] = $this->language->get('text_invoice_no');
		$data['text_invoice_date'] = $this->language->get('text_invoice_date');
		$data['text_date_added'] = $this->language->get('text_date_added');
		$data['text_telephone'] = $this->language->get('text_telephone');
		$data['text_fax'] = $this->language->get('text_fax');
		$data['text_email'] = $this->language->get('text_email');
		$data['text_website'] = $this->language->get('text_website');
		$data['text_payment_address'] = $this->language->get('text_payment_address');
		$data['text_shipping_address'] = $this->language->get('text_shipping_address');
		$data['text_payment_method'] = $this->language->get('text_payment_method');
		$data['text_shipping_method'] = $this->language->get('text_shipping_method');
		$data['text_comment'] = $this->language->get('text_comment');
		$data['text_affiliate'] = $this->language->get('text_affiliate');
		$data['column_product'] = $this->language->get('column_product');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_total'] = $this->language->get('column_total');
		$this->load->model('sale/incoming');
		$this->load->model('setting/setting');
		$data['orders'] = array();
		$orders = array();
		if (isset($this->request->post['selected'])) {
			$orders = $this->request->post['selected'];
		} elseif (isset($this->request->get['order_id'])) {
			$orders[] = $this->request->get['order_id'];
		}
		foreach ($orders as $order_id) {
			$order_info = $this->model_sale_incoming->getOrder($order_id);
			if ($order_info) {
				$store_info = $this->model_setting_setting->getSetting('config', $order_info['store_id']);
				if ($store_info) {
					$store_address = $store_info['config_address'];
					$store_email = $store_info['config_email'];
					$store_telephone = $store_info['config_telephone'];
					$store_fax = $store_info['config_fax'];
				} else {
					$store_address = $this->config->get('config_address');
					$store_email = $this->config->get('config_email');
					$store_telephone = $this->config->get('config_telephone');
					$store_fax = $this->config->get('config_fax');
				}
				if ($order_info['invoice_no']) {
					$invoice_no = $order_info['invoice_prefix'] . $order_info['invoice_no'];
				} else {
					$invoice_no = '';
				}
				if ($order_info['payment_address_format']) {
					$format = $order_info['payment_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}
				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				);
				$replace = array(
					'firstname' => $order_info['payment_firstname'],
					'lastname'  => $order_info['payment_lastname'],
					'company'   => $order_info['payment_company'],
					'address_1' => $order_info['payment_address_1'],
					'address_2' => $order_info['payment_address_2'],
					'city'      => $order_info['payment_city'],
					'postcode'  => $order_info['payment_postcode'],
					'zone'      => $order_info['payment_zone'],
					'zone_code' => $order_info['payment_zone_code'],
					'country'   => $order_info['payment_country']
				);
				$payment_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
				if ($order_info['shipping_address_format']) {
					$format = $order_info['shipping_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}
				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				);
				$replace = array(
					'firstname' => $order_info['shipping_firstname'],
					'lastname'  => $order_info['shipping_lastname'],
					'company'   => $order_info['shipping_company'],
					'address_1' => $order_info['shipping_address_1'],
					'address_2' => $order_info['shipping_address_2'],
					'city'      => $order_info['shipping_city'],
					'postcode'  => $order_info['shipping_postcode'],
					'zone'      => $order_info['shipping_zone'],
					'zone_code' => $order_info['shipping_zone_code'],
					'country'   => $order_info['shipping_country']
				);
				$shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
				$this->load->model('tool/upload');
				$product_data = array();
				$products = $this->model_sale_incoming->getOrderProducts($order_id);
				foreach ($products as $product) {
					$option_data = array();
					$options = $this->model_sale_incoming->getOrderOptions($order_id, $product['order_product_id']);
					foreach ($options as $option) {
						if ($option['type'] != 'file') {
							$value = $option['value'];
						} else {
							$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);
							if ($upload_info) {
								$value = $upload_info['name'];
							} else {
								$value = '';
							}
						}
						$option_data[] = array(
							'name'  => $option['name'],
							'value' => $value
						);
					}
					$this->load->model('catalog/unit_conversion');
                $units   = $this->model_catalog_unit_conversion->getOrderUnits($product['unit_conversion_values']);
			/*	$data['products'][] = array(
					'order_product_id' => $product['order_product_id'],
					'product_id'       => $product['product_id'],
					'name'    	 	   => $product['name'],
					'model'    		   => $product['model'],
					'option'   		   => $option_data,
    				'unit'   		   => ($units == 0 ? '' : $units),
                    'quantity' => $product['quantity'],
                    'quantity_supplied' => $product['quantity_supplied'],
					'quantity'		   => $product['quantity'],
					//'price'    		   => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
					//'total'    		   => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
					'price' => $this->currency->format($product['price'], $order_info['currency_code'], $order_info['currency_value']),
                    'total' => $this->currency->format($product['total'], $order_info['currency_code'], $order_info['currency_value']),
					'href'     		   => $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], 'SSL'),
					'unitdatanames' => $this->model_sale_incoming->getunitdataname($product['product_id']),
					'getDefaultUnitDetails' => $this->model_sale_incoming->getDefaultUnitDetails($product['product_id'])
				);*/
					$product_data[] = array(
						'name'     => $product['name'],
						'model'    => $product['model'],
						'unit'   		   => ($units == 0 ? '' : $units),
						'quantity' => $product['quantity'],
						'quantity_supplied' => $product['quantity_supplied'],
						'quantity'		   => $product['quantity'],
						//'price'    		   => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
						//'total'    		   => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
						'price' => $this->currency->format($product['price'], $order_info['currency_code'], $order_info['currency_value']),
						'total' => $this->currency->format($product['total'], $order_info['currency_code'], $order_info['currency_value']),
						'option'   => $option_data,
						'unitdatanames' => $this->model_sale_incoming->getunitdataname($product['product_id']),
					    'getDefaultUnitDetails' => $this->model_sale_incoming->getDefaultUnitDetails($product['product_id'])
						/*'quantity' => $product['quantity'],
						'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
						'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])*/
					);
				}
				$this->load->model('customer/customer');
				$data['reward'] = $order_info['reward'];
				$data['reward_total'] = $this->model_customer_customer->getTotalCustomerRewardsByOrderId($this->request->get['order_id']);
				$data['affiliate_firstname'] = $order_info['affiliate_firstname'];
				$data['affiliate_lastname'] = $order_info['affiliate_lastname'];
				if ($order_info['affiliate_id']) {
					$data['affiliate'] = $this->url->link('marketing/affiliate/edit', 'token=' . $this->session->data['token'] . '&affiliate_id=' . $order_info['affiliate_id'], 'SSL');
				} else {
					$data['affiliate'] = '';
				}
			$data['commission'] = $this->currency->format($order_info['commission'], $order_info['currency_code'], $order_info['currency_value']);
			$this->load->model('marketing/affiliate');
			$data['commission_total'] = $this->model_marketing_affiliate->getTotalTransactionsByOrderId($this->request->get['order_id']);
				$voucher_data = array();
				$vouchers = $this->model_sale_incoming->getOrderVouchers($order_id);
				foreach ($vouchers as $voucher) {
					$voucher_data[] = array(
						'description' => $voucher['description'],
						'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
					);
				}
				$total_data = array();
				$totals = $this->model_sale_incoming->getOrderTotals($order_id);
				foreach ($totals as $total) {
					$total_data[] = array(
						'title' => $total['title'],
						'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
					);
				}
				$data['orders'][] = array(
					'order_id'	         => $order_id,
					'invoice_no'         => $invoice_no,
					'date_added'         => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
					'store_name'         => $order_info['store_name'],
					'store_url'          => rtrim($order_info['store_url'], '/'),
					'store_address'      => nl2br($store_address),
					'store_email'        => $store_email,
					'store_telephone'    => $store_telephone,
					'store_fax'          => $store_fax,
					'email'              => $order_info['email'],
					'telephone'          => $order_info['telephone'],
					'shipping_address'   => $shipping_address,
					'shipping_method'    => $order_info['shipping_method'],
					'payment_address'    => $payment_address,
					'payment_method'     => $order_info['payment_method'],
					'product'            => $product_data,
					'voucher'            => $voucher_data,
					'total'              => $total_data,
					'comment'            => nl2br($order_info['comment'])
				);
			}
		}
		$this->response->setOutput($this->load->view('sale/order_invoice.tpl', $data));
	}
	public function shipping() {
		$this->load->language('sale/incoming');
		$data['title'] = $this->language->get('text_shipping');
		if ($this->request->server['HTTPS']) {
			$data['base'] = HTTPS_SERVER;
		} else {
			$data['base'] = HTTP_SERVER;
		}
		$data['direction'] = $this->language->get('direction');
		$data['lang'] = $this->language->get('code');
		$data['text_shipping'] = $this->language->get('text_shipping');
		$data['text_picklist'] = $this->language->get('text_picklist');
		$data['text_order_detail'] = $this->language->get('text_order_detail');
		$data['text_order_id'] = $this->language->get('text_order_id');
		$data['text_invoice_no'] = $this->language->get('text_invoice_no');
		$data['text_invoice_date'] = $this->language->get('text_invoice_date');
		$data['text_date_added'] = $this->language->get('text_date_added');
		$data['text_telephone'] = $this->language->get('text_telephone');
		$data['text_fax'] = $this->language->get('text_fax');
		$data['text_email'] = $this->language->get('text_email');
		$data['text_website'] = $this->language->get('text_website');
		$data['text_contact'] = $this->language->get('text_contact');
		$data['text_payment_address'] = $this->language->get('text_payment_address');
		$data['text_shipping_method'] = $this->language->get('text_shipping_method');
		$data['text_sku'] = $this->language->get('text_sku');
		$data['text_upc'] = $this->language->get('text_upc');
		$data['text_ean'] = $this->language->get('text_ean');
		$data['text_jan'] = $this->language->get('text_jan');
		$data['text_isbn'] = $this->language->get('text_isbn');
		$data['text_mpn'] = $this->language->get('text_mpn');
		$data['text_comment'] = $this->language->get('text_comment');
		$data['column_location'] = $this->language->get('column_location');
		$data['column_reference'] = $this->language->get('column_reference');
		$data['column_product'] = $this->language->get('column_product');
		$data['column_weight'] = $this->language->get('column_weight');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$this->load->model('sale/incoming');
		$this->load->model('catalog/product');
		$this->load->model('setting/setting');
		$data['orders'] = array();
		$orders = array();
		if (isset($this->request->post['selected'])) {
			$orders = $this->request->post['selected'];
		} elseif (isset($this->request->get['order_id'])) {
			$orders[] = $this->request->get['order_id'];
		}
		foreach ($orders as $order_id) {
			$order_info = $this->model_sale_incoming->getOrder($order_id);
			// Make sure there is a shipping method
			if ($order_info && $order_info['shipping_code']) {
				$store_info = $this->model_setting_setting->getSetting('config', $order_info['store_id']);
				if ($store_info) {
					$store_address = $store_info['config_address'];
					$store_email = $store_info['config_email'];
					$store_telephone = $store_info['config_telephone'];
					$store_fax = $store_info['config_fax'];
				} else {
					$store_address = $this->config->get('config_address');
					$store_email = $this->config->get('config_email');
					$store_telephone = $this->config->get('config_telephone');
					$store_fax = $this->config->get('config_fax');
				}
				if ($order_info['invoice_no']) {
					$invoice_no = $order_info['invoice_prefix'] . $order_info['invoice_no'];
				} else {
					$invoice_no = '';
				}
				if ($order_info['shipping_address_format']) {
					$format = $order_info['shipping_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}
				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				);
				$replace = array(
					'firstname' => $order_info['shipping_firstname'],
					'lastname'  => $order_info['shipping_lastname'],
					'company'   => $order_info['shipping_company'],
					'address_1' => $order_info['shipping_address_1'],
					'address_2' => $order_info['shipping_address_2'],
					'city'      => $order_info['shipping_city'],
					'postcode'  => $order_info['shipping_postcode'],
					'zone'      => $order_info['shipping_zone'],
					'zone_code' => $order_info['shipping_zone_code'],
					'country'   => $order_info['shipping_country']
				);
				$shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
				$this->load->model('tool/upload');
				$product_data = array();
				$products = $this->model_sale_incoming->getOrderProducts($order_id);
				foreach ($products as $product) {
					$option_weight = '';
					$product_info = $this->model_catalog_product->getProduct($product['product_id']);
					if ($product_info) {
						$option_data = array();
						$options = $this->model_sale_incoming->getOrderOptions($order_id, $product['order_product_id']);
						foreach ($options as $option) {
							$option_value_info = $this->model_catalog_product->getProductOptionValue($order_id, $product['order_product_id']);
							if ($option['type'] != 'file') {
								$value = $option['value'];
							} else {
								$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);
								if ($upload_info) {
									$value = $upload_info['name'];
								} else {
									$value = '';
								}
							}
							$option_data[] = array(
								'name'  => $option['name'],
								'value' => $value
							);
							$product_option_value_info = $this->model_catalog_product->getProductOptionValue($product['product_id'], $option['product_option_value_id']);
							if ($product_option_value_info) {
								if ($product_option_value_info['weight_prefix'] == '+') {
									$option_weight += $product_option_value_info['weight'];
								} elseif ($product_option_value_info['weight_prefix'] == '-') {
									$option_weight -= $product_option_value_info['weight'];
								}
							}
						}
						$product_data[] = array(
							'name'     => $product_info['name'],
							'model'    => $product_info['model'],
							'option'   => $option_data,
							'quantity' => $product['quantity'],
							'location' => $product_info['location'],
							'sku'      => $product_info['sku'],
							'upc'      => $product_info['upc'],
							'ean'      => $product_info['ean'],
							'jan'      => $product_info['jan'],
							'isbn'     => $product_info['isbn'],
							'mpn'      => $product_info['mpn'],
							'weight'   => $this->weight->format(($product_info['weight'] + $option_weight) * $product['quantity'], $product_info['weight_class_id'], $this->language->get('decimal_point'), $this->language->get('thousand_point'))
						);
					}
				}
				$data['orders'][] = array(
					'order_id'	       => $order_id,
					'invoice_no'       => $invoice_no,
					'date_added'       => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
					'store_name'       => $order_info['store_name'],
					'store_url'        => rtrim($order_info['store_url'], '/'),
					'store_address'    => nl2br($store_address),
					'store_email'      => $store_email,
					'store_telephone'  => $store_telephone,
					'store_fax'        => $store_fax,
					'email'            => $order_info['email'],
					'telephone'        => $order_info['telephone'],
					'shipping_address' => $shipping_address,
					'shipping_method'  => $order_info['shipping_method'],
					'product'          => $product_data,
					'comment'          => nl2br($order_info['comment'])
				);
			}
		}
		$this->response->setOutput($this->load->view('sale/order_shipping.tpl', $data));
	}
	public function getEnabledPaymentMethods()
	{
		$data['extensions'] = array();
		$files = glob(DIR_APPLICATION . 'controller/payment/*.php');
		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');
				if ($this->config->get($extension . '_status'))
				{
					$this->load->language('payment/' . $extension);
					$text_link = $this->language->get('text_' . $extension);
					if ($text_link != 'text_' . $extension) {
						$link = $this->language->get('text_' . $extension);
					} else {
						$link = '';
					}
					$data['extensions'][] = array(
						'name'       => $this->language->get('heading_title'),
						'code'       => $extension,
						'status'     => $this->config->get($extension . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled')
					);
			  	}
			}
		}
		return $data['extensions']; 
	}
	public function assignsp()
	{
		$json = array();
		if (isset($this->request->get['order_id']) && isset($this->request->get['sales_person'])) {
			$this->load->model('sale/incoming');
			$this->model_sale_incoming->assignSalesPersontoOrder($this->request->get['order_id'],$this->request->get['sales_person']);
			$order_status_id = $this->model_sale_incoming->getLatestOrderStatusId($this->request->get['order_id']);
			$sale_person = $this->model_sale_incoming->getSalesPersonById($this->request->get['sales_person']);
			$comment = "Sales person " . $sale_person . " is assigned to this order." ." (" . $this->request->get['username'] . ")";
			$this->model_sale_incoming->addOrderHistory($this->request->get['order_id'],$order_status_id, $comment);
			$json['success'] = "Success: Order successfully assigned!";
		} else 
		{
			$json['error'] = "Warning: Could not complete this action!";
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}