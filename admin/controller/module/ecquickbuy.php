<?php
class ControllerModuleEcquickbuy extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('module/ecquickbuy');
		$this->load->model('tool/image');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('ecquickbuy', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('module/ecquickbuy', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['heading_title'] = $this->language->get('heading_title');
                $data['text_header'] = $this->language->get('text_header'); 
		$data['text_image_manager'] = $this->language->get('text_image_manager');
 		$data['text_browse'] = $this->language->get('text_browse');
		$data['text_clear'] = $this->language->get('text_clear');			
				
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_content_top'] = $this->language->get('text_content_top');
		$data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$data['text_column_left'] = $this->language->get('text_column_left');
		$data['text_column_right'] = $this->language->get('text_column_right');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_alllayout'] = $this->language->get('text_alllayout');
		
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_store'] = $this->language->get('entry_store');
		
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_layout'] = $this->language->get('entry_layout');
		$data['entry_position'] = $this->language->get('entry_position');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_limit'] = $this->language->get('entry_limit');
		$data['entry_view_direction'] = $this->language->get('entry_view_direction');
		$data['entry_width_of_input'] = $this->language->get('entry_width_of_input');
		$data['entry_popup_width_height'] = $this->language->get('entry_popup_width_height');
		$data['entry_item_text_color'] = $this->language->get('entry_item_text_color');
		$data['entry_hover_item_bgcolor'] = $this->language->get('entry_hover_item_bgcolor');
		$data['entry_show_title'] = $this->language->get('entry_show_title');
		$data['entry_show_qty'] = $this->language->get('entry_show_qty');
		$data['entry_show_category'] = $this->language->get('entry_show_category');
		$data['entry_show_manufacturer'] = $this->language->get('entry_show_manufacturer');
		$data['entry_display_see_all'] = $this->language->get('entry_display_see_all');
		$data['entry_display_tag_suggestion'] = $this->language->get('entry_display_tag_suggestion');
		$data['entry_display_image'] = $this->language->get('entry_display_image');
		$data['entry_display_price'] = $this->language->get('entry_display_price');
		$data['entry_display_search_sub_category'] = $this->language->get('entry_display_search_sub_category');
		$data['entry_display_search_description'] = $this->language->get('entry_display_search_description');
		$data['text_horizontal'] = $this->language->get('text_horizontal');
		$data['text_vertical'] = $this->language->get('text_vertical');
		
		 
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_add_module'] = $this->language->get('button_add_module');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);		
		$data['entry_show_image'] = $this->language->get( 'entry_show_image' );
		$data['entry_module_title'] = $this->language->get( 'entry_module_title' );
		$data['tab_module'] = $this->language->get('tab_module_banner');
		$data['entry_image_navigator'] = $this->language->get( 'entry_image_navigator' );
		$data['entry_navigator_width'] = $this->language->get( 'entry_navigator_width' );
		$data['entry_navigator_height'] = $this->language->get( 'entry_navigator_height' );
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => '::'
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/ecquickbuy', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => '::'
		);

		$data['action'] = $this->url->link('module/ecquickbuy', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['ecquickbuy_module'])) {
			$data['module'] = $this->request->post['ecquickbuy_module'];
		} elseif ($this->config->get('ecquickbuy_module')) { 
			$data['module'] = $this->config->get('ecquickbuy_module');
		}	

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/ecquickbuy.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/account')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}