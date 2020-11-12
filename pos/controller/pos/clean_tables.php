<?php
class ControllerPosCleanTables extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('pos/clean_tables');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('pos/clean_tables');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->model_pos_clean_tables->deleteTables((!empty($this->request->post['selected'])) ? $this->request->post['selected'] : array());
		}
		$this->getTableList();
	}
	
	private function getTableList() {
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$url = '&page=' . $page;

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('pos/clean_tables', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		$data['action_delete'] = $this->url->link('pos/clean_tables', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
		$table_total = $this->model_pos_clean_tables->getTotalTables();
		$start = $page * $this->config->get('config_limit_admin');
		$limit = $this->config->get('config_limit_admin');
		$data['tables'] = $this->model_pos_clean_tables->getTables($start, $limit);

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_table_list'] = $this->language->get('text_table_list');
		$data['text_no_tables'] = $this->language->get('text_no_tables');

		$data['column_table_name'] = $this->language->get('column_table_name');
		$data['column_table_row'] = $this->language->get('column_table_row');

		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['button_delete'] = $this->language->get('button_delete');

		$data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
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

		$pagination = new Pagination();
		$pagination->total = $table_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('pos/clean_tables', 'token=' . $this->session->data['token'] . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($table_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($table_total - $this->config->get('config_limit_admin'))) ? $table_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $table_total, ceil($table_total / $this->config->get('config_limit_admin')));

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('pos/clean_tables.tpl', $data));
	}
}