<?php
abstract class Controller {
	protected $registry;
	
	public function __construct($registry) {
		$this->registry = $registry;
	}

	public function __get($key) {
		return $this->registry->get($key);
	}

	public function __set($key, $value) {
		$this->registry->set($key, $value);
	}
	
	protected function forward($route, $args = array()) {
        return new Action($route, $args);
    }

    //protected function redirect($url, $status = 302) {
    protected function redirect($url, $status = 301) {
        header('Status: ' . $status);
        header('Location: ' . str_replace(array('&amp;', "\n", "\r"), array('&', '', ''), $url));
        exit();
    }

    protected function getChild($child, $args = array()) {
        $action = new Action($child, $args);

        if (file_exists($action->getFile())) {
            require_once(\VQMod::modCheck($action->getFile()));

            $class = $action->getClass();

            $controller = new $class($this->registry);

            $controller->{$action->getMethod()}($action->getArgs());

            return $controller->output;
        } else {

            trigger_error('Error: Could not load controller ' . $action . '!');
            exit();
        }
    }

    protected function hasAction($child, $args = array()) {
        $action = new Action($child, $args);

        if (file_exists($action->getFile())) {
            require_once(\VQMod::modCheck($action->getFile()));

            $class = $action->getClass();

            $controller = new $class($this->registry);

            if (method_exists($controller, $action->getMethod())) {
                return true;
            } else {
                return false;
            }
        } else {
            trigger_error('Error: Could not load controller ' . $child . '!');
            exit();
        }
    }

    protected function render() {
        foreach ($this->children as $child) {
            $data[basename($child)] = $this->getChild($child);
        }

        if (file_exists(DIR_TEMPLATE . $this->template)) {
            extract($data);

            ob_start();

            require(VQMod::modCheck(DIR_TEMPLATE . $this->template));

            $this->output = ob_get_contents();

            ob_end_clean();
            return $this->compressPage($this->output);
        } else {
            trigger_error('Error: Could not load template ' . DIR_TEMPLATE . $this->template . '!');
            exit();
        }
    }
    function compressPage($content) {
        $content=preg_replace('/>\s+</','><', $content);
        return trim($content);
    }
}