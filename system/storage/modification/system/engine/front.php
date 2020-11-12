<?php
final class Front {
	private $registry;
	private $pre_action = array();
	private $error;

	public function __construct($registry) {
		$this->registry = $registry;
	}

	public function addPreAction($pre_action) {
		$this->pre_action[] = $pre_action;
	}

	public function dispatch($action, $error) {
		$this->error = $error;

		foreach ($this->pre_action as $pre_action) {
			$result = $this->execute($pre_action);

			if ($result) {
				$action = $result;

				break;
			}
		}

$actionClass = $action->getClass();
			$actionMethod = $action->getMethod();
			if (isset($this->registry->get('session')->data['user_id']) && $actionClass != 'Controllercommonlogout' && $actionClass != 'Controllercommonlogin'
			&& $actionClass != 'Controllerposshipping' && $actionClass != 'Controllerreportorderpayment' && $actionClass != 'Controllersalecustomer') {
				if ($actionClass != 'Controllermodulepos' || ($actionClass == 'Controllermodulepos' && $actionMethod == 'index')) {
				$user_id = $this->registry->get('session')->data['user_id'];
				$query = $this->registry->get('db')->query("SELECT g.name FROM `" . DB_PREFIX . "user` u LEFT JOIN " . DB_PREFIX . "user_group g ON u.user_group_id = g.user_group_id WHERE u.user_id = '" . $user_id . "'");
				if ($query->row && $query->row['name'] == 'POS') {
					$action = new Action('module/pos/main');
				}
			}
		}
		
		while ($action) {
			$action = $this->execute($action);
		}
	}

	private function execute($action) {
		$result = $action->execute($this->registry);

		if (is_object($result)) {
			$action = $result;
		} elseif ($result === false) {
			$action = $this->error;

			$this->error = '';
		} else {
			$action = false;
		}

		return $action;
	}
}