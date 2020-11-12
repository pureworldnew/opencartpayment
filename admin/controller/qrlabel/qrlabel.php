<?php
class ControllerQrlabelQrlabel extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('qrlabel/qrlabel');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('qrlabel/qrlabel');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			
		}
		
		if (($this->request->server['REQUEST_METHOD'] == 'GET')) {
			if (isset($this->request->get['gen'])) {
				$filter = array();
				$filter['filter_category_id'] = isset($this->request->get['filter_category_id'])?$this->request->get['filter_category_id']:false;
				$filter['filter_manufacturer_id'] = isset($this->request->get['filter_manufacturer_id'])?$this->request->get['filter_manufacturer_id']:false;
				$filter['filter_model'] = isset($this->request->get['filter_model'])?$this->request->get['filter_model']:false;
				$filter['filter_location'] = isset($this->request->get['filter_location'])?$this->request->get['filter_location']:false;
				$filter['gen'] = isset($this->request->get['gen'])?$this->request->get['gen']:false;
				
				$sets = array();
				$sets['filter_cpp'] = isset($this->request->get['filter_cpp'])?$this->request->get['filter_cpp']:false;
				$sets['filter_custom'] = isset($this->request->get['filter_custom'])?$this->request->get['filter_custom']:null;
				$sets['filter_text'] = isset($this->request->get['filter_text'])?$this->request->get['filter_text']:'';
				$sets['default_format'] = isset($this->request->get['default_format'])?$this->request->get['default_format']:null;
				$this->model_qrlabel_qrlabel->generateReport($filter,$sets);
			}
			
			//echo 'hehehhe';
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_select_all'] = $this->language->get('text_select_all');
		$data['text_unselect_all'] = $this->language->get('text_unselect_all');

		$data['entry_model'] = $this->language->get('entry_model');
		$data['entry_category'] = $this->language->get('entry_category');
		$data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
		$data['button_generate'] = $this->language->get('entry_generate');
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('qrlabel/qrlabel', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		/**
		* 
		* @Product Label
		* 
		*/
		$this->load->language('module/product_labels');
		//$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addScript('view/javascript/product_labels/pdfobject.js');
		$this->document->addScript('view/javascript/product_labels/jquery.colorPicker.js');
		$this->document->addScript('view/javascript/product_labels/product_labels.min.js');
		$this->document->addStyle('view/stylesheet/product_labels/stylesheet.css');
		$store_id = 0;

		$this->load->model('module/product_labels');
		$this->load->model('setting/setting');
		
		$data['settings'] = $this->model_setting_setting->getSetting('product_labels');
		$data['labels'] = $this->model_module_product_labels->getLabels();
		$data['label_templates'] = $this->model_module_product_labels->getLabelTemplates();
		
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
		
		$data['text_portrait'] = $this->language->get('text_portrait');
		$data['text_landscape'] = $this->language->get('text_landscape');

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
		
		$data['row']  = 0;
		$data['type'] = "text";
		$data['i']    = 1;
		
		$data['update_needed']   		= $this->language->get('text_update_needed');
		$data['new_version']   			= $this->language->get('text_new_version');
		$data['please_update']   		= $this->language->get('text_please_update');
		$data['this_version'] 			= "1.0";
		
		/**
		* 
		* @Product Label
		* 
		*/

		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} elseif (isset($this->error['warning'])) {
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
		
		if (isset($this->request->get['filter_model'])) {
			$data['filter_model'] = $this->request->get['filter_model'];
		} else {
			$data['filter_model'] = '';
		}
		
		if (isset($this->request->get['filter_location'])) {
			$data['filter_location'] = $this->request->get['filter_location'];
		} else {
			$data['filter_location'] = '';
		}
		
		if (isset($this->request->get['filter_custom'])) {
			$data['filter_custom'] = $this->request->get['filter_custom'];
		} else {
			$data['filter_custom'] = '';
		}
		
		if (isset($this->request->get['filter_text'])) {
			$data['filter_text'] = $this->request->get['filter_text'];
		} else {
			$data['filter_text'] = '';
		}
		
		if (isset($this->request->get['filter_category_id'])) {
			$data['filter_category_id'] = $this->request->get['filter_category_id'];
		} else {
			$data['filter_category_id'] = '';
		}
		
		
		if (isset($this->request->get['filter_manufacturer_id'])) {
			$data['filter_manufacturer_id'] = $this->request->get['filter_manufacturer_id'];
		} else {
			$data['filter_manufacturer_id'] = '';
		}
		
		$data['qr_lists'] = array();
		$data['pagination'] = '';
		$data['results'] = '';
		$data['link_d'] = $this->url->link('qrlabel/qrlabel', 'token=' . $this->session->data['token'], 'SSL');

		

		
		$this->load->model('module/adv_profit_module');
		$data['categories'] = $this->model_module_adv_profit_module->getProductsCategories(0);
		
		$this->load->model('catalog/manufacturer');
		$data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();
		
		$data['token'] = $this->request->get['token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('qrlabel/qrlabel.tpl', $data));
	}
	
	public function generateQrFromCsv() {
		
		$this->load->language('qrlabel/qrlabel');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('qrlabel/qrlabel');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$file = (isset($this->request->post['filepath']) && !empty($this->request->post['filepath']))?$this->request->post['filepath']:false;
			$filetype = (isset($this->request->post['ispdf']) && !empty($this->request->post['ispdf']))?$this->request->post['ispdf']:false;
			$wmodel = (isset($this->request->post['wmodel']) && !empty($this->request->post['wmodel']))?$this->request->post['wmodel']:false;
			if ($wmodel == 'true')
				$this->model_qrlabel_qrlabel->impCSVFileWithOutModel($file,$filetype);	
			else
				$this->model_qrlabel_qrlabel->impCSVFile($file,$filetype);
			return;
		}
		
		$data['cancel'] = $this->url->link('qrlabel/qrlabel/generateQrFromCsv', 'token=' . $this->session->data['token'], 'SSL');
		$data['heading_title'] = 'Qrcode Generator From CSV';

		$data['button_cancel']="Cancel";

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
		'text'      => $this->language->get('text_home'),
		'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
		'separator' => false
		);

		$data['breadcrumbs'][] = array(
		'text'      => 'Qr Generator From CSV',
		'href'      => $this->url->link('qrlabel/qrlabel/generateQrFromCsv', 'token=' . $this->session->data['token'], 'SSL'),       		
		'separator' => ' :: '
		);

		$data['action'] = $this->url->link('qrlabel/qrlabel/generateQrFromCsv', 'token=' . $this->session->data['token'], 'SSL');
		$data['action_csv'] = $this->url->link('qrlabel/qrlabel/generateQrFromCsv', 'token=' . $this->session->data['token'], 'SSL');

		$data['token'] = $this->request->get['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('qrlabel/qrlabel_import.tpl', $data));
	}
	
	public function impCSV() {
		$res = array();
		if (!empty($this->request->files)) {
			$uploads_dir = DIR_UPLOAD;
			$error = $_FILES["importfile"]["error"];
			    if ($error == UPLOAD_ERR_OK) {
			        $tmp_name = $_FILES["importfile"]["tmp_name"];
			        // basename() may prevent filesystem traversal attacks;
			        // further validation/sanitation of the filename may be appropriate
			        $name = basename($_FILES["importfile"]["name"]);
			        move_uploaded_file($tmp_name, "$uploads_dir/$name");
			    }
		
			echo $uploads_dir.'/'.$name;
			return;
		}
		return null;
	}
}
