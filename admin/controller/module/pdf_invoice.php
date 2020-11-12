<?php
class ControllerModulePdfInvoice extends Controller {
  const MODULE = 'pdf_invoice';
  const PREFIX = 'pdf_invoice';
  const MOD_FILE = 'pdf_invoice_pro';
  
	private $error = array();
  private $token;
	
  public function __construct($registry) {
		parent::__construct($registry);
    
    $this->token = isset($this->session->data['user_token']) ? 'user_token='.$this->session->data['user_token'] : 'token='.$this->session->data['token'];
	}
  
	public function index() {
	  $asset_path = 'view/pdf_invoice_pro/';
    if (defined('JPATH_MIJOSHOP_OC') && version_compare(VERSION, '2', '>=')) {
      $asset_path = 'admin/'.$asset_path;
    }
		$data['_language'] = $this->language;
    $data['_img_path'] = $asset_path . 'img/';
    if (defined('JPATH_MIJOSHOP_OC') && !version_compare(VERSION, '2', '>=')) {
      $data['_img_path'] = 'admin/' . $asset_path . 'img/';
    }
		$data['_config'] = $this->config;
		$data['_url'] = $this->url;
		$data['token'] = $this->token;
  	$data['OC_V2'] = version_compare(VERSION, '2', '>=');

    if (version_compare(VERSION, '3', '>=')) {
      $this->load->language('extension/module/pdf_invoice');
    } else {
      $this->load->language('module/pdf_invoice');
    }

		if (!version_compare(VERSION, '2', '>=')) {
			$this->document->addStyle($asset_path . 'awesome/css/font-awesome.min.css');
			$this->document->addStyle($asset_path . 'bootstrap.min.css');
			$this->document->addStyle($asset_path . 'bootstrap-theme.min.css');
			$this->document->addScript($asset_path . 'bootstrap.min.js');
		} else {
			$this->document->addScript($asset_path . 'jquery-ui.min.js');
		}
		$this->document->addScript($asset_path . 'prettyCheckable.js');
		$this->document->addScript($asset_path . 'jqueryFileTree.js');
		$this->document->addScript($asset_path . 'spectrum.js');
		$this->document->addScript($asset_path . 'itoggle.js');
		$this->document->addStyle($asset_path . 'prettyCheckable.css');
		$this->document->addStyle($asset_path . 'jqueryFileTree.css');
		$this->document->addStyle($asset_path . 'spectrum.css');
		$this->document->addStyle($asset_path . 'style.css');
    
		if (version_compare(VERSION, '2.3', '>=')) {
			$this->document->addScript('view/javascript/summernote/summernote.js');
      $this->document->addStyle('view/javascript/summernote/summernote.css');
			$this->document->addScript('view/javascript/summernote/opencart.js');
    }
		
		$this->document->setTitle(strip_tags($this->language->get('heading_title')));
		
		// check tables
		if (version_compare(VERSION, '2.3', '>=') && !$this->config->has('pdf_invoice_template')) {
      $this->install('redir');
    } else {
      $this->db_tables();
    }
    
		$this->load->model('setting/setting');
		
		// multi-stores management
		$this->load->model('setting/store');
		$data['stores'] = array();
		$data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->config->get('config_name')
		);

		$stores = $this->model_setting_store->getStores();

		foreach ($stores as $store) {
			$action = array();

			$data['stores'][] = array(
				'store_id' => $store['store_id'],
				'name'     => $store['name']
			);
		}
		
		$data['store_id'] = $store_id = 0;
		
		// Overwrite store settings
		if (isset($this->request->get['store_id']) && $this->request->get['store_id']) {
			$data['store_id'] = $store_id = (int) $this->request->get['store_id'];
			
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '".$store_id."'");
			
			foreach ($query->rows as $setting) {
				if (!$setting['serialized']) {
					$this->config->set($setting['key'], $setting['value']);
				} else if (version_compare(VERSION, '2.1', '>=')) {
					$this->config->set($setting['key'], json_decode($setting['value'], true));
				} else {
					$this->config->set($setting['key'], unserialize($setting['value']));
				}
			}
		}
		
		// handle post
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			if (isset($_POST['customer_groups'])){
				foreach ($_POST['customer_groups'] as $groupid => $group) { 
					$this->db->query("UPDATE " . DB_PREFIX . "customer_group SET company_id_display = '" . isset($group['company_id_display']) . "', company_id_required = '" . isset($group['company_id_required']) . "', tax_id_display = '" . isset($group['tax_id_display']) . "', tax_id_required = '" . isset($group['tax_id_required']) . "' WHERE customer_group_id = '" . (int)$groupid . "'");
				}
			}
			
			$this->model_setting_setting->editSetting('pdf_invoice', $this->request->post, $store_id);		
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$redirect_store = '';
      
			if ($store_id) {
				$redirect_store = '&store_id=' . $store_id;
      }
      
			if (version_compare(VERSION, '2', '>=')) {
				$this->response->redirect($this->url->link('module/pdf_invoice', $this->token . $redirect_store, 'SSL'));
			} else {
				$this->redirect($this->url->link('module/pdf_invoice', $this->token . $redirect_store, 'SSL'));
			}
		}
    
    // delete extension/module folder on OC 1.5
    if (version_compare(VERSION, '2', '<') && is_dir(DIR_APPLICATION.'controller/extension/module')) {
      @rename(DIR_APPLICATION.'controller/extension/module', DIR_APPLICATION.'controller/extension/.seo_package');
      if (version_compare(VERSION, '2', '<') && is_dir(DIR_APPLICATION.'controller/extension/module')) {
        $this->session->data['error'] = 'OC v1.5 - Please delete the folder ' . DIR_APPLICATION.'controller/extension/module';
      }
    }
    
		// version check
		foreach (array(self::MOD_FILE, 'a_'.self::MOD_FILE, 'z_'.self::MOD_FILE) as $mod_file) {
      if (is_file(DIR_SYSTEM.'../vqmod/xml/'.$mod_file.'.xml')) {
        $data['module_version'] = @simplexml_load_file(DIR_SYSTEM.'../vqmod/xml/'.$mod_file.'.xml')->version;
        $data['module_type'] = 'vqmod';
        break;
      } else if (is_file(DIR_SYSTEM.'../system/'.$mod_file.'.ocmod.xml')) {
        $data['module_version'] = @simplexml_load_file(DIR_SYSTEM.'../system/'.$mod_file.'.ocmod.xml')->version;
        $data['module_type'] = 'ocmod';
        break;
      } else {
        $data['module_version'] = 'not found';
        $data['module_type'] = '';
      }
		}
    
    if (!extension_loaded('mbstring')) {
      $this->error['warning'] = 'Warning : PHP extension <b>mbstring</b> not loaded, make sure to enable this extension in order to use correctly the module.';
    }
    
    if (is_file(DIR_SYSTEM.'../vqmod/xml/'.self::MOD_FILE.'.xml') && is_file(DIR_SYSTEM.'../system/'.self::MOD_FILE.'.ocmod.xml')) {
      $this->error['warning'] = 'Warning : both vqmod and ocmod version are installed<br/>- delete /vqmod/xml/'.self::MOD_FILE.'.xml if you want to use ocmod version<br/>- or delete /system/'.self::MOD_FILE.'.ocmod.xml if you want to use vqmod version';
    }
		
		$this->load->model('localisation/language');
		$data['languages'] = $languages = $this->model_localisation_language->getLanguages();
		
    foreach ($data['languages'] as &$tpl_lng) {
      if (version_compare(VERSION, '2.2', '>=')) {
        $tpl_lng['image'] = 'language/'.$tpl_lng['code'].'/'.$tpl_lng['code'].'.png';
      } else {
        $tpl_lng['image'] = 'view/image/flags/'. $tpl_lng['image'];
      }
    }
		
		// module checks
    $modification_active = false;
    
    if (!$modification_active) {
      $this->session->data['error'] = 'Module modification are not applied<br/>- if you installed ocmod version, go to extensions > modifications and push refresh button<br/>- if you installed vqmod version, make sure vqmod is correctly installed and working';
    }
    
		if (!is_dir(DIR_SYSTEM . '../' . $this->config->get('pdf_invoice_backup_folder'))) {
      mkdir(DIR_SYSTEM . '../' . $this->config->get('pdf_invoice_backup_folder'), 0777, true);
    }
    
		if (!is_writable(DIR_SYSTEM . '../' . $this->config->get('pdf_invoice_backup_folder'))) {
			$this->error['warning'] = 'Warning : backup folder is not writable, please change the chmod of this folder to 766';
    }
		
    if (!is_file(DIR_SYSTEM . 'library/mpdf/mpdf.php')) {
			$this->error['warning'] = 'Mpdf library not detected, please go on module page to download the "Libraries v1.x" package from <a href="http://www.opencart.com/index.php?route=extension/extension/info&extension_id=15075" target="_blank">module page</a> and upload it on your server';
    }
    
		foreach ($languages as $language) {
      if (defined('_JEXEC')) {
				$lg_folder = $language['locale'];
			} else {
        $lg_folder = !empty($language['directory']) ? $language['directory'] : $language['code'];
      }
      
			if (!is_file(DIR_SYSTEM . '../catalog/language/' . $lg_folder . '/module/pdf_invoice.php')) {
				$this->error['warning'] = 'Language file missing for '.$language['name'] .' language, please follow this procedure : <br />- Check if this language is included in the module package, in <b>extra languages/</b> folder<br />- If it doesn\'t exist, just copy the english file and open it to translate it.<br />- Copy <b>pdf_invoice.php</b> language file into <b>/catalog/language/'.$lg_folder.'/module/</b>';
      }
		}
		// end module checks
		
		$data['heading_title'] = strip_tags($this->language->get('heading_title'));
    
	    // Mijoshop specific
	    if (defined('_JEXEC')) {
	       $data['button_savenew'] = $this->language->get('button_savenew');
				 $data['button_saveclose'] = $this->language->get('button_saveclose');
	    }
		
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
		$data['token'] = $this->token;
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->session->data['error'])) {
			$data['error'] = $this->session->data['error'];
		
			unset($this->session->data['error']);
		} else {
			$data['error'] = '';
		}
		
 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
        'text'      => $this->language->get('text_home'),
        'href'      => $this->url->link('common/home', $this->token, 'SSL'),
      	'separator' => false
   		);

      if (version_compare(VERSION, '3', '>=')) {
        $extension_link = $this->url->link('marketplace/extension', 'type=payment&' . $this->token, 'SSL');
      } else if (version_compare(VERSION, '2.3', '>=')) {
        $extension_link = $this->url->link('extension/extension', 'type=payment&' . $this->token, 'SSL');
			} else {
        $extension_link = $this->url->link('extension/payment', $this->token, 'SSL');
			}
      
   		$data['breadcrumbs'][] = array(
       	'text'      => $this->language->get('text_module'),
        'href'      => $extension_link,
      	'separator' => ' :: '
   		);
		
   		$data['breadcrumbs'][] = array(
       	'text'      => strip_tags($this->language->get('heading_title')),
        'href'      => $this->url->link('module/pdf_invoice', $this->token, 'SSL'),
        'separator' => ' :: '
   		);
		
		$data['action'] = $this->url->link('module/pdf_invoice', $this->token . '&store_id=' . $store_id, 'SSL');
		
		$data['cancel'] = $this->url->link('extension/module', $this->token, 'SSL');
		
		$data['modules'] = array();
		
		// customer groups
    if (version_compare(VERSION, '2.1', '>=')) {
      $this->load->model('customer/customer_group');
      $data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

    } else {
      $this->load->model('sale/customer_group');
      $data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
    }
		
		$data['group_settings'] = false;
		if ($this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "customer_group` LIKE 'tax_id_display'")->row) {
			$data['group_settings'] = true;
		}
		
		/* gestion des variables */
		
		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
    
    $this->load->model('localisation/return_status');
		$data['return_statuses'] = $this->model_localisation_return_status->getReturnStatuses();
    
    $data['icons'] = array();
    
		if (@scandir(DIR_IMAGE . 'invoice')) {
      $data['icons'] = array_diff(scandir(DIR_IMAGE . 'invoice'), array('..', '.'));
    }
		
		// Tab 1 - main settings
		if (isset($this->request->post['pdf_invoice_mail'])) {
			$data['pdf_invoice_mail'] = $this->request->post['pdf_invoice_mail'] ? true : false;
		} else {
			$data['pdf_invoice_mail'] = $this->config->get('pdf_invoice_mail');
		}
		
		if (isset($this->request->post['pdf_invoice_adminalertcopy'])) {
			$data['pdf_invoice_adminalertcopy'] = $this->request->post['pdf_invoice_adminalertcopy'] ? true : false;
		} else {
			$data['pdf_invoice_adminalertcopy'] = $this->config->get('pdf_invoice_adminalertcopy');
		}
		
		if (isset($this->request->post['pdf_invoice_admincopy'])) {
			$data['pdf_invoice_admincopy'] = $this->request->post['pdf_invoice_admincopy'] ? true : false;
		} else {
			$data['pdf_invoice_admincopy'] = $this->config->get('pdf_invoice_admincopy');
		}
		
		if (isset($this->request->post['pdf_invoice_invoiced'])) {
			$data['pdf_invoice_invoiced'] = $this->request->post['pdf_invoice_invoiced'] ? true : false;
		} else {
			$data['pdf_invoice_invoiced'] = $this->config->get('pdf_invoice_invoiced');
		}
		
		if (isset($this->request->post['pdf_invoice_auto_generate'])) {
			$data['pdf_invoice_auto_generate'] = $this->request->post['pdf_invoice_auto_generate'] ? true : false;
		} else {
			$data['pdf_invoice_auto_generate'] = $this->config->get('pdf_invoice_auto_generate');
		}
		
		if (isset($this->request->post['pdf_invoice_manual_inv_no'])) {
			$data['pdf_invoice_manual_inv_no'] = $this->request->post['pdf_invoice_manual_inv_no'] ? true : false;
		} else {
			$data['pdf_invoice_manual_inv_no'] = $this->config->get('pdf_invoice_manual_inv_no');
		}
		
		if (isset($this->request->post['pdf_invoice_vat_number'])) {
			$data['pdf_invoice_vat_number'] = $this->request->post['pdf_invoice_vat_number'];
		} else {
			$data['pdf_invoice_vat_number'] = $this->config->get('pdf_invoice_vat_number');
		}
		
		if (isset($this->request->post['pdf_invoice_company_id'])) {
			$data['pdf_invoice_company_id'] = $this->request->post['pdf_invoice_company_id'];
		} else {
			$data['pdf_invoice_company_id'] = $this->config->get('pdf_invoice_company_id');
		}
    
    $data['custom_fields'] = false;
    if (version_compare(VERSION, '2', '>=')) {
      if (version_compare(VERSION, '2.1', '>=')) {
        $this->load->model('customer/custom_field');
        $data['custom_fields'] = $this->model_customer_custom_field->getCustomFields();
      } else {
        $this->load->model('sale/custom_field');
        $data['custom_fields'] = $this->model_sale_custom_field->getCustomFields();
      }
      
      foreach($data['custom_fields'] as $k => $custom_field) {
        if(!$custom_field['status']) {
          unset( $data['custom_fields'][$k]);
        }
      }
    }
    
		if (isset($this->request->post['pdf_invoice_tax'])) {
			$data['pdf_invoice_tax'] = $this->request->post['pdf_invoice_tax'] ? true : false;
		} else {
			$data['pdf_invoice_tax'] = $this->config->get('pdf_invoice_tax');
		}
		
		if (isset($this->request->post['pdf_invoice_total_tax'])) {
			$data['pdf_invoice_total_tax'] = $this->request->post['pdf_invoice_total_tax'] ? true : false;
		} else {
			$data['pdf_invoice_total_tax'] = $this->config->get('pdf_invoice_total_tax');
		}
		
		$this->load->model('localisation/tax_class');
		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
		
		
		if (isset($this->request->post['pdf_invoice_totals_tax'])) {
			$data['pdf_invoice_totals_tax'] = $this->request->post['pdf_invoice_totals_tax'];
		} else {
			$data['pdf_invoice_totals_tax'] = $this->config->get('pdf_invoice_totals_tax');
		}
		
		if (isset($this->request->post['pdf_invoice_customerid'])) {
			$data['pdf_invoice_customerid'] = $this->request->post['pdf_invoice_customerid'] ? true : false;
		} else {
			$data['pdf_invoice_customerid'] = $this->config->get('pdf_invoice_customerid');
		}
		
		if (isset($this->request->post['pdf_invoice_customerprefix'])) {
      		$data['pdf_invoice_customerprefix'] = $this->request->post['pdf_invoice_customerprefix'];
    	} else { 
			$data['pdf_invoice_customerprefix'] = $this->config->get('pdf_invoice_customerprefix');
		}
		
		if (isset($this->request->post['pdf_invoice_customersize'])) {
      		$data['pdf_invoice_customersize'] = $this->request->post['pdf_invoice_customersize'];
    	} else { 
			$data['pdf_invoice_customersize'] = $this->config->get('pdf_invoice_customersize');
		}
		
		if (isset($this->request->post['pdf_invoice_auto_notify'])) {
      $data['pdf_invoice_auto_notify'] = $this->request->post['pdf_invoice_auto_notify'];
    } else { 
			$data['pdf_invoice_auto_notify'] = $this->config->get('pdf_invoice_auto_notify');
		}
    
    if (isset($this->request->post['pdf_invoice_return_pdf'])) {
      $data['pdf_invoice_return_pdf'] = $this->request->post['pdf_invoice_return_pdf'];
    } else { 
			$data['pdf_invoice_return_pdf'] = $this->config->get('pdf_invoice_return_pdf');
		}
		
		if (isset($this->request->post['pdf_invoice_duedate'])) {
			$data['pdf_invoice_duedate'] = $this->request->post['pdf_invoice_duedate'];
		} else {
			$data['pdf_invoice_duedate'] = $this->config->get('pdf_invoice_duedate');
		}
    
    if (isset($this->request->post['pdf_invoice_duedate_invoice'])) {
			$data['pdf_invoice_duedate_invoice'] = $this->request->post['pdf_invoice_duedate_invoice'];
		} else {
			$data['pdf_invoice_duedate_invoice'] = $this->config->get('pdf_invoice_duedate_invoice');
		}
		
		if (isset($this->request->post['pdf_invoice_adminlang'])) {
			$data['pdf_invoice_adminlang'] = $this->request->post['pdf_invoice_adminlang'];
		} else {
			$data['pdf_invoice_adminlang'] = $this->config->get('pdf_invoice_adminlang');
		}
		
		if (isset($this->request->post['pdf_invoice_force_lang'])) {
			$data['pdf_invoice_force_lang'] = $this->request->post['pdf_invoice_force_lang'];
		} else {
			$data['pdf_invoice_force_lang'] = $this->config->get('pdf_invoice_force_lang');
		}
    
    if (isset($this->request->post['pdf_invoice_display_mode'])) {
			$data['pdf_invoice_display_mode'] = $this->request->post['pdf_invoice_display_mode'];
		} else {
			$data['pdf_invoice_display_mode'] = $this->config->get('pdf_invoice_display_mode');
		}
		
		if (isset($this->request->post['pdf_invoice_icon'])) {
			$data['pdf_invoice_icon'] = $this->request->post['pdf_invoice_icon'];
		} else {
			$data['pdf_invoice_icon'] = $this->config->get('pdf_invoice_icon');
		}
		
		// Tab 2 - invoice settings
		$this->load->model('tool/image');
		
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 200, 60);
		
		if (isset($this->request->post['pdf_invoice_logo'])) {
			$data['pdf_invoice_logo'] = $this->request->post['pdf_invoice_logo'];
		} else {
			$data['pdf_invoice_logo'] = $this->config->get('pdf_invoice_logo');
		}
		
		if (isset($this->request->post['pdf_invoice_logo']) && file_exists(DIR_IMAGE . $this->request->post['pdf_invoice_logo'])) {
			$data['thumb_header'] = $this->model_tool_image->resize($this->request->post['pdf_invoice_logo'], 200, 60);
		} elseif ($this->config->get('pdf_invoice_logo') && file_exists(DIR_IMAGE . $this->config->get('pdf_invoice_logo'))) {
			$data['thumb_header'] = $this->model_tool_image->resize($this->config->get('pdf_invoice_logo'), 200, 60);
		} else {
			if(version_compare(VERSION, '2', '>=')) {
				$data['thumb_header'] = $this->model_tool_image->resize('no_image.png', 200, 60);
			} else {
				$data['thumb_header'] = $this->model_tool_image->resize('no_image.jpg', 200, 60);
			}
		}
    
    if (isset($this->request->post['pdf_invoice_watermark'])) {
			$data['pdf_invoice_watermark'] = $this->request->post['pdf_invoice_watermark'];
		} else {
			$data['pdf_invoice_watermark'] = $this->config->get('pdf_invoice_watermark');
		}
    
    if (isset($this->request->post['pdf_invoice_watermark']) && file_exists(DIR_IMAGE . $this->request->post['pdf_invoice_watermark'])) {
			$data['thumb_watermark'] = $this->model_tool_image->resize($this->request->post['pdf_invoice_watermark'], 200, 100);
		} elseif ($this->config->get('pdf_invoice_watermark') && file_exists(DIR_IMAGE . $this->config->get('pdf_invoice_watermark'))) {
			$data['thumb_watermark'] = $this->model_tool_image->resize($this->config->get('pdf_invoice_watermark'), 200, 100);
		} else {
			if(version_compare(VERSION, '2', '>=')) {
				$data['thumb_watermark'] = $this->model_tool_image->resize('no_image.png', 200, 100);
			} else {
				$data['thumb_watermark'] = $this->model_tool_image->resize('no_image.jpg', 200, 100);
			}
		}
    
		$data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 200, 60);
		
		if (isset($this->request->post['pdf_invoice_filename_prefix'])) {
			$data['pdf_invoice_filename_prefix'] = $this->request->post['pdf_invoice_filename_prefix'] ? true : false;
		} else {
			$data['pdf_invoice_filename_prefix'] = $this->config->get('pdf_invoice_filename_prefix');
		}
		
		if (isset($this->request->post['pdf_invoice_filename_invnum'])) {
			$data['pdf_invoice_filename_invnum'] = $this->request->post['pdf_invoice_filename_invnum'] ? true : false;
		} else {
			$data['pdf_invoice_filename_invnum'] = $this->config->get('pdf_invoice_filename_invnum');
		}
		
		if (isset($this->request->post['pdf_invoice_filename_ordnum'])) {
			$data['pdf_invoice_filename_ordnum'] = $this->request->post['pdf_invoice_filename_ordnum'] ? true : false;
		} else {
			$data['pdf_invoice_filename_ordnum'] = $this->config->get('pdf_invoice_filename_ordnum');
		}
		
		$data['templates'] = array();
		
		$templates = glob(DIR_CATALOG . 'view/theme/default/template/pdf/*.tpl');
		foreach ($templates as $tpl) {
			$data['templates'][] = basename($tpl, '.tpl');
		}
		
		if (isset($this->request->post['pdf_invoice_template'])) {
			$data['pdf_invoice_template'] = $this->request->post['pdf_invoice_template'];
		} else {
			$data['pdf_invoice_template'] = $this->config->get('pdf_invoice_template');
		}
		
    $data['barcode_types'] = array('QR', 'EAN13', 'ISBN','ISSN','UPCA','UPCE','EAN8','IMB','RM4SCC','KIX','POSTNET','PLANET','C128A','C128B','C128C','EAN128A','EAN128B','EAN128C','C39','C39+','C39E','C39E+','I25','I25+','I25B','C93','MSI','MSI+','CODABAR','CODE11');
    
    // barcode
    if (isset($this->request->post['pdf_invoice_barcode'])) {
			$data['pdf_invoice_barcode'] = $this->request->post['pdf_invoice_barcode'];
		} else {
			$data['pdf_invoice_barcode'] = $this->config->get('pdf_invoice_barcode');
		}
    
		// colors
		if (isset($this->request->post['pdf_invoice_color_text'])) {
			$data['pdf_invoice_color_text'] = $this->request->post['pdf_invoice_color_text'];
		} else {
			$data['pdf_invoice_color_text'] = $this->config->get('pdf_invoice_color_text');
		}

		if (isset($this->request->post['pdf_invoice_color_title'])) {
			$data['pdf_invoice_color_title'] = $this->request->post['pdf_invoice_color_title'];
		} else {
			$data['pdf_invoice_color_title'] = $this->config->get('pdf_invoice_color_title');
		}
		
		if (isset($this->request->post['pdf_invoice_color_thead'])) {
			$data['pdf_invoice_color_thead'] = $this->request->post['pdf_invoice_color_thead'];
		} else {
			$data['pdf_invoice_color_thead'] = $this->config->get('pdf_invoice_color_thead');
		}
		
		if (isset($this->request->post['pdf_invoice_color_theadtxt'])) {
			$data['pdf_invoice_color_theadtxt'] = $this->request->post['pdf_invoice_color_theadtxt'];
		} else {
			$data['pdf_invoice_color_theadtxt'] = $this->config->get('pdf_invoice_color_theadtxt');
		}
		
		if (isset($this->request->post['pdf_invoice_color_tborder'])) {
			$data['pdf_invoice_color_tborder'] = $this->request->post['pdf_invoice_color_tborder'];
		} else {
			$data['pdf_invoice_color_tborder'] = $this->config->get('pdf_invoice_color_tborder');
		}
		
		if (isset($this->request->post['pdf_invoice_color_footertxt'])) {
			$data['pdf_invoice_color_footertxt'] = $this->request->post['pdf_invoice_color_footertxt'];
		} else {
			$data['pdf_invoice_color_footertxt'] = $this->config->get('pdf_invoice_color_footertxt');
		}
		
		// columns
		$default_columns = array('product_id', 'image', 'product', 'sku', 'upc', 'ean', 'mpn', 'isbn', 'model', 'location', 'description', 'weight', 'quantity','qty_shipped', 'price', 'price_tax', 'tax', 'tax_total', 'tax_rate', 'total');
		$data['pdf_invoice_columns'] = (array) $this->config->get('pdf_invoice_columns');
		foreach($default_columns as $col) {
			if (!array_key_exists($col, $data['pdf_invoice_columns'])) {
				$data['pdf_invoice_columns'][$col] = false;
			}
			if (isset($this->request->post['pdf_invoice_columns'][$col])) {
				$data['pdf_invoice_columns'][$col] = $this->request->post['pdf_invoice_columns'][$col] ? true : false;
			}
		}
		foreach($data['pdf_invoice_columns'] as $col => $val) {
			if (!in_array($col, $default_columns)) {
				unset($data['pdf_invoice_columns'][$col]);
			}
		}
		
		$options = array_flip(array('quantity', 'product_id', 'model',  'sku', 'upc', 'ean', 'mpn', 'isbn', 'manufacturer', 'weight'));
		$config_options = (array) $this->config->get('pdf_invoice_options');
		foreach($options as $col => $val) {
			if (isset($this->request->post['pdf_invoice_options'][$col])) {
				$options[$col] = $this->request->post['pdf_invoice_options'][$col] ? true : false;
			} else {
				$options[$col] = isset($config_options[$col]) ? $config_options[$col] : false;
			}
		}
		$data['pdf_invoice_options'] = $options;
		
		if (isset($this->request->post['pdf_invoice_thumbwidth'])) {
			$data['pdf_invoice_thumbwidth'] = $this->request->post['pdf_invoice_thumbwidth'];
		} else {
			$data['pdf_invoice_thumbwidth'] = $this->config->get('pdf_invoice_thumbwidth');
		}
		
		if (isset($this->request->post['pdf_invoice_thumbheight'])) {
			$data['pdf_invoice_thumbheight'] = $this->request->post['pdf_invoice_thumbheight'];
		} else {
			$data['pdf_invoice_thumbheight'] = $this->config->get('pdf_invoice_thumbheight');
		}
		
		foreach ($languages as $lang) {
			if (isset( $this->request->post[ 'pdf_invoice_filename_'.$lang['language_id'] ])) {
				$data[ 'pdf_invoice_filename_'.$lang['language_id'] ] = str_replace('/', '-', trim($this->request->post[ 'pdf_invoice_filename_'.$lang['language_id'] ]));
			} else {
				$data[ 'pdf_invoice_filename_'.$lang['language_id'] ] = $this->config->get('pdf_invoice_filename_'.$lang['language_id']);
			}
			
			if (isset( $this->request->post[ 'pdf_invoice_size_'.$lang['language_id'] ])) {
				$data[ 'pdf_invoice_size_'.$lang['language_id'] ] = trim($this->request->post[ 'pdf_invoice_size_'.$lang['language_id'] ]);
			} else {
				$data[ 'pdf_invoice_size_'.$lang['language_id'] ] = $this->config->get('pdf_invoice_size_'.$lang['language_id']);
			}
			
			if (isset( $this->request->post[ 'pdf_invoice_footer_'.$lang['language_id'] ])) {
				$data[ 'pdf_invoice_footer_'.$lang['language_id'] ] = $this->request->post[ 'pdf_invoice_footer_'.$lang['language_id'] ];
			} else {
				$data[ 'pdf_invoice_footer_'.$lang['language_id'] ] = $this->config->get('pdf_invoice_footer_'.$lang['language_id']);
			}
		}
		
		// Tab 4 - custom blocks
    if (version_compare(VERSION, '3', '>=')) {
      $this->load->model('setting/extension');
			$extension_model = $this->model_setting_extension;
    } else if (version_compare(VERSION, '2', '>=')) {
      $this->load->model('extension/extension');
			$extension_model = $this->model_extension_extension;
		} else {
			$this->load->model('setting/extension');
			$extension_model = $this->model_setting_extension;
		}

    $data['installed_modules'] = $extension_model->getInstalled('module');

		// 1.5.1 language bug
			if(defined('_JEXEC')) {
				$language = $this->language;
			} else if (isset($languages[$this->config->get('config_admin_language')])) { // 1.5.1 language bug
        $lg_folder = !empty($languages[$this->config->get('config_admin_language')]['directory']) ? $languages[$this->config->get('config_admin_language')]['directory'] : $languages[$this->config->get('config_admin_language')]['code'];
        $language = new Language($lg_folder);
			} else {
        $lg_folder = !empty($languages[$this->config->get('config_language_id')]['directory']) ? $languages[$this->config->get('config_language_id')]['directory'] : $languages[$this->config->get('config_language_id')]['code'];
        $language = new Language($lg_folder);
			}
		
		$data['payment_methods'] = array();
		//$payment_methods = glob(DIR_APPLICATION . 'controller/payment/*.php');
		$payment_methods = $extension_model->getInstalled('payment');
		
		if ($payment_methods) {
			foreach ($payment_methods as $payment) {
				$language->load('payment/' . $payment);
				$data['payment_methods'][] = array(
					'code'        => $payment,
					'name'       => $language->get('heading_title'),
				);
			}
		}

		$data['shipping_methods'] = array();
		$shipping_methods = $extension_model->getInstalled('shipping');

		foreach ($shipping_methods as $value) {
			$language->load('shipping/' . $value);
				$data['shipping_methods'][] = array(
					'code'        => $value,
					'name'       => $language->get('heading_title'),
				);
		}
		
		$this->load->model('localisation/geo_zone');
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		$data['block_positions'] = array(
			'top' => $this->language->get('text_top'),
			'middle' => $this->language->get('text_middle'),
			'bottom' => $this->language->get('text_bottom'),
			'footer' => $this->language->get('text_footer'),
			'newpage' => $this->language->get('text_newpage'),
		);
    
    $data['block_targets'] = array(
			'' => $this->language->get('text_all'),
			'invoice' => $this->language->get('text_invoice'),
			'packingslip' => $this->language->get('text_packingslip'),
		);
		
		$data['block_displays'] = array();
		$data['block_displays'][] = array(
			'value' => 'show',
			'name' => 'Always enabled',
			'section' => 0,
		);
		
		$data['pdf_invoice_blocks'] = array();

		if (isset($this->request->post['pdf_invoice_blocks'])) {
			$data['pdf_invoice_blocks'] = $this->request->post['pdf_invoice_blocks'];
		} elseif ($this->config->get('pdf_invoice_blocks')) { 
			$data['pdf_invoice_blocks'] = $this->config->get('pdf_invoice_blocks');
		}
		
		// Tab 5 - Packing slip
		if (isset($this->request->post['pdf_invoice_packingslip'])) {
			$data['pdf_invoice_packingslip'] = $this->request->post['pdf_invoice_packingslip'] ? true : false;
		} else {
			$data['pdf_invoice_packingslip'] = $this->config->get('pdf_invoice_packingslip');
		}
		
		$data['slip_templates'] = array();
		
		$templates = glob(DIR_CATALOG . 'view/theme/default/template/pdf/packingslip/*.tpl');
		foreach ($templates as $tpl) {
			$data['slip_templates'][] = basename($tpl, '.tpl');
		}
		
		if (isset($this->request->post['pdf_invoice_sliptemplate'])) {
			$data['pdf_invoice_sliptemplate'] = $this->request->post['pdf_invoice_sliptemplate'];
		} else {
			$data['pdf_invoice_sliptemplate'] = $this->config->get('pdf_invoice_sliptemplate');
		}
		
		if (isset($this->request->post['pdf_invoice_sliplogo'])) {
			$data['pdf_invoice_sliplogo'] = $this->request->post['pdf_invoice_sliplogo'] ? true : false;
		} else {
			$data['pdf_invoice_sliplogo'] = $this->config->get('pdf_invoice_sliplogo');
		}
		
    // barcode
    if (isset($this->request->post['pdf_invoice_slip_barcode'])) {
			$data['pdf_invoice_slip_barcode'] = $this->request->post['pdf_invoice_slip_barcode'];
		} else {
			$data['pdf_invoice_slip_barcode'] = $this->config->get('pdf_invoice_slip_barcode');
		}
    
    if (isset($this->request->post['pdf_invoice_slip_col_barcode'])) {
			$data['pdf_invoice_slip_col_barcode'] = $this->request->post['pdf_invoice_slip_col_barcode'];
		} else {
			$data['pdf_invoice_slip_col_barcode'] = $this->config->get('pdf_invoice_slip_col_barcode');
		}
    
		// columns
		$default_slip_columns = array('image', 'product', 'model', 'manufacturer', 'mpn', 'location', 'sku', 'upc', 'ean', 'barcode', 'weight', 'quantity', 'qty_shipped', 'price', 'price_tax', 'slip_qty', 'qty_check', 'expected','total');
		$data['pdf_invoice_slip_columns'] = (array) $this->config->get('pdf_invoice_slip_columns');
		foreach($default_slip_columns as $col) {
			if (!array_key_exists($col, $data['pdf_invoice_slip_columns'])) {
				$data['pdf_invoice_slip_columns'][$col] = false;
			}
			if (isset($this->request->post['pdf_invoice_slip_columns'][$col])) {
				$data['pdf_invoice_slip_columns'][$col] = $this->request->post['pdf_invoice_slip_columns'][$col] ? true : false;
			}
		}
		foreach($data['pdf_invoice_slip_columns'] as $col => $val) {
			if (!in_array($col, $default_slip_columns)) {
				unset($data['pdf_invoice_slip_columns'][$col]);
			}
		}
		
		// Tab 3 - backup
		if (isset($this->request->post['pdf_invoice_backup'])) {
			$data['pdf_invoice_backup'] = $this->request->post['pdf_invoice_backup'] ? true : false;
		} else {
			$data['pdf_invoice_backup'] = $this->config->get('pdf_invoice_backup');
		}
		if (isset($this->request->post['pdf_invoice_backup_moment'])) {
			$data['pdf_invoice_backup_moment'] = $this->request->post['pdf_invoice_backup_moment'];
		} else {
			$data['pdf_invoice_backup_moment'] = $this->config->get('pdf_invoice_backup_moment');
		}
		if (isset($this->request->post['pdf_invoice_backup_structure'])) {
			$data['pdf_invoice_backup_structure'] = $this->request->post['pdf_invoice_backup_structure'];
		} else {
			$data['pdf_invoice_backup_structure'] = $this->config->get('pdf_invoice_backup_structure');
		}
		if (isset($this->request->post['pdf_invoice_backup_size'])) {
			$data['pdf_invoice_backup_size'] = $this->request->post['pdf_invoice_backup_size'];
		} else {
			$data['pdf_invoice_backup_size'] = $this->config->get('pdf_invoice_backup_size');
		}
		if (isset($this->request->post['pdf_invoice_backup_folder'])) {
			$data['pdf_invoice_backup_folder'] = $this->request->post['pdf_invoice_backup_folder'];
		} else {
			$data['pdf_invoice_backup_folder'] = $this->config->get('pdf_invoice_backup_folder');
		}
		if (isset($this->request->post['pdf_invoice_backup_prefix'])) {
			$data['pdf_invoice_backup_prefix'] = str_replace('/', '-', trim($this->request->post['pdf_invoice_backup_prefix']));
		} else {
			$data['pdf_invoice_backup_prefix'] = $this->config->get('pdf_invoice_backup_prefix');
		}
		if (isset($this->request->post['pdf_invoice_backup_invnum'])) {
			$data['pdf_invoice_backup_invnum'] = $this->request->post['pdf_invoice_backup_invnum'] ? true : false;
		} else {
			$data['pdf_invoice_backup_invnum'] = $this->config->get('pdf_invoice_backup_invnum');
		}
		if (isset($this->request->post['pdf_invoice_backup_ordnum'])) {
			$data['pdf_invoice_backup_ordnum'] = $this->request->post['pdf_invoice_backup_ordnum'] ? true : false;
		} else {
			$data['pdf_invoice_backup_ordnum'] = $this->config->get('pdf_invoice_backup_ordnum');
		}
		//end backup
		
		/* gestion des variables */
		
    if (version_compare(VERSION, '2', '>=')) {
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			
      if (version_compare(VERSION, '3', '>=')) {
        $this->config->set('template_engine', 'template');
        $this->response->setOutput($this->load->view('module/pdf_invoice', $data));
      } else {
        $this->response->setOutput($this->load->view('module/pdf_invoice.tpl', $data));
      }
		} else {
			$data['column_left'] = '';
			$this->data = &$data;
			$this->template = 'module/pdf_invoice.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);
      
			$this->response->setOutput($this->render());
		}
	}
	
	public function tree() {
		$_POST['dir'] = urldecode($_POST['dir']);
		$root = DIR_SYSTEM . '../'.$this->config->get('pdf_invoice_backup_folder');

		if ( file_exists($root . $_POST['dir']) ) {
			$files = scandir($root . $_POST['dir']);
			natcasesort($files);
			if ( count($files) > 2 ) { /* . and .. */
				echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
				// All dirs
				foreach( $files as $file ) {
					if ( file_exists($root . $_POST['dir'] . $file) && $file != '.' && $file != '..' && is_dir($root . $_POST['dir'] . $file) ) {
						echo '<li class="directory collapsed"><a href="#" rel="' . htmlentities($_POST['dir'] . $file) . '/">' . htmlentities($file) . '</a></li>';
					}
				}
				// All files
				foreach( $files as $file ) {
					if ( file_exists($root . $_POST['dir'] . $file) && $file != '.' && $file != '..' && !is_dir($root . $_POST['dir'] . $file) && substr($file, -3) == 'pdf' ) {
						$ext = preg_replace('/^.*\./', '', $file);
						echo '<li class="file ext_'.$ext.'"><a target="new" href="'.$this->url->link('module/pdf_invoice/getfile', 'dir=' . htmlentities($_POST['dir'])  . '&file=' . htmlentities($file) . '&' . $this->token, 'SSL').'" rel="' . htmlentities($_POST['dir'] . $file) . '">' . htmlentities($file) . '</a></li>';
					}
				}
				echo "</ul>";	
			}
		}
		die;
	}

	public function getfile() {
		$fld = DIR_SYSTEM . '../'.$this->config->get('pdf_invoice_backup_folder') . $_GET['dir'] . '/';
		$filename =  $_GET['file'];
		
		if (!file_exists($fld.'/'.$filename))
			return 'File not found';
		
		//@ob_end_clean();
		header('Pragma: public');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		header('Cache-Control: must-revalidate, pre-check=0, post-check=0, max-age=0');
		header("Content-Transfer-Encoding: binary");
		header("Content-Type: application/octet-stream");
		header("Content-Disposition: attachment; filename=\"".$filename."\"");
		header("Content-Length: ".(string)(filesize($fld.$filename)));
		if ($file = fopen($fld.$filename, 'rb')) {
			while (!feof($file) && (connection_status()==0)) {
				print(fread($file, 1024*8));
				flush();
			}
			fclose($file);
		}
		die;
	}
	
  public function modal_info() {
    $this->load->language('module/pdf_invoice');
    
    $item = $this->request->post['info'];
    
    $extra_class = $this->language->get('info_css_' . $item) != 'info_css_' . $item ? $this->language->get('info_css_' . $item) : 'modal-lg';
    $title = $this->language->get('info_title_' . $item) != 'info_title_' . $item ? $this->language->get('info_title_' . $item) : $this->language->get('info_title_default');
    $message = $this->language->get('info_msg_' . $item) != 'info_msg_' . $item? $this->language->get('info_msg_' . $item) : $this->language->get('info_msg_default');
    
    echo '<div class="modal-dialog ' . $extra_class . '">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><i class="fa fa-info-circle"></i> ' . $title . '</h4>
        </div>
        <div class="modal-body">' . $message . '</div>
      </div>
    </div>';
    
    die;
	}
  
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/pdf_invoice')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
	  
	public function install($redir = false) {
    // rights
    $this->load->model('user/user_group');

    $this->model_user_user_group->addPermission(version_compare(VERSION, '2.0.2', '>=') ? $this->user->getGroupId() : 1, 'access', 'module/' . self::MODULE);
    $this->model_user_user_group->addPermission(version_compare(VERSION, '2.0.2', '>=') ? $this->user->getGroupId() : 1, 'modify', 'module/' . self::MODULE);
    
		$this->load->model('setting/setting');
		
		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();
		
		// tables
		$this->db_tables();
		
		$ml_settings = array();
		foreach($languages as $language)
		{
			$ml_settings['pdf_invoice_filename_'.$language['language_id']] = 'Invoice';
		}
		
		$this->model_setting_setting->editSetting('pdf_invoice', array(
			'pdf_invoice_template' => 'default',
			'pdf_invoice_mail' => true,
			//'pdf_invoice_tax' => $this->config->get('config_tax'),
			'pdf_invoice_total_tax' => $this->config->get('config_tax'),
			'pdf_invoice_invoiced' => false,
			'pdf_invoice_logo' => $this->config->get('config_logo'),
			'pdf_invoice_icon' => 'invoice-pdf1.png',
			'pdf_invoice_filename_prefix' => true,
			'pdf_invoice_filename_ordnum' => true,
			'pdf_invoice_thumbwidth' => 60,
			'pdf_invoice_thumbheight' => 60,
			'pdf_invoice_options' => array (
				'quantity' => true,
       ),
			'pdf_invoice_columns' => array (
					'image' => true, 
					'product' => true,
					'model' => true,
					'weight' => false,
					'quantity' => false,
					'qty_shipped' => true,
					'price' => true,
					'tax' => false,
				),
			'pdf_invoice_slip_columns' => array (
					'image' => true, 
					'product' => true,
					'model' => true,
					'mpn' => false,
					'location' => false,
					'sku' => false,
					'weight' => true,
					'quantity' => true,
					'slip_qty' => false,
					'qty_check' => true,
					'qty_shipped' => true,
					'expected' => false,
					'total' => false,
				),
			'pdf_invoice_sliptemplate' => 'default',
			'pdf_invoice_backup' => true,
			'pdf_invoice_backup_moment' => 'order',
			'pdf_invoice_backup_prefix' => 'Invoice',
			'pdf_invoice_backup_ordnum' => true,
			'pdf_invoice_backup_structure' => 'Y/m',
			'pdf_invoice_backup_folder' => 'invoice_backup',
		) + $ml_settings);
		
		// generate bug on 1.5.1
		//$this->redirect($this->url->link('module/pdf_invoice', $this->token, 'SSL'));
    
    if ($redir || !empty($this->request->get['redir'])) {
      if (version_compare(VERSION, '2', '>=')) {
				$this->response->redirect($this->url->link('module/'.self::MODULE, $this->token, 'SSL'));
			} else {
				$this->redirect($this->url->link('module/'.self::MODULE, $this->token, 'SSL'));
			}
    }
	}
	
	private function db_tables() {
		if(!$this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "order` LIKE 'date_invoice'")->row)
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD `date_invoice` DATETIME");
	}
}