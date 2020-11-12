<?php
class ControllerCommonLogin extends Controller {
	private $error = array();

	public function index() {

		if (isset($_GET['cron']) && ($_GET['cron'] == 1) && $this->config->get('advppp' . $_GET['user_id'] . 'cron_setting')) {
			$cron_settings = unserialize($this->config->get('advppp' . $_GET['user_id'] . 'cron_setting'));
			foreach ($cron_settings as $cron_setting) {
            	if (isset($_GET['cron_token']) && ($_GET['cron_token'] == $cron_setting['cron_token']) && isset($_GET['cron_id']) && ($_GET['cron_id'] == $cron_setting['cron_id'])) {
					$this->session->data['token'] = md5(mt_rand());
					$this->request->get['token'] = $this->session->data['token'];
					$cron_route = (isset($_GET['cron_route']) ? $_GET['cron_route'] : 'common/logout');
					$this->user->login($cron_setting['cron_user'], base64_decode(base64_decode($cron_setting['cron_pass'])));
					$this->response->redirect($this->url->link($cron_route, 'user_id='.$cron_setting['cron_user_id'].'&token='.$this->session->data['token'].'&cron_id='.$cron_setting['cron_id'].'&cron=2', 'SSL'));
				}
			}
		}
            
		$this->load->language('common/login');

		$this->document->setTitle($this->language->get('heading_title'));

		if ($this->user->isLogged() && isset($this->request->get['token']) && ($this->request->get['token'] == $this->session->data['token'])) {

			//$this->pos_redirect();
			$this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->session->data['token'] = token(32);

			if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], HTTP_SERVER) === 0 || strpos($this->request->post['redirect'], HTTPS_SERVER) === 0 )) {
				$this->response->redirect($this->request->post['redirect'] . '&token=' . $this->session->data['token']);
			} else {
				$this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_login'] = $this->language->get('text_login');
		$data['text_forgotten'] = $this->language->get('text_forgotten');

		$data['entry_username'] = $this->language->get('entry_username');
		$data['entry_password'] = $this->language->get('entry_password');

		$data['button_login'] = $this->language->get('button_login');

		if ((isset($this->session->data['token']) && !isset($this->request->get['token'])) || ((isset($this->request->get['token']) && (isset($this->session->data['token']) && ($this->request->get['token'] != $this->session->data['token']))))) {
			$this->error['warning'] = $this->language->get('error_token');
		}

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

if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
				$data['ajax_action'] = HTTPS_SERVER . 'controller/pos/ajax_login.php';
			} else {
				$data['ajax_action'] = HTTP_SERVER . 'controller/pos/ajax_login.php';
			}
			
		$data['action'] = $this->url->link('common/login', '', 'SSL');

		if (isset($this->request->post['username'])) {
			$data['username'] = $this->request->post['username'];
		} else {
			$data['username'] = '';
		}

		if (isset($this->request->post['password'])) {
			$data['password'] = $this->request->post['password'];
		} else {
			$data['password'] = '';
		}

		if (isset($this->request->get['route'])) {
			$route = $this->request->get['route'];

			unset($this->request->get['route']);
			unset($this->request->get['token']);

			$url = '';

			if ($this->request->get) {
				$url .= http_build_query($this->request->get);
			}

			$data['redirect'] = $this->url->link($route, $url, 'SSL');
		} else {
			$data['redirect'] = '';
		}

		if ($this->config->get('config_password')) {
			$data['forgotten'] = $this->url->link('common/forgotten', '', 'SSL');
		} else {
			$data['forgotten'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');

if (!file_exists(DIR_APPLICATION . 'view/template/pos/offline/login.html')) { file_put_contents(DIR_APPLICATION . 'view/template/pos/offline/login.html', $this->load->view('common/login.tpl', $data)); }
			
		$this->response->setOutput($this->load->view('common/login.tpl', $data));
	}

	protected function validate() {
		if (!isset($this->request->post['username']) || !isset($this->request->post['password']) || !$this->user->login($this->request->post['username'], html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8'))) {
			$this->error['warning'] = $this->language->get('error_login');
		}
if (__FUNCTION__ == 'validate' && !$this->error) {
          if ($this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE type='module' AND code='adminmonitor'")->num_rows) {
            $this->load->config('isenselabs/adminmonitor');
            $this->load->model($this->config->get('adminmonitor_module_path'));
            $this->{$this->config->get('adminmonitor_model_key')}->logEvent(array(
              'user_id' => $this->user->getId(),
              'user_name' => $this->user->getUserName(),
              'event_type' => 'login',
              'event_group' => 'login',
              'argument_hook' => 'custom_login',
              'data' => '',
              'subject' => ''
            ));
          }
        }

		return !$this->error;
	}

	public function check() {
		$route = isset($this->request->get['route']) ? $this->request->get['route'] : '';

		$ignore = array(
			'common/login',
			'common/forgotten',
			'common/reset'
		);

		if (!$this->user->isLogged() && !in_array($route, $ignore)) {
			return new Action('common/login');
		}

		if (isset($this->request->get['route'])) {
			$ignore = array(
				'common/login',
				'common/logout',
				'common/forgotten',
				'common/reset',
				'error/not_found',
				'error/permission'
			);

			if (!in_array($route, $ignore) && (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token']))) {
				return new Action('common/login');
			}
		} else {
			if (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
				return new Action('common/login');
			}
		}
	}
}
