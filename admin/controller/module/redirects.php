<?php

class ControllerModuleRedirects extends Controller {

	private $error = array();



	public function index() {



		$this->document->setTitle("Manage your sites redirects");

		$data['old_url']        = "Old URL";
        $data['new_url']        = "New URL";
		$data['heading_title']  = "Manage Link Redirects";
        $data['text_edit']      = "Current redirect list";

		$this->load->model('module/redirects');
        $data['redirects']    = $this->model_module_redirects->listAllRedirects();

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_save'] = $this->language->get('button_save');

		$data['button_cancel'] = $this->language->get('button_cancel');


		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
            'text' => "Redirects",
			'href' => $this->url->link('module/redirects', 'token=' . $this->session->data['token'], true)
		);

         //die("NO WAY1");


        

		$data['action'] = $this->url->link('module/redirects/saveRedirects', 'token=' . $this->session->data['token'], true);
        //$data['copy'] = $this->url->link('module/redirects/loadDatas', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

         

		$this->response->setOutput($this->load->view('module/redirects.tpl', $data));
        //die("NO WAY");

	}

        

    public function saveRedirects() {
        
        if(!isset($_POST['redirect'])) {
            $this->response->redirect($this->url->link('module/redirects', 'token=' . $this->session->data['token'], true));
            exit();
        }

        if (isset($_POST['upddata']))
        {
            $this->load->model('module/redirects');
            $this->model_module_redirects->loadData();
        }


        $redirects = $_POST['redirect'];

        // first make sure that each entry will pass validation
        foreach ($redirects as $redirect) {
            $data['old_url'] = $this->validateURL($redirect['old_url']);
            $data['new_url'] = $this->validateURL($redirect['new_url']);
            if(!$data['old_url'] || !$data['new_url']){
                // failed the validation rules!
                // do not proceed with script, because old entries would be deleted
                // TODO: retain bad entries when we return to the home page and notify user of this
                // NOTE: most validation is done in the front end currently.
                $this->response->redirect($this->url->link('module/redirects', 'token=' . $this->session->data['token'], true));
                exit();
            }
        }

        // passed validation, lets update the redirect table
        $this->load->model('module/redirects');
        $this->model_module_redirects->deleteAllRedirects();

        foreach ($redirects as $redirect) {
            $data['old_url'] = $this->validateURL($redirect['old_url']);
            $data['new_url'] = $this->validateURL($redirect['new_url']);

            $this->model_module_redirects->saveRedirect($data);
        }

        $this->response->redirect($this->url->link('module/redirects', 'token=' . $this->session->data['token'], true));

    }

    private function validateURL($data){
        // NOTE: most validation is done in the front end currently
        // 1. strip leading and trailing slashes
        // 2. check for illegal characters

        if(strlen($data) > 255) {
            return false;
        }
        $result = trim($data);
        return $result;
    }

	protected function validate() {

		if (!$this->user->hasPermission('modify', 'module/redirects')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}