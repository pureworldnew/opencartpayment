<?php

class ControllerModuleAdminMonitor extends Controller {
    private $error = array();

    public function __construct($registry) {
        parent::__construct($registry);
        $this->load->config('isenselabs/adminmonitor');
    }

    public function index() {
        $this->document->addScript('view/javascript/adminmonitor/adminmonitor.js');
        $this->document->addStyle('view/stylesheet/adminmonitor/adminmonitor.css');

        $this->load->model($this->config->get('adminmonitor_module_path'));

        $this->language->load($this->config->get('adminmonitor_module_path'));

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            if (!empty($this->request->post['OaXRyb1BhY2sgLSBDb21'])) {
                $this->request->post['adminmonitor']['LicensedOn'] = $this->request->post['OaXRyb1BhY2sgLSBDb21'];
            }
                        
            if (!empty($this->request->post['cHRpbWl6YXRpb24ef4fe'])) {
                $this->request->post['adminmonitor']['License'] = json_decode(base64_decode($this->request->post['cHRpbWl6YXRpb24ef4fe']), true);
            }

            $this->model_setting_setting->editSetting('adminmonitor', $this->request->post);
            
            $this->session->data['success'] = $this->language->get('adminmonitor_text_success');

            $this->response->redirect($this->url->link($this->config->get('adminmonitor_module_path'), 'token=' . $this->session->data['token'], 'SSL'));
        }

        $language_entries = array(
            'button_save',
            'button_cancel',
            'button_rehook',
            'button_clear_filters',
            'button_delete_selected',
            'heading_title',
            'heading_title_dashboard',
            'license_your_license',
            'license_enter_code',
            'license_placeholder',
            'license_activate',
            'license_get_code',
            'license_holder',
            'license_registered_domains',
            'license_expires',
            'license_valid',
            'license_manage',
            'license_get_support',
            'license_community',
            'license_community_info',
            'license_forums',
            'license_tickets',
            'license_tickets_info',
            'license_tickets_open',
            'license_presale',
            'license_presale_info',
            'license_presale_bump',
            'license_missing',
            'table_user',
            'table_message',
            'table_event_type',
            'table_event_group',
            'table_date_created',
            'placeholder_start',
            'placeholder_end',
            'adminmonitor_text_to',
            'adminmonitor_text_confirm',
            'adminmonitor_text_loading'
        );

        foreach ($language_entries as $language_entry) {
            $data[$language_entry] = $this->language->get($language_entry);
        }

        $data['tabs'] = $this->getTabs();
        $data['module_path'] = $this->config->get('adminmonitor_module_path');

        if (isset($this->session->data['adminmonitor_error'])) {
            $data['alert_error'] = $this->session->data['adminmonitor_error'];
            unset($this->session->data['adminmonitor_error']);
        } else if (isset($this->error['warning'])) {
            $data['alert_error'] = $this->error['warning'];
        } else {
            $data['alert_error'] = '';
        }

        if (!empty($this->session->data['success'])) {
            $data['alert_success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['alert_success'] = '';
        }

        $data['filter_users'] = array();
        $this->load->model('user/user');
        $users = $this->model_user_user->getUsers();
        foreach ($users as $user) {
            $data['filter_users'][] = array(
                'user_id' => $user['user_id'],
                'user_name' => $user['firstname'] . ' ' . $user['lastname'] . ' (' . $user['username'] . ')'
            );
        }

        $data['filter_types'] = array();
        $types = $this->{$this->config->get('adminmonitor_model_key')}->getEventTypes(true);
        foreach ($types as $type) {
            $data['filter_types'][] = array(
                'type_id' => $type,
                'type_name' => $this->language->get('event_type_' . $type)
            );
        }
        uasort($data['filter_types'], array($this->{$this->config->get('adminmonitor_model_key')}, 'sortTypeNameAlpha'));

        $data['filter_groups'] = array();
        $groups = $this->{$this->config->get('adminmonitor_model_key')}->getEventGroups(true);
        foreach ($groups as $group) {
            $data['filter_groups'][] = array(
                'group_id' => $group['group'],
                'group_name' => $this->language->get('event_group_' . $group['group'])
            );
        }
        uasort($data['filter_groups'], array($this->{$this->config->get('adminmonitor_model_key')}, 'sortGroupNameAlpha'));


        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('adminmonitor_text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('adminmonitor_text_module'),
            'href' => $this->url->link($this->config->get('adminmonitor_extension_route'), 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link($this->config->get('adminmonitor_module_path'), 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['action'] = $this->url->link($this->config->get('adminmonitor_module_path'), 'token=' . $this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link($this->config->get('adminmonitor_extension_route'), 'token=' . $this->session->data['token'], 'SSL');

        $data['rehook'] = $this->url->link($this->config->get('adminmonitor_module_path') . '/rehook_events', 'token=' . $this->session->data['token'], 'SSL');

        if (!empty($this->request->post)) {
            $data['setting'] = $this->request->post;
        } else {
            $data['setting'] = $this->model_setting_setting->getSetting('adminmonitor');
        }

        $data['token'] = $this->session->data['token'];

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view($this->config->get('adminmonitor_module_path') . '.tpl', $data));
    }

    public function rehook_events() {
        $this->language->load($this->config->get('adminmonitor_module_path'));
        
        if (!$this->validate()) {
            if (isset($this->error['warning'])) {
                $this->session->data['adminmonitor_error'] = $this->error['warning'];
            }
        } else {
            $this->load->model($this->config->get('adminmonitor_module_path'));

            $this->{$this->config->get('adminmonitor_model_key')}->unhookEvents();
            $this->{$this->config->get('adminmonitor_model_key')}->hookEvents();

            $this->session->data['success'] = $this->language->get('adminmonitor_text_success_rehook');
        }

        $this->response->redirect($this->url->link($this->config->get('adminmonitor_module_path'), 'token=' . $this->session->data['token'], 'SSL'));
    }

    public function log_event() {
        $allowed_groups = array('order');
        $allowed_types = array('add', 'edit');

        $group = !empty($this->request->get['event_group']) ? $this->request->get['event_group'] : null;
        $type = !empty($this->request->get['event_type']) ? $this->request->get['event_type'] : null;

        if (!in_array($group, $allowed_groups) || !in_array($type, $allowed_types)) return;

        $this->load->model($this->config->get('adminmonitor_module_path'));
        $this->language->load($this->config->get('adminmonitor_module_path'));

        $this->{$this->config->get('adminmonitor_model_key')}->logEvent(array(
            'user_id' => $this->user->getId(),
            'user_name' => $this->user->getUserName(),
            'event_type' => $type,
            'event_group' => $group,
            'argument_hook' => 'custom_' . $type,
            'data' => $this->{$this->config->get('adminmonitor_model_key')}->getCustomData($group, $this->request->get),
            'subject' => $this->{$this->config->get('adminmonitor_model_key')}->getCustomSubject($group, $this->request->get)
        ));
    }

    public function install() {
        $this->load->model($this->config->get('adminmonitor_module_path'));
        $this->{$this->config->get('adminmonitor_model_key')}->hookEvents();
    }

    public function event_sink_before($route, $del_id = null) {
        $data = is_numeric($del_id) ? array($del_id) : $del_id; // numeric in OpenCart <2.2.x, array in the rest.
        $parts = explode("/", $route);
        array_shift($parts);

        if (in_array('adminmonitor', $parts)) return;

        $this->load->model($this->config->get('adminmonitor_module_path'));
        $types = $this->{$this->config->get('adminmonitor_model_key')}->getEventTypes();

        foreach ($types as $type) {
            if (strpos(end($parts), $type) === 0) {
                $parts[count($parts) - 1] = $type;
                $function = "logevent_admin_pre_" . implode("_", $parts);
                $this->__call($function, $data);
                break;
            }
        }
    }

    public function event_sink_after($route) {
        $data = $this->getAfterArg(func_get_args());

        $parts = explode("/", $route);
        array_shift($parts);

        if (in_array('adminmonitor', $parts)) return;

        $this->load->model($this->config->get('adminmonitor_module_path'));
        $types = $this->{$this->config->get('adminmonitor_model_key')}->getEventTypes();

        foreach ($types as $type) {
            if (strpos(end($parts), $type) === 0) {
                $parts[count($parts) - 1] = $type;
                
                $function = "logevent_admin_post_" . implode("_", $parts);
                $this->__call($function, $data);
                break;
            }
        }
    }

    public function uninstall() {
        $this->load->model($this->config->get('adminmonitor_module_path'));
        $this->{$this->config->get('adminmonitor_model_key')}->unhookEvents();
        $this->{$this->config->get('adminmonitor_model_key')}->dropDB();
    }

    public function __call($function, $arg_raw) {
        $hook = substr($function, 9);

        $arg = $this->parseArg($arg_raw); // $arg_raw is expected to be an array containing a single integer at position 0

        $this->load->model($this->config->get('adminmonitor_module_path'));

        $this->language->load($this->config->get('adminmonitor_module_path'));

        $events = $this->{$this->config->get('adminmonitor_model_key')}->getEvents(true);

        foreach ($events as $event) {
            if ($event['argument_hook'] != $hook) continue;

            $subject = "";

            // Attempt to modify the $subject variable (either creating it to a link, or string)
            if (!empty($arg)) {
                eval($event['eval']);
            }

            $this->{$this->config->get('adminmonitor_model_key')}->logEvent(array(
                'user_id' => $this->user->getId(),
                'user_name' => $this->user->getUserName(),
                'event_type' => $event['type'],
                'event_group' => $event['group'],
                'argument_hook' => $hook,
                'data' => $arg,
                'subject' => $subject
            ));

            break;
        }
    }

    public function delete_events() {
        $this->language->load($this->config->get('adminmonitor_module_path'));
        
        $response = array();

        if (!$this->validate()) {
            $response['error'] = $this->error['warning'];
        }

        if (empty($response['error']) && !empty($this->request->post['delete_event'])) {
            $this->load->model($this->config->get('adminmonitor_module_path'));

            foreach ($this->request->post['delete_event'] as $event_id) {
                $this->{$this->config->get('adminmonitor_model_key')}->deleteEvent($event_id);
            }
        }

        $this->response->setOutput(json_encode($response));
    }

    public function list_events() {
        $this->load->model($this->config->get('adminmonitor_module_path'));
        $this->load->model('user/user');

        $this->language->load($this->config->get('adminmonitor_module_path'));

        $filters = $this->getFilters();

        $results = $this->{$this->config->get('adminmonitor_model_key')}->listEvents($filters);
        $total = $this->{$this->config->get('adminmonitor_model_key')}->eventsTotal($filters);
        $output = array();

        foreach ($results as $result) {
            $user_data = $this->model_user_user->getUser($result['user_id']);

            $output['events'][] = array(
                'adminmonitor_id' => $result['adminmonitor_id'],
                'user_name' => $user_data['firstname'] . ' ' . $user_data['lastname'] . ' (' . $result['user_name'] . ')',
                'message' =>  sprintf($this->language->get('message_' . $result['event_type']), $this->language->get('subject_prefix_' . $result['event_group']), str_replace('{token}', $this->session->data['token'], $result['subject'])),
                'date_created' => date($this->language->get('date_format_long') . ' ' . $this->language->get('time_format'), strtotime($result['date_created'])),
                'type' => $this->language->get('event_type_' . $result['event_type']),
                'group' => $this->language->get('event_group_' . $result['event_group']),
            );
        }

        $pagination = new Pagination();
        $pagination->total = $total;
        $pagination->page = $filters['page'];
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = '{page}';

        $output['pagination'] = $pagination->render();

        $this->response->setOutput(json_encode($output));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', $this->config->get('adminmonitor_module_path'))) {
            $this->error['warning'] = sprintf($this->language->get('error_permission'), $this->config->get('adminmonitor_module_path'));
        }

        return !$this->error;
    }

    private function parseArg($arg) {
        return !empty($arg[0]) && is_numeric($arg[0]) ? (int)$arg[0] : 0; // Used in OpenCart 2.0.0.0
    }

    private function getAfterArg($args) {
        if (VERSION < '2.2') {
            return !empty($args[1]) && is_numeric($args[1]) ? array((int)$args[1]) : array(0);
        } else if (VERSION < '2.3') {
            if (!empty($args[1])) {
                return is_numeric($args[1]) ? array((int)$args[1]) : array(0);
            } else if (!empty($args[2])) {
                return is_numeric($args[2]) ? array((int)$args[2]) : array(0);
            }
        } else {
            $final = end($args);

            if (!empty($final)) {
                return is_numeric($final) ? array((int)$final) : array(0);
            } else if (!empty($args[1][0])) {
                return is_numeric($args[1][0]) ? array((int)$args[1][0]) : array(0);
            }
        }
    }

    private function getFilters() {
        $result = array();

        $result['page'] = !empty($this->request->get['page']) ? (int)$this->request->get['page'] : 1;
        $result['limit'] = $this->config->get('config_limit_admin');

        if (!empty($this->request->get['filter_user_id'])) {
            $result['user_id'] = (int)$this->request->get['filter_user_id'];
        }

        if (!empty($this->request->get['filter_type'])) {
            $result['type'] = $this->request->get['filter_type'];
        }

        if (!empty($this->request->get['filter_group'])) {
            $result['group'] = $this->request->get['filter_group'];
        }

        if (!empty($this->request->get['filter_start'])) {
            $result['start'] = $this->request->get['filter_start'];
        }

        if (!empty($this->request->get['filter_end'])) {
            $result['end'] = $this->request->get['filter_end'];
        }

        return $result;
    }

    private function getTabs() {

        $this->language->load($this->config->get('adminmonitor_module_path'));

        if (!function_exists('modification_vqmod')) {
            function modification_vqmod($file) {
                if (class_exists('VQMod')) {
                    return VQMod::modCheck(modification($file), $file);
                } else {
                    return modification($file);
                }
            }
        }
        
        $dir = DIR_APPLICATION . 'view/template/' . $this->config->get('adminmonitor_module_path') . '/';

        $files = scandir($dir);
        $result = array();

        $name_map = array(
            'tab_list_events.php' => array(
                'name' => $this->language->get('tab_list_events'),
                'id' => 'tab_list_events',
                'icon' => 'list'
            ),
            'tab_support.php' => array(
                'name' => $this->language->get('tab_support'),
                'id' => 'tab_support',
                'icon' => 'life-ring'
            )
        );

        foreach ($files as $file) {
            if (!in_array($file, array_keys($name_map))) continue;

            $result[] = array(
                'file' => modification_vqmod($dir . $file),
                'name' => $name_map[$file]['name'],
                'id' => $name_map[$file]['id'],
                'icon' => $name_map[$file]['icon']
            );
        }

        return $result;
    }
}
