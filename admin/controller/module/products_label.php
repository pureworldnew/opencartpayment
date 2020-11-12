<?php
class ControllerModuleProductslabel extends Controller {
    private $error = array();

    public function index()
    {

        $this->load->language('module/products_label');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/module');


        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            if (!isset($this->request->get['module_id'])) {
                $this->model_extension_module->addModule('products_label', $this->request->post);
            } else {
                $this->model_extension_module->editModule($this->request->get['module_id'], $this->request->post);
            }

        }

        $data['heading_title'] = $this->language->get('heading_title');

        $this->load->language('localisation/products_label');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('localisation/products_label');
        $this->getForm();
    }
    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'module/products_label')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function install() {


         $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "products_label` (
 `label_id` int(5) unsigned NOT NULL  PRIMARY KEY,
 `label_text` varchar(50) NOT NULL,
 `label_color` char(30) NOT NULL,
`label_text_color` char(30) NOT NULL,
`condition_type` int(3)  NOT NULL,
`status` enum('0','1') NOT NULL,
`position` int(3)  NOT NULL)");

        $this->db->query("insert into `" . DB_PREFIX . "products_label` (label_id,label_text,label_color,label_text_color,condition_type,status,position)values (1,'New','#1cbfef','#c64a6a',30,1,2)");
        $this->db->query("insert into `" . DB_PREFIX . "products_label` (label_id,label_text,label_color,label_text_color,condition_type,status,position)values (2,'Discount','#1cd04a','#25353a',50,1,1)");
        $this->db->query("insert into `" . DB_PREFIX . "products_label` (label_id,label_text,label_color,label_text_color,condition_type,status,position)values (3,'Free shipping','#f12e1c','#d7ebf1',0,1,4)");
        $this->db->query("insert into `" . DB_PREFIX . "products_label` (label_id,label_text,label_color,label_text_color,condition_type,status,position)values (4,'Out Of Stack','#43a19d','#d7ebf1',0,1,3)");

        $Enable=1;
        $this->load->model('setting/setting');
        $this->model_setting_setting->editSetting('product_label_install_status',array('product_label_install_status'=>$Enable));

     }
     public function uninstall() {
         $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "products_label`");

         $Enable=0;
         $this->load->model('setting/setting');
         $this->model_setting_setting->editSetting('product_label_install_status',array('product_label_install_status'=>$Enable));

     }

     protected function getForm() {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !isset($this->request->get['label_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_addlabel'] = $this->language->get('text_addlabel');

        $data['text_label_text'] = $this->language->get('text_label_text');
        $data['text_label_color'] = $this->language->get('text_label_color');
        $data['text_label_text_color'] = $this->language->get('text_label_text_color');
        $data['text_label_conditiontype'] = $this->language->get('text_label_conditiontype');
        $data['text_label_id'] = $this->language->get('text_label_id');


        $data['text_addnew'] = $this->language->get('text_addnew');
        $data['text_addshipping'] = $this->language->get('text_addshipping');
        $data['text_adddiscount'] = $this->language->get('text_adddiscount');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_code'] = $this->language->get('entry_code');
        $data['entry_locale'] = $this->language->get('entry_locale');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_directory'] = $this->language->get('entry_directory');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['entry_status'] = $this->language->get('entry_status');

        $data['help_code'] = $this->language->get('help_code');
        $data['help_locale'] = $this->language->get('help_locale');
        $data['help_image'] = $this->language->get('help_image');
        $data['help_directory'] = $this->language->get('help_directory');
        $data['help_status'] = $this->language->get('help_status');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
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

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('localisation/products_label', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );


       //$label_info = $this->model_localisation_products_label->getLabels();

        $data['action'] = $this->url->link('localisation/products_label/edit', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['labels']=array();

            $label_info = $this->model_localisation_products_label->getLabels();

        foreach($label_info as $labelget){
            $data['labels'][]=array(
                'label_id'=> $labelget['label_id'],
                'label_text'=> $labelget['label_text'],
                'label_color'=> $labelget['label_color'],
                'label_text_color'=> $labelget['label_text_color'],
                'condition_type'=> $labelget['condition_type'],
                'status'=> $labelget['status'],
                'position'=> $labelget['position']

            );
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('localisation/products_label.tpl', $data));
    }
}
?>
