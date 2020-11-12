<?php
if (!defined('VERSION')) {
	die('<h2>Opencart version not defined.</h2><br>Please add following code "define(\'VERSION\', \'<i>your opencart version here</i>\');" in ' . __DIR__ . 'index.php without quotes');
}
/**
 * default opencart framework code
 */
if (version_compare(VERSION, '2.3', '<')):
	// Registry
	$registry = new Registry();

	// Loader
	$loader = new Loader($registry);
	$registry->set('load', $loader);

	// Config
	$config = new Config();
	$config->load('default');
	$config->load($application_config);
	$registry->set('config', $config);

	// Request
	$registry->set('request', new Request());

	// Response
	$response = new Response();
	$response->addHeader('Content-Type: text/html; charset=utf-8');
	$registry->set('response', $response);

	// Database
	if ($config->get('db_autostart')) {
		$registry->set('db', new DB($config->get('db_type'), $config->get('db_hostname'), $config->get('db_username'), $config->get('db_password'), $config->get('db_database'), $config->get('db_port')));
	}

	// Session
	if ($config->get('session_autostart')) {
		$session = new Session();
		$session->start();
		$registry->set('session', $session);
	}

	// Cache
	$registry->set('cache', new Cache($config->get('cache_type'), $config->get('cache_expire')));

	// Url
	$registry->set('url', new Url($config->get('site_ssl')));

	// Language
	$language = new Language($config->get('language_default'));
	$language->load($config->get('language_default'));
	$registry->set('language', $language);

	// Document
	$registry->set('document', new Document());

	// Event
	$event = new Event($registry);
	$registry->set('event', $event);

	// Event Register
	if ($config->has('action_event')) {
		foreach ($config->get('action_event') as $key => $value) {
			$event->register($key, new Action($value));
		}
	}

	// Config Autoload
	if ($config->has('config_autoload')) {
		foreach ($config->get('config_autoload') as $value) {
			$loader->config($value);
		}
	}

	// Language Autoload
	if ($config->has('language_autoload')) {
		foreach ($config->get('language_autoload') as $value) {
			$loader->language($value);
		}
	}

	// Library Autoload
	if ($config->has('library_autoload')) {
		foreach ($config->get('library_autoload') as $value) {
			$loader->library($value);
		}
	}

	// Model Autoload
	if ($config->has('model_autoload')) {
		foreach ($config->get('model_autoload') as $value) {
			$loader->model($value);
		}
	}

	$db = $registry->get('db');
	$session = $registry->get('session');
	$config = $registry->get('config');

	if (version_compare(VERSION, '2.2', '>=')) {
		$registry->set('currency', new Cart\Currency($registry));
	} else {
		$registry->set('currency', new Currency($registry));
	}

		$currency_data = array();

		$query = $registry->get('db')->query("SELECT * FROM " . DB_PREFIX . "currency ORDER BY title ASC");

		foreach ($query->rows as $result) {
			$currency_data[$result['code']] = array(
				'currency_id'   => $result['currency_id'],
				'title'         => $result['title'],
				'code'          => $result['code'],
				'symbol_left'   => $result['symbol_left'],
				'symbol_right'  => $result['symbol_right'],
				'decimal_place' => $result['decimal_place'],
				'value'         => $result['value'],
				'status'        => $result['status'],
				'date_modified' => $result['date_modified']
			);
		}

	$code = '';

	$currencies = $currency_data;

	if (isset($session->data['currency'])) {
		$code = $session->data['currency'];
	}

	if (!array_key_exists($code, $currencies)) {
		$code = $db->query("SELECT * FROM `" . DB_PREFIX . "setting` where `key`='config_currency'")->row['value'];
	}

	if (!isset($session->data['currency']) || $session->data['currency'] != $code) {
		$session->data['currency'] = $code;
	}

	// Front Controller
	$controller = new Front($registry);

	// Pre Actions
	if ($config->has('action_pre_action')) {
		foreach ($config->get('action_pre_action') as $value) {
			$controller->addPreAction(new Action($value));
		}
	}

	// Dispatch
	$controller->dispatch(new Action($config->get('action_router')), new Action($config->get('action_error')));

	// Output
	$response->setCompression($config->get('config_compression'));
	$response->output();
else:
	global $registry;
	// Registry
	$registry = new Registry();

	// Config
	$config = new Config();
	$config->load('default');
	$config->load($application_config);
	$registry->set('config', $config);

	// Event
	$event = new Event($registry);
	$registry->set('event', $event);

	// Event Register
	if ($config->has('action_event')) {
		foreach ($config->get('action_event') as $key => $value) {
			$event->register($key, new Action($value));
		}
	}

	// Loader
	$loader = new Loader($registry);
	$registry->set('load', $loader);

	// Request
	$registry->set('request', new Request());

	// Response
	$response = new Response();
	$response->addHeader('Content-Type: text/html; charset=utf-8');
	$registry->set('response', $response);

	// Database
	if ($config->get('db_autostart')) {
		$registry->set('db', new DB($config->get('db_type'), $config->get('db_hostname'), $config->get('db_username'), $config->get('db_password'), $config->get('db_database'), $config->get('db_port')));
	}

	// Session
	$session = new Session();

	if ($config->get('session_autostart')) {
		$session->start();
	}

	$registry->set('session', $session);

	// Cache
	$registry->set('cache', new Cache($config->get('cache_type'), $config->get('cache_expire')));

	// Url
	if ($config->get('url_autostart')) {
		$registry->set('url', new Url($config->get('site_base'), $config->get('site_ssl')));
	}

	// Language
	$language = new Language($config->get('language_default'));
	$language->load($config->get('language_default'));
	$registry->set('language', $language);

	// Document
	$registry->set('document', new Document());

	// Config Autoload
	if ($config->has('config_autoload')) {
		foreach ($config->get('config_autoload') as $value) {
			$loader->config($value);
		}
	}

	// Language Autoload
	if ($config->has('language_autoload')) {
		foreach ($config->get('language_autoload') as $value) {
			$loader->language($value);
		}
	}

	// Library Autoload
	if ($config->has('library_autoload')) {
		foreach ($config->get('library_autoload') as $value) {
			$loader->library($value);
		}
	}

	// Model Autoload
	if ($config->has('model_autoload')) {
		foreach ($config->get('model_autoload') as $value) {
			$loader->model($value);
		}
	}

	// Front Controller
	$controller = new Front($registry);

	$db = $registry->get('db');
	$session = $registry->get('session');
	$config = $registry->get('config');
	if (version_compare(VERSION, '2.2', '>=')) {
		$registry->set('currency', new Cart\Currency($registry));
	} else {
		$registry->set('currency', new Currency($registry));
	}

		$currency_data = array();

		$query = $registry->get('db')->query("SELECT * FROM " . DB_PREFIX . "currency ORDER BY title ASC");

		foreach ($query->rows as $result) {
			$currency_data[$result['code']] = array(
				'currency_id'   => $result['currency_id'],
				'title'         => $result['title'],
				'code'          => $result['code'],
				'symbol_left'   => $result['symbol_left'],
				'symbol_right'  => $result['symbol_right'],
				'decimal_place' => $result['decimal_place'],
				'value'         => $result['value'],
				'status'        => $result['status'],
				'date_modified' => $result['date_modified']
			);
		}

	$code = '';

	$currencies = $currency_data;

	if (isset($session->data['currency'])) {
		$code = $session->data['currency'];
	}

	if (!array_key_exists($code, $currencies)) {
		$code = $db->query("SELECT * FROM `" . DB_PREFIX . "setting` where `key`='config_currency'")->row['value'];
	}

	if (!isset($session->data['currency']) || $session->data['currency'] != $code) {
		$session->data['currency'] = $code;
	}

	// Pre Actions
	if ($config->has('action_pre_action')) {
		foreach ($config->get('action_pre_action') as $value) {
			$controller->addPreAction(new Action($value));
		}
	}

	// Dispatch
	$controller->dispatch(new Action($config->get('action_router')), new Action($config->get('action_error')));

	// Output
	$response->setCompression($config->get('config_compression'));
	$response->output();
endif;
