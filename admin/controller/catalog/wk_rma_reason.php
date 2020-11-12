<?php
/**
 * Webkul Software.
 * @category  Webkul
 * @author    Webkul
 * @copyright Copyright (c) 2010-2016 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
class ControllerCatalogWkRmaReason extends Controller {

	private $error = array();
	use RmaControllerTrait;

	public function index() {

		if (!$this->config->get('wk_rma_status'))
			$this->response->redirect($this->urlChange('common/dashboard', 'token=' . $this->session->data['token'], true));

		$data = array_merge($data = array(),$this->language->load('catalog/wk_rma_reason'));

		$filter_array = array(
		  'filter_reason',
		  'filter_status',
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
				if ($key=='filter_reason')
					$url .= '&'.$key.'=' . urlencode(html_entity_decode($filter_array[$key], ENT_QUOTES, 'UTF-8'));
				else
					$url .= '&'.$key.'='. $filter_array[$key];
			}
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/wk_rma_admin');

		$data['breadcrumbs'] = array();

 		$data['breadcrumbs'][] = array(
     		'text'      => $this->language->get('text_home'),
				'href'      => $this->urlChange('common/dashboard', 'token=' . $this->session->data['token'].$url , 'SSL'),
    		'separator' => false
 		);

 		$data['breadcrumbs'][] = array(
     		'text'      => $this->language->get('heading_title'),
				'href'      => $this->urlChange('catalog/wk_rma_reason', 'token=' . $this->session->data['token'].$url , 'SSL'),
    		'separator' => ' :: '
 		);

		$reason_total = $this->model_catalog_wk_rma_admin->viewtotalreason($filter_array);

		$results = $this->model_catalog_wk_rma_admin->viewreason($filter_array);

		$data['result'] = array();

		foreach ($results as $result) {
			$action = array();

			$action = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->urlChange('catalog/wk_rma_reason/update', 'token=' . $this->session->data['token'] .'&id=' . $result['reason_id'], 'SSL')
			);

			$data['result'][] = array(
				'selected'=>False,
				'id' => $result['reason_id'],
				'reason' => $result['reason'],
				'status' => $result['status'],
				'action' => $action
			);

		}

		$data['delete'] = $this->urlChange('catalog/wk_rma_reason/delete', 'token=' . $this->session->data['token'], 'SSL');
		$data['insert'] = $this->urlChange('catalog/wk_rma_reason/insert', 'token=' . $this->session->data['token'], 'SSL');

 		$data['token'] = $this->session->data['token'];

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['warning'])) {
			$data['error_warning'] = $this->session->data['warning'];
			unset($this->session->data['warning']);
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
				elseif ($key=='filter_reason')
					$url .= '&'.$key.'=' . urlencode(html_entity_decode($filter_array[$key], ENT_QUOTES, 'UTF-8'));
				elseif($key=='order')
					$url .= $value=='ASC' ? '&order=DESC' : '&order=ASC';
				elseif($key!='start' AND $key!='limit' AND $key!='sort')
					$url .= '&'.$key.'='. $filter_array[$key];
			}
		}

		$data['sort_reason'] = $this->urlChange('catalog/wk_rma_reason', 'token=' . $this->session->data['token'] . '&sort=wrr.reason' . $url, 'SSL');
		$data['sort_status'] = $this->urlChange('catalog/wk_rma_reason', 'token=' . $this->session->data['token'] . '&sort=wrr.status' . $url, 'SSL');

		$url = '';

		foreach ($filter_array as $key => $value) {
			if(isset($this->request->get[$key])){
				if(!isset($this->request->get['order']) AND isset($this->request->get['sort']))
					$url .= '&order=DESC';
				elseif ($key=='filter_reason')
					$url .= '&'.$key.'=' . urlencode(html_entity_decode($filter_array[$key], ENT_QUOTES, 'UTF-8'));
				elseif($key!='page')
					$url .= '&'.$key.'='. $filter_array[$key];
			}
		}

		$limit = $this->config->get('config_limit_admin') ? $this->config->get('config_limit_admin') : 20;

		$pagination = new Pagination();
		$pagination->total = $reason_total;
		$pagination->page = $filter_array['page'];
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->urlChange('catalog/wk_rma_reason', 'token=' . $this->session->data['token'] . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($reason_total) ? (($filter_array['page'] - 1) * $limit) + 1 : 0, ((($filter_array['page'] - 1) * $limit) > ($reason_total - $limit)) ? $reason_total : ((($filter_array['page'] - 1) * $limit) + $limit), $reason_total, ceil($reason_total / $limit));

		foreach ($filter_array as $key => $value) {
			if($key!='start' AND $key!='end')
				$data[$key] = $value;
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/wk_rma_reason.tpl', $data));
  }

  public function insert() {

		if (!$this->config->get('wk_rma_status'))
			$this->response->redirect($this->urlChange('common/dashboard', 'token=' . $this->session->data['token'], true));

    $this->language->load('catalog/wk_rma_reason');
		$this->document->setTitle($this->language->get('heading_title_insert'));
		$data['heading_title'] = $this->language->get('heading_title_insert');
		$this->load->model('catalog/wk_rma_admin');

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate('catalog/wk_rma_reason')) {
    	foreach ($this->request->post['reason'] as $key => $value) {
    		if ((utf8_strlen($value) < 5) || (utf8_strlen($value) > 50)) {
					$error = $this->language->get('error_reason');
					break;
				}
	    }

			if (!isset($error)) {
				$this->model_catalog_wk_rma_admin->addReason($this->request->post);
				$this->session->data['success'] = $this->language->get('text_success_insert');
				$this->response->redirect($this->urlChange('catalog/wk_rma_reason', 'token=' . $this->session->data['token'] , 'SSL'));
			}
			$this->error['warning'] = $error ;
		} else if ($this->request->server['REQUEST_METHOD'] == 'POST'){
			$this->session->data['error_warning'] = $this->language->get('error_permission');
		}
		$this->getform();
	}

  public function update() {

		if (!$this->config->get('wk_rma_status'))
			$this->response->redirect($this->urlChange('common/dashboard', 'token=' . $this->session->data['token'], true));

		$this->language->load('catalog/wk_rma_reason');
    $this->document->setTitle($this->language->get('heading_title_insert'));
		$data['heading_title'] = $this->language->get('heading_title_update');
		$this->load->model('catalog/wk_rma_admin');
    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate('catalog/wk_rma_reason')) {
    	foreach ($this->request->post['reason'] as $key => $value) {
    		if ((utf8_strlen($value) < 5) || (utf8_strlen($value) > 50)) {
					$error = $this->language->get('error_reason');
					break;
				}
	    }
			if (!isset($error)) {
				$this->model_catalog_wk_rma_admin->UpdateReason($this->request->post);
				$this->session->data['success'] = $this->language->get('text_success_update');
				$this->response->redirect($this->urlChange('catalog/wk_rma_reason', 'token=' . $this->session->data['token'] , 'SSL'));
			}
			$this->error['warning'] = $error ;
	  } else if ($this->request->server['REQUEST_METHOD'] == 'POST'){
			$this->session->data['error_warning'] = $this->language->get('error_permission');
		}
    $this->getform();
  }

  private function getform() {

		$data = array_merge($data = array(), $this->language->load('catalog/wk_rma_reason'));

  	$data['breadcrumbs'] = array();
   	$data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_home'),
			'href'      => $this->urlChange('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
      'separator' => false
   	);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->urlChange('catalog/wk_rma_reason', 'token=' . $this->session->data['token'] , 'SSL'),
			'separator' => ' :: '
		);

		$config_data = array(
				'id',
				'reason',
				'status',
			);

		foreach ($config_data as $conf) {
			if (isset($this->request->post[$conf])) {
				$data[$conf] = $this->request->post[$conf];
			}
		}

		$data['save'] = $this->urlChange('catalog/wk_rma_reason/insert', 'token=' . $this->session->data['token'], 'SSL');

		if(isset($this->request->get['id'])){
			$id = $this->request->get['id'];

			$results = $this->model_catalog_wk_rma_admin->viewreasonbyId($id);

			if($results){
				foreach ($results as $key => $result) {
					$data['id'] = $result['reason_id'];
					$data['status'] = $result['status'];
					$data['reason'][$result['language_id']] = $result['reason'];
				}
			}
			$data['text_form'] = $this->language->get('text_edit_form');
			$data['save'] = $this->urlChange('catalog/wk_rma_reason/update', 'token=' . $this->session->data['token'], 'SSL');
		}

		if(!empty($data['id'])){
			$data['text_form'] = $this->language->get('text_edit_form');
			$data['save'] = $this->urlChange('catalog/wk_rma_reason/update', 'token=' . $this->session->data['token'] ,'SSL');
		}

		$data['back'] = $this->urlChange('catalog/wk_rma_reason', 'token=' . $this->session->data['token'], 'SSL');

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

		$this->response->setOutput($this->load->view('catalog/wk_rma_reason_form.tpl', $data));
  	}

  public function delete() {

		if (!$this->config->get('wk_rma_status'))
			$this->response->redirect($this->urlChange('common/dashboard', 'token=' . $this->session->data['token'], true));

    $this->language->load('catalog/wk_rma_reason');
    $this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/wk_rma_admin');

		if (isset($this->request->post['selected']) && $this->validate('catalog/wk_rma_reason')) {
			foreach ($this->request->post['selected'] as $id) {
				$this->model_catalog_wk_rma_admin->deleteReason((int)$id);
	  	}
			$this->session->data['success'] = $this->language->get('text_success');
			$url='';
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->response->redirect($this->urlChange('catalog/wk_rma_reason', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		} else {
			$this->session->data['error_warning'] = $this->language->get('error_selected');
			if (!$this->validate('catalog/wk_rma_reason')) {
				$this->session->data['error_warning'] = $this->language->get('error_permission');
			}
		}
    $this->index();
  }

}
