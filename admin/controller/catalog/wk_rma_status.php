<?php
/**
 * Webkul Software.
 * @category  Webkul
 * @author    Webkul
 * @copyright Copyright (c) 2010-2016 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
class ControllerCatalogWkRmaStatus extends Controller {

	private $error = array();
	use RmaControllerTrait;

	public function index() {

		if (!$this->config->get('wk_rma_status'))
			$this->response->redirect($this->urlChange('common/dashboard', 'token=' . $this->session->data['token'], true));

		$data = array_merge($data = array(), $this->language->load('catalog/wk_rma_status'));

		$filter_array = array(
							  'filter_name',
							  'filter_status',
							  'filter_assign',
							  'filter_ostatus', // order status
							  'page',
							  'sort',
							  'order',
							  'start',
							  'limit',
							  );

		$url = '';

		foreach ($filter_array as $unsetKey => $key) {

			if (isset($this->request->get[$key])) {
				$filter_array[$key] = $this->request->get[$key];
			} else {
				if ($key=='page')
					$filter_array[$key] = 1;
				elseif($key=='sort')
					$filter_array[$key] = 'cs.id';
				elseif($key=='order')
					$filter_array[$key] = 'ASC';
				elseif($key=='start')
					$filter_array[$key] = ($filter_array['page'] - 1) * $this->config->get('config_admin_limit');
				elseif($key=='limit')
					$filter_array[$key] = $this->config->get('config_admin_limit');
				else
					$filter_array[$key] = null;
			}
			unset($filter_array[$unsetKey]);

			if(isset($this->request->get[$key])){
				if ($key=='filter_name')
					$url .= '&'.$key.'=' . urlencode(html_entity_decode($filter_array[$key], ENT_QUOTES, 'UTF-8'));
				else
					$url .= '&'.$key.'='. $filter_array[$key];
			}
		}

		$this->language->load('catalog/wk_rma_status');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/wk_rma_admin');

  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->urlChange('common/dashboard', 'token=' . $this->session->data['token'].$url, 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->urlChange('catalog/wk_rma_status', 'token=' . $this->session->data['token'].$url , 'SSL'),
      		'separator' => ' :: '
   		);

		$status_total = $this->model_catalog_wk_rma_admin->viewtotalStatus($filter_array);

		$results = $this->model_catalog_wk_rma_admin->viewStatus($filter_array);

		$data['result'] = array();

		foreach ($results as $result) {
			$action = array();
			$action = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->urlChange('catalog/wk_rma_status/update', 'token=' . $this->session->data['token'] .'&id=' . $result['status_id'], 'SSL')
			);
			$foruser = '';

			$data['result'][] = array(
				'selected'=>False,
				'id' => $result['status_id'],
				'name' => $result['name'],
				'status' => $result['status'],
				'color' => $result['color'],
				'action' => $action
				);

		}

		$data['delete'] = $this->urlChange('catalog/wk_rma_status/delete', 'token=' . $this->session->data['token'], 'SSL');
		$data['insert'] = $this->urlChange('catalog/wk_rma_status/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->load->model('catalog/wk_rma_admin');

		$data['defaultRmaStatus'] = $this->model_catalog_wk_rma_admin->defaultRmaStatus();
		$data['solveRmaStatus'] = $this->model_catalog_wk_rma_admin->solveRmaStatus();
		$data['cancelRmaStatus'] = $this->model_catalog_wk_rma_admin->cancelRmaStatus();

 		$data['token'] = $this->session->data['token'];

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];
			unset($this->session->data['error_warning']);
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$url = '';

		foreach ($filter_array as $key => $value) {
			if(isset($this->request->get[$key])){
				if(!isset($this->request->get['order']) AND isset($this->request->get['sort']))
					$url .= '&order=DESC';
				if ($key=='filter_name')
					$url .= '&'.$key.'=' . urlencode(html_entity_decode($filter_array[$key], ENT_QUOTES, 'UTF-8'));
				elseif($key=='order')
					$url .= $value=='ASC' ? '&order=DESC' : '&order=ASC';
				elseif($key!='start' AND $key!='limit' AND $key!='sort')
					$url .= '&'.$key.'='. $filter_array[$key];
			}
		}

		$data['sort_name'] = $this->urlChange('catalog/wk_rma_status', 'token=' . $this->session->data['token'] . '&sort=wrs.name' . $url, 'SSL');
		$data['sort_status'] = $this->urlChange('catalog/wk_rma_status', 'token=' . $this->session->data['token'] . '&sort=wrs.status' . $url, 'SSL');
		$data['sort_assign'] = $this->urlChange('catalog/wk_rma_status', 'token=' . $this->session->data['token'] . '&sort=wrs.id' . $url, 'SSL');

		$url = '';

		foreach ($filter_array as $key => $value) {
			if(isset($this->request->get[$key])){
				if(!isset($this->request->get['order']) AND isset($this->request->get['sort']))
					$url .= '&order=DESC';
				if ($key=='filter_name')
					$url .= '&'.$key.'=' . urlencode(html_entity_decode($filter_array[$key], ENT_QUOTES, 'UTF-8'));
				elseif($key!='page')
					$url .= '&'.$key.'='. $filter_array[$key];
			}
		}

		$limit = $this->config->get('config_limit_admin') ? $this->config->get('config_limit_admin') : 20;

		$pagination = new Pagination();
		$pagination->total = $status_total;
		$pagination->page = $filter_array['page'];
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->urlChange('catalog/wk_rma_status', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($status_total) ? (($filter_array['page'] - 1) * $limit) + 1 : 0, ((($filter_array['page'] - 1) * $limit) > ($status_total - $limit)) ? $status_total : ((($filter_array['page'] - 1) * $limit) + $limit), $status_total, ceil($status_total / $limit));

		foreach ($filter_array as $key => $value) {
			if($key!='start' AND $key!='end')
				$data[$key] = $value;
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/wk_rma_status.tpl', $data));
  }

  public function insert() {

		if (!$this->config->get('wk_rma_status'))
			$this->response->redirect($this->urlChange('common/dashboard', 'token=' . $this->session->data['token'], true));

    $this->language->load('catalog/wk_rma_status');
    $this->document->setTitle($this->language->get('heading_title_insert'));
		$data['heading_title'] = $this->language->get('heading_title_insert');
		$this->load->model('catalog/wk_rma_admin');
    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate('catalog/wk_rma_status')) {
  		foreach ($this->request->post['name'] as $key => $value) {
  			if ((utf8_strlen($value) < 5) || (utf8_strlen($value) > 50)) {
					$error = $this->language->get('error_name');
					break;
			  }
  	  }
    	$this->request->post['admin'] = 1;
			if (!isset($error)) {
				$this->model_catalog_wk_rma_admin->addStatus($this->request->post);
				$this->session->data['success'] = $this->language->get('text_success_insert');
				$this->response->redirect($this->urlChange('catalog/wk_rma_status', 'token=' . $this->session->data['token'] , 'SSL'));
			}
			$this->error['warning'] = $error ;
    } else if ($this->request->server['REQUEST_METHOD'] == 'POST'){
			$this->session->data['error_warning'] = $this->language->get('error_permission');
		}

    $this->getfrom();
  }

  public function update() {

		if (!$this->config->get('wk_rma_status'))
			$this->response->redirect($this->urlChange('common/dashboard', 'token=' . $this->session->data['token'], true));

    $this->language->load('catalog/wk_rma_status');
    $this->document->setTitle($this->language->get('heading_title_insert'));
		$data['heading_title'] = $this->language->get('heading_title_update');
		$this->load->model('catalog/wk_rma_admin');

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate('catalog/wk_rma_status')) {

  		foreach ($this->request->post['name'] as $key => $value) {
  			if ((utf8_strlen($value) < 5) || (utf8_strlen($value) > 50)) {
					$error = $this->language->get('error_name');
					break;
				}
  		}
    	$this->request->post['admin'] = 1;
			if (!isset($error)) {
				$this->model_catalog_wk_rma_admin->UpdateStatus($this->request->post);
				$this->session->data['success'] = $this->language->get('text_success_update');
				$this->response->redirect($this->urlChange('catalog/wk_rma_status', 'token=' . $this->session->data['token'] , 'SSL'));
			}
			$this->error['warning'] = $error ;
    } else if ($this->request->server['REQUEST_METHOD'] == 'POST'){
			$this->session->data['error_warning'] = $this->language->get('error_permission');
		}
    $this->getfrom();
  }

  private function getfrom() {

		$data = array_merge($data = array(), $this->language->load('catalog/wk_rma_status'));

  	$data['breadcrumbs'] = array();
   	$data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_home'),
			'href'      => $this->urlChange('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
      'separator' => false
   	);
   	$data['breadcrumbs'][] = array(
      'text'      => $this->language->get('heading_title'),
			'href'      => $this->urlChange('catalog/wk_rma_status', 'token=' . $this->session->data['token'] , 'SSL'),
      'separator' => ' :: '
    );

		$this->document->addScript('view/javascript/jscolor/js/colorpicker.js');
		$this->document->addStyle('view/javascript/jscolor/css/colorpicker.css');

		$config_data = array(
				'id',
				'name',
				'status',
				'admin',
				'color'
			);

		foreach ($config_data as $conf) {
			if (isset($this->request->post[$conf])) {
				$data[$conf] = $this->request->post[$conf];
			}
		}

		$data['save'] = $this->urlChange('catalog/wk_rma_status/insert', 'token=' . $this->session->data['token'], 'SSL');

		if(isset($this->request->get['id'])){
			$id = $this->request->get['id'];

			$results = $this->model_catalog_wk_rma_admin->viewStatusbyId($id);

			if($results){
				foreach ($results as $key => $result) {
					$data['id'] = $result['status_id'];
					$data['status'] = $result['status'];
					$data['color'] = $result['color'];
					$data['name'][$result['language_id']] = $result['name'];
				}
			}
			$data['text_form'] = $this->language->get('text_edit_form');
			$data['save'] = $this->urlChange('catalog/wk_rma_status/update', 'token=' . $this->session->data['token'], 'SSL');
		}

		if(!empty($data['id'])){
			$data['text_form'] = $this->language->get('text_edit_form');
			$data['save'] = $this->urlChange('catalog/wk_rma_status/update', 'token=' . $this->session->data['token'] ,'SSL');
		}

		$data['back'] = $this->urlChange('catalog/wk_rma_status', 'token=' . $this->session->data['token'], 'SSL');

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

		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/wk_rma_status_form.tpl', $data));
  	}

  public function delete() {

		if (!$this->config->get('wk_rma_status'))
			$this->response->redirect($this->urlChange('common/dashboard', 'token=' . $this->session->data['token'], true));

    $this->language->load('catalog/wk_rma_status');
    $this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/wk_rma_admin');
		if (isset($this->request->post['selected']) && $this->validate('catalog/wk_rma_status')) {
			foreach ($this->request->post['selected'] as $id) {
				$this->model_catalog_wk_rma_admin->deleteStatus((int)$id);
	  	}
			$this->session->data['success'] = $this->language->get('text_success');
			$url='';
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->response->redirect($this->urlChange('catalog/wk_rma_status', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		} else {
			$this->session->data['error_warning'] = $this->language->get('error_selected');
			if (!$this->validate('catalog/wk_rma_reason')) {
				$this->session->data['error_warning'] = $this->language->get('error_permission');
			}
		}
    $this->index();
  }

	public function approveAdminStatus() {
		$json = array();
		if (isset($this->request->post['id']) && isset($this->request->post['approve']) && $this->validate('catalog/wk_rma_status')) {
			$this->load->model('catalog/wk_rma_admin');
			$json['success'] = $this->model_catalog_wk_rma_admin->approveAdminStatus($this->request->post);
		} else {
			$this->load->language('catalog/wk_rma_status');
			$this->session->data['error_warning'] = $this->language->get('error_permission');
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function approveSolveStatus() {
		$json = array();
		if (isset($this->request->post['id']) && isset($this->request->post['approve']) && $this->validate('catalog/wk_rma_status')) {
			$this->load->model('catalog/wk_rma_admin');
			$json['success'] = $this->model_catalog_wk_rma_admin->approveSolveStatus($this->request->post);
		} else {
			$this->load->language('catalog/wk_rma_status');
			$this->session->data['error_warning'] = $this->language->get('error_permission');
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function approveCancelStatus() {
		$json = array();
		if (isset($this->request->post['id']) && isset($this->request->post['approve']) && $this->validate('catalog/wk_rma_status')) {
			$this->load->model('catalog/wk_rma_admin');
			$json['success'] = $this->model_catalog_wk_rma_admin->approveCancelStatus($this->request->post);
		} else {
			$this->load->language('catalog/wk_rma_status');
			$this->session->data['error_warning'] = $this->language->get('error_permission');
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

}
