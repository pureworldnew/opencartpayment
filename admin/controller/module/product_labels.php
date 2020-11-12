<?php

class ControllerModuleProductLabels extends Controller {

	private $error = array();
	private $version = "1.0";
	private $update_server = "http://www.torcu.com";

	public function install() {
       $this->load->model('module/product_labels');
	   //$this->load->model('setting/store');
	   //$store_id = $this->model_setting_store->getStore($data['store_id']);
	   $store_id=0;
       $this->model_module_product_labels->install();

       $this->load->model('setting/setting');
	   $pl_settings = array(
			'product_labels_status' => 1,
			'product_labels_enabled' => 1,
			'product_labels_default_template' => 1,
			'product_labels_default_label' => 1,
			'product_labels_default_orientation' => 'P',
			'product_labels_default_pagew' => 210,
			'product_labels_default_pageh' => 297,
			'product_labels_fgcolours' => '000000,FFFFFF',
			'product_labels_bgcolours' => 'FFFFFF,000000',
			'product_labels_download' => 0,
			'product_labels_filename' => 'labels.pdf',
			'product_labels_border' => 0,
			'product_labels_default_addr_format' => ""
		);
		$this->model_setting_setting->editSetting('product_labels', $pl_settings, $store_id);
	}

	public function uninstall() {
        $this->load->model('module/product_labels');
        $this->model_module_product_labels->uninstall();

        $this->load->model('setting/setting');
        $this->model_setting_setting->deleteSetting('product_labels');
    }

	public function previewTemplate() {

		$store_id = 0;
		$this->load->language('module/product_labels');
		$this->document->setTitle('template preview');

		$this->data = array(
			'orientation'=> 'P',
			'pagew'		 => 0,
			'pageh'		 => 0,
			'labelw'	 => 0,
			'labelh'	 => 0,
			'numw'		 => 0,
			'numh'		 => 0,
			'hspacing'	 => 0,
			'vspacing'	 => 0,
			'rounded'	 => 0,
			'margint'	 => 'auto',
			'marginl'    => 'auto'
		);

		foreach(array("orientation","pagew","pageh","labelw","labelh","numw","numh","rounded","vspacing","hspacing","margint","marginl") as $key) {
			if(isset($_GET[$key])) {
				$data[$key] = $this->request->get[$key];
			}
		}
		if ($data['margint'] == 'auto')
			$data['margint'] = @($data['pageh'] - (($data['numh']*($data['labelh']+$data['vspacing']))-$data['vspacing']))/2;

		if ($data['marginl'] == 'auto')
			$data['marginl'] = @($data['pagew'] - (($data['numw']*($data['labelw']+$data['hspacing']))-$data['hspacing']))/2;

		$this->response->setOutput($this->load->view('module/product_labels_template.tpl', $data));
	}

	private function addInputOptions($row, $options="", $name="") {
		if (!$options && !$name) {
			return '<input type="text" style="width:25px;" row="" names="" options="" name="pl_options_num['.$row.']" id="pl_options_num_'.$row.'" value="" class="pl_serializable"> <input style="width:120" type="text" name="pl_options_string['.$row.']"  id="pl_options_string_'.$row.'" class="pl_serializable">'.$name.'</br />';
		} else {
			return '<input type="text" style="width:25px;" row="'.$row.'" names="" options="'.$options.'" name="pl_options['.$row.']" id="pl_options_'.$row.'" value="" class="pl_serializable"> <input style="width:120" type="text" name="pl_options_string['.$row.']" id="pl_options_string_'.$row.'" class="pl_serializable" value="'.$name.'"><br />';
		}
	}

	public function index() {

		$this->load->language('module/product_labels');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addScript('view/javascript/product_labels/pdfobject.js');
		$this->document->addScript('view/javascript/product_labels/jquery.colorPicker.js');
		$this->document->addScript('view/javascript/product_labels/product_labels.min.js');
		$this->document->addStyle('view/stylesheet/product_labels/stylesheet.css');
		$store_id = 0;

		$this->load->model('module/product_labels');
		$this->load->model('catalog/product');
		$this->load->model('catalog/option');
		$this->load->model('setting/setting');
		$this->load->model('setting/store');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {

			$product_labels_settings = array(
				'product_labels_default_template' => $this->request->post['default_template'],
				'product_labels_default_label' => $this->request->post['default_label'],
				'product_labels_default_pagew' => $this->request->post['default_pagew'],
				'product_labels_default_pageh' => $this->request->post['default_pageh'],
				'product_labels_default_orientation' => $this->request->post['default_orientation'],
				'product_labels_fgcolours' => $this->request->post['fgcolours'],
				'product_labels_bgcolours' => $this->request->post['bgcolours'],
				'product_labels_filename' => $this->request->post['filename'],
				'product_labels_download' => $this->request->post['download'],
				'product_labels_default_addr_format' => $this->request->post['default_addr_format']
			);

			$product_labels_settings['product_labels_enabled'] = (!empty($this->request->post['enabled']))?'1':'0';
			$product_labels_settings['product_labels_border']  = (!empty($this->request->post['border']))?'1':'0';

			$this->model_setting_setting->editSetting('product_labels', $product_labels_settings, $store_id);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title']      	= $this->language->get('heading_title');

		$data['settings_tab']			= $this->language->get('settings_tab');
		$data['label_templates_tab']	= $this->language->get('label_templates_tab');
		$data['labels_tab']				= $this->language->get('labels_tab');
		$data['update_tab']				= $this->language->get('update_tab');

		$data['update_needed']   		= $this->language->get('text_update_needed');
		$data['new_version']   			= $this->language->get('text_new_version');
		$data['please_update']   		= $this->language->get('text_please_update');

		$data['text_module_enabled']	= $this->language->get('text_module_enabled');
		$data['text_default_template']	= $this->language->get('text_default_template');
		$data['text_default_pagesize']	= $this->language->get('text_default_pagesize');
		$data['text_default_label']		= $this->language->get('text_default_label');
		$data['text_default_addr_format']	= $this->language->get('text_default_addr_format');
		$data['text_default_orientation']	= $this->language->get('text_default_orientation');
		$data['text_filename']			= $this->language->get('text_filename');
		$data['text_portrait']			= $this->language->get('text_portrait');
		$data['text_landscape']			= $this->language->get('text_landscape');

		$data['text_foreground_colours']	= $this->language->get('text_foreground_colours');
		$data['text_background_colours']	= $this->language->get('text_background_colours');
		$data['text_print_borders']		= $this->language->get('text_print_borders');

		$data['text_generate_labels']	= $this->language->get('text_generate_labels');
		$data['text_option_download']	= $this->language->get('text_option_download');
		$data['text_option_no_download']	= $this->language->get('text_option_no_download');

		$data['text_template_settings']	= $this->language->get('text_template_settings');
		$data['text_select_template']	= $this->language->get('text_select_template');
		$data['text_preview']			= $this->language->get('text_preview');
		$data['text_page_size']			= $this->language->get('text_page_size');
		$data['text_label_width']		= $this->language->get('text_label_width');
		$data['text_label_height']		= $this->language->get('text_label_height');
		$data['text_labels_hor']		= $this->language->get('text_labels_hor');
		$data['text_labels_ver']		= $this->language->get('text_labels_ver');
		$data['text_spacer_hor']		= $this->language->get('text_spacer_hor');
		$data['text_spacer_ver']		= $this->language->get('text_spacer_ver');
		$data['text_margin_top']		= $this->language->get('text_margin_top');
		$data['text_margin_left']		= $this->language->get('text_margin_left');
		$data['text_rounded']			= $this->language->get('text_rounded');

		$data['text_select_label']		= $this->language->get('text_select_label');
		$data['text_text']			    = $this->language->get('text_text');
		$data['text_list']			    = $this->language->get('text_list');
		$data['text_img']			    = $this->language->get('text_img');
		$data['text_barcode']		    = $this->language->get('text_barcode');
		$data['text_addnew']		    = $this->language->get('text_addnew');
		$data['text_add']		        = $this->language->get('text_add');
		$data['text_font_f']		    = $this->language->get('text_font_f');
		$data['text_font_s']		    = $this->language->get('text_font_s');
		$data['text_font_a']		    = $this->language->get('text_font_a');
		$data['text_xpos']			    = $this->language->get('text_xpos');
		$data['text_ypos']			    = $this->language->get('text_ypos');
		$data['text_width']				= $this->language->get('text_width');
		$data['text_height']		    = $this->language->get('text_height');
		$data['text_color']		        = $this->language->get('text_color');
		$data['text_fill']			    = $this->language->get('text_fill');
		$data['text_rect']			    = $this->language->get('text_rect');
		$data['text_option_delete']		= $this->language->get('text_option_delete');
		$data['text_option_new_template']	= $this->language->get('text_option_new_template');
		$data['text_option_new_label']	= $this->language->get('text_option_new_label');

		$data['text_placeholder_text']	= $this->language->get('text_placeholder_text');
		$data['text_placeholder_img']	= $this->language->get('text_placeholder_text');
		$data['text_placeholder_font_f'] = $this->language->get('text_placeholder_font_f');
		$data['text_placeholder_font_s'] = $this->language->get('text_placeholder_font_s');
		$data['text_placeholder_font_a'] = $this->language->get('text_placeholder_font_a');
		$data['text_placeholder_xpos']	= $this->language->get('text_placeholder_xpos');
		$data['text_placeholder_ypos']	= $this->language->get('text_placeholder_ypos');
		$data['text_placeholder_width']	= $this->language->get('text_placeholder_width');
		$data['text_placeholder_height'] = $this->language->get('text_placeholder_height');
		$data['text_placeholder_color']	= $this->language->get('text_placeholder_color');
		$data['text_placeholder_fill']	= $this->language->get('text_placeholder_fill');

		$data['text_tip_font_f']		= $this->language->get('text_tip_font_f');
		$data['text_tip_font_s']	    = $this->language->get('text_tip_font_s');
		$data['text_tip_font_a']	    = $this->language->get('text_tip_font_a');
		$data['text_tip_text']		    = $this->language->get('text_tip_text');
		$data['text_tip_img']		    = $this->language->get('text_tip_img');
		$data['text_tip_xpos']		    = $this->language->get('text_tip_xpos');
		$data['text_tip_ypos']		    = $this->language->get('text_tip_ypos');
		$data['text_tip_width']	        = $this->language->get('text_tip_width');
		$data['text_tip_height']	    = $this->language->get('text_tip_height');
		$data['text_tip_color']	        = $this->language->get('text_tip_color');
		$data['text_tip_fill']		    = $this->language->get('text_tip_fill');

		$data['error_saveas_template'] 	= $this->language->get('error_saveas_template');
		$data['error_saveas_label']     = $this->language->get('error_saveas_label');
		$data['error_delete_template']  = $this->language->get('error_delete_template');
		$data['error_delete_label']     = $this->language->get('error_delete_label');
		$data['error_hi_fields']       	= $this->language->get('error_hi_fields');
		$data['error_pdf']    			= $this->language->get('error_pdf');
		$data['error_nopdf']    		= $this->language->get('error_nopdf');

		$data['button_save']          	= $this->language->get('button_save');
		$data['button_cancel']        	= $this->language->get('button_cancel');
		$data['button_remove']			= $this->language->get('button_remove');
		$data['button_add']  			= $this->language->get('button_add');
		$data['button_delete'] 			= $this->language->get('button_delete');
		$data['button_saveas']    		= $this->language->get('button_saveas');
		$data['button_preview']   		= $this->language->get('button_preview');
		$data['button_printpreview']   	= $this->language->get('button_printpreview');

		$data['token'] 					= $this->session->data['token'];

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

  		$data['breadcrumbs'] = array();
		$data['this_version'] = $this->version;

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token='.$this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token='.$this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/product_labels', 'token='.$this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		$data['action'] = $this->url->link('module/product_labels', 'token='.$this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('extension/module', 'token='.$this->session->data['token'], 'SSL');

		$data['settings'] = $this->model_setting_setting->getSetting('product_labels');
		$data['labels'] = $this->model_module_product_labels->getLabels();
		$data['label_templates'] = $this->model_module_product_labels->getLabelTemplates();

		$data['serialized'] = '{"numrows":"0","type":{},"ff":{},"fs":{},"fr":{},"text":{},"img":{},"x":{},"y":{},"w":{},"h":{}}';
		$data['label_data'] = json_decode($data['serialized'],TRUE);

		$data['row']  = 0;
		$data['type'] = "text";
		$data['i']    = 1;

		if (isset($this->request->post['product_labels_module'])) {
			$modules = $this->request->post['product_labels_module'];
		} elseif ($this->config->has('product_labels_module')) {
			$modules = $this->config->get('product_labels_module');
		} else {
			$modules = array();
		}

		$data['product_labels_module'] = array();

		foreach ($modules as $key => $module) {
			$data['slideshow_modules'][] = array(
				'key'       => $key,
				'width'     => $module['width'],
				'height'    => $module['height']
			);
		}

		$data['header'] 	 = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] 	 = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('module/product_labels.tpl', $data));
	}

	public function tab() {

		$this->load->language('module/product_labels');
		$this->load->model('module/product_labels');
		$this->load->model('catalog/option');
		$this->load->model('setting/setting');
		$this->load->model('setting/store');

		if (isset($_GET['product_id'])) {
			$product_id = $this->request->get['product_id'];
			$product = $this->model_catalog_product->getProduct($product_id);
			$product['options'] = $this->model_catalog_product->getProductOptions($product_id);

			foreach ($product['options'] as $product_option_id => $product_option) {
				if (isset($product_option['product_option_value'])) {
					foreach ($product_option['product_option_value'] as $product_option_value => $option) {
						if (!isset($option['name'])) {
							$option_value = $this->model_catalog_option->getOptionValue($option['option_value_id']);
							$product['options'][$product_option_id]['product_option_value'][$product_option_value]['name'] = $option_value['name'];
						}
					}
				}
			}

			$match =array();
			$row=0;

			$data['product'] = $product;
			$data['product_id'] = $product_id;

			$data['text_portrait'] = $this->language->get('text_portrait');
			$data['text_landscape'] = $this->language->get('text_landscape');

			$data['text_pd_label'] = $this->language->get('text_pd_label');
			$data['text_pd_orientation'] = $this->language->get('text_pd_orientation');
			$data['text_pd_template'] = $this->language->get('text_pd_template');
			$data['text_pd_generate_labels'] = $this->language->get('text_pd_generate_labels');
			$data['text_pd_preview_sheet'] = $this->language->get('text_pd_preview_sheet');
			$data['text_pd_preview_label'] = $this->language->get('text_pd_preview_label');
			$data['text_pd_tip_preview'] = $this->language->get('text_pd_tip_preview');
			$data['button_generate_labels'] = $this->language->get('button_generate_labels');

			$data['settings'] = $this->model_setting_setting->getSetting('product_labels');
			$data['labels'] = $this->model_module_product_labels->getLabels();
			$data['label_templates'] = $this->model_module_product_labels->getLabelTemplates();
			$default_template = $this->model_module_product_labels->getLabelTemplate($data['settings']['product_labels_default_template']);

			$data['pagew'] = $default_template[0]['page_w'];
			$data['pageh'] = $default_template[0]['page_h'];
			$data['rounded'] = $default_template[0]['rounded'];
			$data['orientation'] = $data['settings']['product_labels_default_orientation'];
			$data['download'] = $data['settings']['product_labels_download'];

			$data['token'] = $this->session->data['token'];

			if ($data['settings']['product_labels_default_orientation']=="P") {
				$data['labelw'] = $default_template[0]['width'];
				$data['labelh'] = $default_template[0]['height'];
				$data['numw'] = $default_template[0]['number_h'];
				$data['numh'] = $default_template[0]['number_v'];
				$data['hspacing'] = $default_template[0]['space_h'];
				$data['vspacing'] = $default_template[0]['space_v'];
			} else {
				$data['labelw'] = $default_template[0]['height'];
				$data['labelh'] = $default_template[0]['width'];
				$data['numw'] = $default_template[0]['number_v'];
				$data['numh'] = $default_template[0]['number_h'];
				$data['hspacing'] = $default_template[0]['space_v'];
				$data['vspacing'] = $default_template[0]['space_h'];
			}

			$data['n'] = $data['numw']*$data['numh'];
			$data['marginl'] = ($data['pagew']-(($data['numw']*($data['labelw']+$data['hspacing']))-$data['hspacing']))/2;
			$data['margint'] = ($data['pageh']-(($data['numh']*($data['labelh']+$data['vspacing']))-$data['vspacing']))/2;
			//echo $data['numh'];exit;
			return $this->load->view('module/product_labels_tab.tpl', $data);
		}
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/product_labels'))
			$this->error['warning'] = $this->language->get('error_permission');

		if (!$this->error)
			return TRUE;
		else
			return FALSE;
	}

   public function deleteOrderProducts()
   {
		$order_id = $this->request->get['order_id'];  
		$products = explode(",",$this->request->post['product_ids']);
		foreach ( $products as $product_id)
		{
			$this->deleteThisOrderProduct($order_id, $product_id);
		}
	  $this->updateOrderTotal($order_id);
	 }
	 
	 public function getOrderProductPriceInDefaultUnit($price, $unit_conversion_product_id)
	 {
			if( empty( $unit_conversion_product_id ) )
			{
				return round($price, 4);
			}

			$query = $this->db->query("SELECT convert_price FROM ".DB_PREFIX."unit_conversion_product WHERE unit_conversion_product_id = '".$unit_conversion_product_id."'");

			$convert_price = $query->row ? $query->row['convert_price'] : 0;

			if ( empty( $convert_price ) )
			{
				return round($price, 4);
			}

			$price_in_default_unit = $price / $convert_price;

			return round($price_in_default_unit, 4);
	 }
	 public function getOrderProductQuantityInDefaultUnit($quantity, $unit_conversion_product_id)
	 {
			if( empty( $unit_conversion_product_id ) )
			{
				return $quantity;
			}

			$query = $this->db->query("SELECT convert_price FROM ".DB_PREFIX."unit_conversion_product WHERE unit_conversion_product_id = '".$unit_conversion_product_id."'");

			$convert_price = $query->row ? $query->row['convert_price'] : 0;

			if ( empty( $convert_price ) )
			{
				return $quantity;
			}

			$quantity_in_default_unit = $quantity * $convert_price;

			return round($quantity_in_default_unit, 2);
	 }

   public function deleteThisOrderProduct($order_id, $product_id)
   {
		$this->db->query("DELETE FROM ".DB_PREFIX."order_product WHERE product_id='".$product_id."' AND order_id='".$order_id."'");
   }

   public function updateOrderTotal($order_id)
	{ 
		$query = $this->db->query("SELECT sum(total) as products_total FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

		$total = $query->row ? $query->row['products_total'] : 0;
		
		if( $total )
		{ 
			$this->db->query("UPDATE " . DB_PREFIX . "order_total SET value='".$total."' WHERE order_id='".$order_id."' AND code='sub_total'");	
			$this->db->query("UPDATE " . DB_PREFIX . "order_total SET value='".$total."' WHERE order_id='".$order_id."' AND code='total'");
			$this->db->query("UPDATE " . DB_PREFIX . "order SET total='".$total."' WHERE order_id='".$order_id."'");
		}
	}

// ===================================== add/update stocks
	public function addStock()
	{ 
		$order_id = $this->request->get['order_id'];  
		$products = $this->request->post;

		foreach( $products as $product_id => $product )
		{
			$quantity = $product;
			if( $quantity > 0)
			{
				//$this->response->setOutput("Hello".$product_id);
				//die("Hello".$product_id);
				$query = $this->db->query("SELECT quantity_received from ".DB_PREFIX."stock_refund WHERE order_id='".$order_id."' AND product_id='".$product_id."'");
				if ($query->num_rows){
						//Record already in system. Do nothing just pass to next iteration
						continue;
						//$this->response->addHeader('Content-Type: application/json');
						//$json['success']  = 'Stock already added in System';
						$json['code']     = '101';
						//$this->response->setOutput(json_encode($json));
						//return;
				}else{

					$this->db->query("UPDATE ".DB_PREFIX."product SET quantity = quantity + '".$quantity."'  WHERE product_id='".$product_id."' ");
					$this->db->query("INSERT INTO ".DB_PREFIX."stock_refund SET order_id='".$order_id."',product_id='".$product_id."',quantity_received='".$quantity."' ON DUPLICATE KEY UPDATE quantity_received='".$quantity."'");
					//$this->db->query("UPDATE ".DB_PREFIX."order_product SET quantity_supplied = IFNULL( '".$quantity."' , quantity_supplied + 2+'".$quantity."' )  WHERE product_id='".$product_id."' AND order_id ='".$order_id."'");
					/*if( isset( $product['selceted_location_id'] ) )
					{
						$this->db->query("UPDATE ".DB_PREFIX."product_to_location_quantity SET location_quantity = location_quantity + '".$quantity."'  WHERE product_id='".$product_id."' AND product_location_id ='".$product['selceted_location_id']."'");
					} */ //this code makes no sense and i dont know who wrote it, so just commenting out. and product has no attribute for that name
				
					$query = $this->db->query("SELECT p.product_id, p.sku, p.model, p.upc, p.mpn, p.manufacturer_id, pd.name FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

					$product = $query->row;
					//only for backward compatibility.
					$this->db->query("INSERT INTO " . DB_PREFIX . "incoming_stock_history SET order_id='".$order_id."', product_id='". $product_id."', manufacturer_id='".$product['manufacturer_id']."', sku='". $product['sku']."', model='". $product['model']."', upc='". $product['upc']."', mpn='". $product['mpn']."', name='". $product['name']."', quantity_received='".$quantity."'");
			   }
			}
		}

		$json['success']  = 'stock added';
		$json['code']     = '200';
		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function addRefund()
	{

		$order_id = $this->request->get['order_id'];  
		$refunds = $this->request->post;
		foreach( $refunds as $product_id => $refund )
		{
			
			//$quantity = intval($product['quantity']);
			if( $refund > 0)
			{
				$this->db->query("INSERT INTO ".DB_PREFIX."return_refund SET order_id='".$order_id."',product_id='".$product_id."',refund_amount='".$refund."' ON DUPLICATE KEY UPDATE refund_amount='".$refund."'");
				
				//$this->db->query("INSERT INTO " . DB_PREFIX . "incoming_stock_history SET order_id='".$order_id."', product_id='". $product_id."', manufacturer_id='".$product['manufacturer_id']."', sku='". $product['sku']."', model='". $product['model']."', upc='". $product['upc']."', mpn='". $product['mpn']."', name='". $product['name']."', quantity_received='".$quantity."'");
			}
		}

		$json['success']  = 'Refund added';
		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function addNewLocationStock()
	{
		if ( !empty( $this->request->post['locations'] ) && !empty( $this->request->post['product_id'] ) )
		{
			$product_ids = explode( ",", $this->request->post['product_id'] );
			foreach ( $product_ids as $product_id )
			{
				foreach ( $this->request->post['locations'] as $location )  
				{
					$this->db->query("INSERT INTO ".DB_PREFIX."product_to_location_quantity SET location_id='".$location['location_name']."', location_quantity='".$location['location_quantity']."', product_id='". $product_id."'");
				}
			}
		}
	}

	public function updateLocationStock()
	{
		if ( isset( $this->request->post['product_location_quantity'] ) )
		{
			foreach( $this->request->post['product_location_quantity'] as $product_location_id => $location_quantity )
			{
				$this->db->query("UPDATE ".DB_PREFIX."product_to_location_quantity SET location_quantity = '". $location_quantity ."'  WHERE product_location_id = '". $product_location_id ."'");
			}

			foreach( $this->request->post['product'] as $product )
			{
				$product_id = $product['product_id'];
				$query = $this->db->query("SELECT sum(`location_quantity`) as total FROM ".DB_PREFIX."product_to_location_quantity WHERE product_id = '". $product_id ."'");

				$sum = $query->row ? $query->row['total'] : 0; 
				
				if( !empty( $sum ) )
				{
					$this->db->query("UPDATE ".DB_PREFIX."product SET quantity = '". $sum ."'  WHERE product_id='". $product_id ."'");	
				} 
				
			}
		}
	}

	public function updateStocks(){
		
		if(isset($this->request->post)){
				if(isset($this->request->post['instock'])){
					if($this->request->post['instock']==1){
		          $this->db->query("UPDATE ".DB_PREFIX."product SET quantity='".$this->request->post['total_instock']."'  where product_id='".$this->request->post['product_id']."' ");
		            }else{
		            	$this->db->query("UPDATE ".DB_PREFIX."product SET quantity='0'  where product_id='".$this->request->post['product_id']."' ");
		            }
				}
				$this->db->query("DELETE FROM ".DB_PREFIX."product_to_location_quantity WHERE product_id='".$this->request->post['product_id']."' ");
				foreach ($this->request->post['locations'] as $location) {
					# code...
					$this->db->query("INSERT INTO ".DB_PREFIX."product_to_location_quantity SET location_id='".$location['location_name']."' , location_quantity='".$location['location_quantity']."' , product_id='".$this->request->post['product_id']."' ");

				}
			}

       

	}
	// ===================================== getting stock related data

	public function getStockRelatedData(){
			$json=array();
				if(isset($this->request->get['product_id'])){
				$query=$this->db->query("SELECT p.*,pd.* FROM ".DB_PREFIX."product p LEFT JOIN ".DB_PREFIX."product_description pd ON(p.product_id=pd.product_id) WHERE p.product_id='".$this->request->get['product_id']."'");
				if($query->num_rows){
					$locations=array();
					$location = $this->db->query("SELECT * FROM ".DB_PREFIX."product_to_location_quantity WHERE product_id='".$this->request->get['product_id']."' ");
					if($location->num_rows){
						$locations=$location->rows;
					}
		           $json['product_data']=$query->row;
		           $json['location_data']=$locations;
				}
			}
			$incoming_orders = array();
			$sql = "SELECT o.* , CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, o.shipping_code, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . "order` o";
			$sql .= " LEFT JOIN `" . DB_PREFIX . "order_product` op  ON op.order_id=o.order_id";
			$sql .= " WHERE op.product_id = '" . $this->request->get['product_id'] . "'";
			$sql .= " AND order_type='2' ORDER BY order_id DESC LIMIT 3";
			$query = $this->db->query($sql);
			if($query->rows)
			{
				foreach($query->rows as $row)
				{
						$incoming_orders[] = array(
							'order_id' 				=> $row['order_id'],
							'status' 					=> $row['status'],
							'manufacturer' 		=> $this->getProductManufacturer($this->request->get['product_id']),
							'no_of_items' 		=> $this->getNoofProductsInOrder($row['order_id']),
							'total' 					=> $this->currency->format($row['total']),
							'date_added' 			=> date( "m/d/Y", strtotime( $row['date_added'] ) ),
							'view'          	=> $this->url->link('sale/incoming/info', 'token=' . $this->session->data['token'] . '&order_id=' . $row['order_id'], 'SSL'),
							'copy'          	=> $this->url->link('sale/incoming/copy', 'token=' . $this->session->data['token'] . '&order_id=' . $row['order_id'], 'SSL'),
							'pdfinv_packing'  => $this->url->link('sale/order/pdf_packingslip', 'token=' . $this->session->data['token'] . '&type=incoming&order_id=' . $row['order_id'], 'SSL'),
							'link_pdfinv_invoice'   => $this->url->link('sale/order/pdf_invoice', 'token=' . $this->session->data['token'] . '&type=incoming&order_id=' . $row['order_id'], 'SSL'),
							'link_pdf_order_request'   => $this->url->link('sale/order/pdf_order_request', 'token=' . $this->session->data['token'] . '&order_id=' . $row['order_id'], 'SSL'),
							'edit'    => $this->url->link('sale/orderq/editincoming', 'token=' . $this->session->data['token'] . '&order_id=' . $row['order_id'], 'SSL'),
						);
				}
			}
			$json['incoming_orders']= $incoming_orders;
			echo json_encode($json);
	}

	public function getNoofProductsInOrder($order_id)
	{
		$query = $this->db->query("SELECT count(product_id) as total FROM ".DB_PREFIX."order_product WHERE order_id='".$order_id."'");
		return $query->row ? $query->row['total'] : 1;
	}

	public function getProductManufacturer($product_id)
	{
		$name = "";
		$query = $this->db->query("SELECT manufacturer_id FROM ".DB_PREFIX."product WHERE product_id='".$product_id."'");
		$manufacturer_id = $query->row ? $query->row['manufacturer_id'] : 0;
		if($manufacturer_id)
		{
			$query = $this->db->query("SELECT name FROM ".DB_PREFIX."manufacturer WHERE manufacturer_id='".$manufacturer_id."'");
			$name = $query->row ? $query->row['name'] : "";
		}
		return $name;
	}

	public function getMultipleStockRelatedData()
	{
		$json=array();
				if(isset($this->request->get['product_ids'])){
				$query=$this->db->query("SELECT p.*,pd.* FROM ".DB_PREFIX."product p LEFT JOIN ".DB_PREFIX."product_description pd ON(p.product_id=pd.product_id) WHERE p.product_id IN (".$this->request->get['product_ids'].") AND pd.language_id=1");
				if($query->num_rows){
					$locations=array();
					$json['product_data']=$query->rows;
		           	$json['location_data']=$locations;
				}
			}
			
			echo json_encode($json);
	}

	// ===================================== Print lables for order product

	public function printOrderProductlabel() {

		ob_start();
		require_once(DIR_SYSTEM.'library/product_labels/product_labels.php');

		$this->load->model('setting/setting');
		$this->load->model('module/product_labels');
		$this->load->model('sale/order');
		$settings = $this->model_setting_setting->getSetting('product_labels');
		
		//echo '<pre>';print_r($_POST);exit;
		
		$orientation = $settings['product_labels_default_orientation'];
		$sample 	 = 0;
		$edit		 = 0;
		$templateid  = $settings['product_labels_default_template'];
		$labelid	 = $settings['product_labels_default_label'];
		$orderids	 = array();
		$productid	 = -1;
		$blanks 	 = array();
		$order_info  = array();
		$download	 = $settings['product_labels_download'];
		$border		 = $settings['product_labels_border'];
		$printpreview= 0;

		foreach(array("orientation","templateid","labelid","productid","sample","orderids","blanks","edit","download","border","printpreview") as $key) {
			if(isset($_POST[$key]))
				if($key == "blanks")
					$$key = explode(",",$this->request->post[$key]);
				else
					$$key = $this->request->post[$key];
			if(isset($_GET[$key]))
				if($key == "blanks")
					$$key = explode(",",$this->request->get[$key]);
				else
					$$key = $this->request->get[$key];
		}

		$productid=$this->request->get['product_id'];
		$templateid=5;
		$labelid=6;

		$template_info	= $this->model_module_product_labels->getLabelTemplate($templateid);
		$label_info		= $this->model_module_product_labels->getLabel($labelid);
		$label_elements = json_decode($label_info[0]['data'],TRUE);

		$lw	 	 = $template_info[0]['width'];
		$lh		 = $template_info[0]['height'];
		$pw		 = $template_info[0]['page_w'];
		$ph		 = $template_info[0]['page_h'];
		$nw		 = $template_info[0]['number_h'];
		$nh		 = $template_info[0]['number_v'];
		$rounded = $template_info[0]['rounded'];
		$vspace  = $template_info[0]['space_v'];
		$hspace	 = $template_info[0]['space_h'];
		$mt 	 = $template_info[0]['margin_t'];
		$ml 	 = $template_info[0]['margin_l'];
		if ($sample) {
			$nw=1;
			$nh=1;
			$ph=$lh+2;
			$pw=$lw+2;
			$blanks=array();
			$orderids=1;
			$border=1;
			$label_list = 0;

		} else {

			if ($printpreview) {

				$label_list = array_fill(0, $nw*$nh, -1);
				$border=1;
				$label_elements = array();
				$orientation = "P";
				$label_elements['printpreview'] = 1;
				$labelids = array();

			} else {
				
				$this->load->model('catalog/product');
				$this->load->model('catalog/option');

				$label_elements['product_info'] = $this->model_catalog_product->getProduct($productid);
				$label_elements['product_info']['price_total']  = $label_elements['product_info']['price'];
				$label_elements['product_info']['weight_total'] = $label_elements['product_info']['weight'];
				$label_elements['product_info']['price_tax']    = $label_elements['product_info']['price'];

				$options = $this->model_catalog_product->getProductOptions($productid);

				foreach($options as $ido => $id_option) {
					if (isset($id_option['product_option_value'])) {
						foreach ($id_option['product_option_value'] as $product_option_value => $option) {
							if (!isset($option['name'])) {
								$option_value = $this->model_catalog_option->getOptionValue($option['option_value_id']);
								$options[$ido]['product_option_value'][$product_option_value]['name'] = $option_value['name'];
								$options[$ido]['product_option_value'][$product_option_value]['image'] = $option_value['image'];
							}
						}
					}
				}

				$this->load->model('catalog/manufacturer');
				$manufacturer = $this->model_catalog_manufacturer->getManufacturer($label_elements['product_info']['manufacturer_id']);

				$label_elements['product_info']['manufacturer_image'] = (isset($manufacturer['image']))?$manufacturer['image']:"undefined";
				$label_elements['product_info']['manufacturer'] = (isset($manufacturer['name']))?$manufacturer['name']:"";

				foreach($options as $id_option) {

					$label_elements['option_names'][$id_option['product_option_id']] = strtolower($id_option['name']);

					$label_elements['product_info']['options'][$id_option['product_option_id']]['name']=$id_option['name'];
					if(isset($id_option['product_option_value'])) {
						foreach ($id_option['product_option_value'] as  $tov_id => $t_option_value) {
							$t_option[$tov_id]['name'] = $t_option_value['name'];
							$t_option[$tov_id]['image'] = $t_option_value['image'];
							$t_option[$tov_id]['price'] = (float)($t_option_value['price_prefix'].$t_option_value['price']);
							$t_option[$tov_id]['weight'] = (float)($t_option_value['weight_prefix'].$t_option_value['weight']);
							$label_elements['product_info']['options'][$id_option['product_option_id']]['values']=$t_option;
						}
					} else {
						$label_elements['product_info']['options'][$id_option['product_option_id']]['values']=array();
					}
				}

				$labelids = array();
				$label_list = array();

				foreach ($this->request->post['pl_options_num'] as $id => $option_num) {
					$labelids[$id]['num'] = $option_num;
					for($n=0;$n<$option_num;$n++)
						$label_list[] = $id;
				}
				$total_labels = count($label_list);
				foreach ($this->request->post['pl_options_name'] as $id => $option_name) {
					$keyval = explode(",",$id);
					$labelids[$keyval[0]]['pl_options_name'][$keyval[1]] = $option_name;
				}
				foreach ($this->request->post['pl_options_value'] as $id => $option_value) {
					$keyval = explode(",",$id);
					$labelids[$keyval[0]]['pl_options_value'][$keyval[1]] = urldecode($option_value);
				}
				foreach ($this->request->post['pl_options_string'] as $id => $option_string) {
					$keyval = explode(",",$id);
					$labelids[$keyval[0]]['pl_options_string'][$keyval[1]] = urldecode($option_string);
				}

				$this->load->model('localisation/tax_class');
				$this->load->model('localisation/tax_rate');
				$tax   = $this->model_localisation_tax_class->getTaxClass($label_elements['product_info']['tax_class_id']);
				$taxes = $this->model_localisation_tax_class->getTaxClasses();
				$rules = $this->model_localisation_tax_class->getTaxRules($label_elements['product_info']['tax_class_id']);

			}
		}

		$label_elements['sample'] = $sample;
		$label_elements['rounded'] = $rounded;
		$label_elements['border'] = $border;

		if ($orientation=="P") {
			list($pagew, $pageh, $labelw, $labelh, $numw, $numh, $hspacing, $vspacing, $margint, $marginl) = array($pw, $ph, $lw, $lh, $nw, $nh, $hspace, $vspace, $mt, $ml);
		} else {
			list($pagew, $pageh, $labelw, $labelh, $numw, $numh, $hspacing, $vspacing, $margint, $marginl) = array($ph, $pw, $lh, $lw, $nh, $nw, $vspace, $hspace, $ml, $mt);
		}
		$n = $nw*$nh;

		if($marginl == 'auto' || $sample)
			$marginl = ($pagew - (($numw*($labelw+$hspacing))-$hspacing))/2; //margin left;

		if ($margint == 'auto' || $sample)
			$margint = ($pageh - (($numh*($labelh+$vspacing))-$vspacing))/2; //margin top;

		if ($orientation=="L")
			$margint = round(abs($pageh - (($numh * ($labelh + $vspacing)) - $vspacing) - $margint), 2);

		$pdf = new ProductLabels("",'mm',array($pagew,$pageh));
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('torcu.com');
		$pdf->SetTitle('Opencart labels');
		$pdf->SetSubject('Opencart labels');
		$pdf->SetKeywords('labels, opencart, torcu.com');
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetFont('helvetica','',8);
		$pdf->SetMargins($marginl,$margint);
		$pdf->SetAutoPageBreak(false);
		$pdf->SetDisplayMode('real');

		$j=1;
		$order_pos = 0;
		$current_options_id = -1;
		$total=count($label_list)+count($blanks);
        $label_list=array('0'=>1);
		while($order_pos < count($label_list)) {

			$pdf->AddPage();

			$labels=0;
			for($row=1;$row<=$numh;$row++) {

				if($order_pos >= count($label_list)) break;

				for($i=1;$i<=$numw;$i++) {

					if($order_pos >= count($label_list)) break;

					$x=$pdf->getX();
					$y=$pdf->getY();

					if(in_array($j,$blanks))
						$pdf->setXY($x+$labelw,$y);
					else {

						if($current_options_id != $label_list[$order_pos] && !$sample) {

							$current_options_id = $label_list[$order_pos];

							$label_elements['product_info']['price_total']  = $label_elements['product_info']['price'];
							$label_elements['product_info']['weight_total'] = $label_elements['product_info']['weight'];
							$label_elements['product_info']['price_tax']    = $label_elements['product_info']['price'];
							$label_elements['product_info']['taxes']        = 0;
							$label_elements['product_info']['tax']          = array();
							$label_elements['custom_text']					= array();
							$label_elements['option_values']				= array();

							$custom_id = 1;
							$rate = array();

							foreach($labelids[$current_options_id]['pl_options_name'] as $lidon => $option_name) {

								if ($option_name == "_c") {
									$label_elements['custom_text'][$custom_id]=$labelids[$current_options_id]['pl_options_string'][$lidon];
									$custom_id++;
								}

								if(isset($label_elements['product_info']['options'][$option_name]['values'])) {
									foreach($label_elements['product_info']['options'][$option_name]['values'] as $option_data) {
										if ($labelids[$current_options_id]['pl_options_value'][$lidon] == $option_data['name']) {
											$label_elements['option_values'][$option_name] = $option_data['name'];
											$label_elements['product_info']['price_total'] += $option_data['price'];
											$label_elements['product_info']['weight_total'] += $option_data['weight'];
											break;
										} else {
											$label_elements['option_values'][$option_name] = $labelids[$current_options_id]['pl_options_string'][$lidon];
										}
									}
								}
							}

							foreach($rules as $rateid => $rule) {
								$rate[$rateid] = $this->model_localisation_tax_rate->getTaxRate($rule['tax_rate_id']);
								$rate[$rateid]['price'] = ($label_elements['product_info']['price_total']*(1+($rate[$rateid]['rate']/100))) - $label_elements['product_info']['price_total'];
								$label_elements['product_info']['tax'][$rateid] = $rate[$rateid]['price'];
								$label_elements['product_info']['price_tax'] += $rate[$rateid]['price'];
								//$label_elements['product_info']['price_tax']."\n";
								//$label_elements['product_info']['taxes']."\n";
							}
							$label_elements['product_info']['taxes']  = $label_elements['product_info']['price_tax'] - $label_elements['product_info']['price_total'];

							$weight = $this->getProductWeights($label_elements['product_info']['weight_total'],$label_elements['product_info']['weight_class_id']);
							foreach($weight as $unit => $unit_values) {
								$label_elements['product_info'][$unit] = $unit_values['value'];
							}
						}

						$pdf->printLabel($labelw,$labelh,$x,$y,$label_elements);
						$order_pos++;
					}
					$j++;
					if($i<$numw && $hspacing>0)
						$pdf->setX($pdf->getX()+$hspacing);
				}
				if($j>$total) break;
				$pdf->setXY($marginl,$y+$labelh);
				if($row<$numh && $vspacing>0)
					$pdf->setXY($marginl,$y+$labelh+$vspacing);
			}
		}

		if(!empty($_GET['debugpdf'])) {
			print_r($pdf);
			die();
		}

		if(ob_get_contents()) ob_clean();

		if (($settings['product_labels_download'] || $download) && !$sample)
			$this->response->setOutput($pdf->Output($settings['product_labels_filename'], 'D'));
		else
			$this->response->setOutput($pdf->Output($settings['product_labels_filename'], 'I'));
	}

	// ===================================== CONTROLLER PRODUCT ORDERS
	public function labels_orders() {

		ob_start();
		require_once(DIR_SYSTEM.'library/product_labels/order_labels.php');

		$this->load->model('setting/setting');
		$this->load->model('module/product_labels');
		$this->load->model('sale/order');
		$settings = $this->model_setting_setting->getSetting('product_labels');
		
		$orientation = isset($this->request->post['orientation']) ? $this->request->post['orientation'] : $settings['product_labels_default_orientation'];//$settings['product_labels_default_orientation'];
		$sample 	 = 0;
		$edit		 = 0;
		$templateid  = isset($this->request->post['orientation']) ? $this->request->post['orientation'] : $settings['product_labels_default_template'];//$settings['product_labels_default_template'];
		$labelid	 = isset($this->request->post['labelid']) ? $this->request->post['labelid'] : 8;//$settings['product_labels_default_label'];
		$orderids	 = array();
		$productids	 = array();
		$blanks 	 = array();
		$order_info  = array();
		$download	 = $settings['product_labels_download'];
		$border		 = $settings['product_labels_border'];
		$printpreview= 0;
		$orderid	 = 0;
				
		foreach(array("orderid","orientation","templateid","labelid","productid","sample","orderids","blanks","edit","download","border","printpreview") as $key) {

			if(isset($_POST[$key]))
				if($key == "blanks")
					$$key = explode(",",$this->request->post[$key]);
				else
					$$key = $this->request->post[$key];
			if(isset($_GET[$key]))
				if($key == "blanks")
					$$key = explode(",",$this->request->get[$key]);
				else
					$$key = $this->request->get[$key];


			if(isset($_POST[$key]))
				if($key == "productid"){
					$productids = explode(",",$this->request->post[$key]);
				}else{
					$$key = $this->request->post[$key];
				}
			if(isset($_POST[$key]))
				if($productids == "productid")
					$productids = explode(",",$this->request->post[$key]);
				else
					$$key = $this->request->post[$key];


		}
		
		
		
		$label_id = isset($this->request->post['labelid']) ? $this->request->post['labelid'] : 8;
		
		$template_info	= $this->model_module_product_labels->getLabelTemplate($templateid);
		$label_info		= $this->model_module_product_labels->getLabel($label_id);
		
		$label_data = json_decode($label_info[0]['data']);
		
		$idscount = count($productids);
		if(!empty($productids)){
		for($i=0; $i<$idscount; $i++){
			$label_elements[$i] = json_decode($label_info[0]['data'],TRUE);
		}
		}else{
			$label_elements[$i] = json_decode($label_info[0]['data'],TRUE);
		}


		
		$lw	 	 = $template_info[0]['width'];//+20;
		$lh		 = $template_info[0]['height'];//+20;
		$pw		 = $template_info[0]['page_w'];//+15;
		$ph		 = $template_info[0]['page_h'];
		$nw		 = ($template_info[0]['number_h'] == 0)? 1 : $template_info[0]['number_h'];
		$nh		 = ($template_info[0]['number_v'] == 0)? 1 : $template_info[0]['number_v'];
		$rounded = $template_info[0]['rounded'];
		$vspace  = $template_info[0]['space_v'];//+20;
		$hspace	 = $template_info[0]['space_h'];//+20;
		$mt 	 = $template_info[0]['margin_t'];
		$ml 	 = $template_info[0]['margin_l'];

		if ($sample) {
			$nw=1; 
			$nh=1;
			$ph=$lh;//+2;
			$pw=$lw;//+2;
			$blanks=array();
			$orderids=1;
			$border=1;
			$label_list = 0;
		} else {
			if ($printpreview) {

				$label_list = array_fill(0, $nw*$nh, -1);
				$border=1;
				$label_elements = array();
				$orientation = "P";
				$label_elements['printpreview'] = 1;
				$labelids = array();

			} else {

				$this->load->model('catalog/product');
				$this->load->model('catalog/option');
				$this->load->model('catalog/attribute');
				 $idscount = count($productids);
				for($i=0; $i<$idscount; $i++){
					$label_elements[$i]['product_info'] = $this->model_catalog_product->getProduct($productids[$i]);
					$label_elements[$i]['product_info']['price_total']  = $label_elements[$i]['product_info']['price'];
					$label_elements[$i]['product_info']['weight_total'] = $label_elements[$i]['product_info']['weight'];
					$label_elements[$i]['product_info']['price_tax']    = $label_elements[$i]['product_info']['price'];
					$label_elements[$i]['order_info']   = $this->model_sale_order->getOrder($orderid);
					$label_elements[$i]['order_product']   = $this->model_sale_order->getOrderProduct($orderid,$label_elements[$i]['product_info']['product_id']);
					$label_elements[$i]['order_product']['total'] = $this->currency->format($label_elements[$i]['order_product']['total'], $label_elements[$i]['order_info']['currency_code'], $label_elements[$i]['order_info']['currency_value']);
					$label_elements[$i]['order_product']['unit_price'] = $this->getOrderProductPriceInDefaultUnit($label_elements[$i]['order_product']['price'], $label_elements[$i]['order_product']['unit_conversion_values']);
					$label_elements[$i]['order_product']['price'] = $this->currency->format($label_elements[$i]['order_product']['price'], $label_elements[$i]['order_info']['currency_code'], $label_elements[$i]['order_info']['currency_value']);
					$label_elements[$i]['order_product']['unit_price'] = $this->currency->format($label_elements[$i]['order_product']['unit_price'], $label_elements[$i]['order_info']['currency_code'], $label_elements[$i]['order_info']['currency_value']);
					$label_elements[$i]['order_product']['quantity_supplied'] = number_format($label_elements[$i]['order_product']['quantity_supplied'], 2);//$this->currency->format($label_elements[$i]['order_product']['price'], $label_elements[$i]['order_info']['currency_code'], $label_elements[$i]['order_info']['currency_value']);
					$label_elements[$i]['order_product']['quantity'] = $this->getOrderProductQuantityInDefaultUnit($label_elements[$i]['order_product']['quantity'], $label_elements[$i]['order_product']['unit_conversion_values']);
					//WA
					if($label_elements[$i]['order_product']['order_item_sort_order'] == 0){
						$label_elements[$i]['order_product']['order_item_sort_order'] = $i+1;
					}else{
						$label_elements[$i]['order_product']['order_item_sort_order'] = $label_elements[$i]['order_product']['order_item_sort_order'];
					}
					//WA					
					$options = $this->model_catalog_product->getProductOptions($productids[$i]);

					foreach($options as $ido => $id_option) {
						if (isset($id_option['product_option_value'])) {
							foreach ($id_option['product_option_value'] as $product_option_value => $option) {
								if (!isset($option['name'])) {
									$option_value = $this->model_catalog_option->getOptionValue($option['option_value_id']);
									$options[$ido]['product_option_value'][$product_option_value]['name'] = $option_value['name'];
									$options[$ido]['product_option_value'][$product_option_value]['image'] = $option_value['image'];
								}
							}
						}
					}

					$attributes = $this->model_catalog_product->getProductAttributesData($productids[$i]);
					if(!empty($attributes)){
					  	$array_attr=array();
					  	$all_attributes = $this->model_catalog_attribute->getAttributes();		
						if(!empty($all_attributes)){
							foreach ($all_attributes as $key => $value) {
								$array_attr []= $value["name"];	
							}
						}
					$label_elements[$i]['product_info']['attributes']=$array_attr;	
						foreach($attributes as $value){
							$label_elements[$i]['product_info'][$value["name"]] = $value["value"];
						}
					}
					$this->load->model('catalog/manufacturer');
					$manufacturer = $this->model_catalog_manufacturer->getManufacturer($label_elements[$i]['product_info']['manufacturer_id']);

					$label_elements[$i]['product_info']['manufacturer_image'] = (isset($manufacturer['image']))?$manufacturer['image']:"undefined";
					$label_elements[$i]['product_info']['manufacturer'] = (isset($manufacturer['name']))?$manufacturer['name']:"";

					foreach($options as $id_option) {

						$label_elements[$i]['option_names'][$id_option['product_option_id']] = strtolower($id_option['name']);

						$label_elements[$i]['product_info']['options'][$id_option['product_option_id']]['name']=$id_option['name'];
						if(isset($id_option['product_option_value'])) {
							foreach ($id_option['product_option_value'] as  $tov_id => $t_option_value) {
								$t_option[$tov_id]['name'] = $t_option_value['name'];
								$t_option[$tov_id]['image'] = $t_option_value['image'];
								$t_option[$tov_id]['price'] = (float)($t_option_value['price_prefix'].$t_option_value['price']);
								$t_option[$tov_id]['weight'] = (float)($t_option_value['weight_prefix'].$t_option_value['weight']);
								$label_elements[$i]['product_info']['options'][$id_option['product_option_id']]['values']=$t_option;
							}
						} else {
							$label_elements[$i]['product_info']['options'][$id_option['product_option_id']]['values']=array();
						}
					}

					$labelids = array();
					$label_list = array();

					foreach ($this->request->post['pl_options_num'] as $id => $option_num) {
						$labelids[$id]['num'] = $option_num;
						for($n=0;$n<$option_num;$n++)
							$label_list[] = $id;
					}
					$total_labels = count($label_list);
					if( isset($this->request->post['pl_options_name']) )
					{
						foreach ($this->request->post['pl_options_name'] as $id => $option_name) {
							$keyval = explode(",",$id);
							$labelids[$keyval[0]]['pl_options_name'][$keyval[1]] = $option_name;
						}
					}
					if( isset($this->request->post['pl_options_value']) )
					{
						foreach ($this->request->post['pl_options_value'] as $id => $option_value) {
							$keyval = explode(",",$id);
							$labelids[$keyval[0]]['pl_options_value'][$keyval[1]] = urldecode($option_value);
						}
					}
					if( isset($this->request->post['pl_options_string']) )
					{
						foreach ($this->request->post['pl_options_string'] as $id => $option_string) {
							$keyval = explode(",",$id);
							$labelids[$keyval[0]]['pl_options_string'][$keyval[1]] = urldecode($option_string);
						}
					}
					$this->load->model('localisation/tax_class');
					$this->load->model('localisation/tax_rate');
					$tax   = $this->model_localisation_tax_class->getTaxClass($label_elements[$i]['product_info']['tax_class_id']);
					$taxes = $this->model_localisation_tax_class->getTaxClasses();
					$rules = $this->model_localisation_tax_class->getTaxRules($label_elements[$i]['product_info']['tax_class_id']);

				}//for
			}
		}
		//echo '<pre>';print_r($label_elements);exit;
	
		if(!empty($productids)){
			for($i=0; $i<$idscount; $i++){
				$label_elements[$i]['sample'] = $sample;
				$label_elements[$i]['rounded'] = $rounded;
				$label_elements[$i]['border'] = $border;
			}
		}else{
				$label_elements[$i]['sample'] = $sample;
				$label_elements[$i]['rounded'] = $rounded;
				$label_elements[$i]['border'] = $border;
		}	

		if ($orientation=="P") {
			list($pagew, $pageh, $labelw, $labelh, $numw, $numh, $hspacing, $vspacing, $margint, $marginl) = array($pw, $ph, $lw, $lh, $nw, $nh, $hspace, $vspace, $mt, $ml);
		} else {
			list($pagew, $pageh, $labelw, $labelh, $numw, $numh, $hspacing, $vspacing, $margint, $marginl) = array($ph, $pw, $lh, $lw, $nh, $nw, $vspace, $hspace, $ml, $mt);
		}

		$n = $nw*$nh;

		if($marginl == 'auto' || $sample)
			$marginl = ($pagew - (($numw*($labelw+$hspacing))-$hspacing))/2; //margin left;

		if ($margint == 'auto' || $sample)
			$margint = ($pageh - (($numh*($labelh+$vspacing))-$vspacing))/2; //margin top;

		if ($orientation=="L")
			$margint = round(abs($pageh - (($numh * ($labelh + $vspacing)) - $vspacing) - $margint), 2);

		$pdf = new OrderLabels("",'mm',array($pagew,$pageh));
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('torcu.com');
		$pdf->SetTitle('Opencart labels');
		$pdf->SetSubject('Opencart labels');
		$pdf->SetKeywords('labels, opencart, torcu.com');
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetFont('helvetica','',8);
		$pdf->SetMargins($marginl,$margint);
		$pdf->SetAutoPageBreak(false);
		$pdf->SetDisplayMode('real');

		$j=1;
		$order_pos = 0;
		$current_options_id = -1;
		$total=count($label_list)+count($blanks);
		//echo count($label_list);exit;
		//print(count($productids));
		//die;

		$label_e = array();
		$c = 0;
		for($a=0; $a < count($productids); $a++) {
			
		  if($order_pos < count($productids)){

			$pdf->AddPage();

			$labels=0;
			for($row=1;$row<=$numh;$row++) {

				//if($order_pos >= count($productids)) break;

				for($i=1;$i<=$numw;$i++) {
							
					//if($order_pos >= count($productids)) break;

					$x=$pdf->getX();
					$y=$pdf->getY();

					if(in_array($j,$blanks)){
						$pdf->setXY($x+$labelw,$y);
					}else {


						if($current_options_id != $label_list[$order_pos] && !$sample) {

							$current_options_id = $label_list[$order_pos];

							$label_elements[$c]['product_info']['price_total']  = $label_elements[$c]['product_info']['price'];
							$label_elements[$c]['product_info']['weight_total'] = $label_elements[$c]['product_info']['weight'];
							$label_elements[$c]['product_info']['price_tax']    = $label_elements[$c]['product_info']['price'];
							$label_elements[$c]['product_info']['taxes']        = 0;
							$label_elements[$c]['product_info']['tax']          = array();
							$label_elements[$c]['custom_text']					= array();
							$label_elements[$c]['option_values']				= array();

							$custom_id = 1;
							$rate = array();

							foreach($labelids[$current_options_id]['pl_options_name'] as $lidon => $option_name) {

								if ($option_name == "_c") {
									$label_elements[$c]['custom_text'][$custom_id]=$labelids[$current_options_id]['pl_options_string'][$lidon];
									$custom_id++;
								}

								if(isset($label_elements[$c]['product_info']['options'][$option_name]['values'])) {
									foreach($label_elements[$c]['product_info']['options'][$option_name]['values'] as $option_data) {
										if ($labelids[$current_options_id]['pl_options_value'][$lidon] == $option_data['name']) {
											$label_elements[$c]['option_values'][$option_name] = $option_data['name'];
											$label_elements[$c]['product_info']['price_total'] += $option_data['price'];
											$label_elements[$c]['product_info']['weight_total'] += $option_data['weight'];
											break;
										} else {
											$label_elements['option_values'][$option_name] = $labelids[$current_options_id]['pl_options_string'][$lidon];
										}
									}
								}
							}

							foreach($rules as $rateid => $rule) {
								$rate[$rateid] = $this->model_localisation_tax_rate->getTaxRate($rule['tax_rate_id']);
								$rate[$rateid]['price'] = ($label_elements[$c]['product_info']['price_total']*(1+($rate[$rateid]['rate']/100))) - $label_elements[$c]['product_info']['price_total'];
								$label_elements[$c]['product_info']['tax'][$rateid] = $rate[$rateid]['price'];
								$label_elements[$c]['product_info']['price_tax'] += $rate[$rateid]['price'];
								//$label_elements['product_info']['price_tax']."\n";
								//$label_elements['product_info']['taxes']."\n";
							}
							$label_elements[$c]['product_info']['taxes']  = $label_elements[$c]['product_info']['price_tax'] - $label_elements[$c]['product_info']['price_total'];

							$weight = $this->getProductWeights($label_elements[$c]['product_info']['weight_total'],$label_elements[$c]['product_info']['weight_class_id']);
							foreach($weight as $unit => $unit_values) {
								$label_elements[$c]['product_info'][$unit] = $unit_values['value'];
							}
						}
						
						//echo '<pre>';print_r($label_elements[$c]);exit;
						$pdf->printLabel($labelw,$labelh,$x,$y,$label_elements[$c]);

						
						$order_pos++;
					}
					$j++;
					if($i<$numw && $hspacing>0)
						$pdf->setX($pdf->getX()+$hspacing);
				}
				if($j>$total) break;
				$pdf->setXY($marginl,$y+$labelh);
				if($row<$numh && $vspacing>0)
					$pdf->setXY($marginl,$y+$labelh+$vspacing);
			}

			}
			$c++;
		}
		//echo '<pre>';print_r($label_elements);exit;
		//die;

		if(!empty($_GET['debugpdf'])) {
			print_r($pdf);
			die();
		}

		if(ob_get_contents()) ob_clean();

		if (($settings['product_labels_download'] || $download) && !$sample)
			$this->response->setOutput($pdf->Output($settings['product_labels_filename'], 'D'));
		else
			$this->response->setOutput($pdf->Output($settings['product_labels_filename'], 'I'));
	}
	
	public function labels_qrcode() {
		$this->load->model('qrlabel/qrlabel');
		$this->load->model('catalog/product');
		$filter = array();
		$products = array();
		$searchable = false;
		$filter['filter_category_id'] = isset($this->request->post['filter_catid_pl'])?rawurldecode($this->request->post['filter_catid_pl']):false;
		$filter['filter_manufacturer_id'] = isset($this->request->post['filter_manid_pl'])?rawurldecode($this->request->post['filter_manid_pl']):false;
		$filter['filter_model'] = isset($this->request->post['filter_model_pl'])?rawurldecode($this->request->post['filter_model_pl']):false;
		$filter['filter_location'] = isset($this->request->post['filter_location_pl'])?rawurldecode($this->request->post['filter_location_pl']):false;
		$filter['gen'] = isset($this->request->post['gen'])?$this->request->post['gen']:false;
		
		$sets = array();
		$sets['filter_cpp'] = isset($this->request->post['filter_cpp_pl'])?$this->request->post['filter_cpp_pl']:false;
		$sets['filter_custom'] = isset($this->request->post['filter_custom_pl'])?rawurldecode($this->request->post['filter_custom_pl']):null;
		$sets['filter_text'] = isset($this->request->post['filter_text_pl'])?rawurldecode($this->request->post['filter_text_pl']):'';
		$sets['default_format'] = isset($this->request->post['default_format_pl'])?rawurldecode($this->request->post['default_format_pl']):null;
		
		foreach ($filter as $key=>$val) {
			if ((isset($key)) && (!empty($val))) {
				$searchable = true;
				break;
			}
		}
		
		if ($searchable) {
			$products = $this->model_catalog_product->getProducts($filter);
		}
		
		if (empty($products)) {
			die("No products matching search criteria found.");
		}
		
		ob_start();
		require_once(DIR_SYSTEM.'library/product_labels/product_labels.php');

		$this->load->model('setting/setting');
		$this->load->model('module/product_labels');
		$this->load->model('sale/order');
		$settings = $this->model_setting_setting->getSetting('product_labels');
		
		$orientation = isset($this->request->post['orientation']) ? $this->request->post['orientation'] : $settings['product_labels_default_orientation'];//$settings['product_labels_default_orientation'];
		$sample 	 = 0;
		$edit		 = 0;
		$templateid  = isset($this->request->post['orientation']) ? $this->request->post['orientation'] : $settings['product_labels_default_template'];//$settings['product_labels_default_template'];
		$labelid	 = isset($this->request->post['labelid']) ? $this->request->post['labelid'] : 8;//$settings['product_labels_default_label'];
		$orderids	 = array();
		$productids	 = array();
		$blanks 	 = array();
		$order_info  = array();
		$download	 = $settings['product_labels_download'];
		$border		 = $settings['product_labels_border'];
		$printpreview= 0;
		$orderid	 = 0;
				
		foreach(array("orderid","orientation","templateid","labelid","productid","sample","orderids","blanks","edit","download","border","printpreview") as $key) {

			if(isset($_POST[$key]))
				if($key == "blanks")
					$$key = explode(",",$this->request->post[$key]);
				else
					$$key = $this->request->post[$key];
			if(isset($_GET[$key]))
				if($key == "blanks")
					$$key = explode(",",$this->request->get[$key]);
				else
					$$key = $this->request->get[$key];
		}
		
		foreach ($products as $pid) {
			$productids[] = $pid['product_id'];
		}
		
		
		
		$label_id = isset($this->request->post['labelid']) ? $this->request->post['labelid'] : 8;
		
		$template_info	= $this->model_module_product_labels->getLabelTemplate($templateid);
		$label_info		= $this->model_module_product_labels->getLabel($label_id);
		
		$label_data = json_decode($label_info[0]['data']);
		
		$idscount = count($productids);
		if(!empty($productids)){
			for($i=0; $i<$idscount; $i++){
				$label_elements[$i] = json_decode($label_info[0]['data'],TRUE);
			}
		}else{
			$label_elements[$i] = json_decode($label_info[0]['data'],TRUE);
		}

		
		$lw	 	 = $template_info[0]['width'];//+20;
		$lh		 = $template_info[0]['height'];//+20;
		$pw		 = $template_info[0]['page_w'];//+15;
		$ph		 = $template_info[0]['page_h'];
		$nw		 = ($template_info[0]['number_h'] == 0)? 1 : $template_info[0]['number_h'];
		$nh		 = ($template_info[0]['number_v'] == 0)? 1 : $template_info[0]['number_v'];
		$rounded = $template_info[0]['rounded'];
		$vspace  = $template_info[0]['space_v'];//+20;
		$hspace	 = $template_info[0]['space_h'];//+20;
		$mt 	 = $template_info[0]['margin_t'];
		$ml 	 = $template_info[0]['margin_l'];

		if ($sample) {
			$nw=1; 
			$nh=1;
			$ph=$lh;//+2;
			$pw=$lw;//+2;
			$blanks=array();
			$orderids=1;
			$border=1;
			$label_list = 0;
		} else {
			if ($printpreview) {

				$label_list = array_fill(0, $nw*$nh, -1);
				$border=1;
				$label_elements = array();
				$orientation = "P";
				$label_elements['printpreview'] = 1;
				$labelids = array();

			} else {

				$this->load->model('catalog/product');
				$this->load->model('catalog/option');
				$this->load->model('catalog/attribute');
				 $idscount = count($productids);
				for($i=0; $i<$idscount; $i++){
					$label_elements[$i]['product_info'] = $this->model_catalog_product->getProduct($productids[$i]);
					$label_elements[$i]['product_info']['price_total']  = $label_elements[$i]['product_info']['price'];
					$label_elements[$i]['product_info']['weight_total'] = $label_elements[$i]['product_info']['weight'];
					$label_elements[$i]['product_info']['price_tax']    = $label_elements[$i]['product_info']['price'];
					$label_elements[$i]['product_info']['qrformat'] = $sets['filter_custom'];
					
										
					$options = $this->model_catalog_product->getProductOptions($productids[$i]);

					foreach($options as $ido => $id_option) {
						if (isset($id_option['product_option_value'])) {
							foreach ($id_option['product_option_value'] as $product_option_value => $option) {
								if (!isset($option['name'])) {
									$option_value = $this->model_catalog_option->getOptionValue($option['option_value_id']);
									$options[$ido]['product_option_value'][$product_option_value]['name'] = $option_value['name'];
									$options[$ido]['product_option_value'][$product_option_value]['image'] = $option_value['image'];
								}
							}
						}
					}

					$attributes = $this->model_catalog_product->getProductAttributesData($productids[$i]);
					if(!empty($attributes)){
					  	$array_attr=array();
					  	$all_attributes = $this->model_catalog_attribute->getAttributes();		
						if(!empty($all_attributes)){
							foreach ($all_attributes as $key => $value) {
								$array_attr []= $value["name"];	
							}
						}
					$label_elements[$i]['product_info']['attributes']=$array_attr;	
						foreach($attributes as $value){
							$label_elements[$i]['product_info'][$value["name"]] = $value["value"];
						}
					}
					$this->load->model('catalog/manufacturer');
					$manufacturer = $this->model_catalog_manufacturer->getManufacturer($label_elements[$i]['product_info']['manufacturer_id']);

					$label_elements[$i]['product_info']['manufacturer_image'] = (isset($manufacturer['image']))?$manufacturer['image']:"undefined";
					$label_elements[$i]['product_info']['manufacturer'] = (isset($manufacturer['name']))?$manufacturer['name']:"";

					foreach($options as $id_option) {

						$label_elements[$i]['option_names'][$id_option['product_option_id']] = strtolower($id_option['name']);

						$label_elements[$i]['product_info']['options'][$id_option['product_option_id']]['name']=$id_option['name'];
						if(isset($id_option['product_option_value'])) {
							foreach ($id_option['product_option_value'] as  $tov_id => $t_option_value) {
								$t_option[$tov_id]['name'] = $t_option_value['name'];
								$t_option[$tov_id]['image'] = $t_option_value['image'];
								$t_option[$tov_id]['price'] = (float)($t_option_value['price_prefix'].$t_option_value['price']);
								$t_option[$tov_id]['weight'] = (float)($t_option_value['weight_prefix'].$t_option_value['weight']);
								$label_elements[$i]['product_info']['options'][$id_option['product_option_id']]['values']=$t_option;
							}
						} else {
							$label_elements[$i]['product_info']['options'][$id_option['product_option_id']]['values']=array();
						}
					}

					$labelids = array();
					$label_list = array();

					foreach ($this->request->post['pl_options_num'] as $id => $option_num) {
						$labelids[$id]['num'] = $option_num;
						for($n=0;$n<$option_num;$n++)
							$label_list[] = $id;
					}
					$total_labels = count($label_list);
					if( isset($this->request->post['pl_options_name']) )
					{
						foreach ($this->request->post['pl_options_name'] as $id => $option_name) {
							$keyval = explode(",",$id);
							$labelids[$keyval[0]]['pl_options_name'][$keyval[1]] = $option_name;
						}
					}
					if( isset($this->request->post['pl_options_value']) )
					{
						foreach ($this->request->post['pl_options_value'] as $id => $option_value) {
							$keyval = explode(",",$id);
							$labelids[$keyval[0]]['pl_options_value'][$keyval[1]] = urldecode($option_value);
						}
					}
					if( isset($this->request->post['pl_options_string']) )
					{
						foreach ($this->request->post['pl_options_string'] as $id => $option_string) {
							$keyval = explode(",",$id);
							$labelids[$keyval[0]]['pl_options_string'][$keyval[1]] = urldecode($option_string);
						}
					}
					$this->load->model('localisation/tax_class');
					$this->load->model('localisation/tax_rate');
					$tax   = $this->model_localisation_tax_class->getTaxClass($label_elements[$i]['product_info']['tax_class_id']);
					$taxes = $this->model_localisation_tax_class->getTaxClasses();
					$rules = $this->model_localisation_tax_class->getTaxRules($label_elements[$i]['product_info']['tax_class_id']);

				}//for
			}
		}
		//echo '<pre>';print_r($label_elements);exit;
	
		if(!empty($productids)){
			for($i=0; $i<$idscount; $i++){
				$label_elements[$i]['sample'] = $sample;
				$label_elements[$i]['rounded'] = $rounded;
				$label_elements[$i]['border'] = $border;
			}
		}else{
				$label_elements[$i]['sample'] = $sample;
				$label_elements[$i]['rounded'] = $rounded;
				$label_elements[$i]['border'] = $border;
		}	

		if ($orientation=="P") {
			list($pagew, $pageh, $labelw, $labelh, $numw, $numh, $hspacing, $vspacing, $margint, $marginl) = array($pw, $ph, $lw, $lh, $nw, $nh, $hspace, $vspace, $mt, $ml);
		} else {
			list($pagew, $pageh, $labelw, $labelh, $numw, $numh, $hspacing, $vspacing, $margint, $marginl) = array($ph, $pw, $lh, $lw, $nh, $nw, $vspace, $hspace, $ml, $mt);
		}

		$n = $nw*$nh;

		if($marginl == 'auto' || $sample)
			$marginl = ($pagew - (($numw*($labelw+$hspacing))-$hspacing))/2; //margin left;

		if ($margint == 'auto' || $sample)
			$margint = ($pageh - (($numh*($labelh+$vspacing))-$vspacing))/2; //margin top;

		if ($orientation=="L")
			$margint = round(abs($pageh - (($numh * ($labelh + $vspacing)) - $vspacing) - $margint), 2);

		$pdf = new ProductLabels("",'mm',array($pagew,$pageh));
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('torcu.com');
		$pdf->SetTitle('Opencart labels');
		$pdf->SetSubject('Opencart labels');
		$pdf->SetKeywords('labels, opencart, torcu.com');
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetFont('helvetica','',8);
		$pdf->SetMargins($marginl,$margint);
		$pdf->SetAutoPageBreak(false);
		$pdf->SetDisplayMode('real');

		$j=1;
		$order_pos = 0;
		$current_options_id = -1;
		$total=count($label_list)+count($blanks);
		//echo count($label_list);exit;
		//print(count($productids));
		//die;

		$label_e = array();
		$c = 0;
		for($a=0; $a < count($productids); $a++) {
			
		  if($order_pos < count($productids)){

			$pdf->AddPage();

			$labels=0;
			for($row=1;$row<=$numh;$row++) {

				//if($order_pos >= count($productids)) break;

				for($i=1;$i<=$numw;$i++) {
							
					//if($order_pos >= count($productids)) break;

					$x=$pdf->getX();
					$y=$pdf->getY();

					if(in_array($j,$blanks)){
						$pdf->setXY($x+$labelw,$y);
					}else {


						if($current_options_id != $label_list[$order_pos] && !$sample) {

							$current_options_id = $label_list[$order_pos];

							$label_elements[$c]['product_info']['price_total']  = $label_elements[$c]['product_info']['price'];
							$label_elements[$c]['product_info']['weight_total'] = $label_elements[$c]['product_info']['weight'];
							$label_elements[$c]['product_info']['price_tax']    = $label_elements[$c]['product_info']['price'];
							$label_elements[$c]['product_info']['taxes']        = 0;
							$label_elements[$c]['product_info']['tax']          = array();
							$label_elements[$c]['custom_text']					= array();
							$label_elements[$c]['option_values']				= array();

							$custom_id = 1;
							$rate = array();

						if (isset($labelids[$current_options_id]['pl_options_name'])){
							foreach($labelids[$current_options_id]['pl_options_name'] as $lidon => $option_name) {

								if ($option_name == "_c") {
									$label_elements[$c]['custom_text'][$custom_id]=$labelids[$current_options_id]['pl_options_string'][$lidon];
									$custom_id++;
								}

								if(isset($label_elements[$c]['product_info']['options'][$option_name]['values'])) {
									foreach($label_elements[$c]['product_info']['options'][$option_name]['values'] as $option_data) {
										if ($labelids[$current_options_id]['pl_options_value'][$lidon] == $option_data['name']) {
											$label_elements[$c]['option_values'][$option_name] = $option_data['name'];
											$label_elements[$c]['product_info']['price_total'] += $option_data['price'];
											$label_elements[$c]['product_info']['weight_total'] += $option_data['weight'];
											break;
										} else {
											$label_elements['option_values'][$option_name] = $labelids[$current_options_id]['pl_options_string'][$lidon];
										}
									}
								}
							}
						}

							foreach($rules as $rateid => $rule) {
								$rate[$rateid] = $this->model_localisation_tax_rate->getTaxRate($rule['tax_rate_id']);
								$rate[$rateid]['price'] = ($label_elements[$c]['product_info']['price_total']*(1+($rate[$rateid]['rate']/100))) - $label_elements[$c]['product_info']['price_total'];
								$label_elements[$c]['product_info']['tax'][$rateid] = $rate[$rateid]['price'];
								$label_elements[$c]['product_info']['price_tax'] += $rate[$rateid]['price'];
								//$label_elements['product_info']['price_tax']."\n";
								//$label_elements['product_info']['taxes']."\n";
							}
							$label_elements[$c]['product_info']['taxes']  = $label_elements[$c]['product_info']['price_tax'] - $label_elements[$c]['product_info']['price_total'];

							$weight = $this->getProductWeights($label_elements[$c]['product_info']['weight_total'],$label_elements[$c]['product_info']['weight_class_id']);
							foreach($weight as $unit => $unit_values) {
								$label_elements[$c]['product_info'][$unit] = $unit_values['value'];
							}
						}
						
						//echo '<pre>';print_r($label_elements[$c]);exit;
						$pdf->printLabel($labelw,$labelh,$x,$y,$label_elements[$c]);

						
						$order_pos++;
					}
					$j++;
					if($i<$numw && $hspacing>0)
						$pdf->setX($pdf->getX()+$hspacing);
				}
				if($j>$total) break;
				$pdf->setXY($marginl,$y+$labelh);
				if($row<$numh && $vspacing>0)
					$pdf->setXY($marginl,$y+$labelh+$vspacing);
			}

			}
			$c++;
		}
		//echo '<pre>';print_r($label_elements);exit;
		//die;

		if(!empty($_GET['debugpdf'])) {
			print_r($pdf);
			die();
		}

		if(ob_get_contents()) ob_clean();

		if (($settings['product_labels_download'] || $download) && !$sample)
			$this->response->setOutput($pdf->Output($settings['product_labels_filename'], 'D'));
		else
			$this->response->setOutput($pdf->Output($settings['product_labels_filename'], 'I'));
	}

	// ===================================== CONTROLLER PRODUCT
	public function labels() {

		ob_start();
		require_once(DIR_SYSTEM.'library/product_labels/product_labels.php');

		$this->load->model('setting/setting');
		$this->load->model('module/product_labels');
		$this->load->model('sale/order');
		$settings = $this->model_setting_setting->getSetting('product_labels');
		
		//echo '<pre>';print_r($_POST);exit;
		
		$orientation = $settings['product_labels_default_orientation'];
		$sample 	 = 0;
		$edit		 = 0;
		$templateid  = $settings['product_labels_default_template'];
		$labelid	 = $settings['product_labels_default_label'];
		$orderids	 = array();
		$productid	 = -1;
		$blanks 	 = array();
		$order_info  = array();
		$download	 = $settings['product_labels_download'];
		$border		 = $settings['product_labels_border'];
		$printpreview= 0;

		foreach(array("orientation","templateid","labelid","productid","sample","orderids","blanks","edit","download","border","printpreview") as $key) {
			if(isset($_POST[$key]))
				if($key == "blanks")
					$$key = explode(",",$this->request->post[$key]);
				else
					$$key = $this->request->post[$key];
			if(isset($_GET[$key]))
				if($key == "blanks")
					$$key = explode(",",$this->request->get[$key]);
				else
					$$key = $this->request->get[$key];
		}

		$template_info	= $this->model_module_product_labels->getLabelTemplate($templateid);
		$label_info		= $this->model_module_product_labels->getLabel($labelid);
		$label_elements = json_decode($label_info[0]['data'],TRUE);

		$lw	 	 = $template_info[0]['width'];
		$lh		 = $template_info[0]['height'];
		$pw		 = $template_info[0]['page_w'];
		$ph		 = $template_info[0]['page_h'];
		$nw		 = $template_info[0]['number_h'];
		$nh		 = $template_info[0]['number_v'];
		$rounded = $template_info[0]['rounded'];
		$vspace  = $template_info[0]['space_v'];
		$hspace	 = $template_info[0]['space_h'];
		$mt 	 = $template_info[0]['margin_t'];
		$ml 	 = $template_info[0]['margin_l'];
		if ($sample) {
			$nw=1;
			$nh=1;
			$ph=$lh+2;
			$pw=$lw+2;
			$blanks=array();
			$orderids=1;
			$border=1;
			$label_list = 0;

		} else {

			if ($printpreview) {

				$label_list = array_fill(0, $nw*$nh, -1);
				$border=1;
				$label_elements = array();
				$orientation = "P";
				$label_elements['printpreview'] = 1;
				$labelids = array();

			} else {
				
				$this->load->model('catalog/product');
				$this->load->model('catalog/option');

				$label_elements['product_info'] = $this->model_catalog_product->getProduct($productid);
				$label_elements['product_info']['price_total']  = $label_elements['product_info']['price'];
				$label_elements['product_info']['weight_total'] = $label_elements['product_info']['weight'];
				$label_elements['product_info']['price_tax']    = $label_elements['product_info']['price'];

				$options = $this->model_catalog_product->getProductOptions($productid);

				foreach($options as $ido => $id_option) {
					if (isset($id_option['product_option_value'])) {
						foreach ($id_option['product_option_value'] as $product_option_value => $option) {
							if (!isset($option['name'])) {
								$option_value = $this->model_catalog_option->getOptionValue($option['option_value_id']);
								$options[$ido]['product_option_value'][$product_option_value]['name'] = $option_value['name'];
								$options[$ido]['product_option_value'][$product_option_value]['image'] = $option_value['image'];
							}
						}
					}
				}

				$this->load->model('catalog/manufacturer');
				$manufacturer = $this->model_catalog_manufacturer->getManufacturer($label_elements['product_info']['manufacturer_id']);

				$label_elements['product_info']['manufacturer_image'] = (isset($manufacturer['image']))?$manufacturer['image']:"undefined";
				$label_elements['product_info']['manufacturer'] = (isset($manufacturer['name']))?$manufacturer['name']:"";

				foreach($options as $id_option) {

					$label_elements['option_names'][$id_option['product_option_id']] = strtolower($id_option['name']);

					$label_elements['product_info']['options'][$id_option['product_option_id']]['name']=$id_option['name'];
					if(isset($id_option['product_option_value'])) {
						foreach ($id_option['product_option_value'] as  $tov_id => $t_option_value) {
							$t_option[$tov_id]['name'] = $t_option_value['name'];
							$t_option[$tov_id]['image'] = $t_option_value['image'];
							$t_option[$tov_id]['price'] = (float)($t_option_value['price_prefix'].$t_option_value['price']);
							$t_option[$tov_id]['weight'] = (float)($t_option_value['weight_prefix'].$t_option_value['weight']);
							$label_elements['product_info']['options'][$id_option['product_option_id']]['values']=$t_option;
						}
					} else {
						$label_elements['product_info']['options'][$id_option['product_option_id']]['values']=array();
					}
				}

				$labelids = array();
				$label_list = array();

				foreach ($this->request->post['pl_options_num'] as $id => $option_num) {
					$labelids[$id]['num'] = $option_num;
					for($n=0;$n<$option_num;$n++)
						$label_list[] = $id;
				}
				$total_labels = count($label_list);
				foreach ($this->request->post['pl_options_name'] as $id => $option_name) {
					$keyval = explode(",",$id);
					$labelids[$keyval[0]]['pl_options_name'][$keyval[1]] = $option_name;
				}
				foreach ($this->request->post['pl_options_value'] as $id => $option_value) {
					$keyval = explode(",",$id);
					$labelids[$keyval[0]]['pl_options_value'][$keyval[1]] = urldecode($option_value);
				}
				foreach ($this->request->post['pl_options_string'] as $id => $option_string) {
					$keyval = explode(",",$id);
					$labelids[$keyval[0]]['pl_options_string'][$keyval[1]] = urldecode($option_string);
				}

				$this->load->model('localisation/tax_class');
				$this->load->model('localisation/tax_rate');
				$tax   = $this->model_localisation_tax_class->getTaxClass($label_elements['product_info']['tax_class_id']);
				$taxes = $this->model_localisation_tax_class->getTaxClasses();
				$rules = $this->model_localisation_tax_class->getTaxRules($label_elements['product_info']['tax_class_id']);

			}
		}

		$label_elements['sample'] = $sample;
		$label_elements['rounded'] = $rounded;
		$label_elements['border'] = $border;

		if ($orientation=="P") {
			list($pagew, $pageh, $labelw, $labelh, $numw, $numh, $hspacing, $vspacing, $margint, $marginl) = array($pw, $ph, $lw, $lh, $nw, $nh, $hspace, $vspace, $mt, $ml);
		} else {
			list($pagew, $pageh, $labelw, $labelh, $numw, $numh, $hspacing, $vspacing, $margint, $marginl) = array($ph, $pw, $lh, $lw, $nh, $nw, $vspace, $hspace, $ml, $mt);
		}
		$n = $nw*$nh;

		if($marginl == 'auto' || $sample)
			$marginl = ($pagew - (($numw*($labelw+$hspacing))-$hspacing))/2; //margin left;

		if ($margint == 'auto' || $sample)
			$margint = ($pageh - (($numh*($labelh+$vspacing))-$vspacing))/2; //margin top;

		if ($orientation=="L")
			$margint = round(abs($pageh - (($numh * ($labelh + $vspacing)) - $vspacing) - $margint), 2);

		$pdf = new ProductLabels("",'mm',array($pagew,$pageh));
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('torcu.com');
		$pdf->SetTitle('Opencart labels');
		$pdf->SetSubject('Opencart labels');
		$pdf->SetKeywords('labels, opencart, torcu.com');
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetFont('helvetica','',8);
		$pdf->SetMargins($marginl,$margint);
		$pdf->SetAutoPageBreak(false);
		$pdf->SetDisplayMode('real');

		$j=1;
		$order_pos = 0;
		$current_options_id = -1;
		$total=count($label_list)+count($blanks);

		while($order_pos < count($label_list)) {

			$pdf->AddPage();

			$labels=0;
			for($row=1;$row<=$numh;$row++) {

				if($order_pos >= count($label_list)) break;

				for($i=1;$i<=$numw;$i++) {

					if($order_pos >= count($label_list)) break;

					$x=$pdf->getX();
					$y=$pdf->getY();

					if(in_array($j,$blanks))
						$pdf->setXY($x+$labelw,$y);
					else {

						if($current_options_id != $label_list[$order_pos] && !$sample) {

							$current_options_id = $label_list[$order_pos];

							$label_elements['product_info']['price_total']  = $label_elements['product_info']['price'];
							$label_elements['product_info']['weight_total'] = $label_elements['product_info']['weight'];
							$label_elements['product_info']['price_tax']    = $label_elements['product_info']['price'];
							$label_elements['product_info']['taxes']        = 0;
							$label_elements['product_info']['tax']          = array();
							$label_elements['custom_text']					= array();
							$label_elements['option_values']				= array();

							$custom_id = 1;
							$rate = array();

							foreach($labelids[$current_options_id]['pl_options_name'] as $lidon => $option_name) {

								if ($option_name == "_c") {
									$label_elements['custom_text'][$custom_id]=$labelids[$current_options_id]['pl_options_string'][$lidon];
									$custom_id++;
								}

								if(isset($label_elements['product_info']['options'][$option_name]['values'])) {
									foreach($label_elements['product_info']['options'][$option_name]['values'] as $option_data) {
										if ($labelids[$current_options_id]['pl_options_value'][$lidon] == $option_data['name']) {
											$label_elements['option_values'][$option_name] = $option_data['name'];
											$label_elements['product_info']['price_total'] += $option_data['price'];
											$label_elements['product_info']['weight_total'] += $option_data['weight'];
											break;
										} else {
											$label_elements['option_values'][$option_name] = $labelids[$current_options_id]['pl_options_string'][$lidon];
										}
									}
								}
							}

							foreach($rules as $rateid => $rule) {
								$rate[$rateid] = $this->model_localisation_tax_rate->getTaxRate($rule['tax_rate_id']);
								$rate[$rateid]['price'] = ($label_elements['product_info']['price_total']*(1+($rate[$rateid]['rate']/100))) - $label_elements['product_info']['price_total'];
								$label_elements['product_info']['tax'][$rateid] = $rate[$rateid]['price'];
								$label_elements['product_info']['price_tax'] += $rate[$rateid]['price'];
								//$label_elements['product_info']['price_tax']."\n";
								//$label_elements['product_info']['taxes']."\n";
							}
							$label_elements['product_info']['taxes']  = $label_elements['product_info']['price_tax'] - $label_elements['product_info']['price_total'];

							$weight = $this->getProductWeights($label_elements['product_info']['weight_total'],$label_elements['product_info']['weight_class_id']);
							foreach($weight as $unit => $unit_values) {
								$label_elements['product_info'][$unit] = $unit_values['value'];
							}
						}

						$pdf->printLabel($labelw,$labelh,$x,$y,$label_elements);
						$order_pos++;
					}
					$j++;
					if($i<$numw && $hspacing>0)
						$pdf->setX($pdf->getX()+$hspacing);
				}
				if($j>$total) break;
				$pdf->setXY($marginl,$y+$labelh);
				if($row<$numh && $vspacing>0)
					$pdf->setXY($marginl,$y+$labelh+$vspacing);
			}
		}

		if(!empty($_GET['debugpdf'])) {
			print_r($pdf);
			die();
		}

		if(ob_get_contents()) ob_clean();

		if (($settings['product_labels_download'] || $download) && !$sample)
			$this->response->setOutput($pdf->Output($settings['product_labels_filename'], 'D'));
		else
			$this->response->setOutput($pdf->Output($settings['product_labels_filename'], 'I'));
	}

	public function serialize($data) {
		return $this->alt_json_encode($data);
	}

	public function unserialize($data) {
		return json_decode($data);
	}

	private function alt_json_encode($arr) {
		array_walk_recursive($arr, array('ControllerModuleProductLabels','alt_json_encode_callback'));
        return mb_decode_numericentity(json_encode($arr), array (0x80, 0xffff, 0, 0xffff), 'UTF-8');
	}

	static private function alt_json_encode_callback (&$item, $key) {
		 if (is_string($item))
			 $item = mb_encode_numericentity($item, array (0x80, 0xffff, 0, 0xffff), 'UTF-8');
	}

	public static function toggle($type,$id) {
		$toggle = array(
			"img"	  => array('hide','hide','show','show','show','show','show','hide','hide','hide'),
			"text"	  => array('show','show','hide','show','show','hide','hide','show','hide','show'),
			"rect"	  => array('hide','hide','hide','show','show','show','show','show','show','hide'),
			"barcode" => array('hide','show','hide','show','show','show','show','show','hide','hide'),
			"list" 	  => array('show','hide','hide','show','show','show','show','show','hide','hide'),
		);
		return $toggle[$type][$id];
	}

	public function getLabel()
	{
		$this->language->load('module/product_labels');
		$res = array();
		if(!empty($this->request->get['id'])) {
			$this->load->model('module/product_labels');
			$res=$this->model_module_product_labels->getLabel($this->request->get['id']);
		}
		if (empty($res))
			foreach(array('id','name','data') as $key)
				$res[0][$key]='';

		$this->response->setOutput($this->serialize($res[0]));
	}

	public function saveLabel() {

		$this->language->load('module/product_labels');
		$labeldata = array();
		$keys = array('type','ff','fs','fr','text','img','x','y','w','h','color','fill');

		foreach ($keys as $key)
			if (isset($this->request->post[$key]))
				$$key = $this->request->post[$key];
			else
				$$key = array();

		if ($type) {
			foreach (array_keys($type) as $i)
				foreach ($keys as $key)
					$labeldata[$key][$i] = str_replace("'","&#8217;",${$key}[$i]);
			$labeldata['numrows'] = count(array_keys($type));
		} else
			$labeldata['numrows'] = 0;

		$serialized  = $this->serialize($labeldata);

		if (!$this->user->hasPermission('modify', 'module/product_labels'))
	     	$res['error'] = $this->language->get('error_permission');
		else {
			$this->load->model('module/product_labels');
			if(empty($this->request->get['id']) || !empty($this->request->get['saveas_label_name']))
				$res=$this->model_module_product_labels->setLabel($serialized);
			else
				$res=$this->model_module_product_labels->updateLabel($serialized);
		}
		$this->response->setOutput($res);
	}

	public function getTemplate() {

		$this->language->load('module/product_labels');
		$res = array();

		if(!empty($this->request->get['id'])) {
			$this->load->model('module/product_labels');
			$res=$this->model_module_product_labels->getLabelTemplate($this->request->get['id']);
		}
		if(empty($res)) {
			foreach(array('id','width','height','number_h','number_v','space_h','space_v','rounded') as $key) {
				$res[0][$key]='';
			}
			foreach(array('margin_t','margin_l') as $key) {
				$res[0][$key]='auto';
			}
		}
		$this->response->setOutput(json_encode($res[0]));
	}

	public function saveTemplate() {

		$this->language->load('module/product_labels');
		$res = array();

		if (!$this->user->hasPermission('modify', 'module/product_labels'))
	     	$res['error'] = $this->language->get('error_permission');
		else {
			$this->load->model('module/product_labels');

			foreach(array("pagew","pageh","labelw","labelh","numw","numh","rounded","vspacing","hspacing") as $key)
				if(empty($this->request->get[$key]))
					$this->request->get[$key] = 0;

			foreach(array("margint","marginl") as $key)
				if(!is_numeric($this->request->get[$key]) || $this->request->get[$key] == "")
					$this->request->get[$key] = 'auto';

			if(empty($this->request->get['id']))
				$res=$this->model_module_product_labels->setLabelTemplate();
			else
				$res=$this->model_module_product_labels->updateLabelTemplate();
		}
		$this->response->setOutput($res);

	}

	public function deleteTemplate() {

		$this->language->load('module/product_labels');
		$res = array();

		if (!$this->user->hasPermission('modify', 'module/product_labels'))
	     	$res['error'] = $this->language->get('error_permission');
		else
			$this->load->model('module/product_labels');
			if(!empty($this->request->get['id']))
				$res=$this->model_module_product_labels->deleteTemplate();
		$this->response->setOutput($res);
	}

	public function deleteLabel() {

		$this->language->load('module/product_labels');
		$res = array();

		if (!$this->user->hasPermission('modify', 'module/product_labels'))
	     	$res['error'] = $this->language->get('error_permission');
		else {
			$this->load->model('module/product_labels');
			if(!empty($this->request->get['id']))
				$res=$this->model_module_product_labels->deleteLabel();
		}
		$this->response->setOutput($res);
	}

	private function uuid()
	{
		$this->load->model('setting/setting');
		$cid = $this->model_setting_setting->getSetting('cid');
		if(isset($cid['cid_uuid']) && (preg_match('/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/',$cid['cid_uuid'])))
			return $cid['cid_uuid'];
		else {
			$uuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
				mt_rand(0, 0xffff), mt_rand(0, 0xffff),
				mt_rand(0, 0xffff),
				mt_rand(0, 0x0fff) | 0x4000,
				mt_rand(0, 0x3fff) | 0x8000,
				mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
			);
			$this->model_setting_setting->editSetting('cid', array('cid_uuid'=>$uuid));
			return $uuid;
		}
	}


	public function combineOptions($options, $prev='') {
		$result = array();
		$option = array_shift($options);
		foreach($option as $current) {
			if($options) {
				$result = array_merge($result, self::combineOptions($options, $prev.$current.':::'));
			} else {
				$result[] = $prev.$current;
			}
		}
		return $result;
	}

	public function getLabelOptions() {

		$this->load->model('catalog/product');
		$this->load->model('catalog/option');

		$product_id 	    = $this->request->get['product_id'];
		$product 	    = $this->model_catalog_product->getProduct($product_id);
		$product['options'] = $this->model_catalog_product->getProductOptions($product_id);

		$res= array();
		$values=array();
		$i=0;
		foreach ($product['options'] as $product_option_id => $product_option) {
			if (isset($product_option['product_option_value'])) {
				foreach ($product_option['product_option_value'] as $product_option_value => $option) {
					if (!isset($option['name'])) {
						$option_value = $this->model_catalog_option->getOptionValue($option['option_value_id']);
						$product['options'][$product_option_id]['product_option_value'][$product_option_value]['name'] = $option_value['name'];
					}
				}
			}
		}
		foreach ($product['options'] as $opt) {
			if (isset($opt['product_option_value'])) {
				$aOptions = array();
				$res['options'][$opt['product_option_id']] = array('id'=>$opt['option_id'],'name' => $opt['name']);
				foreach ($opt['product_option_value'] as $product_option_value => $option_value) {
					$res['options'][$opt['product_option_id']]['values'][] = $option_value['name'];
					$values[$i][] = $opt['product_option_id'].'::'.$option_value['name'];
				}
				$i++;
			}
		}

		$res['combinations']=self::combineOptions($values);
		//echo '<pre>';print_r($res['combinations']);exit;
		$this->response->setOutput(json_encode($res));
	}

	public function getProductWeights($weight,$weight_id) {
		$sSQL = "SELECT wc.weight_class_id, value, wcd.title, wcd.unit\n";
		$sSQL.= "FROM `".DB_PREFIX."weight_class` wc\n";
		$sSQL.= "LEFT JOIN `".DB_PREFIX."weight_class_description` wcd ON wcd.weight_class_id = wc.weight_class_id LIMIT 0, 30 ";
		$query = $this->db->query($sSQL);
		$res = array();
		foreach($query->rows as $row)
			if($row['weight_class_id']==$weight_id)
				$factor=$row['value'];

		foreach($query->rows as $row)
			$res[$row['unit']]['value'] = $weight*($row['value']/$factor);

		return $res;
	}

	public function checkUpdate() {
		$res = "";
		$this->response->setOutput($res);
	}

	public function getUpdateInfo() {
		$notes = "";
		$this->response->setOutput($notes);
	}

	public function checkInstall()
	{
		$res = array();
		$i=0;
		$cid = $this->uuid();
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->update_server.'/update/hash/opencart2/productlabels?c='.md5('2d52ee2c1b19b9b971098ae7dc9e78a8'.gmdate('YmdH')).'&cid='.$cid.'&d='.$_SERVER['SERVER_NAME']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		$files = json_decode(curl_exec($ch));
		foreach ($files as $oFile) {
			eval('$file = '.$oFile->local.';');
			$res[$i]['local'] = $file;
			$res[$i]['remote'] = $oFile->remote;
			$res[$i]['hash'] = $oFile->hash;

			if (!file_exists($file)) {
				$res[$i]['valid'] = -1;
			} else {
				if ($oFile->hash == sha1_file($file)) {
					$res[$i]['valid'] = 1;
				} else {
					$res[$i]['valid'] = 0;
				}
			}
			$i++;
		}
		die(json_encode($res));
	}
}
