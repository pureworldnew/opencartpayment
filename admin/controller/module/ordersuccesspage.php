<?php
class ControllerModuleOrderSuccessPage extends Controller {
	private $error = array(); 
	
	// Module-dependant variables	
	private $moduleName = 'ordersuccesspage';
	private $moduleModel = 'model_module_ordersuccesspage';
	


    public function index() {
		// Main Variables
		$data['moduleName'] 				= $this->moduleName;
		$data['moduleNameSmall']	 		= $this->moduleName;
		$data['moduleModel'] 				= $this->moduleModel;

		// Load language files
        $this->load->language('module/'.$this->moduleName);
		
		// Load models
        $this->load->model('module/'.$this->moduleName);
        $this->load->model('setting/store');
		$this->load->model('setting/setting');
        $this->load->model('localisation/language');
		$this->load->model('catalog/product');
		
		// Load script & stylesheets
        $this->document->addStyle('view/stylesheet/'.$this->moduleName.'/'.$this->moduleName.'.css');
		$this->document->addScript('view/javascript/'.$this->moduleName.'/main.js');
		
		// Set main title
        $this->document->setTitle($this->language->get('heading_title'));

		// Check for set store_id
        if(!isset($this->request->get['store_id'])) {
           $this->request->get['store_id'] 	= 0; 
        }
		
		// Get store info
        $store 								= $this->getCurrentStore($this->request->get['store_id']);
		
		// Save module settings
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) { 	
            if (!empty($_POST['OaXRyb1BhY2sgLSBDb21'])) {
                $this->request->post[$this->moduleName]['LicensedOn'] = $_POST['OaXRyb1BhY2sgLSBDb21'];
            }
            if (!empty($_POST['cHRpbWl6YXRpb24ef4fe'])) {
                $this->request->post[$this->moduleName]['License'] = json_decode(base64_decode($_POST['cHRpbWl6YXRpb24ef4fe']), true);
            }

        	$this->model_setting_setting->editSetting($this->moduleName, $this->request->post, $this->request->post['store_id']);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('module/'.$this->moduleName, 'store_id='.$this->request->post['store_id'] . '&token=' . $this->session->data['token'], 'SSL'));
        }
		
		// Get success message
		if (isset($this->session->data['success'])) {
			$data['success'] 				= $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] 				= '';
		}
		
		// Get error/warning message
		if (isset($this->error['warning'])) {
			$data['error_warning'] 			= $this->error['warning'];
		} else {
			$data['error_warning'] 			= '';
		}

		// Breadcrumb data
        $data['breadcrumbs']   				= array();
        $data['breadcrumbs'][] 				= array(
            'text' 					=> $this->language->get('text_home'),
            'href' 					=> $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
        );
        $data['breadcrumbs'][] 				= array(
            'text' 					=> $this->language->get('text_module'),
            'href' 					=> $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
        );
        $data['breadcrumbs'][] 				= array(
            'text' 					=> $this->language->get('heading_title'),
            'href' 					=> $this->url->link('module/'.$this->moduleName, 'token=' . $this->session->data['token'], 'SSL'),
        );

		// Language variables	
        $languageVariables 					= array('heading_title', 'error_permission', 'text_success', 'text_enabled',
			'text_disabled', 'button_cancel', 'save_changes', 'text_default', 'text_module');
       
        foreach ($languageVariables as $languageVariable) {
            $data[$languageVariable] 		= $this->language->get($languageVariable);
        }
		
		// Data for the template files
        $data['stores']						= array_merge(array(0 => array('store_id' => '0', 'name' => $this->config->get('config_name') . ' (' . $data['text_default'].')', 'url' => HTTP_SERVER, 'ssl' => HTTPS_SERVER)), $this->model_setting_store->getStores());
        $data['languages']              	= $this->model_localisation_language->getLanguages();
        $data['store']                  	= $store;
        $data['token']                  	= $this->session->data['token'];
        $data['action']                 	= $this->url->link('module/'.$this->moduleName, 'token=' . $this->session->data['token'], 'SSL');
        $data['cancel']                 	= $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		$data['moduleSettings']				= $this->model_setting_setting->getSetting($this->moduleName, $store['store_id']);
        $data['moduleData']					= (isset($data['moduleSettings'][$this->moduleName])) ? $data['moduleSettings'][$this->moduleName] : array();
		$data['language_id']				= $this->config->get('config_language_id');
		$data['modelCatalogProduct']		= $this->model_catalog_product;
		$data['currency']					= $this->config->get('config_currency');

		// Get the the main OpenCart admin styles & design
		$data['header']						= $this->load->controller('common/header');
		$data['column_left']				= $this->load->controller('common/column_left');
		$data['footer']						= $this->load->controller('common/footer');
		
		// Outputs the data from the function
		$this->response->setOutput($this->load->view('module/'.$this->moduleName.'.tpl', $data));
    }
	
	// Check for permissions 
	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'module/'.$this->moduleName)) {
			$this->error['warning'] 		= $this->language->get('error_permission');
		}
		
		return !$this->error;
	}
	
	// Module-specific settings
	public function get_store_settings() {
        $this->load->model('module/'.$this->moduleName);
        $this->load->model('setting/store');
		$this->load->model('setting/setting');
        $this->load->model('localisation/language');
		
		$data['languages']					= $this->model_localisation_language->getLanguages();
		$data['language_id']				= $this->config->get('config_language_id');
		$data['storedata']['id']			= $this->request->get['storedata_id'];
		$store_id							= $this->request->get['store_id'];
		$data['data']						= $this->model_setting_setting->getSetting($this->moduleName, $store_id);
		$data['moduleName']					= $this->moduleName;
		$data['moduleData']					= (isset($data['data'][$this->moduleName])) ? $data['data'][$this->moduleName] : array();
		$data['newAddition']				= true;
		
		// Outputs the data from the function
		$this->response->setOutput($this->load->view('module/'.$this->moduleName.'/tab_storetab.tpl', $data));
	}
	
	// Module installation
    public function install() {
	    $this->load->model('module/'.$this->moduleName);
	    $this->{$this->moduleModel}->install();
    }
    
	// Module uninstallation
    public function uninstall() {
        $this->load->model('module/'.$this->moduleName);
        $this->load->model('setting/store');
		$this->load->model('setting/setting');
		
		$this->model_setting_setting->deleteSetting($this->moduleName, 0);
		$stores=$this->model_setting_store->getStores();
		foreach ($stores as $store) {
			$this->model_setting_setting->deleteSetting($this->moduleName, $store['store_id']);
		}
		
        $this->load->model('module/'.$this->moduleName);
        $this->{$this->moduleModel}->uninstall();
    }
	
	// Gets the front-end URL
    private function getCatalogURL() {
        if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
            $storeURL 						= HTTPS_CATALOG;
        } else {
            $storeURL 						= HTTP_CATALOG;
        } 
        return $storeURL;
    }

	// Get the data about a given store
    private function getCurrentStore($store_id) {    
        if($store_id && $store_id != 0) {
            $store 							= $this->model_setting_store->getStore($store_id);
        } else {
            $store['store_id'] 				= 0;
            $store['name'] 					= $this->config->get('config_name');
            $store['url']					= $this->getCatalogURL(); 
			$store['ssl']					= $this->getCatalogURL();
        }
		
        return $store;
    }
}
?>