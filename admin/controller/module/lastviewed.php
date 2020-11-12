<?php
class ControllerModuleLastViewed extends Controller {
    private $moduleVersion;
    private $modulePath;
    private $moduleName;
    private $moduleModel;
    private $moduleData_module;
    private $error;
    private $extensionLink;

    public function __construct($registry) {
        parent::__construct($registry);

        $this->config->load('isenselabs/lastviewed');

        $this->moduleVersion = $this->config->get('lastviewed_version');
        $this->modulePath = $this->config->get('lastviewed_path');
        $this->moduleName = $this->config->get('lastviewed_name');
        $this->moduleModel = $this->config->get('lastviewed_model');
        $this->moduleData_module = $this->config->get('lastviewed_module');

        $this->extensionLink =  $this->url->link($this->config->get('lastviewed_extension_link'), 'token=' . $this->session->data['token'].$this->config->get('lastviewed_extension_link_type'), 'SSL');

        $this->load->language($this->modulePath);
        $this->load->model($this->modulePath);
    }

    public function index() { 
        $data['moduleName'] = $this->moduleName;
        $data['modulePath'] = $this->modulePath;
        $data['moduleNameSmall'] = $this->moduleName;
        $data['moduleData_module'] = $this->moduleData_module;
        $data['moduleModel'] = $this->moduleModel;
     
        $this->load->model('setting/store');
        $this->load->model('localisation/language');
        $this->load->model('design/layout');
        
        $catalogURL = $this->getCatalogURL();
 
        $this->document->addStyle('view/stylesheet/'.$this->moduleName.'/'.$this->moduleName.'.css');
        $this->document->setTitle($this->language->get('heading_title'));

        if(!isset($this->request->get['store_id'])) {
           $this->request->get['store_id'] = 0; 
        }
    
        $store = $this->getCurrentStore($this->request->get['store_id']);
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {    
            if (!empty($_POST['OaXRyb1BhY2sgLSBDb21'])) {
                $this->request->post[$this->moduleName]['LicensedOn'] = $_POST['OaXRyb1BhY2sgLSBDb21'];
            }

            if (!empty($_POST['cHRpbWl6YXRpb24ef4fe'])) {
                $this->request->post[$this->moduleName]['License'] = json_decode(base64_decode($_POST['cHRpbWl6YXRpb24ef4fe']), true);
            }

            $this->{$this->moduleModel}->editSetting($this->moduleName, $this->request->post, $this->request->post['store_id']);
            $this->session->data['success'] = $this->language->get('text_success');
            
            $this->response->redirect($this->url->link($this->modulePath, 'store_id='.$this->request->post['store_id'] . '&token=' . $this->session->data['token'], 'SSL'));
        }
        
        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }
        
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs']   = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->extensionLink,
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link($this->modulePath, 'token=' . $this->session->data['token'], 'SSL'),
        );

        $languageVariables = array(
            // Main
            'heading_title',
            'error_permission',
            'text_success',
            'text_enabled',
            'text_disabled',
            'button_cancel',
            'save_changes',
            'text_default',
            'text_module',
            // Control panel
            'entry_code',
            'entry_code_help',
            'text_content_top', 
            'text_content_bottom',
            'text_column_left', 
            'text_column_right',
            'entry_layout',         
            'entry_position',       
            'entry_status',         
            'entry_sort_order',     
            'entry_layout_options',  
            'entry_position_options',
            'entry_action_options',
            'button_add_module',
            'button_remove',
            // Custom CSS
            'custom_css',
            'custom_css_help',
            'custom_css_placeholder',
            // Module depending
            'wrap_widget',
            'wrap_widget_help',
            'text_products',
            'text_products_help',
            'text_image_dimensions',
            'text_image_dimensions_help',
            'text_pixels',
            'text_panel_name',
            'text_panel_name_help',
            'text_products_small',
            'show_add_to_cart',
            'show_add_to_cart_help'
        );
       
        foreach ($languageVariables as $languageVariable) {
            $data[$languageVariable] = $this->language->get($languageVariable);
        }
        $data['heading_title'] = $data['heading_title'] . ' ' . $this->moduleVersion;
        $data['stores'] = array_merge(array(0 => array('store_id' => '0', 'name' => $this->config->get('config_name') . ' (' . $data['text_default'].')', 'url' => HTTP_SERVER, 'ssl' => HTTPS_SERVER)), $this->model_setting_store->getStores());
        $data['languages']              = $this->model_localisation_language->getLanguages();
        //2.2.0.0 language flag image fix
        foreach ($data['languages'] as $key => $value) {
            if(version_compare(VERSION, '2.2.0.0', "<")) {
                $data['languages'][$key]['flag_url'] = 'view/image/flags/'.$data['languages'][$key]['image'];
            } else {
                $data['languages'][$key]['flag_url'] = 'language/'.$data['languages'][$key]['code'].'/'.$data['languages'][$key]['code'].'.png"';
            }
        }
        $data['store']                  = $store;
        $data['token']                  = $this->session->data['token'];
        $data['action']                 = $this->url->link($this->modulePath, 'token=' . $this->session->data['token'], 'SSL');
        $data['cancel']                 = $this->extensionLink;
        $data['moduleSettings']         = $this->{$this->moduleModel}->getSetting($this->moduleName, $store['store_id']);
        $data['catalog_url']            = $catalogURL;
        
        $data['moduleData']             = isset($data['moduleSettings'][$this->moduleName]) ? $data['moduleSettings'][$this->moduleName] : array ();

        if ($this->config->get('featured_status')) {
            $data['lastviewed_status'] = $this->config->get('lastviewed_status');
        } else {
            $data['lastviewed_status'] = '0';
        }
        
        $data['lastviewed_modules'] = array();
        if (isset($data['moduleSettings']['lastviewed_module'])) {
            foreach ($data['moduleSettings']['lastviewed_module'] as $key => $module) {
                $data['lastviewed_modules'][] = array(
                    'key'    => $key,
                    'limit'  => $module['limit'],
                    'width'  => $module['width'],
                    'height' => $module['height']
                );
            }
        }
        
        $data['header']                 = $this->load->controller('common/header');
        $data['column_left']            = $this->load->controller('common/column_left');
        $data['footer']                 = $this->load->controller('common/footer');
        
        $this->response->setOutput($this->load->view($this->modulePath.'.tpl', $data));
    }
    
    protected function validateForm() {
        if (!$this->user->hasPermission('modify', $this->modulePath)) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        return !$this->error;
    }

    private function getCatalogURL() {
        if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
            $storeURL = HTTPS_CATALOG;
        } else {
            $storeURL = HTTP_CATALOG;
        } 
        return $storeURL;
    }

    private function getServerURL() {
        if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
            $storeURL = HTTPS_SERVER;
        } else {
            $storeURL = HTTP_SERVER;
        } 
        return $storeURL;
    }

    private function getCurrentStore($store_id) {    
        if($store_id && $store_id != 0) {
            $store = $this->model_setting_store->getStore($store_id);
        } else {
            $store['store_id'] = 0;
            $store['name'] = $this->config->get('config_name');
            $store['url'] = $this->getCatalogURL(); 
        }
        return $store;
    }
    
    public function install() {
        $this->{$this->moduleModel}->install();
    }
    
    public function uninstall() {
        $this->load->model('setting/setting');
        
        $this->load->model('setting/store');
        $this->model_setting_setting->deleteSetting($this->moduleData_module,0);
        $stores=$this->model_setting_store->getStores();
        foreach ($stores as $store) {
            $this->model_setting_setting->deleteSetting($this->moduleData_module, $store['store_id']);
        }
        
        $this->{$this->moduleModel}->uninstall();
    }
}

?>