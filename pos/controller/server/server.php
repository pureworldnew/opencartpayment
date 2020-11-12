<?php
class ControllerServerServer extends Controller {
	public function index() {

	}

	public function ping() {
		$output = array(
			'status' => 'false'
		);

		if ($this->user->isLogged() && isset($this->request->get['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
			$output['status'] = 'true';
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($output));
	}
}
