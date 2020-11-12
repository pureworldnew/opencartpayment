<?php
class ControllerModuleGoogleAll extends Controller {
    private $error = array();

    private $data_to_view = array();

    public function __construct($registry) {

        parent::__construct($registry);

        if( !ini_get('allow_url_fopen') ) {
           die('ERROR: YOU NEED ENABLE PHP DIRECTIVE "<b>allow_url_fopen</b>" IN YOUR SERVER<br>IF YOU DON\'T KNOW HOW TO DO IT, YOUR HOSTING SUPPORT TEAM WILL CAN DO IT FOR YOU.');
        }

        if(!extension_loaded('mbstring')) {
            die('ERROR: YOU NEED ENABLE PHP EXTENSION "<b>mbstring</b>" in your server<br>IF YOU DON\'T KNOW HOW TO DO IT, YOUR HOSTING SUPPORT TEAM WILL CAN DO IT FOR YOU.');
        }

        $this->extension_name = 'google_all';

        $this->extension_group_config = 'google';

        $this->oc_version = version_compare(VERSION, '3.0.0.0', '>=') ? 3 : (version_compare(VERSION, '2.0.0.0', '>=') ? 2 : 1);

        $this->data_to_view = array(
            'button_apply_allowed' => true,
            'button_save_allowed' => false,
            'extension_name' => $this->extension_name,
            'license_id' => $this->config->get($this->extension_group_config.'_license_id') ? $this->config->get($this->extension_group_config.'_license_id') : '',
            'oc_version' => $this->oc_version
        );
        $this->license_id = $this->config->get($this->extension_group_config.'_license_id') ? $this->config->get($this->extension_group_config.'_license_id') : '';
        $this->form_file_path = str_replace('system/', '', DIR_SYSTEM).$this->extension_name.'_form.txt';
        $this->form_file_url = HTTP_CATALOG.$this->extension_name.'_form.txt';

        $this->extension_id = '5420686f-9450-4afa-a9c1-0994fa641b0a';

        $this->api_url = defined('DEVMAN_SERVER_TEST') ? DEVMAN_SERVER_TEST : 'https://devmanextensions.com/';

        $this->is_oc_3x = $this->oc_version >= 3;
        $this->use_session_form = !$this->is_oc_3x;

        $this->form_token_name = 'devmanextensions_form_token_'.$this->extension_group_config;
        $this->form_session_name = 'devmanextensions_form_'.$this->extension_group_config;

        //IMPORTANT - DEFINE TYPE EXTENSION - MODULE, PAYMENT, SHIPPING...
        $this->extension_type = 'module';
        $this->real_extension_type = (version_compare(VERSION, '2.3', '>=') ? 'extension/':'').$this->extension_type;

        $this->extension_url_cancel_oc_15x = $this->real_extension_type;
        $this->extension_url_cancel_oc_20x = $this->real_extension_type;
        $this->extension_url_cancel_oc_23x = 'extension/extension';
        $this->extension_url_after_save_oc_15x = $this->real_extension_type;
        $this->extension_url_after_save_oc_20x = $this->real_extension_type;
        $this->extension_url_after_save_oc_23x = 'extension/extension';
        $this->extension_url_after_save_error = $this->real_extension_type.'/'.$this->extension_name;

        //Devman Extensions - info@devmanextensions.com - 2017-06-26 21:41:41 - Added Opencart 3.X Compatibility
            $this->token_name = version_compare(VERSION, '3.0.0.0', '<') ? 'token' : 'user_token';
            $this->token = $this->session->data[$this->token_name];
            $this->extension_view = version_compare(VERSION, '3.0.0.0', '<') ? $this->extension_name.'.tpl' : $this->extension_name;
        //END

        $this->path_modules = version_compare(VERSION, '3', '>=') ? 'marketplace/extension' : (version_compare(VERSION, '2.3', '>=') ? 'extension/extension' : 'extension/module');
        $this->url_modules = $this->url->link($this->path_modules, $this->token_name.'=' . $this->token, 'SSL');

        $this->path_home = version_compare(VERSION, '2.3', '>=') ? 'common/dashboard' : 'common/home';
        $this->url_home = $this->url->link($this->path_home, $this->token_name.'=' . $this->token, 'SSL');

        $this->path_module = $this->real_extension_type.'/'.$this->extension_name;
        $this->url_module = $this->url->link($this->path_module, $this->token_name.'=' . $this->token, 'SSL');

        $loader = new Loader($registry);
        $loader->language($this->real_extension_type.'/'.$this->extension_name);
        $loader->model('design/layout');
        $loader->model('extension/devmanextensions/tools');
        $loader->model('localisation/language');
        $loader->model('localisation/currency');
        $loader->model('catalog/product');
        $loader->model('tool/image');

        //Set layouts
            $layouts_temp = $this->model_design_layout->getLayouts();
            $layouts = array();
            foreach ($layouts_temp as $key => $layout) {
                $layouts[$layout['layout_id']] = $layout['name'];
            }
            $this->layouts = $layouts;
        //END Set layouts

        //Devman Extensions - info@devmanextensions.com - 2016-10-09 19:32:04 - Load stores
            $this->stores = $this->model_extension_devmanextensions_tools->getStores();
        //END

        //Devman Extensions - info@devmanextensions.com - 2016-10-09 19:39:52 - Load languages
            $this->languages = $this->model_localisation_language->getLanguages();
        //END

        //Devman Extensions - info@devmanextensions.com - 2017-05-09 20:02:00 - Get currencies
            $this->currencies = $this->model_localisation_currency->getCurrencies();
        //END

        //Set statuses
            $this->statuses = array(
                1 => $this->language->get('active'),
                0 => $this->language->get('disabled')
            );
        //END Set statuses

        //Set positions
            $this->positions = array(
                'content_top' => $this->language->get('text_content_top'),
                'content_bottom' => $this->language->get('text_content_bottom'),
                'column_left' => $this->language->get('text_column_left'),
                'column_right' => $this->language->get('text_column_right'),
            );
        //END Set positions

        //Is the first time that configure extension?
            $this->setting_group_code = version_compare(VERSION, '2.0.1.0', '>=') ? 'code' : '`group`';
            $results = $this->db->query('SELECT setting_id FROM '. DB_PREFIX . 'setting WHERE '.$this->setting_group_code.' = "'.$this->extension_group_config.'" AND `key` NOT LIKE "%license_id%" LIMIT 1');
            $this->first_configuration = empty($results->row['setting_id']);
        //END

        //Devman Extensions - info@devmanextensions.com - 2016-10-09 19:39:52 - Load languages
            $languages = $this->model_localisation_language->getLanguages();
            $this->langs = $this->model_extension_devmanextensions_tools->formatLanguages($languages);
        //END

        //Devman Extensions - info@devmanextensions.com - 2017-08-29 19:25:03 - Get customer groups
            $customer_groups = $this->model_extension_devmanextensions_tools->getCustomerGroups();
            $this->cg = $customer_groups;
            $customer_groups_formatted = array();
            foreach ($customer_groups as $key => $cgroup) {
                $customer_groups_formatted[$cgroup['customer_group_id']] = $cgroup['name'];
            }
            $this->cgf = $customer_groups_formatted;
        //END

        $this->feed_types = array(
            '' => $this->language->get('feed_type_choose_type'),
            'tab-feed-shopping' => $this->language->get('feed_type_google_shopping'),
            'tab-feed-shopping-reviews' => $this->language->get('feed_type_google_shopping_reviews'),
            'tab-csv-adwords' => $this->language->get('feed_type_adwords'),
            'tab-feed-fb' => $this->language->get('feed_type_facebook_catalog'),
            'tab-feed-criteo' => $this->language->get('feed_type_criteo'),
            'tab-feed-twenga' => $this->language->get('feed_type_twenga'),
        );

        $this->oc_2 = version_compare(VERSION, '2.0.0.0', '>=');
        $this->oc_3 = version_compare(VERSION, '3.0.0.0', '>=');

        $this->no_image_thumb = $this->model_tool_image->resize('no_image.'.($this->oc_2 ? 'png':'jpg'), 100, 100);

        //Devman Extensions - info@devmanextensions.com - 2017-08-29 18:52:09 - Construct a variable to pass all basic datas to generate form in API
            $form_basic_datas = array(
                'tab_changelog' => true,
                'tab_help' => true,
                'tab_faq' => true,
                'extension_id' => $this->extension_id,
                'first_configuration' => $this->first_configuration,
                'positions' => $this->positions,
                'statuses' => $this->statuses,
                'stores' => $this->stores,
                'layouts' => $this->layouts,
                'languages' => $this->langs,
                'oc_version' => $this->oc_version,
                'oc_2' => $this->oc_2,
                'oc_3' => $this->oc_3,
                'customer_groups' => $this->cg,
                'version' => VERSION,
                'token' => $this->token,
                'extension_group_config' => $this->extension_group_config,
                'extension_name' => $this->extension_name,
                'no_image_thumb' => $this->no_image_thumb,
                'lang' => array(
                    'choose_store' => $this->language->get('choose_store'),
                    'text_browse' => $this->language->get('text_browse'),
                    'text_clear' => $this->language->get('text_clear'),
                    'text_sort_order' => $this->language->get('text_sort_order'),
                    'text_clone_row' => $this->language->get('text_clone_row'),
                    'text_remove' => $this->language->get('text_remove'),
                    'text_add_module' => $this->language->get('text_add_module'),
                    'tab_help' => $this->language->get('tab_help'),
                    'tab_changelog' => $this->language->get('tab_changelog'),
                    'tab_faq' => $this->language->get('tab_faq'),
                ),
            );

            $this->form_basic_datas = $form_basic_datas;
        //END

        $this->form_array = $this->_construct_view_form();

        $this->google_base_pro_configurations_path = DIR_CATALOG.'controller/extension/feed/google_base_pro_configurations.json';
        $this->google_base_pro_configurations_path_backup = DIR_CATALOG.'controller/extension/feed/google_base_pro_configurations_backup.json';
        $this->google_base_pro_configurations_url = HTTPS_CATALOG.'catalog/controller/extension/feed/google_base_pro_configurations.json';

        $this->google_reviews_base_pro_configurations_path = DIR_CATALOG.'controller/extension/feed/google_reviews_base_pro_configurations.json';
        $this->google_reviews_base_pro_configurations_path_backup = DIR_CATALOG.'controller/extension/feed/google_reviews_base_pro_configurations_backup.json';
        $this->google_reviews_base_pro_configurations_url = HTTPS_CATALOG.'catalog/controller/extension/feed/google_reviews_base_pro_configurations.json';

        $this->google_business_base_pro_configurations_path = DIR_CATALOG.'controller/extension/feed/google_business_base_pro_configurations.json';
        $this->google_business_base_pro_configurations_path_backup = DIR_CATALOG.'controller/extension/feed/google_business_base_pro_configurations_backup.json';
        $this->google_business_base_pro_configurations_url = HTTPS_CATALOG.'catalog/controller/extension/feed/google_business_base_pro_configurations.json';

        $this->google_facebook_base_pro_configurations_path = DIR_CATALOG.'controller/extension/feed/facebook_base_pro_configurations.json';
        $this->google_facebook_base_pro_configurations_path_backup = DIR_CATALOG.'controller/extension/feed/facebook_base_pro_configurations_backup.json';
        $this->google_facebook_base_pro_configurations_url = HTTPS_CATALOG.'catalog/controller/extension/feed/facebook_base_pro_configurations.json';

        $this->google_criteo_base_pro_configurations_path = DIR_CATALOG.'controller/extension/feed/criteo_base_pro_configurations.json';
        $this->google_criteo_base_pro_configurations_path_backup = DIR_CATALOG.'controller/extension/feed/criteo_base_pro_configurations_backup.json';
        $this->google_criteo_base_pro_configurations_url = HTTPS_CATALOG.'catalog/controller/extension/feed/criteo_base_pro_configurations.json';

        $this->google_twenga_base_pro_configurations_path = DIR_CATALOG.'controller/extension/feed/twenga_base_pro_configurations.json';
        $this->google_twenga_base_pro_configurations_path_backup = DIR_CATALOG.'controller/extension/feed/twenga_base_pro_configurations_backup.json';
        $this->google_twenga_base_pro_configurations_url = HTTPS_CATALOG.'catalog/controller/extension/feed/twenga_base_pro_configurations.json';
    }

    public function index() {
        /*$this->_construct_view_form();
        die("delete_this!");*/

        //Set document title
            $this->document->setTitle($this->language->get('heading_title_2'));

        //Add scripts and css
            if(version_compare(VERSION, '2.0.0.0', '<'))
            {
                $this->document->addScript('view/javascript/devmanextensions/bootstrap.min.js?'.date('Ymdhis'));
                $this->document->addStyle('view/stylesheet/devmanextensions/bootstrap.min.css?'.date('Ymdhis'));
            }

            $this->document->addStyle('view/stylesheet/devmanextensions/colpick.css?'.date('Ymdhis'));
            $this->document->addStyle('view/stylesheet/devmanextensions/bootstrap-select.min.css?'.date('Ymdhis'));
            $this->document->addStyle('view/stylesheet/devmanextensions/bootstrap-switch.css?'.date('Ymdhis'));
            $this->document->addScript('view/javascript/devmanextensions/colpick.js?'.date('Ymdhis'));
            $this->document->addScript('view/javascript/devmanextensions/bootstrap-select.min.js?'.date('Ymdhis'));
            $this->document->addScript('view/javascript/devmanextensions/tools.js?'.date('Ymdhis'));
            $this->document->addScript('view/javascript/devmanextensions/jquery-sortable.js?'.date('Ymdhis'));
            $this->document->addScript('view/javascript/devmanextensions/bootstrap-switch.min.js?'.date('Ymdhis'));
            $this->document->addStyle('view/stylesheet/devmanextensions/license_form.css?'.date('Ymdhis'));

            if(version_compare(VERSION, '2.0.0.0', '>='))
            {
                $this->document->addScript('view/javascript/devmanextensions/oc2x.js?'.date('Ymdhis'));
                $this->document->addStyle('view/stylesheet/devmanextensions/oc2x.css?'.date('Ymdhis'));
            }
            else
            {
                $this->document->addScript('view/javascript/devmanextensions/oc15x.js?'.date('Ymdhis'));
                $this->document->addStyle('view/stylesheet/devmanextensions/oc15x.css?'.date('Ymdhis'));
                $this->document->addScript('view/javascript/ckeditor/ckeditor.js?'.date('Ymdhis'));
                $this->document->addStyle('//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css?'.date('Ymdhis'));
            }
        //END Add scripts and css

        //Add custom js
            if(file_exists('view/javascript/devmanextensions_'.$this->extension_name.'.js'))
                $this->document->addScript('view/javascript/devmanextensions_'.$this->extension_name.'.js?'.date('Ymdhis'));

            if(version_compare(VERSION, '2.0.0.0', '>=') && file_exists('view/javascript/devmanextensions_'.$this->extension_name.'_oc2x.js'))
                $this->document->addScript('view/javascript/devmanextensions_'.$this->extension_name.'_oc2x.js?'.date('Ymdhis'));
            elseif(file_exists('view/javascript/devmanextensions_'.$this->extension_name.'_oc15x.js'))
                $this->document->addScript('view/javascript/devmanextensions_'.$this->extension_name.'_oc15x.js?'.date('Ymdhis'));

        //Add custom css
            if(file_exists('view/stylesheet/devmanextensions_'.$this->extension_name.'.css'))
                $this->document->addStyle('view/stylesheet/devmanextensions_'.$this->extension_name.'.css?'.date('Ymdhis'));

            if(version_compare(VERSION, '2.0.0.0', '>=') && file_exists('view/stylesheet/devmanextensions_'.$this->extension_name.'_oc2x.css'))
                $this->document->addStyle('view/stylesheet/devmanextensions_'.$this->extension_name.'_oc2x.css?'.date('Ymdhis'));
            elseif(file_exists('view/stylesheet/devmanextensions_'.$this->extension_name.'_oc15x.css'))
                $this->document->addStyle('view/stylesheet/devmanextensions_'.$this->extension_name.'_oc15x.css?'.date('Ymdhis'));

        //Devman Extensions - info@devmanextensions.com - 2016-10-21 18:57:30 - Custom functions
            if(
                !empty($this->request->post['ajax_function']) || !empty($this->request->get['ajax_function'])
                ||
                !empty($this->request->post[$this->extension_group_config.'_ajax_function']) || !empty($this->request->get[$this->extension_group_config.'ajax_function'])
            )
            {
                if(!empty($this->request->post['ajax_function']) || !empty($this->request->get['ajax_function']))
                    $index = 'ajax_function';
                else
                    $index = $this->extension_group_config.'_force_function';

                $post_get = !empty($this->request->post[$index]) ? 'post' : 'get';
                $this->{$this->request->{$post_get}[$index]}();
            }
        //END

        //Pressed save button
            if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
                $no_exit = !empty($this->request->post['no_exit']) ? 1 : 0;

                //Devman Extensions - info@devmanextensions.com - 2016-10-21 18:57:30 - Custom functions
                    if(
                        !empty($this->request->post['force_function']) || !empty($this->request->get['force_function'])
                        ||
                        !empty($this->request->post[$this->extension_group_config.'_force_function']) || !empty($this->request->get[$this->extension_group_config.'force_function'])
                    )
                    {
                        if(!empty($this->request->post['force_function']) || !empty($this->request->get['force_function']))
                            $index = 'force_function';
                        else
                            $index = $this->extension_group_config.'_force_function';

                        $post_get = !empty($this->request->post[$index]) ? 'post' : 'get';
                        $this->{$this->request->{$post_get}[$index]}();
                    }
                //END

                unset($this->request->post['no_exit']);

                //Devman Extensions - info@devmanextensions.com - 2017-09-28 09:04:51 - Remove google merchant center fields will be saved into json file
                    foreach ($this->request->post as $field_name => $value) {
                        if (strpos($field_name, 'google_base_pro_merchantcenter_') !== false || strpos($field_name, 'google_business_base_pro_') !== false || strpos($field_name, 'google_facebook_base_pro') !== false || strpos($field_name, 'google_criteo_base_pro_') !== false || strpos($field_name, 'google_twenga_base_pro_') !== false || strpos($field_name, 'google_reviews_base_pro_') !== false)
                            unset($this->request->post[$field_name]);
                    }
                //END

                //Serialize multiples field from table inputs
                    foreach ($this->request->post as $input_name => $data_post) {
                        if(is_array($data_post) && isset($data_post['replace_by_number']))
                        {
                            unset($data_post['replace_by_number']);

                            if(empty($data_post))
                                $this->request->post[$input_name] = '';
                            else
                                $this->request->post[$input_name] = base64_encode(serialize(array_values($data_post)));
                        }
                    }
                //END Serialize multiples field from table inputs

                $error = $this->_test_before_save();

                if(!$error)
                {
                    $this->load->model('setting/setting');
                    $this->model_setting_setting->editSetting($this->extension_group_config, $this->request->post);

                    if(!empty($no_exit))
                    {
                        $array_return = array(
                            'error' => false,
                            'message' => $this->language->get('text_success')
                        );
                        echo json_encode($array_return); die;
                    }
                    else
                        $this->session->data['success'] = $this->language->get('text_success');

                    $after_save_temp = version_compare(VERSION, '2.0.0.0', '>=') ? $this->extension_url_after_save_oc_20x : $this->extension_url_after_save_oc_15x;
                    $after_save_temp = version_compare(VERSION, '2.3.0.0', '>=') ? $this->extension_url_after_save_oc_23x : $after_save_temp;

                    if(version_compare(VERSION, '2.0.0.0', '>='))
                        $this->response->redirect($this->url->link($after_save_temp, $this->token_name.'=' . $this->token, 'SSL'));
                    else
                        $this->redirect($after_save_temp, $this->token_name.'=' . $this->token, 'SSL');
                }
                else
                {
                    if(!empty($no_exit))
                    {
                        $array_return = array(
                            'error' => true,
                            'message' => $error
                        );
                        echo json_encode($array_return); die;
                    }
                    else
                        $this->session->data['error'] = $error;

                    if(version_compare(VERSION, '2.0.0.0', '>='))
                        $this->response->redirect($this->url->link($this->extension_url_after_save_error, $this->token_name.'=' . $this->token, 'SSL'));
                    else
                        $this->redirect($this->extension_url_after_save_error, $this->token_name.'=' . $this->token, 'SSL');
                }
            }
        //END Pressed save button

        //Devman Extensions - info@devmanextensions.com - 2016-10-21 18:57:30 - Custom functions
            if(!empty($this->request->post['force_function']) || !empty($this->request->get['force_function']))
            {
                $post_get = !empty($this->request->post['force_function']) ? 'post' : 'get';
                $this->{$this->request->{$post_get}['force_function']}();
            }
        //END

        //Send token to view
            $this->data_to_view['token'] = $this->token;

        //Actions
            $this->data_to_view['action'] = $this->url->link($this->real_extension_type.'/'.$this->extension_name, $this->token_name.'=' . $this->token, 'SSL');
            $this->data_to_view['cancel'] = $this->url_cancel;

        //Load extension languages
            $lang_array = array(
                'heading_title',
                'heading_title_2',
                'button_save',
                'button_cancel',
                'apply_changes',
                'text_image_manager',
                'text_browse',
                'text_clear',
                'text_validate_license',
                'text_license_id',
                'text_send',
            );

            foreach ($lang_array as $key => $value) {
                $this->data_to_view[$value] = $this->language->get($value);
            }
        //END Load extension languages

        //Construct view template form
            $form = $this->model_extension_devmanextensions_tools->_get_form_in_settings();
            $this->data_to_view['form'] =  !empty($form) ? $form : '';
        //END Construct view template form

        //Devman Extensions - info@devmanextensions.com - 2016-11-19 14:43:03 - Send custom variables to view
                $this->_send_custom_variables_to_view();
        //END

        $this->data_to_view['breadcrumbs'] = array();
        $this->data_to_view['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url_home,
            'separator' => false
        );


        $this->data_to_view['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_'.$this->extension_type),
            'href'      => $this->url_modules,
            'separator' => ' :: '
        );

        $this->data_to_view['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title_2'),
            'href'      => $this->url_module,
            'separator' => ' :: '
        );

        //Devman Extensions - info@devmanextensions.com - 2017-06-28 20:47:35 - Opencart 3.x compatibility with alerts
            if(version_compare(VERSION, '3.0.0.0', '>='))
            {
                if(!empty($this->session->data['error']))
                {
                    $this->data_to_view['error_warning'] = $this->session->data['error'];
                    unset($this->session->data['error']);
                }

                if(!empty($this->session->data['error_expired']))
                {
                    $this->data_to_view['error_warning_expired'] = $this->session->data['error_expired'];
                    unset($this->session->data['error_expired']);
                }

                if(!empty($this->session->data['success']))
                {
                    $this->data_to_view['success_message'] = $this->session->data['success'];
                    unset($this->session->data['success']);
                }

                if(!empty($this->session->data['info']))
                {
                    $this->data_to_view['info_message'] = $this->session->data['info'];
                    unset($this->session->data['info']);
                }
            }
        //END

        //OC Versions compatibility
            if(version_compare(VERSION, '2.0.0.0', '>='))
            {
                $data = $this->data_to_view;
                $data['header'] = $this->load->controller('common/header');
                $data['column_left'] = $this->load->controller('common/column_left');
                $data['footer'] = $this->load->controller('common/footer');

                $this->response->setOutput($this->load->view($this->real_extension_type.'/'.$this->extension_view, $data));
            }
            else
            {
                $this->data = $this->data_to_view;
                $this->template = $this->real_extension_type.'/'.$this->extension_view;
                $this->children = array(
                    'common/header',
                    'common/footer'
                );

                $this->response->setOutput($this->render());
            }
    }

    private function validate() {
        if (!$this->user->hasPermission('modify', $this->real_extension_type.'/'.$this->extension_name)) {
            if(!empty($this->request->post['no_exit']))
            {
                $array_return = array(
                    'error' => true,
                    'message' => $this->language->get('error_permission')
                );
                echo json_encode($array_return); die;
            }
            else
                $this->session->data['error'] = $this->language->get('error_permission');
            return false;
        }
        return true;
    }

    public function _construct_view_form()
    {
        //Countries to google merchant center
            $merchant_center_countries = array(
                'en-AU' => 'Australia',
                'pt-BR' => 'Brazil / Portugal',
                'cs-CZ' => 'Czech Republic',
                'da-DK' => 'Denmark',
                'en-GB' => 'England / Belgium / New Zealand / Norway / Sweden',
                'fr-FR' => 'France / Belgium',
                'de-DE' => 'Germany / Austria',
                'it-IT' => 'Italy',
                'ja-JA' => 'Japan',
                'nl-NL' => 'Netherlands / Belgium',
                'no-NO' => 'Norway',
                'pl-PL' => 'Poland',
                'ru-RU' => 'Russia',
                'es-ES' => 'Spain / Argentina / Chile / Colombia / Mexico',
                'sv-SE' => 'Sweden',
                'tr-TR' => 'Turkey',
                'en-US' => 'USA / Canada / China / Malaysia / Mexico / Philippines / Singapore / South Africa / United Arab Emirates',
            );
        //END Countries to google merchant center categories

        //Categories to google merchant center
            $this->load->model('catalog/category');
            $categories = $this->model_catalog_category->getCategories(true);
            if (empty($categories))
                $categories = $this->model_catalog_category->getCategories();
        //END Categories to google merchant center

        //Get Attributes
            $attributes_to_select = array('' => $this->language->get('text_select'));
            $this->load->model('catalog/attribute');
            $attributes = $this->model_catalog_attribute->getAttributes();

            if(!empty($attributes))
            {
                foreach ($attributes as $key => $attr) {
                    $attributes_to_select[$attr['attribute_id']] = $attr['attribute_group'].' > '.$attr['name'];
                }
            }
        //END Get Attributes

        //Get Filters
            $filters_to_select = array('' => $this->language->get('text_select'));
            if(version_compare(VERSION, '1.5.4.1', '>'))
            {
                $this->load->model('catalog/filter');
                $filters = $this->model_catalog_filter->getFilters(true);
                if(!empty($filters))
                {
                    foreach ($filters as $key => $filt) {
                        $filters_to_select[$filt['filter_group_id']] = $filt['group'];
                    }
                }
            }
        //END Get Filters

        //Get Options
            $options_to_select = array('' => $this->language->get('text_select'));
            $this->load->model('catalog/option');
            $options = $this->model_catalog_option->getOptions();
            if(!empty($options))
            {
                foreach ($options as $key => $opt) {
                    $options_to_select[$opt['option_id']] = $opt['name'];
                }
            }
        //END Get Options

        //Devman Extensions - info@devmanextensions.com - 2017-11-12 11:24:05 - Get order statuses
            $this->load->model('localisation/order_status');
            $order_statuses = array();
            $order_statuses_temp = $this->model_localisation_order_status->getOrderStatuses();
            foreach ($order_statuses_temp as $key => $ord) {
                $order_statuses[$ord['order_status_id']] = $ord['name'];
            }
        //END

        $actions_ee_multichannel = array(
            '' => '',
            'visit_product_page' => $this->language->get('multichannel_funnel_action_visit_product_page'),
            'add_to_cart' => $this->language->get('multichannel_funnel_action_add_to_cart'),
            'visit_cart_page' => $this->language->get('multichannel_funnel_action_visit_cart_page'),
            'visit_checkout_page' => $this->language->get('multichannel_funnel_action_visit_checkout_page'),
            'finish_order' => $this->language->get('multichannel_funnel_action_finish_order'),
        );

        $product_identificators = array(
            'product_id' => 'Product ID',
            'model' => 'Model',
            'sku' => 'SKU',
            'upc' => 'UPC',
            'ean' => 'EAN',
            'jan' => 'JAN',
            'isbn' => 'ISBN',
            'mpn' => 'MPN'
        );

        $v_gmt = preg_replace('/[^0-9]/', '', strip_tags($this->language->get('heading_title')));

        $form_view = array(
            'action' => $this->url->link($this->real_extension_type.'/'.$this->extension_name, $this->token_name.'=' . $this->token, 'SSL'),
            'id' => $this->extension_name,
            'extension_name' => $this->extension_name,
            'columns' => 1,
            'multi_store' => true,
            'tabs' => array(
                $this->language->get('tab_configuration') => array(
                    'icon' => '<i class="fa fa-cog"></i>',
                    'fields' => array(
                        array(
                            'text' => '<i class="fa fa-cog"></i>'.$this->language->get('gmt_configuration'),
                            'type' => 'legend',
                        ),

                        array(
                            'label' => $this->language->get('status'),
                            'type' => 'boolean',
                            'value' => $this->config->get('google_tag_manager_status'),
                            'name' => 'tag_manager_status',
                            'after' => '<input type="hidden" name="google_version" value="'.$v_gmt.'">'
                        ),

                        array(
                            'label' => '<img class="tag_icon" src="'.HTTP_SERVER.'view/image/gmt/icons/gtm.jpg">'.$this->language->get('container_id'),
                            'help' => $this->language->get('container_id_help'),
                            'type' => 'text',
                            'class' => 'container_config',
                            'value' => $this->config->get('google_container_id'),
                            'name' => 'container_id'
                        ),

                        array(
                            'label' => '<img class="tag_icon go" src="'.HTTP_SERVER.'view/image/gmt/icons/go.png">'.$this->language->get('container_optimize_id'),
                            'help' => $this->language->get('container_optimize_id_help'),
                            'type' => 'text',
                            'class' => 'container_optimize_config',
                            'value' => $this->config->get('google_container_optimize_id'),
                            'name' => 'container_optimize_id'
                        ),

                        array(
                            'label' => $this->language->get('positive_conversion_status_id'),
                            'help' => $this->language->get('positive_conversion_status_id_help'),
                            'type' => 'select',
                            'multiple' => true,
                            'options' => $order_statuses,
                            'name' => 'positive_conversion_status_id'
                        ),

                        array(
                            'label' => $this->language->get('negative_conversion'),
                            'help' => $this->language->get('negative_conversion_help'),
                            'class' => 'negative_conversion_input',
                            'type' => 'text',
                            'name' => 'negative_conversion',
                            'after' => '<a href="javascript:{}" onclick="send_conversion(true);">'.$this->language->get('negative_conversion_send').'</a>'
                        ),

                        array(
                            'label' => $this->language->get('positive_conversion'),
                            'help' => $this->language->get('positive_conversion_help'),
                            'class' => 'positive_conversion_input',
                            'type' => 'text',
                            'name' => 'positive_conversion',
                            'after' => '<a href="javascript:{}" onclick="send_conversion();">'.$this->language->get('positive_conversion_send').'</a>'
                        ),

                        array(
                            'label' => $this->language->get('multichannel_funnel'),
                            'type' => 'boolean',
                            'class' => 'container_config multichannel_funnel_status',
                            'value' => $this->config->get('google_multichannel_funnel_status'),
                            'name' => 'multichannel_funnel_status',
                            'onchange' => 'check_multichannel_funnel_params($(this));'
                        ),

                        array(
                            'label' => $this->language->get('multichannel_step_1'),
                            'type' => 'select',
                            'options' => $actions_ee_multichannel,
                            'class' => 'container_config multichannel_funnel_params',
                            'value' => $this->config->get('google_multichannel_step_1'),
                            'name' => 'multichannel_step_1'
                        ),

                        array(
                            'label' => $this->language->get('multichannel_step_2'),
                            'type' => 'select',
                            'options' => $actions_ee_multichannel,
                            'class' => 'container_config multichannel_funnel_params',
                            'value' => $this->config->get('google_multichannel_step_2'),
                            'name' => 'multichannel_step_2'
                        ),

                        array(
                            'label' => $this->language->get('multichannel_step_3'),
                            'type' => 'select',
                            'options' => $actions_ee_multichannel,
                            'class' => 'container_config multichannel_funnel_params',
                            'value' => $this->config->get('google_multichannel_step_3'),
                            'name' => 'multichannel_step_3'
                        ),

                        array(
                            'label' => $this->language->get('multichannel_step_4'),
                            'type' => 'select',
                            'options' => $actions_ee_multichannel,
                            'class' => 'container_config multichannel_funnel_params',
                            'value' => $this->config->get('google_multichannel_step_4'),
                            'name' => 'multichannel_step_4'
                        ),

                        array(
                            'label' => $this->language->get('multichannel_step_5'),
                            'type' => 'select',
                            'options' => $actions_ee_multichannel,
                            'class' => 'container_config multichannel_funnel_params',
                            'value' => $this->config->get('google_multichannel_step_5'),
                            'name' => 'multichannel_step_5'
                        ),

                        array(
                            'label' => '<img class="tag_icon" src="'.HTTP_SERVER.'view/image/gmt/icons/google_reviews.jpg">'.$this->language->get('google_reviews_status'),
                            'type' => 'boolean',
                            'class' => 'container_config google_reviews_status',
                            'value' => $this->config->get('google_google_reviews_status'),
                            'name' => 'google_reviews_status',
                            'onchange' => 'check_google_reviews_params($(this));'
                        ),

                        array(
                            'label' => $this->language->get('google_reviews_id'),
                            'help' => $this->language->get('google_reviews_id_help'),
                            'type' => 'text',
                            'class' => 'container_config google_reviews_params',
                            'value' => $this->config->get('google_google_reviews_id'),
                            'name' => 'google_reviews_id'
                        ),

                        array(
                            'label' => $this->language->get('google_reviews_style'),
                            'help' => $this->language->get('google_reviews_style_help'),
                            'type' => 'select',
                            'options' => array(
                                'CENTER_DIALOG' => $this->language->get('google_reviews_style_center_dialog'),
                                'BOTTOM_RIGHT_DIALOG' => $this->language->get('google_reviews_style_bottom_right_dialog'),
                                'BOTTOM_LEFT_DIALOG' => $this->language->get('google_reviews_style_bottom_left_dialog'),
                                'TOP_RIGHT_DIALOG' => $this->language->get('google_reviews_style_top_right_dialog'),
                                'TOP_LEFT_DIALOG' => $this->language->get('google_reviews_style_top_left_dialog'),
                                'BOTTOM_TRAY' => $this->language->get('google_reviews_style_bottom_tray'),
                            ),
                            'class' => 'container_config google_reviews_params',
                            'value' => $this->config->get('google_google_reviews_style'),
                            'name' => 'google_reviews_style'
                        ),

                        array(
                            'label' => $this->language->get('google_reviews_delivery_days'),
                            'help' => $this->language->get('google_reviews_delivery_days_help'),
                            'type' => 'text',
                            'class' => 'container_config google_reviews_params',
                            'value' => $this->config->get('google_google_reviews_delivery_days'),
                            'name' => 'google_reviews_delivery_days',
                            'default' => 3
                        ),

                        array(
                            'label' => $this->language->get('google_reviews_lang_default_code'),
                            'help' => $this->language->get('google_reviews_lang_default_code_help'),
                            'type' => 'text',
                            'class' => 'container_config google_reviews_params',
                            'value' => $this->config->get('google_google_reviews_lang_default_code'),
                            'name' => 'google_reviews_lang_default_code'
                        ),

                        array(
                            'label' => $this->language->get('google_reviews_gtin'),
                            'help' => $this->language->get('google_reviews_gtin_help'),
                            'type' => 'select',
                            'options' => array(
                                '' => $this->language->get('google_reviews_gtin_none'),
                                'mpn' => $this->language->get('google_reviews_gtin_mpn'),
                                'model' => $this->language->get('google_reviews_gtin_model'),
                                'ean' => $this->language->get('google_reviews_gtin_ean'),
                                'upc' => $this->language->get('google_reviews_gtin_upc'),
                            ),
                            'class' => 'container_config google_reviews_params',
                            'value' => $this->config->get('google_google_reviews_gtin'),
                            'name' => 'google_reviews_gtin'
                        ),

                        array(
                            'label' => $this->language->get('google_reviews_badge_code'),
                            'type' => 'boolean',
                            'class' => 'container_config google_reviews_params',
                            'value' => $this->config->get('google_google_reviews_badge_code'),
                            'name' => 'google_reviews_badge_code'
                        ),

                        array(
                            'label' => $this->language->get('google_reviews_badge_code_style'),
                            'type' => 'select',
                            'options' => array(
                                'BOTTOM_RIGHT' => $this->language->get('google_reviews_badge_code_style_bottom_right'),
                                'BOTTOM_LEFT' => $this->language->get('google_reviews_badge_code_style_bottom_left'),
                                'INLINE' => $this->language->get('google_reviews_badge_code_style_bottom_inline'),
                            ),
                            'class' => 'container_config google_reviews_params',
                            'value' => $this->config->get('google_google_reviews_badge_code_style'),
                            'name' => 'google_reviews_badge_code_style'
                        ),

                        array(
                            'label' => $this->language->get('google_product_id_like'),
                            'help' => $this->language->get('google_product_id_like_help'),
                            'type' => 'select',
                            'options' => $product_identificators,
                            'value' => $this->config->get('google_google_product_id_like'),
                            'name' => 'google_product_id_like'
                        ),

                        array(
                            'text' => '<i class="fa fa-dashboard"></i>'.$this->language->get('configure_workspace'),
                            'type' => 'legend',
                        ),

                        array(
                            'label' => '<img class="tag_icon" src="'.HTTP_SERVER.'view/image/gmt/icons/oc.jpg">'.$this->language->get('license_id'),
                            'help' => $this->language->get('license_id_help'),
                            'type' => 'text',
                            'class' => 'container_config',
                            'value' => $this->config->get('google_license_id'),
                            'name' => 'license_id'
                        ),

                        array(
                            'label' => '<img class="tag_icon" src="'.HTTP_SERVER.'view/image/gmt/icons/ga.jpg">'.$this->language->get('google_analytics_ua'),
                            'type' => 'text',
                            'class' => 'container_config',
                            'value' => $this->config->get('google_google_analytics_ua'),
                            'name' => 'google_analytics_ua'
                        ),

                        array(
                            'label' => '<img class="tag_icon" src="'.HTTP_SERVER.'view/image/gmt/icons/ee.jpg">'.$this->language->get('enhanced_ecommerce_status'),
                            'type' => 'boolean',
                            'class' => 'container_config',
                            'value' => $this->config->get('google_enhanced_ecommerce_status'),
                            'name' => 'enhanced_ecommerce_status',
                        ),

                        array(
                            'label' => '<img class="tag_icon" src="'.HTTP_SERVER.'view/image/gmt/icons/adwords.jpg">'.$this->language->get('conversion_status'),
                            'type' => 'boolean',
                            'class' => 'container_config conversion_status',
                            'value' => $this->config->get('google_conversion_status'),
                            'name' => 'conversion_status',
                            'onchange' => 'check_conversion_params($(this));'
                        ),

                        array(
                            'label' => $this->language->get('conversion_id'),
                            'help' => $this->language->get('conversion_id_help'),
                            'type' => 'text',
                            'class' => 'container_config conversion_params',
                            'value' => $this->config->get('google_conversion_id'),
                            'name' => 'conversion_id'
                        ),

                        array(
                            'label' => $this->language->get('conversion_label'),
                            'help' => $this->language->get('conversion_label_help'),
                            'type' => 'text',
                            'class' => 'container_config conversion_params',
                            'value' => $this->config->get('google_conversion_label'),
                            'name' => 'conversion_label'
                        ),

                        array(
                            'label' => '<img class="tag_icon" src="'.HTTP_SERVER.'view/image/gmt/icons/dynamic_remarketing.jpg">'.$this->language->get('dynamic_remarketing_status'),
                            'type' => 'boolean',
                            'class' => 'container_config dynamic_remarketing_status',
                            'value' => $this->config->get('google_dynamic_remarketing_status'),
                            'name' => 'dynamic_remarketing_status',
                            'onchange' => 'check_dynamic_remarketing_params($(this));'
                        ),

                        array(
                            'label' => $this->language->get('dynamic_remarketing_id'),
                            'help' => $this->language->get('dynamic_remarketing_id_help'),
                            'type' => 'text',
                            'class' => 'container_config dynamic_remarketing_params',
                            'value' => $this->config->get('google_dynamic_remarketing_id'),
                            'name' => 'dynamic_remarketing_id'
                        ),

                        array(
                            'label' => $this->language->get('dynamic_remarketing_label'),
                            'help' => $this->language->get('dynamic_remarketing_label_help'),
                            'type' => 'text',
                            'class' => 'container_config dynamic_remarketing_params',
                            'value' => $this->config->get('google_dynamic_remarketing_label'),
                            'name' => 'dynamic_remarketing_label'
                        ),

                        array(
                            'label' => $this->language->get('dynamic_remarketing_id_prefix'),
                            'help' => $this->language->get('dynamic_remarketing_id_prefix_help'),
                            'type' => 'text',
                            'class' => 'container_config dynamic_remarketing_params',
                            'value' => $this->config->get('google_dynamic_remarketing_id_prefix'),
                            'name' => 'dynamic_remarketing_id_prefix'
                        ),

                        array(
                            'label' => $this->language->get('dynamic_remarketing_id_sufix'),
                            'help' => $this->language->get('dynamic_remarketing_id_sufix_help'),
                            'type' => 'text',
                            'class' => 'container_config dynamic_remarketing_params',
                            'value' => $this->config->get('google_dynamic_remarketing_id_sufix'),
                            'name' => 'dynamic_remarketing_id_sufix'
                        ),

                        array(
                            'label' => $this->language->get('dynamic_remarketing_dynx'),
                            'type' => 'boolean',
                            'class' => 'container_config dynamic_remarketing_params dynamic_remarketing_dynx_status',
                            'value' => $this->config->get('google_dynamic_remarketing_dynx'),
                            'name' => 'dynamic_remarketing_dynx',
                            'onchange' => 'check_dynamic_remarketing_dynx_params($(this));'
                        ),

                        array(
                            'label' => $this->language->get('dynamic_remarketing_dynx2'),
                            'type' => 'select',
                            'class' => 'container_config dynamic_remarketing_params dynamic_remarketing_dynx_params',
                            'options' => array(
                                'product_id' => $this->language->get('dynamic_remarketing_dynx2_product_id'),
                                'model' => $this->language->get('dynamic_remarketing_dynx2_model'),
                                'upc' => $this->language->get('dynamic_remarketing_dynx2_upc'),
                                'ean' => $this->language->get('dynamic_remarketing_dynx2_ean'),
                                'jan' => $this->language->get('dynamic_remarketing_dynx2_jan'),
                                'isbn' => $this->language->get('dynamic_remarketing_dynx2_isbn'),
                                'mpn' => $this->language->get('dynamic_remarketing_dynx2_mpn'),
                                'location' => $this->language->get('dynamic_remarketing_dynx2_location'),
                            ),
                            'value' => $this->config->get('google_dynamic_remarketing_dynx2'),
                            'name' => 'dynamic_remarketing_dynx2',
                        ),

                        array(
                            'label' => '<img class="tag_icon" src="'.HTTP_SERVER.'view/image/gmt/icons/rich_snippets.jpg">'.$this->language->get('rich_snippets'),
                            'type' => 'boolean',
                            'class' => 'container_config rich_snippets',
                            'value' => $this->config->get('google_rich_snippets'),
                            'name' => 'rich_snippets',
                        ),

                        array(
                            'label' => '<img class="tag_icon" src="'.HTTP_SERVER.'view/image/gmt/icons/hotjar.jpg">'.$this->language->get('hotjar_status'),
                            'type' => 'boolean',
                            'class' => 'container_config hotjar_status',
                            'value' => $this->config->get('google_hotjar_status'),
                            'name' => 'hotjar_status',
                            'onchange' => 'check_hotjar_params($(this));'
                        ),

                        array(
                            'label' => $this->language->get('hotjar_site_id'),
                            'help' => $this->language->get('hotjar_site_id_help'),
                            'type' => 'text',
                            'class' => 'container_config hotjar_params',
                            'value' => $this->config->get('google_hotjar_site_id'),
                            'name' => 'hotjar_site_id'
                        ),

                        array(
                            'label' => '<img class="tag_icon" src="'.HTTP_SERVER.'view/image/gmt/icons/pinterest.jpg">'.$this->language->get('pinterest_status'),
                            'type' => 'boolean',
                            'class' => 'container_config pinterest_status',
                            'value' => $this->config->get('google_pinterest_status'),
                            'name' => 'pinterest_status',
                            'onchange' => 'check_pinterest_params($(this));'
                        ),

                        array(
                            'label' => $this->language->get('pinterest_id'),
                            'help' => $this->language->get('pinterest_id_help'),
                            'type' => 'text',
                            'class' => 'container_config pinterest_params',
                            'value' => $this->config->get('google_pinterest_id'),
                            'name' => 'pinterest_id'
                        ),

                        array(
                            'label' => '<img class="tag_icon" src="'.HTTP_SERVER.'view/image/gmt/icons/crazyegg.jpg">'.$this->language->get('crazyegg_status'),
                            'type' => 'boolean',
                            'class' => 'container_config crazyegg_status',
                            'value' => $this->config->get('google_crazyegg_status'),
                            'name' => 'crazyegg_status',
                            'onchange' => 'check_crazyegg_params($(this));'
                        ),

                        array(
                            'label' => $this->language->get('crazyegg_id'),
                            'help' => $this->language->get('crazyegg_id_help'),
                            'type' => 'text',
                            'class' => 'container_config crazyegg_params',
                            'value' => $this->config->get('google_crazyegg_id'),
                            'name' => 'crazyegg_id'
                        ),

                        array(
                            'label' => '<img class="tag_icon" src="'.HTTP_SERVER.'view/image/gmt/icons/criteo.jpg">'.$this->language->get('criteo_status'),
                            'type' => 'boolean',
                            'class' => 'container_config criteo_status',
                            'value' => $this->config->get('google_criteo_status'),
                            'name' => 'criteo_status',
                            'onchange' => 'check_criteo_params($(this));'
                        ),

                        array(
                            'label' => $this->language->get('criteo_id'),
                            'help' => $this->language->get('criteo_id_help'),
                            'type' => 'text',
                            'class' => 'container_config criteo_params',
                            'value' => $this->config->get('google_criteo_id'),
                            'name' => 'criteo_id'
                        ),

                        array(
                            'label' => '<img class="tag_icon" src="'.HTTP_SERVER.'view/image/gmt/icons/facebook_pixel.jpg">'.$this->language->get('facebook_pixel_status'),
                            'type' => 'boolean',
                            'class' => 'container_config facebook_pixel_status',
                            'value' => $this->config->get('google_facebook_pixel_status'),
                            'name' => 'facebook_pixel_status',
                            'onchange' => 'check_facebook_pixel_params($(this));'
                        ),

                        array(
                            'label' => $this->language->get('facebook_pixel_id'),
                            'help' => $this->language->get('facebook_pixel_id_help'),
                            'type' => 'text',
                            'class' => 'container_config facebook_pixel_params',
                            'value' => $this->config->get('google_facebook_pixel_id'),
                            'name' => 'facebook_pixel_id'
                        ),

                        array(
                            'label' => '<img class="tag_icon" src="'.HTTP_SERVER.'view/image/gmt/icons/bing_ads.jpg">'.$this->language->get('bing_ads_status'),
                            'type' => 'boolean',
                            'class' => 'container_config bing_ads_status',
                            'value' => $this->config->get('google_bing_ads_status'),
                            'name' => 'bing_ads_status',
                            'onchange' => 'check_bing_ads_params($(this));'
                        ),

                        array(
                            'label' => $this->language->get('bing_ads_tag_id'),
                            'help' => $this->language->get('bing_ads_tag_id_help'),
                            'type' => 'text',
                            'class' => 'container_config bing_ads_params',
                            'value' => $this->config->get('google_bing_ads_tag_id'),
                            'name' => 'bing_ads_tag_id'
                        ),

                        array(
                            'type' => 'button',
                            'label' => $this->language->get('generate_workspace'),
                            'text' => '<i class="fa fa-floppy-o"></i> '.$this->language->get('generate_workspace'),
                            'onclick' => 'generate_workspace();'
                        ),
                    ),
                ),
                $this->language->get('tab_gdpr') => array(
                    'icon' => '<i class="fa fa-cog"></i>',
                    'fields' => array(
                        array(
                            'text' => '<i class="fa fa-cog"></i>'.$this->language->get('gdpr_configuration'),
                            'type' => 'legend',
                        ),

                        array(
                            'label' => $this->language->get('gdpr_status'),
                            'type' => 'boolean',
                            'value' => $this->config->get('google_gdpr_status'),
                            'name' => 'gdpr_status',
                        ),

                        array(
                            'label' => $this->language->get('gdpr_position'),
                            'type' => 'select',
                            'options' => array(
                                'top' => $this->language->get('gdpr_position_top'),
                                'bottom' => $this->language->get('gdpr_position_bottom')
                            ),
                            'value' => $this->config->get('google_gdpr_position'),
                            'name' => 'gdpr_position',
                        ),

                        array(
                            'label' => $this->language->get('gdpr_button_position'),
                            'type' => 'select',
                            'options' => array(
                                '' => $this->language->get('gdpr_button_none_button'),
                                'top_left' => $this->language->get('gdpr_button_position_top_left'),
                                'top_right' => $this->language->get('gdpr_button_position_top_right'),
                                'bottom_left' => $this->language->get('gdpr_button_position_bottom_left'),
                                'bottom_right' => $this->language->get('gdpr_button_position_bottom_right')
                            ),
                            'value' => $this->config->get('google_gdpr_button_position'),
                            'name' => 'gdpr_button_position',
                        ),

                        array(
                            'label' => $this->language->get('gdpr_button_title'),
                            'type' => 'text',
                            'name' => 'gdpr_button_title',
                            'default' => 'Cookies configuration',
                            'value' => $this->config->get('google_gdpr_button_title'),
                            'multilanguage' => true
                        ),

                        array(
                            'label' => $this->language->get('gdpr_conatiner_width'),
                            'type' => 'text',
                            'name' => 'gdpr_conatiner_width',
                            'default' => '1170',
                            'value' => $this->config->get('google_gdpr_conatiner_width')
                        ),

                        array(
                            'label' => $this->language->get('gdpr_title'),
                            'type' => 'text',
                            'name' => 'gdpr_title',
                            'default' => 'This site uses cookies.',
                            'value' => $this->config->get('google_gdpr_title'),
                            'multilanguage' => true
                        ),

                        array(
                            'label' => $this->language->get('gdpr_text'),
                            'help' => $this->language->get('gdpr_text_help'),
                            'type' => 'html_editor',
                            'name' => 'gdpr_text',
                            'default' => '<p>Some of these cookies are essential, while others help us to improve your experience by providing insights into how the site is being used.</p><p>For more detailed information on the cookies we use, please check our <a target="_blank" href="#link_to_your_privacy_policy">Privacy Policy</a></p>',
                            'value' => $this->config->get('google_gdpr_text'),
                            'multilanguage' => true
                        ),

                        array(
                            'label' => $this->language->get('gdpr_button_accept_text'),
                            'type' => 'text',
                            'name' => 'gdpr_button_accept_text',
                            'default' => 'Accept current settings',
                            'value' => $this->config->get('google_gdpr_button_accept_text'),
                            'multilanguage' => true
                        ),

                        array(
                            'label' => $this->language->get('gdpr_button_checkbox_statistics'),
                            'type' => 'text',
                            'name' => 'gdpr_button_checkbox_statistics',
                            'default' => 'Statistics',
                            'value' => $this->config->get('google_gdpr_button_checkbox_statistics'),
                            'multilanguage' => true
                        ),

                        array(
                            'label' => $this->language->get('gdpr_button_checkbox_marketing'),
                            'type' => 'text',
                            'name' => 'gdpr_button_checkbox_marketing',
                            'default' => 'Marketing',
                            'value' => $this->config->get('google_gdpr_button_checkbox_marketing'),
                            'multilanguage' => true
                        ),

                        array(
                            'label' => $this->language->get('gdpr_bar_background'),
                            'type' => 'colpick',
                            'default' => '0077a7',
                            'value' => $this->config->get('google_gdpr_bar_background'),
                            'name' => 'gdpr_bar_background'
                        ),

                        array(
                            'label' => $this->language->get('gdpr_bar_color'),
                            'type' => 'colpick',
                            'default' => 'ffffff',
                            'value' => $this->config->get('google_gdpr_bar_color'),
                            'name' => 'gdpr_bar_color'
                        ),

                        array(
                            'label' => $this->language->get('gdpr_button_configure_background'),
                            'type' => 'colpick',
                            'default' => '0077a7',
                            'value' => $this->config->get('google_gdpr_button_configure_background'),
                            'name' => 'gdpr_button_configure_background'
                        ),

                        array(
                            'label' => $this->language->get('gdpr_bar_background_button'),
                            'type' => 'colpick',
                            'default' => 'ffffff',
                            'value' => $this->config->get('google_gdpr_bar_background_button'),
                            'name' => 'gdpr_bar_background_button'
                        ),

                        array(
                            'label' => $this->language->get('gdpr_bar_background_button_hover'),
                            'type' => 'colpick',
                            'default' => 'eaeaea',
                            'value' => $this->config->get('google_gdpr_bar_background_button_hover'),
                            'name' => 'gdpr_bar_background_button_hover'
                        ),

                        array(
                            'label' => $this->language->get('gdpr_bar_background_link'),
                            'type' => 'colpick',
                            'default' => 'ffffff',
                            'value' => $this->config->get('google_gdpr_bar_background_link'),
                            'name' => 'gdpr_bar_background_link'
                        ),

                        array(
                            'label' => $this->language->get('gdpr_bar_background_link_hover'),
                            'type' => 'colpick',
                            'default' => 'eaeaea',
                            'value' => $this->config->get('google_gdpr_bar_background_link_hover'),
                            'name' => 'gdpr_bar_background_link_hover'
                        ),

                        array(
                            'label' => $this->language->get('gdpr_bar_color_button'),
                            'type' => 'colpick',
                            'default' => '111125',
                            'value' => $this->config->get('google_gdpr_bar_color_button'),
                            'name' => 'gdpr_bar_color_button'
                        ),

                        array(
                            'label' => $this->language->get('gdpr_custom_code'),
                            'help' => $this->language->get('gdpr_custom_code_help'),
                            'type' => 'textarea',
                            'value' => $this->config->get('google_gdpr_custom_code'),
                            'name' => 'gdpr_custom_code'
                        ),
                    ),
                ),
                $this->language->get('tab_feeds_configurations') => array(
                    'icon' => '<i class="fa fa-rss"></i>',
                    'fields' => array(
                        array(
                            'type' => 'html_hard',
                            'html_code' => '<center><img class="img-responsive" src="'.HTTP_CATALOG.'admin/view/image/gmt/feed_logos.jpg" class="icon_tab"></center>'
                        ),

                        array(
                            'text' => '<i class="fa fa-cog"></i>'.$this->language->get('feeds_choose_type_legend'),
                            'type' => 'legend',
                            'class' => 'feeds_legend'
                        ),

                        array(
                            'label' => $this->language->get('feeds_choose_type'),
                            'type' => 'select',
                            'class' => 'feed_type_selector',
                            'onchange' => 'show_feed_container($(this))',
                            'options' => $this->feed_types,
                            'value' => $this->config->get('feeds_choose_type'),
                            'name' => 'feeds_choose_type',
                            'after' => '<script> var feed_types = '.json_encode($this->feed_types).'; </script>'
                        ),
                    ),
                ),
                $this->language->get('tab_merchant_center') => array(
                    'icon' => '<i class="fa fa-rss"></i>',
                    'fields' => array(
                        array(
                            'text' => '<i class="fa fa-cog"></i>'.$this->language->get('google_base_pro_configuration_legend').' - '.$this->language->get('tab_merchant_center'),
                            'type' => 'legend',
                        ),

                        array(
                            'label' => $this->language->get('google_base_pro_configuration_select'),
                            'type' => 'select',
                            'value' => $this->config->get('google_base_pro_configuration_select'),
                            'name' => 'base_pro_config_selected',
                            'class' => 'select_merchat_center_configuration',
                            'options' => array()
                        ),

                        array(
                            'type' => 'button',
                            'text' => $this->language->get('google_base_pro_configuration_load'),
                            'label' => $this->language->get('google_base_pro_configuration_load'),
                            'onclick' => 'feed_load_configuration($(this), \'google\');',
                            'href' => 'javascript:{}'
                        ),

                        array(
                            'type' => 'button',
                            'text' => $this->language->get('google_base_pro_configuration_delete'),
                            'label' => $this->language->get('google_base_pro_configuration_delete'),
                            'onclick' => 'feed_delete_configuration($(this), \'google\');',
                            'href' => 'javascript:{}'
                        ),

                        array(
                            'type' => 'text',
                            'label' => $this->language->get('google_base_pro_configuration_name'),
                            'href' => 'javascript:{}',
                            'after' => '<a onclick="feed_save_configuration($(this),  \'google\');" href="javascript:{}" class="button" style="margin-top:10px;">'.$this->language->get('google_base_pro_configuration_save').'</a>',
                            'name' => 'base_pro_config_name',
                        ),

                        array(
                            'type' => 'button',
                            'text' => $this->language->get('google_base_pro_configuration_restore'),
                            'label' => $this->language->get('google_base_pro_configuration_restore'),
                            'help' => $this->language->get('google_base_pro_configuration_restore_help'),
                            'onclick' => 'feed_restore_configuration($(this), \'google\');',
                            'href' => 'javascript:{}'
                        ),

                        array(
                            'text' => '<i class="fa fa-file-code-o" aria-hidden="true"></i>'.$this->language->get('google_base_pro_feed_urls'),
                            'type' => 'legend',
                        ),

                        array(
                            'type' => 'html_code',
                            'html_code' => '<div class="google_base_pro_feed_urls"></div>',
                        ),

                        array(
                            'text' => '<i class="fa fa-cog"></i>'.$this->language->get('configuration'),
                            'type' => 'legend',
                        ),

                        array(
                            'label' => $this->language->get('status'),
                            'type' => 'boolean',
                            'value' => $this->config->get('google_base_pro_status'),
                            'name' => 'base_pro_status'
                        ),

                        array(
                            'label' => $this->language->get('google_base_pro_title'),
                            'type' => 'text',
                            'value' => $this->config->get('google_base_pro_title'),
                            'name' => 'base_pro_title'
                        ),

                        array(
                            'label' => $this->language->get('google_base_pro_description'),
                            'type' => 'text',
                            'value' => $this->config->get('google_base_pro_description'),
                            'name' => 'base_pro_description'
                        ),

                        array(
                            'label' => $this->language->get('google_base_pro_link'),
                            'type' => 'text',
                            'value' => $this->config->get('google_base_pro_link'),
                            'name' => 'base_pro_link'
                        ),

                        array(
                            'label' => $this->language->get('google_base_pro_thumb_width'),
                            'help' => $this->language->get('google_base_pro_thumb_width_help'),
                            'type' => 'text',
                            'value' => $this->config->get('google_base_pro_thumb_width'),
                            'name' => 'base_pro_thumb_width',
                            'default' => 600
                        ),

                        array(
                            'label' => $this->language->get('google_base_pro_thumb_height'),
                            'help' => $this->language->get('google_base_pro_thumb_height_help'),
                            'type' => 'text',
                            'value' => $this->config->get('google_base_pro_thumb_height'),
                            'name' => 'base_pro_thumb_height',
                            'default' => 600
                        ),

                        array(
                            'label' => $this->language->get('google_base_pro_ignore_products'),
                            'help' => $this->language->get('google_base_pro_ignore_products_help'),
                            'type' => 'textarea',
                            'style' => 'height:100px;',
                            'value' => $this->config->get('google_base_pro_ignore_products'),
                            'name' => 'base_pro_ignore_products'
                        ),

                        array(
                            'label' => $this->language->get('only_these_products'),
                            'help' => $this->language->get('only_these_products_help'),
                            'type' => 'products_autocomplete',
                            'value' => '',
                            'name' => 'base_pro_only_these_products'
                        ),

                        array(
                            'label' => $this->language->get('google_base_pro_show_out_stock'),
                            'type' => 'boolean',
                            'value' => $this->config->get('google_base_pro_show_out_stock'),
                            'name' => 'base_pro_show_out_stock'
                        ),

                        array(
                            'label' => $this->language->get('google_base_pro_option_split'),
                            'help' => $this->language->get('google_base_pro_option_split_help'),
                            'type' => 'boolean',
                            'value' => $this->config->get('google_base_pro_option_split'),
                            'name' => 'base_pro_option_split'
                        ),

                        //PRODUCTS ATTRIBUTES CONFIGURATION
                            array(
                              'text' => '<i class="fa fa-cog"></i>'.$this->language->get('configuration').' - '.$this->language->get('google_base_pro_products_attributes'),
                              'type' => 'legend',
                            ),

                            array(
                              'label' => $this->language->get('google_base_pro_product_id'),
                              'type' => 'boolean',
                              'value' => $this->config->get('google_base_pro_product_id'),
                              'name' => 'base_pro_product_id'
                            ),

                            array(
                              'label' => $this->language->get('google_base_pro_product_id_like'),
                              'type' => 'select',
                              'options' => $product_identificators,
                              'value' => $this->config->get('google_base_pro_product_id_like'),
                              'name' => 'base_pro_product_id_like'
                            ),

                            array(
                              'label' => $this->language->get('google_base_pro_multiples_identificators'),
                              'help' => $this->language->get('google_base_pro_multiples_identificators_help'),
                              'type' => 'boolean',
                              'value' => $this->config->get('google_base_pro_multiples_identificators'),
                              'name' => 'base_pro_multiples_identificators'
                            ),

                            array(
                              'label' => $this->language->get('google_base_pro_identifier_exists'),
                              'help' => $this->language->get('google_base_pro_identifier_exists_help'),
                              'type' => 'boolean',
                              'value' => $this->config->get('google_base_pro_identifier_exists'),
                              'name' => 'base_pro_identifier_exists'
                            ),

                            array(
                              'label' => $this->language->get('google_base_pro_product_title'),
                              'type' => 'boolean',
                              'value' => $this->config->get('google_base_pro_product_title'),
                              'name' => 'base_pro_product_title'
                            ),

                            array(
                              'label' => $this->language->get('google_base_pro_product_link'),
                              'type' => 'boolean',
                              'value' => $this->config->get('google_base_pro_product_link'),
                              'name' => 'base_pro_product_link'
                            ),

                            array(
                              'label' => $this->language->get('google_base_pro_product_description'),
                              'type' => 'boolean',
                              'value' => $this->config->get('google_base_pro_product_description'),
                              'name' => 'base_pro_product_description'
                            ),

                            array(
                              'label' => $this->language->get('google_base_pro_product_brand'),
                              'type' => 'boolean',
                              'value' => $this->config->get('google_base_pro_product_brand'),
                              'name' => 'base_pro_product_brand'
                            ),

                            array(
                              'label' => $this->language->get('google_base_pro_product_condition'),
                              'type' => 'boolean',
                              'value' => $this->config->get('google_base_pro_product_condition'),
                              'name' => 'base_pro_product_condition'
                            ),

                            array(
                              'label' => $this->language->get('google_base_pro_product_image_link'),
                              'type' => 'boolean',
                              'value' => $this->config->get('google_base_pro_product_image_link'),
                              'name' => 'base_pro_product_image_link'
                            ),

                            array(
                                'label' => $this->language->get('google_base_pro_product_additional_images'),
                                'help' => $this->language->get('google_base_pro_product_additional_images_help'),
                                'type' => 'boolean',
                                'name' => 'google_base_pro_product_additional_images'
                            ),

                            array(
                              'label' => $this->language->get('google_base_pro_product_price'),
                              'type' => 'boolean',
                              'value' => $this->config->get('google_base_pro_product_price'),
                              'name' => 'base_pro_product_price'
                            ),

                            array(
                              'label' => $this->language->get('google_base_pro_product_sale_price'),
                              'type' => 'boolean',
                              'value' => $this->config->get('google_base_pro_product_sale_price'),
                              'name' => 'base_pro_product_sale_price'
                            ),

                            array(
                              'label' => $this->language->get('google_base_pro_product_sale_price_customer_group'),
                              'help' => $this->language->get('google_base_pro_product_sale_price_customer_group_help'),
                              'type' => 'select',
                              'options' => $this->cgf,
                              'value' => $this->config->get('google_base_pro_product_sale_price_customer_group'),
                              'name' => 'base_pro_product_sale_price_customer_group'
                            ),

                            array(
                              'label' => $this->language->get('google_base_pro_product_include_tax'),
                              'type' => 'boolean',
                              'value' => $this->config->get('google_base_pro_product_include_tax'),
                              'name' => 'base_pro_product_include_tax'
                            ),

                            array(
                              'label' => $this->language->get('google_base_pro_product_type'),
                              'type' => 'boolean',
                              'value' => $this->config->get('google_base_pro_product_type'),
                              'name' => 'base_pro_product_type'
                            ),

                            array(
                              'label' => $this->language->get('google_base_pro_product_quantity'),
                              'type' => 'boolean',
                              'value' => $this->config->get('google_base_pro_product_quantity'),
                              'name' => 'base_pro_product_quantity'
                            ),

                            array(
                              'label' => $this->language->get('google_base_pro_product_weight'),
                              'type' => 'boolean',
                              'value' => $this->config->get('google_base_pro_product_weight'),
                              'name' => 'base_pro_product_weight'
                            ),

                            array(
                              'label' => $this->language->get('google_base_pro_product_availability'),
                              'type' => 'boolean',
                              'value' => $this->config->get('google_base_pro_product_availability'),
                              'name' => 'base_pro_product_availability'
                            ),
                            //Size
                                array(
                                    'label' => $this->language->get('google_base_pro_product_size'),
                                    'help' => $this->language->get('google_base_pro_product_size_help'),
                                    'type' => 'boolean',
                                    'value' => $this->config->get('google_base_pro_product_size'),
                                    'class' => 'special_attributes_feed_checkbox',
                                    'name' => 'base_pro_product_size',
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_size_attribute'),
                                    'type' => 'select',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_size_attribute'),
                                    'name' => 'base_pro_product_size_attribute',
                                    'options' => $attributes_to_select
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_size_filter'),
                                    'type' => 'select',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_size_filter'),
                                    'name' => 'base_pro_product_size_filter',
                                    'options' => $filters_to_select
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_size_option'),
                                    'type' => 'select',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_size_option'),
                                    'name' => 'base_pro_product_size_option',
                                    'options' => $options_to_select
                                ),
                            //END Size
                            //Color
                                array(
                                    'label' => $this->language->get('google_base_pro_product_color'),
                                    'type' => 'boolean',
                                    'value' => $this->config->get('google_base_pro_product_color'),
                                    'class' => 'special_attributes_feed_checkbox',
                                    'name' => 'base_pro_product_color',
                                ),
                                array(
                                    'label' => $this->language->get('google_base_pro_product_color_slipt'),
                                    'help' => $this->language->get('google_base_pro_product_color_slipt_help'),
                                    'type' => 'boolean',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_color_slipt'),
                                    'name' => 'base_pro_product_color_split',
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_color_attribute'),
                                    'type' => 'select',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_color_attribute'),
                                    'name' => 'base_pro_product_color_attribute',
                                    'options' => $attributes_to_select
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_color_filter'),
                                    'type' => 'select',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_color_filter'),
                                    'name' => 'base_pro_product_color_filter',
                                    'options' => $filters_to_select
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_color_option'),
                                    'type' => 'select',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_color_option'),
                                    'name' => 'base_pro_product_color_option',
                                    'options' => $options_to_select
                                ),
                            //END Color
                            //Material
                                array(
                                    'label' => $this->language->get('google_base_pro_product_material'),
                                    'type' => 'boolean',
                                    'value' => $this->config->get('google_base_pro_product_material'),
                                    'class' => 'special_attributes_feed_checkbox',
                                    'name' => 'base_pro_product_material',
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_material_attribute'),
                                    'type' => 'select',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_material_attribute'),
                                    'name' => 'base_pro_product_material_attribute',
                                    'options' => $attributes_to_select
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_material_filter'),
                                    'type' => 'select',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_material_filter'),
                                    'name' => 'base_pro_product_material_filter',
                                    'options' => $filters_to_select
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_material_option'),
                                    'type' => 'select',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_material_option'),
                                    'name' => 'base_pro_product_material_option',
                                    'options' => $options_to_select
                                ),
                            //END Material
                            //Gender
                                array(
                                    'label' => $this->language->get('google_base_pro_product_gender'),
                                    'help' => $this->language->get('google_base_pro_product_gender_help'),
                                    'type' => 'boolean',
                                    'value' => $this->config->get('google_base_pro_product_gender'),
                                    'class' => 'special_attributes_feed_checkbox',
                                    'name' => 'base_pro_product_gender',
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_gender_attribute'),
                                    'type' => 'select',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_gender_attribute'),
                                    'name' => 'base_pro_product_gender_attribute',
                                    'options' => $attributes_to_select
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_gender_filter'),
                                    'type' => 'select',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_gender_filter'),
                                    'name' => 'base_pro_product_gender_filter',
                                    'options' => $filters_to_select
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_gender_option'),
                                    'type' => 'select',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_gender_option'),
                                    'name' => 'base_pro_product_gender_option',
                                    'options' => $options_to_select
                                ),
                            //END Gender

                            //Age Group
                                array(
                                    'label' => $this->language->get('google_base_pro_product_age_group'),
                                    'help' => $this->language->get('google_base_pro_product_age_group_help'),
                                    'type' => 'boolean',
                                    'value' => $this->config->get('google_base_pro_product_age_group'),
                                    'class' => 'special_attributes_feed_checkbox',
                                    'name' => 'base_pro_product_age_group',
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_age_group_attribute'),
                                    'type' => 'select',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_age_group_attribute'),
                                    'name' => 'base_pro_product_age_group_attribute',
                                    'options' => $attributes_to_select
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_age_group_filter'),
                                    'type' => 'select',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_age_group_filter'),
                                    'name' => 'base_pro_product_age_group_filter',
                                    'options' => $filters_to_select
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_age_group_option'),
                                    'type' => 'select',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_age_group_option'),
                                    'name' => 'base_pro_product_age_group_option',
                                    'options' => $options_to_select
                                ),
                            //END Age Group

                            //Custom label 0
                                array(
                                    'label' => $this->language->get('google_base_pro_product_custom_label_0'),
                                    'help' => $this->language->get('google_base_pro_product_custom_label_help'),
                                    'type' => 'boolean',
                                    'value' => $this->config->get('google_base_pro_product_custom_label_0'),
                                    'class' => 'special_attributes_feed_checkbox',
                                    'name' => 'base_pro_product_custom_label_0',
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_custom_label_0_fixed_word'),
                                    'type' => 'text',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_custom_label_0_fixed_word'),
                                    'name' => 'base_pro_product_custom_label_0_fixed_word'
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_custom_label_0_attribute'),
                                    'type' => 'select',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_custom_label_0_attribute'),
                                    'name' => 'base_pro_product_custom_label_0_attribute',
                                    'options' => $attributes_to_select
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_custom_label_0_filter'),
                                    'type' => 'select',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_custom_label_0_filter'),
                                    'name' => 'base_pro_product_custom_label_0_filter',
                                    'options' => $filters_to_select
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_custom_label_0_option'),
                                    'type' => 'select',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_custom_label_0_option'),
                                    'name' => 'base_pro_product_custom_label_0_option',
                                    'options' => $options_to_select
                                ),
                            //END Custom label 0

                            //Custom label 1
                                array(
                                    'label' => $this->language->get('google_base_pro_product_custom_label_1'),
                                    'help' => $this->language->get('google_base_pro_product_custom_label_help'),
                                    'type' => 'boolean',
                                    'value' => $this->config->get('google_base_pro_product_custom_label_1'),
                                    'class' => 'special_attributes_feed_checkbox',
                                    'name' => 'base_pro_product_custom_label_1',
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_custom_label_1_fixed_word'),
                                    'type' => 'text',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_custom_label_1_fixed_word'),
                                    'name' => 'base_pro_product_custom_label_1_fixed_word'
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_custom_label_1_attribute'),
                                    'type' => 'select',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_custom_label_1_attribute'),
                                    'name' => 'base_pro_product_custom_label_1_attribute',
                                    'options' => $attributes_to_select
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_custom_label_1_filter'),
                                    'type' => 'select',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_custom_label_1_filter'),
                                    'name' => 'base_pro_product_custom_label_1_filter',
                                    'options' => $filters_to_select
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_custom_label_1_option'),
                                    'type' => 'select',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_custom_label_1_option'),
                                    'name' => 'base_pro_product_custom_label_1_option',
                                    'options' => $options_to_select
                                ),
                            //END Custom label 1

                            //Custom label 2
                                array(
                                    'label' => $this->language->get('google_base_pro_product_custom_label_2'),
                                    'help' => $this->language->get('google_base_pro_product_custom_label_help'),
                                    'type' => 'boolean',
                                    'value' => $this->config->get('google_base_pro_product_custom_label_2'),
                                    'class' => 'special_attributes_feed_checkbox',
                                    'name' => 'base_pro_product_custom_label_2',
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_custom_label_2_fixed_word'),
                                    'type' => 'text',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_custom_label_2_fixed_word'),
                                    'name' => 'base_pro_product_custom_label_2_fixed_word'
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_custom_label_2_attribute'),
                                    'type' => 'select',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_custom_label_2_attribute'),
                                    'name' => 'base_pro_product_custom_label_2_attribute',
                                    'options' => $attributes_to_select
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_custom_label_2_filter'),
                                    'type' => 'select',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_custom_label_2_filter'),
                                    'name' => 'base_pro_product_custom_label_2_filter',
                                    'options' => $filters_to_select
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_custom_label_2_option'),
                                    'type' => 'select',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_custom_label_2_option'),
                                    'name' => 'base_pro_product_custom_label_2_option',
                                    'options' => $options_to_select
                                ),
                            //END Custom label 2

                            //Custom label 3
                                array(
                                    'label' => $this->language->get('google_base_pro_product_custom_label_3'),
                                    'help' => $this->language->get('google_base_pro_product_custom_label_help'),
                                    'type' => 'boolean',
                                    'value' => $this->config->get('google_base_pro_product_custom_label_3'),
                                    'class' => 'special_attributes_feed_checkbox',
                                    'name' => 'base_pro_product_custom_label_3',
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_custom_label_3_fixed_word'),
                                    'type' => 'text',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_custom_label_3_fixed_word'),
                                    'name' => 'base_pro_product_custom_label_3_fixed_word'
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_custom_label_3_attribute'),
                                    'type' => 'select',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_custom_label_3_attribute'),
                                    'name' => 'base_pro_product_custom_label_3_attribute',
                                    'options' => $attributes_to_select
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_custom_label_3_filter'),
                                    'type' => 'select',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_custom_label_3_filter'),
                                    'name' => 'base_pro_product_custom_label_3_filter',
                                    'options' => $filters_to_select
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_custom_label_3_option'),
                                    'type' => 'select',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_custom_label_3_option'),
                                    'name' => 'base_pro_product_custom_label_3_option',
                                    'options' => $options_to_select
                                ),
                            //END Custom label 3

                            //Custom label 4
                                array(
                                    'label' => $this->language->get('google_base_pro_product_custom_label_4'),
                                    'help' => $this->language->get('google_base_pro_product_custom_label_help'),
                                    'type' => 'boolean',
                                    'value' => $this->config->get('google_base_pro_product_custom_label_4'),
                                    'class' => 'special_attributes_feed_checkbox',
                                    'name' => 'base_pro_product_custom_label_4',
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_custom_label_4_fixed_word'),
                                    'type' => 'text',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_custom_label_4_fixed_word'),
                                    'name' => 'base_pro_product_custom_label_4_fixed_word'
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_custom_label_4_attribute'),
                                    'type' => 'select',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_custom_label_4_attribute'),
                                    'name' => 'base_pro_product_custom_label_4_attribute',
                                    'options' => $attributes_to_select
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_custom_label_4_filter'),
                                    'type' => 'select',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_custom_label_4_filter'),
                                    'name' => 'base_pro_product_custom_label_4_filter',
                                    'options' => $filters_to_select
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_custom_label_4_option'),
                                    'type' => 'select',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_custom_label_4_option'),
                                    'name' => 'base_pro_product_custom_label_4_option',
                                    'options' => $options_to_select
                                ),
                            //END Custom label 4

                            //Custom label 5
                                array(
                                    'label' => $this->language->get('google_base_pro_product_custom_label_5'),
                                    'help' => $this->language->get('google_base_pro_product_custom_label_help'),
                                    'type' => 'boolean',
                                    'value' => $this->config->get('google_base_pro_product_custom_label_5'),
                                    'class' => 'special_attributes_feed_checkbox',
                                    'name' => 'base_pro_product_custom_label_5',
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_custom_label_5_fixed_word'),
                                    'type' => 'text',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_custom_label_5_fixed_word'),
                                    'name' => 'base_pro_product_custom_label_5_fixed_word'
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_custom_label_5_attribute'),
                                    'type' => 'select',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_custom_label_5_attribute'),
                                    'name' => 'base_pro_product_custom_label_5_attribute',
                                    'options' => $attributes_to_select
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_custom_label_5_filter'),
                                    'type' => 'select',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_custom_label_5_filter'),
                                    'name' => 'base_pro_product_custom_label_5_filter',
                                    'options' => $filters_to_select
                                ),

                                array(
                                    'label' => $this->language->get('google_base_pro_product_custom_label_5_option'),
                                    'type' => 'select',
                                    'class' => 'special_attributes_feed',
                                    'value' => $this->config->get('google_base_pro_product_custom_label_5_option'),
                                    'name' => 'base_pro_product_custom_label_5_option',
                                    'options' => $options_to_select
                                ),
                            //END Custom label 5
                        //END PRODUCTS ATTRIBUTES CONFIGURATION

                        //Google merchancenter categories
                            array(
                                'text' => '<i class="fa fa-cog"></i>'.$this->language->get('configuration').' - '.$this->language->get('google_base_pro_products_google_categories'),
                                'type' => 'legend',
                            ),
                            array(
                                'label' => $this->language->get('google_base_pro_country'),
                                'type' => 'select',
                                'class' => 'merchantcenter_country',
                                'value' => $this->config->get('google_base_pro_merchantcenter_country'),
                                'name' => 'base_pro_merchantcenter_country',
                                'options' => $merchant_center_countries
                            ),
                        //END Google merchancenter categories
                    ),
                ),
                $this->language->get('tab_feed_shopping_reviews') => array(
                    'icon' => '<i class="fa fa-rss"></i>',
                    'fields' => array(
                        array(
                            'text' => '<i class="fa fa-cog"></i>'.$this->language->get('reviews_base_pro_configuration_legend'),
                            'type' => 'legend',
                        ),

                        array(
                            'label' => $this->language->get('reviews_base_pro_configuration_select'),
                            'type' => 'select',
                            'value' => $this->config->get('reviews_base_pro_configuration_select'),
                            'name' => 'reviews_base_pro_config_selected',
                            'class' => 'select_shopping_reviews_configuration',
                            'options' => array()
                        ),

                        array(
                            'type' => 'button',
                            'text' => $this->language->get('reviews_base_pro_configuration_load'),
                            'label' => $this->language->get('reviews_base_pro_configuration_load'),
                            'onclick' => 'feed_load_configuration($(this), \'google_reviews\');',
                            'href' => 'javascript:{}'
                        ),

                        array(
                            'type' => 'button',
                            'text' => $this->language->get('reviews_base_pro_configuration_delete'),
                            'label' => $this->language->get('reviews_base_pro_configuration_delete'),
                            'onclick' => 'feed_delete_configuration($(this), \'google_reviews\');',
                            'href' => 'javascript:{}'
                        ),

                        array(
                            'type' => 'text',
                            'label' => $this->language->get('reviews_base_pro_configuration_name'),
                            'href' => 'javascript:{}',
                            'after' => '<a onclick="feed_save_configuration($(this),  \'google_reviews\');" href="javascript:{}" class="button" style="margin-top:10px;">'.$this->language->get('reviews_base_pro_configuration_save').'</a>',
                            'name' => 'reviews_base_pro_config_name',
                        ),

                        array(
                            'type' => 'button',
                            'text' => $this->language->get('reviews_base_pro_configuration_restore'),
                            'label' => $this->language->get('reviews_base_pro_configuration_restore'),
                            'help' => $this->language->get('reviews_base_pro_configuration_restore_help'),
                            'onclick' => 'feed_restore_configuration($(this), \'google_reviews\');',
                            'href' => 'javascript:{}'
                        ),

                        array(
                            'text' => '<i class="fa fa-file-code-o" aria-hidden="true"></i>'.$this->language->get('reviews_base_pro_feed_urls'),
                            'type' => 'legend',
                        ),

                        array(
                            'type' => 'html_code',
                            'html_code' => '<div class="google_reviews_base_pro_feed_urls"></div>',
                        ),

                        array(
                            'text' => '<i class="fa fa-cog"></i>'.$this->language->get('configuration'),
                            'type' => 'legend',
                        ),

                        array(
                            'label' => $this->language->get('status'),
                            'type' => 'boolean',
                            'value' => $this->config->get('reviews_base_pro_status'),
                            'name' => 'reviews_base_pro_status'
                        ),
                        array(
                            'label' => $this->language->get('reviews_base_pro_ignore_products'),
                            'help' => $this->language->get('reviews_base_pro_ignore_products_help'),
                            'type' => 'textarea',
                            'style' => 'height:100px;',
                            'value' => $this->config->get('reviews_base_pro_ignore_products'),
                            'name' => 'reviews_base_pro_ignore_products'
                        ),
                        array(
                            'label' => $this->language->get('only_these_products'),
                            'help' => $this->language->get('only_these_products_help'),
                            'type' => 'products_autocomplete',
                            'value' => '',
                            'name' => 'reviews_base_pro_only_these_products'
                        ),
                    )
                ),
                $this->language->get('tab_google_business_feed') => array(
                    'icon' => '<i class="fa fa-rss"></i>',
                    'fields' => array(
                        array(
                            'text' => '<i class="fa fa-cog"></i>'.$this->language->get('business_base_pro_configuration_legend').' - '.$this->language->get('tab_google_business_feed'),
                            'type' => 'legend',
                        ),

                        array(
                            'label' => $this->language->get('business_base_pro_configuration_select'),
                            'type' => 'select',
                            'value' => $this->config->get('business_base_pro_configuration_select'),
                            'name' => 'business_base_pro_config_selected',
                            'class' => 'select_merchat_center_configuration',
                            'options' => array()
                        ),

                        array(
                            'type' => 'button',
                            'text' => $this->language->get('business_base_pro_configuration_load'),
                            'label' => $this->language->get('business_base_pro_configuration_load'),
                            'onclick' => 'feed_load_configuration($(this), \'google_business\');',
                            'href' => 'javascript:{}'
                        ),

                        array(
                            'type' => 'button',
                            'text' => $this->language->get('business_base_pro_configuration_delete'),
                            'label' => $this->language->get('business_base_pro_configuration_delete'),
                            'onclick' => 'feed_delete_configuration($(this), \'google_business\');',
                            'href' => 'javascript:{}'
                        ),

                        array(
                            'type' => 'text',
                            'label' => $this->language->get('business_base_pro_configuration_name'),
                            'href' => 'javascript:{}',
                            'after' => '<a onclick="feed_save_configuration($(this), \'google_business\');" href="javascript:{}" class="button" style="margin-top:10px;">'.$this->language->get('business_base_pro_configuration_save').'</a>',
                            'name' => 'business_base_pro_config_name',
                        ),

                        array(
                            'type' => 'button',
                            'text' => $this->language->get('business_base_pro_configuration_restore'),
                            'label' => $this->language->get('business_base_pro_configuration_restore'),
                            'help' => $this->language->get('business_base_pro_configuration_restore_help'),
                            'onclick' => 'feed_restore_configuration($(this), \'google_business\');',
                            'href' => 'javascript:{}'
                        ),

                        array(
                            'text' => '<i class="fa fa-file-code-o" aria-hidden="true"></i>'.$this->language->get('business_base_pro_feed_urls'),
                            'type' => 'legend',
                        ),

                        array(
                            'type' => 'html_code',
                            'html_code' => '<div class="google_business_base_pro_feed_urls"></div>',
                        ),

                        array(
                            'text' => '<i class="fa fa-cog"></i>'.$this->language->get('configuration'),
                            'type' => 'legend',
                        ),

                        array(
                            'label' => $this->language->get('status'),
                            'type' => 'boolean',
                            'name' => 'business_base_pro_status'
                        ),

                        array(
                            'label' => $this->language->get('business_base_pro_thumb_width'),
                            'help' => $this->language->get('business_base_pro_thumb_width_help'),
                            'type' => 'text',
                            'name' => 'business_base_pro_thumb_width',
                            'default' => 600
                        ),

                        array(
                            'label' => $this->language->get('business_base_pro_thumb_height'),
                            'help' => $this->language->get('business_base_pro_thumb_height_help'),
                            'type' => 'text',
                            'name' => 'business_base_pro_thumb_height',
                            'default' => 600
                        ),

                        array(
                            'label' => $this->language->get('business_base_pro_ignore_products'),
                            'help' => $this->language->get('business_base_pro_ignore_products_help'),
                            'type' => 'textarea',
                            'style' => 'height:100px;',
                            'name' => 'business_base_pro_ignore_products'
                        ),

                        array(
                            'label' => $this->language->get('only_these_products'),
                            'help' => $this->language->get('only_these_products_help'),
                            'type' => 'products_autocomplete',
                            'value' => '',
                            'name' => 'business_base_pro_only_these_products'
                        ),

                        array(
                            'label' => $this->language->get('business_base_pro_show_out_stock'),
                            'type' => 'boolean',
                            'name' => 'business_base_pro_show_out_stock'
                        ),

                        /*Product attrubutes feed*/
                            array(
                              'text' => '<i class="fa fa-cog"></i>'.$this->language->get('configuration').' - '.$this->language->get('business_base_pro_products_attributes'),
                              'type' => 'legend',
                            ),

                            array(
                              'label' => $this->language->get('business_base_pro_product_id'),
                              'type' => 'boolean',
                              'name' => 'business_base_pro_product_id'
                            ),

                            array(
                              'label' => $this->language->get('business_base_pro_product_id2'),
                              'help' => $this->language->get('business_base_pro_product_id2_help'),
                              'type' => 'boolean',
                              'name' => 'business_base_pro_product_id2'
                            ),

                            array(
                              'label' => $this->language->get('business_base_pro_product_title'),
                              'type' => 'boolean',
                              'name' => 'business_base_pro_product_title'
                            ),

                            array(
                              'label' => $this->language->get('business_base_pro_product_keywords'),
                              'type' => 'boolean',
                              'name' => 'business_base_pro_product_keywords'
                            ),

                            array(
                              'label' => $this->language->get('business_base_pro_product_category'),
                              'type' => 'boolean',
                              'name' => 'business_base_pro_product_category'
                            ),

                            array(
                              'label' => $this->language->get('business_base_pro_product_description'),
                              'type' => 'boolean',
                              'name' => 'business_base_pro_product_description'
                            ),

                            array(
                              'label' => $this->language->get('business_base_pro_product_link'),
                              'type' => 'boolean',
                              'name' => 'business_base_pro_product_link'
                            ),

                            array(
                              'label' => $this->language->get('business_base_pro_product_image_link'),
                              'type' => 'boolean',
                              'name' => 'business_base_pro_product_image_link'
                            ),

                            array(
                              'label' => $this->language->get('business_base_pro_product_price'),
                              'type' => 'boolean',
                              'name' => 'business_base_pro_product_price'
                            ),

                            array(
                              'label' => $this->language->get('business_base_pro_product_price_formatted'),
                              'type' => 'boolean',
                              'name' => 'business_base_pro_product_price_formatted'
                            ),

                            array(
                              'label' => $this->language->get('business_base_pro_product_sale_price'),
                              'type' => 'boolean',
                              'name' => 'business_base_pro_product_sale_price'
                            ),

                            array(
                              'label' => $this->language->get('business_base_pro_product_sale_price_formatted'),
                              'type' => 'boolean',
                              'name' => 'business_base_pro_product_sale_price_formatted'
                            ),

                            array(
                              'label' => $this->language->get('business_base_pro_product_sale_price_customer_group'),
                              'help' => $this->language->get('business_base_pro_product_sale_price_customer_group_help'),
                              'type' => 'select',
                              'options' => $this->cgf,
                              'value' => $this->config->get('business_base_pro_product_sale_price_customer_group'),
                              'name' => 'business_base_pro_product_sale_price_customer_group'
                            ),

                            array(
                              'label' => $this->language->get('business_base_pro_product_include_tax'),
                              'type' => 'boolean',
                              'name' => 'business_base_pro_product_include_tax'
                            ),
                        /*END*/
                    ),
                ),
                $this->language->get('tab_facebook_base') => array(
                    'icon' => '<i class="fa fa-rss"></i>',
                    'fields' => array(
                        array(
                            'text' => '<i class="fa fa-cog"></i>'.$this->language->get('facebook_base_pro_configuration_legend').' - '.$this->language->get('tab_facebook_base'),
                            'type' => 'legend',
                        ),

                        array(
                            'label' => $this->language->get('facebook_base_pro_configuration_select'),
                            'type' => 'select',
                            'value' => $this->config->get('facebook_base_pro_configuration_select'),
                            'name' => 'facebook_base_pro_config_selected',
                            'class' => 'select_merchat_center_configuration',
                            'options' => array()
                        ),

                        array(
                            'type' => 'button',
                            'text' => $this->language->get('facebook_base_pro_configuration_load'),
                            'label' => $this->language->get('facebook_base_pro_configuration_load'),
                            'onclick' => 'feed_load_configuration($(this), \'google_facebook\');',
                            'href' => 'javascript:{}'
                        ),

                        array(
                            'type' => 'button',
                            'text' => $this->language->get('facebook_base_pro_configuration_delete'),
                            'label' => $this->language->get('facebook_base_pro_configuration_delete'),
                            'onclick' => 'feed_delete_configuration($(this), \'google_facebook\');',
                            'href' => 'javascript:{}'
                        ),

                        array(
                            'type' => 'text',
                            'label' => $this->language->get('facebook_base_pro_configuration_name'),
                            'href' => 'javascript:{}',
                            'after' => '<a onclick="feed_save_configuration($(this), \'google_facebook\');" href="javascript:{}" class="button" style="margin-top:10px;">'.$this->language->get('facebook_base_pro_configuration_save').'</a>',
                            'name' => 'facebook_base_pro_config_name',
                        ),

                        array(
                            'type' => 'button',
                            'text' => $this->language->get('facebook_base_pro_configuration_restore'),
                            'label' => $this->language->get('facebook_base_pro_configuration_restore'),
                            'help' => $this->language->get('facebook_base_pro_configuration_restore_help'),
                            'onclick' => 'feed_restore_configuration($(this), \'google_facebook\');',
                            'href' => 'javascript:{}'
                        ),

                        array(
                            'text' => '<i class="fa fa-file-code-o" aria-hidden="true"></i>'.$this->language->get('facebook_base_pro_feed_urls'),
                            'type' => 'legend',
                        ),

                        array(
                            'type' => 'html_code',
                            'html_code' => '<div class="google_facebook_base_pro_feed_urls"></div>',
                        ),

                        array(
                            'text' => '<i class="fa fa-cog"></i>'.$this->language->get('configuration'),
                            'type' => 'legend',
                        ),

                        array(
                            'label' => $this->language->get('status'),
                            'type' => 'boolean',
                            'name' => 'facebook_base_pro_status'
                        ),

                        array(
                            'label' => $this->language->get('facebook_base_pro_title'),
                            'type' => 'text',
                            'name' => 'facebook_base_pro_title'
                        ),

                        array(
                            'label' => $this->language->get('facebook_base_pro_description'),
                            'type' => 'text',
                            'name' => 'facebook_base_pro_description'
                        ),

                        array(
                            'label' => $this->language->get('facebook_base_pro_link'),
                            'type' => 'text',
                            'name' => 'facebook_base_pro_link'
                        ),

                        array(
                            'label' => $this->language->get('facebook_base_pro_thumb_width'),
                            'help' => $this->language->get('facebook_base_pro_thumb_width_help'),
                            'type' => 'text',
                            'name' => 'facebook_base_pro_thumb_width',
                            'default' => 600
                        ),

                        array(
                            'label' => $this->language->get('facebook_base_pro_thumb_height'),
                            'help' => $this->language->get('facebook_base_pro_thumb_height_help'),
                            'type' => 'text',
                            'name' => 'facebook_base_pro_thumb_height',
                            'default' => 600
                        ),

                        array(
                            'label' => $this->language->get('facebook_base_pro_ignore_products'),
                            'help' => $this->language->get('facebook_base_pro_ignore_products_help'),
                            'type' => 'textarea',
                            'style' => 'height:100px;',
                            'name' => 'facebook_base_pro_ignore_products'
                        ),

                        array(
                            'label' => $this->language->get('only_these_products'),
                            'help' => $this->language->get('only_these_products_help'),
                            'type' => 'products_autocomplete',
                            'value' => '',
                            'name' => 'facebook_base_pro_only_these_products'
                        ),

                        array(
                            'label' => $this->language->get('facebook_base_pro_show_out_stock'),
                            'type' => 'boolean',
                            'name' => 'facebook_base_pro_show_out_stock'
                        ),

                        /*Product attrubutes feed*/
                            array(
                              'text' => '<i class="fa fa-cog"></i>'.$this->language->get('configuration').' - '.$this->language->get('facebook_base_pro_products_attributes'),
                              'type' => 'legend',
                            ),

                            array(
                              'label' => $this->language->get('facebook_base_pro_product_id'),
                              'type' => 'boolean',
                              'name' => 'facebook_base_pro_product_id'
                            ),

                            array(
                              'label' => $this->language->get('facebook_base_pro_multiples_identificators'),
                              'help' => $this->language->get('facebook_base_pro_multiples_identificators_help'),
                              'type' => 'boolean',
                              'name' => 'facebook_base_pro_multiples_identificators'
                            ),

                            array(
                              'label' => $this->language->get('facebook_base_pro_product_title'),
                              'type' => 'boolean',
                              'name' => 'facebook_base_pro_product_title'
                            ),

                            array(
                              'label' => $this->language->get('facebook_base_pro_product_description'),
                              'type' => 'boolean',
                              'name' => 'facebook_base_pro_product_description'
                            ),

                            array(
                              'label' => $this->language->get('facebook_base_pro_product_link'),
                              'type' => 'boolean',
                              'name' => 'facebook_base_pro_product_link'
                            ),

                            array(
                              'label' => $this->language->get('facebook_base_pro_product_brand'),
                              'type' => 'boolean',
                              'name' => 'facebook_base_pro_product_brand'
                            ),

                            array(
                              'label' => $this->language->get('facebook_base_pro_product_condition'),
                              'type' => 'boolean',
                              'name' => 'facebook_base_pro_product_condition'
                            ),

                            array(
                              'label' => $this->language->get('facebook_base_pro_product_image_link'),
                              'type' => 'boolean',
                              'name' => 'facebook_base_pro_product_image_link'
                            ),

                            array(
                              'label' => $this->language->get('facebook_base_pro_product_price'),
                              'type' => 'boolean',
                              'name' => 'facebook_base_pro_product_price'
                            ),

                            array(
                              'label' => $this->language->get('facebook_base_pro_product_sale_price'),
                              'type' => 'boolean',
                              'name' => 'facebook_base_pro_product_sale_price'
                            ),

                            array(
                              'label' => $this->language->get('facebook_base_pro_product_sale_price_customer_group'),
                              'help' => $this->language->get('facebook_base_pro_product_sale_price_customer_group_help'),
                              'type' => 'select',
                              'options' => $this->cgf,
                              'value' => $this->config->get('facebook_base_pro_product_sale_price_customer_group'),
                              'name' => 'facebook_base_pro_product_sale_price_customer_group'
                            ),

                            array(
                              'label' => $this->language->get('facebook_base_pro_product_include_tax'),
                              'type' => 'boolean',
                              'name' => 'facebook_base_pro_product_include_tax'
                            ),

                            array(
                              'label' => $this->language->get('facebook_base_pro_product_quantity'),
                              'type' => 'boolean',
                              'name' => 'facebook_base_pro_product_quantity'
                            ),

                            array(
                              'label' => $this->language->get('facebook_base_pro_product_type'),
                              'type' => 'boolean',
                              'name' => 'facebook_base_pro_product_type'
                            ),

                            array(
                              'label' => $this->language->get('facebook_base_pro_product_weight'),
                              'type' => 'boolean',
                              'name' => 'facebook_base_pro_product_weight'
                            ),

                            array(
                              'label' => $this->language->get('facebook_base_pro_product_availability'),
                              'type' => 'boolean',
                              'name' => 'facebook_base_pro_product_availability'
                            ),
                        /*END*/

                        //Google merchancenter categories
                            array(
                                'text' => '<i class="fa fa-cog"></i>'.$this->language->get('configuration').' - '.$this->language->get('facebook_base_pro_products_google_categories'),
                                'type' => 'legend',
                            ),
                            array(
                                'label' => $this->language->get('facebook_base_pro_country'),
                                'type' => 'select',
                                'class' => 'merchantcenter_country',
                                'value' => $this->config->get('facebook_base_pro_merchantcenter_country'),
                                'name' => 'facebook_base_pro_merchantcenter_country',
                                'options' => $merchant_center_countries
                            ),
                        //END Google merchancenter categories
                    ),
                ),
                $this->language->get('tab_criteo_base') => array(
                    'icon' => '<i class="fa fa-rss"></i>',
                    'fields' => array(
                        array(
                            'text' => '<i class="fa fa-cog"></i>'.$this->language->get('criteo_base_pro_configuration_legend').' - '.$this->language->get('tab_criteo_base'),
                            'type' => 'legend',
                        ),

                        array(
                            'label' => $this->language->get('criteo_base_pro_configuration_select'),
                            'type' => 'select',
                            'value' => $this->config->get('criteo_base_pro_configuration_select'),
                            'name' => 'criteo_base_pro_config_selected',
                            'class' => 'select_merchat_center_configuration',
                            'options' => array()
                        ),

                        array(
                            'type' => 'button',
                            'text' => $this->language->get('criteo_base_pro_configuration_load'),
                            'label' => $this->language->get('criteo_base_pro_configuration_load'),
                            'onclick' => 'feed_load_configuration($(this), \'google_criteo\');',
                            'href' => 'javascript:{}'
                        ),

                        array(
                            'type' => 'button',
                            'text' => $this->language->get('criteo_base_pro_configuration_delete'),
                            'label' => $this->language->get('criteo_base_pro_configuration_delete'),
                            'onclick' => 'feed_delete_configuration($(this), \'google_criteo\');',
                            'href' => 'javascript:{}'
                        ),

                        array(
                            'type' => 'text',
                            'label' => $this->language->get('criteo_base_pro_configuration_name'),
                            'href' => 'javascript:{}',
                            'after' => '<a onclick="feed_save_configuration($(this), \'google_criteo\');" href="javascript:{}" class="button" style="margin-top:10px;">'.$this->language->get('criteo_base_pro_configuration_save').'</a>',
                            'name' => 'criteo_base_pro_config_name',
                        ),

                        array(
                            'type' => 'button',
                            'text' => $this->language->get('criteo_base_pro_configuration_restore'),
                            'label' => $this->language->get('criteo_base_pro_configuration_restore'),
                            'help' => $this->language->get('criteo_base_pro_configuration_restore_help'),
                            'onclick' => 'feed_restore_configuration($(this), \'google_criteo\');',
                            'href' => 'javascript:{}'
                        ),

                        array(
                            'text' => '<i class="fa fa-file-code-o" aria-hidden="true"></i>'.$this->language->get('criteo_base_pro_feed_urls'),
                            'type' => 'legend',
                        ),

                        array(
                            'type' => 'html_code',
                            'html_code' => '<div class="google_criteo_base_pro_feed_urls"></div>',
                        ),

                        array(
                            'text' => '<i class="fa fa-cog"></i>'.$this->language->get('configuration'),
                            'type' => 'legend',
                        ),

                        array(
                            'label' => $this->language->get('status'),
                            'type' => 'boolean',
                            'name' => 'criteo_base_pro_status'
                        ),

                        array(
                            'label' => $this->language->get('criteo_base_pro_title'),
                            'type' => 'text',
                            'name' => 'criteo_base_pro_title'
                        ),

                        array(
                            'label' => $this->language->get('criteo_base_pro_description'),
                            'type' => 'text',
                            'name' => 'criteo_base_pro_description'
                        ),

                        array(
                            'label' => $this->language->get('criteo_base_pro_link'),
                            'type' => 'text',
                            'name' => 'criteo_base_pro_link'
                        ),

                        array(
                            'label' => $this->language->get('criteo_base_pro_ignore_products'),
                            'help' => $this->language->get('criteo_base_pro_ignore_products_help'),
                            'type' => 'textarea',
                            'style' => 'height:100px;',
                            'name' => 'criteo_base_pro_ignore_products'
                        ),

                        array(
                            'label' => $this->language->get('only_these_products'),
                            'help' => $this->language->get('only_these_products_help'),
                            'type' => 'products_autocomplete',
                            'value' => '',
                            'name' => 'criteo_base_pro_only_these_products'
                        ),

                        array(
                            'label' => $this->language->get('criteo_base_pro_show_out_stock'),
                            'type' => 'boolean',
                            'name' => 'criteo_base_pro_show_out_stock'
                        ),

                        array(
                            'label' => $this->language->get('criteo_base_pro_product_include_tax'),
                            'type' => 'boolean',
                            'value' => $this->config->get('criteo_base_pro_product_include_tax'),
                            'name' => 'criteo_base_pro_product_include_tax'
                        ),

                        /*Product attrubutes feed*/
                            array(
                              'text' => '<i class="fa fa-cog"></i>'.$this->language->get('configuration').' - '.$this->language->get('criteo_base_pro_required'),
                              'type' => 'legend',
                            ),

                            array(
                                'label' => $this->language->get('criteo_base_pro_product_adult'),
                                'type' => 'select',
                                'options' => array(
                                    'no' => $this->language->get('criteo_base_pro_product_adult_no'),
                                    'yes' => $this->language->get('criteo_base_pro_product_adult_yes'),
                                ),
                                'name' => 'criteo_base_pro_product_adult'
                            ),

                            array(
                                'label' => $this->language->get('criteo_base_pro_product_out_of_stock'),
                                'type' => 'select',
                                'options' => array(
                                    'out_of_stock' => $this->language->get('criteo_base_pro_product_out_of_stock_out_of_stock'),
                                    'preorder' => $this->language->get('criteo_base_pro_product_out_of_stock_preorder'),
                                ),
                                'name' => 'criteo_base_pro_product_out_of_stock'
                            ),
                        /*END*/
                        //Google merchancenter categories
                            array(
                                'text' => '<i class="fa fa-cog"></i>'.$this->language->get('configuration').' - '.$this->language->get('criteo_base_pro_products_google_categories'),
                                'type' => 'legend',
                            ),
                            array(
                                'label' => $this->language->get('criteo_base_pro_country'),
                                'type' => 'select',
                                'class' => 'merchantcenter_country',
                                'value' => $this->config->get('criteo_base_pro_merchantcenter_country'),
                                'name' => 'criteo_base_pro_merchantcenter_country',
                                'options' => $merchant_center_countries
                            ),
                        //END Google merchancenter categories
                    ),
                ),
                $this->language->get('tab_twenga_base') => array(
                    'icon' => '<i class="fa fa-rss"></i>',
                    'fields' => array(
                        array(
                            'text' => '<i class="fa fa-cog"></i>'.$this->language->get('twenga_base_pro_configuration_legend').' - '.$this->language->get('tab_twenga_base'),
                            'type' => 'legend',
                        ),

                        array(
                            'label' => $this->language->get('twenga_base_pro_configuration_select'),
                            'type' => 'select',
                            'value' => $this->config->get('twenga_base_pro_configuration_select'),
                            'name' => 'twenga_base_pro_config_selected',
                            'class' => 'select_merchat_center_configuration',
                            'options' => array()
                        ),

                        array(
                            'type' => 'button',
                            'text' => $this->language->get('twenga_base_pro_configuration_load'),
                            'label' => $this->language->get('twenga_base_pro_configuration_load'),
                            'onclick' => 'feed_load_configuration($(this), \'google_twenga\');',
                            'href' => 'javascript:{}'
                        ),

                        array(
                            'type' => 'button',
                            'text' => $this->language->get('twenga_base_pro_configuration_delete'),
                            'label' => $this->language->get('twenga_base_pro_configuration_delete'),
                            'onclick' => 'feed_delete_configuration($(this), \'google_twenga\');',
                            'href' => 'javascript:{}'
                        ),

                        array(
                            'type' => 'text',
                            'label' => $this->language->get('twenga_base_pro_configuration_name'),
                            'href' => 'javascript:{}',
                            'after' => '<a onclick="feed_save_configuration($(this), \'google_twenga\');" href="javascript:{}" class="button" style="margin-top:10px;">'.$this->language->get('twenga_base_pro_configuration_save').'</a>',
                            'name' => 'twenga_base_pro_config_name',
                        ),

                        array(
                            'type' => 'button',
                            'text' => $this->language->get('twenga_base_pro_configuration_restore'),
                            'label' => $this->language->get('twenga_base_pro_configuration_restore'),
                            'help' => $this->language->get('twenga_base_pro_configuration_restore_help'),
                            'onclick' => 'feed_restore_configuration($(this), \'google_twenga\');',
                            'href' => 'javascript:{}'
                        ),

                        array(
                            'text' => '<i class="fa fa-file-code-o" aria-hidden="true"></i>'.$this->language->get('twenga_base_pro_feed_urls'),
                            'type' => 'legend',
                        ),

                        array(
                            'type' => 'html_code',
                            'html_code' => '<div class="google_twenga_base_pro_feed_urls"></div>',
                        ),

                        array(
                            'text' => '<i class="fa fa-cog"></i>'.$this->language->get('configuration'),
                            'type' => 'legend',
                        ),

                        array(
                            'label' => $this->language->get('status'),
                            'type' => 'boolean',
                            'name' => 'twenga_base_pro_status'
                        ),

                        array(
                            'label' => $this->language->get('twenga_base_pro_thumbs_width'),
                            'help' => $this->language->get('twenga_base_pro_thumbs_width_help'),
                            'type' => 'text',
                            'name' => 'twenga_base_pro_thumbs_width',
                        ),

                        array(
                            'label' => $this->language->get('twenga_base_pro_thumbs_height'),
                            'help' => $this->language->get('twenga_base_pro_thumbs_height_help'),
                            'type' => 'text',
                            'name' => 'twenga_base_pro_thumbs_height',
                        ),

                        array(
                            'label' => $this->language->get('twenga_base_pro_ignore_products'),
                            'help' => $this->language->get('twenga_base_pro_ignore_products_help'),
                            'type' => 'textarea',
                            'style' => 'height:100px;',
                            'name' => 'twenga_base_pro_ignore_products'
                        ),

                        array(
                            'label' => $this->language->get('only_these_products'),
                            'help' => $this->language->get('only_these_products_help'),
                            'type' => 'products_autocomplete',
                            'value' => '',
                            'name' => 'twenga_base_pro_only_these_products'
                        ),

                        array(
                            'label' => $this->language->get('twenga_base_pro_product_stock_margin'),
                            'help' => $this->language->get('twenga_base_pro_product_stock_margin_help'),
                            'type' => 'text',
                            'name' => 'twenga_base_pro_product_stock_margin',
                        ),

                        array(
                            'label' => $this->language->get('twenga_base_pro_option_split'),
                            'help' => $this->language->get('twenga_base_pro_option_split_help'),
                            'type' => 'boolean',
                            'value' => $this->config->get('twenga_base_pro_option_split'),
                            'name' => 'twenga_base_pro_option_split'
                        ),

                        array(
                            'label' => $this->language->get('twenga_base_pro_show_out_stock'),
                            'type' => 'boolean',
                            'name' => 'twenga_base_pro_show_out_stock'
                        ),

                        /*Product attrubutes feed*/
                            array(
                              'text' => '<i class="fa fa-cog"></i>'.$this->language->get('configuration').' - '.$this->language->get('twenga_base_pro_required'),
                              'type' => 'legend',
                            ),

                            array(
                                'label' => $this->language->get('twenga_base_pro_product_shipping'),
                                'help' => $this->language->get('twenga_base_pro_product_shipping_help'),
                                'type' => 'text',
                                'name' => 'twenga_base_pro_product_shipping'
                            ),

                            array(
                                'label' => $this->language->get('twenga_base_pro_product_out_of_stock'),
                                'type' => 'select',
                                'options' => array(
                                    'out_of_stock' => $this->language->get('twenga_base_pro_product_out_of_stock_out_of_stock'),
                                    'preorder' => $this->language->get('twenga_base_pro_product_out_of_stock_restocking'),
                                ),
                                'name' => 'twenga_base_pro_product_out_of_stock'
                            ),

                            array(
                                'label' => $this->language->get('twenga_base_pro_product_stock_detail'),
                                'help' => $this->language->get('twenga_base_pro_product_stock_detail_help'),
                                'type' => 'text',
                                'name' => 'twenga_base_pro_product_stock_detail',
                                'multilanguage' => true
                            ),
                        /*END*/
                    ),
                ),
                $this->language->get('tab_abandoned_cart') => array(
                    'icon' => '<img class="tag_icon" src="'.HTTP_CATALOG.'admin/view/image/gmt/icons/mailchimp.jpg" class="icon_tab">',
                    'fields' => array(
                        array(
                            'label' => $this->language->get('status'),
                            'type' => 'boolean',
                            'value' => $this->config->get('google_ac_status'),
                            'name' => 'google_ac_status'
                        ),

                        array(
                            'label' => $this->language->get('google_ac_api_key'),
                            'type' => 'password',
                            'value' => $this->config->get('google_ac_api_key'),
                            'name' => 'google_ac_api_key'
                        ),

                        array(
                            'label' => $this->language->get('google_ac_list_id'),
                            'type' => 'text',
                            'value' => $this->config->get('google_ac_list_id'),
                            'name' => 'google_ac_list_id'
                        ),
                        array(
                            'label' => $this->language->get('google_ac_input_selector_firstname'),
                            'help' => $this->language->get('google_ac_input_selector_firstname_help'),
                            'type' => 'text',
                            'value' => $this->config->get('google_ac_input_selector_firstname'),
                            'name' => 'google_ac_input_selector_firstname',
                            'default' => 'div#'.(version_compare(VERSION, '2', '>=') ? 'collapse-':'').'payment-address input[name=firstname]'
                        ),
                        array(
                            'label' => $this->language->get('google_ac_input_selector_lastname'),
                            'help' => $this->language->get('google_ac_input_selector_lastname_help'),
                            'type' => 'text',
                            'value' => $this->config->get('google_ac_input_selector_lastname'),
                            'name' => 'google_ac_input_selector_lastname',
                            'default' => 'div#'.(version_compare(VERSION, '2', '>=') ? 'collapse-':'').'payment-address input[name=lastname]'
                        ),
                        array(
                            'label' => $this->language->get('google_ac_input_selector_email'),
                            'help' => $this->language->get('google_ac_input_selector_email_help'),
                            'type' => 'text',
                            'value' => $this->config->get('google_ac_input_selector_email'),
                            'name' => 'google_ac_input_selector_email',
                            'default' => 'div#'.(version_compare(VERSION, '2', '>=') ? 'collapse-':'').'payment-address input[name=email]'
                        ),

                        array(
                          'text' => '<i class="fa fa-cog"></i>'.$this->language->get('google_ac_tutorial_title'),
                          'type' => 'legend',
                        ),

                        array(
                            'type' => 'html_hard',
                            'html_code' => (version_compare(VERSION, '1.5.6.4', '<=') ? '<tr class="field_tr"><td colspan="2">' : '').'<h2 class="faq_title" onclick="toggle_faq($(this))">'.$this->language->get('google_ac_tutorial_step_1').'</h2>',
                        ),

                        array(
                            'type' => 'html_hard',
                            'html_code' => '<div class="faq_description">'.$this->language->get('google_ac_tutorial_step_1_text').'</div>',
                        ),

                        array(
                            'type' => 'html_hard',
                            'html_code' => '<h2 class="faq_title" onclick="toggle_faq($(this))">'.$this->language->get('google_ac_tutorial_step_2').'</h2>',
                        ),

                        array(
                            'type' => 'html_hard',
                            'html_code' => '<div class="faq_description">'.$this->language->get('google_ac_tutorial_step_2_text').'</div>',
                        ),

                        array(
                            'type' => 'html_hard',
                            'html_code' => '<h2 class="faq_title" onclick="toggle_faq($(this))">'.$this->language->get('google_ac_tutorial_step_3').'</h2>',
                        ),

                        array(
                            'type' => 'html_hard',
                            'html_code' => '<div class="faq_description">'.$this->language->get('google_ac_tutorial_step_3_text').'</div>'.(version_compare(VERSION, '1.5.6', '<=') ? '</td></tr>' : ''),
                        ),
                    )
                ),
                $this->language->get('tab_create_mysql_views') => array(
                    'icon' => '<i class="fa fa-database"></i>',
                    'fields' => array(
                        array(
                            'type' => 'html_code',
                            'html_code' => $this->language->get('mysql_views_create_views_help'),
                        ),

                        array(
                            'type' => 'button',
                            'text' => $this->language->get('mysql_views_create_views_button'),
                            'onclick' => 'save_configuration_ajax($(\'form#'.$this->extension_name.'\'), \'create_views\');',
                            'href' => 'javascript:{}'
                        ),

                        array(
                            'type' => 'button',
                            'text' => $this->language->get('mysql_views_delete_views_button'),
                            'onclick' => 'save_configuration_ajax($(\'form#'.$this->extension_name.'\'), \'delete_views\');',
                            'href' => 'javascript:{}'
                        ),
                    )
                )
            )
        );

        if(!version_compare(VERSION, '1.5.4.1', '>'))
        {
            $form_view['tabs'][$this->language->get('tab_merchant_center')] = $this->removeElementWithValue($form_view['tabs'][$this->language->get('tab_merchant_center')], 'label', $this->language->get('google_base_pro_product_color_filter'));
            $form_view['tabs'][$this->language->get('tab_merchant_center')] = $this->removeElementWithValue($form_view['tabs'][$this->language->get('tab_merchant_center')], 'label', $this->language->get('google_base_pro_product_gender_filter'));
            $form_view['tabs'][$this->language->get('tab_merchant_center')] = $this->removeElementWithValue($form_view['tabs'][$this->language->get('tab_merchant_center')], 'label', $this->language->get('google_base_pro_product_age_group_filter'));
            $form_view['tabs'][$this->language->get('tab_merchant_center')] = $this->removeElementWithValue($form_view['tabs'][$this->language->get('tab_merchant_center')], 'label', $this->language->get('google_base_pro_product_size_filter'));
            $form_view['tabs'][$this->language->get('tab_merchant_center')] = $this->removeElementWithValue($form_view['tabs'][$this->language->get('tab_merchant_center')], 'label', $this->language->get('google_base_pro_product_material_filter'));
        }

        //Add categories fields to google merchant center array
            foreach ($categories as $key => $cat) {
                $form_view['tabs'][$this->language->get('tab_merchant_center')]['fields'][] = array(
                    'label' => $cat['name'],
                    'type' => 'text',
                    'placeholder' => $this->language->get('google_base_pro_typing'),
                    'class' => 'merchantcenter_category',
                    'value' => $this->config->get('google_base_pro_merchantcenter_category_'.$cat['category_id']),
                    'name' => 'google_base_pro_merchantcenter_category_'.$cat['category_id'],
                    'onkeyup' => 'autocomplete_google_category($(this));',
                    'after' => '<input type="hidden">',
                );
            }
        //END Add categories fields to google merchant center array

        //Add categories fields to facebook feed xls array
            foreach ($categories as $key => $cat) {
                $form_view['tabs'][$this->language->get('tab_facebook_base')]['fields'][] = array(
                    'label' => $cat['name'],
                    'type' => 'text',
                    'placeholder' => $this->language->get('facebook_base_pro_typing'),
                    'class' => 'merchantcenter_category',
                    'value' => $this->config->get('facebook_base_pro_merchantcenter_category_'.$cat['category_id']),
                    'name' => 'facebook_base_pro_merchantcenter_category_'.$cat['category_id'],
                    'onkeyup' => 'autocomplete_google_category($(this));',
                    'after' => '<input type="hidden">',
                );
            }
        //END Add categories fields to facebook feed xls array

        //Add categories fields to criteo feed xls array
            foreach ($categories as $key => $cat) {
                $form_view['tabs'][$this->language->get('tab_criteo_base')]['fields'][] = array(
                    'label' => $cat['name'],
                    'type' => 'text',
                    'placeholder' => $this->language->get('criteo_base_pro_typing'),
                    'class' => 'merchantcenter_category',
                    'value' => $this->config->get('criteo_base_pro_merchantcenter_category_'.$cat['category_id']),
                    'name' => 'criteo_base_pro_merchantcenter_category_'.$cat['category_id'],
                    'onkeyup' => 'autocomplete_google_category($(this));',
                    'after' => '<input type="hidden">',
                );
            }
        //END Add categories fields to criteo feed xls array

        //Add categories to allow feed his products
            $types_feed = array(
                'tab_merchant_center' => 'google_base_pro',
                'tab_feed_shopping_reviews' => 'reviews_base_pro',
                'tab_google_business_feed' => 'google_business_pro',
                'tab_facebook_base' => 'facebook_base_pro',
                'tab_criteo_base' => 'criteo_base_pro',
                'tab_twenga_base' => 'twenga_base_pro'
            );

            foreach ($types_feed as $tab_name => $var_config_name) {
                $form_view['tabs'][$this->language->get($tab_name)]['fields'][] = array(
                    'text' => '<i class="fa fa-cog"></i>'.$this->language->get($var_config_name.'_categories_legend'),
                    'type' => 'legend',
                );

                $form_view['tabs'][$this->language->get($tab_name)]['fields'][] = array(
                    'label' => $this->language->get($var_config_name.'_select_all'),
                    'type' => 'boolean',
                    'value' => '',
                    'class' => 'select_all_categories',
                    'name' => $var_config_name.'_select_all',
                );

                foreach ($categories as $key => $cat) {
                    $form_view['tabs'][$this->language->get($tab_name)]['fields'][] = array(
                        'label' => $cat['name'],
                        'type' => 'boolean',
                        'name' => $var_config_name.'_category_allowed_'.$cat['category_id'],
                    );
                }
            }
        //END Add categories to allow feed his products

        $form_view = $this->model_extension_devmanextensions_tools->_get_form_values($form_view);

        return $form_view;
    }

    //Devman Extensions - info@devmanextensions.com - 2016-10-09 19:28:44 - Crete views
        public function create_views()
        {
            $array_return = array(
                'error' => false,
                'message' => $this->language->get('mysql_views_create_views_success')
            );

            if ($this->validate()) {
                foreach ($this->stores as $key => $store) {
                    $store_id = $store['store_id'];
                    foreach ($this->languages as $key2 => $language) {
                        $language_id = $language['language_id'];

                        $view_name = DB_PREFIX . "view_products_".$store_id."_".$language_id;

                        $this->db->query("DROP VIEW IF EXISTS ".$view_name);

                        $sql_view = "CREATE VIEW ".$view_name." AS
                            SELECT
                            pp.product_id,
                            pp.quantity,
                            pp.price,
                            pp.model,
                            pp.image,
                            pp.tax_class_id,
                            pp.upc,
                            pp.weight,
                            pp.weight_class_id,
                            ma.name as manufacturer,
                            pd.name,
                            pd.description,
                            pd.meta_description,
                            pd.meta_keyword,
                            ps.price as special,";

                        if(version_compare(VERSION, '1.5.2.1', '>'))
                            $sql_view .= "pp.mpn,pp.ean,";

                        $sql_view .= "GROUP_CONCAT(DISTINCT ptc.category_id ORDER BY ptc.category_id ASC SEPARATOR '~') AS product_categories,
                            GROUP_CONCAT(DISTINCT pta.attribute_id ORDER BY pta.language_id ASC SEPARATOR '~') AS product_attributes_id,
                            GROUP_CONCAT(DISTINCT pta.text ORDER BY pta.language_id ASC SEPARATOR '~') AS product_attributes_texts,
                            GROUP_CONCAT(DISTINCT pf.filter_id ORDER BY pf.filter_id ASC SEPARATOR '~') AS product_filters,
                            GROUP_CONCAT(DISTINCT po.option_id ORDER BY po.option_id ASC SEPARATOR '~') AS product_options,
                            GROUP_CONCAT(DISTINCT pov.option_value_id ORDER BY pov.option_value_id ASC SEPARATOR '~') AS product_option_values,

                            (SELECT
                                GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;')
                                FROM " . DB_PREFIX . "category_path cp
                                LEFT JOIN " . DB_PREFIX . "category c1 ON (cp.category_id = c1.category_id)
                                LEFT JOIN " . DB_PREFIX . "category c2 ON (cp.path_id = c2.category_id)
                                LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id)
                                LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id)
                                WHERE cd1.language_id = ".$language_id."
                                    AND cd2.language_id = ".$language_id."
                                    AND (c1.category_id = ptc.category_id OR c2.category_id = ptc.category_id)
                            ) as category_tree

                            from " . DB_PREFIX . "product pp

                            LEFT JOIN " . DB_PREFIX . "product_description pd ON(pp.product_id = pd.product_id AND pd.language_id = ".$language_id.")
                            LEFT JOIN " . DB_PREFIX . "product_to_category ptc ON(pp.product_id = ptc.product_id)
                            LEFT JOIN " . DB_PREFIX . "product_attribute pta ON(pp.product_id = pta.product_id AND pta.language_id = ".$language_id.")
                            LEFT JOIN " . DB_PREFIX . "product_filter pf ON(pp.product_id = pf.product_id)
                            LEFT JOIN " . DB_PREFIX . "manufacturer ma ON(pp.manufacturer_id = ma.manufacturer_id)
                            LEFT JOIN " . DB_PREFIX . "product_option po ON(pp.product_id = po.product_id)
                            LEFT JOIN " . DB_PREFIX . "product_option_value pov ON(pp.product_id = pov.product_id AND pov.option_id = po.option_id)
                            INNER JOIN " . DB_PREFIX . "product_to_store pte ON (pte.product_id = pp.product_id AND store_id = ".$store_id.")
                            LEFT JOIN " . DB_PREFIX . "product_special ps ON (ps.product_id = pp.product_id AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) AND ps.customer_group_id = 1)
                            WHERE pp.status = 1
                            GROUP BY pp.product_id
                            ORDER BY pp.product_id";
                        $this->db->query($sql_view);
                    }
                }
            }

            echo json_encode($array_return); die;
        }
    //END

    //Devman Extensions - info@devmanextensions.com - 2017-06-27 19:40:09 - Delete views
        public function delete_views()
        {
            $array_return = array(
                'error' => false,
                'message' => $this->language->get('mysql_views_delete_views_success')
            );

            if ($this->validate()) {
                foreach ($this->stores as $key => $store) {
                    $store_id = $store['store_id'];
                    foreach ($this->languages as $key2 => $language) {
                        $language_id = $language['language_id'];

                        $view_name = DB_PREFIX . "view_products_".$store_id."_".$language_id;

                        $this->db->query("DROP VIEW IF EXISTS ".$view_name);
                    }
                }
            }

            echo json_encode($array_return); die;
        }
    //END

    public function _send_custom_variables_to_view()
    {
        $this->data_to_view['jquery_variables'] = array(
            'token' => $this->token,
            'token_name' => $this->token_name,
            'link_ajax_get_form' => htmlspecialchars_decode($this->url->link($this->real_extension_type.'/'.$this->extension_name.'&ajax_function=ajax_get_form', $this->token_name.'=' . $this->session->data[$this->token_name], 'SSL')),
            'link_ajax_open_ticket' => htmlspecialchars_decode($this->url->link($this->real_extension_type.'/'.$this->extension_name.'&ajax_function=ajax_open_ticket', $this->token_name.'=' . $this->session->data[$this->token_name], 'SSL')),
            'text_image_manager' => $this->language->get('text_image_manager'),
            'conversion_error_empty_order_id' => $this->language->get('conversion_error_empty_order_id'),
            'link_save_json_config' => htmlspecialchars_decode($this->url->link($this->real_extension_type.'/'.$this->extension_name.'&ajax_function=ajax_save_json_configuration', $this->token_name.'=' . $this->session->data[$this->token_name], 'SSL')),
            'link_restore_json_config' => htmlspecialchars_decode($this->url->link($this->real_extension_type.'/'.$this->extension_name.'&ajax_function=ajax_restore_json_configuration', $this->token_name.'=' . $this->session->data[$this->token_name], 'SSL')),
            'link_delete_json_config' => htmlspecialchars_decode($this->url->link($this->real_extension_type.'/'.$this->extension_name.'&ajax_function=ajax_delete_json_configuration', $this->token_name.'=' . $this->session->data[$this->token_name], 'SSL')),
            'link_load_json_config' => htmlspecialchars_decode($this->url->link($this->real_extension_type.'/'.$this->extension_name.'&ajax_function=ajax_load_json_configuration', $this->token_name.'=' . $this->session->data[$this->token_name], 'SSL')),
            'link_get_select_json_config' => htmlspecialchars_decode($this->url->link($this->real_extension_type.'/'.$this->extension_name.'&ajax_function=ajax_get_select_json_config', $this->token_name.'=' . $this->session->data[$this->token_name], 'SSL')),
            'link_ajax_get_feed_urls' => htmlspecialchars_decode($this->url->link($this->real_extension_type.'/'.$this->extension_name.'&ajax_function=ajax_get_feed_urls', $this->token_name.'=' . $this->session->data[$this->token_name], 'SSL')),
            'link_ajax_generate_conversion' => htmlspecialchars_decode($this->url->link($this->real_extension_type.'/'.$this->extension_name.'&ajax_function=ajax_generate_conversion', $this->token_name.'=' . $this->session->data[$this->token_name], 'SSL')),
            'link_get_workspace' => $this->api_url.'gmt/get_workspace',
        );

        $this->data_to_view['stores'] = $this->stores;
        $this->data_to_view['text_none'] = $this->language->get('text_none');
        $this->data_to_view['custom_conversion_reserved_values'] = $this->reserved_values;


    }

    public function autocomplete_information() {
        $json = array();

        if (isset($this->request->get['filter_name'])) {
            $this->load->model('catalog/information');

            $filter_data = array(
                'filter_name' => $this->request->get['filter_name'],
                'start'       => 0,
                'limit'       => 5
                );

            $results = $this->model_catalog_information->getInformations($filter_data);

            foreach ($results as $key => $info) {
                if (strpos(strtoupper($info['title']), strtoupper($this->request->get['filter_name'])) !== FALSE)
                    {}
                else
                    unset($results[$key]);
            }
            foreach ($results as $result) {
                $json[] = array(
                    'information_id' => $result['information_id'],
                    'name'            => strip_tags(html_entity_decode($result['title'], ENT_QUOTES, 'UTF-8'))
                );
            }
        }

        $sort_order = array();

        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['name'];
        }

        array_multisort($sort_order, SORT_ASC, $json);

        echo json_encode($json); die;
    }
    public function autocomplete()
    {
        $country = $this->request->get['country_id'];
        $text = $this->request->get['text'];
        //Google categories
            $google_categories = explode("\n", file_get_contents(DIR_CATALOG.'controller/extension/feed/google_all_txt_merchantcenter_categories/taxonomy.'.$country.'.txt'));
            if($google_categories[0][0] == "#" || strpos($google_categories[0], 'Google_Product_Taxonomy_Version') !== false)
                unset($google_categories[0]);
        //END Google categories
        $result = array();
        foreach($google_categories as $key => $value) {
            /*if(!in_array($country, array('pl-PL', 'ru-RU')))
                $value = utf8_decode($value);
            else
                $value = utf8_encode($value);*/

            if (!empty($text) && !empty($value) && strpos(strtoupper($value), strtoupper($text)) !== false) {
              $result[] = array('id' => $key, 'name' => $value);
          }
      }
      echo json_encode($result); die;
    }

    function removeElementWithValue($array, $key, $value){
        foreach($array as $subKey => $subArray){
            if(isset($subArray[$key]) && $subArray[$key] == $value)
                unset($array[$subKey]);
        }
        return $array;
    }

    public function ajax_generate_conversion() {
        $array_return = array(
            'error' => false,
            'message' => ''
        );

        $this->request->post['no_exit'] = true;
        $this->validate();

        $this->load->model('sale/order');
        $order_data = $this->get_order_data($this->request->post['order_id']);
        $negative = $this->request->post['negative'];

        if(empty($order_data)) {
            $array_return['error'] = true;
            $array_return['message'] = $this->language->get('conversion_error_empty_order');
        } else {
            $script = array(
                'begin_head' => '<script>var dataLayer = [];dataLayer.push({"current_currency":"'.$order_data['currency_code'].'"})</script>'.$this->generate_gtm_conversion_code($order_data, $negative).$this->generate_gtm_head_code($this->request->post['gtm_id']),
                'begin_body' => $this->generate_gtm_body_code($this->request->post['gtm_id']),
            );
            $array_return['script'] = $script;
            $array_return['message'] = $this->language->get($negative ? 'negative_conversion_success' : 'positive_conversion_success');
        }

        echo json_encode($array_return); die;
    }

    public function generate_gtm_head_code($gtm) {
        return "<!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','$gtm');</script>
        <!-- End Google Tag Manager -->";
    }

    public function generate_gtm_body_code($gtm) {
        return "<!-- Google Tag Manager (noscript) -->
        <noscript><iframe src=\"https://www.googletagmanager.com/ns.html?id=$gtm\"
        height=\"0\" width=\"0\" style=\"display:none;visibility:hidden\"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->";
    }

    public function get_order_data($order_id) {
        $this->load->model('sale/order');
        $order_data = $this->model_sale_order->getOrder(trim($order_id));

        if(empty($order_data))
            return '';

        $order_totals = $this->model_sale_order->getOrderTotals($order_id);
        $order_products = $this->model_sale_order->getOrderProducts($order_id);
        $order_data['totals'] = $order_totals;
        $order_data['products'] = $order_products;

        return $order_data;
    }

    public function _get_product_manufacturer($product_id) {
        $sql = "SELECT ma.name FROM " . DB_PREFIX . "product pr LEFT JOIN " . DB_PREFIX . "manufacturer ma ON (pr.manufacturer_id = ma.manufacturer_id) WHERE pr.product_id = ".$product_id;
        $results = $this->db->query($sql);

        return !empty($results->row['name']) ? $results->row['name'] : '';
    }

    public function _get_product_category_name($product_id) {
        $this->load->model('catalog/product');
        $product_categories = $this->model_catalog_product->getProductCategories($product_id);
        $this->load->model('catalog/category');

        if(!empty($product_categories[0]['category_id']))
            $category_info = $this->model_catalog_category->getCategory($product_categories[0]['category_id']);

        return !empty($category_info['name']) ? str_replace("'", "\'", $category_info['name']) : '';
    }
    public function _get_product_variant_order_success($options){
        foreach ($options as $key => $opt) {
            return $opt['name'].(!empty($opt['value']) ? ': '.$opt['value'] : '');
        }
    }
    public function generate_gtm_conversion_code($order_data, $negative = false) {
        //Format totals
            $shipping = 0;
            $tax = 0;
            $subtotal = 0;
            $coupon = '';
            foreach ($order_data['totals'] as $key => $ord) {
                if ($ord['code'] == 'sub_total')
                    $subtotal += $ord['value'];
                elseif ($ord['code'] == 'shipping')
                    $shipping += $ord['value'];
                elseif ($ord['code'] == 'tax')
                    $tax += $ord['value'];
                elseif ($ord['code'] == 'coupon')
                    $coupon = $ord['title'];
            }
            $order_data['shipping'] = $this->format_price($shipping);
            $order_data['tax'] = $this->format_price($tax);
            $order_data['subtotal'] = $this->format_price($subtotal);
            $order_data['coupon'] = $coupon;
            $order_data['total'] = $this->format_price($order_data['total']);
        //END

        //Format products
            $final_products = array();
            foreach ($order_data['products'] as $key => $prod) {
                $manufacturer = $this->_get_product_manufacturer($prod['product_id']);
                $category_name = $this->_get_product_category_name($prod['product_id']);
                $variant = '';

                if (!empty($prod['option']))
                    $variant = $this->_get_product_variant_order_success($prod['option']);

                $final_products[] = array(
                    'id' => $prod['product_id'],
                    'name' => $prod['name'],
                    'price' => $this->format_price($prod['price']),
                    'brand' => $manufacturer,
                    'category' => $category_name,
                    'variant' => $variant,
                    'quantity' => $prod['quantity']
                );
            }
            $order_data['products_ee'] = $final_products;
        //END

        //Devman Extensions - info@devmanextensions.com - 26/12/17 12:56 - Add "date_added" to google reviews
            $order_data['date_added'] = date('Y-m-d');

        if($negative) {
            $order_data['total'] *= -1;
            foreach ($order_data['totals'] as $key => $ot) {
                $order_data['totals'][$key]['value'] = $ot['value'] * -1;
            }
            foreach ($order_data['products'] as $key => $prod) {
                $order_data['products'][$key]['price'] = $prod['price'] * -1;
                $order_data['products'][$key]['total'] = $prod['total'] * -1;
                $order_data['products'][$key]['tax'] = $prod['tax'] * -1;
            }
            foreach ($order_data['products_ee'] as $key => $prod) {
                $order_data['products_ee'][$key]['price'] = $prod['price'] * -1;
            }
            $order_data['tax'] = $order_data['tax'] > 0 ? ($order_data['tax']*-1) : 0;
            $order_data['shipping'] = $order_data['shipping'] > 0 ? ($order_data['shipping']*-1) : 0;
        }

        //Add Enhanced Ecommerce object to datalayer
            $script = '<script type="text/javascript">';
                $script .= "dataLayer.push({";
                    $script .= '"ecommerce" : {';
                        $script .= '"currencyCode": "' . $order_data['currency_code'] . '",';
                        $script .= '"purchase": {';
                            $script .= '"actionField": ';
                            $order_data_json = array(
                                'id' => $order_data['order_id'],
                                'affiliation' => $order_data['store_name'],
                                'revenue' => $this->format_price($order_data['total']),
                                'tax' => $this->format_price($order_data['tax']),
                                'shipping' => $this->format_price($order_data['shipping']),
                                'coupon' => $order_data['coupon'],
                            );
                            $script .= json_encode($order_data_json) . ',';
                            $script .= '"products" : ' . json_encode($order_data['products_ee']);
                        $script .= "}";
                    $script .= "}";
                 $script .= "});";
            $script .= "</script>";

        //Add order data to datalayer, will can use it for extenal tags
            $script .= '<script type="text/javascript">';
                $script .= "dataLayer.push({";
                    $script .= '"event": "orderSuccess",';
                    $script .= '"orderSuccess": ' . json_encode($order_data);
                $script .= "});";
            $script .= "</script>";

        return $script;
    }

    public function format_price($price, $currency_code, $thousands = false)
    {
        $price = $this->currency->format($price, $currency_code, '', false);

        $decimal_separator = !$thousands ? '.' : ',';
        $thousands_separator = !$thousands ? '' : '.';
        return number_format((float)$price, 2, $decimal_separator, $thousands_separator);
    }

    public function ajax_save_json_configuration()
    {
        $type = array_key_exists('type', $this->request->post) ? $this->request->post['type'] : die('No param POST "type" found.');

        $this->request->post['no_exit'] = true;
        $this->validate();

        $array_return = array(
            'error' => false,
            'message' => ''
        );

        //Devman Extensions - info@devmanextensions.com - 2017-09-18 11:01:12 - Get store id
            foreach ($this->request->post as $key => $value) {
                $store_id = filter_var($key, FILTER_SANITIZE_NUMBER_INT);

                if(is_numeric($store_id))
                    break;
            }
        //END

        //Devman Extensions - info@devmanextensions.com - 2017-09-18 11:04:14 - Get setting name
            $config_name = array_key_exists($type.'_base_pro_config_name_'.$store_id, $this->request->post) ? $this->request->post[$type.'_base_pro_config_name_'.$store_id] : '';
        //END

        if(empty($config_name))
        {
            $array_return['error'] = true;
            $array_return['message'] = $this->language->get($type.'_base_pro_configuration_name_error');
            echo json_encode($array_return); die;
        }

        $config_name = urlencode($config_name);

        //Devman Extensions - info@devmanextensions.com - 2017-09-18 11:25:41 - Format configuration
            $datas_to_save = array('config_name','status','title','description','link','thumb_width','thumb_height','ignore','show_out_stock','product_id','multiples_identificators','identifier_exists','product_title','product_link','product_description','product_brand','product_condition','product_image_link','product_additional_images','product_price','product_sale_price','product_include_tax','product_custom_label','product_type','product_quantity','product_weight','product_availability','product_color','product_color_split','product_color_attribute','product_color_filter','product_color_option','product_gender','product_gender_attribute','product_gender_filter','product_gender_option','product_age_group','product_age_group_attribute','product_age_group_filter','product_age_group_option','product_size','product_size_attribute','product_size_filter','product_size_option','product_material','product_material_attribute','product_material_filter','product_material_option','merchantcenter_country','merchantcenter_category','category_allowed','product_adult','product_out_of_stock', 'thumbs_width', 'thumbs_height', 'product_shipping', 'product_stock_detail', 'option_split', 'product_stock_margin', 'base_pro_product_keywords', 'base_pro_product_category', 'ignore_product', 'only_these_products');

            $final_configuration = array();

            foreach ($this->request->post as $key => $value) {
                $save = false;

                foreach ($datas_to_save as $key_to_save) {
                    if (strpos($key, $key_to_save) !== false) {
                        $save = true;
                        break;
                    }
                }

                if($save)
                    $final_configuration[$key] = $value;
            }
        //END

        $prev_configuration = $this->ajax_get_json_config($type);

        //Devman Extensions - info@devmanextensions.com - 2017-09-18 11:26:39 - If config exist, update old configuration
            $prev_configuration['store_'.$store_id][$config_name] = $final_configuration;
        //END

        $fp = fopen($this->{$type.'_base_pro_configurations_path'}, 'w');
        fwrite($fp, json_encode($prev_configuration));
        fclose($fp);

        //Devman Extensions - info@devmanextensions.com - 2017-10-06 09:59:15 - Save backup
            $fp = fopen($this->{$type.'_base_pro_configurations_path_backup'}, 'w');
            fwrite($fp, json_encode($prev_configuration));
            fclose($fp);
        //END

        $array_return['message'] = sprintf($this->language->get($type.'_base_pro_configuration_save_successfully'), $config_name);
        echo json_encode($array_return); die;
    }

    public function ajax_restore_json_configuration()
    {
        $this->request->post['no_exit'] = true;
        $this->validate();

        $type = array_key_exists('type', $this->request->post) ? $this->request->post['type'] : die('No param POST "type" found.');

        $array_return = array(
            'error' => false,
            'message' => $this->language->get($type.'_base_pro_configuration_restore_backup_success')
        );

        if(!file_exists($this->{$type.'_base_pro_configurations_path_backup'}))
        {
            $array_return = array(
                'error' => true,
                'message' => $this->language->get($type.'_base_pro_configuration_restore_backup_not_found')
            );
            echo json_encode($array_return); die;
        }

        if(!copy($this->{$type.'_base_pro_configurations_path_backup'}, $this->{$type.'_base_pro_configurations_path'}))
        {
            $array_return = array(
                'error' => true,
                'message' => $this->language->get($type.'_base_pro_configuration_restore_backup_error_copy')
            );
            echo json_encode($array_return); die;
        }


        echo json_encode($array_return); die;
    }

    public function ajax_delete_json_configuration()
    {
        $type = array_key_exists('type', $this->request->post) ? $this->request->post['type'] : die('No param POST "type" found.');

        $this->request->post['no_exit'] = true;
        $this->validate();

        $array_return = array(
            'error' => false,
            'message' => ''
        );

        //Devman Extensions - info@devmanextensions.com - 2017-09-18 11:04:14 - Get setting name
            $store_id = array_key_exists('store_id', $this->request->post) ? $this->request->post['store_id'] : '';
            $configuration_name = array_key_exists('configuration_name', $this->request->post) ? $this->request->post['configuration_name'] : '';

            if(empty($configuration_name))
            {
                $array_return['error'] = true;
                $array_return['message'] = $this->language->get($type.'_base_pro_configuration_not_found');
                echo json_encode($array_return); die;
            }
        //END

        $prev_configuration = $this->ajax_get_json_config($type);

        //Devman Extensions - info@devmanextensions.com - 2017-09-18 11:26:39 - If config exist, update old configuration
            unset($prev_configuration['store_'.$store_id][$configuration_name]);
        //END

        $fp = fopen($this->{$type.'_base_pro_configurations_path'}, 'w');
        fwrite($fp, json_encode($prev_configuration));
        fclose($fp);

        $array_return['message'] = sprintf($this->language->get($type.'_base_pro_configuration_deleted_successfully'), $configuration_name);
        echo json_encode($array_return); die;
    }

    public function ajax_load_json_configuration()
    {
        $type = array_key_exists('type', $this->request->post) ? $this->request->post['type'] : die('No param POST "type" found.');

        $array_return = array(
            'error' => false,
            'message' => ''
        );

        $store_id = array_key_exists('store_id', $this->request->post) ? $this->request->post['store_id'] : '';
        $configuration_name = array_key_exists('configuration_name', $this->request->post) ? $this->request->post['configuration_name'] : '';

        $prev_configuration = $this->ajax_get_json_config($type);

        if(empty($configuration_name) || !array_key_exists('store_'.$store_id, $prev_configuration) || !array_key_exists($configuration_name, $prev_configuration['store_'.$store_id]))
        {
            $array_return['error'] = true;
            $array_return['message'] = $this->language->get($type.'_base_pro_configuration_not_found');
            echo json_encode($array_return); die;
        }

        if(!empty($prev_configuration['store_'.$store_id][$configuration_name]))
        {
            foreach ($prev_configuration['store_'.$store_id][$configuration_name] as $key => $value) {
                $prev_configuration['store_'.$store_id][$configuration_name][$key] = htmlspecialchars_decode(html_entity_decode($value));
            }
        }

        echo json_encode($prev_configuration['store_'.$store_id][$configuration_name]); die;
    }

    public function ajax_get_select_json_config()
    {
        $type = array_key_exists('type', $this->request->post) ? $this->request->post['type'] : die('No param POST "type" found.');

        $configuration = $this->ajax_get_json_config($type);

        $select_configurations_merchant_center = array();
        if(!empty($configuration))
        {
            foreach ($configuration as $store_name => $config) {
                $store_id = filter_var($store_name, FILTER_SANITIZE_NUMBER_INT);
                $select_configurations_merchant_center[$store_id] = array();
                foreach ($config as $config_name => $value) {
                    $select_configurations_merchant_center[$store_id][] = $config_name;
                }
            }
        }

        echo json_encode($select_configurations_merchant_center); die;
    }

    public function ajax_get_feed_urls()
    {
        $type = array_key_exists('type', $this->request->post) ? $this->request->post['type'] : die('No param POST "type" found.');
        $array_return = array(
            'error' => false,
            'message' => ''
        );

        $type = array_key_exists('type', $this->request->post) ? $this->request->post['type'] : die('No param POST "type" found.');

        if(empty($type))
        {
            $array_return['error'] = true;
            $array_return['message'] = $this->language->get($type.'_base_pro_configuration_type_error');
            echo json_encode($array_return); die;
        }

        $json_configuration = $this->ajax_get_json_config($type);

        $html = '';

        if(!empty($json_configuration))
        {
            if($type == 'google_facebook')
                $type = 'facebook';
            if($type == 'google_criteo')
                $type = 'criteo';
            if($type == 'google_twenga')
                $type = 'twenga';

            foreach ($this->stores as $key => $store) {
                $html .= '<div class="col-md-4"><b><i class="fa fa-shopping-cart"></i>'.$store['name'].'</b>';

                if(array_key_exists('store_'.$store['store_id'], $json_configuration))
                {
                    $html .= '<ul>';
                        foreach ($json_configuration['store_'.$store['store_id']] as $configuration_name => $value) {
                            $html .= '<li><b>'.$configuration_name.'</b>';
                            $html .= '<ul>';
                                foreach ($this->languages as $key2 => $lang) {
                                    if(in_array($type, array('google_reviews'))) {
                                        $url_temp = $store['url'] . 'index.php?route=extension/feed/' . $type . '_base_pro&configuration=' . $configuration_name . '&language_id=' . $lang['language_id'];
                                                $html .= '<li><b>' . $lang['name'] . ':</b> <a target="_blank" href="' . $url_temp . '">Feed url</a></li>';
                                    } else {
                                        foreach ($this->currencies as $key3 => $cur) {
                                            if ($cur['status']) {
                                                $url_temp = $store['url'] . 'index.php?route=extension/feed/' . $type . '_base_pro&configuration=' . $configuration_name . '&language_id=' . $lang['language_id'] . '&currency_code=' . $cur['code'];
                                                $html .= '<li><b>' . $lang['name'] . ' - ' . $cur['title'] . ':</b> <a target="_blank" href="' . $url_temp . '">Feed url</a></li>';
                                            }
                                        }
                                    }
                                }
                            $html .= '</ul></li>';
                        }
                    $html .= '</ul></div>';
                }
            }
        }

        $array_return['html'] = $html;
        echo json_encode($array_return); die;
    }

    public function ajax_get_json_config($type = '')
    {
        if(empty($type))
            $type = array_key_exists('type', $this->request->post) ? $this->request->post['type'] : die('No param POST "type" found.');

        $str = file_get_contents($this->{$type.'_base_pro_configurations_path'});
        $prev_configuration = json_decode($str, true);
        return $prev_configuration;
    }

    public function ajax_get_form($license_id = '')
    {
        $this->model_extension_devmanextensions_tools->ajax_get_form($license_id);
    }

    public function ajax_open_ticket()
    {
        $data = $this->request->post;
        $data['domain'] = HTTP_CATALOG;
        $data['license_id'] = $this->config->get($this->extension_group_config.'_license_id');
        $result = $this->model_extension_devmanextensions_tools->curl_call($data, $this->api_url.'opencart/ajax_open_ticket');

        //from API are in json_encode
        echo $result; die;
    }

    public function _test_before_save()
    {
        //Devman Extensions - info@devmanextensions.com - 2017-11-22 09:58:18 - Create table to abandone carts
            $table_name = DB_PREFIX."gmt_abandoned_carts";

            $exist_table = $this->db->query("SELECT * FROM information_schema.tables WHERE table_schema = '".DB_DATABASE."' AND table_name = '".$table_name."' LIMIT 1;");

            if($exist_table->num_rows == 0)
                $this->db->query("CREATE TABLE IF NOT EXISTS `".$table_name."` (
                                      `id` varchar(36) NOT NULL,
                                      `customer_id` int(11) NOT NULL DEFAULT '0',
                                      `ip` varchar(50) NOT NULL,
                                      `session_cart` text NOT NULL,
                                      `email` varchar(200) NOT NULL,
                                      `firstname` varchar(200) NOT NULL,
                                      `lastname` varchar(200) NOT NULL,
                                      `created` datetime NOT NULL,
                                      PRIMARY KEY (`id`)
                                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        //END

        return false;
    }
}
?>